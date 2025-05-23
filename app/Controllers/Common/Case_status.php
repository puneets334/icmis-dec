<?php

namespace App\Controllers\Common;

use App\Controllers\BaseController;
use App\Models\Entities\Model_main;
use App\Models\Entities\Model_main_a;
use App\Models\Common\Component\Model_case_status;

class Case_status extends BaseController
{
    public $Model_main;
    public $Model_main_a;
    public $Model_case_status;

    function __construct()
    {
        $this->Model_main = new Model_main();
        $this->Model_main_a = new Model_main_a();
        $this->Model_case_status = new Model_case_status();
        ini_set('memory_limit','51200M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
        ini_set('max_execution_time', -1);
    }
    function index()
    {

        $data['casetype'] = get_from_table_json('casetype');
        return view('Common/Component/case_status/case_status', $data);
    }

    function case_status()
    {
       
        if ($this->request->getMethod() === 'post') {
            $search_type = $this->request->getPost('search_type');
            $diary_number = $this->request->getPost('diary_number');
            $diary_year = $this->request->getPost('diary_year');
            $case_type = $this->request->getPost('case_type');
            $case_number = $this->request->getPost('case_number');
            $case_year = $this->request->getPost('case_year');

            $this->validation->setRule('search_type', 'Select Diary or Case type', 'required');
            if (!empty($search_type) && $search_type != null) {
                if ($search_type == 'D') {
                    $this->validation->setRule('search_type', 'Select Diary or Case type', 'required');
                    $this->validation->setRule('diary_number', 'Diary number', 'required');
                    $this->validation->setRule('diary_year', 'Diary year', 'required');

                    $data = [
                        'search_type' => $search_type,
                        'diary_number' => $diary_number,
                        'diary_year' => $diary_year,
                    ];
                } else {
                    $this->validation->setRule('search_type', 'Select Diary or Case type', 'required');
                    $this->validation->setRule('case_type', 'Case type', 'required');
                    $this->validation->setRule('case_number', 'Case number', 'required');
                    $this->validation->setRule('case_year', 'Case year', 'required');

                    $data = [
                        'search_type' => $search_type,
                        'case_type' => $case_type,
                        'case_number' => $case_number,
                        'case_year' => $case_year,
                    ];
                }
            } else {
                $data = [
                    'search_type' => $search_type
                ];
            }

            if (!$this->validation->run($data)) {
                echo '3@@@';
                echo $this->validation->listErrors();
                exit();
            }
            $is_a = '';
            if ($search_type != 'D') {
                $main_diary_number = get_diary_case_type($case_type, $case_number, $case_year,'R','B');
                //pr($main_diary_number);
                if(!empty($main_diary_number))
                {
                   
                    $main_diary_number['ct'] = $case_type;
                    $main_diary_number['cn'] = $case_number;
                    $main_diary_number['cy'] = $case_year;
                }else{
                    echo '<div class="text-center"><b>Case not Found!!</b></div>';
                    die;
                }
            } else {
                $main_diary_number = $diary_number.$diary_year;
                $diary_details = is_data_from_table('main', ['diary_no' => $main_diary_number], '*', 'R');
                $flag = "";
                if (empty($diary_details)) {

                    $flag = "_a";
                    $diary_details = is_data_from_table('main_a', ['diary_no' => $main_diary_number], '*', 'R');
                    
                }
                if (!empty($diary_details))
                {
                    $main_diary_number = array('dn' => $diary_number , 'dy' => $diary_year);
                }else{
                    echo '<div class="text-center"><b>Diary No. not Found</b></div>';
                    die;
                }               

            }
            //pr($main_diary_number);
            echo $this->component_case_status_process_tab($main_diary_number);
            return true;
        }
        // return true;
    }

    function case_status_by_diaryno()
    {
        $diary_number = $this->request->getGetPost('diaryno');
         
        if(!empty($diary_number)) 
        {
            $diary_info = get_diary_numyear($diary_number);
            $main_diary_number = array('dn' => $diary_info[0] , 'dy' => $diary_info[1]);
            
            echo $this->component_case_status_process_tab($main_diary_number);
        }else{
            echo '<div class="text-center"><b>Diary No. not Found</b></div>';
            die;
        } 
        
        exit();
    }


    public function component_case_status_process_tab($diary_no = '')
    {
        $model = new \App\Models\Common\Component\Model_case_status();
        $html = "";
        $data = getCaseDetails($diary_no);
        $data['component'] = 'component_for_case_status_process';
        $data['Model_case_status'] = $model;
        $html = view('Common/Component/case_status/case_status_process_tab', $data);
        return $html;
    }


    function case_status_same()
    {
       
        if ($this->request->getMethod() === 'post') {
            $search_type = $this->request->getPost('search_type');
            $diary_number = $this->request->getPost('diary_number');
            $diary_year = $this->request->getPost('diary_year');
            $case_type = $this->request->getPost('case_type');
            $case_number = $this->request->getPost('case_number');
            $case_year = $this->request->getPost('case_year');

            $this->validation->setRule('search_type', 'Select Diary or Case type', 'required');
            if (!empty($search_type) && $search_type != null) {
                if ($search_type == 'D') {
                    $this->validation->setRule('search_type', 'Select Diary or Case type', 'required');
                    $this->validation->setRule('diary_number', 'Diary number', 'required');
                    $this->validation->setRule('diary_year', 'Diary year', 'required');

                    $data = [
                        'search_type' => $search_type,
                        'diary_number' => $diary_number,
                        'diary_year' => $diary_year,
                    ];
                } else {
                    $this->validation->setRule('search_type', 'Select Diary or Case type', 'required');
                    $this->validation->setRule('case_type', 'Case type', 'required');
                    $this->validation->setRule('case_number', 'Case number', 'required');
                    $this->validation->setRule('case_year', 'Case year', 'required');

                    $data = [
                        'search_type' => $search_type,
                        'case_type' => $case_type,
                        'case_number' => $case_number,
                        'case_year' => $case_year,
                    ];
                }
            } else {
                $data = [
                    'search_type' => $search_type
                ];
            }

            if (!$this->validation->run($data)) {
                echo '3@@@';
                echo $this->validation->listErrors();
                exit();
            }
            $is_a = '';
            if ($search_type != 'D') {
                $main_diary_number = get_diary_case_type($case_type, $case_number, $case_year,'R','B');
                //pr($main_diary_number);
                if(!empty($main_diary_number))
                {
                   
                    $main_diary_number['ct'] = $case_type;
                    $main_diary_number['cn'] = $case_number;
                    $main_diary_number['cy'] = $case_year;
                }else{
                    echo '<div class="text-center"><b>Case not Found!!</b></div>';
                    die;
                }
            } else {
                $main_diary_number = $diary_number.$diary_year;
                $diary_details = is_data_from_table('main', ['diary_no' => $main_diary_number], '*', 'R');
                $flag = "";
                if (empty($diary_details)) {

                    $flag = "_a";
                    $diary_details = is_data_from_table('main_a', ['diary_no' => $main_diary_number], '*', 'R');
                    
                }
                if (!empty($diary_details))
                {
                    $main_diary_number = array('dn' => $diary_number , 'dy' => $diary_year);
                }else{
                    echo '<div class="text-center"><b>Diary No. not Found</b></div>';
                    die;
                }               

            }
            //pr($main_diary_number);
            echo $this->case_status_process_tab_same_page($main_diary_number);
            return true;
        }
        // return true;
    }

    public function case_status_process_tab_same_page($diary_no = '')
    {
        $model = new \App\Models\Common\Component\Model_case_status();
        $html = "";
        $data = getCaseDetails($diary_no);
        $data['component'] = 'component_for_case_status_process';
        $data['Model_case_status'] = $model;
        $data['diary_number'] = $diary_no['dn'] . $diary_no['dy'];
        $html = view('Common/Component/case_status/case_status_process_tab_same_page', $data);
        return $html;
    }




    function earlier_court()
    {
        if ($this->request->getMethod() === 'post') {
            $diary_number = $this->request->getPost('diaryno');
            /*$this->validation->setRule('diaryno', 'Diary Number', 'required');

            if (!$this->validation->run($diary_number)) {
                echo '3@@@';
                echo $this->validation->listErrors();exit();
            }
            */

            echo component_earlier_court_tab($diary_number);
            exit();
        }
        exit();
    }


    public function get_connected()
    {
        if ($this->request->getMethod() === 'post') {
            $diaryno = $this->request->getPost('diaryno');
            

            $output = "";
            
            $ro_main = is_data_from_table('main as m', ['diary_no' => $diaryno], " coalesce(m.conn_key, '') as connkey ", '');

            if (!empty($ro_main)) {

                $conn_case = $ro_main["connkey"];
                // if ($conn_case != "") {
                if ($conn_case != "" && $conn_case != 0) {

                    $result_conn = $this->Model_case_status->getTagedMattersData($conn_case);
                    if (!empty($result_conn)) {
                        $output .= '<table border="0" class="table table-bordered table-striped custom-table ">';
                        $output .= '<tr><thead><th>&nbsp;</th><th>&nbsp;</th><th align="center">Case No.</th><th>Petitioner vs. Respondant</th><th>List</th><th>Status</th><th>Stat. Info.</th><th>IA</th><th>DA</th><th>Entry By & Date</th></thead></tr>';
                        $cntt = 0;
                        foreach ($result_conn  as  $row_conn) {
                            $t_fil_no = get_case_nos($row_conn['diary_no'], '&nbsp;&nbsp;');

                            if (trim($t_fil_no) == '') {
                                $casetype_id = $row_conn['casetype_id'];
                                $row_12 = is_data_from_table('casetype', ['casecode' => $casetype_id], " short_description ", '');
                                if (!empty($row_12)) {
                                    $t_fil_no = $row_12['short_description'];
                                }
                            }

                            $cntt++;
                            //DA NAME START FOR CONNECTED
                            $da_name_conn = "";
                            $sql_da_conn = "SELECT dacode, name, section_name FROM main a LEFT JOIN master.users b ON dacode = b.usercode LEFT JOIN master.usersection us ON b.section=us.id WHERE diary_no = '" . $row_conn["diary_no"] . "' and dacode!=0 ";

                            $results_da_conn = $this->db->query($sql_da_conn);
                            $row_da_conn = $results_da_conn->getRowArray();
                            if (!empty($row_da_conn)) {

                                $da_name_conn = "<font color='blue' style='font-size:12px;font-weight:bold;'>" . $row_da_conn["name"] . "</font><br>";

                                if ($row_da_conn["dacode"] != "")
                                    $da_name_conn .= "<font style='font-size:12px;font-weight:bold;'> [SECTION : </font><font color='red' style='font-size:12px;font-weight:bold;'>" . $row_da_conn["section_name"] . "</font><font style='font-size:12px;font-weight:bold;'>]</font>";
                            }
                            //DA NAME ENDS FOR CONNECTED
                            if (($cntt % 2) == 1)
                                $bgcolor = "#FDFEFF";
                            else
                                $bgcolor = "#F5F6F7";
                            if ($row_conn["mc1"] == "M")
                                $t_mc = "<font style='color:blue;'>M</font>";
                            else {
                                $t_mc = "<font style='color:blue;'>" . $row_conn["conn_type"] . "</font>";
                            }


                            if ($row_conn["diary_no"] == $diaryno)
                                $output .= '<tr height="25px" bgcolor="' . $bgcolor . '"><td><b>' . $cntt . '</b></td><td><b>' . $t_mc . '</b></td><td>' . $row_conn['case_no']  . '/' . $row_conn['year'] . '<br><font size=-1 color=grey>(' . $row_conn["diary_no_rec_date"] . ')</font></br>' . $t_fil_no . '</td><td>' . $row_conn['pet_name'] . ' vs.<br>' . $row_conn['res_name'] . '</td><td align=center>';
                            else
                                $output .= '<tr height="25px" bgcolor="' . $bgcolor . '"><td><b>' . $cntt . '</b></td><td><b>' . $t_mc . '</b></td><td><a data-animation="fade" data-reveal-id="myModal" onclick="return call_f1(' . $row_conn["case_no"] . ',' . $row_conn["year"] . ',\'\',\'\',\'\');" href="#">' . $row_conn['case_no']  . '/' . $row_conn['year'] . '</a><br><font size=-1 color=grey>(' . $row_conn["diary_no_rec_date"] . ')</font></br>' . $t_fil_no . '</td><td>' . $row_conn['pet_name'] . ' vs.<br>' . $row_conn['res_name'] . '</td><td align=center>';

                            if ($row_conn["mc1"] != "M") {
                                if ($row_conn['list'] == 'N')
                                    $output .= "<font style='color:red;'>N</font>";
                                else
                                    $output .= "<font color:red;>Y</font>";
                            } else {
                                $output .= "<font style='color:red;'>-</font>";
                            }
                            $output .= '</td><td align=center>';
                            if ($row_conn['c_status'] == 'D')
                                $output .= "<font style='color:red;'>D</font>";
                            else
                                $output .= "<font color:red;>P</font>";
                            $t_bfnbf = "";
                            ////IA CONN CASES
                            $ia_conn = "";
                            
                            $row_conn_diary_no =  $row_conn["diary_no"];
                            $result_ia = is_data_from_table('docdetails', " doccode='8' and diary_no='$row_conn_diary_no' and display='Y' order by ent_dt ", " * ", 'A');
                            if (!empty($result_ia)) {
                                foreach ($result_ia as $row_ia) {
                                    $docnum = $row_ia['docnum'];
                                    $docyear = $row_ia['docyear'];
                                    $doccode = $row_ia['doccode'];
                                    $doccode1 = $row_ia['doccode1'];
                                    $docdesc1 = $row_ia['other1'];
                                     $docdesc = "";
                                    $row_docm = is_data_from_table('master.docmaster', " doccode='" . $doccode . "' and doccode1='" . $doccode1 . "' and display='Y' ", " * ", '');
                                    if (!empty($row_docm)) {
                                        $docdesc = $row_docm['docdesc'];
                                    }
                                    if (trim($docdesc) == 'OTHER')
                                        $docdesc = $docdesc1;
                                    if (trim($docdesc) == 'XTRA')
                                        $docdesc = $other1;
                                    if ($row_ia['iastat'] == "D")
                                        $iastat = "(<font color=red>" . $row_ia['iastat'] . "</font>)";
                                    else
                                        $iastat = "(<font color=blue>" . $row_ia['iastat'] . "</font>)";
                                    if ($ia_conn == "")
                                        $ia_conn .= $docnum . "/" . $docyear . " " . $iastat . " <br><font color=green>" . $docdesc . "</font>";
                                    else
                                        $ia_conn .= "<br>" . $docnum . "/" . $docyear . " " . $iastat . " <br><font color=green>" . $docdesc . "</font>";
                                }
                            }
                            ////IA CONN CASES
                            $t_bfnbf = "";
                            if ($row_conn['c_status'] != 'D') {
                                $stat_conn = "";
                                
                                $diary_no = $row_conn["diary_no"];
                                $row_stat = is_data_from_table('brdrem', " diary_no='" . $diary_no . "' ", " remark ", '');
                                if (!empty($row_stat)) {
                                    
                                    $t_bfnbf = $row_stat["remark"];
                                }
                            }

                            $enteredby = $row_conn['username'] . '<br>' . $row_conn['endt'];
                            $output .= '</td><td>' . $t_bfnbf . '</td><td>' . $ia_conn . '</td><td>' . $da_name_conn . '</td><td>' . $enteredby . '</td></tr>';
                        }
                        $output .= '</table>';
                    } else {
                        $output .= '<p align=center><font color=red><b>CONNECTED MATTERS NOT FOUND</b></font></p>';
                    }
                } else
                    $output .= '<p align=center><font color=red><b>CONNECTED MATTERS NOT FOUND</b></font></p>';
                //CONNECTED MATTERS
                $output .= '<div>';


                $output .= '</div> ';
            }

            return $output;

            exit();
        }
        exit();
    }




    public function get_listings()
    {
        //die('Working on ..');
        if ($this->request->getMethod() === 'post') {
            $diaryno = $this->request->getPost('diaryno');
            $ucode = session()->get('login')['usercode'];
            $output = "";
            //Listing Start
             
            $result_listing  = $this->Model_case_status->getHeardtWithUser($diaryno);

            
            $result_listing1  = $this->Model_case_status->getLastHeardtWithUser($diaryno);
           
            $subhead = "";
            $next_dt = "";
            $lo = "";
            $sj = "";
            $bt = "";
            $status_list = $status_list1 = 0;

                $t_table = '<table class="table table-striped custom-table table-hover dt-responsive  table_tr_th_w_clr c_vertical_align" width="100%">';
                $t_table .= "<thead>
                        <tr>
                            <th align='center'>CL Date</th>
                            <th>Misc./Regular</th>
                            <th>Stage</th>
                            <th>Purpose</th>
                            <th align='center'>Proposed/ List in</th>
                            <th align='center'>Judges</th>
                            <th>IA</th>
                            <th>Remarks</th>
                            <th>Updated By</th>
                            <th>Listed</th>
                        </tr>
                    </thead>";
                    $check_drop = "";
            if (!empty($result_listing)) 
            {                
                foreach ($result_listing as $row_listing) 
                {
                    $listed_ia = $row_listing['listed_ia'];
                    if ($row_listing['mainhead'] == "M")
                        $t_mainhead = "Misc.";
                    if ($row_listing['mainhead'] == "F")
                        $t_mainhead = "Regular";
                    if ($row_listing['mainhead'] == "L")
                        $t_mainhead = "Lok Adalat";
                    if ($row_listing['mainhead'] == "S")
                        $t_mainhead = "Mediation";
                    $t_stage = "";
                    $subhead = $row_listing['subhead'];
                    if ($row_listing['mainhead'] == "M") {
                        $t_stage = get_stage($row_listing['subhead'], 'M');
                    }
                    if ($row_listing['mainhead'] == "F") {
                        $t_stage = get_stage($row_listing['subhead'], 'F');
                    }

                    $list_printed = check_list_printed($row_listing['roster_id'], $row_listing['mainhead'], $row_listing['clno'], $row_listing['main_supp_flag'], $row_listing['next_dt']);
                    //if ((($list_printed == 'YES') && ($ucode == '1' || $ucode = 49 || $ucode == 47 || $ucode == 723 || $ucode == 757 || $ucode == 742 || $ucode == 747 ||  $ucode = 935 || $ucode = 1486 | $ucode == 469 or $ucode == 744 or $ucode == 747 or $ucode == 633 or $ucode == 762 or $ucode == 10606 or $ucode == 10605 or $ucode == 10674 or $ucode == 10675 or $ucode == 10705 or $ucode == 10656 or $ucode == 10670 or $ucode == 10730 or $ucode == 10749 or $ucode == 3113)) and $row_listing['clno'] != 0 and $row_listing['brd_slno'] != 0 and ($row_listing['main_supp_flag'] == "1" or $row_listing['main_supp_flag'] == "2") && $row_listing['judges'] != '' && $row_listing['judges'] != '0') {
                    if (($list_printed == 'YES') && $row_listing['clno'] != 0 and $row_listing['brd_slno'] != 0 and ($row_listing['main_supp_flag'] == "1" or $row_listing['main_supp_flag'] == "2") && $row_listing['judges'] != '' && $row_listing['judges'] != '0') {    
                        $listed = "<font color=red>LISTED</font>";
                    } else {
                        $listed = "";
                    }
                    
                    $next_dt = $row_listing['next_dt'];
                    $lo = $row_listing['listorder'];
                    $sj = $row_listing['sitting_judges'];
                    $bt = $row_listing['board_type'];
                    if ($bt == 'J')
                        $bt = 'Judge';
                    else if ($bt == 'C')
                        $bt = 'Chamber';
                    else if ($bt == 'R')
                        $bt = 'Registrar';
                    else
                        $bt = '';
                   
                    if ($row_listing['judges'] != '' and $row_listing['judges'] != '0') {
                        $cr = get_case_remarks($row_listing['diary_no'], $row_listing['next_dt'], $row_listing['judges'], $row_listing['brd_slno']);
                    } else {
                        $cr = "";
                    }
                   
                    
                    if ($row_listing['clno'] != 0 and $row_listing['roster_id'] != 0 and ($row_listing['judges'] != '' and $row_listing['judges'] != 0) and ($row_listing['main_supp_flag'] == 1 or $row_listing['main_supp_flag'] == 2)) {
                        $check_drop = check_drop($row_listing['diary_no'], $row_listing['next_dt'], $row_listing['roster_id'], $row_listing['brd_slno']);
                    }

                    //if (($row_listing['main_supp_flag'] == "1" or $row_listing['main_supp_flag'] == "2") and (($list_printed == 'YES') and ($ucode == 1 or $ucode == 47 or $ucode == 723 or $ucode == 757 or $ucode = 49 or $ucode == 742 or $ucode == 747  or $ucode == 744  or  $ucode == 146 or  $ucode = 935 or $ucode = 1486 or $ucode == 469 or $ucode == 633 or $ucode == 742 or $ucode == 762 or $ucode == 10606 or $ucode == 10605 or $ucode == 10674 or $ucode == 10675 or $ucode == 10705 or $ucode == 10656 or $ucode == 10670 or $ucode == 10730 or $ucode == 10749 or $ucode == 3113))) {
                    if (($row_listing['main_supp_flag'] == "1" or $row_listing['main_supp_flag'] == "2") && ($list_printed == 'YES') ) {
                        $judgesnames = get_judges($row_listing['judges']);
                    } else {
                        $judgesnames = "";
                    }
                    
                    $cr1 = explode("?", $cr);
                    $cr_1 = $cr1[0] ?? '';
                    $cr_his1 = $cr1[1] ?? '';
                    $t = $cr1[2] ?? '';
                    $n = $cr1[3] ?? '';


                    
                    $result_array = getCaseStatusFlag();

                    //var_dump($result_array);
                    if (($result_array['display_flag'] == '1' || in_array($ucode, explode(',', $result_array['always_allowed_users'])))) {

                        if ($t > 0) {
                            $t_table .= "<tr><td align='center'>" . change_date_format($row_listing['next_dt']) . "</td><td>" . $t_mainhead . "</td><td>" . $t_stage . "</td>"
                                . "<td>" . get_purpose($row_listing['listorder']) . "</td><td align='center'>" . $bt . "</td><td align='center'>" . $judgesnames . "</td>"
                                . "<td align='center'>" . $row_listing['listed_ia'] . "</td> <div class='content'><td align='center'>" . $cr_1 . $check_drop . " <div class='content'>
                                        <a href='#' data-toggle='tooltip' title='$cr_his1'>History</td></a>
                                    </div>";
                        } else {
                            $t_table .= "<tr><td align='center'>" . change_date_format($row_listing['next_dt']) . "</td><td>" . $t_mainhead . "</td><td>" . $t_stage . "</td>"
                                . "<td>" . get_purpose($row_listing['listorder']) . "</td><td align='center'>" . $bt . "</td><td align='center'>" . $judgesnames . "</td>"
                                . "<td align='center'>" . $row_listing['listed_ia'] . "</td> <div class='content'><td align='center'>" . $cr_1 . $check_drop . " <div class='content'>
                                    </td>
                                </div>";
                        }
                        $t_table .= "</td>"; ?>

                        </div>
                        <?php

                        $t_table .= "<td>";

                        $cr1 = explode("?", $cr);
                        $cr_1 = $cr1[0] ?? '';
                        $cr_his1 = $cr1[1] ?? '';
                        $t = $cr1[2] ?? '';
                        $n = $cr1[3] ?? '';

                        if ($row_listing['ent_dt'] == '' || $row_listing['ent_dt'] == '' || $row_listing['ent_dt'] == NULL) {
                            if (($row_listing['name'] == '' || $row_listing['name'] == NULL))
                                $t_table .= "";
                            else
                                $t_table .= $row_listing['name'];
                            if (($row_listing['section_name'] == '' || $row_listing['section_name'] == NULL))
                                $t_table .= "";
                            else
                                $t_table .= " [" . $row_listing['section_name'] . "]";
                        } else
                            $t_table .= $row_listing['name'] . " [" . $row_listing['section_name'] . "] ON " . date('d-m-Y h:i:s A', strtotime($row_listing['ent_dt']));
                        $t_table .= "</td>";
                        $t_table .= "<td>" . $listed . "</td>";
                        $t_table .= "</tr>";
                    }
                    // else { if((($list_printed == 'YES') and ($ucode == '1' OR $ucode == 469 OR $ucode == 559)) and $row_listing['clno']!=0 and $row_listing['brd_slno']!=0 and ($row_listing['main_supp_flag']=="1" or $row_listing['main_supp_flag']=="2") and $row_listing['judges']!='' and $row_listing['judges']!='0'){
                    else {
                        if (($list_printed == 'YES') and $row_listing['clno'] != 0 and $row_listing['brd_slno'] != 0 and ($row_listing['main_supp_flag'] == "1" or $row_listing['main_supp_flag'] == "2") and $row_listing['judges'] != '' and $row_listing['judges'] != '0') {
                            if ($t > 0) {
                                $t_table .= "<tr><td align='center'>" . change_date_format($row_listing['next_dt']) . "</td><td>" . $t_mainhead . "</td><td>" . $t_stage . "</td><td>" . get_purpose($row_listing['listorder']) . "</td><td align='center'>" . $bt . "</td><td>" . $judgesnames . "</td><td align='center'>" . $row_listing['listed_ia'] . "</td><td align='center'>" . $cr_1 . $check_drop . " <div class='content'>
                 <a href='#' data-toggle='tooltip' title='$cr_his1'>History</td></a>";
                            } else {
                                $t_table .= "<tr><td align='center'>" . change_date_format($row_listing['next_dt']) . "</td><td>" . $t_mainhead . "</td><td>" . $t_stage . "</td><td>" . get_purpose($row_listing['listorder']) . "</td><td align='center'>" . $bt . "</td><td>" . $judgesnames . "</td><td align='center'>" . $row_listing['listed_ia'] . "</td><td align='center'>" . $cr_1 . $check_drop . " </td></div>";
                            }
                            $t_table .= "<td><font style='font-size:10px;'>";
                            if ($row_listing['ent_dt'] == '' || $row_listing['ent_dt'] == '' || $row_listing['ent_dt'] == NULL) {
                                if (($row_listing['name'] == '' || $row_listing['name'] == NULL))
                                    $t_table .= "";
                                else
                                    $t_table .= $row_listing['name'];
                                if (($row_listing['section_name'] == '' || $row_listing['section_name'] == NULL))
                                    $t_table .= "";
                                else
                                    $t_table .= " [" . $row_listing['section_name'] . "]";
                            } else
                                $t_table .= $row_listing['name'] . " [" . $row_listing['section_name'] . "] ON " . date('d-m-Y h:i:s A', strtotime($row_listing['ent_dt']));
                            $t_table .= "</font></td>";
                            $t_table .= "<td>" . "<font color=red>LISTED</font>" . "</td>";
                            $t_table .= "</tr>";
                        }
                    }
                }
            }else{
                $status_list = 1;
            }

            
            if (!empty($result_listing1)) {

                foreach ($result_listing1 as $row_listing1) {
                    $check_drop1 = "";
                    if ($row_listing1['mainhead'] == "M")
                        $t_mainhead1 = "Misc.";
                    if ($row_listing1['mainhead'] == "F")
                        $t_mainhead1 = "Regular";
                    if ($row_listing1['mainhead'] == "L")
                        $t_mainhead1 = "Lok Adalat";
                    if ($row_listing1['mainhead'] == "S")
                        $t_mainhead1 = "Mediation";
                    $t_stage1 = "";
                    if ($row_listing1['mainhead'] == "M") {
                        $t_stage1 = get_stage($row_listing1['subhead'], 'M');
                    }
                    if ($row_listing1['mainhead'] == "F") {
                        $t_stage1 = get_stage($row_listing1['subhead'], 'F');
                    }
                    $bt1 = $row_listing1['board_type'];
                    if ($bt1 == 'J')
                        $bt1 = 'Judge';
                    else if ($bt1 == 'C')
                        $bt1 = 'Chamber';
                    else if ($bt1 == 'R')
                        $bt1 = 'Registrar';
                    else
                        $bt1 = '';
                    if ($row_listing1['judges'] != '' and $row_listing1['judges'] != '0') {
                        $cr = get_case_remarks($row_listing1['diary_no'], $row_listing1['next_dt'], $row_listing1['judges'], $row_listing1['brd_slno']);
                    } else {
                        $cr = "";
                    }


                    $cr1 = explode("?", $cr);
                    $cr_1 = $cr1[0] ?? '';
                    $cr_his1 = $cr1[1] ?? '' ;
                    $t = $cr1[2] ?? '';
                    $n = $cr1[3] ?? '';


                    $list_printed = check_list_printed($row_listing1['roster_id'], $row_listing1['mainhead'], $row_listing1['clno'], $row_listing1['main_supp_flag'], $row_listing1['next_dt']);
                    if ((($list_printed == 'YES')) and  $row_listing1['clno'] != 0 and $row_listing1['brd_slno'] != 0 and ($row_listing1['main_supp_flag'] == "1" or $row_listing1['main_supp_flag'] == "2") and $row_listing1['judges'] != '' and $row_listing1['judges'] != '0') {
                        $listed1 = "<font color=red>LISTED</font>";
                    } else {
                        $listed1 = "";
                    }
                    if ($row_listing1['clno'] != 0 and $row_listing1['roster_id'] != 0 and ($row_listing1['judges'] != '' and $row_listing1['judges'] != 0) and ($row_listing1['main_supp_flag'] == 1 or $row_listing1['main_supp_flag'] == 2)) {
                        $check_drop1 = check_drop($row_listing1['diary_no'], $row_listing1['next_dt'], $row_listing1['roster_id'], $row_listing1['brd_slno']);
                    }
                    if (($row_listing1['main_supp_flag'] == "1" or $row_listing1['main_supp_flag'] == "2") and (($list_printed == 'YES') )) {
                        $judgesnames1 = get_judges($row_listing1['judges']);
                    } else {
                        $judgesnames1 = "";
                    }
                   
                   $result_array = getCaseStatusFlag();
                    if (($result_array['display_flag'] == '1' || in_array($ucode, explode(',', $result_array['always_allowed_users'])))) {
                        if ($t > 0) {
                            $t_table .= "<tr><td align='center'>" . change_date_format($row_listing1['next_dt']) . "</td><td>" . $t_mainhead1 . "</td><td>" . $t_stage1 . "</td>"
                                . "<td>" . get_purpose($row_listing1['listorder']) . "</td><td align='center'>" . $bt1 . "</td><td align='center'>" . $judgesnames1 . "</td>"
                                . "<td align='center'>" . $row_listing1['listed_ia'] . "</td></td><td align='center'>" . $cr_1 . $check_drop . " <div class='content'>
                 <a href='#' data-toggle='tooltip' title='$cr_his1'>History</td></a>
            
               </div>";
                        } else {
                            $t_table .= "<tr><td align='center'>" . change_date_format($row_listing1['next_dt']) . "</td><td>" . $t_mainhead1 . "</td><td>" . $t_stage1 . "</td>"
                                . "<td>" . get_purpose($row_listing1['listorder']) . "</td><td align='center'>" . $bt1 . "</td><td align='center'>" . $judgesnames1 . "</td>"
                                . "<td align='center'>" . $row_listing1['listed_ia'] . "</td></td><td align='center'>" . $cr_1 . $check_drop . " <div class='content'>
            
            
               </div>";
                        }
                        $t_table .= "<td>";

                        $cr1 = explode("?", $cr);
                        $cr_1 = $cr1[0] ?? '';
                        $cr_his1 = $cr1[1] ?? '';
                        $t = $cr1[2] ?? '';
                        $n = $cr1[3] ?? '';
                        if ($row_listing1['ent_dt'] == '' || $row_listing1['ent_dt'] == '' || $row_listing1['ent_dt'] == NULL) {
                            if (($row_listing1['name'] == '' || $row_listing1['name'] == NULL))
                                $t_table .= "";
                            else
                                $t_table .= $row_listing1['name'];
                            if (($row_listing1['section_name'] == '' || $row_listing1['section_name'] == NULL))
                                $t_table .= "";
                            else
                                $t_table .= " [" . $row_listing1['section_name'] . "]";
                        } else
                            $t_table .= $row_listing1['name'] . " [" . $row_listing1['section_name'] . "] ON " . date('d-m-Y h:i:s A', strtotime($row_listing1['ent_dt']));
                        $t_table .= "</td>";
                        $t_table .= "<td>" . $listed1 . "</td>";
                        $t_table .= "</tr>";
                    } else {
                        if (($row_listing1['clno'] != 0 and $row_listing1['brd_slno'] != 0 and ($row_listing1['main_supp_flag'] == "1" or $row_listing1['main_supp_flag'] == "2") and $row_listing1['judges'] != '' and $row_listing1['judges'] != '0')) {
                            if ($t > 0) {
                                $t_table .= "<tr><td align='center'>" . change_date_format($row_listing1['next_dt']) . "</td><td>" . $t_mainhead1 . "</td><td>" . $t_stage1 . "</td><td>" . get_purpose($row_listing1['listorder']) . "</td><td align='center'>" . $bt1 . "</td><td>" . $judgesnames1 . "</td><td align='center'>" . $row_listing1['listed_ia'] . "</td>/td><td align='center'>" . $cr_1 . $check_drop . " <div class='content'>
                 <a href='#' data-toggle='tooltip' title='$cr_his1'>History</td></a></div>";
                            } else {
                                $t_table .= "<tr><td align='center'>" . change_date_format($row_listing1['next_dt']) . "</td><td>" . $t_mainhead1 . "</td><td>" . $t_stage1 . "</td><td>" . get_purpose($row_listing1['listorder']) . "</td><td align='center'>" . $bt1 . "</td><td>" . $judgesnames1 . "</td><td align='center'>" . $row_listing1['listed_ia'] . "</td><td>" . $cr_1 . $check_drop . "</td>";
                            }
                            $t_table .= "<td><font style='font-size:10px;'>";
                            if ($row_listing1['ent_dt'] == '' || $row_listing1['ent_dt'] == '' || $row_listing1['ent_dt'] == NULL) {
                                if (($row_listing1['name'] == '' || $row_listing1['name'] == NULL))
                                    $t_table .= "";
                                else
                                    $t_table .= $row_listing1['name'];
                                if (($row_listing1['section_name'] == '' || $row_listing1['section_name'] == NULL))
                                    $t_table .= "";
                                else
                                    $t_table .= " [" . $row_listing1['section_name'] . "]";
                            } else
                                $t_table .= $row_listing1['name'] . " [" . $row_listing1['section_name'] . "] ON " . date('d-m-Y h:i:s A', strtotime($row_listing1['ent_dt']));
                            $t_table .= "</font></td>";
                            $t_table .= "<td><font color=red>LISTED</font></td>";
                            $t_table .= "</tr>";
                        }
                    }
                }

              
            }else{
                $status_list1 = 1;
            }

            if($status_list == 1 && $status_list1 == 1)
            {
                $t_table .= '<tr><td colspan="100%">No record found..</td></tr>';
            }

            $t_table .= "</table>";
            $output .= $t_table;
            //Listing End
            return $output;


            exit();
        }
        exit();
    }


    public function get_ia()
    {

        if ($this->request->getMethod() === 'post') {
            $diaryno = $this->request->getPost('diaryno');
            
            $diary_details = is_data_from_table('main', ['diary_no' => $diaryno], '*', 'R');
            $flag = "";
            if (empty($diary_details)) {
                $flag = "_a";                
            } 
            $data['flag'] = $flag;
            $data['diaryno'] =$_POST['diaryno'];
            $data['Model_case_status'] = $this->Model_case_status;
            $data['results_notices'] = $this->Model_case_status->getNoticesData($diaryno,$flag);              
            return view('Common/Component/case_status/get_ia',$data);  
            exit();
        }
        exit();



       /* if ($this->request->getMethod() === 'post') {
            $diaryno = $this->request->getPost('diaryno');

            $output = "";
            $chk1 = $chk2 = '';
            $result_ia =  $this->Model_case_status->getIaUser($diaryno);
           
            if (!empty($result_ia)) {
                $output.= '<table class="table table-striped custom-table table-hover dt-responsive" border="0" style="width:100%">';
                $output.= '<thead><tr><th colspan="10" align="center">INTERLOCUTARY APPLICATION(s)</th></tr>';
                $output.= '<tr><th>Sr. No.</th><th width="75px" align="center">Reg. No./I.A. No.</th><th>Particular</th><th>Remark</th><th>Filed By</th><th>Filing/Reg. Date</th><th>Status.</th><th>Entered By</th><th>Last Modified By</th><th>Disposed By</th></tr></thead>';
                $cntt = 0;
                foreach ($result_ia as $row_ia) {
                    $cntt++;
                    $remark_ia = $row_ia['remark'];
                    $docnum = $row_ia['docnum'];
                    $docyear = $row_ia['docyear'];
                    $doccode = $row_ia['doccode'];
                    $doccode1 = $row_ia['doccode1'];
                    $iastat = $row_ia['iastat'];
                    $filedby = $row_ia['filedby'];
                    $enteron=$row_ia['username'] . '<br>' . date("d-m-Y H:i:s",strtotime($row_ia['ent_dt']));
                    $other1 = $row_ia['other1'];
                    $modifiedby='';
                    if($row_ia['modify_username']!=null)
                    $modifiedby=$row_ia['modify_username']. '<br>' . date("d-m-Y H:i:s",strtotime($row_ia['lst_mdf']));
                    if ($filedby == "")
                        $filedby = "-";
                    $fildt = $row_ia['ent_dt'];
                    $fildt1 = substr($fildt, 8, 2) . '-' . substr($fildt, 5, 2) . '-' . substr($fildt, 0, 4);
                    $docdesc1 = $row_ia['other1'];
                     
                    $row_docm = is_data_from_table('master.docmaster',  " doccode= $doccode and doccode1=$doccode1 and display='Y' ", '*', $row = '');

                    $docdesc = "";
                    if (!empty($row_docm)) {
                      
                        $docdesc = $row_docm['docdesc'];
                    }
                    $disposed_by='';
                    if($row_ia['iastat']=='D')
                        $disposed_by=$row_ia['disposedby']. '<br>' . date("d-m-Y H:i:s",strtotime($row_ia['lst_mdf']));
                    if (trim($docdesc) == 'OTHER')
                        $docdesc = $docdesc1;
                    if (trim($docdesc) == 'XTRA')
                        $docdesc = $other1;
                    if (($cntt % 2) == 1)
                        $bgcolor = "#FDFEFF";
                    else
                        $bgcolor = "#F5F6F7";
                    $output.= '<tr height="25px" bgcolor="' . $bgcolor . '"><td>' . $cntt . '</td><td>' . $docnum . "/" . $docyear . '</td><td>' . $docdesc . '</td><td>'.$remark_ia.'</td><td>' . $filedby . '</td><td>' . $fildt1 . '</td><td>' . $iastat . '</td><td>' . $enteron . '</td><td>'.$modifiedby.'</td><td>'.$disposed_by.'</td></tr>';
                }
                $output.= '</table><br>';
            }
            else {
                $chk1="NF";
            }
            
            $result_dms =  $this->Model_case_status->getDMSData($diaryno);
            if (!empty($result_dms)) {
                $output.= '<table class="table table-striped custom-table table-hover dt-responsive" border="0" style="width:100%">';
                $output.= '<thead><tr><th colspan="6" align="center">OTHER DOCUMENT(s)</th></tr>';
                $output.= '<tr ><th>Doc. No.</th><th>Document Type</th><th>Filed By</th><th>Filing Date</th><th>Enter By</th><th>Modified By</th></tr></thead>';
                $cntt = 0;
                foreach ($result_dms as $row_dms) {
                    $cntt++;
                    $docnum = $row_dms['docnum'];
                    $docyear = $row_dms['docyear'];
                    $doccode = $row_dms['doccode'];
                    $doccode1 = $row_dms['doccode1'];
                    $iastat = $row_dms['iastat'];
                    $filedby = $row_dms['filedby'];
                    $enterby = $row_dms['username'] . '<br>' . $row_dms['entdt'];
                    if ($filedby == "")
                        $filedby = "-";
                    $forresp = $row_dms['forresp'];
                    $feemode = $row_dms['feemode'];
                    $fildt = $row_dms['entdt'];
                    $modifiedby='';
                    if($row_dms['modify_username']!=null)
                        $modifiedby=$row_dms['modify_username']. '<br>' . date("d-m-Y H:i:s",strtotime($row_dms['lst_mdf']));
                    $fildt1=$fildt;
                    //$fildt1 = substr($fildt, 8, 2) . '-' . substr($fildt, 5, 2) . '-' . substr($fildt, 0, 4);
                    $docdesc1 = $row_dms['other1'];
                     
                    $row_docm = is_data_from_table('master.docmaster',  " doccode= $doccode and doccode1=$doccode1 and display='Y' ", '*', $row = '');
                    $docdesc = "";
                    if (!empty($row_docm)) {                        
                        $docdesc = $row_docm['docdesc'];
                    }
                    if (trim($docdesc) == 'OTHER')
                        $docdesc = $docdesc1;

                    if ($doccode == 7 && $doccode1 == 0)
                        $docno = $docnum . "/" . $docyear . ', Fees Mode: ' . $feemode . ', For Resp: ' . $forresp;
                    else
                        $docno = $docnum . "/" . $docyear;
                    if (($cntt % 2) == 1)
                        $bgcolor = "#FDFEFF";
                    else
                        $bgcolor = "#F5F6F7";
                    $output.= '<tr height="25px" bgcolor="' . $bgcolor . '"><td>' . $docno . '</td><td>' . $docdesc . '</td><td>' . $filedby . '</td><td>' . $fildt1 . '</td><td>' . $enterby . '</td><td>'.$modifiedby.'</td></tr>';
                }
                $output.= '</table>';
            }
            else {
                $chk2="NF";
            }
            if($chk1=="NF" && $chk2=="NF")
                $output.= '<p style="text-align:center;"><font color=red><b>INTERLOCUTARY APPLICATIONS / DOCUMENTS NOT FOUND</b></font></p>';

            return $output;

            exit();
        } */
        exit();
    }


    public function fetch_defect_details()
    {
        $data['docd_id'] =$_GET['docd_id'];
        $data['diary_no'] =$_GET['diary_no'];
        $data['ia'] =$_GET['ia'] ?? '';
        $data['doc'] =$_GET['doc'] ?? '';
        $data['result'] = $this->Model_case_status->getIaFullDetails($_GET['diary_no'], $_GET['docd_id']);
        
        return view('Common/Component/case_status/fetch_defect_details',$data);  
    }

    public function get_court_fees()
    {
        if ($this->request->getMethod() === 'post') {
            $diaryno = $this->request->getPost('diaryno');

            
            $output = "";
            //echo $sql_cf="Select total_court_fee, court_fee from main where diary_no='".$diaryno."'";
            //$results_cf = mysql_query($sql_cf);
            $row_cf = is_data_from_table('main',  " diary_no= $diaryno ", 'total_court_fee, court_fee ', $row = '');
            if(empty($row_cf))
            {
                $row_cf = is_data_from_table('main_a',  " diary_no= $diaryno ", 'total_court_fee, court_fee ', $row = '');
            }

            $output.= '<div style="text-align:center">';
                if(!empty($row_cf)) 
                {
                  
                    //$row_cf = mysql_fetch_array($results_cf);
                    if($row_cf['total_court_fee']!=0)
                        $output.='<p><b>Total Court Fee :</b> '.$row_cf['total_court_fee']."</p>";
                    else
                        $output.='<p><b>Total Court Fee :</b> 0'."</p>";

                    if($row_cf['court_fee']!=0)
                        $output.='<p><b>Court Fee paid :</b> '.$row_cf['court_fee']."</p>";
                    else
                        $output.='<p><b>Court Fee paid :</b> 0'."</p>";
                        
                }else{
                   $output.='<p><b>Total Court Fee :</b> 0'."</p>";
                    $output.='<p><b>Court Fee paid :</b> 0'."</p>";
                }
                $output.= '</div>';
                
            return  $output; 

            exit();
        }
        exit();
    }

    public function get_notices()
    {

        if ($this->request->getMethod() === 'post') {
            $diaryno = $this->request->getPost('diaryno');         

            $data['diaryno'] =$_POST['diaryno'];
            $data['results_notices'] = $this->Model_case_status->getNoticesData($diaryno);              
            return view('Common/Component/case_status/get_notice',$data);  
            exit();
        }
        exit();
    }

    public function get_default()
    {
        if ($this->request->getMethod() === 'post') {
            $diaryno = $this->request->getPost('diaryno');


                $output="";
                $dairy_no=$diaryno;
                    $sno=1;
                    $cn_c='';
                 
                $q_w = $this->Model_case_status->getObjSaveData($diaryno);                

               
                $output.='<fieldset id="fiOD"><legend ><b>Default Details</b></legend>
                            <span id="spAddObj" style="font-size: small;text-transform: uppercase">
                                <table id="tb_nm" class="table table-striped custom-table table-hover dt-responsive" cellpadding="5" cellspacing="5" width="100%">';
                $output.='<thead><tr><th>S.No.</th><th>Default</th><th>Remarks</th><th>Notification Date</th><th>Removed On Date</th></tr></thead>';
                if(!empty($q_w))
                {
                foreach ($q_w as $row1 )
                {
                    if($cn_c=='')
                        $cn_c=$row1['org_id'];
                    else
                        $cn_c=$cn_c.','.$row1['org_id'];
                $output.='<tr><td class="c_vertical_align">';
                $output.= $sno;
                $output.='</td>
                    <td>
                    <span id="spAddObj'.$sno.'">'.$row1['obj_name'].'</span>';
                $output.='<span id="sp_hide'.$sno.'"><br/></span>
                    </td>
                    <td>';
                        $ex_ui=  explode(',',$row1['mul_ent']);
                        $r='';
                        for ($index = 0; $index < count($ex_ui); $index++)
                        {
                            if(trim($ex_ui[$index]==''))
                            {
                                    $r=$r.'-'.',';
                            }
                            else
                                {
                                $r=$r. $ex_ui[$index].',' ;
                                }
                        }
                $output.='<span id="spRema'.$sno.'">'.$row1['remark'].'</span>';

                
                $nd=$row1['save_dt'];
                if($row1['rm_dt']!='')               
                    $rd=$row1['rm_dt'];
                else
                    $rd="";

                $output.="</td><td>".date('d-m-Y',strtotime($nd))."</td><td>".date('d-m-Y',strtotime($rd))."</td>";
                $output.='</tr>';
                    $sno++;
                }               
                
                }else{
                    $output.='<tr><td colspan="100%">No data found...</td></tr>';
                }

                $output.='</table></span></fieldset>';
                return $output;
            exit();
        }
        exit();
    }

    public function get_judgement_order()
    {
        if ($this->request->getMethod() === 'post') {
            $diaryno = $this->request->getPost('diaryno');
 

                    $connected=$condition=$this_no=$DNumber_main='';
                    if($_POST['diaryno']) {
                        $DNumber = $diaryno = $_POST['diaryno'];
                        $this_no = "$DNumber";

                        $result_main = $this->Model_case_status->getConnKeys($diaryno); 

                        if (!empty($result_main)) {
                            foreach ($result_main as $row_main) 
                            {
                                $DNumber_main = $row_main['conn_key'];
                                if ($DNumber_main != '' && $DNumber_main != null) {
                                     
                                    $result_conn_list = $this->Model_case_status->getConnListData($DNumber_main, $diaryno);                                    
                                    if (!empty($result_conn_list)) {
                                        foreach ($result_conn_list as $row_conn_list) 
                                        {
                                            $condition = $row_conn_list['conn_list'];
                                        }
                                    }
                                }

                            }
                        }
                        if(!empty($condition))
                            $connected=trim($condition,',');
                    }

                    if($this_no!=$DNumber_main and  !empty($DNumber_main))
                    {

                        echo '<p align="left"><a href="'.base_url('/Common/Case_status/get_orders?diary_no='.$DNumber_main).'" target="_blank">View Order/Judgments of Main Matter</a></p>';

                        if($connected!=null and $connected!='')
                            echo '<p align="right"><a href="'.base_url('/Common/Case_status/get_orders?diary_no='.$connected).'" target="_blank">View Order/Judgments of Other connected Matters</a></p>';
                    }
                    else if(!empty($DNumber_main)) {
                         echo '<a href="'.base_url('/Common/Case_status/get_orders?diary_no='.$connected).'" target="_blank">View Order/Judgments of connected Matters</a></p>';
                    }
                    echo "\n";
                    if(isset($_GET['diary_no'])) {
                        $diary_no =$_GET['diary_no'];
                    }
                    else
                        $diary_no=$this_no;
                    try {
                    echo "<center>\n <b>Orders/ Judgments of Diary Number: ".substr( $diary_no, 0, strlen($diary_no) -4 ).'/'.substr($diary_no , -4 )."</b></center>";
                   
                   
                     
                   
                    $result_jo = $this->Model_case_status->getJoinData($diaryno);
                    
                    if (!empty($result_jo))
                    {
                    
                    ?>
                    <br>
                    <table class="table table-striped custom-table table-hover dt-responsive" style='width:100%;'>
                        <thead><tr>
                            <th>Date of Judgment/Order</th>
                        </tr></thead>
                        <?php
                        $chk_counter = 0;
                        $temp_var="";
                        foreach ($result_jo as  $row_jo) {
                            $chk_counter++;
                            $rjm=explode("/",$row_jo['jm']);
                            if( $rjm[0]=='supremecourt') {
                                $temp_var='<a href="../jud_ord_html_pdf/'. $row_jo['jm'].'" target="_blank">'.date("d-m-Y", strtotime($row_jo['dated'])).' in D.No. '.$row_jo['d_no'].'/'.$row_jo['d_year'].'</a>&nbsp;&nbsp;['.$row_jo['jo'].']';
                            } else {
                                $temp_var='<a href="../judgment/'. $row_jo['jm'].'" target="_blank">'.date("d-m-Y", strtotime($row_jo['dated'])).' in D.No. '.$row_jo['diary_no'].'/'.$row_jo['d_year'].'</a>&nbsp;&nbsp;['.$row_jo['jo'].']';
                            }
                            if($row_jo['main_or_connected']=='M')
                                $temp_var.='-of Main Case<br>';
                            else
                                $temp_var.='<br>';
 
                                echo   '<tr><td>'.$temp_var.'</td></tr>';

                        } 
                        
                        
                        echo "</table>";
                        }
 

                        else {
                            ?>
                            <div class="col-xs-3">
                                <div style="border-radius:3px; border:#cdcdcd solid 1px; padding:5px;text-align: center;"><?php echo 'No Record Found'; ?></div><br />
                            </div>
                            <?php
                        }
                        ?>
                        <?php
                        } catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }

            exit();
        }
        exit();
    }

