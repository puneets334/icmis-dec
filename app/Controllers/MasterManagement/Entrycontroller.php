<?php

namespace App\Controllers\MasterManagement;
use App\Controllers\BaseController;
use App\Models\MasterManagement\EntryModels;
use CodeIgniter\Controller;
use CodeIgniter\Model;
use App\Models\MasterManagement\IPModel;

 
class Entrycontroller extends BaseController
{

public $EntryModels;
    function __construct()
    {
        ini_set('memory_limit','51200M'); 
        $this->EntryModels = new EntryModels();
        //error_reporting(0);
    }

   
    public function index()
    {
            $data['menus'] = $this->EntryModels->get_menu_nm();
            // pr($data);
            return view('MasterManagement/entry/entryinsert',$data);
    }

    public function save_main_mn()
    {       
        $sql="insert Into master.menu (menu_nm) values ('$_REQUEST[txt_mn_menu]')";
        $sql_s= $this->db->query($sql);
        if(!$sql_s)
        {
            die("Error: Please contact to Computer Cell!!");
        }
        else 
        {
            echo "Main Menu Inserted Successfully";
        }
    }  
    
    
    public function save_sub_mn()
    {
        if($_REQUEST['ddl_order']=='')
        { 
            $sq_up_s = is_data_from_table('master.submenu', " id='$_REQUEST[ddl_mn_menu]' and display='Y' ", 'max(o_d) as o_d', '');
            
            $max_one = $sq_up_s['o_d'];
            $_REQUEST['ddl_order']=$max_one+1;            
        }
        else 
        {         
            $row = is_data_from_table('master.submenu', " o_d>='$_REQUEST[ddl_order]' and id='$_REQUEST[ddl_mn_menu]' and display='Y' ", 'su_menu_id,o_d ', '');
            if(!empty($row))
            {            
                    $ord_s=$row['o_d']+1;
                    $upd_sql=  $this->db->query("Update submenu set o_d='$ord_s' where su_menu_id='$row[su_menu_id]'");                
            }
        }
         
            $sql="insert into submenu (id,sub_mn_nm,o_d) values ('$_REQUEST[ddl_mn_menu]','$_REQUEST[txt_sub_menu]', '$_REQUEST[ddl_order]')";
            $sql_s= $this->db->query($sql);
            if(!$sql_s)
                {
                    die("Error: Please contact to Computer Cell!!");
                }
            else 
                {
                    echo "Main Menu Inserted Successfully";
                }
    }


    public function get_sub_menus()
    {         
        $sql_s = is_data_from_table('master.submenu', " id='$_REQUEST[str]' and display='Y' order by o_d ", 'su_menu_id,sub_mn_nm ', 'A');
        
        $option = '<option value="">Select</option>';
        if(!empty($sql_s))
        {
            foreach($sql_s as $row)
                {           
                    $option .= '<option value="'.$row['su_menu_id'].'">'. $row['sub_mn_nm'].'</option>';
            }
        }
        return $option;
    }

    public function save_sub_sub_mn()
    {
        $sql="Insert Into master.sub_sub_menu (su_menu_id,sub_sub_mn_nm,url,display) values ('$_REQUEST[ddl_sub_menu_3]', '$_REQUEST[txt_sub_menu]','$_REQUEST[txt_url]','Y')";
        $sql_s= $this->db->query($sql);
        if(!$sql_s)
        {
            die("Error: Please contact to Computer Cell!!");
        }
        else 
        {
            echo "Third Menu Inserted Successfully";
        }
    }

