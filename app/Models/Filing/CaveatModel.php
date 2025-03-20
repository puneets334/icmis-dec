<?php

namespace App\Models\Filing;

use CodeIgniter\Model;

class CaveatModel extends Model
{

	public function __construct()
	{
		parent::__construct();
		$this->db = db_connect();
	}

	public function getAorDetails()
	{
		$builder = $this->db->table('master.bar');
		$builder->select('aor_code, bar_id, name');
		$builder->where('if_aor', 'Y');
		$builder->where('isdead', 'N');

		$query = $builder->get();

		if ($query->getNumRows() > 0) {
			return $query->getResultArray();
		} else {
			return [];
		}
	}

	public function checkIfRenew($caveat_no)
	{
		$builder = $this->db->table('caveat');
		$builder->select('*');
		$builder->where('caveat_no', $caveat_no);
		$builder->where('is_renew', 'Y');

		$query = $builder->get();

		if ($query->getNumRows() > 0) {
			return $query->getNumRows();
		} else {
			return [];
		}
	}


	public function getCaveatList($caveat_no)
	{
		$builder = $this->db->table('caveat');
		$builder->where('caveat_no', $caveat_no);
		$query = $builder->get();

		if ($query->getNumRows() > 0) {
			return $result = $query->getResultArray();
		} else {
			// Handle the error or no record found scenario
			log_message('error', 'No records found for caveat_no: ' . $caveat_no);
			return false; // Or handle the error accordingly
		}
	}


	public function getSubmaster($dairy_no)
	{

		$builder = $this->db->table('mul_category_caveat a');
		$builder->select('a.submaster_id, a.od_cat, b.subject_description, b.category_description, b.subject_sc_old, b.category_sc_old, b.sub_name1, b.sub_name4, b.sub_name2, b.sub_name3, a.flag, b.subcode2_hc, b.subcode1_hc, b.subcode3_hc');
		$builder->join('master.submaster b', 'a.submaster_id = b.id');
		$builder->where('a.caveat_no', $dairy_no);
		$builder->where('a.display', 'Y');
		$builder->where('b.display', 'Y');

		$query = $builder->get();
		// echo $this->db->getLastQuery();
		if ($query->getNumRows() > 0) {
			return $result = $query->getResultArray();
		} else {
			log_message('error', 'No records found for caveat_no: ' . $dairy_no);
			return false; // Or handle the error accordingly
		}
	}

	public function getSubMasterByFlag()
	{
		$builder = $this->db->table('master.submaster');

		$builder->select('id, subject_sc_old, category_sc_old, sub_name1, sub_name4, sub_name2, sub_name3, mapping_id, subcode2, subcode3, flag, subcode2_hc, subcode1_hc, subcode3_hc');

		$builder->where('display', 'Y');
		$builder->where('flag', 's');
		$builder->groupStart()
			->where('is_old IS NULL', null, false)
			->orWhere('is_old', 'Y')
			->groupEnd();

		$builder->orderBy('id');

		$query = $builder->get();

		if ($query->getNumRows() > 0) {
			return $result = $query->getResultArray();
			// Handle the result as needed
		} else {
			log_message('error', 'No records found for caveat_no: ' . $dairy_no);
			return false; // Or handle the error accordingly
		}
	}

	public function getSubMasterForMapping_id()
	{
		$builder = $this->db->table('master.submaster');

		$builder->select('id, subject_sc_old, category_sc_old, sub_name1, sub_name4, sub_name2, sub_name3, mapping_id, subcode2, subcode3, flag, subcode2_hc, subcode1_hc, subcode3_hc');

		$builder->where('display', 'Y');
		$builder->where('flag', 's');
		$builder->groupStart()
			->where('is_old IS NULL', null, false)
			->orWhere('is_old', 'Y')
			->groupEnd();

		$builder->orderBy('id');

		$query = $builder->get();

		if ($query->getNumRows() > 0) {
			return $result = $query->getResultArray();
			// Handle the result as needed
		} else {
			log_message('error', 'No records found for caveat_no: ' . $dairy_no);
			return false; // Or handle the error accordingly
		}
	}


