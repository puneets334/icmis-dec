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
   
   
   public function Da_Wise(){
        $data['result_array'] = $this->ReportModel->getSections();
		return view('PendencyReports/Da_Wise_Report', $data);
   }
   
   public function Da_Wise_get(){
	    $request = \Config\Services::request();
		$section = $request->getPost('section');
        $data['result_array'] = $this->ReportModel->Da_Pendency($section);
		$data['section'] = $section;
		return view('PendencyReports/Da_Wise_get_get', $data);
   }   
   
    public function da_pen(){
		$request = \Config\Services::request();
        $usercode= $request->getGet('usercode');
        $data['result_array'] = $this->ReportModel->Da_Pendency_result($usercode);
		return view('PendencyReports/da_pen', $data);
    }
	
	public function not_listed(){
		$data['result_array'] = $this->ReportModel->getSections();
		return view('PendencyReports/not_listed', $data);
    }
	
	public function not_listed_get(){
		$request = \Config\Services::request();
		$section = $request->getPost('section');
        $data['result_array'] = $this->ReportModel->Not_Listed_Report($section);
		$data['section'] = $section;
		return view('PendencyReports/not_listed_get', $data);
	}
	
	public function CaseType_Count(){
		$data['result_array'] = $this->ReportModel->getSections();
		return view('PendencyReports/CaseType_Count', $data);
    }
	
	public function CaseType_Count_get(){
		$request = \Config\Services::request();
		$section = $request->getPost('section');
		$data['case_type_count'] = $this->ReportModel->CaseType_Count($section);
		$data['unReg_count'] = $this->ReportModel->UnRegCases_Count($section);
		$data['misc_reg_count'] = $this->ReportModel->Misc_Reg_Count($section);
		$data['section'] = $section;
		return view('PendencyReports/CaseType_Count_get', $data);
	}
	
	 public function CaseType_YearWise_Count(){
		$request = \Config\Services::request();
		$casetype = $request->getGet('casetype');
		$section = $request->getGet('sect');
        $data['case_type']= $casetype;
        $data['section']=$section;
        $data['YearWise'] =$this->ReportModel->CaseType_Yearwise_Count($section, $casetype);
		$data['agency_type']= $this->ReportModel->CaseType_StateWise_Count($section, $casetype);
		return view('PendencyReports/CaseType_YearWise_Count', $data);
    }
 
	
	
	
	
}