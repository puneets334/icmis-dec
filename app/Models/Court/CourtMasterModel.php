<?php

namespace App\Models\Court;

use CodeIgniter\Model;

class CourtMasterModel extends Model
{
    protected $eservicesdb;
    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
        $this->eservicesdb = \Config\Database::connect('eservices');
    }

    public function getCaseType()
    {
        $builder = $this->db->table("master.casetype");
        $builder->select("casecode, skey, casename,short_description");
        $builder->where("display", "Y");
        $builder->where("casecode != 9999");
        $builder->orderBy("casecode", "DESC");
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return [];
        }
    }

    public function getFileProceedingDetail($file_name, $causelistDate)
    {

        $tbl = is_table_a('proceedings');

        $builder = $this->db->table("public." . $tbl);
        $builder->select("*");
        $builder->where("file_name", $file_name);
        // $builder->where("order_date",$causelistDate);
        $builder->where("DATE(order_date) =", $causelistDate);
        $builder->where("display", "Y");
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultObject();
        } else {
            return [];
        }
    }

    public function getJudge($jcode = null)
    {

        $builder = $this->db->table("master.judge");
        $builder->select("*");
        $builder->where("display", "Y");
        if ($jcode != null) {
            $builder->whereIn("jcode", $jcode);
        } else {
            $builder->where("is_retired", "N");
        }
        $builder->orderBy("jtype", "ASC");
        $builder->orderBy("judge_seniority", "ASC");
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return [];
        }
    }

    public function getBenchByJudge($main_head, $board_type, $causelistDate, $pJudge)
    {

        $m_f = 1;
        if ($main_head == 'F') {
            $m_f = 2;
        }
        if ($causelistDate == null) {
            $causelistDate = date('Y-m-d');
        }
        
        $causelistDate = date('Y-m-d', strtotime($causelistDate));

        $builder = $this->db->table("master.roster r");
        $builder->select("distinct(r.id) as roster_id,session,r.frm_time,r.courtno");
        $builder->join("master.roster_judge rj", "r.id = rj.roster_id", "inner");
        $builder->join("public.cl_printed cp", "r.id=cp.roster_id", "inner");
        $builder->join("master.roster_bench rb", "r.bench_id=rb.id", "inner");
        $builder->join("master.master_bench mb", "rb.bench_id=mb.id", "inner");
        $builder->where("r.display", "Y");
        $builder->where("cp.display", "Y");
        $builder->where("mb.board_type_mb", $board_type);
        $builder->where("r.m_f = CAST(" . $m_f . " AS TEXT)");
        $builder->where("rj.judge_id", $pJudge);
        $builder->where("((to_date IS NULL and from_date<='" . $causelistDate . "' ) or ('" . $causelistDate . "' between  from_date and to_date))");
        $builder->where("cp.next_dt", $causelistDate);
    //    pr($builder->getCompiledSelect());
        $query = $builder->get();
        // pr($query->getNumRows());

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return [];
        }

    }
	
	function getBenchByJudgeModified($causelistDate, $pJudge, $main_head,$board_type){
			$m_f=1;
			if($main_head=='F'){
				$m_f=2;
			}
			
			if ($causelistDate == null) {
                $causelistDate = date('Y-m-d');
            }
        
			$causelistDate = date('Y-m-d', strtotime($causelistDate));
			
			$builder = $this->db->table('master.roster r');
			$builder->distinct();
			$builder->select('r.id as roster_id, session, r.frm_time, r.courtno');
			$builder->join('master.roster_judge rj', 'r.id = rj.roster_id');
			$builder->join('cl_printed cp', 'r.id = cp.roster_id');
			$builder->join('master.roster_bench rb', 'r.bench_id = rb.id');
			$builder->join('master.master_bench mb', 'rb.bench_id = mb.id');
			
			$builder->where('r.display', 'Y');
			$builder->where('cp.display', 'Y');
			$builder->where('mb.board_type_mb', $board_type);
			$builder->where("r.m_f = CAST(" . $m_f . " AS TEXT)");
			$builder->where('rj.judge_id', $pJudge);
			$builder->where('cp.next_dt', $causelistDate);
            $builder->where("((to_date IS NULL and from_date<='" . $causelistDate . "' ) or ('" . $causelistDate . "' between  from_date and to_date))");
		    $query = $builder->get();
			if ($query->getNumRows() > 0) {
				return $query->getResultArray();
			} else {
				return [];
			}
	}
	
	

    public function getCmNsh(){

        $builder = $this->db->table("master.users u");
        $builder->select("u.*");
        $builder->join("master.court_masters cm", "u.usercode=cm.usercode", "inner");
        $builder->where("u.display", "Y");
        $builder->where("cm.display", "Y");
        $builder->where("cm.is_nsh", "Y");
        $builder->orderBy("u.name", "ASC");

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return [];
        }
    }

    public function getUserDetail($usercode)
    {

        $builder = $this->db->table("master.users");
        $builder->select("*");
        $builder->where("usercode", $usercode);
        $builder->where("display", "Y");
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return [];
        }
    }

    public function getCoramInCourt($bench)
    {

        $builder = $this->db->table("master.roster_judge rj");
        $builder->select("r.courtno,string_agg(rj.judge_id::TEXT,'') as coram");
        $builder->join("master.roster r", "rj.roster_id=r.id", "inner");
        $builder->where("roster_id", $bench);
        $builder->where("rj.display", "Y");
        $builder->groupBy("r.courtno,rj.judge_id");
        $builder->orderBy("rj.judge_id", "ASC");
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return [];
        }
    }

    public function getCaseGenerationList($bench, $main_head, $main_supp_flag, $board_type, $causelistDate)
    {

        $tbl_heardt = is_table_a('heardt');
        $tbl_last_heardt = is_table_a('last_heardt');
        $tbl_main = is_table_a('main');

        $causelistDate = date('Y-m-d', strtotime($causelistDate));

        $builder1 = $this->db->table("public." . $tbl_heardt . " hd");
        $builder1->select("distinct(rj.roster_id),m.diary_no ,hd.next_dt as listing_date,m.pet_name as petitioner_name,m.res_name as respondent_name,r.courtno as court_number,hd.brd_slno as item_number,case when hd.listed_ia ='' then m.reg_no_display else concat('IA ',hd.listed_ia,' in ',m.reg_no_display) end as registration_number_desc,m.pno,m.rno");
        $builder1->join("public." . $tbl_main . " m", "hd.diary_no=m.diary_no", "inner");
        $builder1->join("master.roster_judge rj", "hd.roster_id=rj.roster_id", "inner");
        $builder1->join("master.roster r", "rj.roster_id=r.id", "inner");
        $builder1->join("public.cl_printed cp", "hd.roster_id=cp.roster_id and hd.next_dt=cp.next_dt and hd.brd_slno between cp.from_brd_no and cp.to_brd_no AND hd.clno=cp.part", "inner");
        $builder1->where("cp.display", "Y");
        $builder1->where("hd.main_supp_flag !=0");
        $builder1->where("(hd.conn_key is null or hd.conn_key=0 or hd.conn_key=hd.diary_no)");
        $builder1->where("hd.brd_slno is not null");
        $builder1->where("hd.brd_slno>0");
        if ($main_head != "") {
            $builder1->where("hd.next_dt='" . $causelistDate . "' and rj.roster_id=" . $bench . " and hd.mainhead='" . $main_head . "' and hd.board_type='" . $board_type . "'");
        } else {
            $builder1->where("hd.next_dt='" . $causelistDate . "' and rj.roster_id=" . $bench . " and hd.board_type='" . $board_type . "'");
        }


        $builder2 = $this->db->table("public." . $tbl_last_heardt . " hd");
        $builder2->select("distinct(rj.roster_id),m.diary_no ,hd.next_dt as listing_date,m.pet_name as petitioner_name,m.res_name as respondent_name,r.courtno as court_number,hd.brd_slno as item_number,case when hd.listed_ia ='' then m.reg_no_display else concat('IA ',hd.listed_ia,' in ',m.reg_no_display) end as registration_number_desc,m.pno,m.rno");
        $builder2->join("public." . $tbl_main . " m", "hd.diary_no=m.diary_no", "inner");
        $builder2->join("master.roster_judge rj", "hd.roster_id=rj.roster_id", "inner");
        $builder2->join("master.roster r", "rj.roster_id=r.id", "inner");
        $builder2->join("public.cl_printed cp", "hd.roster_id=cp.roster_id and hd.next_dt=cp.next_dt and hd.brd_slno between cp.from_brd_no and cp.to_brd_no AND hd.clno=cp.part", "inner");
        $builder2->where("cp.display", "Y");
        $builder2->where("hd.main_supp_flag !=0");
        $builder2->where("(hd.conn_key is null or hd.conn_key=0 or hd.conn_key=hd.diary_no)");
        $builder2->where("hd.brd_slno is not null");
        $builder2->where("hd.brd_slno>0");
        $builder2->where("hd.bench_flag=''");
        if ($main_head != "") {
            $builder2->where("hd.next_dt='" . $causelistDate . "' and rj.roster_id=" . $bench . " and hd.mainhead='" . $main_head . "' and hd.board_type='" . $board_type . "'");
        } else {
            $builder2->where("hd.next_dt='" . $causelistDate . "' and rj.roster_id=" . $bench . " and hd.board_type='" . $board_type . "'");
        }


        $builder3 = $this->db->table("public.mention_memo mm");
        $builder3->select("distinct(rj.roster_id),m.diary_no ,mm.date_on_decided AS listing_date,m.pet_name as petitioner_name,m.res_name as respondent_name,r.courtno as court_number,mm.m_brd_slno AS item_number,m.reg_no_display AS registration_number_desc,m.pno,m.rno");
        $builder3->join("public." . $tbl_main . " m", "CAST(mm.diary_no AS INT) = m.diary_no", "inner");
        $builder3->join("master.roster_judge rj", "mm.m_roster_id = rj.roster_id", "inner");
        $builder3->join("master.roster r", "rj.roster_id = r.id", "inner");
        $builder3->where("mm.display", "Y");
        $builder3->where("(m.conn_key IS NULL OR CAST(m.conn_key AS INT) = 0 OR CAST(m.conn_key AS INT) = CAST(mm.diary_no AS INT))");
        $builder3->where("mm.m_brd_slno IS NOT NULL");
        $builder3->where("mm.m_brd_slno > 0");
        $builder3->where("mm.date_on_decided = '" . $causelistDate . "'");
        $builder3->where("rj.roster_id = " . $bench . "");
        $builder3->orderBy("item_number");

        $final_query = $builder1->union($builder2)->union($builder3);

        $query = $final_query->get();

        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return [];
        }
    }

    public function getCaseDetails($diary_no, $roster_id, $causelistDate)
    {

        $tbl_heardt = is_table_a('heardt');
        $tbl_last_heardt = is_table_a('last_heardt');
        $tbl_main = is_table_a('main');
        $tbl_brdrem = is_table_a('brdrem');

        $causelistDate = date('Y-m-d', strtotime($causelistDate));

        $builder1 = $this->db->table("public." . $tbl_heardt . " hd");
        $builder1->select("hd.diary_no,hd.listed_ia as ia,m.active_fil_no as registration_number,m.active_reg_year as registration_year,m.active_casetype_id as casetype_id,m.pet_name,m.res_name,hd.brd_slno as item_number,m.reg_no_display,br.remark as remark,us.section_name,m.pno,m.rno,(select casename from master.casetype where casecode=m.casetype_id) as diary_casetype");
        $builder1->join("public." . $tbl_main . " m", "hd.diary_no=m.diary_no", "inner");
        $builder1->join("public." . $tbl_brdrem . " br", "hd.diary_no=CAST(br.diary_no AS BIGINT)", "left outer");
        $builder1->join("master.users u", "m.dacode=u.usercode", "left outer");
        $builder1->join("master.usersection us", "u.section=us.id", "left outer");
        $builder1->where("hd.next_dt", $causelistDate);
        $builder1->where("hd.diary_no", $diary_no);
        $builder1->where("hd.roster_id", $roster_id);

        $builder2 = $this->db->table("public." . $tbl_last_heardt . " hd");
        $builder2->select("hd.diary_no,hd.listed_ia as ia,m.active_fil_no as registration_number,m.active_reg_year as registration_year,m.active_casetype_id as casetype_id,m.pet_name,m.res_name,hd.brd_slno as item_number,m.reg_no_display,br.remark as remark,us.section_name,m.pno,m.rno,(select casename from master.casetype where casecode=m.casetype_id) as diary_casetype");
        $builder2->join("public." . $tbl_main . " m", "hd.diary_no=m.diary_no", "inner");
        $builder2->join("public." . $tbl_brdrem . " br", "hd.diary_no=CAST(br.diary_no AS BIGINT)", "left outer");
        $builder2->join("master.users u", "m.dacode=u.usercode", "left outer");
        $builder2->join("master.usersection us", "u.section=us.id", "left outer");
        $builder2->where("hd.next_dt", $causelistDate);
        $builder2->where("hd.bench_flag", "");
        $builder2->where("hd.diary_no", $diary_no);
        $builder2->where("hd.roster_id", $roster_id);

        $builder3 = $this->db->table("public.mention_memo hd");
        $builder3->select("CAST(hd.diary_no AS INT),'' as ia,m.active_fil_no as registration_number,m.active_reg_year as registration_year,m.active_casetype_id as casetype_id,m.pet_name,m.res_name,hd.m_brd_slno as item_number,m.reg_no_display,br.remark as remark,us.section_name ,m.pno,m.rno,(select casename from master.casetype where casecode=m.casetype_id) as diary_casetype");
        $builder3->join("public." . $tbl_main . " m", "CAST(hd.diary_no AS INT)=m.diary_no", "inner");
        $builder3->join("public." . $tbl_brdrem . " br", "CAST(hd.diary_no AS BIGINT)=CAST(br.diary_no AS BIGINT)", "left outer");
        $builder3->join("master.users u", "m.dacode=u.usercode", "left outer");
        $builder3->join("master.usersection us", "u.section=us.id", "left outer");
        $builder3->where("hd.display", "Y");
        $builder3->where("hd.date_on_decided", $causelistDate);
        $builder3->where("hd.diary_no", $diary_no);
        $builder3->where("hd.m_roster_id", $roster_id);

        $finalQuery = $builder1->union($builder2)->union($builder3);
        //  pr($finalQuery->getCompiledSelect());
        $query = $finalQuery->get();

        if ($query->getNumRows() >= 1) {
            $res = $query->getResultObject();
            $result = $res[0];
            return $result;
        } else {
            return [];
        }
    }

    public function connectedCaseDetails($diary_no, $roster_id, $causelistDate)
    {

        $tbl_heardt = is_table_a('heardt');
        $tbl_last_heardt = is_table_a('last_heardt');
        $tbl_main = is_table_a('main');
        $tbl_brdrem = is_table_a('brdrem');
        $tbl_conct = is_table_a('conct');

        $causelistDate = date('Y-m-d', strtotime($causelistDate));

        $builder1 = $this->db->table("public." . $tbl_heardt . " hd");
        $builder1->select("ct.ent_dt,m.diary_no,hd.conn_key,m.diary_no_rec_date,hd.listed_ia as ia,m.active_fil_no as registration_number,m.active_reg_year as registration_year,m.reg_no_display,m.active_casetype_id as casetype_id,br.remark,us.section_name");
        $builder1->join("public." . $tbl_main . " m", "hd.diary_no=m.diary_no", "inner");
        $builder1->join("public." . $tbl_conct . " ct", "m.diary_no=ct.diary_no", "left outer");
        $builder1->join("master.users u", "m.dacode=u.usercode", "left outer");
        $builder1->join("master.usersection us", "u.section=us.id", "left outer");
        $builder1->join("public." . $tbl_brdrem . " br", "hd.diary_no=CAST(br.diary_no AS BIGINT)", "left outer");
        $builder1->where("ct.list", "Y");
        $builder1->where("hd.next_dt", $causelistDate);
        $builder1->where("hd.conn_key", $diary_no);
        $builder1->where("hd.conn_key<>hd.diary_no");
        $builder1->where("hd.roster_id", $roster_id);

        $builder2 = $this->db->table("public." . $tbl_last_heardt . " hd");
        $builder2->select("ct.ent_dt,m.diary_no,hd.conn_key,m.diary_no_rec_date,hd.listed_ia as ia,m.active_fil_no as registration_number,m.active_reg_year as registration_year,m.reg_no_display,m.active_casetype_id as casetype_id,br.remark,us.section_name");
        $builder2->join("public." . $tbl_main . " m", "hd.diary_no=m.diary_no", "inner");
        $builder2->join("public." . $tbl_conct . " ct", "m.diary_no=ct.diary_no", "left outer");
        $builder2->join("master.users u", "m.dacode=u.usercode", "left outer");
        $builder2->join("master.usersection us", "u.section=us.id", "left outer");
        $builder2->join("public." . $tbl_brdrem . " br", "hd.diary_no=CAST(br.diary_no AS BIGINT)", "left outer");
        $builder2->where("hd.next_dt", $causelistDate);
        $builder2->where("hd.bench_flag=''");
        $builder2->where("hd.conn_key", $diary_no);
        $builder2->where("hd.conn_key<>hd.diary_no");
        $builder2->where("hd.roster_id", $roster_id);

        $builder3 = $this->db->table("public.mention_memo hd");
        $builder3->select("ct.ent_dt,m.diary_no,m_conn_key as conn_key,m.diary_no_rec_date,'' as ia,m.active_fil_no as registration_number,m.active_reg_year as registration_year,m.reg_no_display,m.active_casetype_id as casetype_id,br.remark,us.section_name");
        $builder3->join("public." . $tbl_main . " m", "CAST(hd.diary_no AS INT)=m.diary_no", "inner");
        $builder3->join("public." . $tbl_conct . " ct", "m.diary_no=ct.diary_no", "left outer");
        $builder3->join("master.users u", "m.dacode=u.usercode", "left outer");
        $builder3->join("master.usersection us", "u.section=us.id", "left outer");
        $builder3->join("public." . $tbl_brdrem . " br", "CAST(hd.diary_no AS BIGINT)=CAST(br.diary_no AS BIGINT)", "left outer");
        $builder3->where("hd.display", "Y");
        $builder3->where("hd.date_on_decided", $causelistDate);
        $builder3->where("hd.m_conn_key", $diary_no);
        $builder3->where("hd.m_conn_key<>CAST(hd.diary_no as INT)");
        $builder3->where("hd.m_roster_id", $roster_id);

        $subquery = $builder1->union($builder2)->union($builder3);

        $finalQuery  = $this->db->newQuery()->select('*')->fromSubquery($subquery, 'aa')->groupBy("diary_no,ent_dt,conn_key,diary_no_rec_date,ia,registration_number,registration_year,reg_no_display,casetype_id,remark,section_name")->orderBy("(CASE WHEN ent_dt IS NOT NULL THEN CAST(ent_dt AS VARCHAR) ELSE CAST(999 AS VARCHAR) END) ASC")->orderBy("CAST(SUBSTRING(diary_no::TEXT,-4) as INTEGER) ASC")->orderBy("CAST(LEFT(diary_no::TEXT,length(diary_no::TEXT)-4) as INTEGER) ASC");

        // pr($finalQuery->getCompiledSelect());
        $query = $finalQuery->get();

        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return [];
        }
    }

    public function getAdvocateDetails($diary_no, $adv_for)
    {

        $tbl_advocate = is_table_a('advocate');
        $tbl_heardt = is_table_a('heardt');

        $diary_nos = implode(',', $diary_no);

        $builder = $this->db->table("public." . $tbl_advocate . " a");
        $builder->distinct();
        $builder->select("a.pet_res, b.aor_code, b.bar_id as advoate_code,b.title,b.name as advocate_name,b.if_aor,CASE WHEN a.pet_res in ('I','N') THEN 99 ELSE 0 END,a.adv_type,a.pet_res_no");
        $builder->join("master.bar b", "a.advocate_id=b.bar_id", "inner");
        $builder->where("a.diary_no  IN(select diary_no from " . $tbl_heardt . " where diary_no IN(" . $diary_nos . "))");

        if ($adv_for == 'P') {
            $builder->where("a.pet_res", "P");
        }
        if ($adv_for == 'R') {
            $builder->whereIn("a.pet_res", ["R", "I", "N"]);
        }
        $builder->where("a.display", "Y");
        $builder->where("b.isdead", "N");
        $builder->where("if_sen", "N");
        $builder->orderBy("(CASE WHEN pet_res in ('I','N') THEN 99 ELSE 0 END) ASC, adv_type DESC, pet_res_no ASC");
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return [];
        }
    }

    public function getLowerCourtDetails($diary_no)
    {

        $tbl_lowerct = is_table_a('lowerct');

        $builder = $this->db->table("public." . $tbl_lowerct . " lc");
        $builder->select("lc.lower_court_id, lc.ct_code, lc.lct_dec_dt");
        $builder->select("case when ct_code=4 then (select short_description from master.casetype where casecode=lc.lct_casetype) else (select type_sname from master.lc_hc_casetype where lccasecode=lc.lct_casetype) end as casetype");
        $builder->select("lc.lct_casetype, lc.lct_caseno, lc.lct_caseyear");
        $builder->select("(CASE WHEN ct_code=4 THEN 'Supreme Court of India' ELSE rgc.agency_name END) AS agency_name", FALSE);
        $builder->select("(SELECT name FROM master.state WHERE id_no = lc.l_state) AS state_name");
        $builder->select("lc.is_order_challenged");
        $builder->join("master.ref_agency_code rgc", "lc.l_state = rgc.cmis_state_id AND lc.l_dist = rgc.id", "left outer");
        $builder->where("lc.lw_display", "Y");
        $builder->where("lc.is_order_challenged", "Y");
        $builder->where("diary_no", $diary_no);
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return [];
        }
    }

    public function getJudgeName($coram)
    {
        $coramArr = explode(',', $coram);
        $builder = $this->db->table("master.judge");
        $builder->select("jcode,jname,first_name,sur_name");
        $builder->whereIn("jcode", $coramArr);
        $builder->orderBy("judge_seniority");
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return [];
        }
    }

    public function getTentativeSection($diary_no)
    {

        $builder = $this->db->query("select tentative_section(" . $diary_no . ") as section");
        $result  = $builder->getResultObject();
        return $result[0];
    }

    public function getIADetails($listedIAs, $diary_no = 0)
    {

        $tbl_docdetails = is_table_a('docdetails');

        $subquery = $this->db->table("public." . $tbl_docdetails . " d");
        $subquery->select("d.docnum,d.docyear,d.doccode1,(CASE WHEN dm.doccode1 = 19 THEN other1 ELSE docdesc END) docdesp,d.other1, d.iastat");
        $subquery->join("master.docmaster dm", "d.doccode = dm.doccode AND d.doccode1 = dm.doccode1", "INNER");
        $subquery->where("d.doccode", 8);
        $subquery->where("dm.display", "Y");
        $subquery->where("POSITION(CONCAT(d.docnum, d.docyear) IN TRIM(BOTH ',' FROM REPLACE(REPLACE(REPLACE('89018/2022', '/', ''), ' ', ''), ' ', ''))) > 0");
        $subquery->where("d.diary_no", $diary_no);

        $finalQuery  = $this->db->newQuery()->select('*')->fromSubquery($subquery, 'a')->where("docdesp !='' ")->orderBy("docdesp");

        $query = $finalQuery->get();

        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return [];
        }
    }

    public function getAdvocateAppearanceDetails($diary_no, $adv_for, $causelistDate, $aor_code)
    {

        $builder = $this->db->table("master.appearing_in_diary a");
        $builder->distinct();
        $builder->select("advocate_type, advocate_title, advocate_name");
        $builder->whereIn("diary_no", $diary_no);
        if ($adv_for == 'P') {
            $builder->where("a.appearing_for", "P");
        } else {
            $builder->whereIn("a.appearing_for", ['R', 'I', 'N']);
        }
        $builder->where("a.list_date", date('Y-m-d', strtotime($causelistDate)));
        $builder->where("a.aor_code", $aor_code);
        $builder->where("a.is_active", "1");
        $builder->where("a.is_submitted", "1");
        $builder->orderBy("priority");
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return [];
        }
    }

    public function getUserNameAndDesignation($usercode)
    {

        $builder = $this->db->table("master.users u");
        $builder->select("u.name,ut.type_name,u.section,u.usertype");
        $builder->join("master.usertype ut", "u.usertype=ut.id", "inner");
        $builder->where("usercode", $usercode);
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $res = $query->getResultObject();
            $result = $res[0];
            return $result;
        } else {
            return [];
        }
    }

    public function getFileName($diary_no, $listing_date, $roster_id, $item_no)
    {

        $tbl_main = is_table_a('main');
        $tbl_proceedings = is_table_a('proceedings');

        $builder = $this->db->table("public." . $tbl_main . " m");
        $builder->select("skey as casetype_desc,m.diary_no,active_fil_no as registration_number,active_reg_year as registration_year,p.file_name");
        $builder->join("master.casetype c", "m.active_casetype_id=c.casecode", "left outer");
        $builder->join("public." . $tbl_proceedings . " p", "m.diary_no = p.diary_no and p.order_date='" . $listing_date . "' and p.roster_id=$roster_id and p.item_number=$item_no", "left outer");
        $builder->where("m.diary_no", $diary_no);
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $res = $query->getResultObject();
            $result = $res[0];
            return $result;
        } else {
            return [];
        }
    }

    public function updateProceedingsDetail($data)
    {

        $tbl_proceedings = is_table_a('proceedings');

        $builder = $this->db->table("public." . $tbl_proceedings . "");
        $builder->select("id,generated_by");
        $builder->where("order_date", $data['order_date']);
        $builder->where("roster_id", $data['roster_id']);
        $builder->where("court_number", $data['court_number']);
        $builder->where("item_number", $data['item_number']);
        $builder->where("diary_no", $data['diary_no']);
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $res = $query->getResultArray();

            $generated_by = $res[0]['generated_by'];
            $id = $res[0]['id'];
            $generated_users = explode(',', $generated_by);
            if (!in_array($data['generated_by'], $generated_users)) {
                $generated_by .= ',' . $data['generated_by'];
            }

            $upd_arr = [
                'is_reportable'   =>   $data['is_reportable'],
                'generated_by'    =>   $generated_by,
                'generated_on'    =>   'NOW()'
            ];

            $builder = $this->db->table('public.proceedings');
            $builder->where('id', $id);
            $query = $builder->update($upd_arr);
        } else {

            $tbl_heardt = is_table_a('heardt');
            $tbl_last_heardt = is_table_a('last_heardt');
            $tbl_main = is_table_a('main');

            $builder1 = $this->db->table("public." . $tbl_heardt . " hd");
            $builder1->select("'" . $data['order_date'] . "' as order_date," . $data['court_number'] . " as court_number," . $data['item_number'] . " as item_number," . $data['diary_no'] . " as diary_no,'" . $data['generated_by'] . "' as generated_by,now() as generated_on,'" . $data['file_name'] . "' as file_name,'" . $data['order_type'] . "' as order_type," . $data['is_oral_mentioning'] . " as is_oral_mentioning,hd.listed_ia as app_no,m.active_fil_no as registration_number,m.active_reg_year as registration_year," . $data['roster_id'] . " as roster_id," . $data['is_reportable'] . " as is_reportable, 0 as ordernet_id");
            $builder1->join("public." . $tbl_main . " m", "hd.diary_no=m.diary_no", "inner");
            $builder1->where("hd.diary_no", $data['diary_no']);
            $builder1->where("hd.roster_id", $data['roster_id']);
            $builder1->where("hd.brd_slno", $data['item_number']);
            $builder1->where("hd.next_dt", $data['order_date']);

            $builder2 = $this->db->table("public." . $tbl_last_heardt . " hd");
            $builder2->select("'" . $data['order_date'] . "' as order_date," . $data['court_number'] . " as court_number," . $data['item_number'] . " as item_number," . $data['diary_no'] . " as diary_no,'" . $data['generated_by'] . "' as generated_by,now() as generated_on,'" . $data['file_name'] . "' as file_name,'" . $data['order_type'] . "' as order_type," . $data['is_oral_mentioning'] . " as is_oral_mentioning,hd.listed_ia as app_no,m.active_fil_no as registration_number,m.active_reg_year as registration_year," . $data['roster_id'] . " as roster_id," . $data['is_reportable'] . " as is_reportable, 0 as ordernet_id");
            $builder2->join("public." . $tbl_main . " m", "hd.diary_no=m.diary_no", "inner");
            $builder2->where("hd.diary_no", $data['diary_no']);
            $builder2->where("hd.roster_id", $data['roster_id']);
            $builder2->where("hd.brd_slno", $data['item_number']);
            $builder2->where("hd.next_dt", $data['order_date']);

            $builder3 = $this->db->table("public.mention_memo hd");
            $builder3->select("'" . $data['order_date'] . "' as order_date," . $data['court_number'] . " as court_number," . $data['item_number'] . " as item_number," . $data['diary_no'] . " as diary_no,'" . $data['generated_by'] . "' as generated_by,now() as generated_on,'" . $data['file_name'] . "' as file_name,'" . $data['order_type'] . "' as order_type," . $data['is_oral_mentioning'] . " as is_oral_mentioning,'' as app_no,m.active_fil_no as registration_number,m.active_reg_year as registration_year," . $data['roster_id'] . " as roster_id," . $data['is_reportable'] . " as is_reportable, 0 as ordernet_id");
            $builder3->join("public." . $tbl_main . " m", "hd.diary_no=CAST(m.diary_no AS TEXT)", "inner");
            $builder3->where("hd.diary_no", $data['diary_no']);
            $builder3->where("hd.m_roster_id", $data['roster_id']);
            $builder3->where("hd.m_brd_slno", $data['item_number']);
            $builder3->where("hd.date_on_decided", $data['order_date']);

            $finalQuery = $builder1->union($builder2)->union($builder3);

            $ins_data_query = $finalQuery->get();

            if ($ins_data_query->getNumRows() >= 1) {
                $insertData = $ins_data_query->getResultArray();
                $this->db->table('public.proceedings')->insertBatch($insertData);
            }
        }
    }

    public function getOrdernet($order_upload, $txt_o_frmdt, $txt_o_todt)
    {

        $tbl = is_table_a('ordernet');
        $tbl_main = is_table_a('main');

        $builder = $this->db->table("public." . $tbl . " ont");
        $builder->select("id, ont.diary_no, petn, resp, roster_id, perj, orderdate,type,prnt_name,prnt_dt,ent_dt,pdfname,pet_name,res_name,b.reg_no_display");
        $builder->join("public." . $tbl_main . " b", "on ont.diary_no=b.diary_no");
        $builder->where("ent_dt notnull");
        $builder->where("$order_upload between '$txt_o_frmdt' and '$txt_o_todt'");
        $builder->where("pdfname !='' ");
        // pr($builder->getCompiledSelect());
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return [];
        }
    }

    public function getRosterJudge($roster_id)
    {

        $builder = $this->db->table("master.roster_judge a");
        $builder->select("jname");
        $builder->join("master.judge b", "a.judge_id=b.jcode");
        $builder->where("roster_id", $roster_id);
        $builder->where("a.display", "Y");
        $builder->where("b.display", "Y");
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return [];
        }
    }

    public function getPdfname($docid)
    {

        $tbl = is_table_a('ordernet');

        $builder = $this->db->table("public." . $tbl . "");
        $builder->select("pdfname");
        $builder->where("id", $docid);
        $builder->where("(prnt_name!='' || prnt_name is not null)");
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return [];
        }
    }

    public function getCaseDetailsForReplace($diary_no)
    {

        $tbl_heardt = is_table_a('heardt');
        $tbl_last_heardt = is_table_a('last_heardt');
        $tbl_main = is_table_a('main');

        $builder1 = $this->db->table("public." . $tbl_heardt . " hd");
        $builder1->select("hd.diary_no,m.active_fil_no as registration_number,m.active_reg_year as registration_year, m.active_casetype_id as casetype_id,m.pet_name,m.res_name,hd.brd_slno as item_number,m.reg_no_display,hd.next_dt,r.courtno,hd.roster_id");
        $builder1->join("public." . $tbl_main . " m", "hd.diary_no=m.diary_no", "inner");
        $builder1->join("master.roster r", "hd.roster_id=r.id", "inner");
        $builder1->where("hd.diary_no", $diary_no);
        $builder1->where("hd.brd_slno !=0 ");
        $builder1->where("hd.clno !=0");

        $builder2 = $this->db->table("public." . $tbl_last_heardt . " hd");
        $builder2->select("hd.diary_no,m.active_fil_no as registration_number,m.active_reg_year as registration_year,m.active_casetype_id as casetype_id,m.pet_name,m.res_name,hd.brd_slno as item_number,m.reg_no_display, hd.next_dt,r.courtno,hd.roster_id");
        $builder2->join("public." . $tbl_main . " m", "hd.diary_no=m.diary_no", "inner");
        $builder2->join("master.roster r", "hd.roster_id=r.id", "inner");
        $builder2->where("hd.bench_flag=''");
        $builder2->where("hd.diary_no", $diary_no);
        $builder2->where("hd.brd_slno !=0 ");
        $builder2->where("hd.clno !=0");


        $builder3 = $this->db->table("public.mention_memo hd");
        $builder3->select("CAST(hd.diary_no AS INT),m.active_fil_no as registration_number,m.active_reg_year as registration_year,m.active_casetype_id as casetype_id,m.pet_name,m.res_name,hd.m_brd_slno as item_number,m.reg_no_display,hd.date_on_decided as next_dt,r.courtno,hd.m_roster_id as roster_id");
        $builder3->join("public." . $tbl_main . " m", "CAST(hd.diary_no AS INT)=m.diary_no", "inner");
        $builder3->join("master.roster r", "hd.m_roster_id=r.id", "inner");
        $builder3->where("hd.display", "Y");
        $builder3->where("hd.diary_no", $diary_no);
        $builder3->where("hd.m_brd_slno!=0");
        $builder3->orderBy("next_dt", "DESC");

        $subquery = $builder1->union($builder2)->union($builder3);

        $query = $subquery->get();

        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return [];
        }
    }

    public function getLanguage($usercode)
    {

        $userdetail = $this->getUserDetail($usercode);
        $usersection = $userdetail[0]['section'];

        $builder = $this->db->table("master.vernacular_languages");
        $builder->select("*");

        if ($usersection == 79) { //:TODO Replace 79 with transaltion cell usersection id
            $builder->where("id !=", "1", false);
        } else {
            $builder->where("id", "1");
        }
        $builder->where("display", "Y");
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return [];
        }
    }

    public function getAllJudge()
    {
        $builder = $this->db->table("master.judge");
        $builder->select("*");
        $builder->where("display", "Y");
        $builder->orderBy("judge_seniority", "ASC");
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return [];
        }
    }

    public function getDiaryProceedingDetail($diary_no, $causelistDate, $court_number, $item_number, $roster_id, $orderType)
    {

        $tbl_proceedings = is_table_a('proceedings');
        $tbl_ordernet = is_table_a('ordernet');

        $builder = $this->db->table("public." . $tbl_proceedings . " p");
        $builder->select("p.*,o.pdfname,o.usercode,o.ent_dt,o.type");

        if ($orderType == 'Order') {
            $builder->join("public." . $tbl_ordernet . " o", "p.ordernet_id=o.id", "left");
        } elseif ($orderType == 'Judgement') {
            $builder->join("public." . $tbl_ordernet . " o", "p.diary_no=o.diary_no and p.order_date=o.orderdate and p.roster_id=o.roster_id and o.type='J'", "left");
        } elseif ($orderType == 'FinalOrder') {
            $builder->join("public." . $tbl_ordernet . " o", "p.diary_no=o.diary_no and p.order_date=o.orderdate and p.roster_id=o.roster_id and o.type='FO'", "left");
        }

        $builder->where("p.diary_no", $diary_no);
        $builder->where("date(p.order_date)", $causelistDate);
        $builder->where("p.court_number", $court_number);
        $builder->where("p.item_number", $item_number);
        $builder->where("p.roster_id", $roster_id);
        $builder->where("p.display", "Y");

        if ($orderType == 'Order') {
            $builder->where("p.order_type", "O");
        }

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultObject();
        } else {
            return [];
        }
    }

    public function getNCDetails($diary_no, $order_date)
    {

        $builder = $this->db->table("public.neutral_citation");
        $builder->select("*");
        $builder->where("diary_no", $diary_no);
        $builder->where("dispose_order_date", $order_date);

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return [];
        }
    }

    public function getVernacularJudgmentDetail($diary_no, $orderDate, $orderType, $languageId)
    {

        $builder = $this->db->table("public.vernacular_orders_judgments");
        $builder->select("*");
        $builder->where("display", "Y");
        $builder->where("diary_no", $diary_no);
        $builder->where("order_date", $orderDate);
        $builder->where("order_type", $orderType);
        $builder->where("ref_vernacular_languages_id", $languageId);

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultObject();
        } else {
            return [];
        }
    }

    public function insertVernacularOrdersJudgments($data)
    {

        $result = 0;
        $res = $this->getVernacularJudgmentDetail($data['diary_no'], $data['order_date'], $data['order_type'], $data['ref_vernacular_languages_id']);

        $builder = $this->db->table('public.vernacular_orders_judgments');

        if (!$res) {
            $builder->insert($data);
            $result = 1;
        } elseif (sizeof($res) > 0) {
            $orderDetail = $res[0];
            $id = $orderDetail->id;

            $builder->where("id", $id);
            $builder->update($data);
            $result = 1;
        }
        return $result;
    }

    public function insertProceedingsInOrderNet($data)
    {

        $isReportable = 'N';
        if ($data['is_reportable'] == 1) {
            $isReportable = 'Y';
        }
        $perj = 0;
        if (!empty($data['presiding_judge'])) {
            $perj = $data['presiding_judge'];
        }
        $result = 0;

        $tblordernet = is_table_a('ordernet');

        $builder = $this->db->table("public." . $tblordernet . "");
        $builder->select("*");
        $builder->where("diary_no", $data['diary_no']);
        $builder->where("orderdate", $data['order_date']);
        $builder->where("roster_id", $data['roster_id']);
        $builder->where("display", "Y");
        $builder->where("type", $data['type']);
        $res = $builder->get()->getResultObject();

        if (!$res) {

            $ordernet_id = 0;
            $ins_arr = [
                'diary_no'      =>      $data['diary_no'],
                'perj'          =>      $perj,
                'orderdate'     =>      $data['order_date'],
                'pdfname'       =>      $data['pdf_name'],
                'usercode'      =>      $data['usercode'],
                'ent_dt'        =>      'NOW()',
                'type'          =>      $data['type'],
                'h_p'           =>      'P',
                'afr'           =>      $isReportable,
                'display'       =>      'Y',
                'roster_id'     =>      $data['roster_id'],
                'c_type'        =>      $data['case_type'],
                'c_num'         =>      $data['registration_number'],
                'c_year'        =>      $data['registration_year'],
                'ordertextdata' =>      $data['orderTextData']
            ];

            $builder_ins = $this->db->table('public.ordernet');
            $builder_ins->insert($ins_arr);
            $ordernet_id = $this->db->insertID();

            if ($ordernet_id != 0) {
                if ($data['type'] == 'O') {
                    $upd_arr = [
                        'upload_flag'         =>      '1',
                        'uploaded_by'         =>      $data['usercode'],
                        'upload_date_time'    =>      'NOW()',
                        'ordernet_id'         =>      $ordernet_id
                    ];
                    $builder_upd = $this->db->table('public.proceedings');
                    $builder_upd->where('diary_no', $data['diary_no']);
                    $builder_upd->where('order_date', $data['order_date']);
                    $builder_upd->where('roster_id', $data['roster_id']);
                    $builder_upd->update($upd_arr);
                }
                $result = 1;
            }
        } elseif (sizeof($res) > 0) {
            //::TODO Write replace queries here;
            $ordernetDetail = $res[0];
            $ordernet_id = $ordernetDetail->id;

            $upd_ordernet_arr = [
                'perj'            =>      $perj,
                'pdfname'         =>      $data['pdf_name'],
                'usercode'        =>      $data['usercode'],
                'ent_dt'          =>      'NOW()',
                'type'            =>      $data['type'],
                'afr'             =>      $isReportable,
                'ordertextdata'   =>      $data['orderTextData']
            ];

            $builder_ordernet_upd = $this->db->table('public.ordernet');
            $builder_ordernet_upd->where('id', $ordernet_id);
            $builder_ordernet_upd->update($upd_ordernet_arr);

            if ($ordernet_id != 0) {
                if ($data['type'] == 'O') {
                    $upd_arr = [
                        'upload_flag'         =>      '1',
                        'uploaded_by'         =>      $data['usercode'],
                        'upload_date_time'    =>      'NOW()',
                        'ordernet_id'         =>      $ordernet_id
                    ];
                    $builder_upd = $this->db->table('public.proceedings');
                    $builder_upd->where('diary_no', $data['diary_no']);
                    $builder_upd->where('order_date', $data['order_date']);
                    $builder_upd->where('roster_id', $data['roster_id']);
                    $builder_upd->update($upd_arr);
                }
                $result = 1;
            }
        }
        return $result;
    }

    public function getDiariesformList($list_date_ymd, $courtno)
    {

        $builder = $this->eservicesdb->table('appearing_in_diary');
        $builder->distinct();
        $builder->select('diary_no, item_no, court_no, list_date,advocate_title,advocate_name,advocate_type');
        $builder->where('is_submitted', true);
        $builder->where('is_active', true);
        $builder->where('list_date', $list_date_ymd);
        $builder->where('court_no', $courtno);
        $builder->orderBy('item_no');

        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function getDiaryDetails($diary_no)
    {
        $sql = "SELECT * FROM main WHERE diary_no = :diary_no:";

        // Prepare and execute the query
        $query = $this->db->query($sql, ['diary_no' => $diary_no]);

        // Fetch the single row result as an associative array
        $diary = $query->getRowArray();

        // Check if diary data is retrieved
        if ($diary) {
            return $diary; // Process or return the result as needed
        } else {
            return 'No diary found with the given number.';
        }
    }

    //........New added 29-08-2024........//

    // function parseDate($date)
    // { // From dd-mm-yyyy to yyyy-mm-dd
    //     $myDate = \DateTime::createFromFormat('d-m-Y', $date);
    //     return $myDate->format('Y-m-d');
    // }

    function getCaseUploadingList($causelistDate, $usercode)
    {
        // $db = \Config\Database::connect();

        // Query
        $builder = $this->db->table('proceedings p')
            ->distinct()
            ->select('p.id, p.upload_flag, p.uploaded_by, p.upload_date_time, m.diary_no, m.pet_name as petitioner_name, m.res_name as respondent_name, p.court_number, p.item_number')
            ->select("CASE WHEN p.app_no = '' THEN m.reg_no_display ELSE CONCAT('IA ', p.app_no, ' in ', m.reg_no_display) END AS registration_number_desc")
            ->select('(SELECT name FROM master.users WHERE master.users.usercode = p.uploaded_by LIMIT 1) as username')
            ->join('main m', 'm.diary_no = p.diary_no', 'inner')
            ->join('cl_printed cp', 'p.roster_id = cp.roster_id AND p.order_date = cp.next_dt', 'inner')
            ->where("p.generated_by LIKE '%,$usercode,%' OR p.generated_by LIKE '$usercode,%' OR p.generated_by LIKE '%,$usercode' OR p.generated_by = '$usercode'")
            ->where("DATE(p.order_date) =", $causelistDate)
            ->where('cp.display', 'Y')
            ->orderBy('p.item_number');

        $query = $builder->get();
        // echo $this->db->getLastQuery();

        $result = $query->getResultArray();
        return $result;
    }

    function getUploadedVernacularJudgmentsList($fromDate = null, $toDate = null)
    {
        // $condition = "";

        // $sql="SELECT distinct o.diary_no,
        //    group_concat(distinct trim(s.Name))as stateName,
        //    group_concat(distinct rac.agency_name)  as highCourt,
        //    concat(b.reg_no_display,' @ ',concat(SUBSTR(b.diary_no, 1, LENGTH(b.diary_no) - 4),'/',SUBSTR(b.diary_no, - 4))) as caseNo,
        //    concat(b.pet_name,' Vs. ',b.res_name) as causeTitle,
        //    DATE_FORMAT(o.order_date, '%d-%m-%Y') as judgmentDate,
        //    e.name as uploadedBy,
        //    DATE_FORMAT(o.entry_date, '%d-%m-%Y') as uploadedOn,
        //    o.pdf_name as filePath,
        //    vl.name as language
        //    FROM vernacular_orders_judgments o
        //    inner join vernacular_languages vl on o.ref_vernacular_languages_id=vl.id and vl.display='Y'
        //    left join main b ON o.diary_no = b.diary_no
        //    left join lowerct lct on (o.diary_no=lct.diary_no and is_order_challenged='Y')
        //    left join ref_agency_code rac on (lct.l_dist=rac.id and rac.is_deleted='f')
        //    left join state s on (lct.l_state=s.id_no and s.display='Y')
        //    left join users e ON e.usercode = o.user_code
        //    left join usertype u1 on e.usertype=u1.id
        //    WHERE  date(o.entry_date) between '".$fromDate."' and '".$toDate."'
        //    and o.display='Y'
        //    GROUP BY o.diary_no,vl.name
        //    order by stateName asc,highCourt asc,o.diary_no,uploadedOn desc";


        // $query = $this->db->query($sql);
        // if ($query->num_rows() >= 1)
        // {
        //     return $query->result_array();
        // }
        // else
        // {
        //     return false;
        // }

        $builder = $this->db->table('vernacular_orders_judgments o')
            ->distinct()
            ->select('o.diary_no')
            ->select("string_agg(distinct trim(s.name), ', ') as stateName", FALSE)
            ->select("string_agg(distinct rac.agency_name, ', ') as highCourt", FALSE)
            ->select("concat(b.reg_no_display, ' @ ', concat(substr(b.diary_no::text, 1, length(b.diary_no::text) - 4), '/', substr(b.diary_no::text, -4))) as caseNo", FALSE)
            ->select("concat(b.pet_name, ' Vs. ', b.res_name) as causeTitle", FALSE)
            ->select("to_char(o.order_date, 'DD-MM-YYYY') as judgmentDate", FALSE)
            ->select("e.name as uploadedBy")
            ->select("to_char(o.entry_date, 'DD-MM-YYYY') as uploaded_on", FALSE)
            ->select("o.pdf_name as filePath")
            ->select("vl.name as language")


            ->join('master.vernacular_languages vl', 'o.ref_vernacular_languages_id = vl.id AND vl.display = \'Y\'', 'inner')
            ->join('main b', 'o.diary_no = b.diary_no', 'left')
            ->join('lowerct lct', 'o.diary_no = lct.diary_no AND lct.is_order_challenged = \'Y\'', 'left')
            ->join('master.ref_agency_code rac', 'lct.l_dist = rac.id AND rac.is_deleted = \'F\'', 'left')
            ->join('master.state s', 'lct.l_state = s.id_no AND s.display = \'Y\'', 'left')
            ->join('master.users e', 'e.usercode = o.user_code', 'left')
            ->join('master.usertype u1', 'e.usertype = u1.id', 'left')
            ->where("date(o.entry_date) BETWEEN '$fromDate' AND '$toDate'")
            ->where('o.display', 'Y') // Assuming 'display' is also a CHAR column with 'Y' for active records
            ->groupBy('o.diary_no, vl.name, b.reg_no_display, b.diary_no, o.order_date, e.name, o.entry_date, o.pdf_name')
            ->orderBy(1, 'asc') // Ordering by the first column in SELECT
            ->orderBy(2, 'asc') // Ordering by the second column in SELECT
            ->orderBy(3) // Ordering by the third column in SELECT
            ->orderBy(4, 'desc'); // Ordering by the fourth column in SELECT
        $query = $builder->get();
        return $query->getResultArray();
    }

    public  function getSearchDiary($caseTypeId, $caseNo, $caseYear, $diaryNo, $diaryYear, $searchType)
    {
        $parameters = [];
        if ($searchType == 'C') {
            $sql = "SELECT distinct h.diary_no FROM
                     main_casetype_history h
                    WHERE (SUBSTRING_INDEX(h.new_registration_number, '-', 1) = cast(? as UNSIGNED) AND
                    CAST(? AS UNSIGNED) BETWEEN (SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2),'-',-1))
                    AND (SUBSTRING_INDEX(h.new_registration_number, '-', -1))
                    AND h.new_registration_year=?) AND h.is_deleted=?";
            array_push($parameters, $caseTypeId, $caseNo, $caseYear, 'f');
        } else if ($searchType == 'D') {
            $sql = "SELECT diary_no FROM main WHERE LEFT(diary_no::text, LENGTH(diary_no::text) - 4)=? AND RIGHT(diary_no::text, 4)=?";
            array_push($parameters, $diaryNo, $diaryYear);
        }

        $query = $this->db->query($sql, $parameters);
        pr($this->db->getLastQuery());
        $row = $query->getRowArray()['diary_no'];
        return $row->diary_no;
    }


    function getSittingPlan($causelistDate, $forPrint = 0)
    {
        /*$sql="select spcd.id as sitting_plan_court_details_id,spd.if_finalized,spd.id as sitting_plan_details_id,spd.next_dt,spcd.court_number,spcd.board_type,spcd.if_special_bench,spcd.header_remark,spcd.footer_remark,spcd.mainhead,spcd.if_in_printed,spcd.bench_start_time,group_concat(spjd.jcode order by j.judge_seniority) as judges
            from master.sitting_plan_details spd inner join master.sitting_plan_court_details spcd on spd.id=spcd.sitting_plan_details_id inner join master.sitting_plan_judges_details spjd on spcd.id=spjd.sitting_plan_court_details_id
            inner join master.judge j on spjd.jcode=j.jcode where spd.next_dt=? and spd.display=? and spcd.display=? and spjd.display=?
            group by spd.next_dt,spcd.court_number,spcd.board_type,spcd.if_special_bench,spcd.header_remark,spcd.footer_remark,spcd.mainhead
            order by court_number asc,board_type desc,if_special_bench asc";
		if($forPrint!=0){
			$sql="select spcd.id as sitting_plan_court_details_id,spd.if_finalized,spd.id as sitting_plan_details_id,spd.next_dt,spcd.court_number,spcd.board_type,spcd.if_special_bench,spcd.header_remark,spcd.footer_remark,spcd.mainhead,spcd.if_in_printed,spcd.bench_start_time,group_concat(spjd.jcode order by j.judge_seniority) as judges
            from master.sitting_plan_details spd inner join master.sitting_plan_court_details spcd on spd.id=spcd.sitting_plan_details_id inner join master.sitting_plan_judges_details spjd on spcd.id=spjd.sitting_plan_court_details_id
            inner join master.judge j on spjd.jcode=j.jcode where spd.next_dt=? and spd.display=? and spcd.display=? and spjd.display=?
            group by spd.next_dt,spcd.court_number,spcd.board_type,spcd.if_special_bench,spcd.header_remark,spcd.footer_remark,spcd.mainhead
            order by court_number asc,board_type desc,case when bench_start_time!='00:00:00' then bench_start_time else '23:59:00' end asc,if_special_bench desc";
		}*/

        //order by court_number asc,board_type desc,case when bench_start_time!='00:00:00' then bench_start_time else '23:59:00' end asc,if_special_bench desc";
        //$query = $this->db->query($sql,array($causelistDate,'Y','Y','Y'));
        //return $query->result_array();

        $sql = "
                SELECT 
                    spcd.id AS sitting_plan_court_details_id,
                    spd.if_finalized,
                    spd.id AS sitting_plan_details_id,
                    spd.next_dt,
                    spcd.court_number,
                    spcd.board_type,
                    spcd.if_special_bench,
                    spcd.header_remark,
                    spcd.footer_remark,
                    spcd.mainhead,
                    spcd.if_in_printed,
                    spcd.bench_start_time,
                    STRING_AGG(spjd.jcode::text, ',' ORDER BY j.judge_seniority) AS judges
                FROM 
                    master.sitting_plan_details spd 
                INNER JOIN 
                    master.sitting_plan_court_details spcd ON spd.id = spcd.sitting_plan_details_id 
                INNER JOIN 
                    master.sitting_plan_judges_details spjd ON spcd.id = spjd.sitting_plan_court_details_id
                INNER JOIN 
                    master.judge j ON spjd.jcode = j.jcode 
                WHERE 
                    spd.next_dt = ? AND spd.display = ? AND spcd.display = ? AND spjd.display = ?
                GROUP BY 
                    spd.next_dt, spcd.court_number, spcd.board_type, spcd.if_special_bench, spcd.header_remark, spcd.footer_remark, spcd.mainhead, spcd.id, spd.if_finalized,spd.id
                ORDER BY 
                    spcd.court_number ASC,
                    spcd.board_type DESC,
                    CASE 
                        WHEN spcd.bench_start_time != '00:00:00' THEN spcd.bench_start_time 
                        ELSE '23:59:00' 
                    END ASC,
                    spcd.if_special_bench DESC
                ";

        if ($forPrint != 0) {
            $sql = "
                SELECT 
                    spcd.id AS sitting_plan_court_details_id,
                    spd.if_finalized,
                    spd.id AS sitting_plan_details_id,
                    spd.next_dt,
                    spcd.court_number,
                    spcd.board_type,
                    spcd.if_special_bench,
                    spcd.header_remark,
                    spcd.footer_remark,
                    spcd.mainhead,
                    spcd.if_in_printed,
                    spcd.bench_start_time,
                    STRING_AGG(spjd.jcode::text, ',' ORDER BY j.judge_seniority) AS judges
                FROM 
                    master.sitting_plan_details spd 
                INNER JOIN 
                    master.sitting_plan_court_details spcd ON spd.id = spcd.sitting_plan_details_id 
                INNER JOIN 
                    master.sitting_plan_judges_details spjd ON spcd.id = spjd.sitting_plan_court_details_id
                INNER JOIN 
                    master.judge j ON spjd.jcode = j.jcode 
                WHERE 
                    spd.next_dt = ? AND spd.display = ? AND spcd.display = ? AND spjd.display = ?
                GROUP BY 
                    spd.next_dt, spcd.court_number, spcd.board_type, spcd.if_special_bench, spcd.header_remark, spcd.footer_remark, spcd.mainhead, spcd.id, spd.if_finalized, spd.id
                ORDER BY 
                    spcd.court_number ASC,
                    spcd.board_type DESC,
                    CASE 
                        WHEN spcd.bench_start_time != '00:00:00' THEN spcd.bench_start_time 
                        ELSE '23:59:00' 
                    END ASC,
                    spcd.if_special_bench DESC
                ";
        }
        //pr($sql);
        // Execute the query
        $query = $this->db->query($sql, array($causelistDate, 'Y', 'Y', 'Y'));

        return $query->getResultArray();
    }

    public function getWorkingDayData($causelistDate)
    {

        $return = [];
        $query = $this->db->table('master.sc_working_days')
            ->select('working_date, is_nmd, is_holiday, holiday_description')
            ->where('display', 'Y')
            ->where('working_date', $causelistDate)
            ->get();
        return $query->getResultArray();
    }

    function finalizeSittingPlan($causelistDate)
    {
        $result = false;
        $data = ['if_finalized' => 1];
        $builder = $this->db->table('master.sitting_plan_details');
        $builder->where('next_dt', $causelistDate)
            ->where('display', 'Y')
            ->update($data);
        if ($this->db->affectedRows() > 0) {
            $result = true;
        }

        return $result;
    }


    function getJudgeLeaveDetail($causeListDate, $isOnLeave = '', $jcode = 0)
    {
        $builder = $this->db->table('master.sitting_plan_judges_leave_details');
        $builder->select('judge.*, sitting_plan_judges_leave_details.id')
            ->join('master.judge', 'sitting_plan_judges_leave_details.jcode = judge.jcode')
            ->where('sitting_plan_judges_leave_details.display', 'Y');

        // Additional conditions based on parameters
        if ($isOnLeave !== '') {
            $builder->where('sitting_plan_judges_leave_details.is_on_leave', $isOnLeave);
        }

        $builder->where('sitting_plan_judges_leave_details.next_dt', $causeListDate);

        if ($jcode != 0) {
            $builder->where('sitting_plan_judges_leave_details.jcode', $jcode);
        }

        // Order by judge seniority
        $builder->orderBy('judge.judge_seniority', 'ASC');

        // Execute the query and get results
        $query = $builder->get();
        return $query->getResultArray();
    }


    //Judges_roster work started
    function saveJudgesOnLeave($data)
    {
        $result = false;
        $res = $this->getJudgeLeaveDetail($data['next_dt'], '', $data['jcode']);
        if (count($res) <= 0) {
            // Insert new record
            $this->db->table('master.sitting_plan_judges_leave_details')->insert($data);
            $result = true;
        } else {
            // Update existing record
            $id = $res[0]['id'];
            $this->db->table('master.sitting_plan_judges_leave_details')->update($data, ['id' => $id]);
            $result = true;
        }

        return $result;
    }

    function saveSittingDeatils($dataForSittingPlan)
    {
        //$this->db->insert('sitting_plan_details', $dataForSittingPlan);
        //return $this->db->insert_id();
        $this->db->table('master.sitting_plan_details')->insert($dataForSittingPlan);
        return $this->db->insertID();
    }

    function saveSittingPlan($data, $judges)
    {
        /*$this->db->insert('sitting_plan_court_details', $data);
		$sitting_plan_court_details_id=$this->db->insert_id();
		foreach($judges as $judge){
			$dataForInsert=array('sitting_plan_court_details_id'=>$sitting_plan_court_details_id,'jcode'=>$judge,'updated_on'=>date('Y-m-d H:i:s'),'usercode'=>$data['usercode'],'display'=>'Y');
			$this->db->insert('sitting_plan_judges_details', $dataForInsert);
		}*/



        //pr($judges);
        $builder = $this->db->table('master.sitting_plan_court_details');
        $builder->insert($data);
        $sitting_plan_court_details_id = $this->db->insertID();
        foreach ($judges as $judge) {

            if (!empty($judge)) {
                $dataForInsert = [
                    'sitting_plan_court_details_id' => $sitting_plan_court_details_id,
                    'jcode' => $judge,
                    'updated_on' => date('Y-m-d H:i:s'),
                    'usercode' => $data['usercode'],
                    'display' => 'Y'
                ];

                $builder = $this->db->table('master.sitting_plan_judges_details');
                $builder->insert($dataForInsert);
            }
        }
    }

    function deleteSittingPlan($sitting_plan_details_id, $if_finalized, $usercode = 1)
    {
        if ($if_finalized == 1) {
            $sql = "
                UPDATE master.sitting_plan_details
                SET 
                    no_of_times_modified_after_finalization = no_of_times_modified_after_finalization + 1,
                    usercode = :usercode:,
                    updated_on = NOW()
                WHERE 
                    id = :sitting_plan_details_id:;
        
                UPDATE master.sitting_plan_court_details
                SET 
                    display = 'N',
                    usercode = :usercode:,
                    updated_on = NOW()
                WHERE 
                    sitting_plan_details_id = :sitting_plan_details_id: 
                    AND display = 'Y';
        
                UPDATE master.sitting_plan_judges_details
                SET 
                    display = 'N',
                    usercode = :usercode:,
                    updated_on = NOW()
                WHERE 
                    sitting_plan_court_details_id IN (
                        SELECT id FROM master.sitting_plan_court_details 
                        WHERE sitting_plan_details_id = :sitting_plan_details_id:
                    ) 
                    AND (display = 'Y' OR display IS NULL);
            ";
        } else {
            $sql = "
                UPDATE master.sitting_plan_details
                SET 
                    no_of_times_modified_before_finalization = no_of_times_modified_before_finalization + 1,
                    usercode = :usercode:,
                    updated_on = NOW()
                WHERE 
                    id = :sitting_plan_details_id:;
        
                UPDATE master.sitting_plan_court_details
                SET 
                    display = 'N',
                    usercode = :usercode:,
                    updated_on = NOW()
                WHERE 
                    sitting_plan_details_id = :sitting_plan_details_id: 
                    AND display = 'Y';
        
                UPDATE master.sitting_plan_judges_details
                SET 
                    display = 'N',
                    usercode = :usercode:,
                    updated_on = NOW()
                WHERE 
                    sitting_plan_court_details_id IN (
                        SELECT id FROM master.sitting_plan_court_details 
                        WHERE sitting_plan_details_id = :sitting_plan_details_id:
                    ) 
                    AND (display = 'Y' OR display IS NULL);
            ";
        }


        $this->db->query($sql, [
            'usercode' => $usercode,
            'sitting_plan_details_id' => $sitting_plan_details_id,
        ]);
    }

    function doCopySittingPlan($fromDate, $toDate)
    {
        //$this->db->trans_begin();
        $usercode = 1; //TODO:: Set it from session.
        //Copy sitting_plan_judges_leave_details;
        $insertQuery = "insert into master.sitting_plan_judges_leave_details(next_dt,jcode,is_on_leave,usercode,updated_on,display)
              select ?,jcode,is_on_leave,?,now(),display
              from master.sitting_plan_judges_leave_details where next_dt=? and display=?";
        $this->db->query($insertQuery, array($toDate, $usercode, $fromDate, 'Y'));

        //copy sitting_plan_details;
        $dataForSittingPlan = [
            'next_dt' => $toDate,
            'display' => 'Y',
            'usercode' => $usercode,
            'updated_on' => date('Y-m-d H:i:s')
        ];
        $this->db->table('master.sitting_plan_details')->insert($dataForSittingPlan);
        $sittingPlanDetailId = $this->db->insertID();

        //copy sitting_plan_court_details;
        $sittingPlan = $this->getSittingPlan($fromDate);
        //var_dump($sittingPlan);
        foreach ($sittingPlan as $plan) {
            $dataCourtDetails = array('sitting_plan_details_id' => $sittingPlanDetailId, 'court_number' => $plan['court_number'], 'board_type' => $plan['board_type'], 'if_special_bench' => $plan['if_special_bench'], 'header_remark' => $plan['header_remark'], 'footer_remark' => $plan['footer_remark'], 'usercode' => $usercode, 'updated_on' => date('Y-m-d H:i:s'), 'display' => 'Y', 'if_in_printed' => $plan['if_in_printed'], 'bench_start_time' => $plan['bench_start_time']);
            $this->db->table('master.sitting_plan_court_details')->insert($dataCourtDetails);
            $sittingPlanCourtDetailId = $this->db->insertID();


            $judges = explode(',', $plan['judges']);
            foreach ($judges as $judge) {
                $dataForSittingPlanJudgesDetails = array(
                    'sitting_plan_court_details_id' => $sittingPlanCourtDetailId,
                    'jcode' => $judge,
                    'updated_on' => date('Y-m-d H:i:s'),
                    'usercode' => $usercode,
                    'display' => 'Y'
                );

                $this->db->table('master.sitting_plan_judges_details')->insert($dataForSittingPlanJudgesDetails);
            }
        }
        return true;
    }

    function ifRostergenerated($causeListDate, $mainhead)
    {
        $builder = $this->db->table('master.sitting_plan_details');
        $builder->select("CASE WHEN '$mainhead' = 'M' THEN if_roster_generated_misc ELSE if_roster_generated_regular END AS if_generated");
        $builder->where('next_dt', $causeListDate);
        $builder->where('display', 'Y');
        $query = $builder->get();
        $res = $query->getRow();
        return $res->if_generated ?? null;
    }


    function generateRoster1($causelistDate, $mainhead)
    {
        $status = 0;
        $this->db->trans_begin();
        $mf = 1;
        if ($mainhead == 'F') {
            $mf = 2;
        }
        $sittingPlan = $this->getSittingPlan($causelistDate);
        $benchDetails = $this->getBenchDetail();
        $sitting_plan_details_id = 0;
        //var_dump($sittingPlan);
        foreach ($sittingPlan as $plan) {
            $toDate = $plan['next_dt'];
            if ($mf == 2) {
                $toDate = '0000-00-00';
            }
            $rosterId = $this->getLastRosterId();
            $judges = explode(',', $plan['judges']);
            //Start get bench id
            $coramSize = sizeof($judges);
            $benchId = 0;
            foreach ($benchDetails as $index => $benchDetail) {
                if ($benchDetail['abbr'] == $coramSize && $benchDetail['board_type_mb'] == $plan['board_type']) {
                    $benchId = $benchDetail['id'];
                    //echo "<br/>Matched Coram size ".$coramSize." and board type is: ".$plan['board_type']." Id is: ".$benchDetail['id'];
                    unset($benchDetails[$index]);
                    break;
                }
            }
            if ($benchId == 0) {
                $status = 1;
                //echo "<br/>Un-Matched Coram size ".$coramSize." and board type is: ".$plan['board_type'];
            }
            //END
            $rosterId += 1;
            $dataForRoster = array(
                'id' => $rosterId,
                'bench_id' => $benchId,
                'from_date' => $plan['next_dt'],
                'to_date' => $toDate,
                'entry_dt' => date('Y-m-d H:i:s'),
                'display' => 'Y',
                'courtno' => $plan['court_number'],
                'm_f' => $mf,
                'frm_time' => $plan['bench_start_time'],
                'session' => 'Whole Day',
                'if_print_in' => $plan['if_in_printed']
            );

            $this->db->insert('roster', $dataForRoster);
            //echo $this->db->last_query().'<br/>';
            //$rosterId=$this->db->insert_id();

            foreach ($judges as $judge) {
                $dataForRosterJudge = array(
                    'roster_id' => $rosterId,
                    'judge_id' => $judge,
                    'display' => 'Y'
                );
                $this->db->insert('roster_judge', $dataForRosterJudge);
            }
            //Insert in Category_Allotment table
            $dataForCategoryAllotment = array(
                'stage_code' => 0,
                'stage_nature' => 'C',
                'ros_id' => $rosterId,
                'priority' => 1,
                'display' => 'Y',
                'case_type' => 0,
                'submaster_id' => 0
            );
            $this->db->insert('category_allottment', $dataForCategoryAllotment);
            //END
            //Update roster id misc./regular in sitting_plan_court_details
            $sitting_plan_details_id = $plan['sitting_plan_details_id'];
            $sitting_plan_court_details_id = $plan['sitting_plan_court_details_id'];
            $this->db->where('id', $sitting_plan_court_details_id);
            if ($mainhead == 'F') {
                $this->db->update('sitting_plan_court_details', array('roster_id_regular' => $rosterId));
            } else {
                $this->db->update('sitting_plan_court_details', array('roster_id_misc' => $rosterId));
            }
            //END
        }
        //update if_roster_generated_misc misc./regular in sitting_plan_details table
        $this->db->where('id', $sitting_plan_details_id);
        if ($mainhead == 'F') {
            $this->db->update('sitting_plan_details', array('if_roster_generated_regular' => 1));
        } else {
            $this->db->update('sitting_plan_details', array('if_roster_generated_misc' => 1));
        }
        //END
        //$this->db->trans_complete(); # Completing transaction

        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            return FALSE;
        } else if ($status == 1) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            # Everything is Perfect.
            # Committing data to the database.
            $this->db->trans_commit();
            return TRUE;
        }
    }
    function getLastRosterId()
    {
        $builder = $this->db->table('master.roster');
        $builder->selectMax('id');
        $query = $builder->get();
        $result = $query->getRow();

        return $result->id ?? null;
    }

    function getBenchDetail()
    {
        $builder = $this->db->table('master.roster_bench rb');
        $builder->select('rb.id, rb.bench_id, rb.bench_no, rb.priority, mb.abbr, mb.board_type_mb');
        $builder->join('master.master_bench mb', 'mb.id = rb.bench_id');
        $builder->where('rb.display', 'Y');
        $builder->where('mb.display', 'Y');
        $builder->orderBy('rb.bench_id, rb.bench_no');

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function generateRoster($causelistDate, $mainhead)
    {
        $status = 0;
        //$this->db->transBegin();
        $mf = ($mainhead === 'F') ? 2 : 1;

        $sittingPlan = $this->getSittingPlan($causelistDate);
        $benchDetails = $this->getBenchDetail();

        $sitting_plan_details_id = 0;

        foreach ($sittingPlan as $plan) {
            $toDate = $plan['next_dt'];
            if ($mf == 2) {
                $toDate = null;
            }
            $rosterId = $this->getLastRosterId();
            $judges = explode(',', $plan['judges']);

            // Start get bench id
            $coramSize = sizeof($judges);
            $benchId = 0;

            foreach ($benchDetails as $index => $benchDetail) {
                if (trim($benchDetail['abbr']) == trim($coramSize) && trim($benchDetail['board_type_mb']) == trim($plan['board_type'])) {
                    $benchId = $benchDetail['id'];
                    unset($benchDetails[$index]);
                    break;
                }
            }

            //if ($benchId == 0) {$status = 1;}

            $rosterId += 1;
            $dataForRoster = [
                'id' => $rosterId,
                'bench_id' => $benchId,
                'from_date' => $plan['next_dt'],
                'to_date' => $toDate,
                'entry_dt' => date('Y-m-d H:i:s'),
                'display' => 'Y',
                'courtno' => $plan['court_number'],
                'm_f' => $mf,
                'frm_time' => $plan['bench_start_time'],
                'session' => 'Whole Day',
                'if_print_in' => $plan['if_in_printed'],
                'tot_cases' => 0,
                'judges' => 0
            ];


            $this->db->table('master.roster')->insert($dataForRoster);


            foreach ($judges as $judge) {
                $dataForRosterJudge = [
                    'roster_id' => $rosterId,
                    'judge_id' => $judge,
                    'display' => 'Y'
                ];
                $this->db->table('master.roster_judge')->insert($dataForRosterJudge);
            }

            // Insert in Category_Allotment table
            $dataForCategoryAllotment = [
                'stage_code' => 0,
                'stage_nature' => 'C',
                'ros_id' => $rosterId,
                'priority' => 1,
                'display' => 'Y',
                'case_type' => 0,
                'submaster_id' => 0,
                'b_n' => 0
            ];
            $this->db->table('category_allottment')->insert($dataForCategoryAllotment);

            // Update roster id in sitting_plan_court_details
            $sitting_plan_details_id = $plan['sitting_plan_details_id'];
            $sitting_plan_court_details_id = $plan['sitting_plan_court_details_id'];

            if ($mainhead == 'F') {
                $this->db->table('master.sitting_plan_court_details')
                    ->where('id', $sitting_plan_court_details_id)
                    ->update(['roster_id_regular' => $rosterId]);
            } else {
                $this->db->table('master.sitting_plan_court_details')
                    ->where('id', $sitting_plan_court_details_id)
                    ->update(['roster_id_misc' => $rosterId]);
            }
        }

        // Update if_roster_generated in sitting_plan_details
        $builder = $this->db->table('master.sitting_plan_details');
        $data = ($mainhead == 'F') ? ['if_roster_generated_regular' => 1] : ['if_roster_generated_misc' => 1];
        $builder->where('id', $sitting_plan_details_id);
        $builder->update($data);

        // Complete the transaction
        if ($this->db->transStatus() === FALSE || $status == 1) {
            $this->db->transRollback();
            return false;
        } else {
            $this->db->transCommit();
            return true;
        }
        //return true;
    }

    public function getCauseTitle($diary_no)
    {
        $db = \Config\Database::connect();

        // Query to fetch pet_name and res_name
        $query = $db->table('main')
                    ->select("TRIM(pet_name) AS pet_name, TRIM(res_name) AS res_name")
                    ->where('diary_no', $diary_no)
                    ->get();

        return $query->getRowArray(); // Fetch results as an associative array
    }


    public function getOfficeReportDetails($diary_no, $order_date)
    {
        $db = \Config\Database::connect();

        // Query to fetch details from office_report_details table
        $query = $db->table('office_report_details')
                    ->select("COUNT(id) AS total, id, office_repot_name")
                    ->where('diary_no', $diary_no)
                    ->where('order_dt', $order_date)
                    ->where('display', 'Y')
                    ->groupBy('id, office_repot_name')
                    ->get();

        return $query->getRowArray(); // Return a single row as an associative array
    }


    public function addOfficeReportDetails($diary_no,$ucode,$office_repot_name,$sp_listed_on,$summary)
        {
           
            $data = [
                'diary_no' => $diary_no,
                'rec_dt' => date('Y-m-d H:i:s'), // Current timestamp
                'rec_user_id' => $ucode,
                'office_report_id' => '0',
                'office_repot_name' => $office_repot_name,
                'order_dt' => $sp_listed_on,
                'web_status' => 1, // Default value
                'summary' => $summary,
                'display' => 'Y',
                'status' => 0,
                'master_id' => 0,
                'batch' => 0,
                'user_ip' => getClientIP()  

            ];

            $insert_rec = $this->db->table('office_report_details')->insert($data);
            if(!empty($insert_rec))
            {
                return true;
            }
            else 
            {
                return false;
                
            }
        }
		
		



}
