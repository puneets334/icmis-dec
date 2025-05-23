<?php
function get_diary_case_type($ct,$cn,$cy)
{
    $db = \Config\Database::connect();
    if($ct != '')
    {
        $sql = "SELECT 
                substr(diary_no, 1, length(diary_no) - 4) as dn, 
                substr(diary_no, -4) as dy 
            FROM main 
            WHERE 
            (
                split_part(fil_no, '-', 1) = ? 
                AND ? BETWEEN CAST(split_part(split_part(fil_no, '-', 2), '-', 2) AS INTEGER) 
                        AND CAST(split_part(fil_no, '-', 3) AS INTEGER)
                AND 
                CASE 
                    WHEN reg_year_mh = 0 OR fil_dt > DATE '2017-05-10' 
                    THEN EXTRACT(YEAR FROM fil_dt)::INT = ?
                    ELSE reg_year_mh = ?
                END
            )
            OR 
            (
                split_part(fil_no_fh, '-', 1) = ? 
                AND ? BETWEEN CAST(split_part(split_part(fil_no_fh, '-', 2), '-', 2) AS INTEGER) 
                        AND CAST(split_part(fil_no_fh, '-', 3) AS INTEGER)
                AND 
                CASE 
                    WHEN reg_year_fh = 0 
                    THEN EXTRACT(YEAR FROM fil_dt_fh)::INT = ?
                    ELSE reg_year_fh = ?
                END
            )";

        $query = $db->query($sql, [$ct, $cn, $cy, $cy, $ct, $cn, $cy, $cy]);

        $row = $query->getRowArray();

        if ($row) {
            return $row['dn'] . $row['dy'];
        }
        else 
        {
            $sql = "SELECT 
                    SUBSTR(h.diary_no, 1, LENGTH(h.diary_no) - 4) AS dn, 
                    SUBSTR(h.diary_no, -4) AS dy,
                    CASE 
                        WHEN h.new_registration_number != '' 
                        THEN split_part(h.new_registration_number, '-', 1) 
                        ELSE '' 
                    END AS ct1,
                    CASE 
                        WHEN h.new_registration_number != '' 
                        THEN split_part(split_part(h.new_registration_number, '-', 2), '-', 2)
                        ELSE '' 
                    END AS crf1,
                    CASE 
                        WHEN h.new_registration_number != '' 
                        THEN split_part(h.new_registration_number, '-', 3) 
                        ELSE '' 
                    END AS crl1
                FROM main_casetype_history h
                WHERE 
                    (
                        split_part(h.new_registration_number, '-', 1) = ?
                        AND ? BETWEEN 
                            CAST(split_part(split_part(h.new_registration_number, '-', 2), '-', 2) AS INTEGER)
                            AND CAST(split_part(h.new_registration_number, '-', 3) AS INTEGER)
                        AND h.new_registration_year = ?
                    )
                    OR 
                    (
                        split_part(h.old_registration_number, '-', 1) = ?
                        AND ? BETWEEN 
                            CAST(split_part(split_part(h.old_registration_number, '-', 2), '-', 2) AS INTEGER)
                            AND CAST(split_part(h.old_registration_number, '-', 3) AS INTEGER)
                        AND h.old_registration_year = ?
                    )
                    AND h.is_deleted = 'f'";

            $query = $db->query($sql, [$ct, $cn, $cy, $ct, $cn, $cy]);

            $row = $query->getRowArray();

            if ($row) {
                return $row['dn'] . $row['dy'];
            }
        }
    }
    return;
}
?>

