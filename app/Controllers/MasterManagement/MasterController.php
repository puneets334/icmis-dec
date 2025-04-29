<?php

namespace App\Controllers\MasterManagement;
use App\Controllers\BaseController;
use App\Models\MasterManagement\MasterModel;
use App\Models\MasterManagement\KeywordModel;
use CodeIgniter\Controller;
use CodeIgniter\Model;
use App\Models\MasterManagement\IPModel;

 
class MasterController extends BaseController
{

public $MasterModel;
protected $keywordModel;
    function __construct()
    {
        ini_set('memory_limit','51200M'); 
        $this->MasterModel = new MasterModel();
        $this->keywordModel = new KeywordModel();
        //error_reporting(0);
        
    }

   
    public function add()
    {
       
            $data['get_law_firm'] =$this->MasterModel->get_law_firm();
            $data['get_state'] =$this->MasterModel->get_state(); 
           return view('MasterManagement/master/add',$data); 
    }


 
    public function get_adv_by_enroll_no()
    {
        $enroll_no = $this->request->getGet('enroll_no');
        $enroll_yr = $this->request->getGet('enroll_yr');
        if (empty($enroll_no) || empty($enroll_yr)) {
            echo "0||<div class='error-message'>Enroll number and year are required</div>";
            return;
        }
    
        $advocates = $this->MasterModel->getAdvocateDetails($enroll_no, $enroll_yr);
        if (!empty($advocates)) {
            $response = "1||<div class='table-responsive' style='text-align: -webkit-center;'><table class='table table-hover table-bordered' style='width: auto;'>";
            $response .= "<tr><th align='left'><h3>Advocate Name</h3></th><th><h3>AOR CODE</h3></th></tr>";
    
            foreach ($advocates as $advocate) {
                $response .= "<tr><td>{$advocate['name']}</td><td>{$advocate['aor_code']}</td></tr>";
            }
    
            $response .= "</table></div>";
            echo $response;
        } else {
            echo "0||<div class='error-message'>No records found.</div>";
        }
    }


