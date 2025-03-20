<?php

namespace CodeIgniter\Validation;

namespace App\Controllers\Caveat;

use App\Controllers\BaseController;
use App\Models\Caveat\Model_similarity;
use App\Models\Entities\Model_Caveat;
use App\Models\Entities\Model_CaveatA;
use App\Models\Entities\Model_CaveatAdvocate;
use App\Models\Entities\Model_CaveatAdvocateA;
use App\Models\Entities\Model_CaveatDiaryMatching;
use App\Models\Entities\Model_main_a;
use App\Models\Model_main;
use CodeIgniter\Model;

class Similarity extends BaseController
{
    public $Model_caveat;
    public $Model_caveat_a;
    public $Model_similarity;
    public $Model_caveat_diary_matching;
    public $Model_CaveatAdvocate;
    public $Model_CaveatAdvocateA;
    public $Model_main;
    public $Model_main_a;
    function __construct()
    {
        $this->Model_caveat= new Model_Caveat();
        $this->Model_caveat_a= new Model_CaveatA();
        $this->Model_similarity= new Model_similarity();
        $this->Model_caveat_diary_matching= new Model_CaveatDiaryMatching();
        $this->Model_CaveatAdvocate= new Model_CaveatAdvocate();
        $this->Model_CaveatAdvocateA= new Model_CaveatAdvocateA();
        $this->Model_main= new Model_main();
        $this->Model_main_a= new Model_main_a();
    }

