<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class CaveatDiaryMatchingNew extends Entity
{
    
    protected $table      = 'caveat_diary_matching_new';
    // protected $primaryKey = 'no';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['caveat_no', 'diary_no', 'link_dt', 'usercode', 'c_d', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}