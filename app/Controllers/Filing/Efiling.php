<?php

namespace App\Controllers\Filing;
use App\Controllers\BaseController;
use App\Models\Entities\Model_efiling_transaction_records;
use App\Models\Filing\Model_efiling;

class Efiling extends BaseController
{
   public $efiling_model;
   public $Model_efiling_transaction_records;
    function __construct()
    {
        $this->efiling_model = new Model_efiling();
        $this->Model_efiling_transaction_records = new Model_efiling_transaction_records();
        ini_set('memory_limit','51200M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
        ini_set('max_execution_time', -1);
    }

    public function check_documents(){
        if ($this->request->getMethod() === 'post') {
            $ack_id=$this->request->getPost('ack_id');
            $ack_year=$this->request->getPost('ack_year');
            $this->validation->setRule('ack_id', 'Ack number', 'required');
            $this->validation->setRule('ack_year', 'Ack year', 'required');
            $data = [
                'ack_id'=>$ack_id,
                'ack_year'=>$ack_year,
            ];
            if (!$this->validation->run($data)) {
                $error=$this->validation->listErrors();
                return json_encode(['token' => csrf_hash(), 'response_code'=>403,'message'=>'Invalid','data' => $error]);
                exit();
            }
                if(isset($_POST['transaction_id'])) {
                    $transaction_id = (is_null($_POST['transaction_id'])) ? 0 : $_POST['transaction_id'];
                }else {
                    $transaction_id = 0;
                }
                    $response = $this->efiling_model->get_efiling_documents($ack_id, $ack_year, $transaction_id);
                if($response){
                    return json_encode(['token' => csrf_hash(), 'response_code'=>200,'message'=>'Success','data' => $response]);
                }else{
                    return json_encode(['token' => csrf_hash(), 'response_code'=>404,'message'=>'Data not found']);
                }
               exit();
        }else{
            return view('Filing/Efiling/search_by_refID');
        }

    }
    
    public function doc_for_refiling(){
        if ($this->request->getMethod() === 'post') {
            $ack_id=$this->request->getPost('ack_id');
            $ack_year=$this->request->getPost('ack_year');
            $doc=$this->request->getPost('doc');
            $this->validation->setRule('ack_id', 'Ack number', 'required');
            $this->validation->setRule('ack_year', 'Ack year', 'required');
            $this->validation->setRule('doc', 'Mark', 'required');
            $data = [
                'ack_id'=>$ack_id,
                'ack_year'=>$ack_year,
                'doc'=>$doc,
            ];
            if (!$this->validation->run($data)) {
                $error=$this->validation->listErrors();
                return json_encode(['token' => csrf_hash(), 'response_code'=>403,'message'=>'Invalid','data' => $error]);
                exit();
            }
            $doc_decode=json_decode($doc);
            $is_false =0;
            $marked_for_refiling = array();
            foreach($doc_decode as $mark_id => $val){
                if($val == 1){
                    array_push($marked_for_refiling, $mark_id);
                }else{
                    $is_false++;
                }
            }
            $is_doc=json_decode($doc, true);
            if ($is_false==count($is_doc)){
                return json_encode(['token' => csrf_hash(), 'response_code'=>404,'message'=>'The Mark one field checked required']);
            }
            $response =$this->efiling_model->doc_for_refiling($ack_id, $ack_year, $marked_for_refiling);
            if($response){
                return json_encode(['token' => csrf_hash(), 'response_code'=>200,'message'=>'Data Save Successful ','data' => $response]);
            }else{
                return json_encode(['token' => csrf_hash(), 'response_code'=>404,'message'=>'Data Not Save Successful']);
            }
            exit();
        }
    }

    public function docs_from_sc_diary_no_for_refiled_documents()
    {
        if ($this->request->getMethod() === 'get') {
            $diary_number = $this->request->getGet('diary_no');
            $diary_year = $this->request->getGet('diary_year');

            $this->validation->setRule('diary_no', 'Diary No.', 'required');
            $this->validation->setRule('diary_year', 'Diary Year', 'required');
           
            if (!empty($this->request->getGet('transaction_id'))){
                $transaction_id = (is_null($this->request->getGet('transaction_id'))) ? 0 : $this->request->getGet('transaction_id');
            }else {
                $transaction_id = 0;
            }

            $is_archival_table = '_a';
            $response1 =$this->efiling_model->docs_from_sc_diary_no($diary_number,$diary_year,$transaction_id,'');
            if (!empty($response1)){
                $response=$response1;
            }else{
                $response=$this->efiling_model->docs_from_sc_diary_no($diary_number,$diary_year,$transaction_id,$is_archival_table);
            }
            $response_SCEFM1 =$this->efiling_model->docs_from_sc_diary_no_SCEFM($diary_number,$diary_year,'');
            if (!empty($response_SCEFM1)){
                $response_SCEFM=$response_SCEFM1;
            }else{
                $response_SCEFM =$this->efiling_model->docs_from_sc_diary_no_SCEFM($diary_number,$diary_year,$is_archival_table);
            }
            $data['documents'] = array();
            $data['result'] = $response;
            $data['resultSCEFM'] = $response_SCEFM;
            $data['transaction_id'] = $transaction_id;
            //echo '<pre>';print_r($response); exit();
            echo json_encode($data);
            die;
            
        }else{
            return json_encode(['token' => csrf_hash(), 'response_code'=>404,'message'=>'Data not found']);
            die;
        } 
    }

    public function docs_from_sc_diary_no()
    {
        if ($this->request->getMethod() === 'post') {
            $diary_number = $this->request->getPost('diary_number');
            $diary_year = $this->request->getPost('diary_year');

            $this->validation->setRule('diary_number', 'Diary No.', 'required');
            $this->validation->setRule('diary_year', 'Diary Year', 'required');
            $data = [
                'diary_number' => $diary_number,
                'diary_year' => $diary_year,
            ];
            if (!$this->validation->run($data)) {
                // handle validation errors
                echo '3@@@';
                echo $this->validation->listErrors();
                exit();
            }
            if (isset($_POST['transaction_id'])) {
                $transaction_id = (is_null($_POST['transaction_id'])) ? 0 : $_POST['transaction_id'];
            } else {
                $transaction_id = 0;
            }

            $is_archival_table = '_a';
            $response1 = $this->efiling_model->docs_from_sc_diary_no_docsby($diary_number, $diary_year, $transaction_id, '');
            if (!empty($response1)) {
                $response = $response1;
            } else {
                $response = $this->efiling_model->docs_from_sc_diary_no_docsby($diary_number, $diary_year, $transaction_id, $is_archival_table);
            }
            $response_SCEFM1 = $this->efiling_model->docs_from_sc_diary_no_SCEFM($diary_number, $diary_year, '');
            if (!empty($response_SCEFM1)) {
                $response_SCEFM = $response_SCEFM1;
            } else {
                $response_SCEFM = $this->efiling_model->docs_from_sc_diary_no_SCEFM($diary_number, $diary_year, $is_archival_table);
            }
            $data['documents'] = array();
            $data['result'] = $response;
            $data['resultSCEFM'] = $response_SCEFM;
            $data['transaction_id'] = $transaction_id;
            //echo '<pre>';print_r($response); exit();
            if ($transaction_id == 0) {
                $resul_view = view('Filing/Efiling/docs_from_sc_diaryNo_get_content', $data);
            } else {
                $resul_view = view('Filing/Efiling/transactions_by_date_get_content_docModal', $data);
            }
            echo '1@@@' . $resul_view;
            exit();
        } else {
            return view('Filing/Efiling/docs_from_sc_diaryNo');
        }
    }

    public function pipreport()
    {
        if ($this->request->getMethod() === 'post') {
            $from_date=$this->request->getPost('from_date');
            $to_date=$this->request->getPost('to_date');
            $status=$this->request->getPost('status');
            $app_type=$this->request->getPost('app_type');
            $action_type=$this->request->getPost('action_type');
            $status_flag = ($status==2)?0:1;
            $this->validation->setRule('from_date', 'Date From', 'required');
            $this->validation->setRule('to_date', 'Date To', 'required');
            $this->validation->setRule('status', 'Status', 'required');
            $this->validation->setRule('app_type', 'Application Type', 'required');
            $data = [
                'from_date'=>$from_date,
                'to_date'=>$to_date,
                'status'=>$status,
                'app_type'=>$app_type,
            ];
            if (!$this->validation->run($data)) {
                // handle validation errors
                echo '3@@@';
                echo $this->validation->listErrors();exit();
            }
            $timestamp1 = strtotime($from_date);
            $timestamp2 = strtotime($to_date);
            if ($timestamp1 > $timestamp2){
                echo "3@@@To Date must be greater than From date"; exit();
            }
            $fromDate = date('Y-m-d', strtotime($from_date));
            $toDate = date('Y-m-d', strtotime($to_date));
            $is_archival_table = '_a';
            $response1 =$this->efiling_model->get_pip_report($fromDate,$toDate,$status_flag,$app_type,$action_type,'');
            $response2 =$this->efiling_model->get_pip_report($fromDate,$toDate,$status_flag,$app_type,$action_type,$is_archival_table);
            if (!empty($response1) && !empty($response2)){
                $response= array_merge($response1,$response2);
            }else if (!empty($response1) && empty($response2)){
                $response=$response1;
            }else if (empty($response1) && !empty($response2)){
                $response=$response2;
            }else{
                $response=$response1;
            }

            $data['result'] = $response1;
            $resul_view= view('Filing/Efiling/pipreport_search_get_content',$data);
            echo '1@@@'.$resul_view;exit();
            exit();
        }else {
            return view('Filing/Efiling/pipreport_search');
        }

    }
    public function old_refiling_refiledcases()
    {
        if ($this->request->getMethod() === 'post') {
            $from_date=$this->request->getPost('from_date');
            $to_date=$this->request->getPost('to_date');

            $this->validation->setRule('from_date', 'Date From', 'required');
            $this->validation->setRule('to_date', 'Date To', 'required');
            $data = [
                'from_date'=>$from_date,
                'to_date'=>$to_date,
            ];
            if (!$this->validation->run($data)) {
                // handle validation errors
                echo '3@@@';
                echo $this->validation->listErrors();exit();
            }
            $timestamp1 = strtotime($from_date);
            $timestamp2 = strtotime($to_date);
            if ($timestamp1 > $timestamp2){
                echo "3@@@To Date must be greater than From date"; exit();
            }
            $is_archival_table = '_a';
            $response1 =$this->efiling_model->old_refiling_refiledcases($from_date,$to_date,'');
            $response_one=array();
            if (!empty($response1)){
                foreach ($response1 as $row){
                    if (empty($row['pet_name']) && empty($row['res_name'])){
                        $pet_pet_name_res_name=is_data_from_table('main_a',['diary_no'=>$row['diary_no']],'pet_name,res_name,reg_no_display','R');
                        if (!empty($pet_pet_name_res_name)){
                            $data_pet_pet_name_res_name = [
                                'pet_name' => $pet_pet_name_res_name['pet_name'],
                                'res_name' => $pet_pet_name_res_name['res_name'],
                                'reg_no_display' => $pet_pet_name_res_name['reg_no_display'],
                            ];
                            unset($row['pet_name']);unset($row['res_name']);
                            unset($row['pet_name']);unset($row['res_name']);
                            unset($row['reg_no_display']);unset($row['reg_no_display']);
                            $response_one[]= array_merge($data_pet_pet_name_res_name,$row);
                        }else{
                            $response_one[]=$row;
                        }
                    }else{
                        $response_one[]=$row;
                    }
                }
            }
            //echo '<pre>';print_r($response_one);exit();


           /* $response2 =$this->efiling_model->old_refiling_refiledcases($from_date,$to_date,$is_archival_table);
            if (!empty($response1) && !empty($response2)){
                $response= array_merge($response1,$response2);
            }else if (!empty($response1) && empty($response2)){
                $response=$response1;
            }else if (empty($response1) && !empty($response2)){
                $response=$response2;
            }else{
                $response=$response1;
            }*/

            $data['result'] = $response_one;
            $resul_view= view('Filing/Efiling/old_refiling_refiledcases_search_get_content',$data);
            echo '1@@@'.$resul_view;exit();
            exit();
        }else {
            return view('Filing/Efiling/old_refiling_refiledcases_search');
        }

    }

    public function refiled_documents($id = null)
    {
        if (!empty($id)) {
            $session =  $_SESSION['login']['usercode'];
            
            // $post_data = json_decode(file_get_contents("php://input"), true);
            // $post_data=$post_data['data'];

        // var_dump($post_data);
            if(!empty($this->request->getGet('from_date')) && !empty($this->request->getGet('to_date')) && !empty($this->request->getGet('status')) && !empty($this->request->getGet('app_type')))
            {
                $from_date = $this->request->getGet('from_date');
                $to_date= $this->request->getGet('to_date');
                $status=$this->request->getGet('status');
                $app_type=$this->request->getGet('app_type');

                $fromDate = date('Y-m-d', strtotime($this->request->getGet('from_date')));
                $toDate = date('Y-m-d', strtotime($this->request->getGet('to_date')));


            //  $eFiled_docs = $this->efiling_model->get_refiling_documents($fromDate, $toDate, $status,$app_type,$session);

                    $data['transactions'] = $this->efiling_model->get_refiling_documents($fromDate, $toDate, $status,$app_type,$session);

                    $data['docmaster'] = $this->efiling_model->docmaster();
                    echo json_encode($data);
                    die;

            }
        }
        else{
            return view('Filing/Efiling/refiling_matters_search');
        }
        die;
    }
    

    public function set_actual_diary_no(){
        if ($this->request->getMethod() === 'post') { 
            extract($_POST);

            if( isset($org_diary_no) && isset($org_diary_yr) && isset($ack) && isset($ack_year) && ((int)$org_diary_yr)>=1950 && !empty($efile_diary))
            {
                $this->efiling_model->set_actual_diary_no($org_diary_no, $org_diary_yr, $ack, $ack_year, $efile_diary);
            }
            echo '1';
        }else{
            echo '0';
        }
    }

    

    public function set_actual_document_no(){
        //$post_data = json_decode(file_get_contents("php://input"), true);
       // $post_data=$post_data['data'];
        extract($_REQUEST);
           if($doccode1=='')
            {
                $doccode1=0;
            }

        if( isset($addDocId) && isset($doccode) && isset($doccode1) && isset($org_doc_no) && isset($org_doc_year) ){
            $this->efiling_model->set_actual_document_no($addDocId, $doccode, $doccode1, $org_doc_no, $org_doc_year);
        }
    }

   /* public function refiled_documents_old()
    {
        $ucode =  $_SESSION['login']['usercode'];
        if ($this->request->getMethod() === 'post') 
        {
           $data = array();
           $from_date = $this->request->getPost('from_date');
           $to_date = $this->request->getPost('to_date');
           $status = $this->request->getPost('status');
           $app_type = $this->request->getPost('app_type');
       
            if( isset($from_date) && isset($to_date) && isset($status) && isset($app_type))
            {            

                $fromDate = date('Y-m-d', strtotime($from_date));
                $toDate = date('Y-m-d', strtotime($to_date));
 
                    $data['transactions'] = $this->efiling_model->get_refiling_documents($fromDate, $toDate, $status,$app_type,$ucode);
                    //$data['docmaster'] = $this->efiling_model->docmaster();

            }
            
            return view('Filing/Efiling/transactions_list',$data);
        }
        else
        return view('Filing/Efiling/refiling_matters_search');
    } */

   


    public function transactions_by_date()
    {
        if ($this->request->getMethod() === 'post') {
            $from_date=$this->request->getPost('from_date');
            $to_date=$this->request->getPost('to_date');
            $status=$this->request->getPost('status');
            $app_type=$this->request->getPost('app_type');
            $action_type=$this->request->getPost('action_type');
            $status_flag = ($status==2)?0:1;
            $this->validation->setRule('from_date', 'Date From', 'required');
            $this->validation->setRule('to_date', 'Date To', 'required');
            $this->validation->setRule('status', 'Status', 'required');
            $this->validation->setRule('app_type', 'Application Type', 'required');
            $data = [
                'from_date'=>$from_date,
                'to_date'=>$to_date,
                'status'=>$status,
                'app_type'=>$app_type,
            ];
            if (!$this->validation->run($data)) {
                // handle validation errors
                echo '3@@@';
                echo $this->validation->listErrors();exit();
            }
            $timestamp1 = strtotime($from_date);
            $timestamp2 = strtotime($to_date);
            if ($timestamp1 > $timestamp2){
                echo "3@@@To Date must be greater than From date"; exit();
            }
            $fromDate = date('Y-m-d', strtotime($from_date));
            $toDate = date('Y-m-d', strtotime($to_date));
            $is_archival_table = '_a';
            $session_r=$_SESSION['login']['usercode'];
            $response1 =$this->efiling_model->get_transactions_by_date($fromDate,$toDate,$status_flag,$app_type,$session_r,$action_type,'');

            /*$response_one=array();
            if (!empty($response1)){
                foreach ($response1 as $row){
                    if (empty($row['pet_name']) && empty($row['res_name'])){
                        $pet_pet_name_res_name=is_data_from_table('main_a',['diary_no'=>$row['org_diary_no']],'pet_name,res_name','R');
                        if (!empty($pet_pet_name_res_name)){
                            $data_pet_pet_name_res_name = [
                                'pet_name' => $pet_pet_name_res_name['pet_name'],
                                'res_name' => $pet_pet_name_res_name['res_name']
                            ];
                            unset($row['pet_name']);unset($row['res_name']);
                            $response_one[]= array_merge($data_pet_pet_name_res_name,$row);
                        }else{
                            $response_one[]=$row;
                        }
                    }else{
                        $response_one[]=$row;
                    }
                }
            }*/


            $data['result'] = $response1;
            $resul_view= view('Filing/Efiling/transactions_by_date_get_content',$data);
            echo '1@@@'.$resul_view;exit();
            exit();
        }else {
            return view('Filing/Efiling/transactions_by_date_search');
        }

    }
    public function transactions_by_refID(){
        if ($this->request->getMethod() === 'post') {
            $ack_id=$this->request->getPost('ack_id');
            $ack_year=$this->request->getPost('ack_year');
            $this->validation->setRule('ack_id', 'Ack number', 'required');
            $this->validation->setRule('ack_year', 'Ack year', 'required');
            $data = [
                'ack_id'=>$ack_id,
                'ack_year'=>$ack_year,
            ];
            if (!$this->validation->run($data)) {
                $error=$this->validation->listErrors();
                return json_encode(['token' => csrf_hash(), 'response_code'=>403,'message'=>'Invalid','data' => $error]);
                exit();
            }
            $response =$this->efiling_model->get_transactions_by_refID($ack_id,$ack_year);
            if($response){
                return json_encode(['token' => csrf_hash(), 'response_code'=>200,'message'=>'Success','data' => $response]);
            }else{
                return json_encode(['token' => csrf_hash(), 'response_code'=>404,'message'=>'Data not found']);
            }
            exit();
        }else {
            return view('Filing/Efiling/transactions_by_refID_search');
        }
    }

    public function update_action(){
        if ($this->request->getMethod() === 'post') {
            $transaction_id=$this->request->getPost('transaction_id');
            $action_update=$this->request->getPost('action_update');
            $this->validation->setRule('transaction_id', 'Transaction ID', 'required');
            $this->validation->setRule('action_update', 'Action Type', 'required');
            $data = [
                'transaction_id'=>$transaction_id,
                'action_update'=>$action_update,
            ];
            if (!$this->validation->run($data)) {
                echo '3@@@';
                echo $this->validation->listErrors();exit();
            }
            if($action_update=='Y'){
                $flgg='N';
            }else if($action_update=='N'){
                $flgg='Y';
            }
            $data_update = ['action_update'=>$flgg];
            //$this->Model_efiling_transaction_records->update($transaction_id,$data_update);
            $is_update=update('e_filing.efiling_transaction_records',$data_update,['transaction_id'=>$transaction_id]);
            if($is_update){
                echo "1@@@<span class='text-success'>Data Updated Successfully</span>"; exit();
            }else{
                echo "3@@@<span class='text-danger'>Data not update Successfully</span>"; exit();
            }
            exit();
        }else {
            return view('Filing/Efiling/transactions_by_refID_search');
        }
    }
    public function get_documents(){
        if ($this->request->getMethod() === 'post') {
            $ack_id=$this->request->getPost('ack_id');
            $ack_year=$this->request->getPost('ack_year');
            $this->validation->setRule('ack_id', 'Ack number', 'required');
            $this->validation->setRule('ack_year', 'Ack year', 'required');
            $data = [
                'ack_id'=>$ack_id,
                'ack_year'=>$ack_year,
            ];
            if (!$this->validation->run($data)) {
                $error=$this->validation->listErrors();
                return json_encode(['token' => csrf_hash(), 'response_code'=>403,'message'=>'Invalid','data' => $error]);
                exit();
            }
            if(isset($_POST['transaction_id'])) {
                $transaction_id = (is_null($_POST['transaction_id'])) ? 0 : $_POST['transaction_id'];
            }else {
                $transaction_id = 0;
            }
            $response = $this->efiling_model->get_efiling_documents($ack_id, $ack_year, $transaction_id);
            $data['documents'] =$response;
            $data['result'] = array();
            $data['resultSCEFM'] = array();
            $data['transaction_id'] = $transaction_id;
                $resul_view = view('Filing/Efiling/transactions_by_date_get_content_docModal',$data);
            echo '1@@@'.$resul_view;exit();
            exit();
        }

    }

    public function efiling_applications()
{
    // Fetch the POST data
    // $post_data = $this->request->getJSON(true);
    // $post_data = $post_data['data'] ?? null;
    if (!empty($this->request->getGet('from_date')) && !empty($this->request->getGet('to_date'))) {
        $fromDate = date('Y-m-d', strtotime($this->request->getGet('from_date')));
        $toDate = date('Y-m-d', strtotime($this->request->getGet('to_date')));
        $status_flag = $this->request->getGet('status') ?? 0;

        // Fetch the applications based on status flag
        if ($status_flag != 0) {
            $eFiled_apps = $this->efiling_model->get_efiling_application($fromDate, $toDate, $status_flag);
        } else {
            $eFiled_apps = $this->efiling_model->pending_applications($fromDate, $toDate);
        }
        // Output the result in JSON format
        return $this->response->setJSON($eFiled_apps);
    } else {
        // Render the view if no data was passed
        return view('Filing/Efiling/efiling_applications');
    }
}

public function efiling_documents($id, $year)
{
    if (isset($id)) {
        $eFiled_docs['docs'] = $this->efiling_model->get_efiling_documents($id, $year);
        $eFiled_docs['category'] = $this->efiling_model->get_efiling_category($id, $year);
        $eFiled_docs['parties'] = $this->efiling_model->get_efiling_parties($id, $year);

        return $this->response->setJSON($eFiled_docs);
    }
}

public function import_to_icmis($id)
{
    if (isset($id)) {
        // Fetch data from the 't_main' table using the efiling model
        $t_main = $this->efiling_model->t_main($id);
        $t_main_diary_no = $t_main[0]['diary_no'];

        // Fetch data from the 't_main_icmis' table using the efiling model
        $t_main_icmis = $this->efiling_model->t_main_icmis($id);

        // Get the next diary number
        $next_diary_no = $this->efiling_model->get_next_diary_no();

        // Insert data into 'main' table if certain conditions are met
        if (count($t_main) == 1 && count($t_main_icmis) == 0) {
            unset($t_main[0]['org_fil_no'], $t_main[0]['cnt_status'], $t_main[0]['org_diary_no'], $t_main[0]['fil_dt'], $t_main[0]['dacode']);
            $t_main[0]['diary_no'] = $next_diary_no . date('Y');

            // Insert into the 'main' table
            $this->efiling_model->insert_into_table('main', $t_main[0]);

            // Update the diary number counter
            $this->efiling_model->update_diary_no_counter();

            // Fetch the updated data from 't_main_icmis'
            $t_main_icmis = $this->efiling_model->t_main_icmis($id);
        }

        // Loop through the tables and insert data accordingly
        $tables = ['party', 'act_main', 'advocate', 'indexing', 'lowerct', 'mul_category', 'ec_keyword', 'sensitive_cases', 'case_limit'];

        foreach ($tables as $table) {
            $t_efile = $this->efiling_model->t_rows($table, $t_main_diary_no);
            $t_icmis = $this->efiling_model->t_rows_icmis($table, $t_main_icmis[0]['diary_no']);

            if (count($t_efile) > 0 && count($t_icmis) == 0) {
                echo $table . "    ";

                // Update the diary_no and unset unnecessary fields
                foreach ($t_efile as &$value) {
                    $value['diary_no'] = $t_main_icmis[0]['diary_no'];
                    unset($value['auto_generated_id'], $value['lower_court_id'], $value['mul_category_idd'], $value['id']);
                }

                // Batch insert the updated rows into the table
                $this->efiling_model->batch_insert_into_table($table, $t_efile);
            }
        }

        // Output the updated diary number
        echo "Diary Number: " . $t_main_icmis[0]['diary_no'];
    }
}



}
