<?php

namespace App\Controllers\Scanning;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Scanning\Scaned\ScanedModel;
// use App\Models\Scanning\Scaned\CaseModel;
use App\Models\Scanning\Scaned\LowerCourtModel;
use DateTime; // Import the DateTime class


class ScanningController extends BaseController
{

    protected $scanedModel;
    protected $lowerCourtModel;

    public function __construct()
    {
        $this->scanedModel = new ScanedModel(); // Load the model
        $this->lowerCourtModel = new LowerCourtModel(); // Load the model
    }

    public function getCSRF()
    {
        return $this->response->setJSON([
            'csrf_token' => csrf_hash()
        ]);
    }

    public function diaryNoDetails()
    {
        return view('scanning/scanedFile/diary_no_details_view'); // Load your view
    }

    public function hcDcIndexing()
    {
        return view('scanning/scanedFile/hc_dc_indexing_view'); // Load your view
    }

    public function indexingExcelView()
    {
        return view('scanning/scanedFile/indexing_excel_view'); // Load your view
    }

    public function indexingLooseDocument()
    {
        return view('scanning/scanedFile/indexing_loose_document'); // Load your view
    }

    public function scanedView()
    {
        $courtData = $this->scanedModel->getCourtData();
        $stateData = $this->scanedModel->getStateData();
        $data = [
            'courtData' => $courtData,
            'stateData' => $stateData ?? '',
        ];
        return view('scanning/scanedFile/scan_view', $data); // Load your view
    }

    //Get banch data
    public function getBench()
    {
        $request = service('request');
        $ddl_st_agncy = $request->getVar('ddl_st_agncy');
        // $ddl_bench = $request->getVar('ddl_bench');
        $ddl_court = $request->getVar('ddl_court');

        $bench = '';
        $model = new ScanedModel();
        $caseTypes = $model->getCaseTypes_branch($ddl_st_agncy, $ddl_court);
        $response = '';
        if ($caseTypes) {
            $response = '<option value="">Select</option>';
            foreach ($caseTypes as $row) {
                $response .= '<option value="' . $row['id'] . '">' .  ucfirst($row['lccasecode']) . ' :: ' . $row['type_sname'] . '</option>';
            }
            return $this->response->setJSON(['status' => 'success', 'html' => $response]);
        }
        return $this->response->setJSON(['status' => 'error', 'html' => '<div style="text-align: center"><b>No Record Found</b></div>']);
    }

