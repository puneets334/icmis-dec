<?php

namespace App\Controllers\Judicial;

use App\Controllers\BaseController;
use App\Models\Judicial\ReportModel;
use App\Models\Extension\NoticesModel;
use CodeIgniter\HTTP\Request;

class Report extends BaseController
{
    public $ReportModel;

    function __construct()
    {
        // ini_set('memory_limit','750M'); // This also needs to be increased in some cases. )
        // ini_set('memory_limit', '-1');

        // $this->request = \Config\Services::request();
        // $this->session = session();
        // $this->session->set('dcmis_user_idd', session()->get('login')['usercode']);
    }

    public function verify_detail_report_da_wise()
    {
        $request = \Config\Services::request();
        $ReportModel = new ReportModel();
        $NoticesModel = new NoticesModel();

        // $data['usertype'] = session()->get('login')['usertype'];
        $data['ucode'] = session()->get('login')['usercode'];
        $data['username_uby'] = session()->get('login')['name'];

        $data['listed_date'] = date("m-d-Y", strtotime($request->getPost('verify_dt')));

        // $empid = session()->get('login')['empid'];

        $filter_data = [];

        $filter_data['list_dt'] = date("Y-m-d", strtotime($request->getPost('verify_dt')));
        $filter_data['usercode'] = session()->get('login')['usercode'];
        $filter_data['usertype'] = session()->get('login')['usertype'];

        $all_matters = $ReportModel->getVarifiedMattersByDate($filter_data);
        foreach ($all_matters as $indexKey => $row) {

            $category_sc_old = $ReportModel->getOldSCcategory($row['diary_no']);
            $all_matters[$indexKey]['cat_code'] = implode(",", $category_sc_old);

            $party_names = $ReportModel->getPartyNames($row['diary_no']);
            $all_matters[$indexKey]['radvname'] = $party_names['r_n'];
            $all_matters[$indexKey]['padvname'] = $party_names['p_n'];
            $all_matters[$indexKey]['impldname'] = $party_names['i_n'];

            if (($row['section_name'] == null or $row['section_name'] == '') and $row['ref_agency_state_id'] != '' and $row['ref_agency_state_id'] != 0) {

                if ($row['active_reg_year'] != 0)
                    $ten_reg_yr = $row['active_reg_year'];
                else
                    $ten_reg_yr = date('Y', strtotime($row['diary_no_rec_date']));

                if ($row['active_casetype_id'] != 0)
                    $casetype_displ = $row['active_casetype_id'];
                else if ($row['casetype_id'] != 0)
                    $casetype_displ = $row['casetype_id'];

                $filter_data2 = [];
                $filter_data2['casetype_displ'] = $casetype_displ;
                $filter_data2['ten_reg_yr'] = $ten_reg_yr;
                $filter_data2['ref_agency_state_id'] = $row['ref_agency_state_id'];

                $section_name = $ReportModel->getSectionName($filter_data);
                $all_matters[$indexKey]['section_name'] = $section_name['section_name'];
            }
        }

        $data['all_matters'] = $all_matters;

        // pr($sectionNames);
        // $data['section'] = !empty($sectionNames) ? implode(', ', $sectionNames) : "YOUR SECTION NOT FOUND";

        $cur_ddt = date('Y-m-d');
        $next_court_work_day = date("d-m-Y", strtotime($NoticesModel->chksDate($cur_ddt)));

        $data['next_court_work_day'] = $next_court_work_day;


        return view('Judicial/Reports/verify_detail_report_da_wise', $data);
    }

