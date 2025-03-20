<?php

namespace App\Controllers\Filing\Master;

use App\Controllers\BaseController;
use App\Models\Entities\Model_post_t;
use CodeIgniter\Model;

class Lower_court_judge_post extends BaseController
{
    public $Model_post_t;
    function __construct()
    {
        $this->Model_post_t = new Model_post_t();
    }
    public function index()
    {
        $data['param'] = '';
        $this->Model_post_t = new Model_post_t();
        $db = \Config\Database::connect();
        if ($this->request->getMethod() === 'post' && $this->validate([
            'designation' => 'required',
            //'designation' => 'required|is_unique[master.post_t.post_name]',
        ])) {
            $designation = trim($this->request->getPost('designation'));
            $data['param'] = ['designation' => $designation];
            $name_lower = strtolower($designation);
            $name_upper = strtoupper($name_lower);
            $is_data = $this->Model_post_t->select("trim(post_name)")->where(['trim(LOWER(post_name))' => $name_lower])->get()->getResultArray();

            if (empty($is_data)) {
                $max_code = 1;
                $is_max_code = $this->Model_post_t->select("max(post_code)+1 as max_code")->get()->getRowArray();
                if (!empty($is_max_code) && $is_max_code['max_code'] != null) {
                    $max_code = $is_max_code['max_code'];
                }
                $data_array = [
                    'post_code' => $max_code,
                    'post_name' => $designation,
                    'cadre_code' => 0,
                    'desig_no' => 0,
                    'status' => 0,
                    'deputation' => 0,
                    'display' => 'Y',
                    'funds' => 0,
                    'sq' => 0,
                    'abbr' => $designation,
                    'oldcadre_code' => 0,
                    'ent_user' => $_SESSION['login']['usercode'],
                    'ent_time' => date('Y-m-d H:i:s'),
                    'ent_ip_address' => date('Y-m-d H:i:s'),


                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_by' => $_SESSION['login']['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];
                //echo '<pre>';print_r($data_array);exit();

                $db->transStart();
                // insert code bellow
                //$is_response = $Model_state->insert($data_array);
                $is_response = insert('master.post_t', $data_array);
                if ($is_response) {
                    session()->setFlashdata("message_success", 'Your request has been successfully saved.');
                } else {
                    session()->setFlashdata("message_error", "Your request is not saved please try again!");
                }
                $db->transComplete();
            } else {
                session()->setFlashdata("message_error", "Record Already Exist");
            }
        }
        $data['details'] = [];
        $data['details_update'] = [];
        $data['data_list'] = $this->Model_post_t->select("post_code,post_name")->where(['display' => 'Y'])->get()->getResultArray();;
        return view('Filing/Master/lower_court_judge_post_view', $data);
    }
}
