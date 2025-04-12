<?php

namespace App\Models\Common\Component;

use CodeIgniter\Model;

class Model_case_status extends Model
{

    public function __construct()
    {
        parent::__construct();
    }


    function get_diary_disposal_date($diary_no)
    {
        //$diary_disposal_date = is_data_from_table('dispose', ['diary_no' => $diary_no], 'disp_dt,disp_type,dispjud,rj_dt,usercode', 'R');

        $sql = "
            SELECT
                d.rj_dt,
                d.jud_id,
                TO_CHAR(d.disp_dt, 'DD-MM-YYYY') AS ddt,
                TO_CHAR(d.ord_dt, 'DD-MM-YYYY') AS odt,
                TO_CHAR(d.ord_dt, 'DD-MM-YYYY') AS ord_dt,
                d.disp_dt,
                d.month,
                d.year,
                d.dispjud,
                u.name,
                u.empid,
                us.section_name,
                d.disp_type,
                STRING_AGG(j.jname, ', ' ORDER BY j.judge_seniority) AS judges,
                d.dispjud
            FROM
                dispose d
            LEFT JOIN
                master.users u ON d.usercode = u.usercode
            LEFT JOIN
                master.usersection us ON us.id = u.section
            LEFT JOIN
                master.judge j ON j.jcode = ANY(string_to_array(d.jud_id, ',')::int[])
            WHERE
                d.diary_no = ?
            GROUP BY
                d.diary_no, d.rj_dt, d.jud_id, d.disp_dt, d.ord_dt, d.month, d.year, d.dispjud, u.name, u.empid, us.section_name, d.disp_type
        ";

        $query = $this->db->query($sql, [$diary_no]);
        $diary_disposal_date =  $query->getRowArray();

        return json_encode($diary_disposal_date);
    }

    function get_party_details($diary_no, $flag = null)
    {
        $sql = "(SELECT 
                    sr_no_show, 
                    partyname, 
                    prfhname, 
                    addr1, 
                    addr2, 
                    state, 
                    city, 
                    dstname, 
                    pet_res, 
                    remark_del, 
                    remark_lrs, 
                    pflag, 
                    partysuff, 
                    deptname, 
                    ind_dep 
                    FROM 
                    public.party p 
                    LEFT JOIN master.deptt d ON state_in_name = d.deptcode 
                    WHERE 
                    diary_no = '$diary_no' 
                    AND pflag NOT IN ('T', 'Z') 
                    ORDER BY 
                    pet_res, 
                    COALESCE(CAST(NULLIF(SPLIT_PART(sr_no_show, '.', 1), '') AS INTEGER), 0), 
                    COALESCE(CAST(NULLIF(SPLIT_PART(SPLIT_PART(CONCAT(sr_no_show, '.0'), '.', 2), '.', -1), '') AS INTEGER), 0), 
                    COALESCE(CAST(NULLIF(SPLIT_PART(SPLIT_PART(CONCAT(sr_no_show, '.0.0'), '.', 3), '.', -1), '') AS INTEGER), 0), 
                    COALESCE(CAST(NULLIF(SPLIT_PART(SPLIT_PART(CONCAT(sr_no_show, '.0.0.0'), '.', 4), '.', -1), '') AS INTEGER), 0)
                    )
                    
                    union 

                   ( SELECT 
                    sr_no_show, 
                    partyname, 
                    prfhname, 
                    addr1, 
                    addr2, 
                    state, 
                    city, 
                    dstname, 
                    pet_res, 
                    remark_del, 
                    remark_lrs, 
                    pflag, 
                    partysuff, 
                    deptname, 
                    ind_dep 
                    FROM 
                    public.party_a p 
                    LEFT JOIN master.deptt d ON state_in_name = d.deptcode 
                    WHERE 
                    diary_no = '$diary_no' 
                    AND pflag NOT IN ('T', 'Z') 
                    ORDER BY 
                    pet_res, 
                    COALESCE(CAST(NULLIF(SPLIT_PART(sr_no_show, '.', 1), '') AS INTEGER), 0), 
                    COALESCE(CAST(NULLIF(SPLIT_PART(SPLIT_PART(CONCAT(sr_no_show, '.0'), '.', 2), '.', -1), '') AS INTEGER), 0), 
                    COALESCE(CAST(NULLIF(SPLIT_PART(SPLIT_PART(CONCAT(sr_no_show, '.0.0'), '.', 3), '.', -1), '') AS INTEGER), 0), 
                    COALESCE(CAST(NULLIF(SPLIT_PART(SPLIT_PART(CONCAT(sr_no_show, '.0.0.0'), '.', 4), '.', -1), '') AS INTEGER), 0) ) ";
                  
        $query = $this->db->query($sql);
        $result = $query->getResultArray();

