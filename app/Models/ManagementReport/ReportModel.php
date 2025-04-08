<?php

namespace App\Models\ManagementReport;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;
use Illuminate\Support\Facades\DB;


class ReportModel extends Model
{

    protected $db;
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    function getUploadedJudgmentOrdersList($reportType = null, $fromDate = null, $toDate = null)
    {

        $condition = "";

        if ($reportType == 1) // 1 for Receive Report
        {
            $condition = " and o.type='J'";
        } elseif ($reportType == 2) // 2 for Dispatch Report
        {
            $condition = " and o.type='O'";
        }

        $sql = "
                SELECT 
                    m.diary_no,
                    CONCAT(m.reg_no_display, ' @ ', 
                        CONCAT(SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4), ' / ', 
                        SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3 FOR 4))) AS case_no,
                    CONCAT(m.pet_name, ' Vs. ', m.res_name) AS cause_title,
                    o.orderdate AS order_date,
                    '' AS no_of_page,
                    CASE 
                        WHEN (s.category_sc_old IS NOT NULL AND s.category_sc_old != '' AND s.category_sc_old != '0') 
                        THEN CONCAT('(', s.category_sc_old, ')', s.sub_name1, '-', s.sub_name4) 
                        ELSE CONCAT('(', s.subcode1, s.subcode2, ')', s.sub_name1, '-', s.sub_name4) 
                    END AS subject_category,
                    CONCAT('http://xxxx/supreme_court/', o.pdfname) AS disp_path,
                    CONCAT('/home/reports/', o.pdfname) AS path,
                    rac.agency_name 
                FROM 
                    ordernet o 
                INNER JOIN 
                    main m ON o.diary_no = m.diary_no 
                LEFT JOIN 
                    mul_category mcat ON o.diary_no = mcat.diary_no 
                LEFT JOIN 
                    master.submaster s ON mcat.submaster_id = s.id 
                LEFT JOIN 
                    lowerct lct ON lct.diary_no = m.diary_no 
                LEFT JOIN 
                    master.ref_agency_code rac ON lct.l_state = rac.cmis_state_id AND lct.l_dist = rac.id 
                WHERE 
                    o.orderdate BETWEEN '" . $fromDate . "' AND '" . $toDate . "' 
                    AND o.display = 'Y' 
                    AND lct.is_order_challenged = 'Y' 
                    $condition 
                GROUP BY 
                    m.diary_no, o.orderdate, s.category_sc_old, s.sub_name1, s.sub_name4, s.subcode1, s.subcode2, o.pdfname, rac.agency_name 
                ORDER BY 
                    o.orderdate ASC
        ";

        // echo $sql;

        $query = $this->db->query($sql);

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return [];
        }
    }

    public function getCatYearWise()
    {
        $tempQuery = $this->db->table('main m')
            ->select('DISTINCT COALESCE(mc.submaster_id, 0) AS submaster_id, 
                     SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3 FOR 4) AS dy,
                     COUNT(SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3 FOR 4)) AS year_count')
            ->join('mul_category mc', 'mc.diary_no = m.diary_no AND mc.display = \'Y\'', 'left')
            ->join('master.submaster s', 's.id = mc.submaster_id AND s.display = \'Y\'', 'left')

            ->where('m.c_status', 'P')
            ->groupBy('submaster_id, SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3 FOR 4)');


        $tempQuerySql = $tempQuery->getCompiledSelect();
        $subquerySql = "SELECT 
                            s.id AS sid, 
                            s.sub_name1 AS main_name, 
                            CONCAT(s.subcode1, CASE WHEN LENGTH(s.subcode2) = 1 THEN '0' ELSE '' END, s.subcode2) AS full_code,
                            COALESCE(
                                CASE
                                    WHEN (s.category_sc_old IS NOT NULL AND s.category_sc_old != '' AND s.category_sc_old != '0')
                                        THEN CONCAT(s.sub_name1, '-', s.sub_name4)
                                    ELSE s.sub_name4
                                END, 
                                'WITHOUT CATEGORY'
                            ) AS sub_name1,
                            COALESCE(s.subcode1, 99) AS subcode1, 
                            COALESCE(s.subcode2, '0') AS subcode2, 
                            temp.*
                        FROM ($tempQuerySql) AS temp
                        LEFT JOIN master.submaster s ON s.id = temp.submaster_id AND s.display = 'Y'";


        $aggregatedSql = "SELECT 
                            sub_name1, 
                            main_name, 
                            subcode1 AS org_subcode1, 
                            subcode2, 
                            full_code AS subcode1, 
                            SUM(year_count) AS gt,
                            SUM(CASE WHEN CAST(dy AS INTEGER) <= 1990 THEN year_count ELSE 0 END) AS upto_1990,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 1991 THEN year_count ELSE 0 END) AS year_1991,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 1992 THEN year_count ELSE 0 END) AS year_1992,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 1993 THEN year_count ELSE 0 END) AS year_1993,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 1994 THEN year_count ELSE 0 END) AS year_1994,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 1995 THEN year_count ELSE 0 END) AS year_1995,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 1996 THEN year_count ELSE 0 END) AS year_1996,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 1997 THEN year_count ELSE 0 END) AS year_1997,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 1998 THEN year_count ELSE 0 END) AS year_1998,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 1999 THEN year_count ELSE 0 END) AS year_1999,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 2000 THEN year_count ELSE 0 END) AS year_2000,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 2001 THEN year_count ELSE 0 END) AS year_2001,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 2002 THEN year_count ELSE 0 END) AS year_2002,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 2003 THEN year_count ELSE 0 END) AS year_2003,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 2004 THEN year_count ELSE 0 END) AS year_2004,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 2005 THEN year_count ELSE 0 END) AS year_2005,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 2006 THEN year_count ELSE 0 END) AS year_2006,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 2007 THEN year_count ELSE 0 END) AS year_2007,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 2008 THEN year_count ELSE 0 END) AS year_2008,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 2009 THEN year_count ELSE 0 END) AS year_2009,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 2010 THEN year_count ELSE 0 END) AS year_2010,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 2011 THEN year_count ELSE 0 END) AS year_2011,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 2012 THEN year_count ELSE 0 END) AS year_2012,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 2013 THEN year_count ELSE 0 END) AS year_2013,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 2014 THEN year_count ELSE 0 END) AS year_2014,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 2015 THEN year_count ELSE 0 END) AS year_2015,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 2016 THEN year_count ELSE 0 END) AS year_2016,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 2017 THEN year_count ELSE 0 END) AS year_2017,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 2018 THEN year_count ELSE 0 END) AS year_2018,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 2019 THEN year_count ELSE 0 END) AS year_2019,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 2020 THEN year_count ELSE 0 END) AS year_2020,
                            SUM(CASE WHEN CAST(dy AS INTEGER) = 2021 THEN year_count ELSE 0 END) AS year_2021
                        FROM ($subquerySql) AS subquery
                        GROUP BY sub_name1, main_name, subcode1, subcode2, full_code";
        $finalSql = "SELECT ROW_NUMBER() OVER () AS sno, aggregated.*
                     FROM ($aggregatedSql) AS aggregated";
        $query = $this->db->query("SELECT CASE WHEN org_subcode1 = 99 THEN sno ELSE 1 END AS sno, * 
                                   FROM ($finalSql) AS final 
                                   ORDER BY final.sno ASC");

        return $query->getResultArray();
    }
    public function getJudges()
    {
        $query = $this->db->query("SELECT jcode, jname FROM master.judge WHERE is_retired = 'N' AND jtype = 'J'");
        $judges = $query->getResultArray();

        return [
            'judges' => $judges,
            'judge_count' => count($judges)
        ];
    }

    public function getCategories()
    {
        $builder = $this->db->table('master.submaster');
        $builder->select("subcode1, CONCAT(subcode1, '-', sub_name1) AS category");
        $builder->where('subcode1 !=', '0');
        $builder->where('subcode2', '0');
        $builder->where('subcode3', '0');
        $builder->where('subcode4', '0');
        $builder->orderBy('subcode1');

        $query = $builder->get();
        return $query->getResultArray(); 
    }

    public function fetchJudgesReport($selsubcat, $mainhead, $tdate, $fdate, $jud_coram, $dfdate, $dtdate, $selcat)
    {
        //h.mainhead
        $db = \Config\Database::connect();
    
        // Start with the base query using PostgreSQL syntax
        $query = "
            WITH row_numbers AS (
                SELECT 
                    ROW_NUMBER() OVER (ORDER BY CAST(RIGHT(m.diary_no, 4) AS INTEGER), CAST(LEFT(m.diary_no, LENGTH(m.diary_no) - 4) AS INTEGER)) AS sno,
                    m.diary_no,
                    m.reg_no_display,
                    m.pet_name,
                    m.res_name,
                    aa.total_connected,
                    (SELECT STRING_AGG(j.abbreviation, '#' ORDER BY j.judge_seniority) FROM judge j WHERE POSITION(j.jcode IN h.coram) > 0) AS Coram,
                    CASE
                        WHEN (s.category_sc_old IS NOT NULL AND s.category_sc_old != '' AND s.category_sc_old != 0)
                            THEN CONCAT('(', s.category_sc_old, ')', s.sub_name1, '-', s.sub_name4)
                        ELSE CONCAT('(', CONCAT(s.subcode1, '', s.subcode2), ')', s.sub_name1, '-', s.sub_name4)
                    END AS Subject_category,
                    tentative_section(m.diary_no) AS Section,
                    tentative_da(m.diary_no) AS DA
                FROM main m
                INNER JOIN heardt h ON h.diary_no = m.diary_no
                INNER JOIN master.listing_purpose l ON l.code = h.listorder
                LEFT JOIN (
                    SELECT n.conn_key, COUNT(*) AS total_connected
                    FROM main m
                    INNER JOIN heardt h ON m.diary_no = h.diary_no
                    INNER JOIN main n ON m.diary_no = n.conn_key
                    WHERE n.diary_no != n.conn_key AND m.c_status = 'P'
                    GROUP BY n.conn_key
                ) aa ON m.diary_no = aa.conn_key
                LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                LEFT JOIN mul_category mc ON mc.diary_no = m.diary_no AND mc.display = 'Y'
                LEFT JOIN master.submaster s ON mc.submaster_id = s.id AND s.flag = 's' AND s.display = 'Y'
                WHERE h.mainhead $mainhead
                    AND (m.diary_no = m.conn_key OR m.conn_key = 0 OR m.conn_key = '' OR m.conn_key IS NULL)
                    AND h.next_dt != '0000-00-00'
                    AND h.board_type = 'J' 
                    AND s.subcode1 IS NOT NULL 
                    AND rd.fil_no IS NULL 
                    AND m.c_status = 'P'
                    AND h.main_supp_flag = 0 
                    AND h.next_dt BETWEEN '$fdate' AND '$tdate'
                    AND s.id IN $selcat
                    AND DATE(m.diary_no_rec_date) BETWEEN '$dfdate' AND '$dtdate'
                    AND $jud_coram
                    AND mc.submaster_id != 911
                    AND mc.submaster_id != 913
                    AND (m.lastorder NOT LIKE '%Heard & Reserved%' OR m.lastorder = '' OR m.lastorder IS NULL)
            )
            SELECT sno, Case_no, Cause_title, Group_count, Coram, Subject_category, Section, DA
            FROM row_numbers
            ORDER BY sno";
    
        // Execute the query
        $result = $db->query($query);
        return $result->getResultArray();
    }
    
    
        public function getJudgeCoram($judge, $jud_num)
        {
            $jud_len = count($judge);
            $jud_flag = 0;
            $jud_coram = "";
    
            if ($jud_num == $jud_len) {
                $jud_flag = 1;
            } else {
                for ($i = 0; $i < $jud_len; $i++) {
                    if ($i == 0) {
                        $jud_coram = ($judge[$i] == 'b') ? "(h.coram='' OR h.coram = 0 OR h.coram IS NULL" : "(h.coram='" . $judge[$i] . "'";
                    } else {
                        $jud_coram .= ($judge[$i] == 'b') ? " OR h.coram='' OR h.coram = 0 OR h.coram IS NULL" : " OR h.coram='" . $judge[$i] . "'";
                    }
                }
                $jud_coram .= ')';
            }
    
            return $jud_coram;
        }
    
        public function getSubCategoryCondition($selsubcat)
        {
            $selcat = "";
            foreach ($selsubcat as $key => $cat) {
                $temp = explode('-', $cat);
                $selcat .= ($key == 0) ? "(" . $temp[0] : "," . $temp[0];
            }
            $selcat .= ")";
    
            return $selcat;
        }


        function get_main_subject_categorywise_pending_cases()
        {
            $sql="SELECT 
                        s.id,
                        s.category_sc_old,
                        s.subcode1,
                        s.flag_use,
                        s.sub_name1,
                        SUM(CASE WHEN a.mf_active = 'M' THEN 1 ELSE 0 END) AS Misc_Sub_Cat_pendency,
                        SUM(CASE WHEN a.mf_active = 'F' THEN 1 ELSE 0 END) AS Regular_Sub_Cat_pendency,
                        SUM(CASE WHEN ((a.mf_active = ' ' OR a.mf_active IS NULL) AND (a.mf_active <> 'M' OR a.mf_active IS NULL)) THEN 1 ELSE 0 END) AS Not_Misc_Not_Regular,
                        COUNT(*) AS Total_Sub_Cat_pendency
                    FROM (
                        SELECT DISTINCT 
                            m.diary_no AS diary,
                            m.conn_key,
                            m.active_casetype_id AS casetype_id,
                            m.mf_active
                        FROM
                            main m
                        LEFT JOIN main_casetype_history b ON (m.diary_no = b.diary_no AND b.is_deleted = 'f')
                        LEFT JOIN dispose d ON d.diary_no = m.diary_no
                        WHERE (
                                c_status = 'P' 
                                OR (c_status = 'D' AND DATE(d.ord_dt) >= CURRENT_DATE)
                            )
                        AND (b.new_registration_number = m.fil_no OR b.diary_no IS NULL)
                        AND CASE 
                                WHEN (order_date IS NOT NULL AND m.c_status != 'P') 
                                    AND DATE(fil_dt) != DATE(order_date) 
                                THEN (DATE(order_date) <= DATE(d.ord_dt) AND DATE(order_date) <= CURRENT_DATE)
                                ELSE DATE(order_date) <= CURRENT_DATE
                            END
                        AND (m.fil_no IS NOT NULL AND m.fil_no != '')
                        
                        UNION
                        
                        SELECT DISTINCT 
                            m.diary_no AS diary,
                            m.conn_key,
                            casetype_id,
                            m.mf_active
                        FROM
                            docdetails dd
                        INNER JOIN main m ON m.diary_no = dd.diary_no
                        LEFT JOIN main_casetype_history b ON (m.diary_no = b.diary_no AND b.is_deleted = 'f')
                        LEFT JOIN dispose d ON d.diary_no = m.diary_no
                        LEFT JOIN heardt h ON h.diary_no = dd.diary_no
                        LEFT JOIN last_heardt lh ON lh.diary_no = dd.diary_no
                        LEFT JOIN (
                            SELECT 
                                diary_no,
                                STRING_AGG(CASE WHEN rm_dt IS NULL THEN 'NR' ELSE NULL END, ',') AS notr,
                                MAX(rm_dt) AS rmdt
                            FROM 
                                obj_save
                            WHERE 
                                display = 'Y'
                            GROUP BY 
                                diary_no
                        ) t2 ON dd.diary_no = t2.diary_no
                        WHERE 
                            (b.new_registration_number = m.fil_no OR b.diary_no IS NULL)
                            AND CASE 
                                    WHEN (m.fil_no IS NULL OR m.fil_no = '') 
                                    THEN 1 = 1
                                    ELSE DATE(order_date) > CURRENT_DATE
                            END
                        AND doccode = '8'
                        AND (doccode1 IN ('28', '95', '214', '215', '16', '79', '99', '300', '226', '288', '322'))
                        AND (DATE(h.next_dt) < CURRENT_DATE OR DATE(lh.next_dt) < CURRENT_DATE)
                        AND (c_status = 'P' OR (c_status = 'D' AND DATE(d.ord_dt) >= CURRENT_DATE))
                        AND t2.notr IS NULL 
                        AND CASE WHEN t2.rmdt IS NOT NULL THEN DATE(t2.rmdt) <= CURRENT_DATE ELSE TRUE END
                        AND CASE 
                                WHEN (dd.ent_dt IS NULL OR dd.ent_dt = '1900-01-01 00:00:00') 
                                THEN DATE(dd.lst_mdf) < CURRENT_DATE 
                                ELSE DATE(dd.ent_dt) < CURRENT_DATE 
                            END
                        AND dd.display = 'Y'
                    ) a
                    LEFT JOIN mul_category mc ON a.diary = mc.diary_no
                    LEFT JOIN master.submaster s ON mc.submaster_id = s.id
                    WHERE mc.display = 'Y'
                    GROUP BY s.subcode1, s.id
                    ORDER BY s.subcode1";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
    
    public function to_be_list_priority_process($limit_number, $sortby, $mainhead, $list_date)
    {
        $return['reports'] = $return['mainhead_title'] = [];
        $limit = '';
        if ($limit_number > 0) {
            $limit = " limit " . $limit_number;
        }

        $order_by = '';
        if ($sortby == 2) {
            $order_by = "ORDER BY 
            CASE WHEN m.reg_no_display != '' THEN 1 ELSE 2 END ASC, 
            m.active_casetype_id ASC, 
            EXTRACT(YEAR FROM m.active_fil_dt) ASC,
            CAST(RIGHT(CAST(m.diary_no AS TEXT), 4) AS BIGINT) ASC, 
            CAST(NULLIF(REGEXP_REPLACE(m.active_fil_no, '[^0-9]', '', 'g'), '') AS BIGINT) ASC,
            CAST(LEFT(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS BIGINT) ASC";
        }
        
        $mainhead_title = '';
        $upto_list_dt = '';
        if ($mainhead == 'M') {
            $mainhead_title = 'Misc. Stage';
            $upto_list_dt = "AND h.next_dt <= '" . date("Y-m-d", strtotime($list_date)) . "'";

            $sql = "select * from (select case 
                        when h.clno > 0 and h.next_dt >= CURRENT_DATE  then 1
                        when a.diary_no is not null then 2
                        when h.main_supp_flag = 0 and h.listorder in (4,5) then 3
                        when h.main_supp_flag = 0 and h.listorder = 7 then 4
                        when h.main_supp_flag = 0 and h.listorder in (25,8,24,21,48) then 5
                        else 6 end case_priority,
                        a.diary_no as advance_list, 
                        m.pet_name, m.res_name, m.reg_no_display,m.active_casetype_id,m.active_fil_dt,m.active_fil_no,
                        h.* from main m
                        inner join heardt h on h.diary_no = m.diary_no
                        left join advance_allocated a on a.diary_no = m.diary_no and a.next_dt = h.next_dt and h.next_dt >= CURRENT_DATE 
                        where m.c_status = 'P' 
                        AND (m.diary_no = m.conn_key::bigint OR m.conn_key='0' OR m.conn_key = '' OR m.conn_key IS NULL)
                        and m.mf_active != 'F' and h.mainhead = 'M' and h.board_type = 'J' 
                        and h.listorder != 32 $upto_list_dt
                        group by m.diary_no, h.clno, h.next_dt, a.diary_no, h.main_supp_flag, h.listorder, h.diary_no
                        order by case_priority asc, 
                        h.next_dt, 
                         CAST(SUBSTRING(CAST(h.diary_no AS TEXT) FROM LENGTH(CAST(h.diary_no AS TEXT))-3 FOR 4) AS INTEGER) ASC, 
                        CAST(SUBSTRING(CAST(h.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(h.diary_no AS TEXT))-4) AS INTEGER) ASC
                        
                        $limit) m $order_by";
        }

        if ($mainhead == 'F') {
            $mainhead_title = "Regular Stage";
            //$upto_list_dt = "and if(h.listorder in (4,5,7),h.next_dt <= '" . date("Y-m-d", strtotime($list_date)) . "', h.next_dt !='0000-00-00')";
            $upto_list_dt = "AND ( CASE WHEN h.listorder IN (4, 5, 7) THEN h.next_dt <= '2024-11-01' ELSE TRUE END )";

            /*$sql1 = "select * from (select case
                    when h.clno > 0 and h.next_dt >= CURRENT_DATE then 1
                    when w.diary_no is not null then 2
                    when h.main_supp_flag = 0 and h.listorder in (4,5) then 3
                    when h.main_supp_flag = 0 and h.listorder = 7 then 4
                    when h.main_supp_flag = 0 and h.listorder not in (4,5,7) then 5
                    else 6 end case_priority, 
                    w.diary_no as advance_list, 
                    m.pet_name, m.res_name, m.reg_no_display,m.active_casetype_id,m.active_fil_dt,m.active_fil_no, m.conn_key as main_key,
                    h.* from main m
                    inner join heardt h on h.diary_no = m.diary_no
                    left join (select wl1.diary_no from weekly_list wl1
                    inner join (
                    SELECT max(weekly_no) max_weekly_no, max(weekly_year) max_weekly_year FROM weekly_list where (EXTRACT(YEAR FROM CURRENT_DATE) = weekly_year OR (EXTRACT(YEAR FROM CURRENT_DATE) + 1) + 1) = weekly_year)
                    ) wl2 on wl2.max_weekly_no = wl1.weekly_no and wl2.max_weekly_year = wl1.weekly_year
                    ) w on w.diary_no = h.diary_no
                    where m.c_status = 'P' and h.mainhead = 'F' and h.board_type = 'J'
                    AND (m.diary_no = m.conn_key OR m.conn_key=0 OR m.conn_key = '' OR m.conn_key IS NULL)
                    and m.mf_active = 'F' $upto_list_dt
                    group by m.diary_no
                    order by case_priority,
                    CAST(RIGHT(h.diary_no, 4) AS INTEGER) ASC, 
                    CAST(LEFT(h.diary_no,LENGTH(h.diary_no)-4) AS INTEGER) ASC 
                    $limit) m
                    $order_by";*/

            $sql = "SELECT * FROM ( SELECT 
                        CASE WHEN h.clno > 0 AND h.next_dt >= CURRENT_DATE THEN 1 
                             WHEN w.diary_no IS NOT NULL THEN 2 WHEN h.main_supp_flag = 0 AND h.listorder IN (4, 5) THEN 3 
                             WHEN h.main_supp_flag = 0 AND h.listorder = 7 THEN 4 
                             WHEN h.main_supp_flag = 0 AND h.listorder NOT IN (4, 5, 7) THEN 5 
                             ELSE 6 END AS case_priority, 
                        w.diary_no AS advance_list, m.pet_name, m.res_name, m.reg_no_display, m.active_casetype_id, m.active_fil_dt,
                        m.active_fil_no, m.conn_key AS main_key, h.* 
                        FROM main m INNER JOIN heardt h ON h.diary_no = m.diary_no 
                        LEFT JOIN (SELECT wl1.diary_no FROM weekly_list wl1 
                        INNER JOIN (SELECT MAX(weekly_no) AS max_weekly_no, MAX(weekly_year) AS max_weekly_year 
                        FROM weekly_list 
                        WHERE (EXTRACT(YEAR FROM CURRENT_DATE) = weekly_year OR EXTRACT(YEAR FROM CURRENT_DATE) + 1 = weekly_year)) wl2 ON wl2.max_weekly_no = wl1.weekly_no 
                        AND wl2.max_weekly_year = wl1.weekly_year) w ON w.diary_no = h.diary_no WHERE m.c_status = 'P' AND h.mainhead = 'F' AND h.board_type = 'J' 
                        AND (m.diary_no = m.conn_key::bigint OR m.conn_key='0' OR m.conn_key = '' OR m.conn_key IS NULL) AND m.mf_active = 'F' $upto_list_dt 
                        GROUP BY m.diary_no, h.clno, h.next_dt, w.diary_no, h.main_supp_flag, h.listorder, h.diary_no 
                        ORDER BY case_priority, 
                        CAST(SUBSTRING(CAST(h.diary_no AS TEXT) FROM LENGTH(CAST(h.diary_no AS TEXT))-3 FOR 4) AS INTEGER) ASC, 
                        CAST(SUBSTRING(CAST(h.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(h.diary_no AS TEXT))-4) AS INTEGER) ASC $limit) m $order_by";
        }
        
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $return['reports'] = $query->getResultArray();
            $return['mainhead_title'] = $mainhead_title;
        }
        return $return;
    }

    public function get_sensitive_cases()
    {
        $return = [];
        $sql = "SELECT tentative_section(a.diary_no) ten_sec, a.diary_no, 
        COALESCE(STRING_AGG(n.j1::TEXT, ','), c.coram) AS coram, nr.res_add,
        reason, b.pet_name, b.res_name,b.next_dt,active_fil_no,short_description,
        EXTRACT(YEAR FROM active_fil_dt) AS active_fil_dt,
        b.reg_no_display 
        FROM sensitive_cases a JOIN main b ON a.diary_no=b.diary_no 	
        LEFT JOIN heardt c ON a.diary_no=c.diary_no
        LEFT JOIN master.casetype d ON d.casecode = CAST(NULLIF(SUBSTRING(b.active_fil_no FROM 1 FOR 2), '') AS BIGINT)
            AND d.display = 'Y'
        LEFT JOIN not_before n ON n.diary_no = a.diary_no AND n.notbef = 'B'
        LEFT JOIN master.not_before_reason nr ON nr.res_id = n.res_id
        WHERE a.display='Y' AND c_status='P' 
        GROUP BY a.diary_no, c.coram, nr.res_add, a.reason, b.pet_name, b.res_name, b.next_dt, b.active_fil_no, d.short_description, b.active_fil_dt, b.reg_no_display
        ORDER BY SUBSTRING(a.diary_no::TEXT FROM LENGTH(a.diary_no::TEXT) - 3 FOR 4),
         SUBSTRING(a.diary_no::TEXT FROM 1 FOR LENGTH(a.diary_no::TEXT) - 4),
        next_dt";
        
        $query = $this->db->query($sql);
        
        if ($query->getNumRows() >= 1) {
            $return = $query->getResultArray();
        }  
        return $return;         
    }

    public function get_display_status_with_date_differnces($tentative_cl_dt)
    {
        $tentative_cl_date_greater_than_today_flag="F";
        $curDate=date('d-m-Y');
        $tentativeCLDate = date('d-m-Y', strtotime($tentative_cl_dt));
        $datediff=strtotime($tentativeCLDate) - strtotime($curDate);
        $noofdays= round($datediff / (60 * 60 * 24));
        if(strtotime($tentativeCLDate) > strtotime($curDate)) {
            if($noofdays<=60 && $noofdays>0){
                $tentative_cl_date_greater_than_today_flag='T';
            }
        } else {
            $tentative_cl_date_greater_than_today_flag='F';
        }
        return $tentative_cl_date_greater_than_today_flag;
    }


}
