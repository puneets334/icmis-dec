<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_police extends Model
{

    protected $table      = 'master.police';
    protected $primaryKey = 'policestncd';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['policestncd', 'policestndesc', 'display', 'cmis_state_id', 'cmis_district_id', 'ent_user', 'ent_time', 'ent_ip_address', 'create_modify', 'updated_on',
                                'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


}