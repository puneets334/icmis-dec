<?php

namespace App\Models\Reports\Court;

use CodeIgniter\Model;

class Model_high_court extends Model
{


    public function __construct(){
        parent::__construct();
    }
    function get_caveat_to_caveat($data) {
        $response1=$this->get_caveat_to_caveat_details($data);
        $response2=$this->get_caveat_to_caveat_details($data,'_a');
        $final_array=array_merge($response1,$response2);
        if (!empty($final_array)){
            $final_array=array_SORT_ASC_DESC($final_array,'caveat_no');
        }
        return $final_array;

    }
    function get_caveat_to_caveat_details($data,$is_archival_table='',$is_archival_table2='') {
        $txt_order_date='';
        if($data['txt_order_date']!='')
        {
            $txt_order_date=date('Y-m-d',  strtotime($data['txt_order_date']));
        }
        $cur_date=date('Y-m-d');
        $builder = $this->db->table("caveat_lowerct$is_archival_table as b");
        $builder->distinct();
        $builder->select('LEFT(CAST(b.caveat_no AS TEXT), -4) AS cn,RIGHT(CAST(b.caveat_no AS TEXT), 4) AS cy,
  c.name state_name,b.lct_dec_dt, b.l_dist, b.l_state, b.lct_casetype, b.lct_caseno, b.lct_caseyear, b.caveat_no c_diary, b.ct_code ');
        $builder->select("CASE 
        WHEN b.ct_code = 3 THEN (
                CASE WHEN b.l_state = 490506 THEN (
                    SELECT court_name state_name FROM master.state s
                        LEFT JOIN master.delhi_district_court d ON s.state_code = d.state_code
                        AND s.district_code = d.district_code WHERE s.id_no = b.l_dist AND display = 'Y'
                )ELSE(
                    SELECT name state_name FROM master.state s WHERE s.id_no = b.l_dist AND display = 'Y'
                )
            END
        )
        ELSE (
            SELECT  agency_name FROM master.ref_agency_code r
        WHERE  r.cmis_state_id =b.l_state AND r.id = b.l_dist AND is_deleted = 'f'
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
        $builder->select("b.caveat_no,d.fil_no,d.fil_dt,e.short_description,f.court_name,d.c_status,
        case when (d.diary_no_rec_date is null) then d1.diary_no_rec_date else d.diary_no_rec_date end as diary_no_rec_date,
        case when (d.pet_name is null) then d1.pet_name else d.pet_name end as pet_name,
        case when (d.res_name is null) then d1.res_name else d.res_name end as res_name,
        case when (cd.caveat_no is null) then bar1.name else bar.name end as advocate_name,
        case when (cd.caveat_no is null) then bar1.aor_code else bar.aor_code end as aor_code
        ", false);


        $builder->join('master.casetype e', "e.casecode = b.lct_casetype AND e.display = 'Y'", 'left');
        $builder->join('master.state c', "b.l_state = c.id_no AND c.display = 'Y'", 'left');
        $builder->join("caveat d", 'd.caveat_no = b.caveat_no','left');
        $builder->join("caveat_a d1", 'd1.caveat_no = b.caveat_no','left');
        $builder->join('master.m_from_court f', "f.id=b.ct_code AND f.display = 'Y'", 'left');

        $builder->join("caveat_advocate cd", 'cd.caveat_no = b.caveat_no','left');
        $builder->join("master.bar bar", "cd.advocate_id=bar.bar_id and cd.caveat_no=b.caveat_no and cd.display='Y'",'left');
        $builder->join("caveat_advocate_a cd1", 'cd1.caveat_no = b.caveat_no','left');
        $builder->join("master.bar bar1", "cd1.advocate_id=bar1.bar_id and cd1.caveat_no=b.caveat_no and cd1.display='Y'",'left');

        $builder->where('b.lct_dec_dt is not null' );
        $builder->where('b.lw_display', 'Y');

        if($data['ddl_court']!='') { $builder->where('b.ct_code', $data['ddl_court']); }
        if($txt_order_date!=''){ $builder->where('date(b.lct_dec_dt)',$txt_order_date); }
        if($data['ddl_bench']!='') {  $builder->where('b.l_dist',$data['ddl_bench']);}
        if($data['ddl_st_agncy']!=''){ $builder->where('b.l_state',$data['ddl_st_agncy']);}
        if($data['ddl_ref_case_type']!=''){ $builder->where('b.lct_casetype',$data['ddl_ref_case_type']);}
        if($data['txt_ref_caseno']!=''){ $builder->where('b.lct_caseno',$data['txt_ref_caseno']);}
        if($data['ddl_ref_caseyr']!=''){ $builder->where('b.lct_caseyear',$data['ddl_ref_caseyr']);}
        $builder->orderBy('b.caveat_no','ASC');
        //$builder->orderBy('b.caveat_no','DESC');
        $query = $builder->get();
        // $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();

        return $result;

    }
    function get_caveat_lower_court_total_count($data,$is_archival_table='',$is_archival_table2='')
    {
        $txt_order_date = '';
        $cur_date = date('Y-m-d');
        if ($data['txt_order_date'] != '') {
            $txt_order_date = date('Y-m-d', strtotime($data['txt_order_date']));
        }
        $ddl_court = '';
        $ddl_bench = '';
        $ddl_st_agncy = '';
        $ddl_ref_case_type = '';
        $txt_ref_caseno = '';
        $ddl_ref_caseyr = '';

        $ddl_court_t = '';
        $txt_order_date_t = '';
        $ddl_bench_t = '';
        $ddl_st_agncy_t = '';
        $ddl_ref_case_type_t = '';
        $txt_ref_caseno_t = '';
        $ddl_ref_caseyr_t = '';
        if ($data['ddl_court'] != '') {
            $ddl_court = $data['ddl_court'];
            $ddl_court = " ct_code = $ddl_court";
            //$ddl_court_t = "  a.ct_code = b.ct_code";
            $ddl_court_t = "  ct_code = ct_code";
        }
        if ($txt_order_date != '') {
            $txt_order_date = " and lct_dec_dt = '$txt_order_date'";
            //$txt_order_date_t = " AND a.lct_dec_dt = b.lct_dec_dt";
            $txt_order_date_t = " AND lct_dec_dt = lct_dec_dt";
        }
        if ($data['ddl_bench'] != '') {
            $ddl_bench = $data['ddl_bench'];
            $ddl_bench = " and  l_dist = '$ddl_bench'";
            //$ddl_bench_t = " AND a.l_dist = b.l_dist";
            $ddl_bench_t = " AND l_dist = l_dist";
        }
        if ($data['ddl_st_agncy'] != '') {
            $ddl_st_agncy = $data['ddl_st_agncy'];
            $ddl_st_agncy = " and  l_state = '$ddl_st_agncy'";
            //$ddl_st_agncy_t = " AND a.l_state = b.l_state";
            $ddl_st_agncy_t = " AND l_state = l_state";
        }
        if ($data['ddl_ref_case_type'] != '') {
            $ddl_ref_case_type = $data['ddl_ref_case_type'];
            $ddl_ref_case_type = " and  lct_casetype = '$ddl_ref_case_type'";
            //$ddl_ref_case_type_t = " and a.lct_casetype=b.lct_casetype";
            $ddl_ref_case_type_t = " and lct_casetype=lct_casetype";
        }
        if ($data['txt_ref_caseno'] != '') {
            $txt_ref_caseno = $data['txt_ref_caseno'];
            $txt_ref_caseno = " and  trim(leading '0' from lct_caseno) = '$txt_ref_caseno'";
            //$txt_ref_caseno_t = " and trim(leading '0' from a.lct_caseno)=trim(leading '0' from b.lct_caseno)";
            $txt_ref_caseno_t = " and trim(leading '0' from lct_caseno)=trim(leading '0' from lct_caseno)";
        }
        if ($data['ddl_ref_caseyr'] != '') {
            $ddl_ref_caseyr = $data['ddl_ref_caseyr'];
            $ddl_ref_caseyr = " and  lct_caseyear = '$ddl_ref_caseyr'";
            //$ddl_ref_caseyr_t = " and a.lct_caseyear=b.lct_caseyear";
            $ddl_ref_caseyr_t = " and lct_caseyear=lct_caseyear";
        }
        $query = "
select
	count(b.lower_court_id) as total_count
from
	(
	SELECT
		DISTINCT caveat_no,b.lct_dec_dt,b.l_dist,b.l_state,b.lct_casetype,b.lct_caseno,b.lct_caseyear,b.caveat_no c_diary,b.ct_code
	FROM
		caveat_lowerct b
	WHERE $ddl_court $txt_order_date $ddl_bench $ddl_st_agncy $ddl_ref_case_type $txt_ref_caseno $ddl_ref_caseyr
		
		AND b.lct_dec_dt is not null
		AND b.lw_display = 'Y' )a
JOIN lowerct b ON
	a.ct_code = b.ct_code AND a.l_dist = b.l_dist AND a.l_state = b.l_state and a.lct_caseyear = b.lct_caseyear AND b.lw_display = 'Y' AND b.is_order_challenged = 'Y'
	
	--2nd
	union all
	select
	count(b.lower_court_id) as total_count
from
	(
	SELECT
		DISTINCT caveat_no,b.lct_dec_dt,b.l_dist,b.l_state,b.lct_casetype,b.lct_caseno,b.lct_caseyear,b.caveat_no c_diary,b.ct_code
	FROM
		caveat_lowerct_a b
	WHERE $ddl_court $txt_order_date $ddl_bench $ddl_st_agncy $ddl_ref_case_type $txt_ref_caseno $ddl_ref_caseyr
		
		AND b.lct_dec_dt is not null
		AND b.lw_display = 'Y' )a
JOIN lowerct_a b ON
	a.ct_code = b.ct_code AND a.l_dist = b.l_dist AND a.l_state = b.l_state and a.lct_caseyear = b.lct_caseyear AND b.lw_display = 'Y' AND b.is_order_challenged = 'Y'
	
	-----3rd
	
	union all
	select
	count(b.lower_court_id) as total_count
from
	(
	SELECT
		DISTINCT caveat_no,b.lct_dec_dt,b.l_dist,b.l_state,b.lct_casetype,b.lct_caseno,b.lct_caseyear,b.caveat_no c_diary,b.ct_code
	FROM
		caveat_lowerct_a b
	WHERE $ddl_court $txt_order_date $ddl_bench $ddl_st_agncy $ddl_ref_case_type $txt_ref_caseno $ddl_ref_caseyr
		
		AND b.lct_dec_dt is not null
		AND b.lw_display = 'Y' )a
JOIN lowerct b ON
	a.ct_code = b.ct_code AND a.l_dist = b.l_dist AND a.l_state = b.l_state and a.lct_caseyear = b.lct_caseyear AND b.lw_display = 'Y' AND b.is_order_challenged = 'Y'
	
	--4th
	union all
	select count(b.lower_court_id) as total_count
from
	(
	SELECT
		DISTINCT caveat_no,b.lct_dec_dt,b.l_dist,b.l_state,b.lct_casetype,b.lct_caseno,b.lct_caseyear,b.caveat_no c_diary,b.ct_code
	FROM caveat_lowerct b
	WHERE $ddl_court $txt_order_date $ddl_bench $ddl_st_agncy $ddl_ref_case_type $txt_ref_caseno $ddl_ref_caseyr
		AND b.lct_dec_dt is not null
		AND b.lw_display = 'Y' )a
JOIN lowerct_a b ON
	a.ct_code = b.ct_code AND a.l_dist = b.l_dist AND a.l_state = b.l_state and a.lct_caseyear = b.lct_caseyear AND b.lw_display = 'Y' AND b.is_order_challenged = 'Y'
  
  ";
        //echo $query;exit();
        $query = $this->db->query($query);
        $result = $query->getResultArray();
        $total_count=0;
        if (!empty($result)){
            foreach ($result as $row){
                $total_count=($total_count + $row['total_count']);
            }
        }
        return $total_count;
    }
    function get_content_caveat_lower_court_details($data)
    {
        $offset_left = $data['offset_left'];
        $offset_right = $data['offset_right'];
        $total_count = $data['total_count'];
        $txt_order_date = '';
        if ($data['txt_order_date'] != '') {
            $txt_order_date = date('Y-m-d', strtotime($data['txt_order_date']));
        }
        $cur_date = date('Y-m-d');
        //$query = $this->db->table('caveat_lowerct b')
        $ddl_court = '';
        $txt_order_date = '';
        $ddl_bench = '';
        $ddl_st_agncy = '';
        $ddl_ref_case_type = '';
        $txt_ref_caseno = '';
        $ddl_ref_caseyr = '';

        $ddl_court_t = '';
        $txt_order_date_t = '';
        $ddl_bench_t = '';
        $ddl_st_agncy_t = '';
        $ddl_ref_case_type_t = '';
        $txt_ref_caseno_t = '';
        $ddl_ref_caseyr_t = '';
        if ($data['ddl_court'] != '') {
            $ddl_court = $data['ddl_court'];
            $ddl_court = " ct_code = $ddl_court";
            //$ddl_court_t = "  a.ct_code = b.ct_code";
            $ddl_court_t = "  ct_code = ct_code";
        }
        if ($txt_order_date != '') {
            $txt_order_date = " and lct_dec_dt = '$txt_order_date'";
            //$txt_order_date_t = " AND a.lct_dec_dt = b.lct_dec_dt";
            $txt_order_date_t = " AND lct_dec_dt = lct_dec_dt";
        }
        if ($data['ddl_bench'] != '') {
            $ddl_bench = $data['ddl_bench'];
            $ddl_bench = " and  l_dist = '$ddl_bench'";
            //$ddl_bench_t = " AND a.l_dist = b.l_dist";
            $ddl_bench_t = " AND l_dist = l_dist";
        }
        if ($data['ddl_st_agncy'] != '') {
            $ddl_st_agncy = $data['ddl_st_agncy'];
            $ddl_st_agncy = " and  l_state = '$ddl_st_agncy'";
            //$ddl_st_agncy_t = " AND a.l_state = b.l_state";
            $ddl_st_agncy_t = " AND l_state = l_state";
        }
        if ($data['ddl_ref_case_type'] != '') {
            $ddl_ref_case_type = $data['ddl_ref_case_type'];
            $ddl_ref_case_type = " and  lct_casetype = '$ddl_ref_case_type'";
            //$ddl_ref_case_type_t = " and a.lct_casetype=b.lct_casetype";
            $ddl_ref_case_type_t = " and lct_casetype=lct_casetype";
        }
        if ($data['txt_ref_caseno'] != '') {
            $txt_ref_caseno = $data['txt_ref_caseno'];
            $txt_ref_caseno = " and  trim(leading '0' from lct_caseno) = '$txt_ref_caseno'";
            //$txt_ref_caseno_t = " and trim(leading '0' from a.lct_caseno)=trim(leading '0' from b.lct_caseno)";
            $txt_ref_caseno_t = " and trim(leading '0' from lct_caseno)=trim(leading '0' from lct_caseno)";
        }
        if ($data['ddl_ref_caseyr'] != '') {
            $ddl_ref_caseyr = $data['ddl_ref_caseyr'];
            $ddl_ref_caseyr = " and  lct_caseyear = '$ddl_ref_caseyr'";
            //$ddl_ref_caseyr_t = " and a.lct_caseyear=b.lct_caseyear";
            $ddl_ref_caseyr_t = " and lct_caseyear=lct_caseyear";
        }

         $query = "
select
	a.caveat_no,b.diary_no,b.lct_dec_dt,b.l_dist,b.l_state,b.lct_casetype,b.lct_caseno,b.lct_caseyear,b.ct_code,name,
  CASE WHEN ((m.pet_name is null) or (m.pet_name='')) THEN ( CASE WHEN ((d.pet_name is null) or (d.pet_name='')) THEN (d1.pet_name)ELSE( d.pet_name)  END  )ELSE ( m.pet_name) END AS pet_name,
  CASE WHEN ((m.res_name is null) or (m.res_name='')) THEN ( CASE WHEN ((d.res_name is null) or (d.res_name='')) THEN (d1.res_name)ELSE( d.res_name)  END  )ELSE ( m.res_name) END AS res_name,  
    
    case when ((m.fil_no is null) or (m.fil_no!='')) then substring(m1.fil_no, 4) else substring(m.fil_no, 4) end as fil_no,
    case when (m.fil_dt is null) then to_char(m1.fil_dt,'YYYY') else to_char(m.fil_dt,'YYYY') end as fil_dt,
    case when (m.active_fil_no is null) then m1.active_fil_no else m.active_fil_no end as active_fil_no,
    case when (m.active_fil_dt is null) then m1.active_fil_dt else m.active_fil_dt end as active_fil_dt,
    case when (e.short_description is null) then e1.short_description else e.short_description end as short_description,
    case when (m.reg_no_display is null) then m1.reg_no_display else m.reg_no_display end as reg_no_display,court_name,
    
    CASE  WHEN b.ct_code = 3 THEN (
                CASE WHEN b.l_state = 490506 THEN ( SELECT court_name name FROM master.state s
                        LEFT JOIN master.delhi_district_court d ON s.state_code = d.state_code AND s.district_code = d.district_code WHERE s.id_no = b.l_dist AND display = 'Y'
                )ELSE(  SELECT name FROM master.state s WHERE s.id_no = b.l_dist AND display = 'Y'
                ) END )
        ELSE (  SELECT  agency_name FROM master.ref_agency_code r WHERE  r.cmis_state_id =b.l_state AND r.id = b.l_dist AND is_deleted = 'f'
        ) END AS agency_name,
   
        CASE  WHEN b.ct_code = 4 THEN ( SELECT skey FROM  master.casetype ct WHERE ct.display = 'Y' AND ct.casecode = b.lct_casetype     
        ) ELSE (  SELECT type_sname FROM master.lc_hc_casetype d
        WHERE d.lccasecode = b.lct_casetype AND d.display = 'Y'          
        ) END AS type_sname
from
	(
	SELECT
		DISTINCT caveat_no,b.lct_dec_dt,b.l_dist,b.l_state,b.lct_casetype,b.lct_caseno,b.lct_caseyear,b.caveat_no c_diary,b.ct_code
	FROM
		caveat_lowerct b
	WHERE $ddl_court $txt_order_date $ddl_bench $ddl_st_agncy $ddl_ref_case_type $txt_ref_caseno $ddl_ref_caseyr
		
		AND b.lct_dec_dt is not null
		AND b.lw_display = 'Y' )a
JOIN lowerct b ON
	a.ct_code = b.ct_code AND a.l_dist = b.l_dist AND a.l_state = b.l_state and a.lct_caseyear = b.lct_caseyear AND b.lw_display = 'Y' AND b.is_order_challenged = 'Y'
	
 left join caveat d on d.caveat_no = a.caveat_no
left join caveat_a d1 on d1.caveat_no = a.caveat_no
left join main m on m.diary_no = b.diary_no 
left join main_a m1 on m1.diary_no = b.diary_no
left join master.m_from_court f on f.id = a.ct_code and f.display = 'Y'
left join master.state c on b.l_state = c.id_no and c.display = 'Y'
left join master.casetype e on e.casecode = a.lct_casetype and e.display = 'Y'
left join master.casetype e1 on e1.casecode = a.lct_casetype and e1.display = 'Y'
	
	union all
	select
	a.caveat_no,b.diary_no,b.lct_dec_dt,b.l_dist,b.l_state,b.lct_casetype,b.lct_caseno,b.lct_caseyear,b.ct_code,name,
  CASE WHEN ((m.pet_name is null) or (m.pet_name='')) THEN ( CASE WHEN ((d.pet_name is null) or (d.pet_name='')) THEN (d1.pet_name)ELSE( d.pet_name)  END  )ELSE ( m.pet_name) END AS pet_name,
  CASE WHEN ((m.res_name is null) or (m.res_name='')) THEN ( CASE WHEN ((d.res_name is null) or (d.res_name='')) THEN (d1.res_name)ELSE( d.res_name)  END  )ELSE ( m.res_name) END AS res_name,  
    
    case when ((m.fil_no is null) or (m.fil_no!='')) then substring(m1.fil_no, 4) else substring(m.fil_no, 4) end as fil_no,
    case when (m.fil_dt is null) then to_char(m1.fil_dt,'YYYY') else to_char(m.fil_dt,'YYYY') end as fil_dt,
    case when (m.active_fil_no is null) then m1.active_fil_no else m.active_fil_no end as active_fil_no,
    case when (m.active_fil_dt is null) then m1.active_fil_dt else m.active_fil_dt end as active_fil_dt,
    case when (e.short_description is null) then e1.short_description else e.short_description end as short_description,
    case when (m.reg_no_display is null) then m1.reg_no_display else m.reg_no_display end as reg_no_display,court_name,
    
    CASE  WHEN b.ct_code = 3 THEN (
                CASE WHEN b.l_state = 490506 THEN ( SELECT court_name name FROM master.state s
                        LEFT JOIN master.delhi_district_court d ON s.state_code = d.state_code AND s.district_code = d.district_code WHERE s.id_no = b.l_dist AND display = 'Y'
                )ELSE(  SELECT name FROM master.state s WHERE s.id_no = b.l_dist AND display = 'Y'
                ) END )
        ELSE (  SELECT  agency_name FROM master.ref_agency_code r WHERE  r.cmis_state_id =b.l_state AND r.id = b.l_dist AND is_deleted = 'f'
        ) END AS agency_name,
   
        CASE  WHEN b.ct_code = 4 THEN ( SELECT skey FROM  master.casetype ct WHERE ct.display = 'Y' AND ct.casecode = b.lct_casetype     
        ) ELSE (  SELECT type_sname FROM master.lc_hc_casetype d
        WHERE d.lccasecode = b.lct_casetype AND d.display = 'Y'          
        ) END AS type_sname
from
	(
	SELECT
		DISTINCT caveat_no,b.lct_dec_dt,b.l_dist,b.l_state,b.lct_casetype,b.lct_caseno,b.lct_caseyear,b.caveat_no c_diary,b.ct_code
	FROM
		caveat_lowerct_a b
	WHERE $ddl_court $txt_order_date $ddl_bench $ddl_st_agncy $ddl_ref_case_type $txt_ref_caseno $ddl_ref_caseyr
		
		AND b.lct_dec_dt is not null
		AND b.lw_display = 'Y' )a
JOIN lowerct_a b ON
	a.ct_code = b.ct_code AND a.l_dist = b.l_dist AND a.l_state = b.l_state and a.lct_caseyear = b.lct_caseyear AND b.lw_display = 'Y' AND b.is_order_challenged = 'Y'
	left join caveat d on d.caveat_no = a.caveat_no
left join caveat_a d1 on d1.caveat_no = a.caveat_no
left join main m on m.diary_no = b.diary_no 
left join main_a m1 on m1.diary_no = b.diary_no
left join master.m_from_court f on f.id = a.ct_code and f.display = 'Y'
left join master.state c on b.l_state = c.id_no and c.display = 'Y'
left join master.casetype e on e.casecode = a.lct_casetype and e.display = 'Y'
left join master.casetype e1 on e1.casecode = a.lct_casetype and e1.display = 'Y'
	


	
	union all
	select
	a.caveat_no,b.diary_no,b.lct_dec_dt,b.l_dist,b.l_state,b.lct_casetype,b.lct_caseno,b.lct_caseyear,b.ct_code,name,
  CASE WHEN ((m.pet_name is null) or (m.pet_name='')) THEN ( CASE WHEN ((d.pet_name is null) or (d.pet_name='')) THEN (d1.pet_name)ELSE( d.pet_name)  END  )ELSE ( m.pet_name) END AS pet_name,
  CASE WHEN ((m.res_name is null) or (m.res_name='')) THEN ( CASE WHEN ((d.res_name is null) or (d.res_name='')) THEN (d1.res_name)ELSE( d.res_name)  END  )ELSE ( m.res_name) END AS res_name,  
    
    case when ((m.fil_no is null) or (m.fil_no!='')) then substring(m1.fil_no, 4) else substring(m.fil_no, 4) end as fil_no,
    case when (m.fil_dt is null) then to_char(m1.fil_dt,'YYYY') else to_char(m.fil_dt,'YYYY') end as fil_dt,
    case when (m.active_fil_no is null) then m1.active_fil_no else m.active_fil_no end as active_fil_no,
    case when (m.active_fil_dt is null) then m1.active_fil_dt else m.active_fil_dt end as active_fil_dt,
    case when (e.short_description is null) then e1.short_description else e.short_description end as short_description,
    case when (m.reg_no_display is null) then m1.reg_no_display else m.reg_no_display end as reg_no_display,court_name,
    
    CASE  WHEN b.ct_code = 3 THEN (
                CASE WHEN b.l_state = 490506 THEN ( SELECT court_name name FROM master.state s
                        LEFT JOIN master.delhi_district_court d ON s.state_code = d.state_code AND s.district_code = d.district_code WHERE s.id_no = b.l_dist AND display = 'Y'
                )ELSE(  SELECT name FROM master.state s WHERE s.id_no = b.l_dist AND display = 'Y'
                ) END )
        ELSE (  SELECT  agency_name FROM master.ref_agency_code r WHERE  r.cmis_state_id =b.l_state AND r.id = b.l_dist AND is_deleted = 'f'
        ) END AS agency_name,
   
        CASE  WHEN b.ct_code = 4 THEN ( SELECT skey FROM  master.casetype ct WHERE ct.display = 'Y' AND ct.casecode = b.lct_casetype     
        ) ELSE (  SELECT type_sname FROM master.lc_hc_casetype d
        WHERE d.lccasecode = b.lct_casetype AND d.display = 'Y'          
        ) END AS type_sname
from
	(
	SELECT
		DISTINCT caveat_no,b.lct_dec_dt,b.l_dist,b.l_state,b.lct_casetype,b.lct_caseno,b.lct_caseyear,b.caveat_no c_diary,b.ct_code
	FROM
		caveat_lowerct_a b
	WHERE $ddl_court $txt_order_date $ddl_bench $ddl_st_agncy $ddl_ref_case_type $txt_ref_caseno $ddl_ref_caseyr
		
		AND b.lct_dec_dt is not null
		AND b.lw_display = 'Y' )a
JOIN lowerct b ON
	a.ct_code = b.ct_code AND a.l_dist = b.l_dist AND a.l_state = b.l_state and a.lct_caseyear = b.lct_caseyear AND b.lw_display = 'Y' AND b.is_order_challenged = 'Y'
	left join caveat d on d.caveat_no = a.caveat_no
left join caveat_a d1 on d1.caveat_no = a.caveat_no
left join main m on m.diary_no = b.diary_no 
left join main_a m1 on m1.diary_no = b.diary_no
left join master.m_from_court f on f.id = a.ct_code and f.display = 'Y'
left join master.state c on b.l_state = c.id_no and c.display = 'Y'
left join master.casetype e on e.casecode = a.lct_casetype and e.display = 'Y'
left join master.casetype e1 on e1.casecode = a.lct_casetype and e1.display = 'Y'
	
	
	union all
	select 
	 a.caveat_no,b.diary_no,b.lct_dec_dt,b.l_dist,b.l_state,b.lct_casetype,b.lct_caseno,b.lct_caseyear,b.ct_code,name,
  CASE WHEN ((m.pet_name is null) or (m.pet_name='')) THEN ( CASE WHEN ((d.pet_name is null) or (d.pet_name='')) THEN (d1.pet_name)ELSE( d.pet_name)  END  )ELSE ( m.pet_name) END AS pet_name,
  CASE WHEN ((m.res_name is null) or (m.res_name='')) THEN ( CASE WHEN ((d.res_name is null) or (d.res_name='')) THEN (d1.res_name)ELSE( d.res_name)  END  )ELSE ( m.res_name) END AS res_name,  
    
    case when ((m.fil_no is null) or (m.fil_no!='')) then substring(m1.fil_no, 4) else substring(m.fil_no, 4) end as fil_no,
    case when (m.fil_dt is null) then to_char(m1.fil_dt,'YYYY') else to_char(m.fil_dt,'YYYY') end as fil_dt,
    case when (m.active_fil_no is null) then m1.active_fil_no else m.active_fil_no end as active_fil_no,
    case when (m.active_fil_dt is null) then m1.active_fil_dt else m.active_fil_dt end as active_fil_dt,
    case when (e.short_description is null) then e1.short_description else e.short_description end as short_description,
    case when (m.reg_no_display is null) then m1.reg_no_display else m.reg_no_display end as reg_no_display,court_name,
    
    CASE  WHEN b.ct_code = 3 THEN (
                CASE WHEN b.l_state = 490506 THEN ( SELECT court_name name FROM master.state s
                        LEFT JOIN master.delhi_district_court d ON s.state_code = d.state_code AND s.district_code = d.district_code WHERE s.id_no = b.l_dist AND display = 'Y'
                )ELSE(  SELECT name FROM master.state s WHERE s.id_no = b.l_dist AND display = 'Y'
                ) END )
        ELSE (  SELECT  agency_name FROM master.ref_agency_code r WHERE  r.cmis_state_id =b.l_state AND r.id = b.l_dist AND is_deleted = 'f'
        ) END AS agency_name,
   
        CASE  WHEN b.ct_code = 4 THEN ( SELECT skey FROM  master.casetype ct WHERE ct.display = 'Y' AND ct.casecode = b.lct_casetype     
        ) ELSE (  SELECT type_sname FROM master.lc_hc_casetype d
        WHERE d.lccasecode = b.lct_casetype AND d.display = 'Y'          
        ) END AS type_sname
from
	(
	SELECT
		DISTINCT caveat_no,b.lct_dec_dt,b.l_dist,b.l_state,b.lct_casetype,b.lct_caseno,b.lct_caseyear,b.caveat_no c_diary,b.ct_code
	FROM caveat_lowerct b
	WHERE $ddl_court $txt_order_date $ddl_bench $ddl_st_agncy $ddl_ref_case_type $txt_ref_caseno $ddl_ref_caseyr
		AND b.lct_dec_dt is not null
		AND b.lw_display = 'Y' )a
JOIN lowerct_a b ON
	a.ct_code = b.ct_code AND a.l_dist = b.l_dist AND a.l_state = b.l_state and a.lct_caseyear = b.lct_caseyear AND b.lw_display = 'Y' AND b.is_order_challenged = 'Y'
  left join caveat d on d.caveat_no = a.caveat_no
left join caveat_a d1 on d1.caveat_no = a.caveat_no
left join main m on m.diary_no = b.diary_no 
left join main_a m1 on m1.diary_no = b.diary_no
left join master.m_from_court f on f.id = a.ct_code and f.display = 'Y'
left join master.state c on b.l_state = c.id_no and c.display = 'Y'
left join master.casetype e on e.casecode = a.lct_casetype and e.display = 'Y'
left join master.casetype e1 on e1.casecode = a.lct_casetype and e1.display = 'Y'

limit $offset_left offset $offset_right
  ";


        $query = $this->db->query($query);
        $result = $query->getResultArray();
        return $result;
    }


}
