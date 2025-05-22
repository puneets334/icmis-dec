<?php

namespace App\Controllers\Filing;

use App\Controllers\BaseController;
use App\Models\Filing\Model_diary;
use App\Models\Filing\Model_fil_trap;
use App\Models\Filing\Model_fil_trap_users;

class FileTrap extends BaseController
{
    public $Model_fil_trap_users;
    public $Model_fil_trap;
    public $Model_diary;

    function __construct()
    {
        $this->Model_diary = new Model_diary();
        $this->Model_fil_trap = new Model_fil_trap();
        $this->Model_fil_trap_users = new Model_fil_trap_users();
        ini_set('memory_limit', '4024M');
        $db = \Config\Database::connect();
    }

    public function index()
    {
        // pr($_SESSION['login']);
        $fil_trap_type = $this->Model_fil_trap->get_fil_trap_type_user_details();
        $cur_date = date('d-m-Y');
        $new_date = date('d-m-Y', strtotime($cur_date . ' + 60 days'));
        $cat = 0;
        $ref = 0;
        $condition = "and remarks=''";

        if (!empty($fil_trap_type)) {
            // pr($fil_trap_type);
            if ($fil_trap_type['usertype'] == 108) {
                $ref = 2;
                return view('Filing/file_trap_incomplete_view_no_record');
                //echo 'SORRY!!!, NO RECORD FOUND';
            } else {

                $data['fil_trap_type_row'] = $fil_trap_type;
                $data['fil_trap_list'] = $this->Model_fil_trap->get_fil_trap_list();
                return view('Filing/file_trap_incomplete_view', $data);
            }

            // pr('ashu38');
        } else {
            return view('Filing/file_trap_incomplete_view_no_record');
            //echo 'SORRY!!!, NO RECORD FOUND';
        }
        // $data['fil_trap_type']=$fil_trap_type;
        // return view('Filing/file_trap_view', $data);
    }
    public function getMatters()
    {
        $value = $this->request->getGet('q');
        // pr($value);

        $fileTrapModel = new Model_fil_trap();
        $data = $fileTrapModel->getMattersByUserType($value);

        // Generate the HTML view
        echo view('Filing/get_matters_view', ['data' => $data, 'value' => $value]);
    }
    public function test()
    {

        $this->db = \Config\Database::connect();
        $this->db->transStart();
        // code write
        $this->db->transComplete();
        exit();
    }
    public function incomplete()
    {
        $data['fil_trap_type_row'] = $this->Model_fil_trap->get_fil_trap_type_user_details();
        $data['fil_trap_list'] = $this->Model_fil_trap->get_fil_trap_list();

        return view('Filing/file_trap_incomplete_view', $data);
    }
    public function receive()
    {
        $given_to = '';
        $usercode = session()->get('login')['usercode'];
        $empid = session()->get('login')['empid'];
        $cat = 0;
        $ref = 0;
        $de = 0;
        $scr = 0;
        $tag = 0;
        $fdr = 0;
        $usertype = '';

        $uid = $_REQUEST['id'];
        if (empty($uid)) {
            echo 'uid is required';
            exit();
        }


        $check_fil_type = $this->Model_fil_trap->get_fil_trap_efiled_type_details($uid, '');
        if (!empty($check_fil_type)) {
            $fil_type = 'E';
        } else {
            $fil_type = 'P';
        }

        $fil_trap_type_row = $this->Model_fil_trap->get_fil_trap_type_user_details();

        if (!empty($fil_trap_type_row)) {
            $usertype = $fil_trap_type_row['usertype'];
            if ($fil_trap_type_row['usertype'] == 104)
                $ref = 1;
            if ($fil_trap_type_row['usertype'] == 105)
                $cat = 1;
            if ($fil_trap_type_row['usertype'] == 102)
                $de = 1;
            if ($fil_trap_type_row['usertype'] == 103)
                $scr = 1;
            if ($fil_trap_type_row['usertype'] == 106)
                $tag = 1;
            if ($fil_trap_type_row['usertype'] == 108)
                $fdr = 1;
            if ($fil_trap_type_row['usertype'] == 109)
                $de = 1;
            if ($fil_trap_type_row['usertype'] == 107)
                $fdr = 2;
        }
        if ($usercode == '29' || $usercode == '9796')
            $de = 1;
        $this->db = \Config\Database::connect();
        $this->db->transStart();
        if ($_REQUEST['value'] == 'R') {
            $ext_rec = '';
            $ck_adv_rec = '';
            $token_no = 0;
            $token_val = '';
            if ($fdr == 1 || ($de == 1 && $fil_trap_type_row['usertype'] != 102) || $fdr == 2) {
                //$ext_rec=",other=if($empid=d_to_empid,0,d_to_empid)";
                $ext_rec = ",other =CASE WHEN d_to_empid=$empid THEN 0 else $empid end";
            }
            //$ck_adv_rec=" if(d_to_empid=29,d_to_empid,$empid)";
            $ck_adv_rec = "CASE WHEN d_to_empid=29 THEN 29 else $empid end";
            $r_remarks = '';
            $r_chk_remark = is_data_from_table('fil_trap', ['uid' => $_REQUEST['id']], 'remarks,diary_no,r_by_empid', 'R');
            $r_remarks = $r_chk_remark['remarks'];


            if ($fdr == 1) {


                if ($r_remarks == 'FDR -> AOR') {
                    $token_arr = is_data_from_table('master.cnt_token', '', '', '');

                    if (!empty($token_arr)) {


                        if ($token_arr['date'] == null or $token_arr['date'] != date("Y-m-d")) {
                            $token_no = $token_no + 1;
                            //check duplicate token no.

                            if ($this->check_duplicate_token($token_no) == 1) {
                                /* echo "Duplicate Token found!!!! Please click again on Receive button ";
                                 exit();*/
                            }

                            $update_query_cnt_token = [
                                'token_no' => $token_no,
                                'date' => date("Y-m-d"),
                                'updated_on' => date("Y-m-d H:i:s"),
                                'updated_by' => $_SESSION['login']['usercode'],
                                'updated_by_ip' => getClientIP(),
                            ];

                            $is_update_query_cnt_token = update('master.cnt_token', $update_query_cnt_token, ['token_no !=' => $token_no]);
                        } else {
                            $token_no = $token_arr['token_no'] + 1;

                            if ($this->check_duplicate_token($token_no) == 1) {
                                //  echo "Duplicate Token found!!!! Please click again on Receive button ";
                                //  exit();
                            }


                            $update_query_cnt_token = [
                                'token_no' => $token_no,
                                'date' => date("Y-m-d"),
                                'updated_on' => date("Y-m-d H:i:s"),
                                'updated_by' => $_SESSION['login']['usercode'],
                                'updated_by_ip' => getClientIP(),
                            ];
                            $is_update_query_cnt_token = update('master.cnt_token', $update_query_cnt_token, ['token_no !=' => $token_no]);
                        }
                    } else {

                        $token_no = $token_no + 1;

                        $insert_cnt_token = [
                            'date' => date("Y-m-d"),
                            'token_no' => $token_no,
                            'create_modify' => date("Y-m-d H:i:s"),
                            'updated_by' => $_SESSION['login']['usercode'],
                            'updated_by_ip' => getClientIP(),
                        ];
                        $is_insert_cnt_token = insert('master.cnt_token', $insert_cnt_token);
                    }
                }
            }

            if ($r_remarks == 'FDR -> AOR') {

                if (!empty($_REQUEST['id'])) {
                    $result = $this->Model_fil_trap->update_main_refiling_attempt($_REQUEST['id']);
                }
            }


            if (!empty($_REQUEST['id'])) {
                $result = $this->Model_fil_trap->update_fil_trap_by_case($_REQUEST['id'], $ck_adv_rec, $ext_rec);
                //var_dump($result);
            }


            if ($fdr == 1) {
                if ($r_remarks == 'FDR -> AOR' && !empty($fil_type)) {
                    $given_to = $this->Model_fil_trap->allot_to_AOR($_REQUEST['id'], $empid, $r_remarks, '1', $fil_type);
                    $given_to = explode('~', $given_to);
                }
            }
            // bellow code not used for Scanning Cell counter
            /* if($usercode=='9796') {
                 $r_scn_qry='';
                 $r_case_details=array();
                 $scn_qry=is_data_from_table('fil_trap',['uid'=>$_REQUEST['id']],'remarks,diary_no,r_by_empid','R');
                 if (!empty($scn_qry)){
                     $r_scn_qry=$scn_qry['diary_no'];
                     if (!empty($scn_qry['diary_no'])){
                         $r_case_details=$this->Model_fil_trap->get_case_skey($scn_qry['diary_no']); // testing pending
                     }
                 }
                 $year=substr($r_scn_qry,-4);
                 $dnum=substr($r_scn_qry,0,-4);
                 $r_get_max_id=is_data_from_table('counter_receive_file',['uid'=>$_REQUEST['id']],'max(id)','R');
                 $r_get_max_id=$r_get_max_id+1;

                 $r_sel_cnt=is_data_from_table('counter_receive_file',['diary_no'=>$dnum,'year'=>$year]);

                 if(empty($r_sel_cnt))
                 {
                     date_default_timezone_set('Asia/Kolkata');
                     $rec_date=date('d-m-Y H:i');
                     $insert_counter_receive_file = [
                         'diary_no' => $dnum,
                         'year'=>$year,
                         'received_by' =>'Scanning Cell',
                         'id' => $r_get_max_id,
                         'casetype' => $r_case_details['skey'],
                         'rec_date' => date('d-m-Y H:i'),
                         'rec_user' => 9796,

                         'create_modify' => date("Y-m-d H:i:s"),
                         'updated_by'=>$_SESSION['login']['usercode'],
                         'updated_by_ip'=>getClientIP(),
                     ];
                     $is_insert_counter_receive_file= insert('counter_receive_file',$insert_counter_receive_file);

                 }
             }*/

            echo "Received Successfully";
        } else if ($_REQUEST['value'] == 'C') {
            $usercode = session()->get('login')['usercode'];
            $empid = session()->get('login')['empid'];


            $r_remarks = '';
            $dno = '';
            $r_chk_remark = is_data_from_table('fil_trap', ['uid' => $uid], 'diary_no,remarks,r_by_empid', 'R');
            if (!empty($r_chk_remark)) {
                $r_remarks = $r_chk_remark['remarks'];
                $dno = $r_chk_remark['diary_no'];
            } else {
                echo 'Diary number is required';
                exit();
            }


            if ($r_chk_remark['r_by_empid'] == '0') {

                $ck_adv_rec = "CASE WHEN d_to_empid=29 THEN 29 else $empid end";
                $ext_rec = '';
                if (!empty($uid)) {
                    $up_aor_fdr = $this->Model_fil_trap->update_fil_trap_by_case($uid, $ck_adv_rec, $ext_rec);
                }
            }
            if ($r_remarks == 'FDR -> AOR') {
                
                $update_main_refiling_attempt = [
                    'refiling_attempt' => date("Y-m-d H:i:s"),
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => $_SESSION['login']['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];
                if (!empty($dno)) {
                    $query_to_update_refiling_attempt = update('main', $update_main_refiling_attempt, ['diary_no=' => $dno]);
                }
            }
            if ($fdr == 1) {
                if ($r_remarks == 'FDR -> AOR') {
                    $given_to = $this->Model_fil_trap->allot_to_AOR($_REQUEST['id'], $_SESSION['icmic_empid'], $r_remarks, '1', $fil_type, $dno);
                }
            }
            //Ends here

            if ($ref == 0 && $cat == 0 && $fdr == 0 && $scr != 1) {

                $update_fil_trap_comp_dt = [
                    'comp_dt' => date("Y-m-d H:i:s"),
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => $_SESSION['login']['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];
                $query_to_update_fil_trap_comp_dt = update('fil_trap', $update_fil_trap_comp_dt, ['uid=' => $uid]);
            } else if ($ref == 1 || $cat == 1 || $fdr == 1 || ($de == 1 && $fil_trap_type_row['usertype'] != 102)) {

                if (!empty($uid)) {
                    $up_aor_fdr = $this->Model_fil_trap->update_fil_trap_comp_dt_by_uid($uid);
                }
            }


            $check_fil_type_type_c = $this->Model_fil_trap->get_fil_trap_efiled_type_details($uid);
            if (!empty($check_fil_type_type_c)) {
                $fil_type = 'E';
            } else {
                $fil_type = 'P';
            }

            if ($de == 1) {



                /* patch for non marking of jail petitions for scrutiny */
                $get_diary1 = is_data_from_table('fil_trap', ['uid' => $uid], 'diary_no', 'R');
                if (!empty($get_diary1)) {
                    $r_get_diary1 = $get_diary1['diary_no'];
                    $rs_jail = is_data_from_table('jail_petition_details', ['diary_no' => $r_get_diary1]);
                    if (empty($rs_jail) && !empty($usertype)) {
                        $given_to = $this->Model_fil_trap->allot_to_SCR($_REQUEST['id'], $_SESSION['login']['empid'], $usertype, $fil_type);
                    }
                }

                if (!empty($given_to) && trim($given_to) != '~') {
                    $given_to = explode('~', $given_to);
                } else {
                    $given_to = '';
                }


                if ($_SESSION['login']['usercode'] == '9796') {

                    $scn_qry = is_data_from_table('fil_trap', ['uid' => $uid], 'diary_no', 'R');
                    if (!empty($scn_qry)) {
                        $r_scn_qry = $scn_qry['diary_no'];
                        $r_case_details = $this->Model_fil_trap->get_case_skey($r_scn_qry);
                        $year = substr($r_scn_qry, -4);
                        $dnum = substr($r_scn_qry, 0, -4);


                        $rec_date = date('d-m-Y H:i');
                        $r_sel_cnt = is_data_from_table('counter_receive_file', ['diary_no' => $dnum, 'year' => $year], 'diary_no', 'R');

                        if (empty($r_sel_cnt)) {


                            $r_get_max_id = is_data_from_table('counter_receive_file', '', 'max(id)', 'R'); // testing pending akg
                            $r_get_max_id = $r_get_max_id + 1;
                            $rec_date = date('d-m-Y H:i');
                            $insert_counter_receive_file = [
                                'diary_no' => $dnum,
                                'year' => $year,
                                'received_by' => 'Scanning Cell',
                                'id' => $r_get_max_id,
                                'casetype' => $r_case_details['skey'],
                                'rec_date' => date('d-m-Y H:i'),
                                'rec_user' => 9796,

                                'create_modify' => date("Y-m-d H:i:s"),
                                'updated_by' => $_SESSION['login']['usercode'],
                                'updated_by_ip' => getClientIP(),
                            ];
                            $is_insert_counter_receive_file = insert('counter_receive_file', $insert_counter_receive_file);

                            if ($is_insert_counter_receive_file) {
                                $update_counter_receive_file = [
                                    'handover_to' => 'IB-Extension',
                                    'handover_by' => 'Scanning Cell',
                                    'handover_user' => 9796,
                                    'handover_date' => date('d-m-Y H:i'),
                                    'handover_status' => 1,

                                    'updated_on' => date("Y-m-d H:i:s"),
                                    'updated_by' => $_SESSION['login']['usercode'],
                                    'updated_by_ip' => getClientIP(),
                                ];
                                $query_to_update_fil_trap_comp_dt = update('counter_receive_file', $update_counter_receive_file, ['diary_no=' => $dnum, 'year=' => $year]);
                            }
                        } else {

                            $update_counter_receive_file = [
                                'handover_to' => 'IB-Extension',
                                'handover_by' => 'Scanning Cell',
                                'handover_user' => 9796,
                                'handover_date' => date('d-m-Y H:i'),
                                'handover_status' => 1,

                                'updated_on' => date("Y-m-d H:i:s"),
                                'updated_by' => $_SESSION['login']['usercode'],
                                'updated_by_ip' => getClientIP(),
                            ];
                            $query_to_update_fil_trap_comp_dt = update('counter_receive_file', $update_counter_receive_file, ['diary_no=' => $dnum, 'year=' => $year]);
                        }
                    }
                }
            } else if ($scr == 1) {

                //$given_to = allot_to_CAT_REF($_REQUEST['id'],$_SESSION['icmic_empid'],$fil_type);
                $given_to = $this->Model_fil_trap->allot_to_CAT_REF($_REQUEST['id'], $fil_type);
                $given_to = explode('~', $given_to);
            } else if ($ref == 1) {

                //$given_to = allot_to_CAT($_REQUEST['id'],$_SESSION['icmic_empid'],$fil_type);
                $given_to = $this->Model_fil_trap->allot_to_CAT($_REQUEST['id'], $_SESSION['login']['empid']);
                $given_to = explode('~', $given_to);
            } else if ($cat == 1 && ($_REQUEST['tag'] == 'Y')) {
                //$given_to = allot_to_TAG($_REQUEST['id'],$_SESSION['icmic_empid'],$fil_type);
                $given_to = $this->Model_fil_trap->allot_to_TAG($_REQUEST['id'], $fil_type);
                $given_to = explode('~', $given_to);
            } else if ($cat == 1 && ($_REQUEST['tag'] != 'Y')) {
                echo "Completed Successfully And Closed";
                $this->db->transComplete();
                exit();
            } else if ($tag == 1) {
                echo "Completed Successfully And Closed";
                $this->db->transComplete();
                exit();
            } else if ($fdr == 1) {

                $r_chk_remark = is_data_from_table('fil_trap', ['uid' => $uid], 'remarks', 'R');
                if (!empty($r_chk_remark) && !empty($fil_trap_type_row) && !empty($fil_type) && !empty($r_remarks)) {
                    $r_remarks =  $r_chk_remark['remarks'];
                    $given_to = $this->Model_fil_trap->allot_to_AOR($_REQUEST['id'], $_SESSION['login']['empid'], $r_remarks, $fil_trap_type_row['usertype'], '2', $fil_type, $dno);
                    $given_to = explode('~', $given_to);
                }
            }


            if ($r_remarks != 'AOR -> FDR'  && $r_remarks != 'FDR -> AOR') {
                echo "Completed Successfully ";
            }
            if (!empty($given_to)) {
                if ($_REQUEST['nature'] != 6 and $given_to[1] != '' and $given_to[1] != null) {
                    if (count($given_to) == 3) {
                        echo " And Automatically Allotted to @@@ : $given_to[1] [$given_to[0]]" . "  " . $given_to[2];
                    } else
                        echo " And Automatically Allotted to : $given_to[1] [$given_to[0]]";
                }
            }
        }
        $this->db->transComplete();
    }

    public function check_duplicate_token($t)
    {
        $duplicate = 0;
        //check duplicate token no.
        $query = "select * from (Select token_no from fil_trap where date(disp_dt)=CURRENT_DATE and
            token_no='$t' and (remarks='AOR -> FDR')
            union
            Select token_no from fil_trap_his where  date(disp_dt)=CURRENT_DATE and
            token_no='$t' and (remarks='AOR -> FDR'))a where token_no='$t'";

        $query = $this->db->query($query);
        if ($query->getNumRows() >= 1) {
            $duplicate = 1;
        }
        return $duplicate;
    }
    public function complete()
    {
        return view('Filing/file_trap_complete_view');
    }
    public function get_report_complete()
    {
        if ($this->request->getMethod() === 'post') {
            $from_date = $this->request->getPost('from_date');
            $to_date = $this->request->getPost('to_date');

            $this->validation->setRule('from_date', 'Date From', 'required');
            $this->validation->setRule('to_date', 'Date To', 'required');
            $data = [
                'from_date' => $from_date,
                'to_date' => $to_date,
            ];
            if (!$this->validation->run($data)) {
                // handle validation errors
                echo '3@@@';
                echo $this->validation->listErrors();
                exit();
            }
            $timestamp1 = strtotime($from_date);
            $timestamp2 = strtotime($to_date);
            if ($timestamp1 > $timestamp2) {
                echo "3@@@To Date must be greater than From date";
                exit();
            }
            //echo '<pre>';print_r($data);exit();

            $response = $this->Model_fil_trap->get_report_complete($data);

            echo '<pre>';
            print_r($data); //exit();
            echo '<pre>';
            print_r($response);
            exit();

            $data['result'] = $response_one;
            $resul_view = view('Filing/Efiling/file_trap_complete_get_content', $data);
            echo '1@@@' . $resul_view;
            exit();
            exit();
        } else {
            return view('Filing/Efiling/file_trap_complete_view');
        }
    }

    public function completedMatter()
    {

        $usercode = $_SESSION['login']['usercode'];
        // pr($_SESSION['login']);

        $data['emp_name_login'] = $_SESSION['login']['name'];
        $data['dcmis_user_idd'] = $usercode;
        $data['icmic_empid'] = $_SESSION['login']['empid'];

        // Get data using model
        $data['fil_trap_type_row'] = $this->Model_fil_trap_users->getUserTrapInfo($usercode);

        return view('Filing/file_trap_complete_matter_view',$data);
    }


    public function getFilTrapData()
    {
         
        $ucode = $_SESSION['login']['usercode'];
        $usection = $_SESSION['login']['section'];
        $data['icmic_empid'] = $_SESSION['login']['empid'];
        $data['cat'] = 0;
        $data['ref'] = 0;
        $data['fil'] = 0;

        $data['result'] = $this->Model_fil_trap_users->getReportData($data['cat'],$data['ref'], $data['fil'], $ucode, $data['icmic_empid']);

        $data['model'] = $this->Model_fil_trap_users;
        return view('Filing/complete_matter_get_report', $data);
    }

    public function DefectiveMatterRecordUpdateView()
    {
        return view('Filing/DefectiveMatterRecordUpdateView');
    }

    public function GetMatterInfo()
    {
        $dno = trim($_REQUEST['dno']);
        $dyr = trim($_REQUEST['dyr']);
        $module = trim($_REQUEST['module']);
        $diaryno = $dno . $dyr;


        // Load the database
        $db = \Config\Database::connect();
        // Query to get defect record paperbook details
        $builder = $db->table('master.defect_record_paperbook');
        $builder->select("section_id, court_fees, TO_CHAR(defect_notify_date, 'DD-MM-YYYY') as defect_notify_date, rack_no, shelf_no, id");
        $builder->where('diary_no', $diaryno);
        $builder->where('display', 'Y');
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            $row = $query->getRowArray();

            // Query to get section name
            $sectionQuery = $db->table('master.usersection')
                ->select('section_name')
                ->where('id', $row['section_id'])
                ->get();

            $section_name = $sectionQuery->getNumRows() > 0 ? $sectionQuery->getRowArray()['section_name'] : '';

            $court_fee = $row['court_fees'];
            $defect_notify_date = $row['defect_notify_date'];
            $rack_no = $row['rack_no'];
            $shelf_no = $row['shelf_no'];
            $id = $row['id'];

            echo $section_name . '~' . $court_fee . '~' . $defect_notify_date . '~' . $rack_no . '~' . $shelf_no . '~' . $id;
        } else {
            echo '0';
        }
    }

    public function SaveRecord()
    {
        // Retrieve request parameters
        $rackno = $this->request->getGet('rackno');
        $shelfno = $this->request->getGet('shelfno');
        $id = $this->request->getGet('id');
        $dcmis_user_idd = $_SESSION['login']['usercode'];
        $icmic_empid = $_SESSION['login']['empid'];
        $db = \Config\Database::connect();

        // Data to be updated
        $data = [
            'rack_no' => $rackno,
            'shelf_no' => $shelfno,
            'upd_dt' => date('Y-m-d H:i:s'),
            'upd_userid' => $dcmis_user_idd
        ];

        $builder = $db->table('master.defect_record_paperbook');
        $builder->where('id', $id);
        $builder->where('display', 'Y');

        if ($builder->update($data)) {
            echo "1";
        } else {
            echo "Error: " . __LINE__;
        }
    }

    public function deleteRecord()
    {
        $id = $this->request->getGet('id');

        if ($id) {
            $db = \Config\Database::connect();
            $builder = $db->table('master.defect_record_paperbook');
            $data = ['display' => 'N'];
            $builder->where('id', $id);
            $builder->where('display', 'Y');
            $result = $builder->update($data);

            if ($result) {
                echo "1";
            } else {
                echo "An error occurred while deleting the record.";
            }
        } else {
            echo "Invalid ID.";
        }
    }

    public function complete_view()
    {
        return view('Filing/comp_view');
    }

    public function get_trap()
    {
        $diaryno = $_REQUEST['dno'] = $_REQUEST['dno'] . $_REQUEST['dyr'];
        $data['result'] = $this->Model_fil_trap->get_trap_data($diaryno);
        return view('Filing/get_trap_table_view', $data);
    }
}
