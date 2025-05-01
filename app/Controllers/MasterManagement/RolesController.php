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
        $request = \Config\Services::request();
        $data['usercode'] =  session()->get('login')['usercode'];
        $action = 'Create';
        $addNewBtn = '';
        $roleMid = !empty($request->getPost('menu_id')) ? (int)$request->getPost('menu_id') : 1;
        $data['rHeading'] = !empty($request->getPost('rHeading')) ?  htmlentities(trim($request->getPost('rHeading'))) : '';
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

 

    public function MenuRoleDetails()
    {
        $MenuReportModel = $this->MenuReportModel;
        $rmid = (int)$this->request->getVar('rmid');
       
        $roles = $this->MenuReportModel->getAllRoles();
        $rolename = $roles;
        $menus = [];
        if (!empty($rmid)) {
            $rolename = $this->MenuReportModel->getRoleById($rmid);
            $menus = $this->MenuReportModel->getMenuByRoleId($rmid);

        }
        return view('MasterManagement/role/role_details', ['MenuReportModel' => $MenuReportModel,'roles' => $roles, 'rolename' => $rolename, 'menus' => $menus, 'rmid' => $rmid]);
    }

    public function roleparameter(){
        if($_POST) {

            date_default_timezone_set('Asia/Kolkata');
            $action=htmlentities(trim($_POST['action']));
             
            switch ($action) {
                case 'createRole':
                    $menu_ids=htmlentities(rtrim($_POST['menuIds'],','));
                    $menuArray=explode(',', $menu_ids);
                    $role_desc=htmlentities(trim($_POST['rcaption']));
                    $now=date('Y-m-d H:i:s');    
                   
                    $rCheckRs= is_data_from_table('master.role_master'," role_desc='$role_desc' and display='Y' ",'id','N');
                    if($rCheckRs > 0) {
                        echo json_encode(array('data' =>'0','error'=>'Role already exists.'));
                        exit();
                    }
    
                    $qone= "insert into master.role_master(role_desc,updated_on) values('$role_desc','$now')";
                    $qoneRs=$this->db->query($qone);
                   
                    if($qoneRs ==1) {

                        $rs= is_data_from_table('master.role_master'," role_desc='$role_desc' and display='Y' ",'id','');
                        $role_master_id= $rs['id'];
                        foreach ($menuArray as $mid) {

                            $qtwo= "insert into master.role_menu_mapping(role_master_id,menu_id,updated_on) values('$role_master_id','$mid','$now')";
                            $qtwoRs=$this->db->query($qtwo);                            						
                        }
                        echo json_encode(array('data' =>'success','error'=>'0'));
                    }else {
                            echo json_encode(array('data' =>'0','error'=>'Error in first insert query'));
                        }
                break;
    
                case 'updateRole':
    
                    $roleMid=(int)$_POST['roleMid'];
                    $menu_ids=htmlentities(rtrim($_POST['menuIds'],','));
                    $menuArray=explode(',', $menu_ids);
                    $role_desc=htmlentities(trim($_POST['rcaption']));
                    $now=date('Y-m-d H:i:s');
    
                    $qone= "update master.role_master SET role_desc='$role_desc',updated_on='$now' where id='$roleMid'";
                    $qoneRs=$this->db->query($qone);
                    
                    if($qoneRs) {    
                        $history='insert into master.role_menu_mapping_history SELECT * FROM master.role_menu_mapping where role_master_id=?;';
                        $rs = $this->db->query($history, [$roleMid]);
                        
    
                        $delsql='DELETE FROM master.role_menu_mapping where role_master_id=?;';
                        $rs = $this->db->query($delsql, [$roleMid]);
                        
                        if($rs) :;
                            foreach ($menuArray as $mid) {
                                $qtwo='insert into master.role_menu_mapping(role_master_id,menu_id,updated_on) values(?,?,?);';
                                $qtwoRs = $this->db->query($qtwo, [$roleMid, $mid, $now]);
                                 
                            }
                        endif;
                        echo json_encode(array('data' =>'success','error'=>'0'));
                    }else {
                            echo json_encode(array('data' =>'0','error'=>'Error in first insert query'));
                        }
                break;
    
                case 'roleDelete':
                        $menu_id=(int)$_POST['menu_id'];
                        $query='update master.role_master set display="N" where id=?';
                        $rs = $this->db->query($query, [$menu_id]);
                       
                        if($rs) 
                                echo json_encode(array('data'=>'success', 'error'=>'0'));
                        else 	echo json_encode(array('data' =>'0' , 'error'=>'Server have some problem, try later'));
                break;
    
                case 'roleUpdate':
                        $roleCaption=htmlentities(trim($_POST['rheading']));					
                        $RoleId=(int)$_POST['menu_id'];
                        $menu_ids=htmlentities(rtrim($_POST['menuIds'],','));
                        $menuArray=explode(',', $menu_ids);
                        $now=date('Y-m-d H:i:s');
    
                        $query='Update master.role_master set role_desc=? where id=?';
                        $qrs = $this->db->query($query, [$roleCaption,$RoleId]);
                         
                        if($qrs) {
                            $qdel='delete from master.role_menu_mapping where role_master_id=? and display="Y";';
                            $qdelRs = $this->db->query($qdel, [$RoleId]);                            

                            if($qdelRs) {
                                foreach ($menuArray as $mid) {
                                    $qtwo='insert into master.role_menu_mapping(role_master_id,menu_id,updated_on) values(?,?,?);';
                                    $qtwoRs = $this->db->query($qtwo, [$RoleId, $mid, $now]);
                                    					
                                }
                                echo json_encode(array('data' =>'success','error'=>'0'));
                            }
    
                        }else {
                            echo json_encode(array('data' =>'0','error'=>'Error in first insert query'));
                        }
                break;
    
                case 'UserProfileUpdate':
    
                        if($_POST) {
    
                            $file_name=''; $actionType='';
                            $actionType=@htmlentities(trim($_POST['actionType']));
                            $usercode=(int)$_POST['UiD'];
                            $name=htmlentities(trim($_POST['name']));
                            $usertype=(int)$_POST['usertype'];
                            $udept=(int)$_POST['udept'];
                            $jcode=(int)$_POST['jcode'];
                            $empid=(int)$_POST['empid'];
                            $mobile_no=trim($_POST['mobile_no']); 
                            $is_CourtMaster=trim($_POST['is_CourtMaster']);
                            $email_id=trim($_POST['email_id']);
                            $ip_address=$_POST['ipads']; 
    
                            if($_FILES['upic'] && $_FILES['upic']['error'] ==0) {
                                $errors= array();
                                $file_name = $_FILES['upic']['name'];
                                if(substr_count($file_name, '.') != 1) {
                                     $errors[]="More than one dot (.) not allowed in uploading file";
                                }
                                else {
                                    $file_name=$empid.substr($file_name, strpos($file_name,'.'));
                                    $file_size = $_FILES['upic']['size'];
                                    $file_tmp = $_FILES['upic']['tmp_name'];
                                    $file_type = $_FILES['upic']['type'];
                                    $file_ext=strtolower(end(explode('.',$_FILES['upic']['name'])));				      
                                    $extensions= array("jpeg","jpg","gif");				      
                                    if(in_array($file_ext,$extensions)=== false) $errors[]="extension not allowed, please choose a JPEG or GIF file.";
                                    if($file_size > 2097152) $errors[]='File size must be excately 2 MB';
                                    if(empty($errors)==true) {
                                        $updPath=$_SERVER['DOCUMENT_ROOT']."/userImage/".$file_name;
                                        move_uploaded_file($file_tmp, $updPath);
                                    }
                                }
                            }
                            if(empty($errors)==true) {
                                foreach ($_POST['usection'] as $key => $section) {
                                    if($key==0 && $actionType !='AddUser'){
    
                                        $data = [
                                            'name'           => $name,
                                            'empid'          => $empid,
                                            'usertype'       => $usertype,
                                            'section'        => $section,
                                            'udept'          => $udept,
                                            'jcode'          => $jcode,
                                            'mobile_no'      => $mobile_no,
                                            'is_CourtMaster' => $is_CourtMaster,
                                            'email_id'       => $email_id,
                                            'ip_address'     => $ip_address,
                                            'updt'           => date('Y-m-d H:i:s'),
                                        ];
                                        
                                        if ($file_name !== '') {
                                            $data['uphoto'] = $file_name;
                                        }                                        
                                        $this->db->table('master.users')->where('usercode', $usercode)->update($data);
    
                                        $secMap='update user_sec_map set display="N", updated_on=NOW() where empid=? AND display="Y";';
                                         $this->db->query($secMap, [$empid]);
                                       
                                    }
    
                                    if($key==0 && $actionType =='AddUser'){
                                        $service='E'; $entdt=date('Y-m-d H:i:s'); $updt='';
                                        $upuser=0; $entuser=0;                                       

                                        $data = [
                                            'name'           => $name,
                                            'empid'          => $empid,
                                            'service'        => $service,
                                            'usertype'       => $usertype,
                                            'section'        => $section,
                                            'udept'          => $udept,
                                            'jcode'          => $jcode,
                                            'entdt'          => $entdt,
                                            'entuser'        => $entuser,
                                            'upuser'         => $upuser,
                                            'updt'           => $updt,
                                            'mobile_no'      => $mobile_no,
                                            'email_id'       => $email_id,
                                            'ip_address'     => $ip_address,
                                            'is_CourtMaster' => $is_CourtMaster,
                                            'uphoto'         => $file_name
                                        ];
                                        
                                        $instQ = this->db->table('master.users')->insert($data);
                                        

                                        if($instQ) $errors[]="Error in insert query";
                                    }
    
                                    $inst='insert into master.user_sec_map(empid,usec) values(?,?);';
                                    $instRs = $this->db->query($inst, [$empid,$section]);
                                    
                                    if($instRs) $errors[]="Error in insert new section";
    
    
                                }
                            }
                            if(empty($errors)==true) echo json_encode(array('data'=>'success', $error=>'0'));
                            else echo json_encode(array('data'=>'', 'error'=>$errors));
                        }
    
                break;
    
                case 'empVerify':
                        $empid=(int)$_POST['empid'];
                        $sql='select * from master.users where empid=? and display="Y"';
                        $rs = $this->db->query($sql, [$empid]);                      
                        if($rs->rowCount() > 0) echo json_encode(array('data'=>'found'));
                        else  echo json_encode(array('data'=>''));
    
                break;
            }
        }
    }
}


