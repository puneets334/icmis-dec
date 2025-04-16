<?php

namespace App\Models\ManagementReport;

use CodeIgniter\Model;

class PendencyModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    public function sectionwise_pendency_get_data_bkup(){
        $sql = "SELECT 
                        tentative_section(temp.diary_no) AS section,
                        SUM(
                            CASE 
                                WHEN (
                                    (mf_active != 'F' AND board_type IN ('C'))
                                    OR (mf_active != 'F' AND board_type IN ('R'))
                                    OR (mf_active != 'F' AND main_supp_flag = 3 AND board_type IN ('J'))
                                    OR (
                                        mf_active != 'F' 
                                        AND (
                                            (main_supp_flag IN (1, 2) AND next_dt < CURRENT_DATE)
                                            OR (
                                                diary_no::TEXT != conn_key::TEXT
                                                AND list = 'N'
                                                AND main_supp_flag != 3
                                                AND NOT (
                                                    main_supp_flag IN (1, 2) AND next_dt < CURRENT_DATE
                                                )
                                            )
                                            OR main_supp_flag > 3
                                        )
                                        AND board_type IN ('J')
                                    )
                                )
                                AND (
                                    temp.conn_key::TEXT = temp.diary_no::TEXT 
                                    OR temp.conn_key IS NULL 
                                    OR temp.conn_key = '0' 
                                    OR temp.conn_key = ''
                                ) 
                            THEN 1 ELSE 0 
                            END
                        ) AS misc_total_main,
                        SUM(
                        CASE WHEN (
                        (
                            mf_active != 'F' 
                            AND board_type IN ('C')
                        ) 
                        OR (
                            mf_active != 'F' 
                            AND board_type IN ('R')
                        ) 
                        OR (
                            mf_active != 'F' 
                            AND main_supp_flag = 3 
                            AND board_type IN ('J')
                        ) 
                        OR (
                            mf_active != 'F' 
                            AND (
                            (
                                main_supp_flag in (1, 2) 
                                and next_dt < CURRENT_DATE
                            ) 
                            OR (
                                diary_no::TEXT != conn_key::TEXT 
                                and list = 'N' 
                                and main_supp_flag != 3 
                                and NOT(
                                main_supp_flag in (1, 2) 
                                and next_dt < CURRENT_DATE
                                )
                            ) 
                            OR main_supp_flag > 3
                            ) 
                            AND board_type IN ('J')
                        )
                        ) 
                        AND (
                        temp.conn_key::TEXT != temp.diary_no::TEXT 
                        AND temp.conn_key ~ '^\d+$'
                        AND temp.conn_key::INTEGER > 0
                        ) THEN 1 ELSE 0 END
                    ) misc_total_conn,
                    SUM(
                        CASE WHEN (
                        (
                            mf_active != 'F' 
                            AND board_type IN ('C')
                        ) 
                        OR (
                            mf_active != 'F' 
                            AND board_type IN ('R')
                        ) 
                        OR (
                            mf_active != 'F' 
                            AND main_supp_flag = 3 
                            AND board_type IN ('J')
                        ) 
                        OR (
                            mf_active != 'F' 
                            AND (
                            (
                                main_supp_flag in (1, 2) 
                                and next_dt < CURRENT_DATE
                            ) 
                            OR (
                                diary_no::TEXT != conn_key::TEXT 
                                and list = 'N' 
                                and main_supp_flag != 3 
                                and not(
                                main_supp_flag in (1, 2) 
                                and next_dt < CURRENT_DATE
                                )
                            ) 
                            OR main_supp_flag > 3
                            ) 
                            AND board_type IN ('J')
                        )
                        ) THEN 1 ELSE 0 END
                    ) misc_total,
                    SUM(
                        CASE WHEN mf_active = 'F' 
                        AND (
                        (
                            main_supp_flag in (1, 2) 
                            and next_dt < CURRENT_DATE
                        ) 
                        OR (
                            diary_no::TEXT != conn_key::TEXT 
                            and list = 'N' 
                            and main_supp_flag != 3 
                            and NOT(
                            main_supp_flag in (1, 2) 
                            and next_dt < CURRENT_DATE
                            )
                        ) 
                        OR main_supp_flag >= 3
                        ) 
                        AND board_type IN ('J', 'C', 'R') 
                        AND (
                        temp.conn_key::TEXT = temp.diary_no::TEXT
                        OR temp.conn_key = '0' 
                        OR temp.conn_key = '' 
                        OR temp.conn_key is null
                        ) THEN 1 ELSE 0 END
                    ) final_total_main,
                    SUM(
                        CASE WHEN mf_active = 'F' 
                        AND (
                        (
                            main_supp_flag in (1, 2) 
                            and next_dt < CURRENT_DATE
                        ) 
                        OR (
                            diary_no::TEXT != conn_key::TEXT 
                            and list = 'N' 
                            and main_supp_flag != 3 
                            and NOT(
                            main_supp_flag in (1, 2) 
                            and next_dt < CURRENT_DATE
                            )
                        ) 
                        OR main_supp_flag >= 3
                        ) 
                        AND board_type IN ('J', 'C', 'R') 
                        AND (
                        temp.conn_key::TEXT != temp.diary_no::TEXT 
                        AND temp.conn_key ~ '^\d+$'
                            AND temp.conn_key::INTEGER > 0
                        ) THEN 1 ELSE 0 END
                    ) final_total_conn,
                    SUM(
                        CASE WHEN mf_active = 'F' 
                        AND (
                        (
                            main_supp_flag in (1, 2) 
                            and next_dt < CURRENT_DATE
                        ) 
                        OR (
                            diary_no::TEXT != conn_key::TEXT
                            and list = 'N' 
                            and main_supp_flag != 3 
                            and not(
                            main_supp_flag in (1, 2) 
                            and next_dt < CURRENT_DATE
                            )
                        ) 
                        OR main_supp_flag >= 3
                        ) 
                        AND board_type IN ('J', 'C', 'R') THEN 1 ELSE 0 END
                    ) final_total
                    FROM (
                        SELECT DISTINCT 
                            a.diary_no,
                            a.conn_key,
                            next_dt,
                            mf_active,
                            main_supp_flag,
                            board_type,
                            case_grp,
                            fil_dt,
                            c.list
                        FROM (
                            SELECT 
                                m.diary_no,
                                m.conn_key,
                                h.next_dt,
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
                                m.case_grp
                            FROM main m
                            LEFT JOIN heardt h ON m.diary_no = h.diary_no
                            LEFT JOIN dispose d ON m.diary_no = d.diary_no
                            LEFT JOIN restored r ON m.diary_no = r.diary_no
                            WHERE board_type IN ('J', 'C', 'R')
                            AND (
                                CASE 
                                    WHEN r.disp_dt IS NOT NULL AND r.conn_next_dt IS NOT NULL THEN
                                        CURRENT_DATE NOT BETWEEN r.disp_dt AND r.conn_next_dt
                                    ELSE
                                        r.disp_dt IS NULL OR r.conn_next_dt IS NULL
                                END
                                OR r.fil_no IS NULL
                            )
                            AND (
                                CASE 
                                    WHEN unreg_fil_dt IS NOT NULL AND m.fil_dt IS NOT NULL AND unreg_fil_dt <= m.fil_dt THEN
                                        unreg_fil_dt <= CURRENT_DATE
                                    ELSE
                                        m.fil_dt IS NOT NULL AND m.fil_dt <= CURRENT_DATE
                                END
                            )
                            AND (
                                c_status = 'P'
                                OR (
                                    c_status = 'D'
                                    AND (
                                        CASE 
                                            WHEN d.rj_dt IS NOT NULL THEN
                                                d.rj_dt >= CURRENT_DATE AND d.rj_dt >= DATE '1950-01-01'
                                            WHEN d.disp_dt IS NOT NULL THEN
                                                d.disp_dt >= CURRENT_DATE AND d.disp_dt >= DATE '1950-01-01'
                                            WHEN d.year IS NOT NULL AND d.month IS NOT NULL THEN
                                                MAKE_DATE(d.year, d.month, 1) >= CURRENT_DATE
                                            ELSE
                                                FALSE
                                        END
                                    )
                                    AND (
                                        CASE 
                                            WHEN unreg_fil_dt IS NOT NULL AND m.fil_dt IS NOT NULL AND unreg_fil_dt <= m.fil_dt THEN
                                                unreg_fil_dt <= CURRENT_DATE
                                            ELSE
                                                m.fil_dt IS NOT NULL AND m.fil_dt <= CURRENT_DATE
                                        END
                                    )
                                    AND (
                                        CASE 
                                            WHEN r.disp_dt IS NOT NULL AND r.conn_next_dt IS NOT NULL THEN
                                                CURRENT_DATE NOT BETWEEN r.disp_dt AND r.conn_next_dt
                                            ELSE
                                                r.disp_dt IS NULL OR r.conn_next_dt IS NULL
                                        END
                                    )
                                )
                            )
                            AND (
                                SUBSTRING(m.fil_no, 1, 2) NOT IN ('39') 
                                OR m.fil_no = '' 
                                OR m.fil_no IS NULL
                            )
                            GROUP BY 
                                m.diary_no,
                                m.conn_key,
                                h.next_dt,
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
                                m.case_grp
                            --LIMIT 10
                        ) a
                        LEFT JOIN conct c ON c.diary_no = a.diary_no
                    ) temp
                    GROUP BY tentative_section(diary_no)
                    LIMIT 30";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

