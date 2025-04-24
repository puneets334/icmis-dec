<?php
#C:\xampp\htdocs\supremecourt_core\Copying\application\models\Recall_model.php
namespace App\Models\Judicial;

use CodeIgniter\Model;

class RecallModel extends Model
{
    function case_types()
    {
        // Prepare the query
        $builder = $this->db->table("master.casetype");
        $builder->where('casecode!=9999');
        $builder->where('is_deleted', 'f');
        $builder->orderBy('casecode', 'ASC');
        $query = $builder->get();

        return $query->getResultArray();
    }

    function get_case_details($case_type = 0, $case_number = 0, $case_year = 0, $diary_number = 0, $diary_year = 0)
    {
        // Initialize the condition variable
        $condition = '';

        // Build the condition based on input parameters
        if ($case_type != 0 && $case_number != 0 && $case_year != 0) {
            $condition = "CAST(SUBSTRING(active_fil_no FROM 1 FOR 2) AS INTEGER) = $case_type AND
                        active_reg_year = $case_year AND
                        (CAST(SUBSTRING(active_fil_no FROM 4 FOR 6) AS INTEGER) = $case_number OR
                        $case_number BETWEEN CAST(SUBSTRING(active_fil_no FROM 4 FOR 6) AS INTEGER) AND
                        CAST(SUBSTRING(active_fil_no FROM 11 FOR 6) AS INTEGER))";
        } elseif ($diary_number != 0 && $diary_year != 0) {
            $condition = "m.diary_no =" . $diary_number . $diary_year;
        }

        // Construct the SQL query
        $sql = "SELECT m.diary_no AS case_diary,
                    c_status,
                    CONCAT(pet_name, ' Vs ', res_name) AS case_title,
                    TO_CHAR(ord_dt, 'DD-MM-YYYY') AS disp_date
                FROM public.main_a m
                LEFT JOIN public.dispose d ON d.diary_no = m.diary_no
                WHERE $condition;";
        
        // echo $sql; die;
        
        // Prepare the query
        $query = $this->db->query($sql);

        // Check the number of rows and return results
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    function checkForRecall($case_diary, $usercode)
    {
        // Prepare the SQL query with PostgreSQL functions
        $sql_da = "SELECT STRING_AGG(code, ',') AS code,
                        STRING_AGG(username, ',') AS name
                FROM (
                    SELECT COALESCE(m.dacode::text, '') AS code,
                            CONCAT(COALESCE(u.name, ''), '(', u.empid, ')', '-', us.section_name) AS username
                    FROM public.main_a m
                    LEFT JOIN master.users u ON m.dacode = u.usercode
                    LEFT JOIN master.usersection us ON us.id = u.section
                    WHERE m.diary_no = ?
                    UNION
                    SELECT COALESCE(u.usercode::text, '') AS code,
                            CONCAT(COALESCE(u.name, ''), '(', u.empid, ')', '-', us.section_name) AS username
                    FROM public.dispose d
                    LEFT JOIN master.users u ON d.usercode = u.usercode
                    LEFT JOIN master.usersection us ON us.id = u.section
                    WHERE d.diary_no = ?
                    UNION
                    SELECT bo.usercode::text AS code,
                            CONCAT(bo.name, '(BO)(', bo.empid, ')', '-', us.section_name) AS username
                    FROM public.main_a m
                    LEFT JOIN master.users bo ON m.section_id = bo.section AND bo.usertype = 14 AND bo.display = 'Y'
                    LEFT JOIN master.usersection us ON us.id = bo.section
                    WHERE m.diary_no = ?
                    UNION
                    SELECT ar.usercode::text AS code,
                            CONCAT(ar.name, '(AR)(', ar.empid, ')', '-', us.section_name) AS username
                    FROM public.main_a m
                    LEFT JOIN master.users ar ON m.section_id = ar.section AND ar.usertype = 9 AND ar.display = 'Y'
                    LEFT JOIN master.usersection us ON us.id = ar.section
                    WHERE m.diary_no = ?
                ) AS combined";

        // Execute the query with parameter binding
        $query = $this->db->query($sql_da, [$case_diary, $case_diary, $case_diary, $case_diary]);

        // Fetch results
        $users = $query->getRowArray();
        $recall_users = $users['code'];

        // Check if the usercode is in the recall list or matches specific codes
        if (strpos($recall_users, (string)$usercode) !== false || in_array($usercode, [1217, 770])) {
            return 1;
        } else {
            return $users;
        }
    }


    function checkForRecallOld($case_diary, $usercode)
    {
        $sql_da = "select group_concat(code) as code,group_concat(username) as name from(
                     Select IF(m.dacode IS NULL, '', m.dacode) AS code, concat(IF(u.name IS NULL, '', u.name),'(',u.empid,')','-',section_name) AS username  from main m LEFT JOIN users u ON m.dacode=u.usercode left join usersection us on us.id=u.section where m.diary_no=$case_diary
                     union
                     select IF(u.usercode IS NULL, '', u.usercode) AS code, concat(IF(u.name IS NULL, '', u.name),'(',u.empid,')','-',section_name) AS username from dispose d LEFT JOIN users u ON d.usercode=u.usercode left join usersection us on us.id=u.section where d.diary_no=$case_diary
                     union
                     select bo.usercode as code,concat(bo.name,'(BO)(',bo.empid,')-',section_name) as username from main m left join users bo on m.section_id=bo.section and usertype=14 and display='Y' left join usersection bo_section on bo_section.id=bo.section where m.diary_no=$case_diary
                     union
                     select ar.usercode as code,concat(ar.name,'(AR)(',ar.empid,')-',section_name) as username from main m left join users ar on m.section_id=ar.section and usertype=9 and display='Y' left join usersection ar_section on ar_section.id=ar.section where m.diary_no=$case_diary)m";
        $result_da = $this->db->query($sql_da);
        $users = $result_da->result_array();
        $recall_users = $users[0]['code'];
        //if ((stristr($recall_users, $usercode)))
        if ((stristr($recall_users, $usercode)) or $usercode == 1217 or $usercode == 770)
            return 1;
        else
            return $users;
        //        }

    }

