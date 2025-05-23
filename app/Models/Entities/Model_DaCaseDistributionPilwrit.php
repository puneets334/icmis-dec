<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_DaCaseDistributionPilwrit extends Model
{
    
    protected $table      = 'da_case_distribution_pilwrit';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'case_type', 'case_from', 'case_f_yr', 'case_to', 'case_t_yr', 'state', 'subcat0', 'subcat1', 'subcat2', 'dacode', 'entdt', 'entuser', 'display', 'type', 'upuser', 'updt', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}