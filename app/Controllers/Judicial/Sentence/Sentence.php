<?php

namespace App\Controllers\Judicial\Sentence;
use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;
use App\Models\Judicial\Sentence\Model_sentence;

class Sentence extends BaseController
{
    public $Dropdown_list_model;
    public $Model_sentence;
    function __construct(){
        $this->Dropdown_list_model= new Dropdown_list_model();
        $this->Model_sentence = new Model_sentence();
    }
    public function index(){
        return view('Judicial/Sentence/sentence_view');
    }
    public function get_sentence(){
        $filing_details= session()->get('filing_details');
        $diary_no = $filing_details['diary_no'];
        $dacode = $filing_details['dacode'];
        $c_status = $filing_details['c_status'];
        $ucode=session()->get('login')['usercode'];
        $diary_number=substr($filing_details['diary_no'], 0, -4).'/'.substr($filing_details['diary_no'],-4);
        if(empty($filing_details)) {
            $get_result="3@@@<center><font color=red>The Searched case is not found.</font></center>";
        } else if($dacode!=$ucode) {
            $get_result="3@@@<center><font color=red>Only Concerned Dealing Assistant is authorized to update in Diary No-" . $diary_number . "</font></center>";
        }else if($c_status=='D') {
            $get_result="3@@@<center><font style='color:red; font-weight: bold;'>Diary No-" . $diary_number . " is Disposed. Updation is not allowed.</font></center>";
        }else{
            //$m_from_court=$this->get_from_cout($diary_no);
            $get_result='1@@@'.$diary_no;
        }
        echo $get_result;exit();
    }

