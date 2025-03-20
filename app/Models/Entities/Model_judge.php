<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_judge extends Model
{

    protected $table      = 'master.judge';
    protected $primaryKey = 'jcode';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'jcode', 'jname', 'first_name', 'title', 'sur_name', 'jcourt', 'abbreviation', 'is_retired', 'display', 'appointment_date', 'to_dt', 'cji_date',
                                 'jtype', 'entuser', 'entdt', 'judge_seniority', 'national_uid', 'judge_desg_code', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


}