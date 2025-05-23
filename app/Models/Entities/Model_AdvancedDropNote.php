<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_AdvancedDropNote extends Model
{
    
    protected $table      = 'advanced_drop_note';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [ 'cl_date', 'clno', 'diary_no', 'roster_id', 'nrs', 'usercode', 'ent_dt', 'display', 'mf', 'update_time', 'update_user', 'so_user', 'so_time', 'part', 'board_type', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}