<?php

namespace App\Models\Listing;

use CodeIgniter\Model;

class JudgeMasterModel extends Model
{

    protected $table = 'master_list_type';
    //protected $primaryKey = 'diary_no';
    // protected $allowedFields = ['fil_no', 'fil_dt', 'lastorder', 'pet_name', 'res_name', 'c_status'];
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    public function getJudge()
    {
        $builder = $this->db->table('master.judge');
        $builder->where('is_retired', 'N');
        $builder->where('display', 'Y');
        $builder->where('jtype', 'J');
        $query = $builder->get();
        return $query->getResultArray();
    }

    function insert_Judge_Master($judge1, $judge2, $judge3, $freshLimit, $oldLimit, $frm_dt, $usercode)
    {
        if ($judge3 == '') {
            $judge3 = 0;
        }
        if ($usercode != '' && $judge1 != '' && $judge2 != '' && $freshLimit != '' && $oldLimit != '' && $frm_dt != '') {
            $data = [
                'p1' => $judge1,
                'p2' => $judge2,
                'p3' => $judge3,
                'from_dt' => $frm_dt,
                'fresh_limit' => $freshLimit,
                'old_limit' => $oldLimit,
                'ent_dt' => date('Y-m-d H:i:s'),
                'usercode' => $usercode,
                'display' => 'Y'
            ];
            $builder = $this->db->table('judge_group');
            $builder->insert($data);
            if ($this->db->affectedRows() >= 1) {
                return true;
            }
        }
        return false;
    }

