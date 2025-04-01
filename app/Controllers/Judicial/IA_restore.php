<?php

namespace App\Controllers\Judicial;
use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;
use App\Models\Judicial\AmendCausetitleModel;
use App\Libraries\webservices\Efiling_webservices;
use App\Libraries\webservices\Highcourt_webservices;
use App\Models\Judicial\Model_IA_restore;

class IA_restore extends BaseController
{
    public $Dropdown_list_model;
    public $Model_IA_restore;

    function __construct(){
        $this->Dropdown_list_model= new Dropdown_list_model();
        $this->Model_IA_restore = new Model_IA_restore();
    }
    public function index(){
        $data['current_page_url'] = base_url('Judicial/IA_restore');
        //echo 'test';exit();
        //echo "<pre>";print_r($data['casedesc']); die;
        return view('Judicial/IA_restore_view',$data);
    }
    public function get_search(){

    }
    public function get_content_ia_alive_process(){
        $diary_no = $_SESSION['filing_details']['diary_no'];
        $is_docdetails='';
        if (!empty($diary_no)){
            //$diary_no=$_REQUEST['diary_no'];
            $is_docdetails = $this->Model_IA_restore->get_docdetails($diary_no);
            if (empty($is_docdetails)){
                $is_docdetails = $this->Model_IA_restore->get_docdetails($diary_no,'','_a');
            }
        }
        $data['dno_data'] = $_SESSION['filing_details'];
        $data['ia_res'] = $is_docdetails;
        //echo "<pre>";print_r($data['dno_data']); die;
        $get_view_result= view('Judicial/IA_restore_get_content',$data);
        echo $get_view_result;exit();
        // echo "3@@@Diary No. or Case No. doesn't exist .";exit();
    }
    public function ia_alive_process(){
        $option=$_REQUEST['option'];
        $diary_no = $_SESSION['filing_details']['diary_no'];
        $usercode=session()->get('login')['usercode'];
        if ($option == 2) {

            $updated_by = $usercode;
            //$updated_on = date();
            $remark_array = $_REQUEST['remark'];

            $doc_id = $_REQUEST['doc_id'];
            $dno =$diary_no; // $_REQUEST['diary_no'];

            $len = sizeof($doc_id);

            $this->db = \Config\Database::connect();
            $this->db->transStart();
            $temp_doc_id = '';
            for ($i = 0; $i < $len; $i++) {
                if ($temp_doc_id) {
                    $temp_doc_id = $temp_doc_id . ',' . $doc_id[$i];
                }else{
                    $temp_doc_id = $doc_id[$i];
                }
                $docdetails = $this->Model_IA_restore->get_docdetails($diary_no,[$doc_id[$i]]);
                if (empty($docdetails)){
                    $docdetails = $this->Model_IA_restore->get_docdetails($diary_no,[$doc_id[$i]],'_a');
                }
                if (!empty($docdetails)){
                    $ia_restore_remarks_data = [
                        'diary_no' => $docdetails[0]['diary_no'],
                        'docnum' => $docdetails[0]['docnum'],
                        'docyear' => $docdetails[0]['docyear'],
                        'docd_id' => $docdetails[0]['docd_id'],
                        'restoration_remarks' => $remark_array[$i],
                        'ip_address' => getClientIP(),

                        'updated_on' => date("Y-m-d H:i:s"),
                        'create_modify' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                    $is_sql_lowerct_judges = insert('ia_restore_remarks', $ia_restore_remarks_data);
                }
            }
            $is_data_from_docdetails='P';
            $docdetails2 = $this->Model_IA_restore->get_docdetails($diary_no,$doc_id);
            if (empty($docdetails2)){
                $docdetails2 = $this->Model_IA_restore->get_docdetails($diary_no,$doc_id,'_a');
                $is_data_from_docdetails='D';
            }
            if (!empty($docdetails2)){
                $data_addon = [
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                ];
                unset($docdetails2[0]['updated_on']);
                unset($docdetails2[0]['updated_by']);
                unset($docdetails2[0]['ia']);
                unset($docdetails2[0]['trial320']);

                $final_array = array_merge($docdetails2[0],$data_addon);

                $query_ia_log = insert('docdetails_history', $final_array);
                if ($query_ia_log) {
                    $upd_docdetails = [
                        'iastat'     =>'P',
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];

                    if ($is_data_from_docdetails=='D'){
                        $query_update_res = $this->db->table('docdetails_a');
                    }else{
                        $query_update_res = $this->db->table('docdetails');
                    }
                    $query_update_res->whereIn('docd_id',$doc_id);
                    $query_update_res->where('diary_no',$dno);
                    $query_update_res->update($upd_docdetails);

                    if ($query_update_res) {
                        $response='<div class="alert alert-success"><strong>Success!</strong> IA(s) restored successfully.</div>';
                    }else{
                        $response='<div class="alert alert-danger"><strong>Fail!</strong> IA restoration failed.</div>';
                    }
                } else {
                    $response='<div class="alert alert-danger"><strong>Fail!</strong> IA restoration failed.</div>';

                }


            }
            $this->db->transComplete();

            echo $response;exit();

        }

        // echo "3@@@Diary No. or Case No. doesn't exist .";exit();
    }

}