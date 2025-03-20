<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_lc_casetype extends Model
{

    protected $table      = 'master.lc_casetype';
    protected $primaryKey = 'lccasecode';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['lccasecode', 'lccasename', 'corttyp', 'display', 'skey', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


}