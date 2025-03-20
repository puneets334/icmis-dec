<?php
namespace CodeIgniter\Validation;
namespace App\Controllers\Caveat;
use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;
use App\Models\Entities\Model_CaveatA;
use App\Models\Filing\Model_caveat;
use App\Models\Filing\Model_party;
use App\Models\Master\Model_casetype;
use App\Models\Filing\Model_diary;
use CodeIgniter\Model;

class Generation extends BaseController
{
    public $LoginModel;
    public $Dropdown_list_model;
    public $Model_diary;
    public $Model_casetype;
    public $Model_party;
    public $Model_caveat;
    public $Model_caveat_a;
    function __construct()
    {
        $this->Dropdown_list_model= new Dropdown_list_model();
        $this->Model_diary= new Model_diary();
        $this->Model_casetype= new Model_casetype();
        $this->Model_party= new Model_party();
        $this->Model_caveat= new Model_caveat();
        $this->Model_caveat_a= new Model_CaveatA();

    }
    public function test(){

        //echo '<pre>';print_r($resul); exit();
    }

    public function index()
    {
        $data['country'] = get_from_table_json('country');
        $data['state'] = get_from_table_json('state');
        $data['ref_special_category_filing'] = get_from_table_json('ref_special_category_filing','Y','display');
        //$data['casetype']=get_from_table_json('casetype');
        $data['court_type_list'] = $this->Dropdown_list_model->get_court_type_list();
        $data['usersection']=$this->Dropdown_list_model->get_usersection();
        $data['state_list'] = $this->Dropdown_list_model->get_address_state_list();
        $role = $this->Model_diary->get_role_fil_trap(session()->get('login')['usercode']);
        $data['casetype']=$this->Dropdown_list_model->get_case_type('filing','nature_sci');
        $data['casetype_nature_sci']=$this->Dropdown_list_model->get_case_type('filing','nature_sci');
        $data['role']=$role;
        //echo '<pre>';print_r(session()->get('login')['type_name']);exit();
        //echo '<pre>';print_r($data['ref_special_category_filing']);exit();
        $data['diary_details']=array();
        $data['sclsc']=array();
        return view('Caveat/caveat_generation',$data);
    }


    public function getsection() {
        $data1 = $_REQUEST['q'];
        //echo'get_section anshu= '.$data1;exit();
        $this->Model_diary->get_section($data1);
        exit();
    }
    public function get_adv_name() {
        $advno=$this->request->getGet('advno');
        $advyr=$this->request->getGet('advyr');
        $flag=$this->request->getGet('flag');
        $is_ac=$this->request->getGet('is_ac');
        $state_id='';
        $p_r_advt='';
        if($flag=='P')
        {
            $state_id=$this->request->getGet('ddl_pet_adv_state');
            $p_r_advt=$this->request->getGet('padvt');
        }
        else if($flag=='R')
        {
            $state_id=$_REQUEST['ddl_res_adv_state'];
            $p_r_advt=$this->request->getGet('radvt');
        }

        $adv = $this->Dropdown_list_model->get_adv_name($p_r_advt,$advno,$advyr,$state_id);
        if (!empty($adv))
        { 
            echo $adv['name'].'~'.$adv['mobile'].'~'.$adv['email'].'~'.$adv['bar_id'].'~'.$adv['enroll_year']; 
        }else{
            echo '0'; 
        }
        exit();

    }


