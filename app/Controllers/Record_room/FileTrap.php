<?php

namespace App\Controllers\Record_room;

use App\Controllers\BaseController;

use App\Models\Record_room\FileTrap_model;
use App\Models\Record_room\Record_keeping_model;

class FileTrap extends BaseController
{
    public $FileTrap_model;
    public $Record_keeping_model;
    protected $request;

    function __construct()
    {
        $this->FileTrap_model = new FileTrap_model();
        $this->Record_keeping_model = new Record_keeping_model();
    }

    public function get_session()
    {
        $this->index();
    }

    public function index()
    {
        $data['app_name'] = "File Trap";
        // $data['case_type']=$this->Sensitive_info_model->case_types();
        //$this->load->view('FileTrap/RRDAReceiveDispatch',$data);
        $usercode = session()->get('login')['usercode'];

        $fileTrapUserRoles =  $this->FileTrap_model->getFileTrapUsersRole($usercode);
        if ($fileTrapUserRoles !== false && count($fileTrapUserRoles) > 0) {
            $userType = $fileTrapUserRoles[0]['usertype'];
            $roleName = $fileTrapUserRoles[0]['type_name'];
            $hallNo = $fileTrapUserRoles[0]['ref_hall_no'];
            $hallLocation = $fileTrapUserRoles[0]['description'];
            $data['param'] = array($roleName, $userType, null, null, $hallNo, $hallLocation);
        } else {
            $data['param'] = array(null, null, null, null, null, null);
        }
        // echo "<pre>";print_r($data);exit;


        return view('Record_room/FileTrap/RRDAReceiveDispatch', $data);
    }

    public function fileRevert()
    {
        $data['app_name'] = "File Trap";
        // $data['case_type']=$this->Sensitive_info_model->case_types();
        //$this->load->view('FileTrap/RRDAReceiveDispatch',$data);
        $usercode = session()->get('login')['usercode'];
        $fileTrapUserRoles =  $this->FileTrap_model->getFileTrapUsersRole($usercode);
        if (is_array($fileTrapUserRoles) && count($fileTrapUserRoles) > 0) {
            $userType = $fileTrapUserRoles[0]['usertype'];
            $roleName = $fileTrapUserRoles[0]['type_name'];
            $hallNo = $fileTrapUserRoles[0]['ref_hall_no'];
            $hallLocation = $fileTrapUserRoles[0]['description'];

            $data['param'] = [$roleName, $userType, null, null, $hallNo, $hallLocation];
        } else {
            $data['param'] = [null, null, null, null, null, null];
        }
        return view('Record_room/FileTrap/RRDAReceiveFromScanningDispatch', $data);
    }

    public function sanitize($data)
    {
        $data = preg_replace("/[^A-Za-z0-9:.,\-\s\_\(\)\[\]\/]/", "", $data);
        return $data;
    }


    public function receiveCases()
    {
        $request = service('request');
        $usercode = session()->get('login')['usercode'];
        $fileTrapUserRoles = $this->FileTrap_model->getFileTrapUsersRole($usercode);
       

        if ($fileTrapUserRoles) {
            $userType = $fileTrapUserRoles[0]['usertype'];
            $roleName = $fileTrapUserRoles[0]['type_name'];
            $hallNo = $fileTrapUserRoles[0]['ref_hall_no'];
            $hallLocation = $fileTrapUserRoles[0]['description'];
        } else {
            $userType = '';
            $roleName = '';
            $hallNo = '';
            $hallLocation = '';
        }
        
        // Check user specific allocation given or not
        $UserCaseRole = $this->FileTrap_model->checkUserCaseTypeRole($usercode, $userType);

        $roleCaseNature = 'S'; // Default value
        if ($UserCaseRole) {
            $roleCaseNature = $UserCaseRole[0]['caseHead'];
        }

        $data['disposedCasesList'] = '';
        $data['app_name'] = 'File Trap';
        $data['param'] = [$roleName, $userType, null, null];

        if ($request->getMethod() === 'post') {
            $fromDate = date('Y-m-d', strtotime($request->getPost('orderDateFrom')));
            $toDate = date('Y-m-d', strtotime($request->getPost('orderDateTo')));
            $data['disposedCasesList'] = $this->FileTrap_model->getReceivedCasesList($fromDate, $toDate, $userType, $usercode, $roleCaseNature);

            $data['app_name'] = 'Disposed Cases List';
            $data['app_name'] = 'disposedCasesList';

            $data['param'] = [$roleName, $userType, $fromDate, $toDate, $hallNo, $hallLocation];
            // echo "<pre>";print_r($data);exit;
        }

        return view('Record_room/FileTrap/receiveCases', $data);
    }

