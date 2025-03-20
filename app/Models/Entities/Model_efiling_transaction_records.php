<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_efiling_transaction_records extends Model
{

    protected $table      = 'e_filing.efiling_transaction_records';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['id', 'status_id', 'transaction_id', 'amount', 'transaction_datetime', 'transaction_timestamp', 'oid', 'ccbin', 'response_hash', 'hash_algo',
                                'payment_method', 'card_number', 'expiry_month', 'expiry_year', 'transaction_type', 'approval_code', 'fail_rc', 'fail_reason', 'processor_response_code',
                                'endpoint_transaction_id', 'diary_no', 'app_flag', 'payment_userid', 'sci_txtid', 'bank_txnid', 'bank_name', 'udf1', 'udf2', 'udf3', 'udf4', 'udf5', 'court_fees',
                                'print_fees', 'scheduler_datetime', 'action_update'];


    /*protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';*/

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

}

?>