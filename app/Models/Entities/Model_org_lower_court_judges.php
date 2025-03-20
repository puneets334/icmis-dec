<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_org_lower_court_judges extends Model
{
    protected $table      = 'master.org_lower_court_judges';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['judge_code', 'abbreviation', 'title', 'first_name', 'sur_name', 'appointment_date', 'retirement_date', 'is_retired', 'reg_agency_state_id',
                                'updated_by', 'updated_on', 'is_deleted', 'cmis_state_id', 'supreme_court_jud_id', 'ent_ip_address', 'create_modify', 'updated_by_ip'];
    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


}