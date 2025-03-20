<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class CaveatAdvocateOrignal extends Entity
{
    
    protected $table      = 'caveat_advocate_orignal';
    // protected $primaryKey = 'no';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['caveat_no', 'adv_type', 'pet_res', 'pet_res_no', 'advocate_id', 'adv', 'usercode', 'ent_dt', 'display', 'stateadv', 'old_adv'];

    protected $useTimestamps = true;
    // protected $createdField  = 'create_modify';
    // protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}