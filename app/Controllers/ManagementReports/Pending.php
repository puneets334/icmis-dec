<?php

namespace App\Controllers\ManagementReports;

use App\Controllers\BaseController;

use CodeIgniter\Controller;
use CodeIgniter\Model;
use App\Models\ManagementReport\PendingModel;

class Pending extends BaseController
{
    protected $PendingModel;

    public function __construct()
    {
        $this->PendingModel = new PendingModel();
    }
    public function casetype_wise()
    {
        $data['result_array'] = $this->PendingModel->casetype_wise();

        return view('ManagementReport/Pending/casetype_wise', $data);
    }
    public function judge_date_wise_tobe_list()
    {
        $data['rasult_array'] = $this->PendingModel->judge_date_wise_tobe_list();
        return view('ManagementReport/Pending/judge_date_wise_tobe_list', $data);
    }
    public function judge_date_wise_tobe_list_get()
    {
        $jcd = $this->request->getPost('jcd');
        $board_type = $this->request->getPost('board_type');
        $data['result_array'] = $this->PendingModel->judge_date_wise_tobe_list_get($jcd);
        $data['result_array2'] = $this->PendingModel->judge_date_wise_tobe_list_table_get($jcd);
        return view('ManagementReport/Pending/judge_date_wise_tobe_list_get', $data);
    }
    public function coram_wise_cat_tobe_list()
    {
        return view('ManagementReport/Pending/coram_wise_cat_tobe_list');
    }
    public function coram_wise_cat_tobe_list_get()
    {
        $data['list_dt'] = date('Y-m-d', strtotime($this->request->getPost('list_dt')));
        $data['board_type'] = $this->request->getPost('board_type');
        $data['result_array'] = $this->PendingModel->coram_wise_cat_tobe_list_get();
        return view('ManagementReport/Pending/coram_wise_cat_tobe_list_get', $data);
    }
    public function sc_disposed_cav_verfication()
    {
        return view('ManagementReport/Pending/sc_disposed_cav_verfication');
    }
    /**
     * To display blank category cases
     */
    public function blank_category()
    {
        $data['purpose_list'] = $this->PendingModel->get_listing_purpose();
        return view('ManagementReport/Pending/blank_category', $data);
    }

    /**
     * To fetch blank category cases
     */
    public function blank_category_get()
    {
        $data['board_type'] = $board_type = $this->request->getPost('board_type');
        $data['mainhead'] = $mainhead = $this->request->getPost('mainhead');
        $reg_unreg = $this->request->getPost('reg_unreg');
        $listorder = $this->request->getPost('listorder');
        $ucode = session()->get('login')['usercode'];
        $usertype = session()->get('login')['usertype'];
        $data['results'] =  $this->PendingModel->blank_category_report($ucode, $usertype, $board_type, $mainhead, $reg_unreg, $listorder);
        return view('ManagementReport/Pending/blank_category_get', $data);
    }

    /**
     * To display blank coram cases
     */
    public function blank_coram()
    {
        return view('ManagementReport/Pending/blank_coram');
    }

    /**
     * To fetch blank coram cases
     */
    public function blank_coram_get()
    {
        $data['board_type'] = $this->request->getPost('board_type');
        $data['mainhead'] = $this->request->getPost('mainhead');
        $reg_unreg = $this->request->getPost('reg_unreg');
        $data['results'] =  $this->PendingModel->blank_coram_get($data['board_type'], $data['mainhead'], $reg_unreg);
        return view('ManagementReport/Pending/blank_coram_get', $data);
    }

    /**
     * To display bunch matters
     */
    public function bunch_matter()
    {
        return view('ManagementReport/Pending/bunch_matter');
    }

    /**
     * To fetch bunch matters using grouping
     */
    public function bunch_matter_get()
    {
        $mainhead = $this->request->getPost('mainhead');
        $data['grp_hv'] = $this->request->getPost('grp_hv');
        $data['bunch_type'] = $this->request->getPost('bunch_type');
        if ($mainhead == 'M') {
            $mainhead_description = "Miscellaneous";
        }
        if ($mainhead == 'F') {
            $mainhead_description = "Regular";
        }
        if ($mainhead == 'L') {
            $mainhead_description = "Lok Adalat";
        }
        $data['mainhead_description'] = $mainhead_description;
        $data['mainhead'] = $mainhead;
        $data['results'] =  $this->PendingModel->bunch_matter_get($data['bunch_type'], $data['mainhead'], $data['grp_hv']);
        return view('ManagementReport/Pending/bunch_matter_get', $data);
    }

