<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class CaseRemarksMultipleHistory extends Entity
{
    
    protected $table      = 'case_remarks_multiple_history';
    // protected $primaryKey = '';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'fil_no', 'cl_date', 'r_head', 'head_content', 'remark', 'e_date', 'jcodes', 'remove', 'mainhead', 'clno', 'uid', 'dw', 'status', 'usr_entry', 'comp_date', 'notice_type', 'comp_comp_date', 'comp_remarks', 'last_updated', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}