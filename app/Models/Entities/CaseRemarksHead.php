<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class CaseRemarksHead extends Entity
{
    
    protected $table      = 'case_remarks_head';
    protected $primaryKey = 'sno';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [  'head', 'pending_text', 'side', 'cis_disp_code', 'cat_head_id', 'rgo_color', 'compliance_limit_in_day', 'fixed_date', 'stage', 'priority', 'display', 'national_short_name', 'national_code', 'national_remark_type', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}