    public function receiveCasesFromScanning()
    {
        // echo "<pre>";
        $usercode = session()->get('login')['usercode'];

        $fileTrapUserRoles =  $this->FileTrap_model->getFileTrapUsersRole($usercode);
        if (is_array($fileTrapUserRoles) && count($fileTrapUserRoles) > 0) {
            $userType = $fileTrapUserRoles[0]['usertype'];
            $roleName = $fileTrapUserRoles[0]['type_name'];
            $hallNo = $fileTrapUserRoles[0]['ref_hall_no'];
            $hallLocation = $fileTrapUserRoles[0]['description'];
        } else {
            $userType = '';
            $roleName = '';
            $hallNo = '';
            $hallLocation = '';
        }
        $data['param'] = array($roleName, $userType, null, null, $hallNo, $hallLocation);
        $UserCaseRole =  $this->FileTrap_model->checkUserCaseTypeRole($usercode, $userType);
       
        $roleCaseNature = $UserCaseRole[0]['casehead'] ?? '';

        $data['disposedCasesList'] = '';
        $data['app_name'] = 'File Trap';
        
        if ($userType == 110) {
            if ($this->request->getMethod() === 'post') {
                $fromDate = date('Y-m-d', strtotime($this->request->getPost('orderDateFrom')));
                $toDate = date('Y-m-d', strtotime($this->request->getPost('orderDateTo')));
                $data['disposedCasesList'] = $this->FileTrap_model->getReceivedCasesFromScanningList($fromDate, $toDate, $userType, $usercode, $roleCaseNature);
                // echo "<pre>";
                // print_r($this->db->getLastQuery());
                // print_r($data['disposedCasesList']);exit;

                $data['app_name'] = 'disposedCasesList';
                $data['param'] = array($roleName, $userType, $fromDate, $toDate, $hallNo, $hallLocation);
            }
            //var_dump($this->data['mentioningReports']);
        } else {

            $this->session->setFlashdata('msg', '<div class="alert alert-success text-center">You are not authorized to receive the files from scanning.</div>');

            //redirect("FileTrap/receiveCasesFromScanning");
        }
        // echo "<pre>";
        //         // print_r($this->db->getLastQuery());
        //         print_r($data);exit;
        return view('Record_room/FileTrap/receiveCasesFromScanning', $data);
    }


