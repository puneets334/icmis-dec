<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_CopyCategory extends Model
{
    
    protected $table      = 'copy_category';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'code', 'description', 'charges', 'urgent_fee', 'per_certification_fee', 'from_date', 'to_date', 'per_page', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}