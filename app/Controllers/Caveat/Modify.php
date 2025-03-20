<?php
namespace CodeIgniter\Validation;
namespace App\Controllers\Caveat;
use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;
use App\Models\Entities\Model_Bar;
use App\Models\Entities\Model_CaveatA;
use App\Models\Entities\Model_CaveatPartyA;
use App\Models\Filing\Model_caveat;
use App\Models\Filing\Model_party;
use App\Models\Master\Model_casetype;
use App\Models\Filing\Model_diary;
use App\Models\Entities\Model_CaveatParty;

class Modify extends BaseController
{
    public $LoginModel;
    public $Dropdown_list_model;
    public $Model_diary;
    public $Model_casetype;
    public $Model_party;
    public $Model_caveat;
    public $Model_caveat_a;
    public $Model_CaveatParty;
    public $Model_CaveatPartyA;
    public $Model_Bar;
    function __construct()
    {
        if (isset($_SESSION['login'])) {
            if (!isset($_SESSION['caveat_details'])) { header('Location:'.base_url('Caveat/Search'));exit(); }
        }else{  header('Location:'.base_url('Signout'));exit(); }
        $this->Dropdown_list_model= new Dropdown_list_model();
        $this->Model_diary= new Model_diary();
        $this->Model_casetype= new Model_casetype();
        $this->Model_party= new Model_party();
        $this->Model_caveat= new Model_caveat();
        $this->Model_caveat_a= new Model_CaveatA();
        $this->Model_CaveatParty= new Model_CaveatParty();
        $this->Model_CaveatPartyA= new Model_CaveatPartyA();
        $this->Model_Bar= new Model_Bar();

    }
    public function test(){


        echo '<pre>';print_r($_SESSION['login']); exit();
    }

    public function index()
    {   
        $caveat_no=$_SESSION['caveat_details']['caveat_no'];
        $data['pet_dist_list']=array();
        $data['res_dist_list']=array();
        $data['petadv_info_rw']=array();
        $data['resadv_info_rw']=array();
        $data['country'] = get_from_table_json('country');
        //$data['state'] =is_data_from_table('master.ref_agency_state',['is_deleted'=>'False'],'cmis_state_id,agency_state as state_name');
        $data['state'] = $this->Dropdown_list_model->get_address_state_list();
        $data['ref_special_category_filing'] = get_from_table_json('ref_special_category_filing','Y','display');
        $data['court_type_list'] = $this->Dropdown_list_model->get_court_type_list();
        $data['usersection']=$this->Dropdown_list_model->get_usersection();
        $data['state_list'] = $this->Dropdown_list_model->get_address_state_list();
        $role = $this->Model_diary->get_role_fil_trap(session()->get('login')['usercode']);
        $data['casetype']=$this->Dropdown_list_model->get_case_type('filing','nature_sci');
        $data['casetype_nature_sci']=$this->Dropdown_list_model->get_case_type('filing','nature_sci');
        $data['role']=$role;
        $data['is_c_status']='P';
        $fetch_rw=$this->Model_caveat->select('*')->where(['caveat_no'=>$caveat_no])->get()->getRowArray();
        if (empty($fetch_rw)){
            $fetch_rw=$this->Model_caveat_a->select('*')->where(['caveat_no'=>$caveat_no])->get()->getRowArray();
            $data['is_c_status']='D';
        }
        $data['hc_benches'] = $this->Dropdown_list_model->get_ref_agency_code($fetch_rw['ref_agency_state_id'],$fetch_rw['from_court']);
        //echo '<pre>';print_r(session()->get('login')['type_name']);exit();
        //echo '<pre>';print_r($data['ref_special_category_filing']);exit();

        $this->Model_CaveatParty->join('master.deptt b', 'caveat_party.state_in_name=b.deptcode','left');
        $data['pet_rw']=$this->Model_CaveatParty->select('caveat_party.*,b.deptname')->where(['caveat_no'=>$caveat_no,'pet_res'=>'P','pflag'=>'P','sr_no'=>1])->get()->getRowArray();
        if (empty($data['pet_rw'])){
            $this->Model_CaveatPartyA->join('master.deptt b', 'caveat_party_a.state_in_name=b.deptcode','left');
            $data['pet_rw']=$this->Model_CaveatPartyA->select('caveat_party_a.*,b.deptname')->where(['caveat_no'=>$caveat_no,'pet_res'=>'P','pflag'=>'P','sr_no'=>1])->get()->getRowArray();

        }
        $this->Model_CaveatParty->join('master.deptt b', 'caveat_party.state_in_name=b.deptcode','left');
        $data['res_rw']=$this->Model_CaveatParty->select('caveat_party.*,b.deptname')->where(['caveat_no'=>$caveat_no,'pet_res'=>'R','pflag'=>'P','sr_no'=>1])->get()->getRowArray();
        if (empty($data['res_rw'])){
            $this->Model_CaveatPartyA->join('master.deptt b', 'caveat_party_a.state_in_name=b.deptcode','left');
            $data['res_rw']=$this->Model_CaveatPartyA->select('caveat_party_a.*,b.deptname')->where(['caveat_no'=>$caveat_no,'pet_res'=>'R','pflag'=>'P','sr_no'=>1])->get()->getRowArray();
        }
        if (!empty($data['pet_rw']) && !empty($data['pet_rw']['city'])){
            $data['pet_dist_list'] = $this->Dropdown_list_model->get_districts_list($data['pet_rw']['city']);
        }
        if (!empty($data['res_rw']) && !empty($data['res_rw']['city'])){
            $data['res_dist_list'] = $this->Dropdown_list_model->get_districts_list($data['res_rw']['city']);
        }
        if (!empty($fetch_rw) && !empty($fetch_rw['pet_adv_id'])){
            $data['petadv_info_rw'] = $this->Model_Bar->select("mobile,email,enroll_no,TO_CHAR(enroll_date, 'YYYY') as enroll_date,state_id,name,aor_code")->where(['bar_id'=>$fetch_rw['pet_adv_id']])->get()->getRowArray();
        }
        if (!empty($fetch_rw) && !empty($fetch_rw['res_adv_id'])) {
            $data['resadv_info_rw'] = $this->Model_Bar->select("mobile,email,enroll_no,TO_CHAR(enroll_date, 'YYYY') as enroll_date,state_id,name,aor_code")->where(['bar_id'=>$fetch_rw['res_adv_id']])->get()->getRowArray();
        }
        $data['update_button']='';
        if (!empty($data['is_c_status']) && $data['is_c_status']=='P'){
            $data['update_button']="<input type='button' class='btn btn-success' value='Update' onclick='call_update_main()' id='svbtn' onkeydown='if (event.keyCode == 13) document.getElementById('svbtn').click()'>";
        }
        $data['fetch_rw']=$fetch_rw;
        return view('Caveat/caveat_modify',$data);
    }