    public function da_daily_court_remarks_process()
    {
        $request = \Config\Services::request();
        $ReportModel = new ReportModel();
        $usercode = session()->get('login')['usercode'];

        
        $jcourt = 0;
        $tdt1 = date("Y-m-d", strtotime($request->getGet('dtd')));

        if (!empty($request->getGet('aw1'))) {
            $filter_data = [];
            $filter_data['r_status'] = $request->getGet('r_status');
            $filter_data['tdt1'] = $tdt1;
            $filter_data['mf'] = $request->getGet('mf');
            $filter_data['jcd'] = $request->getGet('aw1');

            $results = $ReportModel->getDAHearingsByJudges($filter_data);
        } else {

            $filter_data = [];
            $filter_data['mf'] = $request->getGet('mf');
            $filter_data['tdt1'] = $tdt1;
            $filter_data['crt'] = $request->getGet('courtno');
            
            $results = $ReportModel->getJudgesRoster($filter_data);

            $result = '';
            foreach ($results as $res) {
                if ($result == '')
                    $result .= $res['roster_id'];
                else
                    $result .= "," . $res['roster_id'];
            }

            $filter_data = [];
            $filter_data['result'] = (!empty($result)) ? $result : '0';
            $filter_data['r_status'] = $request->getGet('r_status');
            $filter_data['vstats'] = $request->getGet('vstats');
            $filter_data['mf'] = $request->getGet('mf');
            $filter_data['jcd'] = $request->getGet('aw1');
            $filter_data['tdt1'] = $tdt1;

            $results = $ReportModel->getDAHearings($filter_data);
        }

        // print_r($results);


        // $results[] = array(
        //     'roster_id' => 1,
        //     'judges' => '279',
        //     'tentative_cl_dt' => '2024-10-10',
        //     'board_type' => 'board_type',
        //     'mainhead' => 'mainhead',
        //     'year' => '2024',
        //     'm_year' => '2024',
        //     'f_year' => '2024',
        //     'case_no' => '1',
        //     'clno' => '1',
        //     'ct1' => '10',
        //     'crf1' => '10',
        //     'crl1' => '10',
        //     'ct2' => '10',
        //     'crf2' => '10',
        //     'crl2' => '10',
        //     'cl_dt' => null,
        //     'brd_slno' => '1',
        //     // 'jud1' => '1',
        //     // 'jud2' => '1',
        //     'res_name' => 'res_name',
        //     'pet_name' => 'pet_name',
        //     'stagename' => '1',
        //     'brd_prnt' => '1',
        //     'brd_slno' => '1',
        //     'conn_key' => '0',
        //     'diary_no' => '12024',
        //     'next_dt' => '2024-10-10',
        //     'pet_adv_id' => '1,2,3,4',
        //     'res_adv_id' => '4,3,2,1',
        //     'reg_no_display' => 'reg_no_display',
        //     'c_status' => 1,
        // );

        $data['ucode'] = $usercode;
        
        foreach ($results as $indexKey => $row10) {

            $t_fil_no = "";
            if ($row10['ct1'] != '') {
                $res_ct_typ = $ReportModel->getCaseType($row10['ct1']);
                if ($row10['crf1'] == $row10['crl1'])
                    $t_fil_no .= '' . $res_ct_typ['short_description'] . " " . $row10['crf1'] . '/' . $row10['m_year'];
                else
                    $t_fil_no .= '' . $res_ct_typ['short_description'] . " " . $row10['crf1'] . " - " . $row10['crl1'] . '/' . $row10['m_year'];
            }

            if ($row10['ct2'] != '') {
                $check_for_regular_case = "FOUND";
                $res_ct_typ = $ReportModel->getCaseType($row10['ct2']);
                if(!empty($res_ct_typ)){
                    if ($row10['crf2'] == $row10['crl2'])
                        $t_fil_no .= "</br>" . '' . $res_ct_typ['short_description'] . " " . $row10['crf2'] . '/' . $row10['f_year'];
                    else
                        $t_fil_no .= "</br>" . '' . $res_ct_typ['short_description'] . " " . $row10['crf2'] . " - " . $row10['crl2'] . '/' . $row10['f_year'];
                }
            }

            if (trim($t_fil_no) == '') {
                $row_12 = $ReportModel->getCaseType($row10['casetype_id']);
                if (!empty($row_12)) {
                    $t_fil_no = $row_12['short_description'];
                }
            }

            $results[$indexKey]['t_fil_no'] = $t_fil_no;

            $jcourt = "";
            $bench_from_roster = "";

            $row_rstr = $ReportModel->getDARoaster($row10['roster_id']);

            $casecode = intval(substr($row10["diary_no"], 2, 3));
            $tmp_caseno = $ReportModel->getCaseType($casecode);

            $brdremark = $ReportModel->getReaderRemarks($row10["diary_no"]);
            $brdremark = (!empty($brdremark['remark'])) ? $brdremark['remark'] : '';

            $results_s = $ReportModel->getCaseMultipleRemarks(['diary_no' => $row10["diary_no"], 'cl_date' => $tdt1, 'jcodes' => $row10["judges"]]);

            $head1 = "";
            $txt_value = "";
            if (!empty($results_s)) {
                foreach ($results_s as $row_s) {
                    $t_cl_dt = "";
                    if ($row_s['side'] == "P") {
                        $head1 .= '<b><font color="blue">';
                        $t_cl_dt = date('d-m-Y', @strtotime(@$row10["tentative_cl_dt"]));
                    }
                    if ($row_s['side'] == "D") {
                        $head1 .= '<b><font color="red">';
                        $t_cl_dt = "";
                    }

                    $head1 .= $row_s['head'];
                    if ($row_s['head_content'] != "")
                        $head1 .= ' [' . $row_s['head_content'] . ']';
                    $head1 .= '</font></b><br>';
                    $txt_value .= $row_s['r_head'] . "|" . $row_s['head_content'] . "^^";
                }
            }

            $results[$indexKey]['head1'] = $head1;

            $res_verif = $ReportModel->getCaseVerifyROP(['diary_no' => @$row10["diary_no"], 'cl_dt' => $tdt1]);
            
            $results[$indexKey]['res_verif'] = $res_verif;
            
            $ro_hu = $ReportModel->getListingPurpose(['diary_no' => $row10["diary_no"], 'next_dt' => $tdt1]);

            $t_cl_dt = "";
            // $heardt_updtby = "";
            $heardt_stagename = "";
            $heardt_purpose = "";
            $heardt_mainhead = "";
            

            // if (!empty($ro_hu)) {
            //     $t_cl_dt = date('d-m-Y', strtotime($ro_hu["tentative_cl_dt"]));
            //     $heardt_updtby = $ro_hu['name'] . ' - ' . $ro_hu['empid'];
            //     $heardt_stagename = $ro_hu['stagename'];
            //     $heardt_purpose = '<span style="color:green;">' . $ro_hu['purpose'] . '</span>';
            //     if ($ro_hu['mainhead'] == 'F') {
            //         $heardt_mainhead = '<br><span style="font-weight:bold; color: #1b6d85;">Regular</span>';
            //     }
            //     if ($ro_hu['mainhead'] == 'M') {
            //         $heardt_mainhead = '<br><span style="font-weight:bold; color: #1b6d85;">Misc.</span>';
            //     }
            //     if ($ro_hu['board_type'] == 'J') {
            //         $heardt_board_type = ', <span style="font-weight:bold; color: brown;">Court</span>';
            //     }
            //     if ($ro_hu['board_type'] == 'C') {
            //         $heardt_board_type = ', <span style="font-weight:bold; color: brown;">Chamber</span>';
            //     }
            //     if ($ro_hu['board_type'] == 'R') {
            //         $heardt_board_type = ', <span style="font-weight:bold; color: brown;">Registrar</span>';
            //     }
            // }

            $rs_lct = $ReportModel->getLowerCourtCaseType($row10["diary_no"]);

            $row10["tdt1"] = $tdt1;
            $result_drop = $ReportModel->getDADropNote($row10);
            
            // pr($drop_note);

            $results[$indexKey]['t_cl_dt'] = $t_cl_dt;
            $results[$indexKey]['heardt_purpose'] = $heardt_purpose;
            $results[$indexKey]['result_drop'] = $result_drop;
            $results[$indexKey]['heardt_stagename'] = $heardt_stagename;
            $results[$indexKey]['heardt_mainhead'] = $heardt_mainhead;
            $results[$indexKey]['rs_lct'] = $rs_lct;
            // pr($row_rstr);

            if (!empty($row_rstr)) {
                $jcourt = $row_rstr["courtno"];
                $bench_from_roster = " (" . $row_rstr["bnch"] . ")";
                if ($row_rstr["session"] != "Whole Day")
                    $bench_from_roster .= " (" . $row_rstr["session"] . ")";
                if (trim($row_rstr["frm_time"]) != "")
                    $bench_from_roster .= " (From " . $row_rstr["frm_time"] . ") ";
            }
            
            $results[$indexKey]['stagename'] = $row10["stagename"] ?? "";
            
            $result1_s = $ReportModel->getSubheading($row10["subhead"]);            
            $results[$indexKey]['brdremark'] = $brdremark;
            $results[$indexKey]['result1_s'] = $result1_s;
            $results[$indexKey]['tmp_caseno'] = $tmp_caseno;
            $results[$indexKey]['jcourt'] = $jcourt;
            $results[$indexKey]['bench_from_roster'] = $bench_from_roster;
            
        }

        // print_r($results);

        $data['results10'] = $results;

        $data['res_rem'] = $ReportModel->getShowLCDMsg(['court' => $jcourt, 'cl_dt' => $tdt1]);
        $data['res_rem'] = $ReportModel->getCaseVerifyBySecRemark();

        return view('Judicial/Reports/da_daily_court_remarks_process', $data);
    }

