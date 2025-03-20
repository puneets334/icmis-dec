<?php

namespace App\Controllers\Filing;
use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Filing\RegistrationModel;

class Registration extends BaseController
{
    
    public $model;
    public $diary_no;

    function __construct()
    {   
        $this->model = new RegistrationModel();

        if(empty(session()->get('filing_details')['diary_no'])){
            $uri = current_url(true);
            //$getUrl = $uri->getSegment(1).'-'.$uri->getSegment(2);
            $getUrl = str_replace('/', '-', $uri->getPath());
            header('Location:'.base_url('Filing/Diary/search?page_url='.base64_encode($getUrl)));exit();
        }else{
            $this->diary_no = session()->get('filing_details')['diary_no'];
        }
    }

    public function index()
    {
        $diary_no = $this->diary_no;

        $data['res_p_r'] = $this->model->get_main($diary_no);
        //echo $data['res_p_r']['casetype_id'];
        //pr($data['res_p_r']);
        $data['category'] = $this->model->get_mul_category($diary_no);
        $data['res_casetype_added'] = $this->model->get_casetype($data['res_p_r'][0]['casetype_id']);
        $data['res_ck_def'] = $this->model->get_obj_save($diary_no);
        $data['check_ia'] = $this->model->get_docdetails_docmaster($diary_no,$data['res_p_r'][0]['casetype_id']);

        $data['get_causetitle'] = $this->get_causetitle();

        $tbl = is_table_a('heardt');

        $get_order_dt = $this->model->get_order_dt($diary_no,$tbl);

        $order_date = '';
        if($get_order_dt){
            !empty($get_order_dt)?$order_date = $get_order_dt[0]['order_dt']:'';
        }else{
            $get_order_dt = $this->model->get_order_dt($diary_no,'last_heardt');
            !empty($get_order_dt)?$order_date = $get_order_dt[0]['order_dt']:'';
            
        }

        $data['order_date'] = $order_date;

        $get_lowerct = $this->model->get_lowerct($diary_no,$data['res_p_r'][0]['casetype_id']);

        if($get_lowerct){
            $data['res_ck_def'] = $this->model->get_obj_save($diary_no);
        }

        $data['get_lowerct'] = $get_lowerct;

        return view('Filing/registration_view',$data);
    }

    public function get_causetitle(){

        $diary_no = $this->diary_no;

        $cause_title='';

        $cause_title_arr = $this->model->get_causetitle($diary_no);

        $cause_title=$cause_title.$cause_title_arr[0]['pet_name'];

        if($cause_title_arr[0]['pno']==2){
            $cause_title=$cause_title."<font color='blue'> AND ANR </font>";
        }
        else if($cause_title_arr[0]['pno']>2){
            $cause_title=$cause_title."<font color='blue'> AND ORS </font>";
        }
        $cause_title=$cause_title."<font color=blue> VS </font>".$cause_title_arr[0]['res_name'];
        if($cause_title_arr[0]['rno']==2){
            $cause_title=$cause_title."<font color='blue'> AND ANR </font>";
        }
        else if($cause_title_arr[0]['rno']>2){
            $cause_title=$cause_title."<font color='blue'> AND ORS </font>";
        }

        return $cause_title;
    }

