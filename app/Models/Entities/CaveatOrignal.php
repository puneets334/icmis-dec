<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class CaveatOrignal extends Entity
{
    
    protected $table      = 'caveat_orignal';
    protected $primaryKey = 'caveat_no';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    
    
    protected $allowedFields = ['caveat_no', 'fil_no', 'pet_name', 'res_name', 'pet_adv_id', 'res_adv_id', 'actcode', 'claim_amt', 'bench', 'fixed', 'c_status', 'fil_dt', 'case_pages', 'relief', 'usercode', 'last_usercode', 'dacode', 'last_dt', 'conn_key', 'case_grp', 'lastorder', 'fixeddet', 'bailno', 'prevno', 'head_code', 'scr_user', 'scr_time', 'scr_type', 'prevno_fildt', 'ack_id', 'ack_rec_dt', 'admitted', 'outside', 'diary_no_rec_date', 'diary_user_id', 'ref_agency_state_id', 'ref_agency_code_id', 'from_court', 'is_undertaking', 'undertaking_doc_type', 'undertaking_reason', 'casetype_id', 'casetype_name', 'padvt', 'radvt', 'total_court_fee', 'court_fee', 'valuation', 'case_status_id', 'brief_description', 'nature', 'fil_no_fh', 'fil_dt_fh', 'mf_active', 'pno', 'rno', 'is_renew'];

    protected $useTimestamps = true;
    // protected $createdField  = 'create_modify';
    // protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}