    public function rop_daily_court_remarks_process()
    {
        $request = \Config\Services::request();
        $ReportModel = new ReportModel();
        $usercode = session()->get('login')['usercode'];

        $jcourt = 0;
        $tdt1 = date("Y-m-d", strtotime($request->getGet('dtd')));
        
        if (!empty($request->getGet('aw1'))) {
            $filter_data = [];
            $filter_data['r_status'] = $request->getGet('r_status');
            $filter_data['tdt1'] = $tdt1;
            $filter_data['mf'] = $request->getGet('mf');
            $filter_data['jcd'] = $request->getGet('aw1');

            $results = $ReportModel->getROPHearingsByJudges($filter_data);
        } else {            
            $filter_data = [];
            $filter_data['mf'] = $request->getGet('mf');
            $filter_data['tdt1'] = $tdt1;
            $filter_data['crt'] = $request->getGet('courtno');

            $result = '';
            $results = $ReportModel->getJudgesRoster($filter_data);
            //print_r($results);die;
            
            foreach ($results as $res) {
                if ($result == '')
                    $result .= $res['roster_id'];
                else
                    $result .= "," . $res['roster_id'];
            }
            
            $filter_data = [];
            $filter_data['result'] = (!empty($result)) ? $result : '0';
            $filter_data['r_status'] = $request->getGet('r_status');
            $filter_data['vstats'] = $request->getGet('vstats');
            $filter_data['mf'] = $request->getGet('mf');
            $filter_data['jcd'] = $request->getGet('aw1');
            $filter_data['tdt1'] = $tdt1;
            
            $results = $ReportModel->getROPHearings($filter_data);
        }

         //print_r($results);die;


        // $results[] = array(
        //     'roster_id' => 1,
        //     'judges' => 'J1',
        //     'tentative_cl_dt' => '2024-10-10',
        //     'board_type' => 'board_type',
        //     'mainhead' => 'mainhead',
        //     'year' => '2024',
        //     'case_no' => '1',
        //     'clno' => '1',
        //     'cl_dt' => null,
        //     'brd_slno' => '1',
        //     'jud1' => '1',
        //     'res_name' => 'res_name',
        //     'pet_name' => 'pet_name',
        //     'stagename' => '1',
        //     'brd_prnt' => '1',
        //     'brd_slno' => '1',
        //     'conn_key' => '0',
        //     'diary_no' => '12024',
        //     'pet_adv_id' => '1,2,3,4',
        //     'res_adv_id' => '4,3,2,1',
        //     'reg_no_display' => 'reg_no_display',
        //     'c_status' => 1,
        // );

        $data['ucode'] = $usercode;
        
        foreach ($results as $indexKey => $row10) {

            $jcourt = "";
            $bench_from_roster = "";

            $row_rstr = $ReportModel->getRoaster($row10['roster_id']);

            $casecode = intval(substr($row10["diary_no"], 2, 3));
            $tmp_caseno = $ReportModel->getCaseType($casecode);

            $rop_view = "";
            if ($row10['conn_key'] > 0) {
                $main_case_no = $row10['conn_key'];
            } else {
                $main_case_no = $row10['diary_no'];
            }

            $resus = $ReportModel->getOrderDetails(['diary_no' => $main_case_no, 'orderdate' => $tdt1]);

            if (!empty($resus)) {
                $rop_view = "ROP : <span style='color:blue;'>";
                foreach ($resus as $ro_rop) {
                    $rjm = explode("/", $ro_rop['pdfname']);
                    if ($rjm[0] == 'supremecourt') {
                        $rop_view .= '<a href="../jud_ord_html_pdf/' . $ro_rop['pdfname'] . '" target="_blank">View</a>';
                    } else {
                        $rop_view .= '<a href="../judgment/' . $ro_rop['pdfname'] . '" target="_blank">View</a>';
                    }
                }
                $rop_view .= "</span>";
            } else {
                $rop_view = "ROP : <span style='color:red;'>Not Available</span>";
            }


            $brdremark = $ReportModel->getReaderRemarks($row10["diary_no"]);
            $brdremark = (!empty($brdremark['remark'])) ? $brdremark['remark'] : '';

            $results_s = $ReportModel->getCaseMultipleRemarks(['diary_no' => $row10["diary_no"], 'cl_date' => $tdt1, 'jcodes' => $row10["judges"]]);

            $head1 = "";
            $txt_value = "";
            if (!empty($results_s)) {
                foreach ($results_s as $row_s) {
                    $t_cl_dt = "";
                    if ($row_s['side'] == "P") {
                        $head1 .= '<b><font color="blue">';
                        $t_cl_dt = date('d-m-Y', strtotime($row10["tentative_cl_dt"]));
                    }
                    if ($row_s['side'] == "D") {
                        $head1 .= '<b><font color="red">';
                        $t_cl_dt = "";
                    }

                    $head1 .= $row_s['head'];
                    if ($row_s['head_content'] != "")
                        $head1 .= ' [' . $row_s['head_content'] . ']';
                    $head1 .= '</font></b><br>';
                    $txt_value .= $row_s['r_head'] . "|" . $row_s['head_content'] . "^^";
                }
            }

            $results[$indexKey]['head1'] = $head1;

            $res_verif = $ReportModel->getCaseVerifyROP(['diary_no' => $row10["diary_no"], 'cl_dt' => $tdt1]);
            $results[$indexKey]['res_verif'] = $res_verif;

            $ro_hu = $ReportModel->getListingPurpose(['diary_no' => $row10["diary_no"], 'next_dt' => $tdt1]);

            $t_cl_dt = "";
            $heardt_updtby = "";
            $heardt_stagename = "";
            $heardt_purpose = "";
            $heardt_mainhead = "";
            $heardt_board_type = "";

            if (!empty($ro_hu)) {
                $t_cl_dt = date('d-m-Y', strtotime($ro_hu["tentative_cl_dt"]));
                $heardt_updtby = $ro_hu['name'] . ' - ' . $ro_hu['empid'];
                $heardt_stagename = $ro_hu['stagename'];
                $heardt_purpose = '<span style="color:green;">' . $ro_hu['purpose'] . '</span>';
                if ($ro_hu['mainhead'] == 'F') {
                    $heardt_mainhead = '<br><span style="font-weight:bold; color: #1b6d85;">Regular</span>';
                }
                if ($ro_hu['mainhead'] == 'M') {
                    $heardt_mainhead = '<br><span style="font-weight:bold; color: #1b6d85;">Misc.</span>';
                }
                if ($ro_hu['board_type'] == 'J') {
                    $heardt_board_type = ', <span style="font-weight:bold; color: brown;">Court</span>';
                }
                if ($ro_hu['board_type'] == 'C') {
                    $heardt_board_type = ', <span style="font-weight:bold; color: brown;">Chamber</span>';
                }
                if ($ro_hu['board_type'] == 'R') {
                    $heardt_board_type = ', <span style="font-weight:bold; color: brown;">Registrar</span>';
                }
            }

            $results[$indexKey]['t_cl_dt'] = $t_cl_dt;
            $results[$indexKey]['heardt_purpose'] = $heardt_purpose;
            $results[$indexKey]['heardt_updtby'] = $heardt_updtby;
            $results[$indexKey]['heardt_stagename'] = $heardt_stagename;
            $results[$indexKey]['heardt_mainhead'] = $heardt_mainhead;
            $results[$indexKey]['heardt_board_type'] = $heardt_board_type;
            // pr($row_rstr);

            if (!empty($row_rstr)) {
                $jcourt = $row_rstr["courtno"];
                $bench_from_roster = " (" . $row_rstr["bnch"] . ")";
                if ($row_rstr["session"] != "Whole Day")
                    $bench_from_roster .= " (" . $row_rstr["session"] . ")";
                if (trim($row_rstr["frm_time"]) != "")
                    $bench_from_roster .= " (From " . $row_rstr["frm_time"] . ") ";
            }

            $results[$indexKey]['brdremark'] = $brdremark;
            $results[$indexKey]['rop_view'] = $rop_view;
            $results[$indexKey]['tmp_caseno'] = $tmp_caseno;
            $results[$indexKey]['jcourt'] = $jcourt;
            $results[$indexKey]['bench_from_roster'] = $bench_from_roster;

            $t_stagename = "";
            if($filter_data['mf'] == "F") {
                $t_stagename = $ReportModel->getGroupOrStageNameByStageCode($row10["subhead"]);                
            } elseif(!empty($row10["stagename"])) {
                $t_stagename = $row10["stagename"];
            }

            $results[$indexKey]['t_stagename'] = $t_stagename;
        }

        //echo "<pre>";print_r($results);die;

        $data['results10'] = $results;

        $data['res_rem'] = $ReportModel->getShowLCDMsg(['court' => $jcourt, 'cl_dt' => $tdt1]);
        $data['res_rem'] = $ReportModel->getCaseVerifyBySecRemark();

        return view('Judicial/Reports/rop_daily_court_remarks_process', $data);
    }