    function disp_Judge_Entry()
    {
        $builder = $this->db->table('judge_group jg');
        $builder->select('jg.p1, jg.p2, jg.p3, 
            COALESCE(jg.to_dt_usercode::text, \'\') AS to_dt_usercode, 
            jg.usercode,
            COALESCE(TO_CHAR(jg.from_dt, \'DD/MM/YYYY\'), \'\') AS from_dt,
            COALESCE(TO_CHAR(jg.to_dt, \'DD/MM/YYYY\'), \'\') AS to_dt,
            jg.display, jg.fresh_limit, jg.old_limit,
            COALESCE(TO_CHAR(jg.to_dt_ent_dt, \'DD/MM/YYYY HH24:MI\'), \'\') AS to_dt_ent_dt,
            j1.jname AS judge1, j2.jname AS judge2,
            COALESCE(j3.jname, \'\') AS judge3,
            COALESCE(u1.name, \'\') AS ins_by,
            COALESCE(u2.name, \'\') AS to_updated_by');
        $builder->join('master.judge j1', 'jg.p1 = j1.jcode', 'left')
            ->join('master.judge j2', 'jg.p2 = j2.jcode', 'left')
            ->join('master.judge j3', 'jg.p3 = j3.jcode', 'left')
            ->join('master.users u1', 'u1.usercode = jg.usercode', 'left')
            ->join('master.users u2', 'u2.usercode = jg.to_dt_usercode', 'left');
        $builder->where('(jg.to_dt IS NULL OR jg.to_dt = NULL)')
            ->orderBy('jg.ent_dt', 'desc');
        $query = $builder->get();
        if (count($query->getResultArray()) >= 1) {
            return $query->getResultArray();
        }
        return [];
    }

    function check_case($judge1, $judge2, $judge3, $to_dt)
    {
        $builder = $this->db->table('public.judge_group');
        $builder->select('id, to_dt')
                ->where('p1', $judge1)
                ->where('p2', $judge2);
            if($judge3 != ''){
                $builder->where('p3', $judge3);
            }else{
                $builder->where('p3', 0);
            }
            $builder->where('to_dt', NULL)
                ->orderBy('ent_dt')
                ->limit(1);
        if ($builder->countAllResults(false) >= 1) {
            return $builder->get()->getResultArray();
        } else {
            return false;
        }
    }

    function check_already_sitted($judge1, $judge2, $judge3)
    {
        if ($judge3 != '') {
            $cond = [$judge1, $judge2, $judge3];
            $sql_judge_exact = $this->db->table('judge_group')
                ->select('id, p1, p2, p3')
                ->where('p1', $judge1)
                ->where('p2', $judge2)
                ->where('p3', $judge3)
                ->where('to_dt', NULL)
                ->countAllResults();
            
            if ($sql_judge_exact >= 1) {                
                return false;
            } else {                
                $sql_judge_check = $this->db->table('judge_group')
                    ->select('id, p1, p2, p3')
                    ->groupStart()
                    ->whereIn('p1', $cond)
                    ->orWhereIn('p2', $cond)
                    ->orWhereIn('p3', $cond)
                    ->groupEnd()
                    ->where('to_dt', NULL);

                if ($sql_judge_check->countAllResults(false) >= 1) {
                    return $sql_judge_check->get()->getResultArray();
                } else {
                    return false;
                }
            }
        } else {
            $cond = [$judge1, $judge2];
            $judge3 = 0;
            $sql_judge_exact = $this->db->table('judge_group')
                ->select('id, p1, p2, p3')
                ->where('p1', $judge1)
                ->where('p2', $judge2)
                ->where('p3', $judge3)
                ->where('to_dt', NULL)
                ->countAllResults();

            if ($sql_judge_exact >= 1) {
                return false;
            } else {
                $sql_judge_check = $this->db->table('judge_group')
                    ->select('id, p1, p2, p3')
                    ->groupStart()
                    ->whereIn('p1', $cond)
                    ->orWhereIn('p2', $cond)
                    ->where('p3', 0)
                    ->groupEnd()
                    ->where('to_dt', NULL);

                if ($sql_judge_check->countAllResults(false) >= 1) {
                    echo $sql_judge_check->getCompiledSelect();
                    // return $sql_judge_check->get()->getResultArray();
                } else {
                    return false;
                }
            }
        }
    }

    function update_Judge_Master($judge1, $judge2, $judge3, $freshLimit, $oldLimit, $to_dt, $frm_dt, $usercode)
    {
        $result = $this->check_case($judge1, $judge2, $judge3, $to_dt);
        $id = $result[0]['id'];
        if ($judge3 == '') {
            $judge3 = 0;
        }
        if (sizeof($result) > 0) {
            $builder = $this->db->table('judge_group');
            $builder->set([
                        'to_dt' => $to_dt, 
                        'to_dt_ent_dt' => 'NOW()',
                        'to_dt_usercode' => $usercode 
                    ])
                    ->where('id', $id)
                    ->groupStart()
                        ->where('to_dt', '0000-00-00') 
                        ->orWhere('to_dt', NULL) 
                    ->groupEnd()
                    ->where('p1', $judge1) 
                    ->where('p2', $judge2) 
                    ->where('p3', $judge3) 
                    ->update(); 

            if ($this->db->affectedRows() >= 1) {
                $this->insert_Judge_Master($judge1, $judge2, $judge3, $freshLimit, $oldLimit, $frm_dt, $usercode);
                return "Record Updated Successfully.";
            } else {
                return "There is some problem. Please contact Computer-Cell.";
            }
        }
    }

    function get_Sub_SubjectCategory($Mcat)
    {
        $sql = "SELECT id, subcode1, category_sc_old, sub_name1, sub_name4,
            CASE 
                WHEN (category_sc_old IS NOT NULL AND category_sc_old != '' AND CAST(category_sc_old AS INTEGER) != 0)
                THEN CONCAT('', category_sc_old, '#-#', sub_name4)
                ELSE CONCAT('', CONCAT(subcode1, '', subcode2), '#-#', sub_name4)
            END AS dsc 
        FROM master.submaster 
        WHERE subcode1 = :Mcat: 
            AND id_sc_old != 0  
            AND flag = 's' 
            AND flag_use IN ('S', 'L') 
        GROUP BY id, subcode1, category_sc_old, sub_name1, sub_name4";
        $query = $this->db->query($sql, ['Mcat' => $Mcat]);
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function getMainSubjectCategory()
    {
        $sql = "SELECT subcode1, sub_name1 
            FROM master.submaster 
            WHERE (flag_use = 'S' OR flag_use = 'L') 
            AND display = 'Y' 
            AND match_id != 0 
            AND (flag = 's' OR flag = 'S') 
            GROUP BY subcode1, sub_name1 
            ORDER BY subcode1";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function insert_judge_category($judge, $fromDate, $McategoryCode, $categoryCode, $usercode, $mf)
    {
        $fromDate = date('Y-m-d', strtotime($fromDate));
        foreach ($categoryCode as $categoryCode1) {
            $sql = "SELECT * FROM master.judge_category 
                WHERE j1 = :judge: 
                AND submaster_id = :categoryCode1: 
                AND to_dt = NULL 
                AND m_f = :mf:";
            $query = $this->db->query($sql, [
                'judge' => $judge,
                'categoryCode1' => $categoryCode1,
                'mf' => $mf
            ]);
            if ($query->getNumRows() >= 1) {
                // Record exists, do nothing
            } else {
                $sql = "SELECT COALESCE(MAX(priority), 0) + 1 AS priority 
                    FROM master.judge_category 
                    WHERE j1 = :judge: 
                    AND to_dt = NULL 
                    AND m_f = :mf:";
                $priorityQuery = $this->db->query($sql, [
                    'judge' => $judge,
                    'mf' => $mf
                ]);
                $priorityResult = $priorityQuery->getRow();
                $priority = $priorityResult->priority ?: 1;
                $sql = "INSERT INTO master.judge_category (j1, submaster_id, priority, from_dt, ent_dt, usercode, display, to_dt, to_dt_ent_dt, to_dt_usercode, m_f) VALUES (:judge:, :categoryCode1:, :priority:, :fromDate:, NOW(), :usercode:, 'Y', NULL, NULL, 0, :mf:)";
                $this->db->query($sql, [
                    'judge' => $judge,
                    'categoryCode1' => $categoryCode1,
                    'priority' => $priority,
                    'fromDate' => $fromDate,
                    'usercode' => $usercode,
                    'mf' => $mf
                ]);
            }
        }
        if ($this->db->affectedRows() >= 1) {
            return "Record Inserted Successfully!!";
        } else {
            return "There is some problem. Please contact Computer-Cell.";
        }
    }

    function getJudgeRecord($judge, $mf)
    {
        $builder = $this->db->table('master.judge_category a')
            ->select([
                'a.*',
                'j.jcode',
                'j.jname',
                "CONCAT(s.sub_name1, ' - ', s.sub_name4, ' (', s.category_sc_old, ')') AS catg"
            ])
            ->join('master.judge j', 'a.j1 = j.jcode', 'inner')
            ->join('master.submaster s', 's.id = a.submaster_id', 'inner')
            ->where('a.j1', $judge);
            if ($mf === 'M' || $mf === 'F') {
                $builder->where('a.m_f', $mf);
            }
        $builder->where('a.to_dt IS NULL', null, false)
            ->orderBy('a.priority', 'ASC');

        $query = $builder->get();
        return $result = $query->getResultArray();
    }

    function get_judge_category($id = NULL){
        $builder = $this->db->table('master.judge_category');
        if($id){
            $builder->where('id', $id);
        }
        $query = $builder->get();
        return $result = $query->getResultArray();
    }

    function update_judge_category($priority, $toDate, $id, $usercode, $mf)
    {
        $result = $this->get_judge_category($id);
        $priorityDb = $result[0]['priority'];
        // $toDateDb   = $result[0]['toDate'];
        $jcode      = $result[0]['j1'];
        $subject    = $result[0]['submaster_id'];

        if ($toDate == '') {
            $toDate         = NULL;
            $to_dt_ent_dt   = NULL;
            $to_dt_usercode = 0;
        } else {
            $toDate         = date('Y-m-d', strtotime($toDate));
            $to_dt_ent_dt   = date("Y-m-d H:i:s");
            $to_dt_usercode = $usercode;
        }

        if ($priority != $priorityDb) 
        {
            $builder = $this->db->table('master.judge_category');
            $builder->set('to_dt', date(now()));
            $builder->set('to_dt_usercode', $usercode); 
            $builder->set('to_dt_ent_dt', 'NOW()', false);
            $builder->where('id', $id);
            $builder->update();

            // Check the result of the update
            if ($this->db->affectedRows() > 0) {
                $data = [
                    'j1' => $jcode,
                    'submaster_id' => $subject,
                    'priority' => $priority,
                    'from_dt' => date('Y-m-d'),
                    'ent_dt' => date('Y-m-d H:i:s'),
                    'usercode' => $usercode,
                    'display' => 'Y',
                    'to_dt' => NULL,
                    'to_dt_ent_dt' => NULL,
                    'to_dt_usercode' => 0,
                    'm_f' => $mf
                ];
                $builder = $this->db->table('master.judge_category');
                $builder->insert($data);
            }
        }
        else {
            $data = [
                'to_dt' => $toDate,
                'to_dt_usercode' => $usercode,
                'to_dt_ent_dt' => date('Y-m-d H:i:s')
            ];
            
            $builder = $this->db->table('master.judge_category');
            $builder->where('id', $id);
            $builder->update($data);
        }

        if ($this->db->affectedRows() >= 1)
            return "Records Updated Successfully!!";
        else
            return "There is some problem. Please contact Computer-Cell.";
    }

    function judgeCategoryReport($mf)
    {
        // $sql = "SELECT 
        //                     j1, 
        //                     jname, 
        //                     judge_seniority, 
        //                     STRING_AGG(
        //                         catg1 || '#' || sub_name1, '#'
        //                     ) AS catg 
        //                     FROM (
        //                     SELECT 
        //                         j1, 
        //                         jname, 
        //                         judge_seniority, 
        //                         CASE 
        //                         WHEN count_judge = count_all 
        //                         AND catg BETWEEN min_catg AND max_catg THEN 
        //                             CONCAT(min_catg, ' to ', max_catg, ' All') 
        //                         ELSE catg 
        //                         END AS catg1, 
        //                         sub_name1 
        //                     FROM (
        //                         SELECT 
        //                         j1, 
        //                         jname, 
        //                         judge_seniority, 
        //                         STRING_AGG(
        //                             category_sc_old, ' , ' ORDER BY category_sc_old
        //                         ) AS catg, 
        //                         COUNT(*) AS count_judge, 
        //                         subcode1 
        //                         FROM 
        //                         master.judge_category jc 
        //                         JOIN 
        //                         master.judge j ON jc.j1 = j.jcode 
        //                         JOIN 
        //                         master.submaster s ON jc.submaster_id = s.id 
        //                         WHERE 
        //                         jc.to_dt is NULL 
        //                         AND j.is_retired = 'N' 
        //                         AND m_f = '$mf'  
        //                         GROUP BY 
        //                         j1, 
        //                         subcode1 ,j.jname,j.judge_seniority
        //                         ORDER BY 
        //                         j1
        //                         limit 10
        //                     ) a 
        //                     JOIN (
        //                         SELECT 
        //                         subcode1, 
        //                         COUNT(*) AS count_all, 
        //                         MIN(category_sc_old) AS min_catg, 
        //                         MAX(category_sc_old) AS max_catg, 
        //                         sub_name1 
        //                         FROM 
        //                         master.submaster 
        //                         WHERE 
        //                         flag = 's' 
        //                         AND flag_use IN ('S', 'L') 
        //                         AND display = 'Y' 
        //                         AND category_sc_old IS NOT NULL 
        //                         AND category_sc_old != '' 
        //                         AND category_sc_old != '0' 
        //                         GROUP BY 
        //                         subcode1,submaster.sub_name1
        //                         limit 10
        //                     ) b 
        //                     ON a.subcode1 = b.subcode1
        //                     ) z 
        //                     GROUP BY 
        //                     j1, jname, judge_seniority 
        //                     ORDER BY 
        //                     judge_seniority
        // //                     ";
        // $sql = "SELECT jc.j1, j.jname, j.judge_seniority,
		// COUNT(*) AS count_judge,
		// COUNT(s.*) AS count_all,
        //     s.subcode1,
		// 	MIN(s.category_sc_old) || ' to ' || MAX(s.category_sc_old) || ' All ' AS catg,
        //     s.sub_name1
        // FROM master.judge_category jc
        // JOIN master.judge j ON jc.j1 = j.jcode
        // JOIN master.submaster s ON jc.submaster_id = s.id
        // WHERE jc.to_dt IS NULL AND j.is_retired = 'N' AND jc.m_f = 'M'AND s.flag = 's' AND s.flag_use IN ('S', 'L') AND s.display = 'Y' AND 
		// s.category_sc_old IS NOT NULL AND s.category_sc_old != '' AND s.category_sc_old != '0'
        // GROUP BY jc.j1, s.subcode1, j.jname, j.judge_seniority,s.sub_name1
        // ORDER BY jc.j1";
        // $query  = $this->db->query($sql);
        // $result = $query->getResultArray();
      
        $builder = $this->db->table('master.judge_category jc');
        $builder->select('jc.j1, j.jname, j.judge_seniority, COUNT(*) AS count_judge, COUNT(s.category_sc_old) AS count_all, s.subcode1, MIN(s.category_sc_old) || \' to \' || MAX(s.category_sc_old) || \' All \' AS catg, s.sub_name1'); // Note the escaped single quotes around " to "
        $builder->join('master.judge j', 'jc.j1 = j.jcode');
        $builder->join('master.submaster s', 'jc.submaster_id = s.id');
        $builder->where('jc.to_dt IS NULL');
        $builder->where('j.is_retired', 'N');
        $builder->where('jc.m_f', 'M');
        $builder->where('s.flag', 's');
        $builder->whereIn('s.flag_use', ['S', 'L']);
        $builder->where('s.display', 'Y');
        $builder->where('s.category_sc_old IS NOT NULL');
        $builder->where('s.category_sc_old !=', '');
        $builder->where('s.category_sc_old !=', '0');
        $builder->groupBy('jc.j1, s.subcode1, j.jname, j.judge_seniority, s.sub_name1');
        $builder->orderBy('j.judge_seniority');

        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }


    function update_judge_bulk_category($judge, $toDate, $usercode, $mf)
    {
        if ($toDate == '') {
            $toDate = null;
        } else {
            $toDate = date('Y-m-d', strtotime($toDate));
        }
                
        $mfCon = ($mf === 'M' || $mf === 'F') ? $mf : null;
        
        // if ($toDate != '') {
        //     $builder = $this->db->table('master.judge_category');
        //     $data = [
        //         'to_dt' => $toDate,
        //         'to_dt_usercode' => $usercode,
        //         'to_dt_ent_dt' => date('Y-m-d')
        //     ];

        //     $builder->where('j1', $judge);
        //     if($mfCon){
        //         $builder->where('m_f', $mfCon);
        //     }

        //     $builder->where('to_dt', null)
        //         ->update($data);
        // }

        if ($toDate != '') {
            $getRecord = $this->db->table('master.judge_category')
                ->where('j1', $judge)
                ->where('to_dt', $toDate);
                if($mfCon){
                    $getRecord->where('m_f', $mfCon);
                }
                $existingRecord = $getRecord->get()
                ->getRow();
            //echo $this->db->getlastquery();die;    
            if ($existingRecord) {
                // "Record already exists, update skipped.";
                //return "There is some problem. Please contact Computer-Cell.";
                return "Already Updated.";
            }
            else {
                $data = [
                    'to_dt' => $toDate,
                    'to_dt_usercode' => $usercode,
                    'to_dt_ent_dt' => date('Y-m-d')
                ];

                $builder = $this->db->table('master.judge_category');

                $builder->select('id')
                        ->where('j1', $judge)
                        ->where('to_dt', null);

                if ($mfCon) {
                    $builder->where('m_f', $mfCon);
                }

                $builder->limit(1);
                $row = $builder->get()->getRow();

                if ($row) {
                    $this->db->table('master.judge_category')
                        ->where('id', $row->id)
                        ->update($data);
                }
            }
        
        }
        
        if ($this->db->affectedRows() >= 1) {
            return "Judge Category Updated Successfully!!";
        } else {
            return "There is some problem. Please contact Computer-Cell.";
        }
    }

    function update_close_Entry($judge1, $judge2, $judge3, $to_dt, $usercode)
    {        
        if ($judge3 != '') {
            $builder = $this->db->table('judge_group');
            $query = $builder->select('id, p1, p2, p3')
                ->where(['p1' => $judge1, 'p2' => $judge2, 'p3' => $judge3, 'to_dt' => NULL])
                ->get();
            if (count($query->getResultArray()) >= 1) {
                $builder = $this->db->table('judge_group');
                $builder->set(['to_dt_usercode' => $usercode, 'to_dt' => $to_dt, 'to_dt_ent_dt' => 'NOW()'])
                    ->where(['p1' => $judge1, 'p2' => $judge2, 'p3' => $judge3, 'to_dt' => NULL])
                    ->update();
                if ($this->db->affectedRows() >= 1) {
                    echo "Judge Entry Successfully Closed.";
                }
            }
        }
        else {
            $judge3 = 0;
            $builder = $this->db->table('judge_group');
            $query = $builder->select('id, p1, p2, p3')
                ->where(['p1' => $judge1, 'p2' => $judge2, 'p3' => $judge3, 'to_dt' => NULL])
                ->get();
            if (count($query->getResultArray()) >= 1) {
                $builder = $this->db->table('judge_group');
                $builder->set(['to_dt' => $to_dt, 'to_dt_ent_dt' => 'NOW()'])
                    ->where(['p1' => $judge1, 'p2' => $judge2, 'p3' => $judge3, 'to_dt' => NULL])
                    ->update();
                if ($this->db->affectedRows() >= 1) {
                    echo "Judge Entry Successfully Closed.";
                }
            }
        }
    }

    function transfer_judge_category($judge_from, $judge_to, $usercode, $mf)
    {
        if ($judge_from != '' && $judge_to != '' && $usercode != '') {
            $condition = '';
            if ($mf === 'M' || $mf === 'F') {
                $condition = " AND m_f = :mf:";
            }
            $sql_insert = "INSERT INTO master.judge_category (j1, submaster_id, priority, from_dt, ent_dt, usercode, display, to_dt, to_dt_ent_dt, to_dt_usercode, m_f) SELECT :judge_to:, submaster_id, priority, from_dt, NOW(), :usercode:, display, to_dt, to_dt_ent_dt, to_dt_usercode, m_f FROM master.judge_category WHERE j1 = :judge_from: AND to_dt = NULL $condition";
            $this->db->query($sql_insert, [
                'judge_to' => $judge_to,
                'usercode' => $usercode,
                'judge_from' => $judge_from,
                'mf' => ($mf === 'M' || $mf === 'F') ? $mf : null
            ]);
            if ($this->db->affectedRows() >= 1) {
                $sql_update = "UPDATE master.judge_category SET to_dt = CURRENT_DATE, to_dt_usercode = :usercode:, to_dt_ent_dt = NOW() WHERE j1 = :judge_from: $condition AND (to_dt = '' OR to_dt = NULL OR to_dt IS NULL)";
                $this->db->query($sql_update, [
                    'usercode' => $usercode,
                    'judge_from' => $judge_from,
                    'mf' => ($mf === 'M' || $mf === 'F') ? $mf : null
                ]);
                if ($this->db->affectedRows() >= 1) {
                    return "Judge Category Updated Successfully!!";
                } else {
                    return "There is some problem. Please contact Computer-Cell.";
                }
            }
        }
    }
}
