<?php

namespace App\Models\Listing;

use CodeIgniter\Model;

class PrintModel extends Model
{
    public function getUserdata($usercode)
    {

        $builder = $this->db->table('master.users');
        $builder->select('section');
        $builder->where('display', 'Y');
        $builder->where('usercode', $usercode);
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function listing_dates_fresh()
    {
        $builder = $this->db->table('heardt');
        $builder->select('next_dt')
                    ->where('mainhead', 'M')
                    ->where('next_dt >=', 'CURRENT_DATE - INTERVAL \'7 days\'', false) // Important: false for raw SQL
                    ->whereIn('main_supp_flag', ['1', '2'])
                    ->groupBy('next_dt');
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function get_cause_list_fresh($list_dt, $mainhead, $orderby,$courtno,$board_type,$ma_cc_crlm,$received,$sec_id,$main_suppl,$scn_sts,$limit){

    // **Session Data**
    $session = session()->get('login');
    $ucode = $session['usercode'];
    $usertype = $session['usertype'];
    $section1 = $session['section'];
    // $list_dt='2023-10-10';
    $mdacode ="";
    if($usertype == '14' and $section1!=77 and $section1!=60){
        $builder = $this->db->table('users u');
        $builder->select('GROUP_CONCAT(u2.empid) as allda');
        $builder->join('users u2', 'u2.section = u.section', 'left');
        $builder->where('u.display', 'Y');
        $builder->where('u.usercode', $ucode);
        $builder->groupBy('u2.section');

        $rd = $builder->get()->getRowArray();
        $all_da = $rd ? $rd['allda'] : null;
        $mdacode = "AND tentative_da(m.diary_no) IN ($all_da)";
    }
    if($list_dt == "-1" || $list_dt == "0"){
        $list_dt_data = "";
    }
    else{
        $list_dt_data = "AND h.next_dt = '".$list_dt."'";
    }

    if($limit == "0"){
        $limit ="";
    }
    else {
        $limit = " limit 10";
    }
        
    
    
    if($main_suppl == "0"){
        $main_suppl = "";
    }
    else{
        $main_suppl = "AND h.main_supp_flag = '".$main_suppl."'";
           if($main_suppl == "1"){
               $main_supl_head = "Main List";
           }
            if($main_suppl == "2"){
               $main_supl_head = "Supplimentary List";
           }
    }
    if($ma_cc_crlm == "0"){
        $ma_cc_crlm = "AND (m.casetype_id NOT IN (39,13,14,15,16,9,10,19,20,25,26) AND m.nature::TEXT != '6') ";
    }
    if($ma_cc_crlm == "1"){
        $ma_cc_crlm = "";
    }
    if($received == "0"){
        $received = "";
    }
    if($received == "1"){
       $received = "AND (remarks='SCN -> IB-Ex' and r_by_empid!=0)";
    }
    if($received == "2") {
        $received = "AND ((remarks='SCN -> IB-Ex' and r_by_empid=0) OR (remarks!='SCN -> IB-Ex'))";
    }

    if($scn_sts == "0"){
        $left_join_scn_qry = "";
    }
    if($scn_sts == "1"){
        $left_join_scn_qry = " and i.diary_no is not null ";
    }
    if($scn_sts == "2") {
        $left_join_scn_qry = " and i.diary_no is null ";
    }
    if($board_type == "0"){
        $board_type = "";
    }
    else{
        $board_type = "AND h.board_type = '".$board_type."'";
    }
    if($orderby == "1"){
        $orderby = "courtno, ";
    }
    else if($orderby == "2"){
        $orderby = "id, ";
    }
    else if($orderby == "3"){
        $orderby = "courtno, brd_slno, ";
    }    
    else{
        $orderby = "";        
    }   
     $selected_section = f_selected_values1($sec_id);
    if($selected_section == "0"){
        $sec_id = "";
        $sec_id2 = "";
    }
    else{
        $sec_id = "AND tentative_section(cast(m.diary_no as text)) IN ('".$selected_section."')";
        $sec_id2 = "AND (tentative_section(m.diary_no) is not null) ";
    } 

    if($courtno == "0"){
        $court_no = "";
    }
    else{
        $court_no = "AND r.courtno = '".$courtno."'";
    }
   
    // -- $sec_id 
    // -- $ma_cc_crlm 
    // -- $received
    

    $sql = "SELECT j.* FROM ( SELECT ct.ent_dt AS cl_ent_dt, i.file_id, p.id AS is_printed, r.courtno, tentative_da(CAST(m.diary_no AS INTEGER)) AS daname, tentative_section(m.diary_no) AS section_name, l.purpose, 
            c1.short_description, EXTRACT(YEAR FROM m.active_fil_dt) AS fyr, active_reg_year, active_fil_dt, reg_no_display, active_fil_no, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, 
            diary_no_rec_date, h.* FROM heardt h 
            INNER JOIN main m ON m.diary_no = h.diary_no 
            INNER JOIN master.listing_purpose l ON l.code = h.listorder AND l.display = 'Y' 
            INNER JOIN master.roster r ON r.id = h.roster_id AND r.display = 'Y' $court_no 
            LEFT JOIN cl_printed p ON p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = 'Y' 
            LEFT JOIN master.casetype c1 ON m.active_casetype_id = c1.casecode 
            LEFT JOIN indexing i ON i.diary_no = m.diary_no AND i.display = 'Y' AND i.file_id IS NOT NULL 
            LEFT JOIN fil_trap f ON m.diary_no = f.diary_no 
            LEFT JOIN conct ct ON m.diary_no = ct.diary_no AND ct.list = 'Y' 
            WHERE 
            h.mainhead = '$mainhead'
            $main_suppl 
            $sec_id2  
            $left_join_scn_qry 
            $list_dt_data  
            $board_type  
            $sec_id 
            $ma_cc_crlm 
            $received 
            AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2) 
            AND m.c_status = 'P' 
            AND h.clno > 0 
            AND h.brd_slno > 0 
            AND h.roster_id > 0 
            AND m.diary_no IS NOT NULL 
            GROUP BY h.diary_no,ct.ent_dt,i.file_id,p.id,r.courtno,m.diary_no,l.purpose, c1.short_description, m.active_fil_dt, active_reg_year, active_fil_dt, reg_no_display, active_fil_no, m.pet_name, 
            m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date ) j 
            LEFT JOIN last_heardt l ON j.diary_no = l.diary_no AND j.next_dt != l.next_dt AND (
                    CASE
                        WHEN l.judges IS NULL THEN FALSE 
                        ELSE EXISTS (
                            SELECT 1
                            FROM unnest(string_to_array(l.judges, ',')) AS judge_id
                            WHERE judge_id::INTEGER != 0
                        )
                    END
                ) AND l.judges IS NOT NULL 
            AND l.brd_slno > 0 AND (l.bench_flag IS NULL) 
            WHERE l.diary_no IS NULL ORDER BY j.courtno, CASE WHEN j.section_name IS NULL THEN 9999 ELSE 0 END ASC, j.section_name, j.daname, j.brd_slno, 
            CASE WHEN j.conn_key = j.diary_no THEN '0001-01-01'::DATE ELSE j.diary_no_rec_date END, 
            CASE WHEN j.cl_ent_dt::TEXT IS NOT NULL THEN j.cl_ent_dt::TEXT ELSE '999' END ASC $limit";
            // echo $sql;
            // die();
            $query = $this->db->query($sql);
            $result = $query->getResultArray();
            return $result;
    }

