<?php

namespace App\Controllers\Filing;

use App\Models\Filing\TaggingModel;
use CodeIgniter\Controller;
use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;
use App\Models\Common\Component\Model_case_status;



class Tagging extends BaseController
{

    public $Dropdown_list_model;
    public $Model_TaggingModel;
    public $Model_case_status;
    public $diary_no;
    
    function __construct()
    {
        $this->Model_TaggingModel = new TaggingModel();
        $this->Dropdown_list_model = new Dropdown_list_model();
        $this->Model_case_status = new Model_case_status();
        
        if (empty(session()->get('filing_details')['diary_no'])) {
            $uri = current_url(true);             
            
            $getUrl = str_replace('/', '-', $uri->getPath());
            header('Location:'.base_url('Filing/Diary/search?page_url='.base64_encode($getUrl)));exit();
            exit();
        } else {
            $this->diary_no = session()->get('filing_details')['diary_no'];
        }

    }

    public function index()
    {

        $sessionData = $this->session->get();
 
        $diary_no = $sessionData['filing_details']['diary_no'];

        $data['section'] = '';
        $data = array();
        $conct_data_diary = array();
        $diary_details_connected = array();
        $all_connected_cases = $connected_cases = array();
        $list_not_before = $list_not_bf = array();
        $hearing_details = $heardt_details = array();
        $data['old_category_name'] = '';
        $advocateDetails = $party_details_d = $old_category_data = array();
        $case_status = session()->get('filing_details')['c_status'];
        $data['section'] = $sessionData['login']['section'];
        $filing_details['result'] = session()->get('filing_details');
        if ($case_status == 'D') {
            $is_archived_flag = '_a';
        } else {
            $is_archived_flag = '';
        }


        $diary_details_connected = $this->Model_TaggingModel->getDiaryDetails($diary_no, 'V', $is_archived_flag);

        if (!empty($diary_details_connected['connto'])) {
            $conct_data_diary = $this->get_connected_diary_details($diary_details_connected['connto'], $is_archived_flag);
        }

        $all_connected_cases = $this->Model_TaggingModel->getAllConnectedCases($diary_no, $is_archived_flag);
        if (is_array($diary_details_connected) && isset($diary_details_connected['ccdet']) && $diary_details_connected['ccdet'] != 'NA') {
            $connected_cases = $this->Model_TaggingModel->getConnectedCases($diary_no, $is_archived_flag);
        }



        $diary_party_details = $this->get_party_details($diary_no, $is_archived_flag);
        $get_pet_res_advocate = $this->get_pet_res_advocate($diary_no, $is_archived_flag);

        $list_not_before = $this->getListNotBefore($diary_no);
        $hearing_details = $this->getHearingDetails($diary_no);

        $sub_array['diary_details'] = $diary_details_connected;
        $sub_array['connected_matters'] = $all_connected_cases;
        $sub_array['conct_matters'] = $connected_cases;
        $sub_array['conct_matter_diary'] = $conct_data_diary;

        $party_details_d['party_details_diary'] = $diary_party_details;
        $advocateDetails['advocate_details'] = $get_pet_res_advocate;
        $list_not_bf['not_before'] = $list_not_before;
        $heardt_details['heardt'] = $hearing_details;
        $old_category_data = json_decode($this->Model_case_status->get_old_category($diary_no, $is_archived_flag), true);

        $category_nm = '';
        $mul_category = '';
        if (!empty($old_category_data)) {
            foreach ($old_category_data as $old_category) {
                if ($old_category['subcode1'] > 0 and $old_category['subcode2'] == 0 and $old_category['subcode3'] == 0 and $old_category['subcode4'] == 0)
                    $category_nm =  $old_category['sub_name1'];
                elseif ($old_category['subcode1'] > 0 and $old_category['subcode2'] > 0 and $old_category['subcode3'] == 0 and $old_category['subcode4'] == 0)
                    $category_nm =  $old_category['sub_name1'] . " : " . $old_category['sub_name4'];
                elseif ($old_category['subcode1'] > 0 and $old_category['subcode2'] > 0 and $old_category['subcode3'] > 0 and $old_category['subcode4'] == 0)
                    $category_nm =  $old_category['sub_name1'] . " : " . $old_category['sub_name2'] . " : " . $old_category['sub_name4'];
                elseif ($old_category['subcode1'] > 0 and $old_category['subcode2'] > 0 and $old_category['subcode3'] > 0 and $old_category['subcode4'] > 0)
                    $category_nm =  $old_category['sub_name1'] . " : " . $old_category['sub_name2'] . " : " . $old_category['sub_name3'] . " : " . $old_category['sub_name4'];

                if ($mul_category == '') {
                    $mul_category = $old_category['category_sc_old'] . '-' . $category_nm;
                } else {
                    $mul_category = $old_category['category_sc_old'] . '-' . $mul_category . ',<br> ' . $category_nm;
                }
            }
            $data['old_category_name'] = $mul_category;
        }


        $final_array[] = array_merge($sub_array, $party_details_d, $advocateDetails, $list_not_bf, $heardt_details);
        $data['connected_data'] = $final_array;
        /*echo'<pre>';

        echo'</pre>';*/
        //print_r($data);
        return view('Filing/tagging_view', $data);
    }

