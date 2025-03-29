<?php

namespace App\Controllers\Reports\Copying;
use App\Controllers\BaseController;
use App\Models\Reports\Copying\ReportModel;
use App\Models\Common\Dropdown_list_model;



class Report extends BaseController
{
    public $Dropdown_list_model;
    public $ReportModel;
    function __construct()
    {
         ini_set('memory_limit','51200M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
         $this->Dropdown_list_model= new Dropdown_list_model();

        $this->ReportModel= new ReportModel();
    }
    public function index(){
        return view('Reports/copying/report');
    }
    public function get_search_view(){

        $sessionData = $this->session->get();
        $ucode = $sessionData['login']['usercode'];
        $type = $_REQUEST['type'];
        if(!empty($type)){
            $type =  (int)$type;
             switch($type){
                case 1: //AOR Signature Report
                    $data['aor_signature']= $this->ReportModel->getAORsignature();
                    return view('Reports/copying/AOR_signature_search_view',$data);
                    break;
                case 2: //Consumed Barcode
                    return view('Reports/copying/consumed_barcode_search_view');
                    break;
                case 3: //Copying Request
                    $data['empid']=1;
                   // $data['desig']=$this->Dropdown_list_model->getDesignation(1);
                  //  $data['order_type']=get_from_table_json('master.ref_order_type');
                    $data['order_type'] = is_data_from_table('master.ref_order_type');

                     //   $query = $this->db->where('display', 'Y')->where('isda', 'Y')->or_where('id','61')->order_by('isda desc, id ASC')->get('usersection');

              //      $data['sections']=is_data_from_table('master.usersection',['usercode'=>session()->get('login')['usercode']],'usertype,section','R');

                    $query = $this->db->table('master.usersection');
                    $query->select('*');
                    $query->where('display', 'Y');
                    $query->where('isda', 'Y');
                    $query->orwhere('id', '61');
                    $query->orderBy('isda desc, id ASC');
                    $result = $query->get();
                    if ($result->getNumRows() >= 1) {
                        $data['usersection'] = $result->getResultArray();
                    }

                    $data['empid']=1;
                    return view('Reports/copying/copying_request_search_view',$data);
                    break;
                case 4: //DA Wise
                    $query = $this->db->table('master.usersection');
                    $query->select('*');
                    $query->where('display', 'Y');
                    $query->where('isda', 'Y');
                    $query->orderBy('isda desc, id ASC');
                    $result = $query->get();
                    if ($result->getNumRows() >= 1) {
                        $data['usersection'] = $result->getResultArray();
                        $usersectionData = $result->getResultArray();

                        $usersectionIds = array_column($usersectionData, 'id');
                    }

                    $data['empid'] = 1;
                    $data['dawise']= $this->ReportModel->getDawise($ucode);
                    return view('Reports/copying/da_wise_search_view',$data);
                    break;
                case 5: //E-Copy Stats
                    return view('Reports/copying/ecopy_status_search_view');
                    break;
                case 6: //ePay
                    return view('Reports/copying/epay_search_view');
                    break;
                case 7: //File Request
                    $data['file_request']= $this->ReportModel->getFileRequest();
                     return view('Reports/copying/file_request_search_view',$data);
                     break;
                 case 8://Received By R&I
                     return view('Reports/copying/received_by_ri_search_view');
                     break;
                 case 9: //Reports Search by Diary Number
                     $data['casetype']=get_from_table_json('casetype');
                     return view('Reports/copying/search_by_diary_search_view',$data);
                     break;
                 case 10: //User Wise Report
                     return view('Reports/copying/userwise_search_view');
                     break;
                 case 11: //View Search
                     $data['copy_category'] = is_data_from_table('master.copy_category');
                     $data['copy_status']=is_data_from_table('master.ref_copying_status');
                     $data['case_source']=is_data_from_table('master.ref_copying_source');
                     $data['order_type'] = $this->ReportModel->order_type();
                     

                    // echo "<pre>"; print_r($data['order_type']);exit;
                     return view('Reports/copying/view_search_view',$data);
                     break;
                default:

            }
        }
    }
    public function section_user()
    {
        $sectionId = $this->request->getPost('section_id');
        $query = $this->db->table('master.users');
        $query->select('*');
        $query->where('display', 'Y');
        $query->where('section',$sectionId);
        $result = $query->get();

        if ($result->getNumRows() >= 1) {
            $userData = $result->getResultArray();
            return $this->response->setJSON($userData);
        } else {
            return $this->response->setJSON([]);
        }

        // $data['dawise'] = $this->ReportModel->getDawise($ucode);
    }
    public function getsection_user()
    {
        $ucode = $this->request->getPost('usercode');
        $data['da_wise'] = $this->ReportModel->getDawise($ucode);

        return view('Reports/copying/da_wise_search_view_details', $data);

        // Return the data as JSON response
    }
    public function consumed_barcode_search(){
        $data['from_date'] = $this->request->getPost('from_date');
        $data['to_date'] = $this->request->getPost('to_date');
         if (!empty($data)){
            $data['consumedBarcode']= $this->ReportModel->getConsumedBarcode($data);
        }
       $data['formdata'] = $this->request->getPost();
       $data['report_title'] = 'Details of Consumed Barcode Data';
       $data['title'] = 'Details of Consumed Barcode Data';
      return view('Reports/copying/get_content_consumed_barcode',$data);exit;

    }
    public function copying_request_search(){
        $data['from_date'] = $this->request->getPost('from_date');
        $data['to_date'] = $this->request->getPost('to_date');
        $data['section'] = $this->request->getPost('section');
        $data['deliver_mode'] = $this->request->getPost('deliver_mode');
        $data['order_type'] = $this->request->getPost('order_type');
        $data['case_status'] = $this->request->getPost('case_status');
        if (!empty($data)){
            $data['copyingRequest']= $this->ReportModel->getCopyingRequest($data);
        }
        $data['formdata'] = $this->request->getPost();
        $data['report_title'] = 'Details of Copying Request Data';
        return view('Reports/copying/get_content_copying_request',$data);exit;

    }

    public function ecopy_status_search(){
        $data['from_date'] = $this->request->getPost('from_date');
        $data['to_date'] = $this->request->getPost('to_date');
        if (!empty($data)){
            $data['ecopyStatus']= $this->ReportModel->getEcopyStatus($data);
        }
        $data['formdata'] = $this->request->getPost();
        $data['report_title'] = 'Details of E-copy Stats';
        return view('Reports/copying/get_content_ecopy_status',$data);exit;

    }
    public function epay_search(){
        $data['from_date'] = $this->request->getPost('from_date');
        $data['to_date'] = $this->request->getPost('to_date');
        $data['pay_heads'] = $this->request->getPost('pay_heads');
        $data['crn'] = $this->request->getPost('crn');
        $data['rdbtn_select'] = $this->request->getPost('rdbtn_select');


        if (!empty($data)){
            $data['dataEpay']= $this->ReportModel->getEpay($data);
        }
        $orderCodes=''; 
        foreach ($data['dataEpay'] as $item) {
            $orderCodes = $item->order_code;
        }
        if(!empty($data['dataEpay'])){
            $data['ordertype']= $this->ReportModel->ordertype($orderCodes);
        }else{
            $data['ordertype']=[];
        }
        

        // echo "<pre>"; print_r($data['ordertype']); exit;
        $data['formdata'] = $this->request->getPost();
        $data['report_title'] = 'Details of ePay Report';
        return view('Reports/copying/get_content_epay',$data);

    }
    public function received_by_ri_search(){
        $data['from_date'] = $this->request->getPost('from_date');
        $data['to_date'] = $this->request->getPost('to_date');
        if (!empty($data)){
            $data['receivedByRI']= $this->ReportModel->getReceivedbyri($data);
        }
        $data['formdata'] = $this->request->getPost();
        $data['report_title'] = 'Details of Received by RI ';
        $data['title'] = 'Details of Received by RI ';
        return view('Reports/copying/get_content_received_by_ri',$data);exit;

    }
    public function diaryorcase_search(){

        if ($this->request->getMethod() === 'post') {
            $search_type=$this->request->getPost('search_type');

            $diary_number=$this->request->getPost('diary_number');
            $diary_year=$this->request->getPost('diary_year');

            $case_type=$this->request->getPost('case_type');
            $case_number=$this->request->getPost('case_number');
            $case_number_to=$this->request->getPost('case_number_to');
            $case_year=$this->request->getPost('case_year');

            $this->validation->setRule('search_type', 'Select Diary or Case type', 'required');

            if (!empty($search_type) && $search_type !=null){
                if ($search_type =='D'){
                    $this->validation->setRule('search_type', 'Select Diary or Case type', 'required');
                    $this->validation->setRule('diary_number', 'Diary number', 'required');
                    $this->validation->setRule('diary_year', 'Diary year', 'required');

                    $data = [
                        'search_type'=>$search_type,
                        'diary_number'=>$diary_number,
                        'diary_year'=>$diary_year,
                    ];

                }else{
                    $this->validation->setRule('search_type', 'Select Diary or Case type', 'required');
                    $this->validation->setRule('case_type', 'Case type', 'required');
                    $this->validation->setRule('case_number', 'Case number', 'required');
                    $this->validation->setRule('case_year', 'Case year', 'required');

                    $data = [
                        'search_type'=>$search_type,
                        'case_type'=>$case_type,
                        'case_number'=>$case_number,
                        'case_year'=>$case_year,
                    ];
                }

            }else{
                $data = [
                    'search_type'=>$search_type
                ];
            }

            if (!$this->validation->run($data)) {
                // handle validation errors
                echo '3@@@';
                //echo $this->validation->getError('search_type').$this->validation->getError('case_type');
                echo $this->validation->listErrors();exit();
            }

            if (!$this->validation->run($data)) {
                // handle validation errors
                echo '3@@@';
                //echo $this->validation->getError('search_type').$this->validation->getError('case_type');
                echo $this->validation->listErrors();exit();
            }
            if ($search_type !='D'){
                $main_diary_number = get_diary_case_type($case_type,$case_number,$case_year);
            }else{
                $main_diary_number = $diary_number.$diary_year;
            }
            $reg_number_dispplay = "";
            if (!empty($main_diary_number)){
                $data['resultDiaryorcase']= $this->ReportModel->getDiaryorceseSearch($main_diary_number);
                if(!empty($data['resultDiaryorcase'][0]->reg_no_display)){
                    $reg_number_dispplay = $data['resultDiaryorcase'][0]->reg_no_display;
                }
            }
            $data['diary_no'] = $main_diary_number;
            $data['formdata'] = $this->request->getPost();
            $d_no_text = substr($main_diary_number, 0, -4) . '/' . substr($main_diary_number, -4);

            $data['report_title'] =  "Applications received in Case Number:". $reg_number_dispplay."(".$d_no_text.")"." as on ".date('d-m-Y h:i:s A');

            return view('Reports/copying/get_content_diaryorcase',$data);exit;
        }

    }

    public function userwise_search(){
        $data['from_date'] = $this->request->getPost('from_date');
        $data['to_date'] = $this->request->getPost('to_date');
        if (!empty($data)){
            $data['dataUserwise']= $this->ReportModel->getUserwise($data);
        }

        $data['formdata'] = $this->request->getPost();
        $data['report_title'] = 'Details of Report user wise  ';
        return view('Reports/copying/get_content_userwise',$data);exit;

    }
    public function view_search(){
        $data['from_date'] = $this->request->getPost('from_date');
        $data['to_date'] = $this->request->getPost('to_date');
        $data['status'] = $this->request->getPost('application_status');
        $data['category'] = $this->request->getPost('category');
        $data['document'] = $this->request->getPost('document');
        $data['case_source'] = $this->request->getPost('case_source');
        $data['radiodate'] = $this->request->getPost('radiodate');
        

// print_r($data['order_type']);exit;
        $status = $data['status'];
        $category = $data['category'];
        $document = $data['document'];
        $case_source = $data['case_source'];
        $radiodate = $data['radiodate'];
        $fromDate = $data['from_date'];
        $toDate = $data['to_date'];
       if (!empty($data)){
            $data['dataView']= $this->ReportModel->getView($status,$category,$fromDate,$toDate,$document,$case_source,$radiodate);
        }

        $data['formdata'] = $this->request->getPost();
        $data['report_title'] = 'Details of Report View   ';
        return view('Reports/copying/get_content_view',$data);exit;

    }

    public function user_cases()
    {
        $data['app_name']='';
        $category=$this->request->getGet('category');
        $user=$this->request->getGet('user');
        $from_date=$this->request->getGet('from_date');
        $to_date=$this->request->getGet('to_date');

        if(isset($user) && isset($category) && isset($from_date) &&  isset($to_date))
        {
            $user_cases_result=$this->ReportModel->show_user_cases($category,$user,$from_date,$to_date);
            $data['user_cases_result']=$user_cases_result;
            $data['category']=$category;
            $data['from_date']=$from_date;
            $data['to_date']=$to_date;
            return view('Reports/copying/user_cases_view', $data);
        }
    }

    public function trap()
    {
        $id = $this->request->getGet('id');
        $num = $this->request->getGet('num');
        if (isset($id)) {
            $trap_result = $this->ReportModel->trap($id);
            $data['trap'] = $trap_result;
            $data['number'] = $num;
            $data['id'] = $id;
            return view('Reports/copying/trap_view', $data);
        }
    }


    public function documents()
    {
        $id=$this->request->getGet('id');
        $num=$this->request->getGet('num');
        $data = array();
        if(isset($id))
        {
            $documents_result=$this->ReportModel->documents($id,'');
            if(empty($documents_result)){
                $documents_result=$this->ReportModel->documents($id,'_a');
            }
            $data['document']=$documents_result;
            $data['number']=$num;
        }
        return view('Reports/copying/documents_view',$data);
    }

    public function defects_history()
    {
        $id=$this->request->getGet('id');
        $num=$this->request->getGet('num');
        if(isset($id))
        {
            $defects_history_result=$this->ReportModel->defects_history($id);
            $data['defects']=$defects_history_result;
            $data['number']=$num;
            $data['id']=$id;
            return view('Reports/copying/defects_view',$data);
        }
    }

}
