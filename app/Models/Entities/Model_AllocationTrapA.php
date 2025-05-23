<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_AllocationTrapA extends Model
{
    
    protected $table      = 'allocation_trap_a';
    // protected $primaryKey = '';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['id', 'list_dt', 'is_roster_selected', 'roster_id', 'fresh_limit', 'old_limit', 'clno', 'main_supp_flag', 'short_cat_flag', 'advance_flag', 'usercode', 'ent_dt', 'listorder', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}