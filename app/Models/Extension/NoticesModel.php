<?php

namespace App\Models\Extension;

use CodeIgniter\Model;
use DateTime;

class NoticesModel extends Model
{


    public function __construct()
    {
        parent::__construct();

        $db = \Config\Database::connect();
    }

    public function get_diary_case_type($ct, $cn, $cy)
    {
        if ($ct !== '') {
            // First query
            $builder = $this->db->table('main');
            $builder->select("SUBSTR(diary_no, 1, LENGTH(diary_no) - 4) as dn, SUBSTR(diary_no, -4) as dy");
            $builder->where("SUBSTRING_INDEX(fil_no, '-', 1)", $ct);
            $builder->where("$cn BETWEEN CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no, '-', 2),'-',-1) AS UNSIGNED) AND CAST(SUBSTRING_INDEX(fil_no, '-', -1) AS UNSIGNED)", null, false);
            $builder->where("(reg_year_mh = 0 OR fil_dt > '2017-05-10')", null, false);
            $builder->where("YEAR(fil_dt) = $cy OR reg_year_mh = $cy", null, false);

            $query = $builder->get();

            if ($query->getNumRows() > 0) {
                $result = $query->getRowArray();
                return $result['dn'] . $result['dy'];
            }

            // Second query (if the first one returns no results)
            $builder = $this->db->table('main_casetype_history h');
            $builder->select("SUBSTR(h.diary_no, 1, LENGTH(h.diary_no) - 4) as dn, SUBSTR(h.diary_no, -4) as dy");
            $builder->where("SUBSTRING_INDEX(h.new_registration_number, '-', 1) = $ct AND CAST($cn AS UNSIGNED) BETWEEN CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2), '-', -1) AS UNSIGNED) AND CAST(SUBSTRING_INDEX(h.new_registration_number, '-', -1) AS UNSIGNED) AND h.new_registration_year = $cy", null, false);
            $builder->orWhere("SUBSTRING_INDEX(h.old_registration_number, '-', 1) = $ct AND CAST($cn AS UNSIGNED) BETWEEN CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(h.old_registration_number, '-', 2), '-', -1) AS UNSIGNED) AND CAST(SUBSTRING_INDEX(h.old_registration_number, '-', -1) AS UNSIGNED) AND h.old_registration_year = $cy", null, false);
            $builder->where('h.is_deleted', 'f');

            $query = $builder->get();

            if ($query->getNumRows() > 0) {
                $result = $query->getRowArray();
                return $result['dn'] . $result['dy'];
            }
        }

        return null; // If no records found, return null
    }

