<?php

namespace App\Controllers\ManagementReports\DA;

use App\Controllers\BaseController;
use App\Models\ManagementReport\CaseRemarksVerification;

class DA extends BaseController
{
    public $CaseRemarksVerification;
    public $Model_diary;

    function __construct()
    {
        set_time_limit(10000000000);
        ini_set('memory_limit', '-1');
        $this->CaseRemarksVerification = new CaseRemarksVerification();
    }

    public function case_remarks_verification()
    {
        return view('ManagementReport/DA/case_remarks_verification_view');
    }

    public function verification_get()
    {
        $ucode = $_SESSION['login']['usercode'];
        $usertype = $_SESSION['login']['usertype'];
        $section1 = $_SESSION['login']['section'];
        $data['if_bo'] = false;
        $mdacode = "";
        $from_dt = date('Y-m-d', strtotime($this->request->getPost('from_dt')));
        $to_dt = date('Y-m-d', strtotime($this->request->getPost('to_dt')));
        $verify_status = $this->request->getPost('verify_status');
        $data['model'] = $this->CaseRemarksVerification;
        $data['data'] =  $data['model']->verification_get_data($usertype, $ucode, $mdacode, $verify_status, $to_dt, $from_dt, $data['if_bo']);
        return view('ManagementReport/DA/verification_get_data_view', $data);
    }

    public function response_case_remarks_verification()
    {

        $ucode = $_SESSION['login']['usercode'];
        $dno = $this->request->getPost('dno');
        $cl_date = $this->request->getPost('cl_date');
        $rremark = $this->request->getPost('rremark');
        $rejection_remark = $this->request->getPost('rejection_remark');

        $data['model'] = $this->CaseRemarksVerification;
        $data['data'] =  $data['model']->response_case_remarks_verification_store($ucode, $dno, $cl_date, $rremark, $rejection_remark);
        echo $data['data'];
    }

    public function CaseVerification()
    {
        $data['usertype'] = $_SESSION['login']['usertype'];
        $data['model'] = $this->CaseRemarksVerification;
        $data['empid'] = $_SESSION['login']['empid'];
        return view('ManagementReport/DA/workdone_verify_view', $data);
    }

    public function workdone_verify_get()
    {
        $section = '';
        $chk_user_sec_map = 1;
        $date = $this->request->getPost('date');
        $data['usertype'] = $_SESSION['login']['usertype'];
        $data['empid'] = $_SESSION['login']['empid'];
        $data['model'] = $this->CaseRemarksVerification;
        $data['data'] =  $data['model']->workdone_verify_get_data($data['usertype'], $section, $chk_user_sec_map, $date, $data['empid']);
        return view('ManagementReport/DA/workdone_verify_get_view', $data);
    }

    public function workdone_verify_get_full()
    {
		$data['type'] = $this->request->getPost('type');
        $data['flag'] = $this->request->getPost('flag');
        $data['date'] = $this->request->getPost('date');
        $data['name'] = $this->request->getPost('name');
        $data['id'] =   $this->request->getPost('id');
		$data['model'] = $this->CaseRemarksVerification;
		$data['usertype'] = $_SESSION['login']['usertype'];
        $data['result_array'] =  $data['model']->workdone_verify_get_full($data['type'], $data['flag'], $data['date'], $data['name'], $data['id'], $data['usertype']);
        $data['sql_get_oc'] =  $data['model']->sql_get_oc($data['id']);
		return view('ManagementReport/DA/workdone_verify_get_popup_view', $data);   
   }

   public function workdone_verify_response(){
	    $usertype = $_SESSION['login']['usertype'];
		$ucode= $_SESSION['login']['empid'];
		$str_explo = explode("_", $this->request->getPost('dno'));
		$dno = $str_explo[0];
		$board_type = $str_explo[1];
		$mainhead = $str_explo[2];
		$next_dt = $str_explo[3];
		$data['model'] = $this->CaseRemarksVerification;
	    echo $data['model']->workdone_verify_response_status_update($ucode,$dno,$board_type,$mainhead,$next_dt,$usertype);
  }

