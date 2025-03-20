<?php

namespace App\Controllers\Listing;
use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Filing\AdvocateModel;
use App\Models\Listing\CaseInfoModel;
use App\Models\Listing\DropRequestModel;
use App\Models\Common\Dropdown_list_model;
use App\Models\Listing\Heardt;
class CaseDrop extends BaseController
{

    public $model;
    public $diary_no;
    public $DropRequestModel;
    public $CaseInfoModel;
    public $Dropdown_list_model;

    function __construct()
    {
         $this->CaseInfoModel = new CaseInfoModel();
         $this->DropRequestModel = new DropRequestModel();
         $this->Dropdown_list_model= new Dropdown_list_model();
        /*if (empty(session()->get('filing_details')['diary_no'])) {
             $uri = current_url(true);
             $getUrl = $uri->getSegment(3).'-'.$uri->getSegment(4);
             header('Location:'.base_url('Filing/Diary/search?page_url='.base64_encode($getUrl)));exit();
            exit();
        } else {
            $this->diary_no = session()->get('filing_details')['diary_no'];
        }*/
    }

    /**
     * To display drop request add menu page
     *
     * @return void
     */
    public function index()
    {
        if (empty(session()->get('filing_details')['diary_no'])) {
            $uri = current_url(true);
            $getUrl = $uri->getSegment(0).'-'.$uri->getSegment(1);
            header('Location:'.base_url('Filing/Diary/search?page_url='.base64_encode($getUrl)));exit();
           exit();
        } else {
            $this->diary_no = session()->get('filing_details')['diary_no'];
        }

        $filing_details = session()->get('filing_details');
        $data['diary_number'] = $filing_details['diary_no'];
        $data['diary_year'] = $filing_details['diary_year'];

        //$filing_details['diary_no'] = 292024;
        $data['from_heardt'] = $this->DropRequestModel->get_case_details_by_diary_no($filing_details['diary_no']);
        //pr($data['from_heardt']);
        if(!empty($data['from_heardt'])) {
            $q_next_dt = isset($data['from_heardt']['next_dt']) ? $data['from_heardt']['next_dt'] : '';
            $partno = isset($data['from_heardt']['clno']) ? $data['from_heardt']['clno'] : '';
            $mainhead = isset($data['from_heardt']['mainhead']) ? $data['from_heardt']['mainhead'] : '';
            $roster_id = isset($data['from_heardt']['roster_id']) ? $data['from_heardt']['roster_id'] : '';
            $brd_slno = isset($data['from_heardt']['brd_slno']) ? $data['from_heardt']['brd_slno'] : '';
            $data['chk_drop_note'] =  $data['drop_reasons'] = '';
            $cl_result = $this->DropRequestModel->f_cl_is_printed($q_next_dt, $partno, $mainhead, $roster_id);
            if($cl_result) {
                $data['chk_drop_note'] = $this->DropRequestModel->get_drop_note($data['diary_number'], $brd_slno, $roster_id, $mainhead, $q_next_dt);
            } else {
                $data['drop_reasons'] = $this->DropRequestModel->drop_reason();
            }
            $data['cl_result'] = $cl_result;
        }
        return  view('Listing/drop_note/field_case_drop', $data);
    }

    public function drop_note_now()
    {
        if ($this->request->getMethod() === 'post') {
            $next_dt = $this->request->getPost('next_dt');
            $brd_slno = $this->request->getPost('brd_slno');
            $dno = $this->request->getPost('dno');
            $roster_id = $this->request->getPost('roster_id');
            $drop_rmk = $this->request->getPost('drop_rmk');
            $mainhead = $this->request->getPost('mainhead');
            $ldates = $this->request->getPost('ldates');
            $ready_not = $this->request->getPost('ready_not');
            $partno = $this->request->getPost('partno');
            $is_printed = $this->request->getPost('is_printed');
            $drop_reason_select = $this->request->getPost('drop_reason_select');

            $ucode =  $_SESSION['login']['usercode'];
            $data['session_id_url'] = session()->get('dcmis_user_idd');
            if($is_printed == 'B' && $drop_reason_select == 0){
                echo '<table align="center" style="background-color: white; color:red;"><tr><th>Please Select Reason...</th></tr></table>';
            }

            if(strlen(trim($drop_rmk)) < 8 && ($is_printed == 'Y' || ($is_printed == 'B' && $ready_not == 6))){
                echo '<table align="center" style="background-color: white; color:red;"><tr><th>Please Entre Drop Reason with minimum 8 characters...</th></tr></table>';
            }

            $res_drp_note = $this->DropRequestModel->drop_note_ins($ucode,$next_dt,$brd_slno,$dno,$roster_id,$drop_rmk,$mainhead,$partno,$is_printed,$drop_reason_select,$ready_not);
            $ldates = date("Y-m-d", strtotime($ldates) );
            if($res_drp_note == 1){
                echo "Drop Note Created Successfully<br/>";
               $result = $this->DropRequestModel->f_cl_drop_case($dno, $ucode, $ldates, $ready_not);
                if($result == 1){
                    echo "Case Droped Successfully";
                } else {
                    echo "Error:Unable to Drop";
                }
            } else{
                echo "Unable to Make Drop Note";
            }
        }
    }

