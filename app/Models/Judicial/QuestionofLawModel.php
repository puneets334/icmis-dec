<?php
#C:\xampp\htdocs\supremecourt_core\Copying\application\models\Law_point_model.php
namespace App\Models\Judicial;

use CodeIgniter\Model;

class QuestionofLawModel extends Model
{
    public function case_types()
    {
        $builder = $this->db->table("master.casetype");
        $builder->where('casecode!=9999');
        $builder->where('is_deleted', 'f');
        $builder->orderBy('casecode', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_case_details($diary_no = 0)
    {
        // Case where diary_number and diary_year are provided
        $condition = "(m.diary_no = $diary_no)";

        // Prepare query_condition for acts and keywords
        $query_condition = "(SELECT STRING_AGG(act::TEXT, ', ') 
                            FROM act_main 
                            WHERE (diary_no = $diary_no) AND display = 'Y') AS acts,
                            (SELECT STRING_AGG(keyword_id::TEXT, ', ') 
                            FROM ec_keyword 
                            WHERE (diary_no = $diary_no) AND display = 'Y') AS keyword";

        // Construct the main SELECT query using CI4 builder
        $builder = $this->db->table('main m')
                    ->select([
                        'CAST(LEFT(m.diary_no::TEXT, LENGTH(m.diary_no::TEXT) - 4) AS TEXT) AS diary_no',
                        'CAST(RIGHT(m.diary_no::TEXT, 4) AS TEXT) AS diary_year',
                        "TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS diary_date",
                        'm.diary_no AS case_diary',
                        'c_status',
                        "CONCAT(pet_name, ' Vs ', res_name) AS case_title",
                        'us.section_name AS user_section',
                        'm.reg_no_display',
                        'm.c_status',
                        'l.question_of_law',
                        'l.catchwords',
                        'l.is_verified',
                        "TO_CHAR(l.verified_on, 'DD-MM-YYYY') AS verified_on",
                        'u2.name AS verified_by',
                        "TO_CHAR(l.updated_on, 'DD-MM-YYYY') AS updated_date",
                        'l.display',
                        $query_condition,
                        'u.name AS lawPointEnteredBy'
                    ])
                    ->join('law_points l', "m.diary_no = l.diary_no AND l.display = 'Y'", 'left')
                    ->join('master.users u', "u.usercode = l.updated_by AND (u.display = 'Y' OR u.display IS NULL)", 'left')
                    ->join('master.users u1', "m.dacode = u1.usercode AND (u1.display = 'Y' OR u1.display IS NULL)", 'left')
                    ->join('master.users u2', "u2.usercode = l.verified_by AND (u2.display = 'Y' OR u2.display IS NULL)", 'left')
                    ->join('master.usersection us', "us.id = u1.section AND (us.display = 'Y' OR us.display IS NULL)", 'left')
                    ->where($condition);

        // echo $builder->getCompiledSelect();die;

        // Execute the query
        $query = $builder->get();

        if ($query->getNumRows() >= 1)
            //echo $sql;
            return $query->getResultArray();
        else
            return false;
    }

    public function check_case($case_diary)
    {
        // Using query builder to count records in PostgreSQL
        $builder = $this->db->table('law_points');

        // Select count of diary_no with conditions
        $builder->select('count(diary_no) as count_no')
                ->where('diary_no', $case_diary)
                ->where('display', 'Y');

        // Execute the query
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function check_act_entry($diary_no = null, $actCode = null)
    {
        // Using query builder to perform the query in PostgreSQL
        $builder = $this->db->table('act_main');

        // Select the diary_no with conditions
        $builder->select('diary_no')
                ->where('diary_no', $diary_no)
                ->where('act', $actCode)
                ->where('display', 'Y');

        // Execute the query and check if any rows are returned
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return 1;
        } else {
            return 0;
        }
    }

    public function get_all_entered_acts($diary_no = null)
    {
        // Using query builder to achieve the same result in PostgreSQL
        $builder = $this->db->table('act_main');

        // Select string_agg equivalent to group_concat in MySQL
        $builder->select('string_agg(act::text, \',\') as acts')
                ->where('diary_no', $diary_no)
                ->where('display', 'Y');

        // Execute the query
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getRowArray();
        } else {
            return false;
        }
    }

