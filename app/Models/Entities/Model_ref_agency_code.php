<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_ref_agency_code extends Model
{

    protected $table      = 'master.ref_agency_code';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['id', 'agency_name', 'adm_updated_by', 'updated_on', 'state_id', 'agency_or_court', 'short_agency_name', 'is_deleted', 'is_main', 'head_post', 'address',
                                'ref_city_id', 'cmis_state_id', 'district_no', 'main_branch', 'ent_ip_address', 'create_modify', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


}