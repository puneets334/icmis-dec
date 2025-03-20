<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class Bar extends Entity
{
    
    protected $table      = 'bar';
    protected $primaryKey = 'bar_id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'title', 'name', 'rel', 'fname', 'mname', 'dob', 'paddress', 'pcity', 'caddress', 'ccity', 'pp', 'sex', 'cast', 'phno', 'mobile', 'email', 'enroll_no', 'enroll_date', 'isdead', 'date_of_dead', 'passing_year', 'if_aor', 'state_id', 'bentuser', 'bentdt', 'bupuser', 'bupdt', 'aor_code', 'if_sen', 'sc_from_dt', 'sc_to_date', 'cmis_state_id', 'agency_code', 'if_other', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}