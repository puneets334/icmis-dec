<?php

namespace App\Controllers\Reports\Judicial;
use App\Controllers\BaseController;
use App\Models\Reports\Judicial\ReportModel;
use App\Models\Common\Dropdown_list_model;
use App\Models\Filing\Model_diary;


class Report extends BaseController
{
    public $Dropdown_list_model;
    public $Model_diary;
    public $ReportModel;
    function __construct()
    {
         ini_set('memory_limit','51200M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
         $this->Dropdown_list_model= new Dropdown_list_model();
        $this->Model_diary= new Model_diary();
        $this->ReportModel= new ReportModel();
    }

    public function index(){
        return view('Reports/Judicial/report');
    }
    
    public function get_search_view(){
        $type = $_REQUEST['type'];
        $ReportModel = new ReportModel();
        if(!empty($type)){
            $type =  (int)$type;
             switch($type){
                case 1: //Elimination_list					 
                    $data['section'] = $this->Dropdown_list_model->getSections();
                    return view('Reports/Judicial/elimination_list', $data);
                    break;
                case 3: //Section List
                    $data['dates_list']= $ReportModel->field_sel_roster_dts();
                    if(empty($data['dates_list']))
                    {
                        $data['dates_list']= $ReportModel->field_sel_roster_dts_custom();
                    }
                    $data['section'] = $this->Dropdown_list_model->getSections();
                    return view('Reports/Judicial/section_list',$data);
                    break;
                case 4: //weekly_list
                    $data['section'] = $this->Dropdown_list_model->getSections();
                    return view('Reports/Judicial/weekly_list',$data);
                    break;
                case 5: //Sec List
                    $data['tentavive_date_lists'] = $this->Dropdown_list_model->tentative_dates_list();
                    //echo "<pre>";print_r($data['tentavive_date_lists']);die;
                    $data['section'] = $this->Dropdown_list_model->getSections();
                    return view('Reports/Judicial/sec_list',$data);
                    break;
                case 6: //Vacation Registrar List
                    $data['section'] = $this->Dropdown_list_model->getSections();
                    return view('Reports/Judicial/vacation_registrar_list',$data);
                    break;
                case 7: //daily_court_remarks_da
                     $data['section'] = $this->Dropdown_list_model->getSections();
                     $data['judges'] = $this->ReportModel->get_judges_list_current();
                     return view('Reports/Judicial/daily_court_remark',$data);
                     break;
                 case 8: //action_pending_da
                     return view('Reports/Judicial/action_pending_da');
                     break;
                 case 9: //Reports master
                     return view('Reports/court/appearance_search_view');
                     break;
                 case 10: //Work Done
                     return view('Reports/Judicial/workdone');
                     break;
                 case 11: //Reports master
                     return view('Reports/court/vernacular_judgments_report_search_view');
                     break;
                case 13: //aor_wise_matters
                        return view('Reports/Judicial/aor_wise_matters');
                        break;
                case 14: //aor_wise_matters
                        return view('Reports/Judicial/oruploded_status');
                        break;

                case 15: //Advocate List
                        return view('Reports/Judicial/advocate_list');
                        break;

               case 16: //ROP Varification
                        $data['section'] = $this->Dropdown_list_model->getSections();
                        $data['judges'] = $this->ReportModel->get_judges_list_current();
                        //print_r($data['judges']); exit;
                        return view('Reports/Judicial/rop_varification_list', $data);
                        break;

                default:

            }
        }

    }
     
    public function work_done(){
        $ReportModel = new ReportModel();
        $data = $this->request->getGet();
        $data['Work_done']= $ReportModel->getWork_done($data);
        return view('Reports/Judicial/get_work_done',$data);exit;

    }


    public function Aor_wise_matters(){
        $ReportModel = new ReportModel();
        $data = $this->request->getGet();
        $data['Aor_list']= $ReportModel->getAorwise_Matter($data);
        //$data['Aor_list']= $ReportModel->getAorwise_Matter($data);
        
        return view('Reports/Judicial/get_aor_wise_matters',$data);exit;
    }

    public function get_advocate_List(){
        $ReportModel = new ReportModel();
        $data = $this->request->getGet();
        $data['Advlist']= $ReportModel->getAdvocate_list($data);
        $data['Advlist_']= $ReportModel->get_advlist2($data);
        $data['Adv_list'] = array_merge($data['Advlist'], $data['Advlist_']);
        //print_r($data['Adv_list']); exit;
        return view('Reports/Judicial/get_advocate_list',$data);exit;
    }

    public function getORuploded_status()
    {
        $ReportModel = new ReportModel();
        $data['case_result']='';
        $data['app_name']='ORUploadStatus.';
        //$usercode = $this->request->getGet('usercode');
        $on_date=date('Y-m-d', strtotime($this->request->getGet('on_date')));
        $data['case_result']  = $ReportModel->getorUplodStatus($on_date);
        $data['on_date'] = $on_date;

        return view('Reports/Judicial/getORuploded_status',$data);

    }

    public function ROP_Varification(){
        $ReportModel = new ReportModel();
        $data = $this->request->getGet();

    }
	
	public function EliminationList()
	{
		$data['active_status'] = 'Elimination_list';
		return view('Reports/Judicial/report',$data);
	}
	

    public function Elimination_list(){
        $ReportModel = new ReportModel();
        $data = $this->request->getGet();
		$data['post_data'] = $_REQUEST;
        $data['Elimination_list']= $ReportModel->getElimination_list($data);
		//pr($data['$_POST']);
        return view('Reports/Judicial/get_content_elimination_list',$data);exit;
        //print_r($data); exit;
    }
	
	public function SectionWiseList()
	{
		$data['active_status'] = 'Section_Wise_list';
		return view('Reports/Judicial/report',$data);
	}

   public function Section_list(){
        $ReportModel = new ReportModel();
        $data = $this->request->getGet();
        $data['Section_list']= $ReportModel->getSection_list($data);
        return view('Reports/Judicial/get_content_section_list',$data);exit;
   }
   
   public function WeeklyWiseList()
	{
		$data['active_status'] = 'Weekly_Wise_list';
		return view('Reports/Judicial/report',$data);
	}

    public function Weekly_list(){
        $ReportModel = new ReportModel();
        $data = $this->request->getGet();
        //echo "<pre>";print_r($_GET);die;
        $data['Weekly_list']= $ReportModel->getWeekly_list($data);        
        return view('Reports/Judicial/get_content_weekly_list',$data);exit;
   }
   
    public function SecWiseList()
	{
		$data['active_status'] = 'Sec_list';
		return view('Reports/Judicial/report',$data);
	}

   public function sec_list(){
        $ReportModel = new ReportModel();
        $data = $this->request->getGet();
        $data['Sec_list']= $ReportModel->getSec_list($data);
        return view('Reports/Judicial/get_content_sec_list',$data);exit;
   }
   
   public function VacWiseList()
	{
		$data['active_status'] = 'Vac_list';
		return view('Reports/Judicial/report',$data);
	}

   public function vac_list(){
        $ReportModel = new ReportModel();
        $data = $this->request->getGet();
        $data['Vac_list']= $ReportModel->getVac_list($data);
        return view('Reports/Judicial/get_content_vac_list',$data);exit;
   } 

   public function daily_court_remark(){
        $ReportModel = new ReportModel();
        $data = $this->request->getGet();
        $data['Vac_list']= $ReportModel->getDailycourtRemark($data);
        return view('Reports/Judicial/get_content_DailycourtRemark_list',$data);exit;
   }

   function action_pending_report_da($empid){
    $data['sections'] = $this->ReportModel->getSec_list();
   $data['order_type'] = $this->ReportModel->order_type();
   $data['empid']=$empid;
   $data['desig']=$this->application_model->getDesignation($empid);
   $this->load->view('Copying/action_pending_da', $data);
}

public function da_rog()
    {
        $data['app_name']='DA Report';
        $result_array = $this->Reports_model->da_rog_report();
        $data['da_rog_result'] = $result_array;
        $this->load->view('Reports/da_rog_report',$data);

    }
    public function da_wise_report($emp_id){
        $data['app_name']='DA Wise Report';
        $result_array = $this->Reports_model->show_da_wise_report($emp_id);
        $data['da_result'] = $result_array;
        $this->load->view('Reports/da_wise_report',$data);
    }
	
	

}
