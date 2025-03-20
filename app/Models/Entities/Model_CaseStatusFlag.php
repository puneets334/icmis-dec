<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_CaseStatusFlag extends Model
{
    
    protected $table      = 'case_status_flag';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'flag_name', 'display_flag', 'updated_on', 'always_allowed_users', 'from_date', 'to_date', 'ip', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}