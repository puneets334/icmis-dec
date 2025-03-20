<?php
namespace App\Models\Master;
use CodeIgniter\Model;
class Model_casetype extends Model{
    protected $table      = 'master.casetype';
    protected $primaryKey = 'casecode';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['casecode', 'casename', 'skey', 'display', 'nature', 'cs_m_f', 'order_no','company','short_description', 'adm_updated_by',
                                'updated_on', 'limitation', 'case_type_code', 'is_deleted', 'sc_case_type_code', 'case_type_judis', 'diary_code', 'national_code', 'national_case_type',
                                'national_relief_type', 'jurisdiction', 'national_relief_code', 'create_modify', 'updated_by', 'updated_by_ip'
                               ];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

}
