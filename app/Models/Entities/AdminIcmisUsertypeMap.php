<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class AdminIcmisUsertypeMap extends Entity
{
    
    protected $table      = 'admin_icmis_usertype_map';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['admin_designation_id', 'admin_designation_name', 'icmis_usertype_id', 'icmis_usertype_name', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}