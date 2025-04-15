<?php

namespace App\Models\Copying;

use CodeIgniter\Model;

class Model_copying extends Model
{
    protected $eservicesdb;
    public function __construct(){
        parent::__construct();
        $this->eservicesdb = \Config\Database::connect('eservices');
    }

    public function get_rop_path($data){

        $builder = $this->db->table("public.ordernet_rop_sci");

        $builder->select('*');
        $builder->where('diary_no',(INT)$data['diary']);
        $builder->where('diary_year', (INT)$data['diary_year']);
        $builder->where('rop',$data['order_date']);
        //echo $builder->getCompiledSelect();
        //die;
        $query = $builder->get();

         //echo $this->db->getLastQuery();
        //exit; 
       
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            
            return $result;
        } else {
            return false;
        }

    }

    public function get_consume_barcode(){

        $builder = $this->db->table('copying_order_issuing_application_new c');

        $subquery = $this->db->table('copying_application_documents')
            ->select('*')
            ->union($this->db->table('copying_application_documents_a')->select('*'))
            ->getCompiledSelect(); // Corrected method name

        $builder->selectSum('(number_of_copies * number_of_pages_in_pdf)', 'total_pages')
            ->selectSum('number_of_copies', 'total_copy')
            ->select('c.*')
            ->join("($subquery) b", "c.id = b.copying_order_issuing_application_id and b.display = 'Y'", 'inner')
            ->join("post_bar_code_mapping p", "p.copying_application_id = c.id and p.is_consumed = '1' and p.is_deleted = '0'", 'left')
           // ->where('p.copying_application_id', null)
            ->where('c.delivery_mode', '1')
            ->where('c.source', '6')
            ->where('application_status', 'D')
           // ->where('date(dispatch_delivery_date) >=', '2020-08-01')
            ->groupBy(['c.id', 'c.diary', 'c.copy_category', 'c.application_reg_number', 'c.application_reg_year'])
            ->orderBy('application_receipt');
            //echo $builder->getCompiledSelect();
            //die;
        $query = $builder->get();
       //echo $this->db->getLastQuery();
      
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }

    }

    public function getConsignmentDetails($barcode){
        $builder = $this->eservicesdb->table("post_tracking");
        $builder->select('*');
        $builder->where('barcode', $barcode);
        $builder->orderBy('event_date ASC, event_time ASC');
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }
    public function getApplication($document_id){
        $builder = $this->db->table("public.copying_application_documents");

        $builder->select('*');
        $builder->where('display','Y');
        $builder->where('id',$document_id);
        //echo $builder->getCompiledSelect();
        //die;
        $query = $builder->get();

         //echo $this->db->getLastQuery();
        //exit; 
       
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            
            return $result;
        } else {
            return false;
        }
    }
    
    public function getUserVerficationDetails($mobile,$email,$diary_no){
        
        $builder = $this->eservicesdb->table("user_assets as u");
        $builder->select('u.asset_type, a.asset_name, u.id_proof_type, i.id_name, u.file_path, u.verify_status, u.verify_on, u.video_random_text');
        $builder->join('user_asset_type_master a', 'a.id = u.asset_type');
        $builder->join('id_proof_master i', "i.id = u.id_proof_type and i.display = 'Y'", 'left');
        $builder->where('u.mobile',$mobile);
        $builder->where('u.email', $email);
        $builder->where('u.verify_status', 2);
        $builder->whereIn('u.diary_no', [0, $diary_no]);
        $builder->orderBy('u.ent_time');

        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }

    }
   public function save_copying_application_documents($data,$id){
    $builder = $this->db->table("public.copying_application_documents");
    // Update the user data using Query Builder
    $builder->where('id',$id);
    $builder->update($data);
   }



}

?>