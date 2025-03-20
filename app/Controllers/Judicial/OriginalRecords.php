<?php
namespace App\Controllers\Judicial;
use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\FasterModel;
use App\Models\Judicial\OriginalRecordsModel;
use App\Models\Judicial\Mentioning_Model;
use App\Models\Court\CourtMasterModel;
use App\Libraries\phpqrcode\Qrlib;
use App\Libraries\Fpdf;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class OriginalRecords extends BaseController
{
    public $OriginalRecordsModel;
    public $CourtMasterModel;
    public $FasterModel;
    public $Mentioning_Model;

    public function __construct()
    {
        // $this->load->helper('form');
        // $this->load->helper('url');
        // $this->load->helper('html');
        // $this->load->database('sci_cmis_final');
        // $this->load->model('Mentioning_Model');
        // $this->load->model('OriginalRecordsModel');
        // $this->load->model('CourtMasterModel');
        // $this->load->library('form_validation');
        // $this->load->library('session');
        $this->FasterModel = new FasterModel();
        $this->CourtMasterModel = new CourtMasterModel();
        $this->OriginalRecordsModel = new OriginalRecordsModel();
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

        if ($_POST) {

            if ($_POST['optradio'] == 1)
            {
                if (((isset($_POST['caseType']))) || (!empty($_POST['caseType'])) && (((isset($_POST['caseNo']))) || (!empty($_POST['caseNo']))))
                {
                    $caseTypeId = $_POST['caseType'];
                    $caseNo = $_POST['caseNo'];
                    $caseYear = $_POST['caseYear'];
                    $data['diaryDetails'] = $this->Mentioning_Model->get_diary_details($caseTypeId, $caseNo, $caseYear);
                }
            }

            if ($_POST['optradio'] == 2)
            {
                $diaryNo = $_POST['diaryNumber'];
                $diaryYear = $_POST['diaryYear'];
                $data['diaryDetails'] = $this->Mentioning_Model->get_diary_details($diaryNo, $diaryYear);
            }

            if (($data['diaryDetails']))
            {
                foreach ($data['diaryDetails'] as $row)
                {
                    // diary No for All further process
                    $diaryNumber = $row['diary_no'];
                    session()->set([
                        'diaryNo' => $row['dn'],
                        'diaryYear' => $row['dy'],
                        'diaryNumber' => $row['diary_no']
                    ]);
                }

                $data['caseInfo'] = $this->Mentioning_Model->getCaseDetails($diaryNumber);
            }
            else
            {
                $data['save'] = 'saveMentionMemo';
                $data['caseInfo'] = '';
                $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Case Not Found</div>');
                return redirect()->to(base_url('Judicial/OriginalRecords/index'));
            }
        }
        // pr($data);
        return view('Judicial/OriginalRecords/originalRecordUpload', $data);

    }

    public function uploadOriginalRecord()
    {
        extract($_POST);
        $diarynumber = $diaryNo . $diaryYear;
        $ifAlreadyUploaded = $this->OriginalRecordsModel->checkIfFileExist($diarynumber);
        $msg="";$status=0;
        if (isset($_FILES['fileOriginalRecord'])) {
            $desired_dir = "";
            $desired_dir_in_db = "";
            $myhash = md5_file($_FILES['fileOriginalRecord']['tmp_name']);
            $fileName = $_FILES['fileOriginalRecord']['name'];
            $fileSize = $_FILES['fileOriginalRecord']['size'];
            $fileTmp = $_FILES['fileOriginalRecord']['tmp_name'];
            $fileType = $_FILES['fileOriginalRecord']['type'];
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            if ($ifAlreadyUploaded != 0) {
                //var_dump($ifAlreadyUploaded[0]);
                $desired_dir = "/san_home/OriginalRecords/" . $diaryYear . "/" . $diaryNo;
                $desired_dir_in_db = "originalrecords/" . $diaryYear . "/" . $diaryNo;
                $uploadTime = str_replace(':', '-', str_replace(' ', '-', $ifAlreadyUploaded[0]['updated_on']));
                $existingFileName = $ifAlreadyUploaded[0]['file_name'];
                $existingFileName = substr($existingFileName, strrpos($existingFileName, '/') + 1);

                $uploadedFileName = $myhash . "." . $fileExtension;
                if (is_dir("$desired_dir/" . $uploadedFileName) == false) {
                    $old = $desired_dir . "/" . $existingFileName . ".pdf";
                    $new = $desired_dir . "/" . $existingFileName . "#" . $uploadTime . ".pdf";
                    $newForDb = $desired_dir_in_db . "/" . $existingFileName . "#" . $uploadTime;
                    rename($old, $new);
                    $dataForUpdate = array('id' => $ifAlreadyUploaded[0]['id'], 'file_name' => $newForDb);
                    $result = $this->OriginalRecordsModel->updateOriginalRecords($dataForUpdate);
                }

            }

            $desired_dir = "/san_home/OriginalRecords/" . $diaryYear . "/" . $diaryNo;
            $desired_dir_in_db = "originalrecords/" . $diaryYear . "/" . $diaryNo;
            $uploadedFileName = $myhash . "." . $fileExtension;
            if (is_dir($desired_dir) == false) {
                //echo "Inside to create directory: ".$desired_dir;
                mkdir("$desired_dir", 0755, true);        // Create directory if it does not exist
            }
            if (is_dir("$desired_dir/" . $uploadedFileName) == false) {
                move_uploaded_file($fileTmp, "$desired_dir/" . $uploadedFileName);
            }
            $dataForUpdate = array('diary_no' => $diarynumber, 'file_name' => $desired_dir_in_db . '/' . $myhash, 'usercode' => $usercode);
            $result = $this->OriginalRecordsModel->insertOriginalRecords($dataForUpdate);
            if ($result) {
                $msg= "Original record uploaded Successfully";
                $status=1;
            } else {
                $msg= "There is some problem while uploading Original record";
                $status=0;
            }

        }
        else{
            $msg= "Only PDF file is allowed";
            $status=0;
        }
        $data['status']=$status;
        $data['message']=$msg;
        $this->load->view('OriginalRecords/originalRecordUploadStatus', $data);
    }

    public function originalRecordReport()
    {
        $usercode = session()->get('login')['usercode'];
        return view('Judicial/OriginalRecords/originalRecordReport');
    }

    public function getCasesForDownloading()
    {
        extract($_GET);
        // pr($_GET);
        // die;
        // $usercode = session()->get('login')['usercode'];
        // $fromDate = $_GET['fromDate'];
        // $toDate = $_GET['toDate'];
        // echo $usercode."<br>";
        // echo $fromDate."<br>";
        // echo $toDate."<br>";
        // die;
        $data['caseList'] = $this->OriginalRecordsModel->getCaseDownloadList($fromDate, $toDate, $usercode);
        // $this->load->view('OriginalRecords/getOriginalRecordResult', $data);
        return view('Judicial/OriginalRecords/getOriginalRecordResult', $data);
    }

}
