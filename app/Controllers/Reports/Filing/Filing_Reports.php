<?php

namespace App\Controllers\Reports\Filing;

use App\Controllers\BaseController;
use App\Models\Reports\Filing\FilingReportModel;
use App\Models\Common\Dropdown_list_model;
use App\Models\Filing\Model_diary;
use App\Models\Entities\fil_trap_a;

class Filing_Reports extends BaseController
{
    public $Dropdown_list_model;
    public $Model_diary;
    function __construct()
    {
        //ini_set('memory_limit','750M'); // This also needs to be increased in some cases. )
        ini_set('memory_limit', '-1');
        $this->Dropdown_list_model = new Dropdown_list_model();
        $this->Model_diary = new Model_diary();
        $ReportModel = new FilingReportModel();
    }


    public function get_defect_reports()
    {
        $ReportModel = new FilingReportModel();
        $data['case_result'] = '';
        $data['app_name'] = 'User wise Diary Report';
        $data = $this->request->getGet();
        $data['dreports'] = $ReportModel->get_filing_scrutiny_defect_reports($data);
        $data['prname'] = $ReportModel->get_scrutiny_causetitle($data);
        $data['adv'] = $ReportModel->get_scrutiny_adv($data);
        //print_r($data['prname']); exit;


        return view('Reports/filing_reports/get_scrutiny_defect_reports', $data);
        exit;
    }

    public function get_user_defect_reports()
    {
        $ReportModel = new FilingReportModel();
        $data['case_result'] = '';
        $data['app_name'] = 'User wise Diary Report';
        $data = $this->request->getGet();
        $data['on_date'] = $this->request->getGet('on_date');
        $data['dreports'] = $ReportModel->getDefectsReports($data);
        //print_r($data['dreports']);
        //$arr = array_merge($data['dreports'], $data['dreports1']);

        return view('Reports/filing_reports/get_defect_reports', $data);
        exit;
    }


    public function scrutiny_user_wise_detail_report($user, $ondate, $name)
    {

        $ReportModel = new FilingReportModel();
        $data['case_result'] = '';
        $data['app_name'] = 'User wise Scrutiny Detail Report';
        $data['on_date'] = $ondate;
        $data['name'] = $name;
        $data['case_result'] = $ReportModel->scrutiny_user_wise_detail_report_model($user, $ondate);

        return view('Reports/filing_reports/scrutiny_user_wise_detail_report', $data);
        exit;
    }

    public function scrutiny_caseSearch()
    {
        $ReportModel = new FilingReportModel();
        $data['case_result'] = '';
        $data['app_name'] = 'User wise Scrutiny Detail Report';
        $data['on_date'] = $ondate;
        $data['name'] = $name;
        $result_array = $ReportModel->get_res_total_defects($user, $ondate);
        $result_array = $ReportModel->scrutiny_diary_wise_case($user, $ondate);
        $data['case_result'] = $result_array;
        return view('Reports/filing_reports/scrutiny_case', $data);
        exit;
    }
    public function tagged_matter_report()
    {
        $ReportModel = new FilingReportModel();
        $result_array = $ReportModel->tagged_matter_report();
        if (!empty($result_array)) {
            $data['tagged_result'] = $result_array;
            return view('Reports/filing/tagged_report', $data);
        } else {
            return view('Reports/filing/tagged_report');
        }
    }

    public function filtrapMon()
    {
        return view('Reports/filing/filtrap_mon');
    }

    public function revertDate_hiphen($date)
    {
        $date = explode('-', $date);
        return $date[2] . '-' . $date[1] . '-' . $date[0];
    }

    public function get_filtrap_mon()
    {
        $trapModel = new fil_trap_a();

        $date = $this->request->getPost('date');
        $date = $this->revertDate_hiphen($date);
        $data['date'] = $date;
        $data['result'] = $trapModel->get_filtrap_mon_data($date);
        return view('Reports/filing/get_filtrap_mon_table', $data);
    }



    public function party_diary_search()
    {
        return view('Filing/party_search/diary_search');
    }

    public function get_diary_search()
    {

        $ddl_party_type = $_REQUEST['ddl_party_type'];
        $parties = '';
        $txt_name = strtolower($_REQUEST['txt_name']);
        if ($ddl_party_type == '') {
            //        $parties=" (pet_name like '%$_REQUEST[txt_name]%' || res_name like '%$_REQUEST[txt_name]%')";

            $parties = " (LOWER(partyname) like '%$txt_name%')";
        } else  if ($ddl_party_type == 'P') {
            //        $parties=" pet_name like '%$_REQUEST[txt_name]%'";
            $parties = " LOWER(partyname) like '%$txt_name%' and pet_res='P'";
        } else  if ($ddl_party_type == 'R') {
            //        $parties=" res_name like '%$_REQUEST[txt_name]%'";
            $parties = " LOWER(partyname) like '%$txt_name%' and pet_res='R'";
        } else  if ($ddl_party_type == 'I') {
            //        $parties=" res_name like '%$_REQUEST[txt_name]%'";
            $parties = " LOWER(partyname) like '%$txt_name%' and pet_res='I'";
        } else  if ($ddl_party_type == 'N') {
            //        $parties=" res_name like '%$_REQUEST[txt_name]%'";
            $parties = " LOWER(partyname) like '%$txt_name%' and pet_res='N'";
        }

        $tb_name = '';
        $caveat_no = '';
        $ddl_status = '';
        $ddl_year = '';
        if ($_REQUEST['ddl_diary_caveat'] == 'D') {
            //        $tb_name=" main";
            $tb_name = " party";
            if ($_REQUEST['ddl_status'] != '') {
                $ddl_status = " join main b on b.diary_no=party.diary_no and c_status='$_REQUEST[ddl_status]'";
            }

            echo $_REQUEST['ddl_year'];
            if ($_REQUEST['ddl_year'] != '') {
                $ddl_year = " and substr(party.diary_no,-4)= '$_REQUEST[ddl_year]' ";
            }
        } else if ($_REQUEST['ddl_diary_caveat'] == 'C') {
            //        $tb_name=" caveat";
            $tb_name = " caveat_party";
            $caveat_no = " caveat_no";
            if ($_REQUEST['ddl_year'] != '') {
                $ddl_year = " and substr(caveat_party.caveat_no,-4)='$_REQUEST[ddl_year]'";
            }
        }


        $sql_cnt = "SELECT COUNT(*) AS total FROM $tb_name $ddl_status WHERE $parties $ddl_year";

        $query = $this->db->query($sql_cnt);
        $row = $query->getRowArray();

        $data['res_sq'] = $row['total'];

        return view('Filing/party_search/get_diary_search', $data);
    }


