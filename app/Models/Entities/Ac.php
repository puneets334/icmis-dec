<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class Ac extends Entity
{
    
    protected $table      = 'ac';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['aor_code', 'cname', 'cfname', 'pa_line1', 'pa_line2', 'pa_district', 'pa_pin', 'ppa_line1', 'ppa_line2', 'ppa_district', 'ppa_pin', 'dob', 'place_birth', 'nationality', 'cmobile', 'eq_x', 'eq_xii', 'eq_ug', 'eq_pg', 'eino', 'regdate', 'status', 'updatedby', 'updatedon', 'updatedip', 'modified_on', 'modified_by', 'modified_ip', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}