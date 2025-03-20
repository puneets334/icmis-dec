<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_mediapersions extends Model
{

    protected $table = 'master.media_persions';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['name', 'media_name', 'mobile', 'otp', 'display', 'create_on', 'last_login', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];
    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

}


?>