    public function get_mention_memo()
    {
        if ($this->request->getMethod() === 'post') {
            $diaryno = $this->request->getPost('diaryno');

            $output = "";               
                $doc = is_data_from_table('mention_memo m', " diary_no = '$diaryno' and m.display='Y' ORDER BY m.date_of_received asc ", 'm.*', $row = 'A');
                
                  $output = "<table class='table table-striped custom-table table-hover dt-responsive' style='width:100%;'>
                    <thead>
                        <th>Case No</th><th>Received Date</th><th>Presented On</th><th>Decided For</th><th>Order</th></thead>";
                        if (!empty($doc)) {
                            foreach($doc as $rowse) {
                                if ($rowse['result'] == 'A') {
                                    $rowse['result'] = 'Allowed';
                                } else if ($rowse['result'] == 'R') {
                                    $rowse['result'] = 'Rejected';
                                } else if ($rowse['result'] == 'N') {
                                    $rowse['result'] = 'As Per Schedule';
                                }
                                if ($rowse['date_on_decided'] != '') {
                                    $AE = date("d-m-Y", strtotime($rowse['date_on_decided']));
                                } else {
                                    $AE = '';
                                }
                                if ($rowse['date_for_decided'] != '') {
                                    $AF = date("d-m-Y", strtotime($rowse['date_for_decided']));
                                } else {
                                    $AF = '';
                                }
                                if($rowse['pdfname']!=''){
                                $href_mm='../jud_ord_html_pdf/'.$rowse['pdfname'];
                                $trg="<a href='$href_mm' target='_blank'>" . "DN: ".get_real_diaryno($rowse['diary_no']).", ".get_casenos_comma($rowse['diary_no']) . "</a>";
                                }
                                else{
                                $href_mm=''; 
                                $trg="DN: ".get_real_diaryno($rowse['diary_no']).", ".get_casenos_comma($rowse['diary_no']);
                                }
                                $output.="<tr><td></td><td>" . $trg. " ".date("d-m-Y", strtotime($rowse['date_of_received'])) . " <b>(" . date("H:i:s", strtotime($rowse['date_of_entry'])) . ")</b></td><td>" . $AE . "</td><td>" . $AF . "</td><td>" . $rowse['result'] . "</td></tr>";
                            }
                        }else{
                            $output.="<tr><td colspan='100%'><font color=red><b>MENTION MEMO NOT FOUND</b></font></td></tr>";
                        }
                    $output.="</table>";
                
                return $output;

            exit();
        }
        exit();
    }

