<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_ref_pil_category extends Model
{

    protected $table      = 'master.ref_pil_category';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['pil_code', 'pil_category', 'is_deleted', 'adm_updated_by', 'updated_on', 'pil_type', 'create_modify', 'updated_by', 'updated_by_ip'];
    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

}


?>