    public function response_verify_rop()
    {
        $request = \Config\Services::request();
        $ReportModel = new ReportModel();

        $ucode = session()->get('login')['usercode'];;
        $str_explo = explode("_", $request->getPost('dno'));
        $dno = $str_explo[0];
        $board_type = $str_explo[1];
        $mainhead = $str_explo[2];
        $next_dt = $str_explo[3];
        $court = $str_explo[4];
        $t_dt = $str_explo[5];

        // Prepare data for insertion
        $data = [
            'diary_no'      => $dno,
            'cl_dt'         => $next_dt,
            'm_f'           => $mainhead,
            'board_type'    => $board_type,
            'ent_dt'        => date('Y-m-d H:i:s'), // Use current timestamp
            'ucode'         => $ucode,
            'remark_id'     => $request->getPost('rremark'),
            'tentative_dt'  => $t_dt,
            'court'         => $court,
        ];

        try {
            $resss = $ReportModel->addCaseVerifyROP($data);
        } catch (\Exception $e) {
            return json_encode(['success' => 0, 'message' => $e->getMessage()]);
        }

        if ($resss > 0) {
            $rowuser = session()->get('login');
            $username_empid = $rowuser['name'] . " [" . $rowuser['empid'] . "] ";

            $rowsel = $ReportModel->getCaseDetails($dno);

            $rremark_array = explode(",", $_POST['rremark']);
            $array = array_diff($rremark_array, ["1"]);
            $arrays = $array;
            $array = array();
            $i = 0;
            foreach ($arrays as $k => $item) {
                $array[$i] = $item;
                unset($arrays[$k]);
                $i++;
            }
            $remarks_join = "";
            if (count($array) > 0) {
                for ($k = 0; $k <= count($array); $k++) {

                    $row_ar = $ReportModel->getCaseVerifyBySecRemarkById($array[$k]);

                    $remarks_join .= $row_ar['remarks'] . ",";
                }
                $remarks_join = rtrim($remarks_join, ",");
                if (!empty($rowsel)) {
                    $msg = " AS PER COURT REMARKS FOR DATED " . date("d-m-Y", strtotime($next_dt)) . " IN ";
                    if ($rowsel['reg_no_display']) {
                        $msg .= " CASE NO. " . $rowsel['reg_no_display'];
                    }
                    $msg .= " DIARY NO. " . substr_replace($rowsel['diary_no'], '-', -4, 0);
                    $msg .= " FOLLOWING DEFECTS RAISED BY MONITORING TEAM " . $username_empid . " : " . $remarks_join;

                    // Prepare data for insertion
                    $data = [
                        'to_user'  => $rowsel['empid'],
                        'from_user' => $rowuser['empid'],
                        'msg'      => $msg,
                    ];

                    $resss = $ReportModel->addMSG($data);
                }
            }
        }
    }

