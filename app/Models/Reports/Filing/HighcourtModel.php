<?php

namespace App\Models\Reports\Filing;

use CodeIgniter\Model;

//use CodeIgniter\Database\BaseBuilder;


class HighcourtModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }



    public function getCaseData($filters)
    {
        // Extract values safely from filters
        $ddl_court = $filters['ddl_court'] ?? '';
        $txt_order_date = $filters['txt_order_date'] ?? '';
        $ddl_bench = $filters['ddl_bench'] ?? '';
        $ddl_st_agncy = $filters['ddl_st_agncy'] ?? '';
        $ddl_ref_case_type = $filters['ddl_ref_case_type'] ?? '';
        $txt_ref_caseno = $filters['txt_ref_caseno'] ?? '';
        $ddl_ref_caseyr = $filters['ddl_ref_caseyr'] ?? '';
        $inc_val = $filters['inc_val'] ?? 10;
        $fst = $filters['fst'] ?? 0;

        $sql = "SELECT 
                name,
                CASE 
                    WHEN b.ct_code = 3 THEN (
                        SELECT s.name
                        FROM master.state s
                        WHERE s.id_no = b.l_dist
                            AND s.display = 'Y'
                        LIMIT 1
                    )
                    ELSE (
                        SELECT c.agency_name
                        FROM master.ref_agency_code c
                        WHERE c.cmis_state_id = b.l_state
                            AND c.id = b.l_dist
                            AND c.is_deleted = 'f'
                        LIMIT 1
                    )
                END AS agency_name,

                CASE 
                    WHEN b.ct_code = 4 THEN (
                        SELECT ct.skey
                        FROM master.casetype ct
                        WHERE ct.display = 'Y'
                            AND ct.casecode = b.lct_casetype
                        LIMIT 1
                    )
                    ELSE (
                        SELECT d.type_sname
                        FROM master.lc_hc_casetype d
                        WHERE d.lccasecode = b.lct_casetype
                            AND d.display = 'Y'
                        LIMIT 1
                    )
                END AS type_sname,

                short_description,
                court_name,
                d.pet_name,
                d.res_name,
                b.lct_dec_dt,
                b.l_dist,
                b.l_state,
                b.lct_casetype,
                b.lct_caseno,
                b.lct_caseyear,
                b.diary_no,
                b.ct_code
            FROM 
                lowerct b
            LEFT JOIN master.state c ON b.l_state = CAST(c.id_no as BIGINT)
                AND c.display = 'Y'
            LEFT JOIN main d ON d.diary_no = CAST(b.diary_no as BIGINT)
            LEFT JOIN master.casetype e ON e.casecode = CAST(
			       CASE 
			           WHEN d.fil_no IS NOT NULL AND d.fil_no ~ '^[0-9]+$' 
			           THEN SUBSTRING(d.fil_no FROM 1 FOR 2) 
			           ELSE '0'   
			       END 
			   AS INTEGER)
                AND e.display = 'Y'
            LEFT JOIN master.m_from_court f ON f.id = CAST(b.ct_code as BIGINT)
                AND f.display = 'Y'
            WHERE 
                $ddl_court
                $txt_order_date
                $ddl_bench
                $ddl_st_agncy
                $ddl_ref_case_type
                $txt_ref_caseno
                $ddl_ref_caseyr
                AND b.lct_dec_dt IS NOT NULL
                AND b.lw_display = 'Y'
            ORDER BY 
                b.diary_no
            LIMIT $inc_val OFFSET $fst ";
 
        $query = $this->db->query($sql);
        
       
        return $query->getResultArray();
    }


    public function getCaveatDetails($ddl_court, $txt_order_date, $ddl_bench, $ddl_st_agncy, $ddl_ref_case_type, $txt_ref_caseno, $ddl_ref_caseyr, $fst, $inc_val)
{
    $query = $this->db->table('caveat_lowerct b')
        ->select('
            name,
            CASE
                WHEN b.ct_code = 3 THEN (
                    SELECT s.name
                    FROM master.state s
                    WHERE s.id_no = b.l_dist
                    AND s.display = \'Y\'
                )
                ELSE (
                    SELECT c.agency_name
                    FROM master.ref_agency_code c
                    WHERE c.cmis_state_id = b.l_state
                    AND c.id = b.l_dist
                    AND c.is_deleted = \'f\'
                )
            END AS agency_name,
            CASE
                WHEN b.ct_code = 4 THEN (
                    SELECT ct.skey
                    FROM master.casetype ct
                    WHERE ct.display = \'Y\'
                    AND ct.casecode = b.lct_casetype
                )
                ELSE (
                    SELECT d.type_sname
                    FROM master.lc_hc_casetype d
                    WHERE d.lccasecode = b.lct_casetype
                    AND d.display = \'Y\'
                )
            END AS type_sname,
            short_description,
            court_name,
            d.pet_name,
            d.res_name,
            b.lct_dec_dt,
            b.l_dist,
            b.l_state,
            b.lct_casetype,
            b.lct_caseno,
            b.lct_caseyear,
            b.caveat_no,
            b.ct_code,
            date(d.diary_no_rec_date) AS diary_no_rec_date
        ')
        ->join('master.state c', 'b.l_state = c.id_no AND c.display = \'Y\'', 'left')
        ->join('caveat d', 'd.caveat_no = b.caveat_no', 'left')
        ->join('master.casetype e', 'e.casecode = CAST(SUBSTRING(d.fil_no FROM 1 FOR 2) AS INTEGER) AND e.display = \'Y\'', 'left')
        ->join('master.m_from_court f', 'f.id = b.ct_code AND f.display = \'Y\'', 'left')
        ->where('b.lct_dec_dt IS NOT NULL')
        ->where('b.lw_display', 'Y');
        
    if(!empty($ddl_court))
        $query->where($ddl_court);
    if(!empty($txt_order_date))
        $query->where($txt_order_date);
    if(!empty($ddl_bench))
        $query->where($ddl_bench);
    if(!empty($ddl_st_agncy))
        $query->where($ddl_st_agncy);
    if(!empty($ddl_ref_case_type))
        $query->where($ddl_ref_case_type);
    if(!empty($txt_ref_caseno))
        $query->where($txt_ref_caseno);
    if(!empty($ddl_ref_caseyr))
        $query->where($ddl_ref_caseyr);
    
   $result =  $query->orderBy('caveat_no')
        ->limit($inc_val, $fst)
        ->get();
    
   // pr($this->db->getLastQuery());  // Log the last query for debugging
    return $result->getResultArray();
}


public function getCaveatAdvocate($caveat_no)
{
    $query = $this->db->table('caveat_advocate a')
        ->select('aor_code, name')
        ->join('master.bar b', 'a.advocate_id = b.bar_id', 'inner')  // Join bar table
        ->where('a.caveat_no', $caveat_no)  // Add where condition for caveat_no
        ->where('a.display', 'Y')  // Add where condition for display = 'Y'
        ->get();  // Execute the query

    
    return $query->getResultArray();  // Return the result as an array
}

    
    	
    /* Added by RGV End - 05032024*/
}
