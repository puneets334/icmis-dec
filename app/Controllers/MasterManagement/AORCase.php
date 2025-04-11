<?php

namespace App\Controllers\MasterManagement;
use App\Controllers\BaseController;
use App\Models\Entities\Model_menu;
use App\Models\MasterManagement\AORcaseReportsModel;
use App\Models\Menu_model;
use CodeIgniter\Controller;
use CodeIgniter\Model;
use DateTime;
use CodeIgniter\I18n\Time;

class AORCase extends BaseController
{
public $Model_menu;
public $Menu_model;
public $AORcaseReportsModel;
    function __construct()
    {
        ini_set('memory_limit','51200M'); 
        $this->Model_menu = new Model_menu();
        $this->Menu_model = new Menu_model();
        $this->AORcaseReportsModel = new AORcaseReportsModel();
        //error_reporting(0);
    }

    public function index()
    {

        $type  =  $this->request->getPost('type');
        $data['app_name'] = '';
        $data['reports'] = [];
        $data['param'] = [];
        if ($this->request->getPost()) {
            $aorCode = $this->request->getPost('aorCode');
            $resultArray = $this->AORcaseReportsModel->get_aor_detail2($aorCode);
            $data['sub_reports'] = $resultArray;
            if ($this->request->getPost('type')) {
                $type = $this->request->getPost('type');
                $resultArray = $this->AORcaseReportsModel->get_aor_detail2_report($aorCode, $type);
                $data['reports'] = $resultArray;
                $data['app_name'] = 'AOR CASES';
                // pr($resultArray);
            }
            $data['param'] = [$aorCode];
        }
        switch ($type) {
            case 1:
                $data['list_type'] = "Pending Un-Registered Cases";
                break;
            case 2:
                $data['list_type'] = "Pending Registered Cases";
                break;
            case 3:
                $data['list_type'] = "Disposed Cases";
                break;
        }
        //pr($data);die();
        return view('MasterManagement/aor_case/aorcasedefault',$data);
    }

	public function aor_detail(){
        $flag = session()->get('login')['usercode'];
        if($flag){
            $data['app_name']='AOR CASES';
            $data['reports'] = '';
            $data['param']='';
            $data['aorCode'] = $this->request->getPost('aorCode');
            if ($this->request->getPost()) {
                $aorCode = $this->request->getPost('aorCode');
                $fromDate = date('Y-m-d', strtotime($this->request->getPost('fromDate')));
                $toDate = date('Y-m-d', strtotime($this->request->getPost('toDate')));
                if (DateTime::createFromFormat('Y-m-d', $fromDate) === false || DateTime::createFromFormat('Y-m-d', $toDate) === false) {
                    return false;
                }
                $result_array = $this->AORcaseReportsModel->get_aor_detail($aorCode, $fromDate, $toDate, $flag);
                $data['reports'] = $result_array;
                $data['param'] = array($aorCode, $fromDate, $toDate);
            }
           return view('MasterManagement/aor_case/aorcase', $data);
        }
    }

    
}
