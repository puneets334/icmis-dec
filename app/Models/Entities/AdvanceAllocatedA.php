<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class AdvanceAllocatedA extends Entity
{
    
    protected $table      = 'advance_allocated_a';
    // protected $primaryKey = '';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id', 'diary_no', 'conn_key', 'next_dt', 'subhead', 'board_type', 'clno', 'brd_slno', 'j1', 'j2', 'j3', 'listorder', 'usercode', 'ent_dt', 'main_supp_flag', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}