    public function rop_daily_court_remarks()
    {
        $request = \Config\Services::request();
        $ReportModel = new ReportModel();
        $usercode = session()->get('login')['usercode'];

        $user_info = $ReportModel->getDisplayUser($usercode);
        $data['userid'] = $user_info['empid'];

        $all_judges = $ReportModel->getJudges(['jcode' => $user_info['jcode']]);

        $data['all_judges'] = $all_judges;
        $data['aw1'] = $request->getPost('aw1');

        // echo c_list($paps);
        if ($request->getPost("dtd") != "")
            $data['dtd'] = $request->getPost("dtd");
        else
            $data['dtd'] = date("d-m-Y");

        if ($request->getPost("hdate") != "")
            $data['hdate'] = $request->getPost("hdate");
        else
            $data['hdate'] = $data['dtd'];

        if ($request->getPost("mf") != "")
            $data['mf'] = $request->getPost("mf");
        else
            $data['mf'] = 1;

        $data['sql11'] = $ReportModel->getCaseRemarks();
        $data['sql12'] = $ReportModel->getCaseRemarks2();

        return view('Judicial/Reports/rop_daily_court_remarks', $data);
    }

    public function da_daily_court_remarks()
    {
        $request = \Config\Services::request();
        $ReportModel = new ReportModel();
        $usercode = session()->get('login')['usercode'];

        $user_info = $ReportModel->getDisplayUser($usercode);
        $data['userid'] = $user_info['empid'];

        $all_judges = $ReportModel->getJudges(['jcode' => $user_info['jcode']]);

        $data['all_judges'] = $all_judges;
        $data['aw1'] = $request->getPost('aw1');

        // echo c_list($paps);
        if ($request->getPost("dtd") != "")
            $data['dtd'] = $request->getPost("dtd");
        else
            $data['dtd'] = date("d-m-Y");

        if ($request->getPost("hdate") != "")
            $data['hdate'] = $request->getPost("hdate");
        else
            $data['hdate'] = $data['dtd'];

        if ($request->getPost("mf") != "")
            $data['mf'] = $request->getPost("mf");
        else
            $data['mf'] = 1;

        $data['sql11'] = $ReportModel->getCaseRemarks();
        $data['sql12'] = $ReportModel->getCaseRemarks2();

        return view('Judicial/Reports/da_daily_court_remarks', $data);
    }

