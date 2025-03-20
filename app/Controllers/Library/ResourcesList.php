<?php

namespace App\Controllers\Library;

use App\Controllers\BaseController;
use Config\Database;

class ResourcesList extends BaseController
{
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->session = session();
    }

    public function resourcesList_view()
    {
        $sessionData = $this->session->get();
        if (!isset($sessionData['login']['usercode'])) {
            return redirect()->to('/login');
        }

        return view('Library/resourcesList');
    }

    public function list_process()
    {
        $request = \Config\Services::request();
        $list_date = $request->getPost('list_date');
        $courtno = $request->getPost('courtno');
        $status = $request->getPost('status');

        $list_date_ymd = date("Y-m-d", strtotime($list_date));

        // Build query conditions
        $courtno_where = ($courtno > 0) ? " AND court_no = :courtno:" : "";
        $status_where = $status === 'Pending' ? " AND i_status = 'Pending' " : ($status === 'Completed' ? " AND i_status = 'Completed' " : "");

        // SQL Query
        $sql = "SELECT * FROM library_reference_material 
                WHERE is_active = 1 
                AND list_date = :list_date: 
                $courtno_where 
                $status_where 
                ORDER BY court_no, item_no, aor_code";

        // Execute the query
        $query = $this->db->query($sql, [
            'list_date' => $list_date_ymd,
            'courtno' => $courtno,
        ]);

        $results = $query->getResultArray();
        
        $courtno_string = ($courtno > 0) ? " COURT NO. $courtno" : "";
        $status_string = ($status === 'Pending') ? "(STATUS - PENDING)" : (($status === 'Completed') ? "(STATUS - COMPLETED)" : "");
        $title = "LIBRARY RESOURCES FOR CAUSE LIST DATE " . $list_date . $courtno_string . " $status_string (As on " . date('d-m-Y H:i:s') . ")";

        // Generate HTML response
        if (count($results) > 0) {
            $output = '<table class="table" id="tab">';
            $output .= '<thead><tr><th>S.No.</th><th>Case Details</th><th>AOR Name</th><th>Action</th></tr></thead>';
            $output .= '<tbody>';

            $sno = 1;
            foreach ($results as $row) {
                // Fetch AOR details
                $aor_name = $this->getAORName($row['aor_code']);
                // Generate case details
                $case_details = $this->getCaseDetails($row);
                // Fetch case number from the main table
                $diary_no = $row['diary_no'];
                $case_no = $this->getCaseNumber($diary_no);

                $output .= '<tr>';
                $output .= '<td>' . $sno++ . '</td>';
                $output .= '<td>' . $case_details . '</td>';
                $output .= '<td>' . $aor_name . '</td>';
                $output .= '<td>
                                <button type="button" class="btn btn-info btn_upload_modal" 
                                    data-case_no="' . htmlspecialchars($case_no) . '" 
                                    data-cause_title="' . htmlspecialchars($case_details) . '" 
                                    data-court_no="' . htmlspecialchars($row['court_no']) . '" 
                                    data-item_no="' . htmlspecialchars($row['item_no']) . '" 
                                    data-library_reference_material="' . htmlspecialchars($row['id']) . '" 
                                    data-diary_no="' . htmlspecialchars($diary_no) . '" 
                                    data-i_status="' . htmlspecialchars($row['i_status']) . '"  
                                    data-list_date="' . htmlspecialchars($row['list_date']) . '" 
                                    data-toggle="modal" 
                                    data-target="#myModal">Details
                                </button>
                            </td>';
                $output .= '</tr>';
            }

            $output .= '</tbody></table>';
            return $output;  
        } else {
            return "No Records Found";
        }
    }


    public function uploadFiles()
    {
        $request = \Config\Services::request();
        $files = $request->getFiles();
        $library_reference_material = $this->request->getPost('library_reference_material');
        $uploaded_files = [];

        // Handle file uploads
        $this->handleFileUpload($files['upload_document'], $library_reference_material, $uploaded_files);
        $this->handleFileUpload($files['upload_document_lib'], $library_reference_material, $uploaded_files);

        return json_encode(['status' => 'success', 'uploaded_files' => $uploaded_files]);
    }

    private function handleFileUpload($fileArray, $library_reference_material, &$uploaded_files)
    {
        if ($fileArray) {
            foreach ($fileArray as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $file->move(WRITEPATH . 'uploads/', $file->getName());
                    $uploaded_files[] = $file->getName();
                    // Optionally log the file upload to the database
                }
            }
        }
    }

    private function getAORName($aor_code)
    {
        $aor_query = $this->db->query("SELECT title, name FROM master.bar WHERE aor_code = ?", [$aor_code]);
        $aor_data = $aor_query->getRow();
        return $aor_data ? ucwords(strtolower($aor_data->title . ' ' . $aor_data->name)) : '';
    }

    private function getCaseNumber($diary_no)
    {
        $diary_query = $this->db->query("SELECT reg_no_display FROM main WHERE diary_no = ?", [$diary_no]);
        $diary_data = $diary_query->getRow();
        return $diary_data ? ($diary_data->reg_no_display ?: 'Diary No. ' . $diary_no) : 'Diary No. ' . $diary_no; // Default if not found
    }

    private function getCaseDetails($row)
    {
        $diary_no = $row['diary_no'];
        $diary_query = $this->db->query("SELECT pet_name, res_name, reg_no_display, pno, rno FROM main WHERE diary_no = ?", [$diary_no]);
        $diary = $diary_query->getRow();

        if (!$diary) {
            return 'Diary No. ' . $diary_no; // Handle as necessary
        }

        $pet_name = $diary->pet_name;
        $res_name = $diary->res_name;

        if ($diary->pno == 2) {
            $pet_name .= " AND ANR.";
        } elseif ($diary->pno > 2) {
            $pet_name .= " AND ORS.";
        }

        if ($diary->rno == 2) {
            $res_name .= " AND ANR.";
        } elseif ($diary->rno > 2) {
            $res_name .= " AND ORS.";
        }

        return $pet_name . ' Vs. ' . $res_name . '<br> Case No.: ' . ($diary->reg_no_display ?: 'Diary No. ' . $diary_no);
    }

    public function UploadModel()
    {

        $data = [
            'list_date' => $this->request->getPost('list_date'),
            'diary_no' => $this->request->getPost('diary_no'),
            'library_reference_material' => $this->request->getPost('library_reference_material'),
            'i_status' => $this->request->getPost('i_status'),
            'case_no' => $this->request->getPost('case_no'),
            'cause_title' => $this->request->getPost('cause_title'),
            'court_no' => $this->request->getPost('court_no'),
            'item_no' => $this->request->getPost('item_no'),
        ];

        // Load the view for the modal
        return view('Library/UploadModel', $data);
    }

    public function upload()
    {
      
        if ($this->request->getMethod() !== 'post') {
            return json_encode(['status' => 'Error: Invalid request method']);
        }

        $libraryReferenceMaterialId = $this->request->getPost('library_reference_material');
        $files = $this->request->getFiles('upload_document');

        $uploadedFiles = []; 

       
        foreach ($files['upload_document'] as $file) {
            if ($file->isValid()) {
              
                if ($file->getMimeType() === 'application/pdf') {
                    $newFileName = $file->getRandomName();  
                    $file->move(WRITEPATH . 'public/uploads/', $newFileName);
                    $uploadedFiles[] = $newFileName; 
                } else {
                    return json_encode(['status' => 'Error: Only PDF files are allowed']);
                }
            } else {
                return json_encode(['status' => 'Error: Invalid file upload']);
            }
        }

        return json_encode(['status' => 'Uploaded successfully', 'uploaded_files' => $uploadedFiles]);
    }

    
}