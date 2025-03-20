<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;

class CopyingApplicationDocumentsA extends Entity
{
    
    protected $table      = 'copying_application_documents_a';
    // protected $primaryKey = '';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'id', 'order_type', 'order_date', 'copying_order_issuing_application_id', 'number_of_copies', 'number_of_pages_in_pdf', 'path', 'from_page', 'to_page', 'display', 'pdf_embed_path', 'pdf_embed_on', 'pdf_embed_by', 'pdf_downloaded_on', 'pdf_downloaded_by', 'pdf_digital_signature_path', 'pdf_digital_signature_on', 'pdf_digital_signature_by', 'sent_to_applicant_on', 'sent_to_applicant_by', 'email_sent_on', 'is_bail_order', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}