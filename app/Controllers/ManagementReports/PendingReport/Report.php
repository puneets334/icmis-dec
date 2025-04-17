<?php

namespace App\Controllers\ManagementReports\PendingReport;

use App\Controllers\BaseController;
use App\Models\ManagementReport\CaseRemarksVerification;
use App\Models\ManagementReport\PendencyModel;

class Report extends BaseController
{
    public $CaseRemarksVerification;
    public $Model_diary;
    public $PendencyModel;

    function __construct()
    {
        set_time_limit(10000000000);
        ini_set('memory_limit', '-1');
        $this->CaseRemarksVerification = new CaseRemarksVerification();
        $this->PendencyModel = new PendencyModel();
    }

    public function imp_ias_pending()
    {
        $data['get_section_list'] = $this->CaseRemarksVerification->get_section_list();
        return view('ManagementReport/Pending/imp_ias_pending_view', $data);
    }

    public function imp_ias_pending_get()
    {
        $ucode = $_SESSION['login']['usercode'];
        $section1 = $_SESSION['login']['section'];
        $sec_id = $this->request->getPost('sec_id');
        $ten_sect = '';
        $data['data'] = $this->CaseRemarksVerification->imp_ias_pending_get_data($ten_sect, $section1, $sec_id);
        return view('ManagementReport/Pending/imp_ias_pending_get_view', $data);
    }

    public function inperson()
    {
        $data['get_section_list'] = $this->CaseRemarksVerification->get_section_list();
        return view('ManagementReport/Pending/inperson_view', $data);
    }

    public function get_inperson()
    {
        $sec_id = $this->request->getPost('sec_id');
        $board_type = $this->request->getPost('board_type');
        $mainhead = $this->request->getPost('mainhead');
        $ucode = $_SESSION['login']['usercode'];
        $usertype = $_SESSION['login']['usertype'];
        $data['h3_head'] = "IN-PERSON CASES";
        $data['model'] = $this->CaseRemarksVerification;
        $data['data'] = $this->CaseRemarksVerification->get_inperson($mainhead, $ucode, $usertype, $sec_id, $board_type);

        return view('ManagementReport/Pending/get_inperson_table', $data);
    }

    public function pending_not_ready_conn()
    {
        return view('ManagementReport/Pending/pending_not_ready_conn_view');
    }

    public function pending_not_ready_process()
    {
        $data['res'] = $this->CaseRemarksVerification->pending_not_ready_process_data();
        return view('ManagementReport/Pending/pending_not_ready_process_view', $data);
    }

    public function category()
    {
        $ucode = $_SESSION['login']['usercode'];
        $data['dtd'] = date("d-m-Y");
        if ($ucode == 1 || $ucode == 9785) {
            $data['file_list'] = "";
            $data['cntr'] = 0;
            $data['chk_slno'] = 0;
            $data['chk_pslno'] = 0;
            $data['temp_msg'] = "";
            return view('ManagementReport/Pending/pendency_report_category_view', $data);
        }
    }

    public function category_process()
    {

        $dt1 =  $this->request->getPost('dt1');
        $include_defects =  $this->request->getPost('include_defects');
        $data['include_details'] =  $this->request->getPost('include_details');
        $data['tdt1'] = date('d-m-Y', strtotime($dt1));
        $data['for_date'] = date('Y-m-d', strtotime($dt1));

        $data['res'] = $this->CaseRemarksVerification->category_process_data($include_defects,$data['include_details'],$data['for_date'],$data['tdt1'],$dt1);
        // pr($data['res']);
        return view('ManagementReport/Pending/category_process_table', $data); 
    }

    public function sectionwise_pendency(){
        $data =[];    
        return view('ManagementReport/Pending/section_wise_pendency_view', $data);
    }
    public function sectionwise_pendency_get()
    {
        $ucode = $_SESSION['login']['usercode'];
        $section1 = $_SESSION['login']['section'];
        //$sec_id = $this->request->getPost('sec_id');
        
        $data['sectionwise_pendency_arr'] = $this->PendencyModel->sectionwise_pendency_get_data();
        //print_r($data['sectionwise_pendency_arr']);die;
        // $data['data'] = [];
        return view('ManagementReport/Pending/sectionwise_pendency_get_view', $data);
    }

    public function section_pendency()
    {
        $ucode = $_SESSION['login']['usercode'];
        $section1 = $_SESSION['login']['section'];
        $result_array = $this->PendencyModel->da_rog_report($section1);        
        $data['da_rog_result'] = $result_array;
        return view('ManagementReport/Pending/section_pendency_view', $data);
        
    }
	
	public function cases(){
        $category=  $this->request->getGet('category');
		$dacode= $this->request->getGet('dacode');
        $da_rog_matters = $this->PendencyModel->da_rog_cases($category,$dacode);                
        $da_details = $this->PendencyModel->da_details($dacode);        
        $data['da_details']=$da_details;        
        $data['dacode']=$dacode;
        $data['da_cases'] = $da_rog_matters;
        $data['category']=$category;
        return view('ManagementReport/Pending/da_rog_cases', $data);        
    }
	

}
