<?php
namespace App\Models;
use CodeIgniter\Model;
class MasterModel extends Model{
    protected $db;

    public function __construct(){
        parent::__construct();
        $db = \Config\Database::connect();
        $this->db = db_connect();
    }



    public function get_table_data($table){
        $builder = $this->db->table($table);
        $builder->select("*");
        $query =$builder->get();
        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{return false;}
    }

}