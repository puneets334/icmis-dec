<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_CaseDistributionTrap extends Model
{
    
    protected $table      = 'case_distribution_trap';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [  'diary_no_list', 'from_da', 'to_da', 'transaction_date', 'remarks', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}