<?php

namespace App\Models\Judicial\Advocate;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class AdvocateModel extends Model
{

    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    public function getAdvocate($advName)
    {
        $builder = $this->db->table("master.bar");
        $builder->select("name, name as label, CONCAT(mobile,'~',email,'~',aor_code,'~',if_aor) value");
        if (!empty($advName)) {
            $builder->like('LOWER(name)', strtolower($advName)); 
        }

        $builder->where('isdead', 'N');
        // $sql = $builder->getCompiledSelect();
        // echo "<pre>$sql</pre>";
        $builder->limit(10);
        // Execute the query
        $query = $builder->get();
        return $query->getResultArray();
    }



    /* end function case_nos Anshu */
}
