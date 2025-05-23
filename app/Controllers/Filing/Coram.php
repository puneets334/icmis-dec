<?php

namespace App\Controllers\Filing;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Filing\CoramModel;

class Coram extends BaseController
{
    public $model;
    public $diary_no;

    function __construct()
    {

        $this->model = new CoramModel();

        if (empty(session()->get('filing_details')['diary_no'])) {
            $uri = current_url(true);
            //$getUrl = $uri->getSegment(1) . '-' . $uri->getSegment(2) . '-' . $uri->getSegment(3);
            $getUrl = str_replace('/', '-', $uri->getPath());
            header('Location:' . base_url('Filing/Diary/search?page_url=' . base64_encode($getUrl)));
            exit();
        } else {
            $this->diary_no = session()->get('filing_details')['diary_no'];
        }
    }

    public function index($editcoram = '')
    {
        $diary_no = $this->diary_no;
        $case_status = $this->model->get_case_status($diary_no);
        $coram_data = $this->model->coram_detail($diary_no);
        $get_coram_entry_date = $this->model->get_coram_entry_date($diary_no, '539');
        $coram_detail = [];
        foreach ($coram_data as $coram_val) :
            if ($coram_val['notbef'] == 'C') {

                $get_coram_entry_date = $this->model->get_coram_entry_date($diary_no, $coram_val['coram']);

                $coram_val['entry_date'] = date('d-m-Y H:i:s', strtotime($get_coram_entry_date[0]['ent_dt']));
                $coram_val['update_by'] = $get_coram_entry_date[0]['name'] . '[' . $get_coram_entry_date[0]['empid'] . ']';
            } else {
                $coram_val['entry_date'] = date('d-m-Y H:i:s', strtotime($coram_val['ent_dt']));
                $coram_val['update_by'] = $coram_val['name'] . '[' . $coram_val['empid'] . ']';
            }

            $coram_detail[] = $coram_val;
        endforeach;
        $data['coram_detail'] = $coram_detail;
        $data['case_status'] = $case_status;
        $data['diary_no'] = $diary_no;
        $data['editcoram'] =  $editcoram;

        return view('Filing/coram_view', $data);
    }

    public function coram_add($editcoram = '')
    {

        $diary_no = $this->diary_no;

        $judge = get_from_table_json('judge');

        $case_status = $this->model->get_case_status($diary_no);

        $coram_data = $this->model->coram_detail($diary_no);

        //$get_coram_entry_date = $this->model->get_coram_entry_date($diary_no, '539');

        $coram_detail = [];

        foreach ($coram_data as $coram_val) :
            if ($coram_val['notbef'] == 'C') {

                $get_coram_entry_date = $this->model->get_coram_entry_date($diary_no, $coram_val['coram']);

                $coram_val['entry_date'] = date('d-m-Y H:i:s', strtotime($get_coram_entry_date[0]['ent_dt']));
                $coram_val['update_by'] = $get_coram_entry_date[0]['name'] . '[' . $get_coram_entry_date[0]['empid'] . ']';
            } else {
                $coram_val['entry_date'] = date('d-m-Y H:i:s', strtotime($coram_val['ent_dt']));
                $coram_val['update_by'] = $coram_val['name'] . '[' . $coram_val['empid'] . ']';
            }

            $coram_detail[] = $coram_val;
        endforeach;


        foreach ($judge as $key => $value) :

            if ($value['is_retired'] == 'N') {
                $judge_list[] = $value;
            }

        endforeach;

        $data['judge_list'] = $judge_list;
        $data['coram_detail'] = $coram_detail;
        $data['case_status'] = $case_status;
        $data['diary_no'] = $diary_no;
        $data['editcoram'] =  $editcoram;

        return view('Filing/coram_add', $data);
    }

    public function coram_not_before_judge()
    {

        $diary_no = $this->diary_no;

        $data['not_before_judge'] = $this->model->not_before_judge($diary_no);
        $data['diary_no'] = $diary_no;

        return view('Filing/coram_not_before_judge', $data);
    }

    public function get_reason()
    {

        $save_as_val = '';
        if (!empty($this->request->getPost('save_as_val'))) {
            $save_as_val = $this->request->getPost('save_as_val');
        }
        $get_data = $this->model->get_reason($save_as_val);

        echo '<option value="">Select</option>';
        foreach ($get_data as $reason_val) {
            echo '<option value="' . $reason_val['res_id'] . '">' . $reason_val['res_add'] . '</option>';
        }
    }

