<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_ec_postal_dispatch_connected_letters_history extends Model
{

    protected $table      = 'ec_postal_dispatch_connected_letters_history';
    //protected $primaryKey = 'id';

    //protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['id', 'ec_postal_dispatch_id', 'ec_postal_dispatch_id_main', 'usercode', 'updated_on', 'is_deleted', 'create_modify', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


}