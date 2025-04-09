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
    

    // Shubham Work END
}