    public function register_case(){

        $diary_no = $this->diary_no;

        $year=date('Y');

        $registration_date='';
        if($this->request->getPost('reg_for_year')!=0){
            $year = $this->request->getPost('reg_for_year');
        }

        $hd_casetype_id = $this->request->getPost('hd_casetype_id');
        $num = $this->request->getPost('num');
        $fn_val = $this->request->getPost('fn_val');

        if($this->request->getPost('txt_order_dt')==''){
            $txt_order_dt=NULL;
        }else{
            $txt_order_dt=date('Y-m-d',strtotime($this->request->getPost('txt_order_dt')));
        }

        $f_no=0;
        $l_no=0;
        $s_no=0;
        $cnt_total=0;

        $get_kounter = $this->model->get_kounter($year,$hd_casetype_id);

        if($get_kounter){
            $res_case_ct = $get_kounter[0]['knt'];
        }else{
            $get_last_reg_no = $this->model->get_last_reg_no($year,$hd_casetype_id);
            $last_reg_no = !empty($get_last_reg_no[0]['fil_no'])?$get_last_reg_no[0]['fil_no']:0;
            $pos=strrpos($last_reg_no,'-',0);
            $last_reg_no= substr($last_reg_no, $pos+1);
            $res_case_ct=!empty(ltrim($last_reg_no,'0'))?ltrim($last_reg_no,'0'):0;

            $ins_arr = [
                'year'          =>      $year,
                'knt'           =>      $res_case_ct,
                'casetype_id'   =>      $hd_casetype_id,
                'create_modify' =>      date("Y-m-d H:i:s"),
                'updated_on'    =>      date("Y-m-d H:i:s"),
                'updated_by'    =>      session()->get('login')['usercode'],
                'updated_by_ip' =>      getClientIP()
            ];

            $this->model->insert_kounter($ins_arr);
        }

        // in case registration is for previous year
        if($this->request->getPost('reg_for_year')!=0){
            $registration_date=$txt_order_dt;
            $reg_no=0;
            if(strlen($res_case_ct)<6)
            {
                $length=strlen($res_case_ct);
                $reg_no=intval($res_case_ct)+1;
                for ($index = $length; $index < 6; $index++) {
                    $reg_no='0'.$reg_no;
                }
            }
            $check_reg_no = $this->model->check_reg_no($reg_no,$year,$hd_casetype_id);

            if($check_reg_no > 0){
                $get_last_reg_no = $this->model->get_last_reg_no($year,$hd_casetype_id);
                $max_regno = $get_last_reg_no[0]['fil_no'];

                $pos=strrpos($max_regno,'-',0);
                $max_regno= substr($max_regno, $pos+1);
                $res_case_ct=ltrim($max_regno,'0');
            }

        }else{
            $registration_date=date('Y-m-d H:i:s');
        }

        if($num =='' || $num==0){
            $ex_explode=explode('@',$fn_val);
        }else{

            $fn='';
            for($i=0;$i < $num ;$i++)
            {
               //"6502602!Y@6502603!Y"
               $fn=$fn.   $i.'!Y@';
            }

            $x = substr($fn, 0, -1);
            $ex_explode=explode('@',$x);
        }

        $cnt_no=$res_case_ct;

        for($i = 0; $i < count($ex_explode); $i++){

            $sub_exp=explode('!',$ex_explode[$i]);
            if($sub_exp[1]=='Y')
            {
                $cnt_no++;
                $cnt_total++;
            }  
        }


        $upd_case_ct = $this->model->update_kounter(['knt'=>$cnt_no,'create_modify'=>date("Y-m-d H:i:s"),'updated_on'=>date("Y-m-d H:i:s"),'updated_by'=>session()->get('login')['usercode'],'updated_by_ip'=>getClientIP()],$year,$hd_casetype_id);

        if($upd_case_ct){

            for($i = 0; $i < count($ex_explode); $i++){

                $sub_exp=explode('!',$ex_explode[$i]);
                if($sub_exp[1]=='Y')
                {
                    
                    $res_case_ct++;
                }

                if($this->request->getPost('hd_casetype_id')!='7' && $this->request->getPost('hd_casetype_id')!='8' && $this->request->getPost('hd_casetype_id')!='19' && $this->request->getPost('hd_casetype_id')!='20' && $this->request->getPost('hd_casetype_id')!='11' && $this->request->getPost('hd_casetype_id')!='12'){

                    $get_lowerct = $this->model->get_lowerct_by_lower_court_id($sub_exp[0],$sub_exp[1]);

                    if($get_lowerct[0]['count'] <= 0){

                        $this->model->update_lowerct(['is_order_challenged'=>$sub_exp[1],'create_modify'=>date("Y-m-d H:i:s"),'updated_on'=>date("Y-m-d H:i:s"),'updated_by'=>session()->get('login')['usercode'],'updated_by_ip'=>getClientIP()],$sub_exp[0]);
                    }
                }

                if($sub_exp[1]=='Y'){

                    $hd_casetype_id=strlen($this->request->getPost('hd_casetype_id'));

                    $app_zero_ct='';
                    if($hd_casetype_id<2){

                        for($index = $hd_casetype_id; $index < 2; $index++) {
                            if($app_zero_ct=='')
                                $app_zero_ct='0';
                            else 
                                $app_zero_ct=$app_zero_ct.'0';
                        }
                    }

                    $hd_casetype_id1=$app_zero_ct.$this->request->getPost('hd_casetype_id');

                    $hd_res_case_ct=strlen($res_case_ct);

                    $app_zero_cno='';
                    if($hd_res_case_ct<6)
                    {
                        
                        for($index = $hd_res_case_ct; $index < 6; $index++) {
                            if($app_zero_cno=='')
                                $app_zero_cno='0';
                            else 
                                $app_zero_cno=$app_zero_cno.'0';
                        }
                    }
                    $hd_res_case_ct1=$app_zero_cno.$res_case_ct;

                    $fil_no=$hd_casetype_id1.$hd_res_case_ct1.$year;

                    $ins_arr = [
                                'lowerct_id'        =>      $sub_exp[0],
                                'diary_no'          =>      $diary_no,
                                'fil_no'            =>      $fil_no,
                                'entuser'           =>      session()->get('login')['usercode'],
                                'entdt'             =>      'now()',
                                'casetype_id'       =>      $this->request->getPost('hd_casetype_id'),
                                'case_no'           =>      $res_case_ct,
                                'case_year'         =>      $year,
                                'create_modify'     =>      date("Y-m-d H:i:s"),
                                'updated_on'        =>      date("Y-m-d H:i:s"),
                                'updated_by'        =>      session()->get('login')['usercode'],
                                'updated_by_ip'     =>      getClientIP()
                                ];

                    $this->model->insert_registered_cases($ins_arr);
                    $s_no++;

                    if($s_no==1){
                        $f_no=substr($fil_no,2,6);
                    }
                    elseif($s_no==$cnt_total){
                        $l_no=substr($fil_no,2,6);
                    }

                }
            }

            if($l_no==0){
                $reg_no= $hd_casetype_id1.'-'.$f_no;
            }else{
                $reg_no= $hd_casetype_id1.'-'.$f_no.'-'.$l_no;
            }

            $res_cur_det = $this->model->get_cur_date($diary_no);

            $pre_case_type = 0;
            if(!empty($res_cur_det[0]['fil_no'])){
                $pre_case_type=substr($res_cur_det[0]['fil_no'],0,2);
            }

            $res_sel_his = $this->model->get_main_casetype_history($diary_no,$res_cur_det[0]['fil_no'],$res_cur_det[0]['fil_dt'],$reg_no,$year,$txt_order_dt);

            if($res_sel_his[0]['count']<=0){
                $ins_arr = [
                            'diary_no'                     =>      $diary_no,
                            'old_registration_number'      =>      $res_cur_det[0]['fil_no'],
                            'old_registration_year'        =>      $res_cur_det[0]['fil_dt'],
                            'new_registration_number'      =>      $reg_no,
                            'new_registration_year'        =>      $year,
                            'order_date'                   =>      $txt_order_dt,
                            'ref_old_case_type_id'         =>      $pre_case_type,
                            'ref_new_case_type_id'         =>      $this->request->getPost('hd_casetype_id'),
                            'adm_updated_by'               =>      session()->get('login')['usercode'],
                            'updated_on'                   =>      'now()',
                            'is_deleted'                   =>      'f',
                            'create_modify'                =>       date("Y-m-d H:i:s"),
                            'updated_by'                   =>       session()->get('login')['usercode'],
                            'updated_by_ip'                =>       getClientIP()
                            ];

                $upd_his = $this->model->insert_main_casetype_history($ins_arr);

                if($upd_his){

                    $regNoDisplay= $this->getRegistrationNumberDisplay($diary_no,$reg_no,$year);

                    $upd_arr = [
                            'fil_no'                =>      $reg_no,
                            'fil_dt'                =>      $registration_date,
                            'usercode'              =>      session()->get('login')['usercode'],
                            'mf_active'             =>      'M',
                            'active_fil_no'         =>      $reg_no,
                            'active_fil_dt'         =>      $registration_date,
                            'active_reg_year'       =>      $year,
                            'active_casetype_id'    =>      $this->request->getPost('hd_casetype_id'),
                            'reg_no_display'        =>      !empty($regNoDisplay)?$regNoDisplay:'',
                            'create_modify'         =>      date("Y-m-d H:i:s"),
                            'updated_on'            =>      date("Y-m-d H:i:s"),
                            'updated_by'            =>      session()->get('login')['usercode'],
                            'updated_by_ip'         =>      getClientIP()
                            ];

                    $this->model->update_main($upd_arr,$diary_no);
                }
            }

            $skey = $this->model->get_casetype($this->request->getPost('hd_casetype_id'));

            $res_skey = $skey[0]['short_description'];

            $registration_arr = [];
            $track_inserted_arr = [];
            if($l_no==0){
                $registration_arr = ["registration" => "Registration No.: ".$res_skey.'-'.$f_no."/".$year];
            }else{
                $registration_arr = ["registration" => "Registration No.: ".$res_skey.'-'.$f_no.'-'.$l_no."/".$year];
            }

            if($num<>0||$this->request->getPost('reg_for_year')!=0){

                $ins_arr = [
                            'diary_no'                       =>      $diary_no,
                            'num_to_register'                =>      $num,
                            'registration_number_alloted'    =>      $reg_no,
                            'registration_year'              =>      $year,
                            'usercode'                       =>      session()->get('login')['usercode'],
                            'reg_date'                       =>      'NOW()',
                            'create_modify'                  =>      date("Y-m-d H:i:s"),
                            'updated_on'                     =>      date("Y-m-d H:i:s"),
                            'updated_by'                     =>      session()->get('login')['usercode'],
                            'updated_by_ip'                  =>      getClientIP()
                            ];

                $ins_registration_track = $this->model->insert_registration_track($ins_arr);

                if($ins_registration_track){
                    $track_inserted_arr = ["track_inserted" => "Track maintained Successfully"];
                }
            }


            $sms_status='R';

            $err_msg_arr = [];
            $send_sms = $this->send_sms($sms_status);
            $err_msg_arr = ["err_msg"=>$send_sms];

            echo json_encode(array_merge($registration_arr,$track_inserted_arr,$err_msg_arr));

        }
        
    }


