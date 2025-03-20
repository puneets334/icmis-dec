<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_ActMaster extends Model
{
    
    protected $table      = 'act_master';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['act_name', 'act_name_h', 'year', 'actno', 'state_id', 'display', 'old_id', 'old_act_code', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}