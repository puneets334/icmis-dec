<?php

namespace App\Controllers\ManagementReports\Listing;

use App\Controllers\BaseController;
use App\Models\ManagementReport\CaseRemarksVerification;

class Report extends BaseController
{
    public $CaseRemarksVerification;
    public $Model_diary;

    function __construct()
    {
        set_time_limit(10000000000);
        ini_set('memory_limit', '-1');
        $this->CaseRemarksVerification = new CaseRemarksVerification();
    }

    public function date_given_by_da(){
        return view('ManagementReport/Listing/date_given_by_da_view');
    }

    public function date_given_by_da_get(){
        $data['list_dt'] = date('d-m-Y', strtotime($this->request->getPost('list_dt')));
        $list_dt_db = date('Y-m-d', strtotime($this->request->getPost('list_dt')));
        $data['data'] = $this->CaseRemarksVerification->date_given_by_da_get_data($data['list_dt'],$list_dt_db);
        return view('ManagementReport/Listing/date_given_by_da_get_view',$data);
    }

    public function dropped_cases()
    {
        return view('ManagementReport/Listing/dropped_cases_view');
    }

    public function dropped_cases_get()
    {
        $data['list_dt'] = date('d-m-Y', strtotime($this->request->getPost('list_dt')));
        $list_dt_db = date('Y-m-d', strtotime($this->request->getPost('list_dt')));
        $data['string_heading'] = ", Cause List". $data['list_dt'];
        $data['datetype'] = $this->request->getPost('datetype');
        $data['data'] = $this->CaseRemarksVerification->dropped_cases_get_data($data['list_dt'],$list_dt_db,$data['string_heading'],$data['datetype']);
        return view('ManagementReport/Listing/ddropped_cases_get_view',$data);
    }

}