    public function register_case_supreme(){

        $diary_no = $this->diary_no;

        $year=date('Y');

        $registration_date='';
        if($this->request->getPost('reg_for_year')!=0){
            $year = $this->request->getPost('reg_for_year');
        }

        $hd_casetype_id = $this->request->getPost('hd_casetype_id');

        if($this->request->getPost('txt_order_dt')==''){
            $txt_order_dt=NULL;
        }else{
            $txt_order_dt=date('Y-m-d',strtotime($this->request->getPost('txt_order_dt')));
        }

        $get_kounter = $this->model->get_kounter($year,$hd_casetype_id);

        if($get_kounter){
            $res_case_ct = $get_kounter[0]['knt'];
        }else{
            $get_last_reg_no = $this->model->get_last_reg_no($year,$hd_casetype_id);
            $last_reg_no = !empty($get_last_reg_no[0]['fil_no'])?$get_last_reg_no[0]['fil_no']:0;
            $pos=strrpos($last_reg_no,'-',0);
            $last_reg_no= substr($last_reg_no, $pos+1);
            $res_case_ct=!empty(ltrim($last_reg_no,'0'))?ltrim($last_reg_no,'0'):0;

            $ins_arr = [
                'year'          =>      $year,
                'knt'           =>      $res_case_ct,
                'casetype_id'   =>      $hd_casetype_id,
                'create_modify' =>      date("Y-m-d H:i:s"),
                'updated_on'    =>      date("Y-m-d H:i:s"),
                'updated_by'    =>      session()->get('login')['usercode'],
                'updated_by_ip' =>      getClientIP()
            ];

            $this->model->insert_kounter($ins_arr);
        }

        // in case registration is for previous year
        if($this->request->getPost('reg_for_year')!=0){
            $registration_date=$txt_order_dt;
            $reg_no=0;
            if(strlen($res_case_ct)<6)
            {
                $length=strlen($res_case_ct);
                $reg_no=intval($res_case_ct)+1;
                for ($index = $length; $index < 6; $index++) {
                    $reg_no='0'.$reg_no;
                }
            }
            $check_reg_no = $this->model->check_reg_no($reg_no,$year,$hd_casetype_id);

            if($check_reg_no > 0){
                $get_last_reg_no = $this->model->get_last_reg_no($year,$hd_casetype_id);
                $max_regno = $get_last_reg_no[0]['fil_no'];

                $pos=strrpos($max_regno,'-',0);
                $max_regno= substr($max_regno, $pos+1);
                $res_case_ct=ltrim($max_regno,'0');
            }

        }else{
            $registration_date=date('Y-m-d H:i:s');
        }

        $cnt_no=$res_case_ct+1;


        $upd_case_ct = $this->model->update_kounter(['knt'=>$cnt_no,'create_modify'=>date("Y-m-d H:i:s"),'updated_on'=>date("Y-m-d H:i:s"),'updated_by'=>session()->get('login')['usercode'],'updated_by_ip'=>getClientIP()],$year,$hd_casetype_id);

        if($upd_case_ct){

            $hd_casetype_id=strlen($this->request->getPost('hd_casetype_id'));

            $app_zero_ct='';
            if($hd_casetype_id<2){

                for($index = $hd_casetype_id; $index < 2; $index++) {
                    if($app_zero_ct=='')
                        $app_zero_ct='0';
                    else 
                        $app_zero_ct=$app_zero_ct.'0';
                }
            }

            $hd_casetype_id1=$app_zero_ct.$this->request->getPost('hd_casetype_id');

            $hd_res_case_ct=strlen($cnt_no);

            $app_zero_cno='';
            if($hd_res_case_ct<6)
            {
                
                for($index = $hd_res_case_ct; $index < 6; $index++) {
                    if($app_zero_cno=='')
                        $app_zero_cno='0';
                    else 
                        $app_zero_cno=$app_zero_cno.'0';
                }
            }
            $hd_res_case_ct1=$app_zero_cno.$cnt_no;

            $fil_no=$hd_casetype_id1.$hd_res_case_ct1.$year;

            $f_no=substr($fil_no,2,6);
            
            $reg_no= $hd_casetype_id1.'-'.$f_no;

            $res_cur_det = $this->model->get_cur_date($diary_no);

            $pre_case_type = 0;
            if(!empty($res_cur_det[0]['fil_no'])){
                $pre_case_type=substr($res_cur_det[0]['fil_no'],0,2);
            }

            $res_sel_his = $this->model->get_main_casetype_history($diary_no,$res_cur_det[0]['fil_no'],$res_cur_det[0]['fil_dt'],$reg_no,$year,$txt_order_dt);

            if($res_sel_his[0]['count']<=0){
                $ins_arr = [
                            'diary_no'                     =>      $diary_no,
                            'old_registration_number'      =>      $res_cur_det[0]['fil_no'],
                            'old_registration_year'        =>      $res_cur_det[0]['fil_dt'],
                            'new_registration_number'      =>      $reg_no,
                            'new_registration_year'        =>      $year,
                            'order_date'                   =>      $txt_order_dt,
                            'ref_old_case_type_id'         =>      $pre_case_type,
                            'ref_new_case_type_id'         =>      $this->request->getPost('hd_casetype_id'),
                            'adm_updated_by'               =>      session()->get('login')['usercode'],
                            'updated_on'                   =>      'now()',
                            'is_deleted'                   =>      'f',
                            'create_modify'                =>       date("Y-m-d H:i:s"),
                            'updated_by'                   =>       session()->get('login')['usercode'],
                            'updated_by_ip'                =>       getClientIP()
                            ];

                $upd_his = $this->model->insert_main_casetype_history($ins_arr);
            }

            $ins_arr = [
                        'lowerct_id'        =>      '0',
                        'diary_no'          =>      $diary_no,
                        'fil_no'            =>      $fil_no,
                        'entuser'           =>      session()->get('login')['usercode'],
                        'entdt'             =>      'now()',
                        'casetype_id'       =>      $this->request->getPost('hd_casetype_id'),
                        'case_no'           =>      $cnt_no,
                        'case_year'         =>      $year,
                        'create_modify'     =>      date("Y-m-d H:i:s"),
                        'updated_on'        =>      date("Y-m-d H:i:s"),
                        'updated_by'        =>      session()->get('login')['usercode'],
                        'updated_by_ip'     =>      getClientIP()
                        ];

            $this->model->insert_registered_cases($ins_arr);

            $regNoDisplay= $this->getRegistrationNumberDisplay($diary_no,$reg_no,$year);

            $upd_arr = [
                    'fil_no'                =>      $reg_no,
                    'fil_dt'                =>      $registration_date,
                    'usercode'              =>      session()->get('login')['usercode'],
                    'mf_active'             =>      'M',
                    'active_fil_no'         =>      $reg_no,
                    'active_fil_dt'         =>      $registration_date,
                    'active_reg_year'       =>      $year,
                    'active_casetype_id'    =>      $this->request->getPost('hd_casetype_id'),
                    'reg_no_display'        =>      !empty($regNoDisplay)?$regNoDisplay:'',
                    'create_modify'         =>      date("Y-m-d H:i:s"),
                    'updated_on'            =>      date("Y-m-d H:i:s"),
                    'updated_by'            =>      session()->get('login')['usercode'],
                    'updated_by_ip'         =>      getClientIP()
                    ];

            $this->model->update_main($upd_arr,$diary_no);

            $skey = $this->model->get_casetype($this->request->getPost('hd_casetype_id'));

            $res_skey = $skey[0]['short_description'];

            $registration_arr = [];
            $track_inserted_arr = [];

            $registration_arr = ["registration" => "Registration No.: ".$res_skey.'-'.$f_no."/".$year];

            if($this->request->getPost('reg_for_year')!=0){

                $ins_arr = [
                            'diary_no'                       =>      $diary_no,
                            'registration_number_alloted'    =>      $reg_no,
                            'registration_year'              =>      $year,
                            'usercode'                       =>      session()->get('login')['usercode'],
                            'reg_date'                       =>      'NOW()',
                            'create_modify'                  =>      date("Y-m-d H:i:s"),
                            'updated_on'                     =>      date("Y-m-d H:i:s"),
                            'updated_by'                     =>      session()->get('login')['usercode'],
                            'updated_by_ip'                  =>      getClientIP()
                            ];

                $ins_registration_track = $this->model->insert_registration_track($ins_arr);

                if($ins_registration_track){
                    $track_inserted_arr = ["track_inserted" => "Track maintained Successfully"];
                }
            }

            $sms_status='R';

            $err_msg_arr = [];
            $send_sms = $this->send_sms($sms_status);
            $err_msg_arr = ["err_msg"=>$send_sms];

            echo json_encode(array_merge($registration_arr,$track_inserted_arr,$err_msg_arr));

        }
        
    }


