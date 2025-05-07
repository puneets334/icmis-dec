<?php

namespace App\Controllers\Filing;
use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;
use App\Models\Filing\IADocumentModel;
use App\Libraries\webservices\Efiling_webservices;
use App\Libraries\webservices\Highcourt_webservices;

class Ia_documents extends BaseController
{
    public $Dropdown_list_model;
    public $efiling_webservices;
    public $highcourt_webservices;
    public $IADocumentModel;

    function __construct(){   
        $this->Dropdown_list_model= new Dropdown_list_model();
        $this->IADocumentModel = new IADocumentModel();
    }


    public function index(){
        // echo "<pre>"; print_r($_SESSION['filing_details']); die;
       /* if(isset($_SESSION['filing_details'])){
            return redirect()->to('Filing/Ia_documents/Ia_documentsDetails');
        }else{
            $search_type = $this->request->getPost('search_type');
            if ($search_type=='D'){
                $validation = $this->validate([
                    'search_type' => ['label' => 'search Type', 'rules' => 'required|min_length[1]|max_length[1]'],
                    'diary_number' => ['label' => 'Diary Number', 'rules' => 'required|min_length[1]|max_length[8]'],
                    'diary_year' => ['label' => 'Diary Year', 'rules' => 'required|min_length[4]'],
                ]);
            }elseif($search_type=='C'){
                $validation = $this->validate([
                    'search_type' => ['label' => 'search Type', 'rules' => 'required|min_length[1]|max_length[1]'],
                    'case_type_casecode' => ['label' => 'Case type', 'rules' => 'required|min_length[1]|max_length[8]'],
                    'case_number' => ['label' => 'Case No', 'rules' => 'required'],
                    'case_year' => ['label' => 'Case Year', 'rules' => 'required|min_length[4]'],
                ]);
            }
            if ($this->request->getMethod() === 'post' && $validation) {
               
                if ($search_type=='D'){                   
                    $diary_number = $this->request->getPost('diary_number');
                    $diary_year = $this->request->getPost('diary_year');
                    $diary_no=$diary_number.$diary_year;
                    $get_main_table= $this->Dropdown_list_model->get_diary_details_by_diary_no($diary_no);
                }elseif($search_type=='C'){
                    $case_type = $this->request->getPost('case_type_casecode');
                    $case_number = $this->request->getPost('case_number');
                    $case_year = $this->request->getPost('case_year');
                    
                    $diary_no = get_diary_case_type($case_type, $case_number, $case_year);
                    
                    if (!empty($diary_no)) {
                        $get_main_table = $this->Dropdown_list_model->get_diary_details_by_diary_no($diary_no);
                    } else {
                        $get_main_table = array();
                    }
                    
                    // session()->setFlashdata("message_error", 'Data not Fount');
                }
    
                if ($get_main_table){
                    $this->session->set(array('filing_details'=> $get_main_table));
                    return redirect()->to('Filing/Ia_documents/redirect_on_diary_user_type');
                    exit();
                }else{
                    session()->setFlashdata("message_error", 'Data not Fount');
                }
    
            }
            $data['casetype']=get_from_table_json('casetype');
            $data['formAction'] = 'Filing/Ia_documents/index/';
            return view('Filing/diary_search_party',$data);
        } */   
       
        if (empty(session()->get('filing_details')['diary_no'])) {
            $uri = current_url(true);             
             //$getUrl = $uri->getSegment(0).'-'.$uri->getSegment(1);
            $getUrl = str_replace('/', '-', $uri->getPath());
            header('Location:'.base_url('Filing/Diary/search?page_url='.base64_encode($getUrl)));exit();
            exit();
        } else {      
            $diary_no = $_SESSION['filing_details']['diary_no'];
            $data['dno'] = $diary_no;
            $diary_year = substr($diary_no, -4);
            $data['dyr'] = $diary_year;

            $data['aorList'] = $this->IADocumentModel->get_aor_name();
            $data['viewData'] = $this->IADocumentModel->get_hcinfo_m_e_new($diary_no);
            $data['doc_list'] = $this->IADocumentModel->getInfoForLd($diary_no);

        return view('Filing/ia_documents_view', $data);
        }
    }



