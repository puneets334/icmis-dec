<?php

namespace App\Controllers\Judicial\Reports;

use App\Controllers\BaseController;
use App\Models\Judicial\Reports\LooseDocumentReportModel;

class LooseDocumentReport extends BaseController
{
    public $LooseDocumentReportModel;

    function __construct()
    {
        // ini_set('memory_limit','750M'); // This also needs to be increased in some cases. )
        // ini_set('memory_limit', '-1');

        $this->LooseDocumentReportModel = new LooseDocumentReportModel();

        // $this->request = \Config\Services::request();

        $this->session = session();
        $this->session->set('dcmis_user_idd', session()->get('login')['usercode']);
    }
}