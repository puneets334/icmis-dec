<?php

namespace App\Controllers\Filing;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Filing\DefectModel;


class Defect extends BaseController
{

    protected $dModel;
    protected $diary_no;
    function __construct()
    {
        $this->dModel = new DefectModel();

        if (empty(session()->get('filing_details')['diary_no'])) {
            $uri = current_url(true);
            //$getUrl = $uri->getSegment(3).'-'.$uri->getSegment(4);
            //    $getUrl = $uri->getSegment(0).'-'.$uri->getSegment(1);
            $getUrl = str_replace('/', '-', $uri->getPath());
            header('Location:' . base_url('Filing/Diary/search?page_url=' . base64_encode($getUrl)));
            exit();
            exit();
        } else {
            $this->diary_no = session()->get('filing_details')['diary_no'];
        }
    }

    public function index()
    {
        $filing_details = session()->get('filing_details');
        //        echo "<pre>";
        //        print_r($filing_details);
        //        die;
        if (!empty($filing_details['diary_no'])) {
            // pr($filing_details);
            $diaryNumber = $filing_details['diary_no'];
            $petitionerName = $filing_details['pet_name'];
            $respondentName = $filing_details['res_name'];
            $date = date('d-m-Y', strtotime($filing_details['diary_no_rec_date']));
            $caseGroup = $filing_details['case_grp'];
            $fileNumber = $filing_details['fil_no'];
            $caseStatus = $filing_details['c_status'];
            $caseTypeId = $filing_details['casetype_id'];
            $daCode = $filing_details['dacode'];

            //CALLING OF NAVIGATE DIARY FUNCTION *****************************

            //            $resultNavigateFunc = $this->dModel->navigate_diary($diaryNumber);

            //             print_r($resultNavigateFunc);exit;

            //            foreach ($resultNavigateFunc as $data) {
            //
            //                //HARD CODED TO BE REMOVED LATER
            //                //    $data['active_fil_no'] = '05-000072-000072';
            //                $filNumberArray = explode("-", $data['active_fil_no']);
            //
            //                if (empty($filNumberArray[0])) {
            //
            //                    $filNumberPrint = "Unreg.";
            //                } else {
            //    $data['short_description']='R.P.(C) No.';

            //                    $filNumberPrint = $data['short_description'] . "/" . ltrim($filNumberArray[1], '0');
            //
            //                    if (!empty($filNumberArray[2]) and $filNumberArray[1] != $filNumberArray[2])
            //                        $filNumberPrint .= "-" . ltrim($filNumberArray[2], '0');
            //                    $filNumberPrint .= "/" . $data['active_reg_year'];
            //
            //                }
            //                if ($data['c_status'] == "P") {
            //
            //                    $cStatus = "Pending";
            //                } else {
            //                    $cStatus = "Disposed";
            //                }

            //                $getNavigateData = array(
            //                    'session_c_status' => $cStatus,
            //                    'session_pet_name' => $data['pet_name'],
            //                    'session_res_name' => $data['res_name'],
            //                    'session_lastorder' => $data['lastorder'],
            //                    'session_active_reg_no' => $filNumberPrint,
            //                    'session_diary_recv_dt' => date('d-m-Y', strtotime($data['diary_no_rec_date'])),
            //                    //     'session_active_fil_dt ' => date('d-m-Y', strtotime($data['active_fil_dt'])),
            //                    'session_diary_no' => substr($data['diary_no'], 0, -4),
            //                    'session_diary_yr' => substr($data['diary_no'], -4)
            //                );
            //
            //
            //            }
            //END OF NAVIGATE DIARY FUNCTION   ********************************

            //CHECK MATTER IS DISPOSED OR NOT
            if ($caseStatus == 'D') {
                $data['message'] = 'Matter is Disposed!!!!';
                return view('Filing/defect_messages_view', $data);
            }


            // FOR DEFECTS ENTRY IN R.P./CUR.P/CONT.P/MA   *************************************
            //
            $userCode = session()->get('login')['usercode'];
            //     $userCode = 192;    // TO BE REMOVED LATER HARDCODED VALUE >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

            $checkSection = $this->dModel->check_section($userCode);
            // pr( $checkSection);
            //               echo getType($checkSection);die;
            //            echo $userCode;
            //            echo "<pre>";
            //            print_r($checkSection);die;


            //            if ($checkSection > 0 && $userCode != 1)
            if ($checkSection > 0) {

                $caseType = array('9', '10', '19', '20', '25', '26', '39');
                if (!in_array($caseTypeId, $caseType)) {
                    $data['message'] = 'Defects can be added in RP/CUR.P/CONT.P./MA';
                    return view('Filing/defect_messages_view', $data);
                }
                $da = $this->dModel->get_da($diaryNumber);

                if ($da != $userCode) {
                    // pr($userCode);
                    $data['message'] = 'Defects can be updated by concerned Dealing Assistant';
                    return view('Filing/defect_messages_view', $data);
                }
            }


            //                  CHECK MATTER STATUS
            //                  CONDITION TO CHECK FOR DEFECTIVE CHAMBER LISTED MATTERS HAS BEEN ADDED ON 09-11-2022 STARTS HERE


            $ifChamberListed = 0;
            $checkIfChamberListed =  $this->dModel->check_if_chamber_listed($diaryNumber);
            //            echo "<pre>";
            //            print_r($checkIfChamberListed);
            //            die;
            if ($checkIfChamberListed) {
                $ifChamberListed = 1;
            } else {

                $checkIfListed =  $this->dModel->check_if_listed($diaryNumber);
                //                echo "<pre>";
                //                print_r($checkIfListed);
                //                die;

                if ($checkIfListed['next_dt'] != NULL &&  $checkIfListed['next_dt'] != '') {
                    $data['message'] = 'Case Is Listed. Defects cannot be added!!!!';
                    return view('Filing/defect_messages_view', $data);
                } else {
                    $check_if_verified = $this->dModel->check_if_verified($diaryNumber);
                    //                    echo "<pre>";
                    //                    print_r($check_if_verified);
                    //                    die;
                    if (!empty($check_if_verified)) {
                        $data['message'] = 'Case Is Verified. Defects cannot be added!!!!';
                        return view('Filing/defect_messages_view', $data);
                    }
                }
            }

            //            if(!empty($checkIfChamberListed)) {
            //                if (array_key_exists('1', $checkIfChamberListed)) {
            //                    $data['message'] = $checkIfChamberListed['1'];
            //
            //                    return view('Filing/defect_messages_view', $data);
            //                } elseif (array_key_exists('2', $checkIfChamberListed)) {
            //                    $data['message'] = $checkIfChamberListed['2'];
            //
            //                    return view('Filing/defect_messages_view', $data);
            //
            //                } elseif (array_key_exists('3', $checkIfChamberListed)) {
            //
            //
            //                }
            //            }


            //            CONDITION TO CHECK IF LOGIN USER IS SOFT PETITION USER (ADDED ON 05-01-2023)

            $softCopyUser = 0;
            $softCopyUserModel = $this->dModel->get_soft_copy_user($userCode);

            //            echo gettype($softCopyUserModel);
            //            print_r($softCopyUserModel);
            if ($softCopyUserModel) {
                $softCopyUser = 1;
            }

            //        CONDITION TO CHECK---------- added by preeti agrawal on 6.3.2021 to allow bharti from 1b to add soft copy defects in registered matter

            $allowEntryInRegisteredMatter = 0;
            $isEfiled = 0;
            //         ASK MAM ABOUT THIS******************************************************************************
            //            $isEfiledModel = $this->dModel->check_if_counterfiled($diaryNumber);
            //            echo "<pre>";
            //            print_r($isEfiledModel);
            //            die;
            //            if($isEfiledModel)
            //            {
            //                $isEfiled=1;
            //                if($softCopyUser ==1)
            //                {
            //                    $data['message']='Matter is efiled. Soft copy Defect cannot be added!!!!';
            //                    return view('Filing/defect_messages_view',$data);
            //
            //                }
            //            }


            $checkIfRegistered = $this->dModel->check_if_registered($diaryNumber);
            //            echo "<pre>";
            //            print_r($checkIfRegistered);
            //            die;
            if (!empty($checkIfRegistered)) {
                if ($checkIfRegistered['fil_no'] != null && $checkIfRegistered['fil_no'] != '' && $softCopyUser != 1) {
                    $data['message'] = 'Case Is Registered. Defects cannot be added!!!!';
                    return view('Filing/defect_messages_view', $data);
                    //                    echo "<div style='text-align:center;color: red'><h3>Case Is Registered. Defects cannot be added!!!!</h3></div>";
                    //                    exit(0);
                }
                if ($checkIfRegistered['fil_no'] != null && $checkIfRegistered['fil_no'] != '' && $softCopyUser == 1) {
                    $allow_entry_in_registered_matter = 1;
                }
            }

            //   print_r($checkIfRegistered[0]['diary_no']);exit;
            //            if(empty($checkIfRegistered))
            //            {
            //                if( $softCopyUser == 1)
            //                {
            //                    $data['message']='Case Is Un-Registered. Soft copy Defect cannot be added!!!!';
            //                    return view('Filing/defect_messages_view',$data);
            //
            //                }
            //            }else if($checkIfRegistered[0] != null && $softCopyUser != 1 )
            //            {
            //                $data['message']='Case Is Registered. Defects cannot be added!!!!';
            //                return view('Filing/defect_messages_view',$data);
            //
            //            }else if($checkIfRegistered[0] != null && $softCopyUser == 1 )
            //            {
            //                $allowEntryInRegisteredMatter = 1;
            //            }


            /*  OLD DEFECTS FLAG ADDED ON 01-08-2023 TO CHECK IF ALREADY DEFECTS EXIST BEFORE ENTERING NEW DEFECTS*/

            $oldDefect = 0;
            $sqlRes = 0;

            $checkExistingDefect = $this->dModel->check_old_defect($diaryNumber);
            //   print_r($checkExistingDefect);die;
            //            echo "<pre>";
            //            print_r($checkExistingDefect);
            //            die;

            $oldExistDefect = array();

            if (!empty($checkExistingDefect)) {
                //                echo "FDSSSf";
                //                die;
                foreach ($checkExistingDefect as $existingDefect) {
                    //                    echo "uio";
                    //                    die;
                    //                    echo "<pre>";
                    //                    print_r($existingDefect);
                    //                    die;
                    if ($existingDefect['rm_dt'] === null && ($existingDefect['status'] == '0' || $existingDefect['status'] == null || $existingDefect['status'] == '7')) {
                        $sqlRes = 1;
                        $oldDefect = 1;
                        //                        echo ">>".$sqlRes;
                        //                        die;
                    }
                }
            } else {
                $sqlRes = 1;
            }

            if ($sqlRes == 0) {

                if ($userCode == 1 || $userCode == 1486 || $softCopyUser == 1 || $ifChamberListed == 1) {
                    $sqlRes = 1;
                } else {
                    $data['message'] = 'Matter has been refiled!!!';
                    return view('Filing/defect_messages_view', $data);
                }
            }

            if ($sqlRes == 1) {

                //CODE TO CHECK IF MATTER IS MARKED TO THE SCURTINY OFFICIAL

                $checkFilTrap = $this->dModel->check_fil_trap($diaryNumber);
                //                echo "<pre>";
                //                print_r($checkFilTrap);
                //                die;
                //   var_dump($checkFilTrap);die;

                if (!empty($checkFilTrap)) {
                    foreach ($checkFilTrap as $filTrapData) {
                        //                        echo $softCopyUser;
                        //                        die;
                        if ($softCopyUser != 1) {
                            if ($filTrapData['usercode'] != $userCode) {
                                $currentUser = '';
                                if ($filTrapData['d_to_empid'] == 29) {
                                    $currentUser = 'Advocate (for defects cure)';
                                } else if ($filTrapData['d_to_empid'] == 27) {
                                    $currentUser = 'FDR';
                                } else {
                                    $currentUser = $filTrapData['d_to_empid'] . '-' . $filTrapData['name'];
                                }
                                //                                echo $currentUser;
                                //                                die;

                                $stringMess = 'Defects cannot be added as matter is currently with ' . $currentUser . 'current remarks[' . $filTrapData['remarks'] . ']';

                                $data['message'] = $stringMess;
                                return view('Filing/defect_messages_view', $data);
                            }
                        }
                    }
                }
            }

            $oldExistingDefect = $this->dModel->get_all_existing_defect($diaryNumber);

            //   var_dump($oldExistingDefect);die;
            $arrayID = array();
            foreach ($oldExistingDefect as $old_defect_id) {
                $arrayID[] = $old_defect_id['org_id'];
            }
            //            echo "<pre>";
            //            print_r($arrayID);
            //            die;
            //            Array
            //            (
            //            [0] => 10075
            //            [1] => 302
            //            )
            $data['old_defect'] = $oldExistingDefect;

            $data['all_defect'] = $this->dModel->get_all_defect($arrayID);

            //   $data['other_defect'] = $this->dModel->get_existing_otherdefect($diaryNumber);
            //   $other_defect = $this->dModel->get_existing_otherdefect($filing_details['diary_no']);
            $data['result_check'] = $this->dModel->checkEfiledCase($diaryNumber);
            $data['dModel'] = $this->dModel;
            return view('Filing/defect_view', $data);
        }
    }