    public function include_diary_search()
    {
        $data = array();
        $data['$_REQUEST'] = $_REQUEST;
        return view('Filing/party_search/include_diary_search', $data);
    }

    public function diary_user_wise()
    {
        $data['case_result']='';
        $data['app_name']='User wise Diary Report';
        if($_REQUEST && isset($_REQUEST['on_date']) && !empty($_REQUEST['on_date'])) {
            $from_date=date('Y-m-d', strtotime($this->request->getPost('on_date')));
            //var_dump($c_Type);
            $ReportModel = new FilingReportModel();
            $result_array = $ReportModel->diary_user_wise($from_date);
            // pr($result_array);
            $data['case_result'] = $result_array;
        }
        return view('Reports/filing_reports/diary_user_wise_report',$data);

    }

    public function diary_user_wise_detail_report($user,$ondate,$name)
    {
        $data['case_result']='';
        $data['app_name']='User wise Diary Detail Report';
        $data['on_date']=$ondate;
        $data['name']=$name;
        $ReportModel = new FilingReportModel();
        $result_array = $ReportModel->diary_user_wise_detail_report($user,$ondate);
        $data['case_result'] = $result_array;
        // pr($result_array);
        return view('Reports/filing_reports/diary_user_wise_detail_report',$data);

    }


    public function scrutiny_user_wise()
    {
        $ReportModel = new FilingReportModel();
        $data['case_result'] = '';
        $data['app_name'] = 'User wise Diary Report';
        $data['$_POST'] = $_POST;
        if($_REQUEST && isset($_REQUEST['on_date']) && !empty($_REQUEST['on_date'])) {
            $on_date = date('Y-m-d', strtotime($_REQUEST['on_date']));
            //var_dump($c_Type);

            $result_array = $ReportModel->scrutiny_user_wise($on_date);
            
            $data['case_result'] = $result_array;
        }

        return view('Reports/scrutiny_user_report/scrutiny_user_wise_report', $data);
    }

    public function scrutiny_user_wise_defect_detail_report($user, $ondate, $name)
    {

        $ReportModel = new FilingReportModel();
        $data['case_result'] = '';
        $data['app_name'] = 'User wise Scrutiny Detail Report';
        $data['on_date'] = $ondate;
        $data['name'] = $name;
        $data['case_result'] = $ReportModel->scrutiny_user_wise_detail_report_model($user, $ondate);
        //pr($data['case_result']);
        return view('Reports/scrutiny_user_report/scrutiny_user_wise_defect_detail_report', $data);
    }


    public function defect_type_report()
    {
        $data = array();
        $data['$_REQUEST'] = $_REQUEST;
        return view('Reports/filing_reports/defect_type_report', $data);
    }

    public function get_defect_type_report()
    {
        $ReportModel = new FilingReportModel();
        $data = array();
        $data['$_REQUEST'] = $_REQUEST;
        $data['FilingReportModel'] = $ReportModel;
        return view('Reports/filing_reports/get_defect_type_report', $data);
    }


    public function lowerct_user_wise()
    {
        $ReportModel = new FilingReportModel();
        $data['case_result'] = '';
        $data['app_name'] = 'Lower Court Report';
        $data['$_POST'] = $_POST;
        if ($_POST) {
            $from_date = date('Y-m-d', strtotime($_POST['on_date']));
            //var_dump($c_Type);

            $result_array = $ReportModel->lowerct_user_wise($from_date);
            //pr($result_array);
            $data['case_result'] = $result_array;
        }

        return view('Reports/filing_reports/lower_court_report', $data);
    }

    public function lowerct_user_wise_detail_report($ondate,$user='', $name='')
    {
        $ReportModel = new FilingReportModel();
        $data['case_result'] = '';
        $data['app_name'] = 'User wise Lower Court Detail Report';
        $data['on_date'] = $ondate;
        $data['name'] = $name;

        $result_array = $ReportModel->lowerct_user_wise_detail_report($ondate,$user);
        //pr($result_array);
        $data['case_result'] = $result_array;
        return view('Reports/filing_reports/lowerct_user_wise_detail_report', $data);
    }
}