    function redirect_on_diary_user_type() {
        
        if(session()->get('login')) {
            return redirect()->to('Filing/Ia_documents/Ia_documentsDetails');
        }else{
            session()->setFlashdata("message_error", 'Accessing permission denied contact to Computer Cell.');
        }
        return redirect()->to('Filing/Ia_documents/Ia_documentsDetails');
    }

    public function Ia_documentsDetails(){

        if(!isset($_SESSION['filing_details'])){
            return redirect()->to('Filing/Ia_documents/index');
        }        
        $diary_no = $_SESSION['filing_details']['diary_no'];
        $data['dno'] = $diary_no;
        $diary_year = substr($diary_no, -4);
        $data['dyr'] = $diary_year;

        $data['aorList'] = $this->IADocumentModel->get_aor_name();
        $data['viewData'] = $this->IADocumentModel->get_hcinfo_m_e_new($diary_no);
        $data['doc_list'] = $this->IADocumentModel->getInfoForLd($diary_no);

       return view('Filing/ia_documents_view', $data);
    }


    public function caseBlockList_view(){
        /* if(!isset($_SESSION['filing_details'])){
            // return redirect()->to('Filing/Ia_documents/index');
            $search_type = $this->request->getPost('search_type');            
            if ($search_type=='D'){
                $validation = $this->validate([
                    'search_type' => ['label' => 'search Type', 'rules' => 'required|min_length[1]|max_length[1]'],
                    'diary_number' => ['label' => 'Diary Number', 'rules' => 'required|min_length[1]|max_length[8]'],
                    'diary_year' => ['label' => 'Diary Year', 'rules' => 'required|min_length[4]'],
                ]);
            }elseif($search_type=='C'){
                $validation = $this->validate([
                    'search_type' => ['label' => 'search Type', 'rules' => 'required|min_length[1]|max_length[1]'],
                    'case_type_casecode' => ['label' => 'Case type', 'rules' => 'required|min_length[1]|max_length[8]'],
                    'case_number' => ['label' => 'Case No', 'rules' => 'required'],
                    'case_year' => ['label' => 'Case Year', 'rules' => 'required|min_length[4]'],
                ]);
            }
            if ($this->request->getMethod() === 'post' && $validation) {                
                if ($search_type=='D'){
                    $diary_number = $this->request->getPost('diary_number');
                    $diary_year = $this->request->getPost('diary_year');
                    $diary_no=$diary_number.$diary_year;
                    $get_main_table= $this->Dropdown_list_model->get_diary_details_by_diary_no($diary_no);
                }elseif($search_type=='C'){
                    $ct = $this->request->getPost('case_type_casecode');
                    $cn = $this->request->getPost('case_number');
                    $cy = $this->request->getPost('case_year');
                    $get_main_table= $this->Dropdown_list_model->getDiaryDetails($ct,$cn,$cy);
                    // session()->setFlashdata("message_error", 'Data not Fount');
                }
                // print_r($get_main_table);die;
    
                if ($get_main_table){
                    $this->session->set(array('filing_details'=> $get_main_table));
                    return redirect()->to('Filing/Ia_documents/caseBlockList_view');
                    exit();
                }else{
                    session()->setFlashdata("message_error", 'Data not Fount');
                }
    
            }
            
            $data['casetype']=get_from_table_json('casetype');
            $data['formAction'] = 'Filing/Ia_documents/caseBlockList_view/';
            return view('Filing/diary_search_party',$data);
        } 

        $diary_no = $_SESSION['filing_details']['diary_no'];
        $data['dno'] = $diary_no;
        $diary_year = substr($diary_no, -4);
        $data['dyr'] = $diary_year;*/

        $data['caseBlock_list'] = $this->IADocumentModel->getcaseblocklist();

        return view('Filing/caseBlockList_view', $data);
    }

