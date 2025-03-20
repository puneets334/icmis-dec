<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_CaveatLowerctA extends Model
{
    
    protected $table      = 'caveat_lowerct_a';
    // protected $primaryKey = 'no';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['caveat_no', 'lct_dec_dt', 'lct_judge_desg', 'lct_judge_name', 'lctjudname2', 'lct_jud_id', 'lct_jud_id1', 'lct_jud_id2', 'lct_jud_id3', 'l_dist', 'polstncode', 'crimeno', 'crimeyear', 'usercode', 'ent_dt', 'lctjudname3', 'ct_code', 'doi', 'hjs_cnr', 'ljs_doi', 'ljs_cnr', 'l_state', 'l_state_old', 'lower_court_id', 'lw_display', 'brief_desc', 'sub_law', 'l_inddep', 'l_iopb', 'l_iopbn', 'l_org', 'l_orgname', 'l_ordchno', 'lct_casetype', 'lct_casetype_old', 'lct_caseno', 'lct_caseyear', 'is_order_challenged', 'full_interim_flag', 'judgement_covered_in', 'vehicle_code', 'vehicle_no', 'cnr_no', 'ref_court', 'ref_case_type', 'ref_case_no', 'ref_case_year', 'ref_state', 'ref_district', 'gov_not_state_id', 'gov_not_case_type', 'gov_not_case_no', 'gov_not_case_year', 'gov_not_date', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
        
}