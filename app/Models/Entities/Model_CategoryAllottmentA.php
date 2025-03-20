<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_CategoryAllottmentA extends Model
{
    
    protected $table      = 'category_allottment_a';
    // protected $primaryKey = '';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['cat_allot_id', 'stage_code', 'stage_nature', 'ros_id', 'priority', 'display', 'case_type', 'submaster_id', 'b_n', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}