    public function verify_index_da_wise()
    {
        $NoticesModel = new NoticesModel();

        $cur_ddt = date('Y-m-d');
        $next_court_work_day = date("d-m-Y", strtotime($NoticesModel->chksDate($cur_ddt)));
        $data['next_court_work_day'] = $next_court_work_day;

        return view('Judicial/Reports/verify_index_da_wise', $data);
    }

    public function workdone()
    {
        $ReportModel = new ReportModel();

        $data['usertype'] = session()->get('login')['usertype'];

        $empid = session()->get('login')['empid'];

        $sectionNames = $ReportModel->getEmployeeSections($empid);

        $data['section'] = !empty($sectionNames) ? implode(', ', $sectionNames) : "YOUR SECTION NOT FOUND";
        
        return view('Judicial/Reports/workdone', $data);
    }

    public function get_workdone()
    {
        $request = \Config\Services::request();
        $ReportModel = new ReportModel();

        $empid = session()->get('login')['empid'];
        $dcmis_usertype = session()->get('login')['usertype'];

        $filter_data = [];

        $filter_data['section'] = [];

        if ($dcmis_usertype != 1) {
            $filter_data['section'] = $ReportModel->getEmployeeSections($empid, 'usec');
        }

        $filter_data['ddl_all_blank'] = $request->getPost('ddl_all_blank');
        $filter_data['filter_date'] = date("Y-m-d", strtotime($request->getPost('date')));

        $data['results'] = $ReportModel->getCasesWithoutDA($filter_data);

        // pr($data);

        return view('Judicial/Reports/get_workdone', $data);
    }

