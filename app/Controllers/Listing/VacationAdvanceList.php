<?php

namespace App\Controllers\Listing;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Listing\CaseType;
use App\Models\Listing\CaseAdd;

use App\Models\Listing\AllocationTp;
use App\Models\Common\Dropdown_list_model;

class VacationAdvanceList extends BaseController
{
    public $AllocationTp;
    public $diary_no;
    public $CaseType;
    public $CaseAdd;
    public $Dropdown_list_model;

    function __construct()
    {
        $this->AllocationTp = new AllocationTp();
        $this->CaseType = new CaseType();
        $this->CaseAdd = new CaseAdd();
        $this->Dropdown_list_model = new Dropdown_list_model();
    }

    public function report()
    {
        return view('Listing/VacationAdvanceList/report');
    }

    public function VacationReports()
    {
        $data['case_result'] = '';
        $data['app_name'] = 'Vacation Report';
        $request = \Config\Services::request();
        if ($this->request->getMethod() == 'post') {
            $data['from_date'] = $request->getPost('from_date');
            $data['to_date'] = $request->getPost('to_date');
            $data['type'] = $request->getPost('listDecline_remain');
            $data['case_result'] = $this->AllocationTp->getVacationListReports($data['from_date'], $data['to_date'], $data['type']);
        }
        return view('Listing/VacationAdvanceList/report', $data);
    }

