<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_escr_users extends Model
{

    protected $table      = 'master.escr_users';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['id', 'usercode', 'role', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];


    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

}

?>