    public function get_restore()
    {
        if ($this->request->getMethod() === 'post') {
            $diaryno = $this->request->getPost('diaryno');
            $ucode = session()->get('login')['usercode'];
            $rest_tab = "";
                   
            $results_rest = is_data_from_table('restored', " diary_no = '$diaryno' order by conn_next_dt desc ", '*', $row = 'A');
            
                        $rest_tab.='<table class="table table-striped custom-table table-hover dt-responsive" style="width:100%;" width="100%" cellspacing="0" cellpadding="1" align="center" border="2">
                <td width="55%" align="center"><font color=blue><em><b> Main Case Disposal Details</b></em></font></td>
                <td width="40%" align="center"><font color=blue><em><b>Restoration Details</b></em></font></td>
                </tr>
                </table>';
                        $rest_tab.='<table class="table table-striped custom-table table-hover dt-responsive" style="width:100%;" width="100%" cellspacing="0" cellpadding="1" align="center" border="1">
                <td width="5%" align="left"><em><b> Sr.No.</b></em></td>
                <td width="10%" align="left"><em><b>Disposal Dt.</b></em></td>
                <td width="20%" align="left"><em><b>Disposed by:</b></em></td>
                <td width="10%" align="left"><em><b>Disposal Type</b></em></td>
                <td width="10%" align="left"><em><b>Disp. Entry Dt.</b></em></td>
                <td width="10%" align="left"><em><b>Restoration Dt.</b></em></td>
                <td width="20%" align="left"><em><b>Restore By Judge:</b></em></td>
                <td width="10%" align="left"><em><b>Restored by Case No.</b></em></td>
                </tr>';
                if (!empty($results_rest)) 
                {
                        $ascnt = 1;
                        foreach ($results_rest as $row_rest) 
                        {
                            $m_dispdt = $row_rest['disp_dt'];
                            $m_dispdt1 = substr($m_dispdt, 8, 2) . '/' . substr($m_dispdt, 5, 2) . '/' . substr($m_dispdt, 0, 4);
                            $m_djud1 = $row_rest['disp_jud1'];
                            $m_djud2 = $row_rest['disp_jud2'];
                            $m_djud3 = $row_rest['disp_jud3'];
                            $m_disptyp = $row_rest['disp_type'];
                            
                            $row9 = is_data_from_table('disposal', " dispcode='$m_disptyp' ", '*', $row = '');
                                if($ucode==203 || $ucode==204 || $ucode==888 || $ucode==912)
                                {                     
                                            if($row9['spk']=="N")
                                                $m_spk=" (Non Speaking)";
                                            else
                                                $m_spk=" (Speaking)";
                                }            
                            $m_dispname = $row9['dispname'].$m_spk;
                            $m_dispedt = substr($row_rest['disp_ent_dt'], 0, 10);
                            $m_dispedt1 = substr($m_dispedt, 8, 2) . '/' . substr($m_dispedt, 5, 2) . '/' . substr($m_dispedt, 0, 4);
                            $m_restdt = $row_rest['conn_next_dt'];
                            $m_restdt1 = substr($m_restdt, 8, 2) . '/' . substr($m_restdt, 5, 2) . '/' . substr($m_restdt, 0, 4);
                            $m_rjud1 = $row_rest['jcode1'];
                            $m_rjud2 = $row_rest['jcode2'];
                            $m_rjud3 = $row_rest['jcode3'];
                            $c_diary_no = $row_rest['conn_key'];
                            $tcaseno = typenoyr1($c_diary_no);
                            $rest_tab.='<tr><td align="left">(' . $ascnt . ')</td><td align="left">' . $m_dispdt1 . '</td><td align="left">';
                            if ($m_djud1 != 0 && $m_djud1 != "") {
                                $rest_tab.=stripslashes(sel_jud1($m_djud1));
                            }
                            if ($m_djud2 != 0 && $m_djud2 != "") {
                                $rest_tab.= " And " . stripslashes(sel_jud1($m_djud2));
                            }
                            if ($m_djud3 != 0 && $m_djud3 != "") {
                                $rest_tab.= " And " . stripslashes(sel_jud1($m_djud3));
                            }
                            $rest_tab.='</td><td align="left">' . $m_dispname . '</td><td align="left">' . $m_dispedt1 . '</td><td align="left">' . $m_restdt1 . '</td><td align="left">';
                            if ($m_rjud1 != 0 && $m_rjud1 != "") {
                                $rest_tab.= stripslashes(sel_jud1($m_rjud1));
                            }
                            if ($m_rjud2 != 0 && $m_rjud2 != "") {
                                $rest_tab.= " And " . stripslashes(sel_jud1($m_rjud2));
                            }
                            if ($m_rjud3 != 0 && $m_rjud3 != "") {
                                $rest_tab.= " And " . stripslashes(sel_jud1($m_rjud3));
                            }
                            $rest_tab.='</td><td align="left">' . $tcaseno . '</td></tr>';
                            ++$ascnt;
                        }
                    }else{
                        $rest_tab.="<tr><td colspan='100%'><font color=red><b>RESTORATION DETAILS NOT FOUND</b></font></td></tr>";
                    }                        
                    
                    return $rest_tab.='</table>';                    

            exit();
        }
        exit();
    }

