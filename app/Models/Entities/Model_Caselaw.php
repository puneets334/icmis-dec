<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_Caselaw extends Model
{
    
    protected $table      = 'caselaw';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['casetype', 'lawcode', 'nature', 'law', 'display', 'case_code', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}