    public function get_sentence_undergone_list(){
        $sentence_period_id=$_REQUEST['sentence_period_id'];
        $data['sentence_undergone_list']= $this->Model_sentence->get_sentence_undergone_list($sentence_period_id);
        return view('Judicial/Sentence/sentence_undergone_list',$data);
    }
    public function delete_sentence_undergone(){
        $sentence_undergone_id=$_REQUEST['sentence_undergone_id'];
        if (!empty($sentence_undergone_id)){
            $updateData=[
                'sen_display'=>'N',
                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by'=>$_SESSION['login']['usercode'],
                'updated_by_ip'=>getClientIP()
            ];
            $is_update_sentence_undergone= $this->Model_sentence->update_sentence_undergone($updateData,$sentence_undergone_id);
            if ($is_update_sentence_undergone){
                $response="<span class='text-success'>Successfully Deleted</span>";
            }else{
                $response="<span class='text-danger'>Data not deleted please try again</span>";
            }
        }else{
            $response="<span class='text-danger'>Data not deleted please try again</span>";
        }
        return $response;
    }
    public function add_details()
    {
        if ($this->request->getMethod() === 'post') {
            $sentence_period_id=$this->request->getPost('sentence_period_id');
            $diary_no=$this->request->getPost('diary_no');
            $m_status=$this->request->getPost('m_status');
            $txt_frm_dt=$this->request->getPost('txt_frm_dt');
            $txt_to_dt=$this->request->getPost('txt_to_dt');
            $ddl_case_no=$this->request->getPost('ddl_case_no');
            $ddl_tot_accused=$this->request->getPost('ddl_tot_accused');
            $sp_judgement_dt=$this->request->getPost('sp_judgement_dt');
            $remarks=$this->request->getPost('remarks');

            $cnt_rw=$this->request->getPost('cnt_rw');
            $m_sent2=$this->request->getPost('m_sent2');
            $m_sent2_mon=$this->request->getPost('m_sent2_mon');
            $period_under=$this->request->getPost('period_under');

            $this->validation->setRule('diary_no', 'Diary number', 'required');
            if(($m_sent2=='') && ($m_status!= 'U')) {
                $this->validation->setRule('m_sent2', 'Select year', 'required');
                $this->validation->setRule('m_sent2_mon', 'Select month', 'required');
            }

            $this->validation->setRule('m_status', 'Select Bail/Cusdoty status', 'required');
            $this->validation->setRule('txt_frm_dt', 'Please select from date', 'required');

            $this->validation->setRule('ddl_case_no', 'Case number', 'required');
            $this->validation->setRule('ddl_tot_accused', 'Accused', 'required');
            $this->validation->setRule('sp_judgement_dt', 'Judgement date', 'required');
            $this->validation->setRule('remarks', 'Remarks', 'required');

            $validation = [
                'diary_no'=>$diary_no,
                'm_status'=>$m_status,
                'm_sent2'=>$m_sent2,
                'm_sent2_mon'=>$m_sent2_mon,
                'remarks'=>$remarks,
                'txt_frm_dt'=>$txt_frm_dt,
                'ddl_case_no'=>$ddl_case_no,
                'ddl_tot_accused'=>$ddl_tot_accused,
                'sp_judgement_dt'=>$sp_judgement_dt,
                'sentence_period_id'=>$sentence_period_id,
            ];


            if (!$this->validation->run($validation)) {
                // handle validation errors
                echo '3@@@';
                echo $this->validation->listErrors();exit();
            }
            $difference='';
            if (!empty($txt_frm_dt) && $txt_to_dt){
                $txt_frm_dt_c=strtotime($txt_frm_dt);
                $txt_to_dt_c=strtotime($txt_to_dt);
                $difference = $txt_to_dt_c - $txt_frm_dt_c;
                $difference= round($difference / (60 * 60 * 24));
            }

            $data = [
                'id'=>$cnt_rw,
                'diary_no'=>$diary_no,
                'status'=>$m_status,
                'frm_date'=>$txt_frm_dt,
                'to_date'=>$txt_to_dt,
                'rem'=>$remarks,
                'difference'=>$difference,
                'm_status'=>$m_status,
                'cnt_rw'=>$cnt_rw,

                'm_sent2'=>$m_sent2,
                'm_sent2_mon'=>$m_sent2_mon,
                'ddl_case_no'=>$ddl_case_no,
                'ddl_tot_accused'=>$ddl_tot_accused,
                'sp_judgement_dt'=>$sp_judgement_dt,
                'sentence_period_id'=>$sentence_period_id,
                'add_details'=>'add_details',
            ];
            $sen_time='';
            if (!empty($sp_judgement_dt) && !empty($m_sent2) && !empty($m_sent2_mon)){
                $sen_time=date('d-m-Y',strtotime($sp_judgement_dt.'+'.$m_sent2.' years'));
                $sen_time=date('d-m-Y',strtotime($sen_time.'+'.$m_sent2_mon.' months'));
            }
            if (!empty($period_under)){
                $ex_period_under=  explode(',', $period_under);
                for ($index = 0; $index < count($ex_period_under); $index++) {
                    $in_exp=  explode('@', $ex_period_under[$index]);
                    if($in_exp[0]!='')
                    {
                        if(((strtotime($txt_frm_dt)>=  strtotime($in_exp[0]) && strtotime($txt_frm_dt)<=  strtotime($in_exp[1])) && $in_exp[0]!='') ||  (strtotime($txt_to_dt)>=  strtotime($in_exp[0]) && strtotime($txt_to_dt)<=  strtotime($in_exp[1])))
                        {
                            echo "3@@@<span class='text-danger'>Period already entered. Please select proper from and to date!</span>";exit();
                        }
                    }
                }
            }
            $is_sentence_period= $this->Model_sentence->is_sentence_period($data);
            if (!empty($is_sentence_period)) {
                if ($is_sentence_period['frm_date'] != NULL && $is_sentence_period['to_date'] != NULL) {
                    $is_sentence_period['frm_date'] = date('d-m-Y', strtotime($is_sentence_period['frm_date']));
                    $is_sentence_period['to_date'] = date('d-m-Y', strtotime($is_sentence_period['to_date']));

                    if ((strtotime($txt_frm_dt) <= strtotime($is_sentence_period['frm_date']) && strtotime($txt_frm_dt) >= strtotime($is_sentence_period['to_date']))
                        || strtotime($txt_to_dt) <= strtotime($is_sentence_period['frm_date']) && strtotime($txt_to_dt) >= strtotime($is_sentence_period['to_date'])) {
                        //echo "3@@@<span class='text-danger'>Period already entered. Please select proper from and to date!!</span>";exit();
                    }
                }
            }

            $data['sentence_undergone_list']= [$data];
            $get_result_view= view('Judicial/Sentence/get_sentence_undergone_add_details',$data);
            echo "1@@@".$get_result_view;exit();


        }
    }
    public function add_period_undergone()
    {
        if ($this->request->getMethod() === 'post') {
            $diary_no=$this->request->getPost('diary_no');
            $m_status=$this->request->getPost('m_status');
            $txt_frm_dt=$this->request->getPost('txt_frm_dt');
            $txt_to_dt=$this->request->getPost('txt_to_dt');
            $ddl_case_no=$this->request->getPost('ddl_case_no');
            $ddl_tot_accused=$this->request->getPost('ddl_tot_accused');
            $sp_judgement_dt=$this->request->getPost('sp_judgement_dt');
            $remarks=$this->request->getPost('remarks');

            $cnt_rw=$this->request->getPost('cnt_rw');
            $m_sent2=$this->request->getPost('m_sent2');
            $m_sent2_mon=$this->request->getPost('m_sent2_mon');
            $period_under=$this->request->getPost('period_under');

            $this->validation->setRule('diary_no', 'Diary number', 'required');
            $this->validation->setRule('m_status', 'Please select status', 'required');
            $this->validation->setRule('txt_frm_dt', 'Please select from date', 'required');
            // $this->validation->setRule('txt_to_dt', 'Please select to date', 'required');
            $this->validation->setRule('ddl_case_no', 'Case number', 'required');
            $this->validation->setRule('ddl_tot_accused', 'Accused', 'required');
            $this->validation->setRule('sp_judgement_dt', 'Judgement date', 'required');
            $this->validation->setRule('remarks', 'Remarks', 'required');

            $validation = [
                'diary_no'=>$diary_no,
                'm_status'=>$m_status,
                'remarks'=>$remarks,
                'txt_frm_dt'=>$txt_frm_dt,
                'ddl_case_no'=>$ddl_case_no,
                'ddl_tot_accused'=>$ddl_tot_accused,
                'sp_judgement_dt'=>$sp_judgement_dt,
            ];

            if (!$this->validation->run($validation)) {
                // handle validation errors
                echo '3@@@';
                echo $this->validation->listErrors();exit();
            }
            $sen_time='';
            if (!empty($sp_judgement_dt) && !empty($m_sent2) && !empty($m_sent2_mon)){
                $sen_time=date('d-m-Y',strtotime($sp_judgement_dt.'+'.$m_sent2.' years'));
                $sen_time=date('d-m-Y',strtotime($sen_time.'+'.$m_sent2_mon.' months'));
            }
            if (!empty($period_under)){
                $ex_period_under=  explode(',', $period_under);
                for ($index = 0; $index < count($ex_period_under); $index++) {
                    $in_exp=  explode('@', $ex_period_under[$index]);
                    if($in_exp[0]!='')
                    {
                        if(((strtotime($txt_frm_dt)>=  strtotime($in_exp[0]) && strtotime($txt_frm_dt)<=  strtotime($in_exp[1])) && $in_exp[0]!='') ||  (strtotime($txt_to_dt)>=  strtotime($in_exp[0]) && strtotime($txt_to_dt)<=  strtotime($in_exp[1])))
                        {
                            /*echo "3@@@<span class='text-danger'>Period already entered. Please select proper from and to date!</span>";
                            exit();*/
                        }
                    }
                }
            }

            $data = [
                'diary_no'=>$diary_no,
                'm_status'=>$m_status,
                'txt_frm_dt'=>$txt_frm_dt,
                'txt_to_dt'=>$txt_to_dt,
                'ddl_case_no'=>$ddl_case_no,
                'cnt_rw'=>$cnt_rw,
                'ddl_tot_accused'=>$ddl_tot_accused,
                'sp_judgement_dt'=>$sp_judgement_dt,
                'remarks'=>$remarks,
                'm_sent2'=>$m_sent2,
                'm_sent2_mon'=>$m_sent2_mon,
                'period_under'=>$period_under,
                'sen_time'=>$sen_time,
            ];

            $is_sentence_period= $this->Model_sentence->is_sentence_period($data);

            if (!empty($is_sentence_period)){
                if(!empty($is_sentence_period['frm_date']) && !empty($is_sentence_period['to_date']))
                {
                    $is_sentence_period['frm_date']=date('d-m-Y',  strtotime($is_sentence_period['frm_date']));
                    $is_sentence_period['to_date']=date('d-m-Y',  strtotime($is_sentence_period['to_date']));
                    if((strtotime($txt_frm_dt)<=strtotime($is_sentence_period['frm_date']) && strtotime($txt_frm_dt)>=strtotime($is_sentence_period['to_date']))
                        || strtotime($txt_to_dt)<=strtotime($is_sentence_period['frm_date']) && strtotime($txt_to_dt)>=strtotime($is_sentence_period['to_date']))
                    {
                        //echo "3@@@<span class='text-danger'>Period already entered. Please select proper from and to date</span>";exit();
                    }
                }
            }
            $this->db = \Config\Database::connect();
            $this->db->transStart();
            $get_sentence_period= $this->Model_sentence->get_sentence_period($data);
            //$effected_sentence_period=true;
            if (empty($get_sentence_period)){
                $sentence_period_insert = [
                    'diary_no'=>$diary_no,
                    'sentence_yr'=>!empty($m_sent2) ? $m_sent2 :0,
                    'ucode'=>$_SESSION['login']['usercode'],
                    'entdt'=>date("Y-m-d H:i:s"),
                    'sentence_mth'=>!empty($m_sent2_mon) ? $m_sent2_mon :0,
                    'lower_court_id'=>$ddl_case_no,
                    'accused_id'=>$ddl_tot_accused,
                    'display'=>'Y',

                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_by'=>$_SESSION['login']['usercode'],
                    'updated_by_ip'=>getClientIP()
                ];
                $effected_sentence_period = insert('sentence_period', $sentence_period_insert);
            }else{
                $sentence_period_update = [
                    'sentence_yr'=>!empty($m_sent2) ? $m_sent2 :0,
                    'ucode'=>$_SESSION['login']['usercode'],
                    'entdt'=>date("Y-m-d H:i:s"),
                    'sentence_mth'=>!empty($m_sent2_mon) ? $m_sent2_mon :0,

                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by'=>$_SESSION['login']['usercode'],
                    'updated_by_ip'=>getClientIP()
                ];
                $effected_sentence_period = update('sentence_period', $sentence_period_update, ['diary_no' => $diary_no,'lower_court_id' => $ddl_case_no,'accused_id' => $ddl_tot_accused,'display' => 'Y']);
            }
            if ($effected_sentence_period){
                $check_sentence_period= $this->Model_sentence->get_sentence_period($data);
                if (!empty($check_sentence_period)){
                    $sentence_period_id=$check_sentence_period['id'];
                    $ex_period_under=  explode(',',$period_under);
                    for ($index = 0; $index < count($ex_period_under); $index++) {
                        $in_exp=  explode('@', $ex_period_under[$index]);
                        $frm_dt=date('Y-m-d',  strtotime($in_exp[1]));
                        $to_dt=date('Y-m-d',  strtotime($in_exp[2]));
                        if($in_exp[3]=='')
                        {
                            $sentence_undergone_insert = [
                                'sentence_period_id'=>$sentence_period_id,
                                'status'=>$in_exp[0],
                                'usercode'=>$_SESSION['login']['usercode'],
                                'entdt'=>date("Y-m-d H:i:s"),
                                'frm_date'=>$frm_dt,
                                'to_date'=>$to_dt,
                                'sen_display'=>'Y',
                                'rem'=>$remarks,

                                'create_modify' => date("Y-m-d H:i:s"),
                                'updated_by'=>$_SESSION['login']['usercode'],
                                'updated_by_ip'=>getClientIP()
                            ];
                            $effected_sentence_undergone_insert = insert('sentence_undergone', $sentence_undergone_insert);

                        }
                        else
                        {

                        }

                        $sno=1;
                    }
                }
            }
            $this->db->transComplete();
            $get_view_result='<span class="text-success">Data Inserted Successfully</span>';
            echo '1@@@'.$get_view_result;exit();

        }
    }
    public function get_details(){
        $data['sentence_period_id']=$sentence_period_id='';
        $data['res_max_to_dt']='';
        $data['sentence_undergone_list']=array();
        $diary_no=$_REQUEST['diary_no'];
        $ddl_case_no=$_REQUEST['ddl_case_no'];
        $ddl_tot_accused=$_REQUEST['ddl_tot_accused'];
        $params['diary_no'] = $diary_no;
        $params['ddl_case_no'] = $ddl_case_no;
        $params['ddl_tot_accused'] =$ddl_tot_accused;
        $data['lct_dec_dt']= $this->Model_sentence->get_lct_dec_dt($params);
        $data['get_details']= $this->Model_sentence->get_details($params);
        if (!empty($data['get_details'])){
            $sentence_period_id=$data['get_details']['id'];
            $get_res_max_to_dt= $this->Model_sentence->get_max_to_dt($sentence_period_id);
            if (!empty($get_res_max_to_dt)){
                $res_max_to_dt=date('Y-m-d',  strtotime($get_res_max_to_dt['res_max_to_dt']. '+1 days'));
                $data['res_max_to_dt']=$res_max_to_dt;
            }
            $data['sentence_undergone_list']= $this->Model_sentence->get_sentence_undergone_list($sentence_period_id);
            $data['sentence_period_id']=$sentence_period_id;
        }

        $get_view= view('Judicial/Sentence/get_sentence_view_details',$data);
        echo '1@@@'.$sentence_period_id.'@@@'.$get_view;
    }

