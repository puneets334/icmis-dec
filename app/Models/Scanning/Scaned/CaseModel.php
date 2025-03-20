<?php

namespace App\Models\Scanning\Scaned;

use CodeIgniter\Model;

class CaseModel extends Model
{
    protected $table = 'master.lc_hc_casetype'; 
    protected $primaryKey = 'lccasecode'; 

    public function getCaseTypes($ddl_st_agncy)
    {
        return $this->where('display', 'Y')
            ->where('cmis_state_id', $ddl_st_agncy)
            ->where('ref_agency_code_id', 0)
            ->where('corttyp', 'H')
            ->orderBy('type_sname')
            ->findAll();
    }
}
