<?php


namespace App\Controllers\Listing;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Filing\AdvocateModel;
use App\Models\Listing\CaseInfoModel;
use App\Models\Listing\AllocationTp;
use App\Models\Common\Dropdown_list_model;
use App\Models\Listing\Subheading;
use App\Models\Listing\AdvanceAllocated;
use App\Models\Listing\CaseType;
use App\Models\Listing\Submaster;
use App\Models\Listing\ListingPurpose;
use App\Models\Listing\Roster;
use App\Models\Listing\CaseAdd;
use App\Models\Listing\Heardt;
use App\Models\Listing\Judge;
use App\Models\Listing\SingleJudgeNominate;
use CodeIgniter\I18n\Time;
use App\Models\Listing\CaseDrop;
//use mPDF;
use Mpdf\Mpdf;

use App\Models\CaseModel;

class SingleJudgeAdvance extends BaseController
{


    public $CaseType;
    public $Judge;
    public $SingleJudgeNominate;
    public $ListingPurpose;
    public $CaseAdd;
    public $CaseDrop;


    public $Dropdown_list_model;



    function __construct()
    {
        $this->CaseType = new CaseType();
        $this->Judge = new Judge();
        $this->SingleJudgeNominate = new SingleJudgeNominate();
        $this->CaseAdd = new CaseAdd();
        $this->ListingPurpose = new ListingPurpose();
        $this->CaseDrop = new CaseDrop();
    }



    //Single Judge Advance =>Add single Case
    
    public function add_case()
    {
        $caseTypes = $this->CaseType->caseTypeFun();
        $usercode = session()->get('login')['usercode'];
        $userTypeString = getUser_dpdg_full_2($usercode);
        $userType = explode('~', $userTypeString);
        if (($userType[0] != 1 && $userType[0] != 57 && $userType[0] != 3 && $userType[0] != 4) && ($userType[6] != 450)) {
            echo "YOU ARE NOT AUTHORISED";
            exit();
        }
        return view('Listing/SingleJudgeAdvance/add_case', [
            'caseTypes' => $caseTypes,
            'sessionDiaryNo' => session()->get('session_diary_no'),
            'sessionDiaryYr' => session()->get('session_diary_yr')
        ]);
    }
    
    public function get_case_details()
    {
        $request = service('request');
        $ct = $request->getPost('case_type');
        $cn = $request->getPost('case_number');
        $cy = $request->getPost('case_year');
        $dyr = $request->getPost('diary_year');
        $dn = $request->getPost('diary_number');
        $dno = '';
        if (!empty($ct) && !empty($cn) && !empty($cy))
        {
            $dno = $this->CaseAdd->getDiaryNoSingleJudge($ct, $cn, $cy); // Same h advance list wala
        }
        else if (!empty($dn) && !empty($dyr))
        {
            $dno = $dn . $dyr;
        }
        $data['model'] = $this->CaseAdd;
        $data['ifMain'] = $this->CaseAdd;
        $data['dno'] = $dno;
        return view('Listing/SingleJudgeAdvance/get_case_details', $data);
    }
    public function checkIfPublishedSingleJudge()
    {
        $request = service('request');
        $list_dt_explode = explode("_", $request->getPost('date'));
        $from_dt = $list_dt_explode[0];
        $to_dt = $list_dt_explode[1];
        $isPrinted = $this->CaseAdd->isPrintedCaseSingleJudge($from_dt, $to_dt);
        return $this->response->setJSON(['msg' => $isPrinted]);
    }

    public function saveCaseToAdvanceList()
    {
        $request = service('request');
        $list_dt_explode = explode("_", $request->getPost('listing_date'));
        $sessionData = $this->session->get();
        $data['diary_number'] = $request->getPost('dno');
        $data['from_dt'] = $list_dt_explode[0];
        $data['to_dt'] = $list_dt_explode[1];
        $data['q_usercode'] = $sessionData['login']['usercode'];
        $data['q_clno'] = 1;
        $data['q_main_supp_flag'] = 1;
        $data['mainhead'] = "M";
        $data['model'] = $this->CaseAdd;
        return view('Listing/SingleJudgeAdvance/save_case_single_judge', $data);
    }
    
