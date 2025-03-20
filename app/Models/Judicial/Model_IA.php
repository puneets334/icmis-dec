<?php
namespace App\Models\Judicial;

use CodeIgniter\Model;

class Model_IA extends Model{


    public function get_docdetails($diary_no,$doc_id='',$is_archival_table=''){
        $doccode = 8;
        $builder = $this->db->table("docdetails$is_archival_table");
        $builder->select("*,concat(docnum,'/',docyear) as ia");
        $builder->where('diary_no',$diary_no);
        if (!empty($doc_id)){
            $builder->whereIn('docd_id',$doc_id);
        }else{
            $builder->where('doccode', $doccode);
            $builder->where('display', 'Y');
            // $builder->where('iastat', 'D');
        }
        // Print the generated query
        // echo "Generated Query: " . $builder->getCompiledSelect();
        // exit;

        $query = $builder->get();
        return $query->getResultArray();

    }
    public function getIArec(){
        $doccode = 8;
        $builder = $this->db->table("master.docmaster");
        $builder->select('*');
        $builder->where('doccode', $doccode);
        $builder->where('doccode1 !=',0);
        //$builder->where('(doctype=4 or doctype=0 or doctype=1 or doctype=2 or doctype=3)'); // if data type reset By deepak then working
        //$builder->where('(doctype=true or doctype=false)');
        $builder->whereIn('doctype', [0, 1]);
        $builder->where('display','Y');
        $builder->orderBy('docdesc', 'ASC');
         // Print the generated query
        // echo "Generated Query: " . $builder->getCompiledSelect();
        // exit;
        $query = $builder->get();
        return $query->getResultArray();       

    }
    public function getPartyName($diary_no){
        $builder = $this->db->table('party');
        $builder->distinct();
        $builder->select('sr_no,partyname,pet_res,diary_no');
        $builder->where('diary_no', $diary_no);
        $builder->where('pet_res', 'P');
        $builder->orderBy('partyname', 'ASC');
        $query = $builder->get();
        $result1= $query->getResultArray();

        $builder2 = $this->db->table('party_a');
        $builder2->distinct();
        $builder2->select('sr_no,partyname,pet_res,diary_no');
        $builder2->where('diary_no', $diary_no);
        $builder2->where('pet_res', 'P');
        $builder2->orderBy('partyname', 'ASC');
        $query2 = $builder2->get();
        $result2= $query2->getResultArray();

        $result=array_merge($result1,$result2);
        return $result;

    }
    public function get_diary_details($diary_no){
        $builder = $this->db->table('main');
        $builder->distinct();
        $builder->select("casetype_id,diary_no,conn_key,fil_no,fil_dt,
       EXTRACT(YEAR FROM fil_dt) AS filyr,
       TO_CHAR(fil_dt, 'DD-MM-YYYY HH:MI AM') AS fil_dt_f,
       fil_no_fh,
       TO_CHAR(fil_dt_fh, 'DD-MM-YYYY HH:MI AM') AS fil_dt_fh,
       actcode,pet_adv_id,res_adv_id,lastorder,c_status,
       CASE WHEN fil_no != '' THEN SPLIT_PART(fil_no, '-', 1) ELSE '' END AS ct1,
       CASE WHEN fil_no != '' THEN SPLIT_PART(SPLIT_PART(fil_no, '-', 2), '-', -1) ELSE '' END AS crf1,
       CASE WHEN fil_no != '' THEN SPLIT_PART(fil_no, '-', -1) ELSE '' END AS crl1,
       CASE WHEN fil_no_fh != '' THEN SPLIT_PART(fil_no_fh, '-', 1) ELSE '' END AS ct2,
       CASE WHEN fil_no_fh != '' THEN SPLIT_PART(SPLIT_PART(fil_no_fh, '-', 2), '-', -1) ELSE '' END AS crf2,
       CASE WHEN fil_no_fh != '' THEN SPLIT_PART(fil_no_fh, '-', -1) ELSE '' END AS crl2,
       dacode");
        $builder->where('diary_no', $diary_no);
        $query = $builder->get();
        $result= $query->getRowArray();
        if (empty($result)){
            $builder = $this->db->table('main_a');
            $builder->distinct();
            $builder->select("casetype_id,diary_no,conn_key,fil_no,fil_dt,
       EXTRACT(YEAR FROM fil_dt) AS filyr,
       TO_CHAR(fil_dt, 'DD-MM-YYYY HH:MI AM') AS fil_dt_f,
       fil_no_fh,
       TO_CHAR(fil_dt_fh, 'DD-MM-YYYY HH:MI AM') AS fil_dt_fh,
       actcode,pet_adv_id,res_adv_id,lastorder,c_status,
       CASE WHEN fil_no != '' THEN SPLIT_PART(fil_no, '-', 1) ELSE '' END AS ct1,
       CASE WHEN fil_no != '' THEN SPLIT_PART(SPLIT_PART(fil_no, '-', 2), '-', -1) ELSE '' END AS crf1,
       CASE WHEN fil_no != '' THEN SPLIT_PART(fil_no, '-', -1) ELSE '' END AS crl1,
       CASE WHEN fil_no_fh != '' THEN SPLIT_PART(fil_no_fh, '-', 1) ELSE '' END AS ct2,
       CASE WHEN fil_no_fh != '' THEN SPLIT_PART(SPLIT_PART(fil_no_fh, '-', 2), '-', -1) ELSE '' END AS crf2,
       CASE WHEN fil_no_fh != '' THEN SPLIT_PART(fil_no_fh, '-', -1) ELSE '' END AS crl2,
       dacode");
            $builder->where('diary_no', $diary_no);
            $query = $builder->get();
            $result= $query->getRowArray();
        }
        return $result;

    }

    public function get_party_details($diary_no){
        $builder = $this->db->table('party p');
        $builder->select('p.sr_no, p.pet_res, p.ind_dep, p.partyname, p.sonof, p.prfhname, p.age, p.sex, p.caste, p.addr1, p.addr2, p.pin, p.state, p.city, p.email, p.contact AS mobile, p.deptcode, d.deptname, c.skey, m.casetype_id');
        $builder->join('main m', 'm.diary_no = p.diary_no');
        $builder->join('master.casetype c', '(c.casecode::text) = LEFT(m.diary_no::text, 2)', 'left');
        $builder->join('master.deptt d', 'd.deptcode = p.deptcode', 'left');
        $builder->where('m.diary_no', $diary_no);
        $builder->where('p.sr_no', 1);
        $builder->where('p.pflag', 'P');
        $builder->whereIn('p.pet_res', ['P', 'R']);
        $builder->orderBy('p.pet_res');
        $builder->orderBy('p.sr_no');
        $query =$builder->get();
        return $query->getResultArray();
        //$query = $this->db->getLastQuery(); echo (string) $query; exit();
    }




    /* Start function case_nos Anshu */
    public function get_short_description($casetype_id){
        $builder = $this->db->table("master.casetype");
        $builder->select('short_description,cs_m_f');
        $builder->where('casecode', $casetype_id);
        $builder->where('display','Y');
        $query = $builder->get();
        return $query->getRowArray();

    }
    public function get_new_old_registration_details($diary_no){
        $query = "SELECT t.oldno,STRING_AGG( CONCAT(t.new_registration_number, ':', t.new_registration_year, ':', TO_CHAR(t.order_date, 'DD-MM-YYYY')), ' ' ORDER BY t.order_date, t.id) AS newno
FROM ( SELECT row_number() OVER () AS rowid,main_casetype_history.*,
            CASE WHEN row_number() OVER () = 1 THEN
                    CASE WHEN old_registration_number = '' OR old_registration_number IS NULL THEN '' ELSE CONCAT(old_registration_number, ':', old_registration_year, ':', TO_CHAR(order_date, 'DD-MM-YYYY'))
                    END ELSE '' END AS oldno
        FROM main_casetype_history,(SELECT 0) AS init
        WHERE diary_no = $diary_no AND is_deleted = 'f'
        ORDER BY main_casetype_history.order_date,id
    ) t GROUP BY t.oldno;
 ";
        $query = $this->db->query($query);
        return $query->getResultArray();
        //login user 4684 then check
        $query = $this->db->table('main_casetype_history t');
        $query->select('t.oldno');
        $query->select("STRING_AGG(CONCAT(t.new_registration_number, ':', t.new_registration_year, ':', TO_CHAR(t.order_date, 'DD-MM-YYYY')), ' ' ORDER BY t.order_date, t.id) AS newno");

        $subquery = $this->db->table('main_casetype_history');
        $subquery->select('row_number() OVER () AS rowid, *');
        $subquery->select("CASE WHEN row_number() OVER () = 1 THEN CASE WHEN old_registration_number = '' OR old_registration_number IS NULL THEN '' ELSE CONCAT(old_registration_number, ':', old_registration_year, ':', TO_CHAR(order_date, 'DD-MM-YYYY')) END ELSE '' END AS oldno");
        $subquery->join('(SELECT 0) AS init', 'init.id = init.id', 'CROSS');
        $subquery->where('diary_no', 80642013);
        $subquery->where('is_deleted', 'f');
        $subquery->orderBy('main_casetype_history.order_date');
        $subquery->orderBy('id');

        $query->from('(' . $subquery->getCompiledSelect() . ') t');
        $query->groupBy('t.oldno');
        return $query->get()->getResultArray();

    }

    /* end function case_nos Anshu */
	
	
	public function getActMain($diary_no)
	{
		$builder = $this->db->table('act_main a');
		$builder->select('a.act, STRING_AGG(b.section, \', \') AS section, c.act_name');
		$builder->join('master.act_section b', 'a.id = b.act_id', 'left');
		$builder->join('master.act_master c', 'c.id = a.act');
		$builder->where('diary_no', $diary_no);
		$builder->where('a.display', 'Y');
		$builder->where('b.display', 'Y');
		$builder->where('c.display', 'Y');
		$builder->groupBy('a.act, c.act_name'); // Include all selected non-aggregated columns in group by
		$query = $builder->get();
		return $result = $query->getResultArray();

	}
}