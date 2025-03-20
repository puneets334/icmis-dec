<?php

namespace CodeIgniter\Validation;

namespace App\Controllers\Filing;

use App\Controllers\BaseController;
use App\Models\Filing\Office_report_model;

class OfficeReport extends BaseController
{
    protected $session;
    protected $form_validation;
    protected $office_report_model;

    public function __construct()
    {
        helper(['form', 'url', 'html', 'security']);
        $this->office_report_model = new office_report_model();
        $this->form_validation = \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $data['sections'] = $this->office_report_model->getSections();
        return view('Extension/office_report/cl_print_section_bulk_or', $data);
    }

    public function getCauseListSectionBulkOrUpload()
    {
        // pr($_REQUEST);
        $data['list_dt'] = date('d-m-Y', strtotime($_REQUEST['list_dt']));
        $data['list_dt1'] =date("Y-m-d", strtotime($_REQUEST['list_dt']));
        $data['main_supl_head'] =  $_REQUEST['main_suppl'];
        $section = $_REQUEST['sec_id'];
        $data['mainhead'] = $_REQUEST['mainhead'];

        if ($data['mainhead']== 'M') {
            $data['mainhead_descri'] = "Miscellaneous Hearing";
        }
        if ($data['mainhead']== 'F') {
            $data['mainhead_descri'] = "Regular Hearing";
        }
        if ($data['mainhead']== 'L') {
            $data['mainhead_descri'] = "Lok Adalat";
        }
        if ($data['mainhead']== 'S') {
            $data['mainhead_descri'] = "Mediation";
        }
        if ($_POST['board_type'] == 'C') {
            if (strtotime(date('y-m-d', strtotime($_REQUEST['list_dt']))) == strtotime(date('y-m-d'))) {
                if (strtotime(date('H:i:s')) > strtotime(date('10:50:00'))) {

                    ?>
                    <div id="prnnt" style="text-align: center; font-size:10px;">
                        <H3>Cause List for Dated <?php echo $data['list_dt']; ?> (<?php echo $data['mainhead_descri']; ?>)<br><?php echo $data['main_supl_head']; ?> </H3>
                        "No Records Found"
                        <div id='div_result'> </div>
                        <BR /><BR /><BR /><BR /> <BR /><BR /><BR /><BR />
                    </div>
            <?php die;
                }
            }
        }
        if ($_REQUEST['lp'] == "all") {
            $lp = "";
        } else {
            $lp = "and h.listorder = '" . $_REQUEST['lp'] . "'";
        }

        if ($_REQUEST['main_suppl'] == "0") {
            $main_suppl = "";
        } else {
            $main_suppl = "AND h.main_supp_flag = '" . $_REQUEST['main_suppl'] . "'";
            if ($_REQUEST['main_suppl'] == "1") {
                $data['main_supl_head'] = "Main List";
            }
            if ($_REQUEST['main_suppl'] == "2") {
                $data['main_supl_head'] = "Supplimentary List";
            }
        }

        if ($_REQUEST['courtno'] == "0") {
            $court_no = "";
        } else {
            $court_no = "AND r.courtno = '" . $_REQUEST['courtno'] . "'";
        }
        if ($_REQUEST['board_type'] == "0") {
            $board_type = "";
        } else {
            // $board_type = "AND h.board_type = '".$_REQUEST['board_type']."'";
            $board_type = "AND h.board_type = '" . $_REQUEST['board_type'] . "'";
        }
        // echo $board_type;
        if ($_REQUEST['orderby'] == "1") {
            $orderby = "r.courtno, ";
        } else if ($_REQUEST['orderby'] == "2") {
            $orderby = "us.id, ";
        } else {
            $orderby = "";
        }
        $sec_id2 ='';
        if ($_REQUEST['sec_id'] == "0") {
            $sec_id = "";
        } else {
            $rp = is_data_from_table('master.usersection', "id = $_REQUEST[sec_id]", 'section_name');
            $sec_name = $rp[0]['section_name'];
            $sec_id = " and (us.id ='" . $_REQUEST['sec_id'] . "'  or tentative_section(h.diary_no) = '$sec_name' )";
            $sec_id2 = "AND us.id is not null";
        }
        $ucode = $_SESSION['login']['usercode'];
        $usertype = $_SESSION['login']['usertype'];
        $section1 = $_SESSION['login']['section'];
        $re_sec =is_data_from_table('master.usersection', "id = $section1", 'section_name');
        $sec_name = $re_sec[0]['section_name'];
     

        if ($usertype == '14' and $section != 77) {
            $ro_u =  $this->office_report_model->sq_u();
            $all_da = $ro_u['allda'];
            $mdacode = "AND (m.dacode IN ($all_da)  or m.dacode=0)";

        } else if ((($usertype == '3') || ($usertype == '4') || ($usertype == '6') || ($usertype == '9')) && $section != 77) {
            $rs_sec_map = $this->office_report_model->get_user_emid($ucode);
            foreach ($rs_sec_map as $rw_sec_map ) {
                $uempid = $rw_sec_map['empid'];
            }

            $sql_if_exists = is_data_from_table('master.user_sec_map',"display='Y' and empid=$uempid",'*','A');
            if (!empty($sql_if_exists)) {

                $rs_usection = is_data_from_table('master.user_sec_map',"display = 'Y' AND empid = $uempid","STRING_AGG(DISTINCT usec::TEXT, ',') AS distinct_usec",'A');
               
                foreach ($rs_usection as $rw_section) {
                    $idd = $rw_section['distinct_usec'];
                }

                $del = ',';
                $rs_sec_name = is_data_from_table('master.usersection',"id in ($idd)",'section_name','A');;
                $sec_list = '';
                foreach ($rs_sec_name as $rw_sec_name ) {
                    $sec_list = $sec_list . "','$rw_sec_name[section_name]";
                }
                $usec_name = $sec_list;

                $sql_ar_users = is_data_from_table('master.users',"display='Y' and section in ($idd)","STRING_AGG(usercode::TEXT,',') as usercode",'A');
             
                foreach($sql_ar_users as $row_ar ) {
                    $all_da = $row_ar[0];
                }

                $mdacode = "AND (m.dacode IN ($all_da)  or m.dacode=0)";
                //echo " the section is ";

                $section = "and (us.id in($idd) or tentative_section(h.diary_no) in ($usec_name)";

                //  echo " ar dr registratr coloum";
            }
        } else if ($usertype == '17' or $usertype == '50' or $usertype == '51') {
            $mdacode = "AND m.dacode = '$ucode'";
        } else {
            $mdacode = "";
        }
        if ($ucode == '1' or $ucode == '469') {
            $cl_print_jo = "";
            $cl_print_jo2 = "";
        } else {
            $cl_print_jo = "LEFT JOIN cl_printed p ON p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = 'Y'";
            $cl_print_jo2 = "p.id IS NOT NULL AND ";
        }

        if ($ucode == 1) {
            $section = '';
        } else if ((($usertype == '3') || ($usertype == '4') || ($usertype == '6') || ($usertype == '9')) && $section != 77) {
            $section = "and (us.id in('$idd') or tentative_section(h.diary_no) in ('$usec_name'))";
        } else {
            $section = "and (us.id='$section1' or tentative_section(h.diary_no) = '$sec_name' )";   
        }

        $data['res'] = $this->office_report_model->getCauseListSectionBulkOrUploadData($court_no, $cl_print_jo,$cl_print_jo2,$data['mainhead'],$main_suppl,$sec_id2,$sec_id,$mdacode,$lp,$board_type,$section,$orderby,$data['list_dt1']);
        $data['model'] = $this->office_report_model;
        
        return view('Extension/office_report/getCauseListSectionBulkOrUploadView',$data);
    }