    public function getRollId()
    {

        $builder = $this->db->table('master.usersection');
        $builder->select('*');
        $builder->where('display', 'Y');
        $builder->where('isda', 'Y');
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function getUserSection($sec_id)
    {
        $builder = $this->db->table('master.usersection');
        $builder->select('section_name');
        $builder->where('display', 'Y');
        $builder->where('id', $sec_id);
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function getCauseList($conditions, $list_dt_val,$sec_id,$orderby,$mainhead)
    {
        $ucode = session()->get('login')['usercode'];
        $usertype= session()->get('login')['usertype'];
        $sess_section1= session()->get('login')['section'];
        $list_dt_val = date('Y-m-d',strtotime($list_dt_val));
        
        // $sql = "SELECT h.*, m.*, r.* FROM heardt h 
        //         INNER JOIN main m ON m.diary_no = h.diary_no
        //         INNER JOIN master.roster r ON r.id = h.roster_id 
        //         WHERE h.next_dt = :list_dt:";
        if($orderby == "1"){
            $order_by = "r.courtno, ";
        }
        else if($orderby == "2"){
            $order_by = "us.id, ";
        }
        else{
            $order_by = '';
        }
        
        $sec_id = "";
        $sec_id2 = "";
        if($sec_id != "0" && !empty($sec_id)){
        
            $sql_sec_name ="select section_name from master.usersection where id = $sec_id";
            $rpquery = $this->db->query($sql_sec_name);
            $rp = $rpquery->getResultArray();
            $sec_name= $rp[0]['section_name'];
            $sec_id = " (us.id ='".$sec_id."'  or tentative_section(h.diary_no) = '$sec_name' )";
            $sec_id2 = " us.id is not null";

            $section = "SELECT section_name FROM master.usersection WHERE display = 'Y' and id='$sec_id'";
            $section = $this->db->query($section);
            $section = $section->getResultArray();
            $section_name = $section[0];
        }

            $sql_sec_name_d ="select section_name from master.usersection where id=$sess_section1";
            $re_sec= $this->db->query($sql_sec_name_d);
            $rp = $re_sec->getResultArray();
            $sec_name = $rp[0]['section_name'];
            
            if($usertype == '14' and $sess_section1!=77)
            {
                $sq_u = "SELECT GROUP_CONCAT(u2.usercode) as allda FROM master.users u LEFT JOIN master.users u2 ON u2.section = u.section WHERE u.display = 'Y' AND u.usercode = '$ucode' group by u2.section";
                $re_u= $this->db->query($sq_u);
                $ro_u = $re_u->getResultArray();
                $all_da = $ro_u[0]['allda'];
                // echo $all_da;
                $mdacode = " (m.dacode IN ($all_da)  or m.dacode=0)";
            }
    
            else if ((($usertype=='3')||($usertype=='4')||($usertype=='6')||($usertype=='9')) && $sess_section1!=77)
            {
    
    
                $sql_sec_map="select empid from users where usercode=$_SESSION[dcmis_user_idd]";
                $rs_sec_map= $this->db->query($sql_sec_map);
                $rs_sec_map_data = $rs_sec_map->getResultArray();
                if (!empty($rs_sec_map_data)) { // Check if any results were returned
                    $uempid = $rs_sec_map_data[0]['empid'];
                }
                
                $builder = $this->db->table('user_sec_map');
                $builder->where('display', 'Y');
                $builder->where('empid', $uempid);
                $rs_if_exists = $builder->countAllResults(); 
    
                if($rs_if_exists > 0)
                {
                    $builder = $this->db->table('user_sec_map');
                    $builder->select('GROUP_CONCAT(DISTINCT usec) AS usecs'); // Alias for clarity
                    $builder->where('display', 'Y');
                    $builder->where('empid', $uempid);
                    $rs_usection = $builder->get()->getRow();
                    if ($rs_usection) { // Check if a result was returned
                        $idd = $rs_usection->usecs;
                    }
                    
                    $sec_list = ""; // Initialize the variable correctly
                    if ($idd) { // Only proceed if $idd is not null
                        $builder = $this->db->table('master.usersection');
                        $builder->select('section_name');
                        $builder->whereIn('id', explode(',', $idd)); // Use whereIn for multiple IDs
                        $rs_sec_name = $builder->get()->getResult();

                        foreach ($rs_sec_name as $row) {
                            $sec_list .= ",'" . $row->section_name . "'"; // Correct concatenation
                        }
                        $usec_name = substr($sec_list, 1); // Remove the leading comma
                    }
                    if ($idd) {  // only execute query if $idd is not null
                        $builder = $this->db->table('users');
                        $builder->select('GROUP_CONCAT(usercode) AS usercodes');
                        $builder->where('display', 'Y');
                        $builder->whereIn('section', explode(',', $idd)); // Use whereIn
                        $rs_ar_users = $builder->get()->getRow();
                
                        if ($rs_ar_users) {
                            $all_da = $rs_ar_users->usercodes;
                        } else {
                            $all_da = null; // Or some default
                            log_message('error', "No users found for sections: {$idd}");
                        }
                    }
    
                    $mdacode = " (m.dacode IN ($all_da)  or m.dacode=0)";
                    $section= " (us.id in($idd) or tentative_section(h.diary_no) in ($usec_name)";
                }
            }
            else if($usertype == '17' OR $usertype == '50' OR $usertype == '51'){
                $mdacode = " m.dacode = '$ucode'";
            }
            else{
                $mdacode = "";
            }
            if($ucode == '1' OR $ucode == '469'){
                $cl_print_jo = "";
                $cl_print_jo2 = "";
            }
            else{
                $cl_print_jo = "LEFT JOIN sci_cmis_final.cl_printed p ON p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = 'Y'";
                $cl_print_jo2 = "p.id IS NOT NULL ";
            }
    
            if($ucode==1)
            {
                $section='';
            }
            else if ((($usertype=='3')||($usertype=='4')||($usertype=='6')||($usertype=='9')) && $section!=77)
            {
                $section= " (us.id in('$idd') or tentative_section(h.diary_no) in ('$usec_name'))";
            }
            else
            {
                //  echo "dfjasdfj";
                $section= " (us.id='$sess_section1' or tentative_section(h.diary_no) = '$sec_name' )";
                //ech "section is ".$section;
            }
            
            if($section != '' ){ $where[] = $section; } 
            if($cl_print_jo2 != '' ){ $where[] = $cl_print_jo2; } 
            if($sec_id2 != '' ){ $where[] = $sec_id2; } 
            if($mdacode != '' ){ $where[] = $mdacode; } 
            if($mainhead != '' ){ $where[] = "h.mainhead = "."'".$mainhead."'"; } 
            $where[] = "h.next_dt = "."'".$list_dt_val."'";
            $where[] = "(h.main_supp_flag = 1 OR h.main_supp_flag = 2)";
            $where[] = "h.roster_id > 0";
            $where[] = "m.diary_no IS NOT NULL";
            $where[] = "m.c_status = 'P'";
            if($conditions !== ''){
                $conditions = ' AND '.$conditions;
            }
            
            
            $con = implode(' AND ', $where); 
            
        $sql = "SELECT tentative_section(h.diary_no) AS dno, r.courtno, u.name, us.section_name, l.purpose, c1.short_description, EXTRACT(YEAR FROM m.active_fil_dt) AS fyr, 
                        active_reg_year, active_fil_dt, active_fil_no, m.reg_no_display, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date, 
                        remark,m.active_casetype_id, h.* 
                        FROM heardt h 
                        INNER JOIN main m ON m.diary_no = h.diary_no 
                        INNER JOIN master.listing_purpose l ON l.code = h.listorder AND l.display = 'Y' 
                        INNER JOIN master.roster r ON r.id = h.roster_id AND r.display = 'Y' AND r.courtno = 1 
                        LEFT JOIN brdrem br ON cast(br.diary_no as bigint) = cast(m.diary_no as bigint) 
                        LEFT JOIN master.casetype c1 ON m.active_casetype_id = c1.casecode 
                        LEFT JOIN master.users u ON u.usercode = m.dacode AND (u.display = 'Y' OR u.display IS NULL) 
                        LEFT JOIN master.usersection us ON us.id = u.section
                    
                    WHERE $con
                       
                          $conditions
                        
                    GROUP BY h.diary_no,r.courtno,u.name, us.section_name, l.purpose, c1.short_description, m.active_fil_dt,active_reg_year, active_fil_dt, active_fil_no, m.reg_no_display
                    , m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date, remark,m.active_casetype_id
                    ORDER BY 
                        $order_by 
                        h.brd_slno,
                        COALESCE(NULLIF(us.section_name, ''), '9999') ASC,
                        us.section_name,
                        u.name,
                        CASE WHEN h.conn_key = h.diary_no THEN NULL ELSE m.diary_no_rec_date::date END ASC
                        ";
                    //     echo $sql;
                    //    die();
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        $sno = 1;
        $arr_result = [];
        foreach($result as $ro){
            $remark=$ro['remark'];
                    $sno1 = $sno % 2;
                    $dno = $ro['diary_no'];
                    $diary_no_rec_date = date('d-m-Y', strtotime($ro['diary_no_rec_date']));
                    if($ro['active_fil_dt'] !== null){ $active_fil_dt = date('d-m-Y', strtotime($ro['active_fil_dt'])); } else { $active_fil_dt = ''; }
                    
                    $conn_no = $ro['conn_key'];
                    $m_c = "";
                    if($conn_no == $dno){
                        $m_c = "Main";
                    }
                    if($conn_no != $dno AND $conn_no > 0){
                        $m_c = "Conn.";
                    }
                    $coram = $ro['coram'];
                    if($ro['board_type'] == "J"){
                        $board_type1 = "Court";
                    }
                    if($ro['board_type'] == "C"){
                        $board_type1 = "Chamber";
                    }
                    if($ro['board_type'] == "R"){
                        $board_type1 = "Registrar";
                    }
                    $filno_array = explode("-",$ro['active_fil_no']);

                    if(empty($ro['reg_no_display'])){
                        $fil_no_print = "Unregistred";
                    }
                    else{
                        $fil_no_print = $ro['reg_no_display'];

                    }
                   


                    if($ro['pno'] == 2){
                        $pet_name = $ro['pet_name']." AND ANR.";
                    }
                    else if($ro['pno'] > 2){
                        $pet_name = $ro['pet_name']." AND ORS.";
                    }
                    else{
                        $pet_name = $ro['pet_name'];
                    }



                    if($ro['rno'] == 2){
                        $res_name = $ro['res_name']." AND ANR.";
                    }
                    else if($ro['rno'] > 2){
                        $res_name = $ro['res_name']." AND ORS.";
                    }
                    else{
                        $res_name = $ro['res_name'];
                    }
                    $padvname = ""; $radvname = ""; $impldname= "";
                    $advsql = "SELECT a.*, GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'R' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) r_n,
                                GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'P' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) p_n,
                                GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'I' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) i_n FROM 
                                (SELECT a.diary_no, b.name, 
                                GROUP_CONCAT(IFNULL(a.adv,'') ORDER BY IF(pet_res = 'I', 99, 0) ASC, adv_type DESC, pet_res_no ASC) grp_adv, 
                                a.pet_res, a.adv_type, pet_res_no
                                FROM advocate a LEFT JOIN bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y' WHERE a.diary_no='".$ro["diary_no"]."' AND a.display = 'Y' GROUP BY a.diary_no, b.name
                                ORDER BY IF(pet_res = 'I', 99, 0) ASC, adv_type DESC, pet_res_no ASC) a GROUP BY diary_no";
                    
                    $advsql =  "SELECT
                                a.diary_no,
                                STRING_AGG(CASE WHEN pet_res = 'R' THEN name || grp_adv ELSE NULL END, '' ORDER BY adv_type DESC, pet_res_no ASC) AS r_n,
                                STRING_AGG(CASE WHEN pet_res = 'P' THEN name || grp_adv ELSE NULL END, '' ORDER BY adv_type DESC, pet_res_no ASC) AS p_n,
                                STRING_AGG(CASE WHEN pet_res = 'I' THEN name || grp_adv ELSE NULL END, '' ORDER BY adv_type DESC, pet_res_no ASC) AS i_n
                            FROM (
                                SELECT
                                    a.diary_no,
                                    b.name,
                                    STRING_AGG(COALESCE(a.adv, ''), '' ORDER BY CASE WHEN pet_res = 'I' THEN 99 ELSE 0 END, adv_type DESC, pet_res_no ASC) AS grp_adv,
                                    a.pet_res,
                                    a.adv_type,
                                    pet_res_no
                                FROM advocate a
                                LEFT JOIN master.bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y'
                                WHERE a.diary_no = '".$ro["diary_no"]."' AND a.display = 'Y' 
                                GROUP BY a.diary_no, b.name,a.pet_res,
                                    a.adv_type,
                                    pet_res_no
                                ORDER BY CASE WHEN pet_res = 'I' THEN 99 ELSE 0 END, adv_type DESC, pet_res_no ASC
                            ) AS a
                            GROUP BY diary_no";
                            $resultsadv = $this->db->query($advsql);
                            $rowadv = $resultsadv->getResultArray();

                            if(count($rowadv) > 0) {
                                $radvname=  $rowadv[0]["r_n"];
                                $padvname=  $rowadv[0]["p_n"];
                                $impldname = $rowadv[0]["i_n"];
                            }


                    if(($ro['section_name'] == null OR $ro['section_name'] == '') AND $ro['ref_agency_state_id'] != '' and $ro['ref_agency_state_id'] != 0 )
                    {

                        if($ro['active_reg_year']!=0)
                            $ten_reg_yr = $ro['active_reg_year'];
                        else
                            $ten_reg_yr = date('Y',strtotime($ro['diary_no_rec_date']));

                        if($ro['active_casetype_id']!=0)
                            $casetype_displ = $ro['active_casetype_id'];
                        else if($ro['casetype_id']!=0)
                            $casetype_displ = $ro['casetype_id'];
                        $builder = $this->db->table('master.da_case_distribution a');
                        $builder->select('dacode, section_name, name');
                        $builder->join('master.users b', 'usercode = dacode', 'left');
                        $builder->join('master.usersection c', 'b.section = c.id', 'left');
                        $builder->where('case_type', $casetype_displ); // Use parameter binding if $casetype_displ is from user input
                        $builder->where("{$ten_reg_yr} BETWEEN case_f_yr AND case_t_yr"); // Important: Parameter binding for $ten_reg_yr!
                        $builder->where('state', $ro['ref_agency_state_id']); // Parameter binding here too!
                        $builder->where('a.display', 'Y');

                        $section_ten_row = $builder->get()->getResultArray();
                        if(count($section_ten_row) > 0) {
                            if($ro['section_name'] =='')
                            {
                                $ro['section_name']=  $ro['dno'];
                            }
                            else
                            {
                                $ro['section_name']=$section_ten_row[0]["section_name"];
                                // echo" dacode is ". $section_ten_row[dacode];
                            }
                            if($section_ten_row[0]["dacode"] == 0)
                                $ro['name']="no dacode";
                        }
                        if($sno1 == '1'){  $sno1 = $dno; } else { $sno1 = $dno;  }
                            
                       
                    }
                    $padvname_trimmed = ""; // Initialize to an empty string in case it's null
                    if ($padvname !== null) {  // Check for null
                        $padvname_trimmed = trim($padvname, ",");
                    }

                    $radvname_trimmed = "";
                    if ($radvname !== null) {
                        $radvname_trimmed = trim($radvname, ",");
                    }

                    $impldname_trimmed = "";
                    if ($impldname !== null) {
                        $impldname_trimmed = trim($impldname, ",");
                    }
        $arr_result[] = [
            'sno'=>$sno,
            'courtno'=>$ro['courtno'],
            'itemno'=>$ro['brd_slno'],
            'diaryno'=>substr_replace($ro['diary_no'], '/', -4, 0). 'Ddt '.$diary_no_rec_date ,
            'regno'=>$fil_no_print.' Rdt '.$active_fil_dt,
            'petitioner_respondent'=>$pet_name." Vs ".$res_name,
            'advocate'=>str_replace(",", ", ", $padvname_trimmed) . "Vs" . str_replace(",", ", ", $radvname_trimmed) . " " . str_replace(",", ", ", $impldname_trimmed),
            'daname'=>$ro['name'],
            'purpose'=>$ro['purpose'],
            'remarks'=>$ro['remark'],
        ];
        $sno++;
     }
     return $arr_result;
    }