    /**
     * To fetch bunch matters by using diary number
     */
    public function bunch_matter_dno_detail()
    {
        $diary_no = $this->request->getPost('diary_no');
        $data['results'] =  $this->PendingModel->bunch_matter_dno_detail($diary_no);
        return view('ManagementReport/Pending/bunch_matter_dno_detail', $data);
    }


    // new add pushpendra

    public function sc_disposed_cav_verification_get()
    {
        $data['mainhead'] = $this->request->getPost('mainhead');
        $data['result_array'] = $this->PendingModel->sc_disposed_cav_verification_get($data['mainhead']);
        return view('ManagementReport/Pending/sc_disposed_cav_verification_get', $data);
    }
    public function case_type_listed_disposed()
    {
        return view('ManagementReport/Pending/case_type_listed_disposed');
    }
    public function get_ct_listed_disposed()
    {
        $data['start_dt'] = date('Y-m-d', strtotime($this->request->getPost('start_dt')));
        $data['end_dt'] = date('Y-m-d', strtotime($this->request->getPost('end_dt')));
        $data['h3_head'] = "CASE TYPE WISE LISTED AND DISPOSED CASES";
        $data['usercode'] = session()->get('login')['usercode'];
        $data['usertype'] = session()->get('login')['usertype'];
        $data['get_ct_listed_disposed'] = $this->PendingModel->get_ct_listed_disposed($data['start_dt'], $data['end_dt']);
        return view('ManagementReport/Pending/get_ct_listed_disposed', $data);
    }

    public function get_ct_listed_disposed_popup()
    {
        $data['flag'] = $this->request->getPost('flag');
        $data['start_dt'] = date('Y-m-d', strtotime($this->request->getPost('start_dt')));
        $data['end_dt'] = date('Y-m-d', strtotime($this->request->getPost('end_dt')));
        $data['ct'] = $this->request->getPost('ct');
        $data['get_ct_listed_disposed_popup'] = $this->PendingModel->get_ct_listed_disposed_popup($data['flag'], $data['start_dt'], $data['end_dt'], $data['ct']);
        return view('ManagementReport/Pending/get_ct_listed_disposed_popup', $data);
    }
    public function spread_out_cert_catwise()
    {
        return view('ManagementReport/Pending/spread_out_cert_catwise');
    }
    public function spread_out_cert_catwise_get()
    {
        $data['list_dt'] = date('Y-m-d', strtotime($this->request->getPost('list_dt')));
        $data['result_array'] = $this->PendingModel->spread_out_cert_catwise_get($data['list_dt']);
        return view('ManagementReport/Pending/spread_out_cert_catwise_get', $data);
    }
    public function get_pendency_section_year_wise()
    {
        return view('ManagementReport/Pending/get_pendency_section_year_wise');
    }
    public function year_section_wise_pendency()
    {
        $data['array_result'] = $this->PendingModel->year_section_wise_pendency();
        return view('ManagementReport/Pending/year_section_wise_pendency', $data);
    }

    // Shubham work start 
    public function details()
    {
        $heading = "";
        $agency = $this->request->getGet('agency');
        $state = $this->request->getGet('state');
        $case_type = $this->request->getGet('case_type');
        $year = $this->request->getGet('year');
        $section = $this->request->getGet('section');

        if ($agency) {
            $condition_agency = "m.ref_agency_state_id=" . $agency;
            $heading .= " State-" . $state;
        } else {
            $condition_agency = "";
        }

        if ($case_type) {
            $condition_case = "m.active_casetype_id=" . $case_type;
            $casename = $this->PendingModel->getCaseName($case_type);
            $heading .= "Case Type-" . $casename;
        } else {
            $condition_case = "";
        }

        if ($year) {
            $condition_year = "m.active_reg_year=" . $year;
            $heading .= "Registration Year-" . $year;
        } else {
            $condition_year = "";
        }

        if ($section) {
            $condition_sec = "b.id=" . $section;
            $sectionname = $this->PendingModel->getSectionName($section);
            $heading .= "Section-" . $sectionname;
        } else {
            $condition_sec = "";
        }

        $data['heading'] = $heading;
        $data['details_list'] = $this->PendingModel->getDetailsList($condition_agency, $condition_case, $condition_year, $condition_sec);
        return view('ManagementReport/Pending/details', $data);
    }
    //  Shubham work END

