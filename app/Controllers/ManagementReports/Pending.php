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
    public function details()
    {
        $data['year'] = $this->request->getGet('year');
        $data['section'] = $this->request->getGet('section');
        return view('ManagementReport/Pending/details', $data);
    }
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
    public function get_year_head_nature_wise_ason_rpt(){
        $data['result_array'] = $this->PendingModel->get_year_head_nature_wise_ason_rpt();
        return view('ManagementReport/Pending/get_year_head_nature_wise_ason_rpt', $data);
    }
    public function regular_in_misc(){
        return view('ManagementReport/Pending/regular_in_misc');
    }
    public function regular_in_misc_get(){
        $data['result_array'] = $this->PendingModel->regular_in_misc_get();
        return view('ManagementReport/Pending/regular_in_misc_get',$data);
    }
    public function section_pendency(){
        
        $data['result_array'] = $this->PendingModel->section_pendency();
        return view('ManagementReport/Pending/section_pendency',$data);
    }
    //////kr************************************************************************************************
    public function institution_report()
    {
        return view('ManagementReport/Pending/institution');
    }
    public function institution_report_post()
    {
        //pr($_POST);die;
        $from_date  = $_POST['from_date'];
        $to_date    = $_POST['to_date'];
        $rpt_type   = $_POST['rpt_type'];
        if ($_POST['rpt_type'] == 'registration' || $_POST['rpt_type'] == 'institution') {
            $condition = "1=1"; // Default condition
            if ($_POST['rpt_type'] == 'registration') {
                $report_name = 'Fresh Registration';
            }
            if ($_POST['rpt_type'] == 'institution') {
                $report_name = 'Institution';
                $condition = " substr(`fil_no`, 1, 2) != 39"; // Additional condition for institution
            }
            $result['report_data']  = $this->PendingModel->get_institution_report($from_date, $to_date, $rpt_type);
            // pr($result['report_data']);die;
            $result['from_date'] = $from_date; // Add from_date to result
            $result['to_date'] = $to_date;
            return view('ManagementReport/Pending/data_institution', $result);
        }
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
