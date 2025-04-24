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

            $ason_str = " CASE WHEN d.rj_dt IS NOT NULL THEN d.rj_dt >= DATE '$til_dt'
                        WHEN d.disp_dt IS NOT NULL THEN d.disp_dt >= DATE '$til_dt'
                        ELSE TO_DATE(CONCAT( COALESCE(d.year::text, '0000'), '-', LPAD(COALESCE(d.month::text, '01'), 2, '0'), '-01'
                            ), 'YYYY-MM-DD' ) >= DATE '$til_dt' END ";

            $ason_str_res = "IF(disp_rj_dt != '0000-00-00', disp_rj_dt >= '" . $til_dt . "',
                    IF(r.disp_dt IS NOT NULL, r.disp_dt >= '" . $til_dt . "', 
                    CONCAT(r.disp_year::text, '-', LPAD(r.disp_month, 2, 0), '-01') >= '" . $til_dt . "'))";

                    $exclude_cond = "CASE WHEN r.disp_dt IS NOT NULL AND r.conn_next_dt IS NOT NULL 
                            THEN DATE '$til_dt' NOT BETWEEN r.disp_dt AND r.conn_next_dt 
                            ELSE r.disp_dt IS NULL OR r.conn_next_dt IS NULL 
                        END  OR r.fil_no IS NULL ";

                    $exclude_cond_other = "CASE WHEN r.disp_dt IS NOT NULL AND r.conn_next_dt IS NOT NULL
                        THEN DATE '$til_dt' NOT BETWEEN r.disp_dt AND r.conn_next_dt
                        ELSE r.disp_dt IS NULL OR r.conn_next_dt IS NULL END ";
            
        } else if ($this->request->getGet('ason_type') == 'month') {
            $til_dt = $this->request->getGet('lst_year') . "-" . str_pad($this->request->getGet('lst_month'), 2, "0", STR_PAD_LEFT) . "-01";

            $ason_str = " IF(d.rj_dt IS NOT NULL, d.rj_dt >= '" . $til_dt . "', 
                            IF(d.month = 0, d.disp_dt >= '" . $til_dt . "', CONCAT(d.year, '-',LPAD(d.month::text, 2, '0'), '-01') >= '" . $til_dt . "'))";

            $ason_str_res = " IF(r.disp_rj_dt != '0000-00-00', r.disp_rj_dt >= '" . $til_dt . "', 
                            IF(r.disp_month = 0, r.disp_dt >= '" . $til_dt . "', CONCAT(r.disp_year, '-', LPAD(r.disp_month, 2, 0), '-01') >= '" . $til_dt . "'))";

            $exclude_cond = " CASE 
            WHEN r.disp_month != '0' AND r.disp_month IS NOT NULL AND r.month != '0' AND r.month IS NOT NULL 
            THEN '" . $til_dt . "' NOT BETWEEN CONCAT(r.disp_year, '-', LPAD(r.disp_month, 2, '0'), '-01') AND CONCAT(r.year, '-', LPAD(r.month, 2, '0'), '-01') 
            WHEN r.month != '0' AND r.month IS NOT NULL 
            THEN CONCAT(r.year, '-', LPAD(r.month, 2, '0'), '-01') != '" . $til_dt . "'
            ELSE r.disp_month = '0' OR r.`disp_month` IS NULL OR r.month = '0' OR r.month IS NULL END OR r.fil_no IS NULL";

            $exclude_cond_other = "
            CASE 
                WHEN r.disp_month != '0' AND r.disp_month IS NOT NULL 
                     AND r.month != '0' AND r.month IS NOT NULL 
                THEN DATE ? NOT BETWEEN 
                    TO_DATE(CONCAT(r.disp_year, '-', LPAD(r.disp_month, 2, '0'), '-01'), 'YYYY-MM-DD') 
                    AND TO_DATE(CONCAT(r.year, '-', LPAD(r.month, 2, '0'), '-01'), 'YYYY-MM-DD')
                
                WHEN r.month != '0' AND r.month IS NOT NULL 
                THEN TO_DATE(CONCAT(r.year, '-', LPAD(r.month, 2, '0'), '-01'), 'YYYY-MM-DD') != '" . $til_dt . "'
                
                ELSE 
                    r.disp_month = '0' OR r.disp_month IS NULL 
                    OR r.month = '0' OR r.month IS NULL END ";
        

        } else if ($this->request->getGet('ason_type') == 'ent_dt') {
            $til_date = explode("-", $this->request->getGet('til_date'));
            $til_dt = $til_date[2] . "-" . $til_date[1] . "-" . $til_date[0];

            $ason_str = " d.ent_dt >= '" . $til_dt . "'";

            $ason_str_res = " r.disp_ent_dt >= '" . $til_dt . "'";

            $exclude_cond = " CASE WHEN  r.`entry_date` IS NOT NULL 
                        AND DATE(r.disp_ent_dt) != '0000-00-00' AND r.disp_ent_dt IS NOT NULL
            THEN '" . $til_dt . "' NOT BETWEEN DATE(r.disp_ent_dt) AND `entry_date` 
            ELSE DATE(r.`disp_ent_dt`) = '0000-00-00' OR r.`disp_ent_dt` IS NULL OR DATE(r.entry_date) = '0000-00-00' OR r.entry_date IS NULL END 
            OR r.fil_no IS NULL";

            $exclude_cond_other = " CASE WHEN r.entry_date IS NOT NULL 
             AND DATE(r.disp_ent_dt) != '0000-00-00' AND r.disp_ent_dt IS NOT NULL
                        THEN DATE '$til_dt' NOT BETWEEN DATE(r.disp_ent_dt) AND r.entry_date
                        ELSE 
                            DATE(r.disp_ent_dt) = '0000-00-00' 
                            OR r.disp_ent_dt IS NULL 
                            OR DATE(r.entry_date) = '0000-00-00' 
                            OR r.entry_date IS NULL 
                    END ";
        }

        if ($this->request->getGet('rpt_purpose') == 'sw') {
            $subhead_name = "subhead_n";
            $mainhead_name = "mainhead_n";
        } else {
            $subhead_name = "subhead";
            $mainhead_name = "mainhead";
        }

        if ($this->request->getGet('subhead') == 'all,' || $this->request->getGet('subhead') == '') {
            $subhead = '';
            $subhead_if_last_heardt = " ";
            $subhead_condition = " ";
            $head_subhead = " ";
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
                $subhead_condition = $subhead_if_heardt_con;
                $head_subhead = $this->stagename(substr($this->request->getGet('subhead'), 0, -1));
            }
        }
        $mf_f2_table = "";
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
                              LEFT JOIN master.submaster s ON mc.submaster_id = s.id";
        } else {
            $mul_cat_join = "";
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
                            $str_all_cat = "(s.subcode1 = '" . $head[0] . "' AND s.subcode2 = '" . $head[1] . "')";
                        } else {
                            $str_all_cat = "((s.subcode1 = '" . $head[0] . "' AND s.subcode2 = '" . $head[1] . "') OR " . $str_all_cat . ")";
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
                        $str_all_cat = "(s.subcode1 = '" . $head[0] . "' AND s.subcode2 = '" . $head[1] . "' AND s.subcode3 = '" . $head[2] . "')";
                    } else {
                        $str_all_cat = "((s.subcode1 = '" . $head[0] . "' AND s.subcode2 = '" . $head[1] . "' AND s.subcode3 = '" . $head[2] . "') OR " . $str_all_cat . ")";
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
                    $str_all_cat = "(s.subcode1 = '" . $head[0] . "' AND s.subcode2 = '" . $head[1] . "' AND s.subcode3 = '" . $head[2] . "' AND s.subcode4 = '" . $head[3] . "')";
                } else {
                    $str_all_cat = "((s.subcode1 = '" . $head[0] . "' AND s.subcode2 = '" . $head[1] . "' AND s.subcode3 = '" . $head[2] . "' AND s.subcode4 = '" . $head[3] . "') OR " . $str_all_cat . ")";
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
            $cat_and_act = "( " . $all_category . " " . $all_act . " )";
        }
        if ($this->request->getGet('from_year') == '' || $this->request->getGet('to_year') == '') {
            if ($this->request->getGet('from_year') == '' && $this->request->getGet('to_year') != '') {
                $year_main = " AND SUBSTR(m.diary_no::text, -4) <= '" . $this->request->getGet('to_year') . "' ";
                $year_lastheardt = " AND SUBSTR(m.diary_no::text, -4) <= '" . $this->request->getGet('to_year') . "' ";
            } elseif ($this->request->getGet('from_year') != '' && $this->request->getGet('to_year') == '') {
                $year_main = " AND SUBSTR(m.diary_no::text, -4) >= '" . $this->request->getGet('from_year') . "' ";
                $year_lastheardt = " AND SUBSTR(m.diary_no::text, -4) >= '" . $this->request->getGet('from_year') . "' ";
            } else {
                $year_main = " ";
                $year_lastheardt = " ";
            }
        } else {
            $year_main = "SUBSTR(m.diary_no::text, -4) BETWEEN '" . $this->request->getGet('from_year') . "' AND '" . $this->request->getGet('to_year') . "' ";
            $year_lastheardt = "SUBSTR(m.diary_no::text, -4) BETWEEN '" . $this->request->getGet('from_year') . "' AND '" . $this->request->getGet('to_year') . "' ";
        }

        $Brep = "";
        $Brep1 = "";
        $act_join = '';
        $registration = '';
        $main_connected = '';
        $pc_act = $women = $children = $land = $cr_compound = $commercial_code = $party_name = $pet_res = $act_msc = '';
        $from_fil_dt = $this->request->getGet('from_fil_dt') ?
            " DATE(m.diary_no_rec_date) > '" . date('Y-m-d', strtotime($this->request->getGet('from_fil_dt'))) . "' " : " ";

        $upto_fil_dt = $this->request->getGet('upto_fil_dt') ?
            " DATE(m.diary_no_rec_date) < '" . date('Y-m-d ', strtotime($this->request->getGet('upto_fil_dt'))) . "' " : " ";
       
        $case_status_id = " ";
        if ($this->request->getGet('case_status_id') == 'all,') {
            $case_status_id = " AND case_status_id IN (1, 2, 3, 6, 7, 9) ";
        } elseif ($this->request->getGet('case_status_id') == '103,' || $this->request->getGet('case_status_id') == 103) {
            $registration = " ";
        } elseif ($this->request->getGet('case_status_id') == 101 || $this->request->getGet('case_status_id') == '101,') {
            $registration = " (active_fil_no = '' OR active_fil_no IS NULL) ";
            
        } elseif ($this->request->getGet('case_status_id') == 102 || $this->request->getGet('case_status_id') == '102,') {
            $registration = " NOT (active_fil_no = '' OR active_fil_no IS NULL) ";
          
        } elseif ($this->request->getGet('case_status_id') == 104 || $this->request->getGet('case_status_id') == '104,') {
          
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
        } elseif ($this->request->getGet('case_status_id') == 105 || $this->request->getGet('case_status_id') == '105,') {
            
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
        } elseif ($this->request->getGet('case_status_id') == 106 || $this->request->getGet('case_status_id') == '106,') {
            
            $Brep = " LEFT OUTER JOIN (SELECT DISTINCT diary_no FROM obj_save WHERE rm_dt IS NULL AND display = 'Y')
                                os ON m.diary_no=os.diary_no
                                ";
            $Brep1 = " and os.diary_no IS NOT NULL and c_status = 'P' AND (active_fil_no IS NULL OR  active_fil_no='') AND h.board_type='J'";
        } elseif ($this->request->getGet('case_status_id') == 107 || $this->request->getGet('case_status_id') == '107,') {
            $Brep = " INNER JOIN docdetails b ON m.diary_no=b.diary_no
            INNER JOIN
            (SELECT DISTINCT diary_no FROM obj_save WHERE rm_dt IS NULL AND display = 'Y' AND DATEDIFF(NOW(),save_dt)>60) os
            ON m.diary_no=os.diary_no ";
            $Brep1 = " and m.c_status = 'P' AND (m.active_fil_no IS NULL OR  m.active_fil_no='')
            AND doccode = '8' AND doccode1 = '226' AND b.iastat='P' ";
        } elseif ($this->request->getGet('case_status_id') == 108 || $this->request->getGet('case_status_id') == '108,') {
            $Brep = " INNER JOIN docdetails b ON m.diary_no=b.diary_no
            INNER JOIN
            (SELECT DISTINCT diary_no FROM obj_save WHERE rm_dt IS NULL AND display = 'Y' AND DATEDIFF(NOW(),save_dt)<=60) os
            ON m.diary_no=os.diary_no ";
            $Brep1 = " and  m.c_status = 'P' AND (m.active_fil_no IS NULL OR  m.active_fil_no='')
            AND doccode = '8' AND doccode1 = '226' AND b.iastat='P' ";
        } elseif ($this->request->getGet('case_status_id') == 109 || $this->request->getGet('case_status_id') == '109,') {
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
        else {
            $case_status_id = " and case_status_id in (" . substr($this->request->getGet('case_status_id'), 0, -1) . ")";
        }
      

        if ($this->request->getGet('mf') != 'ALL') {
            if ($this->request->getGet('til_date') != date('d-m-Y')) {
                // echo '<br>';
                $t = "CREATE TEMPORARY TABLE vw2 AS 
                        SELECT DISTINCT ON (diary_no) diary_no, ent_dt AS med, subhead_n, mainhead_n
                        FROM last_heardt WHERE DATE(ent_dt) < '" . $til_dt . "' " . $year_lastheardt . "
                        ORDER BY diary_no, ent_dt DESC";
                $this->db->query($t);
        

                $t2 = "CREATE INDEX id_index ON vw2 (diary_no)";
                $this->db->query($t2);

                $t3 = "CREATE TEMPORARY TABLE vw3 AS
                        SELECT l.diary_no, l." . $subhead_name . ", l.judges, med, next_dt, l." . $mainhead_name . "
                        FROM vw2 
                        INNER JOIN last_heardt l ON vw2.diary_no = l.diary_no
                        AND l.ent_dt = med
                        AND l." . $mainhead_name . " = '" . $this->request->getGet('mf') . "' " . $subhead;
                        $this->db->query($t3);

                $t4 = "CREATE INDEX id_index2 ON vw3 (diary_no)";
                $this->db->query($t4);
            }
        }
        
        if ($this->request->getGet('mf') != 'ALL') {

            if ($this->request->getGet('til_date') != date('d-m-Y'))
             {

                $builder = $this->db->table('main m');
                $builder->join('heardt h', 'm.diary_no = h.diary_no', 'left');
                $builder->join('dispose d', 'm.diary_no = d.diary_no', 'left');
                $builder->join('restored r', 'm.diary_no = r.diary_no', 'left');
                $builder->join('vw3 f2', 'm.diary_no = f2.diary_no', 'left');
                $builder->join('act_main a', 'a.diary_no = m.diary_no', 'left');
                $builder->where('1=1');
                if (!empty($mul_cat_join)) {
                    $builder->join('mul_category mc', 'mc.diary_no = h.diary_no', 'left');
                    $builder->join('master.submaster s', 'mc.submaster_id = s.id', 'left');
                }
                $builder->where("IF(med > h.ent_dt AND f2.$mainhead_name IS NOT NULL,$mf_f2_table $subhead_if_last_heardt,$mf_h_table $subhead_if_last_heardt)", null, false);
                if (!empty($exclude_cond) && $exclude_cond != ' ') $builder->where("($exclude_cond)", null, false);
                $builder->where('DATE(m.diary_no_rec_date) <', $til_dt);
                $builder->where('c_status', 'P');

                $builder->orGroupStart()
                        ->where('c_status', 'D')
        ->where("IF(med > h.ent_dt AND f2.$mainhead_name IS NOT NULL,$mf_f2_table $subhead_if_last_heardt,$mf_h_table $subhead_if_last_heardt)", null, false);
        if (!empty($ason_str) && trim($ason_str) !== '') $builder->where($ason_str);
        $builder->where('DATE(m.diary_no_rec_date) <', $til_dt);
                        if (!empty($exclude_cond_other) && trim($exclude_cond_other) !== '') $builder->where($exclude_cond_other, null, false);
                        if (!empty($cat_and_act) && trim($cat_and_act) !== '') $builder->where($cat_and_act);
                        if (!empty($year_main) && trim($year_main) !== '') $builder->where($year_main);
                        if (!empty($from_fil_dt) && trim($from_fil_dt) !== '') $builder->where($from_fil_dt);
                        if (!empty($upto_fil_dt) && trim($upto_fil_dt) !== '') $builder->where($upto_fil_dt);
                        if (!empty($bench) && trim($bench) !== '') $builder->where($bench);
                        if (!empty($pc_act) && trim($pc_act) !== '') $builder->where($pc_act);
                        if (!empty($women) && trim($women) !== '') $builder->where($women);
                        if (!empty($children) && trim($children) !== '') $builder->where($children);
                        if (!empty($land) && trim($land) !== '') $builder->where($land);
                        if (!empty($cr_compound) && trim($cr_compound) !== '') $builder->where($cr_compound);
                        if (!empty($commercial_code) && trim($commercial_code) !== '') $builder->where($commercial_code);
                        if (!empty($party_name) && trim($party_name) !== '') $builder->where($party_name);
                        if (!empty($pet_res) && trim($pet_res) !== '') $builder->where($pet_res);
                        if (!empty($act_msc) && trim($act_msc) !== '') $builder->where($act_msc);
                        if (!empty($registration) && trim($registration) !== '') $builder->where($registration);
                
                $builder->groupEnd()

            ->orGroupStart()
                ->where($ason_str_res)
                ->where("IF(med > h.ent_dt AND f2.$mainhead_name IS NOT NULL,$mf_f2_table $subhead_if_last_heardt,$mf_h_table $subhead_if_last_heardt)", null, false)
                ->where('DATE(m.diary_no_rec_date) <', $til_dt);
                if (!empty($exclude_cond_other) && trim($exclude_cond_other) !== '') $builder->where($exclude_cond_other,null,false);
                if (!empty($year_main) && trim($year_main) !== '') $builder->where($year_main);
                if (!empty($from_fil_dt) && trim($from_fil_dt) !== '') $builder->where($from_fil_dt);
                if (!empty($upto_fil_dt) && trim($upto_fil_dt) !== '') $builder->where($upto_fil_dt);
                if (!empty($cat_and_act) && trim($cat_and_act) !== '') $builder->where($cat_and_act);
                if (!empty($bench) && trim($bench) !== '') $builder->where($bench);
                if (!empty($pc_act) && trim($pc_act) !== '') $builder->where($pc_act);
                if (!empty($women) && trim($women) !== '') $builder->where($women);
                if (!empty($children) && trim($children) !== '') $builder->where($children);
                if (!empty($land) && trim($land) !== '') $builder->where($land);
                if (!empty($cr_compound) && trim($cr_compound) !== '') $builder->where($cr_compound);
                if (!empty($commercial_code) && trim($commercial_code) !== '') $builder->where($commercial_code);
                if (!empty($party_name) && trim($party_name) !== '') $builder->where($party_name);
                if (!empty($pet_res) && trim($pet_res) !== '') $builder->where($pet_res);
                if (!empty($act_msc) && trim($act_msc) !== '') $builder->where($act_msc);
                $builder->groupEnd();

            if (!empty($registration) && $registration != ' ') $builder->where($registration);
            if (!empty($subhead_condition) && $subhead_condition != ' ') $builder->where($subhead_condition);
            if (!empty($case_status_id) && $case_status_id != ' ') $builder->where($case_status_id);
            if (!empty($Brep1) && $Brep1 != ' ') $builder->where($Brep1);

            $builder->select(['m.diary_no','m.fil_dt','c_status','d.rj_dt','d.month','d.year','d.disp_dt','active_casetype_id','casetype_id']);
            $builder->groupBy(['m.diary_no','m.fil_dt','c_status','d.rj_dt','d.month','d.year','d.disp_dt','active_casetype_id','casetype_id']);
            $subQuery = $builder->getCompiledSelect();
            $sql = "SELECT SUBSTR(diary_no::text, -4) AS year, " . $str . " FROM ( $subQuery ) t GROUP BY ROLLUP(SUBSTR(diary_no::text, -4))";

            } else {
                
                $builder = $this->db->table('main m');
                $builder->join('dispose d', 'm.diary_no = d.diary_no', 'left');
                $builder->join('heardt h', 'm.diary_no = h.diary_no', 'left');
                $builder->join('restored r', 'm.diary_no = r.diary_no', 'left');
                $builder->join('act_main a', 'a.diary_no = m.diary_no', 'left');
                if (!empty($mul_cat_join)) {
                    $builder->join('mul_category mc', 'mc.diary_no = h.diary_no', 'left');
                    $builder->join('master.submaster s', 'mc.submaster_id = s.id', 'left');
                }
                                
                if (!empty($act_join)) 
                $builder->join($act_join, 'left');   

                $builder->whereIn('case_status_id', [1, 2, 3, 6, 7, 9]);
                $builder->where("(c_status = 'P' AND DATE(m.diary_no_rec_date) < '$til_dt')");
                
                if (!empty($registration)  && $registration != ' ') $builder->where($registration);
                if (!empty($mf_h_table) && $mf_h_table != ' ') $builder->where($mf_h_table);
                if (!empty($cat_and_act) && $cat_and_act != ' ') $builder->where($cat_and_act);
                if (!empty($year_main) && $year_main != ' ') $builder->where($year_main);
                if (!empty($from_fil_dt) && $from_fil_dt != ' ') $builder->where($from_fil_dt);
                if (!empty($upto_fil_dt) && $upto_fil_dt != ' ') $builder->where($upto_fil_dt);
                if (!empty($case_status_id) && $case_status_id != ' ') $builder->where($case_status_id);
                if (!empty($Brep1) && $Brep1 != '') $builder->where($Brep1);
                if (!empty($subhead_condition) && $subhead_condition != ' ') $builder->where($subhead_condition);
                
                $builder->select(['m.diary_no','m.fil_dt','c_status','d.rj_dt','d.month','d.year','d.disp_dt','active_casetype_id','casetype_id']);
                $builder->groupBy(['m.diary_no','m.fil_dt','c_status','d.rj_dt','d.month','d.year','d.disp_dt','active_casetype_id','casetype_id']);
                // $builder->limit(100);
                $subQuery = $builder->getCompiledSelect();
                $sql = "SELECT SUBSTR(diary_no::text, -4) AS year, " . $str . " FROM ( $subQuery ) t GROUP BY ROLLUP(SUBSTR(diary_no::text, -4))";
            }
        } else {
            if ($this->request->getGet('til_date') != date('d-m-Y')) 
            {
                $builder = $this->db->table('main m');
                $builder->join('heardt h', 'm.diary_no = h.diary_no', 'left');
                $builder->join('dispose d', 'm.diary_no = d.diary_no', 'left');
                $builder->join('restored r', 'm.diary_no = r.diary_no', 'left');
                $builder->join('act_main a', 'a.diary_no = m.diary_no', 'left');
                $builder->where("1=1");
                if (!empty($mul_cat_join)) 
                $builder->join($mul_cat_join, 'left'); 
                
                if (!empty($act_join)) 
                $builder->join($act_join, 'left');                 

                $builder->groupStart();
                if (!empty($exclude_cond)) { $builder->where($exclude_cond,null,false); }
                $builder->groupEnd();  
                $builder->where("DATE(m.diary_no_rec_date) <", $til_dt)->where("c_status", 'P');

            $builder->orGroupStart(); if (!empty($registration)  && $registration != ' ') $builder->where($registration);
            if (!empty($mf_h_table) && $mf_h_table != ' ') $builder->where($mf_h_table);
            if (!empty($cat_and_act) && $cat_and_act != ' ') $builder->where($cat_and_act);
            if (!empty($year_main) && $year_main != ' ') $builder->where($year_main);
            if (!empty($from_fil_dt) && $from_fil_dt != ' ') $builder->where($from_fil_dt);
            if (!empty($upto_fil_dt) && $upto_fil_dt != ' ') $builder->where($upto_fil_dt);
            if (!empty($case_status_id) && $case_status_id != ' ') $builder->where($case_status_id);
            if (!empty($Brep1) && $Brep1 != '') $builder->where($Brep1);
            if (!empty($subhead_condition) && $subhead_condition != ' ') $builder->where($subhead_condition);
                $builder->where("c_status", 'D');
                $builder->where("DATE(m.diary_no_rec_date) <", $til_dt);
                if (!empty($ason_str)) $builder->where($ason_str);
                if (!empty($cat_and_act) && $cat_and_act != ' ') $builder->where($cat_and_act);
                if (!empty($year_main) && $year_main != ' ') $builder->where($year_main);
                if (!empty($from_fil_dt) && $from_fil_dt != ' ') $builder->where($from_fil_dt);
                if (!empty($upto_fil_dt) && $upto_fil_dt != ' ') $builder->where($upto_fil_dt);
                if (!empty($exclude_cond_other) && $exclude_cond_other != ' ') $builder->where($exclude_cond_other,null,false );
                if (!empty($main_connected) && $main_connected != ' ') $builder->where($main_connected);
            $builder->groupEnd(); 

            
            if (!empty($Brep1) && $Brep1 != '') $builder->where($Brep1);
            if (!empty($registration) && $registration != ' ') $builder->where($registration);
            if (!empty($cat_and_act) && $cat_and_act != ' ') $builder->where($cat_and_act);
            if (!empty($year_main) && $year_main != ' ') $builder->where($year_main);
            if (!empty($from_fil_dt) && $from_fil_dt != ' ') $builder->where($from_fil_dt);
            if (!empty($upto_fil_dt) && $upto_fil_dt != ' ') $builder->where($upto_fil_dt);
            if (!empty($main_connected) && $main_connected != ' ') $builder->where($main_connected);
            if (!empty($case_status_id) && $case_status_id != ' ') $builder->where($case_status_id);
            // $builder->limit(100);$pet_res

            $builder->select(['m.diary_no','m.fil_dt','c_status','d.rj_dt','d.month','d.year','d.disp_dt','active_casetype_id','casetype_id']);
            $builder->groupBy(['m.diary_no','m.fil_dt','c_status','d.rj_dt','d.month','d.year','d.disp_dt','active_casetype_id','casetype_id']);
            $subQuery = $builder->getCompiledSelect();

            $sql = "SELECT SUBSTR(diary_no::text, -4) AS year, " . $str . " FROM ( $subQuery ) t GROUP BY ROLLUP(SUBSTR(diary_no::text, -4))";

            } else {
                
                $builder = $this->db->table('main m');
                $builder->join('dispose d', 'm.diary_no = d.diary_no', 'left');
                $builder->join('restored r', 'm.diary_no = r.diary_no', 'left');
                $builder->join('heardt h', 'm.diary_no = h.diary_no', 'left');
                $builder->join('act_main a', 'a.diary_no = m.diary_no', 'left');

                if (!empty($mul_cat_join)) {
                    
                    $builder->join($mul_cat_join, 'left');
                }
                if (!empty($act_join)) {
                    $builder->join($act_join, 'left');
                }
                $builder->where('c_status', 'P');
                $builder->where("DATE(m.diary_no_rec_date) <= ", $til_dt);
                
                if (!empty($Brep1) && $Brep1 != '') $builder->where($Brep1);
                if (!empty($registration) && $registration != ' ') $builder->where($registration);
                if (!empty($bench) && $bench != ' ') $builder->where($bench);
                if (!empty($cat_and_act) && $cat_and_act != ' ') $builder->where($cat_and_act);
                if (!empty($year_main) && $year_main != ' ') $builder->where($year_main);
                if (!empty($from_fil_dt) && $from_fil_dt != ' ') $builder->where($from_fil_dt);
                if (!empty($upto_fil_dt) && $upto_fil_dt != ' ') $builder->where($upto_fil_dt);
                if (!empty($case_status_id) && $case_status_id != ' ') $builder->where($case_status_id);

                $builder->select(['m.diary_no','m.fil_dt','c_status','d.rj_dt','d.month','d.year','d.disp_dt','active_casetype_id','casetype_id']);
                $builder->groupBy(['m.diary_no','m.fil_dt','c_status','d.rj_dt','d.month','d.year','d.disp_dt','active_casetype_id','casetype_id']);
                // $builder->limit(100);
                $subQuery = $builder->getCompiledSelect();

                $sql = "SELECT SUBSTR(diary_no::text, -4) AS year, " . $str . " FROM ( $subQuery ) t GROUP BY ROLLUP(SUBSTR(diary_no::text, -4)) ";
                }
            }        
        // pr($sql);die;
        $query = $this->db->query($sql);
        $data['results'] = $results = $query->getResultArray();
        $data['tot_row'] = count($results);
        $data['civil_colspan'] = $this->tot_case_in_nature('C');
        $data['cr_colspan'] = $this->tot_case_in_nature('R');
        $data['til_dt']   = $til_dt;
        $data['head_subhead'] = $head_subhead;
        $data['rpt_type'] = $this->request->getGet('rpt_type');
        $data['db'] = \Config\Database::connect();
        return view('ManagementReport/Pending/get_year_head_nature_wise_ason_rpt', $data);
    }


    public function show_case_for_ason()
    {
        $act_join =  $add_table = '';
        $nature_wise_to = $this->request->getGet('nature_wise_tot');
        $year_wise_tot = $this->request->getGet('year_wise_tot');

        $subject = $this->request->getGet('subject');
        $subject_length = $this->request->getGet('subject_length');
        $cat = $this->request->getGet('cat');
        $cat_length = $this->request->getGet('cat_length');
        $subcat = $this->request->getGet('subcat');
        $subcat_length = $this->request->getGet('subcat_length');
        $year = $this->request->getGet('year');
        $skey = $this->request->getGet('skey');
        $subhead = $this->request->getGet('subhead');
        $mf = $this->request->getGet('mf');
        $til_date = $this->request->getGet('til_date');
        $from_year = $this->request->getGet('from_year');
        $to_year = $this->request->getGet('to_year');
        $rpt_type = $this->request->getGet('rpt_type');
        // $pet_res = $this->request->getGet('pet_res');
        $pet_res ='';
        $party_name = $this->request->getGet('party_name');
        $act_msc = $this->request->getGet('act_msc');
        $lst_month = $this->request->getGet('lst_month');
        $lst_year = $this->request->getGet('lst_year');
        $ason_type = $this->request->getGet('ason_type');
        $from_fil_dt = $this->request->getGet('from_fil_dt');
        $upto_fil_dt = $this->request->getGet('upto_fil_dt');
        $rpt_purpose = $this->request->getGet('rpt_purpose');
        $spl_case = $this->request->getGet('spl_case');
        $concept = $this->request->getGet('concept');
        $main_connected = $this->request->getGet('main_connected');
        $act = $this->request->getGet('act');
        $order_by = $this->request->getGet('order_by');
        $adv_opt = $this->request->getGet('adv_opt');
      
        $case_status_id = $this->request->getGet('case_status_id');
        $subcat2 = $this->request->getGet('subcat2');
        $subcat2_length = $this->request->getGet('subcat2_length');


        if ($rpt_type == 'year') {
            if ($nature_wise_to == 'y' || $year_wise_tot == 'all') {
                $year_condition = " ";
                $year_condition_last_heardt = " ";
            } else {
                $year_condition = " and substr(m.diary_no::text,-4)='" . $year . "' ";
                $year_condition_last_heardt = " and SUBSTR(m.diary_no::text,-4)='" . $year . "' ";
            }
        } else {
            $year_condition = " ";
            $year_condition_last_heardt = " ";
        }


        if ($mf == 'all')
            $mf = '';
        else if ($mf == 'N')
            $mf = " and mainhead not in ('M','F')";
        else
            $mf = " and  mainhead ='" . $mf . "'";
        $head_bench = '';
        if ($this->request->getGet('bench') == 'S')      $head_bench = ' Single bench ';
        elseif ($this->request->getGet('bench') == 'D')  $head_bench = ' Divisional bench ';
        else if ($this->request->getGet('bench') == 'F') $head_bench = ' Full bench ';
        else if ($this->request->getGet('bench') == 'all') $head_bench = ' All benches ';
        else if ($this->request->getGet('bench') == 'B') $head_bench = ' Bench not in (SB, DB, FB) ';

        if ($mf == 'M')      $head_mf = ' Motion Hearing ';
        elseif ($mf == 'F')  $head_mf = ' Final Hearing ';
        else if ($mf == 'all') $head_mf = ' All Hearing ';
        else if ($mf == 'N') $head_mf = ' Mainhead not in (Motion ,Final) ';

        $til_date = explode("-", $til_date);
        $til_dt = $til_date[2] . "-" . $til_date[1] . "-" . $til_date[0];

        $subhead_name = ($rpt_purpose == 'sw') ? "subhead_n" : "subhead";
        $mainhead_name = ($rpt_purpose == 'sw') ? "mainhead_n" : "mainhead";
        
        

        if ($subhead == 'all,' || $subhead == '') {
            $subhead = '';
            $subhead_if_heardt = " ";
            $subhead_if_last_heardt = " ";
            $subhead_condition = " ";
            $head_subhead = ' ';
        } else {
            $subhead = "  and l." . $subhead_name . " in (" . substr($this->request->getGet('subhead'), 0, -1) . ")";
            $subhead_if_heardt = " and h." . $subhead_name . " in (" . substr($this->request->getGet('subhead'), 0, -1) . ") ";
            $subhead_if_last_heardt = " and f2." . $subhead_name . " in (" . substr($this->request->getGet('subhead'), 0, -1) . ") ";
          
    
            $subhead_if_heardt_con = "  h." . $subhead_name . " in (" . substr($this->request->getGet('subhead'), 0, -1) . ") ";
            $subhead_if_last_heardt_con = "  f2." . $subhead_name . " in (" . substr($this->request->getGet('subhead'), 0, -1) . ") ";
            if ($til_date != date('d-m-Y')) 
            {
                // $subhead_condition=" AND if(date(h.ent_dt)<'".$til_dt."' and date(h.ent_dt)>med,". $subhead_if_heardt_con.",". $subhead_if_last_heardt_con."  )";
                $head_subhead=$this->stagename(substr($this->request->getGet('subhead'),0,-1));
                $subhead_condition ="";

            } else {
                $subhead_condition = "  AND " . $subhead_if_heardt_con;
                $head_subhead = $this->stagename(substr($this->request->getGet('subhead'), 0, -1));
            }
        }

        
if(trim($_GET['subcat2'])=='all,')
{ 
 if(trim($_GET['subcat'])=='all,')
   { 
		if(trim($_GET['cat'])=='all,')
		{  
		  if(trim($_GET['subject'])=='all,')
			{
			$all_category=" ";
			}
			else
			$all_category="  s.subcode1 in (".substr($_GET['subject'],0,-1).")";
		}
		else
		  {
			  $head1=explode(',' ,$_GET['cat']);
			  for($m=0;$m<$_GET['cat_length'];$m++) 
				 {
				  $head=explode('|' ,$head1[$m]);
				   if( $m==0)
					$str_all_cat="  (s.subcode1 ='".$head[0]."' and s.subcode2='".$head[1]."')";
				   else
				   $str_all_cat=" (( s.subcode1 ='".$head[0]."' and s.subcode2='".$head[1]."') OR ".$str_all_cat.")";
		  }  
		  $all_category=$str_all_cat;
	}
}
		else
		{
				  $head1=explode(',' ,$_GET['subcat']);
				  for($m=0;$m<$_GET['subcat_length'];$m++) 
					 {
					  $head=explode('|' ,$head1[$m]);
					
					 if( $m==0)
						$str_all_cat="  (s.subcode1 ='".$head[0]."' and s.subcode2='".$head[1]."' and s.subcode3='".$head[2]."')";
					else
					 $str_all_cat=" (( s.subcode1 ='".$head[0]."' and s.subcode2='".$head[1]."' and s.subcode3='".$head[2]."') OR ".$str_all_cat.")";
					}  
					  
				$all_category=$str_all_cat;
		}  
}
else
{
           $head1=explode(',' ,$_GET['subcat2']);
				  for($m=0;$m<$_GET['subcat2_length'];$m++) 
					 {
					  $head=explode('|' ,$head1[$m]);
					
					 if( $m==0)
						$str_all_cat="  (s.subcode1 ='".$head[0]."' and s.subcode2='".$head[1]."' and s.subcode3='".$head[2]."' and s.subcode4='".$head[3]."')";
					else
					 $str_all_cat=" (( s.subcode1 ='".$head[0]."' and s.subcode2='".$head[1]."' and s.subcode3='".$head[2]."' and s.subcode4='".$head[3]."') OR ".$str_all_cat.")";
					}  
					  
				$all_category=$str_all_cat;
}


$all_act = " ";
        // if (trim($act) == 'all,') {
        // } else {
        //     if (trim($subject) == 'all,')
        //         $all_act = " a.act in (" . substr($act, 0, -1) . ")";
        //     else
        //         $all_act = " or a.act in (" . substr($act, 0, -1) . ")";
        // }

        if (trim($act) == 'all,' && trim($subject) == 'all,')
            $cat_and_act = " ";
        else
            $cat_and_act = " and ( " . $all_category . " " . $all_act . " )";


        if (trim($subject) != 'all,' || trim($act) != 'all,' || trim($act_msc) != '') {
            $mul_cat_join = " LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no 
                              LEFT JOIN master.submaster s ON mc.submaster_id = s.id";
            $cat_field = "";
        } else {
            $mul_cat_join = " ";
            $cat_field = " ";
        }
       

        if (empty($from_year) || empty($to_year)) {
            if (empty($from_year) && !empty($to_year)) {
                $year_main = " substring( m.diary_no::text,-4 ) <= '" . $to_year . "'   ";
                $year_lastheardt = " AND substring( l.diary_no::text,-4 ) <= '" . $to_year . "' ";
            } elseif (!empty($from_year) && empty($to_year)) {
                $year_main = "  substring( m.diary_no::text,-4 ) >= '" . $from_year . "' ";
                $year_lastheardt = " AND substring( l.diary_no::text,-4 ) >= '" . $from_year . "' ";
            } else {
                $year_main = " ";
                $year_lastheardt = " ";
            }
        } else {
            $year_main = " substring( m.diary_no::text,-4 ) BETWEEN '" . $from_year . "' AND '" . $to_year . "' ";
            $year_lastheardt = " AND substring( l.diary_no::text,-4 ) BETWEEN '" . $from_year . "' AND '" . $to_year . "' ";
        }
       

        if (empty($from_fil_dt)) {
            $from_fil_dt_condition = " ";
        } else {
            $from_fil_date = date('Y-m-d', strtotime($from_fil_dt));
            $from_fil_dt_condition = " AND date( m.diary_no_rec_date) >'" . $from_fil_date . "' ";
        }

        if (empty($upto_fil_dt)) {
            $upto_fil_dt_condition = " ";
        } else {
            $upto_fil_date = date('Y-m-d', strtotime($upto_fil_dt));
            $upto_fil_dt_condition = " AND date( m.diary_no_rec_date) <'" . $upto_fil_date . "' ";
        }

        if (trim($party_name) == '') {
            $join_party = " ";
            $party_name_condition = "  ";
        } else {
            $join_party = " LEFT JOIN party p ON m.fil_no = p.fil_no ";
            $party_name_condition = " and (partyname like'%HIGH%COURT%'   OR  partyname like'%registrar%gen%'   )  ";
        }
        if ($_GET['act_msc'] == '')
            $act_msc = '';
        else
            $act_msc = " and (a.section  like '%" . $_GET['act_msc'] . "%' OR m.act  like '%" . $_GET['act_msc'] . "%'  or usec1  like '%" . $_GET['act_msc'] . "%'  OR usec2  like '%" . $_GET['act_msc'] . "%' OR desc1  like '%" . $_GET['act_msc'] . "%' ) ";


        if ($this->request->getGet('ason_type') == 'dt') {
            $til_date = explode("-", $this->request->getGet('til_date'));
            $til_dt = $til_date[2] . "-" . $til_date[1] . "-" . $til_date[0];

            $ason_str = " IF(d.rj_dt IS NOT NULL ,d.rj_dt >= '" . $til_dt . "',
                        IF(d.disp_dt IS NOT NULL ,d.disp_dt >='" . $til_dt . "', concat(d.year,'-',lpad(d.month,2,0),'-01') >= '" . $til_dt . "'	 )    )  ";

            $ason_str_res = " IF(disp_rj_dt != '0000-00-00',disp_rj_dt >= '" . $til_dt . "',
                        IF( r.disp_dt != '0000-00-00' AND r.disp_dt IS NOT NULL ,r.disp_dt >='" . $til_dt . "', concat(r.disp_year,'-',lpad(r.disp_month,2,0),'-01') >= '" . $til_dt . "'	 )    )  ";

            $exclude_cond = " CASE WHEN r.disp_dt IS NOT NULL 
                        AND r.conn_next_dt IS NOT NULL
                THEN '" . $til_dt . "' NOT BETWEEN r.disp_dt AND conn_next_dt
                ELSE r.disp_dt IS NULL OR r.conn_next_dt IS NULL 
                END 
            OR r.fil_no IS NULL	";

            $exclude_cond_other = " CASE WHEN r.disp_dt IS NOT NULL 
                        AND r.conn_next_dt IS NOT NULL
                THEN '" . $til_dt . "' NOT BETWEEN r.disp_dt AND conn_next_dt
                ELSE r.disp_dt IS NULL OR r.conn_next_dt IS NULL 
                END 
                ";
        } else
            if ($ason_type == 'month') {
            $til_dt = $lst_year . "-" . str_pad($lst_month, 2, "0", STR_PAD_LEFT) . "-01";

            $ason_str = " IF(d.rj_dt IS NOT NULL,d.rj_dt >= '" . $til_dt . "', 
                            IF(d.month =0,d.disp_dt >='" . $til_dt . "', concat(d.year,'-',lpad(d.month,2,0),'-01' ) >= '" . $til_dt . "' 
                            ) 
                        ) ";

            $ason_str_res = " IF(r.disp_rj_dt != '0000-00-00',r.disp_rj_dt >= '" . $til_dt . "', 
                            IF(r.disp_month =0,r.disp_dt >='" . $til_dt . "', concat(r.disp_year,'-',lpad(r.disp_month,2,0),'-01' ) >= '" . $til_dt . "' 
                            ) 
                        ) ";

            $exclude_cond = " CASE 
            WHEN r.disp_month != '0' AND r.disp_month IS NOT NULL AND r.month != '0' AND r.month IS NOT NULL 
            THEN '" . $til_dt . "' NOT BETWEEN concat(r.disp_year,'-',lpad(r.disp_month,2,'0'),'-01') AND concat(r.year,'-',lpad(r.month,2,'0'),'-01') 
            WHEN  r.month != '0' AND r.month IS NOT NULL 
            THEN concat(r.year,'-',lpad(r.month,2,'0'),'-01')!='" . $til_dt . "'
            ELSE r.disp_month = '0' OR r.`disp_month` IS NULL OR r.month = '0' OR r.month IS NULL END OR r.fil_no IS NULL 	";

            $exclude_cond_other = " CASE 
            WHEN r.disp_month != '0' AND r.disp_month IS NOT NULL AND r.month != '0' AND r.month IS NOT NULL 
            THEN '" . $til_dt . "' NOT BETWEEN concat(r.disp_year,'-',lpad(r.disp_month,2,'0'),'-01') 
            AND concat(r.year,'-',lpad(r.month,2,'0'),'-01') 
            WHEN  r.month != '0' AND r.month IS NOT NULL 
            THEN concat(r.year,'-',lpad(r.month,2,'0'),'-01')!='" . $til_dt . "'
            ELSE r.disp_month = '0' OR r.`disp_month` IS NULL OR r.month = '0' OR r.month IS NULL END 	";
        } else
            if ($ason_type == 'ent_dt') {
            $til_date = explode("-", $_GET['til_date']);
            $til_dt = $til_date[2] . "-" . $til_date[1] . "-" . $til_date[0];

            $ason_str = " d.ent_dt >= '" . $til_dt . "' ";
            $ason_str_res = " r.disp_ent_dt >= '" . $til_dt . "' ";


            $exclude_cond = " CASE WHEN r.`entry_date` IS NOT NULL AND  r.disp_ent_dt IS NOT NULL
            THEN '" . $til_dt . "' NOT BETWEEN date(r.disp_ent_dt) AND entry_date
            ELSE r.`disp_ent_dt` IS NULL OR r.entry_date IS NULL  END 
            OR r.fil_no IS NULL	";

            $exclude_cond_other = " CASE WHEN  r.`entry_date` IS NOT NULL 
                        AND  r.disp_ent_dt IS NOT NULL
            THEN '" . $til_dt . "' NOT BETWEEN date(r.disp_ent_dt) AND `entry_date` 
            ELSE r.`disp_ent_dt` IS NULL OR r.entry_date IS NULL  END ";
        }


        if ($year_wise_tot == 'y' || $year_wise_tot == 'all') {
            $year_tot = " ";
            $year_tot_main = " ";
        } else {
            $year_tot = " and substr(l.fil_no,4,2)='" . $this->casetype($_GET['skey']) . "' ";
            if (empty($this->casetype($_GET['skey'])))
                $year_tot_main = " and IF(m.active_casetype_id=0,m.casetype_id ,m.active_casetype_id) ='" . $this->casetype($_GET['skey']) . "' ";
            else
                $year_tot_main = "";
        }
      
        $mf_h_table = '';
        $mf_f2_table = '';


        if ($concept == 'new') {
            if ($this->request->getGet('mf') == 'M') {
            $mf_f2_table = " (f2." . $mainhead_name . " = 'M' AND (admitted = '' OR admitted IS NULL))";
            $mf_h_table = " (h." . $mainhead_name . " = 'M' AND (admitted = '' OR admitted IS NULL))";
            }
            if ($this->request->getGet('mf') == 'F') {
            $mf_f2_table = " (f2." . $mainhead_name . " = 'F' OR (admitted != '' AND admitted IS NOT NULL))";
            $mf_h_table = " (h." . $mainhead_name . " = 'F' OR (admitted != '' AND admitted IS NOT NULL))";
            }
        } elseif ($concept == 'old') 
        {

            if($_GET['mf']=='M')
            {
            $mf_f2_table=" f2.".$mainhead_name."= '".$_GET['mf']."' ";
            $mf_h_table=" h.".$mainhead_name."= '".$_GET['mf']."' ";
            }
            if($_GET['mf']=='F')
            {
            $mf_f2_table=" f2.".$mainhead_name."= '".$_GET['mf']."'  ";
            $mf_h_table=" h.".$mainhead_name."= '".$_GET['mf']."'  ";
            }

        }
       
        if ($_GET['main_connected'] == 'main')
            $main_connected = " and ( m.diary_no = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL ) ";
        else
            $main_connected = " ";

        if ($_GET['order_by'] == 'case')
            $order_by = " order by substr(m.fil_no,3,3),substr(m.fil_no,11,4),substr(m.fil_no,6,5) ";
        elseif ($_GET['order_by'] == 'fil_dt')
            $order_by = " order by date(m.active_fil_dt) ";
        elseif ($_GET['order_by'] == 'da')
            $order_by = " order by m.dacode ";

        if ($adv_opt == 'Y') {
            $adv_field_list = " group_concat(
            IF(pet_res='P',cast(concat(a2.pet_res_no,' - ',a2.adv,' ') AS char),'') ORDER BY a2.pet_res_no SEPARATOR ' ')pet_adv2, 
            group_concat(
            IF (pet_res='R', cast(concat(a2.pet_res_no,' - ',a2.adv,' ' ) AS char ) , '' ) ORDER BY a2.pet_res_no SEPARATOR ' ' )res_adv2, ";
            $adv_join = " LEFT JOIN advocate a2 ON a2.diary_no = m.diary_no ";
        } else {
            $adv_field_list = " '' as pet_adv2, '' as res_adv2, ";
            $adv_join = " ";
        }

        if ($case_status_id == 'all,') {
            $case_status_id = " and case_status_id in (1, 2, 3, 6, 7, 9 ) ";
        } elseif ($case_status_id == 103 || $case_status_id == '103,') {
            $case_status_id = " ";
        } elseif ($case_status_id == 101 || $case_status_id == '101,') {
            $case_status_id = " and o.rm_dt IS NOT NULL 
                AND o.display = 'Y' 
                AND m.c_status = 'P' 
                AND (m.fil_no IS NULL 
                OR m.fil_no = '')";
            $add_table = ' LEFT JOIN obj_save o ON o.diary_no = m.diary_no ';
        } elseif ($case_status_id == 102 || $case_status_id == '102,') {
            //$case_status_id=" and m.diary_no NOT IN (SELECT DISTINCT diary_no FROM `obj_save` WHERE rm_dt = '0000-00-00 00:00:00' AND display = 'Y' ) "; 
            $case_status_id = " AND NOT (m.fil_no IS NULL OR m.fil_no = '') ";
        } else {
            $case_status_id = " and case_status_id in (" . substr($_GET['case_status_id'], 0, -1) . ")";
        }


        //INNER JOIN vw3 f2 ON m.fil_no = f2.fil_no	 
        // having mainhead = '".$mf."'  and mnd=next_dt

        if ($this->request->getGet('mf')!= 'ALL') {
            if ($this->request->getGet('til_date') != date('d-m-Y')) {
                $t = "CREATE TEMPORARY TABLE vw2 
                            SELECT MAX(ent_dt) AS med, " . $subhead_name . ", " . $mainhead_name . ", fil_no
                            FROM `last_heardt` l
                            WHERE DATE(ent_dt) < '" . $til_dt . "' 
                            " . $year_condition_last_heardt . " " . $year_tot . "
                            GROUP BY diary_no";
                $this->db->query($t);

                $t2 = "CREATE INDEX id_index ON vw2 (fil_no)";
                $this->db->query($t2);

                $t3 = "CREATE TEMPORARY TABLE vw3 SELECT l.fil_no, l." . $subhead_name . ", l." . $mainhead_name . ", l.jud1, med, next_dt
                            FROM vw2 
                            INNER JOIN last_heardt l ON vw2.fil_no = l.fil_no
                            AND l.ent_dt = med
                            AND l." . $mainhead_name . " = '" . $mf . "' " . $subhead . " " . $year_condition_last_heardt . " " . $year_tot;
                $this->db->query($t3);

                $t4 = "CREATE INDEX id_index2 ON vw3 (fil_no)";
                $this->db->query($t4);
            }

            if ($this->request->getGet('til_date') != date('d-m-Y')) 
            {
                $sql = "SELECT " . $adv_field_list . " m.diary_no_rec_date,tentative_cl_dt, m.active_fil_no, m.active_fil_dt, m.active_reg_year, m.active_casetype_id, m.casetype_id, c_status, d.rj_dt, d.month, d.year, d.disp_dt,  
                        r.disp_month, r.disp_year, f2." . $subhead_name . " AS last_subhead, med, h.ent_dt, h." . $mainhead_name . " AS mainhead, r.conn_next_dt, r.disp_dt AS disp_dt_res, m.pet_name, m.res_name, h.next_dt " . $cat_field . ", m.bench, m.lastorder, h.judges, m.diary_no
                        FROM main m 
                        LEFT JOIN dispose d ON m.diary_no = d.diary_no  
                        LEFT JOIN restored r ON m.diary_no = r.diary_no  
                        LEFT JOIN heardt h ON m.diary_no = h.diary_no  
                        LEFT JOIN vw3 f2 ON m.diary_no = f2.diary_no
                        LEFT JOIN act_main a ON a.diary_no = m.diary_no " . $add_table . $mul_cat_join . " " . $act_join . " " . $adv_join . " " . $join_party . "
                        WHERE 1=1 " . $party_name . " " . $pet_res . " " . $year_main . " " . $from_fil_dt . " " . $upto_fil_dt . " " . $case_status_id . " " . $cat_and_act . " " . $act_msc . " " . $main_connected . "
                        AND IF(med > h.ent_dt AND f2." . $mainhead_name . " IS NOT NULL, " . $mf_f2_table . " " . $subhead_if_last_heardt . ", " . $mf_h_table . " " . $subhead_if_last_heardt . ")
                        AND (
                    CASE WHEN r.disp_dt IS NOT NULL 
                                AND r.conn_next_dt IS NOT NULL
                        THEN '" . $til_dt . "' NOT BETWEEN r.disp_dt AND conn_next_dt
                        ELSE r.`disp_dt` IS NULL OR r.conn_next_dt IS NULL 
                        END 
                    OR r.diary_no IS NULL
                    )
                        " . $subhead_condition . " AND
                        DATE(m.diary_no_rec_date) < '" . $til_dt . "' " . $year_condition . " " . $year_tot_main . " AND (c_status = 'P' AND DATE(m.diary_no_rec_date) < '" . $til_dt . "')
                        OR (
                            c_status = 'D' 
                            AND IF(med > h.ent_dt AND f2." . $mainhead_name . " IS NOT NULL, " . $mf_f2_table . " " . $subhead_if_last_heardt . ", " . $mf_h_table . " " . $subhead_if_last_heardt . ")
                            AND " . $ason_str . " AND DATE(m.diary_no_rec_date) < '" . $til_dt . "' " . $year_condition . " " . $year_main . " " . $year_tot_main . " " .$from_fil_dt_condition . " " . $upto_fil_dt_condition . " " . $cat_and_act . " " . $party_name . " " . $pet_res . " " . $act_msc . " " . $main_connected . "
                        )
                        OR (" . $ason_str_res . " 
                            AND IF(med > h.ent_dt AND f2." . $mainhead_name . " IS NOT NULL, " . $mf_f2_table . " " . $subhead_if_last_heardt . ", " . $mf_h_table . " " . $subhead_if_last_heardt . ")
                            AND DATE(m.diary_no_rec_date) < '" . $til_dt . "' " . $year_condition . " " . $year_main . " " . $year_tot_main . "" .$from_fil_dt_condition . " " . $upto_fil_dt_condition . " " . $cat_and_act . " " . $party_name . " " . $pet_res . " " . $act_msc . " " . $main_connected . "
                        )
                        GROUP BY m.diary_no,tentative_cl_dt,d.rj_dt,d.month,d.year,d.disp_dt,r.disp_month,r.disp_year,h.ent_dt,h.mainhead_n,r.conn_next_dt,h.next_dt,h.judges,r.disp_dt " . $order_by;
            } else 
            {
               $sql = "SELECT " . $adv_field_list . " m.diary_no_rec_date,tentative_cl_dt, m.active_fil_no, m.active_fil_dt, m.active_reg_year, m.active_casetype_id, m.casetype_id, c_status, d.rj_dt, d.month, d.year, d.disp_dt, 
                        r.disp_month, r.disp_year, h.ent_dt, h." . $mainhead_name . " AS mainhead, r.conn_next_dt, r.disp_dt AS disp_dt_res, m.pet_name, m.res_name, h.next_dt " . $cat_field . ", m.bench, m.lastorder, h.judges, m.diary_no
                        FROM main m 
                        LEFT JOIN dispose d ON m.diary_no = d.diary_no  
                        LEFT JOIN restored r ON m.diary_no = r.diary_no  
                        LEFT JOIN heardt h ON m.diary_no = h.diary_no  
                        " . $add_table . $mul_cat_join . " " . $act_join . " " . $adv_join . " " . $join_party . "
                        WHERE " . $mf_h_table . " " . $party_name . " " . $pet_res . " " . $year_main . " " .$from_fil_dt_condition . " " . $upto_fil_dt_condition . " " . $case_status_id . " " . $cat_and_act . " " . $act_msc . " " . $main_connected . "
                       AND  ( CASE WHEN r.disp_dt IS NOT NULL AND r.conn_next_dt IS NOT NULL THEN '" . $til_dt . "' NOT BETWEEN r.disp_dt AND conn_next_dt
                        ELSE r.disp_dt IS NULL OR r.conn_next_dt IS NULL END OR r.diary_no IS NULL )
                        " . $subhead_condition . " AND
                        DATE(m.diary_no_rec_date) < '" . $til_dt . "' " . $year_condition . " " . $year_tot_main . " AND (c_status = 'P' AND DATE(m.diary_no_rec_date) < '" . $til_dt . "')
                        GROUP BY m.diary_no,tentative_cl_dt,d.rj_dt,d.month,d.year,d.disp_dt,r.disp_month,r.disp_year,h.ent_dt,h.mainhead_n,r.conn_next_dt,h.next_dt,h.judges,r.disp_dt " . $order_by;
            }
        } else {
            if ($this->request->getGet('til_date') != date('d-m-Y')) {
                $sql = "SELECT {$adv_field_list} m.diary_no_rec_date, m.active_fil_no, tentative_cl_dt,m.active_fil_dt, m.active_reg_year, m.active_casetype_id, m.casetype_id, c_status, d.rj_dt, d.month, d.year, d.disp_dt, 
                r.disp_month, r.disp_year, r.conn_next_dt, r.disp_dt as res_disp_dt, m.pet_name, m.res_name, {$mainhead_name}, next_dt {$cat_field}, m.bench, m.lastorder, h.judges, m.diary_no
                FROM main m 
                LEFT JOIN heardt h ON m.diary_no = h.diary_no 
                LEFT JOIN dispose d ON m.diary_no = d.diary_no  
                LEFT JOIN restored r ON m.diary_no = r.diary_no    
                LEFT JOIN act_main a ON a.diary_no = m.diary_no {$add_table} {$mul_cat_join} {$act_join} {$adv_join} {$join_party}
                WHERE 1=1 {$party_name} {$pet_res} {$year_main} {$from_fil_dt} {$upto_fil_dt} {$case_status_id} {$cat_and_act} {$act_msc} {$main_connected} AND 
                (
                    CASE WHEN r.disp_dt IS NOT NULL 
                                AND r.conn_next_dt IS NOT NULL
                        THEN '" . $til_dt . "' NOT BETWEEN r.disp_dt AND conn_next_dt 
                        ELSE r.`disp_dt` IS NULL OR r.conn_next_dt IS NULL 
                        END 
                    OR r.diary_no IS NULL
                    )
                AND
                DATE(m.diary_no_rec_date) < '{$til_dt}' {$year_condition} {$year_tot_main} AND (c_status = 'P' AND DATE(m.diary_no_rec_date) < '{$til_dt}')
                OR 
                (
                    c_status = 'D' AND {$ason_str} AND DATE(m.diary_no_rec_date) < '{$til_dt}' {$year_condition} {$year_main} {$year_tot_main} {$from_fil_dt} {$upto_fil_dt} {$cat_and_act} {$party_name} {$pet_res} {$act_msc} {$main_connected}
                )
                OR ({$ason_str_res} AND DATE(m.diary_no_rec_date) < '{$til_dt}' {$year_condition} {$year_main} {$year_tot_main} {$from_fil_dt} {$upto_fil_dt} {$cat_and_act} {$party_name} {$pet_res} {$act_msc} {$main_connected})
                GROUP BY m.diary_no,tentative_cl_dt,d.rj_dt,d.month,d.year,d.disp_dt,r.disp_month,r.disp_year,h.ent_dt,h.mainhead_n,r.conn_next_dt,h.next_dt,h.judges,r.disp_dt {$order_by}";
            } else {
                $sql = "SELECT {$adv_field_list} m.diary_no_rec_date,tentative_cl_dt, m.active_fil_no, m.active_fil_dt, m.active_reg_year, m.active_casetype_id, m.casetype_id, c_status, d.rj_dt, d.month, d.year, d.disp_dt,
                r.disp_month, r.disp_year, r.conn_next_dt, r.disp_dt as res_disp_dt,r.disp_dt AS disp_dt_res, m.pet_name,h.ent_dt, m.res_name,  h." . $mainhead_name . " AS mainhead, next_dt {$cat_field}, m.bench, m.lastorder, h.judges, m.diary_no
                FROM main m 
                LEFT JOIN heardt h ON m.diary_no = h.diary_no 
                LEFT JOIN dispose d ON m.diary_no = d.diary_no  
                LEFT JOIN restored r ON m.diary_no = r.diary_no    
                LEFT JOIN act_main a ON a.diary_no = m.diary_no {$add_table} {$mul_cat_join} {$act_join} {$adv_join} {$join_party}
                WHERE 1=1 {$party_name} {$pet_res} {$year_main} {$from_fil_dt} {$upto_fil_dt} {$case_status_id} {$cat_and_act} {$act_msc} {$main_connected} AND 
                (
                    CASE WHEN r.disp_dt IS NOT NULL 
                                AND r.conn_next_dt IS NOT NULL
                        THEN '" . $til_dt . "' NOT BETWEEN r.disp_dt AND conn_next_dt
                        ELSE r.disp_dt IS NULL OR r.conn_next_dt IS NULL 
                        END 
                    OR r.diary_no IS NULL
                    )
                AND
                DATE(m.diary_no_rec_date) < '{$til_dt}' {$year_condition} {$year_tot_main} AND (c_status = 'P' AND DATE(m.diary_no_rec_date) < '{$til_dt}')
                GROUP BY m.diary_no ,d.rj_dt,d.month,d.year,d.disp_dt,r.disp_month,r.disp_year,h.ent_dt,h.mainhead_n,r.conn_next_dt,h.next_dt,h.judges,r.disp_dt,tentative_cl_dt {$order_by}";
            }
        }

        // pr($sql);die;
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        $data['table'] = $result;
        $data['skey'] = $skey;
        $data['mainhead_name'] = $mainhead_name;
        $data['subhead_name'] = $subhead_name;
        $data['til_dt'] = $til_dt;
        $data['year_wise_tot'] = $year_wise_tot;
        $data['case_status_id'] = $case_status_id;
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
