<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_CaseInfo extends Model
{
    
    protected $table      = 'case_info';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'diary_no', 'message', 'insert_time', 'usercode', 'userip', 'display', 'deleted_on', 'deleted_by', 'deleted_user_ip', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}