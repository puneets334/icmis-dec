<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class CopyingApplicationDefectsOrg extends Entity
{
    
    protected $table      = 'copying_application_defects_org';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'copying_order_issuing_application_id', 'ref_order_defect_id', 'defect_notification_date', 'defect_cure_date', 'defect_notified_by', 'defect_cured_by', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}