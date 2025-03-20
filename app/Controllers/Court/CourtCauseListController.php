<?php

namespace App\Controllers\Court;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Court\CourtMasterModel;
use App\Models\Court\CourtCausesListModel;
use App\Libraries\phpqrcode\Qrlib;
use App\Libraries\Fpdf;

class CourtCauseListController extends BaseController
{
    public $model;
    public $diary_no;
    public $qrlib;
    public $Fpdf;
    public $CourtCausesListModel;

    function __construct()
    {
        $this->model = new CourtMasterModel();
        $this->CourtCausesListModel = new CourtCausesListModel();
        $this->qrlib = new Qrlib();
        $this->Fpdf = new Fpdf();
        $this->db = db_connect();

     /*   if (empty(session()->get('filing_details')['diary_no'])) {
            header('Location:' . base_url('Filing/Diary/search'));
            exit();
        } else {
            $this->diary_no = session()->get('filing_details')['diary_no'];
        } */
    }

    public function index()
    {
        $data['regular_judges'] =  $this->CourtCausesListModel->getRegularJudges();
        $data['case_remarks_heads'] =  $this->CourtCausesListModel->getCaseRemarksHead();
        $data['case_remarks_head_for_d'] =  $this->CourtCausesListModel->getCaseRemarksHeadForD();
        return view('Court/CourtCauseList/index', $data);
    }




    public function insert_show()
    {

        $this->CourtCausesListModel = new CourtCausesListModel();
        $db = \Config\Database::connect();
        $str = $this->request->getGet('str') ?? '';


        // if ($str) {
        // $str1 = explode(":", $str);


        $str = '0::2024-09-05:::::M:0:0';
        $str1 = explode(":", $str);

        $str1[6] = preg_replace("/[^a-zA-Z\d\s\.]/", "", $str1[6]);
        $str1[4] = preg_replace("/[^a-zA-Z\d\s\.]/", "", $str1[4]);
        $ent_dt = date('Y-m-d');
        $ent_dttime = date('Y-m-d H:i:s');
        $ucode = session()->get('login')['usercode'];

        // Check if record exists
        $builder = $db->table('showlcd');
        $builder->where('court', $str1[0]);
        $builder->where('ent_dt', $ent_dt);
        $query = $builder->get();
        $existingRecord = $query->getRow();

        if ($existingRecord) {

            $record_insert = $this->CourtCausesListModel->insertShowCauselistHistory((array)$existingRecord);


            // Prepare update data
            $updateData = $str1[7] === 'D' ? [
                'judges_list' => $str1[9],
                'fil_no' => $str1[8],
                'mf' => $str1[1],
                'csno' => $str1[3],
                'parties' => $str1[4],
                'clno' => $str1[5],
                'ent_dttime' => $ent_dttime,
                'jcodes' => $str1[10],
                'sbdb' => $str1[11],
                'ent_by' => $ucode,
                'is_mentioning' => 'N'
            ] : [
                'msg' => $str1[6] ?? '',
                'ent_dttime' => $ent_dttime,
                'ent_by' => $ucode,
                'is_mentioning' => 'N'
            ];

            $updatedata = $this->CourtCausesListModel->updateShowCauselist($updateData, $str1[0]);
        } else {
            // Insert new record

            $insertData = [
                'court' => $str1[0],
                'mf' => $str1[1],
                'cl_dt' => $str1[2],
                'csno' => $str1[3],
                'parties' => $str1[4],
                'clno' => $str1[5],
                'ent_dt' => $ent_dt,
                'ent_dttime' => $ent_dttime,
                'fil_no' => $str1[8],
                'judges_list' => $str1[9],
                'jcodes' => $str1[9],
                'sbdb' => $str1[9],
                'ent_by' => $ucode,
                'is_mentioning' => 'N',
                'msg' => $str1[6] ?? ''
            ];

            $insertdata = $this->CourtCausesListModel->insertShowCauselist($insertData);
        }

        return $this->response->setJSON(['status' => 'success']);
        //   }

        // return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid input']);

    }



    public function insert_mentioning_case_for_displayboard()
    {
        // $str = $this->request->getPost('str');
        // $str1 = explode(":", $str);


        $this->CourtCausesListModel = new CourtCausesListModel();
        $db = \Config\Database::connect();
        $str = $this->request->getGet('str') ?? '';


        $str = '0::2024-09-05:::::M:0:0';
        $str1 = explode(":", $str);

        $str1[6] = preg_replace("/[^a-zA-Z\d\s\.]/", "", $str1[6]);

        // Check if record exists
        $builder = $db->table('showlcd');
        $builder->where('court', $str1[0]);
        $builder->where('ent_dt', date('Y-m-d'));
        $query = $builder->get();
        $existingRecord = $query->getRow();

        if ($existingRecord) {

            $record_insert = $this->CourtCausesListModel->insertShowCauselistHistory((array)$existingRecord);
            // if ($query->getNumRows() > 0) {
            //     // Insert into showlcd_history
            //     $db->query("INSERT INTO showlcd_history (SELECT * FROM showlcd WHERE court = ? AND ent_dt = CURDATE())", [$str1[0]]);
            // Update showlcd table


            $updateData = $str1[7] === 'D' ? [
                'judges_list' => $str1[9],
                'fil_no' => $str1[8],
                'mf' => $str1[1],
                'csno' => $str1[3],
                'parties' => $str1[4],
                'clno' => $str1[6],
                'ent_dttime' => date('Y-m-d H:i:s'),
                'jcodes' => $str1[9],
                'sbdb' => $str1[9],
                'ent_by' => session()->get('login')['usercode'],
                'is_mentioning' => 'N',
                'msg' => $str1[6] ?? ''
            ]
                : [
                    'clno' => $str1[6],
                    'ent_dttime' => date('Y-m-d H:i:s'),
                    'ent_by' =>  session()->get('login')['usercode'],
                    'is_mentioning' => 'Y'
                ];


            $updatedata = $this->CourtCausesListModel->updateShowCauselist($updateData, $str1[0]);
        } else {
            // Insert into showlcd table
            $insertData = [
                'court' => $str1[0],
                'mf' => $str1[1],
                'cl_dt' => $str1[2],
                'csno' => $str1[3],
                'parties' => $str1[4],
                'clno' => $str1[6],
                'ent_dt' => date('Y-m-d'),
                'ent_dttime' => date('Y-m-d H:i:s'),
                'fil_no' => $str1[8],
                'judges_list' => $str1[9],
                'jcodes' => $str1[9],
                'sbdb' => $str1[9],
                'ent_by' => session()->get('login')['usercode'],
                'is_mentioning' => 'Y',
                'msg' => $str1[6] ?? ''
            ];

            $insertdata = $this->CourtCausesListModel->insertShowCauselist($insertData);
        }
        return $this->response->setJSON(['status' => 'success']);
    }