    function getRegistrationNumberDisplay($diary_no,$registrationNumber,$registrationYear){

        $previousRegistrationNumber = $regNoDisplay = "";
        $caseType = substr($registrationNumber, 0, 2);
        $reg1 = substr($registrationNumber, 3, 6);

        if(strlen($registrationNumber)>9)
            $reg2 = substr($registrationNumber, 10, 6);
        else
            $reg2 = substr($registrationNumber, 3, 6);

            $row = $this->model->get_casetype($caseType);

            $res_ct_typ = $row[0]['short_description'];
            $res_ct_typ_mf = $row[0]['cs_m_f'];

            if ($caseType == 9 || $caseType == 10 || $caseType == 19 || $caseType == 20 || $caseType == 25 || $caseType == 26 || $caseType == 39) {
                $row_result = $this->model->get_reg_no_display_from_main($diary_no);
                $previousRegistrationNumber = !empty($row_result[0]['reg_no_display'])?$row_result[0]['reg_no_display']:'';
            }

            if($reg1 == $reg2){
                $regNoDisplay = $res_ct_typ . " " . (int)$reg1 . '/' . $registrationYear;
            }else{
                $regNoDisplay = $res_ct_typ . " " . (int)$reg1 . '-' . (int)$reg2 . '/' . $registrationYear;
            }

            if($previousRegistrationNumber != "" && $previousRegistrationNumber != null) {
                $regNoDisplay .= " in " . $previousRegistrationNumber;
            }
            return $regNoDisplay;

    }

