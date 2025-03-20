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
                    //echo $sql;

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
                                    AND  $Type
                                    AND $Status
                                    AND $code ";
                    $query = $this->db->query($sql);
                    $result = $query->getResultArray();
                    return $result;
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
                    $sql = "SELECT listed.jcode, listed.jname,listed.Misc_Main AS listed_Misc_Main,listed.Misc_Conn AS listed_Misc_Conn,
            listed.Regular_Main AS listed_Regular_Main,listed.Regular_Conn AS listed_Regular_Conn,
            listed.total_Main AS listed_total_Main,listed.total_Conn AS listed_total_Conn,
            disposed.Misc_Main AS disposed_Misc_Main,disposed.Misc_Conn AS disposed_Misc_Conn,
            disposed.Regular_Main AS disposed_Regular_Main,disposed.Regular_Conn AS disposed_Regular_Conn,
            disposed.total_Main AS disposed_total_Main,disposed.total_Conn AS disposed_total_Conn
            FROM
            (SELECT jcode, jname,
            count(distinct CASE WHEN (m.mf_active='M' AND (m.conn_key=0 OR m.conn_key IS NULL OR m.conn_key=m.diary_no)) THEN m.diary_no END) AS Misc_Main,
            count(distinct CASE WHEN (m.mf_active='M' AND (m.conn_key!=0 AND m.conn_key IS NOT NULL AND m.conn_key!=m.diary_no)) THEN m.diary_no END) AS Misc_Conn,
            count(distinct CASE WHEN (m.mf_active<>'M' AND (m.conn_key=0 OR m.conn_key IS NULL OR m.conn_key=m.diary_no)) THEN m.diary_no END) AS Regular_Main,
            count(distinct CASE WHEN (m.mf_active<>'M' AND (m.conn_key!=0 AND m.conn_key IS NOT NULL AND m.conn_key!=m.diary_no)) THEN m.diary_no END) AS Regular_Conn,
            count(distinct CASE WHEN (m.conn_key=0 OR m.conn_key IS NULL OR m.conn_key=m.diary_no) THEN m.diary_no END) AS total_Main,
            count(distinct CASE WHEN (m.conn_key!=0 AND m.conn_key IS NOT NULL AND m.conn_key!=m.diary_no) THEN m.diary_no END) AS total_Conn FROM main m INNER JOIN
            (SELECT DISTINCT diary_no,next_dt,judges FROM
            (SELECT diary_no,next_dt,judges,board_type FROM heardt WHERE next_dt BETWEEN '" . $from_Date . "' AND '" . $to_Date . "' AND
            clno!=0 AND brd_slno!=0 AND roster_id!=0 AND judges!=0 AND roster_id NOT IN (29,30) AND board_type ='J'
            UNION ALL
            SELECT diary_no,next_dt,judges,board_type FROM last_heardt WHERE next_dt BETWEEN '" . $from_Date . "' AND '" . $to_Date . "'
            AND (bench_flag IS NULL OR bench_flag='') AND clno!=0 AND brd_slno!=0 AND roster_id!=0 AND judges!=0
            AND roster_id NOT IN (29,30) AND board_type ='J')bb) aa
            ON m.diary_no=aa.diary_no
            INNER JOIN judge j ON FIND_IN_SET (j.jcode, aa.judges)=1
            WHERE 1=1  $condition
            GROUP BY jcode, jname) listed
            LEFT JOIN
            (SELECT  jcode, jname,
            count(distinct CASE WHEN (m.mf_active='M' AND (m.conn_key=0 OR m.conn_key IS NULL OR m.conn_key=m.diary_no)) THEN m.diary_no END) AS Misc_Main,
            count(distinct CASE WHEN (m.mf_active='M' AND (m.conn_key!=0 AND m.conn_key IS NOT NULL AND m.conn_key!=m.diary_no)) THEN m.diary_no END) AS Misc_Conn,
            count(distinct CASE WHEN (m.mf_active<>'M' AND (m.conn_key=0 OR m.conn_key IS NULL OR m.conn_key=m.diary_no)) THEN m.diary_no END) AS Regular_Main,
            count(distinct CASE WHEN (m.mf_active<>'M' AND (m.conn_key!=0 AND m.conn_key IS NOT NULL AND m.conn_key!=m.diary_no)) THEN m.diary_no END) AS Regular_Conn,
            count(distinct CASE WHEN (m.conn_key=0 OR m.conn_key IS NULL OR m.conn_key=m.diary_no) THEN m.diary_no END) AS total_Main,
            count(distinct CASE WHEN (m.conn_key!=0 AND m.conn_key IS NOT NULL AND m.conn_key!=m.diary_no) THEN m.diary_no END) AS total_Conn FROM main m left JOIN heardt h
            ON m.diary_no=h.diary_no INNER JOIN dispose d
            ON m.diary_no=d.diary_no INNER JOIN judge j ON FIND_IN_SET (j.jcode, d.jud_id)=1
             WHERE d.ord_dt BETWEEN '" . $from_Date . "' AND '" . $to_Date . "' $condition  AND c_status='D' AND h.board_type ='J'
            GROUP BY jcode, jname) disposed
            ON listed.jcode=disposed.jcode ORDER BY listed.jcode";

                    $sql2 = "SELECT                     
            count(distinct m.diary_no ) AS other_disp FROM main m 
            left JOIN heardt h ON m.diary_no=h.diary_no 
            INNER JOIN dispose d
            ON m.diary_no=d.diary_no INNER JOIN judge j ON FIND_IN_SET (j.jcode, d.jud_id)=1
             WHERE d.ord_dt BETWEEN '" . $from_Date . "' AND '" . $to_Date . "'  AND c_status='D' AND (h.board_type !='J' OR h.diary_no is NULL)";
                    $query2 = $this->db->query($sql2);

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
                }
            default:
                break;
        }
        //echo $sql;

        // $query = $this->db->query($sql);

        // if ($reportType == 6) {
        //     $result['other_disposal'] = $query2->result_array();
        //     $result['disposal'] = $query->result_array();
        //     return $result;
        // }
        // $query = $builder->getCompiledSelect();
        // pr($query);
        $query = $builder->get();
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
        $sql = "SELECT COUNT(m.diary_no) as prev_dt_pendency
            FROM main m
            LEFT JOIN heardt h ON m.diary_no = h.diary_no
            LEFT JOIN dispose d ON m.diary_no = d.diary_no
            LEFT JOIN restored r ON m.diary_no = r.diary_no
            WHERE (
                CASE
                    WHEN r.disp_dt IS NOT NULL AND r.conn_next_dt IS NOT NULL
                         AND '$prev_date' NOT BETWEEN r.disp_dt AND r.conn_next_dt
                    THEN true
                    ELSE r.disp_dt IS NULL OR r.conn_next_dt IS NULL
                END OR r.fil_no IS NULL
            )
            AND (
                (r.unreg_fil_dt IS NOT NULL AND r.unreg_fil_dt <= m.fil_dt) 
                OR 
                (m.fil_dt IS NULL OR m.fil_dt <= '$prev_date')
            )
            AND m.c_status = 'P'
            GROUP BY m.diary_no";

        $query = $this->db->query($sql);
        return $query->getRow();
    }


    public function getToDatePendency($dt2)
    {
        $sql = "SELECT COUNT(m.diary_no) as to_dt_pendency
                FROM main m
                LEFT JOIN heardt h ON m.diary_no = h.diary_no
                LEFT JOIN dispose d ON m.diary_no = d.diary_no
                LEFT JOIN restored r ON m.diary_no = r.diary_no
                WHERE (
                    CASE
                        WHEN r.disp_dt IS NOT NULL AND r.conn_next_dt IS NOT NULL
                             AND r.disp_dt <= '$dt2' AND r.conn_next_dt >= '$dt2'
                        THEN FALSE
                        ELSE r.disp_dt IS NULL OR r.conn_next_dt IS NULL
                    END OR r.fil_no IS NULL
                )
                AND (
                    (r.unreg_fil_dt IS NOT NULL AND r.unreg_fil_dt <= '$dt2') 
                    OR (r.unreg_fil_dt IS NULL AND (m.fil_dt <= '$dt2' OR m.fil_dt IS NULL))
                )
                AND m.c_status = 'P'
                GROUP BY m.diary_no";

        $query = $this->db->query($sql);
        return $query->getRow();
    }


    public function getInstCases($dt1, $dt2)
    {
        $sql = "SELECT COUNT(m.diary_no) as inst
            FROM main m
            WHERE (
                DATE(m.unreg_fil_dt) BETWEEN '$dt1' AND '$dt2'
                OR DATE(m.fil_dt) BETWEEN '$dt1' AND '$dt2'
            )
            AND (
                SUBSTRING(m.fil_no, 1, 2) NOT IN ('39') OR m.fil_no = '' OR m.fil_no IS NULL
            )
            GROUP BY m.diary_no";

        $query = $this->db->query($sql);
        return $query->getRow();
    }


    public function getDisposedCases($dt1, $dt2)
    {
        $sql = "SELECT COUNT(diary_no) as dispose
                FROM dispose d
                INNER JOIN main m ON m.diary_no = d.diary_no
                WHERE (
                    SUBSTRING(m.fil_no, 1, 2) NOT IN (39)
                    OR m.fil_no = '' OR m.fil_no IS NULL
                )
                AND (
                    DATE(d.rj_dt) BETWEEN '$dt1' AND '$dt2'
                    OR DATE(d.disp_dt) BETWEEN '$dt1' AND '$dt2'
                )
                GROUP BY d.diary_no";

        $query = $this->db->query($sql);
        return $query->getRow();
    }

    public function getPendencyCases($dt2)
    {
        $sql = "SELECT COUNT(diary_no) as pendency
                FROM main m
                WHERE DATE(unreg_fil_dt) <= '$dt2'
                AND m.c_status = 'P'
                AND (
                    SUBSTRING(m.fil_no, 1, 2) NOT IN (39)
                    OR m.fil_no = '' OR m.fil_no IS NULL
                )
                GROUP BY m.diary_no";

        $query = $this->db->query($sql);
        return $query->getRow();
    }
}
