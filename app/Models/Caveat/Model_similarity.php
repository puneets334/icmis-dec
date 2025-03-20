<?php

namespace App\Models\Caveat;

use CodeIgniter\Model;

class Model_similarity extends Model
{
    public function get_SBCJ($caveat_no,$is_order_challenged=null,$flag='')
    {
        $caveatSBCJ_pending=$this->get_SBCJ_details($caveat_no,$is_order_challenged,'','',$flag);
        $caveatSBCJ_disposed=$this->get_SBCJ_details($caveat_no,$is_order_challenged,'_a','_a',$flag);
        $caveatSBCJ_disposed2=$this->get_SBCJ_details($caveat_no,$is_order_challenged,'','_a',$flag);
        $caveatSBCJ_disposed_others=$this->get_SBCJ_details($caveat_no,$is_order_challenged,'_a','',$flag);
        $caveatSBCJ=array_merge($caveatSBCJ_pending,$caveatSBCJ_disposed,$caveatSBCJ_disposed_others,$caveatSBCJ_disposed2);
        $final_array=array();
        if (!empty($caveatSBCJ)){
            foreach ($caveatSBCJ as $row) {
                $sub_details = $advocate_details_array = array(); $casetype_array['casetype_details'] = array(); $caveat_diary_matching_array['caveat_diary_matching'] = array();$getAdvocated_details=array();
                if (!empty($row['diary_no']) && $row['diary_no'] != null && $row['diary_no'] != '') {
                    if($flag=='D')
                        $caveat_diary_matching_array['caveat_diary_matching'] = is_data_from_table('caveat_diary_matching', ['caveat_no' => $row['c_diary'], 'display' => 'Y'], "diary_no as caveat_no,TO_CHAR(link_dt,'DD-MM-YYYY hh:ii:ss') AS link_dt");
                    else
                        $caveat_diary_matching_array['caveat_diary_matching'] = is_data_from_table('caveat_diary_matching', ['diary_no' => $row['diary_no'], 'display' => 'Y'], "caveat_no,TO_CHAR(link_dt,'DD-MM-YYYY hh:ii:ss') AS link_dt");                    $sub_details = $this->get_sub_details($row['diary_no']);
                    if (empty($sub_details)) {
                        $sub_details = $this->get_sub_details($row['diary_no'], '_a', '_a');
                    }
                    if (!empty($sub_details)) {
                        if (!empty($sub_details['active_fil_no']) && $sub_details['active_fil_no'] != null && $sub_details['active_fil_no'] != '') {
                            $casecode = substr($sub_details['active_fil_no'], 0, 2);
                            $casetype_array['casetype_details'] = is_data_from_table('master.casetype', ['casecode' => $casecode, 'display' => 'Y'], 'short_description', 'R');
                        }
                    }

                    $getAdvocated_details = $this->getCaveatAdvocates($row['c_diary'],'');

                    if(empty($getAdvocated_details)){
                        $getAdvocated_details = $this->getCaveatAdvocates($row['c_diary'],'_a');
                    }
                    $sub_array['advocate_details'] = $getAdvocated_details;

                    $sub_array['sub_details'] = $sub_details;
                    $final_array[] = array_merge($row, $sub_array, $casetype_array,$caveat_diary_matching_array);
                }
            }
        }
        return $final_array;
    }
    public function get_SBC($caveat_no,$is_order_challenged=null,$flag='')
    {
        $caveatSBC_pending=$this->get_SBC_details($caveat_no,$is_order_challenged,'','',$flag);
        $caveatSBC_disposed=$this->get_SBC_details($caveat_no,$is_order_challenged,'_a','_a',$flag);
        $caveatSBC_disposed2=$this->get_SBC_details($caveat_no,$is_order_challenged,'','_a',$flag);
        $caveatSBC_disposed_others=$this->get_SBC_details($caveat_no,$is_order_challenged,'_a','',$flag);
        $caveatSBC=array_merge($caveatSBC_pending,$caveatSBC_disposed,$caveatSBC_disposed_others,$caveatSBC_disposed2);
      
        $final_array=array();
        if (!empty($caveatSBC)){
            foreach ($caveatSBC as $row) {
                $sub_details = array(); $casetype_array['casetype_details'] = array(); $caveat_diary_matching_array['caveat_diary_matching'] = array();$getAdvocated_details=array();
                if (!empty($row['diary_no']) && $row['diary_no'] != null && $row['diary_no'] != '') {
                   if($flag=='D')
                       $caveat_diary_matching_array['caveat_diary_matching'] = is_data_from_table('caveat_diary_matching', ['caveat_no' => $row['c_diary'], 'display' => 'Y'], "diary_no as caveat_no,TO_CHAR(link_dt,'DD-MM-YYYY hh:ii:ss') AS link_dt");
                   else
                    $caveat_diary_matching_array['caveat_diary_matching'] = is_data_from_table('caveat_diary_matching', ['diary_no' => $row['diary_no'], 'display' => 'Y'], "caveat_no,TO_CHAR(link_dt,'DD-MM-YYYY hh:ii:ss') AS link_dt");
                    
                   
                    $sub_details = $this->get_sub_details($row['diary_no']);
                    
                    if (empty($sub_details)) {
                        $sub_details = $this->get_sub_details($row['diary_no'], '_a', '_a');
                    }
                   
                    if (!empty($sub_details)) {
                        if (!empty($sub_details['active_fil_no']) && $sub_details['active_fil_no'] != null && $sub_details['active_fil_no'] != '') {
                            $casecode = substr($sub_details['active_fil_no'], 0, 2);
                            $casetype_array['casetype_details'] = is_data_from_table('master.casetype', ['casecode' => $casecode, 'display' => 'Y'], 'short_description', 'R');
                        }
                    }
                    $getAdvocated_details = $this->getCaveatAdvocates($row['c_diary'],'');

                    if(empty($getAdvocated_details)){
                        $getAdvocated_details = $this->getCaveatAdvocates($row['c_diary'],'_a');
                    }
                    $sub_array['advocate_details'] = $getAdvocated_details;
                    $sub_array['sub_details'] = $sub_details;
                    $final_array[] = array_merge($row, $sub_array, $casetype_array,$caveat_diary_matching_array);
                }
            }
        }
        
        return $final_array;
    }
    public function get_SBJ($caveat_no,$is_order_challenged=null,$flag='')
    {
        $caveatSBJ_pending=$this->get_SBJ_details($caveat_no,$is_order_challenged,'','',$flag);
        $caveatSBJ_disposed=$this->get_SBJ_details($caveat_no,$is_order_challenged,'_a','_a',$flag);
        $caveatSBJ_disposed2=$this->get_SBJ_details($caveat_no,$is_order_challenged,'','_a',$flag);
        $caveatSBJ_disposed_others=$this->get_SBJ_details($caveat_no,$is_order_challenged,'_a','',$flag);
        $caveatSBJ=array_merge($caveatSBJ_pending,$caveatSBJ_disposed,$caveatSBJ_disposed_others,$caveatSBJ_disposed2);
        $final_array=array();
        if (!empty($caveatSBJ)){
            foreach ($caveatSBJ as $row) {
                $sub_details = array(); $getAdvocated_details=array(); $caveat_diary_matching_array['caveat_diary_matching'] = array();
                if (!empty($row['diary_no']) && $row['diary_no'] != null && $row['diary_no'] != '') {
                    if($flag=='D')
                        $caveat_diary_matching_array['caveat_diary_matching'] = is_data_from_table('caveat_diary_matching', ['caveat_no' => $row['c_diary'], 'display' => 'Y'], "diary_no as caveat_no,TO_CHAR(link_dt,'DD-MM-YYYY hh:ii:ss') AS link_dt");
                    else
                        $caveat_diary_matching_array['caveat_diary_matching'] = is_data_from_table('caveat_diary_matching', ['diary_no' => $row['diary_no'], 'display' => 'Y'], "caveat_no,TO_CHAR(link_dt,'DD-MM-YYYY hh:ii:ss') AS link_dt");                    $sub_details = $this->get_sub_details($row['diary_no']);
                    if (empty($sub_details)) {
                        $sub_details = $this->get_sub_details($row['diary_no'], '_a', '_a');
                    }

                    $getAdvocated_details = $this->getCaveatAdvocates($row['c_diary'],'');

                    if(empty($getAdvocated_details)){
                        $getAdvocated_details = $this->getCaveatAdvocates($row['c_diary'],'_a');
                    }
                    $sub_array['advocate_details'] = $getAdvocated_details;
                    $sub_array['sub_details'] = $sub_details;
                    $final_array[] = array_merge($row, $sub_array,$caveat_diary_matching_array);
                }
            }
        }
        return $final_array;
    }

