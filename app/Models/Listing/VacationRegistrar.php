<?php

 namespace App\Models\Listing;

use CodeIgniter\Model;

class VacationRegistrar extends Model
{
    
    public function insertNotReadyCases($listDt, $userCode, $judgeCodes, $noc)
    {
        $db = \Config\Database::connect();
        $affectedRows = 0;
        $table = 'vacation_registrar_not_ready_cl'; // Define table name here

        foreach ($judgeCodes as $jcode) {
            $sql = "INSERT INTO $table (diary_no, list_dt, user_code, ent_dt, reg_jcode)
                    SELECT m.diary_no, ?, ?, NOW(), ?
                    FROM main m
                    INNER JOIN vacation_registrar_pool vrp ON vrp.diary_no = m.diary_no
                    LEFT JOIN heardt h ON h.diary_no = m.diary_no
                    LEFT JOIN master.users u ON u.usercode = m.dacode
                    LEFT JOIN $table v ON v.diary_no = m.diary_no
                        AND v.display = 'Y'
                        AND EXTRACT(YEAR FROM v.ent_dt) = EXTRACT(YEAR FROM CURRENT_DATE)
                        AND v.list_dt >= '2019-05-21'
                    WHERE vrp.display = 'Y'
                      AND v.diary_no IS NULL
                      AND m.c_status = 'P'
                      AND EXTRACT(YEAR FROM vrp.ent_dt) = EXTRACT(YEAR FROM CURRENT_DATE)
                    GROUP BY m.diary_no
                    ORDER BY RANDOM()
                    LIMIT ?";

            $db->query($sql, [$listDt, $userCode, $jcode, $noc]);
            $affectedRows += $db->affectedRows();
        }

        return $affectedRows;
    }
}
