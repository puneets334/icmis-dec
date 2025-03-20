<?php

namespace App\Models\MasterManagement;

use CodeIgniter\Model;

class MenuUserReportModel extends Model
{
    protected $table = 'master.users';
    protected $primaryKey = 'usercode';
    protected $allowedFields = ['name', 'usercode'];

    public function getUserReportOrderedById()
    {
        return $this->select('usercode')->select('name')->orderBy('usercode', 'asc')->findAll();
    }
 

    public function getMenuUserReportById($usrid)
    {
       
       $sql =  "select role_desc,id from master.role_master where id IN (select role_master_id from master.user_role_master_mapping where display='Y' AND usercode=$usrid);";
       $query = $this->db->query($sql);
       return $query->getResultArray();

    }


    

    public function RoleMenuMapping($role_master_id)
    {
        $subQuery = "SELECT a.menu_id, b.menu_nm, b.url, b.old_smenu_id 
        FROM master.role_menu_mapping a, master.menu b 
        WHERE a.role_master_id = ? 
            AND a.display = 'Y' 
            AND b.menu_id = CASE 
                LENGTH(a.menu_id) 
                WHEN 2 THEN CONCAT(a.menu_id, '0000000000') 
                WHEN 4 THEN CONCAT(a.menu_id, '00000000') 
                WHEN 6 THEN CONCAT(a.menu_id, '000000') 
                WHEN 8 THEN CONCAT(a.menu_id, '0000') 
                WHEN 10 THEN CONCAT(a.menu_id, '00') 
                ELSE a.menu_id 
            END 
        ORDER BY a.menu_id;";
        $query = $this->db->query($subQuery, [$role_master_id]);
        return  $query;

    }



    public function getMasterMenu($rmenu_id)
    {
                $sql = "SELECT display FROM master.menu WHERE menu_id = CASE 
                LENGTH('" . $rmenu_id . "') 
                WHEN 2 THEN '" . $rmenu_id . "0000000000' 
                WHEN 4 THEN '" . $rmenu_id . "00000000' 
                WHEN 6 THEN '" . $rmenu_id . "000000' 
                WHEN 8 THEN '" . $rmenu_id . "0000'  
                WHEN 10 THEN '" . $rmenu_id . "00' 
                ELSE '" . $rmenu_id . "' 
            END;";

            $query = $this->db->query($sql);
            return  $query;


        }


   
        public function getAllParentMenuRowID($rmenu_id)
        {


            $sql = "SELECT menu_id, menu_nm, url, old_smenu_id 
            FROM master.menu 
            WHERE display = 'Y' AND menu_id = 
                CASE LENGTH('" . $this->db->escapeString($rmenu_id) . "') 
                    WHEN 2 THEN '" . $this->db->escapeString($rmenu_id . "0000000000") . "' 
                    WHEN 4 THEN '" . $this->db->escapeString($rmenu_id . "00000000") . "' 
                    WHEN 6 THEN '" . $this->db->escapeString($rmenu_id . "000000") . "' 
                    WHEN 8 THEN '" . $this->db->escapeString($rmenu_id . "0000") . "'  
                    WHEN 10 THEN '" . $this->db->escapeString($rmenu_id . "00") . "' 
                    ELSE '" .  $this->db->escapeString($rmenu_id) . "' 
                END;";

                $query = $this->db->query($sql);
                return  $query;
    
    
            }



            public function getallSubMenuRowsID($rmenu_id)
            {
                $subQuery = "SELECT menu_id, menu_nm, url, old_smenu_id 
                FROM master.menu 
                WHERE display = 'Y' AND menu_id LIKE ? 
                ORDER BY menu_id;";
                $query = $this->db->query($subQuery, [$rmenu_id . '%']);
                return $query;
                
            }




 


            



}  


    
    

     
    

 
  
