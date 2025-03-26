<?php

namespace App\Models\CashAccounts;

use CodeIgniter\Model;
use Config\Database;

class Fdr_Model extends Model
{

    public function get_caseType()
    {
        $builder = $this->db->table('master.casetype');
        $builder->select('casecode as id, casename as description')
            ->where('casecode !=', 9999)
            ->orderBy('casecode', 'ASC');

        $query = $builder->get();
        return $query->getResultArray();
    }


    public function get_Disposedcases()
{
    // Build the query using Query Builder
    $builder = $this->db->table('main m');
    $builder->distinct();
    $builder->select("diary_no, 
    CASE 
        WHEN (m.active_fil_no IS NULL OR m.active_fil_no::text = '0' OR m.active_fil_no = '') 
        THEN CONCAT(SUBSTR(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4), '/', 
                    SUBSTR(m.diary_no::text, -4)) 
        ELSE m.reg_no_display 
    END AS reg_No");
    $builder->join('fdr_records fd', 'm.diary_no = fd.ec_case_id');
    $builder->where('m.c_status', 'D');

    // Execute the query
    $query = $builder->get();

    // Check for results
    if ($query->getNumRows() >= 1) {
        return $query->getResultArray();
    } else {
        return false;
    }
}


    public function get_Banks()
    {
        $builder = $this->db->table('master.master_banks');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_fdStatus()
    {
        $builder = $this->db->table('master.master_fdstatus');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_section()
    {
        $builder = $this->db->table('master.usersection');
        $builder->select('id, section_name');
        $builder->where('isda', 'Y');
        $builder->orderBy('section_name', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

    function get_caseInfo($caseType, $caseNo, $caseYear, $ecCaseId)
    {
        /*$this->db->select('ec_case.id, ec_case.registration_number_display, ec_case.petitioner_name, ec_case.respondent_name, org_dealing_section_id, section_name');
        $this->db->from('ec_case');
        $this->db->join('v_cases', 'v_cases.id = ec_case.id');
        $this->db->join('org_section', 'org_section.id = org_dealing_section_id');
        if(is_null($ecCaseId)){
            $this->db->where('ec_case.registration_year', $caseYear);
            $this->db->where('ec_case.ref_case_type_id', $caseType);
            $this->db->where("$caseNo BETWEEN registration_number_from AND registration_number_to");
        }
        else
            $this->db->where('ec_case.id', $ecCaseId);*/
        if (is_null($ecCaseId)) {

            $builder = $this->db->table('main_casetype_history h')
                ->select([
                    'h.diary_no AS id',
                    'm.reg_no_display AS registration_number_display',
                    'm.pet_name AS petitioner_name',
                    'm.res_name AS respondent_name',
                    '(SELECT id FROM master.usersection WHERE section_name = tentative_section(h.diary_no)) AS org_dealing_section_id',
                    'tentative_section(h.diary_no) AS section_name'
                ])
                ->join('main m', 'm.diary_no = h.diary_no')
                ->where("CAST(NULLIF(split_part(h.new_registration_number, '-', 1), '') AS INTEGER)", $caseType)
                ->where("CAST('{$caseNo}' AS INTEGER) BETWEEN CAST(NULLIF(split_part(h.new_registration_number, '-', 2), '') AS INTEGER) AND CAST(NULLIF(split_part(h.new_registration_number, '-', 3), '') AS INTEGER)")
                ->where('h.new_registration_year', $caseYear)
                ->where('h.is_deleted', 'f');
                    
                // echo $builder->getCompiledSelect();die;

            $query = $builder->get();
        } else {
            // Raw SQL for the second query
            $sql = "SELECT diary_no AS id, reg_no_display AS registration_number_display, 
                               pet_name AS petitioner_name, res_name AS respondent_name, 
                               (SELECT id FROM master.usersection WHERE section_name = tentative_section(h.diary_no)) AS org_dealing_section_id, 
                               tentative_section(h.diary_no) AS section_name
                        FROM main 
                        WHERE diary_no = $ecCaseId";

            // echo $sql;

            $query = $this->db->query($sql);
        }

    //    echo $this->db->getLastQuery();die;

        // Check for results
        if ($query == false) {
            return false;
        } else {
            return $query->getResultArray();
           
        }
    }

    function form_insert($data, $table = 'fdr_records')
    {
        $builder = $this->db->table($table);
        $builder->insert($data);
        return $this->db->insertID();
    }

    function get_fdrRecords($ecCaseId)
    {
        $builder = $this->db->table('fdr_records');
        $builder->where(['ec_case_id' => $ecCaseId, 'is_deleted' => '0']);
        $builder->where(['is_deleted' => '0']);
        $builder->orderBy('maturity_date', 'DESC');
        // echo $builder->getCompiledSelect();die;
        $query = $builder->get();
        return $query->getResultArray();
    }

    function get_OneRecord($id)
    {
        $builder = $this->db->table('fdr_records');
        $query = $builder->getWhere(['id' => $id]);
        return $query->getResultArray();
    }

    function Bank_update($data, $id)
    {
        $builder = $this->db->table('master.master_banks');
        $builder->where('id', $id);
        $builder->update($data);
    }

    function form_update($data, $id)
    {
        $builder = $this->db->table('fdr_records');
        $builder->where('id', $id);
        $builder->update($data);
    }

    function search_result($data, $spl_condition)
    {
        $builder = $this->db->table('fdr_records');
        $builder->select('fdr_records.*'); 
        $builder->join('master.usersection', 'ref_section_code = usersection.id', 'left');
        $builder->join('master.master_banks', 'ref_bank_id = master_banks.id', 'left');
        $builder->join('master.master_fdstatus', 'fdr_records.ref_status_id = master_fdstatus.id', 'left');
        $builder->join('main', 'ec_case_id = main.diary_no', 'left');
        $builder->where($data);
        $builder->where('fdr_records.is_deleted', '0');         
        $builder->where($spl_condition);
        $builder->limit(100);        
        return $results = $builder->get()->getResultArray();                      
        // if (!$results) {
        //     die("Query failed: " . $this->db->error());
        // }
        // return $result = $query->getResultArray();
        
    }

    /************************ Bank Master ************************/
    public function bankMaster()
    {
        $builder = $this->db->table('master.master_banks m');
        $builder->select('m.id, m.bank_name, m.updated_datetime, m.Contact_Person, m.Email_ID, m.Ph_No, u.name');
        $builder->join('users u', 'm.updated_by = u.usercode');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function sectionwise_report($section_id)
    {
        $builder = $this->db->table('fdr_records fd');

        $builder->select('fd.case_number_display, 
                          fd.document_number, 
                          fd.account_number, 
                          fd.deposit_date, 
                          fds.status, 
                          fd.maturity_date, 
                          fd.amount, 
                          bank.bank_name, 
                          sec.id AS sec_id, 
                          sec.section_name, 
                          fd.petitioner_name, 
                          fd.respondent_name, 
                          fd.roi, 
                          tentative_da(fd.ec_case_id) AS da');

        $builder->join('master.master_banks bank', 'bank.id = fd.ref_bank_id', 'left');
        $builder->join('master.usersection sec', 'sec.id = fd.ref_section_code', 'left');
        $builder->join('master_fdstatus fds', 'fd.ref_status_id = fds.id', 'left');

        $builder->where('fd.is_deleted', '0');

        if ($section_id != 0) {
            $builder->where('sec.id', $section_id);
        }
        $builder->orderBy('fd.maturity_date');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function disposedReport($case_number)
    {
        $builder = $this->db->table('fdr_records fd');
        // Select the required fields
        $builder->select('fd.case_number_display, 
                      fd.document_number, 
                      fd.account_number, 
                      fd.deposit_date, 
                      fds.status, 
                      fd.maturity_date, 
                      fd.amount, 
                      bank.bank_name, 
                      sec.id AS sec_id, 
                      sec.section_name, 
                      fd.petitioner_name, 
                      fd.respondent_name, 
                      fd.roi, 
                      tentative_da(fd.ec_case_id::INTEGER) AS da');
        // Join with other tables
        $builder->join('master.master_banks bank', 'bank.id = fd.ref_bank_id', 'left');
        $builder->join('master.usersection sec', 'sec.id = fd.ref_section_code', 'left');
        $builder->join('master.master_fdstatus fds', 'fd.ref_status_id = fds.id', 'left');
        $builder->join('main m', 'fd.ec_case_id = m.diary_no', 'left');
        // Apply conditions
        $builder->where('fd.is_deleted', '0');
        $builder->whereIn('m.diary_no', function ($query) {
            $query->select('ec_case_id')->from('fdr_records');
        });
        $builder->where('m.diary_no', $case_number);
        // Execute the query
        $query = $builder->get();          
        // Return the results as an array
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function caseTypeReport($caseNo, $caseType, $caseYear)
    {
        // Prepare the query using Query Builder
        $builder = $this->db->table('fdr_records fd');

        // Select the required fields
        $builder->select('fd.case_number_display, 
                          fd.document_number, 
                          fd.account_number, 
                          fd.deposit_date, 
                          fds.status, 
                          fd.maturity_date, 
                          fd.amount, 
                          bank.bank_name, 
                          sec.id AS sec_id, 
                          sec.section_name, 
                          fd.petitioner_name, 
                          fd.respondent_name, 
                          fd.roi, 
                          tentative_da(fd.ec_case_id::INTEGER) AS da');

        // Join with other tables
        $builder->join('master.master_banks bank', 'bank.id = fd.ref_bank_id', 'left');
        $builder->join('master.usersection sec', 'sec.id = fd.ref_section_code', 'left');
        $builder->join('master.master_fdstatus fds', 'fd.ref_status_id = fds.id', 'left');
        $builder->join('main m', 'fd.ec_case_id = m.diary_no', 'left');

        // Apply conditions
        $builder->where('fd.is_deleted', '0');
        $builder->whereIn('m.diary_no', function ($subQuery) use ($caseNo, $caseType, $caseYear) {
            $subQuery->select('ec_case_id')
                ->from('fdr_records')
                ->whereIn('diary_no', function ($innerQuery) use ($caseNo, $caseType, $caseYear) {
                    $innerQuery->select('h.diary_no AS id')
                        ->from('main_casetype_history h')
                        ->join('main m', 'm.diary_no = h.diary_no', 'left')
                     //  ->where('SUBSTRING_INDEX(h.new_registration_number, '-', 1)', $caseType)
                        ->where("CAST($caseNo AS INTEGER) BETWEEN 
                                 (CAST(SPLIT_PART(SPLIT_PART(h.new_registration_number, '-', 2), '-', -1) AS INTEGER)) 
                                  AND (CAST(SPLIT_PART(h.new_registration_number, '-', -1) AS INTEGER))")
                        ->where('h.new_registration_year', $caseYear)
                        ->where('h.is_deleted', 'f');
                });
        });

        // Execute the query
        $query = $builder->get();        
       // if ($query === false) {
           // log_message('error', $this->db->getLastQuery());
         
      //  }
        // Return the results as an array
        if ($query === false) {
            return false;
            
        } else {
            return $query->getResultArray();
        }
    }


    public function TenureWiseReport($days, $month, $year)
    {
        
        $builder = $this->db->table('fdr_records fd');
        $builder->select('fd.case_number_display, 
                          fd.document_number, 
                          fd.account_number, 
                          fd.deposit_date, 
                          fds.status, 
                          fd.maturity_date, 
                          fd.amount, 
                          bank.bank_name, 
                          sec.id AS sec_id, 
                          sec.section_name, 
                          fd.petitioner_name, 
                          fd.respondent_name, 
                          fd.roi, 
                          tentative_da(fd.ec_case_id::INTEGER) AS da');

        // Join with other tables
        $builder->join('master.master_banks bank', 'bank.id = fd.ref_bank_id', 'left');
        $builder->join('master.usersection sec', 'sec.id = fd.ref_section_code', 'left');
        $builder->join('master.master_fdstatus fds', 'fd.ref_status_id = fds.id', 'left');

        // Apply conditions
        $builder->where('fd.is_deleted', '0');

        if ($days != 0) {
            $builder->where('fd.days', $days);
        }
        if ($month != 0) {
            $builder->where('fd.month', $month);
        }
        if ($year != 0) {
            $builder->where('fd.year', $year);
        }

        $query = $builder->get();
        echo $this->db->getLastQuery();die;
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
}
