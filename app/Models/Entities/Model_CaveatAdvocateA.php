<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_CaveatAdvocateA extends Model
{
    
    protected $table      = 'caveat_advocate_a';
    // protected $primaryKey = 'no';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['caveat_no', 'adv_type', 'pet_res', 'pet_res_no', 'advocate_id', 'adv', 'usercode', 'ent_dt', 'display', 'stateadv', 'old_adv', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}