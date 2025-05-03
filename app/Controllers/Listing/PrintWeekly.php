<?php

namespace App\Controllers\Listing;

use App\Controllers\BaseController;
use App\Models\Listing\Heardt;
use App\Models\Listing\PrintModel;
use Mpdf\Mpdf;

class PrintWeekly extends BaseController
{

    public $diary_no;
    protected $Heardt;
    protected $fetch_api_response;
    protected $db;
    public $PrintModel;

    function __construct()
    {

        $this->Heardt = new Heardt();
        $this->db = \Config\Database::connect();
        $this->PrintModel = new PrintModel();
    }

   private function fetch_api_response($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return 'cURL Error: ' . curl_error($ch);
        }
        curl_close($ch);
        return $response;
    }

    public function cl_print_verify_weekly()
    {
        $request = service('request');
        $data['dates']  = $this->Heardt->getListDates();

        return view('Listing/print_advance/cl_print_verify_weekly', $data);
    }

    public function cl_print_unpublish_wk()
    {
        $session = session();
        $request = service('request');
        
        // Retrieve user session ID
        $ucode = $session->get('dcmis_user_idd') ?? $session->get('login')['usercode'];
        
        // Get posted form data
        $list_dt = date('Y-m-d', strtotime($request->getPost('list_dt')));
        $list_dt_to = date('Y-m-d', strtotime($request->getPost('list_dt_to')));
        $courtno = $request->getPost('courtno');

        if (!$list_dt || !$list_dt_to || !$courtno) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid input data']);
        }

        // Define paths
        $timestamp = date('Y-m-d_H-i-s');
        $baseDir = WRITEPATH . "judgment/causelist/{$list_dt}_{$list_dt_to}/";
        $unpublishedDir = $baseDir . "Unpublished/";

        // Ensure directory exists
        if (!is_dir($unpublishedDir)) {
            mkdir($unpublishedDir, 0777, true);
        }

        // Define file paths
        $fileMappings = [
            "html"  => "{$courtno}.html",
            "pdf"   => "{$courtno}.pdf",
            "json"  => "{$courtno}.json",
            "weekly_html" => "weekly.html",
            "weekly_pdf"  => "weekly.pdf",
            "weekly_json" => "weekly.json"
        ];

        // Move files if they exist
        foreach ($fileMappings as $type => $filename) {
            $oldPath = $baseDir . $filename;
            $newPath = $unpublishedDir . ($type === 'weekly_html' || $type === 'weekly_pdf' || $type === 'weekly_json' ? "weekly_{$timestamp}.{$type}" : "{$courtno}_{$timestamp}.{$type}");

            if (file_exists($oldPath)) {
                rename($oldPath, $newPath);
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Cause List Un-Published Successfully. Please Publish / Merge again.'
        ]);
    }

    public function cl_print_save_wk()
    {
        $session = session();
        $request = service('request');

        // Retrieve user session ID
        $ucode = $session->get('dcmis_user_idd') ?? $session->get('login')['usercode'];

        // Get posted form data
        $list_dt = date('Y-m-d', strtotime($request->getPost('list_dt')));
        $list_dt_to = date('Y-m-d', strtotime($request->getPost('list_dt_to')));
        $courtno = $request->getPost('courtno');

        if (!$list_dt || !$list_dt_to || !$courtno) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid input data']);
        }

        // Get the base URL
        $base_url = base_url(); 

        // Replace "scilogo.png" with the full URL
        $pdf_cont = str_replace("scilogo.png", 'scilogo.png', $request->getPost('prtContent'));

        // Get the writable path for storing files (use WRITEPATH for CI writable folder)
        $file_path = $courtno;
        $path_dir = WRITEPATH . "wk/{$list_dt}_{$list_dt_to}/";  // Store in writable folder
        
        if (!file_exists($path_dir)) {
            mkdir($path_dir, 0777, true);  // Create the directory if it doesn't exist
        }

        $data_file = $path_dir . $file_path . ".html";
        $data_file1 = $path_dir . $file_path . ".pdf";
        
        // Initialize mPDF
        $mpdf = new \Mpdf\Mpdf();

        // Set mPDF settings
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->showImageErrors = true;

        // Write the HTML content to mPDF
        $mpdf->WriteHTML($pdf_cont);

        // Define file path in writable folder
        $path_dir = WRITEPATH . "wk/{$list_dt}_{$list_dt_to}/";
        if (!file_exists($path_dir)) {
            mkdir($path_dir, 0777, true);  // Create directory if it doesn't exist
        }

        // Define the output PDF file path
        $data_file1 = $path_dir . $courtno . '.pdf';

        // Output PDF to the file
        $mpdf->Output($data_file1, \Mpdf\Output\Destination::FILE);  // Save as a file in the writable folder

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Cause List Ported/Published Successfully.',
            'file_path' => $data_file1  // Optionally, return the file path to the frontend
        ]);
    }





    public function get_causelist_weekly_verify()
    {
        $request = service('request');
        $session = session();

        // Get user code from session
        $ucode = $session->get('dcmis_user_idd') ?? $session->get('login')['usercode'];

        // Validate and sanitize input
        //For testing
         $list_dt = date('Y-m-d', strtotime('2025-03-25'));
         $list_dt_to = date('Y-m-d', strtotime('2025-03-25'));
        //For Production
        //$list_dt = date('Y-m-d', strtotime($request->getPost('list_dt')));
        //$list_dt_to = date('Y-m-d', strtotime($request->getPost('list_dt_to')));
        $mainhead = 'F';//$request->getPost('mainhead');
        $courtno = 1;//$request->getPost('courtno');

        // Prepare data for view
        $data = [
            //For Testing
            // 'list_dt' => '2023-02-06',
            // 'list_dt_to' => '2023-03-27',
            //For Production
            'list_dt' => $list_dt,
            'list_dt_to' => $list_dt_to,
            'mainhead' => $mainhead,
            'courtno' => $courtno,
            'part_no' => 1,
            'psrno' => 1,
            'clnochk' => 0,
            'subheading_rep' => 0,
            'subheading' => '',
            'mnhead_print_once' => 1,
            'ucode' => $ucode
        ];

        // Fetch cases and related details
        $data['getCases'] = $this->Heardt->getCasesListWeek($list_dt, $courtno);
        //pr($data['getCases']);
        if (!empty($data['getCases'])) {
            foreach ($data['getCases'] as &$case) {
                $case['details'] = $this->Heardt->getCaseDetailsWeekly($list_dt, $list_dt_to, $mainhead, $courtno, $case['jcd']);
                //pr($case['details']);
                if (!empty($case['details'])) {
                    foreach ($case['details'] as &$case) {
                        $case['advocate'] = $this->Heardt->get_advocate_detailsWeekly($case['diary_no']);
                    
                        $case['tentativeSec'] = $this->Heardt->get_tentative_sectionWeekly($case['diary_no']);
                      
                        $case['lowerCourtDt'] = $this->Heardt->get_lower_court_detailsWeekly($case['diary_no']);

                    }
                }
            }
        }

        return view('Listing/print_advance/get_causelist_weekly_verify_v2', $data);
    }

    public function cl_print_save_wk_merge()
    {
        $session = session();
        $request = service('request');

        // Retrieve user session ID
        $ucode = $session->get('dcmis_user_idd') ?? $session->get('login')['usercode'];

        // Get posted form data
        $list_dt = date('Y-m-d', strtotime($request->getPost('list_dt')));
        $list_dt_to = date('Y-m-d', strtotime($request->getPost('list_dt_to')));

        if (!$list_dt || !$list_dt_to) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid input data']);
        }

        // Define writable path for storing files
        $path_dir = WRITEPATH . "judgment/cl/wk/{$list_dt}_{$list_dt_to}/";

        // Ensure the directory exists
        if (!file_exists($path_dir)) {
            mkdir($path_dir, 0777, true);
        }

        // Scan and sort files
        $files = array_values(array_diff(scandir($path_dir), ['.', '..']));
        natsort($files);

        $pdf_cont = ''; // Initialize PDF content string

        foreach ($files as $file) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);

            if ($ext === 'html' && !in_array($file, ['weekly.html', 'weekly2.html'])) {
                $pdf_cont .= "<div style='page-break-after:always'>";
                $pdf_cont .= file_get_contents($path_dir . '/' . $file);
                $pdf_cont .= "<br/></div>";
            }
        }

        // Define output PDF file path
        $data_file1 = $path_dir . "weekly.pdf";

        // Generate PDF using mPDF
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->showImageErrors = true;
        $mpdf->WriteHTML($pdf_cont);
        $mpdf->Output($data_file1, \Mpdf\Output\Destination::FILE);

        // Merge JSON files
        $mergedData = [];

        foreach ($files as $file) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);

            if ($ext === 'json' && !in_array($file, ['weekly.json', 'weekly2.json'])) {
                $jsonContent = file_get_contents($path_dir . '/' . $file);
                $jsonData = json_decode($jsonContent, true);

                if (is_array($jsonData)) {
                    $mergedData = array_merge($mergedData, $jsonData);
                }
            }
        }

        // Save merged JSON
        $mergedJson = json_encode($mergedData, JSON_PRETTY_PRINT);
        file_put_contents($path_dir . 'weekly.json', $mergedJson);

        // Send SMS Notification
        date_default_timezone_set('Asia/Kolkata');
        $causelist_title = "Weekly List dated " . $_POST['list_dt'];
        $sms_text = rawurlencode($causelist_title . " has been published on www.sci.gov.in at " . date("d-m-Y H:i:s") . " - Supreme Court Of India");

        //For Testing
        // $sms_url = "http://xxxx/eAdminSCI/a-push-sms-gw?mobileNos=8756413330&message={$sms_text}&typeId=29&myUserId=NIC001001&myAccessId=root&authCode=sdjkfgbsjh$1232_12nmnh&templateId=1107161578957835848";
        
        //For Pduction

        /* $sms_url='http://xxxx/eAdminSCI/a-push-sms-gw?mobileNos='.'9630100950,9810884595,9319170909,9821411915,9868069855,9718009598,9818782386,9910727768,9968281944,9968319828,9968811042,9971685090,9999100724,9312570277,9910431438,9643323531,7838900365,8800307859,8800928316,9810855890,9711475023,9711475578,9810263541,9810464620,9810481741,9810485122,9810506860,9810594145,9811471402,9811904000,9818617598,9868186878,9868200903,9868216440,9868280279,9868281372,9868631191,9868996564,9811316333,9871922703,9868207383,9899016720,9899249150,9899518586,9899924364,9910431438,9911675788,9968281944,9968319828,9968811042,9971685090,8860012863,9810267531'.'&message='.$sms_text.'&typeId=29&myUserId=NIC001001&myAccessId=root&authCode=sdjkfgbsjh$1232_12nmnh&templateId=1107161578957835848';

        $sms_response = file_get_contents($sms_url);
        $sms_response = $this->fetch_api_response($sms_url);
        $json = json_decode($sms_response); 

        if ($sms_response === false || empty($json)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'SMS API request failed. Please check the API URL and internet connection.'
            ]);
        }elseif ($json->{'responseFlag'} === "success") {
            $sms_status = "Success: Causelist Uploaded alert SMS sent.";
        } else {
            $sms_status = "Error: Causelist Uploaded alert SMS could not be sent.";
        } */
          

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Weekly List Ported/Published Successfully.',
            'file_path' => base_url("writable/judgment/cl/wk/{$list_dt}_{$list_dt_to}/weekly.pdf"),
            'sms_status' => 'Error: Causelist Uploaded alert SMS could not be sent.'
        ]);
    }


    

    public function wk_cl_print(){
        $data['dates']  = $this->Heardt->getListDates();
        return view('Listing/print_advance/wk_cl_print', $data);
    }

    public function cl_print_verify(){
        $request = service('request');
        $data['listingDates']  = $this->Heardt->getListingDatesMV1();
        $data['benches'] = $this->Heardt->getBenchJudges();
        return view('Listing/print_advance/cl_print_verify', $data);
    }

    public function get_cl_print_benches()
    {
        $request = service('request');
        $mainhead = $request->getPost('mainhead');
        $list_dt = $request->getPost('list_dt');
        $board_type = $request->getPost('board_type');
        $data['judge_list'] = $this->Heardt->getBenchJudges1($mainhead, $list_dt, $board_type);
        return view('Listing/drop_note/get_cl_print_benches', $data);
    }

    public function get_cl_print_mainhead()
    {
        $request = service('request');
        $mainhead = $request->getPost('mainhead');
        $board_type = $request->getPost('board_type');
        $dates = $this->Heardt->getClPrintMainhead1($mainhead, $board_type);
      
        return $this->response->setJSON($dates);
    }


    public function get_cause_list_verify()
    {
        $request = service('request');
        $postData = $request->getPost();
        $mainhead = $request->getPost('mainhead');
        $list_dt = $request->getPost('list_dt');
        if($list_dt == -1){
            $list_dt =0;
        }
        
        $part_no = $request->getPost('part_no');
        $board_type = $request->getPost('board_type');
        $request = service('request');
        $jud_ros = $request->getPost('jud_ros');
        
        $judges_id = $roster_id = 0;
        if (!empty($jud_ros)) {
            $jud_ros_array = explode("|", $jud_ros);
            if (isset($jud_ros_array[0]) && isset($jud_ros_array[1])) {
                $judges_id = $jud_ros_array[0];
                $roster_id = $jud_ros_array[1];
            }
        }

        if ($jud_ros == 0) {
            $part_no = 1;
        } else {
            $part_no = $request->getPost('part_no');
        }

        
        /*$verifyDetails = $this->Heardt->getHeardtCLVerify($list_dt, $mainhead, $board_type, $roster_id);
        foreach($verifyDetails as $key => $row_ros){
            $jud_ros = explode("|",$row_ros['rsdf']);
            $roster_id = $jud_ros[1];
            $judges_id = $jud_ros[0];
            $list_dt = date('Y-m-d', strtotime($list_dt));
            //$post_listdt = $_POST['list_dt'];
            $getDetails = $this->Heardt->getRosterDetails($list_dt, $mainhead, $board_type, $roster_id, $part_no);
            $verifyDetails[$key]['verifyDetails'] = $getDetails;
        }
        
        $data['records'] = $verifyDetails;*/
        $data['list_dt'] = $list_dt;
        $data['mainhead'] = $mainhead;
        $data['roster_id'] = $roster_id;
        $data['part_no'] = $part_no;
        $data['rosterDetails'] = $this->Heardt->getRosterDetails($list_dt, $mainhead, $board_type, $roster_id, $part_no);
        
        $data['benchJudges'] = $this->Heardt->getJudgesDetails($roster_id);
        $data['printCourtNo'] = isset($data['benchJudges']['courtno']) ? $this->getCourtNumber($data['benchJudges']['courtno']) : '';
        $data['printModel'] = $this->PrintModel;
        return view('Listing/print_advance/get_cause_list_verify', $data);
    }



    private function getCourtNumber($courtNo)
    {
        switch ($courtNo) {
            case "1":
                return "CHIEF JUSTICE'S COURT";
            case "21":
                return "Registrar Court";
            case "22":
                return "Registrar Court No. 2";
            case "61":
                return "Registrar Virtual Court No. 1";
            case "62":
                return "Registrar Virtual Court No. 2";
            case "31":
                return "Virtual Court No. 1";
            case "22":
                return "Registrar Court No. 2";
            case "22":
                return "Registrar Court No. 2";
            case "22":
                return "Registrar Court No. 2";
            case "22":
                return "Registrar Court No. 2";
            case "22":
                return "Registrar Court No. 2";
            case "22":
                return "Registrar Court No. 2";

            default:
                return "COURT NO.: $courtNo";
        }
    }

    public function call_reshuffle_function()
    {
        $request = service('request');
        $list_dt = $request->getPost('list_dt');
        $mainhead = $request->getPost('mainhead');
        $part_no = $request->getPost('part_no');
        $jud_ros = explode("|", $request->getPost('jud_ros'));
        $judge_id = $jud_ros[0];
        $roster_id = $jud_ros[1];
        $from_cl_no = $request->getPost('from_cl_no');

        if ($this->Heardt->isPrinted($list_dt, $part_no, $mainhead, $roster_id)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Cause List already Printed. You cannot Reshuffle']);
        }

        if ($from_cl_no > 0) {
            $result = $this->Heardt->reshuffleFromDesiredNo($list_dt, $judge_id, $mainhead, $part_no, $roster_id, $from_cl_no);
        } else {
            $result = $this->Heardt->reshuffle($list_dt, $judge_id, $mainhead, $part_no, $roster_id);
        }

        if ($result) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Reshuffled Successfully']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: Reshuffling Failed']);
        }
    }

    public function get_cl_print_partno()
    {
        $request = service('request');
        $list_dt = $request->getPost('list_dt');
        $mainhead = $request->getPost('mainhead');
        $jud_ros = explode("|", $request->getPost('jud_ros'));
        $roster_id = $jud_ros[1];
        $board_type = $request->getPost('board_type');
        
        // Get the part numbers from the model
        $partNumbers = $this->Heardt->get_cl_print_partno($mainhead, $list_dt, $roster_id, $board_type);
        return $this->response->setJSON($partNumbers);
    }




    public function cl_print_save()
    {
        $session = session();
        $request = service('request');
        $ucode = $session->get('dcmis_user_idd');
        $list_dt = $request->getPost('list_dt');
        $mainhead = $request->getPost('mainhead');
        $part_no = $request->getPost('part_no');
        $board_type = $request->getPost('board_type');
        $jud_ros = explode("|", $request->getPost('jud_ros'));
        $judge_id = $jud_ros[0];
        $roster_id = $jud_ros[1];
        $cntt = base64_encode($request->getPost('prtContent'));
        $pdf_cont = str_replace("scilogo.png", "/home/judgment/cl/scilogo.png", $request->getPost('prtContent'));


        if ($this->Heardt->isPrinted($list_dt, $part_no, $mainhead, $roster_id)) {
            return $this->response->setJSON(['message' => 'Already Printed.']);
        }

        // Handle Holidays and Update Heardt Table
        $this->handleHolidaysAndUpdateHeardt($list_dt, $part_no, $mainhead, $roster_id, $board_type, $ucode);

        // Get Min and Max Brd No
        $min_max = $this->Heardt->isPrinted->getMinMaxBrdNo($list_dt, $part_no, $mainhead, $roster_id);
        $min_brd = $min_max['min_brd_no'];
        $max_brd = $min_max['max_brd_no'];
        $main_supp_flag = $min_max['main_supp_flag'];

        // Save Printed Record
        $this->Heardt->isPrinted->insert([
            'next_dt' => $list_dt,
            'part' => $part_no,
            'main_supp' => $main_supp_flag,
            'm_f' => $mainhead,
            'roster_id' => $roster_id,
            'from_brd_no' => $min_brd,
            'to_brd_no' => $max_brd,
            'usercode' => $ucode,
            'ent_time' => date('Y-m-d H:i:s'),
            'user_ip' => getenv("REMOTE_ADDR")
        ]);

        // Save Text
        $inserted_id = $this->db->insertID();
        $this->Heardt->isPrinted->saveClText($inserted_id, $cntt, $ucode);

        // File Operations
        $this->generateFile($mainhead, $board_type, $main_supp_flag, $part_no, $roster_id, $pdf_cont);

        return $this->response->setJSON(['message' => 'Cause List Ported/Published Successfully.']);
    }

    private function handleHolidaysAndUpdateHeardt($list_dt, $part_no, $mainhead, $roster_id, $board_type, $ucode)
    {
        // Handle logic for holidays and updating the heardt table
        $sql_holiday = "SELECT * FROM holidays WHERE hname LIKE '%Summer Vacation%' AND hdate = ?";
        $query_holiday = $this->db->query($sql_holiday, [$list_dt]);

        if ($query_holiday->getNumRows() == 0) {
            if ($mainhead === 'F') {
                $this->db->query("UPDATE heardt SET coram = SUBSTRING_INDEX(judges, ',', 1) WHERE next_dt = ? AND clno = ? AND brd_slno > 0 AND mainhead = ? AND roster_id = ? AND board_type = 'J' AND (main_supp_flag = 1 OR main_supp_flag = 2)", [$list_dt, $part_no, $mainhead, $roster_id]);
            } else {
                $this->updateHeardtForMainHead($list_dt, $part_no, $mainhead, $roster_id);
            }
        }

        // Handle board type updates
        if ($board_type === 'R') {
            $this->handleBoardTypeR($list_dt, $part_no, $mainhead, $roster_id, $ucode);
        }
    }

    private function updateHeardtForMainHead($list_dt, $part_no, $mainhead, $roster_id)
    {
        // Update heardt with multiple conditions
        $this->db->query("UPDATE heardt h, main m SET h.coram = IF(m.casetype_id IN (39,19,20,34,35) OR m.active_casetype_id IN (39,19,20,34,35), h.judges, SUBSTRING_INDEX(h.judges, ',', 1)), h.list_before_remark = 15 WHERE h.diary_no = m.diary_no AND h.next_dt = ? AND h.clno = ? AND h.brd_slno > 0 AND h.subhead != 850 AND h.subhead != 817 AND h.list_before_remark != 11 AND h.mainhead = ? AND h.roster_id = ? AND h.board_type = 'J' AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2) AND (h.coram = '' OR h.coram = 0 OR h.coram IS NULL)", [$list_dt, $part_no, $mainhead, $roster_id]);

        // Process main cases
        $sql_main_case = "SELECT m.conn_key, h.coram FROM heardt h INNER JOIN main m ON m.diary_no = h.diary_no WHERE m.c_status = 'P' AND m.diary_no = m.conn_key AND h.roster_id = ? AND h.next_dt = ? AND h.clno = ? AND h.brd_slno > 0 AND h.mainhead = 'M' AND h.board_type = 'J'";
        $query_main_case = $this->db->query($sql_main_case, [$roster_id, $list_dt, $part_no]);

        foreach ($query_main_case->getResult() as $main_case) {
            $this->db->query("UPDATE main m INNER JOIN heardt h ON m.diary_no = h.diary_no SET h.coram = ? WHERE m.conn_key = ? AND m.diary_no != ? AND h.next_dt = ? AND h.brd_slno > 0 AND h.mainhead = 'M' AND h.board_type = 'J' AND h.coram != ? AND h.roster_id = ? AND h.clno = ?", [$main_case->coram, $main_case->conn_key, $main_case->conn_key, $list_dt, $main_case->coram, $roster_id, $part_no]);
        }
    }

    private function handleBoardTypeR($list_dt, $part_no, $mainhead, $roster_id, $ucode)
    {
        // Logic for handling Board Type R
        $cur_dt = date('Y-m-d');
        $sql = "SELECT diary_no, judges FROM heardt WHERE next_dt = ? AND clno = ? AND mainhead = ? AND roster_id = ? AND brd_slno > 0 AND board_type = 'R' AND (main_supp_flag = 1 OR main_supp_flag = 2)";
        $query = $this->db->query($sql, [$list_dt, $part_no, $mainhead, $roster_id]);

        foreach ($query->getResult() as $reg_cor) {
            $sel_from_heardt = "SELECT * FROM coram WHERE diary_no = ? AND to_dt = '0000-00-00' AND display = 'Y' AND board_type = 'R'";
            $cor_query = $this->db->query($sel_from_heardt, [$reg_cor->diary_no]);
            $cor_row = $cor_query->getRow();

            if ($cor_row) {
                if ($cor_row->jud != $reg_cor->judges) {
                    $this->db->query("UPDATE coram SET to_dt = ?, del_reason = 'By cl_print_save' WHERE diary_no = ? AND board_type = 'R'", [$cur_dt, $reg_cor->diary_no]);
                }
            }

            // Insert new record
            $this->db->query("INSERT INTO coram (diary_no, board_type, jud, res_id, from_dt, to_dt, usercode, ent_dt, display) VALUES (?, 'R', ?, '2', ?, '0000-00-00', ?, NOW(), 'Y')", [$reg_cor->diary_no, $reg_cor->judges, $cur_dt, $ucode]);
        }
    }

    private function generateFile($mainhead, $board_type, $main_supp_flag, $part_no, $roster_id, $pdf_cont)
    {
        $file_path = "{$mainhead}_{$board_type}_{$main_supp_flag}_{$part_no}_{$roster_id}";
        $path_dir = "/home/judgment/cl/" . date('Y-m-d') . "/";

        if (!file_exists($path_dir)) {
            mkdir($path_dir, 0777, true);
        }

        $data_file = $path_dir . $file_path . ".html";
        $data_file1 = $path_dir . $file_path . ".pdf";

        if (file_exists($data_file)) {
            unlink($data_file);
        }

        file_put_contents($data_file, $pdf_cont);

        // PDF generation logic (use mPDF)
        include '/var/www/html/supreme_court/MPDF60/mpdf.php';
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML(file_get_contents($data_file));
        $mpdf->Output($data_file1, 'F'); // Save the PDF file
    }
}
