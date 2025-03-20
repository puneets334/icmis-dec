<?php

namespace App\Controllers\Filing;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Entities\DefectRecordPaperbook;


class DefectiveMatter extends BaseController
{
    public function add()
    {
        return view('Filing/DefectiveMatterRecordKeepingView');
    }

    public function GetMatterInfo()
    {


        $section = "";
        $courtfees = "";
        $dateofnotification = "";

        $dno = trim($_REQUEST['dno']);
        $dyr = trim($_REQUEST['dyr']);
        $module = trim($_REQUEST['module']);
        $diaryno = $dno . $dyr;

        $defectiveMatterModel = new DefectRecordPaperbook();
        $data['data'] = $defectiveMatterModel->getMainRecord($diaryno, $module);
        // pr($data['data']);
    }

    public function saveRecord()
    {
        // pr($_REQUEST);
        $defectModel = new DefectRecordPaperbook();
        $ucode = $_REQUEST['ucode'];

        if (($_REQUEST['controller'] == 'I') || ($_REQUEST['controller'] == 'U') || ($_REQUEST['controller'] == 'D')) {
            $_REQUEST['dno'] = htmlentities(trim($_REQUEST['dno']));
        }
        $_REQUEST['section'] = htmlentities(trim($_REQUEST['section']));
        $_REQUEST['courtfee'] = htmlentities(trim($_REQUEST['courtfee']));
        $_REQUEST['notfdate'] = htmlentities(trim($_REQUEST['notfdate']));
        $_REQUEST['rackno'] = htmlentities(trim($_REQUEST['rackno']));
        $_REQUEST['shelfno'] = htmlentities(trim($_REQUEST['shelfno']));

        if ($_REQUEST['controller'] == 'BU') {
            $_REQUEST['seqno1'] = htmlentities(trim($_REQUEST['seqno1']));
            $_REQUEST['seqno2'] = htmlentities(trim($_REQUEST['seqno2']));
        }

        $section_name = $_REQUEST['section'];
        $section_id = $defectModel->getSectionId($section_name);
        $secid = "";
        if (!empty($section_id)) {
            $secid = $section_id['id'];
        }
        $dno = $_REQUEST['dno'];
        $courtfee = $_REQUEST['courtfee'];
        $defect_notify_date = $_REQUEST['notfdate'];
        $date = \DateTime::createFromFormat('d-m-Y', $defect_notify_date);
        $formattedDate = $date->format('Y-m-d');
        $rackno = $_REQUEST['rackno'];
        $shelfno = $_REQUEST['shelfno'];

        $controller = $_REQUEST['controller'];
        if ($controller === 'I') {
            // pr('92');
            $data = [
                'diary_no' => $dno,
                'section_id' => $secid,
                'court_fees' => $courtfee,
                'defect_notify_date' => $formattedDate,
                'rack_no' => $rackno,
                'shelf_no' => $shelfno,
                'display' => 'Y',
                'ent_dt' => date('Y-m-d H:i:s'),
                'ent_userid' =>  $_SESSION['login']['usercode']
            ];

            if ($defectModel->insertRecord($data)) {
                return $this->response->setJSON('Record Added Successfully');
            } else {
                return $this->response->setJSON('Failed to Add Record');
            }
        } elseif ($controller === 'U') {
            // pr('111');
            $id = $_REQUEST['id'];
            $data = [
                'rack_no' => $rackno,
                'shelf_no' => $shelfno,
                'upd_dt' => date('Y-m-d H:i:s'),
                'upd_userid' => session()->get('dcmis_user_idd')
            ];

            if ($defectModel->updateRecord($data, $id)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Record Updated Successfully']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to Update Record']);
            }
        } elseif ($controller === 'D') {
            $id = $this->request->getPost('id');
            if ($defectModel->deleteRecord($id)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Record Deleted Successfully']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to Delete Record']);
            }
        }
    }

    public function DefectiveMatterReport()
    {
        $data['dtd'] = date("d-m-Y");
        return view('Filing/DefectiveMatterReportView',$data);
    }

    public function GetDefectiveReport()
    {   
        // pr($_REQUEST);
        $dtd1=date('Y-m-d',strtotime($_REQUEST['dtd1']));
        $dtd2=date('Y-m-d',strtotime($_REQUEST['dtd2']));
        $data['date1'] = date('d-m-Y',strtotime($dtd1));
        $data['date2'] = date('d-m-Y',strtotime($dtd2));

        $model = new DefectRecordPaperbook();
        $data['result'] = $model->getDefectiveRecords($dtd1, $dtd2);
        return view('Filing/DefectiveMatterReportData',$data);

    }


    public function updateBulk()
    {
        return view('Filing/UpdateBulkView');
    }

    public function DefectUpdateBulk()
    {
        $defectModel = new DefectRecordPaperbook();
        $controller = $_REQUEST['controller'];
        $rackno = htmlentities($_REQUEST['rackno'] ?? '');
        $shelfno = htmlentities($_REQUEST['shelfno'] ?? '');

        if ($controller === 'BU') {
            $seqno1 = $_REQUEST['seqno1'];
            $seqno2 = $_REQUEST['seqno2'];

            if (!$seqno1 || !$seqno2 || !$rackno || !$shelfno) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Missing required fields',
                ]);
            }

            $idRange = range($seqno1, $seqno2);
            $defectModel->bulkUpdate($rackno, $shelfno, $idRange);
            if (!empty($defectModel)) {
                echo 1;
                exit();
            } else {
                echo "No records were updated";
                exit();
            }
        }
    }
}
