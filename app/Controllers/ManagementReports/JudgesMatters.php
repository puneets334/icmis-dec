<?php

namespace App\Controllers\ManagementReports;

use App\Controllers\BaseController;
use App\Models\ManagementReport\NoDaCodeModel;
use App\Models\ManagementReport\VCModel;
use App\Libraries\Mctable;

class JudgesMatters extends BaseController
{
    public $NoDaCodeModel;
    public $Model_diary;

    function __construct()
    {
        // set_time_limit(10000000000);
        // ini_set('memory_limit', '-1');
        // $this->NoDaCodeModel = new NoDaCodeModel();
    }

    public function index()
    {
        $VC_model = new VCModel();

        $data['judge_list'] = $VC_model->get_judges_list();

        return view('ManagementReport/Reports/JudgesMattersReport', $data);
    }

    function judges_matter_list()
    {
        $request = \Config\Services::request();

        if ($request->getPost('judges_list') != 0) {

            $j_details = explode('|', $_POST['judges_list']);

            $j_code = $j_details[0]; //code
            $j_name = $j_details[1]; //name


            $this->showMatterReport($j_code, $j_name);
        } else {
            echo "No Data Found.";
            exit(0);
        }
    }

    public function judges_matter_list_ajax()
{
    $request = \Config\Services::request();

    if(!empty($this->request->getPost())){
        $j_details = explode('|', $request->getPost('judges_list'));  
        $j_code = $j_details[0];
         $j_name = $j_details[1];
       
         if (!empty($j_code) && !empty($j_name)) {
            
            session()->set('date_loader_report', ['j_code' => $j_code, 'j_name' => $j_name]);
        }
    }else{
        $session = session()->get('date_loader_report');
        if (!empty($session) && is_array($session)) {
            $j_name = $session['j_name'] ?? null;
            $j_code = $session['j_code'] ?? null;
        }
    }

if($j_name=='' && $j_code==''){
    return redirect()->to(''); 
}
$this->showMatterReport($j_code, $j_name);





    // if ($request->getPost('judges_list') != 0) {
    //     $j_details = explode('|', $request->getPost('judges_list'));       
        
    //     if (count($j_details) >= 2) {
    //         $j_code = $j_details[0];
    //         $j_name = $j_details[1];
    //         session()->set('date_loader_report', ['j_code' => $j_code, 'j_name' => $j_name]);
            
    //         $this->showMatterReport($j_code, $j_name);
    //     }
    //     else{
    //         $session = session()->get('date_loader_report');
    //         if (!empty($session) && is_array($session)) {
    //             $j_code = $session['j_code'] ?? null;
    //             $j_code = $session['j_code'] ?? null;
    //         }
    //     }    
            
    // } else {
    //     $session = session()->get('date_loader_report');
    //         if (!empty($session) && is_array($session)) {
    //             $j_code = $session['j_code'] ?? null;
    //             $j_code = $session['j_code'] ?? null;
    //         }
    //     return $this->response->setStatusCode(400)->setBody("No Data Found.");
    // }
}