    public function receiveAndDispatchCases()
    {
        //var_dump($_POST);
        if ($this->request->getMethod() === 'post') {

            $fromDate = date('Y-m-d', strtotime($this->request->getPost('dateFrom')));
            $toDate = date('Y-m-d', strtotime($this->request->getPost('dateTo')));

            $userCode = $this->request->getPost('usercode');
            $userEmpID =  $this->FileTrap_model->getEmpID($userCode);
            $dispatchFromEmpID = $userEmpID[0]['empid'];


            $fileTrapUserRoles =  $this->FileTrap_model->getFileTrapUsersRole($userCode);
            $userType = $fileTrapUserRoles[0]['usertype'];
            $roleName = $fileTrapUserRoles[0]['type_name'];
          
            $remarks = "";
            if ($userType == 110) {
                $remarks = "RR-DA -> SEG-DA";
            } elseif ($userType == 111) {
                $remarks = "SEG-DA -> SCA";
            } elseif ($userType == 58) {
                $remarks = "SCA -> RR-DA";
            } elseif ($userType == 112) {
                $remarks = "REC-DA -> Rack";
            }
         
            foreach ($this->request->getPost('allReceivedCases') as $key => $value) {
                $diaryNumber = $value;
                
                $consignmentRemarks = $this->request->getPost('consignmentRemarks');
                $consignmentRemark = $this->sanitize($consignmentRemarks[$key]);
                $consignmentRemark = $this->sanitize($consignmentRemark);
                
                $caseDistinationHallNo =  $this->FileTrap_model->getCaseDestinationHallNo($diaryNumber);
                // echo $this->db->getLastQuery();
                $hallNo = $caseDistinationHallNo['hall_no'] ?? '';
                $caseGroups = $this->FileTrap_model->getCaseType($diaryNumber);   //check the diary no is Civil type or Criminal type
                $caseGroup = $caseGroups['case_grp'];
                
                // echo $caseGroup;
                // echo 'Hall No : '.$hallNo;
                $updationStatus = '';
                if ($userType == 110) {
                    $designatedUserEmpID = $this->caseEquallyAllotment(111, $caseGroup, $hallNo);
                } elseif ($userType == 111) {
                    $designatedUserEmpID = 82708;
                } elseif ($userType == 58) {
                    $designatedUserEmpID = $this->caseEquallyAllotment(110, $caseGroup, $hallNo);
                } elseif ($userType == 112) {
                    $designatedUserEmpID = 99999;
                }
                // echo $designatedUserEmpID;
                
                if ($designatedUserEmpID != 0 or $designatedUserEmpID != null) {
                    
                    $caseCountFiletrap = $this->FileTrap_model->check_case_file_trap($diaryNumber);                    
                    $updationStatus = $this->FileTrap_model->updateCaseFileTrap($caseCountFiletrap['count_no'], $diaryNumber, $dispatchFromEmpID, $designatedUserEmpID, $remarks, $hallNo, $consignmentRemark);
                    echo $this->FileTrap_model->getLastQuery();

                } 
            }
            // print_r($updationStatus);die;
            
            if ($updationStatus == true) {
                //$myObj = array('name' => 'John', 'age'=>'30', 'city'=> 'New York');
                $myObj = ('Selected Cases has been successfully Accepted and Auto Dispatched');
                $myJSON = json_encode($myObj);
                return $myJSON;
            } else {
                return "Error !! while updating file trap Updation";
            }
        }
    }