    public function get_drop()
    {
        if ($this->request->getMethod() === 'post') {
            $diaryno = $this->request->getPost('diaryno');
 

            $result_drop1 = $this->Model_case_status->getDropNoteData($diaryno);
           
            $drop_note1 ="<h2 style='text-align: center;'><font color='red' style='font-weight:bold;'>Case dropped from Hon'ble Court</font></h2>";
            $drop_note1 .="<table class='table table-striped custom-table table-hover dt-responsive' style='width:100%;' width='100%'>";            
            $drop_note1.="<thead><tr><th>CL Date</th><th align=left>&nbsp;Hon'ble Court</th><th>Cause List No.</th><th align=left>&nbsp;Remark</th></tr></thead>";

                if(!empty($result_drop1)){                
                    foreach($result_drop1 as $row_drop1){ 
                        $drop_note1.="<tr><td align=center>".date('d-m-Y',strtotime($row_drop1["cl_date"]))."</td>
                        <td>".stripslashes($row_drop1["jnm"])."</td><td>".$row_drop1["clno"]."</td>
                        <td>".$row_drop1["nrs"]."</td></tr>";
                        $t_drp_jname1=stripslashes($row_drop1["jnm"]);
                    }
               
                }
            else{
                $drop_note1 .='<tr><td colspan="100%"><p align=center><font color=red><b>DROP NOTE NOT FOUND</b></font></p></td></tr>';

            }   
            
            $drop_note1.="</table>";   
            return $drop_note1;

            exit();
        }
        exit();
    }

