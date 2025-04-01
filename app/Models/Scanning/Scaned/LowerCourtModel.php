<?php

namespace App\Models\Scanning\Scaned;

use CodeIgniter\Model;

class LowerCourtModel extends Model
{

    protected $db;
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }
    public function getRecords($txt_frm_date, $txt_to_date)
    {

            // a.lct_dec_dt,  a.l_dist,  a.ct_code, a.l_state,  CASE WHEN a.ct_code = 3 THEN (SELECT s.name FROM master.state s WHERE s.id_no = a.l_dist 
        //     AND s.display = \'Y\' 
        //          LIMIT 1)
        //     ELSE
        //         (SELECT c.agency_name 
        //          FROM master.ref_agency_code c 
        //          WHERE c.cmis_state_id = a.l_state 
        //          AND c.id = a.l_dist 
        //          AND c.is_deleted = \'f\' 
        //          LIMIT 1)
        // END AS agency_name, 
        // a.lct_casetype,
            // a.lower_court_id, 
            // a.is_order_challenged, 
            // a.full_interim_flag, 
            // a.judgement_covered_in, 
            // e.casetype_id


        $builder = $this->db->table('lowerct a');
        $builder->select('a.diary_no,a.lct_caseno, a.lct_caseyear, 
            CASE
                WHEN a.ct_code = 4 THEN
                    (SELECT ct.skey 
                     FROM master.casetype ct 
                     WHERE ct.display = \'Y\' 
                     AND ct.casecode = a.lct_casetype 
                     LIMIT 1)
                ELSE
                    (SELECT d.type_sname 
                     FROM master.lc_hc_casetype d 
                     WHERE d.lccasecode = a.lct_casetype 
                     AND d.display = \'Y\' 
                     LIMIT 1)
            END AS type_sname
        ');
        
        // Join with the main table
        $builder->join('main e', 'e.diary_no = a.diary_no');
        
        // Add WHERE conditions based on the parameters
        $builder->where('a.ent_dt >=', $txt_frm_date);
        $builder->where('a.ent_dt <=', $txt_to_date); // Uncomment if you want to include this condition
        $builder->where('a.l_state', '292979');
        $builder->where('a.l_dist', '17');
        $builder->where('a.lw_display', 'Y');
        $builder->where('a.ct_code !=', 4);
        $builder->where('e.c_status', 'P');
        $builder->where('a.is_order_challenged', 'Y');
        
        // Order the results
        $builder->orderBy('a.diary_no, a.lower_court_id');
        // echo $builder->getCompiledSelect();
        // Execute the query and return the results as an array
        return $builder->get()->getResultArray();
        
    }


}
