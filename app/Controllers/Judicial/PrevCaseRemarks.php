<?php

namespace App\Controllers\Judicial;

use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;
use App\Models\Judicial\PrevCaseRemarksModel;
use App\Libraries\webservices\Efiling_webservices;
use App\Libraries\webservices\Highcourt_webservices;

class PrevCaseRemarks extends BaseController
{
    public $Dropdown_list_model;
    public $PrevCaseRemarksModel;

    function __construct()
    {
        $this->Dropdown_list_model = new Dropdown_list_model();
        $this->PrevCaseRemarksModel = new PrevCaseRemarksModel();
    }

    public function index()
    {
        $request = \Config\Services::request();

        $data = [];
        
        if ($request->getMethod() === 'post' && $this->validate([
            'search_type' => ['label' => 'search Type', 'rules' => 'required|min_length[1]|max_length[1]']
        ])) {
            
            $search_type = $this->request->getPost('search_type');
            
            if ($search_type == 'D' && $this->validate([
                'diary_number' => ['label' => 'Diary Number', 'rules' => 'required|min_length[1]|max_length[8]'],
                'diary_year' => ['label' => 'Diary Year', 'rules' => 'required|min_length[4]'],
            ])) {
                $diary_number = $this->request->getPost('diary_number');
                $diary_year = $this->request->getPost('diary_year');
                $diary_no = $diary_number . $diary_year;
                $get_main_table = $this->Dropdown_list_model->get_diary_details_by_diary_no($diary_no);
            } elseif ($search_type == 'C' && $this->validate([
                'case_type' => ['label' => 'Case Type', 'rules' => 'required|min_length[1]|max_length[2]'],
                'case_number' => ['label' => 'Case Number', 'rules' => 'required|min_length[1]|max_length[8]'],
                'case_year' => ['label' => 'Case Year', 'rules' => 'required|min_length[4]'],
            ])) {

                $case_type = $this->request->getPost('case_type');
                $case_number = $this->request->getPost('case_number');
                $case_year = $this->request->getPost('case_year');
                
                $get_main_table = $this->Dropdown_list_model->get_case_details_by_case_no($case_type, $case_number, $case_year);

                if($get_main_table === false) {
                    return $this->response->setJSON(['success' => 0, 'error' => 'Case not Found']);
                }

            } else {
                return $this->response->setJSON(['success' => 0, 'error' => 'Case not Found']);
            }

            if(empty($get_main_table)) {
                return $this->response->setJSON(['success' => 0, 'error' => 'Case not Found']);
            }
        }

        if (!empty($get_main_table)) {
            $this->session->set(array('filing_details' => $get_main_table));
            return $this->response->setJSON(['redirect' => base_url('Judicial/PrevCaseRemarks/prev_case_remarks')]);
        }

        $data['casetype'] = get_from_table_json('casetype');
        $data['sectionHeading'] = 'Judicial / Previous Case Remarks >> Diary Search';
        $data['formAction'] = 'Judicial/PrevCaseRemarks/index';

        return view('Judicial/PrevCaseRemarks/index', $data);
    }

