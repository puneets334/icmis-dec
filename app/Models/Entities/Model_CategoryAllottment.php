<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_CategoryAllottment extends Model
{
    
    protected $table      = 'category_allottment';
    protected $primaryKey = 'cat_allot_id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'stage_code', 'stage_nature', 'ros_id', 'priority', 'display', 'case_type', 'submaster_id', 'b_n', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}