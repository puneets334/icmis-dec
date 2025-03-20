<?php

namespace App\Models\Scanning;
use CodeIgniter\Model;
class ScaningModel extends Model
{
    protected $db;
    
    public function __construct()
    {
        parent::__construct();
		$db = \Config\Database::connect();
    }

    // Fetch court data
    public function getDataByDateRange($fromDate, $toDate)
    {
        $db = \Config\Database::connect();
        $sql = "SELECT c.SNO, c.Case_NO, c.Cause_Title, c.diary_dt, c.verified_on
                FROM (
                    SELECT 
                    ROW_NUMBER() OVER (ORDER BY dv.verification_date ASC) AS SNO,  -- Moved ROW_NUMBER here
                    CONCAT(m.reg_no_display, ' @ ', m.diary_no) AS Case_NO, 
                    CONCAT(pet_name, ' Vs. ', res_name) AS Cause_Title, 
                    TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS diary_dt, 
                    TO_CHAR(dv.verification_date, 'DD-MM-YYYY HH24:MI:SS') AS verified_on
                    FROM public.defects_verification dv 
                    INNER JOIN public.main m ON dv.diary_no = m.diary_no
                    WHERE m.c_status = 'P' 
                    AND dv.verification_date::DATE BETWEEN ? AND ? 
                    AND CAST(dv.verification_status AS INTEGER) = 0  -- Cast to integer or compare to string
                    ORDER BY dv.verification_date ASC
                ) c;";

        $query = $db->query($sql, [$fromDate, $toDate]);
        $result = $query->getResult();
        return $result;     
    }