    public function save_case_in_vacation_list()
    {
        $request = service('request');       
        $fil_no = $request->getPost('fil_no');
        $usercode = $request->getPost('usercode');

        if ($this->CaseAdd->isCaseInVacationPool($fil_no)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Said case is already added in Vacation List Pool.']);    
        }
        
        $result = $this->CaseAdd->addCaseToVacationPool($fil_no, $usercode);
        if ($result) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Record added to Vacation List Pool']);    
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to add record to Vacation List Pool']);    
        }
    }

    public function regular_advance_weekly()
    {   
        return view('Listing/VacationAdvanceList/regular_advance_weekly');
    }
    public function get_regular_advance_weekly()
    {   
        $result = $this->CaseAdd->get_regular_advance_weekly();
       
        return $result;
    }

    public function list_regular_advance_weekly()
    {   
        $request = service('request');
        $diary_numbers_string_post = $request->getPost('diaryNos');
        $diary_numbers_string1 = implode(',', $diary_numbers_string_post);
        $data['list_weekly'] = $this->CaseAdd->list_regular_advance_weekly($diary_numbers_string1);
        return view('Listing/VacationAdvanceList/list_regular_advance_weekly', $data);
       // return $result;
    }

    public function VacationDel()
    {   
        return view('Listing/VacationAdvanceList/vacation_del');
    }

    public function getVacationAdvanceList()
    {
        $request = service('request');
        $userId = $request->getPost('userID');
        if (isset($_POST['mainhead'])) {
            $mainhead = $_POST['mainhead'];
            $mainhead_query_part = $mainhead ? " and mainhead = '$mainhead'" : " and mainhead = ''";
        } else {
            $mainhead = ''; // Or null, or a default value
            $mainhead_query_part = '';
        }
        $data['mainhead'] = $mainhead;
        $data['caseAddModel'] = $this->CaseAdd;
        $data['cases']= $this->CaseAdd->getDiary($mainhead_query_part);
        return view('Listing/VacationAdvanceList/get_vacation_advance_list', $data);
    }

    public function declineVacationListCases()
    {
        $request = service('request');
        $diaryNos = implode(",", $request->getPost('diary_no'));
        $updatedFromSystem = $request->getServer('REMOTE_ADDR');
        $userID = $request->getPost('userID');
        if ($this->CaseAdd->logVacationAdvances($diaryNos)) {
            if ($this->CaseAdd->updateVacationAdvances($diaryNos, $userID, $updatedFromSystem)) {
                $arr = [];
                foreach ($request->getPost('diary_no') as $value) {
                    $record = $this->CaseAdd->getVacationAdvance($value);
                    $var = '';
                    if ($record->is_deleted == 't') {
                        $var .= "<a class='btn btn-xs btn-danger' onclick=\"javascript:confirmBeforeList($record->diary_no);\">";
                        $var .= '<span id="deleteButton" class="ui-icon ui-icon-closethick"></span> Declined</a>';
                    } else {
                        if ($record->is_fixed != 'Y') {
                            $var .= "<input type='checkbox' name='vacationList' id='vacationList' value='$record->diary_no>";
                        } else {
                            $var .= "<span style='color:green;'>Fixed For <br> Partial Court Working Days</span><br/>";
                        }
                    }
                    $arr[$value] = $var;
                }
                return $this->response->setJSON($arr);
            } else {
                return $this->response->setStatusCode(500, "Error updating vacation advances");
            }
        } else {
            return $this->response->setStatusCode(500, "Error logging vacation advances");
        }
    }

    public function restoreVacationAdvanceList()
    {
        $request = service('request');
        $diaryNo = $request->getPost('diary_no');
        $userID = $request->getPost('userID');
        $updatedFromSystem = $this->request->getIPAddress();
        
        if ($this->CaseAdd->restoreVacationAdvanceListLog($diaryNo)) {
            if ($this->CaseAdd->restoreVacationAdvanceList($diaryNo, $userID, $updatedFromSystem)) {
                $vacationAdvance = $this->CaseAdd->getVacationAdvance($diaryNo);

                $response = $this->generateResponse($vacationAdvance);
                return $this->response->setJSON($response);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Update failed']);
            }
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Log insertion failed']);
        }
    }

    private function generateResponse($vacationAdvance)
    {
        
        $response = [];

        if ($vacationAdvance) {
            if ($vacationAdvance->is_deleted == 't') {
                $response['html'] = "<a class='btn btn-xs btn-danger' onclick=\"confirmBeforeList({$vacationAdvance->diary_no});\">
                    <span id='deleteButton' class='ui-icon ui-icon-closethick'></span> Declined</a>";
            } 
            else {
              
                if ($vacationAdvance->is_fixed != 'Y') {
                 
                    $response['html'] = "<input type='checkbox' name='vacationList' id='vacationList' value='{$vacationAdvance->diary_no}'>";
                } else {
                  
                    $response['html'] = "<span style='color:green;'>Fixed For <br> Vacation</span><br/>";
                }
            }
        }

        return $response;
    }

    public function addCase()
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
                $diary_number = $get_main_table['dn'];
                $diary_year = $get_main_table['dy'];
            } else {
                return $this->response->setJSON(['success' => 0, 'error' => 'Data not Found']);
            }

            if(empty($get_main_table)) {
                return $this->response->setJSON(['success' => 0, 'error' => 'Data not Found']);
            }
        }
        if (!empty($get_main_table)) {
            $get_main_table['diary_number'] = $diary_number;
			$get_main_table['diary_year']   = $diary_year;
            $this->session->set(array('filing_details' => $get_main_table));
            return $this->response->setJSON(['redirect' => base_url('Listing/VacationAdvanceList/get_addCase_details')]);
        }

        $data['casetype'] = get_from_table_json('casetype');
        $data['sectionHeading'] = "Add Case in Vacation List";
        $data['formAction'] = 'Listing/VacationAdvanceList/index';

        return view('Listing/VacationAdvanceList/index', $data);
    }

    public function get_addCase_details()
    {
        $filing_details = session()->get('filing_details');
        $dno = $filing_details['diary_no'];
        $case_name = $this->CaseAdd->getCaseName($dno);
        $chk_heardt = $this->CaseAdd->getHearingDetails_1($dno);
        if(!$chk_heardt){
            session()->setFlashdata("error", 'DATA NOT IN HEARDT TABLE');
            return redirect()->to('Listing/VacationAdvanceList/addCase');
        }

        $caseDetails = $this->CaseAdd->getCaseDetails_1($dno);
        $casetype = $this->CaseAdd->getCaseTopDetails($dno);
        $r_case =[];
        //if(isset($casetype['fil_no_fh']) && ($casetype['fil_no_fh'] != '' || $casetype['fil_no_fh'] != NULL)){
        if(isset($casetype['fil_no_fh']) && !empty($casetype['fil_no_fh'])){
            $r_case = $this->CaseAdd->getCaseTypeR($casetype['fil_no_fh']);
        }
        navigate_diary($dno);

        $categories = $this->CaseAdd->getCategories($dno);
        $main_case = $this->CaseAdd->getMainOrConnectedCase($dno);
        $isInVacationList = $this->CaseAdd->isCaseInVacationList($dno);
        $usercode = session()->get('login')['usercode'];
        return view('Listing/VacationAdvanceList/case_details', [
            'caseDetails' => $caseDetails,
            'case_name' => $case_name,
            'categories' => $categories,
            'main_case' => $main_case,
            'diary_no' => $dno,
            'isInVacationList' => $isInVacationList,
            'casetype' => $casetype,
            'r_case' => $r_case,
            'usercode' => $usercode,
        ]);
    }
}
