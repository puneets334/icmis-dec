<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_mul_category_a extends Model
{

    protected $table = 'mul_category_a';
    // protected $primaryKey = '';
    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['diary_no', 'submaster_id', 'mul_category_idd', 'display', 'od_cat', 'e_date', 'mul_cat_user_code', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];
    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

}


?>