<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class CaveatLowerctJudges extends Entity
{
    
    protected $table      = 'caveat_lowerct_judges';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['lowerct_id', 'judge_id', 'lct_display',  'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}