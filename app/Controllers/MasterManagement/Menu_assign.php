<?php

namespace App\Controllers\MasterManagement;

use App\Controllers\BaseController;
use App\Models\Entities\Model_menu;
use App\Models\Menu_model;
use CodeIgniter\Controller;
use App\Models\MasterManagement\MenuReportModel;
use App\Models\MasterManagement\MenuUserReportModel;
use App\Models\MasterManagement\ReportMenuPermissionModel;
use CodeIgniter\Model;


class Menu_assign extends BaseController
{
    public $Model_menu;
    public $Menu_model;
    public $MenuUserReportModel;
    public $ReportMenuPermissionModel;
    public $MenuReportModel;

    protected $dbo;


    function __construct()
    {
        ini_set('memory_limit', '51200M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
        $this->Model_menu = new Model_menu();
        $this->Menu_model = new Menu_model();
        $this->MenuReportModel = new MenuReportModel();
        $this->MenuUserReportModel = new MenuUserReportModel();
        $this->ReportMenuPermissionModel = new ReportMenuPermissionModel();
        $this->dbo = db_connect();
        //error_reporting(0);

    }

    public function index()
    {

        $data['get_menus_rs'] = $this->Model_menu->select("menu_nm,substr(menu_id,1,2),url as menu_id")->where(['substr(menu_id,3)' => '0000000000', 'display' => 'Y', 'menu_id is not' => null])->orderBy('priority')->get()->getResultArray();
        $data['action_permission_allotment'] = $this->Menu_model->get_action_permission_allotment();
        $data['menu_list'] = $this->Menu_model->get_menu_list();
        $data['role_master_list'] = $this->Menu_model->get_role_master_with_role_menu_mapping_list();
        //echo '<pre>';print_r($data['role_master_list']);exit();
        // pr($data);
        return view('MasterManagement/menu_assign/index', $data);
    }
    public function upd_umpermission()
    {
        if ($this->request->getMethod() === 'post') {
            date_default_timezone_set('Asia/Kolkata');
            $action = htmlentities(trim($_POST['action']));
            $menu_id = (int)$_POST['menu_id'];
            // $action = 'editMenu';
            switch ($action) {
                case 'getAlotmentMenu':
                    $count = 1;
                    $qsel = "select string_agg(role_master_id::text, ',')  from master.user_role_master_mapping where usercode ='$menu_id'AND display='Y'";
                    $qselRs = $this->db->query($qsel);
                    $menuIds = $qselRs->getRowArray()['string_agg'];
                    $menuIds = explode(',', $menuIds ?? '');


                    $query = "select id,role_desc,updated_on from master.role_master where display='Y' order by id";
                    $rs = $this->db->query($query);
                    $data = $rs->getResultArray();
                    foreach ($data as $rows) {
                        foreach ($menuIds as $GetmId) {
                            $checked = '';
                            $fontColor = 'text-danger';
                            if ($rows['id'] == $GetmId) {
                                $checked = ' checked="checked"';
                                $fontColor = 'text-success';
                                break;
                            }
                        }

                        echo '<tr>
								<td>
									<input type="checkbox" name="mRoleId" value="' . $rows['id'] . '" id="' . $count . '"' . $checked . '>&nbsp;&nbsp;
									<label class="' . $fontColor . ' font-weight-bold" for="' . $count . '">' . $rows['role_desc'] . '</label>
								</td>
								<td><div class="lupdon"><i class="fa fa-calendar text-warning">&nbsp;Last updated on : </i>' . $rows['updated_on'] . '</div></td> 
							</tr>';
                        $count++;
                    }

                    break;

                case 'UpdUserDisplay_uy':
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $now = date('Y-m-d H:i:s');
                    $display = 'N';
                    $qupd = "update master.users set display='$display', updt='$now', ip_address='$ip' where usercode='$menu_id'";
                    $qrs = $this->db->query($qupd);
                    if ($qrs == 1) {
                        echo json_encode(array('data' => 'success'));
                    } else {
                        echo json_encode(array('data' => 'failed'));
                    }
                    break;


                case 'UpdUserDisplay_un':
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $now = date('Y-m-d H:i:s');
                    $display = 'Y';
                    $qupd = "update master.users set display='$display', updt='$now', ip_address='$ip' where usercode='$menu_id'";
                    $qrs = $this->db->query($qupd);
                    if ($qrs == 1) echo json_encode(array('data' => 'success'));
                    else                      echo json_encode(array('data' => 'failed'));

                    break;

                case 'mn':
                    $data = [
                        'display' => 'Y'
                    ];
                    $this->db->table('master.menu')
                        ->where('id', $menu_id)
                        ->update($data);
                    if ($this->db->affectedRows() === 1) {
                        echo json_encode(['data' => 'success']);
                    } else {
                        echo json_encode(['data' => 'failed']);
                    }
                    break;

                case 'my':
                    $data = [
                        'display' => 'N'
                    ];
                    $this->db->table('master.menu')
                        ->where('id', $menu_id)
                        ->update($data);

                    if ($this->db->affectedRows() === 1) {
                        echo json_encode(['data' => 'success']);
                    } else {
                        echo json_encode(['data' => 'failed']);
                    }
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
            $updatemenu =   $this->request->getPost('menu_id');
            if (isset($updatemenu) || !empty($updatemenu)) {
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
            }

            $menu_id = htmlentities(trim($_POST['mnid'] ?? ''));
            if ($menu_id != '') {
                $disabled = '';
                switch (strlen($menu_id)) {
                    case 2:
                        $squery = "SELECT menu_nm, SUBSTRING(menu_id, 1, 4) AS sml1_id 
                                    FROM master.menu 
                                    WHERE SUBSTRING(menu_id, 5) = '00000000' 
                                    AND SUBSTRING(menu_id, 1, 2) = ? 
                                    AND SUBSTRING(menu_id, 3, 2) <> '00' 
                                    AND display = 'Y' 
                                    AND menu_id IS NOT NULL 
                                    ORDER BY priority, SUBSTRING(menu_id, 1, 4);";
                        break;

                    case 4:
                        $squery = "SELECT menu_nm, SUBSTRING(menu_id, 1, 6) AS sml2_id 
                                    FROM master.menu 
                                    WHERE SUBSTRING(menu_id, 7) = '000000' 
                                    AND SUBSTRING(menu_id, 1, 4) = ? 
                                    AND SUBSTRING(menu_id, 5, 2) <> '00' 
                                    AND display = 'Y' 
                                    AND menu_id IS NOT NULL 
                                    ORDER BY priority, SUBSTRING(menu_id, 1, 4);";
                        break;

                    case 6:
                        $squery = "SELECT menu_nm, SUBSTRING(menu_id, 1, 8) AS sml3_id 
                                    FROM master.menu 
                                    WHERE SUBSTRING(menu_id, 9) = '0000' 
                                    AND SUBSTRING(menu_id, 1, 6) = ? 
                                    AND SUBSTRING(menu_id, 7, 2) <> '00' 
                                    AND display = 'Y' 
                                    AND menu_id IS NOT NULL 
                                    ORDER BY priority, SUBSTRING(menu_id, 1, 4);";
                        break;

                    case 8:
                        $squery = "SELECT menu_nm, SUBSTRING(menu_id, 1, 10) AS sml4_id 
                                    FROM master.menu 
                                    WHERE SUBSTRING(menu_id, 11) = '00' 
                                    AND SUBSTRING(menu_id, 1, 8) = ? 
                                    AND SUBSTRING(menu_id, 9, 2) <> '00' 
                                    AND display = 'Y' 
                                    AND menu_id IS NOT NULL 
                                    ORDER BY priority, SUBSTRING(menu_id, 1, 4);";
                        break;

                    case 10:
                        $squery = "SELECT menu_nm, SUBSTRING(menu_id, 1, 12) AS sml5_id 
                                    FROM master.menu 
                                    WHERE SUBSTRING(menu_id, 1, 10) = ? 
                                    AND SUBSTRING(menu_id, 11, 2) <> '00' 
                                    AND display = 'Y' 
                                    AND menu_id IS NOT NULL 
                                    ORDER BY priority, SUBSTRING(menu_id, 1, 4);";
                        $disabled = ' disabled';
                        break;
                }

                $query = $this->db->query($squery, [$menu_id]);
                $smlv1 = '<option value="">Select Sub menu level</option><option value="' . $menu_id . 'addNew" class="text-danger">Add New</option>';
                if ($query->getNumRows() > 0) {
                    foreach ($query->getResultArray() as $smlv1_rows) {
                        for ($i = 1; $i <= 10; $i++) {
                            $smlv1_id_key = 'sml' . $i . '_id';
                            if (isset($smlv1_rows[$smlv1_id_key])) {
                                $smlv1 .= '<option value="' . $smlv1_rows[$smlv1_id_key] . '"' . $disabled . '>' . $smlv1_rows['menu_nm'] . '</option>';
                            }
                        }
                    }
                }
                echo $smlv1;
            } else {

                // $menuId = htmlentities(trim($this->request->getPost('menu')));
                // $caption = htmlentities(trim($this->request->getPost('caption')));
                // $priority = (int) $this->request->getPost('priority');
                // $display = htmlentities(trim(strtoupper($this->request->getPost('display'))));
                // $url = htmlentities(trim($this->request->getPost('url'))) ?: '#';   
                // $oldsmid = (int) $this->request->getPost('oldsmid');
                // if (!is_numeric($oldsmid)) $oldsmid = 0;

                // $children = [];
                // for ($i = 1; $i <= 10; $i++) {
                //     $childVar = 'child' . $i;
                //     $children[$i] = $this->request->getPost($childVar);
                //     if (empty($children[$i])) break;
                // }

                // foreach ($children as $index => $child) {
                //     if (strpos($child, 'addNew') !== false) {
                //         $preMenu = str_replace('addNew', '', $child);
                //         $level = 11 - (2 * $index); 

                //         $query = "SELECT MAX(SUBSTRING(menu_id FROM $level FOR 2)) AS maxChild 
                //                     FROM master.menu 
                //                     WHERE SUBSTRING(menu_id FROM 1 FOR $level) = ? 
                //                     AND SUBSTRING(menu_id FROM $level FOR 2) <> '00' 
                //                     AND SUBSTRING(menu_id FROM $level + 2 FOR 2) = '00';";
                //    pr($result);
                //         $result = $this->dbo->query($query, [$preMenu]);

                //         $childMaxId = $result->getRow()->maxChild;

                //         $childMaxId = ($childMaxId !== null) ? (int)$childMaxId + 1 : 1;
                //         $childMaxId = str_pad($childMaxId, 2, '0', STR_PAD_LEFT);
                //         $childMaxId = $preMenu . $childMaxId . str_repeat('0', 10 - strlen($childMaxId));

                //         $insertQuery = "INSERT INTO master.menu (menu_nm, priority, display, menu_id, url, old_smenu_id) 
                //                         VALUES (?, ?, ?, ?, ?, ?);";

                //         if ($this->dbo->query($insertQuery, [$caption, $priority, $display, $childMaxId, $url, $oldsmid])) {
                //             echo 'Inserted';
                //         } else {
                //             echo 'Failed';
                //         }
                //         return;
                //     }
                // }

                // if (strpos($menuId, 'addNew') !== false) {
                //     $query = "SELECT MAX(SUBSTRING(menu_id FROM 1 FOR 2)) AS maxMenuId 
                //               FROM master.menu 
                //               WHERE SUBSTRING(menu_id FROM 1 FOR 2) <> '00' 
                //               AND SUBSTRING(menu_id FROM 3) = '0000000000';";

                //     $result = $this->dbo->query($query);
                //     $menuMaxId = $result->getRow()->maxMenuId;

                //     $menuMaxId = ($menuMaxId !== null) ? (int)$menuMaxId + 1 : 1;
                //     $menuMaxId = str_pad($menuMaxId, 2, '0', STR_PAD_LEFT) . '0000000000';

                //     $insertQuery = "INSERT INTO master.menu (menu_nm, priority, display, menu_id, url, old_smenu_id) 
                //                     VALUES (?, ?, ?, ?, ?, ?);";

                //     if ($this->dbo->query($insertQuery, [$caption, $priority, $display, $menuMaxId, $url, $oldsmid])) {
                //         echo 'Inserted';
                //     } else {
                //         echo 'Failed';
                //     }
                // }
                $db = \Config\Database::connect();
                $menuId = htmlentities(trim($_POST['menu'] ?? ''));
                $caption = htmlentities(trim($_POST['caption'] ?? ''));
                $priority = (int)($_POST['priority'] ?? 0);
                $display = htmlentities(trim(strtoupper($_POST['display'] ?? '')));
                $url = htmlentities(trim($_POST['url'] ?? '#'));
                $oldsmid = (int)($_POST['oldsmid'] ?? 0);
                if (!is_numeric($oldsmid)) {
                    $oldsmid = 0;
                }
                $children = [];
                for ($i = 1; $i <= 5; $i++) {
                    $children[$i] = htmlentities(trim($this->request->getPost('child' . $i) ?? ''));
                }
                function insertMenuItem($db, $caption, $priority, $display, $menuId, $url, $oldsmid)
                {
                    return $db->table('master.menu')->insert([
                        'menu_nm' => $caption,
                        'priority' => $priority,
                        'display' => $display,
                        'menu_id' => $menuId,
                        'url' => $url,
                        'old_smenu_id' => $oldsmid,
                    ]);
                }

                for ($i = 5; $i >= 1; $i--) {
                    if (isset($children[$i]) && is_string($children[$i]) && strstr($children[$i], 'addNew')) {
                        $preMenu = str_replace('addNew', '', $children[$i]);
                        $query = 'SELECT max(substr(menu_id, ' . (11 - $i * 2) . ', 2)) as maxChild 
                                  FROM master.menu 
                                  WHERE substr(menu_id, 1, ' . (10 - $i * 2) . ') = ? 
                                  AND substr(menu_id, ' . (11 - $i * 2) . ', 2) <> \'00\' 
                                  AND substr(menu_id, ' . (9 - $i * 2) . ', 4) = \'0000\'';

                        $maxChild = $db->query($query, [$preMenu])->getRow();
                        log_message('info', 'PreMenu: ' . $preMenu . ', Max Child: ' . json_encode($maxChild));
                        if ($maxChild && isset($maxChild->maxChild)) {
                            $childMaxId = (int)$maxChild->maxChild + 1;
                            if ($childMaxId <= 9) $childMaxId = '0' . $childMaxId;
                            $childMaxId = $preMenu . $childMaxId . str_repeat('0', (5 - $i) * 2);
                        } else {
                            $childMaxId = $preMenu . '01' . str_repeat('0', (5 - $i) * 2);
                        }
                        if (insertMenuItem($db, $caption, $priority, $display, $childMaxId, $url, $oldsmid)) {
                            echo 'Inserted';
                        } else {
                            echo 'Failed';
                        }
                        break;
                    }
                }

                if (strstr($menuId, 'addNew')) {
                    $query = 'SELECT max(substr(menu_id, 1, 2)) as maxMenuId 
                              FROM master.menu 
                              WHERE substr(menu_id, 1, 2) <> \'00\' 
                              AND substr(menu_id, 3) = \'0000000000\'';
                    $maxMenuId = $db->query($query)->getRow();
                    log_message('info', 'Max Menu ID: ' . json_encode($maxMenuId));

                    if ($maxMenuId && isset($maxMenuId->maxMenuId)) {
                        $menuMaxId = (int)$maxMenuId->maxMenuId + 1;
                        if ($menuMaxId <= 9) $menuMaxId = '0' . $menuMaxId;
                        $menuMaxId .= '0000000000';
                    } else {
                        $menuMaxId = '010000000000';
                    }

                    if (insertMenuItem($db, $caption, $priority, $display, $menuMaxId, $url, $oldsmid)) {
                        echo 'Inserted';
                    } else {
                        echo 'Failed';
                    }
                }
            }
        }
    }

    public function premession_update()
    {

        // pr($_REQUEST);
        $now = date('Y-m-d H:i:s');

        if ($this->request->getPost('action') == 'GrantPermission') {
           
            $selected_menus = htmlentities(trim(rtrim($this->request->getPost('selected_menus'), ',')));
            $usercode = (int)$this->request->getPost('usercode');
            $query = "insert into user_menu_permission (usercode,menu_permission) values($usercode,$selected_menus);";
            pr($query);
            $rs = $this->db->query($query);
            if ($rs){
                $return = 'Inserted';
            }
            else{
                $return ='Failed';
            }
           return $return;

        } elseif ($this->request->getPost('action') == 'Update') {
            $selected_menus = htmlentities(trim(rtrim($this->request->getPost('selected_menus'), ',')));
            $selected_menus = explode(',', $selected_menus);

            $postUcode = $this->request->getPost('usercode');
            if (substr_count($postUcode, ','))
                $ucdArray = explode(',', rtrim($postUcode, ','));
            else      $ucdArray = array(0 => $postUcode);

            foreach ($ucdArray as $usercode) {

                $chkFirst = "select id from master.user_role_master_mapping where usercode='$usercode'";
                $chkRs = $this->db->query($chkFirst);
                $chkRs->getRowArray();

                if (!empty($chkRs)) {
                    $qihistory = "insert into master.user_role_master_mapping_history (select * from master.user_role_master_mapping where usercode='$usercode')";
                    $qihistoryRs =  $this->db->query($qihistory);   

                    $qupd = "delete from master.user_role_master_mapping where usercode='$usercode'";
                    $qupdRs = $this->db->query($qupd);
                }
                foreach ($selected_menus as $mid) {
                    $data = [
                        'usercode' => $usercode,
                        'role_master_id' => $mid,
                        'updated_on' => $now
                    ];

                    $this->db->table('master.user_role_master_mapping')->insert($data);

                    if ($this->db->affectedRows() > 0) {
                        $return = 'Inserted';
                    } else {
                        $return = 'Failed';
                    }
                }
            }
            return trim($return);

           
        } elseif ($this->request->getPost('action') == 'menuUpdate') {
            $menu_nm = htmlentities(trim($this->request->getPost('caption')));
            $priority = htmlentities(trim($this->request->getPost('priority')));
            $url = htmlentities(trim($this->request->getPost('url')));
            $menu_id = htmlentities(trim($this->request->getPost('menu_id')));
            $oldsmid = (int)$this->request->getPost('oldsmid');
            $updq = 'update menu set menu_nm=?, priority=?, url=?, old_smenu_id=? where id=?;';
            $qrs = $dbo->prepare($updq);
            $qrs->bindParam(1, $menu_nm, PDO::PARAM_STR);
            $qrs->bindParam(2, $priority, PDO::PARAM_INT);
            $qrs->bindParam(3, $url, PDO::PARAM_STR);
            $qrs->bindParam(4, $oldsmid, PDO::PARAM_INT);
            $qrs->bindParam(5, $menu_id, PDO::PARAM_STR);
            if ($qrs->execute() == 1) echo 'Updated';
            else                     echo 'Failed';
            $dbo = null;
            exit();
        }
        $menu_id = htmlentities(trim($this->request->getPost('mnid')));
        if ($menu_id != '') {

            $disabled = '';
            switch (strlen($menu_id)) {
                case 2:

                    $squery = "select menu_nm,substr(menu_id,1,4) as sml1_id from menu where substr(menu_id,5)='00000000' AND substr(menu_id,1,2)=? AND substr(menu_id,3,2) <>'00' AND display='Y' AND menu_id is not null order by priority, substr(menu_id,1,4);";

                    break;

                case 4:

                    $squery = "select menu_nm,substr(menu_id,1,6) as sml2_id from menu where substr(menu_id,7)='000000' AND substr(menu_id,1,4)=? AND substr(menu_id,5,2) <>'00' AND display='Y' AND menu_id is not null order by priority, substr(menu_id,1,4);";

                    break;

                case 6:

                    $squery = "select menu_nm,substr(menu_id,1,8) as sml3_id from menu where substr(menu_id,9)='0000' AND substr(menu_id,1,6)=? AND substr(menu_id,7,2) <>'00' AND display='Y' AND menu_id is not null order by priority, substr(menu_id,1,4);";

                    break;

                case 8:

                    $squery = "select menu_nm,substr(menu_id,1,10) as sml4_id from menu where substr(menu_id,11)='00' AND substr(menu_id,1,8)=? AND substr(menu_id,9,2) <>'00' AND display='Y' AND menu_id is not null order by priority, substr(menu_id,1,4);";

                    break;

                case 10:

                    $squery = "select menu_nm,substr(menu_id,1,12) as sml5_id from menu where substr(menu_id,1,10)=? AND substr(menu_id,11,2) <>'00' AND display='Y' AND menu_id is not null order by priority, substr(menu_id,1,4);";
                    $disabled = ' disabled';

                    break;
            }

            $smlv1_rs = $dbo->prepare($squery);
            $smlv1_rs->bindParam(1, $menu_id, PDO::PARAM_STR);
            $smlv1_rs->execute();

            $smlv1 = '<option value="">Select Sub menu level</option><option value="' . $menu_id . 'addNew" class="text-danger">Add New</option>';
            if ($smlv1_rs->rowCount() > 0) {
                while ($smlv1_rows = $smlv1_rs->fetch(PDO::FETCH_BOTH, PDO::FETCH_ORI_NEXT)) {
                    $smlv1 .= '<option value="' . $smlv1_rows[1] . '"' . $disabled . '>' . $smlv1_rows[0] . '</option>';
                }
            }
            echo $smlv1;
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
                $query = 'select max(substr(menu_id,11,2)) maxChild from menu where substr(menu_id,1,10)=? and substr(menu_id,11,2) <>"00";';
                $rs = $dbo->prepare($query);
                $rs->bindParam(1, $preMenu, PDO::PARAM_STR);
                $rs->execute();
                if ($rs->rowCount() > 0) {
                    $childMaxId = (int)$rs->fetchColumn() + 1;
                    if ($childMaxId <= 9) $childMaxId = '0' . $childMaxId;
                    $childMaxId = $preMenu . $childMaxId;
                } else {
                    $childMaxId = $preMenu . '01';
                }

                $insertQuery = 'insert into menu(menu_nm,priority,display,menu_id,url,old_smenu_id) values(?,?,?,?,?,?);';
                $rs = $dbo->prepare($insertQuery);
                $rs->bindParam(1, $caption, PDO::PARAM_STR);
                $rs->bindParam(2, $priority, PDO::PARAM_INT);
                $rs->bindParam(3, $display, PDO::PARAM_STR);
                $rs->bindParam(4, $childMaxId, PDO::PARAM_STR);
                $rs->bindParam(5, $url, PDO::PARAM_STR);
                $rs->bindParam(6, $oldsmid, PDO::PARAM_INT);
                if ($rs->execute() == 1) echo 'Inserted';
                else echo 'Failed';
            } elseif (strstr($child4, 'addNew')) {

                $preMenu = strtr($child4, array('addNew' => ''));
                $query = 'select max(substr(menu_id,9,2)) maxChild from menu where substr(menu_id,1,8)=? and substr(menu_id,9,2) <>"00" and substr(menu_id,11,2)="00";';
                $rs = $dbo->prepare($query);
                $rs->bindParam(1, $preMenu, PDO::PARAM_STR);
                $rs->execute();
                if ($rs->rowCount() > 0) {
                    $childMaxId = (int)$rs->fetchColumn() + 1;
                    if ($childMaxId <= 9) $childMaxId = '0' . $childMaxId;
                    $childMaxId = $preMenu . $childMaxId . '00';
                } else {
                    $childMaxId = $preMenu . '0100';
                }

                $insertQuery = 'insert into menu(menu_nm,priority,display,menu_id,url,old_smenu_id) values(?,?,?,?,?,?);';
                $rs = $dbo->prepare($insertQuery);
                $rs->bindParam(1, $caption, PDO::PARAM_STR);
                $rs->bindParam(2, $priority, PDO::PARAM_INT);
                $rs->bindParam(3, $display, PDO::PARAM_STR);
                $rs->bindParam(4, $childMaxId, PDO::PARAM_STR);
                $rs->bindParam(5, $url, PDO::PARAM_STR);
                $rs->bindParam(6, $oldsmid, PDO::PARAM_INT);
                if ($rs->execute() == 1) echo 'Inserted';
                else echo 'Failed';
            } elseif (strstr($child3, 'addNew')) {

                $preMenu = strtr($child3, array('addNew' => ''));
                $query = 'select max(substr(menu_id,7,2)) maxChild from menu where substr(menu_id,1,6)=? and substr(menu_id,7,2) <>"00" and substr(menu_id,9,4)="0000";';
                $rs = $dbo->prepare($query);
                $rs->bindParam(1, $preMenu, PDO::PARAM_STR);
                $rs->execute();
                if ($rs->rowCount() > 0) {
                    $childMaxId = (int)$rs->fetchColumn() + 1;
                    if ($childMaxId <= 9) $childMaxId = '0' . $childMaxId;
                    $childMaxId = $preMenu . $childMaxId . '0000';
                } else {
                    $childMaxId = $preMenu . '010000';
                }

                $insertQuery = 'insert into menu(menu_nm,priority,display,menu_id,url,old_smenu_id) values(?,?,?,?,?,?);';
                $rs = $dbo->prepare($insertQuery);
                $rs->bindParam(1, $caption, PDO::PARAM_STR);
                $rs->bindParam(2, $priority, PDO::PARAM_INT);
                $rs->bindParam(3, $display, PDO::PARAM_STR);
                $rs->bindParam(4, $childMaxId, PDO::PARAM_STR);
                $rs->bindParam(5, $url, PDO::PARAM_STR);
                $rs->bindParam(6, $oldsmid, PDO::PARAM_INT);
                if ($rs->execute() == 1) echo 'Inserted';
                else echo 'Failed';
            } elseif (strstr($child2, 'addNew')) {

                $preMenu = strtr($child2, array('addNew' => ''));
                $query = 'select max(substr(menu_id,5,2)) maxChild from menu where substr(menu_id,1,4)=? and substr(menu_id,5,2) <>"00" and substr(menu_id,7,6)="000000";';
                $rs = $dbo->prepare($query);
                $rs->bindParam(1, $preMenu, PDO::PARAM_STR);
                $rs->execute();
                if ($rs->rowCount() > 0) {
                    $childMaxId = (int)$rs->fetchColumn() + 1;
                    if ($childMaxId <= 9) $childMaxId = '0' . $childMaxId;
                    $childMaxId = $preMenu . $childMaxId . '000000';
                } else {
                    $childMaxId = $preMenu . '01000000';
                }

                $insertQuery = 'insert into menu(menu_nm,priority,display,menu_id,url,old_smenu_id) values(?,?,?,?,?,?);';
                $rs = $dbo->prepare($insertQuery);
                $rs->bindParam(1, $caption, PDO::PARAM_STR);
                $rs->bindParam(2, $priority, PDO::PARAM_INT);
                $rs->bindParam(3, $display, PDO::PARAM_STR);
                $rs->bindParam(4, $childMaxId, PDO::PARAM_STR);
                $rs->bindParam(5, $url, PDO::PARAM_STR);
                $rs->bindParam(6, $oldsmid, PDO::PARAM_INT);
                if ($rs->execute() == 1) echo 'Inserted';
                else echo 'Failed';
            } elseif (strstr($child1, 'addNew')) {

                $preMenu = strtr($child1, array('addNew' => ''));
                $query = 'select max(substr(menu_id,3,2)) maxChild from menu where substr(menu_id,1,2)=? and substr(menu_id,3,2) <>"00" and substr(menu_id,5,8)="00000000";';
                $rs = $dbo->prepare($query);
                $rs->bindParam(1, $preMenu, PDO::PARAM_STR);
                $rs->execute();
                if ($rs->rowCount() > 0) {
                    $childMaxId = (int)$rs->fetchColumn() + 1;
                    if ($childMaxId <= 9) $childMaxId = '0' . $childMaxId;
                    $childMaxId = $preMenu . $childMaxId . '00000000';
                } else {
                    $childMaxId = $preMenu . '0100000000';
                }

                $insertQuery = 'insert into menu(menu_nm,priority,display,menu_id,url,old_smenu_id) values(?,?,?,?,?,?);';
                $rs = $dbo->prepare($insertQuery);
                $rs->bindParam(1, $caption, PDO::PARAM_STR);
                $rs->bindParam(2, $priority, PDO::PARAM_INT);
                $rs->bindParam(3, $display, PDO::PARAM_STR);
                $rs->bindParam(4, $childMaxId, PDO::PARAM_STR);
                $rs->bindParam(5, $url, PDO::PARAM_STR);
                $rs->bindParam(6, $oldsmid, PDO::PARAM_INT);
                if ($rs->execute() == 1) echo 'Inserted';
                else echo 'Failed';
            } elseif (strstr($menuId, 'addNew')) {
                $query = 'select max(substr(menu_id,1,2)) maxMenuId from menu where substr(menu_id,1,2) <>"00" and substr(menu_id,3)="0000000000";';
                $rs = $dbo->prepare($query);
                $rs->execute();
                if ($rs->rowCount() > 0) {
                    $menuMaxId = (int)$rs->fetchColumn() + 1;
                    if ($menuMaxId <= 9) $menuMaxId = '0' . $menuMaxId;
                    $menuMaxId .= '0000000000';
                } else $menuMaxId = '010000000000';

                $insertQuery = 'insert into menu(menu_nm,priority,display,menu_id,url,old_smenu_id) values(?,?,?,?,?,?);';
                $rs = $dbo->prepare($insertQuery);
                $rs->bindParam(1, $caption, PDO::PARAM_STR);
                $rs->bindParam(2, $priority, PDO::PARAM_INT);
                $rs->bindParam(3, $display, PDO::PARAM_STR);
                $rs->bindParam(4, $menuMaxId, PDO::PARAM_STR);
                $rs->bindParam(5, $url, PDO::PARAM_STR);
                $rs->bindParam(6, $oldsmid, PDO::PARAM_INT);
                if ($rs->execute() == 1) echo 'Inserted';
                else echo 'Failed';
            }
        }
    }



    public function Reportroles()
    {
        $data['models'] =  $this->MenuReportModel;
        $data['roles'] = $this->MenuReportModel->getMenuRolesOrderedById();
        $RolesList = $this->request->getPost('roles_list');
        $data['Roleslist'] = $this->request->getPost('roles_list');
        $data['role_array'] =  NULL;
        if (isset($RolesList) && !empty($RolesList)) {
            $data['role_array'] =  $this->MenuReportModel->getMenuRolesById($RolesList);
            //    pr($datas);
        }



        return view('MasterManagement/menu_assign/report/roles', $data);
    }




    public function MenuUserReport()
    {
        $data['models'] =  $this->MenuReportModel;
        $data['user_alllist'] = $this->MenuUserReportModel->getUserReportOrderedById();
        $userId = $this->request->getPost('users_list');
        $data['UserId'] =  $userId;
        $data['user_list'] =  NULL;
        if (isset($userId) && !empty($userId)) {
            $data['user_list'] =  $this->MenuUserReportModel->getMenuUserReportById($userId);
        }


        return view('MasterManagement/menu_assign/report/userreport', $data);
    }






    public function ReportMenuPermission()
    {

        $data['main_menu'] = $this->ReportMenuPermissionModel->getAllMnMenuById();
        $userId = $this->request->getPost('users_list');
        $data['UserId'] =  $userId;
        $data['user_list'] =  NULL;
        if (isset($userId) && !empty($userId)) {
            $data['user_list'] =  $this->MenuUserReportModel->getMenuUserReportById($userId);
        }

        return view('MasterManagement/menu_assign/menu_privilege/report_menu_permission', $data);
    }



    public function RepMenuPermission()
    {

        $mn_id = $this->request->getPost('ddl_mn_menu');
        $data = $this->ReportMenuPermissionModel->whereIn('id', [$mn_id])->findAll();
        $result = '';
        if (!empty($data)) {
            $result = $data[0]['menu_nm'];
        }
        $result2 = $this->ReportMenuPermissionModel->getUserByMenuID($mn_id);
        if (empty($result2)) {
            echo "<h2 style='color:red;'>No users allotted to this menu.</h2>";
            return;
        }

        echo '<div class="text-right mb-3 mt-5" style="float: inline-end;">
            <button type="button" class="btn btn-secondary" id="print1" onclick="printDiv(\'result\')">Print</button>
            </div>';
        echo '<table width="100%" border="1" id="datatables" class="table table-striped table-bordered table-hover">';
        echo '<tr><td colspan="6" align="center"><font color="blue"><h3>' . $result . ' menu allotted to the following users</h3></font></td></tr>';
        echo '<tr>';
        echo '<td><h5>S.NO</h5></td>';
        echo '<td><h5>Emp Id</h5></td>';
        echo '<td><h5>Name</h5></td>';
        echo '<td><h5>Section</h5></td>';
        echo '<td><h5>Designation</h5></td>';
        echo '<td><h5>Action</h5></td>';
        echo '</tr>';
        $sno = 0;
        foreach ($result2 as $rw) {
            ++$sno;
            $ucode = $rw['us_code'];
            $empid = $rw['empid'];
            $uname = $rw['name'];
            $section_name = $rw['section_name'];
            $tname = $rw['uname'];
            echo '<tr>';
            echo '<td>' . $sno . '</td>';
            echo '<td>' . $empid . '</td>';
            echo '<td>' . $uname . '</td>';
            echo '<td>' . $section_name . '</td>';
            echo '<td>' . $tname . '</td>';
            echo '<td><input type="button" onclick="revokethis(this.id)" value="Revoke" id="' . $mn_id . '?' . $ucode . '"/></td>';
            echo '</tr>';
        }

        echo '</table>';
    }



    public function RevokeMainMenu()
    {
        $qq = $this->request->getGet('q');
        $arr = explode('?', $qq);
        if (count($arr) < 2) {
            return $this->response->setStatusCode(400, 'Invalid parameters');
        }

        try {

            $result =  $this->ReportMenuPermissionModel->getRevokeMenuID($arr[0], $arr[1]);
            $result2 =  '';
            foreach ($result as $item) {
                $subID =  $item['res'];
                $result2 =  $this->ReportMenuPermissionModel->getUpdateRevoke($subID, $arr);
            }
            if ($result2 == true) {
                return $this->response->setJSON(['status' => 200, 'message' => 'Menu permissions revoked successfully']);
            } else {
                return $this->response->setJSON(['status' => 400, 'message' => 'Failed to revoke menu permissions']);
            }
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return redirect()->to('/error')->with('error', 'An error occurred.');
        }
    }




    public function Rep_Second_Menu()
    {
        $mn_id = $this->request->getGet('ddl_mn_menu');
        $sm_id = $this->request->getGet('ddl_sub_menu_3');

        // $result1 = $this->ReportMenuPermissionModel->getsubmenuMn_id($mn_id);
        // $menuName = !empty($result1) ? $result1[0]['menu_nm'] : 'Unknown Menu';

        // $result2 = $this->ReportMenuPermissionModel->getsubmenu($sm_id);
        // $subMenuName = !empty($result2) ? $result2[0]['sub_mn_nm'] : 'Unknown Submenu';
        // Get menu names
        $result1 = $this->ReportMenuPermissionModel->getsubmenuMn_id($mn_id);
        $menuNames = [];
        foreach ($result1 as $item) {
            $menuNames[] = $item['menu_nm'];
        }
        $menuName = !empty($menuNames) ? implode(', ', $menuNames) : 'Unknown Menu';

        // Get submenu names
        $result2 = $this->ReportMenuPermissionModel->getsubmenu($sm_id);
        $subMenuNames = [];
        foreach ($result2 as $item) {
            $subMenuNames[] = $item['sub_mn_nm'];
        }
        $subMenuName = !empty($subMenuNames) ? implode(', ', $subMenuNames) : 'Unknown Submenu';


        $menuResult = $this->ReportMenuPermissionModel->getsubmenuMnSm($mn_id, $sm_id);

        echo '<div class="text-right mb-3 mt-5" style="float: inline-end;">
            <button type="button" class="btn btn-secondary" id="print1" onclick="printDiv(\'result\')">Print</button>
            </div>';
        echo '<div class="table-responsive mt-5">';
        echo '<table width="100%" border="1" class="table table-bordered table-sm table-hover table-striped">';
        echo '<tr><td colspan="6" align="center"><font color="blue"><h3 style="color:blue;">' . htmlspecialchars($menuName . ' -> ' . $subMenuName . ' menu allotted to the following users') . '</h3></font></td></tr>';
        echo '<tr>';
        echo '<td>S.NO</td>';
        echo '<td>Emp Id</td>';
        echo '<td>Name</td>';
        echo '<td>Section</td>';
        echo '<td>Designation</td>';
        echo '<td>Action</td>';
        echo '</tr>';
        $sno = 1;
        if (!empty($menuResult)) {
            foreach ($menuResult as $row) {
                $ucode = $row['ucode'];
                $empid = $row['empid'];
                $uname = $row['name'];
                $sectionName = $row['section_name'];
                $tname = $row['uname'];
                $resultMain = $this->ReportMenuPermissionModel->MnMePer($ucode, $mn_id);
                $hasAccess = false;

                foreach ($resultMain as $mainRow) {
                    if (trim($mainRow['display']) === 'Y') {
                        $hasAccess = true;
                        break;
                    }
                }

                // Only display if user has access
                if ($hasAccess) {
                    echo '<tr>';
                    echo '<td>' . $sno++ . '</td>';
                    echo '<td>' . htmlspecialchars($empid) . '</td>';
                    echo '<td>' . htmlspecialchars($uname) . '</td>';
                    echo '<td>' . htmlspecialchars($sectionName) . '</td>';
                    echo '<td>' . htmlspecialchars($tname) . '</td>';
                    echo '<td><input type="button" onclick="revokethis(this.id)" value="Revoke" id="' . htmlspecialchars($mn_id . '?' . $sm_id . '?' . $ucode) . '"></td>';
                    echo '</tr>';
                }
            }
        } else {
            echo '<tr>';
            echo '<td colspan="6"> No data available in table</td>';
            echo '</tr>';
        }

        echo '</table>';
        echo '</div>';
    }





    public function getSubMenus()
    {

        $str1 = $this->request->getGet('str');
        $sql = "select su_menu_id,sub_mn_nm from master.submenu where id='$str1' and display='Y' order by o_d";
        $query  = $this->dbo->query($sql);
        $result = $query->getResultArray();
        $html = '<option value="">--Select Option--</option>';
        foreach ($result as $menu) {
            $html .= '<option value="' . htmlspecialchars($menu['su_menu_id']) . '">' . htmlspecialchars($menu['sub_mn_nm']) . '</option>';
        }
        return $html;
    }





    public function getSubSubMenu()
    {
        $su_menu_id = $this->request->getVar('str');
        $sql = "SELECT su_su_menu_id, sub_sub_mn_nm, url FROM master.sub_sub_menu WHERE su_menu_id = ? AND display = 'Y'";
        $query = $this->dbo->query($sql, [$su_menu_id]);
        $options = '<option value="">Select</option>';
        foreach ($query->getResultArray() as $row) {
            $options .= '<option value="' . htmlspecialchars($row['su_su_menu_id']) . '">' .
                htmlspecialchars($row['sub_sub_mn_nm']) . ' - ' .
                htmlspecialchars($row['url']) . '</option>';
        }
        echo $options;
    }



    public function RepThirdMenu()
    {
        $mn_id = $this->request->getVar('ddl_sub_sub_menu_5');
        $main = $this->request->getVar('ddl_mn_menu_per');
        $submenu = $this->request->getVar('ddl_sub_menu_5');

        $sql_name1 = "SELECT * FROM master.menu WHERE id IN($main)";
        $rs_name1 = $this->dbo->query($sql_name1)->getResultArray();
        $n1 = !empty($rs_name1) ? implode(', ', array_column($rs_name1, 'menu_nm')) : 'Unknown Menu';

        $sql_name = "SELECT sub_mn_nm FROM master.submenu WHERE su_menu_id IN ($submenu)";
        $rs_name = $this->dbo->query($sql_name)->getResultArray();
        $n = !empty($rs_name) ? implode(', ', array_column($rs_name, 'sub_mn_nm')) : 'Unknown Submenu';

        $sql_name2 = "SELECT sub_sub_mn_nm FROM master.sub_sub_menu WHERE su_su_menu_id IN ($mn_id)";
        $rs_name2 = $this->dbo->query($sql_name2)->getResultArray();
        $n2 = !empty($rs_name2) ? implode(', ', array_column($rs_name2, 'sub_sub_mn_nm')) : 'Unknown Sub-Submenu';

        $sql = "SELECT (sub_sub_us_code) AS ucode, empid, name, section_name, type_name AS uname 
                        FROM master.sub_sub_me_per sp 
                        JOIN master.sub_sub_menu sm ON sp.sub_sub_menu = sm.su_su_menu_id 
                        JOIN master.users ON sp.sub_sub_us_code = users.usercode 
                        JOIN master.usersection ON users.section = usersection.id 
                        JOIN master.usertype ON users.usertype = usertype.id 
                        WHERE sub_sub_menu = $mn_id AND sp.display = 'Y' AND users.display = 'Y' 
                        ORDER BY usertype, empid";
        $rs = $this->dbo->query($sql)->getResultArray();

        $sno = 0;

        echo '<div class="text-right mb-3 mt-5" style="float: inline-end;">
                <button type="button" class="btn btn-secondary" id="print1" onclick="printDiv(\'result\')">Print</button>
                </div>';
        echo '<div class="table-responsive mt-5">';
        echo '<table width="100%" border="1" class="table table-bordered table-sm table-hover table-striped">';
        echo '<tr><td colspan="6" align="center"><font color="blue"><h3 style="color:blue;">' . htmlspecialchars($n1 . ' -> ' . $n . ' -> ' . $n2 . ' menu allotted to the following users') . '</h3></font></td></tr>';
        echo '<tr>';
        echo '<td>S.NO</td>';
        echo '<td>Emp Id</td>';
        echo '<td>Name</td>';
        echo '<td>Section</td>';
        echo '<td>Designation</td>';
        echo '<td>Action</td>';
        echo '</tr>';

        if (empty($rs)) {
            echo '<tr><td colspan="6" align="center">No data available in table</td></tr>';
        } else {
            foreach ($rs as $row) {
                ++$sno;
                $ucode = $row['ucode'];
                $empid = $row['empid'];
                $uname = $row['name'];
                $section_name = $row['section_name'];
                $tname = $row['uname'];

                $sql_check_main_menu = "SELECT display FROM master.mn_me_per WHERE us_code = $ucode AND mn_me_per = $main";
                $rs_main = $this->dbo->query($sql_check_main_menu)->getRowArray();
                if ($rs_main && trim($rs_main['display']) === 'Y') {
                    $sel_check_sub_menu = "SELECT display FROM master.sub_me_per WHERE sub_us_code = $ucode AND mn_me_per = $main AND sub_me_per = $submenu";
                    $rs_sub_menu = $this->dbo->query($sel_check_sub_menu)->getRowArray();

                    if ($rs_sub_menu && trim($rs_sub_menu['display']) === 'Y') {
                        echo '<tr>';
                        echo '<td>' . $sno . '</td>';
                        echo '<td>' . htmlspecialchars($empid) . '</td>';
                        echo '<td>' . htmlspecialchars($uname) . '</td>';
                        echo '<td>' . htmlspecialchars($section_name) . '</td>';
                        echo '<td>' . htmlspecialchars($tname) . '</td>';
                        echo '<td><input type="button" name="revoke" value="Revoke" onclick="revokethis(this.id)" id="' . htmlspecialchars($mn_id . "?" . $ucode) . '"></td>';
                        echo '</tr>';
                    }
                }
            }
        }

        echo '</table>';
        echo '</div>';
    }

    public function userProfile()
    {
        $data['Menu_model'] = $this->Menu_model;
        return view('templates/menu_assign/userProfile',$data);
    }
}
