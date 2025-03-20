<?php

namespace App\Models\Judicial\Sentence;

use CodeIgniter\Model;

class Model_sentence extends Model
{


    public function get_report_details($data)
    {
        $case_status = $data['case_status'];
        $jail_bail = $data['jail_bail'];
        $cs_qry = $sp_qry = $cs_qry_d = '';

        if ($jail_bail == 'A') {
            $sp_qry = ['C', 'B'];
        } elseif ($jail_bail == 'C') {
            $sp_qry = ['C'];
        } elseif ($jail_bail == 'B') {
            $sp_qry = ['B'];
        }
        $response = array();
        if ($case_status == 'A') {
            $cs_qry = ['P', 'D'];
            // $cs_qry_d = ['D'];
            $response1 = $this->get_report($case_status, $jail_bail);
            $response_d = $this->get_report($case_status, $jail_bail, '_a');
            $response = array_merge($response1, $response_d);
        } elseif ($case_status == 'P') {
            $cs_qry = ['P'];
            $response = $this->get_report($case_status, $jail_bail);
        } elseif ($case_status == 'D') {
            $cs_qry_d = ['D'];
            $response = $this->get_report($case_status, $jail_bail, '_a');
        }
        return $response;
    }
    public function get_report($case_status, $jail_bail, $is_archival_table = '')
    {
        $ucode = session()->get('login')['usercode'];
        // $case_status=$data['case_status'];
        // $jail_bail=$data['jail_bail'];
        $cs_qry = $sp_qry = $cs_qry_d = '';
        if ($case_status == 'A') {
            $cs_qry = ['P', 'D'];
            $cs_qry_d = ['D'];
        } elseif ($case_status == 'P') {
            $cs_qry = ['P'];
        } elseif ($case_status == 'D') {
            $cs_qry_d = ['D'];
        }

        if ($jail_bail == 'A') {
            $sp_qry = ['C', 'B'];
        } elseif ($jail_bail == 'C') {
            $sp_qry = ['C'];
        } elseif ($jail_bail == 'B') {
            $sp_qry = ['B'];
        }
        $row_desg = $this->get_user_details($ucode);
        $condition = '';
        if ($ucode == 1) {
            $condition = '';
        } else if ($row_desg['usertype'] == 50 or $row_desg['usertype'] == 51 or $row_desg['usertype'] == 17) {
            $condition = "m.dacode=$ucode";
        } else if ($row_desg['usertype'] == 14 or $row_desg['usertype'] == 3 or $row_desg['usertype'] == 4 or $row_desg['usertype'] == 6 or $row_desg['usertype'] == 9) {
            $condition = "u.section=" . $row_desg['section'];
        }

        $builder = $this->db->table("sentence_undergone su");
        $builder->select("sp.diary_no, 
            case when (su.status = 'C') then 'In Jail' else 'On Bail' end as status,reg_no_display,
            CONCAT(m.pet_name , ' Vs ',m.res_name) AS cause_title,   
            u.name,
            u.empid,
            us.section_name as section_name,
            case when (m.c_status = 'P') then 'Pending' else 'Disposed' end as c_status,
            case when (m.mf_active = 'F') then 'Final' else 'Misc' end as mf_active,
        ");
        $builder->join('sentence_period sp', 'sp.id = su.sentence_period_id', 'left', false);
        $builder->join("main$is_archival_table m", 'm.diary_no = sp.diary_no');
        $builder->join('master.users u', 'm.dacode=u.usercode', 'left', false);
        $builder->join('master.usersection us', 'u.section=us.id', 'left', false);

        $builder->where('su.sen_display', 'Y');
        if (!empty($cs_qry) && !empty($case_status)) {
            $builder->whereIn('m.c_status', $cs_qry);
        }

        if (!empty($sp_qry) && !empty($jail_bail)) {
            $builder->whereIn('su.status', $sp_qry);
        }
        if (!empty($condition) && !empty($condition)) {
            $builder->where($condition);
        }
        $builder->where("(date(to_date) <= '1970-01-01' OR date(to_date) >= current_date)");
        $builder->groupBy('sp.diary_no,su.status,m.reg_no_display,m.pet_name,m.res_name,u.name,u.empid,us.section_name,m.c_status,m.mf_active,
                               m.diary_no_rec_date,m.diary_no', false);
        $builder->orderBy("section_name,empid,su.status, m.c_status,to_char(m.diary_no_rec_date,'YYYY'), CAST(LEFT(CAST(m.diary_no AS TEXT), -4) AS INTEGER)");

        // echo $builder->getCompiledSelect();die;

        $query = $builder->get();

        //$query=$this->db->getLastQuery();echo (string) $query;exit();
        return $query->getResultArray();
    }
























    public function get_report_old($case_status, $jail_bail)
    {
        $ucode = session()->get('login')['usercode'];
        // $case_status=$data['case_status'];
        // $jail_bail=$data['jail_bail'];
        $cs_qry = $sp_qry = $cs_qry_d = '';
        if ($case_status == 'A') {
            $cs_qry = ['P', 'D'];
            $cs_qry_d = ['D'];
        } elseif ($case_status == 'P') {
            $cs_qry = ['P'];
        } elseif ($case_status == 'D') {
            $cs_qry_d = ['D'];
        }

        if ($jail_bail == 'A') {
            $sp_qry = ['C', 'B'];
        } elseif ($jail_bail == 'C') {
            $sp_qry = ['C'];
        } elseif ($jail_bail == 'B') {
            $sp_qry = ['B'];
        }
        $row_desg = $this->get_user_details($ucode);
        $condition = '';
        if ($ucode == 1) {
            $condition = '';
        } else if ($row_desg['usertype'] == 50 or $row_desg['usertype'] == 51 or $row_desg['usertype'] == 17) {
            $condition = "m.dacode=$ucode || m1.dacode=$ucode";
        } else if ($row_desg['usertype'] == 14 or $row_desg['usertype'] == 3 or $row_desg['usertype'] == 4 or $row_desg['usertype'] == 6 or $row_desg['usertype'] == 9) {
            $condition = "u.section=" . $row_desg['section'] . " || u1.section=" . $row_desg['section'];
        }




        $builder = $this->db->table("sentence_undergone su");
        $builder->select("sp.diary_no, 
        case when (su.status = 'C') then 'In Jail' else 'On Bail' end as status,
        case when (m.reg_no_display is null) then m1.reg_no_display else m.reg_no_display end as reg_no_display,
        CONCAT((case when(m.pet_name is null) then m1.pet_name else m.pet_name end) , ' Vs ', (case when (m.res_name is null) then m1.res_name else m.res_name end)) AS cause_title,   
        case when (u.name is null) then u1.name else u.name end as name,
        case when (u.empid is null) then u1.empid else u.empid end as empid,
        case when (us.section_name is null) then us1.section_name else us.section_name end as section_name,
        case when (m.c_status = 'P') then 'Pending' else 'Disposed' end as c_status,
        case when (m.mf_active = 'F') then 'Final' else 'Misc' end as mf_active,
        ");
        $builder->join('sentence_period sp', 'sp.id = su.sentence_period_id', 'left', false);
        $builder->join('main m', 'm.diary_no = sp.diary_no', 'left', false);
        $builder->join('master.users u', 'm.dacode=u.usercode', 'left', false);
        $builder->join('master.usersection us', 'u.section=us.id', 'left', false);


        $builder->join('main_a m1', 'm1.diary_no = sp.diary_no', 'left', false);
        $builder->join('master.users u1', 'm1.dacode=u1.usercode', 'left', false);
        $builder->join('master.usersection us1', 'u1.section=us1.id', 'left', false);

        $builder->where('su.sen_display', 'Y');
        if (!empty($cs_qry) && !empty($case_status)) {
            $builder->whereIn('m.c_status', $cs_qry);
        }
        if ((!empty($cs_qry_d) && !empty($case_status)) && ($case_status == 'A')) {
            $builder->WhereIn('m1.c_status', $cs_qry_d);
        } elseif ((!empty($cs_qry_d) && !empty($case_status)) && ($case_status == 'D')) {
            $builder->WhereIn('m1.c_status', $cs_qry_d);
        }
        if (!empty($sp_qry) && !empty($jail_bail)) {
            $builder->whereIn('su.status', $sp_qry);
        }
        if (!empty($condition) && !empty($condition)) {
            $builder->where($condition);
        }
        $builder->where("(date(to_date) <= '1970-01-01' OR date(to_date) >= current_date)");
        $builder->groupBy('sp.diary_no,su.status,m.reg_no_display,m1.reg_no_display,m.pet_name,m1.pet_name,m.res_name,m1.res_name,
                               u.name,u1.name,u.empid,u1.empid,us.section_name,us1.section_name,m.c_status,m1.c_status,m.mf_active,
                               m1.mf_active,m.diary_no_rec_date,m1.diary_no_rec_date,m.diary_no,m1.diary_no', false);
        $builder->orderBy("section_name,empid,su.status, m.c_status,to_char(m.diary_no_rec_date,'YYYY'), CAST(LEFT(CAST(m.diary_no AS TEXT), -4) AS INTEGER)");
        $query = $builder->get();

        //$query=$this->db->getLastQuery();echo (string) $query;exit();
        return $query->getResultArray();
    }
    public function get_user_details($ucode)
    {
        $query = $this->db->table('master.users u')
            ->select('u.*, section_name')
            ->join('master.usersection us', 'u.section = us.id')
            ->where('usercode', $ucode)
            ->get();

        return $query->getRowArray();
    }

    public function get_sentence_details()
    {
        $response = $this->get_sentence();
        /*$response1= $this->get_sentence();
        $response_d= $this->get_sentence('_a');
        $response=array_merge($response1,$response_d);*/
        return $response;
    }

    public function get_sentence($is_archival_table = '')
    {
        $ucode = session()->get('login')['usercode'];
        $row_desg = $this->get_user_details($ucode);
        $condition = '';
        if ($ucode == 1) {
            $condition = '';
        } else if ($row_desg['usertype'] == 50 or $row_desg['usertype'] == 51 or $row_desg['usertype'] == 17) {
            $condition = "and m.dacode=$ucode";
        } else if ($row_desg['usertype'] == 14 or $row_desg['usertype'] == 3 or $row_desg['usertype'] == 4 or $row_desg['usertype'] == 6 or $row_desg['usertype'] == 9) {
            $condition = "and u.section=" . $row_desg['section'];
        }
        $query = "select reg,diary_number,diary_year,diary_no,cause,accused,da,section,awarded,sum(undergone) from(
select
  m.reg_no_display as reg,
  cast(left(m.diary_no::text, LENGTH(m.diary_no::text) - 4) as INTEGER) as diary_number,
  cast(right(m.diary_no::text, 4) as INTEGER) as diary_year,
  sp.diary_no,
  CONCAT(pet_name, ' ', (case when pno = 2 then 'and anr.' when pno > 2 then 'and ors.' else '' end), ' vs ', res_name, ' ', (case when rno = 2 then 'and anr.' when rno > 2 then 'and ors.' else '' end)) cause,
  partyname as accused,
  concat(u.name, '[', u.empid, ']') as da,
  us.section_name as section,
  concat(sentence_yr, ' year ', sentence_mth, ' mnth') as awarded,
  undergone
from
  sentence_period sp
left join main m on
  m.diary_no = sp.diary_no
left join master.users u on
  m.dacode = u.usercode
left join master.usersection us on
  u.section = us.id
left join party p on
  p.auto_generated_id = sp.accused_id
right join ( select sentence_period_id,sum( (case when to_date = '1970-01-01' then current_date else to_date end) - (case when frm_date = '1970-01-01' then null else frm_date end)) as undergone
  from sentence_undergone
  where status in ('C', 'U')
  group by sentence_period_id,to_date,frm_date) und on und.sentence_period_id = sp.id where m.c_status = 'P' $condition 
) temp group by reg,diary_number,diary_year,diary_no,cause,accused,da,section,awarded order by diary_year,diary_number";

        $query = $this->db->query($query);
        return $query->getResultArray();


        $builder = $this->db->table("sentence_period sp");
        $builder->select("m.reg_no_display as reg,
        CAST(LEFT(sp.diary_no::TEXT, LENGTH(sp.diary_no::TEXT) - 4) AS INTEGER) AS diary_number,
        CAST(RIGHT(sp.diary_no::TEXT, 4) AS INTEGER) AS diary_year,
        sp.diary_no,
        CONCAT(pet_name, ' ', 
            (CASE
                WHEN pno = 2 THEN 'and anr.'
                WHEN pno > 2 THEN 'and ors.'
                ELSE ''
            END),
            ' vs ',
            res_name,' ',
            (CASE
                WHEN rno = 2 THEN 'and anr.'
                WHEN rno > 2 THEN 'and ors.'
                ELSE ''
            END)) cause,
            partyname as accused,
            concat(u.name,'[',u.empid,']') as da,
            us.section_name as section,
            concat(sentence_yr,' year ',sentence_mth,' mnth') as awarded,
            undergone
        ");
        $builder->join("main$is_archival_table m", 'm.diary_no=sp.diary_no', 'left', false);
        $builder->join('master.users u', 'm.dacode=u.usercode', 'left', false);
        $builder->join('master.usersection us', 'u.section=us.id', 'left', false);
        $builder->join('party p', 'p.auto_generated_id=sp.accused_id', 'left', false);
        $builder->join("
(select sentence_period_id,
sum(
(case when to_date='1970-01-01' then current_date else to_date end) -
(case when frm_date='1970-01-01' then null else frm_date end)) as undergone
from sentence_undergone 
where status in ('C', 'U')
group by sentence_period_id,to_date,frm_date) und", 'und.sentence_period_id=sp.id', 'right', false);

        $builder->where('m.c_status', 'P');
        if (!empty($condition) && !empty($condition)) {
            $builder->where($condition);
        }
        //$builder->groupBy('reg,m.diary_no,sp.diary_no,p.partyname,u.name,u.empid,us.section_name,sp.sentence_yr,sp.sentence_mth,und.undergone',false );
        //$builder->orderBy("section,da");
        $builder->orderBy("sp.diary_no,section,da");
        $query = $builder->get();

        //$query=$this->db->getLastQuery();echo (string) $query;exit();
        return $query->getResultArray();
    }

    public function get_from_court_by_diary_no($diary_no, $ct_code = null)
    {
        $builder = $this->db->table('lowerct a');
        $builder->distinct();
        $builder->select('ct_code,court_name');
        $builder->join('master.m_from_court b', 'a.ct_code=b.id', false);
        $builder->where('lw_display', 'Y');
        $builder->where('display', 'Y');
        $builder->where('diary_no', $diary_no);
        if (!empty($ct_code)) {
            $builder->where('ct_code', $ct_code);
        }
        $query = $builder->get();
        return $query->getResultArray();
    }
    public function get_state_name($diary_no, $ddl_court = null)
    {

        $builder = $this->db->table('master.state a');
        $builder->distinct();
        $builder->select('id_no,name');
        $builder->join('lowerct b', 'a.id_no=b.l_state', false);
        $builder->where('district_code', 0);
        $builder->where('sub_dist_code', 0);
        $builder->where('village_code', 0);
        $builder->where('sci_state_id !=', 0);
        $builder->where('lw_display', 'Y');
        $builder->where('display', 'Y');
        $builder->where('diary_no', $diary_no);
        if (!empty($ddl_court)) {
            $builder->where('ct_code', $ddl_court);
        }
        $builder->orderBy('name', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_sentence_bench($params = array())
    {
        $output = false;
        if (isset($params) && !empty($params) && is_array($params)) {
            if (isset($params['court_type']) && !empty($params['court_type']) && !empty($params['diary_no']) && !empty($params['cmis_state_id'])) {
                $court_type = (int)$params['court_type'];
                if ($court_type != 3) {
                    $court_type = 2;
                }
                switch ($court_type) {
                    case 3:
                        $builder = $this->db->table('master.state a');
                        $builder->distinct();
                        $builder->select('a.id_no as id,a.name as agency_name');
                        $builder->join('lowerct b', 'a.id_no=b.l_dist');
                        $builder->where('a.sub_dist_code', 0);
                        $builder->where('a.village_code', 0);
                        $builder->where('a.district_code !=', 0);
                        $builder->where('b.diary_no', $params['diary_no']);
                        $builder->where('b.ct_code', $params['court_type']);
                        $builder->where('b.l_state', $params['cmis_state_id']);
                        $builder->where('b.lw_display', 'Y');
                        $builder->where('a.display', 'Y');
                        $builder->orderBy('agency_name', 'ASC');
                        $query = $builder->get();
                        $output = $query->getResultArray();
                        break;
                    case 2:
                        $builder = $this->db->table('master.ref_agency_code a');
                        $builder->distinct();
                        $builder->select('id,agency_name,short_agency_name');
                        $builder->join('lowerct b', 'b.ct_code=cast(a.agency_or_court as INTEGER)  and a.cmis_state_id=b.l_state and a.id=b.l_dist');
                        $builder->where('a.is_deleted', 'f');
                        $builder->where('b.lw_display', 'Y');
                        $builder->where('a.agency_or_court', $params['court_type']);
                        $builder->where('a.cmis_state_id', $params['cmis_state_id']);
                        $builder->where('b.diary_no', $params['diary_no']);
                        $builder->orderBy('agency_name', 'ASC');
                        $query = $builder->get();
                        $output = $query->getResultArray();
                        break;
                    default:
                        $output = false;
                }
            }
        }
        return $output;
    }
    public function get_tot_cases($params)
    {
        $output = array();
        if (isset($params) && !empty($params) && is_array($params)) {
            if (isset($params['court_type']) && !empty($params['court_type']) && !empty($params['diary_no']) && !empty($params['cmis_state_id'])) {
                $builder = $this->db->table('lowerct');
                $builder->distinct();
                $builder->select('lct_casetype,lct_caseno,lct_caseyear,lower_court_id,ct_code');
                $builder->where('ct_code', $params['court_type']);
                $builder->where('l_state', $params['cmis_state_id']);
                $builder->where('l_dist', $params['ddl_bench']);
                $builder->where('diary_no', $params['diary_no']);
                $builder->where('is_order_challenged', 'Y');
                $builder->where('lw_display', 'Y');
                $builder->orderBy('lct_caseno', 'ASC');
                $builder->orderBy('lct_casetype', 'ASC');
                $query = $builder->get();
                $get_output = $query->getResultArray();

                if (!empty($get_output)) {
                    foreach ($get_output as $row) {
                        $get_casetype = $this->get_casetype($params['court_type'], $row['lct_casetype']);
                        if (!empty($get_casetype)) {
                            $display_name = $get_casetype['type_sname'] . '-' . intval($row['lct_caseno']) . '-' . $row['lct_caseyear'];
                            $output[] = [
                                'lower_court_id' => $row['lower_court_id'],
                                'display_name' => $display_name
                            ];
                        }
                    }
                }
            }
        }
        return $output;
    }

    public function get_casetype($court_type, $lct_casetype)
    {
        if ($court_type == 4) {
            $builder = $this->db->table('master.casetype');
            $builder->distinct();
            $builder->select('skey as type_sname');
            $builder->where('display', 'Y');
            $builder->where('casecode', $lct_casetype);
            $builder->orderBy('type_sname', 'ASC');
        } else {
            $builder = $this->db->table('master.lc_hc_casetype');
            $builder->distinct();
            $builder->select('type_sname');
            $builder->where('display', 'Y');
            $builder->where('lccasecode', $lct_casetype);
            $builder->orderBy('type_sname', 'ASC');
        }
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function get_tot_accused($params)
    {
        if (!empty($params['ddl_case_no'])) {
            $builder = $this->db->table('party_lowercourt a');
            $builder->distinct();
            $builder->select('party_id,partyname');
            $builder->join('party b', 'a.party_id=b.auto_generated_id');
            $builder->where('a.lowercase_id', $params['ddl_case_no']);
            $builder->where('display', 'Y');
            $builder->where('pflag', 'P');
            $builder->orderBy('partyname', 'ASC');
            $query = $builder->get();
            return $query->getResultArray();
        } else {
            return array();
        }
    }
    public function get_lct_dec_dt($params)
    {
        if (!empty($params['ddl_case_no'])) {
            $builder = $this->db->table('lowerct');
            $builder->select("to_char(lct_dec_dt,'DD-MM-YYYY') AS lct_dec_dt");
            $builder->where('lower_court_id', $params['ddl_case_no']);
            $builder->where('lw_display', 'Y');
            $builder->orderBy('lct_dec_dt', 'ASC');
            $query = $builder->get();
            return $query->getRowArray();
        } else {
            return array();
        }
    }
    public function get_details($params)
    {
        if (!empty($params['ddl_case_no']) && !empty($params['diary_no']) && !empty($params['ddl_tot_accused'])) {
            $builder = $this->db->table('sentence_period');
            $builder->select('id,sentence_yr,sentence_mth');
            $builder->where('diary_no', $params['diary_no']);
            $builder->where('lower_court_id', $params['ddl_case_no']);
            $builder->where('accused_id', $params['ddl_tot_accused']);
            $builder->where('display', 'Y');
            $query = $builder->get();
            return $query->getRowArray();
        } else {
            return array();
        }
    }
    public function get_max_to_dt($sentence_period_id)
    {
        if (!empty($sentence_period_id)) {
            $builder = $this->db->table('sentence_undergone');
            $builder->select("(case when MAX(to_date)='1970-01-01' then null else MAX(to_date) end) as res_max_to_dt");
            $builder->where('sentence_period_id', $sentence_period_id);
            $builder->where('sen_display', 'Y');
            $query = $builder->get();
            return $query->getRowArray();
        } else {
            return array();
        }
    }
    public function get_sentence_undergone_list($sentence_period_id)
    {
        if (!empty($sentence_period_id)) {
            $builder = $this->db->table('sentence_undergone');
            $builder->select("id,status,frm_date,to_date,
                                    CASE 
                                        WHEN (to_date = '1970-01-01' AND status = 'C') THEN 'Presently in Jail' 
                                        ELSE (to_date - frm_date)::int::text
                                    END AS difference,
                                    rem 
                             ");
            $builder->where('sen_display', 'Y');
            $builder->where('sentence_period_id', $sentence_period_id);
            $query = $builder->get();
            return $query->getResultArray();
        } else {
            return array();
        }
    }
    public function update_sentence_undergone($updateData, $sentence_period_id)
    {
        if (!empty($sentence_period_id) && !empty($updateData)) {
            $builder = $this->db->table('sentence_undergone');
            $builder->where('id', $sentence_period_id);
            if ($builder->update($updateData)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function is_sentence_period($params)
    {
        if (!empty($params) && !empty($params['diary_no']) && !empty($params['ddl_case_no']) && !empty($params['ddl_tot_accused'])) {
            $builder = $this->db->table('sentence_period a');
            $builder->select("MAX(frm_date) as frm_date,MAX(to_date) as to_date");
            $builder->join('sentence_undergone b', 'a.id=b.sentence_period_id');
            $builder->where('a.display', 'Y');
            $builder->where('b.sen_display', 'Y');
            $builder->where('diary_no', $params['diary_no']);
            $builder->where('lower_court_id', $params['ddl_case_no']);
            $builder->where('accused_id', $params['ddl_tot_accused']);
            $query = $builder->get();
            return $query->getRowArray();
        } else {
            return array();
        }
    }
    public function get_sentence_period($params)
    {
        if (!empty($params) && !empty($params['diary_no']) && !empty($params['ddl_case_no']) && !empty($params['ddl_tot_accused'])) {
            $builder = $this->db->table('sentence_period');
            $builder->select("*");
            $builder->where('display', 'Y');
            $builder->where('diary_no', $params['diary_no']);
            $builder->where('lower_court_id', $params['ddl_case_no']);
            $builder->where('accused_id', $params['ddl_tot_accused']);
            $query = $builder->get();
            return $query->getRowArray();
        } else {
            return array();
        }
    }
}
