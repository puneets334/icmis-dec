<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_filing_stats extends Model
{

    protected $table      = 'filing_stats';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['filing_date', 'updation_time', 'total_filed', 'physical_filed', 'old_efiled', 'refiled', 'registered', 'checked_verified', 'verified', 'tagging_verification',
                                'verification_refiled_total', 'verification_refiled_reg', 'filing_alloted', 'filing_completed', 'filing_pending', 'refiled_alloted', 'refiled_completed',
                                'refiled_pending', 'new_efiled', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];


    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

}

?>