    public function getCaseType()
    {
        $builder = $this->db->table("master.casetype");
        $builder->select("casecode, skey, casename,short_description");
        $builder->where("display","Y");
        $builder->where("casecode != 9999");
        $builder->whereNotIn("casecode", [9999, 15, 16]);
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }   
    }  

    public function getRosterIds($stg, $t_cn)
    {
        $query = $this->db->query("
            SELECT DISTINCT roster_id
            FROM `roster_judge` rj
            JOIN `roster` r ON rj.roster_id = r.id
            JOIN `roster_bench` rb ON rb.id = r.bench_id AND rb.display = 'Y'
            JOIN `master_bench` mb ON mb.id = rb.bench_id AND mb.display = 'Y'
            WHERE r.m_f = $stg $t_cn
            AND rj.display = 'Y'
            AND r.display = 'Y'
            ORDER BY IF(courtno = 0, 9999, courtno),
                CASE 
                    WHEN board_type_mb = 'J' THEN 1
                    WHEN board_type_mb = 'C' THEN 2
                    WHEN board_type_mb = 'CC' THEN 3
                    WHEN board_type_mb = 'R' THEN 4
                END, judge_id
        ");
        
        $results = $query->getResultArray();
        $roster_ids = implode(',', array_column($results, 'roster_id'));
        
        return $roster_ids;
    }

    public function buildWhereCondition($movement_flag_type)
    {
        switch ($movement_flag_type) {
            case 'ALL':
                return "ORDER BY h.judges, IF(brd_prnt = 'NA', 2, 1), h.brd_slno, ...";
            case 'receive':
                return "WHERE ((sm.movement_flag='return' AND sm.roster_id != h.roster_id) OR sm.movement_flag IS NULL) ORDER BY ...";
            case 'return':
                return "WHERE sm.movement_flag='receive' ORDER BY ...";
            case 'already_return':
                return "WHERE sm.movement_flag='return' AND sm.roster_id = h.roster_id ORDER BY ...";
            default:
                return '';
        }
    }

    public function getCaseDetails($list_date, $mainhead, $roster_ids, $where_condition)
    {
        $sql = "SELECT sm.entry_date_time, sm.list_dt as move_next_dt, sm.movement_flag, ...
            FROM heardt t1
            WHERE t1.next_dt = '$list_date'
            AND t1.mainhead = '$mainhead'
            AND FIND_IN_SET(t1.roster_id, '$roster_ids') > 0
            UNION SELECT ...
            FROM last_heardt t2
            WHERE t2.next_dt = '$list_date'
            AND FIND_IN_SET(t2.roster_id, '$roster_ids') > 0
            ...
            LEFT JOIN scan_movement sm ON h.diary_no = sm.dairy_no
            $where_condition";

        $query = $this->db->query($sql);
        return $query->getResultArray();
    }

    public function getAssetsSearch($chk_status, $ct, $cn, $cy, $d_no, $d_yr)
    {
        $db = \Config\Database::connect(); // Load the database

        if ($chk_status == 1) {
            $dataRes="No Record Found";
            return $dataRes;

            // Query for diary number from 'main'
            $builder = $db->table('public.main');
            $builder->select("SUBSTRING(CAST(diary_no AS TEXT), 1, LENGTH(CAST(diary_no AS TEXT)) - 4) as dn, 
                            SUBSTRING(CAST(diary_no AS TEXT), -4) as dy")
                    ->where("SUBSTRING_INDEX(fil_no, '-', 1)", $ct)
                    ->where("CAST($cn AS UNSIGNED) BETWEEN SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no, '-', 2), '-', -1) AND SUBSTRING_INDEX(fil_no, '-', -1)", null, false)
                    ->where("(reg_year_mh = 0 OR DATE(fil_dt) > DATE('2017-05-10'))", null, false)
                    ->where("YEAR(fil_dt) = $cy OR reg_year_mh = $cy");

            $query = $builder->get();

            if ($query->getNumRows() > 0) {
                $get_dno = $query->getRowArray();
                $diary_no = $get_dno['dn'] . $get_dno['dy'];
            } else {
                // If no result, fetch from 'main_casetype_history'
                $builder = $db->table('main_casetype_history h');
                $builder->select("SUBSTRING(CAST(h.diary_no AS TEXT), 1, LENGTH(CAST(h.diary_no AS TEXT)) - 4) as dn, 
                                SUBSTRING(CAST(h.diary_no AS TEXT), -4) as dy, 
                                IF(h.new_registration_number != '', SUBSTRING_INDEX(h.new_registration_number, '-', 1), '') as ct1,
                                IF(h.new_registration_number != '', SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2), '-', -1), '') as crf1,
                                IF(h.new_registration_number != '', SUBSTRING_INDEX(h.new_registration_number, '-', -1), '') as crl1")
                        ->where("((SUBSTRING_INDEX(h.new_registration_number, '-', 1) = $ct AND 
                                CAST($cn AS UNSIGNED) BETWEEN 
                                SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2), '-', -1) 
                                AND SUBSTRING_INDEX(h.new_registration_number, '-', -1) 
                                AND h.new_registration_year = $cy) 
                                OR (SUBSTRING_INDEX(h.old_registration_number, '-', 1) = $ct AND 
                                CAST($cn AS UNSIGNED) BETWEEN 
                                SUBSTRING_INDEX(SUBSTRING_INDEX(h.old_registration_number, '-', 2), '-', -1) 
                                AND SUBSTRING_INDEX(h.old_registration_number, '-', -1) 
                                AND h.old_registration_year = $cy AND h.is_deleted = 't'))")
                        ->where("h.is_deleted", 'f');

                $query = $builder->get();

                if ($query->getNumRows() > 0) {
                    $get_dno = $query->getRowArray();
                    $diary_no = $get_dno['dn'] . $get_dno['dy'];
                } else {
                    echo "Case Number not found";
                    exit();
                }
            }
        } else {
            $diary_no = $d_no . $d_yr;
            $dataRes="No Record Found";
            return $dataRes;
        }
        $builder = $db->table('main m');
        $builder->select('m.diary_no, m.conn_key, m.reg_no_display, r.courtno, h.roster_id, h.next_dt, h.mainhead')
                ->join('heardt h', 'h.diary_no = m.diary_no')
                ->join('roster r', 'r.id = h.roster_id')
                ->where('m.diary_no', $diary_no)
                ->where('h.next_dt >=', date('Y-m-d'))
                ->where('h.clno >', 0)
                ->groupBy('m.diary_no');
        
        $query = $builder->get();

        if ($query->getNumRows() > 0)
        {
            foreach ($query->getResultArray() as $data_case) {
                $courtno = $data_case['courtno'];
                $list_date = date("d-m-Y", strtotime($data_case['next_dt']));
                $mainhead = $data_case['mainhead'];

                if (!empty($data_case['conn_key'])) {
                    $diary_no_manual_qry = "and conn_key = " . $data_case['conn_key'];
                } else {
                    $diary_no_manual_qry = "and diary_no = $diary_no";
                }

                // Handle $courtno, $list_date, $mainhead, $diary_no_manual_qry as needed.
            }
        } else {
            echo "Case Not Listed";
            exit();
        }
    }
    /* 21-10-2024 */
    public function getCourtDetails($diary_no)
    {
        $sql = "SELECT DISTINCT a.ct_code, b.court_name
                FROM public.lowerct AS a
                JOIN master.m_from_court AS b ON a.ct_code = b.id
                WHERE a.lw_display = 'Y' AND a.diary_no = ? AND b.display = 'Y'";
        
        $query = $this->db->query($sql, [$diary_no]); // Bind the parameter
        return $query->getResult();
    }
    public function getIndexDocs($diary_no)
    {
        $sql = "SELECT docdesc, a.doccode, a.doccode1, other, fp, tp, np, pdf_name, lowerct_id, a.i_type FROM (
        SELECT * FROM public.indexing  WHERE diary_no = ? AND display = 'Y' ) a LEFT JOIN master.docmaster b ON a.doccode = b.doccode 
        AND a.doccode1 = b.doccode1 AND (b.display = 'Y' OR b.display = 'E') ORDER BY  CASE WHEN a.doccode = 100 THEN 0  ELSE 1  END,  fp;";    
        $query = $this->db->query($sql, [$diary_no]); // Bind the parameter
        return $query->getResult();
    }
    /* 22-10-2024 */

    public function getLowerCourtDetails($lowerct_id)
    {
        $sql = "SELECT ct_code, l_state, l_dist, lower_court_id, 
                    CASE  WHEN ct_code = 3 THEN ( 
                            SELECT Name 
                            FROM master.state s  
                            WHERE s.id_no = a.l_dist AND display = 'Y' 
                        )
                        ELSE ( 
                            SELECT agency_name 
                            FROM master.ref_agency_code c 
                            WHERE c.cmis_state_id = a.l_state AND c.id = a.l_dist AND is_deleted = 'f'
                        ) 
                    END AS agency_name, 
                    CASE  
                        WHEN ct_code = 4 THEN (  
                            SELECT skey 
                            FROM master.casetype ct 
                            WHERE ct.display = 'Y' AND ct.casecode = a.lct_casetype 
                        )
                        ELSE ( 
                            SELECT type_sname 
                            FROM master.lc_hc_casetype d  
                            WHERE d.lccasecode = a.lct_casetype AND d.display = 'Y'
                        ) 
                    END AS type_sname, 
                    Name, lct_caseno, lct_caseyear, court_name  
                FROM lowerct a 
                LEFT JOIN master.state b  
                    ON a.l_state = b.id_no  AND b.display = 'Y'   
                JOIN master.m_from_court fc  
                    ON fc.id = a.ct_code  AND fc.display = 'Y' 
                WHERE lower_court_id =? AND lw_display = 'Y'";     
                 return $this->db->query($sql, [$lowerct_id])->getResultArray(); // Fetch one result row
    }

    public function getDiaryDocumentsDetails($dairy_no)
    {
        $sql = "SELECT docnum, docyear, a.doccode, a.doccode1, other1, docdesc, docd_id FROM public.docdetails a 
        JOIN master.docmaster b ON a.doccode = b.doccode AND a.doccode1 = b.doccode1 
        LEFT JOIN public.indexing c ON c.diary_no = a.diary_no 
        AND c.display = 'Y' AND c.src_of_ent = a.docd_id WHERE a.diary_no = ?  AND a.display = 'Y' AND b.display = 'Y' AND c.src_of_ent IS NULL 
        ORDER BY docyear, docnum";
        $result = $this->db->query($sql, [$dairy_no])->getResult();
        // echo $this->db->getLastQuery();
        // die;
        return $result;

    }


    public function getAllDocuments()
    {
        // Create a query builder instance
        $builder = $this->db->table('master.docmaster');
        $builder->select('doccode, doccode1, docdesc');
        $builder->where('doccode1', 0);
        $builder->groupStart()
                ->where('display', 'Y')
                ->orWhere('display', 'E')
                ->groupEnd();
        $builder->orderBy('doccode');
        
        // Execute the query and return the result
        $query = $builder->get();
        return $query->getResult(); // You can also use getResultArray() if you prefer an array
    }
    public function getTotalIndexingPage($dairy_no)
    {
        $from_page_start = 1; 
        $sql = "SELECT MAX(tp) as tp FROM public.indexing WHERE diary_no = ? AND display = 'Y'";
        $result = $this->db->query($sql, [$dairy_no])->getRow();
        if ($result && $result->tp !== null) {
            $from_page_start = $result->tp + 1;
        }
        return $from_page_start;
    }


    public function getSubDocsByDoccode($doccode)
    {
        $builder = $this->db->table('master.docmaster');
        $builder->where('display', 'Y');
        $builder->orWhere('display', 'E');
        $builder->where('doccode', $doccode);
        $builder->orderBy('docdesc');
        $query = $builder->get(); 
        return $query->getResultArray(); 
    }
    public function checkIndexRecord($diary_no, $itype, $doccode, $doccode1)
    {
        $builder = $this->db->table('public.indexing');
        $builder->where('diary_no', $diary_no);
        $builder->where('i_type', $itype);
        $builder->where('doccode', $doccode);
        $builder->where('doccode1', $doccode1);
        $builder->where('display', 'Y');        
        $query = $builder->get(); 
        $checkResponse= $query->getResultArray(); 
        return $checkResponse;
    }

    public function insertDataIndex(array $data)
    {
        if (empty($data)) {
            return false; // Return false if data is empty
        }

        $result = $this->db->table("public.indexing")->insert($data);
        
        // Check for errors during insertion
        if (!$result) {
            // Handle errors (optional, you can log them if necessary)
            log_message('error', 'Insert failed: ' . json_encode($this->errors()));
            return false; // Return false if insertion fails
        }

        return true; // Return true if insertion is successful
    }



    public function getStateNameData($court_code, $dairy_no)
    {

        // echo $dairy_no;
        // die;
        // $builder = $this->db->table('master.state a');
        // $builder->select('DISTINCT a.id_no, a.Name')
        // ->join('public.lowerct b', 'a.id_no = b.l_state')
        // ->where('District_code', 0)
        // ->where('Sub_Dist_code', 0)
        // ->where('Village_code', 0)
        // ->where('display', 'Y')
        // ->where('sci_state_id !=', 0)
        // ->where('lw_display', 'Y')
        // ->where('ct_code', $court_code)
        // ->where('b.diary_no', $dairy_no)
        // ->orderBy('a.Name');


        $sql = "SELECT DISTINCT a.id_no, a.Name 
        FROM master.state a 
        JOIN public.lowerct b ON a.id_no = b.l_state 
        WHERE a.district_code = 0 
        AND a.sub_dist_code = '0'
        AND a.village_code = '0'
        AND a.display = 'Y' 
        AND a.sci_state_id != 0 
        AND b.lw_display = 'Y' 
        AND b.ct_code = ? 
        AND b.diary_no = ? 
        ORDER BY a.Name";

        // Running the query with bound parameters
        $result = $this->db->query($sql, [$court_code, $dairy_no])->getResultArray();

        return $result;
        
      
    }





   

   

    














}



