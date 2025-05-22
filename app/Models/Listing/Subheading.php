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
     public function getMulCategorySubheadings($diaryNo, $side)
     {
        $stage_exclude_codes = [];
        if ($side === 'C') {
            $stage_exclude_codes = [811, 814, 815];
        } elseif ($side === 'R') {
            $stage_exclude_codes = [812, 813, 816];
        }

        $builder1 = $this->db->table('master.subheading');
        $builder1->select("stagecode, stagename, '1' as mf");
        $builder1->where('listtype', 'M');
        $builder1->where('display', 'Y');
        if (!empty($stage_exclude_codes)) {
            $builder1->whereNotIn('stagecode', $stage_exclude_codes);
        }
        $result1 = $builder1->get()->getResultArray();
        
        $builder2 = $this->db->table('mul_category a');
        $builder2->select("submaster_id as stagecode, CONCAT_WS('-', sub_name1, sub_name2, sub_name3, sub_name4) as stagename, '2' as mf");
        $builder2->join('master.submaster b', 'submaster_id = b.id', 'left');
        $builder2->where('a.display', 'Y');
        $builder2->where('diary_no', $diaryNo);

        $result2 = $builder2->get()->getResultArray();
        $combined = array_merge($result1, $result2);
        foreach ($combined as $key => $value) {
            if (empty($value['stagename'])) {
                unset($combined[$key]);
            }
        }
        
        usort($combined, function ($a, $b) {
            return $a['mf'] <=> $b['mf'];
        });
        return $combined;
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