    public function note()
    {
        $data = [];
        $heardtModel = new Heardt();
        $data['listing_dates'] = $heardtModel->getListingDates();
        $data['judge_list'] = $this->DropRequestModel->field_sel_ros_jgs();
        $data['listing_dates'] = $this->DropRequestModel->field_sel_roster_dts();
        return  view('Listing/drop_note/note', $data);
    }

    public function get_cl_print_mainhead()
    {
        $mainhead = $this->request->getPost('mainhead');
        $board_type = $this->request->getPost('board_type');
        $data['listing_dates'] = $this->DropRequestModel->get_cl_print_mainhead($mainhead, $board_type);
        return view('Listing/drop_note/get_cl_print_mainhead', $data);
    }

    public function get_cl_print_benches()
    {
        $list_dt = $this->request->getPost('list_dt');
        //$list_dt = !empty($list_dt) ? $list_dt : null;
        $list_dt = (empty($list_dt) || $list_dt == '-1') ? null : $list_dt;
        $mainhead = $this->request->getPost('mainhead');
        $board_type = $this->request->getPost('board_type');
        $data['judge_list'] = $this->DropRequestModel->get_cl_print_benches($list_dt, $mainhead, $board_type);
        return view('Listing/drop_note/get_cl_print_benches', $data);
    }

    public function get_cl_print_partno()
    {
        $mainhead = $this->request->getPost('mainhead');
        $list_dt = $this->request->getPost('list_dt');
        $list_dt = !empty($list_dt) ? $list_dt : null;
        $jud_ros = explode("|", $this->request->getPost('jud_ros'));
        $roster_id = isset($jud_ros[1]) ? $jud_ros[1] : 0;
        $board_type = $this->request->getPost('board_type');
        $data['part_numbers'] = $this->DropRequestModel->get_cl_print_partno($mainhead, $list_dt, $roster_id, $board_type);
        return view('Listing/drop_note/get_cl_print_partno', $data);
    }

    public function note_field()
    {
        $list_dt = $data['list_dt'] = $this->request->getPost('list_dt');
        $list_dt = !empty($list_dt) ? $list_dt : null;
        $mainhead = $data['mainhead'] = $this->request->getPost('mainhead');
        $part_no = $data['part_no'] = $this->request->getPost('part_no');
        $jud_ros = explode("|", $this->request->getPost('jud_ros'));
        $roster_id = isset($jud_ros[1]) ? $jud_ros[1] : 0;
        $data['note_field'] = $this->DropRequestModel->note_field($roster_id);
        $data['h_notes'] = $this->DropRequestModel->get_header_footer_print($list_dt, $mainhead, $roster_id, $part_no, 'H');
        $data['f_notes'] = $this->DropRequestModel->get_header_footer_print($list_dt, $mainhead, $roster_id, $part_no, 'F');
        $drop_notes = $this->DropRequestModel->get_drop_note_print($list_dt, $mainhead, $roster_id);
        $advocate =[];
        $shifted_to = $courtno =  '';
        //pr($drop_notes);
        foreach($drop_notes as $key => $note){
            $advocate = $this->DropRequestModel->get_advocate($note['diary_no']);
            $radvname=  isset($advocate["r_n"]) ? $advocate["r_n"] : '';
            $padvname=  isset($advocate["p_n"]) ? $advocate["p_n"] : '';
            $drop_notes[$key]['advocate'] = "";
            $drop_notes[$key]['advocate'] =  strtoupper(str_replace(",",", ",trim($padvname,",")))."<br/><br/>".strtoupper(str_replace(",",", ", trim($radvname,",")));
            // $drop_notes[$key]['shifted_to'] = $this->DropRequestModel->get_courtno($note['p_r_id']);
            // $courtno = $this->DropRequestModel->get_courtno($note['p_r_id']);

            $drop_notes[$key]['shifted_to'] = "";
            if($note['p_r_id'] == 0){
                $drop_notes[$key]['shifted_to'] = "-";
            } else {
                if($note['p_r_id'] == $note['roster_id']){
                    $drop_notes[$key]['shifted_to'] = "Item No. ".$note['p_brd_slno'];
                }else{
                    $courtno = $this->DropRequestModel->get_courtno($note['p_r_id']);
                    if($note['c_status'] == 'D'){
                        $dispose_flag = " Disposed";
                    }
                    else{
                        $dispose_flag = " ";
                    }
                    $drop_notes[$key]['shifted_to'] = "Court No. ". $courtno." as Item No. ".$note['p_brd_slno']." ".$dispose_flag." On ".date('d-m-Y', strtotime($note['p_next_dt']));
                }
            }
        }
        //$diary_no = 42024;
        //$advocate = $this->DropRequestModel->get_advocate($diary_no);
        //pr($advocate);
        $data['drop_notes'] = $drop_notes;
        return view('Listing/drop_note/note_field', $data);
    }

}
