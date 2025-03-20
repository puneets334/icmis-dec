<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_ClTextSave extends Model
{
    
    protected $table      = 'cl_text_save';
    // protected $primaryKey = '';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'clp_id', 'cl_content', 'display', 'userid', 'ent_dt', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}