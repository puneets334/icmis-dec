<?php

namespace App\Controllers\MasterManagement;
use App\Controllers\BaseController;
use App\Models\Entities\Model_menu;
use App\Models\MasterManagement\AORPendingMatters;
use App\Models\Menu_model;
use CodeIgniter\Controller;
use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class Advocate extends BaseController
{
public $Model_menu;
public $Menu_model;
public $AORPendingMatters;
    function __construct()
    {
        ini_set('memory_limit','51200M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
        $this->Model_menu = new Model_menu();
        $this->Menu_model = new Menu_model();
        $this->AORPendingMatters = new AORPendingMatters();
        //error_reporting(0);
    }

    public function index()
    {
      
        $data['get_menus_rs']=$this->Model_menu->select("menu_nm,substr(menu_id,1,2),url as menu_id")->where(['substr(menu_id,3)'=>'0000000000','display'=>'Y','menu_id is not'=>null])->orderBy('priority')->get()->getResultArray();
        $data['action_permission_allotment']=$this->Menu_model->get_action_permission_allotment();
        $data['menu_list']=$this->Menu_model->get_menu_list();
        $data['role_master_list']=$this->Menu_model->get_role_master_with_role_menu_mapping_list();
        //echo '<pre>';print_r($data['role_master_list']);exit();
        $data['advocate'] = $this->AORPendingMatters->getPendingMatters();
        $data['casetype'] = $this->AORPendingMatters->getCaseType();
        // pr($datadddd);
        return view('MasterManagement/advocate/aor_pending_matters',$data);

    }


    public function CasesView()
    {
     
        $aor = $this->request->getPost('aor');
        $from_dt1 = $this->request->getPost('from_dt1');
        $from_dt2 = $this->request->getPost('from_dt2');
        $status = $this->request->getPost('status');
        $caseType = $this->request->getPost('caseType');
        $from_dt1 = $from_dt1 ? date('Y-m-d', strtotime($from_dt1)) : null;
        $from_dt2 = $from_dt2 ? date('Y-m-d', strtotime($from_dt2)) : null;
        $aor_name = $this->AORPendingMatters->getAorName($aor);
        $data['aorNameText'] = $aor_name ? $aor_name->title . ' ' . $aor_name->name : 'Unknown';
        $bar_id = $aor_name ? $aor_name->bar_id : null;
        if (!$bar_id) {
            return view('error_view', ['message' => 'No such AOR code exists']);
        }
        
        $data['Reportsget'] = $this->AORPendingMatters->getCases($bar_id, $from_dt1, $from_dt2, $status, $caseType);
        
        return view('MasterManagement/advocate/templateAORreport',$data);
        
    }




    public function AorspecimenSigatureUpload()
    {
    
        $data['get_menus_rs']=$this->Model_menu->select("menu_nm,substr(menu_id,1,2),url as menu_id")->where(['substr(menu_id,3)'=>'0000000000','display'=>'Y','menu_id is not'=>null])->orderBy('priority')->get()->getResultArray();
        $data['action_permission_allotment']=$this->Menu_model->get_action_permission_allotment();
        $data['menu_list']=$this->Menu_model->get_menu_list();
        $data['role_master_list']=$this->Menu_model->get_role_master_with_role_menu_mapping_list();
        $data['advocate'] = $this->AORPendingMatters->getPendingMatters();
        $data['casetype'] = $this->AORPendingMatters->getCaseType();
        return view('MasterManagement/advocate/aor_pecimen_signature_upload',$data);

    }



    public function UploadFilestore()
    {
        // pr($this->request->getPost());
        // die;
        $aor_code = $this->request->getPost('aor_code');
        $upd_file = $this->request->getPost('upd_file');
        if ($upd_file != '') {
            $pdf_name = $aor_code . '.pdf';
            $master_to_path = WRITEPATH . 'signature'; 
            if (file_exists($master_to_path . '/' . $pdf_name)) {
                return $this->response->setJSON(['status' => 1, 'message' => 'File already exists']);
            }
            $file = $this->request->getFile('file');
            if (!$file->isValid() || $file->getExtension() != 'pdf') {
                return $this->response->setJSON(['status' => 2, 'message' => 'Only PDF files are allowed']);
            }
            if ($file->move($master_to_path, $pdf_name)) {
                return $this->response->setJSON(['status' => 0, 'message' => 'File uploaded successfully']);
            } else {
                return $this->response->setJSON(['status' => 3, 'message' => 'Failed to upload file']);
            }
        }
        return $this->response->setJSON(['status' => 4, 'message' => 'No file uploaded']);
    }
        
    

    public function CheckFilesExist()
    {
        $pdf_name = $this->request->getGet('fname');
        $upload_path = WRITEPATH . 'signature/' . $pdf_name;
        
        if (file_exists($upload_path)) {
            return $this->response->setJSON(['exists' => true], 200);
        } else {
            return $this->response->setJSON(['exists' => false], 404);
        }
    }



    
    public function ReUploadFiles()
    {
      
        $response = ['status' => 'error', 'message' => 'An error occurred.'];
        if ($this->request->getFile('file') && $this->request->getPost('upd_file') != '') {
            $file = $this->request->getFile('file');
            $oldFile = $this->request->getPost('old_file');
            $directory = WRITEPATH . 'signature'; 
            $pdfNameOriginal = $oldFile . '.pdf';
            $pdfPathOriginal = $directory . DIRECTORY_SEPARATOR . $pdfNameOriginal;
            if (file_exists($pdfPathOriginal)) {
                $date = date('Y-m-d_H-i-s');
                $pdfNameNew = $oldFile . "_" . $date . ".pdf";
                $pdfPathNew = $directory . DIRECTORY_SEPARATOR . $pdfNameNew;
                if (!rename($pdfPathOriginal, $pdfPathNew)) {
                    $response['message'] = 'Failed to rename existing file.';
                }
            }
            $allowedTypes = ['pdf'];
            $fileType = $file->getClientExtension();
            if (!in_array($fileType, $allowedTypes)) {
                $response['message'] = 'Sorry, only PDF files are allowed for uploading.';
            } else {
                if ($file->move($directory, $pdfNameOriginal)) {
                    $response['status'] = 200;
                    $response['message'] = 'Specimen Signature Uploaded successfully.';
                } else {
                    $response['message'] = 'Failed to upload file.';
                }
            }
        } else {
            $response['message'] = 'No file uploaded or missing parameters.';
        }
        return $this->response->setJSON($response);
    }
 
    




    public function registrationcmis()
    {
     
        $data['get_menus_rs']=$this->Model_menu->select("menu_nm,substr(menu_id,1,2),url as menu_id")->where(['substr(menu_id,3)'=>'0000000000','display'=>'Y','menu_id is not'=>null])->orderBy('priority')->get()->getResultArray();
        $data['action_permission_allotment']=$this->Menu_model->get_action_permission_allotment();
        $data['menu_list']=$this->Menu_model->get_menu_list();
        $data['role_master_list']=$this->Menu_model->get_role_master_with_role_menu_mapping_list();
        //echo '<pre>';print_r($data['role_master_list']);exit();
        $data['advocate'] = $this->AORPendingMatters->getPendingMatters();
        $data['casetype'] = $this->AORPendingMatters->getCaseType();
        $data['state_name'] = $this->AORPendingMatters->getStates();
        // pr($datadddd);
        return view('MasterManagement/advocate/registration_cmis',$data);

      
    }


    public function registrationcmisStore()
    {

        $this->validation->setRules([
            'adv_enroll_dt' => 'permit_empty|valid_date[Y-m-d]',
            'adv_dob' => 'permit_empty|valid_date[Y-m-d]',
            'adv_enroll_no' => 'required',
            'adv_state' => 'required',
            'adv_name' => 'required',
        ]);

        if (!$this->validation->run($this->request->getPost())) {
            return $this->response->setJSON(['status'=> 404,'errors' => $this->validation->getErrors()]);
        }

           // Retrieve and format dates
           $adv_enroll_dt = $this->request->getPost('adv_enroll_dt');
           $adv_dob = $this->request->getPost('adv_dob');
   
           $timezone = 'America/New_York'; 
   
           $adv_enroll_dt = empty($adv_enroll_dt) ? null : (new Time($adv_enroll_dt, $timezone))->format('Y-m-d');
           $adv_dob = empty($adv_dob) ? null : (new Time($adv_dob, $timezone))->format('Y-m-d');
   
           $aor_code = $this->request->getPost('aor_code') ?: 0;
           $adv_year = $this->request->getPost('adv_year');
           $dateComponents = explode('-', $adv_enroll_dt);
           $year = $dateComponents[0] ?? null;


           $check = $this->AORPendingMatters->checkRecordExists($this->request->getPost('adv_enroll_no'), $year, $this->request->getPost('adv_state'));
           if ($check) {
               return $this->response->setJSON(['status'=> 404,'message' => 'Record Already Present for given enrollment no and year']);
           }
           $checkDate = function ($date) {
               return $date !== null && !checkdate($date[1], $date[2], $date[0]);
           };
   
           if ($adv_enroll_dt !== null && ($checkDate($dateComponents) || $year > date('Y'))) {
               return $this->response->setJSON(['status'=> 404,'message' => 'ENROLMENT DATE IS NOT A VALID DATE']);
           }
           $dobComponents = $adv_dob ? explode('-', $adv_dob) : [];
           if ($adv_dob !== null && ($checkDate($dobComponents) || ($dobComponents[0] >= date('Y')))) {
               return $this->response->setJSON(['status'=> 404,'message' => 'DoB IS NOT A VALID DATE']);
           }

           $code = $this->AORPendingMatters->getNextAORCode();

        $data = [
            'title' => $this->request->getPost('adv_tite'),
            'name' => $this->request->getPost('adv_name'),
            'fname' => $this->request->getPost('adv_fhnm'),
            'dob' => $adv_dob,
            'caddress' => $this->request->getPost('adv_address'),
            'ccity' => $this->request->getPost('adv_city'),
            'sex' => $this->request->getPost('adv_sex'),
            'cast' => $this->request->getPost('adv_cast'),
            'mobile' => $this->request->getPost('adv_mob'),
            'enroll_no' => $this->request->getPost('adv_enroll_no'),
            'enroll_date' => $adv_enroll_dt,
            'passing_year' => $this->request->getPost('adv_year'),
            'email' => $this->request->getPost('adv_email'),
            'rel' => $this->request->getPost('adv_rel'),
            'mname' => $this->request->getPost('adv_moth'),
            'pp' => $this->request->getPost('adv_pp'),
            'if_aor' => $this->request->getPost('adv_aor'),
            'state_id' => $this->request->getPost('adv_state'),
            'bentdt' => date('Y-m-d H:i:s'),
            'bentuser' => $this->session->get('dcmis_user_idd'),
            'aor_code' => $code,
            'if_sen' => $this->request->getPost('adv_sen')
        ];

        $this->db->table('master.bar')->insert($data);

        if ($this->request->getPost('adv_mob')) {
            $smsData = [
                'mobile' => $this->request->getPost('adv_mob'),
                'msg' => "{$this->request->getPost('adv_tite')}. {$this->request->getPost('adv_name')}, You have been allotted AOR code: {$this->request->getPost('aor_code')} in Supreme Court Of India. - Supreme Court of India",
                'c_status' => 'N',
                'table_name' => 'AOR',
                'ent_time' => date('Y-m-d H:i:s'),
                'template_id' => '1107161234609750003'
            ];
            $this->db->table('sms_pool')->insert($smsData);

            return $this->response->setJSON(['status'=> 200,'message' => "AOR registered successfully with AOR code [$code]. Msg Sent Successfully to Mobile Number {$this->request->getPost('adv_mob')}."
            ]);
          } else {
            return $this->response->setJSON(['status'=> 400,'message' => "AOR registered successfully with AOR code [$code]. Mobile No Empty. Can't Send SMS to Advocate."
            ]);
        }
    }



    public function UpdateDeathElevationResignationDeletionBlock(){

        $data['get_menus_rs']=$this->Model_menu->select("menu_nm,substr(menu_id,1,2),url as menu_id")->where(['substr(menu_id,3)'=>'0000000000','display'=>'Y','menu_id is not'=>null])->orderBy('priority')->get()->getResultArray();
        $data['action_permission_allotment']=$this->Menu_model->get_action_permission_allotment();
        $data['menu_list']=$this->Menu_model->get_menu_list();
        $data['role_master_list']=$this->Menu_model->get_role_master_with_role_menu_mapping_list();
        //echo '<pre>';print_r($data['role_master_list']);exit();
        $data['state_name'] = $this->AORPendingMatters->getStates();
        // pr($datadddd);
        return view('MasterManagement/advocate/UpdatedeathElevationResignationDeletionBlock',$data);
        
    }



    public function getdetailsUDERDB(){

        $data['state'] = $this->request->getPost('state');
        $data['enroll'] = $this->request->getPost('enroll');
        $data['year'] = $this->request->getPost('year');
        $data['aor'] = $this->request->getPost('aor');
        $state = $this->request->getPost('state');
        $enroll = $this->request->getPost('enroll');
        $year = $this->request->getPost('year');
        $aor = $this->request->getPost('aor');
        $data['result'] = $this->AORPendingMatters->getDetails($state, $enroll, $year, $aor);
        // pr($data);
        if (empty($data['result'])) {
            $data['error'] = '<h3 style="color:red;">SORRY, NO RECORD FOUND!!!</h3>';
        }
        return view('MasterManagement/advocate/GetDetailsUpdatedeathElevationResignationDeletionBlock',$data);
        
    }


    public function updateRecord()
    {

        $state = $this->request->getPost('state');
        $enroll = $this->request->getPost('enroll');
        $year = $this->request->getPost('year');
        $aor = $this->request->getPost('aor');
        $dead = $this->request->getPost('dead');

        if (empty($aor)) {
            $whereCondition = [
                'state_id' => $state,
                'enroll_no' => $enroll,
                'YEAR(enroll_date)' => $year
            ];
            }else {
                $whereCondition = [ 'aor_code' => $aor ];
            }

        $data = [
            'isdead' => $dead,
            'date_of_dead' => date('Y-m-d H:i:s'),
            'bupuser' => $_SESSION['dcmis_user_idd'] ?? null,
            'bupdt' => date('Y-m-d H:i:s')
        ];
        try {
            
            $updated =  $this->db->table('master.bar');
            $updated->where($whereCondition)->update($data);
           
            if ($updated) {
                return $this->response->setJSON([ 'status' => 200, 'message' => 'RECORD UPDATED SUCCESSFULLY' ]);
            } else {
                return $this->response->setJSON([ 'status' => 400, 'message' => 'NO RECORDS UPDATED' ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 400,'message' => 'Error updating record: ' . $e->getMessage()]);
        }
    }




    public function UpdateAdvocateFullDetails()
    {
        
        $data['get_menus_rs']=$this->Model_menu->select("menu_nm,substr(menu_id,1,2),url as menu_id")->where(['substr(menu_id,3)'=>'0000000000','display'=>'Y','menu_id is not'=>null])->orderBy('priority')->get()->getResultArray();
        $data['action_permission_allotment']=$this->Menu_model->get_action_permission_allotment();
        $data['menu_list']=$this->Menu_model->get_menu_list();
        $data['role_master_list']=$this->Menu_model->get_role_master_with_role_menu_mapping_list();
        $data['state_name'] = $this->AORPendingMatters->getStates();
        return view('MasterManagement/advocate/UpdateAdvocatefulldetails',$data);
    }




    public function getUpdateFull()
    {
        $data['state'] = $this->request->getVar('state');
        $data['enroll'] = $this->request->getVar('enroll');
        $data['year'] = $this->request->getVar('year');
        $data['aor'] = $this->request->getVar('aor');
        $aor = $this->request->getVar('aor');
        $stateId = $this->request->getVar('state');
        $enrollNo = $this->request->getVar('enroll');
        $year = $this->request->getVar('year');
        $data['record'] = $this->AORPendingMatters->getFullDetails($stateId, $enrollNo, $year, $aor);
        $data['state_name'] = $this->AORPendingMatters->getStates();
        return view('MasterManagement/advocate/templategetFullupdate', $data);
    }

 
    public function UpdateAdvBar()
    {
        $request = \Config\Services::request();
        $enrollDate = $request->getGet('enroll_date');
        $advDob = $request->getGet('adv_dob');
        $aorCode = $request->getGet('aor_code');
        $advState = $request->getGet('adv_state');
        $advEnrollNo = $request->getGet('adv_enroll_no');
        $advEnrollDt = $request->getGet('adv_enroll_dt');
        $enrollDate = $this->formatDate($enrollDate);
        $advDob = $this->formatDate($advDob);
        $aorCode = !empty($aorCode) ? (int)$aorCode : null;
        $advState = !empty($advState) ? $advState : null;
        $advEnrollNo = !empty($advEnrollNo) ? $advEnrollNo : null;
        $advEnrollDt = !empty($advEnrollDt) ? $advEnrollDt : null;

        $builder = $this->db->table('master.bar');
        if (empty($aorCode)) {
            $builder->where('state_id', $advState);
            $builder->where('enroll_no', $advEnrollNo);
            $builder->where("EXTRACT(YEAR FROM enroll_date) =", $advEnrollDt);
        } else {
            $builder->where('aor_code', $aorCode);
        }

        if (!$this->isValidDate($enrollDate) || !$this->isValidDate($advDob)) {
            return $this->response->setBody("<span style='color:red;text-align:center'><h3 style='color:red;'>DoB IS NOT A VALID DATE</h3></span>")->setContentType('text/html');
        
        }
        
        $data = [
            'title' => $request->getGet('adv_tite'),
            'name' => $request->getGet('adv_name'),
            'fname' => $request->getGet('adv_fhnm'),
            'dob' => $advDob,
            'caddress' => $request->getGet('adv_address'),
            'ccity' => $request->getGet('adv_city'),
            'sex' => $request->getGet('adv_sex'),
            'cast' => $request->getGet('adv_cast'),
            'mobile' => $request->getGet('adv_mob'),
            'passing_year' => $request->getGet('adv_year'),
            'email' => $request->getGet('adv_email'),
            'rel' => $request->getGet('adv_rel'),
            'mname' => $request->getGet('adv_moth'),
            'pp' => $request->getGet('adv_pp'),
            'bupdt' => Time::now(),
            'bupuser' => session()->get('dcmis_user_idd'),
            'enroll_no' => $request->getGet('enroll_no'),
            'enroll_date' => $enrollDate,
            'state_id' => $request->getGet('state'),
            'updated_on' => (new \DateTime())->format('Y-m-d H:i:s')
        ];

        if ($builder->update($data)) {
            return $this->response->setBody("<span style='text-align:center'><h3 style='color:green;'>Updated Successfully!!!</h3></span>")
                                  ->setContentType('text/html');
        } else {
            return $this->response->setBody("<span style='text-align:center'><h3 style='color:red;'>Update failed</h3></span>")->setContentType('text/html');
        }
    }

    private function formatDate($date)
    {
        if (empty($date) || $date === '0000-00-00') {
            return '0000-00-00';
        }
        $dateParts = explode('-', $date);
        return "{$dateParts[2]}-{$dateParts[1]}-{$dateParts[0]}";
    }

    private function isValidDate($date)
    {
        if ($date === '0000-00-00') {
            return true; 
        }
        $dateParts = explode('-', $date);
        return checkdate($dateParts[1], $dateParts[2], $dateParts[0]) && $dateParts[0] <= date('Y');
    }

    



    public function UpdateAdvocateShortDetails()
    {
      
        $data['get_menus_rs']=$this->Model_menu->select("menu_nm,substr(menu_id,1,2),url as menu_id")->where(['substr(menu_id,3)'=>'0000000000','display'=>'Y','menu_id is not'=>null])->orderBy('priority')->get()->getResultArray();
        $data['action_permission_allotment']=$this->Menu_model->get_action_permission_allotment();
        $data['menu_list']=$this->Menu_model->get_menu_list();
        $data['role_master_list']=$this->Menu_model->get_role_master_with_role_menu_mapping_list();
        $data['state_name'] = $this->AORPendingMatters->getStates();

        return view('MasterManagement/advocate/UpdateAdvocateShortDetails',$data);
        // templateShortdetails
        

    }



    public function templateUpdateShortdetails()
    {
        $state = $this->request->getPost('state');
        $enroll = $this->request->getPost('enroll');
        $year = $this->request->getPost('year');
        $aor = $this->request->getPost('aor');

        $data['state'] = $this->request->getPost('state');
        $data['enroll'] = $this->request->getPost('enroll');
        $data['year'] = $this->request->getPost('year');
        $data['aor'] = $this->request->getPost('aor');
        $data['state_name'] = $this->AORPendingMatters->getStates();
        $data['bar'] =  $this->AORPendingMatters->getDetails($state, $enroll, $year, $aor);
        // pr($result);
       
        return view('MasterManagement/advocate/templateShortdetails',$data);
        // templateShortdetails
        

    }



    public function UpdateShortdetailsStore()
    {

         $session = session();
         $data = [
             'aor' => $this->request->getVar('aor'),
             'state' => $this->request->getVar('state'),
             'enroll' => $this->request->getVar('enroll'),
             'year' => $this->request->getVar('year'),
             'aor_code' => $this->request->getVar('aor_code'),
             'aor_code_db' => $this->request->getVar('aor_code_db'),
             'adv_aor' => $this->request->getVar('adv_aor'),
             'adv_sen' => $this->request->getVar('adv_sen'),
             'title' => $this->request->getVar('title'),
             'name' => strtoupper($this->request->getVar('name')),
             'mobile' => $this->request->getVar('mobile'),
             'email' => $this->request->getVar('email'),
             'address' => $this->request->getVar('address'),
             'city' => $this->request->getVar('city')
         ];
     
         $builder = $this->db->table('master.bar');
         if ($data['aor'] === '') {
             $builder->where('state_id', $data['state']);
             $builder->where('enroll_no', $data['enroll']);
             $builder->where("EXTRACT(YEAR FROM enroll_date) =", $data['year']);
         } else {
             $builder->where('aor_code', $data['aor']);
         }

         $updateData = [
             'if_aor' => $data['adv_aor'],
             'if_sen' => $data['adv_sen'],
             'title' => $data['title'],
             'name' => $data['name'],
             'mobile' => $data['mobile'],
             'email' => $data['email'],
             'bupuser' => $session->get('dcmis_user_idd'),
             'bupdt' => date('Y-m-d H:i:s'),
             'caddress' => $data['address'],
             'ccity' => $data['city'],
              'updated_on' => (new \DateTime())->format('Y-m-d H:i:s')
         ];
     
         try {
             if ($data['aor_code'] !== $data['aor_code_db']) {
                 if ($data['adv_aor'] === 'N') {
                     $builder->update($updateData);
                 } elseif ($data['adv_aor'] === 'Y') {
                     $exists = $builder->where('aor_code', $data['aor_code'])
                                        ->where('aor_code !=', 0)
                                        ->get()
                                        ->getRowArray();
     
                     if ($exists) {
                         echo "<span><h3 style='color:red;'>Record Already Present for given AOR CODE</h3></span>";
                         exit();
                     }
                     $updateData['aor_code'] = $data['aor_code'];
                     $builder->update($updateData);
                 }
             } else {
                 $builder->update($updateData);
             }
     
             echo "<div class='okay' style='color:green;'>RECORD UPDATED SUCCESSFULLY</div>";
         } catch (\Exception $e) {
             echo "<span><h3 style='color:red;'>Error Updating Record: " . $e->getMessage() . "</h3></span>";
         }


    }
       

 
    
    
    
}
