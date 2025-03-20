<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_state extends Model
{

    protected $table      = 'master.state';
    protected $primaryKey = 'id_no';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['state_code', 'district_code', 'sub_dist_code', 'village_code', 'name', 'id_no', 'display', 'dj_email_id', 'sp_email', 'cltor_emil', 'region', 'plc_grade', 'sci_state_id', 'ref_code_id', 'pincode', 'ent_user', 'ent_time', 'ent_ip_address', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

}


?>