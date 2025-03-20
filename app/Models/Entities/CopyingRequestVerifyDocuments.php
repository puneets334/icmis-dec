<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class CopyingRequestVerifyDocuments extends Entity
{
    
    protected $table      = 'copying_request_verify_documents';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'order_type', 'order_date', 'copying_order_issuing_application_id', 'number_of_copies', 'number_of_pages_in_pdf', 'path', 'from_page', 'to_page', 'display', 'order_type_remark', 'request_status',  'reject_cause', 'sms_sent_on', 'email_sent_on', 'current_section', 'fee_clc_for_certification_no_doc', 'fee_clc_for_certification_pages', 'fee_clc_for_uncertification_no_doc', 'fee_clc_for_uncertification_pages', 'fee_clc_creaded_by', 'fee_clc_created_on', 'fee_clc_created_ip', 'fee_clc_updated_by', 'fee_clc_updated_on', 'fee_clc_updated_ip', 'creaded_by', 'created_on', 'created_ip', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}