    public function navigate_diary($dno)
    {
        // Load session
        $session = session();

        // Build the query using Query Builder
        $builder = $this->db->table('main m');
        $builder->select('m.diary_no, c1.short_description, m.active_reg_year, m.active_fil_no, 
                          m.pet_name, m.res_name, m.pno, m.rno, m.diary_no_rec_date, 
                          m.active_fil_dt, m.lastorder, m.c_status');
        $builder->join('master.casetype c1', 'm.active_casetype_id = c1.casecode', 'left');
        $builder->where('m.diary_no', $dno);

        // Execute the query and get the result
        $query = $builder->get();
        $row = $query->getRowArray();

        if (!empty($row)) {
            $filno_array = explode("-", $row['active_fil_no']);
            if (empty($filno_array[0])) {
                $fil_no_print = "Unreg.";
            } else {
                $fil_no_print = $row['short_description'] . "/" . ltrim($filno_array[1], '0');
                if (!empty($filno_array[2]) && $filno_array[1] != $filno_array[2]) {
                    $fil_no_print .= "-" . ltrim($filno_array[2], '0');
                }
                $fil_no_print .= "/" . $row['active_reg_year'];
            }

            $cstatus = ($row['c_status'] === "P") ? "Pending" : "Disposed";

            // Set session data
            $session->set([
                'session_c_status'       => $cstatus,
                'session_pet_name'       => $row['pet_name'],
                'session_res_name'       => $row['res_name'],
                'session_lastorder'      => $row['lastorder'],
                'session_diary_recv_dt'  => !empty($row['diary_no_rec_date']) ? date('d-m-Y H:i:s', strtotime($row['diary_no_rec_date'])) : null,
                'session_active_fil_dt'  => !empty($row['active_fil_dt']) ? date('d-m-Y H:i:s', strtotime($row['active_fil_dt'])) : null,
                'session_diary_no'       => substr($dno, 0, -4),
                'session_diary_yr'       => substr($dno, -4),
                'session_active_reg_no'  => $fil_no_print,
            ]);
        }
    }

    public function getDiaryStatus($diary_no)
    {
        // Build the query using Query Builder
        $builder = $this->db->table('main');
        $builder->select('dacode, c_status');
        $builder->where('diary_no', $diary_no);

        // Execute the query and get the result
        $query = $builder->get();
        return $query->getRowArray();

        // Check if a result exists
        if ($result) {
            $dacode = $result['dacode'];
            $status = $result['c_status'];
            return ['dacode' => $dacode, 'status' => $status];
        } else {
            return ['dacode' => null, 'status' => null];
        }
    }

    public function get_next_working_date_new($dt, $head_no, $mf)
    {
        // Initialize variables
        $start = strtotime($dt);
        $t_var = '';
        $cdate1 = '';
        $ivar = 0;

        if ($head_no != 24 && $head_no != 180) {
            while ($cdate1 == '') {
                // Create date range for the next 15 days in batches
                $t_loop = $ivar + 15;
                for ($ivar; $ivar < $t_loop; $ivar++) {
                    if ($t_var == '') {
                        $t_var .= "SELECT '" . date('Y-m-d', strtotime("+" . $ivar . " day", $start)) . "' as cdates";
                    } else {
                        $t_var .= " UNION SELECT '" . date('Y-m-d', strtotime("+" . $ivar . " day", $start)) . "' as cdates";
                    }
                }

                // Prepare the SQL query with Query Builder's custom query method
                $sql = "
                SELECT 
                    *,
                    CASE
                        WHEN (wd = 0) THEN cdates
                    END AS c1,
                    CASE
                        WHEN (wk = 1) THEN MAX(cdates)
                        ELSE MIN(cdates)
                    END AS c2,
                    MIN(CASE
                        WHEN (wd = 1 OR wd = 2 OR wd = 3) THEN cdates
                    END) AS r1
                FROM (
                    SELECT 
                        t1.cdates,
                        WEEKDAY(t1.cdates) AS wd,
                        WEEK(t1.cdates) - WEEK('" . $dt . "') + 1 AS wk
                    FROM (
                        " . $t_var . "
                    ) t1 
                    LEFT JOIN master.holidays t2 ON t1.cdates = t2.hdate 
                    WHERE WEEKDAY(t1.cdates) NOT IN (5, 6) 
                        AND t2.hdate IS NULL
                ) z1 
                GROUP BY z1.wk
            ";

                // Execute the query
                $query = $this->db->query($sql);
                $results = $query->getResultArray();

                // Process the results
                foreach ($results as $row) {
                    if ($mf == 'F') {
                        if (!is_null($row['r1']) && $cdate1 == '') {
                            $cdate1 = $row['r1'];
                        }
                    } else {
                        if (!is_null($row['c1']) && $cdate1 == '') {
                            $cdate1 = $row['c1'];
                        }
                        if (!is_null($row['c2']) && $cdate1 == '') {
                            $cdate1 = $row['c2'];
                        }
                    }
                }
            }
        } else {
            $cdate1 = $dt;
        }

        // Return the final date
        return date('Y-m-d', strtotime($cdate1));
    }

    public function get_res_cont($dairy_no, $date)
    {
        $builder = $this->db->table('tw_tal_del');
        $builder->where('diary_no', $dairy_no);
        $builder->where('rec_dt', $date);
        $builder->where('display', 'Y');
        $builder->selectCount('id', 'count');
        $query = $builder->get();
        $res_cont = $query->getRow();
        return $res_cont ? $res_cont->count : 0;
    }

    public function res_sq_fi_sub($dairy_no, $date)
    {
        $builder = $this->db->table('tw_tal_del');
        $builder->where('diary_no', $dairy_no);
        $builder->where('rec_dt', $date);
        $builder->where('display', 'Y');
        $builder->selectCount('id', 'count');
        $builder->where('print', 0);
        $builder->limit(1);
        $query = $builder->get();
        $res_sq_fi_sub = $query->getRowArray();
        return $res_sq_fi_sub;
    }

    public function getResAdvNm($dairy_no)
    {
        $builder = $this->db->table('main a');
        $builder->select('a.*, c.name res_adv_nm');
        $builder->join('master.bar b', 'b.bar_id = a.pet_adv_id', 'left');
        $builder->join('master.bar c', 'c.bar_id = a.res_adv_id', 'left');
        $builder->where('a.diary_no', $dairy_no);
        $query = $builder->get();
        $row = $query->getRowArray();
        return $row;
    }


    public function geTwTalDel($dairy_no, $date)
    {
        $builder = $this->db->table('tw_tal_del');
        $builder->where('diary_no', $dairy_no);
        $builder->where('rec_dt', $date);
        $builder->where('display', 'Y');
        $builder->select('count(id) as count');
        $query = $builder->get();
        $result = $query->getRow();
        return $result ? $result->count : 0;
    }


    public function geResSqFiSub($dairy_no, $date)
    {
        $builder = $this->db->table('tw_tal_del');
        $builder->where('diary_no', $dairy_no);
        $builder->where('rec_dt', $date);
        $builder->where('display', 'Y');
        $builder->select('count(id) as count');
        $builder->limit(1);
        $builder->where('print', 0);
        $query = $builder->get();
        $res_sq_fi_sub = $query->getRowArray();
        return $res_sq_fi_sub;
    }

    public function get_max_dt($dm_fno, $var_st)
    {
        // The SQL query
        $sql = "SELECT dcrm.*, dcrm1.cl_date, dcrm1.jcodes, STRING_AGG(dcrm1.r_head::TEXT, ', ') AS r_head
        FROM (
            SELECT MAX(cl_date) AS cl, diary_no
            FROM case_remarks_multiple
            WHERE diary_no = ? $var_st
            GROUP BY diary_no
        ) AS dcrm
        JOIN case_remarks_multiple dcrm1 
        ON dcrm.diary_no = dcrm1.diary_no
        AND dcrm.cl = dcrm1.cl_date
        GROUP BY dcrm.diary_no, dcrm.cl, dcrm1.cl_date, dcrm1.jcodes";

        $query = $this->db->query($sql, [$dm_fno]);

        $result = $query->getRowArray();



        if ($result) {
            $rs_sq_s = explode(',', $result['r_head']);
            $ck_jcodes = 0;

            foreach ($rs_sq_s as $index => $r_head) {
                $ck_jcodes = 1;
                break;
            }

            if ($ck_jcodes == 1) {
                return $result['cl_date'];
            } else {
                // Adjust the condition for the recursive call
                $var_st = "and cl_date < '" . $result['cl_date'] . "'";
                return $this->get_max_dt($dm_fno, $var_st);
            }
        }

        return null;  // In case no data is found
    }

    public function getCaseRemarksMultiple($dairy_no, $ret_res)
    {
        // Prepare the SQL query
        $builder = $this->db->table('case_remarks_multiple');
        // $builder->select("count(diary_no) as ct_fn, GROUP_CONCAT(CAST(r_head AS CHAR)) as r_head");
        $builder->select("count(diary_no) as ct_fn, STRING_AGG(CAST(r_head AS TEXT), ',') as r_head");
        $builder->where('diary_no', $dairy_no);
        $builder->whereIn('r_head', ['90', '91', '9', '10', '117', '62', '11', '60', '74', '75', '65', '2', '1', '94', '3', '4', '96', '57', '93', '59', '24', '21', '23', '8', '12', '20', '53', '54', '68', '131', '149', '113', '181']);
        $builder->where('cl_date', $ret_res);
        $query = $builder->get();
        $result = $query->getRowArray();

        return $result;
    }

    public function case_remarks_multiple($dairy_no, $ret_res)
{
    $sqy_pf = $this->db->query(
        "
        SELECT TO_CHAR(TO_DATE(CONCAT(SUBSTR(head_content, 7, 4), '-', 
        SUBSTR(head_content, 4, 2), '-', SUBSTR(head_content, 1, 2)), 'YYYY-MM-DD'), 'YYYY-MM-DD') AS head_content
        FROM case_remarks_multiple
        WHERE diary_no = ? AND r_head IN ('24', '21', '59', '91', '131') AND cl_date = ?",
        [$dairy_no, $ret_res]
    )->getRow();

    return $sqy_pf;
}


    public function get_heardt($dairy_no)
    {
        $sqy_pf = $this->db->query("SELECT tentative_cl_dt FROM heardt WHERE diary_no = ?", [$dairy_no])->getRow();
        return $sqy_pf;
    }

    public function get_docdetails($dairy_no, $or_dt)
    {
        $sql_pf = $this->db->query(
            "
            SELECT DATE_FORMAT(ent_dt, '%Y-%m-%d') AS dt 
            FROM docdetails 
            WHERE display = 'Y' 
            AND diary_no = ? 
            AND ((doccode = '7' AND doccode1 = '0') OR (doccode = '29' AND doccode1 = '0')) 
            AND DATE_FORMAT(ent_dt, '%Y-%m-%d') >= ?
            ORDER BY docyear DESC, ent_dt ASC LIMIT 1",
            [$dairy_no, $or_dt]
        )->getRow();
        return $sql_pf;
    }

    public function get_sq_con_not($dairy_no)
    {
        $sq_con_not = $this->db->query(
            "
            SELECT next_dt, mainhead 
            FROM heardt 
            WHERE diary_no = ?",
            [$dairy_no]
        )->getRowArray();
        return $sq_con_not;
    }

    public function get_r_bx($dairy_no, $sq_con_not)
    {
        $r_bx = $this->db->query(
            "
            SELECT a.* 
            FROM (
                SELECT diary_no, conn_key 
                FROM main 
                WHERE conn_key = (
                    SELECT conn_key 
                    FROM main 
                    WHERE diary_no = ?
                ) 
                AND conn_key IS NOT NULL 
                AND conn_key != ''
            ) a
            JOIN heardt b ON a.diary_no = b.diary_no 
            WHERE next_dt = ?",
            [$dairy_no, $sq_con_not['next_dt']]
        )->getResultArray();
        return $r_bx;
    }

    public function get_sqy_pf($conn_main_cs)
    {
        $sqy_pf = $this->db->query("SELECT tentative_cl_dt FROM heardt WHERE diary_no = ?", [$conn_main_cs])->getRow();
        return $sqy_pf;
    }

    public function get_sqy_pf2($conn_main_cs, $ret_res)
    {
        $sqy_pf = $this->db->query(
            "
            SELECT DATE_FORMAT(CONCAT(SUBSTR(head_content, 7, 4), '-', 
                SUBSTR(head_content, 4, 2), '-', SUBSTR(head_content, 1, 2)), '%Y-%m-%d') AS head_content
            FROM case_remarks_multiple 
            WHERE diary_no = ? 
            AND r_head IN ('24', '21', '59', '91', '131') 
            AND cl_date = ?",
            [$conn_main_cs, $ret_res]
        )->getRow();
        return $sqy_pf;
    }

    public function get_tentative_cl_dt($dairy_no)
    {
        $sqy_pf = $this->db->query(
            "
            SELECT tentative_cl_dt 
            FROM heardt 
            WHERE diary_no = ?",
            [$dairy_no]
        )->getRow();
        return $sqy_pf;
    }

    public function get_tentative_cl_dt2($dairy_no, $ret_res)
    {
        $sqy_pf = $this->db->query(
            "
            SELECT DATE_FORMAT(CONCAT(SUBSTR(head_content, 7, 4), '-', 
                SUBSTR(head_content, 4, 2), '-', SUBSTR(head_content, 1, 2)), '%Y-%m-%d') AS head_content
            FROM case_remarks_multiple 
            WHERE diary_no = ? 
            AND r_head IN ('24', '21', '59', '91', '131') 
            AND cl_date = ?",
            [$dairy_no, $ret_res]
        )->getRow();
        return $sqy_pf;
    }

    public function get_sqy_pf_no_r($dairy_no)
    {
        $sqy_pf_no_r = $this->db->table('heardt')
            ->select('tentative_cl_dt')
            ->where('diary_no', $dairy_no)
            ->get()
            ->getRowArray();
        return $sqy_pf_no_r;
    }

    public function chksDate($dt)
    {
        // Query to check if the date exists in the holidays table
        $query = $this->db->table('master.holidays')
            ->select('COUNT(hdate) as date_count')
            ->where('hdate', $dt)
            ->get();

        $result = $query->getRow();

        if ($result->date_count > 0) {
            // If the date is a holiday, add 1 day and recheck
            $dt = date('Y-m-d', strtotime($dt . ' + 1 days'));
            return $this->chksDate($dt);  // Recursion to check the next date
        } else {
            // If not a holiday, return the date
            return $dt;
        }
    }

    public function get_display_status_with_date_differences($tentative_cl_dt)
    {
        // Initialize flag
        $tentative_cl_date_greater_than_today_flag = 'F';

        // Current date
        $curDate = new DateTime();

        // Convert tentative closing date to DateTime
        $tentativeCLDate = new DateTime($tentative_cl_dt);

        // Calculate the difference in days
        $noofdays = $curDate->diff($tentativeCLDate)->days;

        // Check if the tentative closing date is in the future
        if ($tentativeCLDate > $curDate && $noofdays <= 60) {
            $tentative_cl_date_greater_than_today_flag = 'T';
        }

        return $tentative_cl_date_greater_than_today_flag;
    }

    public function checkMultipleRecords($diary_no, $date)
    {
        // Prepare the query
        $builder = $this->db->table('tw_tal_del');
        $builder->where('diary_no', $diary_no);
        $builder->where('rec_dt', $date);
        $builder->where('display', 'Y');
        $builder->where('print', 0);

        // Get the count of records
        $count = $builder->countAllResults();

        // Set the status based on the count
        $ck_mul_re_st = ($count > 0) ? '1' : '0';

        return $ck_mul_re_st;
    }

    public function getCaseAndSectionDetails($row)
    {
        $builder = $this->db->table('master.casetype');
        $builder->select('nature, skey');
        $builder->where('casecode', $row['casetype_id']);
        $query = $builder->get();
        // $result = $query->getResult();
        $result = $query->getRow();
        return $result;
    }

    public function getCaseAndSectionDetails2($res_skey)
    {
        $builder = $this->db->table('master.tw_section');
        $builder->select('id');
        $builder->where('name', $res_skey);
        $query = $builder->get();
        // Fetching the result
        $result = $query->getRow();
        return $result;
    }

    public function getNotice($str, $res_section, $n_status, $casetype_id)
    {
        // Determine the value for $nt based on $str
        $nt = '';
        if ($str == 'C' || $str == 'W') {
            $nt = 'Y';
        } else if ($str == 'R') {
            $nt = 'Z';
        }
        $builder = $this->db->table('master.tw_notice'); // Directly specify the table name

        // Build the query using Query Builder
        $builder->select('id, name');
        $builder->where('display', 'Y');

        if ($casetype_id != '39') {
            $builder->groupStart()
                ->where('nature', $str)
                ->orWhere('nature', '')
                ->orWhere('nature', $nt)
                ->groupEnd();
        }

        $builder->groupStart()
            ->where('section', $res_section)
            ->orWhere('section', '0')
            ->groupEnd();

        $builder->groupStart()
            ->where('notice_status', $n_status)
            ->orWhere('notice_status', '')
            ->groupEnd();

        $builder->orderBy('name');

        // Execute the query
        $query = $builder->get();
        $results = $query->getResultArray();

        // Format the results
        $notice = [];
        foreach ($results as $row) {
            $notice[] = $row['id'] . '^' . $row['name'];
        }

        return $notice;
    }

    public function getSendTo()
    {
        $builder = $this->db->table('master.tw_send_to');

        // Build the query
        $builder->select('id, desg');
        $builder->where('display', 'Y');

        // Execute the query
        $query = $builder->get();
        $results = $query->getResultArray();

        // Format the results
        $send_too = [];
        foreach ($results as $row) {
            $send_too[] = $row['id'] . '^' . $row['desg'];
        }

        return $send_too;
    }

    public function getState()
    {
        $builder = $this->db->table('master.state');

        // Build the query
        $builder->select('id_no AS State_code, name');
        $builder->where('district_code', '0');
        $builder->where('sub_dist_code', '0');
        $builder->where('village_code', '0');
        $builder->orderBy('name');

        // Execute the query
        $query = $builder->get();
        $results = $query->getResultArray();

        // Format the results
        $state = [];
        foreach ($results as $row) {
            $state[] = $row['State_code'] . "^" . $row['name'];
        }

        return $state;
    }

    public function get_sql_party($dairy_no, $date)
    {
        //     $sql = "
        //     SELECT null as id, partyname, addr1, addr2, sr_no_show as sr_no, pet_res, sonof, prfhname,
        //            null as nt_type, null as amount, state, city, null as enrol_no, null as enrol_yr
        //     FROM party  
        //     WHERE diary_no='$dairy_no' AND pflag='P' AND partyname IS NOT NULL AND partyname != ''

        //     UNION

        //     SELECT id, name as partyname, address as addr1, null as addr2, sr_no, pet_res, null as sonof, null as prfhname, nt_type,
        //            amount, CAST(tal_state AS TEXT) as state, CAST(tal_district AS TEXT) as city, enrol_no, enrol_yr
        //     FROM tw_tal_del
        //     WHERE diary_no='$dairy_no' AND rec_dt='$date' AND display='Y' AND sr_no='0' AND print=0

        //     ORDER BY CASE
        //         WHEN CAST(sr_no AS UNSIGNED) = 1 THEN -1
        //         WHEN CAST(sr_no AS UNSIGNED) > 1 AND pet_res='P' THEN 0
        //         WHEN CAST(sr_no AS UNSIGNED) > 1 AND pet_res='R' THEN 1
        //         WHEN CAST(sr_no AS UNSIGNED) = '0' THEN 2
        //         ELSE CAST(sr_no AS UNSIGNED)
        //     END, 
        //     CAST(SUBSTRING_INDEX(sr_no, '.', 1) AS UNSIGNED),
        //     CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(CONCAT(sr_no, '.0'), '.', 2), '.', -1) AS UNSIGNED),
        //     CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(CONCAT(sr_no, '.0.0'), '.', 3), '.', -1) AS UNSIGNED),
        //     CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(CONCAT(sr_no, '.0.0.0'), '.', 4), '.', -1) AS UNSIGNED), 
        //     pet_res
        // ";
        $sql = "
    SELECT null as id, partyname, addr1, addr2, sr_no_show as sr_no, pet_res, sonof, prfhname,
           null as nt_type, null as amount, state, city, null as enrol_no, null as enrol_yr
    FROM party  
    WHERE diary_no='$dairy_no' AND pflag='P' AND partyname IS NOT NULL AND partyname != ''
    
    UNION
    
    SELECT id, name as partyname, address as addr1, null as addr2, sr_no, pet_res, null as sonof, null as prfhname, nt_type,
           amount, CAST(tal_state AS TEXT) as state, CAST(tal_district AS TEXT) as city, enrol_no, enrol_yr
    FROM tw_tal_del
    WHERE diary_no='$dairy_no' AND rec_dt='$date' AND display='Y' AND sr_no='0' AND print=0
";
        // Execute the query
        $query = $this->db->query($sql);

        // Fetch results
        $results = $query->getResultArray();
        return $results;
    }

    public function get_tw_tal_del($dairy_no, $row1, $date)
    {
        $query = $this->db->query("
        SELECT id, name, address, nt_type, amount 
        FROM tw_tal_del 
        WHERE diary_no = '$dairy_no' 
          AND sr_no = '{$row1['sr_no']}' 
          AND pet_res = '{$row1['pet_res']}' 
          AND rec_dt = '$date' 
          AND display = 'Y' 
          AND print = 0
    ");
        return $query->getRowArray();
    }

    public function get_tw_o_r_s($ck_en_nt_x)
    {
        $builder = $this->db->table('tw_o_r');
        $builder->select('del_type');
        $builder->where('tw_org_id', $ck_en_nt_x['id']);
        $builder->where('display', 'Y');
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function getCities($state)
    {
        $subQuery = $this->db->table('master.state')
            ->select('state_code')
            ->where('id_no', $state)
            ->where('display', 'Y')
            ->getCompiledSelect();
        $builder = $this->db->table('master.state');
        $builder->select('id_no AS district_code, name');
        $builder->where("state_code = ($subQuery)", null, false);
        $builder->where('sub_dist_code', '0');
        $builder->where('district_code !=', 0);
        $builder->where('village_code', 0);
        $builder->orderBy('name');

        // Execute the query
        $query = $builder->get();

        // Fetch the result
        $row_c = $query->getResultArray();
        return $row_c;
    }
    public function getCitiesName($str)
    {
        $builder = $this->db->table('master.state');
        $builder->select('id_no, name');
        $builder->where('state_code', function ($builder) use ($str) {
            $builder->select('state_code')->from('master.state')->where('id_no', $str)->where('display', 'Y')->limit(1);
        });
        $builder->where('sub_dist_code', '0');
        $builder->where('district_code !=', 0);
        $builder->where('village_code', '0');
        $builder->where('display', 'Y');
        $builder->orderBy('name');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_tw_o_r($ck_en_nt_x)
    {
        $builder = $this->db->table('tw_o_r a');
        $builder->select('a.id, del_type, tw_sn_to, sendto_state, sendto_district, copy_type, send_to_type');
        $builder->join('tw_comp_not b', 'a.id = b.tw_o_r_id');
        $builder->where('tw_org_id', $ck_en_nt_x['id']);
        $builder->where('a.display', 'Y');
        $builder->where('b.display', 'Y');
        $builder->where('copy_type', 0);
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_tw_cp_send_to($ck_en_nt_x)
    {
        $builder = $this->db->table('tw_o_r a');
        $builder->select('a.id, del_type, tw_sn_to, sendto_state, sendto_district, copy_type, send_to_type');
        $builder->join('tw_comp_not b', 'a.id = b.tw_o_r_id');
        $builder->where('tw_org_id', $ck_en_nt_x['id']);
        $builder->where('a.display', 'Y');
        $builder->where('b.display', 'Y');
        $builder->where('copy_type', 1);
        $builder->orderBy('a.id, del_type, copy_type');
        // Execute the query
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function countRecords($diary_no, $date)
    {
        // Set table dynamically for the query
        $builder = $this->db->table('tw_tal_del');
        $count = $builder->where('diary_no', $diary_no)
            ->where('rec_dt', $date)
            ->where('display', 'Y')
            ->where('print', 0)
            ->countAllResults();

        return $count;
    }

    public function getCaseTypeByCode($casecode)
    {
        $builder = $this->db->table('master.casetype');
        $result = $builder->where('casecode', $casecode)->get()->getRowArray();
        return $result;
    }

    public function getIdByName($name)
    {
        $builder = $this->db->table('master.tw_section');
        $builder->select('id');
        $builder->where('name', $name);
        $query = $builder->get();
        $result = $query->getRowArray();

        return $result ? $result['id'] : null;
    }

    public function getDetails($dairy_no, $sr_no, $pet_res, $date)
    {
        $sql = "SELECT id, name, address, nt_type, amount 
        FROM tw_tal_del 
        WHERE diary_no = '$dairy_no' 
        AND sr_no = '$sr_no' 
        AND pet_res = '$pet_res' 
        AND rec_dt = '$date' 
        AND display = 'Y' 
        AND print = 0";

        $query = $this->db->query($sql);
        return $query->getRowArray();
    }

    public function getFileDetails($diary_no)
    {
        $sql = "SELECT 
                    active_casetype_id AS casetype_id,
                    active_fil_no AS fil_no,
                    short_description,
                    casename,
                    active_fil_dt AS fil_dt,
                    pet_name,
                    res_name,
                    pet_adv_id,
                    lastorder,
                    CAST(diary_no_rec_date AS DATE) AS diary_no_rec_date,
                    pno,
                    rno,
                    b.nature,
                    casetype_id AS c_t_id
                FROM main a 
                LEFT JOIN master.casetype b 
                ON a.active_casetype_id = b.casecode 
                WHERE display = 'Y' 
                AND diary_no = ?";  // Use prepared statements to prevent SQL Injection

        $query = $this->db->query($sql, [$diary_no]); // Execute query safely

        return $query->getRowArray(); // Fetch single row as an array
    }

    public function getTalDelDetails($diary_no, $dt, $fil_nm)
    {
        return $this->db->table('tw_tal_del')
            ->select("
                id,
                process_id,
                name,
                nt_type,
                sr_no,
                pet_res,
                address,
                amount,
                amt_wor,
                TO_CHAR(rec_dt, 'YYYY') AS rec_dt,   
                fixed_for,
                sub_tal,
                tal_state,
                tal_district,
                individual_multiple,
                pet_res || '[' || sr_no || ']' AS p_sno,  
                order_dt,
                rec_dt AS rec_dt1
            ")
            ->where('diary_no', $diary_no)
            ->where('rec_dt', $dt)
            ->where('print', '0')
            ->where('notice_path', $fil_nm)
            ->where('display', 'Y')
            ->orderBy('process_id, pet_res, sr_no', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getSendToDetails($org_id, $del_type,$copy_type)
    {
        $query =  $this->db->table('tw_o_r a')
            ->select('a.tw_sn_to, a.sendto_state, a.sendto_district, a.send_to_type')
            ->join('tw_comp_not b', 'a.id = b.tw_o_r_id')
            ->where('a.display', 'Y')
            ->where('b.display', 'Y')
            ->where('a.tw_org_id', $org_id)
            ->where('b.copy_type', $copy_type)
            ->where('a.tw_sn_to !=', 0)
            ->where('a.del_type', $del_type)
            ->get();
            if($copy_type == 0)
            {
                return $query->getRowArray();
            }else{
                return $query->getResultArray(); 
            }
            
    }

    public function getMultiSendTp($diary_no, $rec_dt, $nt_type, $del_type)
    {
        $sql = "
            SELECT 
                a.tw_sn_to, 
                a.sendto_state, 
                a.sendto_district, 
                a.send_to_type,
                z.tal_state, 
                z.tal_district, 
                z.name, 
                z.address,
                
                STRING_AGG(CONCAT(z.process_id, '/', EXTRACT(YEAR FROM z.rec_dt)) ORDER BY z.pet_res, z.sr_no, z.process_id, ', ') AS process_id,
                EXTRACT(YEAR FROM z.rec_dt) AS rec_dt,
                
                STRING_AGG(CONCAT(z.pet_res, '[', z.sr_no, ']') ORDER BY z.pet_res, z.sr_no, z.process_id, ', ') AS p_sno,
                
                z.pet_res, 
                z.sr_no, 
                z.del_type, 
                z.nt_type, 
                z.section
                
            FROM tw_tal_del z
            JOIN tw_o_r a ON z.id = a.tw_org_id
            JOIN tw_comp_not b ON a.id = b.tw_o_r_id
            JOIN tw_notice tn ON tn.id = z.nt_type AND tn.display = 'Y' AND tn.war_notice != 'L'
            
            WHERE 
                a.display = 'Y' 
                AND z.display = 'Y' 
                AND z.diary_no = ?
                AND z.rec_dt = ?
                AND z.print = '0'
                AND b.display = 'Y'
                AND b.copy_type = 0
                AND z.nt_type = ?
                AND z.del_type = ?
            
            GROUP BY 
                CASE 
                    WHEN a.tw_sn_to != 0 
                    THEN CONCAT(a.send_to_type, a.tw_sn_to, z.pet_res, z.sr_no, a.sendto_state, z.id) 
                    ELSE z.id 
                END,
                a.tw_sn_to, a.sendto_state, a.sendto_district, a.send_to_type, 
                z.tal_state, z.tal_district, z.name, z.address, 
                z.pet_res, z.sr_no, z.del_type, z.nt_type, z.section, z.rec_dt
            
            ORDER BY 
                (CASE WHEN TRY_CAST(z.sr_no AS INTEGER) = 0 THEN 1 ELSE 0 END),
                CAST(split_part(z.sr_no, '.', 1) AS INTEGER),
                CAST(split_part(CONCAT(z.sr_no, '.0'), '.', 2) AS INTEGER),
                CAST(split_part(CONCAT(z.sr_no, '.0.0'), '.', 3) AS INTEGER),
                CAST(split_part(CONCAT(z.sr_no, '.0.0.0'), '.', 4) AS INTEGER),
                z.pet_res, 
                z.process_id;
        ";

        return $this->db->query($sql, [$diary_no, $rec_dt, $nt_type, $del_type])->getResultArray();
    }

    public function getFirDetail($diary_no)
    {
        $sql = "
            SELECT 
                a.lct_casetype, 
                a.lct_caseno, 
                a.lct_caseyear, 
                b.type_sname
            FROM lowerct a
            LEFT JOIN lc_hc_casetype b 
                ON b.lccasecode = a.lct_casetype 
                AND b.display = 'true'
            WHERE 
                a.diary_no = ? 
                AND a.lw_display = 'true' 
                AND a.ct_code = 3;
        ";

        return $this->db->query($sql, [$diary_no])->getRowArray();
    }

    public function getPetitionerAdvocateParty($diary_no, $party_type, $sno)
    {
        // Build the query using Query Builder
        $builder = $this->db->table('advocate a');
        $builder->select('a.title, a.name');
        $builder->join('bar b', 'a.advocate_id = b.bar_id', 'inner');
        $builder->where('a.diary_no', $diary_no);
        $builder->where('a.display', 'Y');
        $builder->where('a.pet_res', $party_type);
        $builder->where('a.pet_res_no', $sno);

        // Execute the query and get the result
        $query = $builder->get();

        // Check if the result exists
        if ($query->getNumRows() > 0) {
            $result = $query->getRow();
            $res_sql = $result->title . ' ' . $result->name;
            return $res_sql;
        } else {
            return null;  // In case no result is found
        }
    }

    public function get_party_case_no($diary_no,$party_type,$party_no)
    {
        
        $sql = is_data_from_table('party',  " diary_no='$diary_no' AND pet_res='$party_type' and sr_no='$party_no' and pflag='P' "," auto_generated_id ",'');
        $res_sql = (!empty($sql)) ? $sql['auto_generated_id'] : '';
        return $res_sql;
    }
    
    public function get_lowercourt_id($party_no)
    {
        $outer_array = array();
        //$sql="Select lowercase_id from party_lowercourt where display='Y' and party_id='$party_no'";
       // $sql=  mysql_query($sql)or die("Error: ".__LINE__.mysql_error());

        $sqlresult = is_data_from_table('party_lowercourt',  " display='Y' and party_id='$party_no' "," lowercase_id ",'A');
        if(!empty($sqlresult))
        {
            foreach ($sqlresult as $row) {
                $inner_array = array();
                            $inner_array[0] = $row['lowercase_id'];


                            $outer_array[] = $inner_array;
            }
        }
        return $outer_array;

    }


    public function getRegisteredCaseFromLowerCtId($diary_no, $lower_ct_id)
    {
        // Use query builder to retrieve case details
        $builder = $this->db->table('registered_cases');
        $builder->select('casetype_id, case_no, case_year');
        $builder->where('diary_no', $diary_no);
        $builder->where('display', 'Y');
        $builder->where('lowerct_id', $lower_ct_id);

        // Execute the query
        $query = $builder->get();

        // Check if any results are found
        if ($query->getNumRows() > 0) {
            $row = $query->getRow();
            $inner_array = [
                $row->casetype_id,
                $row->case_no,
                $row->case_year
            ];
            return $inner_array;
        } else {
            return null;  // Return null if no records found
        }
    }

    public function getCaseTypeCode($skey)
    {
        // Use query builder to retrieve short_description of the case type
        $builder = $this->db->table('master.casetype');
        $builder->select('short_description');
        $builder->where('casecode', $skey);
        $builder->where('display', 'Y');

        // Execute the query
        $query = $builder->get();

        // Check if any results are found
        if ($query->getNumRows() > 0) {
            // Fetch the result
            $row = $query->getRow();
            return $row->short_description;  // Return the short description
        } else {
            return null;  // Return null if no records found
        }
    }

    public function getPinCode($diary_no, $party_type, $party_no)
    {
        // Use query builder to retrieve the pin from the party table
        $builder = $this->db->table('party');
        $builder->select('pin');
        $builder->where('diary_no', $diary_no);
        $builder->where('pet_res', $party_type);
        $builder->where('sr_no', $party_no);
        $builder->where('pflag', 'P');

        // Execute the query and fetch the result
        $query = $builder->get();

        // Check if any results are found
        if ($query->getNumRows() > 0) {
            // Return the pin value from the first result row
            $row = $query->getRow();
            return $row->pin;
        } else {
            return null;  // Return null if no record is found
        }
    }

    public function getMulSendTp1($dairy_no, $dt, $nt_type, $del_type, $dtc)
    {
        // Use the query builder for the desired query
        $builder = $this->db->table('tw_tal_del z');
        $builder->distinct();  // Get distinct rows
        $builder->select('tw_sn_to, sendto_state, sendto_district, send_to_type, del_type');
        $builder->join('tw_o_r a', 'z.id = a.tw_org_id', 'inner');
        $builder->join('tw_comp_not b', 'a.id = b.tw_o_r_id', 'inner');
        $builder->join('tw_notice tn', 'tn.id = z.nt_type AND tn.display = "Y" AND tn.war_notice != "L"', 'inner');
        
        $builder->where('a.display', 'Y');
        $builder->where('z.display', 'Y');
        $builder->where('diary_no', $dairy_no);
        $builder->where('rec_dt', $dt);
        $builder->where('print', '0');
        $builder->where('b.display', 'Y');
        $builder->where('nt_type', $nt_type);
        $builder->where('copy_type', 1);
        $builder->where('del_type', $del_type[$dtc]);

        // Execute the query and return the result
        $query = $builder->get();

        // Fetch and return results
        return $query->getResultArray();  // This returns the result as an associative array
    }

    public function getPetitioners($dairy_no, $tw_sn_to)
    {
        // Use the query builder for the desired query
        $builder = $this->db->table('advocate');
        
        // Select group_concat with concat logic
        $builder->select("GROUP_CONCAT(CONCAT(pet_res, '[', pet_res_no, ']')) AS parties");
        
        // Apply where conditions
        $builder->where('diary_no', $dairy_no);
        $builder->where('display', 'Y');
        $builder->where('advocate_id', $tw_sn_to);
        
        // Execute the query and return the result
        $query = $builder->get();

        // Return the result (since only one row is expected, use getRow())
        $result = $query->getRowArray();

        // If the result is not empty, return the 'parties' column
        return isset($result['parties']) ? $result['parties'] : null;
    }


    public function getIndividualMultiple($fil_no, $c_date)
    {
        $db = \Config\Database::connect();

        $query = $db->query("SELECT individual_multiple 
                             FROM tw_tal_del 
                             WHERE display = 'Y' 
                             AND diary_no = ? 
                             AND rec_dt = ? 
                             AND print = 0 
                             LIMIT 1", [$fil_no, $c_date]);

        $result = $query->getRow(); // Fetch single row
        
        return $result ? $result->individual_multiple : null;
    }


    public function getLetterIds($fil_no)
    {
        $db = \Config\Database::connect();

        $query = $db->query("SELECT a.id 
                             FROM tw_tal_del a 
                             JOIN tw_notice b ON a.nt_type = b.id 
                             WHERE a.diary_no = ? 
                             AND a.display = 'Y' 
                             AND b.display = 'Y' 
                             AND b.war_notice = 'L'", [$fil_no]);

        return $query->getResultArray(); // Fetch all matching records as an array
    }

    public function getDiaryModes($tw_org_id, $del_type, $ext_ids = "")
    {
        $db = \Config\Database::connect();

        $sql = "SELECT b.id, del_type, diary_no, rec_dt 
                FROM tw_o_r a
                JOIN tw_tal_del b ON a.tw_org_id = b.id 
                WHERE a.tw_org_id = ? 
                AND a.display = 'Y' 
                AND b.display = 'Y' 
                AND b.del_type = ? $ext_ids";

        $query = $db->query($sql, [$tw_org_id, $del_type]);

        return $query->getRowArray(); // Fetch single row as an associative array
    }

    public function getSelectedMode($diary_no, $rec_dt, $del_type)
    {
        $db = \Config\Database::connect();

        $sql = "SELECT b.id 
                FROM tw_o_r b
                JOIN tw_tal_del a ON b.tw_org_id = a.id
                WHERE a.diary_no = ? 
                AND a.rec_dt = ? 
                AND a.display = 'Y'
                AND a.del_type = ?
                AND b.display = 'Y'";

        $query = $db->query($sql, [$diary_no, $rec_dt, $del_type]);

        return $query->getRowArray(); // Fetch single row as an associative array
    }

    public function checkDataCount($fil_no, $dt, $hd_off_notice)
    {
        $db = \Config\Database::connect();

        $sql = "SELECT COUNT(diary_no) as count 
                FROM tw_tal_del 
                WHERE diary_no = ? 
                AND rec_dt = ? 
                AND display = 'Y' 
                AND office_notice_rpt = ? 
                AND print = 1";

        $query = $db->query($sql, [$fil_no, $dt, $hd_off_notice]);

        return $query->getRowArray()['count']; // Fetch count as an integer
    }


    public function getNoticeDetails($fromDate, $toDate, $userCode = '')
    {
        $db = \Config\Database::connect();

        $sql = "
        SELECT aa.*, bb.s 
        FROM (
            SELECT a.diary_no, process_id, a.name, address, b.name AS nt_type, del_type, tw_sn_to, copy_type, send_to_type, 
                   fixed_for, rec_dt, office_notice_rpt, reg_no_display, sendto_district, sendto_state, notice_path, 
                   published_by, user_id, dispatch_dt
            FROM tw_tal_del a
            JOIN master.tw_notice b ON CAST(a.nt_type AS INTEGER) = b.id
            JOIN tw_o_r c ON c.tw_org_id = a.id
            JOIN tw_comp_not d ON d.tw_o_r_id = c.id
            JOIN main m ON a.diary_no = m.diary_no
            WHERE rec_dt BETWEEN ? AND ?
              AND a.display = 'Y' $userCode
              AND print = 1
              AND b.display = 'Y'
              AND c.display = 'Y'
              AND d.display = 'Y'
        ) aa 
        JOIN (
            SELECT COUNT(a.diary_no) AS s, a.diary_no, a.rec_dt, notice_path 
            FROM tw_tal_del a
            JOIN master.tw_notice b ON CAST(a.nt_type AS INTEGER) = b.id
            JOIN tw_o_r c ON c.tw_org_id = a.id
            JOIN tw_comp_not d ON d.tw_o_r_id = c.id
            JOIN main m ON a.diary_no = m.diary_no
            WHERE rec_dt BETWEEN ? AND ?
              AND a.display = 'Y' $userCode
              AND print = 1
              AND b.display = 'Y'
              AND c.display = 'Y'
              AND d.display = 'Y'
            GROUP BY a.diary_no, a.rec_dt, notice_path
        ) bb ON aa.diary_no = bb.diary_no 
             AND aa.rec_dt = bb.rec_dt 
             AND aa.notice_path = bb.notice_path 
        ORDER BY aa.diary_no, aa.rec_dt, aa.notice_path;
        ";

        $query = $db->query($sql, [$fromDate, $toDate, $fromDate, $toDate]);
        return $query->getResultArray();
    }




}
