<?php

namespace App\Controllers\Scanning\SupremeCourtScan;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Scanning\SupremeCourtScan\SupremeCourtScanModal;
use setasign\Fpdi\Fpdi;

use DateTime;


class SupremeCourtScanController extends BaseController
{

    protected $supremeCourt;

    public function __construct()
    {
        $this->supremeCourt = new SupremeCourtScanModal();
    }

    public function getCSRF()
    {
        return $this->response->setJSON([
            'csrf_token' => csrf_hash()
        ]);
    }

    public function hCDCIndexingReport()
    {
        return view('scanning/SupremeCourtScan/indexing_hc_dc_report_view'); // Load your view
    }

    public function scanFileView()
    {
        return view('Scanning/SupremeCourtScan/scan_file_view'); // Load your view
    }

    public function fetchDetails()
    {
        $request = service('request');
        $txt_frm_date = $request->getVar('txt_frm_date');
        $txt_to_date = $request->getVar('txt_to_date');
        $ddl_status = $request->getVar('ddl_status');

        $txt_frm_date = date('Y-m-d', strtotime($txt_frm_date));
        $txt_to_date = date('Y-m-d', strtotime($txt_to_date));
        $data = $this->supremeCourt->getIndexingReport($txt_frm_date, $txt_to_date, $ddl_status);

        if (!empty($data)) {
            $html = '<div align="center">
                        <table class="align-items-center table table-hover table-striped"><thead class="thead-dark"><tr>
                                <th scope="col"><strong>S.No.</strong></th>
                                <th scope="col"><strong>Diary No.</strong></th>
                                <th scope="col"><strong>Case No.</strong></th>
                                <th scope="col"><strong>Status</strong></th>
                            </tr>';

            $sno = 1;
            $diary_no = '';
            foreach ($data as $row) {
                $html .= '<tr>';
                if ($diary_no != $row['diary_no']) {
                    $html .= '<td rowspan="' . $row['rowspan'] . '">' . $sno++ . '</td>';
                    $html .= '<td rowspan="' . $row['rowspan'] . '">' . substr($row['diary_no'], 0, -4) . '-' . substr($row['diary_no'], -4) . '</td>';
                }
                $html .= '<td>' . $row['type_sname'] . '-' . $row['lct_caseno'] . '-' . $row['lct_caseyear'] . '</td>';
                $html .= '<td>' . ($row['conformation'] == '1' ? 'Completed' : 'Not Completed') . '</td>';
                $html .= '</tr>';
            }

            $html .= '</table></div>';

            // Send the response back as JSON
            return $this->response->setJSON(['status' => '1', 'html' => $html, 'message' => 'Data found successfully']);
        } else {
            return $this->response->setJSON(['status' => '0', 'message' => 'No data found.']);
        }
    }

    public function getDiaryDocument()
    {
        $request = service('request');
        $diary_no = $request->getVar('diary_no');
        $year = $request->getVar('year');

        $base_directory = FCPATH .'uploads' . DIRECTORY_SEPARATOR . 'scan_documents' . DIRECTORY_SEPARATOR;
        $master = $base_directory . $year . DIRECTORY_SEPARATOR . $diary_no;

        $html = '<div align="center">
        <table class="align-items-center table table-hover table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="col"><strong>S.No.</strong></th>
                    <th scope="col"><strong>Document Type</strong></th>
                    <th scope="col"><strong>Case No.</strong></th>
                    <th scope="col"><strong>Total Pages</strong></th>
                </tr>
            </thead>';

        if (is_dir($master)) {
                $sno = 1;
                $files = glob($master . DIRECTORY_SEPARATOR . "*");

            if (!empty($files)) {
                foreach ($files as $file) {
           

                    if (basename($file) == 'Thumbs.db') continue;

                    $file_info = pathinfo($file);
                    $document_type = isset($file_info['filename']) ? $file_info['filename'] : 'Unknown';
                    $total_pages = 0;

                    if ($file_info['extension'] == 'pdf' && file_exists($file)) {
                        $total_pages = $this->getPdfPageCount($file);
                    }
                    $file_url = base_url('uploads/scan_documents/' . $year . '/' . $diary_no . '/' . $file_info['basename']);

                    $getFileSD = $this->supremeCourt->getFileSD($file_info['filename']);

                    $html .= "<tr>
                    <td>{$sno}</td>
                    <td>
                        <a style=\"text-decoration: underline; cursor: pointer;\" 
                        data-url=\"" . htmlspecialchars($file_url) . "\" 
                        onclick=\"openModal(this.getAttribute('data-url'))\">
                            " . htmlspecialchars($file_info['basename']) . "
                        </a>
                    </td>
                    <td>
                        <a style=\"text-decoration: underline; cursor: pointer;\" 
                        data-url=\"" . htmlspecialchars($file_url) . "\" 
                        onclick=\"openModal(this.getAttribute('data-url'))\">
                            {$getFileSD}
                        </a>
                    </td>
                    <td>{$total_pages}</td>
                    </tr>";
                    $sno++;
                }
            } else {
                $html .= '<tr><td colspan="4">No files found.</td></tr>';
            }
        } else {
            $html .= '<tr><td colspan="4">Directory does not exist.</td></tr>';
        }

        $html .= '</table></div>';

        return $this->response->setJSON(['status' => '1', 'html' => $html]);
    }


    // public function getDiaryDetail()
    // {
    //     $request = service('request');
    //     $diary_no = $request->getVar('diary_no');
    //     $year = $request->getVar('year');

    //     $data = $this->supremeCourt->findDiaryDetail($diary_no, $year);
    //     if (!empty($data)) {
    //         $html = '<div align="center">
    //                     <table class="align-items-center table table-hover table-striped">
    //                         <thead class="thead-dark">
    //                             <tr>
    //                                 <th scope="col"><strong>S.No.</strong></th>
    //                                 <th scope="col"><strong>Document Type</strong></th>
    //                                 <th scope="col"><strong>Case No.</strong></th>
    //                                 <th scope="col"><strong>Total Pages</strong></th>
    //                             </tr>
    //                         </thead>
    //                         <tbody>';
    //         $sno = 1;
    //         foreach ($data as $row) {
    //             $html .= "<tr>
    //                         <td>{$sno}</td>
    //                         <td>{$row['document_type']}</td>
    //                         <td>{$row['case_no']}</td>
    //                         <td>{$row['total_pages']}</td>
    //                     </tr>";
    //             $sno++;
    //         }

    //         $html .= '</tbody></table></div>';

    //         return $this->response->setJSON(['status' => '1', 'html' => $html, 'message' => 'Data found successfully']);
    //     } else {
    //         return $this->response->setJSON(['status' => '0', 'message' => 'No data found.']);
    //     }
    // }

    // Function to get the total page count of a PDF file using FPDI
    private function getPdfPageCount($file)
    {
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($file);
        return $pageCount;
    }

   
}
