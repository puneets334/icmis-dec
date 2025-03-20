<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class ConsentThroughEmail extends Entity
{
    
    protected $table      = 'consent_through_email';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [  'diary_no', 'conn_key', 'next_dt', 'roster_id', 'part', 'main_supp_flag', 'applicant_type', 'party_id', 'advocate_id', 'entry_source', 'user_id', 'entry_date', 'user_ip', 'is_deleted', 'deleted_by', 'deleted_on', 'deleted_ip' ,'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}