    public function get_from_court_by_diary_no()
    {
        $diary_no=$_REQUEST['diary_no'];
        $ddl_court=$_REQUEST['ddl_court'];
        $from_cout=$this->Model_sentence->get_from_court_by_diary_no($diary_no);
        //echo '<pre>';print_r($from_cout);exit();
        $selected = "";
        $dropDownOptions = '<option value="">Select Court Type</option>';
        if (!empty($from_cout) && !empty($from_cout)) {
            foreach ($from_cout as $row) {
                if (!empty($ddl_court)) {
                    if ($ddl_court == $row['ct_code']) {
                        $selected = "selected";
                    } else {
                        $selected = "";
                    }
                }
                $dropDownOptions .= '<option value="' . $row['ct_code'] . '" ' . $selected . '>' . $row['court_name'] . '</option>';
            }
        }
        return $dropDownOptions;

    }
    public function get_state_name()
    {
        $diary_no=$_REQUEST['diary_no'];
        $ddl_court=$_REQUEST['ddl_court'];
        if (!isset($_REQUEST['ddl_st_agncy'])){$_REQUEST['ddl_st_agncy']='';}else{ $_REQUEST['ddl_st_agncy']; }
        $id_no=$_REQUEST['ddl_st_agncy'];
        $from_data=$this->Model_sentence->get_state_name($diary_no,$ddl_court);
        //echo '<pre>';print_r($from_cout);exit();
        $selected = "";
        $dropDownOptions = '<option value="">Select Court Type</option>';
        if (!empty($from_data) && !empty($from_data)) {
            foreach ($from_data as $row) {
                if (!empty($id_no)) {
                    if ($id_no == $row['id_no']) {
                        $selected = "selected";
                    } else {
                        $selected = "";
                    }
                }
                $dropDownOptions .= '<option value="' . $row['id_no'] . '" ' . $selected . '>' . $row['name'] . '</option>';
            }
        }
        return $dropDownOptions;

    }
    public function get_index_bench()
    {
        if (!isset($_REQUEST['bench_id'])){$_REQUEST['bench_id']='';}else{ $_REQUEST['bench_id']; }
        $bench_id=$_REQUEST['bench_id'];

        $diary_no=$_REQUEST['diary_no'];
        $ddl_court=$_REQUEST['ddl_court'];
        $ddl_st_agncy=$_REQUEST['ddl_st_agncy'];

        $params = array();
        $params['diary_no'] = $diary_no;
        $params['court_type'] = $ddl_court;
        $params['cmis_state_id'] =$ddl_st_agncy;
        $params['bench_id'] =$bench_id;

        $from_data=$this->Model_sentence->get_sentence_bench($params);
        $selected = "";
        $dropDownOptions = '<option value="">Select Bench Type</option>';
        if (!empty($from_data) && !empty($from_data)) {
            foreach ($from_data as $row) {
                if (!empty($bench_id)) {
                    if ($bench_id == $row['id']) {
                        $selected = "selected";
                    } else {
                        $selected = "";
                    }
                }
                if($ddl_court==3) {
                    $dropDownOptions .= '<option value="' . $row['id'] . '" ' . $selected . '>' . trim($row['agency_name']) . '</option>';
                }else{
                    $dropDownOptions .= '<option value="' . $row['id'] . '" ' . $selected . '>' . trim($row['agency_name']).'::'.$row['short_agency_name'] . '</option>';
                }
            }
        }
        return $dropDownOptions;

    }

