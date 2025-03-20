<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_menu extends Model
{

    protected $table = 'master.menu';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['id', 'menu_nm', 'priority', 'display', 'menu_id', 'url', 'old_smenu_id', 'icon', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];
    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

}


?>