    public function get_party_details($diary_no, $is_archived_flag = null)
    {
        $builder = $this->db->table("party" . $is_archived_flag . " as a");
        $builder->select("string_agg(partyname,' , ' ORDER BY sr_no) as pn,pet_res");
        $builder->where('diary_no', $diary_no);
        $builder->whereIn('pet_res', ['P', 'R']);
        $builder->where('sr_no>', '1');
        $builder->groupBy('pet_res,sr_no');
        $query = $builder->get();
        //echo $this->db->getLastQuery();
        $result = $query->getResultArray();
        return $result;
    }

    function get_pet_res_advocate($diary_no, $is_archived_flag = null)
    {
        $builder1 = $this->db->table("advocate" . $is_archived_flag . " a");
        $builder1->select("pet_res_no,adv, advocate_id, pet_res,name,enroll_no,enroll_date, isdead,date_part('year', enroll_date) as eyear");
        $builder1->join('master.bar b', "a.advocate_id=b.bar_id");
        $builder1->whereIn('pet_res', ['P', 'R']);
        $builder1->where('diary_no', $diary_no);
        $builder1->where('display', 'Y');
        $builder1->orderBy("pet_res,pet_res_no, pet_res_show_no ");
        $query = $builder1->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    function getListNotBefore($diary_no)
    {
        $builder1 = $this->db->table("not_before a");
        $builder1->select("a.diary_no, string_agg(b.jname,',') as jn,a.notbef");
        $builder1->join('master.judge b', "a.j1=b.jcode");
        $builder1->where('diary_no', $diary_no);
        $builder1->groupBy('a.diary_no,a.notbef');

        $query = $builder1->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    // function getHearingDetails($diary_no)
    // {
    //     $builder1 = $this->db->table("heardt a");
    //     $builder1->select("to_char(a.next_dt,'DD-MM-YYYY') as next_dt,roster_id AS judgename1,judges,next_dt as nd1");
    //     $builder1->where('diary_no', $diary_no);
    //     $builder1->where('next_dt>current_date');
    //     $builder1->where('roster_id>0');
    //     $builder1->whereIn('main_supp_flag', ['1', '2']);

    //     $query = $builder1->get();
    //     if ($query->getNumRows() >= 1) {
    //         $result = $query->getResultArray();
    //         return $result;
    //     } else {
    //         return false;
    //     }
    // }

    function getHearingDetails($diary_no)
{
    $builder1 = $this->db->table("heardt a");
    $builder1->select("to_char(a.next_dt,'DD-MM-YYYY') as next_dt, roster_id AS judgename1, judges, next_dt as nd1");
    $builder1->where('diary_no', $diary_no);
    $builder1->where('next_dt > current_date');
    $builder1->where('roster_id > 0');
    $builder1->whereIn('main_supp_flag', ['1', '2']);

    $query = $builder1->get();
    if ($query->getNumRows() > 0) {
        return $query->getResultArray();
    }
    // Return an empty array instead of false
    return [];
}


    function conn_case_update_to_main()
    {
        $i = 0;
        $cond = "";
        $str = "";
        $i = 0;
        foreach ($_POST as $stuff) {
            if (is_array($stuff)) {
                foreach ($stuff as $diary_no) {
                    $dataArray = array(
                        'conn_key' => '',
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    );
                    $archived_flag = "";
                    $is_main = is_data_from_table('main', ['diary_no' => $diary_no], '*');
                    if (empty($is_main)) {
                        $archived_flag = "_a";
                    }

                    $isupdated = update('main' . $archived_flag, $dataArray, ['diary_no' => $diary_no]);
                    if ($isupdated) {
                        $conct_old = is_data_from_table('conct' . $archived_flag, ['diary_no' => $diary_no], '*');
                        if (!empty($conct_old)) {
                            foreach ($conct_old as $conct_old_row) {
                                $data_addon_party = [
                                    'updated_on' => date("Y-m-d H:i:s"),
                                    'updated_by' => session()->get('login')['usercode'],
                                    'updated_by_ip' => getClientIP(),
                                    'create_modify' => date("Y-m-d H:i:s"),
                                    'chng_by' => session()->get('login')['usercode'],
                                    'chng_date' => date("Y-m-d H:i:s"),
                                ];

                                if (empty($conct_old_row['migration'])) {
                                    $migration_array = ['migration' => '0'];
                                } else {
                                    $migration_array = [];
                                }
                                $final_array_conct_history = array_merge($data_addon_party, $conct_old_row, $migration_array);
                                $rs3 = insert('conct_history' . $archived_flag, $final_array_conct_history);
                            }
                        }
                        $conct_deleted = delete('conct' . $archived_flag, ['diary_no' => $diary_no]);
                    }
                }
            }
        }
    }

    function conn_case_delink_main_old()
    {
        $cond = "";
        $str = "";
        $dno = $this->request->getPost('dno');
        $dyr = $this->request->getPost('dyr');
        $diary_no = $dno . $dyr;
        $ttl = $this->request->getPost('ttl');
        $ttlp = $this->request->getPost('ttlp');
        $ttld = $this->request->getPost('ttld');

        if ($ttld > 0) {
            $is_archived_flag = '_a';
        } else {
            $is_archived_flag = '';
        }

        $builder1 = $this->db->table("main" . $is_archived_flag);
        $builder1->select("diary_no");
        $builder1->where('diary_no!=', $diary_no);
        $builder1->where('conn_key', $diary_no);
        if ($ttlp > 0)
            $builder1->where('c_status!=', 'D');
        $builder1->orderBy("case when fil_dt is NULL then '2099-01-01' else fil_dt end");
        $query = $builder1->get(1);

        if ($query->getNumRows() >= 1) {
            $result = $query->getRowArray();
            $new_fil_no = $result["diary_no"];
            $dataArray = array(
                'conn_key' => ''
            );
            $isupdated = update('main' . $is_archived_flag, $dataArray, ['diary_no' => $diary_no]);

            $dataArray = array(
                'conn_key' => $new_fil_no,
                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by' => session()->get('login')['usercode'],
                'updated_by_ip' => getClientIP(),
            );
            $isupdated = update('main' . $is_archived_flag, $dataArray, ['conn_key' => $diary_no]);
            if ($isupdated) {
                $conct_old = is_data_from_table('conct' . $is_archived_flag, ['conn_key' => $diary_no], '*');
                if (!empty($conct_old)) {
                    foreach ($conct_old as $conct_old_row) {
                        $data_addon_party = [
                            'updated_on' => date("Y-m-d H:i:s"),
                            'updated_by' => session()->get('login')['usercode'],
                            'updated_by_ip' => getClientIP(),
                            'create_modify' => date("Y-m-d H:i:s"),
                            'chng_by' => session()->get('login')['usercode'],
                            'chng_date' => date("Y-m-d H:i:s"),
                        ];

                        if (empty($conct_old_row['migration'])) {
                            $migration_array = ['migration' => '0'];
                        } else {
                            $migration_array = [];
                        }
                        $final_array_conct_history = array_merge($data_addon_party, $conct_old_row, $migration_array);
                        $rs3 = insert('conct_history' . $is_archived_flag, $final_array_conct_history);
                    }
                }
                $conct_deleted = delete('conct' . $is_archived_flag, ['diary_no' => $diary_no]);
            }
            $dataArray = array(
                'conn_key' => $new_fil_no,
                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by' => session()->get('login')['usercode'],
                'updated_by_ip' => getClientIP(),
            );
            $isupdated = update('conct' . $is_archived_flag, $dataArray, ['conn_key' => $diary_no]);
        }
    }

    function conn_case_delink_main()
    {
        $cond = "";
        $str = "";
        $dno = $this->request->getPost('dno');
        $dyr = $this->request->getPost('dyr');
        $diary_no = $dno . $dyr;
        $ttl = $this->request->getPost('ttl');
        $ttlp = $this->request->getPost('ttlp');
        $ttld = $this->request->getPost('ttld');

        if ($ttld > 0) {
            $is_archived_flag = '_a';
        } else {
            $is_archived_flag = '';
        }

        $builder1 = $this->db->table("main" . $is_archived_flag);
        $builder1->select("diary_no");
        $builder1->where('diary_no!=', $diary_no);
        $builder1->where('conn_key', $diary_no);
        if ($ttlp > 0)
            $builder1->where('c_status!=', 'D');
        $builder1->orderBy("case when fil_dt is NULL then '2099-01-01' else fil_dt end");
        $query = $builder1->get(1);
        // echo $this->db->getLastQuery();
        if ($query->getNumRows() >= 1) {
            $result = $query->getRowArray();
            $new_fil_no = $result["diary_no"];
            $dataArray = array(
                'conn_key' => ''
            );
            $isupdated = update('main' . $is_archived_flag, $dataArray, ['diary_no' => $diary_no]);

            $dataArray = array(
                'conn_key' => $new_fil_no,
                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by' => session()->get('login')['usercode'],
                'updated_by_ip' => getClientIP(),
            );
            $isupdated = update('main' . $is_archived_flag, $dataArray, ['conn_key' => $diary_no]);
            if ($isupdated) {
                $conct_old = is_data_from_table('conct' . $is_archived_flag, ['conn_key' => $diary_no], '*');
                if (!empty($conct_old)) {
                    foreach ($conct_old as $conct_old_row) {
                        $data_addon_party = [
                            'updated_on' => date("Y-m-d H:i:s"),
                            'updated_by' => session()->get('login')['usercode'],
                            'updated_by_ip' => getClientIP(),
                            'create_modify' => date("Y-m-d H:i:s"),
                            'chng_by' => session()->get('login')['usercode'],
                            'chng_date' => date("Y-m-d H:i:s"),
                        ];

                        if (empty($conct_old_row['migration'])) {
                            $migration_array = ['migration' => '0'];
                        } else {
                            $migration_array = [];
                        }
                        $final_array_conct_history = array_merge($data_addon_party, $conct_old_row, $migration_array);
                        $rs3 = insert('conct_history' . $is_archived_flag, $final_array_conct_history);
                    }
                }
                $conct_deleted = delete('conct' . $is_archived_flag, ['diary_no' => $diary_no]);
            }
            $dataArray = array(
                'conn_key' => $new_fil_no,
                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by' => session()->get('login')['usercode'],
                'updated_by_ip' => getClientIP(),
            );
            $isupdated = update('conct' . $is_archived_flag, $dataArray, ['conn_key' => $diary_no]);
        }
    }

    public function conn_case_status()
    {
        $dno = $this->request->getPost('dno');
        $dyr = $this->request->getPost('dyr');
        $dno_conn = $this->request->getPost('dno1');
        $dyr_conn = $this->request->getPost('dyr1');
        $ct = $this->request->getPost('ct');
        $cn = $this->request->getPost('cn');
        $cy = $this->request->getPost('cy');
        if ($ct != '') {
            $main_diary_number = get_diary_case_type($ct, $cn, $cy, 'R');
            $dno_conn = $main_diary_number['dn'];
            $dyr_conn = $main_diary_number['dy'];
        }
        $diaryno = $dno . $dyr;
        $diaryno_conn = $dno_conn . $dyr_conn;
        $data = $conct_data_diary = array();
        if ($diaryno == $diaryno_conn) {
            echo "<p align=center><font color=red>Add different case no from Main Case</font></p>";
            exit();
        }
        $data['dno'] = $dno;
        $data['dyr'] = $dyr;
        $data['diary_no'] = $diaryno;
        $data['dno_conn'] = $dno_conn;
        $data['dyr_conn'] = $dyr_conn;
        $data['diaryno_conn'] = $diaryno_conn;
        $sub_array['conct_matter_diary'] = array();
        $is_archived_flag = '';
        $data['section'] =  $data['old_category_name'] = '';

        $main_table_data =  is_data_from_table('main', ['diary_no' => $diaryno_conn],  "pet_name,res_name,TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') as diary_no_rec_date", 'R');
        if (empty($main_table_data)) {
            $is_archived_flag = '_a';
            $main_table_data =  is_data_from_table('main_a', ['diary_no' => $diaryno_conn], "pet_name,res_name,TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') as diary_no_rec_date", 'R');
        }



        $diary_details_connected = $this->Model_TaggingModel->getDiaryDetails($diaryno_conn, 'S', $is_archived_flag);
        // pr($diary_details_connected);
        if (!empty($diary_details_connected['connto'])) {
            $conct_data_diary = $this->get_connected_diary_details($diary_details_connected['connto'], $is_archived_flag);
        }


        $all_connected_cases = $this->Model_TaggingModel->getAllConnectedCases($diaryno_conn, $is_archived_flag);


        $connected_cases = $this->Model_TaggingModel->getConnectedCases($diaryno_conn, $is_archived_flag);
        

        $diary_party_details = $this->get_party_details($diaryno_conn);
        $get_pet_res_advocate = $this->get_pet_res_advocate($diaryno_conn);
        $list_not_before = $this->getListNotBefore($diaryno_conn);
        $hearing_details = $this->getHearingDetails($diaryno_conn);

        $sub_array['diary_details'] = $diary_details_connected;
        $sub_array['connected_matters'] = $all_connected_cases;
        $sub_array['conct_matters'] = $connected_cases;
        $sub_array['conct_matter_diary'] = $conct_data_diary;

        $party_details_d['party_details_diary'] = $diary_party_details;
        $advocateDetails['advocate_details'] = $get_pet_res_advocate;
        $list_not_bf['not_before'] = $list_not_before;
        $heardt_details['heardt'] = $hearing_details;
        $old_category_data = json_decode($this->Model_case_status->get_old_category($diaryno_conn, ''), true);

        $category_nm = '';
        $mul_category = '';
        if (!empty($old_category_data)) {
            foreach ($old_category_data as $old_category) {
                if ($old_category['subcode1'] > 0 and $old_category['subcode2'] == 0 and $old_category['subcode3'] == 0 and $old_category['subcode4'] == 0)
                    $category_nm =  $old_category['sub_name1'];
                elseif ($old_category['subcode1'] > 0 and $old_category['subcode2'] > 0 and $old_category['subcode3'] == 0 and $old_category['subcode4'] == 0)
                    $category_nm =  $old_category['sub_name1'] . " : " . $old_category['sub_name4'];
                elseif ($old_category['subcode1'] > 0 and $old_category['subcode2'] > 0 and $old_category['subcode3'] > 0 and $old_category['subcode4'] == 0)
                    $category_nm =  $old_category['sub_name1'] . " : " . $old_category['sub_name2'] . " : " . $old_category['sub_name4'];
                elseif ($old_category['subcode1'] > 0 and $old_category['subcode2'] > 0 and $old_category['subcode3'] > 0 and $old_category['subcode4'] > 0)
                    $category_nm =  $old_category['sub_name1'] . " : " . $old_category['sub_name2'] . " : " . $old_category['sub_name3'] . " : " . $old_category['sub_name4'];

                if ($mul_category == '') {
                    $mul_category = $old_category['category_sc_old'] . '-' . $category_nm;
                } else {
                    $mul_category = $old_category['category_sc_old'] . '-' . $mul_category . ',<br> ' . $category_nm;
                }
            }
            $data['old_category_name'] = $mul_category;
        }


        $final_array[] = array_merge($sub_array, $party_details_d, $advocateDetails, $list_not_bf, $heardt_details);
        $data['connected_data'] = $final_array;
        /* echo'<pre>';

        echo'</pre>';*/
        //print_r($data);
        return view('Filing/tagging_connected_view', $data);
    }

    function conn_case_update()
    {
        $lchk = "";
        $cond = "";
        $str = "";
        $dno = $this->request->getPost('dno');
        $dyr = $this->request->getPost('dyr');
        $cl = $this->request->getPost('cl');
        $diaryno = $dno . $dyr;

        if (strlen($diaryno) <= 4) {
            echo "error";
            exit();
        }

        $filnol = "";
        $i = 0;
        $cases = array();
        $cases[$i] = $diaryno;
        foreach ($_POST as $stuff) {
            if (is_array($stuff)) {
                foreach ($stuff as $thing) {
                    $i++;
                    $cases[$i] = $thing;
                }
            }
        }
        $conct_data =  is_data_from_table('conct', ['conn_key' => $diaryno],  "*");
        if (is_array($conct_data)) {
            foreach ($conct_data as $row_other) {
                if ($row_other["diary_no"] != $diaryno) {
                    $i++;
                    $cases[$i] = $row_other["diary_no"];
                }
            }
        }


        $t_cases = "";
        for ($j = 0; $j <= $i; $j++) {
            if ($j == 0)
                $t_cases .= "'" . $cases[$j] . "'";
            else
                $t_cases .= ",'" . $cases[$j] . "'";
        }

        if ($cl != "L") {
            $builder1 = $this->db->table("main");
            $builder1->select("diary_no, fil_dt as rgdt");
            $builder1->whereIn('diary_no', $t_cases);
            $builder1->where('c_status!=', 'D');
            $builder1->orderBy("case when fil_dt is NULL then '2099-01-01' else fil_dt end");
            $query = $builder1->get(1);
            if ($query->getNumRows() >= 1) {
                $result = $query->getRowArray();
                if ($result['diary_no'] == $diaryno) {
                    $lchk = "";
                    $filnol = $diaryno;
                } else {
                    $lchk = "OTHER";
                    $filnol = $result['diary_no'];
                }
            }
        } else {
            $lchk = "";
            $filnol = $diaryno;
        }
        $i = 0;
        if ($lchk == "OTHER") {

            $conct_old = is_data_from_table('conct', ['conn_key' => $diaryno], '*');
            if (!empty($conct_old)) {
                foreach ($conct_old as $conct_old_row) {
                    $data_addon_party = [
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                        'create_modify' => date("Y-m-d H:i:s"),
                        'chng_by' => session()->get('login')['usercode'],
                        'chng_date' => date("Y-m-d H:i:s"),
                    ];

                    if (empty($conct_old_row['migration'])) {
                        $migration_array = ['migration' => '0'];
                    } else {
                        $migration_array = [];
                    }
                    $final_array_conct_history = array_merge($data_addon_party, $conct_old_row, $migration_array);
                    $rs3 = insert('conct_history', $final_array_conct_history);
                }
            }


            $dataArray = array(
                'conn_key' => $filnol,
                'list' => "(case when diary_no=$filnol then 'Y' else list end) ",
                'conn_type' => $cl,
                'usercode' => session()->get('login')['usercode'],
                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by' => session()->get('login')['usercode'],
                'updated_by_ip' => getClientIP(),
                'ent_dt' => 'now()'
            );
            $isupdated = update('conct', $dataArray, ['conn_key' => $diaryno]);

            $dataArray = array(
                'conn_key' => $filnol
            );
            $isupdated = update('main', $dataArray, ['conn_key' => $diaryno]);

            $dataArray = array(
                'conn_key' => $filnol,
                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by' => session()->get('login')['usercode'],
                'updated_by_ip' => getClientIP(),
            );
            $isupdated = update('main', $dataArray, ['diary_no' => $diaryno]);

            $conct_old = is_data_from_table('conct', ['diary_no' => $diaryno], '*');
            if (!empty($conct_old)) {
                foreach ($conct_old as $conct_old_row) {
                    $data_addon_party = [
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                        'create_modify' => date("Y-m-d H:i:s"),
                        'chng_by' => session()->get('login')['usercode'],
                        'chng_date' => date("Y-m-d H:i:s"),
                    ];

                    if (empty($conct_old_row['migration'])) {
                        $migration_array = ['migration' => '0'];
                    } else {
                        $migration_array = [];
                    }
                    $final_array_conct_history = array_merge($data_addon_party, $conct_old_row, $migration_array);
                    $rs3 = insert('conct_history', $final_array_conct_history);
                }

                $dataArray = array(
                    'conn_key' => $filnol,
                    'list' => 'Y',
                    'conn_type' => $cl,
                    'usercode' => session()->get('login')['usercode'],
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                    'ent_dt' => 'now()'
                );
                $isupdated = update('conct', $dataArray, ['diary_no' => $diaryno]);
            } else {
                $dataArray = array(
                    'conn_key' => $filnol,
                    'diary_no' => $diaryno,
                    'list' => 'Y',
                    'conn_type' => $cl,
                    'linked_to' => 0,
                    'usercode' => session()->get('login')['usercode'],
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                    'ent_dt' => 'now()'
                );

                $rs3 = insert('conct', $dataArray);
            }
        }

        foreach ($_POST as $stuff) {
            if (is_array($stuff)) {
                foreach ($stuff as $thing) {
                    if ($thing != $filnol) {
                        $i++;
                        //echo "<br>".$i."-".$thing;
                        $rowm =  is_data_from_table('main', ['diary_no' => $thing], "c_status", 'R');
                        $is_archived_flag = '';
                        if (empty($rowm)) {
                            $is_archived_flag = '_a';
                            $rowm = is_data_from_table('main', ['diary_no' => $thing],  "c_status", 'R');
                        }

                        $cstatus = $rowm["c_status"];
                        if ($cstatus == "P")
                            $cstatus = "Y";
                        else
                            $cstatus = "N";

                        $dataArray = array(
                            'conn_key' => $filnol,
                            'updated_on' => date("Y-m-d H:i:s"),
                            'updated_by' => session()->get('login')['usercode'],
                            'updated_by_ip' => getClientIP(),
                        );


                        $isupdated = update('main' . $is_archived_flag, $dataArray, ['diary_no' => $thing]);

                        $conct_old = is_data_from_table('conct', ['diary_no' => $thing], '*');
                        if (!empty($conct_old)) {
                            foreach ($conct_old as $conct_old_row) {
                                $data_addon_party = [
                                    'updated_on' => date("Y-m-d H:i:s"),
                                    'updated_by' => session()->get('login')['usercode'],
                                    'updated_by_ip' => getClientIP(),
                                    'create_modify' => date("Y-m-d H:i:s"),
                                    'chng_by' => session()->get('login')['usercode'],
                                    'chng_date' => date("Y-m-d H:i:s"),
                                ];

                                if (empty($conct_old_row['migration'])) {
                                    $migration_array = ['migration' => '0'];
                                } else {
                                    $migration_array = [];
                                }
                                $final_array_conct_history = array_merge($data_addon_party, $conct_old_row, $migration_array);
                                $rs3 = insert('conct_history', $final_array_conct_history);
                            }

                            $dataArray = array(
                                'conn_key' => $filnol,
                                'list' => $cstatus,
                                'conn_type' => $cl,
                                'usercode' => session()->get('login')['usercode'],
                                'updated_on' => date("Y-m-d H:i:s"),
                                'updated_by' => session()->get('login')['usercode'],
                                'updated_by_ip' => getClientIP(),
                                'ent_dt' => 'now()'
                            );
                            $isupdated = update('conct', $dataArray, ['conn_key' => $thing]);
                        } else {
                            $dataArray = array(
                                'conn_key' => $filnol,
                                'diary_no' => $thing,
                                'list' => $cstatus,
                                'conn_type' => $cl,
                                'linked_to' => 0,
                                'usercode' => session()->get('login')['usercode'],
                                'updated_on' => date("Y-m-d H:i:s"),
                                'updated_by' => session()->get('login')['usercode'],
                                'updated_by_ip' => getClientIP(),
                                'ent_dt' => 'now()'
                            );

                            $rs3 = insert('conct', $dataArray);
                        }
                    }
                }
            }
        }
        $dataArray = array(
            'conn_key' => $filnol,
            'updated_on' => date("Y-m-d H:i:s"),
            'updated_by' => session()->get('login')['usercode'],
            'updated_by_ip' => getClientIP()
        );
        $isupdated = update('main', $dataArray, ['diary_no' => $filnol]);
    }

    public function get_connected_diary_details($diary_no, $isarchived = null)
    {
        $builder = $this->db->table("main" . $isarchived . " as m");
        $builder->select("case when (m.reg_no_display is null or m.reg_no_display = '') then mc.short_description else m.reg_no_display end as reg_no_display");
        $builder->join('master.casetype mc', 'mc.casecode = m.casetype_id', 'left');
        $builder->where('diary_no', $diary_no);
        $query = $builder->get();
        //echo $this->db->getLastQuery();
        $result = $query->getRowArray();
        return $result;
    }
}
