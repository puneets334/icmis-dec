<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_dispose extends Model
{

    protected $table      = 'dispose';
    protected $primaryKey = 'diary_no';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['fil_no', 'month', 'dispjud', 'year', 'ord_dt', 'disp_dt', 'disp_dt_old', 'disp_type', 'bench', 'jud_id', 'camnt', 'crtstat', 'usercode', 'ent_dt', 'jorder', 'rj_dt', 'disp_type_all', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

}


?>