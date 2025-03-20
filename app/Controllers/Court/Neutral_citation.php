<?php

namespace App\Controllers\Court;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Court\Neutral_citation_model;
use App\Libraries\phpqrcode\Qrlib;
use App\Libraries\Fpdf;

class Neutral_citation extends BaseController
{
    public $model;
    public $diary_no;
    public $qrlib;
    public $Fpdf;

    function __construct()
    {

        $this->model = new Neutral_citation_model();
        $this->qrlib = new Qrlib();
        $this->Fpdf = new Fpdf();

        if (empty(session()->get('filing_details')['diary_no'])) {
            $uri = current_url(true);
            $getUrl = str_replace('/', '-', $uri->getPath());
            header('Location:' . base_url('Filing/Diary/search?page_url=' . base64_encode($getUrl)));
            exit();
            exit();
        } else {
            $this->diary_no = session()->get('filing_details')['diary_no'];
        }
    }

    public function index()
    {

        $diary_no = $this->diary_no;

        if ($this->request->getPost('diary_number')) {
            if ($this->request->getPost('insert')) {

                $file = $this->request->getFiles('file');
                $file_tmp = $file['file']->getTempName();

                $nc_number_already_generated = '';
                $check_digital_sign = $this->countStringInFile($file_tmp, 'adbe.pkcs7');

                if ($check_digital_sign == 0) {
                    $diary_number = $this->request->getPost('diary_number');
                    $getDetails = $this->model->getDetails($diary_number);

                    $active_casetype_id = $getDetails[0]['active_casetype_id'];
                    $active_fil_no = $getDetails[0]['active_fil_no'];
                    $active_reg_year = $getDetails[0]['active_reg_year'];
                    $pet_name = $getDetails[0]['pet_name'];
                    $res_name = $getDetails[0]['res_name'];
                    $dispose_order_date = date('Y-m-d', strtotime($this->request->getPost('date')));
                    $reg_no_display = $getDetails[0]['reg_no_display'];
                    $date = $this->request->getPost('date');
                    $judge = $this->request->getPost('judge');
                    $coram = implode(',', $judge);
                    $no_of_judges = sizeof($judge);
                    $judgment_pronounced_by = $this->request->getPost('judgment_by');
                    $year = date('Y', strtotime($this->request->getPost('date')));
                    $getNcNumber = $this->model->getNcNumber($year);

                    if (!empty($getNcNumber[0]['min_nc_no'])) {
                        $nc_number = $getNcNumber[0]['min_nc_no'];
                        $id = $getNcNumber[0]['id'];
                        $updateNcNumber = $this->model->updateNcNumber($id);
                    } else {
                        $getNcNumberForNeutralCitation = $this->model->getNcNumberForNeutralCitation($year);
                        $nc_number = !empty($getNcNumberForNeutralCitation[0]['max_nc_no']) ? $getNcNumberForNeutralCitation[0]['max_nc_no'] : 1;
                    }
                    $ucode = session()->get('login')['usercode'];
                    $nc_display = $year . 'INSC' . $nc_number;

                    $array = [
                        'diary_no' => $diary_number,
                        'nc_number' => $nc_number,
                        'nc_year' => $year,
                        'nc_display' => $nc_display,
                        'updated_by' => $ucode,
                        'updated_on' => date('Y-m-d H:i:s'),
                        'is_deleted' => 'f',
                        'active_casetype_id' => $active_casetype_id,
                        'active_fil_no' => $active_fil_no,
                        'active_reg_year' => $active_reg_year,
                        'pet_name' => $pet_name,
                        'res_name' => $res_name,
                        'dispose_order_date' => $dispose_order_date,
                        'reg_no_display' => $reg_no_display,
                        'order_type' => 'J',
                        'coram' => $coram,
                        'no_of_judges' => $no_of_judges,
                        'judgment_pronounced_by' => $judgment_pronounced_by,
                        'create_modify' => date("Y-m-d H:i:s"),
                        'updated_by_ip' => getClientIP()
                    ];

                    $data['insert'] = $this->db->table('public.neutral_citation')->insert($array);

                    if ($data['insert'] > 0) {
                        $this->embedQRJudgment($nc_display, $diary_number);
                    } else {
                        $check_already_inserted = $this->model->get_neutral_citation_details($diary_number);
                        if ($check_already_inserted) {
                            foreach ($check_already_inserted as $details) {
                                $nc_number_already_generated .= date('d-m-Y', strtotime($details['dispose_order_date'])) . ' ~' . $details['nc_display'] . ',';
                            }
                            $nc_number_already_generated = rtrim($nc_number_already_generated, ',');
                            $nc_generated_message = "Neutral Citation Numbers have been already generated for this diary number for following judgment dates:" . $nc_number_already_generated . ".Please check Downloads folder for QR embedded file!";
                            echo '<script>alert("' . $nc_generated_message . '")</script>';
                        } else {
                            echo '<script>alert("There is some error in generating Neutral Citation Number. Please contact Computer Cell.")</script>';
                            echo '<script>window.location.href=""</script>';
                        }
                    }
                } else {
                    echo '<script>alert("Digitally signed PDF are not allowed")</script>';
                    echo '<script>window.location.href=""</script>';
                }
            } else if ($this->request->getPost('update')) {
                $diary_number = $this->request->getPost('diary_number');
                $nc_display = $this->request->getPost('judgment_dates');

                $file_c = $this->request->getFiles('file_corrigendum');
                $file_c_tmp = $file_c['file_corrigendum']->getTempName();

                $check_digital_sign_corrigendum = $this->countStringInFile($file_c_tmp, 'adbe.pkcs7');

                if ($check_digital_sign_corrigendum == 0) {
                    $this->embedQRCorrigendum($nc_display, $diary_number);
                } else {
                    echo '<script>alert("Digitally signed PDF are not allowed")</script>';
                    echo '<script>window.location.href=""</script>';
                }
            }
        }

        $data['getJudges'] = $this->model->getJudges();
        $data['neutral_citaion_details'] = $this->model->get_neutral_citation_details($diary_no);
        $data['listed_date'] = $this->model->getListedDate($diary_no);
        $data['getDetails'] = $this->model->getDetails($diary_no);

        return view('Court/Neutral_citation/neutral_citation', $data);
    }

