<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class CauselistFileMovement extends Entity
{
    
    protected $table      = 'causelist_file_movement';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'diary_no', 'next_dt', 'roster_id', 'dacode', 'cm_nsh_usercode', 'ref_file_movement_status_id', 'updated_on', 'usercode', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}