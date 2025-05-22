<?php

namespace App\Controllers\Judicial;
use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;
use App\Models\Judicial\DefectsModel;
use App\Libraries\webservices\Efiling_webservices;
use App\Libraries\webservices\Highcourt_webservices;

class Defects extends BaseController
{
    public $Dropdown_list_model;
    public $efiling_webservices;
    public $highcourt_webservices;
    public $DefectsModel;

    function __construct(){   
        $this->Dropdown_list_model= new Dropdown_list_model();
        $this->DefectsModel       = new DefectsModel();
    }

    public function index() {
        $data =[];        
        return view('Judicial/defects/da_defect', $data);
    }

    public function da_defect_getData()
    {
        $request = \Config\Services::request();
        $dairy_no = $request->getGet('d_no').$request->getGet('d_yr');
        $ucode = session()->get('login')['usercode'];
        $data['ucode']= $ucode;
        $data['da_defect_arr'] = $this->DefectsModel->get_da_defect($dairy_no);     
        
        $data['result_casetype']='';
        $data['check_section_rs'] = [];$data['$q_w'] = [];
        $data['fil_trap'] = [];$data['obj_save_result'] = [];
        $data['rw'] = [];$data['sql_obj'] = [];
        $data['if_chamber_listed'] = 0;$data['soft_copy_user'] = 0;
        $data['allow_entry_in_registered_matter'] = 0;
        $data['sql_res'] = 0;
        if(!empty($data['da_defect_arr']) && count($data['da_defect_arr']) > 0){            
            $data['result_casetype']= $data['da_defect_arr']['casetype_id'];
            $data['check_section_rs'] = $this->DefectsModel->check_section_get($ucode);
            $data['get_da'] = $this->DefectsModel->get_da_get($dairy_no); 
            $data['if_chamber_listed'] = $this->DefectsModel->chamber_listed_get($dairy_no);            
            $data['soft_copy_user'] = $this->DefectsModel->softcopy_user_rs($ucode);            
            $data['check_if_reg'] = $this->DefectsModel->check_if_reg_get($dairy_no); 
            $data['sql_res'] = $this->DefectsModel->sql_jk($dairy_no); 
            if ($ucode == 1 || $ucode == 1486 || $data['soft_copy_user'] == 1 || $data['if_chamber_listed'] == 1) {
                $data['sql_res'] =1;
            }
            
            if($data['sql_res'] == 1){                
                $data['q_w'] = $this->DefectsModel->get_q_w($dairy_no);
                //echo count($data['q_w']);die;
                $data['fil_trap'] = $this->DefectsModel->get_fil_trap($dairy_no);
                $data['obj_save_result'] = $this->DefectsModel->get_obj_save($dairy_no); 
                $data['rw'] = $this->DefectsModel->get_rw($dairy_no); 
                if(empty($data['q_w']) && count($data['q_w']) <= 0)
                {
                    $data['sql_obj'] = $this->DefectsModel->get_sql_obj();                     
                }

            }            
            
        }
        return view('Judicial/defects/da_defect_getDataview', $data);
    }
    
    public function get_upd_obj_u()
    {
        $request = \Config\Services::request();
        $data = [];
        $strVal = $request->getGet('strVal');
        $se = $request->getGet('se');
        $allow_entry_in_registered_matter = $request->getGet('allow');
        $data['se'] = $se;
        $data['sql_obj'] = $this->DefectsModel->get_sql_obj_search($strVal,$se,$allow_entry_in_registered_matter);        
        return view('Judicial/defects/get_upd_obj_u', $data); 

    }

    public function save_data()
    {
        
        $request = \Config\Services::request();
        $dairy_no = $request->getGet('d_no').$request->getGet('d_yr');
        $ucode = session()->get('login')['usercode'];
        $txtRem_mul=explode(',',$request->getGet('txtRem_mul'));
        $hd_id = $this->request->getVar('hd_id');
        $txtRem = $this->request->getVar('txtRem');
        $dairy_no = $dairy_no ?? ''; // ensure $dairy_no is defined earlier
        $sessionUser = $ucode ?? ''; // ensure $sessionUser is defined earlier
        $db = \Config\Database::connect();
        $builder = $db->table('obj_save');
        foreach ($txtRem_mul as $mul_ent)
        {
                // Check for existing record
                $count = $builder->where([
                        'diary_no' => $dairy_no,
                        'org_id'   => $hd_id,
                        'remark'   => $txtRem,
                        'display'  => 'Y'
                    ])
                    ->countAllResults();

                if ($count <= 0) {
                    // Insert new record
                    $builder->insert([
                        'org_id'   => $hd_id,
                        'save_dt'  => date('Y-m-d H:i:s'),
                        'usercode' => $sessionUser,
                        'remark'   => $txtRem,
                        'mul_ent'  => $mul_ent,
                        'diary_no' => $dairy_no
                    ]);
                }
        }


    }

