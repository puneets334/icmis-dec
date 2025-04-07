<?php

namespace App\Models\Filing;

use CodeIgniter\Model;

class RegistrationModel extends Model
{

    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
    }


    public function get_main($diary_no){

        
        $builder = $this->db->table("main");
        $builder->select("pet_name,res_name,casetype_id,fil_no,c_status");
        $builder->where('diary_no',$diary_no);

         
        $builder1 = $this->db->table("main_a");
        $builder1->select("pet_name,res_name,casetype_id,fil_no,c_status");
        $builder1->where('diary_no',$diary_no);

        $query = $builder->union($builder1)->get();
 
        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function get_mul_category($diary_no){

        $tbl = is_table_a('mul_category');

        $builder = $this->db->table("public.".$tbl);
        $builder->select("*");
        $builder->where('diary_no',$diary_no);
        $builder->where('display','Y');
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getNumRows();
        }else{
            return 0;
        }
        
    }

    public function get_casetype($casecode){
        $builder = $this->db->table("master.casetype");
        $builder->select("short_description,cs_m_f");
        $builder->where('casecode',$casecode);
        $builder->where('display','Y');
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function get_obj_save($diary_no){

        $tbl = is_table_a('obj_save');

        $builder = $this->db->table("public.".$tbl);
        $builder->select("count(id)");
        $builder->where('diary_no',$diary_no);
        $builder->where('display','Y');
        $builder->where('rm_dt',NULL);
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function get_docdetails_docmaster($diary_no,$casetype_id){

        $tbl_docdetails = is_table_a('docdetails');

        $builder = $this->db->table("public.".$tbl_docdetails." a");
        $builder->select("docnum, docyear, docdesc, other1");
        $builder->join("master.docmaster b", "a.doccode = b.doccode AND a.doccode1 = b.doccode1");
        $builder->where('diary_no',$diary_no);
        $builder->where('iastat','P');
        $builder->where('a.display','Y');
        $builder->where('b.display','Y');

        if($casetype_id==1 || $casetype_id==2){
            $builder->where('(not_reg_if_pen=1 or not_reg_if_pen=2)');
        }else{
            $builder->where('not_reg_if_pen','1');
        }

        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

    public function get_causetitle($diary_no){

         
        $builder = $this->db->table("main");
        $builder->select("pet_name,res_name,pno,rno");
        $builder->where('diary_no',$diary_no);

        $builder1 = $this->db->table("main_a");
        $builder1->select("pet_name,res_name,pno,rno");
        $builder1->where('diary_no',$diary_no);

        $query = $builder->union($builder1)->get();

        //$query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function get_order_dt($diary_no,$table){

        $tbl_crm = is_table_a('case_remarks_multiple');

        $builder = $this->db->table("public.$table h");
        $builder->select("h.diary_no, to_char(h.next_dt, 'DD-MM-YYYY') AS order_dt, string_agg(crm.r_head::text, ',') AS Disp_Remarks");
        $builder->join("public.".$tbl_crm." crm", " cast(crm.diary_no as BIGINT) = cast(h.diary_no as BIGINT) AND crm.cl_date = h.next_dt");
        $builder->join("master.case_remarks_head crh", "crh.sno = crm.r_head AND (crh.display = 'Y' OR crh.display IS NULL)");
        $builder->where("h.diary_no", $diary_no);
        $builder->where("h.clno !=", 0);
        $builder->where("h.brd_slno !=", 0);
        $builder->where("h.brd_slno is not null");
        $builder->where("h.roster_id !=", 0);
        $builder->where("h.roster_id is not null");
        if($table=='last_heardt'){
            $builder->where("(h.bench_flag is null or h.bench_flag='')");
        }
        $builder->whereIn("main_supp_flag", [1, 2]);
        $builder->where("h.next_dt <=", date('Y-m-d')); // Assuming CURRENT_DATE is today's date

        if($table=='last_heardt'){
            $str = "h.next_dt = (SELECT MAX(next_dt) FROM $table b WHERE b.diary_no = h.diary_no AND b.clno != 0 AND b.brd_slno != 0 AND b.brd_slno IS NOT NULL AND b.roster_id != 0 AND b.roster_id IS NOT NULL AND (b.bench_flag is null or b.bench_flag='') AND main_supp_flag IN (1, 2))";
        }else{
            $str = "h.next_dt = (SELECT MAX(next_dt) FROM $table b WHERE b.diary_no = h.diary_no AND b.clno != 0 AND b.brd_slno != 0 AND b.brd_slno IS NOT NULL AND b.roster_id != 0 AND b.roster_id IS NOT NULL AND main_supp_flag IN (1, 2))";
        }

        $builder->where($str);
        $builder->whereIn("crm.r_head", [181, 182, 3, 183, 184, 1, 41, 176, 177, 178, 27, 196, 200, 201]);
        $builder->groupBy("h.next_dt, h.diary_no");
        
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function get_lowerct($diary_no,$casetype_id){

        $tbl_lowerct = is_table_a('lowerct');
        $tbl_main = is_table_a('main');

        $transfer_petition = '';
        $transfer_state = '';
        if($casetype_id=='7' || $casetype_id=='8'){
            $transfer_petition=" join transfer_to_details ttd on ttd.lowerct_id=a.lower_court_id and ttd.display='Y'";
            $transfer_state=",transfer_case_type,transfer_case_no,transfer_case_year,transfer_state,transfer_district,transfer_court";
        }

        $builder = $this->db->table("public.".$tbl_lowerct." a");
        $builder->select("lct_dec_dt, l_dist, ct_code, l_state, name");
        $builder->select("(CASE WHEN ct_code = 3 THEN (SELECT NAME FROM master.state s WHERE s.id_no = a.l_dist AND display = 'Y') ELSE 
                (SELECT agency_name FROM master.ref_agency_code c WHERE c.cmis_state_id = a.l_state AND c.id = a.l_dist AND is_deleted = 'f') END) AS agency_name", false);
        $builder->select("lct_casetype, lct_caseno, lct_caseyear");
        $builder->select("(CASE WHEN ct_code = 4 THEN (SELECT skey FROM master.casetype ct WHERE ct.display = 'Y' AND ct.casecode = a.lct_casetype) ELSE 
                (SELECT type_sname FROM master.lc_hc_casetype d WHERE d.lccasecode = a.lct_casetype AND d.display = 'Y') END) AS type_sname", false);
        $builder->select("lower_court_id,is_order_challenged $transfer_state");
        $builder->join("master.state f", "a.l_state = f.id_no AND f.display = 'Y'", "left");
        $builder->join("public.".$tbl_main." e", "e.diary_no = a.diary_no");

        if($transfer_petition!='' || $transfer_petition!=NULL){
        $builder->join("public.transfer_to_details ttd", "ttd.lowerct_id = a.lower_court_id AND ttd.display='Y'");
        }

        $builder->where("a.diary_no", $diary_no);
        $builder->where("lw_display", "Y");
        $builder->where("c_status", "P");
        $builder->where("is_order_challenged", "Y");
        $builder->orderBy("a.lower_court_id");

        $query =$builder->get(); 

        $get_lowerct1 = [];

        if($casetype_id=='7' || $casetype_id=='8'){

            $get_lowerct = $query->getResultArray();

            foreach($get_lowerct as $get_lowerct_val):

                if($get_lowerct_val['transfer_court']!=0){
                    $res_court = $this->get_m_from_court($get_lowerct_val['transfer_court']);
                    $res_state = $this->get_state($get_lowerct_val['transfer_state']);

                    $get_lowerct_val['res_court'] = $res_court[0]['court_name'];
                    $get_lowerct_val['res_state'] = $res_state[0]['name'];

                    if($get_lowerct_val['transfer_court']=='3'){
                    $res_district = $this->get_state($get_lowerct_val['transfer_district']);
                    $get_lowerct_val['res_district'] = $res_district[0]['name'];
                    }else{
                        $res_district = $this->get_ref_agency_code($get_lowerct_val['transfer_district']);
                        $get_lowerct_val['res_district'] = $res_district[0]['agency_name'];
                    }

                    if($get_lowerct_val['transfer_court']=='4'){
                        $r_case_type = $this->get_casetype_skey($get_lowerct_val['transfer_case_type']);
                        $get_lowerct_val['r_case_type'] = $r_case_type[0]['skey'];
                    }else{
                        $r_case_type = $this->get_lc_hc_casetype($get_lowerct_val['transfer_case_type']);
                        $get_lowerct_val['r_case_type'] = $r_case_type[0]['type_sname'];
                    }
                }

                $get_lowerct1[] = $get_lowerct_val;

            endforeach;

        }

        if($get_lowerct1) {
            return $get_lowerct1;
        }
        elseif($query->getNumRows() >= 1) {
            return $get_lowerct = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function get_m_from_court($transfer_court){

        $builder = $this->db->table("master.m_from_court");
        $builder->select("court_name");
        $builder->where('id',$transfer_court);
        $builder->where('display','Y');
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function get_state($transfer_state){

        $builder = $this->db->table("master.state");
        $builder->select("name");
        $builder->where('id_no',$transfer_state);
        $builder->where('display','Y');
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function get_ref_agency_code($transfer_district){

        $builder = $this->db->table("master.ref_agency_code");
        $builder->select("agency_name");
        $builder->where('id',$transfer_district);
        $builder->where('is_deleted','f');
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function get_casetype_skey($transfer_case_type){

        $builder = $this->db->table("master.casetype");
        $builder->select("skey");
        $builder->where('display','Y');
        $builder->where('casecode',$transfer_case_type);
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function get_lc_hc_casetype($transfer_case_type){

        $builder = $this->db->table("master.lc_hc_casetype");
        $builder->select("type_sname");        
        $builder->where('lccasecode',$transfer_case_type);
        $builder->where('display','Y');
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function get_kounter($year,$hd_casetype_id){

        $builder = $this->db->table("master.kounter");
        $builder->select("knt");
        $builder->where('year',$year);
        $builder->where('casetype_id',$hd_casetype_id);
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function get_last_reg_no($year,$hd_casetype_id){

        $tbl_main = is_table_a('main');

        $builder = $this->db->table("public.".$tbl_main);
        $builder->select("fil_no");
        $builder->where('casetype_id',$hd_casetype_id);
        $builder->where('extract(year from fil_dt)',$year);
        $builder->orderBy('fil_no','DESC');
        $builder->limit(1);
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function insert_kounter($ins_arr){

        $builder = $this->db->table("master.kounter");
        $query = $builder->insert($ins_arr);

        if($query) {
            return 1;
        }else
        {
            return 0;
        }
        
    }

    public function check_reg_no($reg_no,$year,$hd_casetype_id){

        $tbl_main = is_table_a('main');

        $builder = $this->db->table("public.".$tbl_main);
        $builder->select("*");
        $builder->like('fil_no',$reg_no);
        $builder->where('casetype_id',$hd_casetype_id);
        $builder->where('extract(year from fil_dt)',$year);
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getNumRows();
        }else{
            return 0;
        }
        
    }

    public function update_kounter($upd_arr,$year,$hd_casetype_id){
        
        $builder = $this->db->table('master.kounter');

        $builder->where('year',$year);
        $builder->where('casetype_id',$hd_casetype_id);

        $query = $builder->update($upd_arr);

        if($query) {
            return 1;
        }else
        {
            return 0;
        }
        
    }

    public function get_lowerct_by_lower_court_id($lower_court_id,$is_order_challenged){

        $tbl_lowerct = is_table_a('lowerct');

        $builder = $this->db->table("public.".$tbl_lowerct);
        $builder->select("count(lower_court_id)");
        $builder->where('lower_court_id',$lower_court_id);
        $builder->where('is_order_challenged',$is_order_challenged);
        $builder->where('lw_display','Y');
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function update_lowerct($upd_arr,$lower_court_id){
        
        $builder = $this->db->table('public.lowerct');

        $builder->where('lower_court_id',$lower_court_id);
        $builder->where('lw_display','Y');

        $query = $builder->update($upd_arr);

        if($query) {
            return 1;
        }else
        {
            return 0;
        }
    }

    public function insert_registered_cases($ins_arr)
    {
        $builder = $this->db->table("public.registered_cases");
        $query = $builder->insert($ins_arr);

        if($query) {
            return 1;
        }else
        {
            return 0;
        }
        
    }

    public function get_cur_date($diary_no){

        $tbl_main = is_table_a('main');

        $builder = $this->db->table("public.".$tbl_main);
        $builder->select("fil_no, extract(year from fil_dt) as fil_dt");
        $builder->where('diary_no',$diary_no);
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function get_main_casetype_history($diary_no,$old_registration_number,$old_registration_year,$new_registration_number,$new_registration_year,$order_date){

        $tbl = is_table_a('main_casetype_history');

        $builder = $this->db->table("public.".$tbl);
        $builder->select("count(id)");
        $builder->where('diary_no',$diary_no);
        $builder->where('old_registration_number',$old_registration_number);
        $builder->where('old_registration_year',$old_registration_year);
        $builder->where('new_registration_number',$new_registration_number);
        $builder->where('new_registration_year',$new_registration_year);
        $builder->where('order_date',$order_date);
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function insert_main_casetype_history($ins_arr){

        $builder = $this->db->table("public.main_casetype_history");
        $query = $builder->insert($ins_arr);

        if($query) {
            return 1;
        }else
        {
            return 0;
        }
        
    }

    public function get_reg_no_display_from_main($diary_no){

        $tbl_lowerct = is_table_a('lowerct');
        $tbl_main = is_table_a('main');
        $tbl_mch = is_table_a('main_casetype_history');

        $builder = $this->db->table("public.".$tbl_lowerct);
        $builder->select("lct_casetype,lct_caseno as caseNumber,lct_caseyear as caseYear",false);
        $builder->where("diary_no",$diary_no);
        $builder->where("ct_code",'4');

        $builder_m = $this->db->table("public.".$tbl_main." m");
        $builder_m->select("m.reg_no_display");
        $builder_m->where("(char_length(m.active_fil_no) > 10");
        $builder_m->where("(active_casetype_id = ld.lct_casetype");
        $builder_m->where('CAST(ld.caseNumber AS VARCHAR) BETWEEN (split_part(split_part(active_fil_no, '."'".'-'."'".', 2),'."'".'-'."'".',-1))');
        $builder_m->where("(split_part(active_fil_no, '-', -1))");
        $builder_m->where("active_reg_year = ld.caseYear))");
        $builder_m->orWhere("((char_length(m.active_fil_no) = 9");
        $builder_m->where("(active_casetype_id = ld.lct_casetype");
        $builder_m->where('CAST(ld.caseNumber AS VARCHAR) = (split_part(split_part(active_fil_no, '."'".'-'."'".', 2),'."'".'-'."'".',-1))');
        $builder_m->where("active_reg_year = ld.caseYear)))");

        $query1 =$builder_m->fromSubquery($builder,'ld');

        $builder1 = $this->db->table("public.".$tbl_lowerct);
        $builder1->select("lct_casetype,lct_caseno as caseNumber,lct_caseyear as caseYear",false);
        $builder1->where("diary_no",$diary_no);
        $builder1->where("ct_code",'4');

        $builder_m1 = $this->db->table("public.".$tbl_mch." mch,main");
        $builder_m1->select("main.reg_no_display");
        $builder_m1->where("mch.diary_no = main.diary_no");
        $builder_m1->where("((char_length(mch.old_registration_number) > 10");
        $builder_m1->where("(ref_old_case_type_id = ld.lct_casetype");
        $builder_m1->where('CAST(ld.caseNumber AS VARCHAR) BETWEEN (split_part(split_part(old_registration_number, '."'".'-'."'".', 2),'."'".'-'."'".',-1))');
        $builder_m1->where("(split_part(old_registration_number, '-', -1))");
        $builder_m1->where("old_registration_year = ld.caseYear))");
        $builder_m1->orWhere("((char_length(mch.old_registration_number) = 9");
        $builder_m1->where("(ref_old_case_type_id = ld.lct_casetype");
        $builder_m1->where('CAST(ld.caseNumber AS VARCHAR) = (split_part(split_part(old_registration_number, '."'".'-'."'".', 2),'."'".'-'."'".',-1))');
        $builder_m1->where("old_registration_year = ld.caseYear)))");
        $builder_m1->orWhere("(char_length(mch.new_registration_number) > 10");
        $builder_m1->where("(ref_new_case_type_id = ld.lct_casetype");
        $builder_m1->where('CAST(ld.caseNumber AS VARCHAR)BETWEEN (split_part(split_part(new_registration_number, '."'".'-'."'".', 2),'."'".'-'."'".',-1))');
        $builder_m1->where("(split_part(new_registration_number, '-', -1))");
        $builder_m1->where("new_registration_year = ld.caseYear))");
        $builder_m1->orWhere("((char_length(mch.new_registration_number) = 9");
        $builder_m1->where("(ref_new_case_type_id = ld.lct_casetype");
        $builder_m1->where('CAST(ld.caseNumber AS VARCHAR) = (split_part(split_part(new_registration_number, '."'".'-'."'".', 2),'."'".'-'."'".',-1))');
        $builder_m1->where("new_registration_year = ld.caseYear))))");

        $query2 =$builder_m1->fromSubquery($builder1,'ld');

        $query = $query1->union($query2)->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function update_main($upd_arr,$diary_no){
        
        $builder = $this->db->table('public.main');

        $builder->where('diary_no',$diary_no);

        $query = $builder->update($upd_arr);

        if($query) {
            return 1;
        }else
        {
            return 0;
        }
        
    }

    public function insert_registration_track($ins_arr){

        $builder = $this->db->table("public.registration_track");
        $query = $builder->insert($ins_arr);

        if($query) {
            return 1;
        }else
        {
            return 0;
        }
        
    }

    public function get_party($diary_no){

        $tbl = is_table_a('party');

        $builder = $this->db->table("public.".$tbl);
        $builder->select("contact");
        $builder->where('diary_no',$diary_no);
        $builder->where('pet_res','P');
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function advocate_mob($diary_no){

        $tbl = is_table_a('advocate');

        $builder = $this->db->table("public.".$tbl." a");
        $builder->select("mobile");
        $builder->join("master.bar b", "a.advocate_id = b.bar_id");
        $builder->where('diary_no',$diary_no);
        $builder->where('display','Y');
        $builder->where('pet_res','P');
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

    public function insert_sms_pool($ins_arr){

        $builder = $this->db->table("public.sms_pool");
        $query = $builder->insert($ins_arr);

        if($query) {
            return 1;
        }else
        {
            return 0;
        }
        
    }

    public function check_for_heardt_zero($diary_no){

        $tbl_heardt = is_table_a('heardt');
        $tbl_main = is_table_a('main');

        $builder = $this->db->table("public.".$tbl_heardt." h");
        $builder->select("h.*");
        $builder->join("public.".$tbl_main." m", "h.diary_no = m.diary_no");
        $builder->where("h.diary_no", (int) $diary_no);
        $builder->where("(h.coram is not null or h.coram!='0' or h.coram!='')");
        $builder->where("(m.dacode=NULL or m.dacode!='0' or m.dacode is not null)");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getNumRows();
        }else{
            return 0;
        }
        
    }

    public function get_mention_memo($diary_no){

        $builder = $this->db->table("public.mention_memo");
        $builder->select("*");
        $builder->where("diary_no",$diary_no);
        $builder->where("display","Y");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getNumRows();
        }else{
            return 0;
        }
        
    }

    public function get_drop_note($diary_no){

        $builder = $this->db->table("public.drop_note");
        $builder->select("*");
        $builder->where("diary_no",$diary_no);
        $builder->where("display","Y");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getNumRows();
        }else{
            return 0;
        }
        
    }

    public function get_and_set_da($diary_no){

        $tbl_main = is_table_a('main');

        $builder = $this->db->table("public.".$tbl_main);
        $builder->select("section_id,dacode,from_court,ref_agency_state_id,ref_agency_code_id");
        $builder->select("CASE WHEN (active_casetype_id = 0 or active_casetype_id is null or active_casetype_id=NULL) THEN casetype_id ELSE active_casetype_id END as casetype_id");
        $builder->select("EXTRACT(YEAR FROM CASE WHEN (active_fil_dt is null or active_fil_dt=NULL) THEN diary_no_rec_date ELSE active_fil_dt END) as regyear");
        $builder->select("DATE(diary_no_rec_date) as fildate");
        $builder->select("DATE(CASE WHEN (active_fil_dt is null or active_fil_dt=NULL) THEN diary_no_rec_date ELSE active_fil_dt END) as filregdate");
        $builder->where("diary_no",$diary_no);
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function get_lower_case_temp($diary_no){

        $tbl = is_table_a('lowerct');

        $builder = $this->db->table("public.".$tbl);
        $builder->select("ct_code,l_state,lct_casetype,lct_caseno,lct_caseyear");
        $builder->where("diary_no",$diary_no);
        $builder->where("lw_display","Y");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

    public function get_for_da_temp($lower_case_temp_row){

        $tbl_mch = is_table_a('main_casetype_history');
        $tbl_main = is_table_a('main');

        $builder = $this->db->table("public.".$tbl_mch." a");
        $builder->select("b.dacode,a.diary_no,new_registration_number");
        $builder->select("split_part(split_part(new_registration_number, '-', 2),'-',-1), split_part(new_registration_number, '-', -1)");
        $builder->select("dacode,name,section_name,casetype_id,active_casetype_id,diary_no_rec_date,reg_year_mh,reg_year_fh,active_reg_year,ref_agency_state_id");
        $builder->join("public.".$tbl_main." b", "a.diary_no = b.diary_no", "left");
        $builder->join("master.users c", "b.dacode = c.usercode", "left");
        $builder->join("master.usersection us", "c.section = us.id", "left");
        $builder->where("ref_new_case_type_id",$lower_case_temp_row[0]['lct_casetype']);
        $builder->where("new_registration_year",$lower_case_temp_row[0]['lct_caseyear']);
        $builder->where("is_deleted","f");
        $builder->where("'".str_pad($lower_case_temp_row[0]['lct_caseno'], 6, 0, STR_PAD_LEFT)."' BETWEEN 
            split_part(split_part(new_registration_number, '-', 2), '-', -1) AND 
            split_part(new_registration_number, '-', -1)");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

    public function get_check_section($dacode){

        $builder = $this->db->table("master.users");
        $builder->select("*");
        $builder->where("usercode",$dacode);
        $builder->where("display","Y");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

    public function insert_matters_with_wrong_section($ins_arr){

        $builder = $this->db->table("public.matters_with_wrong_section");
        $query = $builder->insert($ins_arr);

        if($query) {
            return 1;
        }else
        {
            return 0;
        }
        
    }

    public function get_submaster_id($diary_no){

        $tbl = is_table_a('mul_category');

        $submaster_id_arr = [349,118,119,120,121,122,123,124,125,126,127,128,129,130,131,132,133,318,332,567,568,569,570,571,572,573,574,575,576,577,578,579,580,581,582];

        $builder = $this->db->table("public.".$tbl);
        $builder->select("submaster_id");
        $builder->where('diary_no',$diary_no);
        $builder->whereIn('submaster_id',$submaster_id_arr);
        $builder->where('display','Y');
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getNumRows();
        }else{
            return 0;
        }
        
    }

    public function get_da_case_distribution_pilwrit($case_type,$filregdate,$ref_agency_state_id){

        $builder = $this->db->table("master.da_case_distribution_pilwrit");
        $builder->select("dacode");
        $builder->where("case_type",$case_type);
        $builder->where("'".$filregdate."' BETWEEN 
            case_f_yr AND case_t_yr 
            AND (state=".$ref_agency_state_id." OR state=0) AND display='Y'
            ");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

    public function get_da_case_distribution_pilwrit_num_rows($case_type,$filregdate,$ref_agency_state_id){

        $builder = $this->db->table("master.da_case_distribution_pilwrit");
        $builder->select("dacode");
        $builder->where("case_type",$case_type);
        $builder->where("'".$filregdate."' BETWEEN 
            case_f_yr AND case_t_yr 
            AND (state=".$ref_agency_state_id." OR state=0) AND display='Y'
            ");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getNumRows();
        }else{
            return 0;
        }
    }

    public function get_tribunal_sec_qr($ref_agency_code_id){

        $builder = $this->db->table("master.ref_agency_code");
        $builder->select("agency_or_court");
        $builder->where('id',$ref_agency_code_id);
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function get_da_case_distribution_tri_new($casetype_id,$filregdate,$tribunal){

        $builder = $this->db->table("master.da_case_distribution_tri_new");
        $builder->select("dacode");
        $builder->where('case_type',$casetype_id);
        $builder->where("'".$filregdate."' BETWEEN 
            case_f_yr AND case_t_yr 
            AND ref_agency is not null and ref_agency='".$tribunal."' AND display='Y'
            ");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function get_da_case_distribution_tri_new_num_rows($casetype_id,$filregdate,$tribunal){

        $builder = $this->db->table("master.da_case_distribution_tri_new");
        $builder->select("dacode");
        $builder->where('case_type',$casetype_id);
        $builder->where("'".$filregdate."' BETWEEN 
            case_f_yr AND case_t_yr 
            AND ref_agency is not null and ref_agency='".$tribunal."' AND display='Y'
            ");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getNumRows();
        }else{
            return 0;
        }
        
    }

    public function get_user_by_section($section){

        $builder = $this->db->table("master.users");
        $builder->select("usercode");
        $builder->where("section",$section);
        $builder->where("usertype","14");
        $builder->where("display","Y");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }


    public function get_number_for($ref_agency_state_id,$active_casetype_id,$regyear){

        $tbl = is_table_a('main');

        $builder = $this->db->table("public.".$tbl." a, (SELECT 0 AS x_sar)x_sar");
        $builder->select("a.diary_no, fil_dt, row_number() OVER () AS rownum");
        $builder->where("ref_agency_state_id",$ref_agency_state_id);
        $builder->where("active_casetype_id",$active_casetype_id);
        $builder->where("EXTRACT(YEAR FROM COALESCE(NULLIF(active_fil_dt, NULL), diary_no_rec_date)) = '".$regyear."'");
        $builder->orderBy("fil_dt");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

    public function get_da_case_distribution_new($table,$case_type,$current_no,$fildate,$ref_agency_state_id){

        $builder = $this->db->table($table);
        $builder->select("dacode");
        $builder->where("case_type",$case_type);
        $builder->where("'".$current_no."' BETWEEN case_from AND case_to");
        $builder->where("'".$fildate."' BETWEEN 
            case_f_yr AND case_t_yr 
            AND (state=".$ref_agency_state_id." OR state=0) AND display='Y'
            ");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

    public function get_da_case_distribution_new_num_rows($case_type,$current_no,$fildate,$ref_agency_state_id){

        $builder = $this->db->table("master.da_case_distribution_new");
        $builder->select("dacode");
        $builder->where("case_type",$case_type);
        $builder->where("'".$current_no."' BETWEEN case_from AND case_to");
        $builder->where("'".$fildate."' BETWEEN 
            case_f_yr AND case_t_yr 
            AND (state=".$ref_agency_state_id." OR state=0) AND display='Y'
            ");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getNumRows();
        }else{
            return 0;
        }
    }

    public function get_case_name_q($diary_no){

        $tbl_main = is_table_a('main');

        $builder = $this->db->table("public.".$tbl_main);
        $builder->select("short_description");
        $builder->join("master.casetype", "casetype_id=casecode", "left");
        $builder->where('diary_no',$diary_no);
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

    public function get_chk_heardt($diary_no){

        $tbl = is_table_a('heardt');

        $builder = $this->db->table("public.".$tbl);
        $builder->select("next_dt");
        $builder->where('diary_no',$diary_no);
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getNumRows();
        }else{
            return 0;
        }
    }

    public function get_popup_details($diary_no){

        $tbl_main = is_table_a('main');
        $tbl_heardt = is_table_a('heardt');
        $tbl_brdrem = is_table_a('brdrem');

        $builder = $this->db->table("public.".$tbl_main." a");
        $builder->select("diary_no_rec_date,fil_dt,lastorder,pet_name,res_name,c_status,listorder,next_dt,mainhead,subhead,clno,brd_slno,roster_id,judges,board_type,main_supp_flag,listorder,tentative_cl_dt,sitting_judges,c.remark,case_grp side,description status");
        $builder->join("public.".$tbl_heardt." b", "a.diary_no=b.diary_no", "left");
        $builder->join("public.".$tbl_brdrem." c", "a.diary_no=c.diary_no", "left");
        $builder->join("master.master_case_status d", "case_status_id=d.id", "left");
        $builder->where("a.diary_no",$diary_no);
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

    public function get_query_cate($diary_no){

        $tbl_mc = is_table_a('mul_category');

        $builder = $this->db->table("public.".$tbl_mc." a");
        $builder->select("submaster_id,sub_name1,sub_name2,sub_name3,sub_name4");
        $builder->join("master.submaster b", "submaster_id=b.id", "left");
        $builder->where("diary_no",$diary_no);
        $builder->where("a.display","Y");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function get_main_case($diary_no){

        $tbl = is_table_a('conct');

        $builder = $this->db->table("public.".$tbl);
        $builder->select("conn_key,diary_no");
        $builder->where("conn_key",$diary_no);
        $builder->where("diary_no",$diary_no);
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function get_subheading($listtype,$stage_based_on_side){

        $builder = $this->db->table("master.subheading");
        $builder->select("stagecode,stagename");
        $builder->where("listtype",$listtype);
        $builder->where($stage_based_on_side);
        $builder->where("display","Y");
        $builder->orderBy("stagecode");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function get_mul_category_with_submaster($diary_no){

        $tbl_mc = is_table_a('mul_category');

        $builder = $this->db->table("public.".$tbl_mc." a");
        $builder->select("submaster_id stagecode,CONCAT(sub_name1,'-',sub_name2,'-',sub_name3,'-',sub_name4) stagename");
        $builder->join("master.submaster b", "submaster_id=b.id", "left");
        $builder->where("a.display","Y");
        $builder->where("diary_no",$diary_no);
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function get_if_printed($next_dt,$mainhead,$roster_id,$clno,$main_supp_flag){

        $builder = $this->db->table("public.cl_printed");
        $builder->select("id");
        $builder->where("next_dt",$next_dt);
        $builder->where("next_dt >= cast(now() as date)");
        $builder->where("m_f",$mainhead);
        $builder->where("roster_id",$roster_id);
        $builder->where("part",$clno);
        $builder->where("main_supp",$main_supp_flag);
        $builder->where("display","Y");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getNumRows();
        }else{
            return 0;
        }
        
    }

    public function get_judge($next_dt){

        $builder = $this->db->table("master.roster r");
        $builder->select("r.id, STRING_AGG(j.jcode::TEXT, ',' ORDER BY j.judge_seniority) jcd, STRING_AGG(CONCAT(j.first_name,' ',j.sur_name), ',' ORDER BY j.judge_seniority) jnm, rb.bench_no, mb.abbr, r.tot_cases");
        $builder->join("master.roster_bench rb", "rb.id = r.bench_id", "left");
        $builder->join("master.master_bench mb", "mb.id = rb.bench_id", "left");
        $builder->join("master.roster_judge rj", "rj.roster_id = r.id", "left");
        $builder->join("master.judge j", "j.jcode = rj.judge_id", "left");
        $builder->where("j.is_retired != 'Y'");
        $builder->where("j.display","Y");
        $builder->where("rj.display","Y");
        $builder->where("rb.display","Y");
        $builder->where("mb.display","Y");
        $builder->where("r.display","Y");
        $builder->where("m_f","1");
        $builder->where("r.from_date",$next_dt);
        $builder->where("r.to_date",$next_dt);
        $builder->groupBy("r.id, rb.bench_no, mb.abbr, r.tot_cases, j.judge_seniority");
        $builder->orderBy("r.id, j.judge_seniority");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function get_master_main_supp(){

        $builder = $this->db->table("master.master_main_supp");
        $builder->select("id,descrip");
        $builder->where("display","Y");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function get_listing_purpose(){

        $builder = $this->db->table("master.listing_purpose");
        $builder->select("code,purpose");
        $builder->where("display","Y");
        $builder->orderBy("code");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function get_ia_details($diary_no){

        $tbl = is_table_a('docdetails');

        $builder = $this->db->table("public.".$tbl." a");
        $builder->select("a.doccode,a.doccode1,docnum,docyear,filedby,other1,iastat,b.docdesc");
        $builder->join("master.docmaster b", "a.doccode = b.doccode AND a.doccode1 = b.doccode1","left");
        $builder->where('a.doccode','8');
        $builder->where('diary_no',$diary_no);
        $builder->where('a.display','Y');
        $builder->orderBy('ent_dt,docyear,docnum');
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

}
