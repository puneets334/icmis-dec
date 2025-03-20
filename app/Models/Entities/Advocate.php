<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class Advocate extends Entity
{
    
    protected $table      = 'advocate';
    // protected $primaryKey = '';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['diary_no', 'adv_type', 'pet_res', 'pet_res_no', 'advocate_id', 'adv', 'usercode', 'ent_dt', 'display', 'stateadv', 'old_adv', 'ent_by_caveat_advocate', 'remark', 'aor_state', 'pet_res_show_no', 'is_ac', 'writ_adv_remarks', 'ac_direction_given_by', 'ac_remarks', 'inperson_mobile', 'inperson_email', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}