    public function isCaseListPrinted($q_next_dt, $partno, $mainhead, $roster_id)
    {
        $roster_ids = explode(',', $roster_id);
        $roster_ids = array_filter($roster_ids, function ($id) {
            return !empty($id) && is_numeric($id);
        });

        $builder = $this->db->table('cl_printed');
        $builder->select('*');
        $builder->where('next_dt', $q_next_dt);
        $builder->where('part', $partno);
        $builder->where('m_f', $mainhead);
        $builder->where('display', 'Y');
        if (!empty($roster_ids)) {
            $builder->whereIn('roster_id', $roster_ids);
        }

        $query = $builder->get();
        return $query->getNumRows() > 0;
    }

    //////
    public function getListingPurpose($p_listorder)
    {
        $builder = $this->db->table('master.listing_purpose');
        $builder->select("GROUP_CONCAT(code ORDER BY priority) AS code, priority, 
                          CASE WHEN code IN (4, 5, 7) THEN 1 ELSE 2 END AS mand");
        $builder->where('display', 'Y');
        $builder->where('code !=', 49);
        $builder->orderBy('priority');
        $builder->groupBy('mand');

        if ($p_listorder) {
            $builder->where($p_listorder);
        }

        return $builder->get()->getResultArray();
    }

    public function getRosterData($sell_roster_id, $m_ff, $q_next_dt, $mainhead, $main_supp)
    {
        $builder = $this->db->table('main m');
        $builder->select("GROUP_CONCAT(t.rid) rid, t.cat, submaster_id, h.*");
        $builder->join('heardt h', 'h.diary_no = m.diary_no', 'left');
        $builder->join('master.listing_purpose l', 'l.code = h.listorder', 'left');
        $builder->join('rgo_default rd', 'rd.fil_no = h.diary_no AND rd.remove_def = "N"', 'left');
        $builder->join('mul_category mc', 'mc.diary_no = m.diary_no', 'left');
        $builder->join('(SELECT GROUP_CONCAT(r.id) rid, c.* FROM master.roster r 
                        LEFT JOIN category_allottment c ON c.ros_id = r.id AND c.display = "Y"
                        WHERE r.id IN (' . implode(',', $sell_roster_id) . ') AND r.m_f = "' . $m_ff . '" 
                        AND IF(r.to_date = "0000-00-00", r.from_date = "' . $q_next_dt . '", "' . $q_next_dt . '" BETWEEN r.from_date AND r.to_date) 
                        AND r.display = "Y" GROUP BY submaster_id) t', 'mc.submaster_id = t.cat', 'inner');

        $builder->where('rd.fil_no IS NULL');
        $builder->whereNotIn('m.active_casetype_id', [9, 10, 25, 26]);
        $builder->where('mc.display', 'Y');
        $builder->whereNotIn('mc.submaster_id', [911, 912, 914, 239, 240, 241, 242, 243]);
        $builder->where('m.c_status', 'P');
        $builder->where('(m.diary_no = m.conn_key OR m.conn_key=0 OR m.conn_key = "" OR m.conn_key IS NULL)');
        $builder->where('h.main_supp_flag', 0);
        $builder->whereNotIn('h.subhead', [801, 817, 818, 819, 820, 848, 849, 850, 854]);
        $builder->where('h.mainhead', $mainhead);
        $builder->where('h.next_dt !=', '0000-00-00');
        $builder->where('h.roster_id', 0);
        $builder->where('h.brd_slno', 0);
        $builder->where('h.board_type', 'J');

        if ($main_supp == 2) {
            $builder->where('h.next_dt', $q_next_dt);
        } else {
            $builder->groupStart()
                ->where('h.next_dt =', $q_next_dt)
                ->orWhere('h.next_dt <= CURDATE()')
                ->groupEnd();
        }

        $builder->orderBy('CAST(RIGHT(h.diary_no, 4) AS UNSIGNED)', 'ASC');
        $builder->orderBy('CAST(LEFT(h.diary_no, LENGTH(h.diary_no)-4) AS UNSIGNED)', 'ASC');
        $builder->limit(600);

        return $builder->get()->getResultArray();
    }

    //////



    public function getCaseDetails($mainhead, $list_dt, $court_no, $cl_print_jo, $sec_id, $sec_id2, $mdacode, $lp, $board_type, $section, $orderby)
    {

        if ($orderby == "1") {
            $orderby = "r.courtno, ";
        } else if ($orderby == "2") {
            $orderby = "us.id, ";
        } else {
            $orderby = "";
        }

        $builder = $this->db->table('sci_cmis_final.heardt h');

        // SELECT fields
        $builder->select("tentative_section(h.diary_no) as dno, 
                          r.courtno, u.name, us.section_name, l.purpose, 
                          c1.short_description, YEAR(m.active_fil_dt) as fyr, 
                          m.active_reg_year, m.active_fil_dt, m.active_fil_no, 
                          m.reg_no_display, m.pet_name, m.res_name, m.pno, 
                          m.rno, m.casetype_id, m.ref_agency_state_id, 
                          m.diary_no_rec_date, m.remark, h.*");
        // INNER JOINs
        $builder->join('sci_cmis_final.main m', 'm.diary_no = h.diary_no', 'inner');
        $builder->join('sci_cmis_final.listing_purpose l', 'l.code = h.listorder AND l.display = "Y"', 'inner');
        $builder->join('sci_cmis_final.roster r', 'r.id = h.roster_id AND r.display = "Y"', 'inner');

        // LEFT JOINs
        $builder->join('brdrem br', 'br.diary_no = m.diary_no', 'left');
        $builder->join('sci_cmis_final.casetype c1', 'm.active_casetype_id = c1.casecode', 'left');
        $builder->join('users u', 'u.usercode = m.dacode AND (u.display = "Y" OR u.display IS NULL)', 'left');
        $builder->join('usersection us', 'us.id = u.section', 'left');

        // WHERE clauses
        $builder->where('h.mainhead', $mainhead);

        $builder->where('h.next_dt', $list_dt);
        $builder->where('(h.main_supp_flag = 1 OR h.main_supp_flag = 2)');
        $builder->where('h.roster_id >', 0);
        $builder->where('m.diary_no IS NOT NULL');
        $builder->where('m.c_status', 'P');

        // Dynamic conditions
        if ($court_no) {
            $builder->where($court_no);
        }
        if ($cl_print_jo) {
            $builder->where($cl_print_jo);
        }
        if ($sec_id) {
            $builder->where($sec_id);
        }
        if ($sec_id2) {
            $builder->where($sec_id2);
        }
        if ($mdacode) {
            $builder->where($mdacode);
        }
        if ($lp) {
            $builder->where($lp);
        }
        if ($board_type) {
            $builder->where($board_type);
        }
        if ($section) {
            $builder->where($section);
        }

        // GROUP BY
        $builder->groupBy('h.diary_no');

        // ORDER BY
        $builder->orderBy($orderby);
        $builder->orderBy('r.courtno');
        $builder->orderBy('h.brd_slno');
        $builder->orderBy('IF(us.section_name IS NULL, 9999, 0)', 'ASC');
        $builder->orderBy('us.section_name');
        $builder->orderBy('u.name');
        $builder->orderBy('IF(h.conn_key = h.diary_no, "0000-00-00", m.diary_no_rec_date)', 'ASC');

        // Execute and return the results as an array
        // return $builder->get()->getResultArray();

        $query = $builder->getCompiledSelect();
        pr($query);
    }
    public function section_name()
    {
        
        $builder = $this->db->table('master.usersection');
        $builder->select('*')
            ->where('display', 'Y')
            ->where('isda', 'Y')
            ->orderBy('section_name');
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }
    public function listing_dates()
    {
        $sql = "SELECT DISTINCT next_dt FROM advance_allocated WHERE next_dt >= CURRENT_DATE ORDER BY next_dt";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

    public function purpose_of_listing()
    {
        $builder = $this->db->table('master.listing_purpose');
        $builder->select('*')
            ->where('display', 'Y')
            ->where('code !=', 99)
            ->orderBy('priority');
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }
    public function get_usersection($sec_id)
    {
        
        $builder = $this->db->table('master.usersection');
        $builder->select('section_name');
        if($sec_id != 'all'){
        $builder->where('id', $sec_id);
        }
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }
    public function get_list_section($listtype, $list_dt1, $sec_id, $sec_name, $ucode, $usertype, $section1, $lp)
    {
        //$list_dt1 = '2023-01-02';
        if($list_dt1 !== '-1'){
        $builder = $this->db->table('advance_allocated h');

        $builder->select('tentative_section(h.diary_no) AS dno, u.name, us.section_name, l.purpose, c1.short_description, active_reg_year, active_fil_dt, active_fil_no, 
        m.reg_no_display, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date, remark, h.*');

        $builder->join('main m', 'cast(m.diary_no as bigint) = h.diary_no::bigint');
        $builder->join('master.listing_purpose l', 'l.code = h.listorder AND l.display = \'Y\'');
        $builder->join('brdrem br', 'cast(br.diary_no as text) = m.diary_no::text', 'left'); // Removed unnecessary cast if diary_no is already an integer in brdrem
        $builder->join('master.casetype c1', 'm.active_casetype_id = c1.casecode', 'left');
        $builder->join('master.users u', 'u.usercode = m.dacode AND (u.display = \'Y\' OR u.display IS NULL)', 'left');
        $builder->join('master.usersection us', 'us.id = u.section', 'left');


        if ($lp == "all") {
            // No where clause needed for all
        } else {
            $builder->where('h.listorder', $lp);
        }

        if ($listtype == 'D') {
            // No join needed in this case
        } else {
            $builder->join('advance_cl_printed p', 'p.next_dt = h.next_dt AND p.display = \'Y\'', 'left');
            $builder->where('p.id IS NOT NULL');
        }
        if ($sec_id == 'all') {
            // No where clause needed
        } else {
            $builder->groupStart();
            $builder->where('us.id', $sec_id);
            $builder->orWhere('tentative_section(h.diary_no)', $sec_name);
            $builder->groupEnd();
        }

        if ($ucode == 1) {
            // No where clause needed
        } else {
            $builder->groupStart();
            $builder->where('us.id', $section1);
            $builder->orWhere('tentative_section(h.diary_no)', $sec_name);
            $builder->groupEnd();
            
        }


        if ($usertype == '14' and $section1 != 77) {
            $sq_u = $this->db->query("SELECT GROUP_CONCAT(u2.usercode) as allda FROM users u LEFT JOIN users u2 ON u2.section = u.section WHERE u.display = 'Y' AND u.usercode = '$ucode' group by u2.section");
            $ro_u = $sq_u->getResultArray();
            $all_da = $ro_u[0]['allda']; // Access the first element of the result array
            $builder->where(function($query) use ($all_da){
                $query->whereIn('m.dacode', explode(',', $all_da))
                    ->orWhere('m.dacode', 0);
            });

        } else if ($usertype == '17' or $usertype == '50' or $usertype == '51') {
            $builder->where('m.dacode', $ucode);
        }
        
            $builder->where('h.next_dt', $list_dt1);
        
        
        $builder->where('m.diary_no IS NOT NULL');
        $builder->where('m.c_status', 'P');

        $builder->groupBy('h.diary_no, h.brd_slno, h.next_dt, h.id, u.name, us.section_name, l.purpose, c1.short_description, active_reg_year, active_fil_dt, active_fil_no, m.reg_no_display, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date, remark, h.*');
        $builder->orderBy('us.section_name, u.name, h.brd_slno');
        // $builder->limit(10);
        // echo $builder->getCompiledSelect();
        // die();
        $query = $builder->get();
        $get_list_section = $query->getResultArray();

        // $query = $this->db->query($sql);
        // $get_list_section = $query->getResultArray();
        $i = 1;
        if(count($get_list_section)>0){
        foreach ($get_list_section as $ro) {
            $remark = $ro['remark'];
            $dno = $ro['diary_no'];
            $diary_no_rec_date = date('d-m-Y', strtotime($ro['diary_no_rec_date']));
            //$active_fil_dt = date('d-m-Y', strtotime($ro['active_fil_dt']));
            $active_fil_dt = $ro['active_fil_dt'];
            $conn_no = $ro['conn_key'];
            $m_c = "";
            if ($conn_no == $dno) {
                $m_c = "Main";
            }
            if ($conn_no != $dno and $conn_no > 0) {
                $m_c = "Conn.";
            }
            //$coram = $ro['coram'];
            if ($ro['board_type'] == "J") {
                $board_type1 = "Court";
            }
            if ($ro['board_type'] == "C") {
                $board_type1 = "Chamber";
            }
            if ($ro['board_type'] == "R") {
                $board_type1 = "Registrar";
            }
            if($ro['pno'] == 2){
                $pet_name = $ro['pet_name']." AND ANR.";
            }
            else if($ro['pno'] > 2){
                $pet_name = $ro['pet_name']." AND ORS.";
            }
            else{
                $pet_name = $ro['pet_name'];
            }
            if($ro['rno'] == 2){
                $res_name = $ro['res_name']." AND ANR.";
            }
            else if($ro['rno'] > 2){
                $res_name = $ro['res_name']." AND ORS.";
            }
            else{
                $res_name = $ro['res_name'];
            }
            $filno_array = explode("-", $ro['active_fil_no']);

            if (empty($ro['reg_no_display'])) {
                $fil_no_print = "Unregistred";
            } else {
                $fil_no_print = $ro['reg_no_display'];
            }
            $padvname = "";
            $radvname = "";
            $impldname = "";  
           
            $arr_result[] = [
                'SNO' => $i,
                'Item_No' => $ro['brd_slno'],
                'Diary_No' => substr_replace($ro["diary_no"], "/", -4, 0),
                'Reg_No' => $fil_no_print . "<br>Rdt " . $active_fil_dt,
                'Petitioner' => $pet_name . "<br/>Vs<br/>" . $res_name,
                'Advocate' => str_replace(",", ", ", trim($padvname, ",")) . "<br/>Vs<br/>" . str_replace(",", ", ", trim($radvname, ",")) . " ", str_replace(",", ", ", trim($impldname, ",")),
                'Section_Name' => $ro["section_name"],
                'DA_Name' => $ro["name"],
                'Statutory_Info' => $remark,
                'Listed_Before' => $board_type1,
                'Purpose' => $ro["purpose"],
                'Trap' => ''

            ];
            $i++;
        }
    } else {
        $arr_result = []; 
    }
        }
        else {
            $arr_result = [];
        }

        return json_encode($arr_result); 
    }

    function get_party_contact_details($dno, $flag)
    {
        if($flag == 'P'){
            $pet_res = " and pet_res = 'P' ";
            $flag = "P";
        } else{
            $flag = "R";
            $pet_res = " and pet_res != 'P' ";
        }
        $sql="select pet_res, partysuff, contact, email from party where diary_no = $dno $pet_res and (length(contact) = 10 or (email is not null and email != '')) and pflag = 'P'";
        
        $results =  $this->db->query($sql)->getResultArray();
        $str = "";
        if (!empty($results)) {
            foreach ($results as $row) {
                $str .= " ($flag : ".$row['partysuff'].", M : ".$row['contact'].", E : ".$row['email'].") ";
            }
            return rtrim($str,",");
        }
    }

    function get_advocate_details($diary_no)
    {   
        $return = [];
        $sql = "SELECT 
                    a.*, 
                    STRING_AGG(
                        (a.name || ' (A : ' || a.aor_code || ', M : ' || a.mobile || ', E : ' || a.email || ') ' || 
                        CASE WHEN pet_res = 'R' THEN grp_adv END)::text, 
                        ''::text
                    ORDER BY adv_type DESC, pet_res_no ASC) AS r_n, 

                    STRING_AGG(
                        (a.name || ' (A : ' || a.aor_code || ', M : ' || a.mobile || ', E : ' || a.email || ') ' || 
                        CASE WHEN pet_res = 'P' THEN grp_adv END)::text, 
                        ''::text
                    ORDER BY adv_type DESC, pet_res_no ASC) AS p_n, 

                    STRING_AGG(
                        (a.name || ' (A : ' || a.aor_code || ', M : ' || a.mobile || ', E : ' || a.email || ') ' || 
                        CASE WHEN pet_res = 'I' THEN grp_adv END)::text, 
                        ''::text
                    ORDER BY adv_type DESC, pet_res_no ASC) AS i_n, 

                    STRING_AGG(
                        (a.name || ' (A : ' || a.aor_code || ', M : ' || a.mobile || ', E : ' || a.email || ') ' || 
                        CASE WHEN pet_res = 'N' THEN grp_adv END)::text, 
                        ''::text
                    ORDER BY adv_type DESC, pet_res_no ASC) AS intervenor
                    FROM 
                    (SELECT 
                        a.diary_no, 
                        b.name, 
                        b.aor_code, 
                        b.mobile, 
                        b.email, 
                        STRING_AGG(
                            COALESCE(a.adv, '')::text,
                            ''::text                    
                            ORDER BY 
                                CASE 
                                    WHEN pet_res IN ('I', 'N') THEN 99 
                                    ELSE 0 
                                END ASC, 
                                adv_type DESC, 
                                pet_res_no ASC
                        ) AS grp_adv, 
                        a.pet_res, 
                        a.adv_type, 
                        a.pet_res_no
                    FROM 
                        advocate a 
                        LEFT JOIN master.bar b ON a.advocate_id = b.bar_id 
                        AND b.isdead != 'Y' 
                    WHERE 
                        a.diary_no = '" . $diary_no . "' 
                        AND a.display = 'Y' 
                    GROUP BY 
                        a.diary_no, 
                        b.name,
                        b.aor_code,
                        b.mobile,
                        b.email,
                        a.pet_res,
                        a.adv_type,
                        a.pet_res_no
                    ORDER BY 
                        CASE 
                            WHEN pet_res IN ('I', 'N') THEN 99 
                            ELSE 0 
                        END ASC, 
                        adv_type DESC, 
                        pet_res_no ASC
                    ) a 
                    GROUP BY 
                    a.diary_no, a.name, a.aor_code, a.mobile, a.email, a.grp_adv, a.pet_res, a.adv_type, a.pet_res_no";

                    $query = $this->db->query($sql);
                    if ($query->getNumRows() >= 1) {
                        $return = $query->getRowArray();
                        
                    }
                    return $return;
    }
    public function get_section_name($diary_no){
        $return =[];
        $sql = "SELECT tentative_section(" . $diary_no . ") as section_name";
        $query = $this->db->query($sql);
                    if ($query->getNumRows() >= 1) {
                        $return = $query->getRowArray();
                        
                    }
                    return $return;

    }

    public function getDocnumDocYear($diary_no)
    {
        $sql = "SELECT * FROM (
                SELECT h.diary_no, d.docnum, d.docyear, d.doccode1,
                    (CASE WHEN dm.doccode1 = 19 THEN other1 ELSE dm.docdesc END) AS docdesp,
                    d.other1, d.iastat
                FROM heardt h
                INNER JOIN docdetails d ON d.diary_no = h.diary_no
                INNER JOIN master.docmaster dm ON dm.doccode1 = d.doccode1 AND dm.doccode = d.doccode
                WHERE h.diary_no = '$diary_no'
                AND d.doccode = 8
                AND dm.display = 'Y'
                AND array_position(
                        string_to_array(
                            REPLACE(REPLACE(REPLACE(listed_ia, '/', ''), ' ', ''), ',', ','),
                            ','
                        ),
                        CAST(CONCAT(docnum, docyear) AS TEXT)
                    ) > 0
            ) a
            WHERE docdesp != ''
            ORDER BY docdesp";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

    public function get_not_before_reason($list_before_remark){
        $return =[];
        $sql = "SELECT res_add FROM master.not_before_reason WHERE res_id = '".$list_before_remark."'";
        $query = $this->db->query($sql);
                    if ($query->getNumRows() >= 1) {
                        $return = $query->getRowArray();
                        
                    }
                    return $return;

    }
    public function get_lowerct_casetype($diaryNo){

    $builder = $this->db->table('lowerct a')
                    ->select('a.lct_dec_dt, a.lct_caseno, a.lct_caseyear, ct.short_description AS type_sname')
                    ->join('master.casetype ct', "ct.casecode = a.lct_casetype AND ct.display = 'Y'", 'left')
                    ->where('a.diary_no', $diaryNo)
                    ->where('a.is_order_challenged', "Y")
                    ->where('a.lw_display', "Y")
                    ->where('a.ct_code', 4)
                    ->orderBy('a.lct_dec_dt', 'DESC');

    $query = $builder->get();
    $results = $query->getResultArray(); 
    return $results;
    }

}
