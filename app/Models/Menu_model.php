<?php
namespace App\Models;
use CodeIgniter\Model;
class Menu_model extends Model{
    //protected $table = 'master.users';
    protected $db;

    public function __construct()
    {
        parent::__construct();
        //$this->db = db_connect();
        $this->db = \Config\Database::connect();
    }
    public function get_Main_menus($q_usercode) {
        //if want all manu find and removed AND cast(substr(m.menu_id,1,2) as integer) =01
        $query = "select distinct m.menu_nm,substr(m.menu_id,1,2) as menu_id,m.url as url, m.icon, m.old_smenu_id from master.user_role_master_mapping urmm inner join master.role_master rm on urmm.role_master_id=rm.id
inner join master.role_menu_mapping rmm on rm.id=rmm.role_master_id inner join master.menu m on m.menu_id like 
 CONCAT(substr(rmm.menu_id,1,2), '%') 
where substr(m.menu_id,3)='0000000000' and m.menu_id is not null and urmm.display='Y' and rm.display='Y' and rmm.display='Y' and m.display='Y' 
and urmm.usercode=$q_usercode  order by m.menu_nm";
        $query = $this->db->query($query);
        if($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        }else{return 0;}
    }
    public function get_sub_menus($q_usercode,$menu_id) {
        //if want all manu find and removed AND cast(substr(m.menu_id,1,4) as integer) =0101
        $query = "select distinct m.menu_nm,substr(m.menu_id,1,4) as sml1_id,m.url, m.old_smenu_id from master.user_role_master_mapping urmm inner join master.role_master rm on urmm.role_master_id=rm.id
                    inner join master.role_menu_mapping rmm on rm.id=rmm.role_master_id inner join master.menu m on 
                    m.menu_id like CONCAT(substr(rmm.menu_id,1,4), '%') 
                    where substr(m.menu_id,5)='00000000' AND cast(substr(m.menu_id,1,2) as integer) =$menu_id AND substr(m.menu_id,3,2) <>'00' and m.menu_id is not null and urmm.display='Y' and rm.display='Y' and rmm.display='Y' and m.display='Y' 
                    and urmm.usercode=$q_usercode  order by m.menu_nm";
        $query = $this->db->query($query);
        if($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        }else{return 0;}
    }
    public function get_sub_menus_two($q_usercode,$smlv1_id) {
        //if want all manu find and removed AND cast(substr(m.menu_id,1,6) as integer)=010101
        $query = "select distinct m.menu_nm,substr(m.menu_id,1,6) as sml2_id,m.url, m.old_smenu_id from master.user_role_master_mapping urmm inner join master.role_master rm on urmm.role_master_id=rm.id
                    inner join master.role_menu_mapping rmm on rm.id=rmm.role_master_id inner join master.menu m on m.menu_id 
                    like CONCAT(substr(rmm.menu_id,1,6), '%')
                    where substr(m.menu_id,7)='000000' AND cast(substr(m.menu_id,1,4) as integer)=$smlv1_id AND substr(m.menu_id,5,2) <>'00' 
                    AND m.menu_id is not null and urmm.display='Y' and rm.display='Y' and rmm.display='Y' and m.display='Y' 
                    and urmm.usercode=$q_usercode  order by m.menu_nm";

        $query = $this->db->query($query);
        if($query->getNumRows() >= 1) {
            $result = $query->getResultArray(); //echo '<pre>';print_r($result);exit();
            return $result;
        }else{return 0;}
    }
    public function get_sub_menus_three($q_usercode,$smlv3_id) {
        $query = "select distinct m.menu_nm,substr(m.menu_id,1,8) as sml3_id,m.url, m.old_smenu_id from master.user_role_master_mapping urmm inner join master.role_master rm on urmm.role_master_id=rm.id
                            inner join master.role_menu_mapping rmm on rm.id=rmm.role_master_id inner join master.menu m on m.menu_id 
                            like CONCAT(substr(rmm.menu_id,1,8), '%')            
                            where substr(m.menu_id,9)='0000' AND cast(substr(m.menu_id,1,6) as integer)=$smlv3_id AND substr(m.menu_id,7,2) <>'00' AND m.menu_id is not null and urmm.display='Y' and rm.display='Y' and rmm.display='Y' and m.display='Y' and urmm.usercode=$q_usercode order by m.menu_nm";

        $query = $this->db->query($query);
        if($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        }else{return 0;}
    }
    public function get_sub_menus_four($q_usercode,$smlv4_id) {
        $query = "select distinct m.menu_nm,substr(m.menu_id,1,10) as sml4_id,m.url, m.old_smenu_id from master.user_role_master_mapping urmm inner join master.role_master rm on urmm.role_master_id=rm.id
                            inner join master.role_menu_mapping rmm on rm.id=rmm.role_master_id inner join master.menu m 
                            on m.menu_id like CONCAT(substr(rmm.menu_id,1,10), '%') 
                            where substr(m.menu_id,11)='00' AND cast(substr(m.menu_id,1,8) as integer)=$smlv4_id AND substr(m.menu_id,9,2) <>'00' 
                            AND m.menu_id is not null and urmm.display='Y' and rm.display='Y' and rmm.display='Y' 
                            and m.display='Y' and urmm.usercode=$q_usercode order by m.menu_nm";

        $query = $this->db->query($query);
        if($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        }else{return 0;}
    }
    public function get_sub_menus_five($q_usercode,$smlv5_id) {
        $query = "select distinct m.menu_nm,substr(m.menu_id,1,12) as sml5_id,m.url, m.old_smenu_id from master.user_role_master_mapping urmm inner join master.role_master rm on urmm.role_master_id=rm.id
                                            inner join master.role_menu_mapping rmm on rm.id=rmm.role_master_id inner join master.menu m 
                                            on m.menu_id like CONCAT(substr(rmm.menu_id,1,12), '%') 
                                            where cast(substr(m.menu_id,1,10) as integer)=$smlv5_id AND substr(m.menu_id,11,2) <>'00' AND m.menu_id is not null and urmm.display='Y' 
                                            and rm.display='Y' and rmm.display='Y' and m.display='Y' and urmm.usercode=$q_usercode 
                                            order by m.menu_nm";

        $query = $this->db->query($query);
        if($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            //echo '<pre>';print_r($result);exit();
            return $result;
        }else{return 0;}
    }


