<?php

namespace App\Models\Judicial\FileCover;

use CodeIgniter\Model;

class CoverPrintModel extends Model
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    // public function getDiaryDetails($diary_no)
    // {
    //     try {
    //         // Check if diary number is provided
    //         if (empty($diary_no)) {
    //             throw new \Exception('Diary number is required.');
    //         }

    //         // Initialize Query Builder
    //         $builder = $this->db->table('main');
    //         $builder->select('section_id, dacode, diary_no_rec_date, fil_dt, pet_name, res_name, pno, rno, fil_no, casetype_id');
    //         $builder->where('diary_no', $diary_no);

    //         // Execute the query
    //         $query = $builder->get();

    //         // Initialize data array
    //         $data = [];
    //         if ($query->getNumRows() > 0) {
    //             $data = $query->getResultArray();
    //         } else {
    //             // Log if no data is found
    //             log_message('info', "No records found for diary number: {$diary_no}");
    //         }

    //         // Return fetched data
    //         return $data;
    //     } catch (\Exception $e) {
    //         // Log the error for debugging
    //         log_message('error', 'Error fetching diary details: ' . $e->getMessage());
    //         throw $e;
    //     }
    // }
    // private function getLowerCourtDetails($diary_no)
    // {
    //     return $this->db->table('lower_court')
    //         ->select('*') // Include required fields
    //         ->where('diary_no', $diary_no)
    //         ->get()
    //         ->getResultArray();
    // }

    
    public function getIADetails($diary_no,$diary_year='')
    {

        $builder = $this->db->table('public.docdetails a');
        $builder->select('iastat, diary_no, docnum, docyear, docdesc, iastat');
        $builder->join('master.docmaster b', 'a.doccode = b.doccode AND a.doccode1 = b.doccode1');
        $builder->where('a.display', 'Y');
        $builder->where('diary_no', $diary_no.$diary_year);
        $builder->where('a.doccode', '8');
        $builder->where('b.display', 'Y');

        // Use RAW SQL for ORDER BY CASE
        $builder->orderBy("CASE a.iastat WHEN 'P' THEN 1 WHEN 'D' THEN 2 ELSE 3 END", '', false);
        $builder->orderBy('docnum', 'ASC');

        $query = $builder->get();
        return $results = $query->getResultArray(); // Fetch results        
        // echo $db->getLastQuery();

    }

    public function getAOR_COR_Details($diary_no,$diary_year='')
    {
        
        $builder = $this->db->table('public.advocate a');
        $builder->select("b.aor_code, b.name, STRING_AGG(DISTINCT a.adv, ', ' ORDER BY a.adv) AS tot_pet");
        $builder->join('master.bar b', 'a.advocate_id = b.bar_id');
        $builder->where('a.display', 'Y');
        $builder->where('a.pet_res', 'R');
        $builder->where('a.diary_no', $diary_no.$diary_year);       
        $builder->groupBy('b.aor_code, b.name');
        //$builder->orderBy('MIN(a.pet_res_no)');
        $query = $builder->get();
         return $results = $query->getResultArray(); // Fetch results        
              
        //echo $this->db->getLastQuery();die;

    }

    public function getLowerCourtDetails($diary_no,$diary_year='')
    {
        
        if (empty($diary_no)) {
            throw new \Exception('Diary number is required for fetching lower court details.');
        }

        // Check case type from the main database
        $builder = $this->db->table('main');
        $builder->select('active_casetype_id');
        $builder->where('diary_no', $diary_no.$diary_year);
        $chk_casetype_query = $builder->get();
        $res_chk_casetype = $chk_casetype_query->getRowArray();
        //echo "<prE>";print_r($res_chk_casetype);die;
        //echo $this->db->getLastQuery();die;
        
        $is_order_challenged = '';
        if (!empty($res_chk_casetype) && !in_array($res_chk_casetype['active_casetype_id'], [25, 26, 7, 8])) {
            $is_order_challenged = " AND is_order_challenged = 'Y' ";
        }

        // Construct the SQL query to get lower court details using STRING_AGG
        $sql = "SELECT lct_dec_dt, l_dist, ct_code, l_state, Name, agency_name, 
                STRING_AGG(lct_casetype::text, ', ' ORDER BY lower_court_id) AS lct_casetype, 
                STRING_AGG(lct_caseno::text, ', ' ORDER BY lower_court_id) AS lct_caseno, 
                STRING_AGG(lct_caseyear::text, ', ' ORDER BY lower_court_id) AS lct_caseyear, 
                STRING_AGG(type_sname, ', ' ORDER BY lower_court_id) AS type_sname
            FROM (
                SELECT lct_dec_dt, l_dist, ct_code, l_state, Name,
                    CASE WHEN ct_code = 3 
                        THEN (SELECT Name FROM master.state s WHERE s.id_no = a.l_dist AND s.display = 'Y')
                        ELSE (SELECT CONCAT(agency_name, ', ', address) 
                            FROM master.ref_agency_code c 
                            WHERE c.cmis_state_id = a.l_state 
                            AND c.id = a.l_dist 
                            AND c.is_deleted = 'f')
                    END AS agency_name,
                    crimeno, crimeyear, polstncode, 
                    (SELECT policestndesc 
                    FROM master.police p 
                    WHERE p.policestncd = a.polstncode 
                    AND p.display = 'Y' 
                    AND p.cmis_state_id = a.l_state 
                    AND p.cmis_district_id = a.l_dist) AS policestndesc, 
                    lct_casetype, lct_caseno, lct_caseyear,
                    CASE WHEN ct_code = 4 
                        THEN (SELECT short_description FROM master.casetype ct 
                            WHERE ct.display = 'Y' AND ct.casecode = a.lct_casetype) 
                        ELSE (SELECT type_sname FROM master.lc_hc_casetype d 
                            WHERE d.lccasecode = a.lct_casetype AND d.display = 'Y')
                    END AS type_sname, 
                    a.lower_court_id, is_order_challenged, full_interim_flag, judgement_covered_in
                FROM lowerct a
                LEFT JOIN master.state b ON a.l_state = b.id_no AND b.display = 'Y'
                JOIN main e ON e.diary_no = a.diary_no
                WHERE a.diary_no = ? 
                AND lw_display = 'Y'
                $is_order_challenged
                ORDER BY a.lower_court_id
            ) aa 
            GROUP BY lct_dec_dt, l_dist, ct_code, l_state, Name, agency_name";

        // Execute the SQL query
        try {
            $query = $this->db->query($sql, [$diary_no.$diary_year]);
        } catch (\Exception $e) {
            // Log the error or display a message for debugging
            throw new \Exception('Error executing SQL query: ' . $e->getMessage());
        }

        //$query->getNumRows();
        //echo $this->db->getLastQuery();die;
        // Process the results
        $lower_court_details = [];
        if ($query->getNumRows() > 0) {
            //echo "<pre>";print_r($query->getResultArray());die;
            foreach ($query->getResultArray() as $row) {
                $lower_court_details[] = [
                    'lct_dec_dt' => $row['lct_dec_dt'],
                    'Name' => $row['name'],
                    'agency_name' => $row['agency_name'],
                    'type_sname' => $row['type_sname'],
                    'lct_caseno' => $row['lct_caseno'],
                    'lct_caseyear' => $row['lct_caseyear'],
                    'lct_casetype' => $row['lct_casetype']
                ];
            }
        }
      //echo "<pre>";print_r($lower_court_details);die();
        return $lower_court_details;
    }
}