    public function get_tot_cases()
    {
        if (!isset($_REQUEST['ddl_case_no'])){$_REQUEST['ddl_case_no']='';}else{ $_REQUEST['ddl_case_no']; }
        $ddl_case_no=$_REQUEST['ddl_case_no'];

        $diary_no=$_REQUEST['diary_no'];
        $ddl_court=$_REQUEST['ddl_court'];
        $ddl_st_agncy=$_REQUEST['ddl_st_agncy'];
        $bench_id=$_REQUEST['ddl_bench'];
        $params = array();
        $params['diary_no'] = $diary_no;
        $params['court_type'] = $ddl_court;
        $params['cmis_state_id'] =$ddl_st_agncy;
        $params['ddl_bench'] =$bench_id;

        $from_data=$this->Model_sentence->get_tot_cases($params);
        $selected = "";
        $dropDownOptions = '<option value="">Select</option>';
        if (!empty($from_data) && !empty($from_data)) {
            foreach ($from_data as $row) {
                if (!empty($ddl_case_no)) {
                    if ($ddl_case_no == $row['lower_court_id']) {
                        $selected = "selected";
                    } else {
                        $selected = "";
                    }
                }
                $dropDownOptions .= '<option value="' . $row['lower_court_id'] . '" ' . $selected . '>' . trim($row['display_name']). '</option>';
            }
        }
        return $dropDownOptions;
    }

