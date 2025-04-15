<?php

namespace App\Controllers\ManagementReports;

use App\Controllers\BaseController;
use App\Models\ManagementReport\NoDaCodeModel;

class NoDaCode extends BaseController
{
    public $NoDaCodeModel;
    public $Model_diary;

    function __construct()
    {
        set_time_limit(10000000000);
        ini_set('memory_limit', '-1');
        $this->NoDaCodeModel = new NoDaCodeModel();
    }

    public function index()
    {
        return view('ManagementReport/nodacode/nodacode');
    }

    public function get_nodacode_report()
    { 
        $section_id = session()->get('login')['section'];
        $data['results'] =  $this->NoDaCodeModel->get_nodacode_report($section_id);
        // pr($data['results']);
        // die;
        return view('ManagementReport/nodacode/get_nodacode_report', $data);
    }
    
}