    public function caseBlockList_viewAjax()
    {
        $request = $this->request->getGet(); // Get DataTables parameters from POST request
    
        // Get parameters from DataTables
        $searchValue = $request['search']['value'] ?? ''; // Search value
        $start = $request['start'] ?? 0; // Pagination start index
        $length = $request['length'] ?? 10; // Number of records to fetch
        $orderColumnIndex = $request['order'][0]['column'] ?? 0; // Column index for sorting
        $orderDir = $request['order'][0]['dir'] ?? 'asc'; // Sorting direction
        
        if ($length == -1) {
            $length = null;
        }
        
        $columns = [            
            'diary_no',
            'pet_name',
            'reason_blk',
            'section_name',
            'a.ent_dt'
        ];
        $orderColumn = $columns[$orderColumnIndex] ?? 'sn'; // Default sorting column
    
        // Query your database
        $caseBlockList = $this->IADocumentModel->getFilteredCaseBlockList($searchValue, $start, $length, $orderColumn, $orderDir);
        $totalRecords = $this->IADocumentModel->getCaseBlockCount(); // Total records
        $filteredRecords = $this->IADocumentModel->getFilteredCaseBlockCount($searchValue); // Filtered records
    
        // Format response for DataTables
        $data = [];
        foreach ($caseBlockList as $key => $row) {
            $data[] = [
                'action' => '<button type="button" id="btnDelete' . $row['id'] . '" value="Remove" class="caseBlckDelete btn btn-danger btn-sm table-edit-btn"><i class="fas fa-trash" aria-hidden="true"></i></button>',
                'sn' => $start + $key + 1,
                'diary_no' => substr($row['diary_no'], 0, -4) . '/' . substr($row['diary_no'], -4),
                'parties' => ($row['pet_name'] != '' && $row['res_name'] != '') ? $row['pet_name'] . '<b> V/S </b>' . $row['res_name'] : '',
                'reason_blk' => $row['reason_blk'],
                'section_name' => $row['section_name'],
                'date' => (!empty($row['ent_dt'])) ? date('d-m-Y h:i:s A', strtotime($row['ent_dt'])) : ''
            ];
        }
        
        // Send JSON response
        return $this->response->setJSON([
            'draw' => intval($request['draw']), // DataTables draw counter
            'recordsTotal' => $totalRecords, // Total records
            'recordsFiltered' => $filteredRecords, // Filtered records
            'data' => $data // Formatted data
        ]);
    }
    

    public function verify_defective_view(){
        /* if(!isset($_SESSION['filing_details'])){
            // return redirect()->to('Filing/Ia_documents/index');
            if ($this->request->getMethod() === 'post' && $this->validate([
                'search_type' => ['label' => 'search Type', 'rules' => 'required|min_length[1]|max_length[1]'],
                'diary_number' => ['label' => 'Diary Number', 'rules' => 'required|min_length[1]|max_length[8]'],
                'diary_year' => ['label' => 'Diary Year', 'rules' => 'required|min_length[4]'],
            ])) {
                $search_type = $this->request->getPost('search_type');
                if ($search_type=='D'){
                    $diary_number = $this->request->getPost('diary_number');
                    $diary_year = $this->request->getPost('diary_year');
                    $diary_no=$diary_number.$diary_year;
                    $get_main_table= $this->Dropdown_list_model->get_diary_details_by_diary_no($diary_no);
                }elseif($search_type=='C'){
                    $case_number = $this->request->getPost('case_number');
                    $case_year = $this->request->getPost('case_year');
                    session()->setFlashdata("message_error", 'Data not Fount');
                }
    
                if ($get_main_table){
                    $this->session->set(array('filing_details'=> $get_main_table));
                    return redirect()->to('Filing/Ia_documents/verify_defective_view');
                    exit();
                }else{
                    session()->setFlashdata("message_error", 'Data not Fount');
                }
    
            }
            $data['casetype']=get_from_table_json('casetype');
            $data['formAction'] = 'Filing/Ia_documents/verify_defective_view/';
            return view('Filing/diary_search_party',$data);
        }

        $diary_no = $_SESSION['filing_details']['diary_no'];
        $data['dno'] = $diary_no;
        $diary_year = substr($diary_no, -4);
        $data['dyr'] = $diary_year; */

        $data['userDetails_verify'] = $this->IADocumentModel->getUserDetails_verify();
        $data['verify_defective'] = $this->IADocumentModel->getverify_defective();

       return view('Filing/verify_defective_view', $data);
    }

