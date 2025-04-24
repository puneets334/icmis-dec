<?php

namespace App\Models\Judicial;

use CodeIgniter\Model;

class ReportModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    function getSubheading($subhead = 0)
    {
        // Prepare the query
        $query = $this->db->table('master.subheading')
            ->select('*')
            ->where('stagecode', $subhead)
            ->where('display', 'Y')
            ->get();
        
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return [];
        }
    }

    function getListingPurpose($data = [])
    {
        // Prepare the query using the query builder
        $builder = $this->db->table('heardt h');
        $builder->select('u.name, u.empid, h.mainhead, h.board_type, s.stagename, l.purpose, h.tentative_cl_dt');
        $builder->join('master.users u', 'u.usercode = h.usercode AND u.display = \'Y\'', 'left');
        $builder->join('master.listing_purpose l', 'l.code = h.listorder AND l.display = \'Y\'', 'left');
        $builder->join('master.subheading s', 's.stagecode = h.subhead AND s.display = \'Y\' AND s.listtype = \'M\'', 'left');
        $builder->where('h.diary_no', $data['diary_no']);
        $builder->where('h.next_dt !=', $data['next_dt']);
        $builder->where('h.clno', 0);
        $builder->where('h.brd_slno', 0);

        // Execute the query and get the results
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return [];
        }
    }

    function getCaseMultipleRemarks($data = [])
    {
        // Prepare the query using the query builder
        $builder = $this->db->table('case_remarks_multiple c');
        $builder->select('c.r_head, h.side, c.head_content, h.head');
        $builder->join('master.case_remarks_head h', 'c.r_head = h.sno');
        $builder->where('c.diary_no', $data['diary_no']);
        $builder->where('c.cl_date', $data['cl_date']);
        $builder->where('c.jcodes', $data['jcodes']);
        $builder->where('c.remove', 0);
        $builder->orderBy('c.e_date', 'DESC');

        // Execute the query and get the results
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return [];
        }
    }

    function getReaderRemarks($diary_no = 0)
    {
        // Using CodeIgniter's Query Builder
        $builder = $this->db->table('brdrem');
        $builder->select('*');
        $builder->where('diary_no', $diary_no);

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return [];
        }
    }

    function getOrderDetails($data = [])
    {
        // Using CodeIgniter's Query Builder
        $sql = "
            SELECT diary_no, pdfname, orderdate
            FROM (
                SELECT 
                    o.diary_no AS diary_no,
                    o.pdfname AS pdfname,
                    TO_CHAR(o.orderdate, 'YYYY-MM-DD') AS orderdate,
                    CASE
                        WHEN o.type = 'O' THEN 'ROP'
                        WHEN o.type = 'J' THEN 'Judgement'
                    END AS jo
                FROM 
                    ordernet o
                WHERE 
                    o.diary_no = '" . $data['diary_no'] . "' AND o.orderdate = '" . $data['orderdate'] . "'
            ) AS tbl1 
            WHERE jo = 'ROP'
            ORDER BY orderdate DESC
        ";

        $query = $this->db->query($sql);

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return [];
        }
    }

    function getCaseType($casecode = 0)
    {
        // Prepare the query using Query Builder for case type
        $builder = $this->db->table('master.casetype');
        $builder->select('skey, short_description');
        $builder->where('display', 'Y');
        $builder->where('casecode', $casecode);

        // Execute the query
        $casetypeQuery = $builder->get();

        // Check if any rows were returned
        if ($casetypeQuery->getNumRows() > 0) {
            return $casetypeQuery->getRowArray();
        }

        return false;
    }

    public function getVarifiedMattersByDate($data = [])
    {
        $ucode = $data['usercode'];
        $usertype = $data['usertype'];

        $all_da = "";
        $checkDaCode = "";
        if ($ucode == 1) {
            $checkDaCode = "";
        } else if ($usertype == '14' and $ucode != 3564 and $ucode != 722 and $ucode != 1182 and $ucode != 184) {
            $builder = $this->db->table('users u');
            $builder->select("STRING_AGG(u2.usercode, ',') AS allda");
            $builder->join('users u2', 'u2.section = u.section', 'LEFT');
            $builder->where('u.display', 'Y');
            $builder->where('u.usercode', $ucode);
            $builder->groupBy('u2.section');

            $query = $builder->get();
            $result = $query->getRow();

            $all_da = $result ? $result->allda : '';

            $checkDaCode = " AND (m.dacode=$ucode OR m.dacode IN ($all_da)) ";
        } else if (($usertype == '17' or $usertype == '50' or $usertype == '51') and ($ucode != 3564 and $ucode != 722 and $ucode != 1182 and $ucode != 184)) {
            $checkDaCode = " AND m.dacode=$ucode ";
        }

        $sql = "
            SELECT DISTINCT
                m.diary_no,
                h.next_dt,
                u.name,
                CASE 
                    WHEN us.section_name IS NOT NULL THEN us.section_name 
                    ELSE tentative_section(m.diary_no) 
                END AS section_name,
                m.conn_key AS main_key,
                l.purpose,
                s.stagename,
                h.coram,
                c1.short_description,
                m.active_fil_no,
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
                m.lastorder,
                pno,
                rno,
                m.diary_no_rec_date,
                CASE
                    WHEN (
                        m.diary_no::text = m.conn_key 
                        OR m.conn_key = '0' 
                        OR m.conn_key = '' 
                        OR m.conn_key IS NULL
                    ) 
                    THEN 0 
                    ELSE 1 
                END AS main_or_connected,
                (SELECT CASE WHEN diary_no IS NOT NULL THEN 1 ELSE 0 END 
                FROM conct
                WHERE diary_no = m.diary_no AND LIST = 'Y') AS listed,
                TO_CHAR(tt.ent_dt, 'DD-MM-YYYY HH12:MI AM') AS verified_on,
                (SELECT STRING_AGG(remarks, '<br/>') 
                FROM master.case_verify_by_sec_remark 
                WHERE id::text IN (SELECT UNNEST(STRING_TO_ARRAY(tt.remark_id, ',')))) AS remarks_by_monitoring,
                (SELECT name || '(' || empid || ')' 
                FROM master.users 
                WHERE usercode = tt.ucode) AS verified_by,
                CASE WHEN (m.conn_key != '') THEN SUBSTRING(m.conn_key::text FROM -4) ELSE SUBSTRING(m.diary_no::text FROM -4) END AS order_key,
                CASE WHEN (m.conn_key != '') THEN m.conn_key ELSE m.diary_no::text END AS order_key2,
                CASE WHEN (m.conn_key = m.diary_no::text) THEN 0 ELSE 1 END AS order_key3
                FROM main m 
                INNER JOIN heardt h ON h.diary_no = m.diary_no
                INNER JOIN case_verify tt ON tt.diary_no = h.diary_no 
                LEFT JOIN master.listing_purpose l ON l.code = h.listorder AND l.display = 'Y'
                LEFT JOIN master.subheading s ON s.stagecode = h.subhead AND s.display = 'Y' AND s.listtype = 'M'    
                LEFT JOIN master.casetype c1 ON m.active_casetype_id = c1.casecode
                LEFT JOIN master.users u ON u.usercode = m.dacode AND u.display = 'Y'
                LEFT JOIN master.usersection us ON us.id = u.section
                WHERE tt.display = 'Y'   
                AND date(h.next_dt) = '" . $data['list_dt'] . "'
                $checkDaCode
                AND (
                    TRIM(LEADING '0' FROM SUBSTRING(m.fil_no FROM 1 FOR POSITION('-' IN m.fil_no) - 1))::int IN (3, 15, 19, 31, 23, 24, 40, 32, 34, 22, 39, 11,
                        17, 13, 1, 7, 37, 9999, 38, 5, 21, 27, 4, 16, 20, 18, 33, 41, 35, 36, 28, 12, 14, 2, 8, 6)
                    OR m.active_fil_no = ''
                    OR m.active_fil_no IS NULL
                )        
                AND (
                    CASE
                        WHEN (
                            m.diary_no::text = m.conn_key 
                            OR m.conn_key = '0' 
                            OR m.conn_key = '' 
                            OR m.conn_key IS NULL
                        ) 
                        THEN TRUE 
                        ELSE (
                            (SELECT DISTINCT conn_key 
                            FROM conct 
                            WHERE diary_no = m.diary_no) IN 
                            (SELECT diary_no 
                            FROM heardt t1 
                            WHERE t1.next_dt = h.next_dt)
                        ) 
                    END
                ) 
                GROUP BY m.diary_no, 
                h.next_dt, 
                u.name, 
                us.section_name, 
                l.purpose, 
                s.stagename, 
                h.coram,
                c1.short_description,
                verified_on,
                remarks_by_monitoring,
                tt.ucode
                ORDER BY 
                order_key,
                order_key2,
                order_key3,
                main_or_connected ASC
        ";

        $query = $this->db->query($sql);

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        }

        return [];
    }

    public function getSectionName($data = [])
    {
        $sql = "
            SELECT a.dacode, c.section_name, b.name
            FROM master.da_case_distribution a
            LEFT JOIN master.users b ON b.usercode = a.dacode
            LEFT JOIN master.usersection c ON b.section = c.id
            WHERE a.case_type = '" . $data['casetype_displ'] . "' 
            AND '" . $data['ten_reg_yr'] . "' BETWEEN a.case_f_yr AND a.case_t_yr 
            AND a.state = '" . $data['ref_agency_state_id'] . "' 
            AND a.display = 'Y'
        ";

        $query = $this->db->query($sql);

        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        }

        return [];
    }

    public function getPartyNames($diary_no = 0)
    {
        $sql = "
        SELECT a.*, 
            STRING_AGG(CASE WHEN pet_res = 'R' THEN grp_adv END, ',' ORDER BY adv_type DESC, pet_res_no ASC) AS r_n,
            STRING_AGG(CASE WHEN pet_res = 'P' THEN grp_adv END, ',' ORDER BY adv_type DESC, pet_res_no ASC) AS p_n,
            STRING_AGG(CASE WHEN pet_res = 'I' THEN grp_adv END, ',' ORDER BY adv_type DESC, pet_res_no ASC) AS i_n
        FROM (
            SELECT a.diary_no, b.name, 
                STRING_AGG(COALESCE(a.adv, ''), ',' ORDER BY adv_type DESC, pet_res_no ASC) AS grp_adv, 
                a.pet_res, a.adv_type, pet_res_no
            FROM advocate a
            LEFT JOIN master.bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y'
            WHERE a.diary_no = '" . $diary_no . "' AND a.display = 'Y'
            GROUP BY a.diary_no, b.name, a.pet_res, a.adv_type, pet_res_no
            ORDER BY adv_type DESC, pet_res_no ASC
        ) a 
        GROUP BY a.diary_no, a.name, grp_adv, a.pet_res, a.adv_type, pet_res_no
        ";

        $query = $this->db->query($sql);

        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        }

        return [];
    }

    public function getOldSCcategory($diary_no = 0)
    {

        $result = [];

        // Build the query using Query Builder
        $res_sm = $this->db->table('mul_category mc')
            ->select('category_sc_old')
            ->join('master.submaster s', 's.id = mc.submaster_id')
            ->where('mc.display', 'Y')
            ->where('mc.diary_no', $diary_no)
            ->get();

        // Check if there are results
        if ($res_sm->getNumRows() > 0) {
            // Fetch the results
            foreach ($res_sm->getResultArray() as $cate_old_id) {
                $result[] = $cate_old_id['category_sc_old'];
            }
        }

        return $result;
    }

    public function getCasesWithoutDA_bkup($data = [])
    {
        $section = "";
        if (!empty($data['section'])) {
            $section = " AND section IN (" . implode(",", $data['section']) . ") ";
        }

        $ddl_all_blank_a = "";
        if ($data['ddl_all_blank'] == 2) {
            $ddl_all_blank_a = " where totdoc is null and totup is null and totoff is null and totnot is null AND 
        supuser is null and red is null AND p_notice_not_made is null AND d_notice_not_made is null 
        AND totdoc_not is null ";
        } elseif ($data['ddl_all_blank'] == 3) {
            $ddl_all_blank_a = " where totdoc is not null or totup is not null or totoff is not null or 
        totnot is not null or supuser is not null or red is not null or 
        p_notice_not_made is not null or d_notice_not_made is not null or totdoc_not is not null ";
        }

        $filter_date = $data['filter_date'];

        $sql = "SELECT * FROM 
        (
            SELECT usercode, u.name, empid, section_name, type_name, section, usertype 
            FROM master.users u 
            LEFT JOIN master.usersection us ON section = us.id
            LEFT JOIN master.usertype ut ON usertype = ut.id
            WHERE isda = 'Y' AND u.display = 'Y' AND us.display = 'Y' AND usertype IN (17, 50, 51) $section
            --UNION
            --SELECT 0, 'NO DACODE', '0', '0', '0', 0, 0
        )t1 
        LEFT JOIN
        (
            SELECT COUNT(*) totdoc, dacode
            FROM ld_move a 
            INNER JOIN docdetails d ON a.diary_no = d.diary_no 
                                    AND a.doccode = d.doccode 
                                    AND a.doccode1 = d.doccode1 
                                    AND a.docnum = d.docnum 
                                    AND a.docyear = d.docyear 
                                    AND d.display = 'Y' 
                                    AND a.rece_by = 0 
            INNER JOIN main m ON d.diary_no = m.diary_no
            LEFT JOIN master.docmaster dm ON d.doccode = dm.doccode AND d.doccode1 = dm.doccode1
            WHERE d.ent_dt::date = '" . $filter_date . "'    
            AND d.display = 'Y' 
            GROUP BY disp_to, m.dacode
        )t2 ON t1.usercode = dacode
        LEFT JOIN 
        (
            SELECT COUNT(*) totup, usercode daheardt 
            FROM (
                SELECT diary_no, usercode 
                FROM heardt 
                WHERE ent_dt::date = '" . $filter_date . "'

                UNION

                SELECT diary_no, usercode 
                FROM last_heardt 
                WHERE ent_dt::date = '" . $filter_date . "'
                GROUP BY diary_no, last_heardt.usercode
            ) t1
            GROUP BY usercode
        )t3 ON t1.usercode = daheardt

        LEFT JOIN 
        (
            SELECT DISTINCT m.dacode as dddcc, 
                SUM(CASE WHEN t1.usercode = 1 THEN 1 ELSE 0 END) supuser 
            FROM (
                SELECT diary_no, usercode 
                FROM heardt 
                WHERE ent_dt::date = '" . $filter_date . "'

                UNION

                SELECT diary_no, usercode 
                FROM last_heardt 
                WHERE ent_dt::date = '" . $filter_date . "'
                GROUP BY diary_no, last_heardt.usercode
            ) t1
            LEFT JOIN main m ON t1.diary_no = m.diary_no
            LEFT JOIN master.users u ON u.usercode = m.dacode
            WHERE u.usertype IN (17, 50, 51) AND u.display = 'Y' 
            GROUP BY m.dacode
        )t3a ON t1.usercode = dddcc

        LEFT JOIN 
        (
            SELECT COUNT(*) totoff, rec_user_id 
            FROM office_report_details 
            WHERE rec_dt::date = '" . $filter_date . "' 
            AND display = 'Y'
            GROUP BY rec_user_id
        )t4 ON t1.usercode = rec_user_id

        LEFT JOIN
        (
            SELECT COUNT(*) totnot, user_id 
            FROM tw_tal_del 
            WHERE rec_dt::date = '" . $filter_date . "' 
            AND display = 'Y'
            GROUP BY user_id
        )t5 ON t1.usercode = user_id 

        LEFT JOIN 
        (
            SELECT dacode as rogy_da,
                COUNT(DISTINCT total) as total_tt,
                COUNT(DISTINCT red) as red, 
                COUNT(DISTINCT orange) as orange, 
                COUNT(DISTINCT green) as green,
                COUNT(DISTINCT yellow) as yellow 
            FROM (
                SELECT empid, dacode, name, type_name, section_name, m.diary_no as total,
                    CASE WHEN EXTRACT(DAY FROM (h.tentative_cl_dt::timestamp - now())) < 2 THEN m.diary_no END as red,
                    CASE WHEN EXTRACT(DAY FROM (h.tentative_cl_dt::timestamp - now())) > 1 THEN m.diary_no END as orange,
                    CASE WHEN (h.main_supp_flag = 0) THEN m.diary_no END as green,
                    CASE WHEN h.main_supp_flag = 3 THEN m.diary_no END as yellow
                FROM main m
                INNER JOIN master.casetype c ON c.casecode = COALESCE(m.active_casetype_id, m.casetype_id) 
                LEFT JOIN heardt h ON m.diary_no = h.diary_no
                LEFT JOIN master.users usr ON m.dacode = usr.usercode
                LEFT JOIN master.usertype ut ON ut.id = usr.usertype
                LEFT JOIN rgo_default rd ON m.diary_no = rd.fil_no
                LEFT JOIN master.usersection b ON b.id = usr.section 
                LEFT JOIN master.subheading s ON h.subhead = s.stagecode 
                WHERE c_status = 'P'
            ) a 
            GROUP BY empid, dacode, name, type_name, section_name, a.total 
            ORDER BY section_name, type_name DESC, total
        ) t6 ON t1.usercode = t6.rogy_da

        LEFT JOIN
        (
            SELECT COUNT(a.diary_no) d_notice_not_made, m.dacode d_notice_not_made_da 
            FROM (
                SELECT diary_no, MAX(cl_date) cl_dt 
                FROM case_remarks_multiple 
                WHERE cl_date > '2018-01-01' 
                AND status = 'D'
                GROUP BY diary_no
            ) a
            LEFT JOIN tw_tal_del t ON t.diary_no::text = a.diary_no::text 
                                AND t.rec_dt > cl_dt 
                                AND t.display = 'Y' 
                                AND t.rec_dt <= '" . $filter_date . "'
            LEFT JOIN main m ON m.diary_no::text = a.diary_no::text
            WHERE t.diary_no IS NULL
            GROUP BY m.dacode
        ) t7 ON t1.usercode = d_notice_not_made_da

        LEFT JOIN
        (
            SELECT COUNT(a.diary_no) p_notice_not_made, m.dacode p_notice_not_made_da 
            FROM (
                SELECT diary_no, MAX(cl_date) cl_dt 
                FROM case_remarks_multiple 
                WHERE cl_date > '2018-01-01' 
                AND r_head IN (3, 9, 113, 181, 182, 183, 184)
                GROUP BY diary_no
            ) a
            LEFT JOIN tw_tal_del t ON t.diary_no::text = a.diary_no::text 
                                AND t.rec_dt > cl_dt 
                                AND t.display = 'Y' 
                                AND t.rec_dt <= '" . $filter_date . "'
            LEFT JOIN main m ON m.diary_no::text = a.diary_no::text
            WHERE t.diary_no IS NULL
            GROUP BY m.dacode
        ) t8 ON t1.usercode = p_notice_not_made_da

        LEFT JOIN
        (
            SELECT COUNT(*) totdoc_not, disp_to as dacode_not_veri 
            FROM ld_move a 
            INNER JOIN docdetails d ON a.diary_no = d.diary_no 
                                AND a.doccode = d.doccode 
                                AND a.doccode1 = d.doccode1 
                                AND a.docnum = d.docnum 
                                AND a.docyear = d.docyear 
                                AND d.display = 'Y'  
            INNER JOIN main m ON d.diary_no::text = m.diary_no::text 
            LEFT JOIN master.docmaster c ON a.doccode = c.doccode AND a.doccode1 = c.doccode1
            WHERE d.ent_dt::date <= '" . $filter_date . "' 
            AND (verified IS NULL OR verified = '') 
            AND d.iastat = 'P' 
            AND d.display = 'Y' 
            GROUP BY disp_to
        ) t9 ON t1.usercode = dacode_not_veri

        $ddl_all_blank_a
        ORDER BY section_name, usertype limit 100";

        //echo $sql;
        //die();
        $query = $this->db->query($sql);

        // Check for results
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return [];
        }
    }

    public function getCasesWithoutDA($data = [])
    {
        $section = "";
        if (!empty($data['section'])) {
            $section = " AND section IN (" . implode(",", $data['section']) . ") ";
        }

        $ddl_all_blank_a = "";
        if ($data['ddl_all_blank'] == 2) {
            $ddl_all_blank_a = " where totdoc is null and totup is null and totoff is null and totnot is null AND 
            supuser is null and red is null AND p_notice_not_made is null AND d_notice_not_made is null 
            AND totdoc_not is null ";
        } elseif ($data['ddl_all_blank'] == 3) 
        {
            $ddl_all_blank_a = " where totdoc is not null or totup is not null or totoff is not null or 
            totnot is not null or supuser is not null or red is not null or 
            p_notice_not_made is not null or d_notice_not_made is not null or totdoc_not is not null ";
        }

        $filter_date = $data['filter_date'];
        $sql = "SELECT 
            empid,
            MAX(t1.usercode) AS usercode,
            MAX(t1.name) AS name,
            MAX(t1.section_name) AS section_name,
            MAX(t1.type_name) AS type_name,
            MAX(t1.section) AS section,
            MAX(t1.usertype) AS usertype,
            SUM(COALESCE(t2.totdoc, 0)) AS totdoc,
            SUM(COALESCE(t3.totup, 0)) AS totup,
            SUM(COALESCE(t3a.supuser, 0)) AS supuser,
            SUM(COALESCE(t4.totoff, 0)) AS totoff,
            SUM(COALESCE(t5.totnot, 0)) AS totnot,
            SUM(COALESCE(t6.total_tt, 0)) AS total_tt,
            SUM(COALESCE(t6.red, 0)) AS red,
            SUM(COALESCE(t6.orange, 0)) AS orange,
            SUM(COALESCE(t6.green, 0)) AS green,
            SUM(COALESCE(t6.yellow, 0)) AS yellow,
            SUM(COALESCE(t7.d_notice_not_made, 0)) AS d_notice_not_made,
            SUM(COALESCE(t7.d_notice_not_made_da, 0)) AS d_notice_not_made_da,
            SUM(COALESCE(t8.p_notice_not_made, 0)) AS p_notice_not_made,
            SUM(COALESCE(t8.p_notice_not_made_da, 0)) AS p_notice_not_made_da,
            SUM(COALESCE(t9.totdoc_not, 0)) AS totdoc_not,
            SUM(COALESCE(t9.dacode_not_veri, 0)) AS dacode_not_veri 
        FROM 
        (
            SELECT usercode, u.name, empid, section_name, type_name, section, usertype 
            FROM master.users u 
            LEFT JOIN master.usersection us ON section = us.id
            LEFT JOIN master.usertype ut ON usertype = ut.id
            WHERE isda = 'Y' AND u.display = 'Y' AND us.display = 'Y' AND usertype IN (17, 50, 51) $section
            --UNION
            --SELECT 0, 'NO DACODE', '0', '0', '0', 0, 0
        )t1 
        LEFT JOIN
        (
            SELECT COUNT(*) totdoc, dacode
            FROM ld_move a 
            INNER JOIN docdetails d ON a.diary_no = d.diary_no 
                                    AND a.doccode = d.doccode 
                                    AND a.doccode1 = d.doccode1 
                                    AND a.docnum = d.docnum 
                                    AND a.docyear = d.docyear 
                                    AND d.display = 'Y' 
                                    AND a.rece_by = 0 
            INNER JOIN main m ON d.diary_no = m.diary_no
            LEFT JOIN master.docmaster dm ON d.doccode = dm.doccode AND d.doccode1 = dm.doccode1
            WHERE d.ent_dt::date = '" . $filter_date . "'    
            AND d.display = 'Y' 
            GROUP BY disp_to, m.dacode
        )t2 ON t1.usercode = dacode
        LEFT JOIN 
        (
            SELECT COUNT(*) totup, usercode daheardt 
            FROM (
                SELECT diary_no, usercode 
                FROM heardt 
                WHERE ent_dt::date = '" . $filter_date . "'

                UNION

                SELECT diary_no, usercode 
                FROM last_heardt 
                WHERE ent_dt::date = '" . $filter_date . "'
                GROUP BY diary_no, last_heardt.usercode
            ) t1
            GROUP BY usercode
        )t3 ON t1.usercode = daheardt

        LEFT JOIN 
        (
            SELECT DISTINCT m.dacode as dddcc, 
                SUM(CASE WHEN t1.usercode = 1 THEN 1 ELSE 0 END) supuser 
            FROM (
                SELECT diary_no, usercode 
                FROM heardt 
                WHERE ent_dt::date = '" . $filter_date . "'

                UNION

                SELECT diary_no, usercode 
                FROM last_heardt 
                WHERE ent_dt::date = '" . $filter_date . "'
                GROUP BY diary_no, last_heardt.usercode
            ) t1
            LEFT JOIN main m ON t1.diary_no = m.diary_no
            LEFT JOIN master.users u ON u.usercode = m.dacode
            WHERE u.usertype IN (17, 50, 51) AND u.display = 'Y' 
            GROUP BY m.dacode
        )t3a ON t1.usercode = dddcc

        LEFT JOIN 
        (
            SELECT COUNT(*) totoff, rec_user_id 
            FROM office_report_details 
            WHERE rec_dt::date = '" . $filter_date . "' 
            AND display = 'Y'
            GROUP BY rec_user_id
        )t4 ON t1.usercode = rec_user_id

        LEFT JOIN
        (
            SELECT COUNT(*) totnot, user_id 
            FROM tw_tal_del 
            WHERE rec_dt::date = '" . $filter_date . "' 
            AND display = 'Y'
            GROUP BY user_id
        )t5 ON t1.usercode = user_id 

        LEFT JOIN 
        (
            SELECT dacode as rogy_da,
                COUNT(DISTINCT total) as total_tt,
                COUNT(DISTINCT red) as red, 
                COUNT(DISTINCT orange) as orange, 
                COUNT(DISTINCT green) as green,
                COUNT(DISTINCT yellow) as yellow 
            FROM (
                SELECT empid, dacode, name, type_name, section_name, m.diary_no as total,
                    CASE WHEN EXTRACT(DAY FROM (h.tentative_cl_dt::timestamp - now())) < 2 THEN m.diary_no END as red,
                    CASE WHEN EXTRACT(DAY FROM (h.tentative_cl_dt::timestamp - now())) > 1 THEN m.diary_no END as orange,
                    CASE WHEN (h.main_supp_flag = 0) THEN m.diary_no END as green,
                    CASE WHEN h.main_supp_flag = 3 THEN m.diary_no END as yellow
                FROM main m
                INNER JOIN master.casetype c ON c.casecode = COALESCE(m.active_casetype_id, m.casetype_id) 
                LEFT JOIN heardt h ON m.diary_no = h.diary_no
                LEFT JOIN master.users usr ON m.dacode = usr.usercode
                LEFT JOIN master.usertype ut ON ut.id = usr.usertype
                LEFT JOIN rgo_default rd ON m.diary_no = rd.fil_no
                LEFT JOIN master.usersection b ON b.id = usr.section 
                LEFT JOIN master.subheading s ON h.subhead = s.stagecode 
                WHERE c_status = 'P'
            ) a 
            GROUP BY empid, dacode, name, type_name, section_name, a.total 
            ORDER BY section_name, type_name DESC, total
        ) t6 ON t1.usercode = t6.rogy_da

        LEFT JOIN
        (
            SELECT COUNT(a.diary_no) d_notice_not_made, m.dacode d_notice_not_made_da 
            FROM (
                SELECT diary_no, MAX(cl_date) cl_dt 
                FROM case_remarks_multiple 
                WHERE cl_date > '2018-01-01' 
                AND status = 'D'
                GROUP BY diary_no
            ) a
            LEFT JOIN tw_tal_del t ON t.diary_no::text = a.diary_no::text 
                                AND t.rec_dt > cl_dt 
                                AND t.display = 'Y' 
                                AND t.rec_dt <= '" . $filter_date . "'
            LEFT JOIN main m ON m.diary_no::text = a.diary_no::text
            WHERE t.diary_no IS NULL
            GROUP BY m.dacode
        ) t7 ON t1.usercode = d_notice_not_made_da

        LEFT JOIN
        (
            SELECT COUNT(a.diary_no) p_notice_not_made, m.dacode p_notice_not_made_da 
            FROM (
                SELECT diary_no, MAX(cl_date) cl_dt 
                FROM case_remarks_multiple 
                WHERE cl_date > '2018-01-01' 
                AND r_head IN (3, 9, 113, 181, 182, 183, 184)
                GROUP BY diary_no
            ) a
            LEFT JOIN tw_tal_del t ON t.diary_no::text = a.diary_no::text 
                                AND t.rec_dt > cl_dt 
                                AND t.display = 'Y' 
                                AND t.rec_dt <= '" . $filter_date . "'
            LEFT JOIN main m ON m.diary_no::text = a.diary_no::text
            WHERE t.diary_no IS NULL
            GROUP BY m.dacode
        ) t8 ON t1.usercode = p_notice_not_made_da

        LEFT JOIN
        (
            SELECT COUNT(*) totdoc_not, disp_to as dacode_not_veri 
            FROM ld_move a 
            INNER JOIN docdetails d ON a.diary_no = d.diary_no 
                                AND a.doccode = d.doccode 
                                AND a.doccode1 = d.doccode1 
                                AND a.docnum = d.docnum 
                                AND a.docyear = d.docyear 
                                AND d.display = 'Y'  
            INNER JOIN main m ON d.diary_no::text = m.diary_no::text 
            LEFT JOIN master.docmaster c ON a.doccode = c.doccode AND a.doccode1 = c.doccode1
            WHERE d.ent_dt::date <= '" . $filter_date . "' 
            AND (verified IS NULL OR verified = '') 
            AND d.iastat = 'P' 
            AND d.display = 'Y' 
            GROUP BY disp_to
        ) t9 ON t1.usercode = dacode_not_veri

        $ddl_all_blank_a
        GROUP BY empid
        ORDER BY empid ASC limit 100;
        ";

        //echo $sql;
        //die();
        $query = $this->db->query($sql);

        // Check for results
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return [];
        }
    }

    public function getEmployeeSections($empid, $column = 'section_name')
    {
        $builder = $this->db->table('master.user_sec_map a');
        $builder->select('b.section_name, a.usec');
        $builder->join('master.usersection b', 'usec=b.id');
        $builder->where('empid', $empid);
        $builder->where('a.display', 'Y');
        $builder->where('b.display', 'Y');
        
        $query = $builder->get();

        if ($query->getResult()) { 
            return array_column($query->getResultArray(), $column);
        } else {
            return [];
        }
    }

    function getJudges($data = [])
    {
        $builder = $this->db->table('master.judge');

        // Prepare the query
        $builder->select(['jcode AS jcode', 'TRIM(jname) AS jname']);

        if (!empty($data['jcode'])) {
            $builder->where('jcode', $data['jcode']);
        }

        $builder->where('display', 'Y');
        $builder->where('is_retired', 'N');
        $builder->whereIn('jtype', ['J', 'R']);
        $builder->orderBy('jtype');
        $builder->orderBy('judge_seniority');

        // Execute the query
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return [];
        }
    }

    function getJudgesRoster($data = [])
    {
        $to_ref_date = date('Y-m-d');
        $stg = '';
        if ($data['mf'] == 'M') {
            $stg = '1';
        } else if ($data['mf'] == 'F') {
            $stg = '2';
        }
        if ($data['crt'] == '') {
            if($data['crt'] == '1970-01-01'){
                $t_cn = " AND (board_type_mb != 'R' OR board_type_mb = 'R' )";
            }else{
                    $t_cn = " AND (board_type_mb != 'R' OR board_type_mb = 'R' AND from_date = '" . $data['tdt1'] . "'  AND to_date::text = '".$to_ref_date."') ";
            }
            
        } else if ($data['crt'] == "101") {
            $t_cn = " and board_type_mb = 'C' AND from_date = '" . $data['tdt1'] . "' ";
        } else if ($data['crt'] == "102") {
            $t_cn = " and board_type_mb = 'R' AND to_date::text = '".$to_ref_date."' ";
        } else {
            
            $t_cn = " and courtno = '" . $data['crt'] . "' AND ((to_date::text = '0000-00-00' AND from_date::text = '" . $data['tdt1'] . "') OR ('" . $data['tdt1'] . "' BETWEEN from_date AND to_date)) ";
        }

        $sql = "
            SELECT DISTINCT 
                    rj.roster_id, 
                    judge_id,
                    mb.board_type_mb,
                    CASE 
                        WHEN courtno = 0 THEN 9999 
                        ELSE courtno 
                    END AS ordered_courtno,
                    CASE 
                        WHEN mb.board_type_mb = 'J' THEN 1
                        WHEN mb.board_type_mb = 'C' THEN 2
                        WHEN mb.board_type_mb = 'CC' THEN 3
                        WHEN mb.board_type_mb = 'R' THEN 4
                    END AS board_type_order
                FROM 
                    master.roster_judge rj 
                JOIN 
                    master.roster r ON rj.roster_id = r.id 
                JOIN 
                    master.roster_bench rb ON rb.id = r.bench_id AND rb.display = 'Y'
                JOIN 
                    master.master_bench mb ON mb.id = rb.bench_id AND mb.display = 'Y'
                WHERE 
                    r.m_f = '$stg'
                    $t_cn 
                    AND rj.display = 'Y' 
                    AND r.display = 'Y' 
                ORDER BY 
                    ordered_courtno,
                    board_type_order, 
                    judge_id Limit 100
        ";

        //echo $sql;die;

        // Execute the query
        $query = $this->db->query($sql);

        // Check for results
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return [];
        }
    }

    function getCaseDetails($diary_no = 0)
    {
        // Prepare the query using the query builder
        $builder = $this->db->table('main m');
        $builder->select('reg_no_display, m.diary_no, m.dacode, u.empid');
        $builder->join('users u', 'u.usercode = m.dacode');
        $builder->where('m.diary_no', $diary_no);

        // Execute the query
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return [];
        }
    }

    function addCaseVerifyROP($data)
    {
        // Execute the insert
        $builder = $this->db->table('case_verify_rop');
        $resss = $builder->insert($data);

        // Check if the insert was successful
        if ($resss) {
            return $this->db->affectedRows(); // Get the number of affected rows
        } else {
            return 0;
        }
    }

    function getShowLCDMsg($data = [])
    {
        // Prepare the query using the query builder
        $builder = $this->db->table('showlcd');
        $builder->select('msg');
        $builder->where('(court)::int', (int) $data['court']);
        $builder->where('cl_dt', $data['cl_dt']);

        // Execute the query
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return [];
        }
    }

    function addMSG($data = [])
    {
        // Load the database connection
        $db = \Config\Database::connect();

        // Execute the insert
        $builder = $db->table('msg');
        $success = $builder->insert($data);

        // Check if the insert was successful
        if ($success) {
            return $this->db->affectedRows(); // Get the number of affected rows
        } else {
            return 0;
        }
    }

    function getCaseVerifyBySecRemarkById($id = 0)
    {
        // Execute the query and get the results
        $builder = $this->db->table('master.case_verify_by_sec_remark');
        $builder->where('id', $id);
        // Execute the query
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return [];
        }
    }

    function getCaseVerifyBySecRemark()
    {
        // Execute the query and get the results
        $builder = $this->db->table('master.case_verify_by_sec_remark');

        // Execute the query
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return [];
        }
    }  
    

    function getCaseVerifyROP($data = [])
    {
        // Prepare the query using raw SQL (since query builder does not support STRING_AGG directly)
        // $sql = "SELECT STRING_AGG(cr.remarks, ', ') AS rem_dtl, cv.ent_dt 
        //             FROM case_verify_rop cv
        //             LEFT JOIN master.case_verify_by_sec_remark cr ON cr.id = ANY(string_to_array(cv.remark_id, ',')::int[])
        //             WHERE cv.diary_no = '" . $data['diary_no'] . "' AND cv.cl_dt >= '" . $data['cl_dt'] . "'
        //             GROUP BY cv.id";
        $sql = "SELECT STRING_AGG(cr.remarks, ', ') AS rem_dtl, cv.ent_dt 
                    FROM case_verify_rop cv
                    LEFT JOIN master.case_verify_by_sec_remark cr 
                    ON cr.id = ANY(
                        string_to_array(
                            REGEXP_REPLACE(cv.remark_id, '[^0-9,]', '', 'g'),
                            ','
                        )::int[]
                    )
                    WHERE cv.diary_no = '" . $data['diary_no'] . "' AND cv.cl_dt >= '" . $data['cl_dt'] . "'
                    GROUP BY cv.id";
        
        // Execute the query
        $query = $this->db->query($sql);

        // Check for results
        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return [];
        }
    }

    function getDADropNote($data = [])
    {
        // $sql = "SELECT d.*, 
        //             (SELECT STRING_AGG(jname, ', ' ORDER BY CASE WHEN jsen = 0 THEN 99999 ELSE jsen END) 
        //             FROM master.judge 
        //             WHERE (jcode = d.jud1 OR jcode = d.jud2) AND jcode != 0) AS jnm 
        //         FROM drop_note d 
        //         WHERE d.diary_no = '" . $data["diary_no"] . "' 
        //         AND d.display = 'Y' 
        //         AND d.cl_date = '" . date("Y-m-d", strtotime($data["tdt1"])) . "' 
        //         AND d.clno = " . $data["brd_slno"] . " 
        //         AND d.jud1 = " . $data["jud1"] . " 
        //         AND d.jud2 = " . $data["jud2"] . " 
        //         ORDER BY d.ent_dt ASC";

        $sql = "SELECT d.*, 
                    (SELECT STRING_AGG(jname, ', ' ORDER BY CASE WHEN judge_seniority = 0 THEN 99999 ELSE judge_seniority END) 
                    FROM master.judge 
                    WHERE jcode != 0) AS jnm 
                FROM drop_note d 
                WHERE d.diary_no = '" . $data["diary_no"] . "' 
                AND d.display = 'Y' 
                AND d.cl_date = '" . date("Y-m-d", strtotime($data["tdt1"])) . "' 
                AND d.clno = " . $data["brd_slno"] . "  
                ORDER BY d.ent_dt ASC";

        // Execute the query
        $query = $this->db->query($sql);

        // Check for results
        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return [];
        }
    }

    function getDAHearings($data = [])
    {
        
        $t1_nxtdate = '';$t2_nxtdate = '';$h_nxtdate = '';
        if($data['tdt1']  != '1970-01-01' && !empty($data['tdt1']) ){
            $t1_nxtdate = "t1.next_dt =  '".$data['tdt1']."' AND ";
            $t2_nxtdate =  "t2.next_dt =  '".$data['tdt1']."' AND ";
            $h_nxtdate = " AND h.next_dt =  '".$data['tdt1']."'  ";
        }

        $whereStatus = "";
        if ($data['r_status'] == 'A') {
            $whereStatus = '';
        } else if ($data['r_status'] == 'P') {
            $whereStatus = " and m.c_status='P'";
        } else if ($data['r_status'] == 'D') {
            $whereStatus = " and m.c_status='D'";
        }

        $sql = "
            SELECT 
                SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4) AS case_no,
                SUBSTRING(m.diary_no::text FROM -4) AS year,
                to_char(m.fil_dt, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_f,
                CASE
                    WHEN m.reg_year_mh = 0 THEN EXTRACT(YEAR FROM m.fil_dt)
                    ELSE m.reg_year_mh
                END AS m_year,
                m.diary_no,
                m.mf_active,
                m.conn_key,
                h.judges,
                h.mainhead,
                h.next_dt,
                h.subhead,
                h.clno,
                h.brd_slno,
                h.tentative_cl_dt,
                m.pet_name,
                m.res_name,
                m.pet_adv_id,
                m.res_adv_id,
                m.c_status,
                CASE
                    WHEN cl.next_dt IS NULL THEN 'NA'
                    ELSE h.brd_slno::TEXT
                END AS brd_prnt,
                h.roster_id,
                to_char(m.fil_dt_fh, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_fh,
                CASE
                    WHEN m.reg_year_fh = 0 THEN EXTRACT(YEAR FROM m.fil_dt_fh)
                    ELSE m.reg_year_fh
                END AS f_year,
                CASE
                    WHEN m.fil_no != '' THEN split_part(m.fil_no, '-', 1)
                    ELSE ''
                END AS ct1,
                CASE
                    WHEN m.fil_no != '' THEN split_part(m.fil_no, '-', 2)
                    ELSE ''
                END AS crf1,
                CASE
                    WHEN m.fil_no != '' THEN split_part(m.fil_no, '-', array_length(string_to_array(m.fil_no, '-'), 1))
                    ELSE ''
                END AS crl1,
                CASE
                    WHEN m.fil_no_fh != '' THEN split_part(m.fil_no_fh, '-', 1)
                    ELSE ''
                END AS ct2,
                CASE
                    WHEN m.fil_no_fh != '' THEN split_part(m.fil_no_fh, '-', 2)
                    ELSE ''
                END AS crf2,
                CASE
                    WHEN m.fil_no_fh != '' THEN split_part(m.fil_no_fh, '-', array_length(string_to_array(m.fil_no_fh, '-'), 1))
                    ELSE ''
                END AS crl2,
                m.casetype_id,
                m.case_status_id 
            FROM (
                SELECT 
                    t1.diary_no,
                    t1.next_dt,
                    t1.roster_id,
                    t1.judges,
                    t1.mainhead,
                    t1.subhead,
                    t1.clno,
                    t1.brd_slno,
                    t1.main_supp_flag,
                    t1.tentative_cl_dt
                FROM
                    heardt t1 
                WHERE 
                    $t1_nxtdate 
                    t1.mainhead = '" . $data['mf'] . "' 
                    AND position(t1.roster_id::text IN ('" . $data['result'] . "')) > 0
                    AND (t1.main_supp_flag = 1 OR t1.main_supp_flag = 2)

                UNION

                SELECT 
                    t2.diary_no,
                    t2.next_dt,
                    t2.roster_id,
                    t2.judges,
                    t2.mainhead,
                    t2.subhead,
                    t2.clno,
                    t2.brd_slno,
                    t2.main_supp_flag,
                    t2.tentative_cl_dt
                FROM
                    last_heardt t2 
                WHERE 
                    $t2_nxtdate
                    t2.mainhead = '" . $data['mf'] . "' 
                    AND position(t2.roster_id::text IN ('" . $data['result'] . "')) > 0
                    AND (t2.main_supp_flag = 1 OR t2.main_supp_flag = 2) 
                    AND t2.bench_flag = ''
            ) h 
            INNER JOIN main m 
                ON (
                    h.diary_no = m.diary_no 
                    $h_nxtdate
                    AND h.mainhead = '" . $data['mf'] . "' 
                    AND position(h.roster_id::text IN ('" . $data['result'] . "')) > 0
                    AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
                )
            LEFT JOIN cl_printed cl 
                ON (
                    cl.next_dt = h.next_dt 
                    AND cl.m_f = h.mainhead 
                    AND cl.part = h.clno 
                    AND cl.main_supp = h.main_supp_flag 
                    AND cl.roster_id = h.roster_id 
                    AND cl.display = 'Y'
                ) 
            WHERE h.next_dt IS NOT NULL 
            $whereStatus
            ORDER BY h.judges,
                h.brd_slno,
                CASE 
                    WHEN m.conn_key = h.diary_no::text THEN '0000-00-00'
                    ELSE '99'
                END ASC,
                CAST(SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3 FOR 4) AS INTEGER) ASC,
                CAST(SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4) AS INTEGER) ASC
                LIMIT 300
        ";

        //echo $sql;die;

        // Execute the query
        $query = $this->db->query($sql);

        // Check for results
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return [];
        }
    }

    function getROPHearings($data = [])
    {
        $whereStatus = "";
        if ($data['r_status'] == 'A') {
            $whereStatus = '';
        } else if ($data['r_status'] == 'P') {
            $whereStatus = " and m.c_status='P'";
        } else if ($data['r_status'] == 'D') {
            $whereStatus = " and m.c_status='D'";
        }

        $left_join_verify = "";
        $left_join_verify_whr = "";
        if ($data['vstats'] == 0) {
            $list_print_flag = "(Verified/Not Verified )";
            $left_join_verify = "LEFT JOIN case_verify_rop tt ON tt.diary_no = h.diary_no 
            AND tt.ent_dt > h.heardt_ent_dt and tt.cl_dt = h.next_dt AND tt.display = 'Y' ";
            $left_join_verify_whr = "  ";
        }
        if ($data['vstats'] == 2) {
            $list_print_flag = "(Not Verified Cases)";
            $left_join_verify = "LEFT JOIN case_verify_rop tt ON tt.diary_no = h.diary_no AND date(tt.cl_dt) = date(h.next_dt) 
            AND tt.ent_dt > h.heardt_ent_dt AND tt.display = 'Y' ";
            $left_join_verify_whr = " tt.diary_no IS NULL AND ";
        }
        if ($data['vstats'] == 1) {

            $list_print_flag = "(Verified Cases)";
            $left_join_verify = "LEFT JOIN case_verify_rop tt ON tt.diary_no = h.diary_no AND h.next_dt <= tt.cl_dt AND date(tt.cl_dt) <= date(h.next_dt) AND tt.display = 'Y' ";
            $left_join_verify_whr = " tt.diary_no IS NOT NULL AND ";
        }

        $sql = "
            SELECT 
                tt.remark_id, 
                tt.cl_dt, 
                SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4) AS case_no,
                SUBSTRING(m.diary_no::text FROM -4) AS year,
                to_char(m.fil_dt, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_f,
                CASE
                    WHEN m.reg_year_mh = 0 THEN EXTRACT(YEAR FROM m.fil_dt)
                    ELSE m.reg_year_mh
                END AS m_year,
                m.diary_no,
                m.reg_no_display,
                m.mf_active,
                m.conn_key,
                h.judges,
                h.mainhead,
                h.board_type,
                h.next_dt,
                h.subhead,    
                h.clno,
                h.brd_slno,
                h.heardt_ent_dt,
                h.tentative_cl_dt,
                m.pet_name,
                m.res_name,
                m.pet_adv_id,
                m.res_adv_id,
                m.c_status,
                CASE
                    WHEN cl.next_dt IS NULL THEN 'NA'
                    ELSE h.brd_slno::TEXT
                END AS brd_prnt,
                h.roster_id,
                to_char(m.fil_dt_fh, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_fh,
                CASE
                    WHEN m.reg_year_fh = 0 THEN EXTRACT(YEAR FROM m.fil_dt_fh)
                    ELSE m.reg_year_fh
                END AS f_year,
                CASE
                    WHEN m.fil_no != '' THEN split_part(m.fil_no, '-', 1)
                    ELSE ''
                END AS ct1,
                CASE
                    WHEN m.fil_no != '' THEN split_part(m.fil_no, '-', 2)
                    ELSE ''
                END AS crf1,
                CASE
                    WHEN m.fil_no != '' THEN split_part(m.fil_no, '-', array_length(string_to_array(m.fil_no, '-'), 1))
                    ELSE ''
                END AS crl1,
                CASE
                    WHEN m.fil_no_fh != '' THEN split_part(m.fil_no_fh, '-', 1)
                    ELSE ''
                END AS ct2,
                CASE
                    WHEN m.fil_no_fh != '' THEN split_part(m.fil_no_fh, '-', 2)
                    ELSE ''
                END AS crf2,
                CASE
                    WHEN m.fil_no_fh != '' THEN split_part(m.fil_no_fh, '-', array_length(string_to_array(m.fil_no_fh, '-'), 1))
                    ELSE ''
                END AS crl2,
                m.casetype_id,
                m.case_status_id 
            FROM (
                SELECT 
                    t1.diary_no,
                    t1.next_dt,
                    t1.roster_id,
                    t1.judges,
                    t1.mainhead,
                    t1.board_type,
                    t1.subhead,
                    t1.listorder,
                    t1.clno,
                    t1.brd_slno,
                    t1.ent_dt AS heardt_ent_dt,
                    t1.main_supp_flag,
                    t1.tentative_cl_dt
                FROM
                    heardt t1 
                WHERE 
                    t1.next_dt = '" . $data['tdt1'] . "' 
                    AND t1.mainhead = '" . $data['mf'] . "' 
                    AND position(t1.roster_id::text IN ('" . $data['result'] . "')) > 0
                    AND (t1.main_supp_flag = 1 OR t1.main_supp_flag = 2)

                UNION

                SELECT 
                    t2.diary_no,
                    t2.next_dt,
                    t2.roster_id,
                    t2.judges,
                    t2.mainhead,
                    t2.board_type,
                    t2.subhead,
                    t2.listorder,
                    t2.clno,
                    t2.brd_slno,
                    t2.ent_dt AS heardt_ent_dt,
                    t2.main_supp_flag,
                    t2.tentative_cl_dt
                FROM
                    last_heardt t2 
                WHERE 
                    t2.next_dt = '" . $data['tdt1'] . "' 
                    AND t2.mainhead = '" . $data['mf'] . "' 
                    AND position(t2.roster_id::text IN ('" . $data['result'] . "')) > 0
                    AND (t2.main_supp_flag = 1 OR t2.main_supp_flag = 2) 
                    AND t2.bench_flag = ''
            ) h 
            INNER JOIN main m 
                ON (
                    h.diary_no = m.diary_no 
                    AND h.next_dt = '" . $data['tdt1'] . "' 
                    AND h.mainhead = '" . $data['mf'] . "' 
                    AND position(h.roster_id::text IN ('" . $data['result'] . "')) > 0
                    AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
                )
            $left_join_verify 
            LEFT JOIN cl_printed cl 
                ON (
                    cl.next_dt = h.next_dt 
                    AND cl.m_f = h.mainhead 
                    AND cl.part = h.clno 
                    AND cl.main_supp = h.main_supp_flag 
                    AND cl.roster_id = h.roster_id 
                    AND cl.display = 'Y'
                ) 
            WHERE $left_join_verify_whr cl.next_dt IS NOT NULL 
            $whereStatus
            GROUP BY h.diary_no, tt.remark_id,tt.cl_dt,m.diary_no, h.judges, h.mainhead, h.board_type, h.next_dt, h.subhead, h.clno,h.brd_slno,h.heardt_ent_dt,h.tentative_cl_dt, cl.next_dt, h.roster_id 
            ORDER BY h.judges,
                h.brd_slno,
                CASE 
                    WHEN m.conn_key = h.diary_no::text THEN '0000-00-00'
                    ELSE m.fil_dt::text
                END ASC;
        ";

        //echo $sql;die;

        // Execute the query
        $query = $this->db->query($sql);

        // Check for results
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return [];
        }
    }

    function getDAHearingsByJudges($data = [])
    {
        $whereStatus = "";
        if ($data['r_status'] == 'A') {
            $whereStatus = '';
        } else if ($data['r_status'] == 'P') {
            $whereStatus = " and m.c_status='P'";
        } else if ($data['r_status'] == 'D') {
            $whereStatus = " and m.c_status='D'";
        }

        $sql = "
            SELECT 
                SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4) AS case_no,
                SUBSTRING(m.diary_no::text FROM -4) AS year,
                to_char(m.fil_dt, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_f,
                CASE
                    WHEN m.reg_year_mh = 0 THEN EXTRACT(YEAR FROM m.fil_dt)
                    ELSE m.reg_year_mh
                END AS m_year,
                m.diary_no,
                m.mf_active,
                m.conn_key,
                h.judges,
                h.mainhead,
                h.next_dt,
                h.subhead,
                h.clno,
                h.brd_slno,
                h.tentative_cl_dt,
                m.pet_name,
                m.res_name,
                m.pet_adv_id,
                m.res_adv_id,
                m.c_status,
                CASE
                    WHEN cl.next_dt IS NULL THEN 'NA'
                    ELSE h.brd_slno::text
                END AS brd_prnt,
                h.roster_id,
                to_char(m.fil_dt_fh, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_fh,
                CASE
                    WHEN m.reg_year_fh = 0 THEN EXTRACT(YEAR FROM m.fil_dt_fh)
                    ELSE m.reg_year_fh
                END AS f_year,
                CASE
                    WHEN m.fil_no != '' THEN split_part(m.fil_no, '-', 1)
                    ELSE ''
                END AS ct1,
                CASE
                    WHEN m.fil_no != '' THEN split_part(m.fil_no, '-', 2)
                    ELSE ''
                END AS crf1,
                CASE
                    WHEN m.fil_no != '' THEN split_part(m.fil_no, '-', array_length(string_to_array(m.fil_no, '-'), 1))
                    ELSE ''
                END AS crl1,
                CASE
                    WHEN m.fil_no_fh != '' THEN split_part(m.fil_no_fh, '-', 1)
                    ELSE ''
                END AS ct2,
                CASE
                    WHEN m.fil_no_fh != '' THEN split_part(m.fil_no_fh, '-', 2)
                    ELSE ''
                END AS crf2,
                CASE
                    WHEN m.fil_no_fh != '' THEN split_part(m.fil_no_fh, '-', array_length(string_to_array(m.fil_no_fh, '-'), 1))
                    ELSE ''
                END AS crl2,
                m.casetype_id,
                m.case_status_id 
            FROM (
                SELECT 
                    t1.diary_no,
                    t1.next_dt,
                    t1.roster_id,
                    t1.judges,
                    t1.mainhead,
                    t1.subhead,
                    t1.clno,
                    t1.brd_slno,
                    t1.main_supp_flag,
                    t1.tentative_cl_dt
                FROM
                    heardt t1 
                WHERE 
                    t1.next_dt = '" . $data['tdt1'] . "' 
                    AND t1.mainhead = '" . $data['mf'] . "' 
                    AND position('" . $data['jcd'] . "' IN t1.judges) > 0
                    AND (t1.main_supp_flag = 1 OR t1.main_supp_flag = 2)

                UNION

                SELECT 
                    t2.diary_no,
                    t2.next_dt,
                    t2.roster_id,
                    t2.judges,
                    t2.mainhead,
                    t2.subhead,  
                    t2.clno,
                    t2.brd_slno,
                    t2.main_supp_flag,
                    t2.tentative_cl_dt
                FROM
                    last_heardt t2 
                WHERE 
                    t2.next_dt = '" . $data['tdt1'] . "' 
                    AND t2.mainhead = '" . $data['mf'] . "' 
                    AND position('" . $data['jcd'] . "' IN t2.judges) > 0
                    AND (t2.main_supp_flag = 1 OR t2.main_supp_flag = 2) 
                    AND t2.bench_flag = ''
            ) h 
            INNER JOIN main m 
                ON (
                    h.diary_no = m.diary_no 
                    AND h.next_dt = '" . $data['tdt1'] . "' 
                    AND h.mainhead = '" . $data['mf'] . "' 
                    AND position('" . $data['jcd'] . "' IN h.judges) > 0
                    AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
                ) 
            LEFT JOIN cl_printed cl 
                ON (
                    cl.next_dt = h.next_dt 
                    AND cl.m_f = h.mainhead 
                    AND cl.part = h.clno 
                    AND cl.main_supp = h.main_supp_flag 
                    AND cl.roster_id = h.roster_id 
                    AND cl.display = 'Y'
                ) 
            WHERE cl.next_dt IS NOT NULL $whereStatus
            ORDER BY 
                position('" . $data['jcd'] . "' IN h.judges),
                h.brd_slno,
                CASE 
                    WHEN m.conn_key = h.diary_no::text THEN '0000-00-00'
                    ELSE m.fil_dt::text
                END ASC,
                CAST(SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3 FOR 4) AS INTEGER) ASC,
                CAST(SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4) AS INTEGER) ASC
        ";

        // echo $sql;

        $query = $this->db->query($sql);

        // Check for results
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return [];
        }
    }

    function getROPHearingsByJudges($data = [])
    {
        $whereStatus = "";
        if ($data['r_status'] == 'A') {
            $whereStatus = '';
        } else if ($data['r_status'] == 'P') {
            $whereStatus = " and m.c_status='P'";
        } else if ($data['r_status'] == 'D') {
            $whereStatus = " and m.c_status='D'";
        }

        $sql = "
            SELECT 
                SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4) AS case_no,
                SUBSTRING(m.diary_no::text FROM -4) AS year,
                to_char(m.fil_dt, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_f,
                CASE
                    WHEN m.reg_year_mh = 0 THEN EXTRACT(YEAR FROM m.fil_dt)
                    ELSE m.reg_year_mh
                END AS m_year,
                m.diary_no,
                m.reg_no_display,
                m.mf_active,
                m.conn_key,
                h.judges,
                h.mainhead,
                h.board_type,
                h.next_dt,
                h.subhead,
                s.stagename,
                h.clno,
                h.brd_slno,
                h.tentative_cl_dt,
                m.pet_name,
                m.res_name,
                m.pet_adv_id,
                m.res_adv_id,
                m.c_status,
                CASE
                    WHEN cl.next_dt IS NULL THEN 'NA'
                    ELSE h.brd_slno::text
                END AS brd_prnt,
                h.roster_id,
                to_char(m.fil_dt_fh, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_fh,
                CASE
                    WHEN m.reg_year_fh = 0 THEN EXTRACT(YEAR FROM m.fil_dt_fh)
                    ELSE m.reg_year_fh
                END AS f_year,
                CASE
                    WHEN m.fil_no != '' THEN split_part(m.fil_no, '-', 1)
                    ELSE ''
                END AS ct1,
                CASE
                    WHEN m.fil_no != '' THEN split_part(m.fil_no, '-', 2)
                    ELSE ''
                END AS crf1,
                CASE
                    WHEN m.fil_no != '' THEN split_part(m.fil_no, '-', array_length(string_to_array(m.fil_no, '-'), 1))
                    ELSE ''
                END AS crl1,
                CASE
                    WHEN m.fil_no_fh != '' THEN split_part(m.fil_no_fh, '-', 1)
                    ELSE ''
                END AS ct2,
                CASE
                    WHEN m.fil_no_fh != '' THEN split_part(m.fil_no_fh, '-', 2)
                    ELSE ''
                END AS crf2,
                CASE
                    WHEN m.fil_no_fh != '' THEN split_part(m.fil_no_fh, '-', array_length(string_to_array(m.fil_no_fh, '-'), 1))
                    ELSE ''
                END AS crl2,
                m.casetype_id,
                m.case_status_id 
            FROM (
                SELECT 
                    t1.diary_no,
                    t1.next_dt,
                    t1.roster_id,
                    t1.judges,
                    t1.mainhead,
                    t1.board_type,
                    t1.subhead,
                    t1.clno,
                    t1.brd_slno,
                    t1.main_supp_flag,
                    t1.tentative_cl_dt
                FROM
                    heardt t1 
                WHERE 
                    t1.next_dt = '" . $data['tdt1'] . "' 
                    AND t1.mainhead = '" . $data['mf'] . "' 
                    AND position('" . $data['jcd'] . "' IN t1.judges) > 0
                    AND (t1.main_supp_flag = 1 OR t1.main_supp_flag = 2)

                UNION

                SELECT 
                    t2.diary_no,
                    t2.next_dt,
                    t2.roster_id,
                    t2.judges,
                    t2.mainhead,
                    t2.board_type,
                    t2.subhead,  
                    t2.clno,
                    t2.brd_slno,
                    t2.main_supp_flag,
                    t2.tentative_cl_dt
                FROM
                    last_heardt t2 
                WHERE 
                    t2.next_dt = '" . $data['tdt1'] . "' 
                    AND t2.mainhead = '" . $data['mf'] . "' 
                    AND position('" . $data['jcd'] . "' IN t2.judges) > 0
                    AND (t2.main_supp_flag = 1 OR t2.main_supp_flag = 2) 
                    AND t2.bench_flag = ''
            ) h 
            INNER JOIN main m 
                ON (
                    h.diary_no = m.diary_no 
                    AND h.next_dt = '" . $data['tdt1'] . "' 
                    AND h.mainhead = '" . $data['mf'] . "' 
                    AND position('" . $data['jcd'] . "' IN h.judges) > 0
                    AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
                ) 
            LEFT JOIN master.subheading s 
                ON s.stagecode = h.subhead AND s.display = 'Y' AND s.listtype = 'M'
            LEFT JOIN cl_printed cl 
                ON (
                    cl.next_dt = h.next_dt 
                    AND cl.m_f = h.mainhead 
                    AND cl.part = h.clno 
                    AND cl.main_supp = h.main_supp_flag 
                    AND cl.roster_id = h.roster_id 
                    AND cl.display = 'Y'
                ) 
            WHERE cl.next_dt IS NOT NULL $whereStatus
            ORDER BY 
                position('" . $data['jcd'] . "' IN h.judges),
                h.brd_slno,
                CASE 
                    WHEN m.conn_key = h.diary_no::text THEN '0000-00-00'
                    ELSE m.fil_dt::text
                END ASC;
        ";

        // echo $sql;

        $query = $this->db->query($sql);

        // Check for results
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return [];
        }
    }

    function getCaseRemarks2()
    {
        $sql = "
            SELECT *
            FROM master.case_remarks_head
            WHERE side = 'D' AND display = 'Y'
            ORDER BY (CASE WHEN sno IN (134, 144, 27, 28, 30, 36) THEN 0 ELSE 1 END) ASC, head ASC
        ";

        // Execute the query
        $query = $this->db->query($sql);

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return [];
        }
    }

    function getCaseRemarks()
    {
        $builder = $this->db->table('master.case_remarks_head');

        // Prepare the query
        $builder->select('*');
        $builder->where('side', 'P');
        $builder->where('display', 'Y');

        // Using a CASE statement for ordering
        $builder->orderBy("(CASE WHEN cat_head_id < 1000 THEN 0 ELSE 1 END)", 'ASC');
        $builder->orderBy('head', 'ASC');

        // Execute the query
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return [];
        }
    }

    function getDisplayUser($usercode = '')
    {
        $query = $this->db->table('master.users')
            ->select('name, jcode, empid')
            ->where('usercode', $usercode)
            ->where('display', 'Y')
            ->get();

        if ($query->getNumRows() > 0) {
            return $query->getRowArray(); // Get the name from the result
        } else {
            return [];
        }
    }

    function getLowerCourtCaseType($diary_no = 0)
    {
        $sql = "SELECT lct_dec_dt, lct_caseno, lct_caseyear, short_description AS type_sname
            FROM lowerct a
            LEFT JOIN master.casetype ct ON ct.casecode = a.lct_casetype AND ct.display = 'Y'
            WHERE a.diary_no = '" . $diary_no . "' AND lw_display = 'Y' AND ct_code = 4
            ORDER BY a.lct_dec_dt";

        // Execute the query
        $query = $this->db->query($sql);

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return [];
        }
    }

    function getDARoaster($roster_id = 0)
    {
        $sql = "
            SELECT roster.id,
                roster.bench_id,
                CONCAT(master_bench.abbr, ' - ', roster_bench.bench_no) AS bnch,
                m_f, 
                roster.session, 
                roster.frm_time, 
                roster.courtno
            FROM master.roster
            INNER JOIN master.roster_bench 
                ON (roster_bench.id = roster.bench_id AND roster.display = 'Y') 
            LEFT JOIN master.master_bench 
                ON (master_bench.id = roster_bench.bench_id AND roster_bench.display = 'Y') 
            WHERE (roster.to_date::text = '0000-00-00' OR (CURRENT_DATE <= roster.to_date))
            AND roster.display = 'Y' 
            AND roster.id = $roster_id
        ";

        // echo $sql;

        $query = $this->db->query($sql);

        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return [];
        }
    }

    function getRoaster($roster_id = 0)
    {
        $sql = "
            SELECT roster.id,
                roster.bench_id,
                CONCAT(master_bench.abbr, ' - ', roster_bench.bench_no) AS bnch,
                m_f, 
                roster.session, 
                roster.frm_time, 
                roster.courtno
            FROM master.roster
            INNER JOIN master.roster_bench 
                ON roster_bench.id = roster.bench_id 
                AND roster.display = 'Y' 
            LEFT JOIN master.master_bench 
                ON master_bench.id = roster_bench.bench_id 
                AND roster_bench.display = 'Y' 
            WHERE roster.display = 'Y' 
            AND roster.id = $roster_id
        ";

        // echo $sql;

        $query = $this->db->query($sql);

        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return [];
        }
    }

    function getDisplayUserName($usercode = '')
    {
        $query = $this->db->table('master.users')
            ->select('name')
            ->where('usercode', $usercode)
            ->where('display', 'Y')
            ->get();

        if ($query->getNumRows() > 0) {
            return $query->getRow()->name; // Get the name from the result
        } else {
            return "";
        }
    }

    function getBarInfoByAOR($aor_code = 0)
    {
        // Use your database connection
        $aor_code = (int) $aor_code; // Ensure $a_code is properly escaped

        $builder = $this->db->table('master.bar');
        $builder->select('bar_id, name');
        $builder->where('aor_code', $aor_code);

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return [];
        }
    }

    function getAORWiseMatters($bar_id = 0)
    {
        $da_section = "SELECT DISTINCT a.diary_no, b.dacode, c.section,
                            COALESCE(d.section_name, x.section_name) AS section_name,
                            short_description,
                            SUBSTRING(b.fil_no FROM 3) AS fil_no,
                            EXTRACT(YEAR FROM b.fil_dt) AS fil_dt,
                            pet_name,
                            res_name
                        FROM advocate a
                        JOIN main b ON a.diary_no = b.diary_no
                        LEFT JOIN master.users c ON c.usercode = b.dacode AND c.display = 'Y'
                        LEFT JOIN master.usersection d ON d.id = c.section AND d.display = 'Y'
                        LEFT JOIN master.casetype e ON e.casecode::text = SUBSTRING(b.fil_no FROM 1 FOR 2) AND e.display = 'Y'
                        LEFT JOIN (
                            SELECT us.section_name, us.id AS sec_id, diary_no, advocate_id
                            FROM (
                                SELECT a.diary_no,
                                    COALESCE(NULLIF(active_casetype_id, 0), casetype_id) AS casetype_id,
                                    COALESCE(NULLIF(active_fil_no, ''), fil_no) AS fil_no,
                                    COALESCE(
                                        NULLIF(active_reg_year, 0),
                                        EXTRACT(YEAR FROM COALESCE(active_fil_dt, fil_dt))
                                    ) AS reg_year,
                                    ref_agency_state_id, ref_agency_code_id, diary_no_rec_date, pet_name, res_name, advocate_id
                                FROM main a
                                JOIN advocate b ON a.diary_no = b.diary_no
                                LEFT JOIN mul_category mc ON a.diary_no = mc.diary_no AND mc.display = 'Y'
                                WHERE b.advocate_id = " . $bar_id . "
                                    AND c_status = 'P'
                                    AND b.display = 'Y'
                                    AND ref_agency_code_id NOT IN (116, 117, 118, 119, 120, 121, 122, 123, 124, 125, 126, 127, 128, 129, 130, 131, 132, 133, 134, 135, 136, 141, 190, 203, 1, 247, 272, 322, 140, 165, 182, 163, 156, 107, 189, 217, 155, 161, 271)
                                    AND submaster_id NOT IN (118, 119, 120, 121, 122, 123, 124, 125, 126, 127, 128, 129, 130, 131, 132, 318, 332)
                            ) a
                            LEFT JOIN master.da_case_distribution b ON b.case_type = a.casetype_id 
                                AND ref_agency_state_id = state 
                                AND reg_year BETWEEN b.case_f_yr AND b.case_t_yr
                            LEFT JOIN master.users u ON b.dacode = u.usercode AND u.display = 'Y'
                            LEFT JOIN master.usersection us ON u.section = us.id AND us.display = 'Y'
                            WHERE b.display = 'Y'
                            GROUP BY diary_no, us.section_name, us.id, a.advocate_id
                            UNION
                            SELECT us.section_name, us.id AS sec_id, diary_no, advocate_id
                            FROM (
                                SELECT a.diary_no,
                                    COALESCE(NULLIF(active_casetype_id, 0), casetype_id) AS casetype_id,
                                    COALESCE(NULLIF(active_fil_no, ''), fil_no) AS fil_no,
                                    COALESCE(
                                        NULLIF(active_reg_year, 0),
                                        EXTRACT(YEAR FROM COALESCE(active_fil_dt, fil_dt))
                                    ) AS reg_year,
                                    ref_agency_state_id, ref_agency_code_id, diary_no_rec_date, pet_name, res_name, advocate_id
                                FROM main a
                                JOIN advocate b ON a.diary_no = b.diary_no
                                WHERE b.advocate_id = " . $bar_id . "
                                    AND b.display = 'Y'
                                    AND c_status = 'P'
                                    AND ref_agency_code_id IN (116, 117, 118, 119, 120, 121, 122, 123, 124, 125, 126, 127, 128, 129, 130, 131, 132, 133, 134, 135, 136, 141, 190, 203, 1, 247, 272, 322, 140, 165, 182, 163, 156, 107, 189, 217, 155, 161, 271)
                            ) a
                            LEFT JOIN master.da_case_distribution_tri b ON b.case_type = a.casetype_id 
                                AND ref_agency_state_id = state 
                                AND reg_year BETWEEN b.case_f_yr AND b.case_t_yr
                            LEFT JOIN master.users u ON b.dacode = u.usercode AND u.display = 'Y'
                            LEFT JOIN master.usersection us ON u.section = us.id AND us.display = 'Y'
                            WHERE b.display = 'Y'
                            GROUP BY diary_no, us.section_name, us.id, a.advocate_id
                            UNION
                            SELECT us.section_name, us.id AS sec_id, diary_no, advocate_id
                            FROM (
                                SELECT a.diary_no,
                                    COALESCE(NULLIF(active_casetype_id, 0), casetype_id) AS casetype_id,
                                    COALESCE(NULLIF(active_fil_no, ''), fil_no) AS fil_no,
                                    COALESCE(
                                        NULLIF(active_reg_year, 0),
                                        EXTRACT(YEAR FROM COALESCE(active_fil_dt, fil_dt))
                                    ) AS reg_year,
                                    ref_agency_state_id, ref_agency_code_id, diary_no_rec_date, pet_name, res_name, advocate_id
                                FROM main a
                                JOIN advocate b ON a.diary_no = b.diary_no
                                LEFT JOIN mul_category mc ON a.diary_no = mc.diary_no AND mc.display = 'Y'
                                WHERE b.advocate_id = " . $bar_id . "
                                    AND b.display = 'Y'
                                    AND c_status = 'P'
                                    AND submaster_id IN (118, 119, 120, 121, 122, 123, 124, 125, 126, 127, 128, 129, 130, 131, 132, 318, 332)
                            ) a
                            LEFT JOIN master.da_case_distribution_tri b ON b.case_type = a.casetype_id 
                                AND reg_year BETWEEN b.case_f_yr AND b.case_t_yr
                            LEFT JOIN master.users u ON b.dacode = u.usercode AND u.display = 'Y'
                            LEFT JOIN master.usersection us ON u.section = us.id AND us.display = 'Y'
                            WHERE b.display = 'Y'
                            GROUP BY diary_no, us.section_name, us.id, a.advocate_id
                        ) x ON x.diary_no = a.diary_no AND x.advocate_id = a.advocate_id
                        WHERE a.advocate_id = " . $bar_id . " AND a.display = 'Y'
                            AND c_status = 'P'
                        ORDER BY section_name
        ";

        $query = $this->db->query($da_section);

        // Check for results
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();;
        } else {
            return [];
        }
    }

    function loose_document_da_detail($first_date, $to_date, $user)
    {
        $da_section = "Select section,usertype from master.users where usercode = $user";
        $query1 = $this->db->query($da_section);
        $result = $query1->getRowArray();

        if (! $result) {
            return false; // Return an empty array if no user found
        }

        $sec = trim($result['section']);
        $type = trim($result['usertype']);

        if (($type == 14) || ($type == 12) || ($type == 6) || ($type == 9)) {

            // echo   "login as branch officer";

            $mcode = "select group_concat(usercode)  as x from master.users where section = $sec";
            $query2 = $this->db->query($mcode);
            $result = $query2->getRowArray();

            if ($result) {
                $users_da = trim($result['x']);
            }

            // branch officer login query
            $sql = "
                SELECT 
                    CONCAT(CAST(LEFT(dc.diary_no::TEXT,LENGTH(dc.diary_no::TEXT)-4) AS TEXT), ' / ', CAST(RIGHT(dc.diary_no::TEXT, 4) AS TEXT)) AS diary_no,
                    m.reg_no_display,
                    concat(pet_name, ' Vs ', res_name) AS causetitle,
                    docdesc,
                    concat(docnum, '/', docyear) AS document,
                    filedby,
                    u_dak.name AS dak_name,
                    u_dak.empid AS dak_empid,
                    dc.ent_dt,
                    da.name AS da_name,
                    da.empid AS da_empid,
                    us.section_name AS da_section,
                    date(h.next_dt) AS next_date,
                    (h.next_dt::date - CURRENT_DATE) AS diff
                FROM 
                    public.docdetails dc
                LEFT JOIN 
                    master.docmaster dm ON dc.doccode = dm.doccode AND dc.doccode1 = dm.doccode1
                LEFT JOIN 
                    public.main m ON dc.diary_no = m.diary_no
                LEFT JOIN 
                    master.users u_dak ON u_dak.usercode = dc.usercode
                LEFT JOIN 
                    master.users da ON da.usercode = m.dacode
                LEFT JOIN 
                    master.usersection us ON us.id = da.section
                LEFT JOIN 
                    public.heardt h ON h.diary_no = dc.diary_no
                WHERE 
                    dc.ent_dt BETWEEN '$first_date' AND '$to_date'
                    AND da.usercode IN ($users_da)
                    AND dm.display = 'Y'
                    AND dc.display = 'Y'
                ORDER BY 
                    da_name, document
            ";
        } else {
            // echo "loggod on as a dealing assistant";

            $sql = "
                SELECT 
                    CONCAT(CAST(LEFT(dc.diary_no::TEXT,LENGTH(dc.diary_no::TEXT)-4) AS TEXT), ' / ', CAST(RIGHT(dc.diary_no::TEXT, 4) AS TEXT)) AS diary_no,
                    m.reg_no_display,
                    concat(pet_name, ' Vs ', res_name) AS causetitle,
                    docdesc,
                    concat(docnum, '/', docyear) AS document,
                    filedby,
                    u_dak.name AS dak_name,
                    u_dak.empid AS dak_empid,
                    dc.ent_dt,
                    da.name AS da_name,
                    da.empid AS da_empid,
                    us.section_name AS da_section,
                    date(h.next_dt) AS next_date,
                    (h.next_dt::date - CURRENT_DATE) AS diff
                FROM 
                    public.docdetails dc
                LEFT JOIN 
                    master.docmaster dm ON dc.doccode = dm.doccode AND dc.doccode1 = dm.doccode1
                LEFT JOIN 
                    public.main m ON dc.diary_no = m.diary_no
                LEFT JOIN 
                    master.users u_dak ON u_dak.usercode = dc.usercode
                LEFT JOIN 
                    master.users da ON da.usercode = m.dacode
                LEFT JOIN 
                    master.usersection us ON us.id = da.section
                LEFT JOIN 
                    public.heardt h ON h.diary_no = dc.diary_no
                WHERE 
                    dc.ent_dt BETWEEN '$first_date' AND '$to_date'
                    AND da.usercode = $user
                    AND dm.display = 'Y'
                    AND dc.display = 'Y'
                ORDER BY document
            ";
        }

        //  echo $sql;
        //    die();
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->getResultArray();
    }

    function get_loosedocuments($fromDate, $toDate, $usercode)
    {
        // Query to fetch user details
        $sql_sec = "SELECT * FROM master.users WHERE usercode = $usercode";
       
        $rs = $this->db->query($sql_sec);
        $r = $rs->getRowArray();
        if (!$r) {
            return []; // Return an empty array if no user found
        }

        // Define the common parts of the query
        $commonSelect = "
            CONCAT(CAST(LEFT(dc.diary_no::TEXT,LENGTH(dc.diary_no::TEXT)-4) AS TEXT), ' / ', CAST(RIGHT(dc.diary_no::TEXT, 4) AS TEXT)) AS diary_no,
            da.section,
            m.reg_no_display,
            CONCAT(pet_name, ' Vs ', res_name) AS causetitle,
            docdesc,
            CONCAT(dc.docnum, '/', dc.docyear) AS document,
            filedby,
            u_dak.name AS dak_name,
            u_dak.empid AS dak_empid,
            TO_CHAR(dc.ent_dt, 'DD-MM-YYYY HH24:MI:SS') AS ent_dt,
            da.name AS da_name,
            da.empid AS da_empid,
            us.section_name AS da_section,
            TO_CHAR(h.next_dt::date, 'DD-MM-YYYY') AS next_date,
            (h.next_dt - CURRENT_DATE) AS diff,
            '' AS pdf_name,
            dc.is_efiled,
            ed.efiling_no
        ";

        $commonGroupBy = ", DC.DIARY_NO::TEXT,DA.SECTION,M.REG_NO_DISPLAY,m.pet_name,m.res_name,dm.docdesc,dc.filedby,u_dak.name,u_dak.empid,dc.ent_dt,da.name,da.empid
    ,US.SECTION_NAME,h.next_dt,dc.is_efiled,ed.efiling_no";

        // echo $r['section']." | ".$fromDate." | ".$toDate;

        if (in_array($r['section'], ['19', '71', '30', '77'])) {
            $sql = "SELECT $commonSelect
                    FROM public.docdetails dc
                    LEFT JOIN master.docmaster dm ON dc.doccode = dm.doccode AND dc.doccode1 = dm.doccode1
                    LEFT JOIN public.main m ON dc.diary_no = m.diary_no
                    LEFT JOIN public.efiled_docs ed ON ed.diary_no = m.diary_no AND ed.display = 'Y' AND ed.docnum = dc.docnum AND ed.docyear = dc.docyear
                    LEFT JOIN master.users u_dak ON u_dak.usercode = dc.usercode
                    LEFT JOIN master.users da ON da.usercode = m.dacode
                    LEFT JOIN master.usersection us ON us.id = da.section
                    LEFT JOIN public.heardt_webuse h ON h.diary_no = dc.diary_no
                    WHERE dc.ent_dt BETWEEN '$fromDate' AND '$toDate'
                    AND (dm.display = 'Y' OR dm.display = 'E')
                    AND dc.display = 'Y'
                    GROUP BY dc.docnum, dc.docyear $commonGroupBy
                    ORDER BY ent_dt, da_name, document Limit 1000";

            $query = $this->db->query($sql);
        } elseif ($r['section'] != "19" && $r['section'] != "71" && $r['section'] != "30" && $r['section'] != "77" && $r['usertype'] == 14) {
            $sql = "SELECT $commonSelect
                    FROM public.docdetails dc
                    LEFT JOIN master.docmaster dm ON dc.doccode = dm.doccode AND dc.doccode1 = dm.doccode1
                    LEFT JOIN public.main m ON dc.diary_no = m.diary_no
                    LEFT JOIN public.efiled_docs ed ON ed.diary_no = m.diary_no AND ed.display = 'Y' AND ed.docnum = dc.docnum AND ed.docyear = dc.docyear
                    LEFT JOIN master.users u_dak ON u_dak.usercode = dc.usercode
                    LEFT JOIN master.users da ON da.usercode = m.dacode
                    LEFT JOIN master.usersection us ON us.id = da.section
                    LEFT JOIN public.heardt_webuse h ON h.diary_no = dc.diary_no
                    WHERE dc.ent_dt BETWEEN '$fromDate' AND '$toDate'
                    AND (dm.display = 'Y' OR dm.display = 'E')
                    AND dc.display = 'Y'
                    AND da.section IN (" . $r['section'] . ")
                    GROUP BY dc.docnum, dc.docyear $commonGroupBy
                    ORDER BY ent_dt, da_name, document  Limit 1000";
                   
            $query = $this->db->query($sql);
        } elseif ($r['section'] != "19" && $r['section'] != "71" && $r['section'] != "30" && $r['section'] != "77" && ($r['usertype'] == "50" || $r['usertype'] == "51" || $r['usertype'] == "17")) {
            $sql = "SELECT $commonSelect
                    FROM public.docdetails dc
                    LEFT JOIN master.docmaster dm ON dc.doccode = dm.doccode AND dc.doccode1 = dm.doccode1
                    LEFT JOIN public.main m ON dc.diary_no = m.diary_no
                    LEFT JOIN public.efiled_docs ed ON ed.diary_no = m.diary_no AND ed.display = 'Y' AND ed.docnum = dc.docnum AND ed.docyear = dc.docyear
                    LEFT JOIN master.users u_dak ON u_dak.usercode = dc.usercode
                    LEFT JOIN master.users da ON da.usercode = m.dacode
                    LEFT JOIN master.usersection us ON us.id = da.section
                    LEFT JOIN public.heardt_webuse h ON h.diary_no = dc.diary_no
                    WHERE dc.ent_dt BETWEEN '$fromDate' AND '$toDate'
                    AND (dm.display = 'Y' OR dm.display = 'E')
                    AND dc.display = 'Y'
                    AND da.usercode IN (" . $r['usercode'] . ")
                    GROUP BY dc.docnum, dc.docyear $commonGroupBy
                    ORDER BY ent_dt, da_name, document  Limit 1000";
            $query = $this->db->query($sql);
        } elseif ($r['section'] == '77') {
            $sql1 = "SELECT $commonSelect
                    FROM public.docdetails dc
                    LEFT JOIN master.docmaster dm ON dc.doccode = dm.doccode AND dc.doccode1 = dm.doccode1
                    LEFT JOIN public.main m ON dc.diary_no = m.diary_no
                    LEFT JOIN master.users u_dak ON u_dak.usercode = dc.usercode
                    LEFT JOIN master.users da ON da.usercode = m.dacode
                    LEFT JOIN master.usersection us ON us.id = da.section
                    WHERE dc.ent_dt BETWEEN '$fromDate' AND '$toDate'
                    AND pdf_name IS NOT NULL
                    AND (dm.display = 'Y' OR dm.display = 'E')
                    AND dc.display = 'Y'
                    GROUP BY docnum, docyear $commonGroupBy
                    ORDER BY ent_dt  Limit 1000";
            $query1 = $this->db->query($sql1);

            if (!$query1) {
                echo "issue in query";
                exit();
            }

            $y = [];
            foreach ($query1->getResultArray() as $res_filter) {
                $dn = $res_filter['dn'];

                // Check if the matter is fresh or not
                $sql_da = "SELECT CASE
                                WHEN first_listing_date($dn) IS NULL THEN 'not listed'
                                WHEN LLDT($dn) < CURRENT_DATE THEN 'listed once'
                                ELSE 'fresh'
                            END AS listed_flag";
                $result_da = $this->db->query($sql_da);

                foreach ($result_da->getResultArray() as $rs_da) {
                    $listed_flag = $rs_da['listed_flag'];

                    if ($listed_flag == 'fresh' || $listed_flag == 'not listed') {
                        $sql = "SELECT d_to_empid AS dacode, u.name, us.section_name, u.usercode
                                FROM (SELECT d_to_empid, diary_no
                                    FROM fil_trap
                                    WHERE diary_no = $dn
                                    UNION
                                    SELECT d_to_empid, diary_no
                                    FROM fil_trap_his
                                    WHERE diary_no = $dn AND remarks LIKE '%IB-Ex%') f
                                JOIN master.users u ON f.d_to_empid = u.empid
                                JOIN master.usersection us ON u.section = us.id
                                WHERE f.diary_no = $dn
                                AND us.id = 77";

                        $query1 = $this->db->query($sql);

                        foreach ($query1->getResultArray() as $chk) {
                            if ($chk['usercode'] == $usercode) {
                                $y[] = $res_filter;
                            }
                        }
                    }
                }
            }
            return $y;
        } else {
            // Handle other cases or return empty array
            return [];
        }

        // Return the result
        return $query->getResultArray();
        //echo $this->db->getLastQuery();die;
    }

    function da_rog_report($section = 0)
    {
        $condition = $section != '0' ? " AND section = $section" : "";

        $sql = "
            SELECT empid, dacode, name, type_name, section_name,
                COUNT(DISTINCT total) AS total,
                COUNT(DISTINCT red) AS red,
                COUNT(DISTINCT orange) AS orange,
                COUNT(DISTINCT green) AS green,
                COUNT(DISTINCT yellow) AS yellow
            FROM (
                SELECT empid, dacode, name, type_name, section_name, m.diary_no AS total,
                    CASE
                        WHEN (h.tentative_cl_dt IS NOT NULL AND h.tentative_cl_dt::text != '0000-00-00' AND (CURRENT_DATE - h.tentative_cl_dt::DATE) < 2)
                                AND NOT ((h.mainhead = 'M' AND s.listtype = 'M' AND s.listtype IS NOT NULL AND s.display = 'Y' AND s.display IS NOT NULL)
                                        OR (h.mainhead = 'S' AND s.listtype = 'S' AND s.listtype IS NOT NULL AND s.display = 'Y' AND s.display IS NOT NULL)
                                        OR (main_supp_flag = 0 AND clno = 0 AND h.brd_slno = 0 AND (judges = '' OR judges::int = 0) AND roster_id = 0)
                                        OR (h.next_dt IS NOT NULL AND h.next_dt >= CURRENT_DATE))
                                AND (lastorder NOT LIKE '%Not Reached%' AND lastorder NOT LIKE '%Case Not Receive%' AND lastorder NOT LIKE '%Heard & Reserved%' OR lastorder IS NULL)
                                AND (head_code != '5' OR head_code IS NULL)
                                AND m.diary_no NOT IN (SELECT diary_no FROM public.heardt WHERE main_supp_flag = 3 AND usercode IN (559, 146, 744, 747, 469, 1485, 742, 1486, 935, 757, 49, 762)
                                                    UNION
                                                    SELECT fil_no AS diary_no FROM public.rgo_default WHERE remove_def != 'Y')
                        THEN m.diary_no
                    END AS red,
                    CASE
                        WHEN (CURRENT_DATE - h.tentative_cl_dt) > 1
                                AND NOT ((h.mainhead = 'M' AND s.listtype = 'M' AND s.listtype IS NOT NULL AND s.display = 'Y' AND s.display IS NOT NULL)
                                        OR (h.mainhead = 'S' AND s.listtype = 'S' AND s.listtype IS NOT NULL AND s.display = 'Y' AND s.display IS NOT NULL)
                                        OR (main_supp_flag = 0 AND clno = 0 AND h.brd_slno = 0 AND (judges = '' OR judges::int = 0) AND roster_id = 0)
                                        OR (h.next_dt IS NOT NULL AND h.next_dt >= CURRENT_DATE))
                                AND (lastorder NOT LIKE '%Not Reached%' AND lastorder NOT LIKE '%Case Not Receive%' AND lastorder NOT LIKE '%Heard & Reserved%' OR lastorder IS NULL)
                                AND (head_code != '5' OR head_code IS NULL)
                                AND m.diary_no NOT IN (SELECT diary_no FROM public.heardt WHERE main_supp_flag = 3 AND usercode IN (559, 146, 744, 747, 469, 1485, 742, 1486, 935, 757, 49, 762)
                                                    UNION
                                                    SELECT fil_no AS diary_no FROM public.rgo_default WHERE remove_def != 'Y')
                        THEN m.diary_no
                    END AS orange,
                    CASE
                        WHEN (h.mainhead = 'M' AND s.listtype = 'M' AND s.listtype IS NOT NULL AND s.display = 'Y' AND s.display IS NOT NULL)
                                OR (h.mainhead = 'S' AND s.listtype = 'S' AND s.listtype IS NOT NULL AND s.display = 'Y' AND s.display IS NOT NULL)
                                OR (main_supp_flag = 0 AND clno = 0 AND h.brd_slno = 0 AND (judges = '' OR judges::int = 0) AND roster_id = 0)
                                OR (h.next_dt IS NOT NULL AND h.next_dt::date >= CURRENT_DATE)
                                OR (lastorder LIKE '%Not Reached%' OR lastorder LIKE '%Case Not Receive%')
                                OR head_code = '5'
                                AND m.diary_no NOT IN (SELECT diary_no FROM public.heardt WHERE main_supp_flag = 3 AND usercode IN (559, 146, 744, 747, 469, 1485, 742, 1486, 935, 757, 49, 762)
                                                    UNION
                                                    SELECT fil_no AS diary_no FROM public.rgo_default WHERE remove_def != 'Y')
                        THEN m.diary_no
                    END AS green,
                    CASE
                        WHEN (h.main_supp_flag = 3 AND h.usercode IN (559, 146, 744, 747, 469, 1485, 742, 1486, 935, 757, 49, 762))
                                OR (rd.remove_def != 'Y')
                                OR (lastorder LIKE '%Heard & Reserved%')
                        THEN m.diary_no
                    END AS yellow
                FROM public.main m
                INNER JOIN master.casetype c ON c.casecode = COALESCE(NULLIF(m.active_casetype_id, 0), m.casetype_id)
                LEFT JOIN public.heardt h ON m.diary_no = h.diary_no
                LEFT JOIN master.users u ON m.dacode = u.usercode
                LEFT JOIN master.usertype ut ON ut.id = u.usertype
                LEFT JOIN public.rgo_default rd ON m.diary_no = rd.fil_no
                LEFT JOIN master.usersection b ON b.id = u.section
                LEFT JOIN master.subheading s ON h.subhead = s.stagecode
                WHERE c_status = 'P'
            $condition) a
            GROUP BY empid, dacode, name, type_name, section_name
            ORDER BY section_name, type_name DESC, total
        ";

        // echo $sql;

        $query = $this->db->query($sql);

        return $query->getResultArray();
    }

    function da_rog_cases($category, $dacode)
    {
        // Prepare the category condition based on the given category
        switch ($category) {
            case 't':
                $condition = "";
                break;
            case 'r':
                $condition = " AND (
                        (tentative_cl_dt::text != '0000-00-00' AND h.tentative_cl_dt IS NOT NULL AND h.tentative_cl_dt::date - CURRENT_DATE < 2)
                        OR TRUE
                    )
                    AND (
                        (h.mainhead = 'M' AND s.listtype = 'M' AND s.listtype IS NOT NULL AND s.display = 'Y')
                        OR (h.mainhead = 'S' AND s.listtype = 'S' AND s.listtype IS NOT NULL AND s.display = 'Y')
                        OR (h.mainhead IS DISTINCT FROM 'M' AND h.mainhead IS DISTINCT FROM 'S')
                    )
                    AND (
                        (main_supp_flag = 0 AND h.clno = 0 AND brd_slno = 0 AND (judges::text = '' OR judges::int = 0) AND roster_id = 0) 
                        OR (h.next_dt is not null AND h.next_dt::date >= CURRENT_DATE)
                    )
                    AND (
                        lastorder NOT LIKE '%Not Reached%' 
                        AND lastorder NOT LIKE '%Case Not Receive%' 
                        AND lastorder NOT LIKE '%Heard & Reserved%' 
                        OR lastorder IS NULL
                    )
                    AND (head_code::text != '5' OR head_code IS NULL)
                    AND m.diary_no NOT IN (
                        SELECT diary_no FROM public.heardt WHERE main_supp_flag = 3 AND usercode IN (559, 146, 744, 747, 469, 1485, 742, 1486, 935, 757, 49, 762)
                        UNION
                        SELECT fil_no AS diary_no FROM public.rgo_default WHERE remove_def != 'Y'
                    )";
                break;
            case 'o':
                $condition = " AND h.tentative_cl_dt::date - CURRENT_DATE > 1
                    AND (
                        (h.mainhead = 'M' AND s.listtype = 'M' AND s.listtype IS NOT NULL AND s.display = 'Y')
                        OR (h.mainhead = 'S' AND s.listtype = 'S' AND s.listtype IS NOT NULL AND s.display = 'Y')
                        OR (h.mainhead IS DISTINCT FROM 'M' AND h.mainhead IS DISTINCT FROM 'S')
                    )
                    AND (
                        (main_supp_flag = 0 AND h.clno = 0 AND brd_slno = 0 AND (judges::text = '' OR judges = '0') AND roster_id = 0) 
                        OR (h.next_dt is not null AND h.next_dt::date >= CURRENT_DATE)
                    )
                    AND (
                        lastorder NOT LIKE '%Not Reached%' 
                        AND lastorder NOT LIKE '%Case Not Receive%' 
                        AND lastorder NOT LIKE '%Heard & Reserved%' 
                        OR lastorder IS NULL
                    )
                    AND (head_code::text != '5' OR head_code IS NULL)
                    AND m.diary_no NOT IN (
                        SELECT diary_no FROM public.heardt WHERE main_supp_flag = 3 AND usercode IN (559, 146, 744, 747, 469, 1485, 742, 1486, 935, 757, 49, 762)
                        UNION
                        SELECT fil_no AS diary_no FROM public.rgo_default WHERE remove_def != 'Y'
                    )";
                break;
            case 'g':
                $condition = " AND (
                    (h.mainhead = 'M' AND s.listtype = 'M' AND s.listtype IS NOT NULL AND s.display = 'Y')
                    OR (h.mainhead = 'S' AND s.listtype = 'S' AND s.listtype IS NOT NULL AND s.display = 'Y')
                    OR (h.mainhead IS DISTINCT FROM 'M' AND h.mainhead IS DISTINCT FROM 'S')
                ) 
                AND (
                    (main_supp_flag = 0 AND h.clno = 0 AND brd_slno = 0 AND (judges = '' OR judges::int = 0) AND roster_id = 0) 
                    OR (h.next_dt IS NOT NULL AND h.next_dt >= CURRENT_DATE)
                    OR (lastorder LIKE '%Not Reached%' OR lastorder LIKE '%Case Not Receive%' OR lastorder LIKE '%Heard & Reserved%') 
                    OR head_code::text = '5'
                )
                AND m.diary_no NOT IN (
                    SELECT diary_no FROM public.heardt WHERE main_supp_flag = 3 AND usercode IN (559, 146, 744, 747, 469, 1485, 742, 1486, 935, 757, 49, 762)
                    UNION
                    SELECT fil_no AS diary_no FROM public.rgo_default WHERE remove_def != 'Y'
                )";
                break;
            case 'y':
                $condition = " AND (
                    (h.main_supp_flag = 3 AND h.usercode IN (559, 146, 744, 747, 469, 1485, 742, 1486, 935, 757, 49, 762))
                    OR rd.remove_def != 'Y'
                    OR lastorder LIKE '%Heard & Reserved%'
                )";
                break;
            case 'd':
                $condition = " AND m.diary_no NOT IN (
                    SELECT m.diary_no FROM public.main m 
                    LEFT JOIN public.heardt h ON m.diary_no = h.diary_no
                    LEFT JOIN master.subheading s ON h.subhead = s.stagecode
                    WHERE c_status = 'P' AND dacode = $dacode 
                    AND (
                        (tentative_cl_dt is not null AND h.tentative_cl_dt IS NOT NULL AND h.tentative_cl_dt::date - CURRENT_DATE < 2)
                        OR TRUE
                    )
                    AND (
                        (h.mainhead = 'M' AND s.listtype = 'M' AND s.listtype IS NOT NULL AND s.display = 'Y')
                        OR (h.mainhead = 'S' AND s.listtype = 'S' AND s.listtype IS NOT NULL AND s.display = 'Y')
                        OR (h.mainhead IS DISTINCT FROM 'M' AND h.mainhead IS DISTINCT FROM 'S')
                    )
                    AND (
                        (main_supp_flag = 0 AND h.clno = 0 AND brd_slno = 0 AND (judges::text = '' OR judges = '0') AND roster_id = 0) 
                        OR (h.next_dt is not null AND h.next_dt::date >= CURRENT_DATE)
                    )
                    AND (
                        lastorder NOT LIKE '%Not Reached%' 
                        AND lastorder NOT LIKE '%Case Not Receive%' 
                        AND lastorder NOT LIKE '%Heard & Reserved%' 
                        OR lastorder IS NULL
                    )
                    AND (
                        (head_code::text != '5' OR head_code IS NULL)                    
                    )
                    AND m.diary_no NOT IN (
                        SELECT diary_no FROM public.heardt 
                        WHERE main_supp_flag = 3 
                        AND usercode IN (559, 146, 744, 747, 469, 1485, 742, 1486, 935, 757, 49, 762)
                        UNION
                        SELECT fil_no AS diary_no FROM public.rgo_default 
                        WHERE remove_def != 'Y'
                    )
                    UNION ALL
                    SELECT m.diary_no FROM public.main m 
                    LEFT JOIN public.heardt h ON m.diary_no = h.diary_no
                    LEFT JOIN master.subheading s ON h.subhead = s.stagecode
                    WHERE c_status = 'P' AND dacode = $dacode 
                    AND h.tentative_cl_dt IS NOT NULL 
                    AND h.tentative_cl_dt::date - CURRENT_DATE > 1 
                    AND (
                        (h.mainhead = 'M' AND s.listtype = 'M' AND s.listtype IS NOT NULL AND s.display = 'Y')
                        OR (h.mainhead = 'S' AND s.listtype = 'S' AND s.listtype IS NOT NULL AND s.display = 'Y')
                        OR (h.mainhead IS DISTINCT FROM 'M' AND h.mainhead IS DISTINCT FROM 'S')
                    )
                    AND (
                        (main_supp_flag = 0 AND h.clno = 0 AND brd_slno = 0 AND (judges::text = '' OR judges = '0') AND roster_id = 0)
                        OR (h.next_dt is not null AND h.next_dt >= CURRENT_DATE)
                    )
                    AND (
                        lastorder NOT LIKE '%Not Reached%' 
                        AND lastorder NOT LIKE '%Case Not Receive%' 
                        AND lastorder NOT LIKE '%Heard & Reserved%' 
                        OR lastorder IS NULL
                    )
                    AND (head_code::text != '5' OR head_code IS NULL)
                    AND m.diary_no NOT IN (
                        SELECT diary_no FROM public.heardt 
                        WHERE main_supp_flag = 3 
                        AND usercode IN (559, 146, 744, 747, 469, 1485, 742, 1486, 935, 757, 49, 762)
                        UNION
                        SELECT fil_no AS diary_no FROM public.rgo_default 
                        WHERE remove_def != 'Y'
                    )
                )";
                break;
        }

        // Build the query

        // Assuming you have a CodeIgniter 4 database connection $db
        $query = "
        SELECT DISTINCT 
            active_fil_no,
            active_reg_year,
            m.diary_no, 
            tentative_section(m.diary_no::bigint) AS Section,
            reg_no_display,
            pet_name,
            res_name,
            state.name,
            CASE 
                WHEN m.mf_active = 'M' OR (m.mf_active = 'F' AND crh.head = '24') 
                THEN tentative_cl_dt 
            END AS tentative,
            h.next_dt AS next,
            CASE 
                WHEN h.board_type = 'J' THEN 'Court'
                WHEN h.board_type = 'C' THEN 'Chamber'
                WHEN h.board_type = 'R' THEN 'Registrar'
            END AS board_type,
            STRING_AGG(DISTINCT CONCAT(crh.head, ' ', crm.head_content), ',') AS Rmrk_Disp,
            CAST(SUBSTRING(active_fil_no FROM 1 FOR 2) AS INTEGER) AS part1,
            CAST(SUBSTRING(active_fil_no FROM 4 FOR 6) AS INTEGER) AS part2
        FROM public.main m
        INNER JOIN master.casetype c 
            ON c.casecode = COALESCE(m.active_casetype_id, m.casetype_id)
        LEFT JOIN public.heardt h 
            ON m.diary_no = h.diary_no
        LEFT JOIN master.state state 
            ON m.ref_agency_state_id = state.id_no
        LEFT JOIN master.subheading s 
            ON h.subhead = s.stagecode
        LEFT JOIN public.rgo_default rd 
            ON m.diary_no = rd.fil_no
        LEFT JOIN public.case_remarks_multiple crm 
            ON crm.diary_no::TEXT = m.diary_no::TEXT 
            AND crm.cl_date = (SELECT MAX(cl_date) FROM public.case_remarks_multiple WHERE diary_no::TEXT = m.diary_no::TEXT)
        LEFT JOIN master.case_remarks_head crh 
            ON crh.sno = crm.r_head 
            AND (crh.display = 'Y' OR crh.display IS NULL)
        WHERE c_status = 'P' 
            AND dacode = $dacode
            $condition
        GROUP BY m.diary_no, active_fil_no, reg_no_display, pet_name, res_name, state.name, tentative_cl_dt, h.next_dt, h.board_type, crh.head
        ORDER BY 
            Section,
            active_reg_year,
            part1,
            part2;
        ";

        //echo $query; die;
        // Prepare the query
        $builder = $this->db->query($query);
        // echo $this->db->last_query(); die;

        // Execute the query
        return $builder->getResultArray();
    }

    public function show_da_wise_report($emp_id)
    {
        $sql = "
        SELECT empid, dacode, name, type_name, section_name,
            COUNT(DISTINCT total) AS total,
            COUNT(DISTINCT red) AS red,
            COUNT(DISTINCT orange) AS orange,
            COUNT(DISTINCT green) AS green,
            COUNT(DISTINCT yellow) AS yellow
        FROM (
            SELECT empid, dacode, name, type_name, section_name, m.diary_no AS total,
                CASE 
                    WHEN h.tentative_cl_dt::text != '0000-00-00' AND (h.tentative_cl_dt::date - CURRENT_DATE) < 2
                        AND NOT ((
                            CASE 
                                WHEN h.mainhead = 'M' THEN s.listtype = 'M' AND s.listtype IS NOT NULL AND s.display = 'Y'
                                WHEN h.mainhead = 'S' THEN s.listtype = 'S' AND s.listtype IS NOT NULL AND s.display = 'Y'
                                ELSE TRUE
                            END
                            )
                            AND (main_supp_flag = 0 AND h.clno = 0 AND h.brd_slno = 0 AND (judges = '' OR judges = '0') AND roster_id = 0)
                            OR (h.next_dt::text != '0000-00-00' AND h.next_dt::date >= CURRENT_DATE)
                        ) 
                        AND (lastorder NOT LIKE '%Not Reached%' AND lastorder NOT LIKE '%Case Not Receive%' AND lastorder NOT LIKE '%Heard & Reserved%' OR lastorder IS NULL)
                        AND (head_code != '5' OR head_code IS NULL) 
                        AND m.diary_no NOT IN (
                            SELECT diary_no FROM public.heardt WHERE main_supp_flag = 3 AND usercode IN (559,146,744,747,469,1485,742,1486,935,757,49,762)
                            UNION
                            SELECT fil_no AS diary_no FROM public.rgo_default WHERE remove_def != 'Y'
                        ) 
                        THEN m.diary_no 
                END AS red,
                CASE 
                    WHEN (h.tentative_cl_dt::date - CURRENT_DATE) > 1
                        AND NOT ((
                            CASE 
                                WHEN h.mainhead = 'M' THEN s.listtype = 'M' AND s.listtype IS NOT NULL AND s.display = 'Y'
                                WHEN h.mainhead = 'S' THEN s.listtype = 'S' AND s.listtype IS NOT NULL AND s.display = 'Y'
                                ELSE TRUE
                            END)
                            AND (main_supp_flag = 0 AND h.clno = 0 AND h.brd_slno = 0 AND (judges = '' OR judges = '0') AND roster_id = 0)
                            OR (h.next_dt::text != '0000-00-00' AND h.next_dt::date >= CURRENT_DATE)
                        ) 
                        AND (lastorder NOT LIKE '%Not Reached%' AND lastorder NOT LIKE '%Case Not Receive%' AND lastorder NOT LIKE '%Heard & Reserved%' OR lastorder IS NULL)
                        AND (head_code != '5' OR head_code IS NULL)
                        AND m.diary_no NOT IN (
                            SELECT diary_no FROM public.heardt WHERE main_supp_flag = 3 AND usercode IN (559,146,744,747,469,1485,742,1486,935,757,49,762)
                            UNION
                            SELECT fil_no AS diary_no FROM public.rgo_default WHERE remove_def != 'Y'
                        )
                    THEN m.diary_no 
                END AS orange,
                CASE 
                    WHEN (
                        CASE 
                            WHEN h.mainhead = 'M' THEN s.listtype = 'M' AND s.listtype IS NOT NULL AND s.display = 'Y'
                            WHEN h.mainhead = 'S' THEN s.listtype = 'S' AND s.listtype IS NOT NULL AND s.display = 'Y'
                            ELSE TRUE
                        END
                    ) 
                    AND ((main_supp_flag = 0 AND h.clno = 0 AND h.brd_slno = 0 AND (judges = '' OR judges = '0') AND roster_id = 0)
                        OR (h.next_dt::text != '0000-00-00' AND h.next_dt >= CURRENT_DATE)
                        OR (lastorder LIKE '%Not Reached%' OR lastorder LIKE '%Case Not Receive%' OR lastorder LIKE '%Heard & Reserved%')
                        OR head_code = '5')
                    AND m.diary_no NOT IN (
                        SELECT diary_no FROM public.heardt WHERE main_supp_flag = 3 AND usercode IN (559,146,744,747,469,1485,742,1486,935,757,49,762)
                        UNION
                        SELECT fil_no AS diary_no FROM public.rgo_default WHERE remove_def != 'Y'
                    ) THEN m.diary_no 
                END AS green,
                CASE 
                    WHEN (h.main_supp_flag = 3 AND h.usercode IN (559,146,744,747,469,1485,742,1486,935,757,49,762))
                        OR rd.remove_def != 'Y'
                        OR lastorder LIKE '%Heard & Reserved%'
                    THEN m.diary_no 
                END AS yellow
            FROM public.main m
            INNER JOIN master.casetype c ON c.casecode = COALESCE(m.active_casetype_id, m.casetype_id)
            LEFT JOIN public.rgo_default rd ON m.diary_no = rd.fil_no
            LEFT JOIN public.heardt h ON m.diary_no = h.diary_no
            LEFT JOIN master.users usr ON m.dacode = usr.usercode
            LEFT JOIN master.usertype ut ON ut.id = usr.usertype
            LEFT JOIN master.usersection b ON b.id = usr.section
            LEFT JOIN master.subheading s ON h.subhead = s.stagecode
            LEFT JOIN master.case_remarks_head u ON m.lastorder LIKE '%' || COALESCE(NULLIF(u.pending_text, ''), u.head) || '%'
            WHERE c_status = 'P'
                AND usr.empid = $emp_id
        ) a
        GROUP BY empid, dacode, name, type_name, section_name
        ORDER BY section_name, type_name DESC, total;
        ";

        // echo $sql;

        $builder = $this->db->query($sql);
        
        return $builder->getResultArray();
    }

    public function da_details($dacode)
    {
        // Define the SQL query with a parameter placeholder
        $sql = "
            SELECT name, type_name, usertype, section_name, empid
            FROM master.users usr
            LEFT JOIN master.usersection us ON usr.section = us.id
            LEFT JOIN master.usertype ut ON ut.id = usr.usertype
            WHERE usr.usercode = $dacode
        ";

        // echo $sql;

        // Execute the query with the provided parameter
        $query = $this->db->query($sql);

        // Return the result as an array
        return $query->getResultArray();
    }

    function get_orUplodStatus($onDate, $usercode)
    {

        $sql_da = "
            SELECT 
                usr.name,
                usr.empid,
                COALESCE(STRING_AGG(us.id::text, ','), us.id::text) AS us_id,
                ut.id AS ut_id
            FROM 
                master.users usr
            INNER JOIN 
                master.user_sec_map um ON usr.empid = um.empid AND um.display = 'Y'
            LEFT JOIN 
                master.usersection us ON usr.section = us.id
            LEFT JOIN 
                master.usertype ut ON ut.id = usr.usertype
            WHERE 
                usr.display = 'Y' 
                AND usr.attend = 'P' 
                AND usr.usercode = '" . $usercode . "'
            GROUP BY 
                usr.name, usr.empid, ut.id, us.id;
        ";

        $query2 = $this->db->query($sql_da);
        $result = $query2->getResultArray();
        if (sizeof($result) > 0) {
            $ut_id = $result[0]['ut_id'];
            $us_id = $result[0]['us_id'];
        }
        $cond = "";
        if ($ut_id == 14) {
            $cond = " and us.id=$us_id";
        } else if ($ut_id == 6 or $ut_id == 9 or $ut_id == 4 or $ut_id == 12) {
            $cond = " and us.id in ($us_id)";
        } else if ($ut_id == 1) {
            $cond = "";
        } else if ($ut_id != 14 && $ut_id != 4 && $ut_id != 6 && $ut_id != 9 && $ut_id != 12 && $ut_id != 1) {
            $cond = " and u.usercode=$usercode";
        }

        $sql = "
            SELECT 
                m.diary_no,
                CONCAT(CAST(LEFT(m.diary_no::TEXT,LENGTH(m.diary_no::TEXT)-4) AS TEXT), ' / ', CAST(RIGHT(m.diary_no::TEXT, 4) AS TEXT)) AS d_no,
                CONCAT(pet_name, ' Vs. ', res_name) AS cause_title, 
                (SELECT tentative_section(m.diary_no)) AS user_section,
                m.reg_no_display, 
                u.name AS DA_Name, 
                CASE WHEN o.web_status = 1 THEN 'Upload' ELSE 'Not Upload' END AS web_status, 
                Rt.courtno,
                COALESCE(h.brd_slno::text, 'NA') AS brd_prnt 
            FROM public.main m 
            LEFT JOIN master.users u ON u.usercode = m.dacode AND (u.display = 'Y' OR u.display IS NULL) 
            LEFT JOIN master.usersection us ON us.id = u.section AND (us.display = 'Y' OR us.display IS NULL) 
            LEFT JOIN public.heardt h ON m.diary_no = h.diary_no AND roster_id != 0 AND coram::TEXT != '0' AND clno::TEXT != '0' AND h.next_dt = '$onDate' 
            LEFT JOIN public.office_report_details o ON o.diary_no = h.diary_no AND (o.display = 'Y' OR o.display IS NULL) AND (o.order_dt = '$onDate' OR o.order_dt IS NULL) 
            INNER JOIN master.roster Rt ON Rt.id = h.roster_id 
            INNER JOIN public.cl_printed cl ON (cl.next_dt = h.next_dt AND cl.m_f = h.mainhead AND cl.part = h.clno AND cl.main_supp = h.main_supp_flag AND cl.roster_id = h.roster_id AND cl.display = 'Y') 
            WHERE h.next_dt = '$onDate' $cond 
            ORDER BY user_section
        ";
       // die();

    //    echo $sql; die;

        $query = $this->db->query($sql);

        //echo $this->db->last_query();
        if ($query->getNumRows() >= 1)
            return $query->getResultArray();
        else
            return false;
    }
}
