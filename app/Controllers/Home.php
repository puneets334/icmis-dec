<?php

namespace App\Controllers;
use App\Models\Model_icmis_njdg_sep;
class Home extends BaseController
{
    protected $session;

    function __construct()
    {   $session = session();
        $this->session = \Config\Services::session();
        $this->session->start();
        // $this->LoginModel = new LoginModel();
        helper(['url', 'form']);
        helper("functions");
        date_default_timezone_set('Asia/Calcutta');
    }
    public function index()
    {

        $Model_report = new Model_icmis_njdg_sep();
        //$disposal=$Model_report->get_disposal();
        $pendency=$Model_report->get_pendency();
        /*$get_coram_wise_matters=$Model_report->get_coram_wise_matters();

        $case_typewise_pendency=$Model_report->get_case_typewise_pendency();*/
        $data=[
            //'coram_wise_matters'=>$get_coram_wise_matters,
            'pendency'=>$pendency,
            //'disposal'=>$disposal,
           // 'case_typewise_pendency'=>$case_typewise_pendency,
        ];
       //echo '<pre>';print_r($data);exit();
        return view('report_icmic_njdg',$data);
        //return view('report_icmic_njdg');

       // return view('welcome_message');
    }
}
