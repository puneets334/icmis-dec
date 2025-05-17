<?php

namespace App\Controllers\Filing;

use App\Controllers\Filing\Similarity;
use App\Controllers\Filing\Coram;
use App\Controllers\Filing\Category;
use App\Controllers\Coram\Coram_query;
use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Filing\VerificationModel;



class Verification extends BaseController
{
    protected $session;
    public $VerificationModel;
    public $diary_no;
    
    function __construct()
    {
        $this->session = \Config\Services::session();        
        $this->VerificationModel = new VerificationModel();

        if (empty(session()->get('filing_details')['diary_no'])) {
            $uri = current_url(true);             
            
            $getUrl = str_replace('/', '-', $uri->getPath());
            header('Location:'.base_url('Filing/Diary/search?page_url='.base64_encode($getUrl)));exit();
            exit();
        } else {
            $this->diary_no = session()->get('filing_details')['diary_no'];
        }

    }

    public function verifyRecord()
    {

        $model = new VerificationModel();
        $sessionData = $this->session->get();
        $ucode = $sessionData['login']['usercode'];
        $diary_no = $sessionData['filing_details']['diary_no'];
        $defects['get_verification_text'] = '';
        $defects['result'] = $model->getUnverifiedDefects($diary_no);
        $defects['partydetails'] = $model->getPetDetailsByDiaryNo($diary_no);
        $defects['partydetails1'] = array($sessionData['filing_details']);
        $defects['cavetor_adv'] = $model->getCaveatAdvocates($diary_no);
        $advocateNames = [];
        foreach ($defects['cavetor_adv'] as $advocate) {
            $advocateNames[] = $advocate->name;
        }
        $defects['advocate_names'] = $advocateNames;
        $defects['res_adv'] = $model->getPetDetailsByDiaryNo($diary_no);
        $resadv = [];
        foreach ($defects['res_adv'] as $advocate) {
            $resadv[] = $advocate->name;
        }
        $defects['res_adv'] = $resadv;
        $defects['ia'] = $model->getDocDetailsByDiaryNo($diary_no);
        $ia_details = [];
        foreach ($defects['ia'] as $doc) {
            $ia_details[] = [
                'docnum' => $doc->docnum,
                'docyear' => $doc->docyear,
                'other1' => $doc->other1,
                'doccode1' => $doc->doccode1,
                'docdesc' => $doc->docdesc,
                'ent_dt' => $doc->ent_dt
            ];
        }


        $defects['cat'] = $model->getcategory($diary_no);
        $cat_details_list = [];
        foreach ($defects['cat'] as $cat_detail) {

            $cat_details_list[] = [
                'row_cat' => $cat_detail->id,
                'category_sc_old' => $cat_detail->category_sc_old,
                'sub_name1' => $cat_detail->sub_name1,
                'sub_name4' => $cat_detail->sub_name4,
                'id' => $cat_detail->id,
            ];
            // 

        }


        $defects['cat_emp1'] = $this->VerificationModel->other_categoryinfo($diary_no);
        $cat_code = $id = $defects['cat'][0]->id ?? '';
        if (!empty($defects['cat_emp1'])) {
            $defects['cat_code'] = $cat_code . '~' . 't';
        } else {
            $defects['cat_code'] = $cat_code . '~' . 'f';
        }
        $defects['getnature'] = $model->getnature($diary_no);
        //$getnature = $model->getnature($diary_no)->short_description;
        $tagging_user = $model->checkTaggingUser($ucode);
        $defects['tagging_user'] = $tagging_user;
        $defects['getbench'] = $model->getbench($diary_no);
        $defects['proof'] = $model->checkproof($diary_no);
        $defects['connected_output'] = $model->get_connected($diary_no);
        $Url_Similarity = new Similarity();
        $defects['Url_Similarity'] = $Url_Similarity->index('N');
        $Url_Coram = new Coram_query();
        $defects['Url_Coram'] = $Url_Coram->index('Url_Coram');
        $editcoram = new Coram();
        $defects['editcoram'] = $editcoram->coram_add('editcoram');
        $category = new Category();
        $defects['category'] = $category->index('category');
 
        $defects['get_verification_text'] =  $this->get_verification_dup();

        return view('Filing/verification_view', $defects);
    }

    public function verifySearch()
    {
        $model = new VerificationModel();
        $sessionData = $this->session->get();
        $diary_no = $sessionData['filing_details']['diary_no'];
        $ucode = $sessionData['login']['usercode'];
        $data['partydetails'] = $model->getPetDetailsByDiaryNo($diary_no);
        $data['partydetails1'] = array($sessionData['filing_details']);
        $data['cavetor_adv'] = $model->getCaveatAdvocates($diary_no);
        $advocateNames = [];
        foreach ($data['cavetor_adv'] as $advocate) {
            $advocateNames[] = $advocate->name;
        }
        $data['advocate_names'] = $advocateNames;
        $data['res_adv'] = $model->getPetDetailsByDiaryNo($diary_no);
        $resadv = [];
        foreach ($data['res_adv'] as $advocate) {
            $resadv[] = $advocate->name;
        }
        $data['res_adv'] = $resadv;
        $resadv1 = $data['res_adv'][1];
        $data['ia'] = $model->getDocDetailsByDiaryNo($diary_no);
        $ia_details = [];
        foreach ($data['ia'] as $doc) {
            $ia_details[] = [
                'docnum' => $doc->docnum,
                'docyear' => $doc->docyear,
                'other1' => $doc->other1,
                'doccode1' => $doc->doccode1,
                'docdesc' => $doc->docdesc,
                'ent_dt' => $doc->ent_dt
            ];
        }
        $data['cat'] = $model->getcategory($diary_no);
        $data['cat_emp1'] = $this->VerificationModel->other_categoryinfo($diary_no);
        $cat_code = $id = $data['cat'][0]->id;
        $cat_details_list = [];
        foreach ($data['cat'] as $cat_detail) {
            $cat_details_list[] = [
                'category_sc_old' => $cat_detail->category_sc_old,
                'sub_name1' => $cat_detail->sub_name1,
                'sub_name4' => $cat_detail->sub_name4,
                'id' => $cat_detail->id,
            ];
        }
        if (!empty($data['cat_emp1'])) {
            $cat_code = $cat_code . '~' . 't';
        } else {
            $cat_code = $cat_code . '~' . 'f';
            // print_r($cat_code);
            // exit();
        }
        $data['getbench'] = $model->getbench($diary_no);
        $data['proof'] = $model->checkproof($diary_no);
        $data['connected_output'] = $model->get_connected($diary_no);
        $coram_data = $model->coram_detail($diary_no);
        $get_coram_entry_date = $model->get_coram_entry_date($diary_no, '539');
        $coram_detail = [];
        foreach ($coram_data as $coram_val) {
            if ($coram_val['notbef'] == 'C') {
                $get_coram_entry_date = $model->get_coram_entry_date($diary_no, $coram_val['coram']);
                $coram_val['entry_date'] = date('d-m-Y H:i:s', strtotime($get_coram_entry_date[0]['ent_dt']));
                $coram_val['update_by'] = $get_coram_entry_date[0]['name'] . '[' . $get_coram_entry_date[0]['empid'] . ']';
            } else {
                $coram_val['entry_date'] = date('d-m-Y H:i:s', strtotime($coram_val['ent_dt']));
                $coram_val['update_by'] = $coram_val['name'] . '[' . $coram_val['empid'] . ']';
            }

            $coram_detail[] = $coram_val;
        }
        $data['coram_detail'] = $coram_detail;
        $data['getnature'] = $model->getnature($diary_no);
        $getnature = $model->getnature($diary_no)->short_description;
        $tagging_user = $model->checkTaggingUser($ucode);
        $data['tagging_user'] = $tagging_user;
        $data['defects'] = $model->getUnverifiedDefects($diary_no);
        $Url_Coram = new Coram_query();
        $data['Url_Coram'] = $Url_Coram->index('Url_Coram');
        $editcoram = new Coram();
        $data['editcoram'] = $editcoram->index('editcoram');
        $category = new Category();
        $data['category'] = $category->updateCategory('category');
        return view('Filing/verification_search', $data);
    }

