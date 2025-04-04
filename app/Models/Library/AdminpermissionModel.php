<?php
namespace App\Models\Library;
use CodeIgniter\Model;
class AdminpermissionModel extends Model{
    
    private $db_table = "admin";

    public $id;
    public $permission_desc;
    public $level;
    public $perm_id;
    public $status;  
    public $userName;  
    public $passWord;  


    public $permid; 
    public $role_id;      
    
    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
    }



    public function getRequisitionLogin($icmis_user_id,$role_id) {
        $query=" select * from ".$this->db_table."  where  icmis_user_id='$icmis_user_id' and role_id='$role_id' AND status=1  ";
        $stmt = $this->db->query($query);
        return $stmt->getRowArray();        
    }

    public function getRequiLogin_Other($passWord,$role_id) {
        $query=" select * from ".$this->db_table."  where  username='Other' and password='$passWord' and role_id='$role_id' AND status=1 ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }




}