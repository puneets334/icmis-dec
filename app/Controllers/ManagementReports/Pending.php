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
        // echo "<pre>";
        
        $str = $this->get_case_type();       
        
        $bench = '';
        $benchInput = $this->request->getGet('bench');

        if ($benchInput === 'all') {
            $bench = '';
        } elseif ($benchInput === '2') {
            $bench = " AND h.judges LIKE '%,%'";
        } elseif ($benchInput === '3') {
            $bench = " AND h.judges LIKE '%,%,%'";
        } elseif ($benchInput === '5') {
            $bench = " AND h.judges LIKE '%,%,%,%,%'";
        } elseif ($benchInput === '7') {
            $bench = " AND h.judges LIKE '%,%,%,%,%,%,%'";
        } elseif ($benchInput === '9') {
            $bench = " AND h.judges LIKE '%,%,%,%,%,%,%,%,%'";
        } else {
            $bench = " AND h.judges NOT LIKE '%%,%'";
        }


        if ($this->request->getGet('ason_type') == 'dt') {
            $til_date = explode("-", $this->request->getGet('til_date'));
            $til_dt = $til_date[2] . "-" . $til_date[1] . "-" . $til_date[0];

        $ason_str = " IF(d.rj_dt != '0000-00-00', d.rj_dt >= '" . $til_dt . "',
                    IF(d.`disp_dt` != '0000-00-00' AND d.`disp_dt` IS NOT NULL, d.disp_dt >= '" . $til_dt . "', 
                    CONCAT(d.year, '-', LPAD(d.month, 2, 0), '-01') >= '" . $til_dt . "'))";

        $ason_str_res = "IF(disp_rj_dt != '0000-00-00', disp_rj_dt >= '" . $til_dt . "',
                    IF(r.disp_dt != '0000-00-00' AND r.disp_dt IS NOT NULL, r.disp_dt >= '" . $til_dt . "', 
                    CONCAT(r.disp_year, '-', LPAD(r.disp_month, 2, 0), '-01') >= '" . $til_dt . "'))";

        $exclude_cond = "CASE WHEN r.`disp_dt` != '0000-00-00' AND r.`disp_dt` IS NOT NULL 
                AND r.conn_next_dt != '0000-00-00' AND r.conn_next_dt IS NOT NULL
            THEN '" . $til_dt . "' NOT BETWEEN r.disp_dt AND `conn_next_dt` 
            ELSE r.`disp_dt` = '0000-00-00' OR r.`disp_dt` IS NULL OR r.conn_next_dt = '0000-00-00' OR r.conn_next_dt IS NULL 
            END OR r.fil_no IS NULL";

        $exclude_cond_other = " CASE WHEN r.`disp_dt` != '0000-00-00' AND r.`disp_dt` IS NOT NULL 
                AND r.conn_next_dt != '0000-00-00' AND r.conn_next_dt IS NOT NULL
            THEN '" . $til_dt . "' NOT BETWEEN r.disp_dt AND `conn_next_dt` 
            ELSE r.`disp_dt` = '0000-00-00' OR r.`disp_dt` IS NULL OR r.conn_next_dt = '0000-00-00' OR r.conn_next_dt IS NULL END";

        }else if ($this->request->getGet('ason_type') == 'month') {
            $til_dt = $this->request->getGet('lst_year') . "-" . str_pad($this->request->getGet('lst_month'), 2, "0", STR_PAD_LEFT) . "-01";

            $ason_str = " IF(d.rj_dt != '0000-00-00', d.rj_dt >= '" . $til_dt . "', 
                            IF(d.month = 0, d.disp_dt >= '" . $til_dt . "', CONCAT(d.year, '-', LPAD(d.month, 2, 0), '-01') >= '" . $til_dt . "'))";

            $ason_str_res = " IF(r.disp_rj_dt != '0000-00-00', r.disp_rj_dt >= '" . $til_dt . "', 
                            IF(r.disp_month = 0, r.disp_dt >= '" . $til_dt . "', CONCAT(r.disp_year, '-', LPAD(r.disp_month, 2, 0), '-01') >= '" . $til_dt . "'))";

            $exclude_cond = " CASE 
            WHEN r.disp_month != '0' AND r.disp_month IS NOT NULL AND r.month != '0' AND r.month IS NOT NULL 
            THEN '" . $til_dt . "' NOT BETWEEN CONCAT(r.disp_year, '-', LPAD(r.disp_month, 2, '0'), '-01') AND CONCAT(r.year, '-', LPAD(r.month, 2, '0'), '-01') 
            WHEN r.month != '0' AND r.month IS NOT NULL 
            THEN CONCAT(r.year, '-', LPAD(r.month, 2, '0'), '-01') != '" . $til_dt . "'
            ELSE r.disp_month = '0' OR r.`disp_month` IS NULL OR r.month = '0' OR r.month IS NULL END OR r.fil_no IS NULL";

            $exclude_cond_other = " CASE 
            WHEN r.disp_month != '0' AND r.disp_month IS NOT NULL AND r.month != '0' AND r.month IS NOT NULL 
            THEN '" . $til_dt . "' NOT BETWEEN CONCAT(r.disp_year, '-', LPAD(r.disp_month, 2, '0'), '-01') 
            AND CONCAT(r.year, '-', LPAD(r.month, 2, '0'), '-01') 
            WHEN r.month != '0' AND r.month IS NOT NULL 
            THEN CONCAT(r.year, '-', LPAD(r.month, 2, '0'), '-01') != '" . $til_dt . "'
            ELSE r.disp_month = '0' OR r.`disp_month` IS NULL OR r.month = '0' OR r.month IS NULL END";
        } else if ($this->request->getGet('ason_type') == 'ent_dt') {
            $til_date = explode("-", $this->request->getGet('til_date'));
            $til_dt = $til_date[2] . "-" . $til_date[1] . "-" . $til_date[0];

            $ason_str = " d.ent_dt >= '" . $til_dt . "'";

            $ason_str_res = " r.disp_ent_dt >= '" . $til_dt . "'";

            $exclude_cond = " CASE WHEN DATE(r.entry_date) != '0000-00-00' AND r.`entry_date` IS NOT NULL 
                        AND DATE(r.disp_ent_dt) != '0000-00-00' AND r.disp_ent_dt IS NOT NULL
            THEN '" . $til_dt . "' NOT BETWEEN DATE(r.disp_ent_dt) AND `entry_date` 
            ELSE DATE(r.`disp_ent_dt`) = '0000-00-00' OR r.`disp_ent_dt` IS NULL OR DATE(r.entry_date) = '0000-00-00' OR r.entry_date IS NULL END 
            OR r.fil_no IS NULL";

            $exclude_cond_other = " CASE WHEN DATE(r.entry_date) != '0000-00-00' AND r.`entry_date` IS NOT NULL 
                        AND DATE(r.disp_ent_dt) != '0000-00-00' AND r.disp_ent_dt IS NOT NULL
            THEN '" . $til_dt . "' NOT BETWEEN DATE(r.disp_ent_dt) AND `entry_date` 
            ELSE DATE(r.`disp_ent_dt`) = '0000-00-00' OR r.`disp_ent_dt` IS NULL OR DATE(r.entry_date) = '0000-00-00' OR r.entry_date IS NULL END";
        }

        // print_r($ason_str);
        // echo "<br><hr>";
        // print_r($ason_str_res);
        // echo "<br><hr>";
        // print_r($exclude_cond);
        // echo "<br><hr>";
        // print_r($exclude_cond_other);
        // echo "<br><hr>";
        


        if ($this->request->getGet('rpt_purpose') == 'sw') {
            $subhead_name = "subhead_n";
            $mainhead_name = "mainhead_n";
        } else {
            $subhead_name = "subhead";
            $mainhead_name = "mainhead";
        }
        // print_r($subhead_name);
        // echo "<br><hr>";
        // print_r($mainhead_name);
        // echo "<br><hr>";
        

        if ($this->request->getGet('subhead') == 'all,' || $this->request->getGet('subhead') == '') {
            $subhead = '';
            // Removed unused variable $subhead_if_heardt
            $subhead_if_last_heardt = " ";
            $subhead_condition = " ";
            $head_subhead = " ";
            // Removed unused variable $head_subhead
        } else {
            $subhead = " AND l." . $subhead_name . " IN (" . substr($this->request->getGet('subhead'), 0, -1) . ")";
            $subhead_if_heardt = " AND h." . $subhead_name . " IN (" . substr($this->request->getGet('subhead'), 0, -1) . ")";
            $subhead_if_last_heardt = " AND f2." . $subhead_name . " IN (" . substr($this->request->getGet('subhead'), 0, -1) . ")";

            $subhead_if_heardt_con = " h." . $subhead_name . " IN (" . substr($this->request->getGet('subhead'), 0, -1) . ")";
            $subhead_if_last_heardt_con = " f2." . $subhead_name . " IN (" . substr($this->request->getGet('subhead'), 0, -1) . ")";

            if ($this->request->getGet('til_date') != date('d-m-Y')) {
                $subhead_condition = " AND IF(DATE(h.ent_dt) < '" . $til_dt . "' AND DATE(h.ent_dt) > med, " . $subhead_if_heardt_con . ", " . $subhead_if_last_heardt_con . ")";
                $head_subhead = $this->stagename(substr($this->request->getGet('subhead'), 0, -1));
            } else {
                $subhead_condition = " AND " . $subhead_if_heardt_con;
                $head_subhead = $this->stagename(substr($this->request->getGet('subhead'), 0, -1));
            }
        }

        if ($this->request->getGet('concept') == 'new') {

            if ($this->request->getGet('mf') == 'M') {
                $mf_f2_table = " f2." . $mainhead_name . " = 'M' AND (admitted = '' OR admitted IS NULL)";
                $mf_h_table = " h." . $mainhead_name . " = 'M' AND (admitted = '' OR admitted IS NULL)";
            }
            if ($this->request->getGet('mf') == 'F') {
                $mf_f2_table = " (f2." . $mainhead_name . " = 'F' OR (admitted != '' AND admitted IS NOT NULL)) ";
                $mf_h_table = "( h." . $mainhead_name . " = 'F' OR (admitted != '' AND admitted IS NOT NULL))";
            }
            if ($this->request->getGet('mf') == 'N') {
                $mf_f2_table = " (f2." . $mainhead_name . " NOT IN ('M', 'F')) ";
                $mf_h_table = "( h." . $mainhead_name . " NOT IN ('M', 'F'))";
            }
        } elseif ($this->request->getGet('concept') == 'old') {
            if ($this->request->getGet('mf') == 'M') {
                $mf_f2_table = " f2." . $mainhead_name . " = '" . $this->request->getGet('mf') . "' ";
                $mf_h_table = " h." . $mainhead_name . " = '" . $this->request->getGet('mf') . "' ";
            }
            if ($this->request->getGet('mf') == 'F') {
                $mf_f2_table = " f2." . $mainhead_name . " = '" . $this->request->getGet('mf') . "' ";
                $mf_h_table = " h." . $mainhead_name . " = '" . $this->request->getGet('mf') . "' ";
            }
            if ($this->request->getGet('mf') == 'N') {
                $mf_f2_table = " (f2." . $mainhead_name . " NOT IN ('M', 'F')) ";
                $mf_h_table = "( h." . $mainhead_name . " NOT IN ('M', 'F'))";
            }
        }



        if (trim($this->request->getGet('subject')) != 'all,' || trim($this->request->getGet('act')) != 'all,' || trim($this->request->getGet('act_msc')) != '') {
            $mul_cat_join = " LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no
                              LEFT JOIN submaster s ON mc.submaster_id = s.id";
        } else {
            $mul_cat_join = " ";
        }
        if (trim($this->request->getGet('subcat2')) == 'all,') { 
            if (trim($this->request->getGet('subcat')) == 'all,') { 
                if (trim($this->request->getGet('cat')) == 'all,') {  
                    if (trim($this->request->getGet('subject')) == 'all,') {
                        $all_category = " ";
                    } else {
                        $all_category = "s.subcode1 IN (" . substr($this->request->getGet('subject'), 0, -1) . ")";
                    }
                } else {
                    $head1 = explode(',', $this->request->getGet('cat'));
                    $str_all_cat = "";
                    for ($m = 0; $m < $this->request->getGet('cat_length'); $m++) {
                        $head = explode('|', $head1[$m]);
                        if ($m == 0) {
                            $str_all_cat = "(s.subcode1 = " . $head[0] . " AND s.subcode2 = " . $head[1] . ")";
                        } else {
                            $str_all_cat = "((s.subcode1 = " . $head[0] . " AND s.subcode2 = " . $head[1] . ") OR " . $str_all_cat . ")";
                        }
                    }
                    $all_category = $str_all_cat;
                }
            } else {
                $head1 = explode(',', $this->request->getGet('subcat'));
                $str_all_cat = "";
                for ($m = 0; $m < $this->request->getGet('subcat_length'); $m++) {
                    $head = explode('|', $head1[$m]);
                    if ($m == 0) {
                        $str_all_cat = "(s.subcode1 = " . $head[0] . " AND s.subcode2 = " . $head[1] . " AND s.subcode3 = " . $head[2] . ")";
                    } else {
                        $str_all_cat = "((s.subcode1 = " . $head[0] . " AND s.subcode2 = " . $head[1] . " AND s.subcode3 = " . $head[2] . ") OR " . $str_all_cat . ")";
                    }
                }
                $all_category = $str_all_cat;
            }
        } else {
            $head1 = explode(',', $this->request->getGet('subcat2'));
            $str_all_cat = "";
            for ($m = 0; $m < $this->request->getGet('subcat2_length'); $m++) {
                $head = explode('|', $head1[$m]);
                if ($m == 0) {
                    $str_all_cat = "(s.subcode1 = " . $head[0] . " AND s.subcode2 = " . $head[1] . " AND s.subcode3 = " . $head[2] . " AND s.subcode4 = " . $head[3] . ")";
                } else {
                    $str_all_cat = "((s.subcode1 = " . $head[0] . " AND s.subcode2 = " . $head[1] . " AND s.subcode3 = " . $head[2] . " AND s.subcode4 = " . $head[3] . ") OR " . $str_all_cat . ")";
                }
            }
            $all_category = $str_all_cat;
        }
        
        if (trim($this->request->getGet('act')) == 'all,') {
            $all_act = " ";
        } else {
            if (trim($this->request->getGet('subject')) == 'all,') {
                $all_act = " a.act IN (" . substr($this->request->getGet('act'), 0, -1) . ")";
            } else {
                $all_act = " OR a.act IN (" . substr($this->request->getGet('act'), 0, -1) . ")";
            }
        }
        
        if (trim($this->request->getGet('act')) == 'all,' && trim($this->request->getGet('subject')) == 'all,') {
            $cat_and_act = " ";
        } else {
            $cat_and_act = " AND ( " . $all_category . " " . $all_act . " )";
        }
        
        if ($this->request->getGet('from_year') == '' || $this->request->getGet('to_year') == '') {
            if ($this->request->getGet('from_year') == '' && $this->request->getGet('to_year') != '') {
                $year_main = " AND SUBSTR(m.diary_no, -4) <= '" . $this->request->getGet('to_year') . "' ";
                $year_lastheardt = " AND SUBSTR(l.diary_no, -4) <= '" . $this->request->getGet('to_year') . "' ";
            } elseif ($this->request->getGet('from_year') != '' && $this->request->getGet('to_year') == '') {
                $year_main = " AND SUBSTR(m.diary_no, -4) >= '" . $this->request->getGet('from_year') . "' ";
                $year_lastheardt = " AND SUBSTR(l.diary_no, -4) >= '" . $this->request->getGet('from_year') . "' ";
            } else {
                $year_main = " ";
                $year_lastheardt = " ";
            }
        } else {
            $year_main = " AND SUBSTR(m.diary_no, -4) BETWEEN '" . $this->request->getGet('from_year') . "' AND '" . $this->request->getGet('to_year') . "' ";
            $year_lastheardt = " AND SUBSTR(l.diary_no, -4) BETWEEN '" . $this->request->getGet('from_year') . "' AND '" . $this->request->getGet('to_year') . "' ";
        }
        
        $Brep = "";
        $Brep1 = "";
        $act_join ='';
        $registration='';
        $main_connected='';
        
        $from_fil_dt = $this->request->getGet('from_fil_dt') ? 
            " AND DATE(m.diary_no_rec_date) > '" . date('Y-m-d', strtotime($this->request->getGet('from_fil_dt'))) . "' " : " ";
        
        
        $upto_fil_dt = $this->request->getGet('upto_fil_dt') ? 
            " AND DATE(m.diary_no_rec_date) < '" . date('Y-m-d', strtotime($this->request->getGet('upto_fil_dt'))) . "' " : " ";
        
        $add_table = '';
       
        if ($this->request->getGet('case_status_id') == 'all,') {
            $case_status_id = " AND case_status_id IN (1, 2, 3, 6, 7, 9) ";
            $add_table = '';
        } elseif ($this->request->getGet('case_status_id') == '103,') {
            $case_status_id = " ";
            $registration = " ";
        } elseif ($this->request->getGet('case_status_id') == 101) {
            $registration = " AND (active_fil_no = '' OR active_fil_no IS NULL) ";
            
        } elseif ($this->request->getGet('case_status_id') == 102) {
            $registration = " AND !(active_fil_no = '' OR active_fil_no IS NULL) ";
        } elseif ($this->request->getGet('case_status_id') == 104 || $this->request->getGet('case_status_id') == '104,') {
            $case_status_id = " ";
            $Brep = " INNER JOIN
            (SELECT CASE WHEN os.diary_no IS NULL THEN m.diary_no ELSE 0 END AS dd FROM main m
             INNER JOIN docdetails b ON m.diary_no = b.diary_no
             LEFT OUTER JOIN
            (SELECT DISTINCT diary_no FROM obj_save WHERE rm_dt IS NULL AND display = 'Y')
            os ON m.diary_no = os.diary_no
             WHERE c_status = 'P' AND (active_fil_no IS NULL OR active_fil_no = '')
            AND (
            (doccode = '8' AND doccode1 = '28') OR 
            (doccode = '8' AND doccode1 = '95') OR 
            (doccode = '8' AND doccode1 = '214') OR 
            (doccode = '8' AND doccode1 = '215')
            )
            AND b.iastat = 'P') aa ON m.diary_no = aa.dd ";

        }
        elseif ($this->request->getGet('case_status_id') == 105) {
            $Brep = " INNER JOIN
            (SELECT CASE WHEN os.diary_no IS NULL THEN m.diary_no ELSE 0 END AS dd FROM main m
             INNER JOIN docdetails b ON m.diary_no = b.diary_no
             LEFT OUTER JOIN
            (SELECT DISTINCT diary_no FROM obj_save WHERE rm_dt IS NULL AND display = 'Y')
            os ON m.diary_no=os.diary_no
             WHERE  c_status = 'P' AND (active_fil_no IS NULL OR active_fil_no='')
            AND(
            (doccode = '8' AND doccode1 = '16') OR 
            (doccode = '8' AND doccode1 = '79') OR 
            (doccode = '8' AND doccode1 = '99') OR 
            (doccode = '8' AND doccode1 = '300')
            )
            AND b.iastat='P') aa ON m.diary_no=aa.dd ";
        } elseif ($this->request->getGet('case_status_id') == 106) {
            $Brep = " LEFT OUTER JOIN (SELECT DISTINCT diary_no FROM obj_save WHERE rm_dt IS NULL AND display = 'Y')
                                os ON m.diary_no=os.diary_no
                                ";
            $Brep1 = " and os.diary_no IS NOT NULL and c_status = 'P' AND (active_fil_no IS NULL OR  active_fil_no='') AND h.board_type='J'";
        } elseif ($this->request->getGet('case_status_id') == 107) {
            $Brep = " INNER JOIN docdetails b ON m.diary_no=b.diary_no
            INNER JOIN
            (SELECT DISTINCT diary_no FROM obj_save WHERE rm_dt IS NULL AND display = 'Y' AND DATEDIFF(NOW(),save_dt)>60) os
            ON m.diary_no=os.diary_no ";
            $Brep1 = " and m.c_status = 'P' AND (m.active_fil_no IS NULL OR  m.active_fil_no='')
            AND doccode = '8' AND doccode1 = '226' AND b.iastat='P' ";
        } elseif ($this->request->getGet('case_status_id') == 108) {
            $Brep = " INNER JOIN docdetails b ON m.diary_no=b.diary_no
            INNER JOIN
            (SELECT DISTINCT diary_no FROM obj_save WHERE rm_dt IS NULL AND display = 'Y' AND DATEDIFF(NOW(),save_dt)<=60) os
            ON m.diary_no=os.diary_no ";
            $Brep1 = " and  m.c_status = 'P' AND (m.active_fil_no IS NULL OR  m.active_fil_no='')
            AND doccode = '8' AND doccode1 = '226' AND b.iastat='P' ";
        } elseif ($this->request->getGet('case_status_id') == 109) {
            $Brep = " LEFT JOIN (SELECT DISTINCT CASE WHEN os.diary_no IS NULL THEN m.diary_no ELSE 0 END AS dd FROM main m
             INNER JOIN docdetails b ON m.diary_no = b.diary_no
             LEFT OUTER JOIN
            (SELECT DISTINCT diary_no FROM obj_save WHERE rm_dt IS NULL AND display = 'Y')
            os ON m.diary_no=os.diary_no
             WHERE  c_status = 'P' AND (active_fil_no IS NULL OR active_fil_no='')
            AND (((
            (doccode = '8' AND doccode1 = '28') OR 
            (doccode = '8' AND doccode1 = '95') OR 
            (doccode = '8' AND doccode1 = '214') OR 
            (doccode = '8' AND doccode1 = '215') OR 
            (doccode = '8' AND doccode1 = '16') OR 
            (doccode = '8' AND doccode1 = '79') OR 
            (doccode = '8' AND doccode1 = '99') OR 
            (doccode = '8' AND doccode1 = '300') OR
            (doccode = '8' AND doccode1 = '226') OR 
            (doccode = '8' AND doccode1 = '288') OR 
            (doccode = '8' AND doccode1 = '322')
            )
            AND b.iastat='P' ))) aa ON m.diary_no=aa.dd
            LEFT OUTER JOIN
                                (SELECT DISTINCT diary_no FROM obj_save WHERE
                                (rm_dt IS NULL OR rm_dt='0000-00-00 00:00:00') AND display='Y')
                                os1 ON m.diary_no=os1.diary_no ";
            $Brep1 = " and m.c_status = 'P' AND IF((m.active_fil_no IS NULL OR m.active_fil_no=''),(aa.dd !=0 OR (os1.diary_no IS NOT NULL AND h.board_type='J')),3=3) ";
            
        }
        /*elseif($_GET['case_status_id']==101){
        $case_status_id=" and o.rm_dt = '0000-00-00 00:00:00' 
            AND o.display = 'Y' 
            AND m.c_status = 'P' 
            AND (m.fil_no IS NULL 
              OR m.fil_no = '')"; 
        $add_table=' LEFT JOIN obj_save o ON o.diary_no = m.diary_no ';
        }
        elseif($_GET['case_status_id']==102){
        //$case_status_id=" and m.diary_no NOT IN (SELECT DISTINCT diary_no FROM `obj_save` WHERE rm_dt = '0000-00-00 00:00:00' AND display = 'Y' ) "; 
         $case_status_id=" and (!(m.fil_no IS NULL OR m.fil_no = '')) "; 
            $add_table='';
        }*/
        else {
            $case_status_id = " and case_status_id in (" . substr($this->request->getGet('case_status_id'), 0, -1) . ")"; 
            $add_table = '';
        }
     
        if ($this->request->getGet('mf') != 'ALL') {
            if ($this->request->getGet('til_date') != date('d-m-Y')) {
                echo '<br>';
                $t = "CREATE TEMPORARY TABLE vw2 
                        SELECT diary_no, MAX(ent_dt) AS med, " . $subhead_name . ", " . $mainhead_name . "
                        FROM `last_heardt` l
                        WHERE DATE(ent_dt) < '" . $til_dt . "' " . $year_lastheardt . " 
                        GROUP BY diary_no";
                $this->db->query($t);
        
                // echo '<br>';
                $t2 = "CREATE INDEX id_index ON vw2 (diary_no)";
                $db->query($t2);
        
                // echo '<br>';
                $t3 = "CREATE TEMPORARY TABLE vw3 
                        SELECT l.diary_no, l." . $subhead_name . ", l.judges, med, next_dt, l." . $mainhead_name . "
                        FROM vw2 
                        INNER JOIN last_heardt l ON vw2.diary_no = l.diary_no
                        AND l.ent_dt = med
                        AND l." . $mainhead_name . " = '" . $this->request->getGet('mf') . "' " . $subhead;
                $db->query($t3);
        
                // echo '<br>';
                $t4 = "CREATE INDEX id_index2 ON vw3 (diary_no)";
                $db->query($t4);
            }
        }
        
        if ($this->request->getGet('mf') != 'ALL') {
            if ($this->request->getGet('til_date') != date('d-m-Y')) {
                $sql = "
                SELECT SUBSTR(diary_no::text, -4) AS year ," . $str . " FROM 
                (
                    SELECT m.diary_no, m.fil_dt, c_status, d.rj_dt, d.month, d.year, d.disp_dt, active_casetype_id, casetype_id
                    FROM main m " . $Brep . " 
                    LEFT JOIN heardt h ON m.diary_no = h.diary_no 
                    LEFT JOIN dispose d ON m.diary_no = d.diary_no
                    LEFT JOIN restored r ON m.diary_no = r.diary_no
                    LEFT JOIN vw3 f2 ON m.diary_no = f2.diary_no 
                    LEFT JOIN act_main a ON a.diary_no = m.diary_no " . $add_table . $mul_cat_join . $act_join . "
                    WHERE 1=1 " . $Brep1 . $registration . " " . $bench . " " . $cat_and_act . " " . $year_main . " " . $from_fil_dt . " " . $upto_fil_dt . " " . $case_status_id . "
                    AND IF(med > h.ent_dt AND f2." . $mainhead_name . " IS NOT NULL, " . $mf_f2_table . " " . $subhead_if_last_heardt . ", " . $mf_h_table . " " . $subhead_if_last_heardt . ") 
                    AND (
                        " . $exclude_cond . "
                    ) " . $subhead_condition . " AND DATE(m.diary_no_rec_date) < '" . $til_dt . "' AND c_status = 'P' 
                    OR (
                        c_status = 'D' " . $cat_and_act . " " . $year_main . " " . $from_fil_dt . " " . $upto_fil_dt . "
                        AND IF(med > h.ent_dt AND f2." . $mainhead_name . " IS NOT NULL, " . $mf_f2_table . " " . $subhead_if_last_heardt . ", " . $mf_h_table . " " . $subhead_if_last_heardt . ")
                        AND " . $ason_str . " AND DATE(m.diary_no_rec_date) < '" . $til_dt . "' " . $year_main . " " . $from_fil_dt . " " . $upto_fil_dt . " " . $cat_and_act . " " . $bench . " " . $pc_act . " " . $women . " " . $children . " " . $land . " " . $cr_compound . " " . $commercial_code . " " . $party_name . " " . $pet_res . " " . $act_msc . " AND " . $exclude_cond_other . "
                    )
                    OR ( 
                        " . $ason_str_res . "
                        AND IF(med > h.ent_dt AND f2." . $mainhead_name . " IS NOT NULL, " . $mf_f2_table . " " . $subhead_if_last_heardt . ", " . $mf_h_table . " " . $subhead_if_last_heardt . ")
                        AND DATE(m.diary_no_rec_date) < '" . $til_dt . "' " . $year_main . " " . $from_fil_dt . " " . $upto_fil_dt . " " . $cat_and_act . " " . $bench . " 
                        " . $pc_act . " " . $women . " " . $children . " " . $land . " " . $cr_compound . " " . $commercial_code . " " . $party_name . " " . $pet_res . " " . $act_msc . " AND " . $exclude_cond_other . "
                    )
                    GROUP BY m.diary_no, fil_dt, c_status, d.rj_dt, d.month, d.year, d.disp_dt, active_casetype_id, casetype_id LIMIT 100
                ) t
                GROUP BY ROLLUP(SUBSTR(diary_no::text, -4) ) ";
            } else {
                $sql = "
                SELECT SUBSTR(diary_no::text, -4) AS year ," . $str . " FROM 
                (
                    SELECT m.diary_no, m.fil_dt, c_status, d.rj_dt, d.month, d.year, d.disp_dt, active_casetype_id, casetype_id
                    FROM main m " . $Brep . " 
                    LEFT JOIN dispose d ON m.diary_no = d.diary_no
                    LEFT JOIN heardt h ON m.diary_no = h.diary_no 
                    LEFT JOIN restored r ON m.diary_no = r.diary_no
                    LEFT JOIN act_main a ON a.diary_no = m.diary_no
                    " . $add_table . $mul_cat_join . $act_join . "
                    WHERE " . $registration . " " . $mf_h_table . " " . $cat_and_act . " " . $year_main . " " . $from_fil_dt . " " . $upto_fil_dt . " " . $case_status_id . $Brep1 . "
                    AND case_status_id IN (1, 2, 3, 6, 7, 9) 
                    AND (c_status = 'P' AND DATE(m.diary_no_rec_date) < '" . $til_dt . "') " . $subhead_condition . "
                    GROUP BY m.diary_no, fil_dt, c_status, d.rj_dt, d.month, d.year, d.disp_dt, active_casetype_id, casetype_id LIMIT 100
                ) t
                GROUP BY ROLLUP(SUBSTR(diary_no::text, -4) ) ";
            }
        }
        else {
            if ($this->request->getGet('til_date') != date('d-m-Y')) {
                $sql = "
                SELECT SUBSTR(diary_no::text, -4) AS year ," . $str . " FROM 
                (
                    SELECT m.diary_no, m.fil_dt, c_status, d.rj_dt, d.month, d.year, d.disp_dt, active_casetype_id, casetype_id
                    FROM main m " . $Brep . " 
                    LEFT JOIN heardt h ON m.diary_no = h.diary_no 
                    LEFT JOIN dispose d ON m.diary_no = d.diary_no 
                    LEFT JOIN restored r ON m.diary_no = r.diary_no 
                    LEFT JOIN act_main a ON a.diary_no = m.diary_no " . $add_table . $mul_cat_join . " " . $act_join . "
                    WHERE 1=1 " . $Brep1 . $registration . " " . $cat_and_act . " " . $year_main . " " . $from_fil_dt . " " . $upto_fil_dt . " " . $case_status_id . " " . $main_connected . " AND 
                    (
                        " . $exclude_cond . "
                    ) AND DATE(m.diary_no_rec_date) < '" . $til_dt . "' AND c_status = 'P' 
                    OR 
                    (
                        c_status = 'D' AND " . $ason_str . " " . $cat_and_act . " " . $year_main . " " . $from_fil_dt . " " . $upto_fil_dt . " AND DATE(m.diary_no_rec_date) < '" . $til_dt . "' AND " . $exclude_cond_other . " " . $main_connected . "
                    )
                    GROUP BY m.diary_no, fil_dt, c_status, d.rj_dt, d.month, d.year, d.disp_dt, active_casetype_id, casetype_id LIMIT 100
                ) t
                GROUP BY ROLLUP(SUBSTR(diary_no::text, -4) ) ";
            } else {
                $sql = "
                SELECT SUBSTR(diary_no::text, -4) AS year ," . $str . " FROM 
                (
                    SELECT m.diary_no, m.fil_dt, c_status, d.rj_dt, d.month, d.year, d.disp_dt, active_casetype_id, casetype_id
                    FROM main m " . $Brep . " 
                    LEFT JOIN dispose d ON m.diary_no = d.diary_no
                    LEFT JOIN restored r ON m.diary_no = r.diary_no
                    LEFT JOIN heardt h ON m.diary_no = h.diary_no 
                    LEFT JOIN act_main a ON a.diary_no = m.diary_no " . $add_table . $mul_cat_join . " " . $act_join . "
                    WHERE 2=2 " . $Brep1 . $registration . " " . $bench . " " . $cat_and_act . " " . $year_main . " " . $from_fil_dt . " " . $upto_fil_dt . " " . $case_status_id . " AND (c_status = 'P' AND DATE(m.diary_no_rec_date) <= '" . $til_dt . "')
                    GROUP BY m.diary_no, fil_dt, c_status, d.rj_dt, d.month, d.year, d.disp_dt, active_casetype_id, casetype_id LIMIT 100
                ) t
                GROUP BY ROLLUP(SUBSTR(diary_no::text, -4) ) ";
            }
        }
        // pr($sql);
        // die;
        $query = $this->db->query($sql);
        $data['results'] = $results = $query->getResultArray();
        // echo "<pre>";print_r($results);die;
        $data['tot_row'] = count($results);
        $data['civil_colspan'] = $this->tot_case_in_nature('C');
        $data['cr_colspan'] = $this->tot_case_in_nature('R');
        $data['til_dt']   = $til_dt;
        $data['head_subhead'] = $head_subhead;
        $data['rpt_type'] = $this->request->getGet('rpt_type');
        $data['db'] = \Config\Database::connect();

        // echo "<pre>";
        // print_r($this->request->getGet('rpt_type'));
        // die();
        return view('ManagementReport/Pending/get_year_head_nature_wise_ason_rpt', $data);
    
    }
    private function get_case_type()
    {
        $builder = $this->db->table('master.casetype');
        $builder->select('casecode,skey');
        $builder->where('display', 'Y');
        $builder->orderBy('nature', 'ASC');
        $builder->orderBy('skey', 'ASC');
        $query = $builder->get();
        $results = $query->getResultArray();

        $str = '';

        $j = 1;
        $aff = count($results);

        foreach ($results as $r) {
            if ($j == $aff) {
                $str .= " SUM(CASE WHEN (CASE WHEN active_casetype_id = 0 THEN casetype_id ELSE active_casetype_id END) = '" . $r['casecode'] . "' THEN 1 ELSE 0 END) AS " . $r['skey'] . " ";
            } else {
                $str .= " SUM(CASE WHEN (CASE WHEN active_casetype_id = 0 THEN casetype_id ELSE active_casetype_id END) = '" . $r['casecode'] . "' THEN 1 ELSE 0 END) AS " . $r['skey'] . " ,";
            }
            $j++;
        }
        return $str;
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
        $builder = $db->table('subheading');
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
            $connt = "(m.diary_no = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0') AND ";
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
