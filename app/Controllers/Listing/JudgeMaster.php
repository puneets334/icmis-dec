<?php

namespace App\Controllers\Listing;

use App\Controllers\BaseController;

use App\Models\Listing\JudgeMasterModel;

class JudgeMaster extends BaseController
{

    protected $Judge_Master_model;

    function __construct()
    {
        $this->Judge_Master_model = new JudgeMasterModel();
        helper(['form', 'url', 'html']);
        // $this->load->library('form_validation');
        // $this->load->library('session');
    }

    public function getSession($session)
    {
        $this->session->set('dcmis_user_idd', $session);
        return $this->index();
    }

    public function index()
    {
        $data['app_name'] = 'Judge Master';
        $data['judge'] = $this->Judge_Master_model->getJudge();
        return view('Listing/judge_master/judge_master_entry', $data);
    }

    public function insert_Judge_Master()
    {
        $data['insert_result'] = '';
        if (isset($_POST) && isset($_POST['judge1']) && isset($_POST['judge2']) && isset($_POST['judge2']) && isset($_POST['from_date']) && isset($_POST['fresh_limit']) && isset($_POST['old_limit'])) {
            $judge1 = $_POST['judge1'];
            $judge2 = $_POST['judge2'];
            $judge3 = $_POST['judge3'];
            $freshLimit = $_POST['fresh_limit'];
            $oldLimit = $_POST['old_limit'];
            $frm_dt = date('Y-m-d', strtotime($_POST['from_date']));
            // $to_dt = date('Y-m-d', strtotime($_POST['to_date']));
            $insertStatus = $this->Judge_Master_model->insert_Judge_Master($judge1, $judge2, $judge3, $freshLimit, $oldLimit, $frm_dt, $_POST['usercode']);
        }
        $this->display_Judge_Entry();
    }

    public function display_Judge_Entry()
    {
        ob_clean();
        header("Content-Type: application/json;charset=utf-8");
        $arr = $this->Judge_Master_model->disp_Judge_Entry();
        if ($arr != null)
            echo json_encode($arr);
        else
            echo json_encode(0);
        ob_end_flush();
    }

    public function check_case()
    {
        $data['app_name'] = "Check Entry";
        $judge1 = $_GET['judge1'];
        $judge2 = $_GET['judge2'];
        $judge3 = $_GET['judge3'];
        $to_dt = $_GET['to_dt'];
        if (isset($judge1) && isset($judge2) && isset($to_dt))
            $data_array['case_detail'] = $this->Judge_Master_model->check_case($judge1, $judge2, $judge3, $to_dt);
        echo json_encode($data_array);
    }

    public function check_already_sitted()
    {
        $data['app_name'] = "Check Already Sitted.";
        $judge1 = $_POST['judge1'];
        $judge2 = $_POST['judge2'];
        $judge3 = $_POST['judge3'];
        // $to_dt = $_POST['to_dt'];
        if (isset($judge1) && isset($judge2))
            $data_array['case_detail'] = $this->Judge_Master_model->check_already_sitted($judge1, $judge2, $judge3);
        echo json_encode($data_array);
    }

    public function update_case()
    {
        $data['app_name'] = "Update Record";
        $judge1 = $_POST['judge1'];
        $judge2 = $_POST['judge2'];
        $judge3 = $_POST['judge3'];
        $freshLimit = $_POST['fresh_limit'];
        $oldLimit = $_POST['old_limit'];
        $to_dt = date('Y-m-d', strtotime($_POST['to_date']));
        $frm_dt = date('Y-m-d', strtotime($_POST['from_date']));
        if (isset($judge1) && isset($judge2) && isset($judge3) && isset($freshLimit) && isset($oldLimit) && isset($to_dt))
            $this->Judge_Master_model->update_Judge_Master($judge1, $judge2, $judge3, $freshLimit, $oldLimit, $to_dt, $frm_dt, $_POST['usercode']);
    }

    /*Category Wise Controller*/
    public function judgeCategory($session)
    {
        $data['app_name'] = 'Judge Category Master';
        $this->session->set('dcmis_user_idd', $session);
        // $data['Judge_Master_model'] = $this->Judge_Master_model;
        $data['judge'] = $this->Judge_Master_model->getJudge();
        return view('Listing/judge_master/judge_category', $data);
    }

    public function get_Sub_Subject_Category()
    {
        ob_clean();
        header("Content-Type: application/json;charset=utf-8");
        $data_array = $this->Judge_Master_model->get_Sub_SubjectCategory($_POST['Mcat']);
        echo json_encode($data_array);
        ob_end_flush();
    }

    public function insert_judge_category()
    {
        $data['app_name'] = "Judge Master";
        $judge = $_POST['judge'];
        $fromDate = $_POST['fromDate'];
        $McategoryCode = $_POST['McategoryCode'];
        $categoryCode = $_POST['categoryCode'];
        $usercode = $_POST['usercode'];
        $mf = $_POST['mf'];
        if (isset($judge) && isset($fromDate) && isset($McategoryCode) && isset($categoryCode))
            $response = $this->Judge_Master_model->insert_judge_category($judge, $fromDate, $McategoryCode, $categoryCode, $usercode, $mf);
        return $response;
    }

