<?php

namespace App\Controllers\Listing;

use App\Controllers\BaseController;

use App\Models\Menu_model;
use CodeIgniter\Controller;
use CodeIgniter\Model;
use App\Models\Filing\AdvocateModel;
use App\Models\Casetype;
//use App\Models\Entities\Main;
use App\Models\Listing\CaseAdd;
use App\Models\Listing\CaseDrop;
use App\Models\Listing\Heardt;
use App\Models\Listing\Roster;
use App\Models\Listing\AdvancedDropNote;
use App\Models\Common\Dropdown_list_model;

class DropNoteAdvance extends BaseController
{

    public $diary_no;
    public $Casetype;
    public $CaseAdd;
    public $CaseDrop;
    public $Heardt;
    public $AdvancedDropNote;
    public $Dropdown_list_model;


    function __construct()
    {
        $this->Casetype = new Casetype();
        $this->CaseAdd = new CaseAdd();
        $this->CaseDrop = new CaseDrop();
        $this->Heardt = new Heardt();
        $this->AdvancedDropNote = new AdvancedDropNote();
        $this->Dropdown_list_model = new Dropdown_list_model();
        ini_set('memory_limit','4024M');
    }

    public function case_drop()
    {
        $usercode = session()->get('login')['usercode'];
        $userTypeString = getUser_dpdg_full_2($usercode);
        $userType = explode('~', $userTypeString);
        if (($userType[0] != 1 && $userType[0] != 57 && $userType[0] != 3 && $userType[0] != 4) && ($userType[6] != 450)) {
            echo "YOU ARE NOT AUTHORISED";
            exit();
        }

        $data = [
            // 'caseTypes' => $caseTypes,
            'session_diary_no' => session()->get('session_diary_no'),
            'session_diary_yr' => session()->get('session_diary_yr'),
        ];

        return view('Listing/advance_list/case_drop', $data);
    }


    public function case_drop_info()
    {
        helper('form');
        $request = service('request');
        if ($request->getPost('search_type') == "C") {
            $case_type = $request->getPost('case_type');
            $case_number = (int) $request->getPost('case_number');
            $case_year = (int) $request->getPost('case_year');
            //$get_dno = $this->CaseDrop->getDiaryNumber($ct, $cn, $cy);
            $get_main_table = $this->Dropdown_list_model->get_case_details_by_case_no($case_type, $case_number, $case_year);
            $dno = 0;
            if (!empty($get_main_table)) {
                $dno = $get_main_table['diary_no'];
            }

            $request->setGlobal('post', ['dno' => $dno]);
        } else {
            $dno = $request->getPost('diary_number') . $request->getPost('diary_year');
            $request->setGlobal('post', ['dno' => $dno]);
        }

        $caseData = $this->CaseDrop->getCaseDetails($dno);
        if (empty($caseData)) {
            return "Record Not Available/Case Not listed";
        } else {

            $chk_drop_note = 1;
            $cl_result = $this->CaseDrop->advance_cl_printed($caseData['next_dt']);
            if($cl_result == 1){
                $ro_sq = $this->CaseDrop->checkDropNote($caseData['next_dt'], $caseData['brd_slno'], $caseData['j1'], $dno);
                $chk_drop_note = $ro_sq['count'];
            }

            $judge_names = $this->CaseDrop->f_get_judge_names($caseData['j1']);

            $data = [
                'pet_name' => $caseData['pet_name'],
                'res_name' => $caseData['res_name'],
                'jcode' => $caseData['j1'],
                'next_dt' => $caseData['next_dt'],
                'brd_slno' => $caseData['brd_slno'],
                'partno' => $caseData['clno'],
                'dno' => $dno,
                'pno' => $caseData['pno'],
                'rno' => $caseData['rno'],
                'chk_drop_note' => $chk_drop_note,
                'judge_names' => $judge_names
            ];
            return view('Listing/advance_list/case_drop_info', $data);
        }
    }

  


