<?php
namespace App\Models\Judicial;

use CodeIgniter\Model;

class DefectsModel extends Model{

    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
    }

    function get_da_defect($dairy_no){
        $builder6 = $this->db->table('main');
        $builder6->select("pet_name, res_name, TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') as dt, case_grp, fil_no, c_status, casetype_id, dacode");           
        $builder6->where('diary_no', $dairy_no);
        //echo $builder6->getCompiledSelect();
        //exit();
        $query6 = $builder6->get();         
        return $t_dist = $query6->getRowArray();        
    }

    function check_section_get($ucode){
        $builder = $this->db->table('master.users u');
        $builder->select('*');
        $builder->join('master.usersection us', 'u.section = us.id');
        $builder->where('u.usercode', $ucode);
        $builder->where('us.isda', 'Y');
        $builder->where('u.display', 'Y');        
        $query = $builder->get();
        return $result = $query->getResultArray();
    }

    function get_da_get($dairy_no)
    {
        $da=0;
        $builder = $this->db->table('main');
        $builder->select('dacode');        
        $builder->where('diary_no', $dairy_no);                
        $query = $builder->get();
        $result = $query->getRowArray();
        if(count($result)>0){
            $da=$result['dacode'];
        }
        return $da;
    }

    function chamber_listed_get($dairy_no){
        $if_chamber_listed = 0;
        $check_if_chamber_listed = "
            SELECT next_dt 
            FROM (
                SELECT diary_no, next_dt 
                FROM heardt 
                WHERE main_supp_flag IN (1,2) AND diary_no = ?
                UNION 
                SELECT diary_no, next_dt 
                FROM last_heardt 
                WHERE main_supp_flag IN (1,2) 
                AND (bench_flag IS NULL OR bench_flag = '') 
                AND diary_no = ?
            ) aa 
            WHERE next_dt IN (
                SELECT listing_date 
                FROM defective_chamber_listing 
                WHERE display = 'Y'
            )
        ";

        $query = $this->db->query($check_if_chamber_listed, [$dairy_no, $dairy_no]);

        if ($query->getNumRows() <= 0) {
            $check_if_listed = "
                SELECT MIN(next_dt) AS next_dt
                FROM (
                    SELECT diary_no, next_dt 
                    FROM heardt 
                    WHERE main_supp_flag IN (1,2) AND diary_no = ?
                    UNION 
                    SELECT diary_no, next_dt 
                    FROM last_heardt 
                    WHERE main_supp_flag IN (1,2) 
                    AND (bench_flag IS NULL OR bench_flag = '') 
                    AND diary_no = ?
                ) aa
            ";

            $result = $this->db->query($check_if_listed, [$dairy_no, $dairy_no])->getRow();
            $next_dt = $result ? $result->next_dt : null;

        } else {
            $if_chamber_listed = 1;
        }
        return $if_chamber_listed;

    }

    function softcopy_user_rs($ucode){

        $soft_copy_user = 0;
        $builder = $this->db->table('master.specific_role');
        $builder->select('DISTINCT(usercode)', false);
        $builder->where('display', 'Y');
        $builder->where('flag', 'S');
        $builder->where('usercode', $ucode);
        $query = $builder->get();
        $result = $query->getRowArray();
        if( !empty($result) && count($result) > 0){
            $soft_copy_user = 1;
        }
        return $soft_copy_user;
    }

    function check_if_reg_get($dairy_no)
    {
        $da=0;
        $builder = $this->db->table('main');
        $builder->select('fil_no');        
        $builder->where('diary_no', $dairy_no);                
        $query = $builder->get();
        return $result = $query->getRowArray();        
    }

    function sql_jk($dairy_no){
        $sql_res = 0;
        $builder = $this->db->table('obj_save');
        $builder->select('rm_dt,status');        
        $builder->where('diary_no', $dairy_no);
        $builder->where('display', 'Y');                
        $query = $builder->get();
        $result = $query->getResultArray();
        if(count($result) > 0){
            foreach($result as $row3){
                if ($row3['rm_dt'] == '0000-00-00 00:00:00' && ($row3['status'] == '0' || $row3['status'] == '7')) {
                    $sql_res = 1;
                }
            }
        }
        else{
            $sql_res = 1;
        }
        return $sql_res;
    }
    public function get_q_w($dairy_no)
    {
        $builder = $this->db->table('obj_save a');
        $builder->select("a.id, a.org_id, b.objdesc AS obj_name, rm_dt, remark, STRING_AGG(mul_ent, ',') AS mul_ent", false);
        $builder->join('master.objection b', 'a.org_id = b.objcode');
        $builder->where('diary_no', $dairy_no);
        $builder->where('a.display', 'Y');
        $builder->groupBy(['a.id', 'a.org_id', 'b.objdesc', 'rm_dt', 'remark']);
        $builder->orderBy('a.id');
        //echo $builder->getCompiledSelect();
        //   exit();
        $query = $builder->get();
        return $query->getResultArray();
    }

    function get_fil_trap($dairy_no)
    {
        $builder = $this->db->table('public.fil_trap');
        $builder->select("*");        
        $builder->where('diary_no', $dairy_no);
        //echo $builder->getCompiledSelect();
        //   exit();
        $query = $builder->get();
        return $query->getResultArray();
    }

    function get_obj_save($dairy_no)
    {
        $builder = $this->db->table('obj_save');
        $builder->select("*");        
        $builder->where('diary_no', $dairy_no);
        $builder->where('display', 'Y');
        //echo $builder->getCompiledSelect();
        //   exit();
        $query = $builder->get();
        return $query->getResultArray();
    }

    function get_rw($dairy_no){       
        $builder = $this->db->table('obj_save');
        $builder->select('DISTINCT(rm_dt)', false);
        $builder->where('diary_no', $dairy_no);
        $builder->where('display', 'Y');        
        $query = $builder->get();
        return $result = $query->getResultArray();       
    }

    function get_sql_obj(){
        $builder = $this->db->table('master.objection');
        $builder->select('objcode as org_id, objdesc as obj_name, sideflg as ci_cri');
        $builder->where('display', 'Y');
        $builder->where('sideflg', 'Y'); 
        $builder->orderBy('objcode');      
        $query = $builder->get();
        return $result = $query->getResultArray();
    }

    function get_sql_obj_search($strVal='',$se='',$allow_entry_in_registered_matter =''){        
        $builder = $this->db->table('master.objection');
        $builder->select('objcode as org_id, objdesc as obj_name, sideflg as ci_cri');
        $builder->where('display', 'Y');
        $builder->where('sideflg', 'Y'); 
        if($allow_entry_in_registered_matter ==1 ){
            $builder->where('objcode', 10193);      
        }
        else{
            if(empty($strVal) && empty($se))
            {
                $builder->orderBy('defect_code_main');
                $builder->orderBy('defect_code_sub');
                $builder->orderBy('objdesc');
            }
            else if($allow_entry_in_registered_matter ==0  && empty($strVal))
            {                
                $builder->orderBy('objcode');
            }
            else{
                if (!empty($_REQUEST['strVal'])) {
                    $builder->like('objdesc', $strVal);
                }
            }
        }
        //echo $builder->getCompiledSelect();
         //exit();        
        $query = $builder->get();
        return $result = $query->getResultArray();
    }

    function get_checkObjSaveEntries($dairy_no){        
        $builder = $this->db->table('obj_save');
        $builder->select('*');
        $builder->where('diary_no', $dairy_no);
        $builder->where('display', 'Y');
        $query = $builder->get();
        $result = $query->getResultArray();
        if(!empty($result) && count($result) > 0){
            return 'has_entries';
        }
        else{
            return 'no_entries'; 
        }
    }
    
    

}  