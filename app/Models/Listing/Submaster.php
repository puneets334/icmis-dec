<?php

namespace App\Models\Listing;

use CodeIgniter\Model;

class Submaster extends Model
{
    protected $table = 'master.submaster';  // The table name with schema
    protected $primaryKey = 'id';           // Set your table's primary key
    protected $allowedFields = ['id', 'subcode1', 'subcode2', 'subcode3', 'subcode4', 'sub_name4', 'display'];
    protected $returnType = 'array';

    public function getActiveSubmasters()
    {
        $sql = "SELECT * FROM master.submaster WHERE display = 'Y' AND old_sc_c_kk != 0 ORDER BY CASE WHEN id IN (343, 15, 16, 17, 18, 19, 20, 21, 22, 23, 341, 353, 157, 158, 159, 160, 161, 162, 163, 166, 173, 175, 176, 322, 222) THEN 0 ELSE 1 END ASC, subcode1, subcode2, subcode3, subcode4";
        $query = $this->db->query($sql);
        $results = $query->getResultArray();
        return $results;
    }

    public function getActiveSubmastersBk()
    {
        return $this->where('display', 'Y')
            //->orderBy("CASE WHEN id IN (343,15,16,17,18,19,20,21,22,23,341,353,157,158,159,160,161,162,163,166,173,175,176,322,222) THEN 0 ELSE 1 END", 'ASC')
            ->orderBy('subcode1', 'ASC')
            ->orderBy('subcode2', 'ASC')
            ->orderBy('subcode3', 'ASC')
            ->orderBy('subcode4', 'ASC')
            ->findAll();
    }

    public function getCategories()
    {
        return $this->where('display', 'Y')
                    ->where('flag', 's')
                    ->where('old_sc_c_kk !=', 0)
                    ->where('CAST(subcode2 AS INTEGER)', 0) 
                    ->where('CAST(subcode3 AS INTEGER)', 0) 
                    ->where('CAST(subcode4 AS INTEGER)', 0) 
                    ->orderBy('subcode1')
                    ->findAll();
    }
    
    public function getSubcategories($subcode1)
    {
        return $this->where('subcode1', $subcode1)
            ->where('display', 'Y')
            ->where('flag', 's')
            ->where("CAST(old_sc_c_kk AS TEXT) != '0'", null, false)
            ->where("CAST(subcode2 AS TEXT) != '0'", null, false)
            ->orderBy('subcode2')
            ->findAll();
    }


}
