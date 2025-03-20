<?php
namespace App\Models\Judicial;

use CodeIgniter\Model;

class HcorModel extends Model{


public function getDiaryInfo($dairy_no)
{
    $sql_ck = "SELECT 
                    COALESCE(m.dacode, NULL) AS dacode, 
                    COALESCE(u.name, NULL) AS username, 
                    u.empid 
                FROM 
                    main m 
                LEFT JOIN 
                    master.users u ON m.dacode = u.usercode 
                WHERE 
                    m.diary_no = $dairy_no";

    $query = $this->db->query($sql_ck, [$dairy_no]);
    
    return $query->getRowArray();
}

public function getDiaryCount($condition)
{
    // Prepare the SQL query
    $sql_cnt = "SELECT COUNT(DISTINCT a.diary_no) AS Count
                FROM lowerct a
                INNER JOIN master.lc_hc_casetype d ON d.lccasecode = a.lct_casetype
                LEFT JOIN master.state b ON a.l_state = b.id_no AND b.display = 'Y'
                JOIN main e ON e.diary_no = a.diary_no
                LEFT JOIN transfer_to_details t_t ON t_t.lowerct_id = a.lower_court_id AND t_t.display = 'Y'
                JOIN lowercourt_data.indexing_hc_dc as ihd ON ihd.diary_no = a.diary_no
                WHERE lw_display = 'Y' 
                AND ct_code != 4 
                $condition
                AND c_status = 'P' 
                AND (
                    (e.casetype_id != 7 AND e.casetype_id != 8 AND a.is_order_challenged = 'Y')
                    OR (e.casetype_id = 7 OR e.casetype_id = 8)
                ) 
                AND ihd.display = 'Y'";

    // Execute the query
    $query = $this->db->query($sql_cnt);
    
    // Debugging: Inspect the result
    $result = $query->getRowArray();
    
    // Return the count if it exists
    return $result ? (int) $result['count'] : 0; // Return 0 if no result
}





public function getRankedData($condition, $fst, $inc_val)
{
    // Prepare the SQL query
    $sql = "
    WITH ranked_data AS (
        SELECT 
            DISTINCT
            a.diary_no,
            lct_dec_dt,
            l_dist,
            ct_code,
            l_state,
            Name,
            COALESCE(
                (SELECT Name FROM master.state s WHERE s.id_no = a.l_dist AND display = 'Y'),
                (SELECT agency_name FROM master.ref_agency_code c WHERE c.cmis_state_id = a.l_state AND c.id = a.l_dist AND is_deleted = 'f')
            ) AS agency_name,
            lct_casetype,
            lct_caseno,
            lct_caseyear,
            COALESCE(
                (SELECT skey FROM master.casetype ct WHERE ct.display = 'Y' AND ct.casecode = a.lct_casetype),
                (SELECT type_sname FROM master.lc_hc_casetype d WHERE d.lccasecode = a.lct_casetype AND d.display = 'Y')
            ) AS type_sname,
            a.lower_court_id,
            is_order_challenged,
            full_interim_flag,
            judgement_covered_in,
            e.casetype_id,
            conformation,
            ROW_NUMBER() OVER (PARTITION BY a.diary_no ORDER BY a.lower_court_id) AS diary_no_rank,
            SUBSTRING(a.diary_no::TEXT FROM LENGTH(a.diary_no::TEXT) - 3 FOR 4) AS diary_no_suffix,
            SUBSTRING(a.diary_no::TEXT FROM 1 FOR LENGTH(a.diary_no::TEXT) - 4) AS diary_no_prefix
        FROM 
            lowerct a
            INNER JOIN master.lc_hc_casetype d ON d.lccasecode = a.lct_casetype
            LEFT JOIN master.state b ON a.l_state = b.id_no AND b.display = 'Y'
            JOIN main e ON e.diary_no = a.diary_no
            LEFT JOIN transfer_to_details t_t ON t_t.lowerct_id = a.lower_court_id AND t_t.display = 'Y'
            JOIN lowercourt_data.indexing_hc_dc ihd ON ihd.diary_no = a.diary_no
        WHERE 
            lw_display = 'Y'
            $condition
            AND ct_code != 4
            AND c_status = 'P'
            AND (CASE WHEN e.casetype_id != 7 AND e.casetype_id != 8 THEN is_order_challenged = 'Y' END)
            AND ihd.display = 'Y'
        ORDER BY 
            diary_no_suffix,
            diary_no_prefix,
            a.lower_court_id
    )
    SELECT * FROM ranked_data
    WHERE diary_no_rank BETWEEN " . ($fst + 1) . " AND " . $inc_val;

    // Execute the query
    $query = $this->db->query($sql);
    
    // Fetch the result as an associative array
    return $query->getResultArray(); // Adjust based on your needs
}


public function getDefects($diary_no)
{
    // Prepare the SQL query
    $sql = "
    SELECT * 
    FROM lowercourt_data.defects 
    WHERE diary_no = ? 
    AND (
        (if_verified = 'D' AND (defect_removed_on IS NULL OR defect_removed_on IS NULL))
        OR (if_verified = 'V')
    )";

    // Execute the query with parameter binding
    $query = $this->db->query($sql, [$diary_no]);

    // Fetch the result as an associative array
    return $query->getResultArray();
}


public function getlowerctDiaryCount($diary_no)
{
    // Prepare the SQL query
    $sql = "
    SELECT COUNT(*) AS s
    FROM lowerct a
    JOIN main e ON e.diary_no = a.diary_no
    WHERE 
        a.diary_no = ?
        AND lw_display = 'Y'
        AND c_status = 'P'
        AND (
            (e.casetype_id != 7 AND e.casetype_id != 8 AND a.is_order_challenged = 'Y')
            OR (e.casetype_id = 7 OR e.casetype_id = 8)
        )";

    // Execute the query with parameter binding
    $query = $this->db->query($sql, [$diary_no]);

    // Fetch the result as an associative array
    $result = $query->getRow();

    // Return the count if it exists, otherwise return 0
    return $result ? (int) $result->s : 0;
}


public function getDefectVerification($diary_no)
{
    // Prepare the SQL query
    $sql = "
    SELECT 
        TO_CHAR(vh.notified_on, 'DD/MM/YYYY HH24:MI:SS') AS verify_on,
        CONCAT(u.name, ' (', u.empid, ') ', ', ', us.section_name) AS name,
        COALESCE(vh.defects, 'No Remarks') AS remarks,
        if_verified 
    FROM 
        lowercourt_data.defects vh
    INNER JOIN 
        main m ON m.diary_no = vh.diary_no
    LEFT JOIN 
        master.users u ON u.usercode = vh.updated_by AND (u.display = 'Y' OR u.display IS NULL) 
    LEFT JOIN 
        master.usersection us ON us.id = u.section 
    WHERE 
        vh.diary_no = ? 
        AND (vh.defect_removed_on IS NULL)";

    // Execute the query with parameter binding
    $query = $this->db->query($sql, [$diary_no]);

    // Fetch the result as an associative array
    return $query->getResultArray();
}


public function getPendingVerification($diary_no)
{
    // Prepare the SQL query
    $sql = "
    SELECT 
        CONCAT(u.name, ' (', u.empid, ') ', ', ', us.section_name) AS name 
    FROM 
        main m
    LEFT JOIN 
        master.users u ON u.usercode = m.dacode AND (u.display = 'Y' OR u.display IS NULL) 
    LEFT JOIN 
        master.usersection us ON us.id = u.section  
    WHERE 
        m.diary_no = ?";

    // Execute the query with parameter binding
    $query = $this->db->query($sql, [$diary_no]);

    // Fetch the result as an associative array
    return $query->getResultArray();
}




}