<?php

namespace App\Controllers\Filing;
// use CodeIgniter\Controller;
use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;
use App\Models\Filing\CaveatModel;
use App\Libraries\webservices\Efiling_webservices;
use App\Libraries\webservices\Highcourt_webservices;


class CaveatReport extends BaseController
{
    // protected $session;
    public $Dropdown_list_model;
    public $efiling_webservices;
    public $highcourt_webservices;
    public $CaveatModel;
	protected $diary_no;

    function __construct()
    {
        $this->Dropdown_list_model= new Dropdown_list_model();
        $this->CaveatModel = new CaveatModel();
		 
    }

 
     
	
	public function getNinetyDaysOldCaveat()
	{
		$ucode =  $_SESSION['login']['usercode'];		
		//$filing_details = session()->get('filing_details');		 
		$data['CaveatModel'] = $this->CaveatModel;
		//$data['diary_no'] = $filing_details['diary_no'];
		echo view('Filing/caveat_report/get_nintydays_caveat',$data);
	}
	
	
	public function caveat_ninedays_report()
	{
		$ucode =  $_SESSION['login']['usercode'];
		$filing_details = session()->get('filing_details');		 
		$data['CaveatModel'] = $this->CaveatModel;
		$data['dateFrom'] = $date=  date('Y-m-d',strtotime($_REQUEST['dateFrom']));
		$data['dateTo'] = $date1 = date('Y-m-d',strtotime($_REQUEST['dateTo']));
		$data['caveat_list'] = $this->CaveatModel->Caveat_List_Filed($date, $date1);
		
		echo view('Filing/caveat_report/tpl_caveat_ninedays_report',$data);
		die;
	}

    public function get_today_caveat()
	{
		$ucode =  $_SESSION['login']['usercode'];		
		//$filing_details = session()->get('filing_details');		 
		$data['CaveatModel'] = $this->CaveatModel;
		//$data['diary_no'] = $filing_details['diary_no'];
		echo view('Filing/caveat_report/get_today_caveat',$data);
	}

    public function caveat_report()
	{
		 
		$ucode =  $_SESSION['login']['usercode'];
		$filing_details = session()->get('filing_details');		 
		$data['CaveatModel'] = $this->CaveatModel;
		$data['dateFrom'] = date('Y-m-d',strtotime($_REQUEST['dateFrom']));
		$data['dateTo'] = date('Y-m-d',strtotime($_REQUEST['dateTo']));
		$data['caseTypeId'] = $_REQUEST['caseTypeId'];
		return view('Filing/caveat_report/caveat_report',$data);
	}
	

}