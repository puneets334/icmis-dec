<?php

namespace App\Models\Listing;

use CodeIgniter\Model;
use InvalidArgumentException;


class Heardt extends Model
{
    protected $table = 'heardt';


    public function getListingDates()
    {
        return $this->db->table($this->table)
            ->select('next_dt') // Correct usage of DISTINCT
            ->where('mainhead', 'M')
            ->where('next_dt >= CURRENT_DATE')
            ->groupStart()
                ->where('main_supp_flag', '1')
                ->orWhere('main_supp_flag', '2')
            ->groupEnd()
            ->orderBy('next_dt')
            ->get()
            ->getResultArray();
    }

    public function getListDates()
    {
        $builder = $this->builder();

        $builder->select('next_dt')
            ->where('mainhead', 'F')
            ->where('next_dt >=', date('Y-m-d', strtotime('-7 days')))
            ->groupStart()
                ->where('main_supp_flag', '1')
                ->orWhere('main_supp_flag', '2')
            ->groupEnd()
            ->groupBy('next_dt')
            ->orderBy('next_dt');


        return $builder->get()->getResultArray();
    }



    public function getListingDatesM()
    {
        return $this->select('next_dt')
            ->where('mainhead', 'M')
            ->where('next_dt >=', date('Y-m-d'))
            ->whereIn('main_supp_flag', [1, 2])
            ->groupBy('next_dt')
            ->findAll();
    }


    public function getListingDatesMV1()
    {
        /*$builder = $this->db->table('heardt');
        $builder->select('next_dt')
            ->where('mainhead', 'M')
            ->where('next_dt IS NOT NULL', null, false) // Exclude blank values
            ->where('next_dt >=', "CURRENT_DATE - INTERVAL '7 days'", false) // PostgreSQL date subtraction
            ->whereIn('main_supp_flag', [1, 2])
            ->groupBy('next_dt');*/

        $builder = $this->db->table('heardt c');
        $builder->select('c.next_dt')
                ->where('c.mainhead', 'M')
                ->where('c.next_dt >=', date('Y-m-d'))
                ->groupStart()
                ->where('c.main_supp_flag', '1')
                ->orWhere('c.main_supp_flag', '2')
                ->groupEnd()
                ->groupBy('next_dt');
        return $builder->get()->getResult();
    }


    public function getListingDatesHybrid()
    {
        return $this->select('next_dt')
            ->where('next_dt >=', date('Y-m-d')) // Adjust the date format to match your DB
            ->groupBy('next_dt')
            ->orderBy('next_dt', 'ASC')
            ->findAll();
    }

    public function getBenchJudges1($mainhead, $cldt, $board_type)
    {
        $m_f = ($mainhead === 'M') ? '1' : '2';
        $board_type_in = ($board_type === '0') ? "" : " AND h.board_type = '$board_type'";
        if ($cldt == '-1' || !$this->isValidDate($cldt)) {
            return [];
        }
        $subQuery = $this->db->table('heardt h')
            ->select('h.roster_id, judges')
            ->where('h.mainhead', $mainhead)
            ->where('h.next_dt', $cldt)
            ->whereIn('h.main_supp_flag', [1, 2])
            ->where('h.roster_id >', 0)
            ->groupBy('h.roster_id, h.judges');

        if ($board_type !== '0') {
            $subQuery->where('h.board_type', $board_type);
        }

        $subQuerySQL = $subQuery->getCompiledSelect();
        $query = $this->db->table("($subQuerySQL) a", false)
            ->select("STRING_AGG(CONCAT(j.first_name, ' ', j.sur_name), ', ' ORDER BY j.judge_seniority) AS jnm, a.roster_id, a.judges")
            ->join('master.roster_judge rj', 'rj.roster_id = a.roster_id', 'left')
            ->join('master.judge j', 'j.jcode = rj.judge_id', 'left')
            ->where('j.is_retired !=', 'Y')
            ->where('j.display', 'Y')
            ->where('rj.display', 'Y')
            ->groupBy('a.roster_id, a.judges')
            ->get()
            ->getResultArray();

        return $query; 
    }

    public function get_cl_print_partno1($mainhead, $list_dt, $roster_id, $board_type)
    {
        if ($list_dt == '-1' || !$this->isValidDate($list_dt)) {
            return [];
        }

        $builder = $this->db->table($this->table);
        $builder->select('clno')
            ->where('mainhead', $mainhead)
            ->where('next_dt', $list_dt)
            ->where('roster_id', $roster_id)
            ->whereIn('main_supp_flag', [1, 2]);

        if ($board_type !== '0') {
            $builder->where('board_type', $board_type);
        }

        $builder->groupBy('clno');

        $query = $builder->get();

        return $query->getResultArray();
    }



    // new
    public function getRosterDetails($list_dt, $mainhead, $board_type, $roster_id, $part_no)
    {
        $return = [];
        if($list_dt) {
            $leftjoin_submaster = '';
            if($mainhead != 'F'){
                $sub_head_name = "s.stagename";
                $leftjoin_subhead = "LEFT JOIN master.subheading s ON s.stagecode = h.subhead and s.display = 'Y' and s.listtype = '$mainhead'";
                $group_by = "GROUP BY h.diary_no , m.c_status,m.relief,u.name,us.section_name, m.lastorder, l.purpose, c1.short_description, m.active_fil_no, m.active_reg_year, m.casetype_id, m.active_casetype_id, m.ref_agency_state_id, m.reg_no_display,m.fil_dt, m.fil_no, m.fil_no_fh,m.reg_year_fh,m.mf_active, m.pet_name, m.res_name, m.pno, m.rno,m.diary_no_rec_date,s.stagename,ct.ent_dt,m.diary_no";
            } else {    
                $sub_head_name = "sm.id as submaster_id, sm.sub_name1, sm.sub_name2, sm.sub_name3, sm.sub_name4";
                $leftjoin_subhead = "LEFT JOIN category_allottment c ON  h.subhead = c.submaster_id and c.ros_id = '$roster_id' AND c.display = 'Y'";
                $leftjoin_submaster = "LEFT JOIN master.submaster sm ON h.subhead = sm.id AND sm.display = 'Y'";
                $group_by = "GROUP BY h.diary_no , m.c_status,m.relief,u.name,us.section_name, m.lastorder, l.purpose, c1.short_description, m.active_fil_no, m.active_reg_year, m.casetype_id, m.active_casetype_id, m.ref_agency_state_id, m.reg_no_display,m.fil_dt, m.fil_no, m.fil_no_fh,m.reg_year_fh,m.mf_active, m.pet_name, m.res_name, m.pno, m.rno,m.diary_no_rec_date,ct.ent_dt,m.diary_no, sm.id";
            }

            $sql = "SELECT m.c_status, m.relief, u.name, us.section_name, 
            --STR_TO_DATE(trim(SUBSTRING_INDEX(m.lastorder, '-Ord dt:', -1)), '%d-%m-%Y') AS last_listed_date,
            TO_DATE(trim(substring(m.lastorder FROM position('-Ord dt:' IN m.lastorder) + 9)), 'DD-MM-YYYY') AS last_listed_date,
            m.lastorder, h.*, l.purpose, c1.short_description, 
            active_fil_no, m.active_reg_year, m.casetype_id, m.active_casetype_id, m.ref_agency_state_id, m.reg_no_display, 
            EXTRACT(YEAR FROM m.fil_dt) AS fil_year, m.fil_no, m.fil_dt, m.fil_no_fh, m.reg_year_fh AS fil_year_f, m.mf_active, m.pet_name, 
            m.res_name, pno, rno, m.diary_no_rec_date, h.list_before_remark, $sub_head_name FROM heardt h 
                        left join main m on m.diary_no = h.diary_no 
                        LEFT JOIN master.casetype c1 ON active_casetype_id = c1.casecode            
                        LEFT JOIN master.listing_purpose l ON l.code = h.listorder
                        $leftjoin_submaster
                        $leftjoin_subhead             
                        LEFT JOIN master.users u ON u.usercode = m.dacode AND u.display = 'Y'
                        LEFT JOIN master.usersection us ON us.id = u.section   
                        LEFT JOIN conct ct on m.diary_no=ct.diary_no and ct.list='Y'  
                        WHERE next_dt = '$list_dt' and mainhead = '$mainhead' and 
                        roster_id = '$roster_id' and (main_supp_flag = 1 OR main_supp_flag = 2) and clno = $part_no and brd_slno > 0 AND l.display = 'Y'             
                        $group_by 
                        ORDER BY h.brd_slno, CASE WHEN h.conn_key = h.diary_no THEN '0000-00-00' ELSE '99' END ASC, 
                        CASE WHEN ct.ent_dt IS NOT NULL THEN ct.ent_dt ELSE '9999-12-31'::timestamp END ASC,
                        CAST(SUBSTRING(CAST(m.diary_no AS text) FROM LENGTH(CAST(m.diary_no AS text)) - 3) AS INTEGER) ASC, 
                        CAST(SUBSTRING(CAST(m.diary_no AS text) FROM 1 FOR LENGTH(CAST(m.diary_no AS text)) - 4) AS INTEGER) ASC;";
            $query = $this->db->query($sql);
            if ($query->getNumRows() >= 1) {
                $return = $query->getResultArray();        
            }
        }    
        return $return;
        
        /*$board_type_in = ($board_type == '0') ? "" : " and board_type = '$board_type'";

        $sql = "
            SELECT m.c_status, m.relief, u.name, us.section_name, 
            STR_TO_DATE(trim(SUBSTRING_INDEX(m.lastorder, '-Ord dt:', -1)), '%d-%m-%Y') AS last_listed_date,
            m.lastorder, h.*, l.purpose, c1.short_description, 
            active_fil_no, m.active_reg_year, m.casetype_id, m.active_casetype_id, m.ref_agency_state_id, m.reg_no_display, 
            YEAR(m.fil_dt) fil_year, m.fil_no, m.fil_dt, m.fil_no_fh, m.reg_year_fh AS fil_year_f, m.mf_active, m.pet_name, 
            m.res_name, pno, rno, m.diary_no_rec_date, h.list_before_remark 
            FROM {$this->table} h 
            LEFT JOIN main m ON m.diary_no = h.diary_no 
            LEFT JOIN casetype c1 ON m.active_casetype_id = c1.casecode            
            LEFT JOIN listing_purpose l ON l.code = h.listorder
            LEFT JOIN users u ON u.usercode = m.dacode AND u.display = 'Y'
            LEFT JOIN usersection us ON us.id = u.section   
            WHERE next_dt = '$list_dt' AND mainhead = '$mainhead' 
            AND roster_id = '$roster_id' AND (main_supp_flag = 1 OR main_supp_flag = 2) 
            AND clno = $part_no AND brd_slno > 0 AND l.display = 'Y'
            GROUP BY h.diary_no 
            ORDER BY h.brd_slno, IF(h.conn_key = h.diary_no, '0000-00-00', 99) ASC
        ";

        return $this->db->query($sql)->getResultArray();*/
    }

