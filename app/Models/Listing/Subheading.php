<?php

namespace App\Models\Listing;

use CodeIgniter\Model;

class Subheading extends Model
{
    protected $table = 'master.subheading';  // The table name
    protected $primaryKey = 'id';     // Set your table's primary key

    public function getActiveSubheadings()
    {
        return $this->where('listtype', 'M')
                    ->where('display', 'Y')
                    ->orderBy('priority')
                    ->orderBy('stagename')
                    ->findAll();
    }

    public function getSubheadings($mainhead, $side)
    {
        $this->select('stagecode, stagename');
        $this->where('listtype', $mainhead);
        $this->where('display', 'Y');
        if ($side == 'C') {
            $this->where('stagecode !=', 811);
            $this->where('stagecode !=', 814);
            $this->where('stagecode !=', 815);
        } elseif ($side == 'R') {
            $this->where('stagecode !=', 812);
            $this->where('stagecode !=', 813);
            $this->where('stagecode !=', 816);
        }

        return $this->orderBy('stagecode', 'ASC')->findAll();
    }
    public function getMulCategorySubheadings($diaryNo)
    {
        $builder = $this->db->table('mul_category a');
        $builder->select(['submaster_id AS stagecode', "CONCAT(sub_name1, '-', sub_name2, '-', sub_name3, '-', sub_name4) AS stagename"]);
        $builder->join('master.submaster b', 'submaster_id = b.id', 'left');
        $builder->where('a.display', 'Y');
        $builder->where('diary_no', $diaryNo);
        return $builder->get()->getResultArray();
    }

    public function getSubheading()
    {
        return $this->where('listtype', 'M')
                    ->where('display', 'Y')
                    ->orderBy('priority')
                    ->orderBy('stagename')
                    ->findAll();
    }
    
}
