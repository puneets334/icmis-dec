<?php

namespace App\Models\Judicial\OriginalRecord;

use CodeIgniter\Model;

class HcOrModel extends Model
{


    public function getDiaryInfo($dairy_no)
    {
        $builder = $this->db->table('main m');
        $builder->select("COALESCE(m.dacode, NULL) AS dacode, COALESCE(u.name, NULL) AS username, u.empid");
        $builder->join('master.users u', 'm.dacode = u.usercode', 'left');
        $builder->where('m.diary_no', $dairy_no);
        $query = $builder->get();
        //echo $this->db->getLastQuery(); die();
        return $query->getRowArray();
    }
    
    public function getCaseDetails($userId, $caseType, $caseNo, $caseYear)
    {
        $builder = $this->db->table('main m');

        // Selecting diary number parts
        $builder->select("
            SUBSTRING(CAST(m.diary_no AS TEXT), 1, LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS dn,
            SUBSTRING(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 3, 4) AS dy
        ");

        // Building the WHERE clause
        $builder->groupStart()
            ->groupStart()
                ->where("CAST(SPLIT_PART(NULLIF(m.fil_no, ''), '-', 1) AS INTEGER)", $caseType)
                ->where("$caseNo BETWEEN 
                    CAST(SPLIT_PART(NULLIF(m.fil_no, ''), '-', 2) AS INTEGER) 
                    AND CAST(SPLIT_PART(NULLIF(m.fil_no, ''), '-', -1) AS INTEGER)", null, false)
                ->groupStart()
                    ->where("m.reg_year_mh", 0)
                    ->orWhere("DATE(m.fil_dt) > DATE('2017-05-10')")
                    ->groupStart()
                        ->where("CAST(EXTRACT(YEAR FROM m.fil_dt) AS INTEGER)", $caseYear)
                    ->groupEnd()
                ->orWhere("m.reg_year_mh", $caseYear)
                ->groupEnd()
            ->groupEnd()
            ->orGroupStart()
                ->where("CAST(SPLIT_PART(NULLIF(m.fil_no_fh, ''), '-', 1) AS INTEGER)", $caseType)
                ->where("$caseNo BETWEEN 
                    CAST(SPLIT_PART(NULLIF(m.fil_no_fh, ''), '-', 2) AS INTEGER) 
                    AND CAST(SPLIT_PART(NULLIF(m.fil_no_fh, ''), '-', -1) AS INTEGER)", null, false)
                ->groupStart()
                    ->where("m.reg_year_fh", 0)
                    ->groupStart()
                        ->where("(EXTRACT(YEAR FROM m.fil_dt_fh))", $caseYear)
                    ->groupEnd()
                ->orWhere("m.reg_year_fh", $caseYear)
                ->groupEnd()
            ->groupEnd()
        ->groupEnd();

        // Debugging: Print the generated SQL
        // echo $builder->getCompiledSelect();
        // die();

        // Execute the query
        $query = $builder->get();
        return $query->getRowArray();
    }

    


    public function getDiaryCount($condition)
    {

        $builder = $this->db->table('lowerct a');

        $builder->select("COUNT(DISTINCT a.diary_no) AS Count");

        // Join with related tables
        $builder->join('master.lc_hc_casetype d', 'd.lccasecode = a.lct_casetype', 'inner');
        $builder->join('main e', 'e.diary_no = a.diary_no', 'inner');
        $builder->join('lowercourt_data.indexing_hc_dc ihd', 'ihd.diary_no = a.diary_no AND ihd.display = \'Y\'', 'inner');
        $builder->join('master.state b', 'a.l_state = b.id_no AND b.display = \'Y\'', 'left');
        $builder->join('transfer_to_details t_t', 't_t.lowerct_id = a.lower_court_id AND t_t.display = \'Y\'', 'left');

        // Add conditions
        $builder->where('a.lw_display', 'Y');
        $builder->where('a.ct_code !=', 4);
        $builder->where($condition);
        $builder->where('e.c_status', 'P');
        $builder->groupStart()
            ->groupStart()
            ->where('e.casetype_id !=', 7)
            ->where('e.casetype_id !=', 8)
            ->where('a.is_order_challenged', 'Y')
            ->groupEnd()
            ->orGroupStart()
            ->where('e.casetype_id', 7)
            ->orWhere('e.casetype_id', 8)
            ->groupEnd()
            ->groupEnd();

        // Execute the query
        $query = $builder->get();

        // Debugging: Uncomment to inspect the query
        //echo $builder->getCompiledSelect(); die();

        // Get the result
        $result = $query->getRowArray();

        // Return the count if it exists
        return $result ? (int) $result['count'] : 0; // Return 0 if no result
    }


    public function getRankedData($condition, $fst, $inc_val, $dairy_no)
    {
        $sql = "
    SELECT *
FROM (
    SELECT DISTINCT
        a.diary_no,
        a.lct_dec_dt,
        a.l_dist,
        a.ct_code,
        a.l_state,
        CASE 
            WHEN a.ct_code = 3 THEN (
                SELECT s.name
                FROM master.state s
                WHERE s.id_no = a.l_dist AND s.display = 'Y'
            )
            ELSE (
                SELECT c.agency_name
                FROM master.ref_agency_code c
                WHERE c.cmis_state_id = a.l_state
                  AND c.id = a.l_dist
                  AND c.is_deleted = 'f'
            )
        END AS agency_name,
        a.lct_casetype,
        a.lct_caseno,
        a.lct_caseyear,
        CASE 
            WHEN a.ct_code = 4 THEN (
                SELECT ct.skey
                FROM master.casetype ct
                WHERE ct.display = 'Y'
                  AND ct.casecode = a.lct_casetype
            )
            ELSE (
                SELECT d.type_sname
                FROM master.lc_hc_casetype d
                WHERE d.lccasecode = a.lct_casetype
                  AND d.display = 'Y'
            )
        END AS type_sname,
        a.lower_court_id,
        a.is_order_challenged,
        a.full_interim_flag,
        a.judgement_covered_in,
        e.casetype_id,
        ihd.conformation
    FROM 
        lowerct a
        LEFT JOIN master.state b ON a.l_state = b.id_no AND b.display = 'Y'
        INNER JOIN master.lc_hc_casetype d ON d.lccasecode = a.lct_casetype
        JOIN main e ON e.diary_no = a.diary_no
        LEFT JOIN transfer_to_details t_t ON t_t.lowerct_id = a.lower_court_id AND t_t.display = 'Y'
        JOIN lowercourt_data.indexing_hc_dc ihd ON ihd.diary_no = a.diary_no
    WHERE
        a.lw_display = 'Y'
        AND $condition
        AND a.ct_code != 4
        AND e.c_status = 'P'
        AND (
            CASE
                WHEN e.casetype_id NOT IN (7, 8) THEN a.is_order_challenged = 'Y'
            END
        )
        AND ihd.display = 'Y'
) AS subquery
ORDER BY
    SUBSTRING(CAST(diary_no AS VARCHAR) FROM LENGTH(CAST(diary_no AS VARCHAR)) - 3 FOR 4),
    SUBSTRING(CAST(diary_no AS VARCHAR) FROM 1 FOR LENGTH(CAST(diary_no AS VARCHAR)) - 4),
    lower_court_id;

";

        //echo  $this->db->getLastQuery(); die();
        $result = $this->db->query($sql);
 
        $resultdata = $result->getResultArray();
        // Check if the query returns any records
        if ($result->getNumRows() > 0) {
            // If records exist, run a second query to verify
            $query_verify = $this->db->table('verify_hcor')
                ->select("*")
                ->where('diary_no', $dairy_no)
                ->get();
            $queryVerify = $query_verify->getResultArray();

            // Return the result and queryVerify as an associative array
            return [
                'result' => $resultdata,
                'queryVerify' => $queryVerify
            ];
        }

        // If no records found, return 0
        return 0;
    }


    public function getDefects($dairy_no)
    {
        $builder = $this->db->table('lowercourt_data.defects');
        $builder->select("*");
    
        // Add the condition for diary_no
        $builder->where('diary_no', $dairy_no);
    
        // Add the conditional logic for `if_verified`
        $builder->groupStart() // Start grouping for conditional logic
            ->groupStart()
                ->where('if_verified', 'D')
                ->groupStart() // Nested conditions for 'D'
                    ->where('defect_removed_on IS NULL')
                    ->orWhere("defect_removed_on IS NOT NULL AND defect_removed_on > '1900-01-01 00:00:00'", null, false) // Skip invalid dates
                ->groupEnd()
            ->groupEnd()
            ->orGroupStart()
                ->where('if_verified', 'V')
            ->groupEnd()
        ->groupEnd(); // End grouping for conditional logic
    
        // Execute the query
        $query = $builder->get();
        //echo $this->db->getLastQuery(); die();
            // Check if rows are returned
        // Return the result
        return $query->getRowArray();
    }
    
    
    public function getLowerctDiaryCount($diary_no)
    {
        $builder = $this->db->table('lowerct a');
        $builder->select('COUNT(*) AS s');
        $builder->join('main e', 'e.diary_no = a.diary_no');
        $builder->where('a.diary_no', $diary_no);
        $builder->where('lw_display', 'Y');
        $builder->where('c_status', 'P');
        $builder->groupStart() // Start grouping for conditional logic
            ->groupStart()
                ->where('e.casetype_id !=', 7)
                ->where('e.casetype_id !=', 8)
                ->where('a.is_order_challenged', 'Y')
            ->groupEnd()
            ->orGroupStart()
                ->where('e.casetype_id', 7)
                ->orWhere('e.casetype_id', 8)
            ->groupEnd()
        ->groupEnd(); // End grouping for conditional logic

        // Execute the query and fetch the result
        $query = $builder->get();
        $result = $query->getRow();

        // Return the count if it exists, otherwise return 0
        return $result ? (int) $result->s : 0;
    }


    public function getDefectVerification($diary_no)
    {
        $builder = $this->db->table('lowercourt_data.defects vh');
        
        $builder->select([
            "TO_CHAR(vh.notified_on, 'DD/MM/YYYY HH24:MI:SS') AS verify_on",
            "CONCAT(u.name, ' (', u.empid, ') ', ', ', us.section_name) AS name",
            "COALESCE(vh.defects, 'No Remarks') AS remarks",
            "if_verified"
        ])
        ->join('main m', 'm.diary_no = vh.diary_no', 'inner')
        ->join('master.users u', 'u.usercode = vh.updated_by AND (u.display = \'Y\' OR u.display IS NULL)', 'left')
        ->join('master.usersection us', 'us.id = u.section', 'left')
        ->where('vh.diary_no', $diary_no)
            ->groupStart() // Nested conditions for 'D'
                ->where('vh.defect_removed_on IS NULL')
                ->orWhere("vh.defect_removed_on IS NOT NULL AND vh.defect_removed_on > '1900-01-01 00:00:00'", null, false) // Skip invalid dates
            ->groupEnd();

        // Execute the query
        $query = $builder->get();

        // Return the result as an associative array
        return $query->getResultArray();
    }

    public function getUserData()
    {

        $builder = $this->db->table('master.users u');
        $builder->select('u.usercode');
        $builder->where('u.section', '63');
        $builder->where('u.usercode', '1');
        $builder->where('u.display', 'Y');
        $builder->where('u.attend', 'P');
        $query = $builder->get();
         //echo $this->db->getLastQuery(); die();
        return $query->getResultArray();
    }


    public function updateReOpenCase($diaryNo, $data)
    {
        $builder = $this->db->table('lowercourt_data.indexing_hc_dc');
        
        $builder->where('diary_no', $diaryNo);
        $builder->where('display', 'Y');
        $builder->update($data);
        
        if ($this->db->affectedRows() > 0) {
            return true; // Update was successful
        } else {
            return false; // No rows updated
        }
    }


    public function saveCaseRecord($data)
    {
        $builder = $this->db->table('lowercourt_data.defects');
        $builder->insert($data);
        
        if ($this->db->affectedRows() > 0) {
            return true; // Insert was successful
        } else {
            return false; // Insert failed
        }
    }

    



    public function getMainData($diary_no)
    {

        $builder = $this->db->table('main m');
        $builder->select("
            COALESCE(m.dacode::TEXT, '') AS dacode,
            COALESCE(u.name, '') AS username,
            u.empid
        ");
        $builder->join('master.users u', 'm.dacode = u.usercode', 'LEFT');
        $builder->where('m.diary_no', $diary_no);
        $builder->where('u.section', '63');
        $builder->where('u.usercode', '1');
        $builder->where('u.display', 'Y');
        $builder->where('u.attend', 'P');
        $query = $builder->get();
        //echo $this->db->getLastQuery(); die();
        return $query->getResultArray();
    }

    private function getLowerCtType($lowerct_id)
    {

        $builder = $this->db->table('lowerct a');
        $builder->select("lct_casetype, lct_caseno, lct_caseyear, type_sname");
        $builder->join('master.lc_hc_casetype b', 'a.lct_casetype = b.lccasecode');
        $builder->where('a.lower_court_id', $lowerct_id);
        $builder->where('a.lw_display', 'Y');
        $builder->where('b.display', 'Y');
        $query = $builder->get();
        //echo $this->db->getLastQuery(); die();
        return $query->getResultArray();
    }

    public function getLowerCourtData($diary_no,$d_yr,$d_no)
    {

        $builder = $this->db->table('lowercourt_data.indexing_hc_dc as a');
        $builder->select("
            b.docdesc,
            a.doccode,
            a.doccode1,
            a.other,
            a.fp,
            a.tp,
            a.np,
            a.pdf_name,
            a.lowerct_id,
            a.document_from
        ");
        $builder->join('lowercourt_data.docmaster_hc_dc as b', 'a.doccode = b.id AND (b.display = \'Y\' OR b.display = \'E\')', 'LEFT');
        $builder->where('a.diary_no', $diary_no);
        $builder->where('a.display', 'Y');
        //$builder->orderBy("CASE WHEN a.doccode = 100 THEN 0 ELSE 1 END, a.fp");
    
        $query = $builder->get();

        //echo $this->db->getLastQuery(); die();
        $result = $query->getResultArray();
        //pr($result);
        $sno = 1;
        $total = 0;
        $html = '';

        if (!empty($result)) {
            foreach ($result as $row_data) {
                $document_from = '';
                if ($row_data['document_from'] == 'H') {
                    $document_from = "High Court";
                } elseif ($row_data['document_from'] == 'D') {
                    $document_from = "District Court";
                }
    
                $lower_ct_result = $this->getLowerCtType($row_data['lowerct_id']); // Fetch lower court type data
                if (!empty($lower_ct_result) && is_array($lower_ct_result)) {
                    $first_row = $lower_ct_result[0];
                    $against_case = isset($first_row['type_sname'], $first_row['lct_caseno'], $first_row['lct_caseyear'])
                        ? $first_row['type_sname'] . '-' . $first_row['lct_caseno'] . '-' . $first_row['lct_caseyear']
                        : '';
                } else {
                    $against_case = '';
                }

                $pdf_link = $row_data['pdf_name'] != ''
                    ? "https://registry.sci.gov.in/hcor_upload/scan_file_hc_dc/" . $d_yr . '/' . $d_no . '/' . str_replace('+', ' ', urlencode($row_data['pdf_name']))
                    : '';
    
                $html .= '<tr class="with_border" id="r1w' . $sno . '">
                            <td>' . $sno . '</td>
                            <td>' . $document_from . '</td>
                            <td>' . ($row_data['other'] ? $row_data['docdesc'] . '-' . $row_data['other'] : $row_data['docdesc']) . '</td>
                            <td>' . $row_data['fp'] . '</td>
                            <td>' . $row_data['tp'] . '</td>
                            <td>' . $row_data['np'] . '</td>
                            <td>' . $against_case . '</td>
                            <td>
                                <span id="spshow_' . $sno . '" class="btn btn-primary btn-sm cl_hover">' . ($row_data['pdf_name'] == '' ? '-' : 'Show') . '</span>
                                <input type="hidden" name="hdpdf_name_' . $sno . '" id="hdpdf_name_' . $sno . '" value="' . $pdf_link . '" />
                            </td>
                        </tr>';
    
                $sno++;
                $total += $row_data['np'];
            }
    
            $html .= '<tr class="with_border">
                        <td colspan="5" align="right"><strong>Total No. of Pages</strong></td>
                        <td>' . $total . '</td>
                      </tr>';
        } else {
            $html .= '<tr><td colspan="8" align="center">No Records Found</td></tr>';
        }
        return json_encode($html);
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
