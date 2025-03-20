<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class CaseVerifyRopA extends Entity
{
    
    protected $table      = 'case_verify_rop_a';
    // protected $primaryKey = '';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['diary_no', 'cl_dt', 'm_f', 'board_type', 'ent_dt', 'ucode', 'display', 'remark_id', 'tentative_dt', 'court', 'id', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}