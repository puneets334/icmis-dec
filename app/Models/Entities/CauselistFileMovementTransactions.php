<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class CauselistFileMovementTransactions extends Entity
{
    
    protected $table      = 'causelist_file_movement_transactions';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'causelist_file_movement_id', 'ref_file_movement_status_id', 'attendant_usercode', 'remarks', 'usercode', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}