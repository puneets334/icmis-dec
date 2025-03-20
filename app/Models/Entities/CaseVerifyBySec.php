<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class CaseVerifyBySec extends Entity
{
    
    protected $table      = 'case_verify_by_sec';
    // protected $primaryKey = '';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'diary_no', 'next_dt', 'm_f', 'board_type', 'display', 'bo_ent_dt', 'bo_ucode', 'ar_ent_dt', 'ar_ucode', 'dy_ent_dt', 'dy_ucode', 'adr_ent_dt', 'adr_ucode', 'remark', 'remark_ar', 'remark_dy', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}