    public function add()
    {
        $diary_no = $this->diary_no;

        $ip_address = $_SERVER['REMOTE_ADDR'];

        if (!empty($this->request->getPost('save'))) {

            $ctrl = $this->request->getPost('ctrl');
            $judge_array = $this->request->getPost('j');
            $save = $this->request->getPost('save');
            $list_res = $this->request->getPost('list_res');

            $coramArr = [];
            foreach ($judge_array as $judge_val) {

                if ($save == 'N' || $save == 'B') {
                    if ($ctrl == 'I') {

                        $ins_arr = [
                            'diary_no'          =>      $diary_no,
                            'j1'                =>      $judge_val,
                            'notbef'            =>      $save,
                            'usercode'          =>      session()->get('login')['usercode'],
                            'ent_dt'            =>      'NOW()',
                            'u_ip'              =>      $ip_address,
                            'u_mac'             =>      getClientMAC(),
                            'enterby'           =>      '4',
                            'res_id'            =>      $list_res,
                            'res_add'           =>      ''
                        ];
                        
                      $checkData =  is_data_from_table('public.not_before',"diary_no =  '$diary_no' AND j1 = $judge_val","*","N");
                      if($checkData == 0) {
                        $ins = $this->model->insert_not_before($ins_arr);
                        echo 'Record Inserted Successfully';
                      }else{
                        echo 'Record Already Exists';
                      }  
                    }
                } elseif ($save == 'C') {
                    $coramArr[] = $judge_val;
                }
            }


            if ($save == 'C') {

                $coram = implode(',', $coramArr);

                if ($coram >= 500) {

                    $cur_dt = date('Y-m-d');

                    $get_coram = $this->model->get_coram($diary_no);

                    if ($get_coram > 0) {

                        $upd_arr = [
                            'to_dt'          =>      $cur_dt
                        ];

                        $this->model->update_coram($upd_arr, $diary_no);

                        $ins_coram_arr = [
                            'diary_no'          =>      $diary_no,
                            'board_type'        =>      'R',
                            'jud'               =>      $coramArr[0],
                            'res_id'            =>      $list_res,
                            'from_dt'           =>      $cur_dt,
                            'to_dt'             =>      NULL,
                            'usercode'          =>      session()->get('login')['usercode'],
                            'ent_dt'            =>      'NOW()',
                            'display'           =>      'Y'
                        ];

                        $this->model->insert_coram($ins_coram_arr);
                    } else {

                        $ins_coram_arr = [
                            'diary_no'          =>      $diary_no,
                            'board_type'        =>      'R',
                            'jud'               =>      $coramArr[0],
                            'res_id'            =>      $list_res,
                            'from_dt'           =>      $cur_dt,
                            'to_dt'             =>      NULL,
                            'usercode'          =>      session()->get('login')['usercode'],
                            'ent_dt'            =>      'NOW()',
                            'display'           =>      'Y'
                        ];

                        $this->model->insert_coram($ins_coram_arr);
                    }
                } else {

                    $get_heardt_main = $this->model->get_heardt_main($diary_no);

                    if ($get_heardt_main) {

                        if ($get_heardt_main[0]['coram'] > 0) {
                            echo  'Please use coram delete module to delete previous coram.';
                            die();
                        }

                        $get_last_heardt = $this->model->get_last_heardt($diary_no, $get_heardt_main);

                        if ($get_last_heardt == 0) {

                            $ins_last_heardt_arr = [

                                "diary_no"              =>          $diary_no,
                                "conn_key"              =>          $get_heardt_main[0]['conn_key'],
                                "next_dt"               =>          $get_heardt_main[0]['next_dt'],
                                "mainhead"              =>          $get_heardt_main[0]['mainhead'],
                                "subhead"               =>          $get_heardt_main[0]['subhead'],
                                "clno"                  =>          $get_heardt_main[0]['clno'],
                                "brd_slno"              =>          $get_heardt_main[0]['brd_slno'],
                                "roster_id"             =>          $get_heardt_main[0]['roster_id'],
                                "judges"                =>          $get_heardt_main[0]['judges'],
                                "coram"                 =>          $get_heardt_main[0]['coram'],
                                "board_type"            =>          $get_heardt_main[0]['board_type'],
                                "usercode"              =>          $get_heardt_main[0]['usercode'],
                                "ent_dt"                =>          $get_heardt_main[0]['ent_dt'],
                                "module_id"             =>          $get_heardt_main[0]['module_id'],
                                "mainhead_n"            =>          $get_heardt_main[0]['mainhead_n'],
                                "subhead_n"             =>          $get_heardt_main[0]['subhead_n'],
                                "main_supp_flag"        =>          $get_heardt_main[0]['main_supp_flag'],
                                "listorder"             =>          $get_heardt_main[0]['listorder'],
                                "tentative_cl_dt"       =>          $get_heardt_main[0]['tentative_cl_dt'],
                                "listed_ia"             =>          $get_heardt_main[0]['listed_ia'],
                                "sitting_judges"        =>          $get_heardt_main[0]['sitting_judges'],
                                "list_before_remark"    =>          $get_heardt_main[0]['list_before_remark'],
                                "is_nmd"                =>          $get_heardt_main[0]['is_nmd'],
                                "no_of_time_deleted"    =>          $get_heardt_main[0]['no_of_time_deleted'],
                                "bench_flag"            =>          '',
                                "lastorder"             =>          '',
                                "coram_del_res"         =>          ''
                            ];

                            $insert_last_heardt = $this->model->insert_last_heardt($ins_last_heardt_arr);
                        }

                        $upd_heardt_arr = [
                            'coram'                 =>      $coramArr[0],
                            'usercode'              =>      session()->get('login')['usercode'],
                            'ent_dt'                =>      'NOW()',
                            'list_before_remark'    =>      $list_res,
                            'module_id'             =>      '4'
                        ];

                        $this->model->update_heardt($upd_heardt_arr, $diary_no);
                    } else {
                        $ins_heardt_arr = [
                            'diary_no'              =>      $diary_no,
                            'next_dt'               =>      NULL,
                            'ent_dt'                =>      'NOW()',
                            'mainhead'              =>      'M',
                            'subhead'               =>      '0',
                            'judges'                =>      '0',
                            'coram'                 =>      $coramArr[0],
                            'board_type'            =>      "J",
                            'usercode'              =>      session()->get('login')['usercode'],
                            'ent_dt'                =>      'NOW()',
                            'module_id'             =>      '4',
                            'mainhead_n'            =>      'M',
                            'main_supp_flag'        =>      '0',
                            'list_before_remark'    =>      $list_res,
                            'subhead_n'             =>      '0',
                            "listorder"             =>      '0'

                        ];

                        $this->model->insert_heardt($ins_heardt_arr);
                    }

                    if ($get_heardt_main[0]['main_key'] == $diary_no) {

                        $get_heardt_main = $this->model->get_conct_heardt($diary_no, $get_heardt_main[0]['main_key']);

                        if ($get_heardt_main) {

                            $get_last_heardt = $this->model->get_last_heardt($get_heardt_main[0]['diary_no'], $get_heardt_main);

                            if ($get_last_heardt == 0) {

                                $ins_last_heardt_arr = [

                                    "diary_no"              =>          $get_heardt_main[0]['diary_no'],
                                    "conn_key"              =>          $get_heardt_main[0]['conn_key'],
                                    "next_dt"               =>          $get_heardt_main[0]['next_dt'],
                                    "mainhead"              =>          $get_heardt_main[0]['mainhead'],
                                    "subhead"               =>          $get_heardt_main[0]['subhead'],
                                    "clno"                  =>          $get_heardt_main[0]['clno'],
                                    "brd_slno"              =>          $get_heardt_main[0]['brd_slno'],
                                    "roster_id"             =>          $get_heardt_main[0]['roster_id'],
                                    "judges"                =>          $get_heardt_main[0]['judges'],
                                    "coram"                 =>          $get_heardt_main[0]['coram'],
                                    "board_type"            =>          $get_heardt_main[0]['board_type'],
                                    "usercode"              =>          $get_heardt_main[0]['usercode'],
                                    "ent_dt"                =>          $get_heardt_main[0]['ent_dt'],
                                    "module_id"             =>          $get_heardt_main[0]['module_id'],
                                    "mainhead_n"            =>          $get_heardt_main[0]['mainhead_n'],
                                    "subhead_n"             =>          $get_heardt_main[0]['subhead_n'],
                                    "main_supp_flag"        =>          $get_heardt_main[0]['main_supp_flag'],
                                    "listorder"             =>          $get_heardt_main[0]['listorder'],
                                    "tentative_cl_dt"       =>          $get_heardt_main[0]['tentative_cl_dt'],
                                    "listed_ia"             =>          $get_heardt_main[0]['listed_ia'],
                                    "sitting_judges"        =>          $get_heardt_main[0]['sitting_judges'],
                                    "list_before_remark"    =>          $get_heardt_main[0]['list_before_remark'],
                                    "is_nmd"                =>          $get_heardt_main[0]['is_nmd'],
                                    "no_of_time_deleted"    =>          $get_heardt_main[0]['no_of_time_deleted'],
                                    "bench_flag"            =>          '',
                                    "lastorder"             =>          '',
                                    "coram_del_res"         =>          ''
                                ];

                                $insert_last_heardt = $this->model->insert_last_heardt($ins_last_heardt_arr);
                            }

                            $upd_heardt_arr = [
                                'coram'                 =>      $coramArr[0],
                                'usercode'              =>      session()->get('login')['usercode'],
                                'ent_dt'                =>      'NOW()',
                                'list_before_remark'    =>      $list_res,
                                'module_id'             =>      '4'
                            ];

                            $this->model->update_heardt($upd_heardt_arr, $get_heardt_main[0]['diary_no']);
                        } else {
                            $ins_heardt_arr = [
                                'diary_no'              =>      $get_heardt_main[0]['diary_no'],
                                'next_dt'               =>      NULL,
                                'ent_dt'                =>      'NOW()',
                                'mainhead'              =>      'M',
                                'subhead'               =>      '0',
                                'judges'                =>      '0',
                                'coram'                 =>      $coramArr[0],
                                'board_type'            =>      "J",
                                'usercode'              =>      session()->get('login')['usercode'],
                                'ent_dt'                =>      'NOW()',
                                'module_id'             =>      '4',
                                'mainhead_n'            =>      'M',
                                'main_supp_flag'        =>      '0',
                                'list_before_remark'    =>      $list_res,
                                'subhead_n'             =>      '0',
                                "listorder"             =>      '0'

                            ];

                            $this->model->insert_heardt($ins_heardt_arr);
                        }
                    }
                }
            }
        }
    }

