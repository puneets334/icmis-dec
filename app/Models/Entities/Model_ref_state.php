<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_ref_state extends Model
{

    protected $table      = 'master.ref_state';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['state_name', 'adm_updated_by', 'updated_on', 'short_name', 'is_deleted', 'state_code', 'barcouncil_emailid', 'create_modify', 'updated_by','updated_by_ip'];
    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

}


?>