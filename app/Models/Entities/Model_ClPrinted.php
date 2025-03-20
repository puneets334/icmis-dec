<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_ClPrinted extends Model
{
    
    protected $table      = 'cl_printed';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'next_dt', 'm_f', 'part', 'main_supp', 'from_brd_no', 'to_brd_no', 'roster_id', 'usercode', 'ent_time', 'user_ip', 'display', 'deleted_by', 'deleted_on', 'pdf_gen', 'pdf_nm', 'pdf_dtl_nm', 'pdf_dtl_dt', 'is_pdf_murge', 'pdf_murge_time', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}