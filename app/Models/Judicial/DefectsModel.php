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

    function get_main_row($dairy_no){
        $builder = $this->db->table('main m');
        $builder->select('m.dacode, u.empid');
        $builder->join('master.users u', 'm.dacode = u.usercode', 'left');
        $builder->where('diary_no', $dairy_no);
        $query = $builder->get();
        return $result = $query->getRowArray();
    }

    function get_fil_trap_row($dairy_no)
    {
        $builder = $this->db->table('public.fil_trap');
        $builder->select("*");        
        $builder->where('diary_no', $dairy_no);
        //echo $builder->getCompiledSelect();
        //exit();
        $query = $builder->get();
        return $query->getRowArray();
    }

    function get_main_row_nested($dairy_no){
        $builder = $this->db->table('main m');
        $builder->select('m.dacode, u.empid');
        $builder->join('users u', 'm.dacode = u.usercode', 'left');
        $builder->where('m.diary_no', $diary_no);
        $query = $builder->get();
        return $result = $query->getRowArray();
    }

    function get_fil_trap_his_new($row)
    {
        $builder = $this->db->table('public.fil_trap_his');
        $builder->where([
            'diary_no'     => $row['diary_no'],
            'd_by_empid'   => $row['d_by_empid'],
            'd_to_empid'   => $d_to_empid,
            'disp_dt'      => $row['disp_dt'],
            'r_by_empid'   => $row['r_by_empid'],
            'rece_dt'      => $row['rece_dt'],
            'comp_dt'      => $row['comp_dt'],
            'disp_dt_seq'  => $row['disp_dt_seq'],
            'other'        => $row['other'],
            'scr_lower'    => $row['scr_lower']
        ]);

        $query = $builder->get();
        $result = $query->getRowArray();
    }
    function get_obj_save_sms($diary_no,$display,$rm_dt){

        $builder = $this->db->table('public.obj_save');
        $builder->where('diary_no', $diary_no);
        //$builder->where('docd_id', $doc_id);
        if(!empty($display)){
            $builder->where('display', $display);    
        }
        if(!empty($rm_dt)){
            $builder->where('rm_dt', $rm_dt);    
        }        
        return $count = $builder->countAllResults();

    }

    function get_obj_save_ia_sms($diary_no,$doc_id,$display,){

        $builder = $this->db->table('obj_save_ia');
        $builder->where('diary_no', $diary_no);
        //$builder->where('docd_id', $doc_id);
        if(!empty($display)){
            $builder->where('display', $display);    
        }
        if(!empty($doc_id)){
            $builder->where('docd_id', $doc_id);    
        }        
        return $count = $builder->countAllResults();
    }

    function get_advocate_mob($caveat_no){
        $builder = $this->db->table('public.caveat_advocate a');
        $builder->select('b.mobile');
        $builder->join('master.bar b', 'a.advocate_id = b.bar_id');
        $builder->where('a.caveat_no', $caveat_no);
        $builder->where('a.display', 'Y');
        $builder->where('a.pet_res', 'P');
        echo $builder->getCompiledSelect();
         exit();
        $query = $builder->get();
        return $result = $query->getRowArray(); 
    }

    function get_caveat_info($caveat_no){
        $builder = $this->db->table('public.caveat');
        $builder->select('pet_name,res_name');        
        $builder->where('caveat_no', $caveat_no);        
        $query = $builder->get();
        return $result = $query->getRowArray(); 
    }
    function get_pet_res($diary_no){
        $builder = $this->db->table('main');
        $builder->select('pet_name,res_name');        
        $builder->where('diary_no', $diary_no);        
        $query = $builder->get();
        return $result = $query->getRowArray(); 
    }
    function get_sql_ia($doc_id)
    {
        $builder = $this->db->table('public.docdetails');
        $builder->select('docnum,docyear');        
        $builder->where('docd_id', $doc_id);        
        $query = $builder->get();
        return $result = $query->getRowArray(); 
    }

    function get_sqlparty($diary_no)
    {
        $builder = $this->db->table('public.party');
        $builder->select('contact');        
        $builder->where('diary_no', $diary_no);
        $builder->where('pet_res', 'P');        
        $query = $builder->get();
        return $result = $query->getResultArray(); 
    }

    function get_advocate_mob_new($doc_id) 
    {
        $builder = $this->db->table('public.docdetails d');
        $builder->select('b.mobile');
        $builder->join('master.bar b', 'd.advocate_id = b.aor_code');
        if(!empty($doc_id)){
            $builder->where('d.docd_id', $doc_id);
        }
        $query = $builder->get();
        return $result = $query->getResultArray();
    }

    function get_advocate_mob_adv($diary_no,$display,$pet_res) 
    {        
        $builder = $this->db->table('advocate a');
        $builder->select('b.mobile');
        $builder->join('master.bar b', 'a.advocate_id = b.bar_id');
        $builder->where([
            'a.diary_no' => $diary_no,
            'display'    => 'Y',
            'pet_res'    => 'P'
        ]);            
        $query = $builder->get();
        $result = $query->getResultArray();
    }

    function get_result_casetype($dairy_no)
    {
        $builder = $this->db->table('public.main m');
        $builder->select("m.pet_name, m.res_name, TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS dt, m.case_grp, m.fil_no, m.c_status, m.casetype_id");
        $builder->where('m.diary_no', $dairy_no);
        $query = $builder->get();
        return $result = $query->getRowArray();
    }

    function get_check_section_rs($ucode){
        $builder = $this->db->table('master.users u'); // Use schema if needed
        $builder->select('*');
        $builder->join('master.usersection us', 'u.section = us.id');
        $builder->where([
            'u.usercode' => $ucode,
            'us.isda'    => 'Y',
            'u.display'  => 'Y'
        ]);
        $query = $builder->get();
        return $result = $query->getRowArray();
    }


    function get_rs_res($dairy_no)
    {
        $builder = $this->db->table('public.main m');
        $builder->select("
            m.pet_name || ' VS ' || m.res_name AS cause_title,
            TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS diary_date,
            MIN(o.save_dt) AS defect_date,
            MIN(DATE(o.save_dt)) AS df,
            o.display
        ");
        $builder->join('public.obj_save o', 'm.diary_no = o.diary_no');
        $builder->where('m.diary_no', $dairy_no);
        $builder->where('o.display', 'Y');
        $builder->groupBy([
                            'm.pet_name',
                            'm.res_name',
                            'm.diary_no_rec_date',
                            'o.display'
                        ]);
        $query = $builder->get();
        return $result = $query->getResultArray();
    }

    function get_no_of_days_qr($c_date)
    {
        $builder = $this->db->table('master.defect_policy');
        $c_date = date('Y-m-d'); // or from POST/GET
        $builder->select('no_of_days');
        $builder->where('master_module', '1');
        $builder->groupStart()
            ->where("'$c_date' BETWEEN from_date AND to_date", null, false) // ✅ FIXED: disable escaping
            ->orGroupStart()
                ->where('from_date <=', $c_date)
                ->where('to_date IS NULL', null, false) // ✅ already correct
            ->groupEnd()
        ->groupEnd();
        $query = $builder->get();
        $result = $query->getRowArray();
    }

    function get_holiday($date){
        $builder = $this->db->table('master.holidays');
        $builder->select('hdate');
        $builder->where('hdate', $date);
        $builder->where('emp_hol <>', 0); // PostgreSQL supports '<>' for not equals

        $query = $builder->get();
        $result = $query->getRowArray();
    }

    function get_ia($diary_no)
    {
        $sql = "
                SELECT a.*, b.docdesc 
                FROM (
                    SELECT doccode, doccode1, docnum, docyear, filedby, other1, ent_dt 
                    FROM public.docdetails 
                    WHERE doccode = '8' 
                    AND diary_no = :diary_no: 
                    AND iastat = 'P' 
                    AND display = 'Y'
                ) a 
                JOIN master.docmaster b 
                ON a.doccode = b.doccode 
                AND a.doccode1 = b.doccode1 
                AND b.display = 'Y'
                ORDER BY CASE WHEN b.doccode1 = 28 THEN 1 ELSE b.doccode1 END
            ";
            $query = $this->db->query($sql, ['diary_no' => $diary_no]);
            return $result = $query->getResultArray();
    }

    function get_causetitle_qr($diary_no)
    {
        $builder = $this->db->table('public.main');
        $builder->select('pet_name,res_name,pno,rno');        
        $builder->where('diary_no', $diary_no);                        
        $query = $builder->get();
        return $result = $query->getRowArray();
    }

    function get_res_wdn($w_wo_dn){
        $sql = "
                SELECT zz.*
                FROM (
                    SELECT a.*, b.c, b.dt
                    FROM (
                        SELECT DISTINCT 
                            id, rm_dt, status, a.diary_no, org_id AS objcode, 
                            pet_name, res_name, a.remark, 
                            to_char(b.diary_no_rec_date, 'YYYY-MM-DD HH24:MI:SS') AS fdt, 
                            save_dt, mul_ent, objdesc AS obj_name, name
                        FROM obj_save a
                        JOIN main b ON a.diary_no = b.diary_no
                        JOIN master.objection c ON c.objcode = a.org_id
                        JOIN master.users u ON u.usercode = a.usercode
                        WHERE rm_dt IS NULL
                        AND c_status = 'P'
                        AND (c.display = 'Y' OR (c.display = 'N' AND c.objcode < 10075))
                        AND status IN ('0', '11', '7')
                        AND a.display = 'Y'
                        AND b.fixed NOT IN ('9', '10')
                        $w_wo_dn
                    ) a
                    JOIN (
                        SELECT 
                            COUNT(org_id) AS c, a.diary_no, b.fil_no, rm_dt, 
                            MIN(date(save_dt)) AS dt
                        FROM obj_save a
                        JOIN main b ON a.diary_no = b.diary_no
                        JOIN master.objection c ON c.objcode = a.org_id
                        WHERE rm_dt IS NULL
                        AND c_status = 'P'
                        AND (c.display = 'Y' OR (c.display = 'N' AND c.objcode < 10075))
                        AND status IN ('0', '11', '7')
                        AND a.display = 'Y'
                        AND b.fixed NOT IN ('9', '10')
                        $w_wo_dn
                        GROUP BY a.diary_no, b.fil_no, rm_dt
                    ) b ON a.diary_no = b.diary_no
                ) zz
                ORDER BY id
                ";                
                $query = $this->db->query($sql);
                return $result = $query->getResultArray();


    }

    function get_efiling_rs($empid) 
    {
        $builder = $this->db->table('public.fil_trap a');
        $builder->select("
            ec.efiling_no,
            a.uid,
            a.diary_no,
            d_by_empid,
            d_to_empid,
            disp_dt,
            remarks,
            e.name AS d_by_name,
            e.empid,
            pet_name,
            res_name,
            rece_dt,
            nature,
            TO_CHAR(h.next_dt, 'DD-MM-YYYY') AS next_dt,
            h.main_supp_flag,
            CASE 
                WHEN h.board_type = 'C' THEN 'CHAMBER'
                WHEN h.board_type = 'J' THEN 'COURT'
                ELSE 'REGISTRAR'
            END AS board_type
        ");
        $builder->join('public.main b', 'a.diary_no = b.diary_no', 'left');
        $builder->join('master.users e', 'e.usercode = b.dacode', 'left');
        $builder->join('public.heardt h', 'b.diary_no = h.diary_no', 'left');
        $builder->join('public.efiled_cases ec', "ec.diary_no = b.diary_no AND ec.display = 'Y'", 'left');
        $builder->where('1=1', null, false);
        $builder->where('e.empid', 1);
        $builder->where('b.c_status', 'P');
        $builder->whereIn('remarks', ['FDR -> SCR']);
        $builder->where('comp_dt IS NULL', null, false); // Raw IS NULL condition
        $builder->orderBy('disp_dt', 'DESC');
        //$builder->limit(5);
        $query = $builder->get();
        return $result = $query->getResultArray();
    }


}  