    public function sectionwise_pendency_get_data(){
        $sql = "WITH base_data AS (
                    SELECT
                        tentative_section(temp.diary_no) AS section,
                        SUM(
                            CASE
                                WHEN (
                                    (mf_active != 'F' AND board_type IN ('C')) OR
                                    (mf_active != 'F' AND board_type IN ('R')) OR
                                    (mf_active != 'F' AND main_supp_flag = 3 AND board_type IN ('J')) OR
                                    (
                                        mf_active != 'F' AND (
                                            (main_supp_flag IN (1, 2) AND next_dt < CURRENT_DATE) OR
                                            (
                                                diary_no::TEXT != conn_key::TEXT AND
                                                list = 'N' AND
                                                main_supp_flag != 3 AND
                                                NOT(main_supp_flag IN (1, 2) AND next_dt < CURRENT_DATE)
                                            ) OR
                                            main_supp_flag > 3
                                        ) AND board_type IN ('J')
                                    )
                                )
                                AND (
                                    temp.conn_key::TEXT = temp.diary_no::TEXT OR
                                    temp.conn_key IS NULL OR
                                    temp.conn_key = '0' OR
                                    temp.conn_key = ''
                                )
                            THEN 1 ELSE 0
                            END
                        ) AS misc_total_main,
                        SUM(
                            CASE
                                WHEN (
                                    (mf_active != 'F' AND board_type IN ('C')) OR
                                    (mf_active != 'F' AND board_type IN ('R')) OR
                                    (mf_active != 'F' AND main_supp_flag = 3 AND board_type IN ('J')) OR
                                    (
                                        mf_active != 'F' AND (
                                            (main_supp_flag IN (1, 2) AND next_dt < CURRENT_DATE) OR
                                            (
                                                diary_no::TEXT != conn_key::TEXT AND
                                                list = 'N' AND
                                                main_supp_flag != 3 AND
                                                NOT(main_supp_flag IN (1, 2) AND next_dt < CURRENT_DATE)
                                            ) OR
                                            main_supp_flag > 3
                                        ) AND board_type IN ('J')
                                    )
                                )
                                AND (
                                    temp.conn_key::TEXT != temp.diary_no::TEXT AND
                                    temp.conn_key ~ '^\d+$' AND
                                    temp.conn_key::INTEGER > 0
                                )
                            THEN 1 ELSE 0
                            END
                        ) AS misc_total_conn,
                        SUM(
                            CASE
                                WHEN (
                                    (mf_active != 'F' AND board_type IN ('C')) OR
                                    (mf_active != 'F' AND board_type IN ('R')) OR
                                    (mf_active != 'F' AND main_supp_flag = 3 AND board_type IN ('J')) OR
                                    (
                                        mf_active != 'F' AND (
                                            (main_supp_flag IN (1, 2) AND next_dt < CURRENT_DATE) OR
                                            (
                                                diary_no::TEXT != conn_key::TEXT AND
                                                list = 'N' AND
                                                main_supp_flag != 3 AND
                                                NOT(main_supp_flag IN (1, 2) AND next_dt < CURRENT_DATE)
                                            ) OR
                                            main_supp_flag > 3
                                        ) AND board_type IN ('J')
                                    )
                                )
                            THEN 1 ELSE 0
                            END
                        ) AS misc_total,
                        SUM(
                            CASE
                                WHEN mf_active = 'F' AND (
                                    (main_supp_flag IN (1, 2) AND next_dt < CURRENT_DATE) OR
                                    (
                                        diary_no::TEXT != conn_key::TEXT AND
                                        list = 'N' AND
                                        main_supp_flag != 3 AND
                                        NOT(main_supp_flag IN (1, 2) AND next_dt < CURRENT_DATE)
                                    ) OR
                                    main_supp_flag >= 3
                                )
                                AND board_type IN ('J', 'C', 'R')
                                AND (
                                    temp.conn_key::TEXT = temp.diary_no::TEXT OR
                                    temp.conn_key = '0' OR
                                    temp.conn_key = '' OR
                                    temp.conn_key IS NULL
                                )
                            THEN 1 ELSE 0
                            END
                        ) AS final_total_main,
                        SUM(
                            CASE
                                WHEN mf_active = 'F' AND (
                                    (main_supp_flag IN (1, 2) AND next_dt < CURRENT_DATE) OR
                                    (
                                        diary_no::TEXT != conn_key::TEXT AND
                                        list = 'N' AND
                                        main_supp_flag != 3 AND
                                        NOT(main_supp_flag IN (1, 2) AND next_dt < CURRENT_DATE)
                                    ) OR
                                    main_supp_flag >= 3
                                )
                                AND board_type IN ('J', 'C', 'R')
                                AND (
                                    temp.conn_key::TEXT != temp.diary_no::TEXT AND
                                    temp.conn_key ~ '^\d+$' AND
                                    temp.conn_key::INTEGER > 0
                                )
                            THEN 1 ELSE 0
                            END
                        ) AS final_total_conn,
                        SUM(
                            CASE
                                WHEN mf_active = 'F' AND (
                                    (main_supp_flag IN (1, 2) AND next_dt < CURRENT_DATE) OR
                                    (
                                        diary_no::TEXT != conn_key::TEXT AND
                                        list = 'N' AND
                                        main_supp_flag != 3 AND
                                        NOT(main_supp_flag IN (1, 2) AND next_dt < CURRENT_DATE)
                                    ) OR
                                    main_supp_flag >= 3
                                )
                                AND board_type IN ('J', 'C', 'R')
                            THEN 1 ELSE 0
                            END
                        ) AS final_total
                    FROM (
                        SELECT DISTINCT
                            a.diary_no,
                            a.conn_key,
                            next_dt,
                            mf_active,
                            main_supp_flag,
                            board_type,
                            case_grp,
                            fil_dt,
                            c.list
                        FROM (
                            SELECT
                                m.diary_no,
                                m.conn_key,
                                h.next_dt,
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
                                m.case_grp
                            FROM main m
                            LEFT JOIN heardt h ON m.diary_no = h.diary_no
                            LEFT JOIN dispose d ON m.diary_no = d.diary_no
                            LEFT JOIN restored r ON m.diary_no = r.diary_no
                            WHERE board_type IN ('J', 'C', 'R')
                                AND (
                                    CASE
                                        WHEN r.disp_dt IS NOT NULL AND r.conn_next_dt IS NOT NULL THEN
                                            CURRENT_DATE NOT BETWEEN r.disp_dt AND r.conn_next_dt
                                        ELSE
                                            r.disp_dt IS NULL OR r.conn_next_dt IS NULL
                                    END
                                    OR r.fil_no IS NULL
                                )
                                AND (
                                    CASE
                                        WHEN unreg_fil_dt IS NOT NULL AND m.fil_dt IS NOT NULL AND unreg_fil_dt <= m.fil_dt THEN
                                            unreg_fil_dt <= CURRENT_DATE
                                        ELSE
                                            m.fil_dt IS NOT NULL AND m.fil_dt <= CURRENT_DATE
                                    END
                                )
                                AND (
                                    c_status = 'P' OR (
                                        c_status = 'D' AND (
                                            CASE
                                                WHEN d.rj_dt IS NOT NULL THEN d.rj_dt >= CURRENT_DATE AND d.rj_dt >= DATE '1950-01-01'
                                                WHEN d.disp_dt IS NOT NULL THEN d.disp_dt >= CURRENT_DATE AND d.disp_dt >= DATE '1950-01-01'
                                                WHEN d.year IS NOT NULL AND d.month IS NOT NULL THEN MAKE_DATE(d.year, d.month, 1) >= CURRENT_DATE
                                                ELSE FALSE
                                            END
                                        )
                                        AND (
                                            CASE
                                                WHEN unreg_fil_dt IS NOT NULL AND m.fil_dt IS NOT NULL AND unreg_fil_dt <= m.fil_dt THEN
                                                    unreg_fil_dt <= CURRENT_DATE
                                                ELSE
                                                    m.fil_dt IS NOT NULL AND m.fil_dt <= CURRENT_DATE
                                            END
                                        )
                                        AND (
                                            CASE
                                                WHEN r.disp_dt IS NOT NULL AND r.conn_next_dt IS NOT NULL THEN
                                                    CURRENT_DATE NOT BETWEEN r.disp_dt AND r.conn_next_dt
                                                ELSE
                                                    r.disp_dt IS NULL OR r.conn_next_dt IS NULL
                                            END
                                        )
                                    )
                                )
                                AND (
                                    SUBSTRING(m.fil_no, 1, 2) NOT IN ('39') OR m.fil_no = '' OR m.fil_no IS NULL
                                )
                            GROUP BY
                                m.diary_no,
                                m.conn_key,
                                h.next_dt,
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
                                m.case_grp
                        ) a
                        LEFT JOIN conct c ON c.diary_no = a.diary_no
                    ) temp
                    GROUP BY tentative_section(temp.diary_no)
                ),
                numbered_data AS (
                    SELECT *,
                        ROW_NUMBER() OVER () AS rn
                    FROM base_data
                )
                SELECT *
                FROM numbered_data
                ORDER BY (section IS NOT NULL AND section <> '') ASC, rn
                LIMIT 30
                ";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
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
    

