<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class BulkDismissalLog extends Entity
{
    
    protected $table      = 'bulk_dismissal_log';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'diary_nos', 'ucode', 'jcodes', 'dismissal_type', 'dismissal_order_dt', 'entered_on', 'rj_date', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}