    //Single Judge Advance =>Nominate
    
    public function nominate()
    {
        $sessionData = $this->session->get();
        $ucode = $sessionData['login']['usercode'];
        if ($ucode)
        {
            return $this->singleJudgeNominateAdd($ucode);
        }
        else
        {
            return "Session Out, Please login.";
        }
    }

    public function singleJudgeNominateAdd($ucode = null)
    {
        $data = [];
        $data['judge'] = $this->Judge->getJudges();
        if (!empty($ucode))
        {
            $singleJudgeData = $this->SingleJudgeNominate->getSingleJudgeNominateDataById($ucode);
            if (!empty($singleJudgeData))
            {
                $data['nominated_judge_modify'] = $singleJudgeData;
                $data['selected_judge_code'] = $singleJudgeData['jcode'];
            }
        }
        return view('Listing/SingleJudgeAdvance/nominate', $data);
    }
    public function singleJudgeNominateJcodeDaytypeValidate()
    {
        $request = service('request');
        $inArr = [];
        $inArr['jcode'] = $request->getPost('jcode') ? trim($request->getPost('jcode')) : NULL; // Remove (int) cast
        $inArr['day_type'] = $request->getPost('day_type') ? $request->getPost('day_type') : NULL;
        $inArr['is_active'] = 1;
        $output = $this->SingleJudgeNominate->selectData('master.single_judge_nominate', $inArr);
        if ($output)
        {
            $return_arr = ["status" => "success"];
        }
        else
        {
            $return_arr = ["status" => "Error"];
        }
        return $this->response->setJSON($return_arr);
    }
    
    public function getSingleJudgeNominatedData()
    {
        $output =  $this->SingleJudgeNominate->getSingleJudgeNominatedData();
        return $this->response->setJSON($output);
    }
    public function getJudgeData()
    {
        $request = service('request');
        $id = $request->getGet('single_judge_id');
        $judgeData = $this->SingleJudgeNominate->getSingleJudgeNominatedData1($id);
        if ($judgeData)
        {
            echo json_encode($judgeData);
        } else {
            echo json_encode(['error' => 'No data found']);
        }
    }

    public function singleJudgeNominateUpdate()
    {
        $request = service('request');
        $updateData = [
            'jcode' => $request->getPost('judge'),
            'day_type' => $request->getPost('day_type'),
            'from_date' => date('Y-m-d', strtotime($request->getPost('effect_date'))),
        ];
        $id = $request->getPost('update_id');
        $updateResult = $this->SingleJudgeNominate->updateJudge($id, $updateData);
        session()->setFlashdata('success', 'Data updated successfully!');
        return $this->response->setJSON(['status' => 'success', 'message' => 'Data updated successfully!']);
    }
    
