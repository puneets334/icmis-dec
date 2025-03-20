<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class AdvanceClPrinted extends Entity
{
    
    protected $table      = 'advance_cl_printed';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['next_dt', 'board_type', 'part', 'main_supp', 'from_brd_no', 'to_brd_no', 'j1', 'j2', 'j3', 'usercode', 'ent_time', 'display', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}