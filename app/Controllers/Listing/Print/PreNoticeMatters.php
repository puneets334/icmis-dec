<?php

namespace App\Controllers\Listing\Print;

use App\Controllers\BaseController;

use App\Models\Menu_model;
use CodeIgniter\Controller;
use CodeIgniter\Model;
use App\Models\Filing\AdvocateModel;
use App\Models\Listing\ReportModel;
use App\Models\Listing\ListingStatisticsModel;
use App\Models\Listing\SpreadOutCertificateModel;
use App\Models\Listing\RoserRegModel;
use App\Models\Listing\CaseAdd;
use App\Models\Casetype;

class PreNoticeMatters extends BaseController
{
    public function index(){
        
        return view('Listing/print/pre_notice_matters_data');
    }
}