    public function year_head_nature_wise_ason_rpt()
    {
        $data['subject'] = $this->PendingModel->subject();
        $data['act'] = $this->PendingModel->act();
        $data['case_stage'] = $this->PendingModel->case_stage();
        return view('ManagementReport/Pending/year_head_nature_wise_ason_rpt', $data);
    }
    public function get_subhead_for_ason()
    {
        $data['m_f'] = $this->request->getGet('m_f');
        $data['result_array'] = $this->PendingModel->get_subhead_for_ason($data['m_f']);
        return view('ManagementReport/Pending/get_subhead_for_ason', $data);
    }
    public function getcat_multiple()
    {
        $data['subject'] = $this->request->getGet('subject');
        $data['subject_length'] = $this->request->getGet('subject_length');
        $data['getcat_multiple'] = $this->PendingModel->getcat_multiple($data['subject']);
        return view('ManagementReport/Pending/getcat_multiple', $data);
    }
    public function getsubcat_mul()
    {
        $data['subject'] = $this->request->getGet('subject');
        $data['subject_length'] = $this->request->getGet('subject_length');
        $data['cat'] = $this->request->getGet('cat');
        $data['cat_length'] = $this->request->getGet('cat_length');
        $head1 = explode(',', $data['cat']);
        $str = '';
        for ($m = 0; $m < $data['cat_length']; $m++) {
            $head = explode('|', $head1[$m]);
            if ($m == 0)
                $str = "  (subcode1 ='" . $head[0] . "' and subcode2='" . $head[1] . "')";
            else
                $str = " (( subcode1 ='" . $head[0] . "' and subcode2='" . $head[1] . "') OR " . $str . ")";
        }
        $data['result_array'] = $this->PendingModel->getsubcat_mul($str);
        return view('ManagementReport/Pending/getsubcat_mul', $data);
    }
    public function getsubcat2_mul()
    {
        $head1 = explode(',', $this->request->getGet('subcat'));
        $str2 = '';
        for ($m2 = 0; $m2 < $this->request->getGet('subcat_length'); $m2++) {
            $head2 = explode('|', $head1[$m2]);
            if ($m2 == 0)
                $str2 = "  (subcode1 ='" . $head2[0] . "' AND subcode2='" . $head2[1] . "' AND subcode3='" . $head2[2] . "')";
            else
                $str2 = " (( subcode1 ='" . $head2[0] . "' AND subcode2='" . $head2[1] . "' AND subcode3='" . $head2[2] . "') OR " . $str2 . ")";
        }
        $data['result_array'] = $this->PendingModel->getsubcat2_mul($str2);

        return view('ManagementReport/Pending/getsubcat2_mul', $data);
    }

    
    public function get_year_head_nature_wise_ason_rpt()
    {
        ini_set('memory_limit', '-1');
        set_time_limit(20000);
	    ini_set("pcre.backtrack_limit", "5000000");
        $request = $this->PendingModel->get_nature_wise_ason();
        // echo "<pre>";pr($request['query']);die;
        $query = $this->db->query($request['query']);
        $data['results'] = $results = $query->getResultArray();
        $data['tot_row'] = count($results);
        $data['civil_colspan'] = $this->tot_case_in_nature('C');
        $data['cr_colspan'] = $this->tot_case_in_nature('R');
        $data['til_dt']   = $request['date'];
        $data['head_subhead'] = $request['subhead_name'];
        $data['rpt_type'] = $this->request->getGet('rpt_type');
        $data['db'] = \Config\Database::connect();
        return view('ManagementReport/Pending/get_year_head_nature_wise_ason_rpt', $data);
    }


    public function show_case_for_ason()
    {
        ini_set('memory_limit', '-1');
        set_time_limit(20000);
        ini_set('max_execution_time', '300');
	    ini_set("pcre.backtrack_limit", "5000000");
        $request = $this->PendingModel->get_nature_wise_ason_model();
        $query = $this->db->query($request['query']);
        // echo "<pre>";pr($request['query']);die;
        $result = $query->getResultArray();
        $data['table'] = $result;
        $data['skey'] = $request['skey'];
        $data['mainhead_name'] = $request['mainhead_name'];
        $data['subhead_name'] = $request['subhead_name'];
        $data['til_dt'] = $request['til_dt'];
        $data['year_wise_tot'] = $request['year_wise_tot'];
        $data['case_status_id'] = $request['case_status_id'];
        return view('ManagementReport/Pending/show_case_for_ason', $data);
    }

    function casetype($skey)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('master.casetype');
        $builder->select('casecode');
        $builder->where('skey', $skey);
        $query = $builder->get();
        $result = $query->getRowArray();

