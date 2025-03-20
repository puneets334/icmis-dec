<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_post_bar_code_mapping extends Model
{
    
    protected $table      = 'post_bar_code_mapping';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'id','barcode', 'copying_application_id', 'ent_time', 'module_flag', 'is_consumed', 'consumed_by', 
                                'consumed_on', 'is_deleted', 'deleted_by', 'deleted_on', 'envelope_weight', 'sms_sent_time','email_sent_time','create_modify','updated_on',
                                'updated_by','updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}