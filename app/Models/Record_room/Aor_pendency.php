<?php

namespace App\Models\Record_room;

use CodeIgniter\Model;

class Aor_pendency extends Model
{
    // protected $table = '"master"."bar"';

    public function getAorName($aor_no = "")
    {
        $builder = $this->db->table('master.bar');
        $builder->select("bar_id bid, concat(title,' ',name) adv_name")->where('aor_code', $aor_no);
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $row = $query->getRow()->bid;
            return $row;
        } else {
            return false;
        }
    }

    public function GetAllCaseType()
    {
        $builder = $this->db->table('master.casetype');
        $builder->select('casecode, skey, casename, short_description');
        $builder->where(['display' => 'Y', 'casecode !=' => 9999]);
        $builder->whereNotIn('casecode', [9999, 15, 16]);
        $builder->orderBy('casecode');
        $builder->orderBy('short_description');
        return $builder->get()->getResultArray();
    }

    public function fetchdetails($bar_id)
    {
        $db = \Config\Database::connect();

        $subQuery1 = $db->table('main a')
            ->select('us.section_name, us.id AS sec_id, a.diary_no, advocate_id')
            ->join('advocate b', 'a.diary_no = b.diary_no')
            ->join('master.da_case_distribution b', 'b.case_type = COALESCE(NULLIF(active_casetype_id, 0), casetype_id) AND ref_agency_state_id = state AND reg_year BETWEEN b.case_f_yr AND b.case_t_yr', 'left')
            ->join('master.users u', 'b.dacode = u.usercode AND u.display = "Y"', 'left')
            ->join('master.usersection us', 'u.section = us.id AND us.display = "Y"', 'left')
            ->where('b.advocate_id', '2')
            ->where('c_status', 'D')
            ->where('b.display', 'Y')
            ->groupBy('a.diary_no, us.section_name, us.id, advocate_id');
           
            
            $subQuery2 = $db->table('main a')
            ->select('us.section_name, us.id AS sec_id, a.diary_no, advocate_id')
            ->join('advocate b', 'a.diary_no = b.diary_no')
            ->join('master.da_case_distribution_tri b', 'b.case_type = COALESCE(NULLIF(active_casetype_id, 0), casetype_id) AND ref_agency_state_id = state AND reg_year BETWEEN b.case_f_yr AND b.case_t_yr', 'left')
            ->where('b.advocate_id', '2')
            ->where('b.display', 'Y')
            ->where('c_status', 'D')
            ->groupBy('a.diary_no, us.section_name, us.id, advocate_id');
            
            $subQuerySql = $subQuery1->getCompiledSelect() . ' UNION ' . $subQuery2->getCompiledSelect();
            
            // echo "<pre>"; print_r($subQuerySql);
        $query = $db->table('advocate a')
            ->select('
        DISTINCT CONCAT(SUBSTRING(CAST(a.diary_no AS text) FROM 1 FOR LENGTH(CAST(a.diary_no AS text)) - 4), \'/\', SUBSTRING(CAST(a.diary_no AS text) FROM -4)) AS "Diary_no",
        CONCAT(reg_no_display, \' @ \', CONCAT(SUBSTRING(CAST(a.diary_no AS text) FROM 1 FOR LENGTH(CAST(a.diary_no AS text)) - 4), \'/\', SUBSTRING(CAST(a.diary_no AS text) FROM -4))) AS no,
        SUBSTRING(CAST(a.diary_no AS text) FROM -4) AS dyear, a.diary_no,
        CONCAT(pet_name, \' VS \', res_name) AS Causetitle,
        CASE 
            WHEN h.conn_key = 0 THEN \'MAIN\'
            WHEN h.diary_no = h.conn_key THEN \'Main\'
            ELSE \'Connected\'
        END AS Main_Connected,
        CASE 
            WHEN c_status = \'P\' THEN \'Pending\'
            ELSE \'Disposed\'
        END AS status
    ')
            ->join('main b', 'a.diary_no = b.diary_no')
            ->join('master.users c', 'c.usercode = b.dacode AND c.display = "Y"', 'left')
            ->join('heardt h', 'h.diary_no = b.diary_no', 'left')
            ->join('master.usersection d', 'd.id = c.section AND d.display = "Y"', 'left')
            ->join("($subQuerySql) x", 'x.diary_no = a.diary_no AND x.advocate_id = a.advocate_id', 'left')
            ->where('a.advocate_id', '2')
            ->where('a.display', 'Y')
            ->where('c_status', 'D') 
            ->orderBy('dyear')
            ->orderBy('a.diary_no')
            ->orderBy('"Diary_no"');

        return $result = $query->get()->getResult();
    }
}
