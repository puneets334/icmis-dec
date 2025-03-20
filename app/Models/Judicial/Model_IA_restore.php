<?php
namespace App\Models\Judicial;

use CodeIgniter\Model;

class Model_IA_restore extends Model{


    public function get_docdetails($diary_no,$doc_id='',$is_archival_table=''){
        $doccode = 8;
        $builder = $this->db->table("docdetails$is_archival_table");
        $builder->select("*,concat(docnum,'/',docyear) as ia");
        $builder->where('diary_no',$diary_no);
        if (!empty($doc_id)){
            $builder->whereIn('docd_id',$doc_id);
        }else{
            $builder->where('doccode', $doccode);
            $builder->where('iastat', 'D');
        }
        $query = $builder->get();
        return $query->getResultArray();

    }




}