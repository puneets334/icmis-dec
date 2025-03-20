<?php

namespace App\Models\MasterManagement;

use CodeIgniter\Model;

class ReportMenuPermissionModel extends Model
{
    protected $table = 'master.menu';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'menu_nm'];

    public function getAllMnMenuById()
    { 
        return $this->where('display','Y')->orderBy('id','ASC')->findAll();
    }
 

    public function getMenuUserReportById($usrid)
    {
       
       $sql =  "select role_desc,id from master.role_master where id IN (select role_master_id from master.user_role_master_mapping where display='Y' AND usercode=$usrid);";
       $query = $this->db->query($sql);
       return $query->getResultArray();

    }


     
 
    public function getUserByMenuID($mn_id)
    {
       
        $sql="select us_code,empid,name, section_name,type_name uname 
             from master.mn_me_per 
             join master.users on mn_me_per.us_code = users.usercode 
             join master.usersection on users.section=usersection.id
             join master.usertype on users.usertype=usertype.id  
             where mn_me_per = $mn_id 
             and users.display='Y' 
             and mn_me_per.display='Y' 
             order by usertype,empid";

       $query = $this->db->query($sql);
       return $query->getResultArray();

    }

            



    public function getRevokeMenuID($arr,$arr2)
    {
       
        $sql="select concat(sub_me_per) as res  from master.sub_me_per where mn_me_per=$arr and sub_us_code=$arr2 and display='Y'";
       $query = $this->db->query($sql);
       return $query->getResultArray();

    }


    public function getUpdateRevoke($subID,$arr)
    {
       
        
        $sql_main_revoke="update master.mn_me_per set display='N' where us_code=$arr[1] and mn_me_per=$arr[0]";
        $sql_revoke="update master.sub_me_per set display='N' where sub_us_code=$arr[1] and mn_me_per=$arr[0]";
        $sql_revoke_third_menu="update master.sub_sub_me_per set display='N' where sub_sub_menu in ($subID) and sub_sub_us_code=$arr[1]";
        $this->db->transStart(); 
        try {
            $this->db->query($sql_main_revoke);
            $affectedRows1 = $this->db->affectedRows(); 
            $this->db->query($sql_revoke);
            $affectedRows2 = $this->db->affectedRows();  
            $this->db->query($sql_revoke_third_menu);
            $affectedRows3 = $this->db->affectedRows(); 
            $this->db->transComplete(); 
            return ($affectedRows1 > 0 || $affectedRows2 > 0 || $affectedRows3 > 0);
        } catch (Exception $e) {
            $this->db->rollBack();
            return false; 
        }

    }

   
    

    public function getsubmenuMn_id($mn_id)
    {

      
      
        $sql="select * from master.menu where id in($mn_id) order By id";
        $query  = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;

    }

    

    public function getsubmenu($sm_id)
    {
       
        $sql="select sub_mn_nm from master.submenu where su_menu_id in ($sm_id) order By id";
        $query  = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;

    }
       


    public function getsubmenuMnSm($mn_id, $sm_id)
    {
       
        $sql="select sub_us_code ucode ,empid,name,section_name,type_name uname
        from master.sub_me_per sp
        join master.users on sp.sub_us_code =users.usercode
        join master.usersection on users.section=usersection.id
        join master.usertype on users.usertype=usertype.id where users.display='Y' and mn_me_per=$mn_id and sub_me_per=$sm_id and sp.display='Y'  and users.display='Y' order by usertype,empid";
        // pr($sql);
        $query  = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;

    }

 

    public function MnMePer($ucode, $mn_id)
    {
     
        $sql="select display from master.mn_me_per where us_code=$ucode and mn_me_per=$mn_id";
        $query  = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;

    }
   

}  


    
    

     
    

 
  