    public function get_appearance()
    {
        if ($this->request->getMethod() === 'post') {
            $diaryno = $this->request->getPost('diaryno');



            exit();
        }
        exit();
    }

    public function get_office_report()
    {
        if ($this->request->getMethod() === 'post') {
            $diaryno = $this->request->getPost('diaryno');
 
            $office_report = is_data_from_table('office_report_details', " diary_no=$diaryno and display='Y' and web_status=1 ", " substr(diary_no::TEXT, 1, length( diary_no::TEXT) -4 ) as dno, substr(diary_no::TEXT,-4) as d_yr, office_repot_name,office_report_id,order_dt,rec_dt, summary", 'A');
            
            
                echo '<center>
                <h5>Office Report</h5>
                <table width="100%" class="table table-striped custom-table table-hover dt-responsive table_tr_th_w_clr c_vertical_align">';
                echo '<thead><tr><th>S.No.</th><th>Process Id</th><th>Listing Date</th><th>Receiving Date</th><th>Summary</th></tr></thead>';
                if (!empty($office_report)) 
                {
                    $sno = 0;
                    foreach ($office_report as $row) {
                        $sno++;
                        $res_office_report = $row['office_repot_name'];
                        $res_max_o_r = $row['office_report_id'];
                        if ($res_max_o_r == 0)
                            $res_max_o_r = "&nbsp;";
                        $dno = $row['dno'];
                        $d_yr = $row['d_yr'];
                        $order_dt = $row['order_dt'];
                        $rec_dt = $row['rec_dt'];
                        $fil_nm = "../officereport/" . $d_yr . '/' . $dno . '/' . $res_office_report;
                        $pos = stripos($res_office_report, '.pdf');
                        if ($pos !== false) {
                            echo '<tr><td>' . $sno . '</td><td>' . $res_max_o_r . '</td><td><a href="#" onclick="openPDF(\'' . $fil_nm . '\');">' . date('d-m-Y', strtotime($order_dt)) . '</a></td><td>' . date('d-m-Y H:i:s', strtotime($rec_dt)) . '</td><td>' . $row['summary'] . '</td></tr>';
                        } else {
                            echo '<tr><td>' . $sno . '</td><td>' . $res_max_o_r . '</td><td><a href=\'' . $fil_nm . '\' target="popup" onClick=window.open("$fil_nm","popup","width=600, height=400")>' . date('d-m-Y', strtotime($order_dt)) . '</a></td><td>' . date('d-m-Y H:i:s', strtotime($rec_dt)) . '</td><td>' . $row['summary'] . '</td></tr>';
                        }
                    }
                
                }else{
                    echo '<tr><td colspan="100%"><p align=center>No data Found</p></td></tr>';
                }

                echo '</table></center>';

                exit();
            }
        exit();
    }

