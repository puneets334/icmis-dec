<?php

namespace App\Models\Entities;

use CodeIgniter\Model;

class Main extends Model
{
    protected $table = 'main';
    protected $primaryKey = 'diary_no';
    // protected $allowedFields = ['fil_no', 'fil_dt', 'lastorder', 'pet_name', 'res_name', 'c_status'];


    public function getCaseDetails($dno)
    {
        if (empty($dno)) {
            return null;
        }
    
        $builder = $this->db->table('main a');
        $builder->select("aa.next_dt as advance_list_date, a.diary_no_rec_date, a.fil_dt, 
            a.lastorder,a.pet_name, a.res_name,a.c_status,b.listorder, b.next_dt,b.mainhead, 
            b.subhead, 
            b.clno, 
            b.brd_slno, 
            b.roster_id, 
            b.judges,
            b.board_type, 
            b.main_supp_flag, 
            b.listorder, 
            b.tentative_cl_dt, 
            sitting_judges, 
            c.remark, 
            case_grp as side, 
            b.is_nmd
        ");
        $builder->join('heardt b', 'a.diary_no = b.diary_no', 'left');
        $builder->join('brdrem c', 'a.diary_no = c.diary_no', 'left');
        $builder->join('advance_allocated aa', 'b.diary_no = aa.diary_no AND b.next_dt = aa.next_dt', 'left');
        $builder->where('a.diary_no', $dno);
       

    
        $query = $builder->get();
        echo "$this->db->getLastQuery()";die;
        return $query->getRowArray();
    }
    public function getDiaryNo($ct, $cn, $cy)
    {
        if (empty($ct) || empty($cn) || empty($cy)) {
            return 0; // Return 0 or handle error appropriately
        }

        // First part of the query
        $builder = $this->db->table('main');
        $builder->select("SUBSTRING(diary_no, 1, LENGTH(diary_no) - 4) as dn, SUBSTRING(diary_no, -4) as dy");

        $builder->groupStart()
            ->where("SUBSTRING_INDEX(fil_no, '-', 1)", $ct)
            ->where("$cn BETWEEN CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no, '-', 2), '-', -1) AS UNSIGNED) AND CAST(SUBSTRING_INDEX(fil_no, '-', -1) AS UNSIGNED)")
            ->where("IF(reg_year_mh = 0, YEAR(fil_dt) = $cy, reg_year_mh = $cy)")
            ->groupEnd();

        // Second part of the query using OR condition
        $builder->orGroupStart()
            ->where("SUBSTRING_INDEX(fil_no_fh, '-', 1)", $ct)
            ->where("$cn BETWEEN CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no_fh, '-', 2), '-', -1) AS UNSIGNED) AND CAST(SUBSTRING_INDEX(fil_no_fh, '-', -1) AS UNSIGNED)")
            ->where("IF(reg_year_fh = 0, YEAR(fil_dt_fh) = $cy, reg_year_fh = $cy)")
            ->groupEnd();

        $query = $builder->get();

        $result = $query->getRowArray();
        return $result ? $result['dn'] . $result['dy'] : 0;
    }
 
    

    public function getCategory($dno)
    {
        if (empty($dno)) {
            return ''; // Return an empty string or handle error appropriately
        }

        $builder = $this->db->table('mul_category a');
        $builder->select('submaster_id, sub_name1, sub_name2, sub_name3, sub_name4');
        $builder->join('submaster b', 'a.submaster_id = b.id', 'left');
        $builder->where('a.display', 'Y');
        $builder->where('a.diary_no', $dno);

        $query = $builder->get();

        $category = '';
        foreach ($query->getResultArray() as $row) {
            $category .= $row['sub_name1'] . '-' . $row['sub_name2'] . '-' . $row['sub_name3'] . '-' . $row['sub_name4'] . '<br>';
        }
        return $category;
    }


    public function getCaseType($dno)
    {
        if (empty($dno)) {
            return null; // Return null or handle error appropriately
        }

        $builder = $this->db->table('main a');
        $builder->select('fil_no, fil_dt, fil_no_fh, fil_dt_fh, short_description');
        $builder->select("COALESCE(NULLIF(reg_year_mh, 0), EXTRACT(YEAR FROM a.fil_dt)) AS m_year");
        $builder->select("COALESCE(NULLIF(reg_year_fh, 0), EXTRACT(YEAR FROM a.fil_dt_fh)) AS f_year");
        $builder->join('casetype b', 'SUBSTR(fil_no, 1, 2) = b.casecode', 'left');
        $builder->where('a.diary_no', $dno);

        $query = $builder->get();
        return $query->getRowArray();
    }


    public function getMainCase($dno)
    {
        if (empty($dno)) {
            return null; // Return null or handle error appropriately
        }

        $builder = $this->db->table('conct');
        $builder->select('conn_key, diary_no');
        $builder->where('conn_key', $dno);
        $builder->orWhere('diary_no', $dno);

        $query = $builder->get();
        return $query->getRowArray();
    }


    public function getAlreadyEntries($dno)
    {
        if (empty($dno)) {
            return []; // Return an empty array or handle error appropriately
        }

        $builder1 = $this->db->table('heardt h');
        $builder1->select('jcode, GROUP_CONCAT(jname, \' \') AS jname, h.diary_no, \'C\' AS notbef, ent_dt, res_add');
        $builder1->join('judge j', 'FIND_IN_SET(jcode, coram) > 0', 'inner');
        $builder1->join('not_before_reason', 'list_before_remark = res_id', 'left');
        $builder1->where('h.diary_no', $dno);
        $builder1->groupBy('h.diary_no');

        $builder2 = $this->db->table('not_before');
        $builder2->select('jcode, jname, diary_no, not_before.notbef, ent_dt, not_before_reason.res_add');
        $builder2->join('judge j', 'jcode = j1', 'left');
        $builder2->join('not_before_reason', 'not_before.res_id = not_before_reason.res_id', 'left');
        $builder2->where('diary_no', $dno);

        // Combine the queries with UNION
        $query = $this->db->query("
        ({$builder1->getCompiledSelect()})
        UNION
        ({$builder2->getCompiledSelect()})
    ");

        return $query->getResultArray();
    }


    public function getAdvanceList($next_dt)
    {
        if (empty($next_dt)) {
            return []; // Return an empty array or handle error appropriately
        }

        $builder = $this->db->table('advance_allocated aa');
        $builder->select('DISTINCT aa.next_dt');
        $builder->join('advance_cl_printed acp', 'aa.next_dt = acp.next_dt AND acp.display = \'Y\'', 'left');
        $builder->where('aa.next_dt', $next_dt);
        $builder->where('acp.id IS NULL');
        $builder->orderBy('aa.next_dt');

        $query = $builder->get();
        return $query->getResultArray();
    }
}