    public function reprint()
    {
        return view('Court/CourtCauseList/reprint');
    }

    public function get_reprint_j_o()
    {
        $data['_REQUEST'] = $_REQUEST;
        $data['CourtCausesListModel'] = $this->CourtCausesListModel;
        $ucode = $_SESSION['login']['usercode'];
        $fromDate = date('Y-m-d',  strtotime($_REQUEST['txt_o_frmdt']));
        $toDate = date('Y-m-d',  strtotime($_REQUEST['txt_o_todt']));

        if ($_REQUEST['order_upload'] == 'O') {
            $o_o = 'orderdate';
        } else if ($_REQUEST['order_upload'] == 'U') {
            $o_o = 'date(ent_dt)';
        }
        $usercode = '';
        if ($ucode != '1') {
            $usercode = " and usercode='$ucode'";
        }

        $data['result'] =    $this->CourtCausesListModel->getOrderDetails($fromDate, $toDate, $o_o);
        
        return view('Court/CourtCauseList/get_reprint_j_o', $data);
    }

    public function get_pdf_name()
    {

        $docid = $_REQUEST['docid'];
        $path = is_data_from_table('ordernet', "id='$docid' and (prnt_name!='' || prnt_name is not null)", " pdfname ", $row = '');
        if (!empty($path)) {

            echo '../jud_ord_html_pdf/' . $path['pdfname'];
            // echo $path;
        } else {
            //no pdf found;
            echo '0';
        }
    }

    public function live_reporting()
    {
        return view('Court/CourtCauseList/live_reporting');
    }

    public function summary()
    {
        return view('Court/CourtCauseList/summary');
    }

    public function reader_cl_process()
    {
        $data['_REQUEST'] = $_REQUEST;
        $data['CourtCausesListModel'] = $this->CourtCausesListModel;

        //$ucode = $_SESSION['login']['usercode'];		
       //$msg = "";
        
        $crt = $_REQUEST['courtno'];
        $court_text = $_REQUEST['court_text'];
        $check_text='';
        if($court_text!=''){
        if (stripos($court_text, 'Registrar') !== false) {
            $check_text = 'Found';
        }
        else
        {
        $check_text='';  
        }
        }
        $dtd = $_REQUEST['dtd'];
        $jcd = $_REQUEST['aw1'];
        $mf = $_REQUEST['mf'];
        //$printFrm = 0;

        //$pr_mf = $mf;

        $tdt1 = date('Y-m-d',strtotime($dtd));


        if ($crt != '') {

            $sql_ro = $this->CourtCausesListModel->getRosterData($crt, $mf, $check_text, $tdt1);
            
            $result = '';
            foreach ($sql_ro as $res) {
                if ($result == '')
                $result .= $res['roster_id'];
                else
                    $result .= "," . $res['roster_id'];
            }
            $whereStatus = "";
            $r_status = '';
            if ($r_status == 'A') {
                $whereStatus = '';
            } else if ($r_status == 'P') {
                $whereStatus = " and m.c_status='P'";
            } else if ($r_status == 'D') {
                $whereStatus = " and m.c_status='D'";
            }
            $data['sql_t'] = $this->CourtCausesListModel->getCases($tdt1, $mf, $result, $whereStatus);
            
        }

        if($jcd!='')
        {
            if($mf == 'M') 
                $tmf='1';
            else if($mf == 'F') 
                $tmf='2';
            else if($mf == 'L') 
                $tmf='3';
            else if($mf == 'S') 
                $tmf='4';
            
            $msg = "";

           // $sql_t = "";
            //$ttt = 0;

            $data['sql_t'] = $this->CourtCausesListModel->getCasesjcd($tdt1, $mf, $jcd);
        
        }



        return view('Court/CourtCauseList/reader_cl_process', $data);
        die;
    }


    public function get_steno()
    {
        $jcodes = $_GET['judges'];
        if ($jcodes != "") {
            $judges = $jcodes;

            $t_paps = "";
            $t11a = $this->CourtCausesListModel->getUserDetails($judges);
            if (!empty($t11a)) {
                foreach ($t11a as $row11a) {
                    $t_paps .= $row11a["usercode"] . "|" . $row11a["name"] . " [" . $row11a["disp_flag"] . "]#";
                }
            }
        }
        echo $t_paps;
        die;
    }


