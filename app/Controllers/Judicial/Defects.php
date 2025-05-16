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

        echo 'Email has been sent successfully.';
        // if (send_mail_JIO($email, $subject, $message)) {
        //     echo 'Email has been sent successfully.';
        //     $db->table('defects_notified_mails')->insert([
        //         'to_sender'   => $email,
        //         'subject'     => $subject,
        //         'display'     => 'Y',
        //         'usercode'    => $session->get('dcmis_user_idd'),
        //         'created_on'  => date('Y-m-d H:i:s')
        //     ]);
        // } else {
        //     echo 'Email sending failed.';
        // }

    }

    function obj_save_get(){
        $request = \Config\Services::request();
        $dairy_no = $request->getPost('diary_no');
        $data['checkObjSaveEntries'] = $this->DefectsModel->get_checkObjSaveEntries($dairy_no);   
        echo $data['checkObjSaveEntries'];die;
    }

    function save_sms_det(){

    }


    
    
}