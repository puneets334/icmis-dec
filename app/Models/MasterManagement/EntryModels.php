<?php

namespace App\Models\MasterManagement;
use CodeIgniter\Model;

class EntryModels extends Model
{


    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
    }


    function get_menu_nm()
    {
        $sql="Select id,menu_nm from master.menu where display='Y'";
        $query = $this->db->query($sql);
        if ($query->getNumRows() > 0) {
            return $query->getResultArray(); 
        }else {
            return false;
        }
    }

 
      

 
        

  }
  