    function send_sms($sms_status){

        $diary_no = $this->diary_no;

        $frm='';
        $template_id='';
        $res_skey='';
        $f_no='';
        $year='';

        if($sms_status=='R'){
            $frm='Registration';
            $template_id='1107165881515458494';
            $res_sql_obj=  1;
        }

        if($res_sql_obj>0){
            if($res_sql_obj==1){
                $mobileno = '';

                if($sms_status == 'R'){

                    $r_get_pet_res = $this->model->get_main($diary_no);

                    $pet=$r_get_pet_res[0]['pet_name'];
                    $res=$r_get_pet_res[0]['res_name'];

                    if(strlen($pet)>30){
                        $pet=str_replace(substr($pet, 27, strlen($pet)),'...',$pet) ;
                    }
                    if(strlen($res)>30){
                        $res=str_replace(substr($res, 27, strlen($res)),'...',$res) ;
                    }
                    $testmsg="The case filed by you with Diary No. ".$diary_no." - ".$pet." VS ".$res." is successfully registered with registration no. ".$res_skey.'-'.$f_no."/".$year.". - Supreme Court of India";
                }

                $r_party = $this->model->get_party($diary_no);

                foreach($r_party as $r_party_val):

                    if($r_party_val['contact'] != '' && strlen($r_party_val['contact']) == '10'){
                        if($mobileno == ''){
                            $mobileno = $r_party_val['contact'];
                        }
                        else{
                            $mobileno = $mobileno . ',' . $r_party_val['contact'];
                        }
                    }

                endforeach;

                $advocate_mob = $this->model->advocate_mob($diary_no);

                foreach($advocate_mob as $advocate_mob_val):

                    if($advocate_mob_val['mobile'] != '' && strlen($advocate_mob_val['mobile']) == '10'){
                        if($mobileno == ''){
                            $mobileno = $advocate_mob_val['mobile'];
                        }
                        else{
                            $mobileno = $mobileno . ',' . $advocate_mob_val['mobile'];
                        }
                    }

                endforeach;
            }

            $mo = $mobileno;
            $ms = $testmsg;
            $frm = $frm;

            return $this->mphc_sms($mo,$ms,$frm,$template_id);
        }

    }

