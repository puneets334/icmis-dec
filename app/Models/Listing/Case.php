<?php namespace App\Models\Listing;

use CodeIgniter\Model;

class CaseModel extends Model
{
    protected $table = 'cases'; 
    protected $primaryKey = 'id'; 

    public function getCases($filters)
    {
        $builder = $this->db->table($this->table);
        $builder->select('m.*, group_concat(c.diary_no) as child_case');
        $builder->join('heardt h', 'm.diary_no = h.diary_no', 'left');
        $builder->join('listing_purpose l', 'l.code = h.listorder', 'left');
        $builder->join('casetype c', 'm.active_casetype_id = c.casecode', 'left');
        $builder->join('mul_category c2', 'c2.diary_no = h.diary_no AND c2.display = \'Y\' and c2.submaster_id != 331 and c2.submaster_id != \'\'', 'left');
        $builder->where("l.display", 'Y');
        
        // Apply filters
        if (!empty($filters['mainhead'])) {
            $builder->where("m.diary_no", $filters['mainhead']);
        }

        // Apply other conditions similarly based on $filters array

        $builder->groupBy('h.diary_no');
        $builder->orderBy('...'); // Dynamic order by logic

        return $builder->get()->getResultArray();
    }
}