    public function singleJudgeNominateAddSubmit()
    {
        $request = service('request');
        $requestData = $request->getVar();
        $judge = $request->getGet('judge');
        $day_type = $request->getGet('day_type');
        $effect_date = $request->getGet('effect_date');
        $submit_action_type = $request->getGet('submit_action_type');
        $update_id = $request->getGet('update_id');
        if (empty($judge))
        {
            echo "Judge is required.";
            exit;
        }
        $entryDate = Time::now()->toDateTimeString();
        $inArr = [
            'jcode' => (int)$judge,
            'day_type' => $day_type ? $day_type : NULL,
            'from_date' => $effect_date ? date('Y-m-d', strtotime($effect_date)) : NULL,
            'to_date' => $effect_date ? date('Y-m-d', strtotime($effect_date)) : date('Y-m-d'),
            'entry_date' =>  $entryDate,
        ];
        $session = session();
        if (!$session->has('login') || !$session->get('login')['usercode'])
        {
            echo "Session expired";
            exit;
        }
        $inArr['usercode'] = (int)$session->get('login')['usercode'];
        if ($submit_action_type == "update")
        {
            $id = $update_id ? (int)$update_id : NULL;
            $inArrUpdate = [
                'is_active' => 0,
                'updated_on' => date('Y-m-d H:i:s'),
                'update_by' => $session->get('login')['usercode'],
                'delete_reason' => "Edition",
                'entry_date' =>  $entryDate,
            ];
            $resUpdate =  $this->SingleJudgeNominate->updateData($id, $inArrUpdate);
            if ($resUpdate)
            {
                $res =  $this->SingleJudgeNominate->insertData($inArr);
                $return_arr['flash_msg'] = '<div class="alert alert-warning text-center">Single Judge details have been updated successfully.</div>';
                session()->setFlashdata('success', '<div class="alert alert-warning text-center">Single Judge details have been updated successfully.</div>');
            }
            else
            {
                $session->setFlashdata('error', 'Something went wrong, Please try again.');
            }
            $session->set($return_arr) ;
        }
        else
        {
            $res = $this->SingleJudgeNominate->insertData($inArr);
            if ($res)
            {
                session()->setFlashdata('success', 'Single Judge details have been added successfully');
            } else
            {
                session()->setFlashdata('error', 'Something went wrong, Please try again.');
            }
        }
        return redirect()->to('Listing/SingleJudgeAdvance/nominate');
    }
    
    public function singleJudgeNominatedDeActive()
    {
        $request = service('request');
        $inArr = array();
        $id = $request->getPost('single_judge_id') ? (int) $request->getPost('single_judge_id') : NULL;

        if ($request->getPost('deactivate_flag') == "close") {
            $to_date = $request->getPost('to_date') ? $request->getPost('to_date') : NULL;
            $inArr['to_date'] = $to_date ? date('Y-m-d', strtotime($to_date)) : NULL;
            $inArr['delete_reason'] = "De-Active / Close";
            $success_flag = "De-Activated";
        }

        if ($request->getPost('deactivate_flag') == "delete") {
            $inArr['delete_reason'] = $request->getPost('delete_reason') ? $request->getPost('delete_reason') : NULL;
            $success_flag = "Deleted";
        }

        $inArr['is_active'] = 0;
        $inArr['updated_on'] = date('Y-m-d H:i:s');
        $inArr['update_by'] = session()->get('dcmis_user_idd') ? (int) session()->get('dcmis_user_idd') : 1;


        $resUpdate = $this->SingleJudgeNominate->update($id, $inArr);

        if ($resUpdate) {
            $return_arr = array(
                "status" => "success",
                "message" => 'Single Judge details have been ' . $success_flag . ' successfully.'
            );
        } else {
            $return_arr = array(
                "status" => "error",
                "message" => 'Something went wrong, Please try again.'
            );
        }
        return $this->response->setJSON($return_arr);
    }
    
    
    //Single Judge Advance=>Allocation
    
    public function singleJudgeAdvanceAllocationIndex()
    {
        return view('Listing/SingleJudgeAdvance/singleJudgeAdvanceAllocationIndex');
    }

    // public function singleJudgeAdvanceAllocationIndex($session)
    // {
    //     if($session){
    //         $data = array();
    //         $this->session->set_userdata('dcmis_user_idd', $session);
    //         $this->load->view('CauseList/singleJudgeAdvance', $data);
    //     }
    //     else{
    //         echo "Error:Access Denied";
    //     }
    // }

    public function singleJudgeAdvanceGet()
    {
        $request = service('request');
        $request = $request->getPost();
        $from_date = $request['from_date'] ?? null;
        $to_date = $request['to_date'] ?? null;

        
        
        $data['post_data'] = $request;
        $data['listed_cases'] = $this->SingleJudgeNominate->getSingleJudgeListed($from_date, $to_date);
        
        $data['case_in_pool'] = $this->SingleJudgeNominate->getSingleJudgePools($from_date, $to_date);
        $data['listing_purpose'] = $this->SingleJudgeNominate->getListingPurposes();
        return view('Listing/SingleJudgeAdvance/single_judges_advance_allocation_inputs', $data);
    }

