<?php

namespace App\Models\Record_room;

use CodeIgniter\Model;

class Advocate extends Model
{
    protected $table = 'advocate';
    // protected $primaryKey = 'diary_no';

    protected $useAutoIncrement = false; 

    protected $allowedFields = [
        'diary_no',
        'adv_type',
        'pet_res',
        'pet_res_no',
        'advocate_id',
        'adv',
        'usercode',
        'ent_dt',
        'display',
        'stateadv',
        'old_adv',
        'ent_by_caveat_advocate',
        'remark',
        'aor_state',
        'pet_res_show_no',
        'is_ac',
        'writ_adv_remarks',
        'ac_direction_given_by',
        'ac_remarks',
        'inperson_mobile',
        'inperson_email'
    ];


    public function getAdvtDetails($bar_id)
    {
        $db = \Config\Database::connect();
        
        $bar_id = $db->escape($bar_id);

        $sql = "SELECT cc.* 
        FROM (
            SELECT DISTINCT
                concat(
                    substring(cast(a.diary_no AS text) FROM 1 FOR length(cast(a.diary_no AS text)) - 4),
                    '/',
                    substring(cast(a.diary_no AS text) FROM length(cast(a.diary_no AS text)) - 3 FOR 4)
                ) AS Diary_no,
                concat(
                    reg_no_display,
                    ' @ ',
                    concat(
                        substring(cast(a.diary_no AS text) FROM 1 FOR length(cast(a.diary_no AS text)) - 4),
                        '/',
                        substring(cast(a.diary_no AS text) FROM length(cast(a.diary_no AS text)) - 3 FOR 4)
                    )
                ) AS No,
                substring(cast(a.diary_no AS text) FROM length(cast(a.diary_no AS text)) - 3 FOR 4) AS dyear,
                concat(pet_name, ' VS ', res_name) AS Causetitle,
                CASE
                    WHEN h.conn_key = 0 THEN 'MAIN'
                    ELSE CASE
                        WHEN h.diary_no = h.conn_key THEN 'Main'
                        ELSE 'Connected'
                    END
                END AS Main_Connected,
                CASE
                    WHEN c_status = 'P' THEN 'Pending'
                    ELSE 'Disposed'
                END AS status
            FROM
                advocate a
                JOIN main b ON a.diary_no = b.diary_no
                LEFT JOIN master.users c ON c.usercode = b.dacode AND c.display = 'Y'
                LEFT JOIN heardt h ON h.diary_no = b.diary_no
                LEFT JOIN master.usersection d ON d.id = c.section AND d.display = 'Y'
                LEFT JOIN master.casetype e ON e.casecode = CAST(substring(cast(b.fil_no AS text) FROM 1 FOR 2) AS INTEGER) AND e.display = 'Y'
                LEFT JOIN (
                    SELECT
                        us.section_name,
                        us.id AS sec_id,
                        a.diary_no,
                        advocate_id
                    FROM (
                        SELECT
                            a.diary_no,
                            COALESCE(NULLIF(active_casetype_id, 0), casetype_id) AS casetype_id,
                            COALESCE(NULLIF(active_fil_no, ''), fil_no) AS fil_no,
                            CASE
                                WHEN COALESCE(NULLIF(active_reg_year, 0),
                                    CASE
                                        WHEN EXTRACT(YEAR FROM active_fil_dt) = 0 THEN EXTRACT(YEAR FROM fil_dt)
                                        ELSE EXTRACT(YEAR FROM active_fil_dt)
                                    END) = 0 THEN EXTRACT(YEAR FROM diary_no_rec_date)
                                ELSE COALESCE(NULLIF(active_reg_year,
                                    CASE
                                        WHEN EXTRACT(YEAR FROM active_fil_dt) = 0 THEN EXTRACT(YEAR FROM fil_dt)
                                        ELSE EXTRACT(YEAR FROM active_fil_dt)
                                    END), active_reg_year)
                            END AS reg_year,
                            ref_agency_state_id,
                            ref_agency_code_id,
                            diary_no_rec_date,
                            pet_name,
                            res_name,
                            advocate_id
                        FROM
                            main a
                            JOIN advocate b ON a.diary_no = b.diary_no
                            LEFT JOIN heardt h ON h.diary_no = a.diary_no
                            LEFT JOIN mul_category mc ON a.diary_no = mc.diary_no AND mc.display = 'Y'
                        WHERE
                            b.advocate_id = $bar_id
                            AND b.display = 'Y'
                    ) a
                    LEFT JOIN master.da_case_distribution b ON b.case_type = a.casetype_id
                        AND ref_agency_state_id = state
                        AND reg_year BETWEEN b.case_f_yr AND b.case_t_yr
                    LEFT JOIN master.users u ON b.dacode = u.usercode AND u.display = 'Y'
                    LEFT JOIN heardt h ON h.diary_no = a.diary_no
                    LEFT JOIN master.usersection us ON u.section = us.id AND us.display = 'Y'
                    WHERE b.display = 'Y'
                    GROUP BY a.diary_no, us.section_name, us.id, advocate_id
                    UNION
                    SELECT
                        us.section_name,
                        us.id AS sec_id,
                        a.diary_no,
                        advocate_id
                    FROM (
                        SELECT
                            a.diary_no,
                            COALESCE(NULLIF(active_casetype_id, 0), casetype_id) AS casetype_id,
                            COALESCE(NULLIF(active_fil_no, ''), fil_no) AS fil_no,
                            CASE
                                WHEN COALESCE(NULLIF(active_reg_year, 0),
                                    CASE
                                        WHEN EXTRACT(YEAR FROM active_fil_dt) = 0 THEN EXTRACT(YEAR FROM fil_dt)
                                        ELSE EXTRACT(YEAR FROM active_fil_dt)
                                    END) = 0 THEN EXTRACT(YEAR FROM diary_no_rec_date)
                                ELSE COALESCE(NULLIF(active_reg_year,
                                    CASE
                                        WHEN EXTRACT(YEAR FROM active_fil_dt) = 0 THEN EXTRACT(YEAR FROM fil_dt)
                                        ELSE EXTRACT(YEAR FROM active_fil_dt)
                                    END), active_reg_year)
                            END AS reg_year,
                            ref_agency_state_id,
                            ref_agency_code_id,
                            diary_no_rec_date,
                            pet_name,
                            res_name,
                            advocate_id
                        FROM
                            main a
                            JOIN advocate b ON a.diary_no = b.diary_no
                        WHERE
                            b.advocate_id = $bar_id
                            AND b.display = 'Y'
                    ) a
                    LEFT JOIN master.da_case_distribution_tri b ON b.case_type = a.casetype_id
                        AND ref_agency_state_id = state
                        AND reg_year BETWEEN b.case_f_yr AND b.case_t_yr
                    LEFT JOIN heardt h ON h.diary_no = a.diary_no
                    LEFT JOIN master.users u ON b.dacode = u.usercode AND u.display = 'Y'
                    LEFT JOIN master.usersection us ON u.section = us.id AND us.display = 'Y'
                    WHERE b.display = 'Y'
                    GROUP BY a.diary_no, us.section_name, us.id, advocate_id
                    UNION
                    SELECT
                        us.section_name,
                        us.id AS sec_id,
                        a.diary_no,
                        advocate_id
                    FROM (
                        SELECT
                            a.diary_no,
                            COALESCE(NULLIF(active_casetype_id, 0), casetype_id) AS casetype_id,
                            COALESCE(NULLIF(active_fil_no, ''), fil_no) AS fil_no,
                            CASE
                                WHEN COALESCE(NULLIF(active_reg_year, 0),
                                    CASE
                                        WHEN EXTRACT(YEAR FROM active_fil_dt) = 0 THEN EXTRACT(YEAR FROM fil_dt)
                                        ELSE EXTRACT(YEAR FROM active_fil_dt)
                                    END) = 0 THEN EXTRACT(YEAR FROM diary_no_rec_date)
                                ELSE COALESCE(NULLIF(active_reg_year,
                                    CASE
                                        WHEN EXTRACT(YEAR FROM active_fil_dt) = 0 THEN EXTRACT(YEAR FROM fil_dt)
                                        ELSE EXTRACT(YEAR FROM active_fil_dt)
                                    END), active_reg_year)
                            END AS reg_year,
                            ref_agency_state_id,
                            ref_agency_code_id,
                            diary_no_rec_date,
                            pet_name,
                            res_name,
                            advocate_id
                        FROM
                            main a
                            JOIN advocate b ON a.diary_no = b.diary_no
                            LEFT JOIN mul_category mc ON a.diary_no = mc.diary_no AND mc.display = 'Y'
                        WHERE
                            b.advocate_id = $bar_id
                            AND b.display = 'Y'
                    ) a
                    LEFT JOIN master.da_case_distribution_tri b ON b.case_type = a.casetype_id
                        AND reg_year BETWEEN b.case_f_yr AND b.case_t_yr
                    LEFT JOIN heardt h ON h.diary_no = a.diary_no
                    LEFT JOIN master.users u ON b.dacode = u.usercode AND u.display = 'Y'
                    LEFT JOIN master.usersection us ON u.section = us.id AND us.display = 'Y'
                    WHERE b.display = 'Y'
                    GROUP BY a.diary_no, us.section_name, us.id, advocate_id
                ) x ON x.diary_no = a.diary_no AND x.advocate_id = a.advocate_id
            WHERE
                a.advocate_id = $bar_id
                AND a.display = 'Y'
            ORDER BY dyear, Diary_no
        ) cc;
        ";

        $query = $db->query($sql);
        return $query->getResultArray();
    }



    

   
}