<?php

namespace App\Models\Listing;

use CodeIgniter\Model;

class WorkingDaysModel extends Model
{
    protected $table = 'master.sc_working_days';



    public function getHolidays()
    {
        return $this->select("TO_CHAR(working_date, 'DD-MM-YYYY') AS holidays")
            ->where('working_date >= CURRENT_DATE')
            ->where('display', 'Y')
            ->where('is_holiday', 1)
            ->findAll();
    }


    public function getJudgeAllocation($date, $p1, $board_type)
    {
        $m_f = '';
        $from_to_dt = '';

        if ($p1 == "M") {
            $m_f = "AND r.m_f = '1'";
            $from_to_dt = $board_type == 'R' ? "AND r.to_date = '0000-00-00'" : "AND r.from_date = '$date'";
        } else if ($p1 == "L") {
            $m_f = "AND r.m_f = '3'";
            $from_to_dt = "AND r.from_date = '$date'";
        } else if ($p1 == "S") {
            $m_f = "AND r.m_f = '4'";
            $from_to_dt = "AND r.from_date = '$date'";
        } else {
            $m_f = "AND r.m_f = '2'";
            $from_to_dt = "AND r.from_date = '$date'";
        }

        $sql = "SELECT jg.p1, jg.p2, jg.p3, j.abbreviation,
                       (SELECT 5 old_limit FROM 
                           (SELECT (@a:=@a+1) SNo, s.* FROM sc_working_days s, 
                            (SELECT @a:= 0) AS b 
                            WHERE WEEK(working_date) = WEEK('$date')
                            AND is_holiday = 0 
                            AND is_nmd = 1 
                            AND display = 'Y' 
                            AND YEAR(working_date) = YEAR('$date')
                            ORDER BY working_date) a 
                            WHERE working_date = '$date') old_limit
                FROM judge_group jg 
                LEFT JOIN judge j ON j.jcode = jg.p1
                WHERE jg.to_dt = '0000-00-00' 
                AND jg.display = 'Y' 
                AND j.is_retired != 'Y' 
                ORDER BY j.judge_seniority";

        return $this->db->query($sql)->getResultArray();
    }

    public function getJudgeDetails($date, $p1, $board_type)
    {
        $sql1 = "SELECT j1, COUNT(diary_no) listed,
                        SUM(CASE WHEN pre_after_notice = 'Pre_Notice' THEN 1 ELSE 0 END) Pre_Notice,
                        SUM(CASE WHEN pre_after_notice = 'After_Notice' THEN 1 ELSE 0 END) After_Notice  
                 FROM (SELECT DISTINCT h.diary_no, h.j1,
                              CASE WHEN (c.diary_no IS NULL AND (m.fil_no_fh = '' OR m.fil_no_fh IS NULL) 
                                        AND h.subhead NOT IN (813, 814))
                                   THEN 'Pre_Notice' ELSE 'After_Notice' END pre_after_notice
                       FROM advance_allocated h 
                       LEFT JOIN main m ON h.diary_no = m.diary_no 
                       LEFT JOIN advanced_drop_note d ON d.diary_no = h.diary_no AND d.cl_date = h.next_dt
                       LEFT JOIN case_remarks_multiple c ON c.diary_no = m.diary_no AND c.r_head IN (1, 3, 62, 181, 182, 183, 184)
                       WHERE d.diary_no IS NULL 
                       AND h.next_dt = '$date'                                          
                       AND h.j1 = '$p1' 
                       AND h.board_type = '$board_type'
                       AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2) 
                       AND h.clno = 2
                       AND (m.diary_no = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0') 
                       GROUP BY m.diary_no) h
                 GROUP BY h.j1";

        return $this->db->query($sql1)->getResultArray();
    }

