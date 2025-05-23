<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_tempo extends Model
{

    protected $table      = 'tempo';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['dn', 'dy', 'ct', 'cn', 'cy', 'dated', 'jm', 'jt', 'diary_no', 'usercode', 'ent_dt', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];
    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

}


?>