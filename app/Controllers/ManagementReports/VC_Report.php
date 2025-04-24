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
            $pdf->Cell(0, 0, 'Data of Video Conferencing hearing matters ' . date('d/m/Y', strtotime($from_dt)) . ' to ' . date('d/m/Y', strtotime($to_dt)), 0, 1, 'C');
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
			if(count($main_taken_up)!=0){
				$total_takenup = $main_taken_up[0]['total'] + $main_taken_up[1]['total'];
			}else{
				$total_takenup = 0;
			}
			
			if(count($main_disposal)!=0){
				if(count($main_disposal)== 1){
					$total_disposed = $main_disposal[0]['total'];
				}else{
				   $total_disposed = $main_disposal[0]['total'] + $main_disposal[1]['total'];
				}
				
			}else{
				$total_disposed = 0;
			}
			
			if(count($main_taken_up)!=0 AND count($main_disposal)!=0){
				if(count($main_disposal)== 1){
					$pdf->Row(array($main_taken_up[1]['total'], 0, $main_taken_up[0]['total'], $main_disposal[0]['total'], $total_takenup, $total_disposed), 1);
				}else{
			    	$pdf->Row(array($main_taken_up[1]['total'], $main_disposal[1]['total'], $main_taken_up[0]['total'], $main_disposal[0]['total'], $total_takenup, $total_disposed), 1);
             	}		
			}else{
				$pdf->Row(array(0, 0, 0, 0, $total_takenup, $total_disposed), 1);
			}
            
            

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
	
	
	function vc_reports_view(){
		return view('ManagementReport/VCReports/vc_reports_filter_page');
	}
	
	
	function getMainConn_matters() {
			if(!empty($this->request->getPost())){
				$from_dt = $this->request->getPost('dateFrom');
			    $to_dt = $this->request->getPost('dateTo');
				 if (!empty($from_dt) && !empty($to_dt)) {
					$from_dt = date('Y-m-d', strtotime($from_dt));
					$to_dt = date('Y-m-d', strtotime($to_dt));
					session()->set('date_loader_report', ['from_dt_report' => $from_dt, 'to_dt_report' => $to_dt]);
				}
			}else{
				$session = session()->get('date_loader_report');
				if (!empty($session) && is_array($session)) {
					$from_dt = $session['from_dt_report'] ?? null;
					$to_dt = $session['to_dt_report'] ?? null;
				}
			}
		
		if($from_dt=='' && $to_dt==''){
			return redirect()->to(''); 
		}
		$this->showVCReport($from_dt, $to_dt);
	}
	
	public function showVCReport($from_dt, $to_dt){
		
		$disp_vc_stats = $this->model_vcreport->disposal_Vc_Stats($from_dt, $to_dt);
		$list_vc_stats = $this->model_vcreport->Listed_Vc_Stats($from_dt, $to_dt);
		$bench_vc_stats = $this->model_vcreport->bench_Vc_Stats($from_dt, $to_dt);
		$filed_vc_stats = $this->model_vcreport->Filed_Vc_Stats($from_dt, $to_dt);
		$efiled_vc_stats=$this->model_vcreport->efiled_matters($from_dt, $to_dt);
		
		$list_vc_stats = $list_vc_stats ?? ['m_total' => 0, 'r_total' => 0];
		$disp_vc_stats = $disp_vc_stats ?? ['m_total' => 0, 'r_total' => 0];
		$filed_vc_stats =  isset($filed_vc_stats['total']) ? $filed_vc_stats : ['total' => 0];
		$efiled_vc_stats = isset($efiled_vc_stats['total'])? $efiled_vc_stats : ['total' => 0];
		$bench_vc_stats = isset($bench_vc_stats['total']) ? $bench_vc_stats : ['total' => 0];
	
		$pdf = new MCTABLE();

            $pdf->AddPage();
            $pdf->SetMargins(20, 44, 11.7);
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(0, 0, 'SUPREME COURT OF INDIA', 0, 1, 'C');
            $pdf->Ln(8);
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(0, 0, 'COMPUTER CELL', 0, 1, 'C');
            $pdf->Ln(14);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 0, 'STASTISTICAL DATA OF HEARING BY COURTS', 0, 1, 'C');
            $pdf->Ln(8);
            $pdf->SetFont('Arial', '', 10);
            $from_dt = $from_dt ?? '';  
			$to_dt = $to_dt ?? '';  
			$pdf->Cell(0, 0, '[Period From : ' . date('d/m/Y', strtotime($from_dt ?: 'now')) . ' to ' . date('d/m/Y', strtotime($to_dt ?: 'now')) . ' ]', 0, 1, 'C');
            $pdf->Ln(4);
            $pdf->SetFont('Arial', 'B', 13);


            $pdf->Ln(7);
            $pdf->MultiCell(0, 8, '1. Number of matters heard', 1, 'L');

            $pdf->SetWidths(array(70, 70, 38));
            $pdf->SetFont('Arial', '', 13);

            $pdf->Row(array('Miscellaneous', 'Regular', 'Total'), 1);

			$total = (!empty($list_vc_stats['m_total']) ? $list_vc_stats['m_total'] : 0) + (!empty($list_vc_stats['r_total']) ? $list_vc_stats['r_total'] : 0);
            $pdf->Row(array($list_vc_stats['m_total'],$list_vc_stats['r_total'], $total), 1);

           
            $pdf->Ln(7);
            $pdf->SetFont('Arial', 'B', 13);
            $end = '2. Number of matters Disposed of';

            $pdf->MultiCell(0, 8, $end, 1, 'L');

            $pdf->SetWidths(array(70, 70, 38));
            $pdf->SetFont('Arial', '', 13);

            $pdf->Row(array('Miscellaneous', 'Regular', 'Total'), 1);

            $total = (!empty($disp_vc_stats['m_total']) ? $disp_vc_stats['m_total'] : 0) + (!empty($disp_vc_stats['r_total']) ? $disp_vc_stats['r_total'] : 0);
            $pdf->Row(array($disp_vc_stats['m_total'],$disp_vc_stats['r_total'], $total), 1);

            $pdf->Ln(7);
            $pdf->SetFont('Arial', 'B', 13);
            $end = '3. Number of Cases Filed';
            $pdf->MultiCell(0, 8, $end, 1, 'L');
            $pdf->SetFont('Arial', '', 13);
            $pdf->SetWidths(array(70, 70,38));
            $pdf->Row(array('e-Filed', 'Counter filing', 'Total'), 1);

            $pdf->Row(array($efiled_vc_stats['total'],($filed_vc_stats['total'] -$efiled_vc_stats['total']),$filed_vc_stats['total']), 1);

            $pdf->Ln(7);
            $pdf->SetFont('Arial', 'B', 13);
            $end = '4. Total number of benches ';

            $pdf->MultiCell(0, 8, $end, 1, 'L');
            $pdf->SetFont('Arial', '', 13);
            $pdf->MultiCell(0, 8, (!empty($bench_vc_stats['total']) ? $bench_vc_stats['total'] : 0), 1, 'L');
            $pdf->Ln(7);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 0, 'Note:- Above statistics are generated from ICMIS and e-Filing database of website; ', 0, 1, 'C');
            $pdf->Ln(7);
            $pdf->Cell(0, 0, '       Include both Physical as well as Virtual Hearing Stats', 0, 1, 'C');
			
			header('Content-Type: application/pdf');
			header('Content-Disposition: inline; filename="vc_reports.pdf"');
			header('Cache-Control: private, max-age=0, must-revalidate');
			$pdf->Output('I');
			exit();
	}
	
    
   
}