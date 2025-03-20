<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class CoramA extends Entity
{
    
    protected $table      = 'coram_a';
    // protected $primaryKey = '';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'diary_no', 'board_type', 'jud', 'res_id', 'from_dt', 'to_dt', 'usercode', 'ent_dt', 'display', 'del_reason', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}