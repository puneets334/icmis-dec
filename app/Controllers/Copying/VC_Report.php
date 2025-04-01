<?php
namespace App\Controllers\Copying;
use App\Controllers\BaseController;
use App\Models\Copying\Model_vcreport;
use setasign\Fpdi\Tcpdf\Tcpdf;
use setasign\Fpdi\Tcpdf\Fpdi;


class VC_Report extends BaseController
{

    public $model_vcreport;
    
    function __construct(){
        ini_set('memory_limit', '51200M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
        $this->model_vcreport = new Model_vcreport();
    }
	
	function vc_details_reports(){
		return view('Copying/vc_details_reports');
	}
	
    
   
}