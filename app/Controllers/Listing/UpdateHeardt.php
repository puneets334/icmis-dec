<?php

namespace App\Controllers\Listing;

use App\Controllers\BaseController;
use CodeIgniter\Controller;

use App\Models\Listing\AllocationTp;
use App\Models\Listing\CaseType;
use App\Models\Listing\CaseAdd;
use App\Models\Listing\ListingPurpose;
use App\Models\Listing\Subheading;
use App\Models\Listing\Roster;
use App\Models\Listing\Heardt;
use App\Models\Common\Dropdown_list_model;


class UpdateHeardt extends BaseController
{

    public $AllocationTp;
    public $diary_no;
    public $CaseType;
    public $CaseAdd;
    public $ListingPurpose;
    public $Subheading;
    public $Roster;
    public $Heardt;
    public $Dropdown_list_model;

    function __construct()
    {
        $this->AllocationTp = new AllocationTp();
        $this->CaseType = new CaseType();
        $this->CaseAdd = new CaseAdd();
        $this->ListingPurpose = new ListingPurpose();
        $this->Subheading = new Subheading();
        $this->Roster = new Roster();
        $this->Heardt = new Heardt();
        $this->Dropdown_list_model = new Dropdown_list_model();
       
    }

    public function update_heardt_report()
    { 
      return view('Listing/UpdateHeardt/update_heardt_report'); 
    }

 
    public function update_heardt_report_get()
    {
        $request = service('request');
        $list_dt = $request->getPost('list_dt');
        $datetype = $request->getPost('datetype');
     
        if ($datetype == 1) {
            $string_next_dt = "next_dt = '" . date('Y-m-d', strtotime($list_dt)) . "' AND ";
            $string_heading = "Cause List " . date('d-m-Y', strtotime($list_dt));
        } elseif ($datetype == 2) {
            $string_next_dt = "DATE(ent_dt) = '" . date('Y-m-d', strtotime($list_dt)) . "' AND ";
            $string_heading = "Entry Date " . date('d-m-Y', strtotime($list_dt));
        }
        
        $data['string_next_dt'] = $string_next_dt;
        $data['string_heading'] = $string_heading;
        $data['cases'] = $this->AllocationTp->getCases($data);
        
        return view('Listing/UpdateHeardt/transfer_cases', $data);
    }

    public function update_heardt()
    {
        $request = \Config\Services::request();
        $data = [];
        
        if ($request->getMethod() === 'post' && $this->validate([
            'search_type' => ['label' => 'search Type', 'rules' => 'required|min_length[1]|max_length[1]']
        ])) {
            
            $search_type = $this->request->getPost('search_type');
            
            if ($search_type == 'D' && $this->validate([
                'diary_number' => ['label' => 'Diary Number', 'rules' => 'required|min_length[1]|max_length[8]'],
                'diary_year' => ['label' => 'Diary Year', 'rules' => 'required|min_length[4]'],
            ])) {
                $diary_number = $this->request->getPost('diary_number');
                $diary_year = $this->request->getPost('diary_year');
                $diary_no = $diary_number . $diary_year;
                $get_main_table = $this->Dropdown_list_model->get_diary_details_by_diary_no($diary_no);
            } elseif ($search_type == 'C' && $this->validate([
                'case_type' => ['label' => 'Case Type', 'rules' => 'required|min_length[1]|max_length[2]'],
                'case_number' => ['label' => 'Case Number', 'rules' => 'required|min_length[1]|max_length[8]'],
                'case_year' => ['label' => 'Case Year', 'rules' => 'required|min_length[4]'],
            ])) {
                $case_type = $this->request->getPost('case_type');
                $case_number = $this->request->getPost('case_number');
                $case_year = $this->request->getPost('case_year');
                
                $get_main_table = $this->Dropdown_list_model->get_case_details_by_case_no($case_type, $case_number, $case_year);
            } else {
                return $this->response->setJSON(['success' => 0, 'error' => 'Data not Found']);
            }

            if(empty($get_main_table)) {
                return $this->response->setJSON(['success' => 0, 'error' => 'Data not Found']);
            }
        }

        if (!empty($get_main_table)) {
            $this->session->set(array('filing_details' => $get_main_table));
            return $this->response->setJSON(['redirect' => base_url('Listing/UpdateHeardt/get_proposal_heardt')]);
        }

        $data['casetype'] = get_from_table_json('casetype');
        $data['sectionHeading'] = "UPDATE HEARDT TABLE";
        $data['formAction'] = 'Listing/UpdateHeardt/index';

        return view('Listing/UpdateHeardt/index', $data);
    }