    public function embedQRJudgment($nc_display, $diary_no)
    {   
        $neutral_citation_number = $nc_display;
        $diary_number = $diary_no;

        $number = uniqid();
        // $desired_dir = "/home/ubuntu/reports/supremecourt/qr_judgments/" . $number;
        $desired_dir = "uploaded_documents/reports/qr_judgments/" . uniqid();

        if (isset($_FILES['file'])) {
            $file_name = $_FILES['file']['name'];
            $file_size = $_FILES['file']['size'];
            $file_tmp = $_FILES['file']['tmp_name'];
            $file_type = $_FILES['file']['type'];
            $fileNameWithoutExtension = pathinfo($file_name, PATHINFO_FILENAME);
            $fileNameWithoutExtensionList = explode('_', $fileNameWithoutExtension);
            $fileNameWithoutExtension = $fileNameWithoutExtensionList[0];
            $fileExtension = pathinfo($file_name, PATHINFO_EXTENSION);
            $diary_number_only = substr($diary_number, 0, -4);
            $diary_year = substr($diary_number, -4);
        
    
            if (is_dir($desired_dir) == false) {
                //echo "Inside to create directory: ".$desired_dir;
                mkdir("$desired_dir", 0755, true);
            }

            if (is_dir("$desired_dir/" . $file_name) == false) {

                move_uploaded_file($file_tmp, "$desired_dir/" . $number . ".pdf");
                $this->generateQR($neutral_citation_number, $number, $desired_dir);
            }
        }

        $data = file_get_contents($desired_dir . '/judgment_after_qr/' . $number . '.pdf'); //assuming my file is on localhost
        $name = $file_name;
        $this->delete_directory($desired_dir);
        force_download($name, $data);
    }

    function countStringInFile($file, $string)
    {
        $handle = fopen($file, 'r');
        $valid = 0; // init as 0
        while (($buffer = fgets($handle)) !== false) {
            if (strpos($buffer, $string) !== false) {
                $valid++;
            }
        }
        fclose($handle);
        return $valid;
    }

