<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_CaveatAdv extends Model
{
    
    protected $table      = 'caveat_adv';
    // protected $primaryKey = 'no';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['cav_no', 'cav_yr', 'adv_en', 'adv_yr', 'adv_name', 'usercode', 'ent_dt', 'display',  'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}