<?php

namespace App\Controllers\Listing;

use App\Controllers\BaseController;

use App\Models\Menu_model;
use CodeIgniter\Controller;
use CodeIgniter\Model;
use App\Models\Filing\AdvocateModel;
use App\Models\Casetype;
//use App\Models\Entities\Main;
use App\Models\Listing\CaseAdd;
use App\Models\Listing\JudgeGroup;
use App\Models\Listing\AdvanceClPrinted;
use CodeIgniter\Database\Database;

class AddCaseAdvance extends BaseController
{

    public $diary_no;
    public $Casetype;
    public $CaseAdd;
    public $AdvanceClPrinted;
    public $JudgeGroup;

    function __construct()
    {


        $this->Casetype = new Casetype();
        $this->CaseAdd = new CaseAdd();
        $this->AdvanceClPrinted = new AdvanceClPrinted();
        $this->JudgeGroup = new JudgeGroup();
    }
    public function add_case()
    {
        $usercode = session()->get('login')['usercode'];
        $userTypeString = getUser_dpdg_full_2($usercode);
        $userType = explode('~', $userTypeString);
        if (($userType[0] != 1 && $userType[0] != 57 && $userType[0] != 3 && $userType[0] != 4) && ($userType[6] != 450)) {
            echo "YOU ARE NOT AUTHORISED";
            exit();
        }


        // $data = [
        //     'session_diary_no' => session()->get('session_diary_no'),
        //     'session_diary_yr' => session()->get('session_diary_yr'),
        // ];
        return view('Listing/advance_list/case_add/add_case');
    }


    public function add_case_info_old()
    {
        $request = service('request');
        $ct = $request->getPost('case_type');
        $cn = $request->getPost('case_number');
        $cy = $request->getPost('case_year');
        $dyr = $request->getPost('diary_year');
        $dn = $request->getPost('diary_number');
        $dno = '';

        if (!empty($ct) && !empty($cn) && !empty($cy)) {

            $dno = $this->CaseAdd->getDiaryNo($ct, $cn, $cy);
        } else if (!empty($dn) && !empty($dyr)) {
            $dno = $dn . $dyr;
        } else {
            return redirect()->back()->with('error', 'Required parameters are missing.');
        }


        $dno = (string) $dno;
  



        $details = $this->CaseAdd->getCaseDetails(str_replace('/', '', $dno)); // use
      
        $data = [
            'dno' => str_replace('/', '', $dno),
            'details' => is_array($details) ? $details : [],
            'category' => $this->CaseAdd->getCategory(str_replace('/', '', $dno)) ?? '',
            'casetype' => $this->CaseAdd->getCaseType(str_replace('/', '', $dno)) ?? [],
            'main_case' => $this->CaseAdd->checkDiaryNo(str_replace('/', '', $dno)) ?? [],
            //'already_entries' => $this->CaseAdd->getAlreadyEntries($dno) ?? [],
            'hearingDetails' => $this->CaseAdd->getHearingDetails(str_replace('/', '', $dno)),
            'advance_list' => isset($details['next_dt']) ? $this->CaseAdd->getAdvanceDate($details['next_dt']) : [],
        ];
        
        $data['model'] = $this->CaseAdd;
        return view('Listing/advance_list/case_add/add_case_info', $data);
    }

    public function add_case_info()
    {
        $request = service('request');
        $ct = $request->getPost('case_type');
        $cn = $request->getPost('case_number');
        $cy = $request->getPost('case_year');
        $dyr = $request->getPost('diary_year');
        $dn = $request->getPost('diary_number');
        $data['ct']=$ct;
        $data['cn']=$cn;
        $data['cy']=$cy;
        $data['dyr']=$dyr;
        $data['dn']=$dn;
        $data['model'] = $this->CaseAdd;
        return view('Listing/advance_list/case_add/add_case_info', $data);
    }



    public function checkIfPublished()
    {
        $request = service('request');
        $date = $request->getPost('date');
        $query = $this->AdvanceClPrinted->isPrintedCase($date);
        return $this->response->setJSON(['msg' => $query]);
    }


    private function checkIfNMD($date)
    {
        $db = \Config\Database::connect();
        $query = $db->query("SELECT is_nmd FROM sc_working_days WHERE working_date = ? AND is_holiday = 0 AND display = 'Y'", [$date]);
        $result = $query->getRow();
        return $result ? $result->is_nmd : null;
    }

    public function save_case_to_advance_list()
    {
        $db = \Config\Database::connect();
        $request = service('request');
        $q_next_dt = $request->getPost('listing_date');
        $diary_number = $request->getPost('dno');
        $data['q_next_dt'] = $q_next_dt;
        $data['diary_number'] = $diary_number;
        $data['q_usercode'] = $this->session->get('login')['usercode'];
        $data['model'] = $this->CaseAdd;
        return view('Listing/advance_list/case_add/save_case_to_advance_list', $data);
    }





