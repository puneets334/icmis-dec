<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_AdvanceSingleJudgeAllocatedLog extends Model
{
    
    protected $table      = 'advance_single_judge_allocated_log';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['diary_no', 'conn_key', 'next_dt', 'from_dt', 'to_dt', 'subhead', 'board_type', 'clno', 'brd_slno', 'listorder', 'main_supp_flag', 'weekly_no', 'weekly_year', 'usercode', 'ent_dt', 'log_sent_on', 'log_sent_by', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}