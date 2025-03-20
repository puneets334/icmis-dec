<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class Authority extends Entity
{
    
    protected $table      = 'authority';
    protected $primaryKey = 'authcode';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'authdesc', 'usercode', 'ent_dt', 'display', 'authtype', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}