<?php
namespace App\Controllers\ManagementReports;
use App\Controllers\BaseController;
use App\Models\ManagementReport\Model_vcreport;
use setasign\Fpdi\Tcpdf\Tcpdf;
use setasign\Fpdi\Tcpdf\Fpdi;
use App\Libraries\Fpdf;
use App\Libraries\Mctable;

  

class VC_Report extends BaseController
{

    public $model_vcreport;
    
    function __construct(){
		helper('url');
        ini_set('memory_limit', '51200M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
        $this->model_vcreport = new Model_vcreport();
    }  
	
	function vc_details_reports(){
		return view('ManagementReport/VCReports/vc_details_reports');
	}
	
	
	function VCStats() {
			if(!empty($this->request->getPost())){
				$from_dt = $this->request->getPost('dateFrom');
			     $to_dt = $this->request->getPost('dateTo');
				 if (!empty($from_dt) && !empty($to_dt)) {
					$from_dt = date('Y-m-d', strtotime($from_dt));
					$to_dt = date('Y-m-d', strtotime($to_dt));
					session()->set('date_loader', ['from_dt' => $from_dt, 'to_dt' => $to_dt]);
				}
			}else{
				$session = session()->get('date_loader');
				if (!empty($session) && is_array($session)) {
					$from_dt = $session['from_dt'] ?? null;
					$to_dt = $session['to_dt'] ?? null;
				}
			}
		
		if($from_dt=='' && $to_dt==''){
			return redirect()->to(''); 
		}
		$this->get_vc_detailed_report($from_dt, $to_dt);
	}
	
	function get_vc_detailed_report($from_dt, $to_dt){
		  
		 $main_taken_up = $this->model_vcreport->getMainConnTakenup_matters($from_dt, $to_dt);
		 $main_disposal = $this->model_vcreport->getMainConnDisposal_matters($from_dt, $to_dt);
		 $total_judgment = $this->model_vcreport->get_total_judgment($from_dt, $to_dt);
		 
		 $IA_disposed = $this->model_vcreport->get_IA_disposed($from_dt, $to_dt);
		 $MA_Disposed= $this->model_vcreport->get_MA_disposed($from_dt, $to_dt);
         
		$slp_appeal_disposed = $this->model_vcreport->get_SLP_Appeals_disposed($from_dt, $to_dt);
		$writ_disposed = $this->model_vcreport->get_Writ_Petitions_disposed($from_dt, $to_dt);
		$transfer_pet_disposed = $this->model_vcreport->get_Transfer_Petitions_disposed($from_dt, $to_dt);
        
		
		$total_filing = $this->model_vcreport->get_total_filed($from_dt, $to_dt);
        
		$total_filing_SLP_Appeals = $this->model_vcreport->get_filing_SLP_Appeals($from_dt, $to_dt);
		$filing_IA = $this->model_vcreport->get_filing_IA($from_dt, $to_dt);
		$filing_MA = $this->model_vcreport->get_filing_MA($from_dt, $to_dt);
		
		$IA_total = isset($IA_disposed['total']) ? $IA_disposed['total'] : 0;
		$MA_total = isset($MA_Disposed['total']) ? $MA_Disposed['total'] : 0;
		$slp_total = isset($slp_appeal_disposed['total']) ? $slp_appeal_disposed['total'] : 0;
		$writ_total = isset($writ_disposed['total']) ? $writ_disposed['total'] : 0;
		$transfer_total = isset($transfer_pet_disposed['total']) ? $transfer_pet_disposed['total'] : 0;
		
		
		$pdf = new MCTABLE();

            $pdf->AddPage();
            $pdf->Ln(8);
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(0, 0, 'Data of Video Conferencing hearing matters ' . date('m-d-Y', strtotime($from_dt)) . ' to ' . date('m-d-Y', strtotime($to_dt)), 0, 1, 'C');
            $pdf->Ln(4);
            $pdf->SetFont('Arial', 'B', 13);

		//Main matters
            $pdf->Ln(7);
            $pdf->MultiCell(0, 8, 'A. Main and Connected Matters', 1, 'L');

            $end = 'Matters taken on board and disposed of through Video Conferencing (other than Review & Chamber matters';

            $pdf->MultiCell(0, 8, $end, 1, 'L');

            $pdf->SetWidths(array(70, 70, 50));
            $pdf->SetFont('Arial', '', 13);

            $pdf->Row(array('Main matter', 'Connected Matters', 'Total'), 1);
            $pdf->SetWidths(array(35, 35, 35, 35, 25, 25));
            $pdf->Row(array('Taken up', 'Disposed', 'Taken up', 'Disposed', 'Taken up', 'Disposed'), 1);
            $total_takenup = $main_taken_up[0]['total'] + $main_taken_up[1]['total'];
            $total_disposed = $main_disposal[0]['total'] + $main_disposal[1]['total'];
            $pdf->Row(array($main_taken_up[1]['total'], $main_disposal[1]['total'], $main_taken_up[0]['total'],
            $main_disposal[0]['total'], $total_takenup, $total_disposed), 1);

            //Chambers matters
            $pdf->Ln(7);
            $pdf->SetFont('Arial', 'B', 13);
            $end = 'B. Data relating to Chamber matters, Review Petitions and Registrar Courts';

            $pdf->MultiCell(0, 8, $end, 1, 'L');

            $pdf->SetWidths(array(70, 70, 50));
            $pdf->SetFont('Arial', '', 13);


            $pdf->Row(array('Chamber Matters', 'Review Petition', 'Matters before Registrar Court'), 1);
            $pdf->SetWidths(array(35, 35, 35, 35, 25, 25));
            $pdf->Row(array('Taken up', 'Disposed', 'Taken up', 'Disposed', 'Taken up', 'Disposed'), 1);
            //$pdf->Row(array($main_taken_up[0]['total'], $main_taken_up[1]['total'], $main_disposal[0]['total'], $main_disposal[1]['total']), 1);

            $pdf->Ln(7);
            $pdf->SetFont('Arial', 'B', 13);
            $end = 'C. Break-up of disposal';

            $pdf->MultiCell(0, 8, $end, 1, 'L');
            $pdf->SetFont('Arial', '', 13);
            $pdf->SetWidths(array(30, 30, 30, 30, 30, 40));
            $pdf->Row(array('Total Disposal (Other than Review, Registrar & Chamber Matters)', '	Total Judgments', '	Total IA/MA & Contempt Petitions disposed'
            , 'SLP + Appeals disposed', 'Writ Petitions disposed', '	Transfer Petitions Disposed'), 1);
            $pdf->Row(array(0, $total_judgment['total'], 'IA- ' . $IA_total . ' MA-' . $MA_total, $slp_total, $writ_total, $transfer_total), 1);
			
            $pdf->Ln(7);
            $pdf->SetFont('Arial', 'B', 13);
            $end = 'D. Status of filing of matters';

            $pdf->MultiCell(0, 8, $end, 1, 'L');
            $pdf->SetFont('Arial', '', 13);
            $pdf->SetWidths(array(30, 70));
            $pdf->Row(array('Total filings', '	Breakup'), 1);
            $pdf->SetWidths(array(30, 35, 35));
            $pdf->Row(array(' ', 'SLP/Appeals', '	Misc. Matters (IAs & MAs)'), 1);
            $pdf->Row(array($total_filing['total'],$total_filing_SLP_Appeals['total'] , 'IA- ' . $filing_IA['total'] . ' MA-' . $filing_MA['total']), 1);

            header('Content-Type: application/pdf');
			header('Content-Disposition: inline; filename="vc_details_reports.pdf"');
			header('Cache-Control: private, max-age=0, must-revalidate');
			$pdf->Output('I');
			exit();
	}
	
	
    
   
}