    public function singleJudgeAdvanceAllocationAction()
    {

        $request = service('request');
        $chk_lp = $request->getPost('chk_lp[]');
        $from_date_selected = $request->getPost('from_date_selected');
        $to_date_selected = $request->getPost('to_date_selected');
        $number_of_cases = $request->getPost('number_of_cases');

        if (empty($chk_lp)) {
            return $this->response->setJSON(['status' => 'error', 'msg' => 'Purpose of Listing Required']);
        } elseif (empty($from_date_selected)) {
            return $this->response->setJSON(['status' => 'error', 'msg' => 'From Date Required']);
        } elseif (empty($to_date_selected)) {
            return $this->response->setJSON(['status' => 'error', 'msg' => 'To Date Required']);
        } elseif (empty($number_of_cases)) {
            return $this->response->setJSON(['status' => 'error', 'msg' => 'No. of Cases to list Required']);
        } else
        {
            $from_date = date('Y-m-d', strtotime($from_date_selected));
            $to_date = date('Y-m-d', strtotime($to_date_selected));
            $is_list_printed = $this->SingleJudgeNominate->isPrinted($from_date, $to_date);
            if ($is_list_printed)
            {
                return $this->response->setJSON(['status' => 'error', 'msg' => 'List Already Published']);
            }
            else
            {
                $inArr = [
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'number_of_cases' => $number_of_cases,
                    'listorder' => implode(',', $chk_lp),
                    'usercode' => $this->session->get('dcmis_user_idd') ?? 1,
                    'max_weekly_number' => $this->SingleJudgeNominate->single_judge_advance_max_weekly_number($from_date),
                    'max_item_number' =>  $this->SingleJudgeNominate->single_judge_advance_max_cause_list_number($from_date, $to_date),
                ];
                $total_case_allocated = $this->SingleJudgeNominate->singleJudgeAdvanceAllocation($inArr); // Proper check query pending
                if ($total_case_allocated > 0)
                {
                    $this->SingleJudgeNominate->single_judge_advance_connected_cases_allocation($inArr);
                    return $this->response->setJSON(['status' => 'success', 'msg' => "Total $total_case_allocated Case(s) Allocated"]);
                }
                else
                {
                    return $this->response->setJSON(['status' => 'error', 'msg' => 'Cases Not Found.']);
                }
            }
        }
    }

    public function singleJudgeAdvanceCasesSendToPool()
    {
        $request = service('request');
        $fromDate = date('Y-m-d', strtotime($request->getPost('from_date_selected')));
        $toDate = date('Y-m-d', strtotime($request->getPost('to_date_selected')));
        $usercode = session()->get('login')['usercode'];
        $isListPrinted = $this->SingleJudgeNominate->isPrinted($fromDate, $toDate);
        if ($isListPrinted)
        {
            echo json_encode(array("status" => "error", "msg" => "List Already Publised"));
            exit;
        }
        else
        {
            $result = $this->SingleJudgeNominate->singleJudgeAdvanceCasesSendToPool($fromDate, $toDate, $usercode);
            if ($result > 0)
            {
                echo json_encode(array("status" => "success", "msg" => "Successfully Sent to Pool"));
                exit;
            }
            else
            {
                echo json_encode(array("status" => "error", "msg" => "List Already Publised"));
                exit;
            }
        }
    }
    
    //Single Judge Advance=>Case Drop
    
    public function case_drop()
    {
        return view('Listing/SingleJudgeAdvance/case_drop');
    }
    
