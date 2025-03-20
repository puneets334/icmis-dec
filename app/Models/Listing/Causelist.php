<?php

namespace App\Models\Listing;

use CodeIgniter\Model;

class Causelist extends Model
{
    protected $DBGroup = 'default';
    protected $table      = 'master.single_judge_nominate';




    public function dateWise($clDate)
    {
        //remove vkg

        //     $builder = $this->db->table('master.sc_working_days wd');
        //     $subQuery = $this->db->table('heardt h')
        //         ->select('
        //         h.next_dt,
        //         h.coram,
        //         j.abbreviation,
        //         j.judge_seniority,
        //         h.diary_no,
        //         h.listorder,
        //         CAST(h.inperson AS integer) AS inperson,
        //         CAST(h.bail AS integer) AS bail,
        //         CAST(h.schm AS integer) AS schm,
        //         h.subhead,
        //         j.is_retired,
        //         j.jtype,
        //         h.clno,
        //         h.board_type,
        //         h.mainhead,
        //         h.main_supp_flag
        //     ')
        //         ->join('master.judge j', 'j.jcode = CAST(split_part(h.coram, \',\', 1) AS integer)', 'LEFT')
        //         ->where('h.is_nmd', 'N');
        //     $subQueryString = $subQuery->getCompiledSelect();
        //     $builder->select('
        //     th.next_dt,
        //     th.coram,
        //     CASE 
        //         WHEN (th.is_retired = \'Y\' OR th.jtype = \'R\') THEN \'others\'
        //         ELSE COALESCE(th.abbreviation, \'Blank\')
        //     END AS abbreviation,
        //     th.judge_seniority,
        //     COUNT(th.diary_no) AS not_listed,
        //     SUM(CASE WHEN (th.listorder IN (4, 5, 7)) THEN 1 ELSE 0 END) AS fd_not_list,
        //     SUM(CASE WHEN (th.listorder IN (32, 25)) THEN 1 ELSE 0 END) AS frs_adj_not_list,
        //     SUM(CASE WHEN (CAST(th.inperson AS integer) != 1 AND CAST(th.bail AS integer) != 1 AND CAST(th.listorder AS integer) = 8) THEN 1 ELSE 0 END) AS aw_not_list,
        //     SUM(CASE WHEN (CAST(th.inperson AS integer) != 1 AND CAST(th.bail AS integer) != 1 AND CAST(th.listorder AS integer) = 48) THEN 1 ELSE 0 END) AS nradj_not_list,
        //     SUM(CASE WHEN (CAST(th.inperson AS integer) = 1 AND CAST(th.bail AS integer) != 1 AND CAST(th.schm AS integer) != 1 AND CAST(th.listorder AS integer) NOT IN (4, 5, 7, 25, 32)) THEN 1 ELSE 0 END) AS inperson_not_list,
        //     SUM(CASE WHEN (CAST(th.bail AS integer) = 1 AND CAST(th.inperson AS integer) != 1 AND CAST(th.schm AS integer) != 1 AND CAST(th.listorder AS integer) NOT IN (4, 5, 7, 25, 32)) THEN 1 ELSE 0 END) AS bail_not_list,
        //     SUM(CASE WHEN (CAST(th.schm AS integer) = 1 AND CAST(th.inperson AS integer) != 1 AND CAST(th.bail AS integer) != 1 AND CAST(th.listorder AS integer) NOT IN (4, 5, 7, 25, 32, 8, 48)) THEN 1 ELSE 0 END) AS imp_ia_not_list,
        //     SUM(CASE WHEN (th.subhead IN (813, 814) AND CAST(th.inperson AS integer) = 0 AND CAST(th.bail AS integer) = 0 AND CAST(th.schm AS integer) = 0 AND CAST(th.listorder AS integer) NOT IN (48, 8, 4, 5, 7, 25, 32)) THEN 1 ELSE 0 END) AS notice_not_list,
        //     SUM(CASE WHEN (th.subhead IN (815, 816) AND CAST(th.inperson AS integer) = 0 AND CAST(th.bail AS integer) = 0 AND CAST(th.schm AS integer) = 0 AND CAST(th.listorder AS integer) NOT IN (48, 8, 4, 5, 7, 25, 32)) THEN 1 ELSE 0 END) AS fdisp_not_list,
        //     SUM(CASE WHEN (th.subhead NOT IN (813, 814, 815, 816) AND CAST(th.inperson AS integer) = 0 AND CAST(th.bail AS integer) = 0 AND CAST(th.schm AS integer) = 0 AND CAST(th.listorder AS integer) NOT IN (48, 8, 4, 5, 7, 25, 32)) THEN 1 ELSE 0 END) AS oth_not_list
        // ');
        //     $builder->join("($subQueryString) th", 'th.next_dt = wd.working_date', 'LEFT');

        //     // Applying WHERE conditions
        //     $builder->where('wd.display', 'Y');
        //     $builder->where('wd.is_holiday', 0);
        //     $builder->where('wd.is_nmd', 0);
        //     $builder->where('wd.working_date >= CURRENT_DATE');
        //     $builder->where('th.next_dt >= CURRENT_DATE');
        //     $builder->where('th.board_type', 'J');
        //     $builder->where('th.mainhead', 'M');
        //     $builder->where('th.clno', 0);
        //     // $builder->where('th.brd_slno', 0);
        //     $builder->where('th.main_supp_flag', 0);
        //     $builder->whereIn('th.subhead', [824, 810, 803, 802, 807, 804, 808, 811, 812, 813, 814, 815, 816]);


        //     $builder->where('wd.working_date', $clDate);
        //     $builder->groupBy('th.next_dt, th.coram, th.abbreviation, th.judge_seniority, th.is_retired, th.jtype');
        //     $builder->orderBy('th.judge_seniority', 'DESC');
        //     echo $builder->getCompiledSelect();die;

        //     $query = $builder->get();

        $sql2 = "SELECT 
                    next_dt,
                    coram,
                    abbreviation,
                    judge_seniority,
                    COUNT(th.diary_no) AS not_listed,
                    SUM(CASE WHEN (th.listorder = 4 OR th.listorder = 5 OR th.listorder = 7) THEN 1 ELSE 0 END) AS fd_not_list,
                    SUM(CASE WHEN (th.listorder = 32 OR th.listorder = 25) THEN 1 ELSE 0 END) AS frs_adj_not_list,
                    SUM(CASE WHEN (inperson != 1 AND bail != 1 AND th.listorder = 8) THEN 1 ELSE 0 END) AS aw_not_list,
                    SUM(CASE WHEN (inperson != 1 AND bail != 1 AND th.listorder = 48) THEN 1 ELSE 0 END) AS nradj_not_list,
                    SUM(CASE WHEN inperson = 1 AND bail != 1 AND schm != 1 AND th.listorder NOT IN (4, 5, 7, 25, 32) THEN 1 ELSE 0 END) AS inperson_not_list,
                    SUM(CASE WHEN bail = 1 AND inperson != 1 AND schm != 1 AND th.listorder NOT IN (4, 5, 7, 25, 32) THEN 1 ELSE 0 END) AS bail_not_list,
                    SUM(CASE WHEN schm = 1 AND inperson != 1 AND bail != 1 AND th.listorder NOT IN (4, 5, 7, 25, 32, 8, 48) THEN 1 ELSE 0 END) AS imp_ia_not_list,
                    SUM(CASE WHEN subhead IN (813, 814) AND inperson = 0 AND bail = 0 AND schm = 0 AND th.listorder NOT IN (48, 8, 4, 5, 7, 25, 32) THEN 1 ELSE 0 END) AS notice_not_list,
                    SUM(CASE WHEN subhead IN (815, 816) AND inperson = 0 AND bail = 0 AND schm = 0 AND th.listorder NOT IN (48, 8, 4, 5, 7, 25, 32) THEN 1 ELSE 0 END) AS fdisp_not_list,
                    SUM(CASE WHEN subhead NOT IN (813, 814, 815, 816) AND inperson = 0 AND bail = 0 AND schm = 0 AND th.listorder NOT IN (48, 8, 4, 5, 7, 25, 32) THEN 1 ELSE 0 END) AS oth_not_list
                        FROM (
                            SELECT 
                                next_dt,
                                h.coram,
                                CASE 
                                    WHEN (is_retired = 'Y' OR jtype = 'R') THEN 'others' 
                                    ELSE COALESCE(abbreviation, 'Blank') 
                                END AS abbreviation,
                                judge_seniority,
                                h.subhead,
                                d.doccode1,
                                mc.submaster_id,
                                h.listorder,
                                h.diary_no,
                                CASE 
                                    WHEN (h.subhead = 804 OR mc.submaster_id = 173 OR doccode1 IN (40, 41, 48, 49, 71, 72, 118, 131, 211, 309)) THEN 1 
                                    ELSE 0 
                                END AS bail,
                                CASE 
                                    WHEN a.advocate_id = 584 THEN 1 
                                    ELSE 0 
                                END AS inperson,
                                CASE 
                                    WHEN d.doccode1 IN (7, 66, 29, 56, 57, 28, 102, 103, 133, 226, 3, 73, 99, 27, 124, 2, 16) THEN 1 
                                    ELSE 0 
                                END AS schm
                            FROM 
                                master.sc_working_days wd
                            LEFT JOIN heardt h ON h.next_dt = wd.working_date
                            INNER JOIN main m ON m.diary_no = h.diary_no
                            LEFT JOIN docdetails d ON d.diary_no = m.diary_no 
                                AND d.display = 'Y' 
                                AND d.iastat = 'P' 
                                AND d.doccode = 8 
                                AND d.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 226, 3, 309, 73, 99,
                                                40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 71, 72, 102, 118, 131, 211, 309)
                            LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no 
                                AND mc.display = 'Y' 
                                AND mc.submaster_id NOT IN (911, 912, 914, 240, 241, 242, 243, 331, 9999)
                            LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no 
                                AND rd.remove_def = 'N'
                            LEFT JOIN advocate a ON a.diary_no = m.diary_no 
                                AND a.advocate_id = 584 
                                AND a.display = 'Y'
                            LEFT JOIN master.judge j ON j.jcode::text = SPLIT_PART(h.coram, ',', 1)
                            WHERE 
                                rd.fil_no IS NULL 
                                AND mc.diary_no IS NOT NULL 
                                AND m.c_status = 'P'
                                AND (m.diary_no::text = m.conn_key OR m.conn_key IS NULL OR m.conn_key = '' OR m.conn_key = '0')
                                AND h.is_nmd = 'N'
                                AND mc.submaster_id NOT IN (343, 15, 16, 17, 18, 19, 20, 21, 22, 23, 341, 353, 157, 158, 159, 160, 161, 162, 163, 166, 173, 175, 176, 322, 222)
                                AND wd.display = 'Y' 
                                AND wd.is_holiday = 0 
                                AND wd.is_nmd = 0 
                                AND wd.working_date >= CURRENT_DATE
                                AND next_dt >= CURRENT_DATE
                                AND h.board_type = 'J'
                                AND h.mainhead = 'M'
                                AND h.clno = 0
                                AND h.brd_slno = 0
                                AND h.main_supp_flag = 0
                                AND h.subhead IN (824, 810, 803, 802, 807, 804, 808, 811, 812, 813, 814, 815, 816)
                            GROUP BY h.diary_no,j.is_retired,j.jtype,j.abbreviation,j.judge_seniority,d.doccode1,mc.submaster_id,a.advocate_id
                        ) th
                        WHERE th.next_dt = '$clDate'
                        GROUP BY abbreviation,th.next_dt,th.coram,th.judge_seniority
                        ORDER BY judge_seniority DESC";


