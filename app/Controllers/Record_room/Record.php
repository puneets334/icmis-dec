<?php

namespace App\Controllers\Record_room;

use App\Controllers\BaseController;

use App\Models\Record_room\Model_record;
use App\Models\Record_room\TransactionModel;
use App\Models\Record_room\Record_keeping_model;
use App\Models\Entities\Model_Ac;
use DateTimeZone;

class Record extends BaseController
{
    public $Model_Ac;

    public $model;
    public $Record_keeping_model;

    function __construct()
    {
        $this->model = new Model_record();
        ini_set('memory_limit', '51200M');

        $this->model = new TransactionModel();
        $this->Record_keeping_model = new Record_keeping_model();
        ini_set('memory_limit', '51200M');

        $this->Model_Ac = new Model_Ac();
    }


    public function ac_form()
    {
        $sessionData = $this->session->get();
        $usercode = $sessionData['login']['usercode'];
        return view('Record_room/ac_form');
    }

    public function AorInsert()
    {
        try {

            $model = new Model_record();
            $sessionData = $this->session->get();

            $ucode = $sessionData['login']['usercode'] ?? null;
            if (empty($ucode)) {
                echo "User code is not set or is empty!";
                die();
            }

            $tvap = $this->request->getGet('tvap');

            $wordChunks = explode(";", $tvap);
            $vform = array_map(function ($item) {
                return str_replace("undefined", "", $item);
            }, $wordChunks);

            $vcname = $vform[2] . ' ' . $vform[3] . ' ' . $vform[4];

            if (empty($vform[0]) || empty($vcname) || empty($vform[5]) || empty($vform[22])) {
                echo "Please Enter Mandatory * Values";
                die();
            }

            $aorcode = $vform[0];
            $eino = $vform[22];

            $numRows = $model->checkExistingData($aorcode, $eino);
            if ($numRows > 0) {
                echo "Data already exists!!!";
                die();
            }

            $data = [
                'aor_code' => $aorcode,
                'cname' => $vcname,
                'cfname' => $vform[5],
                'pa_line1' => $vform[6],
                'pa_line2' => $vform[7],
                'pa_district' => $vform[8],
                'pa_pin' => $vform[9],
                'ppa_line1' => $vform[10],
                'ppa_line2' => $vform[11],
                'ppa_district' => $vform[12],
                'ppa_pin' => $vform[13],
                'dob' => date('Y-m-d', strtotime($vform[14])),
                'place_birth' => $vform[15],
                'nationality' => $vform[16],
                'cmobile' => $vform[17],
                'eq_x' => $vform[18],
                'eq_xii' => $vform[19],
                'eq_ug' => $vform[20],
                'eq_pg' => $vform[21],
                'eino' => $eino,
                'regdate' => date('Y-m-d', strtotime($vform[23])),
                'status' => 1,
                'updated_by' => $ucode,
                'create_modify' => date("Y-m-d H:i:s"),
                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by_ip' => getClientIP(),
            ];

            $model->insert1($data);
            $db = \Config\Database::connect();
            $transactionModel = $db->table('transactions');
            $transactionModel->insert([
                'acid' => $db->insertID(),
                'event_code' => $data['status'],
                'event_date' => $data['regdate'],
                'updated_by' => $ucode,
                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by_ip' => getClientIP()
            ]);

            echo "Record Successfully Inserted";
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
            die();
        }
    }