    // Shubham Work END
	
	
		
	public function da_rog_cases($category, $dacode){
// Initialize the Query Builder
		$builder = $this->db->table('main as m');

		$builder->distinct()->select('m.active_fil_no, m.diary_no, tentative_section(m.diary_no) AS Section, m.reg_no_display, m.pet_name, m.res_name, state.name, '
			. 'CASE WHEN (m.mf_active = \'M\' OR (m.mf_active = \'F\' AND crh.head::TEXT = \'24\')) THEN m.tentative_cl_dt END AS tentative, ' // Cast crh.head to TEXT if necessary
			. 'm.next_dt AS next, '
			. 'CASE WHEN h.board_type = \'J\' THEN \'Court\' '
			. 'WHEN h.board_type = \'C\' THEN \'Chamber\' '
			. 'WHEN h.board_type = \'R\' THEN \'Registrar\' '
			. 'END AS board_type, '
			. 'STRING_AGG(DISTINCT crh.head || \' \' || crm.head_content, \',\') AS Rmrk_Disp');

		// Join the necessary tables
		$builder->join('master.casetype c', 'c.casecode = COALESCE(m.active_casetype_id, m.casetype_id)', 'inner')
			->join('heardt h', 'm.diary_no = h.diary_no', 'left')
			->join('master.state', 'm.ref_agency_state_id = state.id_no', 'left')
			->join('master.subheading s', 'h.subhead = s.stagecode', 'left')
			->join('rgo_default rd', 'm.diary_no = rd.fil_no', 'left')
			->join('case_remarks_multiple crm', 'crm.diary_no = m.diary_no AND crm.cl_date = (SELECT MAX(cl_date) FROM case_remarks_multiple WHERE diary_no = m.diary_no)', 'left')
			->join('master.case_remarks_head crh', 'crh.sno = crm.r_head AND (crh.display = \'Y\' OR crh.display IS NULL)', 'left'); // Corrected the string literals

// Define the WHERE conditions
$builder->where('c.c_status', 'P')
    ->where('rd.dacode', $dacode);

// Add the dynamic condition based on the $category value
switch ($category) {
    case 't':
        // Empty condition
        break;

    case 'r':
        $builder->where("(CASE WHEN m.tentative_cl_dt != '0000-00-00' THEN CURRENT_DATE - h.tentative_cl_dt < 2 ELSE TRUE END)")
                ->where("!(CASE WHEN h.mainhead = 'M' THEN s.listtype = 'M' AND s.listtype IS NOT NULL AND s.display = 'Y' "
                        . "ELSE CASE WHEN h.mainhead = 'S' THEN s.listtype = 'S' AND s.listtype IS NOT NULL AND s.display = 'Y' END END) "
                        . "AND (m.main_supp_flag = 0 AND h.clno = 0 AND h.brd_slno = 0 AND (h.judges = '' OR h.judges = 0) "
                        . "AND h.roster_id = 0) OR (m.next_dt != '0000-00-00' AND m.next_dt >= CURRENT_DATE))")
                ->where("m.lastorder NOT LIKE '%Not Reached%' AND m.lastorder NOT LIKE '%Case Not Receive%' AND m.lastorder NOT LIKE '%Heard & Reserved%' "
                        . "OR m.lastorder IS NULL");
        $builder->where("(m.head_code != 5 OR m.head_code IS NULL)");
        $builder->whereNotIn('m.diary_no', function($query) {
            $query->select('diary_no')->from('heardt')->where('main_supp_flag', 3)
                ->whereIn('usercode', [559, 146, 744, 747, 469, 1485, 742, 1486, 935, 757, 49, 762]);
            $query->union(function($query) {
                $query->select('fil_no AS diary_no')->from('rgo_default')->where('remove_def != "Y"');
            });
        });
        break;

    case 'y':
        $builder->where("((h.main_supp_flag = 3 AND h.usercode IN(559, 146, 744, 747, 469, 1485, 742, 1486, 935, 757, 49, 762)) "
            . "OR rd.remove_def != 'Y' OR m.lastorder LIKE '%Heard & Reserved%')");
        break;

    case 'd':
        // Complex condition for 'd', including multiple unions (This is a simplified approach and might need adaptation)
        break;
}

// Group by and ordering
$builder->groupBy('m.diary_no')
    ->orderBy('Section')
    ->orderBy('m.active_reg_year')
    ->orderBy('CAST(SUBSTRING(m.active_fil_no, 1, 2) AS INTEGER)')
    ->orderBy('CAST(SUBSTRING(m.active_fil_no, 4, 6) AS INTEGER)');

// Execute the query and get results
$query = $builder->get();
return $query->getResultArray();
	}
	
	
	public function da_details($dacode){
		$sql="SELECT name,type_name,section_name,empid FROM users user left join usersection us on user.section=us.id
				left join usertype ut on ut.id=user.usertype where usercode=".$dacode;
        $query = $this->db->query($sql);
        return $query->getResultArray();
	}
		
}