    public function get_similarities()
    {
        if ($this->request->getMethod() === 'post') {
            $diaryno = $this->request->getPost('diaryno');

            $output="";
            $dairy_no=$diaryno;
            $output.='<div class="cl_center"><h4>Similarities based on State, Bench, Case No. and Judgement Date</h4></div>';
            

            $sql = $this->Model_case_status->getLowerCourtData($diaryno);
            
           
            $output.='<table width="100%" class="table table-striped custom-table table-hover dt-responsive table_tr_th_w_clr c_vertical_align">
                <thead><tr><th>S.No.</th><th>Diary No.</th><th>Registration No.</th>
                    <th>From Court</th><th>State</th><th>Bench</th>
                    <th>Case No.</th><th>Judgement Date</th>
                    <th>Judgement Challenged</th><th>Judgement Type</th>
                    <th>Status</th></tr></thead>';
            
            if(!empty($sql))
            {
                    $s_no=1;
                foreach ($sql as $row)
                {
                 //pr($row);
            $output.='<tr><td>'.$s_no.'</td><td><a href="#" onclick="return call_f1(' . substr($row['c_diary'],0,-4). ','.substr($row['c_diary'],-4).',\'\',\'\',\'\');">'.substr($row['c_diary'],0,-4).'-'.  substr($row['c_diary'],-4).'</a></td><td>';
                        $case_no=$row['short_description'];
                        if($row['fil_no']!='')
                            $case_no=$case_no.'/'.$row['fil_no'];
                        if($row['fil_dt']!='')
                            $case_no=$case_no.'/'.date('Y',strtotime($row['fil_dt']));
                        if($case_no=='')
                        $output.="-";
                        else
                        $output.=$case_no;
                  $name =   (isset($row['name']) && !empty($row['name'])) ? $row['name'] : "";
            $output.='</td><td>'.$row['court_name'].'</td><td>'. $name.'</td>';
            $output.='<td>'.$row['agency_name'].'</td><td>';
            $output.=$row['type_sname'].'-'.$row['lct_caseno'].'-'.$row['lct_caseyear'];
            $output.='</td><td>'.date('d-m-Y',strtotime($row['lct_dec_dt'])).'</td>
                    <td>'.$row['is_order_challenged'].'</td><td>';
                        if($row['full_interim_flag']=='F')
                            $output.= "Final";
                        else 
                            if($row['full_interim_flag']=='I') 
                                $output.= "Interim";
            $output.='</td><td>';
                        if($row['c_status']=='P')
                        $output.="Pending";
                        else  if($row['c_status']=='D')
                        $output.="Disposed";
            $output.='</td></tr>';
                $s_no++;
                }
            
            }
            else 
            {
                $output.= '<tr><td colspan="100%"><p align=center>No data Found</p></td></tr>';
            }
            $output.='</table>';  
            
            /* START ANOTHER */
            
            $output.='<div class="cl_center"><h4>Similarities based on State, district, Police Station and Crime No</h2></div>';
            $sql = $this->Model_case_status->getLowerCourtData($diaryno,1);
            
           
            $output.='<table width="100%" class="table table-striped custom-table table-hover  table_tr_th_w_clr c_vertical_align">
                <thead><tr><th>S.No.</th><th>Diary No.</th><th>Registration No.</th>
                    <th>From Court</th><th>State</th><th>Bench</th><th>Case No.
                    </th><th>Judgement Date</th><th>Judgement Challenged</th>
                    <th>Judgement Type</th><th>Police Station</th><th>Crime No/Year</th>
                    <th>Status</th></tr></thead>';
                    if(!empty($sql))
                    {   
                    $s_no=1;
                foreach ($sql as $row)
                {
                $output.='<tr><td>'.$s_no.'</td><td><a href="#" onclick="return call_f1(' . substr($row['c_diary'],0,-4). ','.substr($row['c_diary'],-4).',\'\',\'\',\'\');">'.
                substr($row['c_diary'],0,-4).'-'.substr($row['c_diary'],-4).'</a> 
                    </td><td>';
                        $case_no=$row['short_description'];
                        if($row['fil_no']!='')
                            $case_no=$case_no.'/'.$row['fil_no'];
                        if($row['fil_dt']!='')
                            $case_no=$case_no.'/'.$row['fil_dt'];
                        if($case_no=='')
                            $output.= "-";
                        else
                        $output.= $case_no;
            $output.='</td><td>'.$row['court_name'].'</td><td>'.$row['name'].'</td><td>';
            $output.=$row['agency_name'];
            $output.='</td><td>'.$row['type_sname'].'-'.$row['lct_caseno'].'-'.$row['lct_caseyear'];
            $output.='</td><td>';
            if($row['lct_dec_dt']!='')
                $output.=date('d-m-Y',strtotime($row['lct_dec_dt']));
            $output.='</td><td>'.$row['is_order_challenged'].'</td><td>';
                        if($row['full_interim_flag']=='F')
                            $output.= "Final";
                        else 
                            if($row['full_interim_flag']=='I') 
                                $output.= "Interim";
            $output.='</td><td>'.$row['policestndesc'].'</td><td>'.$row['crimeno'].'/'.$row['crimeyear'];
            $output.='</td><td>';
                        if($row['c_status']=='P')
                            $output.= "Pending";
                        else  if($row['c_status']=='D')
                            $output.= "Disposed";
            $output.='</td></tr>';
                $s_no++;
                }
           
            }
            else 
            {
                $output.= '<tr><td colspan="100%"><p align=center>No data Found</p></td></tr>';
            }
            $output.='</table>';


            $output.='<div class="cl_center"><h4>Similarities based on Vehicle No.</h2></div>';
                      
            $output.='<table width="100%" class="table table-striped custom-table table-hover table_tr_th_w_clr c_vertical_align">';
                $output.='<thead><tr><th>S.No.</th><th>Diary No.</th><th>Registration No.</th>
                    <th>From Court</th><th>State</th><th>Bench</th><th>Case No.</th>
                    <th>Judgement Date</th><th>Judgement Challenged</th><th>Judgement Type
                    </th><th>Vehicle No.</th><th>Status</th></tr></thead>';
            $sql = $this->Model_case_status->getLowerCourtData($diaryno,2);  
            if(!empty($sql))
            {
                    $s_no=1;
                foreach ($sql as $row)
                {
                $output.='<tr><td>'.$s_no.'</td><td><a href="#" onclick="return call_f1(' . substr($row['c_diary'],0,-4). ','.substr($row['c_diary'],-4).',\'\',\'\',\'\');">' .substr($row['c_diary'],0,-4).'-'.  substr($row['c_diary'],-4).'</a>';
                $output.='</td><td>';
                        $case_no=$row['short_description'];
                        if($row['fil_no']!='')
                            $case_no=$case_no.'/'.$row['fil_no'];
                        if($row['fil_dt']!='')
                            $case_no=$case_no.'/'.$row['fil_dt'];
                        if($case_no=='')
                            $output.= "-";
                        else
                        $output.= $case_no;
            $output.='</td><td>'.$row['court_name'].'</td><td>'.$row['name'];
            $output.='</td><td>'.$row['agency_name'].'</td>
                    <td>'.$row['type_sname'].'-'.$row['lct_caseno'].'-'.$row['lct_caseyear'];
            $output.='</td><td>'.date('d-m-Y',strtotime($row['lct_dec_dt'])); 
            $output.='</td><td>'.$row['is_order_challenged'].'</td><td>';
                        if($row['full_interim_flag']=='F')
                            $output.= "Final";
                        else 
                            if($row['full_interim_flag']=='I') 
                                $output.= "Interim";
            $output.='</td><td>'.$row['code'].'-'.$row['vehicle_no']; 
            $output.='</td><td>';
                        if($row['c_status']=='P')
                            $output.="Pending";
                        else  if($row['c_status']=='D')
                            $output.="Disposed";
            $output.='</td></tr>';
                $s_no++;
                }
            
            }
            else 
            {
                $output.= '<tr><td colspan="100%"><p align=center>No data Found</p></td></tr>';
            }
            $output.='</table>';
            
            $output.='<div class="cl_center"><h4>Similarities based on Court, State, District and Reference No.</h2></div>';
            
            $output.='<table width="100%" class="table table-striped custom-table table-hover table_tr_th_w_clr c_vertical_align">';
            $output.='<thead><tr><th>S.No.</th><th>Diary No.</th><th>Registration No.</th>
                    <th>From Court</th><th>State</th><th>Bench</th><th>Case No.
                    </th><th>Judgement Date</th><th>Judgement Challenged</th>
                    <th>Judgement Type</th><th>Reference Court / State / District / No.
                    </th><th>Status</th></tr></thead>';
            $sql = $this->Model_case_status->getLowerCourtData($diaryno,3); 
            
            if(!empty($sql))
            { 
            $s_no=1;
                foreach($sql as $row)
                {
            $output.='<tr><td>'.$s_no.'</td><td><a href="#" onclick="return call_f1(' . substr($row['c_diary'],0,-4). ','.substr($row['c_diary'],-4).',\'\',\'\',\'\');">';
            $output.=substr($row['c_diary'],0,-4).'-'.  substr($row['c_diary'],-4);
            $output.='</a></td><td>';
                        $case_no=$row['short_description'];
                        if($row['fil_no']!='')
                            $case_no=$case_no.'/'.$row['fil_no'];
                        if($row['fil_dt']!='')
                            $case_no=$case_no.'/'.$row['fil_dt'];
                        if($case_no=='')
                            $output.= "-";
                        else
                        $output.= $case_no;
            $output.='</td><td>'.$row['court_name'].'</td><td>'.$row['name'];
            $output.='</td><td>'.$row['agency_name'].'</td><td>';
            $output.=$row['type_sname'].'-'.$row['lct_caseno'].'-'.$row['lct_caseyear'];
            $output.='</td><td>'.date('d-m-Y',strtotime($row['lct_dec_dt'])); 
            $output.='</td><td>'.$row['is_order_challenged'];
            $output.='</td><td>';
                        if($row['full_interim_flag']=='F')
                            $output.= "Final";
                        else 
                            if($row['full_interim_flag']=='I') 
                                $output.= "Interim";
            $output.='</td><td>';
                    if($row['ref_court']!=0)
                    {
                        
                        $ref_court = $row['ref_court'];
                        $r_court = is_data_from_table('master.m_from_court', " id=$ref_court and display='Y' ", 'court_name', '');
                        $res_court= $r_court['court_name'] ?? '';
                        
                        
                        $ref_state = $row['ref_state'];
                        $r_state = is_data_from_table('master.state', " id_no=$ref_state and display='Y' ", 'name', '');
                        $res_state= $r_state['name'] ?? '';
                        if($row['ref_court']=='3')
                        {
                           
                            $ref_district = $row['ref_district'];
                            $r_district = is_data_from_table('master.state', " id_no=$ref_district and display='Y' ", 'name', '');
                            $res_district= $r_district['name'] ?? '';
                        }
                        else 
                        {
                          
                           $ref_district = $row['ref_district'];
                           $r_district = is_data_from_table('master.state', " id=$ref_district and is_deleted='f' ", 'agency_name', '');
                           $res_district= $r_district['agency_name'] ?? '';
                        }
                          
                            $case_type='';
                        if($row['ref_court']=='4')
                        {                           
                            $ref_case_type = $row['ref_case_type'];
                            $case_type = is_data_from_table('master.casetype', " casecode=$ref_case_type and display='Y' ", 'skey', '');
                            $r_case_type= $case_type['skey'] ?? '';
                        }
                        else 
                        {                           
                            $ref_case_type = $row['ref_case_type'];
                            $case_type = is_data_from_table('master.lc_hc_casetype', " lccasecode=$ref_case_type and display='Y' ", 'type_sname', '');
                            $r_case_type= $case_type['type_sname'] ?? '';
                        }
                       
                    }
                    if($row['ref_court']==0) { $output.= "-";} else { $output.= $res_court ;} 
                    $output.=' / ';
                    if($row['ref_state']==0) { $output.= '-';} else { $output.= $res_state; } 
                    $output.=' / ';
                    if($row['ref_district']==0) { $output.= '-';} else { $output.= $res_district ;} 
                    $output.=' / ';
                    if($row['ref_case_type']==0){ $output.= '';} else { $output.= $r_case_type; } 
                    $output.='-';
                    if($row['ref_case_no']==0) { $output.= "";} else { $output.= $row['ref_case_no']; } 
                    $output.='-';
                    if($row['ref_case_year']==0) { $output.= '';} else { $output.= $row['ref_case_year']; } 
            $output.='</td><td>';
            if($row['c_status']=='P')
                            $output.= "Pending";
                        else  if($row['c_status']=='D')
                            $output.= "Disposed";
            $output.='</td></tr>';
                $s_no++;
                }
            
            }
            else 
            {
                $output.= '<tr><td colspan="100%"><p align=center>No data Found</p></td></tr>';
            }
            $output.='</table>';


            $output.='<div class="cl_center"><h4>Similarities based on Government Notification state, No., Date</h2></div>';
           
            
            
            $output.='<table width="100%" class="table table-striped custom-table table-hover table_tr_th_w_clr c_vertical_align">';
            $output.='<thead><tr><th>S.No.</th><th>Diary No.</th><th>Registration No.</th>
                    <th>From Court</th><th>State</th><th>Bench</th><th>Case No.</th>
                    <th>Judgement Date</th><th>Judgement Challenged</th><th>
                    Judgement Type</th><th>Government Notification State / No. / Date
                    </th><th>Status</th></tr></thead>';
            $sql = $this->Model_case_status->getLowerCourtData($diaryno,4); 
           
            if(!empty($sql))
            {
            $s_no=1;
                foreach ($sql as $row)
                {
            $output.='<tr><td>'.$s_no.'</td><td><a href="#" onclick="return call_f1(' . substr($row['c_diary'],0,-4). ','.substr($row['c_diary'],-4).',\'\',\'\',\'\');">'.substr($row['c_diary'],0,-4).'-'.  substr($row['c_diary'],-4);
            $output.='</a></td><td>';
                        $case_no=$row['short_description'];
                        if($row['fil_no']!='')
                            $case_no=$case_no.'/'.$row['fil_no'];
                        if($row['fil_dt']!='')
                            $case_no=$case_no.'/'.$row['fil_dt'];
            
                        if($case_no=='')
                            $output.= "-";
                        else
                        $output.= $case_no;
            $output.='</td><td>'.$row['court_name'].'</td><td>'.$row['name'].'</td>
                    <td>'.$row['agency_name'].'</td><td>'.$row['type_sname'].'-'.$row['lct_caseno'].'-'.$row['lct_caseyear'];
            $output.='</td><td>'.date('d-m-Y',strtotime($row['lct_dec_dt']));
            $output.='</td><td>'.$row['is_order_challenged'];
            $output.='</td><td>';
                        if($row['full_interim_flag']=='F')
                            $output.= "Final";
                        else 
                            if($row['full_interim_flag']=='I') 
                                $output.= "Interim";
            $output.='</td><td>';
                    if($row['gov_not_state_id']!=0)
                    {
                        
                        $gov_not_state_id = $row['gov_not_state_id'];
                        $r_gov = is_data_from_table('master.state', " id_no=$gov_not_state_id and display='Y' ", 'name', '');
                        $res_r_gov= $r_gov['name'] ?? '';

                    }
            if($row['gov_not_state_id']==0) { $output.= "-";} else { $output.= $res_r_gov;} 
            $output.=' / ';
            if($row['gov_not_case_type']=='') { $output.= '';} else { $output.= $row['gov_not_case_type']; } 
            $output.='-';
            if($row['gov_not_case_no']==0) { $output.= '';} else { $output.= $row['gov_not_case_no']; } 
            $output.='-';
            if($row['gov_not_case_year']==0){ $output.= '';} else { $output.= $row['gov_not_case_year']; } 
            $output.=' / ';
            if($row['gov_not_date']=='') { $output.= '-';} else { $output.= date('d-m-Y',strtotime($row['gov_not_date'])) ;} 
            $output.='</td><td>';
                        if($row['c_status']=='P')
                            $output.= "Pending";
                        else  if($row['c_status']=='D')
                            $output.= "Disposed";
            $output.='</td></tr>';
                $s_no++;
                }
            
            }
            else 
            {
                $output.= '<tr><td colspan="100%"><p align=center>No data Found</p></td></tr>';
            }
            $output.='</table>';


            $output.='<div class="cl_center"><h4>Similarities based on Relied Upon Court, State, District and  No.</h2></div>';            
            
            $output.='<table width="100%" class="table table-striped custom-table table-hover table_tr_th_w_clr c_vertical_align">';
            $output.='<thead><tr><th>S.No.</th><th>Diary No.</th><th>Registration No.</th>
                    <th>From Court</th><th>State</th><th>Bench</th><th>
                        Case No.</th><th>Judgement Date</th><th>Judgement Challenged
                    </th><th>Judgement Type</th><th>Relied Upon Court / State / District / No.
                    </th><th>Status</th></tr></thead>';
            $sql = $this->Model_case_status->getLowerCourtData($diaryno,5); 
           
            if(!empty($sql))
            {
            $s_no=1;
                foreach ($sql as $row)
                {
            $output.='<tr><td>'.$s_no.'</td><td><a href="#" onclick="return call_f1(' . substr($row['c_diary'],0,-4). ','.substr($row['c_diary'],-4).',\'\',\'\',\'\');">'.substr($row['c_diary'],0,-4).'-'.  substr($row['c_diary'],-4);
            $output.='</a></td><td>';
                        $case_no=$row['short_description'];
                        if($row['fil_no']!='')
                            $case_no=$case_no.'/'.$row['fil_no'];
                        if($row['fil_dt']!='')
                            $case_no=$case_no.'/'.$row['fil_dt'];
                        if($case_no=='')
                            $output.= "-";
                        else
                        $output.= $case_no;
            $output.='</td><td>'.$row['court_name'].'
                    </td><td>'.$row['Name'].'</td>
                    <td>'.$row['agency_name'].'</td>
                    <td>'.$row['type_sname'].'-'.$row['lct_caseno'].'-'.$row['lct_caseyear'];
            $output.='</td><td>'.date('d-m-Y',strtotime($row['lct_dec_dt']));
            $output.='</td><td>'.$row['is_order_challenged'].'</td><td>';
                        if($row['full_interim_flag']=='F')
                            $output.= "Final";
                        else 
                            if($row['full_interim_flag']=='I') 
                                $output.= "Interim";
            $output.='</td><td>';
                    if($row['relied_court']!=0)
                    {
                         
                        $relied_court = $row['relied_court'];
                        $r_court = is_data_from_table('master.m_from_court', " id=$relied_court and display='Y' ", 'court_name', '');
                        $res_court= $r_court['court_name'] ?? '';
                         

                        $ref_state = $row['relied_state'];
                        $r_state = is_data_from_table('master.state', " id_no=$ref_state and display='Y' ", 'name', '');
                        $res_state= $r_state['name'] ?? '';

                        if($row['relied_court']=='3')
                        {                                
                                $ref_district = $row['relied_district'];
                                $r_district = is_data_from_table('master.state', " id_no=$ref_district and display='Y' ", 'name', '');
                                $res_district= $r_district['name'] ?? '';
                        }
                        else 
                        {                           

                                $ref_district = $row['relied_district'];
                                $r_district = is_data_from_table('master.ref_agency_code', " id=$ref_district and is_deleted='f' ", 'agency_name', '');
                                $res_district= $r_district['agency_name'] ?? '';
                        }
                            

                            
                            $case_type='';
                        if($row['relied_court']=='4')
                        {
                           
                            $ref_case_type = $row['relied_case_type'];
                            $case_type = is_data_from_table('master.casetype', " casecode=$ref_case_type and display='Y' ", 'skey', '');
                            $r_case_type= $case_type['skey'] ?? '';
                        }
                        else 
                        {
                            
                            $ref_case_type = $row['relied_case_type'];
                            $case_type = is_data_from_table('master.lc_hc_casetype', " lccasecode=$ref_case_type and display='Y' ", 'type_sname', '');
                            $r_case_type= $case_type['type_sname'] ?? '';
                        }
                       

                    }
                    if($row['relied_court']==0) { $output.= "-";} else { $output.= $res_court ;} 
                    $output.=' / ';
                    if($row['relied_state']==0) { $output.= '-';} else { $output.= $res_state; } 
                    $output.=' / ';
                    if($row['relied_district']==0) { $output.= '-';} else { $output.= $res_district ;} 
                    $output.=' / ';
                    if($row['relied_case_type']==0){ $output.= '';} else { $output.= $r_case_type; } 
                    $output.='-';
                    if($row['relied_case_no']==0) { $output.= "";} else { $output.= $row['relied_case_no']; } 
                    $output.='-';
                    if($row['relied_case_year']==0) { $output.= '';} else { $output.= $row['relied_case_year']; }
            $output.='</td><td>';
                        if($row['c_status']=='P')
                            $output.= "Pending";
                        else  if($row['c_status']=='D')
                            $output.= "Disposed";
                    $output.='</td></tr>';
                $s_no++;
                }
           
            }
            else 
            {
                $output.= '<tr><td colspan="100%"><p align=center>No data Found</p></td></tr>';
            }
            $output.='</table>';



            $output.='<div class="cl_center"><h4>Similarities based on Transfer Court, State, District and  No.</h2></div>';           
            
            $output.='<table width="100%" class="table table-striped custom-table table-hover table_tr_th_w_clr c_vertical_align">
                <thead><tr><th>S.No.</th><th>Diary No.</th><th>Registration No.</th>
                    <th>From Court</th><th>State</th><th>Bench</th><th>
                    Transfer from Case No.</th><th>Judgement Date</th>
                    <th>Judgement Challenged</th><th>Judgement Type</th>
                    <th>Transfer To State / District </th><th>Status</th></tr></thead>';
            $sql = $this->Model_case_status->getLowerCourtData($diaryno,6); 
           
            if(!empty($sql))
            {
            $s_no=1;
                foreach ($sql as $row)
                {
            $output.='<tr><td>'.$s_no.'
                    </td><td><a href="#" onclick="return call_f1(' . substr($row['c_diary'],0,-4). ','.substr($row['c_diary'],-4).',\'\',\'\',\'\');">' .substr($row['c_diary'],0,-4).'-'.  substr($row['c_diary'],-4);
            $output.='</a></td><td>';
                        $case_no=$row['short_description'];
                        if($row['fil_no']!='')
                            $case_no=$case_no.'/'.$row['fil_no'];
                        if($row['fil_dt']!='')
                            $case_no=$case_no.'/'.$row['fil_dt'];
                        if($case_no=='')
                            $output.= "-";
                        else
                        $output.= $case_no;
            $output.='</td><td>'.$row['court_name'].'</td><td>'.$row['name'];
            $output.='</td><td>'.$row['agency_name'].'</td><td>';
            $output.=$row['type_sname'].'-'.$row['lct_caseno'].'-'.$row['lct_caseyear'];
            $output.='</td><td>';
            if($row['lct_dec_dt']=='') { $output.= '-';} else { $output.= date('d-m-Y',strtotime($row['lct_dec_dt']));} 
            $output.='</td><td>'.$row['is_order_challenged'].'</td><td>';
                        if($row['full_interim_flag']=='F')
                            $output.= "Final";
                        else 
                            if($row['full_interim_flag']=='I') 
                                $output.= "Interim";
            $output.='</td><td>';
                    if($row['transfer_state']!=0)
                    {
                       

                        $ref_state = $row['transfer_state'];
                        $r_state = is_data_from_table('master.state', " id_no=$ref_state and display='Y' ", 'name', '');
                        $res_state= $r_state['name'] ?? '';

                        if($row['ct_code']=='3')
                        {
                           
                            $transfer_district = $row['transfer_district'];
                            $r_district = is_data_from_table('master.state', " id_no=$transfer_district and display='Y' ", 'name', '');
                            $res_district= $r_district['name'] ?? '';
                        }
                        else 
                        {
                           
                            $transfer_district = $row['transfer_district'];
                            $r_district = is_data_from_table('master.ref_agency_code', " id=$transfer_district and is_deleted='f' ", 'agency_name', '');
                            $res_district= $r_district['agency_name'] ?? '';
                        }
                           
                    }
                    if($row['transfer_state']==0) { $output.= '-';} else { $output.= $res_state; }
                    $output.=' / ';
                    if($row['transfer_district']==0) { $output.= '-';} else { $output.= $res_district ;}
            $output.='</td><td>';
                        if($row['c_status']=='P')
                            $output.= "Pending";
                        else  if($row['c_status']=='D')
                            $output.= "Disposed";
            $output.='</td></tr>';
                $s_no++;
                }
            
            }
            else 
            {
                $output.= '<tr><td colspan="100%"><p align=center>No data Found</p></td></tr>';
            }
            $output.='</table>';
            return $output;

            exit();
        }
        exit();
    }