    private function getUserIP()
    {
        $client  = $this->request->getServer('HTTP_CLIENT_IP');
        $forward = $this->request->getServer('HTTP_X_FORWARDED_FOR');
        $remote  = $this->request->getServer('REMOTE_ADDR');

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            return $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            return $forward;
        } else {
            return $remote;
        }
    }

    public function modify_details()
    {
        $Model = new Model_record();
        $data['clerkDetails'] = $Model->getClerkDetails();
        return view('Record_room/modify_ac_form', $data);
    }


    public function modify()
    {
        $Model = new Model_record();
        $id = $this->request->getGet('id');
        $res = '';
        $val = $Model->getval($id);
        $data['val'] = $val;
        // echo "<pre>";
        // print_r($val);
        // exit();
        return view('Record_room/modify_ac_form1', $data);
    }



    public function getadv_name()
    {

        $model = new Model_record();
        $tvap = $this->request->getGet('tvap');
        $defects['result'] = $model->getadv_name1($tvap);
        // return view('Record_room/modify_ac_form1');
    }



    // modification update -----------------
    public function AorUpdate()
    {
        $sessionData = $this->session->get();
        $ucode = $sessionData['login']['usercode'];
        $tvap = $this->request->getGet('tvap');
        $id = $this->request->getGet('id');

        $wordChunks = explode(";", $tvap);

        for ($i = 0; $i < count($wordChunks); $i++) {
            $vform[$i] = str_replace("undefined", "", $wordChunks[$i]);
        }
       
        $vcname = $vform[2] . ' ' . $vform[3] . ' ' . $vform[4];
        try {
            $aorcode = $vform[0];
            $eino = $vform[22];
            $data = [
                'aor_code' => $aorcode,
                'cname' => trim($vcname),
                'cfname' => $vform[5],
                'pa_line1' => $vform[6],
                'pa_line2' => $vform[7],
                'pa_district' => $vform[8],
                'pa_pin' => (isset($vform[9]) && !empty($vform[9])) ? $vform[9] : 0,
                'ppa_line1' => !empty($vform[10]) ? $vform[10] : '',
                'ppa_line2' => !empty($vform[11]) ? $vform[11] : '',
                'ppa_district' => $vform[12],
                'ppa_pin' => (isset($vform[13]) && !empty($vform[13])) ? $vform[13] : 0,
                'dob' => $vform[14] != '' ? date('Y-m-d', strtotime($vform[14])) : NULL,
                'place_birth' => $vform[15],
                'nationality' => $vform[16],
                'cmobile' =>$vform[17] !='' ? $vform[17] : '0',
                'eq_x' => $vform[18],
                'eq_xii' => $vform[19],
                'eq_ug' => $vform[20],
                'eq_pg' => $vform[21],
                'eino' => $eino,
                'regdate' => date('Y-m-d', strtotime($vform[23])),
                'status' => 1,
                'updated_by' => $ucode,
                'create_modify' => date("Y-m-d H:i:s"),
                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by_ip' => getClientIP(),
                // 'modified_ip' => getClientIP(),
            ];
            
            $model = new Model_record();
            $model->updateAc($id, $data);
            echo "<script>alert('Record Successfully Updated')</script>";
        } catch (\Exception $e) {
            die("Error! Contact Administrator!!");
        }
    }

    public function renewal()
    {
        // $Model = new Model_record();
        return view('Record_room/renewal_cancel_ac_form');
    }



    public function getAorOptions()
    {
        $tvap = $this->request->getGet('tvap');
     
        $Model = new Model_record();
        $options1 = $Model->getaoroption($tvap);

        if (!empty($options1)) {
            $optionsHtml = '<select id="vadvc">';
            foreach ($options1 as $option) {
                $optionsHtml .= '<option  value=' . $option['aor_code'] . '>' . htmlspecialchars($option['aor_code']) . ' - ' . htmlspecialchars($option['name']) . '</option>';
            }
            $optionsHtml .= '</select>';
            return $this->response->setJSON(["success" => $optionsHtml]) ;
        } else {
            $select = '<select id="vadvc"><option value="">No data found</option></select>';
            return $this->response->setJSON(["error" => $select]);
        }
    }


    public function getAorOptions1()
    {
        $tvap = $this->request->getGet('tvap');
        $vadvc = $this->request->getGet('vadvc');
        $Model = new Model_record();

        try {
            $options = $Model->getaoroption1($tvap, $vadvc);
            if ($options) {
                return $this->response->setJSON($options);
            } else {
                return $this->response->setJSON(["error" => "No data found"]);
            }
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return $this->response->setJSON(["error" => "An internal error occurred"]);
        }
    }




    public function getAORsWithMoreClerks()
    {
        // error_reporting(0);
        $model = new Model_record();
        $data['model'] = $model;
        $data['records'] = $model->getAORsWithMoreClerks();

        foreach ($data['records'] as $record) {
            $data['clerks'][$record['aor_code']] = $model->getAORClerks($record['aor_code']);
        }

        $data['mergedData'] = array_map(function($record) use ($data) {
            $record['clerks'] = $data['clerks'][$record['aor_code']] ?? []; 
            return $record;
        }, $data['records']);
        return view('Record_room/reports/lst_aor_rep1', $data);
    }


    public function getCancelRecords()
    {
        $model = new Model_record();
        $data['records'] = $model->getCancelRecords();

        return view('Record_room/reports/lst_cancel_rec', $data);
    }



    public function duplicateRecords()
    {
        $model = new Model_record();
        $data['records'] = $model->getDuplicateRecords();
        $data['clerks'] = array();

        foreach ($data['records'] as $record) {
            $data['clerks'][$record['eino']] = $model->getClerksAttachedWithAORs($record['eino']);
        }
        $mergedData = array_merge_recursive($data['records'], $data['clerks']);
        $data['mergedData'] = $mergedData;
        // echo $this->db->getLastQuery();
        // print_r($data['mergedData']);die;
        return view('Record_room/reports/lst_dup_rec', $data);
    }




    public function aorDetails()
    {
        $model = new Model_record();
        $data['records'] = $model->getAORDetails();

        // echo "<pre>"; print_r($data); exit;
        return view('Record_room/reports/lst_aor_rep', $data);
    }



    public function clerkReport()
    {
        $Model = new Model_record();

        $data = [
            'clerkDetails' => $Model->getClerkDetails(),
        ];
        return view('Record_room/reports/lst_clerk_rep', $data);
    }



    public function lst_clerk_rep1()
    {
        $model = new Model_record();
        $records = $model->getClerksWithMoreThan2AORs();
        $data['clerks'] = array();
        foreach ($records as $record) {
            $data['clerks'][$record['eino']] = $model->getClerkDetailsByEino($record['eino']);
        }
        $data['records'] = $records;
        $mergedData = array_merge_recursive($data['records'], $data['clerks']);
        $data['mergedData'] = $mergedData;
        return view('Record_room/reports/lst_clerk_rep1', $data);
    }

    public function rr_user_mgmt_view()
    {
        $sessionData = $this->session->get();
        $ucode = $sessionData['login']['usercode'];
        $model = new Model_record();
        $data['records'] = $model->getdept();
        $data['user'] = $model->getuser($ucode);
        $data['usertype_row'] = $model->getUserByCode($ucode);
        $data['dept'] = $model->getUsersData();
        //   echo "<pre>"; print_r($data); exit();

        return view('Record_room/file_movement/rr_user_mgmt_view', $data);
    }

    public function rr_view_user_information_hall()
    {
        $currentYear = date("Y");
        $model = new Model_record();
        $allotmentCategory = $this->request->getPost('allotmentCategory');
        $data['view_rs'] = $model->getRefHallData();
        $data['model'] = $model;
        return view('Record_room/file_movement/rr_view_user_information_hall', $data);
    }

    public function rr_user_mgmt_multiple()
    {
        $model = new Model_record();
        $data['model'] = $model;
        $data['keyValue'] = $this->request->getGet('key') != '' ? $this->request->getGet('key') : '';
        $data['setter'] = $this->request->getGet('setter');
        $data['cur_user_type'] = $this->request->getGet('cur_user_type');
        $data['deptname'] = $this->request->getGet('deptname');
        $data['section'] = $this->request->getGet('section');
        $data['deptValue'] = $this->request->getGet('dept');
        $data['ltypeValue'] = $this->request->getGet('ltype');
        $data['f_auth'] = $this->request->getGet('f_auth');
        $data['fil_t'] = $this->request->getGet('fil_t');
        $data['totalValue'] = $this->request->getGet('total');
        $data['chk_code'] = $this->request->getGet('chk_code');
        $data['userValue'] = $this->request->getGet('user');
        $data['serviceValue'] = $this->request->getGet('service');
        $data['empid'] = $this->request->getGet('empid');
        $data['userid'] = $this->request->getGet('userid') != '' ? $this->request->getGet('userid') : '';
        $data['statusValue'] = $this->request->getGet('status');
        $data['utypeValue'] = $this->request->getGet('utype');
        $data['utypeValue'] = $this->request->getGet('utype');
        $data['empname'] = $this->request->getGet('empname');
        $data['judge'] = $this->request->getGet('judge');
        $data['authValue'] = $this->request->getGet('auth');
        $data['aname'] = $this->request->getGet('aname');
        $data['da_code'] = $this->request->getGet('da_code');
        $data['rkds_code'] = $this->request->getGet('rkds_code');
        $data['rkdcmpda_code'] = $this->request->getGet('rkdcmpda_code');
        $data['hall_no'] = $this->request->getGet('hall_no') != '' ? $this->request->getGet('hall_no') : '';

        $data['usercode'] = session()->get('login')['usercode'];
        // echo "<pre>";
        // print_r($data);die;
        return view('Record_room/file_movement/rr_user_mgmt_multiple', $data);
    }

    public function rr_view_user_information()
    {
        $model = new Model_record();
        $data['model'] = $model;
        $data['dept'] = $this->request->getVar('dept');
        $data['secValue'] = $this->request->getVar('sec');
        $data['desg'] = $this->request->getVar('desg');
        $data['cur_user_type'] = $this->request->getVar('cur_user_type');
        $data['allotmentCategory'] = $this->request->getVar('allotmentCategory');
        $data['jud_sel'] = $this->request->getVar('jud_sel') != '' ? $this->request->getVar('jud_sel') : '';
        $data['orderjud'] = $this->request->getVar('orderjud') != '' ? $this->request->getVar('orderjud') : '';
        $data['view_sta'] = $this->request->getVar('view_sta') != '' ? $this->request->getVar('view_sta') : '';
        $data['auth_name'] = $this->request->getVar('auth_name') != '' ? $this->request->getVar('auth_name') : '';
        $data['authValue'] = $this->request->getVar('auth') != '' ? $this->request->getVar('auth') : '';
        $data['auth_sel_name'] = $this->request->getVar('auth_sel_name') != '' ? $this->request->getVar('auth_sel_name') : '';
        $data['usercode'] = session()->get('login')['usercode'];
        
        return view('Record_room/file_movement/rr_view_user_information', $data);
    }

    public function lst_aor_search1()
    {
        $Model = new Model_record();

        $sessionData = session()->get();
        $ucode = $sessionData['login']['usercode'];

        $tvap = $this->request->getGet('tvap');

        $wordChunks = explode(";", $tvap);
        for ($i = 0; $i < count($wordChunks); $i++) {
            $vform[$i] = str_replace("undefined", "", $wordChunks[$i]);
        }
        $tvap = $vform[0];
        $aorn = $vform[1];

        if (!$tvap) {
            echo "Please Enter AOR Code";
            die(" ");
        }

        $clerks = $Model->getClerkDetails1($tvap);
        $transactions = [];
        $data['model'] = $Model;

        $data['clerks'] = $clerks;
        $data['aorn'] = $aorn;
        $data['tvap'] = $tvap;
        return view('Record_room/search/lst_aor_search_details', $data);

        exit;
    }



    public function lst_aor_search()
    {
        $sessionData = $this->session->get();
        $ucode = $sessionData['login']['usercode'];
        $model = new Model_record();
        $data['records'] = $model->getdept();
        $data['user'] = $model->getuser($ucode);

        //   echo "<pre>"; print_r($data); exit();

        return view('Record_room/search/lst_aor_search', $data);
    }



    public function lst_clerk_search()
    {
        $model = new Model_record();
        $records = $model->getClerksWithMoreThan2AORs();

        $data['clerks'] = array();


        foreach ($records as $record) {
            $data['clerks'][$record['eino']] = $model->getClerkDetailsByEino($record['eino']);
        }
        $data['records'] = $records;
        $mergedData = array_merge_recursive($data['records'], $data['clerks']);
        $data['mergedData'] = $mergedData;

        //   echo "<pre>"; print_r($data); exit();

        return view('Record_room/search/lst_clerk_search', $data);
    }



    public function lst_clerk_search1()
    {
        $Model = new Model_record();

        $sessionData = session()->get();
        $ucode = $sessionData['login']['usercode'];

        $tvap = $this->request->getGet('tvap');

        $wordChunks = explode(";", $tvap);
        for ($i = 0; $i < count($wordChunks); $i++) {
            $vform[$i] = str_replace("undefined", "", $wordChunks[$i]);
        }
        $tvap = $vform[0];
        $aorn = $vform[1];

        if (!$tvap) {
            echo "Please Enter AOR Code";
            die(" ");
        }
        $records = $Model->getclerk($tvap);
        $data['model'] = $Model;
        $data['clerks'] = $records;
        $data['aorn'] = $aorn;
        $data['tvap'] = $tvap;




        return view('Record_room/search/lst_clerk_search_details', $data);

        exit;
    }
    public function getclerk_name()
    {
        $model = new Model_record();
        $tvap = $this->request->getGet('tvap');
        $defects['result'] = $model->getclerk1($tvap);

        return $defects['result'];

        // print_r($defects['result']);exit;

        // return view('Record_room/Record/lst_clerk_search',$defects);
    }


    // code by sandeep

    // joint registration  view --------------
    public function joint_ac_form()
    {
        return view('Record_room/joint_ac_form');
    }



    // joint registration filter ------------
    public function join_ac_search()
    {
        $tvap = $this->request->getGet('tvap');
        if (empty($tvap)) {
            echo "No data found.";
            return;
        }

        $acModel = new Model_record();
        $result = $acModel->getData($tvap);

        if ($result) {
            echo $result['result'];
        } else {
            echo "No data found.";
        }
    }


    //  joint registration register code -------------
    public function ac_register1()
    {
        $tvap = $this->request->getGet('tvap');
        $wordChunks = explode(";", $tvap);
        $vform = array_map(fn($chunk) => str_replace("undefined", "", $chunk), $wordChunks);
       
        $sessionData = session()->get();
        $ucode = $sessionData['login']['usercode'];

        $vcname = $vform[2] . ' ' . $vform[3] . ' ' . $vform[4];

        if (empty($vform[0]) || empty($vcname) || empty($vform[5]) || empty($vform[21])) {
            return $this->response->setJSON(['error' => 'Please Enter Mandatory *  Values']);
        }

        $acModel = new Model_record();

        if ($acModel->checkExistingData($vform[0], $vform[21])) {
            return $this->response->setJSON(['error' => 'Data already exists !!!']);
        }

        $data = [
            'aor_code' => $vform[0],
            'cname' => $vcname,
            'cfname' => $vform[5],
            'pa_line1' => $vform[6],
            'pa_line2' => $vform[7],
            'pa_district' => $vform[8],
            'pa_pin' => $vform[9],
            'ppa_line1' => $vform[10],
            'ppa_line2' => $vform[11],
            'ppa_district' => $vform[12],
            'ppa_pin' => $vform[13],
            'dob' => date('Y-m-d', strtotime($vform[14])),
            'place_birth' => $vform[15],
            'nationality' => $vform[16],
            'eq_x' => $vform[17],
            'eq_xii' => $vform[18],
            'eq_ug' => $vform[19],
            'eq_pg' => $vform[20],
            'eino' => $vform[21],
            'cmobile' => $vform[23],
            'regdate' => date('Y-m-d', strtotime($vform[22]))
        ];
        $acModel->getInsertData($data);        
        $db = \Config\Database::connect();
        $query = $db->query("SELECT id, status, regdate FROM ac WHERE aor_code = ? AND eino = ?", [$vform[0], $vform[21]]);
        $result = $query->getRowArray();

        if ($result) {
            $event_code = $result['status'];
        } else {
            return $this->response->setJSON(['error' => 'No matching record found in the database.']);
        }

        $db = \Config\Database::connect();
        $transactionModel = $db->table('transactions');
        $transactionModel->insert([
            'acid' => $db->insertID(),
            'event_code' => $event_code,
            'event_date' => $data['regdate'],
            'updated_by' => $ucode,
            'updated_on' => date("Y-m-d H:i:s"),
            'updated_by_ip' => getClientIP()
        ]);
        return $this->response->setJSON(['success' => 'Registered Successfully !!']);
    }


    public function fetchTds()
    {
        $tid = $this->request->getGet('tid');
        $model = new TransactionModel();

        try {
            $result = $model->getTransactionDetails($tid);
            
            if ($result) {
                foreach ($result as $row) {
                    echo $row['temp'] . "<br>";
                }
            } else {
                echo "Record Not found";
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }




    public function RenewalRegister()
    {

        $sessionData = session()->get();
        $ucode = $sessionData['login']['usercode'];
        // $tvap= "2;19-03-2025;Renew SKD_257 19-03-2025;6033";
        $tvap = $this->request->getPost('tvap');
        $wordChunks = explode(';', $tvap);
        $vform = array_map(
            function ($item) {
                return str_replace('undefined', '', $item);
            },
            $wordChunks
        );
       

        if (!$vform[0] || !$vform[1] || !$vform[3]) {
            return $this->response->setJSON(['error' => 'Please Enter Mandatory * Values']);
        }

        try {
            $model = new TransactionModel();

            $existingRecord = $model->where('acid', $vform[3])->where('event_code', $vform[0])->countAllResults();
            
            if ($existingRecord > 0) {
                return $this->response->setJSON(['error' => 'Data already exists !!!']);
            }

            $data = [
                'acid' => $vform[3],
                'event_code' => $vform[0],
                'event_date' => date('Y-m-d', strtotime($vform[1])),
                'remarks' => $vform[2],
                'updated_by' => $ucode,
                'updated_by_ip' => getClientIP(),
            ];
       
            $query_ia_log = insert('transactions', $data);
            if ($query_ia_log) 
                return $this->response->setJSON(['success' => 'Transaction Registered Successfully !!']);
            else
            return $this->response->setJSON(['error' => 'Error! Contact Administrator !!']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => $e->getMessage()]);
        }
    }

    public function ripe_cases()
    {
        $model = new Model_record();
        header('Access-Control-Allow-Origin: *');
        $data['ripeCasesReports'] = '';
        $data['app_name'] = '';

        $data['param'] = '';
        if ($_POST && !empty($_POST['fromDate']) && !empty($_POST['toDate']) && !empty($_POST['reportType'])) {
            $fromDate = !empty($_POST['fromDate']) ? date('Y-m-d', strtotime($_POST['fromDate'])) : null;
            $toDate = !empty($_POST['toDate']) ? date('Y-m-d', strtotime($_POST['toDate'])) : null;
            //TODO : hall_no and reportype UI html component need to add in the UI and by default the cases related to hall no will
            $reportType = $_POST['reportType'];
            $hall_no = !empty($_POST['hall_no']) ? $_POST['hall_no'] : null;
            if (!empty($reportType)) {

                if ($this->checkIsValidDateRange($fromDate, $toDate)) {
                    if ($reportType == 1 or ($reportType == 2 and !is_null($hall_no))) {
                        $noOfDays = !empty($_POST['noOfDays']) ? ($_POST['noOfDays']) : null; // default set 1 year old cases
                        $offset = !empty($_POST['offset']) ? ($_POST['offset']) : 1;
                        $this->RipeCasesReportAjax($fromDate, $toDate, $reportType, $hall_no, $noOfDays, $offset);
                    } else {

                        echo  '<span style="color: red">Please select Hall Number</span>';
                    }
                } else {

                    session()->setFlashdata('msg', '<div class="alert alert-danger text-center">Date interval cannot be exceeded by 1 year (365 days)</div>');
                    //redirect("Record_keeping/RipeCasesReport");
                }
            }
        } else {
            return view('Record_room/reports/ripe_cases',  $data);
        }
    }

    private function checkIsValidDateRange($from_date, $to_date)
    {
        $diff = date_diff(date_create($from_date), date_create($to_date));
        return (intval($diff->format("%R%a days")) <= 365 && intval($diff->format("%R%a days")) >= 0) ? true : false;
    }


    public function RipeCasesReportAjax($fromDate = null, $toDate = null, $reportType = null, $hall_no = null, $noOfDays = null, $offset = null)
    {
        // error_reporting(0);
        if (!empty($fromDate) && !empty($toDate) && !empty($reportType)) {

            /* var_dump($this->data['ripeCasesReports']);*/

            $total_disposed_non_weeded_cases_found = (!empty($data['ripeCasesReports'])) ? count($data['ripeCasesReports']) : null;

            if ($reportType == 1) {
                $data['ripeCasesReports'] = $this->Record_keeping_model->get_ripe_cases_report($fromDate, $toDate, $noOfDays, $offset);
                $data['app_name'] = 'ripeCases';
                $data['param'] = array($fromDate, $toDate, $total_disposed_non_weeded_cases_found);

                //  pr($data);
                return view('Record_room/reports/ripe_cases_report_ajax', $data);
            } else {
                $data['ripeCasesReports'] = $this->Record_keeping_model->get_ripe_cases_report_hallwise($fromDate, $toDate, $hall_no);
                $data['app_name'] = 'ripeCasesHallwise';
                $data['param'] = array($fromDate, $toDate, $total_disposed_non_weeded_cases_found);
                return view('Record_room/reports/ripe_cases_report_hallwise_ajax', $data);
            }
        }
    }
}