    public function receiveAndDispatchCasesToRC()
    {
        //var_dump($_POST);
        if ($_POST) {
            $fromDate = date('Y-m-d', strtotime($_POST['dateFrom']));
            $toDate = date('Y-m-d', strtotime($_POST['dateTo']));

            $userCode = $_POST['usercode'];
            $userEmpID =  $this->FileTrap_model->getEmpID($userCode);
            $dispatchFromEmpID = $userEmpID[0]['empid'];


            $fileTrapUserRoles =  $this->FileTrap_model->getFileTrapUsersRole($userCode);
            $userType = $fileTrapUserRoles[0]['usertype'];
            $roleName = $fileTrapUserRoles[0]['type_name'];

            //echo $userType.'#'.$roleName;

            $remarks = "";
            if ($userType == 110) {
                $remarks = "RR-DA -> REC-DA";
            }

            foreach ($_POST['allReceivedCases'] as $key => $value) {
                $diaryNumber = $value;
                $consignmentRemark = $_POST['consignmentRemarks'][$key];
                $consignmentRemark = $this->sanitize($consignmentRemark);

                // if case alloted within hall-usertype civil or criminal wise then below  two line
                $caseGroups = $this->FileTrap_model->getCaseType($diaryNumber);   //check the diary no is Civil type or Criminal type
                $caseGroup = $caseGroups[0]['case_grp'];

                // if case alloted within hall-usertype equally then remove comment to below mention line and commnted above two lines
                //$caseGroup=null;

                $caseDistinationHallNo =  $this->FileTrap_model->getCaseDestinationHallNo($diaryNumber);
                $hallNo = $caseDistinationHallNo[0]['hall_no'];

                if ($userType == 110) {
                    $designatedUserEmpID = $this->caseEquallyAllotment(112, $caseGroup, $hallNo);
                }
                if ($designatedUserEmpID != 0 or $designatedUserEmpID1 = '' or $designatedUserEmpID != null) {

                    $caseCountFiletrap = $this->FileTrap_model->check_case_file_trap($diaryNumber);

                    $updationStatus = $this->FileTrap_model->updateCaseFileTrap($caseCountFiletrap[0]['count_no'], $diaryNumber, $dispatchFromEmpID, $designatedUserEmpID, $remarks, $hallNo, $consignmentRemark);
                } else {
                    echo "No Record DA Alloted for received the case -";
                }
                //echo $diaryNumber.' - '.$designatedUserEmpID.'<br>';
            }
            if ($updationStatus == true) {
                $myObj = ('Selected Cases has been Successfully Accepted and Auto Dispatched to Record Keeper');
                $myJSON = json_encode($myObj);
                echo $myJSON;
            } else {
                echo "Error !! while updating file trap Updation";
            }
        }
    }

    public function caseEquallyAllotment($userType, $caseGroup, $hallNo)
    {
        if ($userType == 111) {
            $utypeName = 'SEGDA';
        }
        if ($userType == 112) {
            $utypeName = 'RECDA';
        }
        if ($userType == 58) {
            $utypeName = 'RRDA';
        }

        $sgda_users = $this->FileTrap_model->get_all_users($userType, $caseGroup, $hallNo);

        $designated_user_code = 0;
        if ($sgda_users != 0) {
            $designated_users = $this->FileTrap_model->get_designated_users($userType, $utypeName, $caseGroup, $hallNo);
            if ($designated_users == 0) {
                $designated_user_code = $sgda_users[0]['empid'];
            } else {
                $designated_user_code = $designated_users[0]['to_userno'];
            }
        }
        // echo  $designated_user_code;


        $this->FileTrap_model->record_last_assigned_user($designated_user_code, $utypeName);
        return $designated_user_code;
    }

    public function receiveDispatchReport()
    {
        $data['app_name'] = '';
        $data['receiveDispatchReports'] = '';
        $data['param'] = '';
        $data['reportType'] = '1';

        if ($this->request->getMethod() === 'post') {
            $usercode = $this->request->getPost('usercode');
            $reportType = $this->request->getPost('rptType');
            $fromDate = date('Y-m-d', strtotime($this->request->getPost('fromDate')));
            $toDate = date('Y-m-d', strtotime($this->request->getPost('toDate')));

            $userEmpIDs =  $this->FileTrap_model->getEmpID($usercode);
            $userEmpID = $userEmpIDs[0]['empid'] ?? '';
            $userEmpName = $userEmpIDs[0]['name'] ?? '';

            $fileTrapUserRoles =  $this->FileTrap_model->getFileTrapUsersRole($usercode);
            $userType = $fileTrapUserRoles[0]['usertype'] ?? '';
            $roleName = $fileTrapUserRoles[0]['type_name'] ?? '';

            $data['app_name'] = 'receivedDispatchedReport';
            $data['receiveDispatchReports'] = $this->FileTrap_model->getReceivedDispatchedReport($fromDate, $toDate, $userType, $userEmpID, $reportType);
            $data['param'] = array($fromDate, $toDate, $roleName, $userType, $reportType, $userEmpName);
            $data['reportType'] = $reportType;
         
        }
        return view('Record_room/FileTrap/file_trap_report', $data);
    }

