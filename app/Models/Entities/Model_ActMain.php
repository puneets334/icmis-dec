<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_ActMain extends Model
{
    
    protected $table      = 'act_main';
    // protected $primaryKey = 'id';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['diary_no', 'act', 'entdt', 'user', 'display', 'updated_from_ip', 'updatedfrommodule',  'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}