    public function insert_function()
    {

        $dataForInsertion = $_POST;
        $insertData = false;
        if (!empty($dataForInsertion)) {
            $insertData = $this->dModel->insert_function($dataForInsertion);
        }
        if ($insertData) {

            echo "Record Inserted Successfully !!!!";
            exit;
        } else {
            echo "Record Is Not Inserted Successfully !!!!";
            exit;
        }
    }

    public function update_function()
    {
        $dataForUpdate = $_POST;
        //        echo "<pre>";
        //        print_r($dataForUpdate);
        //        die;
        if (!empty($dataForUpdate)) {
            $updateData = $this->dModel->update_function($dataForUpdate);
        }

        if ($updateData) {
            //  echo "yy";die;
            echo "Record Updated Successfully !!!!";
        } else {
            echo "Record Is Not Updated Successfully !!!!";
        }
    }

    public function cancel_refiling()
    {
        $filing_details = session()->get('filing_details');
        if (!empty($filing_details['diary_no'])) {

            $data['dModel'] = $this->dModel;

            $data['diary_no'] = $filing_details['diary_no'];
            $data['flag'] = "A";

            return view('Filing/defects/cancel_refiling', $data);
        }
    }

    public function objection_del()
    {
        $filing_details = session()->get('filing_details');
        if (!empty($filing_details['diary_no'])) {

            $data['dModel'] = $this->dModel;

            $data['diary_no'] = $filing_details['diary_no'];
            $data['flag'] = "A";

            return view('Filing/defects/objection_del', $data);
        }
    }

