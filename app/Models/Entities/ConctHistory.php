<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class ConctHistory extends Entity
{
    
    protected $table      = 'conct_history';
    // protected $primaryKey = '';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [  'conn_key', 'diary_no', 'list', 'usercode', 'ent_dt', 'conn_type', 'linked_to', 'linking_reason', 'migration', 'chng_by', 'chng_date','create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}