    private function allocateJudge($row_c, $coram, $misc_nmd_flag, $short_categoary_array)
    {
        $main_key = $row_c['main_key'];
        $q_diary_no = $row_c['diary_no'];
        $q_next_dt = '2023-10-01';
        $q_usercode =  session()->get('login')['usercode'];
        $q_clno = 'cl123';
        $board_type = $row_c['board_type'];
        $q_listorder = $row_c['listorder'];
        $finally_listed = 0;
        if ($misc_nmd_flag == 1 && in_array($row_c['submaster_id'], $short_categoary_array)) {
            // $possible_judges = $presiing_judge_str;
        } else {
            $possible_judges = $row_c['rid'];
        }
        if ($coram) {
            $coram_exploded = explode(",", $coram);
            foreach ($coram_exploded as $judge) {
                $allocationSuccess = $this->performAllocation($q_diary_no, $main_key, $q_next_dt, $board_type, $judge, $q_listorder, $q_usercode);
                if ($allocationSuccess) {
                    $finally_listed = 1;
                    break;
                }
            }
        }
        if ($finally_listed == 0) {
            echo "No judges were allocated for diary number: $q_diary_no\n";
        }
    }


    private function performAllocation($diary_no, $main_key, $next_dt, $board_type, $judge, $listorder, $usercode)
    {
        // Simulate allocation process
        // Insert into allocation table or perform business logic
        $db = \Config\Database::connect();
        $data = [
            'diary_no' => $diary_no,
            'main_key' => $main_key,
            'next_dt' => $next_dt,
            'board_type' => $board_type,
            'judge' => $judge,
            'listorder' => $listorder,
            'usercode' => $usercode,
        ];

        $db->table('allocation_table')->insert($data);

        return true;
    }