    public function insert_rec_an()
    {
        $request = \Config\Services::request();

        $str = $request->getPost('str');
        $str1 = $request->getPost('str1');
        $dt = $request->getPost('dt');
        $hdt = $request->getPost('hdt');
        $ucode = session()->get('login')['usercode'];
        //$ucode=$request->getPost('ucode');
        if ($request->getPost('uip') !== null)
            $uip1 = $request->getPost('uip');
        if ($request->getPost('umac') !== null)
            $umac1 = $request->getPost('umac');
        $old_new = $request->getPost('old_new');
        if ($request->getPost('concstr') !== null)
            $concstr = $request->getPost('concstr');
        else
            $concstr = "";
        if ($request->getPost('sno') !== null)
            $snop = $request->getPost('sno');
        else
            $snop = '';
        //$nextCourt=$request->getPost('nextCourt');
        $statusSide = $request->getPost('statusSide');
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
        $result_crm = $this->PrevCaseRemarksModel->getRemarks($fno, $dt);
        foreach($result_crm as $row_crm) {
            $remarks_db = $row_crm['remarks'];
        }
        //end
        for ($ivar = 0; $ivar < $cncntr; $ivar++) 
        {
            if ($ivar > 0)
                $fno = $cncases[$ivar - 1];
            if ($status == "P") {
                //undo_before($fno, $dt, $ucode, $uip1, $umac1);
                if (strtotime($dt) == strtotime(date('Y-m-d'))) {

                    $builder = $this->db->table('docdetails');

                    // Prepare data to update
                    $data = [
                        'iastat'  => 'P',
                        'lst_mdf' => 'NOW()', // PostgreSQL uses 'NOW()' similarly to MySQL
                        'lst_user' => $ucode
                    ];

                    // Add conditions to the query
                    $builder->set($data)
                        ->where('diary_no', $fno)
                        ->where('iastat', 'D')
                        ->where('DATE(lst_mdf)', $dt) // Ensure $dt is properly formatted (use `DATE()` in PostgreSQL)
                        ->where('doccode', 8)
                        ->where('display', 'Y');

                    // Execute the query
                    $builder->update();

                    // $str_ia_p = "UPDATE docdetails SET iastat='P', lst_mdf=NOW(),lst_user=$ucode WHERE diary_no='" . $fno . "' AND iastat='D' AND DATE(lst_mdf)='" . $dt . "' AND doccode=8 AND display='Y'";
                    // mysql_query($str_ia_p) or die(mysql_error() . $str_ia_p);
                    //add_category($fno,0);
                    //add_category($fno,50);
                    //add_category($fno,63);
                }
            }
            
            if ($status == "D") {
                $builder = $this->db->table('docdetails');

                // Prepare data to update
                $data = [
                    'iastat' => 'D',
                    'lst_mdf' => 'NOW()', // PostgreSQL uses 'NOW()' to get the current timestamp
                    'dispose_date' => $dt,
                    'last_modified_by' => $ucode
                ];

                // Add conditions to the query
                $builder->set($data)
                    ->where('diary_no', $fno)
                    ->where('iastat', 'P')
                    ->where('doccode', 8)
                    ->where('display', 'Y');

                // Execute the update query
                $builder->update();

                // $str_ia_d = "UPDATE docdetails SET iastat='D', lst_mdf=NOW(),dispose_date='$dt',last_modified_by=$ucode WHERE diary_no='" . $fno . "' AND iastat='P' AND doccode=8 AND display='Y'";
                // mysql_query($str_ia_d) or die(mysql_error() . $str_ia_d);
                //delete_category($fno,50);
                //delete_category($fno,63);
                //delete_category($fno,0);
            }
            $rcount = count($rec_rem);
            //echo $fno.$dt.$ucode.$old_new;
            //added on 19.1.2019 to avoid duplicate entries
            // if ($remarks_db != rtrim($rec[2],'!')) {
            //end

            $this->PrevCaseRemarksModel->clear_remarks($fno, $dt, $ucode, $old_new);

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
                //$today = date("Y-m-d");// current date
                //$tomorrow = strtotime(date("Y-m-d", strtotime($today)) . " +1 day");
                //$output = date("Y-m-d", $tomorrow);
                //echo strpos((",21,22,23,24,25,26,52,53,54,68,69,70,71,72,73,74,75,"),(",".$head.","))."sdfsfs";
                //CHECK FOR PAPERBOOK NOT RECEIVED
                if ($head == 128 or $head == 153 or $head == 126 or $head == 152) {

                    $builder = $this->db->table('main');
                    $builder->select('dacode, diary_no as caseno')
                        ->where('diary_no', $fno);

                    // Execute the query and get the result
                    $results_selm1 = $builder->get();

                    // $str_sel_main1 = "SELECT dacode, diary_no as caseno FROM main where diary_no='" . $fno . "'";
                    // $results_selm1 = mysql_query($str_sel_main1) or die(mysql_error());
                    $dacode = "";
                    $caseno = "";
                    if ($this->db->affectedRows() > 0) {
                        $row_selm1 = $results_selm1->getRowArray();
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
                        // Prepare data to insert
                        $data = [
                            'to_user'   => $dacode,
                            'from_user' => $ucode,
                            'msg'       => $msg_p,
                            'ipadd'     => $umac1
                        ];

                        // Perform the insert query using the query builder
                        $builder = $this->db->table('msg');
                        $builder->insert($data);

                        // $sql_p = "insert into msg(to_user,from_user,msg,ipadd) values('$dacode','$ucode','$msg_p','$umac1')";
                        // mysql_query($sql_p) or die(mysql_error() . $sql_p);
                    }
                }
                //CHECK FOR PAPERBOOK NOT RECEIVED
                // Prepare data to insert
                $data = [
                    'diary_no'    => $fno,
                    'cl_date'     => $dt,
                    'r_head'      => $head,
                    'e_date'      => 'NOW()',  // PostgreSQL uses NOW() for current timestamp
                    'jcodes'      => $jcodes,
                    'head_content' => $head_cont,
                    'mainhead'    => $mainhead,
                    'clno'        => $clno,
                    'uid'         => $ucode,
                    'dw'          => '',
                    'remark'      => '',
                    'usr_entry'   => 0,
                    'notice_type'   => 0,
                    'comp_remarks'   => '',
                    'status'      => $status,
                    'remove'      => 0
                ];

                // Perform the insert query using the query builder
                $builder = $this->db->table('case_remarks_multiple');
                $builder->insert($data);

                // $str_ins = "INSERT INTO case_remarks_multiple(diary_no, cl_date, r_head, e_date, jcodes, head_content,mainhead,clno,uid,status,remove) values('" . $fno . "','" . $dt . "'," . $head . ", NOW(),'" . $jcodes . "','" . $head_cont . "','" . $mainhead . "'," . $clno . "," . $ucode . ",'" . $status . "',0)";
                //mysql_query($str_ins) or die(mysql_error() . $str_ins);
                /* if($statusSide==1){
                    if($nextCourt!="")
                        mysql_query($str_ins) or die(mysql_error() . $str_ins);
                    else {
                        echo "Data not updated! Please refresh your screen by pressing ctrl+F5 before updation.";
                        exit(0);
                    }}
                else*/
                // mysql_query($str_ins) or die(mysql_error() . $str_ins);

                if ($head == 81 or $head == 74 or $head == 75 or $head == 65 or $head == 2 or $head == 1 or $head == 94) {
                    // Prepare data to update
                    $data = [
                        'admitted' => 'admitted on ' . $dt . ', entered on ' . date('Y-m-d')
                    ];

                    // Perform the update query using the query builder
                    $builder = $this->db->table('main');
                    $builder->set($data)
                        ->where('diary_no', $fno)
                        ->update();

                    // $str_upmain = "UPDATE main SET admitted='admitted on " . $dt . ", entered on " . date('Y-m-d') . "' where diary_no='" . $fno . "'";
                    // mysql_query($str_upmain) or die(mysql_error() . $str_upmain);
                }
                //IA DISPOSAL
                if ($head == 22 or $head == 26 or $head == 95 or $head == 142) {
                    if (trim($head_cont) != '') {
                        $ia = explode(",", trim($head_cont));
                        for ($ii = 0; ($ii < (count($ia))); $ii++) {
                            $ia1 = explode("/", trim($ia[$ii]));
                            $ia_num = trim($ia1[0]);
                            $ia_yr = trim($ia1[1]);
                            if ($ia_num != '' and $ia_yr != '') {
                                // Prepare data to update
                                $data = [
                                    'iastat'           => 'D',
                                    'lst_mdf'          => 'NOW()',  // PostgreSQL uses 'NOW()' for the current timestamp
                                    'dispose_date'     => $dt,
                                    'last_modified_by' => $ucode
                                ];

                                // Perform the update query using the query builder
                                $builder = $this->db->table('docdetails');
                                $builder->set($data)
                                    ->where('diary_no', $fno)
                                    ->where('docnum', $ia_num)
                                    ->where('docyear', $ia_yr)
                                    ->where('doccode', 8)
                                    ->where('display', 'Y')
                                    ->update();

                                // $str_ia = "UPDATE docdetails SET iastat='D', lst_mdf=NOW(),dispose_date='$dt',last_modified_by=$ucode WHERE diary_no='" . $fno . "' AND docnum=" . $ia_num . " AND docyear=" . $ia_yr . " AND doccode=8 AND display='Y'";
                                // mysql_query($str_ia) or die(mysql_error() . $str_ia);
                                //if(($_REQUEST[m_doc1]==50 || $_REQUEST[m_doc1]==63) && $_REQUEST[ddlIASTAT]=='D')
                            }
                        }
                        //delete_category($fno,50);
                        //delete_category($fno,63);
                    }
                }
                //IA DISPOSAL
                //
                //echo $fno.$dt.$head.$head_cont.$hdt;
                //echo $i."-".$str1;
                /*if($str1!="")
                {
                if (!(mysql_query($str1)))
                    $err=$err.$fno.", ";
                    else
                    {
                    //update_cis
                    $pos=strpos("DELETE", $str1 );
                    //echo "'".$fno."','".$dt."',".$head.",'".$head_cont."'";
                    //if($pos!==false)
                    //update_cis($fno,$dt,$head,$head_cont);
                    }
                }*/
                //if($old_new==0)
                //
                //    if($head != 55 && $head != 125 && $head != 16){
                //       if($old_new==0 || $old_new=='')
                //        do_receive_file_on_rdr_remark($fno,$ucode);
                //    }
                if ($head == 200 or $head == 201)
                    $delay = 1;
                if ($head == 176 or $head == 177 or $head == 178)
                    $delay = 2;
            }

            // } end of if statement of remarks comparison
            //if($old_new=='H' || $old_new=='')
            //{
            //if($status=="D" and $check_case_withdraw!="YES")
            //if($status=="D" and ($subh=="804" or $subh=="805"))
            //set_before($fno, $tj1, $tj2, $tj3, $tj4, $tj5, $ucode, $uip1, $umac1);
            //var_dump($head_r);var_dump($head_c);
            //echo $fno."-".$dt."-".$head_r."-".$head_c."-".$hdt."-".$ucode;


            //Code added by preeti on 3.7.2019 to add 2 more disposal remarks-Delay condone and permission to file SLP
            $casetype = "";
            $t_lc = 0;
            $t_lcid = 0;
            $count = 0;
            $check_var = '';
            $cyear = date('Y');

            // Perform the SELECT query using the query builder
            $builder = $this->db->table('main');
            $builder->select('casetype_id, active_casetype_id')->where('diary_no', $fno);

            // Execute the query
            $results_casetype = $builder->get();

            // $sql_casetype = "select * from main where diary_no='$fno'";
            // $results_casetype = mysql_query($sql_casetype) or die(mysql_error() . $sql_casetype);
            if ($this->db->affectedRows() > 0) {
                $row_casetype = $results_casetype->getRowArray();
                $casetype = $row_casetype['casetype_id'];
                $activeCasetype = $row_casetype['active_casetype_id'];
            }

            // Perform the SELECT query using the query builder
            $builder = $this->db->table('lowerct');
            $builder->select('count(*) as count, string_agg(lower_court_id::text, \',\') as lcid')
                ->where('diary_no', $fno)
                ->where('lw_display', 'Y')
                ->where('is_order_challenged', 'Y');

            // Execute the query
            $resultsl = $builder->get();

            // $sql_l = "SELECT count(*),GROUP_CONCAT(lower_court_id) as lcid FROM `lowerct` WHERE `diary_no` =" . $fno . " and lw_display='Y' and is_order_challenged='Y'";
            // $resultsl = mysql_query($sql_l) or die(mysql_error() . $sql_l);

            if ($this->db->affectedRows() > 0) {
                $row_l = $resultsl->getRowArray();
                
                $t_lc =   $row_l['count'];
                $t_lcid = $row_l['lcid'];
            }
            if ($delay == 1) {
                $allowed_case_type = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
                $allowed_case_type1 = array(1, 2, 3, 4, 7, 8, 9, 10, 19, 20, 25, 26, 39);
                
                // Perform the SELECT query using the query builder
                $builder = $this->db->table('obj_save');
                $builder->select('count(id) as count')
                    ->where('diary_no', $fno)
                    ->where('display', 'Y')
                    ->where('rm_dt', '0000-00-00 00:00:00');

                // Execute the query
                $query = $builder->get();

                $sql_obj_check = $query->getRowArray();
                $res_obj_check = $sql_obj_check['count'];

                // $sql_obj_check = "Select count(id) from obj_save where diary_no = '$fno'  and display='Y' and
                //     rm_dt='0000-00-00 00:00:00'";
                // $sql_obj_check = mysql_query($sql_obj_check) or die("Error: " . __LINE__ . mysql_error());
                // $res_obj_check = mysql_result($sql_obj_check, 0);

                if ($res_obj_check > 0) {

                    // Prepare data to update
                    $data = [
                        'rm_dt' => 'NOW()',  // PostgreSQL uses NOW() for the current timestamp
                        'rm_user_id' => $ucode
                    ];

                    // Perform the update query using the query builder
                    $builder = $this->db->table('obj_save');
                    $builder->set($data)
                        ->where('diary_no', $fno)
                        ->where('display', 'Y')
                        ->update();

                    // $sql_obj_rm = "UPDATE obj_save SET rm_dt=now(),rm_user_id=$ucode WHERE diary_no='" . $fno . "' and display='Y'";
                    // mysql_query($sql_obj_rm) or die(mysql_error() . $sql_obj_rm);
                }
                // echo $fno."-".$t_lc;
                if ($t_lc == 0 and $casetype != 5 and $casetype != 6)
                    continue;
                if ((($activeCasetype == null or $activeCasetype == '' or $activeCasetype == 0 or $activeCasetype == 13 or $activeCasetype == 14 or $activeCasetype == 31) and ((in_array($casetype, $allowed_case_type1)) and $t_lc != 0)) or (($t_lc == 0 and ($casetype == 5 or $casetype == 6)) and ($activeCasetype == null or $activeCasetype == '' or $activeCasetype == 0)))
                // if(($activeCasetype==null or $activeCasetype=='' or $activeCasetype==0) and ($casetype==1 or $casetype==2))
                {
                    if ($t_lc == 0 and ($casetype == 5 or $casetype == 6))
                        $t_lc = 1;
                    
                    // Perform the SELECT query using the query builder
                    $builder = $this->db->table('kounter');
                    $builder->select('knt')
                        ->where('year', $cyear)
                        ->where('casetype_id', $casetype);

                    // Execute the query
                    $query = $builder->get();

                    // Check if rows are returned and fetch the result
                    if ($query->getNumRows() > 0) {
                        $row_max = $query->getRowArray();
                        $count = $row_max['knt'];  // Access the 'knt' value
                    }

                    $count_max = $count + $t_lc;

                    // Prepare the data to be updated
                    $data = [
                        'knt' => $count_max
                    ];

                    // Perform the update using the query builder
                    $builder = $this->db->table('kounter');
                    $builder->set($data)
                        ->where('year', $cyear)
                        ->where('casetype_id', $casetype)
                        ->update();

                    // Check if the update was successful
                    if ($this->db->affectedRows() <= 0) {
                        $check_var = 'error';
                    }

                    // $upd_case_ct = "Update kounter set knt='$count_max' where year='$cyear' and casetype_id='" . $casetype . "'";
                    // $result = mysql_query($upd_case_ct) or die(mysql_error() . $upd_case_ct);
                    // if (!$result) {
                    //     $check_var = 'error';
                    // }

                    $reg_no = str_pad($casetype, 2, "0", STR_PAD_LEFT) . '-' . str_pad(($count + 1), 6, "0", STR_PAD_LEFT) . '-' . str_pad($count_max, 6, "0", STR_PAD_LEFT);
                    $regNoDisplay = $this->PrevCaseRemarksModel->getRegistrationNumberDisplay($fno, $reg_no, $cyear);
                    
                    // Prepare the data to update
                    $data = [
                        'fil_no' => $reg_no,
                        'fil_dt' => 'NOW()',  // PostgreSQL uses NOW() for the current timestamp
                        'usercode' => $ucode,
                        'mf_active' => 'M',
                        'active_fil_no' => $reg_no,
                        'active_fil_dt' => 'NOW()',  // PostgreSQL uses NOW() for the current timestamp
                        'active_reg_year' => $cyear,
                        'reg_year_mh' => $cyear,
                        'active_casetype_id' => $casetype,
                        'reg_no_display' => $regNoDisplay
                    ];

                    // Perform the update query using the query builder
                    $builder = $this->db->table('main');
                    $builder->set($data)
                        ->where('diary_no', $fno)
                        ->update();

                    // Check if the update was successful
                    if ($this->db->affectedRows() <= 0) {
                        $check_var = 'error';
                    }

                    // Prepare the data to insert
                    $data = [
                        'diary_no' => $fno,
                        'new_registration_number' => $reg_no,
                        'new_registration_year' => $cyear,
                        'order_date' => $hdt,
                        'updated_on' => 'NOW()',  // PostgreSQL uses NOW() for the current timestamp
                        'is_deleted' => 'f',
                        'adm_updated_by' => $ucode
                    ];

                    // Perform the insert query using the query builder
                    $builder = $this->db->table('main_casetype_history');
                    $upd_main_ct = $builder->insert($data);

                    // Check if the insert was successful
                    if (!$upd_main_ct) {
                        $check_var = 'error';
                    }

                    $t_lcid_exp = explode(',', $t_lcid);
                    for ($j = 0; $j < $t_lc; $j++) {
                        $fil_no = str_pad($casetype, 2, "0", STR_PAD_LEFT) . str_pad(($count + $j + 1), 6, "0", STR_PAD_LEFT) . $cyear;

                        // Prepare the data to insert
                        $data = [
                            'lowerct_id' => $t_lcid_exp[$j],
                            'diary_no' => $fno,
                            'fil_no' => $fil_no,
                            'entuser' => $ucode,
                            'entdt' => 'NOW()',  // PostgreSQL uses NOW() for the current timestamp
                            'casetype_id' => $casetype,
                            'case_no' => ($j + $count + 1),  // Calculating the case number
                            'case_year' => $cyear
                        ];

                        // Perform the insert query using the query builder
                        $builder = $this->db->table('registered_cases');
                        $ins_sql = $builder->insert($data);

                        // Check if the insert was successful
                        if (!$ins_sql) {
                            $check_var = 'error12335';
                        } else {
                            $dno = substr($fno, 0, -4);
                            $dyr = substr($fno, -4);
                            $_REQUEST['dno'] = $dno;
                            $_REQUEST['dyr'] = $dyr;
                            //include('../scrutiny/get_and_set_da.php');

                        }
                    }
                }
                $msg = $fno . " - " . $regNoDisplay;
                //echo "going into include file";
                // include('get_and_set_da.php');
                $dno = $_REQUEST['dno'] . $_REQUEST['dyr'];
                $this->PrevCaseRemarksModel->get_da($dno);
                // echo "file Included";
                /*if ($check_var == '')
                    echo "done   ".$msg;
                else

                    echo "Error";*/

                //end
            }

            if ($delay == 2) {
                $count = 0;
                $mf_info = $this->PrevCaseRemarksModel->get_cases_mf($fno);
                $t_var = explode(",", $mf_info);
                $nos_generate = explode('||', $t_var[5]);
                $t_nos_m = explode('#', $nos_generate[0]);
                $t_nos_f = explode('#', $nos_generate[1]);
                //echo "t_nos_m".$t_nos_m[0]."   t_nos_f".$t_nos_f[0];
                if ($t_nos_m[0] == 'Y') {
                    if ($t_lc <= 0)
                        continue;

                    // Perform the SELECT query to check if records exist
                    $builder = $this->db->table('obj_save');
                    $builder->select('count(id) AS count');
                    $builder->where('diary_no', $fno);
                    $builder->where('display', 'Y');
                    $builder->where('rm_dt', '0000-00-00 00:00:00');
                    $query = $builder->get();

                    // Get the result of the query
                    $res_obj_check = $query->getRow()->count;

                    // Check if records exist
                    if ($res_obj_check > 0) {
                        // Perform the UPDATE query if records exist
                        $data = [
                            'rm_dt' => 'NOW()',  // PostgreSQL uses NOW() for the current timestamp
                            'rm_user_id' => $ucode
                        ];

                        // Perform the UPDATE query
                        $builder = $this->db->table('obj_save');
                        $builder->set($data)
                                ->where('diary_no', $fno)
                                ->where('display', 'Y')
                                ->update();
                    }

                    $check_var = '';

                    // Prepare the query to get the current count
                    $builder = $this->db->table('kounter');
                    $builder->select('knt');
                    $builder->where('year', $cyear);
                    $builder->where('casetype_id', $t_nos_m[1]);
                    $query = $builder->get();

                    // Check if the result exists
                    if ($query->getNumRows() > 0) {
                        // Fetch the row and get the count value
                        $row_max = $query->getRowArray();
                        $count = $row_max['knt'];
                    } else {
                        $count = 0; // Default to 0 if no rows were found
                    }

                    // Calculate the new count
                    $count_max = $count + $t_lc;

                    // Prepare the data to update
                    $data = [
                        'knt' => $count_max
                    ];

                    // Perform the update query
                    $builder = $this->db->table('kounter');
                    $builder->set($data);
                    $builder->where('year', $cyear);
                    $builder->where('casetype_id', $t_nos_m[1]);

                    $update = $builder->update();

                    // Check if the update was successful
                    if (!$update) {
                        $check_var = 'error';
                    }

                    $reg_no = str_pad($t_nos_m[1], 2, "0", STR_PAD_LEFT) . '-' . str_pad(($count + 1), 6, "0", STR_PAD_LEFT) . '-' . str_pad($count_max, 6, "0", STR_PAD_LEFT);

                    $regNoDisplay = $this->PrevCaseRemarksModel->getRegistrationNumberDisplay($fno, $reg_no, $cyear);

                    // Initialize the check variable
                    $check_var = '';

                    // Prepare the data for updating the `main` table
                    $update_data = [
                        'fil_no' => $reg_no,
                        'fil_dt' => 'NOW()', // PostgreSQL uses NOW() for the current timestamp
                        'usercode' => $ucode,
                        'mf_active' => 'M',
                        'active_fil_no' => $reg_no,
                        'active_fil_dt' => 'NOW()', // PostgreSQL uses NOW() for the current timestamp
                        'active_reg_year' => $cyear,
                        'reg_year_mh' => $cyear,
                        'active_casetype_id' => $t_nos_m[1],
                        'reg_no_display' => $regNoDisplay
                    ];

                    // Update the `main` table
                    $builder = $this->db->table('main');
                    $builder->set($update_data);
                    $builder->where('diary_no', $fno);
                    $update_result = $builder->update();

                    // Check if the update was successful
                    if (!$update_result) {
                        $check_var = 'error';
                    }

                    // Prepare the data for inserting into the `main_casetype_history` table
                    $insert_data = [
                        'diary_no' => $fno,
                        'new_registration_number' => $reg_no,
                        'new_registration_year' => $cyear,
                        'ref_new_case_type_id' => $t_nos_m[1],
                        'order_date' => $dt,
                        'updated_on' => 'NOW()', // PostgreSQL uses NOW() for the current timestamp
                        'is_deleted' => 'f', // assuming it's a boolean flag stored as 'f' for false
                        'adm_updated_by' => $ucode
                    ];

                    // Insert into the `main_casetype_history` table
                    $builder = $this->db->table('main_casetype_history');
                    $insert_result = $builder->insert($insert_data);

                    // Check if the insert was successful
                    if (!$insert_result) {
                        $check_var = 'error';
                    }

                    $t_lcid_exp = explode(',', $t_lcid);
                    for ($j = 0; $j < $t_lc; $j++) {
                        // Generate the fil_no
                        $fil_no = str_pad($casetype, 2, "0", STR_PAD_LEFT) . str_pad(($count + $j + 1), 6, "0", STR_PAD_LEFT) . $cyear;

                        // Prepare the data to insert
                        $data = [
                            'lowerct_id' => $t_lcid_exp[$j],
                            'diary_no' => $fno,
                            'fil_no' => $fil_no,
                            'entuser' => $ucode,
                            'entdt' => 'NOW()', // PostgreSQL uses NOW() for the current timestamp
                            'casetype_id' => $casetype,
                            'case_no' => $j + $count + 1,
                            'case_year' => $cyear
                        ];

                        // Insert into the `registered_cases` table
                        $builder = $this->db->table('registered_cases');
                        $insert_result = $builder->insert($data);

                        // Check if the insert was successful
                        if (!$insert_result) {
                            $check_var = 'error';
                        } else {
                            // Extract and store parts of the diary number for further processing
                            $dno = substr($fno, 0, -4);
                            $dyr = substr($fno, -4);
                            $_REQUEST['dno'] = $dno;
                            $_REQUEST['dyr'] = $dyr;

                            // Optionally include additional scripts here if needed
                            // include('../scrutiny/get_and_set_da.php');
                            // include('get_and_set_da.php');
                        }
                    }
                } //end of if(t_nos_m=='y'
                $count = 0;
                if ($t_nos_f[0] == 'Y') {
                    if ($t_lc <= 0)
                        continue;

                    $check_var = '';

                    // Prepare the query to select `knt` from `kounter`
                    $builder = $this->db->table('kounter');
                    $builder->select('knt');
                    $builder->where('year', $cyear);
                    $builder->where('casetype_id', $t_nos_f[1]);
                    $query = $builder->get();
                    
                    // Check if a record was found
                    if ($query->getNumRows() > 0) {
                        // Get the first result (since `get()` returns an array of results)
                        $row_max = $query->getRowArray();
                        $count = $row_max['knt'];
                    }
                    
                    // Calculate the new count
                    $count_max = $count + $t_lc;
                    
                    // Prepare the data for updating the `kounter` table
                    $update_data = [
                        'knt' => $count_max
                    ];
                    
                    // Update the `kounter` table
                    $builder = $this->db->table('kounter');
                    $builder->set($update_data);
                    $builder->where('year', $cyear);
                    $builder->where('casetype_id', $t_nos_f[1]);
                    $update_result = $builder->update();
                    
                    // Check if the update was successful
                    if (!$update_result) {
                        $check_var = 'error';
                    }

                    $reg_no = str_pad($t_nos_f[1], 2, "0", STR_PAD_LEFT) . '-' . str_pad(($count + 1), 6, "0", STR_PAD_LEFT) . '-' . str_pad($count_max, 6, "0", STR_PAD_LEFT);
                    $regNoDisplay = $this->PrevCaseRemarksModel->getRegistrationNumberDisplay($fno, $reg_no, $cyear);
                    //code to change section id while conversion to CA if matter belongs to section-XI,IVB,XIV
                    $section = "";

                    // Prepare the query to select `active_casetype_id`, `ref_agency_state_id`, and `ref_agency_code_id` from `main`
                    $builder = $this->db->table('main');
                    $builder->select('active_casetype_id, ref_agency_state_id, ref_agency_code_id');
                    $builder->where('diary_no', $fno);

                    // Execute the query and retrieve the result
                    $query = $builder->get();

                    // Check if a record is found
                    if ($query->getNumRows() > 0) {
                        // Fetch the first result (as an associative array)
                        $check_section = $query->getRowArray();
                    } else {
                        // Handle the case where no records are found
                        $check_section = [];
                    }

                    //UP matters
                    if ($check_section['active_casetype_id'] == 1 and $check_section['ref_agency_state_id'] == '61023' and in_array($check_section['ref_agency_code_id'], array('15', '16'), TRUE)) {
                        $section = ",section_id=23 "; //section XI->III-A
                    }
                    //Maharashtra matters from IX to III
                    else if ($check_section['active_casetype_id'] == 1 and $check_section['ref_agency_state_id'] == '358033' and in_array($check_section['ref_agency_code_id'], array('31', '32', '33', '34'), TRUE)) {
                        $section = ",section_id=22 "; //section XIV->XIVA
                    }
                    //Punjab matters
                    else if ($check_section['active_casetype_id'] == 1 and $check_section['ref_agency_state_id'] == '226817' and in_array($check_section['ref_agency_code_id'], array('10'), TRUE)) {
                        $section = ",section_id=24 "; //section IVB->IV
                    }
                    //Delhi HP North East
                    else if ($check_section['active_casetype_id'] == 1 and in_array($check_section['ref_agency_state_id'], array('490506', '571779', '291560', '537722', '349528', '348677', '355594', '184724', '511231', '167131'), TRUE) and in_array($check_section['ref_agency_code_id'], array('14', '27', '28', '29', '30', '86', '137', '138', '139', '149', '150', '153', '53', '183', '184', '9', '299'), TRUE)) {
                        $section = ",section_id=80 "; //section XIV->XIVA
                    }
                    //code to change section ends
                    $check_var = '';

                    // Step 1: Get data from the `main` table
                    $builder = $this->db->table('main');
                    $builder->select('*');
                    $builder->where('diary_no', $fno);
                    $query_main = $builder->get();

                    // Check if the row exists
                    if ($query_main->getNumRows() > 0) {
                        $row_main = $query_main->getRowArray();

                        $sql = "
                            UPDATE main
                            SET
                                fil_no_fh = '$reg_no',
                                $section
                                fil_dt_fh = NOW()
                                usercode = '$ucode',
                                mf_active = 'F',
                                active_fil_no = '$reg_no',
                                active_fil_dt = NOW()
                                active_reg_year = '$cyear',
                                reg_year_fh = '$cyear',
                                reg_year_mh = EXTRACT(YEAR FROM CAST('" . $row_main['fil_dt'] . "' AS DATE)),
                                active_casetype_id = '" . $t_nos_f[1] . "',
                                reg_no_display = '$regNoDisplay'
                            WHERE diary_no = '$fno';
                        ";

                        $update_result = $this->db->query($sql);

                        if (!$update_result) {
                            $check_var = 'error';
                        }

                        $insert_data = [
                            'diary_no' => $fno,
                            'old_registration_number' => $row_main['fil_no'],
                            'old_registration_year' => $row_main['reg_year_mh'],
                            'ref_old_case_type_id' => $row_main['casetype_id'],
                            'new_registration_number' => $reg_no,
                            'new_registration_year' => $cyear,
                            'ref_new_case_type_id' => $t_nos_f[1],
                            'order_date' => $dt,
                            'updated_on' => 'now()',
                            'is_deleted' => 'f',
                            'adm_updated_by' => $ucode
                        ];

                        $builder = $this->db->table('main_casetype_history');
                        $insert_result = $builder->insert($insert_data);

                        if (!$insert_result) {
                            $check_var = 'error';
                        }
                    } else {
                        $check_var = 'error'; // If no record is found
                    }

                    // Step 4: Output the result
                    if ($check_var == '') {
                        $output .= "<td>" . $regNoDisplay . "</td>";
                    } else {
                        $output .= "<td>Error</td>";
                    }
                }
                $dno = substr($fno, 0, -4);
                $dyr = substr($fno, -4);
                $_REQUEST['dno'] = $dno;
                $_REQUEST['dyr'] = $dyr;
                $dno = $_REQUEST['dno'] . $_REQUEST['dyr'];
                //include('get_and_set_da.php');
                $msg = $fno . " - " . $regNoDisplay;
                $this->PrevCaseRemarksModel->get_da($dno);
            } //end of if(delay==2)
            //preeti's code end


            $this->PrevCaseRemarksModel->update_cis($fno, $dt, $head_r, $head_c, $hdt, $ucode, $old_new, $snop/*,$nextCourt*/);
            //}
            // else {
            //     update_lastheardt($fno,$dt,$head_r,$head_c,$hdt,$ucode,$snop);
            //   update_lastorder($fno,$dt,$head_r,$head_c,$hdt,$ucode,$snop);
            //}
        }
    }

    public function check_parties() {
        
        $request = \Config\Services::request();

        $dt = $request->getPost('dt');
        $cn = $request->getPost('cn');

        if ($cn != "" and $dt != "") {
            $this->PrevCaseRemarksModel->check_parties($cn, $dt);
        }
    }

    public function prev_case_remarks() {

        $data = [];

        // $data['db'] = db_connect();
        // $data['PrevCaseRemarksModel'] = $this->PrevCaseRemarksModel;
        // pr(session()->get('login'));

        $data['ucode'] = session()->get('login')['usercode'];
        $data['usection'] = session()->get('login')['section'];

        $filing_details = session()->get('filing_details');

        $data['diary_num'] = substr($filing_details['diary_no'], 0, -4);
        $data['diary_year'] = substr($filing_details['diary_no'], -4);
        
        $diary_no = $filing_details['diary_no'];

        $data['diary_no'] = $filing_details['diary_no'];
        $data['actcode'] = $filing_details['actcode'];
        $data['lastorder'] = $filing_details['lastorder'];
        $data['c_status'] = $filing_details['c_status'];
        $data['filling_no'] = $filing_details['diary_no'];

        $party_details = $this->PrevCaseRemarksModel->getPartyDetails($diary_no);
        foreach ($party_details as $indexKey => $eachParty) {

            $district_name = $this->PrevCaseRemarksModel->getDistrictName($eachParty['state'], $eachParty['city']);

            $party_details[$indexKey]['district_name'] = $district_name;
        }

        $data['party_details'] = $party_details;

        $data['lowerct_details'] = $this->PrevCaseRemarksModel->getLowerctDetails($diary_no);

        $lower_case_temp_row = $this->PrevCaseRemarksModel->getLowerctCaseTypeDetails($diary_no);
        $data['lower_case_temp_row'] = $lower_case_temp_row;

        ###################
        $for_da_temp_row = [];
        if(!empty($lower_case_temp_row['lct_caseno'])) {
            $casetype_id = $lower_case_temp_row['lct_casetype'];
            $case_year = $lower_case_temp_row['lct_caseyear'];
            $case_number = str_pad($lower_case_temp_row['lct_caseno'], 6, '0', STR_PAD_LEFT);

            $for_da_temp_row = $this->PrevCaseRemarksModel->getLowerctTentativeSection($casetype_id, $case_number, $case_year);
        }

        $section_ten_row = '';

        if(!empty($for_da_temp_row)) {
            if ($for_da_temp_row['active_reg_year'] != 0)
                $ten_reg_yr = $for_da_temp_row['active_reg_year'];
            else if ($for_da_temp_row['reg_year_fh'] != 0)
                $ten_reg_yr = $for_da_temp_row['reg_year_fh'];
            else if ($for_da_temp_row['reg_year_mh'] != 0)
                $ten_reg_yr = $for_da_temp_row['reg_year_mh'];
            else
                $ten_reg_yr = date('Y', strtotime($for_da_temp_row['diary_no_rec_date']));

            if ($for_da_temp_row['active_casetype_id'] != 0)
                $casetype_displ = $for_da_temp_row['active_casetype_id'];
            else if ($for_da_temp_row['casetype_id'] != 0)
                $casetype_displ = $for_da_temp_row['casetype_id'];

            $state_id = $for_da_temp_row['ref_agency_state_id'];
            
            $section_ten_row = $this->PrevCaseRemarksModel->getTentativeSection($casetype_displ, $state_id, $ten_reg_yr);
        }

        $for_da_temp_row['section_ten_row'] = $section_ten_row;
    
        $data['for_da_temp_row'] = $for_da_temp_row;
        ###################

        ###################
        $row_da = $this->PrevCaseRemarksModel->getDADetails($diary_no);

        if(!empty($row_da)) {
            if ($row_da['active_reg_year'] != 0)
                $ten_reg_yr = $row_da['active_reg_year'];
            else if ($row_da['reg_year_fh'] != 0)
                $ten_reg_yr = $row_da['reg_year_fh'];
            else if ($row_da['reg_year_mh'] != 0)
                $ten_reg_yr = $row_da['reg_year_mh'];
            else
                $ten_reg_yr = date('Y', strtotime($row_da['diary_no_rec_date']));

            // $row_da['ten_reg_yr'] = $ten_reg_yr;

            if ($row_da['active_casetype_id'] != 0)
                $casetype_displ = $row_da['active_casetype_id'];
            else if ($row_da['casetype_id'] != 0)
                $casetype_displ = $row_da['casetype_id'];
                
            $row_da['casetype_displ'] = $casetype_displ;

            $state_id = $row_da['ref_agency_state_id'];

            $section_ten_row = $this->PrevCaseRemarksModel->getTentativeSection($casetype_displ, $state_id, $ten_reg_yr);

            $row_da['section_ten_row'] = $section_ten_row;
        }

        $data['row_da'] = $row_da;
        ###################

        $act_rows = $this->PrevCaseRemarksModel->getActDetails($diary_no);
        $data['act_rows'] = $act_rows;

        $advocate_rows = $this->PrevCaseRemarksModel->getAdvocateDetails($diary_no);
        $data['advocate_rows'] = $advocate_rows;


        $law_row = $this->PrevCaseRemarksModel->getActLawDetails($filing_details['actcode']);
        $data['law_row'] = $law_row;

        $result_next_dt = $this->PrevCaseRemarksModel->getNextHearingDate($diary_no);
        $data['result_next_dt'] = $result_next_dt;
        
        $r_ttv = $this->PrevCaseRemarksModel->getTentativeDate($diary_no);
        $data['r_ttv'] = $r_ttv;

        $flag_tentative_listing_date = $this->PrevCaseRemarksModel->getCaseStatusFlag('tentative_listing_date');
        $data['flag_tentative_listing_date'] = $flag_tentative_listing_date;

        $flag_case_updation = $this->PrevCaseRemarksModel->getCaseStatusFlag('case_updation');
        $data['flag_case_updation'] = $flag_case_updation;
        
        $result_remarks = $this->PrevCaseRemarksModel->getCaseRemarksMultiple($diary_no);
        $data['result_remarks'] = $result_remarks;

        $result_listing = $this->PrevCaseRemarksModel->getListingDetails($diary_no);
        foreach ($result_listing as $indexKey => $eachListing) {

            $row_cr = $this->PrevCaseRemarksModel->getCaseRemarkMultiple($eachListing['diary_no'], $eachListing['next_dt']);
            $result_listing[$indexKey]['row_cr'] = $row_cr;

            $row_lp123 = $this->PrevCaseRemarksModel->getUserDetails($eachListing['diary_no']);
            $result_listing[$indexKey]['row_lp123'] = $row_lp123;

            $reslt_validate_caseInAdvanceList = $this->PrevCaseRemarksModel->ifInAdvanceList($eachListing['diary_no']);
            $result_listing[$indexKey]['reslt_validate_caseInAdvanceList'] = $reslt_validate_caseInAdvanceList;

            $row_working_dates = $this->PrevCaseRemarksModel->getWorkingDates($eachListing['next_dt']);
            $result_listing[$indexKey]['row_working_dates'] = $row_working_dates;

            $row_connected_cases = $this->PrevCaseRemarksModel->getConnectedCases($eachListing['diary_no'], $eachListing['next_dt']);
            $result_listing[$indexKey]['row_connected_cases'] = $row_connected_cases;
        }

        $data['result_listing'] = $result_listing;
        
        $is_courtMaster = $this->PrevCaseRemarksModel->checkCourtMaster($data['ucode']);
        // pr($is_courtMaster);
        $data['is_courtMaster'] = $is_courtMaster;
        
        $results_ian = $this->PrevCaseRemarksModel->getInterlocutaryApplications($diary_no);
        $data['results_ian'] = $results_ian;

        $results_od = $this->PrevCaseRemarksModel->getDocumentsFiled($diary_no);
        $data['results_od'] = $results_od;

        $results_conncases = $this->PrevCaseRemarksModel->get_conn_cases($diary_no);
        foreach ($results_conncases as $indexKey => $link) {        
            $results_conncases[$indexKey]['get_real_diaryno'] = get_real_diaryno($link['diary_no']);
            $get_mul_category =  $this->PrevCaseRemarksModel->get_mul_category($link['diary_no']);
            $results_conncases[$indexKey]['get_mul_category'] = $get_mul_category[0];
            $results_conncases[$indexKey]['get_ia'] = $this->PrevCaseRemarksModel->get_ia($link['diary_no']);
            $results_conncases[$indexKey]['get_main_details'] = $this->PrevCaseRemarksModel->get_main_details($link['diary_no'], 'diary_no,pet_name,res_name,c_status');
            $results_conncases[$indexKey]['get_brd_remarks'] = $this->PrevCaseRemarksModel->get_brd_remarks($link['diary_no'], 'diary_no,pet_name,res_name,c_status');
        }
        
        // print_r($results_conncases);

        $data['holiday_dates'] = getSCHolidays();

        $data['get_conn_cases'] = $results_conncases;

        $caseRemarksHeadPending = $this->PrevCaseRemarksModel->getCaseRemarksHeadPending();
        $data['caseRemarksHeadPending'] = $caseRemarksHeadPending;

        $sql_judges = $this->PrevCaseRemarksModel->getJudgesDetails();
        $data['sql_judges'] = $sql_judges;

        $caseRemarksHeadDisposed = $this->PrevCaseRemarksModel->getCaseRemarksHeadDisposed();
        $data['caseRemarksHeadDisposed'] = $caseRemarksHeadDisposed;

        return view('Judicial/PrevCaseRemarks/prev_case_remarks', $data);
    }
}