    function showMatterReport($j_code, $j_name)
    {
        
        if ($j_code != '') {

            $VC_model = new VCModel();

            $judge_DOA_AOR = $VC_model->get_judges_DOA_AOR($j_code);
            $single_bench = $VC_model->single_bench($j_code);
            $divison_bench = $VC_model->division_bench($j_code);
            $five = $VC_model->five($j_code);
            $six = $VC_model->six($j_code);
            $seven = $VC_model->seven($j_code);
            $eight = $VC_model->eight($j_code);
            $nine = $VC_model->nine($j_code);
            $ten = $VC_model->ten($j_code);
            $eleven = $VC_model->eleven($j_code);
            $tweleve = $VC_model->tweleve($j_code);
            $thirteen_total_disposed = $VC_model->thirteen_total_disposed($j_code);
            $thirteen_disposed_by_lordship = $VC_model->thirteen_disposed_by_lordship($j_code);           

            // $judge_DOA_AOR['appointment_date'] = '2024-06-14';
            // $judge_DOA_AOR['to_dt'] = '2024-10-10';

            $date_of_elevation = '';
            $date_of_retirement = '';

            $fourteen = [];
            $fifteen = [];
            if(!empty($judge_DOA_AOR['appointment_date']) && !empty($judge_DOA_AOR['to_dt'])) {
                
                $date_of_elevation = date('d-m-Y', strtotime($judge_DOA_AOR['appointment_date']));
                $date_of_retirement = date('d-m-Y', strtotime($judge_DOA_AOR['to_dt']));

                $fourteen = $VC_model->fourteen($j_code, $judge_DOA_AOR['appointment_date'], $judge_DOA_AOR['to_dt']);
                $fifteen = $VC_model->fifteen($j_code, $judge_DOA_AOR['appointment_date'], $judge_DOA_AOR['to_dt']);
            }

            $fourteen['total'] = (!empty($fourteen['total'])) ? $fourteen['total'] : 0;
            $fifteen['total'] = (!empty($fifteen['total'])) ? $fifteen['total'] : 0;

            $pdf = new MCTABLE();

            $pdf->AddPage();

            $pdf->SetFont('Arial', 'B', 15);
            $pdf->Cell(0, 0, 'SUPREME COURT OF INDIA', 0, 1, 'C');
            $pdf->Ln(5);
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(0, 0, '(Supreme Court Statistics)', 0, 1, 'C');
            $pdf->Ln(10);

            $pdf->Cell(0, 0, "Date:- " . date("jS \ F Y"), 0, 1, 'R');
            $pdf->Ln(4);
            $txt = "I am under directions of Ld. Registrar (J-I) to provide the following Statistical Information with respect to $j_name, Judge, Hon'ble Supreme Court of India: ";


            $pdf->MultiCell(0, 8, $txt, 0, 'L');

            $pdf->SetFont('Arial', '', 11);

            //Main matters
            $pdf->Ln(5);
            $pdf->SetWidths(array(10, 128, 50));

            $pdf->Row(array("1.", "Date of Elevation at Hon'ble Supreme Court of India ", @$date_of_elevation), 1);

            $pdf->Row(array("2.", "Date of Retirement from Hon'ble Supreme Court of India ", @$date_of_retirement), 1);
            
            $pdf->Row(array("3.", " No. of single Bench held by His Lordship ", @$single_bench['total']), 1);

            $pdf->Row(array("4.", " No. of Division Bench Held in which His Lordship was not presiding over the bench ", @$divison_bench['total']), 1);

            $pdf->Row(array("5.", " No. of Division Bench Held in which His Lordship was presiding over the bench ", @$five['total']), 1);

            $pdf->Row(array("6.", " No. of three Judge Bench held in which His Lordship was not presiding over the bench ", @$six['total']), 1);

            $pdf->Row(array("7.", " No. of three Judge Bench held in which His Lordship was presiding over the bench ", @$seven['total']), 1);

            $pdf->Row(array("8.", " No. of three Judge Constitution Bench held in which His Lordship was not presiding over the bench ", @$eight['total']), 1);

            $pdf->Row(array("9.", " No. of three Judge Constitution Bench held in which His Lordship was presiding over the bench ", @$nine['total']), 1);

            $pdf->Row(array("10.", " No. of Constitution Bench held in which His Lordship was not presiding over the bench ", @$ten['total']), 1);

            $pdf->Row(array("11.", " No. of Constitution Bench held in which His Lordship was presiding over the bench ", @$eleven['total']), 1);

            $pdf->Row(array("12.", " No. of Cases dealt with by His Lordship ", @$tweleve['total'] . " (as per ICMIS . Data available since 2017) "), 1);

            $pdf->Row(array("13.", " No. of Cases disposed of by His Lordship ", @$thirteen_total_disposed['total'] . " Total " . @$thirteen_disposed_by_lordship['total'] . " Disposed by his Lordship"), 1);

            $pdf->Row(array("14.", " No. of Judgments authored by His Lordship ", @$fourteen['total']), 1);

            $pdf->Row(array("15.", " Out of which how many were constitution bench judgments ", @$fifteen['total']), 1);

            $pdf->Ln(10);
            $pdf->Cell(0, 0, '(Ravi Shanti Bhushan)', 0, 1, 'R');
            $pdf->Ln(4);

            $pdf->Cell(0, 0, 'Branch Officer', 0, 1, 'R');
            $pdf->Ln(4);

            $pdf->Cell(0, 0, 'Supreme Court Statistics', 0, 1, 'R');

            $pdf->Ln(7);
            $pdf->Cell(0, 0, '(Computer Cell)', 0, 1, 'L');
            header('Content-Type: application/pdf');
			header('Content-Disposition: inline; filename="vc_reports.pdf"');
			header('Cache-Control: private, max-age=0, must-revalidate');
			$pdf->Output('I');
			exit();
        }
    }

