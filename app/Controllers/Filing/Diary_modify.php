<?php

namespace App\Controllers\Filing;
use App\Controllers\BaseController;
use App\Libraries\webservices\Efiling_webservices;
use App\Libraries\webservices\Highcourt_webservices;
use App\Models\Common\Dropdown_list_model;
use App\Models\Filing\Model_caveat;
use App\Models\Filing\Model_party;
use App\Models\Filing\Model_renewed_caveat;
use App\Models\Filing\Model_special_category_filing;
use App\Models\Master\Model_casetype;
use App\Models\Master\Model_cnt_diary_no;
use App\Models\Model_main;
use App\Models\Filing\Model_diary;

class Diary_modify extends BaseController
{
    public $LoginModel;
    public $efiling_webservices;
    public $highcourt_webservices;
    public $Dropdown_list_model;
    public $Model_main;
    public $Model_diary;
    public $Model_cnt_diary_no;
    public $Model_casetype;
    public $Model_special_category_filing;
    public $Model_party;
    public $Model_renewed_caveat;
    public $Model_caveat;
    function __construct()
    {
        if (isset($_SESSION['login'])) {
            if (!isset($_SESSION['filing_details'])) {
                header('Location:'.base_url('Filing/Diary/search'));exit();
            }
        }else{
            header('Location:'.base_url('Signout'));exit();
        }
        $this->efiling_webservices= new Efiling_webservices();
        $this->highcourt_webservices= new Highcourt_webservices();
        $this->Dropdown_list_model= new Dropdown_list_model();
        $this->Model_main= new Model_main();
        $this->Model_diary= new Model_diary();
        $this->Model_cnt_diary_no= new Model_cnt_diary_no();
        $this->Model_casetype= new Model_casetype();
        $this->Model_special_category_filing= new Model_special_category_filing();
        $this->Model_party= new Model_party();
        $this->Model_renewed_caveat= new Model_renewed_caveat();
        $this->Model_caveat= new Model_caveat();
        ini_set('memory_limit','-1'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
        ini_set('max_execution_time', 0);
        //error_reporting(2);

    }


    public function index()
    {
        $data['pet_dist_list']=array();
        $data['res_dist_list']=array();
        //echo $table_main=is_table_main();
        $c_status=session()->get('filing_details')['c_status'];
        $diary_no=session()->get('filing_details')['diary_no'];
        $data['country'] = get_from_table_json('country');
        $data['state'] = get_from_table_json('state');

        $data['court_type_list'] = $this->Dropdown_list_model->get_court_type_list();
        $data['usersection']=$this->Dropdown_list_model->get_usersection();
        $data['state_list'] = $this->Dropdown_list_model->get_address_state_list();
        $role = $this->Model_diary->get_role_fil_trap(session()->get('login')['usercode']);
        $data['casetype']=$this->Dropdown_list_model->get_case_type('filing','nature_sci');
        $data['casetype_nature_sci']=$this->Dropdown_list_model->get_case_type($role,'nature_sci');
        $data['role']=$role;

        $fetch_rw = $this->Model_diary->get_diary_details($diary_no);
        $data['sclsc'] = $this->Model_diary->get_sclsc($diary_no);
        $data['jailer_sign_dt'] = $this->Model_diary->get_jailer_sign_dt($diary_no);
        $data['check_if_fil_user'] = $this->Model_diary->check_if_fil_user(session()->get('login')['usercode']);

        $data['hc_benches'] = $this->Dropdown_list_model->get_ref_agency_code($fetch_rw['ref_agency_state_id'],$fetch_rw['from_court']);
        $data['pet_rw'] = $this->Model_diary->get_party_list($diary_no);
        $data['jail_petition_details']=$this->Model_diary->get_jail_petition_details($diary_no);
        $data['check_lowerct']=$this->Model_diary->check_lowerct_when_modify_diary($diary_no);
        $data['ref_special_category_filing'] = $this->Model_diary->get_special_category_filing_details($diary_no);
        if (!empty($data['pet_rw']) && !empty($data['pet_rw']['city'])){
            $data['pet_dist_list'] = $this->Dropdown_list_model->get_districts_list($data['pet_rw']['city']);
        }
        $data['res_rw'] = $this->Model_diary->get_party_list($diary_no,'R');
        if (!empty($data['res_rw']) && !empty($data['res_rw']['city'])){
            $data['res_dist_list'] = $this->Dropdown_list_model->get_districts_list($data['res_rw']['city']);
        }
        $data['rw_utype']=is_data_from_table('master.users',['usercode'=>session()->get('login')['usercode']],'usertype,section','R');
        $data['fetch_rw']=$fetch_rw;
        $data['petadv_info_rw'] = $this->Model_diary->get_advocate_details($diary_no,'P');
        $data['resadv_info_rw'] = $this->Model_diary->get_advocate_details($diary_no,'R');
 
        $data['additional_address_p'] = $this->Model_diary->get_additional_address_details($diary_no,'P');
        $data['additional_address_r'] = $this->Model_diary->get_additional_address_details($diary_no,'R');
        $data['update_button']='';
        if (!empty($fetch_rw)){
            if (!empty($fetch_rw['c_status']) && $fetch_rw['c_status']=='P'){
                $data['update_button']="<input type='button' class='btn btn-success' value='Update' onclick='call_update_main()' id='svbtn' onkeydown='if (event.keyCode == 13) document.getElementById('svbtn').click()' ><input type='button' class='btn btn-primary' value='Cancel' onclick='window.location.reload()'>";
            }
        }
        //echo '<pre>';print_r(session()->get('filing_details'));exit();
       // echo '<pre>';print_r( $data['petadv_info_rw']);//exit();
        //echo '<pre>';print_r( $data['resadv_info_rw']);exit();

        /*echo '<pre>';print_r( $data['fetch_rw']);//exit();
        echo '<pre>';print_r( $data['pet_rw']);
        echo '<pre>';print_r( $data['res_rw']);
        exit();*/
       // echo '<pre>';print_r($data['additional_address_p']);
        //echo '<pre>';print_r($data['additional_address_r']);
        //exit();
//pr($data['state_list']);
       //return view('Filing/diary_modify',$data);
       return view('Filing/diary_update',$data);
    }
    /*XXXXXXXXXXXXXXXXXXX start Upadation save_new_filing XXXXXXXXXXXXXXXXXX*/
    public function save_new_filing(){
        if($this->request->getPost('controller')=='U') {
            $ucode = $_SESSION['login']['usercode'];
            $year = date('Y');

            if (isset($_REQUEST['type_special']) && $_REQUEST['type_special'] == 6) {
                $_REQUEST['txt_doc_signed'] = date('Y-m-d', strtotime($_REQUEST['txt_doc_signed']));
            }else{
                $_REQUEST['txt_doc_signed'] = NULL;
            }
            $padvno_and_yr = $this->request->getPost('hd_p_barid');
            $radvno_and_yr = $this->request->getPost('hd_r_barid');

            if ($this->request->getPost('padtype') == 'SS') {
                $padvno_and_yr = '584';
            }

            if ($this->request->getPost('radtype') == 'SS') {
                $radvno_and_yr = '585';
            }
            $d_y = $this->request->getPost('d_no').$this->request->getPost('d_yr');
            $filing_details= session()->get('filing_details');
            $diary_no=$filing_details['diary_no'];
            if (empty($diary_no)){
                echo 'Diary is required';exit();
            }else{$diary_no=$d_y;}

            echo '!~!';
            $ip = '';
            $ip = getClientIP();
            $in_sup = "";
            $ctrl = 0;
            $insup2 = "";
            //echo $q1 = "select pet_name,res_name from main where diary_no = '$d_y'";
            $pet_cause_title=''; $res_cause_title = '';
            $sclsc = 0; 
            //error_reporting(0);
             
            if ($this->request->getPost('if_sclsc') == 0 || $this->request->getPost('if_sclsc') == '') {
                $sclsc = 0;
            } else {
                $sclsc =$this->request->getPost('if_sclsc');
            }
            $section = $this->request->getPost('section');
            if ($section == '' || $section == null) {
                $section = 'null';
            }
            $efil = 0;
            $efil_no = 0;
            $efil_yr = '';
            if ($this->request->getPost('if_efil') == 0 || $this->request->getPost('if_efil') == ''){
                $efil = 0;
             }else {
                $efil = $this->request->getPost('if_efil');
                $efil_no=$this->request->getPost('txt_efil_no');
                $efil_yr=$this->request->getPost('ddl_efil_yr');
            }
            if (!isset($_REQUEST['padd'])){$_REQUEST['padd']='';}else{ $_REQUEST['padd'] = htmlentities(sanitize($_REQUEST['padd'])); }
            if (!isset($_REQUEST['pocc'])){$_REQUEST['pocc']='';}else{ $_REQUEST['pocc'] = htmlentities(sanitize($_REQUEST['pocc'])); }
            if (!isset($_REQUEST['radd'])){$_REQUEST['radd']='';}else{ $_REQUEST['radd'] = htmlentities(sanitize($_REQUEST['radd'])); }
            if (!isset($_REQUEST['rocc'])){$_REQUEST['rocc']='';}else{ $_REQUEST['rocc'] = htmlentities(sanitize($_REQUEST['rocc'])); }

            if (!isset($_REQUEST['pet_statename_hd'])){$_REQUEST['pet_statename_hd']=0;}
            if (!isset($_REQUEST['res_statename_hd'])){$_REQUEST['res_statename_hd']=0;}
            if (empty($_REQUEST['pmob'])){  $_REQUEST['pmob']=0;}
            if (empty($_REQUEST['rmob'])){  $_REQUEST['rmob']=0;}

            if (!isset($_REQUEST['cs_tp'])){$_REQUEST['cs_tp']=0;$cs_tp=0;}

            if (!isset($_REQUEST['pet_rel_name'])){$_REQUEST['pet_rel_name']='';}
            if (!isset($_REQUEST['p_age'])){$_REQUEST['p_age']=0;}
            if (!isset($_REQUEST['p_sex'])){$_REQUEST['p_sex']=0;}
            if (!isset($_REQUEST['pet_rel'])){$_REQUEST['pet_rel']='';}

            if (!isset($_REQUEST['res_rel_name'])){$_REQUEST['res_rel_name']='';}
            if (!isset($_REQUEST['r_age'])){$_REQUEST['r_age']=0;}
            if (!isset($_REQUEST['r_sex'])){$_REQUEST['r_sex']=0;}
            if (!isset($_REQUEST['res_rel'])){$_REQUEST['res_rel']='';}
            $this->db = \Config\Database::connect();
            $this->db->transStart();

            $res_nt['nature']=''; $res_nt['casename']='';
            if (isset($_REQUEST['ddl_nature']) && !empty($_REQUEST['ddl_nature'])){
                $res_nt = $this->Model_casetype->select('nature,casename')->where('casecode', $this->request->getPost('ddl_nature'))->get()->getRowArray();
                $res_nt['nature'];
                $res_nt['casename'];
            }
            if($this->request->getPost('p_type')=='I' && $this->request->getPost('r_type')=='I'){
                $pet_cause_title=strtoupper(trim($this->request->getPost('pname')));
                $res_cause_title=strtoupper(trim($this->request->getPost('rname')));
                $update_main=[
                    'case_pages'=> $this->request->getPost('page'),
                    'pet_adv_id'=> $padvno_and_yr,
                    'res_adv_id'=> $radvno_and_yr,
                    'last_usercode'=> $ucode,
                    'last_dt'=> date("Y-m-d H:i:s"),
                    'ref_agency_state_id'=> $this->request->getPost('ddl_st_agncy'),
                    'ref_agency_code_id'=> $this->request->getPost('ddl_bench'),
                    'case_grp'=> $res_nt['nature'],
                    'casetype_id'=> $this->request->getPost('ddl_nature'),
                    'from_court'=> $this->request->getPost('ddl_court'),
                    'padvt'=> $this->request->getPost('padtype'),
                    'radvt'=> $this->request->getPost('radtype'),
                    'nature'=> $this->request->getPost('type_special'),
                    'pno'=> $this->request->getPost('t_pet'),
                    'rno'=> $this->request->getPost('t_res'),
                    'if_sclsc'=> $sclsc,
                    'section_id'=> $section,
                    'ack_id'=> $efil_no,
                    'ack_rec_dt'=> $efil_yr,

                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];
                // echo" second update";
            }
            else if($_REQUEST['p_type']=='I' && $_REQUEST['r_type']!='I'){
                $pet_cause_title=strtoupper(trim($_REQUEST['pname']));
                if($_REQUEST['r_cause_t1']==1)
                    $res_cause_title = strtoupper(trim($_REQUEST['res_statename'])).' ';
                if($_REQUEST['r_cause_t2']==1)
                    $res_cause_title .= strtoupper(trim($_REQUEST['res_deptt'])).' ';
                if($_REQUEST['r_cause_t3']==1)
                    $res_cause_title .= strtoupper(trim($_REQUEST['res_post'])).' ';
               
                    $res_cause_title =  (!empty($res_cause_title)) ?  rtrim(trim($res_cause_title),',') : '';
                // echo "third update";
                $update_main=[
                    'case_pages'=> $_REQUEST['page'],
                    'pet_adv_id'=> $padvno_and_yr,
                    'res_adv_id'=> $radvno_and_yr,
                    'last_usercode'=> $ucode,
                    'last_dt'=> date("Y-m-d H:i:s"),
                    'ref_agency_state_id'=> $_REQUEST['ddl_st_agncy'],
                    'ref_agency_code_id'=> $_REQUEST['ddl_bench'],
                    'case_grp'=> $res_nt['nature'],
                    'casetype_id'=> $_REQUEST['ddl_nature'],
                    'from_court'=> $_REQUEST['ddl_court'],
                    'padvt'=> $_REQUEST['padtype'],
                    'radvt'=> $_REQUEST['radtype'],
                    'nature'=> $_REQUEST['type_special'],
                    'pno'=> $_REQUEST['t_pet'],
                    'rno'=> $_REQUEST['t_res'],
                    'if_sclsc'=> $sclsc,
                    'section_id'=> $section,
                    'ack_id'=> $efil_no,
                    'ack_rec_dt'=> $efil_yr,

                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];

            }
            else if($_REQUEST['p_type']!='I' && $_REQUEST['r_type']=='I'){
                if($_REQUEST['p_cause_t1']==1)
                    $pet_cause_title = strtoupper(trim($_REQUEST['pet_statename'])).' ';
                if($_REQUEST['p_cause_t2']==1)
                    $pet_cause_title .= strtoupper(trim($_REQUEST['pet_deptt'])).' ';
                if($_REQUEST['p_cause_t3']==1)
                    $pet_cause_title .= strtoupper(trim($_REQUEST['pet_post'])).' ';
                $pet_cause_title = (!empty($pet_cause_title)) ?  rtrim(trim($pet_cause_title),',') : '';
                $res_cause_title=strtoupper(trim($_REQUEST['rname']));

                $update_main=[
                    'case_pages'=> $_REQUEST['page'],
                    'pet_adv_id'=> $padvno_and_yr,
                    'res_adv_id'=> $radvno_and_yr,
                    'last_usercode'=> $ucode,
                    'last_dt'=> date("Y-m-d H:i:s"),
                    'ref_agency_state_id'=> $_REQUEST['ddl_st_agncy'],
                    'ref_agency_code_id'=> $_REQUEST['ddl_bench'],
                    'case_grp'=> $res_nt['nature'],
                    'casetype_id'=> $_REQUEST['ddl_nature'],
                    'from_court'=> $_REQUEST['ddl_court'],
                    'padvt'=> $_REQUEST['padtype'],
                    'radvt'=> $_REQUEST['radtype'],
                    'nature'=> $_REQUEST['type_special'],
                    'pno'=> $_REQUEST['t_pet'],
                    'rno'=> $_REQUEST['t_res'],
                    'if_sclsc'=> $sclsc,
                    'section_id'=> $section,
                    'ack_id'=> $efil_no,
                    'ack_rec_dt'=> $efil_yr,

                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];

            }
            else if($_REQUEST['p_type']!='I' && $_REQUEST['r_type']!='I'){

                if($_REQUEST['p_cause_t1']==1)
                    $pet_cause_title = strtoupper(trim($_REQUEST['pet_statename'])).' ';
                if($_REQUEST['p_cause_t2']==1)
                    $pet_cause_title .= strtoupper(trim($_REQUEST['pet_deptt'])).' ';
                if($_REQUEST['p_cause_t3']==1)
                    $pet_cause_title .= strtoupper(trim($_REQUEST['pet_post'])).' ';
                $pet_cause_title = rtrim(trim($pet_cause_title),',');
                if($_REQUEST['r_cause_t1']==1)
                    $res_cause_title = strtoupper(trim($_REQUEST['res_statename'])).' ';
                if($_REQUEST['r_cause_t2']==1)
                    $res_cause_title .= strtoupper(trim($_REQUEST['res_deptt'])).' ';
                if($_REQUEST['r_cause_t3']==1)
                    $res_cause_title .= strtoupper(trim($_REQUEST['res_post'])).' ';
                $res_cause_title = rtrim(trim($res_cause_title),',');
                //  echo "firstgfgd";

                $update_main=[
                    'case_pages'=> $_REQUEST['page'],
                    'pet_adv_id'=> $padvno_and_yr,
                    'res_adv_id'=> $radvno_and_yr,
                    'last_usercode'=> $ucode,
                    'last_dt'=> date("Y-m-d H:i:s"),
                    'ref_agency_state_id'=> $_REQUEST['ddl_st_agncy'],
                    'ref_agency_code_id'=> $_REQUEST['ddl_bench'],
                    'case_grp'=> $res_nt['nature'],
                    'casetype_id'=> $_REQUEST['ddl_nature'],
                    'from_court'=> $_REQUEST['ddl_court'],
                    'padvt'=> $_REQUEST['padtype'],
                    'radvt'=> $_REQUEST['radtype'],
                    'nature'=> $_REQUEST['type_special'],
                    'pno'=> $_REQUEST['t_pet'],
                    'rno'=> $_REQUEST['t_res'],
                    'if_sclsc'=> $sclsc,
                    'section_id'=> $section,
                    'ack_id'=> $efil_no,
                    'ack_rec_dt'=> $efil_yr,

                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];

            }
            if (!empty($update_main) && !empty($diary_no)){
                
               // $update_query_result = update('main',$update_main,['diary_no'=>$diary_no]);
                 $this->db->table('main')->update($update_main, ['diary_no' => $diary_no]);
               // pr($update_main);
            }

            $check_if_priorityset_rs= is_data_from_table('special_category_filing',['diary_no'=>$diary_no,'display'=>'Y'],'ref_special_category_filing_id','R');
            if($_REQUEST['priority_category']==0) {
                if (!empty($check_if_priorityset_rs)){
                if ($check_if_priorityset_rs['ref_special_category_filing_id'] != $this->request->getPost('priority_category')) {
                    $update_special_category_filing = [
                        'diary_no' => $diary_no,
                        'ref_special_category_filing_id' => $_REQUEST['priority_category'],
                        'display' => 'Y',
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                    $is_special_category_filing = update('special_category_filing', $update_special_category_filing, ['diary_no' => $diary_no]);
                }
                }else{
                    $insert_special_category_filing = [
                        'diary_no' => $diary_no,
                        'ref_special_category_filing_id' => $_REQUEST['priority_category'],
                        'display' => 'Y',
                        'create_modify' => date("Y-m-d H:i:s"),
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];

                    $is_special_category_filing = $this->Model_special_category_filing->insert($insert_special_category_filing);
                }
            }
            if($this->request->getPost('priority_category')!=0){
                if (!empty($check_if_priorityset_rs)) {
                    if ($check_if_priorityset_rs['ref_special_category_filing_id'] !=$_REQUEST['priority_category']) {
                        $update_special_category_filing = [
                            'diary_no' => $diary_no,
                            'ref_special_category_filing_id' => $_REQUEST['priority_category'],
                            'display' => 'Y',
                            'updated_on' => date("Y-m-d H:i:s"),
                            'updated_by' => session()->get('login')['usercode'],
                            'updated_by_ip' => getClientIP(),
                        ];
                        $is_special_category_filing = update('special_category_filing', $update_special_category_filing, ['diary_no' => $diary_no]);
                    }
                }else{
                    $insert_special_category_filing = [
                        'diary_no' => $diary_no,
                        'ref_special_category_filing_id' => $_REQUEST['priority_category'],
                        'display' => 'Y',
                        'create_modify' => date("Y-m-d H:i:s"),
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                    $is_special_category_filing = $this->Model_special_category_filing->insert($insert_special_category_filing);

                }
            }
            
            $res_chk_lc= is_data_from_table('lowerct',['diary_no'=>$diary_no],'count(lower_court_id)' ,'R');

            $case_no=0;
            if($_REQUEST['hd_mn']!='' && $_REQUEST['cs_tp']!='' && $_REQUEST['txtFNo']!='' && $_REQUEST['txtYear']!='')
            {

                $case_no=$cs_tp.$_REQUEST['txtFNo'].$_REQUEST['txtYear'];
                $_REQUEST['txtFNo']=ltrim($_REQUEST['txtFNo'],0);

                $res_chk_lc= is_data_from_table('lowerct',['diary_no'=>$diary_no],'count(lower_court_id)');
                if($res_chk_lc['count']==0)
                {
                    $ins_l_c=[
                        'ct_code'=> $_REQUEST['ddl_court'],
                        'l_state '=>$_REQUEST['ddl_st_agncy'],
                        'l_dist'=>$_REQUEST['ddl_bench'],
                        'diary_no'=> $diary_no,
                        'lw_display'=> 'R',
                        'lct_casetype'=>$_REQUEST['cs_tp'],
                        'lct_caseno'=> $_REQUEST['txtFNo'],
                        'lct_caseyear'=> $_REQUEST['txtYear'],

                        'create_modify' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                    
                    $lowerct_last_insert=insert('lowerct',$ins_l_c);
                }
                else if($res_chk_lc['count']==1)
                {
                    
                    $res_chk_status= is_data_from_table('lowerct',['diary_no'=>$diary_no],'lw_display','R');
                    if(!empty($res_chk_status) && $res_chk_status['lw_display']=='R')
                    {
                    $ins_l_c=[
                        'ct_code'=> $_REQUEST['ddl_court'],
                        'l_state '=>$_REQUEST['ddl_st_agncy'],
                        'l_dist'=>$_REQUEST['ddl_bench'],
                        'lct_casetype'=>$_REQUEST['cs_tp'],
                        'lct_caseno'=> $_REQUEST['txtFNo'],
                        'lct_caseyear'=> $_REQUEST['txtYear'],

                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                    $lowerct_last_insert=update('lowerct',$ins_l_c,['diary_no'=>$diary_no,'lw_display'=>'R']);
                    }
                }
                
            }

            if($padvno_and_yr!=0)
            {
                if($_REQUEST['padtype']=='SS')
                    $_REQUEST['padtype']='A';
                $chk_adv_p= is_data_from_table('advocate',['diary_no'=>$diary_no,'adv_type'=>'M','pet_res'=>'P','pet_res_no'=>'1','display'=>'Y'],'diary_no');
                if(!empty($chk_adv_p)){
                    $update_adv_pet=[
                        'advocate_id'=> $padvno_and_yr,
                        'adv_type '=>'M',
                        'pet_res'=>'P',
                        'pet_res_no'=> 1,
                        'usercode'=>$ucode,
                        'ent_dt'=>  date("Y-m-d H:i:s"),
                        'display'=> 'Y',
                        'stateadv'=>'N',
                        'aor_state'=> $_REQUEST['padtype'],
                        'is_ac'=> $_REQUEST['is_ac'],
                        'pet_res_show_no'=> 1,
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                    $advocate_pet_update_adv=update('advocate',$update_adv_pet,['diary_no'=>$diary_no,'adv_type'=>'M','pet_res'=>'P','pet_res_no'=>'1','display'=>'Y']);
                }
                else{

                    $ins_adv_pet=[
                        'diary_no'=> $diary_no,
                        'adv_type '=>'M',
                        'pet_res'=>'P',
                        'pet_res_no'=> 1,
                        'advocate_id'=> $padvno_and_yr,
                        'usercode'=>$ucode,
                        'ent_dt'=>  date("Y-m-d H:i:s"),
                        'display'=> 'Y',
                        'stateadv'=>'N',
                        'aor_state'=> $_REQUEST['padtype'],
                        'is_ac'=> $_REQUEST['is_ac'],
                        'pet_res_show_no'=> 1,
                        'create_modify' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                    $advocate_pet_ins_adv=insert('advocate',$ins_adv_pet);
                }
            }
            

            if($radvno_and_yr!=0)
            {
                if($_REQUEST['radtype']=='SS')
                    $_REQUEST['radtype']='A';
               $chk_adv_r= is_data_from_table('advocate',['diary_no'=>$diary_no,'adv_type'=>'M','pet_res'=>'R','pet_res_no'=>'1','display'=>'Y'],'diary_no');

                if(!empty($chk_adv_r)){
                    $update_adv_res=[
                        'adv_type '=>'M',
                        'pet_res'=>'R',
                        'pet_res_no'=> 1,
                        'advocate_id'=> $radvno_and_yr,
                        'usercode'=>$ucode,
                        'ent_dt'=>  date("Y-m-d H:i:s"),
                        'display'=> 'Y',
                        'stateadv'=>'N',
                        'aor_state'=> $_REQUEST['radtype'],
                        'is_ac'=> $_REQUEST['ris_ac'],
                        'pet_res_show_no'=> 1,

                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];

                    $advocate_pet_update_adv=update('advocate',$update_adv_res,['diary_no'=>$diary_no,'adv_type'=>'M','pet_res'=>'R','pet_res_no'=>'1','display'=>'Y']);
                }
                else{
                   $ins_adv_res=[
                        'diary_no'=> $diary_no,
                        'adv_type '=>'M',
                        'pet_res'=>'R',
                        'pet_res_no'=> 1,
                        'advocate_id'=> $radvno_and_yr,
                        'usercode'=>$ucode,
                        'ent_dt'=>  date("Y-m-d H:i:s"),
                        'display'=> 'Y',
                        'stateadv'=>'N',
                        'aor_state'=> $_REQUEST['radtype'],
                        'is_ac'=> $_REQUEST['ris_ac'],
                        'pet_res_show_no'=> 1,

                        'create_modify' => date("Y-m-d H:i:s"),
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];

                    $advocate_res_ins_adv=insert('advocate',$ins_adv_res);
                    //echo '<pre>ins_adv_res=';print_r($ins_adv_res);
                }
            }else{
                // when Respondent Advocate Details blank
                $update_adv_res=[      
                        'display'=> 'N',                                                        
                        'usercode'=>$ucode,
                        'ent_dt'=>  date("Y-m-d H:i:s"),                        
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                ];
                $advocate_pet_update_adv=update('advocate',$update_adv_res,['diary_no'=>$diary_no,'adv_type'=>'M','pet_res'=>'R','pet_res_no'=>'1','display'=>'Y']);
            }
            
            if(isset($_REQUEST['ext_address']) && $_REQUEST['ext_address']!='')
            {
                $is_res_party_id=is_data_from_table('party',['diary_no'=>$diary_no,'pet_res'=>'P','sr_no_show'=>'1','pflag'=>'P'],'auto_generated_id','R');
                if (!empty($is_res_party_id)) {
                    $res_party_id = $is_res_party_id['auto_generated_id'];
                    $ex_address = explode('^', $_REQUEST['ext_address']);
                    for ($index = 0; $index < count($ex_address); $index++) {
                        $in_exp = explode('~', $ex_address[$index]);
                        //echo '<pre>';print_r($in_exp);exit();
                        if($in_exp[4]!=0)
                        {
                            if (empty($pet_add_det)) {
                                $update_party_additional_address = [
                                    'country' => $in_exp[1],
                                    'state ' => $in_exp[2],
                                    'district' => $in_exp[3],
                                    'address' => $in_exp[0],
                                    'updated_on' => date("Y-m-d H:i:s"),
                                    'updated_by' => session()->get('login')['usercode'],
                                    'updated_by_ip' => getClientIP(),
                                ];

                                $is_ins_party_additional_address = update('party_additional_address', $update_party_additional_address,['id'=>$in_exp[4],'display'=>'Y']);
                            }
                        }else {
                            $pet_add_det = '';
                            if (!empty($in_exp[1]) && $in_exp[2] && $in_exp[3] && $in_exp[0]) {
                                $pet_add_det = is_data_from_table('party_additional_address', ['party_id' => $res_party_id, 'country' => $in_exp[1], 'state' => $in_exp[2], 'district' => $in_exp[3], 'address' => $in_exp[0]]);
                            }
                            // echo print_r($pet_add_det);
                            if (empty($pet_add_det)) {
                                $ins_party_additional_address = [
                                    'country' => $in_exp[1],
                                    'state ' => $in_exp[2],
                                    'district' => $in_exp[3],
                                    'address' => $in_exp[0],
                                    'party_id' => $res_party_id,

                                    'create_modify' => date("Y-m-d H:i:s"),
                                    'updated_by' => session()->get('login')['usercode'],
                                    'updated_by_ip' => getClientIP(),
                                ];

                                $is_ins_party_additional_address = insert('party_additional_address', $ins_party_additional_address);
                            }
                        }
                    }
                }
            }
            
            if(isset($_REQUEST['ext_address_r']) && $_REQUEST['ext_address_r']!='')
            {
                $is_res_party_id_r=is_data_from_table('party',['diary_no'=>$diary_no,'pet_res'=>'R','sr_no_show'=>'1','pflag'=>'P'],'auto_generated_id','R');
                if (!empty($is_res_party_id_r)) {
                    $res_party_id_r = $is_res_party_id_r['auto_generated_id'];
                    $ex_address_r = explode('^', $_REQUEST['ext_address_r']);
                    for ($index = 0; $index < count($ex_address_r); $index++) {
                        $in_exp_r = explode('~', $ex_address_r[$index]);
                        if($in_exp[4]!=0)
                        {

                                $update_party_additional_address_r = [
                                    'country' => $in_exp_r[1],
                                    'state ' => $in_exp_r[2],
                                    'district' => $in_exp_r[3],
                                    'address' => $in_exp_r[0],
                                    'updated_on' => date("Y-m-d H:i:s"),
                                    'updated_by' => session()->get('login')['usercode'],
                                    'updated_by_ip' => getClientIP(),
                                ];
                                $is_update_party_additional_address_r = update('party_additional_address', $update_party_additional_address_r,['id'=>$in_exp_r[4],'display'=>'Y']);
                        }else {
                        $res_add_det_r='';
                        if (!empty($in_exp_r[1]) && $in_exp_r[2] && $in_exp_r[3] && $in_exp_r[0]) {
                            $res_add_det_r = is_data_from_table('party_additional_address', ['party_id' => $res_party_id_r, 'country' => $in_exp_r[1], 'state' => $in_exp_r[2], 'district' => $in_exp_r[3], 'address' => $in_exp_r[0]]);
                        }
                        if (empty($res_add_det_r)) {

                            $ins_party_additional_address_r = [
                                'country' => $in_exp_r[1],
                                'state ' => $in_exp_r[2],
                                'district' => $in_exp_r[3],
                                'address' => $in_exp_r[0],
                                'party_id' => $res_party_id_r,

                                'create_modify' => date("Y-m-d H:i:s"),
                                'updated_by' => session()->get('login')['usercode'],
                                'updated_by_ip' => getClientIP(),
                            ];

                            $is_ins_party_additional_address_r = insert('party_additional_address', $ins_party_additional_address_r);
                        }

                        }
                    }
                }
            }
            
            $res_jail_ent_dt=is_data_from_table('jail_petition_details',['jail_display'=>'Y','diary_no'=>$diary_no],'count(diary_no)','R');
           
            if($_REQUEST['type_special']==6)
            {
                if($res_jail_ent_dt['count']==0) {
                    $insert_jail_ent_dt = [
                        'diary_no'=>$diary_no,
                        'jailer_sign_dt'=>$_REQUEST['txt_doc_signed'],
                        'jail_display'=>'Y',
                        'diary_no_entry_dt'=>date("Y-m-d H:i:s"),

                        'create_modify' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                    $is_insert_jail_ent_dt= insert('jail_petition_details',$insert_jail_ent_dt);

                }
                else
                {
                    $update_jail_ent_dt = [
                        'jailer_sign_dt'=>$_REQUEST['txt_doc_signed'],
                        'diary_no_entry_dt'=>date("Y-m-d H:i:s"),

                        'updated_on'=>date("Y-m-d H:i:s"),
                        'updated_by'=>$_SESSION['login']['usercode'],
                        'updated_by_ip'=>getClientIP(),
                    ];
                    $is_update_jail_ent_dt=update('jail_petition_details',$update_jail_ent_dt,['jail_display'=>'Y','diary_no'=>$diary_no]);

                }
            }
            
            else
            {
               // pr($_REQUEST['type_special']);
                
               // if ($res_jail_ent_dt['count'] == 0) {
                    $update_jail_ent_dt = [
                        'jailer_sign_dt'=>$_REQUEST['txt_doc_signed'],
                        'jail_display' => 'N',
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => $_SESSION['login']['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                    $is_update_jail_ent_dt = update('jail_petition_details', $update_jail_ent_dt, ['jail_display' => 'Y', 'diary_no' => $diary_no]);
               // }

            }

            $res_sclsc=is_data_from_table('sclsc_details',['display'=>'Y','diary_no'=>$diary_no],'count(id)','R');
            if($sclsc !=0) {
                if ($res_sclsc['count']==0) {
                    $ins_sclsc = [
                        'diary_no' => $diary_no,
                        'sclsc_diary_no' => $_REQUEST['txt_sclsc_no'],
                        'sclsc_diary_year' => $_REQUEST['ddl_sclsc_yr'],
                        'sclsc_ent_dt' => date("Y-m-d H:i:s"),
                        'display' => 'Y',

                        'create_modify' => date("Y-m-d H:i:s"),
                        'updated_by'=>$_SESSION['login']['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                    $is_insert_ins_sclsc = insert('sclsc_details', $ins_sclsc);
                } else {
                    $update_sclsc = [
                        'sclsc_diary_no' => $_REQUEST['txt_sclsc_no'],
                        'sclsc_diary_year' => $_REQUEST['ddl_sclsc_yr'],
                        'sclsc_ent_dt' => date("Y-m-d H:i:s"),

                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => $_SESSION['login']['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                    $is_update_sclsc = update('sclsc_details', $update_sclsc,['display'=>'Y','diary_no'=>$diary_no]);

                }

            }else if($sclsc==0){
               // if($res_sclsc['count'] > 0)   {
                    $update_sclsc = [
                        'display' => 'N',
                        'sclsc_diary_no' => NULL,
                        'sclsc_diary_year' => NULL,
                        'sclsc_ent_dt' => date("Y-m-d H:i:s"),
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => $_SESSION['login']['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                    $is_update_sclsc = update('sclsc_details', $update_sclsc,['display'=>'Y','diary_no'=>$diary_no]);

                //}
            }
            
            //code added on 10-08-2023 to update SCeFM matters in Dispatch Report of Diary users after diary modification
            //starts here

           /* $check_if_fil_user = "SELECT count(*) FROM fil_trap_users a WHERE a.usertype=101 AND a.display='Y'  and usercode='$_SESSION[dcmis_user_idd]' ";
            $check_if_fil_user_rs = mysql_query($check_if_fil_user) or die(LINE . '->' . mysql_error());

            $if_fil_user=  mysql_result($check_if_fil_user_rs, 0);*/

            $if_fil_user=is_data_from_table('fil_trap_users',['usertype'=>101,'display'=>'Y','usercode'=>$_SESSION['login']['usercode']]);
            if(!empty($if_fil_user)) {
                $is_scefm = $this->Model_diary->get_efiled_cases($diary_no);
                if (!empty($is_scefm)) {
                    if (!empty($is_scefm) && ($is_scefm['diary_no'] != 0 and $is_scefm['diary_no'] != null)) {
                        if ($is_scefm['ects_diary_no'] != 0 and $is_scefm['ects_diary_no'] != null) {
                            if ($is_scefm['diary_update_by'] == null or $is_scefm['diary_update_by'] == '') {
                                $scefm_up_qr = [
                                    'diary_update_by' => $_SESSION['login']['usercode'],
                                    'diary_update_on' => date("Y-m-d H:i:s"),

                                    'updated_on' => date("Y-m-d H:i:s"),
                                    //'updated_by' => $_SESSION['login']['usercode'],
                                    'updated_by_ip' => getClientIP(),
                                ];
                                
                                $is_scefm_up_qr = update('efiled_cases_transfer_status', $scefm_up_qr, ['diary_no' => $diary_no]);

                            }
                        } else {

                            $scefm_in_qr = [
                                'diary_no' => $diary_no,
                                'diary_update_by' => $_SESSION['login']['usercode'],
                                'diary_update_on' => date("Y-m-d H:i:s"),

                                //'create_modify' => date("Y-m-d H:i:s"),
                                //'updated_by' => $_SESSION['login']['usercode'],
                                'updated_by_ip' => getClientIP(),
                            ];
                            
                            $is_scefm_up_qr = insert('efiled_cases_transfer_status', $scefm_in_qr);

                        }
                    }
                }
            }
            
            $this->db->transComplete();
            //ends here
            $petres_name=is_data_from_table('main',['diary_no'=>$diary_no],' pet_name,res_name,pno,rno','R');
            ?>
            <div style="padding: 30px;">
            <table align="center" width="100%" style="text-align: center;">
                <thead>
                <tr><th style="color: green;">Record Updated Successfully</th></tr>
                <th>Diary No.:<h2 style="color: blue"><?php echo $_REQUEST['d_no'].'/'.$_REQUEST['d_yr'];?></h2></th></tr>
               <?php if (!empty($petres_name)) {?>
                <tr align="center" style="color: blue"><th><?php if($petres_name['pno']<=1) echo strtoupper(trim($petres_name['pet_name']));
                        else if($petres_name['pno']==2)
                            echo strtoupper(trim($petres_name['pet_name'].' and anr'));
                        else
                            echo strtoupper(trim($petres_name['pet_name'].' and ors'));
                        ?></th></tr>
                <tr align="center" ><th style="color: black">Versus !!!!!</th></tr>

                <tr align="center" style="color: blue"><th><?php if($petres_name['rno']<=1) echo strtoupper(trim($petres_name['res_name']));
                        else if($petres_name['rno']==2)
                            echo strtoupper(trim($petres_name['res_name'].' and anr'));
                        else
                            echo strtoupper(trim($petres_name['res_name'].' and ors'));
                        ?></th></tr>
                 <?php } ?>
                <tr align="center" style="color: blue"><th></th></tr>
                <tr><th></th></tr>
                 
                <tr align="center" ><th style="font-size: 17px;color: blue"><?php echo "Case Type: ".$res_nt['casename']; ?></th></tr>
                <tr align="center" ><th style="font-size: 17px;color: blue"><?php echo "Filed by: ".strtoupper(trim($_REQUEST['padvname']))."(ADV)"; ?></th></tr>

            <?php
            if (isset($_REQUEST['ddl_st_agncy']) && isset($_REQUEST['ddl_bench'])) {
                if (!empty($_REQUEST['ddl_st_agncy']) && !empty($_REQUEST['ddl_bench'])) {
                    $ref_agency_code_details=get_ref_agency_code_details($_REQUEST['ddl_st_agncy'], $_REQUEST['ddl_bench']);
                    if ($ref_agency_code_details && !empty($ref_agency_code_details)){
                        echo "<tr aligh='center'><th style='font-size: 17px;color: blue'>Bench: ".strtoupper(trim($ref_agency_code_details['agency_state']))  .' - '.strtoupper(trim($ref_agency_code_details['agency_name']))."</th></tr>";
                    }  ?>
                <?php } } ?>
                </thead>
            </table>
            </div>
            <?php

              return true;          

            // exit();
        }
    }
    /*XXXXXXXXXXXXXXXXXXX end Upadation save_new_filing XXXXXXXXXXXXXXXXXX*/
    public function additional_address_modify(){
        $diary_no=session()->get('filing_details')['diary_no'];
        if (empty($diary_no)){ echo 'additional address modify diary is required';exit(); }
        //$sno=$_REQUEST['hd_add_address'];
        $p_r=$_REQUEST['p_r'];
        $data['p_r']=$p_r;
        $data['country'] = get_from_table_json('country');
        $data['state_list'] = $this->Dropdown_list_model->get_address_state_list();
        $additional_address = $this->Model_diary->get_additional_address_details($diary_no,$p_r);
        $data['additional_address'] =$additional_address;
        if (!empty($additional_address)){
            $result=''; $data['dist_list']=array();   $sno=0;
            foreach ($additional_address as $row){
                $data['sno']=$sno;   $data['row1']=$row;
                if (!empty($row['state'])){
                    $data['dist_list'] = $this->Dropdown_list_model->get_districts_list($row['state']);
                }
                $result.= view('Filing/additional_address_modify',$data);
                 $sno++;
            }
        }
        return $result;
        exit();
    }
    public function delete_additional_address(){
        $hd_main_idP=$_REQUEST['hd_main_idP'];
        if (empty($hd_main_idP)){ echo 'additional address id is required';exit(); }
        $party_additional_address_up_qr = [
            'display' => 'N',
            'updated_on' => date("Y-m-d H:i:s"),
            'updated_by' => $_SESSION['login']['usercode'],
            'updated_by_ip' => getClientIP(),
        ];
        $is_party_additional_address = update('party_additional_address', $party_additional_address_up_qr, ['id' => $hd_main_idP]);
        if ($is_party_additional_address){
            echo "Data Deleted Successfully";
        }else{echo "Data is mot delete please try again !!!";}
        exit();
    }
    function get_hc_bench_list() {
        $high_court_id = (sanitize($_GET['high_court_id']));
        $court_type = $_GET['court_type'];
        $dropDownOptions = '<option value="">Select High Court Bench</option>';
        if (!empty($high_court_id) && !empty($court_type)) {
            $hc_benches = $this->Dropdown_list_model->get_ref_agency_code($high_court_id,$court_type);
            foreach ($hc_benches as $bench) {
                $dropDownOptions .= '<option value="' . sanitize($bench['id']) .'">' . sanitize(strtoupper($bench['agency_name'])) . '</option>';
            }
        }
        echo $dropDownOptions;
    }
}