    public function get_proposal_heardt()
    {
        $request = service('request');
        $usercode = session()->get('login')['usercode'];

        $filing_details = session()->get('filing_details');
        $dno = $filing_details['diary_no'];
        $dyr = session()->get('session_diary_yr');
        $caseName = $this->CaseAdd->getCaseName($dno);
        $hearingDetails = $this->CaseAdd->getHearingDetails_1($dno);
        if(!$hearingDetails){
            session()->setFlashdata("error", 'DATA NOT IN HEARDT TABLE');
            return redirect()->to('Listing/UpdateHeardt/update_heardt');
        }
        $caseDetails = $this->CaseAdd->getCaseDetails_1($dno);
        $casetype = $this->CaseAdd->getCaseTopDetails($dno);
        $r_case =[];
        if(isset($casetype['fil_no_fh']) && ($casetype['fil_no_fh'] != '' || $casetype['fil_no_fh'] != NULL)){
            $r_case = $this->CaseAdd->getCaseTypeR($casetype['fil_no_fh']);
        }
        navigate_diary($dno);
        
        $categories = $this->CaseAdd->getCategories($dno);
        $main_case = $this->CaseAdd->getMainOrConnectedCase($dno);
        $getCoramEntries = $this->CaseAdd->getCoramEntries($dno);
        $mainSuppOptions  = $this->CaseAdd->getAllMainSupp($dno);
        $listingPurpose = $this->ListingPurpose->getPurposeList();
        //$applications = $this->CaseAdd->getInterlocutoryApplications($dno);
        $if_list_is_printed = false;
        $if_list_is_printed = $this->CaseAdd->isListPrinted($caseDetails['next_dt'],$caseDetails['mainhead'], $caseDetails['roster_id'], $caseDetails['clno'], $caseDetails['main_supp_flag']);
        
        $m_f = '';
        if ($caseDetails['mainhead'] == 'M') {
            $m_f = " AND m_f='1' ";
        } else if ($caseDetails['mainhead'] == 'F') {
            $m_f = " AND m_f='2' ";
        } else if ($caseDetails['mainhead'] == 'L') {
            $m_f = " AND m_f='5' ";
        } else if ($caseDetails['mainhead'] == 'S') {
            $m_f = " AND m_f='7' ";
        }

        if($caseDetails['next_dt']){
            $todate=" AND r.from_date = '" . $caseDetails['next_dt']. "' ";
        }else{
            $todate=" AND r.from_date is null";
        }
        
        if ($request->getPost('board') == 'R') {
            $todate = " AND r.to_date is null";
        }
        
        $board_type = " AND mb.board_type_mb='{$caseDetails['board_type']}' ";
        if ($caseDetails['board_type'] == 'C') {
            $board_type = " AND (mb.board_type_mb='C' OR mb.board_type_mb='CC') ";
        }
        
        $judgeData =$this->CaseAdd->getJudges12($m_f, $todate, $board_type);
      
         $reason = '';

        if ($dno) {
            $reasonData = $this->CaseAdd->getLatestReason($dno);
            $reason = $reasonData['reason'] ?? '';
        }
        if ($caseDetails['mainhead'] != 'F') {
            $subheadings = $this->Subheading->getSubheadings($caseDetails['mainhead'], $caseDetails['case_grp']);
        } else {
            $subheadings = $this->Subheading->getMulCategorySubheadings($dno);
        }
        
        if (!empty($caseDetails['tentative_cl_dt'])) {
            $caseDetails['tentative_cl_dt'] = date('d-m-Y', strtotime($caseDetails['tentative_cl_dt']));
        }
        if (!empty($caseDetails['next_dt'])) {
            $caseDetails['next_dt'] = date('d-m-Y', strtotime($caseDetails['next_dt']));
        }
        
        return view('Listing/UpdateHeardt/heardt_case_details', [
            'caseDetails' => $caseDetails,
            'categories' => $categories,
            'caseName' => $caseName,
            'hearingDetails' => $hearingDetails,
            'diary_no' => $dno,
            'diary_year' => $dyr,
            'getCoramEntries'=>$getCoramEntries,
            'listingPurpose'=>$listingPurpose,
            'subheadings'=>$subheadings,
            'mainSuppOptions'=>$mainSuppOptions,
            'reason'=>$reason,
            'is_nmd' => $caseDetails['is_nmd'] ?? 'N' ,
            'if_list_is_printed'=>$if_list_is_printed,
            'judgeData'=>$judgeData,
            'casetype'=> $casetype,
            'r_case'=> $r_case,
            'main_case'=> $main_case
        ]);
    }

