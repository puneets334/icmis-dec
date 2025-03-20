<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_usertype extends Model
{

    protected $table      = 'master.usertype';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['type_name', 'disp_flag', 'mgmt_flag', 'display','entuser', 'entdt', 'upuser', 'updt', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];
    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

}


?>






