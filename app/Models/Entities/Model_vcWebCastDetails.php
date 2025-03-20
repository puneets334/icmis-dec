<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_vcWebCastDetails extends Model
{

    protected $table      = 'vc_webcast_details';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['nofn_link', 'vcmeet_link', 'display', 'is_nofn', 'is_vcmeet', 'updated_on', 'courtno', 'sbanch_link', 'is_sb', 'is_webex', 'webex_link', 'bench_time', 'remark', 'bench_date', 'create_modify', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

}


?>






