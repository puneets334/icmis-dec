<?php

namespace App\Models\Reports\DefaultReports;

use CodeIgniter\Model;

class DefaultReportsModel extends Model
{
    protected $eservicesdb;

    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
        $this->eservicesdb = \Config\Database::connect('eservices');
    }

    public function cases_not_verified()
    {
        $queryString = "SELECT 
        COUNT(DISTINCT m.diary_no) AS total, 
        COUNT(DISTINCT CASE WHEN m.diary_no_rec_date > '2018-05-31' THEN m.diary_no END) AS filed_after_may_2018,
        COUNT(DISTINCT CASE WHEN m.diary_no_rec_date < '2014-08-18' THEN m.diary_no END) AS filed_before_18_08_2014,
        COUNT(DISTINCT CASE WHEN m.diary_no_rec_date >= '2014-08-18' AND m.diary_no_rec_date <= '2018-05-31' THEN m.diary_no END) AS filed_after_18_08_2014_before_may_2018,
        COUNT(DISTINCT CASE WHEN m.diary_no_rec_date >= '2014-08-18' AND m.diary_no_rec_date <= '2018-05-31' AND os.diary_no IS NULL THEN m.diary_no END) AS defect_not_notified,
        COUNT(DISTINCT CASE WHEN m.diary_no_rec_date >= '2014-08-18' AND m.diary_no_rec_date <= '2018-05-31'
            AND EXTRACT(DAY FROM (CURRENT_DATE - os.save_dt)) BETWEEN 60 AND 90 AND os.display = 'Y' 
            AND os.rm_dt IS NULL AND os.diary_no IS NOT NULL THEN m.diary_no END) AS delay_in_refiling_60,
        COUNT(DISTINCT CASE WHEN m.diary_no_rec_date >= '2014-08-18' AND m.diary_no_rec_date <= '2018-05-31'
            AND EXTRACT(DAY FROM (CURRENT_DATE - os.save_dt)) > 90 AND os.display = 'Y' 
            AND os.rm_dt IS NULL AND os.diary_no IS NOT NULL THEN m.diary_no END) AS delay_in_refiling_greater_than_90,
        COUNT(DISTINCT CASE WHEN m.diary_no_rec_date >= '2014-08-18' AND m.diary_no_rec_date <= '2018-05-31'
            AND EXTRACT(DAY FROM (CURRENT_DATE - os.save_dt)) < 60 AND os.display = 'Y' 
            AND os.rm_dt IS NULL AND os.diary_no IS NOT NULL THEN m.diary_no END) AS delay_in_refiling_less_than_60
        FROM main m
        LEFT JOIN obj_save os ON m.diary_no = os.diary_no 
        WHERE m.c_status = 'P' 
        AND m.diary_no NOT IN (SELECT diary_no FROM physical_verify)";

        $sql_bifurcation = $this->db->query($queryString);
        if($sql_bifurcation->getNumRows() >= 1)
        {
            $result_bifurcation = $sql_bifurcation->getRowArray();
            return $result_bifurcation;
        }
        else
        {
            return [];
        }
    }


    public function cases_not_verified_details()
    {
        $reportType = $_GET['reportType'];
        $sql = "";
        $reportHeading = "";
        $returnArr = [];
        switch($reportType)
        {
            case 'A':
            {
                /*$sql = "select distinct concat(concat(SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4),' / ',SUBSTR(m.diary_no, - 4)),case when (m.reg_no_display is not null and trim(m.reg_no_display)!='') then concat(' @ ',m.reg_no_display) else '' end) as case_no,
                      concat(m.pet_name,' Vs. ',m.res_name) as cause_title,date(m.diary_no_rec_date) as filing_date from main m
                      left join obj_save os on m.diary_no=os.diary_no where m.c_status='P' 
                      and m.diary_no not in (select diary_no from physical_verify)
                      order by SUBSTR(m.diary_no, - 4),SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4)";*/

                $sql = "SELECT DISTINCT 
                    CONCAT(
                        SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4), 
                        ' / ', 
                        SUBSTRING(CAST(m.diary_no AS TEXT) FROM -4 FOR 4)
                    ) 
                    || CASE 
                            WHEN (m.reg_no_display IS NOT NULL AND TRIM(m.reg_no_display) != '') 
                            THEN ' @ ' || m.reg_no_display 
                            ELSE '' 
                        END AS case_no,
                    m.pet_name || ' Vs. ' || m.res_name AS cause_title,
                    m.diary_no_rec_date::DATE AS filing_date
                FROM 
                    main m
                LEFT JOIN 
                    obj_save os ON m.diary_no = os.diary_no
                WHERE 
                    m.c_status = 'P' 
                    AND m.diary_no NOT IN (SELECT diary_no FROM physical_verify)
                ORDER BY 
                filing_date ASC;
                    -- SUBSTRING(CAST(m.diary_no AS TEXT) FROM -4 FOR 4),
                    -- SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4)";
                $reportHeading = "Total No. of Matters not Verfied by Branches and Pending in ICMIS";
                break;
            }
            case 'B':
            {
                /*$sql="select distinct concat(concat(SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4),' / ',SUBSTR(m.diary_no, - 4)),case when (m.reg_no_display is not null and trim(m.reg_no_display)!='') then concat(' @ ',m.reg_no_display) else '' end) as case_no,
                      concat(m.pet_name,' Vs. ',m.res_name) as cause_title,date(m.diary_no_rec_date) as filing_date from main m
                      left join obj_save os on m.diary_no=os.diary_no where m.c_status='P' 
                      and m.diary_no not in (select diary_no from physical_verify) and date(diary_no_rec_date)>'2018-05-31'
                      order by SUBSTR(m.diary_no, - 4),SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4)";*/

                $sql = "
                    SELECT DISTINCT 
                        CONCAT(
                            SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4), 
                            ' / ', 
                            SUBSTRING(CAST(m.diary_no AS TEXT) FROM -4 FOR 4)
                        ) 
                        || CASE 
                                WHEN (m.reg_no_display IS NOT NULL AND TRIM(m.reg_no_display) != '') 
                                THEN ' @ ' || m.reg_no_display 
                                ELSE '' 
                            END AS case_no,
                        m.pet_name || ' Vs. ' || m.res_name AS cause_title,
                        m.diary_no_rec_date::DATE AS filing_date
                    FROM 
                        main m
                    LEFT JOIN 
                        obj_save os ON m.diary_no = os.diary_no
                    WHERE 
                        m.c_status = 'P' 
                        AND m.diary_no NOT IN (SELECT diary_no FROM physical_verify)
                        AND m.diary_no_rec_date::DATE > '2018-05-31'
                    ORDER BY 
                    filing_date ASC
                        -- SUBSTRING(CAST(m.diary_no AS TEXT) FROM -4 FOR 4),
                        -- SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4)";
                $reportHeading="Total No. of Matters filed (after  Last Verification Date viz. May-2018)";
                break;
            }
            case 'C':
            {
                /*$sql="select distinct concat(concat(SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4),' / ',SUBSTR(m.diary_no, - 4)),case when (m.reg_no_display is not null and trim(m.reg_no_display)!='') then concat(' @ ',m.reg_no_display) else '' end) as case_no,
                      concat(m.pet_name,' Vs. ',m.res_name) as cause_title,date(m.diary_no_rec_date) as filing_date from main m
                      left join obj_save os on m.diary_no=os.diary_no where m.c_status='P' and m.diary_no not in (select diary_no from physical_verify) and date(diary_no_rec_date)<'2014-08-18'
                      order by SUBSTR(m.diary_no, - 4),SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4)";*/

                $sql = "
                    SELECT DISTINCT 
                        CONCAT(
                            SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4), 
                            ' / ', 
                            SUBSTRING(CAST(m.diary_no AS TEXT) FROM -4 FOR 4)
                        ) 
                        || CASE 
                                WHEN (m.reg_no_display IS NOT NULL AND TRIM(m.reg_no_display) != '') 
                                THEN ' @ ' || m.reg_no_display 
                                ELSE '' 
                            END AS case_no,
                        m.pet_name || ' Vs. ' || m.res_name AS cause_title,
                        m.diary_no_rec_date::DATE AS filing_date
                    FROM 
                        main m
                    LEFT JOIN 
                        obj_save os ON m.diary_no = os.diary_no
                    WHERE 
                        m.c_status = 'P' 
                        AND m.diary_no NOT IN (SELECT diary_no FROM physical_verify)
                        AND m.diary_no_rec_date::DATE < '2014-08-18'
                    ORDER BY 
                    filing_date ASC
                        --SUBSTRING(CAST(m.diary_no AS TEXT) FROM -4 FOR 4),
                        --SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4)";
                $reportHeading="Total No. of Matters filed before 18.08.2014";
                break;
            }
            case 'D':
            {
                /*$sql="select distinct concat(concat(SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4),' / ',SUBSTR(m.diary_no, - 4)),case when (m.reg_no_display is not null and trim(m.reg_no_display)!='') then concat(' @ ',m.reg_no_display) else '' end) as case_no,
                      concat(m.pet_name,' Vs. ',m.res_name) as cause_title,date(m.diary_no_rec_date) as filing_date from main m
                      left join obj_save os on m.diary_no=os.diary_no where m.c_status='P' and m.diary_no not in (select diary_no from physical_verify) and date(diary_no_rec_date)>='2014-08-18' and date(diary_no_rec_date)<='2018-05-31'
                      order by SUBSTR(m.diary_no, - 4),SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4)";*/

                $sql = "
                    SELECT DISTINCT 
                        CONCAT(
                            SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4), 
                            ' / ', 
                            SUBSTRING(CAST(m.diary_no AS TEXT) FROM -4 FOR 4)
                        ) 
                        || CASE 
                                WHEN (m.reg_no_display IS NOT NULL AND TRIM(m.reg_no_display) != '') 
                                THEN ' @ ' || m.reg_no_display 
                                ELSE '' 
                            END AS case_no,
                        m.pet_name || ' Vs. ' || m.res_name AS cause_title,
                        m.diary_no_rec_date::DATE AS filing_date
                    FROM 
                        main m
                    LEFT JOIN 
                        obj_save os ON m.diary_no = os.diary_no
                    WHERE 
                        m.c_status = 'P' 
                        AND m.diary_no NOT IN (SELECT diary_no FROM physical_verify)
                        AND m.diary_no_rec_date::DATE >= '2014-08-18'
                        AND m.diary_no_rec_date::DATE <= '2018-05-31'
                    ORDER BY 
                    filing_date ASC
                        --SUBSTRING(CAST(m.diary_no AS TEXT) FROM -4 FOR 4),
                        --SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4)";
                $reportHeading="Total No. of Matters filed after 18.08.2014 and Before May-2018";
                break;
            }
            case "E1":
            {
                /*$sql="select distinct concat(concat(SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4),' / ',SUBSTR(m.diary_no, - 4)),case when (m.reg_no_display is not null and trim(m.reg_no_display)!='') then concat(' @ ',m.reg_no_display) else '' end) as case_no,
                      concat(m.pet_name,' Vs. ',m.res_name) as cause_title,date(m.diary_no_rec_date) as filing_date from main m
                      left join obj_save os on m.diary_no=os.diary_no where m.c_status='P' 
                      and m.diary_no not in (select diary_no from physical_verify) and date(diary_no_rec_date)>='2014-08-18' and date(diary_no_rec_date)<='2018-05-31'
                      and os.diary_no is null order by SUBSTR(m.diary_no, - 4),SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4)";*/

                $sql = "
                    SELECT DISTINCT 
                        CONCAT(
                            SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4), 
                            ' / ', 
                            SUBSTRING(CAST(m.diary_no AS TEXT) FROM -4 FOR 4)
                        ) 
                        || CASE 
                                WHEN (m.reg_no_display IS NOT NULL AND TRIM(m.reg_no_display) != '') 
                                THEN ' @ ' || m.reg_no_display 
                                ELSE '' 
                            END AS case_no,
                        m.pet_name || ' Vs. ' || m.res_name AS cause_title,
                        m.diary_no_rec_date::DATE AS filing_date
                    FROM 
                        main m
                    LEFT JOIN 
                        obj_save os ON m.diary_no = os.diary_no
                    WHERE 
                        m.c_status = 'P' 
                        AND m.diary_no NOT IN (SELECT diary_no FROM physical_verify)
                        AND m.diary_no_rec_date::DATE >= '2014-08-18'
                        AND m.diary_no_rec_date::DATE <= '2018-05-31'
                        AND os.diary_no IS NULL
                    ORDER BY 
                    filing_date ASC
                        --SUBSTRING(CAST(m.diary_no AS TEXT) FROM -4 FOR 4),
                        --SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4)";
                $reportHeading="Total No. of Matters filed after 18.08.2014 and Before May-2018 (Defects Not Notified (in process in 1B))";
                break;
            }
            case "E2":
            {
                /*$sql="select distinct concat(concat(SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4),' / ',SUBSTR(m.diary_no, - 4)),case when (m.reg_no_display is not null and trim(m.reg_no_display)!='') then concat(' @ ',m.reg_no_display) else '' end) as case_no,
                      concat(m.pet_name,' Vs. ',m.res_name) as cause_title,date(m.diary_no_rec_date) as filing_date from main m
                      left join obj_save os on m.diary_no=os.diary_no where m.c_status='P' 
                      and m.diary_no not in (select diary_no from physical_verify) and date(diary_no_rec_date)>='2014-08-18' and date(diary_no_rec_date)<='2018-05-31'
                      and datediff(current_date(), date(os.save_dt))>= 60 and datediff(current_date(), date(os.save_dt))<=90 and os.display='Y' and (os.rm_dt is null or os.rm_dt='0000-00-00') and os.diary_no is not null
                      order by SUBSTR(m.diary_no, - 4),SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4)";*/

                $sql = "
                SELECT DISTINCT 
                    CONCAT(
                        SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4), 
                        ' / ', 
                        SUBSTRING(CAST(m.diary_no AS TEXT) FROM -4 FOR 4)
                    ) 
                    || CASE 
                            WHEN (m.reg_no_display IS NOT NULL AND TRIM(m.reg_no_display) != '') 
                            THEN ' @ ' || m.reg_no_display 
                            ELSE '' 
                        END AS case_no,
                    m.pet_name || ' Vs. ' || m.res_name AS cause_title,
                    m.diary_no_rec_date::DATE AS filing_date
                FROM 
                    main m
                LEFT JOIN 
                    obj_save os ON m.diary_no = os.diary_no
                WHERE 
                    m.c_status = 'P' 
                    AND m.diary_no NOT IN (SELECT diary_no FROM physical_verify)
                    AND m.diary_no_rec_date::DATE >= '2014-08-18'
                    AND m.diary_no_rec_date::DATE <= '2018-05-31'
                    AND EXTRACT(DAY FROM CURRENT_DATE - os.save_dt) >= 60
                    AND EXTRACT(DAY FROM CURRENT_DATE - os.save_dt) <= 90
                    AND os.display = 'Y'
                    AND (os.rm_dt IS NULL OR os.rm_dt = '0001-01-01')
                    AND os.diary_no IS NOT NULL
                ORDER BY 
                filing_date ASC
                    --SUBSTRING(CAST(m.diary_no AS TEXT) FROM -4 FOR 4),
                    --SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4)";
                $reportHeading="Total No. of Matters filed after 18.08.2014 and Before May-2018 (Delay in Refiling > 60 and < 90)";
                break;
            }
            case "E3":
            {
                /*$sql="select distinct concat(concat(SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4),' / ',SUBSTR(m.diary_no, - 4)),case when (m.reg_no_display is not null and trim(m.reg_no_display)!='') then concat(' @ ',m.reg_no_display) else '' end) as case_no,
                    concat(m.pet_name,' Vs. ',m.res_name) as cause_title,date(m.diary_no_rec_date) as filing_date from main m
                    left join obj_save os on m.diary_no=os.diary_no where m.c_status='P' 
                    and m.diary_no not in (select diary_no from physical_verify) and date(diary_no_rec_date)>='2014-08-18' and date(diary_no_rec_date)<='2018-05-31'
                    and datediff(current_date(), date(os.save_dt)) > 90 and os.display='Y' and (os.rm_dt is null or os.rm_dt='0000-00-00') and os.diary_no is not null
                    order by SUBSTR(m.diary_no, - 4),SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4)";*/

                $sql = "
                SELECT DISTINCT 
                    CONCAT(
                        SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4), 
                        ' / ', 
                        SUBSTRING(CAST(m.diary_no AS TEXT) FROM -4 FOR 4)
                    ) 
                    || CASE 
                            WHEN (m.reg_no_display IS NOT NULL AND TRIM(m.reg_no_display) != '') 
                            THEN ' @ ' || m.reg_no_display 
                            ELSE '' 
                        END AS case_no,
                    m.pet_name || ' Vs. ' || m.res_name AS cause_title,
                    m.diary_no_rec_date::DATE AS filing_date
                FROM 
                    main m
                LEFT JOIN 
                    obj_save os ON m.diary_no = os.diary_no
                WHERE 
                    m.c_status = 'P' 
                    AND m.diary_no NOT IN (SELECT diary_no FROM physical_verify)
                    AND m.diary_no_rec_date::DATE >= '2014-08-18'
                    AND m.diary_no_rec_date::DATE <= '2018-05-31'
                    AND EXTRACT(DAY FROM CURRENT_DATE - os.save_dt) > 90
                    AND os.display = 'Y'
                    AND (os.rm_dt IS NULL)
                    AND os.diary_no IS NOT NULL
                ORDER BY 
                filing_date ASC
                    --SUBSTRING(CAST(m.diary_no AS TEXT) FROM -4 FOR 4),
                    --SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4)";
                $reportHeading="Total No. of Matters filed after 18.08.2014 and Before May-2018 (Delay in Refiling > 90)";
                break;
            }
            case "E4":
            {
                /*$sql="select distinct concat(concat(SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4),' / ',SUBSTR(m.diary_no, - 4)),case when (m.reg_no_display is not null and trim(m.reg_no_display)!='') then concat(' @ ',m.reg_no_display) else '' end) as case_no,
                    concat(m.pet_name,' Vs. ',m.res_name) as cause_title,date(m.diary_no_rec_date) as filing_date from main m
                    left join obj_save os on m.diary_no=os.diary_no where m.c_status='P' 
                    and m.diary_no not in (select diary_no from physical_verify) and date(diary_no_rec_date)>='2014-08-18' and date(diary_no_rec_date)<='2018-05-31'
                    and datediff(current_date(), date(os.save_dt)) < 60 and os.display='Y' and (os.rm_dt is null or os.rm_dt='0000-00-00') and os.diary_no is not null
                    order by SUBSTR(m.diary_no, - 4),SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4)";*/

                $sql = "
                SELECT DISTINCT 
                    CONCAT(
                        SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4), 
                        ' / ', 
                        SUBSTRING(CAST(m.diary_no AS TEXT) FROM -4 FOR 4)
                    ) 
                    || CASE 
                            WHEN (m.reg_no_display IS NOT NULL AND TRIM(m.reg_no_display) != '') 
                            THEN ' @ ' || m.reg_no_display 
                            ELSE '' 
                        END AS case_no,
                    m.pet_name || ' Vs. ' || m.res_name AS cause_title,
                    m.diary_no_rec_date::DATE AS filing_date
                FROM 
                    main m
                LEFT JOIN 
                    obj_save os ON m.diary_no = os.diary_no
                WHERE 
                    m.c_status = 'P' 
                    AND m.diary_no NOT IN (SELECT diary_no FROM physical_verify)
                    AND m.diary_no_rec_date::DATE >= '2014-08-18'
                    AND m.diary_no_rec_date::DATE <= '2018-05-31'
                    AND EXTRACT(DAY FROM CURRENT_DATE - os.save_dt) < 60
                    AND os.display = 'Y'
                    AND (os.rm_dt IS NULL)
                    AND os.diary_no IS NOT NULL
                ORDER BY 
                filing_date ASC
                    --SUBSTRING(CAST(m.diary_no AS TEXT) FROM -4 FOR 4),
                    --SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4)";
                $reportHeading="Total No. of Matters filed after 18.08.2014 and Before May-2018 (Delay in Refiling < 60 (in process with adv))";
                break;
            }
            default:
            {
                /*$sql="select distinct concat(concat(SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4),' / ',SUBSTR(m.diary_no, - 4)),case when (m.reg_no_display is not null and trim(m.reg_no_display)!='') then concat(' @ ',m.reg_no_display) else '' end) as case_no,
                      concat(m.pet_name,' Vs. ',m.res_name) as cause_title,date(m.diary_no_rec_date) as filing_date from main m
                      left join obj_save os on m.diary_no=os.diary_no where m.c_status='P' 
                      and m.diary_no not in (select diary_no from physical_verify) order by SUBSTR(m.diary_no, - 4),SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4)";*/

                $sql = "
                SELECT DISTINCT 
                    CONCAT(
                        SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4), 
                        ' / ', 
                        SUBSTRING(CAST(m.diary_no AS TEXT) FROM -4 FOR 4)
                    ) 
                    || CASE 
                            WHEN (m.reg_no_display IS NOT NULL AND TRIM(m.reg_no_display) != '') 
                            THEN ' @ ' || m.reg_no_display 
                            ELSE '' 
                        END AS case_no,
                    m.pet_name || ' Vs. ' || m.res_name AS cause_title,
                    m.diary_no_rec_date::DATE AS filing_date
                FROM 
                    main m
                LEFT JOIN 
                    obj_save os ON m.diary_no = os.diary_no
                WHERE 
                    m.c_status = 'P' 
                    AND m.diary_no NOT IN (SELECT diary_no FROM physical_verify)
                ORDER BY 
                filing_date ASC
                    --SUBSTRING(CAST(m.diary_no AS TEXT) FROM -4 FOR 4),
                    --SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4)";
                $reportHeading="Total No. of Matters not Verfied by Branches and Pending in ICMIS";
                break;
            }
        }
        $sql_query = $this->db->query($sql);
        // pr($this->db->getLastQuery()->getQuery());
        if($sql_query->getNumRows() >= 1)
        {
            $queryResult = $sql_query->getResultArray();
            $returnArr['queryResult'] = $queryResult;
            $returnArr['reportHeading'] = $reportHeading;
            return $returnArr;
        }
        else
        {
            $returnArr['queryResult'] = [];
            $returnArr['reportHeading'] = $reportHeading;
            return $returnArr;
        }
    }
}
