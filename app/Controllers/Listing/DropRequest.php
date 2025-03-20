<?php

namespace App\Controllers\Listing;
use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Filing\AdvocateModel;
use App\Models\Listing\CaseInfoModel;
use App\Models\Listing\DropRequestModel;
use App\Models\Common\Dropdown_list_model;
class DropRequest extends BaseController
{

    public $model;
    public $diary_no;
    public $CaseInfoModel;
    public  $DropRequestModel;
    public $Dropdown_list_model;

    function __construct()
    {
         $this->CaseInfoModel = new CaseInfoModel();
         $this->DropRequestModel = new DropRequestModel();
         $this->Dropdown_list_model= new Dropdown_list_model();
         
         
       if (empty(session()->get('filing_details')['diary_no'])) {
             $uri = current_url(true);
             //echo $uri.'<br/>';
             $getUrl = $uri->getSegment(1).'-'.$uri->getSegment(2);
             
             header('Location:'.base_url('Filing/Diary/search?page_url='.base64_encode($getUrl)));exit();
            exit();
        } else {
            $this->diary_no = session()->get('filing_details')['diary_no'];
        }
    }

    /**
     * To display drop request add menu page
     *
     * @return void
     */
    public function index()
    {
        $filing_details = session()->get('filing_details');
        $data['diary_number'] = $filing_details['diary_number'];
        $data['diary_year'] = $filing_details['diary_year'];
        $data['lastorder'] = $filing_details['lastorder'];
        $data['c_status'] = $filing_details['c_status'];
        $data['fil_no'] = !empty($filing_details['fil_no']) ? substr($filing_details['fil_no'], 3) : '' ;
        $data['fil_dt'] = !empty($filing_details['fil_dt']) ? date('Y', strtotime($filing_details['fil_dt'])) : '';
        $data['case_info'] = $this->DropRequestModel->get_case_type($filing_details['casetype_id']);
        $data['bench'] = $this->DropRequestModel->get_bench_name($filing_details['bench']);
        //pr($filing_details);
        $party_details = $this->DropRequestModel->get_party_details_by_diary_no($filing_details['diary_no']);
        $data['party_details'] = true;
        $data['pet_adv'] =  $data['res_adv'] = $pet_name = $res_name = '';
        foreach($party_details as $party){
            $temp_var = "";
            $temp_var = $party['partyname'];
            if(!empty($party['sonof']) && trim($party['sonof']) != '') {
                $temp_var.= $party['sonof'] . "/o " . $party['prfhname'];
            }
            if($party['deptname'] != "") {
                $temp_var.= "<br>Department : ".$party['deptname'];
            }
            $temp_var.= "<br>";
            if ($party['addr1'] == '')
                $temp_var.=$party['addr2'];
            else
                $temp_var.=$party['addr1'] . ', ' . $party['addr2'];

            if ($party['pet_res'] == 'P') {
                $res_name = $temp_var;
            } else {
                $pet_name = $temp_var;
            }
        }

        $data['pet_name'] = $pet_name;
        $data['res_name'] = $res_name;
        $data['mul_category'] = $this->DropRequestModel->get_multiple_category($filing_details['diary_number']);
        $data['act_section'] = $this->DropRequestModel->get_act_section($filing_details['diary_number']);
        $data['law'] = $this->DropRequestModel->get_provision_of_law($filing_details['actcode']);
        $tentative_date = $this->DropRequestModel->get_tentative_date($filing_details['diary_number']);
        $data['tentative_date'] = date('d-m-Y', strtotime($tentative_date));

        $data['hearing_date_list'] = $this->DropRequestModel->hearing_date_list($filing_details['diary_number']);
        $data['hearing_last_date_list'] = $this->DropRequestModel->hearing_last_date_list($filing_details['diary_number']);

        $data['get_interlocutary_app'] = $this->DropRequestModel->get_interlocutary_app($filing_details['diary_number']);
        $data['get_other_docs'] = $this->DropRequestModel->get_other_docs($filing_details['diary_number']);
        return  view('Listing/drop_request/get_report',$data);
    }

}
