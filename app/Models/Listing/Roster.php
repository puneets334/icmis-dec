<?php

namespace App\Models\Listing;

use CodeIgniter\Model;

class Roster extends Model
{
    protected $table = 'master.roster'; // Use your actual table name
    protected $primaryKey = 'id';

    public function getBenchDetails($roster_id)
    {
        $builder = $this->db->table('master.roster r');

        $builder->select("
            r.id, 
            STRING_AGG(CAST(j.jcode AS TEXT), ', ' ORDER BY j.judge_seniority) AS jcd, 
            STRING_AGG(
                CASE 
                    WHEN j.jtype = 'J' THEN j.jname 
                    ELSE CONCAT(j.first_name, ' ', j.sur_name) 
                END, 
                ', ' 
                ORDER BY j.judge_seniority
            ) AS jnm, 
            r.courtno, 
            rb.bench_no, 
            mb.abbr, 
            mb.board_type_mb, 
            r.tot_cases, 
            r.frm_time
        ");

        $builder->join('master.roster_bench rb', 'rb.id = r.bench_id', 'left');
        $builder->join('master.master_bench mb', 'mb.id = rb.bench_id', 'left');
        $builder->join('master.roster_judge rj', 'rj.roster_id = r.id', 'left');
        $builder->join('master.judge j', 'j.jcode = rj.judge_id', 'left');

        $builder->where('j.is_retired !=', 'Y');
        $builder->where('j.display', 'Y');
        $builder->where('rj.display', 'Y');
        $builder->where('rb.display', 'Y');
        $builder->where('mb.display', 'Y');
        $builder->where('r.display', 'Y');
        $builder->where('r.id', $roster_id);

        // Include all non-aggregated columns in GROUP BY
        $builder->groupBy('r.id, r.courtno, rb.bench_no, mb.abbr, mb.board_type_mb, r.tot_cases, r.frm_time');

        $query = $builder->get();
        $result = $query->getRowArray();

        // Debugging: Output the last query
        //echo $this->db->getLastQuery(); die;

        return $result;
    }

    public function getAllocationJudge($p1, $cldt, $jud_count, $board_type)
    {
        $cldt = date('Y-m-d', strtotime($cldt));
        
        $m_f = "";
        $from_to_dt = "";
        
        if ($p1 == "M") {
            $m_f = "AND r.m_f = '1'";
            $from_to_dt = ($board_type == 'R') ? "AND r.to_date IS NULL" : "AND r.from_date = '$cldt'";
        } elseif ($p1 == "L") {
            $m_f = "AND r.m_f = '3'";
            $from_to_dt = "AND r.from_date = '$cldt'";
        } elseif ($p1 == "S") {
            $m_f = "AND r.m_f = '4'";
            $from_to_dt = "AND r.from_date = '$cldt'";
        } else {
            $m_f = "AND r.m_f = '2'";
            $from_to_dt = "AND r.from_date = '$cldt'";
        }

         $query = $this->db->query("
         SELECT 
             r.id, 
             STRING_AGG(j.jcode::text, ',' ORDER BY j.judge_seniority) as jcd, 
             STRING_AGG(CONCAT(j.first_name, ' ', j.sur_name), ', ' ORDER BY j.judge_seniority) as jnm, 
             rb.bench_no, 
             mb.abbr, 
             r.tot_cases, 
             r.courtno, 
             mb.board_type_mb 
         FROM master.roster r
         LEFT JOIN master.roster_bench rb ON rb.id = r.bench_id 
         LEFT JOIN master.master_bench mb ON mb.id = rb.bench_id                
         LEFT JOIN master.roster_judge rj ON rj.roster_id = r.id 
         LEFT JOIN master.judge j ON j.jcode = rj.judge_id
        WHERE
        mb.display = 'Y'
        AND mb.board_type_mb = '$board_type'
        AND j.is_retired != 'Y'
        AND j.display = 'Y'
        AND rj.display = 'Y'
        AND rb.display = 'Y'
        AND r.display = 'Y'
        $m_f 
        $from_to_dt
    GROUP BY
        r.id, rb.bench_no, mb.abbr, r.tot_cases, r.courtno, mb.board_type_mb
    ORDER BY
    r.courtno, r.id");

     return $query->getResultArray();

        // // Main SQL query using STRING_AGG for PostgreSQL
        // $query = $this->db->query("
        //     SELECT 
        //         r.id, 
        //         STRING_AGG(j.jcode::text, ',' ORDER BY j.judge_seniority) as jcd, 
        //         STRING_AGG(CONCAT(j.first_name, ' ', j.sur_name), ', ' ORDER BY j.judge_seniority) as jnm, 
        //         rb.bench_no, 
        //         mb.abbr, 
        //         r.tot_cases, 
        //         r.courtno, 
        //         mb.board_type_mb 
        //     FROM master.roster r
        //     LEFT JOIN master.roster_bench rb ON rb.id = r.bench_id 
        //     LEFT JOIN master.master_bench mb ON mb.id = rb.bench_id                
        //     LEFT JOIN master.roster_judge rj ON rj.roster_id = r.id 
        //     LEFT JOIN master.judge j ON j.jcode = rj.judge_id
        //     WHERE mb.display = 'Y' 
        //         AND mb.board_type_mb = ?
        //         AND j.is_retired != 'Y' 
        //         AND j.display = 'Y' 
        //         AND rj.display = 'Y' 
        //         AND rb.display = 'Y' 
        //         AND r.display = 'Y' 
        //         $m_f 
        //         $from_to_dt 
        //     GROUP BY r.id, rb.bench_no, mb.abbr, r.tot_cases, r.courtno, mb.board_type_mb 
        //     ORDER BY r.courtno, r.id, STRING_AGG(j.judge_seniority::text, ',' ORDER BY j.judge_seniority)", [$board_type]);

        // return $query->getResultArray();
    }

    public function getCasesCount($cldt, $jcd, $board_type, $p1)
    {
        $sql1 = "SELECT 
                    SUM(CASE WHEN m.case_grp = 'C' THEN 1 ELSE 0 END) AS civil, 
                    SUM(CASE WHEN m.case_grp = 'R' THEN 1 ELSE 0 END) AS criminal 
                FROM heardt h 
                LEFT JOIN main m ON h.diary_no = m.diary_no 
                WHERE h.next_dt = '$cldt' 
                AND h.judges = '$jcd' 
                AND h.board_type = '$board_type' 
                AND h.mainhead = '$p1' 
                AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2) 
                AND (h.diary_no = h.conn_key OR h.conn_key='0') 
                GROUP BY h.judges";

        return $this->db->query($sql1)->getRowArray();
    }


    public function getCivilCriminalCases($cldt, $jcd, $board_type, $p1)
    {
        // Query to get civil and criminal cases
        $query = $this->db->query("
            SELECT 
                SUM(CASE WHEN m.case_grp = 'C' THEN 1 ELSE 0 END) as civil, 
                SUM(CASE WHEN m.case_grp = 'R' THEN 1 ELSE 0 END) as criminal
            FROM heardt h 
            LEFT JOIN main m ON h.diary_no = m.diary_no 
            WHERE h.next_dt = ?
                AND h.judges = ?
                AND h.board_type = ?
                AND h.mainhead = ?
                AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2) 
                AND (h.diary_no = h.conn_key OR h.conn_key='0') 
            GROUP BY h.judges", [$cldt, $jcd, $board_type, $p1]);

        return $query->getRowArray();
    }
    

    public function getJudgeAllocationold($p1, $cldt, $board_type)
    {
        $cldt = date('Y-m-d', strtotime($cldt));
        $fromToDate = "r.from_date = '$cldt'";
        $m_f = "r.m_f = '2'";

        if ($p1 === "M") {
            $m_f = "r.m_f = '1'";
            if ($board_type === 'R') {
                $fromToDate = "r.to_date = '0000-00-00'";
            }
        } elseif ($p1 === "L") {
            $m_f = "r.m_f = '3'";
        } elseif ($p1 === "S") {
            $m_f = "r.m_f = '4'";
        }

        // Using Query Builder
        $builder = $this->db->table('master.roster r');


        $builder->join('master.roster_bench rb', 'rb.id = r.bench_id', 'left');
        $builder->join('master.master_bench mb', 'mb.id = rb.bench_id', 'left');
        $builder->join('master.roster_judge rj', 'rj.roster_id = r.id', 'left');
        $builder->join('master.judge j', 'j.jcode = rj.judge_id', 'left');
        $builder->select('r.id');
        $builder->select("string_agg(j.jcode::text, ',' ORDER BY j.judge_seniority) as jcd");
        $builder->select("string_agg(CONCAT(j.first_name, ' ', j.sur_name), ',' ORDER BY j.judge_seniority) as jnm");
        $builder->select('rb.bench_no');
        $builder->select('mb.abbr');
        $builder->select('r.tot_cases');
        $builder->select('r.courtno');
        $builder->select('mb.board_type_mb');

        $builder->where('j.is_retired !=', 'Y');
        $builder->where('mb.board_type_mb', $board_type);
        $builder->where('j.display', 'Y');
        $builder->where('rj.display', 'Y');
        $builder->where('rb.display', 'Y');
        $builder->where('mb.display', 'Y');
        $builder->where('r.display', 'Y');
        $builder->where($m_f);
        $builder->where($fromToDate);

        // $builder->groupBy('r.id', 'rb.bench_no', 'mb.abbr', 'r.tot_cases', 'r.courtno', 'mb.board_type_mb');
        $builder->orderBy('r.courtno');
        //$builder->orderBy('r.id');
        $builder->orderBy('j.judge_seniority');

        // $sql = $builder->getCompiledSelect();
        // echo '<pre>' . htmlspecialchars($sql) . '</pre>';

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getJudgeAllocation($p1, $cldt, $board_type)
    {
        $cldt = date('Y-m-d', strtotime($cldt));

        if ($p1 == "M") {
            $m_f = "r.m_f = '1'";
            if ($board_type == 'R') {
                $from_to_dt = "r.to_date = '0000-00-00'";
            } else {
                $from_to_dt = "r.from_date = '$cldt'";
            }
        } else if ($p1 == "L") {
            $m_f = "r.m_f = '3'";
            $from_to_dt = "r.from_date = '$cldt'";
        } else if ($p1 == "S") {
            $m_f = "r.m_f = '4'";
            $from_to_dt = "r.from_date = '$cldt'";
        } else {
            $m_f = "r.m_f = '2'";
            $from_to_dt = "r.from_date = '$cldt'";
        }

        $sql = "SELECT r.id, 
                   string_agg(j.jcode::text, ',' ORDER BY j.judge_seniority) AS jcd, 
                   string_agg(CONCAT(j.first_name, ' ', j.sur_name), ',' ORDER BY j.judge_seniority) AS jnm, 
                   rb.bench_no, 
                   mb.abbr, 
                   r.tot_cases, 
                   r.courtno, 
                   mb.board_type_mb 
            FROM master.roster r 
            LEFT JOIN master.roster_bench rb ON rb.id = r.bench_id 
            LEFT JOIN master.master_bench mb ON mb.id = rb.bench_id 
            LEFT JOIN master.roster_judge rj ON rj.roster_id = r.id 
            LEFT JOIN master.judge j ON j.jcode = rj.judge_id 
            WHERE j.is_retired != 'Y' 
              AND mb.board_type_mb = '$board_type' 
              AND j.display = 'Y' 
              AND rj.display = 'Y' 
              AND rb.display = 'Y' 
              AND mb.display = 'Y' 
              AND r.display = 'Y' 
              AND $m_f 
              AND $from_to_dt 
            GROUP BY r.id, rb.bench_no, mb.abbr, r.tot_cases, r.courtno, mb.board_type_mb 
            ORDER BY r.courtno, r.id";

        $query = $this->db->query($sql);

        $result = $query->getResultArray();

        return $result;
    }




    public function getListingDetails($cldt, $p1, $jcd)
{
    $builder = $this->db->table('heardt h');
    $builder->select('COUNT(*) as ttt');
    $builder->select("SUM(CASE WHEN l.code IN (4, 5, 7) THEN 1 ELSE 0 END) as fd");
    $builder->select("SUM(CASE WHEN l.code NOT IN (4, 5, 7) THEN 1 ELSE 0 END) as ors");

    $builder->join('main m', 'h.diary_no = m.diary_no', 'inner');
    $builder->join('master.listing_purpose l', 'l.code = h.listorder', 'inner');

    $builder->where('l.display', 'Y');
    $builder->where('h.next_dt', $cldt);
    $builder->where('h.judges', $jcd);
    $builder->where('h.mainhead', $p1);

    $builder->groupStart();
    $builder->where('h.main_supp_flag', 1);
    $builder->orWhere('h.main_supp_flag', 2);
    $builder->groupEnd();

    $builder->groupStart();
    $builder->where('h.diary_no = h.conn_key');
    $builder->orWhere('h.conn_key', 0);
    $builder->groupEnd();

    $query = $builder->get();
    return $query->getRowArray();
}

    
    public function getJudgeRosterForTrans($p1, $cldt, $jud_count, $board_type)
    {
        $cldt = date('Y-m-d', strtotime($cldt));
        if ($p1 == "M") {
            $m_f = 1;
            $from_to_dt = ($board_type == 'R') ? '0000-00-00' : $cldt;
        } elseif ($p1 == "L") {
            $m_f = 3;
            $from_to_dt = $cldt;
        } elseif ($p1 == "S") {
            $m_f = 4;
            $from_to_dt = $cldt;
        } else {
            $m_f = 2;
            $from_to_dt = $cldt;
        }
    
        $builder = $this->db->table('master.roster r');
        $builder->select('r.id, 
                          GROUP_CONCAT(j.jcode ORDER BY j.judge_seniority) AS jcd, 
                          GROUP_CONCAT(CONCAT(j.first_name, \' \', j.sur_name) ORDER BY j.judge_seniority) AS jnm, 
                          rb.bench_no, 
                          mb.abbr, 
                          r.tot_cases, 
                          r.courtno, 
                          mb.board_type_mb');
        $builder->join('master.roster_bench rb', 'rb.id = r.bench_id', 'left');
        $builder->join('master.master_bench mb', 'mb.id = rb.bench_id', 'left');
        $builder->join('master.roster_judge rj', 'rj.roster_id = r.id', 'left');
        $builder->join('master.judge j', 'j.jcode = rj.judge_id', 'left');
        
        // Filter conditions
        $builder->where('j.is_retired !=', 'Y');
        $builder->where('j.display', 'Y');
        $builder->where('rj.display', 'Y');
        $builder->where('rb.display', 'Y');
        $builder->where('mb.display', 'Y');
        $builder->where('r.display', 'Y');
        $builder->where('r.m_f', $m_f);
        $builder->groupStart();
        if ($board_type == 'C') {
            $builder->groupStart()
                    ->where('mb.board_type_mb', 'C')
                    ->orWhere('mb.board_type_mb', 'CC')
                    ->groupEnd();
        } else {
            $builder->where('mb.board_type_mb', $board_type);
        }
        $builder->groupEnd();
        $builder->groupStart();
        if ($board_type == 'R') {
            $builder->where('r.to_date', '0000-00-00');
        } else {
            $builder->where('r.from_date', $from_to_dt);
        }
        $builder->groupEnd();
    
        $builder->groupBy('r.id');
        $builder->orderBy('r.courtno');
        $builder->orderBy('r.id');
        $builder->orderBy('j.judge_seniority');
    
        // Execute the query
        $query = $builder->get();
        return $query->getResultArray();
    }
    
    public function getCivilCriminalCounts($cldt, $p1, $roster_id)
    {
        $builder = $this->db->table('heardt h');
        $builder->select('SUM(CASE WHEN m.case_grp = "C" THEN 1 ELSE 0 END) AS civil, 
                          SUM(CASE WHEN m.case_grp = "R" THEN 1 ELSE 0 END) AS criminal');
        $builder->join('main m', 'h.diary_no = m.diary_no', 'left');
        $builder->where('h.next_dt', $cldt);
        $builder->where('h.roster_id', $roster_id);
        $builder->where('h.mainhead', $p1);
        $builder->whereIn('h.main_supp_flag', [1, 2]);
        $builder->groupStart()
                ->where('h.diary_no = h.conn_key')
                ->orWhere('h.conn_key', '')
                ->orWhere('h.conn_key IS NULL')
                ->groupEnd();
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function getJudgeRoster($p1, $cldt, $board_type)
    {
        $m_f = '';
        $from_to_dt = '';

        $cldt = date('Y-m-d', strtotime($cldt));

        switch ($p1) {
            case "M":
                $m_f = "AND r.m_f = '1'";
                $from_to_dt = ($board_type == 'R') ? "AND r.to_date = '0000-00-00'" : "AND r.from_date = '$cldt'";
                break;
            case "L":
                $m_f = "AND r.m_f = '3'";
                $from_to_dt = "AND r.from_date = '$cldt'";
                break;
            case "S":
                $m_f = "AND r.m_f = '4'";
                $from_to_dt = "AND r.from_date = '$cldt'";
                break;
            default:
                $m_f = "AND r.m_f = '2'";
                $from_to_dt = "AND r.from_date = '$cldt'";
                break;
        }

        $board_type_cc = ($board_type == 'C') ? "AND (mb.board_type_mb = 'C' OR mb.board_type_mb = 'CC')" : "AND mb.board_type_mb = '$board_type'";

        $sql = "SELECT r.id, 
                       GROUP_CONCAT(j.jcode ORDER BY j.judge_seniority) AS jcd, 
                       GROUP_CONCAT(CONCAT(j.first_name, ' ', j.sur_name) ORDER BY j.judge_seniority) AS jnm, 
                       rb.bench_no, 
                       mb.abbr, 
                       r.tot_cases, 
                       r.courtno, 
                       mb.board_type_mb 
                FROM master.roster r 
                LEFT JOIN master.roster_bench rb ON rb.id = r.bench_id 
                LEFT JOIN master.master_bench mb ON mb.id = rb.bench_id 
                LEFT JOIN master.roster_judge rj ON rj.roster_id = r.id 
                LEFT JOIN master.judge j ON j.jcode = rj.judge_id 
                WHERE j.is_retired != 'Y' 
                $board_type_cc 
                AND j.display = 'Y' 
                AND rj.display = 'Y' 
                AND rb.display = 'Y' 
                AND mb.display = 'Y' 
                AND r.display = 'Y' 
                $m_f 
                $from_to_dt 
                GROUP BY r.id 
                ORDER BY r.courtno, r.id, j.judge_seniority";

        return $this->db->query($sql)->getResultArray();
    }

    public function getCaseCounts($cldt, $judgeId, $mainHead)
    {
        $sql1 = "SELECT SUM(CASE WHEN m.case_grp = 'C' THEN 1 ELSE 0 END) AS civil, 
                         SUM(CASE WHEN m.case_grp = 'R' THEN 1 ELSE 0 END) AS criminal 
                  FROM heardt h 
                  LEFT JOIN main m ON h.diary_no = m.diary_no 
                  WHERE h.next_dt = '$cldt' 
                  AND h.roster_id = '$judgeId' 
                  AND h.mainhead = '$mainHead' 
                  AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2) 
                  AND (h.diary_no = h.conn_key OR h.conn_key = '' OR h.conn_key IS NULL) 
                  GROUP BY h.judges";

        return $this->db->query($sql1)->getRowArray();
    }


    public function isListPrinted($details)
    {
        $nextDt = $details['next_dt'] ?? '0000-00-00';

        $query = $this->db->table('cl_printed')
            ->select('id')
            ->where([
                'next_dt' => $nextDt,
                //'next_dt >= current_date()',
                'm_f' => $details['mainhead'],
                'roster_id' => $details['roster_id'],
                'part' => $details['clno'],
                'main_supp' => $details['main_supp_flag'],
                'display' => 'Y'
            ]);

        return $query->get()->getNumRows() > 0;
    }
    public function getMainSupp()
    {
        $builder = $this->db->table('master.master_main_supp')
            ->select('id, descrip')
            ->where('display', 'Y');
        
        return $builder->get()->getResultArray();
    }
    //rb.bench_no
    //r.m_f
    public function getJudges($details)
    {
        $builder = $this->db->table('master.roster r');
        $builder->select("r.m_f, r.id, 
                          STRING_AGG(j.jcode::text, ', ' ORDER BY j.judge_seniority) AS jcd, 
                          STRING_AGG(CONCAT(j.first_name, ' ', j.sur_name), ', ' ORDER BY j.judge_seniority) AS jnm, 
                          rb.bench_no, mb.abbr, r.tot_cases, mb.board_type_mb, 
                          j.judge_seniority"); 
        $builder->join('master.roster_bench rb', 'rb.id = r.bench_id', 'left');
        $builder->join('master.master_bench mb', 'mb.id = rb.bench_id', 'left');
        $builder->join('master.roster_judge rj', 'rj.roster_id = r.id', 'left');
        $builder->join('master.judge j', 'j.jcode = rj.judge_id', 'left');
        $builder->where('j.is_retired !=', 'Y');
        $builder->where('j.display', 'Y');
        $builder->where('rj.display', 'Y');
        $builder->where('rb.display', 'Y');
        $builder->where('mb.display', 'Y');
        $builder->where('r.display', 'Y');

        if (isset($details['mainhead'])) {
            switch ($details['mainhead']) {
                case 'M':
                    $builder->where('m_f', '1');
                    break;
                case 'F':
                    $builder->where('m_f', '2');
                    break;
                case 'L':
                    $builder->where('m_f', '5');
                    break;
                case 'S':
                    $builder->where('m_f', '7');
                    break;
            }
        }
    
        if (!empty($details['next_dt'])) {
            $builder->where('r.from_date', $details['next_dt']);
        }
        if (isset($details['board_type'])) {
            $board_type = $details['board_type'] == 'C' 
                ? "(mb.board_type_mb='C' OR mb.board_type_mb='CC')" 
                : "mb.board_type_mb='{$details['board_type']}'";
    
            $builder->where($board_type, null, false);
        }
        $builder->groupBy('r.m_f, r.id, rb.bench_no, mb.abbr, r.tot_cases, mb.board_type_mb, j.judge_seniority'); // Add j.judge_seniority to group by
        $builder->orderBy('r.id'); 
        $builder->orderBy('j.judge_seniority');
    
        // Execute the query and return results
        $query = $builder->get();
        return $query->getResultArray();
    }
    
    
    ////Alok-Sharma Code
    
    public function getCategories($hd_roster_id){
        $builder = $this->db->table('category_allottment a');
        $builder->select('b.b_n, b.stage_code, b.stage_nature, b.priority, a.case_type, a.submaster_id, b.m_f, b.bench_id, a.id')
          ->join('master.roster b', 'a.ros_id = b.id AND b.display = "Y"')
          ->where('a.display', 'Y')
          ->where('ros_id', $hd_roster_id);

        $query = $builder->get();
        return $result = 	$query->getResultArray();
      }
    
      public function getSubHeading($stage_code){
        $builder = $this->db->table('master.subheading');    
        $builder->select('stagename')
          ->where('stagecode', $stage_code)
          ->where('display', 'Y');
        $query = $builder->get();
        return $result = 	$query->getResultArray();
      }
    
      public function getCasetypeKey($casetype){
        $builder = $this->db->table('master.casetype');    
        $builder->select('skey')
          ->where('casecode', $casetype)
          ->where('display', 'Y');
        $query = $builder->get();
        return $result = 	$query->getResultArray();
      }
    
      public function getSubMaster($ex_submaster_id){
          $builder = $this->db->table('master.submaster');    
          $builder->select('sub_name1, sub_name2, sub_name3, sub_name4, category_sc_old')
            ->where('display', 'Y')
            ->where('id', $ex_submaster_id);
          $query = $builder->get();
          return $result = 	$query->getResultArray();
      }
    
      public function getCatSubMaster($catId){
        if($catId != ''){          
          $subQuery = $this->db->table('master.submaster')
            ->select('subcode1, subcode2')
            ->where('id', $catId)
            ->where('display', 'Y')
            ->get()
            ->getRow();

          if ($subQuery) {
            $subcode1 = $subQuery->subcode1;
            $subcode2 = $subQuery->subcode2;

            // Build the main query
            $builder = $this->db->table('master.submaster');
            $builder->select('*')
                ->where('subcode1', $subcode1)
                ->where('subcode2', $subcode2)
                ->where('subcode2 !=', '0')
                ->where('subcode3 !=', '0')
                ->where('subcode4', '0')
                ->where('display', 'Y')
                ->orderBy('subcode1', 'ASC')
                ->orderBy('subcode2', 'ASC')
                ->orderBy('subcode3', 'ASC')
                ->orderBy('subcode4', 'ASC');

            // Execute the main query
            $query = $builder->get();
            return $result = $query->getResultArray();
          }
          else{
            return [];
          }
        }
        else{
          return [];
        }
      }
    
      public function getSubCatSubMaster($catId){
        if($catId != ''){        
          $subQuery = $this->db->table('master.submaster')
            ->select('subcode1, subcode2, subcode3')
            ->where('id', $catId)
            ->where('display', 'Y')
            ->get()
            ->getRow();
        
          if ($subQuery) {
            $subcode1 = $subQuery->subcode1;
            $subcode2 = $subQuery->subcode2;
            $subcode3 = $subQuery->subcode3;

            $builder = $this->db->table('master.submaster');
            $builder->select('*')
                ->where('subcode1', $subcode1)
                ->where('subcode2', $subcode2)
                ->where('subcode3', $subcode3)
                ->where('subcode4 !=', '0')
                ->where('display', 'Y')
                ->orderBy('subcode1', 'ASC')
                ->orderBy('subcode2', 'ASC')
                ->orderBy('subcode3', 'ASC')
                ->orderBy('subcode4', 'ASC');
        
            // Execute the main query
            $query = $builder->get();
            return $result = $query->getResult();
          } else {
            return [];
          }
        }
        else{
          return [];
        }
        
      }
    
      public function getRosterDetails($btnroster){
        $builder = $this->db->table('master.roster');
        $builder->select('bench_id, m_f, from_date, session, frm_time, tot_cases, courtno')
                ->where('id', $btnroster)
                ->where('display', 'Y');
        $query = $builder->get();
        return $result = 	$query->getRowArray();
      }
    
      public function countRosterDetail($bench_names, $res_sql_search)
      {
        //dump($bench_names);die;
        $builder = $this->db->table('master.roster');
        $builder->selectCount('*', 'count')
          ->where('bench_id', (int)$bench_names)
          ->where('m_f', $res_sql_search['m_f'])
          ->where('display', 'Y')
          ->groupStart()
              ->where('to_date IS NULL')
              ->where('from_date', $res_sql_search['from_date'])
          ->orGroupStart()
              ->where("'{$res_sql_search['from_date']}' BETWEEN from_date AND to_date", null, false)
          ->groupEnd()
          ->groupEnd();

        // echo $sql = $builder->getCompiledSelect();
        // echo '<pre>' . htmlspecialchars($sql) . '</pre>';
        // die;
        // Execute the query and get the result
        $query = $builder->get();        
        // pr($query->getResultArray());
        return $query->getRowArray();
      }
    
      public function countRosterDetails($rostData, $date){
        $builder = $this->db->table('master.roster');
        $builder->selectCount('*', 'count')
          ->where('bench_id', $rostData['bench_id'])
          ->where('m_f', $rostData['m_f'])
          ->where('display', 'Y')
          ->where('from_date >', $rostData['from_date'])
          ->where('from_date <=', $date);
        $query = $builder->get();
        return $query->getResultArray();
      }
    
    
      public function rosterDetails($id){
        $builder = $this->db->table('master.roster a');
        $builder->select('b.bench_id, a.m_f')
                ->join('master.roster_bench b', 'a.bench_id = b.id', 'inner')
                ->where('a.id', $id)
                ->where('a.display', 'Y');

        // Execute the query and get results
        $query = $builder->get();	
        return $result = 	$query->getResultArray();
      }
    
    
      public function getCountDiary($btnroster){
        $builder = $this->db->table('heardt');
        $builder->selectCount('diary_no', 'count')
                ->where('roster_id', $btnroster);
        $query = $builder->get();
        return $result = 	$query->getResultArray();
      }
    
    
      public function getStageDetails($rid){
        $stgQuery = "SELECT 
                    STRING_AGG(stage_code::text, ',' ORDER BY ca.priority) AS stage_code,
                    STRING_AGG(ca.priority::text, ',' ORDER BY ca.priority) AS priority,
                    STRING_AGG(stage_nature::text, ',' ORDER BY ca.priority) AS stage_nature,
                    STRING_AGG(case_type::text, ',' ORDER BY ca.priority) AS case_type,
                    STRING_AGG(submaster_id::text, ',' ORDER BY ca.priority) AS submaster_id,
                    STRING_AGG(b_n::text, ',' ORDER BY ca.priority) AS b_n
                    FROM category_allottment ca
                    WHERE ca.display = 'Y' 
                    AND ros_id = '$rid'
                    GROUP BY ros_id";
        $query = $this->db->query($stgQuery);
        return $query->getRowArray();
      }
    
      public function gatCaseType(){
        $builder = $this->db->table('master.casetype');
        $builder->select('nature')
                ->distinct()
                ->where('display', 'Y')
                ->orderBy('nature');

        // Execute the query and get results
        $query = $builder->get();
        return $result = 	$query->getResultArray();
      }
    
      public function gatSubMaster(){
        $builder = $this->db->table('master.submaster');
        $builder->select('id, sub_name1, flag, category_sc_old')
                ->where('display', 'Y')
                ->where('subcode2', '0')
                ->where('subcode3', '0')
                ->where('subcode4', '0')
                ->orderBy('subcode1')
                ->orderBy('subcode2')
                ->orderBy('subcode3')
                ->orderBy('subcode4');
        $query = $builder->get();
        return $result = 	$query->getResultArray();
      }
    
      public function getRosterBench($benchId){
        $builder = $this->db->table('master.roster_bench');
        $builder->select('id, bench_id')
                ->where('id', $benchId)
                ->where('display', 'Y');

        // Execute the query and get results
        $query = $builder->get();
        return $result = 	$query->getResultArray();
      }
    
      public function newRosterBench($benchId, $btnid){
        $builder = $this->db->table('master.roster_bench');
        $builder->select('id, bench_id, bench_no')
                ->where('id !=', $benchId)
                ->where('bench_id', $btnid)
                ->where('display', 'Y')
                ->orderBy('priority');
        $query = $builder->get();
        return $result = 	$query->getResultArray();
      }
    
      public function getMasterBench($benchId = '')
      {
        $builder = $this->db->table('master.master_bench');
        $builder->select('id, bench_name')
                ->where('display', 'Y');
        if($benchId != ''){
          $builder->where('id', $benchId);
        }
        $query = $builder->get();
        return $result = 	$query->getResultArray();
      }
    
      public function getJudgeDetils($btnroster){
        $builder = $this->db->table('master.roster_judge');
        $builder->select('judge_id')
                ->where('roster_id', $btnroster);
        $query = $builder->get();
        return $result = 	$query->getResultArray();
      }
    
      public function getJudgeCourt($courtno1){
        $builder = $this->db->table('master.judge');
        $builder->select('jcourt')
                ->where('jcode', $courtno1)
                ->where('display', 'Y')
                ->groupStart()
                    ->where('to_dt IS NULL')
                    ->orWhere('to_dt >=', date('Y-m-d'))
                ->groupEnd();

        // Execute the query and get results
        $query = $builder->get();
        return $result = 	$query->getRowArray();
      }
    
      public function getRosterData($bench_id, $m_f, $from_date){
        $builder = $this->db->table('master.roster');
        $builder->select('from_date, to_date')
                ->where('bench_id', $bench_id)
                ->where('m_f', $m_f)
                ->where('display', 'Y')
                ->groupStart()
                    ->groupStart()
                        ->where('to_date IS NULL')
                        ->where('from_date', $from_date)
                    ->groupEnd()
                    ->orGroupStart()
                        ->where("'$from_date' BETWEEN from_date AND to_date", null, false)
                    ->groupEnd()
                ->groupEnd();

        // Execute the query and get results
        $query = $builder->get();
        return $result = 	$query->getResultArray();
      }
    
      public function getRoterMaxId(){
        $builder = $this->db->table('master.roster');
        $builder->selectMax('id', 'id');

        // Execute the query and get the result
        $query = $builder->get();
        return $result = 	$query->getRowArray();
      }

      public function getCategoriesAlot($ros_id){
        $builder = $this->db->table('category_allottment');
        $builder->select('stage_code, priority, case_type, submaster_id, b_n, stage_nature')
                ->where('ros_id', $ros_id)
                ->where('display', 'Y');

        // Execute the query and get results
        $query = $builder->get();
        return $result = $query->getResultArray();
      }
      
      public function getCategoriesAlotCount($ros_id){
        $builder = $this->db->table('category_allottment');
        $builder->selectCount('*', 'count')
                ->where('ros_id', $ros_id)
                ->where('display', 'Y');

        // Execute the query and get the result
        $query = $builder->get();

        return $result = $query->getResultArray();
    }
    
    public function getJcodeNewOne(){
        $sq_y = "SELECT jcode,
                    LTRIM(
                        REPLACE(
                            REPLACE(
                                REPLACE(
                                    REPLACE(
                                        REPLACE(
                                            REPLACE(
                                                REPLACE(
                                                    REPLACE(
                                                        REPLACE(
                                                            jname,
                                                            '\\', ''
                                                        ),
                                                        'HON''BLE SHRI JUSTICE', ''
                                                    ),
                                                    'HON''BLE MR. JUSTICE', ''
                                                ),
                                                'Hon''ble Shri Justice', ''
                                            ),
                                            'HON''BLE SMT. JUSTICE', ''
                                        ),
                                        'HON''BLE MRS.', ''
                                    ),
                                    'HON''BLE SHRI', ''
                                ),
                                'HON''BLE SHRI', ''
                            ),
                            'HON''BLE ', ''
                        )
                    ) AS jname
                FROM master.judge
                WHERE display = 'Y'
                  AND (
                      to_dt IS NULL OR to_dt >= CURRENT_DATE
                  )
                  AND is_retired = 'N'
                ORDER BY judge_seniority";
    
        $query = $this->db->query($sq_y);	
        return	$query->getResultArray();
    }

    public function getJcodeOnes(){
        $sq_y = "SELECT 
              jcode,
              LTRIM(
                REPLACE(
                  REPLACE(
                    REPLACE(
                      REPLACE(
                        REPLACE(
                          REPLACE(
                            REPLACE(
                              REPLACE(
                                REPLACE(jname, E'\\\\', ''),
                                E'HON\\\\\'BLE SHRI JUSTICE', ''
                              ),
                              E'HON\\\\\'BLE MR. JUSTICE', ''
                            ),
                            E'Hon\\\\\'ble Shri Justice', ''
                          ),
                          E'HON\\\\\'BLE SMT. JUSTICE', ''
                        ),
                        E'HON\\\\\'BLE MRS.', ''
                      ),
                      E'HON\\\\\'BLE SHRI', ''
                    ),
                    E'HON\\\\\'BLE SHRI', ''
                  ),
                  E'HON\\\\\'BLE', ''
                )
              ) AS jname
            FROM master.judge
            WHERE display = 'Y' 
              AND is_retired = 'N'
              AND (
                to_dt IS NULL OR to_dt >= CURRENT_DATE
              )
              OR (
                jcode >= 9001 
                AND jcode != 9010 
                AND jcode != 9011 
                AND jcode != 9012 
                AND jcode != 9013
              )
            ORDER BY judge_seniority";
        $query = $this->db->query($sq_y);	
        return	$query->getResultArray();
    }

    public function getJcodeOne()
        {
            $sq_y = "SELECT 
              jcode,
              LTRIM(
            --    REPLACE(
                  REPLACE(
                    REPLACE(
                      REPLACE(
                        REPLACE(
                          REPLACE(
                            REPLACE(
                              REPLACE(
                                REPLACE(
                                  CASE 
                                    WHEN jtype = 'R' 
                                    THEN CONCAT(first_name, ' ', sur_name, ' ', jname) 
                                    ELSE jname 
                                  END,
                                  '\\', ''
                                ),
                                'HON''BLE SHRI JUSTICE', ''
                              ),
                              'HON''BLE MR. JUSTICE', ''
                            ),
                            'Hon''ble Shri Justice', ''
                          ),
                          'HON''BLE SMT. JUSTICE', ''
                        ),
                        'HON''BLE MRS.', ''
                      ),
                      'HON''BLE SHRI', ''
                    ),
                    'HON''BLE ', ''
                  )
                -- )
              ) AS jname
            FROM 
              master.judge
            WHERE 
              display = 'Y' 
              AND (to_dt IS NULL OR to_dt >= CURRENT_DATE)
              AND is_retired = 'N'
            ORDER BY 
              judge_seniority";
        $query = $this->db->query($sq_y);	
        return $result = 	$query->getResultArray();
    }
    
}