    public function judgeCategoryUpdate($session)
    {
        $data['app_name'] = "Judge Master";
        $this->session->set('dcmis_user_idd', $session);
        $data['judge_details'] = 'No';
        if (isset($_POST['submit']) && isset($_POST['judge']) && !empty($_POST['judge'])) {
            $judge = $_POST['judge'];
            $mf = $_POST['mf'];
        }
        if (isset($judge)){
            $judgeRec = $this->Judge_Master_model->getJudgeRecord($judge, $mf);
            $data['judge_details'] = ($judgeRec) ? $judgeRec : 'No';
        }
        $data['judge'] = $this->Judge_Master_model->getJudge();
        $data['matters'] = (isset($mf)) ? $mf : '';
        return view('Listing/judge_master/judge_category_update', $data);
    }

    public function getJudgeRecord()
    {
        $judge = $_POST['judge'];
        $mf = $_POST['mf'];
        if (isset($judge))
            $data['judge'] = $this->Judge_Master_model->getJudgeRecord($judge, $mf);
        return view('JudgeMaster/judge_category_update', $data);
    }

    public function update_judge_category()
    {
        $data['app_name'] = "Judge Master";
        if (isset($_POST['priority']) && isset($_POST['toDate']) && isset($_POST['id']) && !empty($_POST)) {            
            $priority = $_POST['priority'];
            $toDate = $_POST['toDate'];
            $id = $_POST['id'];
            $usercode = session()->get('login')['usercode'];
            $mf = $_POST['mf'];
            echo $this->Judge_Master_model->update_judge_category($priority, $toDate, $id, $usercode, $mf);
        }            
    }

    public function judgeCategoryReport()
    {
        
        $data['app_name'] = "Judge Category Report";
        $mf = $this->request->getPost('mf');
        if (isset($mf)) {
            $res = $this->Judge_Master_model->judgeCategoryReport($mf);
            $data = [];
            foreach($res as $val){
                $data['result'][$val['jname']][] = ['subject_category_code'=>$val['catg'],'category_description'=>$val['sub_name1']];
            }
        
        }
        
        $data['mf'] = $mf;
        return view('/Listing/judge_master/judge_category_report', $data);
    }

    public function close_entry()
    {
        $data['app_name'] = "Close Entry";
        $judge1 = $_POST['judge1'];
        $judge2 = $_POST['judge2'];
        $judge3 = $_POST['judge3'];
        $freshLimit = $_POST['fresh_limit'];
        $oldLimit = $_POST['old_limit'];
        $to_dt = date('Y-m-d', strtotime($_POST['to_date']));
        $frm_dt = date('Y-m-d', strtotime($_POST['from_date']));
        if (isset($judge1) && isset($judge2) && isset($judge3) && isset($to_dt)){
            $this->Judge_Master_model->update_close_Entry($judge1, $judge2, $judge3, $to_dt, $_POST['usercode']);
        }            
        else{
            echo "Not Done";
        }
    }

    public function update_judge_bulkcategory($session)
    {
        $data['app_name'] = "Judge Bulk Category";
        $this->session->set('dcmis_user_idd', $session);
        if (isset($_POST['submit']) && !empty($_POST)) {
            $judge = $_POST['judge'];
            $mf = $_POST['mf'];
        }
        if (isset($judge) && isset($mf)) {
            $JudgeRecord = $this->Judge_Master_model->getJudgeRecord($judge, $mf);
            $data['judge_details'] = ($JudgeRecord) ? $JudgeRecord : 'No';
            $data['matters'] = $mf;
        } else {
            $data['matters'] = '';
        }
        $data['judge'] = $this->Judge_Master_model->getJudge();
        return view('Listing/judge_master/judge_catg_bulkup_trnsfr', $data);
    }

    public function updateprocess_judge_bulkcategory()
    {
        if (isset($_POST['judge']) && isset($_POST['usercode']) && !empty($_POST)) {
            $judge = $_POST['judge'];
            $toDate = $_POST['toDate'];
            $usercode = $_POST['usercode'];
            $mf = $_POST['mf'];
            if (isset($judge) && isset($toDate)) {
                echo $this->Judge_Master_model->update_judge_bulk_category($judge, $toDate, $usercode, $mf);
                die;
            }
        }
    }

    public function transfer_Judge_Category($session)
    {
        $data['app_name'] = "Transfer Judge Category";
        $this->session->set('dcmis_user_idd', $session);
        if (isset($_POST['submit']) && !empty($_POST)) {
            $mf = $_POST['mf'];
        }
        if (isset($judge))
            $data['judge_details'] = $this->Judge_Master_model->getJudgeRecord($judge, $mf) ? $this->Judge_Master_model->getJudgeRecord($judge, $mf) : 'No';
        $data['judge'] = $this->Judge_Master_model->getJudge();
        return view('Listing/judge_master/judge_catg_bulk_transfer', $data);
        // $this->transfer_insert_category();
    }

    public function transfer_insert_category()
    {
        $judge_from = $_POST['judge_from'];
        $judge_to = $_POST['judge_to'];
        $mf = $_POST['mf'];
        $usercode = $_POST['usercode'];
        if (isset($judge_from) && isset($judge_to) && isset($mf) && isset($usercode)) {
            $this->Judge_Master_model->transfer_judge_category($judge_from, $judge_to, $usercode, $mf);
        }
    }
}
