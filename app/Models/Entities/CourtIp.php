<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class CourtIp extends Entity
{
    
    protected $table      = 'court_ip';
    protected $primaryKey = 'sno';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'court_no', 'ip_address', 'display', 'entered_by', 'entered_on', 'entered_ip', 'deleted_by', 'deleted_ip', 'deleted_on', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}