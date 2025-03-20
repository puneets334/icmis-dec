<?php

namespace App\Controllers\MasterManagement;

use App\Controllers\BaseController;
use App\Models\Entities\Model_menu;
use App\Models\Menu_model;
use CodeIgniter\Controller;
use App\Models\MasterManagement\MenuReportModel;

use CodeIgniter\Model;


class RolesController extends BaseController
{
    public $MenuReportModel;
    protected $db;


    function __construct()
    {
        ini_set('memory_limit', '51200M');
        $this->MenuReportModel = new MenuReportModel();
        $this->db = db_connect();
    }

    public function index()
    {

        $data['usercode'] =  session()->get('login')['usercode'];
        $action = 'Create';
        $addNewBtn = '';
        $roleMid = 1;
        $data['rHeading'] = '';
        $data['model'] = $this->MenuReportModel;

        // $menusTreeView = $this->MenuReportModel->fetchSubMenus($roleMid);
         
         $data['rolelist'] = $this->MenuReportModel->RoleList();
       

        $data['data'] = [
            // 'model' => $model,
            'roleMid' => $roleMid,
        ];
        $data['rHeading'] = '';
        return view('MasterManagement/role/addroles', $data);
    }



    // public function fetchSubMenus($menu_id, $length, $roleMid)
    // {
    //     $subMenusQuery = $this->db->query("
    //         SELECT menu_nm, SUBSTR(menu_id, 1, ?) AS sml1_id, url, 
    //                (SELECT COALESCE(string_agg(b.menu_id, ','), '0') 
    //                 FROM master.role_menu_mapping b 
    //                 WHERE LENGTH(b.menu_id) = ? AND b.role_master_id = ?) AS mid 
    //         FROM master.menu 
    //         WHERE SUBSTR(menu_id, 5) = '00000000' 
    //               AND SUBSTR(menu_id, 1, 2) = ? 
    //               AND SUBSTR(menu_id, 3, 2) <> '00' 
    //               AND display = 'Y' AND menu_id IS NOT NULL 
    //         ORDER BY priority, SUBSTR(menu_id, 1, ?)", 
    //         // [$length, $length, $roleMid, $menu_id, $length]);
    //         [$roleMid, $menu_id]);

    //     $subMenus = $subMenusQuery->getResultArray();

    //     foreach ($subMenus as &$subMenu) {
    //         $subMenu['subMenus'] = $this->fetchSubMenus($subMenu['sml1_id'], 4, $roleMid);
    //     }

    //     return $subMenus;
    // }




    public function MenuRoleDetails()
    {
        $rmid = (int) $this->request->getVar('rmid');
        // dd($rmid);
        $roles = $this->MenuReportModel->getAllRoles();
        $rolename = null;
        $menus = [];
        if (!empty($rmid)) {
            $rolename = $this->MenuReportModel->getRoleById($rmid);
            $menus = $this->MenuReportModel->getMenuByRoleId($rmid);
            // pr($role);

        }
        return view('MasterManagement/role/role_details', ['roles' => $roles, 'rolename' => $rolename, 'menus' => $menus, 'rmid' => $rmid]);
    }

    public function roleparameter(){
        pr(97);
    }
}


