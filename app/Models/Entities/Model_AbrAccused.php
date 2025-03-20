<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_AbrAccused extends Model
{
    
    protected $table      = 'abr_accused';
    // protected $primaryKey = 'no';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['diary_no', 'ord_dt', 'p_r', 'p_r_side', 'acc_ent_time', 'allot_to',  'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}