	public function actMainCaveat($dairy_no)
	{
		$builder = $this->db->table('act_main_caveat a');
		$builder->select("a.id, a.act, STRING_AGG(b.section, ',') as section");
		$builder->join('master.act_section b', 'a.id = b.act_id', 'left');
		$builder->where('a.caveat_no', $dairy_no);
		$builder->where('a.display', 'Y');
		$builder->where('b.display', 'Y');
		$builder->groupBy('a.id');

		$query = $builder->get();

		if ($query->getNumRows() > 0) {
			return $result = $query->getResultArray();
		} else {
			log_message('error', 'No records found for caveat_no: ' . $dairy_no);
			return false; // Or handle the error accordingly
		}
	}

	public function getActMaster()
	{
		$builder = $this->db->table('master.act_master');
		$builder->select('*');
		$builder->where('display', 'Y');
		$builder->orderBy('id', 'ASC');

		$query = $builder->get();
		if ($query->getNumRows() > 0) {
			return $result = $query->getResultArray();
		} else {
			//log_message('error', 'No records found for caveat_no: ' . $dairy_no);
			return false; // Or handle the error accordingly
		}
	}

	public function getMasterFixedfor()
	{
		$builder = $this->db->table('master.master_fixedfor');
		$builder->select('id, fixed_for_desc');
		$builder->like('displayat', 'SCR');
		$builder->orderBy('id', 'ASC');

		$query = $builder->get();
		if ($query->getNumRows() > 0) {
			return $result = $query->getResultArray();
		} else {
			//log_message('error', 'No records found for caveat_no: ' . $dairy_no);
			return false; // Or handle the error accordingly
		}
	}

	public function getMasterBench()
	{
		$builder = $this->db->table('master.master_bench');
		$builder->select('bench_name, id');
		$builder->where('id >=', 1);
		$builder->where('id <=', 6);

		$query = $builder->get();
		if ($query->getNumRows() > 0) {
			return $result = $query->getResultArray();
		} else {
			//log_message('error', 'No records found for caveat_no: ' . $dairy_no);
			return false; // Or handle the error accordingly
		}
	}

	public function getCaseLaw($case_grp)
	{
		$builder = $this->db->table('master.caselaw');
		$builder->select('id, law');
		$builder->where('display', 'Y');
		$builder->where('nature', $case_grp);
		$builder->orderBy('law', 'ASC');

		$query = $builder->get();
		if ($query->getNumRows() > 0) {
			return $result = $query->getResultArray();
		} else {
			//log_message('error', 'No records found for caveat_no: ' . $dairy_no);
			return false; // Or handle the error accordingly
		}
	}

	public function getMasterCourtFee($casetype_id)
	{
		$c_date = date('Y-m-d');
		$builder = $this->db->table('master.m_court_fee');
		$builder->select('court_fee, flag, security_deposit');
		$builder->where('display', 'Y');
		$builder->where('casetype_id', $casetype_id);
		$builder->where('submaster_id', 0);
		$builder->where('case_law', '0');

		$builder->groupStart();  // Open parentheses for OR conditions
		$builder->where("('$c_date' BETWEEN from_date AND to_date)", null, false);
		$builder->orWhere("(from_date <= '$c_date' AND to_date IS NULL)", null, false);  // Use IS NULL instead of '0000-00-00'
		$builder->groupEnd();  // Close parentheses

		$query = $builder->get();

		if ($query->getNumRows() > 0) {
			return $result = $query->getRowArray();
		} else {
			//log_message('error', 'No records found for caveat_no: ' . $dairy_no);
			return false; // Or handle the error accordingly
		}
	}


	public function getparty($dairy_no)
	{
		$builder = $this->db->table('party');
		$builder->select('COUNT(*) as total_count');
		$builder->where('diary_no', $dairy_no);
		$builder->where('pflag', 'P');
		$builder->where('pet_res', 'P');

		$query = $builder->get();
		if ($query->getNumRows() > 0) {
			return $result = $query->getRowArray();
		} else {
			//log_message('error', 'No records found for caveat_no: ' . $dairy_no);
			return false; // Or handle the error accordingly
		}
	}

