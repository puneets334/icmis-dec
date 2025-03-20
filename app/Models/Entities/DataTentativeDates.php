<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class DataTentativeDates extends Entity
{
    
    protected $table      = 'data_tentative_dates';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'judge_id', 'next_dt', 'non_fix_date_count', 'fix_date_count', 'is_nmd', 'entry_date', 'diary_no', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}