        return $result ? $result['casecode'] : " ";
    }

    function judge($jcode)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('judge');
        $builder->select('jname');
        $builder->where('jcode', $jcode);
        $query = $builder->get();
        $result = $query->getRowArray();

        return $result ? $result['jname'] : " ";
    }

    function casetype2($casecode)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('master.casetype');
        $builder->select('short_description');
        $builder->where('casecode', $casecode);
        $query = $builder->get();
        $result = $query->getRowArray();

        return $result ? $result['short_description'] : " ";
    }



    private function tot_case_in_nature($nature)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('master.casetype');
        $builder->where('nature', $nature);
        $builder->where('display', 'Y');
        $builder->orderBy('nature', 'ASC');
        $builder->orderBy('skey', 'ASC');
        $query = $builder->get();
        return $query->getNumRows();
    }

    private  function stagename($scode)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('master.subheading');
        $builder->where('display', 'Y');
        $builder->whereIn('stagecode', explode(',', $scode));
        $builder->orderBy('stagecode', 'ASC');
        $query = $builder->get();
        $result = $query->getRowArray();
        return $result ? $result['stagename'] : '';
    }


    public function regular_in_misc()
    {
        return view('ManagementReport/Pending/regular_in_misc');
    }
    public function regular_in_misc_get()
    {
        $data['result_array'] = $this->PendingModel->regular_in_misc_get();
        return view('ManagementReport/Pending/regular_in_misc_get', $data);
    }
    public function pre_after_notice()
    {
        return view('ManagementReport/Pending/pre_after_notice');
    }
    public function pre_after_notice_get()
    {
        $data['result_array'] = $this->PendingModel->pre_after_notice_get();
        return view('ManagementReport/Pending/pre_after_notice_get', $data);
    }
    public function ready_not()
    {
        return view('ManagementReport/Pending/ready_not');
    }
    public function ready_not_get()
    {
        $data['connt'] = $this->request->getPost('connt');
        $data['future_date'] = $this->PendingModel->ready_not_future_date();
        $data['back_date'] = $this->PendingModel->ready_not_back_date($data['connt']);
        return view('ManagementReport/Pending/ready_not_get', $data);
    }
    public function not_ready_get_detail()
    {
        $ct = $this->request->getGet('ct');
        $flag = $this->request->getGet('flag');
        $list_dt_t = $this->request->getGet('list_dt');
        if ($list_dt_t !== '0') {
            $list_dt = date('Y-m-d', strtotime($this->request->getGet('list_dt')));
        } else {
            $list_dt = 0;
        }

        $ltype = $this->request->getGet('ltype');
        if ($ct == '1') {
            $connt = " ";
        }
        if ($ct == '2') {
            $connt = "(m.diary_no::TEXT = m.conn_key::TEXT OR m.conn_key::TEXT = '' OR m.conn_key::TEXT IS NULL OR m.conn_key::TEXT = '0') AND ";
        }

        if ($flag == 'f') {
            $dt_flag = " AND h.next_dt >= CURRENT_DATE";
            $headnote1 = " Future Date Cases ";
        }
        if ($flag == 'b') {
            $dt_flag = " AND h.next_dt < CURRENT_DATE";
            $headnote1 = " Back Date Cases ";
        }

        if ($list_dt === 0) {
            $list_dt_f = " ";
            $headnote1 .= " ";
        } else {
            $list_dt_f = " AND h.next_dt = '$list_dt'";
            $headnote1 .= " For Dated " . $list_dt;
        }
        if ($ltype == 'court_r') {
            $headnote1 .= " Ready to list before Court ";
        }
        if ($ltype == 'court_nr') {
            $headnote1 .= " Not Ready to list before Court ";
        }
        if ($ltype == 'court') {
            $headnote1 .= " Ready/Not Ready to list before Court ";
        }
        if ($ltype == 'chamber_r') {
            $headnote1 .= " Ready to list before Chamber ";
        }
        if ($ltype == 'chamber_nr') {
            $headnote1 .= " Not Ready to list before Chamber ";
        }
        if ($ltype == 'chamber') {
            $headnote1 .= " Ready/Not Ready to list before Chamber ";
        }
        if ($ltype == 'reg_r') {
            $headnote1 .= " Ready to list before Registrar ";
        }
        if ($ltype == 'reg_nr') {
            $headnote1 .= " Not Ready to list before Registrar ";
        }
        if ($ltype == 'reg') {
            $headnote1 .= " Ready/Not Ready to list before Registrar ";
        }
        if ($ltype == 'ready') {
            $headnote1 .= " Total Ready to list ";
        }
        if ($ltype == 'not_ready') {
            $headnote1 .= " Total Not Ready to list ";
        }
        if ($ltype == 'Total') {
            $headnote1 .= " Total Ready/Not Ready to list before ";
        }

        $data['headnote1'] = $headnote1;
        $data['headnote2'] = '';
        $data['result_array'] = $this->PendingModel->not_ready_get_detail($connt, $dt_flag, $ltype, $list_dt_f);
        return view('ManagementReport/Pending/not_ready_get_detail', $data);
    }
    public function section_pendency()
    {

        $data['result_array'] = $this->PendingModel->section_pendency();
        return view('ManagementReport/Pending/section_pendency', $data);
    }
    //////kr************************************************************************************************
    public function institution_report()
    {
        return view('ManagementReport/Pending/institution');
    }
    // public function institution_report_post()
    // {

    //     $from_date  = $_POST['from_date'];
    //     $to_date    = $_POST['to_date'];
    //     $rpt_type   = $_POST['rpt_type'];
    //     if ($rpt_type == 'registration' || $rpt_type == 'institution') {

    //         $condition = "1=1"; 
    //         if ($_POST['rpt_type'] == 'registration') {
    //             $report_name = 'Fresh Registration';
    //         }
    //         if ($_POST['rpt_type'] == 'institution') {
    //             $report_name = 'Institution';
    //             $condition = " substr(`fil_no`, 1, 2) != 39"; 
    //         }
    //         $result['report_data']  = $this->PendingModel->get_institution_report($from_date, $to_date, $rpt_type);
    //         $result['from_date'] = $from_date; 
    //         $result['to_date'] = $to_date;
    //         return view('ManagementReport/Pending/data_institution', $result);
    //     }

    // }

    public function institution_report_post()
    {
        $data['from_date'] = $this->request->getPost('from_date');
        $data['to_date']   = $this->request->getPost('to_date');
        $data['rpt_type']  = $this->request->getPost('rpt_type');
        $data['model']  = $this->PendingModel;
        return view('ManagementReport/Pending/data_institution', $data);
    }


    public function InstitutionDisposal()
    {
        return view('ManagementReport/Pending/InstitutionDisposal');
    }
    public function InstitutionDisposalPost()
    {

        $ddlYear = $this->request->getPost('ddlYear');
        $ddlMonth = $this->request->getPost('ddlMonth');
        $report_data['report_data']  = $this->PendingModel->get_institutionDisposal_report($ddlYear, $ddlMonth);
        //pr($result['report_data']);die;
        $report_name = '';
        $month = $ddlMonth;
        $year = $ddlYear;
        $report_name = 'Institution';
        $query_date = date($year . '-' . $month . '-01');

        // First day of the month.
        $firstDate = date('Y-m-01', strtotime($query_date));

        // Last day of the month.
        $lastDate = date('Y-m-t', strtotime($query_date));
        $report_data['firstDate'] = $firstDate;
        $report_data['lastDate']   =  $lastDate;
        $report_data['report_name']   =  $report_name;
        return view('ManagementReport/Pending/DataInstitutionDisposal', $report_data);
    }

    public function pendency_reports()
    {
        return view('ManagementReport/Pending/JudgeWiseMatterListedDisposal_form');
    }

    public function pendency_reports_post($reportType1 = null, $fromdate = null, $todate = null, $jcode = null)
    {
        // pr($_POST);die;

        if ($_POST) {
            $id = 6;
            // Check if from_date and to_date are set in POST
            if (isset($_POST['from_date']) && isset($_POST['to_date'])) {
                // Convert from_date and to_date to Y-m-d format
                $from_date = date('Y-m-d', strtotime($_POST['from_date']));
                $to_date = date('Y-m-d', strtotime($_POST['from_date']));
            } else {
                // Default to today's date if not provided
                $from_date = date('Y-m-d');
                $to_date = date('Y-m-d');
            }

            // Fetch the report data with from_date and to_date
            $reports['reports'] = $this->PendingModel->get_pendency($id, NULL, NULL, $from_date, $to_date);

            // pr( $reports['reports']);
            $reports['app_name'] = 'JudgeWiseMatterListedDisposal';
        }


        return view('ManagementReport/Pending/dataJudgeWiseMatterListedDisposal', $reports);
    }

    ////kr********************************************************************************************************************************
}
