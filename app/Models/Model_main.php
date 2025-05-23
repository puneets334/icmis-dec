<?php
namespace App\Models;
use CodeIgniter\Model;
class Model_main extends Model{
    protected $table = 'main';
    protected $primaryKey = 'diary_no';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['diary_no', 'active_fil_no', 'fil_no', 'fil_no_old', 'pet_name', 'res_name', 'res_name_old', 'pet_adv_id', 'res_adv_id', 'actcode', 'claim_amt', 'bench', 'fixed', 'c_status', 'fil_dt', 'active_fil_dt', 'case_pages', 'relief', 'usercode', 'last_usercode', 'dacode', 'old_dacode', 'old_da_ec_case', 'last_dt', 'conn_key', 'case_grp', 'lastorder', 'fixeddet', 'bailno', 'prevno', 'head_code', 'scr_user', 'scr_time', 'scr_type', 'prevno_fildt', 'ack_id', 'ack_rec_dt', 'admitted', 'outside', 'diary_no_rec_date', 'diary_user_id', 'ref_agency_state_id', 'ref_agency_state_id_old', 'ref_agency_code_id', 'ref_agency_code_id_old', 'from_court', 'is_undertaking', 'undertaking_doc_type', 'undertaking_reason', 'casetype_id','active_casetype_id', 'padvt', 'radvt', 'total_court_fee', 'court_fee', 'valuation', 'case_status_id', 'brief_description', 'nature', 'fil_no_fh', 'fil_no_fh_old', 'fil_dt_fh', 'mf_active', 'active_reg_year', 'reg_year_mh', 'reg_year_fh', 'reg_no_display', 'pno', 'rno', 'if_sclsc', 'section_id', 'unreg_fil_dt', 'refiling_attempt', 'last_return_to_adv', 'create_modify', 'updated_by_ip', 'updated_by', 'updated_on'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

/*public function get_filing_detailsBydiary_no($diary_no){
    $this->db = \Config\Database::connect();
    $builder = $this->db->table("main");
    $builder->select("*");
    $builder->WHERE('diary_no',$diary_no);
    //$builder->WHERE('diary_no is not null');
    $query =$builder->get();
    if($query->getNumRows() >= 1) {
        return $result = $query->getResultArray();
    }else{return false;}
}*/

}
