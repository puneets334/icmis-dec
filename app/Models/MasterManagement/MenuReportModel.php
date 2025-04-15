<?php

namespace App\Models\MasterManagement;

use CodeIgniter\Model;

class MenuReportModel extends Model
{

    protected $table = 'master.role_master';
    protected $primaryKey = 'id';
    protected $allowedFields = ['role_desc', 'id'];

    public function getMenuRolesOrderedById()
    {
        return $this->orderBy('id', 'ASC')->findAll();
    }


    public function getMenuRolesById($rolelistID)
    {
        return $this->whereIn('id', $rolelistID)->findAll();
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




    public function fetchSubMenus($roleMid)
    {
        echo '<div class="tree well">
            <ul>';
        $query = "SELECT 
                a.menu_nm, 
                    substring(a.menu_id FROM 1 FOR 2) AS menu_id_prefix, 
                    a.url AS menu_id, 
                    (
                        SELECT 
                        COALESCE(STRING_AGG(b.menu_id, ','), '0') 
                        FROM 
                        master.role_menu_mapping b 
                        WHERE 
                        LENGTH(b.menu_id) = 2 
                        AND b.role_master_id = $roleMid
                    ) AS mid 
                    FROM 
                    master.menu a 
                    WHERE 
                    substring(a.menu_id FROM 3) = '0000000000' 
                    AND display = 'Y' 
                    AND a.menu_id IS NOT NULL 
                    ORDER BY 
                    a.priority;";
        $rs = $this->query($query);

        if ($rs->getNumRows() > 0) {
            foreach ($rs->getResultArray() as $Main_menus) {
                $menu_id =  $Main_menus['menu_id_prefix'];
                $mheading = ucfirst($Main_menus['menu_nm']);
                $murl = $Main_menus['menu_id'];
                $Mchkid = $Main_menus['mid'];
                $mchecked = '';
                if (strstr($Mchkid, $menu_id)) $mchecked = ' checked="checked"';

                $squery = "SELECT 
                            menu_nm, 
                            substring(menu_id FROM 1 FOR 4) AS sml1_id, 
                            url, 
                            (
                                SELECT 
                                COALESCE(STRING_AGG(b.menu_id, ','), '0') 
                                FROM 
                                master.role_menu_mapping b 
                                WHERE 
                                LENGTH(b.menu_id) = 4 
                                AND b.role_master_id = $roleMid
                            ) AS mid 
                            FROM 
                            master.menu 
                            WHERE 
                            substring(menu_id FROM 5) = '00000000' 
                            AND substring(menu_id FROM 1 FOR 2)::integer = $menu_id
                            AND substring(menu_id FROM 3 FOR 2) <> '00' 
                            AND display = 'Y' 
                            AND menu_id IS NOT NULL 
                            ORDER BY 
                            priority, 
                            substring(menu_id FROM 1 FOR 4)";

                $sqrs = $this->query($squery);

                if ($sqrs->getNumRows() > 0) {

                    echo '<li>
                            <input type="checkbox" name="menuIds" id="menuIds" value="' . $menu_id . '"' . $mchecked . '><span>' . $mheading . '&nbsp;&nbsp;<i class="fa fa-plus"></i></span>
                            <ul>';

                    foreach ($sqrs->getResultArray() as $sm_rows) {
                        $smlv1_id = $sm_rows['sml1_id'];
                        $smlv1_heading = ucfirst($sm_rows['menu_nm']);
                        $url_lv1 = $sm_rows['url'];
                        $lv1chkid = $sm_rows['mid'];
                        $l1checked = '';
                        if (strstr($lv1chkid, $smlv1_id)) $l1checked = ' checked="checked"';

                        $sml2_query = "SELECT 
                                        menu_nm, 
                                        substring(menu_id FROM 1 FOR 6) AS sml2_id, 
                                        url, 
                                        (
                                            SELECT 
                                            COALESCE(STRING_AGG(b.menu_id, ','), '0') 
                                            FROM 
                                            master.role_menu_mapping b 
                                            WHERE 
                                            LENGTH(b.menu_id) = 6 
                                            AND b.role_master_id = $roleMid
                                        ) AS mid 
                                        FROM 
                                        master.menu 
                                        WHERE 
                                        substring(menu_id FROM 7) = '000000' 
                                        AND substring(menu_id FROM 1 FOR 4)::INTEGER = $smlv1_id
                                        AND substring(menu_id FROM 5 FOR 2) <> '00' 
                                        AND display = 'Y' 
                                        AND menu_id IS NOT NULL 
                                        ORDER BY 
                                        priority, 
                                        substring(menu_id FROM 1 FOR 4);";


                        $sml2_rs = $this->query($sml2_query);
                        if ($sml2_rs->getNumRows() > 0) {

                            echo '<li>
                                    <input type="checkbox" name="menuIds" id="menuIds" value="' . $smlv1_id . '"' . $l1checked . '><span>' . $smlv1_heading . '&nbsp;&nbsp;<i class="fa fa-plus"></i></span>
                                    <ul>';

                            foreach ($sml2_rs->getResultArray() as $sml2_rows) {

                                $smlv3_id = $sml2_rows['sml2_id'];
                                $smlv3_heading = ucfirst($sml2_rows['menu_nm']);
                                $url_lv3 = $sml2_rows['url'];
                                $lv3chkid = $sml2_rows['mid'];
                                $l3checked = '';
                                if (strstr($lv3chkid, $smlv3_id)) $l3checked = ' checked="checked"';

                                $sml3_query = "SELECT 
                                                menu_nm, 
                                                substring(menu_id FROM 1 FOR 8) AS sml3_id, 
                                                url, 
                                                (
                                                    SELECT 
                                                    COALESCE(STRING_AGG(b.menu_id, ','), '0') 
                                                    FROM 
                                                    master.role_menu_mapping b 
                                                    WHERE 
                                                    LENGTH(b.menu_id) = 8 
                                                    AND b.role_master_id = $roleMid
                                                ) AS mid 
                                                FROM 
                                                master.menu 
                                                WHERE 
                                                substring(menu_id FROM 9) = '0000' 
                                                AND substring(menu_id FROM 1 FOR 6)::INTEGER = $smlv3_id 
                                                AND substring(menu_id FROM 7 FOR 2) <> '00' 
                                                AND display = 'Y' 
                                                AND menu_id IS NOT NULL 
                                                ORDER BY 
                                                priority, 
                                                substring(menu_id FROM 1 FOR 4)";

                                $sml3_rs = $this->query($sml3_query);

                                if ($sml3_rs->getNumRows() <= 0) {
                                   
                                    echo '<li>
                                            <input type="checkbox" name="menuIds" id="menuIds" value="' . $smlv3_id . '"' . $l3checked . '><span>' . $smlv3_heading . '&nbsp;&nbsp;<i class="fa fa-plus"></i></span>
                                            <ul>';
                                         
                                    foreach ($sml3_rs->getResultArray() as $sml3_rows) {

                                        $smlv4_id = $sml3_rows['sml3_id'];
                                        $smlv4_heading = ucfirst($sml3_rows['menu_nm']);
                                        $url_lv4 = $sml3_rows['url'];
                                        $lv4chkid = $sml3_rows['mid'];
                                        $l4checked = '';
                                        if (strstr($lv4chkid, $smlv4_id)) $l4checked = ' checked="checked"';

                                        $sml4_query = "SELECT 
                                                        menu_nm, 
                                                        substring(menu_id FROM 1 FOR 10) AS sml4_id, 
                                                        url, 
                                                        (
                                                            SELECT 
                                                            COALESCE(STRING_AGG(b.menu_id, ','), '0') 
                                                            FROM 
                                                            master.role_menu_mapping b 
                                                            WHERE 
                                                            LENGTH(b.menu_id) = 10 
                                                            AND b.role_master_id = $roleMid
                                                        ) AS mid 
                                                        FROM 
                                                        master.menu 
                                                        WHERE 
                                                        substring(menu_id FROM 11) = '00' 
                                                        AND substring(menu_id FROM 1 FOR 8)::INTEGER = $smlv4_id  
                                                        AND substring(menu_id FROM 9 FOR 2) <> '00' 
                                                        AND display = 'Y' 
                                                        AND menu_id IS NOT NULL 
                                                        ORDER BY 
                                                        priority, 
                                                        substring(menu_id FROM 1 FOR 4)";
                                       
                                        $sml4_rs = $this->query($sml4_query);
                                       

                                        if ($sml4_rs->getNumRows() > 0) {

                                            echo '<li>
                                                    <input type="checkbox" name="menuIds" id="menuIds" value="' . $smlv4_id . '"' . $l4checked . '><span>' . $smlv4_heading . '&nbsp;&nbsp;<i class="fa fa-plus"></i></span>
                                                    <ul>';

                                            foreach ($sml4_rs->getResultArray() as $sml4_rows) {
                                                $smlv5_id = $sml4_rows['sml4_id'];
                                                $smlv5_heading = ucfirst($sml4_rows['menu_nm']);
                                                $url_lv5 = $sml4_rows['url'];
                                                $lv5chkid = $sml4_rows['mid'];
                                                $l5checked = '';
                                                if (strstr($lv5chkid, $smlv5_id)) $l5checked = ' checked="checked"';

                                                $sml5_query = "SELECT 
                                                                menu_nm, 
                                                                substring(menu_id FROM 1 FOR 12) AS sml5_id, 
                                                                url, 
                                                                (
                                                                    SELECT 
                                                                    COALESCE(STRING_AGG(b.menu_id, ','), '0') 
                                                                    FROM 
                                                                    master.role_menu_mapping b 
                                                                    WHERE 
                                                                    LENGTH(b.menu_id) = 12 
                                                                    AND b.role_master_id = $roleMid
                                                                ) AS mid 
                                                                FROM 
                                                                master.menu 
                                                                WHERE 
                                                                substring(menu_id FROM 1 FOR 10)::INTEGER = $smlv5_id  
                                                                AND substring(menu_id FROM 11 FOR 2) <> '00' 
                                                                AND display = 'Y' 
                                                                AND menu_id IS NOT NULL 
                                                                ORDER BY 
                                                                priority, 
                                                                substring(menu_id FROM 1 FOR 4)";
                                                
                                               
                                                $sml5_rs = $this->query($sml5_query);
                                                
                                                if ($sml5_rs->getNumRows() > 0) {

                                                    echo '<li>
                                                                <input type="checkbox" name="menuIds" id="menuIds" value="' . $smlv5_id . '"' . $l5checked . '><span>' . $smlv5_heading . '&nbsp;&nbsp;<i class="fa fa-plus"></i></span>
                                                                <ul>';

                                                    foreach ($sml5_rs->getResultArray() as $sml5_rows) {
                                                        $smlv6_id = $sml5_rows['sml5_id'];
                                                        $smlv6_heading = ucfirst($sml5_rows['menu_nm']);
                                                        $url_lv6 = $sml5_rows['url'];
                                                        $lv6chkid = $sml5_rows['mid'];
                                                        $l6checked = '';
                                                        if (strstr($lv6chkid, $smlv6_id)) $l6checked = ' checked="checked"';

                                                        echo '<li>
                                                                    <input type="checkbox" name="menuIds" id="menuIds" value="' . $smlv6_id . '"' . $l6checked . '><span>' . $smlv6_heading . '</span>
                                                                </li>';
                                                    }
                                                    echo    '</ul>
                                                            </li>';
                                                } else {

                                                    echo '<li>
                                                                <input type="checkbox" name="menuIds" id="menuIds" value="' . $smlv5_id . '"' . $l5checked . '><span>' . $smlv5_heading . '</span>
                                                            </li>';
                                                }
                                            }
                                            echo    '</ul>
                                                </li>';
                                        } else {
                                            echo '<li>
                                                    <input type="checkbox" name="menuIds" id="menuIds" value="' . $smlv4_id . '"' . $l4checked . '><span>' . $smlv4_heading . '</span>
                                                </li>';
                                        }
                                    }
                                    echo   '</ul>
                                        </li>';
                                } else {
                                    echo '<li>
                                            <input type="checkbox" name="menuIds" id="menuIds" value="' . $smlv3_id . '"' . $l3checked . '><span>' . $smlv3_heading . '</span>
                                        </li>';
                                }
                            }
                            echo    '</ul>
                                </li>';
                        } else {
                            echo '<li>
                                    <input type="checkbox" name="menuIds" id="menuIds" value="' . $smlv1_id . '"' . $l1checked . '><span>' . $smlv1_heading . '</span>
                                </li>';
                        }
                    }
                    echo '</ul>
                    </li>';
                } else echo '<li><input type="checkbox" name="menuIds" id="menuIds" value="' . $menu_id . '"' . $mchecked . '><span>' . $mheading . '</span></li>';
            }
        } else {
            echo '<li><span>You don&#39;t have permission to view menus.</span></li>';
        }

        echo '</ul>
            </div>';
    }



    public function getAllRoles()
    {
        return $this->db->query('SELECT role_desc, id FROM master.role_master ORDER BY id, role_desc')->getResultArray();
    }

    public function getRoleById($id)
    {
        return $this->db->query('SELECT role_desc, id FROM master.role_master WHERE id = ?', [$id])->getResultArray();
    }


    public function getMenuByRoleId($role_master_id)
    {
        return $this->db->query("
                    SELECT 
                        a.menu_id,
                        b.menu_nm,
                        b.url,
                        b.old_smenu_id,
                        (
                            SELECT c.menu_nm 
                            FROM master.menu c 
                            WHERE SUBSTRING(c.menu_id FROM 1 FOR 2) = SUBSTRING(b.menu_id FROM 1 FOR 2) 
                            AND c.display = 'Y' 
                            AND SUBSTRING(c.menu_id FROM 3 FOR 10) = '0000000000'  LIMIT 1
                        ) AS main_menu 
                    FROM 
                        master.role_menu_mapping a
                    JOIN 
                        master.menu b 
                    ON 
                        b.menu_id = CASE 
                            WHEN LENGTH(a.menu_id) = 2 THEN CONCAT(a.menu_id, '0000000000') 
                            WHEN LENGTH(a.menu_id) = 4 THEN CONCAT(a.menu_id, '00000000') 
                            WHEN LENGTH(a.menu_id) = 6 THEN CONCAT(a.menu_id, '000000') 
                            WHEN LENGTH(a.menu_id) = 8 THEN CONCAT(a.menu_id, '0000') 
                            WHEN LENGTH(a.menu_id) = 10 THEN CONCAT(a.menu_id, '00') 
                            ELSE a.menu_id 
                        END 
                    WHERE 
                        a.role_master_id = ?
                        AND a.display = 'Y' 
                    ORDER BY 
                        a.menu_id;
                ", [$role_master_id])->getResultArray();
    }

    public function RoleList(){
        $builder = $this->db->table('master.role_master'); 
        $builder->select('id, role_desc')
                ->where('display', 'Y')
                ->orderBy('id');
        
        $roles = $builder->get()->getResultArray(); 
        return $roles;
    }
}