        if (count($result) >= 1) {
            $result = $query->getResultArray();
            return json_encode($result);
        } else {
            return false;
        }
    }

    function get_pet_res_advocate($diary_no, $flag = null)
    {
        $builder1 = $this->db->table("advocate" . $flag . " a");
        $builder1->select("pet_res_no,adv, advocate_id, pet_res,is_ac,if_aor,if_sen,if_other,name,enroll_no,enroll_date, isdead");
        $builder1->join('master.bar b', "a.advocate_id=b.bar_id");
        $builder1->where('diary_no', $diary_no);
        $builder1->where('display', 'Y');
        $builder1->orderBy("pet_res");
        $query = $builder1->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return json_encode($result);
        } else {
            return false;
        }
    }

    function get_old_category($diary_no, $flag = null)
    {
        $builder1 = $this->db->table("mul_category" . $flag . " mc");
        $builder1->select("s.*");
        $builder1->join('master.submaster s', "mc.submaster_id=s.id");
        $builder1->where('diary_no', $diary_no);
        $builder1->where('mc.display', 'Y');
        $query = $builder1->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return json_encode($result);
        } else {
            return false;
        }
    }

    function get_new_category($diary_no, $flag = null)
    {
        $builder1 = $this->db->table("mul_category" . $flag . " mc");
        $builder1->select("s.*");
        $builder1->join('master.submaster s', "mc.new_submaster_id=s.id");
        $builder1->where('diary_no', $diary_no);
        $builder1->where('mc.display', 'Y');
        $query = $builder1->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return json_encode($result);
        } else {
            return false;
        }
    }

    function get_defect_days($diary_no, $flag = null)
    {
        $builder = $this->db->table("obj_save" . $flag . " obj")->select('(CURRENT_DATE-date(save_dt)) as no_of_days');
        $builder->join("main" . $flag . " m", "obj.diary_no=m.diary_no", 'left');
        $builder->where('obj.diary_no', $diary_no);
        $builder->where('rm_dt IS NULL');
        $builder->where('date(m.diary_no_rec_date)>', '2018-10-14');
        $builder->where('obj.display', 'Y');
        $query = $this->db->newQuery()->select('max(no_of_days) as no_of_days')->fromSubquery($builder, 'a')->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return json_encode($result[0]);
        }
    }

    function get_recalled_matters($diary_no)
    {
        $recalled_matters = is_data_from_table('recalled_matters', ['diary_no' => $diary_no, 'court_or_user' => 'C'], '*', 'R');
        return json_encode($recalled_matters);
    }
    function get_consignment_status($diary_no, $flag = null)
    {
        $builder = $this->db->table('record_keeping');
        $builder->select('diary_no, DATE(consignment_date) AS consignment_date');
        $builder->where('diary_no', $diary_no);
        $builder->where('display', 'Y');
        $builder->where('consignment_status', 'Y');

        $builder2 = $this->db->table('fil_trap_his' . $flag);
        $builder2->select('diary_no, DATE(rece_dt) AS consignment_date');
        $builder2->where('diary_no', $diary_no);
        $builder2->where('remarks', 'DISPOSAL -> RR-DA');

        $builder3 = $this->db->table('fil_trap' . $flag);
        $builder3->select('diary_no, DATE(rece_dt) AS consignment_date');
        $builder3->where('diary_no', $diary_no);
        $builder3->where('remarks', 'RR-DA -> SEG-DA');

        $query = $builder->union($builder2)->union($builder3)->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return json_encode($result);
        } else {
            return false;
        }
    }

    function get_sensitive_cases($diary_no)
    {
        $sensitive_matters = is_data_from_table('sensitive_cases', ['diary_no' => $diary_no, 'display' => 'Y'], 'diary_no', 'R');
        return json_encode($sensitive_matters);
    }
    function get_efiled_cases($diary_no)
    {
        $efiled_matters = is_data_from_table('efiled_cases', ['diary_no' => $diary_no, 'display' => 'Y', 'efiled_type' => 'new_case'], '*', 'R');
        return json_encode($efiled_matters);
    }

    function get_heardt_case($diary_no, $flag = null)
    {

        $query1 = $this->db->table('heardt' . $flag)
            ->select("next_dt as next_dt, clno, brd_slno AS brdslno, judges, subhead, mainhead, 'H' AS tbl, diary_no AS filno, null AS benchflag, next_dt AS next_dt_o, main_supp_flag, roster_id, CAST(board_type AS TEXT) as board_type, tentative_cl_dt")
            ->where('diary_no', $diary_no)
            ->where('next_dt IS NOT NULL');


        $query2 = $this->db->table('last_heardt' . $flag)
            ->select("next_dt as next_dt, clno, 0 AS brdslno, judges, subhead, mainhead, 'L' AS tbl, diary_no AS filno, bench_flag AS benchflag, next_dt AS next_dt_o, main_supp_flag, roster_id, CAST(board_type AS TEXT) as board_type, tentative_cl_dt")
            ->where('diary_no', $diary_no)
            ->groupStart()
            ->where('bench_flag is null')
            ->orWhere('bench_flag', '')
            ->orWhere('bench_flag', 'W')
            ->groupEnd()
            ->where('next_dt IS NOT NULL');
        $unionResult = $query1->union($query2);

        $finalQuery = $this->db->newQuery()
            ->select(" t1.*,(case when  t1.tbl = 'H' then (case when t1.main_supp_flag IN (1,2) then 'L' else 'P' end) else  (case when t1.main_supp_flag IN (1,2) then 'L' else 'P' end) end)  AS porl")
            ->fromSubquery($unionResult, 't1')
            ->orderBy('t1.tbl,t1.next_dt_o DESC')
            ->get();

        // $query = $this->db->getLastQuery();
        // echo (string) $query;

        if ($finalQuery->getNumRows() >= 1) {
            // $result = $finalQuery->getResultArray();
            $result = $finalQuery->getRowArray();
            return json_encode($result);
        } else {
            return false;
        }
    }

    function get_case_type_history($diary_no, $flag = null)
    {
        $query = $this->db->table('main_casetype_history' . $flag)
            ->select("date(order_date) as orderdt,date(updated_on) as updated")
            ->whereIn('ref_new_case_type_id', [3, 4])
            ->where('diary_no', $diary_no)->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return json_encode($result);
        } else {
            return false;
        }
    }

    function get_recalled_by($diary_no)
    {
        $builder1 = $this->db->table("recalled_matters rm");
        $builder1->select("u.name,us.section_name,rm.updated_on");
        $builder1->join('master.users u', "rm.updated_by=u.usercode");
        $builder1->join('master.usersection us', "us.id=u.section");
        $builder1->where('diary_no', $diary_no);
        $query = $builder1->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return json_encode($result);
        } else {
            return false;
        }
    }

    function get_fill_dt_case($diary_no, $flag = null)
    {
        $builder1 = $this->db->table("main" . $flag . " a");
        $builder1->select("fil_dt, (case when last_dt is NULL then null else last_dt end) as last_dt, 
a.usercode, (case when last_usercode is NULL then null else last_usercode end) as last_usercode, u.name as user, 
c.name as last_u");
        $builder1->join('master.users u', "a.usercode=u.usercode");
        $builder1->join('master.users c', "a.last_usercode=c.usercode");
        $builder1->where('diary_no', $diary_no);
        $query = $builder1->get(1);
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray()[0];
            return json_encode($result);
        } else {
            return false;
        }
    }

    function get_diary_section_details($diary_no, $flag = null)
    {
        $builder1 = $this->db->table("main" . $flag . " a");
        $builder1->select("a.usercode, name,section_name");
        $builder1->join('master.users b', "a.diary_user_id = b.usercode", 'LEFT');
        $builder1->join('master.usersection us', "b.section=us.id", "LEFT");
        $builder1->where('diary_no', $diary_no);
        $query = $builder1->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return json_encode($result[0]);
        } else {
            return false;
        }
    }

    function get_autodiary_details($diary_no)
    {
        $builder1 = $this->db->table("efiled_cases");
        $builder1->select("diary_no");
        $builder1->where('diary_no', $diary_no);
        $builder1->where('display', 'Y');
        $builder1->where('efiled_type', 'new_case');
        $builder1->groupStart()
            ->where('created_by', 10531)
            ->orWhere('date(created_at)>', '2023-07-19')
            ->groupEnd();
        $query = $builder1->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return json_encode($result);
        } else {
            return false;
        }
    }

    function get_fil_trap_details($diary_no, $flag = null)
    {
        $get_fil_trap = is_data_from_table('fil_trap' . $flag, ['diary_no' => $diary_no], 'remarks', 'R');
        $filing_stage = "";
        if (!empty($get_fil_trap)) {
            $stagerow = $get_fil_trap['remarks'];
            if ($stagerow == 'FIL -> DE') {
                $filing_stage = "Case is Diarized and Pending for Data Entry!!";
            } elseif ($stagerow == 'DE -> SCR') {
                $filing_stage = "Case is Pending for Scrutiny";
            } elseif ($stagerow == 'FDR -> SCR') {
                $filing_stage = "Case Refiled & under Scrutiny";
            } elseif ($stagerow == 'SCR -> FDR') {
                $defect_notify_count = 0;

                $builder = $this->db->table('fil_trap' . $flag);
                $builder->select('remarks');
                $builder->where('diary_no', $diary_no);
                $builder->where('remarks', 'SCR -> FDR');

                $builder2 = $this->db->table('fil_trap_his' . $flag);
                $builder2->select('remarks');
                $builder2->where('diary_no', $diary_no);
                $builder2->where('remarks', 'SCR -> FDR');

                $unionResult = $builder->unionAll($builder2)->get();
                if ($unionResult->getNumRows() == 1) {
                    $filing_stage = "Defects Notified & Returned";
                } else if ($unionResult->getNumRows() > 1)
                    $filing_stage = "Still Defective & Returned";
            } elseif ($stagerow == 'SCR -> CAT' || $stagerow == 'CAT -> TAG' || $stagerow == 'AUTO -> TAG') {

                $builder = $this->db->table('main' . $flag . ' m')
                    ->select('m.*')
                    ->join('obj_save AS o', 'm.diary_no = o.diary_no', 'left')
                    ->where('m.diary_no', $diary_no)
                    ->groupStart()
                    ->groupStart()
                    ->where('org_id', '10193')
                    ->where('display', 'Y')
                    ->where('rm_dt is not null')
                    ->groupEnd()
                    ->orWhere('display', null)
                    ->groupEnd()
                    ->groupStart()
                    ->where('m.ack_id', null)
                    ->orWhere('m.ack_id', 0)
                    ->groupEnd();
                $query = $builder->get();

                if ($query->getNumRows() == 0) {
                    $filing_stage = "Soft Copy not Filed";
                } else {
                    $verify_rs = is_data_from_table('defects_verification' . $flag, ['diary_no' => $diary_no, 'verification_status' => '0'], '*');
                    if (empty($verify_rs))
                        $filing_stage = "Case is Pending for Verification";
                }
            } elseif ($stagerow == 'TAG -> IB-Ex' || $stagerow == 'CAT -> IB-Ex') {
                $filing_stage = "Case is Listed/Ready for Listing";
            }
        }
        $get_case_type = is_data_from_table('main' . $flag, ['diary_no' => $diary_no], 'casetype_id', 'R');
        if (!empty($get_case_type)) {
            $casetype_id = $get_case_type['casetype_id'];
        }

        $section_casetypes = array(11, 12, 19, 25, 26, 9, 10, 39);
        if (!empty($casetype_id) && in_array($casetype_id, $section_casetypes)) {
            $t_section = is_data_from_table('main' . $flag, ['diary_no' => $diary_no], 'tentative_section(diary_no) as section', 'R');
            if (!empty($t_section)) {
                $sec = $t_section['section'];
            }
            $filing_stage = "Pending in Section: " . $sec;
        }
        return json_encode($filing_stage);
    }

    function get_acts_sections_details($diary_no)
    {
        $builder = $this->db->table('act_main as a');
        $builder->select('a.act, act_name,concat(c.section) as section');
        $builder->join('master.act_master as b', "(a.act = b.id AND b.display = 'Y')");
        $builder->join('master.act_section as c', "(c.act_id = a.id AND c.display = 'Y')", "left");
        $builder->where('diary_no', $diary_no);
        $builder->groupBy("act, c.section,act_name");
        $builder->orderBy('act_name');
        $query = $builder->get();

        // $query = $this->db->getLastQuery();
        // echo (string) $query;

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return json_encode($result);
        } else {
            return false;
        }
    }


    function get_IB_DA_Details($diary_no, $flag)
    {
        $union   =  $this->db->table('fil_trap_his' . $flag)->select('d_to_empid,diary_no')->where('diary_no', $diary_no)->where('remarks', 'CAT -> IB-Ex');
        $builder = $this->db->table('fil_trap' . $flag)->select('d_to_empid,diary_no')->union($union);
        $query = $this->db->newQuery()->select("d_to_empid,u.name,us.section_name")->fromSubquery($builder, 'f')->join('master.users as u', 'f.d_to_empid= u.empid')->join('master.usersection us', 'u.section=us.id')->where('f.diary_no', $diary_no)->whereIn('us.id', [19, 77])->get(1);

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray()[0];
            return json_encode($result);
        } else {
            return false;
        }
    }

    function get_da_section_details($diary_no, $flag = null)
    {
        $builder1 = $this->db->table("main" . $flag . " a");
        $builder1->select("dacode,name,section_name,casetype_id,active_casetype_id,diary_no_rec_date,reg_year_mh,reg_year_fh,active_reg_year,ref_agency_state_id");
        $builder1->join('master.users b', "a.dacode = b.usercode", 'LEFT');
        $builder1->join('master.usersection us', "b.section=us.id", "LEFT");
        $builder1->where('diary_no', $diary_no);
        $query = $builder1->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return json_encode($result[0]);
        } else {
            return false;
        }
    }

    function get_tentative_section($diary_no, $flag)
    {
        $builder1 = $this->db->table("main" . $flag);
        $builder1->select("tentative_section(diary_no) as section_name");
        $builder1->where('diary_no', $diary_no);
        $query = $builder1->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return json_encode($result[0]);
        } else {
            return false;
        }
    }

    function get_cl_printed_data($next_date, $gender, $part, $roster_id)
    {
        $builder1 = $this->db->table("cl_printed");
        $builder1->select("*");
        $builder1->where('next_dt>=CURRENT_DATE');
        $builder1->where('next_dt', $next_date);
        $builder1->where('m_f', $gender);
        $builder1->where('part', $part);
        $builder1->where('roster_id', $roster_id);
        $builder1->where('display', 'Y');
        $query = $builder1->get();
        if ($query->getNumRows() >= 1) {
            return 'Y';
        } else {
            return 'N';
        }
    }

    function get_file_movement_data($diary_no, $flag = null)
    {
        $builder = $this->db->table('fil_trap' . $flag);
        $builder->select('diary_no,d_by_empid,disp_dt,remarks,r_by_empid,d_to_empid,rece_dt,comp_dt,other');
        $builder->where('diary_no', $diary_no);


        $builder2 = $this->db->table('fil_trap_his' . $flag);
        $builder2->select('diary_no,d_by_empid,disp_dt,remarks,r_by_empid,d_to_empid,rece_dt,comp_dt,other');
        $builder2->where('diary_no', $diary_no);
        $builder2->where("comp_dt = (SELECT MAX(comp_dt) FROM fil_trap" . $flag . ")");

        $unionResult = $builder->union($builder2);

        $query = $this->db->newQuery()->select('a.*,u1.name d_by_name,u2.name r_by_name,u3.name o_name,u4.name d_to_name')->fromSubquery($unionResult, 'a')->join('master.users u1', "d_by_empid=u1.empid", 'left')->join('master.users u2', "r_by_empid=u2.empid", 'left')->join('master.users u3', "other=u3.empid", 'left')->join('master.users u4', "d_to_empid=u4.empid", 'left')->orderBy('disp_dt DESC, rece_dt DESC')->get();
        /*$query = $this->db->getLastQuery();
        echo (string) $query;
        exit;*/
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return json_encode($result);
        } else {
            return false;
        }
    }

    function getEarlierCourtData($diary_no, $flag)
    {
        $builder = $this->db->table("lowerct" . $flag . " as a");
        $builder->select('casetype_id,lct_dec_dt,lct_judge_name,lctjudname2,lctjudname3,l_dist,ct_code,l_state,name,brief_desc desc1,sub_law usec2,lct_judge_desg');
        $builder->select("CASE WHEN ct_code = 3 THEN (CASE WHEN l_state = 490506 THEN (SELECT court_name Name FROM master.state s LEFT JOIN master.delhi_district_court d ON s.state_code = d.state_code
                        AND s.district_code = d.district_code WHERE s.id_no = a.l_dist AND display = 'Y' )ELSE(SELECT Name FROM master.state s WHERE s.id_no = a.l_dist AND display = 'Y') END)
                        ELSE (SELECT agency_name FROM master.ref_agency_code c WHERE c.cmis_state_id = a.l_state AND c.id = a.l_dist AND is_deleted = 'f') END AS agency_name", false);
        $builder->select('crimeno,crimeyear,polstncode');
        $builder->select("(SELECT policestndesc FROM master.police p WHERE p.policestncd = a.polstncode AND p.display = 'Y' AND p.cmis_state_id = a.l_state AND p.cmis_district_id = a.l_dist AND a.crimeno != ''
            AND a.crimeno != '0') policestndesc", false);
        $builder->select('authdesc,l_inddep,l_orgname,l_ordchno,l_iopb,l_iopbn,l_org,lct_casetype,lct_caseno,lct_caseyear');
        $builder->select("CASE WHEN ct_code = 4 THEN (SELECT skey FROM master.casetype ct WHERE ct.display = 'Y' AND ct.casecode = a.lct_casetype )
        ELSE (
            SELECT 
            type_sname
        FROM
            master.lc_hc_casetype d
        WHERE
            d.lccasecode = a.lct_casetype
                AND d.display = 'Y'          
        )
    END AS type_sname", false);
        $builder->select('a.lower_court_id,
    is_order_challenged,
    full_interim_flag,
    judgement_covered_in,
    vehicle_code,
    vehicle_no,
    code,
    post_name,
    cnr_no,
    ref_court,
    ref_case_type,
    ref_case_no,
    ref_case_year,
    ref_state,
    ref_district,
    gov_not_state_id,
    gov_not_case_type,
    gov_not_case_no,
    gov_not_case_year,
    gov_not_date,
    relied_court,
    relied_case_type,
    relied_case_no,
    relied_case_year,
    relied_state,
    relied_district,
    transfer_case_type,
    transfer_case_no,
    transfer_case_year,
    transfer_state,
    transfer_district,
    transfer_court');
        $builder->join('master.state b', 'a.l_state = b.id_no AND b.display = \'Y\'', 'left');
        $builder->join('main' . $flag . ' e', 'e.diary_no = a.diary_no');
        $builder->join('master.authority f', 'f.authcode = a.l_iopb AND f.display = \'Y\'', 'left');
        $builder->join('master.rto h', 'h.id = a.vehicle_code AND h.display = \'Y\'', 'left');
        $builder->join('master.post_t i', 'i.post_code = a.lct_judge_desg AND i.display = \'Y\'', 'left');
        $builder->join('relied_details rd', 'rd.lowerct_id = a.lower_court_id AND rd.display = \'Y\'', 'left');
        $builder->join('transfer_to_details t_t', 't_t.lowerct_id = a.lower_court_id AND t_t.display = \'Y\'', 'left');
        $builder->where('a.diary_no', $diary_no);
        $builder->where('a.lw_display', 'Y');  // and ((lw_display='R' and lct_dec_dt is null and polstncode ='0' and crimeno =''
        // and crimeyear ='0') or (lw_display='Y' and lct_dec_dt='2022-03-01' and polstncode ='0' and crimeno =''
        // and crimeyear ='0'))
        $builder->orderBy('a.lower_court_id');
        //  $queryString = $builder->getCompiledSelect();
        //  echo $queryString;
        //  exit();
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return json_encode($result);
        } else {
            return false;
        }
    }

    function allTransferDetailsByDiaryNo($dairy_no, $flag)
    {
        $builder = $this->db->table("lowerct" . $flag . " as a");
        $builder->select([
            'lower_court_id',
            'transfer_court',
            'transfer_case_type',
            'name',
            '(CASE WHEN transfer_court = 3 THEN (CASE WHEN t.transfer_state = 490506 THEN (SELECT court_name FROM master.state s LEFT JOIN master.delhi_district_court d ON s.state_code = d.state_code AND s.district_code = d.district_code WHERE s.id_no = t.transfer_district AND display = \'Y\') ELSE (SELECT Name FROM master.state s WHERE s.id_no = t.transfer_district AND display = \'Y\') END) ELSE (SELECT agency_name FROM master.ref_agency_code c WHERE c.cmis_state_id = t.transfer_state AND c.id = t.transfer_district AND is_deleted = \'f\') END) AS reference_name',
            'CONCAT(CASE WHEN transfer_court = 4 THEN (SELECT skey FROM master.casetype ct WHERE ct.display = \'Y\' AND ct.casecode = t.transfer_case_type) ELSE (SELECT type_sname FROM master.lc_hc_casetype d WHERE d.lccasecode = t.transfer_case_type AND d.display = \'Y\') END, \'-\', transfer_case_no, \'-\', transfer_case_year) AS case_name',
            '(CASE WHEN transfer_court = 4 THEN \'Supreme Court\' WHEN transfer_court = 1 THEN \'High Court\' WHEN transfer_court = 3 THEN \'District Court\' WHEN transfer_court = 5 THEN \'State Agency\' END) AS court_name',
        ]);

        $builder->join('transfer_to_details t', 't.lowerct_id = a.lower_court_id AND t.display = \'Y\'', 'LEFT');
        $builder->join('master.state b', 't.transfer_state = b.id_no AND b.display = \'Y\'', 'LEFT');

        $builder->where('a.diary_no', $dairy_no);
        $builder->where('a.lw_display', 'Y');
        $builder->where('transfer_court >=', 1);

        $builder->orderBy('a.lower_court_id');

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            if (is_array($result)) {
                foreach ($result as $all_data) {
                    $data[$all_data['lower_court_id']] = $all_data;
                }
            }
            return json_encode($data);
        } else {
            return false;
        }
    }

    function allReferenceDetailsByDiaryNo($dairy_no, $flag)
    {
        $builder = $this->db->table("lowerct" . $flag . " as a");
        $builder->select([
            'lower_court_id',
            'ref_court',
            'name',
            '(CASE WHEN ref_court = 3 THEN (CASE WHEN ref_state = 490506 THEN (SELECT court_name Name FROM master.state s LEFT JOIN master.delhi_district_court d ON s.state_code = d.state_code AND s.district_code = d.district_code WHERE s.id_no = a.ref_district AND display = \'Y\') ELSE (SELECT Name FROM master.state s WHERE s.id_no = a.ref_district AND display = \'Y\') END) ELSE (SELECT agency_name FROM master.ref_agency_code c WHERE c.cmis_state_id = a.ref_state AND c.id = a.ref_district AND is_deleted = \'f\') END) AS reference_name',
            'CONCAT(CASE WHEN ref_court = 4 THEN (SELECT skey FROM master.casetype ct WHERE ct.display = \'Y\' AND ct.casecode = a.ref_case_type) ELSE (SELECT type_sname FROM master.lc_hc_casetype d WHERE d.lccasecode = a.ref_case_type AND d.display = \'Y\') END, \'-\', ref_case_no, \'-\', ref_case_year) AS case_name',
            '(CASE WHEN ref_court = 4 THEN \'Supreme Court\' WHEN ref_court = 1 THEN \'High Court\' WHEN ref_court = 3 THEN \'District Court\' WHEN ref_court = 5 THEN \'State Agency\' END) AS court_name',
        ]);

        $builder->join('master.state b', 'a.ref_state = b.id_no AND b.display = \'Y\'', 'LEFT');

        $builder->where('a.diary_no', $dairy_no);
        $builder->where('a.lw_display', 'Y');
        $builder->where('ref_court >=', 1);

        $builder->orderBy('a.lower_court_id');

        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            if (is_array($result)) {
                foreach ($result as $all_data) {
                    $data[$all_data['lower_court_id']] = $all_data;
                }
            }
            return json_encode($data);
        } else {
            return false;
        }
    }
    function allGovernmentNotificationsByDiaryNo($dairy_no, $flag)
    {
        $builder = $this->db->table("lowerct" . $flag . " as a");
        $builder->select([
            'lower_court_id',
            'name',
            'CONCAT(gov_not_case_type, \'-\', gov_not_case_no, \'-\', gov_not_case_year) AS case_name',
            'gov_not_date',
        ]);

        $builder->join('master.state b', 'a.gov_not_state_id = b.id_no AND b.display = \'Y\'', 'LEFT');

        $builder->where('a.diary_no', $dairy_no);
        $builder->where('a.lw_display', 'Y');
        $builder->where('gov_not_state_id >=', 1);

        $builder->orderBy('a.lower_court_id');

        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            if (is_array($result)) {
                foreach ($result as $all_data) {
                    $data[$all_data['lower_court_id']] = $all_data;
                }
            }
            return json_encode($data);
        } else {
            return false;
        }
    }

    function allReliedDetailsByDiaryNo($dairy_no, $flag)
    {
        $builder = $this->db->table("lowerct" . $flag . " as a");
        $builder->select([
            'lower_court_id',
            'relied_court',
            'relied_case_type',
            'name',
            '(CASE WHEN relied_court = 3 THEN (CASE WHEN rd.relied_state = 490506 THEN (SELECT court_name Name FROM master.state s LEFT JOIN master.delhi_district_court d ON s.state_code = d.state_code AND s.district_code = d.district_code WHERE s.id_no = rd.relied_district AND display = \'Y\') ELSE (SELECT Name FROM master.state s WHERE s.id_no = rd.relied_district AND display = \'Y\') END) ELSE (SELECT agency_name FROM master.ref_agency_code c WHERE c.cmis_state_id = rd.relied_state AND c.id = rd.relied_district AND is_deleted = \'f\') END) AS reference_name',
            'CONCAT(CASE WHEN relied_court = 4 THEN (SELECT skey FROM master.casetype ct WHERE ct.display = \'Y\' AND ct.casecode = rd.relied_case_type) ELSE (SELECT type_sname FROM master.lc_hc_casetype d WHERE d.lccasecode = rd.relied_case_type AND d.display = \'Y\') END, \'-\', relied_case_no, \'-\', relied_case_year) AS case_name',
            '(CASE WHEN relied_court = 4 THEN \'Supreme Court\' WHEN relied_court = 1 THEN \'High Court\' WHEN relied_court = 3 THEN \'District Court\' WHEN relied_court = 5 THEN \'State Agency\' END) AS court_name',
        ]);

        $builder->join('relied_details rd', 'rd.lowerct_id = a.lower_court_id AND rd.display = \'Y\'', 'LEFT');
        $builder->join('master.state b', 'rd.relied_state = b.id_no AND b.display = \'Y\'', 'LEFT');

        $builder->where('a.diary_no', $dairy_no);
        $builder->where('a.lw_display', 'Y');
        $builder->where('relied_court >=', 1);

        $builder->orderBy('a.lower_court_id');

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            if (is_array($result)) {
                foreach ($result as $all_data) {
                    $data[$all_data['lower_court_id']] = $all_data;
                }
            }
            return json_encode($data);
        } else {
            return false;
        }
    }

    public function getJudgeDetailsByDiary($dairy_no, $flag)
    {
        $builder = $this->db->table("lowerct" . $flag . " as l");
        $builder->select([
            'id',
            'lower_court_id',
            '(CASE 
                WHEN l.ct_code = 4 THEN (
                    SELECT 
                        CASE 
                            WHEN lj.judge_id is not null THEN CONCAT(j.title, \' \', j.first_name, \' \', j.sur_name)
                            ELSE \'\'
                        END          
                    FROM master.judge j 
                    WHERE lj.judge_id = j.jcode            
                )
                ELSE (
                    SELECT 
                        CASE 
                            WHEN lj.judge_id is not null THEN CONCAT(oj.title, \' \', oj.first_name, \' \', oj.sur_name)
                            ELSE \'\'
                        END
                    FROM master.org_lower_court_judges oj 
                    WHERE lj.judge_id = oj.id           
                )
            END) AS judge_name',
        ]);

        $builder->join('lowerct_judges' . $flag . ' as lj', 'l.lower_court_id = lj.lowerct_id', 'RIGHT');
        $builder->where('l.diary_no', $dairy_no);
        $query = $builder->get();

        $resultJudges = $query->getResultArray();
        if (is_array($resultJudges)) {
            $lower_court_id_arr = [];
            $baseArr = [];
            foreach ($resultJudges as $judges) {
                $lower_court_id_arr[] = $judges['lower_court_id'];
                $baseArr[] = $judges;
            }

            $arr1 = array_unique($lower_court_id_arr);

            $resultSet = [];
            foreach ($resultJudges as $jud) {
                if (in_array($jud['lower_court_id'], $arr1)) {
                    $id = $jud['lower_court_id'];
                    $resultSet[$id][] = [
                        'judge_name' => $jud['judge_name']
                    ];
                }
            }
        }
        return json_encode($resultSet);
    }


    public function getTagedMattersData($conn_case)
    {
        $db = \Config\Database::connect();
        $sqlconn = "SELECT 
            (SELECT name FROM master.users WHERE usercode = t.usercode) AS username,
            SUBSTRING(m.diary_no::TEXT FROM 1 FOR LENGTH(m.diary_no::TEXT) - 4) AS case_no,
            SUBSTRING(m.diary_no::TEXT FROM LENGTH(m.diary_no::TEXT) - 3 FOR 4) AS year,
            m.pet_name,
            m.res_name,
            t.list,
            m.c_status,
            TO_CHAR(m.fil_dt, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_f,
            CASE 
                WHEN (m.reg_year_mh = 0 OR m.fil_dt::DATE > '2017-05-10'::DATE) 
                THEN EXTRACT(YEAR FROM m.fil_dt)
                ELSE m.reg_year_mh
            END AS m_year,
            CASE 
                WHEN m.diary_no = $conn_case
                THEN 'M'
                ELSE 'C'
            END AS mc1,
            TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY HH12:MI AM') AS diary_no_rec_date,
            m.fil_no,
            m.fil_no_fh,
            TO_CHAR(m.fil_dt_fh, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_fh,
            COALESCE(m.reg_year_fh, EXTRACT(YEAR FROM m.fil_dt_fh)) AS f_year,
            CASE 
                WHEN m.fil_no IS NOT NULL THEN SPLIT_PART(m.fil_no, '-', 1)
                ELSE ''
            END AS ct1,
            CASE 
                WHEN m.fil_no IS NOT NULL THEN SPLIT_PART(SPLIT_PART(m.fil_no, '-', 2), '-', 1)
                ELSE ''
            END AS crf1,
            CASE 
                WHEN m.fil_no IS NOT NULL THEN SPLIT_PART(m.fil_no, '-', 3)
                ELSE ''
            END AS crl1,
            CASE 
                WHEN m.fil_no_fh IS NOT NULL THEN SPLIT_PART(m.fil_no_fh, '-', 1)
                ELSE ''
            END AS ct2,
            CASE 
                WHEN m.fil_no_fh IS NOT NULL THEN SPLIT_PART(SPLIT_PART(m.fil_no_fh, '-', 2), '-', 1)
                ELSE ''
            END AS crf2,
            CASE 
                WHEN m.fil_no_fh IS NOT NULL THEN SPLIT_PART(m.fil_no_fh, '-', 3)
                ELSE ''
            END AS crl2,
            m.casetype_id,
            m.case_status_id,
            TO_CHAR(m.fil_dt, 'DD-MM-YYYY HH12:MI AM') AS fldt,
            TRIM(LEADING '0' FROM SUBSTRING(m.diary_no::text FROM 3 FOR 3)) AS ccode,
            m.diary_no,
            t.conn_type,
            TO_CHAR(t.ent_dt, 'DD-MM-YYYY HH12:MI AM') AS endt
        FROM
            main m
        LEFT JOIN
            conct t ON (t.conn_key = m.conn_key::bigint AND m.diary_no = t.diary_no)
        WHERE 
            m.conn_key = '$conn_case' 
        ORDER BY 
            mc1 DESC, endt, diary_no_rec_date";
                
            $query = $this->db->query($sqlconn);
             return    $results = $query->getResultArray();
    }



    public function getHeardtWithUser($diaryno)  {
        $builder = $this->db->table('heardt a');

        $builder->select('a.*, section_name, b.name');
        $builder->join('master.users b', 'a.usercode = b.usercode', 'left');
        $builder->join('master.usersection c', 'b.section = c.id', 'left');
        $builder->where('diary_no', $diaryno);

        $result_listing = $builder->get();         
        return $data = $result_listing->getResultArray();
    }

    public function getLastHeardtWithUser($diaryno)  {
        $builder = $this->db->table('last_heardt a');

        $builder->select('a.*, section_name, b.name');
        $builder->join('master.users b', 'a.usercode = b.usercode', 'left');
        $builder->join('master.usersection c', 'b.section = c.id', 'left');
        $builder->where('diary_no', $diaryno);
        $builder->where('next_dt IS NOT NULL'); // Use != for inequality
        $builder->where('a.bench_flag', ''); // Empty string for null check
        $builder->orderBy('ent_dt', 'DESC');

        $result_listing = $builder->get();         
        return $data = $result_listing->getResultArray();
    }


    public function getIaUser($diaryno)
    {         
        $builder = $this->db->table('docdetails d');
        $builder->select([
            'd.*',
            '(SELECT name FROM master.users WHERE usercode = d.usercode) AS username',
            '(SELECT name FROM master.users WHERE usercode = d.lst_user) AS modify_username',
            '(SELECT name FROM master.users WHERE usercode = d.last_modified_by) AS disposedby'
        ]);
        $builder->where('d.doccode', '8');
        $builder->where('d.diary_no', $diaryno);
        $builder->where('d.display', 'Y');
        $builder->orderBy('d.ent_dt');

        $query = $builder->get();
        return $result_ia = $query->getResultArray();
    }

    public function getDMSData($diaryno)
    {
        $builder = $this->db->table('docdetails d');
        $builder->select([
            'd.*',
            'to_char(d.ent_dt, \'DD/MM/YYYY HH24:MI\') AS entdt',
            '(SELECT name FROM master.users WHERE usercode = d.usercode) AS username',
            '(SELECT name FROM master.users WHERE usercode = d.lst_user) AS modify_username'
        ]);
        $builder->where('d.doccode !=', 8);
        $builder->where('d.diary_no', $diaryno);
        $builder->where('d.display', 'Y');
        $builder->orderBy('d.ent_dt', 'DESC');

        $query = $builder->get();
        return $query->getResultArray();
    }



    public function getNoticesData($diaryno)
{
    $builder = $this->db->table('tw_tal_del a');
    $builder->select([
        'DISTINCT (a.id)',
        'a.diary_no',
        'process_id',
        'a.name',
        'address',
        'b.name AS nt_typ',
        'del_type',
        'tw_sn_to',
        'copy_type',
        'send_to_type',
        'fixed_for',
        'rec_dt',
        'office_notice_rpt',
        'sendto_district',
        'sendto_state',
        'nt_type',
        'tal_state',
        'tal_district',
        'dispatch_id',
        'dispatch_dt',
        'station',
        'weight',
        'stamp',
        'barcode',
        'dis_remark',
        'dispatch_user_id',
        'notice_path',
        "COALESCE(d.ser_date is NULL) AS ser_date",
        "COALESCE(d.ser_dt_ent_dt is NULL) AS ser_dt_ent_dt",
        'd.serve',
        'd.ser_type'
    ]);
    $builder->join('master.tw_notice b', 'CAST(a.nt_type AS INTEGER) = b.id AND b.display = \'Y\'');
    $builder->join('tw_o_r c', 'c.tw_org_id = a.id AND c.display = \'Y\'');
    $builder->join('tw_comp_not d', 'd.tw_o_r_id = c.id AND d.display = \'Y\'');
    $builder->where('a.display', 'Y');
    $builder->where('print', 1);
    $builder->where('a.diary_no', $diaryno);
    $builder->orderBy('a.id');
    $builder->orderBy('process_id');
   // pr($builder->getCompiledSelect());
    //die;
    $query = $builder->get();
    return $query->getResultArray();
}

    public function getObjSaveData($diary_no)
    {
        $builder = $this->db->table('obj_save a');
        $builder->select([
            'a.org_id',
            'objdesc AS obj_name',
            'rm_dt',
            'remark',
            'a.mul_ent',
            'save_dt'
        ]);
        $builder->join('master.objection b', 'a.org_id = b.objcode');
        $builder->where('diary_no', $diary_no);
        $builder->where('a.display', 'Y');
        $builder->orderBy('id');
    
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getConnKeys($diaryno)
    {    
        $builder = $this->db->query("
            SELECT STRING_AGG(conn_key::text, ',') as conn_key 
            FROM (
                SELECT cast(conn_key as BIGINT) FROM main WHERE diary_no = '$diaryno' AND cast(conn_key AS INTEGER) > 0
                UNION
                SELECT conn_key FROM heardt WHERE diary_no = '$diaryno' AND conn_key != diary_no AND conn_key > 0 AND diary_no != 0
                UNION
                SELECT conn_key FROM last_heardt WHERE diary_no = '$diaryno' AND conn_key != diary_no AND conn_key > 0 AND brd_slno > 0 AND roster_id > 0 AND (bench_flag = '' OR bench_flag IS NULL) AND diary_no != 0
            ) a
        ");

        return $row = $builder->getResultArray();
        
    }

    public function getConnListData($DNumber_main, $diaryno)
    {
        $builder = $this->db->table('main');
        $builder->select("STRING_AGG(diary_no::text, ',') as conn_list");
        $builder->whereIn('conn_key', explode(',', $DNumber_main)); 
        $builder->whereNotIn('diary_no', explode(',', $DNumber_main));
        $builder->where('diary_no !=', $diaryno); 
         
        $query = $builder->get();
        return $result = $query->getResultArray();         
    }

    public function getJoinData($diaryNo)
    {
    
        $sql = "SELECT *
                FROM (
                    SELECT 
                        o.diary_no AS diary_no,
                        SUBSTRING(o.diary_no::text FROM 1 FOR LENGTH(o.diary_no::text) - 4) AS d_no,
                        SUBSTRING(o.diary_no::text FROM LENGTH(o.diary_no::text) - 3) AS d_year,
                        o.jm AS jm,
                        TO_CHAR(o.dated::date, 'YYYY-MM-DD') AS dated,
                        m.pet_name,
                        m.res_name,
                        pet.name AS pet_adv_id,
                        res.name AS res_adv_id,
                        m.active_fil_no,
                        short_description,
                        active_casetype_id,
                        active_reg_year,
                        CASE
                            WHEN o.jt = 'rop' THEN 'ROP'
                            WHEN o.jt = 'judgment' THEN 'Judgement'
                            WHEN o.jt = 'or' THEN 'Office Report'
                        END AS jo,
                        CASE
                            WHEN (m.diary_no = m.conn_key::int OR m.conn_key = '0' OR m.conn_key IS NULL) THEN 'M'
                            ELSE 'C'
                        END AS main_or_connected
                    FROM tempo o
                    LEFT JOIN main m ON o.dn || o.dy = m.diary_no::text
                    LEFT JOIN master.bar pet ON m.pet_adv_id = pet.bar_id
                    LEFT JOIN master.bar res ON m.res_adv_id = res.bar_id
                    LEFT JOIN master.casetype c ON m.active_casetype_id = casecode
                    WHERE m.diary_no IN (?)

                    UNION

                    SELECT 
                        o.diary_no AS diary_no,
                        SUBSTRING(o.diary_no::text FROM 1 FOR LENGTH(o.diary_no::text) - 4) AS d_no,
                        SUBSTRING(o.diary_no::text FROM LENGTH(o.diary_no::text) - 3) AS d_year,
                        o.pdfname AS jm,
                        TO_CHAR(o.orderdate, 'YYYY-MM-DD') AS dated,
                        m.pet_name,
                        m.res_name,
                        pet.name AS pet_adv_id,
                        res.name AS res_adv_id,
                        m.active_fil_no,
                        short_description,
                        active_casetype_id,
                        active_reg_year,
                        CASE
                            WHEN o.type = 'O' THEN 'ROP'
                            WHEN o.type = 'J' THEN 'Judgement'
                        END AS jo,
                        CASE
                            WHEN (m.diary_no = m.conn_key::int OR m.conn_key = '0' OR m.conn_key IS NULL) THEN 'M'
                            ELSE 'C'
                        END AS main_or_connected
                    FROM ordernet o
                    LEFT JOIN main m ON o.diary_no = m.diary_no
                    LEFT JOIN master.bar pet ON m.pet_adv_id = pet.bar_id
                    LEFT JOIN master.bar res ON m.res_adv_id = res.bar_id
                    LEFT JOIN master.casetype c ON m.active_casetype_id = casecode
                    WHERE o.diary_no IN (?)

                    UNION

                    SELECT 
                        o.dn AS diary_no,
                        SUBSTRING(o.dn::text FROM 1 FOR LENGTH(o.dn::text) - 4) AS d_no,
                        SUBSTRING(o.dn::text FROM LENGTH(o.dn::text) - 3) AS d_year,
                        'ropor/rop/all/' || o.pno || '.pdf' AS jm,
                        TO_CHAR(o.orderdate, 'YYYY-MM-DD') AS dated,
                        m.pet_name,
                        m.res_name,
                        pet.name AS pet_adv_id,
                        res.name AS res_adv_id,
                        m.active_fil_no,
                        short_description,
                        active_casetype_id,
                        active_reg_year,
                        'ROP' AS jo,
                        CASE
                            WHEN (m.diary_no = m.conn_key::int OR m.conn_key = '0' OR m.conn_key IS NULL) THEN 'M'
                            ELSE 'C'
                        END AS main_or_connected
                    FROM rop_text_web.old_rop o
                    LEFT JOIN main m ON o.dn = m.diary_no
                    LEFT JOIN master.bar pet ON m.pet_adv_id = pet.bar_id
                    LEFT JOIN master.bar res ON m.res_adv_id = res.bar_id
                    LEFT JOIN master.casetype c ON m.active_casetype_id = casecode
                    WHERE o.dn IN (?)

                    UNION

                    SELECT 
                        o.dn AS diary_no,
                        SUBSTRING(o.dn::text FROM 1 FOR LENGTH(o.dn::text) - 4) AS d_no,
                        SUBSTRING(o.dn::text FROM LENGTH(o.dn::text) - 3) AS d_year,
                        'judis/' || o.filename || '.pdf' AS jm,
                        TO_CHAR(o.juddate::date, 'YYYY-MM-DD') AS dated,
                        m.pet_name,
                        m.res_name,
                        pet.name AS pet_adv_id,
                        res.name AS res_adv_id,
                        m.active_fil_no,
                        short_description,
                        active_casetype_id,
                        active_reg_year,
                        'Judgment' AS jo,
                        CASE
                            WHEN (m.diary_no = m.conn_key::int OR m.conn_key = '0' OR m.conn_key IS NULL) THEN 'M'
                            ELSE 'C'
                        END AS main_or_connected
                    FROM scordermain o
                    LEFT JOIN main m ON o.dn = m.diary_no
                    LEFT JOIN master.bar pet ON m.pet_adv_id = pet.bar_id
                    LEFT JOIN master.bar res ON m.res_adv_id = res.bar_id
                    LEFT JOIN master.casetype c ON m.active_casetype_id = casecode
                    WHERE o.dn IN (?)

                    UNION

                    SELECT 
                        o.dn AS diary_no,
                        SUBSTRING(o.dn::text FROM 1 FOR LENGTH(o.dn::text) - 4) AS d_no,
                        SUBSTRING(o.dn::text FROM LENGTH(o.dn::text) - 3) AS d_year,
                        'bosir/orderpdf/' || o.pno || '.pdf' AS jm,
                        TO_CHAR(o.orderdate::date, 'YYYY-MM-DD') AS dated,
                        m.pet_name,
                        m.res_name,
                        pet.name AS pet_adv_id,
                        res.name AS res_adv_id,
                        m.active_fil_no,
                        short_description,
                        active_casetype_id,
                        active_reg_year,
                        'ROP' AS jo,
                        CASE
                            WHEN (m.diary_no = m.conn_key::int OR m.conn_key = '0' OR m.conn_key IS NULL) THEN 'M'
                            ELSE 'C'
                        END AS main_or_connected
                    FROM rop_text_web.oldordtext o
                    LEFT JOIN main m ON o.dn = m.diary_no
                    LEFT JOIN master.bar pet ON m.pet_adv_id = pet.bar_id
                    LEFT JOIN master.bar res ON m.res_adv_id = res.bar_id
                    LEFT JOIN master.casetype c ON m.active_casetype_id = casecode
                    WHERE o.dn IN (?) AND o.display = 'Y'
                    UNION
                    SELECT 
                        o.dn AS diary_no,
                        SUBSTRING(o.dn::text FROM 1 FOR LENGTH(o.dn::text) - 4) AS d_no,
                        SUBSTRING(o.dn::text FROM LENGTH(o.dn::text) - 3) AS d_year,
                        'bosir/orderpdfold/' || o.pno || '.pdf' AS jm,
                        TO_CHAR(o.orderdate::date, 'YYYY-MM-DD') AS dated,
                        m.pet_name,
                        m.res_name,
                        pet.name AS pet_adv_id,
                        res.name AS res_adv_id,
                        m.active_fil_no,
                        short_description,
                        active_casetype_id,
                        active_reg_year,
                        'ROP' AS jo,
                        CASE
                            WHEN (m.diary_no = m.conn_key::int OR m.conn_key = '0' OR m.conn_key IS NULL) THEN 'M'
                            ELSE 'C'
                        END AS main_or_connected
                    FROM rop_text_web.ordertext o
                    LEFT JOIN main m ON o.dn = m.diary_no
                    LEFT JOIN master.bar pet ON m.pet_adv_id = pet.bar_id
                    LEFT JOIN master.bar res ON m.res_adv_id = res.bar_id
                    LEFT JOIN master.casetype c ON m.active_casetype_id = casecode
                    WHERE o.dn IN (?)
                ) tbl1
                ORDER BY tbl1.dated DESC;";

 
        $query = $this->db->query($sql, [$diaryNo, $diaryNo, $diaryNo,$diaryNo, $diaryNo, $diaryNo]);
        return $query->getResultArray();
 
    }

    public function getDropNoteData($diaryno)
    {
        $builder = $this->db->table('drop_note d');
        $builder->select([
            'd.*',
            "STRING_AGG(j.jname, ', ' ORDER BY j.judge_seniority) AS jnm"
        ]);
        $builder->join('master.roster_judge r', 'd.roster_id = r.roster_id');
        $builder->join('master.judge j', 'j.jcode = r.judge_id AND j.jcode != 0');
        $builder->where([
            'd.diary_no' => $diaryno,
            'd.display' => 'Y',
            'r.display' => 'Y'
        ]);
        $builder->groupBy('d.id');
        $builder->groupBy('diary_no');
        $builder->groupBy('d.cl_date');
        $builder->groupBy('d.roster_id');
        $builder->orderBy('d.ent_dt', 'DESC');
         
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getCaseInfo($diaryno)
    {
        $builder = $this->db->table('case_info');
        $builder->select([
            '*',
            'case_info.usercode AS u',
            "insert_time AS entrydate",
            "concat(users.name, '[', users.empid, ']') AS userinfo",
            'main.reg_no_display AS caseno'
        ]);
        $builder->join('master.users', 'case_info.usercode = users.usercode');
        $builder->join('main', 'case_info.diary_no = main.diary_no');
        $builder->where([
            'case_info.diary_no' => $diaryno,
            'case_info.display' => 'Y'
        ]);
        //echo $builder->getCompiledSelect();
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getMainInfo($diaryno)
    {
        $builder = $this->db->table('main');
        $builder->select([
            'pet_name',
            'res_name',
            'DATE(diary_no_rec_date) AS diary_no_rec_date', // Cast to date
            'casetype_id'
        ]);
        $builder->where('diary_no', $diaryno);

        $query = $builder->get();
        return $query->getRowArray(); // Use getRowArray() for a single row result
    }


    public function caseStatusCaveat($diaryno,$is_order_challenged)
    {
        $sql = "
                SELECT DISTINCT
                    b.lct_dec_dt,
                    b.l_dist,
                    b.l_state,
                    b.lct_casetype,
                    b.lct_caseno,
                    b.lct_caseyear,
                    b.caveat_no AS c_diary,
                    b.ct_code,
                    c.name,
                    CASE
                        WHEN b.ct_code = 3 THEN (
                            SELECT s.name
                            FROM master.state s
                            WHERE s.id_no = b.l_dist
                            AND s.display = 'Y'
                        )
                        ELSE (
                            SELECT c.agency_name
                            FROM master.ref_agency_code c
                            WHERE c.cmis_state_id = b.l_state
                            AND c.id = b.l_dist
                            AND c.is_deleted = 'f'
                        )
                    END AS agency_name,
                    CASE
                        WHEN b.ct_code = 4 THEN (
                            SELECT skey
                            FROM master.casetype ct
                            WHERE ct.display = 'Y'
                            AND ct.casecode = b.lct_casetype
                        )
                        ELSE (
                            SELECT type_sname
                            FROM master.lc_hc_casetype d
                            WHERE d.lccasecode = b.lct_casetype
                            AND d.display = 'Y'
                        )
                    END AS type_sname,
                    SUBSTRING(fil_no FROM 4) AS fil_no,
                    EXTRACT(YEAR FROM fil_dt) AS fil_dt,
                    short_description,
                    court_name,
                    c_status,
                    pet_name,
                    res_name,
                    TO_DATE(diary_no_rec_date::TEXT, 'YYYY-MM-DD') AS diary_no_rec_date
                FROM
                    lowerct a
                JOIN
                    caveat_lowerct b ON a.lct_dec_dt = b.lct_dec_dt
                    AND a.l_state = b.l_state
                    AND TRIM(LEADING '0' FROM a.lct_caseno) = TRIM(LEADING '0' FROM b.lct_caseno)
                    AND a.lct_caseyear = b.lct_caseyear
                    AND a.ct_code = b.ct_code
                    AND a.lct_dec_dt IS NOT NULL
                LEFT JOIN
                    master.state c ON b.l_state = c.id_no AND c.display = 'Y'
                LEFT JOIN
                    caveat d ON d.caveat_no = b.caveat_no
                LEFT JOIN
                    master.casetype e ON e.casecode::text = SUBSTRING(d.fil_no::text FROM 1 FOR 2) AND e.display = 'Y'
                LEFT JOIN
                    master.m_from_court f ON f.id = a.ct_code AND f.display = 'Y'
                WHERE
                    a.diary_no = ?
                    AND a.lw_display = 'Y'
                    AND b.lw_display = 'Y'
                    $is_order_challenged

                UNION

                SELECT DISTINCT
                    b.lct_dec_dt,
                    b.l_dist,
                    b.l_state,
                    b.lct_casetype,
                    b.lct_caseno,
                    b.lct_caseyear,
                    cd.caveat_no AS c_diary,
                    b.ct_code,
                    c.name,
                    CASE
                        WHEN b.ct_code = 3 THEN (
                            SELECT s.name
                            FROM master.state s
                            WHERE s.id_no = b.l_dist
                            AND s.display = 'Y'
                        )
                        ELSE (
                            SELECT c.agency_name
                            FROM master.ref_agency_code c
                            WHERE c.cmis_state_id = b.l_state
                            AND c.id = b.l_dist
                            AND c.is_deleted = 'f'
                        )
                    END AS agency_name,
                    CASE
                        WHEN b.ct_code = 4 THEN (
                            SELECT skey
                            FROM master.casetype ct
                            WHERE ct.display = 'Y'
                            AND ct.casecode = b.lct_casetype
                        )
                        ELSE (
                            SELECT type_sname
                            FROM   master.lc_hc_casetype d
                            WHERE d.lccasecode = b.lct_casetype
                            AND d.display = 'Y'
                        )
                    END AS type_sname,
                    SUBSTRING(fil_no FROM 4) AS fil_no,
                    EXTRACT(YEAR FROM fil_dt) AS fil_dt,
                    short_description,
                    court_name,
                    c_status,
                    pet_name,
                    res_name,
                    TO_DATE(diary_no_rec_date::TEXT, 'YYYY-MM-DD') AS diary_no_rec_date
                FROM
                    caveat_diary_matching cd
                LEFT JOIN
                    lowerct a ON cd.diary_no = a.diary_no
                LEFT JOIN
                    caveat_lowerct b ON cd.caveat_no = b.caveat_no
                LEFT JOIN
                    master.state c ON b.l_state = c.id_no AND c.display = 'Y'
                LEFT JOIN
                    caveat d ON d.caveat_no = b.caveat_no
                LEFT JOIN
                    master.casetype e ON e.casecode::text = SUBSTRING(d.fil_no::text FROM 1 FOR 2) AND e.display = 'Y'
                LEFT JOIN
                    master.m_from_court f ON f.id = a.ct_code AND f.display = 'Y'
                WHERE
                    cd.diary_no = ?
                    AND b.lw_display = 'Y'
                    AND cd.display = 'Y'
                 
                ";

                $query = $this->db->query($sql, [$diaryno, $diaryno]);
                //echo $this->db->getLastQuery();
                return $result = $query->getResultArray();
    }


    public function getAdvocates($c_diary)
    {
        $builder = $this->db->table('caveat_advocate a');
        $builder->select('name, aor_code');
        $builder->join('master.bar b', 'a.advocate_id = b.bar_id AND a.display = \'Y\'');
        $builder->where('a.caveat_no', $c_diary); // Use parameter binding

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getLowerCourtData($diaryNo,$number='')
    {
        $builder = $this->db->table('lowerct a');

        $builder->select([
            "DISTINCT (a.lct_dec_dt)",
            'a.l_dist',
            'a.l_state',
            'a.lct_casetype',
            'a.lct_caseno',
            'a.lct_caseyear',
            'b.diary_no AS c_diary',
            'a.ct_code',
            'c.name',
            'CASE
                WHEN a.ct_code = 3 THEN (
                    SELECT name
                    FROM master.state s
                    WHERE s.id_no = a.l_dist
                      AND display = \'Y\'
                )
                ELSE (
                    SELECT agency_name
                    FROM master.ref_agency_code c
                    WHERE c.cmis_state_id = a.l_state
                      AND c.id = a.l_dist
                      AND is_deleted = \'f\'
                )
            END AS agency_name',
            'CASE
                WHEN a.ct_code = 4 THEN (
                    SELECT skey
                    FROM master.casetype ct
                    WHERE ct.display = \'Y\'
                      AND ct.casecode = a.lct_casetype
                )
                ELSE (
                    SELECT type_sname
                    FROM master.lc_hc_casetype d
                    WHERE d.lccasecode = a.lct_casetype
                      AND d.display = \'Y\'
                )
            END AS type_sname',
            'SUBSTR(fil_no::TEXT, 4) AS fil_no',
            'fil_dt AS fil_dt',
            'b.is_order_challenged',
            'b.full_interim_flag',
            'short_description',
            'court_name',
            'c_status'
        ]);
        $zero = $one = $two = $three = '';
        if($number == 1)
        {
            $builder->select(['a.polstncode','a.crimeno','a.crimeyear','policestndesc']);
            $one = " AND a.polstncode=b.polstncode AND trim(leading '0' from trim(a.crimeno))=trim(leading '0' from trim(b.crimeno)) 
            AND a.crimeyear=b.crimeyear  AND a.ct_code = b.ct_code ";

            $builder->join('master.police p', 'p.policestncd = a.polstncode AND p.cmis_state_id = a.l_state AND p.cmis_district_id = a.l_dist AND p.display = \'Y\'','left');
           
            $builder->join('lowerct b', "  a.l_dist = b.l_dist AND a.l_state = b.l_state 
            AND a.ct_code = b.ct_code  $one ");
            $builder->where('a.polstncode != 0')->where("a.crimeno != '0'")->where('a.crimeyear != 0')->where("a.diary_no !=  b.diary_no");

        }elseif($number == 2)
        {
            $builder->select(['b.vehicle_no','b.vehicle_code','g.code']);
            
            $builder->join('master.rto g','g.id=a.vehicle_code AND g.display = \'Y\'','left');
            $builder->where('b.vehicle_code != 0');            
            $builder->join('lowerct b', " a.l_state = b.l_state AND a.ct_code = b.ct_code  AND a.vehicle_code=b.vehicle_code  AND a.vehicle_no=b.vehicle_no ");

        }elseif($number == 3)
        {
            $builder->select(['a.ref_court','a.ref_case_type','a.ref_case_no','a.ref_case_year','a.ref_state','a.ref_district']);             
            $builder->join('master.rto g','g.id=a.vehicle_code AND g.display = \'Y\'','left');
            $builder->where('a.ref_case_type!=0')->where('a.ref_court!=0')
            ->where('a.ref_case_no!=0')->where('a.ref_case_year!=0')->where('a.ref_state!=0')->where('a.ref_district!=0');
            $builder->join('lowerct b', "a.ref_court = b.ct_code
                AND a.ref_case_type = b.lct_casetype
                AND a.ref_case_no = cast(b.lct_caseno as bigint)
                AND a.ref_case_year = b.lct_caseyear 
                AND a.ref_state = b.l_state
                AND a.ref_district = b.l_dist");

        }elseif($number == 4)
        {
            $builder->select(['a.gov_not_state_id', 'a.gov_not_case_type','a.gov_not_case_no','a.gov_not_case_year','a.gov_not_date']);   
            $builder->join('lowerct b', "a.gov_not_state_id = b.gov_not_state_id 
            AND a.gov_not_case_type = b.gov_not_case_type
            AND a.gov_not_case_no = b.gov_not_case_no
            AND a.gov_not_case_year = b.gov_not_case_year 
            AND a.gov_not_date = b.gov_not_date");

            $builder->where('a.ref_case_type!=0')->where('a.ref_court!=0')
            ->where('a.gov_not_state_id!=0')->where("a.gov_not_case_type!= ''")->where('a.gov_not_case_no!=0')->where('a.gov_not_case_year!=0')->where('a.gov_not_date IS NOT NULL');

        }elseif($number == 5)
        {
            $builder->select(['aa.relied_court','aa.relied_case_type','aa.relied_case_no','aa.relied_case_year','aa.relied_state','aa.relied_district']); 
            $builder->join('relied_details aa ', 'a.lower_court_id=aa.lowerct_id and aa.display = \'Y\'');
            $builder->join('lowerct b', "aa.relied_court = b.ct_code
                AND aa.relied_case_type = b.lct_casetype
                AND aa.relied_case_no = cast(b.lct_caseno as BIGINT)
                AND aa.relied_case_year = b.lct_caseyear
                AND aa.relied_state = b.l_state
                AND aa.relied_district = b.l_dist");
                $builder->where('aa.relied_case_type !=0')->where('aa.relied_court!=0')
            ->where('aa.relied_case_no!=0')->where("aa.relied_case_year!= 0")->where('aa.relied_state!=0')->where('aa.relied_district!=0');
        }elseif($number == 6)
        {
            $builder->select(['aa.transfer_state','aa.transfer_district']); 
            $builder->join('transfer_to_details aa ', 'a.lower_court_id=aa.lowerct_id and aa.display = \'Y\'');
            $builder->join('lowerct b', "a.l_dist = b.l_dist
            AND a.l_state = b.l_state
            AND a.lct_casetype = b.lct_casetype
            AND cast(a.lct_caseno as BIGINT) = cast(b.lct_caseno as BIGINT)
            AND a.lct_caseyear = b.lct_caseyear
            AND a.ct_code = b.ct_code");
            $builder->join('transfer_to_details bb ', 'b.lower_court_id=bb.lowerct_id and bb.display = \'Y\'');
            $builder->where('a.l_dist !=0')->where('a.l_state!=0')
            ->where('a.lct_casetype!=0')->where("a.lct_caseno!= '0'")->where('a.lct_caseyear!=0')->where('a.ct_code!=0');
        }                   
        else{

            $zero = "  AND trim(leading '0' from trim(a.lct_caseno)) = trim(leading '0' from trim(b.lct_caseno))  AND a.lct_caseyear = b.lct_caseyear  ";
            $builder->join('lowerct b', "a.lct_dec_dt = b.lct_dec_dt AND a.l_dist = b.l_dist AND a.l_state = b.l_state 
            AND a.ct_code = b.ct_code  $zero");
            $builder->where("a.lct_dec_dt IS NOT NULL ");
            
        }

                
         

        $builder->join('master.state c', 'a.l_state = cast(c.id_no as bigint) AND c.display = \'Y\'','left');
        $builder->join('main d', 'd.diary_no = b.diary_no','left');
        $builder->join('master.casetype e', "e.casecode = SUBSTRING(d.fil_no::TEXT FROM 1 FOR '2')::INTEGER AND e.display = 'Y'", 'left');
        $builder->join('master.m_from_court f', 'f.id = a.ct_code AND f.display = \'Y\'','left');

        $builder->where([
            'a.diary_no' => $diaryNo,           
            'a.lw_display' => 'Y',
            'b.lw_display' => 'Y',
              // Added this condition based on your code
        ]);
        $builder->where("a.diary_no !=  b.diary_no");
       
       // echo $builder->getCompiledSelect();
        // die;
        $query = $builder->get();

        return $query->getResultArray();
    }

    public function getLowerCourtDetails($diaryNo)
    {
        $db = \Config\Database::connect();

        $sql = "SELECT 
                    a.lct_dec_dt, 
                    a.lct_caseno, 
                    a.lct_caseyear, 
                    ct.short_description AS type_sname
                FROM lowerct a  
                LEFT JOIN master.casetype ct 
                    ON ct.casecode = a.lct_casetype 
                    AND ct.display = 'Y'
                WHERE a.diary_no = ? 
                    AND a.lw_display = 'Y' 
                    AND a.ct_code = 4 
                    AND a.is_order_challenged = 'Y'
                ORDER BY a.lct_dec_dt, a.lct_caseno";

        $query = $db->query($sql, [$diaryNo]); // Prevents SQL Injection
        return $query->getResultArray(); // Returns results as an array
    }



    public function getSubheadings($heardt_case)
    {
        $db = \Config\Database::connect();

        $subhead = $heardt_case["subhead"];

        $sql = "
            SELECT *
            FROM master.subheading
            WHERE
                CASE
                    WHEN ? = ANY(string_to_array(?, ',')::int[]) THEN stagecode = ANY(string_to_array(?, ',')::int[])
                    ELSE stagecode = ?
                END
                AND display = 'Y'
            ORDER BY
                CASE
                    WHEN stagecode = ? THEN 0
                    ELSE 1
                END
        ";

        $query = $db->query($sql, [$subhead, $subhead, $subhead, $subhead, $subhead]);

        return $query->getRowArray();
    }


    public function get_previous_stage($filno_1, $sc)
    {
        $sql = "SELECT 
                    (SELECT stagename FROM master.subheading WHERE stagecode = a1.subhead) AS pstage 
                FROM (
                    SELECT subhead, ent_dt
                    FROM heardt
                    WHERE diary_no = :filno_1: AND subhead <> :sc:
                    
                    UNION
                    
                    SELECT subhead, ent_dt
                    FROM last_heardt
                    WHERE diary_no = :filno_1: AND subhead <> :sc:
                ) a1 
                ORDER BY a1.ent_dt DESC 
                LIMIT 1";

        $query = $this->db->query($sql, [
            'filno_1' => $filno_1,
            'sc'      => $sc
        ]);

        $result = $query->getRowArray(); // Get single row as an associative array

        return $result ? $result['pstage'] : ''; // Return stage or empty string if not found
    }



    public function getJoinedDetails($diary_no)
    {

       $sql = "SELECT * FROM (
                SELECT
                    o.diary_no AS diary_no,
                    SUBSTR(o.diary_no::TEXT, 1, LENGTH(o.diary_no::TEXT) - 4) AS d_no,
                    SUBSTR(o.diary_no::TEXT, LENGTH(o.diary_no::TEXT) - 3) AS d_year,
                    o.jm AS jm,
                    TO_CHAR(o.dated::DATE, 'YYYY-MM-DD') AS dated,
                    m.pet_name,
                    m.res_name,
                    pet.name AS pet_adv_id,
                    res.name AS res_adv_id,
                    m.active_fil_no,
                    short_description,
                    active_casetype_id,
                    active_reg_year,
                    CASE
                        WHEN o.jt = 'rop' THEN 'ROP'
                        WHEN o.jt = 'judgment' THEN 'Judgement'
                        WHEN o.jt = 'or' THEN 'Office Report'
                    END AS jo,
                    CASE
                        WHEN (m.diary_no = cast(m.conn_key as BIGINT) OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL) THEN 'M'
                        ELSE 'C'
                    END AS main_or_connected
                FROM
                    tempo o
                LEFT JOIN main m ON SUBSTR(o.diary_no::TEXT, 1, LENGTH(o.diary_no::TEXT) - 4) || SUBSTR(o.diary_no::TEXT, LENGTH(o.diary_no::TEXT) - 3) = cast(m.diary_no as TEXT)
                LEFT JOIN master.bar pet ON m.pet_adv_id = pet.bar_id
                LEFT JOIN master.bar res ON m.res_adv_id = res.bar_id
                LEFT JOIN master.casetype c ON m.active_casetype_id = c.casecode
                WHERE
                    m.diary_no IN (".$diary_no.")
                    
                    
                    
                union
                
                SELECT
                    o.diary_no AS diary_no,
                    SUBSTR(o.diary_no::TEXT, 1, LENGTH(o.diary_no::TEXT) - 4) AS d_no,
                    SUBSTR(o.diary_no::TEXT, LENGTH(o.diary_no::TEXT) - 3) AS d_year,
                    o.pdfname AS jm,
                    TO_CHAR(o.orderdate, 'YYYY-MM-DD') AS dated,
                    m.pet_name,
                    m.res_name,
                    pet.name AS pet_adv_id,
                    res.name AS res_adv_id,
                    m.active_fil_no,
                    short_description,
                    active_casetype_id,
                    active_reg_year,
                    CASE
                        WHEN o.type = 'O' THEN 'ROP'
                        WHEN o.type = 'J' THEN 'Judgement'
                    END AS jo,
                    CASE
                        WHEN (m.diary_no = m.conn_key::BIGINT OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL) THEN 'M'
                        ELSE 'C'
                    END AS main_or_connected
                FROM
                    ordernet o
                LEFT JOIN main m ON o.diary_no = m.diary_no
                LEFT JOIN master.bar pet ON m.pet_adv_id = pet.bar_id
                LEFT JOIN master.bar res ON m.res_adv_id = res.bar_id
                LEFT JOIN master.casetype c ON m.active_casetype_id = c.casecode
                WHERE
                    o.diary_no IN (".$diary_no.")
                    
                    
                union
                
                SELECT
                    o.dn AS diary_no,
                    SUBSTR(o.dn::TEXT, 1, LENGTH(o.dn::TEXT) - 4) AS d_no,
                    SUBSTR(o.dn::TEXT, LENGTH(o.dn::TEXT) - 3) AS d_year,
                    'ropor/rop/all/' || o.pno || '.pdf' AS jm,
                    TO_CHAR(o.orderDate, 'YYYY-MM-DD') AS dated,
                    m.pet_name,
                    m.res_name,
                    pet.name AS pet_adv_id,
                    res.name AS res_adv_id,
                    m.active_fil_no,
                    short_description,
                    active_casetype_id,
                    active_reg_year,
                    'ROP' AS jo,
                    CASE
                        WHEN (m.diary_no = m.conn_key::BIGINT OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL) THEN 'M'
                        ELSE 'C'
                    END AS main_or_connected
                FROM
                    rop_text_web.old_rop o
                LEFT JOIN main m ON o.dn = m.diary_no
                LEFT JOIN master.bar pet ON m.pet_adv_id = pet.bar_id
                LEFT JOIN master.bar res ON m.res_adv_id = res.bar_id
                LEFT JOIN master.casetype c ON m.active_casetype_id = c.casecode
                WHERE
                    o.dn IN (".$diary_no.")
                    
                    
                UNION
                SELECT
                    o.dn AS diary_no,
                    SUBSTR(o.dn::TEXT, 1, LENGTH(o.dn::TEXT) - 4) AS d_no,
                    SUBSTR(o.dn::TEXT, LENGTH(o.dn::TEXT) - 3) AS d_year,
                    'judis/' || o.filename || '.pdf' AS jm,
                    TO_CHAR(o.juddate::DATE, 'YYYY-MM-DD') AS dated,
                    m.pet_name,
                    m.res_name,
                    pet.name AS pet_adv_id,
                    res.name AS res_adv_id,
                    m.active_fil_no,
                    short_description,
                    active_casetype_id,
                    active_reg_year,
                    'Judgment' AS jo,
                    CASE
                        WHEN (m.diary_no = m.conn_key::BIGINT OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL) THEN 'M'
                        ELSE 'C'
                    END AS main_or_connected
                FROM
                    scordermain o
                LEFT JOIN main m ON o.dn = m.diary_no
                LEFT JOIN master.bar pet ON m.pet_adv_id = pet.bar_id
                LEFT JOIN master.bar res ON m.res_adv_id = res.bar_id
                LEFT JOIN master.casetype c ON m.active_casetype_id = c.casecode
                WHERE
                    o.dn IN (".$diary_no.")
                union
                
                SELECT
                    o.dn AS diary_no,
                    SUBSTR(o.dn::TEXT, 1, LENGTH(o.dn::TEXT) - 4) AS d_no,
                    SUBSTR(o.dn::TEXT, LENGTH(o.dn::TEXT) - 3) AS d_year,
                    'bosir/orderpdf/' || o.pno || '.pdf' AS jm,
                    TO_CHAR(o.orderdate::DATE, 'YYYY-MM-DD') AS dated,
                    m.pet_name,
                    m.res_name,
                    pet.name AS pet_adv_id,
                    res.name AS res_adv_id,
                    m.active_fil_no,
                    short_description,
                    active_casetype_id,
                    active_reg_year,
                    'ROP' AS jo,
                    CASE
                        WHEN (m.diary_no = m.conn_key::BIGINT OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL) THEN 'M'
                        ELSE 'C'
                    END AS main_or_connected
                FROM
                    rop_text_web.ordertext o
                LEFT JOIN main m ON o.dn = m.diary_no
                LEFT JOIN master.bar pet ON m.pet_adv_id = pet.bar_id
                LEFT JOIN master.bar res ON m.res_adv_id = res.bar_id
                LEFT JOIN master.casetype c ON m.active_casetype_id = c.casecode
                WHERE
                    o.dn IN (".$diary_no.") AND o.display = 'Y'
                    
                    
                UNION
                SELECT
                    o.dn AS diary_no,
                    SUBSTR(o.dn::TEXT, 1, LENGTH(o.dn::TEXT) - 4) AS d_no,
                    SUBSTR(o.dn::TEXT, LENGTH(o.dn::TEXT) - 3) AS d_year,
                    'bosir/orderpdfold/' || o.pno || '.pdf' AS jm,
                    TO_CHAR(o.orderdate::DATE, 'YYYY-MM-DD') AS dated,
                    m.pet_name,
                    m.res_name,
                    pet.name AS pet_adv_id,
                    res.name AS res_adv_id,
                    m.active_fil_no,
                    short_description,
                    active_casetype_id,
                    active_reg_year,
                    'ROP' AS jo,
                    CASE
                        WHEN (m.diary_no = m.conn_key::BIGINT OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL) THEN 'M'
                        ELSE 'C'
                    END AS main_or_connected
                FROM
                    rop_text_web.oldordtext o
                LEFT JOIN main m ON o.dn = m.diary_no
                LEFT JOIN master.bar pet ON m.pet_adv_id = pet.bar_id
                LEFT JOIN master.bar res ON m.res_adv_id = res.bar_id
                LEFT JOIN master.casetype c ON m.active_casetype_id = c.casecode
                WHERE
                    o.dn IN (".$diary_no.")
            ) AS tbl1
            ORDER BY
                tbl1.dated DESC
        ";

        
        $query = $this->db->query($sql);
        return $query->getResultArray();
    }


}