    public function get_workdone_withget()
    {
        
        $request = \Config\Services::request();
        $ReportModel = new ReportModel();

        //print_r($request->getGET());die;
        $empid = session()->get('login')['empid'];
        $dcmis_usertype = session()->get('login')['usertype'];

        $filter_data = [];

        $filter_data['section'] = [];

        if ($dcmis_usertype != 1) {
            $filter_data['section'] = $ReportModel->getEmployeeSections($empid, 'usec');
        }

        $filter_data['ddl_all_blank'] = $request->getGET('ddl_all_blank');
        $filter_data['filter_date'] = date("Y-m-d", strtotime($request->getGET('date')));

        $data['results'] = $ReportModel->getCasesWithoutDA($filter_data);
        //echo "<pre>";print_r($data['results']);die;
        // pr($data);

        return view('Judicial/Reports/get_workdone', $data);
    }

    public function aor_wise_matters()
    {
        $ReportModel = new ReportModel();
        $request = \Config\Services::request();

        $data = [];

        $data['aor_code'] = '';

        if ($request->getMethod() == 'post') {
            $aorcode = $request->getPost('aorcode');

            $data['aor_code'] = $aorcode;

            // $dacode_query = "SELECT name FROM users WHERE usercode = '$row[dacode]' AND display = 'Y'";
            // $dacode_result = mysql_query($dacode_query) or die("Error: " . __LINE__ . mysql_error());
            // echo mysql_result($dacode_result, 0);

            $bar_info = $ReportModel->getBarInfoByAOR($aorcode);

            if (!empty($bar_info)) {

                $matter_results = $ReportModel->getAORWiseMatters($bar_info['bar_id']);

                if (!empty($matter_results)) {
                    foreach ($matter_results as $indexKey => $row) {
                        $matter_results[$indexKey]['user_name'] = $ReportModel->getDisplayUserName($row['dacode']);
                    }

                    $data['aor_name'] = $bar_info['name'];
                    $data['matter_results'] = $matter_results;
                } else {
                    session()->setFlashdata('warning', 'No Matter Found against this AOR : ' . $aorcode);
                }
            } else {
                session()->setFlashdata('warning', 'No Bar Detail Found against this AOR : ' . $aorcode);
            }
        }

        return view('Judicial/Reports/aor_wise_matters', $data);
    }

    public function loosedoc()
    {        
        $ReportModel = new ReportModel();
        $post_data = json_decode(file_get_contents("php://input"), true);
        $post_data = $post_data['data'] ?? "";
            // print_r(session()->get('login')['usercode'] );
            // die();
        if (isset($post_data['from_date']) && isset($post_data['to_date'])) 
        {
            //echo " the session variable is ".$_SESSION['dcmis_user_idd'] ;
            $fromDate = date('Y-m-d', strtotime($post_data['from_date']));
            $toDate = date('Y-m-d', strtotime($post_data['to_date']));
            //  $status_flag = ($post_data['status']==2)?0:1;
            //  $app_type = $post_data['app_type'];
            //  $action_type=$post_data['action_type'];
            // echo "seesion value tris=".$_SESSION['dcmis_user_idd'];          
            $data['transactions'] = $ReportModel->get_loosedocuments($fromDate, $toDate, session()->get('login')['usercode']);
            // echo "sdfdsf".($data['transactions']);

            //$data['docmaster'] = $this->efiling_model->docmaster();
            echo json_encode($data);
        } else {
            return view('Judicial/Reports/loosedocuments');
        }
    }

    public function loosedoc_getscope(){
        $ReportModel = new ReportModel();
        $post_data = json_decode(file_get_contents("php://input"), true);        
        $post_get = @json_decode($this->request->getGet('data'),true);
        $fromDate='';$fromDate='';
        $fromDate = date('Y-m-d', strtotime(@$post_get['from_date']));
        $toDate = date('Y-m-d', strtotime(@$post_get['to_date']));
        $data['transactions'] = $ReportModel->get_loosedocuments($fromDate, $toDate, session()->get('login')['usercode']);
        echo json_encode($data);die;
    }