    public function index()
    {
        $data['param']=array();
        $data['mainCaveat']=array();
        $data['caveatSBCJ']=array();
        $data['caveatSBC']=array();
        $data['caveatSBJ']=array();
        $data['caveatSCC']=array();
        $data['arbitration']=array();
        $data['arbitration_ref_date']=array();
        $data['arbitration_date']=array();

        $caveat_details= session()->get('caveat_details');
        if (!empty($caveat_details)){
            $is_archival_table='_a';
            $caveat_number=substr($caveat_details['caveat_no'], 0, -4);$caveat_year=substr($caveat_details['caveat_no'],-4);
            $caveat_no=$caveat_number.$caveat_year;
            $mainCaveat=$this->Model_caveat->select("pet_name,res_name,TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') as diary_no_rec_date, 
  (CURRENT_DATE -diary_no_rec_date::date) AS no_of_days,casetype_id,caveat_no,ref_agency_state_id")->where(['caveat_no'=>$caveat_no])->get()->getRowArray();

            if (empty($mainCaveat)){
                $mainCaveat=$this->Model_caveat_a->select("pet_name,res_name,TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') as diary_no_rec_date, 
  (CURRENT_DATE -diary_no_rec_date::date) AS no_of_days,casetype_id,caveat_no,ref_agency_state_id")->where(['caveat_no'=>$caveat_no])->get()->getRowArray();
            }
            if (!empty($mainCaveat)){
                $data['mainCaveat']=$mainCaveat;
            }
        }
        $data['flag']='N';
        return view('Caveat/similarity_view',$data);
    }
    public function get_report(){
        $data['param']=array();
        if ($this->request->getMethod() === 'post'){
            if ($this->request->getMethod() === 'post' && $this->validate([
                    'caveat_number' => ['label' => 'Caveat Number', 'rules' => 'required|min_length[1]|max_length[8]'],
                    'caveat_year' => ['label' => 'Caveat Year', 'rules' => 'required|min_length[4]'],
                ])) {
                $caveat_number = $this->request->getPost('caveat_number');
                $caveat_year = $this->request->getPost('caveat_year');
                $caveat_no=$caveat_number.$caveat_year;
                $data['param']=['caveat_number'=>$caveat_number,'caveat_year'=>$caveat_year];

                $is_main_table=$this->Model_caveat->select("pet_name,res_name,TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') as diary_no_rec_date, 
  (CURRENT_DATE -diary_no_rec_date::date) AS no_of_days,casetype_id,caveat_no,ref_agency_state_id")->where(['caveat_no'=>$caveat_no])->get()->getResultArray();
                if ($is_main_table){
                    $get_main_table=$this->Model_caveat->select('*')->where(['caveat_no'=>$caveat_no])->get()->getRowArray();
                    $this->session->set(array('caveat_details'=> $get_main_table));
                    //return redirect()->to('Caveat/Modify');exit();
                }else{
                    $is_main_table=$this->Model_caveat_a->select("pet_name,res_name,TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') as diary_no_rec_date, 
  (CURRENT_DATE -diary_no_rec_date::date) AS no_of_days,casetype_id,ref_agency_state_id")->where(['caveat_no'=>$caveat_no])->get()->getResultArray();
                    if ($is_main_table) {
                        $get_main_table = $this->Model_caveat_a->select('*')->where(['caveat_no' => $caveat_no])->get()->getRowArray();
                        $this->session->set(array('caveat_details' => $get_main_table));
                    }else{
                        unset($_SESSION['caveat_details']);
                    }
                }
            }
        }
        $data['mainCaveat']=array();
        $data['caveatSBCJ']=array();
        $data['caveatSBC']=array();
        $data['caveatSBJ']=array();
        $data['caveatSCC']=array();
        $data['arbitration']=array();
        $data['arbitration_ref_date']=array();
        $data['arbitration_date']=array();

        $caveat_details= session()->get('caveat_details');
        if (!empty($caveat_details)){
            $is_archival_table='_a';
            $caveat_number=substr($caveat_details['caveat_no'], 0, -4);$caveat_year=substr($caveat_details['caveat_no'],-4);
            $caveat_no=$caveat_number.$caveat_year;
            $mainCaveat=$this->Model_caveat->select("pet_name,res_name,TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') as diary_no_rec_date, 
  (CURRENT_DATE -diary_no_rec_date::date) AS no_of_days,casetype_id,caveat_no,ref_agency_state_id")->where(['caveat_no'=>$caveat_no])->get()->getRowArray();

            if (empty($mainCaveat)){
                $mainCaveat=$this->Model_caveat_a->select("pet_name,res_name,TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') as diary_no_rec_date, 
  (CURRENT_DATE -diary_no_rec_date::date) AS no_of_days,casetype_id,caveat_no,ref_agency_state_id")->where(['caveat_no'=>$caveat_no])->get()->getRowArray();
            }
			
            if (!empty($mainCaveat)){
                $pet_name = trim($mainCaveat['pet_name']);
                $res_name = trim($mainCaveat['res_name']);
                $ref_agency_state_id = trim($mainCaveat['ref_agency_state_id']);
                $casetype_id=$mainCaveat['casetype_id'];
                $is_order_challenged=null;
                if($casetype_id !='7'&& $casetype_id !='8' && $casetype_id !='5' && $casetype_id !='6')
                {
                    $is_order_challenged='Y';
                }
				
                $data['mainCaveat']=$mainCaveat;
                $data['caveatSBCJ']= $this->Model_similarity->get_SBCJ($caveat_no,$is_order_challenged);
				
			   $data['caveatSBC']= $this->Model_similarity->get_SBC($caveat_no,$is_order_challenged);
                $data['caveatSBJ']= $this->Model_similarity->get_SBJ($caveat_no,$is_order_challenged);
                if($casetype_id=='7'|| $casetype_id=='8' || $casetype_id=='5' || $casetype_id=='6')
                {
                    $data['caveatSCC']= $this->Model_similarity->get_SCC($caveat_no);
                }
				
                if($casetype_id=='24') {
                    $data['arbitration'] = $this->Model_similarity->get_arbitration($caveat_no,$is_order_challenged);
                    $data['arbitration_ref_date'] = $this->Model_similarity->get_arbitration_ref_date($caveat_no,$is_order_challenged);
                    $data['arbitration_date'] = $this->Model_similarity->get_arbitration_date($caveat_no,$is_order_challenged);
                }
            }
        }
		
        $resul_view = view('Caveat/get_report_similarity_content',$data);
        echo $resul_view;exit();
    }
    public function get_diary_linked(){
        $diary_no=$_REQUEST['d_no'].$_REQUEST['d_yr'];
        $caveat_for_same_party=$_REQUEST['check_caveat'];
        $hd_rec_date=$_REQUEST['hd_rec_date'];
        $hd_caveat_rec_dt=$_REQUEST['hd_caveat_rec_dt'];
        $hd_link=$_REQUEST['hd_link'];

        if(isset($_REQUEST['flag'])){
            $hd_link=$_REQUEST['d_no'].$_REQUEST['d_yr'];
            $diary_no=$_REQUEST['hd_link'];
        }
        $caveat_exist=0;
        $caveatno='';

        $caveat_diary_matching= $this->Model_caveat_diary_matching->select('*')->where(['diary_no'=>$hd_link,'caveat_no'=>$diary_no,'display'=>'Y'])->get();
        $query = $this->db->getLastQuery();
        if ($caveat_diary_matching->getNumRows() <=0) {
            // $caveat_no=$caveat_diary_matching['caveat_no'];

            if($caveat_for_same_party=='Y'){
                $is_caveat_diary_matching= $this->Model_caveat_diary_matching->select('*')->where(['diary_no'=>$hd_link,'display'=>'Y'])->get()->getResultArray();
                if (!empty($is_caveat_diary_matching)){
                    foreach ($is_caveat_diary_matching as $row){
                        if (!empty($row['caveat_no'])){
                            $is_check_caveat_same_party=$this->Model_similarity->check_caveat_same_party($row['caveat_no'],$diary_no);
                            if ($is_check_caveat_same_party){
                                $is_check_caveat_same_party=$this->Model_similarity->check_caveat_same_party($row['caveat_no'],$diary_no,'_a');
                            }
                            if (!empty($is_check_caveat_same_party)){
                                foreach ($is_check_caveat_same_party as $same_party){
                                    $caveator=$same_party['pet_name'];
                                    $caveatno = $caveatno . " party namely '$caveator' in Caveat No. ".substr($row['caveat_no'], 0, strlen($row['caveat_no']) - 4) . '/' . substr($row['caveat_no'], -4) . ' and ';
                                    echo "Caveat already linked with ".rtrim($caveatno,' and ');
                                    exit();
                                }
                            }
                        }
                    }

                }

            }

            $c_d='';
            if(strtotime($hd_rec_date)>=strtotime($hd_caveat_rec_dt)){
                $c_d='D';
            } else{
                $c_d='C';
            }
            $caveat_diary_matching_data = [
                'caveat_no'=>$diary_no,
                'diary_no'=>$hd_link,
                'link_dt'=>date("Y-m-d H:i:s"),
                'usercode'=>session()->get('login')['usercode'],
                'caveat_diary'=>$c_d,
                'ent_dt'=>date("Y-m-d H:i:s"),
                'matching_reason'=>' ',
                'notice_path'=>' ',
                'print_user_id'=>0,

                'create_modify' => date("Y-m-d H:i:s"),
                'updated_by' => session()->get('login')['usercode'],
                'updated_by_ip' => getClientIP(),
            ];
            $is_insert=insert('caveat_diary_matching',$caveat_diary_matching_data);
            if ($is_insert){
                $sel_cav_adv=$this->Model_CaveatAdvocate->select('advocate_id,adv_type')->where(['caveat_no'=>$diary_no,'pet_res'=>'P','display'=>'Y'])->get()->getResultArray();
                if (empty($sel_cav_adv)){
                    $sel_cav_adv=$this->Model_CaveatAdvocateA->select('advocate_id,adv_type')->where(['caveat_no'=>$diary_no,'pet_res'=>'P','display'=>'Y'])->get()->getResultArray();
                }
                if (!empty($sel_cav_adv)){
                    $advocate_ids='';
                    foreach ($sel_cav_adv as $row){
                        if($advocate_ids=='') {
                            $advocate_ids = $row['advocate_id'];
                        }else{
                            $advocate_ids=$advocate_ids.','.$row['advocate_id'];
                        }

                        $chk_advocate = is_data_from_table('advocate', ['diary_no' => $hd_link, 'display' => 'Y','pet_res'=>'R','advocate_id'=>$row['advocate_id']]);
                        if (empty($chk_advocate)){
                            $chk_advocate = is_data_from_table('advocate_a', ['diary_no' => $hd_link, 'display' => 'Y','pet_res'=>'R','advocate_id'=>$row['advocate_id']]);
                        }
                        if (!empty($chk_advocate)){
                            foreach ($chk_advocate as $row1){
                                $advocate_data_update = [
                                    'adv'=>"[caveat]",
                                    'updated_on' => date("Y-m-d H:i:s"),
                                    'updated_by' => session()->get('login')['usercode'],
                                    'updated_by_ip' => getClientIP(),
                                ];
                                $is_efected_update_advocate=update('advocate',$advocate_data_update,['diary_no'=>$hd_link,'display'=>'Y','pet_res'=>'R','advocate_id'=>$row['advocate_id']]);
                            }
                        }
                        else
                        {
                            $advocate_data_insert = [
                                'diary_no'=>$hd_link,
                                'pet_res'=>'R',
                                'advocate_id'=>$row['advocate_id'],
                                'adv'=>"[caveat]",
                                'usercode' => session()->get('login')['usercode'],
                                'ent_dt'=>date("Y-m-d H:i:s"),
                                'display'=>'Y',
                                'ent_by_caveat_advocate'=>1,
                                'adv_type'=>'A',

                                'pet_res_no'=> 1,
                                'stateadv'=>'N',

                                'create_modify' => date("Y-m-d H:i:s"),
                                'updated_by' => session()->get('login')['usercode'],
                                'updated_by_ip' => getClientIP(),
                            ];
                            $is_efected_insert_advocate=insert('advocate',$advocate_data_insert);

                        }
                    }
                }
                echo "Diary No. and caveat linked successfully";
                //include("../sms_pool/mphc_sms.php");
                $frm='Caveat';
                $mobileno='';

            }
        }
        else
        {
            echo "Caveat already linked with Diary No."; exit();
        }
        exit();
    }
    public function get_diary_unlinked(){
        $hd_caveat_no=$_REQUEST['hd_caveat_no'];
        $hd_linked_no=$_REQUEST['hd_linked_no'];
        $sp_cav_diary_lnl_dt=$_REQUEST['sp_cav_diary_lnl_dt'];
        if (!empty($sp_cav_diary_lnl_dt)){$sp_cav_diary_lnl_dt=date('Y-m-d',strtotime($sp_cav_diary_lnl_dt));}
        if(isset($_REQUEST['flag'])){
            $hd_caveat_no=$_REQUEST['hd_linked_no'];
            $hd_linked_no=$_REQUEST['hd_caveat_no'];
        }
        $caveat_diary_matching= $this->Model_caveat_diary_matching->select('*')->where(['diary_no'=>$hd_caveat_no,'caveat_no'=>$hd_linked_no,'display'=>'Y'])->get();

        if ($caveat_diary_matching->getNumRows() > 0) {
            $this->db = \Config\Database::connect();
            $this->db->transStart();
            $caveat_diary_matching_data = [
                'display'=>'N',
                'usercode'=>session()->get('login')['usercode'],
                'ent_dt'=>date("Y-m-d H:i:s"),

                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by' => session()->get('login')['usercode'],
                'updated_by_ip' => getClientIP(),
            ];
            $is_insert=update('caveat_diary_matching',$caveat_diary_matching_data,['caveat_no'=>$hd_linked_no,'diary_no'=>$hd_caveat_no,'display'=>'Y',"to_char(link_dt,'YYYY-MM-DD')"=>$sp_cav_diary_lnl_dt]);
            //$is_insert=update('caveat_diary_matching',$caveat_diary_matching_data,['caveat_no'=>$hd_linked_no,'diary_no'=>$hd_caveat_no,'display'=>'Y']);
            $cavno=substr($hd_linked_no,0,-4).'/'.  substr($hd_linked_no,-4);
            $dno=substr($hd_caveat_no,0,-4).'/'.  substr($hd_caveat_no,-4);

            $sel_cav_adv=$this->Model_CaveatAdvocate->select('advocate_id,adv_type')->where(['caveat_no'=>$hd_linked_no,'pet_res'=>'P','display'=>'Y'])->get()->getResultArray();
            if (empty($sel_cav_adv)){
                $sel_cav_adv=$this->Model_CaveatAdvocateA->select('advocate_id,adv_type')->where(['caveat_no'=>$hd_linked_no,'pet_res'=>'P','display'=>'Y'])->get()->getResultArray();
            }
            if (!empty($sel_cav_adv)){

                $advocate_ids='';
                foreach ($sel_cav_adv as $row){
                    if($advocate_ids=='') {
                        $advocate_ids = $row['advocate_id'];
                    }else{
                        $advocate_ids=$advocate_ids.','.$row['advocate_id'];
                    }

                    $chk_advocate = is_data_from_table('advocate', ['diary_no' => $hd_caveat_no, 'display' => 'Y','pet_res'=>'R','advocate_id'=>$row['advocate_id']],'advocate_id,ent_by_caveat_advocate');
                    if (empty($chk_advocate)){
                        $chk_advocate = is_data_from_table('advocate_a', ['diary_no' => $hd_caveat_no, 'display' => 'Y','pet_res'=>'R','advocate_id'=>$row['advocate_id']],'advocate_id,ent_by_caveat_advocate');
                    }
                    if (!empty($chk_advocate)){
                        foreach ($chk_advocate as $row1){
                            if($row1['ent_by_caveat_advocate']==0) {
                                $advocate_data_update = [
                                    'adv' =>'',
                                    'updated_on' =>date("Y-m-d H:i:s"),
                                    'updated_by' =>session()->get('login')['usercode'],
                                    'updated_by_ip'=>getClientIP(),
                                ];
                                $is_efected_update_advocate=update('advocate',$advocate_data_update,['diary_no'=>$hd_caveat_no,'display'=>'Y','pet_res'=>'R','advocate_id'=>$row['advocate_id'],'ent_by_caveat_advocate'=>0]);
                            }else if($row1['ent_by_caveat_advocate']==1)
                            {
                                $advocate_data_update = [
                                    'display' =>'N',
                                    'updated_on' => date("Y-m-d H:i:s"),
                                    'updated_by' => session()->get('login')['usercode'],
                                    'updated_by_ip' => getClientIP(),
                                ];
                                $is_efected_update_advocate=update('advocate',$advocate_data_update,['diary_no'=>$hd_caveat_no,'display'=>'Y','pet_res'=>'R','advocate_id'=>$row['advocate_id'],'ent_by_caveat_advocate'=>1]);
                            }

                        }
                    }
                }

            }
            $this->db->transComplete();
            echo "Diary No. and caveat Unlinked successfully"; exit();
        }else{
            echo "Caveat already Unlinked with Diary No.";  exit();
        }
    }

    /*Start Similarities->Caveat->View*/
    public function view()
    {
        $data['param']=array();
        $data['mainCaveat']=array();
        $data['caveatSBCJ']=array();
        $data['caveatSBC']=array();
        $data['caveatSBJ']=array();
        $data['caveatSCC']=array();
        $data['arbitration']=array();
        $data['arbitration_ref_date']=array();
        $data['arbitration_date']=array();
        unset($_SESSION['caveat_details']);
        $data['param']=['caveat_number'=>'','caveat_year'=>''];
        $caveat_details= session()->get('caveat_details');
        if (!empty($caveat_details)){
            $is_archival_table='_a';
            $caveat_number=substr($caveat_details['caveat_no'], 0, -4);$caveat_year=substr($caveat_details['caveat_no'],-4);
            $caveat_no=$caveat_number.$caveat_year;
            $mainCaveat=$this->Model_main->select("pet_name,res_name,TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') as diary_no_rec_date, 
  (CURRENT_DATE -diary_no_rec_date::date) AS no_of_days,casetype_id,diary_no as caveat_no,ref_agency_state_id,diary_no")->where(['diary_no'=>$caveat_no])->get()->getRowArray();

            if (empty($mainCaveat)){
                $mainCaveat=$this->Model_main->select("pet_name,res_name,TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') as diary_no_rec_date, 
  (CURRENT_DATE -diary_no_rec_date::date) AS no_of_days,casetype_id,diary_no as caveat_no,ref_agency_state_id,diary_no")->where(['diary_no'=>$caveat_no])->get()->getRowArray();
            }
            if (!empty($mainCaveat)){
                $data['mainCaveat']=$mainCaveat;
            }
        }
        $data['flag']='D';
        return view('Caveat/similarity_view',$data);
    }
    public function get_report_by_diary(){
        unset($_SESSION['caveat_details']);
        $data['param']=array();
        if ($this->request->getMethod() === 'post'){
            if ($this->request->getMethod() === 'post' && $this->validate([
                    'caveat_number' => ['label' => 'Caveat Number', 'rules' => 'required|min_length[1]|max_length[8]'],
                    'caveat_year' => ['label' => 'Caveat Year', 'rules' => 'required|min_length[4]'],
                ])) {
                $caveat_number = $this->request->getPost('caveat_number');
                $caveat_year = $this->request->getPost('caveat_year');
                $caveat_no=$caveat_number.$caveat_year;
                if (!isset($_REQUEST['flag'])){$flag='C';}else{ $flag = htmlentities(sanitize($_REQUEST['flag'])); }

                $data['param']=['caveat_number'=>$caveat_number,'caveat_year'=>$caveat_year];
                if (!empty($flag) && $flag=='D'){

                    $is_main_table=$this->Model_main->select("pet_name,res_name,TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') as diary_no_rec_date, 
  (CURRENT_DATE -diary_no_rec_date::date) AS no_of_days,casetype_id,diary_no as caveat_no,ref_agency_state_id,diary_no")->where(['diary_no'=>$caveat_no])->get()->getResultArray();
                    if ($is_main_table){
                        $get_main_table=$this->Model_main->select('main.*,diary_no as caveat_no')->where(['diary_no'=>$caveat_no])->get()->getRowArray();
                        $this->session->set(array('caveat_details'=> $get_main_table));
                        //return redirect()->to('Caveat/Modify');exit();
                    }else{
                        $is_main_table=$this->Model_main_a->select("pet_name,res_name,TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') as diary_no_rec_date, 
  (CURRENT_DATE -diary_no_rec_date::date) AS no_of_days,casetype_id,ref_agency_state_id,diary_no as caveat_no,diary_no")->where(['diary_no'=>$caveat_no])->get()->getResultArray();
                        if ($is_main_table) {
                            $get_main_table = $this->Model_main_a->select('main_a.*,diary_no as caveat_no')->where(['diary_no' => $caveat_no])->get()->getRowArray();
                            $this->session->set(array('caveat_details' => $get_main_table));
                        }else{
                            unset($_SESSION['caveat_details']);
                        }
                    }

                }

            }
        
        $data['mainCaveat']=array();
        $data['caveatSBCJ']=array();
        $data['caveatSBC']=array();
        $data['caveatSBJ']=array();
        $data['caveatSCC']=array();
        $data['arbitration']=array();
        $data['arbitration_ref_date']=array();
        $data['arbitration_date']=array();

        $caveat_details= session()->get('caveat_details');
        if (!empty($caveat_details)){
            $is_archival_table='_a';
            $caveat_number=substr($caveat_details['diary_no'], 0, -4);$caveat_year=substr($caveat_details['diary_no'],-4);
            $caveat_no=$caveat_number.$caveat_year;
            $mainCaveat=$this->Model_main->select("pet_name,res_name,TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') as diary_no_rec_date, 
  (CURRENT_DATE -diary_no_rec_date::date) AS no_of_days,casetype_id,diary_no as caveat_no,ref_agency_state_id,diary_no")->where(['diary_no'=>$caveat_no])->get()->getRowArray();

            if (empty($mainCaveat)){
                $mainCaveat=$this->Model_main_a->select("pet_name,res_name,TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') as diary_no_rec_date, 
  (CURRENT_DATE -diary_no_rec_date::date) AS no_of_days,casetype_id,diary_no as caveat_no,ref_agency_state_id,diary_no")->where(['diary_no'=>$caveat_no])->get()->getRowArray();
            }
            if (!empty($mainCaveat)){
                $pet_name = trim($mainCaveat['pet_name']);
                $res_name = trim($mainCaveat['res_name']);
                $ref_agency_state_id = trim($mainCaveat['ref_agency_state_id']);
                $casetype_id=$mainCaveat['casetype_id'];
                $is_order_challenged=null;
                if($casetype_id !='7'&& $casetype_id !='8' && $casetype_id !='5' && $casetype_id !='6')
                {
                    $is_order_challenged='Y';
                }

                $data['mainCaveat']=$mainCaveat;
                $data['caveatSBCJ']= $this->Model_similarity->get_SBCJ($caveat_no,$is_order_challenged,$flag);
                $data['caveatSBC']= $this->Model_similarity->get_SBC($caveat_no,$is_order_challenged,$flag);
                
                $data['caveatSBJ']= $this->Model_similarity->get_SBJ($caveat_no,$is_order_challenged,$flag);
                if($casetype_id=='7'|| $casetype_id=='8' || $casetype_id=='5' || $casetype_id=='6')
                {
                    $data['caveatSCC']= $this->Model_similarity->get_SCC($caveat_no,$flag);
                }
                if($casetype_id=='24') {
                    $data['arbitration'] = $this->Model_similarity->get_arbitration($caveat_no,$is_order_challenged,$flag);
                    $data['arbitration_ref_date'] = $this->Model_similarity->get_arbitration_ref_date($caveat_no,$is_order_challenged,$flag);
                    $data['arbitration_date'] = $this->Model_similarity->get_arbitration_date($caveat_no,$is_order_challenged,$flag);
                }
            }
        }
        $data['flag']=$flag;
        //echo '<pre>';print_r($data);exit();
        $resul_view = view('Caveat/get_report_similarity_content_caveat_view',$data);
        //$resul_view = view('Caveat/get_report_similarity_content_view',$data);
        echo $resul_view;exit();
        }
    }
    /*end Similarities->Caveat->View*/
}