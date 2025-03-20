<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class AllRegNo extends Entity
{
    
    protected $table      = 'all_reg_no';
    // protected $primaryKey = '';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['diary_no', 'active_fil_no', 'ct', 'from_no', 'to_no', 'active_reg_year', 'no', 'active_fil_dt', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}