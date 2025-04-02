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
	
	function get_vc_detailed_report(){
		 $from_dt = '2025-01-01';//date('Y-m-d', strtotime($this->request->getPost('dateFrom')));
	     $to_dt = '2025-04-01';//date('Y-m-d', strtotime($this->request->getPost('dateTo')));
		 $condition=  "between ".'"'.date('Y-m-d', strtotime($from_dt))  .'"'." and  ".'"'. date('Y-m-d', strtotime($to_dt)).'"';
	     
		 $main_taken_up = $this->model_vcreport->getMainConnTakenup_matters($from_dt, $to_dt);
		 $main_disposal = $this->model_vcreport->getMainConnDisposal_matters($from_dt, $to_dt);
		 $total_judgment = $this->model_vcreport->get_total_judgment($condition);
		 print_r($total_judgment); die;
		 $IA_disposed = $this->model_vcreport->get_IA_disposed($condition);
         $MA_Disposed= $this->model_vcreport->get_MA_disposed($condition);

		$slp_appeal_disposed = $this->model_vcreport->get_SLP_Appeals_disposed($condition);
		$writ_disposed = $this->model_vcreport->get_Writ_Petitions_disposed($condition);
		$transfer_pet_disposed = $this->model_vcreport->get_Transfer_Petitions_disposed($condition);

		$total_filing = $this->model_vcreport->get_total_filed($condition);

		$total_filing_SLP_Appeals = $this->model_vcreport->get_filing_SLP_Appeals($condition);
		$filing_IA = $this->model_vcreport->get_filing_IA($condition);
		$filing_MA = $this->model_vcreport->get_filing_MA($condition);

	
	}
	
	
    
   
}