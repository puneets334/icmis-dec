<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_ChangeFilDt extends Model
{
    
    protected $table      = 'change_fil_dt';
    // protected $primaryKey = 'no';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['diary_no', 'fil_no', 'fil_dt', 'order_date', 'c_status', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}