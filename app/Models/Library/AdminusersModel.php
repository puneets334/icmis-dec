<?php
namespace App\Models\Library;
use CodeIgniter\Model;
class AdminusersModel extends Model{
    
    private $db_table = "admin";

    // Columns
    public $id;
    public $FullName;
    public $AdminEmail;
    public $UserName;
    public $Password;
    public $updationDate;
    public $user_type;
    public $role_id;
    public $phone_number;
    public $alternative_phone_no;
    public $created_on;  
    public $status;    

    public function __construct(){
        parent::__construct();
        $this->db = db_connect();         
    }

    // GET ALL
    public function getAdminUsers(){
        $sqlQuery = "SELECT * FROM ".$this->db_table."  ORDER BY  id DESC";
       $stmt = $this->db->query($sqlQuery);
       //$stmt->execute();
       return $stmt->getResultArray();
   }


  
   public function UniqueNameCheck($username){
       $sqlQuery = "SELECT count(*) as cntUser FROM ".$this->db_table."  Where username=:username ";            
       $stmt = $this->db->query($sqlQuery);
      // $stmt->bindParam('username', $this->username);
       //$stmt->execute();
       return $count = $stmt->getNumRows();
   }



   public function insertData()
   {
       $created_on=date("Y-m-d H:i:s");
       $query = "INSERT INTO  " . $this->db_table . " SET FullName='$this->FullName',AdminEmail='$this->AdminEmail',UserName='$this->UserName',Password='$this->Password',role_id='$this->role_id', user_type='$this->user_type',created_on='$created_on',phone_number='$this->phone_number',alternative_phone_no='$this->alternative_phone_no'";
       $stmt = $this->db->query($query);

       $stmt->execute();
       return $stmt;
   }
   public function updatea()
   {
       $query = "update   " . $this->db_table . " SET "
               . "FullName='$this->FullName',"
               . "AdminEmail='$this->AdminEmail',"
               . "role_id='$this->role_id',"
               . "phone_number='$this->phone_number',"
               . "alternative_phone_no='$this->alternative_phone_no',"
               . " user_type='$this->user_type' where id='$this->id' ";
       $stmt = $this->db->query($query);
       $stmt->execute();
       return $stmt;
   }
   public function updatePass(){
       $query = "update   " . $this->db_table . " SET "
               . " Password='$this->Password' where id='$this->id' ";
       $stmt = $this->db->query($query);
       $stmt->execute();
       return $stmt;
   }
   
   public function existingUsername($username){
        $query = "SELECT  *  FROM " . $this->db_table . " WHERE username = '$username' "; 
       $stmt = $this->db->query($query);
       //$stmt->execute();
       return $stmt->getRowArray();
   }
   public function deletea(){
       $query = "delete from " . $this->db_table . "  where id='$this->id'";
       $stmt = $this->db->query($query);
       $stmt->execute();
       return $stmt;
   }
   public function getDataForEdit() {
       $query = "select * from " . $this->db_table . "  where id='$this->id'";
       $stmt = $this->db->query($query);
       //$stmt->execute();
       return $stmt->getRowArray();
   }
   public function userStatus() {
       $query = "update   " . $this->db_table . " SET "
               . "status='$this->status' where id='$this->id' ";
       $stmt = $this->db->query($query);
       //$stmt->execute();
       return $stmt->getRowArray();
   }





}