    function restore_archived_data($diary_no)
    {
        $output = [];

        // Call Recall Procedure
        try {
            $this->db->query("call restore_archived_data('$diary_no')");
            $output['success'] = 1;
            $output['message'] = "Case Recalled.";
        } catch (\Exception $e) {
            log_error("Case Recall Error [$diary_no]: ", $e->getMessage());
            $output['success'] = 0;
            $output['message'] = "There is some problem. Please contact Computer-Cell.";
        }

        return $output;
    }

    function update_case($case_diary, $usercode, $reason, $reason_option)
    {
        // Restore Data from archived tables
        $this->restore_archived_data($case_diary);

        // Clean the reason input
        $reason = preg_replace("/[^a-zA-Z\d\s\.]/", "", $reason);
        
        // Get client IP (this assumes you have a method to get it)
        $client_ip = $this->get_client_ip();

        // Prepare queries
        
        $this->db->transStart();

        // Prepare the SQL query
        $sql = "UPDATE public.main 
                SET c_status = 'P', 
                    lastorder = '', 
                    last_usercode = ?, 
                    last_dt = CURRENT_TIMESTAMP(0) 
                WHERE diary_no = ?";

        // Execute the query with binding parameters to prevent SQL injection
        $this->db->query($sql, [
            $usercode,       // Bind value for last_usercode
            $case_diary      // Bind value for diary_no
        ]);

        // Prepare the SQL query
        $sql = "INSERT INTO public.recalled_matters 
                    (diary_no, ord_dt, disp_dt, disp_type, updated_on, updation_reason, updated_by, updated_by_ip, court_or_user)
                SELECT diary_no, ord_dt, disp_dt, disp_type, CURRENT_TIMESTAMP(0), ?, ?, ?, ?
                FROM dispose 
                WHERE diary_no = ?";

        // Execute the query with binding parameters to prevent SQL injection
        $this->db->query($sql, [
            $reason,         // Bind value for updation_reason
            $usercode,       // Bind value for updated_by
            $client_ip,      // Bind value for updated_by_ip
            $reason_option,  // Bind value for court_or_user
            $case_diary      // Bind value for diary_no
        ]);

        // Prepare the SQL query
        $sql = "INSERT INTO public.dispose_delete 
                    (diary_no, fil_no, month, dispjud, year, ord_dt, disp_dt, disp_type, bench, jud_id, camnt, crtstat, usercode, ent_dt, jorder, rj_dt, disp_type_all, entered_on, dispose_updated_by)
                SELECT diary_no, fil_no, month, dispjud, year, ord_dt, disp_dt, disp_type, bench, jud_id, camnt, crtstat, usercode, ent_dt, jorder, rj_dt, disp_type_all, CURRENT_TIMESTAMP, ?
                FROM dispose 
                WHERE diary_no = ?";

        // Execute the query with binding parameters to prevent SQL injection
        $this->db->query($sql, [
            $usercode,       // Bind value for dispose_updated_by
            $case_diary      // Bind value for diary_no
        ]);

        $this->db->table('dispose')
            ->where('diary_no', $case_diary)
            ->delete();
        
        $this->db->transComplete();

        if ($this->db->transStatus() === true) {
            echo "Case Recalled.";
        } else {
            echo "There is some problem. Please contact Computer-Cell.";
        }

        return;
    }
}
