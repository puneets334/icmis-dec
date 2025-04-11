<?php

namespace App\Models\ManagementReport;
use CodeIgniter\Model;

class NoDaCodeModel extends Model
{
    public function check_sser_section($section_id)
    {
        $section_check = '';
        $builder = $this->db->table('master.usersection');
        $builder->select('id');
        $builder->where('id', $section_id);
        $builder->where('display', 'Y');
        $builder->where('isda', 'Y');
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            $section_check = " us.id = $section_id";
        }
        else{                           /////********* AFTER Test DELETE ELSE Condition
            $section_check = " us.id = 19";
        }
        
        return $section_check;
    }


    public function get_nodacode_report($section_id)
    {
        $section_check = $this->check_sser_section($section_id);
        // pr($section_check);
        $return = [];
        if($section_check){
            $subquery1 = $this->db->table('main a')
                ->select('a.diary_no,
                        COALESCE(NULLIF(active_casetype_id, 0), casetype_id) as casetype_id,
                        COALESCE(NULLIF(active_fil_no, \'\'), active_fil_no) as fil_no,
                        COALESCE(NULLIF(active_reg_year, 0), 
                            COALESCE(NULLIF(EXTRACT(YEAR FROM active_fil_dt), 0), 
                            EXTRACT(YEAR FROM fil_dt), 
                            EXTRACT(YEAR FROM active_fil_dt), 
                            EXTRACT(YEAR FROM diary_no_rec_date))) as reg_year,
                        ref_agency_state_id, ref_agency_code_id, diary_no_rec_date, pet_name, res_name, 
                        b.dacode, u.name, us.section_name, us.id as sec_id, \'O\' as type, rm_dt')
                ->join('mul_category mc', 'a.diary_no = mc.diary_no AND mc.display = \'Y\'', 'left')
                ->join('master.da_case_distribution b', 'b.case_type = a.casetype_id AND a.ref_agency_state_id = state AND 
                        COALESCE(NULLIF(active_reg_year, 0), 
                            COALESCE(NULLIF(EXTRACT(YEAR FROM active_fil_dt), 0), 
                            EXTRACT(YEAR FROM fil_dt), 
                            EXTRACT(YEAR FROM active_fil_dt), 
                            EXTRACT(YEAR FROM diary_no_rec_date)) 
                        ) BETWEEN b.case_f_yr AND b.case_t_yr', 'left')
                ->join('master.users u', 'b.dacode = u.usercode AND u.display = \'Y\'', 'left')
                ->join('master.usersection us', 'u.section = us.id AND us.display = \'Y\'', 'left')
                ->join('obj_save os', 'a.diary_no = os.diary_no AND os.display = \'Y\'', 'left')
                ->groupStart()
                    ->where('a.dacode', 0)
                    ->orWhere('a.dacode', NULL)
                ->groupEnd()
                // ->where('a.dacode', 0)
                ->where('a.c_status', 'P')
                ->whereNotIn('ref_agency_code_id', [116, 117, 118, 119, 120, 121, 122, 123, 124, 125, 126, 127, 128, 129, 130, 131, 132, 133, 134, 135, 136, 141, 190, 203, 1, 247, 272, 322, 140, 165, 182, 163, 156, 107, 189, 217, 155, 161, 271])
                ->whereNotIn('submaster_id', [118, 119, 120, 121, 122, 123, 124, 125, 126, 127, 128, 129, 130, 131, 132, 318, 332]);
                if (!empty($section_check)) {
                    $subquery1->where($section_check);
                }
                
                $subquery1->groupBy('a.diary_no, b.dacode, u.name, us.section_name,us.id, os.rm_dt');
                //pr($subquery1->getCompiledSelect());
                $subquery1 = $subquery1->getCompiledSelect();

                // pr($subquery1);

            // Second subquery (T)
            $subquery2 = $this->db->table('main a')
                ->select('a.diary_no,
                        COALESCE(NULLIF(active_casetype_id, 0), casetype_id) as casetype_id,
                        COALESCE(NULLIF(active_fil_no, \'\'), active_fil_no) as fil_no,
                        COALESCE(NULLIF(active_reg_year, 0), 
                            COALESCE(NULLIF(EXTRACT(YEAR FROM active_fil_dt), 0), 
                            EXTRACT(YEAR FROM fil_dt), 
                            EXTRACT(YEAR FROM active_fil_dt), 
                            EXTRACT(YEAR FROM diary_no_rec_date))) as reg_year,
                        ref_agency_state_id, ref_agency_code_id, diary_no_rec_date, pet_name, res_name, 
                        b.dacode, u.name, us.section_name, us.id as sec_id, \'T\' as type, rm_dt')
                ->join('master.da_case_distribution_tri b', 'b.case_type = a.casetype_id AND 
                        ref_agency_state_id = state AND 
                        COALESCE(NULLIF(active_reg_year, 0), 
                            COALESCE(NULLIF(EXTRACT(YEAR FROM active_fil_dt), 0), 
                            EXTRACT(YEAR FROM fil_dt), 
                            EXTRACT(YEAR FROM active_fil_dt), 
                            EXTRACT(YEAR FROM diary_no_rec_date)) 
                        ) BETWEEN b.case_f_yr AND b.case_t_yr', 'left')
                ->join('master.users u', 'b.dacode = u.usercode AND u.display = \'Y\'', 'left')
                ->join('master.usersection us', 'u.section = us.id AND us.display = \'Y\'', 'left')
                ->join('obj_save os', 'a.diary_no = os.diary_no AND os.display = \'Y\'', 'left')
                ->groupStart()
                    ->where('b.dacode', 0)
                    ->orWhere('b.dacode', NULL)
                ->groupEnd()
                // ->where('b.dacode', 0)
                ->where('c_status', 'P')
                ->whereIn('ref_agency_code_id', [116, 117, 118, 119, 120, 121, 122, 123, 124, 125, 126, 127, 128, 129, 130, 131, 132, 133, 134, 135, 136, 141, 190, 203, 1, 247, 272, 322, 140, 165, 182, 163, 156, 107, 189, 217, 155, 161, 271]);
                if (!empty($section_check)) {
                    $subquery2->where($section_check);
                }
                $subquery2->groupBy('a.diary_no, b.dacode, u.name, us.section_name,us.id, os.rm_dt');
                //pr($subquery2->getCompiledSelect());
                $subquery2 = $subquery2->getCompiledSelect();

                // pr($subquery2);

            // Third subquery (P)
            $subquery3 = $this->db->table('main a')
                ->select('a.diary_no,
                        COALESCE(NULLIF(active_casetype_id, 0), casetype_id) as casetype_id,
                        COALESCE(NULLIF(active_fil_no, \'\'), active_fil_no) as fil_no,
                        COALESCE(NULLIF(active_reg_year, 0), 
                            COALESCE(NULLIF(EXTRACT(YEAR FROM active_fil_dt), 0), 
                            EXTRACT(YEAR FROM fil_dt), 
                            EXTRACT(YEAR FROM active_fil_dt), 
                            EXTRACT(YEAR FROM diary_no_rec_date))) as reg_year,
                        ref_agency_state_id, ref_agency_code_id, diary_no_rec_date, pet_name, res_name, 
                        b.dacode, u.name, us.section_name, us.id as sec_id, \'P\' as type, rm_dt')
                ->join('mul_category mc', 'a.diary_no = mc.diary_no AND mc.display = \'Y\'', 'left')
                ->join('master.da_case_distribution_tri b', 'b.case_type = a.casetype_id AND 
                        COALESCE(NULLIF(active_reg_year, 0), 
                            COALESCE(NULLIF(EXTRACT(YEAR FROM active_fil_dt), 0), 
                            EXTRACT(YEAR FROM fil_dt), 
                            EXTRACT(YEAR FROM active_fil_dt), 
                            EXTRACT(YEAR FROM diary_no_rec_date))) 
                        BETWEEN b.case_f_yr AND b.case_t_yr', 'left')
                ->join('master.users u', 'b.dacode = u.usercode AND u.display = \'Y\'', 'left')
                ->join('master.usersection us', 'u.section = us.id AND us.display = \'Y\'', 'left')
                ->join('obj_save os', 'a.diary_no = os.diary_no AND os.display = \'Y\'', 'left')
                ->where('a.dacode', 0)
                ->where('a.c_status', 'P')
                ->whereIn('submaster_id', [118, 119, 120, 121, 122, 123, 124, 125, 126, 127, 128, 129, 130, 131, 132, 318, 332]);
                if (!empty($section_check)) {
                    $subquery3->where($section_check);
                }
                $subquery3->groupBy('a.diary_no, b.dacode, u.name, us.section_name,us.id, os.rm_dt');
                //pr($subquery3->getCompiledSelect());
                $subquery3 = $subquery3->getCompiledSelect();

                // pr($subquery3);

            // Final query
            $finalQuery = $this->db->query("
                SELECT x.*, y.agency_state, z.agency_name, c.short_description, next_dt, board_type, clno, brd_slno, roster_id 
                FROM (
                    ($subquery1)
                    UNION
                    ($subquery2)
                    UNION
                    ($subquery3)
                ) x
                LEFT JOIN master.ref_agency_state y ON x.ref_agency_state_id = y.cmis_state_id
                LEFT JOIN master.ref_agency_code z ON x.ref_agency_code_id = z.id
                LEFT JOIN master.casetype c ON x.casetype_id = c.casecode
                LEFT JOIN heardt h ON x.diary_no = h.diary_no
                ORDER BY sec_id, dacode
            ");
            
            $return =  $finalQuery->getResultArray();
        }    
        return $return;
    }
}