    /*XXXXXXXXXXXXXXXXXXX start Intertion save_new_filing XXXXXXXXXXXXXXXXXX*/
    public function save_caveat(){

        $ucode=$_SESSION['login']['usercode'];
        $year = date('Y');
        if (empty($_REQUEST['txt_court_fee'])){  $_REQUEST['txt_court_fee']=0;}

        if (!isset($_REQUEST['hd_r_barid'])){$_REQUEST['hd_r_barid']=0;}else{ if (empty($_REQUEST['hd_r_barid'])){$_REQUEST['hd_r_barid']=0;} }

        if (!isset($_REQUEST['hd_p_barid'])){$_REQUEST['hd_p_barid']=0;}else{ if (empty($_REQUEST['hd_p_barid'])){$_REQUEST['hd_p_barid']=0;} }
        if (!isset($_REQUEST['hd_r_barid'])){$_REQUEST['hd_r_barid']=0;}else{ if (empty($_REQUEST['hd_r_barid'])){$_REQUEST['hd_r_barid']=0;} }
        $padvno_and_yr = $_REQUEST['hd_p_barid'];
        $radvno_and_yr = $_REQUEST['hd_r_barid'];

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
        if (!isset($_REQUEST['p_sex'])){$_REQUEST['p_sex']=null;}
        if (!isset($_REQUEST['pet_rel'])){$_REQUEST['pet_rel']=' ';}else{ if (empty($_REQUEST['pet_rel'])){$_REQUEST['pet_rel']=' ';} }

        if (!isset($_REQUEST['res_rel_name'])){$_REQUEST['res_rel_name']='';}
        if (!isset($_REQUEST['r_age'])){$_REQUEST['r_age']=0;}
        if (!isset($_REQUEST['r_sex'])){$_REQUEST['r_sex']=null;}
        //if (!isset($_REQUEST['res_rel'])){$_REQUEST['res_rel']=' ';}
        if (!isset($_REQUEST['res_rel'])){$_REQUEST['res_rel']=' ';}else{ if (empty($_REQUEST['res_rel'])){$_REQUEST['res_rel']=' ';} }
        $this->db = \Config\Database::connect();
        $this->db->transStart();

        if($_REQUEST['controller']=='I') {
            echo '!~!';
            $pet_name='';
            $res_name='';
            $fil_q=is_data_from_table('master.cnt_caveat',['caveat_year'=>$year],'max_caveat_no','R');
            $fil=!empty($fil_q) ? $fil_q['max_caveat_no'] : 0;
            $fil++;
            $diary_no = $fil.$year;
            $efil = 0;
            $efil_no = 0;
            $efil_yr = '';
            if (!isset($_REQUEST['case_doc'])){$_REQUEST['case_doc']=0;}else{ $_REQUEST['case_doc'] = (!empty($_REQUEST['case_doc'])) ? $_REQUEST['case_doc'] :0; }
            if ($_REQUEST['st_status'] == '0') {

                $da = 0;
                $res_nt = $this->Model_casetype->select('nature,casename')->where('casecode', $_REQUEST['ddl_nature'])->get()->getRowArray();
                if (empty($res_nt)){$res_nt['nature']=null;}
                //$res_nt= $res_nt['nature'];
                //$res_nt['casename'];
                $pet_cause_title = $res_cause_title = '';

                if ($_REQUEST['p_type'] == 'I' && $_REQUEST['r_type'] == 'I') {

                    $pet_cause_title = strtoupper(trim($_REQUEST['pname']));
                    $res_cause_title = strtoupper(trim($_REQUEST['rname']));

                } else if ($_REQUEST['p_type'] == 'I' && $_REQUEST['r_type'] != 'I') {

                    $pet_cause_title = strtoupper(trim($_REQUEST['pname']));
                    $res_cause_title = strtoupper(trim($_REQUEST['res_deptt']));

                } else if ($_REQUEST['p_type'] != 'I' && $_REQUEST['r_type'] == 'I') {

                    $pet_cause_title = strtoupper(trim($_REQUEST['pet_deptt']));
                    $res_cause_title = strtoupper(trim($_REQUEST['rname']));

                } else if ($_REQUEST['p_type'] != 'I' && $_REQUEST['r_type'] != 'I') {

                    $pet_cause_title = strtoupper(trim($_REQUEST['pet_deptt']));
                    $res_cause_title = strtoupper(trim($_REQUEST['res_deptt']));
                }
                $insert_q=[
                    'pet_name'=> $pet_cause_title,
                    'res_name'=> $res_cause_title,
                    'pet_adv_id'=> $padvno_and_yr,
                    'res_adv_id'=> $radvno_and_yr,
                    'caveat_no'=> $diary_no,
                    'diary_no_rec_date'=> date("Y-m-d H:i:s"),
                    'diary_user_id'=> $ucode,
                    'ref_agency_state_id'=> $_REQUEST['ddl_st_agncy'],
                    'ref_agency_code_id'=> $_REQUEST['ddl_bench'],
                    'c_status'=> 'P',
                    'case_grp'=> $res_nt['nature'],
                    'casetype_id'=> $_REQUEST['ddl_nature'],
                    'from_court'=> $_REQUEST['ddl_court'],
                    'padvt'=> trim($_REQUEST['padtype']),
                    'radvt'=> trim($_REQUEST['radtype']),
                    'case_status_id'=>1,
                    'nature'=>1,// $_REQUEST['type_special'],
                    'pno'=> $_REQUEST['t_pet'],
                    'rno'=> $_REQUEST['t_res'],
                    'court_fee'=> $_REQUEST['txt_court_fee'],
                    'casetype_name'=> ' ',

                    'scr_user'=> 0,
                    'scr_type'=>'',
                    'undertaking_doc_type'=>0,
                    'undertaking_reason'=>'',
                    'total_court_fee'=>$_REQUEST['txt_court_fee'],
                    'valuation'=>0,
                    'brief_description'=>'',
                    'ack_id'=> $efil_no,
                    'ack_rec_dt'=> $efil_yr,
                    'fil_no_fh'=> 0,
                    'mf_active'=>'',
                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];
                echo "<br>";
                // $is_Model_caveat=$this->Model_party->insert($insert_q); // insert but error return reason pk not default id
                 $is_Model_caveat=insert('caveat',$insert_q);
            }

       if ($_REQUEST['p_type'] == 'I') {

                $insert_party1_p_q=[
                    'pet_res'=>'P',
                    'sr_no'=> 1,
                    'ind_dep'=>$_REQUEST['p_type'],
                    'partyname'=>(!empty($_REQUEST['pname'])) ? strtoupper(trim($_REQUEST['pname'])) :null,
                    'partysuff'=>(!empty($_REQUEST['pname'])) ? strtoupper(trim($_REQUEST['pname'])) :null,
                    'prfhname'=>(!empty($_REQUEST['pet_rel_name'])) ? strtoupper(trim($_REQUEST['pet_rel_name'])) :null,
                    'age'=> (!empty($_REQUEST['p_age'])) ? $_REQUEST['p_age'] :null,
                    'sex'=>(!empty($_REQUEST['p_sex'])) ? $_REQUEST['p_sex'] :null,
                    'addr1'=>(!empty($_REQUEST['pocc'])) ? strtoupper(trim($_REQUEST['pocc'])) :null,
                    'addr2'=>(!empty($_REQUEST['padd'])) ? strtoupper(trim($_REQUEST['padd'])) :null,
                    'dstname'=>(!empty($_REQUEST['pcity'])) ? strtoupper(trim($_REQUEST['pcity'])) :null,
                    'state'=>(!empty($_REQUEST['pst'])) ? $_REQUEST['pst'] :null,
                    'city'=>(!empty($_REQUEST['pdis'])) ? $_REQUEST['pdis'] :null,
                    'pin'=>(!empty($_REQUEST['pp'])) ? $_REQUEST['pp'] :null,
                    'email'=>(!empty($_REQUEST['pemail'])) ? $_REQUEST['pemail'] :null,
                    'contact'=>(!empty($_REQUEST['pmob'])) ? $_REQUEST['pmob'] :null,
                    'usercode'=> $ucode,
                    'ent_dt'=>  date("Y-m-d H:i:s"),
                    'pflag'=> 'P',
                    'sonof'=>(!empty($_REQUEST['pet_rel'])) ? $_REQUEST['pet_rel'] :null,
                    'authcode'=>0,
                    'deptcode'=> 0,
                    'caveat_no'=> $diary_no,
                    'country'=>(!empty($_REQUEST['p_cont'])) ? $_REQUEST['p_cont'] :null,

                    'state_in_name'=>0,
                    'pan_card'=>' ',
                    'adhar_card'=>' ',
                    'education'=>' ',
                    'occ_code'=>0,
                    'edu_code'=>0,
                    'lowercase_id'=>0,


                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];

            } else if ($_REQUEST['p_type'] != 'I') {

                $insert_party1_p_q=[
                    'pet_res'=> 'P',
                    'sr_no '=> 1,
                    'ind_dep'=> $_REQUEST['p_type'],
                    'partyname'=> $pet_cause_title,
                    'partysuff'=>(!empty($_REQUEST['pet_statename']) || !empty($_REQUEST['pet_deptt'])) ? strtoupper(trim($_REQUEST['pet_statename'].' '.$_REQUEST['pet_deptt'])) :null,
                    'prfhname'=>strtoupper(trim($_REQUEST['pet_rel_name'])),
                    'age'=>(!empty($_REQUEST['p_age'])) ? $_REQUEST['p_age'] :null,
                    'sex'=>(!empty($_REQUEST['p_sex'])) ? $_REQUEST['p_sex'] :null,
                    'addr1'=>(!empty($_REQUEST['pet_post'])) ? strtoupper(trim($_REQUEST['pet_post'])) :null,
                    'addr2'=>(!empty($_REQUEST['padd'])) ? strtoupper(trim($_REQUEST['padd'])) :null,
                    'dstname'=>(!empty($_REQUEST['pcity'])) ? strtoupper(trim($_REQUEST['pcity'])) :null,
                    'state'=>(!empty($_REQUEST['pst'])) ? $_REQUEST['pst'] :null,
                    'city'=>(!empty($_REQUEST['pdis'])) ? $_REQUEST['pdis'] :null,
                    'pin'=>(!empty($_REQUEST['pp'])) ? $_REQUEST['pp'] :null,
                    'email'=>(!empty($_REQUEST['pemail'])) ? $_REQUEST['pemail'] :null,
                    'contact'=>(!empty($_REQUEST['pmob'])) ? $_REQUEST['pmob'] : null,
                    'usercode'=> $ucode,
                    'ent_dt'=>  date('Y-m-d'),
                    'pflag'=> 'P',
                    'sonof'=>(!empty($_REQUEST['pet_rel'])) ? $_REQUEST['pet_rel'] : null,
                    'authcode'=>(!empty($_REQUEST['pp_code'])) ? $_REQUEST['pp_code'] : null,
                    'deptcode'=>(!empty($_REQUEST['pd_code'])) ? $_REQUEST['pd_code'] : null,
                    'caveat_no'=> $diary_no,
                    'state_in_name'=> (!empty($_REQUEST['pet_statename_hd'])) ? $_REQUEST['pet_statename_hd'] :null,
                    'country'=>(!empty($_REQUEST['p_cont'])) ? $_REQUEST['p_cont'] : null,

                    'pan_card'=>' ',
                    'adhar_card'=>' ',
                    'education'=>' ',
                    'occ_code'=>0,
                    'edu_code'=>0,
                    'lowercase_id'=>0,

                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];

            }

            if ($_REQUEST['r_type'] == 'I'){

                $insert_party1_r_q=[
                    'pet_res'=> 'R',
                    'sr_no '=> 1,
                    'ind_dep'=>(!empty($_REQUEST['r_type'])) ? $_REQUEST['r_type'] :null,
                    'partyname'=>(!empty($_REQUEST['rname'])) ? strtoupper(trim($_REQUEST['rname'])) :null,
                    'partysuff'=>(!empty($_REQUEST['rname'])) ? strtoupper(trim($_REQUEST['rname'])) :null,
                    'prfhname'=>(!empty($_REQUEST['res_rel_name'])) ? strtoupper(trim($_REQUEST['res_rel_name'])) :null,
                    'age'=>(!empty($_REQUEST['r_age'])) ? $_REQUEST['r_age'] :null,
                    'sex'=>(!empty($_REQUEST['r_sex'])) ? $_REQUEST['r_sex'] :null,
                    'addr1'=>(!empty($_REQUEST['rocc'])) ? strtoupper(trim($_REQUEST['rocc'])) :null,
                    'addr2'=>(!empty($_REQUEST['radd'])) ? strtoupper(trim($_REQUEST['radd'])) :null,
                    'dstname'=>(!empty($_REQUEST['rcity'])) ? strtoupper(trim($_REQUEST['rcity'])) :null,
                    'state'=>(!empty($_REQUEST['rst'])) ? $_REQUEST['rst'] :null,
                    'city'=>(!empty($_REQUEST['rdis'])) ? $_REQUEST['rdis'] :null,
                    'pin'=>(!empty($_REQUEST['rp'])) ? $_REQUEST['rp'] :null,
                    'email'=>(!empty($_REQUEST['remail'])) ? $_REQUEST['remail'] :null,
                    'contact'=>(!empty($_REQUEST['rmob'])) ? $_REQUEST['rmob'] :null,
                    'usercode'=>(!empty($ucode)) ? $ucode :null,
                    'ent_dt'=>  date("Y-m-d H:i:s"),
                    'pflag'=> 'P',
                    'sonof'=>(!empty($_REQUEST['res_rel'])) ? $_REQUEST['res_rel'] :null,
                    'authcode'=> 0,
                    'deptcode'=> 0,
                    'caveat_no'=> $diary_no,
                    'country'=>(!empty($_REQUEST['r_cont'])) ? $_REQUEST['r_cont'] :null,

                    'state_in_name'=>0,
                    'pan_card'=>' ',
                    'adhar_card'=>' ',
                    'education'=>' ',
                    'occ_code'=>0,
                    'edu_code'=>0,
                    'lowercase_id'=>0,

                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];


            }else if($_REQUEST['r_type']!='I') {

                $insert_party1_r_q=[
                    'pet_res'=> 'R',
                    'sr_no '=> 1,
                    'ind_dep'=> (!empty($_REQUEST['r_type'])) ? $_REQUEST['r_type'] :null,
                    'partyname'=> $res_cause_title,
                    'partysuff'=>(!empty($_REQUEST['res_statename']) || !empty($_REQUEST['res_deptt'])) ? strtoupper(trim($_REQUEST['res_statename'].' '.$_REQUEST['res_deptt'])) :null,
                    'prfhname'=>(!empty($_REQUEST['res_rel_name'])) ? strtoupper(trim($_REQUEST['res_rel_name'])) :null,
                    'age'=>(!empty($_REQUEST['r_age'])) ? $_REQUEST['r_age'] :null,
                    'sex'=> (!empty($_REQUEST['r_sex'])) ? $_REQUEST['r_sex'] :null,
                    'addr1'=>(!empty($_REQUEST['res_post'])) ? strtoupper(trim($_REQUEST['res_post'])) :null ,
                    'addr2'=>(!empty($_REQUEST['radd'])) ? strtoupper(trim($_REQUEST['radd'])) :null ,
                    'dstname'=>(!empty($_REQUEST['rcity'])) ? strtoupper(trim($_REQUEST['rcity'])) :null ,
                    'state'=>(!empty($_REQUEST['rst'])) ? $_REQUEST['rst'] :null,
                    'city'=>(!empty($_REQUEST['rdis'])) ? $_REQUEST['rdis'] :null,
                    'pin'=>(!empty($_REQUEST['rp'])) ? $_REQUEST['rp'] :null,
                    'email'=>(!empty($_REQUEST['remail'])) ? $_REQUEST['remail'] :null,
                    'contact'=> (!empty($_REQUEST['rmob'])) ? $_REQUEST['rmob'] :null,
                    'usercode'=>(!empty($ucode)) ? $ucode :null,
                    'ent_dt'=>  date("Y-m-d H:i:s"),
                    'pflag'=> 'P',
                    'sonof'=>(!empty($_REQUEST['res_rel'])) ? $_REQUEST['res_rel'] :null,
                    'authcode'=>(!empty($_REQUEST['rp_code'])) ? $_REQUEST['rp_code'] :0,
                    'deptcode'=>(!empty($_REQUEST['rd_code'])) ? $_REQUEST['rd_code'] :null,
                    'caveat_no'=> $diary_no,
                    'state_in_name'=>(!empty($_REQUEST['res_statename_hd'])) ? $_REQUEST['res_statename_hd'] :null,
                    'country'=>(!empty($_REQUEST['r_cont'])) ? $_REQUEST['r_cont'] :null,

                    'pan_card'=>' ',
                    'adhar_card'=>' ',
                    'education'=>' ',
                    'occ_code'=>0,
                    'edu_code'=>0,
                    'lowercase_id'=>0,

                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];

            }

            $is_caveat_party_p=insert('caveat_party',$insert_party1_p_q);
            $iscaveat_party_r=insert('caveat_party',$insert_party1_r_q);

            if($_REQUEST['hd_mn']!='' && $_REQUEST['cs_tp']!='' && $_REQUEST['txtFNo']!='' && $_REQUEST['txtYear']!='')
            {
                $case_no=$cs_tp.$_REQUEST['txtFNo'].$_REQUEST['txtYear'];
                $_REQUEST['txtFNo']=ltrim($_REQUEST['txtFNo'],0);
                $ins_l_c=[
                    'ct_code'=> $_REQUEST['ddl_court'],
                    'l_state '=>$_REQUEST['ddl_st_agncy'],
                    'l_dist'=>$_REQUEST['ddl_bench'],
                    'caveat_no'=> $diary_no,
                    'lw_display'=> 'R',
                    'lct_casetype'=>$_REQUEST['cs_tp'],
                    'lct_caseno'=> $_REQUEST['txtFNo'],
                    'lct_caseyear'=> $_REQUEST['txtYear'],

                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];
                $lowerct_last_insert=insert('caveat_lowerct',$ins_l_c);
            }

            $update_cnt_diary_no=[
                    'max_caveat_no'=>$fil,
                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by' => session()->get('login')['usercode'],
                'updated_by_ip' => getClientIP(),
            ];
            $is_efected_max_diary_no=update('master.cnt_caveat',$update_cnt_diary_no,['caveat_year'=>$year]);

            if($padvno_and_yr!=0)
            {
                $ins_adv_pet=[
                    'caveat_no'=> $diary_no,
                    'adv_type '=>'M',
                    'pet_res'=>'P',
                    'pet_res_no'=> 1,
                    'advocate_id'=> $padvno_and_yr,
                    'usercode'=>$ucode,
                    'ent_dt'=>  date("Y-m-d H:i:s"),
                    'display'=> 'Y',
                    'stateadv'=>'N',
                    'old_adv'=> ' ',

                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];

                $advocate_pet_ins_adv=insert('caveat_advocate',$ins_adv_pet);

            }
            if($radvno_and_yr!=0)
            {
                $ins_adv_res=[
                    'caveat_no'=> $diary_no,
                    'adv_type '=>'M',
                    'pet_res'=>'R',
                    'pet_res_no'=> 1,
                    'advocate_id'=> $radvno_and_yr,
                    'usercode'=>$ucode,
                    'ent_dt'=>  date("Y-m-d H:i:s"),
                    'display'=> 'Y',
                    'stateadv'=>'N',
                    'old_adv'=> ' ',

                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];

                $advocate_res_ins_adv=insert('caveat_advocate',$ins_adv_res);

            }
            //echo '<br>advocate end point line done';
            $_SESSION['session_cav_no']=$fil;
            $_SESSION['session_cav_yr']=$year;
            ?>
            <table align="center" width="100%" style="text-align: center">
                <thead>
                <tr align="center">
                    <th>Caveat No.:<h2 style="color: blue"><?php echo $fil.'/'.$year;?></h2></th></tr>
                <?php if($_REQUEST['p_type']=='I'){?>
                    <tr align="center" ><th style="font-size: 17px;color: blue"><?php echo strtoupper(trim($_REQUEST['pname']))?></th></tr>
                <?php }else if($_REQUEST['p_type']!='I'){?>
                    <tr align="center" ><th style="font-size: 17px;color: blue"><?php echo strtoupper(trim($_REQUEST['pet_deptt']));?></th></tr>
                <?php }?>
                <tr align="center" ><th style="font-size: 14px;color: blue">Versus</th></tr>
                <?php if($_REQUEST['r_type']=='I'){?>
                    <tr align="center" ><th style="font-size: 17px;color: blue"><?php echo strtoupper(trim($_REQUEST['rname']))?></th></tr>
                <?php }else if($_REQUEST['r_type']!='I'){?>
                    <tr align="center" ><th style="font-size: 17px;color: blue"><?php echo strtoupper(trim($_REQUEST['res_deptt']));?></th></tr>
                <?php }?>
                </thead>
            </table>
            <p><?php echo strtoupper(trim($_REQUEST['padvname']));?><?php echo date('d-m-Y h:i:s A');?></p>
            <?php

            if(strlen($pet_name)>30){
                $pet_name=str_replace(substr($pet_name, 27, strlen($pet_name)),'...',$pet_name) ;
            }
            if(strlen($res_name)>30){
                $res_name=str_replace(substr($res_name, 27, strlen($res_name)),'...',$res_name) ;
            }

            /*$sms_data['sms_status']='CAVEAT_FILING';
            $sms_data['caveat_no']=$fil.$year;
            $sms_data['msg']="Your Caveat with cause title ".$pet_name." vs ".$res_name." has been Registered as Caveat No. ".$fil."/".$year.". -Supreme Court of India";
            send_sms('CAVEAT_FILING',$sms_data);*/

            /* $_REQUEST['sms_status']='CAVEAT_FILING';
             $_REQUEST['caveat_no']=$fil.$year;
             $_REQUEST['msg']="Your Caveat with cause title ".$pet_name." vs ".$res_name." has been Registered as Caveat No. ".$fil."/".$year.". -Supreme Court of India";

             include ('../sms/send_sms.php');
            send_sms($_REQUEST['sms_status'],$_REQUEST['caveat_no'],$_REQUEST['msg']);
            */
            ?>

            <?php // echo substr($fil_no,3,7).substr($fil_no,12,2);?>
        <?php }


        $this->db->transComplete();

        /*
        if($this->db->transStatus() === FALSE)
            return FALSE;
        else
            return TRUE ;*/

        //all part of done after end point
        exit();


    }

    /*XXXXXXXXXXXXXXXXXXX end Intertion save_new_filing XXXXXXXXXXXXXXXXXX*/


}
