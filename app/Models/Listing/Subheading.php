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
    // public function getMulCategorySubheadings($diaryNo)
    // {
    //     $builder = $this->db->table('mul_category a');
    //     $builder->select(['submaster_id AS stagecode', "CONCAT(sub_name1, '-', sub_name2, '-', sub_name3, '-', sub_name4) AS stagename"]);
    //     $builder->join('master.submaster b', 'submaster_id = b.id', 'left');
    //     $builder->where('a.display', 'Y');
    //     $builder->where('diary_no', $diaryNo);
    //     return $builder->get()->getResultArray();
    // }

    public function getMulCategorySubheadings($diaryNo, $side)
    {
        $db = \Config\Database::connect();
        $stage_based_on_side = "";
        if ($side == 'C') {
            $stage_based_on_side = " AND stagecode NOT IN (811, 814, 815)";
        } else if ($side == 'R') {
            $stage_based_on_side = " AND stagecode NOT IN (812, 813, 816)";
        }

        // Final SQL query
        $sql = "
        SELECT * FROM (
            SELECT stagecode, stagename, '1' AS mf 
            FROM subheading 
            WHERE listtype = 'M' $stage_based_on_side AND display = 'Y'
            
            UNION
            
            SELECT 
                submaster_id AS stagecode, 
                CONCAT(COALESCE(sub_name1, ''), '-', COALESCE(sub_name2, ''), '-', COALESCE(sub_name3, ''), '-', COALESCE(sub_name4, '')) AS stagename, 
                '2' AS mf 
            FROM mul_category a 
            LEFT JOIN submaster b ON submaster_id = b.id 
            WHERE a.display = 'Y' AND diary_no = ?
        ) a
        ORDER BY mf
    ";

        // Execute with binding
        $query = $db->query($sql, [$diaryNo]);
        return $query->getResultArray();
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
