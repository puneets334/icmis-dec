<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_ClFreezed extends Model
{
    
    protected $table      = 'cl_freezed';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['next_dt', 'm_f', 'part', 'board_type', 'freezed_by', 'freezed_on', 'freezed_by_ip', 'display', 'unfreezed_by', 'unfreezed_on', 'unfreezed_by_ip', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}