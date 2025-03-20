<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class DashboardDataLog extends Entity
{
    
    protected $table      = 'dashboard_data_log';
    // protected $primaryKey = '';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'id', 'flag', 'da_code', 'counted_data', 'list_date', 'with_connected', 'is_active', 'ason', 'roster_id', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}