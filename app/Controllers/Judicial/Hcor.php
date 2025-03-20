<?php

namespace App\Controllers\Judicial;

use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;
use App\Models\Judicial\HcorModel;
use App\Libraries\webservices\Efiling_webservices;
use App\Libraries\webservices\Highcourt_webservices;

class Hcor extends BaseController
{
	public $Dropdown_list_model;
	public $efiling_webservices;
	public $highcourt_webservices;
	public $HcorModel;

	function __construct()
	{
		$this->Dropdown_list_model = new Dropdown_list_model();
		$this->HcorModel = new HcorModel();
	}


	public function indexing_hc_dc_report_casewise_report()
	{
		if (isset($_SESSION['filing_details']['diary_no']) && !empty($_SESSION['filing_details']['diary_no'])) {
			$diary_no = $_SESSION['filing_details']['diary_no'];
			$data['d_no'] = substr($diary_no, 0, 4);
			$data['d_year'] = substr($diary_no, -4);
			$data['d_yr'] = $diary_no;
		} else {
			$diary_no = null; 
			$data[]= '';
		}

		return view('Judicial/OriginalRecords/HcOr/index-view-hcor', $data);
	}


	public function get_indexing_hc_dc_report_casewise()
	{
		$dairy_no = $_REQUEST['d_no'] . $_REQUEST['d_year'];
		$lct_caseno = $_REQUEST['lct_caseno'] ?? '';
		$lct_casetype = $_REQUEST['lct_casetype'] ?? '';
		$lct_caseyear = $_REQUEST['lct_caseyear'] ?? '';
		$dcmis_user_idd =  session()->get('login')['usercode'];

		$data['HcorModel'] = $this->HcorModel;

		$sub_users = '';

		$dairy_no = $_REQUEST['d_no'] . $_REQUEST['d_year'];
		$data['diary_no'] = $dairy_no;
		if ($dcmis_user_idd != 1) {

			$sql_state_dis = "Select b.id,b.cmis_state_id from lowercourt_data.mapp_uid_sciid a join master.ref_agency_code b on a.hcid::INTEGER=b.id  
				  where uid='$dcmis_user_idd' and display='Y' and is_deleted='f'";

			$query = $this->db->query($sql_state_dis);
			$row_state_dis = $query->getRowArray();
			$sub_users = "  and l_state='$row_state_dis[cmis_state_id]' and l_dist='$row_state_dis[id]'";
		}

		/****** Paging start ****/
		$condition = '';
		if ($dairy_no != '') {
			$condition = ' and a.diary_no=' . $dairy_no;
		} else
			$condition = " and type_sname='$lct_casetype' and lct_caseno='$lct_caseno' and lct_caseyear='$lct_caseyear' ";

		//Check matter alloted to DA start
		$sql_ck = "SELECT 
                    COALESCE(m.dacode, '') AS dacode, 
                    COALESCE(u.name, '') AS username, 
                    u.empid 
                FROM 
                    main m 
                LEFT JOIN 
                    master.users u ON m.dacode = u.usercode 
                WHERE 
                    m.diary_no = :diary_no";

		$row_lp123 = $this->HcorModel->getDiaryInfo($dairy_no);

		if (!empty($row_lp123)) {

			if ($row_lp123["username"] == "" and ($row_lp123["dacode"] == "" or $row_lp123["dacode"] == 0))
				$output1 = "0|#|NO DA INFORMATION AVAILABLE FOR THIS CASE|#|" . $row_lp123["empid"];
			else if ($row_lp123["username"] == "" and ($row_lp123["dacode"] != $dcmis_user_idd))
				$output1 = "0|#|VERIFICATION IN THIS CASE CAN BE DONE ONLY BY DA EMP ID : " . $row_lp123["empid"] . " [DA NAME NOT AVAILABLE]|#|" . $row_lp123["dacode"];
			else if ($row_lp123["dacode"] != $dcmis_user_idd)
				$output1 = "0|#|VERIFICATION IN THIS CASE CAN BE DONE ONLY BY DA : " . $row_lp123["username"] . " [EMP ID : " . $row_lp123["empid"] . "]|#|" . $row_lp123["dacode"];
			else
				$output1 = "1|#|RIGHT DA|#|" . $row_lp123["dacode"];
		}
		$users_to_ignore = array();

		$sql_userV = is_data_from_table('master.users', "(section='63' OR section='37' OR usercode=1) and display='Y' and attend='P'", 'usercode', $row = 'A');

		foreach ($sql_userV as $row) {
			$users_to_ignore[] = $row['usercode'];
		}
		$result_da = explode("|#|", $output1);
		$rmtable = "";
		$data['users_to_ignore'] = $users_to_ignore;
		$data['result_da_status'] = 0;
		if ($result_da[0] > 0 or (in_array($dcmis_user_idd, $users_to_ignore))) {
			$data['res_sq_count'] = $this->HcorModel->getDiaryCount($condition);
			$data['result_da_status'] = 1;
		}
		$data['result_da'] = $result_da;
		$data['condition'] = $condition;
		$data['_REQUEST'] = $_REQUEST;

		return view('Judicial/Hocr/get_indexing_hc_dc_report_casewise', $data);
	}
}
