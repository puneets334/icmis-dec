<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class CopyingRole extends Entity
{
    
    protected $table      = 'copying_role';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'applicant_type_id', 'application_type_id', 'role_assign_by', 'role_assign_to', 'from_date', 'to_date', 'status', 'ip_address', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}