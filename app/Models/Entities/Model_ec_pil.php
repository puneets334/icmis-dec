<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_ec_pil extends Model
{

    protected $table      = 'ec_pil';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['ref_language_id', 'received_from', 'address', 'ref_state_id', 'ref_city_id', 'received_on', 'petition_date', 'subject', 'ref_pil_category_id', 'diary_year', 'is_deleted', 'adm_updated_by', 'updated_on', 'group_file_number', 'action_taken', 'lodgment_date', 'written_to', 'written_for', 'return_date', 'sent_to', 'sent_on', 'remedy_text', 'report_received', 'report_received_date', 'destroy_on', 'in_record_on', 'remarks', 'ec_case_id', 'letter_date', 'action_taken_on', 'transfered_on', 'transfered_to', 'ir_received_on', 'ir_received_from', 'submitted_note_on', 'submitted_note_to', 'judgment_on_submitted_note', 'comp_order', 'weeded_on', 'diary_number', 'email', 'vernacular_language', 'address_to', 'returned_to_sender_remarks', 'written_on', 'lodged_action_reason', 'mobile', 'other_text', 'middle_name', 'last_name', 'registration_date', 'ref_action_taken_id', 'request_summary', 'other_action_taken_on', 'create_modify', 'updated_by', 'updated_by_ip'];
    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

}


?>