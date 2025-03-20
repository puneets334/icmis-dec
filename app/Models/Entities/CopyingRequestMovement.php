<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class CopyingRequestMovement extends Entity
{
    
    protected $table      = 'copying_request_movement';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'copying_request_verify_documents_id', 'from_section', 'from_section_sent_by', 'from_section_sent_on', 'to_section', 'remark', 'display', 'deleted_on', 'deleted_by', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}