    public function delete()
    {
        $diary_no = $this->diary_no;

        $ip_address = $_SERVER['REMOTE_ADDR'];

        $del_key_jcode = $this->request->getPost('del_key_jcode');
        $del_key_diary_no = $this->request->getPost('del_key_diary_no');
        $del_key_notbef = $this->request->getPost('del_key_notbef');
        $del_reason = $this->request->getPost('del_reason');

        if ($del_key_notbef != 'C') {

            $get_not_before = $this->model->get_not_before($del_key_jcode, $del_key_diary_no);

            if ($get_not_before) {

                $diary_no = $get_not_before[0]['diary_no'];
                $j1 = $get_not_before[0]['j1'];
                $notbef = $get_not_before[0]['notbef'];
                $usercode = $get_not_before[0]['usercode'];
                $ent_dt = $get_not_before[0]['ent_dt'];
                $old_u_ip = $get_not_before[0]['u_ip'];
                $old_u_mac = $get_not_before[0]['u_mac'];
                $enterby_old = $get_not_before[0]['enterby'];
                $old_res_add = $get_not_before[0]['res_add'];
                $old_res_id = $get_not_before[0]['res_id'];

                $ins_arr = [
                    'diary_no'          =>      $diary_no,
                    'j1'                =>      $j1,
                    'notbef'            =>      $notbef,
                    'usercode'          =>      $usercode,
                    'ent_dt'            =>      $ent_dt,
                    'old_u_ip'          =>      $old_u_ip,
                    'old_u_mac'         =>      $old_u_mac,
                    'cur_u_ip'          =>      $ip_address,
                    'cur_u_mac'         =>      getClientMAC(),
                    'cur_ucode'         =>      session()->get('login')['usercode'],
                    'c_dt'              =>      'NOW()',
                    'enterby_old'       =>      $enterby_old,
                    'action'            =>      'delete',
                    'old_res_id'        =>      $old_res_id,
                    'del_reason'        =>      $del_reason
                ];

                $ins = $this->model->insert_not_before_his($ins_arr);

                if ($ins) {
                    $del = $this->model->delete_not_before($diary_no, $j1);
                    echo json_encode(['deleted' => 'History Saved And Record Deleted']);
                }
            }
        } else {

            if ($del_key_jcode >= 500) {
                $cur_dt = date('Y-m-d');

                $get_coram_by_jud = $this->model->get_coram_by_jud($del_key_diary_no, $del_key_jcode);

                if ($get_coram_by_jud > 0) {

                    $upd_arr = [
                        'to_dt'          =>      $cur_dt,
                        'del_reason'     =>      $del_reason,
                    ];

                    $this->model->update_coram_by_jud($upd_arr, $diary_no);
                    echo json_encode(['jud_deleted' => 'Record Deleted']);
                }
            } else {

                $get_heardt = $this->model->get_heardt($del_key_diary_no);

                $ins_last_heardt_arr = [

                    "diary_no"              =>          $get_heardt[0]['diary_no'],
                    "conn_key"              =>          $get_heardt[0]['conn_key'],
                    "next_dt"               =>          $get_heardt[0]['next_dt'],
                    "mainhead"              =>          $get_heardt[0]['mainhead'],
                    "subhead"               =>          $get_heardt[0]['subhead'],
                    "clno"                  =>          $get_heardt[0]['clno'],
                    "brd_slno"              =>          $get_heardt[0]['brd_slno'],
                    "roster_id"             =>          $get_heardt[0]['roster_id'],
                    "judges"                =>          $get_heardt[0]['judges'],
                    "coram"                 =>          $get_heardt[0]['coram'],
                    "board_type"            =>          $get_heardt[0]['board_type'],
                    "usercode"              =>          $get_heardt[0]['usercode'],
                    "ent_dt"                =>          $get_heardt[0]['ent_dt'],
                    "module_id"             =>          $get_heardt[0]['module_id'],
                    "mainhead_n"            =>          $get_heardt[0]['mainhead_n'],
                    "subhead_n"             =>          $get_heardt[0]['subhead_n'],
                    "main_supp_flag"        =>          $get_heardt[0]['main_supp_flag'],
                    "listorder"             =>          $get_heardt[0]['listorder'],
                    "tentative_cl_dt"       =>          $get_heardt[0]['tentative_cl_dt'],
                    "listed_ia"             =>          $get_heardt[0]['listed_ia'],
                    "sitting_judges"        =>          $get_heardt[0]['sitting_judges'],
                    "list_before_remark"    =>          $get_heardt[0]['list_before_remark'],
                    "is_nmd"                =>          $get_heardt[0]['is_nmd'],
                    "no_of_time_deleted"    =>          $get_heardt[0]['no_of_time_deleted'],
                    "bench_flag"            =>          '',
                    "lastorder"             =>          '',
                    "coram_del_res"         =>          ''
                ];

                $insert_last_heardt = $this->model->insert_last_heardt($ins_last_heardt_arr);

                $upd_heardt_arr = [
                    'coram'                 =>      '0',
                    'usercode'              =>      session()->get('login')['usercode'],
                    'ent_dt'                =>      'NOW()',
                    'list_before_remark'    =>      '0',
                    'module_id'             =>      '4'
                ];

                $this->model->update_heardt($upd_heardt_arr, $del_key_diary_no);
                echo json_encode(['jud_deleted' => 'Record Deleted']);
            }
        }
    }