        $query = $this->db->query($sql2);
        $result = $query->getResultArray();
        return $result;
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

    public function judgeWise($clDate, $judge)
    {
        // Subquery construction
        // $subQuery = $this->db->table('heardt h')
        //     ->select('
        //         h.next_dt,
        //         h.coram,
        //         j.jname,
        //         CASE 
        //             WHEN (j.is_retired = \'Y\' OR j.jtype = \'R\') THEN \'others\'
        //             ELSE COALESCE(j.abbreviation, \'Blank\')
        //         END AS abbreviation,
        //         j.judge_seniority,
        //         h.subhead,
        //         d.doccode1,
        //         mc.submaster_id,
        //         h.listorder,
        //         h.diary_no,
        //         CASE 
        //             WHEN (h.subhead = 804 OR mc.submaster_id = 173 OR d.doccode1 IN (40, 41, 48, 49, 71, 72, 118, 131, 211, 309)) THEN 1
        //             ELSE 0 
        //         END AS bail,
        //         CASE 
        //             WHEN (a.advocate_id = 584) THEN 1 
        //             ELSE 0 
        //         END AS inperson,
        //         CASE 
        //             WHEN (d.doccode1 IN (7, 66, 29, 56, 57, 28, 102, 103, 133, 226, 3, 73, 99, 27, 124, 2, 16)) THEN 1 
        //             ELSE 0 
        //         END AS schm
        //     ')
        //     ->join('main m', 'm.diary_no = h.diary_no')
        //     ->join('docdetails d', 'd.diary_no = m.diary_no AND d.display = \'Y\' AND d.iastat = \'P\' AND d.doccode = 8', 'LEFT')
        //     ->join('mul_category mc', 'mc.diary_no = h.diary_no AND mc.display = \'Y\' AND mc.submaster_id NOT IN (911, 912, 914, 240, 241, 242, 243, 331, 9999)', 'LEFT')
        //     ->join('rgo_default rd', 'rd.fil_no = h.diary_no AND rd.remove_def = \'N\'', 'LEFT')
        //     ->join('advocate a', 'a.diary_no = m.diary_no AND a.advocate_id = 584 AND a.display = \'Y\'', 'LEFT')
        //     ->join('master.judge j', 'j.jcode = CAST(split_part(h.coram, \',\', 1) AS integer)', 'LEFT')
        //     ->join('master.sc_working_days wd', 'wd.working_date = h.next_dt', 'LEFT')
        //     ->where('rd.fil_no IS NULL')
        //     ->where('mc.diary_no IS NOT NULL')
        //     ->where('m.c_status', 'P')
        //     ->where('h.is_nmd', 'N')
        //     ->where('mc.submaster_id NOT IN (343, 15, 16, 17, 18, 19, 20, 21, 22, 23, 341, 353, 157, 158, 159, 160, 161, 162, 163, 166, 173, 175, 176, 322, 222)')
        //     ->where('wd.display', 'Y')
        //     ->where('wd.is_holiday', 0)
        //     ->where('wd.is_nmd', 0)
        //     ->where('wd.working_date >= CURRENT_DATE')
        //     ->where('h.next_dt >= CURRENT_DATE')
        //     ->where('h.board_type', 'J')
        //     ->where('h.mainhead', 'M')
        //     ->where('h.clno', 0)
        //     ->where('h.brd_slno', 0)
        //     ->where('h.main_supp_flag', 0)
        //     ->whereIn('h.subhead', [824, 810, 803, 802, 807, 804, 808, 811, 812, 813, 814, 815, 816]);

        // // Compile subquery
        // $subQueryString = $subQuery->getCompiledSelect();

        // // Main query using the subquery as a derived table
        // $builder = $this->db->table("($subQueryString) AS th");

        // // Main query selections
        // $builder->select('
        //     th.next_dt,
        //     th.coram,
        //     th.jname,
        //     th.abbreviation,
        //     th.judge_seniority,
        //     COUNT(th.diary_no) AS not_listed,
        //     SUM(CASE WHEN (th.listorder IN (4, 5, 7)) THEN 1 ELSE 0 END) AS fd_not_list,
        //     SUM(CASE WHEN (th.listorder IN (32, 25)) THEN 1 ELSE 0 END) AS frs_adj_not_list,
        //     SUM(CASE WHEN (th.inperson != 1 AND th.bail != 1 AND th.listorder = 8) THEN 1 ELSE 0 END) AS aw_not_list,
        //     SUM(CASE WHEN (th.inperson != 1 AND th.bail != 1 AND th.listorder = 48) THEN 1 ELSE 0 END) AS nradj_not_list,
        //     SUM(CASE WHEN (th.inperson = 1 AND th.bail != 1 AND th.schm != 1 AND th.listorder NOT IN (4, 5, 7, 25, 32)) THEN 1 ELSE 0 END) AS inperson_not_list,
        //     SUM(CASE WHEN (th.bail = 1 AND th.inperson != 1 AND th.schm != 1 AND th.listorder NOT IN (4, 5, 7, 25, 32)) THEN 1 ELSE 0 END) AS bail_not_list,
        //     SUM(CASE WHEN (th.schm = 1 AND th.inperson != 1 AND th.bail != 1 AND th.listorder NOT IN (4, 5, 7, 25, 32)) THEN 1 ELSE 0 END) AS imp_ia_not_list,
        //     SUM(CASE WHEN (th.subhead IN (813, 814) AND th.inperson = 0 AND th.bail = 0 AND th.schm = 0 AND th.listorder NOT IN (48, 8, 4, 5, 7, 25, 32)) THEN 1 ELSE 0 END) AS notice_not_list,
        //     SUM(CASE WHEN (th.subhead IN (815, 816) AND th.inperson = 0 AND th.bail = 0 AND th.schm = 0 AND th.listorder NOT IN (48, 8, 4, 5, 7, 25, 32)) THEN 1 ELSE 0 END) AS fdisp_not_list,
        //     SUM(CASE WHEN (th.subhead NOT IN (813, 814, 815, 816) AND th.inperson = 0 AND th.bail = 0 AND th.schm = 0 AND th.listorder NOT IN (48, 8, 4, 5, 7, 25, 32)) THEN 1 ELSE 0 END) AS oth_not_list
        // ');


        // $builder->where('th.next_dt >', $clDate);
        // $builder->where("CAST(split_part(th.coram, ',', 1) AS TEXT)", '$judge');


        // $builder->groupBy('th.next_dt, th.coram, th.jname, th.abbreviation, th.judge_seniority')
        //     ->orderBy('th.next_dt');

        //     $sqlquery= $builder->getCompiledSelect();
        //     pr($sqlquery);die;
        // $query = $builder->get();
        // return $query->getResultArray();
        
        $sql = "SELECT 
                    next_dt::TEXT,
                    coram,
                    jname,
                    abbreviation,
                    judge_seniority,
                    COUNT(th.diary_no) AS not_listed,
                    SUM(CASE WHEN th.listorder IN (4, 5, 7) THEN 1 ELSE 0 END) AS fd_not_list,
                    SUM(CASE WHEN th.listorder IN (32, 25) THEN 1 ELSE 0 END) AS frs_adj_not_list,
                    SUM(CASE WHEN inperson <> 1 AND bail <> 1 AND th.listorder = 8 THEN 1 ELSE 0 END) AS aw_not_list,
                    SUM(CASE WHEN inperson <> 1 AND bail <> 1 AND th.listorder = 48 THEN 1 ELSE 0 END) AS nradj_not_list,
                    SUM(CASE WHEN inperson = 1 AND bail <> 1 AND schm <> 1 AND th.listorder NOT IN (4, 5, 7, 25, 32) THEN 1 ELSE 0 END) AS inperson_not_list,
                    SUM(CASE WHEN bail = 1 AND inperson <> 1 AND schm <> 1 AND th.listorder NOT IN (4, 5, 7, 25, 32) THEN 1 ELSE 0 END) AS bail_not_list,
                    SUM(CASE WHEN schm = 1 AND inperson <> 1 AND bail <> 1 AND th.listorder NOT IN (4, 5, 7, 25, 32, 8, 48) THEN 1 ELSE 0 END) AS imp_ia_not_list,
                    SUM(CASE WHEN subhead IN (813, 814) AND inperson = 0 AND bail = 0 AND schm = 0 AND th.listorder NOT IN (48, 8, 4, 5, 7, 25, 32) THEN 1 ELSE 0 END) AS notice_not_list,
                    SUM(CASE WHEN subhead IN (815, 816) AND inperson = 0 AND bail = 0 AND schm = 0 AND th.listorder NOT IN (48, 8, 4, 5, 7, 25, 32) THEN 1 ELSE 0 END) AS fdisp_not_list,
                    SUM(CASE WHEN subhead NOT IN (813, 814, 815, 816) AND inperson = 0 AND bail = 0 AND schm = 0 AND th.listorder NOT IN (48, 8, 4, 5, 7, 25, 32) THEN 1 ELSE 0 END) AS oth_not_list
                    FROM (
                        SELECT 
                            next_dt, 
                            h.coram, 
                            jname, 
                            CASE 
                                WHEN is_retired = 'Y' OR jtype = 'R' THEN 'others' 
                                ELSE COALESCE(abbreviation, 'Blank') 
                            END AS abbreviation,
                            judge_seniority, 
                            h.subhead, 
                            d.doccode1, 
                            mc.submaster_id, 
                            h.listorder, 
                            h.diary_no, 
                            CASE 
                                WHEN h.subhead = 804 OR mc.submaster_id = 173 OR d.doccode1 IN (40, 41, 48, 49, 71, 72, 118, 131, 211, 309) THEN 1 
                                ELSE 0 
                            END AS bail,
                            CASE 
                                WHEN a.advocate_id = 584 THEN 1 
                                ELSE 0 
                            END AS inperson,
                            CASE 
                                WHEN d.doccode1 IN (7, 66, 29, 56, 57, 28, 102, 103, 133, 226, 3, 73, 99, 27, 124, 2, 16) THEN 1 
                                ELSE 0 
                            END AS schm
                            FROM 
                            master.sc_working_days wd
                            LEFT JOIN public.heardt h ON h.next_dt = wd.working_date
                            INNER JOIN main m ON m.diary_no = h.diary_no
                            LEFT JOIN docdetails d ON d.diary_no = m.diary_no 
                                AND d.display = 'Y' 
                                AND d.iastat = 'P' 
                                AND d.doccode = 8 
                                AND d.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 226, 3, 309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 71, 72, 102, 118, 131, 211, 309)
                            LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no 
                                AND mc.display = 'Y' 
                                AND mc.submaster_id NOT IN (911, 912, 914, 240, 241, 242, 243, 331, 9999)
                            LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no 
                                AND rd.remove_def = 'N'
                            LEFT JOIN advocate a ON a.diary_no = m.diary_no 
                                AND a.advocate_id = 584 
                                AND a.display = 'Y'
                            LEFT JOIN master.judge j ON j.jcode = SPLIT_PART(h.coram, ',', 1)::INTEGER
                            WHERE 
                            rd.fil_no IS NULL
                            AND mc.diary_no IS NOT NULL
                            AND m.c_status = 'P'
                            AND (m.diary_no::text= m.conn_key OR m.conn_key IS NULL OR m.conn_key = '' OR m.conn_key = '0')
                            AND h.is_nmd = 'N'
                            AND mc.submaster_id NOT IN (343, 15, 16, 17, 18, 19, 20, 21, 22, 23, 341, 353, 157, 158, 159, 160, 161, 162, 163, 166, 173, 175, 176, 322, 222)
                            AND wd.display = 'Y'
                            AND wd.is_holiday = 0
                            AND wd.is_nmd = 0
                            AND wd.working_date >= CURRENT_DATE
                            AND next_dt >= CURRENT_DATE
                            AND h.board_type = 'J'
                            AND h.mainhead = 'M'
                            AND h.clno = 0
                            AND h.brd_slno = 0
                            AND h.main_supp_flag = 0
                            AND h.subhead IN (824, 810, 803, 802, 807, 804, 808, 811, 812, 813, 814, 815, 816)
                        GROUP BY h.diary_no, next_dt, h.coram, jname, abbreviation, judge_seniority, h.subhead, d.doccode1, mc.submaster_id, h.listorder
                    ,j.is_retired,j.jtype,a.advocate_id
                    ) th
                    WHERE 
                  th.next_dt > '$clDate' AND 
                        SPLIT_PART(th.coram, ',', 1)::TEXT = '$judge'

                    GROUP BY next_dt, coram, jname, abbreviation, judge_seniority
                    ORDER BY next_dt";
                    $query = $this->db->query($sql);
                    return $query->getResultArray();
    }
}