    public function field_case_drop_old()
    {
        $request = service('request');
        $formData = $request->getPost();
        $searchType = $request->getPost('search_type');
        $diaryNo = $request->getPost('diary_number');
        $diaryYear = $request->getPost('diary_year');
        $caseType = $request->getPost('case_type');
        $caseNumber = $request->getPost('case_number');
        $caseYear = $request->getPost('case_year');
        //$radioFlag = $request->getPost('radio_flag');

        if ($searchType == "C")
        {
            $diary = $this->CaseAdd->getDiaryNumber($caseType, $caseNumber, $caseYear, $searchType);
            if ($diary)
            {
                $dno = $diary['dn'] . $diary['dy'];
            }
            else
            {
                $dno = 0;
            }
        } else {
            $dno = $diaryNo . $diaryYear;
        }
        pr($dno);
        $availableCases = $this->CaseAdd->checkAvailability($dno);
        if (empty($availableCases))
        {
            return view('Listing/SingleJudgeAdvance/drop_case_view_no_record');
        }
        $case = $availableCases[0];
        $from_dt = $case['from_dt'];
        $to_dt = $case['to_dt'];
        $clno = $case['clno'];
        $brd_slno = $case['brd_slno'];
        $q_next_dt = $case['next_dt'];
        $isPrinted = $this->CaseAdd->isCauseListPrinted($from_dt, $to_dt);
        $chkDropNote = $this->CaseAdd->checkDropNoteStatus($dno, $brd_slno, $from_dt, $to_dt);
        return view('Listing/SingleJudgeAdvance/drop_case_view', [
            'case' => $case,
            'isPrinted' => $isPrinted,
            'chkDropNote' => $chkDropNote,
            'next_dt' => $q_next_dt,
            'from_dt' => $from_dt,
            'to_dt' => $to_dt,
            'dno' => $dno
        ]);
    }

    public function field_case_drop()
    {
        $request = service('request');
        $formData = $request->getPost();
        
        $dn = $request->getPost('diary_number');
        $dyr = $request->getPost('diary_year');
        $ct = $request->getPost('case_type');
        $cn = $request->getPost('case_number');
        $cy = $request->getPost('case_year');
        $radio_flag = $request->getPost('search_type');
        $data['SingleJudgeNominate'] = $this->SingleJudgeNominate;
        $data['CaseAdd'] = $this->CaseAdd;
        $dno = '';
        if (!empty($ct) && !empty($cn) && !empty($cy))
        {
            $data['dno'] = $this->CaseAdd->getDiaryNoSingleJudge($ct, $cn, $cy); // Same h advance list wala
        }
        else if (!empty($dn) && !empty($dyr))
        {
            $data['dno'] = $dn . $dyr;
        }
        //pr($data['dno']);
        return view('Listing/SingleJudgeAdvance/field_case_drop',$data);

       
    }

    public function dropNoteNow()
    {
     
        // print_r($_POST);die;
       
        $request = service('request');
       
        $ucode = session()->get('login')['usercode'];
        $next_dt = $request->getPost('next_dt');
        $from_dt = $request->getPost('from_dt');
        $to_dt = $request->getPost('to_dt');
        $brd_slno = $request->getPost('brd_slno');
        $partno = $request->getPost('partno');
        $dno = $request->getPost('dno');
       
        $drop_rmk = $request->getPost('drop_rmk');
        $mainhead = $request->getPost('mainhead');
        //log_message('debug', "Input values: " . json_encode(compact('ucode', 'next_dt', 'from_dt', 'to_dt', 'brd_slno', 'partno', 'dno', 'drop_rmk', 'mainhead')));

        $result = $this->SingleJudgeNominate->advancedDropNoteIns($ucode, $next_dt, $from_dt, $to_dt, $brd_slno, $dno, $drop_rmk, $mainhead, $partno);

        if ($result == 1) {
            //pr('1');
            return $this->response->setJSON(['status'=>'success','message' => 'Drop Note Created Successfully']);
        } else {
           // pr('2');
            return $this->response->setJSON(['status'=>'error','error' => 'Unable to Make Drop Note']);
        }
    }

