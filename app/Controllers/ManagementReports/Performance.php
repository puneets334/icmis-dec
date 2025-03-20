<?php

namespace App\Controllers\ManagementReports;

use App\Controllers\BaseController;

use CodeIgniter\Controller;
use CodeIgniter\Model;
use App\Models\ManagementReport\PerformanceModel;

class Performance extends BaseController
{
    protected $PerformanceModel;

    public function __construct()
    {
        $this->PerformanceModel = new PerformanceModel();
    }
    public function diary_entry_session_data(){
        $data['module_name'] = $this->PerformanceModel->module_name();
        $data['userSql'] = $this->PerformanceModel->users();
       
        if (!empty($this->request->getGet('fromDate'))) {
            $data['fromDate'] = date('Y-m-d', strtotime(trim($this->request->getGet('fromDate'))));
            $data['user_id'] = !empty($this->request->getGet('user_id')) ? trim($this->request->getGet('user_id')) : NULL;
            $data['module_id'] = !empty($this->request->getGet('module_id')) ? trim($this->request->getGet('module_id')) : NULL;
            $data['result_array'] = $this->PerformanceModel->diary_entry_session_data( $data['fromDate'],$data['user_id'],$data['module_id']);
        }
        return view('ManagementReport/Performance/diary_entry_session_data',$data);
    }
}