    public function workdone_verify_from()
    {
        $data['ucode'] = $_SESSION['login']['usercode'];
        $data['usertype'] = $_SESSION['login']['usertype'];
        $data['model'] = $this->CaseRemarksVerification;
        $data['empid'] = $_SESSION['login']['empid'];
        return view('ManagementReport/DA/workdone_verify_from', $data);
    }

    public function workdone_verify_get_from()
    {
        $section = '';
        $chk_user_sec_map = 1;
        $data['usertype'] = $_SESSION['login']['usertype'];
        $data['empid'] = $_SESSION['login']['empid'];
        $data['date'] = $this->request->getPost('date');
        $data['date2'] = $this->request->getPost('date2');
        $data['model'] = $this->CaseRemarksVerification;
        $data['data'] =  $data['model']->workdone_verify_get_from_data($data['empid'], $data['date'], $data['date2'], $data['model'], $chk_user_sec_map, $section, $data['usertype']);

        return view('ManagementReport/DA/workdone_verify_get_from_data', $data);
    }

    public function case_listed_Advance_Daily_dawise()
    {
        $data['case_result'] = [];
        $data['app_name'] = 'Advance Daily DaWise';
        $data['usercode'] = $_SESSION['login']['usercode'];

        if ($_POST) {
            $usercode = $_POST['usercode'];
            $data['case_result'] = $this->CaseRemarksVerification->case_listed_Advance_Daily_dawise($data['usercode']);
        }

        return view('ManagementReport/DA/cases_listed_Advance_Daily_dawise', $data);
    }

    public function get_loosedoc_verify_Nverify()
    {
        $data['case_result'] = [];
        $data['usercode'] = $_SESSION['login']['usercode'];

        if ($_POST) {
            $usercode = $this->request->getPost('usercode');
            $from_date = date('Y-m-d', strtotime($this->request->getPost('from_date')));
            $to_date = date('Y-m-d', strtotime($this->request->getPost('to_date')));
            $data['case_result'] = $this->CaseRemarksVerification->loosedoc_verify_not_verify($from_date, $to_date, $data['usercode']);
        }
        return view('ManagementReport/DA/loosedoc_userwise', $data);
    }

    public function pre_notice()
    {
        $data['get_section_list'] = $this->CaseRemarksVerification->get_section_list();
        return view('ManagementReport/DA/pre_notice_view', $data);
    }

    public function get_pre_notice()
    {

        $board_type = $this->request->getPost('board_type');
        $sec_id = $this->request->getPost('sec_id');
        $rnr = $this->request->getPost('rnr');
        $coram_having = $this->request->getPost('coram_having');
        $pre_after = $this->request->getPost('pre_after');

        $sec_list_dt = date('d-m-Y H:i:s');
        $data['h3_head'] = "Cases as on " . $sec_list_dt . " (To be list before court)";
        $ucode = $_SESSION['login']['usercode'];
        $usertype = $_SESSION['login']['usertype'];
        $data['model'] =  new CaseRemarksVerification();
        $data['get_pre_notice_data'] = $this->CaseRemarksVerification->get_pre_notice_data($ucode, $usertype, $coram_having, $board_type, $sec_id, $rnr, $pre_after, $data['h3_head']);
        return view('ManagementReport/DA/get_pre_notice_view', $data);
    }

    public function da_wise_rgo_rpt()
    {
        $data['data'] = $this->CaseRemarksVerification->get_section_list();
        return view('ManagementReport/DA/da_wise_rgo_rpt_view', $data);
    }

    public function get_da_wise_rgo()
    {

        $section_name = $this->request->getPost('section_name');
        $condition = "";
        if ($section_name != "ALL") {
            $condition = " and b.section_name='" . $section_name . "'";
        }
        $data['data'] = $this->CaseRemarksVerification->get_da_wise_rgo_data($condition,$section_name);
        return view('ManagementReport/DA/get_da_wise_rgo_view', $data);
    }
}
