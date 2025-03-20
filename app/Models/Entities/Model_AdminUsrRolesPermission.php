<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_AdminUsrRolesPermission extends Model
{
    
    protected $table      = 'admin_usr_roles_permission';
    // protected $primaryKey = 'id';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id', 'role_id', 'permission_id', 'status', 'created_on', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}