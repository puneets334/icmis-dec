<?php

namespace App\Controllers\Judicial\OriginalRecord;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\FasterModel;
use App\Models\Judicial\OriginalRecord\UploadScannedFileModel;
use App\Models\Judicial\Mentioning_Model;
use App\Models\Court\CourtMasterModel;
use App\Libraries\phpqrcode\Qrlib;
use App\Libraries\Fpdf;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class UploadScannedFile extends BaseController
{
    public $UploadScannedFileModel;
    public $CourtMasterModel;
    public $FasterModel;
    public $Mentioning_Model;

    public function __construct()
    {
        $this->FasterModel = new FasterModel();
        $this->CourtMasterModel = new CourtMasterModel();
        $this->UploadScannedFileModel = new UploadScannedFileModel();
        $this->Mentioning_Model = new Mentioning_Model();
    }

    public function getSession()
    {
        $this->session->set_userdata('dcmis_user_idd', $session);
        $this->index();
    }

    public function index()
    {
        $data['app_name'] = 'MentionMemo';
        $data['caseTypes'] = $this->Mentioning_Model->get_case_type_list();
        $diaryNumber = '';
        $data['caseInfo'] = null;
        $data['listingInfo'] = null;
        return view('Judicial/OriginalRecords/originalRecordReport', $data);
    }

    public function uploadIndex()
    {
        $data['app_name'] = 'MentionMemo';
        $data['caseTypes'] = $this->Mentioning_Model->get_case_type_list();
        $data['caseInfo'] = null;
        $data['optradio'] = '2';
        $data['listingInfo'] = null;
        return view('Judicial/OriginalRecords/originalRecordUpload', $data);
    }

    public function originalRecordReport()
    {
        $usercode = session()->get('login')['usercode'];
        return view('Judicial/OriginalRecords/originalRecordReport');
    }



    public function handlePostUploadRequest()
    {
        // pr($_POST);
        
        $validationRules = [
            'optradio' => 'required|min_length[1]|max_length[1]',
            'usercode' => 'required|min_length[1]|max_length[20]',
        ];

        $searchType = $this->request->getPost('optradio');

        if ($searchType === '2') {
            $validationRules = array_merge($validationRules, [
                'diaryNumber' => 'required|min_length[1]|max_length[15]',
                'diaryYear' => 'required|min_length[4]|max_length[4]',
            ]);
        } elseif ($searchType === '1') {
            $validationRules = array_merge($validationRules, [
                'caseType' => 'required',
                'caseNo' => 'required|min_length[1]|max_length[15]',
                'caseYear' => 'required|min_length[4]|max_length[4]',
            ]);
        }

        if (!$this->validate($validationRules)) {
            session()->setFlashdata('message_error', 'Validation errors occurred');
            return redirect()->back()->withInput();
        }

        // Initialize variables based on request data
        $caseType = $this->request->getPost('caseType');
        $caseNo = $this->request->getPost('caseNo');
        $caseYear = $this->request->getPost('caseYear');
        $diaryNo = $this->request->getPost('diaryNumber');
        $diaryYear = $this->request->getPost('diaryYear');

        if ($searchType === '1') {
            if (!empty($caseType) && !empty($caseNo)) {
                $data = $this->UploadScannedFileModel->getCaseDetails($caseType, $caseNo, $caseYear);
                //pr($data);
            }
        } elseif ($searchType === '2') {

            if (!empty($diaryNo) && !empty($diaryYear)) {

                $data = $this->UploadScannedFileModel->getDiaryDetails($diaryNo, $diaryYear);
            }
        }

        if ($data) {
            foreach ($data as $row) {
                $diaryNumber = $row['diary_no'];
                session()->set([
                    'diaryNo' => $row['dn'],
                    'diaryYear' => $row['dy'],
                    'diaryNumber' => $diaryNumber,
                ]);
            }

            $data['caseInfo'] = $this->UploadScannedFileModel->getCaseDetailsData($diaryNumber);
            $data['app_name'] = 'MentionMemo';
            $data['caseTypes'] = $this->Mentioning_Model->get_case_type_list();
        } else {
            // Handle case when no data is found

            session()->setFlashdata('message_error', 'Case Not Found');
            return redirect()->to(base_url('Judicial/OriginalRecord/UploadScannedFile/uploadIndex'))->withInput();
        }
        
        $data['optradio'] = $searchType;

        return view('Judicial/OriginalRecords/originalRecordUpload', $data);
    }


    // public function uploadOriginalRecord()
    // {
    //     $validationRules = [
    //         'usercode' => 'required|min_length[1]|max_length[20]',
    //         'diaryNo' => 'required|min_length[1]|max_length[15]',
    //         'diaryYear' => 'required|min_length[4]|max_length[4]'
    //     ];

    //     $usercode = $this->request->getPost('usercode');
    //     $diaryNo = $this->request->getPost('diaryNo');
    //     $diaryYear = $this->request->getPost('diaryYear');

    //     if (!$this->validate($validationRules)) {
    //         session()->setFlashdata('message_error', 'Validation errors occurred');
    //         return redirect()->back()->withInput();
    //     }

    //     $diarynumber = $diaryNo . $diaryYear;
    //     $ifAlreadyUploaded = $this->UploadScannedFileModel->checkIfFileExist($diarynumber);
    //     $msg = "";
    //     $status = 0;
    //     if (isset($_FILES['fileOriginalRecord'])) {
    //         $desired_dir = "";
    //         $desired_dir_in_db = "";
    //         $myhash = md5_file($_FILES['fileOriginalRecord']['tmp_name']);
    //         $fileName = $_FILES['fileOriginalRecord']['name'];
    //         $fileSize = $_FILES['fileOriginalRecord']['size'];
    //         $fileTmp = $_FILES['fileOriginalRecord']['tmp_name'];
    //         $fileType = $_FILES['fileOriginalRecord']['type'];
    //         $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    //         if ($ifAlreadyUploaded != 0) {
    //             //var_dump($ifAlreadyUploaded[0]);
    //             $desired_dir = "/san_home/OriginalRecords/" . $diaryYear . "/" . $diaryNo;
    //             $desired_dir_in_db = "originalrecords/" . $diaryYear . "/" . $diaryNo;
    //             $uploadTime = str_replace(':', '-', str_replace(' ', '-', $ifAlreadyUploaded[0]['updated_on']));
    //             $existingFileName = $ifAlreadyUploaded[0]['file_name'];
    //             $existingFileName = substr($existingFileName, strrpos($existingFileName, '/') + 1);

    //             $uploadedFileName = $myhash . "." . $fileExtension;
    //             if (is_dir("$desired_dir/" . $uploadedFileName) == false) {
    //                 $old = $desired_dir . "/" . $existingFileName . ".pdf";
    //                 $new = $desired_dir . "/" . $existingFileName . "#" . $uploadTime . ".pdf";
    //                 $newForDb = $desired_dir_in_db . "/" . $existingFileName . "#" . $uploadTime;
    //                 rename($old, $new);
    //                 $dataForUpdate = array('id' => $ifAlreadyUploaded[0]['id'], 'file_name' => $newForDb);
    //                 $result = $this->UploadScannedFileModel->updateOriginalRecords($dataForUpdate);
    //             }
    //         }

    //         $desired_dir = "/san_home/OriginalRecords/" . $diaryYear . "/" . $diaryNo;
    //         $desired_dir_in_db = "originalrecords/" . $diaryYear . "/" . $diaryNo;
    //         $uploadedFileName = $myhash . "." . $fileExtension;
    //         if (is_dir($desired_dir) == false) {
    //             //echo "Inside to create directory: ".$desired_dir;
    //             mkdir("$desired_dir", 0755, true);        // Create directory if it does not exist
    //         }
    //         if (is_dir("$desired_dir/" . $uploadedFileName) == false) {
    //             move_uploaded_file($fileTmp, "$desired_dir/" . $uploadedFileName);
    //         }
    //         $dataForUpdate = array('diary_no' => $diarynumber, 'file_name' => $desired_dir_in_db . '/' . $myhash, 'usercode' => $usercode);
    //         $result = $this->UploadScannedFileModel->insertOriginalRecords($dataForUpdate);
    //         if ($result) {
    //             $msg = "Original record uploaded Successfully";
    //             $status = 1;
    //         } else {
    //             $msg = "There is some problem while uploading Original record";
    //             $status = 0;
    //         }
    //     } else {
    //         $msg = "Only PDF file is allowed";
    //         $status = 0;
    //     }
    //     $data['status'] = $status;
    //     $data['message'] = $msg;
    //     //$this->load->view('OriginalRecords/originalRecordUploadStatus', $data);
    //     return view('Judicial/OriginalRecords/originalRecordUploadStatus', $data);

    // }

    public function uploadOriginalRecord()
    {
        $validationRules = [
            'usercode' => 'required|min_length[1]|max_length[20]',
            'diaryNo' => 'required|min_length[1]|max_length[15]',
            'diaryYear' => 'required|min_length[4]|max_length[4]',
        ];

        // Retrieve post data
        $usercode = $this->request->getPost('usercode');
        $diaryNo = $this->request->getPost('diaryNo');
        $diaryYear = $this->request->getPost('diaryYear');

        // Validate input
        if (!$this->validate($validationRules)) {
            session()->setFlashdata('message_error', 'Validation errors occurred');
            return redirect()->back()->withInput();
        }

        // Generate diary number and check if file exists
        $diarynumber = $diaryNo . $diaryYear;
        $ifAlreadyUploaded = $this->UploadScannedFileModel->checkIfFileExist($diarynumber);

        $msg = "";
        $status = 0;

        // Check if file is uploaded
        if ($file = $this->request->getFile('fileOriginalRecord')) {
            $fileHash = md5_file($file->getTempName());
            $fileExtension = $file->getClientExtension();
            $fileSize = $file->getSize();
            $fileName = $file->getName();

            $desiredDir = WRITEPATH."/san_home/OriginalRecords/" . $diaryYear . "/" . $diaryNo;
            

 
            $desiredDirInDb = "OriginalRecords/" . $diaryYear . "/" . $diaryNo;
            $uploadedFileName = $fileHash . "." . $fileExtension;

            // Handle already uploaded file
            if ($ifAlreadyUploaded) {
                $existingFileName = basename($ifAlreadyUploaded[0]['file_name']);
                $uploadTime = str_replace(':', '-', str_replace(' ', '-', $ifAlreadyUploaded[0]['updated_on']));
                $oldPath = $desiredDir . "/" . $existingFileName . ".pdf";
                $newPath = $desiredDir . "/" . $existingFileName . "#" . $uploadTime . ".pdf";
                $newPathInDb = $desiredDirInDb . "/" . $existingFileName . "#" . $uploadTime;

                if (!file_exists("$desiredDir/$uploadedFileName")) {
                    if (file_exists($oldPath)) {
                        rename($oldPath, $newPath);
                    }

                    $updateData = [
                        'id' => $ifAlreadyUploaded[0]['id'],
                        'file_name' => $newPathInDb,
                    ];
                    
                    $this->UploadScannedFileModel->updateOriginalRecords($updateData);
                }
            }

            // Ensure directory exists
            if (!is_dir($desiredDir)) {
                mkdir($desiredDir, 0755, true);
            }

            // Move uploaded file
            if (!file_exists("$desiredDir/$uploadedFileName")) {
                $file->move($desiredDir, $uploadedFileName);
            }

            // Insert new file record
            $dataForInsert = [
                'diary_no' => $diarynumber,
                'file_name' => $desiredDirInDb . '/' . $fileHash,
                'usercode' => $usercode,
            ];
            
            $result = $this->UploadScannedFileModel->insertOriginalRecords($dataForInsert);
            if ($result) {
                $msg = "Original record uploaded successfully.";
                $status = 1;
            } else {
                $msg = "There was a problem uploading the original record.";
                $status = 0;
            }
        } else {
            $msg = "No file uploaded or invalid file format.";
            $status = 0;
        }

        // Prepare response data and render the view
        $data = [
            'status' => $status,
            'message' => $msg,
        ];
        return view('Judicial/OriginalRecords/originalRecordUploadStatus', $data);
    }


    public function downloadpdf_file($fileName){
        
        $fileName = base64_decode($fileName);
        $filePath = WRITEPATH . 'san_home/' . $fileName . '.pdf';

        if (file_exists($filePath)) {
            return $this->response->download($filePath, null);
        }

        return "File not found.";

    }


    public function getCasesData()
    {
        // Get input parameters from GET request
        $fromDate = $this->request->getGet('fromDate');
        $toDate = $this->request->getGet('toDate');
        $usercode = session()->get('login')['usercode'];

        // Debugging: Uncomment to view GET parameters
        // pr($_GET); // Custom print_r function (if available)
        // die();
        if (empty($fromDate) || empty($toDate) || empty($usercode)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Missing required parameters: fromDate, toDate, or usercode.',
            ]);
        }

        // Fetch case list using the model
        $result = $this->UploadScannedFileModel->getUploadedOriginalRecord($fromDate, $toDate, $usercode);
        //pr($result);
        if (!empty($result) && isset($result[0]['diary_no'])) {
            $response = [
                'status' => 'success',
                'data' => $result
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'No cases found for the given date range and user.'
            ];
        }

        //return json_encode($response);
        // Load the view with the retrieved data
        return view('Judicial/OriginalRecords/getOriginalRecordResult', $response);
    }
}