    public function send_email(){

        helper(['url', 'form']);
        $request = service('request');
        $db = \Config\Database::connect();
        $session = session();

        $d_no = $request->getVar('d_no');
        $d_yr = $request->getVar('d_yr');
        $diaryno = $d_no . $d_yr;

        // Fetch advocate details
        $builder = $db->table('master.bar b');
        $builder->select("email, mobile, CONCAT(title, ' ', name) as name");
        $builder->join('main m', 'b.bar_id = m.pet_adv_id');
        $builder->where('m.diary_no', $diaryno);
        $result = $builder->get()->getRow();        
        if (!$result || empty($result->email)) {
            echo "<center>No email id found. Please first add e-mail id of the concerned advocate in ICMIS</center>";
            return;
        }

        $email = $result->email;
        $mobile = $result->mobile;
        $aor_name = $result->name;

        date_default_timezone_set('Asia/Kolkata');

        $subject = "List of Defects in Diary no. $d_no/$d_yr filed by you:-";
        $from_name = 'Supreme Court';

        // Fetch objection details
        $q_w = $db->query("
            SELECT a.org_id, objdesc AS obj_name, rm_dt, remark, mul_ent, save_dt 
            FROM obj_save a 
            JOIN master.objection b ON a.org_id = b.objcode 
            WHERE a.diary_no = '$diaryno' AND a.display = 'Y' 
            ORDER BY id
        ");

        $rows = $q_w->getResult();        
        if (count($rows) > 0) {
            $output = '<fieldset id="fiOD"><legend><b>Default Details</b></legend>
            <span style="font-size: small; text-transform: uppercase">
            <table class="table_tr_th_w_clr c_vertical_align" cellpadding="5" cellspacing="5" width="100%">
            <tr><th>S.No.</th><th>Default</th><th>Remarks</th><th>Notification Date</th></tr>';
            $sno = 1;
            foreach ($rows as $row1) {
                $output .= "<tr><td>$sno</td><td><span>{$row1->obj_name}</span></td>
                            <td><span>{$row1->remark}</span></td>
                            <td>{$row1->save_dt}</td></tr>";
                $sno++;
            }

            $output .= '</table></span></fieldset>';
        } else {
            $output = '';
        }

        $htmlContent = "<p>Sir/Madam,</p><p>Please remove the following defects notified in the petition filed by you. <br> Diary no - $d_no/$d_yr </p>";
        $htmlContent2 = '<br><p>Regards,</p><p>Section I-B ,</p><p>Supreme Court of India.</p>';
        $message = $htmlContent . $output . $htmlContent2;

        //echo 'Email has been sent successfully.';
        if (send_mail_JIO($email, $subject, $message)) {
            echo 'Email has been sent successfully.';
            $db->table('defects_notified_mails')->insert([
                'to_sender'   => $email,
                'subject'     => $subject,
                'display'     => 'Y',
                'usercode'    => $session->get('dcmis_user_idd'),
                'created_on'  => date('Y-m-d H:i:s')
            ]);
        } else {
            echo 'Email sending failed.';
        }

    }
    function obj_save_get(){
        $request = \Config\Services::request();
        $dairy_no = $request->getPost('diary_no');
        $data['checkObjSaveEntries'] = $this->DefectsModel->get_checkObjSaveEntries($dairy_no);   
        echo $data['checkObjSaveEntries'];die;
    }
    function save_sms_det()
    {   
        $request = \Config\Services::request();
        $dairy_no = $request->getPost('d_no').$request->getPost('d_yr');     
        $doc_id=$request->getPost('doc_id');
        $this->send_sms_process($request->getPost(),$doc_id);
        $this->fill_trap_save_process($dairy_no,$doc_id);
    }
    private function send_sms_process($_request_get,$doc_id)
    {                
            if($_request_get['sms_status'] != 'PWDRESET')
            {
                $dairy_no=$_request_get['d_no'].$_request_get['d_yr'];
                $doc_id;
                $frm='';
                $template_id='';
                $wh_mobileno='';
                $templateCode='';
                $listing_date='';

                    if($_request_get['sms_status']=='D' || $_request_get['sms_status']=='refiling' || $_request_get['sms_status']=='DIA')
                    {
                                if($_request_get['sms_status']=='D')
                                    $frm='Defects';
                                else if($_request_get['sms_status']=='refiling')
                                    $frm='Refiling';
                                    else if($_request_get['sms_status']=='DIA')
                                    $frm='Defects in IA';
                                if($_request_get['sms_status']=='D')
                                {
                                    //template modified for defect notification
                                    //$template_id='1107165872917800681';
                                    $template_id='1107172767309969953';
                                    $sql_obj = $this->DefectsModel->get_obj_save_sms($dairy_no,$display='',$rm_dt='');                            
                                }
                                else if( $_request_get['sms_status']=='refiling')
                                {
                                    $template_id='1107161234619089003';
                                    $sql_obj = $this->DefectsModel->get_obj_save_sms($dairy_no,$display='Y',$rm_dt='0000-00-00 00:00:00');                            
                                }
                                if($_request_get['sms_status']=='DIA')
                                {
                                    //template modified for defect notification
                                    //$template_id='1107165872917800681';
                                    $template_id='1107173286097545052';   //template id to change for defects in IA
                                    $sql_obj = $this->DefectsModel->get_obj_save_ia_sms($dairy_no,$doc_id,$display='Y',);
                                    
                                }
                                $res_sql_obj=  $sql_obj;
                                if($res_sql_obj<=0)
                                {
                                    exit();
                                }
                                else{
                                    $res_sql_obj=  1;
                                }
                    }
                    else if($_request_get['sms_status']=='R')
                    {
                        $frm='Registration';
                        $template_id='1107165881515458494';
                        $res_sql_obj=  1;
                    }
                    else if($_request_get['sms_status']=='DN')
                    {
                        $frm='Diary';
                        //$template_id='1107161234603870863';
                        $template_id='1107165900206642770';
                        $res_sql_obj=  1;
                    }
                    else if($_request_get['sms_status']=='PWDRESET')
                    {
                        //$template_id='';
                        $empid= $_request_get['empid'];
                        $password=$_request_get['pwd'];
                        $mobileno=$_request_get['mob'];
                        $template_id='1107162764348028579';
                        //$res_sql_obj=  -1;
                        $res_sql_obj=  2;
                        //        $testmsg="ICMIS Password has been reset. New Password for Emp ID ".$empid." in ICMIS is ".$password;
                        $testmsg="ICMIS Password has been reset. New Password for Emp ID  ".$empid." in ICMIS is ".$password." -  Supreme Court of India";
                        $frm = "ResetPassword";
                    }
                    else if($_request_get['sms_status']=='NEXTDAYLISTED'){
                            $mobileno=$_request_get['mob'];
                            $testmsg=$_request_get['msg'];
                            //$template_id='1107165873011597277';
                            $template_id='1107165950597744475';
                            //$res_sql_obj=  -1;
                            $res_sql_obj=  3;
                            $frm = "LOOSEDOC";
                    }
                    else if($_request_get['sms_status']=='scrutiny'){
                        $mobileno=$_request_get['mob'];
                        $testmsg=$_request_get['msg'];
                        $template_id='1107165872958238165';
                        //$res_sql_obj=  -1;
                        $res_sql_obj=  2;
                        $frm = "MARKED_FOR_SCRUTINY";
                    }
                    else if($_request_get['sms_status']=='CAVEAT_FILING')
                    {
                        $caveat_adv = $this->DefectsModel->get_advocate_mob($_request_get['caveat_no']);                                                 

                        if(!empty($caveat_adv) && count($caveat_adv)>0){
                            if ($caveat_adv['mobile'] != '' && strlen($caveat_adv['mobile']) == '10') {
                                $mobileno = $caveat_adv['mobile'];
                                $wh_mobileno= "91".$caveat_adv['mobile'];
                            }
                        }

                        $caveat = $this->DefectsModel->get_caveat_info($_request_get['caveat_no']);

                        
                        
                        if(!empty($caveat) && count($caveat)>0){ 
                            $caveat = mysql_fetch_array($caveat_rs);
                        }
                        $testmsg=$_request_get['msg'];
                        $template_id='1107166235834498119';
                        //$res_sql_obj=  -1;

                        $cavyear=substr($_request_get['caveat_no'],-4);
                        $cavnum=substr($_request_get['caveat_no'],0,-4);
                        $res_sql_obj=  2;
                        $frm = "CAVEAT_FILING";
                        $sms_params=array($caveat['pet_name'] . " vs " . $caveat['res_name'] ," registered with Caveat Number ".$cavnum.'/'.$cavyear);
                        $purpose='Fresh Caveat Generation';
                        $module='Caveat';
                        $templateCode="icmis::case::caveat::status";

                    }
                    else if($_request_get['sms_status']=='VERIFY')
                    {
                        $res_sql_obj=  1;
                        $template_id='1107165881523805462';
                        $listing_date=date('d-m-Y',strtotime($_request_get['next_dt']));
                        $testmsg = "Your Case having Diary No.".substr($diary_no,0,-4).'/'.substr($diary_no,-4)." likely to be listed on $listing_date ".". - Supreme Court of India";
                        $frm = "Verification";

                    }
                    
                    if($res_sql_obj>0)
                    {
                            if($res_sql_obj==1) {
                                $mobileno = '';
                                $diary_no = $_request_get['d_no'] . $_request_get['d_yr'];
                                $r_get_pet_res = $this->DefectsModel->get_pet_res($diary_no);  
                                if ($_request_get['sms_status'] == 'D') 
                                {
                                    // $diary_no= $_request_get[d_no].$_request_get[d_yr];
                                    /* FUNCTION FOR encryption */

                                    //Commented on 03-10-2024
                                    //starts here
                                    /*
                                    $ciphering = "AES-128-CTR";
                                    $encryption_iv = '98765432123456789';
                                    $encryption_key = "SCIDEFECTS_06072022";
                                    $encryption = openssl_encrypt($diary_no, $ciphering,$encryption_key, 0, $encryption_iv);
                                    $long_url= "https://scetransport.nic.in/get_default.php?diaryno=".$encryption;
                                    */
                                    //ends here

                                    /* $short_url_api = 'http://10.25.78.60/supreme_court/anu/call_api.php?key=zajkk60ldkq&url='.$long_url;
                                    $response = file_get_contents($short_url_api);
                                    $response_array = json_decode($response, true);
                                    echo $response_array['status'];
                                    if($response_array['status']=='success'){
                                                    $append_msg = '. '.$response_array['slug'];
                                                // echo $append_msg;
                                    }  */

                                    //Commented on 03-10-2024
                                    //starts here
                                    /* $shorturl_key = "zajkk60ldkq"; //kjk540kjljkj9 for 67 server and zajkk60ldkq for 60 server
                                        $content_push = array("key" => $shorturl_key, "url" => $long_url);
                                        $content = json_encode($content_push);
                                        $base64_encode = base64_encode($content);
                                        $result = create_shorten($base64_encode);
                                        $base64_decode = base64_decode($result);
                                        $json = json_decode($base64_decode, true);
                                        //var_dump($json);
                                        if($json['status'] == 'success' OR $json['status'] == 'Short URL Already Available.') {
                                            $short_url = $json['slug'];
                                        }*/

                                        //ends here

                                        //$testmsg = "The case filed by you with Diary No. " . $_request_get[d_no] . '-' . $_request_get[d_yr] . " has been notified with " . $res_sql_obj . " objections. Please remove within statutory period.Link to view defects is ".$short_url." - Supreme Court of India";
                                    $testmsg = "The case filed by you with Diary No. " .$_request_get['d_no'] . '-' . $_request_get['d_yr'] . " has been notified with objections. Please remove within statutory period. For more details, visit website https://www.sci.gov.in  - Supreme Court of India";
                                
                                    $sms_params=array($r_get_pet_res['pet_name'] . " vs " . $r_get_pet_res['res_name'] ," defective with objections"."(Diary no. ".$_request_get['d_no'].'/'.$_request_get['d_yr'].")");
                                    $purpose='Defects Notification';
                                    $module='Filing';
                                    $templateCode="icmis::case::diarization_and_registration";
                                    //    echo "Select contact from  party where diary_no='$diary_no' and  pet_res='P'";
                                }
                                if ($_request_get['sms_status'] == 'DIA') {
                                    $ia_num = $this->DefectsModel->get_sql_ia($doc_id);                             
                                    $testmsg = "The IA/document filed by you with IA/Document No. " .$ia_num['docnum'] . '-' . $ia_num['docyear'] . "(Diary No.-".$_request_get['d_no'].'/'.$_request_get[d_yr].")  has been notified with objections. Please remove within statutory period. For more details, visit website https://www.sci.gov.in  - Supreme Court of India";
                                    $sms_params=array($ia_num['docnum'] . '-' . $ia_num['docyear']."(Diary No.-".$_request_get['d_no'].'/'.$_request_get['d_yr'].")");  //to change for defects in IA
                                    $purpose='Defects Notification in IA';
                                    $module='Judicial';
                                    $templateCode="icmis::ia::defect::status";  //template code to change for defects in IA
                                }
                                else if ($_request_get['sms_status'] == 'R') 
                                {

                                    // modified as below on 28.02.2019 $testmsg="The case filed by you with Diary No. ".$_request_get[d_no].'-'.$_request_get[d_yr].' '.$r_get_pet_res[pet_name].'Vs'.$r_get_pet_res[res_name]. " is succesfully registered with registration no. ".$res_skey.'-'.$f_no."/".$year. " and prepared for listing as per rules.";
                                    // modified as below on 1.08.2022 $testmsg = "The case filed by you with Diary No. " . $_request_get[d_no] . '-' . $_request_get[d_yr] . ' ' . $r_get_pet_res[pet_name] . 'Vs' . $r_get_pet_res[res_name] . " is succesfully registered with registration no. " . $res_skey . '-' . $f_no . "/" . $year.". - Supreme Court of India";
                                    $pet=$r_get_pet_res['pet_name'];
                                    $res=$r_get_pet_res['res_name'];
                                    if(strlen($r_get_pet_res['pet_name'])>30){
                                        $pet=str_replace(substr($r_get_pet_res['pet_name'], 27, strlen($r_get_pet_res['pet_name'])),'...',$r_get_pet_res['pet_name']) ;
                                    }
                                    if(strlen($r_get_pet_res['res_name'])>30){
                                        $res=str_replace(substr($r_get_pet_res['res_name'], 27, strlen($r_get_pet_res['res_name'])),'...',$r_get_pet_res['res_name']) ;
                                    }
                                    $testmsg="The case filed by you with Diary No. " . $_request_get['d_no']."/".$_request_get['d_yr'] ." - ".$pet." VS ".$res." is successfully registered with registration no. ".$res_skey.'-'.$f_no."/".$year.". - Supreme Court of India";
                                    $sms_params=array(' with Diary no. '.$_request_get['d_no'] . '-' . $_request_get['d_yr'].' and Cause title- '.$r_get_pet_res['pet_name'] . " vs " . $r_get_pet_res[res_name] ," registered with Registration Number ".$res_skey.'-'.$f_no."/".$year);
                                    $purpose='Registration';
                                    $module='Filing';
                                    $templateCode="icmis::case::diarization_and_registration";

                                }
                                else if ($_request_get['sms_status'] == 'DN')
                                {

                                    //$testmsg="The case filed by you with Diary No. ".$_request_get[d_no].'-'.$_request_get[d_yr]. " is succesfully registered with registration no. ".$res_skey.'-'.$f_no."/".$year. " and prepared for listing as per rules.";
                                    date_default_timezone_set('Asia/Kolkata');
                                    $pet=$pet_cause_title;
                                    $res=$res_cause_title;
                                    if(strlen($pet_cause_title)>30){
                                        $pet=str_replace(substr($pet_cause_title, 27, strlen($pet_cause_title)),'...',$pet_cause_title) ;
                                    }
                                    if(strlen($res_cause_title)>30){
                                        $res=str_replace(substr($res_cause_title, 27, strlen($res_cause_title)),'...',$res_cause_title) ;
                                    }

                                    $testmsg = "Your case " . $pet . " vs " . $res . " is filed with Diary No. " . $_request_get['d_no'] . '-' . $_request_get['d_yr'] . " on " . date('d-m-Y H:i:s').'. - Supreme Court of India';

                                    //$testmsg = "Your case " . $pet_cause_title . " Vs " . $res_cause_title . " is filed with Diary No. " . $_request_get[d_no] . '-' . $_request_get[d_yr] . " on " . date('d-m-Y H:i:s');
                                    $sms_params=array($pet_cause_title . " vs " . $res_cause_title ," diarized with  Diary Number ".$_request_get['d_no']."/".$_request_get['d_yr']);
                                    $purpose='Fresh Diary Generation';
                                    $module='Filing';
                                    $templateCode="icmis::case::diarization_and_registration";
                                }
                                else if ($_request_get['sms_status'] == 'refiling') {
                                    $testmsg = "The case filed by you with Diary No. " . $_request_get['d_no'] . '-' . $_request_get['d_yr'] . " is still defective having " . $res_sql_obj . " objections. Please collect the same from Re-filing counter. - Supreme Court of India";
                                //    echo "Select contact from  party where diary_no='$diary_no' and  pet_res='P'";
                                }
                                else if($_request_get['sms_status']=='VERIFY'){
                                    $pet=$r_get_pet_res['pet_name'];
                                    $res=$r_get_pet_res['res_name'];
                                    $sms_params=array($pet. " vs " . $res ." with Diary No. " . $_request_get['d_no'] . '-' . $_request_get['d_yr'] ," likely to be listed on ".$listing_date);
                                    $purpose='Verification';
                                    $module='FILING';
                                    $templateCode="icmis::case::diarization_and_registration";
                                }
                                $sql = $this->DefectsModel->get_sqlparty($diary_no);
                                if(!empty($sql) && count($sql)>0){
                                    foreach($sql as $r_party){
                                        if ($r_party['contact'] != '' && strlen($r_party['contact']) == '10') {
                                            if ($mobileno == '') {
                                                $mobileno = $r_party['contact'];
                                                # $wh_mobileno="91".$r_party['contact'];
                                            }
                                            else {
                                                $mobileno = $mobileno . ',' . $r_party['contact'];
                                                #  $wh_mobileno=$mobileno . ',' ."91".$r_party['contact'];
                                            }
                                        }
                                    }
                                }

                                if($_request_get['sms_status']=='DIA'){                                
                                    $advocate_mob_new = $this->DefectsModel->get_advocate_mob_doc($doc_id);                                  
                                }
                                else{                                
                                    $advocate_mob_new = $this->DefectsModel->get_advocate_mob_adv($diary_no,$display='Y',$pet_res='P');                                
                                }
                                if(!empty($advocate_mob_new) && count($advocate_mob_new)>0){
                                    foreach($advocate_mob_new as $row){
                                        if ($row['mobile'] != '' && strlen($row['mobile']) == '10') {
                                            if ($mobileno == '') {
                                                $mobileno = $row['mobile'];
                                                $wh_mobileno="91".$row['mobile'];
                                            }
                                            else {
                                                $mobileno = $mobileno . ',' . $row['mobile'];
                                                $wh_mobileno=$wh_mobileno . ','."91".$row['mobile'];
                                            }
                                        }
                                    }
                                    $wh_mobileno=explode(',',$wh_mobileno);
                                }                           


                            }
                            
                            if(!empty($mo)){
                            $mo = $mobileno;                        
                            $ms = $testmsg;
                            $frm = $frm;
                            $wh_mobileno;
                            if($res_sql_obj==3){
                                    $k=sendSMS($mo,$ms,$template_id);
                                    echo "SMS Send Successfully.";
                                    //print_r($k);die;
                            }
                            else{
                                //  $k = mphc_sms($mo,$ms,$frm,$template_id);
                                    $k=sendSMS($mo,$ms,$template_id);
                                    echo "SMS Send Successfully.";
                                    //print_r($k);die;
                                }
                            }
                            if($wh_mobileno){                       
                                $created_by_user= array("name"=>session()->get('login')['name'],"id"=>session()->get('login')['empid'],"employeeCode"=>session()->get('login')['usercode'],"organizationName"=>'SCI'); 
                                $response= send_sms_whatsapp_through_uni_notify(1,$wh_mobileno,$templateCode, $sms_params,null, $purpose,$created_by_user,$module,'ICMIS',null,null, null);  
                            }   


                    }
                    else{
                        if($_request_get['sms_status']=='D')
                        {
                            ?>
                            <div style="text-align: center">Please enter atleast one defect before sendind SMS.</div>
                            <?php
                        }
                    }





            }
    }

    private function fill_trap_save_process($dairy_no,$doc_id){        
    
        $main_row = $this->DefectsModel->get_main_row($dairy_no);        
        if(!empty($main_row) && count($main_row)>0){
            $d_to_empid = $main_row['empid'];
        }
        $row = $this->DefectsModel->get_fil_trap_row($dairy_no);                
        if(!empty($row) && count($row)>0)
        {
            $uid = $row['uid']; 
            $remarks = $row['remarks'];
            $disp_dt = $row['disp_dt'];
            $rece_dt = $row['rece_dt'];
            $r_by_empid = $row['r_by_empid'];
            
            $main_row_nested = $this->DefectsModel->get_main_row_nested($dairy_no);   
            if(!empty($main_row_nested) && count($main_row_nested)>0)
            {
                $d_to_empid = $main_row_nested['empid']; 
                $chk_row = $this->DefectsModel->get_fil_trap_his_new($main_row_nested);
                if(!empty($chk_row) && count($chk_row)>0){
                    $data = [
                                'diary_no'      => $dairy_no,
                                'd_by_empid'    => $d_to_empid,
                                'd_to_empid'    => '9798',
                                'disp_dt'       => date('Y-m-d H:i:s'),
                                'remarks'       => 'SCR -> FDR',
                                'r_by_empid'    => '9798',
                                'other'    => 0,
                                'rece_dt'       => date('Y-m-d H:i:s'),
                                'comp_dt'       => date('Y-m-d H:i:s'),
                                'thisdt'        => date('Y-m-d H:i:s')
                            ];            
                            $builder = $this->db->table('public.fil_trap_his');                
                            if (!$builder->insert($data)) {
                                // If insert fails
                                echo 'Error inserting data: ' . print_r($db->error(), true);
                            }

                            $builder2 = $this->db->table('fil_trap');

                            $data_upd = [
                                        'd_by_empid'    => 9798,
                                        'd_to_empid'    => $d_to_empid,
                                        'disp_dt'       => $disp_dt,
                                        'remarks'       => 'FDR -> AOR',
                                        'r_by_empid'    => $d_to_empid,
                                        'rece_dt'       => $rece_dt,
                                        'comp_dt'       => null, // PostgreSQL doesn't allow '0000-00-00 ...', use NULL instead
                                        'disp_dt_seq'   => null,
                                        'other'         => '0'
                                    ];

                            $builder2->where('uid', $uid);
                            $builder2->update($data_upd);        
                    
                }


            }
            else {
                    echo "Error: No matching record found in the main table.";
                    exit();
                }
            
            
        }
        else{
            echo "else";die;
                $data = [
                        'diary_no'      => $dairy_no,
                        'd_by_empid'    => $d_to_empid,
                        'd_to_empid'    => '9798',
                        'disp_dt'       => date('Y-m-d H:i:s'),
                        'remarks'       => 'SCR -> FDR',
                        'r_by_empid'    => '9798',
                        'other'    => 0,
                        'rece_dt'       => date('Y-m-d H:i:s'),
                        'comp_dt'       => date('Y-m-d H:i:s'),
                        'thisdt'        => date('Y-m-d H:i:s')
                    ];            
            $builder = $this->db->table('public.fil_trap_his');                
            if (!$builder->insert($data)) {
                // If insert fails
                echo 'Error inserting data: ' . print_r($db->error(), true);
            }

            $data2 = [
                    'diary_no'      => $dairy_no,
                    'd_by_empid'    => '9798',
                    'd_to_empid'    => '29',
                    'disp_dt'       => date('Y-m-d H:i:s'),
                    'remarks'       => 'FDR -> AOR',
                    'r_by_empid'    => '29',
                    'rece_dt'       => null, // PostgreSQL allows nulls; use NULL instead of '0000-00-00 00:00:00.000000'
                    'comp_dt'       => null,
                    'disp_dt_seq'   => 0,
                    'other'         => 0,
                    'scr_lower'     => 0
                ];
            $builder2 = $this->db->table('public.fil_trap');                
            if (!$builder2->insert($data2)) {
                // If insert fails
                echo 'Error inserting data: ' . print_r($db->error(), true);
            }    


        }


    }
    

    public function remove_defects()
    {           
        $data =[];        
        return view('Judicial/defects/obj_cl', $data);
    }

    public function get_obj_cl_dup()
    {
        $request = \Config\Services::request();        
        $ucode = session()->get('login')['usercode'];
        $dairy_no = $request->getGet('d_no').$request->getGet('d_yr');
        $result_casetype = $this->DefectsModel->get_result_casetype($dairy_no);              
        if(!empty($result_casetype) && count($result_casetype)>0)
        {
            $result_casetype = $result_casetype['casetype_id'];
            $check_section_rs = $this->DefectsModel->get_check_section_rs($ucode);
            if(!empty($check_section_rs) && count($check_section_rs)>0) 
            {
                $casetype = array('9', '10', '19', '20', '25', '26', '39');
                if (!in_array($result_casetype, $casetype)) {
                    echo '<div style="text-align: center"><h3>Defects can be updated in RP/CUR.P/CONT.P./MA</h3></div>';
                    exit();
                }
                $da=get_da($dairy_no);
                if($da!=$ucode){
                    echo '<div style="text-align: center"><h3>Defects can be updated by concerned Dealing Assistant</h3></div>';
                    exit();
                }
            }        
        }
        else {
                 echo '<div style="text-align: center"><b>Diary No. Not Found</b></div>';
                 exit();                        
        }

        echo $this->get_refiling_report($request);
        
        echo $this->get_objection($request);

    }

    private function get_objection($request){
        $ucode = session()->get('login')['usercode'];
        $data =[];
        $diary_no = $request->getGet('d_no').$_REQUEST['d_yr'];
        $data['w_wo_dn']='';
        
        $data['diary_no'] = $diary_no;
        $data['diary_no_display'] = $request->getGet('d_no').'/'.$_REQUEST['d_yr'];
        $data['res'] = $this->DefectsModel->get_causetitle_qr($diary_no);
        $data['w_wo_dn'] = " and a.diary_no='$diary_no'";
        $data['def_notify'] = "SELECT MIN(save_dt::date) AS save_dt,
                                MIN(save_dt::date) AS df,MIN(rm_dt::date) AS rm_dt
                                FROM obj_save
                                WHERE diary_no = '$diary_no' AND display = 'Y'";
        $data['softcopy_user_qr']= "select distinct master.usercode from specific_role where display='Y' and flag='S' and usercode='$ucode'";                         
        $data['check_fil_trap'] = "select remarks, d_to_empid, usercode,name from fil_trap f join master.users u on f.d_to_empid=u.empid where diary_no='$diary_no' and remarks='FDR -> SCR'";
        $data['softcopy_def'] = "SELECT *  FROM obj_save WHERE diary_no = '$diary_no' 
                                    AND org_id = '10193' AND display = 'Y' AND rm_dt IS NULL";
        
        $c_date=date('Y-m-d');
        $refil_date =date('Y-m-d');
        $data['get_no_of_days_qry'] = "SELECT no_of_days  FROM defect_policy 
                                    WHERE master_module = '1' 
                                    AND (
                                            ('$c_date' BETWEEN from_date AND to_date)
                                            OR (from_date <= '$c_date' AND to_date IS NULL)
                                        )";
        $data['find_doc_qry'] = "select * from docdetails where diary_no='$diary_no' and doccode=8 and doccode1='226' and display='Y'";                                
        $data['res_wdn'] = $this->DefectsModel->get_res_wdn($data['w_wo_dn']);  
        
        
        return view('Judicial/defects/get_objection', $data);    

    }
    
    private function get_refiling_report($request)
    {        
        $data =[];
        $diary_no = $request->getGet('d_no').$_REQUEST['d_yr'];
        $data['diary_no'] = $diary_no;
        $data['diary_no_display'] = $request->getGet('d_no').'/'.$_REQUEST['d_yr'];

        $data['rs'] = $this->DefectsModel->get_rs_res($diary_no);  
        $data['get_no_of_days'] = 0;$data['nextdate']='';
        if(!empty($data['rs']) && count($data['rs'])>0){
            foreach($data['rs'] as $result)
            {
                $cause_title=$result['cause_title'];
                $diary_date=$result['diary_date'];
                $def_notify_date=$result['defect_date'];
                $df=$result['df'];
            }
            if($def_notify_date!=null) 
            {
                $c_date = date('Y-m-d');
                $data['res_no_of_days'] = $this->DefectsModel->get_no_of_days_qr($c_date);  
                $days_to_add = isset($data['res_no_of_days']) ? (int)$data['res_no_of_days'] : 0;
              
                $i=0;                
                //$def_rem_max_date=date(date("Y-m-d", strtotime($def_notify_date)) . " +".$i."days");
                //$def_rem_max_date = date('Y-m-d', strtotime($def_notify_date . ' + ' . @$data['res_no_of_days'] . ' days'));
                $date = new \DateTime($def_notify_date);
                $date->modify("+{$days_to_add} days");

                // Format final date (choose Y-m-d or Y-m-d H:i:s)
                $def_rem_max_date = $date->format('Y-m-d');
                $data['nextdate'] = $this->next_date($def_rem_max_date, 1);                
            }
        }

        $data['ia'] = $this->DefectsModel->get_ia($diary_no);
        $refiling=0;
        if(!empty($ia) && count($ia)>0){
            foreach($ia as $row){
                if($row['doccode1'] == 226){
                    $refiling = 1;                        
                }
            }
        }
        
        $data['causetitle'] = $this->get_causetitle($diary_no); 

        
        return view('Judicial/defects/get_refiling_report', $data);

    }

    function get_causetitle($diary_no){
        $cause_title='';
        $cause_title_arr = $this->DefectsModel->get_causetitle_qr($diary_no);                
        if(!empty($cause_title_arr)){
            $cause_title=$cause_title.$cause_title_arr['pet_name'];
            if($cause_title_arr['pno']==2){
                $cause_title=$cause_title."<font color='blue'> AND ANR </font>";
            }
            else if($cause_title_arr['pno']>2){
                $cause_title=$cause_title."<font color='blue'> AND ORS </font>";
            }
            $cause_title=$cause_title."<font color=blue> VS </font>".$cause_title_arr['res_name'];
            if($cause_title_arr['rno']==2){
                $cause_title=$cause_title."<font color='blue'> AND ANR </font>";
            }
            else if($cause_title_arr['rno']>2){
                $cause_title=$cause_title."<font color='blue'> AND ORS </font>";
            }
        }
        return $cause_title;
    }

    function is_holiday($date)
    {
        $holiday = $this->DefectsModel->get_holiday($date);
        if(!empty($holiday) && count($holiday)>0)        
            return 1;
        else
            return 0;        
    }
    function next_date($date,$day)
    {
        $nxt_dt = $date;
        $count=1;
        while($count<=$day)
        {
            $ch = $this->is_holiday($nxt_dt);
            
            
            if($ch==1)
            {
            $nxt_dt = date('Y-m-d',strtotime($nxt_dt.'+1day'));
                continue;
            }
            else
            {
                if($count==$day){
                    return $nxt_dt;
                }
                $count++;
            
                $nxt_dt = date('Y-m-d',strtotime($nxt_dt.'+1day'));
                echo "next date is ".$nxt_dt;
            }
        }
    }    

    public function incomplete_filtrap() 
    {   
        $data =[];             
        $data['ucode'] = session()->get('login')['usercode'];
        $data['empid'] = session()->get('login')['empid'];
        $data['emp_name_login'] =  session()->get('login')['name'];
        //echo "<pre>"; print_r(session()->get());die;       
        $data['select_rs'] = $this->DefectsModel->get_efiling_rs($data['empid']);         
        return view('Judicial/defects/incomplete_filtrap', $data);
    }
    
}