    public function getIsNmd($working_date)
    {
        $builder = $this->db->table($this->table); 
        $builder->select('is_nmd');
        $builder->where('working_date', $working_date);
        $builder->where('is_holiday', 0);
        $builder->where('display', 'Y');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getRow(); 
        } else {
            return null;
        }
    }

    
    // public function getIsNMD($cldt)
    // {
    //     return $this->db->table('sc_working_days')
    //                     ->select('is_nmd')
    //                     ->where('working_date', $cldt)
    //                     ->where('is_holiday', 0)
    //                     ->where('display', 'Y')
    //                     ->get()
    //                     ->getRowArray();
    // }

    public function getJudges($isNMD, $cldt)
    {
        
        if ($isNMD == 1) {
            $sql = "SELECT jg.p1, jg.p2, jg.p3, j.abbreviation, 
                    (SELECT (CASE WHEN SNo = 1 THEN 15 ELSE 10 END) old_limit
                    FROM (SELECT (@a:=@a+1) SNo, s.*
                    FROM sc_working_days s, (SELECT @a:= 0) AS b
                    WHERE week(working_date) = week(?) AND is_holiday = 0
                    AND is_nmd = 1 AND display = 'Y' AND year(working_date) = year(?)
                    ORDER BY working_date) a WHERE working_date = ?) old_limit
                    FROM judge_group jg
                    LEFT JOIN judge j ON j.jcode = jg.p1
                    WHERE jg.to_dt = '0000-00-00' AND jg.display = 'Y' 
                    AND j.is_retired != 'Y'
                    ORDER BY j.judge_seniority";
        } else {
            $sql = "SELECT jg.p1, jg.p2, jg.p3, j.abbreviation, jg.fresh_limit, jg.old_limit
                    FROM judge_group jg 
                    LEFT JOIN judge j ON j.jcode = jg.p1
                    WHERE jg.to_dt = '0000-00-00' AND jg.display = 'Y' 
                    AND j.is_retired != 'Y'
                    ORDER BY j.judge_seniority";
        }

        return $this->db->query($sql, [$cldt, $cldt, $cldt])->getResultArray();
    }

    public function getAllocationDetails($cldt, $boardType, $judgeCode)
    {
        $sql = "SELECT j1, COUNT(diary_no) AS listed,
                       SUM(CASE WHEN pre_after_notice = 'Pre_Notice' THEN 1 ELSE 0 END) AS Pre_Notice,
                       SUM(CASE WHEN pre_after_notice = 'After_Notice' THEN 1 ELSE 0 END) AS After_Notice
                FROM (
                    SELECT DISTINCT h.diary_no, h.j1,
                    CASE WHEN (c.diary_no IS NULL AND (m.fil_no_fh = '' OR m.fil_no_fh IS NULL)
                    AND h.subhead NOT IN (813,814)) THEN 'Pre_Notice'
                    ELSE 'After_Notice' END AS pre_after_notice
                    FROM advance_allocated h 
                    LEFT JOIN main m ON h.diary_no = m.diary_no 
                    LEFT JOIN advanced_drop_note d ON d.diary_no = h.diary_no AND d.cl_date = h.next_dt
                    LEFT JOIN case_remarks_multiple c ON c.diary_no = m.diary_no AND c.r_head IN (1,3,62,181,182,183,184)
                    WHERE d.diary_no IS NULL 
                    AND h.next_dt = ? AND h.j1 = ? AND h.board_type = ?
                    AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2) 
                    AND (m.diary_no = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
                    GROUP BY m.diary_no) h
                GROUP BY h.j1";

        return $this->db->query($sql, [$cldt, $judgeCode, $boardType])->getRowArray();
    }

    
    public function getWorkingDays($date)
    {
        return $this->where('working_date', $date)
                    ->where('display', 'Y')
                    ->orderBy('working_date')
                    ->findAll();
    }

    public function getHearingRecords1($date, $mainhead, $listorder)
    {
        //$date = "2023-10-11";
        $builder = $this->db->table('main m')
                            ->select("tentative_section(CAST(m.diary_no AS TEXT)) as sec, u.name, mc.submaster_id, l.purpose, m.reg_no_display, m.pet_name, m.res_name, m.lastorder, h.*")
                            ->join('heardt h', 'CAST(h.diary_no AS TEXT) = CAST(m.diary_no AS TEXT)')
                            ->join('master.listing_purpose l', 'l.code = h.listorder')
                            ->join('mul_category mc', "mc.diary_no = m.diary_no AND mc.display = 'Y' AND mc.submaster_id NOT IN (911, 912, 914, 239, 240, 241, 242, 243)", 'left')
                            ->join('master.users u', 'u.usercode = m.dacode', 'left')
                            ->where('mc.diary_no IS NOT NULL')
                            ->where('m.c_status', 'P')
                           ->where("(CAST(m.diary_no AS TEXT) = m.conn_key OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL)")
                            ->where('h.main_supp_flag', 0)
                            ->where('h.mainhead', $mainhead)
                            ->where('h.next_dt IS NOT NULL')
                            ->where('h.roster_id', 0)
                            ->where('h.brd_slno', 0)
                            ->where('h.board_type', 'J');
        
        $listorder = f_selected_values($listorder);  
        if ($listorder != "all") {
            $builder->whereIn('h.listorder', explode(',', $listorder));
        }
    
        if ($mainhead == 'M') {
            $builder->where('h.next_dt', $date)
                    ->groupBy('h.diary_no, u.name, mc.submaster_id, l.purpose, m.reg_no_display, m.pet_name, m.res_name, m.lastorder, h.main_supp_flag, h.mainhead, h.next_dt, h.roster_id, h.brd_slno, h.board_type,l.priority,m.diary_no')
                    ->orderBy("l.priority, (CAST(SUBSTRING(CAST(h.diary_no AS TEXT) FROM CHAR_LENGTH(CAST(h.diary_no AS TEXT)) - 3 FOR 4) AS INTEGER)) ASC, (CAST(SUBSTRING(CAST(h.diary_no AS TEXT) FROM 1 FOR CHAR_LENGTH(CAST(h.diary_no AS TEXT)) - 4) AS INTEGER)) ASC");
        } else {
            $builder->join('vacation_advance_list v', 'v.diary_no = m.diary_no', 'inner')
                    ->where("(is_fixed = 'Y' OR is_deleted = 'f')")
                    ->where("CASE WHEN h.listorder IN (4,5,7,8) THEN 
                        CASE WHEN h.next_dt = '$date' THEN TRUE ELSE (h.next_dt <= CURRENT_DATE) END
                        ELSE h.next_dt > '1947-08-15' END")
                    ->groupBy('h.diary_no, u.name, mc.submaster_id, l.purpose, m.reg_no_display, m.pet_name, m.res_name, m.lastorder, h.main_supp_flag, h.mainhead, h.next_dt, h.roster_id, h.brd_slno, h.board_type,l.priority,m.diary_no')
                    ->orderBy("(CAST(SUBSTRING(CAST(h.diary_no AS TEXT) FROM CHAR_LENGTH(CAST(h.diary_no AS TEXT)) - 3 FOR 4) AS INTEGER)) ASC, (CAST(SUBSTRING(CAST(h.diary_no AS TEXT) FROM 1 FOR CHAR_LENGTH(CAST(h.diary_no AS TEXT)) - 4) AS INTEGER)) ASC");
        }
        
        return $builder->get()->getResultArray();
    }
    public function getHearingRecords($q_next_dt, $mainhead, $listorder = null)
    {
        $builder = $this->db->table('heardt h');

        $builder->select([
            'tentative_section(m.diary_no) AS sec',
            'u.name',
            'mc.submaster_id',
            'l.purpose',
            'm.reg_no_display',
            'm.pet_name',
            'm.res_name',
            'm.lastorder',
            'h.*',
        ]);

        $builder->join('main m', 'm.diary_no = h.diary_no');
        $builder->join('master.listing_purpose l', 'l.code = h.listorder');
        $builder->join('master.users u', 'u.usercode = m.dacode', 'left');
        $builder->join('mul_category mc', 'mc.diary_no = m.diary_no AND mc.display = \'Y\'', 'left');

        $builder->where('mc.diary_no IS NOT NULL');
        $builder->where('m.c_status', 'P');
        $builder->groupStart();
        $builder->where('(m.conn_key IS NULL OR m.conn_key = \'0\' OR CAST(m.conn_key AS BIGINT) = m.diary_no)');
        $builder->groupEnd();
        $builder->where('h.main_supp_flag', 0);
        $builder->where('h.mainhead', $mainhead);
        $builder->where('h.next_dt IS NOT NULL');
        $builder->where('h.roster_id', 0);
        $builder->where('h.brd_slno', 0);
        $builder->where('h.board_type', 'J');

        $listorder = f_selected_values($listorder);

        if ($listorder != "all") {
            $listorderArray = explode(',', $listorder);
            $listorderArray = array_filter($listorderArray);
            $listorderArray = array_map('trim', $listorderArray);
            $builder->whereIn('h.listorder', $listorderArray);
        }

        if ($mainhead === 'M') {    
            $builder->where('h.next_dt', $q_next_dt);
        } else {
            $builder->groupStart();
            $builder->whereIn('h.listorder', [4, 5, 7, 8]);
            $builder->orGroupStart();
            $builder->where('h.next_dt', $q_next_dt);
            $builder->orWhere('h.next_dt <= CURRENT_DATE');
            $builder->groupEnd();
            //$builder->orWhere('h.listorder NOT IN', [4, 5, 7, 8]);
            $builder->orWhere('h.listorder NOT IN (4, 5, 7, 8)');
            $builder->where('h.next_dt > \'1947-08-15\'');
            $builder->groupEnd();
        }
        $builder->orderBy('l.priority', 'ASC');
        // $builder->orderBy([
        //     'l.priority' => 'ASC',
        //     'CAST(RIGHT(h.diary_no, 4) AS INTEGER)' => 'ASC',
        //     'CAST(LEFT(h.diary_no, LENGTH(h.diary_no) - 4) AS INTEGER)' => 'ASC',
        // ]);
        return $builder->get()->getResultArray();
    }

   public function q_from_heardt_to_last_heardt($dno){
        $builder = $this->db->table('last_heardt');

        $heardtBuilder = $this->db->table('heardt h')
            ->select('h.*, m.lastorder')
            ->join('main m', 'm.diary_no = h.diary_no', 'left')
            ->where('h.diary_no', 42)
            ->where('h.diary_no >', 0);

        $dataToInsert = $heardtBuilder->get()->getResultArray();

        foreach ($dataToInsert as $j) {
            $existingRecord = $builder
                ->where('diary_no', $j['diary_no'])
                ->where('conn_key', $j['conn_key'])
                ->where('next_dt', $j['next_dt'])
                ->where('mainhead', $j['mainhead'])
                ->where('board_type', $j['board_type'])
                ->where('subhead', $j['subhead'])
                ->where('clno', $j['clno'])
                ->where('coram', $j['coram'])
                ->where('judges', $j['judges'])
                ->where('roster_id', $j['roster_id'])
                ->where('listorder', $j['listorder'])
                ->where('tentative_cl_dt', $j['tentative_cl_dt'])
                ->groupStart()
                    ->where('listed_ia', $j['listed_ia'])
                    ->orWhere('listed_ia IS NULL', null, false)
                ->groupEnd()
                ->groupStart()
                    ->where('list_before_remark', $j['list_before_remark'])
                    ->orWhere('list_before_remark IS NULL', null, false)
                ->groupEnd()
                ->where('no_of_time_deleted', $j['no_of_time_deleted'])
                ->where('is_nmd', $j['is_nmd'])
                ->where('main_supp_flag', $j['main_supp_flag'])
                 ->groupStart()
                    ->where('bench_flag', '')
                    ->orWhere('bench_flag IS NULL', null, false)
                ->groupEnd()
                ->get()->getRowArray();


            if (!$existingRecord) {
                $builder->insert($j);
            }
        }     
    }
  

    public function getConnKey($q_diary_no)
    {
        $builder = $this->db->table('main');
        $builder->select('GROUP_CONCAT(diary_no) as dno_c');
        $builder->where('conn_key', $q_diary_no);
        $builder->groupBy('conn_key');
        $query = $builder->get();
        $row = $query->getRow();
        return $row ? $row->dno_c : '';
    }

    public function checkNtlJudge($q_diary_no, $judges)
    {
        $judges = rtrim($judges, ',');
        $numRows = 0;

        $builder = $this->db->table('ntl_judge');
        $builder->whereIn('org_judge_id', explode(',', $judges));
        $builder->where('display', 'Y');
        $query = $builder->get();

        foreach ($query->getResult() as $row) {
            $subBuilder = $this->db->table('advocate');
            $subBuilder->whereIn('diary_no', explode(',', $q_diary_no));
            $subBuilder->where('advocate_id', $row->org_advocate_id);
            $subBuilder->where('display', 'Y');
            $subQuery = $subBuilder->get();

            if ($subQuery->getNumRows() > 0) {
                return 1;
            }
        }

        return $numRows;
    }

    public function checkNtlJudgeDept($q_diary_no, $judges)
    {
        $numRows = 0;

        $builder = $this->db->table('ntl_judge_dept');
        $builder->whereIn('org_judge_id', explode(',', $judges));
        $query = $builder->get();

        foreach ($query->getResult() as $row) {
            $subBuilder = $this->db->table('party');
            $subBuilder->whereIn('diary_no', explode(',', $q_diary_no));
            $subBuilder->where('deptcode', $row->dept_id);
            $subBuilder->where('pflag !=', 'T');
            $subQuery = $subBuilder->get();

            if ($subQuery->getNumRows() > 0) {
                return 1;
            }
        }

        return $numRows;
    }

    public function checkNotBefore($q_diary_no, $judges1)
    {
        $judges = explode(",", $judges1);
        $builder = $this->db->table('not_before n');
        $builder->select('GROUP_CONCAT(n.j1 ORDER BY j.judge_seniority) AS j1, n.notbef');
        $builder->join('judge j', 'j.jcode = n.j1');
        $builder->whereIn('n.diary_no', explode(',', $q_diary_no));
        $builder->groupBy('n.notbef');
        $builder->orderBy('IF(n.notbef = \'N\', 1, 2)', 'ASC');

        $query = $builder->get();
        foreach ($query->getResult() as $row) {
            $j1 = explode(",", $row->j1);
            $result = array_intersect($judges, $j1);

            if ($row->notbef == 'N' && !empty($result)) {
                return 1; // not to list
            } elseif ($row->notbef == 'B' && count(array_intersect($judges, $j1)) != count($j1)) {
                return 1; // not to list
            } elseif ($row->notbef == 'B' && count(array_intersect($judges, $j1)) == count($j1)) {
                return 0; // list before
            }
        }

        return 0;
    }

    public function getJudgeNamesInShort($chk_jud_id)
    {
        $builder = $this->db->table('judge');
        $builder->select('abbreviation');
        $builder->whereIn('jcode', explode(',', rtrim($chk_jud_id, ',')));
        $query = $builder->get();
        $names = [];

        foreach ($query->getResult() as $row) {
            $names[] = $row->abbreviation;
        }

        return implode(', ', $names);
    }

    public function moveFromHeardtToLastHeardt($dno)
    {
        $sql = "INSERT INTO last_heardt (diary_no, conn_key, next_dt, mainhead, subhead, clno, brd_slno, roster_id, judges, coram, board_type, usercode, ent_dt, module_id, mainhead_n, subhead_n, main_supp_flag, listorder, tentative_cl_dt, lastorder, listed_ia, sitting_judges) 
                SELECT j.* FROM (SELECT h.diary_no, h.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, h.board_type, h.usercode, h.ent_dt, h.module_id, h.mainhead_n, h.subhead_n, h.main_supp_flag, h.listorder, h.tentative_cl_dt, m.lastorder, h.listed_ia, h.sitting_judges
                FROM main m
                LEFT JOIN heardt h ON m.diary_no = h.diary_no     
                WHERE h.diary_no = ? AND h.diary_no > 0) j                
                LEFT JOIN last_heardt l ON j.diary_no = l.diary_no AND l.next_dt = j.next_dt AND l.listorder = j.listorder AND l.mainhead = j.mainhead 
                AND l.subhead = j.subhead AND l.judges = j.judges AND l.roster_id = j.roster_id AND l.clno = j.clno 
                AND l.main_supp_flag = j.main_supp_flag AND (l.bench_flag = '' OR l.bench_flag IS NULL) WHERE l.diary_no IS NULL";
        $this->db->query($sql, [$dno]);

        return $this->db->affectedRows() > 0 ? 1 : 0;
    }

    public function updateHeardtCL($q_diary_no, $q_next_dt, $q_clno, $q_brd_slno, $q_roster_id, $q_judges, $q_usercode, $q_module_id, $q_main_supp_flag, $mainhead, $cat1)
    {
        if ($mainhead == 'F') {
            $sql = "UPDATE (SELECT id FROM submaster WHERE display = 'Y' AND id IN (?) LIMIT 1) a,
                    heardt h SET h.subhead = a.id WHERE h.diary_no = ? AND h.mainhead = 'F' AND h.diary_no > 0";
            $this->db->query($sql, [$cat1, $q_diary_no]);
        }

        $sql = "UPDATE heardt SET next_dt = ?, clno = ?, brd_slno = ?, roster_id = ?, judges = ?, usercode = ?, ent_dt = NOW(), module_id = ?,
                main_supp_flag = ?, tentative_cl_dt = ? WHERE diary_no = ? AND diary_no > 0";
        $this->db->query($sql, [$q_next_dt, $q_clno, $q_brd_slno, $q_roster_id, $q_judges, $q_usercode, $q_module_id, $q_main_supp_flag, $q_next_dt, $q_diary_no]);

        return $this->db->affectedRows() > 0 ? 1 : 0;
    }


  




    

    
    
}

    
    
    