<?php

namespace App\Models\PaperBook;

use CodeIgniter\Model;

class FixedDateMattersModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    public function getNextDate()
    {
        $query = $this->db->query("SELECT (CURRENT_DATE + INTERVAL '9 days')::DATE AS next_date;");
        $nextDateRow = $query->getRow();
        $nt = $nextDateRow ? $nextDateRow->next_date : null;
        $ss = $this->next_date($nt, 1);
        return $ss;
    }

    function next_date($date, $day)
    {
        $nxt_dt = $date;
        $count = 1;
        while ($count <= $day) {
            $ch = $this->is_holiday($nxt_dt);
            
            if ($ch == 1) {
                $nxt_dt = date('Y-m-d', strtotime($nxt_dt . '+1day'));
                continue;
            } else {
                if ($count == $day) {
                    return $nxt_dt;
                }
                $count++;
                $nxt_dt = date('Y-m-d', strtotime($nxt_dt . '+1day'));
                return $nxt_dt;
            }
        }
    }

    function is_holiday($date)
    {
        $builder = $this->db->table('master.holidays');
        $builder->select('hdate')->where('hdate', $date);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return 1;
        } else {
            return 0;
        }                
    }

    public function get_fixed_date_matters($next_date)
    {
        $results = [];
        $builder = $this->db->table('heardt');
        $subquery = $this->db->table('case_remarks_multiple c')
        ->select('c.diary_no, MAX(cl_date) AS max_cl_dt')
        ->where('LENGTH(jcodes) > 4')
        ->groupBy('c.diary_no');
        $subQueryResult = $subquery->getCompiledSelect();
        
        $builder->select([
            'DISTINCT(main.diary_no) AS dno',
            "CONCAT(reg_no_display, ' @ ', CONCAT(SUBSTR(main.diary_no::TEXT, 1, LENGTH(main.diary_no::TEXT) - 4), '/', SUBSTR(main.diary_no::TEXT, -4))) AS diary_no", // Cast to TEXT
            "CONCAT(pet_name, ' vs ', res_name) AS ct",
            'heardt.listorder',
            'lastorder',
            "TO_CHAR(heardt.next_dt, 'DD-MM-YYYY') AS next_dt",
            "TO_CHAR(c.cl_date, 'DD-MM-YYYY') AS last_date",
            'NOW() AS t',
            'c.jcodes',
            'j.judge_seniority'
        ])
        ->join('main', 'heardt.diary_no = main.diary_no')
        ->join("($subQueryResult) AS c1", 'c1.diary_no = heardt.diary_no', 'left')
        ->join('case_remarks_multiple c', 'c.diary_no = c1.diary_no AND c.cl_date = c1.max_cl_dt', 'left')
        ->join('master.judge j', "j.jcode = ANY(string_to_array(c.jcodes, ',')::int[])", 'left')
        ->where('heardt.listorder', 4)
        ->where('main_supp_flag', 0)
        ->where('heardt.next_dt', $next_date)
        ->where('board_type', 'J')
        ->where('c_status', 'P')
        ->orderBy('j.judge_seniority');
        
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $records = $query->getResultArray();
            $results = $records;
            foreach($records as $index => $record){
                $results[$index]['jname'] = $this->getJudges($record['jcodes']);
            }
        }
        
        return $results;
    }

    public function getJudges($jcodes)
    {
        $return = null;
        if (!is_null($jcodes) && is_string($jcodes)) {
            $jcodesArray = !empty($jcodes) ? explode(',', rtrim($jcodes, ',')) : [];
            $builder = $this->db->table('master.judge');
            $builder->select('STRING_AGG(jname, \', \') AS jnames'); 
            $builder->whereIn('jcode', $jcodesArray);
            $query = $builder->get();
            if ($query->getNumRows() > 0) {
                $return = $query->getRow()->jnames;
            }
        }
        return $return;
    }
}
