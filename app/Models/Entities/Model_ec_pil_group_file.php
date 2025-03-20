<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_ec_pil_group_file extends Model
{

    protected $table      = 'ec_pil_group_file';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['group_file_number', 'is_deleted', 'adm_updated_by', 'updated_on', 'create_modify', 'updated_by', 'updated_by_ip'];
    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

}


?>