    function showMatterReport_bkp($j_code, $j_name)
    {        
        
        if ($j_code != '') {

            $VC_model = new VCModel();

            $judge_DOA_AOR = $VC_model->get_judges_DOA_AOR($j_code);
            $single_bench = $VC_model->single_bench($j_code);
            $divison_bench = $VC_model->division_bench($j_code);
            $five = $VC_model->five($j_code);
            $six = $VC_model->six($j_code);
            $seven = $VC_model->seven($j_code);
            $eight = $VC_model->eight($j_code);
            $nine = $VC_model->nine($j_code);
            $ten = $VC_model->ten($j_code);
            $eleven = $VC_model->eleven($j_code);
            $tweleve = $VC_model->tweleve($j_code);
            $thirteen_total_disposed = $VC_model->thirteen_total_disposed($j_code);
            $thirteen_disposed_by_lordship = $VC_model->thirteen_disposed_by_lordship($j_code);           

            // $judge_DOA_AOR['appointment_date'] = '2024-06-14';
            // $judge_DOA_AOR['to_dt'] = '2024-10-10';

            $date_of_elevation = '';
            $date_of_retirement = '';

            $fourteen = [];
            $fifteen = [];
            if(!empty($judge_DOA_AOR['appointment_date']) && !empty($judge_DOA_AOR['to_dt'])) {
                
                $date_of_elevation = date('d-m-Y', strtotime($judge_DOA_AOR['appointment_date']));
                $date_of_retirement = date('d-m-Y', strtotime($judge_DOA_AOR['to_dt']));

                $fourteen = $VC_model->fourteen($j_code, $judge_DOA_AOR['appointment_date'], $judge_DOA_AOR['to_dt']);
                $fifteen = $VC_model->fifteen($j_code, $judge_DOA_AOR['appointment_date'], $judge_DOA_AOR['to_dt']);
            }

            $fourteen['total'] = (!empty($fourteen['total'])) ? $fourteen['total'] : 0;
            $fifteen['total'] = (!empty($fifteen['total'])) ? $fifteen['total'] : 0;

            $pdf = new MCTABLE();

            $pdf->AddPage();

            $pdf->SetFont('Arial', 'B', 15);
            $pdf->Cell(0, 0, 'SUPREME COURT OF INDIA', 0, 1, 'C');
            $pdf->Ln(5);
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(0, 0, '(Supreme Court Statistics)', 0, 1, 'C');
            $pdf->Ln(10);

            $pdf->Cell(0, 0, "Date:- " . date("jS \ F Y"), 0, 1, 'R');
            $pdf->Ln(4);
            $txt = "I am under directions of Ld. Registrar (J-I) to provide the following Statistical Information with respect to $j_name, Judge, Hon'ble Supreme Court of India: ";


            $pdf->MultiCell(0, 8, $txt, 0, 'L');

            $pdf->SetFont('Arial', '', 11);

            //Main matters
            $pdf->Ln(5);
            $pdf->SetWidths(array(10, 128, 50));

            $pdf->Row(array("1.", "Date of Elevation at Hon'ble Supreme Court of India ", @$date_of_elevation), 1);

            $pdf->Row(array("2.", "Date of Retirement from Hon'ble Supreme Court of India ", @$date_of_retirement), 1);
            
            $pdf->Row(array("3.", " No. of single Bench held by His Lordship ", @$single_bench['total']), 1);

            $pdf->Row(array("4.", " No. of Division Bench Held in which His Lordship was not presiding over the bench ", @$divison_bench['total']), 1);

            $pdf->Row(array("5.", " No. of Division Bench Held in which His Lordship was presiding over the bench ", @$five['total']), 1);

            $pdf->Row(array("6.", " No. of three Judge Bench held in which His Lordship was not presiding over the bench ", @$six['total']), 1);

            $pdf->Row(array("7.", " No. of three Judge Bench held in which His Lordship was presiding over the bench ", @$seven['total']), 1);

            $pdf->Row(array("8.", " No. of three Judge Constitution Bench held in which His Lordship was not presiding over the bench ", @$eight['total']), 1);

            $pdf->Row(array("9.", " No. of three Judge Constitution Bench held in which His Lordship was presiding over the bench ", @$nine['total']), 1);

            $pdf->Row(array("10.", " No. of Constitution Bench held in which His Lordship was not presiding over the bench ", @$ten['total']), 1);

            $pdf->Row(array("11.", " No. of Constitution Bench held in which His Lordship was presiding over the bench ", @$eleven['total']), 1);

            $pdf->Row(array("12.", " No. of Cases dealt with by His Lordship ", @$tweleve['total'] . " (as per ICMIS . Data available since 2017) "), 1);

            $pdf->Row(array("13.", " No. of Cases disposed of by His Lordship ", @$thirteen_total_disposed['total'] . " Total " . @$thirteen_disposed_by_lordship['total'] . " Disposed by his Lordship"), 1);

            $pdf->Row(array("14.", " No. of Judgments authored by His Lordship ", @$fourteen['total']), 1);

            $pdf->Row(array("15.", " Out of which how many were constitution bench judgments ", @$fifteen['total']), 1);

            $pdf->Ln(10);
            $pdf->Cell(0, 0, '(Ravi Shanti Bhushan)', 0, 1, 'R');
            $pdf->Ln(4);

            $pdf->Cell(0, 0, 'Branch Officer', 0, 1, 'R');
            $pdf->Ln(4);

            $pdf->Cell(0, 0, 'Supreme Court Statistics', 0, 1, 'R');

            $pdf->Ln(7);
            $pdf->Cell(0, 0, '(Computer Cell)', 0, 1, 'L');
            $pdf->Output('D');
        }
    }
}