    public function caseDropNow()
    {
       
        $request = service('request');
        $dno = $request->getPost('dno');
        $next_dt = $request->getPost('next_dt');
        $from_dt = $request->getPost('from_dt');
        $to_dt = $request->getPost('to_dt');
        $ucode = session()->get('login')['usercode'];
        $result = $this->SingleJudgeNominate->fAdvanceClDropCase($dno, $ucode, $next_dt, $from_dt, $to_dt);
         //pr($result);
      
        if ($result == 1) {
            return $this->response->setJSON(['status'=>'success','message' => 'Case Dropped Successfully']);
        } else {

            return $this->response->setJSON(['status'=>'error', 'error' => 'Unable to Drop']);
        }
    }

    
    
    
    //Single Judge Advance=>Drop Note
    
    public function note()
    {
        $data['listing_dates'] = $this->SingleJudgeNominate->getAdvanceListingDates();
        return view('Listing/SingleJudgeAdvance/note', $data);
    }
    
    public function note_field_old()
    {
        $request = service('request');
        $listDt = $request->getPost('list_dt');
        if (!empty($listDt))
        {
            $listDtExplode = explode('_', $listDt);
            $fromDt = $listDtExplode[0];
            $toDt = $listDtExplode[1];
            $data['dropNotes'] = $this->SingleJudgeNominate->getDropNotes($fromDt, $toDt);
            $data['from_dt'] = $fromDt;
            $data['to_dt'] = $toDt;

            // foreach ($data['dropNotes'] as &$row) {
            //     $advocates = $this->SingleJudgeNominate->getAdvocates($row['diary_no']);
            //     $advocate_names = [];

            //     foreach ($advocates as $advocate) {
            //         $advocate_names[] = strtoupper($advocate['name']) . ' (' . $advocate['advocates'] . ')';
            //     }
            //     $row['advocate_names'] = implode('<br>', $advocate_names);
            // }
            return view('Listing/SingleJudgeAdvance/single_judge_advance_drop_notes', $data);
        }
        else
        {
            return redirect()->back()->with('error', 'Invalid date range provided.');
        }
    }

    public function note_field()
    {
        $request = service('request');
        $listDt = $request->getPost('list_dt');
        if (!empty($listDt))
        {
            $listDtExplode = explode('_', $listDt);
            $fromDt = $listDtExplode[0];
            $toDt = $listDtExplode[1];
            $data['from_dt'] = $fromDt;
            $data['to_dt'] = $toDt;
            $data['model'] = $this->SingleJudgeNominate;
            return view('Listing/SingleJudgeAdvance/single_judge_advance_drop_notes', $data);
        }
        else
        {
            return redirect()->back()->with('error', 'Invalid date range provided.');
        }
    }

   
   
    //Single Judge Advance=>Print/Publish

    public function cl_print_single_judge_advance()
    {
        $data['listing_dates'] = $this->SingleJudgeNominate->getAdvanceListingDates();
        return view('Listing/SingleJudgeAdvance/cl_print_single_judge_advance', $data);
    }

    public function get_cause_list_single_judge_advance()
    {
        $request = service('request');
        $list_dt = $request->getPost('list_dt');
        $listDtExplode = explode('_', $list_dt);
        $fromDate = $listDtExplode[0];
        $toDate = $listDtExplode[1];
        $mainhead = $request->getPost('mainhead');
        $board_type = $request->getPost('board_type');
        $data['from_dt'] = $fromDate;
        $data['to_dt'] = $toDate;
        $data['mainhead'] = $mainhead;
        $data['model'] = $this->SingleJudgeNominate;
        $data['board_type'] = $board_type;
        return view('Listing/SingleJudgeAdvance/get_cause_list_single_judge_advance', $data);
    }
    
