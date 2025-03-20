<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_CaveatParty extends Model
{
    
    protected $table      = 'caveat_party';
    // protected $primaryKey = 'no';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['sr_no', 'caveat_no', 'pet_res', 'sr_no_old', 'ind_dep', 'partysuff', 'partyname', 'sonof', 'authcode', 'state_in_name', 'prfhname', 'age', 'sex', 'caste', 'addr1', 'addr2', 'state', 'city', 'pin', 'email', 'contact', 'usercode', 'ent_dt', 'pflag', 'dstname', 'deptcode', 'pan_card', 'adhar_card', 'country', 'education', 'occ_code', 'edu_code', 'lowercase_id', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}