    public function getBenchJudges12($roster_id)
    {
        $sql = "
            SELECT r.id, GROUP_CONCAT(j.jcode ORDER BY j.judge_seniority) jcd, 
            GROUP_CONCAT(j.jname ORDER BY j.judge_seniority) jnm, r.courtno, 
            rb.bench_no, mb.abbr, mb.board_type_mb, r.tot_cases, r.frm_time 
            FROM roster r 
            LEFT JOIN master.roster_bench rb ON rb.id = r.bench_id 
            LEFT JOIN master.master_bench mb ON mb.id = rb.bench_id
            LEFT JOIN master.roster_judge rj ON rj.roster_id = r.id 
            LEFT JOIN master.judge j ON j.jcode = rj.judge_id
            WHERE j.is_retired != 'Y' AND j.display = 'Y' 
            AND rj.display = 'Y' AND rb.display = 'Y' 
            AND mb.display = 'Y' AND r.display = 'Y' 
            AND r.id = '$roster_id' 
            GROUP BY r.id 
            ORDER BY r.id, j.judge_seniority
        ";

        return $this->db->query($sql)->getRowArray();
    }

    // end




    public function getClPrintMainhead1($mainhead, $board_type)
    {
        $builder = $this->db->table('heardt c');
        $builder->select('c.next_dt')
            ->where('c.mainhead', $mainhead)
            ->where('c.next_dt >=', date('Y-m-d'))
            ->whereIn('c.main_supp_flag', [1, 2]);
        if ($board_type !== '0') {
            $builder->where('c.board_type', $board_type);
        }
        $builder->groupBy('c.next_dt');
        // pr($builder->getCompiledSelect());
        return $builder->get()->getResultArray();
    }


    public function getBenchJudges()
    {
        $subQuery = $this->db->table('heardt h')
            ->select('h.roster_id, judges')
            ->where('h.mainhead', 'M')
            ->where('h.board_type', 'J')
            ->where('h.next_dt >=', date('Y-m-d'))
            ->whereIn('h.main_supp_flag', [1, 2])
            ->where('h.roster_id >', 0)
            ->groupBy('h.roster_id, h.judges')
            ->getCompiledSelect();
        return $this->db->table("($subQuery) a", false)
            ->select("STRING_AGG(CONCAT(j.first_name, ' ', j.sur_name), ', ' ORDER BY j.judge_seniority) AS jnm, a.roster_id, a.judges")
            ->join('master.roster_judge rj', 'rj.roster_id = a.roster_id', 'left')
            ->join('master.judge j', 'j.jcode = rj.judge_id', 'left')
            ->where('j.is_retired !=', 'Y')
            ->where('j.display', 'Y')
            ->where('rj.display', 'Y')
            ->groupBy('a.roster_id, a.judges')
            ->get()
            ->getResultArray();
    }





    public function getBenches()
    {
        return $this->db->query("
            SELECT 
                string_agg(CONCAT(j.first_name, ' ', j.sur_name), ', ' ORDER BY j.judge_seniority) AS jnm, 
                a.roster_id, 
                a.judges 
            FROM (
                SELECT h.roster_id, h.judges 
                FROM heardt h 
                WHERE h.mainhead = 'M' 
                AND h.board_type = 'J' 
                AND h.next_dt >= CURRENT_DATE 
                AND (h.main_supp_flag = '1' OR h.main_supp_flag = '2') 
                AND h.roster_id > 0 
                GROUP BY h.roster_id, h.judges
            ) a
            LEFT JOIN master.roster_judge rj ON rj.roster_id = a.roster_id 
            LEFT JOIN master.judge j ON j.jcode = rj.judge_id
            WHERE j.is_retired != 'Y' 
            AND j.display = 'Y' 
            AND rj.display = 'Y' 
            GROUP BY a.roster_id, a.judges
        ")->getResultArray();
    }

    public function getClPrintMainhead($mainhead, $board_type)
    {
        if ($mainhead === 'M') {
            $m_f = '1';
        } elseif ($mainhead === 'F') {
            $m_f = '2';
        } else {
            $m_f = '';
        }

        // Prepare the SQL query using CodeIgniter Query Builder
        $builder = $this->db->table($this->table);
        $builder->select('next_dt');
        $builder->where('mainhead', $mainhead);
        $builder->where('next_dt >= CURRENT_DATE');

        // Add board type condition if provided
        if ($board_type !== '0') {
            $builder->where('board_type', $board_type);
        }

        $builder->groupStart()
            ->where('main_supp_flag', '1')
            ->orWhere('main_supp_flag', '2')
            ->groupEnd();

        $builder->orderBy('next_dt');

        // Execute the query
        $query = $builder->get();
        $results = $query->getResultArray();

        // Prepare the HTML options
        $options = '<option value="0" selected>SELECT</option>';
        if (!empty($results)) {
            foreach ($results as $row) {
                $options .= '<option value="' . $row['next_dt'] . '">' . date("d-m-Y", strtotime($row['next_dt'])) . '</option>';
            }
        } else {
            $options .= '<option value="0" selected>EMPTY</option>';
        }

        return $options;
    }

    public function getClPrintBenches($mainhead, $board_type, $cldt)
    {
        if ($mainhead === 'M') {
            $m_f = '1';
        } elseif ($mainhead === 'F') {
            $m_f = '2';
        } else {
            $m_f = '';
        }




        $subquery = $this->db->table('heardt')
            ->select('roster_id, judges')
            ->where('mainhead', $mainhead)
            //->where('next_dt', $cldt)
            ->whereIn('main_supp_flag', ['1', '2'])
            ->where('roster_id > 0')
            ->groupBy('roster_id, judges')
            ->getCompiledSelect();


        $builder = $this->db->table('heardt h');
        $builder->select('string_agg(j.first_name || \' \' || j.sur_name, \', \' ORDER BY j.judge_seniority) AS jnm, h.roster_id, h.judges');
        $builder->join('master.roster_judge rj', 'rj.roster_id = h.roster_id', 'left');
        $builder->join('master.judge j', 'j.jcode = rj.judge_id', 'left');
        $builder->join("($subquery) a", 'a.roster_id = h.roster_id', 'left');
        $builder->where('j.is_retired !=', 'Y')
            ->where('j.display', 'Y')
            ->where('rj.display', 'Y')
            ->groupBy('h.roster_id, h.judges');

        // Execute the query
        $query = $builder->get();
        $results = $query->getResultArray();

        $options = '<option value="0" selected>SELECT</option>';
        if (!empty($results)) {
            foreach ($results as $row) {
                $options .= '<option value="' . $row["judges"] . '|' . $row["roster_id"] . '">' . $row['jnm'] . '</option>';
            }
        } else {
            $options .= '<option value="0" selected>EMPTY</option>';
        }

        return $options;
    }



    public function getPartNumbers($mainhead, $list_dt, $roster_id, $board_type)
    {
        $builder = $this->db->table($this->table);
        $builder->select('clno');
        $builder->where('mainhead', $mainhead);
        $builder->where('next_dt', $list_dt);
        $builder->where('roster_id', $roster_id);
        $builder->groupStart()
            ->where('main_supp_flag', '1')
            ->orWhere('main_supp_flag', '2')
            ->groupEnd();

        if ($board_type != '0') {
            $builder->where('board_type', $board_type);
        }

        $builder->groupBy('clno');

        return $builder->get()->getResultArray();
    }

    public function getUpcomingDates()
    {
        $builder = $this->db->table('advance_cl_printed')
            ->select('next_dt')
            ->where('next_dt >=', date('Y-m-d'))
            ->groupBy('next_dt');
        return $builder->get()->getResultArray();
    }



    function validate_final_cl_printed($next_dt, $partno, $mainhead, $roster_id)
    {
        $sql = "select * from cl_printed where next_dt = '$next_dt' AND part = '$partno' AND m_f = '$mainhead' AND roster_id IN ($roster_id) AND display='Y'";
        $result = $this->db->query($sql);
        if ($result->getNumRows() > 0) {
            return 1;
        } else {
            return null;
        }
    }


    public function singleJudgeRosterDetails($next_dt_selected, $roster_id_imploded, $board_type)
    {
        /*$is_selected = in_array($next_dt_selected, explode(",", $roster_id_imploded)) ? 1 : 0;

        $sql_query = "SELECT r.id, rj.judge_id, $is_selected AS is_selected
                      FROM master.roster r 
                      INNER JOIN master.roster_bench rb ON rb.id = r.bench_id 
                      INNER JOIN master.master_bench mb ON mb.id = rb.bench_id 
                      INNER JOIN master.roster_judge rj ON rj.roster_id = r.id                                                
                      WHERE mb.board_type_mb = '$board_type'  
                      AND rj.display = 'Y' AND rb.display = 'Y' AND mb.display = 'Y' AND r.display = 'Y' 
                      AND r.m_f = '1' AND r.from_date = '$next_dt_selected'";
        $query = $this->db->query($sql_query);
        return $query->getResultArray();
        */

        $sql_query = "SELECT r.id, rj.judge_id, 
                        CASE 
                            WHEN position(r.id::text IN '$roster_id_imploded') > 0 THEN 1
                            ELSE 0
                        END AS is_selected
                    FROM master.roster r 
                    INNER JOIN master.roster_bench rb ON rb.id = r.bench_id 
                    INNER JOIN master.master_bench mb ON mb.id = rb.bench_id 
                    INNER JOIN master.roster_judge rj ON rj.roster_id = r.id                                                
                    WHERE mb.board_type_mb = '$board_type'  
                    AND rj.display = 'Y' 
                    AND rb.display = 'Y' 
                    AND mb.display = 'Y' 
                    AND r.display = 'Y' 
                    AND r.m_f = '1' 
                    AND r.from_date = '$next_dt_selected'
                ";
        $query = $this->db->query($sql_query);
        return $query->getResultArray();

    }


    public function singleJudgeFinalProcessGetCases($inArr)
    {
        $builder = $this->db->table('some_table');
        foreach ($inArr as $key => $value) {
            $builder->where($key, $value);
        }
        return $builder->get()->getResultArray();
    }

    public function updateHeardtData($table, $diaryNo, $data)

    {
        return $this->db->table($table)->update($data, ['diary_no' => $diaryNo]);
    }

    public function q_from_heardt_to_last_heardt($dno)
    {
        $sql = "INSERT INTO last_heardt (diary_no, conn_key, next_dt, mainhead, subhead, clno, brd_slno, roster_id, judges, coram, board_type, usercode, ent_dt, module_id, mainhead_n, subhead_n,
                 main_supp_flag, listorder, tentative_cl_dt, lastorder, listed_ia, sitting_judges, list_before_remark, is_nmd, no_of_time_deleted) 
                 SELECT j.* FROM (SELECT h.diary_no, h.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, h.board_type, h.usercode, h.ent_dt, h.module_id, h.mainhead_n, h.subhead_n,
                 h.main_supp_flag, h.listorder, h.tentative_cl_dt, m.lastorder, h.listed_ia, h.sitting_judges,
                 h.list_before_remark, h.is_nmd, h.no_of_time_deleted
        FROM main m
        LEFT JOIN heardt h ON m.diary_no = h.diary_no     
        WHERE h.diary_no = '$dno' AND h.diary_no > 0) j                
        LEFT JOIN last_heardt l ON j.diary_no = l.diary_no
        AND l.conn_key = j.conn_key
        AND l.next_dt = j.next_dt
        AND l.mainhead = j.mainhead
        AND l.board_type = j.board_type
        AND l.subhead = j.subhead
        AND l.clno = j.clno
        AND l.coram = j.coram
        AND l.judges = j.judges
        AND l.roster_id = j.roster_id
        AND l.listorder = j.listorder
        AND l.tentative_cl_dt = j.tentative_cl_dt
        AND (CASE WHEN j.listed_ia IS NULL THEN TRUE ELSE l.listed_ia = j.listed_ia END)
        AND (CASE WHEN j.list_before_remark IS NULL THEN TRUE ELSE l.list_before_remark = j.list_before_remark END)
        AND l.no_of_time_deleted = j.no_of_time_deleted
        AND l.is_nmd = j.is_nmd
        AND l.main_supp_flag = j.main_supp_flag
        AND (l.bench_flag = '' OR l.bench_flag IS NULL)
        WHERE l.diary_no IS NULL";

        $query = $this->db->query($sql);
        return $query;
    }


