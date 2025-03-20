<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_AdvanceAllocated extends Model
{
    
    protected $table      = 'advance_allocated';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['diary_no', 'conn_key', 'next_dt', 'subhead', 'board_type', 'clno', 'brd_slno', 'j1', 'j2', 'j3', 'listorder', 'usercode', 'ent_dt', 'main_supp_flag', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}