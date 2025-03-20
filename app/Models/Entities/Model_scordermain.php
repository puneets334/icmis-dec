<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_scordermain extends Model
{
    protected $table      = 'scordermain';
    // protected $primaryKey = 'id';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['casetype', 'caseno', 'caseyr', 'petname', 'resname', 'juddate', 'filename', 'number', 'jud1', 'jud2', 'jud3', 'jud4', 'jud5', 'reportable', 'id', 'diary_number', 'diary_year', 'typecode', 'cis_typecode', 'dn', 'dn_zero', 'id_dn', 'order_type', 'usercode', 'ent_dt', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];
    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


}


?>