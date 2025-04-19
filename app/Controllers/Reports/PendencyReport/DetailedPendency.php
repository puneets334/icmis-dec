<?php
namespace App\Controllers\Reports\PendencyReport;

use App\Controllers\BaseController;
use App\Models\Reports\PendencyReport\DetailedPendencyModel;
use App\Libraries\MCTABLE;

class DetailedPendency extends BaseController
{
  public $DetailedPendencyModel;

	function __construct()
  {
    $this->DetailedPendencyModel = new DetailedPendencyModel();
  }

  public function current_pendency_report($id = null,$reportType1 = null)
  {
    $mainReportID = 3;
    $subReportID = 4;

    $data['reportsAE'] = '';
    $data['reportsB'] = '';
    $data['reportsD'] = '';
    $data['reportsC1'] = '';
    $data['reportsC2'] = '';
    $data['app_name'] = '';

    if(isset($_POST['from_date'])&& isset($_POST['to_date']))
    {
      $from_date = date('Y-m-d', strtotime($_POST['from_date']));
      $to_date = date('Y-m-d', strtotime($_POST['to_date']));
    }
    else
    {
      $from_date = date('Y-m-d');
      $to_date = date('Y-m-d');
    }

    $data['reportsAG'] = $this->DetailedPendencyModel->getCurrentPendency($mainReportID,$subReportID,$from_date,$to_date,1,null);
    $data['reportsB'] = $this->DetailedPendencyModel->getCurrentPendency($mainReportID,$subReportID,$from_date,$to_date,2,null);
    $data['reportsC'] = $this->DetailedPendencyModel->getCurrentPendency($mainReportID,$subReportID,$from_date,$to_date,3,null);
    $data['reportsD'] = $this->DetailedPendencyModel->getCurrentPendency($mainReportID,$subReportID,$from_date,$to_date,4,null);
    $data['reportsE1'] = $this->DetailedPendencyModel->getCurrentPendency($mainReportID,$subReportID,$from_date,$to_date,5,null);
    $data['reportsE2'] = $this->DetailedPendencyModel->getCurrentPendency($mainReportID,$subReportID,$from_date,$to_date,6,null);
    $data['reportsTotal'] = $this->DetailedPendencyModel->getCurrentPendency($mainReportID,$subReportID,$from_date,$to_date,20,null);

    $data['app_name'] = 'CurrentPendency';
    return view('Reports/pendencyReport/AllReportsUI', ['data' => $data]);
	}

	public function detailed_pendency_report($id,$reportType1=null)
	{
    $mainReportID = 3;
    $subReportID = 4;
		if($id == 34)
		{
    	if($reportType1 == 'A' || $reportType1 == 'B' ||  $reportType1 == 'C' || $reportType1 == 'G' || $reportType1 == 'D' || $reportType1 == 'E1' || $reportType1 == 'E2')
    	{
        $data['reports']='';
        $data['app_name']='CurrentPendencyDetailed';

        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');
        $data['param']=array($mainReportID,$subReportID,$from_date,$to_date,$reportType1);
        if($reportType1=='A')
            $data['reports'] = $this->DetailedPendencyModel->getCurrentPendency($mainReportID,$subReportID,$from_date,$to_date,7,$reportType1);
        elseif($reportType1=='B')
            $data['reports'] = $this->DetailedPendencyModel->getCurrentPendency($mainReportID,$subReportID,$from_date,$to_date,8,$reportType1);
        elseif($reportType1=='C')
            $data['reports'] = $this->DetailedPendencyModel->getCurrentPendency($mainReportID,$subReportID,$from_date,$to_date,11,$reportType1);
        elseif($reportType1=='D')
            $data['reports'] = $this->DetailedPendencyModel->getCurrentPendency($mainReportID,$subReportID,$from_date,$to_date,10,$reportType1);
        elseif($reportType1=='G')
            $data['reports'] = $this->DetailedPendencyModel->getCurrentPendency($mainReportID,$subReportID,$from_date,$to_date,9,$reportType1);

        elseif($reportType1=='E1')
            $data['reports'] = $this->DetailedPendencyModel->getCurrentPendency($mainReportID,$subReportID,$from_date,$to_date,12,$reportType1);
        elseif($reportType1=='E2')
            $data['reports'] = $this->DetailedPendencyModel->getCurrentPendency($mainReportID,$subReportID,$from_date,$to_date,13,$reportType1);
          
        return view('Reports/pendencyReport/currentPendencyDetails', $data);
			}
		}
		elseif($id==35)
    {
      if($reportType1=='F')
      {
        $data['reports']='';
        $data['app_name']='FClassification';

        $from_date=date('Y-m-d');
        $to_date = date('Y-m-d');

        $data['reportsF11'] = $this->DetailedPendencyModel->getCurrentPendency($mainReportID,$subReportID,$from_date,$to_date,14,$reportType1);
        $data['reportsF12'] = $this->DetailedPendencyModel->getCurrentPendency($mainReportID,$subReportID,$from_date,$to_date,15,$reportType1);
        $data['reportsF21'] = $this->DetailedPendencyModel->getCurrentPendency($mainReportID,$subReportID,$from_date,$to_date,16,$reportType1);
        $data['reportsF22'] = $this->DetailedPendencyModel->getCurrentPendency($mainReportID,$subReportID,$from_date,$to_date,17,$reportType1);

       return view('Reports/pendencyReport/FClassification', [ 'data' => $data]);
      }
    }
    elseif($id==36)
    {
      $data['reports'] = '';
      $data['app_name'] = 'FClassificationDetailed';

      $from_date = date('Y-m-d');
      $to_date = date('Y-m-d');
      $data['param'] = array($mainReportID,$subReportID,$from_date,$to_date,$reportType1);
      if($reportType1 == 'F11')
          $data['reports'] = $this->DetailedPendencyModel->getCurrentPendency($mainReportID,$subReportID,$from_date,$to_date,18,$reportType1);
      elseif($reportType1 == 'F12')
          $data['reports'] = $this->DetailedPendencyModel->getCurrentPendency($mainReportID,$subReportID,$from_date,$to_date,19,$reportType1);
      elseif($reportType1 == 'F21')
          $data['reports'] = $this->DetailedPendencyModel->getCurrentPendency($mainReportID,$subReportID,$from_date,$to_date,21,$reportType1);
      elseif($reportType1 =='F22')
          $data['reports'] = $this->DetailedPendencyModel->getCurrentPendency($mainReportID,$subReportID,$from_date,$to_date,22,$reportType1);
      return view('Reports/pendencyReport/fClassificationDetails', $data);
    }
	}

