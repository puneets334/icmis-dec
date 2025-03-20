<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class AorClerkTrainee extends Entity
{
    
    protected $table      = 'aor_clerk_trainee';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [  'clerk_id_no', 'name', 'pho_no', 'email_id', 'aor_code', 'aor_name', 'willful_participation', 'ip_address', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}