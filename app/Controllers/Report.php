<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Reports\ListedNotListedModel;
use App\Models\Reports\PendencyReport\PendencyReportsModel;

class Report extends BaseController
{
    protected $ListedNotListedModel;
    protected $pendency_reports_model;
    public function __construct()
    {
        $this->ListedNotListedModel = new ListedNotListedModel();
        $this->pendency_reports_model = new PendencyReportsModel();
		helper('function_helper');
    }
    public function listed_notListed()
    {
        $data['getlistedData'] = $this->ListedNotListedModel->GetListedData();

        return view('Reports/listed_notListed', $data);
    }
    public function get_listed_notlisted()
    {
       
        $data['form_dt'] = $this->request->getGet('from_dt');
        $data['to_dt'] = $this->request->getGet('to_dt');
        $data['main_conn'] = $this->request->getGet('mc');
        $data['purpose'] = $this->request->getGet('purpose');
        $data['get_result'] = $this->ListedNotListedModel->get_result($data['form_dt'], $data['to_dt'], $data['main_conn'], $data['purpose']);

        return view('Reports/get_listed_notlisted', $data);
    }
    public function elimination_statistics()
    {
        $data['get_result'] = $this->ListedNotListedModel->get_elimination_statistics();
        return view('Reports/elimination_statistics', $data);
    }
    public function elimination_statistics_details()
    {
        $eliminated =  $_GET['eliminated'];
        $data['no_of_times_deleted'] =  $eliminated;
        $type = $_GET['type'];
        $data['report_name'] = "Fresh ";
        if ($type == 'F') {
            $data['report_name'] = "Fresh ";
        }
        if ($type == 'O') {
            $data['report_name'] = "Old";
        }
        $data['get_result'] = $this->ListedNotListedModel->get_elimination_statistics_detail($eliminated, $type);
        return view('Reports/elimination_statistics_details', $data);
    }
    public function scrutiny_report()
    {
        return view('Reports/scrutiny_report');
    }
    public function get_scrutiny_details()
    {
        $data['dateFrom'] = date('Y-m-d', strtotime($this->request->getPost('dateFrom')));
        $data['dateTo'] = date('Y-m-d', strtotime($this->request->getPost('dateTo')));
        $data['result_array'] = $this->ListedNotListedModel->get_scrutiny_details($data['dateFrom'], $data['dateTo']);
        return view('Reports/get_scrutiny_details', $data);
    }
	
	public function get_scrutiny_matters(){
		$data['frm_dt'] = date('Y-m-d', strtotime($this->request->getPost('txt_frm_dt')));
		$data['to_dt'] = date('Y-m-d', strtotime($this->request->getPost('txt_to_dt')));
		$data['empid'] = $this->request->getPost('empid');
		$data['case'] = $this->request->getPost('detailfor');
		$data['result_array'] = $this->ListedNotListedModel->get_scrutiny_matters_details($data['frm_dt'], $data['to_dt'],$data['empid'],$data['case']);
		return view('Reports/get_scrutiny_matters_popup_details', $data);
	}
	
    public function pendency_reports($id, $reportType1 = null, $fromdate = null, $todate = null, $jcode = null)
    {
        $data = [];
        if ($id >= 1 and $id <= 4) {
            $data['reports'] = $this->pendency_reports_model->get_pendency($id);

            if ($id == 1)
                $data['app_name'] = 'JudgeWise';
            if ($id == 2)
                $data['app_name'] = 'CategoryWise';
            if ($id == 3)
                $data['app_name'] = 'JudgeWiseDetails';
            if ($id == 4)
                $data['app_name'] = 'CategoryWiseDetails';

            return view('Reports/pendency_report', $data);
        }
        if ($id == 5) {
            $data['reports'] = '';
            $data['app_name'] = '';
            $categoryCode = '';
            $groupCount = '';
            $matterType = '';
            $matterStatus = '';
            // pr($_POST);
            if ($_POST) {
                if (!empty($this->request->getPost('categoryCode')) && !empty($this->request->getPost('groupCount')) && !empty($this->request->getPost('matterType')) && !empty($this->request->getPost('matterStatus'))) {
                    $categoryCode = $this->request->getPost('categoryCode');
                    $groupCount = $this->request->getPost('groupCount');
                    $matterType = $this->request->getPost('matterType');
                    $matterStatus = $this->request->getPost('matterStatus');
                }
                //var_dump($categoryCode,$matterStatus,$matterType);
                $data['reports'] = $this->pendency_reports_model->get_pendency($id, $categoryCode, $groupCount, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $matterType, $matterStatus);
                $data['app_name'] = 'SubjectCategoryWiseGroupCount';
                $data['matterType'] = $matterType;
                $data['matterStatus'] = $matterStatus;
                $data['code'] = $categoryCode;
            }
            $data['forCategory'] = ''; 
            $data['status']  = '';
            $data['type']  = '';
            return view('Reports/SubjectCategorywithGroupCountReport', $data);
        }
        if ($id == 6) {
           
            $data['reports'] = '';
            $data['app_name'] = '';

            if ($_POST) {
                if (isset($_POST['from_date']) && isset($_POST['to_date'])) {
                    $from_date = date('Y-m-d', strtotime($this->request->getPost('from_date')));
                    $to_date = date('Y-m-d', strtotime($this->request->getPost('to_date')));
                } else {
                    $from_date = date('Y-m-d');
                    $to_date = date('Y-m-d');
                }

                $data['reports'] = $this->pendency_reports_model->get_pendency($id, NULL, NULL, $from_date, $to_date);
                $data['app_name'] = 'JudgeWiseMatterListedDisposal';
            }
          
          
            
            return view('ManagementReport/Pending/dataJudgeWiseMatterListedDisposal', $data);
        }
        if ($id == 7) {
            $data['reports'] = '';
            $data['app_name'] = 'AllReportUI';

            return view('Reports/AllReportsUI', $data);
        }
        if ($id == 8) {
            $data['reports'] = '';
            $data['app_name'] = 'JudgesWiseMattersListedAndDisposedDetailed';

            $data['reports'] = $this->pendency_reports_model->get_pendency($id, NULL, NULL, NULL, NULL, NULL, NULL, $fromdate, $todate, $reportType1, $jcode);
            $data['param'] = array($fromdate, $todate, $reportType1, $jcode);
            return view('Reports/MattersListedAndDisposedDetailedReport', $data);
        }
    }
}
