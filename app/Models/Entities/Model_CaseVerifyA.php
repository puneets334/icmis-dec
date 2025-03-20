<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_CaseVerifyA extends Model
{
    
    protected $table      = 'case_verify_a';
    // protected $primaryKey = '';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'diary_no', 'next_dt', 'm_f', 'board_type', 'ent_dt', 'ucode', 'display', 'id', 'remark_id', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}