    public function addCaseBlock()
    {
        // Start session (if not already started)
        $session = session();

        // Get user ID from session
        $ucode = $session->get('dcmis_user_idd');

        // Get request data
        $dno = $this->request->getPost('dno') . $this->request->getPost('dyr');
        $reason = trim(strtoupper(addslashes($this->request->getPost('reason'))));
       
        // Database query to check if the record already exists
        $builder = $this->db->table('loose_block');
        $builder->where(['diary_no' => $dno, 'display' => 'Y']);
        $query = $builder->get();   

        if ($query->getNumRows() > 0) {
            echo "0~RECORD ALREADY PRESENT";
        } else {
            // Insert new record into the database
            $data = [
                'diary_no' => $dno,
                'reason_blk' => $reason,
                'usercode' => $ucode??'0',
                'up_user' => '0',
                'ent_dt' => date('Y-m-d H:i:s')
            ];

            if ($builder->insert($data)) {
                echo "1~RECORD INSERTED SUCCESSFULLY";
            } else {
                echo "0~FAILED TO INSERT RECORD";
            }
        }
    }

    public function get_case_block()
    {
        // Get the data from the model
        $data['results'] = $this->IADocumentModel->getLooseBlockRecords();
        // Pass data to the view
        return view('Filing/loose_block_view', $data);
    }

    public function get_party_name(){
        $dataset = $_POST['data'];
        $getParty = $this->IADocumentModel->get_party_name($dataset);
        echo $getParty;
    }

    public function getDoc_type1(){
        $diary_no = $_POST['dno'];
        $gettype = $this->IADocumentModel->getDoc_type1($diary_no);
        echo $gettype;
    }


    public function getPetResList(){
        $dataset = $_POST['data'];
        $getList = $this->IADocumentModel->getPetResList($dataset);
        echo $getList;
    }


    public function save_loose(){
        $dataset = $_POST;
        $getList = $this->IADocumentModel->save_loose($dataset);
        echo $getList;
    }

    public function del_for_ld_del(){
        $dataset = $_POST;
        $getList = $this->IADocumentModel->del_for_ld_del($dataset);
        echo $getList;
    }

    public function loose_up_new(){
        $dataset = $_POST;       
        $getList = $this->IADocumentModel->loose_up_new($dataset);
        print_r($getList);die;
        echo $getList;
    }

    public function delete_case_block(){
        $dataset = $_POST;
        $getList = $this->IADocumentModel->delete_case_block($dataset);
        echo $getList;
    }

    public function save_case_block(){
        $dataset = $_POST['data'];
        $getList = $this->IADocumentModel->save_case_block($dataset);
        echo $getList;
    }


    public function verify_save(){
        $dataset = $_POST['data'];
        $getList = $this->IADocumentModel->verify_save($dataset);
        echo $getList;
    }

    public function getRemarksList(){
        $dataset = $_POST['txt'];
        $getList = $this->IADocumentModel->getRemarksList($dataset);
        echo $getList;
    }


    public function get_remark_list()
    {
        $q = strtolower($_GET["term"]);
        if (!$q) return;
        
        //$sql = "SELECT DISTINCT remark_data FROM docdetails_remark WHERE remark_data LIKE '%$q%' ";
        //$result = mysql_query($sql) or die(mysql_error());

        $builder = $this->db->table('docdetails_remark');
        $builder->distinct();
        $builder->select('remark_data');
        $builder->where("remark_data != ''");
        $builder->where("remark_data ILIKE '%$q%'");
        $builder->limit(10);
        $query1 = $builder->get();
        $result = $query1->getResultArray();

        $json=array();
        foreach($result as $row)
        {
            $json[]=array('value'=>$row['remark_data'],'label'=>$row['remark_data']);
        }
        echo json_encode($json);
        die;
        
    }
    

}