    private function generateQR($neutral_citation_number, $file_name, $desired_dir)
    {
        if (is_dir($desired_dir) == false) {
            mkdir($desired_dir, 0755, true);

        }
        if (is_dir($desired_dir . "/judgment_after_qr") == false) {
            mkdir($desired_dir . "/judgment_after_qr", 0777, true);
        }
        ini_set('display_errors', 1);
        $finalfile = $desired_dir . "/judgment_after_qr/" . $file_name . ".pdf";
        $file = $desired_dir . "/" . $file_name . "_qr.png";
        $qr_file_url = $desired_dir . "/" . $file_name . "_qr.pdf";
        $pdf_file = $desired_dir . "/" . $file_name . ".pdf";
        $ecc = 'L';
        $pixel_Size = 10;
        $frame_Size = 10;
        $this->qrlib->QRcodePng($neutral_citation_number, $file, $ecc, $pixel_Size, $frame_Size);
        $this->Fpdf->AddPage();
        $this->Fpdf->Image($file, $this->Fpdf->Getx() + 140, $this->Fpdf->GetY() - 5, 25.00); //For TOP right
        $this->Fpdf->Output($qr_file_url, 'F');

        $nm_s =  shell_exec('pdftk ' . $pdf_file . ' dump_data | grep NumberOfPages');
        $NumberOfPages = !empty($nm_s) ? str_replace('NumberOfPages: ', '', $nm_s) : 0;
        if ($NumberOfPages == 1) {
            shell_exec("pdftk " . $pdf_file . " background " . $qr_file_url . " output " . $finalfile . " ");
        } else {
            $page_one_file_name = $desired_dir . "/judgment_after_qr/page_1_" . $file_name . ".pdf";
            $page_one_file_name_with_qr = $desired_dir . "/judgment_after_qr/page_1_with_qr_" . $file_name . ".pdf";
            shell_exec("pdftk " . $pdf_file . " cat 1 output " . $page_one_file_name . " ");
            shell_exec("pdftk " . $page_one_file_name . " background " . $qr_file_url . " output " . $page_one_file_name_with_qr . " ");
            shell_exec("pdftk A=" . $page_one_file_name_with_qr . " B=" . $pdf_file . " cat A1 B2-end output " . $finalfile . " ");

            if (is_dir($page_one_file_name != false)) {
                unlink($page_one_file_name);
            }

            if (is_dir($page_one_file_name != false)) {
                unlink($page_one_file_name_with_qr);
            }
        }
        move_uploaded_file($pdf_file, $desired_dir . "/" . "judgment_after_qr/" . $file_name . ".pdf");
    }

    public function embedQRCorrigendum($nc_display, $diary_no)
    {                   // generate QR Code on new Judgment
        $neutral_citation_number = $nc_display;
        $diary_number = $diary_no;

        $number = uniqid();
        $desired_dir = "uploaded_documents/reports/qr_judgments/" . $number;
        //$desired_dir = "../reports/supremecourt/qr_judgments/Corrigendum".uniqid();
        if (!empty($this->request->getFiles('file_corrigendum'))) {

            $getFile = $this->request->getFiles('file_corrigendum');

            $file_name = $getFile['file_corrigendum']->getName();
            $file_size = $getFile['file_corrigendum']->getSize();
            $file_tmp = $getFile['file_corrigendum']->getTempName();
            $file_type = $getFile['file_corrigendum']->getMimeType();

            $fileNameWithoutExtension = pathinfo($file_name, PATHINFO_FILENAME);
            $fileNameWithoutExtensionList = explode('_', $fileNameWithoutExtension);
            $fileNameWithoutExtension = $fileNameWithoutExtensionList[0];
            $fileExtension = pathinfo($file_name, PATHINFO_EXTENSION);
            $diary_number_only = substr($diary_number, 0, -4);
            $diary_year = substr($diary_number, -4);

            if (is_dir($desired_dir) == false) {
                mkdir("$desired_dir", 0755, true); // Create directory if it does not exist
            }

            if (is_dir("$desired_dir/" . $file_name) == false) {
                move_uploaded_file($file_tmp, "$desired_dir/" . $number . ".pdf");
                $this->generateQRCorrigendum($neutral_citation_number, $number, $desired_dir);
            }
        }

        $data = file_get_contents($desired_dir . '/corrigendum_after_qr/' . $number . '.pdf'); //assuming my file is on localhost
        $name = $file_name;
        $this->delete_directory($desired_dir);
        force_download($name, $data);
    }