    public function call_reshuffle_function_single_judge_advance()
    {
        $request = service('request');
        $response = service('response');
        if ($request->getMethod() === 'post')
        {
            $list_dt = $request->getPost('list_dt');
            $from_cl_no = $request->getPost('from_cl_no');
            $list_dt_explode = explode('_', $list_dt);
            if (count($list_dt_explode) == 2)
            {
                $from_dt = $list_dt_explode[0];
                $to_dt = $list_dt_explode[1];
                $result = $this->SingleJudgeNominate->callReshuffleFunctionSingleJudgeAdvance($from_dt, $to_dt, $from_cl_no);
                if ($result == 1)
                {
                    return $this->response->setJSON([
                        'status' => 'success',
                        'message' => 'Reshuffled Successfully'
                    ]);
                }
                else
                {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Error: Reshuffling Failed'
                    ]);
                }
            }
            else
            {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Error: Invalid List Date format. Please use "YYYY-MM-DD_YYYY-MM-DD".'
                ]);
            }
        }
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Invalid request method.'
        ]);
    }

    public function cl_print_save_single_judge_advance()
    {
        $session = session();
        $ucode = session()->get('login')['usercode'];
        $request = service('request');
        $list_dt = $request->getPost('list_dt');
        $from_dt = explode("_", $list_dt)[0];
        $to_dt = explode("_", $list_dt)[1];
        $encprtContent = json_decode($request->getPost('encprtContent'), true);
        $weekly_number = $request->getPost('weekly_number');
        $weekly_year = $request->getPost('weekly_year');
        $mainhead = 'M';
        $board_type = 'S';
        $pdf_cont = str_replace("scilogo.png", "/home/judgment/cl/scilogo.png", $encprtContent);
        $exists = $this->SingleJudgeNominate->checkIfPrinteds($from_dt, $to_dt);
        if (!empty($exists))
        {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Already Printed.']);
        }
        else
        {
            $weekly_number = !empty($weekly_number) ? (int)$weekly_number : 0; 
            $weekly_year = !empty($weekly_year) ? (int)$weekly_year : 0;
            $data =
            [
                'from_dt' => $from_dt,
                'to_dt' => $to_dt,
                'weekly_no' => $weekly_number,
                'weekly_year' => $weekly_year,
                'usercode' => $ucode,
            ];
            $success = $this->SingleJudgeNominate->insertPrintedCauseList($data);
            if (!empty($success))
            {
                $prtContent = $this->removeIgnoreInPrintDiv($encprtContent);
                $savePath = WRITEPATH . 'single_judge_advance/' . $list_dt . '/';
                if (!is_dir($savePath))
                {
                    mkdir($savePath, 0777, true); 
                }
                
                $fileName = 'AV_' . $board_type . '.pdf';
                $filePath = $mainhead . "_" . $board_type;
                
                try
                {
                    $mpdf = new Mpdf(); 
                    $mpdf->SetDisplayMode('fullpage');
                    $mpdf->SetHTMLFooter('<div style="text-align: center; font-size: 12px;">Page {PAGENO} of {nbpg}</div>');
                    $mpdf->WriteHTML($prtContent);
                    $mpdf->Output($filePath, 'F');
                    return $this->response->setJSON([
                        'status' => 'success',
                        'message' => 'PDF generated successfully!',
                       //For lOcal Dir
                        //'filePath' => $filePath
                        // For Live Server dir
                        'filePath' => base_url('writable/single_judge_advance/' . $fileName) 
                    ]);
                }
                catch (\Exception $e)
                {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Failed to generate PDF: ' . $e->getMessage()
                    ]);
                } } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Error: Cause List Not Ported/Published.']);
            }
        }
    }

    private function removeIgnoreInPrintDiv(string $html): string
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD); // Suppress warnings for malformed HTML
        $xpath = new \DOMXPath($dom);
        $nodes = $xpath->query('//*[contains(@class, "ignore_in_print")]');
        foreach ($nodes as $node)
        {
            $node->parentNode->removeChild($node);
        }

        return $dom->saveHTML();
    }
}
