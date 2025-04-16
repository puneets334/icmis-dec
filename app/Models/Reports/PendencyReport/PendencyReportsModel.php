<?php

namespace App\Models\Reports\PendencyReport;

use CodeIgniter\Model;

class PendencyReportsModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function get_pendency($reportType, $categoryCode = NULL, $groupCountFrom = NULL, $groupCountTo = NULL, $caseCategory = NULL, $caseStatus = NULL, $caseType = NULL, $fromDate = NULL, $toDate = NULL, $reportType1 = NULL, $jcode = null, $matterType = null, $matterStatus = null)
    {


        $sql = "";
        switch ($reportType) {
            case 1: {
                    $sql = "SELECT  
                                    j.jcode, 
                                    j.jname, 
                                    count(*) AS judge_wise_pendency,
                                    sum(
                                        CASE 
                                            WHEN h.conn_key = 0 OR h.conn_key = h.diary_no THEN 1 
                                            ELSE 0 
                                        END
                                    ) AS MainCaseCount,
                                    sum(
                                        CASE 
                                            WHEN h.conn_key != 0 AND h.conn_key != h.diary_no THEN 1 
                                            ELSE 0 
                                        END
                                    ) AS ConnectedCaseCount,
                                    CASE 
                                        WHEN j.is_retired = 'Y' THEN 1 
                                        ELSE 2 
                                    END AS is_retired
                                FROM 
                                    heardt h
                                INNER JOIN 
                                    main m ON h.diary_no = m.diary_no
                                LEFT JOIN 
                                    master.judge j ON array_position(string_to_array(h.judges, ','), j.jcode::text) IS NOT NULL
                                WHERE 
                                    m.c_status = 'P'
                                    AND h.judges  is not null
                                    AND h.judges != '0'
                                    AND h.judges IS NOT NULL
                                GROUP BY 
                                    j.jcode, j.jname, j.is_retired
                                ORDER BY 
                                    j.judge_seniority DESC";
                    $query = $this->db->query($sql);
                    break;
                }
            case 2: {

                    $sql = "select id,subcode1,category_sc_old,sub_name1,sub_name4, count(*) as total_pendency,
                sum(case when mf_active!='F' and main_supp_flag in (0,1,2) then 1 else 0 end) as misc_ready,
                sum(case when mf_active!='F' and (main_supp_flag not in (0,1,2) or main_supp_flag is null) then 1 else 0 end) as misc_not_ready,
                sum(case when mf_active='F' and main_supp_flag in (0,1,2) then 1 else 0 end) as regular_ready,
                sum(case when mf_active='F' and (main_supp_flag not in (0,1,2) or main_supp_flag is null) then 1 else 0 end) as regular_not_ready
                                    from 
                                    ( 
                                        SELECT m.diary_no,m.fil_dt, 
                                        s.id,s.subcode1,s.category_sc_old, s.sub_name1,s.sub_name4,
                                        m.mf_active,h.main_supp_flag
                                        FROM
                                            heardt h
                                            right JOIN main m ON h.diary_no = m.diary_no
                                            INNER JOIN mul_category mcat ON m.diary_no = mcat.diary_no
                                            INNER JOIN master.submaster s ON mcat.submaster_id = s.id
                                            LEFT JOIN master.users u ON u.usercode = m.dacode AND (u.display = 'Y' || u.display is null)
                                            LEFT JOIN master.usersection us ON us.id = u.section

                                            WHERE m.c_status = 'P' AND mcat.display = 'Y'        
                                            AND s.display = 'Y'    and flag='s' and flag_use in('S','L')                                           
                                    ) a
                                    GROUP BY id,subcode1,category_sc_old, sub_name1,sub_name4";
                    $query = $this->db->query($sql);
                    $result = $query->getResultArray();
                    return $result;
                    break;
                }
            case 3: {

                    $builder = $this->db->table('heardt h');
                    $builder->select('
                        j.jcode,
                        j.jname,
                        s.section_name as user_section,
                        SUBSTR(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4) AS diary_no,
                        SUBSTR(m.diary_no::text, -4) AS diary_year,
                        TO_CHAR(m.diary_no_rec_date, \'YYYY-MM-DD\') AS diary_date,
                         m.next_dt,
                        mainhead,
                        subhead,
                        brd_slno,
                        h.usercode,
                        ent_dt,
                        pet_name,
                        res_name,
                        active_fil_no,
                        dacode,
                        h.conn_key,
                        stagename,
                        main_supp_flag,
                        u.name AS alloted_to_da,
                        descrip,
                        u1.name AS updated_by,
                        m.listorder
                    ');

                    $builder->join('main m', 'h.diary_no = m.diary_no', 'INNER');
                    $builder->join('master.judge j', 'h.judges LIKE \'%\' || j.jcode::text || \'%\'', 'LEFT');
                    // $builder->join('master.judge j', 'FIND_IN_SET(j.jcode, m.judges) = 1', 'LEFT');
                    $builder->join('master.subheading c', 'h.subhead = c.stagecode AND c.display = \'Y\'', 'LEFT');
                    $builder->join('master.users u', 'u.usercode = m.dacode AND u.display = \'Y\'', 'LEFT');
                    $builder->join('master.users u1', 'u1.usercode = h.usercode AND u1.display = \'Y\'', 'LEFT');
                    $builder->join('master.master_main_supp mms', 'mms.id = h.main_supp_flag', 'LEFT');
                    $builder->join('master.listing_purpose lp', 'lp.code = h.listorder AND lp.display = \'Y\'', 'LEFT');
                    $builder->join('master.usersection s', 's.id = u.section AND s.display = \'Y\'', 'LEFT');

                    $builder->where('m.c_status', 'P');
                    $builder->where('judges !=', '');
                    $builder->where('judges !=', '0');
                    $builder->where('judges IS NOT NULL');
                    $builder->where('j.jcode', $jcode);
                    echo $builder->getCompiledSelect();
                    $query = $builder->get();

                    break;
                }
            case 4: {
                    $catregory_code = $this->input->getGet('categoryCode');
                    $builder = $this->db->table('mul_category mc');
                    $builder->select('
                        CASE 
                            WHEN (m.conn_key != 0 AND m.conn_key IS NOT NULL AND m.conn_key != m.diary_no) 
                            THEN (SELECT reg_no_display FROM main WHERE diary_no = m.conn_key) 
                            ELSE "Main" 
                        END AS connected_with,
                        sm.category_sc_old,
                        sm.sub_name1,
                        sm.sub_name4,
                        s.section_name AS user_section,
                        SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) AS diary_no,
                        SUBSTR(m.diary_no, -4) AS diary_year,
                        m.reg_no_display,
                        m.mf_active,
                        DATE_FORMAT(m.diary_no_rec_date, "%Y-%m-%d") AS diary_date,
                        next_dt,
                        mainhead,
                        subhead,
                        brd_slno,
                        h.usercode,
                        ent_dt,
                        pet_name,
                        res_name,
                        active_fil_no,
                        dacode,
                        h.conn_key,
                        stagename,
                        main_supp_flag,
                        u.name AS alloted_to_da,
                        descrip,
                        u1.name AS updated_by,
                        listorder
                    ');

                    $builder->join('main m', 'mc.diary_no = m.diary_no', 'INNER');
                    $builder->join('heardt h', 'm.diary_no = h.diary_no', 'INNER');
                    $builder->join('submaster sm', 'mc.submaster_id = sm.id AND sm.display = "Y"', 'LEFT');
                    $builder->join('subheading c', 'h.subhead = c.stagecode AND c.display = "Y"', 'LEFT');
                    $builder->join('users u', 'u.usercode = m.dacode AND u.display = "Y"', 'LEFT');
                    $builder->join('users u1', 'u1.usercode = h.usercode AND u1.display = "Y"', 'LEFT');
                    $builder->join('master_main_supp mms', 'mms.id = h.main_supp_flag', 'LEFT');
                    $builder->join('listing_purpose lp', 'lp.code = h.listorder AND lp.display = "Y"', 'LEFT');
                    $builder->join('usersection s', 's.id = u.section AND s.display = "Y"', 'LEFT');

                    $builder->where('m.c_status', 'P');
                    $builder->where('mc.display', 'Y');
                    $builder->where('sm.category_sc_old', $catregory_code);
                    $query = $builder->get();
                    break;
                }
            case 5: {
               
                    if (strcasecmp($matterType, 'MF') == 0) {
                        $Type = "1=1";
                    } else {
                        $Type = "m.mf_active='$matterType'";
                    }
                    if (strcasecmp($matterStatus, 'NR') == 0) {
                        $Status = "1=1";
                    } elseif (strcasecmp($matterStatus, 'N') == 0) {
                        $Status = "h.main_supp_flag!=0";
                    } elseif (strcasecmp($matterStatus, 'R') == 0) {
                        $Status = "h.main_supp_flag=0";
                    }
                    if (strcasecmp($categoryCode, '0') == 0) {
                        $code = "1=1";
                    } else {
                        $code = " s.subcode1 = $categoryCode";
                    }
                    $sql = "SELECT 
                                    us.section_name AS user_section, 
                                    u.name AS alloted_to_da, 
                                    CONCAT(s.subcode1) AS mainSubCategoryCode, 
                                    sub_name1, 
                                    SUBSTRING(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4) AS diary_no, 
                                    SUBSTRING(m.diary_no::text, -4) AS diary_year, 
                                    TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS diary_date, 
                                    m.reg_no_display, 
                                    m.pet_name, 
                                    m.res_name, 
                                    m.mf_active, 
                                    h.next_dt, 
                                    CASE 
                                        WHEN h.main_supp_flag = '0' THEN 'Ready' 
                                        ELSE 'Not Ready' 
                                    END AS Status, 
                                    aa.total_connected AS group_count, 
                                    CASE 
                                        WHEN (m.conn_key = '0' OR m.conn_key IS NULL OR COALESCE(NULLIF(m.conn_key, ''), '0')::int = m.diary_no) THEN 'M' 
                                        ELSE CASE 
                                            WHEN (m.conn_key != '0' AND m.conn_key IS NOT NULL AND COALESCE(NULLIF(m.conn_key, ''), '0')::int != m.diary_no) THEN 'C' 
                                        END 
                                    END AS mainorconn
                                FROM 
                                    heardt h
                                JOIN 
                                    main m ON h.diary_no = m.diary_no
                                JOIN 
                                    mul_category mcat ON h.diary_no = mcat.diary_no
                                JOIN 
                                    master.submaster s ON mcat.submaster_id = s.id
                                LEFT JOIN (
                                    SELECT 
                                        n.conn_key, 
                                        COUNT(*) AS total_connected
                                    FROM 
                                        main m
                                    INNER JOIN 
                                        heardt h ON m.diary_no = h.diary_no
                                    INNER JOIN 
                                        main n ON m.diary_no = COALESCE(NULLIF(n.conn_key, ''), '0')::int
                                    WHERE 
                                        n.diary_no != COALESCE(NULLIF(n.conn_key, ''), '0')::int 
                                        AND m.c_status = 'P'
                                    GROUP BY 
                                        n.conn_key
                                    HAVING 
                                        COUNT(*) >= $groupCountFrom
                                ) aa ON m.diary_no = aa.conn_key::int
                                LEFT JOIN 
                                    master.users u ON u.usercode = m.dacode 
                                    AND (u.display = 'Y' OR u.display IS NULL)
                                LEFT JOIN 
                                    master.usersection us ON us.id = u.section
                                WHERE 
                                    m.c_status = 'P'
                                    AND mcat.display = 'Y'
                                    AND (m.conn_key = '0' OR m.conn_key IS NULL OR COALESCE(NULLIF(m.conn_key, ''), '0')::int = m.diary_no)
                                    AND (aa.total_connected IS NULL OR aa.total_connected >= $groupCountFrom)
                                    AND s.display = 'Y'
                                    AND $Type
                                    AND $Status
                                    AND $code ";
                                    // pr($sql);
                    $query = $this->db->query($sql);
                    $result = $query->getResultArray();
                    return $result;
                    break;
                }
            case 6:
                {

                    $jCode = $jcode;
                   
                    $from_Date = date('Y-m-d', strtotime($fromDate));
                    $to_Date = date('Y-m-d', strtotime($toDate));
                    
                   
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
                                    COUNT(DISTINCT CASE WHEN (m.mf_active = 'M' AND (m.conn_key::text IS NULL OR m.conn_key::text = '0' OR m.conn_key::text = m.diary_no::text)) THEN m.diary_no END) AS Misc_Main,
                                    COUNT(DISTINCT CASE WHEN (m.mf_active = 'M' AND (m.conn_key::text IS NOT NULL AND m.conn_key::text != '0' AND m.conn_key::text != m.diary_no::text)) THEN m.diary_no END) AS Misc_Conn,
                                    COUNT(DISTINCT CASE WHEN (m.mf_active <> 'M' AND (m.conn_key::text IS NULL OR m.conn_key::text = '0' OR m.conn_key::text = m.diary_no::text)) THEN m.diary_no END) AS Regular_Main,
                                    COUNT(DISTINCT CASE WHEN (m.mf_active <> 'M' AND (m.conn_key::text IS NOT NULL AND m.conn_key::text != '0' AND m.conn_key::text != m.diary_no::text)) THEN m.diary_no END) AS Regular_Conn,
                                    COUNT(DISTINCT CASE WHEN (m.conn_key::text IS NULL OR m.conn_key::text = '0' OR m.conn_key::text = m.diary_no::text) THEN m.diary_no END) AS total_Main,
                                    COUNT(DISTINCT CASE WHEN (m.conn_key::text IS NOT NULL AND m.conn_key::text != '0' AND m.conn_key::text != m.diary_no::text) THEN m.diary_no END) AS total_Conn
                                FROM main m
                                INNER JOIN (
                                    SELECT DISTINCT diary_no, next_dt, judges
                                    FROM (
                                        SELECT diary_no, next_dt, judges, board_type
                                        FROM heardt
                                        WHERE next_dt BETWEEN '$from_Date'::date AND '$to_Date'::date
                                            AND clno != 0 AND brd_slno != 0 AND roster_id != 0 AND judges != '0' AND board_type = 'J'
                                            AND roster_id NOT IN (29,30)

                                        UNION ALL

                                        SELECT diary_no, next_dt, judges, board_type
                                        FROM last_heardt
                                        WHERE next_dt BETWEEN '$from_Date'::date AND '$to_Date'::date
                                            AND (bench_flag IS NULL OR bench_flag = '')
                                            AND clno != 0 AND brd_slno != 0 AND roster_id != 0 AND judges != '0'
                                            AND board_type = 'J' AND roster_id NOT IN (29,30)
                                    ) AS bb
                                ) AS aa ON m.diary_no = aa.diary_no
                                INNER JOIN master.judge j ON j.jcode::text = ANY(string_to_array(aa.judges, ','))
                                WHERE 1=1  $condition
                                GROUP BY j.jcode, j.jname
                            ) AS listed
                            LEFT JOIN (
                                SELECT 
                                    j.jcode, 
                                    j.jname,
                                    COUNT(DISTINCT CASE WHEN (m.mf_active = 'M' AND (m.conn_key::text IS NULL OR m.conn_key::text = '0' OR m.conn_key::text = m.diary_no::text)) THEN m.diary_no END) AS Misc_Main,
                                    COUNT(DISTINCT CASE WHEN (m.mf_active = 'M' AND (m.conn_key::text IS NOT NULL AND m.conn_key::text != '0' AND m.conn_key::text != m.diary_no::text)) THEN m.diary_no END) AS Misc_Conn,
                                    COUNT(DISTINCT CASE WHEN (m.mf_active <> 'M' AND (m.conn_key::text IS NULL OR m.conn_key::text = '0' OR m.conn_key::text = m.diary_no::text)) THEN m.diary_no END) AS Regular_Main,
                                    COUNT(DISTINCT CASE WHEN (m.mf_active <> 'M' AND (m.conn_key::text IS NOT NULL AND m.conn_key::text != '0' AND m.conn_key::text != m.diary_no::text)) THEN m.diary_no END) AS Regular_Conn,
                                    COUNT(DISTINCT CASE WHEN (m.conn_key::text IS NULL OR m.conn_key::text = '0' OR m.conn_key::text = m.diary_no::text) THEN m.diary_no END) AS total_Main,
                                    COUNT(DISTINCT CASE WHEN (m.conn_key::text IS NOT NULL AND m.conn_key::text != '0' AND m.conn_key::text != m.diary_no::text) THEN m.diary_no END) AS total_Conn
                                FROM main m
                                LEFT JOIN heardt h ON m.diary_no = h.diary_no
                                INNER JOIN dispose d ON m.diary_no = d.diary_no
                                INNER JOIN master.judge j ON j.jcode::text = ANY(string_to_array(d.jud_id, ','))
                                WHERE d.ord_dt BETWEEN '$from_Date'::date AND '$to_Date'::date
                                $condition
                                    AND m.c_status = 'D'
                                    AND h.board_type = 'J'
                                GROUP BY j.jcode, j.jname
                            ) AS disposed ON listed.jcode = disposed.jcode
                            ORDER BY listed.jcode";


                    $sql2 = "SELECT                     
                                COUNT(DISTINCT m.diary_no) AS other_disp
                            FROM main m 
                            LEFT JOIN heardt h ON m.diary_no = h.diary_no 
                            INNER JOIN dispose d ON m.diary_no = d.diary_no 
                            INNER JOIN master.judge j ON j.jcode::text = ANY(string_to_array(d.jud_id, ','))
                            WHERE 
                                d.ord_dt BETWEEN '$from_Date'::date AND '$to_Date'::date
                                AND m.c_status = 'D'
                                AND (h.board_type != 'J' OR h.diary_no IS NULL)";

                    $query2 = $this->db->query($sql2);

                    $query = $this->db->query($sql);

                    break;
                }
            case 7: {
                    break;
                }
            case 8: {
                    $fromDate = date('Y-m-d', strtotime($fromDate));
                    $toDate = date('Y-m-d', strtotime($toDate));

                    //Judgwise Matter Listed and Disposed Query

                    if ($jcode != '0')
                        $condition1 = " and j.jcode=$jcode";
                    else
                        $condition1 = " and 1=1";

                    if ($reportType1 == 'LMM') {
                        $condition2 = " and m.mf_active='M' AND (m.conn_key=0 OR m.conn_key IS NULL OR m.conn_key=m.diary_no)";
                    } else if ($reportType1 == 'LMC') {
                        $condition2 = " and m.mf_active='M' AND (m.conn_key!=0 AND m.conn_key IS NOT NULL AND m.conn_key!=m.diary_no)";
                    } else if ($reportType1 == 'LRM') {
                        $condition2 = " and m.mf_active='F' AND (m.conn_key=0 OR m.conn_key IS NULL OR m.conn_key=m.diary_no)";
                    } else if ($reportType1 == 'LRC') {
                        $condition2 = " and m.mf_active='F' AND (m.conn_key!=0 AND m.conn_key IS NOT NULL AND m.conn_key!=m.diary_no)";
                    } else if ($reportType1 == 'LTM') {
                        $condition2 = " and (m.conn_key=0 OR m.conn_key IS NULL OR m.conn_key=m.diary_no)";
                    } else if ($reportType1 == 'LTC') {
                        $condition2 = " and m.conn_key!=0 AND m.conn_key IS NOT NULL AND m.conn_key!=m.diary_no";
                    } else if ($reportType1 == 'DMM') {
                        $condition2 = " and m.mf_active='M' AND (m.conn_key=0 OR m.conn_key IS NULL OR m.conn_key=m.diary_no)";
                    } else if ($reportType1 == 'DMC') {
                        $condition2 = " and m.mf_active='M' AND (m.conn_key!=0 AND m.conn_key IS NOT NULL AND m.conn_key!=m.diary_no)";
                    } else if ($reportType1 == 'DRM') {
                        $condition2 = " and m.mf_active='F' AND (m.conn_key=0 OR m.conn_key IS NULL OR m.conn_key=m.diary_no)";
                    } else if ($reportType1 == 'DRC') {
                        $condition2 = " and m.mf_active='F' AND (m.conn_key!=0 AND m.conn_key IS NOT NULL AND m.conn_key!=m.diary_no)";
                    } else if ($reportType1 == 'DTM') {
                        $condition2 = " and (m.conn_key=0 OR m.conn_key IS NULL OR m.conn_key=m.diary_no)";
                    } else if ($reportType1 == 'DTC') {
                        $condition2 = " and m.conn_key!=0 AND m.conn_key IS NOT NULL AND m.conn_key!=m.diary_no";
                    } else {
                        $condition2 = " and 1=1";
                    }

                    //and j.jcode=108
                    if ($reportType1 == 'AL' || $reportType1 == 'LMM' || $reportType1 == 'LMC' || $reportType1 == 'LRM' || $reportType1 == 'LRC' || $reportType1 == 'LTM' || $reportType1 == 'LTC') {
                        $sql = "SELECT j.jcode, j.jname,
                      us.section_name AS user_section,
                      u.name alloted_to_da,
                      SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) AS diary_no,
                      SUBSTR(m.diary_no, - 4) AS diary_year,
                      DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                      m.reg_no_display,
                      m.pet_name,
                      m.res_name,
                      m.mf_active,
                      aa.next_dt,
                      aa.brd_slno,
                      r.courtno,

                                    CASE
                                        WHEN
                                            (m.conn_key = 0 OR m.conn_key IS NULL
                                                OR m.conn_key = m.diary_no)
                                        THEN
                                            'M'
                         ELSE CASE
                            WHEN
                               (m.conn_key != 0
                                   AND m.conn_key IS NOT NULL
                                        AND m.conn_key != m.diary_no)
                                            THEN
                                                'C'
                                        END
                                    END AS mainorconn
                    FROM main m INNER JOIN
                    (
                    SELECT DISTINCT diary_no,judges,next_dt,brd_slno,roster_id FROM
                        (
                            SELECT diary_no,judges,board_type,next_dt,brd_slno,roster_id FROM heardt WHERE next_dt BETWEEN '" . $fromDate . "' AND '" . $toDate . "' AND
                            clno!=0 AND brd_slno!=0 AND roster_id!=0 AND judges!=0 AND roster_id NOT IN (29,30) AND board_type ='J'
                            UNION ALL
                            SELECT diary_no,judges,board_type,next_dt,brd_slno,roster_id FROM last_heardt WHERE next_dt BETWEEN '" . $fromDate . "' AND '" . $toDate . "'
                            AND (bench_flag IS NULL OR bench_flag='') AND clno!=0 AND brd_slno!=0 AND roster_id!=0 AND judges!=0
                            AND roster_id NOT IN (29,30) AND board_type ='J'
                        )bb
                    ) aa
                    ON m.diary_no=aa.diary_no
                    INNER JOIN judge j ON FIND_IN_SET (j.jcode, aa.judges)=1
                    LEFT JOIN users u ON u.usercode = m.dacode AND u.display = 'Y'
                    LEFT JOIN usersection us ON us.id = u.section
                    LEFT JOIN roster r on aa.roster_id=r.id
                    WHERE j.is_retired='N' $condition1 $condition2 order by aa.next_dt,r.courtno,aa.brd_slno";
                    }
                    if ($reportType1 == 'AD' || $reportType1 == 'DMM' || $reportType1 == 'DMC' || $reportType1 == 'DRM' || $reportType1 == 'DRC' || $reportType1 == 'DTM' || $reportType1 == 'DTC') {
                        $sql = "SELECT j.jcode, j.jname,
                      us.section_name AS user_section,
                      u.name alloted_to_da,
                      SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) AS diary_no,
                      SUBSTR(m.diary_no, - 4) AS diary_year,
                      DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                      m.reg_no_display,
                      m.pet_name,
                      m.res_name,
                      m.mf_active,
                     h.next_dt,
                     h.brd_slno,
                     r.courtno,

                                    CASE
                                        WHEN
                                            (m.conn_key = 0 OR m.conn_key IS NULL
                                                OR m.conn_key = m.diary_no)
                                        THEN
                                            'M'
                         ELSE CASE
                            WHEN
                               (m.conn_key != 0
                                   AND m.conn_key IS NOT NULL
                                        AND m.conn_key != m.diary_no)
                                            THEN
                                                'C'
                                        END
                                    END AS mainorconn
                    FROM main m INNER JOIN heardt h
                    ON m.diary_no=h.diary_no INNER JOIN dispose d
                    ON m.diary_no=d.diary_no INNER JOIN judge j ON FIND_IN_SET (j.jcode, d.jud_id)=1
                    LEFT JOIN users u ON u.usercode = m.dacode AND u.display = 'Y'
                    LEFT JOIN usersection us ON us.id = u.section
                     LEFT JOIN roster r on h.roster_id=r.id
                    WHERE j.is_retired='N' AND d.ord_dt BETWEEN '" . $fromDate . "' AND '" . $toDate . "'  AND c_status='D'
                    AND h.board_type ='J'  $condition1 $condition2 order by d.ord_dt,r.courtno,h.brd_slno";
                    }
                    $query = $this->db->query($sql);
                }
            default:
                break;
        }
        //echo $sql;



        if ($reportType == 6) {
            $result['other_disposal'] = $query2->getResultArray();
            $result['disposal'] = $query->getResultArray();
            return $result;
        }
        // $query = $builder->getCompiledSelect();
        // pr($query);
        //$query = $builder->get();
        //echo ($query->num_rows());
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function getMainSubjectCategory()
    {
        $builder = $this->db->table('master.submaster')
            ->select(['subcode1', 'sub_name1'])
            ->whereIn('flag_use', ['S', 'L'])
            ->where('display', 'Y')
            ->where('match_id !=', 0)
            ->where('flag', 'S')
            ->groupBy('subcode1, sub_name1')
            ->orderBy('subcode1');
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }










    public function getPrevPendency($prev_date)
    {

        $sql = "SELECT COUNT(diary_no) AS prev_dt_pendency
        FROM (
            SELECT m.diary_no, m.fil_dt, m.c_status, d.rj_dt, d.month, d.year, d.disp_dt, m.active_casetype_id, m.casetype_id
            FROM main m
            LEFT JOIN heardt h ON m.diary_no = h.diary_no
            LEFT JOIN dispose d ON m.diary_no = d.diary_no
            LEFT JOIN restored r ON m.diary_no = r.diary_no
            WHERE (
                CASE 
                    WHEN r.disp_dt IS NOT NULL AND r.conn_next_dt IS NOT NULL THEN
                        NOT ('$prev_date' BETWEEN r.disp_dt AND r.conn_next_dt)
                    ELSE
                        r.disp_dt IS NULL OR r.conn_next_dt IS NULL
                END
                OR r.fil_no IS NULL
            )
            AND (
                CASE 
                    WHEN m.unreg_fil_dt IS NOT NULL AND m.unreg_fil_dt <= m.fil_dt THEN
                        m.unreg_fil_dt <= '$prev_date'
                    ELSE
                        m.fil_dt <= '$prev_date'
                END
            )
            AND (
                m.c_status = 'P'
                OR (
                    m.c_status = 'D'
                    AND (
                        CASE 
                            WHEN d.rj_dt IS NOT NULL THEN
                                d.rj_dt >= '$prev_date' AND d.rj_dt >= '1950-01-01' AND d.rj_dt <= CURRENT_DATE
                            WHEN d.disp_dt IS NOT NULL THEN
                                d.disp_dt >= '$prev_date' AND d.disp_dt >= '1950-01-01' AND d.disp_dt <= CURRENT_DATE
                            ELSE
                                TO_DATE(d.year || '-' || LPAD(d.month::TEXT, 2, '0') || '-01', 'YYYY-MM-DD') >= '$prev_date' AND d.disp_dt >= '1950-01-01' AND d.disp_dt <= CURRENT_DATE
                        END
                    )
                    AND (
                        CASE 
                            WHEN m.unreg_fil_dt IS NOT NULL AND m.unreg_fil_dt <= m.fil_dt THEN
                                m.unreg_fil_dt <= '$prev_date'
                            ELSE
                                m.fil_dt <= '$prev_date'
                        END
                    )
                    AND (
                        CASE 
                            WHEN r.disp_dt IS NOT NULL AND r.conn_next_dt IS NOT NULL THEN
                                NOT ('$prev_date' BETWEEN r.disp_dt AND r.conn_next_dt)
                            ELSE
                                r.disp_dt IS NULL OR r.conn_next_dt IS NULL
                        END
                    )
                )
            )
            AND (
                SUBSTRING(m.fil_no FROM 1 FOR 2) NOT IN ('39') OR m.fil_no = '' OR m.fil_no IS NULL
            )
            GROUP BY m.diary_no,d.rj_dt,d.month,d.year,d.disp_dt
        ) a";


        $query = $this->db->query($sql);
        return $query->getRow();
    }


    public function getToDatePendency($dt2)
    {

        $sql = "SELECT COUNT(diary_no) AS to_dt_pendency
                    FROM (
                        SELECT m.diary_no, m.fil_dt, m.c_status, d.rj_dt, d.month, d.year, d.disp_dt, m.active_casetype_id, m.casetype_id
                        FROM main m
                        LEFT JOIN heardt h ON m.diary_no = h.diary_no
                        LEFT JOIN dispose d ON m.diary_no = d.diary_no
                        LEFT JOIN restored r ON m.diary_no = r.diary_no
                        WHERE (
                            CASE 
                                WHEN r.disp_dt IS NOT NULL AND r.conn_next_dt IS NOT NULL THEN
                                    NOT ('$dt2' BETWEEN r.disp_dt AND r.conn_next_dt)
                                ELSE
                                    r.disp_dt IS NULL OR r.conn_next_dt IS NULL
                            END
                            OR r.fil_no IS NULL
                        )
                        AND (
                            CASE 
                                WHEN m.unreg_fil_dt IS NOT NULL AND (m.unreg_fil_dt <= m.fil_dt OR m.fil_dt IS NULL) THEN
                                    m.unreg_fil_dt <= '$dt2'
                                ELSE
                                    (m.fil_dt <= '$dt2' AND m.fil_dt IS NOT NULL)
                            END
                        )
                        AND (
                            m.c_status = 'P'
                            OR (
                                m.c_status = 'D'
                                AND (
                                    CASE 
                                        WHEN d.rj_dt IS NOT NULL THEN
                                            d.rj_dt >= '$dt2' AND d.rj_dt >= '1950-01-01' AND d.rj_dt <= CURRENT_DATE
                                        WHEN d.disp_dt IS NOT NULL THEN
                                            d.disp_dt >= '$dt2' AND d.disp_dt >= '1950-01-01' AND d.disp_dt <= CURRENT_DATE
                                        ELSE
                                            TO_DATE(d.year || '-' || LPAD(d.month::TEXT, 2, '0') || '-01', 'YYYY-MM-DD') >= '$dt2'
                                            AND d.disp_dt >= '1950-01-01'
                                            AND d.disp_dt <= CURRENT_DATE
                                    END
                                )
                                AND (
                                    CASE 
                                        WHEN m.unreg_fil_dt IS NOT NULL AND (m.unreg_fil_dt <= m.fil_dt OR m.fil_dt IS NULL) THEN
                                            m.unreg_fil_dt <= '$dt2'
                                        ELSE
                                            (m.fil_dt <= '$dt2' AND m.fil_dt IS NOT NULL)
                                    END
                                )
                                AND (
                                    CASE 
                                        WHEN r.disp_dt IS NOT NULL AND r.conn_next_dt IS NOT NULL THEN
                                            NOT ('$dt2' BETWEEN r.disp_dt AND r.conn_next_dt)
                                        ELSE
                                            r.disp_dt IS NULL OR r.conn_next_dt IS NULL
                                    END
                                )
                            )
                        )
                        AND (
                            SUBSTRING(m.fil_no FROM 1 FOR 2) NOT IN ('39') OR m.fil_no = '' OR m.fil_no IS NULL
                        )
                        GROUP BY m.diary_no,d.rj_dt,d.month,d.year,d.disp_dt
                    ) a";

        $query = $this->db->query($sql);
        return $query->getRow();
    }


    public function getInstCases($dt1, $dt2)
    {

        $sql = "SELECT COUNT(diary_no) AS inst
                FROM (
                    SELECT m.diary_no, m.fil_dt, m.unreg_fil_dt
                    FROM main m
                    WHERE (
                        CASE 
                            WHEN m.unreg_fil_dt IS NOT NULL
                                AND (m.unreg_fil_dt <= m.fil_dt OR m.fil_dt IS NULL)
                            THEN m.unreg_fil_dt BETWEEN DATE '$dt1' AND DATE '$dt2'
                            ELSE m.fil_dt BETWEEN DATE '$dt1' AND DATE '$dt2'
                                AND m.fil_dt IS NOT NULL
                        END
                    )
                    AND (
                        SUBSTRING(m.fil_no FROM 1 FOR 2) NOT IN ('39')
                        OR m.fil_no = ''
                        OR m.fil_no IS NULL
                    )
                    GROUP BY m.diary_no
                ) a";

        $query = $this->db->query($sql);
        return $query->getRow();
    }


    public function getDisposedCases($dt1, $dt2)
    {

        $sql = "SELECT COUNT(diary_no) AS dispose
                    FROM (
                        SELECT 
                            CASE 
                                WHEN unreg_fil_dt IS NOT NULL 
                                    AND unreg_fil_dt <= m.fil_dt 
                                THEN 'u'
                                ELSE 'r'
                            END AS reg_type,
                            unreg_fil_dt,
                            fil_dt,
                            d.diary_no,
                            d.fil_no,
                            d.month,
                            d.year,
                            d.disp_dt,
                            d.disp_type,
                            d.rj_dt
                        FROM dispose d
                        INNER JOIN main m ON m.diary_no = d.diary_no
                        WHERE 
                            (
                                SUBSTRING(m.fil_no FROM 1 FOR 2) NOT IN ('39')
                                OR m.fil_no = ''
                                OR m.fil_no IS NULL
                            )
                            AND (
                                CASE 
                                    WHEN d.rj_dt IS NOT NULL 
                                    THEN d.rj_dt BETWEEN DATE '$dt1' AND DATE '$dt2'
                                    ELSE d.disp_dt BETWEEN DATE '$dt1' AND DATE '$dt2'
                                END
                            )
                            AND (
                                CASE 
                                    WHEN unreg_fil_dt IS NOT NULL 
                                        AND unreg_fil_dt <= m.fil_dt 
                                    THEN TRUE
                                    ELSE m.fil_dt IS NOT NULL
                                END
                            )
                    ) a";

        $query = $this->db->query($sql);
        return $query->getRow();
    }

    public function getPendencyCases($dt2)
    {
        $sql = "SELECT COUNT(diary_no) AS pendency
                    FROM (
                        SELECT 
                            CASE 
                                WHEN unreg_fil_dt IS NOT NULL AND unreg_fil_dt <= m.fil_dt 
                                THEN 'u' 
                                ELSE 'r' 
                            END AS reg_type,
                            unreg_fil_dt,
                            m.diary_no,
                            m.fil_dt,
                            c_status,
                            d.rj_dt,
                            d.month,
                            d.year,
                            d.disp_dt,
                            active_casetype_id,
                            casetype_id
                        FROM main m
                        LEFT JOIN heardt h ON m.diary_no = h.diary_no
                        LEFT JOIN dispose d ON m.diary_no = d.diary_no
                        LEFT JOIN restored r ON m.diary_no = r.diary_no
                        WHERE (
                            CASE 
                                WHEN unreg_fil_dt IS NOT NULL AND 
                                    (unreg_fil_dt <= m.fil_dt OR m.fil_dt IS NULL) 
                                THEN unreg_fil_dt <= CURRENT_DATE
                                ELSE m.fil_dt IS NOT NULL AND m.fil_dt <= CURRENT_DATE
                            END
                        )
                        AND c_status = 'P'
                        AND (
                            SUBSTRING(m.fil_no FROM 1 FOR 2) NOT IN ('39')
                            OR m.fil_no = ''
                            OR m.fil_no IS NULL
                        )
                        GROUP BY m.diary_no,d.rj_dt,d.month,d.year,d.disp_dt
                    ) a";

        $query = $this->db->query($sql);
        return $query->getRow();
    }

    public function getPending($for_date)
    {
        $sql_bifurcation = "SELECT 
    COUNT(DISTINCT diary_no) AS pending,
    SUM(CASE WHEN mf_active != 'F' THEN 1 ELSE 0 END) AS misc_pending,
    SUM(CASE WHEN mf_active != 'F' AND main_supp_flag IN (0,1,2) AND board_type IN ('J','S','C','R') THEN 1 ELSE 0 END) AS complete,
    SUM(CASE WHEN mf_active != 'F' AND main_supp_flag IN (0,1,2) AND board_type IN ('J','S') THEN 1 ELSE 0 END) AS complete_court,
    SUM(CASE WHEN mf_active != 'F' AND main_supp_flag IN (0,1,2) AND board_type = 'C' THEN 1 ELSE 0 END) AS complete_chamber,
    SUM(CASE WHEN mf_active != 'F' AND main_supp_flag IN (0,1,2) AND board_type = 'R' THEN 1 ELSE 0 END) AS complete_registrar,
    SUM(CASE WHEN mf_active != 'F' AND main_supp_flag IN (0,1,2) AND board_type = 'C' THEN 1 ELSE 0 END) AS incomplete_chamber,
    SUM(CASE WHEN mf_active != 'F' AND main_supp_flag IN (0,1,2) AND board_type = 'R' THEN 1 ELSE 0 END) AS incomplete_registrar,
    SUM(CASE WHEN mf_active != 'F' AND NOT (main_supp_flag IN (0,1,2) AND board_type IN ('J','S','C','R')) THEN 1 ELSE 0 END) AS misc_incomplete_not_updated,
    SUM(CASE WHEN mf_active != 'F' AND (NOT (main_supp_flag IN (0,1,2) AND board_type IN ('J','S','C','R')) OR (main_supp_flag IN (0,1,2) AND board_type IN ('R','C'))) THEN 1 ELSE 0 END) AS misc_incomplete,
    SUM(CASE WHEN mf_active = 'F' THEN 1 ELSE 0 END) AS final_pending,
    SUM(CASE WHEN mf_active = 'F' AND main_supp_flag IN (0,1,2) THEN 1 ELSE 0 END) AS ready,
    SUM(CASE WHEN mf_active = 'F' AND NOT (main_supp_flag IN (0,1,2) AND board_type IN ('J','S','C','R')) THEN 1 ELSE 0 END) AS final_not_ready,
    SUM(CASE WHEN (case_grp = 'C' OR case_grp IS NULL) THEN 1 ELSE 0 END) AS civil_pendency,
    SUM(CASE WHEN case_grp = 'R' THEN 1 ELSE 0 END) AS criminal_pendency,
    SUM(CASE WHEN fil_dt < CURRENT_DATE - INTERVAL '1 year' THEN 1 ELSE 0 END) AS more_than_one_year_old,
    SUM(CASE WHEN fil_dt >= CURRENT_DATE - INTERVAL '1 year' THEN 1 ELSE 0 END) AS less_than_one_year_old,
    SUM(CASE WHEN fil_dt < CURRENT_DATE - INTERVAL '5 years' THEN 1 ELSE 0 END) AS more_than_five_year_old,
    SUM(CASE WHEN fil_dt < CURRENT_DATE - INTERVAL '10 years' THEN 1 ELSE 0 END) AS more_than_ten_year_old,
    SUM(CASE WHEN fil_dt < CURRENT_DATE - INTERVAL '15 years' THEN 1 ELSE 0 END) AS more_than_fifteen_year_old,
    SUM(CASE WHEN fil_dt < CURRENT_DATE - INTERVAL '20 years' THEN 1 ELSE 0 END) AS more_than_twenty_year_old
FROM (
    SELECT DISTINCT 
        m.diary_no, m.fil_dt, m.mf_active, h.main_supp_flag, h.board_type, m.case_grp
    FROM main m
    LEFT JOIN heardt h ON m.diary_no = h.diary_no
    LEFT JOIN dispose d ON m.diary_no = d.diary_no
    LEFT JOIN restored r ON m.diary_no = r.diary_no
    WHERE board_type IN ('J','S','C','R')
        AND (
            CASE 
                WHEN r.disp_dt IS NOT NULL AND r.conn_next_dt IS NOT NULL THEN
                    DATE '$for_date' NOT BETWEEN r.disp_dt AND r.conn_next_dt
                ELSE r.disp_dt IS NULL OR r.conn_next_dt IS NULL
            END
            OR r.fil_no IS NULL
        )
        AND (
            (unreg_fil_dt IS NOT NULL AND 
             (unreg_fil_dt <= m.fil_dt OR m.fil_dt IS NULL ) AND 
             unreg_fil_dt <= DATE '$for_date')
            OR (m.fil_dt <= DATE '$for_date' AND m.fil_dt IS NOT NULL)
        )
        AND (
            c_status = 'P' OR (
                c_status = 'D' AND (
                    (d.rj_dt IS NOT NULL AND d.rj_dt >= DATE '$for_date' AND d.rj_dt >= DATE '1950-01-01' AND d.rj_dt <= CURRENT_DATE)
                    OR (d.disp_dt IS NOT NULL AND d.disp_dt >= DATE '$for_date' AND d.disp_dt >= DATE '1950-01-01' AND d.disp_dt <= CURRENT_DATE)
                    OR (
                        d.year IS NOT NULL AND d.month IS NOT NULL AND 
                        to_date(d.year || '-' || lpad(d.month::text, 2, '0') || '-01', 'YYYY-MM-DD') >= DATE '$for_date'
                        AND d.disp_dt >= DATE '1950-01-01'
                        AND d.disp_dt <= CURRENT_DATE
                    )
                )
            )
        )
        AND (
            CASE 
                WHEN r.disp_dt IS NOT NULL AND r.conn_next_dt IS NOT NULL THEN
                    DATE '$for_date' NOT BETWEEN r.disp_dt AND r.conn_next_dt
                ELSE r.disp_dt IS NULL OR r.conn_next_dt IS NULL
            END
        )
        AND (
            SUBSTRING(m.fil_no FROM 1 FOR 2) NOT IN ('39') OR m.fil_no = '' OR m.fil_no IS NULL
        )
) temp";
        $query = $this->db->query($sql_bifurcation);
        return $query->getRowArray();
    }

    public function getTotConstitution($for_date)
    {
        $sqlConstitution = "SELECT 
                            COUNT(DISTINCT m.diary_no) AS tot_constitution,

                            COUNT(DISTINCT CASE 
                                WHEN (
                                    (m.conn_key IS NULL OR m.conn_key = '' OR m.conn_key = '0') 
                                    OR (m.conn_key ~ '^\d+$' AND m.conn_key::bigint = m.diary_no::bigint)
                                ) THEN m.diary_no 
                                ELSE NULL 
                            END) AS main_constitution,

                            COUNT(DISTINCT CASE 
                                WHEN (
                                    m.conn_key IS NOT NULL AND m.conn_key != '' AND m.conn_key != '0' 
                                    AND m.conn_key ~ '^\d+$' AND m.conn_key::bigint != m.diary_no::bigint
                                ) THEN m.diary_no 
                                ELSE NULL 
                            END) AS connected_constitution

                        FROM main m
                        INNER JOIN heardt h ON m.diary_no = h.diary_no
                        INNER JOIN mul_category mcat ON m.diary_no = mcat.diary_no
                        INNER JOIN master.submaster s ON mcat.submaster_id = s.id
                        WHERE m.c_status = 'P'
                        AND mcat.display = 'Y'
                        AND s.display = 'Y'
                        -- AND mcat.e_date <= DATE '2025-05-02'
                        AND s.subcode1 IN (20, 21, 22, 23)";
        $query = $this->db->query($sqlConstitution);
        return $query->getRowArray();
    }

    public function getReferred($for_date)
    {
        $sqlReferred = "SELECT 
                    COUNT(DISTINCT m.diary_no) AS referred
                FROM main m
                INNER JOIN case_remarks_multiple mcat ON m.diary_no = mcat.diary_no
                WHERE m.c_status = 'P'
                AND mcat.e_date <= DATE '$for_date'
                AND mcat.r_head = 174";
        $query = $this->db->query($sqlReferred);
        return $query->getRowArray();
    }

    public function getPendingConnected($for_date)
    {
        $sql_connected = "SELECT 
                            SUM(CASE 
                                WHEN (conn_key ~ '^\d+$' AND diary_no::bigint != conn_key::bigint AND conn_key::bigint > 0) THEN 1 
                                ELSE 0 
                            END) AS pending_connected,
                            
                            SUM(CASE 
                                WHEN (conn_key IS NULL OR conn_key = '' OR conn_key = '0' 
                                    OR (conn_key ~ '^\d+$' AND diary_no::bigint = conn_key::bigint)) THEN 1 
                                ELSE 0 
                            END) AS pending_main
                        FROM (
                            SELECT DISTINCT diary_no, mf_active, main_supp_flag, board_type, case_grp, fil_dt, conn_key
                            FROM (
                                SELECT 
                                    m.diary_no,
                                    m.fil_dt,
                                    m.c_status,
                                    d.rj_dt,
                                    d.month,
                                    d.year,
                                    d.disp_dt,
                                    m.active_casetype_id,
                                    m.casetype_id,
                                    m.mf_active,
                                    h.main_supp_flag,
                                    h.board_type,
                                    m.case_grp,
                                    m.conn_key
                                FROM main m
                                LEFT JOIN heardt h ON m.diary_no = h.diary_no
                                LEFT JOIN dispose d ON m.diary_no = d.diary_no
                                LEFT JOIN restored r ON m.diary_no = r.diary_no
                                WHERE board_type IN ('J', 'S', 'C', 'R')
                                
                                AND (
                                    (r.disp_dt IS NOT NULL AND r.conn_next_dt IS NOT NULL AND DATE '$for_date' NOT BETWEEN r.disp_dt AND r.conn_next_dt)
                                    OR r.disp_dt IS NULL OR r.conn_next_dt IS NULL OR r.fil_no IS NULL
                                )
                                
                                AND (
                                    (m.unreg_fil_dt IS NOT NULL AND (m.unreg_fil_dt <= m.fil_dt OR m.fil_dt IS NULL) AND m.unreg_fil_dt <= DATE '$for_date')
                                    OR (m.fil_dt IS NOT NULL AND m.fil_dt <= DATE '$for_date')
                                )
                                
                                AND (
                                    m.c_status = 'P'
                                    OR (
                                        m.c_status = 'D' AND (
                                            (d.rj_dt IS NOT NULL AND d.rj_dt >= DATE '$for_date' AND d.rj_dt >= DATE '1950-01-01' AND d.rj_dt <= CURRENT_DATE)
                                            OR (d.disp_dt IS NOT NULL AND d.disp_dt >= DATE '$for_date' AND d.disp_dt >= DATE '1950-01-01' AND d.disp_dt <= CURRENT_DATE)
                                            OR (to_date(d.year || '-' || LPAD(d.month::text, 2, '0') || '-01', 'YYYY-MM-DD') >= DATE '$for_date' AND d.disp_dt >= DATE '1950-01-01' AND d.disp_dt <= CURRENT_DATE)
                                        )
                                    )
                                )
                                
                                AND (
                                    SUBSTRING(m.fil_no FROM 1 FOR 2) NOT IN ('39') OR m.fil_no IS NULL OR m.fil_no = ''
                                )
                                
                                GROUP BY m.diary_no, m.fil_dt, m.c_status, d.rj_dt, d.month, d.year, d.disp_dt, m.active_casetype_id, m.casetype_id,
                                        m.mf_active, h.main_supp_flag, h.board_type, m.case_grp, m.conn_key
                            ) a
                        ) temp";
        $query = $this->db->query($sql_connected);
        return $query->getRowArray();
    }

    public function getConstitutionBench($for_date)
    {
        $sqlConstitutionBench = "SELECT 
    s.subcode1, 
    s.sub_name1,
    COUNT(DISTINCT m.diary_no) AS tot_constitution,

    COUNT(DISTINCT CASE 
        WHEN (
            (m.conn_key IS NULL OR m.conn_key = '' OR m.conn_key = '0') 
            OR m.conn_key::bigint = m.diary_no::bigint
        )
        THEN m.diary_no 
        ELSE NULL 
    END) AS main_constitution,

    COUNT(DISTINCT CASE 
        WHEN (
            m.conn_key IS NOT NULL 
            AND m.conn_key != '' 
            AND m.conn_key != '0' 
            AND m.conn_key::bigint != m.diary_no::bigint
        )
        THEN m.diary_no 
        ELSE NULL 
    END) AS connected_constitution

FROM main m
INNER JOIN heardt h ON h.diary_no = m.diary_no
INNER JOIN mul_category mcat ON m.diary_no = mcat.diary_no
INNER JOIN master.submaster s ON mcat.submaster_id = s.id

WHERE 
    m.c_status = 'P'
    AND mcat.display = 'Y'
    AND s.display = 'Y'
    AND mcat.e_date <= DATE '$for_date'
    AND s.subcode1 IN (20, 21, 22, 23)

GROUP BY s.subcode1, s.sub_name1
ORDER BY s.subcode1";


        $query = $this->db->query($sqlConstitutionBench);
        return $query->getResultArray();
    }

    public function getRHead($first_date, $last_date)
    {
        $first_date = date('Y-m-d', strtotime($first_date));
        $last_date = date('Y-m-d', strtotime($last_date));
        $sql_notice = "SELECT 
                        crm.r_head,
                        crh.head,
                        COUNT(DISTINCT crm.diary_no) AS tot_cases
                    FROM case_remarks_multiple crm
                    INNER JOIN master.case_remarks_head crh ON crm.r_head = crh.sno
                    WHERE crm.r_head IN (3, 181, 182, 183, 184)
                    AND crm.cl_date BETWEEN DATE '$first_date' AND DATE '$last_date'
                    GROUP BY crm.r_head, crh.head
                    ORDER BY crm.r_head";
        $query = $this->db->query($sql_notice);
        return $query->getResultArray();
    }

    public function getTotMatters($dt1, $first_date, $last_date)
    {
        $dt2 = '';
        // $dt1 = date('Y-m-d', strtotime($dt1));
        $first_date = date('Y-m-d', strtotime($first_date));
        $last_date = date('Y-m-d', strtotime($last_date));

        $sql_inlimine = "SELECT COUNT(1) AS tot_matters 
            FROM main m 
            INNER JOIN (
                SELECT diary_no, COUNT(next_dt) AS no_of_times_listed 
                FROM (
                    SELECT diary_no, next_dt, clno, roster_id, judges 
                    FROM heardt 
                    WHERE clno IS NOT NULL AND clno != 0 
                    AND brd_slno IS NOT NULL AND brd_slno != 0 
                    AND roster_id IS NOT NULL AND roster_id != 0 
                    AND board_type IN ('J', 'S') 
                    AND mainhead = 'M' 
                    AND next_dt = DATE '$dt1'
                    
                    UNION ALL
                    
                    SELECT diary_no, next_dt, clno, roster_id, judges 
                    FROM last_heardt 
                    WHERE clno IS NOT NULL AND clno != 0 
                    AND brd_slno IS NOT NULL AND brd_slno != 0 
                    AND roster_id IS NOT NULL AND roster_id != 0 
                    AND board_type IN ('J', 'S') 
                    AND mainhead = 'M' 
                    AND (bench_flag IS NULL OR bench_flag = '') 
                    AND next_dt BETWEEN DATE '$first_date' AND DATE '$last_date'
                ) listed 
                GROUP BY diary_no
            ) listed_count ON m.diary_no = listed_count.diary_no 
            WHERE m.c_status = 'D' 
            AND no_of_times_listed = 1";


        $query = $this->db->query($sql_inlimine);
        return $query->getRowArray();
    }

    public function pendency_bifurcation_process_detail($flag, $for_date, $subquert1, $headnote1, $first_date_pg, $last_date_pg)
    {
        if ($flag == 'Number_of_Admission_hearing_matters' or $flag == 'complete_court' or $flag == 'misc_incomplete' or $flag == 'incomplete_chamber' or $flag == 'incomplete_registrar' or $flag == 'incomplete_not_updated' or $flag == 'final_pending' or $flag == 'Regular_Ready' or $flag == 'Regular_Not_Ready' or $flag == 'civil_pendency' or $flag == 'criminal_pendency' or $flag == 'more_than_one_year_old' or $flag == 'less_than_one_year_old' or $flag == 'total_pending' or $flag == 'more_than_five_year_old' or $flag == 'more_than_ten_year_old' or $flag == 'more_than_fifteen_year_old' or $flag == 'more_than_twenty_year_old' or $flag == 'Incomplete_Not_Ready') {

            //q1
            $whr = '';
            if (trim($subquert1)) {
                $whr =  ' WHERE ' .  $subquert1;
            }

            $qry = "SELECT DISTINCT 
                        m.diary_no AS Diary_No, 
                        m.reg_no_display AS Case_NO, 
                        pet_name || ' Vs. ' || res_name AS Cause_title
                    FROM (
                        SELECT  
                            m.diary_no,
                            m.fil_dt,
                            c_status,
                            d.rj_dt,
                            d.month,
                            d.year,
                            d.disp_dt,
                            active_casetype_id,
                            casetype_id,
                            m.mf_active,
                            h.main_supp_flag,
                            h.board_type,
                            m.case_grp,
                            reg_no_display,
                            pet_name,
                            res_name
                        FROM main m
                        LEFT JOIN heardt h ON m.diary_no = h.diary_no
                        LEFT JOIN dispose d ON m.diary_no = d.diary_no
                        LEFT JOIN restored r ON m.diary_no = r.diary_no
                        WHERE 
                            board_type IN ('J','S','C','R')
                            AND (
                                (r.disp_dt IS NOT NULL AND r.conn_next_dt IS NOT NULL AND '$for_date'::date NOT BETWEEN r.disp_dt AND r.conn_next_dt)
                                OR r.disp_dt IS NULL OR r.conn_next_dt IS NULL
                            )
                            AND (
                                (m.unreg_fil_dt IS NOT NULL AND m.unreg_fil_dt <= m.fil_dt AND m.unreg_fil_dt <= '$for_date')
                                OR (m.fil_dt IS NOT NULL AND m.fil_dt <= '$for_date')
                            )
                            AND (
                                c_status = 'P' 
                                OR (
                                    c_status = 'D'
                                    AND (
                                        (d.rj_dt IS NOT NULL AND d.rj_dt >= '$for_date' AND d.rj_dt >= '1950-01-01' AND d.rj_dt <= CURRENT_DATE)
                                        OR (d.disp_dt IS NOT NULL AND d.disp_dt >= '$for_date' AND d.disp_dt >= '1950-01-01' AND d.disp_dt <= CURRENT_DATE)
                                        OR(
                                            d.year IS NOT NULL AND d.year > 0 AND 
                                            d.month IS NOT NULL AND d.month BETWEEN 1 AND 12 AND
                                            make_date(d.year, d.month, 1) >= '$for_date' AND 
                                            d.disp_dt IS NOT NULL AND d.disp_dt >= '1950-01-01' AND d.disp_dt <= CURRENT_DATE)
                                    )
                                )
                            )
                            AND (
                                (m.unreg_fil_dt IS NOT NULL AND m.unreg_fil_dt <= m.fil_dt AND m.unreg_fil_dt <= '$for_date')
                                OR (m.fil_dt IS NOT NULL AND m.fil_dt <= '$for_date')
                            )
                            AND (
                                (r.disp_dt IS NOT NULL AND r.conn_next_dt IS NOT NULL AND '$for_date'::date NOT BETWEEN r.disp_dt AND r.conn_next_dt)
                                OR r.disp_dt IS NULL OR r.conn_next_dt IS NULL
                            )
                            AND (
                                substring(m.fil_no FROM 1 FOR 2) NOT IN ('39')
                                OR m.fil_no IS NULL OR m.fil_no = ''
                            )
                        GROUP BY 
                            m.diary_no, m.fil_dt, c_status, d.rj_dt, d.month, d.year, d.disp_dt,
                            active_casetype_id, casetype_id, m.mf_active, h.main_supp_flag, h.board_type,
                            m.case_grp, reg_no_display, pet_name, res_name
                        ORDER BY m.diary_no
                    ) m 
                    $whr";
                    
                    
        }

        if ($flag == 'Total_Connected' or $flag == 'Pendency_after_excluding_connected') {


            $qry = "  SELECT DISTINCT 
                        m.diary_no AS Diary_No, 
                        m.reg_no_display AS Case_NO, 
                        pet_name || ' Vs. ' || res_name AS Cause_title,
                        m.diary_no_rec_date
                    FROM main m
                    LEFT JOIN heardt h ON m.diary_no = h.diary_no
                    LEFT JOIN dispose d ON m.diary_no = d.diary_no
                    LEFT JOIN restored r ON m.diary_no = r.diary_no
                    WHERE 
                        board_type IN ('J','S','C','R')
                        AND (
                        m.diary_no <> CAST(NULLIF(m.conn_key, '') AS BIGINT)
                        AND m.conn_key > '0')

                        AND (
                            (r.disp_dt IS NOT NULL AND r.conn_next_dt IS NOT NULL AND '$for_date'::date NOT BETWEEN r.disp_dt AND r.conn_next_dt)
                            OR r.disp_dt IS NULL OR r.conn_next_dt IS NULL OR r.fil_no IS NULL
                        )

                        AND (
                            (m.unreg_fil_dt IS NOT NULL AND m.unreg_fil_dt <= m.fil_dt AND m.unreg_fil_dt <= '$for_date')
                            OR (m.fil_dt IS NOT NULL AND m.fil_dt <= '$for_date')
                        )

                        AND (
                            c_status = 'P'
                            OR (
                                c_status = 'D'
                                AND (
                                    (d.rj_dt IS NOT NULL AND d.rj_dt >= '$for_date' AND d.rj_dt >= '1950-01-01' AND d.rj_dt <= CURRENT_DATE)
                                    OR (d.disp_dt IS NOT NULL AND d.disp_dt >= '$for_date' AND d.disp_dt >= '1950-01-01' AND d.disp_dt <= CURRENT_DATE)
                                    OR (
                                        d.year IS NOT NULL AND d.year > 0
                                        AND d.month IS NOT NULL AND d.month BETWEEN 1 AND 12
                                        AND make_date(d.year, d.month, 1) >= '$for_date'
                                        AND d.disp_dt IS NOT NULL AND d.disp_dt >= '1950-01-01' AND d.disp_dt <= CURRENT_DATE
                                    )
                                )
                            )
                        )

                        AND (
                            (m.unreg_fil_dt IS NOT NULL AND m.unreg_fil_dt <= m.fil_dt AND m.unreg_fil_dt <= '$for_date')
                            OR (m.fil_dt IS NOT NULL AND m.fil_dt <= '$for_date')
                        )

                        AND (
                            (r.disp_dt IS NOT NULL AND r.conn_next_dt IS NOT NULL AND '$for_date'::date NOT BETWEEN r.disp_dt AND r.conn_next_dt)
                            OR r.disp_dt IS NULL OR r.conn_next_dt IS NULL
                        )

                        AND (
                            substring(m.fil_no FROM 1 FOR 2) NOT IN ('39') 
                            OR m.fil_no IS NULL 
                            OR m.fil_no = ''
                        )
                    GROUP BY m.diary_no, m.reg_no_display, pet_name, res_name
                    ORDER BY m.diary_no_rec_date";
        }
        if ($flag == 'tot_constitution') {
            $qry = " SELECT DISTINCT 
                        m.diary_no AS Diary_No, 
                        m.reg_no_display AS Case_NO, 
                        pet_name || ' Vs. ' || res_name AS Cause_title,
                        m.diary_no_rec_date
                    FROM main m
                    INNER JOIN heardt h ON m.diary_no = h.diary_no 
                    INNER JOIN mul_category mcat ON m.diary_no = mcat.diary_no
                    INNER JOIN master.submaster s ON mcat.submaster_id = s.id
                    WHERE 
                        c_status = 'P' 
                        AND mcat.display = 'Y' 
                        AND s.display = 'Y' 
                        -- AND mcat.e_date <= '$for_date'
                        AND s.subcode1 IN (20, 21, 22, 23)
                    ORDER BY m.diary_no_rec_date";
        }
        if ($flag == 'referred') {

            // q4
            $qry = "SELECT DISTINCT 
                            m.diary_no AS Diary_No, 
                            m.reg_no_display AS Case_NO, 
                            pet_name || ' Vs. ' || res_name AS Cause_title,
                            m.diary_no_rec_date
                        FROM main m
                        INNER JOIN case_remarks_multiple mcat ON m.diary_no = mcat.diary_no
                        WHERE 
                            c_status = 'P' 
                            AND mcat.e_date <= '$for_date'::date
                            AND mcat.r_head = 174
                        ORDER BY m.diary_no_rec_date";
        }
        if ($flag == 'Total_20' or $flag == 'Main_20' or $flag == 'conn_20' or $flag == 'Total_21' or $flag == 'Main_21' or $flag == 'conn_21' or $flag == 'Total_22' or $flag == 'Main_22' or $flag == 'conn_22' or $flag == 'Total_23' or $flag == 'Main_23' or $flag == 'conn_23') {

            // q5
            $qry = "SELECT DISTINCT 
                    m.diary_no AS Diary_No, 
                    m.reg_no_display AS Case_NO, 
                    pet_name || ' Vs. ' || res_name AS Cause_title,
                    m.diary_no_rec_date
                FROM main m
                INNER JOIN heardt h ON h.diary_no = m.diary_no
                INNER JOIN mul_category mcat ON m.diary_no = mcat.diary_no
                INNER JOIN master.submaster s ON mcat.submaster_id = s.id
                WHERE 
                    c_status = 'P' 
                    AND mcat.display = 'Y' 
                    AND s.display = 'Y' 
                    AND mcat.e_date <= '$for_date'::date
                    AND $subquert1
                ORDER BY m.diary_no_rec_date";
               // pr($qry);
        }
        if ($flag == 'Notice_3' or $flag == 'Notice_181' or $flag == 'Notice_182' or $flag == 'Notice_183' or $flag == 'Notice_184') {



            // q6
            $qry = "SELECT DISTINCT 
                    m.diary_no AS Diary_No, 
                    m.reg_no_display AS Case_NO, 
                    pet_name || ' Vs. ' || res_name AS Cause_title,
                    m.diary_no_rec_date
                FROM case_remarks_multiple crm
                INNER JOIN master.case_remarks_head crh ON crm.r_head = crh.sno
                INNER JOIN main m ON m.diary_no = crm.diary_no
                WHERE 
                $subquert1
                ORDER BY m.diary_no_rec_date";
        }
        if ($flag == 'In_Limine') {

            $qry = "SELECT DISTINCT 
                    m.diary_no AS Diary_No, 
                    m.reg_no_display AS Case_NO, 
                    pet_name || ' Vs. ' || res_name AS Cause_title,
                    m.diary_no_rec_date
                FROM main m
                INNER JOIN (
                    SELECT diary_no, COUNT(next_dt) AS no_of_times_listed
                    FROM (
                        SELECT diary_no, next_dt, clno, roster_id, judges
                        FROM heardt
                        WHERE 
                            clno IS NOT NULL AND clno != 0
                            AND brd_slno IS NOT NULL AND brd_slno != 0
                            AND roster_id IS NOT NULL AND roster_id != 0
                            AND board_type IN ('J', 'S')
                            AND mainhead = 'M'
                        
                        UNION
                        
                        SELECT diary_no, next_dt, clno, roster_id, judges
                        FROM last_heardt
                        WHERE 
                            clno IS NOT NULL AND clno != 0
                            AND brd_slno IS NOT NULL AND brd_slno != 0
                            AND roster_id IS NOT NULL AND roster_id != 0
                            AND board_type IN ('J', 'S')
                            AND mainhead = 'M'
                            AND (bench_flag IS NULL OR bench_flag = '')
                            AND next_dt BETWEEN DATE '$first_date_pg' AND DATE '$last_date_pg'
                    ) listed
                    GROUP BY diary_no
                ) listed_count ON m.diary_no = listed_count.diary_no
                WHERE 
                    m.c_status = 'D'
                    AND listed_count.no_of_times_listed = 1
                ORDER BY m.diary_no_rec_date";
        }

        $query = $this->db->query($qry);
        return $query->getResultArray();
    }
}