    public function loose_document_da()
    {
        $ReportModel = new ReportModel();
        $request = \Config\Services::request();

        $user = session()->get('login')['usercode'];

        // pr($request->getPost());

        if (isset($_POST['from_date']) && isset($_POST['to_date'])) {
            $first_date = date('Y-m-d', strtotime($request->getPost('from_date')));
            $to_date = date('Y-m-d', strtotime($request->getPost('to_date')));
        } else {
            $first_date = date('Y-m-d');
            $to_date = date('Y-m-d');
        }

        // echo $first_date,'|', $to_date,'|', $user;

        $data['user'] = $user;
        $data['first_date'] = $first_date;
        $data['to_date'] = $to_date;

        $result_array = $ReportModel->loose_document_da_detail($first_date, $to_date, $user);
        $result_array_da = $ReportModel->da_details($user);

        $data['loose_document_da_result'] = $result_array;
        $data['loose_document_da_detail'] = $result_array_da;

        return view('Judicial/Reports/loose_doc_da_detail', $data);
    }

    public function da_rog()
    {
        $ReportModel = new ReportModel();

        $data['app_name'] = 'DA Report';
        $result_array = $ReportModel->da_rog_report();
        $data['da_rog_result'] = $result_array;

        return view('Judicial/Reports/da_rog_report', $data);
    }

    public function cases()
    {
        $ReportModel = new ReportModel();
        $request = \Config\Services::request();

        $category = $request->getGet('category');
        $dacode = $request->getGet('dacode');
       
        $da_rog_matters = $ReportModel->da_rog_cases($category, $dacode);
        $da_details = $ReportModel->da_details($dacode);

        $data['heading'] = '';
        $data['da_details'] = $da_details;
        $data['dacode'] = $dacode;
        $data['da_cases'] = $da_rog_matters;
        $data['category'] = $category;

        return view('Judicial/Reports/da_rog_cases', $data);
    }

    public function da_wise_report()
    {
        $emp_id = session()->get('login')['empid'];

        $ReportModel = new ReportModel();

        $data['app_name'] = 'DA Wise Report';
        $result_array = $ReportModel->show_da_wise_report($emp_id);
        $data['da_result'] = $result_array;

        return view('Judicial/Reports/da_wise_report', $data);
    }

    function action_pending_report_da()
    {
        // $loginUser = session()->get('login');
        // pr($loginUser);
        $emp_id = session()->get('login')['empid'];

        $MasterModel = new \App\Models\Common\MasterModel();

        $data['sections'] = $MasterModel->getSectionList();
        $data['order_type'] = $MasterModel->getOrderType();
        $data['empid'] = $emp_id;
        $data['desig'] = $MasterModel->getDesignation($emp_id);
        return view('Judicial/Reports/action_pending_da', $data);
    }

    function getActionPendingReportDA()
    {
        
        $MasterModel = new \App\Models\Common\MasterModel();        
        $data = json_decode(file_get_contents("php://input"), true);

        $data['from_date']=''; $data['to_date']=''; $data['deliver_mode']=''; $data['order_type']='';
        $data['empid'] = @$this->request->getGet('empid');
        $data['from_date'] = @$this->request->getGet('from_date');
        $data['to_date'] = @$this->request->getGet('to_date');
        $data['deliver_mode'] = @$this->request->getGet('deliver_mode');
        $data['order_type'] = @$this->request->getGet('order_type');        
        $reportData = $MasterModel->getActionPendingReportDA($data['empid'], $data['from_date'], $data['to_date'], $data['deliver_mode'], $data['order_type']);        
        $this->response->setContentType('Content-Type: application/json');        
        echo json_encode($reportData);
    }

    public function getORuploded_status()
    {
        $ReportModel = new ReportModel();
        $request = \Config\Services::request();

        $data['case_result'] = [];
        $data['app_name'] = 'ORUploadStatus.';
        $data['usercode'] = session()->get('login')['usercode'];

        if ($request->getMethod() == 'post') {
            $usercode = $request->getPost('usercode');

            $on_date = date('Y-m-d', strtotime($request->getPost('on_date')));
            $result_array = $ReportModel->get_orUplodStatus($on_date, $usercode);

            if (! $result_array) {
                session()->setFlashdata('msg', '<div class="alert alert-warning text-center">No Data Found for selected date : ' . $request->getPost('on_date') . ' </div>');
            }

            $data['case_result'] = $result_array;
        }

        return view('Judicial/Reports/orUploadStatus', $data);
    }
}