    function getsection() {
        $data1 = $_REQUEST['q'];
        //echo'get_section anshu= '.$data1;exit();
        $this->Model_diary->get_section($data1);
        exit();
    }
    function get_adv_name() {
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
        if (!empty($adv)){ echo $adv['name'].'~'.$adv['mobile'].'~'.$adv['email'].'~'.$adv['bar_id'].'~'.$adv['enroll_year']; }else{echo '0'; }
        exit();

    }


    /*XXXXXXXXXXXXXXXXXXX start Updation caveat XXXXXXXXXXXXXXXXXX*/
    public function save_caveat(){

        $ucode=$_SESSION['login']['usercode'];
        $year = date('Y');
        if (empty($_REQUEST['txt_court_fee'])){  $_REQUEST['txt_court_fee']=0;}
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

        if($_REQUEST['controller']=='U') {
            $caveat_details= session()->get('caveat_details');
            $caveat_no=$caveat_details['caveat_no'];
            $d_y= $_REQUEST['d_no'].$_REQUEST['d_yr'];
            echo '!~!';
            if ((empty($caveat_no) || empty($d_y)) || ($caveat_no!=$d_y)){echo 'Caveat no. and Caveat Year is required';exit();}
            $pet_name=''; $res_name=''; $efil = 0; $efil_no = 0; $efil_yr = ''; $in_sup="";$ctrl=0;$insup2="";
            if (!isset($_REQUEST['case_doc'])){$_REQUEST['case_doc']=0;}else{ $_REQUEST['case_doc'] = (!empty($_REQUEST['case_doc'])) ? $_REQUEST['case_doc'] :0; }
            if ((!empty($caveat_no) && !empty($d_y)) && ($caveat_no==$d_y)) {
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
                $update_main=[
                    'pet_name'=> $pet_cause_title,
                    'res_name'=> $res_cause_title,
                    'case_pages'=> $_REQUEST['case_doc'],
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
                    'pno'=> $_REQUEST['t_pet'],
                    'rno'=> $_REQUEST['t_res'],
                    'court_fee'=> $_REQUEST['txt_court_fee'],

                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];
                echo "<br>";
               if (!empty($update_main) && !empty($caveat_no)){
                    $update_query_result=update('caveat',$update_main,['caveat_no'=>$caveat_no]);
                }
            }

            if ($_REQUEST['p_type'] == 'I') {

                $update_party1_p_q=[
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
                    'sonof'=>(!empty($_REQUEST['pet_rel'])) ? $_REQUEST['pet_rel'] :null,
                    'authcode'=>0,
                    'deptcode'=> 0,
                    'country'=>(!empty($_REQUEST['p_cont'])) ? $_REQUEST['p_cont'] :null,

                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];
                $update_query_result=update('caveat_party',$update_party1_p_q,['caveat_no'=>$caveat_no,'pet_res'=>'P','sr_no'=>1]);
            } else if ($_REQUEST['p_type'] != 'I') {

                $update_party1_p_q=[
                    'ind_dep'=> $_REQUEST['p_type'],
                    'partyname'=> (!empty($_REQUEST['pet_statename']) || !empty($_REQUEST['pet_deptt'])) ? strtoupper(trim($_REQUEST['pet_statename'].' '.$_REQUEST['pet_deptt'].' '.$_REQUEST['pet_post'])) :null,
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
                    'sonof'=>(!empty($_REQUEST['pet_rel'])) ? $_REQUEST['pet_rel'] : null,
                    'authcode'=>(!empty($_REQUEST['pp_code'])) ? $_REQUEST['pp_code'] : 0,
                    'deptcode'=>(!empty($_REQUEST['pd_code'])) ? $_REQUEST['pd_code'] : 0,
                    'state_in_name'=> (!empty($_REQUEST['pet_statename_hd'])) ? $_REQUEST['pet_statename_hd'] :null,
                    'country'=>(!empty($_REQUEST['p_cont'])) ? $_REQUEST['p_cont'] : null,

                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];
                $update_query_result=update('caveat_party',$update_party1_p_q,['caveat_no'=>$caveat_no,'pet_res'=>'P','sr_no'=>1]);
            }

            if ($_REQUEST['r_type'] == 'I'){

                $update_party1_r_q=[
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
                    'sonof'=>(!empty($_REQUEST['res_rel'])) ? $_REQUEST['res_rel'] :null,
                    'authcode'=> 0,
                    'deptcode'=> 0,
                    'country'=>(!empty($_REQUEST['r_cont'])) ? $_REQUEST['r_cont'] :null,

                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];
                $update_query_result=update('caveat_party',$update_party1_r_q,['caveat_no'=>$caveat_no,'pet_res'=>'R','sr_no'=>1]);

            }else if($_REQUEST['r_type']!='I') {

                $update_party1_r_q=[
                    'ind_dep'=> (!empty($_REQUEST['r_type'])) ? $_REQUEST['r_type'] :null,
                    'partyname'=> (!empty($_REQUEST['res_statename']) || !empty($_REQUEST['res_deptt'])) ? strtoupper(trim($_REQUEST['res_statename'].' '.$_REQUEST['res_deptt'].' '.$_REQUEST['res_post'])) :null,
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
                    'sonof'=>(!empty($_REQUEST['res_rel'])) ? $_REQUEST['res_rel'] :null,
                    'authcode'=>(!empty($_REQUEST['rp_code'])) ? $_REQUEST['rp_code'] :0,
                    'deptcode'=>(!empty($_REQUEST['rd_code'])) ? $_REQUEST['rd_code'] :0,
                    'state_in_name'=>(!empty($_REQUEST['res_statename_hd'])) ? $_REQUEST['res_statename_hd'] :null,
                    'country'=>(!empty($_REQUEST['r_cont'])) ? $_REQUEST['r_cont'] :null,

                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];
                $update_query_result=update('caveat_party',$update_party1_r_q,['caveat_no'=>$caveat_no,'pet_res'=>'R','sr_no'=>1]);
            }

            $case_no=0;
            if($_REQUEST['hd_mn']!='' && $_REQUEST['cs_tp']!='' && $_REQUEST['txtFNo']!='' && $_REQUEST['txtYear']!='')
            {
                $res_chk_lc=is_data_from_table('caveat_lowerct',['caveat_no'=>$caveat_no],'*','R');
                $case_no=$cs_tp.$_REQUEST['txtFNo'].$_REQUEST['txtYear'];
                $_REQUEST['txtFNo']=ltrim($_REQUEST['txtFNo'],0);
                if (empty($res_chk_lc)){
                    $ins_l_c=[
                        'ct_code'=> $_REQUEST['ddl_court'],
                        'l_state '=>$_REQUEST['ddl_st_agncy'],
                        'l_dist'=>$_REQUEST['ddl_bench'],
                        'caveat_no'=> $caveat_no,
                        'lw_display'=> 'R',
                        'lct_casetype'=>$_REQUEST['cs_tp'],
                        'lct_caseno'=> $_REQUEST['txtFNo'],
                        'lct_caseyear'=> $_REQUEST['txtYear'],

                        'create_modify' => date("Y-m-d H:i:s"),
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                    $lowerct_last_insert=insert('caveat_lowerct',$ins_l_c);

                }else if (!empty($res_chk_lc) && $res_chk_lc['lw_display']=='R'){
                    $update_l_c=[
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
                    $update_query_result=update('caveat_lowerct',$update_l_c,['caveat_no'=>$caveat_no,'lw_display'=>'R']);
                }

            }

            if($padvno_and_yr!=0)
            {
                $chk_adv_p=is_data_from_table('caveat_advocate',['caveat_no'=>$caveat_no,'adv_type'=>'M','pet_res'=>'P','pet_res_no'=>1,'display'=>'Y'],'*','R');
                if (!empty($chk_adv_p)){
                    $update_adv_pet=[
                        'advocate_id'=> $padvno_and_yr,
                        'usercode'=>$ucode,
                        'ent_dt'=>  date("Y-m-d H:i:s"),
                        'stateadv'=>'N',

                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                    $update_query_result=update('caveat_advocate',$update_adv_pet,['caveat_no'=>$caveat_no,'adv_type'=>'M','pet_res'=>'P','pet_res_no'=>1,'display'=>'Y']);
                }else{
                    $ins_adv_pet=[
                        'caveat_no'=> $caveat_no,
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
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];

                    $advocate_pet_ins_adv=insert('caveat_advocate',$ins_adv_pet);
                }


            }
            if($radvno_and_yr!=0)
            {
                $chk_adv_r=is_data_from_table('caveat_advocate',['caveat_no'=>$caveat_no,'adv_type'=>'M','pet_res'=>'R','pet_res_no'=>1,'display'=>'Y'],'*','R');
                if (!empty($chk_adv_r)){
                    $update_adv_res=[
                        'advocate_id'=> $radvno_and_yr,
                        'usercode'=>$ucode,
                        'ent_dt'=>  date("Y-m-d H:i:s"),
                        'stateadv'=>'N',

                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                    $update_query_result=update('caveat_advocate',$update_adv_res,['caveat_no'=>$caveat_no,'adv_type'=>'M','pet_res'=>'R','pet_res_no'=>1,'display'=>'Y']);

                }else{
                    $ins_adv_res=[
                        'caveat_no'=> $caveat_no,
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
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];

                    $advocate_res_ins_adv=insert('caveat_advocate',$ins_adv_res);
                }


            }
            ?>

            <table align="center" width="100%">
                <thead>
                <tr align="center"><th style="color: green">Record Updated Successfully</th></tr>
                <tr align="center"><th>Caveat No.:<h2 style="color: blue"><?php echo substr($caveat_details['caveat_no'], 0, -4).'/'.substr($caveat_details['caveat_no'],-4);?></h2></th></tr>
                <?php if($_REQUEST['p_type']=='I'){?>
                    <tr align="center" style="color: blue"><th><?php echo strtoupper(trim($_REQUEST['pname']))?></th></tr>
                <?php }else if($_REQUEST['p_type']!='I'){?>
                    <tr align="center" style="color: blue"><th><?php echo strtoupper(trim($_REQUEST['pet_deptt']));?></th></tr>
                <?php }?>
                <tr align="center" style="color: black"><th>Versus</th></tr>
                <?php if($_REQUEST['r_type']=='I'){?>
                    <tr align="center" style="color: blue"><th><?php echo strtoupper(trim($_REQUEST['rname']))?></th></tr>
                <?php }else if($_REQUEST['r_type']!='I'){?>
                    <tr align="center" style="color: blue"><th><?php echo strtoupper(trim($_REQUEST['res_deptt']));?></th></tr>
                <?php }?>
                <tr><th></th></tr>
                </thead>
            </table>
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
    /*XXXXXXXXXXXXXXXXXXX end Update caveat XXXXXXXXXXXXXXXXXX*/


}