    public function get_SCC($caveat_no,$flag='')
    {
        $caveatSCC_pending=$this->get_SCC_details($caveat_no,'','',$flag);
        $caveatSCC_disposed=$this->get_SCC_details($caveat_no,'_a','_a',$flag);
        $caveatSCC_disposed2=$this->get_SCC_details($caveat_no,'','_a',$flag);
        $caveatSCC_disposed_others=$this->get_SCC_details($caveat_no,'_a','',$flag);
        $caveatSCC=array_merge($caveatSCC_pending,$caveatSCC_disposed,$caveatSCC_disposed_others,$caveatSCC_disposed2);
        $final_array=array();
        if (!empty($caveatSCC)){
            foreach ($caveatSCC as $row) {
                $sub_details = array(); $caveat_diary_matching_array['caveat_diary_matching'] = array();$getAdvocated_details=array();
                if (!empty($row['diary_no']) && $row['diary_no'] != null && $row['diary_no'] != '') {
                    if($flag=='D')
                        $caveat_diary_matching_array['caveat_diary_matching'] = is_data_from_table('caveat_diary_matching', ['caveat_no' => $row['c_diary'], 'display' => 'Y'], "diary_no as caveat_no,TO_CHAR(link_dt,'DD-MM-YYYY hh:ii:ss') AS link_dt");
                    else
                       $caveat_diary_matching_array['caveat_diary_matching'] = is_data_from_table('caveat_diary_matching', ['diary_no' => $row['diary_no'], 'display' => 'Y'], "caveat_no,TO_CHAR(link_dt,'DD-MM-YYYY hh:ii:ss') AS link_dt");
                    $sub_details = $this->get_sub_details($row['diary_no']);
                    if (empty($sub_details)) {
                        $sub_details = $this->get_sub_details($row['diary_no'], '_a', '_a');
                    }

                    $getAdvocated_details = $this->getCaveatAdvocates($row['c_diary'],'');

                    if(empty($getAdvocated_details)){
                        $getAdvocated_details = $this->getCaveatAdvocates($row['c_diary'],'_a');
                    }
                    $sub_array['advocate_details'] = $getAdvocated_details;

                    $sub_array['sub_details'] = $sub_details;
                    $final_array[] = array_merge($row, $sub_array,$caveat_diary_matching_array);
                }
            }
        }
        return $final_array;
    }
    public function get_arbitration($caveat_no,$is_order_challenged=null,$flag='')
    {
        $caveat_arbitration_pending=$this->get_arbitration_details($caveat_no,$is_order_challenged,'','',$flag);
        $caveat_arbitration_disposed=$this->get_arbitration_details($caveat_no,$is_order_challenged,'_a','_a',$flag);
        $caveat_arbitration_disposed2=$this->get_arbitration_details($caveat_no,$is_order_challenged,'','_a',$flag);
        $caveat_arbitration_disposed_others=$this->get_arbitration_details($caveat_no,$is_order_challenged,'_a','',$flag);
        $caveat_arbitration=array_merge($caveat_arbitration_pending,$caveat_arbitration_disposed,$caveat_arbitration_disposed_others,$caveat_arbitration_disposed2);
        $final_array=array();
        if (!empty($caveat_arbitration)){
            foreach ($caveat_arbitration as $row) {
                $sub_details = array(); $casetype_array['casetype_details'] = array(); $caveat_diary_matching_array['caveat_diary_matching'] = array();$getAdvocated_details=array();
                if (!empty($row['diary_no']) && $row['diary_no'] != null && $row['diary_no'] != '') {
                    if($flag=='D')
                        $caveat_diary_matching_array['caveat_diary_matching'] = is_data_from_table('caveat_diary_matching', ['caveat_no' => $row['c_diary'], 'display' => 'Y'], "diary_no as caveat_no,TO_CHAR(link_dt,'DD-MM-YYYY hh:ii:ss') AS link_dt");
                    else
                        $caveat_diary_matching_array['caveat_diary_matching'] = is_data_from_table('caveat_diary_matching', ['diary_no' => $row['diary_no'], 'display' => 'Y'], "caveat_no,TO_CHAR(link_dt,'DD-MM-YYYY hh:ii:ss') AS link_dt");                    $sub_details = $this->get_sub_details($row['diary_no']);
                    if (empty($sub_details)) {
                        $sub_details = $this->get_sub_details($row['diary_no'], '_a', '_a');
                    }
                    if (!empty($sub_details)) {
                        if (!empty($sub_details['active_fil_no']) && $sub_details['active_fil_no'] != null && $sub_details['active_fil_no'] != '') {
                            $casecode = substr($sub_details['active_fil_no'], 0, 2);
                            $casetype_array['casetype_details'] = is_data_from_table('master.casetype', ['casecode' => $casecode, 'display' => 'Y'], 'short_description', 'R');
                        }
                    }
                    $getAdvocated_details = $this->getCaveatAdvocates($row['c_diary'],'');

                    if(empty($getAdvocated_details)){
                        $getAdvocated_details = $this->getCaveatAdvocates($row['c_diary'],'_a');
                    }
                    $sub_array['advocate_details'] = $getAdvocated_details;
                    $sub_array['sub_details'] = $sub_details;
                    $final_array[] = array_merge($row, $sub_array, $casetype_array,$caveat_diary_matching_array);
                }
            }
        }

        return $final_array;
    }
    public function get_arbitration_ref_date($caveat_no,$is_order_challenged=null,$flag='')
    {
        $caveat_arbitration_ref_date_pending=$this->get_arbitration_ref_date_details($caveat_no,$is_order_challenged,'','',$flag);
        $caveat_arbitration_ref_date_disposed=$this->get_arbitration_ref_date_details($caveat_no,$is_order_challenged,'_a','_a',$flag);
        $caveat_arbitration_ref_date_disposed2=$this->get_arbitration_ref_date_details($caveat_no,$is_order_challenged,'','_a',$flag);
        $caveat_arbitration_ref_date_disposed_others=$this->get_arbitration_ref_date_details($caveat_no,$is_order_challenged,'_a','',$flag);
        $caveat_arbitration_ref_date=array_merge($caveat_arbitration_ref_date_pending,$caveat_arbitration_ref_date_disposed,$caveat_arbitration_ref_date_disposed_others,$caveat_arbitration_ref_date_disposed2);
        $final_array=array();
        if (!empty($caveat_arbitration_ref_date)){
            foreach ($caveat_arbitration_ref_date as $row) {
                $sub_details = array(); $casetype_array['casetype_details'] = array(); $caveat_diary_matching_array['caveat_diary_matching'] = array();$getAdvocated_details=array();
                if (!empty($row['diary_no']) && $row['diary_no'] != null && $row['diary_no'] != '') {
                    if($flag=='D')
                        $caveat_diary_matching_array['caveat_diary_matching'] = is_data_from_table('caveat_diary_matching', ['caveat_no' => $row['c_diary'], 'display' => 'Y'], "diary_no as caveat_no,TO_CHAR(link_dt,'DD-MM-YYYY hh:ii:ss') AS link_dt");
                    else
                        $caveat_diary_matching_array['caveat_diary_matching'] = is_data_from_table('caveat_diary_matching', ['diary_no' => $row['diary_no'], 'display' => 'Y'], "caveat_no,TO_CHAR(link_dt,'DD-MM-YYYY hh:ii:ss') AS link_dt");
                   /* $sub_details = $this->get_sub_arbitration_ref_date_details($row['diary_no']);
                    if (empty($sub_details)) {
                        $sub_details = $this->get_sub_arbitration_ref_date_details($row['diary_no'], '_a', '_a');
                    }*/
                    $sub_details = $this->get_sub_details($row['diary_no']);
                    if (empty($sub_details)) {
                        $sub_details = $this->get_sub_details($row['diary_no'], '_a', '_a');
                    }
                    if (!empty($sub_details)) {
                        if (!empty($sub_details['active_fil_no']) && $sub_details['active_fil_no'] != null && $sub_details['active_fil_no'] != '') {
                            $casecode = substr($sub_details['active_fil_no'], 0, 2);
                            $casetype_array['casetype_details'] = is_data_from_table('master.casetype', ['casecode' => $casecode, 'display' => 'Y'], 'short_description', 'R');
                        }
                    }
                    $getAdvocated_details = $this->getCaveatAdvocates($row['c_diary'],'');

                    if(empty($getAdvocated_details)){
                        $getAdvocated_details = $this->getCaveatAdvocates($row['c_diary'],'_a');
                    }
                    $sub_array['advocate_details'] = $getAdvocated_details;
                    $sub_array['sub_details'] = $sub_details;
                    $final_array[] = array_merge($row, $sub_array, $casetype_array,$caveat_diary_matching_array);
                }
            }
        }

        return $final_array;
    }
    public function get_arbitration_date($caveat_no,$is_order_challenged=null,$flag='')
    {
        $caveat_arbitration_date_pending=$this->get_arbitration_date_details($caveat_no,$is_order_challenged,'','',$flag);
        $caveat_arbitration_date_disposed=$this->get_arbitration_date_details($caveat_no,$is_order_challenged,'_a','_a',$flag);
        $caveat_arbitration_date_disposed2=$this->get_arbitration_date_details($caveat_no,$is_order_challenged,'','_a',$flag);
        $caveat_arbitration_date_disposed_others=$this->get_arbitration_date_details($caveat_no,$is_order_challenged,'_a','',$flag);
        $caveat_arbitration_date=array_merge($caveat_arbitration_date_pending,$caveat_arbitration_date_disposed,$caveat_arbitration_date_disposed_others,$caveat_arbitration_date_disposed2);
        $final_array=array();
        if (!empty($caveat_arbitration_date)){
            foreach ($caveat_arbitration_date as $row) {
                $sub_details = array(); $casetype_array['casetype_details'] = array(); $caveat_diary_matching_array['caveat_diary_matching'] = array(); $getAdvocated_details=array();
                if (!empty($row['diary_no']) && $row['diary_no'] != null && $row['diary_no'] != '') {
                    if($flag=='D')
                        $caveat_diary_matching_array['caveat_diary_matching'] = is_data_from_table('caveat_diary_matching', ['caveat_no' => $row['c_diary'], 'display' => 'Y'], "diary_no as caveat_no,TO_CHAR(link_dt,'DD-MM-YYYY hh:ii:ss') AS link_dt");
                    else
                        $caveat_diary_matching_array['caveat_diary_matching'] = is_data_from_table('caveat_diary_matching', ['diary_no' => $row['diary_no'], 'display' => 'Y'], "caveat_no,TO_CHAR(link_dt,'DD-MM-YYYY hh:ii:ss') AS link_dt");
                    /* $sub_details = $this->get_sub_arbitration_ref_date_details($row['diary_no']);
                     if (empty($sub_details)) {
                         $sub_details = $this->get_sub_arbitration_ref_date_details($row['diary_no'], '_a', '_a');
                     }*/
                    $sub_details = $this->get_sub_details($row['diary_no']);
                    if (empty($sub_details)) {
                        $sub_details = $this->get_sub_details($row['diary_no'], '_a', '_a');
                    }
                    if (!empty($sub_details)) {
                        if (!empty($sub_details['active_fil_no']) && $sub_details['active_fil_no'] != null && $sub_details['active_fil_no'] != '') {
                            $casecode = substr($sub_details['active_fil_no'], 0, 2);
                            $casetype_array['casetype_details'] = is_data_from_table('master.casetype', ['casecode' => $casecode, 'display' => 'Y'], 'short_description', 'R');
                        }
                    }
                    $getAdvocated_details = $this->getCaveatAdvocates($row['c_diary'],'');

                    if(empty($getAdvocated_details)){
                        $getAdvocated_details = $this->getCaveatAdvocates($row['c_diary'],'_a');
                    }
                    $sub_array['advocate_details'] = $getAdvocated_details;
                    $sub_array['sub_details'] = $sub_details;
                    $final_array[] = array_merge($row, $sub_array, $casetype_array,$caveat_diary_matching_array);
                }
            }
        }

        return $final_array;
    }
    public function get_SBCJ_details($caveat_no,$is_order_challenged=null,$is_archival_table='',$is_archival_table2='',$flag='')
    {
        if($flag=='D'){
            $state_text = "b.l_state";
            $district_text = "b.l_dist";
        }
        else{
            $state_text = "a.l_state";
            $district_text = "a.l_dist";
        }
        $builder = $this->db->table("lowerct$is_archival_table as a");
        $builder->distinct();
        $builder->select('LEFT(CAST(b.caveat_no AS TEXT), -4) AS cn,
  RIGHT(CAST(b.caveat_no AS TEXT), 4) AS cy,b.lct_dec_dt, b.l_dist, b.l_state, b.lct_casetype, b.lct_caseno, b.lct_caseyear, b.caveat_no c_diary, b.ct_code, name');
        $builder->select("CASE 
        WHEN b.ct_code = 3 THEN (
                CASE WHEN b.l_state = 490506 THEN (
                    SELECT concat_ws(' - ', court_name, name) FROM master.state s
                        LEFT JOIN master.delhi_district_court d ON s.state_code = d.state_code
                        AND s.district_code = d.district_code WHERE s.id_no = a.l_dist AND display = 'Y'
                )ELSE(
                    SELECT name FROM master.state s WHERE s.id_no = a.l_dist AND display = 'Y'
                )
            END
        )
        ELSE (
            SELECT  agency_name FROM master.ref_agency_code r
        WHERE  r.cmis_state_id = ".$state_text." AND r.id = ".$district_text." AND is_deleted = 'f'
        )
    END AS agency_name", false);

        $builder->select("CASE 
        WHEN b.ct_code = 4 THEN (
            SELECT skey
        FROM  master.casetype ct
        WHERE ct.display = 'Y'
                AND ct.casecode = b.lct_casetype     
        )
        ELSE (
            SELECT type_sname
        FROM
            master.lc_hc_casetype d
        WHERE
            d.lccasecode = b.lct_casetype
                AND d.display = 'Y'          
        )
    END AS type_sname", false);
        $builder->select("d.fil_no,fil_dt,e.short_description,f.court_name,d.c_status,d.pet_name,d.res_name,a.diary_no,to_char(diary_no_rec_date,'dd-mm-yyyy') as diary_no_rec_date");

        $builder->join("caveat_lowerct$is_archival_table2 b", "a.lct_dec_dt = b.lct_dec_dt and a.l_state = b.l_state and a.lct_caseyear = b.lct_caseyear and a.ct_code = b.ct_code and trim(leading '0' from a.lct_caseno) = trim(leading '0' from b.lct_caseno)");
        $builder->join('master.state c', "b.l_state = c.id_no AND c.display = 'Y'", 'left');
        $builder->join("caveat$is_archival_table2 d", 'd.caveat_no = b.caveat_no','left');
        $builder->join('master.m_from_court f', "f.id=a.ct_code AND f.display = 'Y'", 'left');
        $builder->join('master.casetype e', "e.casecode = b.lct_casetype AND e.display = 'Y'", 'left');
        if ($flag=='D'){$builder->where('a.diary_no', $caveat_no);}else{$builder->where('b.caveat_no', $caveat_no);}

        $builder->where('b.lw_display', 'Y');
        $builder->where('a.lw_display', 'Y');
        if((!empty($is_order_challenged) && $is_order_challenged !=null) && $is_order_challenged =='Y'){
            $builder->where('a.is_order_challenged', 'Y');
            $builder->where('b.lct_dec_dt is not', null);
        }
        if ($flag!='D') {
            $builder->orderBy('b.caveat_no');
        }else{
            $builder->orderBy('cy,cn');
        }
        $query = $builder->get();
        /*$query1 = $this->db->getLastQuery();
        echo (string)"DSFDS". $query1.'<br>';*/

        $result = $query->getResultArray();
        return $result;
    }

    public function get_SBC_details($caveat_no,$is_order_challenged=null,$is_archival_table='',$is_archival_table2='',$flag='')
    {
        if($flag=='D'){
            $state_text = "b.l_state";
            $district_text = "b.l_dist";
        }
        else{
            $state_text = "a.l_state";
            $district_text = "a.l_dist";
        }

        $builder = $this->db->table("lowerct$is_archival_table as a");
        $builder->distinct();
        $builder->select('LEFT(CAST(b.caveat_no AS TEXT), -4) AS cn,
  RIGHT(CAST(b.caveat_no AS TEXT), 4) AS cy,b.lct_dec_dt, b.l_dist, b.l_state, b.lct_casetype, b.lct_caseno, b.lct_caseyear, b.caveat_no c_diary, b.ct_code, name');
        $builder->select("CASE 
        WHEN b.ct_code = 3 THEN (
                CASE WHEN b.l_state = 490506 THEN (
                    SELECT concat_ws(' - ', court_name, name) FROM master.state s
                        LEFT JOIN master.delhi_district_court d ON s.state_code = d.state_code
                        AND s.district_code = d.district_code WHERE s.id_no = a.l_dist AND display = 'Y'
                )ELSE(
                    SELECT name FROM master.state s WHERE s.id_no = a.l_dist AND display = 'Y'
                )
            END
        )
        ELSE (
            SELECT  agency_name FROM master.ref_agency_code r
       WHERE  r.cmis_state_id = ".$state_text." AND r.id = ".$district_text." AND is_deleted = 'f'
        )
    END AS agency_name", false);

        $builder->select("CASE 
        WHEN b.ct_code = 4 THEN (
            SELECT skey
        FROM  master.casetype ct
        WHERE ct.display = 'Y'
                AND ct.casecode = b.lct_casetype     
        )
        ELSE (
            SELECT type_sname
        FROM
            master.lc_hc_casetype d
        WHERE
            d.lccasecode = b.lct_casetype
                AND d.display = 'Y'          
        )
    END AS type_sname", false);
        $builder->select('d.fil_no,fil_dt,e.short_description,f.court_name,d.c_status,d.pet_name,d.res_name,a.diary_no,d.diary_no_rec_date,');

        $builder->join("caveat_lowerct$is_archival_table2 b", "a.l_state = b.l_state and a.lct_caseyear = b.lct_caseyear and a.ct_code = b.ct_code and trim(leading '0' from a.lct_caseno) = trim(leading '0' from b.lct_caseno) and trim(leading '0' from b.lct_caseno) !='0'");
        $builder->join('master.state c', "b.l_state = c.id_no AND c.display = 'Y'", 'left');
        $builder->join("caveat$is_archival_table2 d", 'd.caveat_no = b.caveat_no','left');
        $builder->join('master.m_from_court f', "f.id=a.ct_code AND f.display = 'Y'", 'left');
        $builder->join('master.casetype e', "e.casecode = b.lct_casetype AND e.display = 'Y'", 'left');
        if ($flag=='D'){$builder->where('a.diary_no', $caveat_no);}else{$builder->where('b.caveat_no', $caveat_no);}
        $builder->where('b.lw_display', 'Y');
        $builder->where('a.lw_display', 'Y');
        if((!empty($is_order_challenged) && $is_order_challenged !=null) && $is_order_challenged =='Y'){
            $builder->where('a.is_order_challenged', 'Y');
            $builder->where('b.lct_dec_dt is not', null);
        }
        if ($flag!='D') {
            $builder->orderBy('b.caveat_no');
        }else{
            $builder->orderBy('cy,cn');
        }
        $query = $builder->get();
       /* $query = $this->db->getLastQuery();
        echo (string)"DSFDS". $query.'<br>';
        exit();*/
        $result = $query->getResultArray();
        return $result;

    }

    public function get_SBJ_details($caveat_no,$is_order_challenged=null,$is_archival_table='',$is_archival_table2='',$flag='')
    { //recheck pending reason recoard not matched
        $builder = $this->db->table("lowerct$is_archival_table as a");
        $builder->distinct();
        if($flag=='D'){
            $state_text = "b.l_state";
            $district_text = "b.l_dist";
            $builder->select('LEFT(CAST(b.caveat_no AS TEXT), -4) AS cn,
  RIGHT(CAST(b.caveat_no AS TEXT), 4) AS cy,b.lct_dec_dt, b.l_dist, b.l_state, b.lct_casetype, b.lct_caseno, b.lct_caseyear, b.caveat_no c_diary, b.ct_code, name');
        }
        else{
            $state_text = "a.l_state";
            $district_text = "a.l_dist";
            $builder->select('LEFT(CAST(b.caveat_no AS TEXT), -4) AS cn,
  RIGHT(CAST(b.caveat_no AS TEXT), 4) AS cy,a.lct_dec_dt, a.l_dist, a.l_state, a.lct_casetype, a.lct_caseno, a.lct_caseyear, b.caveat_no c_diary, b.ct_code, name');
        }

        $builder->select("CASE 
        WHEN b.ct_code = 3 THEN (
                CASE WHEN b.l_state = 490506 THEN (
                    SELECT concat_ws(' - ', court_name, name) FROM master.state s
                        LEFT JOIN master.delhi_district_court d ON s.state_code = d.state_code
                        AND s.district_code = d.district_code WHERE s.id_no = a.l_dist AND display = 'Y'
                )ELSE(
                    SELECT name FROM master.state s WHERE s.id_no = a.l_dist AND display = 'Y'
                )
            END
        )
        ELSE (
            SELECT  agency_name FROM master.ref_agency_code r
        WHERE  r.cmis_state_id = ".$state_text." AND r.id = ".$district_text." AND is_deleted = 'f'
        )
    END AS agency_name", false);

        $builder->select("CASE 
        WHEN b.ct_code = 4 THEN (
            SELECT skey
        FROM  master.casetype ct
        WHERE ct.display = 'Y'
                AND ct.casecode = a.lct_casetype     
        )
        ELSE (
            SELECT type_sname
        FROM
            master.lc_hc_casetype lhc
        WHERE
            lhc.lccasecode = b.lct_casetype
                AND lhc.display = 'Y'          
        )
    END AS type_sname", false);
        $builder->select("d.fil_no,TO_CHAR(fil_dt, 'YYYY') as fil_dt,e.short_description,f.court_name,d.c_status,d.pet_name,d.res_name,a.diary_no,d.diary_no_rec_date,");

        $builder->join("caveat_lowerct$is_archival_table2 b", "a.lct_dec_dt = b.lct_dec_dt and a.l_state = b.l_state and a.ct_code = b.ct_code and a.lct_dec_dt is not null");
        $builder->join('master.state c', "b.l_state = c.id_no AND c.display = 'Y'", 'left');
        $builder->join("caveat$is_archival_table2 d", 'd.caveat_no = b.caveat_no','left');
        $builder->join('master.m_from_court f', "f.id=a.ct_code AND f.display = 'Y'", 'left');
        $builder->join('master.casetype e', "e.casecode = b.lct_casetype AND e.display = 'Y'", 'left');

        if ($flag=='D'){$builder->where('a.diary_no', $caveat_no);}else{$builder->where('b.caveat_no', $caveat_no);}
        $builder->where('b.lw_display', 'Y');
        $builder->where('a.lw_display', 'Y');
        if((!empty($is_order_challenged) && $is_order_challenged !=null) && $is_order_challenged =='Y'){
            $builder->where('a.is_order_challenged', 'Y');
            $builder->where('b.lct_dec_dt is not', null);
        }
        if ($flag!='D') {
            $builder->orderBy('b.caveat_no');
        }else{
            $builder->orderBy('cy,cn');
        }
        $query = $builder->get();
        /*$query = $this->db->getLastQuery();
        echo (string)"DSFDS". $query.'<br>';
        exit();
        */
        $result = $query->getResultArray();
        return $result;
    }
    public function get_SCC_details($caveat_no,$is_archival_table='',$is_archival_table2='',$flag='')
    {
        $builder = $this->db->table("main$is_archival_table as p");
        $builder->distinct();
        if($flag=='D')
            $builder->select('m.caveat_no as c_diary,name,m.pet_name,m.res_name,p.diary_no,m.ref_agency_state_id,m.pet_name caveat_pet_name,m.res_name caveat_res_name');
        else
        $builder->select('m.caveat_no as c_diary,name,p.pet_name,p.res_name,p.diary_no,m.ref_agency_state_id,m.pet_name caveat_pet_name,m.res_name caveat_res_name');
        $builder->join("caveat$is_archival_table2 m", "m.ref_agency_state_id=p.ref_agency_state_id and trim(LOWER((m.pet_name))) LIKE   concat('%',trim(LOWER((p.res_name))),'%') and trim(LOWER((m.res_name))) LIKE concat('%',trim(LOWER((p.pet_name))),'%')");
        $builder->join('master.state c', "m.ref_agency_state_id = c.id_no AND c.display = 'Y'", 'left');
        if ($flag=='D'){$builder->where('p.diary_no', $caveat_no);}else{$builder->where('m.caveat_no', $caveat_no);}
        if ($flag!='D') {
            $builder->orderBy('m.caveat_no');
        }
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }
    public function get_arbitration_details($caveat_no,$is_order_challenged=null,$is_archival_table='',$is_archival_table2='',$flag='')
    {
        if($flag=='D'){
            $state_text = "b.l_state";
            $district_text = "b.l_dist";
        }
        else{
            $state_text = "a.l_state";
            $district_text = "a.l_dist";
        }
        $builder = $this->db->table("lowerct$is_archival_table as a");
        $builder->distinct();
        $builder->select('LEFT(CAST(b.caveat_no AS TEXT), -4) AS cn,
  RIGHT(CAST(b.caveat_no AS TEXT), 4) AS cy,b.lct_dec_dt, b.l_dist, b.l_state, b.lct_casetype, b.lct_caseno, b.lct_caseyear, b.caveat_no c_diary, b.ct_code, name');
        $builder->select("CASE 
        WHEN b.ct_code = 3 THEN (
                CASE WHEN b.l_state = 490506 THEN (
                    SELECT concat_ws(' - ', court_name, name) FROM master.state s
                        LEFT JOIN master.delhi_district_court d ON s.state_code = d.state_code
                        AND s.district_code = d.district_code WHERE s.id_no = a.l_dist AND display = 'Y'
                )ELSE(
                    SELECT name FROM master.state s WHERE s.id_no = a.l_dist AND display = 'Y'
                )
            END
        )
        ELSE (
            SELECT  agency_name FROM master.ref_agency_code r
        WHERE  r.cmis_state_id = ".$state_text." AND r.id = ".$district_text." AND is_deleted = 'f'
        )
    END AS agency_name", false);

        $builder->select("CASE 
        WHEN b.ct_code = 4 THEN (
            SELECT skey
        FROM  master.casetype ct
        WHERE ct.display = 'Y'
                AND ct.casecode = b.lct_casetype     
        )
        ELSE (
            SELECT type_sname
        FROM
            master.lc_hc_casetype d
        WHERE
            d.lccasecode = b.lct_casetype
                AND d.display = 'Y'          
        )
    END AS type_sname", false);
        $builder->select("d.fil_no,fil_dt,e.short_description,d.diary_no_rec_date,f.court_name,d.c_status,d.pet_name,d.res_name,a.diary_no,STRING_AGG(CONCAT(SUBSTRING(cast(first_name as text),' ',cast(sur_name as text))),',' ) judgename", false);

        $builder->join("caveat_lowerct$is_archival_table2 b", "a.lct_dec_dt = b.lct_dec_dt and a.l_state = b.l_state and a.lct_caseyear = b.lct_caseyear and a.ct_code = b.ct_code and trim(leading '0' from a.lct_caseno) = trim(leading '0' from b.lct_caseno)");


        $builder->join("lowerct_judges$is_archival_table as lj", "a.lower_court_id=lj.lowerct_id AND lj.lct_display = 'Y'");
        $builder->join('caveat_lowerct_judges cj', "b.lower_court_id=cj.lowerct_id and lj.judge_id=cj.judge_id AND cj.lct_display = 'Y'");
        $builder->join('master.org_lower_court_judges j', "lj.judge_id=j.id AND j.is_deleted = 'f'", 'left');

        $builder->join('master.casetype e', "e.casecode = b.lct_casetype AND e.display = 'Y'", 'left');
        $builder->join('master.state c', "b.l_state = c.id_no AND c.display = 'Y'", 'left');
        $builder->join("caveat$is_archival_table2 d", 'd.caveat_no = b.caveat_no','left');
        $builder->join('master.m_from_court f', "f.id=a.ct_code AND f.display = 'Y'", 'left');
        if ($flag=='D'){$builder->where('a.diary_no', $caveat_no);}else{$builder->where('b.caveat_no', $caveat_no);}
        $builder->where('b.lw_display', 'Y');
        $builder->where('a.lw_display', 'Y');
        if((!empty($is_order_challenged) && $is_order_challenged !=null) && $is_order_challenged =='Y'){
            $builder->where('a.is_order_challenged', 'Y');
            $builder->where('b.lct_dec_dt is not', null);
        }
        $builder->groupBy('a.diary_no,b.lct_dec_dt, b.l_dist, b.l_state, b.lct_casetype, b.lct_caseno, b.lct_caseyear,c_diary, b.ct_code, name,
        a.l_dist,a.l_state,d.fil_no,d.fil_dt,e.short_description,f.court_name,d.c_status,d.pet_name,d.res_name,d.diary_no_rec_date,cy,cn');
        if ($flag!='D') {
            $builder->orderBy('b.caveat_no');
        }else{
            $builder->orderBy('cy,cn');
        }
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;


    }

    public function get_arbitration_ref_date_details($caveat_no,$is_order_challenged=null,$is_archival_table='',$is_archival_table2='',$flag='')
    {
        if($flag=='D'){
            $state_text = "b.l_state";
            $district_text = "b.l_dist";
        }
        else{
            $state_text = "a.l_state";
            $district_text = "a.l_dist";
        }
        $builder = $this->db->table("lowerct$is_archival_table as a");
        $builder->distinct();
        $builder->select('LEFT(CAST(b.caveat_no AS TEXT), -4) AS cn,
  RIGHT(CAST(b.caveat_no AS TEXT), 4) AS cy,b.lct_dec_dt, b.l_dist, b.l_state, b.lct_casetype, b.lct_caseno, b.lct_caseyear, b.caveat_no c_diary, b.ct_code, name,d.diary_no_rec_date');
        $builder->select("CASE 
        WHEN b.ct_code = 3 THEN (
                CASE WHEN b.l_state = 490506 THEN (
                    SELECT concat_ws(' - ', court_name, name) FROM master.state s
                        LEFT JOIN master.delhi_district_court d ON s.state_code = d.state_code
                        AND s.district_code = d.district_code WHERE s.id_no = a.l_dist AND display = 'Y'
                )ELSE(
                    SELECT name FROM master.state s WHERE s.id_no = a.l_dist AND display = 'Y'
                )
            END
        )
        ELSE (
            SELECT  agency_name FROM master.ref_agency_code r
        WHERE  r.cmis_state_id = ".$state_text." AND r.id = ".$district_text." AND is_deleted = 'f'
        )
    END AS agency_name", false);

        $builder->select("CASE 
        WHEN b.ct_code = 4 THEN (
            SELECT skey
        FROM  master.casetype ct
        WHERE ct.display = 'Y'
                AND ct.casecode = b.lct_casetype     
        )
        ELSE (
            SELECT type_sname
        FROM
            master.lc_hc_casetype d
        WHERE
            d.lccasecode = b.lct_casetype
                AND d.display = 'Y'          
        )
    END AS type_sname", false);
        $builder->select("d.fil_no,fil_dt,e.short_description,f.court_name,d.c_status,d.pet_name,d.res_name,a.diary_no,STRING_AGG(CONCAT(SUBSTRING(cast(first_name as text),' ',cast(sur_name as text))),',' ) judgename", false);

        $builder->join("caveat_lowerct$is_archival_table2 b", "a.lct_dec_dt = b.lct_dec_dt and a.l_state = b.l_state and a.lct_caseyear = b.lct_caseyear and a.ct_code = b.ct_code and trim(leading '0' from a.lct_caseno) = trim(leading '0' from b.lct_caseno)");


        $builder->join("lowerct_judges$is_archival_table lj", "a.lower_court_id=lj.lowerct_id AND lj.lct_display = 'Y'", 'left');
        $builder->join('caveat_lowerct_judges cj', "b.lower_court_id=cj.lowerct_id and lj.judge_id=cj.judge_id AND cj.lct_display = 'Y'", 'left');
        $builder->join('master.org_lower_court_judges j', "lj.judge_id=j.id AND j.is_deleted = 'f'", 'left');

        $builder->join('master.casetype e', "e.casecode = b.lct_casetype AND e.display = 'Y'", 'left');
        $builder->join('master.state c', "b.l_state = c.id_no AND c.display = 'Y'", 'left');
        $builder->join("caveat$is_archival_table2 d", 'd.caveat_no = b.caveat_no','left');
        $builder->join('master.m_from_court f', "f.id=a.ct_code AND f.display = 'Y'", 'left');

        if ($flag=='D'){$builder->where('a.diary_no', $caveat_no);}else{$builder->where('b.caveat_no', $caveat_no);}
        $builder->where('b.lw_display', 'Y');
        $builder->where('a.lw_display', 'Y');
        if((!empty($is_order_challenged) && $is_order_challenged !=null) && $is_order_challenged =='Y'){
            $builder->where('a.is_order_challenged', 'Y');
            $builder->where('b.lct_dec_dt is not', null);
        }
        $builder->groupBy('a.diary_no,b.lct_dec_dt, b.l_dist, b.l_state, b.lct_casetype, b.lct_caseno, b.lct_caseyear,c_diary, b.ct_code, name,
        a.l_dist,a.l_state,d.fil_no,d.fil_dt,e.short_description,f.court_name,d.c_status,d.pet_name,d.res_name,d.diary_no_rec_date,cy,cn');
        if ($flag!='D') {
            $builder->orderBy('b.caveat_no');
        }else{
            $builder->orderBy('cy,cn');
        }
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;


    }
    public function get_arbitration_date_details($caveat_no,$is_order_challenged=null,$is_archival_table='',$is_archival_table2='',$flag='')
    {
        if($flag=='D'){
            $state_text = "b.l_state";
            $district_text = "b.l_dist";
        }
        else{
            $state_text = "a.l_state";
            $district_text = "a.l_dist";
        }
        $builder = $this->db->table("lowerct$is_archival_table as a");
        $builder->distinct();
        $builder->select('LEFT(CAST(b.caveat_no AS TEXT), -4) AS cn,
  RIGHT(CAST(b.caveat_no AS TEXT), 4) AS cy,b.lct_dec_dt, b.l_dist, b.l_state, b.lct_casetype, b.lct_caseno, b.lct_caseyear, b.caveat_no c_diary, b.ct_code, name,d.diary_no_rec_date');
        $builder->select("CASE 
        WHEN b.ct_code = 3 THEN (
                CASE WHEN b.l_state = 490506 THEN (
                    SELECT concat_ws(' - ', court_name, name) FROM master.state s
                        LEFT JOIN master.delhi_district_court d ON s.state_code = d.state_code
                        AND s.district_code = d.district_code WHERE s.id_no = a.l_dist AND display = 'Y'
                )ELSE(
                    SELECT name FROM master.state s WHERE s.id_no = a.l_dist AND display = 'Y'
                )
            END
        )
        ELSE (
            SELECT  agency_name FROM master.ref_agency_code r
        WHERE  r.cmis_state_id = ".$state_text." AND r.id = ".$district_text." AND is_deleted = 'f'
        )
    END AS agency_name", false);

        $builder->select("CASE 
        WHEN b.ct_code = 4 THEN (
            SELECT skey
        FROM  master.casetype ct
        WHERE ct.display = 'Y'
                AND ct.casecode = b.lct_casetype     
        )
        ELSE (
            SELECT type_sname
        FROM
            master.lc_hc_casetype d
        WHERE
            d.lccasecode = b.lct_casetype
                AND d.display = 'Y'          
        )
    END AS type_sname", false);
        $builder->select("d.fil_no,fil_dt,e.short_description,f.court_name,d.c_status,d.pet_name,d.res_name,a.diary_no,d.diary_no_rec_date,
      STRING_AGG(CONCAT(cast(first_name as text),' ',cast(sur_name as text)),',' ) as judgename
        ", false);
        $builder->join("caveat_lowerct$is_archival_table2 b", "a.lct_dec_dt = b.lct_dec_dt and a.l_state = b.l_state and a.ct_code = b.ct_code");

        $builder->join("lowerct_judges$is_archival_table as lj", "a.lower_court_id=lj.lowerct_id AND lj.lct_display = 'Y'", 'left');
        $builder->join('caveat_lowerct_judges cj', "b.lower_court_id=cj.lowerct_id and lj.judge_id=cj.judge_id AND cj.lct_display = 'Y'", 'left');
        $builder->join('master.org_lower_court_judges j', "lj.judge_id=j.id AND j.is_deleted = 'f'", 'left');

        $builder->join('master.casetype e', "e.casecode = b.lct_casetype AND e.display = 'Y'", 'left');
        $builder->join('master.state c', "b.l_state = c.id_no AND c.display = 'Y'", 'left');
        $builder->join("caveat$is_archival_table2 d", 'd.caveat_no = b.caveat_no','left');
        $builder->join('master.m_from_court f', "f.id=a.ct_code AND f.display = 'Y'", 'left');

        if ($flag=='D'){$builder->where('a.diary_no', $caveat_no);}else{$builder->where('b.caveat_no', $caveat_no);}
        $builder->where('b.lw_display', 'Y');
        $builder->where('a.lw_display', 'Y');
        if((!empty($is_order_challenged) && $is_order_challenged !=null) && $is_order_challenged =='Y'){
            $builder->where('a.is_order_challenged', 'Y');
            $builder->where('b.lct_dec_dt is not', null);
        }
        $builder->groupBy('a.diary_no,b.lct_dec_dt, b.l_dist, b.l_state, b.lct_casetype, b.lct_caseno, b.lct_caseyear,c_diary, b.ct_code, name,
        a.l_dist,a.l_state,d.fil_no,d.fil_dt,e.short_description,f.court_name,d.c_status,d.pet_name,d.res_name,d.diary_no_rec_date,cy,cn');
    if($flag=='D'){
        $builder->orderBy('cy,cn');
    }
        $query = $builder->get();
        //echo $this->db->getLastQuery().'<br><br>';
        $result = $query->getResultArray();
        return $result;


    }
    /*start sub array*/
    public function get_sub_details($diary_no,$is_archival_table='',$is_archival_table2='')
    {
        $builder = $this->db->table("main$is_archival_table as m");
        $builder->distinct();
        $builder->select("pet_name,res_name,to_char(m.diary_no_rec_date,'dd-mm-yyyy') as diary_no_rec_date,active_fil_no,to_char(m.active_fil_dt,'yyyy') as active_fil_dt,
        c_status,dacode,section_id,us.section_name as da_section,uss.section_name as sectionname, r_head,m.diary_no");

        $builder->join('master.users u',  'm.dacode=u.usercode', 'left');
        $builder->join('master.usersection us',  'u.section =us.id', 'left');
        $builder->join('master.usersection uss',  'm.section_id=uss.id', 'left');
        $builder->join("case_remarks_multiple$is_archival_table2 r", 'm.diary_no= cast(r.diary_no as BIGINT ) and r_head in(3,182,183,184)','left');

        $builder->where('m.diary_no', $diary_no);
        $query = $builder->get(1);
        $result = $query->getRowArray();
        return $result;

    }
    public function get_sub_arbitration_ref_date_details($diary_no,$is_archival_table='',$is_archival_table2='')
    {
        $builder = $this->db->table("main$is_archival_table");
        $builder->distinct();
        $builder->select("pet_name,res_name,to_char(diary_no_rec_date,'dd-mm-yyyy') as diary_no_rec_date,active_fil_no,to_char(active_fil_dt,'yyyy') as active_fil_dt,diary_no");
        $builder->where('diary_no', $diary_no);
        //$queryString = $builder->getCompiledSelect(); echo $queryString;exit();
        $query = $builder->get(1);
        $result = $query->getRowArray();
        return $result;

    }
    /*end sub array*/

    public function check_caveat_same_party($caveat_no,$diary_no,$is_archival_table='')
    {
        $builder = $this->db->table("caveat$is_archival_table");
       // $builder->distinct();
        $builder->select("pet_name");
        $builder->where("caveat_no=$caveat_no and trim(pet_name)=(select trim(pet_name) from caveat where caveat_no=$diary_no)  and pet_adv_id!=(select pet_adv_id from caveat where caveat_no=$diary_no)");
        //$queryString = $builder->getCompiledSelect(); echo $queryString;exit();
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;

    }

    public function getCaveatAdvocates($caveat_no,$is_archival_table=''){
        $builder = $this->db->table("caveat_advocate".$is_archival_table." as a");
        $builder->select('name,aor_code');
        $builder->join('master.bar b','a.advocate_id=b.bar_id');
        $builder->where('caveat_no', $caveat_no);
        $builder->where('display', 'Y');
        $adv_details = array();
        $query = $builder->get();
        //echo $this->db->getLastQuery();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            if(is_array($result)){
                $adv_name = '';
                foreach ($result as $row2){
                    if ($adv_name == '')
                        $adv_name = $row2['aor_code'] . '-' . $row2['name'];
                    else
                        $adv_name = $adv_name . ',' . $row2['aor_code'] . '-' . $row2['name'];
                }
            }

            $adv_details[$caveat_no]=$adv_name;
            return $adv_details;
        } else {
            // insert keyword
            return '';
        }
    }
}
