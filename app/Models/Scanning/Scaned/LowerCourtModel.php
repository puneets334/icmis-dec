<?php

namespace App\Models\Scanning\Scaned;

use CodeIgniter\Model;

class LowerCourtModel extends Model
{

    protected $db;
    public function __construct()
    {
        parent::__construct();
        $db = \Config\Database::connect();
    }
    public function getRecords($txt_frm_date, $txt_to_date)
    {
        $builder = $this->db->table('lowerct a');
        // $builder->select('DISTINCT 
        $builder->select(' 
            a.diary_no, 
            lct_dec_dt, 
            l_dist, 
            ct_code, 
            l_state, 
            CASE
                WHEN ct_code = 3 THEN
                    (SELECT name FROM master.state s WHERE s.id_no = a.l_dist AND display = \'Y\' LIMIT 1)
                ELSE
                    (SELECT agency_name FROM master.ref_agency_code c WHERE c.cmis_state_id = a.l_state AND c.id = a.l_dist AND is_deleted = \'f\' LIMIT 1)
            END AS agency_name, 
            lct_casetype, 
            lct_caseno, 
            lct_caseyear, 
            CASE
                WHEN ct_code = 4 THEN
                    (SELECT skey FROM master.casetype ct WHERE ct.display = \'Y\' AND ct.casecode = a.lct_casetype LIMIT 1)
                ELSE
                    (SELECT type_sname FROM master.lc_hc_casetype d WHERE d.lccasecode = a.lct_casetype AND d.display = \'Y\' LIMIT 1)
            END AS type_sname, 
            a.lower_court_id, 
            is_order_challenged, 
            full_interim_flag, 
            judgement_covered_in, 
            a.casetype_id
        ');

        // Join with the main table
        $builder->join('main e', 'e.diary_no = a.diary_no');
        
        // Add WHERE conditions based on the parameters
        $builder->where('ent_dt >=', $txt_frm_date);
        $builder->where('ent_dt <=', $txt_to_date); // Uncomment this if you want to include this condition
        // Uncomment the below lines if you want to include more filtering
        // $builder->where('l_state', '292979');
        // $builder->where('l_dist', '17');
        $builder->where('lw_display', 'Y');
        $builder->where('ct_code !=', 4);
        $builder->where('c_status', 'P');
        $builder->where('is_order_challenged', 'Y');

        // Order the results
        $builder->orderBy('a.diary_no, a.lower_court_id');

        return $builder->get()->getResultArray();
    }


}