    public function caseDropNow()
    {
        $request = service('request');
        $dno = $request->getPost('dno');
        $ldates_exp = explode("/",str_replace("-","/",$request->getPost('ldates')));
        $ldates = $ldates_exp[2]."-".$ldates_exp[1]."-".$ldates_exp[0];
        $ucode = session()->get('login')['usercode'];
        $result = $this->CaseDrop->fAdvanceClDropCase($dno, $ucode, $ldates);
        if ($result == 1) {
            return $this->response->setJSON(['message' => 'Case Dropped Successfully']);
        } else {
            return $this->response->setJSON(['error' => 'Unable to Drop']);
        }
    }

    public function dropNoteNow()
    {
        $request = service('request');
        $ucode = session()->get('login')['usercode'];
        $next_dt = $request->getPost('next_dt');
        $from_dt = $request->getPost('from_dt');
        $to_dt = $request->getPost('to_dt');
        $brd_slno = $request->getPost('brd_slno');
        $partno = $request->getPost('partno');
        $dno = $request->getPost('dno');
        $roster_id = $request->getPost('roster_id');
        $drop_rmk = $request->getPost('drop_rmk');
        $mainhead = $request->getPost('mainhead');
        //log_message('debug', "Input values: " . json_encode(compact('ucode', 'next_dt', 'from_dt', 'to_dt', 'brd_slno', 'partno', 'dno', 'drop_rmk', 'mainhead')));
        $res_drp_note = $this->CaseDrop->advancedDropNoteIns($ucode, $next_dt, $brd_slno, $dno, $roster_id, $drop_rmk, $mainhead, $partno);

        if ($res_drp_note == 1) {
            return $this->response->setJSON(['message' => 'Drop Note Created Successfully']);
        } else {
            return $this->response->setJSON(['error' => 'Unable to Make Drop Note']);
        }
    }


    // Drop not menu


    public function note()
    {
        $data['listing_dates'] = $this->Heardt->getUpcomingDates();
        $data['benches'] = $this->Heardt->getBenches();
        $data['AdvancedDropNote']=$this->AdvancedDropNote;
        return view('Listing/advance_list/note', $data);
    }

    public function get_cl_print_mainhead()
    {
        $request = service('request');
        $mainhead = $request->getPost('mainhead');
        $board_type = $request->getPost('board_type');



        $options = $this->Heardt->getClPrintMainhead($mainhead, $board_type);

        echo $options;
    }

    public function get_cl_print_benches()
    {
        $request = service('request');

        $mainhead = $request->getPost('mainhead');
        $board_type = $request->getPost('board_type');
        $list_dt = $request->getPost('list_dt');

        $options = $this->Heardt->getClPrintBenches($mainhead, $board_type, $list_dt);

        echo $options;
    }



    public function note_field()
    {
        
        $request = service('request');
        $list_dt = $request->getPost('list_dt');
        $from_dt = $list_dt;
        $to_dt = $list_dt;
        $mainhead = $request->getPost('mainhead');
        $board_type = $request->getPost('board_type');
        // $data['getNotes'] = $this->AdvancedDropNote->getNotes($next_dts,$board_type);
        
        $data['from_dt'] = $from_dt;
        $data['board_type'] = $board_type;
        $data['list_dt'] = $list_dt;
        $data['model'] = $this->AdvancedDropNote;
       
       
        return view('Listing/advance_list/advance_drop_note_print', $data);
    }


    public function get_cl_print_partno()
    {
        $request = service('request');
        $mainhead = $request->getPost('mainhead');
        $list_dt = $request->getPost('list_dt');
        $roster_id = $request->getPost('roster_id');
        $board_type = $request->getPost('board_type');


        $partNumbers = $this->Heardt->getPartNumbers($mainhead, $list_dt, $roster_id, $board_type);

        $options = '<option value="0" selected>SELECT</option>';
        if (!empty($partNumbers)) {
            foreach ($partNumbers as $partNumber) {
                $options .= '<option value="' . esc($partNumber['clno']) . '">' . esc($partNumber['clno']) . '</option>';
            }
        } else {
            $options .= '<option value="1" selected>1 (empty)</option>';
        }

        return $this->response->setJSON(['options' => $options]);
    }
}