    function get_diary_with_connected_cases($diary_no)
    {
        $builder = $this->db->table('main');
        $builder->select("string_agg(diary_no::text, ',') as dnos");
        $builder->where('conn_key', $diary_no);
        $builder->groupBy('conn_key');
        $query = $builder->get();

        $row = $query->getRow();
        if ($row) {
            return $row->dnos;
        } else {
            return null;
        }
    }


    public function getRosterJudges($p1, $cldt, $board_type)
    {
        $cldt = date('Y-m-d', strtotime($cldt));
        $m_f = '';
        $from_to_dt = '';

        // Handle conditions based on $p1
        if ($p1 == "M") {
            $m_f = "AND rj.m_f = '1'";
            $from_to_dt = ($board_type == 'R') ? "AND rj.to_date = '0000-00-00'" : "AND rj.from_date = '$cldt'";
        } elseif ($p1 == "L") {
            $m_f = "AND rj.m_f = '3'";
            $from_to_dt = "AND rj.from_date = '$cldt'";
        } elseif ($p1 == "S") {
            $m_f = "AND rj.m_f = '4'";
            $from_to_dt = "AND rj.from_date = '$cldt'";
        } else {
            $m_f = "AND rj.m_f = '2'";
            $from_to_dt = "AND rj.from_date = '$cldt'";
        }

        // Query builder
        $builder = $this->db->table('heardt h')
            ->select('ro.courtno, 
                  STRING_AGG(j.jcode::text, \',\' ORDER BY j.judge_seniority) AS jcd,
                  STRING_AGG(CONCAT(j.first_name, \' \', j.sur_name), \',\' ORDER BY j.judge_seniority) AS jnm,
                  h.roster_id AS id')
            ->join('master.roster ro', 'ro.id = h.roster_id', 'left')
            ->join('master.roster_judge rj', 'rj.roster_id = h.roster_id', 'left')
            ->join('master.judge j', 'j.jcode = rj.judge_id', 'left')
            ->where('h.next_dt', $cldt)
            // ->where('h.mainhead', $p1)
            // ->where('h.board_type', $board_type)
            // ->where('h.roster_id >', 0)
            ->groupStart()
            ->where('h.main_supp_flag', 1)
            ->orWhere('h.main_supp_flag', 2)
            ->groupEnd()
            // ->where('j.is_retired !=', 'Y')
            // ->where('j.display', 'Y')
            // ->where('rj.display', 'Y')
            ->groupBy('ro.courtno, h.roster_id');
        $query = $builder->get();
        $lastQuery = $this->db->getLastQuery();

        return $query->getResultArray();
    }



    public function f_make_proposal($pool_dt, $chk_tr, $ucode)
    {

        $sql0 = "
            INSERT INTO last_heardt (diary_no, conn_key, next_dt, mainhead, subhead, clno, brd_slno, roster_id, judges, coram, board_type, usercode, ent_dt, module_id, mainhead_n, subhead_n,
            main_supp_flag, listorder, tentative_cl_dt, lastorder, listed_ia, sitting_judges, bench_flag,
            list_before_remark, is_nmd, no_of_time_deleted) 
            SELECT j.diary_no, j.conn_key, j.next_dt, j.mainhead, j.subhead, j.clno, j.brd_slno, j.roster_id, j.judges, j.coram, j.board_type, j.usercode, j.ent_dt, j.module_id, j.mainhead_n, j.subhead_n, j.main_supp_flag, j.listorder, j.tentative_cl_dt, j.lastorder, j.listed_ia, j.sitting_judges, 'X'::text, j.list_before_remark, j.is_nmd, j.no_of_time_deleted 
            FROM (
                SELECT h.diary_no, h.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, h.board_type, h.usercode, h.ent_dt, h.module_id, 
                       h.mainhead_n, h.subhead_n, h.main_supp_flag, h.listorder, h.tentative_cl_dt, m.lastorder, h.listed_ia, h.sitting_judges,
                       h.list_before_remark, h.is_nmd, h.no_of_time_deleted
                FROM main m
                INNER JOIN heardt h ON m.diary_no = h.diary_no 
                WHERE (m.diary_no IN ($chk_tr) OR (m.conn_key IS NOT NULL AND m.conn_key <> '' AND m.conn_key::bigint IN ($chk_tr) AND m.conn_key::bigint > 0))
            ) j                
            LEFT JOIN last_heardt l ON j.diary_no = l.diary_no AND l.conn_key = j.conn_key AND l.next_dt = j.next_dt AND 
            l.mainhead = j.mainhead AND l.board_type = j.board_type AND l.subhead = j.subhead AND l.clno = j.clno AND
            l.coram = j.coram AND l.judges = j.judges AND l.roster_id = j.roster_id 
            AND l.listorder = j.listorder AND l.tentative_cl_dt = j.tentative_cl_dt AND 
            (CASE WHEN j.listed_ia IS NULL THEN true ELSE l.listed_ia = j.listed_ia END) AND 
            (CASE WHEN j.list_before_remark IS NULL THEN true ELSE l.list_before_remark = j.list_before_remark END) 
            AND l.no_of_time_deleted = j.no_of_time_deleted AND l.is_nmd = j.is_nmd AND
            l.main_supp_flag = j.main_supp_flag AND (l.bench_flag = '' OR l.bench_flag IS NULL) 
            WHERE l.diary_no IS NULL;";
        $this->db->query($sql0);
        $sql = "
            UPDATE heardt
            SET next_dt = '$pool_dt', clno = 0, brd_slno = 0, roster_id = 0, 
                judges = 0, main_supp_flag = 0, module_id = 9, 
                usercode = $ucode, ent_dt = NOW(), tentative_cl_dt = '$pool_dt'
            FROM main m 
            WHERE m.diary_no = heardt.diary_no 
              AND (m.diary_no IN ($chk_tr) OR (m.conn_key IS NOT NULL AND m.conn_key <> '' AND m.conn_key::bigint IN ($chk_tr) AND m.conn_key::bigint > 0)) 
              AND m.diary_no > 0;";
        $this->db->query($sql);
        $affectedRows = $this->db->affectedRows();

        return $affectedRows > 0 ? 1 : 0;
    }
    public function get_cl_print_partno($mainhead, $list_dt, $roster_id, $board_type)
    {
        $list_dt = date('Y-m-d', strtotime($list_dt));
        $builder = $this->db->table('heardt');
        $builder->select('clno')
            ->where('mainhead', $mainhead)
            ->where('next_dt', $list_dt);
        if (is_array($roster_id)) {
            $builder->whereIn('roster_id', $roster_id);
        } else {
            $builder->where('roster_id', (int)$roster_id);
        }
        if ($board_type !== '0') {
            $builder->where('board_type', $board_type);
        }
        $builder->groupStart()
            ->where('main_supp_flag', 1)
            ->orWhere('main_supp_flag', 2)
            ->groupEnd();
        $builder->groupBy('clno');

        $query = $builder->get();
        //$lastQuery = $this->db->getLastQuery();
        //echo $lastQuery;die;
        $results = $query->getResultArray();
        $options = [];

        if (!empty($results)) {
            $options[] = '<option value="0" selected>SELECT</option>';
            foreach ($results as $row) {
                $options[] = '<option value="' . htmlspecialchars($row['clno'], ENT_QUOTES) . '">' . htmlspecialchars($row['clno'], ENT_QUOTES) . '</option>';
            }
        } else {
            $options[] = '<option value="1" selected>1 (empty)</option>';
        }
        return $options;
        //return implode('', $options);
    }

    public function checkIfListIsPrinted($request)
    {
        $return = false;
        $nt_dt = date('Y-m-d',strtotime($request['ndt']));
        $query = $this->db->table('cl_printed')
            ->where('next_dt', $nt_dt)
            ->where('next_dt >=', date('Y-m-d'))
            ->where('m_f', $request['heading'])
            ->where('roster_id', $request['coram'])
            ->where('part', $request['session'])
            ->where('main_supp', $request['main_supp_flag'])
            ->where('display', 'Y');
        
        if($query->countAllResults()> 0){
            $return = true;
        }
        return $return;
        //return $query->countAllResults() > 0;
    }

    public function addLastHeardt($data)
    {
        $this->db->table('last_heardt')->insert($data);
    }

    public function getSectionList($list_dt, $mainhead, $board_type)
    {
        $builder = $this->db->table('heardt h');

        $builder->select("u.name, 
            CASE 
                WHEN us.section_name IS NOT NULL THEN us.section_name 
                ELSE tentative_section(CAST(m.diary_no AS bigint)) 
            END AS section_name, 
            m.conn_key AS main_key, 
            h.*, 
            l.purpose, 
            c1.short_description, 
            active_fil_no, 
            m.active_reg_year, 
            m.casetype_id, 
            m.active_casetype_id, 
            m.ref_agency_state_id, 
            m.reg_no_display, 
            EXTRACT(YEAR FROM m.fil_dt) AS fil_year, 
            m.fil_no, 
            m.fil_dt, 
            m.fil_no_fh, 
            m.reg_year_fh AS fil_year_f, 
            m.mf_active, 
            m.pet_name, 
            m.res_name, 
            pno, 
            rno, 
            m.diary_no_rec_date, 
            CASE 
                WHEN (CAST(m.diary_no AS bigint) = CAST(m.conn_key AS bigint) OR m.conn_key = '0' OR m.conn_key IS NULL) 
                THEN 0 ELSE 1 
            END AS main_or_connected, 
            (SELECT CASE WHEN diary_no IS NOT NULL THEN 1 ELSE 0 END 
                FROM conct 
                WHERE diary_no = m.diary_no AND list = 'Y') AS listed");

        $builder->join('main m', 'CAST(m.diary_no AS bigint) = CAST(h.diary_no AS bigint)', 'left')
            ->join('master.casetype c1', 'm.active_casetype_id = c1.casecode', 'left')
            ->join('master.listing_purpose l', 'l.code = h.listorder', 'left')
            ->join('master.users u', 'u.usercode = m.dacode AND u.display = \'Y\'', 'left')
            ->join('master.usersection us', 'us.id = u.section', 'left')
            ->join('mul_category c2', 'c2.diary_no = h.diary_no AND c2.display = \'Y\'', 'left');

        $builder->where('c2.diary_no IS NOT NULL')
            ->where('l.display', 'Y')
            ->whereIn('h.listorder', [4, 5, 25, 32, 24, 7, 8, 21, 48, 2, 16, 49])
            ->where('h.board_type', $board_type)
            ->where('m.c_status', 'P')
            ->where('h.main_supp_flag', '0')
            ->where('h.next_dt', $list_dt)
            ->where('h.mainhead', $mainhead);

        // Uncomment to debug SQL query
        // pr($builder->getCompiledSelect());

        $query = $builder->get();
        return $query->getResult();
    }




    public function getCases($list_dt, $list_dt_to, $mainhead, $courtno)
    {
        if (!$this->isValidDate($list_dt) || !$this->isValidDate($list_dt_to)) {
            return [
                'error' => 'Invalid date format.'
            ];
        }

        $builder = $this->db->table('heardt h');
        $builder->select("h.next_dt, CONCAT(h.judges, '|', roster_id) AS rsdf");
        $builder->join('master.roster r', 'h.roster_id = r.id');
        $builder->where('h.next_dt >=', $list_dt);
        $builder->where('h.next_dt <=', $list_dt_to);
        $builder->where('brd_slno >', 0);
        $builder->where('clno >', 0);
        $builder->where('mainhead', $mainhead);

        if ($courtno != "0") {
            $builder->where('r.courtno', (string)$courtno);
        }

        $m_f = ($mainhead == 'M') ? '1' : '2';
        $builder->where('r.m_f', $m_f);
        $builder->where('main_supp_flag', 1);
        $builder->groupBy('h.next_dt, h.judges, roster_id');

        return [
            'cases' => $builder->get()->getResultArray()
        ];
    }

    public function isPrinted($list_dt, $part_no, $mainhead, $roster_id)
    {
        return $this->db->table('last_heardt')
            ->where([
                'next_dt' => $list_dt,
                'clno' => $part_no,
                'mainhead' => $mainhead,
                'roster_id' => $roster_id
            ])
            ->countAllResults() > 0;
    }
    public function reshuffleFromDesiredNo($listing_dt, $chk_jud_id, $mf, $partno, $chk_rs_id, $from_cl_no)
    {
        $from_cl_no -= 1;

        if ($mf != 'F') {
            $leftjoin_subhead = "LEFT JOIN master.subheading s ON s.stagecode = h.subhead AND s.display = 'Y' AND s.listtype = '$mf'";
            $order_by = "s.priority, RIGHT(h.diary_no, 4) ASC, LEFT(h.diary_no,LENGTH(h.diary_no)-4) ASC";
        } else {
            $leftjoin_subhead = "LEFT JOIN category_allottment c ON h.subhead = c.submaster_id AND c.ros_id = '$chk_rs_id' AND c.display = 'Y'";
            $order_by = "IF(h.subhead = 913, 0, 9999) ASC, RIGHT(h.diary_no, 4) ASC, LEFT(h.diary_no,LENGTH(h.diary_no)-4) ASC";
        }
        $sql1 = "UPDATE (
                    SELECT @a:=@a+1 AS serial_number, a.diary_no, a.conn_key FROM (
                        SELECT h.diary_no, m.conn_key FROM heardt h 
                        INNER JOIN main m ON m.diary_no = h.diary_no 
                        $leftjoin_subhead 
                        WHERE m.c_status = 'P' 
                        AND (m.diary_no = m.conn_key OR m.conn_key=0 OR m.conn_key = '' OR m.conn_key IS NULL)
                        AND h.mainhead = '$mf' 
                        AND h.next_dt = '$listing_dt' 
                        AND h.clno = '$partno' 
                        AND h.brd_slno > 0 
                        AND h.judges = '$chk_jud_id' 
                        AND h.roster_id > 0 
                        ORDER BY $order_by
                    ) a, (SELECT @a:= $from_cl_no) AS b) x, heardt h 
                    SET h.brd_slno = x.serial_number, h.conn_key = x.conn_key 
                    WHERE h.diary_no = x.diary_no AND h.diary_no > 0;";

        $this->db->query($sql1);
        $sql_conn = "INSERT INTO last_heardt (diary_no, conn_key, next_dt, mainhead, subhead, clno, brd_slno, roster_id, judges, coram, board_type, usercode, ent_dt, module_id, mainhead_n, subhead_n,
                            main_supp_flag, listorder, tentative_cl_dt, lastorder, listed_ia, sitting_judges) 
                     SELECT j.* FROM (
                         SELECT a.* FROM (
                             SELECT c.diary_no AS conc_diary_no, m.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, 
                             h.board_type, h.usercode, h.ent_dt, h.module_id, h.mainhead_n, h.subhead_n, h.main_supp_flag, h.listorder, 
                             h.tentative_cl_dt, m.lastorder, h.listed_ia, h.sitting_judges 
                             FROM heardt h
                             INNER JOIN main m ON m.diary_no = h.diary_no 
                             INNER JOIN conct c ON c.conn_key = m.conn_key 
                             WHERE c.list = 'Y' 
                             AND m.c_status = 'P' 
                             AND m.diary_no = m.conn_key     
                             AND h.mainhead = '$mf' 
                             AND h.next_dt = '$listing_dt' 
                             AND h.clno = '$partno' 
                             AND h.brd_slno > 0 
                             AND h.judges = '$chk_jud_id' 
                             AND h.roster_id > 0
                         ) a
                         INNER JOIN main m ON a.conc_diary_no = m.diary_no
                         INNER JOIN heardt h ON a.conc_diary_no = h.diary_no        
                     ) j                
                     LEFT JOIN last_heardt l ON j.conc_diary_no = l.diary_no 
                     AND l.next_dt = j.next_dt 
                     AND l.listorder = j.listorder 
                     AND l.mainhead = j.mainhead 
                     AND l.subhead = j.subhead 
                     AND l.judges = j.judges 
                     AND l.roster_id = j.roster_id 
                     AND l.clno = j.clno 
                     AND l.main_supp_flag = j.main_supp_flag 
                     AND (l.bench_flag = '' OR l.bench_flag IS NULL) 
                     WHERE l.diary_no IS NULL;";

        $this->db->query($sql_conn);

        return true;
    }
    public function reshuffle($listing_dt, $chk_jud_id, $mf, $partno, $chk_rs_id)
    {

        $new_no = $this->getNewNumber($listing_dt, $chk_jud_id, $mf, $partno);

        if ($mf != 'F') {
            $leftjoin_subhead = "LEFT JOIN master.subheading s ON s.stagecode = h.subhead AND s.display = 'Y' AND s.listtype = '$mf'";
            $order_by = "s.priority, RIGHT(h.diary_no, 4) ASC, LEFT(h.diary_no, LENGTH(h.diary_no)-4) ASC";
        } else {
            $leftjoin_subhead = "LEFT JOIN category_allottment c ON h.subhead = c.submaster_id AND c.ros_id = '$chk_rs_id' AND c.display = 'Y'";
            $order_by = "IF(h.subhead = 913, 0, 9999) ASC, RIGHT(h.diary_no, 4) ASC, LEFT(h.diary_no, LENGTH(h.diary_no)-4) ASC";
        }
        $sql1 = "UPDATE (
                    SELECT @a:=@a+1 AS serial_number, a.diary_no, a.conn_key FROM (
                        SELECT h.diary_no, m.conn_key FROM heardt h 
                        INNER JOIN main m ON m.diary_no = h.diary_no 
                        $leftjoin_subhead 
                        WHERE (m.diary_no = m.conn_key OR m.conn_key=0 OR m.conn_key = '' OR m.conn_key IS NULL)
                        AND m.c_status = 'P' 
                        AND h.mainhead = '$mf' 
                        AND h.next_dt = '$listing_dt' 
                        AND h.clno = '$partno' 
                        AND h.brd_slno > 0 
                        AND h.judges = '$chk_jud_id' 
                        AND h.roster_id > 0
                        ORDER BY $order_by
                    ) a, (SELECT @a:= $new_no) AS b) x, heardt h 
                    SET h.brd_slno = x.serial_number, h.conn_key = x.conn_key 
                    WHERE h.diary_no = x.diary_no AND h.diary_no > 0;";

        $this->db->query($sql1);
        $sql_conn = "INSERT INTO last_heardt (diary_no, conn_key, next_dt, mainhead, subhead, clno, brd_slno, roster_id, judges, coram, board_type, usercode, ent_dt, module_id, mainhead_n, subhead_n,
                            main_supp_flag, listorder, tentative_cl_dt, lastorder, listed_ia, sitting_judges) 
                     SELECT j.* FROM (
                         SELECT a.* FROM (
                             SELECT c.diary_no AS conc_diary_no, m.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, 
                             h.board_type, h.usercode, h.ent_dt, h.module_id, h.mainhead_n, h.subhead_n, h.main_supp_flag, h.listorder, 
                             h.tentative_cl_dt, m.lastorder, h.listed_ia, h.sitting_judges 
                             FROM heardt h
                             INNER JOIN main m ON m.diary_no = h.diary_no 
                             INNER JOIN conct c ON c.conn_key = m.conn_key 
                             WHERE c.list = 'Y' 
                             AND m.c_status = 'P' 
                             AND m.diary_no = m.conn_key     
                             AND h.mainhead = '$mf' 
                             AND h.next_dt = '$listing_dt' 
                             AND h.clno = '$partno' 
                             AND h.brd_slno > 0 
                             AND h.judges = '$chk_jud_id' 
                             AND h.roster_id > 0
                         ) a
                         INNER JOIN main m ON a.conc_diary_no = m.diary_no
                         INNER JOIN heardt h ON a.conc_diary_no = h.diary_no        
                     ) j                
                     LEFT JOIN last_heardt l ON j.conc_diary_no = l.diary_no 
                     AND l.next_dt = j.next_dt 
                     AND l.listorder = j.listorder 
                     AND l.mainhead = j.mainhead 
                     AND l.subhead = j.subhead 
                     AND l.judges = j.judges 
                     AND l.roster_id = j.roster_id 
                     AND l.clno = j.clno 
                     AND l.main_supp_flag = j.main_supp_flag 
                     AND (l.bench_flag = '' OR l.bench_flag IS NULL) 
                     WHERE l.diary_no IS NULL;";

        $this->db->query($sql_conn);

        return true;
    }
    public function isPrinted1($list_dt, $part_no, $mainhead, $roster_id)
    {
        return $this->db->table('cl_printed')
            ->where([
                'next_dt' => $list_dt,
                'part' => $part_no,
                'm_f' => $mainhead,
                'roster_id' => $roster_id,
                'display' => 'Y'
            ])->countAllResults() > 0;
    }

    public function getCoramByConditions($list_dt, $part_no, $roster_id)
    {
        return $this->db->table('heardt')
            ->select('m.conn_key, h.coram')
            ->join('main m', 'm.diary_no = heardt.diary_no')
            ->where([
                'm.c_status' => 'P',
                'm.diary_no' => 'm.conn_key',
                'heardt.roster_id' => $roster_id,
                'heardt.next_dt' => $list_dt,
                'heardt.clno' => $part_no,
                'heardt.brd_slno >' => 0,
                'heardt.mainhead' => 'M',
                'heardt.board_type' => 'J'
            ])
            ->get()->getResultArray();
    }
    private function getNewNumber($listing_dt, $chk_jud_id, $mf, $partno)
    {
        $builder = $this->db->table('heardt');
        $builder->selectMax('brd_slno');
        $builder->where('next_dt', $listing_dt);
        $builder->where('mainhead', $mf);
        $builder->where('clno', $partno);
        $builder->where('judges', $chk_jud_id);
        $builder->where('roster_id >', 0);

        $maxNumber = $builder->get()->getRow()->brd_slno;

        return $maxNumber !== null ? $maxNumber + 1 : 1;
    }


    public function getReportData($list_dt) {
      $formattedDate = date('Y-m-d', strtotime($list_dt));

		$subQuery1 = $this->db->table('master.submaster s')
			->select('s.id, s.sub_name1, s.subcode1')
			->where('s.display', 'Y')
			->where('s.flag', 's')
			->where('s.subcode1 !=', 8888)
			->groupBy(['s.id', 's.sub_name1', 's.subcode1'])
			->getCompiledSelect();


		$subQuery2 = $this->db->table('master.roster r')
			->select([
				"(SELECT SPLIT_PART(STRING_AGG(rj.judge_id::text, ',' ORDER BY rj.id), ',', 1)
				  FROM master.roster_judge rj 
				  WHERE rj.roster_id = r.id AND rj.display = 'Y') AS judge_id",
				'r.courtno',
				'ss.sub_name1',
				'ss.subcode1'
			])
			->join('category_allottment c', 'c.ros_id = r.id')
			->join('master.roster_judge rj', 'rj.roster_id = r.id')
			->join('master.submaster s', 's.id = c.submaster_id')
			->join('master.submaster ss', 'ss.subcode1 = s.subcode1', 'left')
			->where([
				'ss.display' => 'Y',
				'rj.display' => 'Y',
				's.display' => 'Y',
				'c.display' => 'Y',
				'r.display' => 'Y',
				'r.m_f' => '1',
				'r.from_date' => $formattedDate
			])
			->getCompiledSelect();


		$cSub = $this->db->table("($subQuery1) a")
			->select('a.*, b.courtno, b.judge_id')
			->join("($subQuery2) b", 'b.sub_name1 = a.sub_name1', 'left')
			->groupBy(['a.sub_name1', 'a.subcode1', 'a.id', 'b.courtno', 'b.judge_id'])
			->getCompiledSelect();


		$dTable = $this->db->table("($cSub) c")
			->select("
				c.sub_name1, 
				c.subcode1, 
				STRING_AGG(c.courtno::text, ',') AS cno, 
				STRING_AGG(c.judge_id::text, ',') AS judge
			")
			->groupBy(['c.sub_name1', 'c.subcode1'])
			->getCompiledSelect();

		$caseSub = $this->db->table('mul_category mc')
			->select([
				'm.diary_no',
				'h.subhead',
				'h.listorder',
				"SPLIT_PART(h.coram, ',', 1) AS coram",
				's.subcode1 AS subcd1'
			])
			->join('master.submaster s', 's.id = mc.submaster_id')
			->join('main m', 'm.diary_no = mc.diary_no')
			->join('heardt h', 'm.diary_no = h.diary_no')
			->where([
				'm.c_status' => 'P',
				'h.mainhead' => 'M',
				'h.board_type' => 'J',
				'clno' => 0,
				'brd_slno' => 0,
				'main_supp_flag' => 0,
				'h.next_dt' => $formattedDate
			])
			->groupStart()
				->where('m.diary_no = CAST(m.conn_key AS BIGINT)', null, false)
				->orWhere('m.conn_key IS NULL')
				->orWhere('m.conn_key', '')
				->orWhere('CAST(m.conn_key AS BIGINT) = 0', null, false)
			->groupEnd()
			->groupBy('h.diary_no, m.diary_no, s.subcode1, h.subhead, h.listorder, h.coram')
			->getCompiledSelect();


		$final = $this->db->table("($dTable) d")
			->select([
				"SUM(CASE WHEN (subhead IN (824,810,803,802,807,804) 
					 OR (subhead IN (824,810,803,802,807,804,811,812) 
					 AND listorder IN (4,5,7,25,32,8))) THEN 1 ELSE 0 END) AS tobe_list_all",
				"SUM(CASE WHEN subhead IN (811,812) AND listorder NOT IN (4,5,7,25,32,8) THEN 1 ELSE 0 END) AS fresh_head_cnt",
				"SUM(CASE WHEN subhead = 808 AND listorder IN (4,5,7,25,32,8) THEN 1 ELSE 0 END) AS order_cnt_fd",
				"SUM(CASE WHEN subhead = 808 AND listorder NOT IN (4,5,7,25,32,8) THEN 1 ELSE 0 END) AS order_cnt",
				"SUM(CASE WHEN subhead IN (813,814,815,816) AND listorder IN (4,5,7,25,32,8) THEN 1 ELSE 0 END) AS notice_cnt_fd",
				"SUM(CASE WHEN subhead IN (813,814,815,816) AND listorder NOT IN (4,5,7,25,32,8) THEN 1 ELSE 0 END) AS notice_cnt",
				"SUM(CASE WHEN subhead IN (824,810,803,802,807,804,808,811,812,813,814,815,816) THEN 1 ELSE 0 END) AS case_cnt",
				"d.sub_name1", "subcode1", "judge"
			])
			->join("($caseSub) t", 't.subcd1 = d.subcode1', 'left')
			->groupBy(['d.sub_name1', 'subcode1', 'judge'])
			->orderBy('d.sub_name1')
			->get()
			->getResultArray();
			
