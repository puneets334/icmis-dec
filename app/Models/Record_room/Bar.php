<?php

namespace App\Models\Record_room;

use CodeIgniter\Model;

class Bar extends Model
{
    protected $table = '"master"."bar"'; 
    protected $allowedFields = [ 
        'title', 'name', 'rel', 'fname', 'mname', 'dob', 'paddress', 'pcity', 'caddress', 'ccity', 'pp', 'sex', 'cast', 'phno', 'mobile', 'email', 
        'enroll_no', 'enroll_date', 'isdead', 'date_of_dead', 'passing_year', 'if_aor', 'state_id', 'bentuser', 'bentdt', 'bupuser', 'bupdt', 'aor_code', 
        'if_sen', 'sc_from_dt', 'sc_to_date', 'cmis_state_id', 'agency_code', 'if_other', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'
    ];
 

    public function getAOR()
    {
        return $this->select('aor_code, title || \' \' || name AS adv_name')  
            ->where('isdead', 'N')
            ->where('if_aor', 'Y')
            ->where('if_sen', 'N')
            ->orderBy('aor_code')  
            ->findAll();
    }

    public function GetAorCode($aor)
    {
        $builder = $this->db->table($this->table);
        $builder->select('bar_id AS bid, CONCAT(title, \' \', name) AS adv_name');
        $builder->where('aor_code', $aor);
        
        $query = $builder->get();
        
        return $query->getResultArray();  
    }


    public function GetAorData($aor)
    {
        return $this->db->table($this->table)
            ->select('email, mobile, CONCAT(title, \' \', name) AS name')
            ->where('aor_code', $aor)
            ->get()
            ->getResultArray();
    }



}
