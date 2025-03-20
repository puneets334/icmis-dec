<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_CaseRemarksVerification extends Model
{
    
    protected $table      = 'case_remarks_verification';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'diary_no', 'cl_date', 'status', 'approved_by', 'approved_on', 'rejection_remark', 'display', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}