    public function objection_upd()
    {
        $filing_details = session()->get('filing_details');
        if (!empty($filing_details['diary_no'])) {
            $data['dModel'] = $this->dModel;

            $data['diary_no'] = $filing_details['diary_no'];
            $data['flag'] = "A";

            return view('Filing/defects/objection_upd', $data);
        }
    }

    public function obj_back_date()
    {
        $filing_details = session()->get('filing_details');
        if (!empty($filing_details['diary_no'])) {
            $data['dModel'] = $this->dModel;
            $data['diary_no'] = $filing_details['diary_number'];
            // $data['flag'] = "A";
            $data['diary_year'] = $filing_details['diary_year'];
            $data['result'] =  $this->dModel->getMainTableData($filing_details['diary_no']);
            return view('Filing/defects/obj_back_date', $data);
        }
        die;
    }

    public function get_obj_data()
    {
        $data['diary_no'] = $_REQUEST['d_no'] . $_REQUEST['d_yr'];
        $ucode = $_SESSION['login']['usercode'];
        $data['dModel'] = $this->dModel;
        $data['result'] =  $this->dModel->getMainTableData($data['diary_no']);
        return view('Filing/defects/obj_back_date_data', $data);
        die;
    }

    public function report()
    {
        $filing_details = session()->get('filing_details');
        //pr($filing_details);
        if (!empty($filing_details['diary_no'])) {

            $data['dModel'] = $this->dModel;

            $data['diary_no'] = $filing_details['diary_no'];
            $data['flag'] = "A";

            return view('Filing/defects/report', $data);
        }
    }

