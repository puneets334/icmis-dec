<?php

namespace App\Models\Listing;

use CodeIgniter\Model;

class AdvanceAllocated extends Model
{
    protected $table = 'advance_allocated';
    //protected $primaryKey = 'diary_no';
    // protected $allowedFields = ['fil_no', 'fil_dt', 'lastorder', 'pet_name', 'res_name', 'c_status'];
    protected $db;
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
        ini_set('memory_limit', '4024M');
    }
    public function getUpcomingDates()
    {
        return $this->select('next_dt')
            ->where('next_dt >=', date('Y-m-d'))
            ->groupBy('next_dt')
            ->findAll();
    }

    public function reshuffleAdvance_OLD($listing_dt, $board_type, $from_cl_no)
    {
        $result = false;

        $new_no = $from_cl_no > 0 ? $from_cl_no - 1 : 0;
        $db = \Config\Database::connect();

        // First query: Re-arrange the `brd_slno` numbers
        $builder1 = $db->table('advance_allocated');
        $builder1->select("ROW_NUMBER() OVER (ORDER BY 
                        CAST(SUBSTRING(CAST(advance_allocated.diary_no AS TEXT) FROM LENGTH(CAST(advance_allocated.diary_no AS TEXT)) - 3 FOR 4) AS INTEGER) ASC, 
                        CAST(SUBSTRING(CAST(advance_allocated.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(advance_allocated.diary_no AS TEXT)) - 4) AS INTEGER) ASC) + $new_no AS serial_number, 
                        advance_allocated.diary_no");

        $builder1->join('main', 'CAST(main.diary_no AS TEXT) = CAST(advance_allocated.diary_no AS TEXT)', 'inner');
        $builder1->join('advanced_drop_note', 'advanced_drop_note.diary_no = advance_allocated.diary_no AND advanced_drop_note.cl_date = advance_allocated.next_dt', 'left');
        $builder1->where('advanced_drop_note.diary_no IS NULL');
        $builder1->where('main.c_status', 'P');
        $builder1->where('advance_allocated.board_type', $board_type);
        $builder1->where('advance_allocated.next_dt', $listing_dt);
        $builder1->where('CAST(advance_allocated.clno AS INTEGER) >', 0);
        $builder1->where('CAST(advance_allocated.brd_slno AS INTEGER) >', 0);

        $subQuery = $builder1->getCompiledSelect();

        // Updated query without alias for the target table in PostgreSQL
        $sql1 = "WITH sub AS ($subQuery)
                 UPDATE advance_allocated
                 SET brd_slno = sub.serial_number
                 FROM sub
                 WHERE CAST(advance_allocated.diary_no AS TEXT) = CAST(sub.diary_no AS TEXT)
                 AND advance_allocated.next_dt = ?
                 AND advance_allocated.board_type = ?
                 AND CAST(advance_allocated.diary_no AS INTEGER) > 0";

        if ($db->query($sql1, [$listing_dt, $board_type])) {
            $result = true;
        }

        // Second query: Update connection keys
        $builder2 = $db->table('advance_allocated');
        $builder2->select('advance_allocated.conn_key, advance_allocated.brd_slno');
        $builder2->join('advanced_drop_note', 'advanced_drop_note.diary_no = advance_allocated.diary_no AND advanced_drop_note.cl_date = advance_allocated.next_dt', 'left');
        $builder2->where('advanced_drop_note.diary_no IS NULL');
        $builder2->where('advance_allocated.next_dt', $listing_dt);
        $builder2->where('advance_allocated.board_type', $board_type);
        $builder2->where('CAST(advance_allocated.diary_no AS TEXT) = CAST(advance_allocated.conn_key AS TEXT)');
        $builder2->where('CAST(advance_allocated.clno AS INTEGER) >', 0);
        $builder2->where('CAST(advance_allocated.brd_slno AS INTEGER) >', 0);

        $subQuery2 = $builder2->getCompiledSelect();

        // Updated query without alias for the target table in PostgreSQL
        $sql_conn = "WITH sub2 AS ($subQuery2)
                     UPDATE advance_allocated
                     SET brd_slno = sub2.brd_slno
                     FROM sub2
                     WHERE CAST(advance_allocated.conn_key AS TEXT) = CAST(sub2.conn_key AS TEXT)
                     AND advance_allocated.next_dt = ?
                     AND advance_allocated.board_type = ?";

        $db->query($sql_conn, [$listing_dt, $board_type]);

        return $result;
    }

    public function reshuffleAdvance($listing_dt, $board_type, $from_cl_no)
    {
        $result = false;

        // $new_no = $from_cl_no > 0 ? $from_cl_no - 1 : 0;
        $new_no = is_numeric($from_cl_no) && $from_cl_no > 0 ? $from_cl_no - 1 : 0;

        $db = \Config\Database::connect();
        $sql_conn1 = "WITH numbered_rows AS (
                        SELECT 
                            ROW_NUMBER() OVER (
                                ORDER BY 
                                    CAST(SUBSTRING(diary_no FROM LENGTH(diary_no::text) - 3 FOR 4) AS INTEGER) ASC,
                                    CAST(SUBSTRING(diary_no::text FROM 1 FOR LENGTH(diary_no::text) - 4) AS INTEGER) ASC
                            ) AS serial_number,
                            a.diary_no
                        FROM (
                            SELECT 
                                h.diary_no
                            FROM 
                                advance_allocated h
                            LEFT JOIN 
                                advanced_drop_note adn 
                                ON adn.diary_no = h.diary_no AND adn.cl_date = h.next_dt
                            INNER JOIN 
                                main m 
                                ON m.diary_no = h.diary_no::int
                            WHERE 
                                adn.diary_no IS NULL
                                AND (m.diary_no = m.conn_key::int OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL)
                                AND m.c_status = 'P'
                                AND h.board_type = '$board_type'
                                AND h.next_dt = '$listing_dt'
                                AND h.clno > 0
                                AND h.brd_slno > 0
                        ) a
                    )
                   
                    UPDATE advance_allocated h
                    SET brd_slno = numbered_rows.serial_number
                    FROM numbered_rows
                    WHERE h.diary_no = numbered_rows.diary_no AND h.diary_no::int > 0";


        $rsss1 = $this->db->query($sql_conn1);
        if ($rsss1) {
            $result = 1;
        }

        $sql_conn2 = "WITH subquery AS (
                        SELECT 
                            h.conn_key, 
                            h.brd_slno
                        FROM 
                            advance_allocated h
                        LEFT JOIN 
                            advanced_drop_note adn 
                            ON adn.diary_no = h.diary_no AND adn.cl_date = h.next_dt
                        WHERE 
                            adn.diary_no IS NULL
                            AND h.next_dt = '$listing_dt'
                            AND h.board_type = 'J'
                            AND h.diary_no::int = h.conn_key
                            AND h.clno > 0
                            AND h.brd_slno > 0
                    )
                    UPDATE advance_allocated h
                    SET brd_slno = subquery.brd_slno
                    FROM subquery
                    WHERE h.conn_key = subquery.conn_key";

        $rsss1 = $this->db->query($sql_conn2);
        return $result;
    }

    public function getSectionName($diary_no)
    {
        $db = \Config\Database::connect();
        $query = $db->query("SELECT tentative_section(?) AS section_name", [(int) $diary_no]);

        return $query->getRowArray();
    }







    public function getLctNoYear($diary_no)
    {
        $builder = $this->db->table('lowerct a');
        $builder->select('a.lct_dec_dt, a.lct_caseno, a.lct_caseyear, ct.short_description as type_sname')
            ->join('master.casetype ct', 'ct.casecode = a.lct_casetype AND ct.display = \'Y\'', 'left')
            ->where('a.diary_no', $diary_no)
            ->where('a.is_order_challenged', 'Y')
            ->where('a.lw_display', 'Y')
            ->where('ct_code', 4)
            ->orderBy('a.lct_dec_dt', 'desc');
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }
    public function getDocnumDocYear($diary_no)
    {
        $sql = "SELECT * FROM (
                SELECT h.diary_no, d.docnum, d.docyear, d.doccode1,
                    (CASE WHEN dm.doccode1 = 19 THEN other1 ELSE dm.docdesc END) AS docdesp,
                    d.other1, d.iastat
                FROM heardt h
                INNER JOIN docdetails d ON d.diary_no = h.diary_no
                INNER JOIN master.docmaster dm ON dm.doccode1 = d.doccode1 AND dm.doccode = d.doccode
                WHERE h.diary_no = '$diary_no'
                AND d.doccode = 8
                AND dm.display = 'Y'
                AND array_position(
                        string_to_array(
                            REPLACE(REPLACE(REPLACE(listed_ia, '/', ''), ' ', ''), ',', ','),
                            ','
                        ),
                        CAST(CONCAT(docnum, docyear) AS TEXT)
                    ) > 0
            ) a
            WHERE docdesp != ''
            ORDER BY docdesp";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }




    public function getRadvnamePadvname($diary_no)
    {
        $sql = "WITH grouped_advocates AS (
                    SELECT 
                        a.diary_no,
                        b.name,
                        string_agg(COALESCE(a.adv, ''), '' ORDER BY 
                                CASE WHEN a.pet_res IN ('I', 'N') THEN 99 ELSE 0 END ASC, 
                                a.adv_type DESC, 
                                a.pet_res_no ASC) AS grp_adv,
                        a.pet_res,
                        a.adv_type,
                        a.pet_res_no
                    FROM 
                        advocate a
                    LEFT JOIN 
                        master.bar b 
                    ON 
                        a.advocate_id = b.bar_id AND b.isdead != 'Y'
                    WHERE 
                        a.diary_no = '" . $diary_no . "' 
                        AND a.display = 'Y'
                    GROUP BY 
                        a.diary_no, b.name, a.pet_res, a.adv_type, a.pet_res_no
                )
                SELECT 
                    a.*,
                    string_agg(a.name || '' || a.grp_adv, '' ORDER BY a.adv_type DESC, a.pet_res_no ASC) FILTER (WHERE a.pet_res = 'R') AS r_n,
                    string_agg(a.name || '' || a.grp_adv, '' ORDER BY a.adv_type DESC, a.pet_res_no ASC) FILTER (WHERE a.pet_res = 'P') AS p_n,
                    string_agg(a.name || '' || a.grp_adv, '' ORDER BY a.adv_type DESC, a.pet_res_no ASC) FILTER (WHERE a.pet_res = 'I') AS i_n,
                    string_agg(a.name || '' || a.grp_adv, '' ORDER BY a.adv_type DESC, a.pet_res_no ASC) FILTER (WHERE a.pet_res = 'N') AS intervenor
                FROM 
                    grouped_advocates a
                GROUP BY 
                    a.diary_no,a.name,a.grp_adv,a.pet_res,a.adv_type,a.pet_res_no;";
        $query = $this->db->query($sql);
        $result = $query->getRowArray();
        return $result;
    }










    // New functions 


    public function getAdvanceListNumber_old($list_dt, $board_type)
    {
        $builder = $this->db->table('advance_cl_printed')
            ->select('count(next_dt) as advance_list_no')
            ->where('EXTRACT(YEAR FROM next_dt)', date('Y', strtotime($list_dt)))
            ->where('next_dt !=', $list_dt)
            ->where('board_type', $board_type)
            ->where('display', 'Y');

        $query = $builder->get();


        if ($query->getRow()) {
            return $query->getRow()->advance_list_no;
        } else {
            return 0;
        }
    }

    public function getAdvanceListNumber($list_dt, $board_type)
    {
        $sql = "SELECT COUNT(next_dt) AS advance_list_no 
FROM advance_cl_printed 
WHERE EXTRACT(YEAR FROM next_dt) = EXTRACT(YEAR FROM DATE '$list_dt') 
  AND next_dt != DATE '$list_dt' 
  AND board_type = '$board_type' 
  AND display = 'Y'";
        $query = $this->db->query($sql);
        return $query->getRowArray();
    }








    public function getBoardRange($list_dt, $board_type)
    {
        $builder = $this->db->table('advance_allocated');
        $builder->select('MIN(brd_slno) AS min_brd, MAX(brd_slno) AS max_brd')
            ->where('next_dt', $list_dt)
            ->where('board_type', $board_type);
        return $builder->get()->getRowArray();
    }



    public function getNMDNote($list_dt)
    {
        $builder = $this->db->table('master.sc_working_days');

        $builder->select('is_nmd')
            ->where('display', 'Y')
            ->where('is_nmd', 1)
            ->where('is_holiday', 0)
            ->where('working_date', $list_dt);

        $query = $builder->get();
        $result = $query->getRowArray();

        return $result;
    }


    public function getMatters($list_dt, $board_type_in = '', $leftjoin_subhead = '', $leftjoin_submaster = '', $sub_head_name = '', $roster_id = '', $mainhead = '')
    {
        // Initialize the query builder

        $builder = $this->db->table('advance_allocated h')
            ->select("m.relief, u.name, us.section_name, h.*, l.purpose, c1.short_description, 
              active_fil_no, m.active_reg_year, m.casetype_id, m.active_casetype_id, m.ref_agency_state_id, 
              m.reg_no_display, YEAR(m.fil_dt) as fil_year, m.fil_no, m.fil_dt, m.fil_no_fh, 
              m.reg_year_fh AS fil_year_f, m.mf_active, m.pet_name, m.res_name, pno, rno, 
              m.if_sclsc, m.diary_no_rec_date, h2.listed_ia, $sub_head_name")
            ->join('main m', 'CAST(m.diary_no AS VARCHAR) = h.diary_no', 'inner')
            ->join('heardt h2', 'h2.diary_no = m.diary_no', 'left')
            ->join('advanced_drop_note ad', "ad.diary_no = h.diary_no AND ad.cl_date = h.next_dt AND ad.display = 'R'", 'left')
            ->join('master.casetype c1', 'active_casetype_id = c1.casecode', 'left')
            ->join('master.listing_purpose l', 'l.code = h.listorder', 'left');

        if (!empty($leftjoin_submaster)) {
            $builder->join($leftjoin_submaster, 'left');
        }

        if ($mainhead !== 'F') {

            $builder->join('master.subheading s', 's.stagecode = h.subhead AND s.display = \'Y\' AND s.listtype = ' . $this->db->escape($board_type_in), 'left');
        } else {
            $builder->join('category_allottment c', "h.subhead = c.submaster_id AND c.ros_id = " . $this->db->escape($roster_id) . " AND c.display = 'Y'", 'left');
            $builder->join('master.submaster sm', "h.subhead = sm.id AND sm.display = 'Y'", 'left');
        }

        $builder->join('master.users u', "u.usercode = m.dacode AND u.display = 'Y'", 'left')
            ->join('master.usersection us', 'us.id = u.section', 'left')
            ->join('conct ct', "m.diary_no = ct.diary_no AND ct.list = 'Y'", 'left')
            ->where('ad.diary_no IS NULL')
            ->where('h.next_dt', $list_dt)
            ->where('h.clno >', 0)
            ->where('h.brd_slno >', 0)
            ->where('l.display', 'Y')
            ->whereIn('h.main_supp_flag', [1, 2])
            ->groupBy('h.diary_no')
            ->orderBy('h.brd_slno')
            ->orderBy("CASE WHEN h.conn_key = h.diary_no THEN '0000-00-00' ELSE '99' END", 'ASC', false)
            ->orderBy("CASE WHEN ct.ent_dt IS NOT NULL THEN ct.ent_dt ELSE 999 END", 'ASC', false)
            ->orderBy("CAST(SUBSTRING(m.diary_no, -4) AS SIGNED)", 'ASC', false)
            ->orderBy("CAST(LEFT(m.diary_no, LENGTH(m.diary_no) - 4) AS SIGNED)", 'ASC', false);


        $sql = $builder->getCompiledSelect();

        $query = $builder->get();


        $data = $query->getNumRows();
    }
    // public function getFutureDates()
    // {

    //     $sql = "SELECT c.next_dt
    //             FROM advance_allocated c
    //             WHERE c.next_dt >= CURRENT_DATE
    //             GROUP BY c.next_dt";
    //     $query = $this->db->query($sql);
    //     $result = $query->getResultArray();

    //     return $result;
    // }

    public function getFutureDates()
    {
        $builder = $this->db->table('advance_allocated');

        $builder->select('next_dt')
            ->where('next_dt >=', date('Y-m-d'))
            ->groupBy('next_dt');
        //echo $this->db->getLastQuery();die;

        $query = $builder->get();
        $result = $query->getResultArray();

        return $result;
    }





    // Fetch keywords
    public function getKeywords()
    {
        return $this->db->table('master.ref_keyword')
            ->where('is_deleted', 'f')
            ->orderBy('keyword_description')
            ->get()
            ->getResultArray();
    }

    // Fetch document codes
    public function getDocs()
    {
        return $this->db->table('master.docmaster')
            ->where('doccode', '8')
            ->where('display', 'Y')
            ->orderBy('docdesc')
            ->get()
            ->getResultArray();
    }

    // Fetch acts
    public function getActs()
    {
        return $this->db->table('master.act_master')
            ->where('display', 'Y')
            ->where('act_name IS NOT NULL')
            ->where('act_name !=', '')
            ->orderBy('act_name')
            ->get()
            ->getResultArray();
    }


    // Advance allocatin

    public function getisNMD($cldt)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('master.sc_working_days');
        $builder->select('is_nmd');
        $builder->where('working_date', $cldt); //vkg
        $builder->where('is_holiday', 0);
        $builder->where('display', 'Y');
        $query = $builder->get();
        $result = $query->getRowArray();
        return $result;
    }

    public function isNMDOne($cldt)
    {
        $sql = "SELECT CONCAT(p1, ',', p2, CASE WHEN p3 != 0 THEN CONCAT(',', p3) ELSE '' END) AS jcd,jg.p1,jg.p2,jg.p3,j.abbreviation, 
                (SELECT CASE WHEN SNo = 1 THEN 15 ELSE 10 END AS old_limit
                    FROM (SELECT ROW_NUMBER() OVER () AS SNo, s.* FROM master.sc_working_days s
                        WHERE EXTRACT(WEEK FROM working_date) = EXTRACT(WEEK FROM DATE '$cldt')
                        AND is_holiday = 0 
                        AND is_nmd = 1 
                        AND display = 'Y' 
                        AND EXTRACT(YEAR FROM working_date) = EXTRACT(YEAR FROM DATE '$cldt')
                        ORDER BY working_date
                        ) a
                    WHERE working_date = DATE '$cldt'
                ) AS old_limit
                FROM judge_group jg 
                LEFT JOIN master.judge j ON j.jcode = jg.p1
                WHERE jg.to_dt IS NULL 
                AND jg.display = 'Y' 
                AND j.is_retired != 'Y'
                ORDER BY j.judge_seniority";

        $query = $this->db->query($sql);
        $results = $query->getResultArray();
        if (empty($results)) {
            return [];
        }
        return $results;
    }

    public function isNMDZero()
    {
        $sql = "SELECT jg.p1 || ',' || jg.p2 || CASE WHEN jg.p3 != 0 THEN ',' || jg.p3 ELSE '' END AS jcd,jg.p1,jg.p2,jg.p3,j.abbreviation,jg.fresh_limit,jg.old_limit 
                FROM judge_group jg 
                LEFT JOIN master.judge j ON j.jcode = jg.p1
                WHERE jg.to_dt IS NULL 
                AND jg.display = 'Y' 
                AND j.is_retired != 'Y' 
                ORDER BY j.judge_seniority";
        $query = $this->db->query($sql);
        $results = $query->getResultArray();
        if (empty($results)) {
            return [];
        }
        return $results;
    }

    public function getListedData(string $jcd, int $p1, string $cldt)
    {
        //$cldt = '2023-01-13'; // remove 

        // $jcd_p1 = explode(",", $jcd);

        // $builder = $this->db->table('advance_allocated h');
        // $builder->select("COUNT(DISTINCT h.diary_no) AS listed,
        //             SUM(CASE WHEN h.subhead = 829 THEN 1 ELSE 0 END) AS TP,
        //             SUM(CASE WHEN h.subhead = 804 THEN 1 ELSE 0 END) AS Bail,
        //             SUM(CASE WHEN h.subhead = 831 THEN 1 ELSE 0 END) AS Old_After_Notice,
        //             SUM(CASE WHEN (c.diary_no IS NULL AND (m.fil_no_fh = '' OR m.fil_no_fh IS NULL) AND h.subhead NOT IN (813, 814)) THEN 1 ELSE 0 END) AS Pre_Notice,
        //             SUM(CASE WHEN NOT (c.diary_no IS NULL AND (m.fil_no_fh = '' OR m.fil_no_fh IS NULL) AND h.subhead NOT IN (813, 814)) THEN 1 ELSE 0 END) AS After_Notice");
        // $builder->join('public.main m', 'CAST(h.diary_no AS BIGINT) = m.diary_no', 'left');
        // $builder->join('advanced_drop_note d', 'd.diary_no = h.diary_no AND d.cl_date = h.next_dt', 'left');
        // $builder->join('public.case_remarks_multiple c', 'CAST(c.diary_no AS BIGINT) = m.diary_no', 'left');
        // $builder->where('h.j1', $p1);
        // $builder->where('h.next_dt', $cldt);
        // $builder->where('h.board_type', 'J');
        // $builder->whereIn('h.main_supp_flag', [1, 2]);
        // $builder->groupStart();
        // $builder->where('CAST(m.conn_key AS BIGINT) = m.diary_no');
        // $builder->orWhere('m.conn_key', '');
        // $builder->orWhere('m.conn_key IS NULL');
        // $builder->orWhere('m.conn_key', '0');
        // $builder->groupEnd();
        // $query = $builder->get();
        // return $query->getRowArray() ?? ['listed' => 0, 'TP' => 0, 'Bail' => 0, 'Old_After_Notice' => 0, 'Pre_Notice' => 0, 'After_Notice' => 0]; // Return default array if no result

        $sql = "SELECT 
                j1,
                COUNT(h.diary_no) AS listed,
                SUM(CASE WHEN pre_after_notice = 'TP' THEN 1 ELSE 0 END) AS TP,
                SUM(CASE WHEN pre_after_notice = 'Bail' THEN 1 ELSE 0 END) AS Bail,
                SUM(CASE WHEN pre_after_notice = 'Old_After_Notice' THEN 1 ELSE 0 END) AS Old_After_Notice,
                SUM(CASE WHEN pre_after_notice = 'Pre_Notice' THEN 1 ELSE 0 END) AS Pre_Notice,
                SUM(CASE WHEN pre_after_notice = 'After_Notice' THEN 1 ELSE 0 END) AS After_Notice
            FROM (
                SELECT DISTINCT 
                    h.diary_no,
                    h.j1, 
                    h.subhead, 
                    CASE 
                        WHEN h.subhead = 829 THEN 'TP'
                        WHEN h.subhead = 804 THEN 'Bail'
                        WHEN h.subhead = 831 THEN 'Old_After_Notice'
                        WHEN c.diary_no IS NULL AND (m.fil_no_fh = '' OR m.fil_no_fh IS NULL) AND h.subhead NOT IN (813, 814) THEN 'Pre_Notice'
                        ELSE 'After_Notice' 
                    END AS pre_after_notice
                FROM 
                    advance_allocated h
                LEFT JOIN 
                    public.main m ON h.diary_no = m.diary_no::text
                LEFT JOIN 
                    advanced_drop_note d ON d.diary_no::int = h.diary_no::int AND d.cl_date = h.next_dt
                LEFT JOIN 
                    public.case_remarks_multiple c ON c.diary_no::int = m.diary_no::int AND c.r_head IN (1, 3, 62, 181, 182, 183, 184)
                WHERE 
                    d.diary_no IS NULL 
                    AND h.next_dt = '$cldt' 
                    AND h.j1 = $p1
                    AND h.board_type = 'J'
                    AND h.main_supp_flag IN (1, 2)
                    AND (m.diary_no::text = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
                GROUP BY 
                    m.diary_no,h.diary_no,h.j1,h.subhead,c.diary_no
            ) AS h
            GROUP BY 
                h.j1";

        $query = $this->db->query($sql);
        $results = $query->getRowArray();
        if (!$results) {
            return [];
        }
        return $results;
    }

    public function getIsNumConditionBase($is_nmd, $q_next_dt)
    {
        if ($is_nmd == 0) {
            $sql = "SELECT
                        jg.p1,
                        jg.p2,
                        jg.p3,
                        j.abbreviation,
                        jg.fresh_limit,
                        (
                            SELECT
                                CASE
                                    WHEN row_number() OVER (ORDER BY s.working_date) = 1 THEN 15
                                    ELSE 10
                                END AS old_limit
                            FROM
                                master.sc_working_days s
                            WHERE
                                EXTRACT(WEEK FROM working_date) = EXTRACT(WEEK FROM '$q_next_dt'::date)
                                AND is_holiday = 0
                                AND is_nmd = 1
                                AND display = 'Y'
                                AND EXTRACT(YEAR FROM working_date) = EXTRACT(YEAR FROM '$q_next_dt'::date)
                                AND working_date = '$q_next_dt'::date
                        ) AS old_limit,
                        COALESCE(b.listed, 0) AS listed
                    FROM
                        judge_group jg
                    LEFT JOIN
                        master.judge j ON j.jcode = jg.p1
                    LEFT JOIN
                        (
                            SELECT
                                h.j1,
                                COUNT(h.diary_no) AS listed
                            FROM
                                advance_allocated h
                            LEFT JOIN
                                main m ON h.diary_no::text = m.diary_no::text
                            LEFT JOIN
                                advanced_drop_note d ON d.diary_no = h.diary_no AND d.cl_date = h.next_dt
                            WHERE
                                d.diary_no IS NULL
                                AND h.next_dt = '$q_next_dt'::date
                                AND h.board_type = 'J'
                                AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
                                AND (m.diary_no::text = m.conn_key::text OR m.conn_key::text = '' OR m.conn_key::text IS NULL OR m.conn_key::text = '0')
                            GROUP BY
                                h.j1
                        ) b ON b.j1 = jg.p1
                    WHERE
                        j.is_retired != 'Y'
                        AND jg.to_dt = '0001-01-01'
                        AND jg.display = 'Y'
                    GROUP BY
                        jg.p1, jg.p2, jg.p3, j.abbreviation, jg.fresh_limit, b.listed,j.judge_seniority
                    ORDER BY
                        j.judge_seniority";
        } else {
            $sql = "SELECT
                        jg.p1,
                        jg.p2,
                        jg.p3,
                        j.abbreviation,
                        jg.fresh_limit,
                        jg.old_limit,
                        COALESCE(b.listed, 0) AS listed
                    FROM
                        judge_group jg
                    LEFT JOIN
                        master.judge j ON j.jcode = jg.p1
                    LEFT JOIN
                        (
                            SELECT
                                h.j1,
                                COUNT(h.diary_no) AS listed
                            FROM
                                advance_allocated h
                            LEFT JOIN
                                main m ON h.diary_no::text = m.diary_no::text
                            LEFT JOIN
                                advanced_drop_note d ON d.diary_no = h.diary_no AND d.cl_date = h.next_dt
                            WHERE
                                d.diary_no IS NULL
                                AND h.next_dt = '$q_next_dt'::date
                                AND h.board_type = 'J'
                                AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
                                AND (m.diary_no::text = m.conn_key::text OR m.conn_key::text = '' OR m.conn_key::text IS NULL OR m.conn_key::text = '0')
                            GROUP BY
                                h.j1
                        ) b ON b.j1 = jg.p1
                    WHERE
                        j.is_retired != 'Y'
                        AND jg.to_dt = '0001-01-01'
                        AND jg.display = 'Y'
                    GROUP BY
                        jg.p1, jg.p2, jg.p3, j.abbreviation, jg.fresh_limit, jg.old_limit, b.listed,j.judge_seniority
                    ORDER BY
                        j.judge_seniority";
        }

        $rs = $this->db->query($sql);
        $results = $rs->getResultArray();
        return $results;
    }









    public function getMetterdata($list_dt, $board_type_in, $mainhead)
    {
        if ($mainhead !== 'F') {

            $sub_head_name = 's.stagename';
            $leftjoin_subhead = "LEFT JOIN master.subheading s ON s.stagecode = h.subhead and s.display = 'Y' AND s.listtype = '$mainhead'";
            $leftjoin_submaster = "";


            $sql = "SELECT m.relief,u.name,us.section_name,h.*,l.purpose,c1.short_description,active_fil_no,m.active_reg_year,m.casetype_id,m.active_casetype_id,
                    m.ref_agency_state_id,m.reg_no_display,EXTRACT(YEAR FROM m.fil_dt) AS fil_year,m.fil_no,m.fil_dt,m.fil_no_fh,m.reg_year_fh AS fil_year_f,
                    m.mf_active,m.pet_name,m.res_name,pno,rno,m.if_sclsc,m.diary_no_rec_date,h2.listed_ia,$sub_head_name
                        FROM 
                            advance_allocated h
                        INNER JOIN 
                            main m ON m.diary_no = h.diary_no::int
                        LEFT JOIN 
                            heardt h2 ON h2.diary_no = m.diary_no  
                        LEFT JOIN 
                            advanced_drop_note ad ON ad.diary_no = h.diary_no 
                        
                            AND ad.cl_date = h.next_dt 
                            AND ad.display = 'R'
                        LEFT JOIN 
                            master.casetype c1 ON m.active_casetype_id = c1.casecode
                        LEFT JOIN 
                            master.listing_purpose l ON l.code = h.listorder
                        $leftjoin_submaster
                        $leftjoin_subhead               
                        LEFT JOIN 
                            master.users u ON u.usercode = m.dacode 
                            AND u.display = 'Y'
                        LEFT JOIN 
                            master.usersection us ON us.id = u.section    
                        LEFT JOIN 
                            conct ct ON m.diary_no = ct.diary_no 
                            AND ct.list = 'Y'
                        WHERE 
                        ad.diary_no IS NULL 
                            AND h.next_dt = '$list_dt'  
                            $board_type_in
                        AND h.clno > 0 
                            AND h.brd_slno > 0 
                            AND l.display = 'Y'  
                            AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)    
                        GROUP BY 
                            h.diary_no, m.relief, u.name, us.section_name, l.purpose, c1.short_description,
                            active_fil_no, m.active_reg_year, m.casetype_id, m.active_casetype_id, m.ref_agency_state_id, 
                            m.reg_no_display, m.fil_dt, m.fil_no, m.fil_no_fh, m.reg_year_fh, m.mf_active, 
                            m.pet_name, m.res_name, pno, rno, m.if_sclsc, m.diary_no_rec_date, h2.listed_ia, s.stagename,
                            h.id,ct.ent_dt,m.diary_no
                        ORDER BY 
                            h.brd_slno,
                            CASE WHEN h.conn_key = h.diary_no::int THEN null ELSE 99 END ASC,
                            CASE WHEN ct.ent_dt IS NOT NULL THEN ct.ent_dt ELSE '1970-01-01 00:00:00+00'::timestamp END ASC,
                            CAST(SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3) AS INTEGER) ASC,
                            CAST(SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4) AS INTEGER) ASC";
            $query = $this->db->query($sql);
            $result = $query->getResultArray();
            return $result;
        } else {

            $sub_head_name = 'sm.sub_name1, sm.sub_name2, sm.sub_name3, sm.sub_name4';
            $leftjoin_subhead = "LEFT JOIN category_allottment c ON  h.subhead = c.submaster_id AND (c.ros_id IS NULL OR c.ros_id = 0)   AND c.display = 'Y'";
            $leftjoin_submaster = "LEFT JOIN master.submaster sm ON h.subhead = sm.id AND sm.display = 'Y'";


            $sql = "SELECT m.relief,u.name,us.section_name,h.*,l.purpose,c1.short_description,active_fil_no,m.active_reg_year,m.casetype_id,m.active_casetype_id,
                    m.ref_agency_state_id,m.reg_no_display,EXTRACT(YEAR FROM m.fil_dt) AS fil_year,m.fil_no,m.fil_dt,m.fil_no_fh,m.reg_year_fh AS fil_year_f,m.mf_active,
                    m.pet_name, m.res_name, pno, rno, m.if_sclsc, m.diary_no_rec_date,h2.listed_ia,
                    $sub_head_name
                    FROM 
                            advance_allocated h
                        INNER JOIN 
                            main m ON m.diary_no = h.diary_no::int
                        LEFT JOIN 
                            heardt h2 ON h2.diary_no = m.diary_no  
                        LEFT JOIN 
                            advanced_drop_note ad ON ad.diary_no = h.diary_no 
                        
                            AND ad.cl_date = h.next_dt 
                            AND ad.display = 'R'
                        LEFT JOIN 
                            master.casetype c1 ON m.active_casetype_id = c1.casecode
                        LEFT JOIN 
                            master.listing_purpose l ON l.code = h.listorder
                    $leftjoin_submaster
                    $leftjoin_subhead               
                        LEFT JOIN 
                            master.users u ON u.usercode = m.dacode 
                            AND u.display = 'Y'
                        LEFT JOIN 
                            master.usersection us ON us.id = u.section    
                        LEFT JOIN 
                            conct ct ON m.diary_no = ct.diary_no 
                            AND ct.list = 'Y'
                        WHERE 
                        ad.diary_no IS NULL 
                            AND h.next_dt = '$list_dt'  
                            $board_type_in
                        AND h.clno > 0 
                            AND h.brd_slno > 0 
                            AND l.display = 'Y'  
                            AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)    
                        GROUP BY 
                            h.diary_no, m.relief, u.name, us.section_name, l.purpose, c1.short_description,
                            active_fil_no, m.active_reg_year, m.casetype_id, m.active_casetype_id, m.ref_agency_state_id, 
                            m.reg_no_display, m.fil_dt, m.fil_no, m.fil_no_fh, m.reg_year_fh, m.mf_active, 
                            m.pet_name, m.res_name, pno, rno, m.if_sclsc, m.diary_no_rec_date, h2.listed_ia, 
                            sm.sub_name1, sm.sub_name2, sm.sub_name3, sm.sub_name4,
                            h.id,ct.ent_dt,m.diary_no
                        ORDER BY 
                            h.brd_slno,
                            CASE WHEN h.conn_key = h.diary_no::int THEN null ELSE 99 END ASC,
                            CASE WHEN ct.ent_dt IS NOT NULL THEN ct.ent_dt ELSE '1970-01-01 00:00:00+00'::timestamp END ASC,
                            CAST(SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3) AS INTEGER) ASC,
                            CAST(SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4) AS INTEGER) ASC";
            $query = $this->db->query($sql);
            $result = $query->getResultArray();
            return $result;
        }
    }



    public function getPrintStatus_old($next_dt, $board_type)
    {
        $sql = "SELECT * FROM advance_cl_printed 
        WHERE next_dt = '$next_dt' 
        AND board_type = '$board_type' 
        AND display='Y'";
        $query = $this->db->query($sql);
        $result = $query->getRowArray();
        return $result;
    }
    public function getPrintStatus($next_dt, $board_type)
    {
        $builder = $this->db->table('advance_cl_printed');
        $builder->select('*')
            ->where('next_dt', $next_dt)
            ->where('board_type', $board_type)
            ->where('display', 'Y');
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function getSno($q_next_dt)
    {
        $sql_SNo = "WITH working_days_with_rownum AS (SELECT
                        ROW_NUMBER() OVER (ORDER BY working_date) AS SNo,
                            s.*
                            FROM master.sc_working_days s
                            WHERE EXTRACT(week FROM working_date) = EXTRACT(week FROM TO_DATE('$q_next_dt', 'YYYY-MM-DD'))
                        AND EXTRACT(year FROM working_date) = EXTRACT(year FROM TO_DATE('$q_next_dt', 'YYYY-MM-DD'))
                        AND is_holiday = 0
                        AND is_nmd = 1
                        AND display = 'Y'
                )
                SELECT SNo
                FROM working_days_with_rownum
                WHERE working_date = TO_DATE('$q_next_dt', 'YYYY-MM-DD');";

        $query = $this->db->query($sql_SNo);
        $result = $query->getRowArray();
        return $result;
    }

    public function  miscNmdFlagOne($q_next_dt)
    {
        // $sql_p2 = "SELECT *
        //                     FROM (
        //                         SELECT
        //                             jg.p1,
        //                             jg.p2,
        //                             jg.p3,
        //                             j.abbreviation,
        //                             jg.fresh_limit,
        //                             (
        //                                 SELECT
        //                                     CASE
        //                                         WHEN SNo = 1 THEN 15
        //                                         ELSE 10
        //                                     END AS old_limit
        //                                 FROM (
        //                                     SELECT
        //                                         ROW_NUMBER() OVER (ORDER BY s.working_date) AS SNo,
        //                                         s.*
        //                                     FROM master.sc_working_days s
        //                                     WHERE EXTRACT(WEEK FROM s.working_date) = EXTRACT(WEEK FROM TO_DATE('$q_next_dt', 'YYYY-MM-DD'))
        //                                         AND s.is_holiday = 0
        //                                         AND s.is_nmd = 1
        //                                         AND s.display = 'Y'
        //                                         AND EXTRACT(YEAR FROM s.working_date) = EXTRACT(YEAR FROM TO_DATE('$q_next_dt', 'YYYY-MM-DD'))
        //                                     ORDER BY s.working_date
        //                                 ) a
        //                                 WHERE a.working_date = TO_DATE('$q_next_dt', 'YYYY-MM-DD')
        //                             ) AS old_limit,
        //                             COALESCE(b.listed, 0) AS listed
        //                         FROM judge_group jg
        //                         LEFT JOIN master.judge j ON j.jcode = jg.p1
        //                         LEFT JOIN (
        //                             SELECT
        //                                 h.j1,
        //                                 COUNT(h.diary_no) AS listed
        //                             FROM advance_allocated h
        //                             LEFT JOIN main m ON CAST(h.diary_no AS bigint) = m.diary_no
        //                             LEFT JOIN advanced_drop_note d ON d.diary_no = h.diary_no AND d.cl_date = h.next_dt
        //                             WHERE d.diary_no IS NULL
        //                                 AND h.next_dt = TO_DATE('$q_next_dt', 'YYYY-MM-DD')
        //                                 AND h.board_type = 'J'
        //                                 AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
        //                                 AND (CAST(m.diary_no AS bigint) = CAST(m.conn_key AS bigint)
        //                                     OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
        //                             GROUP BY h.j1
        //                         ) b ON b.j1 = jg.p1
        //                         WHERE j.is_retired != 'Y'

        //                             AND (jg.to_dt IS NULL OR TO_CHAR(jg.to_dt, 'YYYY-MM-DD') != '0000-00-00')
        //                             AND jg.display = 'Y'
        //                         ORDER BY j.judge_seniority
        //                         LIMIT 20 OFFSET 4
        //                     ) a
        //                     WHERE a.old_limit > a.listed";

        $sql_p2 = "WITH working_days_ranked AS (
                SELECT 
                    ROW_NUMBER() OVER (ORDER BY working_date) AS SNo,
                    working_date,
                    CASE WHEN ROW_NUMBER() OVER (ORDER BY working_date) = 1 THEN 15 ELSE 10 END AS old_limit
                FROM master.sc_working_days
                WHERE 
                    EXTRACT(WEEK FROM working_date) = EXTRACT(WEEK FROM TO_DATE('$q_next_dt', 'YYYY-MM-DD'))
                    AND is_holiday = 0 
                    AND is_nmd = 1 
                    AND display = 'Y' 
                    AND EXTRACT(YEAR FROM working_date) = EXTRACT(YEAR FROM TO_DATE('$q_next_dt', 'YYYY-MM-DD'))
            )
            SELECT * FROM (
                SELECT 
                    jg.p1, 
                    jg.p2, 
                    jg.p3, 
                    j.abbreviation, 
                    jg.fresh_limit, 
                    wd.old_limit, 
                    COALESCE(listed, 0) AS listed
                FROM judge_group jg
                LEFT JOIN master.judge j ON j.jcode = jg.p1
                LEFT JOIN working_days_ranked wd ON wd.working_date = TO_DATE('$q_next_dt', 'YYYY-MM-DD')
                LEFT JOIN (
                    SELECT h.j1, COUNT(h.diary_no) AS listed 
                    FROM advance_allocated h 
                    LEFT JOIN main m ON h.diary_no::bigint = m.diary_no::bigint 
                    LEFT JOIN advanced_drop_note d ON d.diary_no = h.diary_no AND d.cl_date = h.next_dt
                    WHERE d.diary_no IS NULL 
                    AND h.next_dt = TO_DATE('$q_next_dt', 'YYYY-MM-DD')                                          
                    AND h.board_type = 'J' 
                    AND h.main_supp_flag IN (1, 2)
                    AND (m.diary_no::bigint = m.conn_key::bigint OR m.conn_key IN ('', NULL, '0')) 
                    GROUP BY h.j1
                ) b ON b.j1 = jg.p1
                WHERE j.is_retired != 'Y' 
                AND jg.to_dt IS NULL 
                AND jg.display = 'Y' 
                GROUP BY jg.p1, jg.p2, jg.p3, j.abbreviation, jg.fresh_limit, wd.old_limit, listed, j.judge_seniority
                ORDER BY j.judge_seniority
                OFFSET 4 LIMIT 20  -- Corrected pagination for PostgreSQL
            ) a 
            WHERE old_limit > listed";
        // pr($sql_p2);
        $rs_p2 = $this->db->query($sql_p2);
        $ro_isnmd = $rs_p2->getResultArray();
        return $ro_isnmd;
    }

    public function  miscNmdFlagZero($q_next_dt)
    {
        // $sql_p2 = "SELECT
        //                 jg.p1,
        //                 jg.p2,
        //                 jg.p3,
        //                 j.abbreviation,
        //                 jg.fresh_limit,
        //                 jg.old_limit,
        //                 COALESCE(b.listed, 0) AS listed,
        //                 MAX(j.judge_seniority) AS judge_seniority
        //             FROM judge_group jg
        //             LEFT JOIN master.judge j ON j.jcode = jg.p1
        //             LEFT JOIN (
        //                 SELECT
        //                     h.j1,
        //                     COUNT(h.diary_no) AS listed
        //                 FROM advance_allocated h
        //                 LEFT JOIN main m ON CAST(h.diary_no AS bigint) = CAST(m.diary_no AS bigint)
        //                 LEFT JOIN advanced_drop_note d ON d.diary_no = h.diary_no AND d.cl_date = h.next_dt
        //                 WHERE d.diary_no IS NULL
        //                     AND h.next_dt = TO_DATE('$q_next_dt', 'YYYY-MM-DD')
        //                     AND h.board_type = 'J'
        //                     AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
        //                     AND (
        //                         CAST(m.diary_no AS bigint) = CAST(m.conn_key AS bigint)
        //                         OR m.conn_key = ''
        //                         OR m.conn_key IS NULL
        //                         OR m.conn_key = '0'
        //                     )
        //                 GROUP BY h.j1
        //             ) b ON b.j1 = jg.p1
        //             WHERE j.is_retired != 'Y'
        //                 AND (jg.to_dt IS NULL OR TO_CHAR(jg.to_dt, 'YYYY-MM-DD') != '0000-00-00')
        //                 AND jg.display = 'Y'
        //             GROUP BY jg.p1, jg.p2, jg.p3, j.abbreviation, jg.fresh_limit, jg.old_limit, b.listed
        //             HAVING jg.old_limit > COALESCE(b.listed, 0)
        //             ORDER BY judge_seniority";

        $sql_p2 = "SELECT jg.p1, 
                    jg.p2, 
                    jg.p3, 
                    j.abbreviation, 
                    jg.fresh_limit, 
                    jg.old_limit, 
                    COALESCE(listed, 0) AS listed 
                FROM judge_group jg 
                LEFT JOIN master.judge j ON j.jcode = jg.p1 
                LEFT JOIN (
                    SELECT h.j1, COUNT(h.diary_no)::BIGINT AS listed  -- Explicitly cast COUNT() to BIGINT
                    FROM advance_allocated h 
                    LEFT JOIN main m 
                        ON m.diary_no::TEXT ~ '^[0-9]+$' 
                        AND m.conn_key::TEXT ~ '^[0-9]+$'
                        AND m.diary_no::BIGINT = m.conn_key::BIGINT 
                    LEFT JOIN advanced_drop_note d 
                        ON d.diary_no = h.diary_no 
                        AND d.cl_date = h.next_dt 
                    WHERE d.diary_no IS NULL 
                    AND h.next_dt = TO_DATE('$q_next_dt', 'YYYY-MM-DD') 
                    AND h.board_type = 'J' 
                    AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)  
                    AND (
                        (m.diary_no::TEXT ~ '^[0-9]+$' AND m.conn_key::TEXT ~ '^[0-9]+$' AND m.diary_no::BIGINT = m.conn_key::BIGINT) 
                        OR m.conn_key = '' 
                        OR m.conn_key IS NULL 
                        OR m.conn_key = '0'
                    ) 
                    GROUP BY h.j1
                ) b ON b.j1 = jg.p1 
                WHERE j.is_retired != 'Y' 
                AND jg.to_dt IS NULL 
                AND jg.display = 'Y' 
                GROUP BY jg.p1, jg.p2, jg.p3, j.abbreviation, jg.fresh_limit, jg.old_limit, listed, j.judge_seniority
                HAVING jg.old_limit > COALESCE(listed, 0) 
                ORDER BY j.judge_seniority;";
        $rs_p2 = $this->db->query($sql_p2);

        $ro_isnmd = $rs_p2->getResultArray();
        return $ro_isnmd;
    }





    public function getIsPersonendOne($q_next_dt, $presiing_judge_str, $misc_nmd_flag, $p_listorder, $subhead_select, $case_type_select, $subject_cat_select)
    {
        $sql_c = "SELECT *
                        FROM (
                            SELECT
                                'NO' AS is_prepon,
                                CASE
                                    WHEN (c.diary_no IS NULL
                                        AND (m.fil_no_fh = '' OR m.fil_no_fh IS NULL)
                                        AND h.subhead NOT IN (813, 814)) THEN 1
                                    ELSE 2
                                END AS pre_notice,
                                t.rid,
                                t.cat,
                                dd.doccode1,  
                                a.advocate_id,  
                                submaster_id,
                                m.conn_key AS main_key,
                                l.priority,  
                                h.diary_no, 
                                h.subhead, 
                                h.listorder, 
                                h.main_supp_flag,
                                h.mainhead, 
                                h.next_dt,
                                h.roster_id,
                                h.brd_slno,
                                aa2.diary_no AS old_advance_no,
                                CASE
                                    WHEN (submaster_id IN (173, 176, 222) OR h.subhead IN (804, 831)) THEN 'Yes'
                                    ELSE 'No'
                                END AS is_short_cat,
                                h.no_of_time_deleted,
                                h.coram
                            FROM public.main m
                            LEFT JOIN public.heardt h ON h.diary_no = m.diary_no
                            LEFT JOIN master.listing_purpose l ON l.code = h.listorder
                            LEFT JOIN public.rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                            LEFT JOIN public.mul_category mc ON mc.diary_no = m.diary_no
                            LEFT JOIN public.docdetails dd ON dd.diary_no = h.diary_no
                                AND dd.iastat = 'P'
                                AND dd.doccode = 8
                                AND dd.doccode1 IN (7,66,29,56,57,28,103,133,3,309,73,99,40,48,72,71,27,124,2,16,41,49,102,118,131,211,309)
                            LEFT JOIN public.advocate a ON a.diary_no = m.diary_no
                                AND a.advocate_id IN (584, 585, 610, 616, 666, 940)
                                AND a.display = 'Y'
                            LEFT JOIN public.advance_allocated aa2 ON CAST(aa2.diary_no AS bigint) = CAST(h.diary_no AS bigint)
                                AND aa2.board_type = 'J'
                            LEFT JOIN public.advance_allocated aa ON CAST(aa.diary_no AS bigint) = CAST(h.diary_no AS bigint)
                                AND aa.next_dt = '$q_next_dt'
                                AND aa.board_type = 'J'
                            LEFT JOIN public.case_remarks_multiple c ON CAST(c.diary_no AS bigint) = m.diary_no  
                                AND c.r_head IN (1, 3, 62, 181, 182, 183, 184)
                            LEFT JOIN (
                                SELECT
                                    STRING_AGG(DISTINCT r.j1::text, ',') AS rid,  
                                    r.submaster_id AS cat
                                FROM master.judge_category r
                                WHERE r.j1 IN ($presiing_judge_str)  
                                AND (r.to_dt IS NULL OR r.to_dt IS NULL)  
                                AND r.display = 'Y'
                                GROUP BY r.submaster_id
                            ) t ON mc.submaster_id = t.cat
                            WHERE rd.fil_no IS NULL
                                AND aa.diary_no IS NULL
                                AND m.active_casetype_id NOT IN (9, 10, 25, 26)
                                AND mc.display = 'Y'
                                AND mc.submaster_id NOT IN (911, 912, 914, 0, 239, 240, 241, 242, 243)
                                AND (
                                    CASE
                                        WHEN (h.listorder IN (4, 5) OR a.advocate_id IS NOT NULL) THEN TRUE
                                        ELSE
                                            CASE
                                                WHEN EXTRACT(DOW FROM '$q_next_dt'::DATE) != 3 THEN h.is_nmd != 'Y'  
                                                ELSE TRUE
                                            END
                                    END
                                )
                                AND (
                                    CASE
                                        WHEN $misc_nmd_flag = 0 THEN TRUE  
                                        ELSE 'No'  
                                    END
                                )
                                AND m.c_status = 'P'
                                AND (m.diary_no = CAST(m.conn_key AS bigint) OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL)  
                                $p_listorder 
                                $subhead_select
                                $case_type_select
                                $subject_cat_select
                                AND h.main_supp_flag = 0
                                AND h.subhead NOT IN (801, 817, 818, 819, 820, 848, 849, 850, 854, 0)
                                AND h.mainhead = 'M'
                                AND h.next_dt IS NOT NULL
                                AND h.roster_id = 0
                                AND h.brd_slno = 0
                                AND h.board_type = 'J'
                                AND h.next_dt = '$q_next_dt'  
                                AND h.listorder > 0
                                AND h.listorder != 32
                            GROUP BY m.diary_no, c.diary_no, h.subhead, t.rid, t.cat, dd.doccode1, a.advocate_id, mc.submaster_id, l.priority,
                                    h.diary_no, h.subhead, h.listorder, h.main_supp_flag, h.mainhead, h.next_dt, h.roster_id, h.brd_slno,
                                    aa2.diary_no, h.no_of_time_deleted, h.coram
                        ) a
                        ORDER BY
                            CASE WHEN subhead = 824 THEN 1 ELSE 999 END ASC,
                            CASE WHEN listorder IN (4, 5) THEN 2 ELSE 999 END ASC,
                            CASE WHEN advocate_id IS NOT NULL THEN 3 ELSE 999 END ASC,
                            CASE WHEN listorder IN (7) THEN 4 ELSE 999 END ASC,
                            CASE
                                WHEN (subhead = 804
                                    OR doccode1 IN (40, 41, 48, 49, 71, 72, 118, 131, 211, 309)
                                    OR submaster_id = 173) THEN 5
                                ELSE 999
                            END ASC,
                            CASE WHEN listorder IN (25) THEN 6 ELSE 999 END ASC,
                            CASE
                                WHEN subhead IN (810, 802, 803, 807) THEN 7
                                ELSE 999
                            END ASC,
                            CASE WHEN doccode1 IN (56, 57, 102, 73, 99, 27, 124, 2, 16) THEN 8 ELSE 999 END ASC,
                            CASE WHEN old_advance_no IS NOT NULL THEN 9 ELSE 999 END ASC,
                            CASE WHEN pre_notice IS NOT NULL THEN pre_notice ELSE 999 END ASC,
                            priority ASC,
                            a.no_of_time_deleted DESC,
                            CAST(RIGHT(CAST(diary_no AS TEXT), 4) AS INTEGER) ASC,
                            CAST(LEFT(CAST(diary_no AS TEXT), LENGTH(CAST(diary_no AS TEXT)) - 4) AS INTEGER) ASC";
        // pr($sql_c);
        $rs_c = $this->db->query($sql_c);

        $rs_c_data = $rs_c->getResultArray();
        return $rs_c_data;
    }

    public function getIsPersonendTwo($presiing_judge_str_p2, $misc_nmd_flag, $q_next_dt, $short_cat, $pre_after_notice_where_condition)
    {
        $sql_c = "SELECT * FROM (
            SELECT 
                'YES' AS is_prepon,
                CASE 
                    WHEN (c.diary_no IS NULL AND (m.fil_no_fh = '' OR m.fil_no_fh IS NULL) AND h.subhead NOT IN (813, 814)) 
                    THEN 1 
                    ELSE 2 
                END AS pre_notice,
                t.rid, 
                t.cat, 
                dd.doccode1, 
                a.advocate_id, 
                submaster_id, 
                m.conn_key AS main_key, 
                l.priority, 
                h.*,
                NULL AS old_advance_no,
                CASE 
                    WHEN submaster_id IN (173, 176, 222) OR h.subhead IN (804, 831) THEN 'Yes' 
                    ELSE 'No' 
                END AS is_short_cat
            FROM main m
            INNER JOIN heardt h ON h.diary_no = m.diary_no
            INNER JOIN master.sc_working_days s ON s.working_date = h.next_dt  
            LEFT JOIN master.listing_purpose l ON l.code = h.listorder
            LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
            LEFT JOIN mul_category mc ON mc.diary_no = m.diary_no
            LEFT JOIN docdetails dd 
                ON dd.diary_no = h.diary_no 
                AND dd.iastat = 'P' 
                AND dd.doccode = 8 
                AND dd.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 3, 309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 102, 118, 131, 211, 309)
            LEFT JOIN case_remarks_multiple c 
                ON c.diary_no::int = m.diary_no 
                AND c.r_head IN (1, 3, 62, 181, 182, 183, 184)
            LEFT JOIN advocate a 
                ON a.diary_no = m.diary_no 
                AND a.advocate_id IN (584, 585, 610, 616, 666, 940) 
                AND a.display = 'Y'
            LEFT JOIN (
                SELECT 
                    STRING_AGG(DISTINCT r.j1::text, ', ') AS rid, 
                    r.submaster_id AS cat 
                FROM master.judge_category r 
            WHERE r.j1 IN ($presiing_judge_str_p2)         AND r.to_dt is null        AND r.display = 'Y' 
                GROUP BY r.submaster_id
            ) t ON mc.submaster_id = t.cat
            WHERE s.display = 'Y' 
            AND s.is_holiday = 0 
            AND s.is_nmd = $misc_nmd_flag
            AND rd.fil_no IS NULL 
            AND m.active_casetype_id NOT IN (9, 10, 25, 26)
            AND mc.display = 'Y' 
            AND mc.submaster_id NOT IN (0, 911, 912, 914, 239, 240, 241, 242, 243)
            AND (CASE 
                WHEN h.listorder IN (4, 5) THEN TRUE
                ELSE (
                    (EXTRACT(DOW FROM '$q_next_dt'::DATE) != 3 AND 
                        h.is_nmd != 'Y') 
                    OR ($misc_nmd_flag = 0 OR $short_cat)
                ) 
            END)
            AND m.c_status = 'P' 
            AND (m.diary_no::text = m.conn_key OR m.conn_key IN ('0', '', NULL))
            AND h.main_supp_flag = 0 
            AND h.subhead NOT IN (0, 801, 817, 818, 819, 820, 848, 849, 850, 854)
            AND h.mainhead = 'M' 
            AND h.next_dt is not null
            AND h.roster_id = 0 
            AND h.brd_slno = 0 
            AND h.board_type = 'J'
            AND (CASE 
                WHEN h.subhead IN (831, 804, 829) THEN h.next_dt > CURRENT_DATE + INTERVAL '7 days' 
                ELSE h.next_dt > CURRENT_DATE + INTERVAL '2 months' 
            END)
            AND h.next_dt > '$q_next_dt'
            AND h.listorder > 0 
            AND h.listorder NOT IN (4, 5, 32)
            AND (h.no_of_time_deleted > 0 
                OR (h.no_of_time_deleted = 0 AND h.subhead IN (831, 804, 829) AND h.listorder NOT IN (4, 5, 7, 8, 32)))
            GROUP BY m.diary_no, t.rid, t.cat, dd.doccode1, a.advocate_id, submaster_id, l.priority, h.*, mc.submaster_id,
            c.diary_no,h.subhead,h.diary_no
        ) b 
        $pre_after_notice_where_condition
        ORDER BY 
            CASE WHEN is_prepon = 'NO' THEN 1 ELSE 2 END,
            COALESCE(pre_notice, 99),
            CASE WHEN pre_notice = '1' AND coram IS NOT NULL AND coram != '0' AND TRIM(coram) != '' THEN 1 ELSE 2 END,
            CASE WHEN pre_notice = 2 AND coram IS NOT NULL AND coram != '0' AND TRIM(coram) != '' THEN 1 ELSE 2 END,
            CASE WHEN subhead = 824 THEN 1 ELSE 999 END,
            CASE WHEN listorder IN (4, 5) THEN 2 ELSE 999 END,
            CASE WHEN advocate_id IS NOT NULL THEN 3 ELSE 999 END,
            CASE WHEN listorder = 7 THEN 4 ELSE 999 END,
            CASE WHEN subhead = 804 OR doccode1 IN (40, 41, 48, 49, 71, 72, 118, 131, 211, 309) OR submaster_id = 173 THEN 5 ELSE 999 END,
            CASE WHEN listorder = 25 THEN 6 ELSE 999 END,
            CASE WHEN subhead IN (810, 802, 803, 807) THEN 7 ELSE 999 END,
            CASE WHEN doccode1 IN (56, 57, 102, 73, 99, 27, 124, 2, 16) THEN 8 ELSE 999 END,
            CASE WHEN old_advance_no IS NOT NULL THEN 9 ELSE 999 END,
            priority ASC, 
            no_of_time_deleted DESC,
            CASE WHEN coram IS NOT NULL AND coram != '0' AND TRIM(coram) != '' THEN 1 ELSE 999 END,
            CAST(RIGHT(diary_no::text, 4) AS INTEGER) ASC,
            CAST(LEFT(diary_no::text, LENGTH(diary_no::text) - 4) AS INTEGER) ASC";

        $rs_c = $this->db->query($sql_c);
        $rs_c_data = $rs_c->getResultArray();
        return $rs_c_data;
    }

    public function getCoarm($coramString)
    {
        $builder = $this->db->table('master.judge');
        $builder->select("string_agg(jcode::text, ',' ORDER BY judge_seniority) AS new_coram");
        $builder->where('is_retired', 'N');
        $builder->where('display', 'Y');
        $builder->where('jtype', 'J');
        if (!empty($coramString)) {
            $jcodes = explode(',', $coramString);
            $builder->whereIn('jcode', $jcodes);
        }

        $query = $builder->get();
        $row_coram = $query->getRowArray();
        return $row_coram;
    }

    public function insertTransferOldComGenCases($q_next_dt)
    {
        $sql3 = "INSERT INTO transfer_old_com_gen_cases (diary_no, next_dt_old, next_dt_new, tentative_cl_dt_old,
                    tentative_cl_dt_new, listorder, conn_key, ent_dt, test2, listorder_new, board_type, listtype)
                    SELECT j.*, 'P' as listtype 
                    FROM (
                        SELECT 
                            diary_no, 
                            m.next_dt AS next_dt_old, 
                            '$q_next_dt'::date AS next_dt_new, 
                            m.tentative_cl_dt AS tentative_cl_dt_old, 
                            '$q_next_dt'::date AS tentative_cl_dt_new, 
                            m.listorder,
                            m.conn_key::bigint, 
                            NOW() as ent_dt, 
                            'cron_a' AS test2, 
                            (CASE WHEN m.listorder = 16 THEN 2 ELSE m.listorder END) AS listorder_new, 
                            'J' AS board_type 
                        FROM (
                            SELECT 
                                lp.priority AS lp_priority, 
                                m.diary_no_rec_date, 
                                s.stagename, 
                                s.priority, 
                                m.conn_key, 
                                active_fil_dt, 
                                active_fil_no, 
                                tentative_cl_dt, 
                                h.next_dt, 
                                h.coram, 
                                h.subhead, 
                                d.doccode1, 
                                mc.submaster_id, 
                                h.listorder, 
                                h.diary_no,
                                h.no_of_time_deleted,
                                CASE WHEN (h.subhead = 804 OR mc.submaster_id = 173 OR d.doccode1 IN (40, 41, 48, 49, 71, 72, 118, 131, 211, 309)) THEN 1 ELSE 0 END AS bail,
                                CASE WHEN a.advocate_id IN (584, 585, 610, 616, 666, 940) THEN 1 ELSE 0 END AS inperson,
                                CASE WHEN d.doccode1 IN (7, 66, 29, 56, 57, 28, 102, 103, 133, 226, 3, 73, 99, 27, 124, 2, 16) THEN 1 ELSE 0 END AS schm
                            FROM 
                                advance_allocated ad_al
                            LEFT JOIN heardt h ON h.diary_no = ad_al.diary_no::int
                            LEFT JOIN master.listing_purpose lp ON lp.code = h.listorder AND lp.display = 'Y'
                            LEFT JOIN master.subheading s ON s.stagecode = h.subhead AND s.listtype = 'M' AND s.display = 'Y'
                            INNER JOIN main m ON m.diary_no = h.diary_no
                            LEFT JOIN docdetails d ON d.diary_no = m.diary_no AND d.display = 'Y' AND d.iastat = 'P' AND d.doccode = 8
                                AND d.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 226, 3, 309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 71, 72, 102, 118, 131, 211, 309)
                            LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no AND mc.display = 'Y'
                                AND mc.submaster_id != 911 AND mc.submaster_id != 912 AND mc.submaster_id != 239
                                AND mc.submaster_id != 240 AND mc.submaster_id != 241 AND mc.submaster_id != 242 AND mc.submaster_id != 243
                            LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                            LEFT JOIN advocate a ON a.diary_no = m.diary_no AND a.advocate_id IN (584, 585, 610, 616, 666, 940) AND a.display = 'Y'
                            WHERE 
                                rd.fil_no IS NULL 
                                AND mc.diary_no IS NOT NULL 
                                AND m.c_status = 'P'
                                AND h.board_type = 'J' 
                                AND h.listorder != 32 
                                AND h.mainhead = 'M' 
                                AND h.clno = 0 
                                AND h.brd_slno = 0 
                                AND h.main_supp_flag = 0
                                AND h.next_dt > '$q_next_dt'::date
                                AND ad_al.next_dt = '$q_next_dt'::date 
                                AND ad_al.board_type = 'J'
                            GROUP BY 
                                h.diary_no,lp.priority,m.diary_no_rec_date,s.stagename,s.priority,m.conn_key,m.active_fil_dt,m.active_fil_no
                            ,d.doccode1,mc.submaster_id,a.advocate_id
                        ) m 
                    ) j
                    LEFT JOIN transfer_old_com_gen_cases l ON j.diary_no = l.diary_no
                        AND l.next_dt_old = j.next_dt_old
                        AND l.next_dt_new = j.next_dt_new
                    WHERE l.diary_no IS NULL";
        $res3 = $this->db->query($sql3);
    }

    public function insLastHeardt($q_next_dt)
    {


        $sql4 = "INSERT INTO last_heardt (diary_no, conn_key, next_dt, mainhead, subhead, clno, brd_slno, roster_id, judges, coram, board_type, usercode, ent_dt, module_id, mainhead_n, subhead_n,
    main_supp_flag, listorder, tentative_cl_dt, lastorder, listed_ia, sitting_judges,
    list_before_remark, is_nmd, no_of_time_deleted)
    SELECT j.* 
    FROM (
        SELECT
            m.diary_no, 
            m.conn_key, 
            m.next_dt, 
            m.mainhead, 
            m.subhead, 
            m.clno, 
            m.brd_slno, 
            m.roster_id, 
            m.judges, 
            m.coram,
            m.board_type::last_heardt_board_type, 
            m.usercode, 
            m.ent_dt, 
            m.module_id, 
            m.mainhead_n, 
            m.subhead_n, 
            m.main_supp_flag,
            m.listorder, 
            m.tentative_cl_dt, 
            m.lastorder, 
            m.listed_ia, 
            m.sitting_judges,
            m.list_before_remark, 
            m.is_nmd, 
            m.no_of_time_deleted 
        FROM (
            SELECT 
                lp.priority AS lp_priority, 
                m.diary_no_rec_date, 
                h.diary_no, 
                h.conn_key, 
                h.next_dt, 
                h.mainhead, 
                h.subhead, 
                h.clno, 
                h.brd_slno, 
                h.roster_id, 
                h.judges, 
                h.coram, 
                h.board_type, 
                h.usercode, 
                h.ent_dt, 
                h.module_id,
                h.mainhead_n, 
                h.subhead_n, 
                h.main_supp_flag, 
                h.listorder, 
                h.tentative_cl_dt, 
                m.lastorder, 
                h.listed_ia, 
                h.sitting_judges,
                h.list_before_remark, 
                h.is_nmd,
                s.stagename, 
                s.priority, 
                active_fil_dt, 
                active_fil_no, 
                d.doccode1, 
                mc.submaster_id,
                h.no_of_time_deleted,
                CASE WHEN (h.subhead = 804 OR mc.submaster_id = 173 OR d.doccode1 IN (40, 41, 48, 49, 71, 72, 118, 131, 211, 309)) THEN 1 ELSE 0 END AS bail,
                CASE WHEN a.advocate_id IN (584, 585, 610, 616, 666, 940) THEN 1 ELSE 0 END AS inperson,
                CASE WHEN d.doccode1 IN (7, 66, 29, 56, 57, 28, 102, 103, 133, 226, 3, 73, 99, 27, 124, 2, 16) THEN 1 ELSE 0 END AS schm
            FROM 
                advance_allocated ad_al
            LEFT JOIN heardt h ON h.diary_no = ad_al.diary_no::int
            LEFT JOIN master.listing_purpose lp ON lp.code = h.listorder AND lp.display = 'Y'
            LEFT JOIN master.subheading s ON s.stagecode = h.subhead AND s.listtype = 'M' AND s.display = 'Y'
            INNER JOIN main m ON m.diary_no = h.diary_no
            LEFT JOIN docdetails d ON d.diary_no = m.diary_no AND d.display = 'Y' AND d.iastat = 'P' AND d.doccode = 8
                AND d.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 226, 3, 309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 71, 72, 102, 118, 131, 211, 309)
            LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no AND mc.display = 'Y'
                AND mc.submaster_id != 911 AND mc.submaster_id != 912 AND mc.submaster_id != 239
                AND mc.submaster_id != 240 AND mc.submaster_id != 241 AND mc.submaster_id != 242 AND mc.submaster_id != 243
            LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
            LEFT JOIN advocate a ON a.diary_no = m.diary_no AND a.advocate_id IN (584, 585, 610, 616, 666, 940) AND a.display = 'Y'
            WHERE 
                rd.fil_no IS NULL 
                AND mc.diary_no IS NOT NULL 
                AND m.c_status = 'P' 
                AND h.board_type = 'J' 
                AND h.listorder != 32 
                AND h.mainhead = 'M' 
                AND h.clno = 0 
                AND h.brd_slno = 0 
                AND h.main_supp_flag = 0
                AND h.next_dt > '$q_next_dt'::date
                AND ad_al.next_dt = '$q_next_dt'::date 
                AND ad_al.board_type = 'J'
            GROUP BY 
                h.diary_no,lp.priority,m.diary_no_rec_date,m.lastorder,s.stagename,s.priority,m.active_fil_dt,m.active_fil_no
            ,d.doccode1,mc.submaster_id,a.advocate_id
        ) m
        ) j
        LEFT JOIN last_heardt l ON j.diary_no = l.diary_no
            AND l.next_dt = j.next_dt
            AND l.listorder = j.listorder
            AND l.mainhead = j.mainhead
            AND l.subhead = j.subhead
            AND l.roster_id = j.roster_id
            AND l.judges = j.judges
            AND l.clno = j.clno
            AND l.main_supp_flag = j.main_supp_flag
            AND l.ent_dt = j.ent_dt
            AND (l.bench_flag = '' OR l.bench_flag IS NULL)
        WHERE l.diary_no IS NULL";
        $res4 = $this->db->query($sql4);
    }

    public function updateHeardt($q_next_dt)
    {

        $sql5 = "UPDATE heardt h
                SET next_dt = '$q_next_dt',
                    tentative_cl_dt = '$q_next_dt',
                    usercode = '1',
                    ent_dt = NOW(),
                    module_id = '28',
                    listorder = CASE WHEN t0.listorder = 16 THEN '2' ELSE t0.listorder END
                FROM (
                    SELECT
                        m.diary_no,
                        m.conn_key,
                        m.next_dt,
                        m.mainhead,
                        m.subhead,
                        m.clno,
                        m.brd_slno,
                        m.roster_id,
                        m.judges,
                        m.coram,
                        m.board_type,
                        m.usercode,
                        m.ent_dt,
                        m.module_id,
                        m.mainhead_n,
                        m.subhead_n,
                        m.main_supp_flag,
                        m.listorder,
                        m.tentative_cl_dt,
                        m.lastorder,
                        m.listed_ia,
                        m.sitting_judges,
                        m.is_nmd,
                        m.no_of_time_deleted
                    FROM (
                        SELECT
                            lp.priority AS lp_priority,
                            m.diary_no_rec_date,
                            h.diary_no,
                            h.conn_key,
                            h.next_dt,
                            h.mainhead,
                            h.subhead,
                            h.clno,
                            h.brd_slno,
                            h.roster_id,
                            h.judges,
                            h.coram,
                            h.board_type,
                            h.usercode,
                            h.ent_dt,
                            h.module_id,
                            h.mainhead_n,
                            h.subhead_n,
                            h.main_supp_flag,
                            h.listorder,
                            h.tentative_cl_dt,
                            m.lastorder,
                            h.listed_ia,
                            h.sitting_judges,
                            h.is_nmd,
                            h.no_of_time_deleted,
                            s.stagename,
                            s.priority,
                            active_fil_dt,
                            active_fil_no,
                            d.doccode1,
                            mc.submaster_id,
                            CASE WHEN (h.subhead = 804 OR mc.submaster_id = 173 OR d.doccode1 IN (40, 41, 48, 49, 71, 72, 118, 131, 211, 309)) THEN 1 ELSE 0 END AS bail,
                            CASE WHEN a.advocate_id IN (584, 585, 610, 616, 666, 940) THEN 1 ELSE 0 END AS inperson,
                            CASE WHEN d.doccode1 IN (7, 66, 29, 56, 57, 28, 102, 103, 133, 226, 3, 73, 99, 27, 124, 2, 16) THEN 1 ELSE 0 END AS schm
                        FROM advance_allocated ad_al
                        LEFT JOIN heardt h ON h.diary_no::bigint = ad_al.diary_no::bigint
                        LEFT JOIN master.listing_purpose lp ON lp.code = h.listorder AND lp.display = 'Y'
                        LEFT JOIN master.subheading s ON s.stagecode = h.subhead AND s.listtype = 'M' AND s.display = 'Y'
                        INNER JOIN main m ON m.diary_no = h.diary_no
                        LEFT JOIN docdetails d ON d.diary_no = m.diary_no AND d.display = 'Y' AND d.iastat = 'P' AND d.doccode = 8
                            AND d.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 226, 3, 309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 71, 72, 102, 118, 131, 211, 309)
                        LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no AND mc.display = 'Y'
                            AND mc.submaster_id NOT IN (911, 912, 239, 240, 241, 242, 243)
                        LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                        LEFT JOIN advocate a ON a.diary_no = m.diary_no AND a.advocate_id IN (584, 585, 610, 616, 666, 940) AND a.display = 'Y'
                        WHERE rd.fil_no IS NULL
                        AND mc.diary_no IS NOT NULL
                        AND m.c_status = 'P'
                        AND h.board_type = 'J'
                        AND h.listorder != 32
                        AND h.mainhead = 'M'
                        AND h.clno = 0
                        AND h.brd_slno = 0
                        AND h.main_supp_flag = 0
                        AND h.next_dt > '$q_next_dt'
                        AND ad_al.next_dt = '$q_next_dt'
                        AND ad_al.board_type = 'J'
                        GROUP BY h.diary_no,lp.priority,m.diary_no_rec_date,m.lastorder,s.stagename,s.priority,m.active_fil_dt,m.active_fil_no,d.doccode1,mc.submaster_id
                        ,a.advocate_id
                    ) m
                ) t0
                WHERE t0.diary_no = h.diary_no";

        $res5 = $this->db->query($sql5);
    }

    public function getEliminatePrint($q_next_dt)
    {

        $eliminate_print = "SELECT 
                    m.diary_no, 
                    m.conn_key, 
                    m.reg_no_display, 
                    m.res_name, 
                    m.pet_name, 
                    l.purpose, 
                    e.reason,
                    STRING_AGG(j.abbreviation, ',' ORDER BY j.judge_seniority) AS judge_coram
                FROM 
                    eliminated_cases e
                INNER JOIN 
                    main m ON m.diary_no = e.diary_no
                LEFT JOIN 
                    master.listing_purpose l ON l.code = e.listorder
                LEFT JOIN 
                    heardt h ON e.diary_no = h.diary_no
                LEFT JOIN 
                    master.judge j ON j.jcode::text = ANY(string_to_array(h.coram, ','))
                    AND j.display = 'Y' 
                    AND j.is_retired = 'N'
                LEFT JOIN 
                    advance_allocated aa ON aa.diary_no::int = m.diary_no 
                    AND aa.next_dt = '$q_next_dt'
                WHERE 
                    aa.diary_no IS NULL 
                    AND m.c_status = 'P' 
                    AND e.next_dt_old = '$q_next_dt'
                    AND e.listtype = 'A' 
                    AND e.board_type = 'J' 
                    AND e.reason != 'DUE TO EXCESS MATTERS'
                GROUP BY 
                    m.diary_no, m.conn_key, m.reg_no_display, m.res_name, m.pet_name, l.purpose, e.reason
                ORDER BY 
                    m.diary_no_rec_date";
        $res_eliminate_print = $this->db->query($eliminate_print);
        $result = $res_eliminate_print->getResultArray();
        return $result;
    }
    public function getDairyWithConn_k($dairy_with_conn_k)
    {

        $sql_nnn = "SELECT abc.*, 
    STRING_AGG( j.abbreviation,', ' ORDER BY j.judge_seniority) AS judge_name
FROM (
    -- Union Query 1
    SELECT 
        a.diary_no, 
        n.org_judge_id AS j1, 
        'N' AS nb_remark 
    FROM advocate a
    INNER JOIN master.ntl_judge n ON n.org_advocate_id = a.advocate_id
    WHERE 
        a.diary_no IN ($dairy_with_conn_k) 
        AND n.display = 'Y' 
        AND a.display = 'Y'
    
    UNION
    
    -- Union Query 2
    SELECT 
        a.diary_no, 
        n.org_judge_id AS j1, 
        'N' AS nb_remark 
    FROM party a
    INNER JOIN ntl_judge_dept n ON a.deptcode = n.dept_id
    WHERE 
        a.diary_no IN ($dairy_with_conn_k) 
        AND a.pflag != 'T' 
        AND n.display = 'Y'
    
    UNION
    
    -- Union Query 3
    SELECT 
        n.diary_no::int , 
        n.j1, 
        n.notbef AS nb_remark 
    FROM not_before n
    INNER JOIN master.judge j ON j.jcode = n.j1
    WHERE 
        n.diary_no::int IN ($dairy_with_conn_k) 
        AND j.is_retired = 'N'
    
    UNION
    
    -- Union Query 4
    SELECT 
        diary_no, 
        n.org_judge_id AS j1, 
        'N' AS nb_remark 
    FROM (
        SELECT 
            diary_no, 
            s.id 
        FROM (
            SELECT 
                c.diary_no, 
                s.id, 
                sub_name1
            FROM 
                mul_category c
            INNER JOIN submaster s ON s.id = c.submaster_id
            WHERE 
                c.diary_no IN ($dairy_with_conn_k) 
                AND c.display = 'Y' 
                AND s.display = 'Y'
        ) a
        INNER JOIN submaster s ON s.sub_name1 = a.sub_name1 
        WHERE s.flag = 's'
    ) a
    INNER JOIN master.ntl_judge_category n ON n.cat_id = a.id
    WHERE n.display = 'Y'
) abc
INNER JOIN master.judge j ON j.jcode = abc.j1 AND j.is_retired = 'N'
GROUP BY abc.diary_no, abc.nb_remark,abc.j1
ORDER BY abc.nb_remark, abc.diary_no";

        $res_nnn = $this->db->query($sql_nnn);
        $res_nnn = $res_nnn->getResultArray();
        return $res_nnn;
    }



    public function eliminate_advance_auto($list_dt)
    {


        $list_dt = $list_dt;

        $builder = $this->db->table('advance_cl_printed');
        $builder->select('next_dt AS printed_max_dt')
            ->where('display', 'Y')
            ->where('board_type', 'J')
            ->where('next_dt >', date('Y-m-d'))
            ->where('next_dt', $list_dt)
            ->orderBy('next_dt', 'DESC')
            ->limit(1);
        $query = $builder->get();
        $res = $query->getResultArray();




        if (!empty($res))
        {
            foreach ($res as $publised)
            {
                $from_dt = $list_dt;
                $to_dt = $publised['printed_max_dt'];
                $sql1nmd = "SELECT 0 AS is_nmd UNION SELECT 1 AS is_nmd;";
                $query1 = $this->db->query($sql1nmd);
                $res1nmd = $query1->getResultArray();



                if (!empty($res1nmd))
                {
                    foreach ($res1nmd as $nmd_misc)
                    {
                        if ($nmd_misc['is_nmd'] == 0) {
                            $is_nmd = $nmd_misc['is_nmd'];
                            $builder = $this->db->table('advance_cl_printed a');
                            $builder->join('master.sc_working_days s', 's.working_date = a.next_dt');
                            $builder->select('a.next_dt')
                                ->where('a.board_type', 'J')
                                ->where('s.is_nmd', $is_nmd)
                                ->orderBy('a.next_dt', 'DESC')
                                ->limit(1, 3); // OFFSET 3

                            $res_last_adv = $builder->get();
                            $row_last_adv = $res_last_adv->getRowArray();

                            $limit_of_days_msc_nmd = "1";
                            $misc_nmd_sq1 = " ";
                            $misc_nmd_sq2 = "0";
                            $misc_nmd_sq3 = "N";
                            $misc_nmd_sq4 = "  ";
                            $misc_nmd_sq5 = "  ";
                        } else {

                            $is_nmd = $nmd_misc['is_nmd'];
                            $builder = $this->db->table('advance_cl_printed a');
                            $builder->join('master.sc_working_days s', 's.working_date = a.next_dt');
                            $builder->select('a.next_dt')
                                ->where('a.board_type', 'J')
                                ->where('s.is_nmd', $is_nmd)
                                ->orderBy('a.next_dt', 'DESC')
                                ->limit(1, 6); // OFFSET 6
                            $res_last_adv = $builder->get();
                            $row_last_adv = $res_last_adv->getRowArray();

                            $limit_of_days_msc_nmd = "0";
                            $misc_nmd_sq1 = "";
                            $misc_nmd_sq2 = "1";
                            $misc_nmd_sq3 = "Y";
                            $misc_nmd_sq4 = " AND (mc.submaster_id IN (173,176,222) or h.subhead in (804,831) ) ";
                            $misc_nmd_sq5 = " AND (
                                                CASE 
                                                    WHEN h.listorder IN (4,5) THEN TRUE  
                                                    ELSE (mc.submaster_id IN (173,176,222) OR h.subhead IN (804,831))
                                                END
                                            )";
                        }


                        $last_advance_dates = $row_last_adv['next_dt'];
                        $working_dt_sc = "0";
                        $while_sno = 1;
                        $qry_str_j='';
                        $qry_str_j_not='';

                        $qry_str_j_not = " AND (h.coram = 0 OR trim(h.coram) = '' OR h.coram is null OR ( ";

                        $builder1 = $this->db->table('judge_group j');
                        $builder1->select('p1, p2, p3, 
                        CASE WHEN old_limit > 24 THEN 20 ELSE (old_limit - 4) END AS old_limit', false);
                        $builder1->where('j.display', 'Y');
                        $builder1->where('j.to_dt IS NULL');

                        $subQuery1 = $builder1->getCompiledSelect();

                        $subQuery2 = $this->db->table('(SELECT 0 AS p1, 0 AS p2, 0 AS p3, 20 AS old_limit) AS temp', false)
                            ->getCompiledSelect();

                        $query = $this->db->query("SELECT t.* FROM ($subQuery1 UNION $subQuery2) t 
                           ORDER BY 
                           CASE WHEN p1 != 0 THEN 1 ELSE 2 END ASC, 
                           p1 ASC");

                        $rslt_coram = $query->getResultArray();
                        
                       



                        $top_4_court_matching = 0;
                        foreach ($rslt_coram as $key=>$ros_coram)
                        {
                            if ($while_sno != 1 and $ros_coram['p1'] != 0) {
                                $qry_str_j_not .= " AND ";
                            }
                            $while_sno++;
                            if ($ros_coram['p1'] != 0) {

                                $qry_str_j = " AND ( (split_part(h.coram, ',', 1)) = '$ros_coram[p1]' OR (split_part(h.coram, ',', 1)) = '$ros_coram[p2]' ";
                                $qry_str_j_not .= " AND (split_part(h.coram, ',', 1)) != '$ros_coram[p1]' AND (split_part(h.coram, ',', 1)) != '$ros_coram[p2]' ";

                                if ($ros_coram['p3'] > 0) {
                                    $qry_str_j .= " OR (split_part(h.coram, ',', 1)) = '$ros_coram[p3]' ";
                                    $qry_str_j_not .= " AND (split_part(h.coram, ',', 1)) != '$ros_coram[p3]' ";
                                }

                                $qry_str_j .= " ) ";
                            }

                            if ($ros_coram['p1'] == 0) {
                                $qry_str_j_not .= " ) ) ";
                                //$qry_str_j = $qry_str_j_not;
                            }


                            for ($i = 0; $i <= 2; $i++){

                                //$top_4senior_judges_str;
                                if ($i == 1) {
                                    $lp_qry = " subhead != 817 AND listorder NOT IN (49,4,5,32) 
                                                AND (listorder IN (25,7) OR subhead IN (824,810,803,802,807,804,831) 
                                                OR bail > 0 OR inperson > 0 OR old_adv_dt IS NOT NULL) ";

                                    $subhead_qry = "";
                                    $where_limit_condition = " ORDER BY next_dt OFFSET $limit_of_days_msc_nmd FETCH FIRST 1 ROW ONLY";
                                } else if ($i == 2) {  // Corrected to $i == 2
                                    $lp_qry = " (listorder IN (8,24,48) OR schm IS NOT NULL) ";

                                    $subhead_qry = " AND NOT (listorder IN (49,4,5,32) OR subhead IN (817,824,810,803,802,807,804,831) OR bail > 0 OR inperson > 0) ";
                                    $where_limit_condition = " WHERE (alow_tot_updt > 0 OR alow_mndt_updt > 0) ORDER BY next_dt";
                                } else {
                                    $lp_qry = " listorder IN (21,2,16) ";  // include after some time, 49
                                    $subhead_qry = " AND NOT (listorder IN (49,4,5,32) OR subhead IN (817,824,810,803,802,807,804,831) OR bail > 0 OR inperson > 0) ";
                                    $where_limit_condition = " WHERE (alow_tot_updt > 0 OR alow_mndt_updt > 0) ORDER BY next_dt";
                                }


                                $loop_nos_of_time = 1;
                                if ($top_4_court_matching == 1 and $nmd_misc['is_nmd'] == 1) {
                                    $loop_nos_of_time = 2;
                                    //echo "<br>loop_nos_of_time : ".$loop_nos_of_time;
                                }
                                for ($k = 1; $k <= $loop_nos_of_time; $k++) {
                                    if ($nmd_misc['is_nmd'] == 1) {
                                        $limit_of_days_msc_nmd = "0";
                                        $misc_nmd_sq2 = "1";
                                        $misc_nmd_sq4 = " AND (mc.submaster_id IN (173,176,222) or h.subhead in (804,831) ) ";
                                        $misc_nmd_sq5 = " AND (
                                                            CASE 
                                                                WHEN h.listorder IN (4,5) THEN TRUE  -- Always true when listorder is 4 or 5
                                                                ELSE (mc.submaster_id IN (173,176,222) OR h.subhead IN (804,831))
                                                            END
                                                        )";
                                    }
                                    if ($nmd_misc['is_nmd'] == 0) {
                                        $limit_of_days_msc_nmd = "1";
                                        $misc_nmd_sq2 = "0";
                                        $misc_nmd_sq4 = "  ";
                                        $misc_nmd_sq5 = "  ";
                                    }



                                    $sq_gt = "SELECT COUNT(m.diary_no) AS tobe_trans 
                                                    FROM (
                                                        SELECT 
                                                            ad_al2.next_dt AS old_adv_dt, 
                                                            s.stagename, 
                                                            s.priority, 
                                                            active_fil_dt, 
                                                            active_fil_no, 
                                                            h.next_dt, 
                                                            h.coram, 
                                                            h.subhead, 
                                                            d.doccode1, 
                                                            mc.submaster_id, 
                                                            h.listorder, 
                                                            h.diary_no,
                                                            CASE 
                                                                WHEN (h.subhead = 804 OR mc.submaster_id = 173 OR d.doccode1 IN (40, 41, 48, 49, 71, 72, 118, 131, 211, 309)) THEN 1 
                                                                ELSE 0 
                                                            END AS bail,
                                                            CASE 
                                                                WHEN a.advocate_id IN (584, 585, 610, 616, 666, 940) THEN 1 
                                                                ELSE 0 
                                                            END AS inperson,
                                                            CASE 
                                                                WHEN d.doccode1 IN (56, 57, 102, 73, 99, 27, 124, 2, 16) THEN 1 
                                                                ELSE 0 
                                                            END AS schm 
                                                        FROM master.sc_working_days wd      
                                                        LEFT JOIN heardt h ON h.next_dt = wd.working_date
                                                        LEFT JOIN master.listing_purpose lp ON lp.code = h.listorder AND lp.display = 'Y'
                                                        LEFT JOIN master.subheading s ON s.stagecode = h.subhead AND s.listtype = 'M' AND s.display = 'Y'
                                                        INNER JOIN main m ON m.diary_no = h.diary_no
                                                        LEFT JOIN docdetails d ON d.diary_no = m.diary_no AND d.display = 'Y' AND d.iastat = 'P' AND d.doccode = 8 
                                                            AND d.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 226, 3, 309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 71, 72, 102, 118, 131, 211, 309) 
                                                        LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no AND mc.display = 'Y' 
                                                            AND mc.submaster_id NOT IN (911, 912, 239, 240, 241, 242, 243)
                                                        LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                                                        LEFT JOIN advocate a ON a.diary_no = m.diary_no AND a.advocate_id IN (584, 585, 610, 616, 666, 940) AND a.display = 'Y'
                                                        LEFT JOIN advance_allocated ad_al ON ad_al.diary_no::bigint = h.diary_no AND ad_al.next_dt = '$from_dt' AND ad_al.board_type = 'J'
                                                        LEFT JOIN advanced_drop_note ad_d ON ad_d.diary_no = ad_al.diary_no AND ad_al.next_dt = ad_d.cl_date
                                                        LEFT JOIN advance_allocated ad_al2 ON ad_al2.diary_no::bigint = h.diary_no AND ad_al2.next_dt >= '$last_advance_dates' AND ad_al2.board_type = 'J'
                                                        WHERE ad_al.diary_no IS NULL 
                                                        AND ad_d.diary_no IS NULL 
                                                        AND rd.fil_no IS NULL 
                                                        AND mc.diary_no IS NOT NULL 
                                                        AND m.c_status = 'P' 
                                                        AND (m.diary_no = m.conn_key::bigint OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
                                                        AND wd.display = 'Y' 
                                                        AND wd.working_date <= '$publised[printed_max_dt]'
                                                        AND h.board_type = 'J' 
                                                        AND h.listorder != 32 
                                                        AND h.mainhead = 'M' 
                                                        AND h.clno = 0 
                                                        AND h.brd_slno = 0 
                                                        AND h.main_supp_flag = 0
                                                         $misc_nmd_sq4
                                                        AND h.next_dt = '$from_dt' 
                                                       $qry_str_j
                                                        GROUP BY h.diary_no,ad_al2.next_dt,s.stagename,s.priority ,m.active_fil_dt ,m.active_fil_no,d.doccode1,mc.submaster_id,h.listorder, 
                                                            h.diary_no,a.advocate_id
                                                    ) m 
                                                    WHERE $lp_qry 
                                                    $subhead_qry";
                                                    // if($key==16)
                                                    // {
                                                    //     //echo  $qry_str_j;
                                                    //     pr($sq_gt);

                                                    // }
                                                   
                                                   
                                    $res1 = $this->db->query($sq_gt);
                                    $ro1 = $res1->getRowArray();

                                    $tobe_trans = $ro1['tobe_trans'];




                                    // Remove  if condition
                                     if ($tobe_trans > 0)
                                     {




                                    $builder = $this->db->table('master.sc_working_days s');
                                    $builder->select('s.working_date');
                                    $builder->join('advance_cl_printed a', 'a.next_dt = s.working_date AND a.display = \'Y\' AND a.board_type = \'J\'', 'left');
                                    $builder->where('s.sec_list_dt <=', 'CURRENT_DATE', false);
                                    $builder->where('s.sec_list_dt IS NOT NULL', null, false);
                                    $builder->where('s.is_holiday', 0);
                                    $builder->where('s.display', 'Y');
                                    $builder->where('a.next_dt IS NULL', null, false);
                                    $subQuery1 = $builder->getCompiledSelect();
                                    $subQuery2 = $this->db->table('(SELECT CURRENT_DATE + INTERVAL \'14 days\' AS working_date) as temp', false)
                                        ->getCompiledSelect();

                                    $query = $this->db->query("SELECT working_date AS printed_max_dt 
                                                                    FROM ($subQuery1 UNION $subQuery2) t 
                                                                    ORDER BY working_date DESC 
                                                                    LIMIT 1 OFFSET 0");

                                    $res_rl_dt = $query->getRowArray();




                                    if (!empty($res_rl_dt)) {
                                        $ro_rl_dt = $res_rl_dt;
                                        $printed_max_dt_rl_dt = $ro_rl_dt['printed_max_dt'];
                                    } else {
                                        $printed_max_dt_rl_dt = $publised['printed_max_dt'];
                                    }

                                    $dt_range_from = date('Y-m-d', strtotime(date($printed_max_dt_rl_dt) . '+1day'));
                                    $dt_range_to = date('Y-m-d', strtotime(date($printed_max_dt_rl_dt) . '+1000day'));
                                    if ($misc_nmd_sq2 == 0) {
                                        $nonmand_limit_for_tot = 10;
                                        $mand_limit_for_tot = 10;
                                        //for justice RG, n.v. ramana and S.A. Bobde

                                        $define_limi1 = $ros_coram['old_limit'];
                                        $define_limi2 = $ros_coram['old_limit'];
                                        $query_for_nmd1 = "";
                                        $query_for_nmd2 = "";
                                        $query_for_nmd3 = "";
                                    } else {
                                        $nonmand_limit_for_tot = 10;
                                        $mand_limit_for_tot = 10;
                                        //for nmd
                                        $define_limi1 = 10;
                                        $define_limi2 = 10;
                                        $query_for_nmd1 = "";
                                        $query_for_nmd2 = "";
                                        $query_for_nmd3 = "";
                                    }

                                    //Anuj1 query correct with variable
                                    $sql_sno = "WITH inner_data AS (
                                    SELECT
                                        wd.working_date   AS next_dt,
                                        t.coram,
                                        t.subhead,
                                        t.doccode1,
                                        t.submaster_id,
                                        t.listorder,
                                        t.diary_no,
                                        t.bail,
                                        t.inperson,
                                        t.schm
                                    FROM master.sc_working_days wd
                                    LEFT JOIN (
                                        SELECT
                                        h.next_dt,
                                        h.coram,
                                        h.subhead,
                                        d.doccode1,
                                        mc.submaster_id,
                                        h.listorder,
                                        h.diary_no,
                                        CASE
                                            WHEN (h.subhead = 804
                                                OR mc.submaster_id = 173
                                                OR d.doccode1 IN (40,41,48,49,71,72,118,131,211,309))
                                            THEN 1 ELSE 0
                                        END AS bail,
                                        CASE
                                            WHEN a.advocate_id IN (584,585,610,616,666,940) THEN 1 ELSE 0
                                        END AS inperson,
                                        CASE
                                            WHEN d.doccode1 IN (56,57,102,73,99,27,124,2,16) THEN 1 ELSE 0
                                        END AS schm
                                        FROM master.sc_working_days wd
                                        LEFT JOIN heardt     h  ON h.next_dt = wd.working_date
                                        INNER JOIN main      m  ON m.diary_no = h.diary_no
                                        LEFT JOIN docdetails d  ON d.diary_no = m.diary_no
                                                            AND d.display  = 'Y'
                                                            AND d.iastat   = 'P'
                                                            AND d.doccode  = 8
                                                            AND d.doccode1 IN (7,66,29,56,57,28,103,133,226,3,309,73,99,
                                                                            40,48,72,71,27,124,2,16,41,49,71,72,102,118,131,211,309)
                                        LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no
                                                            AND mc.display    = 'Y'
                                                            AND mc.submaster_id NOT IN (911,912,239,240,241,242,243)
                                        LEFT JOIN rgo_default rd  ON rd.fil_no = h.diary_no
                                                            AND rd.remove_def = 'N'
                                        LEFT JOIN advocate a     ON a.diary_no    = m.diary_no
                                                            AND a.advocate_id IN (584,585,610,616,666,940)
                                                            AND a.display      = 'Y'
                                        WHERE
                                        rd.fil_no IS NULL
                                        AND mc.diary_no IS NOT NULL
                                        AND m.c_status = 'P'
                                        AND ( m.diary_no = m.conn_key::int
                                                OR m.conn_key = '' 
                                                OR m.conn_key IS NULL 
                                                OR m.conn_key = '0' )
                                        AND wd.display    = 'Y'
                                        AND wd.is_holiday = 0
                                        AND wd.is_nmd     = $misc_nmd_sq2
                                        AND h.board_type    = 'J'
                                        AND h.mainhead      = 'M'
                                        AND h.clno          = 0
                                        AND h.brd_slno      = 0
                                        AND h.main_supp_flag= 0
                                        /* optional extra NMD filter: */
                                        $misc_nmd_sq5
                                        /* skip Wednesdays unless is_nmd = 'Y': */
                                        AND (
                                            (EXTRACT(DOW FROM h.next_dt)::int + 1 <> 3 AND h.is_nmd <> 'Y')
                                            OR (EXTRACT(DOW FROM h.next_dt)::int + 1 = 3)
                                        )
                                        /* date range */
                                        AND h.next_dt BETWEEN '$dt_range_from' AND '$dt_range_to'
                                        /* coram filter */
                                        AND (
                                            split_part(h.coram, ',', 1) IN ('219','286','294')
                                        )
                                        AND h.listorder <> 32
                                        AND h.subhead IN (824,810,803,802,807,804,831,808,811,812,813,814,815,816)
                                        GROUP BY h.next_dt, h.coram, h.subhead,
                                                d.doccode1, mc.submaster_id,
                                                h.listorder, h.diary_no, a.advocate_id
                                    ) t ON t.next_dt = wd.working_date
                                    WHERE
                                        wd.working_date BETWEEN '$dt_range_from' AND '$dt_range_to'
                                        AND wd.display    = 'Y'
                                        AND wd.is_holiday = 0
                                        AND wd.is_nmd     = $misc_nmd_sq2
                                    ),
                                    aggregated AS (
                                    SELECT
                                        next_dt,
                                        COUNT(diary_no)                                                             AS tot_avl,
                                        SUM(CASE WHEN listorder IN (4,5,7) THEN 1 ELSE 0 END)                       AS fd_not_list,
                                        SUM(CASE WHEN listorder = 25 THEN 1 ELSE 0 END)                             AS frs_adj_not_list,
                                        SUM(CASE WHEN (inperson <> 1 AND bail <> 1 AND listorder = 8) THEN 1 ELSE 0 END)   AS aw_not_list,
                                        SUM(CASE WHEN (inperson <> 1 AND bail <> 1 AND listorder = 48) THEN 1 ELSE 0 END)  AS nradj_not_list,
                                        SUM(CASE WHEN (inperson = 1 AND listorder NOT IN (4,5,7,25)) THEN 1 ELSE 0 END)     AS inperson_not_list,
                                        SUM(CASE WHEN (bail = 1 AND inperson <> 1 AND listorder NOT IN (4,5,7,25)) THEN 1 ELSE 0 END) AS bail_not_list,
                                        SUM(CASE WHEN (schm = 1 AND inperson <> 1 AND bail <> 1 AND listorder NOT IN (4,5,7,25,8,48)) THEN 1 ELSE 0 END) AS imp_ia_not_list,
                                        SUM(CASE WHEN (subhead IN (813,814) AND inperson = 0 AND bail = 0 AND schm = 0 AND listorder NOT IN (48,8,4,5,7,25)) THEN 1 ELSE 0 END) AS notice_not_list,
                                        SUM(CASE WHEN (subhead IN (815,816) AND inperson = 0 AND bail = 0 AND schm = 0 AND listorder NOT IN (48,8,4,5,7,25)) THEN 1 ELSE 0 END) AS fdisp_not_list,
                                        SUM(CASE WHEN (subhead NOT IN (813,814,815,816) AND inperson = 0 AND bail = 0 AND schm = 0 AND listorder NOT IN (48,8,4,5,7,25)) THEN 1 ELSE 0 END) AS oth_not_list
                                    FROM inner_data
                                    GROUP BY next_dt
                                    ),
                                    numbered AS (
                                    SELECT
                                        next_dt,
                                        tot_avl,
                                        fd_not_list,
                                        frs_adj_not_list,
                                        aw_not_list,
                                        nradj_not_list,
                                        inperson_not_list,
                                        bail_not_list,
                                        imp_ia_not_list,
                                        notice_not_list,
                                        fdisp_not_list,
                                        oth_not_list,
                                        $define_limi1::int - ROW_NUMBER() OVER (ORDER BY next_dt) AS tot_tobe,
                                        $define_limi2::int - ROW_NUMBER() OVER (ORDER BY next_dt) AS mndt_tobe
                                    FROM aggregated
                                    ),
                                    adjusted AS (
                                    SELECT
                                        next_dt,
                                        CASE WHEN tot_tobe  < $nonmand_limit_for_tot THEN $nonmand_limit_for_tot ELSE tot_tobe  END AS tot_tobe,
                                        CASE WHEN mndt_tobe < $mand_limit_for_tot THEN $mand_limit_for_tot ELSE mndt_tobe END AS mndt_tobe,
                                        tot_avl,
                                        (fd_not_list + frs_adj_not_list + aw_not_list +
                                        nradj_not_list + inperson_not_list + bail_not_list +
                                        imp_ia_not_list) AS mndt_avl
                                    FROM numbered
                                    ),
                                    calculated AS (
                                    SELECT
                                        next_dt,
                                        tot_avl,
                                        mndt_avl,
                                        tot_tobe,
                                        mndt_tobe,
                                        CASE WHEN tot_avl   < tot_tobe   THEN tot_tobe   - tot_avl   ELSE 0 END AS alow_tot_updt,
                                        CASE WHEN mndt_avl < mndt_tobe THEN mndt_tobe - mndt_avl ELSE 0 END AS alow_mndt_updt
                                    FROM adjusted
                                    )
                                    SELECT *
                                    FROM calculated
                                    $query_for_nmd2
                                    $where_limit_condition";
                               
                                    

                                    $query = $this->db->query($sql_sno);
                                    $res_sno = $query->getResultArray();
                                   


                                    if (!empty($res_sno))
                                    {
                                        foreach ($res_sno as $ro_sno) {
                                            if ($i == 1 and $misc_nmd_sq2 == 0) {
                                                $alow_tot_updt = $ro_sno['alow_mndt_updt'];
                                            } else {
                                                $alow_tot_updt = $ro_sno['alow_tot_updt'];
                                            }
                                            $present_slot_date = $ro_sno['next_dt'];
                                            if ($i == 0) {
                                                $limit_tobe_transfer = "2000";
                                            } else {
                                                if ($alow_tot_updt > $tobe_trans) {
                                                    $limit_tobe_transfer = $tobe_trans;
                                                    $tobe_trans = $limit_tobe_transfer - $tobe_trans;
                                                } else {
                                                    $limit_tobe_transfer = $alow_tot_updt;
                                                    $tobe_trans = $tobe_trans - $limit_tobe_transfer;
                                                }
                                            }



                                            if ($limit_tobe_transfer > 0) {

                                                $sql3 = "INSERT INTO transfer_old_com_gen_cases (diary_no,next_dt_old,next_dt_new,tentative_cl_dt_old,
                                                        tentative_cl_dt_new,listorder,conn_key,ent_dt,test2,listorder_new,board_type,listtype)
                                                        SELECT j.*, 'A' AS listtype
                                                        FROM (
                                                        SELECT
                                                            diary_no,
                                                            m.next_dt AS next_dt_old,
                                                            ('$present_slot_date'::DATE) AS next_dt_new,
                                                            m.tentative_cl_dt AS tentative_cl_dt_old,
                                                            ('$present_slot_date' ::DATE) AS tentative_cl_dt_new,
                                                            m.listorder,
                                                            m.conn_key::int,
                                                            NOW() AS now_val,
                                                            'cron_a' AS cron,
                                                            CASE WHEN m.listorder = 16 THEN 2 ELSE m.listorder END AS listorder_new,
                                                            'J' AS btype
                                                        FROM (
                                                            SELECT 
                                                            ad_al2.next_dt AS old_adv_dt,
                                                            lp.priority AS lp_priority,
                                                            m.diary_no_rec_date,
                                                            s.stagename,
                                                            s.priority,
                                                            m.conn_key,
                                                            active_fil_dt,
                                                            active_fil_no,
                                                            tentative_cl_dt,
                                                            h.next_dt,
                                                            h.coram,
                                                            h.subhead,
                                                            d.doccode1,
                                                            mc.submaster_id,
                                                            h.listorder,
                                                            h.diary_no,
                                                            h.no_of_time_deleted,
                                                            CASE 
                                                                WHEN (h.subhead = 804 OR mc.submaster_id = 173 OR d.doccode1 IN (40,41,48,49,71,72,118,131,211,309))
                                                                THEN 1 ELSE 0 
                                                            END AS bail,
                                                            CASE 
                                                                WHEN a.advocate_id IN (584,585,610,616,666,940) THEN 1 ELSE 0 
                                                            END AS inperson,
                                                            CASE 
                                                                WHEN d.doccode1 IN (56,57,102,73,99,27,124,2,16) THEN 1 ELSE 0 
                                                            END AS schm
                                                            FROM master.sc_working_days wd
                                                            LEFT JOIN heardt h ON h.next_dt = wd.working_date
                                                            LEFT JOIN master.listing_purpose lp ON lp.code = h.listorder AND lp.display = 'Y'
                                                            LEFT JOIN master.subheading s ON s.stagecode = h.subhead AND s.listtype = 'M' AND s.display = 'Y'
                                                            INNER JOIN main m ON m.diary_no = h.diary_no
                                                            LEFT JOIN docdetails d ON d.diary_no = m.diary_no 
                                                                AND d.display = 'Y' AND d.iastat = 'P' AND d.doccode = 8
                                                                AND d.doccode1 IN (7,66,29,56,57,28,103,133,226,3,309,73,99,40,48,72,71,27,124,2,16,41,49,71,72,102,118,131,211,309)
                                                            LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no 
                                                                AND mc.display = 'Y'
                                                                AND mc.submaster_id NOT IN (911,912,239,240,241,242,243)
                                                            LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                                                            LEFT JOIN advocate a ON a.diary_no = m.diary_no 
                                                                AND a.advocate_id IN (584,585,610,616,666,940) 
                                                                AND a.display = 'Y'
                                                            LEFT JOIN advance_allocated ad_al ON ad_al.diary_no::int = h.diary_no 
                                                                AND ad_al.next_dt = '$from_dt'
                                                                AND ad_al.board_type = 'J'
                                                            LEFT JOIN advanced_drop_note ad_d ON ad_d.diary_no = ad_al.diary_no 
                                                                AND ad_al.next_dt = ad_d.cl_date
                                                            LEFT JOIN advance_allocated ad_al2 ON ad_al2.diary_no::int = h.diary_no 
                                                                AND ad_al2.next_dt >= '$last_advance_dates'
                                                                AND ad_al2.board_type = 'J'
                                                            WHERE ad_al.diary_no IS NULL 
                                                            AND ad_d.diary_no IS NULL 
                                                            AND rd.fil_no IS NULL 
                                                            AND mc.diary_no IS NOT NULL 
                                                            AND m.c_status = 'P'
                                                            AND (m.diary_no = m.conn_key::int OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
                                                            AND wd.display = 'Y'
                                                            AND wd.working_date <= '$publised[printed_max_dt]'
                                                            AND h.board_type = 'J'
                                                            AND h.listorder <> 32
                                                            AND h.mainhead = 'M'
                                                            AND h.clno = 0
                                                            AND h.brd_slno = 0
                                                            AND h.main_supp_flag = 0
                                                            $misc_nmd_sq4
                                                            AND CASE 
                                                                    WHEN EXTRACT(DOW FROM '$from_dt'::date) <> 2 THEN h.is_nmd <> 'Y' 
                                                                    ELSE TRUE 
                                                                END
                                                            AND h.next_dt = '$from_dt'
                                                            $qry_str_j
                                                            GROUP BY h.diary_no,ad_al2.next_dt,lp.priority,m.diary_no_rec_date,s.stagename,s.priority,m.conn_key,m.active_fil_dt,m.active_fil_no
                                                        ,d.doccode1,mc.submaster_id,a.advocate_id) m 
                                                        WHERE  $lp_qry
                                                        $subhead_qry
                                                        ORDER BY 
                                                            CASE WHEN subhead = '824' THEN 1 ELSE 999 END ASC,
                                                            CASE WHEN listorder IN (4,5) THEN 2 ELSE 999 END ASC,
                                                            CASE WHEN listorder IN (7) THEN 3 ELSE 999 END ASC,
                                                            CASE WHEN inperson IS NOT NULL THEN 4 ELSE 999 END ASC,
                                                            CASE WHEN bail IS NOT NULL THEN 5 ELSE 999 END ASC,
                                                            CASE WHEN listorder IN (25) THEN 6 ELSE 999 END ASC,
                                                            CASE WHEN subhead IN ('810','802','803','807') THEN 7 ELSE 999 END ASC,
                                                            CASE WHEN schm IS NOT NULL THEN 8 ELSE 999 END ASC,
                                                            lp_priority,
                                                            no_of_time_deleted DESC,
                                                            CASE WHEN (coram IS NOT NULL AND coram <> '0' AND TRIM(coram) <> '') THEN 1 ELSE 999 END ASC,
                                                            CAST(RIGHT(diary_no::text, 4) AS INTEGER) DESC,
                                                            CAST(LEFT(diary_no::text, LENGTH(diary_no::text) - 4) AS INTEGER) DESC
                                                        LIMIT $limit_tobe_transfer
                                                        ) j
                                                        LEFT JOIN transfer_old_com_gen_cases l 
                                                        ON j.diary_no = l.diary_no
                                                        AND l.next_dt_old = j.next_dt_old
                                                        AND l.next_dt_new = j.next_dt_new::date
                                                        WHERE l.diary_no IS NULL";

                                                $res3 = $this->db->query($sql3);

                                                $sql4 = "INSERT INTO last_heardt (
                                                            diary_no, conn_key, next_dt, mainhead, 
                                                            subhead, clno, brd_slno, roster_id, 
                                                            judges, coram, board_type, usercode, 
                                                            ent_dt, module_id, mainhead_n, subhead_n, 
                                                            main_supp_flag, listorder, tentative_cl_dt, 
                                                            lastorder, listed_ia, sitting_judges, 
                                                            list_before_remark, is_nmd, no_of_time_deleted
                                                            ) 
                                                            SELECT 
                                                            j.* 
                                                            FROM 
                                                            (
                                                                SELECT 
                                                                m.diary_no, 
                                                                m.conn_key, 
                                                                m.next_dt, 
                                                                m.mainhead, 
                                                                m.subhead, 
                                                                m.clno, 
                                                                m.brd_slno, 
                                                                m.roster_id, 
                                                                m.judges, 
                                                                m.coram, 
                                                                m.board_type :: last_heardt_board_type, 
                                                                m.usercode, 
                                                                m.ent_dt, 
                                                                m.module_id, 
                                                                m.mainhead_n, 
                                                                m.subhead_n, 
                                                                m.main_supp_flag, 
                                                                m.listorder, 
                                                                m.tentative_cl_dt, 
                                                                m.lastorder, 
                                                                m.listed_ia, 
                                                                m.sitting_judges, 
                                                                m.list_before_remark, 
                                                                m.is_nmd, 
                                                                m.no_of_time_deleted 
                                                                FROM 
                                                                (
                                                                    SELECT 
                                                                    ad_al2.next_dt AS old_adv_dt, 
                                                                    lp.priority AS lp_priority, 
                                                                    m.diary_no_rec_date, 
                                                                    h.diary_no, 
                                                                    h.conn_key, 
                                                                    h.next_dt, 
                                                                    h.mainhead, 
                                                                    h.subhead, 
                                                                    h.clno, 
                                                                    h.brd_slno, 
                                                                    h.roster_id, 
                                                                    h.judges, 
                                                                    h.coram, 
                                                                    h.board_type, 
                                                                    h.usercode, 
                                                                    h.ent_dt, 
                                                                    h.module_id, 
                                                                    h.mainhead_n, 
                                                                    h.subhead_n, 
                                                                    h.main_supp_flag, 
                                                                    h.listorder, 
                                                                    h.tentative_cl_dt, 
                                                                    m.lastorder, 
                                                                    h.listed_ia, 
                                                                    h.sitting_judges, 
                                                                    h.list_before_remark, 
                                                                    h.is_nmd, 
                                                                    s.stagename, 
                                                                    s.priority, 
                                                                    active_fil_dt, 
                                                                    active_fil_no, 
                                                                    d.doccode1, 
                                                                    mc.submaster_id, 
                                                                    h.no_of_time_deleted, 
                                                                    CASE WHEN h.subhead = 804 
                                                                    OR mc.submaster_id = 173 
                                                                    OR doccode1 IN (
                                                                        40, 41, 48, 49, 71, 72, 118, 131, 211, 309
                                                                    ) THEN 1 ELSE 0 END AS bail, 
                                                                    CASE WHEN a.advocate_id IN (584, 585, 610, 616, 666, 940) THEN 1 ELSE 0 END AS inperson, 
                                                                    CASE WHEN d.doccode1 IN (56, 57, 102, 73, 99, 27, 124, 2, 16) THEN 1 ELSE 0 END AS schm 
                                                                    FROM 
                                                                    master.sc_working_days wd 
                                                                    LEFT JOIN public.heardt h ON h.next_dt = wd.working_date 
                                                                    LEFT JOIN master.listing_purpose lp ON lp.code = h.listorder 
                                                                    AND lp.display = 'Y' 
                                                                    LEFT JOIN master.subheading s ON s.stagecode = h.subhead 
                                                                    AND s.listtype = 'M' 
                                                                    AND s.display = 'Y' 
                                                                    INNER JOIN main m ON m.diary_no = h.diary_no 
                                                                    LEFT JOIN docdetails d ON d.diary_no = m.diary_no 
                                                                    AND d.display = 'Y' 
                                                                    AND d.iastat = 'P' 
                                                                    AND d.doccode = 8 
                                                                    AND d.doccode1 IN (
                                                                        7, 66, 29, 56, 57, 28, 103, 133, 226, 3, 
                                                                        309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 
                                                                        16, 41, 49, 71, 72, 102, 118, 131, 211, 
                                                                        309
                                                                    ) 
                                                                    LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no 
                                                                    AND mc.display = 'Y' 
                                                                    AND mc.submaster_id NOT IN (911, 912, 239, 240, 241, 242, 243) 
                                                                    LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no 
                                                                    AND rd.remove_def = 'N' 
                                                                    LEFT JOIN advocate a ON a.diary_no = m.diary_no 
                                                                    AND a.advocate_id IN (584, 585, 610, 616, 666, 940) 
                                                                    AND a.display = 'Y' 
                                                                    LEFT JOIN advance_allocated ad_al ON ad_al.diary_no :: int = h.diary_no 
                                                                    AND ad_al.next_dt = '$from_dt' 
                                                                    AND ad_al.board_type = 'J' 
                                                                    LEFT JOIN advanced_drop_note ad_d ON ad_d.diary_no = ad_al.diary_no 
                                                                    AND ad_al.next_dt = ad_d.cl_date 
                                                                    LEFT JOIN advance_allocated ad_al2 ON ad_al2.diary_no :: int = h.diary_no 
                                                                    AND ad_al2.next_dt >= '$last_advance_dates' 
                                                                    AND ad_al2.board_type = 'J' 
                                                                    WHERE 
                                                                    ad_al.diary_no IS NULL 
                                                                    AND ad_d.diary_no IS NULL 
                                                                    AND rd.fil_no IS NULL 
                                                                    AND mc.diary_no IS NOT NULL 
                                                                  AND m.c_status = 'P' 
                                                                    AND (
                                                                        m.diary_no = m.conn_key :: int 
                                                                        OR m.conn_key = '' 
                                                                        OR m.conn_key IS NULL 
                                                                        OR m.conn_key = '0'
                                                                    ) 
                                                                    AND wd.display = 'Y' 
                                                                    AND wd.working_date <= '$publised[printed_max_dt]' 
                                                                    AND h.board_type = 'J' 
                                                                    AND h.listorder != 32 
                                                                    AND h.mainhead = 'M' 
                                                                    AND h.clno = 0 
                                                                    AND h.brd_slno = 0 
                                                                    AND h.main_supp_flag = 0 $misc_nmd_sq4 
                                                                    AND h.next_dt = '$from_dt' $qry_str_j 
                                                                    GROUP BY 
                                                                    h.diary_no, 
                                                                    ad_al2.next_dt, 
                                                                    lp.priority, 
                                                                    m.diary_no_rec_date, 
                                                                    m.lastorder, 
                                                                    s.stagename, 
                                                                    s.priority, 
                                                                    m.active_fil_dt, 
                                                                    m.active_fil_no, 
                                                                    d.doccode1, 
                                                                    mc.submaster_id, 
                                                                    a.advocate_id
                                                                ) m 
                                                                WHERE 
                                                                $lp_qry $subhead_qry 
                                                                ORDER BY 
                                                                CASE WHEN subhead = '824' THEN 1 ELSE 999 END, 
                                                                CASE WHEN listorder IN (4, 5) THEN 2 ELSE 999 END, 
                                                                CASE WHEN listorder = 7 THEN 3 ELSE 999 END, 
                                                                CASE WHEN inperson IS NOT NULL THEN 4 ELSE 999 END, 
                                                                CASE WHEN bail IS NOT NULL THEN 5 ELSE 999 END, 
                                                                CASE WHEN listorder = 25 THEN 6 ELSE 999 END, 
                                                                CASE WHEN subhead IN ('810', '802', '803', '807') THEN 7 ELSE 999 END, 
                                                                CASE WHEN schm IS NOT NULL THEN 8 ELSE 999 END, 
                                                                lp_priority, 
                                                                no_of_time_deleted DESC, 
                                                                CASE WHEN (
                                                                    coram IS NOT NULL 
                                                                    AND coram != '0' 
                                                                    AND TRIM(coram) != ''
                                                                ) THEN 1 ELSE 999 END, 
                                                                CAST(
                                                                    SUBSTRING(
                                                                    diary_no :: text 
                                                                    FROM 
                                                                        LENGTH(diary_no :: text) -3
                                                                    ) AS INTEGER
                                                                ) DESC, 
                                                                CAST(
                                                                    LEFT(
                                                                    diary_no :: text, 
                                                                    LENGTH(diary_no :: text) -4
                                                                    ) AS INTEGER
                                                                ) DESC 
                                                                LIMIT 
                                                                $limit_tobe_transfer
                                                            ) j 
                                                            LEFT JOIN last_heardt l ON j.diary_no = l.diary_no 
                                                            AND l.next_dt = j.next_dt 
                                                            AND l.listorder = j.listorder 
                                                            AND l.mainhead = j.mainhead 
                                                            AND l.subhead = j.subhead 
                                                            AND l.roster_id = j.roster_id 
                                                            AND l.judges = j.judges 
                                                            AND l.clno = j.clno 
                                                            AND l.main_supp_flag = j.main_supp_flag 
                                                            AND l.ent_dt = j.ent_dt 
                                                            AND (
                                                                l.bench_flag = '' 
                                                                OR l.bench_flag IS NULL
                                                            ) 
                                                            WHERE 
                                                            l.diary_no IS NULL ";

                                                $res4 = $this->db->query($sql4);

                                                $sql5 = "UPDATE public.heardt h
                                                            SET next_dt ='$present_slot_date',
                                                                tentative_cl_dt = '$present_slot_date',
                                                                usercode = '1',
                                                                ent_dt = NOW(),
                                                                module_id = '25',
                                                                listorder = CASE WHEN t0.listorder = 16 THEN '2' ELSE t0.listorder END,
                                                                no_of_time_deleted = t0.no_of_time_deleted + 1
                                                            FROM (
                                                                SELECT
                                                                    m.diary_no, m.conn_key, m.next_dt, m.mainhead, m.subhead, m.clno, m.brd_slno, m.roster_id, m.judges, m.coram,
                                                                    m.board_type, m.usercode, m.ent_dt, m.module_id, m.mainhead_n, m.subhead_n, m.main_supp_flag,
                                                                    m.listorder, m.tentative_cl_dt, m.lastorder, m.listed_ia, m.sitting_judges, m.is_nmd, m.no_of_time_deleted
                                                                FROM (
                                                                    SELECT ad_al2.next_dt AS old_adv_dt, lp.priority AS lp_priority, m.diary_no_rec_date,
                                                                        h.diary_no, h.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, 
                                                                        h.board_type, h.usercode, h.ent_dt, h.module_id, h.mainhead_n, h.subhead_n, h.main_supp_flag, 
                                                                        h.listorder, h.tentative_cl_dt, m.lastorder, h.listed_ia, h.sitting_judges, h.is_nmd, h.no_of_time_deleted,
                                                                        s.stagename, s.priority, active_fil_dt, active_fil_no, d.doccode1, mc.submaster_id,
                                                                        CASE WHEN h.subhead = 804 OR mc.submaster_id = 173 OR d.doccode1 IN (40,41,48,49,71,72,118,131,211,309) THEN 1 ELSE 0 END AS bail,
                                                                        CASE WHEN a.advocate_id IN (584,585,610,616,666,940) THEN 1 ELSE 0 END AS inperson,
                                                                        CASE WHEN d.doccode1 IN (56,57,102,73,99,27,124,2,16) THEN 1 ELSE 0 END AS schm
                                                                    FROM master.sc_working_days wd
                                                                    LEFT JOIN public.heardt h ON h.next_dt = wd.working_date
                                                                    LEFT JOIN master.listing_purpose lp ON lp.code = h.listorder AND lp.display = 'Y'
                                                                    LEFT JOIN master.subheading s ON s.stagecode = h.subhead AND s.listtype = 'M' AND s.display = 'Y'
                                                                    INNER JOIN main m ON m.diary_no = h.diary_no
                                                                    LEFT JOIN docdetails d ON d.diary_no = m.diary_no AND d.display = 'Y' AND d.iastat = 'P' AND d.doccode = 8
                                                                    AND d.doccode1 IN (7,66,29,56,57,28,103,133,226,3,309,73,99,40,48,72,71,27,124,2,16,41,49,71,72,102,118,131,211,309)
                                                                    LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no AND mc.display = 'Y'
                                                                    AND mc.submaster_id NOT IN (911, 912, 239, 240, 241, 242, 243)
                                                                    LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                                                                    LEFT JOIN advocate a ON a.diary_no = m.diary_no AND a.advocate_id IN (584,585,610,616,666,940) AND a.display = 'Y'
                                                                    LEFT JOIN advance_allocated ad_al ON ad_al.diary_no::int = h.diary_no AND ad_al.next_dt = '$from_dt' AND ad_al.board_type = 'J'
                                                                    LEFT JOIN advanced_drop_note ad_d ON ad_d.diary_no = ad_al.diary_no AND ad_al.next_dt = ad_d.cl_date
                                                                    LEFT JOIN advance_allocated ad_al2 ON ad_al2.diary_no::int = h.diary_no AND ad_al2.next_dt >= '$last_advance_dates' AND ad_al2.board_type = 'J'
                                                                    WHERE ad_al.diary_no IS NULL 
                                                                    AND ad_d.diary_no IS NULL 
                                                                    AND rd.fil_no IS NULL 
                                                                    AND mc.diary_no IS NOT NULL 
                                                                    AND m.c_status = 'P' 
                                                                    AND (m.diary_no = m.conn_key::int OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
                                                                    AND wd.display = 'Y'
                                                                    AND wd.working_date <= '$publised[printed_max_dt]'
                                                                    AND h.board_type = 'J' 
                                                                    AND h.listorder != 32 
                                                                    AND h.mainhead = 'M' 
                                                                    AND h.clno = 0 
                                                                    AND h.brd_slno = 0 
                                                                    AND h.main_supp_flag = 0
                                                                    $misc_nmd_sq4
                                                                    AND h.next_dt = '$from_dt'
                                                                    $qry_str_j
                                                                    GROUP BY a.advocate_id,mc.submaster_id,d.doccode1,m.active_fil_no,m.active_fil_dt,h.diary_no,ad_al2.next_dt,lp.priority,m.diary_no_rec_date,m.lastorder,s.stagename,s.priority) m 
                                                            WHERE  $lp_qry
                                                                    $subhead_qry
                                                            ORDER BY 
                                                                    CASE WHEN subhead = '824' THEN 1 ELSE 999 END ASC,
                                                                    CASE WHEN listorder IN (4,5) THEN 2 ELSE 999 END ASC,
                                                                    CASE WHEN listorder = 7 THEN 3 ELSE 999 END ASC,
                                                                    CASE WHEN inperson IS NOT NULL THEN 4 ELSE 999 END ASC,
                                                                    CASE WHEN bail IS NOT NULL THEN 5 ELSE 999 END ASC,
                                                                    CASE WHEN listorder = 25 THEN 6 ELSE 999 END ASC,
                                                                    CASE WHEN subhead IN ('810', '802', '803', '807') THEN 7 ELSE 999 END ASC,
                                                                    CASE WHEN schm IS NOT NULL THEN 8 ELSE 999 END ASC,
                                                                    lp_priority,
                                                                    no_of_time_deleted DESC,
                                                                    CASE WHEN coram IS NOT NULL AND coram <> '0' AND TRIM(coram) <> '' THEN 1 ELSE 999 END ASC,
                                                                    CAST(RIGHT(diary_no::text, 4) AS INTEGER) DESC, 
                                                                    CAST(LEFT(diary_no::text, LENGTH(diary_no::text) - 4) AS INTEGER) DESC
                                                                LIMIT $limit_tobe_transfer
                                                            ) t0
                                                            WHERE t0.diary_no = h.diary_no";


                                                $res5 = $this->db->query($sql5);

                                                $sql6 = "INSERT INTO transfer_old_com_gen_cases (diary_no,next_dt_old,next_dt_new,tentative_cl_dt_old,
                                                            tentative_cl_dt_new,listorder,conn_key,ent_dt,test2,listorder_new,board_type,listtype)
                                                            SELECT j.*, 'A' AS listtype
                                                            FROM (
                                                            SELECT
                                                                diary_no,
                                                                m.next_dt AS next_dt_old,
                                                                ('$present_slot_date'::DATE) AS next_dt_new,
                                                                m.tentative_cl_dt AS tentative_cl_dt_old,
                                                                ('$present_slot_date'::DATE)  AS tentative_cl_dt_new,
                                                                m.listorder,
                                                                m.conn_key::int,
                                                                NOW() AS now_val,
                                                                'cron_a' AS cron,
                                                                CASE WHEN m.listorder = 16 THEN 2 ELSE m.listorder END AS listorder_new,
                                                                'J' AS btype
                                                            FROM (
                                                                SELECT 
                                                                ad_al2.next_dt AS old_adv_dt,
                                                                lp.priority AS lp_priority,
                                                                m.diary_no_rec_date,
                                                                s.stagename,
                                                                s.priority,
                                                                m.conn_key,
                                                                active_fil_dt,
                                                                active_fil_no,
                                                                tentative_cl_dt,
                                                                h.next_dt,
                                                                h.coram,
                                                                h.subhead,
                                                                d.doccode1,
                                                                mc.submaster_id,
                                                                h.listorder,
                                                                h.diary_no,
                                                                h.no_of_time_deleted,
                                                                CASE 
                                                                    WHEN (h.subhead = 804 OR mc.submaster_id = 173 OR d.doccode1 IN (40,41,48,49,71,72,118,131,211,309))
                                                                    THEN 1 ELSE 0 
                                                                END AS bail,
                                                                CASE 
                                                                    WHEN a.advocate_id IN (584,585,610,616,666,940) THEN 1 ELSE 0 
                                                                END AS inperson,
                                                                CASE 
                                                                    WHEN d.doccode1 IN (56,57,102,73,99,27,124,2,16) THEN 1 ELSE 0 
                                                                END AS schm
                                                                FROM  master.sc_working_days wd
                                                                LEFT JOIN heardt h ON h.next_dt = wd.working_date
                                                                LEFT JOIN  master.listing_purpose lp ON lp.code = h.listorder AND lp.display = 'Y'
                                                                LEFT JOIN  master.subheading s ON s.stagecode = h.subhead AND s.listtype = 'M' AND s.display = 'Y'
                                                                INNER JOIN main m ON m.diary_no = h.diary_no
                                                                LEFT JOIN docdetails d ON d.diary_no = m.diary_no 
                                                                    AND d.display = 'Y' AND d.iastat = 'P' AND d.doccode = 8
                                                                    AND d.doccode1 IN (7,66,29,56,57,28,103,133,226,3,309,73,99,40,48,72,71,27,124,2,16,41,49,71,72,102,118,131,211,309)
                                                                LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no 
                                                                    AND mc.display = 'Y'
                                                                    AND mc.submaster_id NOT IN (911,912,239,240,241,242,243)
                                                                LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                                                                LEFT JOIN advocate a ON a.diary_no = m.diary_no 
                                                                    AND a.advocate_id IN (584,585,610,616,666,940) 
                                                                    AND a.display = 'Y'
                                                                LEFT JOIN advance_allocated ad_al ON ad_al.diary_no::int = h.diary_no 
                                                                    AND ad_al.next_dt = '$from_dt'
                                                                    AND ad_al.board_type = 'J'
                                                                LEFT JOIN advanced_drop_note ad_d ON ad_d.diary_no = ad_al.diary_no 
                                                                    AND ad_al.next_dt = ad_d.cl_date
                                                                LEFT JOIN advance_allocated ad_al2 ON ad_al2.diary_no::int = h.diary_no 
                                                                    AND ad_al2.next_dt >= '$last_advance_dates'
                                                                    AND ad_al2.board_type = 'J'
                                                                WHERE ad_al.diary_no IS NULL 
                                                                AND ad_d.diary_no IS NULL 
                                                                AND rd.fil_no IS NULL 
                                                                AND mc.diary_no IS NOT NULL 
                                                                AND m.c_status = 'P'
                                                                AND (m.diary_no = m.conn_key::int OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
                                                                AND wd.display = 'Y'
                                                                AND wd.working_date <= '$publised[printed_max_dt]'
                                                                AND h.board_type = 'J'
                                                                AND h.listorder <> 32
                                                                AND h.mainhead = 'M'
                                                                AND h.clno = 0
                                                                AND h.brd_slno = 0
                                                                AND h.main_supp_flag = 0
                                                                    $misc_nmd_sq4
                                                                AND h.is_nmd = 'N'
                                                                AND h.next_dt = '$present_slot_date'
                                                                $qry_str_j
                                                                GROUP BY h.diary_no,ad_al2.next_dt,lp.priority,m.diary_no_rec_date,s.stagename,s.priority,m.conn_key,m.active_fil_dt,m.active_fil_no
                                                            ,d.doccode1,mc.submaster_id,a.advocate_id) m 
                                                            WHERE  $lp_qry
                                                                $subhead_qry
                                                            ORDER BY 
                                                                CASE WHEN subhead = '824' THEN 1 ELSE 999 END ASC,
                                                                CASE WHEN listorder IN (4,5) THEN 2 ELSE 999 END ASC,
                                                                CASE WHEN listorder IN (7) THEN 3 ELSE 999 END ASC,
                                                                CASE WHEN inperson IS NOT NULL THEN 4 ELSE 999 END ASC,
                                                                CASE WHEN bail IS NOT NULL THEN 5 ELSE 999 END ASC,
                                                                CASE WHEN listorder IN (25) THEN 6 ELSE 999 END ASC,
                                                                CASE WHEN subhead IN ('810','802','803','807') THEN 7 ELSE 999 END ASC,
                                                                CASE WHEN schm IS NOT NULL THEN 8 ELSE 999 END ASC,
                                                                lp_priority,
                                                                no_of_time_deleted DESC,
                                                                CASE WHEN (coram IS NOT NULL AND coram <> '0' AND TRIM(coram) <> '') THEN 1 ELSE 999 END ASC,
                                                                CAST(RIGHT(diary_no::text, 4) AS INTEGER) DESC,
                                                                CAST(LEFT(diary_no::text, LENGTH(diary_no::text) - 4) AS INTEGER) DESC
                                                            LIMIT $limit_tobe_transfer
                                                            ) j
                                                            LEFT JOIN transfer_old_com_gen_cases l 
                                                            ON j.diary_no = l.diary_no
                                                            AND l.next_dt_old = j.next_dt_old
                                                            AND l.next_dt_new = j.next_dt_new::date
                                                            WHERE l.diary_no IS NULL";
                                                $res6 = $this->db->query($sql6);


                                                $sql7 = "INSERT INTO last_heardt (
                                                            diary_no, conn_key, next_dt, mainhead, 
                                                            subhead, clno, brd_slno, roster_id, 
                                                            judges, coram, board_type, usercode, 
                                                            ent_dt, module_id, mainhead_n, subhead_n, 
                                                            main_supp_flag, listorder, tentative_cl_dt, 
                                                            lastorder, listed_ia, sitting_judges, 
                                                            list_before_remark, is_nmd, no_of_time_deleted
                                                            ) 
                                                            SELECT 
                                                            j.* 
                                                            FROM 
                                                            (
                                                                SELECT 
                                                                m.diary_no, 
                                                                m.conn_key, 
                                                                m.next_dt, 
                                                                m.mainhead, 
                                                                m.subhead, 
                                                                m.clno, 
                                                                m.brd_slno, 
                                                                m.roster_id, 
                                                                m.judges, 
                                                                m.coram, 
                                                                m.board_type :: last_heardt_board_type, 
                                                                m.usercode, 
                                                                m.ent_dt, 
                                                                m.module_id, 
                                                                m.mainhead_n, 
                                                                m.subhead_n, 
                                                                m.main_supp_flag, 
                                                                m.listorder, 
                                                                m.tentative_cl_dt, 
                                                                m.lastorder, 
                                                                m.listed_ia, 
                                                                m.sitting_judges, 
                                                                m.list_before_remark, 
                                                                m.is_nmd, 
                                                                m.no_of_time_deleted 
                                                                FROM 
                                                                (
                                                                    SELECT 
                                                                    ad_al2.next_dt AS old_adv_dt, 
                                                                    lp.priority AS lp_priority, 
                                                                    m.diary_no_rec_date, 
                                                                    h.diary_no, 
                                                                    h.conn_key, 
                                                                    h.next_dt, 
                                                                    h.mainhead, 
                                                                    h.subhead, 
                                                                    h.clno, 
                                                                    h.brd_slno, 
                                                                    h.roster_id, 
                                                                    h.judges, 
                                                                    h.coram, 
                                                                    h.board_type, 
                                                                    h.usercode, 
                                                                    h.ent_dt, 
                                                                    h.module_id, 
                                                                    h.mainhead_n, 
                                                                    h.subhead_n, 
                                                                    h.main_supp_flag, 
                                                                    h.listorder, 
                                                                    h.tentative_cl_dt, 
                                                                    m.lastorder, 
                                                                    h.listed_ia, 
                                                                    h.sitting_judges, 
                                                                    h.list_before_remark, 
                                                                    h.is_nmd, 
                                                                    s.stagename, 
                                                                    s.priority, 
                                                                    active_fil_dt, 
                                                                    active_fil_no, 
                                                                    d.doccode1, 
                                                                    mc.submaster_id, 
                                                                    h.no_of_time_deleted, 
                                                                    CASE WHEN h.subhead = 804 
                                                                    OR mc.submaster_id = 173 
                                                                    OR doccode1 IN (
                                                                        40, 41, 48, 49, 71, 72, 118, 131, 211, 309
                                                                    ) THEN 1 ELSE 0 END AS bail, 
                                                                    CASE WHEN a.advocate_id IN (584, 585, 610, 616, 666, 940) THEN 1 ELSE 0 END AS inperson, 
                                                                    CASE WHEN d.doccode1 IN (56, 57, 102, 73, 99, 27, 124, 2, 16) THEN 1 ELSE 0 END AS schm 
                                                                    FROM 
                                                                    master.sc_working_days wd 
                                                                    LEFT JOIN public.heardt h ON h.next_dt = wd.working_date 
                                                                    LEFT JOIN master.listing_purpose lp ON lp.code = h.listorder 
                                                                    AND lp.display = 'Y' 
                                                                    LEFT JOIN master.subheading s ON s.stagecode = h.subhead 
                                                                    AND s.listtype = 'M' 
                                                                    AND s.display = 'Y' 
                                                                    INNER JOIN main m ON m.diary_no = h.diary_no 
                                                                    LEFT JOIN docdetails d ON d.diary_no = m.diary_no 
                                                                    AND d.display = 'Y' 
                                                                    AND d.iastat = 'P' 
                                                                    AND d.doccode = 8 
                                                                    AND d.doccode1 IN (
                                                                        7, 66, 29, 56, 57, 28, 103, 133, 226, 3, 
                                                                        309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 
                                                                        16, 41, 49, 71, 72, 102, 118, 131, 211, 
                                                                        309
                                                                    ) 
                                                                    LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no 
                                                                    AND mc.display = 'Y' 
                                                                    AND mc.submaster_id NOT IN (911, 912, 239, 240, 241, 242, 243) 
                                                                    LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no 
                                                                    AND rd.remove_def = 'N' 
                                                                    LEFT JOIN advocate a ON a.diary_no = m.diary_no 
                                                                    AND a.advocate_id IN (584, 585, 610, 616, 666, 940) 
                                                                    AND a.display = 'Y' 
                                                                    LEFT JOIN advance_allocated ad_al ON ad_al.diary_no :: int = h.diary_no 
                                                                    AND ad_al.next_dt = '$from_dt' 
                                                                    AND ad_al.board_type = 'J' 
                                                                    LEFT JOIN advanced_drop_note ad_d ON ad_d.diary_no = ad_al.diary_no 
                                                                    AND ad_al.next_dt = ad_d.cl_date 
                                                                    LEFT JOIN advance_allocated ad_al2 ON ad_al2.diary_no :: int = h.diary_no 
                                                                    AND ad_al2.next_dt >= '$last_advance_dates' 
                                                                    AND ad_al2.board_type = 'J' 
                                                                    WHERE 
                                                                    ad_al.diary_no IS NULL 
                                                                    AND ad_d.diary_no IS NULL 
                                                                    AND rd.fil_no IS NULL 
                                                                    AND mc.diary_no IS NOT NULL 
                                                                    AND m.c_status = 'P' 
                                                                    AND (
                                                                        m.diary_no = m.conn_key :: int 
                                                               OR m.conn_key = '' 
                                                                        OR m.conn_key IS NULL 
                                                                        OR m.conn_key = '0'
                                                                    ) 
                                                                    AND wd.display = 'Y' 
                                                                    AND wd.working_date <= '$publised[printed_max_dt]' 
                                                                    AND h.board_type = 'J' 
                                                                    AND h.listorder != 32 
                                                                    AND h.mainhead = 'M' 
                                                                    AND h.clno = 0 
                                                                    AND h.brd_slno = 0 
                                                                    AND h.main_supp_flag = 0 $misc_nmd_sq4 
                                                                    AND h.next_dt = '$from_dt' $qry_str_j 
                                                                    GROUP BY 
                                                                    h.diary_no, 
                                                                    ad_al2.next_dt, 
                                                                    lp.priority, 
                                                                    m.diary_no_rec_date, 
                                                                    m.lastorder, 
                                                                    s.stagename, 
                                                                    s.priority, 
                                                                    m.active_fil_dt, 
                                                                    m.active_fil_no, 
                                                                    d.doccode1, 
                                                                    mc.submaster_id, 
                                                                    a.advocate_id
                                                                ) m 
                                                                WHERE 
                                                                $lp_qry $subhead_qry 
                                                                ORDER BY 
                                                                CASE WHEN subhead = '824' THEN 1 ELSE 999 END, 
                                                                CASE WHEN listorder IN (4, 5) THEN 2 ELSE 999 END, 
                                                                CASE WHEN listorder = 7 THEN 3 ELSE 999 END, 
                                                                CASE WHEN inperson IS NOT NULL THEN 4 ELSE 999 END, 
                                                                CASE WHEN bail IS NOT NULL THEN 5 ELSE 999 END, 
                                                                CASE WHEN listorder = 25 THEN 6 ELSE 999 END, 
                                                                CASE WHEN subhead IN ('810', '802', '803', '807') THEN 7 ELSE 999 END, 
                                                                CASE WHEN schm IS NOT NULL THEN 8 ELSE 999 END, 
                                                                lp_priority, 
                                                                no_of_time_deleted DESC, 
                                                                CASE WHEN (
                                                                    coram IS NOT NULL 
                                                                    AND coram != '0' 
                                                                    AND TRIM(coram) != ''
                                                                ) THEN 1 ELSE 999 END, 
                                                                CAST(
                                                                    SUBSTRING(
                                                                    diary_no :: text 
                                                                    FROM 
                                                                        LENGTH(diary_no :: text) -3
                                                                    ) AS INTEGER
                                                                ) DESC, 
                                                                CAST(
                                                                    LEFT(
                                                                    diary_no :: text, 
                                                                    LENGTH(diary_no :: text) -4
                                                                    ) AS INTEGER
                                                                ) DESC 
                                                                LIMIT 
                                                                $limit_tobe_transfer
                                                            ) j 
                                                            LEFT JOIN last_heardt l ON j.diary_no = l.diary_no 
                                                            AND l.next_dt = j.next_dt 
                                                            AND l.listorder = j.listorder 
                                                            AND l.mainhead = j.mainhead 
                                                            AND l.subhead = j.subhead 
                                                            AND l.roster_id = j.roster_id 
                                                            AND l.judges = j.judges 
                                                            AND l.clno = j.clno 
                                                            AND l.main_supp_flag = j.main_supp_flag 
                                                            AND l.ent_dt = j.ent_dt 
                                                            AND (
                                                                l.bench_flag = '' 
                                                                OR l.bench_flag IS NULL
                                                            ) 
                                                            WHERE 
                                                            l.diary_no IS NULL";


                                                $res7 = $this->db->query($sql7);

                                                //Anuj7
                                                $sql8 = "UPDATE public.heardt h
                                                            SET next_dt ='$present_slot_date',
                                                                tentative_cl_dt = '$present_slot_date',
                                                                usercode = '1',
                                                                ent_dt = NOW(),
                                                                module_id = '25',
                                                                listorder = CASE WHEN t0.listorder = 16 THEN '2' ELSE t0.listorder END,
                                                                no_of_time_deleted = t0.no_of_time_deleted + 1
                                                            FROM (
                                                                SELECT
                                                                    m.diary_no, m.conn_key, m.next_dt, m.mainhead, m.subhead, m.clno, m.brd_slno, m.roster_id, m.judges, m.coram,
                                                                    m.board_type, m.usercode, m.ent_dt, m.module_id, m.mainhead_n, m.subhead_n, m.main_supp_flag,
                                                                    m.listorder, m.tentative_cl_dt, m.lastorder, m.listed_ia, m.sitting_judges, m.is_nmd, m.no_of_time_deleted
                                                                FROM (
                                                                    SELECT ad_al2.next_dt AS old_adv_dt, lp.priority AS lp_priority, m.diary_no_rec_date,
                                                                        h.diary_no, h.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, 
                                                                        h.board_type, h.usercode, h.ent_dt, h.module_id, h.mainhead_n, h.subhead_n, h.main_supp_flag, 
                                                                        h.listorder, h.tentative_cl_dt, m.lastorder, h.listed_ia, h.sitting_judges, h.is_nmd, h.no_of_time_deleted,
                                                                        s.stagename, s.priority, active_fil_dt, active_fil_no, d.doccode1, mc.submaster_id,
                                                                        CASE WHEN h.subhead = 804 OR mc.submaster_id = 173 OR d.doccode1 IN (40,41,48,49,71,72,118,131,211,309) THEN 1 ELSE 0 END AS bail,
                                                                        CASE WHEN a.advocate_id IN (584,585,610,616,666,940) THEN 1 ELSE 0 END AS inperson,
                                                                        CASE WHEN d.doccode1 IN (56,57,102,73,99,27,124,2,16) THEN 1 ELSE 0 END AS schm
                                                                    FROM master.sc_working_days wd
                                                                    LEFT JOIN public.heardt h ON h.next_dt = wd.working_date
                                                                    LEFT JOIN master.listing_purpose lp ON lp.code = h.listorder AND lp.display = 'Y'
                                                                    LEFT JOIN master.subheading s ON s.stagecode = h.subhead AND s.listtype = 'M' AND s.display = 'Y'
                                                                    INNER JOIN main m ON m.diary_no = h.diary_no
                                                                    LEFT JOIN docdetails d ON d.diary_no = m.diary_no AND d.display = 'Y' AND d.iastat = 'P' AND d.doccode = 8
                                                                    AND d.doccode1 IN (7,66,29,56,57,28,103,133,226,3,309,73,99,40,48,72,71,27,124,2,16,41,49,71,72,102,118,131,211,309)
                                                                    LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no AND mc.display = 'Y'
                                                                    AND mc.submaster_id NOT IN (911, 912, 239, 240, 241, 242, 243)
                                                                    LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                                                                    LEFT JOIN advocate a ON a.diary_no = m.diary_no AND a.advocate_id IN (584,585,610,616,666,940) AND a.display = 'Y'
                                                                    LEFT JOIN advance_allocated ad_al ON ad_al.diary_no::int = h.diary_no AND ad_al.next_dt = '$from_dt' AND ad_al.board_type = 'J'
                                                                    LEFT JOIN advanced_drop_note ad_d ON ad_d.diary_no = ad_al.diary_no AND ad_al.next_dt = ad_d.cl_date
                                                                    LEFT JOIN advance_allocated ad_al2 ON ad_al2.diary_no::int = h.diary_no AND ad_al2.next_dt >= '$last_advance_dates' AND ad_al2.board_type = 'J'
                                                                    WHERE ad_al.diary_no IS NULL 
                                                                    AND ad_d.diary_no IS NULL 
                                                                    AND rd.fil_no IS NULL 
                                                                    AND mc.diary_no IS NOT NULL 
                                                                    AND m.c_status = 'P' 
                                                                    AND (m.diary_no = m.conn_key::int OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
                                                                    AND wd.display = 'Y'
                                                                    AND wd.working_date <= '$publised[printed_max_dt]'
                                                                    AND h.board_type = 'J' 
                                                                    AND h.listorder != 32 
                                                                    AND h.mainhead = 'M' 
                                                                    AND h.clno = 0 
                                                                    AND h.brd_slno = 0 
                                                                    AND h.main_supp_flag = 0
                                                                    $misc_nmd_sq4
                                                                    AND h.next_dt = '$from_dt'
                                                                    $qry_str_j
                                                                    GROUP BY a.advocate_id,mc.submaster_id,d.doccode1,m.active_fil_no,m.active_fil_dt,h.diary_no,ad_al2.next_dt,lp.priority,m.diary_no_rec_date,m.lastorder,s.stagename,s.priority) m 
                                                            WHERE  $lp_qry
                                                                    $subhead_qry
                                                            ORDER BY 
                                                                    CASE WHEN subhead = '824' THEN 1 ELSE 999 END ASC,
                                                                    CASE WHEN listorder IN (4,5) THEN 2 ELSE 999 END ASC,
                                                                    CASE WHEN listorder = 7 THEN 3 ELSE 999 END ASC,
                                                                    CASE WHEN inperson IS NOT NULL THEN 4 ELSE 999 END ASC,
                                                                    CASE WHEN bail IS NOT NULL THEN 5 ELSE 999 END ASC,
                                                                    CASE WHEN listorder = 25 THEN 6 ELSE 999 END ASC,
                                                                    CASE WHEN subhead IN ('810', '802', '803', '807') THEN 7 ELSE 999 END ASC,
                                                                    CASE WHEN schm IS NOT NULL THEN 8 ELSE 999 END ASC,
                                                                    lp_priority,
                                                                    no_of_time_deleted DESC,
                                                                    CASE WHEN coram IS NOT NULL AND coram <> '0' AND TRIM(coram) <> '' THEN 1 ELSE 999 END ASC,
                                                                    CAST(RIGHT(diary_no::text, 4) AS INTEGER) DESC, 
                                                                    CAST(LEFT(diary_no::text, LENGTH(diary_no::text) - 4) AS INTEGER) DESC
                                                                LIMIT $limit_tobe_transfer
                                                            ) t0
                                                            WHERE t0.diary_no = h.diary_no";



                                                $res8 = $this->db->query($sql8);
                                            }


                                            if ($tobe_trans <= 0) {
                                                break;
                                            }
                                        }
                                    }
                                      } // remove 

                                    
                                }
                               
                            } 
                             
                            
                        }


                      
                       
                    }
                   


                    $update_reason = "UPDATE transfer_old_com_gen_cases
                        SET reason = COALESCE(e.reason, 'ELIMINATED DUE TO EXCESS MATTERS')
                        FROM eliminated_cases e
                        WHERE transfer_old_com_gen_cases.diary_no = e.diary_no 
                        AND e.next_dt_old = transfer_old_com_gen_cases.next_dt_old 
                        AND e.listtype = 'A' 
                        AND e.board_type = 'J'
                        AND transfer_old_com_gen_cases.next_dt_old = '$list_dt' 
                        AND transfer_old_com_gen_cases.board_type = 'R' 
                        AND transfer_old_com_gen_cases.listtype = 'A'
                        AND DATE(transfer_old_com_gen_cases.ent_dt) = CURRENT_DATE";
                    $res8 = $this->db->query($update_reason);
                }
                else
                {
                }
            }
        }
        else{
            //echo "<br>List Not Published.";
        }

        //END OF CORAM WISE ELIMINATION- OTHER THAN FIX DT


    }
}
