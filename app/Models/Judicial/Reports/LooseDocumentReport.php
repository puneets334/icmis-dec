<?php

namespace App\Models\Judicial\Reports;

use CodeIgniter\Model;

class LooseDocumentReportModel extends Model
{
    function SampleData()
    {
        $builder = $this->db->table("master.casetype");
        $builder->where('casecode!=9999');
        $builder->where('is_deleted', 'f');
        $builder->orderBy('casecode', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }
}