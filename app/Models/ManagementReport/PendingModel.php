<?php

namespace App\Models\ManagementReport;

use CodeIgniter\Model;

class PendingModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }
    public function casetype_wise()
    {
        $sql = "SELECT 
                m.active_casetype_id,
                c.casename,
                COUNT(DISTINCT m.diary_no) AS total,
                SUM(CASE WHEN (m.conn_key = '0' OR m.conn_key IS NULL OR m.conn_key = CAST(m.diary_no AS VARCHAR)) THEN 1 ELSE 0 END) AS Main,
                SUM(CASE WHEN (m.conn_key != '0' AND m.conn_key IS NOT NULL AND m.conn_key != CAST(m.diary_no AS VARCHAR)) THEN 1 ELSE 0 END) AS Connected
                FROM 
                main m 
                INNER JOIN 
                master.casetype c ON m.active_casetype_id = c.casecode 
                WHERE 
                c_status = 'P' 
                AND m.active_fil_no IS NOT NULL 
                AND m.active_fil_no != '' 
                AND display = 'Y' 
                GROUP BY 
                m.active_casetype_id, c.casename 
                ORDER BY 
                m.active_casetype_id";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
    public function judge_date_wise_tobe_list()
    {
        $builder = $this->db->table('master.judge j');
        $builder->select('j.jname, j.jcode');
        $builder->where('j.display', 'Y');
        $builder->where('j.is_retired !=', 'Y');
        $builder->where('jtype', 'J');
        $builder->orderBy('j.judge_seniority');
        $builder->limit(14);
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }
    public function judge_date_wise_tobe_list_get($jcd)
    {
        $builder = $this->db->table('master.judge j');
        $builder->select('j.jname, j.jcode');
        $builder->where('j.display', 'Y');
        $builder->where('j.is_retired !=', 'Y');
        $builder->where('jtype', 'J');
        $builder->where('j.jcode', $jcd);
        $builder->orderBy('j.judge_seniority');
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }
    public function judge_date_wise_tobe_list_table_get($jcd)
    {
        $sql  = "SELECT
                    next_dt,
                    coram,
                    id,
                    subcode1,
                    sub_name1,
                    SUM(not_listed) AS not_listed,
                    SUM(fd_not_listed) AS fd_not_listed,
                    SUM(aw_not_listed) AS aw_not_listed,
                    SUM(imp_ia_not_listed) AS imp_ia_not_listed,
                    SUM(oth_not_listed) AS oth_not_listed
                    FROM
                    (
                    SELECT
                        next_dt,
                        coram,
                    s.id,
                    s.subcode1,
                    s.sub_name1,
                    SUM(COALESCE(c.not_listed, 0)) AS not_listed,
                    SUM(COALESCE(c.fd_not_list, 0)) AS fd_not_listed,
                    SUM(COALESCE(c.aw_not_list, 0)) AS aw_not_listed,
                    SUM(COALESCE(c.imp_ia_not_list, 0)) AS imp_ia_not_listed,
                    SUM(COALESCE(c.oth_not_list, 0)) AS oth_not_listed
                    FROM
                    master.submaster s
                    LEFT JOIN (
                        SELECT
                        next_dt,
                        coram,
                    th.submaster_id,
                    COUNT(th.diary_no) AS not_listed,
                    SUM(CASE WHEN th.listorder IN (4, 5, 7, 25, 32) THEN 1 ELSE 0 END) AS fd_not_list,
                    SUM(CASE WHEN th.listorder = 8 THEN 1 ELSE 0 END) AS aw_not_list,
                    SUM(CASE WHEN doccode1 IS NOT NULL
                    AND th.listorder NOT IN (8, 4, 5, 7, 25, 32) THEN 1 ELSE 0 END) AS imp_ia_not_list,
                    SUM(CASE WHEN doccode1 IS NULL
                    AND th.listorder NOT IN (8, 4, 5, 7, 25, 32) THEN 1 ELSE 0 END) AS oth_not_list
                        FROM (
                        SELECT
                    h.next_dt,
                    h.coram,
                    d.doccode1,
                    mc.submaster_id,
                    h.listorder,
                    h.diary_no
                        FROM
                    master.sc_working_days wd
                    LEFT JOIN heardt h ON h.next_dt = wd.working_date
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
                    AND mc.submaster_id NOT IN (911, 912, 914, 239, 240, 241, 242, 243, 331, 9999)
                    AND (h.listorder != 4 AND h.listorder != 5 AND mc.submaster_id NOT IN (
                    343, 15, 16, 17, 18, 19, 20, 21, 22, 23,
                    341, 353, 157, 158, 159, 160, 161, 162,
                    163, 166, 173, 175, 176, 322, 222
                            ) OR TRUE)
                    LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                        WHERE
                    rd.fil_no IS NULL
                    AND mc.diary_no IS NOT NULL
                    AND m.c_status = 'P'
                    AND (m.diary_no = m.conn_key::bigint OR m.conn_key IS NULL OR m.conn_key = '0')
                    AND wd.display = 'Y'
                    AND wd.is_holiday = 0
                    AND wd.is_nmd = 0
                    AND wd.working_date >= CURRENT_DATE
                    AND h.next_dt >= CURRENT_DATE
                    AND h.board_type = 'J'
                    AND h.mainhead = 'M'
                    AND h.clno = 0
                    AND h.brd_slno = 0
                    AND h.main_supp_flag = 0
                    AND (h.listorder != 4 AND h.listorder != 5 AND h.is_nmd = 'N' OR TRUE)
                    AND h.listorder != 32
                    AND (h.coram LIKE '$jcd,%' OR h.coram = '$jcd')
                    AND h.subhead IN (824, 810, 803, 802, 807, 804, 808, 811,
                    812, 813, 814, 815, 816)
                        GROUP BY
                    h.diary_no, d.doccode1, mc.submaster_id,
                    h.listorder
                        ) th
                        GROUP BY
                    th.next_dt, th.coram, th.submaster_id
                    ) c ON s.id = c.submaster_id
                    WHERE
                    s.flag = 's'
                    AND s.display = 'Y'
                    AND s.subcode1 NOT IN (146, 147, 148, 149, 8888, 9999)
                    AND not_listed != 0
                    GROUP BY
                    next_dt, coram, s.id, s.subcode1, s.sub_name1
                    ) t
                    GROUP BY
                    next_dt, coram, id, subcode1, sub_name1
                    ORDER BY
                    next_dt";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
    public function coram_wise_cat_tobe_list_get()
    {
        $builder = $this->db->table('master.judge');
        $builder->select('jname, jcode');
        $builder->where('display', 'Y');
        $builder->where('is_retired !=', 'Y');
        $builder->where('jtype', 'J');
        $builder->orderBy('judge_seniority');
        $builder->limit(12);
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function coram_wise_cat_tobe_list_table_get($jcode, $list_dt, $board_type)
    {
        $sql = "SELECT coram, id, subcode1, sub_name1, 
                    SUM(not_listed) AS not_listed, 
                    SUM(fd_not_listed) AS fd_not_listed,
                    SUM(aw_not_listed) AS aw_not_listed,
                    SUM(imp_ia_not_listed) AS imp_ia_not_listed, 
                    SUM(oth_not_listed) AS oth_not_listed
                FROM (
                SELECT coram, s.id, s.subcode1, s.sub_name1,
                    SUM(COALESCE(c.not_listed, 0)) AS not_listed, 
                    SUM(COALESCE(c.fd_not_list, 0)) AS fd_not_listed,
                    SUM(COALESCE(c.aw_not_list, 0)) AS aw_not_listed,
                    SUM(COALESCE(c.imp_ia_not_list, 0)) AS imp_ia_not_listed,
                    SUM(COALESCE(c.oth_not_list, 0)) AS oth_not_listed
                FROM master.submaster s
                LEFT JOIN (
                    SELECT coram, th.submaster_id, 
                        COUNT(th.diary_no) AS not_listed,
                        SUM(CASE WHEN th.listorder IN (4, 5, 7, 25, 32) THEN 1 ELSE 0 END) AS fd_not_list,
                        SUM(CASE WHEN th.listorder = 8 THEN 1 ELSE 0 END) AS aw_not_list,
                        SUM(CASE WHEN doccode1 IS NOT NULL AND th.listorder NOT IN (8, 4, 5, 7, 25, 32) THEN 1 ELSE 0 END) AS imp_ia_not_list,
                        SUM(CASE WHEN doccode1 IS NULL AND th.listorder NOT IN (8, 4, 5, 7, 25, 32) THEN 1 ELSE 0 END) AS oth_not_list 
                    FROM (
                    SELECT h.coram, d.doccode1, mc.submaster_id, h.listorder, h.diary_no
                    FROM heardt h
                    INNER JOIN main m ON m.diary_no = h.diary_no
                    LEFT JOIN docdetails d ON d.diary_no = m.diary_no 
                        AND d.display = 'Y' AND d.iastat = 'P' 
                        AND d.doccode = 8 AND d.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 226, 3, 309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 71, 72, 102, 118, 131, 211, 309)
                    LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no AND mc.display = 'Y'
                        AND mc.submaster_id NOT IN (911, 912, 914, 239, 240, 241, 242, 243, 331, 9999)
                        AND (h.listorder NOT IN (4, 5) OR mc.submaster_id NOT IN (343, 15, 16, 17, 18, 19, 20, 21, 22, 23, 341, 353, 157, 158, 159, 160, 161, 162, 163, 166, 173, 175, 176, 322, 222))
                    LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                    WHERE rd.fil_no IS NULL AND mc.diary_no IS NOT NULL AND m.c_status = 'P' 
                    AND (m.diary_no = m.conn_key::bigint OR m.conn_key IS NULL OR m.conn_key = '0')
                    AND h.next_dt = '$list_dt' AND h.board_type = '$board_type' 
                    AND h.mainhead = 'M' AND h.clno = 0 AND h.brd_slno = 0 
                    AND h.main_supp_flag = 0 AND (h.listorder NOT IN (4, 5) OR h.is_nmd = 'N')
                    AND h.listorder != 32
                    AND (h.coram LIKE '%$jcode,%' OR h.coram = '$jcode')
                    AND h.subhead IN (824, 810, 803, 802, 807, 804, 808, 811, 812, 813, 814, 815, 816)
                    GROUP BY h.diary_no,d.doccode1, mc.submaster_id, h.listorder,h.coram
                    ) th
                    GROUP BY th.submaster_id,th.submaster_id,coram
                ) c ON s.id = c.submaster_id
                WHERE s.flag = 's' AND s.display = 'Y' 
                AND not_listed != 0
                GROUP BY s.id,coram
                ) t 
                GROUP BY subcode1,coram,id,sub_name1
                ORDER BY sub_name1";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

    /**
     * To fetch listing purpose
     */
    public function get_listing_purpose()
    {
        $builder = $this->db->table('master.listing_purpose');
        $builder->select('code, CONCAT(code, \'. \', purpose) AS lp');
        $builder->where('code !=', 22);
        $builder->where('purpose IS NOT NULL');
        $builder->where('display', 'Y');
        $builder->orderBy('priority');
        $query = $builder->get();
        $results = $query->getResultArray();
        return $results;
    }

    /**
     * To fetch blank category cases
     */
    public function blank_category_report($ucode, $usertype, $board_type, $mainhead, $reg_unreg, $listorder)
    {
        $results = [];
        $diary_reg_un = '';
        if ($reg_unreg == 1) {
            $diary_reg_un = " AND  m.reg_no_display != '' ";
        } elseif ($reg_unreg == 2) {
            $diary_reg_un = " AND m.reg_no_display = '' ";
        }

        $listorder_q = '';
        if ($listorder != 0) {
            $listorder_q = " AND  h.listorder = " . $this->db->escape($listorder);
        }

        $sql = "SELECT DISTINCT
            m.diary_no,    
            l.purpose, s.stagename, 
            m.reg_no_display,
            pno,
            rno,
            pet_name,
            res_name,
            tentative_section(m.diary_no) AS section_name,
            tentative_da(m.diary_no) AS da_name,
            CAST(SUBSTRING(m.diary_no::TEXT, -4) AS BIGINT) AS diary_no_suffix,
            CAST(LEFT(m.diary_no::TEXT, LENGTH(m.diary_no::TEXT) - 4) AS BIGINT) AS diary_no_prefix
        FROM main m 
            INNER JOIN heardt h ON h.diary_no = m.diary_no
            LEFT JOIN master.listing_purpose l ON l.code = h.listorder AND l.display = 'Y'
            LEFT JOIN master.subheading s ON s.stagecode = h.subhead and s.display = 'Y' and s.listtype = 'M'
            left join mul_category mc on mc.diary_no = h.diary_no and mc.display = 'Y' and mc.submaster_id != 0
        WHERE 
        m.c_status = 'P' and mc.submaster_id is null $listorder_q  $diary_reg_un AND 
        h.board_type = '$board_type' AND h.mainhead = '$mainhead'     
        
        and h.next_dt IS NOT NULL 
        GROUP BY m.diary_no, l.purpose, s.stagename
        ORDER BY 
        tentative_section(m.diary_no), tentative_da(m.diary_no),
        diary_no_suffix ASC, diary_no_prefix ASC";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $results = $query->getResultArray();
        }
        return $results;
    }

    /**
     * To fetch blank coram cases
     */
    public function blank_coram_get($board_type, $mainhead, $reg_unreg)
    {
        $results = [];
        $diary_reg_un = ($reg_unreg == 1) ? " AND m.reg_no_display != '' " : (($reg_unreg == 2) ? " AND m.reg_no_display = '' " : '');
        $sql = "SELECT DISTINCT
                    m.diary_no,    
                    l.purpose, s.stagename, 
                    m.reg_no_display,
                    pno,
                    rno,
                    pet_name,
                    res_name,
                    tentative_section(m.diary_no) AS section_name,
                    tentative_da(m.diary_no) AS da_name,
                    CAST(SUBSTRING(m.diary_no::TEXT, -4) AS BIGINT) AS diary_no_suffix,
                    CAST(LEFT(m.diary_no::TEXT, LENGTH(m.diary_no::TEXT) - 4) AS BIGINT) AS diary_no_prefix
                FROM main m 
                    INNER JOIN heardt h ON h.diary_no = m.diary_no
                    LEFT JOIN master.listing_purpose l ON l.code = h.listorder AND l.display = 'Y'
                    LEFT JOIN master.subheading s ON s.stagecode = h.subhead and s.display = 'Y' and s.listtype = 'M'    
                WHERE 
                m.c_status = 'P' $diary_reg_un AND 
                h.board_type = '$board_type' AND h.mainhead = '$mainhead'     
                AND (m.diary_no = m.conn_key::bigint OR m.conn_key IS NULL OR m.conn_key = '0')
                and (h.coram is null or trim(h.coram) = '' or h.coram = '0')
                and h.next_dt is not null and h.listorder != 32 and h.clno = 0
                AND h.subhead IN (824,810,803,802,807,804,808,811,812,813,814,815,816)
                GROUP BY m.diary_no, l.purpose, s.stagename
                ORDER BY 
                tentative_section(m.diary_no), tentative_da(m.diary_no),
                diary_no_suffix ASC, diary_no_prefix ASC";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $results = $query->getResultArray();
        }
        return $results;
    }


    /**
     * To fetch bunch matters
     */
    public function bunch_matter_get($bunch_type, $mainhead, $grp_hv)
    {
        $results = [];

        if ($bunch_type == 2) {

            $builder = $this->db->table('main m');
            $builder->select('m.diary_no, ct.short_description, m.active_fil_no, m.active_reg_year, COUNT(*) as cnt')
                ->join('heardt h', 'm.diary_no = h.diary_no', 'inner')
                ->join('conct c', "c.conn_key = CAST(m.conn_key AS BIGINT)", 'inner')
                ->join('master.casetype ct', 'm.active_casetype_id = ct.casecode', 'left');

            $builder->where('m.c_status', 'P');
            $builder->where('c.list', 'Y');
            $builder->where('CAST(m.diary_no AS BIGINT) = CAST(m.conn_key AS BIGINT)');
            $builder->where('h.mainhead', $mainhead);
            $builder->groupBy('c.conn_key, m.diary_no, ct.short_description');
            $builder->having('COUNT(*) >', $grp_hv);

            $builder->orderBy("CAST(SUBSTRING(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 3, 4) AS INTEGER), 
            CAST(SUBSTRING(CAST(m.diary_no AS TEXT), 1, LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER)");

            $query = $builder->get();
            $results = $query->getResultArray();
        }

        if ($bunch_type == 1) {

            $subquery1 = $this->db->table('master.submaster')
                ->select('sub_name1')
                ->where('display', 'Y')
                ->where('flag', 's')
                ->where('flag_use', 'S');

            $subquery2 = $this->db->table('main m')
                ->select('s.sub_name1, COUNT(*) as ttt, string_agg(m.diary_no::TEXT, \',\') as cdno')
                ->join('heardt h', 'm.diary_no = h.diary_no', 'inner')
                ->join('conct c', "c.conn_key = CAST(m.conn_key AS BIGINT)", 'inner')
                ->join('mul_category mc', 'mc.diary_no = m.diary_no', 'inner')
                ->join('master.submaster s', 's.id = mc.submaster_id', 'inner')
                ->where('s.display', 'Y')
                ->where('mc.display', 'Y')
                ->where('m.c_status', 'P')
                ->where('c.list', 'Y')
                ->where('CAST(m.diary_no AS BIGINT) = CAST(m.conn_key AS BIGINT)')
                ->where('h.mainhead', $mainhead)
                ->groupBy('c.conn_key, s.sub_name1')
                ->having('COUNT(*) >', $grp_hv);


            $builder = $this->db->table('master.submaster s');
            $builder->select('c.sub_name1, COALESCE(b.ttt, 0) as cnt, b.cdno')  // Use COALESCE for NULL handling
                ->from('(' . $subquery1->getCompiledSelect() . ') c')
                ->join('(' . $subquery2->getCompiledSelect() . ') b', 'c.sub_name1 = b.sub_name1', 'left')
                ->groupBy('c.sub_name1, b.ttt, b.cdno');

            $query = $builder->get();
            $results = $query->getResultArray();
        }

        return $results;
    }


    public function bunch_matter_dno_detail($diary_no)
    {
        $sql = " SELECT a.conn_key, m.diary_no, 
                c1.short_description, EXTRACT(YEAR FROM m.active_fil_dt) AS fyr, active_reg_year, active_fil_dt,
                    active_fil_no, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date         
                FROM 
                (SELECT m.conn_key FROM main m WHERE m.diary_no = '" . $diary_no . "') a
                INNER JOIN main m ON CAST(m.conn_key AS BIGINT) = CAST(a.conn_key AS BIGINT)
                INNER JOIN conct c ON c.diary_no = m.diary_no
                INNER JOIN heardt h ON h.diary_no = m.diary_no
                LEFT JOIN master.casetype c1 ON m.active_casetype_id = c1.casecode
                WHERE m.c_status = 'P' 
                ORDER BY a.conn_key, CASE WHEN CAST(a.conn_key AS BIGINT) = m.diary_no THEN 1 ELSE 999 END ASC, m.diary_no";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $results = $query->getResultArray();

            $radvname = $padvname = '';
            foreach ($results as $index => $row) {
                $advocate = $this->get_details($row['diary_no']);
                if (!empty($advocate)) {
                    $radvname = $advocate["r_n"];
                    $padvname = $advocate["p_n"];
                }

                if ($row['pno'] == 2) {
                    $pet_name = $row['pet_name'] . " AND ANR.";
                } else if ($row['pno'] > 2) {
                    $pet_name = $row['pet_name'] . " AND ORS.";
                } else {
                    $pet_name = $row['pet_name'];
                }
                if ($row['rno'] == 2) {
                    $res_name = $row['res_name'] . " AND ANR.";
                } else if ($row['rno'] > 2) {
                    $res_name = $row['res_name'] . " AND ORS.";
                } else {
                    $res_name = $row['res_name'];
                }

                $results[$index]['radvname'] = !empty($radvname) ? str_replace(",", ", ", trim($radvname, ",")) : '';
                $results[$index]['padvname'] = !empty($padvname) ? str_replace(",", ", ", trim($padvname, ",")) : '';
                $results[$index]['pet_name'] = $pet_name;
                $results[$index]['res_name'] = $res_name;
            }
        }

        return $results;
    }

    public function get_details($diary_no)
    {
        $return = [];
        $advsql = "SELECT a.*, 
                    STRING_AGG(CASE WHEN pet_res = 'R' THEN grp_adv END, '' ORDER BY adv_type DESC, pet_res_no ASC) AS r_n,
                    STRING_AGG(CASE WHEN pet_res = 'P' THEN grp_adv END, '' ORDER BY adv_type DESC, pet_res_no ASC) AS p_n 
                    FROM (
                        SELECT a.diary_no, b.name, 
                            STRING_AGG(COALESCE(a.adv, ''), '' ORDER BY pet_res ASC, adv_type DESC, pet_res_no ASC) AS grp_adv, 
                            a.pet_res, a.adv_type, pet_res_no
                        FROM advocate a LEFT JOIN master.bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y' WHERE a.diary_no='" . $diary_no . "' AND a.display = 'Y' 
                        GROUP BY a.diary_no, b.name,  a.pet_res, a.adv_type, a.pet_res_no
                        ORDER BY pet_res ASC, adv_type DESC, pet_res_no ASC) a 
                        GROUP BY diary_no, a.name, a.grp_adv, a.pet_res, a.adv_type, a.pet_res_no";

        $query = $this->db->query($advsql);
        if ($query->getNumRows() >= 1) {
            $results = $query->getRowArray();
            $return = $results;
        }
        /* $diary_no = $this->db->escape($diary_no);  // Ensure $diary_no is properly escaped

        // Subquery for inner select (the `a` alias)
        $subquery = $this->db->table('advocate a')
            ->select('a.diary_no, b.name')
            ->select('STRING_AGG(COALESCE(a.adv, \'\'), \'\') AS grp_adv')  // Concatenate `adv` values
            ->select('a.pet_res, a.adv_type, a.pet_res_no')
            ->join('master.bar b', 'a.advocate_id = b.bar_id AND b.isdead != \'Y\'', 'left')
            ->where('a.diary_no', $diary_no)
            ->where('a.display', 'Y')
            ->groupBy('a.diary_no, b.name, a.pet_res, a.adv_type, a.pet_res_no')
            ->orderBy('pet_res', 'ASC')
            ->orderBy('adv_type', 'DESC')
            ->orderBy('pet_res_no', 'ASC');
        
        // Main query using the subquery and grouping
        $builder = $this->db->table('(' . $subquery->getCompiledSelect() . ') a')
            ->select('a.*')
            // Correct usage of ORDER BY inside STRING_AGG without quotes
            ->select("STRING_AGG(CASE WHEN pet_res = 'R' THEN grp_adv END, '' ORDER BY adv_type DESC, pet_res_no ASC) AS r_n", false)
            ->select("STRING_AGG(CASE WHEN pet_res = 'P' THEN grp_adv END, '' ORDER BY adv_type DESC, pet_res_no ASC) AS p_n", false)
            ->groupBy('a.diary_no, a.name, a.grp_adv, a.pet_res, a.adv_type, a.pet_res_no');
            //pr($builder->getCompiledSelect());
        // Execute the query
        $query = $builder->get();
        
        // Check if query returned any results
        if ($query->getNumRows() > 0) {
            // Get the results
            $results = $query->getResultArray();
            $return = $results;
        } else {
            $return = [];
        }*/

        return $return;
    }

    // new model pushpendra

    public function sc_disposed_cav_verification_get($mainhead)
    {
        if ($mainhead == 'M') {
            $main_head = " AND NOT (m.fil_no_fh IS NOT NULL AND m.fil_no_fh != '') ";
        }
        if ($mainhead == 'F') {
            $main_head = " AND (m.fil_no_fh IS NOT NULL AND m.fil_no_fh != '') ";
        }
        $sql = "SELECT 
                    m.conn_key,
                    m.diary_no AS disposed_diary_no, 
                    m.reg_no_display AS disposed_case_no, 
                    CONCAT(m.pet_name, ' Vs. ', m.res_name) AS disposed_cause_title, 
                    TO_CHAR(d.ord_dt, 'DD-MM-YYYY') AS order_dt,
                    (
                        SELECT string_agg(j.abbreviation, ', ' ORDER BY j.judge_seniority) 
                        FROM master.judge j 
                        WHERE POSITION(j.jcode::text IN d.jud_id) > 0
                    ) AS disposed_by,
                    TO_CHAR(m.fil_dt, 'DD-MM-YYYY HH:MI AM') AS fil_dt_f,
                    CASE 
                        WHEN (m.reg_year_mh = 0 OR m.fil_dt::date > DATE '2017-05-10') 
                        THEN EXTRACT(YEAR FROM m.fil_dt) 
                        ELSE m.reg_year_mh 
                    END AS m_year,
                    SPLIT_PART(m.fil_no, '-', 1) AS casecode,
                    m.fil_no,
                    m.fil_no_fh,
                    TO_CHAR(m.fil_dt_fh, 'DD-MM-YYYY HH:MI AM') AS fil_dt_fh,
                    CASE 
                        WHEN m.reg_year_fh = 0 
                        THEN EXTRACT(YEAR FROM m.fil_dt_fh) 
                        ELSE m.reg_year_fh 
                    END AS f_year,
                    CASE 
                        WHEN m.fil_no != '' 
                        THEN SPLIT_PART(m.fil_no, '-', 1) 
                        ELSE '' 
                    END AS ct1,
                    CASE 
                        WHEN m.fil_no != '' 
                        THEN SPLIT_PART(SPLIT_PART(m.fil_no, '-', 2), '-', -1) 
                        ELSE '' 
                    END AS crf1,
                    CASE 
                        WHEN m.fil_no != '' 
                        THEN SPLIT_PART(m.fil_no, '-', -1) 
                        ELSE '' 
                    END AS crl1,
                    CASE 
                        WHEN m.fil_no_fh != '' 
                        THEN SPLIT_PART(m.fil_no_fh, '-', 1) 
                        ELSE '' 
                    END AS ct2,
                    CASE 
                        WHEN m.fil_no_fh != '' 
                        THEN SPLIT_PART(SPLIT_PART(m.fil_no_fh, '-', 2), '-', -1) 
                        ELSE '' 
                    END AS crf2,
                    CASE 
                        WHEN m.fil_no_fh != '' 
                        THEN SPLIT_PART(m.fil_no_fh, '-', -1) 
                        ELSE '' 
                    END AS crl2
                    FROM main m
                    INNER JOIN public.heardt h ON h.diary_no = m.diary_no
                    INNER JOIN dispose d ON d.diary_no = h.diary_no
                    WHERE 
                    m.c_status = 'D' 
                    AND h.subhead IN (818) 
                    $main_head
                    GROUP BY m.diary_no, d.ord_dt ,d.jud_id
                    ORDER BY d.ord_dt 
                    LIMIT 1000";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
    public function sc_disposed_cav_verification_table_get($rop_chk_dno)
    {
        $sql = "SELECT diary_no,jm AS pdfname, dated AS orderdate FROM (SELECT o.diary_no diary_no, o.pdfname jm, TO_CHAR(o.orderdate, 'DD-MM-YYYY') dated, CASE WHEN o.type = 'O' THEN 'ROP' WHEN o.type = 'J' THEN 'Judgement' END AS jo FROM ordernet o WHERE o.diary_no =  ) tbl1 WHERE jo='Judgement' ORDER BY tbl1.dated DESC";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
    public function get_ma_info($c_type, $c_no, $c_yr)
    {
        $ex_explode = explode('-', $c_no);
        $lct_caseno = '';
        for ($index = 0; $index < count($ex_explode); $index++) {
            if ($lct_caseno == '')
                $lct_caseno = $ex_explode[$index];
            else
                $lct_caseno = $lct_caseno . ',' . $ex_explode[$index];
        }
        $sql = "SELECT DISTINCT diary_no FROM lowerct WHERE lct_casetype = '437' AND lct_caseno IN ('288') AND lct_caseyear = '2009' AND lw_display = 'Y' AND ct_code = 4";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();

        $outer_array = [];
        foreach ($result as $row) {
            $inner_array = array();
            $inner_array[0] = $row['diary_no'];
            $outer_array[] = $inner_array;
        }
        return $outer_array;
    }
    public function case_status($in_array_var)
    {
        $sql = "SELECT 
                        m.diary_no, 
                        m.reg_no_display, 
                        m.c_status,
                        (
                            SELECT STRING_AGG(j.abbreviation, ',' ORDER BY j.judge_seniority)
                            FROM master.judge j 
                            WHERE ARRAY_POSITION(string_to_array(h.coram, ','), CAST(j.jcode AS TEXT)) > 0
                        ) AS new_coram
                    FROM 
                        main m
                    INNER JOIN 
                        heardt h ON h.diary_no = m.diary_no
                    WHERE 
                        m.diary_no = 22015";
    }
    public function get_ct_listed_disposed($start_dt, $end_dt)
    {
        $sql = "SELECT 
                    listed.casecode, 
                    listed.short_description, 
                    listed.misc_main AS listed_misc_main, 
                    listed.misc_conn AS listed_misc_conn, 
                    listed.regular_main AS listed_regular_main, 
                    listed.regular_conn AS listed_regular_conn, 
                    listed.total_Main AS listed_total_Main, 
                    listed.total_Conn AS listed_total_Conn, 
                    disposed.misc_main AS disposed_misc_main, 
                    disposed.misc_conn AS disposed_misc_conn, 
                    disposed.regular_main AS disposed_regular_main, 
                    disposed.regular_conn AS disposed_regular_conn, 
                    disposed.total_Main AS disposed_total_Main, 
                    disposed.total_Conn AS disposed_total_Conn 
                    FROM 
                    (
                        SELECT 
                        c.casecode, 
                        c.short_description, 
                        SUM(
                            CASE WHEN (
                            m.mf_active = 'M' 
                            AND (
                                m.conn_key = '0' 
                                OR m.conn_key IS NULL 
                                OR m.conn_key :: int = m.diary_no
                            )
                            ) THEN 1 ELSE 0 END
                        ) AS misc_main, 
                        SUM(
                            CASE WHEN (
                            m.mf_active = 'M' 
                            AND (
                                m.conn_key != '0' 
                                AND m.conn_key IS NOT NULL 
                                AND m.conn_key :: int != m.diary_no
                            )
                            ) THEN 1 ELSE 0 END
                        ) AS misc_conn, 
                        SUM(
                            CASE WHEN (
                            m.mf_active = 'F' 
                            AND (
                                m.conn_key = '0' 
                                OR m.conn_key IS NULL 
                                OR m.conn_key :: int = m.diary_no
                            )
                            ) THEN 1 ELSE 0 END
                        ) AS regular_main, 
                        SUM(
                            CASE WHEN (
                            m.mf_active = 'F' 
                            AND (
                                m.conn_key :: int != 0 
                                AND m.conn_key IS NOT NULL 
                                AND m.conn_key :: int != m.diary_no
                            )
                            ) THEN 1 ELSE 0 END
                        ) AS regular_conn, 
                        SUM(
                            CASE WHEN (
                            m.conn_key = '0' 
                            OR m.conn_key IS NULL 
                            OR m.conn_key :: int = m.diary_no
                            ) THEN 1 ELSE 0 END
                        ) AS total_main, 
                        SUM(
                            CASE WHEN (
                            m.conn_key :: int != 0 
                            AND m.conn_key IS NOT NULL 
                            AND m.conn_key :: int != m.diary_no
                            ) THEN 1 ELSE 0 END
                        ) AS total_conn 
                        FROM 
                        main m 
                        INNER JOIN (
                            SELECT 
                            DISTINCT diary_no, 
                            next_dt, 
                            judges 
                            FROM 
                            (
                                SELECT 
                                diary_no, 
                                next_dt, 
                                judges, 
                                board_type 
                                FROM 
                                heardt 
                                WHERE 
                                next_dt BETWEEN '$start_dt' 
                                AND '06-11-2024' 
                                AND clno != 0 
                                AND brd_slno != 0 
                                AND roster_id != 0 
                                AND judges != '0' 
                                AND roster_id NOT IN (29, 30) 
                                AND board_type = 'J' 
                                UNION ALL 
                                SELECT 
                                diary_no, 
                                next_dt, 
                                judges, 
                                board_type 
                                FROM 
                                last_heardt 
                                WHERE 
                                next_dt BETWEEN '$start_dt' 
                                AND '$end_dt' 
                                AND (
                                    bench_flag IS NULL 
                                    OR bench_flag is null
                                ) 
                                AND clno != 0 
                                AND brd_slno != 0 
                                AND roster_id != 0 
                                AND judges != '0' 
                                AND roster_id NOT IN (29, 30) 
                                AND board_type = 'J'
                            ) bb
                        ) aa ON m.diary_no = aa.diary_no 
                        LEFT JOIN master.casetype c ON c.casecode = m.casetype_id 
                        GROUP BY 
                        c.casecode, 
                        c.short_description
                    ) listed 
                    LEFT JOIN (
                        SELECT 
                        c.casecode, 
                        c.short_description, 
                        SUM(
                            CASE WHEN (
                            m.mf_active = 'M' 
                            AND (
                                m.conn_key = '0' 
                                OR m.conn_key IS NULL 
                                OR m.conn_key :: int = m.diary_no
                            )
                            ) THEN 1 ELSE 0 END
                        ) AS misc_main, 
                        SUM(
                            CASE WHEN (
                            m.mf_active = 'M' 
                            AND (
                                m.conn_key :: int != 0 
                                AND m.conn_key IS NOT NULL 
                                AND m.conn_key :: int != m.diary_no
                            )
                            ) THEN 1 ELSE 0 END
                        ) AS misc_conn, 
                        SUM(
                            CASE WHEN (
                            m.mf_active = 'F' 
                            AND (
                                m.conn_key = '0' 
                                OR m.conn_key IS NULL 
                                OR m.conn_key :: int = m.diary_no
                            )
                            ) THEN 1 ELSE 0 END
                        ) AS regular_main, 
                        SUM(
                            CASE WHEN (
                            m.mf_active = 'F' 
                            AND (
                                m.conn_key :: int != 0 
                                AND m.conn_key IS NOT NULL 
                                AND m.conn_key :: int != m.diary_no
                            )
                            ) THEN 1 ELSE 0 END
                        ) AS regular_conn, 
                        SUM(
                            CASE WHEN (
                            m.conn_key::int = 0 
                            OR m.conn_key IS NULL 
                            OR m.conn_key :: int = m.diary_no
                            ) THEN 1 ELSE 0 END
                        ) AS total_main, 
                        SUM(
                            CASE WHEN (
                            m.conn_key :: int != 0 
                            AND m.conn_key IS NOT NULL 
                            AND m.conn_key :: int != m.diary_no
                            ) THEN 1 ELSE 0 END
                        ) AS total_conn 
                        FROM 
                        main m 
                        INNER JOIN heardt h ON m.diary_no = h.diary_no 
                        INNER JOIN dispose d ON m.diary_no = d.diary_no 
                        LEFT JOIN master.casetype c ON c.casecode = m.casetype_id 
                        WHERE 
                        d.ord_dt BETWEEN '$start_dt' 
                        AND '$end_dt' 
                        AND c_status = 'D' 
                        AND h.board_type = 'J' 
                        GROUP BY 
                        c.casecode, 
                        c.short_description
                    ) disposed ON listed.casecode = disposed.casecode 
                    ORDER BY 
                    listed.short_description";
        $query  = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
    public function get_ct_listed_disposed_popup($flag, $start_dt, $end_dt, $ct)
    {
        $mainhead = '';
        $with_conn = '';
        $sql = "";
        if ($flag == 1 or $flag == 7) {
            $with_conn = " AND (m.diary_no = m.conn_key::int or m.conn_key is null or m.conn_key = '' or m.conn_key = '0')";
            $mainhead = "and m.mf_active='M'";
        }
        if ($flag == 2 or $flag == 8) {
            $with_conn = " AND (m.diary_no != m.conn_key::int AND m.conn_key > 0)";
            $mainhead = "and m.mf_active='M'";
        }
        if ($flag == 3 or $flag == 9) {
            $with_conn = " AND (m.diary_no = m.conn_key::int or m.conn_key is null or m.conn_key = '' or m.conn_key = '0')";
            $mainhead = "and m.mf_active='F'";
        }
        if ($flag == 4 or $flag == 10) {
            $with_conn = " AND (m.diary_no != m.conn_key ::int AND m.conn_key > 0)";
            $mainhead = "and m.mf_active='F'";
        }
        if ($flag == 5 or $flag == 11) {
            $with_conn = " AND (m.diary_no = m.conn_key ::int or m.conn_key is null or m.conn_key = '' or m.conn_key = '0')";
        }
        if ($flag == 6 or $flag == 12) {
            $with_conn = " AND (m.diary_no != m.conn_key::int AND m.conn_key > 0)";
        }
        if ($flag == 1 or $flag == 2 or $flag == 3 or $flag == 4 or $flag == 5 or $flag == 6) {
            $sql = "SELECT 
                    aa.next_dt AS dttt,
                    m.conn_key, 
                    m.diary_no, 
                    c.short_description, 
                    EXTRACT(YEAR FROM m.active_fil_dt) AS fyr,
                    m.active_reg_year, 
                    m.active_fil_dt,
                    m.active_fil_no, 
                    m.pet_name, 
                    m.res_name, 
                    m.pno, 
                    m.rno, 
                    m.casetype_id, 
                    m.ref_agency_state_id, 
                    m.diary_no_rec_date                       
                        FROM main m
                        INNER JOIN (
                            SELECT DISTINCT diary_no, next_dt, judges
                            FROM (
                                SELECT diary_no, next_dt, judges, board_type 
                                FROM heardt 
                                WHERE next_dt BETWEEN '$start_dt' AND '$end_dt'  
                                AND clno != 0 
                                AND brd_slno != 0 
                                AND roster_id != 0 
                                AND judges != '0' 
                                AND roster_id NOT IN (29, 30) 
                                AND board_type = 'J'
                                UNION ALL
                                SELECT diary_no, next_dt, judges, board_type 
                                FROM last_heardt 
                                WHERE next_dt BETWEEN '$start_dt' AND '$end_dt' 
                                AND (bench_flag IS NULL OR bench_flag is null) 
                                AND clno != '0' 
                                AND brd_slno != 0 
                                AND roster_id != 0 
                                AND judges != '0' 
                                AND roster_id NOT IN (29, 30) 
                                AND board_type = 'J'
                            ) bb
                        ) aa ON m.diary_no = aa.diary_no
                        LEFT JOIN master.casetype c ON c.casecode = m.casetype_id
                        WHERE m.casetype_id = '$ct' $with_conn $mainhead
                        GROUP BY m.diary_no, aa.next_dt,c.short_description
                        ORDER BY 
                            m.conn_key, 
                            CASE WHEN m.conn_key ::int= m.diary_no THEN 1 ELSE 999 END ASC, m.diary_no";
        }
        if ($flag == 7 or $flag == 8 or $flag == 9 or $flag == 10 or $flag == 11 or $flag == 12) {
            $sql = "SELECT 
                        d.ord_dt AS dttt,
                        m.conn_key, 
                        m.diary_no, 
                        c.short_description, 
                        EXTRACT(YEAR FROM m.active_fil_dt) AS fyr,
                        m.active_reg_year, 
                        m.active_fil_dt,
                        m.active_fil_no, 
                        m.pet_name, 
                        m.res_name, 
                        m.pno, 
                        m.rno, 
                        m.casetype_id, 
                        m.ref_agency_state_id, 
                        m.diary_no_rec_date                       
                    FROM 
                        main m
                    INNER JOIN heardt h ON m.diary_no = h.diary_no 
                    INNER JOIN dispose d ON m.diary_no = d.diary_no 
                    LEFT JOIN master.casetype c ON c.casecode = m.casetype_id 
                    WHERE 
                        d.ord_dt BETWEEN '2024-01-01' AND '2024-07-01' 
                        AND m.c_status = 'D' 
                        AND h.board_type = 'J' 
                        AND m.casetype_id = '$ct' $with_conn $mainhead
                    GROUP BY 
                        m.diary_no, h.next_dt, d.ord_dt,c.short_description
                    ORDER BY 
                        m.conn_key, 
                        CASE WHEN m.conn_key ::int= m.diary_no THEN 1 ELSE 999 END ASC,
                        m.diary_no";
        }
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
    public function get_ct_listed_disposed_popup_table($diary_no)
    {
        $sql = "SELECT 
                    a.*, 
                    string_agg(
                        a.name || COALESCE(CASE WHEN a.pet_res = 'R' THEN a.grp_adv ELSE '' END, ''),
                        '' ORDER BY a.adv_type DESC, a.pet_res_no ASC
                    ) AS r_n,
                    string_agg(
                        a.name || COALESCE(CASE WHEN a.pet_res = 'P' THEN a.grp_adv ELSE '' END, ''),
                        '' ORDER BY a.adv_type DESC, a.pet_res_no ASC
                    ) AS p_n
                    FROM (
                    SELECT 
                        a.diary_no, 
                        b.name, 
                        string_agg(
                        COALESCE(a.adv::TEXT, ''), '' ORDER BY a.pet_res ASC, a.adv_type DESC, a.pet_res_no ASC
                        ) AS grp_adv, 
                        a.pet_res, 
                        a.adv_type, 
                        a.pet_res_no
                    FROM 
                        advocate a 
                    LEFT JOIN 
                        master.bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y'
                    WHERE 
                        a.diary_no = '$diary_no' 
                        AND a.display = 'Y'
                    GROUP BY 
                        a.diary_no, b.name, a.pet_res, a.adv_type, a.pet_res_no
                    ORDER BY 
                        a.pet_res ASC, a.adv_type DESC, a.pet_res_no ASC
                    ) a
                    GROUP BY 
                    a.diary_no,a.name,a.grp_adv,a.pet_res,a.adv_type,a.pet_res_no";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
    public function spread_out_cert_catwise_get($list_dt)
    {
        $sql = "SELECT 
                        (
                            select 
                            nos_court 
                            from 
                            (
                                SELECT 
                                t1.*, 
                                COUNT(sub_name1) nos_court 
                                FROM 
                                (
                                    SELECT 
                                    ss.sub_name1, 
                                    ss.subcode1 
                                    FROM 
                                    master.roster r 
                                    INNER JOIN category_allottment c ON c.ros_id = r.id 
                                    INNER JOIN master.roster_judge rj ON rj.roster_id = r.id 
                                    INNER JOIN master.submaster s ON s.id = c.submaster_id 
                                    LEFT JOIN master.submaster ss ON ss.subcode1 = s.subcode1 
                                    WHERE 
                                    ss.display = 'Y' 
                                    AND rj.display = 'Y' 
                                    AND s.display = 'Y' 
                                    AND c.display = 'Y' 
                                    AND r.display = 'Y' 
                                    AND r.m_f = '1' 
                                    AND r.from_date = '$list_dt' 
                                    GROUP BY 
                                    ss.subcode1, ss.sub_name1,
                                    r.courtno
                                ) t1 
                                GROUP BY 
                                subcode1 ,t1.sub_name1
                                ORDER BY 
                                sub_name1
                            ) dd 
                            where 
                            dd.subcode1 = t.subcode1
                        ) nos_court, 
                        id, 
                        subcode1, 
                        sub_name1, 
                        SUM(listed) AS listed, 
                        SUM(fd_list) AS fd_list, 
                        SUM(imp_ia_list) AS imp_ia_list, 
                        SUM(oth_list) AS oth_list, 
                        SUM(not_listed) AS not_listed, 
                        SUM(fd_not_listed) AS fd_not_listed, 
                        SUM(imp_ia_not_listed) AS imp_ia_not_listed, 
                        SUM(oth_not_listed) AS oth_not_listed 
                        FROM 
                        (
                            SELECT 
                            s.id, 
                            s.subcode1, 
                            s.sub_name1, 
                            SUM(COALESCE(b.listed, 0)) AS listed, 
                            SUM(COALESCE(b.fd_list, 0)) AS fd_list, 
                            SUM(COALESCE(b.imp_ia_list, 0)) AS imp_ia_list, 
                            SUM(COALESCE(b.oth_list, 0)) AS oth_list, 
                            SUM(COALESCE(c.not_listed, 0)) AS not_listed, 
                            SUM(COALESCE(c.fd_not_list, 0)) AS fd_not_listed, 
                            SUM(COALESCE(c.imp_ia_not_list, 0)) AS imp_ia_not_listed, 
                            SUM(COALESCE(c.oth_not_list, 0)) AS oth_not_listed 
                        FROM 
                            master.submaster s 
                        LEFT JOIN (
                            SELECT 
                                submaster_id, 
                                COUNT(submaster_id) AS listed, 
                                SUM(CASE 
                                        WHEN listorder IN (8, 4, 5, 7, 25, 32) THEN 1 
                                        ELSE 0 
                                    END) AS fd_list, 
                                SUM(CASE 
                                        WHEN doccode1 IS NOT NULL 
                                            AND listorder NOT IN (8, 4, 5, 7, 25, 32) THEN 1 
                                        ELSE 0 
                                    END) AS imp_ia_list, 
                                SUM(CASE 
                                        WHEN doccode1 IS NULL 
                                            AND listorder NOT IN (8, 4, 5, 7, 25, 32) THEN 1 
                                        ELSE 0 
                                    END) AS oth_list 
                            FROM (
                                SELECT 
                                    d.doccode1, 
                                    h.diary_no, 
                                    h.next_dt, 
                                    submaster_id, 
                                    h.listorder 
                                FROM 
                                    heardt h 
                                INNER JOIN main m ON m.diary_no = h.diary_no 
                                INNER JOIN mul_category mc ON mc.diary_no = m.diary_no 
                                LEFT JOIN docdetails d ON d.diary_no = m.diary_no 
                                AND d.display = 'Y' 
                                AND d.iastat = 'P' 
                                AND d.doccode = 8 
                                AND d.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 226, 3, 
                                                    309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 
                                                    16, 41, 49, 71, 72, 102, 118, 131, 211, 
                                                    309)
                                WHERE 
                                    mc.display = 'Y' 
                                    AND (m.diary_no = m.conn_key::int 
                                        OR m.conn_key = '' 
                                        OR m.conn_key IS NULL 
                                        OR m.conn_key = '0')
                                    AND h.next_dt = '$list_dt'
                                    AND clno > 0 
                                    AND h.brd_slno > 0 
                                    AND board_type = 'J' 
                                    AND h.subhead IN (824, 810, 803, 802, 807, 804, 808, 811, 
                                                    812, 813, 814, 815, 816)
                                GROUP BY 
                                    m.diary_no, d.doccode1, h.diary_no, mc.submaster_id
                                UNION 
                                SELECT 
                                    d.doccode1, 
                                    h.diary_no, 
                                    h.next_dt, 
                                    submaster_id, 
                                    h.listorder 
                                FROM 
                                    last_heardt h 
                                INNER JOIN main m ON m.diary_no = h.diary_no 
                                INNER JOIN mul_category mc ON mc.diary_no = m.diary_no 
                                LEFT JOIN docdetails d ON d.diary_no = m.diary_no 
                                AND d.display = 'Y' 
                                AND d.iastat = 'P' 
                                AND d.doccode = 8 
                                AND d.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 226, 3, 
                                                    309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 
                                                    16, 41, 49, 71, 72, 102, 118, 131, 211, 
                                                    309)
                                WHERE 
                                    mc.display = 'Y' 
                                    AND (m.diary_no = m.conn_key::int 
                                        OR m.conn_key = '' 
                                        OR m.conn_key IS NULL 
                                        OR m.conn_key = '0')
                                    AND h.next_dt = '$list_dt'
                                    AND clno > 0 
                                    AND h.brd_slno > 0 
                                    AND board_type = 'J' 
                                    AND h.subhead IN (824, 810, 803, 802, 807, 804, 808, 811, 
                                                    812, 813, 814, 815, 816) 
                                    AND (bench_flag = '' OR bench_flag IS NULL)
                                GROUP BY 
                                    m.diary_no, d.doccode1, h.diary_no, h.next_dt, mc.submaster_id, h.listorder
                            ) a 
                            GROUP BY submaster_id
                        ) b ON s.id = b.submaster_id 
                        LEFT JOIN (
                            SELECT 
                                th.submaster_id, 
                                COUNT(th.diary_no) AS not_listed, 
                                SUM(CASE 
                                        WHEN th.listorder IN (8, 4, 5, 7, 25, 32) THEN 1 
                                        ELSE 0 
                                    END) AS fd_not_list, 
                                SUM(CASE 
                                        WHEN doccode1 IS NOT NULL 
                                            AND th.listorder NOT IN (8, 4, 5, 7, 25, 32) THEN 1 
                                        ELSE 0 
                                    END) AS imp_ia_not_list, 
                                SUM(CASE 
                                        WHEN doccode1 IS NULL 
                                            AND th.listorder NOT IN (8, 4, 5, 7, 25, 32) THEN 1 
                                        ELSE 0 
                                    END) AS oth_not_list 
                            FROM (
                                SELECT 
                                    d.doccode1, 
                                    mc.submaster_id, 
                                    t.listorder, 
                                    t.diary_no 
                                FROM 
                                    transfer_old_com_gen_cases t 
                                INNER JOIN heardt h ON t.diary_no = h.diary_no 
                                INNER JOIN mul_category mc ON mc.diary_no = h.diary_no 
                                LEFT JOIN docdetails d ON d.diary_no = h.diary_no 
                                AND d.display = 'Y' 
                                AND d.iastat = 'P' 
                                AND d.doccode = 8 
                                AND d.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 226, 3, 
                                                    309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 
                                                    16, 41, 49, 71, 72, 102, 118, 131, 211, 
                                                    309)
                                WHERE 
                                    mc.display = 'Y' 
                                    AND (t.diary_no = t.conn_key 
                                        OR t.conn_key IS NOT NULL 
                                        OR t.conn_key IS NULL 
                                        OR t.conn_key = '0')
                                    AND next_dt_old = '$list_dt'
                                    AND next_dt_new > next_dt_old 
                                    AND h.board_type = 'J' 
                                    AND h.mainhead = 'M' 
                                    AND h.subhead IN (824, 810, 803, 802, 807, 804, 808, 811, 
                                                    812, 813, 814, 815, 816)
                                GROUP BY 
                                    h.diary_no, d.doccode1, mc.submaster_id, t.listorder, t.diary_no
                                UNION 
                                SELECT 
                                    d.doccode1, 
                                    mc.submaster_id, 
                                    h.listorder, 
                                    h.diary_no 
                                FROM 
                                    heardt h 
                                INNER JOIN main m ON m.diary_no = h.diary_no 
                                INNER JOIN mul_category mc ON mc.diary_no = m.diary_no 
                                LEFT JOIN docdetails d ON d.diary_no = m.diary_no 
                                AND d.display = 'Y' 
                                AND d.iastat = 'P' 
                                AND d.doccode = 8 
                                AND d.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 226, 3, 
                                                    309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 
                                                    16, 41, 49, 71, 72, 102, 118, 131, 211, 
                                                    309)
                                WHERE 
                                    m.c_status = 'P' 
                                    AND mc.display = 'Y' 
                                    AND (m.diary_no = m.conn_key::int 
                                        OR m.conn_key = '' 
                                        OR m.conn_key IS NULL 
                                        OR m.conn_key = '0')
                                    AND h.next_dt = '$list_dt'
                                    AND h.board_type = 'J' 
                                    AND h.mainhead = 'M' 
                                    AND h.clno = 0 
                                    AND h.brd_slno = 0 
                                    AND h.subhead IN (824, 810, 803, 802, 807, 804, 808, 811, 
                                                    812, 813, 814, 815, 816)
                                GROUP BY 
                                    h.diary_no, d.doccode1, mc.submaster_id
                            ) th 
                            GROUP BY th.submaster_id
                        ) c ON s.id = c.submaster_id 
                        WHERE 
                            s.flag = 's' 
                            AND s.display = 'Y' 
                            AND s.subcode1 NOT IN (146, 147, 148, 149, 8888, 9999) 
                        GROUP BY 
                            s.id
                        ) t 
                        GROUP BY 
                        subcode1,t.id, t.sub_name1
                        ORDER BY 
                        sub_name1";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }


    public function year_section_wise_pendency()
    {
        $sql = "SELECT 
                        active_reg_year AS CaseYear," . ' 
                        SUM("II") AS "II", 
                        SUM("II-A") AS "II-A", 
                        SUM("II-B") AS "II-B", 
                        SUM("II-C") AS "II-C", 
                        SUM("III") AS "III", 
                        SUM("III-A") AS "III-A", 
                        SUM("III-B") AS "III-B", 
                        SUM("IV") AS "IV", 
                        SUM("IV-A") AS "IV-A", 
                        SUM("IV-B") AS "IV-B", 
                        SUM("IX") AS "IX", 
                        SUM("PIL-W") AS "PIL-W", 
                        SUM("X") AS "X", 
                        SUM("XI") AS "XI", 
                        SUM("XI-A") AS "XI-A", 
                        SUM("XII") AS "XII", 
                        SUM("XII-A") AS "XII-A", 
                        SUM("XIV") AS "XIV", 
                        SUM("XV") AS "XV", 
                        SUM("XVI") AS "XVI", 
                        SUM("XVI-A") AS "XVI-A", 
                        SUM("XVII") AS "XVII", 
                        (
                            SELECT 
                            COUNT(1) 
                            FROM 
                            main 
                            WHERE 
                            fil_dt IS NOT NULL' . " 
                            AND c_status = 'P'
                        ) AS total 
                        FROM 
                        (
                            SELECT 
                            active_reg_year," . ' 
                            CASE WHEN section_id = 20 THEN totalmatter ELSE 0 END AS "II", 
                            CASE WHEN section_id = 21 THEN totalmatter ELSE 0 END AS "II-A", 
                            CASE WHEN section_id = 55 THEN totalmatter ELSE 0 END AS "II-B", 
                            CASE WHEN section_id = 74 THEN totalmatter ELSE 0 END AS "II-C", 
                            CASE WHEN section_id = 22 THEN totalmatter ELSE 0 END AS "III", 
                            CASE WHEN section_id = 23 THEN totalmatter ELSE 0 END AS "III-A", 
                            CASE WHEN section_id = 75 THEN totalmatter ELSE 0 END AS "III-B", 
                            CASE WHEN section_id = 24 THEN totalmatter ELSE 0 END AS "IV", 
                            CASE WHEN section_id = 25 THEN totalmatter ELSE 0 END AS "IV-A", 
                            CASE WHEN section_id = 26 THEN totalmatter ELSE 0 END AS "IV-B", 
                            CASE WHEN section_id = 27 THEN totalmatter ELSE 0 END AS "IX", 
                            CASE WHEN section_id = 32 THEN totalmatter ELSE 0 END AS "PIL-W", 
                            CASE WHEN section_id = 42 THEN totalmatter ELSE 0 END AS "X", 
                            CASE WHEN section_id = 43 THEN totalmatter ELSE 0 END AS "XI", 
                            CASE WHEN section_id = 44 THEN totalmatter ELSE 0 END AS "XI-A", 
                            CASE WHEN section_id = 45 THEN totalmatter ELSE 0 END AS "XII", 
                            CASE WHEN section_id = 54 THEN totalmatter ELSE 0 END AS "XII-A", 
                            CASE WHEN section_id = 48 THEN totalmatter ELSE 0 END AS "XIV", 
                            CASE WHEN section_id = 49 THEN totalmatter ELSE 0 END AS "XV", 
                            CASE WHEN section_id = 50 THEN totalmatter ELSE 0 END AS "XVI", 
                            CASE WHEN section_id = 51 THEN totalmatter ELSE 0 END AS "XVI-A", 
                            CASE WHEN section_id = 52 THEN totalmatter ELSE 0 END AS "XVII" 
                            FROM 
                            (
                                SELECT 
                                m.active_reg_year, 
                                u.section AS section_id, 
                                COUNT(1) AS totalmatter 
                                FROM 
                                main m 
                                LEFT JOIN master.users u ON m.dacode = u.usercode 
                                LEFT JOIN master.usersection us ON u.section = us.id 
                                WHERE 
                                m.fil_dt IS NOT NULL' . " 
                                AND m.c_status = 'P' 
                                AND us.isda = 'Y' 
                                AND us.display = 'Y' 
                                GROUP BY 
                                m.active_reg_year, 
                                u.section
                            ) AS aa
                        ) AS bb 
                        GROUP BY 
                        active_reg_year";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
    public function subject()
    {
        $sql = "SELECT * 
                FROM master.submaster 
                WHERE subcode2 = '0' 
                AND subcode3 = '0' 
                AND subcode4 = '0' 
                ORDER BY subcode1, subcode2, subcode3, subcode4";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
    public function act()
    {
        $builder = $this->db->table('master.act_master');
        $builder->where('act_name !=', '');
        $builder->where('display', 'Y');
        $builder->orderBy('act_name');
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }
    public function case_stage()
    {
        $builder = $this->db->table('master.master_case_status');
        $builder->select('*');
        $builder->where('flag_pd', 'P');
        $builder->orderBy('id');
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }
    public function get_subhead_for_ason($m_f)
    {
        $builder = $this->db->table('master.subheading');
        $builder->select('*');
        $builder->where('listtype', $m_f);
        $builder->where('display', 'Y');
        $builder->orderBy('stagecode');
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }
    public function getsubcat2_mul($str2)
    {
        $sql = "SELECT * FROM master.submaster WHERE " . $str2 . " AND subcode1!='0' AND subcode2!='0' AND subcode3!='0' AND subcode4!='0'AND display='Y'";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
    public function getcat_multiple($subject)
    {
        $builder = $this->db->table('master.submaster');
        $builder->whereIn('subcode1', [$subject])
            ->where('subcode1 !=', '0')
            ->where('subcode2 !=', '0')
            ->where('subcode3', '0')
            ->where('subcode4', '0')
            ->where('display', 'Y');
        // $sql= $builder->getCompiledSelect();
        // pr($sql);
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function getsubcat_mul($str)
    {
        $sql = "SELECT * FROM master.submaster WHERE " . $str . " AND subcode1!='0'  AND display='Y'";
        $query  = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
    public function get_year_head_nature_wise_ason_rpt()
    {
        $sql = "SELECT 
                        m.diary_no_rec_date, 
                        m.active_fil_no, 
                        m.active_fil_dt, 
                        m.active_reg_year, 
                        m.active_casetype_id,
                        m.casetype_id, 
                        c_status, 
                        d.rj_dt, 
                        d.month, 
                        d.year, 
                        d.disp_dt, 
                        r.disp_month, 
                        r.disp_year, 
                        r.conn_next_dt,
                        r.disp_dt AS res_disp_dt,
                        m.pet_name,
                        m.res_name,
                        mainhead_n,
                        m.next_dt,
                        m.bench,
                        m.lastorder,
                        h.judges,
                        m.diary_no
                    FROM main m 
                    LEFT JOIN heardt h 
                        ON m.diary_no = h.diary_no 
                    LEFT JOIN dispose d 
                        ON m.diary_no = d.diary_no 
                    LEFT JOIN restored r 
                        ON m.diary_no = r.diary_no 
                    LEFT JOIN act_main a 
                        ON a.diary_no = m.diary_no 
                    WHERE 1 = 1 
                    AND (
                        CASE 
                            WHEN r.disp_dt IS NOT NULL 
                                AND r.conn_next_dt IS NOT NULL 
                            THEN '2024-11-20' NOT BETWEEN r.disp_dt AND r.conn_next_dt
                            ELSE r.disp_dt IS NULL OR r.conn_next_dt IS NULL
                        END
                        OR r.diary_no IS NULL
                    ) 
                    AND m.diary_no_rec_date::date < '2024-11-20' 
                    AND substr(m.diary_no::text,-4) = ''
                    AND (
                        CASE 
                            WHEN m.active_casetype_id = 0 THEN m.casetype_id 
                            ELSE m.active_casetype_id 
                        END
                    ) = '24'
                    AND c_status = 'P' 
                    AND m.diary_no_rec_date::date < '2024-11-20'
                    GROUP BY m.diary_no ,d.rj_dt,d.month,d.year,d.disp_dt,r.disp_month,r.disp_year,r.conn_next_dt,r.disp_dt,h.mainhead_n,h.judges
                    ORDER BY 
                        substr(m.fil_no, 3, 3),
                        substr(m.fil_no, 11, 4),
                        substr(m.fil_no, 6, 5)";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
    public function regular_in_misc_get()
    {
        $sql = "SELECT 
                        ROW_NUMBER() OVER (
                            ORDER BY 
                            tentative_section(c.diary_no),
                            tentative_da(c.diary_no),
                            c.diary_no_rec_date
                        ) AS SNO, 
                        c.* 
                        FROM 
                        (
                            SELECT 
                            m.diary_no,
                            CONCAT(
                                m.reg_no_display, ' @ ', m.diary_no
                            ) AS REGNO_DNO, 
                            CONCAT(pet_name, ' Vs. ', res_name) AS TITLE, 
                            TO_CHAR(h.next_dt :: DATE, 'DD-MM-YYYY') AS Tentative_Date, 
                            tentative_section(m.diary_no) AS SECTION, 
                            tentative_da(m.diary_no) AS DA,
                            m.diary_no_rec_date
                            FROM 
                            heardt h 
                            INNER JOIN main m ON m.diary_no = h.diary_no 
                            LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no 
                            AND mc.display = 'Y' 
                            LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no 
                            AND rd.remove_def = 'N' 
                            WHERE 
                            rd.fil_no IS NULL 
                            AND mc.diary_no IS NOT NULL 
                            AND (
                                m.diary_no :: text = m.conn_key 
                                OR m.conn_key IS NULL 
                                OR m.conn_key = '' 
                                OR m.conn_key = '0'
                            ) 
                            AND m.c_status = 'P' 
                            AND h.mainhead = 'M' 
                            AND (
                                m.fil_no_fh IS NOT NULL 
                                AND m.fil_no_fh != ''
                            )
                            GROUP BY 
                            m.diary_no, 
                            m.reg_no_display, 
                            pet_name, 
                            res_name, 
                            h.next_dt,
                            m.diary_no_rec_date
                            ORDER BY 
                            tentative_section(m.diary_no), 
                            tentative_da(m.diary_no), 
                            m.diary_no_rec_date
                        ) c";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
    public function section_pendency(){
        return ;
    }
    ///kr************************************************************************************************************

    public function get_institution_report($from_dt, $to_dt, $rpt_type)
    {
        //pr($rpt_type);die;

        switch ($rpt_type) {
            case 'registration':
                $sql = "
                      SELECT substr(fil_no, 1, 2) fil_no, count(*) cnt, date(fil_dt) fil_dt, short_description, casename,
                          sum(case when DATE(diary_no_rec_date) BETWEEN '$from_dt' AND '$to_dt' then 1 else 0 end) filed,
                          sum(case when DATE(diary_no_rec_date) not BETWEEN '$from_dt' AND '$to_dt'  then 1 else 0 end) not_filed
                      FROM main m
                      INNER JOIN master.casetype c ON c.casecode::text = substr(fil_no, 1, 2)
                      WHERE DATE(fil_dt) BETWEEN '$from_dt' AND '$to_dt' 
                      GROUP BY substr(fil_no, 1, 2),m.fil_dt,c.short_description,c.casename";
                break;

            case 'institution':
                $sql = "
                      SELECT 
                          substr(fil_no, 1, 2) as case_code, 
                          COUNT(*) AS cnt, 
                          DATE(fil_dt) AS fil_dt, 
                          short_description, 
                          casename,
                          SUM(CASE WHEN DATE(diary_no_rec_date) BETWEEN '$fromDt' AND '$toDt' THEN 1 ELSE 0 END) AS filed,
                          SUM(CASE WHEN DATE(diary_no_rec_date) NOT BETWEEN '$fromDt' AND '$toDt' THEN 1 ELSE 0 END) AS not_filed
                      FROM main m
                      INNER JOIN casetype c ON c.casecode = substr(fil_no, 1, 2)
                      WHERE DATE(fil_dt) BETWEEN '$fromDt' AND '$toDt' AND substr(fil_no, 1, 2) != '39'
                      GROUP BY substr(fil_no, 1, 2)";
                break;

            case 'filing':
                $sql = "
                      SELECT 
                          substr(fil_no, 1, 2) AS case_code, 
                          COUNT(*) AS cnt, 
                          short_description, 
                          casename, 
                          DATE(diary_no_rec_date) AS fil_dt
                      FROM main m
                      INNER JOIN casetype c ON c.casecode = casetype_id
                      WHERE DATE(diary_no_rec_date) BETWEEN '$fromDt' AND '$toDt'
                      GROUP BY casetype_id";
                break;

            case 'defect':
                $sql = "
                      SELECT 
                          DATE(diary_no_rec_date) AS fil_dt, 
                          COUNT(*) AS cnt,
                          SUM(CASE WHEN fil_no = '' OR fil_no IS NULL THEN 1 ELSE 0 END) AS defect,
                          SUM(CASE WHEN (fil_no = '' OR fil_no IS NULL) AND not_reg_if_pen = 1 AND iastat = 'P' THEN 1 ELSE 0 END) AS defect_ia,
                          SUM(CASE WHEN NOT (fil_no = '' OR fil_no IS NULL) THEN 1 ELSE 0 END) AS not_defect
                      FROM main m
                      LEFT JOIN (
                          SELECT d.diary_no, iastat, not_reg_if_pen
                          FROM docdetails d
                          INNER JOIN docmaster d2 ON d.doccode = d2.doccode AND d.doccode1 = d2.doccode1
                          WHERE not_reg_if_pen = 1 AND iastat = 'P'
                          GROUP BY diary_no
                      ) t ON t.diary_no = m.diary_no
                      WHERE DATE(diary_no_rec_date) BETWEEN '$fromDt' AND '$toDt'
                      GROUP BY DATE(diary_no_rec_date)";
                break;

            case 'refiling':
                $sql = "
                      SELECT 
                          disp_dt AS fil_dt, 
                          short_description, 
                          COUNT(a.diary_no) AS cnt 
                      FROM (
                          SELECT DATE(disp_dt) AS disp_dt, diary_no 
                          FROM fil_trap 
                          WHERE remarks = 'FDR -> SCR' AND DATE(disp_dt) BETWEEN '$fromDt' AND '$toDt'
                          UNION ALL
                          SELECT DATE(disp_dt) AS disp_dt, diary_no 
                          FROM fil_trap_his 
                          WHERE remarks = 'FDR -> SCR' AND DATE(disp_dt) BETWEEN '$fromDt' AND '$toDt'
                      ) a
                      JOIN main m ON a.diary_no = m.diary_no
                      JOIN casetype c ON COALESCE(NULLIF(m.active_casetype_id, ''), casetype_id) = c.casecode
                      GROUP BY disp_dt, short_description";
                break;

            default:
                return [];
        }
        $query = $this->db->query($sql);
        return $query->getResultArray();
        // /return $result;
        echo '<pre>';
        print_r($result);
        echo '</pre>';
        die;
    }
    public function get_institutionDisposal_report($ddlYear, $ddlMonth)
    {
        $report_name = '';
        $month = $ddlMonth;
        $year = $ddlYear;
        $report_name = 'Institution';
        $query_date = date($year . '-' . $month . '-01');

        // First day of the month.
        $firstDate = date('Y-m-01', strtotime($query_date));

        // Last day of the month.
        $lastDate = date('Y-m-t', strtotime($query_date));
        $sql = "SELECT *
                          FROM (
                          SELECT 
                          SUM(CASE WHEN mf_active = 'F' THEN 0 ELSE 1 END) AS misc_institution,
                          SUM(CASE WHEN mf_active = 'F' THEN 1 ELSE 0 END) AS reg_institution,
                          SUM(CASE WHEN case_grp = 'C' THEN 1 ELSE 0 END) AS civil_institution,
                          SUM(CASE WHEN case_grp = 'R' THEN 1 ELSE 0 END) AS criminal_institution,
                          COUNT(diary_no) AS inst
                          FROM (
                          SELECT m.diary_no, m.fil_dt, unreg_fil_dt, mf_active, case_grp
                          FROM main m
                          WHERE (
                          (unreg_fil_dt::date IS NOT NULL AND unreg_fil_dt::date != '0001-01-01'::date)
                          AND (
                              unreg_fil_dt::date <= m.fil_dt::date 
                              OR m.fil_dt::date = '0001-01-01'::date
                          )
                          AND unreg_fil_dt::date BETWEEN '$firstDate' AND '$lastDate'
                          OR (
                              m.fil_dt::date BETWEEN '$firstDate' AND '$lastDate'
                              AND m.fil_dt::date != '0001-01-01'::date
                          )
                          )
                          AND (
                          SUBSTRING(m.fil_no FROM 1 FOR 2) NOT IN ('39')
                          OR m.fil_no = ''
                          OR m.fil_no IS NULL
                          )
                          GROUP BY m.diary_no
                          ) AS a
                          ) AS ins,
                          (
                          SELECT 
                          SUM(CASE WHEN mf_active = 'F' THEN 0 ELSE 1 END) AS misc_dispose,
                          SUM(CASE WHEN mf_active = 'F' THEN 1 ELSE 0 END) AS reg_dispose,
                          SUM(CASE WHEN case_grp = 'C' THEN 1 ELSE 0 END) AS civil_dispose,
                          SUM(CASE WHEN case_grp = 'R' THEN 1 ELSE 0 END) AS criminal_dispose,
                          COUNT(diary_no) AS total
                          FROM (
                          SELECT 
                          CASE 
                              WHEN unreg_fil_dt IS NOT NULL 
                              AND unreg_fil_dt::date != '0001-01-01'::date 
                              AND unreg_fil_dt::date <= m.fil_dt::date THEN 'u'
                              ELSE 'r' 
                          END AS fil_type,
                          unreg_fil_dt, fil_dt, d.diary_no, d.fil_no, d.month, d.year, d.disp_dt, d.disp_type, d.rj_dt,
                          mf_active, case_grp
                          FROM dispose d
                          INNER JOIN main m ON m.diary_no = d.diary_no
                          WHERE (
                          SUBSTRING(m.fil_no FROM 1 FOR 2) NOT IN ('39')
                          OR m.fil_no = ''
                          OR m.fil_no IS NULL
                          )
                          AND (
                          (d.rj_dt::date IS NOT NULL AND d.rj_dt::date BETWEEN '$firstDate' AND '$lastDate')
                          OR (d.disp_dt::date BETWEEN '$firstDate' AND '$lastDate')
                          )
                          AND (
                          (unreg_fil_dt::date IS NOT NULL AND unreg_fil_dt::date <= m.fil_dt::date)
                          OR (m.fil_dt::date IS NOT NULL AND m.fil_dt::date != '0001-01-01'::date)
                          )
                          ) AS a
                          ) AS dis";
        $query = $this->db->query($sql);
        return $query->getResultArray();
        return $result;
        // echo '<pre>'; 
        // print_r($result);
        // echo '</pre>'; die;             
    }

    public function get_pendency($reportType, $categoryCode = NULL, $groupCountFrom = NULL, $groupCountTo = NULL, $caseCategory = NULL, $caseStatus = NULL, $caseType = NULL, $fromDate = NULL, $toDate = NULL, $reportType1 = NULL, $jcode = null, $matterType = null, $matterStatus = null)

    {


        $sql = "";
        switch ($reportType) {
            case 1: {
                    $sql = " SELECT  jcode, jname,count(*) as judge_wise_pendency,
                                  sum(case when h.conn_key = 0 or h.conn_key=h.diary_no
                                          then 1 else 0
                                      end) MainCaseCount,
                                  sum(case when  h.conn_key != 0 and h.conn_key!=h.diary_no
                                          then 1 else 0 end) ConnectedCaseCount,
                                          case when j.is_retired='Y' THEN 1 else 2 end as is_retired
                              FROM heardt h
                              INNER JOIN main m ON h.diary_no = m.diary_no
                              LEFT JOIN judge j ON find_in_set( jcode, judges )=1
                              WHERE m.c_status = 'P'
                              AND judges != ''
                              AND judges != '0'
                              AND judges IS NOT NULL
                              GROUP BY jcode
                              order by judge_seniority desc";

                    break;
                }


            case 6: {
                    $jCode = $_POST['jCode'];
                    $from_Date = date('Y-m-d', strtotime($_POST['from_date']));
                    $to_Date = date('Y-m-d', strtotime($_POST['to_date']));
                    if ($jCode != '0')
                        $condition = " and j.jcode=$jCode";
                    else
                        $condition = " and 1=1";
                    $sql = "SELECT 
                              listed.jcode, 
                              listed.jname,
                              listed.Misc_Main AS listed_Misc_Main,
                              listed.Misc_Conn AS listed_Misc_Conn,
                              listed.Regular_Main AS listed_Regular_Main,
                              listed.Regular_Conn AS listed_Regular_Conn,
                              listed.total_Main AS listed_total_Main,
                              listed.total_Conn AS listed_total_Conn,
                              disposed.Misc_Main AS disposed_Misc_Main,
                              disposed.Misc_Conn AS disposed_Misc_Conn,
                              disposed.Regular_Main AS disposed_Regular_Main,
                              disposed.Regular_Conn AS disposed_Regular_Conn,
                              disposed.total_Main AS disposed_total_Main,
                              disposed.total_Conn AS disposed_total_Conn
                              FROM (
                              SELECT 
                                  j.jcode, 
                                  j.jname,
                                  COUNT(DISTINCT CASE WHEN (m.mf_active = 'M' AND (NULLIF(m.conn_key, '')::int = 0 OR m.conn_key IS NULL OR NULLIF(m.conn_key, '')::int = m.diary_no)) THEN m.diary_no END) AS Misc_Main,
                                  COUNT(DISTINCT CASE WHEN (m.mf_active = 'M' AND (NULLIF(m.conn_key, '')::int != 0 AND m.conn_key IS NOT NULL AND NULLIF(m.conn_key, '')::int != m.diary_no)) THEN m.diary_no END) AS Misc_Conn,
                                  COUNT(DISTINCT CASE WHEN (m.mf_active <> 'M' AND (NULLIF(m.conn_key, '')::int = 0 OR m.conn_key IS NULL OR NULLIF(m.conn_key, '')::int = m.diary_no)) THEN m.diary_no END) AS Regular_Main,
                                  COUNT(DISTINCT CASE WHEN (m.mf_active <> 'M' AND (NULLIF(m.conn_key, '')::int != 0 AND m.conn_key IS NOT NULL AND NULLIF(m.conn_key, '')::int != m.diary_no)) THEN m.diary_no END) AS Regular_Conn,
                                  COUNT(DISTINCT CASE WHEN (NULLIF(m.conn_key, '')::int = 0 OR m.conn_key IS NULL OR NULLIF(m.conn_key, '')::int = m.diary_no) THEN m.diary_no END) AS total_Main,
                                  COUNT(DISTINCT CASE WHEN (NULLIF(m.conn_key, '')::int != 0 AND m.conn_key IS NOT NULL AND NULLIF(m.conn_key, '')::int != m.diary_no) THEN m.diary_no END) AS total_Conn
                              FROM 
                                  main m
                              INNER JOIN (
                                  SELECT DISTINCT 
                                  diary_no, 
                                  next_dt, 
                                  judges
                                  FROM (
                                  SELECT 
                                      diary_no, 
                                      next_dt, 
                                      judges, 
                                      board_type
                                  FROM heardt
                                  WHERE 
                                      next_dt BETWEEN '$from_Date' AND '$to_Date'
                                      AND clno != 0 
                                      AND brd_slno != 0 
                                      AND roster_id != 0 
                                      AND judges != '0' 
                                      AND roster_id NOT IN (29, 30) 
                                      AND board_type = 'J'
                                      $condition
                                  UNION ALL
                                  SELECT 
                                      diary_no, 
                                      next_dt, 
                                      judges, 
                                      board_type
                                  FROM last_heardt
                                  WHERE 
                                      next_dt BETWEEN '$from_Date' AND '$to_Date'
                                      AND (bench_flag IS NULL OR bench_flag = '') 
                                      AND clno != 0 
                                      AND brd_slno != 0 
                                      AND roster_id != 0 
                                      AND judges != '0' 
                                      AND roster_id NOT IN (29, 30) 
                                      AND board_type = 'J'
                                      $condition
                                  ) bb
                              ) aa
                              ON m.diary_no = aa.diary_no
                              INNER JOIN master.judge j ON POSITION(j.jcode::text IN aa.judges) > 0
                              WHERE 1=1 $condition
                              GROUP BY j.jcode, j.jname
                              ) listed
                              LEFT JOIN (
                              SELECT 
                                  j.jcode, 
                                  j.jname,
                                  COUNT(DISTINCT CASE WHEN (m.mf_active = 'M' AND (NULLIF(m.conn_key, '')::int = 0 OR m.conn_key IS NULL OR NULLIF(m.conn_key, '')::int = m.diary_no)) THEN m.diary_no END) AS Misc_Main,
                                  COUNT(DISTINCT CASE WHEN (m.mf_active = 'M' AND (NULLIF(m.conn_key, '')::int != 0 AND m.conn_key IS NOT NULL AND NULLIF(m.conn_key, '')::int != m.diary_no)) THEN m.diary_no END) AS Misc_Conn,
                                  COUNT(DISTINCT CASE WHEN (m.mf_active <> 'M' AND (NULLIF(m.conn_key, '')::int = 0 OR m.conn_key IS NULL OR NULLIF(m.conn_key, '')::int = m.diary_no)) THEN m.diary_no END) AS Regular_Main,
                                  COUNT(DISTINCT CASE WHEN (m.mf_active <> 'M' AND (NULLIF(m.conn_key, '')::int != 0 AND m.conn_key IS NOT NULL AND NULLIF(m.conn_key, '')::int != m.diary_no)) THEN m.diary_no END) AS Regular_Conn,
                                  COUNT(DISTINCT CASE WHEN (NULLIF(m.conn_key, '')::int = 0 OR m.conn_key IS NULL OR NULLIF(m.conn_key, '')::int = m.diary_no) THEN m.diary_no END) AS total_Main,
                                  COUNT(DISTINCT CASE WHEN (NULLIF(m.conn_key, '')::int != 0 AND m.conn_key IS NOT NULL AND NULLIF(m.conn_key, '')::int != m.diary_no) THEN m.diary_no END) AS total_Conn
                              FROM 
                                  main m
                              LEFT JOIN heardt h ON m.diary_no = h.diary_no
                              INNER JOIN dispose d ON m.diary_no = d.diary_no
                              INNER JOIN master.judge j ON POSITION(j.jcode::text IN d.jud_id::text) > 0
                              WHERE 
                                  d.ord_dt BETWEEN '$from_Date' AND '$to_Date'
                                  AND c_status = 'D'
                                  AND h.board_type = 'J'
                                  $condition
                              GROUP BY j.jcode, j.jname
                              ) disposed
                              ON listed.jcode = disposed.jcode
                              ORDER BY listed.jcode";

                    $sql2 = "SELECT
                          COUNT(DISTINCT m.diary_no) AS other_disp
                          FROM 
                          main m
                          LEFT JOIN heardt h 
                          ON m.diary_no = h.diary_no
                          INNER JOIN dispose d 
                          ON m.diary_no = d.diary_no
                          INNER JOIN master.judge j 
                          ON POSITION(j.jcode::text IN d.jud_id::text) > 0
                          WHERE 
                          d.ord_dt BETWEEN '$from_Date' AND '$to_Date'
                          AND c_status = 'D'
                          AND (h.board_type != 'J' OR h.diary_no IS NULL)";
                    $query2 = $this->db->query($sql2);

                    break;
                }


            default:
                break;
        }
        //echo $sql;

        $query = $this->db->query($sql);

        if ($reportType == 6) {
            $result['other_disposal'] = $query2->getResultArray();
            $result['disposal'] = $query->getResultArray();
            return $result;
        }


        // echo '<pre>'; 
        // print_r($result);
        // echo '</pre>'; die;   
        //echo ($query->num_rows());
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }
    ///kr************************************************************************************************************



    // Shubham Work 
    public function getCaseName($case_type)
    {
        $builder = $this->db->table('master.casetype');
        $builder->select('casename');
        $builder->where('casecode', $case_type);
        $query = $builder->get();
        $row = $query->getRowArray();
        return $row['casename'] ?? null;
    }

    public function getSectionName($section)
    {
        $builder = $this->db->table('master.usersection');
        $builder->select('section_name');
        $builder->where('id', $section);
        $query = $builder->get();
        $row = $query->getRowArray();
        return $row['section_name'] ?? null;
    }


    public function getDetailsList($condition_agency, $condition_case, $condition_year, $condition_sec)
    {
        $builder = $this->db->table('main m');
        $builder->select("substring(m.diary_no::text, 1, length(m.diary_no::text) - 4) as diary_no,
                          substring(m.diary_no::text, -4) as diary_year,
                  pet_name, res_name, reg_no_display, empid, dacode, name, type_name, section_name");
        $builder->join('master.users user', 'm.dacode = user.usercode', 'left');
        $builder->join('master.usertype ut', 'ut.id = user.usertype', 'left');
        $builder->join('master.usersection b', 'b.id = user.section', 'left');
        $builder->where('c_status', 'P');
        $builder->where('m.fil_dt IS NOT NULL');
        if (!empty($condition_sec)) {
            $builder->where($condition_sec);
        }
        if (!empty($condition_year)) {
            $builder->where($condition_year);
        }
        if (!empty($condition_case)) {
            $builder->where($condition_case);
        }
        if (!empty($condition_agency)) {
            $builder->where($condition_agency);
        }
        $query = $builder->get();
        if ($query->getNumRows() == 0) {
            return [];
        }
        return $query->getResultArray();
    }

    // Shubham Work END
}