    function mphc_sms($mobile,$cnt,$from_adr,$template_id){

        define('SMS_KEY', 'sdjkfgbsjh$1232_12nmnh');//key for 67 server kjuy@98123_-fgbvgAD and key for 60 server sdjkfgbsjh$1232_12nmnh

        if(empty($mobile)){
        $err_msg = " Mobile No. Empty.";
        }
        elseif(empty($cnt)){
            $err_msg = " Message content Empty.";
        }
        elseif(strlen($cnt) > 320){
            $err_msg = " Message length should be less than 320 characters.";
        }
        elseif(empty($from_adr)){
            $err_msg = " Sender Information Empty, contact to server room.";
        }
        else{

            $frm_adr = trim($from_adr);
            $sms_lengt = explode(",", trim($mobile));
            $count_sms = count($sms_lengt);
            $srno = 1;

            for($k=0; $k<$count_sms;$k++){
                //echo "<br/>";
                if(strlen(trim($sms_lengt[$k])) != '10'){
                    $err_msg = "   ".$srno++."   ".$sms_lengt[$k]." Not a proper mobile number. \n";
                }
                else if(!is_numeric($sms_lengt[$k])){
                    //not a numeric value
                    $err_msg = "   ".$srno++."   ".$sms_lengt[$k]." Mobile number contains invalid value. \n";
                }
                else{
                    $mm = trim($sms_lengt[$k]);
                    $homepage = file_get_contents('http://10.25.78.5/eAdminSCI/a-push-sms-gw?mobileNos='.$mm.'&message='.urlencode($cnt).'&typeId=29&myUserId=NIC001001&myAccessId=root&authCode='.SMS_KEY.'&templateId='.$template_id);
                    $json = json_decode($homepage);
                    if($json->{'responseFlag'} == "success"){

                        $ins_arr = [
                            'mobile'            =>      $mm,
                            'msg'               =>      $cnt,
                            'table_name'        =>      $frm_adr,
                            'c_status'          =>      'Y',
                            'ent_time'          =>      'NOW()',
                            'update_time'       =>      'NOW()',
                            'template_id'       =>      $template_id,
                            'create_modify'     =>      date("Y-m-d H:i:s"),
                            'updated_on'        =>      date("Y-m-d H:i:s"),
                            'updated_by'        =>      session()->get('login')['usercode'],
                            'updated_by_ip'     =>      getClientIP()
                            ];

                        $this->model->insert_sms_pool($ins_arr);
                        $err_msg = "   ".$srno++."   ".$sms_lengt[$k]." Success. SMS Sent \n";
                    }
                    else{

                        $ins_arr = [
                            'mobile'            =>      $mm,
                            'msg'               =>      $cnt,
                            'table_name'        =>      $frm_adr,
                            'c_status'          =>      'N',
                            'ent_time'          =>      'NOW()',
                            'template_id'       =>      $template_id,
                            'create_modify'     =>      date("Y-m-d H:i:s"),
                            'updated_on'        =>      date("Y-m-d H:i:s"),
                            'updated_by'        =>      session()->get('login')['usercode'],
                            'updated_by_ip'     =>      getClientIP()
                            ];

                        $this->model->insert_sms_pool($ins_arr);
                        $err_msg = "   ".$srno++."   ".$sms_lengt[$k]."   Error:Not Sent, SMS may send later. \n";
                    }
                }
            }
        }

        return $err_msg;
    }

    public function check_listing(){

        $diary_no = $this->diary_no;

        $for_heardt_zero = $this->model->check_for_heardt_zero($diary_no);

        if($for_heardt_zero<=0){

            $for_mention = $this->model->get_mention_memo($diary_no);
            if($for_mention<=0){
                echo "Please Contact Additional Registrar IB for listing";
            }
        }
        else{
            $for_drop_note = $this->model->get_drop_note($diary_no);
            if($for_drop_note>0){
                echo "Matter is listed and dropped. Please Contact Listing ";
            }
            else{
                echo "listed";
            }
        }
    }

