<?php

namespace App\Models\Scanning\SupremeCourtScan;

use CodeIgniter\Model;

class SupremeCourtScanModal extends Model
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $db = \Config\Database::connect();
    }


    public function getIndexingReport($from_date, $to_date, $status)
    {
        $builder = $this->db->table('lowerct a');

        // Correct the main query to use a valid join for the state table
        $builder->select('a.diary_no, lct_dec_dt, l_dist, ct_code, l_state, b.name AS state_name, 
                        a.lct_casetype, a.lct_caseno, a.lct_caseyear, 
                        a.lower_court_id, a.is_order_challenged, 
                        a.full_interim_flag, a.judgement_covered_in, 
                        a.casetype_id, conformation');
        
        // Fix the CASE statement for agency_name
        $builder->select("(CASE
                            WHEN ct_code = 3 THEN (SELECT Name FROM master.state s WHERE s.id_no = a.l_dist AND s.display = 'Y')
                            ELSE (SELECT agency_name FROM master.ref_agency_code c WHERE c.cmis_state_id = a.l_state AND c.id = a.l_dist AND c.is_deleted = 'f')
                        END) AS agency_name", false);
        
        // Fix the CASE statement for type_sname
        $builder->select("(CASE
                            WHEN ct_code = 4 THEN (SELECT skey FROM master.casetype ct WHERE ct.display = 'Y' AND ct.casecode = a.lct_casetype)
                            ELSE (SELECT type_sname FROM master.lc_hc_casetype d WHERE d.lccasecode = a.lct_casetype AND d.display = 'Y')
                        END) AS type_sname", false);
        
        // Correctly join the state table for the main query
        $builder->join('master.state b', 'a.l_state = b.id_no AND b.display = \'Y\'', 'left');
        $builder->join('main e', 'e.diary_no = a.diary_no');
        $builder->join('public.transfer_to_details t_t', 't_t.lowerct_id = a.lower_court_id AND t_t.display = \'Y\'', 'left');
        $builder->join('lowercourt_data.indexing_hc_dc ihd', 'ihd.diary_no = a.diary_no');
    
        // Add date range condition
        $builder->where("date(ent_dt) >=", $from_date);
        $builder->where("date(ent_dt) <=", $to_date);  // Uncomment if necessary
        $builder->where('lw_display', 'Y');
        $builder->where('ct_code !=', 4);
        $builder->where('c_status', 'P');
        $builder->where('ihd.display', 'Y');
    
        // Handle status
        if ($status == '1') {
            $builder->where('conformation', 1);
        } elseif ($status == '2') {
            $builder->where('conformation', 0);
        }elseif ($status == '12'){
            $builder->where('conformation', );
        }

        // Conditional logic for casetype_id and is_order_challenged
        $builder->groupStart()
                ->where('a.casetype_id !=', 7)
                ->where('a.casetype_id !=', 8)
                ->where('a.is_order_challenged', 'Y')
                ->groupEnd();
        
        // Ordering
        $builder->orderBy('a.diary_no', 'ASC');
        $builder->orderBy('a.lower_court_id', 'ASC');

        $query = $builder->get();
    
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        }
    
        return false;
    }

    public function findDiaryDetail($diary_no, $year)
    {
        $builder =$this->db->table('diary_details');
        $builder->select('*');
        $builder->where('diary_no', $diary_no);
        $builder->where('diary_year', $year);
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        }
    
        return false;
    }

    public function getFileSD($file)
    {
        $ex_explode = explode('_', $file);

        //if (count($ex_explode) >= 3) {
            $casecode = $ex_explode[0];
            $builder = $this->db->table('master.casetype');
            $builder->select('short_description');
            $builder->where('casecode', $casecode);
            $builder->where('display', 'Y');

            $query = $builder->get();

            if ($query->getNumRows() > 0) {
                $result = $query->getRow();
                $result = $result->short_description;
                //return $res_case_type . '-' . $ex_explode[1] . '-' . $ex_explode[2];
                return $result;
            } else {
                return 'No description found';
            }
        // } else {
        //     return 'Invalid file format';
        // }
    }
    
}