    public function caseTimeline()
    {
        $usercode = session()->get('login')['usercode'];    

        $fileTrapUserRoles =  $this->FileTrap_model->getFileTrapUsersRole($usercode);
        $userType = $fileTrapUserRoles[0]['usertype'];
        $roleName = $fileTrapUserRoles[0]['type_name'];
        $data['caseTimeline'] = '';
        $data['app_name'] = 'CaseTimeline';
        $data['param'] = array($roleName, $userType);
        if (!empty($this->request->getGet('diaryNo'))) {
            $diaryNo = $this->request->getGet('diaryNo');

            $data['caseTimeline'] = $this->FileTrap_model->getCaseTimeLineReport($diaryNo);            
            $data['param'] = array($roleName, $userType);        

            return view('Record_room/FileTrap/caseTimeLine',$data);

//             echo "<div id='query_builder_wrapper' class='query_builder_wrapper dataTables_wrapper dt-bootstrap4'>
//             <table class='table table-bordered'>
//         <thead>
//             <tr>
//                 <th>Sr no.</th>
//                 <th>Cause title</th>
//                 <th>Order date</th>
//                 <th>Dispatch date</th> 
//                 <th>Remark</th>
//                 <th>Dispath By</th>
//                 <th>Dispath To</th>
//                 <th>Pet name</th>
//                 <th>Res name</th>
//                 <th>Rece_dt</th>
//                 <th>Role By</th>
//                 <th>RoleTo</th>
//                 <th>Hall location</th>
//                 <th>Hall no</th>
//                 <th>Consignment remark</th>
//             </tr>
//         </thead>
//         <tbody>";

// $srNo = 1; // Initialize a counter for Sr. No.
// foreach ($data['caseTimeline'] as $case) {
//     echo "<tr>
//             <td>{$srNo}</td>
//             <td>{$case['cause_title']}</td>
//             <td>{$case['order_date']}</td>
//             <td>{$case['dispatchdate']}</td>
//             <td>{$case['remarks']}</td>
//             <td>{$case['dispathBy']}</td>
//             <td>{$case['dispathTo']}</td>
//             <td>{$case['pet_name']}</td>
//             <td>{$case['res_name']}</td>
//             <td>{$case['rece_dt']}</td>
//             <td>{$case['roleBy']}</td>
//             <td>{$case['roleTo']}</td>
//             <td>{$case['hall_location']}</td>
//             <td>{$case['hall_no']}</td>
//             <td>{$case['consignment_remark']}</td>$this->load->
//           </tr>";
//     $srNo++; 
// }

// echo "</tbody>
//       </table>
//       </div>";
            // echo "<pre>";print_r($data);exit;
        }
        //var_dump($this->data);
    }

    public function rrUsersCaseMapping()
    {
        $data['app_name'] = "File Trap";
        $usercode = session()->get('login')['usercode'];

        // Fetch user roles and details
        $fileTrapUserRoles = $this->FileTrap_model->getFileTrapUsersRole($usercode);

        // if (!$fileTrapUserRoles) {
        //     return redirect()->to('/some-error-page'); // Handle error if no roles found
        // }

        if (!empty($fileTrapUserRoles)) {
            $userType = $fileTrapUserRoles[0]['usertype'];
            $roleName = $fileTrapUserRoles[0]['type_name'];
            $hallNo = $fileTrapUserRoles[0]['ref_hall_no'];
            $hallLocation = $fileTrapUserRoles[0]['Description'];
        }

        $data['case_type'] = $this->Record_keeping_model->case_types();

        // Initialize data for the view
        $data['param'] = '';

        // Handle POST request
        if ($this->request->getMethod() === 'post') {
            /*  $usercode = $this->request->getPost('usercode');
            $reportType = $this->request->getPost('rptType');
            $fromDate = date('Y-m-d', strtotime($this->request->getPost('fromDate')));
            $toDate = date('Y-m-d', strtotime($this->request->getPost('toDate')));

            // Fetch employee ID and name
            $userEmpIDs = $this->FileTrap_model->getEmpID($usercode);
            if (!$userEmpIDs) {
                return redirect()->to('/some-error-page'); // Handle error if no employee found
            }
            $userEmpID = $userEmpIDs[0]['empid'];
            $userEmpName = $userEmpIDs[0]['name'];

            // Fetch reports
            $data['app_name'] = 'receivedDispatchedReport';
            $data['receiveDispatchReports'] = $this->FileTrap_model->getReceivedDispatchedReport($fromDate, $toDate, $userType, $userEmpID, $reportType);
            $data['param'] = array($fromDate, $toDate, $roleName, $userType, $reportType, $userEmpName);*/
        }

        return view('Record_room/FileTrap/userCaseMapping', $data);
    }