    public function getReport()
    {
        //$filing_details = session()->get('filing_details');
        // Check if both d_no and d_yr are set and are integers
        if (!empty($_REQUEST['d_no']) && !empty($_REQUEST['d_yr']) && is_numeric($_REQUEST['d_no']) && is_numeric($_REQUEST['d_yr']))
        {
            $dairy_no = $_REQUEST['d_no'] . $_REQUEST['d_yr'];
        } else {
            echo "<p style='color:red;text-align:center;'>Invalid diary number or year. Only integers are allowed.</p>";
            return;
        }
        if (!empty($dairy_no)) {

            $data['dModel'] = $this->dModel;
            $data['objection'] = $this->dModel->gatObjSave($dairy_no);
            $data['tot_defects'] = $this->dModel->getTotalDefects($dairy_no);
            $data['row'] = is_data_from_table('main', ['diary_no' => $dairy_no], 'pet_name,res_name,pno,rno', '');
            if (!$data['row']) {
                echo "<p style='color:red;text-align:center;'>No data found for the given diary number.</p>";
                return;
            }
            $data['advocate'] = $this->dModel->getAdvocateName($dairy_no);
            $data['dairy_no'] = $dairy_no;
            $data['d_no'] = $_REQUEST['d_no'];
            $data['d_yr'] = $_REQUEST['d_yr'];

            return view('Filing/defects/get_report', $data);
        }
    }
}