    public function get_tot_accused()
    {
        if (!isset($_REQUEST['ddl_tot_accused'])){$_REQUEST['ddl_tot_accused']='';}else{ $_REQUEST['ddl_tot_accused']; }
        $ddl_tot_accused=$_REQUEST['ddl_tot_accused'];

        $diary_no=$_REQUEST['diary_no'];
        $ddl_court=$_REQUEST['ddl_court'];
        $ddl_st_agncy=$_REQUEST['ddl_st_agncy'];
        $bench_id=$_REQUEST['ddl_bench'];
        $ddl_case_no=$_REQUEST['ddl_case_no'];
        $params = array();
        $params['diary_no'] = $diary_no;
        $params['court_type'] = $ddl_court;
        $params['cmis_state_id'] =$ddl_st_agncy;
        $params['ddl_bench'] =$bench_id;
        $params['ddl_case_no'] =$ddl_case_no;

        $from_data=$this->Model_sentence->get_tot_accused($params);
        $selected = "";
        $dropDownOptions = '<option value="">Select</option>';
        if (!empty($from_data) && !empty($from_data)) {
            foreach ($from_data as $row) {
                if (!empty($ddl_tot_accused)) {
                    if ($ddl_tot_accused == $row['party_id']) {
                        $selected = "selected";
                    } else {
                        $selected = "";
                    }
                }
                $dropDownOptions .= '<option value="' . $row['party_id'] . '" ' . $selected . '>' . trim($row['partyname']). '</option>';
            }
        }
        return $dropDownOptions;
    }
}