    private function generateQRCorrigendum($neutral_citation_number, $file_name, $desired_dir)
    {

        if (is_dir($desired_dir) == false) {
            mkdir("$desired_dir", 0755, true); // Create directory if it does not exist

        }

        if (is_dir($desired_dir . "/corrigendum_after_qr") == false) {
            mkdir($desired_dir . "/corrigendum_after_qr", 0755, true);
        }

        $finalfile = $desired_dir . "/corrigendum_after_qr/" . $file_name . ".pdf";
        $file = $desired_dir . "/" . $file_name . "_qr.png";
        $qr_file_url = $desired_dir . "/" . $file_name . "_qr.pdf";
        $pdf_file = $desired_dir . "/" . $file_name . ".pdf";
        $ecc = 'L';
        $pixel_Size = 10;
        $frame_Size = 5; //increase or decrease QR frame size

        // Generates QR Code and Stores it in directory given
        $this->qrlib->QRcodePng($neutral_citation_number, $file, $ecc, $pixel_Size, $frame_Size);

        $this->Fpdf->AddPage();

        $this->Fpdf->SetFont('Helvetica');

        $this->Fpdf->Write(40, $neutral_citation_number); //align text neutral citation number top to bottom

        $this->Fpdf->Image($file, $this->Fpdf->Getx() - 26, $this->Fpdf->GetY() - 7, 25.00);
        //For bottom right

        $this->Fpdf->Output($qr_file_url, 'F');

        $nm_s =  exec('pdftk ' . $pdf_file . ' dump_data | grep NumberOfPages');
        $NumberOfPages = str_replace('NumberOfPages: ', '', $nm_s);
        if ($NumberOfPages == 1) {
            exec("pdftk " . $pdf_file . " background " . $qr_file_url . " output " . $finalfile . " ");
        } else {
            $page_one_file_name = $desired_dir . "/corrigendum_after_qr/page_1_" . $file_name . ".pdf";
            $page_one_file_name_with_qr = $desired_dir . "/corrigendum_after_qr/page_1_with_qr_" . $file_name . ".pdf";
            exec("pdftk " . $pdf_file . " cat 1 output " . $page_one_file_name . " ");
            exec("pdftk " . $page_one_file_name . " background " . $qr_file_url . " output " . $page_one_file_name_with_qr . " ");
            exec("pdftk A=" . $page_one_file_name_with_qr . " B=" . $pdf_file . " cat A1 B2-end output " . $finalfile . " ");
            unlink($page_one_file_name);
            unlink($page_one_file_name_with_qr);
        }
    }

    private function delete_directory($folderName)
    {
        helper('filesystem');
        if (is_dir($folderName)) {
            delete_files($folderName, true); // Delete files into the folder
            rmdir($folderName); // Delete the folder
            return true;
        }
        return false;
    }

    public function delete()
    {
        // pr($_SESSION);
        $diary_no = $this->diary_no;

        $get_data = $this->model->getDetailsDispose($diary_no);
        $data['neutral_citaion_details'] = $this->model->get_neutral_citation_details($diary_no);
        // pr($get_data);
        if ($diary_no != '') {
            $data['getDetails'] = !empty($get_data) ? $get_data : ['empty'];
        } else {
            $data['getDetails'] = [];
        }

        $data['ucode'] = session()->get('login')['usercode'];

        return view('Court/Neutral_citation/neutral_citation_delete', $data);
    }


    public function delete_NeutralCitation()
    {

        $case_no = $this->request->getPost('case_number');
        $diary_number = $this->request->getPost('diary_number');
        $date_judgment = $this->request->getPost('date_judgment');
        $reason = $this->request->getPost('reason');
        $ucode = $this->request->getPost('ucode');
        $ip_address = getClientIP();

        if ($diary_number != '' && $case_no != '' && $date_judgment != '') {
            $getDeletedDetails = $this->model->deleteandUpdate($diary_number, $case_no, $date_judgment, $reason, $ip_address, $ucode);
            return $getDeletedDetails;
        } else {
            return 2;
        }
    }
}