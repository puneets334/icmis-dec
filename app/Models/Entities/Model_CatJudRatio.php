<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_CatJudRatio extends Model
{
    
    protected $table      = 'cat_jud_ratio';
    // protected $primaryKey = '';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['cat_id', 'cat_name', 'judge', 'next_dt', 'bail_top', 'orders', 'fresh', 'fresh_no_notice', 'an_fd', 'cnt', 'ratio_cnt', 'ent_dt', 'usercode', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}