    public function find_and_set_da(){

        $diary_no = $this->diary_no;

        $row_main = $this->model->get_and_set_da($diary_no);

        $sec_da_upto_disposal = array(21,55);

        if($row_main){
            $rcasetype=array(1,3);

            if($row_main[0]['dacode']!=0 && $row_main[0]['dacode']!=''){
                if(in_array($row_main[0]['section_id'], $sec_da_upto_disposal)) {
                    echo "DA already alloted";
                    exit();
                }
            }

            $previous_daname = array(39,9,10,19,20,25,26);
            $forXandPIL = array(5,6);

            if(in_array($row_main[0]['casetype_id'], $previous_daname)){

                $lower_case_temp_row = $this->model->get_lower_case_temp($diary_no);

                if(!empty($lower_case_temp_row)){
                    
                    $row_da = $this->model->get_for_da_temp($lower_case_temp_row);

                    if(!empty($row_da)){
                        $check_section = $this->check_section($row_da[0]['dacode'],$row_main[0]['section_id']);

                        $upd_arr = [
                                    'dacode'          =>    $row_da[0]['dacode'],
                                    'last_usercode'   =>    session()->get('login')['usercode'],
                                    'last_dt'         =>    'NOW()',
                                    'create_modify'   =>     date("Y-m-d H:i:s"),
                                    'updated_on'      =>     date("Y-m-d H:i:s"),
                                    'updated_by'      =>     session()->get('login')['usercode'],
                                    'updated_by_ip'   =>     getClientIP()
                                    ];

                        $this->model->update_main($upd_arr,$diary_no);
                        echo "SUCCESSFUL, DA ALLOTTED SUCCESSFULLY";
                    }
                    else{
                        echo "SORRY, DA NOT FOUND BECAUSE FOR CONT,RP,CURT AND MA PREVIOUS RECORD DOES NOT HAVE DA";
                    }

                }else{
                    echo "SORRY, DA NOT FOUND BECAUSE FOR CONT,RP,CURT AND MA PREVIOUS RECORD NOT FOUND";
                }

            }
            else{
                $dacodeallotted=0;

                if(in_array($row_main[0]['casetype_id'], $forXandPIL)){

                    $submaster_rs = $this->model->get_submaster_id($diary_no);

                    if($submaster_rs > 0){

                        $result_num_rows = $this->model->get_da_case_distribution_pilwrit_num_rows($row_main[0]['casetype_id'],$row_main[0]['filregdate'],$row_main[0]['ref_agency_state_id']);

                        if($result_num_rows > 0){
                            if($result_num_rows > 1){
                                echo "ERROR, DA CAN NOT ALLOT BECAUSE MORE THAN ONE DA FOUND";
                                $dacodeallotted=0;
                            }
                            else{
                                $row_da = $this->model->get_da_case_distribution_pilwrit($row_main[0]['casetype_id'],$row_main[0]['filregdate'],$row_main[0]['ref_agency_state_id']);

                                $check_section = $this->check_section($row_da[0]['dacode'],$row_main[0]['section_id']);

                                $upd_arr = [
                                            'dacode'          =>    $row_da[0]['dacode'],
                                            'last_usercode'   =>    session()->get('login')['usercode'],
                                            'last_dt'         =>    'NOW()',
                                            'create_modify'   =>     date("Y-m-d H:i:s"),
                                            'updated_on'      =>     date("Y-m-d H:i:s"),
                                            'updated_by'      =>     session()->get('login')['usercode'],
                                            'updated_by_ip'   =>     getClientIP()
                                            ];

                                $this->model->update_main($upd_arr,$diary_no);
                                echo "SUCCESSFUL, DA ALLOTTED SUCCESSFULLY";
                                $dacodeallotted=1;
                            }
                        }
                        else{
                            echo "SORRY, DA NOT FOUND";
                            $dacodeallotted=0;
                        }
                    }
                }
                elseif($row_main[0]['from_court']=='5'){

                    $tribunal='';
                    $tribunal_sec_arr = $this->model->get_tribunal_sec_qr($row_main[0]['ref_agency_code_id']);

                    if($tribunal_sec_arr){
                        $tribunal=$tribunal_sec_arr[0]['agency_or_court'];
                    }

                    if($tribunal==5){

                        $result_num_rows = $this->model->get_da_case_distribution_tri_new_num_rows($row_main[0]['casetype_id'],$row_main[0]['filregdate'],$tribunal);

                        if($result_num_rows > 0){
                            if($result_num_rows > 1){
                                echo "ERROR, DA CAN NOT ALLOT BECAUSE MORE THAN ONE DA FOUND";
                                $dacodeallotted=0;
                            }
                            else{
                                $row_da = $this->model->get_da_case_distribution_tri_new($row_main[0]['casetype_id'],$row_main[0]['filregdate'],$tribunal);

                                $check_section = $this->check_section($row_da[0]['dacode'],$row_main[0]['section_id']);

                                $upd_arr = [
                                            'dacode'          =>    $row_da[0]['dacode'],
                                            'last_usercode'   =>    session()->get('login')['usercode'],
                                            'last_dt'         =>    'NOW()',
                                            'create_modify'   =>     date("Y-m-d H:i:s"),
                                            'updated_on'      =>     date("Y-m-d H:i:s"),
                                            'updated_by'      =>     session()->get('login')['usercode'],
                                            'updated_by_ip'   =>     getClientIP()
                                            ];

                                $this->model->update_main($upd_arr,$diary_no);
                                echo "SUCCESSFUL, DA ALLOTTED SUCCESSFULLY";
                                $dacodeallotted=1;
                            }
                        }
                        elseif(in_array($row_main[0]['casetype_id'],$rcasetype)){

                            $rw_bo = $this->model->get_user_by_section('82');
                            $bocode = $rw_bo[0]['usercode'];

                            $check_section = $this->check_section($bocode,$row_main[0]['section_id']);

                            $upd_arr = [
                                        'dacode'          =>    $bocode,
                                        'last_usercode'   =>    session()->get('login')['usercode'],
                                        'last_dt'         =>    'NOW()',
                                        'create_modify'   =>     date("Y-m-d H:i:s"),
                                        'updated_on'      =>     date("Y-m-d H:i:s"),
                                        'updated_by'      =>     session()->get('login')['usercode'],
                                        'updated_by_ip'   =>     getClientIP()
                                        ];

                            $this->model->update_main($upd_arr,$diary_no);
                            echo "SUCCESSFUL, Branch officer Name Sucessfully Alloted as there is no DA";
                            $dacodeallotted=1;
                        }
                    }
                    else{

                        $result_num_rows = $this->model->get_da_case_distribution_tri_new_num_rows($row_main[0]['casetype_id'],$row_main[0]['filregdate'],$tribunal);

                        if($result_num_rows > 0){
                            if($result_num_rows > 1){
                                echo "ERROR, DA CAN NOT ALLOT BECAUSE MORE THAN ONE DA FOUND";
                                $dacodeallotted=0;
                            }
                            else{
                                $row_da = $this->model->get_da_case_distribution_tri_new($row_main[0]['casetype_id'],$row_main[0]['filregdate'],$tribunal);

                                $check_section = $this->check_section($row_da[0]['dacode'],$row_main[0]['section_id']);

                                $upd_arr = [
                                            'dacode'          =>    $row_da[0]['dacode'],
                                            'last_usercode'   =>    session()->get('login')['usercode'],
                                            'last_dt'         =>    'NOW()',
                                            'create_modify'   =>     date("Y-m-d H:i:s"),
                                            'updated_on'      =>     date("Y-m-d H:i:s"),
                                            'updated_by'      =>     session()->get('login')['usercode'],
                                            'updated_by_ip'   =>     getClientIP()
                                            ];

                                $this->model->update_main($upd_arr,$diary_no);
                                echo "SUCCESSFUL, DA ALLOTTED SUCCESSFULLY";
                                $dacodeallotted=1;
                            }
                        }
                        elseif(in_array($row_main[0]['casetype_id'],$rcasetype)){

                            $rw_bo = $this->model->get_user_by_section('52');
                            $bocode = $rw_bo[0]['usercode'];

                            $check_section = $this->check_section($bocode,$row_main[0]['section_id']);

                            $upd_arr = [
                                        'dacode'          =>    $bocode,
                                        'last_usercode'   =>    session()->get('login')['usercode'],
                                        'last_dt'         =>    'NOW()',
                                        'create_modify'   =>     date("Y-m-d H:i:s"),
                                        'updated_on'      =>     date("Y-m-d H:i:s"),
                                        'updated_by'      =>     session()->get('login')['usercode'],
                                        'updated_by_ip'   =>     getClientIP()
                                        ];

                            $this->model->update_main($upd_arr,$diary_no);
                            echo "SUCCESSFUL, Branch officer Name Sucessfully Alloted as there is no DA";
                            $dacodeallotted=1;
                        }
                    }
                }

                if($dacodeallotted==0){

                    if($row_main[0]['regyear']<date("Y") and  !in_array($row_main[0]['section_id'], $sec_da_upto_disposal)){
                        $row_main[0]['regyear']=date("Y");
                    }

                    $row_number_for = $this->model->get_number_for($row_main[0]['ref_agency_state_id'],$row_main[0]['casetype_id'],$row_main[0]['regyear']);

                    $current_no = 1;
                    foreach($row_number_for as $row_number_for_val):
                        if($row_number_for_val['diary_no'] == $diary_no){
                            $current_no = $row_number_for_val['rownum'];
                        }
                    endforeach;

                    if(in_array($row_main[0]['section_id'], $sec_da_upto_disposal)){

                        $result = $this->model->get_da_case_distribution_new("master.da_case_distribution_new",$row_main[0]['casetype_id'],$current_no,$row_main[0]['fildate'],$row_main[0]['ref_agency_state_id']);
                    }else{
                        $result = $this->model->get_da_case_distribution_new("master.da_case_distribution_new",$row_main[0]['casetype_id'],$current_no,$row_main[0]['filregdate'],$row_main[0]['ref_agency_state_id']);

                        $res_num_rows = $this->model->get_da_case_distribution_new_num_rows($row_main[0]['casetype_id'],$current_no,$row_main[0]['filregdate'],$row_main[0]['ref_agency_state_id']);

                        if($res_num_rows <= 0){

                            $result = $this->model->get_da_case_distribution_new("master.da_case_distribution",$row_main[0]['casetype_id'],$current_no,$row_main[0]['regyear'],$row_main[0]['ref_agency_state_id']);
                        }
                    }

                    $res_numrows = $this->model->get_da_case_distribution_new_num_rows($row_main[0]['casetype_id'],$current_no,$row_main[0]['filregdate'],$row_main[0]['ref_agency_state_id']);

                    if($res_numrows > 0){
                        if($res_numrows > 1){
                            echo "ERROR, DA CAN NOT ALLOT BECAUSE MORE THAN ONE DA FOUND";
                        }
                        else{
                            $row_da = $result;

                            $check_section = $this->check_section($row_da[0]['dacode'],$row_main[0]['section_id']);

                            $upd_arr = [
                                        'dacode'          =>    $row_da[0]['dacode'],
                                        'last_usercode'   =>    session()->get('login')['usercode'],
                                        'last_dt'         =>    'NOW()',
                                        'create_modify'   =>     date("Y-m-d H:i:s"),
                                        'updated_on'      =>     date("Y-m-d H:i:s"),
                                        'updated_by'      =>     session()->get('login')['usercode'],
                                        'updated_by_ip'   =>     getClientIP()
                                        ];

                            $this->model->update_main($upd_arr,$diary_no);
                            echo "SUCCESSFUL, DA ALLOTTED SUCCESSFULLY";
                        }
                    }
                    else{
                        echo "SORRY, DA NOT FOUND";
                    }
                }
            }
        }
        else{
            echo "SORRY, DIARY NUMBER NOT FOUND";
        }
    }