    public function addbutton()
    {
        $model = new VerificationModel();
        $sessionData = $this->session->get();
        $ucode = $sessionData['login']['usercode'];
        $tagging_user = $model->checkTaggingUser($ucode);
        $diary_no = $sessionData['filing_details']['diary_no'];
        $data['cat'] = $model->getcategory($diary_no);
        $data['cat_emp'] = $model->other_category($diary_no);
        $cat_details_list = [];
        foreach ($data['cat'] as $cat_detail) {
            $cat_details_list[] = [
                'category_sc_old' => $cat_detail->category_sc_old,
                'sub_name1' => $cat_detail->sub_name1,
                'sub_name4' => $cat_detail->sub_name4,
                'id' => $cat_detail->id,
            ];
        }
        $data = [
            'tagging_user' => $tagging_user,
        ];
        $data['cat_emp1'] = $this->VerificationModel->other_categoryinfo($diary_no);
        $cat_code = $id = $data['cat'][0]->id;
        if (!empty($data['cat_emp1'])) {
            $cat_code = $cat_code . '~' . 't';
        } else {
            $cat_code = $cat_code . '~' . 'f';
            // print_r($cat_code);
            // exit();
        }
        return view('Filing/add_button', $data);
    }

    public function get_verification_dup()
    {
        $diary_message = '';
        $sessionData = session()->get();
        $ucode = $sessionData['login']['usercode'];
        $diary_no = $sessionData['filing_details']['diary_no'];

        $db = \Config\Database::connect();
        $disposedQuery = $db->table('main')
            ->select('c_status')
            ->where('diary_no', $diary_no)
            ->get();

        if ($disposedQuery->getNumRows() > 0) {
            $disposed = $disposedQuery->getRow();

            if ($disposed->c_status == 'D') {
                $diary_message = 'Matter is Disposed';
            }
        }
        $check_if_verified = $db->table('defects_verification')
            ->where('verification_status', '0')
            ->where('diary_no', $diary_no)
            ->get();

        if ($check_if_verified->getNumRows() > 0) {
            $diary_message = 'Record Already Verified';
        }

        $sql_q = $db->query("SELECT pet_name, res_name, TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') as dt, case_grp, fil_no, c_status, casetype_id 
                        FROM main 
                        WHERE diary_no = '$diary_no'");

        if ($sql_q->getNumRows() > 0) {
            $row = $sql_q->getRow();
            $result_casetype = $row->casetype_id;
            $check_section = $db->query("SELECT * FROM master.users u 
                                   JOIN master.usersection us ON u.section = us.id 
                                   WHERE u.usercode = '$ucode' AND u.display = 'Y'");

            if ($check_section->getNumRows() > 0 && $ucode != 1) {
                $check_section_user = $check_section->getRow();

                if ($check_section_user->section != 19 && $check_section_user->usercode != 1494) {
                    if ($check_section_user->usertype == 4 || $check_section_user->usertype == 6) {
                        $casetype = ['9', '10', '19', '20', '25', '26', '39'];
                        if (!in_array($result_casetype, $casetype)) {
                            $diary_message = 'Verification can be done in RP/CUR.P/CONT.P./MA';
                        } else if (in_array($result_casetype, $casetype)) {
                            echo "<input type='hidden' name='hd_flag' id='hd_flag' value='1'/>";
                        }
                    } else {
                        $diary_message = 'Only AR/DR is authorized for Verification';
                        exit();
                    }
                }
            }
        } else {
            $diary_message = 'Diary No. Not Found';
        }

        if (empty($diary_message)) {
            $diary_message = $this->include_verification();
        }
        return $diary_message;
    }
    public function update_verification()
    {

        $sessionData = $this->session->get();
        $hd_diary_nos = $this->request->getVar('hd_diary_nos');
        $flag = $this->request->getVar('hd_flag');
        $id_val = $this->request->getVar('id_val');
        $sessionData = $this->session->get();
        $ucode = $sessionData['login']['usercode'];
        $sendto = 0;
        $model = new VerificationModel();
        $data['cat'] = $model->getcategory($hd_diary_nos);
        // pr($data['cat']);
        $cat_details_list = [];
        $cat_code='';
        foreach ($data['cat'] as $cat_detail) {
            $cat_details_list[] = [
                'category_sc_old' => $cat_detail->category_sc_old,
                'sub_name1' => $cat_detail->sub_name1,
                'sub_name4' => $cat_detail->sub_name4,
                'id' => $cat_detail->id,
            ];
            $cat_code = $cat_detail->id;
        }
        $data['cat_emp1'] = $this->VerificationModel->other_categoryinfo($hd_diary_nos);
        // $cat_code = $data['cat'][0]->id;

        if (!empty($data['cat_emp1'])) {
            $cat_code = $cat_code . '~' . 't';
        } else {
            $cat_code = $cat_code . '~' . 'f';
            // print_r($cat_code);
            // exit();
        }

        $data['fil_trap_type_rs'] = $model->checkFiltrapuser($ucode);
        if (!empty($data['fil_trap_type_rs'])) {
            $fil_trap_type_row = $data['fil_trap_type_rs'][0];
            if ($fil_trap_type_row->usertype == 105) {
                $sendto = 'T';
            } elseif ($fil_trap_type_row->usertype == 110) {
                $sendto = 'C';
            }
        }
        $caveatResult = $model->checkCaveat($hd_diary_nos);
        // $caveatCount = isset($caveatResult['count']) ? $caveatResult['count'] : 0;
        $t_with = $model->checkWithDocument($hd_diary_nos);

        // print_r($caveatResult);
        // exit();

        $chk_status = 0;
        if ($t_with > 0) {
            $chk_status = 0;
        } elseif ($caveatResult > 0) {

            $db = \Config\Database::connect();
            $chk_document_query = "SELECT COUNT(diary_no) as count FROM docdetails WHERE diary_no = ? AND display = 'Y' AND doccode = 18 AND doccode1 = 0";
            $query = $db->query($chk_document_query, [$hd_diary_nos]);

            if (!$query) {
                die("Error: " . $db->error());
            }
            $row = $query->getRow();
            $res_chk_document = $row->count;

            if ($res_chk_document == 0) {
                $chk_status = 0;
            } else {
                $chk_status = 1;
            }
            // print_r($chk_status); exit;


        }

        if ($chk_status == 0) {
            $defectsVerificationCount = $model->checkDefectsVerificationCount($hd_diary_nos)['id'];
            if ($defectsVerificationCount <= 0) {
                $data = [
                    'diary_no' => $hd_diary_nos,
                    'verification_status' => $this->request->getVar('id_val'),
                    'verification_date' => date('Y-m-d H:i:s'),
                    'user_id' => $ucode,
                    'remarks' => '',
                    'user_ip' => getClientIP(),
                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];
                $model->insertDefectsVerification($data);
            } else {
                $data = [
                    'verification_status' => $this->request->getVar('id_val'),
                    'verification_date' => date('Y-m-d H:i:s'),
                    'user_ip' => getClientIP(),
                    'user_id' => $ucode,
                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];
                $model->updateDefectsVerification($data, $hd_diary_nos);
            }
            if ($this->request->getVar('id_val') == 0) {
                $this->call_listing();
                echo "\nRecord Verified Successfully";
               
            } else if ($this->request->getVar('id_val') == 1)
                $this->move_case_in_filing_trap($hd_diary_nos, $id_val, $sendto);
                echo "\nRecord Updated for Tagging Successfully";
           
        } else {
            echo "Caveat matched in this case and Proof of service application not filed yet.";
        }
        // return view('Filing/add_button', $data);
    }

    public function move_case_in_filing_trap($diary_no, $defectsVerificationCount, $sendto)
    {
        $sessionData = $this->session->get();
        $ucode = $sessionData['login']['usercode'];
        if ($defectsVerificationCount == 0) {
            if ($sendto == 'C')
                $this->receiveInCAT($diary_no, $ucode, $sendto, '2');
            else if ($sendto == 'T')
                $this->receive_in_TAG($diary_no, $ucode, $sendto, '2');
        } else if ($defectsVerificationCount == 1) {
            $tag_user =  $this->allot_to_TAG($diary_no, $ucode);
            echo " Matter alloted to" . $tag_user;
        }
    }
    public function receiveInCAT($diary_no, $ucode, $sendto, $chk_status)
    {
        $sessionData = $this->session->get();
        $loginUserCode = $sessionData['login']['usercode'];
        $loginEmpId = $sessionData['login']['empid'];
        $model = new VerificationModel();
        $uid = $model->getUidByDiaryNo($diary_no);
        $remarks = $model->getremarks($diary_no);

        $check_if_CAT_ava = $model->check_if_CAT_ava($diary_no);

        foreach ($check_if_CAT_ava as $toUserno) {

            $toUserno = $toUserno;
        }

        if ($uid) {
            $data = [
                'diary_no' => $diary_no,
                'd_to_empid' => $toUserno,
                'r_by_empid' => $loginEmpId,
                'disp_dt' => date('Y-m-d H:i:s'),
                'rece_dt' => date('Y-m-d H:i:s'),
                'comp_dt' => date('Y-m-d H:i:s'),
                'other' => $ucode,
                'remarks' => ($remarks == 'SCR -> CAT' || $remarks == 'FDR -> CAT') ? $remarks : 'AUTO -> CAT',
                'create_modify' => date("Y-m-d H:i:s"),
                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by' => $loginUserCode,
                'updated_by_ip' => getClientIP(),
            ];
            // print_r($data); exit();
            $model->updateFilTrap($uid, $data);
        } else {

            $check_if_CAT_ava = $model->check_if_CAT_ava($diary_no);
            foreach ($check_if_CAT_ava as $toUserno) {
                $toUserno = $toUserno;
            }
            $data = [
                'diary_no' => $diary_no,
                'd_to_empid' => $toUserno,
                'r_by_empid' => $ucode,
                'disp_dt' => date('Y-m-d H:i:s'),
                'rece_dt' => date('Y-m-d H:i:s'),
                'comp_dt' => date('Y-m-d H:i:s'),
                'd_by_empid' => ($sendto == 29) ? $sendto : $loginEmpId,
                'other' => $ucode,
                'remarks' => 'AUTO -> CAT',
                'create_modify' => date("Y-m-d H:i:s"),
                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by' => $loginUserCode,
                'updated_by_ip' => getClientIP(),
            ];
            $model->insertIntoFilTrap($data);
        }
        $this->insert_into_history($uid, $sendto);
        if ($chk_status == 2) {
            // Add your code here if needed
        }
    }
    public function receive_in_TAG($diary_no, $ucode, $sendto, $chk_status)
    {
        $sessionData = $this->session->get();
        $loginUserCode = $sessionData['login']['usercode'];
        $loginEmpId = $sessionData['login']['empid'];
        $model = new VerificationModel();
        $uid = $model->getUidByDiaryNo($diary_no);
        $remarks = $model->getremarks($diary_no);

        if ($uid) {
            $check_if_CAT_ava = $model->check_if_TAG_ava1($diary_no);
            foreach ($check_if_CAT_ava as $toUserno) {
                $toUserno = $toUserno;
            }
            $data = [
                'diary_no' => $diary_no,
                'd_to_empid' => $toUserno,
                'r_by_empid' => $ucode,
                'disp_dt' => date('Y-m-d H:i:s'),
                'rece_dt' => date('Y-m-d H:i:s'),
                'comp_dt' => date('Y-m-d H:i:s'),
                'd_by_empid' => ($sendto == 29) ? $sendto : $loginEmpId,
                'other' => $ucode,
                'remarks' => 'AUTO -> TAG',
                'create_modify' => date("Y-m-d H:i:s"),
                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by' => $loginUserCode,
                'updated_by_ip' => getClientIP(),


            ];
            $model->insertIntoFilTrap($data);
        } else {
            $check_if_CAT_ava = $model->check_if_CAT_ava($diary_no);
            foreach ($check_if_CAT_ava as $toUserno) {
                $toUserno = $toUserno;
            }
            $data = [

                'diary_no' => $diary_no,
                'd_to_empid' => $toUserno,
                'r_by_empid' => $loginEmpId,
                'disp_dt' => date('Y-m-d H:i:s'),
                'rece_dt' => date('Y-m-d H:i:s'),
                'comp_dt' => date('Y-m-d H:i:s'),
                'other' => $ucode,
                'remarks' => ($remarks == 'SCR -> CAT' || $remarks == 'FDR -> CAT') ? $remarks : 'AUTO -> TAG',
                'create_modify' => date("Y-m-d H:i:s"),
                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by' => $loginUserCode,
                'updated_by_ip' => getClientIP(),
            ];
            $model->updateFilTrap($uid, $data);
        }
        $this->insert_into_history($uid, $sendto);
        if ($chk_status == 2) {
            // Add your code here if needed
        }
    }

    function insert_into_history($uid, $sendto)
    {
        $sessionData = $this->session->get();
        $loginUserCode = $sessionData['login']['usercode'];
        $loginEmpId = $sessionData['login']['empid'];
        $model = new VerificationModel();
        $diary_no = $sessionData['filing_details']['diary_no'];
        $remarks = $model->getremarks($diary_no);

        $uid = $model->getUidByDiaryNo($diary_no);
        if ($uid > 0) {
            $filtrap = $model->getfiltap($diary_no);


            $data = [
                'diary_no' => $filtrap->diary_no,
                'd_to_empid' => $filtrap->d_to_empid,
                'r_by_empid' => $filtrap->r_by_empid,
                'disp_dt' => $filtrap->disp_dt,
                'rece_dt' => $filtrap->rece_dt,
                'comp_dt' => $filtrap->comp_dt,
                'disp_dt_seq' => $filtrap->disp_dt_seq,
                'd_by_empid' => $filtrap->d_by_empid,
                'other' => $filtrap->other,
                'remarks' => ($remarks == 'SCR -> CAT' || $remarks == 'FDR -> CAT') ? $remarks : 'AUTO -> CAT',
                'create_modify' => date("Y-m-d H:i:s"),
                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by' => $loginUserCode,
                'updated_by_ip' => getClientIP(),
            ];
            // print_r($data);
            // exit();

            $model->insertIntoFilTraphis($data);
        }
    }


    public function allot_to_TAG($diary_no, $ucode)
    {
        $sendto = '';
        $chk_status = '';
        $sessionData = $this->session->get();
        $ucode = $sessionData['login']['usercode'];
        $empid = $sessionData['login']['empid'];
        $diary_no = $sessionData['filing_details']['diary_no'];
        $model = new VerificationModel();
        $uid = $model->getUidByDiaryNo($diary_no);
        $loginUserCode = $sessionData['login']['usercode'];
        $check_if_CAT_ava = $model->check_if_CAT_ava();
        $check_if_TAG_ava = $model->check_if_TAG_ava();
        if ($check_if_TAG_ava['rowCount'] > 0) {
            $first_row = $check_if_TAG_ava['result'];
            $check_ava_rs = $model->check_ava_q();
            $check_if_TAG_ava = $check_ava_rs['result'];
            if ($check_ava_rs['result'] != NULL) {
                $check_ava_rs['result'][0]['to_userno'] = $first_row['empid'];
                $check_ava_rs['result'][0]['to_name'] = $first_row['name'];
            }
            $already_received_at_tagging = 0;
            $check_if_CAT_ava = $model->check_if_CAT_ava($diary_no);
            foreach ($check_if_CAT_ava as $toUserno) {
                $toUserno = $toUserno;
            }
            $check_if_CAT_ava = $model->check_if_CAT_ava($diary_no);
            foreach ($check_if_CAT_ava as $toUserno) {
                $toUserno = $toUserno;
            }

            // print_r($uid); exit();
            if ($uid == 0) {
                $data = [
                    'diary_no' => $diary_no,
                    'd_by_empid' => 29,
                    'other' => $ucode,
                    'd_to_empid' => $toUserno,
                    'disp_dt' => date('Y-m-d H:i:s', strtotime('+2 seconds')),
                    'remarks' => 'CAT -> TAG',
                    'r_by_empid' => 0,
                    'rece_dt' => date('Y-m-d H:i:s'),
                    'comp_dt' => date('Y-m-d H:i:s'),
                    'disp_dt_seq' => date('Y-m-d H:i:s'),
                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];
                $model->insertIntoFilTrap($data);
            } else {

                $data = [
                    'diary_no' => $diary_no,
                    'r_by_empid' => $ucode,
                    'disp_dt' => date('Y-m-d H:i:s'),
                    'rece_dt' => date('Y-m-d H:i:s'),
                    'comp_dt' => date('Y-m-d H:i:s'),
                    'd_by_empid' => 29,
                    'other' => $ucode,
                    'remarks' => 'AUTO -> TAG',
                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => $loginUserCode,
                    'updated_by_ip' => getClientIP(),
                ];

                $model->updateFilTrap($uid, $data);
            }
        }
    }

    public function call_listing()
    {
        $main_flow_content_1 = $main_flow_content_2 = "";
        $list = '';
        $obj = 'Y';
        if ($list != '') {
            if ($list == 'Y')
                $obj = 'Y';
            else if ($list == 'N' || $list == 'Z')
                $obj = 'Y';
        }
        $sessionData = $this->session->get();
        $ucode = $sessionData['login']['usercode'];
        $empid = $sessionData['login']['empid'];
        $diary_no = $sessionData['filing_details']['diary_no'];
        $model = new VerificationModel();
        $main = $model->main($diary_no);
        if ($main !== null && is_array($main) && !empty($main)) {
            $firstElement = $main[0];
            $fixed = $firstElement->fixed;
            $active_fil_no = $firstElement->active_fil_no;
            $casetype_id = $firstElement->casetype_id;
            $case_grp = $firstElement->case_grp;
            $bailno = $firstElement->bailno;
            $nature = $firstElement->nature;
        }
        if ($fixed == 0)
            $fixed = 1;

        if (
            $fixed == 1 || $fixed == '2' || $fixed == 3 || $fixed == 5 || $fixed == 6 || $fixed == 7
            || $fixed == 8 || $fixed == 9 || $fixed == 10 || $fixed == 11 || $fixed == 12
            || $fixed == 13 || $fixed == 14 || $fixed == 15 || $fixed == 'G' || $fixed == 'H' || $fixed == 'I' || $fixed == 'J'
        ) {
            $result = $model->getHeardtByDiaryNo($diary_no);
            $chk_hr = $result['count'];
            $data = $result['data'];
            if (($chk_hr == 0 || $obj == 'Y')) {
                if ($result['count'] > 0) {
                    foreach ($data as $row) {

                        if ($row->roster_id != 0 && $row->clno > 0 && $row->next_dt >= date('Y-m-d'))
                            exit();
                    }
                }
                $check_mention_memo = $model->check_mention_memo($diary_no);
                $check_mention_memo_count = $check_mention_memo['count'];
                if ($check_mention_memo_count > 0) {
                    //  exit();
                    return;
                } {
                    $sus = $sus50 = $sus63 = $con = $exe = $amd = $refiling = $withdrawl = $surrender = $del_respondent = $exemp_court_fee = $substitution = $intervention_impleadment = 0;
                    $Jn = $brdrem = '';
                    $refiling_date = date('Y-m-d');
                    $sess = 0;
                    $ia = $model->ia($diary_no);
                    $chk_hr = $ia['count'];
                    $data = $ia['data'];
                    $iades = '';
                    if ($ia['count'] > 0) {
                        foreach ($data as $row) {
                            if ($row->doccode1 == 50) {
                                $sus50 = 1;
                                $sus = 1;
                            }
                            if ($row->doccode1 == 63) {
                                $sus63 = 1;
                                $sus = 1;
                            }
                            if ($row->doccode1 == 28)
                                $con = 1;
                            if ($row->doccode1 == 17)
                                $exe = 1;
                            if ($row->doccode1 == 7)
                                $amd = 1;
                            if ($row->doccode1 == 226) {
                                $refiling = 0;
                                $refiling_date = date('Y-m-d', strtotime($row->ent_dt));
                            }
                            if ($row->doccode1 == 16)
                                $withdrawl = 1;
                            if ($row->doccode1 == 99)
                                $surrender = 1;
                            if ($row->doccode1 == 235)
                                $del_respondent = 1;
                            if ($row->doccode1 == 79)
                                $exemp_court_fee = 1;
                            // begin added on 11.3.2019
                            if ($row->doccode1 == 29)
                                $substitution = 1;
                            if ($row->doccode1 == 93)
                                $intervention_impleadment = 1;
                            // end
                            if (trim($row->docdesc) == "XTRA")
                                $iades .= " and IA No." . $row->docnum . '/' . $row->docyear . '-' . $row->other1;
                            else
                                $iades .= " and IA No." . $row->docnum . '/' . $row->docyear . '-' . $row->docdesc;
                        }
                        $brdrem .= $iades;
                    }
                    $board_type = 'J';
                    $ia_of_case = $model->ia_of_case($diary_no);
                    $chk_hr = $ia_of_case['count'];
                    $data = $ia_of_case['data'];
                    if ($ia_of_case['count'] > 0) {

                        foreach ($data as $row) {
                            $board_type = $row->listable;
                        }
                    }
                    if ($withdrawl == 1) {
                        if ($active_fil_no == '' || $active_fil_no == NULL || substr($active_fil_no, 0, 2) == '31')
                            $board_type = 'R';
                        else
                            $board_type = 'C';
                    }
                    if ($del_respondent == 1 && $refiling != 1) {
                        $board_type = 'J';
                    }

                    if ($exemp_court_fee == 1) {
                        $board_type = 'C';
                    }

                    if ($surrender == 1) {
                        $board_type = 'C';
                    }
                    $array_cur_rev = array(9, 10, 25, 26);
                    if (in_array($casetype_id, $array_cur_rev)) {
                        $board_type = 'C';
                    }
                    if ($board_type == 'J')
                        $sitting_jud = 2;
                    else if ($board_type == 'C' || $board_type == 'R')
                        $sitting_jud = 1;
                    if ($fixed == 10)
                        $brdrem = "FOR ORDERS ON THE QUESTION OF TERRITORIAL JURISDICTION OF PETITION";
                    else if ($fixed == 7)
                        $brdrem = "FOR ADMISSION and I.R." . $brdrem;
                    else if ($fixed == 9)
                        $brdrem = "FOR ORDERS ON THE QUESTION OF MAINTAINABILITY OF PETITION";
                    else if ($fixed == 11)
                        $brdrem = "FOR ADMISSION with Office Report" . $brdrem;
                    else if ($fixed == 12)
                        $brdrem = "FOR ADMISSION and I.R. with Office Report" . $brdrem;
                    else if ($fixed == 8 || $con == 1 || $exe == 1 || $amd == 1 || $fixed == 13 || $fixed == 'I')
                        $brdrem = ltrim(trim($brdrem), "and");
                    else
                        $brdrem = "FOR ADMISSION" . $brdrem;
                    $if14ASCST = 0;
                    $if53JJA = 0;
                    $if397_401 = 0;
                    $ifbail = 0;
                    $ifquash = 0;
                    $ifsuspension = 0;
                    $ifbailsus = 0;
                    $ifbail438byact = 0;
                    $ifbail439byact = 0;
                    $ifhabeas = 0;
                    $category = $model->category($diary_no);
                    $chk_hr = $category['count'];
                    $data = $category['data'];
                    if ($category['count'] > 0) {
                        foreach ($data as $row) {
                            if ($row->subcode1 == 14 && $row->subcode2 == 9)
                                $ifbail = 1;
                            if ($row->subcode1 == 14 && $row->subcode2 == 29)
                                $ifquash = 1;
                            if ($row->subcode1 == 14 && $row->subcode2 == 36)
                                $ifsuspension = 1;
                            if ($row->subcode1 == 14 && $row->subcode2 == 37)
                                $ifbailsus = 1;
                            if ($row->subcode1 == 13)
                                $ifhabeas = 1;
                        }
                    }
                    $act_main = $model->act_main($diary_no);
                    $chk_hr = $act_main['count'];
                    $data = $act_main['data'];
                    if ($act_main['count'] > 0) {
                        foreach ($data as $row) {

                            if ($row->act == 231 && trim($row->section) == 438)
                                $ifbail438byact = 1;
                            if ($row->act == 231 && trim($row->section) == 439)
                                $ifbail439byact = 1;
                            if ($row->act == 231 && (trim($row->section) == 397 || trim($row->section) == 401))
                                $if397_401 = 1;
                            if ($row->act == 935 && trim($row->section) == '14(A)')
                                $if14ASCST = 1;
                            if ($row->act == 575 && (trim($row->section) == 53 || trim($row->section) == 102))
                                $if53JJA = 1;
                        }
                    }
                    $if_cav = 0;
                    $caveat_mat = $model->caveat_mat($diary_no);
                    $chk_hr = $caveat_mat['count'];
                    $data = $caveat_mat['data'];
                    if ($caveat_mat['count'] > 0) {
                        $if_cav = 1;

                        $proof = $model->proof($diary_no);
                        $chk_hr = $proof['count'];
                        $data = $proof['data'];
                        if ($proof['count'] > 0) {
                            $if_cav = 0;
                        }
                    }
                    if ($if_cav == 1) {
                        $chk_w = $model->chk_w($diary_no);
                        $chk_hr = $chk_w['count'];
                        $data = $chk_w['data'];

                        if ($chk_w['count'] > 0) {
                            $chk_status = 0;
                        } else {
                            // echo "Proposal can not made, Caveat Found";
                        }
                    }
                    $subhead = 0;
                    // $subhead ='';
                    if (trim($case_grp) == 'C') {
                        $subhead = 812;
                    } elseif (trim($case_grp) == 'R') {
                        if ($ifbail == 1 && $ifbail438byact == 1) {
                            $subhead = 804;
                        } elseif ($ifbail == 1 && $ifbail439byact == 1) {
                            $subhead = 805;
                        } elseif ($ifsuspension == 1 && $sus == 1) {
                            if ($sus50 == 1) {
                                $subhead = 806;
                            } elseif ($sus63 == 1) {
                                $subhead = 821;
                            }
                        } else {
                            $subhead = 811;
                        }

                        if ($ifsuspension == 1 && $if14ASCST == 1) {
                            if ($bailno > 0) {
                                $subhead = 823;
                            }
                        }

                        if ($ifsuspension == 1 && $if53JJA == 1) {
                            if ($bailno > 0) {
                                $subhead = 822;
                            }
                        }
                    }
                    if ($if397_401 == 1 && $if53JJA == 1) {
                        $if397_401 = 0;
                    }
                    if ($fixed == 8 || $fixed  == 9 || $fixed  == 10 || $fixed  == 13)
                        $subhead = 808;
                    if ($list == 'Z' || $fixed  == 2 || $fixed  == 17) {
                        if ($case_grp == 'C')
                            $subhead = 801;
                        else
                            $subhead = 808;

                        if ($fixed  == 13)
                            $subhead = 808;

                        if ($_REQUEST['check_ia_not'] == 1)
                            $subhead = 808;

                        if ($fixed  == 17)
                            $subhead = 808;
                    }
                    if ($ifhabeas == 1)
                        $listorder = 32;
                    else
                        $listorder = 32;
                    $brdrem = addslashes($brdrem);
                    $listnxtday = 0;
                    $inperson_case = 0;
                    $inperson_delhi_case = 0;
                    $pipchk = $model->pipchk($diary_no);
                    $chk_hr = $pipchk['count'];
                    $data = $pipchk['data'];
                    if ($pipchk['count'] > 0) {
                        $inperson_case = 1;

                        foreach ($data as $row) {
                            if ($row->state == 490506) {
                                $inperson_delhi_case = 1;
                            }
                        }
                    }
                    $resl_short_cat_case = $model->shortCatCase($diary_no);
                    if ($resl_short_cat_case == 2) {
                        $resl_short_cat_case = $model->top4CourtCase($diary_no);
                    }
                    if ($ifhabeas == 1) {
                        $subhead = 810;
                        $agli_dinank = date('Y-m-d', strtotime(date('Y-m-d') . '+1 day'));
                        $nxt_dt = $model->nmd_misc_after_desired_dt($resl_short_cat_case, $agli_dinank);
                    } else if ($nature == 6) {
                        $agli_dinank = date('Y-m-d', strtotime(date('Y-m-d') . '+28 day'));
                        $nxt_dt = $model->nmd_misc_after_desired_dt($resl_short_cat_case, $agli_dinank);
                    } else if ($inperson_case == 1) {
                        $agli_dinank = date('Y-m-d', strtotime(date('Y-m-d') . '+28 day'));
                        if ($inperson_delhi_case == 1) {
                            $agli_dinank = date('Y-m-d', strtotime(date('Y-m-d') . '+28 day'));
                        }
                        $nxt_dt = $model->nmd_misc_after_desired_dt($resl_short_cat_case, $agli_dinank);
                    } else {
                        $nxt_dt = $model->nmd_misc_dt($resl_short_cat_case);
                    }
                    // $check_ra = $model->check_ra($diary_no ,$nxt_dt);
                    $new_coram = '';
                    $conn_key = 0;
                    $if_listed = 0;
                    $if_fixed = 0;
                    $update_main_case = 0;
                    $next_fixed = ''; // Or set to an empty string if it's empty
                    // Convert empty string to NULL if necessary
                    if ($next_fixed === '') {
                        $next_fixed = $nxt_dt;
                    }
                    $conn_key_disp = 0;
                    $mainhead_conn_main_case = '';
                    $subhead_conn_main_case = '';
                    $headings_conn_main_case = '';
                    $conn_chk_q = $model->conn_chk_q($diary_no, $nxt_dt);
                    $chk_hr = $conn_chk_q['count'];
                    $data = $conn_chk_q['data'];
                    if ($conn_chk_q['count'] > 0) {
                        foreach ($data as $row) {
                            if ($row->c_status == 'P') {
                                $conn_key = $row->conn_key;

                                if ($row->is_retired == 'N')
                                    $new_coram = $new_coram . ',' . $row->jcode;
                            } else
                                $conn_key_disp = $row->conn_key;

                            if ($row->next_dt >= date('Y-m-d') && $row->roster_id != 0 && $row->clno != 0 && $row->brd_slno != 0)
                                $if_listed = 1;

                            /*if($row_conn_ck['is_retired']=='N')
                                $new_coram = $new_coram.','.$row_conn_ck['jcode'];*/

                            $mainhead_conn_main_case = $row->mainhead;
                            $subhead_conn_main_case = $row->subhead;
                        }
                    }
                    $new_coram = ltrim($new_coram, ',');
                    /* patch for Registrar court added on 02-11-2022 */
                    if ($board_type == 'R') {
                        $new_coram = '';
                    }
                    /* end of the patch */
                    if ($new_coram == '')
                        $new_coram = '0';


                    if (!$conn_key == 0) {
                        if ($if_listed == 0) {
                            $check_if_FD = $model->check_if_FD($conn_key);
                            $chk_hr = $check_if_FD['count'];
                            $data = $check_if_FD['data'];

                            if ($check_if_FD['count'] > 0) {
                                foreach ($data as $row) {
                                    $next_fixed = $model->revertDate($row->head_content);
                                    if ($next_fixed <= $nxt_dt) {
                                        $nxt_dt = $next_fixed;
                                        $if_fixed = 1;
                                    } else if ($next_fixed > $nxt_dt) {
                                        $next_fixed = $nxt_dt;
                                        $update_main_case = 1;
                                    }
                                }
                            }
                            $check_if_FD2 = $model->check_if_FD2($conn_key);
                            $chk_hr = $check_if_FD2['count'];
                            $data = $check_if_FD2['data'];

                            if ($check_if_FD2['count'] > 0) {
                                foreach ($data as $row) {

                                    $next_fixed = $row->tentative_cl_dt;
                                    if ($next_fixed <= $nxt_dt) {
                                        $nxt_dt = $next_fixed;
                                        $if_fixed = 1;
                                    } else if ($next_fixed > $nxt_dt) {
                                        $next_fixed = $nxt_dt;
                                        $update_main_case = 1;
                                    }
                                }
                            }
                            $check_ra = $model->check_ra1($conn_key);
                            $chk_hr = $check_ra['count'];
                            $data = $check_ra['data'];

                            if ($check_ra['count'] > 0) {
                                foreach ($data as $row) {
                                    $next_fixed = $row->next_dt;
                                    if ($next_fixed <= $nxt_dt) {
                                        $nxt_dt = $next_fixed;
                                        $if_fixed = 1;
                                    } else if ($next_fixed > $nxt_dt) {
                                        $next_fixed = $nxt_dt;
                                        $update_main_case = 1;
                                    }
                                }
                            }
                            if ($mainhead_conn_main_case == '')
                                $mainhead_conn_main_case = 'M';
                            if ($subhead_conn_main_case == 0)
                                $subhead_conn_main_case = $subhead;
                            if ($mainhead_conn_main_case == 'F') {
                                $headings_conn_main_case = " mainhead='M', subhead='808', mainhead_n='F', subhead_n='$subhead_conn_main_case', ";
                            } else {
                                // $headings_conn_main_case=" mainhead='$mainhead_conn_main_case',subhead='808', "; (commented bcoz main matter is getting effected after tagging done on 07072018)
                                $headings_conn_main_case = " mainhead='$mainhead_conn_main_case',";
                            }


                            if ($next_fixed == '0000-00-00')
                                $next_fixed = $nxt_dt;


                            if ($if_fixed == 0) {
                                $chk_in_l_h = $model->chk_in_l_h($conn_key, $if_fixed, $board_type, $mainhead_conn_main_case, $next_fixed);
                            } else {
                                if ($update_main_case == 1) {
                                    $chk_in_l_h = $model->updateMainCase($conn_key, $update_main_case, $board_type, $mainhead_conn_main_case, $next_fixed);
                                }
                            }
                        }
                    }
                }
                $newCoram = $model->updateCoram($conn_key_disp, $diary_no);
                $result = $model->getHeardtByDiaryNo($diary_no);
                $chk_hr = $result['count'];
                $data = $result['data'];
                foreach ($data as $row) {

                    $chk_row = $row->coram;
                }
                $result = $model->getlastHeardtByDiaryNo($diary_no);
                $chk_hr = $result['count'];
                $data = $result['data'];
                foreach ($data as $row) {

                    $next_dt = $row->next_dt;
                }
                $result = $model->getHeardtByDiaryNo($diary_no);
                $chk_hr = $result['count'];
                $data = $result['data'];
                $next_dt = null;
                if ($diary_no) {
                    $next_dt = '0000-00-00';
                }
                $newCoram = ltrim($newCoram, ',');
                $result = $model->getHeardtByDiaryNo($diary_no);
                $chk_hr = $result['count'];
                $data = $result['data'];
                // print_r($result);
                if ($result['count'] > 0) {
                    foreach ($data as $row) {
                        $chk_row = $row->coram;
                        $next_dt = $row->next_dt;
                        $mainhead = $row->mainhead;
                        $mainhead_n = $row->mainhead_n;
                        $subhead_n = $row->subhead_n;
                        $clno = $row->clno;
                        $brd_slno = $row->brd_slno;
                        $roster_id = $row->roster_id;
                        $board_type = $row->board_type;
                        $main_supp_flag = $row->main_supp_flag;
                        $listorder = $row->listorder;
                        $sitting_judges = $row->sitting_judges;
                        $usercode = $row->usercode;
                        $coram = $row->coram;
                        $is_nmd = $row->is_nmd;
                        $no_of_time_deleted = $row->no_of_time_deleted;
                    }
                }
                $selFromHeardt = $model->getSelFromHeardt($diary_no);

                if ($selFromHeardt > 0) {
                    $chkInLH = $model->checkLastHeardt($selFromHeardt);
                    if (empty($chkInLH)) {
                        $isInserted = $model->insertLastHeardt($selFromHeardt);
                        if (!$isInserted) {
                            die("Error inserting into last_heardt table");
                        }
                    }
                }
                $subhead = 0;
                if (trim($case_grp) == 'C') {
                    $subhead = 812;
                    // exit;
                } elseif (trim($case_grp) == 'R') {
                    if ($ifbail == 1 && $ifbail438byact == 1) {
                        $subhead = 804;
                    } elseif ($ifbail == 1 && $ifbail439byact == 1) {
                        $subhead = 805;
                    } elseif ($ifsuspension == 1 && $sus == 1) {
                        if ($sus50 == 1) {
                            $subhead = 806;
                        } elseif ($sus63 == 1) {
                            $subhead = 821;
                        }
                    } else {
                        $subhead = 811;
                    }

                    if ($ifsuspension == 1 && $if14ASCST == 1) {
                        if ($bailno > 0) {
                            $subhead = 823;
                        }
                    }

                    if ($ifsuspension == 1 && $if53JJA == 1) {
                        if ($bailno > 0) {
                            $subhead = 822;
                        }
                    }
                }
                // print_r($new_coram);
                // exit;
                $result = $model->getHeardtByDiaryNo($diary_no);
                $chk_hr = $result['count'];
                $data = $result['data'];

                if ($result['count'] > 0) {

                    $data = [
                        'diary_no' => $diary_no,
                        'conn_key' => $conn_key,
                        'next_dt' => $nxt_dt,
                        'mainhead' => 'M',
                        'subhead' => $subhead,
                        'clno' => 0,
                        'brd_slno' => 0,
                        'roster_id' => 0,
                        'judges' => '0',
                        'board_type' => $board_type,
                        'usercode' => $ucode,
                        'ent_dt' => date('Y-m-d H:i:s'),
                        'module_id' => 4,
                        'mainhead_n' => 'M',
                        'subhead_n' => $subhead,
                        'main_supp_flag' => 0,
                        'listorder' => 32,
                        'tentative_cl_dt' => $nxt_dt,
                        'sitting_judges' => $sitting_jud,
                        'create_modify' => date("Y-m-d H:i:s"),
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP()
                    ];

                    $model->updateheardt($diary_no, $data);
                } else {
                    $data = [
                        'diary_no' => $diary_no,
                        'conn_key' => $conn_key,
                        'next_dt' => $nxt_dt,
                        'mainhead' => 'M',
                        'subhead' => $subhead,
                        'clno' => 0,
                        'brd_slno' => 0,
                        'roster_id' => 0,
                        'judges' => '0',
                        'coram' => $new_coram,
                        'board_type' => $board_type,
                        'usercode' => $ucode,
                        'ent_dt' => date('Y-m-d H:i:s'),
                        'module_id' => 2,
                        'mainhead_n' => 'M',
                        'subhead_n' => $subhead,
                        'main_supp_flag' => 0,
                        'listorder' => 32,
                        'tentative_cl_dt' => $nxt_dt,
                        'sitting_judges' => $sitting_jud,
                        'create_modify' => date("Y-m-d H:i:s"),
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP()
                    ];
                    $model->insertheardt($data);
                    echo 'Proposal Generated Successfully';
                }
            }
        }
    }

    public function include_verification()
    {
        $db = \Config\Database::connect();
        $w_wo_dn = '';
        $sessionData = $this->session->get();
        $diary_no = $sessionData['filing_details']['diary_no'];
        $verificationQuery = "SELECT DISTINCT
      a.diary_no,
      pet_name,
      res_name
  FROM
      main a
  LEFT JOIN defects_verification c ON
      a.diary_no = c.diary_no
  WHERE
      (fil_no != ''
          AND fil_no IS NOT NULL)
      AND c_status = 'P'
      AND (c.diary_no IS NULL
          OR (c.diary_no IS NOT NULL
              AND verification_status = 1::varchar))
      AND CAST(a.diary_no AS BIGINT) = $diary_no
  UNION
  SELECT DISTINCT
      a.diary_no,
      pet_name,
      res_name
  FROM
      main a
  JOIN docdetails b ON
      a.diary_no = b.diary_no
  JOIN master.docmaster c ON
      b.doccode = c.doccode
      AND b.doccode1 = c.doccode1
  LEFT JOIN defects_verification d ON
      a.diary_no = d.diary_no
  WHERE
      c_status = 'P'
      AND active_fil_no = ''
      AND b.display = 'Y'
      AND c.display = 'Y'
      AND (not_reg_if_pen = 1
          OR not_reg_if_pen = 2)
      AND (d.diary_no IS NULL
          OR (d.diary_no IS NOT NULL
              AND verification_status = 1::varchar))
      AND CAST(a.diary_no AS BIGINT) = $diary_no
  UNION
  SELECT DISTINCT
      a.diary_no,
      pet_name,
      res_name
  FROM
      main a
  JOIN mention_memo mm ON
      a.diary_no::varchar = mm.diary_no::varchar
  LEFT JOIN defects_verification d ON
      a.diary_no = d.diary_no
  WHERE
      c_status = 'P'
      AND active_fil_no = ''
      AND mm.display = 'Y'
      AND (d.diary_no IS NULL
          OR (d.diary_no IS NOT NULL
              AND verification_status = '1'))
      and a.diary_no = $diary_no;";

        $verification = $db->query($verificationQuery);
        $diary_message = '';
        if ($verification->getNumRows() > 0) {
            // $this->call_listing();
        } else {
            $checkIfRegisteredQuery = "SELECT DISTINCT a.diary_no, pet_name, res_name FROM main a
            JOIN docdetails b ON a.diary_no = b.diary_no
            JOIN master.docmaster c ON b.doccode = c.doccode AND b.doccode1 = c.doccode1
            LEFT JOIN defects_verification d ON a.diary_no = d.diary_no
            WHERE c_status = 'P'
            AND active_fil_no = ''
            AND b.display = 'Y'
            AND c.display = 'Y'
            AND (not_reg_if_pen = 1 OR not_reg_if_pen = 2)
            AND (d.diary_no IS NULL OR (d.diary_no IS NOT NULL AND verification_status='1')) and a.diary_no = $diary_no";

            $checkIfRegistered = $db->query($checkIfRegisteredQuery);

            if ($checkIfRegistered->getNumRows() < 1) {
                $diary_message = 'Matter is unregistered and Interlocutary Application not found ';
            } else {
                $diary_message = 'No Record Found';
            }
        }

        return $diary_message;
    }
}
