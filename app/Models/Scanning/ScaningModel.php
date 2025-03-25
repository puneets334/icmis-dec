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
        $builder = $this->db->table('public.defects_verification dv');
        $builder->select("ROW_NUMBER() OVER (ORDER BY dv.verification_date ASC) AS SNO,
                  CONCAT(m.reg_no_display, ' @ ', m.diary_no) AS Case_NO, 
                  CONCAT(pet_name, ' Vs. ', res_name) AS Cause_Title, 
                  TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS diary_dt, 
                  TO_CHAR(dv.verification_date, 'DD-MM-YYYY HH24:MI:SS') AS verified_on")
            ->join('public.main m', 'dv.diary_no = m.diary_no')
            ->where('m.c_status', 'P')
            ->where('CAST(dv.verification_status AS INTEGER)', 0)
            ->where('DATE(dv.verification_date) >=', $fromDate)
            ->where('DATE(dv.verification_date) <=', $toDate)
            ->orderBy('dv.verification_date', 'ASC');

        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }

    public function getCaseType()
    {
        $builder = $this->db->table("master.casetype");
        $builder->select("casecode, skey, casename,short_description");
        $builder->where("display", "Y");
        $builder->where("casecode != 9999");
        $builder->whereNotIn("casecode", [9999, 15, 16]);
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return [];
        }
    }

    public function getRosterIds($stg, $t_cn)
    {
      
        $builder = $this->db->table('master.roster_judge rj');
        $builder->distinct();
        $builder->select("rj.roster_id, mb.board_type_mb, 
                  CASE 
                      WHEN mb.board_type_mb = 'J' THEN 1 
                      WHEN mb.board_type_mb = 'C' THEN 2 
                      WHEN mb.board_type_mb = 'CC' THEN 3 
                      WHEN mb.board_type_mb = 'R' THEN 4 
                  END AS board_type_order")
            ->join('master.roster r', 'rj.roster_id = r.id')
            ->join('master.roster_bench rb', 'rb.id = r.bench_id AND rb.display = \'Y\'')
            ->join('master.master_bench mb', 'mb.id = rb.bench_id AND mb.display = \'Y\'')
            ->where('r.m_f', "$stg")
            ->where('rj.display', 'Y')
            ->where('r.display', 'Y');

            if (!empty($t_cn)) {
                $builder->where($t_cn);
            }
            // echo $builder->getCompiledSelect();

        $query = $builder->get();
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
        $sql = "SELECT sm.entry_date_time, sm.list_dt AS move_next_dt, 
       sm.movement_flag, CONCAT(m.reg_no_display, ' @ ', m.diary_no) AS Case_Number, 
       CONCAT(pet_name, ' Vs. ', res_name) AS Cause_Title,  m.diary_no, 
       m.conn_key, h.judges, h.mainhead, h.next_dt, h.subhead, h.clno, h.brd_slno, h.tentative_cl_dt, 
       m.c_status, 
       CASE WHEN cl.next_dt IS NULL THEN 'NA' ELSE h.brd_slno::text END AS brd_prnt, 
       h.roster_id, m.casetype_id, m.case_status_id
    FROM
        (SELECT 
            t1.diary_no,
                t1.next_dt,
                t1.roster_id,
                t1.judges,
                t1.mainhead,
                t1.subhead,
                t1.clno,
                t1.brd_slno,
                t1.main_supp_flag,
                t1.tentative_cl_dt, t1.ent_dt
        FROM
            heardt t1
        WHERE
            t1.next_dt = '$list_date'
                AND t1.mainhead = '$mainhead'
                AND t1.ROSTER_ID = ANY(ARRAY[".$roster_ids."::INTEGER])
                AND (t1.main_supp_flag = 1
                OR t1.main_supp_flag = 2) UNION SELECT 
            t2.diary_no,
                t2.next_dt,
                t2.roster_id,
                t2.judges,
                t2.mainhead,
                t2.subhead,
                t2.clno,
                t2.brd_slno,
                t2.main_supp_flag,
                t2.tentative_cl_dt, t2.ent_dt
        FROM
            last_heardt t2
        WHERE
            t2.next_dt = '$list_date'
                AND t2.mainhead = '$mainhead'
            AND t2.ROSTER_ID = ANY(ARRAY[".$roster_ids."::INTEGER])
                AND (t2.main_supp_flag = 1
                OR t2.main_supp_flag = 2)
                AND t2.bench_flag = '') h
            INNER JOIN
        main m ON (h.diary_no = m.diary_no
            AND h.next_dt = '$list_date'
            AND h.mainhead = '$mainhead'            
            AND h.ROSTER_ID = ANY(ARRAY[".$roster_ids."::INTEGER])
            AND (h.main_supp_flag = 1
            OR h.main_supp_flag = 2))
            LEFT JOIN
        cl_printed cl ON (cl.next_dt = h.next_dt
            AND cl.m_f = h.mainhead
            AND cl.part = h.clno
            AND cl.main_supp = h.main_supp_flag
            AND cl.roster_id = h.roster_id
            AND cl.display = 'Y')
            LEFT JOIN scan_movement sm ON h.diary_no::text = sm.dairy_no    
            ORDER BY h.judges, CASE WHEN cl.next_dt IS NULL THEN 2 ELSE 1 END, h.brd_slno;";

        // print_r($sql);
        // exit;

        $query = $this->db->query($sql);
        return $query->getResultArray();
    }

    public function getAssetsSearch($chk_status, $ct, $cn, $cy, $d_no, $d_yr)
    {
        $db = \Config\Database::connect(); // Load the database

        if ($chk_status == 1) {
            $dataRes = "No Record Found";
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
            $dataRes = "No Record Found";
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

        if ($query->getNumRows() > 0) {
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
        // $sql = "SELECT docdesc, a.doccode, a.doccode1, other, fp, tp, np, pdf_name, lowerct_id, a.i_type FROM (
        // SELECT * FROM public.indexing  WHERE diary_no = ? AND display = 'Y' ) a LEFT JOIN master.docmaster b ON a.doccode = b.doccode 
        // AND a.doccode1 = b.doccode1 AND (b.display = 'Y' OR b.display = 'E') ORDER BY  CASE WHEN a.doccode = 100 THEN 0  ELSE 1  END,  fp;";    
        // $query = $this->db->query($sql, [$diary_no]); // Bind the parameter
        // return $query->getResult();

        $db = \Config\Database::connect();
        $builder = $db->table('public.indexing AS a');
        $builder->select('docdesc, a.doccode, a.doccode1, other, fp, tp, np, pdf_name, lowerct_id, a.i_type');
        $builder->join('master.docmaster AS b', 'a.doccode = b.doccode AND a.doccode1 = b.doccode1 AND (b.display = \'Y\' OR b.display = \'E\')', 'left');
        $builder->where('a.diary_no', $diary_no);
        $builder->where('b.display', 'Y');
        $builder->orderBy('CASE WHEN a.doccode = 100 THEN 0 ELSE 1 END, fp', 'ASC', false);
        $query = $builder->get();
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
        $checkResponse = $query->getResultArray();
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