	public function getLowerct($dairy_no)
	{
		$builder = $this->db->table('lowerct');
		$builder->select('COUNT(lower_court_id) as total_count');
		$builder->where('diary_no', $dairy_no);
		$builder->where('lw_display', 'Y');
		$builder->where('is_order_challenged', 'Y');

		$query = $builder->get();
		if ($query->getNumRows() > 0) {
			return $result = $query->getRowArray();
		} else {
			//log_message('error', 'No records found for caveat_no: ' . $dairy_no);
			return false; // Or handle the error accordingly
		}
	}


	public function getSensitiveCases($dairy_no)
	{
		$builder = $this->db->table('sensitive_cases');
		$builder->select('reason');
		$builder->where('diary_no', $dairy_no);
		$builder->where('display', 'Y');

		$query = $builder->get();
		if ($query->getNumRows() > 0) {
			return $result = $query->getRowArray();
		} else {
			log_message('error', 'No records found for caveat_no: ' . $dairy_no);
			return false; // Or handle the error accordingly
		}
	}

	public function getMasterKeyword()
	{
		$builder = $this->db->table('master.ref_keyword');
		$builder->select('id, keyword_description');
		$builder->where('is_deleted', 'f');

		$query = $builder->get();
		if ($query->getNumRows() > 0) {
			return $result = $query->getResultArray();
		} else {
			//log_message('error', 'No records found for caveat_no: ' . $dairy_no);
			return false; // Or handle the error accordingly
		}
	}

	public function getEcKeyword($dairy_no)
	{
		$builder = $this->db->table('ec_keyword a');
		$builder->select('a.keyword_id, b.keyword_description');
		$builder->join('master.ref_keyword b', 'a.keyword_id = b.id');
		$builder->where('a.display', 'Y');
		$builder->where('a.diary_no', $dairy_no);
		$builder->where('b.is_deleted', 'f');

		$query = $builder->get();

		//echo $this->db->getLastQuery();
		//die;

		if ($query->getNumRows() > 0) {
			return $result = $query->getResultArray();
		} else {
			log_message('error', 'No records found for caveat_no: ' . $dairy_no);
			return false; // Or handle the error accordingly
		}
	}


	public function getMulCategoryByDiaryNo($diary_no)
	{

		$builder = $this->db->table('mul_category a');
		$builder->select('a.submaster_id, a.od_cat, b.subject_description, b.category_description, b.subject_sc_old, b.category_sc_old, b.sub_name1, b.sub_name4, b.sub_name2, b.sub_name3, b.flag, b.subcode2_hc, b.subcode1_hc, b.subcode3_hc, b.mapping_id');
		$builder->join('master.submaster b', 'a.submaster_id = b.id');
		$builder->where('a.diary_no', $diary_no);
		$builder->where('a.display', 'Y');
		$builder->where('b.display', 'Y');
		$builder->groupStart()
			->where('b.is_old', null)
			->orWhere('b.is_old', 'Y')
			->groupEnd();

		$query = $builder->get();

		if ($query->getNumRows() > 0) {
			return $result = $query->getResultArray();
			// Handle the result as needed
		} else {
			log_message('error', 'No records found for caveat_no: ' . $diary_no);
			return false; // Or handle the error accordingly
		}
	}



