<?php
namespace App\Models\Filing;

use CodeIgniter\Model;

class PartyModel extends Model{

    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
    }

    public function getpartyList($dno){
        $builder = $this->db->table("party");
        $builder->select("*");
        $builder->where('diary_no',$dno);
        $builder->where('pet_res !=','');
        $builder->whereNotIn('pflag', ['T', 'Z']);
        // $builder->orderBy('sr_no', 'ASC');
        // $builder->orderBy('sr_no_show', 'ASC');
       
        $builder->orderBy("CAST(CASE WHEN split_part(\"sr_no_show\", '.', 1) = '' THEN '0' ELSE split_part(\"sr_no_show\", '.', 1) END AS INTEGER)", 'ASC', false);
        $builder->orderBy("CAST(CASE WHEN split_part(\"sr_no_show\", '.', 2) = '' THEN '0' ELSE split_part(\"sr_no_show\", '.', 2) END AS INTEGER)", 'ASC', false);
        $builder->orderBy('sr_no_show', 'ASC');

        // $queryString = $builder->getCompiledSelect();
        // echo $queryString;
        // exit();
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function getCopiedpartyList($dno){
        $builder = $this->db->table("party");
        $builder->select("*");
        $builder->where('diary_no',$dno);
        $builder->where('pet_res !=','');
        $builder->where('pflag', 'Z');
        // $builder->orderBy('sr_no_show', 'ASC');
        $builder->orderBy("CAST(CASE WHEN split_part(\"sr_no_show\", '.', 1) = '' THEN '0' ELSE split_part(\"sr_no_show\", '.', 1) END AS INTEGER)", 'ASC', false);
        $builder->orderBy("CAST(CASE WHEN split_part(\"sr_no_show\", '.', 2) = '' THEN '0' ELSE split_part(\"sr_no_show\", '.', 2) END AS INTEGER)", 'ASC', false);
        $builder->orderBy('sr_no_show', 'ASC');
        
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function getLRList($dno){

        $builder = $this->db->table("party");
        $builder->select("pet_res,sr_no,sr_no_show,partyname");
        $builder->where('diary_no',$dno);
        $builder->where('pflag','P');
        $builder->where('pet_res !=','');
        //$builder->orderBy("pet_res,CAST(split_part(sr_no_show,'.',1) AS NUMERIC(20)) ,CAST(split_part(split_part(CONCAT(sr_no_show,'.0'),'.',2),'.',-1) AS NUMERIC(20)) ,CAST(split_part(split_part(CONCAT(sr_no_show,'.0.0'),'.',3),'.',-1) AS NUMERIC(20)),CAST(split_part(split_part(CONCAT(sr_no_show,'.0.0.0'),'.',4),'.',-1) AS NUMERIC(20))");
        
		$builder->orderBy("
			pet_res,
			CAST(sr_no AS BIGINT),			 
			COALESCE(NULLIF(split_part(sr_no_show, '.', 2), '')::BIGINT, 0),
			COALESCE(NULLIF(split_part(sr_no_show, '.', 3), '')::BIGINT, 0),
			COALESCE(NULLIF(split_part(sr_no_show, '.', 4), '')::BIGINT, 0)
		");
		
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function getlowercase($dno){
        // pr($dno);
        
        
        $builder = $this->db->table("lowerct");
        $builder->select("lower_court_id,lct_dec_dt,l_dist,polstncode,crimeno,crimeyear,ct_code,l_state,lct_casetype,lct_caseno,lct_caseyear");
        $builder->select("CASE WHEN ct_code = 3 THEN (
            SELECT 'name' FROM master.state s
            WHERE s.id_no = l_dist AND s.display = 'Y' Limit 1
        ) ELSE (
            SELECT agency_name FROM master.ref_agency_code c
            WHERE c.cmis_state_id = l_state AND c.id = l_dist AND c.is_deleted = 'f' Limit 1
        ) END AS agency_name");
        $builder->select("CASE WHEN ct_code = 4 THEN (
            SELECT skey FROM master.casetype ct
            WHERE ct.display = 'Y' AND ct.casecode = lct_casetype Limit 1
        ) ELSE (
            SELECT type_sname FROM master.lc_hc_casetype d
            WHERE d.lccasecode = lct_casetype AND d.display = 'Y' Limit 1
        ) END AS type_sname");
        $builder->where('diary_no',$dno);
        $builder->where('lw_display','Y');
        $builder->where('is_order_challenged','Y');
        $builder->orderBy("lower_court_id");

        // pr($builder->getCompiledSelect());


        // $queryString = $builder->getCompiledSelect();
        // echo $queryString;
        // exit();

        $query = $builder->get();
        $result = $query->getResultArray();
        // $sql = "SELECT lower_court_id,lct_dec_dt,l_dist,polstncode,crimeno,crimeyear,ct_code,l_state,lct_casetype,lct_caseno,lct_caseyear,
        //                 IF(ct_code=3,
        //                 (SELECT Name FROM state s WHERE s.id_no = l_dist AND display = 'Y'),
        //                 (SELECT agency_name FROM ref_agency_code c WHERE c.cmis_state_id = l_state AND c.id = l_dist AND is_deleted = 'f') )agency_name,
        //                 IF(ct_code=4,
        //                 (SELECT skey FROM casetype ct WHERE ct.display = 'Y' AND ct.casecode =  lct_casetype),
        //                 (SELECT type_sname FROM lc_hc_casetype d WHERE d.lccasecode = lct_casetype AND d.display = 'Y'))type_sname
        //                 FROM lowerct WHERE $dno AND lw_display='Y' and is_order_challenged='Y' ORDER BY lower_court_id";
        // pr($sql);
        return $result;

    }

    public function set_party_status($dataArr){

        $chk =  $dataArr['fno'];
        // $fil_diary = " diary_no = '$chk'  ";
       
        if($dataArr['add_selector']=='P'){
            // $pet_q = "SELECT MAX(sr_no) FROM party WHERE $fil_diary AND pet_res='$dataArr[val]' AND pflag !='T'";
            // $pet_rs = mysql_query($pet_q) or die(__LINE__.'->'.mysql_error());
            // $no = mysql_result($pet_rs, 0);
            $builder = $this->db->table("party");
            $builder->select("MAX(sr_no)");
            $builder->where('diary_no',$chk);
            $builder->where('pet_res',$dataArr['val']);
            $builder->where('pflag !=','T');
           // pr($builder->getCompiledSelect());
            $query = $builder->get();
            $pet_rs = $query->getRowArray();
            if(!empty($pet_rs)){
                $no = $pet_rs['max'];
                echo ++$no; 
            }
        }
        else if($dataArr['add_selector']=='L' && $dataArr['flag']==''){
            // $pet_q = "SELECT sr_no_show no, array_agg(b.lowercase_id) lowercase_id FROM party a LEFT JOIN party_lowercourt b ON a.auto_generated_id=b.party_id AND b.display='Y' WHERE diary_no = '$chk' AND pet_res='$dataArr[val]' AND pflag !='T' AND sr_no_show LIKE '$dataArr[srnoshow].%'
            // GROUP BY sr_no_show
            // ORDER BY CAST(split_part(sr_no_show,'.',1) AS int) DESC,
            // CAST(split_part(split_part(CONCAT(sr_no_show,'.0'),'.',2),'.',-1) AS int) DESC,
            // CAST(split_part(split_part(CONCAT(sr_no_show,'.0.0'),'.',3),'.',-1) AS int) DESC,
            // CAST(split_part(split_part(CONCAT(sr_no_show,'.0.0.0'),'.',4),'.',-1) AS int) DESC";

            // // if(mysql_num_rows($pet_rs)==0){
            // //     $no['no'] = $dataArr['srnoshow'].'.1';
            // //     echo $no['no'].'~'.$no['lowercase_id'];
            // // }
            // $query = $this->db->query($pet_q);
            // $no= $query->getResultArray();

            // $builder1 = $this->db->table("party a");
            // $builder1->select("sr_no_show as no, array_agg(b.lowercase_id) as lowercase_id");
            // $builder1->join('party_lowercourt b', "a.auto_generated_id = b.party_id AND b.display = 'Y'", 'LEFT');
            // $builder1->where('diary_no', $chk);
            // $builder1->where('pet_res', $dataArr['val']);
            // $builder1->where('pflag !=', 'T');
            // $builder1->like('sr_no_show', $dataArr['srnoshow'] . '.', 'after');
            // $builder1->groupBy('sr_no_show');
            // $builder1->orderBy("CAST(split_part(sr_no_show, '.', 1) AS INTEGER)", 'DESC');
            // $builder1->orderBy("CAST(split_part(split_part(CONCAT(sr_no_show, '.0'), '.', 2), '.', -1) AS INTEGER)", 'DESC');
            // $builder1->orderBy("CAST(split_part(split_part(CONCAT(sr_no_show, '.0.0'), '.', 3), '.', -1) AS INTEGER)", 'DESC');
            // $builder1->orderBy("CAST(split_part(split_part(CONCAT(sr_no_show, '.0.0.0'), '.', 4), '.', -1) AS INTEGER)", 'DESC');

            $query = $this->db->table('party a');
            $query->select('sr_no_show AS no');
            $query->select('array_agg(b.lowercase_id) AS lowercase_id');
            $query->join('party_lowercourt b', "a.auto_generated_id = b.party_id AND b.display = 'Y'", 'LEFT');
            $query->where('diary_no', $chk);
            $query->where('pet_res', $dataArr['val']);
            $query->where('pflag !=', 'T');
            $query->like('sr_no_show', $dataArr['srnoshow'].'.', 'after');
            $query->groupBy('sr_no_show');
            $query->orderBy("CAST(split_part(sr_no_show, '.', 1) AS integer) DESC");
            $query->orderBy("CAST(split_part(split_part(CONCAT(sr_no_show, '.0'), '.', 2), '.', -1) AS integer) DESC" );
            $query->orderBy("CAST(split_part(split_part(CONCAT(sr_no_show, '.0.0'), '.', 3), '.', -1) AS integer) DESC" );
            $query->orderBy("CAST(split_part(split_part(CONCAT(sr_no_show, '.0.0.0'), '.', 4), '.', -1) AS integer) DESC" );
            // $queryString = $query->getCompiledSelect();
            // echo $queryString;
            // exit();
            $query1 = $query->get();
            $no = $query1->getResultArray();

            // echo "<pre>";
            // print_r($no); die;
            $result_rows=$query1->getNumRows();
            if($result_rows == 0) {
                $no['no'] = $dataArr['srnoshow'].'.1';
                // $no['no'] = $dataArr['srnoshow'];
                // echo $no['no'].'~'.$no['lowercase_id'];
                echo $no['no'].'~';
            }else{
                $no = $no[0];
                $tempno = explode('.', $no['no']);
                $remaining = substr($no['no'],0, -1);
                $last = substr($no['no'], -1)+1;
                $no['no'] = $remaining.$last;
                echo $no['no'].'~'.$no['lowercase_id'];
            }

        }
        else if($dataArr['add_selector']=='L' && $dataArr['flag']=='L'){
            // $pet_q = "SELECT sr_no_show no, array_agg(b.lowercase_id) lowercase_id FROM party a LEFT JOIN party_lowercourt b ON a.auto_generated_id=b.party_id AND b.display='Y' WHERE diary_no = '$chk' AND pet_res='$dataArr[val]' AND pflag !='T' AND sr_no_show LIKE '$dataArr[srnoshow].%'
            //     GROUP BY sr_no_show
            //     ORDER BY CAST(split_part(sr_no_show,'.',1) AS int) DESC,
            //     CAST(split_part(split_part(CONCAT(sr_no_show,'.0'),'.',2),'.',-1) AS int) DESC,
            //     CAST(split_part(split_part(CONCAT(sr_no_show,'.0.0'),'.',3),'.',-1) AS int) DESC,
            //     CAST(split_part(split_part(CONCAT(sr_no_show,'.0.0.0'),'.',4),'.',-1) AS int) DESC";

            // $query = $this->db->query($pet_q);
            // $no= $query->getResultArray();

            $builder2 = $this->db->table("party a");
            $builder2->select("sr_no_show as no, array_agg(b.lowercase_id) as lowercase_id");
            $builder2->join('party_lowercourt b', "a.auto_generated_id = b.party_id AND b.display = 'Y'", 'LEFT');
            $builder2->where('diary_no', $chk);
            $builder2->where('pet_res', $dataArr['val']);
            $builder2->where('pflag !=', 'T');
            $builder2->like('sr_no_show', $dataArr['srnoshow'] . '.', 'after');
            $builder2->groupBy('sr_no_show');
            $builder2->orderBy("CAST(split_part(sr_no_show, '.', 1) AS INTEGER)", 'DESC');
            $builder2->orderBy("CAST(split_part(split_part(CONCAT(sr_no_show, '.0'), '.', 2), '.', -1) AS INTEGER)", 'DESC');
            $builder2->orderBy("CAST(split_part(split_part(CONCAT(sr_no_show, '.0.0'), '.', 3), '.', -1) AS INTEGER)", 'DESC');
            $builder2->orderBy("CAST(split_part(split_part(CONCAT(sr_no_show, '.0.0.0'), '.', 4), '.', -1) AS INTEGER)", 'DESC');
            $query2 = $builder2->get();
            $no = $query2->getResultArray();

            if($no['no']==0){
                $no['no']=$dataArr['srnoshow'];
                // $pet_q1 = "SELECT array_agg(b.lowercase_id) lowercase_id FROM party a LEFT JOIN party_lowercourt b ON a.auto_generated_id=b.party_id AND b.display='Y' WHERE $fil_diary AND pet_res='$dataArr[val]' AND pflag !='T' AND sr_no_show = '$dataArr[srnoshow]'";
                // $query1 = $this->db->query($pet_q1);
                // $pet_rs= $query1->getResultArray();
                $builder3 = $this->db->table("party a");
                $builder3->select("array_agg(b.lowercase_id) lowercase_id");
                $builder3->join('party_lowercourt b', "a.auto_generated_id=b.party_id AND b.display='Y' ", 'LEFT');
                $builder3->where('diary_no',$chk);
                $builder3->where('pet_res', $dataArr['val']);
                $builder3->where('pflag !=', 'T');
                $builder3->where('sr_no_show', $dataArr['srnoshow']);
                $query3 = $builder3->get();
                $pet_rs = $query3->getResultArray();

                $no['lowercase_id'] = $pet_rs[0];
            }
            $tempno = explode('.', $no['no']);
            $no['no'] = $tempno[0].'.'.$tempno[1].'.'.($tempno[2]+1);
            echo $no['no'].'~'.$no['lowercase_id'];
        }

    }

    public function savepartyDetails($dataArr){
         
        $update = [];
        $partyname = '';
        if($dataArr['controller']=='I'){
            $dno = $dataArr['fno'];
            if(strpos($dataArr['p_no'], '.')>0){
                $party_no = explode('.', $dataArr['p_no']);
                $party_no = $party_no[0];
            }else{
                $party_no = $dataArr['p_no'];
            }

            if($party_no==0){
                echo "ERROR, Party No. Can not be Zero";
                exit();
            }

            if($dataArr['p_no']==''){
                echo "ERROR, Party No. Can not be Zero";
                exit();
            }

            $builder1 = $this->db->table("party");
            $builder1->select("diary_no");
            $builder1->where('diary_no',$dno);
            $builder1->where('pet_res',$dataArr['p_f']);            
            $query1 = $builder1->get();
            $chk_if_party = $query1->getResultArray();
            if(count($chk_if_party)>0){                                
                
                
                $pa_num = $dataArr['p_no'];
                $pa_num_org = $dataArr['p_no'];
                $party_num_edited = $dataArr['party_num_edited'];
                if($party_num_edited == 0){
                    if ((int) $pa_num == $pa_num) {  //is an integer
                        $pa_num = $pa_num; 
                    }else{  //Float
                        $strLen = strlen($pa_num); 
                        $trLen = $strLen -3;
                        // echo substr($pa_num,0,-$trLen); die; 
                        $str_new = substr($pa_num,0,-$trLen);
                        $str_new = floatval($str_new);
                        $pa_num = ceil($str_new); 
                    }
                    


                    $builder3 = $this->db->table('party');
                    $builder3->set('sr_no_show', "CASE WHEN (position('.' in sr_no_show) != 0 ) and (length(sr_no_show) <= 3 ) THEN cast((sr_no +  (substring(sr_no_show, position('.' in sr_no_show))::numeric + .1) ) as text) when (position('.' in sr_no_show) != 0) and (length(sr_no_show) > 3 and length(sr_no_show) < 5 ) then cast((sr_no + (substring(sr_no_show, position('.' in sr_no_show))::numeric + 2) ) as text) when (position('.' in sr_no_show) != 0) and (length(sr_no_show) = 5 ) then CAST(split_part(sr_no_show, '.', 1)::integer || '.' || (split_part(sr_no_show, '.', 2)::integer + 1)::text ||  '.' || split_part(sr_no_show, '.', 3) AS TEXT) else cast((sr_no + 1) as text) END", false);
                    $builder3->set('sr_no', "case when (position('.' in sr_no_show) != 0 ) then sr_no else sr_no + 1 end", false);
                    $builder3->where('diary_no', $dno);
                    $builder3->where('pet_res', $dataArr['p_f']);
                    $builder3->where('sr_no >=', $pa_num);
                    $builder3->where('sr_no_show >=', $pa_num_org);
                    $builder3->where('pflag !=', 'T');
                    
                    $builder3->update();

                }else{  // If Party Number change to be shihft in between
                    if ((int) $pa_num == $pa_num) {  //is an integer
                        $pa_num = $pa_num; 
    
                        $builder3 = $this->db->table('party');
                        // $builder3->set('sr_no_show', "CONCAT(sr_no + 1, substring(sr_no_show, POSITION('.' IN sr_no_show)))", false);
                        $builder3->set('sr_no_show', "CASE WHEN (position('.' in sr_no_show) != 0 ) THEN cast(CONCAT(sr_no + 1, substring(sr_no_show, position('.' in sr_no_show))) as text) else cast((sr_no + 1) as text)	END", false);
                        $builder3->set('sr_no', 'sr_no + 1', false);
                        $builder3->where('diary_no', $dno);
                        $builder3->where('pet_res', $dataArr['p_f']);
                        $builder3->where('sr_no >=', $pa_num);
                        $builder3->where('pflag !=', 'T');
                        
                        $builder3->update();
    
                    }else{  //Float
                        $strLen = strlen($pa_num); 
                        if($strLen > 3){
                           
                            $trLen = $strLen -1;
                            $str_new = substr($pa_num,0,-$trLen);
                           
                            $pa_num = $str_new;
                        }else{
                            $pa_num = substr($pa_num,0,-2) ;                            
                        } 
    
                       
    
                        $builder3 = $this->db->table('party');
                        $builder3->set('sr_no_show', "CASE WHEN (position('.' in sr_no_show) != 0 ) and (length(sr_no_show) <= 3 ) THEN cast((sr_no +  (substring(sr_no_show, position('.' in sr_no_show))::numeric + .1) ) as text) when (position('.' in sr_no_show) != 0) and (length(sr_no_show) > 3 and length(sr_no_show) < 5 ) then cast((sr_no + (substring(sr_no_show, position('.' in sr_no_show))::numeric + 2) ) as text) when (position('.' in sr_no_show) != 0) and (length(sr_no_show) = 5 ) then CAST(split_part(sr_no_show, '.', 1)::integer || '.' || (split_part(sr_no_show, '.', 2)::integer)::text ||  '.' || (split_part(sr_no_show, '.', 3)::integer  + 1) AS TEXT) else cast((sr_no + 1) as text) END", false);
                        $builder3->set('sr_no', "case when (position('.' in sr_no_show) != 0 ) then sr_no else sr_no + 1 end", false);
                        $builder3->where('diary_no', $dno);
                        $builder3->where('pet_res', $dataArr['p_f']);
                        $builder3->where('sr_no =', $pa_num);
                        $builder3->where('sr_no_show >=', $pa_num_org);
                        $builder3->where('pflag !=', 'T');
                        
                        $builder3->update();
    
                    }
                }

            }/*else{
                return false;
            }  */          

            $partyname = "";                   
            if($dataArr['p_type']!='I'){ 
                if($dataArr['order1']=='S' &&  $dataArr['s_causetitle']=='true' )
                {$partyname .= "$dataArr[p_statename] "; }
                else if($dataArr['order1']=='D' && $dataArr['d_causetitle']=='true'  )
                {$partyname .= "$dataArr[p_deptt] "; }
                else if($dataArr['order1']=='P' && $dataArr['p_causetitle']=='true' )
                {$partyname .= "$dataArr[p_post] "; }

                if($dataArr['order2']=='S' && $dataArr['s_causetitle']=='true' )
                {$partyname .= "$dataArr[p_statename] "; }
                else if($dataArr['order2']=='D'  && $dataArr['d_causetitle']=='true'  )
                {$partyname .= "$dataArr[p_deptt] ";}
                else if($dataArr['order2']=='P' &&  $dataArr['p_causetitle']=='true'  )
                { $partyname .= "$dataArr[p_post] "; }

                if($dataArr['order3']=='S'  &&  $dataArr['s_causetitle']=='true' )
                {  $partyname .= "$dataArr[p_statename] "; }
                else if($dataArr['order3']=='D' &&  $dataArr['d_causetitle']=='true'  )
                {   $partyname .= "$dataArr[p_deptt] ";}
                else if($dataArr['order3']=='P' && $dataArr['p_causetitle']=='true' )
                {   $partyname .= "$dataArr[p_post] ";}


                if($dataArr['s_causetitle']=='false' && $dataArr['d_causetitle']=='false' && $dataArr['p_causetitle']=='false'){
                    if ($dataArr['order1'] == 'S'){
                        $partyname .= "$dataArr[p_statename] ";}
                    else if ($dataArr['order1'] == 'D'){
                        $partyname .= "$dataArr[p_deptt] ";}
                    else if ($dataArr['order1'] == 'P'){
                        $partyname .= "$dataArr[p_post] ";}

                    if ($dataArr['order2'] == 'S'){
                        $partyname .= "$dataArr[p_statename] ";}
                    else if ($dataArr['order2'] == 'D'){
                        $partyname .= "$dataArr[p_deptt] ";}
                    else if ($dataArr['order2'] == 'P'){
                        $partyname .= "$dataArr[p_post] ";}

                    if ($dataArr['order3'] == 'S'){
                        $partyname .= "$dataArr[p_statename] ";}
                    else if ($dataArr['order3'] == 'D'){
                        $partyname .= "$dataArr[p_deptt] ";}
                    else if ($dataArr['order3'] == 'P'){
                        $partyname .= "$dataArr[p_post] ";}
                }
                $partyname = strtoupper(TRIM($partyname)); 
                

                $builder3 = $this->db->table("party_order");
                $builder3->select("*");
                $builder3->where('user',$_SESSION['login']['usercode']);
                $builder3->where('o1',$dataArr['order1']);
                $builder3->where('o2',$dataArr['order2']);            
                $builder3->where('o3',$dataArr['order3']);
                $builder3->where('display','Y');
                $query3 = $builder3->get();
                $chk_order = $query3->getResultArray();

                if(count($chk_order)==0){
                    $builder4 = $this->db->table("party_order");
                    $builder4->set('display', 'N' );
                    $builder4->where('user',$_SESSION['login']['usercode']);
                    $builder4->where('display','Y');
                    $builder4->update(); 

                    $insert_order_data = [
                        'user' => $_SESSION['login']['usercode'],
                        'o1' => $dataArr['order1'], 
                        'o2' => $dataArr['order2'],
                        'o3' => $dataArr['order3'],
                        'ent_dt' => date('Y-m-d H:i:s'),
                    ];
                    $builder5 = $this->db->table("party_order");
                    $builder5->insert($insert_order_data);

                }

                $insert_data = [
                    'pet_res' => $dataArr['p_f'],
                    'sr_no' => $party_no,
                    'sr_no_show' => $dataArr['p_no'],
                    'ind_dep' => $dataArr['p_type'],
                    'partyname' => $partyname,
                    'partysuff' => strtoupper(TRIM($dataArr['p_statename'].' '.$dataArr['p_deptt'])),
                    'addr1' => $dataArr['p_post'],
                    'addr2' => strtoupper(TRIM($dataArr['p_add'])),
                    'dstname' => strtoupper(TRIM($dataArr['p_city'])),
                    'state' => $dataArr['p_st'],
                    'city' => $dataArr['p_dis'],
                    'pin' => (int) $dataArr['p_pin'],
                    'country' => (int) $dataArr['p_cont'],
                    'email' => $dataArr['p_email'],
                    'contact' => (int) $dataArr['p_mob'],
                    'usercode' => (int) $_SESSION['login']['usercode'],
                    'ent_dt' => 'NOW()',
                    'authcode' => (int) $dataArr['p_code'],
                    'deptcode' => (int) $dataArr['d_code'],
                    'diary_no' => (int) $dno,
                    'state_in_name' => $dataArr['p_statename_hd'],
                    'remark_lrs' => $dataArr['remark_lrs'],
                    'lowercase_id' => (is_array($dataArr['lowercase']) ? $dataArr['lowercase'][0] : $dataArr['lowercase']),
                    'cont_pro_info' => $dataArr['cont_pro_info']
                ];
                // echo "<pre>"; print_r($insert_data); die;
                $builder7 = $this->db->table("party");
                $builder7->insert($insert_data);
            }

            if($dataArr['p_type']=='I'){
               
                $insert_data = [
                    'pet_res' => $dataArr['p_f'],
                    'sr_no' => $party_no,
                    'sr_no_show' => $dataArr['p_no'],
                    'ind_dep' => $dataArr['p_type'],
                    'partyname' => strtoupper(TRIM($dataArr['p_name'])),
                    'partysuff' => strtoupper(TRIM($dataArr['p_name'])),
                    'sonof' => $dataArr['p_rel'],
                    'prfhname' => strtoupper(TRIM($dataArr['p_rel_name'])),
                    'age' => (int) $dataArr['p_age'],
                    'sex' => $dataArr['p_sex'],
                    'caste' => strtoupper(TRIM($dataArr['p_caste'])),
                    'addr1' => strtoupper(TRIM($dataArr['p_occ'])),
                    'education' => strtoupper(TRIM($dataArr['p_edu'])),
                    'addr2' => strtoupper(TRIM($dataArr['p_add'])),
                    'dstname' => strtoupper(TRIM($dataArr['p_city'])),
                    'state' => $dataArr['p_st'],
                    'city' => $dataArr['p_dis'],
                    'pin' => (int) $dataArr['p_pin'],
                    'country' => (int)$dataArr['p_cont'],
                    'email' => $dataArr['p_email'],
                    'contact' => (int) $dataArr['p_mob'],
                    'usercode' => (int) $_SESSION['login']['usercode'],
                    'ent_dt' => 'NOW()',
                    'diary_no' => (int) $dno,
                    'occ_code' => (int) $dataArr['p_occ'],
                    'edu_code' => (int) $dataArr['p_edu'],
                    'remark_lrs' => $dataArr['remark_lrs'],
                    'lowercase_id' => (is_array($dataArr['lowercase']) ? $dataArr['lowercase'][0] : $dataArr['lowercase']),
                    'cont_pro_info' => $dataArr['cont_pro_info']                     
                ];
                // echo "<pre>";
                // print_r($insert_data); die;

                $builder6 = $this->db->table("party");
                $builder6->insert($insert_data);

            }
            // else if($dataArr['p_type']!='I'){ 
            // }
            
            if(!isset($dataArr['p_name'])){
                $dataArr['p_name'] = ' ';
                $dataArr['p_rel_name'] = ' ';
            }


            $ucode =  $_SESSION['login']['usercode'];
            $ip_address= $_SERVER['REMOTE_ADDR'];

            $data_auto = [
                'party' => strtoupper(TRIM($dataArr['p_name'])),
                'fh' => strtoupper(TRIM($dataArr['p_rel_name'])),
                'dst' => strtoupper(TRIM($dataArr['p_city'])),
                'addr' => strtoupper(TRIM($dataArr['p_add'])),
                'create_modify' => date('Y-m-d H:i:s'),
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by' => (int) $ucode,
                'updated_by_ip' => $ip_address,
            ];
            $builder8 = $this->db->table("party_autocomp");
            if($builder8->insert($data_auto)) {
                // return true;
                echo json_encode(["message"=>"Party Added Successfully"]);
            }else{
                echo json_encode(["message"=>"Error while Modifying"]);
            }

            if((!empty($dataArr['lowercase']) && $dataArr['lowercase']!='' && $dataArr['lowercase']!= NULL)){
                $builder9 = $this->db->table("party");
                $builder9->select("auto_generated_id");
                $builder9->where('diary_no',$dno);
                $builder9->where('pet_res',$dataArr['p_f']);            
                $builder9->where('sr_no_show',$dataArr['p_no']);
                $builder9->where('pflag','P');
                $query9 = $builder9->get();
                $sel_u_no = $query9->getRow();
                $sel_u_no1 = '';
                if(!empty($sel_u_no)){
                    $sel_u_no1 = $sel_u_no->auto_generated_id;
                }

                if($dataArr['add_add']!=''){
                    $dataArr['add_add'] = ltrim($dataArr['add_add'], '^');
                    $add_add = explode('^',$dataArr['add_add']);

                    for($i=0;$i<sizeof($add_add);$i++){
                        $addresses = explode("~", $add_add[$i]);
                        $ins_data = [
                            'party_id' => $sel_u_no1,
                            'country' => $addresses[1],
                            'state' => $addresses[2],
                            'district' => $addresses[3],
                            'address' => $addresses[0]
                        ];
                        $builder10 = $this->db->table("party_additional_address");
                        $builder10->insert($ins_data);
                    }
                }

                if($dataArr['lowercase']!='' && $dataArr['lowercase']!= NULL){
                    $lowercourts = (array) $dataArr['lowercase'];
                    for($i=0;$i<sizeof($lowercourts);$i++){
                        if($lowercourts[$i]=='')
                            continue;

                        $ins_data = [
                            'party_id' => (int) $sel_u_no1,
                            'lowercase_id' => (int) $lowercourts[$i]
                        ];
                        $builder11 = $this->db->table("party_lowercourt");
                        $builder11->insert($ins_data);

                    }
                }
            }

        }

        else if($dataArr['controller']=='U'){
            $userCode = $_SESSION['login']['usercode'];
            $dno = $dataArr['fno'];
            if($dataArr['p_sta'] == 'O' || $dataArr['p_sta'] == 'D'  || $dataArr['p_sta'] == 'T') {


                // Check for Single Petitioner / Respondant  - If size P1 & R1                
                $dairy_no = $dno;
                $builder = $this->db->table("party");
                $builder->select("*");
                $builder->where('pet_res !=','');
                $builder->where('diary_no', $dairy_no);
                $builder->where('pet_res',$dataArr['p_f']);
                $query = $builder->get();
                $result = $query->getResultArray();
                $pcount = 0;
                $rcount = 0;
                $ct = 0;
                foreach ($result as $vae) {
                    $ct ++;
                    // if($vae['pet_res'] == 'P'){ $pcount++; }
                    // if($vae['pet_res'] == 'R'){ $rcount++; }
                }
                // echo $ct; die;
                if($ct == 1 ){
                    echo json_encode(["message"=>"Please add more parties before deleting this party"]);
                    exit();
                }
                else{
                    // $no_of_party = "select * from party where diary_no = '$dno' and pflag='P' and '$dataArr[p_f]' not in('I','N') and pet_res='$dataArr[p_f]'";
                    // $query = $this->db->query($no_of_party);
                    if(!in_array($dataArr['p_f'], ['I', 'N'])){
                        $builder4 = $this->db->table("party");
                        $builder4->select("*");
                        $builder4->where('diary_no', $dno);
                        $builder4->where('pflag', 'P');
                        // $builder4->whereNotIn($dataArr['p_f'], ['I', 'N']);
                        $builder4->where('pet_res', $dataArr['p_f']);
                        $query4 = $builder4->get();
                        $no_of_party= $query4->getNumRows();
                        if ($no_of_party ==1) {
                            echo "1";
                            exit();
                        }
                    }
                }
                
            }
            
            // $srl_no = "SELECT sr_no FROM party WHERE diary_no = '$dno' AND pet_res='$dataArr[hd_p_f]' and pflag='P' order by sr_no,sr_no_show limit 1";
            // $query1 = $this->db->query($srl_no);
            $builder5 = $this->db->table("party");
            $builder5->select("*");
            $builder5->where('diary_no', $dno);
            $builder5->where('pet_res', $dataArr['hd_p_f']);
            $builder5->where('pflag', 'P');
            $builder5->orderBy('sr_no', 'sr_no_show');
            $builder5->limit(1);
            $query5 = $builder5->get();

            $srl_no= $query5->getResultArray();
            // $srl_no = mysql_query($srl_no) or die(__LINE__.'->'.mysql_error());
            // $srl_no = mysql_fetch_array($srl_no);
            // echo "<pre>"; print_r($dataArr['hd_p_f']); echo "<br>"; print_r($dataArr['p_f']); die;
            $srl_no=$srl_no[0]['sr_no'];

            if($dataArr['hd_p_f']==$dataArr['p_f']){
                // $set_remark_delete = '';

                //if($dataArr['p_sta'] == 'O' || $dataArr['p_sta'] == 'D')
                    // $set_remark_delete = " ,remark_del=if(remark_del='' or remark_del is null,'$dataArr[remark_del]',concat(remark_del,concat(';','$dataArr[remark_del]')) )";
                    
                   
                    
                    
                if($dataArr['p_type']!='I'){

                    $builder4 = $this->db->table("party");
                    $builder4->select("remark_del");
                    $builder4->where('diary_no', $dno);
                    $builder4->where('pet_res', $dataArr['hd_p_f']);
                    $builder4->where('sr_no_show', $dataArr['p_no']);
                    $builder4->where('pflag', 'P');
                    $builder4->orderBy('sr_no', 'sr_no_show');
                    $builder4->limit(1);
                    $query4 = $builder4->get();
                    $remarkdel= $query4->getResultArray();
                    if(!empty($remarkdel)){
                        $remarkdel = $remarkdel[0]['remark_del'];
                    }

                    if($dataArr['order1']=='S' &&  $dataArr['s_causetitle']=='true' )
                    {$partyname .= "$dataArr[p_statename] ";}
                    else if($dataArr['order1']=='D' && $dataArr['d_causetitle']=='true'  )
                    {$partyname .= "$dataArr[p_deptt] "; }
                    else if($dataArr['order1']=='P' && $dataArr['p_causetitle']=='true' )
                    {$partyname .= "$dataArr[p_post] "; }

                    if($dataArr['order2']=='S' && $dataArr['s_causetitle']=='true' )
                    {$partyname .= "$dataArr[p_statename] "; }
                    else if($dataArr['order2']=='D'  && $dataArr['d_causetitle']=='true'  )
                    {$partyname .= "$dataArr[p_deptt] "; }
                    else if($dataArr['order2']=='P' &&  $dataArr['p_causetitle']=='true'  )
                    { $partyname .= "$dataArr[p_post] "; }

                    if($dataArr['order3']=='S'  &&  $dataArr['s_causetitle']=='true' )
                    {  $partyname .= "$dataArr[p_statename] "; }
                    else if($dataArr['order3']=='D' &&  $dataArr['d_causetitle']=='true'  )
                    {   $partyname .= "$dataArr[p_deptt] "; }
                    else if($dataArr['order3']=='P' && $dataArr['p_causetitle']=='true' )
                    {   $partyname .= "$dataArr[p_post] "; }


                    if($dataArr['s_causetitle']=='false' && $dataArr['d_causetitle']=='false' && $dataArr['p_causetitle']=='false' ){       
                        $partyname =$dataArr['p_statename'];
                    }
                    if( $dataArr['p_type']=='D3' && $dataArr['d_causetitle']=='false' && $dataArr['p_causetitle']=='false' ) {
                        $partyname = $dataArr['p_deptt'];
                    }
                    $partyname = strtoupper(TRIM($partyname));


                    //$update = "UPDATE party SET pet_res='$dataArr[p_f]', ind_dep='$dataArr[p_type]',partyname=$partyname,partysuff=UPPER(TRIM('$dataArr[p_statename]" . ' ' . "$dataArr[p_deptt]')), addr1=upper(trim('$dataArr[p_post]')),addr2=UPPER(TRIM('$dataArr[p_add]')),dstname=UPPER(TRIM('$dataArr[p_city]')),state='$dataArr[p_st]',city='$dataArr[p_dis]',pin='$dataArr[p_pin]',country='$dataArr[p_cont]',lowercase_id='$dataArr[lowercase]',  email='$dataArr[p_email]',contact='$dataArr[p_mob]',usercode='$_SESSION[dcmis_user_idd]',ent_dt=NOW(),authcode='$dataArr[p_code]',deptcode='$dataArr[d_code]',pflag='$dataArr[p_sta]',state_in_name='$dataArr[p_statename_hd]', remark_lrs='$dataArr[remark_lrs]',cont_pro_info='$dataArr[cont_pro_info]',last_usercode='$_SESSION[dcmis_user_idd]',last_dt=NOW() $set_remark_delete where diary_no = '$dno' AND pet_res='$dataArr[hd_p_f]' AND sr_no_show='$dataArr[p_no]' AND pflag='P'";
                    $updateData = [
                        'pet_res' => $dataArr['p_f'],
                        'ind_dep' => $dataArr['p_type'],
                        'partyname' => $partyname,
                        'partysuff' => strtoupper(TRIM($dataArr['p_statename'].' '.$dataArr['p_deptt'])),
                        'addr1' => strtoupper(trim($dataArr['p_post'])),
                        'addr2' => strtoupper(TRIM($dataArr['p_add'])),
                        'dstname' => strtoupper(TRIM($dataArr['p_city'])),
                        'state' => $dataArr['p_st'],
                        'city' => $dataArr['p_dis'],
                        'pin' => (int)$dataArr['p_pin'],
                        'country' => (int)$dataArr['p_cont'],
                        'lowercase_id' => (!empty($dataArr['lowercase']) && is_array($dataArr['lowercase']) ? $dataArr['lowercase'][0] : 0),
                        'email' => $dataArr['p_email'],
                        'contact' => $dataArr['p_mob'] ?? '',
                        // 'usercode' => (int)$userCode,
                        // 'ent_dt' => 'NOW()',
                        'authcode' => (!empty($dataArr['p_code'])) ? $dataArr['p_code'] : 0,
                        'deptcode' => (!empty($dataArr['d_code'])) ?  $dataArr['d_code'] : 0,
                        'pflag' => $dataArr['p_sta'],
                        'state_in_name' => $dataArr['p_statename_hd'],
                        'remark_lrs' => $dataArr['remark_lrs'],
                        'cont_pro_info' => $dataArr['cont_pro_info'],
                        'last_usercode' => (int)$userCode,
                        'last_dt' => 'NOW()',
                        'remark_del' => ($remarkdel == '' || $remarkdel == null ? $dataArr['remark_del'] : $remarkdel.';'.$dataArr["remark_del"])
                    ];

                   // echo $dataArr['p_type'];
                   // pr($updateData); 

                    $builder = $this->db->table("party");
                    $builder->where('diary_no', $dno);
                    $builder->where('pet_res', $dataArr['hd_p_f']);
                    $builder->where('sr_no_show', $dataArr['p_no']);
                    // $builder->where('pflag','P');
                    $builder->where('auto_generated_id', $dataArr['auto_generated_id']);
                    
                    $update = $builder->update($updateData);
                    //pr($this->db->getLastQuery());
                   
                }

                // $chk_order = "SELECT * FROM party_order WHERE USER='$userCode' AND o1='$dataArr[order1]' AND o2='$dataArr[order2]' AND o3='$dataArr[order3]' AND display='Y'"; 
                // $query2 = $this->db->query($chk_order);
                $builder6 = $this->db->table("party_order");
                $builder6->select("*");
                $builder6->where('user', $userCode);
                $builder6->where('o1', $dataArr['order1']);
                $builder6->where('o2', $dataArr['order2']);
                $builder6->where('o3', $dataArr['order3']);
                $builder6->where('display', 'Y');
                $query6 = $builder6->get();
                $chk_order= $query6->getNumRows();
                // $chk_order = mysql_query($chk_order) or die(__LINE__.'->'.mysql_error());
                if($chk_order==0){
                    // $update_order = "UPDATE party_order SET display='N' WHERE user=$userCode AND display='Y'";
                    // mysql_query($update_order) or die(__LINE__.'->'.mysql_error());
                    $builder2 = $this->db->table("party_order");
                    $builder2->set('display', 'N' );
                    $builder2->where('user',$userCode);
                    $builder2->where('display','Y');
                    $builder2->update(); 

                    // $insert_order="INSERT INTO party_order(user,o1,o2,o3,ent_dt) VALUES($userCode,'$dataArr[order1]','$dataArr[order2]','$dataArr[order3]',NOW())";
                    // mysql_query($insert_order) or die(__LINE__.'->'.mysql_error());
                    $insert_order_data = [
                        'user' => $userCode,
                        'o1' => $dataArr['order1'], 
                        'o2' => $dataArr['order2'],
                        'o3' => $dataArr['order3'],
                        'ent_dt' => 'NOW()'
                    ];
                    $builder5 = $this->db->table("party_order");
                    $builder5->insert($insert_order_data);
                }


                if($dataArr['p_type']=='I') {
                    // $rem_del = "SELECT remark_del FROM party WHERE diary_no = '$dno' AND pet_res='$dataArr[hd_p_f]' AND sr_no_show='$dataArr[p_no]' AND pflag='P' order by sr_no,sr_no_show limit 1";
                    // $query4 = $this->db->query($rem_del);
                    $builder4 = $this->db->table("party");
                    $builder4->select("remark_del");
                    $builder4->where('diary_no', $dno);
                    $builder4->where('pet_res', $dataArr['hd_p_f']);
                    $builder4->where('sr_no_show', $dataArr['p_no']);
                    $builder4->where('pflag', 'P');
                    $builder4->orderBy('sr_no', 'sr_no_show');
                    $builder4->limit(1);
                    $query4 = $builder4->get();
                    $remarkdel= $query4->getResultArray();
                    if(!empty($remarkdel)){
                        $remarkdel = $remarkdel[0]['remark_del'];
                    }
                   
                    // echo "<pre>"; print_r($updateData); die;
                   
                    $builder = $this->db->table("party");                    
                    $builder->set('pet_res', $dataArr['p_f']);
                    $builder->set('ind_dep', $dataArr['p_type']);
                    $builder->set('partyname', strtoupper(TRIM($dataArr['p_name'])));
                    $builder->set('partysuff', strtoupper(TRIM($dataArr['p_name'])));
                    $builder->set('sonof', $dataArr['p_rel']);
                    $builder->set('prfhname', strtoupper(TRIM($dataArr['p_rel_name'])));
                    $builder->set('age', (int) $dataArr['p_age']);
                    $builder->set('sex', $dataArr['p_sex']);
                    $builder->set('caste', strtoupper(TRIM($dataArr['p_caste'])));
                    $builder->set('addr1', strtoupper(TRIM($dataArr['p_occ'])));
                    $builder->set('education', strtoupper(TRIM($dataArr['p_edu'])));
                    $builder->set('addr2', strtoupper(TRIM($dataArr['p_add'])));
                    $builder->set('dstname', strtoupper(TRIM($dataArr['p_city'])));
                    $builder->set('state', $dataArr['p_st']);
                    $builder->set('city', $dataArr['p_dis']);
                    $builder->set('pin', (int)$dataArr['p_pin']);
                    $builder->set('country', (int)$dataArr['p_cont']);
                    $builder->set('lowercase_id', (isset($dataArr['lowercase']) && is_array($dataArr['lowercase'])) ? $dataArr['lowercase'][0] : 0);
                    $builder->set('email', $dataArr['p_email']);
                    $builder->set('contact', (int)$dataArr['p_mob']);
                    // $builder->set('usercode', (int)$userCode);
                    // $builder->set('ent_dt', 'NOW()');
                    $builder->set('pflag', $dataArr['p_sta']);
                    $builder->set('occ_code', (int)$dataArr['p_occ']);
                    $builder->set('edu_code', (int)$dataArr['p_edu']);
                    $builder->set('remark_lrs', $dataArr['remark_lrs']);
                    $builder->set('cont_pro_info', $dataArr['cont_pro_info']);
                    $builder->set('last_usercode', (int)$userCode);
                    $builder->set('last_dt', 'NOW()');
                    $builder->set('remark_del', ($remarkdel == '' || $remarkdel == null ? $dataArr['remark_del'] : $remarkdel.';'.$dataArr["remark_del"]));
                    $builder->where('diary_no', $dno);
                    $builder->where('pet_res', $dataArr['hd_p_f']);
                    $builder->where('sr_no_show', $dataArr['p_no']);
                    $builder->where('auto_generated_id', $dataArr['auto_generated_id']);
                    // // $builder->where('pflag','P');
                    // $query= $builder->getCompiledUpdate();
                    // echo (string) $query; exit();
                    $update = $builder->update();

                    //$update = "UPDATE party SET pet_res='$dataArr[p_f]',ind_dep='$dataArr[p_type]',partyname=UPPER(TRIM('$dataArr[p_name]')),                    partysuff=UPPER(TRIM('$dataArr[p_name]')),sonof='$dataArr[p_rel]',prfhname=UPPER(TRIM('$dataArr[p_rel_name]')),age='$dataArr[p_age]', sex='$dataArr[p_sex]',caste=UPPER(TRIM('$dataArr[p_caste]')),addr1=UPPER(TRIM('$dataArr[p_occ]')),education=UPPER(TRIM('$dataArr[p_edu]')),addr2=UPPER(TRIM('$dataArr[p_add]')), dstname=UPPER(TRIM('$dataArr[p_city]')),state='$dataArr[p_st]',city='$dataArr[p_dis]',pin='$dataArr[p_pin]',country='$dataArr[p_cont]',lowercase_id='$dataArr[lowercase]', email='$dataArr[p_email]',contact='$dataArr[p_mob]',usercode='$_SESSION[dcmis_user_idd]',ent_dt=NOW(),pflag='$dataArr[p_sta]',occ_code='$dataArr[p_occ_code]',edu_code='$dataArr[p_edu_code]', remark_lrs='$dataArr[remark_lrs]',cont_pro_info='$dataArr[cont_pro_info]',last_usercode='$_SESSION[dcmis_user_idd]',last_dt=NOW() $set_remark_delete WHERE diary_no = '$dno' AND pet_res='$dataArr[hd_p_f]' AND sr_no_show='$dataArr[p_no]' AND pflag='P'";

                }
                // else if($dataArr['p_type']!='I') {
                // }
                if($dataArr['p_sta']!='P'){
                    if(strpos($dataArr['p_no'], '.')>0){
                        $party_no = explode('.', $dataArr['p_no']);
                        $party_no = $party_no[0];
                    }
                    else{
                        $party_no = $dataArr['p_no'];
                    }

                    // $c_a_r = "SELECT adv FROM advocate WHERE diary_no = '$dno' AND pet_res='$dataArr[p_f]' AND pet_res_no='$party_no' AND display='Y'";
                    // $query6 = $this->db->query($c_a_r);
                    $builder14 = $this->db->table("advocate");
                    $builder14->select("adv");
                    $builder14->where('diary_no', $dno);
                    $builder14->where('pet_res', $dataArr['p_f']);
                    $builder14->where('pet_res_no', $party_no);
                    $builder14->where('display', 'Y');
                    $query14 = $builder14->get();
                    $c_a_r= $query14->getNumRows();  
                    // $c_a_r = mysql_query($c_a_r) or die(__LINE__.'->'.mysql_error()); //or die(mysql_error());
                    if($c_a_r > 0){
                        // $update_adv = "UPDATE advocate SET display='N',usercode='$userCode',ent_dt=NOW() WHERE diary_no = '$dno' AND pet_res='$dataArr[p_f]' AND pet_res_no='$party_no' AND display='Y'";
                        // mysql_query($update_adv) or die(__LINE__.'->'.mysql_error());
                        $builder3 = $this->db->table("advocate");
                        $builder3->set('display', 'N' );
                        $builder3->set('usercode', $userCode);
                        $builder3->set('ent_dt', 'NOW()' );
                        $builder3->where('diary_no', $dno);
                        $builder3->where('pet_res', $dataArr['p_f']);
                        $builder3->where('pet_res_no', $party_no);
                        $builder3->where('display','Y');
                        $builder3->update(); 

                    }
                }

                // $insert_auto = "INSERT INTO party_autocomp(party,fh,dst,addr) VALUES(UPPER(TRIM('$dataArr[p_name]')),UPPER(TRIM('$dataArr[p_rel_name]')),UPPER(TRIM('$dataArr[p_city]')),UPPER(TRIM('$dataArr[p_add]')))";
                // mysql_query($insert_auto) or die(__LINE__.'->'.mysql_error());

                if(!isset($dataArr['p_name'])){
                    $dataArr['p_name'] = ' ';
                    $dataArr['p_rel_name'] = ' ';
                }

                $ucode =  $_SESSION['login']['usercode'];
                $ip_address= $_SERVER['REMOTE_ADDR'];    

                $data_auto = [
                    'party' => strtoupper(TRIM($dataArr['p_name'])),
                    'fh' => strtoupper(TRIM($dataArr['p_rel_name'])),
                    'dst' => strtoupper(TRIM($dataArr['p_city'])),
                    'addr' => strtoupper(TRIM($dataArr['p_add'])),
                    'create_modify' => date('Y-m-d H:i:s'),
                    'updated_on' => date('Y-m-d H:i:s'),
                    'updated_by' => (int) $ucode,
                    'updated_by_ip' => $ip_address,
                ];
                $builder8 = $this->db->table("party_autocomp");
                $builder8->insert($data_auto);

                if($dataArr['add_add']!=''){
                    //echo "inside add_add";
                    // $sel_u_no = "SELECT auto_generated_id FROM party WHERE diary_no='$dno' AND pet_res='$dataArr[p_f]' AND sr_no_show='$dataArr[p_no]' AND pflag='P'";
                    // $query7 = $this->db->query($sel_u_no);
                    $builder15 = $this->db->table("party");
                    $builder15->select("auto_generated_id");
                    $builder15->where('diary_no', $dno);
                    $builder15->where('pet_res', $dataArr['p_f']);
                    $builder15->where('sr_no_show', $dataArr['p_no']);
                    $builder15->where('pflag', 'P');
                    $query15 = $builder15->get();

                    $sel_u_no= $query15->getResultArray();
                    $sel_u_no = $sel_u_no[0]['auto_generated_id'];

                    $dataArr['add_add'] = ltrim($dataArr['add_add'], '^');
                    $add_add = explode('^',$dataArr['add_add']);
                    // $update_add_aadd = "UPDATE party_additional_address SET display='N' WHERE party_id=$sel_u_no";
                    // mysql_query($update_add_aadd) or die(__LINE__.'->'.mysql_error());
                    $builder5 = $this->db->table("party_additional_address");
                    $builder5->set('display', 'N' );
                    $builder5->where('party_id', $sel_u_no);
                    $update_add_aadd = $builder5->update(); 

                    for($i=0;$i<sizeof($add_add);$i++){
                        $addresses = explode("~", $add_add[$i]);
                        // $insert = "INSERT INTO party_additional_address(party_id,country,state,district,address,display) VALUES($sel_u_no,'$addresses[1]','$addresses[2]','$addresses[3]','$addresses[0]','Y')";
                        // mysql_query($insert) or die(__LINE__.'->'.mysql_error());
                        $insertData = [
                            'party_id' => $sel_u_no,
                            'country' => $addresses[1],
                            'state' => $addresses[2],
                            'district' => $addresses[3],
                            'address' => $addresses[0],
                            'display' => 'Y'
                        ];
                        $builder9 = $this->db->table("party_additional_address");
                        $builder9->insert($insertData);

                    }
                }
                else if($dataArr['add_add']==''){
                    //echo "inside add_add deny";
                    // $sel_u_no = "SELECT auto_generated_id FROM party WHERE diary_no='$dno' AND pet_res='$dataArr[p_f]' AND sr_no_show='$dataArr[p_no]' AND pflag='P'";
                    // $query8 = $this->db->query($sel_u_no);
                    $builder16 = $this->db->table("party");
                    $builder16->select("auto_generated_id");
                    $builder16->where('diary_no', $dno);
                    $builder16->where('pet_res', $dataArr['p_f']);
                    $builder16->where('sr_no_show', $dataArr['p_no']);
                    $builder16->where('pflag', 'P');
                    $query16 = $builder16->get();

                    $sel_u_no= $query16->getResultArray();
                    if(!empty($sel_u_no)){
                        $sel_u_no = $sel_u_no[0]['auto_generated_id'];
                        // $update_add_aadd = "UPDATE party_additional_address SET display='N' WHERE party_id=$sel_u_no";
                        // mysql_query($update_add_aadd) or die(__LINE__.'->'.mysql_error());
                        $builder5 = $this->db->table("party_additional_address");
                        $builder5->set('display', 'N' );
                        $builder5->where('party_id', $sel_u_no);
                        $update_add_aadd =$builder5->update(); 
                    }
                    
                }

                if(isset($dataArr['lowercase']) && $dataArr['lowercase']!='' && $dataArr['lowercase']!= NULL){
                    // $sel_u_no = "SELECT auto_generated_id FROM party WHERE diary_no='$dno' AND pet_res='$dataArr[p_f]' AND sr_no_show='$dataArr[p_no]' AND pflag='P'";
                    // $query9 = $this->db->query($sel_u_no);
                    $builder17 = $this->db->table("party");
                    $builder17->select("auto_generated_id");
                    $builder17->where('diary_no', $dno);
                    $builder17->where('pet_res', $dataArr['p_f']);
                    $builder17->where('sr_no_show', $dataArr['p_no']);
                    $builder17->where('pflag', 'P');
                    $query17 = $builder17->get();

                    $sel_u_no= $query17->getResultArray();
                    if(!empty($sel_u_no)){
                        $sel_u_no = $sel_u_no[0]['auto_generated_id'];

                        // $update_add_aadd = "UPDATE party_lowercourt SET display='N' WHERE party_id=$sel_u_no";
                        // mysql_query($update_add_aadd) or die(__LINE__.'->'.mysql_error());
                        $builder5 = $this->db->table("party_lowercourt");
                        $builder5->set('display', 'N' );
                        $builder5->where('party_id', $sel_u_no);
                        $update_add_aadd = $builder5->update(); 
 

                        // $lowercourts = count($dataArr['lowercase']);
                        // for($i=0;$i < $lowercourts;$i++){

                        $lowercourts = (array) $dataArr['lowercase'];                        
                        for($i=0;$i<sizeof($lowercourts);$i++){
 
                            if($lowercourts[$i]=='')
                                continue;

                            // $query_lower = "INSERT INTO party_lowercourt(party_id,lowercase_id) VALUES($sel_u_no,$lowercourts[$i])";
                            // mysql_query($query_lower) or die(__LINE__.'->'.mysql_error());
                            $insertData = [
                                'party_id' => $sel_u_no,
                                'lowercase_id' => $lowercourts[$i]
                            ];
                            $builder9 = $this->db->table("party_lowercourt");
                            $builder9->insert($insertData);

                        }
                    }
                    



                }
                else if(isset($dataArr['lowercase']) && ($dataArr['lowercase']=='' || $dataArr['lowercase']== NULL)){
                    // $sel_u_no = "SELECT auto_generated_id FROM party WHERE diary_no='$dno' AND pet_res='$dataArr[p_f]' AND sr_no_show='$dataArr[p_no]' AND pflag='P'";
                    // $query9 = $this->db->query($sel_u_no);
                    $builder17 = $this->db->table("party");
                    $builder17->select("auto_generated_id");
                    $builder17->where('diary_no', $dno);
                    $builder17->where('pet_res', $dataArr['p_f']);
                    $builder17->where('sr_no_show', $dataArr['p_no']);
                    $builder17->where('pflag', 'P');
                    $query17 = $builder17->get();

                    $sel_u_no= $query17->getResultArray();
                    if(!empty($sel_u_no)){
                        $sel_u_no = $sel_u_no[0]['auto_generated_id'];

                        // $update_add_aadd = "UPDATE party_lowercourt SET display='N' WHERE party_id=$sel_u_no";
                        // mysql_query($update_add_aadd) or die(__LINE__.'->'.mysql_error());
                        $builder5 = $this->db->table("party_lowercourt");
                        $builder5->set('display', 'N' );
                        $builder5->where('party_id', $sel_u_no);
                        $update_add_aadd = $builder5->update();
                    }
                     
                }

            
            } else{
                // $update = "UPDATE party SET pflag='T',last_usercode='$userCode',last_dt=NOW() WHERE diary_no = '$dno' AND pet_res='$dataArr[hd_p_f]' AND sr_no_show='$dataArr[p_no]'";

                $builder5 = $this->db->table("party");
                $builder5->set('pflag', 'N' );
                //$builder5->set('last_usercode', 'N' );
                //$builder5->set('last_dt', 'N' );
                $builder5->set('last_usercode', (int)$userCode);
                $builder5->set('last_dt', 'NOW()');
                //$builder5->where('party_id', $sel_u_no);
                $builder5->where('diary_no', $dno);
                $builder5->where('pet_res', $dataArr['hd_p_f']);
                $builder5->where('sr_no_show', $dataArr['p_no']);
                $update = $builder5->update(); 


                // $sql_no = "SELECT MAX(sr_no) no FROM party WHERE diary_no = '$dno' AND pet_res='$dataArr[p_f]' ";
                // $query9 = $this->db->query($sql_no);
                $builder17 = $this->db->table("party");
                $builder17->select("MAX(sr_no) no");
                $builder17->where('diary_no', $dno);
                $builder17->where('pet_res', $dataArr['p_f']);
                $query17 = $builder17->get();
                $sql_no= $query17->getResultArray();
                $p_no = $sql_no[0]['no']+1;
            
            }
        
            if($dataArr['hd_p_f']=='P' &&  $dataArr['p_no']==$srl_no  ) {
                if($dataArr['p_type']=='I'){
                    $name= strtoupper(TRIM($dataArr['p_name']));
                }
                if($dataArr['p_type']!='I'){
                    $name=$partyname;
                    // $causetitle = "update main set pet_name=$name where diary_no= '$dno' and c_status='P' ";
                    $builder5 = $this->db->table("main");
                    $builder5->set('pet_name', $name);
                    $builder5->where('diary_no', $dno);
                    $builder5->where('c_status', 'P');
                    $causetitle = $builder5->update();
                }
                // if(!mysql_query($causetitle))
                //     die(__LINE__.'->'.mysql_error());
            }
            if($dataArr['hd_p_f']=='R' &&  $dataArr['p_no']==$srl_no ) {
                if($dataArr['p_type']=='I'){
                    $name= strtoupper(TRIM($dataArr['p_name']));
                }
                if($dataArr['p_type']!='I'){
                    $name=$partyname;
                    // $causetitle = "update main set res_name=$name where diary_no= '$dno' and c_status='P' ";
                    $builder6 = $this->db->table("main");
                    $builder6->set('res_name', $name);
                    $builder6->where('diary_no', $dno);
                    $builder6->where('c_status', 'P');
                    $causetitle = $builder6->update();
                }
                // if(!mysql_query($causetitle))
                //     die(__LINE__.'->'.mysql_error());
            }

            if($dataArr['p_sta']!='P' &&  $dataArr['p_no']==$srl_no ){
                    // $chk_main = "SELECT partyname FROM party WHERE diary_no='$dno' AND pet_res='$dataArr[p_f]' AND pflag='P' ORDER BY sr_no,CAST(sr_no_show AS int)";
                    // $query2 = $this->db->query($chk_main);
                    $builder11 = $this->db->table("party");
                    $builder11->select("partyname");
                    $builder11->where('diary_no', $dno);
                    $builder11->where('pet_res', $dataArr['p_f']);
                    $builder11->where('pflag', 'P');
                    $builder11->orderBy('sr_no', 'CAST(sr_no_show AS int)');                    
                    $query11 = $builder11->get();
                    $chk_main= $query11->getNumRows();

                    if(($chk_main)>0){
                        $chk_main = $query11->getResultArray();
                        $chk_main = $chk_main[0]['partyname'];
                        if($dataArr['p_f']=='P'){
                            // $update_main = "UPDATE main SET pet_name='$chk_main',last_usercode=$userCode,last_dt=NOW() WHERE diary_no='$dno'";
                            // mysql_query($update_main) or die(__LINE__.'->'.mysql_error());
                            $builder6 = $this->db->table("main");
                            $builder6->set('pet_name', $chk_main);
                            $builder6->set('last_usercode', $userCode);
                            $builder6->set('last_dt', 'NOW()');
                            $builder6->where('diary_no', $dno);
                            $update_main = $builder6->update();
                        }
                        else if($dataArr['p_f']=='R'){
                            // $update_main = "UPDATE main SET res_name='$chk_main',last_usercode=$userCode,last_dt=NOW() WHERE diary_no='$dno'";
                            // mysql_query($update_main) or die(__LINE__.'->'.mysql_error());
                            $builder6 = $this->db->table("main");
                            $builder6->set('res_name', $chk_main);
                            $builder6->set('last_usercode', $userCode);
                            $builder6->set('last_dt', 'NOW()');
                            $builder6->where('diary_no', $dno);
                            $update_main = $builder6->update();
                        }
                        // echo "<br>CAUSE-TITLE UPDATED";
                    }
            }
            $countdot=substr_count($dataArr['p_no'], '.');
            //    echo " no of dots are ".$countdot."  ";

            if( strpos( $dataArr['p_no'], '.' ) == true &&  ($dataArr['p_sta']=='T') && $countdot==1) {
                // $update_further = "UPDATE party   SET sr_no_show= concat(split_part(sr_no_show, '.', 1),'.',split_part(sr_no_show, '.', -1)-1),last_usercode='$userCode',last_dt=NOW() WHERE diary_no='$dno' AND pet_res='$dataArr[p_f]' AND sr_no_show>'$dataArr[p_no]' and sr_no=split_part('$dataArr[p_no]', '.', 1) AND pflag not in('T','Z') ";
                // $this->db->query($update_further);

                             
                $builder6 = $this->db->table('party');
                // $builder6->set('sr_no_show', "CONCAT(cast(split_part(sr_no_show, '.', 1) as integer), '.', cast(split_part(sr_no_show, '.', -1) as integer) - 1)", false);
                $builder6->set('sr_no_show', "CASE WHEN (position('.' in sr_no_show) != 0 ) and (length(sr_no_show) <= 3 ) THEN CONCAT(cast(split_part(sr_no_show, '.', 1) as integer), '.', cast(split_part(sr_no_show, '.', -1) as integer) - 1) when (position('.' in sr_no_show) != 0) and (length(sr_no_show) > 3 and length(sr_no_show) < 5 ) then cast((sr_no - (substring(sr_no_show, position('.' in sr_no_show))::numeric - 2) ) as text) when (position('.' in sr_no_show) != 0) and (length(sr_no_show) = 5 ) then CAST(split_part(sr_no_show, '.', 1)::integer || '.' || (split_part(sr_no_show, '.', 2)::integer - 1)::text ||  '.' || split_part(sr_no_show, '.', 3) AS TEXT) else cast((sr_no - 1) as text) end", false);
                // $builder6->set('last_usercode', $userCode);
                $builder6->set('last_dt', 'NOW()');
                $builder6->where('diary_no', $dno);
                $builder6->where('pet_res', $dataArr['p_f']);
                $builder6->where('sr_no_show >', $dataArr['p_no']);
                $builder6->where('sr_no', "CAST(split_part('{$dataArr['p_no']}', '.', 1) AS INTEGER)", false);
                $builder6->whereNotIn('pflag', ['T', 'Z']);                
                // $query= $builder6->getCompiledUpdate();
                // echo (string) $query; exit();
                $builder6->update();

            }
            if( strpos( $dataArr['p_no'], '.' ) == true &&  ($dataArr['p_sta']=='T') && $countdot==2) {
                // $update_further = "UPDATE party SET sr_no_show= concat(split_part(sr_no_show, '.', 2),'.',split_part(sr_no_show, '.', -1)-1),last_usercode='$userCode',last_dt=NOW() WHERE diary_no='$dno' AND pet_res='$dataArr[p_f]' AND sr_no_show>'$dataArr[p_no]' and sr_no= split_part('$dataArr[p_no]', '.', 1)  AND pflag not in('T','Z') ";
                // $this->db->query($update_further);

                // $updata = [
                //     // 'sr_no_show' => 'concat(cast(split_part(sr_no_show, ".", 2) as integer),".",cast(split_part(sr_no_show, ".", -1) as integer) -1)',
                //     // 'last_usercode' => $userCode,
                //     'last_dt' => 'NOW()'
                // ];
                $builder6 = $this->db->table("party");
                // $builder6->set('sr_no_show', "CAST(split_part(sr_no_show, '.', 1)::integer || '.' || (split_part(sr_no_show, '.', 2)::integer )::text ||  '.' || (split_part(sr_no_show, '.', 3)::integer  - 1) AS TEXT)", false );
                 $builder6->set('sr_no_show', "CASE WHEN (position('.' in sr_no_show) != 0 ) and (length(sr_no_show) <= 3 ) THEN CONCAT(cast(split_part(sr_no_show, '.', 1) as integer), '.', cast(split_part(sr_no_show, '.', -1) as integer) - 1) 
                 when (position('.' in sr_no_show) != 0) and (length(sr_no_show) = 5 ) then CAST(split_part(sr_no_show, '.', 1)::integer || '.' || (split_part(sr_no_show, '.', 2)::integer)::text ||  '.' || (split_part(sr_no_show, '.', 3):: integer - 1)  AS TEXT) 
                 else cast((sr_no - 1) as text) end", false);
                $builder6->where('diary_no', $dno);
                $builder6->where('pet_res', $dataArr['p_f']);
                $builder6->where('sr_no_show >', $dataArr['p_no']);
                $builder6->where("sr_no = CAST(split_part('$dataArr[p_no]', '.', 1) AS INTEGER)");
                $builder6->whereNotIn('pflag', ['T', 'Z']);
                // $query= $builder6->getCompiledUpdate();
                // echo (string) $query; exit();
                $builder6->update();
            }
            if( strpos( $dataArr['p_no'], '.' ) == true &&  ($dataArr['p_sta']=='T') && $countdot==3) {
                //echo "LR  ".$dataArr[p_no]."   ";
                // $update_further = "UPDATE party SET sr_no_show= concat(split_part(sr_no_show, '.', 3),'.',split_part(sr_no_show, '.', -1)-1),last_usercode='$userCode',last_dt=NOW() WHERE diary_no='$dno' AND pet_res='$dataArr[p_f]' AND sr_no_show>'$dataArr[p_no]' and sr_no= split_part('$dataArr[p_no]', '.', 1) AND pflag not in('T','Z') ";
                // $this->db->query($update_further);
                // $updata = [
                //     'sr_no_show' => 'concat(cast(split_part(sr_no_show, ".", 3) as integer),".",cast(split_part(sr_no_show, ".", -1) as integer) -1)',
                //     'last_usercode' => $userCode,
                //     'last_dt' => 'NOW()'
                // ];
                $builder6 = $this->db->table("party");
                $builder6->set('sr_no_show', "CAST(split_part(sr_no_show, '.', 1)::integer || '.' || (split_part(sr_no_show, '.', 2)::integer ) ||  '.' || (split_part(sr_no_show, '.', 3)::integer) || '.' || (split_part(sr_no_show, '.', 4)::integer - 1) AS TEXT)", false );
                $builder6->where('diary_no', $dno);
                $builder6->where('pet_res', $dataArr['p_f']);
                $builder6->where('sr_no_show >', $dataArr['p_no']);
                $builder6->where("sr_no = split_part('$dataArr[p_no]', '.', 1)");
                $builder6->whereNotIn('pflag', ['T', 'Z']);
                $builder6->update();
            }
            if( strpos( $dataArr['p_no'], '.' ) == true &&  ($dataArr['p_sta']=='T') && $countdot==4) {
                // $update_further = "UPDATE party SET sr_no_show= concat(split_part(sr_no_show, '.', 4),'.',split_part(sr_no_show, '.', -1)-1),last_usercode='$userCode',last_dt=NOW() WHERE diary_no='$dno' AND pet_res='$dataArr[p_f]' AND sr_no_show>'$dataArr[p_no]' and sr_no= split_part('$dataArr[p_no]', '.', 1) AND pflag not in('T','Z') ";
                // $this->db->query($update_further);
                // $updata = [
                //     'sr_no_show' => 'concat(cast(split_part(sr_no_show, ".", 4) as integer),".",cast(split_part(sr_no_show, ".", -1) as integer)-1)',
                //     'last_usercode' => $userCode,
                //     'last_dt' => 'NOW()'
                // ];
                $builder6 = $this->db->table("party");
                $builder6->set('sr_no_show', "CAST(split_part(sr_no_show, '.', 1)::integer || '.' || (split_part(sr_no_show, '.', 2)::integer ) ||  '.' || (split_part(sr_no_show, '.', 3)::integer) || '.' || (split_part(sr_no_show, '.', 4)::integer) || '.' || (split_part(sr_no_show, '.', 5)::integer - 1 ) AS TEXT)", false );
                $builder6->where('diary_no', $dno);
                $builder6->where('pet_res', $dataArr['p_f']);
                $builder6->where('sr_no_show >', $dataArr['p_no']);
                $builder6->where("sr_no = split_part('$dataArr[p_no]', '.', 1)");
                $builder6->whereNotIn('pflag', ['T', 'Z']);
                $builder6->update();
            }

            if(strpos( $dataArr['p_no'], '.' ) == false && $dataArr['p_sta']=='T'){
                // $update_further = "UPDATE party SET sr_no_show=CONCAT(sr_no-1,substring(sr_no_show,LOCATE('.', sr_no_show))),sr_no=sr_no-1,last_usercode='$userCode',last_dt=NOW() WHERE diary_no='$dno' AND pet_res='$dataArr[p_f]' AND sr_no>'$dataArr[p_no]' AND pflag not in('T','Z') ";
                // $this->db->query($update_further);
             
                // $builder6 = $this->db->table("party");
                // $builder6->set('sr_no_show', "CONCAT(sr_no-1,substring(sr_no_show,LOCATE('.', sr_no_show)))", false);
                // $builder6->set('last_usercode', $userCode);
                // $builder6->set('last_dt', 'NOW()');
                // $builder6->where('diary_no', $dno);
                // $builder6->where('pet_res', $dataArr['p_f']);
                // $builder6->where('sr_no >', $dataArr['p_no']);
                // $builder6->whereNotIn('pflag', ['T', 'Z']);
                // $builder6->update();


                // $data = [
                //     'sr_no_show' => "CONCAT(sr_no - 1, substring(sr_no_show, POSITION('.' IN sr_no_show)))",
                //     'sr_no' => 'sr_no - 1',
                //     'last_usercode' => $userCode,
                //     'last_dt' => 'NOW()',
                // ];
                
                $builder6 = $this->db->table('party');
                // $builder6->set($data, false); // Set the data without escaping
                // $builder6->set('sr_no_show', "CONCAT(sr_no - 1, substring(sr_no_show, POSITION('.' IN sr_no_show)))", false);
                $builder6->set('sr_no_show', "CASE WHEN (position('.' in sr_no_show) != 0 ) THEN cast(CONCAT(sr_no - 1, substring(sr_no_show, position('.' in sr_no_show))) as text) else cast((sr_no - 1) as text)	END", false);
                $builder6->set('sr_no', 'sr_no - 1', false);
                $builder6->set('last_usercode', $userCode, false);
                $builder6->set('last_dt', 'NOW()', false);
                $builder6->where('diary_no', $dno);
                $builder6->where('pet_res', $dataArr['p_f']);
                $builder6->where('sr_no >', $dataArr['p_no']);
                $builder6->whereNotIn('pflag', ['T', 'Z']);     
                // $queryString = $builder6->getCompiledUpdate();
                // echo $queryString;
                // exit();                
                $builder6->update();
            }
            
            
            //starts here
            if($dataArr['p_no']==1){

                // $check_if_fil_user = "SELECT count(*) FROM fil_trap_users a WHERE a.usertype=101 AND a.display='Y'  and usercode='$userCode' ";
                // $check_if_fil_user_rs = $this->db->query($check_if_fil_user);
                $builder17 = $this->db->table("fil_trap_users");
                $builder17->select("count(*)");
                $builder17->where('usertype', '101');
                $builder17->where('usercode', $userCode);
                $builder17->where('display', 'Y');                   
                $query17 = $builder17->get();
                $if_fil_user= $query17->getResultArray();

                $if_fil_user = $if_fil_user[0]['count'];
                if ($if_fil_user != 0) {
                    // $scefm_qr = "SELECT e.diary_no ,t.diary_no, t.party_update_by from efiled_cases e left join efiled_cases_transfer_status t on e.diary_no=t.diary_no where e.diary_no='$dno' ";
                    // $scefm_rs = $this->db->query($scefm_qr);
                    $builder18 = $this->db->table("efiled_cases e");
                    //$builder18->select("e.diary_no ,t.diary_no, t.party_update_by");
                    $builder18->select('e.diary_no, t.diary_no as ects_diary_no, t.party_update_by');
                    $builder18->join('efiled_cases_transfer_status t', 'e.diary_no=t.diary_no', 'LEFT');
                    $builder18->where('e.diary_no', $dno);
                    $query18 = $builder18->get();

                    $is_scefm= $query18->getResultArray();
                    $is_scefm= $is_scefm[0];
                    

                    if (!empty($is_scefm) && ($is_scefm['diary_no'] != 0 and $is_scefm['diary_no'] != null)) {	    
                        if ($is_scefm['ects_diary_no'] != 0 and $is_scefm['ects_diary_no'] != null) {	
                            if ($is_scefm['party_update_by'] == null or $is_scefm['party_update_by'] == '') {	
                                // $scefm_up_qr = "Update efiled_cases_transfer_status set party_update_by='$userCode',party_update_on=now() where  diary_no='$dno'";
                                $builder6 = $this->db->table("efiled_cases_transfer_status");
                                $builder6->set('party_update_by', $userCode);
                                $builder6->set('party_update_on', 'NOW()');
                                $builder6->where('diary_no', $dno);
                                $scefm_up_qr = $builder6->update();
                            }
                        } else {
                            // $scefm_in_qr = "Insert Into efiled_cases_transfer_status(diary_no,party_update_by,party_update_on)
                            // values ('$dno','$userCode',now()) ";
                            $insertData = [
                                'diary_no' => $dno,
                                'party_update_by' => $userCode,
                                'party_update_on' => 'NOW()'
                            ];
                            $builder9 = $this->db->table("efiled_cases_transfer_status");
                            $builder9->insert($insertData);
                        }
                    }
                }
            }//ends here
            
            if($update > 0){
                echo json_encode(["message"=>"Party Modified Successfully"]);
            }else{
                echo json_encode(["message"=>"Error while Modifying"]);
            }
        }

    }

    public function deleteAction($dataset){
        $auto_generate_id = $dataset['id'];
        $selectedAction = $dataset['selectedAction']; 

        $dairy_no = $dataset['diary_no'];
        $builder = $this->db->table("party");
        $builder->select("*");
        $builder->where('pet_res !=','');
        $builder->where('diary_no', $dairy_no);
        $query = $builder->get();
        $result = $query->getResultArray();
        // echo "<pre>"; print_r($result); die;
        $pcount = 0;
        $rcount = 0;
        foreach ($result as $vae) {
            if($vae['pet_res'] == 'P'){ $pcount++; }
            if($vae['pet_res'] == 'R'){ $rcount++; }
        }
        if($pcount == 1 || $rcount == 1 ){
            return 2;
        }else{
            $builder2 = $this->db->table("party");
            $builder2->set('pflag', $selectedAction);
            $builder2->WHERE('auto_generated_id',$auto_generate_id);
            if($builder2->update()){
                return 1;
            }else{
                return 0;
            }
        }

    }

    public function getUpdateData($dataset){

         $diaryNo = $dataset['diaryNo'];
        // $flag = $dataset['flag'];
        // $sr_no = $dataset['sr_no'];
        // $type = $dataset['type'];
        $auto_gen_id = $dataset['auto_generated_id'];

        // $pet_q = "select partyname,partysuff,sonof,authcode,state_in_name,prfhname,age,sex,caste,addr1,addr2,a.state,city,pin,email,contact,dstname,a.country,a.deptcode,education,
        // occ_code,edu_code,deptname,remark_lrs,count(c.id) add_add,auto_generated_id,cont_pro_info
        // from party a 
        // left join master.deptt b on state_in_name=b.deptcode
        // left join party_additional_address c ON a.auto_generated_id=c.party_id and c.display='Y'
        // LEFT JOIN party_lowercourt d ON a.auto_generated_id=d.party_id and d.display='Y'
        // where auto_generated_id = '$auto_gen_id'
        // GROUP BY partyname,partysuff,sonof,authcode,state_in_name,prfhname,age,sex,caste,addr1,addr2,a.state,city,pin,email,contact,dstname,a.country,a.deptcode,education,occ_code,edu_code, deptname,remark_lrs, auto_generated_id,cont_pro_info";
        // $query = $this->db->query($pet_q);


        $builder = $this->db->table('party a');
        $builder->select('partyname, partysuff, sonof, authcode, state_in_name, prfhname, age, sex, caste, addr1, addr2, a.state, city, pin, email, contact, dstname, a.country, a.deptcode, education, occ_code, edu_code, deptname, remark_lrs, COUNT(c.id) as add_add, array_agg(distinct concat(c.country,\'~\',c.state,\'~\', c.district, \'~\',  c.address )) as con_addition, auto_generated_id, cont_pro_info, array_agg(distinct d.lowercase_id) AS lowercase_id');
        $builder->join('master.deptt b', 'state_in_name = b.deptcode', 'LEFT');
        $builder->join('party_additional_address c', "a.auto_generated_id = c.party_id AND c.display = 'Y'", 'LEFT');
        $builder->join('party_lowercourt d', "a.auto_generated_id = d.party_id AND d.display = 'Y'", 'LEFT');
        $builder->where('auto_generated_id', $auto_gen_id);
        $builder->where('diary_no', $diaryNo);
        $builder->groupBy('partyname, partysuff, sonof, authcode, state_in_name, prfhname, age, sex, caste, addr1, addr2, a.state, city, pin, email, contact, dstname, a.country, a.deptcode, education, occ_code, edu_code, deptname, remark_lrs, auto_generated_id, cont_pro_info');
        // $queryString = $builder->getCompiledSelect();
        // echo $queryString;
        // exit();
        $query = $builder->get();
        $resArr = $query->getResultArray();

        if(!empty($resArr)){
            return json_encode($resArr);
        }else{
            return json_encode([]);
        }
    }

    public function geteduList(){
        $builder = $this->db->table("master.education_type");
        $builder->select("id,edu_desc");
        $builder->where('display','Y');
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function getoccupList(){ 
        $builder = $this->db->table("master.occupation_type");
        $builder->select("id,occ_desc");
        $builder->where('display','Y');
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function get_cause_title($dataset){
        $dairy_no = $dataset['d_no'] . $dataset['d_yr'];

        // $cause_title = "select trim(pet_name) pet_name,trim(res_name) res_name from main where diary_no=".$dairy_no;
        // $cause_title = mysql_query($cause_title) or die("Error: " . __LINE__ . mysql_error());
        // $no_of_rows=  mysql_num_rows($cause_title);
        $builder = $this->db->table("main");
        $builder->select("trim(pet_name) pet_name, trim(res_name) res_name");
        $builder->where('diary_no', $dairy_no);
        $query = $builder->get();
        $result = $query->getResultArray();
        if(!empty($result)){
            foreach ($result as $key => $row) {
                echo "<font style='text-align: center;font-size: 14px;color: red'>Cause Title: </font></br>";
                echo "<font style='text-align: center;font-size: 18px;color: blue'>".$row['pet_name']."</font>&nbsp;&nbsp;";
                echo "<font style='text-align: center;font-size: 14px;color: blue'>VS</font>&nbsp;&nbsp;";
                echo "<font style='text-align: center;font-size: 18px;color: blue'>".$row['res_name']."</font></br>&nbsp;";
            }
        }else{
            echo "<font style='text-align: center;font-size: 14px;color: black'>Case not found</font>";
        }

    }

    public function copy_party_details($dataset){
        $fdno=$dataset['d1'];
        $tdno=$dataset['d2'];

        $builder1 = $this->db->table("main");
        $builder1->select("c_status");
        $builder1->where('diary_no', $tdno);
        $query1 = $builder1->get();
        $rowchk = $query1->getResultArray();

        if(empty($rowchk)){
            echo "Case not found.";
            exit();
        }else{
            $status=$rowchk[0]['c_status'];
            if(trim($status)=='D'){
                echo "DETAILS CANNOT BE COPIED IN A DISPOSED OFF MATTER";
                exit();    
            } 
        }
        
    
        $builder2 = $this->db->table("main");
        $builder2->select("casetype_id");
        $builder2->where('diary_no', $tdno);
        $query2 = $builder2->get();
        $rowct = $query2->getResultArray();
        $ct=$rowct[0]['casetype_id'];

        if(($ct != 5) && ($ct != 6)){
            $builder3 = $this->db->table("lowerct");
            $builder3->select("count(*)");
            $builder3->where('diary_no', $fdno);
            $builder3->where('lw_display', 'Y');
            $query3 = $builder3->get();
            $rowchk1 = $query3->getResultArray();
            $cnt = $rowchk1[0]['count'];
            if ($cnt == 0){
                echo "COPY HIGH COURT DETAILS FIRST !!!";
                exit();    
            }
        }
        $builder4 = $this->db->table("party");
        $builder4->select("*");
        $builder4->where('diary_no', $fdno);
        $builder4->where('sr_no !=', '1');
        $builder4->where('sr_no_show !=', '1');
        $builder4->where('pflag !=', 'T');
        $query4 = $builder4->get();        
        $rs = $query4->getResultArray();
        $tr = count($rs);
        
        $ucode =  $_SESSION['login']['usercode'];
        $ip_address= $_SERVER['REMOTE_ADDR'];

        if(!empty($rs)){
            foreach ($rs as $key => $row) {   
                // echo "<pre>"; print_r($row); die;         
                $inserrtData = [
                    'diary_no' => (int) $tdno,
                    'pet_res' => $row['pet_res'],
                    'sr_no' => (int)$row['sr_no'],
                    'sr_no_show' => $row['sr_no_show'],
                    'ind_dep' => $row['ind_dep'],
                    'partysuff' => $row['partysuff'],
                    'partyname' => $row['partyname'],
                    'sonof' => $row['sonof'],
                    'authcode' => (int)$row['authcode'],
                    'state_in_name' => (int)$row['state_in_name'],
                    'prfhname' => $row['prfhname'],
                    'age' => (int)$row['age'],
                    'sex' => $row['sex'],
                    'caste' => $row['caste'],
                    'addr1' => $row['addr1'],
                    'addr2' => $row['addr2'],
                    'state' => $row['state'],
                    'city' => $row['city'],
                    'pin' => (int)$row['pin'],
                    'email' => $row['email'],
                    'contact' => $row['contact'],
                    'usercode' => (int)$row['usercode'],   
                    'ent_dt' => 'NOW()',
                    'pflag' => 'Z',
                    'dstname' => $row['dstname'],
                    'deptcode' => (int)$row['deptcode'],
                    'pan_card' => $row['pan_card'],
                    'adhar_card' => $row['adhar_card'],
                    'country' => (int)$row['country'],
                    'education' => $row['education'],
                    'occ_code' => (int)$row['occ_code'],
                    'edu_code' => (int)$row['edu_code'],
                    'lowercase_id' => 0,  // (int)$row['lowercase_id']
                    'auto_generated_id' => (int)$row['auto_generated_id'],
                    'remark_lrs' => $row['remark_lrs'],
                    'remark_del' => $row['remark_del'],
                    'cont_pro_info' => $row['cont_pro_info'],
                    'last_dt' => null,
                    'last_usercode' => null,
                    'create_modify' => date('Y-m-d H:i:s'),
                    'updated_on' => date('Y-m-d H:i:s'),
                    'updated_by' => (int) $ucode,
                    'updated_by_ip' => $ip_address,
                ];
    
                $builder8 = $this->db->table("party");
                $rs1 = $builder8->insert($inserrtData);
              
            }
            
            if ($rs1) {
                echo $tr . " Records Found and Copied Successfully";
            } else {
                echo "Error!!. Contact Server Room.";
            }
            
        }else{
            echo "Error!!. Contact Server Room.";
        }

        
    

    }

    public function getcountryList(){
        $builder = $this->db->table("master.country");
        $builder->select("country_name,id");
        $builder->where('display', 'Y');
        $builder->orderBy('country_name');
        $query = $builder->get();
        $result = $query->getResultArray();
        if(!empty($result)){
            return $result;
        }else{ return []; }
    }

    public function get_only_state_name(){
        // SELECT deptcode,deptname FROM (SELECT deptcode,deptname FROM deptt WHERE deptname LIKE 'THE UNION TERRITORY%' OR deptname LIKE 'THE STATE OF%' OR deptcode=2 )x
        $subquery = $this->db->table('master.deptt')
            ->select('deptcode, deptname')
            ->like('deptname', 'THE UNION TERRITORY%')
            ->orLike('deptname', 'THE STATE OF%')
            ->orwhere('deptcode', 2)
            ->getCompiledSelect();

        $query = $this->db->query("SELECT deptcode, deptname FROM ($subquery) AS x");
        $result = $query->getResultArray();
        // echo "<pre>"; print_r($result); die;
        if(!empty($result)){
            $json=array();
            foreach ($result as $row) {
                $json[]=array('value'=>$row['deptcode'].'~'.$row['deptname'], 'label'=>$row['deptname']);
            }
            return $json;
        }else{ return []; }

    }

    public function get_only_post(){
        $builder = $this->db->table("master.authority");
        $builder->select("authcode,authdesc");
        $builder->where('display', 'Y');
        $query = $builder->get();
        $result = $query->getResultArray();
        if(!empty($result)){
            $json=array();
            foreach ($result as $row) {
                $json[]=array('value'=>$row['authcode'].'~'.$row['authdesc'], 'label'=>$row['authdesc']);
            }
            return $json;
            
        }else{ return []; }
    }

    public function get_only_deptt($deptt){

        // echo $deptt; die;
        // $deptt =  $deptt['deptt'];
        $builder = $this->db->table("master.deptt");
        $builder->select("deptcode,deptname");
        $builder->where('display', 'Y');
        if($deptt == 'D1'){
            $builder->where('deptype', 'S');
        }else if($deptt == 'D2'){
            $builder->where('deptype', 'C');
        }else if($deptt == 'D3'){
            $builder->whereNotIn('deptype', ['S', 'C']);
        }
        $query = $builder->get();
        $result = $query->getResultArray();
        if(!empty($result)){
            $json=array();
            foreach ($result as $row) {
                $json[]=array('value'=>$row['deptcode'].'~'.$row['deptname'], 'label'=>$row['deptname']);
            }
            return json_encode($json, true);
        }else{ return []; }

    }


    public function get_petResCaseTitle($diary_no){
        $dataArr = [];
        $builder = $this->db->table("party");
        $builder->select("partyname as pet_name");
        $builder->where('diary_no',$diary_no);
        $builder->where('pet_res !=','');
        $builder->where('pet_res','P');
        $builder->where('pflag','P');
        $builder->whereNotIn('pflag', ['T', 'Z']);
        $builder->orderBy('sr_no', 'ASC');
        $builder->orderBy('sr_no_show', 'ASC');
        $builder->limit(1);
        $query = $builder->get();
        $pet_name = $query->getResultArray();
        // echo "<pre>"; print_r($pet_name); 
        if(!empty($pet_name)){
            $dataArr['pet_name'] = $pet_name[0]['pet_name']; 
        }

        $builder1 = $this->db->table("party");
        $builder1->select("partyname as res_name");
        $builder1->where('diary_no',$diary_no);
        $builder1->where('pet_res !=','');
        $builder1->where('pet_res','R');
        $builder1->where('pflag','P');
        $builder1->whereNotIn('pflag', ['T', 'Z']);
        $builder1->orderBy('sr_no', 'ASC');
        $builder1->orderBy('sr_no_show', 'ASC');
        $builder1->limit(1);
        $query1 = $builder1->get();
        $res_name = $query1->getResultArray();
        // echo "<pre>"; print_r($res_name); die;
        if(!empty($res_name)){
            $dataArr['res_name'] = $res_name[0]['res_name']; 
        }
        //  echo "<pre>"; print_r($dataArr); die;
        return $dataArr;
    }


    public function casetypeDetails($diary_no){

        // $casetype = "SELECT casetype_id,fil_no,fil_dt,fil_no_fh,fil_dt_fh,short_description,IF(reg_year_mh=0,YEAR(a.fil_dt),reg_year_mh) m_year, IF(reg_year_fh=0,YEAR(a.fil_dt_fh),reg_year_fh) f_year,pno,rno FROM main a LEFT JOIN casetype b ON SUBSTR(fil_no,1,2)=casecode WHERE diary_no='$_REQUEST[dno]'";
        // $casetype = mysql_query($casetype) or die(__LINE__.'->'.mysql_query());

        $builder1 = $this->db->table('main a');
        $builder1->select([
            'casetype_id',
            'fil_no',
            'fil_dt',
            'fil_no_fh',
            'fil_dt_fh',
            'short_description',
            'CASE WHEN reg_year_mh = 0 THEN EXTRACT(YEAR FROM a.fil_dt) ELSE reg_year_mh END AS m_year',
            'CASE WHEN reg_year_fh = 0 THEN EXTRACT(YEAR FROM a.fil_dt_fh) ELSE reg_year_fh END AS f_year',
            'pno',
            'rno'
        ]);
        $builder1->join('master.casetype b', "cast(SUBSTRING(fil_no FROM 1 FOR 2) as integer ) = casecode", 'LEFT');
        $builder1->where('diary_no', $diary_no);
        $query = $builder1->get();

        $result = $query->getResultArray();
        if(!empty($result)){
            return $result;
        }else{ return []; }

    }
	
	
	public function casetypeDetail($diary_no){

        // $casetype = "SELECT casetype_id,fil_no,fil_dt,fil_no_fh,fil_dt_fh,short_description,IF(reg_year_mh=0,YEAR(a.fil_dt),reg_year_mh) m_year, IF(reg_year_fh=0,YEAR(a.fil_dt_fh),reg_year_fh) f_year,pno,rno FROM main a LEFT JOIN casetype b ON SUBSTR(fil_no,1,2)=casecode WHERE diary_no='$_REQUEST[dno]'";
        // $casetype = mysql_query($casetype) or die(__LINE__.'->'.mysql_query());

        $builder1 = $this->db->table('main a');
        $builder1->select([
            'casetype_id',
            'fil_no',
            'fil_dt',
            'fil_no_fh',
            'fil_dt_fh',
            'short_description',
            'CASE WHEN reg_year_mh = 0 THEN EXTRACT(YEAR FROM a.fil_dt) ELSE reg_year_mh END AS m_year',
            'CASE WHEN reg_year_fh = 0 THEN EXTRACT(YEAR FROM a.fil_dt_fh) ELSE reg_year_fh END AS f_year',
            'pno',
            'rno'
        ]);
        $builder1->join('master.casetype b', "cast(SUBSTRING(fil_no FROM 1 FOR 2) as integer ) = casecode", 'LEFT');
        $builder1->where('diary_no', $diary_no);
        $query = $builder1->get();

        $result = $query->getRowArray();
        if(!empty($result)){
            return $result;
        }else{ return []; }

    }
	
	
	
	public function getSRNo($diaryno,$forparty)
	{
		$builder = $this->db->table('party');
		$builder->select('sr_no, partyname');
		$builder->where('diary_no', $diaryno);
		$builder->where('pet_res', $forparty);
		$builder->where('pflag', 'P');
		$builder->orderBy('sr_no');
		$builder->orderBy("CAST(SPLIT_PART(sr_no_show, '.', 1) AS TEXT)");
		$builder->orderBy("CAST(SPLIT_PART(sr_no_show, '.', 2) AS TEXT)");
		$builder->orderBy("CAST(SPLIT_PART(sr_no_show, '.', 3) AS TEXT)");
		$builder->orderBy("CAST(SPLIT_PART(sr_no_show, '.', 4) AS TEXT)");
		$builder->limit(1);
        // pr($builder->getCompiledSelect());
		$query = $builder->get();
		return $result = $query->getRowArray();

	}
	
	
	public function updateDispose($dis_flag,$ucode,$diaryno,$spartyid,$resremark)
	{
		$builder = $this->db->table('party');
		$builder->set('pflag', $dis_flag);
		$builder->set('last_usercode', $ucode);
		$builder->set('last_dt', 'NOW()', false); // 'NOW()' as a raw string
		$builder->set("remark_del", "CASE WHEN remark_del IS NOT NULL AND TRIM(remark_del) != '' THEN CONCAT(remark_del, ';', '$resremark') ELSE '$resremark' END", false);

		$builder->where('diary_no', $diaryno);
		$builder->whereIn('auto_generated_id', explode(',', $spartyid)); // Assuming $spartyid is a comma-separated string
        // pr($builder->getCompiledUpdate());
	$result = 	$builder->update();
		return $result;
	}
	
	public function getDisposeParty($diary_no)
	{
		$builder = $this->db->table('party');
		$builder->select('auto_generated_id, pet_res, sr_no, partyname, remark_del, sr_no_show');
		$builder->whereIn('pflag', ['O', 'D']);
		$builder->where('diary_no', $diary_no);

		// Order by pet_res and then by the parsed parts of sr_no_show
		$builder->orderBy('pet_res');
		$builder->orderBy("CAST(SPLIT_PART(sr_no_show, '.', 1) AS TEXT)");
		$builder->orderBy("CAST(SPLIT_PART(sr_no_show, '.', 2) AS TEXT)");
		$builder->orderBy("CAST(SPLIT_PART(sr_no_show, '.', 3) AS TEXT)");
		$builder->orderBy("CAST(SPLIT_PART(sr_no_show, '.', 4) AS TEXT)");

		// Execute the query
		$query = $builder->get();
		return $result = $query->getResultArray();

	}
	
	public function getPartyDetails($dno,$pet_res)
{
     
    // Prepare the query
    $p_pet_q = "
        SELECT 
            partyname, 
            sr_no, 
            sr_no_show, 
            lowercase_id, 
            auto_generated_id, 
            ind_dep, 
            l_dist, 
            ct_code, 
            lct_casetype, 
            lct_caseno, 
            lct_caseyear, 
            remark_del, 
            remark_lrs, 
            pflag,
            STRING_AGG(CONCAT(type_sname, '/', lct_caseno, '/', lct_caseyear), ',') AS caseno 
        FROM (
            SELECT 
                partyname, 
                sr_no, 
                sr_no_show, 
                c.lowercase_id, 
                auto_generated_id, 
                ind_dep, 
                l_dist, 
                ct_code, 
                lct_casetype, 
                lct_caseno, 
                lct_caseyear, 
                remark_del, 
                remark_lrs, 
                pflag,
                COALESCE(
                    (SELECT skey FROM master.casetype ct WHERE ct.display = 'Y' AND ct.casecode = lct_casetype),
                    (SELECT type_sname FROM master.lc_hc_casetype d WHERE d.lccasecode = lct_casetype AND d.display = 'Y')
                ) AS type_sname 
            FROM party a
            LEFT JOIN party_lowercourt c ON a.auto_generated_id = c.party_id AND c.display = 'Y'
            LEFT JOIN lowerct b ON c.lowercase_id = b.lower_court_id 
            WHERE a.diary_no = $dno 
              AND pet_res = '$pet_res' 
              AND pflag != 'T'
        ) x
        GROUP BY 
            partyname, 
            sr_no, 
            sr_no_show, 
            lowercase_id, 
            auto_generated_id, 
            ind_dep, 
            l_dist, 
            ct_code, 
            lct_casetype, 
            lct_caseno, 
            lct_caseyear, 
            remark_del, 
            remark_lrs, 
            pflag
        ORDER BY 
            CAST(SPLIT_PART(sr_no_show, '.', 1) AS TEXT),
            CAST(SPLIT_PART(sr_no_show, '.', 2) AS TEXT),
            CAST(SPLIT_PART(sr_no_show, '.', 3) AS TEXT),
            CAST(SPLIT_PART(sr_no_show, '.', 4) AS TEXT)
    ";

    // Execute the query
    return $this->db->query($p_pet_q)->getResultArray();
}

public function getPartyDetails_new($dno,$pet_res)
{
    
    // Prepare the query
    $query = "
        SELECT * FROM (
            SELECT 
                a.diary_no, 
                pet_res, 
                sr_no, 
                sr_no_show, 
                partyname, 
                pflag, 
                b.lowercase_id, 
                a.ent_dt,
                COALESCE(
                    (SELECT skey FROM master.casetype ct WHERE ct.display = 'Y' AND ct.casecode = lct_casetype),
                    (SELECT type_sname FROM master.lc_hc_casetype d WHERE d.lccasecode = lct_casetype AND d.display = 'Y')
                ) AS type_sname,
                lct_caseno, 
                lct_caseyear
            FROM party a 
            LEFT JOIN party_lowercourt b ON a.auto_generated_id = b.party_id
            LEFT JOIN lowerct l ON b.lowercase_id = l.lower_court_id
            WHERE a.diary_no = $dno AND pet_res = '$pet_res' AND pflag != 'T' AND b.display = 'Y'
        ) a
        LEFT JOIN (
            SELECT 
                b.lowercase_id, 
                diary_no, 
                COUNT(*) AS nos
            FROM party a
            LEFT JOIN party_lowercourt b ON a.auto_generated_id = b.party_id AND b.display = 'Y'
            WHERE diary_no = $dno AND b.lowercase_id != 0 AND pet_res = '$pet_res' AND pflag != 'T'
            GROUP BY b.lowercase_id, diary_no
        ) b ON a.lowercase_id = b.lowercase_id
        ORDER BY 
            a.lowercase_id,
            CAST(COALESCE(NULLIF(SPLIT_PART(sr_no_show, '.', 1), ''), '0') AS INTEGER),
		    CAST(COALESCE(NULLIF(SPLIT_PART(sr_no_show, '.', 2), ''), '0') AS INTEGER),
		    CAST(COALESCE(NULLIF(SPLIT_PART(sr_no_show, '.', 3), ''), '0') AS INTEGER),
		    CAST(COALESCE(NULLIF(SPLIT_PART(sr_no_show, '.', 4), ''), '0') AS INTEGER)";

        //CAST(SPLIT_PART(sr_no_show, '.', 1) AS INTEGER),
        //CAST(SPLIT_PART(sr_no_show, '.', 2) AS INTEGER),
        //CAST(SPLIT_PART(sr_no_show, '.', 3) AS INTEGER),
        //CAST(SPLIT_PART(sr_no_show, '.', 4) AS INTEGER)
    // Execute the query
    return $this->db->query($query)->getResultArray();
}


public function getExtrapartyInfo($fno, $flag, $id, $type)
{
    // Start building the query
    $builder = $this->db->table('party a');
    
    // Joining tables
    $builder->select([
        'partyname',
        'partysuff',
        'sonof',
        'authcode',
        'state_in_name',
        'prfhname',
        'age',
        'sex',
        'caste',
        'addr1',
        'addr2',
        'a.state',
        'city',
        'pin',
        'email',
        'contact',
        'dstname',
        'a.country',
        'a.deptcode',
        'education',
        'occ_code',
        'edu_code',
        'STRING_AGG(d.lowercase_id::TEXT, \',\') AS lowercase_id',
        'deptname',
        'remark_lrs',
        'COUNT(c.id) AS add_add',
        'auto_generated_id',
        'cont_pro_info',
        'party_name'
    ]);
    
    $builder->join('master.deptt b', 'state_in_name = b.deptcode', 'left')
            ->join('party_additional_address c', 'a.auto_generated_id = c.party_id AND c.display = \'Y\'', 'left')
            ->join('party_lowercourt d', 'a.auto_generated_id = d.party_id AND d.display = \'Y\'', 'left')
            ->join('masked_party_info m', 'a.diary_no = m.diary_no AND a.auto_generated_id = m.party_id AND m.display = \'Y\' AND a.is_masked = \'Y\'', 'left');

    // Adding the where conditions
    $builder->where('a.diary_no', $fno)
            ->where('pet_res', $flag)
            ->where('sr_no_show', $id)
            ->where('ind_dep', $type)
            ->where('pflag', 'P');

    // Grouping the results
    $builder->groupBy([
        'partyname',
        'partysuff',
        'sonof',
        'authcode',
        'state_in_name',
        'prfhname',
        'age',
        'sex',
        'caste',
        'addr1',
        'addr2',
        'a.state',
        'city',
        'pin',
        'email',
        'contact',
        'dstname',
        'a.country',
        'a.deptcode',
        'education',
        'occ_code',
        'edu_code',
        'deptname',
        'remark_lrs',
        'auto_generated_id',
        'cont_pro_info',
        'party_name'
    ]);

    // Execute the query and return the results
    return $builder->get()->getRowArray();
}

public function getEfiledCases($dno)
{
	// Load the database connection
	$db = \Config\Database::connect();

	// Use the Query Builder to create the query
	$builder = $db->table('efiled_cases e');
	$builder->select('e.diary_no, t.diary_no as ects_diary_no, t.party_update_by');
	$builder->join('efiled_cases_transfer_status t', 'e.diary_no = t.diary_no', 'left');
	$builder->where('e.diary_no', $dno); // Assuming $dno is already escaped or sanitized

	// Execute the query
	$query = $builder->get();

	// Fetch the result
	return $is_scefm = $query->getRowArray();

	if (!$is_scefm) {
		// Handle case where no result is found
		die("Error: No results found.");
	}

}





}