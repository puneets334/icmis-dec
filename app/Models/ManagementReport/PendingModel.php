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
                    --AND (m.diary_no = m.conn_key::bigint OR m.conn_key IS NULL OR m.conn_key = '0')
                    AND (m.diary_no = NULLIF(m.conn_key, '')::bigint OR m.conn_key IS NULL OR m.conn_key = '0')
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
                    AND (m.diary_no = CAST(NULLIF(m.conn_key, '') AS BIGINT) OR m.conn_key IS NULL OR m.conn_key = '0')
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
            tentative_da(m.diary_no::INT) AS da_name,
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
        tentative_section(m.diary_no), tentative_da(m.diary_no::INT),
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
                    tentative_da(m.diary_no::int) AS da_name,
                    CAST(SUBSTRING(m.diary_no::TEXT, -4) AS BIGINT) AS diary_no_suffix,
                    CAST(LEFT(m.diary_no::TEXT, LENGTH(m.diary_no::TEXT) - 4) AS BIGINT) AS diary_no_prefix
                FROM main m 
                    INNER JOIN heardt h ON h.diary_no = m.diary_no
                    LEFT JOIN master.listing_purpose l ON l.code = h.listorder AND l.display = 'Y'
                    LEFT JOIN master.subheading s ON s.stagecode = h.subhead and s.display = 'Y' and s.listtype = 'M'    
                WHERE 
                m.c_status = 'P' $diary_reg_un AND 
                h.board_type = '$board_type' AND h.mainhead = '$mainhead'     
                AND (m.diary_no = CAST(NULLIF(m.conn_key, '') AS BIGINT) OR m.conn_key IS NULL OR m.conn_key = '0')
                and (h.coram is null or trim(h.coram) = '' or h.coram = '0')
                and h.next_dt is not null and h.listorder != 32 and h.clno = 0
                AND h.subhead IN (824,810,803,802,807,804,808,811,812,813,814,815,816)
                GROUP BY m.diary_no, l.purpose, s.stagename
                ORDER BY 
                tentative_section(m.diary_no), tentative_da(m.diary_no::int),
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
                //->where('CAST(m.diary_no AS BIGINT) = CAST(m.conn_key AS BIGINT)')
                ->where("CAST(m.diary_no AS BIGINT) = CAST(NULLIF(m.conn_key, '') AS BIGINT)")
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
                INNER JOIN main m ON CAST(NULLIF(m.conn_key, '') AS BIGINT) = CAST(a.conn_key AS BIGINT)
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
        $sql = "SELECT diary_no,jm AS pdfname, dated AS orderdate FROM (SELECT o.diary_no diary_no, o.pdfname jm, TO_CHAR(o.orderdate, 'DD-MM-YYYY') dated, CASE WHEN o.type = 'O' THEN 'ROP' WHEN o.type = 'J' THEN 'Judgement' END AS jo FROM ordernet o WHERE o.diary_no =  $rop_chk_dno) tbl1 WHERE jo='Judgement' ORDER BY tbl1.dated DESC";
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
        $sql = "SELECT DISTINCT diary_no FROM lowerct WHERE lct_casetype = $c_type AND lct_caseno::int IN ($lct_caseno)  AND lct_caseno ~ '^\d+$' AND lct_caseyear = '$c_yr' AND lw_display = 'Y' AND ct_code = 4";

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
    public function case_status($diary_no)
    {
        $return = [];
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
                        m.diary_no =" . $diary_no;
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $return = $query->getRowArray();
        }
        return $return;
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
                                OR m.diary_no = CAST(NULLIF(m.conn_key, '') AS BIGINT)
                            )
                            ) THEN 1 ELSE 0 END
                        ) AS misc_main, 
                        SUM(
                            CASE WHEN (
                            m.mf_active = 'M' 
                            AND (
                                m.conn_key != '0' 
                                AND m.conn_key IS NOT NULL 
                                AND m.diary_no != CAST(NULLIF(m.conn_key, '') AS BIGINT)
                            )
                            ) THEN 1 ELSE 0 END
                        ) AS misc_conn, 
                        SUM(
                            CASE WHEN (
                            m.mf_active = 'F' 
                            AND (
                                m.conn_key = '0' 
                                OR m.conn_key IS NULL 
                                OR m.diary_no = CAST(NULLIF(m.conn_key, '') AS BIGINT)
                            )
                            ) THEN 1 ELSE 0 END
                        ) AS regular_main, 
                        SUM(
                            CASE WHEN (
                            m.mf_active = 'F' 
                            AND (
                                m.conn_key != '0'
                                AND m.conn_key IS NOT NULL 
                                AND m.diary_no != CAST(NULLIF(m.conn_key, '') AS BIGINT)
                            )
                            ) THEN 1 ELSE 0 END
                        ) AS regular_conn, 
                        SUM(
                            CASE WHEN (
                            m.conn_key = '0' 
                            OR m.conn_key IS NULL 
                            OR m.diary_no = CAST(NULLIF(m.conn_key, '') AS BIGINT)
                            ) THEN 1 ELSE 0 END
                        ) AS total_main, 
                        SUM(
                            CASE WHEN (
                            m.conn_key != '0'
                            AND m.conn_key IS NOT NULL 
                            AND m.diary_no != CAST(NULLIF(m.conn_key, '') AS BIGINT)
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
                                AND '$end_dt' 
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
                                OR m.diary_no = CAST(NULLIF(m.conn_key, '') AS BIGINT)
                            )
                            ) THEN 1 ELSE 0 END
                        ) AS misc_main, 
                        SUM(
                            CASE WHEN (
                            m.mf_active = 'M' 
                            AND (
                                m.conn_key != '0'
                                AND m.conn_key IS NOT NULL 
                                AND m.diary_no != CAST(NULLIF(m.conn_key, '') AS BIGINT)
                            )
                            ) THEN 1 ELSE 0 END
                        ) AS misc_conn, 
                        SUM(
                            CASE WHEN (
                            m.mf_active = 'F' 
                            AND (
                                m.conn_key = '0' 
                                OR m.conn_key IS NULL 
                                OR m.diary_no = CAST(NULLIF(m.conn_key, '') AS BIGINT)
                            )
                            ) THEN 1 ELSE 0 END
                        ) AS regular_main, 
                        SUM(
                            CASE WHEN (
                            m.mf_active = 'F' 
                            AND (
                                m.conn_key != '0'
                                AND m.conn_key IS NOT NULL 
                                AND m.diary_no != CAST(NULLIF(m.conn_key, '') AS BIGINT)
                            )
                            ) THEN 1 ELSE 0 END
                        ) AS regular_conn, 
                        SUM(
                            CASE WHEN (
                            m.conn_key = '0'
                            OR m.conn_key IS NULL 
                            OR m.diary_no = CAST(NULLIF(m.conn_key, '') AS BIGINT)
                            ) THEN 1 ELSE 0 END
                        ) AS total_main, 
                        SUM(
                            CASE WHEN (
                            m.conn_key != '0'
                            AND m.conn_key IS NOT NULL 
                            AND m.diary_no != CAST(NULLIF(m.conn_key, '') AS BIGINT)
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
            $with_conn = " AND (m.diary_no = CAST(NULLIF(m.conn_key, '') AS BIGINT) or m.conn_key is null or m.conn_key = '0')";
            $mainhead = "and m.mf_active='M'";
        }
        if ($flag == 2 or $flag == 8) {
            $with_conn = " AND (m.diary_no != CAST(NULLIF(m.conn_key, '') AS BIGINT) AND m.conn_key > '0')";
            $mainhead = "and m.mf_active='M'";
        }
        if ($flag == 3 or $flag == 9) {
            $with_conn = " AND (m.diary_no = CAST(NULLIF(m.conn_key, '') AS BIGINT) or m.conn_key is null or m.conn_key = '0')";
            $mainhead = "and m.mf_active='F'";
        }
        if ($flag == 4 or $flag == 10) {
            $with_conn = " AND (m.diary_no != CAST(NULLIF(m.conn_key, '') AS BIGINT) AND m.conn_key > '0')";
            $mainhead = "and m.mf_active='F'";
        }
        if ($flag == 5 or $flag == 11) {
            $with_conn = " AND (m.diary_no = CAST(NULLIF(m.conn_key, '') AS BIGINT) or m.conn_key is null or m.conn_key = '0')";
        }
        if ($flag == 6 or $flag == 12) {
            $with_conn = " AND (m.diary_no != CAST(NULLIF(m.conn_key, '') AS BIGINT) AND m.conn_key > '0')";
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
                              CASE WHEN m.diary_no = CAST(NULLIF(m.conn_key, '') AS BIGINT) THEN 1 ELSE 999 END ASC, m.diary_no
                            --CASE WHEN m.conn_key ::int= m.diary_no THEN 1 ELSE 999 END ASC, m.diary_no
                            ";
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
                        d.ord_dt BETWEEN '$start_dt' AND '$end_dt' 
                        AND m.c_status = 'D' 
                        AND h.board_type = 'J' 
                        AND m.casetype_id = '$ct' $with_conn $mainhead
                    GROUP BY 
                        m.diary_no, h.next_dt, d.ord_dt,c.short_description
                    ORDER BY 
                        m.conn_key, 
                        --CASE WHEN m.conn_key ::int= m.diary_no THEN 1 ELSE 999 END ASC,
                        CASE WHEN m.diary_no = CAST(NULLIF(m.conn_key, '') AS BIGINT) THEN 1 ELSE 999 END ASC,
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
                                    AND (m.diary_no::TEXT = m.conn_key::TEXT 
                                        OR m.conn_key::TEXT = '' 
                                        OR m.conn_key::TEXT IS NULL 
                                        OR m.conn_key::TEXT = '0')
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
                                    AND (m.diary_no::TEXT = m.conn_key::TEXT 
                                        OR m.conn_key::TEXT = '' 
                                        OR m.conn_key::TEXT IS NULL 
                                        OR m.conn_key::TEXT = '0')
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
                                    AND (t.diary_no::TEXT = t.conn_key::TEXT 
                                        OR t.conn_key::TEXT IS NOT NULL 
                                        OR t.conn_key::TEXT IS NULL 
                                        OR t.conn_key::TEXT = '0')
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
                                    AND (m.diary_no::TEXT = m.conn_key::TEXT 
                                        OR m.conn_key::TEXT = '' 
                                        OR m.conn_key::TEXT IS NULL 
                                        OR m.conn_key::TEXT = '0')
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
                        h.next_dt,
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
    public function pre_after_notice_get()
    {
        $sql = "select * from (select 
                notice, 
                SUM(CASE WHEN (listorder = 4) THEN 1 ELSE 0 END) fix_dt,        
                SUM(CASE WHEN (listorder = 5) THEN 1 ELSE 0 END) mentioning,        
                SUM(CASE WHEN (listorder = 7) THEN 1 ELSE 0 END) week_commencing,         
                SUM(CASE WHEN (listorder = 32) THEN 1 ELSE 0 END) freshly_filed,
                SUM(CASE WHEN (listorder = 25) THEN 1 ELSE 0 END) freshly_filed_adj,
                SUM(CASE WHEN subhead = 824 AND listorder not in (4,5,7,32,25) THEN 1 ELSE 0 END) part_heard,
                SUM(CASE WHEN inperson = 1 and bail != 1 and subhead != 824 AND listorder not in (4,5,7,32,25) THEN 1 ELSE 0 END) inperson,
                SUM(CASE WHEN bail = 1 and inperson != 1 and subhead != 824 AND listorder not in (4,5,7,32,25) THEN 1 ELSE 0 END) bail,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 8) THEN 1 ELSE 0 END) after_week,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 24) THEN 1 ELSE 0 END) imp_ia,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 21) THEN 1 ELSE 0 END) ia_other_than_imp_ia,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 48) THEN 1 ELSE 0 END) nradj_not_list,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 2) THEN 1 ELSE 0 END) adm_order,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 16) THEN 1 ELSE 0 END) ordinary,
                count(*) total

                FROM
                (select 'Pre_Notice_Ready' as notice,
                h.subhead, d.doccode1, mc.submaster_id, h.listorder, h.diary_no,
                CASE
                    WHEN h.subhead::INTEGER = 804 OR mc.submaster_id::INTEGER = 173 OR doccode1 IN (40, 41, 48, 49, 71, 72, 118, 131, 211, 309) THEN 1
                    ELSE 0
                END AS bail,
                    CASE
                    WHEN a.advocate_id = 584 THEN 1
                    ELSE 0
                END AS inperson        
                FROM heardt h       
                INNER JOIN main m ON m.diary_no = h.diary_no 
                LEFT JOIN docdetails d ON d.diary_no = m.diary_no AND d.display = 'Y' AND d.iastat = 'P' AND d.doccode = 8 
                    AND d.doccode1 IN  (7,66,29,56,57,28,103,133,226,3,309,73,99,40,48,72,71,27,124,2,16,41,49,71,72,102,118,131,211,309) 
                LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no AND mc.display = 'Y'  
                AND mc.submaster_id != 911 AND mc.submaster_id != 912 AND mc.submaster_id != 914 and mc.submaster_id != 239 AND mc.submaster_id != 240 
                AND mc.submaster_id != 241 AND mc.submaster_id != 242 AND mc.submaster_id != 243 AND mc.submaster_id != 331 AND mc.submaster_id != 9999
                left join advocate a on a.diary_no = m.diary_no and a.advocate_id = 584 and a.display = 'Y'          
                left join case_remarks_multiple c on c.diary_no = m.diary_no and c.r_head in (1,3,62,181,182,183,184)
                LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                WHERE 
                c.diary_no is null and (m.fil_no_fh = '' or m.fil_no_fh is null) and h.subhead not in (813,814) 
                and 
                CASE
                    WHEN h.listorder IN (4, 5) THEN TRUE  
                    ELSE
                        CASE
                            WHEN 0 = 0 THEN (h.is_nmd = 'N' OR h.is_nmd = '' OR h.is_nmd IS NULL)
                            ELSE (h.is_nmd = 'N' OR h.is_nmd = '' OR h.is_nmd IS NULL)
                        END
                END 
                and rd.fil_no is null 
                AND mc.diary_no::INTEGER IS NOT NULL and 
                (m.diary_no::TEXT = m.conn_key::TEXT OR m.conn_key::TEXT IS NULL OR m.conn_key::TEXT = '0') AND 
                    m.c_status = 'P' AND h.board_type = 'J' AND h.mainhead = 'M' and h.next_dt != '0001-01-01'  
                    and main_supp_flag = 0 and h.clno = 0 and h.listorder != 49
                    AND m.active_casetype_id != 9 AND m.active_casetype_id != 10
                AND m.active_casetype_id != 25 AND m.active_casetype_id != 26
                AND h.next_dt != '0001-01-01'  AND h.listorder > 0
                AND h.subhead != 801 AND h.subhead != 817 AND h.subhead != 818 AND h.subhead != 819 AND h.subhead != 820
                AND h.subhead != 848 AND h.subhead != 849 AND h.subhead != 850 AND h.subhead != 854 and h.subhead != 0
                    group by m.diary_no,h.subhead, d.doccode1, mc.submaster_id, h.listorder,h.diary_no,a.advocate_id) t
                    group by notice) a
                        
                        union
                    select * from (select 
                notice, 
                SUM(CASE WHEN (listorder = 4) THEN 1 ELSE 0 END) fix_dt,        
                SUM(CASE WHEN (listorder = 5) THEN 1 ELSE 0 END) mentioning,        
                SUM(CASE WHEN (listorder = 7) THEN 1 ELSE 0 END) week_commencing,         
                SUM(CASE WHEN (listorder = 32) THEN 1 ELSE 0 END) freshly_filed,
                SUM(CASE WHEN (listorder = 25) THEN 1 ELSE 0 END) freshly_filed_adj,
                SUM(CASE WHEN subhead = 824 AND listorder not in (4,5,7,32,25) THEN 1 ELSE 0 END) part_heard,
                SUM(CASE WHEN inperson = 1 and bail != 1 and subhead != 824 AND listorder not in (4,5,7,32,25) THEN 1 ELSE 0 END) inperson,
                SUM(CASE WHEN bail = 1 and inperson != 1 and subhead != 824 AND listorder not in (4,5,7,32,25) THEN 1 ELSE 0 END) bail,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 8) THEN 1 ELSE 0 END) after_week,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 24) THEN 1 ELSE 0 END) imp_ia,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 21) THEN 1 ELSE 0 END) ia_other_than_imp_ia,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 48) THEN 1 ELSE 0 END) nradj_not_list,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 2) THEN 1 ELSE 0 END) adm_order,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 16) THEN 1 ELSE 0 END) ordinary,
                count(*) total

                FROM
                (select 'Pre_Notice_Listed_in_Future_Dates' as notice,
                h.subhead, d.doccode1, mc.submaster_id, h.listorder, h.diary_no,
                CASE
                    WHEN h.subhead = 804 OR mc.submaster_id = 173 OR doccode1 IN (40, 41, 48, 49, 71, 72, 118, 131, 211, 309) THEN 1
                    ELSE 0
                END AS bail,
                    CASE
                    WHEN a.advocate_id = 584 THEN 1
                    ELSE 0
                END AS inperson       
                FROM heardt h       
                INNER JOIN main m ON m.diary_no = h.diary_no 
                LEFT JOIN docdetails d ON d.diary_no = m.diary_no AND d.display = 'Y' AND d.iastat = 'P' AND d.doccode = 8 
                    AND d.doccode1 IN  (7,66,29,56,57,28,103,133,226,3,309,73,99,40,48,72,71,27,124,2,16,41,49,71,72,102,118,131,211,309) 
                LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no AND mc.display = 'Y'  
                AND mc.submaster_id != 911 AND mc.submaster_id != 912 AND mc.submaster_id != 914 and mc.submaster_id != 239 AND mc.submaster_id != 240 
                AND mc.submaster_id != 241 AND mc.submaster_id != 242 AND mc.submaster_id != 243 AND mc.submaster_id != 331 AND mc.submaster_id != 9999
                left join advocate a on a.diary_no = m.diary_no and a.advocate_id = 584 and a.display = 'Y'          
                left join case_remarks_multiple c on c.diary_no = m.diary_no and c.r_head in (1,3,62,181,182,183,184)
                LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                WHERE 
                c.diary_no is null and (m.fil_no_fh = '' or m.fil_no_fh is null) and h.subhead not in (813,814) and
                rd.fil_no is null AND mc.diary_no IS NOT NULL and (m.diary_no::INTEGER = m.conn_key::INTEGER OR m.conn_key::INTEGER IS NULL OR m.conn_key::INTEGER = '0') AND 
                    m.c_status = 'P' AND h.board_type = 'J' AND h.mainhead = 'M' and h.next_dt != '0001-01-01'  
                    and main_supp_flag != 0 and h.listorder != 49
                    AND m.active_casetype_id != 9 AND m.active_casetype_id != 10
                AND m.active_casetype_id != 25 AND m.active_casetype_id != 26
                and date(h.next_dt) >= CURRENT_DATE and h.clno > 0 and h.main_supp_flag in (1,2)
                    group by m.diary_no, h.subhead, d.doccode1, mc.submaster_id, h.listorder, h.diary_no,a.advocate_id) t
                    group by notice) a11
                        
                        union
                    select * from (select 
                notice, 
                SUM(CASE WHEN (listorder = 4) THEN 1 ELSE 0 END) fix_dt,        
                SUM(CASE WHEN (listorder = 5) THEN 1 ELSE 0 END) mentioning,        
                SUM(CASE WHEN (listorder = 7) THEN 1 ELSE 0 END) week_commencing,         
                SUM(CASE WHEN (listorder = 32) THEN 1 ELSE 0 END) freshly_filed,
                SUM(CASE WHEN (listorder = 25) THEN 1 ELSE 0 END) freshly_filed_adj,
                SUM(CASE WHEN subhead = 824 AND listorder not in (4,5,7,32,25) THEN 1 ELSE 0 END) part_heard,
                SUM(CASE WHEN inperson = 1 and bail != 1 and subhead != 824 AND listorder not in (4,5,7,32,25) THEN 1 ELSE 0 END) inperson,
                SUM(CASE WHEN bail = 1 and inperson != 1 and subhead != 824 AND listorder not in (4,5,7,32,25) THEN 1 ELSE 0 END) bail,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 8) THEN 1 ELSE 0 END) after_week,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 24) THEN 1 ELSE 0 END) imp_ia,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 21) THEN 1 ELSE 0 END) ia_other_than_imp_ia,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 48) THEN 1 ELSE 0 END) nradj_not_list,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 2) THEN 1 ELSE 0 END) adm_order,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 16) THEN 1 ELSE 0 END) ordinary,
                count(*) total

                FROM
                (select 'Pre_Notice_Updation_Awaited' as notice,
                h.subhead, d.doccode1, mc.submaster_id, h.listorder, h.diary_no,
                CASE
                    WHEN h.subhead = 804 OR mc.submaster_id = 173 OR doccode1 IN (40, 41, 48, 49, 71, 72, 118, 131, 211, 309) THEN 1
                    ELSE 0
                END AS bail,
                    CASE
                    WHEN a.advocate_id = 584 THEN 1
                    ELSE 0
                END AS inperson        
                FROM heardt h       
                INNER JOIN main m ON m.diary_no = h.diary_no 
                LEFT JOIN docdetails d ON d.diary_no = m.diary_no AND d.display = 'Y' AND d.iastat = 'P' AND d.doccode = 8 
                    AND d.doccode1 IN  (7,66,29,56,57,28,103,133,226,3,309,73,99,40,48,72,71,27,124,2,16,41,49,71,72,102,118,131,211,309) 
                LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no AND mc.display = 'Y'  
                AND mc.submaster_id != 911 AND mc.submaster_id != 912 AND mc.submaster_id != 914 and mc.submaster_id != 239 AND mc.submaster_id != 240 
                AND mc.submaster_id != 241 AND mc.submaster_id != 242 AND mc.submaster_id != 243 AND mc.submaster_id != 331 AND mc.submaster_id != 9999
                left join advocate a on a.diary_no = m.diary_no and a.advocate_id = 584 and a.display = 'Y'          
                left join case_remarks_multiple c on c.diary_no = m.diary_no and c.r_head in (1,3,62,181,182,183,184)
                LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                WHERE 
                c.diary_no is null and (m.fil_no_fh = '' or m.fil_no_fh is null) and h.subhead not in (813,814) and
                rd.fil_no is null AND mc.diary_no IS NOT NULL and (m.diary_no::TEXT = m.conn_key::TEXT OR m.conn_key::TEXT IS NULL OR m.conn_key::TEXT = '0') AND 
                    m.c_status = 'P' AND h.board_type = 'J' AND h.mainhead = 'M' and h.next_dt != '0001-01-01'  
                    and main_supp_flag != 0 and h.listorder != 49
                    AND m.active_casetype_id != 9 AND m.active_casetype_id != 10
                AND m.active_casetype_id != 25 AND m.active_casetype_id != 26
                and date(h.next_dt) < CURRENT_DATE and h.clno > 0 and h.main_supp_flag in (1,2)
                    group by m.diary_no,h.subhead, d.doccode1, mc.submaster_id, h.listorder,h.diary_no,a.advocate_id) t
                    group by notice) a12
                    union
                    select * from (select 
                notice, 
                SUM(CASE WHEN (listorder = 4) THEN 1 ELSE 0 END) fix_dt,        
                SUM(CASE WHEN (listorder = 5) THEN 1 ELSE 0 END) mentioning,        
                SUM(CASE WHEN (listorder = 7) THEN 1 ELSE 0 END) week_commencing,         
                SUM(CASE WHEN (listorder = 32) THEN 1 ELSE 0 END) freshly_filed,
                SUM(CASE WHEN (listorder = 25) THEN 1 ELSE 0 END) freshly_filed_adj,
                SUM(CASE WHEN subhead = 824 AND listorder not in (4,5,7,32,25) THEN 1 ELSE 0 END) part_heard,
                SUM(CASE WHEN inperson = 1 and bail != 1 and subhead != 824 AND listorder not in (4,5,7,32,25) THEN 1 ELSE 0 END) inperson,
                SUM(CASE WHEN bail = 1 and inperson != 1 and subhead != 824 AND listorder not in (4,5,7,32,25) THEN 1 ELSE 0 END) bail,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 8) THEN 1 ELSE 0 END) after_week,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 24) THEN 1 ELSE 0 END) imp_ia,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 21) THEN 1 ELSE 0 END) ia_other_than_imp_ia,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 48) THEN 1 ELSE 0 END) nradj_not_list,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 2) THEN 1 ELSE 0 END) adm_order,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 16) THEN 1 ELSE 0 END) ordinary,
                count(*) total

                FROM
                (select 'Pre_Notice_Not_Ready' as notice,
                h.subhead, d.doccode1, mc.submaster_id, h.listorder, h.diary_no,
                CASE
                    WHEN h.subhead = 804 OR mc.submaster_id = 173 OR doccode1 IN (40, 41, 48, 49, 71, 72, 118, 131, 211, 309) THEN 1
                    ELSE 0
                END AS bail,
                    CASE
                    WHEN a.advocate_id = 584 THEN 1
                    ELSE 0
                END AS inperson      
                FROM heardt h       
                INNER JOIN main m ON m.diary_no = h.diary_no 
                LEFT JOIN docdetails d ON d.diary_no = m.diary_no AND d.display = 'Y' AND d.iastat = 'P' AND d.doccode = 8 
                    AND d.doccode1 IN  (7,66,29,56,57,28,103,133,226,3,309,73,99,40,48,72,71,27,124,2,16,41,49,71,72,102,118,131,211,309) 
                LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no AND mc.display = 'Y'  
                AND mc.submaster_id != 911 AND mc.submaster_id != 912 AND mc.submaster_id != 914 and mc.submaster_id != 239 AND mc.submaster_id != 240 
                AND mc.submaster_id != 241 AND mc.submaster_id != 242 AND mc.submaster_id != 243 AND mc.submaster_id != 331 AND mc.submaster_id != 9999
                left join advocate a on a.diary_no = m.diary_no and a.advocate_id = 584 and a.display = 'Y'          
                left join case_remarks_multiple c on c.diary_no = m.diary_no and c.r_head in (1,3,62,181,182,183,184)
                LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                WHERE 
                c.diary_no is null and (m.fil_no_fh = '' or m.fil_no_fh is null) and h.subhead not in (813,814) and
                rd.fil_no is null AND mc.diary_no IS NOT NULL and (m.diary_no::TEXT = m.conn_key::TEXT OR m.conn_key::TEXT IS NULL OR m.conn_key::TEXT = '0') AND 
                    m.c_status = 'P' AND h.board_type = 'J' AND h.mainhead = 'M' and h.next_dt != '0001-01-01'  
                    and main_supp_flag = 3 and h.listorder != 49
                    AND m.active_casetype_id != 9 AND m.active_casetype_id != 10
                AND m.active_casetype_id != 25 AND m.active_casetype_id != 26
                    group by m.diary_no,h.subhead, d.doccode1, mc.submaster_id, h.listorder,h.diary_no,a.advocate_id) t
                    group by notice) a1
                    union
                    select * from (select 
                notice, 
                SUM(CASE WHEN (listorder = 4) THEN 1 ELSE 0 END) fix_dt,        
                SUM(CASE WHEN (listorder = 5) THEN 1 ELSE 0 END) mentioning,        
                SUM(CASE WHEN (listorder = 7) THEN 1 ELSE 0 END) week_commencing,         
                SUM(CASE WHEN (listorder = 32) THEN 1 ELSE 0 END) freshly_filed,
                SUM(CASE WHEN (listorder = 25) THEN 1 ELSE 0 END) freshly_filed_adj,
                SUM(CASE WHEN subhead = 824 AND listorder not in (4,5,7,32,25) THEN 1 ELSE 0 END) part_heard,
                SUM(CASE WHEN inperson = 1 and bail != 1 and subhead != 824 AND listorder not in (4,5,7,32,25) THEN 1 ELSE 0 END) inperson,
                SUM(CASE WHEN bail = 1 and inperson != 1 and subhead != 824 AND listorder not in (4,5,7,32,25) THEN 1 ELSE 0 END) bail,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 8) THEN 1 ELSE 0 END) after_week,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 24) THEN 1 ELSE 0 END) imp_ia,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 21) THEN 1 ELSE 0 END) ia_other_than_imp_ia,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 48) THEN 1 ELSE 0 END) nradj_not_list,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 2) THEN 1 ELSE 0 END) adm_order,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 16) THEN 1 ELSE 0 END) ordinary,
                count(*) total

                FROM
                (select 'After_Notice_Ready' as notice,
                h.subhead, d.doccode1, mc.submaster_id, h.listorder, h.diary_no,
                CASE
                    WHEN h.subhead = 804 OR mc.submaster_id = 173 OR doccode1 IN (40, 41, 48, 49, 71, 72, 118, 131, 211, 309) THEN 1
                    ELSE 0
                END AS bail,
                    CASE
                    WHEN a.advocate_id = 584 THEN 1
                    ELSE 0
                END AS inperson   
                FROM heardt h       
                INNER JOIN main m ON m.diary_no = h.diary_no 
                LEFT JOIN docdetails d ON d.diary_no = m.diary_no AND d.display = 'Y' AND d.iastat = 'P' AND d.doccode = 8 
                    AND d.doccode1 IN  (7,66,29,56,57,28,103,133,226,3,309,73,99,40,48,72,71,27,124,2,16,41,49,71,72,102,118,131,211,309) 
                LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no AND mc.display = 'Y'  
                AND mc.submaster_id != 911 AND mc.submaster_id != 912 AND mc.submaster_id != 914 and mc.submaster_id != 239 AND mc.submaster_id != 240 
                AND mc.submaster_id != 241 AND mc.submaster_id != 242 AND mc.submaster_id != 243 AND mc.submaster_id != 331 AND mc.submaster_id != 9999
                left join advocate a on a.diary_no = m.diary_no and a.advocate_id = 584 and a.display = 'Y'          
                left join case_remarks_multiple c on c.diary_no = m.diary_no and c.r_head in (1,3,62,181,182,183,184)
                LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                WHERE 
                NOT (c.diary_no IS NULL AND (m.fil_no_fh = '' OR m.fil_no_fh IS NULL) AND h.subhead NOT IN (813, 814)) and 
                rd.fil_no is null AND mc.diary_no IS NOT NULL and (m.diary_no::TEXT = m.conn_key::TEXT OR m.conn_key::TEXT IS NULL OR m.conn_key::TEXT = '0') AND 
                    m.c_status = 'P' AND h.board_type = 'J' AND h.mainhead = 'M' and h.next_dt != '0001-01-01'  
                    and main_supp_flag = 0 and h.clno = 0 and h.listorder != 49
                    AND m.active_casetype_id != 9 AND m.active_casetype_id != 10
                AND m.active_casetype_id != 25 AND m.active_casetype_id != 26
                    group by m.diary_no,h.subhead, d.doccode1, mc.submaster_id, h.listorder,h.diary_no,a.advocate_id) t
                    group by notice) b
                    union
                    select * from (select 
                notice, 
                SUM(CASE WHEN (listorder = 4) THEN 1 ELSE 0 END) fix_dt,        
                SUM(CASE WHEN (listorder = 5) THEN 1 ELSE 0 END) mentioning,        
                SUM(CASE WHEN (listorder = 7) THEN 1 ELSE 0 END) week_commencing,         
                SUM(CASE WHEN (listorder = 32) THEN 1 ELSE 0 END) freshly_filed,
                SUM(CASE WHEN (listorder = 25) THEN 1 ELSE 0 END) freshly_filed_adj,
                SUM(CASE WHEN subhead = 824 AND listorder not in (4,5,7,32,25) THEN 1 ELSE 0 END) part_heard,
                SUM(CASE WHEN inperson = 1 and bail != 1 and subhead != 824 AND listorder not in (4,5,7,32,25) THEN 1 ELSE 0 END) inperson,
                SUM(CASE WHEN bail = 1 and inperson != 1 and subhead != 824 AND listorder not in (4,5,7,32,25) THEN 1 ELSE 0 END) bail,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 8) THEN 1 ELSE 0 END) after_week,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 24) THEN 1 ELSE 0 END) imp_ia,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 21) THEN 1 ELSE 0 END) ia_other_than_imp_ia,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 48) THEN 1 ELSE 0 END) nradj_not_list,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 2) THEN 1 ELSE 0 END) adm_order,
                SUM(CASE WHEN (inperson != 1 and bail != 1 and subhead != 824 and listorder = 16) THEN 1 ELSE 0 END) ordinary,
                count(*) total

                FROM
                (select 'After_Notice_Not_Ready' as notice,
                h.subhead, d.doccode1, mc.submaster_id, h.listorder, h.diary_no,
                CASE
                    WHEN h.subhead = 804 OR mc.submaster_id = 173 OR doccode1 IN (40, 41, 48, 49, 71, 72, 118, 131, 211, 309) THEN 1
                    ELSE 0
                END AS bail,
                    CASE
                    WHEN a.advocate_id = 584 THEN 1
                    ELSE 0
                END AS inperson      
                FROM heardt h       
                INNER JOIN main m ON m.diary_no = h.diary_no 
                LEFT JOIN docdetails d ON d.diary_no = m.diary_no AND d.display = 'Y' AND d.iastat = 'P' AND d.doccode = 8 
                    AND d.doccode1 IN  (7,66,29,56,57,28,103,133,226,3,309,73,99,40,48,72,71,27,124,2,16,41,49,71,72,102,118,131,211,309) 
                LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no AND mc.display = 'Y'  
                AND mc.submaster_id != 911 AND mc.submaster_id != 912 AND mc.submaster_id != 914 and mc.submaster_id != 239 AND mc.submaster_id != 240 
                AND mc.submaster_id != 241 AND mc.submaster_id != 242 AND mc.submaster_id != 243 AND mc.submaster_id != 331 AND mc.submaster_id != 9999
                left join advocate a on a.diary_no = m.diary_no and a.advocate_id = 584 and a.display = 'Y'          
                left join case_remarks_multiple c on c.diary_no = m.diary_no and c.r_head in (1,3,62,181,182,183,184)
                LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                WHERE 
                NOT (c.diary_no IS NULL AND (m.fil_no_fh = '' OR m.fil_no_fh IS NULL) AND h.subhead NOT IN (813, 814))
                AND 
                rd.fil_no is null AND mc.diary_no IS NOT NULL and (m.diary_no::TEXT = m.conn_key::TEXT OR m.conn_key::TEXT IS NULL OR m.conn_key::TEXT = '0') AND 
                    m.c_status = 'P' AND h.board_type = 'J' AND h.mainhead = 'M' and h.next_dt != '0001-01-01'  
                    and main_supp_flag != 0 and h.listorder != 49
                    AND m.active_casetype_id != 9 AND m.active_casetype_id != 10
                AND m.active_casetype_id != 25 AND m.active_casetype_id != 26
                    group by m.diary_no,h.subhead, d.doccode1, mc.submaster_id, h.listorder,h.diary_no,a.advocate_id) t
                    group by notice) b1";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
    public function regular_in_misc_get()
    {
        $sql = "SELECT
                            ROW_NUMBER() OVER () AS SNO,
                            c.*
                        FROM (
                            SELECT
                                CONCAT(m.reg_no_display, ' @ ', m.diary_no) AS REGNO_DNO,
                                CONCAT(m.pet_name, ' Vs. ', m.res_name) AS TITLE,
                                TO_CHAR(h.next_dt, 'DD-MM-YYYY') AS Tentative_Date,
                                tentative_section(m.diary_no) AS SECTION,
                                tentative_da(m.diary_no::INTEGER) AS DA
                            FROM
                                heardt h
                            INNER JOIN
                                main m ON m.diary_no = h.diary_no
                            LEFT JOIN
                                mul_category mc ON mc.diary_no = h.diary_no
                                AND mc.display = 'Y'
                            LEFT JOIN
                                rgo_default rd ON rd.fil_no = h.diary_no
                                AND rd.remove_def = 'N'
                            WHERE
                                rd.fil_no IS NULL
                                AND mc.diary_no IS NOT NULL
                                AND (
                                    (m.conn_key != '' AND m.conn_key IS NOT NULL AND m.diary_no = m.conn_key::INTEGER)
                                    OR m.conn_key = ''
                                    OR m.conn_key IS NULL
                                    OR m.conn_key = '0'
                                ) 
                                AND m.c_status = 'P'
                                AND h.mainhead = 'M'
                                AND (m.fil_no_fh IS NOT NULL AND m.fil_no_fh != '')
                            GROUP BY
                                m.diary_no, h.next_dt 
                            ORDER BY
                                tentative_section(m.diary_no),
                                tentative_da(m.diary_no::INTEGER),
                                m.diary_no_rec_date
                        ) c";

        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
    public function not_ready_get_detail($connt, $dt_flag, $ltype, $list_dt_f)
    {
        if ($ltype == 'court_r') {
            $logic_flag = " AND h.board_type = 'J' and h.main_supp_flag = 0 ";
        }
        if ($ltype == 'court_nr') {
            $logic_flag = " AND h.board_type = 'J' and h.main_supp_flag != 0 ";
        }
        if ($ltype == 'court') {
            $logic_flag = " AND h.board_type = 'J' ";
        }
        if ($ltype == 'chamber_r') {
            $logic_flag = " AND h.board_type = 'C' and h.main_supp_flag = 0 ";
        }
        if ($ltype == 'chamber_nr') {
            $logic_flag = " AND h.board_type = 'C' and h.main_supp_flag != 0 ";
        }
        if ($ltype == 'chamber') {
            $logic_flag = " AND h.board_type = 'C' ";
        }
        if ($ltype == 'reg_r') {
            $logic_flag = " AND h.board_type = 'R' and h.main_supp_flag = 0 ";
        }
        if ($ltype == 'reg_nr') {
            $logic_flag = " AND h.board_type = 'R' and h.main_supp_flag != 0 ";
        }
        if ($ltype == 'reg') {
            $logic_flag = " AND h.board_type = 'R' and h.main_supp_flag = 0 ";
        }
        if ($ltype == 'ready') {
            $logic_flag = " AND h.main_supp_flag = 0 ";
        }
        if ($ltype == 'not_ready') {
            $logic_flag = " AND h.main_supp_flag != 0 ";
        }
        if ($ltype == 'Total') {
            $logic_flag = " AND h.main_supp_flag = 0 ";
        }
        $sql = "SELECT 
                    m.active_fil_no,
                    m.active_reg_year,
                    m.reg_no_display,
                    m.active_casetype_id,
                    m.fil_no,
                    m.fil_dt,
                    EXTRACT(YEAR FROM m.fil_dt) AS fil_year,
                    m.lastorder,
                    m.diary_no_rec_date,
                    h.*,
                    l.purpose,
                    STRING_AGG(mc.submaster_id::TEXT, ',') AS cat1
                FROM
                    heardt h
                INNER JOIN
                    main m ON m.diary_no = h.diary_no
                LEFT JOIN
                    master.listing_purpose l ON l.code = h.listorder
                LEFT JOIN
                    mul_category mc ON mc.diary_no = m.diary_no AND mc.display = 'Y'
                LEFT JOIN
                    not_before nb ON nb.diary_no::INTEGER = m.diary_no::INTEGER
                LEFT JOIN
                    docdetails d ON d.diary_no = m.diary_no
                    AND d.display = 'Y'
                    AND d.iastat = 'P'
                    AND d.doccode = 8
                    AND d.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 226, 3, 309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 71, 72, 102, 118, 131, 211, 309)
                WHERE 
                    $connt 
                    m.c_status = 'P'
                    AND h.mainhead = 'M' 
                    $dt_flag $list_dt_f $logic_flag
                GROUP BY
                    m.diary_no,
                    h.diary_no,
                    m.active_fil_no,
                    m.active_reg_year,
                    m.reg_no_display,
                    m.active_casetype_id,
                    m.fil_no,
                    m.fil_dt,
                    m.lastorder,
                    m.diary_no_rec_date,
                    l.purpose,
                    h.next_dt,
                    h.board_type,
                    h.main_supp_flag,
                    h.listorder,
                    h.mainhead
                ORDER BY
                     CAST(SUBSTRING(m.diary_no::TEXT, LENGTH(m.diary_no::TEXT) - 3) AS INTEGER) ASC,
                     CAST(LEFT(m.diary_no::TEXT, LENGTH(m.diary_no::TEXT) - 4) AS INTEGER) ASC
                ";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
    public function ready_not_back_date($connt)
    {
        if ($connt == 1) {
            $connt2 = "";
        } else {
            $connt2 = "(m.diary_no::TEXT = m.conn_key::TEXT OR m.conn_key::TEXT = '' OR m.conn_key::TEXT IS NULL OR m.conn_key::TEXT = '0') AND ";
        }
        $sql = "SELECT
                    a.*
                FROM
                    (
                        SELECT
                            h.next_dt,
                            SUM(CASE WHEN h.board_type = 'J' AND h.main_supp_flag = 0 THEN 1 ELSE 0 END) AS court_r,
                            SUM(CASE WHEN h.board_type = 'J' AND h.main_supp_flag != 0 THEN 1 ELSE 0 END) AS court_nr,
                            SUM(CASE WHEN h.board_type = 'J' THEN 1 ELSE 0 END) AS court,
                            SUM(CASE WHEN h.board_type = 'C' AND h.main_supp_flag = 0 THEN 1 ELSE 0 END) AS chamber_r,
                            SUM(CASE WHEN h.board_type = 'C' AND h.main_supp_flag != 0 THEN 1 ELSE 0 END) AS chamber_nr,
                            SUM(CASE WHEN h.board_type = 'C' THEN 1 ELSE 0 END) AS chamber,
                            SUM(CASE WHEN h.board_type = 'R' AND h.main_supp_flag = 0 THEN 1 ELSE 0 END) AS reg_r,
                            SUM(CASE WHEN h.board_type = 'R' AND h.main_supp_flag != 0 THEN 1 ELSE 0 END) AS reg_nr,
                            SUM(CASE WHEN h.board_type = 'R' THEN 1 ELSE 0 END) AS reg,
                            SUM(CASE WHEN h.main_supp_flag = 0 THEN 1 ELSE 0 END) AS ready,
                            SUM(CASE WHEN h.main_supp_flag != 0 THEN 1 ELSE 0 END) AS not_ready,
                            COUNT(m.diary_no) AS Total
                        FROM
                            heardt h
                        INNER JOIN
                            main m ON m.diary_no = h.diary_no
                        WHERE $connt2 
                            m.c_status = 'P'
                            AND h.mainhead = 'M'
                            AND h.next_dt < CURRENT_DATE
                        GROUP BY
                            h.next_dt
                    ) a
                ORDER BY
                    CASE
                        WHEN a.next_dt IS NULL THEN 2
                        ELSE 1
                    END ASC,
                    a.next_dt ASC";

        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
    public function ready_not_future_date()
    {
        $sql = "SELECT
                    a.*
                FROM
                    (
                        SELECT
                            wd.is_holiday,
                            h.next_dt,
                            SUM(CASE WHEN h.board_type = 'J' AND h.main_supp_flag = 0 THEN 1 ELSE 0 END) AS court_r,
                            SUM(CASE WHEN h.board_type = 'J' AND h.main_supp_flag != 0 THEN 1 ELSE 0 END) AS court_nr,
                            SUM(CASE WHEN h.board_type = 'J' THEN 1 ELSE 0 END) AS court,
                            SUM(CASE WHEN h.board_type = 'C' AND h.main_supp_flag = 0 THEN 1 ELSE 0 END) AS chamber_r,
                            SUM(CASE WHEN h.board_type = 'C' AND h.main_supp_flag != 0 THEN 1 ELSE 0 END) AS chamber_nr,
                            SUM(CASE WHEN h.board_type = 'C' THEN 1 ELSE 0 END) AS chamber,
                            SUM(CASE WHEN h.board_type = 'R' AND h.main_supp_flag = 0 THEN 1 ELSE 0 END) AS reg_r,
                            SUM(CASE WHEN h.board_type = 'R' AND h.main_supp_flag != 0 THEN 1 ELSE 0 END) AS reg_nr,
                            SUM(CASE WHEN h.board_type = 'R' THEN 1 ELSE 0 END) AS reg,
                            SUM(CASE WHEN h.main_supp_flag = 0 THEN 1 ELSE 0 END) AS ready,
                            SUM(CASE WHEN h.main_supp_flag != 0 THEN 1 ELSE 0 END) AS not_ready,
                            COUNT(m.diary_no) AS Total
                        FROM
                            heardt h
                        INNER JOIN
                            main m ON m.diary_no = h.diary_no
                        LEFT JOIN
                            master.sc_working_days wd ON wd.working_date = h.next_dt
                        WHERE
                            m.c_status = 'P'
                            AND h.mainhead = 'M'
                            AND h.next_dt >= CURRENT_DATE
                        GROUP BY
                            wd.is_holiday, h.next_dt
                    ) a
                ORDER BY
                    CASE
                        WHEN a.next_dt IS NULL THEN 2
                        ELSE 1
                    END ASC,
                    a.next_dt ASC ";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
    public function section_pendency()
    {
        return;
    }
    ///kr************************************************************************************************************

    // public function get_institution_report($from_dt, $to_dt, $rpt_type)
    // {

    //     switch ($rpt_type) {
    //         case 'registration':
    //             $sql = "
    //                   SELECT substr(fil_no, 1, 2) fil_no, count(*) cnt, date(fil_dt) fil_dt, short_description, casename,
    //                       sum(case when DATE(diary_no_rec_date) BETWEEN '$from_dt' AND '$to_dt' then 1 else 0 end) filed,
    //                       sum(case when DATE(diary_no_rec_date) not BETWEEN '$from_dt' AND '$to_dt'  then 1 else 0 end) not_filed
    //                   FROM main m
    //                   INNER JOIN master.casetype c ON c.casecode::text = substr(fil_no, 1, 2)
    //                   WHERE DATE(fil_dt) BETWEEN '$from_dt' AND '$to_dt' 
    //                   GROUP BY substr(fil_no, 1, 2),m.fil_dt,c.short_description,c.casename";
    //                   pr($sql);
    //             break;

    //         case 'institution':
    //             $sql = "SELECT 
    //                         substr(fil_no, 1, 2) AS case_code, 
    //                         COUNT(*) AS cnt, 
    //                         DATE(fil_dt) AS fil_dt, 
    //                         short_description, 
    //                         casename,
    //                         SUM(CASE WHEN DATE(diary_no_rec_date) BETWEEN '$from_dt' AND '$to_dt' THEN 1 ELSE 0 END) AS filed,
    //                         SUM(CASE WHEN DATE(diary_no_rec_date) NOT BETWEEN '$from_dt' AND '$to_dt' THEN 1 ELSE 0 END) AS not_filed
    //                     FROM main m
    //                     INNER JOIN master.casetype c 
    //                     ON c.casecode = CAST(NULLIF(substr(fil_no, 1, 2), '') AS INTEGER) 
    //                     WHERE DATE(fil_dt) BETWEEN '$from_dt' AND '$to_dt' 
    //                     AND substr(fil_no, 1, 2) != '39'
    //                     GROUP BY substr(fil_no, 1, 2), DATE(fil_dt), short_description, casename";
    //             break;

    //         case 'filing':
    //             $sql = "
    //                   SELECT 
    //                       substr(fil_no, 1, 2) AS case_code, 
    //                       COUNT(*) AS cnt, 
    //                       short_description, 
    //                       casename, 
    //                       DATE(diary_no_rec_date) AS fil_dt
    //                   FROM main m
    //                   INNER JOIN casetype c ON c.casecode = casetype_id
    //                   WHERE DATE(diary_no_rec_date) BETWEEN '$from_dt' AND '$to_dt'
    //                   GROUP BY casetype_id";
    //             break;

    //         case 'defect':
    //             $sql = "
    //                   SELECT 
    //                       DATE(diary_no_rec_date) AS fil_dt, 
    //                       COUNT(*) AS cnt,
    //                       SUM(CASE WHEN fil_no = '' OR fil_no IS NULL THEN 1 ELSE 0 END) AS defect,
    //                       SUM(CASE WHEN (fil_no = '' OR fil_no IS NULL) AND not_reg_if_pen = 1 AND iastat = 'P' THEN 1 ELSE 0 END) AS defect_ia,
    //                       SUM(CASE WHEN NOT (fil_no = '' OR fil_no IS NULL) THEN 1 ELSE 0 END) AS not_defect
    //                   FROM main m
    //                   LEFT JOIN (
    //                       SELECT d.diary_no, iastat, not_reg_if_pen
    //                       FROM docdetails d
    //                       INNER JOIN docmaster d2 ON d.doccode = d2.doccode AND d.doccode1 = d2.doccode1
    //                       WHERE not_reg_if_pen = 1 AND iastat = 'P'
    //                       GROUP BY diary_no
    //                   ) t ON t.diary_no = m.diary_no
    //                   WHERE DATE(diary_no_rec_date) BETWEEN '$from_dt' AND '$to_dt'
    //                   GROUP BY DATE(diary_no_rec_date)";
    //             break;

    //         case 'refiling':
    //             $sql = "
    //                   SELECT 
    //                       disp_dt AS fil_dt, 
    //                       short_description, 
    //                       COUNT(a.diary_no) AS cnt 
    //                   FROM (
    //                       SELECT DATE(disp_dt) AS disp_dt, diary_no 
    //                       FROM fil_trap 
    //                       WHERE remarks = 'FDR -> SCR' AND DATE(disp_dt) BETWEEN '$from_dt' AND '$to_dt'
    //                       UNION ALL
    //                       SELECT DATE(disp_dt) AS disp_dt, diary_no 
    //                       FROM fil_trap_his 
    //                       WHERE remarks = 'FDR -> SCR' AND DATE(disp_dt) BETWEEN '$from_dt' AND '$to_dt'
    //                   ) a
    //                   JOIN main m ON a.diary_no = m.diary_no
    //                   JOIN casetype c ON COALESCE(NULLIF(m.active_casetype_id, ''), casetype_id) = c.casecode
    //                   GROUP BY disp_dt, short_description";

    //             break;

    //         default:

    //             return [];
    //     }



    //     $query = $this->db->query($sql);
    //     return $query->getResultArray();

    //     // echo '<pre>';
    //     // print_r($result);
    //     // echo '</pre>';
    //     // die;
    // }

    // public function get_institution_report($from_dt, $to_dt, $rpt_type)
    // {
    //     switch ($rpt_type) {
    //         case 'registration':
    //         case 'institution':
    //             $condition = ($rpt_type === 'institution') ? "AND substring(fil_no from 1 for 2) != '39'" : "";
    //             $sql = "
    //                 SELECT substring(fil_no from 1 for 2) AS case_code,
    //                        COUNT(*) AS cnt,
    //                        DATE(fil_dt) AS fil_dt,
    //                        short_description,
    //                        casename,
    //                        SUM(CASE WHEN DATE(diary_no_rec_date) BETWEEN ? AND ? THEN 1 ELSE 0 END) AS filed,
    //                        SUM(CASE WHEN DATE(diary_no_rec_date) NOT BETWEEN ? AND ? THEN 1 ELSE 0 END) AS not_filed
    //                 FROM main m
    //                 INNER JOIN master.casetype c 
    //                 ON c.casecode = COALESCE(NULLIF(substring(fil_no from 1 for 2), ''), '0')::INTEGER
    //                 WHERE DATE(fil_dt) BETWEEN ? AND ? 
    //                 $condition
    //                 GROUP BY substring(fil_no from 1 for 2), DATE(fil_dt), short_description, casename
    //             ";

    //             return $this->db->query($sql, [$from_dt, $to_dt, $from_dt, $to_dt, $from_dt, $to_dt])->getResultArray();
    //             break;

    //         default:
    //             return [];
    //     }
    // }



    public function get_institution_report($from_date, $to_date, $rpt_type)
    {
        $report_name = '';
        $from_date = explode("-", $from_date);
        $from_dt = $from_date[2] . "-" . $from_date[1] . "-" . $from_date[0];

        $to_date = explode("-", $to_date);
        $to_dt = $to_date[2] . "-" . $to_date[1] . "-" . $to_date[0];

        if ($rpt_type == 'registration' || $rpt_type == 'institution') {
            $condition = "1=1";
            if ($rpt_type == 'registration') {
                $report_name = 'Fresh Registration';
            }
            if ($rpt_type == 'institution') {
                $report_name = 'Institution';
                $condition = " NULLIF((SUBSTRING(fil_no FROM 1 FOR 2)), '')::INTEGER <> 39 ";
            }
            $sql = "SELECT 
                SUBSTRING(fil_no FROM 1 FOR 2) AS case_prefix, 
                COUNT(*) AS cnt,  
                DATE(fil_dt) AS fil_dt, 
                c.short_description, 
                c.casename,
                SUM(CASE 
                    WHEN DATE(diary_no_rec_date) BETWEEN '$from_dt' AND '$to_dt' 
                    THEN 1 ELSE 0 
                END) AS filed,
                SUM(CASE 
                    WHEN DATE(diary_no_rec_date) NOT BETWEEN '$from_dt' AND '$to_dt'  
                    THEN 1 ELSE 0 
                END) AS not_filed
            FROM main m

            INNER JOIN master.casetype c ON c.casecode = NULLIF((SUBSTRING(fil_no FROM 1 FOR 2)), '')::INTEGER  

            WHERE DATE(fil_dt) BETWEEN '$from_dt' AND '$to_dt' 
            AND $condition
            GROUP BY SUBSTRING(fil_no FROM 1 FOR 2), DATE(fil_dt), c.short_description, c.casename";
        } elseif ($rpt_type == 'filing') {
            $report_name = 'Filing';
            $sql = "SELECT 
                substr(MAX(fil_no), 1, 2) AS fil_no_prefix, 
                MAX(short_description) AS short_description, 
                MAX(casename) AS casename, 
                MAX(diary_no_rec_date::date) AS fil_dt, 
                count(m.ack_id) AS cnt
            FROM 
                main m
            INNER JOIN 
                master.casetype c ON c.casecode = m.casetype_id
            WHERE 
                diary_no_rec_date::date BETWEEN '$from_dt' AND '$to_dt'
            GROUP BY 
                m.casetype_id";
        } elseif ($rpt_type == 'defect') {
            $report_name = 'Defect Matters';
            $sql = "SELECT date( diary_no_rec_date ) fil_dt, count(*) cnt,
                    sum(CASE WHEN fil_no = '' or fil_no IS NULL THEN 1 ELSE 0 END ) defect ,
                    sum(CASE WHEN (fil_no = '' or fil_no IS NULL) and not_reg_if_pen =1 AND iastat = 'P'  THEN 1 ELSE 0 END ) defect_ia, 
                    sum(CASE WHEN (fil_no != '' or fil_no IS not NULL) THEN 1 ELSE 0 END ) not_defect ,
                    m.diary_no, diary_no_rec_date, fil_no, not_reg_if_pen, iastat
                    FROM main m
                    LEFT JOIN (
                            SELECT d.diary_no,iastat ,not_reg_if_pen
                            FROM docdetails d
                            INNER JOIN master.docmaster d2 ON d.doccode = d2.doccode
                            AND d.doccode1 = d2.doccode1
                            WHERE not_reg_if_pen =1
                            AND iastat = 'P' group by diary_no,iastat ,not_reg_if_pen
                            ) t on t.diary_no=m.diary_no
                    WHERE date( diary_no_rec_date )
                    BETWEEN '$from_dt' AND '$to_dt' GROUP BY date( diary_no_rec_date ),
                    m.diary_no, diary_no_rec_date, fil_no, not_reg_if_pen, iastat";
        } elseif ($rpt_type == 'refiling') {
            $report_name = 'Re-filing';

            $sql = "SELECT 
                    a.disp_dt AS fil_dt, 
                    c.short_description, 
                    COUNT(a.diary_no) AS cnt
                FROM (
                    SELECT DATE(disp_dt) AS disp_dt, diary_no 
                    FROM fil_trap  
                    WHERE remarks = 'FDR -> SCR' 
                    AND DATE(disp_dt) BETWEEN '$from_dt' AND '$to_dt'
                    
                    UNION ALL
                    
                    SELECT DATE(disp_dt) AS disp_dt, diary_no 
                    FROM fil_trap_his 
                    WHERE remarks = 'FDR -> SCR' 
                    AND DATE(disp_dt) BETWEEN '$from_dt' AND '$to_dt'
                ) a 
                JOIN main m ON a.diary_no = m.diary_no 
                JOIN master.casetype c ON 
                    COALESCE(NULLIF(m.active_casetype_id::TEXT, '0')::INTEGER, m.casetype_id) = c.casecode
                GROUP BY a.disp_dt, c.short_description";
        }



        $query = $this->db->query($sql);
        return $query->getResultArray();
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
        // pr("select * from 
        // (select sum(case when mf_active='F' then 0 else 1 end) as misc_institution,
        // sum(case when mf_active='F' then 1 else 0 end) as reg_institution,
        // sum(case when case_grp='C' then 1 else 0 end) as civil_institution,
        // sum(case when case_grp='R' then 1 else 0 end) as criminal_institution,
        // count(diary_no) inst from 
        // (SELECT m.diary_no, m.fil_dt, unreg_fil_dt,mf_active,case_grp
        // FROM main m
        // WHERE 
        // IF (
        // date(unreg_fil_dt) != '0000-00-00'
        // AND (
        // date(unreg_fil_dt) <= date( m.fil_dt )
        // OR date( m.fil_dt ) = '0000-00-00'
        // ), date(unreg_fil_dt) between '".$firstDate."' and '".$lastDate."', (
        // date( m.fil_dt ) between '".$firstDate."' and '".$lastDate."'
        // AND date(fil_dt) != '0000-00-00'
        // )
        // )
        // AND (
        // substr(m.fil_no, 1, 2) NOT IN ( 39 )
        // OR m.fil_no = ''
        // OR m.fil_no IS NULL
        // )
        // GROUP BY m.diary_no
        // ) a) ins,
        // (select sum(case when mf_active='F' then 0 else 1 end) as misc_dispose,
        // sum(case when mf_active='F' then 1 else 0 end) as reg_dispose,
        // sum(case when case_grp='C' then 1 else 0 end) as civil_dispose,
        // sum(case when case_grp='R' then 1 else 0 end) as criminal_dispose ,
        // count(diary_no) total from (SELECT IF (
        // unreg_fil_dt != '0000-00-00'
        // AND unreg_fil_dt <= date( m.fil_dt ) , 'u', 'r'
        // ), unreg_fil_dt, fil_dt, d.diary_no , d.fil_no, d.month, d.year, d.disp_dt, d.disp_type, d.rj_dt,
        // mf_active,case_grp
        // FROM dispose d
        // INNER JOIN main m ON m.diary_no = d.diary_no
        // WHERE 1 =1 AND (
        // substr(m.fil_no, 1, 2) NOT
        // IN ( 39 )
        // OR m.fil_no = ''
        // OR m.fil_no IS NULL
        // )
        // AND
        // IF (date(d.rj_dt) != '0000-00-00', date(d.rj_dt)
        // BETWEEN '".$firstDate."' and '".$lastDate."', date(d.disp_dt)
        // between '".$firstDate."' and '".$lastDate."'
        // )
        // AND
        // IF (date(unreg_fil_dt) != '0000-00-00'
        // AND date(unreg_fil_dt) <= date( m.fil_dt ) , date(unreg_fil_dt) != '0000-00-00', (
        // date( m.fil_dt ) != '0000-00-00'
        // AND date(fil_dt) != '0000-00-00'
        // ))) a) dis");
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

    private  function stagename($scode)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('master.subheading');
        $builder->where('display', 'Y');
        $builder->whereIn('stagecode', explode(',', $scode));
        $builder->orderBy('stagecode', 'ASC');
        $query = $builder->get();
        $result = $query->getRowArray();
        return $result ? $result['stagename'] : '';
    }


    private function get_case_type()
    {
        $builder = $this->db->table('master.casetype');
        $builder->select('casecode,skey');
        $builder->where('display', 'Y');
        $builder->orderBy('nature', 'ASC');
        $builder->orderBy('skey', 'ASC');
        $query = $builder->get();
        $results = $query->getResultArray();

        $str = '';

        $j = 1;
        $aff = count($results);

        foreach ($results as $r) {
            if ($j == $aff) {
                $str .= " SUM(CASE WHEN (CASE WHEN active_casetype_id = 0 THEN casetype_id ELSE active_casetype_id END) = '" . $r['casecode'] . "' THEN 1 ELSE 0 END) AS " . $r['skey'] . " ";
            } else {
                $str .= " SUM(CASE WHEN (CASE WHEN active_casetype_id = 0 THEN casetype_id ELSE active_casetype_id END) = '" . $r['casecode'] . "' THEN 1 ELSE 0 END) AS " . $r['skey'] . " ,";
            }
            $j++;
        }
        return $str;
    }


    public function get_nature_wise_ason()
    {
        $request = service('request');
        $str = $this->get_case_type();

        $bench = '';
        $benchInput = $request->getGet('bench');

        if ($benchInput === 'all') {
            $bench = '';
        } elseif ($benchInput === '2') {
            $bench = " AND h.judges LIKE '%,%'";
        } elseif ($benchInput === '3') {
            $bench = " AND h.judges LIKE '%,%,%'";
        } elseif ($benchInput === '5') {
            $bench = " AND h.judges LIKE '%,%,%,%,%'";
        } elseif ($benchInput === '7') {
            $bench = " AND h.judges LIKE '%,%,%,%,%,%,%'";
        } elseif ($benchInput === '9') {
            $bench = " AND h.judges LIKE '%,%,%,%,%,%,%,%,%'";
        } else {
            $bench = " AND h.judges NOT LIKE '%%,%'";
        }


        if ($request->getGet('ason_type') == 'dt') {
            $til_date = explode("-", $request->getGet('til_date'));
            $til_dt = $til_date[2] . "-" . $til_date[1] . "-" . $til_date[0];

            $ason_str = " CASE WHEN d.rj_dt IS NOT NULL THEN d.rj_dt >= DATE '$til_dt'
                        WHEN d.disp_dt IS NOT NULL THEN d.disp_dt >= DATE '$til_dt'
                        ELSE TO_DATE(CONCAT( COALESCE(d.year::text, '0000'), '-', LPAD(COALESCE(d.month::text, '01'), 2, '0'), '-01'
                            ), 'YYYY-MM-DD' ) >= DATE '$til_dt' END ";

            $ason_str_res = "IF(disp_rj_dt != '0000-00-00', disp_rj_dt >= '" . $til_dt . "',
                    IF(r.disp_dt IS NOT NULL, r.disp_dt >= '" . $til_dt . "', 
                    CONCAT(r.disp_year::text, '-', LPAD(r.disp_month, 2, 0), '-01') >= '" . $til_dt . "'))";

            $exclude_cond = "CASE WHEN r.disp_dt IS NOT NULL AND r.conn_next_dt IS NOT NULL 
                            THEN DATE '$til_dt' NOT BETWEEN r.disp_dt AND r.conn_next_dt 
                            ELSE r.disp_dt IS NULL OR r.conn_next_dt IS NULL 
                        END  OR r.fil_no IS NULL ";

            $exclude_cond_other = "CASE WHEN r.disp_dt IS NOT NULL AND r.conn_next_dt IS NOT NULL
                        THEN DATE '$til_dt' NOT BETWEEN r.disp_dt AND r.conn_next_dt
                        ELSE r.disp_dt IS NULL OR r.conn_next_dt IS NULL END ";
        } else if ($request->getGet('ason_type') == 'month') {
            $til_dt = $request->getGet('lst_year') . "-" . str_pad($request->getGet('lst_month'), 2, "0", STR_PAD_LEFT) . "-01";

            $ason_str = " IF(d.rj_dt IS NOT NULL, d.rj_dt >= '" . $til_dt . "', 
                            IF(d.month = 0, d.disp_dt >= '" . $til_dt . "', CONCAT(d.year, '-',LPAD(d.month::text, 2, '0'), '-01') >= '" . $til_dt . "'))";

            $ason_str_res = " IF(r.disp_rj_dt != '0000-00-00', r.disp_rj_dt >= '" . $til_dt . "', 
                            IF(r.disp_month = 0, r.disp_dt >= '" . $til_dt . "', CONCAT(r.disp_year, '-', LPAD(r.disp_month, 2, 0), '-01') >= '" . $til_dt . "'))";

            $exclude_cond = " CASE 
            WHEN r.disp_month != '0' AND r.disp_month IS NOT NULL AND r.month != '0' AND r.month IS NOT NULL 
            THEN '" . $til_dt . "' NOT BETWEEN CONCAT(r.disp_year, '-', LPAD(r.disp_month, 2, '0'), '-01') AND CONCAT(r.year, '-', LPAD(r.month, 2, '0'), '-01') 
            WHEN r.month != '0' AND r.month IS NOT NULL 
            THEN CONCAT(r.year, '-', LPAD(r.month, 2, '0'), '-01') != '" . $til_dt . "'
            ELSE r.disp_month = '0' OR r.`disp_month` IS NULL OR r.month = '0' OR r.month IS NULL END OR r.fil_no IS NULL";

            $exclude_cond_other = "
            CASE 
                WHEN r.disp_month != '0' AND r.disp_month IS NOT NULL 
                     AND r.month != '0' AND r.month IS NOT NULL 
                THEN DATE ? NOT BETWEEN 
                    TO_DATE(CONCAT(r.disp_year, '-', LPAD(r.disp_month, 2, '0'), '-01'), 'YYYY-MM-DD') 
                    AND TO_DATE(CONCAT(r.year, '-', LPAD(r.month, 2, '0'), '-01'), 'YYYY-MM-DD')
                
                WHEN r.month != '0' AND r.month IS NOT NULL 
                THEN TO_DATE(CONCAT(r.year, '-', LPAD(r.month, 2, '0'), '-01'), 'YYYY-MM-DD') != '" . $til_dt . "'
                
                ELSE 
                    r.disp_month = '0' OR r.disp_month IS NULL 
                    OR r.month = '0' OR r.month IS NULL END ";
        } else if ($request->getGet('ason_type') == 'ent_dt') {
            $til_date = explode("-", $request->getGet('til_date'));
            $til_dt = $til_date[2] . "-" . $til_date[1] . "-" . $til_date[0];

            $ason_str = " d.ent_dt >= '" . $til_dt . "'";

            $ason_str_res = " r.disp_ent_dt >= '" . $til_dt . "'";

            $exclude_cond = " CASE WHEN  r.`entry_date` IS NOT NULL 
                        AND DATE(r.disp_ent_dt) != '0000-00-00' AND r.disp_ent_dt IS NOT NULL
            THEN '" . $til_dt . "' NOT BETWEEN DATE(r.disp_ent_dt) AND `entry_date` 
            ELSE DATE(r.`disp_ent_dt`) = '0000-00-00' OR r.`disp_ent_dt` IS NULL OR DATE(r.entry_date) = '0000-00-00' OR r.entry_date IS NULL END 
            OR r.fil_no IS NULL";

            $exclude_cond_other = " CASE WHEN r.entry_date IS NOT NULL 
             AND DATE(r.disp_ent_dt) != '0000-00-00' AND r.disp_ent_dt IS NOT NULL
                        THEN DATE '$til_dt' NOT BETWEEN DATE(r.disp_ent_dt) AND r.entry_date
                        ELSE 
                            DATE(r.disp_ent_dt) = '0000-00-00' 
                            OR r.disp_ent_dt IS NULL 
                            OR DATE(r.entry_date) = '0000-00-00' 
                            OR r.entry_date IS NULL 
                    END ";
        }

        if ($request->getGet('rpt_purpose') == 'sw') {
            $subhead_name = "subhead_n";
            $mainhead_name = "mainhead_n";
        } else {
            $subhead_name = "subhead";
            $mainhead_name = "mainhead";
        }

        if ($request->getGet('subhead') == 'all,' || $request->getGet('subhead') == '') {
            $subhead = '';
            $subhead_if_last_heardt = " ";
            $subhead_condition = " ";
            $head_subhead = " ";
        } else {
            $subhead = " AND l." . $subhead_name . " IN (" . substr($request->getGet('subhead'), 0, -1) . ")";
            $subhead_if_heardt = " AND h." . $subhead_name . " IN (" . substr($request->getGet('subhead'), 0, -1) . ")";
            $subhead_if_last_heardt = " AND f2." . $subhead_name . " IN (" . substr($request->getGet('subhead'), 0, -1) . ")";

            $subhead_if_heardt_con = " h." . $subhead_name . " IN (" . substr($request->getGet('subhead'), 0, -1) . ")";
            $subhead_if_last_heardt_con = " f2." . $subhead_name . " IN (" . substr($request->getGet('subhead'), 0, -1) . ")";

            if ($request->getGet('til_date') != date('d-m-Y')) {
                $subhead_condition = " AND IF(DATE(h.ent_dt) < '" . $til_dt . "' AND DATE(h.ent_dt) > med, " . $subhead_if_heardt_con . ", " . $subhead_if_last_heardt_con . ")";
                $head_subhead = $this->stagename(substr($request->getGet('subhead'), 0, -1));
            } else {
                $subhead_condition = $subhead_if_heardt_con;
                $head_subhead = $this->stagename(substr($request->getGet('subhead'), 0, -1));
            }
        }
        $mf_f2_table = "";
        if ($request->getGet('concept') == 'new') {

            if ($request->getGet('mf') == 'M') {
                $mf_f2_table = " f2." . $mainhead_name . " = 'M' AND (admitted = '' OR admitted IS NULL)";
                $mf_h_table = " h." . $mainhead_name . " = 'M' AND (admitted = '' OR admitted IS NULL)";
            }
            if ($request->getGet('mf') == 'F') {
                $mf_f2_table = " (f2." . $mainhead_name . " = 'F' OR (admitted != '' AND admitted IS NOT NULL)) ";
                $mf_h_table = "( h." . $mainhead_name . " = 'F' OR (admitted != '' AND admitted IS NOT NULL))";
            }
            if ($request->getGet('mf') == 'N') {
                $mf_f2_table = " (f2." . $mainhead_name . " NOT IN ('M', 'F')) ";
                $mf_h_table = "( h." . $mainhead_name . " NOT IN ('M', 'F'))";
            }
        } elseif ($request->getGet('concept') == 'old') {
            if ($request->getGet('mf') == 'M') {
                $mf_f2_table = " f2." . $mainhead_name . " = '" . $request->getGet('mf') . "' ";
                $mf_h_table = " h." . $mainhead_name . " = '" . $request->getGet('mf') . "' ";
            }
            if ($request->getGet('mf') == 'F') {
                $mf_f2_table = " f2." . $mainhead_name . " = '" . $request->getGet('mf') . "' ";
                $mf_h_table = " h." . $mainhead_name . " = '" . $request->getGet('mf') . "' ";
            }
            if ($request->getGet('mf') == 'N') {
                $mf_f2_table = " (f2." . $mainhead_name . " NOT IN ('M', 'F')) ";
                $mf_h_table = "( h." . $mainhead_name . " NOT IN ('M', 'F'))";
            }
        }



        if (trim($request->getGet('subject')) != 'all,' || trim($request->getGet('act')) != 'all,' || trim($request->getGet('act_msc')) != '') {
            $mul_cat_join = " LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no
                              LEFT JOIN master.submaster s ON mc.submaster_id = s.id";
        } else {
            $mul_cat_join = "";
        }
        if (trim($request->getGet('subcat2')) == 'all,') {


            if (trim($request->getGet('subcat')) == 'all,') {
                if (trim($request->getGet('cat')) == 'all,') {
                    if (trim($request->getGet('subject')) == 'all,') {
                        $all_category = " ";
                    } else {
                        $all_category = "s.subcode1 IN (" . substr($request->getGet('subject'), 0, -1) . ")";
                    }
                } else {
                    $head1 = explode(',', $request->getGet('cat'));
                    $str_all_cat = "";
                    for ($m = 0; $m < $request->getGet('cat_length'); $m++) {
                        $head = explode('|', $head1[$m]);
                        if ($m == 0) {
                            $str_all_cat = "(s.subcode1 = '" . $head[0] . "' AND s.subcode2 = '" . $head[1] . "')";
                        } else {
                            $str_all_cat = "((s.subcode1 = '" . $head[0] . "' AND s.subcode2 = '" . $head[1] . "') OR " . $str_all_cat . ")";
                        }
                    }


                    $all_category = $str_all_cat;
                }
            } else {
                $head1 = explode(',', $request->getGet('subcat'));
                $str_all_cat = "";
                for ($m = 0; $m < $request->getGet('subcat_length'); $m++) {
                    $head = explode('|', $head1[$m]);
                    if ($m == 0) {
                        $str_all_cat = "(s.subcode1 = '" . $head[0] . "' AND s.subcode2 = '" . $head[1] . "' AND s.subcode3 = '" . $head[2] . "')";
                    } else {
                        $str_all_cat = "((s.subcode1 = '" . $head[0] . "' AND s.subcode2 = '" . $head[1] . "' AND s.subcode3 = '" . $head[2] . "') OR " . $str_all_cat . ")";
                    }
                }
                $all_category = $str_all_cat;
            }
        } else {
            $head1 = explode(',', $request->getGet('subcat2'));
            $str_all_cat = "";
            for ($m = 0; $m < $request->getGet('subcat2_length'); $m++) {
                $head = explode('|', $head1[$m]);
                if ($m == 0) {
                    $str_all_cat = "(s.subcode1 = '" . $head[0] . "' AND s.subcode2 = '" . $head[1] . "' AND s.subcode3 = '" . $head[2] . "' AND s.subcode4 = '" . $head[3] . "')";
                } else {
                    $str_all_cat = "((s.subcode1 = '" . $head[0] . "' AND s.subcode2 = '" . $head[1] . "' AND s.subcode3 = '" . $head[2] . "' AND s.subcode4 = '" . $head[3] . "') OR " . $str_all_cat . ")";
                }
            }
            $all_category = $str_all_cat;
        }

        if (trim($request->getGet('act')) == 'all,') {
            $all_act = " ";
        } else {
            if (trim($request->getGet('subject')) == 'all,') {
                $all_act = " a.act IN (" . substr($request->getGet('act'), 0, -1) . ")";
            } else {
                $all_act = " OR a.act IN (" . substr($request->getGet('act'), 0, -1) . ")";
            }
        }

        if (trim($request->getGet('act')) == 'all,' && trim($request->getGet('subject')) == 'all,') {
            $cat_and_act = " ";
        } else {
            $cat_and_act = "( " . $all_category . " " . $all_act . " )";
        }
        if ($request->getGet('from_year') == '' || $request->getGet('to_year') == '') {
            if ($request->getGet('from_year') == '' && $request->getGet('to_year') != '') {
                $year_main = " AND SUBSTR(m.diary_no::text, -4) <= '" . $request->getGet('to_year') . "' ";
                $year_lastheardt = " AND SUBSTR(m.diary_no::text, -4) <= '" . $request->getGet('to_year') . "' ";
            } elseif ($request->getGet('from_year') != '' && $request->getGet('to_year') == '') {
                $year_main = " AND SUBSTR(m.diary_no::text, -4) >= '" . $request->getGet('from_year') . "' ";
                $year_lastheardt = " AND SUBSTR(m.diary_no::text, -4) >= '" . $request->getGet('from_year') . "' ";
            } else {
                $year_main = " ";
                $year_lastheardt = " ";
            }
        } else {
            $year_main = "SUBSTR(m.diary_no::text, -4) BETWEEN '" . $request->getGet('from_year') . "' AND '" . $request->getGet('to_year') . "' ";
            $year_lastheardt = "SUBSTR(m.diary_no::text, -4) BETWEEN '" . $request->getGet('from_year') . "' AND '" . $request->getGet('to_year') . "' ";
        }

        $Brep = "";
        $Brep1 = "";
        $act_join = '';
        $registration = '';
        $main_connected = '';
        $pc_act = $women = $children = $land = $cr_compound = $commercial_code = $party_name = $pet_res = $act_msc = '';
        $from_fil_dt = $request->getGet('from_fil_dt') ?
            " DATE(m.diary_no_rec_date) > '" . date('Y-m-d', strtotime($request->getGet('from_fil_dt'))) . "' " : " ";

        $upto_fil_dt = $request->getGet('upto_fil_dt') ?
            " DATE(m.diary_no_rec_date) < '" . date('Y-m-d ', strtotime($request->getGet('upto_fil_dt'))) . "' " : " ";

        $case_status_id = " ";
        if ($request->getGet('case_status_id') == 'all,') {
            $case_status_id = " AND case_status_id IN (1, 2, 3, 6, 7, 9) ";
        } elseif ($request->getGet('case_status_id') == '103,' || $request->getGet('case_status_id') == 103) {
            $registration = " ";
        } elseif ($request->getGet('case_status_id') == 101 || $request->getGet('case_status_id') == '101,') {
            $registration = " (active_fil_no = '' OR active_fil_no IS NULL) ";
        } elseif ($request->getGet('case_status_id') == 102 || $request->getGet('case_status_id') == '102,') {
            $registration = " NOT (active_fil_no = '' OR active_fil_no IS NULL) ";
        } elseif ($request->getGet('case_status_id') == 104 || $request->getGet('case_status_id') == '104,') {

            $Brep = " INNER JOIN
            (SELECT CASE WHEN os.diary_no IS NULL THEN m.diary_no ELSE 0 END AS dd FROM main m
             INNER JOIN docdetails b ON m.diary_no = b.diary_no
             LEFT OUTER JOIN
            (SELECT DISTINCT diary_no FROM obj_save WHERE rm_dt IS NULL AND display = 'Y')
            os ON m.diary_no = os.diary_no
             WHERE c_status = 'P' AND (active_fil_no IS NULL OR active_fil_no = '')
            AND (
            (doccode = '8' AND doccode1 = '28') OR 
            (doccode = '8' AND doccode1 = '95') OR 
            (doccode = '8' AND doccode1 = '214') OR 
            (doccode = '8' AND doccode1 = '215')
            )
            AND b.iastat = 'P') aa ON m.diary_no = aa.dd ";
        } elseif ($request->getGet('case_status_id') == 105 || $request->getGet('case_status_id') == '105,') {

            $Brep = " INNER JOIN
            (SELECT CASE WHEN os.diary_no IS NULL THEN m.diary_no ELSE 0 END AS dd FROM main m
             INNER JOIN docdetails b ON m.diary_no = b.diary_no
             LEFT OUTER JOIN
            (SELECT DISTINCT diary_no FROM obj_save WHERE rm_dt IS NULL AND display = 'Y')
            os ON m.diary_no=os.diary_no
             WHERE  c_status = 'P' AND (active_fil_no IS NULL OR active_fil_no='')
            AND(
            (doccode = '8' AND doccode1 = '16') OR 
            (doccode = '8' AND doccode1 = '79') OR 
            (doccode = '8' AND doccode1 = '99') OR 
            (doccode = '8' AND doccode1 = '300')
            )
            AND b.iastat='P') aa ON m.diary_no=aa.dd ";
        } elseif ($request->getGet('case_status_id') == 106 || $request->getGet('case_status_id') == '106,') {

            $Brep = " LEFT OUTER JOIN (SELECT DISTINCT diary_no FROM obj_save WHERE rm_dt IS NULL AND display = 'Y')
                                os ON m.diary_no=os.diary_no
                                ";
            $Brep1 = " and os.diary_no IS NOT NULL and c_status = 'P' AND (active_fil_no IS NULL OR  active_fil_no='') AND h.board_type='J'";
        } elseif ($request->getGet('case_status_id') == 107 || $request->getGet('case_status_id') == '107,') {
            $Brep = " INNER JOIN docdetails b ON m.diary_no=b.diary_no
            INNER JOIN
            (SELECT DISTINCT diary_no FROM obj_save WHERE rm_dt IS NULL AND display = 'Y' AND DATEDIFF(NOW(),save_dt)>60) os
            ON m.diary_no=os.diary_no ";
            $Brep1 = " and m.c_status = 'P' AND (m.active_fil_no IS NULL OR  m.active_fil_no='')
            AND doccode = '8' AND doccode1 = '226' AND b.iastat='P' ";
        } elseif ($request->getGet('case_status_id') == 108 || $request->getGet('case_status_id') == '108,') {
            $Brep = " INNER JOIN docdetails b ON m.diary_no=b.diary_no
            INNER JOIN
            (SELECT DISTINCT diary_no FROM obj_save WHERE rm_dt IS NULL AND display = 'Y' AND DATEDIFF(NOW(),save_dt)<=60) os
            ON m.diary_no=os.diary_no ";
            $Brep1 = " and  m.c_status = 'P' AND (m.active_fil_no IS NULL OR  m.active_fil_no='')
            AND doccode = '8' AND doccode1 = '226' AND b.iastat='P' ";
        } elseif ($request->getGet('case_status_id') == 109 || $request->getGet('case_status_id') == '109,') {
            $Brep = " LEFT JOIN (SELECT DISTINCT CASE WHEN os.diary_no IS NULL THEN m.diary_no ELSE 0 END AS dd FROM main m
             INNER JOIN docdetails b ON m.diary_no = b.diary_no
             LEFT OUTER JOIN
            (SELECT DISTINCT diary_no FROM obj_save WHERE rm_dt IS NULL AND display = 'Y')
            os ON m.diary_no=os.diary_no
             WHERE  c_status = 'P' AND (active_fil_no IS NULL OR active_fil_no='')
            AND (((
            (doccode = '8' AND doccode1 = '28') OR 
            (doccode = '8' AND doccode1 = '95') OR 
            (doccode = '8' AND doccode1 = '214') OR 
            (doccode = '8' AND doccode1 = '215') OR 
            (doccode = '8' AND doccode1 = '16') OR 
            (doccode = '8' AND doccode1 = '79') OR 
            (doccode = '8' AND doccode1 = '99') OR 
            (doccode = '8' AND doccode1 = '300') OR
            (doccode = '8' AND doccode1 = '226') OR 
            (doccode = '8' AND doccode1 = '288') OR 
            (doccode = '8' AND doccode1 = '322')
            )
            AND b.iastat='P' ))) aa ON m.diary_no=aa.dd
            LEFT OUTER JOIN
                                (SELECT DISTINCT diary_no FROM obj_save WHERE
                                (rm_dt IS NULL OR rm_dt='0000-00-00 00:00:00') AND display='Y')
                                os1 ON m.diary_no=os1.diary_no ";
            $Brep1 = " and m.c_status = 'P' AND IF((m.active_fil_no IS NULL OR m.active_fil_no=''),(aa.dd !=0 OR (os1.diary_no IS NOT NULL AND h.board_type='J')),3=3) ";
        } else {
            $case_status_id = " and case_status_id in (" . substr($request->getGet('case_status_id'), 0, -1) . ")";
        }


        if ($request->getGet('mf') != 'ALL') {
            if ($request->getGet('til_date') != date('d-m-Y')) {
                // echo '<br>';
                $t = "CREATE TEMPORARY TABLE vw2 AS 
                        SELECT DISTINCT ON (diary_no) diary_no, ent_dt AS med, subhead_n, mainhead_n
                        FROM last_heardt WHERE DATE(ent_dt) < '" . $til_dt . "' " . $year_lastheardt . "
                        ORDER BY diary_no, ent_dt DESC";

                $this->db->query($t);


                $t2 = "CREATE INDEX id_index ON vw2 (diary_no)";
                $this->db->query($t2);

                $t3 = "CREATE TEMPORARY TABLE vw3 AS
                        SELECT l.diary_no, l." . $subhead_name . ", l.judges, med, next_dt, l." . $mainhead_name . "
                        FROM vw2 
                        INNER JOIN last_heardt l ON vw2.diary_no = l.diary_no
                        AND l.ent_dt = med
                        AND l." . $mainhead_name . " = '" . $request->getGet('mf') . "' " . $subhead;
                $this->db->query($t3);

                $t4 = "CREATE INDEX id_index2 ON vw3 (diary_no)";
                $this->db->query($t4);
            }
        }

        if ($request->getGet('mf') != 'ALL') {

            if ($request->getGet('til_date') != date('d-m-Y')) {

                $builder = $this->db->table('main m');
                $builder->join('heardt h', 'm.diary_no = h.diary_no', 'left');
                $builder->join('dispose d', 'm.diary_no = d.diary_no', 'left');
                $builder->join('restored r', 'm.diary_no = r.diary_no', 'left');
                $builder->join('vw3 f2', 'm.diary_no = f2.diary_no', 'left');
                $builder->join('act_main a', 'a.diary_no = m.diary_no', 'left');
                $builder->where('1=1');
                if (!empty($mul_cat_join)) {
                    $builder->join('mul_category mc', 'mc.diary_no = h.diary_no', 'left');
                    $builder->join('master.submaster s', 'mc.submaster_id = s.id', 'left');
                }
                $builder->where("IF(med > h.ent_dt AND f2.$mainhead_name IS NOT NULL,$mf_f2_table $subhead_if_last_heardt,$mf_h_table $subhead_if_last_heardt)", null, false);
                if (!empty($exclude_cond) && $exclude_cond != ' ') $builder->where("($exclude_cond)", null, false);
                $builder->where('DATE(m.diary_no_rec_date) <', $til_dt);
                $builder->where('c_status', 'P');

                $builder->orGroupStart()
                    ->where('c_status', 'D')
                    ->where("IF(med > h.ent_dt AND f2.$mainhead_name IS NOT NULL,$mf_f2_table $subhead_if_last_heardt,$mf_h_table $subhead_if_last_heardt)", null, false);
                if (!empty($ason_str) && trim($ason_str) !== '') $builder->where($ason_str);
                $builder->where('DATE(m.diary_no_rec_date) <', $til_dt);
                if (!empty($exclude_cond_other) && trim($exclude_cond_other) !== '') $builder->where($exclude_cond_other, null, false);
                if (!empty($cat_and_act) && trim($cat_and_act) !== '') $builder->where($cat_and_act);
                if (!empty($year_main) && trim($year_main) !== '') $builder->where($year_main);
                if (!empty($from_fil_dt) && trim($from_fil_dt) !== '') $builder->where($from_fil_dt);
                if (!empty($upto_fil_dt) && trim($upto_fil_dt) !== '') $builder->where($upto_fil_dt);
                if (!empty($bench) && trim($bench) !== '') $builder->where($bench);
                if (!empty($pc_act) && trim($pc_act) !== '') $builder->where($pc_act);
                if (!empty($women) && trim($women) !== '') $builder->where($women);
                if (!empty($children) && trim($children) !== '') $builder->where($children);
                if (!empty($land) && trim($land) !== '') $builder->where($land);
                if (!empty($cr_compound) && trim($cr_compound) !== '') $builder->where($cr_compound);
                if (!empty($commercial_code) && trim($commercial_code) !== '') $builder->where($commercial_code);
                if (!empty($party_name) && trim($party_name) !== '') $builder->where($party_name);
                if (!empty($pet_res) && trim($pet_res) !== '') $builder->where($pet_res);
                if (!empty($act_msc) && trim($act_msc) !== '') $builder->where($act_msc);
                if (!empty($registration) && trim($registration) !== '') $builder->where($registration);

                $builder->groupEnd()

                    ->orGroupStart()
                    ->where($ason_str_res)
                    ->where("IF(med > h.ent_dt AND f2.$mainhead_name IS NOT NULL,$mf_f2_table $subhead_if_last_heardt,$mf_h_table $subhead_if_last_heardt)", null, false)
                    ->where('DATE(m.diary_no_rec_date) <', $til_dt);
                if (!empty($exclude_cond_other) && trim($exclude_cond_other) !== '') $builder->where($exclude_cond_other, null, false);
                if (!empty($year_main) && trim($year_main) !== '') $builder->where($year_main);
                if (!empty($from_fil_dt) && trim($from_fil_dt) !== '') $builder->where($from_fil_dt);
                if (!empty($upto_fil_dt) && trim($upto_fil_dt) !== '') $builder->where($upto_fil_dt);
                if (!empty($cat_and_act) && trim($cat_and_act) !== '') $builder->where($cat_and_act);
                if (!empty($bench) && trim($bench) !== '') $builder->where($bench);
                if (!empty($pc_act) && trim($pc_act) !== '') $builder->where($pc_act);
                if (!empty($women) && trim($women) !== '') $builder->where($women);
                if (!empty($children) && trim($children) !== '') $builder->where($children);
                if (!empty($land) && trim($land) !== '') $builder->where($land);
                if (!empty($cr_compound) && trim($cr_compound) !== '') $builder->where($cr_compound);
                if (!empty($commercial_code) && trim($commercial_code) !== '') $builder->where($commercial_code);
                if (!empty($party_name) && trim($party_name) !== '') $builder->where($party_name);
                if (!empty($pet_res) && trim($pet_res) !== '') $builder->where($pet_res);
                if (!empty($act_msc) && trim($act_msc) !== '') $builder->where($act_msc);
                $builder->groupEnd();

                if (!empty($registration) && $registration != ' ') $builder->where($registration);
                if (!empty($subhead_condition) && $subhead_condition != ' ') $builder->where($subhead_condition);
                if (!empty($case_status_id) && $case_status_id != ' ') $builder->where($case_status_id);
                if (!empty($Brep1) && $Brep1 != ' ') $builder->where($Brep1);

                $builder->select(['m.diary_no', 'm.fil_dt', 'c_status', 'd.rj_dt', 'd.month', 'd.year', 'd.disp_dt', 'active_casetype_id', 'casetype_id']);
                $builder->groupBy(['m.diary_no', 'm.fil_dt', 'c_status', 'd.rj_dt', 'd.month', 'd.year', 'd.disp_dt', 'active_casetype_id', 'casetype_id']);
                $subQuery = $builder->getCompiledSelect();
                $sql = "SELECT SUBSTR(diary_no::text, -4) AS year, " . $str . " FROM ( $subQuery ) t GROUP BY ROLLUP(SUBSTR(diary_no::text, -4))";
            } else {

                $builder = $this->db->table('main m');
                $builder->join('dispose d', 'm.diary_no = d.diary_no', 'left');
                $builder->join('heardt h', 'm.diary_no = h.diary_no', 'left');
                $builder->join('restored r', 'm.diary_no = r.diary_no', 'left');
                $builder->join('act_main a', 'a.diary_no = m.diary_no', 'left');
                if (!empty($mul_cat_join)) {
                    $builder->join('mul_category mc', 'mc.diary_no = h.diary_no', 'left');
                    $builder->join('master.submaster s', 'mc.submaster_id = s.id', 'left');
                }

                if (!empty($act_join))
                    $builder->join($act_join, 'left');

                $builder->whereIn('case_status_id', [1, 2, 3, 6, 7, 9]);
                $builder->where("(c_status = 'P' AND DATE(m.diary_no_rec_date) < '$til_dt')");

                if (!empty($registration)  && $registration != ' ') $builder->where($registration);
                if (!empty($mf_h_table) && $mf_h_table != ' ') $builder->where($mf_h_table);
                if (!empty($cat_and_act) && $cat_and_act != ' ') $builder->where($cat_and_act);
                if (!empty($year_main) && $year_main != ' ') $builder->where($year_main);
                if (!empty($from_fil_dt) && $from_fil_dt != ' ') $builder->where($from_fil_dt);
                if (!empty($upto_fil_dt) && $upto_fil_dt != ' ') $builder->where($upto_fil_dt);
                if (!empty($case_status_id) && $case_status_id != ' ') $builder->where($case_status_id);
                if (!empty($Brep1) && $Brep1 != '') $builder->where($Brep1);
                if (!empty($subhead_condition) && $subhead_condition != ' ') $builder->where($subhead_condition);

                $builder->select(['m.diary_no', 'm.fil_dt', 'c_status', 'd.rj_dt', 'd.month', 'd.year', 'd.disp_dt', 'active_casetype_id', 'casetype_id']);
                $builder->groupBy(['m.diary_no', 'm.fil_dt', 'c_status', 'd.rj_dt', 'd.month', 'd.year', 'd.disp_dt', 'active_casetype_id', 'casetype_id']);
                $builder->limit(1000);
                $subQuery = $builder->getCompiledSelect();
                $sql = "SELECT SUBSTR(diary_no::text, -4) AS year, " . $str . " FROM ( $subQuery ) t GROUP BY ROLLUP(SUBSTR(diary_no::text, -4))";
            }
        } else {
            if ($request->getGet('til_date') != date('d-m-Y')) {
                $builder = $this->db->table('main m');
                $builder->join('heardt h', 'm.diary_no = h.diary_no', 'left');
                $builder->join('dispose d', 'm.diary_no = d.diary_no', 'left');
                $builder->join('restored r', 'm.diary_no = r.diary_no', 'left');
                $builder->join('act_main a', 'a.diary_no = m.diary_no', 'left');
                $builder->where("1=1");
                if (!empty($mul_cat_join))
                    $builder->join($mul_cat_join, 'left');

                if (!empty($act_join))
                    $builder->join($act_join, 'left');

                $builder->groupStart();
                if (!empty($exclude_cond)) {
                    $builder->where($exclude_cond, null, false);
                }
                $builder->groupEnd();
                $builder->where("DATE(m.diary_no_rec_date) <", $til_dt)->where("c_status", 'P');

                $builder->orGroupStart();
                if (!empty($registration)  && $registration != ' ') $builder->where($registration);
                if (!empty($mf_h_table) && $mf_h_table != ' ') $builder->where($mf_h_table);
                if (!empty($cat_and_act) && $cat_and_act != ' ') $builder->where($cat_and_act);
                if (!empty($year_main) && $year_main != ' ') $builder->where($year_main);
                if (!empty($from_fil_dt) && $from_fil_dt != ' ') $builder->where($from_fil_dt);
                if (!empty($upto_fil_dt) && $upto_fil_dt != ' ') $builder->where($upto_fil_dt);
                if (!empty($case_status_id) && $case_status_id != ' ') $builder->where($case_status_id);
                if (!empty($Brep1) && $Brep1 != '') $builder->where($Brep1);
                if (!empty($subhead_condition) && $subhead_condition != ' ') $builder->where($subhead_condition);
                $builder->where("c_status", 'D');
                $builder->where("DATE(m.diary_no_rec_date) <", $til_dt);
                if (!empty($ason_str)) $builder->where($ason_str);
                if (!empty($cat_and_act) && $cat_and_act != ' ') $builder->where($cat_and_act);
                if (!empty($year_main) && $year_main != ' ') $builder->where($year_main);
                if (!empty($from_fil_dt) && $from_fil_dt != ' ') $builder->where($from_fil_dt);
                if (!empty($upto_fil_dt) && $upto_fil_dt != ' ') $builder->where($upto_fil_dt);
                if (!empty($exclude_cond_other) && $exclude_cond_other != ' ') $builder->where($exclude_cond_other, null, false);
                if (!empty($main_connected) && $main_connected != ' ') $builder->where($main_connected);
                $builder->groupEnd();


                if (!empty($Brep1) && $Brep1 != '') $builder->where($Brep1);
                if (!empty($registration) && $registration != ' ') $builder->where($registration);
                if (!empty($cat_and_act) && $cat_and_act != ' ') $builder->where($cat_and_act);
                if (!empty($year_main) && $year_main != ' ') $builder->where($year_main);
                if (!empty($from_fil_dt) && $from_fil_dt != ' ') $builder->where($from_fil_dt);
                if (!empty($upto_fil_dt) && $upto_fil_dt != ' ') $builder->where($upto_fil_dt);
                if (!empty($main_connected) && $main_connected != ' ') $builder->where($main_connected);
                if (!empty($case_status_id) && $case_status_id != ' ') $builder->where($case_status_id);
                $builder->limit(1000);

                $builder->select(['m.diary_no', 'm.fil_dt', 'c_status', 'd.rj_dt', 'd.month', 'd.year', 'd.disp_dt', 'active_casetype_id', 'casetype_id']);
                $builder->groupBy(['m.diary_no', 'm.fil_dt', 'c_status', 'd.rj_dt', 'd.month', 'd.year', 'd.disp_dt', 'active_casetype_id', 'casetype_id']);
                $subQuery = $builder->getCompiledSelect();

                $sql = "SELECT SUBSTR(diary_no::text, -4) AS year, " . $str . " FROM ( $subQuery ) t GROUP BY ROLLUP(SUBSTR(diary_no::text, -4))";
            } else {

                $builder = $this->db->table('main m');
                $builder->join('dispose d', 'm.diary_no = d.diary_no', 'left');
                $builder->join('restored r', 'm.diary_no = r.diary_no', 'left');
                $builder->join('heardt h', 'm.diary_no = h.diary_no', 'left');
                $builder->join('act_main a', 'a.diary_no = m.diary_no', 'left');
                $builder->where("2=2");
                if (!empty($mul_cat_join)) {

                    $builder->join($mul_cat_join, 'left');
                }
                if (!empty($act_join)) {
                    $builder->join($act_join, 'left');
                }
                $builder->groupStart()->where('c_status', 'P');
                $builder->where("DATE(m.diary_no_rec_date) <= ", $til_dt)->groupEnd();

                if (!empty($Brep1) && $Brep1 != '') $builder->where($Brep1);
                if (!empty($registration) && $registration != ' ') $builder->where($registration);
                if (!empty($bench) && $bench != ' ') $builder->where($bench);
                if (!empty($cat_and_act) && $cat_and_act != ' ') $builder->where($cat_and_act);
                if (!empty($year_main) && $year_main != ' ') $builder->where($year_main);
                if (!empty($from_fil_dt) && $from_fil_dt != ' ') $builder->where($from_fil_dt);
                if (!empty($upto_fil_dt) && $upto_fil_dt != ' ') $builder->where($upto_fil_dt);
                if (!empty($case_status_id) && $case_status_id != ' ') $builder->where($case_status_id);

                $builder->select(['m.diary_no', 'm.fil_dt', 'c_status', 'd.rj_dt', 'd.month', 'd.year', 'd.disp_dt', 'active_casetype_id', 'casetype_id']);
                $builder->groupBy(['m.diary_no', 'm.fil_dt', 'c_status', 'd.rj_dt', 'd.month', 'd.year', 'd.disp_dt', 'active_casetype_id', 'casetype_id']);
                $builder->limit(1000);
                $subQuery = $builder->getCompiledSelect();

                $sql = "SELECT SUBSTR(diary_no::text, -4) AS year, " . $str . " FROM ( $subQuery ) t GROUP BY ROLLUP(SUBSTR(diary_no::text, -4)) ";
            }
        }

        return ['query' => $sql, 'subhead_name' => $head_subhead, 'date' => $til_dt];
    }

    public function get_nature_wise_ason_model()
    {
        $request = service('request');
        $act_join =  $add_table = '';
        $nature_wise_to = $request->getGet('nature_wise_tot');
        $year_wise_tot = $request->getGet('year_wise_tot');

        $subject = $request->getGet('subject');
        $year = $request->getGet('year');
        $skey = $request->getGet('skey');
        $subhead = $request->getGet('subhead');
        $mf = $request->getGet('mf');
        $til_date = $request->getGet('til_date');
        $from_year = $request->getGet('from_year');
        $to_year = $request->getGet('to_year');
        $rpt_type = $request->getGet('rpt_type');
        // $pet_res = $request->getGet('pet_res');
        $pet_res = '';
        $party_name = $request->getGet('party_name');
        $act_msc = $request->getGet('act_msc');
        $lst_month = $request->getGet('lst_month');
        $lst_year = $request->getGet('lst_year');
        $ason_type = $request->getGet('ason_type');
        $from_fil_dt = $request->getGet('from_fil_dt');
        $upto_fil_dt = $request->getGet('upto_fil_dt');
        $rpt_purpose = $request->getGet('rpt_purpose');
        $concept = $request->getGet('concept');
        $main_connected = $request->getGet('main_connected');
        $act = $request->getGet('act');
        $order_by = $request->getGet('order_by');
        $adv_opt = $request->getGet('adv_opt');

        $case_status_id = $request->getGet('case_status_id');

        if ($rpt_type == 'year') {
            if ($nature_wise_to == 'y' || $year_wise_tot == 'all') {
                $year_condition = " ";
                $year_condition_last_heardt = " ";
            } else {
                $year_condition = " and substr(m.diary_no::text,-4)='" . $year . "' ";
                $year_condition_last_heardt = " and SUBSTR(m.diary_no::text,-4)='" . $year . "' ";
            }
        } else {
            $year_condition = " ";
            $year_condition_last_heardt = " ";
        }


        if ($mf == 'all')
            $mf = '';
        else if ($mf == 'N')
            $mf = " and mainhead not in ('M','F')";
        else
            $mf = " and mainhead ='" . $mf . "'";

        if ($mf == 'M')      $head_mf = ' Motion Hearing ';
        elseif ($mf == 'F')  $head_mf = ' Final Hearing ';
        else if ($mf == 'all') $head_mf = ' All Hearing ';
        else if ($mf == 'N') $head_mf = ' Mainhead not in (Motion ,Final) ';

        $til_date = explode("-", $til_date);
        $til_dt = $til_date[2] . "-" . $til_date[1] . "-" . $til_date[0];

        $subhead_name = ($rpt_purpose == 'sw') ? "subhead_n" : "subhead";
        $mainhead_name = ($rpt_purpose == 'sw') ? "mainhead_n" : "mainhead";


        if ($subhead == 'all,' || $subhead == '') {
            $subhead = '';
            $subhead_if_heardt = " ";
            $subhead_if_last_heardt = " ";
            $subhead_condition = " ";
            $head_subhead = ' ';
        } else {
            $subhead = "  and l." . $subhead_name . " in (" . substr($request->getGet('subhead'), 0, -1) . ")";
            $subhead_if_heardt = " and h." . $subhead_name . " in (" . substr($request->getGet('subhead'), 0, -1) . ") ";
            $subhead_if_last_heardt = " and f2." . $subhead_name . " in (" . substr($request->getGet('subhead'), 0, -1) . ") ";


            $subhead_if_heardt_con = "  h." . $subhead_name . " in (" . substr($request->getGet('subhead'), 0, -1) . ") ";
            $subhead_if_last_heardt_con = "  f2." . $subhead_name . " in (" . substr($request->getGet('subhead'), 0, -1) . ") ";
            if ($til_date != date('d-m-Y')) {
                $head_subhead = $this->stagename(substr($request->getGet('subhead'), 0, -1));
                $subhead_condition = "";
            } else {
                $subhead_condition = "  AND " . $subhead_if_heardt_con;
                $head_subhead = $this->stagename(substr($request->getGet('subhead'), 0, -1));
            }
        }


        if (trim($_GET['subcat2']) == 'all,') {
            if (trim($_GET['subcat']) == 'all,') {
                if (trim($_GET['cat']) == 'all,') {
                    if (trim($_GET['subject']) == 'all,')
                        $all_category = " ";
                    else
                        $all_category = "  s.subcode1 in (" . substr($_GET['subject'], 0, -1) . ")";
                } else {
                    $head1 = explode(',', $_GET['cat']);
                    for ($m = 0; $m < $_GET['cat_length']; $m++) {
                        $head = explode('|', $head1[$m]);
                        if ($m == 0)
                            $str_all_cat = "  (s.subcode1 ='" . $head[0] . "' and s.subcode2='" . $head[1] . "')";
                        else
                            $str_all_cat = " (( s.subcode1 ='" . $head[0] . "' and s.subcode2='" . $head[1] . "') OR " . $str_all_cat . ")";
                    }
                    $all_category = $str_all_cat;
                }
            } else {
                $head1 = explode(',', $_GET['subcat']);
                for ($m = 0; $m < $_GET['subcat_length']; $m++) {
                    $head = explode('|', $head1[$m]);

                    if ($m == 0)
                        $str_all_cat = "  (s.subcode1 ='" . $head[0] . "' and s.subcode2='" . $head[1] . "' and s.subcode3='" . $head[2] . "')";
                    else
                        $str_all_cat = " (( s.subcode1 ='" . $head[0] . "' and s.subcode2='" . $head[1] . "' and s.subcode3='" . $head[2] . "') OR " . $str_all_cat . ")";
                }

                $all_category = $str_all_cat;
            }
        } else {
            $head1 = explode(',', $_GET['subcat2']);
            for ($m = 0; $m < $_GET['subcat2_length']; $m++) {
                $head = explode('|', $head1[$m]);

                if ($m == 0)
                    $str_all_cat = "  (s.subcode1 ='" . $head[0] . "' and s.subcode2='" . $head[1] . "' and s.subcode3='" . $head[2] . "' and s.subcode4='" . $head[3] . "')";
                else
                    $str_all_cat = " (( s.subcode1 ='" . $head[0] . "' and s.subcode2='" . $head[1] . "' and s.subcode3='" . $head[2] . "' and s.subcode4='" . $head[3] . "') OR " . $str_all_cat . ")";
            }

            $all_category = $str_all_cat;
        }


        $all_act = " ";

        if (trim($act) == 'all,' && trim($subject) == 'all,')
            $cat_and_act = " ";
        else
            $cat_and_act = " and ( " . $all_category . " " . $all_act . " )";


        if (trim($subject) != 'all,' || trim($act) != 'all,' || trim($act_msc) != '') {
            $mul_cat_join = " LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no 
                              LEFT JOIN master.submaster s ON mc.submaster_id = s.id";
            $cat_field = "";
        } else {
            $mul_cat_join = " ";
            $cat_field = " ";
        }


        if (empty($from_year) || empty($to_year)) {
            if (empty($from_year) && !empty($to_year)) {
                $year_main = " substring( m.diary_no::text,-4 ) <= '" . $to_year . "'   ";
                $year_lastheardt = " AND substring( l.diary_no::text,-4 ) <= '" . $to_year . "' ";
            } elseif (!empty($from_year) && empty($to_year)) {
                $year_main = "  substring( m.diary_no::text,-4 ) >= '" . $from_year . "' ";
                $year_lastheardt = " AND substring( l.diary_no::text,-4 ) >= '" . $from_year . "' ";
            } else {
                $year_main = " ";
                $year_lastheardt = " ";
            }
        } else {
            $year_main = " substring( m.diary_no::text,-4 ) BETWEEN '" . $from_year . "' AND '" . $to_year . "' ";
            $year_lastheardt = " AND substring( l.diary_no::text,-4 ) BETWEEN '" . $from_year . "' AND '" . $to_year . "' ";
        }

        if (empty($from_fil_dt)) {
            $from_fil_dt_condition = " ";
        } else {
            $from_fil_date = date('Y-m-d', strtotime($from_fil_dt));
            $from_fil_dt_condition = " AND date( m.diary_no_rec_date) >'" . $from_fil_date . "' ";
        }

        if (empty($upto_fil_dt)) {
            $upto_fil_dt_condition = " ";
        } else {
            $upto_fil_date = date('Y-m-d', strtotime($upto_fil_dt));
            $upto_fil_dt_condition = " AND date( m.diary_no_rec_date) <'" . $upto_fil_date . "' ";
        }

        if (trim($party_name) == '') {
            $join_party = " ";
            $party_name_condition = "  ";
        } else {
            $join_party = " LEFT JOIN party p ON m.fil_no = p.fil_no ";
            $party_name_condition = " and (partyname like'%HIGH%COURT%'   OR  partyname like'%registrar%gen%'   )  ";
        }
        if ($_GET['act_msc'] == '')
            $act_msc = '';
        else
            $act_msc = " and (a.section  like '%" . $_GET['act_msc'] . "%' OR m.act  like '%" . $_GET['act_msc'] . "%'  or usec1  like '%" . $_GET['act_msc'] . "%'  OR usec2  like '%" . $_GET['act_msc'] . "%' OR desc1  like '%" . $_GET['act_msc'] . "%' ) ";



        if ($request->getGet('ason_type') == 'dt') {
            $til_date = explode("-", $request->getGet('til_date'));
            $til_dt = $til_date[2] . "-" . $til_date[1] . "-" . $til_date[0];

        
            $ason_str = " IF(d.rj_dt IS NOT NULL ,d.rj_dt >= '" . $til_dt . "',
                        IF(d.disp_dt IS NOT NULL ,d.disp_dt >='" . $til_dt . "', concat(d.year,'-',lpad(d.month,2,0),'-01') >= '" . $til_dt . "'	 )    )  ";

            $ason_str_res = " IF(disp_rj_dt != '0000-00-00',disp_rj_dt >= '" . $til_dt . "',
                        IF( r.disp_dt != '0000-00-00' AND r.disp_dt IS NOT NULL ,r.disp_dt >='" . $til_dt . "', concat(r.disp_year,'-',lpad(r.disp_month,2,0),'-01') >= '" . $til_dt . "'	 )    )  ";

            $exclude_cond = " CASE WHEN r.disp_dt IS NOT NULL 
                        AND r.conn_next_dt IS NOT NULL
                THEN '" . $til_dt . "' NOT BETWEEN r.disp_dt AND conn_next_dt
                ELSE r.disp_dt IS NULL OR r.conn_next_dt IS NULL 
                END 
            OR r.fil_no IS NULL	";

            $exclude_cond_other = " CASE WHEN r.disp_dt IS NOT NULL 
                        AND r.conn_next_dt IS NOT NULL
                THEN '" . $til_dt . "' NOT BETWEEN r.disp_dt AND conn_next_dt
                ELSE r.disp_dt IS NULL OR r.conn_next_dt IS NULL 
                END 
                ";
        } elseif ($ason_type == 'month') {
            $til_dt = $lst_year . "-" . str_pad($lst_month, 2, "0", STR_PAD_LEFT) . "-01";

            $ason_str = " IF(d.rj_dt IS NOT NULL,d.rj_dt >= '" . $til_dt . "', 
                            IF(d.month =0,d.disp_dt >='" . $til_dt . "', concat(d.year,'-',lpad(d.month,2,0),'-01' ) >= '" . $til_dt . "' 
                            ) 
                        ) ";

            $ason_str_res = " IF(r.disp_rj_dt != '0000-00-00',r.disp_rj_dt >= '" . $til_dt . "', 
                            IF(r.disp_month =0,r.disp_dt >='" . $til_dt . "', concat(r.disp_year,'-',lpad(r.disp_month,2,0),'-01' ) >= '" . $til_dt . "' 
                            ) 
                        ) ";

            $exclude_cond = " CASE WHEN r.disp_month != '0' AND r.disp_month IS NOT NULL AND r.month != '0' AND r.month IS NOT NULL 
            THEN '" . $til_dt . "' NOT BETWEEN concat(r.disp_year,'-',lpad(r.disp_month,2,'0'),'-01') AND concat(r.year,'-',lpad(r.month,2,'0'),'-01') 
            WHEN  r.month != '0' AND r.month IS NOT NULL 
            THEN concat(r.year,'-',lpad(r.month,2,'0'),'-01')!='" . $til_dt . "'
            ELSE r.disp_month = '0' OR r.`disp_month` IS NULL OR r.month = '0' OR r.month IS NULL END OR r.fil_no IS NULL 	";

            $exclude_cond_other = " CASE 
            WHEN r.disp_month != '0' AND r.disp_month IS NOT NULL AND r.month != '0' AND r.month IS NOT NULL 
            THEN '" . $til_dt . "' NOT BETWEEN concat(r.disp_year,'-',lpad(r.disp_month,2,'0'),'-01') 
            AND concat(r.year,'-',lpad(r.month,2,'0'),'-01') 
            WHEN  r.month != '0' AND r.month IS NOT NULL 
            THEN concat(r.year,'-',lpad(r.month,2,'0'),'-01')!='" . $til_dt . "'
            ELSE r.disp_month = '0' OR r.`disp_month` IS NULL OR r.month = '0' OR r.month IS NULL END 	";
        } elseif ($ason_type == 'ent_dt') {
            $til_date = explode("-", $_GET['til_date']);
            $til_dt = $til_date[2] . "-" . $til_date[1] . "-" . $til_date[0];

            $ason_str = " d.ent_dt >= '" . $til_dt . "' ";
            $ason_str_res = " r.disp_ent_dt >= '" . $til_dt . "' ";


            $exclude_cond = " CASE WHEN r.`entry_date` IS NOT NULL AND  r.disp_ent_dt IS NOT NULL
            THEN '" . $til_dt . "' NOT BETWEEN date(r.disp_ent_dt) AND entry_date
            ELSE r.`disp_ent_dt` IS NULL OR r.entry_date IS NULL  END 
            OR r.fil_no IS NULL	";

            $exclude_cond_other = " CASE WHEN  r.`entry_date` IS NOT NULL 
                        AND  r.disp_ent_dt IS NOT NULL
            THEN '" . $til_dt . "' NOT BETWEEN date(r.disp_ent_dt) AND `entry_date` 
            ELSE r.`disp_ent_dt` IS NULL OR r.entry_date IS NULL  END ";
        }


        if ($year_wise_tot == 'y' || $year_wise_tot == 'all') {
            $year_tot = " ";
            return $request['skey'];
            $year_tot_main = " ";
        } else {
            $year_tot = " and substr(l.fil_no,4,2)='" . $this->casetype($_GET['skey']) . "' ";
            if (empty($this->casetype($_GET['skey'])))
                $year_tot_main = " and IF(m.active_casetype_id=0,m.casetype_id ,m.active_casetype_id) ='" . $this->casetype($_GET['skey']) . "' ";
            else
                $year_tot_main = "";
        }

        $mf_h_table = '';
        $mf_f2_table = '';
       

        if ($concept == 'new') {
            if ($request->getGet('mf') == 'M') {
                $mf_f2_table = " (f2." . $mainhead_name . " = 'M' AND (admitted = '' OR admitted IS NULL))";
                $mf_h_table = " (h." . $mainhead_name . " = 'M' AND (admitted = '' OR admitted IS NULL))";
            }
            if ($request->getGet('mf') == 'F') {
                $mf_f2_table = " (f2." . $mainhead_name . " = 'F' OR (admitted != '' AND admitted IS NOT NULL))";
                $mf_h_table = " (h." . $mainhead_name . " = 'F' OR (admitted != '' AND admitted IS NOT NULL))";
            }
        } elseif ($concept == 'old') {

            if ($request->getGet('mf') == 'M') {
                $mf_f2_table = " f2." . $mainhead_name . "= '" . $request->getGet('mf') . "' ";
                $mf_h_table = " h." . $mainhead_name . "= '" . $request->getGet('mf') . "' ";
            }
            if ($request->getGet('mf') == 'F') {
                $mf_f2_table = " f2." . $mainhead_name . "= '" . $request->getGet('mf') . "'  ";
                $mf_h_table = " h." . $mainhead_name . "= '" .  $request->getGet('mf') . "'  ";
            }
        }

        if ($_GET['main_connected'] == 'main')
            $main_connected = " and ( m.diary_no::text = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL ) ";
        else
            $main_connected = " ";

        if ($request->getGet('order_by') == 'case')
            $order_by = " order by substr(m.fil_no,3,3),substr(m.fil_no,11,4),substr(m.fil_no,6,5) ";
        elseif ($request->getGet('order_by') == 'fil_dt')
            $order_by = " order by date(m.active_fil_dt) ";
        elseif ($request->getGet('order_by') == 'da')
            $order_by = " order by m.dacode ";

        if ($adv_opt == 'Y') {
            $adv_field_list = " group_concat(
            IF(pet_res='P',cast(concat(a2.pet_res_no,' - ',a2.adv,' ') AS char),'') ORDER BY a2.pet_res_no SEPARATOR ' ')pet_adv2, 
            group_concat(
            IF (pet_res='R', cast(concat(a2.pet_res_no,' - ',a2.adv,' ' ) AS char ) , '' ) ORDER BY a2.pet_res_no SEPARATOR ' ' )res_adv2, ";
            $adv_join = " LEFT JOIN advocate a2 ON a2.diary_no = m.diary_no ";
        } else {
            $adv_field_list = " '' as pet_adv2, '' as res_adv2, ";
            $adv_join = " ";
        }

        if ($case_status_id == 'all,') {
            $case_status_id = " and case_status_id in (1, 2, 3, 6, 7, 9 ) ";
        } elseif ($case_status_id == 103 || $case_status_id == '103,') {
            $case_status_id = " ";
        } elseif ($case_status_id == 101 || $case_status_id == '101,') {
            $case_status_id = " and o.rm_dt IS NOT NULL 
                AND o.display = 'Y' 
                AND m.c_status = 'P' 
                AND (m.fil_no IS NULL 
                OR m.fil_no = '')";
            $add_table = ' LEFT JOIN obj_save o ON o.diary_no = m.diary_no ';
        } elseif ($case_status_id == 102 || $case_status_id == '102,') {
            $case_status_id = " AND NOT (m.fil_no IS NULL OR m.fil_no = '') ";
        } else {
            $case_status_id = " and case_status_id in (" . substr($_GET['case_status_id'], 0, -1) . ")";
        }


        //INNER JOIN vw3 f2 ON m.fil_no = f2.fil_no	 
        // having mainhead = '".$mf."'  and mnd=next_dt

        if ($request->getGet('mf') != 'ALL') {
            if ($request->getGet('til_date') != date('d-m-Y')) {
                $t = "CREATE TEMPORARY TABLE vw2 
                            SELECT MAX(ent_dt) AS med, " . $subhead_name . ", " . $mainhead_name . ", fil_no
                            FROM `last_heardt` l
                            WHERE DATE(ent_dt) < '" . $til_dt . "' 
                            " . $year_condition_last_heardt . " " . $year_tot . "
                            GROUP BY diary_no";
                $this->db->query($t);

                $t2 = "CREATE INDEX id_index ON vw2 (fil_no)";
                $this->db->query($t2);

                $t3 = "CREATE TEMPORARY TABLE vw3 SELECT l.fil_no, l." . $subhead_name . ", l." . $mainhead_name . ", l.jud1, med, next_dt
                            FROM vw2 
                            INNER JOIN last_heardt l ON vw2.fil_no = l.fil_no
                            AND l.ent_dt = med
                            AND l." . $mainhead_name . " = '" . $mf . "' " . $subhead . " " . $year_condition_last_heardt . " " . $year_tot;
                $this->db->query($t3);

                $t4 = "CREATE INDEX id_index2 ON vw3 (fil_no)";
                $this->db->query($t4);
            }

            if ($request->getGet('til_date') != date('d-m-Y')) {
                $sql = "SELECT " . $adv_field_list . " m.diary_no_rec_date,tentative_cl_dt, m.active_fil_no, m.active_fil_dt, m.active_reg_year, m.active_casetype_id, m.casetype_id, c_status, d.rj_dt, d.month, d.year, d.disp_dt,  
                        r.disp_month, r.disp_year, f2." . $subhead_name . " AS last_subhead, med, h.ent_dt, h." . $mainhead_name . " AS mainhead, r.conn_next_dt, r.disp_dt AS disp_dt_res, m.pet_name, m.res_name, h.next_dt " . $cat_field . ", m.bench, m.lastorder, h.judges, m.diary_no
                        FROM main m 
                        LEFT JOIN dispose d ON m.diary_no = d.diary_no  
                        LEFT JOIN restored r ON m.diary_no = r.diary_no  
                        LEFT JOIN heardt h ON m.diary_no = h.diary_no  
                        LEFT JOIN vw3 f2 ON m.diary_no = f2.diary_no
                        LEFT JOIN act_main a ON a.diary_no = m.diary_no " . $add_table . $mul_cat_join . " " . $act_join . " " . $adv_join . " " . $join_party . "
                        WHERE 1=1 " . $party_name . " " . $pet_res . " " . $year_main . " " . $from_fil_dt . " " . $upto_fil_dt . " " . $case_status_id . " " . $cat_and_act . " " . $act_msc . " " . $main_connected . "
                        AND IF(med > h.ent_dt AND f2." . $mainhead_name . " IS NOT NULL, " . $mf_f2_table . " " . $subhead_if_last_heardt . ", " . $mf_h_table . " " . $subhead_if_last_heardt . ")
                        AND (
                    CASE WHEN r.disp_dt IS NOT NULL 
                                AND r.conn_next_dt IS NOT NULL
                        THEN '" . $til_dt . "' NOT BETWEEN r.disp_dt AND conn_next_dt
                        ELSE r.`disp_dt` IS NULL OR r.conn_next_dt IS NULL 
                        END 
                    OR r.diary_no IS NULL
                    )
                        " . $subhead_condition . " AND
                        DATE(m.diary_no_rec_date) < '" . $til_dt . "' " . $year_condition . " " . $year_tot_main . " AND (c_status = 'P' AND DATE(m.diary_no_rec_date) < '" . $til_dt . "')
                        OR (
                            c_status = 'D' 
                            AND IF(med > h.ent_dt AND f2." . $mainhead_name . " IS NOT NULL, " . $mf_f2_table . " " . $subhead_if_last_heardt . ", " . $mf_h_table . " " . $subhead_if_last_heardt . ")
                            AND " . $ason_str . " AND DATE(m.diary_no_rec_date) < '" . $til_dt . "' " . $year_condition . " " . $year_main . " " . $year_tot_main . " " . $from_fil_dt_condition . " " . $upto_fil_dt_condition . " " . $cat_and_act . " " . $party_name . " " . $pet_res . " " . $act_msc . " " . $main_connected . "
                        )
                        OR (" . $ason_str_res . " 
                            AND IF(med > h.ent_dt AND f2." . $mainhead_name . " IS NOT NULL, " . $mf_f2_table . " " . $subhead_if_last_heardt . ", " . $mf_h_table . " " . $subhead_if_last_heardt . ")
                            AND DATE(m.diary_no_rec_date) < '" . $til_dt . "' " . $year_condition . " " . $year_main . " " . $year_tot_main . "" . $from_fil_dt_condition . " " . $upto_fil_dt_condition . " " . $cat_and_act . " " . $party_name . " " . $pet_res . " " . $act_msc . " " . $main_connected . "
                        )
                        GROUP BY m.diary_no,tentative_cl_dt,d.rj_dt,d.month,d.year,d.disp_dt,r.disp_month,r.disp_year,h.ent_dt,h.mainhead_n,r.conn_next_dt,h.next_dt,h.judges,r.disp_dt " . $order_by;
            } else {
                $sql = "SELECT " . $adv_field_list . " m.diary_no_rec_date,tentative_cl_dt, m.active_fil_no, m.active_fil_dt, m.active_reg_year, m.active_casetype_id, m.casetype_id, c_status, d.rj_dt, d.month, d.year, d.disp_dt, 
                        r.disp_month, r.disp_year, h.ent_dt, h." . $mainhead_name . " AS mainhead, r.conn_next_dt, r.disp_dt AS disp_dt_res, m.pet_name, m.res_name, h.next_dt " . $cat_field . ", m.bench, m.lastorder, h.judges, m.diary_no
                        FROM main m 
                        LEFT JOIN dispose d ON m.diary_no = d.diary_no  
                        LEFT JOIN restored r ON m.diary_no = r.diary_no  
                        LEFT JOIN heardt h ON m.diary_no = h.diary_no  
                        " . $add_table . $mul_cat_join . " " . $act_join . " " . $adv_join . " " . $join_party . "
                        WHERE " . $mf_h_table . " " . $party_name . " " . $pet_res . " " . $year_main . " " . $from_fil_dt_condition . " " . $upto_fil_dt_condition . " " . $case_status_id . " " . $cat_and_act . " " . $act_msc . " " . $main_connected . "
                       AND  ( CASE WHEN r.disp_dt IS NOT NULL AND r.conn_next_dt IS NOT NULL THEN '" . $til_dt . "' NOT BETWEEN r.disp_dt AND conn_next_dt
                        ELSE r.disp_dt IS NULL OR r.conn_next_dt IS NULL END OR r.diary_no IS NULL )
                        " . $subhead_condition . " AND
                        DATE(m.diary_no_rec_date) < '" . $til_dt . "' " . $year_condition . " " . $year_tot_main . " AND (c_status = 'P' AND DATE(m.diary_no_rec_date) < '" . $til_dt . "')
                        GROUP BY m.diary_no,tentative_cl_dt,d.rj_dt,d.month,d.year,d.disp_dt,r.disp_month,r.disp_year,h.ent_dt,h.mainhead_n,r.conn_next_dt,h.next_dt,h.judges,r.disp_dt " . $order_by;
            }
        } else {
            if ($request->getGet('til_date') != date('d-m-Y')) {
                $sql = "SELECT {$adv_field_list} m.diary_no_rec_date, m.active_fil_no, tentative_cl_dt,m.active_fil_dt, m.active_reg_year, m.active_casetype_id, m.casetype_id, c_status, d.rj_dt, d.month, d.year, d.disp_dt, 
                r.disp_month, r.disp_year, r.conn_next_dt, r.disp_dt as res_disp_dt, m.pet_name, m.res_name, {$mainhead_name}, next_dt {$cat_field}, m.bench, m.lastorder, h.judges, m.diary_no
                FROM main m 
                LEFT JOIN heardt h ON m.diary_no = h.diary_no 
                LEFT JOIN dispose d ON m.diary_no = d.diary_no  
                LEFT JOIN restored r ON m.diary_no = r.diary_no    
                LEFT JOIN act_main a ON a.diary_no = m.diary_no {$add_table} {$mul_cat_join} {$act_join} {$adv_join} {$join_party}
                WHERE 1=1 {$party_name} {$pet_res} {$year_main} {$from_fil_dt} {$upto_fil_dt} {$case_status_id} {$cat_and_act} {$act_msc} {$main_connected} AND 
                (
                    CASE WHEN r.disp_dt IS NOT NULL 
                                AND r.conn_next_dt IS NOT NULL
                        THEN '" . $til_dt . "' NOT BETWEEN r.disp_dt AND conn_next_dt 
                        ELSE r.`disp_dt` IS NULL OR r.conn_next_dt IS NULL 
                        END 
                    OR r.diary_no IS NULL
                    )
                AND
                DATE(m.diary_no_rec_date) < '{$til_dt}' {$year_condition} {$year_tot_main} AND (c_status = 'P' AND DATE(m.diary_no_rec_date) < '{$til_dt}')
                OR 
                (
                    c_status = 'D' AND {$ason_str} AND DATE(m.diary_no_rec_date) < '{$til_dt}' {$year_condition} {$year_main} {$year_tot_main} {$from_fil_dt} {$upto_fil_dt} {$cat_and_act} {$party_name} {$pet_res} {$act_msc} {$main_connected}
                )
                OR ({$ason_str_res} AND DATE(m.diary_no_rec_date) < '{$til_dt}' {$year_condition} {$year_main} {$year_tot_main} {$from_fil_dt} {$upto_fil_dt} {$cat_and_act} {$party_name} {$pet_res} {$act_msc} {$main_connected})
                GROUP BY m.diary_no,tentative_cl_dt,d.rj_dt,d.month,d.year,d.disp_dt,r.disp_month,r.disp_year,h.ent_dt,h.mainhead_n,r.conn_next_dt,h.next_dt,h.judges,r.disp_dt {$order_by}";
            } else {
                $sql = "SELECT {$adv_field_list} m.diary_no_rec_date,tentative_cl_dt, m.active_fil_no, m.active_fil_dt, m.active_reg_year, m.active_casetype_id, m.casetype_id, c_status, d.rj_dt, d.month, d.year, d.disp_dt,
                r.disp_month, r.disp_year, r.conn_next_dt, r.disp_dt as res_disp_dt,r.disp_dt AS disp_dt_res, m.pet_name,h.ent_dt, m.res_name,  h." . $mainhead_name . " AS mainhead, next_dt {$cat_field}, m.bench, m.lastorder, h.judges, m.diary_no
                FROM main m 
                LEFT JOIN heardt h ON m.diary_no = h.diary_no 
                LEFT JOIN dispose d ON m.diary_no = d.diary_no  
                LEFT JOIN restored r ON m.diary_no = r.diary_no    
                LEFT JOIN act_main a ON a.diary_no = m.diary_no {$add_table} {$mul_cat_join} {$act_join} {$adv_join} {$join_party}
                WHERE 1=1 {$party_name} {$pet_res} {$year_main} {$from_fil_dt} {$upto_fil_dt} {$case_status_id} {$cat_and_act} {$act_msc} {$main_connected} AND 
                (
                    CASE WHEN r.disp_dt IS NOT NULL 
                                AND r.conn_next_dt IS NOT NULL
                        THEN '" . $til_dt . "' NOT BETWEEN r.disp_dt AND conn_next_dt
                        ELSE r.disp_dt IS NULL OR r.conn_next_dt IS NULL 
                        END 
                    OR r.diary_no IS NULL
                    )
                AND
                DATE(m.diary_no_rec_date) < '{$til_dt}' {$year_condition} {$year_tot_main} AND (c_status = 'P' AND DATE(m.diary_no_rec_date) < '{$til_dt}')
                GROUP BY m.diary_no ,d.rj_dt,d.month,d.year,d.disp_dt,r.disp_month,r.disp_year,h.ent_dt,h.mainhead_n,r.conn_next_dt,h.next_dt,h.judges,r.disp_dt,tentative_cl_dt {$order_by}";
                // pr($sql);
            }
        }
        return [
            'query' => $sql,
            'skey' => $skey,
            'mainhead_name' => $mainhead_name,
            'subhead_name' => $subhead_name,
            'til_dt' => $til_dt,
            'year_wise_tot' => $year_wise_tot,
            'case_status_id' => $case_status_id
        ];
    }


    function casetype($skey)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('master.casetype');
        $builder->select('casecode');
        $builder->where('skey', $skey);
        $query = $builder->get();
        $result = $query->getRowArray();

        return $result ? $result['casecode'] : " ";
    }
    // Shubham Work END
}
