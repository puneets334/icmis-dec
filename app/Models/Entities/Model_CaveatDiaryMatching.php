<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_CaveatDiaryMatching extends Model
{
    
    protected $table      = 'caveat_diary_matching';
    // protected $primaryKey = 'no';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['caveat_no', 'diary_no', 'link_dt', 'usercode', 'caveat_diary', 'ent_dt', 'matching_reason', 'display', 'notice_path', 'print_dt', 'print_user_id', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}