    public function get_case_structure()
    {
        $request = service('request');
        $ddl_st_agncy = $request->getvar('ddl_st_agncy');
        $ddl_bench = $request->getvar('ddl_bench');
        $ddl_court = $request->getvar('ddl_court');
        

        $bench = '';
        // if ($ddl_st_agncy == '292979') {
        //     switch ($ddl_bench) {
        //         case '17':
        //             $bench = '01';
        //             break;
        //         case '18':
        //             $bench = '02';
        //             break;
        //         case '19':
        //             $bench = '03';
        //             break;
        //     }

            $caseTypes = $this->scanedModel->getCaseTypes($ddl_st_agncy);
            // echo $this->db->getLastQuery();
            // echo "<pre>";print_r($caseTypes);
            if (!empty($caseTypes)) {
                $html = '<input type="hidden" name="hd_mn" id="hd_mn" value="' . $bench . '"/>
                        <select name="cs_tp" id="cs_tp">
                        <option value="">Select</option>';

                foreach ($caseTypes as $caseType) {
                    $html .= '<option value="' . $caseType['lccasecode'] . '">' . $caseType['type_sname'] . '</option>';
                }

                $html .= '</select>&nbsp;&nbsp;
                         <input type="text" name="txtFNo" id="txtFNo" maxlength="5" size="5" onblur="com_filingNo()" />&nbsp;&nbsp;
                         <input type="text" name="txtYear" id="txtYear" maxlength="4" size="4" />&nbsp;&nbsp;
                         <input type="button" name="btnSubmit" id="btnSubmit" value="Submit" onclick="getDetails();" />';

                return $this->response->setJSON(['status' => 'success', 'html' => $html]);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'No data found.']);
            }
        // } else {
        //     return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid State Agency or Bench']);
        // }
    }

    //Indexing(Loose Document) View/Download
    public function exportCsv()
    {
        $request = service('request');
        $txt_fd = $request->getVar('txt_fd');
        $txt_td = $request->getVar('txt_td');
        $csv_type = $request->getVar('csv_type');  // Get the csv_type from the request

        // Convert dates to proper format
        $txt_fd = DateTime::createFromFormat('d/m/Y', $txt_fd)->format('Y-m-d');
        $txt_td = DateTime::createFromFormat('d/m/Y', $txt_td)->format('Y-m-d');
        
        // Get records from the model (you mentioned `scanedModel`, assuming this is correct)
        $records = $this->scanedModel->getRecordsBetweenDates($txt_fd, $txt_td);
        // echo $this->db->getLastQuery();
        // print_r($records);
        // die;
        if (!empty($records)) {
            // Set CSV header based on the csv_type
            if ($csv_type === 'indexingExcelView') {
                // Exclude 'Document No.', 'Document Year', 'S.No.'
                $csv_header = [
                    'diary_no',
                    'doccode',
                    'doccode1',
                    'other',
                    'i_type',
                    'fp',
                    'tp',
                    'np',
                    'entdt',
                    'ucode',
                    'display',
                    'upd_tif_dt',
                    'upd_tif_id',
                    'ind_id',
                    'pdf_name',
                    'lowerct_id',
                    'src_of_ent',
                    'file_id'
                ];
            } else {
                // Include all fields
                $csv_header = [
                    'diary_no',
                    'doccode',
                    'doccode1',
                    'other',
                    'i_type',
                    'fp',
                    'tp',
                    'np',
                    'entdt',
                    'ucode',
                    'display',
                    'upd_tif_dt',
                    'upd_tif_id',
                    'ind_id',
                    'pdf_name',
                    'lowerct_id',
                    'src_of_ent',
                    'file_id',
                    'Document No.',
                    'Document Year',
                    'S.No.'
                ];
            }

            // Set CSV download headers
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . date('Y-m-d_H-i-s') . '.csv');

            // Open PHP output stream for writing the CSV
            $fp = fopen('php://output', 'w');

            // Write CSV header
            fputcsv($fp, $csv_header);

            // Iterate through records and write rows
            $sr_no = 1;
            foreach ($records as $record) {
                // Prepare the CSV row data based on csv_type
                $csv_row = [
                    $record['diary_no'],
                    $record['doccode'],
                    $record['doccode1'],
                    $record['other'],
                    $record['i_type'],
                    $record['fp'],
                    $record['tp'],
                    $record['np'],
                    $record['entdt'],
                    $record['ucode'],
                    $record['display'],
                    $record['upd_tif_dt'],
                    $record['upd_tif_id'],
                    $record['ind_id'],
                    $record['pdf_name'],
                    $record['lowerct_id'],
                    $record['src_of_ent'],
                    $record['file_id']
                ];

                // For non-indexingExcelView type, include additional fields
                if ($csv_type !== 'indexingExcelView') {
                    $csv_row[] = $record['docnum'];
                    $csv_row[] = $record['docyear'];
                    $csv_row[] = $sr_no++;
                }

                // Write the row to CSV
                fputcsv($fp, $csv_row);
            }

            fclose($fp); // Close the file pointer
            exit(); // Exit to avoid any extra output
        } else {
            // Return error response if no records found
            return $this->response->setJSON(['error' => 'No records found for the selected date range.'])->setStatusCode(404);
        }
    }

    //HC DC Indexing View/Download
    public function exportHCDCCsv()
    {
        $request = service('request');
        $txt_frm_date = $request->getVar('txt_frm_date');
        $txt_to_date = $request->getVar('txt_to_date');
        
        $txt_frm_date = DateTime::createFromFormat('d/m/Y', $txt_frm_date)->format('Y-m-d');
        $txt_to_date = DateTime::createFromFormat('d/m/Y', $txt_to_date)->format('Y-m-d');
        
        // Get records using your model
        $records = $this->lowerCourtModel->getRecords($txt_frm_date, $txt_to_date);
        // echo $this->db->getLastQuery();
        // echo "<pre>";
        // print_r($records);
        // die;
        // Prepare CSV output
        if (!empty($records)) {
            $csv_header = [
                'S.No.',
                'Diary No. in Supreme Court',
                'Case No. of High Court',
            ];

            // Set CSV download headers
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename="HC_DC_Indexing_' . date('Y-m-d_H-i-s') . '.csv";');

            // Open PHP output stream for writing the CSV
            $fp = fopen('php://output', 'w');
            fputcsv($fp, $csv_header); // Write CSV header

            // Write each record into the CSV
            $sno = 1;
            foreach ($records as $record) {
                $csv_row = [
                    $sno++,
                    $record['diary_no'],
                    $record['type_sname'] . '-' . $record['lct_caseno'] . '-' . $record['lct_caseyear'],
                ];
                fputcsv($fp, $csv_row); // Write the data row to CSV
            }

            fclose($fp); // Close the output stream
            exit(); // End the script to avoid further output
        } else {
            return $this->response->setJSON(['error' => 'No records found for the selected date range.'])->setStatusCode(404);
        }
    }

    //Diary No Details
    public function getDiaryDetails()
    {
        $request = service('request');

        $txt_frm_date = $request->getVar('txt_frm_date');
        $txt_to_date = $request->getVar('txt_to_date');
        $ddl_dt_type = $request->getVar('ddl_dt_type');

        // Convert dates to Y-m-d format
        $txt_frm_date = date('Y-m-d', strtotime($txt_frm_date));
        $txt_to_date = date('Y-m-d', strtotime($txt_to_date));

        // Fetch records based on the date range and type
        $records = $this->scanedModel->fetchDiaryDetails($txt_frm_date, $txt_to_date, $ddl_dt_type);
        // echo $this->db->getLastQuery();
        // echo "<pre>";
        // print_r($records);
        // die;    
        // Check if CSV download request is made (use a specific flag or parameter if needed)
        if ($request->getVar('download') == 'csv') {
            // Prepare CSV output
            if (!empty($records)) {
                $csv_header = [
                    'S.No',
                    'Diary No',
                    'Diary Receiving Date',
                    'Case No',
                    'Case Type',
                    'Case Year',
                    'Petitioner Name',
                    'Respondent Name',
                    'Petitioner Advocate',
                    'Respondent Advocate',
                    'Subject Category'
                ];

                // Set CSV download headers
                header('Content-Type: application/csv');
                header('Content-Disposition: attachment; filename="' . date('Y-m-d_H-i-s') . '.csv";');
                header('Pragma: no-cache');
                header('Expires: 0');

                // Open PHP output stream for writing the CSV
                $fp = fopen('php://output', 'w');
                fputcsv($fp, $csv_header); // Write CSV header

                // Write each record into the CSV
                $sno = 1;
                foreach ($records as $record) {
                    $csv_row = [
                        $sno++,
                        $record['diary_no'],
                        $record['diary_no_rec_date'],
                        $record['case_number'],
                        $record['case_type'],
                        $record['case_year'],
                        $record['pet_name'],
                        $record['res_name'],
                        $record['petitioner_adv'],
                        $record['respondent_adv'],
                        $record['subject_category'],
                    ];
                    fputcsv($fp, $csv_row); // Write the data row to CSV
                }

                fclose($fp); // Close the output stream
                exit(); // End the script to avoid further output
            } else {
                return $this->response->setStatusCode(404)->setJSON(['error' => 'No records found for the selected date range.']);
            }
        } else {
            // If JSON response is required (for the modal display)
            if (!empty($records)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'data' => $records
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'No data found'
                ]);
            }
        }
    }
}