    public function not_before_judge_sub()
    {

        $ip_address = $_SERVER['REMOTE_ADDR'];

        $diary_no = $this->request->getPost('diary_no');
        $hd_jud_id = $this->request->getPost('hd_jud_id');

        $count_id_not_before = $this->model->count_id_not_before($diary_no, $hd_jud_id);

        if ($count_id_not_before[0]['count'] <= 0) {

            $ins_arr = [
                'diary_no'          =>      $diary_no,
                'j1'                =>      $hd_jud_id,
                'notbef'            =>      'N',
                'usercode'          =>      session()->get('login')['usercode'],
                'ent_dt'            =>      'NOW()',
                'u_ip'              =>      $ip_address,
                'u_mac'             =>      getClientMAC(),
                'enterby'           =>      '1',
                'res_id'            =>      '10',
                'res_add'           =>      ''
            ];

            $ins = $this->model->insert_not_before($ins_arr);
            echo json_encode(['nbj_inserted' => 'Data Save Successfully']);
        } else {

            $get_not_before = $this->model->get_not_before($hd_jud_id, $diary_no);

            if ($get_not_before[0]['notbef'] == 'N') {
                echo json_encode(['nbj_already_inserted' => 'Not Before entry already done']);
            } elseif ($get_not_before[0]['notbef'] == 'B') {
                echo json_encode(['nbj_already_inserted' => 'Before entry is alrady present']);
            }
        }
    }
	
	
	public function sensitive_case()
	{
		$ucode =  $_SESSION['login']['usercode'];		
		$filing_details = session()->get('filing_details');		 
		$data['CoramModel'] =  $this->model;
		$data['diary_no'] = $filing_details['diary_no'];
		return  view('Filing/coram_sensitive_case',$data);
	}
	
	public function get_sensitive_cases()
	{
		$ucode =  $_SESSION['login']['usercode'];		
		$filing_details = session()->get('filing_details');		 
		$data['CoramModel'] =  $this->model;
		$data['diary_no'] = $filing_details['diary_no'];
		$data['results'] = $this->model->getSensitiveCases();
		return  view('Filing/coram_get_sensitive_cases',$data);
	}
	
}
