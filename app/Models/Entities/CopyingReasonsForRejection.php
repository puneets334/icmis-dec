<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class CopyingReasonsForRejection extends Entity
{
    
    protected $table      = 'copying_reasons_for_rejection';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'reasons', 'user_id', 'entry_time', 'is_active', 'ip_address', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}