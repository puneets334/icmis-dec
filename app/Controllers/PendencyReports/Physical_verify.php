<?php

namespace App\Controllers\PendencyReports;
use App\Controllers\BaseController;
use App\Models\PendencyReports\ReportModel;

class Physical_verify extends BaseController {
	
    protected $ReportModel;
	
    function __construct(){
         $this->ReportModel= new ReportModel();
    }
	
	
	public function report_DA(){
		
		$data['report_DA'] = $this->ReportModel->report_DA();
		return view('PendencyReports/report_DA', $data);
	
	}
	
	function report_Section(){
		
		$data['report_Section'] = $this->ReportModel->report_Section();
        return view('PendencyReports/report_Section', $data);
	
	}
	
	
	public function sectionwise_PendingIA(){
		$data['result_array'] = $this->ReportModel->Section_Reg_Report();
		return view('PendencyReports/sectionwise_PendingIA', $data);
		
    }
	
	public function sectionwise_PendingIA_get(){
        $request = \Config\Services::request();
        $section = $request->getPost('sect');
		$data['section'] = $section;
		$data['result_array']= $this->ReportModel->get_section_pendingIA($section);
		return view('PendencyReports/sectionwise_PendingIA_get', $data);
    }
	
	public function Reg_J1_Report(){
		$data['result_array'] = $this->ReportModel->getSections();
		$data['getMainSubjectCategory'] = $this->ReportModel->getMainSubjectCategory();
		return view('PendencyReports/Reg_J1_Report', $data);
    }
	
	public function get_Sub_Subject_Category(){
		$request = \Config\Services::request();
        $Mcat = $request->getPost('Mcat');
		$data_array = $this->ReportModel->get_Sub_SubjectCategory($Mcat);
		print json_encode($data_array); 
        
	}
	
	public function Reg_J1_Report_get(){
		$request = \Config\Services::request();
		$sec_detail = explode('^', $request->getPost('section'));
        $section=$sec_detail[0];
        $sec_name= $sec_detail[1];
        $category = $request->getPost('categoryCode');
        $mcat = $request->getPost('McategoryCode');
        $data['result_array'] = $this->ReportModel->getReg_J1_Reports($category, $section,$mcat);
        $data['param']= array($sec_name, $category);
        return view('PendencyReports/Reg_J1_Report_get', $data);
   }
	
	
}