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

    public function diary_entry_session_data()
    {
        $request = service('request');
        $data['module_name'] = $this->PerformanceModel->module_name();
        $data['userSql'] = $this->PerformanceModel->users();       
        if (!empty($request->getVar('fromDate'))) {
            $data['fromDate'] = date('Y-m-d', strtotime(trim($request->getVar('fromDate'))));
            $data['user_id'] = !empty($request->getVar('user_id')) ? trim($request->getVar('user_id')) : NULL;
            $data['module_id'] = !empty($request->getVar('module_id')) ? trim($request->getVar('module_id')) : NULL;
            $data['result_array'] = $this->PerformanceModel->diary_entry_session_data($data['fromDate'], $data['user_id'], $data['module_id']);
        }
        return view('ManagementReport/Performance/diary_entry_session_data', $data);
    }

}