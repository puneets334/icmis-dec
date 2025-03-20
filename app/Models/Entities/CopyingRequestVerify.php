<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class CopyingRequestVerify extends Entity
{
    
    protected $table      = 'copying_request_verify';
    protected $primaryKey = 'crn';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'id', 'diary', 'copy_category', 'application_reg_number', 'application_reg_year', 'application_receipt', 'advocate_or_party', 'court_fee', 'delivery_mode', 'postal_fee', 'ready_date', 'dispatch_delivery_date', 'adm_updated_by', 'is_deleted', 'is_id_checked', 'purpose', 'application_status', 'defect_code', 'defect_description', 'notification_date', 'filed_by', 'name', 'mobile', 'address', 'application_number_display', 'temp_id', 'remarks', 'source', 'send_to_section', 'email', 'authorized_by_aor', 'allowed_request', 'ready_remarks', 'token_id', 'address_id', 'sms_sent_on', 'email_sent_on', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}