    public function get_caveat()
    {
        if ($this->request->getMethod() === 'post') {
            $diaryno = $this->request->getPost('diaryno');

            $output = "";
            $s_no = 0;          
            $row = $this->Model_case_status->getMainInfo($diaryno);
           
            if (!empty($row)) 
                {
                    
                    $diary_rec_dt = $row['diary_no_rec_date'];
                    $diary_no_rec_date = date('d-m-Y', strtotime($row['diary_no_rec_date']));
                    $s_no = 1;
                    $is_order_challenged = '';
                    $casetype_id = $row['casetype_id'];
                    if ($casetype_id != '7' && $casetype_id != '8' && $casetype_id != '5' && $casetype_id != '6') {
                        $is_order_challenged = " and a.is_order_challenged='Y' and b.lct_dec_dt IS NOT NULL ";
                    }
                    

                        $sqlResult = $this->Model_case_status->caseStatusCaveat($diaryno,$is_order_challenged);
                        
                       
                            $output.='<table width="100%" class="table table-striped custom-table table-hover dt-responsive table_tr_th_w_clr c_vertical_align">
                                <thead><tr>
                                    <th>
                                        S.No.
                                    </th>
                                    <th>
                                        Caveat No. /<br/>Receiving Date
                                    </th>
                                    <th>
                                        Caveator<br/>Vs<br/>Caveatee
                                    </th>
                                    <th>
                                        From Court
                                    </th>
                                    <th>
                                        State
                                    </th>
                                    <th>
                                        Bench
                                    </th>
                                    <th>
                                        Case No.
                                    </th>
                                    <th>
                                    Judgement Date
                                    </th>
                                    <th>
                                    Advocate
                                    </th>
                                    <th>
                                        Linked with Case and Date
                                    </th>
                                    <th>
                                        Status
                                    </th>
                                </tr></thead>';
                            //$s_no=1;
                        if (!empty($sqlResult)) 
                        {
                            foreach ($sqlResult as $row)
                            {
                                        $output.='<tr><td>'.$s_no.'</td>';
                                        $chk_status=0;
                                        $rep_date_diff = '';
                                       
                                        if(strtotime($diary_rec_dt)>=strtotime($row['diary_no_rec_date']))
                                        {
                                            $date1=date_create($row['diary_no_rec_date']);
                                            $date2=date_create($diary_rec_dt);
                                            $diff=date_diff($date1,$date2);
                                            $date_diff= $diff->format("%R%a days");
                                            $rep_date_diff= intval(str_replace('+','', $date_diff));
                                            //pr($rep_date_diff);
                                            if($rep_date_diff<=90)
                                            {
                                                $chk_status=1;
                                            }
                                        }
                                        else
                                            {
                                                $chk_status=1;                                        
                                            }
                            
                                        $output.='<td>';
                                        $output.=substr($row['c_diary'],0,-4).'-'.  substr($row['c_diary'],-4);
                                        
                                        $caveat_rec_dt= date('d-m-Y',strtotime($row['diary_no_rec_date']));
                                        $output.='<br><span style="color: red" id="sp_diary_no'.$s_no.'">'.date('d-m-Y',strtotime($row['diary_no_rec_date']));
                                        $output.='</span>';
                                        $output.='</td><td>';
                                        
                                                $output.= $row['pet_name'].'<br/>Vs<br/>'.$row['res_name'];
                                        $output.='</td><td>';
                                        $output.= $row['court_name'];
                                        $output.='</td><td>';
                                        
                                        $output.= $row['name'];
                                        $output.='</td><td>';
                            
                                        $output.= $row['agency_name'];
                                        $output.='</td><td>';
                            
                                        if($row['lct_casetype']==50) {
                                            $row['type_sname']= "WNN";
                                        }
                                        if($row['lct_casetype']==51){
                                            $row['type_sname']= "ARN";
                                        }
                            
                                        $output.= $row['type_sname'].'-'.$row['lct_caseno'].'-'.$row['lct_caseyear'];
                                        $output.='</td><td>';
                                        $output.= date('d-m-Y',strtotime($row['lct_dec_dt']));
                                        $output.='</td><td>';
                                       
                                        $advocates=  $this->Model_case_status->getAdvocates($row['c_diary']);
                                        $adv_name='';
                                        foreach ($advocates as $row2) {
                                            if($adv_name=='')
                                                $adv_name=$row2['aor_code'].'-'.$row2['name'];
                                            else
                                                $adv_name=$adv_name.','.$row2['aor_code'].'-'.$row2['name'];
                                        }
                                        $output.= $adv_name;
                                        $output.='</td><td>';
                                        
                                        $c_diary = $row['c_diary'];
                                        $diary_no = is_data_from_table('caveat_diary_matching', "caveat_no=$c_diary and display='Y'", 'diary_no,link_dt', 'A');
                                        $total_diary='';
                                        if(!empty($diary_no))
                                        {
                                                $output.='<table>';
                                                foreach ($diary_no  as $row1) 
                                                {
                                                    $output.='<tr><td>'.substr($row1['diary_no'],0,-4).'-'.  substr($row1['diary_no'],-4);
                                                    $output.='</td><td>';
                                                        if($row1['diary_no']== $diaryno)
                                                        {
                                                        $output.='<span id="sp_cav_diary_lnl_dt'.$s_no.'">'.date('d-m-Y H:i:s',strtotime($row1['link_dt'])).'</span>';
                                                        }
                                                        else
                                                        {
                                                        $output.=date('d-m-Y H:i:s',strtotime($row1['link_dt']));
                                                        }
                                                    $output.='</td><td>';
                                                    $output.='</td></tr>';
                                                }
                                                $output.='</table>';
                                            }
                                        $output.='</td><td>';
                                        
                                      
                                        if(($rep_date_diff <=90 && $chk_status==1) || strtotime($diary_no_rec_date)<=strtotime($caveat_rec_dt))                                        
                                        {
                                        $output.='<span style="color: green">Active</span>';
                                        }
                                        else
                                        {
                                        $output.='<span style="color: red">Expired</span>';
                                        }
                                    $output.='</td></tr>';
                                    $s_no++;
                            }
                            
                               
                        } else 
                        {
                            
                            $output .='<tr><td colspan="100%"><p align=center>No Caveat Found</p></td></tr>';
                        }

                        $output.='</table>';
                }else{

                    $output.='<table width="100%" class="table table-striped custom-table table-hover dt-responsive table_tr_th_w_clr c_vertical_align">
                    <thead><tr>
                        <th>
                            S.No.
                        </th>
                        <th>
                            Caveat No. /<br/>Receiving Date
                        </th>
                        <th>
                            Caveator<br/>Vs<br/>Caveatee
                        </th>
                        <th>
                            From Court
                        </th>
                        <th>
                            State
                        </th>
                        <th>
                            Bench
                        </th>
                        <th>
                            Case No.
                        </th>
                        <th>
                        Judgement Date
                        </th>
                        <th>
                        Advocate
                        </th>
                        <th>
                            Linked with Case and Date
                        </th>
                        <th>
                            Status
                        </th>
                    </tr></thead>';
                    $output .='<tr><td colspan="100%"><p align=center>No Caveat Found</p></td></tr>';
                    $output.='</table>';

                }

                return $output;


            exit();
        }
        exit();
    }