    private function advance_cl_printed($q_next_dt)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('advance_cl_printed');
        $builder->where('next_dt', $q_next_dt);
        $builder->where('board_type', 'J');
        $builder->where('display', 'Y');
        $query = $builder->get();
        return count($query->getResult()) > 0 ? 1 : 0;
    }







    public function upd_umpermission()
    {
        if ($this->request->getMethod() === 'post') {
            date_default_timezone_set('Asia/Kolkata');
            $action = htmlentities(trim($_POST['action']));
            $menu_id = (int)$_POST['menu_id'];
            switch ($action) {
                case 'getAlotmentMenu':

                    $count = 1;
                    /*$qsel='select GROUP_CONCAT(role_master_id) from user_role_master_mapping where usercode=? AND display="Y";';
                    $qselRs=$dbo->prepare($qsel);
                    $qselRs->bindParam(1, $menu_id, PDO::PARAM_INT);
                    $qselRs->execute();
                    $menuIds=$qselRs->fetchColumn();
                    $menuIds=explode(',', $menuIds);

                    $query="select id,role_desc,updated_on from role_master where display='Y' order by id;";
                    $rs=$dbo->prepare($query);
                    $rs->execute();
                    while ($rows=$rs->fetch(PDO::FETCH_BOTH, PDO::FETCH_ORI_NEXT)) {

                        foreach ($menuIds as $GetmId) {
                            $checked=''; $fontColor='text-danger';
                            if($rows[0] == $GetmId) {
                                $checked=' checked="checked"';
                                $fontColor='text-success'; break;
                            }
                        }

                        echo'<tr>
								<td>
									<input type="checkbox" name="mRoleId" value="'.$rows[0].'" id="'.$count.'"'.$checked.'>&nbsp;&nbsp;
									<label class="'.$fontColor.' font-weight-bold" for="'.$count.'">'.$rows[1].'</label>
								</td>
								<td><div class="lupdon"><i class="fa fa-calendar text-warning">&nbsp;Last updated on : </i>'.$rows[2].'</div></td>
							</tr>';
                        $count++;
                    }*/

                    break;

                case 'UpdUserDisplay_uy':
                    /*$ip = $_SERVER['REMOTE_ADDR']; $now=date('Y-m-d H:i:s');
                    $qupd='update users set display=?, updt=?, ip_address=? where usercode=?;'; $display='N';
                    $qrs=$dbo->prepare($qupd);
                    $qrs->bindParam(1, $display, PDO::PARAM_STR);
                    $qrs->bindParam(2, $now, PDO::PARAM_STR);
                    $qrs->bindParam(3, $ip, PDO::PARAM_STR);
                    $qrs->bindParam(4, $menu_id, PDO::PARAM_INT);
                    if($qrs->execute() == 1) echo json_encode(array('data'=>'success'));
                    else 					 echo json_encode(array('data'=>'failed'));*/
                    break;

                case 'UpdUserDisplay_un':
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $now = date('Y-m-d H:i:s');
                    /*$qupd='update users set display=?, updt=?, ip_address=? where usercode=?;'; $display='Y';
                    $qrs=$dbo->prepare($qupd);
                    $qrs->bindParam(1, $display, PDO::PARAM_STR);
                    $qrs->bindParam(2, $now, PDO::PARAM_STR);
                    $qrs->bindParam(3, $ip, PDO::PARAM_STR);
                    $qrs->bindParam(4, $menu_id, PDO::PARAM_INT);
                    if($qrs->execute() == 1) echo json_encode(array('data'=>'success'));
                    else 					 echo json_encode(array('data'=>'failed'));*/
                    break;

                case 'mn':
                    /*$qupd='update menu set display=? where id=?;'; $display='Y';
                    $qrs=$dbo->prepare($qupd);
                    $qrs->bindParam(1, $display, PDO::PARAM_STR);
                    $qrs->bindParam(2, $menu_id, PDO::PARAM_INT);
                    if($qrs->execute() == 1) echo json_encode(array('data'=>'success'));
                    else 					 echo json_encode(array('data'=>'failed'));*/
                    break;

                case 'my':
                    /* $qupd='update menu set display=? where id=?;'; $display='N';
                    $qrs=$dbo->prepare($qupd);
                    $qrs->bindParam(1, $display, PDO::PARAM_STR);
                    $qrs->bindParam(2, $menu_id, PDO::PARAM_INT);
                    if($qrs->execute() == 1) echo json_encode(array('data'=>'success'));
                    else 					 echo json_encode(array('data'=>'failed'));*/
                    break;

                case 'editMenu':
                    $menu_list = $this->Menu_model->get_menu_by_id($menu_id);
                    if (!empty($menu_list)) {
                        echo json_encode(array('data' => $menu_list));
                    }
                    break;
            }
            exit();
        }
    }
    public function addMenu()
    {
        if ($this->request->getMethod() === 'post') {
            if ($_POST['action'] == 'GrantPermission') {
            } elseif ($_POST['action'] == 'Update') {
            } elseif ($_POST['action'] == 'menuUpdate') {
                $menu_nm = htmlentities(trim($_POST['caption']));
                $priority = htmlentities(trim($_POST['priority']));
                $url = htmlentities(trim($_POST['url']));
                $menu_id = htmlentities(trim($_POST['menu_id']));
                $oldsmid = (int)$_POST['oldsmid'];
                $update_menu = [
                    'menu_nm' => $menu_nm,
                    'priority' => $priority,
                    'url' => $url,
                    'old_smenu_id' => $oldsmid,

                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => $_SESSION['login']['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];
                $is_update_menu = update('master.menu', $update_menu, ['id' => $menu_id]);
                if ($is_update_menu) {
                    echo 'Updated';
                    exit();
                } else {
                    echo 'Failed';
                    exit();
                }
            }

            $menu_id = htmlentities(trim($_POST['mnid']));

            if ($menu_id != '') {

                $disabled = '';
                switch (strlen($menu_id)) {
                    case 2:
                        $squery = "";
                        break;

                    case 4:
                        $squery = "";
                        break;
                    case 6:
                        $squery = "";
                        break;
                    case 8:
                        $squery = "";
                        break;
                    case 10:
                        $squery = "";
                        $disabled = ' disabled';
                        break;
                }
            } else {
                $menuId = htmlentities(trim($_POST['menu']));
                $caption = htmlentities(trim($_POST['caption']));
                $priority = (int)$_POST['priority'];
                $display = htmlentities(trim(strtoupper($_POST['display'])));
                $url = htmlentities(trim($_POST['url']));
                if ($url == null || $url == '') $url = '#';
                $oldsmid = (int)$_POST['oldsmid'];
                if (!is_numeric($oldsmid)) $oldsmid = 0;

                $child = 'child';
                for ($i = 1; $i <= 5; $i++) {
                    $childVar = $child . $i;
                    $$childVar = htmlentities(trim($_POST[$childVar]));
                    if (!$$childVar) break;
                }

                if (strstr($child5, 'addNew')) {

                    $preMenu = strtr($child5, array('addNew' => ''));
                } elseif (strstr($child4, 'addNew')) {

                    $preMenu = strtr($child4, array('addNew' => ''));
                } elseif (strstr($child3, 'addNew')) {

                    $preMenu = strtr($child3, array('addNew' => ''));
                } elseif (strstr($child2, 'addNew')) {

                    $preMenu = strtr($child2, array('addNew' => ''));
                } elseif (strstr($child1, 'addNew')) {

                    $preMenu = strtr($child1, array('addNew' => ''));
                } elseif (strstr($menuId, 'addNew')) {
                }
            }
        }
    }
}
