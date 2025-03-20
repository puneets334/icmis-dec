<?php
namespace App\Controllers\Reports\ILDStats;

use App\Controllers\BaseController;
use App\Models\Exchange\Matters_Listed;
use App\Models\Exchange\CauseListFileMovementModel;
use App\Models\Exchange\Transaction;
use App\Models\Exchange\Sql_Report;
use App\Models\Exchange\MovementOfDocumentModel;
use App\Models\Court\CourtMasterModel;
use App\Models\Reports\DefaultReports\DefaultReportsModel;
use App\Models\Reports\DepartmentWise\DepartmentWiseModel;
use App\Models\Reports\FilingMonitoring\FilingMonitoringModel;
use App\Models\Reports\ILDStats\Report_IPDModel;
use App\Libraries\MCTABLE;

class Report_IPD extends BaseController
{
	public $Matters_Listed;
  public $CauseListFileMovementModel;
  public $CourtMasterModel;
  public $Transaction;
  public $MovementOfDocumentModel;
  public $DefaultReportsModel;
  public $DepartmentWiseModel;
  public $FilingMonitoringModel;
  public $Report_IPDModel;

	function __construct()
  {   
    $this->Matters_Listed = new Matters_Listed();
    $this->CauseListFileMovementModel = new CauseListFileMovementModel();
    $this->CourtMasterModel = new CourtMasterModel();
    $this->Transaction = new Transaction();
    $this->MovementOfDocumentModel = new MovementOfDocumentModel();
    $this->DefaultReportsModel = new DefaultReportsModel();
    $this->DepartmentWiseModel = new DepartmentWiseModel();
    $this->FilingMonitoringModel = new FilingMonitoringModel();
    $this->Report_IPDModel = new Report_IPDModel();
  }

	public function index()
	{
		return view('Reports/ILDStats/reportIPD');
	}

	function get_IPD()
	{
		$from_date = $_REQUEST['from_date'];
		$to_date = $_REQUEST['to_date'];
		$this->show_IPD($from_date, $to_date);
	}

	function send_IPD_Mail()
	{
  	$condition=  "between '2022-11-09' and  ".'"'. date('Y-m-d',strtotime("-1 days")).'"' ;
		$data['registered']=$this->Report_IPDModel->get_registered_for_IDP_report($condition);
    $data['disp_vc_stats'] = $this->Report_IPDModel->disposal_Vc_Stats($condition);
    $data['list_vc_stats'] = $this->Report_IPDModel->Listed_Vc_Stats($condition);
    $data['filing'] = $this->Report_IPDModel->get_filed_for_IDP_Reprot($condition);
    $data['notice']= $this->Report_IPDModel->get_notice_count($condition);
    $this->load->view('Reports/send_IPD_Mail',$data);
  }

	function show_IPD($from_dt,$to_dt)
	{
		if ($from_dt != '' && $to_dt != '')
		{
			$condition = "BETWEEN '" . date('Y-m-d', strtotime($from_dt)) . "' AND '" . date('Y-m-d', strtotime($to_dt)) . "'";

			$disp_vc_stats = $this->Report_IPDModel->disposal_Vc_Stats($condition);
			$list_vc_stats = $this->Report_IPDModel->Listed_Vc_Stats($condition);
			$filing = $this->Report_IPDModel->get_filed_for_IDP_Reprot($condition);

			$pdf = new MCTABLE();

			$pdf->AddPage();

			$pdf->SetMargins(20, 44, 11.7);
			$pdf->SetFont('Arial', 'B', 16);
			$pdf->Cell(0, 0, 'SUPREME COURT OF INDIA', 0, 1, 'C');
			$pdf->Ln(8);
			$pdf->SetFont('Arial', '', 12);
			$pdf->Cell(0, 0, 'COMPUTER CELL', 0, 1, 'C');
			$pdf->Ln(14);
			$pdf->SetFont('Arial', 'B', 14);
			$pdf->Cell(0, 0, 'DATA OF FILED-LISTED-DISPOSED OFF MATTERS ', 0, 1, 'C');
			$pdf->Ln(8);
			$pdf->SetFont('Arial', '', 12);
			$pdf->Cell(0, 0,'[Period From : '. $from_dt . ' to ' . $to_dt.' ]', 0, 1, 'C');
			$pdf->Ln(4);
			$pdf->SetFont('Arial', 'B', 13);

			//Main matters
			$pdf->Ln(7);

			$pdf->SetFont('Arial', 'B', 13);
			$pdf->SetWidths(array(50, 50, 50), 1);
			$total_Listing = $list_vc_stats[0]['m_total']+$list_vc_stats[0]['r_total'];
			$total_disposed = $disp_vc_stats[0]['m_total']+$disp_vc_stats[0]['r_total'];
			$pdf->Row(array('Total Filing','Total Listing','Total Disposal'), 1);
			$pdf->SetFont('Arial', '', 13);
			$pdf->Row(array($filing[0]['total_filed'],$total_Listing,$total_disposed), 1);

			$pdf->Ln(14);
			$pdf->SetFont('Arial', 'B', 13);
			$pdf->Cell(0, 0, 'Note:-', 0, 1, 'L');
			$pdf->Ln(10);
			$pdf->SetFont('Arial', '', 13);
			$pdf->Cell(0, 0, 'Filing:- All matters which are Diarized between the given dates.', 0, 1, 'L');
			$pdf->Ln(10);
			$pdf->Cell(0, 0, 'Listing:-Total matters listed between the given dates. ', 0, 1, 'L');
			$pdf->Ln(8);
			$pdf->MultiCell(0, 6, "Disposal:- Matters which are ordered by Hon'ble Court to dispose between the given dates excluding recalled matters.", 0, 'J');
			$file_name = 'IPD_Report_'.strtotime(date('Y-m-d H:i:s')).'.pdf';
			// $pdf->Output('I');
			$pdf->Output('D', $file_name);
		}
	}
}
?>
