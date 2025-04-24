<?php

namespace App\Models\MasterManagement;
use CodeIgniter\Model;

class MasterModel extends Model
{


    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
    }

 

    
    function get_law_firm()
    {
        $sql="SELECT * FROM  master.law_firm where display='Y' order by law_firm_name";
        $query = $this->db->query($sql);
        if ($query->getNumRows() > 0) {
            return $query->getResultArray(); 
        }else {
            return false;
        }
    }



    function get_state()
    {
        $sql="SELECT * FROM  master.state WHERE District_code=0  AND	 Sub_Dist_code=0  AND	 Village_code=0  ORDER BY name";
        $query = $this->db->query($sql);
        if ($query->getNumRows() > 0) {
            return $query->getResultArray(); 
        }else {
            return false;
        }
    }


    public function getAdvocateDetails($enroll_no, $enroll_yr)
    {
        
        $builder = $this->db->table('master.bar');
        $builder->select('name');
        $builder->select('aor_code');
        $builder->where('if_aor', 'Y');
        $builder->where('enroll_no', $enroll_no);
        $builder->where("EXTRACT(YEAR FROM enroll_date) = ", $enroll_yr); 
        $query = $builder->get();
        // echo '<pre>';
        // echo "Last Query: " . $this->db->getLastQuery();
        // echo '</pre>';
      
        if ($query->getNumRows() > 0) {
            return $query->getResultArray(); 
        } else {
            return [];
        }
    }

    public function checkDuplicate($law_firm_id, $enroll_no, $enroll_yr, $state_id, $from_date, $to_date)
    {   
          $builder = $this->db->table('master.law_firm_adv');
          $builder->where('law_firm_id', $law_firm_id);
          $builder->where('enroll_no', $enroll_no);
          $builder->where('enroll_yr', $enroll_yr);
          $builder->where('state_id', $state_id);
          $builder->where('from_date', $from_date);
          $builder->where('to_date', $to_date);
          $query = $builder->get();
          return $query->getFirstRow('array');
    }
    
    

    function getJudgeRecord($judge, $mf)
    {       
        
        $condition = '';
        if ($mf == 'M' || $mf == 'F') {
            $condition = " AND m_f='$mf'";
        } else if ($mf == 'B') {
            $condition = ""; 
        }


       

        $sql = "SELECT a.*, jcode, jname, CONCAT(sub_name1, ' - ', sub_name4, ' (', category_sc_old, ')') AS catg 
                FROM master.judge_category a 
                JOIN master.judge j ON a.j1 = j.jcode 
                JOIN master.submaster s ON s.id = a.submaster_id 
                WHERE j1 = $judge $condition 
                -- AND (a.to_dt IS NULL OR a.to_dt = '0001-01-01')
                ORDER BY priority";
 
        $query = $this->db->query($sql);
     
        return $query->getResultArray();
    }
    


    public function getJudge() {
        $builder = $this->db->table('master.judge');
        $builder->where('is_retired', 'N');
        $builder->where('display', 'Y');
        $builder->where('jtype', 'J');
        $query = $builder->get();
        return $query->getResultArray();
    }
    
 

    public function transfer_judge_category($judge_from, $judge_to, $usercode, $mf)
    {
        if (!empty($judge_from) && !empty($judge_to) && !empty($usercode)) {
            $condition = '';

            if ($mf == 'M' || $mf == 'F') {
                $condition = " AND m_f = '$mf'";
            }
            

            $sql_insert = "INSERT INTO master.judge_category (
                j1, 
                submaster_id, 
                priority, 
                from_dt, 
                ent_dt, 
                usercode, 
                display, 
                to_dt, 
                to_dt_ent_dt, 
                to_dt_usercode, 
                m_f
            )
            (SELECT 
                '$judge_to' AS judge_to, 
                submaster_id, 
                priority, 
                from_dt, 
                NOW() AS current_time, 
                '$usercode' AS user_code, 
                display, 
                to_dt, 
                to_dt_ent_dt, 
                to_dt_usercode, 
                m_f 
            FROM 
                master.judge_category 
            WHERE
                j1 = '$judge_from' 
                $condition)
            ON CONFLICT (j1, priority, to_dt, m_f)
            DO NOTHING
            RETURNING *
            ;";

            $query1 =   $this->db->query($sql_insert);
            $query = $query1->getResultArray();

            if (count($query) >= 1) 
            {
                $sql_update = "UPDATE master.judge_category
                        SET 
                            to_dt = CURRENT_DATE, 
                            to_dt_usercode = '$usercode', 
                            to_dt_ent_dt = NOW()
                        WHERE 
                            j1 = '$judge_from' 
                            $condition 
                            AND to_dt IS NULL
                            AND NOT EXISTS (
						        SELECT 1
						        FROM master.judge_category AS t
						        WHERE t.j1 = '254' 
						          AND t.priority = master.judge_category.priority
						          AND t.to_dt IS NOT NULL
						          AND t.m_f = 'M'
						    );";

                  $query =  $this->db->query($sql_update);

                if ($query == 1) {
                    echo "Judge Category Updated Successfully!!";
                } else {
                    echo "There is some problem. Please contact Computer-Cell.";
                }
            }
        }
 
    }
    
    

    public function update_judge_category($priority, $toDate, $id, $usercode, $mf)
    {
        // Fetch the current record
        $sql = "SELECT * FROM master.judge_category WHERE id = ?";
        $result = $this->db->query($sql, [$id]); // Use parameter binding for security
        $resultArray = $result->getResultArray(); // Use getResultArray() instead of result_array()
    
        if (empty($resultArray)) {
            echo "No record found with ID: $id";
            return;
        }
      
        $priorityDb = $resultArray[0]['priority'];
        $toDateDb = $resultArray[0]['to_dt'];
        $jcode = $resultArray[0]['j1'];
        $subject = $resultArray[0]['submaster_id'];
    
        // Handle the toDate logic
        if ($toDate == '') {
            $toDate = NULL;
            $to_dt_ent_dt = NULL;
            $to_dt_usercode = 0;
        } else {
            $toDate = date('Y-m-d', strtotime($toDate));
            $to_dt_ent_dt = date("Y-m-d H:i:s");
            $to_dt_usercode = $usercode;
        }
    
        // Update logic
        if ($priority != $priorityDb) {
            $sql_close = "UPDATE master.judge_category SET to_dt = date(now()), to_dt_usercode = ?, to_dt_ent_dt = now() WHERE id = ?";
            $this->db->query($sql_close, [$usercode, $id]);
    
            // Insert a new record
            // $sql_update = "INSERT INTO master.judge_category (j1, submaster_id, priority, from_dt, ent_dt, usercode, display, to_dt, to_dt_ent_dt, to_dt_usercode, m_f) 
            //                VALUES (?, ?, ?, date(now()), now(), ?, 'Y', NULL, NULL, 0, ?)";
            // $this->db->query($sql_update, [$jcode, $subject, $priority, $usercode, $mf]);
            $sql_update = "UPDATE master.judge_category 
               SET 
                  j1 = ?, 
                  submaster_id = ?, 
                  priority = ?, 
                  from_dt = DATE(NOW()), 
                  ent_dt = NOW(), 
                  usercode = ?, 
                  display = 'Y', 
                  to_dt = date(now()), 
                  to_dt_ent_dt = now(), 
                  to_dt_usercode = 0, 
                  m_f = ?
               WHERE id = ?";
            $this->db->query($sql_update, [$jcode, $subject, $priority, $usercode, $mf, $id]);
        } else {
            $sql_close = "UPDATE master.judge_category SET to_dt = ?, to_dt_usercode = ?, to_dt_ent_dt = now() WHERE id = ?";
            $this->db->query($sql_close, [$toDate, $usercode, $id]);
        }
        if ($this->db->affectedRows() > 0) {
            echo "Records Updated Successfully!!";
        } else {
            echo "There is some problem. Please contact Computer-Cell.";
        }
    }
    


    }  
    
    
    

     
    

 
  