    public function get_gateinfo()
    {
        if ($this->request->getMethod() === 'post') {
            $diaryno = $this->request->getPost('diaryno');

            $output = "";
            //$sql_get_case_info= "select *,case_info.usercode as u , DATE_FORMAT(insert_time,'%d/%m/%Y %r')  as entrydate,concat(users.name,'[',users.empid,']') as userinfo,main.reg_no_display as caseno from case_info join users on case_info.usercode=users.usercode join main on case_info.diary_no=main.diary_no where case_info.diary_no=$diaryno and case_info.display='Y'";
            //$rs_get_case_info=mysql_query($sql_get_case_info) or die(__LINE__.'->'.mysql_error());
             

            $rs_get_case_info = $this->Model_case_status->getCaseInfo($diaryno);
            
                $output.= '<table width="100%" border="1" class="table table-striped custom-table table-hover dt-responsive" style="width:100%;">
                   <thead> <tr>
                        <th>S.No</th>
                        <th>Case No.</th>
                        <th>Case Information</th>
                        <th>User</th>
                        <th>Entry Date</th>
                    </tr></thead>';
                if(!empty($rs_get_case_info)) 
                {    
                    $sno=1;
                
                    foreach ($rs_get_case_info as  $row_caseinfo)
                    {
                        $id=$row_caseinfo['id'];
                        $diary_no=$row_caseinfo['diary_no'];
                        $message=$row_caseinfo['message'];
                        $entry_time=$row_caseinfo['insert_time'];
                        $entered_by=$row_caseinfo['u'];        
                        $entered_ip=$row_caseinfo['userip'];
                        $caseno=$row_caseinfo['caseno'];
                        $uninfo=$row_caseinfo['userinfo'];
                        //$entrydate=date('d/m/Y',strtotime($row_caseinfo['entrydate']));
                        $entrydate = !empty($row_caseinfo['entrydate']) ? date('d/m/Y', strtotime($row_caseinfo['entrydate'])) : '';

                        //  echo $message;
                    

                        $output.= '<tr>';
                        $output.= '<td rowspan="">';
                        $output.=  $sno; 
                        $output.= '</td>';
                        $output.= '<td>';
                        $output.= $caseno;
                        $output.= '</td>';
                        $output.= '<td>';
                        $output.= $message;
                        $output.= '</td>';
                        $output.= '<td>';
                        $output.=  $uninfo;
                        $output.= '</td>';
                        $output.= '<td>';
                        $output.=  $entrydate;
                        $output.= '</td>';
                        $output.= '</tr>';  
                        
                        $sno=$sno+1;
                    }
                }else{
                    $output .='<tr><td colspan="100%"><p align=center><font color=red><b>CASE INFROMATION NOT FOUND</b></font></p></td></tr>';
                } 
                 
                $output.= '</table>';                        
               
                return $output;



            exit();
        }
        exit();
    }

    public function get_orders()
    {             
            
        $data['diary_no'] =$_GET['diary_no'];
        $data['result_jo'] = $this->Model_case_status->getJoinedDetails($_GET['diary_no']);

        return view('Common/Component/case_status/get_orders',$data);
 
    }




}
