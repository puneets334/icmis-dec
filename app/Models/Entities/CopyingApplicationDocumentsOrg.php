<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class CopyingApplicationDocumentsOrg extends Entity
{
    
    protected $table      = 'copying_application_documents_org';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'order_type', 'order_date', 'copying_order_issuing_application_id', 'number_of_copies', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}