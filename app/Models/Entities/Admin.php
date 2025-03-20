<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class Admin extends Entity
{
    
    protected $table      = 'admin';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['fullname', 'adminemail', 'username', 'password', 'updationdate', 'user_type', 'role_id', 'phone_number', 'alternative_phone_no', 'created_on', 'status', 'icmis_user_id', 'court_no', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}