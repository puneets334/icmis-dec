<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class AmicusCuriae extends Entity
{
    
    protected $table      = 'amicus_curiae';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [  'bar_id', 'from_date', 'to_date', 'is_deleted', 'deleted_on', 'deleted_by', 'deleted_by_ip', 'last_assigned_on', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}