    public function insert_rec_an()
    {
        $ucode = $_SESSION['login']['usercode'];
        $str = $_POST['str'];
        $str1 = $_POST['str1'];
        $dt = date('Y-m-d', strtotime($_POST['dt']));
        $hdt = date('Y-m-d', strtotime($_POST['hdt']));
        //$ucode=$_POST['ucode'];
        if (isset($_POST['uip']))
            $uip1 = $_POST['uip'];
        if (isset($_POST['umac']))
            $umac1 = $_POST['umac'];
        $old_new = $_POST['old_new'];
        if (isset($_POST['concstr']))
            $concstr = $_POST['concstr'];
        else
            $concstr = "";
        if (isset($_POST['sno']))
            $snop = $_POST['sno'];
        else
            $snop = '';

        $statusSide = $_POST['statusSide'];
        $rec = explode("#", $str);
        $fno = $rec[0];
        $status = $rec[1];
        $rec_rem = explode("!", $rec[2]);
        $rec_str = explode("|", $str1);
        $jcodes = $rec_str[0];
        $jcodes = str_replace("^", ",", $jcodes);
        $tjcode = explode(",", $jcodes);
        $tj1 = $tj2 = $tj3 = $tj4 = $tj5 = 0;
        for ($ti = 0; $ti < 5; $ti++) {
            if ($ti < (count($tjcode) - 1))
                $tjc = $tjcode[$ti];
            else
                $tjc = 0;
            switch ($ti) {
                case 0:
                    $tj1 = $tjc;
                    break;
                case 1:
                    $tj2 = $tjc;
                    break;
                case 2:
                    $tj3 = $tjc;
                    break;
                case 3:
                    $tj4 = $tjc;
                    break;
                case 4:
                    $tj5 = $tjc;
                    break;
            }
        }
        $mainhead = $rec_str[1];
        $clno = $rec_str[2];
        $subh = $rec_str[3];
        $err = "";
        if ($concstr != '') {
            $cncases = explode(',', $concstr);
            $cncntr = count($cncases);
        } else {
            $cncntr = 1;
        }
        // added to check for duplicate remarks entry

        $row_crm = is_data_from_table('case_remarks_multiple', " diary_no='$fno'  AND cl_date='$dt' ", "STRING_AGG(CONCAT(r_head, '|', head_content), '!') AS remarks", $row = '');
        $remarks_db = '';
        if (!empty($row_crm)) {
            $remarks_db = $row_crm['remarks'];
        }
        //end
        for ($ivar = 0; $ivar < $cncntr; $ivar++) {
            if ($ivar > 0)
                $fno = $cncases[$ivar - 1];

            if ($status == "P") {
                if (strtotime($dt) == strtotime(date('Y-m-d'))) {
                    $str_ia_p = "UPDATE docdetails SET iastat='P', lst_mdf=NOW(),lst_user=$ucode WHERE diary_no='" . $fno . "' AND iastat='D' AND DATE(lst_mdf)='" . $dt . "' AND doccode=8 AND display='Y'";
                    $this->db->query($str_ia_p);
                }
            }
            if ($status == "D") {
                $str_ia_d = "UPDATE docdetails SET iastat='D', lst_mdf=NOW(),dispose_date='$dt',last_modified_by=$ucode WHERE diary_no='" . $fno . "' AND iastat='P' AND doccode=8 AND display='Y'";
                $this->db->query($str_ia_d);
            }
            $rcount = count($rec_rem);


            $this->CourtCausesListModel->clear_remarks($fno, $dt, $ucode, $old_new);

            $head_r = array();
            $head_c = array();
            $i_cnt = 0;
            $check_case_withdraw = "";
            $delay = 0;
            for ($i = 0; ($i < ($rcount - 1)); $i++) {
                $rec1 = explode("|", $rec_rem[$i]);
                //$rem=$rec1[1];
                $head = $rec1[0];
                $head_cont = $rec1[1];
                if ($head == 35) {
                    $check_case_withdraw = "YES";
                }
                if ($head != 16) {
                    $head_r[$i_cnt] = $rec1[0];
                    $head_c[$i_cnt] = $rec1[1];
                } else {
                    $i_cnt--;
                }
                $i_cnt++;

                //CHECK FOR PAPERBOOK NOT RECEIVED
                if ($head == 128 || $head == 153 || $head == 126 || $head == 152) {
                    $str_sel_main1 = "SELECT dacode, diary_no as caseno FROM main where diary_no='" . $fno . "'";
                    $results_selm1 = $this->db->query($str_sel_main1);
                    $row_selm1 = $results_selm1->getRowArray();
                    $dacode = "";
                    $caseno = "";
                    if (!empty($row_selm1)) {
                        $dacode = $row_selm1["dacode"];
                        $caseno = $row_selm1["caseno"];
                    }
                    $msg_p = "";
                    switch ($head) {
                        case 128:
                            $msg_p = "Case Listed Under Wrong Head [" . $head_cont . "] in Case No. : " . $caseno;
                            break;
                        case 153:
                            $msg_p = "Non compliance of order dtd. [" . $head_cont . "] in Case No. : " . $caseno;
                            break;
                        case 126:
                            $msg_p = "Office Remark Defective [" . $head_cont . "] in Case No. : " . $caseno;
                            break;
                        case 152:
                            $msg_p = "Paper Book not received [" . $head_cont . "] with Case No. : " . $caseno;
                            break;
                    }
                    if ($dacode != '') {
                        $sql_p = "insert into msg(to_user,from_user,msg,ipadd) values('$dacode','$ucode','$msg_p','$umac1')";
                        $this->db->query($sql_p);
                    }
                }

                //CHECK FOR PAPERBOOK NOT RECEIVED
                $str_ins = "INSERT INTO case_remarks_multiple(diary_no, cl_date, r_head, e_date, jcodes, head_content,mainhead,clno,uid,status,remove,remark,dw,usr_entry,notice_type,comp_remarks) values('" . $fno . "','" . $dt . "'," . $head . ", NOW(),'" . $jcodes . "','" . $head_cont . "','" . $mainhead . "'," . $clno . "," . $ucode . ",'" . $status . "',0,'','',0,0,'')";
                $this->db->query($str_ins);

                if ($head == 81 || $head == 74 || $head == 75 || $head == 65 || $head == 2 || $head == 1 || $head == 94) {
                    $str_upmain = "UPDATE main SET admitted='admitted on " . $dt . ", entered on " . date('Y-m-d') . "' where diary_no='" . $fno . "'";
                    $this->db->query($str_upmain);
                }
                //IA DISPOSAL
                if ($head == 22 || $head == 26 || $head == 95 || $head == 142) {
                    if (trim($head_cont) != '') {
                        $ia = explode(",", trim($head_cont));
                        for ($ii = 0; ($ii < (count($ia))); $ii++) {
                            $ia1 = explode("/", trim($ia[$ii]));
                            $ia_num = trim($ia1[0]);
                            $ia_yr = trim($ia1[1]);
                            if ($ia_num != '' and $ia_yr != '') {
                                $str_ia = "UPDATE docdetails SET iastat='D', lst_mdf=NOW(),dispose_date='$dt',last_modified_by=$ucode WHERE diary_no='" . $fno . "' AND docnum=" . $ia_num . " AND docyear=" . $ia_yr . " AND doccode=8 AND display='Y'";
                                $this->db->query($str_ia);
                            }
                        }
                    }
                }
                //IA DISPOSAL

                if ($head == 200 or $head == 201)
                    $delay = 1;
                if ($head == 176 or $head == 177 or $head == 178)
                    $delay = 2;
            }



            //Code added by preeti on 3.7.2019 to add 2 more disposal remarks-Delay condone and permission to file SLP
            $casetype = "";
            $t_lc = 0;
            $t_lcid = 0;
            $count = 0;
            $check_var = '';

            $cyear = date('Y');
            $row_casetype = is_data_from_table('main', " diary_no='$fno' ", "*", $row = '');
            if (!empty($row_casetype)) {
                $casetype = $row_casetype['casetype_id'];
                $activeCasetype = $row_casetype['active_casetype_id'];
            }
            $row_l = is_data_from_table('lowerct', " diary_no =" . $fno . " and lw_display='Y' and is_order_challenged='Y' ", " count(*) as total,string_agg(lower_court_id::text, ',') as lcid ", $row = '');
            if (!empty($row_l)) {
                $t_lc =   $row_l['total'];
                $t_lcid = $row_l['lcid'];
            }
            if ($delay == 1) {
                $allowed_case_type = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
                $allowed_case_type1 = array(1, 2, 3, 4, 7, 8, 9, 10, 19, 20, 25, 26, 39);
                $res_obj_check = is_data_from_table('obj_save', " diary_no = '$fno'  and display='Y' and rm_dt IS NULL ", " count(id) ", $row = 'N');
                if ($res_obj_check > 0) {

                    $sql_obj_rm = "UPDATE obj_save SET rm_dt=now(),rm_user_id=$ucode WHERE diary_no='" . $fno . "' and display='Y'";
                    $this->db->query($sql_obj_rm);
                }

                if ($t_lc == 0 && $casetype != 5 && $casetype != 6)
                    continue;
                if ((($activeCasetype == null || $activeCasetype == '' || $activeCasetype == 0 || $activeCasetype == 13 || $activeCasetype == 14 || $activeCasetype == 31) && ((in_array($casetype, $allowed_case_type1)) && $t_lc != 0)) || (($t_lc == 0 && ($casetype == 5 || $casetype == 6)) && ($activeCasetype == null || $activeCasetype == '' || $activeCasetype == 0))) {
                    if ($t_lc == 0 && ($casetype == 5 || $casetype == 6))
                        $t_lc = 1;

                    $row_max = is_data_from_table('master.kounter', " year='$cyear' and casetype_id='$casetype' ", " knt ", $row = '');
                    if (!empty($row_max)) {
                        $count = $row_max['knt'];
                    }
                    $count_max = $count + $t_lc;

                    $upd_case_ct = "Update master.kounter set knt='$count_max' where year='$cyear' and casetype_id='" . $casetype . "'";
                    $result = $this->db->query($upd_case_ct);
                    if (!$result) {
                        $check_var = 'error';
                    }

                    $reg_no = str_pad($casetype, 2, "0", STR_PAD_LEFT) . '-' . str_pad(($count + 1), 6, "0", STR_PAD_LEFT) . '-' . str_pad($count_max, 6, "0", STR_PAD_LEFT);

                    $regNoDisplay = $this->CourtCausesListModel->getRegistrationNumberDisplay($fno, $reg_no, $cyear);

                    $upd_main = "Update main set fil_no='$reg_no',fil_dt=now(),usercode='$ucode',mf_active='M',active_fil_no='$reg_no',
                        active_fil_dt=now(),active_reg_year='$cyear',reg_year_mh='$cyear',active_casetype_id='$casetype',reg_no_display='" . $regNoDisplay . "' where diary_no='$fno'";

                    $upd_main = $this->db->query($upd_main);
                    if (!$upd_main) {
                        $check_var = 'error';
                    }
                    $upd_main_ct = "INSERT INTO main_casetype_history(diary_no, new_registration_number, new_registration_year, order_date, updated_on, is_deleted,adm_updated_by) values('" . $fno . "', '" . $reg_no . "','" . $cyear . "','" . $hdt . "',now(),'f','" . $ucode . "')";
                    $upd_main_ct = $this->db->query($upd_main_ct);
                    if (!$upd_main_ct) {
                        $check_var = 'error';
                    }
                    $t_lcid_exp = explode(',', $t_lcid);
                    for ($j = 0; $j < $t_lc; $j++) {
                        $fil_no = str_pad($casetype, 2, "0", STR_PAD_LEFT) . str_pad(($count + $j + 1), 6, "0", STR_PAD_LEFT) . $cyear;

                        $ins_sql = "Insert into registered_cases (lowerct_id,diary_no,fil_no,entuser,entdt,casetype_id,case_no,case_year) values
                                ('$t_lcid_exp[$j]','$fno','$fil_no','$ucode',now(),
                                    '$casetype','" . ($j + $count + 1) . "','$cyear')";
                        $ins_sql = $this->db->query($ins_sql);
                        if (!$ins_sql) {
                            $check_var = 'error12335';
                        } else {
                            $dno = substr($fno, 0, -4);
                            $dyr = substr($fno, -4);
                            $_REQUEST['dno'] = $dno;
                            $_REQUEST['dyr'] = $dyr;
                        }
                    }
                }
                $msg = $fno . " - " . $regNoDisplay;

                $dno = $_REQUEST['dno'] . $_REQUEST['dyr'];
                $this->CourtCausesListModel->get_da($dno);

                //end
            }

            if ($delay == 2) {
                $count = 0;
                $mf_info = $this->CourtCausesListModel->get_cases_mf($fno);
                $t_var = explode(",", $mf_info);
                $nos_generate = explode('||', $t_var[5]);
                $t_nos_m = explode('#', $nos_generate[0]);
                $t_nos_f = explode('#', $nos_generate[1]);

                if ($t_nos_m[0] == 'Y') {
                    if ($t_lc <= 0)
                        continue;

                    $res_obj_check = is_data_from_table('obj_save', " diary_no=$fno and display='Y' and  rm_dt IS NULL ", " count(id) as total ", $row = '');

                    if ($res_obj_check['total'] > 0) {

                        $sql_obj_rm = "UPDATE obj_save SET rm_dt=now(),rm_user_id=$ucode WHERE diary_no='" . $fno . "' and display='Y'";
                        $this->db->query($sql_obj_rm);
                    }
                    $check_var = '';

                    $row_max = is_data_from_table('master.kounter', " year='$cyear' and casetype_id='$t_nos_m[1]' ", " knt ", $row = '');
                    if (!empty($row_max)) {
                        $count = $row_max['knt'];
                    }
                    $count_max = $count + $t_lc;

                    $upd_case_ct = "Update master.kounter set knt='$count_max' where year='$cyear' and casetype_id='" . $t_nos_m[1] . "'";
                    $result =  $this->db->query($upd_case_ct);
                    if (!$result) {
                        $check_var = 'error';
                    }
                    $reg_no = str_pad($t_nos_m[1], 2, "0", STR_PAD_LEFT) . '-' . str_pad(($count + 1), 6, "0", STR_PAD_LEFT) . '-' . str_pad($count_max, 6, "0", STR_PAD_LEFT);
                    $regNoDisplay = $this->CourtCausesListModel->getRegistrationNumberDisplay($fno, $reg_no, $cyear);

                    $upd_main = "Update main set fil_no='$reg_no',fil_dt=now(),usercode='$ucode',mf_active='M',active_fil_no='$reg_no',
                        active_fil_dt=now(),active_reg_year='$cyear',reg_year_mh='$cyear',active_casetype_id='$t_nos_m[1]',reg_no_display='" . $regNoDisplay . "' where diary_no='$fno'";

                    $upd_main =  $this->db->query($upd_main);
                    if (!$upd_main) {
                        $check_var = 'error';
                    }
                    $upd_main_ct = "INSERT INTO main_casetype_history(diary_no, new_registration_number, new_registration_year, ref_new_case_type_id,order_date, updated_on, is_deleted,adm_updated_by) values('" . $fno . "', '" . $reg_no . "','" . $cyear . "','" . $t_nos_m[1] . "','" . $dt . "',now(),'f','" . $ucode . "')";
                    $upd_main_ct = $this->db->query($upd_main_ct);
                    if (!$upd_main_ct) {
                        $check_var = 'error';
                    }

                    $t_lcid_exp = explode(',', $t_lcid);
                    for ($j = 0; $j < $t_lc; $j++) {
                        $fil_no = str_pad($casetype, 2, "0", STR_PAD_LEFT) . str_pad(($count + $j + 1), 6, "0", STR_PAD_LEFT) . $cyear;
                        $ins_sql = "Insert into registered_cases (lowerct_id,diary_no,fil_no,entuser,entdt,casetype_id,case_no,case_year) values
                                ('$t_lcid_exp[$j]','$fno','$fil_no','$ucode',now(),
                                    '$casetype','" . ($j + $count + 1) . "','$cyear')";
                        $ins_sql = $this->db->query($ins_sql);
                        if (!$ins_sql) {
                            $check_var = 'error';
                        } else {
                            $dno = substr($fno, 0, -4);
                            $dyr = substr($fno, -4);
                            $_REQUEST['dno'] = $dno;
                            $_REQUEST['dyr'] = $dyr;
                        }
                    }
                } //end of if(t_nos_m=='y'
                $count = 0;
                if ($t_nos_f[0] == 'Y') {
                    if ($t_lc <= 0)
                        continue;
                    $check_var = '';

                    $row_max = is_data_from_table('master.kounter', " year='$cyear' and casetype_id='$t_nos_f[1]' ", " knt ", $row = '');
                    if (!empty($row_max)) {
                        $count = $row_max[0];
                    }
                    $count_max = $count + $t_lc;

                    $upd_case_ct = "Update kounter set knt='$count_max'  where year='$cyear' and casetype_id='" . $t_nos_f[1] . "'";
                    $result = $this->db->query($upd_case_ct);
                    if (!$result) {
                        $check_var = 'error';
                    }
                    $reg_no = str_pad($t_nos_f[1], 2, "0", STR_PAD_LEFT) . '-' . str_pad(($count + 1), 6, "0", STR_PAD_LEFT) . '-' . str_pad($count_max, 6, "0", STR_PAD_LEFT);
                    $regNoDisplay = $this->CourtCausesListModel->getRegistrationNumberDisplay($fno, $reg_no, $cyear);

                    //code to change section id while conversion to CA if matter belongs to section-XI,IVB,XIV
                    $section = "";
                    $check_section = is_data_from_table('main', " diary_no=$fno ", " * ", $row = '');

                    //UP matters
                    if ($check_section['active_casetype_id'] == 1 && $check_section['ref_agency_state_id'] == '61023' && in_array($check_section['ref_agency_code_id'], array('15', '16'), TRUE)) {
                        $section = ",section_id=23 "; //section XI->III-A
                    }
                    //Maharashtra matters from IX to III
                    else if ($check_section['active_casetype_id'] == 1 && $check_section['ref_agency_state_id'] == '358033' && in_array($check_section['ref_agency_code_id'], array('31', '32', '33', '34'), TRUE)) {
                        $section = ",section_id=22 "; //section XIV->XIVA
                    }
                    //Punjab matters
                    else if ($check_section['active_casetype_id'] == 1 && $check_section['ref_agency_state_id'] == '226817' && in_array($check_section['ref_agency_code_id'], array('10'), TRUE)) {
                        $section = ",section_id=24 "; //section IVB->IV
                    }
                    //Delhi HP North East
                    else if ($check_section['active_casetype_id'] == 1 && in_array($check_section['ref_agency_state_id'], array('490506', '571779', '291560', '537722', '349528', '348677', '355594', '184724', '511231', '167131'), TRUE) && in_array($check_section['ref_agency_code_id'], array('14', '27', '28', '29', '30', '86', '137', '138', '139', '149', '150', '153', '53', '183', '184', '9', '299'), TRUE)) {
                        $section = ",section_id=80 "; //section XIV->XIVA
                    }
                    //code to change section ends

                    $row_main = $check_section;

                    $upd_main = "Update main set fil_no_fh='$reg_no',fil_dt_fh=now(),usercode='$ucode',mf_active='F',active_fil_no='$reg_no',
                        active_fil_dt=now()$section, active_reg_year='$cyear',reg_year_fh='$cyear',reg_year_mh=year('$row_main[fil_dt]'),active_casetype_id='$t_nos_f[1]',reg_no_display='" . $regNoDisplay . "' where diary_no='$fno'";
                    $upd_main = $this->db->query($upd_main);

                    $upd_main_ct = "INSERT INTO main_casetype_history(diary_no, old_registration_number,old_registration_year,ref_old_case_type_id,new_registration_number, new_registration_year,ref_new_case_type_id, order_date, updated_on, is_deleted,adm_updated_by) values('" . $fno . "','" . $row_main['fil_no'] . "','" . $row_main['reg_year_mh'] . "','" . $row_main['casetype_id'] . "', '" . $reg_no . "','" . $cyear . "','" . $t_nos_f[1] . "','" . $dt . "',now(),'f','" . $ucode . "')";
                    $upd_main_ct = $this->db->query($upd_main_ct);
                    if (!$upd_main_ct) {
                        $check_var = 'error';
                    }
                    if (!$upd_main) {
                        $check_var = 'error';
                    }
                    if ($check_var == '')
                        $output .= "<td>" . $regNoDisplay . "</td>";
                    else
                        $output .= "<td>Error</td>";
                }
                $dno = substr($fno, 0, -4);
                $dyr = substr($fno, -4);
                $_REQUEST['dno'] = $dno;
                $_REQUEST['dyr'] = $dyr;
                $dno = $_REQUEST['dno'] . $_REQUEST['dyr'];

                $msg = $fno . " - " . $regNoDisplay;
                $this->CourtCausesListModel->get_da($dno);
            } //end of if(delay==2)
            //preeti's code end


            $this->CourtCausesListModel->update_cis($fno, $dt, $head_r, $head_c, $hdt, $ucode, $old_new, $snop);
        }
    }

