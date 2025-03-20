<?php

namespace App\Models\Listing;

use CodeIgniter\Model;

class SingleJudgeNominate extends Model
{
    protected $table = 'master.single_judge_nominate'; // Define the table name
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'jcode',
        'day_type',
        'from_date',
        'usercode',
        'is_active',
        'updated_on',
        'update_by',
        'delete_reason',
        'entry_date'
    ];

    public function getSingleJudgeNominateDataById($id)
    {
        return $this->db->table('master.single_judge_nominate')
            ->select('*')
            ->where('id', 1)
            ->where('is_active', 1)
            ->get()
            ->getRowArray();
    }


    public function selectData($table, $inArr)
    {
        return $this->db->table($table)->where($inArr)->countAllResults() > 0;
    }



    public function getSingleJudgeNominatedData()
    {
        $builder = $this->db->table('master.single_judge_nominate sjn');
        $builder->select('sjn.id, j.jname, sjn.day_type, 
                          TO_CHAR(sjn.from_date, \'DD-MM-YYYY\') as effect_date, 
                          TO_CHAR(sjn.entry_date, \'DD-MM-YYYY HH24:MI:SS\') as created_on, 
                          u.name');
        $builder->join('master.judge j', 'sjn.jcode = j.jcode', 'inner');
        $builder->join('master.users u', 'sjn.usercode = u.usercode', 'inner');
        $builder->where('sjn.is_active', 1);
        $builder->where('sjn.to_date IS NULL');
        $builder->orderBy('j.judge_seniority', 'ASC');
        $query = $builder->get();
        return $query->getResult();
    }
    public function getSingleJudgeNominatedData1($id)
    {
        $builder = $this->db->table('master.single_judge_nominate sjn');
        $builder->select('sjn.id, sjn.jcode, j.jname, sjn.day_type, 
                      TO_CHAR(sjn.from_date, \'DD-MM-YYYY\') as effect_date, 
                      TO_CHAR(sjn.entry_date, \'DD-MM-YYYY HH24:MI:SS\') as created_on, 
                      u.name');
        $builder->join('master.judge j', 'sjn.jcode = j.jcode', 'inner');
        $builder->join('master.users u', 'sjn.usercode = u.usercode', 'inner');
        $builder->where('sjn.id', $id);
        $builder->where('sjn.is_active', 1);
        $builder->where('sjn.to_date IS NULL');


        $query = $builder->get();
        return $query->getRow();
    }
    public function updateJudge($id, $data)
    {
        $builder = $this->db->table('master.single_judge_nominate');
        $builder->where('id', $id);
        $builder->update($data);
    }



    public function insertData(array $data)
    {
        return $this->insert($data);
    }

    public function updateData($id, $data)
    {
        return $this->update($id, $data);
    }


    //Single Judge Pool start 

    public function getSingleJudgeListed($from_date, $to_date)
    {
        
        $from_date = date('Y-m-d', strtotime($from_date));
        $to_date = date('Y-m-d', strtotime($to_date));

       

        $builder = $this->db->table('advance_single_judge_allocated a')
            ->select('COUNT(DISTINCT a.diary_no) AS total')
            ->join('single_judge_advanced_drop_note ad', 'ad.diary_no = a.diary_no AND ad.from_dt = a.from_dt AND ad.to_dt = a.to_dt AND ad.display != \'N\'', 'left')
            ->where('ad.diary_no IS NULL')
            ->groupStart()
            ->where('a.diary_no = a.conn_key')
            ->orWhere('a.conn_key', 0)
            ->orWhere('a.conn_key IS NULL')
            ->groupEnd()
            ->where('a.from_dt', $from_date)
            ->where('a.to_dt', $to_date)
            ->get();
        // $sql = $this->db->getLastQuery();
        // echo $sql; die;
        $row = $builder->getRow();
        return $row ? $row->total : 0;
    }


    public function getSingleJudgePools($from_date, $to_date)
    {
        $from_date = date('Y-m-d', strtotime($from_date));
        $to_date = date('Y-m-d', strtotime($to_date));
    
        $builder = $this->db->table('main m')
            ->select('COUNT(DISTINCT m.diary_no) AS total')
            ->join('heardt h', 'h.diary_no = m.diary_no')
            ->join('mul_category mc', 'mc.diary_no = m.diary_no')
            ->join('rgo_default rd', 'rd.fil_no = h.diary_no AND rd.remove_def = \'N\'', 'left')
            ->join('advance_single_judge_allocated aa', 'aa.diary_no = h.diary_no AND aa.from_dt = \'' . $from_date . '\' AND aa.to_dt = \'' . $to_date . '\'', 'left')
            ->where('aa.diary_no IS NULL')
            ->where('m.c_status', 'P')
            ->groupStart()  
            ->where('m.diary_no = CAST(NULLIF(m.conn_key, \'\') AS bigint)', null, false)  
            ->orWhere('CAST(NULLIF(m.conn_key, \'\') AS bigint) =', 0, false)  
            ->orWhere('m.conn_key IS NULL')
            ->groupEnd()
            ->where('m.active_casetype_id !=', 9)
            ->where('m.active_casetype_id !=', 10)
            ->where('h.subhead NOT IN (801, 817, 818, 819, 820, 848, 849, 850, 854, 0)')
            ->where('m.active_casetype_id NOT IN (25, 26)')
            ->where('h.main_supp_flag', 0)
            ->where('h.mainhead', 'M')
            ->where('h.roster_id', 0)
            ->where('h.brd_slno', 0)
            ->where('h.listorder !=', 32)
            ->where('h.board_type', 'S')
            ->groupStart()  
            ->where('h.next_dt <=', $from_date)
            ->orWhere('h.next_dt BETWEEN \'' . $from_date . '\' AND \'' . $to_date . '\'')
            ->groupEnd()
            ->where('mc.display', 'Y')
            ->where('rd.fil_no IS NULL')
            ->get();
        $row = $builder->getRow();
        return $row ? $row->total : 0;
    }
    
    public function getListingPurposes()
    {
        $query = $this->db->table('master.listing_purpose')
            ->where('display', 'Y')
            ->orderBy('priority', 'ASC')
            ->get();
        return $query->getResultArray();
    }


    public function isPrinted($from_dt, $to_dt)
    {
        $builder = $this->db->table('single_judge_advance_cl_printed');
        $query = $builder->select('id')
            ->where('from_dt', $from_dt)
            ->where('to_dt', $to_dt)
            ->where('is_active', 1)
            ->get();
        return $query->getNumRows() > 0 ? 1 : 0;
    }

    // End Single Judge pool

    public function single_judge_advance_cl_printed($from_date, $to_date)
    {
        return $this->db->table('single_judge_advance_cl_printed')
            ->where('from_dt', $from_date)
            ->where('to_dt', $to_date)
            ->where('is_active', 1)
            ->countAllResults() > 0;
    }

    public function single_judge_advance_max_weekly_number($from_date)
    {
        $year = date('Y', strtotime($from_date));
        return $this->db->table('single_judge_advance_cl_printed')
            ->select('COALESCE(MAX(weekly_no), 0) AS max_weekly_number')
            ->where('weekly_year', $year)
            ->where('is_active', 1)
            ->get()
            ->getRow()->max_weekly_number;
    }

    public function single_judge_advance_max_cause_list_number($from_date,  $to_date)
    {
        return $this->db->table('advance_single_judge_allocated')
            ->select('COALESCE(MAX(brd_slno), 0) AS max_item_number')
            ->where('from_dt', $from_date)
            ->where('to_dt', $to_date)
            ->get()
            ->getRow()->max_item_number;
    }
    public function singleJudgeAdvanceAllocation($array)
    {
        // Prepare the last advance query
        $lastAdvanceBuilder = $this->db->table('single_judge_advance_cl_printed')
            ->select('from_dt, to_dt')
            ->where('from_dt <', date('Y-m-d', strtotime($array['from_date'])))
            ->where('is_active', 1)
            ->orderBy('from_dt', 'DESC')
            ->limit(1);


        $resultLastAdvance = $lastAdvanceBuilder->get();


        if ($resultLastAdvance->getNumRows() > 0) {
            $rowLastAdvance = $resultLastAdvance->getRow();
            $last_advance_sub_query = " AND aa2.from_dt >= '" . $rowLastAdvance->from_dt . "' AND aa2.to_dt <= '" . $rowLastAdvance->to_dt . "'";
        } else {
            $last_advance_sub_query = "";
        }
        $weekly_number = $array['max_weekly_number'] + 1;

        $sql_query = "INSERT INTO advance_single_judge_allocated (brd_slno,diary_no,conn_key,next_dt,from_dt,to_dt,subhead, 
                            board_type,listorder,weekly_no,weekly_year,usercode
                        )SELECT 
                                ROW_NUMBER() OVER () AS item_no, 
                            c.diary_no,c.main_key::bigint,c.next_dt,c.from_dt::date,c.to_dt::date, 
                            c.subhead,c.board_type,c.listorder,c.weekly_no::bigint,c.weekly_year::bigint,c.usercode
                            FROM (
                                SELECT 
                                    h.diary_no,h.main_key,h.next_dt, 
                                    '" . date('Y-m-d', strtotime($array['from_date'])) . "' as from_dt, '" . date('Y-m-d', strtotime($array['to_date'])) . "' as to_dt,
                                    h.subhead, 
                                    h.board_type, 
                                    h.listorder, 
                                   $weekly_number AS weekly_no, 
                                    '" . date('Y', strtotime($array['from_date'])) . "' as weekly_year,
                                    " . $array['usercode'] . " AS usercode
                                FROM (
                                    SELECT 
                                        dd.doccode1, 
                                        mc.submaster_id, 
                                        a.advocate_id, 
                                        m.conn_key AS main_key, 
                                        l.priority, 
                                        aa2.diary_no AS old_advance_no, 
                                        h.*
                                    FROM 
                                        main m
                                        INNER JOIN heardt h ON h.diary_no = m.diary_no
                                        INNER JOIN master.listing_purpose l ON l.code = h.listorder
                                        INNER JOIN mul_category mc ON mc.diary_no = m.diary_no
                                        LEFT JOIN docdetails dd ON dd.diary_no = h.diary_no 
                                            AND dd.iastat = 'P' 
                                            AND dd.doccode = 8 
                                            AND dd.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 3, 309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 102, 118, 131, 211, 309)
                                        LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no 
                                            AND rd.remove_def = 'N'
                                        LEFT JOIN advocate a ON a.diary_no = m.diary_no 
                                            AND a.advocate_id IN (584, 585, 610, 616, 666, 940) 
                                            AND a.display = 'Y'
                                        LEFT JOIN advance_single_judge_allocated aa ON aa.diary_no = h.diary_no 
                                            AND aa.from_dt = '" . date('Y-m-d', strtotime($array['from_date'])) . "'
                                            AND aa.to_dt = '" . date('Y-m-d', strtotime($array['to_date'])) . "'
                                        LEFT JOIN advance_single_judge_allocated aa2 ON aa2.diary_no = h.diary_no 
                                            AND aa2.board_type = 'S'  
                                            AND aa2.from_dt >= '" . date('Y-m-d', strtotime($array['from_date'])) . "'
                                            AND aa2.to_dt <= '" . date('Y-m-d', strtotime($array['to_date'])) . "'
                                        LEFT JOIN single_judge_advanced_drop_note adn ON adn.diary_no = aa.diary_no 
                                            AND adn.from_dt = aa.from_dt 
                                            AND adn.to_dt = aa.to_dt 
                                            AND adn.display = 'Y'    
                                    WHERE 
                                        adn.diary_no IS NULL 
                                        AND m.c_status = 'P' 
                                        AND (m.diary_no::char = m.conn_key OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL)
                                        AND m.active_casetype_id != 9 
                                        AND m.active_casetype_id != 10
                                        AND h.subhead NOT IN (801, 817, 818, 819, 820, 848, 849, 850, 854, 0)
                                        AND m.active_casetype_id NOT IN (25, 26)
                                        AND h.listorder IN (" . $array['listorder'] . ")
                                        AND h.main_supp_flag = 0
                                        AND h.mainhead = 'M' 
                                        AND h.roster_id = 0 
                                        AND h.brd_slno = 0 
                                        AND h.listorder != 32 
                                        AND h.board_type = 'S'
                                        AND (h.next_dt <=  '" . date('Y-m-d', strtotime($array['from_date'])) . "'  OR h.next_dt BETWEEN '" . date('Y-m-d', strtotime($array['from_date'])) . "' AND '" . date('Y-m-d', strtotime($array['to_date'])) . "')
                                        AND mc.display = 'Y' 
                                        AND rd.fil_no IS NULL 
                                        AND aa.diary_no IS NULL
                                    GROUP BY m.diary_no,dd.doccode1,mc.submaster_id,a.advocate_id,l.priority,aa2.diary_no,
                                    h.diary_no,
                                    h.conn_key,h.next_dt,h.mainhead,h.subhead,h.clno,h.brd_slno,h.roster_id,h.judges,h.coram,h.board_type,
                                    h.usercode,h.ent_dt,h.module_id,h.mainhead_n,h.subhead_n,h.main_supp_flag,h.listorder,h.tentative_cl_dt,h.listed_ia,
                                    h.sitting_judges,h.list_before_remark,h.coram_prev,h.is_nmd,h.no_of_time_deleted,h.descrip
                                ) h
                                ORDER BY
                                    CASE WHEN h.next_dt BETWEEN '" . date('Y-m-d', strtotime($array['from_date'])) . "' AND '" . date('Y-m-d', strtotime($array['to_date'])) . "' THEN 1 ELSE 999 END,
                                    CASE WHEN h.subhead = '824' THEN 2 ELSE 999 END,
                                    CASE WHEN h.listorder IN (4, 5, 7) THEN 2 ELSE 999 END, 
                                    CASE WHEN advocate_id IS NOT NULL THEN 3 ELSE 999 END, 
                                    CASE WHEN h.subhead IN ('804') OR doccode1 IN (40 , 41, 48, 49, 71, 72, 118, 131, 211, 309) OR submaster_id = 173 THEN 5 ELSE 999 END, 
                                    CASE WHEN h.listorder IN (25) THEN 6 ELSE 999 END, 
                                    CASE WHEN h.subhead IN ('810', '802', '803', '807') THEN 7 ELSE 999 END , 
                                    CASE WHEN doccode1 IN (56,57,102,73,99,27,124,2,16) THEN 8 ELSE 999 END, 
                                    CASE WHEN old_advance_no IS NOT NULL THEN 9 ELSE 999 END,
                                    priority,
                                    h.no_of_time_deleted DESC, 
                                    CASE WHEN (h.coram IS NOT NULL AND h.coram != '0' AND TRIM(h.coram) != '') THEN 13 ELSE 999 END,
                                CAST(SUBSTRING(h.diary_no::TEXT FROM LENGTH(h.diary_no::TEXT) - 3 FOR 4) AS INTEGER), 
                                CAST(SUBSTRING(h.diary_no::TEXT FROM 1 FOR LENGTH(h.diary_no::TEXT) - 4) AS INTEGER)
                                LIMIT " . $array['number_of_cases'] . " 
                            ) c ON CONFLICT (diary_no, from_dt, to_dt) DO NOTHING";

        //pr($sql_query);


        // $sql_query="INSERT INTO advance_single_judge_allocated (
        //                 brd_slno, diary_no, conn_key, next_dt, from_dt, to_dt, subhead, board_type, listorder, weekly_no, weekly_year, usercode
        //             )
        //             SELECT 
        //                 row_number() OVER () AS item_no,
        //                 c.diary_no,
        //                 c.main_key::bigint AS conn_key,
        //                 c.next_dt,
        //                 c.from_dt,
        //                 c.to_dt,
        //                 c.subhead,
        //                 c.board_type,
        //                 c.listorder,
        //                 c.weekly_no,
        //                 c.weekly_year::bigint,
        //                 c.usercode
        //             FROM (
        //                 SELECT 
        //                     h.diary_no, 
        //                     h.main_key, 
        //                     h.next_dt, 
        //                     '" . date('Y-m-d', strtotime($array['from_date'])) . "'::DATE AS from_dt, 
        //                     '" . date('Y-m-d', strtotime($array['to_date'])) . "'::DATE AS to_dt,
        //                     h.subhead, 
        //                     h.board_type, 
        //                     h.listorder, 
        //                     $weekly_number AS weekly_no, 
        //                     '" . date('Y', strtotime($array['from_date'])) . "' AS weekly_year,
        //                     ".$array['usercode']." AS usercode
        //                 FROM (
        //                     SELECT 
        //                         dd.doccode1, 
        //                         mc.submaster_id, 
        //                         a.advocate_id, 
        //                         m.conn_key AS main_key, 
        //                         l.priority, 
        //                         aa2.diary_no AS old_advance_no, 
        //                         h.*
        //                     FROM main m
        //                     INNER JOIN heardt h ON h.diary_no = m.diary_no
        //                     INNER JOIN master.listing_purpose l ON l.code = h.listorder
        //                     INNER JOIN mul_category mc ON mc.diary_no = m.diary_no
        //                     LEFT JOIN docdetails dd 
        //                         ON dd.diary_no = h.diary_no 
        //                         AND dd.iastat = 'P' 
        //                         AND dd.doccode = 8 
        //                         AND dd.doccode1 IN (7,66,29,56,57,28,103,133,3,309,73,99,40,48,72,71,27,124,2,16,41,49,102,118,131,211,309)
        //                     LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
        //                     LEFT JOIN advocate a 
        //                         ON a.diary_no = m.diary_no 
        //                         AND a.advocate_id IN (584,585,610,616,666,940) 
        //                         AND a.display = 'Y'
        //                     LEFT JOIN advance_single_judge_allocated aa 
        //                         ON aa.diary_no = h.diary_no 
        //                         AND aa.from_dt =  '" . date('Y-m-d', strtotime($array['from_date'])) . "'       
        //                         AND aa.to_dt = '" . date('Y-m-d', strtotime($array['to_date'])) . "' 
        //                     LEFT JOIN advance_single_judge_allocated aa2 
        //                         ON aa2.diary_no = h.diary_no 
        //                         AND aa2.board_type = 'S'  
        //                         AND aa2.from_dt >=  '" . date('Y-m-d', strtotime($array['from_date'])) . "'  
        //                         AND aa2.to_dt <= '" . date('Y-m-d', strtotime($array['to_date'])) . "'  
        //                     LEFT JOIN single_judge_advanced_drop_note adn 
        //                         ON adn.diary_no = aa.diary_no 
        //                         AND adn.from_dt = aa.from_dt 
        //                         AND adn.to_dt = aa.to_dt 
        //                         AND adn.display = 'Y'    
        //                     WHERE adn.diary_no IS NULL 
        //                         AND m.c_status = 'P' 
        //                         AND (m.diary_no::bigint = m.conn_key::bigint OR m.conn_key = '0' OR m.conn_key IS NULL) 
        //                         AND m.active_casetype_id NOT IN (9, 10, 25, 26)
        //                         AND h.subhead NOT IN (0, 801, 817, 818, 819, 820, 848, 849, 850, 854)
        //                         AND h.listorder IN (25, 7) 
        //                         AND h.main_supp_flag = 0
        //                         AND h.mainhead = 'M' 
        //                         AND h.roster_id = 0 
        //                         AND h.brd_slno = 0 
        //                         AND h.listorder != 32 
        //                         AND h.board_type = 'S'
        //                         AND (h.next_dt <=  '" . date('Y-m-d', strtotime($array['from_date'])) . "' OR h.next_dt BETWEEN  '" . date('Y-m-d', strtotime($array['from_date'])) . "' AND '" . date('Y-m-d', strtotime($array['to_date'])) . "')
        //                         AND mc.display = 'Y' 
        //                         AND rd.fil_no IS NULL 
        //                         AND aa.diary_no IS NULL
        //                     GROUP BY m.diary_no, dd.doccode1, mc.submaster_id, a.advocate_id, l.priority,aa2.diary_no, h.diary_no
        //                 ) h
        //                 ORDER BY
        //                     CASE WHEN h.next_dt BETWEEN  '" . date('Y-m-d', strtotime($array['from_date'])) . "' AND '" . date('Y-m-d', strtotime($array['to_date'])) . "' THEN 1 ELSE 999 END ASC,
        //                     CASE WHEN h.subhead = '824' THEN 2 ELSE 999 END ASC,
        //                     CASE WHEN h.listorder IN (4, 5, 7) THEN 2 ELSE 999 END ASC, 
        //                     CASE WHEN advocate_id IS NOT NULL THEN 3 ELSE 999 END ASC, 
        //                     CASE WHEN (h.subhead = '804' OR h.doccode1 IN (40, 41, 48, 49, 71, 72, 118, 131, 211, 309) OR h.submaster_id = 173) THEN 5 ELSE 999 END ASC, 
        //                     CASE WHEN h.listorder IN (25) THEN 6 ELSE 999 END ASC, 
        //                     CASE WHEN h.subhead IN ('810', '802', '803', '807') THEN 7 ELSE 999 END ASC, 
        //                     CASE WHEN h.doccode1 IN (56, 57, 102, 73, 99, 27, 124, 2, 16) THEN 8 ELSE 999 END ASC, 
        //                     CASE WHEN old_advance_no IS NOT NULL THEN 9 ELSE 999 END ASC,
        //                     h.priority ASC, 
        //                     h.no_of_time_deleted DESC, 
        //                     CASE WHEN h.coram IS NOT NULL AND h.coram != '0' AND TRIM(h.coram) != '' THEN 13 ELSE 999 END ASC, 
        //                     CAST(SUBSTRING(h.diary_no::text FROM LENGTH(h.diary_no::text) - 3) AS INTEGER) ASC, 
        //                 CAST(SUBSTRING(h.diary_no::text FROM 1 FOR LENGTH(h.diary_no::text) - 4) AS INTEGER) ASC
        //                 LIMIT 12
        //             ) c";

        log_message('debug', 'SQL Query: ' . $sql_query);
     

        // Execute the insert query
        $this->db->query($sql_query);
        $totalInserted = $this->db->affectedRows();

        return $totalInserted > 0 ? $totalInserted : 0;
    }

    public function single_judge_advance_connected_cases_allocation($array)
    {
        $sql = "INSERT INTO advance_single_judge_allocated 
                    (diary_no, conn_key, next_dt, from_dt, to_dt, subhead, board_type, brd_slno, listorder, weekly_no, weekly_year, usercode)
                    SELECT a.* 
                    FROM (
                        SELECT DISTINCT c.diary_no AS conc_diary_no,
                                        m.conn_key::bigint, h.next_dt, h.from_dt, h.to_dt, h.subhead, h.board_type, h.brd_slno, h.listorder, 
                                        h.weekly_no, h.weekly_year, h.usercode
                        FROM advance_single_judge_allocated h
                        INNER JOIN main m ON m.diary_no = h.diary_no
                        INNER JOIN conct c ON c.conn_key::text = m.conn_key
                        WHERE c.list = 'Y' 
                        AND m.c_status = 'P'
                        AND h.diary_no = h.conn_key
                        AND h.from_dt = '" . date('Y-m-d', strtotime($array['from_date'])) . "'  
                        AND h.to_dt = '" . date('Y-m-d', strtotime($array['to_date'])) . "'
                        AND m.conn_key::bigint = h.conn_key
                        AND h.board_type = 'S'
                        AND h.clno > 0
                        AND h.brd_slno > 0
                    ) a
                    INNER JOIN main m ON a.conc_diary_no = m.diary_no
                    INNER JOIN heardt h ON a.conc_diary_no = h.diary_no
                    LEFT JOIN advance_single_judge_allocated sj 
                        ON sj.diary_no = a.conc_diary_no 
                        AND sj.from_dt = '" . date('Y-m-d', strtotime($array['from_date'])) . "'
                        AND sj.to_dt = '" . date('Y-m-d', strtotime($array['to_date'])) . "'
                    WHERE sj.diary_no IS NULL 
                    AND m.c_status = 'P' 
                    AND h.next_dt IS NOT NULL 
                    AND a.conc_diary_no != a.conn_key::bigint
                    ON CONFLICT (diary_no, from_dt, to_dt) DO NOTHING";
        $query = $this->db->query($sql);
        return $query;
        //     // Construct the subquery for connected cases
        //     $subquery = $this->db->table('advance_single_judge_allocated')
        //         ->select('c.diary_no AS conc_diary_no, m.conn_key, h.next_dt, h.from_dt, h.to_dt, h.subhead, h.board_type, h.brd_slno, h.listorder, h.weekly_no, h.weekly_year, h.usercode')
        //         ->join('main m', 'm.diary_no = h.diary_no', 'inner')
        //         ->join('conct c', 'c.conn_key = m.conn_key', 'inner')
        //         ->where('c.list', 'Y')
        //         ->where('m.c_status', 'P')
        //         ->where('h.from_dt', $array['from_date'])
        //         ->where('h.to_dt', $array['to_date'])
        //         ->getCompiledSelect();

        //     // Prepare the insertion data
        //     $data = $this->db->table('(SELECT DISTINCT conc_diary_no, conn_key, next_dt, from_dt, to_dt, subhead, board_type, brd_slno, listorder, weekly_no, weekly_year, usercode
        //     FROM (' . $subquery . ') h
        //     WHERE h.diary_no IS NULL
        //     GROUP BY h.conn_key, h.subhead, h.board_type, h.brd_slno, h.listorder, h.weekly_no, h.weekly_year, h.usercode
        // ) AS data');

        //     // Insert the data into the advance_single_judge_allocated table
        //     $this->db->table('advance_single_judge_allocated')
        //         ->insertBatch($data);

        //     // Return the number of rows inserted
        //     return $this->db->affectedRows();
    }



    public function singleJudgeAdvanceClPrinted($from_date, $to_date)
    {
        if (is_null($from_date) || is_null($to_date)) {
            log_message('error', 'Received null values for dates: from_date: ' . var_export($from_date, true) . ', to_date: ' . var_export($to_date, true));
            return null;
        }

        $builder = $this->db->table('single_judge_advance_cl_printed');
        $builder->where('from_dt', $from_date);
        $builder->where('to_dt', $to_date);
        $builder->where('is_active', 1);

        $result = $builder->get();

        // echo $this->db->getLastQuery(); die; 

        if ($result->getNumRows() > 0) {
            return 1;
        } else {
            return null;
        }
    }



    public function singleJudgeAdvanceCasesSendToPool($from_date, $to_date, $usercode)
    {


        $sql_query = "INSERT INTO advance_single_judge_allocated_log (id,diary_no,conn_key,next_dt,from_dt,to_dt,subhead,board_type,clno,brd_slno, 
                        listorder,main_supp_flag,weekly_no,weekly_year,usercode,ent_dt,log_sent_by,log_sent_on
                    )
                    SELECT 
                        id,a.diary_no,a.conn_key,a.next_dt,a.from_dt,a.to_dt,a.subhead,a.board_type,a.clno,a.brd_slno,a.listorder, 
                        a.main_supp_flag,a.weekly_no,a.weekly_year,a.usercode, a.ent_dt,$usercode,NOW() 
                    FROM advance_single_judge_allocated a
                    WHERE 
                        a.from_dt = '$from_date' 
                        AND a.to_dt = '$to_date'";


        $this->db->query($sql_query);

        $sql_query = "DELETE FROM advance_single_judge_allocated where from_dt = '$from_date' and to_dt = '$to_date'";
        $query = $this->db->query($sql_query);
        $total_updated = $this->db->affectedRows();



        if ($total_updated > 0) {
            $sql_query = "UPDATE single_judge_advanced_drop_note set display = 'N' where from_dt = '$from_date' and to_dt = '$to_date'";
            $this->db->query($sql_query);

            return $total_updated;
        } else {
            return 0;
        }





        // Already code 

        // $sql = "INSERT INTO advance_single_judge_allocated_log 
        //         (diary_no, conn_key, next_dt, from_dt, to_dt, subhead, board_type, clno, brd_slno, listorder, main_supp_flag, weekly_no, weekly_year, usercode, ent_dt, log_sent_by, log_sent_on)
        //         SELECT a.diary_no, a.conn_key, a.next_dt, ?, ?, a.subhead, a.board_type, a.clno, a.brd_slno, a.listorder, a.main_supp_flag, a.weekly_no, a.weekly_year, ?, NOW(), ?, NOW()
        //         FROM advance_single_judge_allocated a
        //         WHERE a.from_dt = ? AND a.to_dt = ?";

        // $this->db->query($sql, [$fromDate, $toDate, $usercode, $usercode, $fromDate, $toDate]);

        // $affectedRows = $this->db->affectedRows();

        // // Delete from advance_single_judge_allocated
        // $builder = $this->db->table('advance_single_judge_allocated');
        // $builder->where('from_dt', $fromDate);
        // $builder->where('to_dt', $toDate);
        // $builder->delete();

        // if ($affectedRows > 0) {
        //     // Update single_judge_advanced_drop_note
        //     $builder = $this->db->table('single_judge_advanced_drop_note');
        //     $builder->set('display', 'N');
        //     $builder->where('from_dt', $fromDate);
        //     $builder->where('to_dt', $toDate);
        //     $builder->update();

        //     return $affectedRows;
        // } else {
        //     return 0;
        // }
    }


    public function getAdvanceListingDates()
    {
        $builder = $this->db->table('advance_single_judge_allocated');
        $builder->select('from_dt, to_dt');
        $builder->where('from_dt >= CURRENT_DATE OR to_dt >= CURRENT_DATE');
        $builder->groupBy(['from_dt', 'to_dt']);
        //pr($builder->getCompiledSelect());

        $query = $builder->get();
        return $query->getResultArray();
    }







    // public function getDropNotes($fromDt, $toDt)
    // {
    //     $builder = $this->db->table('single_judge_advanced_drop_note d');
    //     $builder->select('d.clno, h.next_dt AS p_next_dt, h.brd_slno AS p_brd_slno, 
    //                       COALESCE(d.nrs, \'-\') AS nrs, d.diary_no, 
    //                       CASE 
    //                          WHEN m.reg_no_display = \'\' 
    //                          THEN CONCAT(\'Diary No. \', LEFT(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT))-4), \'-\', RIGHT(CAST(m.diary_no AS TEXT), 4)) 
    //                          ELSE m.reg_no_display 
    //                       END AS case_no,
    //                       CASE 
    //                          WHEN pno = 2 THEN CONCAT(m.pet_name, \' AND ANR.\') 
    //                          WHEN pno > 2 THEN CONCAT(m.pet_name, \' AND ORS.\') 
    //                          ELSE m.pet_name 
    //                       END AS pname,
    //                       CASE 
    //                          WHEN rno = 2 THEN CONCAT(m.res_name, \' AND ANR.\') 
    //                          WHEN rno > 2 THEN CONCAT(m.res_name, \' AND ORS.\') 
    //                          ELSE m.res_name 
    //                       END AS rname');
    //     $builder->join('main m', 'm.diary_no = d.diary_no', 'inner');
    //     $builder->join('advance_single_judge_allocated h', 'h.diary_no = m.diary_no', 'inner');
    //     $builder->where('d.board_type', 'S');
    //     $builder->where('d.from_dt', $fromDt);
    //     $builder->where('d.to_dt', $toDt);
    //     $builder->where('h.from_dt', $fromDt);
    //     $builder->where('h.to_dt', $toDt);
    //     $builder->whereIn('d.display', ['Y', 'R']);
    //     $builder->orderBy('d.clno', 'ASC');
    //     $sql = $builder->getCompiledSelect();
    //    // echo $sql;die;

    //     $query = $builder->get();
    //     return $query->getResultArray();
    // }

    public function getDropNotes($fromDt, $toDt)
    {
        //pr($fromDt);2025-01-25
        //pr($toDt); 2026-01-03
        return $this->db->table('single_judge_advanced_drop_note d')
            ->select('
                d.clno, 
                h.next_dt AS p_next_dt, 
                h.brd_slno AS p_brd_slno, 
                COALESCE(d.nrs, \'-\') AS nrs, 
                d.diary_no,
                CASE 
                    WHEN m.reg_no_display = \'\' THEN 
                        \'Diary No. \' || LEFT(m.diary_no::text, LENGTH(m.diary_no::text) - 4) || \'-\' || RIGHT(m.diary_no::text, 4)
                    ELSE 
                        m.reg_no_display 
                END AS case_no,
                CASE 
                    WHEN pno = 2 THEN m.pet_name || \' AND ANR.\' 
                    WHEN pno > 2 THEN m.pet_name || \' AND ORS.\' 
                    ELSE m.pet_name 
                END AS pname,
                CASE 
                    WHEN rno = 2 THEN m.res_name || \' AND ANR.\' 
                    WHEN rno > 2 THEN m.res_name || \' AND ORS.\' 
                    ELSE m.res_name 
                END AS rname')
            ->join('main m', 'm.diary_no = d.diary_no')
            ->join('advance_single_judge_allocated h', 'h.diary_no = m.diary_no')
            ->where([
                'd.board_type' => 'S',
                'd.from_dt' => $fromDt,
                'd.to_dt' => $toDt,
                'h.from_dt' => $fromDt,
                'h.to_dt' => $toDt,
                
            ])
            ->whereIn('d.display', ['Y', 'R'])

            ->orderBy('d.clno')
            ->get()
            ->getResultArray();
    }

    public function getPadvname($diary_no)
    {
        $sql = "SELECT 
                    a.*, 
                    STRING_AGG(
                        CASE 
                            WHEN pet_res = 'R' THEN grp_adv 
                        END, ',' ORDER BY adv_type DESC, pet_res_no ASC
                    ) AS r_n,
                    STRING_AGG(
                        CASE 
                            WHEN pet_res = 'P' THEN grp_adv 
                        END, ',' ORDER BY adv_type DESC, pet_res_no ASC
                    ) AS p_n
                FROM (
                    SELECT 
                        a.diary_no, 
                        b.name, 
                        STRING_AGG(
                            COALESCE(a.adv, '')::TEXT, ',' ORDER BY pet_res ASC, adv_type DESC, pet_res_no ASC
                        ) AS grp_adv, 
                        a.pet_res, 
                        a.adv_type, 
                        pet_res_no
                    FROM advocate a
                    LEFT JOIN master.bar b 
                        ON a.advocate_id = b.bar_id 
                        AND b.isdead != 'Y'
                    WHERE 
                        a.diary_no = '$diary_no'
                        AND a.display = 'Y'
                    GROUP BY 
                        a.diary_no, b.name, a.pet_res, a.adv_type, a.pet_res_no
                ) a
                GROUP BY a.diary_no,a.name,a.grp_adv,a.pet_res,a.adv_type,a.pet_res_no";
        $query = $this->db->query($sql);
        $result = $query->getRowArray();
        return $result;
    }

    




    public function getAdvocates($diary_no)
    {
        return $this->db->table('advocate a')
            ->select('b.name, a.pet_res, a.adv_type, GROUP_CONCAT(a.adv ORDER BY pet_res ASC, adv_type DESC SEPARATOR \', \') as advocates')
            ->join('master.bar b', 'a.advocate_id = b.bar_id AND b.isdead != \'Y\'')
            ->where('a.diary_no', $diary_no)
            ->where('a.display', 'Y')
            ->groupBy('a.diary_no, b.name')
            ->orderBy('a.pet_res ASC, a.adv_type DESC')
            ->get()
            ->getResultArray();
    }


    // Single judge Print Publish


    public function getWeeklyInfo($from_dt, $to_dt)
    {
        $builder = $this->db->table('advance_single_judge_allocated');
        $builder->select('weekly_no, weekly_year');
        $builder->where('from_dt', $from_dt);
        $builder->where('to_dt', $to_dt);
        $builder->limit(1);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function getPetName($fromDate, $toDate, $boardType, $mainhead)
    {
        //convert 
        $sql = "SELECT m.relief,u.name,us.section_name, 
                h.*, l.purpose,m.active_fil_no,m.active_reg_year,m.casetype_id,m.active_casetype_id, 
                m.ref_agency_state_id,m.reg_no_display,EXTRACT(YEAR FROM m.fil_dt) AS fil_year,  
                m.fil_no,m.fil_dt,m.fil_no_fh,m.reg_year_fh AS fil_year_f,m.mf_active, m.pet_name, 
                m.res_name,pno,rno,m.if_sclsc,m.diary_no_rec_date,h2.listed_ia,s.stagename 
            FROM 
                advance_single_judge_allocated h 
            INNER JOIN 
                main m ON m.diary_no = h.diary_no
            LEFT JOIN 
                heardt h2 ON h2.diary_no = m.diary_no
            LEFT JOIN 
                master.listing_purpose l ON l.code = h.listorder
            LEFT JOIN 
                master.subheading s ON s.stagecode = h.subhead AND s.display = 'Y' AND s.listtype = '$mainhead'             
            LEFT JOIN 
                master.users u ON u.usercode = m.dacode AND u.display = 'Y'
            LEFT JOIN 
                master.usersection us ON us.id = u.section    
            LEFT JOIN 
                conct ct ON m.diary_no = ct.diary_no AND ct.list = 'Y' 
            LEFT JOIN 
                single_judge_advanced_drop_note ad ON ad.diary_no = h.diary_no AND ad.from_dt = h.from_dt AND ad.to_dt = h.to_dt AND ad.display != 'N'
            WHERE 
                ad.diary_no IS NULL 
                AND h.from_dt = '$fromDate' 
                AND h.to_dt = '$toDate' 
                AND h.board_type = '$boardType' 
                AND h.clno > 0 
                AND h.brd_slno > 0 
                AND l.display = 'Y'  
                AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)    
            GROUP BY 
                h.diary_no,m.relief,u.name,us.section_name,h.id,l.purpose,m.active_fil_no,m.active_reg_year,m.casetype_id,m.active_casetype_id,
                m.ref_agency_state_id,m.reg_no_display,m.fil_dt,m.fil_no,m.fil_no_fh,m.reg_year_fh,
                m.mf_active,m.pet_name,m.res_name,m.pno,m.rno,m.if_sclsc,m.diary_no_rec_date,h2.listed_ia,s.stagename,ct.ent_dt,m.diary_no
            ORDER BY 
                h.brd_slno, 
                CASE WHEN h.conn_key = h.diary_no THEN 1 ELSE 99 END ASC, 
                CASE WHEN ct.ent_dt IS NOT NULL THEN ct.ent_dt ELSE '999-12-31' END ASC, 
                CAST(SUBSTRING(CAST(m.diary_no AS TEXT), -4) AS INTEGER) ASC, 
                CAST(LEFT(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER) ASC";

        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }



    public function getIntervenorData($diary_no)
    {
        //convert 
        $sql = "SELECT 
                        a.*, 
                        STRING_AGG(
                            a.name || (CASE WHEN pet_res = 'R' THEN grp_adv ELSE '' END), 
                            '' ORDER BY adv_type DESC, pet_res_no ASC
                        ) AS r_n,
                        STRING_AGG(
                            a.name || (CASE WHEN pet_res = 'P' THEN grp_adv ELSE '' END), 
                            '' ORDER BY adv_type DESC, pet_res_no ASC
                        ) AS p_n,
                        STRING_AGG(
                            a.name || (CASE WHEN pet_res = 'I' THEN grp_adv ELSE '' END), 
                            '' ORDER BY adv_type DESC, pet_res_no ASC
                        ) AS i_n,
                        STRING_AGG(
                            a.name || (CASE WHEN pet_res = 'N' THEN grp_adv ELSE '' END), 
                            '' ORDER BY adv_type DESC, pet_res_no ASC
                        ) AS intervenor
                    FROM (
                        SELECT 
                            a.diary_no, 
                            b.name, 
                            STRING_AGG(
                                COALESCE(a.adv, '')::TEXT, 
                                '' ORDER BY 
                                CASE WHEN pet_res IN ('I', 'N') THEN 99 ELSE 0 END ASC, 
                                adv_type DESC, 
                                pet_res_no ASC
                            ) AS grp_adv, 
                            a.pet_res, 
                            a.adv_type, 
                            a.pet_res_no
                        FROM 
                            advocate a 
                        LEFT JOIN 
                            master.bar b 
                        ON 
                            a.advocate_id = b.bar_id 
                            AND b.isdead != 'Y'
                        WHERE 
                            a.diary_no = '$diary_no' 
                            AND a.display = 'Y'
                        GROUP BY 
                            a.diary_no, b.name, a.pet_res, a.adv_type, a.pet_res_no
                    ) a
                    GROUP BY 
                        a.diary_no, a.name,a.grp_adv,a.pet_res,a.adv_type,a.pet_res_no";
        $query = $this->db->query($sql);
        $result = $query->getRowArray();
        return $result;
    }



    public function getSingleJudgeAdvanceList($fromDate, $toDate, $boardType, $mainhead)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('advance_single_judge_allocated h');

        $builder->select('
            h.relief, h.diary_no, h.from_dt, h.to_dt, h.board_type, h.clno, h.brd_slno, h.listorder, h.subhead, h.main_supp_flag,
            u.name, us.section_name, l.purpose,
            m.active_fil_no, m.active_reg_year, m.casetype_id, m.active_casetype_id, m.ref_agency_state_id, 
            m.reg_no_display, EXTRACT(YEAR FROM m.fil_dt) AS fil_year, m.fil_no, m.fil_dt, m.fil_no_fh, m.reg_year_fh AS fil_year_f, 
            m.mf_active, m.pet_name, m.res_name, pno, rno, m.if_sclsc, m.diary_no_rec_date, h2.listed_ia, s.stagename
        ');

        $builder->join('main m', 'm.diary_no = h.diary_no', 'inner');
        $builder->join('heardt h2', 'h2.diary_no = m.diary_no', 'left');
        $builder->join('master.listing_purpose l', 'l.code = h.listorder', 'left');
        $builder->join('master.subheading s', 's.stagecode = h.subhead AND s.display = \'Y\' AND s.listtype = \'' . $mainhead . '\'', 'left');
        $builder->join('master.users u', 'u.usercode = m.dacode AND u.display = \'Y\'', 'left');
        $builder->join('master.usersection us', 'us.id = u.section', 'left');
        $builder->join('conct ct', 'm.diary_no = ct.diary_no AND ct.list = \'Y\'', 'left');
        $builder->join('single_judge_advanced_drop_note ad', 'ad.diary_no = h.diary_no AND ad.from_dt = h.from_dt AND ad.to_dt = h.to_dt AND ad.display != \'N\'', 'left');

        $builder->where('ad.diary_no IS NULL');
        $builder->where('h.from_dt', $fromDate);
        $builder->where('h.to_dt', $toDate);
        $builder->where('h.board_type', $boardType);
        $builder->where('h.clno >', 0);
        $builder->where('h.brd_slno >', 0);
        $builder->where('l.display', 'Y');
        $builder->whereIn('h.main_supp_flag', [1, 2]);
        $builder->groupBy('
            h.relief, h.diary_no, h.from_dt, h.to_dt, h.board_type, h.clno, h.brd_slno, h.listorder, h.subhead, h.main_supp_flag,
            u.name, us.section_name, l.purpose,
            m.active_fil_no, m.active_reg_year, m.casetype_id, m.active_casetype_id, m.ref_agency_state_id, 
            m.reg_no_display, EXTRACT(YEAR FROM m.fil_dt), m.fil_no, m.fil_dt, m.fil_no_fh, m.reg_year_fh, m.mf_active, m.pet_name, m.res_name, pno, rno, m.if_sclsc, m.diary_no_rec_date, h2.listed_ia, s.stagename
        ');

        // Uncomment and correct the following lines if needed
        // $builder->orderBy('h.brd_slno');
        // $builder->orderBy('CASE WHEN h.conn_key = h.diary_no THEN 1 ELSE 99 END', 'asc');
        // $builder->orderBy('COALESCE(ct.ent_dt, TIMESTAMP \'9999-12-31 23:59:59\')', 'asc');
        // $builder->orderBy('CAST(SUBSTRING(m.diary_no FROM LENGTH(m.diary_no) - 3 FOR 4) AS INTEGER)', 'asc');
        // $builder->orderBy('CAST(SUBSTRING(m.diary_no FROM 1 FOR LENGTH(m.diary_no) - 4) AS INTEGER)', 'asc');

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getTentativeSection($diary_no)
    {
        $query = $this->db->query("SELECT tentative_section(CAST($diary_no AS BIGINT)) AS section_name");
        return $query->getRowArray();
    }
    

    
    

    
    public function getDoc($diary_no)
    {
       
      
        $sql = "SELECT * FROM (
                SELECT 
                    h.diary_no, 
                    d.docnum, 
                    d.docyear, 
                    d.doccode1, 
                    (CASE WHEN dm.doccode1 = 19 THEN d.other1 ELSE dm.docdesc END) AS docdesp, 
                    d.other1, 
                    d.iastat 
                FROM heardt h
                INNER JOIN docdetails d ON d.diary_no = h.diary_no 
                INNER JOIN master.docmaster dm ON dm.doccode1 = d.doccode1 AND dm.doccode = d.doccode
                WHERE h.diary_no = '$diary_no' 
                    AND d.doccode = 8 
                    AND dm.display = 'Y' 
                    AND CAST(docnum AS TEXT) || CAST(docyear AS TEXT) = ANY(
                        STRING_TO_ARRAY(
                            REGEXP_REPLACE(
                                REGEXP_REPLACE(
                                    REGEXP_REPLACE(listed_ia, '/', '', 'g'),
                                    ' ', '', 'g'
                                ),
                                ',', ' ', 'g'
                            ), ' '
                        )
                    )
            ) a 
            WHERE docdesp != ''
            ORDER BY docdesp";
                
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

    public function getTypeSname($diary_no)
    {
        $builder = $this->db->table('lowerct a');
        $builder->select('a.lct_dec_dt, a.lct_caseno, a.lct_caseyear, ct.short_description AS type_sname');
        $builder->join('master.casetype ct', 'ct.casecode = a.lct_casetype AND ct.display = \'Y\'', 'left');
        $builder->where('a.diary_no', $diary_no);
        $builder->where('a.is_order_challenged', 'Y');
        $builder->where('a.lw_display', 'Y');
        $builder->where('ct_code', 4);
        $builder->orderBy('a.lct_dec_dt', 'DESC');

        $query = $builder->get();
        return $query->getResultArray();
    }



    public function callReshuffleFunctionSingleJudgeAdvance_old($from_dt, $to_dt, $from_cl_no)
    {
        $db = \Config\Database::connect();
        $result = 0;
        $new_no = ($from_cl_no > 0) ? ($from_cl_no - 1) : 0;
        $builder1 = $db->table('advance_single_judge_allocated h');

        // Update: Cast diary_no to text before using LENGTH and SUBSTRING
        $builder1->select("ROW_NUMBER() OVER (ORDER BY 
                    CAST(SUBSTRING(CAST(h.diary_no AS TEXT) FROM LENGTH(CAST(h.diary_no AS TEXT)) - 3 FOR 4) AS INTEGER) ASC, 
                    CAST(SUBSTRING(CAST(h.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(h.diary_no AS TEXT)) - 4) AS INTEGER) ASC) + $new_no AS serial_number, 
                    h.diary_no, 
                    h.conn_key");

        $builder1->join('main m', 'm.diary_no = h.diary_no', 'inner');
        $builder1->join('single_judge_advanced_drop_note adn', 'adn.diary_no = h.diary_no AND adn.from_dt = h.from_dt AND adn.to_dt = h.to_dt AND adn.display != \'N\'', 'left');
        $builder1->where('adn.diary_no IS NULL');
        $builder1->where('m.c_status', 'P');
        $builder1->where('h.board_type', 'S');
        $builder1->where('h.from_dt', $from_dt);
        $builder1->where('h.to_dt', $to_dt);
        $builder1->where('h.clno >', 0);
        $builder1->where('h.brd_slno >', 0);

        $subQuery = $builder1->getCompiledSelect();

        $sql1 = "WITH sub AS ($subQuery)
             UPDATE advance_single_judge_allocated h
             SET brd_slno = sub.serial_number
             FROM sub
             WHERE h.diary_no = sub.diary_no
             AND h.from_dt = ?
             AND h.to_dt = ?
             AND h.diary_no > 0";

        $query1 = $db->query($sql1, [$from_dt, $to_dt]);

        if ($query1) {
            $result = 1;
        }

        $builder2 = $db->table('advance_single_judge_allocated h');
        $builder2->select('h.conn_key, h.brd_slno');
        $builder2->join('single_judge_advanced_drop_note adn', 'adn.diary_no = h.diary_no AND adn.from_dt = h.from_dt AND adn.to_dt = h.to_dt AND adn.display != \'N\'', 'left');
        $builder2->where('adn.diary_no IS NULL');
        $builder2->where('h.from_dt', $from_dt);
        $builder2->where('h.to_dt', $to_dt);
        $builder2->where('h.board_type', 'S');
        $builder2->where('h.diary_no = h.conn_key');
        $builder2->where('h.clno >', 0);
        $builder2->where('h.brd_slno >', 0);

        $subQuery2 = $builder2->getCompiledSelect();

        $sql_conn = "WITH sub2 AS ($subQuery2)
                 UPDATE advance_single_judge_allocated h
                 SET brd_slno = sub2.brd_slno
                 FROM sub2
                 WHERE sub2.conn_key = h.conn_key
                 AND h.from_dt = ?
                 AND h.to_dt = ?";

        $db->query($sql_conn, [$from_dt, $to_dt]);

        return $result;
    }

    function callReshuffleFunctionSingleJudgeAdvance($from_dt, $to_dt, $from_cl_no)
    {
        //convert 
        $result = 0;
        if ($from_cl_no > 0) {
            $new_no = $from_cl_no - 1;
        } else {
            $new_no = 0;
        }

        $sql1 = "WITH ranked_entries AS (
                            SELECT 
                                ROW_NUMBER() OVER (
                                    ORDER BY 
                                        CAST(RIGHT(h.diary_no::text, 4) AS INTEGER) ASC, 
                                        CAST(LEFT(h.diary_no::text, LENGTH(h.diary_no::text) - 4) AS INTEGER) ASC
                                ) + $new_no AS serial_number, 
                                h.diary_no,
                                h.from_dt,
                                h.to_dt
                            FROM advance_single_judge_allocated h
                            LEFT JOIN single_judge_advanced_drop_note adn 
                                ON adn.diary_no = h.diary_no 
                                AND adn.from_dt = h.from_dt 
                                AND adn.to_dt = h.to_dt 
                                AND adn.display != 'N'
                            INNER JOIN main m 
                                ON m.diary_no = h.diary_no
                            WHERE adn.diary_no IS NULL
                            AND (m.diary_no = m.conn_key::int OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL)
                            AND m.c_status = 'P'
                            AND h.board_type = 'S'
                            AND h.from_dt = '$from_dt'
                            AND h.to_dt = '$to_dt'
                            AND h.clno > 0
                            AND h.brd_slno > 0
                        )
                        UPDATE advance_single_judge_allocated h
                        SET brd_slno = ranked_entries.serial_number
                        FROM ranked_entries
                        WHERE 
                            h.diary_no = ranked_entries.diary_no
                            AND h.from_dt = ranked_entries.from_dt
                            AND h.to_dt = ranked_entries.to_dt
                            AND h.diary_no > 0";


        $rsss = $this->db->query($sql1);
        $rsss = $this->db->affectedRows();
        if ($rsss > 0) {
            $result = 1;
        }
        $sql_conn = "UPDATE advance_single_judge_allocated h
                    SET brd_slno = a.brd_slno
                    FROM (
                        SELECT 
                            h.conn_key, 
                            h.brd_slno
                        FROM advance_single_judge_allocated h
                        LEFT JOIN single_judge_advanced_drop_note adn 
                            ON adn.diary_no = h.diary_no 
                            AND adn.from_dt = h.from_dt 
                            AND adn.to_dt = h.to_dt 
                            AND adn.display != 'N'
                        WHERE 
                            adn.diary_no IS NULL 
                            AND h.from_dt = '$from_dt'  
                            AND h.to_dt = '$to_dt'  
                            AND h.board_type = 'S'
                            AND h.diary_no = h.conn_key
                            AND h.clno > 0
                            AND h.brd_slno > 0
                    ) a
                    WHERE 
                    h.conn_key = a.conn_key
                        AND h.from_dt = '$from_dt' 
                        AND h.to_dt = '$to_dt'";
        $q = $this->db->query($sql_conn);
        return $result;
    }

    public function checkIfPrinteds($from_dt, $to_dt)
    {
        return $this->db->table('single_judge_advance_cl_printed')
            ->where('from_dt', $from_dt)
            ->where('to_dt', $to_dt)
            ->where('is_active', 1)
            ->countAllResults() > 0;
    }
    public function insertPrintedCauseList($data)
    {
        return $this->db->table('single_judge_advance_cl_printed')->insert($data);
    }


    // End Print Publish













    public function getJudgeAllocation($from_dt, $to_dt, $board_type)
    {
        return $this->db->table($this->table)
            ->select('m.relief, u.name, us.section_name, h.*, l.purpose, active_fil_no, m.active_reg_year, m.casetype_id, m.active_casetype_id, m.ref_agency_state_id, m.reg_no_display, YEAR(m.fil_dt) fil_year, m.fil_no, m.fil_dt, m.fil_no_fh, m.reg_year_fh AS fil_year_f, m.mf_active, m.pet_name, m.res_name, pno, rno, m.if_sclsc, m.diary_no_rec_date, h2.listed_ia, s.stagename')
            ->join('main m', 'm.diary_no = h.diary_no', 'inner')
            ->join('heardt h2', 'h2.diary_no = m.diary_no', 'left')
            ->join('listing_purpose l', 'l.code = h.listorder', 'left')
            ->join('subheading s', 's.stagecode = h.subhead and s.display = "Y" and s.listtype = ' . $this->db->escape($mainhead), 'left')
            ->join('users u', 'u.usercode = m.dacode AND u.display = "Y"', 'left')
            ->join('usersection us', 'us.id = u.section', 'left')
            ->join('conct ct', 'm.diary_no = ct.diary_no and ct.list = "Y"', 'left')
            ->join('single_judge_advanced_drop_note ad', 'ad.diary_no = h.diary_no and ad.from_dt = h.from_dt and ad.to_dt = h.to_dt and ad.display != "N"', 'left')
            ->where('ad.diary_no', null)
            ->where('h.from_dt', $from_dt)
            ->where('h.to_dt', $to_dt)
            ->where('h.board_type', $board_type)
            ->where('h.clno >', 0)
            ->where('h.brd_slno >', 0)
            ->where('l.display', 'Y')
            ->groupBy('h.diary_no')
            ->orderBy('h.brd_slno')
            ->orderBy('if(h.conn_key=h.diary_no,1,99)', 'ASC')
            ->orderBy('if(ct.ent_dt is not null,ct.ent_dt,999)', 'ASC')
            ->orderBy('cast(SUBSTRING(m.diary_no,-4) as signed)', 'ASC')
            ->orderBy('cast(LEFT(m.diary_no,length(m.diary_no)-4) as signed)', 'ASC')
            ->get()->getResultArray();
    }

    public function getPrintedStatus($from_dt, $to_dt)
    {
        return $this->db->table('single_judge_advance_cl_printed')
            ->where('from_dt', $from_dt)
            ->where('to_dt', $to_dt)
            ->where('is_active', 1)
            ->get()->getRow();
    }




    public function checkIfPrinted($fromDate, $to_dt)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('single_judge_advance_cl_printed');
        $builder->where('from_dt', $fromDate);
        $builder->where('to_dt', $to_dt);
        $builder->where('is_active', 1);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return false;
        }
    }

    public function getWeeklyDetails($from_dt, $to_dt)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('advance_single_judge_allocated');
        $builder->select('weekly_no, weekly_year');
        $builder->where('from_dt', $from_dt);
        $builder->where('to_dt', $to_dt);
        $builder->limit(1);
        $subQuery = $builder->getCompiledSelect();



        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return false;
        }
    }

    // Case drop function 

    public function getPetResName($dno)
    {
        // remove 
        $sql="SELECT pet_name, res_name, pno, rno, h.* 
                FROM main m 
                LEFT JOIN advance_single_judge_allocated h ON h.diary_no = m.diary_no
                LEFT JOIN single_judge_advanced_drop_note a ON a.diary_no = h.diary_no 
                AND a.display ='R' AND a.cl_date = h.next_dt 
                WHERE a.diary_no IS NULL
                AND m.diary_no = '$dno' 
                --AND m.diary_no = '185802021' 
                AND (h.from_dt >= CURRENT_DATE OR h.to_dt <= CURRENT_DATE ) 
                AND (main_supp_flag = 1 OR main_supp_flag = 2)";
                 $query = $this->db->query($sql);
                 $result = $query->getRowArray();
                 return $result;
    }



    public function checkCount($dno,$brd_slno,$from_dt, $to_dt){
        $sql="SELECT COUNT(diary_no) 
                FROM single_judge_advanced_drop_note h 
                WHERE diary_no = '$dno' 
                AND clno = '$brd_slno' 
                AND (display = 'Y' OR display = 'R')  
                AND (h.from_dt = '$from_dt' OR h.to_dt = '$to_dt')";
                $query = $this->db->query($sql);
                $result = $query->getRowArray();
                return $result;
    }

    public function advancedDropNoteIns($ucode, $next_dt, $from_dt, $to_dt, $brd_slno, $dno, $drop_rmk, $mainhead, $partno)
    {
        $next_dt = !empty($next_dt) ? date('Y-m-d', strtotime($next_dt)) : null;
        $from_dt = !empty($from_dt) ? date('Y-m-d', strtotime($from_dt)) : null;
        $to_dt = !empty($to_dt) ? date('Y-m-d', strtotime($to_dt)) : null;
        $ent_dt = date('Y-m-d H:i:s');
       
        if (is_null($next_dt) || is_null($from_dt) || is_null($to_dt)) {
            return 0;
        }

        // Prepare data for insertion
        $data = [
            'cl_date' => $next_dt,
            'from_dt' => $from_dt,
            'to_dt' => $to_dt,
            'clno' => $brd_slno,
            'diary_no' => $dno,
            'nrs' => $drop_rmk,
            'usercode' => $ucode,
            'ent_dt' => $ent_dt,
            'mf' => $mainhead,
            'part' => $partno,
            'update_user'=>'',
            'so_user'=>'',
        ];
       
        $builder = $this->db->table('single_judge_advanced_drop_note');
        $builder->insert($data);
        //echo $this->db->getLastQuery();
        if ($this->db->affectedRows() > 0) {
            return 1;
        }
       // log_message('error', "Insert failed: " . $this->db->getLastQuery() . " Error: " . $this->db->error());
        return 0;
    }



    public function fAdvanceClDropCase_old($dno, $next_dt)
    {
        if (empty($dno) || empty($next_dt)) {
            return 0;
        }

        $next_dt = date('Y-m-d', strtotime($next_dt));

        $subquery = $this->db->table('advance_single_judge_allocated h')
            ->select('h.brd_slno, h.diary_no, h.clno, h.board_type')
            ->groupStart()
            ->where('h.diary_no', $dno)
            ->orWhere('h.conn_key', $dno)
            ->groupEnd()
            ->where('h.next_dt', $next_dt)
            ->where('h.diary_no >', 0)
            ->where('h.clno >', 0)
            ->where('h.brd_slno >', 0)
            ->groupStart()
            ->where('h.main_supp_flag', 1)
            ->orWhere('h.main_supp_flag', 2)
            ->groupEnd()
            ->getCompiledSelect();

        $updateUser = 'user1';

        $sql = "INSERT INTO single_judge_advanced_drop_note 
                (cl_date, from_dt, to_dt, clno, diary_no, nrs, usercode, ent_dt, display, mf, part, board_type, update_user) 
                SELECT ?, ?, ?, h.clno, h.diary_no, 'Released', '1', NOW(), 'R', 'M', h.brd_slno, h.board_type, ? 
                FROM ($subquery) AS h";

        $this->db->query($sql, [$next_dt, $next_dt, $next_dt, $updateUser]);

        if ($this->db->affectedRows() > 0) {
            return 1;
        }

        return 0;
    }

    public function fAdvanceClDropCase1($dno, $ucode, $next_dt, $from_dt, $to_dt)
    {
        if (empty($dno)) {
            return 0;
        }

        $updateUser = $ucode;
        $soUser = $ucode;

        $subquery = $this->db->table('advance_single_judge_allocated h')
            ->select('h.brd_slno, h.diary_no, h.clno, h.board_type')
            ->groupStart()
            ->where('h.diary_no', $dno)
            ->orWhere('h.conn_key', $dno)
            ->groupEnd()
            ->where('h.next_dt', $next_dt)
            ->where('h.diary_no >', 0)
            ->where('h.clno >', 0)
            ->where('h.brd_slno >', 0)
            ->groupStart()
            ->where('h.main_supp_flag', 1)
            ->orWhere('h.main_supp_flag', 2)
            ->groupEnd()
            ->getCompiledSelect();

        $sql = "INSERT INTO single_judge_advanced_drop_note 
                (cl_date, from_dt, to_dt, clno, diary_no, nrs, usercode, ent_dt, display, mf, part, board_type, update_user, so_user) 
                SELECT ?, ?, ?, h.brd_slno, h.diary_no, 'Released before advance list printed', ?, NOW(), 'R', 'M', h.clno, h.board_type, ?, ?
                FROM ($subquery) AS h";

        $this->db->query($sql, [$next_dt, $from_dt, $to_dt, $ucode, $updateUser, $soUser]);

        return $this->db->affectedRows() > 0 ? 1 : 0;
    }
    public function fAdvanceClDropCase($dno, $ucode, $next_dt, $from_dt, $to_dt)
    {
        if (empty($dno) || $dno == 0) {
            return 0;
        }
    
        $sql = "INSERT INTO single_judge_advanced_drop_note 
                (cl_date, from_dt, to_dt, clno, diary_no, nrs, usercode, ent_dt, display, mf, part, board_type, update_user,so_user)  
                SELECT next_dt, from_dt, to_dt, brd_slno, diary_no, 
                       'Released before advance list printed', ? AS usercode, NOW(), 'R', 'M', clno, h.board_type, '',''
                FROM advance_single_judge_allocated h
                WHERE (h.conn_key = ? AND (h.diary_no = ? OR h.conn_key = ?)) 
                      OR (h.diary_no = ?)
                      AND h.next_dt = ? 
                      AND h.from_dt = ? 
                      AND h.to_dt = ?
                      AND h.diary_no > 0 
                      AND h.clno > 0 
                      AND h.brd_slno > 0 
                      AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)";
    
        $this->db->query($sql, [$ucode, $dno, $dno, $dno, $dno, $next_dt, $from_dt, $to_dt]);
    
        return $this->db->affectedRows() > 0 ? 1 : 0;
    }
}