    function check_section($dacode,$matter_section){

        $diary_no = $this->diary_no;

        $da_data = $this->model->get_check_section($dacode);

        if($da_data[0]['section']!=$matter_section){

            $ins_arr = [
                        'diary_no'            =>      $diary_no,
                        'dacode'              =>      $dacode,
                        'da_section_id'       =>      $da_data[0]['section'],
                        'matter_section_id'   =>      $matter_section,
                        'ent_by'              =>      session()->get('login')['usercode'],
                        'ent_on'              =>      'NOW()',
                        'create_modify'       =>     date("Y-m-d H:i:s"),
                        'updated_on'          =>     date("Y-m-d H:i:s"),
                        'updated_by'          =>     session()->get('login')['usercode'],
                        'updated_by_ip'       =>     getClientIP()
                        ];

            $this->model->insert_matters_with_wrong_section($ins_arr);

        }
    }

    public function show_proposal(){

        $diary_no = $this->diary_no;

        $case_name = $this->model->get_case_name_q($diary_no);
        $chk_heardt = $this->model->get_chk_heardt($diary_no);
        
        if($chk_heardt == 0){
            //echo "DATA NOT IN HEARDT TABLE";
            //exit();
        }

        $details = $this->model->get_popup_details($diary_no);
        $data['row_cate'] = $this->model->get_query_cate($diary_no);
        $data['main_case'] = $this->model->get_main_case($diary_no);

        if($details[0]['mainhead']!='F'){
            if(trim($details[0]['side'])=='C'){
                $stage_based_on_side = "stagecode!=811 and stagecode!=814 and stagecode!=815 ";
            }
            elseif(trim($details[0]['side'])=='R'){
                $stage_based_on_side = "stagecode!=812 and stagecode!=813 and stagecode!=816 ";
            }

            $data['rw_subh'] = $this->model->get_subheading($details[0]['mainhead'],$stage_based_on_side);
        }
        elseif($details[0]['mainhead']=='F'){

            $data['rw_subh'] = $this->model->get_mul_category_with_submaster($diary_no);
        }

        $if_list_is_printed = false;
        $n_dt = $details[0]['next_dt'];

        if($n_dt==NULL || $n_dt == ''){
            $details[0]['next_dt'] = NULL;
        }

        $if_printed = $this->model->get_if_printed($details[0]['next_dt'],$details[0]['mainhead'],$details[0]['roster_id'],$details[0]['clno'],$details[0]['main_supp_flag']);

        if($if_printed>0){
            $if_list_is_printed = true;
        }
        else{
            $if_list_is_printed = false;
        }

        $data['row_judge'] = $this->model->get_judge($details[0]['next_dt']);

        $data['main_supp_row'] = $this->model->get_master_main_supp();

        $data['row_purpose'] = $this->model->get_listing_purpose();

        $data['row'] = $this->model->get_ia_details($diary_no);

        $data['details'] = $details;

        $data['diary_no'] = $diary_no;

        return view('Filing/popup',$data);
    }

}