    public function save_mn_menu_per()
    {
        $ex_exp=  explode(',', $_REQUEST['txt_mn_per']);
        $res = '';
        for ($i = 0; $i < count($ex_exp); $i++) {

            //$sel_s = "select count(id) from mn_me_per where us_code='$ex_exp[$i]' and mn_me_per='$_REQUEST[ddl_mn_menu_per]' and display='Y'";
            //$sel_s_s =  mysql_query($sel_s);
            //$res_sel =  mysql_result($sel_s_s, 0);

            $sel_s_s = is_data_from_table('master.mn_me_per', " us_code='$ex_exp[$i]' and mn_me_per='$_REQUEST[ddl_mn_menu_per]' and display='Y' ", 'count(id) as total', '');
            $res_sel = $sel_s_s['total']; 
            if ($res_sel <= 0) {
                $sql = "Insert Into mn_me_per (us_code,mn_me_per) values ('$ex_exp[$i]','$_REQUEST[ddl_mn_menu_per]')";
                $sql_s = $this->db->query($sql);
                if (!$sql_s) {
                    die("Error: Please contact to Computer Cell!!");
                } else {
                    $res .= "Main menu alloted to users " . $ex_exp[$i] . '<br/>';
                }
            } else {
                $res .= "Main menu already alloted to users " . $ex_exp[$i] . '<br/>';
            }


            //$sub_per = "Select count(id) from sub_me_per where sub_us_code='$ex_exp[$i]' and mn_me_per='$_REQUEST[ddl_mn_menu_per]' and sub_me_per='$_REQUEST[ddl_sub_menu_5]' and display='Y'";
            //$sub_per_s =  mysql_query($sub_per);
            //$res_sub_per_s =  mysql_result($sub_per_s, 0);

            
            $sub_per_s = is_data_from_table('master.sub_me_per', " sub_us_code='$ex_exp[$i]' and mn_me_per='$_REQUEST[ddl_mn_menu_per]' and sub_me_per='$_REQUEST[ddl_sub_menu_5]' and display='Y' ", 'count(id) as total', '');
            $res_sub_per_s = $sub_per_s['total'];
            if ($res_sub_per_s <= 0) {
                $sql = "Insert Into sub_me_per ( sub_us_code, mn_me_per,sub_me_per) values ('$ex_exp[$i]','$_REQUEST[ddl_mn_menu_per]','$_REQUEST[ddl_sub_menu_5]')";
                $sql_s = $this->db->query($sql);
                if (!$sql_s) {
                    die("Error: Please contact to Computer Cell!!");
                } else {
                    $res .= "Second menu alloted to users " . $ex_exp[$i] . '<br/>';
                }
            } else {
                $res .= "Second menu already alloted to users " . $ex_exp[$i] . '<br/>';
            }


            //$sec_mn = "select id from sub_me_per where mn_me_per ='$_REQUEST[ddl_mn_menu_per]' and sub_me_per='$_REQUEST[ddl_sub_menu_5]' and sub_us_code='$ex_exp[$i]' and display='Y'";

            //$sec_mn_s =  mysql_query($sec_mn);
            //$res_sec_mn_s =  mysql_result($sec_mn_s, 0);

            $sec_mn_s = is_data_from_table('master.sub_me_per', " mn_me_per ='$_REQUEST[ddl_mn_menu_per]' and sub_me_per='$_REQUEST[ddl_sub_menu_5]' and sub_us_code='$ex_exp[$i]' and display='Y' ", 'id', '');
            $res_sec_mn_s = $sec_mn_s['id'];

            //$sub_per = "Select count(id) from sub_sub_me_per where  	sub_sub_us_code='$ex_exp[$i]' and sub_me_per_id='$res_sec_mn_s' and  	sub_sub_menu='$_REQUEST[ddl_sub_sub_menu_5]' and display='Y'";
            //$sub_per_s =  mysql_query($sub_per);
            //$res_sub_per_s =  mysql_result($sub_per_s, 0);
            $sub_per_s = is_data_from_table('master.sub_sub_me_per', " sub_sub_us_code='$ex_exp[$i]' and sub_me_per_id='$res_sec_mn_s' and  	sub_sub_menu='$_REQUEST[ddl_sub_sub_menu_5]' and display='Y' ", 'count(id) as total', '');
            $res_sub_per_s = $sub_per_s['total'];
            if ($res_sub_per_s <= 0) {
                $sql = "Insert Into sub_sub_me_per ( sub_sub_us_code, sub_me_per_id,sub_sub_menu) values ('$ex_exp[$i]','$res_sec_mn_s','$_REQUEST[ddl_sub_sub_menu_5]')";
                $sql_s = $this->db->query($sql);
                if (!$sql_s) {
                    die("Error: Please contact to Computer Cell!!");
                } else {
                    $res .= "Third menu alloted to users " . $ex_exp[$i] . '<br/>';
                }
            } else {
                $res .= "Third menu already alloted to users " . $ex_exp[$i] . '<br/>';
            }
        }

        echo $res;
        die;
    }

    public function get_sub_sub_menus()
    {
        
        $sql_s = is_data_from_table('master.sub_sub_menu', " su_menu_id='$_REQUEST[str]' and display='Y' ", 'su_su_menu_id,sub_sub_mn_nm,url ', 'A');

        $option = '<option value="">Select</option>';
        if(!empty($sql_s))
        {
            foreach($sql_s as $row)
                {           
                    $option .= '<option value="'.$row['su_su_menu_id'].'">'. $row['sub_sub_mn_nm'].'-'.$row['url'].'</option>';
            }
        }
        return $option;
    }

 


    
}
