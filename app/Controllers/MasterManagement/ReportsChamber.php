<?php

namespace App\Controllers\MasterManagement;
use App\Controllers\BaseController;
use App\Models\Entities\Model_menu;
use App\Models\MasterManagement\ReportsModelChamber;
use CodeIgniter\Controller;
use CodeIgniter\Model;
use DateTime;
use CodeIgniter\I18n\Time;

class ReportsChamber extends BaseController
{

public $ReportsModelChamber;
    function __construct()
    {
        ini_set('memory_limit','51200M'); 
        $this->ReportsModelChamber = new ReportsModelChamber();
        //error_reporting(0);
    }

    public function aor_detail(){
      $flag=1;
      $data['app_name']='AOR CASES';
      $data['reports'] = '';
      $data['param']='';
      if($this->request->getPost())
      {
          $aorCode = $this->request->getPost('aorCode');
          $fromDate=date('Y-m-d', strtotime($this->request->getPost('fromDate')));
          $toDate=date('Y-m-d', strtotime($this->request->getPost('toDate')));
          $result_array = $this->ReportsModelChamber->get_aor_detail($aorCode,$fromDate,$toDate, $flag);
          $data['reports'] = $result_array;
          $data['param']=array($aorCode,$fromDate,$toDate);
      }
      return view('MasterManagement/aor_case/aordetailchamber',$data);
  }


    public function aor_detail_new(){        
        $flag=1;
        $data['app_name']='AOR CASES';
        $data['reports'] = '';
        $data['param']='';
        if($this->request->getPost())
        {
            $aorCode = $this->request->getPost('aorCode');
            $fromDate=date('Y-m-d', strtotime($this->request->getPost('fromDate')));
            $toDate=date('Y-m-d', strtotime($this->request->getPost('toDate')));
            $result_array = $this->ReportsModelChamber->get_aor_detail($aorCode,$fromDate,$toDate, $flag);
            $data['reports'] = $result_array;
            $data['param']=array($aorCode,$fromDate,$toDate);
        }
        return view('MasterManagement/aor_case/aordetailnewchamber', $data);
        }



   

 
    
    
    
}
