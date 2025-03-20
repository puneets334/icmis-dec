<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class CaseLimitA extends Entity
{
    
    protected $table      = 'case_limit_a';
    // protected $primaryKey = '';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'diary_no', 'limit_days', 'descr', 'case_nature', 'under_section', 'o_s', 'pol', 'o_d', 'f_d', 'c_d_a', 'd_o_d', 'case_lim_display', 'id', 'lowerct_id', 'order_cof', 'd_o_a', 'case_lmt_user', 'case_lmt_ent_dt', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}