    public function updateConsignmentDate()
    {
        $responseMsg = '';

        $userCode = session()->get('login')['usercode'];
        $userEmpID =  $this->FileTrap_model->getEmpID($userCode);
        $dispatchFromEmpID = $userEmpID[0]['empid'] ?? '';
        $diaryNo = trim($this->request->getVar('diaryNo'));
        

        if ($this->FileTrap_model->check_consignment_entry($userCode, $diaryNo)) {
            if ($this->FileTrap_model->check_already_reconsign_today($diaryNo)) {
                $responseMsg = 'Re-Consignment of this case has been already done today. ';
            } else {
                $whereConditionArray = array(
                    'diary_no' => $diaryNo,
                    'remarks' => 'RR-DA -> SEG-DA'
                );
             

                $existingConsignmentRemarks =  $this->FileTrap_model->getConsignmentRemarks($whereConditionArray);
                $newConsignmentRemarks = $existingConsignmentRemarks[0]['consignment_remark'] ?? '';
                $newConsignmentRemarks = preg_replace('/\s+/', '', $newConsignmentRemarks);
                if (empty($newConsignmentRemarks)) {
                    $newConsignmentRemarks = "Re-Consignment after Restoration of Case";
                } else {
                    $newConsignmentRemarks = $newConsignmentRemarks . ' (Re-Consignment after Restoration of Case)';
                }

                $updateData = array(
                    'rece_dt' => date('Y-m-d H:i:s'),
                    'disp_dt' => date('Y-m-d H:i:s'),
                    'd_by_empid' => $dispatchFromEmpID,
                    'consignment_remark' => $newConsignmentRemarks
                );
                if ($this->FileTrap_model->updateConsignmentDate($diaryNo, $updateData, $newConsignmentRemarks)) {

                    $responseMsg = 'Consignment Date has been successfully Updated.';
                } else {
                    $responseMsg = 'Error! You are not Authorized to Change the Consignment date.';
                }
            }
        } else {
            $responseMsg = 'Error! The case is not Consigned yet,First Received the case from File Movement Option';
        }

        $myJSON = json_encode($responseMsg);
        echo $myJSON;
    }

    public function getAlreadyConsignedRestoredCaseList()
    {
        $data['app_name'] = '';
        $data['alreadyConsignedRestoredCasesList'] = '';
        $data['param'] = '';

        if ($this->request->getMethod() === 'post') {
            $fromDate = date('Y-m-d', strtotime($this->request->getPost('orderDateFrom')));
            $toDate = date('Y-m-d', strtotime($this->request->getPost('orderDateTo')));

            $data['app_name'] = 'alreadyConsignedRestoredCasesReport';
            $data['alreadyConsignedRestoredCasesList'] =  $this->FileTrap_model->getAllRestoredDisposedCases($fromDate, $toDate);
            $data['param'] = array($fromDate, $toDate);
            $data['from_date'] = $fromDate;
            $data['to_date'] = $toDate;
        //    echo "<pre>"; print_r($data['param'][0]);die;
        }
        return view('Record_room/FileTrap/consigned_restored_cases_list', $data);
    }
}
