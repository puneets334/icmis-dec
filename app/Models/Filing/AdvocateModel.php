<?php

namespace App\Models\Filing;

use CodeIgniter\Model;

class AdvocateModel extends Model
{

    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
    }


    public function get_party($diary_no,$party_type){

        $tbl = is_table_a('party');

        $builder = $this->db->table("public.".$tbl);
        $builder->select("partyname,sr_no,sr_no_show");
        $builder->where('diary_no',$diary_no);
        $builder->where('pet_res',$party_type);
        $builder->where('pflag','P');
      /*   $builder->orderBy("CAST(sr_no AS BIGINT),
            split_part(sr_no_show, '.', 1)::BIGINT,
            split_part(split_part(sr_no_show || '.0', '.', 2), '.', -1)::BIGINT,
            split_part(split_part(sr_no_show || '.0.0', '.', 3), '.', -1)::BIGINT,
            split_part(split_part(sr_no_show || '.0.0.0', '.', 4), '.', -1)::BIGINT"); */
		
		    $builder->orderBy("
			CAST(sr_no AS BIGINT),			 
			COALESCE(NULLIF(split_part(sr_no_show, '.', 2), '')::BIGINT, 0),
			COALESCE(NULLIF(split_part(sr_no_show, '.', 3), '')::BIGINT, 0),
			COALESCE(NULLIF(split_part(sr_no_show, '.', 4), '')::BIGINT, 0)
		");
		// echo $this->db->getLastQuery();
		 
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }


    public function get_party_by_sr_no_show($diary_no,$party_type,$sr_no_show){

        $tbl = is_table_a('party');

        $builder = $this->db->table("public.".$tbl);
        $builder->select("partyname,sr_no,sr_no_show");
        $builder->where('diary_no',$diary_no);
        $builder->where('pet_res',$party_type);
        $builder->where('sr_no_show',$sr_no_show);
        $builder->where('pflag','P');
       /*  $builder->orderBy("CAST(sr_no AS BIGINT),
            split_part(sr_no_show, '.', 1)::BIGINT,
            split_part(split_part(sr_no_show || '.0', '.', 2), '.', -1)::BIGINT,
            split_part(split_part(sr_no_show || '.0.0', '.', 3), '.', -1)::BIGINT,
            split_part(split_part(sr_no_show || '.0.0.0', '.', 4), '.', -1)::BIGINT"); */
		
		$builder->orderBy("
			CAST(sr_no AS BIGINT),
			COALESCE(NULLIF(split_part(sr_no_show, '.', 1), '')::BIGINT, 0),
			COALESCE(NULLIF(split_part(sr_no_show, '.', 2), '')::BIGINT, 0),
			COALESCE(NULLIF(split_part(sr_no_show, '.', 3), '')::BIGINT, 0),
			COALESCE(NULLIF(split_part(sr_no_show, '.', 4), '')::BIGINT, 0)
		");
		
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

    public function get_advocate_by_sr_no_show($diary_no,$get_party,$sr_no_show,$adv_type,$advocate_id){

        $tbl = is_table_a('advocate');

        $builder = $this->db->table('public.'.$tbl);
        $builder->select('*');
        $builder->where('diary_no',$diary_no);
        $builder->where('pet_res',$get_party);

        if (fmod((float)$sr_no_show, 1) !== 0.0) {
          $builder->where('pet_res_show_no',$sr_no_show);
        } else {
          $builder->where('pet_res_no',$sr_no_show,false);
        }

        $builder->where('adv_type',$adv_type);
        $builder->where('advocate_id',$advocate_id);
        $builder->where('display','Y');
        
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }


    public function bar_detail_by_bar_id($bar_id){

        $builder = $this->db->table('master.bar');
        $builder->select('bar_id,name,mobile,email,enroll_no,extract(year from enroll_date) as enroll_date,aor_code');
        $builder->where('bar_id',$bar_id);
        
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }


    public function bar_detail($advocate_detail,$adv_no,$adv_year,$stateID){

        $builder = $this->db->table('master.bar');
        $builder->select('name,mobile,email');

        if($advocate_detail=='A'){
            $builder->where('aor_code',$adv_no);
            $builder->where("isdead!='Y'");
            $builder->where('if_aor','Y');
        }

        if($advocate_detail=='S' || $advocate_detail=='AC'){
            $builder->where('enroll_no',$adv_no);
            $builder->where("extract(year from enroll_date)",$adv_year);
            $builder->where('state_id',$stateID);
            $builder->where('isdead','N');
        }
        
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }


    public function check_aor_exist($aor_code){

        $builder = $this->db->table('master.bar');
        $builder->select('bar_id');
        $builder->where('aor_code',$aor_code);
        $builder->where('isdead','N');
        
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

    public function check_aor_exist_with_state($state,$adv_no,$adv_year){

        $builder = $this->db->table('master.bar');
        $builder->select('bar_id');
        $builder->where('enroll_no',$adv_no);
        $builder->where("extract(year from enroll_date)",$adv_year);
        $builder->where('isdead','N');
        
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }


    public function advocate($diary_no,$advocate_id,$get_party,$state_adv){

        $tbl = is_table_a('advocate');

        if($get_party=='P' || $get_party=='R'){
            $p_no_show = NULL;
            $state_adv = NULL;
        }
        elseif($get_party=='I' || $get_party=='N'){
            $p_no_show = 0;
            $state_adv = $state_adv;
        }

        $builder = $this->db->table('public.'.$tbl);
        $builder->select('advocate_id');
        $builder->where('diary_no',$diary_no);
        $builder->where('advocate_id',$advocate_id);
        $builder->where('pet_res',$get_party);
        $builder->where('pet_res_no',$p_no_show);
        $builder->where('display','Y');
        $builder->where('stateadv',$state_adv);
        
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getNumRows();
        }else{
            return 0;
        }
    }

    public function get_advocate($diary_no,$get_party,$sr_no_show){

        $tbl = is_table_a('advocate');

        $builder = $this->db->table("public.".$tbl);
        $builder->select("pet_res_no,adv,adv_type,advocate_id");
        $builder->select("COALESCE(NULLIF(pet_res_show_no, ''), TRIM(CAST(pet_res_no AS VARCHAR)), TRIM(CAST(pet_res_show_no AS VARCHAR))) AS pet_res_show_no");
        $builder->select("name,is_ac,isdead,if_aor,if_sen,if_other,aor_code");
        $builder->join("master.bar", "bar.bar_id=advocate.advocate_id");
        $builder->where('diary_no',$diary_no);
        $builder->where('display','Y');
        $builder->where('pet_res',$get_party);
        $builder->where("COALESCE(NULLIF(pet_res_show_no, ''), TRIM(CAST(pet_res_no AS VARCHAR)), TRIM(CAST(pet_res_show_no AS VARCHAR))) = '".$sr_no_show."'");

        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

    public function get_caveat_advocate($diary_no){

        $tbl = is_table_a('advocate');

        $builder = $this->db->table("public.".$tbl);
        $builder->select("pet_res_no,adv,adv_type,advocate_id");
        $builder->select("COALESCE(NULLIF(pet_res_show_no, ''), TRIM(CAST(pet_res_no AS VARCHAR)), TRIM(CAST(pet_res_show_no AS VARCHAR))) AS pet_res_show_no");
        $builder->select("name,is_ac,isdead,if_aor,if_sen,if_other,aor_code");
        $builder->join("master.bar", "bar.bar_id=advocate.advocate_id");
        $builder->where('diary_no',$diary_no);
        $builder->where('display','Y');
        $builder->where('pet_res','R');
        $builder->where("pet_res_no","0");

        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

    public function get_advocate_impleading_intervenor($diary_no,$get_party){

        $tbl = is_table_a('advocate');

        $builder1 = $this->db->table("public.".$tbl." a");
        $builder1->select("0,'',a.adv,a.advocate_id,a.adv_type,a.is_ac,b.name,b.aor_code,a.inperson_mobile,a.inperson_email");
        $builder1->join("master.bar b", "b.bar_id=a.advocate_id","left");
        $builder1->where('a.diary_no',$diary_no);
        $builder1->where('a.pet_res',$get_party);
        $builder1->where('a.pet_res_no','0');
        $builder1->where('a.display','Y');
        $builder1->orderBy('a.adv_type','DESC');

        $builder = $this->db->table("public.party p");
        $builder->select("p.sr_no,p.partyname,a.adv,a.advocate_id,a.adv_type,a.is_ac,b.name,b.aor_code,a.inperson_mobile,a.inperson_email");
        $builder->join("public.".$tbl." a", "p.diary_no=a.diary_no","right");
        $builder->join("master.bar b", "b.bar_id=a.advocate_id","left");
        $builder->where('p.diary_no',$diary_no);
        $builder->where('p.pet_res',$get_party);
        $builder->where('pflag','P');
        $builder->where('a.display','Y');       
        

        $query = $builder->union($builder1)->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

    public function insert_advocate($ins_arr){
        
        $builder = $this->db->table('public.advocate');

        $query = $builder->insert($ins_arr);

        if($query) {
            return 1;
        }else
        {
            return 0;
        }
        
    }

    public function update_main_pet_res_adv_id($upd_arr,$diary_no){
        
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

    public function update_advocate($upd_arr,$diary_no,$get_pet_res,$get_sr_no_show,$get_adv_type,$get_advocate_id){
        
        $builder = $this->db->table('public.advocate');

        $builder->where('diary_no',$diary_no);
        $builder->where('pet_res',$get_pet_res);

        if($get_sr_no_show==0){
            $builder->where('pet_res_no',$get_sr_no_show);
        }else{
            $builder->where('pet_res_show_no',$get_sr_no_show);
        }
        
        $builder->where('adv_type',$get_adv_type);
        $builder->where('advocate_id',$get_advocate_id);

        $query = $builder->update($upd_arr);

        if($query) {
            return 1;
        }else
        {
            return 0;
        }
        
    }

    public function delete_advocate($diary_no,$get_pet_res,$get_sr_no_show,$get_adv_type,$get_advocate_id){
        
        $builder = $this->db->table('public.advocate');

        $builder->where('diary_no',$diary_no);
        $builder->where('pet_res',$get_pet_res);

        if($get_sr_no_show==0){
            $builder->where('pet_res_no',$get_sr_no_show);
        }else{
            $builder->where('pet_res_show_no',$get_sr_no_show);
        }

        $builder->where('adv_type',$get_adv_type);
        $builder->where('advocate_id',$get_advocate_id);

        $query = $builder->delete();

        if($query) {
            return 1;
        }else
        {
            return 0;
        }
        
    }

    public function delete_advocate_imp($diary_no,$get_pet_res,$get_adv_type,$get_advocate_id)
    {
        
        $builder = $this->db->table('public.advocate');

        $builder->where('diary_no',$diary_no);
        $builder->where('pet_res',$get_pet_res);
        $builder->where('adv_type',$get_adv_type);
        $builder->where('advocate_id',$get_advocate_id);

        $query = $builder->delete();

        if($query) {
            return 1;
        }else
        {
            return 0;
        }
        
    }

    public function get_main_tbl_data($diary_no){

        $builder = $this->db->table("public.party a");
        $builder->select("CONCAT(count(sr_no_show),'-',pet_res)q,c_status");
        $builder->join("public.main b", "a.diary_no=b.diary_no and pflag='P'");
        $builder->where('a.diary_no',$diary_no);
        $builder->groupBy('pet_res,c_status');

        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

    public function get_advocate_name($term){

         // Start building the query
			$builder = $this->db->table("master.bar");

			// Select name and concatenate other fields as 'data'
			$builder->select("name, CONCAT(mobile, '~', email, '~', aor_code, '~', if_aor) as data");

			// Use query bindings to prevent SQL injection
			$builder->like("LOWER(name)", strtolower($term), 'both');  // 'both' adds wildcards on both sides for iLIKE

			// Apply additional conditions
			$builder->where("isdead", "N");

			// Execute the query
			$query = $builder->get();
			//echo $this->db->getLastQuery();
        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

    public function get_advocate_name_by_aor_code($aor_code){

        $builder = $this->db->table("master.bar");
        $builder->select("name,mobile,email,aor_code,if_aor");
        $builder->where("aor_code",$aor_code);
        $builder->where("isdead","N");
        
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

    public function select_aor_code()
    {
        $builder = $this->db->table('master.bar');
        $builder->select('aor_code, bar_id,name');
        $builder->where('if_aor','Y')->where('isdead','N');
        $query = $builder->get();
        $result = $query->getResultArray();
//        $query=$this->db->getLastQuery();echo (string) $query;exit();
//        echo "<pre>";
//        print_r($result);
//        die;
        if($result)
        {
            return $result;
        }else{
            return 0;
        }
    }


    public function add_caveator_writ($diary_no,$usercode,$advocateId,$remarks)
    {
        $columnsData = array(
            'diary_no' => $diary_no,
            'adv_type' => 'A',
            'pet_res' => 'R',
            'advocate_id' => $advocateId,
            'usercode' => $usercode,
            'ent_dt' => 'NOW()',
            'display' => 'Y',
            'stateadv' => 'N',
            'aor_state' => 'A',
            'adv' => '[caveat]',
            'writ_adv_remarks' => $remarks,
            'create_modify' => date("Y-m-d H:i:s"),
            'updated_on' => date("Y-m-d H:i:s"),
            'updated_by' => $usercode,
            'updated_by_ip' => getClientIP()
        );
        $builder = $this->db->table('advocate');
        $result = $builder->insert($columnsData);
//           $query=$this->db->getLastQuery();echo (string) $query;
//           exit();
        if($result)
        {
            return $result;
        }else{
            return 0;
        }
    }
	
	
	public function getRSdata($diary_no)
	{
		$diary_no = $this->db->escape($diary_no);  // Ensure diary_no is properly escaped

		$query = $this->db->query("
			SELECT CONCAT(COUNT(sr_no_show), '-', pet_res) AS q, c_status 
			FROM party a 
			LEFT JOIN main b ON a.diary_no = b.diary_no AND pflag = 'P' 
			WHERE a.diary_no = {$diary_no} 
			GROUP BY pet_res, c_status
			ORDER BY CASE WHEN pet_res = 'P' THEN 0 WHEN pet_res = 'R' THEN 0 ELSE 1 END, pet_res
		");
		$result = $query->getResultArray();
		if($result)
        {
            return $result;
        }else{
            return false;
        }	
		
	}
	
	public function getCaseTypeData($diary_no)
	{
		$sql = "
			SELECT fil_no, 
				   fil_dt, 
				   fil_no_fh, 
				   fil_dt_fh, 
				   short_description, 
				   CASE WHEN reg_year_mh = 0 THEN EXTRACT(YEAR FROM a.fil_dt) ELSE reg_year_mh END AS m_year,
				   CASE WHEN reg_year_fh = 0 THEN EXTRACT(YEAR FROM a.fil_dt_fh) ELSE reg_year_fh END AS f_year,
				   pet_name, 
				   res_name, 
				   pno, 
				   rno 
			FROM main a 
			LEFT JOIN master.casetype b ON CAST(SUBSTRING(fil_no, 1, 2) AS INTEGER) = casecode 
			WHERE diary_no = ?
		";

		// Execute the query with binding
		$query = $this->db->query($sql, [$diary_no]);

		// Fetch the result as a single row array
		$result = $query->getRowArray();

		// Return the result or false if empty
		return $result ? $result : false;
	}
	
	
	public function getAdvocateData($diary_no)
{
    $sql = "
        SELECT sr_no_show AS sr_no,
               partyname,
               adv,
               pet_res_no AS r_no,
               COALESCE(NULLIF(pet_res_show_no, ''), pet_res_no::text) AS pet_res_no,
               mobile,
               c.email,
               advocate_id,
               stateadv,
               adv_type,
               aor_code,
               aor_state,
               name,
               enroll_no,
               EXTRACT(YEAR FROM enroll_date) AS enroll_yr,
               state_id,
               inperson_mobile,
               inperson_email 
        FROM party a 
        RIGHT JOIN advocate b 
            ON a.diary_no = b.diary_no
            AND (CASE 
                WHEN sr_no_show IS NOT NULL 
                     AND sr_no_show != '' 
                     AND pet_res_show_no IS NOT NULL 
                     AND pet_res_show_no != '' 
                THEN sr_no_show = pet_res_show_no 
                ELSE sr_no = pet_res_no 
                END OR pet_res_no = 0)
            AND a.pet_res = b.pet_res 
            AND b.display = 'Y'
        LEFT JOIN master.bar c 
            ON advocate_id = bar_id
        WHERE a.diary_no = ?
          AND a.pet_res = 'P'
          AND pflag = 'P'
          AND isdead = 'N'
        GROUP BY 
            sr_no_show,
            partyname,
            adv,
            pet_res_no,
            pet_res_show_no,
            mobile,
            c.email,
            advocate_id,
            stateadv,
            adv_type,
            aor_code,
            aor_state,
            name,
            enroll_no,
            EXTRACT(YEAR FROM enroll_date),
            state_id,
            inperson_mobile,
            inperson_email
        ORDER BY 
            CAST(sr_no_show AS numeric), 
            CAST(SPLIT_PART(sr_no_show, '.', 1) AS numeric),
            CAST(SPLIT_PART(CONCAT(sr_no_show, '.0'), '.', 2) AS numeric),
            CAST(SPLIT_PART(CONCAT(sr_no_show, '.0.0'), '.', 3) AS numeric),
            CAST(SPLIT_PART(CONCAT(sr_no_show, '.0.0.0'), '.', 4) AS numeric)
    ";

    // Execute the query with binding
    $query = $this->db->query($sql, [$diary_no]);

    // Fetch the result as an array
    $result = $query->getResultArray();
    return $result ? $result : false;
}

public function getAdvocateData_P($diary_no)
{
   $sql = "
        SELECT 
            sr_no_show AS sr_no,
            partyname,
            adv,
            pet_res_no AS r_no,
            COALESCE(NULLIF(pet_res_show_no, ''), pet_res_no::text) AS pet_res_no,
            mobile,
            c.email,
            advocate_id,
            stateadv,
            adv_type,
            aor_code,
            aor_state,
            name,
            enroll_no,
            EXTRACT(YEAR FROM enroll_date) AS enroll_yr,
            state_id,
            inperson_mobile,
            inperson_email
        FROM party a 
        RIGHT JOIN advocate b ON a.diary_no = b.diary_no
            AND (
                (sr_no_show IS NOT NULL AND sr_no_show != '' 
                 AND pet_res_show_no IS NOT NULL AND pet_res_show_no != ''
                 AND sr_no_show = pet_res_show_no)
                OR sr_no = pet_res_no 
                OR pet_res_no = 0
            )
            AND a.pet_res = b.pet_res 
            AND b.display = 'Y'
        LEFT JOIN master.bar c ON advocate_id = bar_id
        WHERE a.diary_no = ?
          AND a.pet_res = 'R'
          AND pflag = 'P'
          AND isdead = 'N'
        GROUP BY 
            sr_no_show,
            partyname,
            adv,
            pet_res_no,
            pet_res_show_no,   
            mobile,
            c.email,
            advocate_id,
            stateadv,
            adv_type,
            aor_code,
            aor_state,
            name,
            enroll_no,
            EXTRACT(YEAR FROM enroll_date),
            state_id,
            inperson_mobile,
            inperson_email
        ORDER BY 
            COALESCE(NULLIF(sr_no_show, '')::numeric, 0), 
            COALESCE(NULLIF(SPLIT_PART(sr_no_show, '.', 1), '')::numeric, 0),
            COALESCE(NULLIF(SPLIT_PART(CONCAT(sr_no_show, '.0'), '.', 2), '')::numeric, 0),
            COALESCE(NULLIF(SPLIT_PART(CONCAT(sr_no_show, '.0.0'), '.', 3), '')::numeric, 0),
            COALESCE(NULLIF(SPLIT_PART(CONCAT(sr_no_show, '.0.0.0'), '.', 4), '')::numeric, 0)
    ";
 
    // Execute the query with binding
    $query = $this->db->query($sql, [$diary_no]);
    
    // Fetch the result as an array
    $result = $query->getResultArray();
    return $result ? $result : false;
}





public function getStateData()
{
    $builder = $this->db->table('master.state');
    $builder->select('state_code as state_code,name, name as Name, name as state_name ,id_no, id_no as cmis_state_id');
    $builder->where('district_code', 0);
    $builder->where('sub_dist_code', 0);
    $builder->where('village_code', 0);
    $builder->where('display', 'Y');
    $builder->where('state_code <', 100);
    $builder->orderBy('name', 'ASC');

    $query = $builder->get();

    // Check if the query returned any rows
    if ($query->getNumRows() > 0) {
        return $query->getResultArray();
    } else {
        // Log the error or handle the case where no data is found
        log_message('error', 'No records found in state table');
        return false;
    }
}

public function getAdvocateDeatil($diary_no,$pet_res)
{	
	// Build the query
	$builder = $this->db->table('advocate');
	$builder->select('bar.name, bar.aor_code')
			->join('master.bar', 'advocate.advocate_id = bar.bar_id')
			->where('diary_no', $diary_no)
			->where('pet_res', $pet_res)
			->where('display', 'Y');

	// Execute the query
	$query = $builder->get();
	if ($query->getNumRows() > 0) {
        return $query->getResultArray();
    } else {
        // Log the error or handle the case where no data is found
        log_message('error', 'No records found in state table');
        return false;
    }
}


public function getAdvocateDetails($diary_no, $advocate_id, $if_pet_res, $party, $party_srno_show, $stateadv, $inperson_condition)
{
    // Load the database connection
    $db = \Config\Database::connect();
    
    // Build the query using the Query Builder
    $builder = $db->table('advocate');
    $builder->select('*')
        ->where('diary_no', $diary_no)
        ->where('advocate_id', $advocate_id)
        ->where('pet_res', $if_pet_res)
        ->where('pet_res_no', $party)
        ->where("COALESCE(NULLIF(pet_res_show_no, '')::numeric, pet_res_no) = $party_srno_show", null, false)
        ->where("
            (stateadv = '$stateadv' OR stateadv = '')
        ", null, false)
        ->where('display', 'Y');
    
    // Apply any additional condition
    if (!empty($inperson_condition)) {
        $builder->where($inperson_condition, null, false);
    }
    //pr($builder->getCompiledSelect());
    // Execute the query
    $query = $builder->get();

    // Check if there are results and return them
    if ($query->getNumRows() > 0) {
        return $query->getResultArray();
    } else {
        // Log the error or handle the case where no data is found
        log_message('error', 'No records found for the given conditions');
        return false;
    }
}

public function getAdvocateDetailsCount($diary_no, $advocate_id, $if_pet_res, $party, $party_srno_show, $stateadv, $inperson_condition)
{
    // Load the database connection
    $db = \Config\Database::connect();
    
    // Build the query using the Query Builder
    $builder = $db->table('advocate');
    $builder->select('*')
        ->where('diary_no', $diary_no)
        ->where('advocate_id', $advocate_id)
        ->where('pet_res', $if_pet_res)
        ->where('pet_res_no', $party)
        ->where("COALESCE(NULLIF(pet_res_show_no, '')::numeric, pet_res_no) = $party_srno_show", null, false)
        ->where("
            (stateadv = '$stateadv' OR stateadv = '')
        ", null, false)
        ->where('display', 'Y');
    
    // Apply any additional condition
    if (!empty($inperson_condition)) {
        $builder->where($inperson_condition, null, false);
    }
    //pr($builder->getCompiledSelect());
    // Execute the query
    $query = $builder->get();

    // Check if there are results and return them
    if ($query->getNumRows() > 0) {
        return $query->getNumRows();
    } else {
        // Log the error or handle the case where no data is found
        log_message('error', 'No records found for the given conditions');
        return false;
    }
}



public function updateAdvocateDetails($ucode, $fil_no_diary, $val, $party, $party_srno_show, $advocate_id_hd, $advtype, $stateadv_hd)
{
    // Load the database connection
    $db = \Config\Database::connect();

    // Build the query using the Query Builder
    $builder = $db->table('advocate');
    
    // Prepare the update data
    $data = [
        'display' => 'N',
        'ent_dt' => 'now()',
        'usercode' => $ucode
    ];
    
    // Apply the update conditions
    $builder->where('diary_no',$fil_no_diary)
        ->where('pet_res', $val)
        ->where('pet_res_no', $party)
        ->where("
            COALESCE(NULLIF(pet_res_show_no, '')::numeric, pet_res_no) = $party_srno_show
        ", null, false)
        ->where('advocate_id', $advocate_id_hd)
        ->where('display', 'Y')
        ->where('adv_type', $advtype)
        ->where("
            (stateadv = '$stateadv_hd' OR stateadv = '')
        ", null, false);

    // Execute the update query
    $builder->update($data);
//echo $db->getLastQuery();
    // Check the result and return an appropriate response
    if ($db->affectedRows() > 0) {
        return true;
    } else {
        log_message('error', 'Update failed or no rows affected');
        return false;
    }
}


public function updateMain($advtype, $ucode, $dno)
{
    // Load the database connection
    $db = \Config\Database::connect();
    
    // Prepare the data to be updated
    $data = [
        'last_usercode' => $ucode,
        'last_dt' => 'NOW()'  // PostgreSQL function for the current timestamp
    ];
    
    // If $advtype is a valid column, add it to the data array
    // Ensure $advtype is safe to use to prevent SQL injection
    if (preg_match('/^[a-zA-Z0-9_]+$/', $advtype)) {
        $data[$advtype] = 'desired_value';  // Replace 'desired_value' with the actual value you want to set
    } else {
        throw new \InvalidArgumentException('Invalid column name');
    }

    // Build the query using Query Builder
    $builder = $db->table('main');
    
    // Update the data
    $builder->where('diary_no', $dno)
        ->update($data);

    // Check if rows were affected and return an appropriate response
    if ($db->affectedRows() > 0) {
        return true;
    } else {
        log_message('error', 'Update failed or no rows affected');
        return false;
    }
}


public function insertAdvocate($ucode,$data,$advocate_id)
{
    // Load the database connection
    $db = \Config\Database::connect();

    if($data['val']!='')
        $if_pet_res = $data['val'];
    else
        $if_pet_res = $data['adv_side'];
    
    // Prepare data for insertion
    $insertData = [
        'diary_no' => $data['dno'],
        'adv_type' => $data['advtype'],
        'pet_res' => $if_pet_res,
        'pet_res_no' => $data['party'],
        'advocate_id' => $advocate_id,
        'adv' => $data['adv_name'],
        'usercode' => $ucode,
        'ent_dt' => 'NOW()', // PostgreSQL function for the current timestamp
        'stateadv' => $data['stateadv'] ?? NULL,
        'aor_state' => $data['advsrc'] ?? NULL,
        'pet_res_show_no' => $data['party_srno_show'] ?? NULL,
        'inperson_mobile' => $data['inperson_mob'] ?? NULL,
        'inperson_email' => $data['inperson_email'] ?? NULL
    ];

    // Build the query using Query Builder
    $builder = $db->table('advocate');
    
    // Insert the data
    $builder->insert($insertData);

    // Check if the insert was successful
    if ($db->affectedRows() > 0) {
        return true;
    } else {
        log_message('error', 'Insert failed');
        return false;
    }
}


public function updateMain_new($diary_no, $advtype, $ucode)
{
    // Load the database connection
    $db = \Config\Database::connect();
    
    // Prepare the data for updating
    $updateData = [
        'last_usercode' => $ucode,
        'last_dt' => 'NOW()'  // PostgreSQL function for the current timestamp
    ];
    
    // Handle dynamic column assignment for $advtype
    // Ensure $advtype is a properly formatted string like 'column_name = value'
    // Example: 'status = \'active\''
    $advtypeUpdate = $advtype; // Ensure $advtype is safely formatted

    // Specify the table
    $builder = $db->table('main');

    // Apply the update
    $builder->set($updateData); // Set columns for update
    $builder->set($advtypeUpdate); // Add dynamic column assignment
    $builder->where('diary_no', $diary_no);

    // Execute the update
    $builder->update();
    
    // Check if the update was successful
    if ($db->affectedRows() > 0) {
        return true;
    } else {
        log_message('error', 'Update failed or no rows affected');
        return false;
    }
}














}
