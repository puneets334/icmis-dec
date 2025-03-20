<?php

namespace App\Models\Filing;

use CodeIgniter\Model;

class Model_fil_trap_users extends Model
{
    protected $table      = 'fil_trap_users';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id', 'usertype', 'usercode', 'display', 'entuser', 'ent_dt', 'upuser', 'updt', 'user_type', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


    public function getUserTrapInfo($usercode)
    {
        $sql = "SELECT usertype,type_name,disp_flag FROM fil_trap_users a 
                LEFT JOIN master.usertype b ON usertype=b.id AND b.display='E' WHERE usercode=$usercode AND a.display='Y' ";
        $query = $this->db->query($sql);
        $return = $query->getRowArray();
        return $return;
    }

    public function getReportData($cat, $ref, $fil, $ucode, $icmic_empid)
    {
        $cat=0; $ref=0; $fil=0;

        $fil_trap_type_q = "SELECT usertype,type_name,disp_flag FROM fil_trap_users a 
                LEFT JOIN master.usertype b ON usertype=b.id AND b.display='E' WHERE usercode=$ucode AND a.display='Y' ";

        $fil_trap_type_rs = $this->db->query($fil_trap_type_q);
        $fil_trap_type_row = $fil_trap_type_rs->getRowArray();;
        // pr($fil_trap_type_row);
        if (!empty($fil_trap_type_row)) {
            if ($fil_trap_type_row['usertype'] == 104)
                $ref = 1;
            if ($fil_trap_type_row['usertype'] == 105)
                $cat = 1;
            if ($fil_trap_type_row['usertype'] == 101)
                $fil = 1;
        }
        if($cat==0 && $ref==0){
            if($fil==1){
                $query = "SELECT ec.efiling_no, a.*,pet_name,res_name,name d_by_name FROM
                (
                    SELECT diary_no,d_by_empid,disp_dt,remarks,rece_dt,comp_dt,other,d_to_empid FROM fil_trap 
                    WHERE d_by_empid=$icmic_empid AND DATE(disp_dt) BETWEEN '".revertDate_hiphen($_REQUEST['from'])."' AND '".revertDate_hiphen($_REQUEST['to'])."'
                    UNION
                    SELECT diary_no,d_by_empid,disp_dt,remarks,rece_dt,comp_dt,other,d_to_empid FROM fil_trap_his 
                    WHERE d_by_empid=$icmic_empid AND DATE(disp_dt) BETWEEN '".revertDate_hiphen($_REQUEST['from'])."' AND '".revertDate_hiphen($_REQUEST['to'])."'
                )a JOIN main b ON a.diary_no=b.diary_no
                LEFT JOIN master.users u ON d_by_empid=empid
                left join efiled_cases ec on ec.diary_no = b.diary_no and ec.display = 'Y'
                ORDER BY disp_dt DESC";
               
            }
            else{

                 $query = "SELECT ec.efiling_no, a.*,pet_name,res_name,name d_by_name FROM
                (
                    SELECT diary_no,d_by_empid,disp_dt,remarks,rece_dt,comp_dt,other,d_to_empid FROM fil_trap 
                    WHERE r_by_empid=$icmic_empid AND DATE(comp_dt) BETWEEN '".revertDate_hiphen($_REQUEST['from'])."' AND '".revertDate_hiphen($_REQUEST['to'])."'
                    UNION
                    SELECT diary_no,d_by_empid,disp_dt,remarks,rece_dt,comp_dt,other,d_to_empid FROM fil_trap_his 
                    WHERE r_by_empid=$icmic_empid AND DATE(comp_dt) BETWEEN '".revertDate_hiphen($_REQUEST['from'])."' AND '".revertDate_hiphen($_REQUEST['to'])."'
                )a JOIN main b ON a.diary_no=b.diary_no
                LEFT JOIN master.users u ON d_by_empid=empid
                left join efiled_cases ec on ec.diary_no = b.diary_no and ec.display = 'Y'
                ORDER BY disp_dt DESC";
            }
        }else{
            if($cat==1){
                $type_rep = $_REQUEST['type_rep'];
                if($_REQUEST['type_rep']=='C'){
                    $query = "SELECT ec.efiling_no, a.*, pet_name, res_name, u.name AS d_by_name, u1.name AS o_name,
                            STRING_AGG(DISTINCT category_sc_old || '-' || sub_name1 || ':' || sub_name4, ', ') AS cat_name,
                            STRING_AGG(CASE WHEN notbef = 'B' THEN j.jname END, ', ') AS beforejudgegrp,
                            STRING_AGG(CASE WHEN notbef = 'N' THEN j.jname END, ', ') AS notbeforejudgegrp,
                            STRING_AGG(DISTINCT j2.jname, ', ') AS coramjudges
                        FROM (
                            SELECT diary_no, d_by_empid, disp_dt, remarks, rece_dt, comp_dt, other, 'C' AS sendto
                            FROM fil_trap 
                            WHERE r_by_empid = (SELECT empid FROM master.users WHERE usertype = 59 AND name ILIKE '%CATEGORIZATION%') 
                            AND comp_dt::date BETWEEN'".revertDate_hiphen($_REQUEST['from'])."' AND '".revertDate_hiphen($_REQUEST['to'])."'
                            UNION
                            SELECT diary_no, d_by_empid, disp_dt, remarks, rece_dt, comp_dt, other, 'T' AS sendto
                            FROM fil_trap_his 
                            WHERE r_by_empid = (SELECT empid FROM master.users WHERE usertype = 59 AND name ILIKE '%CATEGORIZATION%') 
                            AND comp_dt::date BETWEEN'".revertDate_hiphen($_REQUEST['from'])."' AND '".revertDate_hiphen($_REQUEST['to'])."'
                            UNION
                            SELECT diary_no, d_by_empid, disp_dt, remarks, rece_dt, comp_dt, other, 'T' AS sendto
                            FROM fil_trap 
                            WHERE d_by_empid = (SELECT empid FROM master.users WHERE usertype = 59 AND name ILIKE '%CATEGORIZATION%') 
                            AND disp_dt::date BETWEEN'".revertDate_hiphen($_REQUEST['from'])."' AND '".revertDate_hiphen($_REQUEST['to'])."'
                            UNION
                            SELECT diary_no, d_by_empid, disp_dt, remarks, rece_dt, comp_dt, other, 'T' AS sendto
                            FROM fil_trap_his 
                            WHERE d_by_empid = (SELECT empid FROM master.users WHERE usertype = 59 AND name ILIKE '%CATEGORIZATION%') 
                            AND disp_dt::date BETWEEN'".revertDate_hiphen($_REQUEST['from'])."' AND '".revertDate_hiphen($_REQUEST['to'])."'
                        ) a 
                        JOIN main b ON a.diary_no = b.diary_no
                        LEFT JOIN mul_category mc ON a.diary_no = mc.diary_no AND mc.display = 'Y'
                        LEFT JOIN  master.submaster s ON mc.submaster_id = s.id AND s.display = 'Y'
                        LEFT JOIN master.users u ON d_by_empid = u.empid
                        LEFT JOIN master.users u1 ON other = u1.empid
                        LEFT JOIN not_before nb ON a.diary_no::text = nb.diary_no
                        LEFT JOIN  master.judge j ON nb.j1 = j.jcode
                        LEFT JOIN heardt h ON a.diary_no = h.diary_no
                        LEFT JOIN  master.judge j2 ON j2.jcode = ANY (string_to_array(h.coram, ',')::int[])
                        LEFT JOIN efiled_cases ec ON ec.diary_no = b.diary_no AND ec.display = 'Y'
                        GROUP BY a.sendto,ec.efiling_no, a.diary_no, a.d_by_empid, a.disp_dt, a.remarks, a.rece_dt, a.comp_dt, a.other, pet_name, res_name, u.name, u1.name
                        ORDER BY a.comp_dt";
                    
                }else
                    $query = "SELECT ec.efiling_no, 
                                a.*, 
                                pet_name, 
                                res_name, 
                                u.name AS d_by_name,
                                STRING_AGG(DISTINCT category_sc_old || '-' || sub_name1 || ':' || sub_name4, ', ') AS cat_name,
                                STRING_AGG(CASE WHEN notbef = 'B' THEN j.jname END, ', ') AS beforejudgegrp,
                                STRING_AGG(CASE WHEN notbef = 'N' THEN j.jname END, ', ') AS notbeforejudgegrp,
                                STRING_AGG(DISTINCT j2.jname, ', ') AS coramjudges
                            FROM (
                                SELECT diary_no, d_by_empid, disp_dt, remarks, rece_dt, comp_dt, other, 'C' AS sendto 
                                FROM fil_trap 
                                WHERE r_by_empid = (SELECT empid FROM master.users WHERE usertype = 59 remarksAND name ILIKE '%CATEGORIZATION%') 
                                AND comp_dt::date BETWEEN '".revertDate_hiphen($_REQUEST['from'])."' AND '".revertDate_hiphen($_REQUEST['to'])."'
                                                    AND other=$icmic_empid
                                UNION
                                SELECT diary_no, d_by_empid, disp_dt, remarks, rece_dt, comp_dt, other, 'T' AS sendto 
                                FROM fil_trap_his 
                                WHERE r_by_empid = (SELECT empid FROM master.users WHERE usertype = 59 AND name ILIKE '%CATEGORIZATION%') 
                                AND comp_dt::date BETWEEN '".revertDate_hiphen($_REQUEST['from'])."' AND '".revertDate_hiphen($_REQUEST['to'])."'
                                                    AND other=$icmic_empid
                                UNION
                                SELECT diary_no, d_by_empid, disp_dt, remarks, rece_dt, comp_dt, other, 'T' AS sendto 
                                FROM fil_trap 
                                WHERE d_by_empid = (SELECT empid FROM master.users WHERE usertype = 59 AND name ILIKE '%CATEGORIZATION%') 
                                AND disp_dt::date BETWEEN '".revertDate_hiphen($_REQUEST['from'])."' AND '".revertDate_hiphen($_REQUEST['to'])."'
                                                    AND other=$icmic_empid
                                UNION
                                SELECT diary_no, d_by_empid, disp_dt, remarks, rece_dt, comp_dt, other, 'T' AS sendto 
                                FROM fil_trap_his 
                                WHERE d_by_empid = (SELECT empid FROM master.users WHERE usertype = 59 AND name ILIKE '%CATEGORIZATION%') 
                                AND disp_dt::date BETWEEN '".revertDate_hiphen($_REQUEST['from'])."' AND '".revertDate_hiphen($_REQUEST['to'])."'
                                                    AND other=$icmic_empid
                            ) a  JOIN main b ON a.diary_no = b.diary_no
                        LEFT JOIN master.users u ON d_by_empid = u.empid
                        LEFT JOIN mul_category mc ON a.diary_no = mc.diary_no AND mc.display = 'Y'
                        LEFT JOIN master.submaster s ON mc.submaster_id = s.id AND s.display = 'Y'
                        LEFT JOIN not_before nb ON a.diary_no::text = nb.diary_no
                        LEFT JOIN master.judge j ON nb.j1 = j.jcode
                        LEFT JOIN heardt h ON a.diary_no = h.diary_no
                        LEFT JOIN master.judge j2 ON j2.jcode = ANY (string_to_array(h.coram, ',')::int[])
                        LEFT JOIN efiled_cases ec ON ec.diary_no = b.diary_no AND ec.display = 'Y'
                        GROUP BY a.sendto,ec.efiling_no, a.diary_no, a.d_by_empid, a.disp_dt, a.remarks, a.rece_dt, a.comp_dt, a.other, pet_name, res_name, u.name
                        ORDER BY a.comp_dt";
                   
            }else if($ref==1){
                if($_REQUEST['type_rep']=='C')
                {

                    $query = "SELECT ec.efiling_no, a.*,pet_name,res_name,u.name d_by_name,u1.name o_name FROM
                    (
                        SELECT diary_no,d_by_empid,disp_dt,remarks,rece_dt,comp_dt,other FROM fil_trap 
                        WHERE r_by_empid=(SELECT empid FROM master.users WHERE usertype=59 AND name LIKE '%REFILING%') 
                        AND DATE(comp_dt) BETWEEN '".revertDate_hiphen($_REQUEST['from'])."' AND '".revertDate_hiphen($_REQUEST['to'])."'
                        UNION
                        SELECT diary_no,d_by_empid,disp_dt,remarks,rece_dt,comp_dt,other FROM fil_trap_his 
                        WHERE r_by_empid=(SELECT empid FROM master.users WHERE usertype=59 AND name LIKE '%REFILING%') 
                        AND DATE(comp_dt) BETWEEN '".revertDate_hiphen($_REQUEST['from'])."' AND '".revertDate_hiphen($_REQUEST['to'])."'
                    )a JOIN main b ON a.diary_no=b.diary_no
                    LEFT JOIN master.users u ON d_by_empid=u.empid
                    LEFT JOIN master.users u1 ON other=u1.empid
                    left join efiled_cases ec on ec.diary_no = b.diary_no and ec.display = 'Y'
                    ORDER BY disp_dt DESC";
                }else{
                    $query = "SELECT ec.efiling_no, a.*,pet_name,res_name,name d_by_name FROM
                    (
                        SELECT diary_no,d_by_empid,disp_dt,remarks,rece_dt,comp_dt,other FROM fil_trap 
                        WHERE r_by_empid=(SELECT empid FROM master.users WHERE usertype=59 AND name LIKE '%REFILING%') 
                        AND DATE(comp_dt) BETWEEN '".revertDate_hiphen($_REQUEST['from'])."' AND '".revertDate_hiphen($_REQUEST['to'])."'
                        AND other=$icmic_empid
                        UNION
                        SELECT diary_no,d_by_empid,disp_dt,remarks,rece_dt,comp_dt,other FROM fil_trap_his 
                        WHERE r_by_empid=(SELECT empid FROM master.users WHERE usertype=59 AND name LIKE '%REFILING%') 
                        AND DATE(comp_dt) BETWEEN '".revertDate_hiphen($_REQUEST['from'])."' AND '".revertDate_hiphen($_REQUEST['to'])."'
                        AND other=$icmic_empid
                    )a JOIN main b ON a.diary_no=b.diary_no
                    LEFT JOIN master.users u ON d_by_empid=empid
                    left join efiled_cases ec on ec.diary_no = b.diary_no and ec.display = 'Y'
                    ORDER BY disp_dt DESC";
                }
                      
            }
         
        }

        $result = $this->db->query($query);
        $return= $result->getResultArray();
        return $return;

    }
    public function sql_action_data($diary_no){
        $sql = "select remarks from fil_trap where diary_no=$diary_no";
        $query = $this->db->query($sql);
        $result = $query->getRowArray();
        return $result;
    }