        private function date_ex($date_f)
        {
            $d = explode('-', $date_f);
            return $d[2] . '-' . $d[1] . '-' . $d[0];
        }
        
        

        
        public function lawfirmaddprocess()
        {
            // Get request data
            // pr($this->request->getGet());
            $law_firm_id = $this->request->getGet('law_firm_id');
            $enroll_no = $this->request->getGet('enroll_no');
            $enroll_yr = $this->request->getGet('enroll_yr');
            $state_id = $this->request->getGet('state_id');
            $from_date = $this->date_ex($this->request->getGet('from_date'));
            $to_date = $this->date_ex($this->request->getGet('to_date'));

          
            $existingRecord = $this->MasterModel->checkDuplicate($law_firm_id, $enroll_no, $enroll_yr, $state_id, $from_date, $to_date);
            if ($existingRecord) {
                return $this->response->setJSON('This entry has already been taken');
            }
            
            $data = [
                'law_firm_id' => $law_firm_id,
                'enroll_no' => $enroll_no,
                'enroll_yr' => $enroll_yr,
                'state_id' => $state_id,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'display' => 'Y',
                'entry_date' => date('Y-m-d H:i:s'),
            ];
        
            try {
                $this->db->table('master.law_firm_adv')->insert($data);
                return $this->response->setJSON('Record Saved Successfully...!');
            } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
                if ($e->getCode() == 23505) { 
                    return $this->response->setJSON('Error: Duplicate entry for law firm.');
                } else {
                    return $this->response->setJSON('Error inserting record: ' . $e->getMessage());
                }
            } catch (\Exception $e) {
                return $this->response->setJSON('Error inserting record: ' . $e->getMessage());
            }
        }
        

       
        // public function AddUpdates()
        // {
        //     return view('MasterManagement/master/add_update'); 
        // }

    
        public function transfer_Judge_Categor()
        {
            $mf = $this->request->getPost('mf');
            $judge = $this->request->getPost('judge');

            $data['app_name'] = "Transfer Judge Category";
            $data['usercode'] =  session()->get('login')['usercode'];
            $data['judge']=$this->MasterModel->getJudge();

            // if (isset($judge))
            // {
            //     $fData = $this->MasterModel->getJudgeRecord($judge,$mf);
            //     $data['judge_details'] = ($fData) ? $fData : 'No'; 
            // }
           return  view('MasterManagement/master/judge_catg_bulk_transfer', $data);    
        }



        public function transfer_insert_category()
        {
            $judge_from = $this->request->getPost('judge_from');
            $judge_to = $this->request->getPost('judge_to');
            $mf= $this->request->getPost('mf');
            $usercode = $this->request->getPost('usercode');
            if (isset($judge_from) && isset($judge_to) && isset($usercode)) {
                $this->MasterModel->transfer_judge_category($judge_from, $judge_to, $usercode, $mf);
            }
        }



        public function judgeCategoryUpdate()
        {
            $datssa['app_name'] = NULL;                        
            $session = session()->get('login')['usercode'];
            $data['usercodeses_get'] = session()->get('login')['usercode'];
            $mf = $this->request->getPost('mf');
            $judge = $this->request->getPost('judge');
            $sessionService = session();

            // Handle judge session value
            if ($judge !== null) {
                if ($judge != '') {
                    $sessionService->set('judge_selected_code', $judge);
                } else {
                    $sessionService->remove('judge_selected_code');
                }
            }

            if ($mf !== null) {
                if ($mf != '') {
                    $sessionService->set('mf_code', $mf);
                } else {
                    $sessionService->remove('mf_code');
                }
            }

            // Use judge from session (if exists)
            $judge_selected_code = $sessionService->get('judge_selected_code'); 
            $mf_code = $sessionService->get('mf_code');         

            if (isset($judge_selected_code)) {
                
                $data['app_name'] = "Judge Master";
                $data['judge_details'] = $this->MasterModel->getJudgeRecord($judge_selected_code, $mf);
                
            }
            $data['judge'] = $this->MasterModel->getJudge();
            $data['judge_selected_code'] = $judge_selected_code;
            $data['mf_code'] = $mf_code;

            $data['matters'] = $mf;

            return view('MasterManagement/master/judge_category_update', $data);
        }
 


        public function update_judge_category()
        {
            // pr($this->request->getPost());
            $data['app_name'] = "Judge Master";
            $priority = $this->request->getPost('priority');
            $toDate = $this->request->getPost('toDate');
            $id = $this->request->getPost('id');
            $usercode = $this->request->getPost('usercode');
            $mf= $this->request->getPost('mf');
            if (isset($priority) && isset($id))
                $this->MasterModel->update_judge_category($priority, $toDate,$id,$usercode,$mf);
        }



        public function MasterKeyword()
        {
            // pr($this->request->getPost());
            return view('MasterManagement/master/keyword');
        }



        public function save_User()
        {
         
        //    dd('fsdf');
        $userData = $this->request->getPost('user');
        $id = $userData['id'];
         $keywordDescription = $userData['keyword_description'];
 
            $data = [];
            try {
                $keyword =  $keywordDescription;
                if (empty($keyword)) {
                    throw new \Exception("Required fields missing, Please enter and submit");
                }
                $existingKeyword = $this->keywordModel->where('keyword_description', $keyword)->first();
                if ($existingKeyword) {
                    $data['message'] = 'Keyword already exists';
                } else {
                    if (empty($id)) {
                        $this->keywordModel->insert([
                            'keyword_code' => '45',
                            'keyword_description' => $keyword,
                            'updated_by' => '1',
                            'updated_on' => date('Y-m-d H:i:s'),
                            'is_deleted' => 'f'
                        ]);
                        $data['message'] = 'Keyword inserted successfully.';
                        $data['id'] = $this->keywordModel->insertID();
                    } else {
                        $this->keywordModel->update($id, [
                            'keyword_description' => $keyword,
                            'updated_on' => date('Y-m-d H:i:s')
                        ]);
                        $data['message'] = 'Keyword updated successfully.';
                        $data['id'] = (int)$id;
                    }
    
                    $data['success'] = true;
                }
            } catch (\Exception $e) {
                $data['success'] = false;
                $data['message'] = $e->getMessage();
            }
    
            return $this->response->setJSON($data);
        }
    
        public function deleteUser($id = null)
        {
            $data = [];
            try {
                if (empty($id)) throw new \Exception("Invalid Keyword.");
                $this->keywordModel->delete($id);
                $data['success'] = true;
                $data['message'] = 'Keyword deleted successfully.';
            } catch (\Exception $e) {
                $data['success'] = false;
                $data['message'] = $e->getMessage();
            }
            return $this->response->setJSON($data);
        }
    
        public function getUsers()
        {
            $data = [];
            try {
                $result = $this->keywordModel->orderBy('id', 'asc')->findAll(100);
                $data['data'] = array_map(function ($row) {
                    return [
                        'id' => (int)$row['id'],
                        'keyword_code' => $row['keyword_code'],
                        'keyword_description' => $row['keyword_description'],
                        'updated_by' => $row['updated_by'],
                        'updated_on' => $row['updated_on'],
                        'is_deleted' => $row['is_deleted']
                    ];
                }, $result);
                $data['success'] = true;
            } catch (\Exception $e) {
                $data['success'] = false;
                $data['message'] = $e->getMessage();
            }
    
            return $this->response->setJSON($data);
        }
    
}
