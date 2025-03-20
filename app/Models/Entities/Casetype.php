<?php

namespace App\Models;

use CodeIgniter\Model;

class Casetype extends Model
{
    protected $table      = 'public.casetype'; // Ensure this matches the table name in your database
    protected $primaryKey = 'casecode';

    protected $useAutoIncrement = false;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'casecode',
        'casename',
        'skey',
        'display',
        'nature',
        'cs_m_f',
        'order_no',
        'company',
        'short_description',
        'adm_updated_by',
        'limitation',
        'case_type_code',
        'is_deleted',
        'sc_case_type_code',
        'case_type_judis',
        'diary_code',
        'national_code',
        'national_case_type',
        'national_relief_type',
        'jurisdiction',
        'national_relief_code',
        'create_modify',
        'updated_on',
        'updated_by',
        'updated_by_ip'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function caseTypeFun()
    {
        try {
            $builder = $this->builder(); // Use the builder method
            $builder->where('display', 'Y');
            $builder->where('casecode !=', 9999);
            $builder->orderBy('short_description');

            $result = $builder->get()->getResultArray(); // Get the result
            return $result;
        } catch (\Exception $e) {
            // Log the error message and return an empty array
            log_message('error', 'Error in caseType method: ' . $e->getMessage());
            return [];
        }
    }

    public function getActiveCaseTypes()
    {
        return $this->where('display', 'Y')
            ->orderBy('nature')
            ->orderBy('skey')
            ->findAll();
    }

   
}