    public function get_action_permission_allotment() {
        $query = $this->db->table('master.users a')
            ->select('a.usercode, a.name, a.display, a.empid, b.type_name, c.section_name, a.attend')
            ->join('master.usertype b', 'a.usertype = b.id', 'left')
            ->join('master.usersection c', 'a.section = c.id', 'left')
            ->where('a.display', 'Y')
            ->orderBy('a.usercode')
            ->get();
        if($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        }else{return false ;}
    }
    public function get_menu_list() {
        $query = $this->db->table('master.menu')
            ->select('*')
            ->where('menu_id is not null')
            ->where('display', 'Y')
            ->orderBy('substr(menu_id,1,2)')
            ->orderBy('substr(menu_id,3,2)')
            ->orderBy('substr(menu_id,5,2)')
            ->orderBy('substr(menu_id,7,2)')
            ->orderBy('substr(menu_id,9,2)')
            ->orderBy('substr(menu_id,11,2)')
            ->orderBy('priority')
            ->get();
        if($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        }else{return false ;}
    }
    public function get_role_master_with_role_menu_mapping_list() {
        $query = $this->db->table('master.role_master a, master.role_menu_mapping b, master.menu c')
            ->select('a.role_desc,c.menu_nm,c.url,c.old_smenu_id,c.menu_id,c.display')
            ->where("a.id=b.role_master_id AND (b.menu_id=substr(c.menu_id,1,2) OR b.menu_id=substr(c.menu_id,1,4) OR b.menu_id=substr(c.menu_id,1,6) OR b.menu_id=substr(c.menu_id,1,8)) AND c.display='Y'")
            ->orderBy('a.role_desc','c.menu_id')
            ->get();
        if($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        }else{return false ;}
    }
    public function get_menu_by_id($menu_id) {
        $builder = $this->db->table("master.menu");
        $builder->select("*");
        $builder->WHERE('id',$menu_id);
        $query =$builder->get(1);
        if($query->getNumRows() >= 1) {
            $result = $query->getRow();
            return $result;
        }else{return false;}

    }

    public function getJudgeDetail($jcode)
    {
        $builder = $this->db->table('master.judge');
        $builder->select("DISTINCT jname, CASE WHEN jtype = 'R' THEN first_name ELSE '' END AS rname", false);
        $builder->where('display', 'Y');
        $builder->whereIn('jtype', ['J', 'R']);
        $builder->where('is_retired', 'N');
        $builder->where('jcode', $jcode);

        $query = $builder->get();
        return $result = $query->getRowArray();
    }
}
