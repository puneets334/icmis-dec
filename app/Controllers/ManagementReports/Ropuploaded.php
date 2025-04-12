<?php

namespace App\Controllers\ManagementReports;

use App\Controllers\BaseController;
//use App\Models\ManagementReport\CaseRemarksVerification;
//use App\Models\Listing\Heardt;
//use App\Models\ManagementReport\ReportModel;
use App\Models\ManagementReport\RopuploadedModel;

class Ropuploaded extends BaseController
{

    //public $CaseRemarksVerification;
    //public $Model_diary;
    //public $Heardt;
    //public $ReportModel;
    public $RopuploadedModel;

    function __construct()
    {
        set_time_limit(10000000000);
        ini_set('memory_limit', '-1');
        //$this->CaseRemarksVerification = new CaseRemarksVerification();
        //$this->Heardt = new Heardt();
        //$this->ReportModel = new ReportModel();
        $this->RopuploadedModel = new RopuploadedModel();
        $this->session = session();
        $this->session->set('dcmis_user_idd', session()->get('login')['usercode']);
    }

    public function show_count()
    {
        $data['app_name'] = 'ROP Uploaded';

        $listDate = $this->request->getGet('listDate');
        $listing_date = null;

        if (!empty($listDate)) {
            $timestamp = strtotime($listDate);
            if ($timestamp !== false) {
                $listing_date = date('Y-m-d', $timestamp);
            }
        }

        if (!empty($listing_date) && $listing_date != '1970-01-01') {
            $result_array = $this->RopuploadedModel->show_count($listing_date);
            $data['list_stats'] = $result_array;
            $data['listing_date'] = $listing_date;
        }

        return view('ManagementReport/Rop/show_stats', $data);
    }

    public function details()
    {
        $data['app_name']='Details';
        $listing_date=$this->request->getGet('ldate');
        $cno=$this->request->getGet('cno');
        $data['list_details'] = [];
        $data['cno'] = '';
        if(isset($listing_date) && isset($cno) && !empty($listing_date) && !empty($cno))    
        {            
            $result_array=$this->RopuploadedModel->show_details($listing_date,$cno);            
            $data['list_details']=$result_array;
            $data['listing_date']=$listing_date;
            $data['cno']=$cno;
        }
        return view('ManagementReport/Rop/case_details', $data);                 
    }

    public function show_count_between_dates()
    {
        
        $data['app_name']='ROP Uploaded';    
        $fromDate = $this->request->getGet('fromDate');
        $toDate = $this->request->getGet('toDate');

        $from_date = $fromDate ? date('Y-m-d', strtotime($fromDate)) : null;
        $to_date = $toDate ? date('Y-m-d', strtotime($toDate)) : null;
        if (
            !empty($from_date) && $from_date !== '1970-01-01' &&
            !empty($to_date) && $to_date !== '1970-01-01'
        ) 
        {
            $result_array = $this->RopuploadedModel->show_count_between_dates($from_date,$to_date);
            $data['list_stats'] = $result_array;
            $data['from_date']=$from_date;
            $data['to_date']=$to_date;

        }
        return view('ManagementReport/Rop/show_count_between_dates', $data);        
    }
  
    

}