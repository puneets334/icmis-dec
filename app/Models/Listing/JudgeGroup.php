<?php

namespace App\Models\Listing;

use CodeIgniter\Model;

class JudgeGroup extends Model
{
    protected $table = 'judge_group';
    protected $primaryKey = 'p1';

    public function getJudgeGroupForRegularDay($cldt)
    {
        $cldt = $this->db->escapeString($cldt);

        $sql = "SELECT jg.p1, jg.p2, jg.p3, j.abbreviation, 
                (SELECT 5 old_limit 
                 FROM (SELECT (@a:=@a+1) SNo, s.* 
                       FROM sc_working_days s, (SELECT @a:= 0) AS b 
                       WHERE WEEK(working_date) = WEEK('$cldt') 
                         AND is_holiday = 0 
                         AND is_nmd = 1 
                         AND display = 'Y' 
                         AND YEAR(working_date) = YEAR('$cldt') 
                       ORDER BY working_date) a 
                 WHERE working_date = '$cldt') AS old_limit
                FROM judge_group jg
                LEFT JOIN master.judge j ON j.jcode = jg.p1
                WHERE jg.to_dt IS NULL
                  AND jg.display = 'Y'
                  AND j.is_retired != 'Y'
                ORDER BY j.judge_seniority";

        return $this->db->query($sql)->getResult();
    }



    public function getJudgeGroupForMiscDay($cldt)
    {
        $builder = $this->db->table('judge_group jg');
        $builder->select('jg.p1, jg.p2, jg.p3, j.abbreviation, jg.fresh_limit, 5 as old_limit');
        $builder->join('master.judge j', 'j.jcode = jg.p1');
        $builder->where('jg.to_dt IS NULL');
        $builder->where('jg.display', 'Y');
        $builder->where('j.is_retired !=', 'Y');
        $builder->orderBy('j.judge_seniority');

        return $builder->get()->getResult();
    }

    public function getJudgeDetails($listingDate)
    {
        $builder = $this->db->table('judge_group jg');
        $builder->select('jg.p1, jg.p2, jg.p3, j.abbreviation, jg.fresh_limit, IFNULL(listed, 0) as listed');
        $builder->join('master.judge j', 'j.jcode = jg.p1', 'left');
        $subQuery = $this->db->table('advance_allocated h')
            ->select('h.j1, COUNT(h.diary_no) listed')
            ->join('main m', 'h.diary_no = m.diary_no', 'left')
            ->join('advanced_drop_note d', 'd.diary_no = h.diary_no AND d.cl_date = h.next_dt', 'left')
            ->where('d.diary_no IS NULL')
            ->where('h.next_dt', $listingDate)
            ->whereIn('h.board_type', ['J'])
            ->whereIn('h.main_supp_flag', [1, 2])
            ->whereIn('m.diary_no', ['m.conn_key', '', null, '0'])
            ->groupBy('h.j1');

        $builder->join("($subQuery) b", 'b.j1 = jg.p1', 'left');
        $builder->where('j.is_retired !=', 'Y');
        $builder->where('jg.to_dt', '0000-00-00');
        $builder->where('jg.display', 'Y');
        $builder->groupBy('jg.p1');
        $builder->orderBy('j.judge_seniority');

        return $builder->get()->getResultArray();
    }

    //My Code

    public function getIsNMD($date)
    {
        return $this->db->table('master.sc_working_days')
            ->select('is_nmd')
            ->where(['working_date' => $date, 'is_holiday' => 0, 'display' => 'Y'])
            ->get()
            ->getRowArray();
    }

   
}