	public function getMulCategoriesBy($diary_no)
	{

		//$diary_no = $this->request->getVar('hd_diary_nos'); // Get the diary number from the request

		$builder = $this->db->table('mul_category a');

		// Join the submaster table
		$builder->join('master.submaster b', 'a.new_submaster_id = b.id');

		// Select the required columns
		$builder->select('new_submaster_id, od_cat, subject_description, category_description, subject_sc_old, category_sc_old,
			sub_name1, sub_name4, sub_name2, sub_name3, flag, subcode2_hc, subcode1_hc, subcode3_hc');

		// Add the conditions
		$builder->where('a.diary_no', $diary_no);
		$builder->where('a.display', 'Y');
		$builder->where('b.is_old', 'N');
		$builder->where('b.display', 'Y');

		// Execute the query
		$query = $builder->get();

		if ($query->getNumRows() > 0) {
			return $result = $query->getResultArray();
			// Handle the result as needed
		} else {
			log_message('error', 'No records found for caveat_no: ' . $diary_no);
			return false; // Or handle the error accordingly
		}
	}


	public function getNewCategorySubmaster()
	{
		$builder = $this->db->table('master.submaster');

		// Select the required columns
		$builder->select('id, subject_sc_old, category_sc_old, sub_name1, sub_name4, sub_name2, sub_name3,
			subcode2, subcode3, flag, subcode2_hc, subcode1_hc, subcode3_hc, mapping_id');

		// Add conditions
		$builder->where('display', 'Y');
		$builder->where('is_old', 'N');
		$builder->where('sub_name4 IS NOT NULL', null, false); // Ensure sub_name4 is not null
		$builder->where('sub_name4 !=', ''); // Ensure sub_name4 is not empty

		// Add order by clause
		$builder->orderBy('id');

		// Execute the query
		$query = $builder->get();

		if ($query->getNumRows() > 0) {
			return $result = $query->getResultArray();
			// Handle the result as needed
		} else {
			//log_message('error', 'No records found for caveat_no: ' . $dairy_no);
			return false; // Or handle the error accordingly
		}
	}

	public function getOtherCategoryRemarks($diary_no)
	{

		$builder = $this->db->table('other_category');

		// Select the 'remarks' column
		$builder->select('remarks');

		// Add conditions
		$builder->where('diary_no', $diary_no);
		$builder->where('display', 'Y');

		// Execute the query
		$query = $builder->get();

		// Fetch the result set as an array
		if ($query->getNumRows() > 0) {
			return $result = $query->getRowArray();
			// Handle the result as needed
		} else {
			log_message('error', 'No records found for caveat_no: ' . $diary_no);
			return false; // Or handle the error accordingly
		}
	}


	public function getMCourtFee($case_type_id, $ex_in_exp_3)
	{
		// Get the necessary variables from the request or session
		//$case_type_id = $this->request->getVar('lst_case');
		$submaster_id = $ex_in_exp_3;
		$c_date = date('Y-m-d'); // Ensure this is in the correct format

		$builder = $this->db->table('master.m_court_fee');

		// Select the 'flag' and 'id' columns
		$builder->select('flag, id');

		// Add conditions
		$builder->where('display', 'Y');
		$builder->where('casetype_id', $case_type_id);
		$builder->where('submaster_id', $submaster_id);
		$builder->where('case_law', '0');

		// Handle date conditions
		$builder->groupStart(); // Open parentheses for OR conditions
		$builder->where("('$c_date' BETWEEN from_date AND COALESCE(to_date, '$c_date'))", null, false);
		$builder->orWhere("from_date <= '$c_date' AND to_date IS NULL", null, false);
		$builder->groupEnd(); // Close parentheses

		// Execute the query
		$query = $builder->get();

		// Fetch the result set as an array
		if ($query->getNumRows() > 0) {
			return $result = $query->getRowArray();
			// Handle the result as needed
		} else {
			log_message('error', 'No records found for caveat_no: ' . $case_type_id);
			return false; // Or handle the error accordingly
		}
	}


	public function getLowerCTBy7Count($dairy_no)
	{
		$builder = $this->db->table('lowerct a');
		$builder->select('COUNT(*) as count');
		$builder->join('transfer_to_details b', 'a.lower_court_id = b.lowerct_id');
		$builder->where('a.diary_no', $dairy_no);
		$builder->where('a.lw_display', 'Y');
		$builder->where('b.display', 'Y');
		$builder->where('b.transfer_state !=', 0);

		$query = $builder->get();
		// Fetch the result set as an array
		if ($query->getNumRows() > 0) {
			$result = $query->getRowArray();
			return $count = $result['count'];
		} else {
			log_message('error', 'No records found for caveat_no: ' . $dairy_no);
			return false; // Or handle the error accordingly
		}
	}

	public function getLowerCTCount($dairy_no)
	{
		$builder = $this->db->table('lowerct');
		$builder->select('COUNT(lower_court_id) as count');
		$builder->where('diary_no', $dairy_no);
		$builder->where('lw_display', 'Y');
		$builder->where('is_order_challenged', 'Y');

		$query = $builder->get();
		// Fetch the result set as an array
		if ($query->getNumRows() > 0) {
			$result = $query->getRowArray();
			return $count = $result['count'];
			// Handle the result as needed
		} else {
			log_message('error', 'No records found for caveat_no: ' . $dairy_no);
			return false; // Or handle the error accordingly
		}
	}


	public function getCourtFeeCat($lst_case, $ex_in_exp)
	{
		$c_date = date('Y-m-d');  // Assuming $c_date is assigned here
		$builder = $this->db->table('m_court_fee');
		$builder->select('court_fee, flag, security_deposit');
		$builder->where('display', 'Y');
		$builder->where('casetype_id', $lst_case);
		$builder->where('submaster_id', $ex_in_exp[3]);
		$builder->where('case_law', '0');

		// Apply the date conditions
		$builder->groupStart()
			->where("$c_date BETWEEN from_date AND to_date", null, false)
			->orGroupStart()
			->where('from_date <=', $c_date)
			->where('to_date IS NULL', null, false)  // PostgreSQL doesn't accept '0000-00-00'
			->groupEnd()
			->groupEnd();

		$query = $builder->get();
		// Fetch the result set as an array
		if ($query->getNumRows() > 0) {
			return $result = $query->getRowArray();
			// Handle the result as needed
		} else {
			log_message('error', 'No records found for case id: ' . $lst_case);
			return false; // Or handle the error accordingly
		}
	}

	public function getCourtFeeCat1($ex_in_exp)
	{
		$c_date = date('Y-m-d');  // Assuming $c_date is assigned here

		$builder = $this->db->table('m_court_fee');
		$builder->select('court_fee, flag, security_deposit');
		$builder->where('display', 'Y');
		$builder->where('casetype_id', '0'); // casetype_id is hardcoded as '0'
		$builder->where('submaster_id', $ex_in_exp[3]);
		$builder->where('case_law', '0');

		// Apply the date conditions
		$builder->groupStart()
			->where("$c_date BETWEEN from_date AND to_date", null, false)
			->orGroupStart()
			->where('from_date <=', $c_date)
			->where('to_date IS NULL', null, false)  // Handle cases where to_date is NULL
			->groupEnd()
			->groupEnd();

		$query = $builder->get();
		// Fetch the result set as an array
		if ($query->getNumRows() > 0) {
			return $result = $query->getRowArray();
			// Handle the result as needed
		} else {
			//log_message('error', 'No records found for case id: ' . $lst_case);
			return false; // Or handle the error accordingly
		}
	}


	public function showSections($diary_no, $d_yr, $act)
	{
		$builder = $this->db->table('act_main');
		$builder->select('section');
		$builder->where('diary_no', $diary_no);
		//$builder->where('diary_year', $d_yr);
		$builder->where('act', $act);
		$builder->where('display', 'Y');

		$query = $builder->get();

		if ($query->getNumRows() > 0) {
			return $result = $query->getResultArray();
			// Handle the result as needed
		} else {
			log_message('error', 'No records found for diary_no: ' . $diary_no);
			return false; // Or handle the error accordingly
		}
	}

	public function searchKeyword($txt_src_key)
	{
		// Build the query
		$builder = $this->db->table('ref_keyword');
		$builder->select('id, keyword_description');
		$builder->where('is_deleted', 'f');
		$builder->like('LOWER(keyword_description)', strtolower($txt_src_key)); // Use the like() method for LIKE condition

		// Execute the query
		$query = $builder->get();
		if ($query->getNumRows() > 0) {
			return $query->getResultArray(); // Return the result as an array
		} else {
			log_message('error', 'No records found');
			return false; // Handle the case when no records are found
		}
	}

	public function getCaveatPartyDetails($dno)
	{

		// Build the query
		$builder = $this->db->table('caveat_party p');
		$builder->select('p.partyname, p.sr_no, m.pet_name, m.res_name, m.pno, m.rno')
			->join('caveat m', 'p.caveat_no = m.caveat_no')
			->where('p.caveat_no', $dno)
			->where('p.pflag', 'P');

		// Execute the query
		$query = $builder->get();
		// Return results as an array or false if no rows found
		if ($query->getNumRows() > 0) {
			return $query->getRowArray();
		} else {
			// Log error or handle no data found case
			log_message('error', 'No records found for caveat_no: ' . $dno);
			return false;
		}
	}

	public function getCaveatAdvocateDetails($dno, $pet_res)
	{
		// Ensure $dno is an integer and $pet_res is sanitized
		$dno = intval($dno);
		$pet_res = $this->db->escapeString($pet_res);

		// Raw SQL query with UNION using parameter binding
		$sql = "
        SELECT a.sr_no, a.partyname, b.adv, b.advocate_id, b.adv_type, c.name ,b.pet_res_no,c.aor_code,c.mobile,c.email,b.stateadv
        FROM caveat_party a 
        RIGHT JOIN caveat_advocate b ON a.caveat_no = b.caveat_no 
            AND a.sr_no = b.pet_res_no 
            AND a.pet_res = b.pet_res 
            AND b.display = 'Y'
        LEFT JOIN master.bar c ON b.advocate_id = c.bar_id
        WHERE a.caveat_no = ? 
            AND a.pet_res = ? 
            AND a.pflag = 'P'
        
        UNION
        
        SELECT 0 AS sr_no, '' AS partyname, b.adv, b.advocate_id, b.adv_type, c.name,b.pet_res_no,c.aor_code,c.mobile,c.email,b.stateadv
        FROM caveat_advocate b
        LEFT JOIN master.bar c ON b.advocate_id = c.bar_id
        WHERE b.caveat_no = ? 
            AND b.pet_res = ? 
            AND b.pet_res_no = 0 
            AND b.display = 'Y'
        
        -- Reference columns directly in the ORDER BY clause
        ORDER BY adv_type DESC, sr_no
    ";

		// Execute the raw SQL query with bound parameters
		$query = $this->db->query($sql, [$dno, $pet_res, $dno, $pet_res]);

		// Check if the query returns any rows
		if ($query->getNumRows() > 0) {
			return $query->getResultArray();  // Return the result as an array
		} else {
			// Log the error or handle the case where no data is found
			log_message('error', 'No records found for caveat_no: ' . $dno);
			return false;
		}
	}


	public function getCaveatDetails($dno)
	{
		// SQL query for PostgreSQL
		$sql = "
        SELECT string_agg(q, ',') AS q, c_status 
        FROM (
            SELECT CONCAT(COUNT(a.sr_no), '-', a.pet_res) AS q, c_status 
            FROM caveat_party a 
            LEFT JOIN caveat b ON a.caveat_no = b.caveat_no AND pflag = 'P' 
            WHERE a.caveat_no = ?
            GROUP BY a.pet_res, c_status 
            ORDER BY 
                CASE 
                    WHEN a.pet_res = 'P' THEN 0 
                    WHEN a.pet_res = 'R' THEN 1 
                    ELSE 2 
                END
        ) a 
        GROUP BY c_status;
    ";

		// Execute the query using CodeIgniter's query builder
		$query = $this->db->query($sql, [$dno]);

		// Fetch the result
		if ($query->getNumRows() > 0) {
			return $query->getRowArray();  // Return the result as an associative array
		} else {
			// Handle the case where no data is found
			log_message('error', 'No records found for caveat_no: ' . $dno);
			return false;
		}
	}


	public function getCaseType()
	{
		// Initialize the query builder
		$builder = $this->db->table('master.casetype');

		// Select the necessary columns
		$builder->select('casecode, skey, casename, short_description');

		// Apply the conditions
		$builder->where('display', 'Y');
		$builder->where('casecode !=', 9999);
		$builder->whereNotIn('casecode', [9999, 15, 16]);

		// Order the results
		$builder->orderBy('casecode');
		$builder->orderBy('short_description');

		// Execute the query
		$query = $builder->get();

		// Check if any records were found and fetch them
		if ($query->getNumRows() > 0) {
			return $nature = $query->getResultArray();
		} else {
			// Handle the error or log the message
			log_message('error', 'No records found');
		}
	}


	public function getCaveatReportData($dateFrom, $dateTo, $condition)
	{
		$query = "SELECT 
				substring(caveat_no::text from 1 for length(caveat_no::text) - 4) AS caveat_no1,
				right(caveat_no::text, 4) as caveat_year,
				to_char(diary_no_rec_date, 'YYYY-MM-DD') as caveat_date,
				CASE 
					WHEN fil_no IS NULL THEN '<span style=color:red>Not Registered</span>'
					ELSE fil_no
				END as fil_no,
				pet_name,
				res_name,
				b.name as pet_adv_id,
				b1.name as res_adv_id,
				c_status,
				u.name as diary_user_id,
				sis.Name as ref_agency_state_id,
				rac.agency_name as ref_agency_code_id,
				court_fee,
				total_court_fee,
				case_status_id,
				caveat_no as c_no,
				current_date - diary_no_rec_date as no_of_days
			FROM caveat m
			LEFT JOIN master.bar b ON m.pet_adv_id = b.bar_id
			LEFT JOIN master.bar b1 ON m.res_adv_id = b1.bar_id
			LEFT JOIN master.users u ON m.diary_user_id = u.usercode
			LEFT JOIN master.state sis ON m.ref_agency_state_id = sis.id_no
			LEFT JOIN master.ref_agency_code rac ON m.ref_agency_code_id = rac.id
			WHERE diary_no_rec_date BETWEEN '$dateFrom' AND '$dateTo'::date + INTERVAL '1 day'
			" . $condition . "
			ORDER BY caveat_no, caveat_year";


		$query = $this->db->query($query);
		$result = $query->getResultArray();
		return $result;
		//echo $this->db->getLastQuery();
		// Fetch the result
		// if ($query->getNumRows() > 0) {
		// 	return $result = $query->getResultArray();
		// } else {
		// 	log_message('error', 'Query failed: No records found');
		// }
	}


	public function getCaveatLowerct($c_no)
	{
		$lower_ct = "SELECT string_agg(
                concat_ws('<br/>',
                    trim(Name),
                    CASE 
                        WHEN ct_code = 3 THEN (
                            SELECT Name 
                            FROM master.state s
                            WHERE s.id_no = a.l_dist
                            AND display = 'Y'
                        )
                        ELSE (
                            SELECT agency_name 
                            FROM master.ref_agency_code c
                            WHERE c.cmis_state_id = a.l_state 
                            AND c.id = a.l_dist
                            AND is_deleted = 'f'
                        )
                    END,
                    CASE 
                        WHEN ct_code = 4 THEN (
                            SELECT skey 
                            FROM master.casetype ct
                            WHERE ct.display = 'Y' 
                            AND ct.casecode = a.lct_casetype
                        )
                        ELSE (
                            SELECT type_sname 
                            FROM master.lc_hc_casetype d
                            WHERE d.lccasecode = a.lct_casetype 
                            AND d.display = 'Y'
                        )
                    END,
                    '-', lct_caseno, '-', lct_caseyear, '<br/>', 
                    to_char(lct_dec_dt, 'DD-MM-YYYY')
                ),
                '<br/><br/>'
            ) AS tot_data
            FROM caveat_lowerct a
            LEFT JOIN master.state b ON a.l_state = b.id_no 
            AND b.display = 'Y'
            WHERE a.caveat_no = '$c_no' 
            AND lw_display = 'Y' 
            GROUP BY caveat_no";
		//ORDER BY a.lower_court_id";
		$query = $this->db->query($lower_ct);
		$r_lowerct = $query->getRowArray();
		// pr($r_lowerct);
		return $r_lowerct;
	}


	public function getDiaryAdvocate($diary_tot)
	{

		$builder = $this->db->table('advocate a');
		$builder->select('name');
		$builder->join('master.bar b', 'a.advocate_id = b.bar_id');
		$builder->whereIn('diary_no', explode(',', $diary_tot));
		$builder->where('display', 'Y');
		$builder->where('pet_res', 'P');
		$builder->where('pet_res_no', '1');

		$query = $builder->get();

		if ($query->getNumRows() > 0) {
			return $query->getResultArray();  // Return the result as an associative array
		} else {
			// Handle the case where no data is found
			log_message('error', 'No records found for caveat_no: ' . $diary_tot);
			return false;
		}
	}

	/* function Caveat_List_Filed($date=null, $date1=null)
{
    $sql = "SELECT 
                (substr(CAST(c.caveat_no AS TEXT), 1, length(CAST(c.caveat_no AS TEXT)) - 4) || '/' || substr(CAST(c.caveat_no AS TEXT), -4)) AS \"Caveat_no\", 
                (c.pet_name || ' VS ' || c.res_name) AS \"Cause_title\", 
                (b.title || ' ' || b.name) AS \"Caveator\", 
                date(c.diary_no_rec_date) AS \"Caveat_Filing_date\", 
                extract(day from age(current_date, date(c.diary_no_rec_date))) AS \"No_of_days\"
            FROM 
                caveat c 
            JOIN 
                master.bar b 
            ON 
                c.pet_adv_id = b.bar_id
            WHERE 
                extract(day from age(current_date, date(c.diary_no_rec_date))) > 90
            AND 
                c.caveat_no NOT IN (SELECT caveat_no FROM caveat_diary_matching WHERE display = 'Y')
            AND 
                date(c.diary_no_rec_date) BETWEEN '$date' AND '$date1'
            ORDER BY 
                c.diary_no_rec_date;";

    $query = $this->db->query($sql);
    if ($query->num_rows() >= 1) {
        return $query->result_array();
    } else {
        return false;
    }
} */

	public function Caveat_List_Filed($date = null, $date1 = null)
	{

		// Building the query using Query Builder
		$builder = $this->db->table('caveat c');
		$builder->select([
			"substring(c.caveat_no::text from 1 for length(c.caveat_no::text) - 4) || '/' || substring(c.caveat_no::text from length(c.caveat_no::text) - 3) AS caveat_no",
			"c.pet_name || ' VS ' || c.res_name AS cause_title",
			"b.title || ' ' || b.name AS caveator",
			"DATE(c.diary_no_rec_date) AS caveat_filing_date",
			"CURRENT_DATE - DATE(c.diary_no_rec_date) AS no_of_days"
		]);
		$builder->join('master.bar b', 'c.pet_adv_id = b.bar_id', 'inner');
		//$builder->where('EXTRACT(DAY FROM age(CURRENT_DATE, c.diary_no_rec_date)) >', 90);
		$builder->where('CURRENT_DATE - DATE(c.diary_no_rec_date) >', 90);
		$builder->whereNotIn('c.caveat_no', function ($subQuery) {
			return $subQuery->select('caveat_no')
				->from('caveat_diary_matching')
				->where('display', 'Y');
		});
		$builder->where('DATE(c.diary_no_rec_date) >=', $date);
		$builder->where('DATE(c.diary_no_rec_date) <=', $date1);
		$builder->orderBy('c.diary_no_rec_date');
		
		$query = $builder->get();

		//echo $this->db->getLastQuery();
		if ($query->getNumRows() >= 1) {
			return $query->getResultArray();
		} else {
			return false;
		}
	}


	public function caveat_cat_search($search_by_field)
	{
		if($search_by_field != ""){
			 
			// Start building the query
			$builder = $this->db->table('master.submaster');
			$builder->select('id, subject_sc_old, category_sc_old, sub_name1, sub_name4, sub_name2, sub_name3, mapping_id, 
							subcode1,subcode2, subcode3, flag, subcode2_hc, subcode1_hc, subcode3_hc')
					->where('display', 'Y')
					//->where('is_old', 'Y')
					->groupStart()  // Open a bracket for OR conditions
					->like('category_sc_old', $search_by_field)
					->orLike('sub_name1', $search_by_field)
					->orLike('sub_name2', $search_by_field)
					->orLike('sub_name3', $search_by_field)
					->orLike('sub_name4', $search_by_field)
					->groupEnd()  // Close the bracket
					->orderBy('id');

			// Execute the query
			//echo $builder->getCompiledSelect();
			$query = $builder->get();	
			//pr($query->getResultArray());		 
			return $results = $query->getResultArray();

		 }else{
			 
				$builder = $this->db->table('master.submaster');
				$builder->select('id, subject_sc_old, category_sc_old, sub_name1, sub_name4, sub_name2, sub_name3, mapping_id, 
					subcode2, subcode3, flag, subcode2_hc, subcode1_hc, subcode3_hc')
					->where('display', 'Y')
					//->where('is_old', 'Y')
					->orderBy('id');
				$query = $builder->get();			 
				return $results = $query->getResultArray();

		}

	}


}
