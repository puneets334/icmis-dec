<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_Craent extends Model
{
    
    protected $table      = 'craent';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'fil_no', 'sentence', 'status', 'ugone_yr', 'ugone_mon', 'ugone_day', 'ucode', 'entdt', 'upd_da', 'sentence_mth', 'act_fine', 'lower_court_id', 'from_date', 'to_date', 'accused_id', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}