    public function officeReport()
    {
        // $data['ucode'] = session()->get('login')['usercode'];
        $data['natures'] = $this->office_report_model->getCasetype();
        return view('Extension/office_report/office_report', $data);
    }

    public function publish_office_report()
    {
        $nature = $this->request->getPost('ddl_nature');
        $OfficeReportMaster = $this->office_report_model->getOfficeReportMaster($nature);
        echo '<option value="">Select</option>';
        foreach ($OfficeReportMaster as $row) {
            echo '<option value="' . $row['id'] . '">' . $row['r_nature'] . '</option>';
        }
    }

    public function get_report_type()
    {
        $nature = $this->request->getPost('ddl_nature');
        $OfficeReportMaster = $this->office_report_model->getOfficeReportMaster($nature);
        echo '<option value="">Select</option>';
        foreach ($OfficeReportMaster as $row) {
            echo '<option value="' . $row['id'] . '">' . $row['r_nature'] . '</option>';
        }
    }

    public function get_office_report()
    {
        $data['officeReportModel'] = $this->office_report_model;
        $data['ucode'] = session()->get('login')['usercode'];
        $data['diary_no'] = $_REQUEST['d_no'] . $_REQUEST['d_yr'];
        $data['ddl_rt'] = $_REQUEST['ddl_rt'];
        $data['d_yr'] = $_REQUEST['d_yr'];
        $data['d_no'] = $_REQUEST['d_no'];

        return view('Extension/office_report/get_office_report', $data);
    }

    public function get_ia()
    {
        $data['officeReportModel'] = $this->office_report_model;
        $data['ucode'] = session()->get('login')['usercode'];
        $data['dairy_no'] = $_REQUEST['d_no'] . $_REQUEST['d_yr'];
        return view('Extension/office_report/get_ia', $data);
    }
}
