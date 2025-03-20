<?php

namespace App\Models\Reports\PendencyReport;

use CodeIgniter\Model;

class DetailedPendencyModel extends Model
{
    protected $eservicesdb;

    public function __construct()
    {
        parent::__construct();
        $this->db_icmis = db_connect();
        $this->eservicesdb = \Config\Database::connect('eservices');
    }

    function getCurrentPendency($mainReportID=null,$subReportID=null,$from_date=null,$to_date=null,$id=null,$reportType1=null)
    {
        // pr($id);
        switch($id)
        {
            case 1:
            {
                ## A & E Registered pending matters
                $sql = "SELECT
                        sum( case when (mf_active='M' or mf_active is null or mf_active ='') then 1 else 0 end) as misc_side_pendency,
                        sum( case when mf_active='F' then 1 else 0 end) as regular_side_pendency
                        FROM main m
                        WHERE c_status = 'P'
                        AND (active_fil_no IS NOT NULL AND active_fil_no != '')";
                break;
            }
            case 2:
            {
                ## B Un-registered matters
                /*$sql="select count(distinct diary_no) total from main m inner join
                        (select case when os.diary_no is null then m.diary_no else 0 end as dd from main m
                         inner JOIN docdetails b ON m.diary_no = b.diary_no
                         left outer join
                        (select distinct diary_no from obj_save where
                        (rm_dt is null or rm_dt='1970-01-01 00:00:00') and display='Y')
                        os on m.diary_no=os.diary_no
                         where  c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                        AND((doccode = '8'
                        AND doccode1 = '28'
                        ) || ( doccode = '8'
                        AND doccode1 = '95' ) || ( doccode = '8'
                        AND doccode1 = '214' ) || ( doccode = '8'
                        AND doccode1 = '215' )
                        )
                        AND b.iastat='P') aa on m.diary_no=aa.dd";*/

                $sql = "SELECT COUNT(DISTINCT m.diary_no) AS total
                FROM main m
                INNER JOIN (
                    SELECT 
                        CASE 
                            WHEN os.diary_no IS NULL THEN m.diary_no 
                            ELSE NULL 
                        END AS dd
                    FROM main m
                    INNER JOIN docdetails b ON m.diary_no = b.diary_no
                    LEFT OUTER JOIN (
                        SELECT DISTINCT diary_no
                        FROM obj_save
                        WHERE (rm_dt IS NULL OR rm_dt = '1970-01-01 00:00:00')  -- Use a default date instead of '0000-00-00'
                        AND display = 'Y'
                    ) os ON m.diary_no = os.diary_no
                    WHERE c_status = 'P' 
                      AND (active_fil_no IS NULL OR active_fil_no = '')
                      AND (
                          (doccode = '8' AND doccode1 IN ('28', '95', '214', '215'))
                      )
                      AND b.iastat = 'P'
                ) aa ON m.diary_no = aa.dd";
                break;


            }
            case 3:
            {
                ##C
                /*$sql="select count(distinct diary_no) total from main m inner join
                    (select case when os.diary_no is null then m.diary_no else 0 end as dd from main m
                     inner JOIN docdetails b ON m.diary_no = b.diary_no
                     left outer join
                    (select distinct diary_no from obj_save where
                    (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y')
                    os on m.diary_no=os.diary_no
                     where  c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                    AND((doccode = '8'
                    AND doccode1 = '16'
                    ) || ( doccode = '8'
                    AND doccode1 = '79' ) || ( doccode = '8'
                    AND doccode1 = '99' ) || ( doccode = '8'
                    AND doccode1 = '300' )
                    )
                    AND b.iastat='P') aa on m.diary_no=aa.dd";*/

                $sql = "SELECT COUNT(DISTINCT m.diary_no) AS total
                FROM main m
                INNER JOIN (
                    SELECT 
                        CASE 
                            WHEN os.diary_no IS NULL THEN m.diary_no 
                            ELSE NULL
                        END AS dd
                    FROM main m
                    INNER JOIN docdetails b ON m.diary_no = b.diary_no
                    LEFT OUTER JOIN (
                        SELECT DISTINCT diary_no
                        FROM obj_save
                        WHERE (rm_dt IS NULL OR rm_dt = '1970-01-01 00:00:00')  -- Use a valid default date, e.g., '1970-01-01'
                        AND display = 'Y'
                    ) os ON m.diary_no = os.diary_no
                    WHERE c_status = 'P' 
                      AND (active_fil_no IS NULL OR active_fil_no = '')
                      AND doccode = '8'
                      AND doccode1 IN ('16', '79', '99', '300')  -- Simplify OR conditions using IN
                      AND b.iastat = 'P'
                ) aa ON m.diary_no = aa.dd";
                break;
            }
            case 4:
            {
                ## D Diary Number which are defective but are listed before courts

                /*$sql="select sum(case when os.diary_no is not null then 1 else 0 end) DTotal
                    FROM main m inner join heardt h on m.diary_no=h.diary_no
                    left outer join
                    (select distinct diary_no from obj_save where
                    (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y')
                    os on m.diary_no=os.diary_no
                    WHERE c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                     and h.board_type='J'";*/

                $sql = "SELECT SUM(CASE WHEN os.diary_no IS NOT NULL THEN 1 ELSE 0 END) AS DTotal
                FROM main m
                INNER JOIN heardt h ON m.diary_no = h.diary_no
                LEFT OUTER JOIN (
                    SELECT DISTINCT diary_no
                    FROM obj_save
                    WHERE (rm_dt IS NULL OR rm_dt = '1970-01-01 00:00:00')  -- Use a valid date like '1970-01-01'
                    AND display = 'Y'
                ) os ON m.diary_no = os.diary_no
                WHERE c_status = 'P'
                  AND (active_fil_no IS NULL OR active_fil_no = '')
                  AND h.board_type = 'J'";
                break;
            }
            case 5:
            {
                ## E-1
                /*$sql="select count(*) total
                        FROM main m inner join docdetails b on m.diary_no=b.diary_no
                        inner join
                        (select distinct diary_no from obj_save where
                        (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y' and DATEDIFF(now(),save_dt)>60) os
                        on m.diary_no=os.diary_no
                        WHERE  m.c_status = 'P' and (m.active_fil_no is null or  m.active_fil_no='')
                        AND doccode = '8' AND doccode1 = '226' AND b.iastat='P'";*/

                $sql = "SELECT COUNT(*) AS total
                FROM main m
                INNER JOIN docdetails b ON m.diary_no = b.diary_no
                INNER JOIN (
                    SELECT DISTINCT diary_no
                    FROM obj_save
                    WHERE (rm_dt IS NULL OR rm_dt = '1970-01-01 00:00:00')  -- Replace '0000-00-00' with a valid date
                    AND display = 'Y'
                    AND EXTRACT(DAY FROM CURRENT_DATE - save_dt) > 60  -- Extract number of days from the interval
                ) os ON m.diary_no = os.diary_no
                WHERE m.c_status = 'P'
                  AND (m.active_fil_no IS NULL OR m.active_fil_no = '')
                  AND doccode = '8'
                  AND doccode1 = '226'
                  AND b.iastat = 'P'";
                break;
            }
            case 6:
            {
                #E-2
                /*$sql="select count(*) total
                        FROM main m inner join docdetails b on m.diary_no=b.diary_no
                        inner join
                        (select distinct diary_no from obj_save where
                        (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y' and DATEDIFF(now(),save_dt)<=60) os
                        on m.diary_no=os.diary_no
                        WHERE  m.c_status = 'P' and (m.active_fil_no is null or  m.active_fil_no='')
                        AND doccode = '8' AND doccode1 = '226' AND b.iastat='P'";*/

                $sql = "SELECT COUNT(*) AS total
                FROM main m
                INNER JOIN docdetails b ON m.diary_no = b.diary_no
                INNER JOIN (
                    SELECT DISTINCT diary_no
                    FROM obj_save
                    WHERE (rm_dt IS NULL OR rm_dt = '1970-01-01 00:00:00')  -- Replace '0000-00-00' with a valid date
                    AND display = 'Y'
                    AND EXTRACT(DAY FROM CURRENT_DATE - save_dt) <= 60  -- Extract the number of days from the interval
                ) os ON m.diary_no = os.diary_no
                WHERE m.c_status = 'P'
                  AND (m.active_fil_no IS NULL OR m.active_fil_no = '')
                  AND doccode = '8'
                  AND doccode1 = '226'
                  AND b.iastat = 'P'";
              break;
            }
            case 7:
            {
                ## A details Matters
                 /*$sql="SELECT distinct
                    SUBSTR(m.diary_no,
                        1,
                        LENGTH(m.diary_no) - 4) AS diary_no,
                    SUBSTR(m.diary_no, - 4) AS diary_year,
                    DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                    m.reg_no_display,
                    m.pet_name,
                    m.res_name,
                     us.section_name AS user_section,
                    u.name alloted_to_da,
                    h.next_dt,
                    CASE
                        WHEN h.main_supp_flag = 0 THEN 'Ready'
                        ELSE 'Not Ready'
                    END AS 'Status',
                    m.mf_active ,
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
                FROM
                    main m
                    left outer join heardt h ON m.diary_no = h.diary_no
                        LEFT JOIN
                    users u ON u.usercode = m.dacode
                        AND u.display = 'Y'
                        LEFT JOIN
                    usersection us ON us.id = u.section
                WHERE
                    c_status = 'P'
                        AND (active_fil_no IS not NULL and active_fil_no != '')
                        and (m.mf_active='M' or m.mf_active is null or m.mf_active='')";*/

                $sql = "SELECT DISTINCT
                    SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4) AS diary_no,
                    SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3 FOR 4) AS diary_year,
                    TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS diary_date,
                    m.reg_no_display,
                    m.pet_name,
                    m.res_name,
                    us.section_name AS user_section,
                    u.name AS alloted_to_da,
                    h.next_dt,
                    CASE
                        WHEN h.main_supp_flag = 0 THEN 'Ready'
                        ELSE 'Not Ready'
                    END AS Status,
                    m.mf_active,
                    CASE
                        WHEN (m.conn_key = '0' OR m.conn_key IS NULL OR m.conn_key = m.diary_no::text)
                        THEN 'M'
                        ELSE CASE
                            WHEN (m.conn_key != '0' AND m.conn_key IS NOT NULL AND m.conn_key != m.diary_no::text)
                            THEN 'C'
                        END
                    END AS mainorconn
                FROM
                    main m
                    LEFT OUTER JOIN heardt h ON m.diary_no = h.diary_no
                    LEFT JOIN master.users u ON u.usercode = m.dacode AND u.display = 'Y'
                    LEFT JOIN master.usersection us ON us.id = u.section
                WHERE
                    c_status = 'P'
                    AND (active_fil_no IS NOT NULL AND active_fil_no != '')
                    AND (m.mf_active = 'M' OR m.mf_active IS NULL OR m.mf_active = '')";
                break;
            }
            case 8:
            {
                ##B Deatils
                /*$sql="SELECT DISTINCT
                    us.section_name AS user_section,
                    u.name alloted_to_da,
                    SUBSTR(m.diary_no,
                        1,
                        LENGTH(m.diary_no) - 4) AS diary_no,
                    SUBSTR(m.diary_no, - 4) AS diary_year,
                    DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                    m.pet_name,
                    m.res_name,
                    h.next_dt,
                    CASE
                        WHEN h.main_supp_flag = 0 THEN 'Ready'
                        ELSE 'Not Ready'
                    END AS 'Status',
                    m.mf_active,
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
                FROM
                    main m left join
                    heardt h
                         ON m.diary_no = h.diary_no
                        LEFT JOIN
                    users u ON u.usercode = m.dacode
                        AND (u.display = 'Y' or u.display is null)
                        LEFT JOIN
                    usersection us ON us.id = u.section
                WHERE
                         m.diary_no in
                        (select distinct diary_no from main m inner join
                (select case when os.diary_no is null then m.diary_no else 0 end as dd from main m
                 inner JOIN docdetails b ON m.diary_no = b.diary_no
                 left outer join
                (select distinct diary_no from obj_save where
                (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y')
                os on m.diary_no=os.diary_no
                 where  c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                AND((doccode = '8'
                AND doccode1 = '28'
                ) || ( doccode = '8'
                AND doccode1 = '95' ) || ( doccode = '8'
                AND doccode1 = '214' ) || ( doccode = '8'
                AND doccode1 = '215' )
                )
                AND b.iastat='P') aa on m.diary_no=aa.dd)";*/

                $sql = "SELECT DISTINCT
                    us.section_name AS user_section,
                    u.name AS alloted_to_da,
                    SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4) AS diary_no,
                    SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3 FOR 4) AS diary_year,
                    TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS diary_date,
                    m.pet_name,
                    m.res_name,
                    h.next_dt,
                    CASE
                        WHEN h.main_supp_flag = 0 THEN 'Ready'
                        ELSE 'Not Ready'
                    END AS Status,
                    m.mf_active,
                    CASE
                        WHEN
                            (m.conn_key = '0' OR m.conn_key IS NULL OR m.conn_key = m.diary_no::text)
                        THEN
                            'M'
                        ELSE CASE
                            WHEN
                                (m.conn_key != '0' AND m.conn_key IS NOT NULL AND m.conn_key != m.diary_no::text)
                            THEN
                                'C'
                        END
                    END AS mainorconn
                FROM
                    main m
                    LEFT JOIN heardt h ON m.diary_no = h.diary_no
                    LEFT JOIN master.users u ON u.usercode = m.dacode
                        AND (u.display = 'Y' OR u.display IS NULL)
                    LEFT JOIN master.usersection us ON us.id = u.section
                WHERE
                    m.diary_no IN (
                        SELECT DISTINCT diary_no
                        FROM main m
                        INNER JOIN (
                            SELECT
                                CASE
                                    WHEN os.diary_no IS NULL THEN m.diary_no
                                    ELSE 0
                                END AS dd
                            FROM main m
                            INNER JOIN docdetails b ON m.diary_no = b.diary_no
                            LEFT OUTER JOIN (
                                SELECT DISTINCT diary_no
                                FROM obj_save
                                WHERE
                                    (rm_dt IS NULL OR rm_dt != '1970-01-01 00:00:00')
                                    AND display = 'Y'
                            ) os ON m.diary_no = os.diary_no
                            WHERE
                                c_status = 'P'
                                AND (active_fil_no IS NULL OR active_fil_no = '')
                                AND (
                                    (doccode = '8' AND doccode1 = '28')
                                    OR (doccode = '8' AND doccode1 = '95')
                                    OR (doccode = '8' AND doccode1 = '214')
                                    OR (doccode = '8' AND doccode1 = '215')
                                )
                                AND b.iastat = 'P'
                        ) aa ON m.diary_no = aa.dd
                    )";
                break;
            }
            case 9:
            {
                /*$sql="SELECT distinct
                        us.section_name AS user_section,
                        u.name alloted_to_da,
                        SUBSTR(m.diary_no,
                            1,
                            LENGTH(m.diary_no) - 4) AS diary_no,
                        SUBSTR(m.diary_no, - 4) AS diary_year,
                        DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                        m.reg_no_display,
                        m.pet_name,
                        m.res_name,
                        h.next_dt,
                        CASE
                            WHEN h.main_supp_flag = 0 THEN 'Ready'
                            ELSE 'Not Ready'
                        END AS 'Status',
                        m.mf_active,
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
                    FROM
                        main m
                        left outer join heardt h ON m.diary_no = h.diary_no
                            LEFT JOIN
                        users u ON u.usercode = m.dacode
                            AND u.display = 'Y'
                            LEFT JOIN
                        usersection us ON us.id = u.section
                    WHERE
                        c_status = 'P'
                            AND (active_fil_no IS not NULL and active_fil_no != '')
                            and m.mf_active='F'";*/

                $sql="SELECT distinct
                    us.section_name AS user_section,
                    u.name alloted_to_da,
                    SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4) AS diary_no,
                    SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3 FOR 4) AS diary_year,
                    TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS diary_date,
                    m.reg_no_display,
                    m.pet_name,
                    m.res_name,
                    h.next_dt,
                    CASE
                        WHEN h.main_supp_flag = 0 THEN 'Ready'
                        ELSE 'Not Ready'
                    END AS Status,
                    m.mf_active,
                    CASE
                        WHEN
                            (m.conn_key = '0' OR m.conn_key IS NULL
                                OR m.conn_key = m.diary_no::text)
                        THEN
                            'M'
                        ELSE CASE
                            WHEN
                                (m.conn_key != '0'
                                    AND m.conn_key IS NOT NULL
                                    AND m.conn_key != m.diary_no::text)
                            THEN
                                'C'
                        END
                    END AS mainorconn
                FROM
                    main m
                    left outer join heardt h ON m.diary_no = h.diary_no
                    LEFT JOIN master.users u ON u.usercode = m.dacode
                    AND u.display = 'Y'
                    LEFT JOIN master.usersection us ON us.id = u.section
                WHERE
                    c_status = 'P'
                        AND (active_fil_no IS not NULL and active_fil_no != '')
                        and m.mf_active='F'";
                break;

            }
            case 10:
            {
                /*$sql="SELECT distinct
                        us.section_name AS user_section,
                        u.name alloted_to_da,
                        SUBSTR(m.diary_no,
                            1,
                            LENGTH(m.diary_no) - 4) AS diary_no,
                        SUBSTR(m.diary_no, - 4) AS diary_year,
                        DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                        m.reg_no_display,
                        m.pet_name,
                        m.res_name,
                        h.next_dt,
                        CASE
                            WHEN h.main_supp_flag = 0 THEN 'Ready'
                            ELSE 'Not Ready'
                        END AS 'Status',
                        m.mf_active,
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
                    FROM
                        main m
                        inner join heardt h ON m.diary_no = h.diary_no
                            LEFT JOIN
                        users u ON u.usercode = m.dacode
                            AND u.display = 'Y'
                            LEFT JOIN
                        usersection us ON us.id = u.section
                        left outer join
                    (select distinct diary_no from obj_save where
                    (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y')
                    os on m.diary_no=os.diary_no
                    WHERE
                        c_status = 'P' and
                            (fil_no is null or  fil_no='')
                            and os.diary_no is not null
                            and h.board_type='J'
                    ";*/

                    $sql="SELECT DISTINCT
                        us.section_name AS user_section,
                        u.name AS alloted_to_da,
                        SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4) AS diary_no,
                        SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3 FOR 4) AS diary_year,
                        TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS diary_date,
                        m.reg_no_display,
                        m.pet_name,
                        m.res_name,
                        h.next_dt,
                        CASE
                            WHEN h.main_supp_flag = 0 THEN 'Ready'
                            ELSE 'Not Ready'
                        END AS Status,
                        m.mf_active,
                        CASE
                            WHEN
                                (m.conn_key = '0' OR m.conn_key IS NULL
                                    OR m.conn_key = m.diary_no::text)
                            THEN
                                'M'
                            ELSE CASE
                                WHEN
                                    (m.conn_key != '0'
                                        AND m.conn_key IS NOT NULL
                                        AND m.conn_key != m.diary_no::text)
                                THEN
                                    'C'
                            END
                        END AS mainorconn
                    FROM
                        main m
                        INNER JOIN heardt h ON m.diary_no = h.diary_no
                        LEFT JOIN master.users u ON u.usercode = m.dacode
                            AND u.display = 'Y'
                        LEFT JOIN master.usersection us ON us.id = u.section
                        LEFT OUTER JOIN (
                            SELECT DISTINCT diary_no
                            FROM obj_save
                            WHERE
                                (rm_dt IS NULL OR rm_dt != '1970-01-01 00:00:00')
                                AND display = 'Y'
                        ) os ON m.diary_no = os.diary_no
                    WHERE
                        c_status = 'P'
                        AND (fil_no IS NULL OR fil_no = '')
                        AND os.diary_no IS NOT NULL
                        AND h.board_type = 'J'";
                break;
            }
            case 11:
                {
                    /*$sql="SELECT DISTINCT
                        us.section_name AS user_section,
                        u.name alloted_to_da,
                        SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) AS diary_no,
                        SUBSTR(m.diary_no, - 4) AS diary_year,
                        DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                        m.pet_name,
                        m.res_name,
                        h.next_dt,
                        CASE
                            WHEN h.main_supp_flag = 0 THEN 'Ready'
                            ELSE 'Not Ready'
                        END AS 'Status',
                        m.mf_active,
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
                    FROM
                        main m left join
                        heardt h
                             ON m.diary_no = h.diary_no
                            LEFT JOIN
                        users u ON u.usercode = m.dacode
                            AND (u.display = 'Y' or u.display is null)
                            LEFT JOIN
                        usersection us ON us.id = u.section
                    WHERE
                             m.diary_no in
                            (select distinct diary_no from main m inner join
                    (select case when os.diary_no is null then m.diary_no else 0 end as dd from main m
                     inner JOIN docdetails b ON m.diary_no = b.diary_no
                     left outer join
                    (select distinct diary_no from obj_save where
                    (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y')
                    os on m.diary_no=os.diary_no
                     where  c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                    AND((doccode = '8'
                    AND doccode1 = '16'
                    ) || ( doccode = '8'
                    AND doccode1 = '79' ) || ( doccode = '8'
                    AND doccode1 = '99' ) || ( doccode = '8'
                    AND doccode1 = '300' )
                    )
                    AND b.iastat='P') aa on m.diary_no=aa.dd)";*/

                    $sql="SELECT DISTINCT
                        us.section_name AS user_section,
                        u.name alloted_to_da,
                        SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4) AS diary_no,
                        SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3 FOR 4) AS diary_year,
                        TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS diary_date,
                        m.pet_name,
                        m.res_name,
                        h.next_dt,
                        CASE
                            WHEN h.main_supp_flag = 0 THEN 'Ready'
                            ELSE 'Not Ready'
                        END AS Status,
                        m.mf_active,
                        CASE
                            WHEN
                                (m.conn_key = '0' OR m.conn_key IS NULL
                                    OR m.conn_key = m.diary_no::text)
                            THEN
                                'M'
                            ELSE CASE
                                WHEN
                                    (m.conn_key != '0'
                                        AND m.conn_key IS NOT NULL
                                        AND m.conn_key != m.diary_no::text)
                                THEN
                                    'C'
                            END
                        END AS mainorconn
                    FROM
                        main m 
                        LEFT JOIN heardt h ON m.diary_no = h.diary_no
                        LEFT JOIN master.users u ON u.usercode = m.dacode
                            AND (u.display = 'Y' OR u.display IS NULL)
                        LEFT JOIN master.usersection us ON us.id = u.section
                    WHERE 
                        m.diary_no IN (
                            SELECT DISTINCT diary_no
                            FROM main m
                            INNER JOIN (
                                SELECT
                                    CASE
                                        WHEN os.diary_no IS NULL THEN m.diary_no
                                        ELSE 0
                                    END AS dd
                                FROM main m
                                INNER JOIN docdetails b ON m.diary_no = b.diary_no
                                LEFT OUTER JOIN (
                                    SELECT DISTINCT diary_no
                                    FROM obj_save
                                    WHERE
                                        (rm_dt IS NULL OR rm_dt != '1970-01-01 00:00:00')
                                        AND display = 'Y'
                                ) os ON m.diary_no = os.diary_no
                                WHERE
                                    c_status = 'P'
                                    AND (active_fil_no IS NULL OR active_fil_no = '')
                                    AND (
                                        (doccode = '8' AND doccode1 = '16')
                                        OR (doccode = '8' AND doccode1 = '79')
                                        OR (doccode = '8' AND doccode1 = '99')
                                        OR (doccode = '8' AND doccode1 = '300')
                                    )
                                    AND b.iastat = 'P'
                            ) aa ON m.diary_no = aa.dd
                        )";
                   break;
                }
            case 12:
            {
                /*$sql="SELECT DISTINCT
                    us.section_name AS user_section,
                    u.name alloted_to_da,
                    SUBSTR(m.diary_no,
                        1,
                        LENGTH(m.diary_no) - 4) AS diary_no,
                    SUBSTR(m.diary_no, - 4) AS diary_year,
                    DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                    m.pet_name,
                    m.res_name,
                    h.next_dt,
                    CASE
                        WHEN h.main_supp_flag = 0 THEN 'Ready'
                        ELSE 'Not Ready'
                    END AS 'Status',
                    m.mf_active,
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
                FROM
                    main m left join
                    heardt h
                         ON m.diary_no = h.diary_no
                        LEFT JOIN
                    users u ON u.usercode = m.dacode
                        AND (u.display = 'Y' or u.display is null)
                        LEFT JOIN
                    usersection us ON us.id = u.section
                    inner join docdetails b on m.diary_no=b.diary_no
                inner join
                (select distinct diary_no from obj_save where
                (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y' and DATEDIFF(now(),save_dt)>60) os
                on m.diary_no=os.diary_no
                WHERE  m.c_status = 'P' and (m.active_fil_no is null or  m.active_fil_no='')
                AND doccode = '8' AND doccode1 = '226' AND b.iastat='P'";*/

                $sql="SELECT DISTINCT
                    us.section_name AS user_section,
                    u.name AS alloted_to_da,
                    SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4) AS diary_no,
                    SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3 FOR 4) AS diary_year,
                    TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS diary_date,
                    m.pet_name,
                    m.res_name,
                    h.next_dt,
                    CASE
                        WHEN h.main_supp_flag = 0 THEN 'Ready'
                        ELSE 'Not Ready'
                    END AS Status,
                    m.mf_active,
                    CASE
                        WHEN
                            (m.conn_key = '0' OR m.conn_key IS NULL
                                OR m.conn_key = m.diary_no::text)
                        THEN
                            'M'
                        ELSE CASE
                            WHEN
                                (m.conn_key != '0'
                                    AND m.conn_key IS NOT NULL
                                    AND m.conn_key != m.diary_no::text)
                            THEN
                                'C'
                        END
                    END AS mainorconn
                FROM
                    main m
                    LEFT JOIN heardt h ON m.diary_no = h.diary_no
                    LEFT JOIN master.users u ON u.usercode = m.dacode
                    AND (u.display = 'Y' OR u.display IS NULL)
                    LEFT JOIN master.usersection us ON us.id = u.section
                    INNER JOIN docdetails b ON m.diary_no = b.diary_no
                    INNER JOIN (
                        SELECT DISTINCT diary_no
                        FROM obj_save
                        WHERE
                            (rm_dt IS NULL OR rm_dt = '1970-01-01 00:00:00')
                            AND display = 'Y'
                            AND EXTRACT(DAY FROM (CURRENT_TIMESTAMP - save_dt)) > 60
                    ) os ON m.diary_no = os.diary_no
                WHERE
                    m.c_status = 'P'
                    AND (m.active_fil_no IS NULL OR m.active_fil_no = '')
                    AND doccode = '8'
                    AND doccode1 = '226'
                    AND b.iastat = 'P'";
                break;
            }
            case 13:
            {
                /*$sql="SELECT DISTINCT
                            us.section_name AS user_section,
                            u.name alloted_to_da,
                            SUBSTR(m.diary_no,
                                1,
                                LENGTH(m.diary_no) - 4) AS diary_no,
                            SUBSTR(m.diary_no, - 4) AS diary_year,
                            DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                            m.pet_name,
                            m.res_name,
                            h.next_dt,
                            CASE
                                WHEN h.main_supp_flag = 0 THEN 'Ready'
                                ELSE 'Not Ready'
                            END AS 'Status',
                            m.mf_active,
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
                        FROM
                            main m left join
                            heardt h
                                 ON m.diary_no = h.diary_no
                                LEFT JOIN
                            users u ON u.usercode = m.dacode
                                AND (u.display = 'Y' or u.display is null)
                                LEFT JOIN
                            usersection us ON us.id = u.section
                            inner join docdetails b on m.diary_no=b.diary_no
                        inner join
                        (select distinct diary_no from obj_save where
                        (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y' and DATEDIFF(now(),save_dt)<=60) os
                        on m.diary_no=os.diary_no
                        WHERE  m.c_status = 'P' and (m.active_fil_no is null or  m.active_fil_no='')
                        AND doccode = '8' AND doccode1 = '226' AND b.iastat='P'";*/

                $sql = "SELECT DISTINCT
                    us.section_name AS user_section,
                    u.name alloted_to_da,
                    SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4) AS diary_no,
                    SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3 FOR 4) AS diary_year,
                    TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS diary_date,
                    m.pet_name,
                    m.res_name,
                    h.next_dt,
                    CASE
                        WHEN h.main_supp_flag = 0 THEN 'Ready'
                        ELSE 'Not Ready'
                    END AS Status,
                    m.mf_active,
                    CASE
                        WHEN
                            (m.conn_key = '0' OR m.conn_key IS NULL
                                OR m.conn_key = m.diary_no::text)
                        THEN
                            'M'
                        ELSE CASE
                            WHEN
                                (m.conn_key != '0'
                                    AND m.conn_key IS NOT NULL
                                    AND m.conn_key != m.diary_no::text)
                            THEN
                                'C'
                        END
                    END AS mainorconn
                FROM
                    main m
                    LEFT JOIN heardt h ON m.diary_no = h.diary_no
                    LEFT JOIN master.users u ON u.usercode = m.dacode
                        AND (u.display = 'Y' OR u.display IS NULL)
                    LEFT JOIN master.usersection us ON us.id = u.section
                    INNER JOIN docdetails b ON m.diary_no = b.diary_no
                    INNER JOIN (
                        SELECT DISTINCT diary_no
                        FROM obj_save
                        WHERE
                            (
                                rm_dt IS NULL 
                                OR NULLIF(rm_dt, '1970-01-01 00:00:00') IS NULL
                            ) 
                            AND display = 'Y'
                            AND EXTRACT(DAY FROM (CURRENT_TIMESTAMP - save_dt)) <= 60
                    ) os ON m.diary_no = os.diary_no
                WHERE
                    m.c_status = 'P'
                    AND (m.active_fil_no IS NULL OR m.active_fil_no = '')
                    AND doccode = '8'
                    AND doccode1 = '226'
                    AND b.iastat = 'P'";
                break;
            }
            case 14:
            {
                //F11
                /*$sql="select sum(case when os.diary_no is null then 1 else 0 end) total
                        FROM main m
                        left outer join
                        (select distinct diary_no from obj_save where
                        (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y')
                        os on m.diary_no=os.diary_no
                        WHERE c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                        AND m.diary_no not in
                        (
                        select m.diary_no total
                        FROM main m inner join docdetails b on m.diary_no=b.diary_no
                        WHERE  c_status = 'P' and (active_fil_no is null or  active_fil_no='') and
                        ((doccode = '8'
                        AND doccode1 = '16'
                        ) || ( doccode = '8'
                        AND doccode1 = '79' ) || ( doccode = '8'
                        AND doccode1 = '99' ) || ( doccode = '8'
                        AND doccode1 = '300' ) || (doccode = '8'
                        AND doccode1 = '28'
                        ) || ( doccode = '8'
                        AND doccode1 = '95' ) || ( doccode = '8'
                        AND doccode1 = '214' ) || ( doccode = '8'
                        AND doccode1 = '215' )
                        )
                        AND b.iastat='P') and date(diary_no_rec_date)<'2014-08-19'";*/

                $sql = "SELECT 
                            SUM(CASE 
                                    WHEN os.diary_no IS NULL THEN 1 
                                    ELSE 0 
                                END) AS total
                        FROM 
                            main m
                        LEFT JOIN 
                            (SELECT DISTINCT diary_no 
                             FROM obj_save 
                             WHERE (rm_dt IS NULL OR rm_dt = '1970-01-01 00:00:00') 
                               AND display = 'Y') os 
                        ON 
                            m.diary_no = os.diary_no
                        WHERE 
                            m.c_status = 'P' 
                            AND (m.active_fil_no IS NULL OR m.active_fil_no = '')
                            AND m.diary_no NOT IN (
                                SELECT m.diary_no 
                                FROM main m 
                                INNER JOIN docdetails b 
                                ON m.diary_no = b.diary_no
                                WHERE m.c_status = 'P' 
                                AND (m.active_fil_no IS NULL OR m.active_fil_no = '')
                                AND (
                                    (doccode = '8' AND doccode1 IN ('16', '79', '99', '300', '28', '95', '214', '215'))
                                )
                                AND b.iastat = 'P'
                            )
                            AND m.diary_no_rec_date::date < '2014-08-19'";
                break;
            }
            case 15:
            {
                //F12
                /*$sql="select sum(case when os.diary_no is null then 1 else 0 end) total
                        FROM main m
                        left outer join
                        (select distinct diary_no from obj_save where
                        (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y')
                        os on m.diary_no=os.diary_no
                        WHERE c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                        AND m.diary_no not in
                        (
                        select m.diary_no total
                        FROM main m inner join docdetails b on m.diary_no=b.diary_no
                        WHERE  c_status = 'P' and (active_fil_no is null or  active_fil_no='') and
                        ((doccode = '8'
                        AND doccode1 = '16'
                        ) || ( doccode = '8'
                        AND doccode1 = '79' ) || ( doccode = '8'
                        AND doccode1 = '99' ) || ( doccode = '8'
                        AND doccode1 = '300' ) || (doccode = '8'
                        AND doccode1 = '28'
                        ) || ( doccode = '8'
                        AND doccode1 = '95' ) || ( doccode = '8'
                        AND doccode1 = '214' ) || ( doccode = '8'
                        AND doccode1 = '215' )
                        )
                        AND b.iastat='P') and date(diary_no_rec_date)>='2014-08-19'";*/

                $sql = "SELECT 
                    SUM(CASE 
                            WHEN os.diary_no IS NULL THEN 1 
                            ELSE 0 
                        END) AS total
                FROM 
                    main m
                LEFT JOIN 
                    (SELECT DISTINCT diary_no 
                     FROM obj_save 
                     WHERE (rm_dt IS NULL OR rm_dt = '1970-01-01 00:00:00') 
                       AND display = 'Y') os 
                ON 
                    m.diary_no = os.diary_no
                WHERE 
                    m.c_status = 'P' 
                    AND (m.active_fil_no IS NULL OR m.active_fil_no = '')
                    AND m.diary_no NOT IN (
                        SELECT m.diary_no 
                        FROM main m 
                        INNER JOIN docdetails b 
                        ON m.diary_no = b.diary_no
                        WHERE m.c_status = 'P' 
                        AND (m.active_fil_no IS NULL OR m.active_fil_no = '')
                        AND (
                            (doccode = '8' AND doccode1 IN ('16', '79', '99', '300', '28', '95', '214', '215'))
                        )
                        AND b.iastat = 'P'
                    )
                    AND m.diary_no_rec_date::date >= '2014-08-19'";
                break;
            }
            case 16:
            {
                //F21
                /*$sql="select sum(case when os.diary_no is not null  then 1 else 0 end) total
                    FROM main m 
                    left outer join
                    (select  diary_no,DATEDIFF(now(),max(save_dt)) as days from obj_save where
                    (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y'
                     group by diary_no)
                    os on m.diary_no=os.diary_no and os.days>90
                    WHERE c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                    and m.diary_no not in
                    (select m.diary_no
                    FROM main m inner join heardt h on m.diary_no=h.diary_no
                    left outer join
                    (select distinct diary_no from obj_save where
                    (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y')
                    os on m.diary_no=os.diary_no
                    WHERE c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                     and h.board_type='J' and os.diary_no is not null

                     union

                     select m.diary_no
                    FROM main m inner join docdetails b on m.diary_no=b.diary_no
                    inner join
                    (select distinct diary_no from obj_save where
                    (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y') os
                    on m.diary_no=os.diary_no
                    WHERE  m.c_status = 'P' and (m.active_fil_no is null or  m.active_fil_no='')
                    AND doccode = '8' AND doccode1 = '226' AND b.iastat='P')";*/

                $sql = "SELECT 
                    SUM(CASE 
                            WHEN os.diary_no IS NOT NULL THEN 1 
                            ELSE 0 
                        END) AS total
                FROM 
                    main m
                LEFT JOIN 
                    (
                        SELECT 
                            diary_no, 
                            EXTRACT(DAY FROM (CURRENT_DATE - MAX(save_dt))) AS days
                        FROM 
                            obj_save
                        WHERE 
                            (rm_dt IS NULL OR rm_dt = '1970-01-01 00:00:00') 
                            AND display = 'Y'
                        GROUP BY 
                            diary_no
                    ) os ON m.diary_no = os.diary_no 
                        AND os.days > 90
                WHERE 
                    m.c_status = 'P' 
                    AND (m.active_fil_no IS NULL OR m.active_fil_no = '')
                    AND m.diary_no NOT IN (
                        SELECT 
                            m.diary_no
                        FROM 
                            main m
                        INNER JOIN 
                            heardt h ON m.diary_no = h.diary_no
                        LEFT JOIN 
                            (
                                SELECT DISTINCT 
                                    diary_no 
                                FROM 
                                    obj_save 
                                WHERE 
                                    (rm_dt IS NULL OR rm_dt = '1970-01-01 00:00:00') 
                                    AND display = 'Y'
                            ) os ON m.diary_no = os.diary_no
                        WHERE 
                            m.c_status = 'P' 
                            AND (m.active_fil_no IS NULL OR m.active_fil_no = '')
                            AND h.board_type = 'J' 
                            AND os.diary_no IS NOT NULL
                        UNION
                        SELECT 
                            m.diary_no
                        FROM 
                            main m
                        INNER JOIN 
                            docdetails b ON m.diary_no = b.diary_no
                        INNER JOIN 
                            (
                                SELECT DISTINCT 
                                    diary_no 
                                FROM 
                                    obj_save 
                                WHERE 
                                    (rm_dt IS NULL OR rm_dt = '1970-01-01 00:00:00') 
                                    AND display = 'Y'
                            ) os ON m.diary_no = os.diary_no
                        WHERE 
                            m.c_status = 'P' 
                            AND (m.active_fil_no IS NULL OR m.active_fil_no = '')
                            AND doccode = '8' 
                            AND doccode1 = '226' 
                            AND b.iastat = 'P'
                    )";
                break;
            }
            case 17:
            {
                //F22
                /*$sql="select sum(case when os.diary_no is not null  then 1 else 0 end) total
                    FROM main m 
                    left outer join
                    (select  diary_no,DATEDIFF(now(),max(save_dt)) as days from obj_save where
                    (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y'
                     group by diary_no)
                    os on m.diary_no=os.diary_no and os.days<=90
                    WHERE c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                    and m.diary_no not in
                    (select os.diary_no
                    FROM main m inner join heardt h on m.diary_no=h.diary_no
                    left outer join
                    (select distinct diary_no from obj_save where
                    (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y')
                    os on m.diary_no=os.diary_no
                    WHERE c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                     and h.board_type='J' and os.diary_no is not null
                     union
                     select m.diary_no
                    FROM main m inner join docdetails b on m.diary_no=b.diary_no
                    inner join
                    (select distinct diary_no from obj_save where
                    (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y') os
                    on m.diary_no=os.diary_no
                    WHERE  m.c_status = 'P' and (m.active_fil_no is null or  m.active_fil_no='')
                    AND doccode = '8' AND doccode1 = '226' AND b.iastat='P')";*/

                $sql = "SELECT 
                    SUM(CASE 
                            WHEN os.diary_no IS NOT NULL THEN 1 
                            ELSE 0 
                        END) AS total
                FROM 
                    main m
                LEFT JOIN 
                    (
                        SELECT 
                            diary_no, 
                            EXTRACT(DAY FROM (CURRENT_DATE - MAX(save_dt))) AS days
                        FROM 
                            obj_save
                        WHERE 
                            (rm_dt IS NULL OR rm_dt = '1970-01-01 00:00:00') 
                            AND display = 'Y'
                        GROUP BY 
                            diary_no
                    ) os ON m.diary_no = os.diary_no 
                        AND os.days <= 90
                WHERE 
                    m.c_status = 'P' 
                    AND (m.active_fil_no IS NULL OR m.active_fil_no = '')
                    AND m.diary_no NOT IN (
                        SELECT 
                            os.diary_no
                        FROM 
                            main m
                        INNER JOIN 
                            heardt h ON m.diary_no = h.diary_no
                        LEFT JOIN 
                            (
                                SELECT DISTINCT 
                                    diary_no 
                                FROM 
                                    obj_save 
                                WHERE 
                                    (rm_dt IS NULL OR rm_dt = '1970-01-01 00:00:00') 
                                    AND display = 'Y'
                            ) os ON m.diary_no = os.diary_no
                        WHERE 
                            m.c_status = 'P' 
                            AND (m.active_fil_no IS NULL OR m.active_fil_no = '')
                            AND h.board_type = 'J' 
                            AND os.diary_no IS NOT NULL
                        UNION
                        SELECT 
                            m.diary_no
                        FROM 
                            main m
                        INNER JOIN 
                            docdetails b ON m.diary_no = b.diary_no
                        INNER JOIN 
                            (
                                SELECT DISTINCT 
                                    diary_no 
                                FROM 
                                    obj_save 
                                WHERE 
                                    (rm_dt IS NULL OR rm_dt = '1970-01-01 00:00:00') 
                                    AND display = 'Y'
                            ) os ON m.diary_no = os.diary_no
                        WHERE 
                            m.c_status = 'P' 
                            AND (m.active_fil_no IS NULL OR m.active_fil_no = '')
                            AND doccode = '8' 
                            AND doccode1 = '226' 
                            AND b.iastat = 'P'
                    )";
                break;
            }
           case 18:
            {
                /*$sql="SELECT DISTINCT
                        us.section_name AS user_section,
                        u.name alloted_to_da,
                        SUBSTR(m.diary_no,
                            1,
                            LENGTH(m.diary_no) - 4) AS diary_no,
                        SUBSTR(m.diary_no, - 4) AS diary_year,
                        DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                        m.pet_name,
                        m.res_name,
                        h.next_dt,
                        CASE
                            WHEN h.main_supp_flag = 0 THEN 'Ready'
                            ELSE 'Not Ready'
                        END AS 'Status',
                        m.mf_active AS 'Misc or Regular',
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
                    FROM
                        main m left join
                        heardt h
                             ON m.diary_no = h.diary_no
                             left outer join
                    (select distinct diary_no from obj_save where
                    (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y')
                    os on m.diary_no=os.diary_no
                            LEFT JOIN
                        users u ON u.usercode = m.dacode
                            AND (u.display = 'Y' or u.display is null)
                            LEFT JOIN
                        usersection us ON us.id = u.section
                    WHERE c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                    AND m.diary_no not in
                    (
                    select m.diary_no total
                    FROM main m inner join docdetails b on m.diary_no=b.diary_no
                    WHERE  c_status = 'P' and (active_fil_no is null or  active_fil_no='') and
                    ((doccode = '8'
                    AND doccode1 = '16'
                    ) || ( doccode = '8'
                    AND doccode1 = '79' ) || ( doccode = '8'
                    AND doccode1 = '99' ) || ( doccode = '8'
                    AND doccode1 = '300' ) || (doccode = '8'
                    AND doccode1 = '28'
                    ) || ( doccode = '8'
                    AND doccode1 = '95' ) || ( doccode = '8'
                    AND doccode1 = '214' ) || ( doccode = '8'
                    AND doccode1 = '215' )
                    )
                    AND b.iastat='P') and date(diary_no_rec_date)<'2014-08-19'
                    and os.diary_no is null";*/

                $sql="SELECT DISTINCT
                    us.section_name AS user_section,
                    u.name alloted_to_da,
                    SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4) AS diary_no,
                    SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3 FOR 4) AS diary_year,
                    TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS diary_date,
                    m.pet_name,
                    m.res_name,
                    h.next_dt,
                    CASE
                        WHEN h.main_supp_flag = 0 THEN 'Ready'
                        ELSE 'Not Ready'
                    END AS Status,
                    m.mf_active,
                    CASE
                        WHEN
                            (m.conn_key = '0' OR m.conn_key IS NULL
                                OR m.conn_key = m.diary_no::text)
                        THEN
                            'M'
                        ELSE CASE
                            WHEN
                                (m.conn_key != '0'
                                    AND m.conn_key IS NOT NULL
                                    AND m.conn_key != m.diary_no::text)
                            THEN
                                'C'
                        END
                    END AS mainorconn
                FROM
                    main m 
                LEFT JOIN
                    heardt h ON m.diary_no = h.diary_no
                LEFT OUTER JOIN
                    (SELECT DISTINCT diary_no 
                     FROM obj_save 
                     WHERE (rm_dt IS NULL OR rm_dt = '1970-01-01 00:00:00') 
                     AND display = 'Y') os ON m.diary_no = os.diary_no
                LEFT JOIN master.users u ON u.usercode = m.dacode
                    AND (u.display = 'Y' OR u.display IS NULL)
                LEFT JOIN master.usersection us ON us.id = u.section
                WHERE 
                    c_status = 'P' 
                    AND (active_fil_no IS NULL OR active_fil_no = '')
                    AND m.diary_no NOT IN (
                        SELECT m.diary_no
                        FROM main m 
                        INNER JOIN docdetails b ON m.diary_no = b.diary_no
                        WHERE c_status = 'P' 
                        AND (active_fil_no IS NULL OR active_fil_no = '') 
                        AND (
                            (doccode = '8' AND doccode1 = '16') 
                            OR (doccode = '8' AND doccode1 = '79') 
                            OR (doccode = '8' AND doccode1 = '99') 
                            OR (doccode = '8' AND doccode1 = '300') 
                            OR (doccode = '8' AND doccode1 = '28') 
                            OR (doccode = '8' AND doccode1 = '95') 
                            OR (doccode = '8' AND doccode1 = '214') 
                            OR (doccode = '8' AND doccode1 = '215')
                        )
                        AND b.iastat = 'P'
                    ) 
                    AND date(diary_no_rec_date) < '2014-08-19'
                    AND os.diary_no IS NULL";
                break;
            }
            case 19:
            {
                /*$sql="SELECT DISTINCT
                    us.section_name AS user_section,
                    u.name alloted_to_da,
                    SUBSTR(m.diary_no,
                        1,
                        LENGTH(m.diary_no) - 4) AS diary_no,
                    SUBSTR(m.diary_no, - 4) AS diary_year,
                    DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                    m.pet_name,
                    m.res_name,
                    h.next_dt,
                    CASE
                        WHEN h.main_supp_flag = 0 THEN 'Ready'
                        ELSE 'Not Ready'
                    END AS 'Status',
                    m.mf_active AS 'Misc or Regular',
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
                FROM
                    main m left join
                    heardt h
                         ON m.diary_no = h.diary_no
                         left outer join
                (select distinct diary_no from obj_save where
                (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y')
                os on m.diary_no=os.diary_no
                        LEFT JOIN
                    users u ON u.usercode = m.dacode
                        AND (u.display = 'Y' or u.display is null)
                        LEFT JOIN
                    usersection us ON us.id = u.section
                WHERE c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                AND m.diary_no not in
                (
                select m.diary_no total
                FROM main m inner join docdetails b on m.diary_no=b.diary_no
                WHERE  c_status = 'P' and (active_fil_no is null or  active_fil_no='') and
                ((doccode = '8'
                AND doccode1 = '16'
                ) || ( doccode = '8'
                AND doccode1 = '79' ) || ( doccode = '8'
                AND doccode1 = '99' ) || ( doccode = '8'
                AND doccode1 = '300' ) || (doccode = '8'
                AND doccode1 = '28'
                ) || ( doccode = '8'
                AND doccode1 = '95' ) || ( doccode = '8'
                AND doccode1 = '214' ) || ( doccode = '8'
                AND doccode1 = '215' )
                )
                AND b.iastat='P') and date(diary_no_rec_date)>='2014-08-19'
                and os.diary_no is null";*/

                $sql = "SELECT DISTINCT
                    us.section_name AS user_section,
                    u.name AS alloted_to_da,
                    SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4) AS diary_no,
                    SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3 FOR 4) AS diary_year,
                    TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS diary_date,
                    m.pet_name,
                    m.res_name,
                    h.next_dt,
                    CASE
                        WHEN h.main_supp_flag = 0 THEN 'Ready'
                        ELSE 'Not Ready'
                    END AS Status,
                    m.mf_active,
                    CASE
                        WHEN
                            (m.conn_key = '0' OR m.conn_key IS NULL
                                OR m.conn_key = m.diary_no::text)
                        THEN
                            'M'
                        ELSE CASE
                            WHEN
                                (m.conn_key != '0'
                                    AND m.conn_key IS NOT NULL
                                    AND m.conn_key != m.diary_no::text)
                            THEN
                                'C'
                        END
                    END AS mainorconn
                FROM
                    main m
                LEFT JOIN
                    heardt h ON m.diary_no = h.diary_no
                LEFT OUTER JOIN
                    (SELECT DISTINCT diary_no 
                     FROM obj_save 
                     WHERE rm_dt IS NULL AND display = 'Y') os ON m.diary_no = os.diary_no
                LEFT JOIN master.users u ON u.usercode = m.dacode
                    AND (u.display = 'Y' OR u.display IS NULL)
                LEFT JOIN master.usersection us ON us.id = u.section
                WHERE 
                    c_status = 'P' 
                    AND (active_fil_no IS NULL OR active_fil_no = '')
                    AND m.diary_no NOT IN (
                        SELECT m.diary_no
                        FROM main m 
                        INNER JOIN docdetails b ON m.diary_no = b.diary_no
                        WHERE c_status = 'P' 
                        AND (active_fil_no IS NULL OR active_fil_no = '') 
                        AND (
                            (doccode = '8' AND doccode1 = '16') 
                            OR (doccode = '8' AND doccode1 = '79') 
                            OR (doccode = '8' AND doccode1 = '99') 
                            OR (doccode = '8' AND doccode1 = '300') 
                            OR (doccode = '8' AND doccode1 = '28') 
                            OR (doccode = '8' AND doccode1 = '95') 
                            OR (doccode = '8' AND doccode1 = '214') 
                            OR (doccode = '8' AND doccode1 = '215')
                        )
                        AND b.iastat = 'P'
                    ) 
                    AND date(diary_no_rec_date) >= '2014-08-19'
                    AND os.diary_no IS NULL";
                break;
            }
            case 20:
            {
                $sql = "select count(*) total FROM main m where  c_status = 'P'";
                break;
            }
            case 21:
            {
                /*$sql="SELECT DISTINCT
                            us.section_name AS user_section,
                            u.name alloted_to_da,
                            SUBSTR(m.diary_no,
                                1,
                                LENGTH(m.diary_no) - 4) AS diary_no,
                            SUBSTR(m.diary_no, - 4) AS diary_year,
                            DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                            m.pet_name,
                            m.res_name,
                            h.next_dt,
                            CASE
                                WHEN h.main_supp_flag = 0 THEN 'Ready'
                                ELSE 'Not Ready'
                            END AS 'Status',
                            m.mf_active AS 'Misc or Regular',
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
                        FROM
                            main m left join
                            heardt h
                                 ON m.diary_no = h.diary_no
                                 left outer join
                        (select  diary_no,DATEDIFF(now(),max(save_dt)) as days from obj_save where
                        (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y'
                         group by diary_no)
                        os on m.diary_no=os.diary_no and os.days>90
                        LEFT JOIN master.users u ON u.usercode = m.dacode
                        AND (u.display = 'Y' or u.display is null)
                        LEFT JOIN master.usersection us ON us.id = u.section
                        WHERE c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                        and m.diary_no not in
                          (select m.diary_no
                                    FROM main m inner join heardt h on m.diary_no=h.diary_no
                                    left outer join
                                    (select distinct diary_no from obj_save where
                                    (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y')
                                    os on m.diary_no=os.diary_no
                                    WHERE c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                                     and h.board_type='J' and os.diary_no is not null

                                     union

                                     select m.diary_no
                          FROM main m inner join docdetails b on m.diary_no=b.diary_no
                          inner join
                          (select distinct diary_no from obj_save where
                          (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y') os
                          on m.diary_no=os.diary_no
                          WHERE  m.c_status = 'P' and (m.active_fil_no is null or  m.active_fil_no='')
                          AND doccode = '8' AND doccode1 = '226' AND b.iastat='P')
                                    and os.diary_no is not null";*/

                $sql="SELECT DISTINCT
                    us.section_name AS user_section,
                    u.name AS alloted_to_da,
                    SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4) AS diary_no,
                    SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3 FOR 4) AS diary_year,
                    TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS diary_date,
                    m.pet_name,
                    m.res_name,
                    h.next_dt,
                    CASE
                        WHEN h.main_supp_flag = 0 THEN 'Ready'
                        ELSE 'Not Ready'
                    END AS Status,
                    m.mf_active,
                    CASE
                        WHEN
                            (m.conn_key = '0' OR m.conn_key IS NULL
                                OR m.conn_key = m.diary_no::text)
                        THEN
                            'M'
                        ELSE CASE
                            WHEN
                                (m.conn_key != '0'
                                    AND m.conn_key IS NOT NULL
                                    AND m.conn_key != m.diary_no::text)
                            THEN
                                'C'
                        END
                    END AS mainorconn
                FROM
                    main m
                LEFT JOIN
                    heardt h ON m.diary_no = h.diary_no
                LEFT OUTER JOIN
                    (SELECT diary_no, EXTRACT(DAY FROM (NOW() - max(save_dt))) AS days 
                     FROM obj_save 
                     WHERE (rm_dt IS NULL OR rm_dt = '1970-01-01 00:00:00') AND display = 'Y'
                     GROUP BY diary_no) os ON m.diary_no = os.diary_no AND os.days > 90
                LEFT JOIN
                    master.users u ON u.usercode = m.dacode
                    AND (u.display = 'Y' OR u.display IS NULL)
                LEFT JOIN
                    master.usersection us ON us.id = u.section
                WHERE 
                    c_status = 'P' 
                    AND (active_fil_no IS NULL OR active_fil_no = '')
                    AND m.diary_no NOT IN (
                        SELECT m.diary_no
                        FROM main m 
                        INNER JOIN heardt h ON m.diary_no = h.diary_no
                        LEFT OUTER JOIN (
                            SELECT DISTINCT diary_no 
                            FROM obj_save 
                            WHERE (rm_dt IS NULL OR rm_dt = '1970-01-01 00:00:00') 
                            AND display = 'Y') os ON m.diary_no = os.diary_no
                        WHERE c_status = 'P' 
                        AND (active_fil_no IS NULL OR active_fil_no = '') 
                        AND h.board_type = 'J' 
                        AND os.diary_no IS NOT NULL
                        UNION
                        SELECT m.diary_no
                        FROM main m 
                        INNER JOIN docdetails b ON m.diary_no = b.diary_no
                        INNER JOIN (
                            SELECT DISTINCT diary_no 
                            FROM obj_save 
                            WHERE (rm_dt IS NULL OR rm_dt = '1970-01-01 00:00:00') 
                            AND display = 'Y') os ON m.diary_no = os.diary_no
                        WHERE m.c_status = 'P' 
                        AND (m.active_fil_no IS NULL OR m.active_fil_no = '') 
                        AND doccode = '8' 
                        AND doccode1 = '226' 
                        AND b.iastat = 'P'
                    ) 
                    AND os.diary_no IS NOT NULL";
                break;
            }
            case 22:
            {
                /*$sql="SELECT DISTINCT
                        us.section_name AS user_section,
                        u.name alloted_to_da,
                        SUBSTR(m.diary_no,
                            1,
                            LENGTH(m.diary_no) - 4) AS diary_no,
                        SUBSTR(m.diary_no, - 4) AS diary_year,
                        DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                        m.pet_name,
                        m.res_name,
                        h.next_dt,
                        CASE
                            WHEN h.main_supp_flag = 0 THEN 'Ready'
                            ELSE 'Not Ready'
                        END AS 'Status',
                        m.mf_active AS 'Misc or Regular',
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
                    FROM
                        main m left join
                        heardt h
                             ON m.diary_no = h.diary_no
                             left outer join
                    (select  diary_no,DATEDIFF(now(),max(save_dt)) as days from obj_save where
                    (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y'
                     group by diary_no)
                    os on m.diary_no=os.diary_no and os.days<=90
                            LEFT JOIN
                        users u ON u.usercode = m.dacode
                            AND (u.display = 'Y' or u.display is null)
                            LEFT JOIN
                        usersection us ON us.id = u.section
                    WHERE c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                    and m.diary_no not in
                              (select m.diary_no
                                        FROM main m inner join heardt h on m.diary_no=h.diary_no
                                        left outer join
                                        (select distinct diary_no from obj_save where
                                        (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y')
                                        os on m.diary_no=os.diary_no
                                        WHERE c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                                         and h.board_type='J' and os.diary_no is not null

                                         union

                                         select m.diary_no
                              FROM main m inner join docdetails b on m.diary_no=b.diary_no
                              inner join
                              (select distinct diary_no from obj_save where
                              (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y') os
                              on m.diary_no=os.diary_no
                              WHERE  m.c_status = 'P' and (m.active_fil_no is null or  m.active_fil_no='')
                              AND doccode = '8' AND doccode1 = '226' AND b.iastat='P')
                                        and os.diary_no is not null";*/

                $sql="SELECT DISTINCT
                    us.section_name AS user_section,
                    u.name AS alloted_to_da,
                    SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4) AS diary_no,
                    SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3 FOR 4) AS diary_year,
                    TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS diary_date,
                    m.pet_name,
                    m.res_name,
                    h.next_dt,
                    CASE
                        WHEN h.main_supp_flag = 0 THEN 'Ready'
                        ELSE 'Not Ready'
                    END AS Status,
                    m.mf_active,
                    CASE
                        WHEN
                            (m.conn_key = '0' OR m.conn_key IS NULL
                                OR m.conn_key = m.diary_no::text)
                        THEN
                            'M'
                        ELSE CASE
                            WHEN
                                (m.conn_key != '0'
                                    AND m.conn_key IS NOT NULL
                                    AND m.conn_key != m.diary_no::text)
                            THEN
                                'C'
                        END
                    END AS mainorconn
                FROM
                    main m
                LEFT JOIN
                    heardt h ON m.diary_no = h.diary_no
                LEFT OUTER JOIN
                    (SELECT diary_no, EXTRACT(DAY FROM (NOW()::timestamp - max(save_dt)::timestamp)) AS days
                     FROM obj_save 
                     WHERE (rm_dt IS NULL OR rm_dt = '1970-01-01 00:00:00') AND display = 'Y'
                     GROUP BY diary_no) os ON m.diary_no = os.diary_no AND os.days <= 90
                LEFT JOIN
                    master.users u ON u.usercode = m.dacode
                    AND (u.display = 'Y' OR u.display IS NULL)
                LEFT JOIN
                    master.usersection us ON us.id = u.section
                WHERE 
                    c_status = 'P' 
                    AND (active_fil_no IS NULL OR active_fil_no = '')
                    AND m.diary_no NOT IN (
                        SELECT m.diary_no
                        FROM main m 
                        INNER JOIN heardt h ON m.diary_no = h.diary_no
                        LEFT OUTER JOIN (
                            SELECT DISTINCT diary_no 
                            FROM obj_save 
                            WHERE (rm_dt IS NULL OR rm_dt = '1970-01-01 00:00:00') 
                            AND display = 'Y') os ON m.diary_no = os.diary_no
                        WHERE c_status = 'P' 
                        AND (active_fil_no IS NULL OR active_fil_no = '') 
                        AND h.board_type = 'J' 
                        AND os.diary_no IS NOT NULL
                        UNION
                        SELECT m.diary_no
                        FROM main m 
                        INNER JOIN docdetails b ON m.diary_no = b.diary_no
                        INNER JOIN (
                            SELECT DISTINCT diary_no 
                            FROM obj_save 
                            WHERE (rm_dt IS NULL OR rm_dt = '1970-01-01 00:00:00') 
                            AND display = 'Y') os ON m.diary_no = os.diary_no
                        WHERE m.c_status = 'P' 
                        AND (m.active_fil_no IS NULL OR m.active_fil_no = '') 
                        AND doccode = '8' 
                        AND doccode1 = '226' 
                        AND b.iastat = 'P'
                    ) 
                    AND os.diary_no IS NOT NULL";
                break;
            }
            default:
                break;
        }
        $query = $this->db->query($sql);

        if($query->getNumRows() >= 1)
        {
            return $query->getResultArray();
        }
        else
        {
            return false;
        }
    }

    public function pendency()
    {
        /*$query="select x.agency_state,x.ref_agency_state_id,(select count(1) from main where fil_dt is not null and c_status='P') as total,
        coalesce(sum(`SLP(C)`),0) as sc,coalesce(sum(`SLP(Crl)`),0) as sr,coalesce(sum(`C.A`),0) as ca ,
        coalesce(sum(`Crl.A`),0)  as cra,coalesce(sum(`W.P.(C)`),0) as wpc,coalesce(sum(`W.P.(Crl)`),0) as wpcr,
        coalesce(sum(`T.P.(C)`),0) as tpc,coalesce(sum(`T.P.(Crl)`),0) as tpcr,coalesce(sum(`R.P.(C)`),0) as rpc,
        coalesce(sum(`R.P.(Crl)`),0) as rpcr,coalesce(sum(`T.C.(C)`),0) as tc,coalesce(sum(`T.C.(Crl)`),0) as tcr,
        coalesce(sum(`S.L.P(C)...CC`),0) as slpcc,coalesce(sum(`SLP(CRL)...CRLMP NO`),0) as slpcrlmp,
        coalesce(sum(`ORGNNL.SUIT No`),0) as ORGNNLSUIT,coalesce(sum(`DTH.REF.CASE(R)`),0) as DTHREFCASE,
        coalesce(sum(`CONMT.PET.(C)`),0) as conmtpet,coalesce(sum(`CONMT.PET.(Crl.) `),0) as conmtpetcr,
        coalesce(sum(`TAX.REF.CASE(C)`),0) as taxrefc,coalesce(sum(`SPL.REF.`),0) as slpref,
        coalesce(sum(`ELECT.PET.(C)`),0) as electpet,coalesce(sum(`ARBIT.Petition`),0) as arbitc,
        coalesce(sum(`CURATIVE PET(C)`),0) as curativepetc,coalesce(sum(`CURATIVE PET(R)`),0) as curativepetr,
        coalesce(sum(`Ref.U/A 317(1) `),0) as refua317,coalesce(sum(`MOTION(R) `),0) as motionr,
        coalesce(sum(`D.NO. `),0) as dno,coalesce(sum(`F.No.`),0) as fno,coalesce(sum(`SMW(C)`),0) as smwc,
        coalesce(sum(`SMW(Crl)`),0) as smwcrl,coalesce(sum(`SMC(C)`),0) as smc,
        coalesce(sum(`SMC(Crl)`),0) as smccrl,coalesce(sum(`REF. U/S 143`),0) as ref143,coalesce(sum(`REF. U/S 14 RTI`),0) as ref14rti  from (
        select f.agency_state,f.ref_agency_state_id,
        case when active_casetype_id=1 then total_pendency end as 'SLP(C)',
        case when active_casetype_id=2 then total_pendency end as 'SLP(Crl)',
        case when active_casetype_id=3 then total_pendency end as 'C.A',
        case when active_casetype_id=4 then total_pendency end as 'Crl.A',
        case when active_casetype_id=5 OR active_casetype_id=15 then total_pendency end as 'W.P.(C)',
        case when active_casetype_id=6 OR active_casetype_id=16 then total_pendency end as 'W.P.(Crl)',
        case when active_casetype_id=7 then total_pendency end as 'T.P.(C)',
        case when active_casetype_id=8 then total_pendency end as 'T.P.(Crl)',
        case when active_casetype_id=9 then total_pendency end as 'R.P.(C)',
        case when active_casetype_id=10 then total_pendency end as 'R.P.(Crl)',
        case when active_casetype_id=11 then total_pendency end as 'T.C.(C)',
        case when active_casetype_id=12 then total_pendency end as 'T.C.(Crl)',
        case when active_casetype_id=13 then total_pendency end as 'S.L.P(C)...CC',
        case when active_casetype_id=14 then total_pendency end as 'SLP(CRL)...CRLMP NO',
        case when active_casetype_id=17 then total_pendency  end as 'ORGNNL.SUIT No',
        case when active_casetype_id=18 then total_pendency  end as 'DTH.REF.CASE(R)',
        case when active_casetype_id=19 then total_pendency  end as 'CONMT.PET.(C)',
        case when active_casetype_id=20 then total_pendency  end as 'CONMT.PET.(Crl.) ',
        case when active_casetype_id=21 then total_pendency  end as 'TAX.REF.CASE(C)',
        case when active_casetype_id=22 then total_pendency  end as 'SPL.REF.',
        case when active_casetype_id=23 then total_pendency  end as 'ELECT.PET.(C)',
        case when active_casetype_id=24 then total_pendency  end as 'ARBIT.PETITION',
        case when active_casetype_id=25 then total_pendency  end as 'CURATIVE PET(C)',
        case when active_casetype_id=26 then total_pendency  end as 'CURATIVE PET(R)',
        case when active_casetype_id=27 then total_pendency  end as 'Ref.U/A 317(1) ',
        case when active_casetype_id=28 then total_pendency  end as 'MOTION(R) ',
        case when active_casetype_id=29 OR active_casetype_id=31  then total_pendency  end as 'D.NO. ',
        case when active_casetype_id=30 then total_pendency  end as 'F.No.',
        case when active_casetype_id=32 then total_pendency  end as 'SMW(C)',
        case when active_casetype_id=33 then total_pendency  end as 'SMW(Crl)',
        case when active_casetype_id=34 then total_pendency  end as 'SMC(C)',
        case when active_casetype_id=35 then total_pendency  end as 'SMC(Crl)',
        case when active_casetype_id=36 then total_pendency  end as 'REF. U/S 143',
        case when active_casetype_id=37 then total_pendency  end as 'REF. U/S 14 RTI'
        from
        (select ras.agency_state,m.ref_agency_state_id,c.short_description,m.active_casetype_id,
        count(*) as total_pendency
        from main m
        inner join ref_agency_state ras on
        m.ref_agency_state_id=ras.cmis_state_id
        left join casetype c on
        m.active_casetype_id=c.casecode
        where m.fil_dt is not null and m.c_status='P'
        group by  ras.agency_state,m.ref_agency_state_id,c.short_description,m.active_casetype_id
        order by ras.agency_state,m.active_casetype_id ) f
        ) x where x.ref_agency_state_id not in (9999) group by x.ref_agency_state_id order by x.agency_state";*/

        $queryString = 'SELECT x.agency_state,
            x.ref_agency_state_id,
            (
                SELECT COUNT(*) 
                FROM main 
                WHERE fil_dt IS NOT NULL
                AND c_status = \'P\'
            ) AS total,
           COALESCE(SUM("SLP(C)"), 0) AS sc,
           COALESCE(SUM("SLP(Crl)"), 0) AS sr,
           COALESCE(SUM("C.A"), 0) AS ca,
           COALESCE(SUM("Crl.A"), 0) AS cra,
           COALESCE(SUM("W.P.(C)"), 0) AS wpc,
           COALESCE(SUM("W.P.(Crl)"), 0) AS wpcr,
           COALESCE(SUM("T.P.(C)"), 0) AS tpc,
           COALESCE(SUM("T.P.(Crl)"), 0) AS tpcr,
           COALESCE(SUM("R.P.(C)"), 0) AS rpc,
           COALESCE(SUM("R.P.(Crl)"), 0) AS rpcr,
           COALESCE(SUM("T.C.(C)"), 0) AS tc,
           COALESCE(SUM("T.C.(Crl)"), 0) AS tcr,
           COALESCE(SUM("S.L.P(C)...CC"), 0) AS slpcc,
           COALESCE(SUM("SLP(CRL)...CRLMP NO"), 0) AS slpcrlmp,
           COALESCE(SUM("ORGNNL.SUIT No"), 0) AS orgnnlsuit,
           COALESCE(SUM("DTH.REF.CASE(R)"), 0) AS dthrefcase,
           COALESCE(SUM("CONMT.PET.(C)"), 0) AS conmtpet,
           COALESCE(SUM("CONMT.PET.(Crl.) "), 0) AS conmtpetcr,
           COALESCE(SUM("TAX.REF.CASE(C)"), 0) AS taxrefc,
           COALESCE(SUM("SPL.REF."), 0) AS slpref,
           COALESCE(SUM("ELECT.PET.(C)"), 0) AS electpet,
           COALESCE(SUM("ARBIT.PETITION"), 0) AS arbitc,
           COALESCE(SUM("CURATIVE PET(C)"), 0) AS curativepetc,
           COALESCE(SUM("CURATIVE PET(R)"), 0) AS curativepetr,
           COALESCE(SUM("Ref.U/A 317(1) "), 0) AS refua317,
           COALESCE(SUM("MOTION(R) "), 0) AS motionr,
           COALESCE(SUM("D.NO. "), 0) AS dno,
           COALESCE(SUM("F.No."), 0) AS fno,
           COALESCE(SUM("SMW(C)"), 0) AS smwc,
           COALESCE(SUM("SMW(Crl)"), 0) AS smwcrl,
           COALESCE(SUM("SMC(C)"), 0) AS smc,
           COALESCE(SUM("SMC(Crl)"), 0) AS smccrl,
           COALESCE(SUM("REF. U/S 143"), 0) AS ref143,
           COALESCE(SUM("REF. U/S 14 RTI"), 0) AS ref14rti
        FROM
        (
            SELECT f.agency_state,
               f.ref_agency_state_id,
               CASE 
                   WHEN active_casetype_id = 1 THEN total_pendency 
                   ELSE NULL 
               END AS "SLP(C)",
               CASE 
                   WHEN active_casetype_id = 2 THEN total_pendency 
                   ELSE NULL 
               END AS "SLP(Crl)",
               CASE 
                   WHEN active_casetype_id = 3 THEN total_pendency 
                   ELSE NULL 
               END AS "C.A",
               CASE 
                   WHEN active_casetype_id = 4 THEN total_pendency 
                   ELSE NULL 
               END AS "Crl.A",
               CASE 
                   WHEN active_casetype_id = 5 OR active_casetype_id = 15 THEN total_pendency 
                   ELSE NULL 
               END AS "W.P.(C)",
               CASE 
                   WHEN active_casetype_id = 6 OR active_casetype_id = 16 THEN total_pendency 
                   ELSE NULL 
               END AS "W.P.(Crl)",
               CASE 
                   WHEN active_casetype_id = 7 THEN total_pendency 
                   ELSE NULL 
               END AS "T.P.(C)",
               CASE 
                   WHEN active_casetype_id = 8 THEN total_pendency 
                   ELSE NULL 
               END AS "T.P.(Crl)",
               CASE 
                   WHEN active_casetype_id = 9 THEN total_pendency 
                   ELSE NULL 
               END AS "R.P.(C)",
               CASE 
                   WHEN active_casetype_id = 10 THEN total_pendency 
                   ELSE NULL 
               END AS "R.P.(Crl)",
               CASE 
                   WHEN active_casetype_id = 11 THEN total_pendency 
                   ELSE NULL 
               END AS "T.C.(C)",
               CASE 
                   WHEN active_casetype_id = 12 THEN total_pendency 
                   ELSE NULL 
               END AS "T.C.(Crl)",
               CASE 
                   WHEN active_casetype_id = 13 THEN total_pendency 
                   ELSE NULL 
               END AS "S.L.P(C)...CC",
               CASE 
                   WHEN active_casetype_id = 14 THEN total_pendency 
                   ELSE NULL 
               END AS "SLP(CRL)...CRLMP NO",
               CASE 
                   WHEN active_casetype_id = 17 THEN total_pendency 
                   ELSE NULL 
               END AS "ORGNNL.SUIT No",
               CASE 
                   WHEN active_casetype_id = 18 THEN total_pendency 
                   ELSE NULL 
               END AS "DTH.REF.CASE(R)",
               CASE 
                   WHEN active_casetype_id = 19 THEN total_pendency 
                   ELSE NULL 
               END AS "CONMT.PET.(C)",
               CASE 
                   WHEN active_casetype_id = 20 THEN total_pendency 
                   ELSE NULL 
               END AS "CONMT.PET.(Crl.) ",
               CASE 
                   WHEN active_casetype_id = 21 THEN total_pendency 
                   ELSE NULL 
               END AS "TAX.REF.CASE(C)",
               CASE 
                   WHEN active_casetype_id = 22 THEN total_pendency 
                   ELSE NULL 
               END AS "SPL.REF.",
               CASE 
                   WHEN active_casetype_id = 23 THEN total_pendency 
                   ELSE NULL 
               END AS "ELECT.PET.(C)",
               CASE 
                   WHEN active_casetype_id = 24 THEN total_pendency 
                   ELSE NULL 
               END AS "ARBIT.PETITION",
               CASE 
                   WHEN active_casetype_id = 25 THEN total_pendency 
                   ELSE NULL 
               END AS "CURATIVE PET(C)",
               CASE 
                   WHEN active_casetype_id = 26 THEN total_pendency 
                   ELSE NULL 
               END AS "CURATIVE PET(R)",
               CASE 
                   WHEN active_casetype_id = 27 THEN total_pendency 
                   ELSE NULL 
               END AS "Ref.U/A 317(1) ",
               CASE 
                   WHEN active_casetype_id = 28 THEN total_pendency 
                   ELSE NULL 
               END AS "MOTION(R) ",
               CASE 
                   WHEN active_casetype_id = 29 OR active_casetype_id = 31 THEN total_pendency 
                   ELSE NULL 
               END AS "D.NO. ",
               CASE 
                   WHEN active_casetype_id = 30 THEN total_pendency 
                   ELSE NULL 
               END AS "F.No.",
               CASE 
                   WHEN active_casetype_id = 32 THEN total_pendency 
                   ELSE NULL 
               END AS "SMW(C)",
               CASE 
                   WHEN active_casetype_id = 33 THEN total_pendency 
                   ELSE NULL 
               END AS "SMW(Crl)",
               CASE 
                   WHEN active_casetype_id = 34 THEN total_pendency 
                   ELSE NULL 
               END AS "SMC(C)",
               CASE 
                   WHEN active_casetype_id = 35 THEN total_pendency 
                   ELSE NULL 
               END AS "SMC(Crl)",
               CASE 
                   WHEN active_casetype_id = 36 THEN total_pendency 
                   ELSE NULL 
               END AS "REF. U/S 143",
               CASE 
                   WHEN active_casetype_id = 37 THEN total_pendency 
                   ELSE NULL 
               END AS "REF. U/S 14 RTI"
            FROM
            (
                SELECT ras.agency_state,
                m.ref_agency_state_id,
                c.short_description,
                m.active_casetype_id,
                COUNT(*) AS total_pendency
                FROM main m
                INNER JOIN master.ref_agency_state ras 
                ON m.ref_agency_state_id = ras.cmis_state_id
                LEFT JOIN master.casetype c 
                ON m.active_casetype_id = c.casecode
                WHERE m.fil_dt IS NOT NULL
                AND m.c_status = \'P\'
                GROUP BY ras.agency_state,
                m.ref_agency_state_id,
                c.short_description,
                m.active_casetype_id
                ORDER BY ras.agency_state,
                m.active_casetype_id
            ) f
        ) x
        WHERE x.ref_agency_state_id NOT IN (9999)
        GROUP BY x.ref_agency_state_id, x.agency_state
        ORDER BY x.agency_state';

        $query = $this->db->query($queryString);
        $result = $query->getResultArray();
        // pr($result);
        // return $result;

        $srno = 1;
        $html = '';
        $total_sc = 0;                    $total_sr = 0;
        $total_ca = 0;                    $total_cra = 0;
        $total_wpc = 0;                   $total_wpcr = 0;
        $total_tpc = 0;                   $total_tpcr = 0;
        $total_rpc = 0;                   $total_rpcr = 0;                    
        $total_tc = 0;                    $total_tcr = 0;
        $total_slpcc = 0;                 $total_slpcrlmp = 0;
        $total_ORGNNLSUIT = 0;            $total_DTHREFCASE = 0;
        $total_conmtpet = 0;              $total_conmtpetcr = 0;
        $total_taxrefc = 0;               $total_slpref = 0;
        $total_electpet = 0;              $total_arbitc = 0;
        $total_curativepetc = 0;          $total_curativepetr = 0;
        $total_refua317 = 0;              $total_motionr = 0;
        $total_dno = 0;                   $total_fno = 0;
        $total_smwc = 0;                  $total_smwcrl = 0;
        $total_smc = 0;                   $total_smccrl = 0;
        $total_ref143 = 0;                $total_ref14rti = 0;

        $html .= '<div class="col-12 col-sm-12 col-md-12 col-lg-12">';
        $html .= '<div class="table-responsive">';
        $html .= '<h2 style="text-align: center;text-transform: capitalize;color: blue;"> HighCourt wise Pendency Report as on '.date('d/m/Y').'</h2>';

        $html .= '<table id="diaryReport" class="table table-striped table-hover centerview" width="100%" border="1" cellspacing="1">
            <thead>
                <tr bgcolor="#dcdcdc">
                    <th style="text-align: center;">Sr.No.</th>
                    <th width="20%" style="text-align: left;">Agency</th>
                    <th>SLP(C)</th>
                    <th>SLP(Crl)</th>
                    <th>C.A</th>
                    <th>Cr.A</th>
                    <th>WP(C)</th>
                    <th>WP(Crl)</th>
                    <th>TP(C)</th>
                    <th>TP(Crl)</th>
                    <th>RP(C)</th>
                    <th>RP(Crl)</th>
                    <th>TC(C)</th>
                    <th>TC(Crl)</th>
                    <th>SC..CC</th>
                    <th>SR...CRLMP</th>
                    <th>ORGNNL.SUIT</th>
                    <th>DTH.REF.CASE(R)</th>
                    <th>CONMT.PET.(C)</th>
                    <th>CONMT.PET.(Crl.) </th>
                    <th>TAX.REF.CASE(C)</th>
                    <th>SPL.REF.</th>
                    <th>ELECT.PET.(C)</th>
                    <th>ARBIT.PETITION</th>
                    <th>CURATIVE PET(C)</th>
                    <th>CURATIVE PET(R)</th>
                    <th>Ref.U/A 317(1)</th>
                    <th>MOTION(R)</th>
                    <th>D.NO.</th>
                    <th>F.No.</th>
                    <th>SMW(C)</th>
                    <th>SMW(Crl)</th>
                    <th>SMC(C)</th>
                    <th>SMC(Crl)</th>
                    <th>REF. U/S 143</th>
                    <th>REF. U/S 14 RTI</th>
                </tr>
            </thead>
            <tbody>';
        foreach ($result as $key => $row)
        {
            $total = $row['total'];

            $html .= '<tr>';
            $html .= '<td style="text-align: center;">'.$srno.'</td>';
            $html .= '<td>'.$row['agency_state'].'</td>';
            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=1">'.$row['sc'].'
                        </a></td>';
            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=2">'.$row['sr'].'
                        </a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=3">'.$row['ca'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=4">'.$row['cra'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=5">'.$row['wpc'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=6">'.$row['wpcr'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=7">'.$row['tpc'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=8">'.$row['tpcr'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=9">'.$row['rpc'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=10">'.$row['rpcr'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=11">'.$row['tc'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=12">'.$row['tcr'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=13">'.$row['slpcc'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=14">'.$row['slpcrlmp'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=17">'.$row['orgnnlsuit'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=18">'.$row['dthrefcase'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=19">'.$row['conmtpet'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=20">'.$row['conmtpetcr'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=21">'.$row['taxrefc'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=22">'.$row['slpref'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=23">'.$row['electpet'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=24">'.$row['arbitc'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=25">'.$row['curativepetc'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=26">'.$row['curativepetr'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=27">'.$row['refua317'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=28">'.$row['motionr'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=29">'.$row['dno'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=30">'.$row['fno'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=32">'.$row['smwc'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=33">'.$row['smwcrl'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=34">'.$row['smc'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=35">'.$row['smccrl'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=36">'.$row['ref143'].'</a></td>';

            $html .= '<td><a target="_blank" href="'.base_url('Reports/PendencyReport/DetailedPendency/details').'?state='.urlencode($row['agency_state']).'&agency='.$row['ref_agency_state_id'].'&case_type=37">'.$row['ref14rti'].'</a></td>';
            $html .= '</tr>';

            $srno++;
            $total_sc += $row['sc'];                              $total_sr += $row['sr'];
            $total_ca += $row['ca'];                              $total_cra += $row['cra'];
            $total_wpc += $row['wpc'];                            $total_wpcr += $row['wpcr'];
            $total_tpc += $row['tpc'];                            $total_tpcr += $row['tpcr'];
            $total_rpc += $row['rpc'];                            $total_rpcr += $row['rpcr'];
            $total_tc += $row['tc'];                              $total_tcr += $row['tcr'];
            $total_slpcc += $row['slpcc'];                        $total_slpcrlmp += $row['slpcrlmp'];
            $total_ORGNNLSUIT += $row['orgnnlsuit'];              $total_DTHREFCASE += $row['dthrefcase'];
            $total_conmtpet += $row['conmtpet'];                  $total_conmtpetcr += $row['conmtpetcr'];
            $total_taxrefc += $row['taxrefc'];                    $total_slpref += $row['slpref'];
            $total_electpet += $row['electpet'];                  $total_arbitc += $row['arbitc'];
            $total_curativepetc += $row['curativepetc'];          $total_curativepetr += $row['curativepetr'];
            $total_refua317 += $row['refua317'];                  $total_motionr += $row['motionr'];
            $total_dno += $row['dno'];                            $total_fno += $row['fno'];
            $total_smwc += $row['smwc'];                          $total_smwcrl += $row['smwcrl'];
            $total_smc += $row['smc'];                            $total_smccrl += $row['smccrl'];
            $total_ref143 += $row['ref143'];                      $total_ref14rti += $row['ref14rti'];
        }
        $html .= '<tr style="font-weight: bold;"><td colspan="2">Current Pendency:'.$total.'</td><td>'.$total_sc.'</td><td>'.$total_sr.'</td>
            <td>'.$total_ca.'</td><td>'.$total_cra.'</td><td>'.$total_wpc.'</td>
            <td>'.$total_wpcr.'</td><td>'.$total_tpc.'</td><td>'.$total_tpcr.'</td>
            <td>'.$total_rpc.'</td><td>'.$total_rpcr.'</td><td>'.$total_tc.'</td>
            <td>'.$total_tcr.'</td><td>'.$total_slpcc.'</td><td>'.$total_slpcrlmp.'</td>
            <td>'.$total_ORGNNLSUIT.'</td><td>'.$total_DTHREFCASE.'</td><td>'.$total_conmtpet.'</td>
            <td>'.$total_conmtpetcr.'</td><td>'.$total_taxrefc.'</td><td>'.$total_slpref.'</td>
            <td>'.$total_electpet.'</td><td>'.$total_arbitc.'</td><td>'.$total_curativepetc.'</td>
            <td>'.$total_curativepetr.'</td><td>'.$total_refua317.'</td><td>'.$total_motionr.'</td>
            <td>'.$total_dno.'</td><td>'.$total_fno.'</td><td>'.$total_smwc.'</td>
            <td>'.$total_smwcrl.'</td><td>'.$total_smc.'</td><td>'.$total_smccrl.'</td>
            <td>'.$total_ref143.'</td><td>'.$total_ref14rti.'</td>
        </tr>';
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div></div>';
        return $html;
    }

    public function details()
    {
        $heading = "";
        if (isset($_GET['agency']))
        {
            $agency = $_GET['agency'];
            $state = $_GET['state'];
            $condition_agency = " AND m.ref_agency_state_id = $agency";
            $heading .= " State-" . $state;
        }
        else
        {
            $agency = "";
            $state = "";
            $condition_agency = "";
        }

        if (isset($_GET['case_type']))
        {
            $case_type = $_GET['case_type'];
            $condition_case = " AND m.active_casetype_id = $case_type";

            $sqlString = "SELECT casename FROM master.casetype WHERE casecode = $case_type";
            $query = $this->db->query($sqlString);
            $row = $query->getRowArray();

            $casename = $row['casename'];
            $heading .= " and Case Type-" . $casename;
        }
        else
        {
            $case_type = "";
            $condition_case = "";
        }

        if(isset($_GET['year']))
        {
            $year = $_GET['year'];
            $condition_year = " AND m.active_reg_year = $year";
            $heading .= " and Registration Year-" . $year;
        }
        else
        {
            $year = "";
            $condition_year = "";
        }

        if (isset($_GET['section']))
        {
            $section = $_GET['section'];
            $condition_sec = " AND b.id = $section";

            $sqlString = "SELECT section_name FROM master.usersection WHERE id = $section";
            $query = $this->db->query($sqlString);
            $row = $query->getRowArray();

            $sectionname = $row['section_name'];
            $heading .= " and Section-" . $sectionname;
        }
        else
        {
            $section = "";
            $condition_sec = "";
        }

        $sno = 1;
        $sqlString = "SELECT
            CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3 FOR 4) AS INTEGER) AS diary_no,
            CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER) AS diary_year,
            pet_name, 
            res_name, 
            reg_no_display, 
            empid, 
            dacode, 
            name, 
            type_name, 
            section_name 
        FROM main m 
        LEFT JOIN master.users AS u ON m.dacode = u.usercode 
        LEFT JOIN master.usertype AS ut ON ut.id = u.usertype 
        LEFT JOIN master.usersection AS b ON b.id = u.section 
        WHERE c_status = 'P' 
        AND m.fil_dt IS NOT NULL 
        $condition_sec 
        $condition_year 
        $condition_case 
        $condition_agency";

        $query = $this->db->query($sqlString);
        $data['result'] = $query->getResultArray();
        $data['heading'] = $heading;
        return $data;
    }

    public function hc_not_before_required_get()
    {
        $ucode = session()->get('login')['usercode'];
        $mainhead = $_POST['mainhead'];
        if($mainhead == 'M')
        {
            $main_head = " AND NOT (e.fil_no_fh IS NOT NULL AND e.fil_no_fh != '') ";
        }
        if($mainhead == 'F')
        {
            $main_head = " AND (e.fil_no_fh IS NOT NULL AND e.fil_no_fh != '') ";
        }

        $html = '';
        $sql = "
        SELECT 
            e.diary_no AS diary_no, 
            e.reg_no_display AS case_no, 
            concat(pet_name, ' Vs. ', res_name) AS cause_title,
            tentative_section(a.diary_no) AS tentative_section, 
            tentative_da(a.diary_no) AS tentative_da,
            concat(ol.first_name, ' ', ol.sur_name) AS judge_name,
            
            CASE 
                WHEN a.ct_code = 4 THEN (
                    SELECT skey
                    FROM master.casetype ct
                    WHERE ct.display = 'Y'
                    AND ct.casecode = a.lct_casetype
                )
                ELSE (
                    SELECT type_sname
                    FROM master.lc_hc_casetype d
                    WHERE d.lccasecode = a.lct_casetype
                    AND d.display = 'Y'
                )
            END AS type_sname,
            
            lct_caseno, 
            lct_caseyear,
            
            CASE 
                WHEN a.ct_code = 3 THEN 
                    CASE 
                        WHEN a.l_state = 490506 THEN (
                            SELECT court_name AS Name
                            FROM master.state s 
                            LEFT JOIN master.delhi_district_court d 
                                ON s.state_code = d.state_code 
                                AND s.district_code = d.district_code
                            WHERE s.id_no = a.l_dist
                            AND s.display = 'Y'
                        )
                        ELSE (
                            SELECT Name
                            FROM master.state s
                            WHERE s.id_no = a.l_dist
                            AND s.display = 'Y'
                        )
                    END
                ELSE (
                    SELECT agency_name
                    FROM master.ref_agency_code c
                    WHERE c.cmis_state_id = a.l_state
                    AND c.id = a.l_dist
                    AND c.is_deleted = 'f'
                )
            END AS agency_name

        FROM lowerct a
        INNER JOIN main e ON e.diary_no = a.diary_no
        LEFT JOIN master.state b ON a.l_state = b.id_no AND b.display = 'Y'
        LEFT JOIN lowerct_judges lg ON lg.lct_display = 'Y' AND lg.lowerct_id = a.lower_court_id
        LEFT JOIN master.org_lower_court_judges ol ON ol.is_deleted = 'f' AND ol.id = lg.judge_id
            AND ol.id IN (476,3729,18337,18706,21647,27707,41020,55467,55607, 
                          17576,17104,17869,55677,17472,11152,18028, 792,1202,3156,19330,
                          55085,1274,19202,56437,1509,19053,20542,55826,55985,56457,19848,
                          20450,28919,41884,43441,612,1038,17922,19008,19009,22845,24270,
                          27534,42313,44698,44806,46321,51175,1389,18462,18846,44905,56571,
                          18546,19144,43871,52934,56561,57,2141,3334,29179,42236,43954,55728,
                          56608,864,2505,18004,19280,1995,22419,23959,24409,25213,25236,49572,
                          56311,56527,1949,2524,3147,3240,18796,19288,20686,27337,44944,47867,
                          50986,56658,810,1066,1221,22353,22536,22614,22799,26856,28230,40930,
                          46169,47848,48374,50047,50091,51877,54866,55435,56643,56678,28868,
                          41711,55804,56477)
        LEFT JOIN not_before nb ON nb.diary_no = a.diary_no AND nb.notbef = 'N' AND nb.j1 >= 264
        LEFT JOIN master.judge j ON j.jcode = nb.j1
        WHERE nb.j1 IS NULL 
            AND lg.judge_id IS NOT NULL 
            AND ol.id IS NOT NULL 
            AND e.c_status = 'P' 
            -- and year(e.diary_no_rec_date) = '2019'
            AND a.ct_code = 1 
            -- and a.is_order_challenged = 'Y'
            $main_head
            AND a.lw_display = 'Y'
        GROUP BY a.diary_no, ol.id, e.diary_no, e.reg_no_display, pet_name, res_name, a.lct_casetype, a.l_state, a.l_dist, a.lct_caseno, a.lct_caseyear, a.ct_code
        ORDER BY tentative_section(a.diary_no), tentative_da(a.diary_no)";

        $sqlQuery = $this->db->query($sql);
        if($sqlQuery->getNumRows() >= 1)
        {
            $result = $sqlQuery->getResultArray();
            $sno = 1;
            $html .= '<div id="prnnt" style="text-align: center;">';
            $html .= '<h3 style="text-align:center;">Not Before Verification</h3>';
            $html .= '<table id="customers">';
            $html .= '<tr>
                <td width="10%" style="font-weight: bold; color: #dce38d; background: #918788;">SrNo.</td>
                <td width="15%" style="font-weight: bold; color: #dce38d; background: #918788;">Case No. / Diary No.</td>
                <td width="25%" style="font-weight: bold; color: #dce38d; background: #918788;">Cause Title</td>
                <td width="15%" style="font-weight: bold; color: #dce38d; background: #918788;">Honble Judge Name</td>
                <td width="15%" style="font-weight: bold; color: #dce38d; background: #918788;">Lower Court Case No.</td>
                <td width="15%" style="font-weight: bold; color: #dce38d; background: #918788;">Agency</td>
                <td width="15%" style="font-weight: bold; color: #dce38d; background: #918788;">Section / DA</td>
            </tr>';
            foreach ($result as $key => $ro)
            {
                $sno1 = $sno % 2;

                $html .= "<tr class='".($sno1 == 1 ? 'odd' : 'even')."'>";
                $html .= "<td align='left' style='vertical-align: top;'>$sno</td>";
                $html .= "<td align='left' style='vertical-align: top;'>".esc($ro['case_no'])." @ ".esc($ro['diary_no'])."</td>";
                $html .= "<td align='left' style='vertical-align: top;'>".esc($ro['cause_title'])."</td>";
                $html .= "<td align='left' style='vertical-align: top;'>".esc($ro['judge_name'])."</td>";
                $html .= "<td align='left' style='vertical-align: top;'>".esc($ro['type_sname'])." ".esc($ro['lct_caseno'])." / ".esc($ro['lct_caseyear'])."</td>";
                $html .= "<td align='left' style='vertical-align: top;'>".esc($ro['agency_name'])."</td>";
                $html .= "<td align='left' style='vertical-align: top;'>".esc($ro['tentative_section'])."<br>".esc($ro['tentative_da'])."</td>";
                $html .= "</tr>";

                $sno++;
            }
            $html .= '</table>';
            $html .= '</div>';
            $html .= '<div class="col-md-12" style="text-align: center;"><input name="prnnt1" type="button" id="prnnt1" value="Print"></div>';
        }
        else
        {
            $html .= '<div class="nofound" style = "color:red; font-weight:bold; text-align: center;">No Recrods Found</div>';
            return $html;
        }
        return $html;
    }

    function getDisposal_AsPer_OrderDate($fromDate = null, $toDate = null, $id = null)
    {
        if($id == 1)
        {
            /*$sql="select (SELECT count(*)
            FROM `main` m
            WHERE DATE(fil_dt)  between '".$fromDate."' and '".$toDate."') as institution,
            (SELECT count(*)
            FROM `main` m
            WHERE fil_no is not null and fil_no!='' and c_status='P') as current_pendency,
            sum(case when m.fil_no is not null and m.fil_no !='' then 1 else 0 end) registered_disposal,
            sum(case when m.fil_no is null or m.fil_no ='' then 1 else 0 end) diary_disposal,
            SUM(CASE WHEN m.mf_active='M' then 1 else 0 end) misc_disposal,
            SUM(CASE WHEN m.mf_active='F' then 1 else 0 end) regular_disposal,
            count(*) total_disposal
            FROM main m INNER JOIN heardt h
            ON m.diary_no=h.diary_no INNER JOIN dispose d
            ON m.diary_no=d.diary_no INNER JOIN judge j ON FIND_IN_SET (j.jcode, d.jud_id)=1
            WHERE j.is_retired='N' AND d.ord_dt
            between '".$fromDate."' and '".$toDate."' AND c_status='D' AND h.board_type ='J'
            and (mf_active='M' OR mf_active='F')";*/

            $sql = "SELECT 
                (SELECT COUNT(*)
                 FROM main m
                 WHERE m.fil_dt::date BETWEEN '$fromDate' AND '$toDate') AS institution,
                
                (SELECT COUNT(*)
                 FROM main m
                 WHERE m.fil_no IS NOT NULL AND m.fil_no != '' AND m.c_status = 'P') AS current_pendency,
                
                SUM(CASE WHEN m.fil_no IS NOT NULL AND m.fil_no != '' THEN 1 ELSE 0 END) AS registered_disposal,
                
                SUM(CASE WHEN m.fil_no IS NULL OR m.fil_no = '' THEN 1 ELSE 0 END) AS diary_disposal,
                
                SUM(CASE WHEN m.mf_active = 'M' THEN 1 ELSE 0 END) AS misc_disposal,
                
                SUM(CASE WHEN m.mf_active = 'F' THEN 1 ELSE 0 END) AS regular_disposal,
                
                COUNT(*) AS total_disposal

            FROM main m
            INNER JOIN heardt h ON m.diary_no = h.diary_no
            INNER JOIN dispose d ON m.diary_no = d.diary_no
            INNER JOIN master.judge j ON array_position(string_to_array(d.jud_id, ','), CAST(j.jcode AS text)) > 0
            WHERE j.is_retired = 'N'
            AND d.ord_dt BETWEEN '$fromDate' AND '$toDate'
            AND m.c_status = 'D'
            AND h.board_type = 'J'
            AND (m.mf_active = 'M' OR m.mf_active = 'F')";
        }
        if($id == 2)
        {
            /*$sql="select sum(case when m.mf_active<>'F' then 1 else 0 end) as admission_matter,
            sum(case when mf_active<>'F' and main_supp_flag in (0,1,2) then 1 else 0 end) as Total_Complete,
            sum(case when mf_active<>'F' and (main_supp_flag not in (0,1,2) or main_supp_flag is null) then 1 else 0 end) as Total_Incomplete,
            sum(case when m.mf_active='F' then 1 else 0 end) as final_matter,
            sum(case when mf_active='F' and main_supp_flag in (0,1,2) then 1 else 0 end) as Total_Ready,
            sum(case when mf_active='F' and (main_supp_flag not in (0,1,2) or main_supp_flag is null) then 1 else 0 end) as Total_NotReady,
            sum(case when (case_grp='C' or case_grp is null) then 1 else 0 end) civil_pendency,
            sum(case when case_grp='R' then 1 else 0 end) criminal_pendency,
            sum(case when date(fil_dt) < date(DATE_SUB(now(), INTERVAL 1 YEAR)) then 1 else 0 end) more_than_one_year_old,
            sum(case when fil_dt >= date(DATE_SUB(now(), INTERVAL 1 YEAR)) then 1 else 0 end) less_than_one_year_old,
            count(*) as total_pendency from main m left outer join heardt h on m.diary_no=h.diary_no
            where m.c_status='P' and m.fil_no is not null and m.fil_no !=''";*/

            $sql = "SELECT 
                SUM(CASE WHEN m.mf_active <> 'F' THEN 1 ELSE 0 END) AS admission_matter,
                SUM(CASE WHEN m.mf_active <> 'F' AND m.main_supp_flag IN (0, 1, 2) THEN 1 ELSE 0 END) AS total_complete,
                SUM(CASE WHEN m.mf_active <> 'F' AND (m.main_supp_flag NOT IN (0, 1, 2) OR m.main_supp_flag IS NULL) THEN 1 ELSE 0 END) AS total_incomplete,
                SUM(CASE WHEN m.mf_active = 'F' THEN 1 ELSE 0 END) AS final_matter,
                SUM(CASE WHEN m.mf_active = 'F' AND m.main_supp_flag IN (0, 1, 2) THEN 1 ELSE 0 END) AS total_ready,
                SUM(CASE WHEN m.mf_active = 'F' AND (m.main_supp_flag NOT IN (0, 1, 2) OR m.main_supp_flag IS NULL) THEN 1 ELSE 0 END) AS total_notready,
                SUM(CASE WHEN (m.case_grp = 'C' OR m.case_grp IS NULL) THEN 1 ELSE 0 END) AS civil_pendency,
                SUM(CASE WHEN m.case_grp = 'R' THEN 1 ELSE 0 END) AS criminal_pendency,
                SUM(CASE WHEN m.fil_dt::date < CURRENT_DATE - INTERVAL '1 year' THEN 1 ELSE 0 END) AS more_than_one_year_old,
                SUM(CASE WHEN m.fil_dt::date >= CURRENT_DATE - INTERVAL '1 year' THEN 1 ELSE 0 END) AS less_than_one_year_old,
                COUNT(*) AS total_pendency
            FROM main m
            LEFT JOIN heardt h ON m.diary_no = h.diary_no
            WHERE m.c_status = 'P'
            AND m.fil_no IS NOT NULL
            AND m.fil_no != ''";
        }
        if($id == 3)
        {
            /*$sql="SELECT SUBSTR(m.diary_no,1,LENGTH(m.diary_no) - 4) AS diary_no, SUBSTR(m.diary_no, - 4) AS diary_year, reg_no_display,pet_name,res_name,fil_dt
            FROM main m  WHERE DATE(fil_dt) between '".$fromDate."' and '".$toDate."'";*/

            $sql = "SELECT 
                SUBSTRING(CAST(m.diary_no AS TEXT), 1, LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS diary_no,
                SUBSTRING(CAST(m.diary_no AS TEXT), -4) AS diary_year,
                reg_no_display,
                pet_name,
                res_name,
                fil_dt
            FROM main m
            WHERE DATE(fil_dt) BETWEEN '".$fromDate."' AND '".$toDate."'";
        }
        if($id == 4)
        {
            /*$sql="SELECT SUBSTR(m.diary_no,1,LENGTH(m.diary_no) - 4) AS diary_no, SUBSTR(m.diary_no, - 4) AS diary_year, reg_no_display,pet_name,res_name,d.disp_dt,d.ent_dt
            FROM main m 
            INNER JOIN heardt h ON m.diary_no=h.diary_no
            INNER JOIN dispose d ON m.diary_no=d.diary_no
            INNER JOIN master.judge j ON FIND_IN_SET (j.jcode, d.jud_id) = 1
            WHERE j.is_retired='N' AND d.ord_dt
            between '".$fromDate."' and '".$toDate."' AND c_status='D' AND h.board_type ='J'
            and (mf_active='M' OR mf_active='F')
            and (m.fil_no is null or m.fil_no ='')";*/

            $sql = "SELECT 
                SUBSTRING(CAST(m.diary_no AS TEXT), 1, LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS diary_no,
                SUBSTRING(CAST(m.diary_no AS TEXT), -4) AS diary_year,
                reg_no_display,
                pet_name,
                res_name,
                d.disp_dt,
                d.ent_dt
            FROM main m
            INNER JOIN heardt h ON m.diary_no = h.diary_no
            INNER JOIN dispose d ON m.diary_no = d.diary_no
            INNER JOIN master.judge j ON array_position(string_to_array(d.jud_id, ','), j.jcode::text) = 1
            WHERE j.is_retired = 'N'
            AND d.ord_dt BETWEEN '".$fromDate."' AND '".$toDate."'
            AND c_status = 'D'
            AND h.board_type = 'J'
            AND (mf_active = 'M' OR mf_active = 'F')
            AND (m.fil_no IS NULL OR m.fil_no = '')";
        }
        if($id == 5)
        {
            /*$sql=" SELECT SUBSTR(m.diary_no,1,LENGTH(m.diary_no) - 4) AS diary_no, SUBSTR(m.diary_no, - 4) AS diary_year, reg_no_display,pet_name,res_name,d.disp_dt,d.ent_dt
            FROM main m INNER JOIN heardt h
            ON m.diary_no=h.diary_no INNER JOIN dispose d
            ON m.diary_no=d.diary_no INNER JOIN judge j ON FIND_IN_SET (j.jcode, d.jud_id)=1
            WHERE j.is_retired='N' AND d.ord_dt
            between '".$fromDate."' and '".$toDate."' AND c_status='D' AND h.board_type ='J'
            and (mf_active='M' OR mf_active='F')";*/

            $sql = "SELECT 
                SUBSTRING(CAST(m.diary_no AS TEXT), 1, LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS diary_no,
                SUBSTRING(CAST(m.diary_no AS TEXT), -4) AS diary_year,
                reg_no_display,
                pet_name,
                res_name,
                d.disp_dt,
                d.ent_dt
            FROM main m
            INNER JOIN heardt h ON m.diary_no = h.diary_no
            INNER JOIN dispose d ON m.diary_no = d.diary_no
            INNER JOIN master.judge j 
                ON array_position(string_to_array(d.jud_id, ','), j.jcode::text) = 1
            WHERE j.is_retired = 'N'
            AND d.ord_dt BETWEEN '".$fromDate."' AND '".$toDate."'
            AND c_status = 'D'
            AND h.board_type = 'J'
            AND (mf_active = 'M' OR mf_active = 'F')";
        }
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        if ($query->getNumRows() >= 1)
        {
            return  $query->getResultArray();
        }
        else
        {
            return false;
        }
    }
}
