<?php

namespace App\Controllers\MasterManagement;

use App\Controllers\BaseController;
use App\Models\MasterManagement\UserManagementModel;
use App\Models\Record_room\Model_record;
use CodeIgniter\Controller;
use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class UserManagement extends BaseController
{
    public $UserManagementModel;
    public $Model_record;
    function __construct()
    {
        ini_set('memory_limit', '51200M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
        $this->UserManagementModel = new UserManagementModel();
        $this->Model_record = new Model_record();
    }

    public function index()
    {
        $usercode = session()->get('login')['usercode'];

        $hd_ud = $this->request->getVar('hd_ud');

        $data['usertype_row'] = $this->UserManagementModel->getUserByCode($usercode);
        $usertype_row = $data['usertype_row'];
        $data['dept'] = $this->UserManagementModel->getDepartments($usertype_row['usertype'], $usercode, $hd_ud);
        $data['sel_all_jud'] = $this->UserManagementModel->getRetiredJudges();
        // pr($data['sel_all_jud']);
        return view('MasterManagement/UserManagement/user_mgmt_view', $data);
    }

    public function user_mgmt_multiple()
    {
        $data['model'] = $this->Model_record;
        $data['keyValue'] = $this->request->getGet('key');
        $data['setter'] = $this->request->getGet('setter');
        $data['cur_user_type'] = $this->request->getGet('cur_user_type');
        $data['deptname'] = $this->request->getGet('deptname');
        $data['section'] = $this->request->getGet('section');
        $data['deptValue'] = $this->request->getGet('dept');
        $data['ltypeValue'] = $this->request->getGet('ltype');
        $data['f_auth'] = $this->request->getGet('f_auth');
        $data['fil_t'] = $this->request->getGet('fil_t');
        $data['totalValue'] = $this->request->getGet('total');
        $data['chk_code'] = $this->request->getGet('chk_code');
        $data['userValue'] = $this->request->getGet('user');
        $data['serviceValue'] = $this->request->getGet('service');
        $data['empid'] = $this->request->getGet('empid');
        $data['userid'] = $this->request->getGet('userid');
        $data['statusValue'] = $this->request->getGet('status');
        $data['utypeValue'] = $this->request->getGet('utype');
        $data['utypeValue'] = $this->request->getGet('utype');
        $data['empname'] = $this->request->getGet('empname');
        $data['judge'] = $this->request->getGet('judge');
        $data['authValue'] = $this->request->getGet('auth');
        $data['aname'] = $this->request->getGet('aname');
        $data['da_code'] = $this->request->getGet('da_code');
        $data['rkds_code'] = $this->request->getGet('rkds_code');
        $data['rkdcmpda_code'] = $this->request->getGet('rkdcmpda_code');
        $data['hall_no'] = $this->request->getGet('hall_no');

        $data['usercode'] = session()->get('login')['usercode'];

        return view('MasterManagement/UserManagement/user_mgmt_multiple', $data);
    }

    public function view_user_information()
    {
        $data['model'] = $this->Model_record;
        $data['dept'] = $this->request->getGet('dept');
        $data['secValue'] = $this->request->getGet('sec');
        $data['desg'] = $this->request->getGet('desg');
        $data['cur_user_type'] = $this->request->getGet('cur_user_type');
        $data['allotmentCategory'] = $this->request->getGet('allotmentCategory');

        $data['jud_sel'] = $this->request->getGet('jud_sel');
        $data['orderjud'] = $this->request->getGet('orderjud');
        $data['view_sta'] = $this->request->getGet('view_sta');
        $data['auth_name'] = $this->request->getGet('auth_name');
        $data['authValue'] = $this->request->getGet('auth');
        $data['auth_sel_name'] = $this->request->getGet('auth_sel_name');

        $data['usercode'] = session()->get('login')['usercode'];

        return view('MasterManagement/UserManagement/view_user_information', $data);
    }

    public function pass_reset()
    {
        $usercode = session()->get('login')['usercode'];

        $data['name'] = explode('~', getUser_dpdg_full_2($usercode));

        return view('MasterManagement/UserManagement/pass_reset', $data);
    }

    public function userpass_manage()
    {
        $usercode = session()->get('login')['usercode'];
        $mat = $this->request->getGet('mat');
        $ucode = $this->request->getGet('ucode');
        if ($mat == '1') {
            $data['data'] = $this->UserManagementModel->getUserData_obj($ucode);
            if ($data['data'] !== null) {
                $data['newdata'] = explode('~', $data['data']);
            } else {
                $data['newdata'] = [];
            }
            return view('MasterManagement/UserManagement/userpass_manage', $data);
        } else if ($mat == '2') {
            echo $this->UserManagementModel->resetPasswordUser_UM($ucode, $usercode);
            die;
        }
    }

    public function pnd()
    {
        $usercode = session()->get('login')['usercode'];

        $data['name'] = explode('~', getUser_dpdg_full_2($usercode));
        $data['result'] = $this->UserManagementModel->getPeonNdriver();

        return view('MasterManagement/UserManagement/pnd', $data);
    }

    public function user_dept()
    {
        $usercode = session()->get('login')['usercode'];

        $data['name'] = explode('~', getUser_dpdg_full_2($usercode));
        $data['result_utype'] = $this->UserManagementModel->getAllUserSection_full();
        $data['get_Open_id'] = $this->UserManagementModel->get_Open_id();
        $data['result'] = $this->UserManagementModel->getAllUserDept_full();

        return view('MasterManagement/UserManagement/user_dept', $data);
    }


    public function userdept_manage()
    {
        $usercode = session()->get('login')['usercode'];

        $result_message = '';
        $name = $this->request->getGet('name');
        $flag = $this->request->getGet('flag');
        $butype = $this->request->getGet('butype');
        $id = $this->request->getGet('id');
        $mat = $this->request->getGet('mat');
        $func = $this->request->getGet('func');

        $data['result'] = $this->UserManagementModel->getAllUserDept_full();
        $data['get_Open_id'] = $this->UserManagementModel->get_Open_id();
        if ($mat == 1) {
            switch ($func) {
                case 1:
                    $result = $this->UserManagementModel->add_userdept($name, $flag, $butype, $usercode);
                    $result_message = "USER DEPARTMENT ADDED SUCCESSFULLY";
                    break;

                case 2:
                    $result = $this->UserManagementModel->remove_userdept($id, $usercode);
                    $result_message = "USER DEPARTMENT REMOVED SUCCESSFULLY";
                    break;

                case 3:
                    $result = $this->UserManagementModel->edit_userdept($name, $flag, $id, $butype, $usercode);
                    $result_message = "USER DEPARTMENT UPDATED SUCCESSFULLY";
                    break;

                default:
                    break;
            }

            // Return response based on the result
            if ($result == '1') {
                echo "1~" . $result_message;
            } else {
                echo $result;
            }
        } elseif ($mat == 2) {
            return view('MasterManagement/UserManagement/userdept_manage', $data);
        } elseif ($mat == 3) {
            return $this->UserManagementModel->getUserData_obj($id);
        }
    }

    public function user_leave()
    {
        $usercode = session()->get('login')['usercode'];

        $data['name'] = explode('~', getUser_dpdg_full_2($usercode));
        $name = $data['name'];
        $data['permit'] = 0;
        if ($name[0] == 1) {
            $data['permit'] = 1;
        }
        $data['dept'] = $this->UserManagementModel->getDept($name);
        $data['result'] = $this->UserManagementModel->getAllUserLeave_full();

        return view('MasterManagement/UserManagement/user_leave', $data);
    }


    public function user_range()
    {
        $data['usercode'] = session()->get('login')['usercode'];
        $data['select_type'] = $this->UserManagementModel->select_type_row();
        $data['getAllUserRange_full'] = $this->UserManagementModel->getAllUserRange_full();
        return view('MasterManagement/UserManagement/user_range', $data);
    }
    public function userrange_manage()
    {
        $data['mat'] = $this->request->getPost('mat');
        $data['func'] = $this->request->getPost('func');
        $data['utype'] = $this->request->getPost('utype');
        $data['low'] = $this->request->getPost('low');
        $data['up'] = $this->request->getPost('up');
        $usercode = session()->get('login')['usercode'];
        if ($data['mat'] == 1) {
            switch ($data['func']) {
                case 1:
                    $data['result'] = $this->UserManagementModel->add_range($data['utype'], $data['low'], $data['up'], $usercode);
                    $data['result_message'] = "USERRANGE ADDED SUCCESSFULLY";
                    break;
                case 2:

                    $data['id'] = $this->request->getPost('id');
                    // pr($data['id']);
                    $data['result'] = $this->UserManagementModel->remove_userrange($data['id'], $usercode);
                    $data['result_message'] = "USERRANGE REMOVED SUCCESSFULLY";
                    break;
                case 3:
                    $data['id'] = $this->request->getPost('id');
                    $result = $this->UserManagementModel->edit_userrange($data['utype'], $data['low'], $data['up'], $data['id'], $usercode);
                    $result_message = "USERRANGE UPDATED SUCCESSFULLY";
                    break;
                default:
                    break;
            }
        }
        if ($data['mat'] == 2) {
            pr('2');
        }
        if ($data['mat'] == 3) {
            $data['id'] = $this->request->getPost('id');
            $data['getUserData_obj'] =  $this->UserManagementModel->getUser_range_Data($data['id']);
            return $data['getUserData_obj'];
        }
        $data['select_type'] = $this->UserManagementModel->getAllUserRange_full();
        return view('MasterManagement/UserManagement/userrange_manage', $data);
    }

    public function section()
    {
        $usercode = session()->get('login')['usercode'];
        $data['get_Open_id'] = $this->UserManagementModel->user_section_Open_id();
        $data['results'] = $this->UserManagementModel->getAllUserSection_full();
        return view('MasterManagement/UserManagement/section', $data);
    }

    public function user_type()
    {
        //$usercode = session()->get('login')['usercode'];
        $data['get_Open_id'] = $this->UserManagementModel->user_type_Open_id();
        //pr($data['get_Open_id']);
        $data['results'] = $this->UserManagementModel->getAllUserType_full();
        return view('MasterManagement/UserManagement/user_type', $data);
    }


    public function usersec_manage()
    {
        $mat = $this->request->getPost('mat');
        $func = $this->request->getPost('func');
        $name = $this->request->getPost('name');
        $des = $this->request->getPost('des');
        $isda = $this->request->getPost('isda');
        $id = $this->request->getPost('id');
        $usercode = session()->get('login')['usercode'];
        $result = $result_message = '';
        //pr($mat);
        if ($mat == 1) {
            switch ($func) {
                case 1:
                    $result = $this->UserManagementModel->add_usersection($name, $des, $usercode, $isda);
                    $result_message = "USER SECTION ADDED SUCCESSFULLY";
                    break;
                case 2:
                    $result = $this->UserManagementModel->remove_usersection($id, $usercode);
                    $result_message = "USER SECTION REMOVED SUCCESSFULLY";
                    break;
                case 3:
                    $result = $this->UserManagementModel->edit_usersection($name, $des, $id, $usercode, $isda);
                    $result_message = "USER SECTION UPDATED SUCCESSFULLY";
                    break;
                default:
                    break;
            }

            if ($result == '1')
                echo "1~" . $result_message;
            else
                echo $result;
        } elseif ($mat == 2) {
            $data['success'] = 1;
            $data['results'] = $this->UserManagementModel->getAllUserSection_full();
            $data['get_Open_id'] = $this->UserManagementModel->user_section_Open_id();
            $data['html'] = view('MasterManagement/UserManagement/usersec_manage', $data, ['saveData' => true]);
            return $this->response->setJSON($data);
            //echo view('MasterManagement/UserManagement/usersec_manage', $data);
        } elseif ($mat == 3) {
            return $this->UserManagementModel->getUserSectionById($id);
        }
    }


    public function usertype_manage()
    {
        $mat = $this->request->getPost('mat');
        $func = $this->request->getPost('func');
        $name = $this->request->getPost('name');
        $flag = $this->request->getPost('flag');
        $mflag = $this->request->getPost('mflag');
        $usercode = session()->get('login')['usercode'];
        $id = $this->request->getPost('id');
        $result = $result_message = '';

        if ($mat == 1) {
            switch ($func) {
                case 1:
                    $result = $this->UserManagementModel->add_usertype($name, $flag, $mflag, $usercode);
                    $result_message = "USERTYPE ADDED SUCCESSFULLY";
                    break;
                case 2:
                    $result = $this->UserManagementModel->remove_usertype($id, $usercode);
                    $result_message = "USERTYPE REMOVED SUCCESSFULLY";
                    break;
                case 3:
                    $result = $this->UserManagementModel->edit_usertype($name, $flag, $mflag, $id, $usercode);
                    $result_message = "USERTYPE UPDATED SUCCESSFULLY";
                    break;
                default:
                    break;
            }

            if ($result == '1')
                echo "1~" . $result_message;
            else
                echo $result;
        } else if ($mat == 2) {
            $data['success'] = 1;
            $data['results'] = $this->UserManagementModel->getAllUserType_full();
            $data['get_Open_id'] = $this->UserManagementModel->user_type_Open_id();
            $data['html'] = view('MasterManagement/UserManagement/usertype_manage', $data, ['saveData' => true]);
            return $this->response->setJSON($data);
        } elseif ($mat == 3) {
            return $this->UserManagementModel->getUserTypeById($id);
        }
    }

    public function new_user()
    {
        $data['new_user'] = $this->UserManagementModel->new_user();
        $data['result_dept'] = $this->UserManagementModel->result_dept();
        return view('MasterManagement/UserManagement/new_user', $data);
    }

    public function newuser_manage()
    {
        $userObj = $this->UserManagementModel;
        $result_message = '';
        $ucode=$_SESSION['login']['usercode'];
        
        if ($_REQUEST['mat'] == 1) {

            switch ($_REQUEST['func']) {
                case 1:
                    $result = $userObj->add_user($_REQUEST['udept'], $_REQUEST['usec'], $_REQUEST['utype'], $_REQUEST['empid'], $_REQUEST['temp_empid'], $_REQUEST['name'], $_REQUEST['service'], $ucode);
                   
                    $result_message = "USER ADDED SUCCESSFULLY";
                    break;
                case 2:
                    $result = $userObj->remove_user($_REQUEST['id'], $ucode);
                    $result_message = "USER REMOVED SUCCESSFULLY";
                    break;
                case 3:
                    $result = $userObj->edit_user($_REQUEST['udept'], $_REQUEST['usec'], $_REQUEST['utype'], $_REQUEST['empid'], $_REQUEST['temp_empid'], $_REQUEST['name'], $_REQUEST['service'], $_REQUEST['usercode'], $ucode);
                    $result_message = "USER UPDATED SUCCESSFULLY";
                    break;
                default:
                    break;
            }
          
            if ($result == '1')
                echo "1~" . $result_message;
            else
                echo $result;
        } else if ($_REQUEST['mat'] == 2) {
            echo $userObj->get_Open_id();
        } else if ($_REQUEST['mat'] == 3) {
            echo $userObj->getUserData_obj_edit($_REQUEST['id']);
        } else if ($_REQUEST['mat'] == 4) {
            echo $userObj->getUser_mgmt_range($_REQUEST['val']);
        } else if ($_REQUEST['mat'] == 5) {
            $result = $userObj->getUserTypeByUDept($_REQUEST['utype']);
            if ($result > 0) {
                ?>
                <option value="0">Select</option>
                <?php
                foreach ($result as $row) {
                ?>
                    <option value="<?php echo $row['utype']; ?>"><?php echo $row['section_name']; ?></option>
                <?php
                }
            } else {
                ?>
                <option value="0">Select</option>
            <?php
            }
        } else if ($_REQUEST['mat'] == 6) {             
            $userid = explode('~', $userObj->getUser_mgmt_range(0));
            echo $userid[5];
        } else if ($_REQUEST['mat'] == 7) {
            $result = $userObj->getAllUserTypefull_fromNewUser();
            $option = '';
            if ($result > 0) {            
              $option .=  '<option value="0">Select</option>';                
                foreach($result as $row) {
                    if ($_REQUEST['udept'] != 1)
                        if ($row['id'] == 1)
                            continue;                 
                   $option .= '<option value="'.$row['id'].'">'.$row['type_name'].'</option>';                 
                }
            } else {                
                $option .= '<option value="0">Select</option>';                 
            }
            echo $option;
        }
    }
}