    public function check_parties()
    {
        $dt = $_POST['dt'];
        $cn = $_POST['cn'];

        if ($cn != "" && $dt != "") {
            $res = is_data_from_table('case_remarks_multiple', " diary_no='$cn' AND cl_date='$dt' AND r_head=91 ", '*', $row = '');
            if (!empty($res)) {
                $sql_del = "DELETE FROM abr_accused WHERE diary_no='" . $cn . "' AND ord_dt='" . $dt . "'";
                $this->db->query($sql_del);
            }
        }
        return true;
    }


    public function get_reg_no()
    {
        $dno = $_REQUEST['dno'];
        $reg_no = '';
        $casetype = '';
        $slp_no = '';

        $row_reg_no = is_data_from_table('main', " diary_no='$dno' ", " active_casetype_id,diary_no,reg_no_display ", $row = '');

        if (!empty($row_reg_no) && ($row_reg_no['reg_no_display'] != null || $row_reg_no['reg_no_display'] != '')) {
            $reg_no = $row_reg_no['reg_no_display'];
            $diary_no = $row_reg_no['diary_no'];
            $casetype = $row_reg_no['active_casetype_id'];
            if ($casetype == 3 || $casetype == 4) {

                $builder = $this->db->table('main_casetype_history mch');
                $builder->select("
                    CONCAT(
                        c.short_description, ' ',
                        CASE
                            WHEN split_part(mch.old_registration_number, '-', 2) = 
                                split_part(mch.old_registration_number, '-', 3)
                            THEN split_part(mch.old_registration_number, '-', 2)
                            ELSE CONCAT(
                                split_part(mch.old_registration_number, '-', 2), '-', 
                                split_part(mch.old_registration_number, '-', 3)
                            )
                        END, '/',
                        mch.old_registration_year
                    ) AS slp_no
                ");
                $builder->join('master.casetype c', 'mch.ref_old_case_type_id = c.casecode');
                $builder->where('mch.diary_no', $dno);
                $builder->where('mch.ref_new_case_type_id', $casetype);
                $query = $builder->get();
                $row_slp = $query->getRowArray();

                if (!empty($row_slp) &&  ($row_slp['slp_no'] != null || $row_slp['slp_no'] != '')) {
                    $slp_no = $row_slp['slp_no'] . " @ ";
                }
            }
            echo $diary_no . "#" . $slp_no . $reg_no;
        } else {
            $rows = is_data_from_table('lowerct', " diary_no='$dno' and lw_display='Y' and is_order_challenged='Y' ", "*", $row = 'N');
            if ($rows == 0) {
                echo $dno . "#" . "Matter not updated since Lower Court Details not updated in Diary No $dno";
            } else
                echo $dno . "#" . "";
        }
    }



    public function insert_rec_paps()
    {
        $ucode = session()->get('login')['usercode'];

        $cn = $_POST['cn'];
        $cldt = $_POST['cldt'];
        $court = $_POST['court'];
        //$ucode=$_POST['ucode'];
        $paps = $_POST['paps'];
        if ($paps != '') {
            $paps = substr(str_replace("|", ",", $paps), 0, -1);
            $paps1 = explode(",", $paps);
        }
        $mh = $_POST['mh'];
        $clno = $_POST['clno'];
        if (isset($_POST['concstr']))
            $concstr = $_POST['concstr'];
        else
            $concstr = "";
        if (!(is_numeric($clno))) {
            $clno = 0;
        }

        if ($concstr != '') {
            $cncases = explode(',', $concstr);
            $cncntr = count($cncases);
        } else {
            $cncntr = 1;
        }

        for ($ivar = 0; $ivar < $cncntr; $ivar++) {
            if ($ivar > 0)
                $cn = $cncases[$ivar - 1];
            if ($paps == "") {
                $sql_upd = "UPDATE jo_alottment_paps SET display='N' WHERE diary_no='" . $cn . "' and cl_date='" . $cldt . "' AND display='Y'";
                $this->db->query($sql_upd);
            } else {
                $sql_upd = "UPDATE jo_alottment_paps SET display='N' WHERE diary_no='" . $cn . "' and cl_date='" . $cldt . "' AND usercode NOT IN (" . $paps . ") AND display='Y'";
                $this->db->query($sql_upd);
                for ($i = 0; ($i < count($paps1)); $i++) {
                    //$sql_select = "SELECT * FROM jo_alottment_paps WHERE diary_no='" . $cn . "' and cl_date='" . $cldt . "' AND usercode=" . $paps1[$i] . " AND display='Y'";
                    //$result = mysql_query($sql_select) or die(mysql_error() . ":" . $sql_select);

                    $results_cis = is_data_from_table('jo_alottment_paps', " diary_no='" . $cn . "' and cl_date='" . $cldt . "' AND usercode=" . $paps1[$i] . " AND display='Y' ", "*", $row = 'N');
                    if ($results_cis == 0) {
                        $sql_ins = "INSERT INTO jo_alottment_paps(usercode,cl_date,diary_no,display,court,uid,ent_dt,mainhead,clno) VALUES(" . $paps1[$i] . ",'" . $cldt . "','" . $cn . "','Y','" . $court . "'," . $ucode . ",NOW(),'" . $mh . "'," . $clno . ")";
                        $this->db->query($sql_ins);
                    }
                }
            }
        }
        return true;
    }


    public function insert_parties()
    {

        $str = $_POST['str'];
        $dt = $_POST['dt'];
        $cn = $_POST['cn'];
        if ($str != "") {
            $rec = explode("^^", $str);
            $rcount = count($rec);

            if ($cn != "" && $dt != "" && $dt == date('Y-m-d')) {
                $sql_del = "DELETE FROM abr_accused WHERE diary_no='" . $cn . "' AND ord_dt='" . $dt . "'";
                $this->db->query($sql_del);
            }
            for ($i = 0; ($i < ($rcount - 1)); $i++) {
                $rec1 = explode("|", $rec[$i]);
                if ($cn != "" and $dt != "" and $rec1[0] != "" and $rec1[1] != "") {
                    $sql_ins = "INSERT IGNORE INTO abr_accused(diary_no, ord_dt, p_r, p_r_side) VALUES('" . $cn . "', '" . $dt . "', '" . $rec1[0] . "','" . $rec1[1] . "') ";
                    $this->db->query($sql_ins);
                }
            }
        } else {
            echo "ERROR";
        }
    }


    public function daily_court()
    {

        $data['CourtCausesListModel'] = $this->CourtCausesListModel;
        return view('Court/CourtCauseList/daily_court', $data);
    }

    public function get_title()
    {
         
        $ucode = $_SESSION['login']['usercode']; 
        $courtno = $_POST['courtno'];
        $icmis_user_jcode = $_SESSION['login']['jcode'];
        
         
        $judge_code = " r.courtno = $courtno ";
         
        $dtd = date('Y-m-d', strtotime($_POST['dtd']));
        if ($courtno > 0) {
            
            $row = $this->CourtCausesListModel->getRosterDetailsWithJudges($dtd, $judge_code);
            //pr($row);
            if (!empty($row)) {
                //$row = mysql_fetch_array($res);

                if ($row['courtno'] == 21) {
                    $court = "Registrar Court";
                    $judge_name = $row['first_name'] . ' ' . $row['sur_name'] . ', ' . $row['jnm'];
                } else if ($row['courtno'] == 61) {
                    $court = "Registrar Virtual Court No. 1";
                    $judge_name = $row['first_name'] . ' ' . $row['sur_name'] . ', ' . $row['jnm'];
                } else if ($row['courtno'] == 22) {
                    $court = "Registrar Court No. 2";
                    $judge_name = $row['first_name'] . ' ' . $row['sur_name'] . ', ' . $row['jnm'];
                } else if ($row['courtno'] == 62) {
                    $court = "Registrar Virtual Court No. 2";
                    $judge_name = $row['first_name'] . ' ' . $row['sur_name'] . ', ' . $row['jnm'];
                } else {
                    $court = "Court No. " . $row['courtno'];
                    $judge_name = $row['jnm'];
                }
                ?>
                <p style="font-size: 1.2vw; padding-top: 2px;"><?php echo $court . ' @ ' . $judge_name;
                                                                ?>
                    <span style="font-size: 0.7vw; color: #009acd; ">List Of Business For <?php echo date('l', strtotime($_POST['dtd'])) . ' The ' . date('jS F, Y', strtotime($_POST['dtd'])); ?></span>
                </p>

                <?php
                ?>
                <?php
            } else {
                echo "No Records Found";
            }
        }//else{echo 'No title found!!';}
    }

    public function get_item_nos() 
    {              
                   
                $crt = $_REQUEST['courtno'];
                $dtd = $_REQUEST['dtd'];
                if($crt > 0) 
                {
                    //$jcd = $_REQUEST['aw1'];
                    $mf = "M";
                    $r_status=$_REQUEST['r_status'] ?? '';
                    $msg = "";
                     
                    $tdt1 = date('Y-m-d',strtotime($dtd));
                    $printFrm = 0;
                     ///=====Not Show If Cause List Not Print
                    $pr_mf = $mf;
                    $sql_t = "";
                    $ttt = 0;

                    if ($crt != '') {
                        if ($mf == 'M') {
                            $stg = '1';
                        } else if ($mf == 'F') {
                            $stg = '2';
                        }                         
                        //$t_cn = "  courtno = '" . $crt . "' AND if(to_date IS NULL, to_date IS NULL, '" . $tdt1 . "' BETWEEN from_date AND to_date) "; 
                        $t_cn = "courtno = '" . $crt . "' AND (to_date IS NULL OR '" . $tdt1 . "' BETWEEN from_date AND to_date)";                  

                        $results10 = $this->CourtCausesListModel->getRosterAndCaseDetails($tdt1, $mf, $stg, $t_cn, $r_status);                        
                    }
 
                    $jc = "";
                    $chk_var = 0;
                    $not_avail = "";
                    if (!empty($results10)) {
                        ?>

                        <div class="list-group list-group-mine">
                        <?php
                        $chk_var = 1;
                        $con_no = "";
                        $odd_even = 1;
                        foreach ($results10 as $row10) {
                            $t_diary_no = $row10['diary_no'];
                            $t_next_dt = $row10['next_dt'];
                            $t_list_status = $row10['list_status'];
                            $t_reg_no_display = $row10['reg_no_display'];
                            $caseno = $row10["case_no"] . " / " . $row10["year"];
                            if ($row10['diary_no'] == $row10['conn_key'] OR $row10['conn_key'] == 0) {
                                $print_brdslno = $row10['brd_slno'];
                                $con_no = "1";
                            } else {
                                $print_brdslno = $row10["brd_slno"] . "." . $con_no++;
                            }
                            if ($t_list_status == 'DELETED') {
                                $is_deleted = "style='background-color: #ff0000; color:black;'";
                                $is_disable = "disabled";
                            } else {
                                $is_deleted = "";
                                $is_disable = "";
                            }

                            if ($odd_even % 2 == 0) {
                                /*$style_colr = "#99ddff";*/
                                $style_colr = "list-group-item-info";
                            } else {
                                /*$style_colr = "#b3e6ff";*/
                                $style_colr = "list-group-item-primary";
                            }
                            $odd_even++;
                            $display_board_val1 = $crt . ':' . $mf . ':' . $tdt1 . ':' . str_replace(" - ", " ", $caseno) . ':' . str_replace(":", "&nbsp;", str_replace(" & ", " and ", $row10["pet_name"] . ' Vs ' . $row10["res_name"])) . ':' . $row10["brd_slno"];
                            $display_board_val2 = $row10["judges"];


                            ?>

                            <!--style=""-->
                            <div style="padding-bottom: 1px; padding-top: 1px;" class="item_no list-group-item <?= $is_disable; ?>"
                                data-displayboardval1="<?= $display_board_val1; ?>"
                                data-displayboardval2="<?= $display_board_val2; ?>" data-dno="<?= $t_diary_no; ?>"
                                data-listdt="<?= $t_next_dt; ?>">
                                <div class="row"<?= $is_deleted; ?> >
                                    <div class="column_item1"><span style="font-size:0.9vw;"><?= $print_brdslno; ?></span></div>
                                    <!--style="color:#4B0082;"-->
                                    <div class="column_item4"><span style="font-size:0.9vw;">
                            <?php
                            if ($row10['reg_no_display']) {
                                echo $row10['reg_no_display'] . ' <br> DNO. ';
                            } else {
                                echo $row10['short_description'] . " .. DNO. ";
                            }
                            echo substr_replace($row10['diary_no'], '-', -4, 0);
                            //echo '</span><br><p style="color: #9400D3">'.$row10['pet_name'].' <font color="#006400">Vs.</font> '.$row10['res_name'].'</p>';
                            echo '</span><br><span style="font-size:0.6vw;">' . $row10['pet_name'] . ' <font color="#006400">Vs.</font> ' . $row10['res_name'] . '</span>';
                            ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        </div>

                        <?php
                    } else
                        echo 'No Records Found';
                    
                }
                //else{echo 'No Records Found';}
    }

    public function get_cl_date_judges()
    {            

            $ucode = $_SESSION['login']['usercode'];                                     
            $icmis_user_jcode = $_SESSION['login']['jcode'];
            $dcmis_section = $_SESSION['login']['section'];
            $judge_code = '';
            if($_REQUEST['flag'] == 'court'){
                if($icmis_user_jcode > 0 and $ucode != 1){
                    $judge_code = "and t3.jcode = $icmis_user_jcode";
                    $select_display_none = "display:none;";
                }
                else{
                    $selectOption = "<option value=''>select</option>";
                }
            }
            if($_REQUEST['flag'] == 'reader'){
                if($dcmis_section == 62){
                    $judge_code = "and (t1.courtno = 21 OR t1.courtno = 61 )";
                    $select_display_none = "display:none;";
                }
                else if($dcmis_section == 81){
                    $judge_code = "and (t1.courtno = 22 OR t1.courtno = 62 )";
                    $select_display_none = "display:none;";
                }
                else{
                    $selectOption = "<option value=''>select</option>";
                }
            }
 
            $dtd = date('Y-m-d', strtotime($_REQUEST['dtd']));
           
            $results_reg = $this->CourtCausesListModel->getCourtDetails($dtd, $judge_code);
            if (!empty($results_reg)) {
                echo $selectOption;
                foreach ($results_reg as $row_reg) {
                    $judge_name = $row_reg["jname"];
                    echo '<option value="' . $row_reg["courtno"].'">' . str_replace("\\", "", $row_reg["jname"]) . '</option>';
                }
        }
    }


    public function get_right_panel_data_row2()
    {
        
        $diary_no = $_POST['diary_no'];
        $list_dt = $_POST['listdt'];
        
        $row_o = $this->CourtCausesListModel->getOfficeReportDetails($diary_no, $list_dt);
        //pr($row_o);
        if(!empty($row_o)){
           
            $split_or_path = explode("_",$row_o['office_repot_name']);
            $or_gen_dt = date('d-m-Y H:i:s', strtotime($row_o['rec_dt']));
            $or_address = "http://XXXX/supreme_court/officereport/". $split_or_path[1]."/".$split_or_path[0]."/".$row_o['office_repot_name']."#zoom=FitV";
            $or_address_for_path = "http://XXXX/supreme_court/officereport/". $split_or_path[1]."/".$split_or_path[0]."/".$row_o['office_repot_name'];
            $path_info = pathinfo($or_address_for_path);
            if($path_info['extension'] == 'html'){
                $obj_type = "text/html";
            }
            else{
                $obj_type = "application/pdf";
            }
            ?>
            <div style="text-align: left; padding:0px;">
                <p style="font-size: 1.2vw; color: #4169E1;">Office Report <span style="color: #D55C21;">(<?=$or_gen_dt;?>)</span></p>
                <div class="embed-responsive" style="padding-bottom: 97%;">
                    <object class="embed-responsive-item" data="<?=$or_address;?>" type="<?=$obj_type;?>" internalinstanceid="9" title="" >
                        <p>Your browser isn't supporting embedded pdf files. You can download the file
                            <a href="<?=$or_address;?>">here</a>.</p>
                    </object>
                </div>
            </div>
            <?php
        }
        else{
            ?>
            <div style="text-align: left">
                <p style="font-size: 1.2vw; color: #4169E1;">Office Report</p>
                <blockquote><p class="text-info" style="font-size: 1.2vw; color:red;">Oops! Office Report Not Found ...</p>
                </blockquote>
            </div>
        <?php
        }
    }

    public function get_gist_details_nsh()
    {
       
        $usertype = $_SESSION['login']['usertype'];   
        $diary_no = $_POST['diary_no'];
        $list_dt = $_POST['listdt'];
        $verify_str = "";
        $verify_str = $_POST['diary_no']."_".$_POST['listdt'];
         
        $row_org = $this->CourtCausesListModel->getOrgGist($diary_no, $list_dt);

        if(!empty($row_org)){
             
            $gist_dt = date('d-m-Y H:i:s', strtotime($row_org['ent_dt']));

            ?>
        <div style="text-align: left; padding:5px; text-align: justify; ">
            <p style="font-size: 1.2vw; color: #4169E1;">Summary <span style="color: #D55C21;">(<?=$gist_dt;?>)</span></p>
            <?php
            //initial release
            
            if(($usertype == 1 OR $usertype == 50 OR $usertype == 51 OR $usertype == 17 OR $usertype == 14) AND ($list_dt > date('Y-m-d') OR ($list_dt == date('Y-m-d') AND time() <= strtotime('11:00:00') )) ){
            ?>
                <textarea class="btn-block" rows="10" maxlength="1000" style="width:100%; font-size: 1.2vw; color:red;" name="rremark_<?php echo $diary_no; ?>" id="rremark_<?php echo $diary_no; ?>"><?= $row_org["gist_remark"];?></textarea>
                <div class="insertbtn_summary" style="padding:5px;">
                <input type="button" name="bsave" id="bsave" class="btn btn-success btn-xs btn-block" value="UPDATE" onClick='javascript:updateRecordGist("<?php echo $verify_str; ?>")' />
                </div>
                <?php
            }
            else{
                ?>
                <p class="text-info" style="line-height: 180%; white-space: pre-wrap; font-size: 1.2vw; color: red;"><?=$row_org["gist_remark"];?></p>
                <?php
            }
            ?>
        
        </div>
            <?php
        }
        else{
            ?>
            <div style="text-align: left; padding: 5px;" >
                <p style="font-size: 1.2vw; color: #4169E1;">Summary</p>
                <?php
                if(($usertype == 1 OR $usertype == 50 OR $usertype == 51 OR $usertype == 17 OR $usertype == 14) AND ($list_dt > date('Y-m-d') OR ($list_dt == date('Y-m-d') AND time() <= strtotime('14:00:00') )) ){
                ?>
                <textarea class="btn-block" rows="10" style="width:100%; font-size: 1.2vw; color:red;" maxlength="1000" name="rremark_<?php echo $diary_no; ?>" id="rremark_<?php echo $diary_no; ?>"><?= $row_org["gist_remark"];?></textarea>
                <div class="updatebtn_summary" style="padding:5px;">
                <input type="button" name="bsave" id="bsave" value="SAVE" class="btn btn-primary btn-xs btn-block" onClick='javascript:addRecordGist("<?php echo $verify_str; ?>")' />
                </div>
                <?php
                }
                else{
                    ?>
                <p class="text-info" style="line-height: 180%; white-space: pre-wrap; font-size: 1.2vw; color: red;">Not Allowed due to time exceeded</p>
                <?php
                }
                ?>
            </div>

            <?php
        }
    }


    public function gist_updation()
    {
        $db = \Config\Database::connect();
        $ucode = $_SESSION['login']['usercode'];  
        $dno = $_POST['dno'];
        $list_dt =  date("Y-m-d", strtotime($_POST['list_dt'])); 
        $rremark = trim($_POST['rremark']);
        // Step 1: Update `or_gist` to set display = 'N'
        $updateData = [
            'display'    => 'N',
            'deleted_by' => $ucode,
            'deleted_on' => date('Y-m-d H:i:s'),
        ];

        $db->table('or_gist')
            ->where('diary_no', $dno)
            ->where('list_dt', $list_dt)
            ->where('display', 'Y')
            ->update($updateData);

        // Step 2: Insert new record into `or_gist`
        $insertData = [
            'diary_no'   => $dno,
            'list_dt'    => $list_dt,
            'gist_remark' => $rremark,
            'usercode'   => $ucode,
            'ent_dt'     => date('Y-m-d H:i:s'),
        ];

        $db->table('or_gist')->insert($insertData);

        // Step 3: Get the number of affected rows
        echo $db->affectedRows();
    }


    public function  gist_action() 
    {
        $db = \Config\Database::connect();
        $ucode = $_SESSION['login']['usercode'];
        $dno = $_REQUEST['dno'];
        $list_dt =  date("Y-m-d", strtotime($_REQUEST['list_dt']));
        $rremark = trim($_REQUEST['rremark']);

        // Prepare the data to insert
        $data = [
            'diary_no'   => $dno,
            'list_dt'    => $list_dt,
            'gist_remark' => $rremark,
            'usercode'   => $ucode,
            'ent_dt'     => date('Y-m-d H:i:s'),
        ];
 
        $db->table('or_gist')->insert($data);
 
        echo  $db->affectedRows();
    }


    


}
