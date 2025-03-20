<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_CaseDefect extends Model
{
    
    protected $table      = 'case_defect';
    // protected $primaryKey = '';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'diary_no', 'save_dt', 'usercode', 'org_id', 'rm_dt', 'display', 'remarks', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}