	public function get_pendency()
	{
		return view('Reports/pendencyReport/get_pendency');
	}

	public function pendency()
	{
		$result = $this->DetailedPendencyModel->pendency();
		return $this->response->setJSON([
      'status' => true,
      'data' => $result,
    ]);
	}

	public function details()
	{
		$data = $this->DetailedPendencyModel->details();
		return view('Reports/pendencyReport/highCourtCaseWisePendencyDetails', $data);
	}

	public function hc_not_before_required()
	{
		return view('Reports/pendencyReport/hc_not_before_required');
	}

	public function hcNotBeforeRequiredGet()
	{
		$data['result'] = $this->DetailedPendencyModel->hc_not_before_required_get();
    return view('Reports/pendencyReport/hc_not_before_required_get', $data);
	}

	public function Disposal_AsPer_Orderdate()
  {
    $data['case_result1'] = '';
    $data['case_result2'] = '';
    $data['app_name'] = 'Total Disposal as per Updation';
    $request = service('request');
    //$data['from_date'] = date('d-m-Y');
    $data['to_date'] = date('d-m-Y');
    if ($request->getMethod() === 'post') {  
      $frm_date = date('Y-m-d', strtotime($request->getPost('from_date')));
      $to_date = date('Y-m-d', strtotime($request->getPost('to_date')));
      $result_array1 = $this->DetailedPendencyModel->getDisposal_AsPer_OrderDate($frm_date, $to_date, 1);
      $result_array2 = $this->DetailedPendencyModel->getDisposal_AsPer_OrderDate($frm_date, $to_date, 2);
      $data['case_result1'] = $result_array1;
      $data['case_result2'] = $result_array2;
      $data['opening_date'] = strtotime ( '-1 day' , strtotime ( $frm_date ));
      $data['from_date'] = $request->getPost('from_date');
      $data['to_date'] = $request->getPost('to_date');
    }

    return view('Reports/pendencyReport/total_disposeMatters_orderDate',$data);
  }

  public function Disposal_AsPer_Order_Details($from_date=null,$to_date=null,$id=null)
  {
    $data['case_result'] = '';
    $from_date = date('Y-m-d', strtotime($from_date));
    $to_date = date('Y-m-d', strtotime($to_date));
    if($id == 3)
    {
      $data['app_name'] = 'Institution';

      $result_array = $this->DetailedPendencyModel->getDisposal_AsPer_OrderDate($from_date, $to_date,$id);
      $data['case_result'] = $result_array;
      $data['param']=array($from_date,$to_date,$id);
      return view('Reports/pendencyReport/institution_details',$data);
    }
    if($id == 4)
    {
      $data['app_name']='Un-registered Diary No. disposal';
      $result_array = $this->DetailedPendencyModel->getDisposal_AsPer_OrderDate($from_date, $to_date,$id);
      $data['case_result'] = $result_array;
      $data['param']=array($from_date,$to_date,$id);
      return view('Reports/pendencyReport/disposal_details',$data);
    }
    if($id == 5)
    {
      $data['app_name']='Total Disposal';
      $result_array = $this->DetailedPendencyModel->getDisposal_AsPer_OrderDate($from_date, $to_date,$id);
      $data['case_result'] = $result_array;
      $data['param']=array($from_date,$to_date,$id);
      return view('Reports/pendencyReport/disposal_details',$data);
    }
  }
}
?>