    public function getTrapData($cat, $ref, $session_empid, $userType, $condition)
    {
        $builder = $this->db->table('fil_trap a')
            ->select("ec.efiling_no,a.uid,a.diary_no,a.d_by_empid,a.d_to_empid,a.disp_dt,a.remarks,e.name AS d_by_name,b.pet_name,b.res_name,a.rece_dt,b.nature,h.next_dt,h.main_supp_flag,
                 CASE WHEN h.board_type = 'C' THEN 'CHAMBER' 
                      WHEN h.board_type = 'J' THEN 'COURT'
                      ELSE 'REGISTRAR' 
                 END AS board_type, s.ref_special_category_filing_id, r.category_name")
            ->join('main b', 'a.diary_no = b.diary_no', 'left')
            ->join('master.users e', 'e.empid = a.d_by_empid', 'left')
            ->join('heardt h', 'b.diary_no = h.diary_no', 'left')
            ->join('efiled_cases ec', "ec.diary_no = b.diary_no AND ec.display = 'Y' AND ec.efiled_type = 'new_case'", 'left')
            ->join('special_category_filing s', 'a.diary_no = s.diary_no AND s.display = \'Y\'', 'left')
            ->join('master.ref_special_category_filing r', 's.ref_special_category_filing_id = r.id AND r.display = \'Y\'', 'left')
            ->where('a.d_to_empid', $session_empid)
            ->where('b.c_status', 'P')
            ->where('a.comp_dt IS NULL OR a.comp_dt IS NOT DISTINCT FROM NULL');

        if (!empty($condition)) {
            $builder->where($condition);
        }

        if ($cat == 1) {
            $builder->whereIn('d_to_empid', function ($subQuery) use ($userType) {
                $subQuery->select('empid')
                    ->from('fil_trap_users')
                    ->join('master.users', 'fil_trap_users.usercode = users.usercode')
                    ->where('fil_trap_users.usertype', $userType);
            });
        } elseif ($ref == 1) {
            $builder->where('a.d_to_empid', function ($subQuery) {
                $subQuery->select('empid')
                    ->from('master.users')
                    ->where('usertype', 59)
                    ->like('name', '%REFILING%');
            });
        } elseif ($ref == 2) {
            if (!empty($this->request->getVar('dno'))) {
                $builder->where('a.diary_no', $this->request->getVar('dno') . $this->request->getVar('dyr'));
            }
            $builder->whereIn('d_to_empid', function ($subQuery) {
                $subQuery->select('empid')
                    ->from('master.users')
                    ->groupStart()
                    ->where('usertype', 51)
                    ->like('name', '%FILING DISPATCH RECEIVE%')
                    ->groupEnd()
                    ->orGroupStart()
                    ->where('usertype', 59)
                    ->like('name', '%ADVOCATE CHAMBER SUB-SECTION%')
                    ->groupEnd();
            });
        }

        return $builder->orderBy('a.disp_dt', 'desc')->get()->getResultArray();
    }


    public function getCategorizationQuery($typeRep, $fromDate, $toDate, $icmic_empid)
    {
        $builder = $this->db->table('fil_trap')
            ->select('ec.efiling_no, a.*, pet_name, res_name, u.name as d_by_name, u1.name as o_name')
            ->join('main b', 'a.diary_no = b.diary_no', 'inner')
            ->join('mul_category mc', 'a.diary_no = mc.diary_no AND mc.display = \'Y\'', 'left')
            ->join('submaster s', 'mc.submaster_id = s.id AND s.display = \'Y\'', 'left')
            ->join('users u', 'd_by_empid = u.empid', 'left')
            ->join('users u1', 'other = u1.empid', 'left')
            ->join('not_before nb', 'a.diary_no = nb.diary_no', 'left')
            ->join('judge j', 'nb.j1 = j.jcode', 'left')
            ->join('heardt h', 'a.diary_no = h.diary_no', 'left')
            ->join('judge j2', 'FIND_IN_SET(j2.jcode, h.coram)', 'left')
            ->join('efile_cases ec', 'ec.diary_no = b.diary_no AND ec.display = \'Y\'', 'left')
            ->groupBy('a.diary_no')
            ->orderBy('comp_dt');

        if ($typeRep == 'C') {
            $builder->where("(
            SELECT empid FROM users WHERE usertype = 59 AND name LIKE '%CATEGORIZATION%'
        ) = r_by_empid AND DATE(comp_dt) BETWEEN ? AND ?", [$fromDate, $toDate]);
            $builder->orWhere("(
            SELECT empid FROM users WHERE usertype = 59 AND name LIKE '%CATEGORIZATION%'
        ) = d_by_empid AND DATE(disp_dt) BETWEEN ? AND ?", [$fromDate, $toDate]);
        } else {
            $builder->where("(
            SELECT empid FROM users WHERE usertype = 59 AND name LIKE '%CATEGORIZATION%'
        ) = r_by_empid AND DATE(comp_dt) BETWEEN ? AND ? AND other = ?", [$fromDate, $toDate, $icmic_empid]);
        }

        return $builder->getCompiledSelect();
    }

    public function getReFilingQuery($typeRep, $fromDate, $toDate)
    {
        $builder = $this->db->table('fil_trap')
            ->select('ec.efiling_no, a.*, pet_name, res_name, u.name as d_by_name, u1.name as o_name')
            ->join('main b', 'a.diary_no = b.diary_no', 'inner')
            ->join('users u', 'd_by_empid = u.empid', 'left')
            ->join('users u1', 'other = u1.empid', 'left')
            ->join('efile_cases ec', 'ec.diary_no = b.diary_no AND ec.display = \'Y\'', 'left')
            ->orderBy('comp_dt');

        $builder->where("(
        SELECT empid FROM users WHERE usertype = 58 AND name LIKE '%REFILING%'
    ) = r_by_empid AND DATE(comp_dt) BETWEEN ? AND ?", [$fromDate, $toDate]);

        return $builder->getCompiledSelect();
    }

    public function getRecords($cat, $ref, $dno, $fil_trap_type_row = null)
    {
        $builder = $this->db->table('fil_trap a');
        $builder->select('ec.efiling_no, a.uid, a.diary_no, d_by_empid, d_to_empid, disp_dt, remarks, e.name d_by_name, pet_name, res_name, rece_dt, nature, TO_CHAR(h.next_dt, \'DD-MM-YYYY\') as next_dt, h.main_supp_flag, CASE h.board_type WHEN \'C\' THEN \'CHAMBER\' WHEN \'J\' THEN \'COURT\' ELSE \'REGISTRAR\' END as board_type, ref_special_category_filing_id, category_name');
        $builder->join('main b', 'a.diary_no = b.diary_no', 'left');
        $builder->join('master.users e', 'e.empid = a.d_by_empid', 'left');
        $builder->join('heardt h', 'b.diary_no = h.diary_no', 'left');
        $builder->join('efiled_cases ec', 'ec.diary_no = b.diary_no and ec.display = \'Y\' and ec.efiled_type = \'new_case\'', 'left');
        $builder->join('special_category_filing s', 'a.diary_no = s.diary_no and s.display = \'Y\'', 'left');
        $builder->join('master.ref_special_category_filing r', 's.ref_special_category_filing_id = r.id and r.display = \'Y\'', 'left');

        if ($cat == 0 && $ref == 0) {
            $builder->where('d_to_empid', session()->get('icmic_empid'));
            $builder->where('comp_dt IS NULL'); // Handle NULL values in PostgreSQL
            $builder->where('b.c_status', 'P');
        } elseif ($cat == 1) {
            if ($fil_trap_type_row) {
                $builder->whereIn('d_to_empid', function ($subQuery) use ($fil_trap_type_row) {
                    $subQuery->select('empid')
                        ->from('fil_trap_users')
                        ->join('master.users', 'fil_trap_users.usercode = users.usercode')
                        ->where('fil_trap_users.usertype', $fil_trap_type_row['usertype']);
                });
            }
            $builder->where('comp_dt IS NULL'); // Handle NULL values in PostgreSQL
            $builder->where('b.c_status', 'P');
        } elseif ($ref == 1) {
            $builder->where('d_to_empid', function ($subQuery) {
                $subQuery->select('empid')
                    ->from('master.users')
                    ->where('usertype', 59)
                    ->like('name', '%REFILING%');
            });
            $builder->where('comp_dt IS NULL'); // Handle NULL values in PostgreSQL
            $builder->where('b.c_status', 'P');
        } elseif ($ref == 2) {
            if (!empty($dno)) {
                $builder->where('a.diary_no', $dno);
            }
            $builder->whereIn('d_to_empid', function ($subQuery) {
                $subQuery->select('empid')
                    ->from('master.users')
                    ->whereIn('usertype', [51, 59])
                    ->like('name', '%FILING DISPATCH RECEIVE%')
                    ->orLike('name', '%ADVOCATE CHAMBER SUB-SECTION%');
            });
            $builder->where('comp_dt IS NULL'); // Handle NULL values in PostgreSQL
            $builder->where('b.c_status', 'P');
        }

        $builder->orderBy('disp_dt', 'desc');

        $query = $builder->get();
        return $query->getResultArray();
    }
}