    public function new_up_he_check_part()
    {
        $request = service('request');
        $date = $request->getVar('date');
        $coram = $request->getVar('coram');
        $heading = $request->getVar('heading');
        $session = $request->getVar('session');
        $mainSuppFlag = $request->getVar('main_supp_flag');
        $is_nmd = $request->getVar('is_nmd');
        $if_list_is_printed = 0;
        $data = $request->getPost();
         
        $ifListIsPrinted = 0;
        $tempField = explode('~', $coram);
        $coram = isset($tempField[0]) ? $tempField[0] : 0;
        $judges = isset($tempField[1]) ? $tempField[1] : 0;

        if ($coram != 0) {
            $formattedDate = date('Y-m-d', strtotime($date));
            $ifPrinted = $this->CaseAdd->isListPrinted($formattedDate, $heading, $coram, $session, $mainSuppFlag);

            if (!empty($ifPrinted)) {
                $ifListIsPrinted = 1;
            }
        }
        return $this->response->setJSON(['if_list_is_printed' => $ifListIsPrinted]);
    }
    
    public function save_proposal_heardt()
    {
        $request = service('request');
        $data = [
            'dno' => $request->getPost('dno'),
            'ndt' => $request->getPost('ndt'),
            'tdt' => $request->getPost('tdt'),
            'session' => $request->getPost('session'),
            'brd_slno' => $request->getPost('brd_slno'),
            'heading' => $request->getPost('heading'),
            'subhead' => $request->getPost('subhead'),
            'coram' => $request->getPost('coram'),
            'main_supp_flag' => $request->getPost('main_supp_flag'),
            'sitting_jud' => $request->getPost('sitting_jud'),
            'purList' => $request->getPost('purList'),
            'sinfo' => $request->getPost('sinfo'),
            'board_type' => $request->getPost('board_type'),
            'hd_subhead' => $request->getPost('hd_subhead'),
            'reason_md' => $request->getPost('reason_md'),
            'is_nmd' => $request->getPost('is_nmd')
        ];
        

        if (strlen(trim($data['reason_md'])) < 20) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Please Entre Reason with minimum 20 characters...']);
        } else {
            $ucode = session()->get('login')['usercode'];
            $data['sinfo'] = !empty($data['sinfo']) ? htmlspecialchars(trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $data['sinfo'])))) :'';
            
            if (!$this->isUserAuthorized($ucode)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'You are not authorized.']);
            }
            $tempfield = explode('~', $data['coram']);
            $data['coram'] = isset($tempfield[0]) ? $tempfield[0] : 0;
            $data['judges'] = isset($tempfield[1]) ? $tempfield[1] : 0;
            
            $if_list_is_printed = false;
            if($data['coram'] != 0){
                $if_list_is_printed = $this->Heardt->checkIfListIsPrinted($data);
            }

            $sel_from_heardt = $this->Heardt->getHeardtDetails($data['dno']);

            if($sel_from_heardt){
                $chk_in_l_h = $this->Heardt->checkLastHeardtDetails($data['dno'], $sel_from_heardt);
                if($chk_in_l_h == 0) {
                    $lastHeardt['diary_no'] = $data['dno'];
                    $lastHeardt['conn_key'] = $sel_from_heardt['conn_key'];
                    $lastHeardt['next_dt'] = $sel_from_heardt['next_dt'];
                    $lastHeardt['mainhead'] = $sel_from_heardt['mainhead'];
                    $lastHeardt['subhead'] = $sel_from_heardt['subhead'];
                    $lastHeardt['clno'] = $sel_from_heardt['clno'];
                    $lastHeardt['brd_slno'] = $sel_from_heardt['brd_slno'];
                    $lastHeardt['roster_id'] = $sel_from_heardt['roster_id'];
                    $lastHeardt['judges'] = $sel_from_heardt['judges'];
                    $lastHeardt['coram'] = $sel_from_heardt['coram'];
                    $lastHeardt['board_type'] = $sel_from_heardt['board_type'];
                    $lastHeardt['usercode'] = $sel_from_heardt['usercode'];
                    $lastHeardt['ent_dt'] = $sel_from_heardt['ent_dt'];
                    $lastHeardt['module_id'] = $sel_from_heardt['module_id'];
                    $lastHeardt['mainhead_n'] = $sel_from_heardt['mainhead_n'];
                    $lastHeardt['subhead_n'] = $sel_from_heardt['subhead_n'];
                    $lastHeardt['main_supp_flag'] = $sel_from_heardt['main_supp_flag'];
                    $lastHeardt['listorder'] = $sel_from_heardt['listorder'];
                    $lastHeardt['tentative_cl_dt'] = $sel_from_heardt['tentative_cl_dt'];
                    $lastHeardt['listed_ia'] = $sel_from_heardt['listed_ia'];
                    $lastHeardt['sitting_judges'] = $sel_from_heardt['sitting_judges'];
                    $lastHeardt['list_before_remark'] = $sel_from_heardt['list_before_remark'];
                    $lastHeardt['is_nmd'] = $sel_from_heardt['is_nmd'];
                    $lastHeardt['no_of_time_deleted'] = $sel_from_heardt['no_of_time_deleted'];

                    if($sel_from_heardt['roster_id']==0 OR ($sel_from_heardt['next_dt'] <= date('Y-m-d') AND $sel_from_heardt['roster_id'] > 0)) {
                        $this->Heardt->addLastHeardt($lastHeardt);
                    } else {
                        if($if_list_is_printed == true){
                            $this->Heardt->addLastHeardt($lastHeardt);
                        }else if($if_list_is_printed == false){
                            $this->Heardt->addLastHeardt($lastHeardt);
                        }
                    }
                }

                if(($sel_from_heardt['mainhead_n'] == $data['heading']) || ($sel_from_heardt['mainhead_n'] == 'M' && $data['heading'] == 'F'))
                    $headings=['mainhead'=>$data['heading'], 'subhead'=>$data['subhead'],'mainhead_n'=>$data['heading'],'subhead_n'=> $data['subhead']];
                else if(($sel_from_heardt['mainhead_n'] == 'F' && $data['heading'] == 'M') || ($data['heading'] == 'L') || ($data['heading'] == 'S'))
                    $headings=['mainhead'=>$data['heading'], 'subhead'=>$data['subhead']];
                else {
                if($sel_from_heardt['mainhead']=='')
                    $headings=['mainhead'=>$data['heading'], 'subhead'=>$data['subhead'],'mainhead_n'=>$data['heading'],'subhead_n'=> $data['subhead']];
                }

                //Update Case Data
                $dataArray = $headings;
                $dataArray['diary_no'] = $data['dno'];
                $dataArray['usercode'] = $ucode;
                $dataArray['next_dt'] = date('Y-m-d', strtotime($data['ndt']));
                $dataArray['clno'] = $data['session'];
                $dataArray['brd_slno'] = $data['brd_slno'];
                $dataArray['roster_id'] = $data['coram'];
                $dataArray['judges'] = $data['judges'];
                $dataArray['board_type'] = $data['board_type'];
                $dataArray['module_id'] = 10;
                $dataArray['main_supp_flag'] = $data['main_supp_flag'];
                $dataArray['listorder'] = $data['purList'];
                $dataArray['tentative_cl_dt'] = date('Y-m-d', strtotime($data['tdt']));
                $dataArray['sitting_judges'] = $data['sitting_jud'];
                $dataArray['is_nmd'] = $data['is_nmd'];
                $dataArray['ent_dt'] = date('Y-m-d H:i:s');
                $this->Heardt->updateHeardtDetails($dataArray);
            }

            //Insert Reason
            $reasonArray = [
                'diary_no' => $data['dno'],
                'reason' => $data['reason_md'],
                'usercode' => $ucode,
                'ent_dt' => date('Y-m-d H:i:s'),
                'updated_by_ip' => getClientIP(),
                'updated_by' => session()->get('login')['usercode'],
                'create_modify' => date("Y-m-d H:i:s")
            ];
            $reason_up = insert('update_heardt_reason', $reasonArray);

            //Update Remark
            $remark = [
                'remark' => $data['sinfo'],
                'usercode' => $ucode,
                'ent_dt' => date('Y-m-d H:i:s'),
            ];
            $this->Heardt->checkAndUpdateRemark($remark, $data['dno']);
            return $this->response->setJSON(['status' => 'success', 'message' => 'HEARDT Updated Successfully!!!']);    
        }
    }

    private function revertDate($date)
    {
        $date = explode('/', $date);
        return $date[2] . '-' . $date[1] . '-' . $date[0];
    }

    private function isUserAuthorized($ucode)
    {
        $authorizedEmpIds = [2628, 2620, 1127, 3642, 1194, 3047, 3054, 3742, 4042, 4605,
            4387, 4655, 4784, 4782, 4932, 5002, 4922, 4925, 2666, 1268, 1971,
            4323, 2231, 4130, 4326, 4322, 4448, 4628, 4995, 4320, 3910, 2639, 
            5653, 5642, 5695, 5712, 5726, 5623, 5691, 5744, 5774, 1];
        return in_array($ucode, $authorizedEmpIds);
    }

    public function get_coram(){
        $request = service('request');
        $date = date('Y-m-d', strtotime($request->getPost('date')));
        $board = $request->getPost('board');
        $heading = $request->getPost('heading');
        $data['judge_rs'] = $this->Heardt->getCoram($date, $board, $heading);
        return view('Listing/UpdateHeardt/get_coram', $data);
        
    }

    public function set_sitting_jud() {
        $request = service('request');
        $coram = $request->getPost('coram');
        if($coram == 0){
            echo '0';
        } else {
            $coram = explode('~',$coram);
            $coram_new = explode(',',$coram[1]);
            echo count($coram_new).'#'.$coram[2];
        }
    }

    public function get_subhead_for_heardt(){
        $request = service('request');
        $heading = $request->getPost('heading');
        $side = $request->getPost('side');
        $diary_no = $request->getPost('dno');
        $data['subheading'] = $this->Heardt->getSubheading($diary_no, $side, $heading);
        $data['heading'] = $heading;
        return view('Listing/UpdateHeardt/get_subhead_for_heardt', $data);    
    }
    
}