<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_CaseVerifyRop extends Model
{
    
    protected $table      = 'case_verify_rop';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['diary_no', 'cl_dt', 'm_f', 'board_type', 'ent_dt', 'ucode', 'display', 'remark_id', 'tentative_dt', 'court',  'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}