    function get_all_entered_keywords($diary_no = null)
    {
        // Using query builder to achieve the same result in PostgreSQL
        $builder = $this->db->table('ec_keyword');

        // Select string_agg equivalent to group_concat in MySQL
        $builder->select('string_agg(keyword_id::text, \',\') as keywords')
                ->where('diary_no', $diary_no)
                ->where('display', 'Y');

        // Execute the query
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getRowArray();
        } else {
            return false;
        }
    }

    function check_keyword_entry($diary_no = null, $keywordID = null)
    {
        // Prepare the query using the query builder
        $query = $this->db->table('ec_keyword')
                        ->select('diary_no')
                        ->where('diary_no', $diary_no)
                        ->where('keyword_id', $keywordID)
                        ->where('display', 'Y')
                        ->get();

        // Check if the query returned any rows
        if ($query->getNumRows() >= 1) {
            return 1;
        } else {
            return 0;
        }
    }

    function update_case($case_count, $case_diary, $usercode, $usertype, $law_point = null, $catchwords = null)
    {
        // log_error('$usercode', $usercode);

        $updatedFromSystem = get_client_ip();
        if ($law_point == '') {
            $law_point = 'null';
        }
        // echo $remark;

        if ($case_count <= 0 && ($law_point != '' && $law_point != null)) {
            // for freash entry


            if ($usertype != 6) {
                // FOR LAY POINT ENTRY BY DA
                $data = [
                    'diary_no'         => $case_diary,
                    'question_of_law'  => $law_point,
                    'display'           => 'Y',
                    'updated_by'        => $usercode,
                    'updated_on'        => date('Y-m-d H:i:s'), // You can use current timestamp directly in CI4
                    'updated_from_ip'   => $updatedFromSystem
                ];
                
                $this->db->table('law_points')->insert($data);
                

            //     $sql2 = "Insert Into public.law_points (diary_no,question_of_law,display,updated_by,updated_on,updated_from_ip)
            //   values ('$case_diary','" . $this->db->escape($law_point) . "','Y'," . $usercode . ",NOW(),'$updatedFromSystem')";
            } else {
                // FOR LAW POINTS ENTRY AND VERIFY by DR
                $data = [
                    'diary_no'         => $case_diary,
                    'question_of_law'  => $law_point,
                    'catchwords'        => $catchwords,
                    'display'           => 'Y',
                    'updated_by'        => $usercode,
                    'updated_on'        => date('Y-m-d H:i:s'),  // Current timestamp
                    'updated_from_ip'   => $updatedFromSystem,
                    'is_verified'       => 1,
                    'verified_on'       => date('Y-m-d H:i:s'),  // Current timestamp for verified_on
                    'verified_by'       => $usercode,
                    'verified_from_ip'  => $updatedFromSystem
                ];

            //     $sql2 = "Insert Into public.law_points (diary_no,question_of_law,catchwords,display,updated_by,updated_on,updated_from_ip,is_verified,verified_on,verified_by,verified_from_IP)
            //   values ('$case_diary','" . $this->db->escape($law_point) . "','" . $this->db->escape($catchwords) . "','Y'," . $usercode . ",NOW(),'$updatedFromSystem',1,NOW()," . $usercode . ",'$updatedFromSystem')";
            }
        } else {
            //when law point entry already exists
            $result = $this->db->table('law_points')
                            ->select('updated_by, updated_on, updated_from_ip')
                            ->where('diary_no', $case_diary)
                            ->where('display', 'Y')
                            ->get();

            // $sql = "select updated_by,updated_on,updated_from_ip from public.law_points where diary_no='$case_diary' and display='Y'";
            // $result = $this->db->query($sql);

            if ($result->getNumRows() > 0) {
                $result = $result->getResultArray();
                $law_entry_updated_by = $result[0]['updated_by'];
                $law_entry_updated_on = $result[0]['updated_on'];
                $law_entry_updated_from_ip = $result[0]['updated_from_ip'];
            }

            // Step 1: Update query
            $updated = $this->db->table('law_points')
                                ->set('display', 'N')
                                ->where('diary_no', $case_diary)
                                ->where('display', 'Y')
                                ->update();

            // Step 2: Check if any rows were affected by the update
            if ($this->db->affectedRows() > 0) {
                if ($usertype != 6) {
                    // Insert query when user type is not 6
                    $data = [
                        'diary_no'        => $case_diary,
                        'question_of_law' => $law_point,
                        'display'          => 'Y',
                        'updated_by'       => $usercode,
                        'updated_on'       => date('Y-m-d H:i:s'),  // Using PHP's current timestamp
                        'updated_from_ip'  => $updatedFromSystem
                    ];
                } else {
                    // Insert query for user type 6 with additional fields
                    $data = [
                        'diary_no'         => $case_diary,
                        'question_of_law'  => $law_point,
                        'catchwords'        => $catchwords,
                        'display'           => 'Y',
                        'updated_by'        => $law_entry_updated_by,
                        'updated_on'        => $law_entry_updated_on,
                        'updated_from_ip'   => $law_entry_updated_from_ip,
                        'is_verified'       => 1,
                        'verified_on'       => date('Y-m-d H:i:s'),  // Using PHP's current timestamp
                        'verified_by'       => $usercode,
                        'verified_from_ip'  => $updatedFromSystem
                    ];
                }
            } else {
                // Error handling if no rows were updated
                echo "Error !! while updating previous entry";
            }
        }
        
        $this->db->table('law_points')->insert($data);

        $NoRowAffected = $this->db->affectedRows();

        return $NoRowAffected;
    }

    public function insert_acts($diary_no = null, $actCode = null, $usercode=0)
    {
        // Get the remote IP address
        $updatedFromSystem = getClientIP();

        // Prepare data array for insertion into the 'act_main' table
        $dataArray = [
            'diary_no'          => $diary_no,
            'act'                => $actCode,
            'entdt'              => date('Y-m-d H:i:s'),  // Current timestamp
            'user'               => $usercode,
            'display'            => 'Y',
            'updated_from_ip'    => $updatedFromSystem,
            'updatedfrommodule'  => 'Question of Law'
        ];

        // Insert data into the 'act_main' table using query builder
        $this->db->table('act_main')->insert($dataArray);

        // Get the last inserted ID (useful if you need to reference the newly inserted record)
        $insert_id1 = $this->db->insertID();

        // Get the number of affected rows to ensure the insert was successful
        $num_row1 = $this->db->affectedRows();

        return $num_row1;
    }


    public function insert_keywords($diary_no = null, $keywordCode = null, $usercode=0)
    {
        // Get the remote IP address
        $updatedFromSystem = getClientIP();

        // Prepare data array to insert into ec_keyword table
        $dataArray = [
            'diary_no'           => $diary_no,
            'keyword_id'         => $keywordCode,
            'display'            => 'Y',
            'ent_dt'             => date('Y-m-d H:i:s'),  // Current timestamp
            'updated_from_ip'    => $updatedFromSystem,
            'updatedfrommodule'  => 'Question of Law',
            'user'               => $usercode
        ];

        // Insert data into the 'ec_keyword' table using the query builder
        $this->db->table('ec_keyword')->insert($dataArray);

        // Get the last inserted ID (for any subsequent use, e.g., referencing the newly inserted record)
        $insert_id1 = $this->db->insertID();

        // Get the number of affected rows to ensure the insert was successful
        $num_row1 = $this->db->affectedRows();

        //echo $num_row1;
        return $num_row1;
    }

    public function update_non_selected_acts($diary_no = null, $actCode = null, $usercode=0)
    {
        // Get the remote IP address
        $updatedFromSystem = get_client_ip();

        // Prepare the data for update
        $value = array(
            'entdt' => date('Y-m-d H:i:s'),
            'user' => $usercode,
            'display' => 'N',
            'updated_from_ip' => $updatedFromSystem,
            'updatedfrommodule' => 'Question of Law'
        );

        // Define the condition for the update
        $where = array(
            'diary_no' => $diary_no,
            'act' => $actCode
        );

        // Update the record in 'act_main' table
        $builder = $this->db->table('act_main');
        $builder->where($where);
        $builder->update($value);

        // Get the number of affected rows
        $num_row1 = $this->db->affectedRows();

        return $num_row1;
    }

    public function update_non_selected_keywords($diary_no = null, $keywordCode = null, $usercode=0)
    {
        // Get the remote IP address
        $updatedFromSystem = getClientIP();

        // Prepare the data for update
        $value = array(
            'ent_dt' => date('Y-m-d H:i:s'),
            'user' => $usercode,
            'display' => 'N',
            'updated_from_ip' => $updatedFromSystem,
            'updatedfrommodule' => 'Question of Law'
        );

        // Define the condition for the update
        $where = array(
            'diary_no' => $diary_no,
            'keyword_id' => $keywordCode
        );

        // Update the record in 'ec_keyword' table
        $builder = $this->db->table('ec_keyword');
        $builder->where($where);
        $builder->update($value);

        // Get the number of affected rows
        $num_row1 = $this->db->affectedRows();

        return $num_row1;
    }

    public function get_law_point_report($fromDate = null, $toDate = null)
    {
        $fromDate = date('Y-m-d', strtotime($fromDate));
        $toDate = date('Y-m-d', strtotime($toDate));

        $builder = $this->db->table('main m');
        $builder->select("
            CONCAT(CAST(LEFT(m.diary_no::TEXT,LENGTH(m.diary_no::TEXT)-4) AS TEXT), ' / ', CAST(RIGHT(m.diary_no::TEXT, 4) AS TEXT)) AS diary_no,
            CONCAT(pet_name, '<strong> Vs. </strong>', res_name) AS case_title,
            us.section_name AS user_section,
            m.reg_no_display,
            l.question_of_law,
            TO_CHAR(l.updated_on, 'DD-MM-YYYY') AS updated_date,
            u1.name AS lawPointEnteredBy
        ");
        $builder->join('public.law_points l', 'm.diary_no = l.diary_no AND l.display = \'Y\'', 'left');
        $builder->join('master.users u', 'u.usercode = m.dacode AND (u.display = \'Y\' OR u.display IS NULL)', 'left');
        $builder->join('master.users u1', 'u1.usercode = l.updated_by AND (u1.display = \'Y\' OR u1.display IS NULL)', 'left');
        $builder->join('master.usersection us', 'us.id = u.section AND (us.display = \'Y\' OR us.display IS NULL)', 'left');
        $builder->where("l.updated_on BETWEEN '$fromDate' AND '$toDate'");
        $builder->orderBy('l.updated_on');

        // echo $builder->getCompiledSelect();die;

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {

            return $query->getResultArray();
            // var_dump($result);
        } else {
            session()->setFlashdata('msg', '<div class="alert alert-warning text-center col-md-12">No Law Points Entered between selected Date</div>');
            redirect()->to("Judicial/QuestionofLaw/Report")->withInput();
        }
    }

    public function get_law_point_verify($fromDate = null, $toDate = null, $sec_id = null)
    {
        $builder = $this->db->table('main m');

        $fromDate = date('Y-m-d', strtotime($fromDate));
        $toDate = date('Y-m-d', strtotime($toDate));

        // CONCAT(SUBSTRING(m.diary_no FROM 1 FOR LENGTH(m.diary_no::int) - 4), ' / ', SUBSTRING(m.diary_no FROM LENGTH(m.diary_no::int) - 3 FOR 4)) as diary_no,

        $builder->select("
            m.diary_no as dno,
            CONCAT(CAST(LEFT(m.diary_no::TEXT,LENGTH(m.diary_no::TEXT)-4) AS TEXT), ' / ', CAST(RIGHT(m.diary_no::TEXT, 4) AS TEXT)) AS diary_no,
            CONCAT(pet_name, '<strong> Vs. </strong>', res_name) as case_title,
            us.section_name AS user_section,
            m.reg_no_display,
            l.question_of_law,
            TO_CHAR(l.updated_on, 'DD-MM-YYYY') as updated_date,
            u1.name as lawPointEnteredBy,
            l.is_verified,
            TO_CHAR(l.verified_on, 'DD-MM-YYYY') as verified_on,
            u2.name as verified_by
        ");
        $builder->join('public.law_points l', 'm.diary_no = l.diary_no AND l.display = \'Y\'', 'left');
        $builder->join('master.users u', 'u.usercode = m.dacode AND (u.display = \'Y\' OR u.display IS NULL)', 'left');
        $builder->join('master.users u1', 'u1.usercode = l.updated_by AND (u1.display = \'Y\' OR u1.display IS NULL)', 'left');
        $builder->join('master.users u2', 'u2.usercode = l.verified_by AND (u2.display = \'Y\' OR u2.display IS NULL)', 'left');
        $builder->join('master.usersection us', 'us.id = u.section AND (us.display = \'Y\' OR us.display IS NULL)', 'left');
        $builder->where("l.verified_on BETWEEN '$fromDate' AND '$toDate'");
        $builder->where('l.is_verified', 1);
        // $builder->where('l.is_verified', 1);
        $builder->orderBy('us.section_name');
        $builder->orderBy('l.updated_on');

        // echo $builder->getCompiledSelect();die;

        $query = $builder->get(); //['fromDate' => $fromDate, 'toDate' => $toDate]

        // $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {

            return $query->getResultArray();
            // var_dump($result);
        } else {
            session()->setFlashdata('msg', '<div class="alert alert-warning text-center col-md-12">No Law Points Entered between selected Dates</div>');
            redirect()->to("Judicial/QuestionofLaw/VerifyReport")->withInput();
        }
    }

    public function get_keyword_list()
    {
        $sql = "Select id,keyword_code,keyword_description from public.ref_keyword where is_deleted='f'";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_acts_list()
    {
        $sql = "select id,actno,act_name from master.act_master where display= 'Y' order by id ASC";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function da_details($userCode = null)
    {
        $builder = $this->db->table('master.users user');
        $builder->select('name, type_name, section_name, empid, us.id, user.usertype');
        $builder->join('master.usersection us', 'user.section = us.id', 'left');
        $builder->join('master.usertype ut', 'ut.id = user.usertype', 'left');
        $builder->where('usercode', $userCode);

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function update_cases_to_verify($dnos = null, $userCode = null)
    {
        // Prepare the data to be updated
        $value = [
            'is_verified' => 1, // Assuming is_verified is an integer type
            'verified_on' => date('Y-m-d H:i:s'), // Current datetime
            'verified_by' => $userCode // Ensure $userCode is properly sanitized
        ];

        // Apply the update to rows where diary_no is in the $dnos array
        $this->db->whereIn('diary_no', $dnos); // `whereIn` is a CodeIgniter method compatible with PostgreSQL
        $this->db->update('law_points', $value); // Perform the update

        // Get the number of affected rows
        $num_row1 = $this->db->affectedRows();

        return $num_row1;
    }
}
