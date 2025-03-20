<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class AdvocateRequisitionRequest extends Entity
{
    
    protected $table      = 'advocate_requisition_request';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['req_id', 'file_type', 'file_text', 'file_name', 'created_on', 'created_by', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}