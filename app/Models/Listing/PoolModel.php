<?php

namespace App\Models\Listing;

use CodeIgniter\Model;

class PoolModel extends Model
{
   
    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
    }


    public function f_ia(){
        $builder = $this->db->table('master.docmaster');
        $builder->select('doccode1, docdesc');
        $builder->where('doccode', '8');
        $builder->where('display', 'Y');
        $builder->orderBy('docdesc');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function f_act(){
        $builder1 = $this->db->table('master.act_master');
        $builder1->select('id, act_name');
        $builder1->where('display', 'Y');
        $builder1->where('act_name !=', '');
        $builder1->where('act_name IS NOT NULL');
        $builder1->orderBy('act_name');
        $query1 = $builder1->get();
        return $query1->getResultArray();
    }

    public function f_keyword(){
        $builder2 = $this->db->table('master.ref_keyword');
        $builder2->select('id, keyword_description');
        $builder2->where('is_deleted', 'f');
        $builder2->orderBy('keyword_description');
        $query2 = $builder2->get();
        return $query2->getResultArray();
    }

    public function getCaseDetails($diary_no){
        $builder = $this->db->table('main m');
        $builder->select('reg_no_display, m.diary_no, diary_no_rec_date, fil_dt, active_fil_dt, fil_dt_fh, mf_active, c_status, pet_name, res_name, pno, rno, active_casetype_id');
        $builder->where('m.diary_no', $diary_no);
        $builder->where('m.c_status', 'P');
        $query = $builder->get();
        return $query->getRowArray();
         
    }

    public function getMainheadInfo($diary_no){
        $query = $this->db->table('heardt')
        ->select('mainhead')
        ->where('diary_no', $diary_no)
        ->where('mainhead', 'F')
        ->get();
        return $query->getRowArray();
    }

    public function getStageNameInfo($diary_no){
        $query = $this->db->table('heardt h')
        ->select('s.stagename, h.mainhead')
        ->join('master.subheading s', 'h.subhead = s.stagecode', 'inner')
        ->where('h.diary_no', $diary_no)
        ->where('h.mainhead', 'M')
        ->get();
        return $query->getRowArray();
    }

    public function getCategoryInfo($diary_no){
        $query= $this->db->table('mul_category mc')
        ->select('s.sub_name1, s.sub_name2, s.sub_name3, s.sub_name4')
        ->join('master.submaster s', 's.id = mc.submaster_id', 'inner')
        ->where('mc.diary_no', $diary_no)
        ->where('mc.display', 'Y')
        ->get();
        return $query->getResultArray();
    }

    public function isAlreadyInPool($diary_no){
        $builder111 = $this->db->table('vacation_registrar_pool');
        $builder111->where('diary_no', $diary_no);
        $query = $builder111->get();
        return $query->getRowArray();
    }

    public function getPoolCaseDetails($diary_numbers){
        $sql = "SELECT DISTINCT 
                    tentative_section(m.diary_no) AS section_name,             
                    h.diary_no,   
                    
                    m.lastorder, 
                    m.active_fil_no, 
                    m.active_reg_year, 
                    m.casetype_id,  
                    m.active_casetype_id,  
                    m.ref_agency_state_id,
                    m.reg_no_display, 
                    EXTRACT(YEAR FROM m.fil_dt) AS fil_year, 
                    m.fil_no, 
                    m.conn_key AS main_key, 
                    m.fil_dt, 
                    m.fil_no_fh, 
                    m.reg_year_fh AS fil_year_f,
                    m.mf_active, 
                    m.pet_name,  
                    m.res_name, 
                    pno, 
                    rno, 
                    m.diary_no_rec_date, 
                    l.purpose, 
                    s.category_sc_old,
                    CAST(RIGHT(CAST(m.diary_no AS TEXT), 4) AS INTEGER) AS diary_no_suffix,
                    CAST(LEFT(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER) AS diary_no_prefix
                FROM main m
                INNER JOIN heardt h ON h.diary_no = m.diary_no
                INNER JOIN master.listing_purpose l ON l.code = h.listorder
                LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                LEFT JOIN mul_category mc ON mc.diary_no = m.diary_no AND mc.display = 'Y' 
                LEFT JOIN master.submaster s ON mc.submaster_id = s.id AND s.flag = 's' AND s.display = 'Y' AND (s.category_sc_old IS NOT NULL AND s.category_sc_old != '')
                LEFT JOIN case_info ci ON ci.diary_no = h.diary_no AND ci.display = 'Y'
                WHERE 
                    m.diary_no IN ($diary_numbers)
                GROUP BY 
                    m.diary_no, 
                    h.diary_no, 
                    
                
                    m.lastorder, 
                    m.active_fil_no, 
                    m.active_reg_year, 
                    m.casetype_id,  
                    m.active_casetype_id,  
                    m.ref_agency_state_id,
                    m.reg_no_display, 
                    m.fil_no, 
                    m.conn_key, 
                    m.fil_dt, 
                    m.fil_no_fh, 
                    m.reg_year_fh, 
                    m.mf_active, 
                    m.pet_name,  
                    m.res_name, 
                    pno, 
                    rno, 
                    m.diary_no_rec_date, 
                    l.purpose, 
                    s.category_sc_old,
                    diary_no_suffix, 
                    diary_no_prefix
                ORDER BY 
                    diary_no_suffix ASC, 
                    diary_no_prefix ASC
                ";
 
            $query = $this->db->query($sql);
			$results = $query->getResultArray();
            $radvname = $padvname = '';
            foreach ($results as $index => $result) {
                $results[$index]['sno'] = $index + 1; // Serial number starting from 1
               
                
                $advocate = $this->get_advocate_bar($result['diary_no']);
                if(!empty($advocate)) {
                    $radvname = ($advocate["r_n"]);
                    $padvname = ($advocate["p_n"]);
                    $impldname = ($advocate["i_n"]);
                    $intervenorname = ($advocate["intervenor"]);
                }
                if ($result['pno'] == 2) {
                    $pet_name = $result['pet_name'] . " AND ANR.";
                } else if ($result['pno'] > 2) {
                    $pet_name = $result['pet_name'] . " AND ORS.";
                } else {
                    $pet_name = $result['pet_name'];
                }
                if ($result['rno'] == 2) {
                    $res_name = $result['res_name'] . " AND ANR.";
                } else if ($result['rno'] > 2) {
                    $res_name = $result['res_name'] . " AND ORS.";
                } else {
                    $res_name = $result['res_name'];
                }
    
                $results[$index]['radvname'] = !empty($radvname) ? str_replace(",", ", ", trim($radvname, ",")) : '';
                $results[$index]['padvname'] = !empty($padvname) ? str_replace(",", ", ", trim($padvname, ",")) : '';
                $results[$index]['impldname'] = !empty($impldname) ? str_replace(",", ", ", trim($impldname, ",")) : '';
                $results[$index]['intervenorname'] = !empty($intervenorname) ? str_replace(",", ", ", trim($intervenorname, ",")) : '';
                $results[$index]['get_pet_name'] = $pet_name;
                $results[$index]['res_name'] = $res_name;
                //pr($results);
                if($result['diary_no'] == $result['main_key']) {
                    $connected_cases = $this->get_connected_cases($result['diary_no']);
                    $results[$index]['connected_cases'] = $connected_cases;
                    foreach($connected_cases as $connected_case_index => $connected_case) {
                        $advocate_by_old_cases = $this->get_advocate_bar($connected_case['diary_no']);
                        $results[$index]['connected_cases'][$connected_case_index]['advocate_by_old_cases'] =  $advocate_by_old_cases;
                    }
                }
            }
            
            return $results;
    }

    public function get_advocate_bar($diary_no)
    {
        $subquery = $this->db->table('advocate a')
        ->select("
            a.diary_no, 
            b.name, 
            STRING_AGG(COALESCE(a.adv, ''), '' ORDER BY CASE WHEN pet_res = 'I' THEN 99 ELSE 0 END ASC, 'adv_type DESC', pet_res_no ASC) AS grp_adv,
            a.pet_res, 
            a.adv_type, 
            a.pet_res_no
        ", false)
        ->join('master.bar b', 'a.advocate_id = b.bar_id AND b.isdead != \'Y\'', 'left')
        ->where('a.diary_no', $diary_no)
        ->where('a.display', 'Y')
        ->groupBy('a.diary_no, b.name, a.pet_res, a.adv_type, a.pet_res_no')
        ->orderBy("(CASE WHEN a.pet_res = 'I' THEN 99 ELSE 0 END) ASC")
        ->orderBy('(a.adv_type) DESC')
        ->orderBy('(a.pet_res_no) ASC');
        $builder = $this->db->table('(' . $subquery->getCompiledSelect() . ') a', false);
        $builder->select("
            a.diary_no, 
            STRING_AGG(a.name || CASE WHEN a.pet_res = 'R' THEN a.grp_adv END, ', ' ORDER BY a.adv_type DESC, a.pet_res_no ASC) AS r_n,
            STRING_AGG(a.name || CASE WHEN a.pet_res = 'P' THEN a.grp_adv END, ', ' ORDER BY a.adv_type DESC, a.pet_res_no ASC) AS p_n,
            STRING_AGG(a.name || CASE WHEN a.pet_res = 'I' THEN a.grp_adv END, ', ' ORDER BY a.adv_type DESC, a.pet_res_no ASC) AS i_n,
            STRING_AGG(a.name || CASE WHEN a.pet_res = 'N' THEN a.grp_adv END, ', ' ORDER BY a.adv_type DESC, a.pet_res_no ASC) AS intervenor
        ", false);
        $builder->groupBy('a.diary_no');      
        $query = $builder->get();
        $results = $query->getRowArray();
        return $results;
    }

    public function get_connected_cases($diary_no) {
        $sql2 = "SELECT tentative_section(j.diary_no) AS section_name, j.* 
                    FROM (
                        SELECT 
                            h.*, 
                            active_fil_no,
                            m.active_reg_year,
                            m.casetype_id,
                            m.active_casetype_id,
                            m.ref_agency_state_id,
                            m.reg_no_display,
                            EXTRACT(YEAR FROM m.fil_dt) AS fil_year,
                            m.fil_no,
                            m.conn_key AS main_key,
                            m.fil_dt,
                            m.fil_no_fh,
                            m.reg_year_fh AS fil_year_f,
                            m.mf_active,
                            m.pet_name,
                            m.res_name,
                            pno,
                            rno,
                            m.diary_no_rec_date
                        FROM (
                            SELECT 
                                c.diary_no AS conc_diary_no,
                                m.conn_key,
                                h.next_dt,
                                h.mainhead,
                                h.subhead,
                                h.clno,
                                h.brd_slno,
                                h.roster_id,
                                h.judges,
                                h.coram,
                                h.board_type,
                                h.usercode,
                                h.ent_dt,
                                h.module_id,
                                h.mainhead_n,
                                h.subhead_n,
                                h.main_supp_flag,
                                h.listorder,
                                h.tentative_cl_dt,
                                m.lastorder,
                                h.listed_ia,
                                h.sitting_judges,
                                h.list_before_remark,
                                h.is_nmd,
                                h.no_of_time_deleted 
                            FROM heardt h
                            INNER JOIN main m ON m.diary_no = h.diary_no 
                            INNER JOIN conct c ON c.conn_key = CAST(m.conn_key AS bigint)   
                            WHERE 
                                c.list = 'Y' 
                                AND m.c_status = 'P' 
                                AND m.diary_no = CAST(m.conn_key AS bigint)  
                                AND m.conn_key = '".$diary_no."'
                        ) a
                        INNER JOIN main m ON a.conc_diary_no = m.diary_no
                        INNER JOIN heardt h ON a.conc_diary_no = h.diary_no  
                        WHERE 
                            m.c_status = 'P' 
                            AND CAST(m.conn_key AS bigint)  != m.diary_no 
                            AND h.next_dt IS NOT NULL 
                        ORDER BY m.diary_no_rec_date
                    ) j";



			$query = $this->db->query($sql2);
			$result = $query->getResultArray();
            return $result;
    }
}