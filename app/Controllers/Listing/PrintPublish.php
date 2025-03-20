<?php

namespace App\Controllers\Listing;

use App\Controllers\BaseController;

use App\Models\Menu_model;
use CodeIgniter\Controller;
use CodeIgniter\Model;
use App\Models\Filing\AdvocateModel;
use App\Models\Casetype;
//use App\Models\Entities\Main;
use App\Models\Listing\CaseAdd;
use App\Models\Listing\CaseDrop;
use App\Models\Listing\Heardt;
use App\Models\Listing\Roster;
use App\Models\Listing\AdvanceAllocated;
use App\Models\Listing\AdvanceClPrinted;
use Mpdf\Mpdf;

class PrintPublish extends BaseController
{

    public $diary_no;
    public $Casetype;
    public $CaseAdd;
    public $CaseDrop;
    public $AdvanceAllocated;
    public $heardtModel;
    public $AdvanceClPrinted;
    public $request;



    function __construct()
    {
        $this->Casetype = new Casetype();
        $this->CaseAdd = new CaseAdd();
        $this->CaseDrop = new CaseDrop();
        $this->AdvanceAllocated = new AdvanceAllocated();
        $this->heardtModel = new Heardt();
        $this->AdvanceAllocated = new AdvanceAllocated();
        $this->AdvanceClPrinted = new AdvanceClPrinted();
        $request = \Config\Services::request();
        ini_set('memory_limit', '4024M');
    }


    // Drop not menu


    public function cl_print_advance()
    {
        //$data['listing_dates'] = $this->AdvanceAllocated->getUpcomingDates();
        // $data['benches'] = $this->heardtModel->getBenches();
        $data['dates'] = $this->AdvanceAllocated->getFutureDates();
        return view('Listing/advance_list/cl_print_advance', $data);
    }





    public function get_cause_list_advance()
    {
        $request = \Config\Services::request();
        $list_dt = date('Y-m-d', strtotime($request->getPost('list_dt')));
        $mainhead = $request->getPost('mainhead');
        $board_type = $request->getPost('board_type');
        $roster_id = $request->getPost('roster_id');

        session()->set('advance_json_mainhead', $mainhead);
        session()->set('json_advance_board_type', $board_type);
        session()->set('json_advance_list_dt', $list_dt);

        $data = [];
        $board_type_in = ($board_type === '0') ? '' : " AND h.board_type = '$board_type' ";




        $data['list_dt'] = $list_dt;
        $data['board_type_in'] = $board_type_in;

        $data['mainhead'] = $mainhead;

        $data['subheading'] = ""; // not use in php
        $data['jcd_rp'] = ""; // not use in php
        $data['part_no'] = ""; // not use in php
        $data['print_mainhead'] = ""; // not use in php

        $data['board_type'] = $board_type;


        $data['model'] = $this->AdvanceAllocated;

        return view('Listing/advance_list/advance_list_print_view', $data);
    }




    public function reshuffle_advance()
    {
        $request = \Config\Services::request();
        $list_dt = $request->getPost('list_dt');
        $board_type = $request->getPost('board_type');
        $from_cl_no = $request->getPost('from_cl_no');

        if ($this->AdvanceAllocated->reshuffleAdvance($list_dt, $board_type, $from_cl_no)) {
            echo "Reshuffled Successfully";
        } else {
            echo "Error: Reshuffling Failed";
        }
    }

    public function call_reshuffle_function_advance()
    {
        $request = \Config\Services::request();

        $list_dt = $request->getPost('list_dt');
        $board_type = $request->getPost('board_type');
        $from_cl_no = $request->getPost('from_cl_no');


        $result = $this->AdvanceAllocated->reshuffleAdvance($list_dt, $board_type, $from_cl_no);
        if ($result == 1) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Reshuffled Successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error: Reshuffling Failed'
            ]);
        }
    }




    public function cl_print_save_advance()
    {
        helper(['form', 'url']);
        $request = \Config\Services::request();

        $session = session();
        $ucode = $this->session->get('login')['usercode'];
        $list_dt = $request->getPost('list_dt');
        $mainhead = $request->getPost('mainhead');
        $board_type = $request->getPost('board_type');
        $prtContent = $request->getPost('prtContent');


        $now = date('Y-m-d H:i:s');

        $part_no = 1;
        //$cntt = base64_encode($prtContent);
        //        $pdf_cont = str_replace("scilogo.png", "/home/judgment/cl/scilogo.png", $prtContent);

        $check = $this->AdvanceClPrinted->isAlreadyPrinted($list_dt, $board_type, $part_no);
        if (!empty($check)) {

            return "Already Printed.";
        } else {
            $chk1 = $this->AdvanceClPrinted->insertPrintedCauseList($list_dt, $part_no, $board_type, $ucode, $now);


            if ($chk1 == 1)
            {
               // echo $this->AdvanceAllocated->eliminate_advance_auto($list_dt);die; // REmove  line


               

                $file_path = $mainhead . "_" . $board_type;
               
                $path_dir = WRITEPATH . '/home/judgment/cl/advance/' . $list_dt . '/';

                // pr('mkdir check UAT Serverr');
                if (!is_dir($path_dir)) {
                    mkdir($path_dir, 0777, true);
                }

                $data_file = $path_dir . $file_path . ".html";
                $data_file1 = $path_dir . $file_path . ".pdf";
                
                if (file_exists($data_file))
                {
                    unlink($data_file);
                }

                file_put_contents($data_file, $prtContent);


                // Generate PDF using Mpdf
                $mpdf = new \Mpdf\Mpdf();
                $mpdf->SetDisplayMode('fullpage');
                $mpdf->showImageErrors = true;
                $mpdf->shrink_tables_to_fit = 0;
                $mpdf->keep_table_proportions = true;
                $mpdf->WriteHTML($prtContent);
                $mpdf->Output($data_file1, 'F');
                //echo $this->AdvanceAllocated->eliminate_advance_auto($list_dt);
                return $this->response->setJSON(['message' => 'List Ported/Published Successfully.']);
            } else {
                return $this->response->setJSON(['message' => 'List Not Ported/Published.']);
            }
        }
    }





    private function validateDate($date)
    {
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }


    private function generatePdf($mainhead, $board_type, $list_dt, $pdf_cont)
    {
        $file_path = $mainhead . "_" . $board_type;
        $path_dir = "/home/judgment/cl/advance/$list_dt/";

        if (!file_exists($path_dir)) {
            if (!mkdir($path_dir, 0777, true) && !is_dir($path_dir)) {
                echo "Failed to create directories...";
                return false;
            }
        }

        $data_file = $path_dir . $file_path . ".html";
        $data_file1 = $path_dir . $file_path . ".pdf";
        if (file_exists($data_file)) {
            unlink($data_file);
        }

        touch("$data_file");
        $fp = fopen($data_file, "w+");
        fwrite($fp, $pdf_cont);
        //exec('html2pdf '.$data_file. ' '.$data_file1, $output1, $return1);
        header("Content-type: image/png");
        include '/var/www/html/supreme_court/MPDF60/mpdf.php';
        ob_start();  // start output buffering
        include $data_file;
        $content = ob_get_clean(); // get content of the buffer and clean the buffer

        $mpdf = new mPDF();
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->showImageErrors = true;
        $mpdf->shrink_tables_to_fit = 0;
        $mpdf->keep_table_propotions = true;
        $mpdf->WriteHTML($content);
        $mpdf->Output($data_file1); // output as inline content

        fclose($fp);
    }
}
