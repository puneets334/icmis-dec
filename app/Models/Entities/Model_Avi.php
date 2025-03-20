<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_Avi extends Model
{
    
    protected $table      = 'avi';
    // protected $primaryKey = '';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'diary_no', 'fil_dt', 'c_status', 'a', 'b', 'ss', 'ss2', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}