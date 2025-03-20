<?php

namespace App\Controllers\MasterManagement;
use App\Controllers\BaseController;
use App\Models\MasterManagement\LatestupdatesModel;
use CodeIgniter\Controller;
use CodeIgniter\Model;
use App\Models\MasterManagement\IPModel;

 
class LatestUpdatesController extends BaseController
{

public $LatestupdatesModel;
    function __construct()
    {
        ini_set('memory_limit','51200M'); 
        $this->LatestupdatesModel = new LatestupdatesModel();
        //error_reporting(0);
        
    }

   
    public function index()
    {
        //$data['case_result']='';
       // $data['app_name']='Retrieve Not Found Cases.';
        /*if($_POST) {
            $Diary_No=$this->input->post('Diary_No');
            $Diary_Year=$this->input->post('Diary_Year');

            //Retrieve Result.
            $result_array =$this->NoRecord_model->getRecord_from_main($Diary_No, $Diary_Year);
            $data['case_result'] = $result_array;
        }*/
       
        $data['usercode'] = session()->get('login')['usercode'];
        $data['updates']=$this->LatestupdatesModel->get_menu_latestupdates();

       return view('MasterManagement/latest_updates/LatestUpdates_Entry',$data); 
    }
       


    
    public function display_Latest_Updates()
    {
        ob_clean();
        header("Content-Type: application/json;charset=utf-8");
        $arr = $this->LatestupdatesModel->display_Latest_Updates();
        echo json_encode($arr);
        ob_end_flush();

    }

 
    public function insert_Latest_updates()
    {

        $data['insert_result'] = '';
        if ($this->request->getMethod() === 'post') {
            $updatedFor = $this->request->getPost('updated_for');
            $fromDate = $this->request->getPost('from_date');
            $toDate = $this->request->getPost('to_date');
            $description = $this->request->getPost('dsc');
            $userCode = $this->request->getPost('usercode');

            if ($updatedFor && $fromDate && $toDate && $description) {
                $menu = explode('^', $updatedFor);
                $frmDt = date('Y-m-d', strtotime($fromDate));
                $toDt = date('Y-m-d', strtotime($toDate));

                $insertStatus = $this->LatestupdatesModel->insertLatestupdates($menu[0], $frmDt, $toDt, $description, $userCode);

                $data['insert_result'] = $insertStatus ? 'Insert successful!' : 'Insert failed!';
            }
        }
       
        return $this->display_Latest_Updates($data);
    
    }
    

    


    
}