		if(!empty($final)){
			foreach($final as $k=>$vals){
				$string_array = array();
				if($vals['judge']!=''){
					$judgeCodes =  explode(',', $vals['judge']);
					$judgeCodes = array_unique($judgeCodes);
					
					$builder = $this->db->table('master.judge');
					$builder->select("STRING_AGG(abbreviation, ',' ORDER BY judge_seniority) as abr");
					$builder->whereIn('jcode', $judgeCodes);
					$builder->where('jtype', 'J');
					$builder->groupBy('jtype');

					$query = $builder->get();
					$result = $query->getRowArray();
					
					$final[$k]['roster_listing'] = isset($result['abr']) ? str_replace(",", ", ", $result['abr']) : '';
				}else{
					$final[$k]['roster_listing'] = '';
				}
			}
		}
		return $final;
	}


    public function getcatAvlCaseIndvGetReportData($list_dt, $court_no, $ucode, $data_save){
        
        $formattedDate = date('Y-m-d', strtotime($list_dt));


  $sql = "
SELECT * FROM (
    SELECT       
        SUM(CASE WHEN subhead IN (824,810,803,802,807,804) THEN 1 ELSE 0 END) AS tobe_list_all,
        SUM(CASE WHEN subhead = 808 THEN 1 ELSE 0 END) AS order_cnt,
        SUM(CASE WHEN subhead IN (811,812) AND listorder = 32 THEN 1 ELSE 0 END) AS fresh_cnt,
        SUM(CASE WHEN subhead IN (811,812) AND listorder != 32 THEN 1 ELSE 0 END) AS fresh_head_cnt,
        SUM(CASE WHEN subhead IN (813,814,815,816) THEN 1 ELSE 0 END) AS notice_cnt,       
        SUM(CASE WHEN subhead IN (824,810,803,802,807,804,808,811,812,813,814,815,816) THEN 1 ELSE 0 END) AS case_cnt,      
        d.sub_name1, d.subcode1, d.judge
    FROM (
        SELECT 
            c.id,
            c.sub_name1,
            c.subcode1,
            STRING_AGG(c.courtno::text, ',') AS cno,
            STRING_AGG(c.judge_id::text, ',') AS judge
        FROM (
            SELECT 
                a.id, 
                a.sub_name1, 
                a.subcode1, 
                b.courtno, 
                b.judge_id 
            FROM (
                SELECT s.id, s.sub_name1, s.subcode1 
                FROM master.submaster s 
                WHERE s.display = 'Y' AND s.flag = 's' AND s.subcode1 != 8888 
                GROUP BY s.id, s.sub_name1, s.subcode1
            ) a 
            LEFT JOIN (
                SELECT 
                    SPLIT_PART(STRING_AGG(rj.judge_id::text, ',' ORDER BY rj.id), ',', 1) AS judge_id,
                    r.courtno, 
                    ss.sub_name1, 
                    ss.subcode1 
                FROM master.roster r 
                INNER JOIN category_allottment c ON c.ros_id = r.id 
                INNER JOIN master.roster_judge rj ON rj.roster_id = r.id 
                INNER JOIN master.submaster s ON s.id = c.submaster_id 
                LEFT JOIN master.submaster ss ON ss.subcode1 = s.subcode1               
                WHERE ss.display = 'Y' 
                    AND rj.display = 'Y' 
                    AND s.display = 'Y' 
                    AND c.display = 'Y' 
                    AND r.display = 'Y' 
                    AND r.m_f = '1' 
                    AND r.from_date = :list_dt: 
                    AND r.courtno = :court_no:
                GROUP BY r.id, r.courtno, ss.sub_name1, ss.subcode1
            ) b ON b.sub_name1 = a.sub_name1 
            WHERE b.sub_name1 IS NOT NULL 
            GROUP BY a.id, a.sub_name1, a.subcode1, b.courtno, b.judge_id
        ) c 
        GROUP BY c.id, c.sub_name1, c.subcode1
    ) d 
    LEFT JOIN (
        SELECT 
            m.diary_no, 
            h.subhead, 
            h.listorder, 
            SPLIT_PART(h.coram, ',', 1) AS coram, 
            s.subcode1 AS subcd1 
        FROM mul_category mc
        INNER JOIN master.submaster s ON s.id = mc.submaster_id    
        INNER JOIN main m ON m.diary_no = mc.diary_no 
        INNER JOIN heardt h ON m.diary_no = h.diary_no 
        WHERE m.c_status = 'P' 
            AND (
                m.conn_key IS NULL 
                OR m.conn_key = '' 
                OR m.conn_key::bigint = 0 
                OR m.diary_no = m.conn_key::bigint
            )
            AND h.mainhead = 'M' 
            AND h.board_type = 'J' 
            AND clno = 0 
            AND brd_slno = 0 
            AND main_supp_flag = 0 
            AND h.next_dt = :list_dt:
    ) t ON t.subcd1 = d.subcode1
    GROUP BY d.sub_name1, d.subcode1, d.judge
) t 
ORDER BY sub_name1";

        $result =  $this->db->query($sql, ['list_dt' => $formattedDate, 'court_no' => $court_no])->getResultArray();
        if(!empty($result) && $data_save == "Yes"){
			$this_crt_avl = ""; $total_this_cat_ratio = "";
			foreach($result as $k=>$row){
				if(!empty($row['sub_name1'])){
                    $this_crt_avl = $row['case_cnt'];
                }
				if ($this_crt_avl != 0) {
					$this_cat_ratio = (int)$row['case_cnt'] * 60 / (float)$this_crt_avl;
				} else {
					$this_cat_ratio = 0; 
				}
				
					 $sql = "INSERT INTO master.cat_jud_ratio 
								(cat_id, cat_name, judge, next_dt, bail_top, orders, fresh, fresh_no_notice, an_fd, cnt, ratio_cnt, ent_dt, usercode)
							VALUES 
								(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)
							ON CONFLICT (cat_id, judge, next_dt) 
							DO UPDATE 
								SET bail_top = EXCLUDED.bail_top, 
									orders = EXCLUDED.orders,
									fresh = EXCLUDED.fresh,
									fresh_no_notice = EXCLUDED.fresh_no_notice,
									an_fd = EXCLUDED.an_fd,
									cnt = EXCLUDED.cnt,
									ratio_cnt = EXCLUDED.ratio_cnt,
									ent_dt = EXCLUDED.ent_dt,
									usercode = EXCLUDED.usercode;
							";
							$judge = $row['judge'] ?? '0';
							$subcode1 = $row['subcode1']??'0';
							$binds = [
								$subcode1,
								$row['sub_name1'],
								$judge,
								$formattedDate,
								$row['tobe_list_all'],
								$row['order_cnt'],
								$row['fresh_cnt'],
								$row['fresh_head_cnt'],
								$row['notice_cnt'],
								$row['case_cnt'],
								round($this_cat_ratio, 2),
								$ucode
							];
					$this->db->query($sql, $binds); 
				}
		}
				return $result;
	}
	
	function getcatAvlCaseIndvGetReportDatajudge($list_dt, $court_no){
		 $formattedDate = date('Y-m-d', strtotime($list_dt));
		$ros12 = $this->db->table('master.roster r')
					->select('jname')
					->join('master.roster_judge rj', 'rj.roster_id = r.id')
					->join('master.judge j', 'rj.judge_id = j.jcode')
					->where('r.display', 'Y')
					->where('r.m_f', '1')
					->where('r.from_date', $formattedDate)
					->where('r.courtno', $court_no)
					->where('rj.display', 'Y')
					->orderBy('j.judge_seniority')
					->limit(1)
					->get()
					->getRowArray();
			   if(!empty($ros12)){
				   return  $ros12['jname'];
			   }else{
				   return '';
			   }
	}
	

    private function isValidDate($date)
    {
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }


    //get_cause_list_week.php Convert In modal for CAUSE LIST PRINT MODULE


    public function getCasesListWeek($list_dt, $courtno)
    {
        $builder = $this->db->table('master.roster r');

        $builder->select('r.id, '
            . 'array_to_string(array_agg(j.jcode ORDER BY j.judge_seniority), \', \') AS jcd, '
            . 'array_to_string(array_agg(j.jname ORDER BY j.judge_seniority), \', \') AS jnm, '
            . 'r.courtno, '
            . 'rb.bench_no, '
            . 'mb.abbr, '
            . 'mb.board_type_mb, '
            . 'r.tot_cases, '
            . 'r.frm_time, '
            . 'r.session, '
            . 'j.judge_seniority');

        // Joins
        $builder->join('master.roster_bench rb', 'rb.id = r.bench_id', 'left');
        $builder->join('master.master_bench mb', 'mb.id = rb.bench_id', 'left');
        $builder->join('master.roster_judge rj', 'rj.roster_id = r.id', 'left');
        $builder->join('master.judge j', 'j.jcode = rj.judge_id', 'left');

        // Where conditions
        $builder->where('j.is_retired !=', 'Y');
        $builder->where('j.display', 'Y');
        $builder->where('rj.display', 'Y');
        $builder->where('rb.display', 'Y');
        $builder->where('mb.display', 'Y');
        $builder->where('r.display', 'Y');
        $builder->where('r.courtno', $courtno);
        //For Testing 
        $builder->where('r.from_date', '2017-05-09');
        $builder->where('r.m_f', '1');
        //$builder->where('r.from_date', $list_dt);
        //$builder->where('r.m_f', '2');


        // Group By
        $builder->groupBy('r.id, rb.bench_no, mb.abbr, mb.board_type_mb, r.tot_cases, r.frm_time, r.session, j.judge_seniority');

        // ORDER BY

        //$builder->orderBy("LENGTH(array_to_string(array_agg(j.jcode ORDER BY j.judge_seniority), ', '))", 'DESC');
        $builder->orderBy('r.id');
        $builder->orderBy('mb.abbr');
        $builder->orderBy('mb.board_type_mb');
        $builder->orderBy('r.tot_cases');
        $builder->orderBy('r.frm_time');
        $builder->orderBy('rb.bench_no');
        $builder->orderBy('j.judge_seniority');

        // Debugging Output
        // echo $builder->getCompiledSelect();
        // exit();

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_advocate_detailsWeekly($diary_no)
    {
        $subquery = $this->db->table('advocate a')
        ->select([
            'a.diary_no',
            'b.name',
            "STRING_AGG(
                CASE WHEN a.pet_res IN ('I', 'N') THEN '' ELSE a.adv END, '' 
                ORDER BY 
                    CASE WHEN a.pet_res IN ('I', 'N') THEN 99 ELSE 0 END ASC, 
                    a.pet_res_no ASC
            ) AS grp_adv",
            'a.pet_res',
            'a.adv_type',
            'a.pet_res_no'
        ])
        ->join('master.bar b', 'a.advocate_id = b.bar_id AND b.isdead != \'Y\'', 'left')
        ->where('a.diary_no', $diary_no)
        ->where('a.display', 'Y')
        ->groupBy(['a.diary_no', 'b.name', 'a.pet_res', 'a.adv_type', 'a.pet_res_no']);
    
    $builder = $this->db->table('(' . $subquery->getCompiledSelect() . ') a')
        ->select([
            'a.diary_no',
            "STRING_AGG(
                a.name || CASE WHEN a.pet_res = 'R' THEN a.grp_adv ELSE '' END, '' 
                ORDER BY a.adv_type DESC, a.pet_res_no ASC
            ) AS r_n",
            "STRING_AGG(
                a.name || CASE WHEN a.pet_res = 'P' THEN a.grp_adv ELSE '' END, '' 
                ORDER BY a.adv_type DESC, a.pet_res_no ASC
            ) AS p_n",
            "STRING_AGG(
                a.name || CASE WHEN a.pet_res = 'I' THEN a.grp_adv ELSE '' END, '' 
                ORDER BY a.adv_type DESC, a.pet_res_no ASC
            ) AS i_n",
            "STRING_AGG(
                a.name || CASE WHEN a.pet_res = 'N' THEN a.grp_adv ELSE '' END, '' 
                ORDER BY a.adv_type DESC, a.pet_res_no ASC
            ) AS intervenor"
        ])
        ->groupBy('a.diary_no');
    
        // Debugging Output (optional, remove in production)
        // echo $builder->getCompiledSelect();
        // exit();

        $query = $builder->get();
        return $query->getRowArray();
    }




    public function getCaseDetailsWeekly($list_dt, $list_dt_to, $mainhead, $courtno, $jcd_rp)
    {

        if ($courtno == "0") {
            $court_no = "";
        } else {
            $court_no = "AND r.courtno = '" . $courtno . "'";
        }

        $builder = $this->db->table('heardt h');

        // Select the columns as required
        $builder->select('u.name, us.section_name, h.*, l.purpose, c1.short_description, active_fil_no, m.active_reg_year, m.casetype_id, m.active_casetype_id, m.ref_agency_state_id, m.reg_no_display, 
            EXTRACT(YEAR FROM m.fil_dt) AS fil_year, m.fil_no, m.fil_dt, m.fil_no_fh, m.reg_year_fh AS fil_year_f, m.mf_active, m.pet_name, m.res_name, pno, rno, m.diary_no_rec_date');

        // Join statements
        $builder->join('main m', 'm.diary_no = h.diary_no', 'left');
        $builder->join('e_filing.casetype c1', 'active_casetype_id = c1.casecode', 'left');  // Fixing case-sensitive table name issue
        $builder->join('master.listing_purpose l', 'l.code = h.listorder', 'left');
        // $builder->join('master.roster r', 'r.id = h.roster_id AND r.display = \'Y\' AND r.from_date BETWEEN \'' . $list_dt . '\' AND \'' . $list_dt_to . '\'', 'inner');
        $builder->join('master.roster r', 'r.id = h.roster_id AND r.display = \'Y\' AND r.from_date BETWEEN \'' . $list_dt . '\' AND \'' . $list_dt_to . '\'' . ($court_no ? ' AND ' . $court_no : ''), 'inner');
        $builder->join('master.users u', 'u.usercode = m.dacode AND u.display = \'Y\'', 'left');
        $builder->join('master.usersection us', 'us.id = u.section', 'left');
        $builder->join('conct ct', 'm.diary_no = ct.diary_no AND ct.list = \'Y\'', 'left');

        // Where conditions
        $builder->where('h.next_dt BETWEEN \'' . $list_dt . '\' AND \'' . $list_dt_to . '\'');
        //For Testing
        $builder->where('h.mainhead', 'M');
        //$builder->where('h.mainhead', $mainhead);
        //AND '219' = ANY(string_to_array("h"."judges", ','))
        $builder->where("'$jcd_rp' = ANY(string_to_array(h.judges, ','))");


        $builder->where('h.clno >', 0);
        $builder->where('h.brd_slno >', 0);
        $builder->where('h.roster_id >', 0);
        $builder->where('l.display', 'Y');

        // Order by clauses
        $builder->orderBy('LENGTH(h.judges)', 'DESC');
        $builder->orderBy('h.next_dt');
        $builder->orderBy('h.brd_slno');

        // CASE WHEN expressions in order by
        $builder->orderBy("CASE WHEN h.conn_key = h.diary_no THEN '0000-00-00' ELSE '99' END", 'ASC');
        $builder->orderBy("CASE WHEN ct.ent_dt IS NOT NULL THEN ct.ent_dt ELSE '9999-12-31'::timestamp END", 'ASC'); // Use valid date for ELSE part

        // Correct the CAST for RIGHT and LEFT usage
        $builder->orderBy("CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3 FOR 4) AS INTEGER)", 'ASC');  // RIGHT equivalent
        $builder->orderBy("CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER)", 'ASC'); // LEFT equivalent

        // Debugging Output (optional, remove in production)
        // echo $builder->getCompiledSelect();
        // exit();

        // Execute the query and return the result
        $query = $builder->get();
        return $query->getResultArray();
    }


    public function get_tentative_sectionWeekly($diary_no)
    {
        $query = $this->db->query("SELECT tentative_section(?) AS section_name", [$diary_no]);
        $result = $query->getRowArray();

        return $result ? $result['section_name'] : '';
    }

    public function get_lower_court_detailsWeekly($diary_no)
    {
        $builder = $this->db->table('lowerct a')
            ->select([
                'a.lct_dec_dt',
                'a.lct_caseno',
                'a.lct_caseyear',
                'ct.short_description AS type_sname'
            ])
            ->join('master.casetype ct', 'ct.casecode = a.lct_casetype AND ct.display = \'Y\'', 'left')
            ->where('a.diary_no', $diary_no)
            ->where('a.is_order_challenged', 'Y')
            ->where('a.lw_display', 'Y')
            ->where('a.ct_code', 4)
            ->orderBy('a.lct_dec_dt');

        $query = $builder->get();
        return $query->getResultArray();
    }



    /**
     * To get Heardt Deatils by diary number
     */
    public function getHeardtDetails($diary_no){
        $builder = $this->db->table('heardt h');
        $builder->join('main m', 'm.diary_no = h.diary_no');
        $builder->where('m.diary_no', $diary_no);
        $builder->select('h.*, m.conn_key, m.lastorder');
        $sel_from_heardt = $builder->get()->getRowArray();
        return $sel_from_heardt ?: [];
    }

    public function checkLastHeardtDetails($diary_no, $sel_from_heardt) {
        $return = 0;
        //$sel_from_heardt = $this->getHeardtDetails($diary_no);
        if($sel_from_heardt){
            $builder = $this->db->table('last_heardt');
            $builder->where('diary_no', $diary_no)
                    ->where('conn_key', $sel_from_heardt['conn_key'])
                    ->where('next_dt', $sel_from_heardt['next_dt'])
                    ->where('mainhead', $sel_from_heardt['mainhead'])
                    ->where('subhead', $sel_from_heardt['subhead'])
                    ->where('clno', $sel_from_heardt['clno'])
                    ->where('brd_slno', $sel_from_heardt['brd_slno'])
                    ->where('roster_id', $sel_from_heardt['roster_id'])
                    ->where('judges', $sel_from_heardt['judges'])
                    ->where('coram', $sel_from_heardt['coram'])
                    ->where('board_type', $sel_from_heardt['board_type'])
                    ->where('usercode', $sel_from_heardt['usercode'])
                    ->where('ent_dt', $sel_from_heardt['ent_dt'])
                    ->where('module_id', $sel_from_heardt['module_id'])
                    ->where('mainhead_n', $sel_from_heardt['mainhead_n'])
                    ->where('subhead_n', $sel_from_heardt['subhead_n'])
                    ->where('main_supp_flag', $sel_from_heardt['main_supp_flag'])
                    ->where('listorder', $sel_from_heardt['listorder'])
                    ->where('tentative_cl_dt', $sel_from_heardt['tentative_cl_dt'])
                    ->where('listed_ia', $sel_from_heardt['listed_ia'])
                    ->where('sitting_judges', $sel_from_heardt['sitting_judges'])
                    ->where('list_before_remark', $sel_from_heardt['list_before_remark'])
                    ->where('is_nmd', $sel_from_heardt['is_nmd'])
                    ->where('no_of_time_deleted', $sel_from_heardt['no_of_time_deleted']);
            $query = $builder->get();
            $return = $query->getNumRows() > 0 ? 1 : 0;
        }
        return $return;
    }

    public function updateHeardtDetails($data){
        $this->db->table('heardt')
            ->set($data)
            ->where('diary_no', $data['diary_no'])
            ->update();
    }

    
    public function checkAndUpdateRemark($data, $diary_no) {
        $builder = $this->db->table("brdrem");
        $builder->select("*");
        $builder->where('diary_no', $diary_no);
        $query = $builder->get();
        
        if($query->getNumRows() == 0) {
            $data['diary_no'] = $diary_no;
            $brd = insert('brdrem', $data);
        } else {
            $brd = update('brdrem', $data, ['diary_no' => $diary_no]);
        }
    }


    public function getCoram($date, $board, $heading) {
        //$date = '2023-10-06';
        $m_f='';
        if($heading == 'M')
            $m_f=" and m_f='1' ";
        else if($heading == 'F')
            $m_f=" and m_f='2' ";
        else if($heading == 'L')
            $m_f=" and m_f='5' ";
        else if($heading == 'S')
            $m_f=" and m_f='7' ";

        $todate=" AND r.from_date = '$date' ";
        if($board=='R')
            $todate=" AND r.to_date is null ";
        
        $board_type = " AND mb.board_type_mb='$board' ";
        if($board == 'C')
            $board_type = " AND (mb.board_type_mb='C' OR mb.board_type_mb='CC') ";
        
    $judge = "SELECT r.m_f, r.id, GROUP_CONCAT(j.jcode ORDER BY j.judge_seniority) jcd, GROUP_CONCAT(CONCAT(j.first_name,' ',j.sur_name) ORDER BY j.judge_seniority) jnm, rb.bench_no, mb.abbr, r.tot_cases, mb.board_type_mb FROM master.roster r 
            LEFT JOIN master.roster_bench rb ON rb.id = r.bench_id 
            LEFT JOIN master.master_bench mb ON mb.id = rb.bench_id
            LEFT JOIN master.roster_judge rj ON rj.roster_id = r.id 
            LEFT JOIN master.judge j on j.jcode = rj.judge_id
            WHERE j.is_retired != 'Y' and j.display  = 'Y' and rj.display = 'Y' and rb.display = 'Y' and mb.display = 'Y' 
            and r.display = 'Y' $m_f $todate
            $board_type GROUP BY r.id, rb.bench_no, mb.abbr, mb.board_type_mb ORDER BY r.id";  
            $judge_rs = $this->db->query($judge)->getResultArray();
        return  $judge_rs;  
        
    }

    public function getSubheading($diary_no, $side, $heading){
        $subheading = [];

        if($heading !='F') {
            if(trim($side) == 'C')
                $stage_based_on_side = "stagecode!=811 and stagecode!=814 and stagecode!=815 ";
            else if(trim($side) =='R')
                $stage_based_on_side = "stagecode!=812 and stagecode!=813 and stagecode!=816 ";
            
            $builder = $this->db->table("master.subheading");
            $builder->select("stagecode, stagename");
            $builder->where("listtype",$heading);
            $builder->where($stage_based_on_side);
            $builder->where("display","Y");
            $builder->orderBy("stagecode");
            $query =$builder->get();

            if($query->getNumRows() >= 1) {
                $subheading = $query->getResultArray();
            }
        } else if($heading == 'F') {
            $builder = $this->db->table("mul_category as a");
            $builder->select('submaster_id, sub_name1, sub_name2,sub_name3,sub_name4');
            $builder->join('master.submaster b', 'a.submaster_id=b.id', 'left');
            $builder->where('diary_no', $diary_no);
            $builder->where('a.display', 'Y');
            $query = $builder->get();

            if ($query->getNumRows() >= 1) {
                $subheading = $query->getResultArray();
            }
        }
        return $subheading;
        
    }

    public function getHeardtCLVerify($list_dt, $mainhead, $board_type, $roster_id)
    {
        $roster_id_array = explode(',', $roster_id);
        $return = [];
        if(!empty($list_dt) && !in_array($list_dt, [-1, 0])){
            $builder = $this->db->table("heardt");
            $builder->select("CONCAT(judges, '|', roster_id) AS rsdf")
                ->where('next_dt', $list_dt)
                ->where('brd_slno >', 0)
                ->where('clno >', 0)
                ->where('mainhead', $mainhead)
                ->groupStart()
                ->where('diary_no = conn_key OR conn_key = 0')
                ->groupEnd();
                if ($board_type != "0") {
                    $builder->where('board_type', $board_type);
                }
                if ($roster_id != "0") {
                    //$builder->where('board_type', $ros_qryll);
                    $builder->whereIn('roster_id', $roster_id_array);
                }
                $builder->groupBy('roster_id, heardt.judges')
                        ->orderBy('SPLIT_PART(judges, \',\', 1)');

            $query = $builder->get();
            $return = $query->getResultArray();
        }
        return $return;
    }


    public function getJudgesDetails($roster_id)
    {   
        $return = [];
        if($roster_id) {
            $builder = $this->db->table('master.roster r');
            $builder->select('r.id, 
                STRING_AGG(j.jcode::text, \',\' ORDER BY j.judge_seniority) AS jcd, 
                STRING_AGG(j.jname, \',\' ORDER BY j.judge_seniority) AS jnm, 
                , r.courtno, rb.bench_no, mb.abbr, mb.board_type_mb, r.tot_cases, r.frm_time');

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
            $builder->groupBy('r.id,rb.bench_no,mb.abbr,mb.board_type_mb');
            $builder->orderBy('r.id', 'ASC');
            $return = $builder->get()->getRowArray();
        } 
        return $return;  
    }
}