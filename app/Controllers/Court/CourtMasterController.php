<?php

namespace App\Controllers\Court;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Court\CourtMasterModel;
use App\Libraries\phpqrcode\Qrlib;
use App\Libraries\Fpdf;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use App\Models\Common\Dropdown_list_model;
use App\Controllers\Court\LIVE_URL;
use DirectoryIterator;
use FilesystemIterator;

class CourtMasterController extends BaseController
{
    public $model;
    public $diary_no;
    public $qrlib;
    public $Fpdf;
    public $Dropdown_list_model;

    function __construct()
    {

        $this->model = new CourtMasterModel();
        $this->qrlib = new Qrlib();
        $this->Fpdf = new Fpdf();
        $this->Dropdown_list_model = new Dropdown_list_model();
		$this->request = service('request');
		$uri = $this->request->uri;<?php

        /**
         * Created by PhpStorm.
         * User: aktripathi
         * Date: 22/6/17
         * Time: 11:29 AM
         */
        class CourtMasterModel extends CI_Model
        {
            function __construct()
            {
                parent::__construct();
                //$this->e_services = $this->load->database('e_services', true);
                $this->appearance = $this->load->database('appearance', true);
                $this->rop_text_web = $this->load->database('rop_text_web', true);
            }
            function getBenchByJudge($causelistDate = null, $pJudge = null, $main_head,$board_type)
            {
                $m_f=1;
                if($main_head=='F'){
                    $m_f=2;
                }
                if ($causelistDate == null)
                    $causelistDate = date('d-m-Y');
                $causelistDate = $this->parseDate($causelistDate);
                $whereCondition = "rj.judge_id=".$pJudge." and ((to_date ='0000-00-00' and from_date<='".$causelistDate."' )
                      or ('".$causelistDate."' between  from_date and to_date)) and cp.next_dt='".$causelistDate."'";
        
                 $queryString = "select distinct r.id as roster_id,session,r.frm_time,r.courtno
          from roster r  inner join roster_judge rj on r.id=rj.roster_id
           inner join cl_printed cp on r.id=cp.roster_id
           inner join roster_bench rb on r.bench_id=rb.id
          inner join master_bench mb on rb.bench_id=mb.id
        where r.display='Y' and cp.display='Y' and mb.board_type_mb='".$board_type."' and r.m_f=".$m_f." and ".$whereCondition;
                $query = $this->db->query($queryString);
                return $query->result_array();
            }
            
            function getBenchByJudgeModified($causelistDate = null, $pJudge = null, $main_head,$board_type)
            {
                $m_f=1;
                if($main_head=='F'){
                    $m_f=2;
                }
                if ($causelistDate == null)
                    $causelistDate = date('d-m-Y');
                //$causelistDate = $this->parseDate($causelistDate);
                $whereCondition = "rj.judge_id=".$pJudge." and ((to_date ='0000-00-00' and from_date<='".$causelistDate."' )
                      or ('".$causelistDate."' between  from_date and to_date)) and cp.next_dt='".$causelistDate."'";
        
                $queryString = "select distinct r.id as roster_id,session,r.frm_time,r.courtno
          from roster r  inner join roster_judge rj on r.id=rj.roster_id
           inner join cl_printed cp on r.id=cp.roster_id
           inner join roster_bench rb on r.bench_id=rb.id
          inner join master_bench mb on rb.bench_id=mb.id
        where r.display='Y' and cp.display='Y' and mb.board_type_mb='".$board_type."' and r.m_f=".$m_f." and ".$whereCondition;
                $query = $this->db->query($queryString);
                return $query->result_array();
            }
            
            function getJudge($jcode=null){
                $this->db->order_by('jtype', 'ASC');
                $this->db->order_by('judge_seniority', 'ASC');
                $this->db->where('display','Y');
                if($jcode!=null){
                    $this->db->where_in('jcode',$jcode);
                }else{
                    $this->db->where('is_retired','N');
                }
                $query = $this->db->get('judge');//judge_seniority
                //echo $this->db->last_query();
                return $query->result_array();
            }
            function getAllJudge(){
                $this->db->order_by('judge_seniority', 'ASC');
                $this->db->where('display','Y');
                $query = $this->db->get('judge');//judge_seniority
                //echo $this->db->last_query();
                return $query->result_array();
            }
            function getAllJudgesNoRegistrar(){
                $this->db->order_by('judge_seniority', 'ASC');
                $this->db->where('display','Y');
                $this->db->where('jtype','J');
                $query = $this->db->get('judge');//judge_seniority
                //echo $this->db->last_query();
                return $query->result_array();
            }
        
            function getCaseGenerationList($bench, $main_head, $main_supp_flag, $board_type,$causelistDate)
            {
                $causelistDate = $this->parseDate($causelistDate);
                $whereCondition = "";
                if ($main_head != "")
                    $whereCondition = "hd.next_dt='".$causelistDate."' and rj.roster_id=" . $bench . " and hd.mainhead='" . $main_head . "' and hd.board_type='" . $board_type . "'";
                else
                    $whereCondition = "hd.next_dt='".$causelistDate."' and rj.roster_id=" . $bench . " and hd.board_type='" . $board_type . "'";
        
        
                 $queryString = "select distinct rj.roster_id,m.diary_no ,hd.next_dt as listing_date,m.pet_name as petitioner_name,m.res_name as respondent_name,r.courtno as court_number,hd.brd_slno as item_number,
                                case when hd.listed_ia ='' then m.reg_no_display else concat('IA ',hd.listed_ia,' in ',m.reg_no_display) end as registration_number_desc,m.pno,m.rno
                                from heardt hd inner join main m on hd.diary_no=m.diary_no inner join roster_judge rj on hd.roster_id=rj.roster_id inner join roster r on rj.roster_id=r.id INNER JOIN
                              cl_printed cp on hd.roster_id=cp.roster_id and hd.next_dt=cp.next_dt and hd.brd_slno between cp.from_brd_no and cp.to_brd_no AND hd.clno=cp.part
                                where cp.display='Y' and hd.main_supp_flag !=0 and (hd.conn_key is null or hd.conn_key=0 or hd.conn_key=hd.diary_no) and hd.brd_slno is not null and hd.brd_slno>0 and
                                " . $whereCondition . " union
                                select distinct rj.roster_id,m.diary_no ,hd.next_dt as listing_date,m.pet_name as petitioner_name,m.res_name as respondent_name,r.courtno as court_number,hd.brd_slno as item_number,
                                case when hd.listed_ia ='' then m.reg_no_display else concat('IA ',hd.listed_ia,' in ',m.reg_no_display) end as registration_number_desc,m.pno,m.rno
                                from last_heardt hd inner join main m on hd.diary_no=m.diary_no inner join roster_judge rj on hd.roster_id=rj.roster_id inner join roster r on rj.roster_id=r.id INNER JOIN
                              cl_printed cp on hd.roster_id=cp.roster_id and hd.next_dt=cp.next_dt and hd.brd_slno between cp.from_brd_no and cp.to_brd_no AND hd.clno=cp.part
                                where cp.display='Y' and hd.main_supp_flag !=0 and (hd.conn_key is null or hd.conn_key=0 or hd.conn_key=hd.diary_no) and hd.brd_slno is not null and hd.brd_slno>0 and hd.bench_flag='' and
                                " . $whereCondition . " union
                                select distinct rj.roster_id, m.diary_no, mm.date_on_decided AS listing_date,  m.pet_name AS petitioner_name,
                                m.res_name AS respondent_name, r.courtno AS court_number,mm.m_brd_slno AS item_number, m.reg_no_display AS registration_number_desc,m.pno,m.rno
                                from mention_memo mm  INNER JOIN  main m ON mm.diary_no = m.diary_no  INNER JOIN roster_judge rj ON mm.m_roster_id = rj.roster_id
                                INNER JOIN roster r ON rj.roster_id = r.id where mm.display='Y' and (m.conn_key IS NULL OR m.conn_key = 0
                                OR m.conn_key = mm.diary_no) AND mm.m_brd_slno IS NOT NULL AND mm.m_brd_slno > 0 AND mm.date_on_decided = '".$causelistDate."'
                                AND rj.roster_id = " . $bench . "   order by item_number";
        
        
                //echo $queryString;
                $query = $this->db->query($queryString);
                return $query->result_array();
            }
        
            function getCoramInCourt($bench)
            {
                $queryString = "select r.courtno,group_concat(rj.judge_id) as coram from roster_judge rj inner join roster r on rj.roster_id=r.id where roster_id=? and rj.display=? order by rj.judge_id asc";
                $query = $this->db->query($queryString,array($bench,'Y'));
                return $query->result_array();
            }
        
            function parseDate($date)
            { // From dd-mm-yyyy to yyyy-mm-dd
                $myDate = DateTime::createFromFormat('d-m-Y', $date);
                return $myDate->format('Y-m-d');
            }
        
            function revParseDate($date)
            { // From yyyy-mm-dd to dd-mm-yyyy
                return date("d-m-Y", strtotime($date));
            }
        
            function getCmNsh()
            {
                // $queryString = "select * from users where display='Y' and empid in (564,583,724,742,730,1101,1004,767,1003,1144,983,680,1047,979,1017,955,970, 1020,1091,1074,759,967, 1009,1065,1032,1158,1222,1172,1169,1178,1180,1166,2618,2618) order by name ASC";
                $queryString=" select u.* from users u inner join court_masters cm on u.usercode=cm.usercode
                     where u.display=? and cm.display=? and cm.is_nsh=? order by u.name ASC";
                $query = $this->db->query($queryString,array('Y','Y','Y'));
                return $query->result_array();
            }
        
            function getUserDetail($usercode)
            {
                $this->db->where('usercode', $usercode);
                $this->db->where('display', 'Y');
                $query = $this->db->get('users');
                return $query->result_array();
            }
        
            function getCaseDetails($diary_no,$roster_id,$causelistDate)
            {
                $causelistDate = $this->parseDate($causelistDate);
                $queryString="";
                $queryString = "select hd.diary_no,hd.listed_ia as ia,m.active_fil_no as registration_number,m.active_reg_year as registration_year,
                        m.active_casetype_id as casetype_id,m.pet_name,m.res_name,hd.brd_slno as item_number,m.reg_no_display,br.remark as remark,us.section_name,m.pno,m.rno,(select casename from casetype where casecode=m.casetype_id) as diary_casetype
                        from heardt hd inner join main m on hd.diary_no=m.diary_no left outer join brdrem br on
                        hd.diary_no=br.diary_no left outer join users u on m.dacode=u.usercode left outer join usersection us
                        on u.section=us.id  where hd.next_dt='".$causelistDate."' and hd.diary_no=".$diary_no." and hd.roster_id=".$roster_id."
                        union
                        select hd.diary_no,hd.listed_ia as ia,m.active_fil_no as registration_number,m.active_reg_year as registration_year,
                        m.active_casetype_id as casetype_id,m.pet_name,m.res_name,hd.brd_slno as item_number,m.reg_no_display,br.remark as remark,us.section_name ,m.pno,m.rno,(select casename from casetype where casecode=m.casetype_id) as diary_casetype
                        from last_heardt hd inner join main m on hd.diary_no=m.diary_no left outer join brdrem br on
                        hd.diary_no=br.diary_no left outer join users u on m.dacode=u.usercode left outer join usersection us
                        on u.section=us.id  where  hd.next_dt='".$causelistDate."' and hd.bench_flag='' and hd.diary_no=".$diary_no." and hd.roster_id=".$roster_id."
                        union
                        select hd.diary_no,'' as ia,m.active_fil_no as registration_number,m.active_reg_year as registration_year,
                        m.active_casetype_id as casetype_id,m.pet_name,m.res_name,hd.m_brd_slno as item_number,m.reg_no_display,br.remark as remark,us.section_name ,m.pno,m.rno,(select casename from casetype where casecode=m.casetype_id) as diary_casetype
                        from mention_memo hd inner join main m on hd.diary_no=m.diary_no left outer join brdrem br on
                        hd.diary_no=br.diary_no left outer join users u on m.dacode=u.usercode left outer join usersection us
                        on u.section=us.id  where hd.display='Y' and hd.date_on_decided='".$causelistDate."' and hd.diary_no=".$diary_no." and hd.m_roster_id=".$roster_id."";
                //echo $queryString;
                $query = $this->db->query($queryString);
                $res = $query->result();  // this returns an object of all results
                $row = $res[0];
                return $row;
            }
            function getIADetails($listedIAs,$diaryNo=0){
                $queryString="SELECT * FROM (SELECT  d.docnum, d.docyear, d.doccode1,
                        (CASE WHEN dm.doccode1 = 19 THEN other1 ELSE docdesc END) docdesp,
                        d.other1, d.iastat FROM docdetails d
                        INNER JOIN docmaster dm ON dm.doccode1 = d.doccode1 AND dm.doccode = d.doccode
                        WHERE  d.doccode = 8 AND dm.display = 'Y' AND
                        FIND_IN_SET(CAST(CONCAT(docnum,docyear) as SIGNED), TRIM(BOTH ',' FROM REPLACE(REPLACE(REPLACE('$listedIAs','/',''),' ',''),' ',''))) > 0
                        and d.diary_no=$diaryNo) a
                        WHERE docdesp != ''
                        ORDER BY docdesp";
        
                $query = $this->db->query($queryString);
                return $query->result_array();
            }
            function connectedCaseDetails($diary_no,$roster_id,$causelistDate)
            {
                $causelistDate = $this->parseDate($causelistDate);
                $queryString="";
                $queryString = "select  * from
                      (select ct.ent_dt,m.diary_no,hd.conn_key,m.diary_no_rec_date,hd.listed_ia as ia,m.active_fil_no as registration_number,m.active_reg_year as registration_year
                      ,m.reg_no_display,m.active_casetype_id as casetype_id,br.remark,us.section_name from heardt hd inner join main m on hd.diary_no=m.diary_no
                      left outer join conct ct on m.diary_no=ct.diary_no
                      left outer join users u on m.dacode=u.usercode left outer join usersection us on u.section=us.id
                      left outer join brdrem br on hd.diary_no=br.diary_no where ct.list='Y' and hd.next_dt='".$causelistDate."' and hd.conn_key=".$diary_no." and
                      hd.conn_key<>hd.diary_no and hd.roster_id=".$roster_id."
                      union
                      select ct.ent_dt,m.diary_no,hd.conn_key,m.diary_no_rec_date,hd.listed_ia as ia,m.active_fil_no as registration_number,m.active_reg_year as registration_year
                      ,m.reg_no_display,m.active_casetype_id as casetype_id,br.remark,us.section_name from last_heardt hd inner join main m on hd.diary_no=m.diary_no
                      left outer join conct ct on m.diary_no=ct.diary_no
                      left outer join users u on m.dacode=u.usercode left outer join usersection us on u.section=us.id
                      left outer join brdrem br on hd.diary_no=br.diary_no where hd.next_dt='".$causelistDate."' and hd.bench_flag='' and hd.conn_key=".$diary_no." and
                      hd.conn_key<>hd.diary_no and hd.roster_id=".$roster_id."
                      union
                      select ct.ent_dt,m.diary_no,m_conn_key as conn_key,m.diary_no_rec_date,'' as ia,m.active_fil_no as registration_number,m.active_reg_year as registration_year
                      ,m.reg_no_display,m.active_casetype_id as casetype_id,br.remark,us.section_name from mention_memo hd inner join main m on hd.diary_no=m.diary_no
                      left outer join conct ct on m.diary_no=ct.diary_no
                      left outer join users u on m.dacode=u.usercode left outer join usersection us on u.section=us.id
                      left outer join brdrem br on hd.diary_no=br.diary_no where hd.display='Y' and hd.date_on_decided='".$causelistDate."' and hd.m_conn_key=".$diary_no." and
                      hd.m_conn_key<>hd.diary_no and hd.m_roster_id=".$roster_id.") aa
                      group by diary_no
                      order by if(ent_dt is not null,ent_dt,999) ASC, cast(SUBSTRING(diary_no,-4) as signed) ASC, cast(LEFT(diary_no,length(diary_no)-4) as signed ) ASC";
                $query = $this->db->query($queryString);
                return $query->result_array();
            }
        
        
            function getAdvocateAppearanceDetails($diary_no,$adv_for,$causelistDate, $aor_code)
            {
                $queryString="";
                if($adv_for=='P') {
                    $pet_res_flag = "and a.appearing_for='P'";
                }
                else{
                    $pet_res_flag = "and a.appearing_for in ('R','I','N')";
                }
                $queryString = "select distinct advocate_type, advocate_title, advocate_name from appearing_in_diary a
        where a.diary_no in ? $pet_res_flag and a.list_date = ? and aor_code = ?
        and a.is_active = 1 and a.is_submitted = 1 order by priority";
                $query = $this->appearance->query($queryString,array($diary_no,date('Y-m-d', strtotime($causelistDate)),$aor_code));
                return $query->result_array();
            }
        
            function getAdvocateAppearanceAORIncludeORExclude($diary_no, $adv_for, $causelistDate, $aor_code)
            {
                $queryString="";
                if($adv_for=='P') {
                    $pet_res_flag = "and a.appearing_for='P'";
                }
                else{
                    $pet_res_flag = "and a.appearing_for in ('R','I','N')";
                }
                $queryString = "select id from appearing_in_diary a
        where a.diary_no in ? $pet_res_flag and a.list_date = ? and aor_code = ? and advocate_type = 'AOR'
        and a.is_active = 1 and a.is_submitted = 1 limit 1";
                $query = $this->appearance->query($queryString,array($diary_no,date('Y-m-d', strtotime($causelistDate)),$aor_code));
                return $query->result_array();
            }
        
            function getAdvocateDetails($diary_no,$adv_for)
            {
                $queryString="";
                if($adv_for=='P'){
                    $queryString = " select distinct a.pet_res, b.aor_code, b.bar_id as advoate_code,b.title,b.name as advocate_name,b.if_aor from advocate a inner join bar b on a.advocate_id=b.bar_id where a.diary_no in
                                 (select diary_no from heardt where diary_no in ?)
                                 and a.pet_res='P' and a.display=? and b.isdead=? and if_sen=?
                                 ORDER BY IF(pet_res in ('I','N'), 99, 0) ASC, adv_type DESC, pet_res_no ASC";
                }
                elseif ($adv_for=='R'){
                    $queryString = " select distinct a.pet_res, b.aor_code, b.bar_id as advoate_code,b.title,b.name as advocate_name,b.if_aor from advocate a inner join bar b on a.advocate_id=b.bar_id where a.diary_no in
                                 (select diary_no from heardt where diary_no in ?)
                                 and a.pet_res in ('R','I','N') and a.display=? and b.isdead=? and if_sen=?
                                 ORDER BY IF(pet_res in ('I','N'), 99, 0) ASC, adv_type DESC, pet_res_no ASC";
                }
        
                $query = $this->db->query($queryString,array($diary_no,'Y','N','N'));
                //echo $this->db->last_query();
        
                return $query->result_array();
            }
        
        
            /*    function getAdvocateDetails($diary_no,$adv_for)
                {
                    $queryString="";
                    if($adv_for=='P'){
                        $queryString = " select distinct b.bar_id as advoate_code,b.title,b.name as advocate_name,b.if_aor from advocate a inner join bar b on a.advocate_id=b.bar_id where a.diary_no in
                                     (select diary_no from heardt where diary_no in ?)
                                     and a.pet_res='P' and a.display=? and b.isdead=? and if_sen=?
                                     ORDER BY IF(pet_res in ('I','N'), 99, 0) ASC, adv_type DESC, pet_res_no ASC";
                    }
                    elseif ($adv_for=='R'){
                        $queryString = " select distinct b.bar_id as advoate_code,b.title,b.name as advocate_name,b.if_aor from advocate a inner join bar b on a.advocate_id=b.bar_id where a.diary_no in
                                     (select diary_no from heardt where diary_no in ?)
                                     and a.pet_res in ('R','I','N') and a.display=? and b.isdead=? and if_sen=?
                                     ORDER BY IF(pet_res in ('I','N'), 99, 0) ASC, adv_type DESC, pet_res_no ASC";
                    }
        
                    $query = $this->db->query($queryString,array($diary_no,'Y','N','N'));
                    //echo $this->db->last_query();
                    return $query->result_array();
                }*/
            function getLowerCourtDetails($diary_no)
            {
                $queryString = "select lc.lower_court_id,lc.ct_code,lc.lct_dec_dt,
        case when ct_code=4 then (select short_description from casetype where casecode=lc.lct_casetype) else
        (select type_sname from lc_hc_casetype where lccasecode=lc.lct_casetype) end as casetype,
                              lc.lct_casetype,lc.lct_caseno,lc.lct_caseyear,
                              case when ct_code=4 then 'Supreme Court of India' else
                              rgc.agency_name end as agency_name,
                              (select name from state where id_no= lc.l_state)  state_name
                              ,lc.is_order_challenged from lowerct lc left outer join ref_agency_code rgc on lc.l_state=rgc.cmis_state_id and lc.l_dist=rgc.id where lc.lw_display=? and lc.is_order_challenged=? and diary_no=?";
                $query = $this->db->query($queryString,array('Y','Y',$diary_no));
                return $query->result_array();
            }
            function getTentativeSection($diary_no){
                $queryString="select tentative_section(?) as section";
                $query = $this->db->query($queryString,array($diary_no));
                $res = $query->result();  // this returns an object of all results
                $row = $res[0];
                return $row;
            }
            function getJudgeName($coram)
            {
                $queryString = "select jcode,jname,first_name,sur_name from judge where jcode in (".$coram.") order by judge_seniority";
                $query = $this->db->query($queryString);
                return $query->result_array();
            }
            function getUserNameAndDesignation($usercode){
                $queryString = "select u.name,ut.type_name,u.section,u.usertype from users u inner join usertype ut on u.usertype=ut.id where usercode=?";
                $query = $this->db->query($queryString,array($usercode));
                $res = $query->result();  // this returns an object of all results
                $row = $res[0];
                return $row;
            }
            function updateProceedingsDetail($data){
                $selectQuery="select id,generated_by from proceedings where order_date=? and roster_id=? and court_number=? and item_number=? and diary_no=?";
                $query = $this->db->query($selectQuery,array($data['order_date'],$data['roster_id'],$data['court_number'],$data['item_number'],$data['diary_no']));
                //var_dump($query);
                $res = $query->result_array();
                if(sizeof($res)>0)
                {
                    $generated_by = $res[0]['generated_by'];
                    $id=$res[0]['id'];
                    $generated_users=explode(',',$generated_by);
                    if(!in_array($data['generated_by'], $generated_users))
                        $generated_by.=','.$data['generated_by'];
        
                    $queryString="update proceedings set is_reportable=".$data['is_reportable'].",generated_by='".$generated_by."',generated_on=now() where id=".$id;
                    $this->db->query($queryString);
                }
                else
                {
                    $queryString="INSERT INTO `proceedings`
                                    (`order_date`,
                                    `court_number`,
                                    `item_number`,
                                    `diary_no`,
                                    `generated_by`,
                                    `generated_on`,
                                    `file_name`,
                                    `order_type`,
                                    `is_oral_mentioning`,
                                    `app_no`,
                                    `registration_number`,
                                    `registration_year`,
                                    `roster_id`,`is_reportable`)
                                    select '".$data['order_date']."',".$data['court_number'].",".$data['item_number'].",".$data['diary_no'].",'".$data['generated_by']."',now(),'".$data['file_name']."','".$data['order_type']."',".$data['is_oral_mentioning'].",hd.listed_ia,m.active_fil_no,m.active_reg_year,".$data['roster_id'].",".$data['is_reportable']." from heardt hd inner join main m on hd.diary_no=m.diary_no where hd.diary_no=".$data['diary_no']." and hd.roster_id=".$data['roster_id']." and hd.brd_slno=".$data['item_number']." and hd.next_dt='".$data['order_date']."'
                                    union select '".$data['order_date']."',".$data['court_number'].",".$data['item_number'].",".$data['diary_no'].",'".$data['generated_by']."',now(),'".$data['file_name']."','".$data['order_type']."',".$data['is_oral_mentioning'].",hd.listed_ia,m.active_fil_no,m.active_reg_year,".$data['roster_id'].",".$data['is_reportable']." from last_heardt hd inner join main m on hd.diary_no=m.diary_no where hd.diary_no=".$data['diary_no']." and hd.roster_id=".$data['roster_id']." and hd.brd_slno=".$data['item_number']." and hd.next_dt='".$data['order_date']."'
                                    union select '".$data['order_date']."',".$data['court_number'].",".$data['item_number'].",".$data['diary_no'].",'".$data['generated_by']."',now(),'".$data['file_name']."','".$data['order_type']."',".$data['is_oral_mentioning'].",'',m.active_fil_no,m.active_reg_year,".$data['roster_id'].",".$data['is_reportable']." from mention_memo hd inner join main m on hd.diary_no=m.diary_no where hd.diary_no=".$data['diary_no']." and hd.m_roster_id=".$data['roster_id']." and hd.m_brd_slno=".$data['item_number']." and hd.date_on_decided='".$data['order_date']."'";
                    $this->db->query($queryString);
                }
            }
            function convertToTitleCase($str){
                return str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($str))));
            }
            function insertOrdernetDeleted($data){
                if($data['tbl_name'] == 'ordernet') {
                    $queryString = "INSERT INTO `order_type_changed_log`
                                    (tbl_id, tbl_name, user_id, ent_dt, order_type, modified_date, modified_by)
                                    values ('" . $data['id'] . "','" . $data['tbl_name'] . "','" . $data['usercode'] . "','" . $data['ent_dt'] . "',
                                    '" . $data['type'] . "','" . $data['modified_date'] . "','" . $data['modified_by'] . "')";
                }
                else if($data['tbl_name'] == 'tempo') {
                    $queryString = "INSERT INTO `order_type_changed_log`
                                    (tbl_id, tbl_name, user_id, ent_dt, order_type, modified_date, modified_by)
                                    values ('" . $data['id'] . "','" . $data['tbl_name'] . "','" . $data['usercode'] . "','" . $data['ent_dt'] . "',
                                    '" . $data['jt'] . "','" . $data['modified_date'] . "','" . $data['modified_by'] . "')";
                }
                else if($data['tbl_name'] == 'scordermain') {
                    $queryString = "INSERT INTO `order_type_changed_log`
                                    (tbl_id, tbl_name, user_id, ent_dt, order_type, modified_date, modified_by)
                                    values ('" . $data['id_dn'] . "','" . $data['tbl_name'] . "','" . $data['usercode'] . "','" . $data['ent_dt'] . "',
                                    '" . $data['order_type'] . "','" . $data['modified_date'] . "','" . $data['modified_by'] . "')";
                }
                else if($data['tbl_name'] == 'old_rop' OR $data['tbl_name'] == 'ordertext' OR $data['tbl_name'] == 'oldordtext') {
                    $queryString = "INSERT INTO `order_type_changed_log`
                                    (tbl_id, tbl_name, user_id, ent_dt, order_type, modified_date, modified_by)
                                    values ('" . $data['pno'] . "','" . $data['tbl_name'] . "','" . $data['usercode'] . "','" . $data['ent_dt'] . "',
                                    '" . $data['order_type'] . "','" . $data['modified_date'] . "','" . $data['modified_by'] . "')";
                }
                $this->db->query($queryString);
            }
            function updateOrdernetFlag($id, $type, $usercode, $tbl_name){
                if($type == 'J'){
                    $type_long = "judgment";
                }
                else if($type == 'FO'){
                    $type_long = "finalorder";
                }
                else{
                    $type_long = "rop";
                }
        
        
                if($tbl_name == 'ordernet')
                    $updateString=$this->db->query("update ordernet set type='".$type."', usercode = '".$usercode."', ent_dt=now() where id=".$id." ");
                else if($tbl_name == 'tempo')
                    $updateString=$this->db->query("update tempo set jt='" . $type_long . "', usercode = '" . $usercode . "', ent_dt=now() where id=" . $id . " ");
                else if($tbl_name == 'scordermain')
                    $updateString=$this->db->query("update scordermain set order_type='" . $type_long . "', usercode = '" . $usercode . "', ent_dt=now() where id_dn=" . $id . " ");
                else if($tbl_name == 'old_rop' OR $tbl_name == 'ordertext' OR $tbl_name == 'oldordtext')
                    $updateString=$this->rop_text_web->query("update $tbl_name set order_type='" . $type_long . "', usercode = '" . $usercode . "', ent_dt=now() where pno=" . $id . " ");
        
                return $updateString;
            }
        
        
            function getFileName($diary_no,$listing_date,$roster_id,$item_no)
            {
                //$queryString = "select skey as casetype_desc,diary_no,active_fil_no as registration_number,active_reg_year as registration_year from main m left outer join casetype c on m.active_casetype_id=c.casecode where diary_no=$diary_no";
                $queryString="select skey as casetype_desc,m.diary_no,active_fil_no as registration_number,active_reg_year
        as registration_year,p.file_name from main m left outer join casetype c
        on m.active_casetype_id=c.casecode left outer join proceedings p
        on m.diary_no = p.diary_no and p.order_date='".$listing_date."' and p.roster_id=$roster_id and p.item_number=$item_no where m.diary_no=$diary_no";
                //echo $queryString;
                $query = $this->db->query($queryString);
                $res = $query->result();  // this returns an object of all results
                $row = $res[0];
                return $row;
            }
        
            function getCaseUploadingList($causelistDate,$usercode)
            {
                /*$queryString = "select distinct p.id,p.upload_flag,p.uploaded_by,p.upload_date_time,m.diary_no ,m.pet_name as petitioner_name,m.res_name as respondent_name,p.court_number as court_number,p.item_number,
                                 case when hd.listed_ia ='' then m.reg_no_display else concat('IA ',hd.listed_ia,' in ',m.reg_no_display) end as registration_number_desc,(select name from users where usercode=p.uploaded_by) as username
                                 from heardt hd inner join main m on hd.diary_no=m.diary_no inner join proceedings p on hd.diary_no=p.diary_no and hd.brd_slno=p.item_number
                                 and hd.roster_id = p.roster_id where (hd.conn_key is null or hd.conn_key=0 or hd.conn_key=hd.diary_no) and hd.brd_slno is not null and hd.brd_slno>0
                                 and find_in_set('".$usercode."',p.generated_by) and p.order_date='".$causelistDate."'
                                  union
                                  select distinct p.id,p.upload_flag,p.uploaded_by,p.upload_date_time,m.diary_no ,m.pet_name as petitioner_name,m.res_name as respondent_name,p.court_number as court_number,p.item_number,
                                 case when hd.listed_ia ='' then m.reg_no_display else concat('IA ',hd.listed_ia,' in ',m.reg_no_display) end as registration_number_desc,(select name from users where usercode=p.uploaded_by) as username
        
                                 from last_heardt hd inner join main m on hd.diary_no=m.diary_no inner join proceedings p on hd.diary_no=p.diary_no and hd.brd_slno=p.item_number
                                 and hd.roster_id = p.roster_id where hd.bench_flag='' and (hd.conn_key is null or hd.conn_key=0 or hd.conn_key=hd.diary_no) and hd.brd_slno is not null and hd.brd_slno>0
                                 and find_in_set('".$usercode."',p.generated_by) and p.order_date='".$causelistDate."'
                                 union
                                  select distinct p.id,p.upload_flag,p.uploaded_by,p.upload_date_time,m.diary_no ,m.pet_name as petitioner_name,m.res_name as respondent_name,p.court_number as court_number,p.item_number,
                                 m.reg_no_display as registration_number_desc,(select name from users where usercode=p.uploaded_by) as username
                                 from mention_memo hd inner join main m on hd.diary_no=m.diary_no inner join proceedings p on hd.diary_no=p.diary_no and hd.m_brd_slno=p.item_number
                                 and hd.m_roster_id = p.roster_id where hd.display='Y' and (hd.m_conn_key is null or hd.m_conn_key=0 or hd.m_conn_key=hd.diary_no) and hd.m_brd_slno is not null and hd.m_brd_slno>0
                                 and find_in_set('".$usercode."',p.generated_by) and p.order_date='".$causelistDate."'
                                  order by item_number";*/
                $queryString="select distinct p.id,p.upload_flag,p.uploaded_by,p.upload_date_time,m.diary_no ,m.pet_name as petitioner_name,m.res_name as respondent_name,p.court_number as court_number,p.item_number,
                                case when p.app_no ='' then m.reg_no_display else concat('IA ',p.app_no,' in ',m.reg_no_display) end as registration_number_desc,(select name from users where usercode=p.uploaded_by) as username
                                from main m inner join proceedings p on m.diary_no=p.diary_no
                                inner join cl_printed cp on p.roster_id=cp.roster_id and p.order_date=cp.next_dt and cp.display='Y'
                                where find_in_set('".$usercode."',p.generated_by) and p.order_date='".$causelistDate."' order by item_number";
                //echo $queryString;
                $query = $this->db->query($queryString);
                return $query->result_array();
            }
            function getFileProceedingDetail($file_name,$causelistDate){
                $queryString = "select * from proceedings where file_name='".$file_name."' and order_date='".$causelistDate."' and display='Y'";
                //echo $queryString;
                $query = $this->db->query($queryString);
                $res = $query->result();  // this returns an object of all results
                return $res;
        
            }
            function getDiaryProceedingDetail($diary_number,$causelistDate,$court_number,$item_number,$roster_id,$orderType){
                $queryString="";
                if($orderType=='Order')
                    $queryString="select p.*,o.pdfname,o.usercode,o.ent_dt,o.type from proceedings p left join ordernet o on p.ordernet_id=o.id where p.diary_no=? and date(p.order_date)=? and p.court_number=? and p.item_number=? and p.roster_id=? and p.display=? and p.order_type='O'";
                else if($orderType=='Judgement')
                    $queryString="select p.*,o.pdfname,o.usercode,o.ent_dt,o.type from proceedings p left join ordernet o on p.diary_no=o.diary_no and p.order_date=o.orderdate and p.roster_id=o.roster_id and o.type='J' where p.diary_no=? and date(p.order_date)=? and p.court_number=? and p.item_number=? and p.roster_id=? and p.display=?";
                else if($orderType=='FinalOrder')
                    $queryString="select p.*,o.pdfname,o.usercode,o.ent_dt,o.type from proceedings p left join ordernet o on p.diary_no=o.diary_no and p.order_date=o.orderdate and p.roster_id=o.roster_id and o.type='FO' where p.diary_no=? and date(p.order_date)=? and p.court_number=? and p.item_number=? and p.roster_id=? and p.display=?";
                //echo $queryString;
                $query = $this->db->query($queryString,array($diary_number,$causelistDate,$court_number,$item_number,$roster_id,'Y'));
                $res = $query->result();  // this returns an object of all results
                return $res;
            }
            function getDiaryJudmentFinalOrderDetail($id, $tbl_name){
                $queryString="";
                if($tbl_name == 'ordernet')
                    $queryString="select o.* from ordernet o where o.id = $id and o.display = 'Y'";
                else if($tbl_name == 'tempo')
                    $queryString="select o.* from tempo o where o.id = $id ";
                else if($tbl_name == 'scordermain')
                    $queryString="select o.* from scordermain o where o.id_dn = $id ";
                else if($tbl_name == 'old_rop')
                    $queryString="select o.* from rop_text_web.old_rop o where o.pno = $id ";
                else if($tbl_name == 'ordertext')
                    $queryString="select o.* from rop_text_web.ordertext o where o.pno = $id ";
                else if($tbl_name == 'oldordtext')
                    $queryString="select o.* from rop_text_web.oldordtext o where o.pno = $id ";
        
                //echo $queryString;
                $query = $this->db->query($queryString);
                $res = $query->result_array();  // this returns an object of all results
                return $res;
            }
        
            /*function insertProceedingsInOrderNet($data)
            {
                $isReportable='N';
                if($data['is_reportable']==1){
                    $isReportable='Y';
                }
                $result=0;
                $selectString="select * from ordernet where diary_no=".$data['diary_no']." and orderdate='".$data['order_date']."' and roster_id=".$data['roster_id']." and display='Y' and type='O'";
                $query = $this->db->query($selectString);
                $res = $query->result();
                if(!$res){
                    $ordernet_id=0;
                    $queryString="insert into ordernet(diary_no,perj,orderdate,pdfname,usercode,ent_dt,type,h_p,afr,display,roster_id,c_type,c_num,c_year,orderTextData)
                            values(".$data['diary_no'].",0,'".$data['order_date']."','".$data['pdf_name']."',".$data['usercode'].",now(),'".$data['type']."','P','".$isReportable."','Y',".$data['roster_id'].",".$data['case_type'].",'".$data['registration_number']."',".$data['registration_year'].",'".$data['orderTextData']."')";
                    //echo "<br/>".$queryString;
                    $this->db->query($queryString);
                    $ordernet_id= $this->db->insert_id();
                    if($ordernet_id!=0){
                        $updateString="update proceedings set upload_flag=1,uploaded_by=".$data['usercode'].",upload_date_time=now(),ordernet_id=".$ordernet_id." where file_name='".$data['filename']."'";
                        //echo "<br/>".$updateString;
                        $this->db->query($updateString);
                        $result=1;
                    }
                }
                return $result;
            }*/
            function insertProceedingsInOrderNet($data,$myhash,$myhashWithDateTime)
            {
                $isReportable='N';
                if($data['is_reportable']==1){
                    $isReportable='Y';
                }
                $perj=0;
                if(isset($data['presiding_judge'])){
                    $perj=$data['presiding_judge'];
                }
                $result=0;
                $selectString="select * from ordernet where diary_no=".$data['diary_no']." and orderdate='".$data['order_date']."' and roster_id=".$data['roster_id']." and display='Y' and type='".$data['type']."'";
                $query = $this->db->query($selectString);
                $res = $query->result();
        
                if(!$res){
                    $ordernet_id=0;
                    /*$queryString="insert into ordernet(diary_no,perj,orderdate,pdfname,usercode,ent_dt,type,h_p,afr,display,roster_id,c_type,c_num,c_year,orderTextData)
                            values(".$data['diary_no'].",".$perj.",'".$data['order_date']."','".$data['pdf_name']."',".$data['usercode'].",now(),'".$data['type']."','P','".$isReportable."','Y',".$data['roster_id'].",".$data['case_type'].",'".$data['registration_number']."',".$data['registration_year'].",'".$data['orderTextData']."')";*/
                    $queryString="insert into ordernet(diary_no,perj,orderdate,pdfname,usercode,ent_dt,type,h_p,afr,display,roster_id,c_type,c_num,c_year,orderTextData,pdf_hash_value,pdf_hash_value_date_time)
                            values(?,?,?,?,?,now(),?,?,?,?,?,?,?,?,?,?,?)";
                    //echo "<br/>".$queryString;
                    $this->db->query($queryString,array($data['diary_no'],$perj,$data['order_date'],$data['pdf_name'],$data['usercode'],$data['type'],'P',$isReportable,'Y',$data['roster_id'],$data['case_type'],$data['registration_number'],$data['registration_year'],$data['orderTextData'],$myhash,$myhashWithDateTime));
                    $ordernet_id= $this->db->insert_id();
                    if($ordernet_id!=0){
                        $this->send_whatsapp($data['diary_no'],$data['order_date'],$data['type'],$data['pdf_name'],'N',$data['roster_id'],'A');
                        if($data['type']=='O'){
                            $updateString="update proceedings set upload_flag=1,uploaded_by=".$data['usercode'].",upload_date_time=now(),ordernet_id=".$ordernet_id." where
                             diary_no=".$data['diary_no']." and order_date='".$data['order_date']."' and roster_id=".$data['roster_id']."";
                            $this->db->query($updateString);
                        }
                        $result=1;
                    }
                }
                elseif (sizeof($res)>0){
                    //::TODO Write replace queries here;
                    $ordernetDetail = $res[0];
                    $ordernet_id=$ordernetDetail->id;
                    $queryString="update ordernet set perj=".$perj.",pdfname='".$data['pdf_name']."',usercode=".$data['usercode'].",
                                ent_dt=now(),type='".$data['type']."',afr='".$isReportable."',orderTextData='".$data['orderTextData']."',pdf_hash_value=".'"'.$myhash.'"'.",pdf_hash_value_date_time=".'"'.$myhashWithDateTime.'"'."
                                where id=".$ordernet_id."";
                    //where diary_no=".$data['diary_no']." and orderdate='".$data['order_date']."' and roster_id=".$data['roster_id']." and display='Y'";
                    //echo "<br/>".$queryString;
                    $this->db->query($queryString);
                    $this->send_whatsapp($data['diary_no'],$data['order_date'],$data['type'],$data['pdf_name'],'Y',$data['roster_id'],'B');
                    if($ordernet_id!=0){
                        if($data['type']=='O'){
                            $updateString="update proceedings set upload_flag=1,uploaded_by=".$data['usercode'].",upload_date_time=now(),ordernet_id=".$ordernet_id." where
                             diary_no=".$data['diary_no']." and order_date='".$data['order_date']."' and roster_id=".$data['roster_id']."";
                            $this->db->query($updateString);
                        }
                        $result=1;
                    }
                }
                return $result;
            }
        
            //For Paper Book Module
            function getCauseTitleForPaperBook($diaryNo){
                $sql="select sr_no_show,partyname,prfhname,addr1,addr2,state,city,pet_res,remark_del,remark_lrs,pflag from party
                      where diary_no=$diaryNo AND pflag !='T' ORDER BY pet_res,
                        CAST(SUBSTRING_INDEX(sr_no_show,'.',1) AS UNSIGNED) ,
                        CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(CONCAT(sr_no_show,'.0'),'.',2),'.',-1) AS UNSIGNED) ,
                        CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(CONCAT(sr_no_show,'.0.0'),'.',3),'.',-1) AS UNSIGNED),
                        CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(CONCAT(sr_no_show,'.0.0.0'),'.',4),'.',-1) AS UNSIGNED)";
                $query = $this->db->query($sql);
                return $query->result_array();
            }
            //END
            //FOR Replacing or uploading Judgment
            function getCaseType()
            {
                $sql = "SELECT casecode, skey, casename,short_description FROM casetype WHERE display = ? AND casecode!=? ORDER BY casecode";
                $query = $this->db->query($sql, array('Y', 9999));
                if ($query->num_rows() > 0) {
                    return $query->result_array();
                } else {
                    return false;
                }
            }
            function getSearchDiaryAllFields($caseTypeId = null, $caseNo = null, $caseYear = null, $diaryNo = null, $diaryYear = null, $searchType)
            {
                $parameters=[];
                if ($searchType == 'C') {
                    $sql = "SELECT distinct m.* FROM
                             main_casetype_history h
                            inner join main m on m.diary_no = h.diary_no
                            WHERE (SUBSTRING_INDEX(h.new_registration_number, '-', 1) = cast(? as UNSIGNED) AND
                            CAST(? AS UNSIGNED) BETWEEN (SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2),'-',-1))
                            AND (SUBSTRING_INDEX(h.new_registration_number, '-', -1))
                            AND h.new_registration_year=?) AND h.is_deleted=?";
                    array_push($parameters,$caseTypeId,$caseNo,$caseYear,'f');
                }
                else if($searchType == 'D') {
                    $sql = "Select m.* from main m where substr( diary_no, 1, length( diary_no ) -4 )=? and substr( diary_no , -4 )=?";
                    array_push($parameters,$diaryNo,$diaryYear);
                }
        
                $query = $this->db->query($sql,$parameters);
                //echo $this->db->last_query();
                return $query->row();
                //return $row->diary_no;
            }
            function getWithAllConnected($main_diary)
            {
                $this->db->simple_query('SET SESSION group_concat_max_len=10000000000');
                $sql = "select group_concat(diary_no) as conn_list from main where conn_key=?";
                $query = $this->db->query($sql, array($main_diary));
                //echo $this->db->last_query();
                $row = $query->row();
                return $row->conn_list;
            }
            function getSearchDiary($caseTypeId = null, $caseNo = null, $caseYear = null, $diaryNo = null, $diaryYear = null, $searchType)
            {
                $parameters=[];
                if ($searchType == 'C') {
                    $sql = "SELECT distinct h.diary_no FROM
                             main_casetype_history h
                            WHERE (SUBSTRING_INDEX(h.new_registration_number, '-', 1) = cast(? as UNSIGNED) AND
                            CAST(? AS UNSIGNED) BETWEEN (SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2),'-',-1))
                            AND (SUBSTRING_INDEX(h.new_registration_number, '-', -1))
                            AND h.new_registration_year=?) AND h.is_deleted=?";
                    array_push($parameters,$caseTypeId,$caseNo,$caseYear,'f');
                }
                else if($searchType == 'D') {
                    $sql = "Select diary_no from main where substr( diary_no, 1, length( diary_no ) -4 )=? and substr( diary_no , -4 )=?";
                    array_push($parameters,$diaryNo,$diaryYear);
                }
        
                $query = $this->db->query($sql,$parameters);
                //echo $this->db->last_query();
                $row = $query->row();
                return $row->diary_no;
            }
            function getCaseDetailsJudgementFlagChange($diaryNo){
                $queryString="";
                $rop_text_web = "rop_text_web";
                /*	$queryString = "SELECT * FROM
                (SELECT o.id, 'ordernet' as tbl_name, o.diary_no, SUBSTR(o.diary_no, 1, LENGTH(o.diary_no) - 4) AS d_no, SUBSTR(o.diary_no, - 4) AS d_year,
            pdfname file_address, date_format(o.orderdate,'%Y-%m-%d') order_date, o.type as order_type_short,
            case when o.type='O' then 'rop' when o.type='J' then 'judgment' when o.type='FO' then 'final order' END as order_type
                        FROM ordernet o
                        WHERE o.diary_no  in ($diaryNo)
                        union
                        SELECT
                        o.id, 'tempo' as tbl_name, o.diary_no, SUBSTR(o.diary_no, 1, LENGTH(o.diary_no) - 4) AS d_no, SUBSTR(o.diary_no, - 4) AS d_year,
            jm file_address, date_format(o.dated,'%Y-%m-%d') order_date,
            case when o.jt='rop' then 'O' when o.jt='judgment' then 'J' when o.jt='final order' then 'FO' END as order_type_short,
            jt as order_type
                        FROM tempo o
                        WHERE o.diary_no  in ($diaryNo) and jt != 'or'
                        union
                        SELECT
                         o.id_dn as id, 'scordermain' as tbl_name, o.dn as diary_no, SUBSTR(o.dn, 1, LENGTH(o.dn) - 4) AS d_no, SUBSTR(o.dn, - 4) AS d_year,
            concat('judis/',o.filename,'.pdf') file_address, date_format(o.juddate,'%Y-%m-%d') order_date,
            case when o.order_type='rop' then 'O' when o.order_type='judgment' then 'J' when o.order_type='final order' then 'FO' END as order_type_short,
            order_type as order_type
                        FROM scordermain o
                        WHERE o.dn  in ($diaryNo)
                        union
                        SELECT
                         o.pno as id, 'old_rop' as tbl_name, o.dn as diary_no, SUBSTR(o.dn, 1, LENGTH(o.dn) - 4) AS d_no, SUBSTR(o.dn, - 4) AS d_year,
            concat('ropor/rop/all/',o.pno,'.pdf') file_address, date_format(o.orderDate,'%Y-%m-%d') order_date,
            case when o.order_type='rop' then 'O' when o.order_type='judgment' then 'J' when o.order_type='final order' then 'FO' END as order_type_short,
            order_type as order_type
                        FROM $rop_text_web.old_rop o
                        WHERE o.dn  in ($diaryNo)
                        union
                        SELECT
                          o.pno as id, 'ordertext' as tbl_name, o.dn as diary_no, SUBSTR(o.dn, 1, LENGTH(o.dn) - 4) AS d_no, SUBSTR(o.dn, - 4) AS d_year,
            concat('bosir/orderpdf/',o.pno,'.pdf') file_address, date_format(o.orderDate,'%Y-%m-%d') order_date,
            case when o.order_type='rop' then 'O' when o.order_type='judgment' then 'J' when o.order_type='final order' then 'FO' END as order_type_short,
            order_type as order_type
                        FROM $rop_text_web.ordertext o
                        WHERE o.dn in ($diaryNo)
                        union
                        SELECT
                         o.pno as id, 'oldordtext' as tbl_name, o.dn as diary_no, SUBSTR(o.dn, 1, LENGTH(o.dn) - 4) AS d_no, SUBSTR(o.dn, - 4) AS d_year,
            concat('bosir/orderpdfold/',o.pno,'.pdf') file_address, date_format(o.orderdate,'%Y-%m-%d') order_date,
            case when o.order_type='rop' then 'O' when o.order_type='judgment' then 'J' when o.order_type='final order' then 'FO' END as order_type_short,
            order_type as order_type
                        FROM $rop_text_web.oldordtext o
                        WHERE o.dn in ($diaryNo)
                        ) tbl1 order by order_date desc
            ";*/
                /*
                 select 'ordernet' as table_name, hd.id, hd.type as order_type, hd.orderdate, hd.diary_no, pdfname
                        from ordernet hd where hd.type in ('O', 'J', 'FO') and hd.diary_no in (".$diaryNo.") and hd.display = 'Y'
                        order by orderdate desc */
                $queryString="SELECT * FROM  (SELECT o.id, 'ordernet' as tbl_name, o.diary_no, SUBSTR(o.diary_no, 1, LENGTH(o.diary_no) - 4) AS d_no, SUBSTR(o.diary_no, - 4) AS d_year,
        pdfname file_address, date_format(o.orderdate,'%Y-%m-%d') order_date, o.type as order_type_short,
        case when o.type='O' then 'rop' when o.type='J' then 'judgment' when o.type='FO' then 'final order' END as order_type,nc_display
                    FROM ordernet o left join neutral_citation n on o.diary_no=n.diary_no and n.dispose_order_date=date_format(o.orderdate,'%Y-%m-%d') and o.type='J'
                    WHERE o.diary_no  in ($diaryNo)
                    union
                    SELECT
                    o.id, 'tempo' as tbl_name, o.diary_no, SUBSTR(o.diary_no, 1, LENGTH(o.diary_no) - 4) AS d_no, SUBSTR(o.diary_no, - 4) AS d_year,
        jm file_address, date_format(o.dated,'%Y-%m-%d') order_date,
        case when o.jt='rop' then 'O' when o.jt='judgment' then 'J' when o.jt='final order' then 'FO' END as order_type_short,
        jt as order_type ,nc_display
                    FROM tempo o left join neutral_citation n on o.diary_no=n.diary_no and n.dispose_order_date=date_format(o.dated,'%Y-%m-%d') and o.jt='judgment'
                    WHERE o.diary_no  in ($diaryNo) and jt != 'or'
                    union
                    SELECT
                     o.id_dn as id, 'scordermain' as tbl_name, o.dn as diary_no, SUBSTR(o.dn, 1, LENGTH(o.dn) - 4) AS d_no, SUBSTR(o.dn, - 4) AS d_year,
        concat('judis/',o.filename,'.pdf') file_address, date_format(o.juddate,'%Y-%m-%d') order_date,
        case when o.order_type='rop' then 'O' when o.order_type='judgment' then 'J' when o.order_type='final order' then 'FO' END as order_type_short,
        o.order_type as order_type ,nc_display
                    FROM scordermain o left join neutral_citation n on o.dn=n.diary_no and n.dispose_order_date=date_format(o.juddate,'%Y-%m-%d') and o.order_type='judgment'
                    WHERE o.dn  in ($diaryNo)
                    union
                    SELECT
                     o.pno as id, 'old_rop' as tbl_name, o.dn as diary_no, SUBSTR(o.dn, 1, LENGTH(o.dn) - 4) AS d_no, SUBSTR(o.dn, - 4) AS d_year,
        concat('ropor/rop/all/',o.pno,'.pdf') file_address, date_format(o.orderDate,'%Y-%m-%d') order_date,
        case when o.order_type='rop' then 'O' when o.order_type='judgment' then 'J' when o.order_type='final order' then 'FO' END as order_type_short,
        o.order_type as order_type     ,nc_display
                    FROM $rop_text_web.old_rop o left join neutral_citation n on o.dn=n.diary_no and n.dispose_order_date=date_format(o.orderDate,'%Y-%m-%d') and o.order_type='judgment'
                    WHERE o.dn  in ($diaryNo)
                    union
                    SELECT
                      o.pno as id, 'ordertext' as tbl_name, o.dn as diary_no, SUBSTR(o.dn, 1, LENGTH(o.dn) - 4) AS d_no, SUBSTR(o.dn, - 4) AS d_year,
        concat('bosir/orderpdf/',o.pno,'.pdf') file_address, date_format(o.orderDate,'%Y-%m-%d') order_date,
        case when o.order_type='rop' then 'O' when o.order_type='judgment' then 'J' when o.order_type='final order' then 'FO' END as order_type_short,
        o.order_type as order_type    ,nc_display
                    FROM $rop_text_web.ordertext o left join neutral_citation n on o.dn=n.diary_no and n.dispose_order_date=date_format(o.orderDate,'%Y-%m-%d') and o.order_type='judgment'
                    WHERE o.dn in ($diaryNo)
                    union
                    SELECT
                     o.pno as id, 'oldordtext' as tbl_name, o.dn as diary_no, SUBSTR(o.dn, 1, LENGTH(o.dn) - 4) AS d_no, SUBSTR(o.dn, - 4) AS d_year,
        concat('bosir/orderpdfold/',o.pno,'.pdf') file_address, date_format(o.orderdate,'%Y-%m-%d') order_date,
        case when o.order_type='rop' then 'O' when o.order_type='judgment' then 'J' when o.order_type='final order' then 'FO' END as order_type_short,
        o.order_type as order_type  ,nc_display
                    FROM $rop_text_web.oldordtext o left join neutral_citation n on o.dn=n.diary_no and n.dispose_order_date=date_format(o.orderdate,'%Y-%m-%d') and o.order_type='judgment'
                    WHERE o.dn in ($diaryNo)
                    ) tbl1 order by order_date desc;";
                $query = $this->db->query($queryString);
                return $query->result_array();
            }
            function getCaseDetailsForReplace($diaryNo){
                $queryString="";
                $queryString = "select hd.diary_no,m.active_fil_no as registration_number,m.active_reg_year as registration_year, m.active_casetype_id as casetype_id,m.pet_name,m.res_name,hd.brd_slno as item_number,m.reg_no_display,hd.next_dt,r.courtno,hd.roster_id
                        from heardt hd inner join main m on hd.diary_no=m.diary_no inner join roster r on hd.roster_id=r.id
                        where hd.diary_no=".$diaryNo." and brd_slno!=0 and clno!=0
                        union
                        select  hd.diary_no,m.active_fil_no as registration_number,m.active_reg_year as registration_year,m.active_casetype_id as casetype_id,m.pet_name,m.res_name,hd.brd_slno as item_number,m.reg_no_display, hd.next_dt,r.courtno,hd.roster_id
                        from last_heardt hd inner join main m on hd.diary_no=m.diary_no inner join roster r on hd.roster_id=r.id
                        where hd.bench_flag='' and hd.diary_no=".$diaryNo." and brd_slno!=0 and clno!=0
                        union
                        select hd.diary_no,m.active_fil_no as registration_number,m.active_reg_year as registration_year,m.active_casetype_id as casetype_id,m.pet_name,m.res_name,hd.m_brd_slno as item_number,m.reg_no_display,hd.date_on_decided as next_dt,r.courtno,hd.m_roster_id as roster_id
                        from mention_memo hd inner join main m on hd.diary_no=m.diary_no inner join roster r on hd.m_roster_id=r.id
                        where hd.display='Y' and hd.diary_no=".$diaryNo." and m_brd_slno!=0 order by next_dt desc";
                //echo $queryString;
                $query = $this->db->query($queryString);
                return $query->result_array();
            }
            function getLanguage($usercode){
                $userdetail=$this->getUserDetail($usercode);
                $usersection=$userdetail[0]['section'];
                if($usersection == 79){//:TODO Replace 79 with transaltion cell usersection id
                    $this->db->where('vernacular_languages.id != ',1,FALSE);
                }
                else{
                    $this->db->where('id',1);
                }
                $this->db->order_by('id', 'ASC');
                $this->db->where('display','Y');
                $query = $this->db->get('vernacular_languages');
                return $query->result_array();
            }
            function getVernacularJudgmentDetail($diaryNumber,$orderDate,$orderType,$languageId){
                $this->db->where('display','Y');
                $this->db->where('diary_no',$diaryNumber);
                $this->db->where('order_date',$orderDate);
                $this->db->where('order_type',$orderType);
                $this->db->where('ref_vernacular_languages_id',$languageId);
                $query = $this->db->get('vernacular_orders_judgments');
                //echo $this->db->last_query();
                //exit(0);
                return $query->result();
            }
            function insertVernacularOrdersJudgments($data){
                $result=0;
                $res=$this->getVernacularJudgmentDetail($data['diary_no'],$data['order_date'],$data['order_type'],$data['ref_vernacular_languages_id']);
                if(!$res){
                    $this->db->insert('vernacular_orders_judgments', $data);
                    $result=1;
                }
                elseif (sizeof($res)>0){
                    $orderDetail = $res[0];
                    $id=$orderDetail->id;
                    $this->db->where('id', $id)->update('vernacular_orders_judgments', $data);
                    $result=1;
                }
                return $result;
            }
        
            function getUploadedVernacularJudgmentsList($fromDate=null,$toDate=null)
            {
                $condition = "";
        
                $sql="SELECT distinct o.diary_no,
                       group_concat(distinct trim(s.Name))as stateName,
                       group_concat(distinct rac.agency_name)  as highCourt,
                       concat(b.reg_no_display,' @ ',concat(SUBSTR(b.diary_no, 1, LENGTH(b.diary_no) - 4),'/',SUBSTR(b.diary_no, - 4))) as caseNo,
                       concat(b.pet_name,' Vs. ',b.res_name) as causeTitle,
                       DATE_FORMAT(o.order_date, '%d-%m-%Y') as judgmentDate,
                       e.name as uploadedBy,
                       DATE_FORMAT(o.entry_date, '%d-%m-%Y') as uploadedOn,
                       o.pdf_name as filePath,
                       vl.name as language
                       FROM vernacular_orders_judgments o
                       inner join vernacular_languages vl on o.ref_vernacular_languages_id=vl.id and vl.display='Y'
                       left join main b ON o.diary_no = b.diary_no
                       left join lowerct lct on (o.diary_no=lct.diary_no and is_order_challenged='Y')
                       left join ref_agency_code rac on (lct.l_dist=rac.id and rac.is_deleted='f')
                       left join state s on (lct.l_state=s.id_no and s.display='Y')
                       left join users e ON e.usercode = o.user_code
                       left join usertype u1 on e.usertype=u1.id
                       WHERE  date(o.entry_date) between '".$fromDate."' and '".$toDate."'
                       and o.display='Y'
                       GROUP BY o.diary_no,vl.name
                       order by stateName asc,highCourt asc,o.diary_no,uploadedOn desc";
        
        
                $query = $this->db->query($sql);
                if ($query->num_rows() >= 1) {
                    //echo $sql;
                    return $query->result_array();
                }
                else
                    return false;
            }
        
        
            //Judges_roster work started
            function saveJudgesOnLeave($data){
                $result=false;
                $res=$this->getJudgeLeaveDetail($data['next_dt'],'',$data['jcode']);
                //var_dump($res);
                if(sizeof($res)<=0){
                    $this->db->insert('sitting_plan_judges_leave_details', $data);
                    $result=true;
                }
                elseif (sizeof($res)>0){
                    $id = $res[0]['id'];
                    $this->db->where('id', $id)->update('sitting_plan_judges_leave_details', $data);
                    $result=true;
                }
                return $result;
            }
            function getJudgeLeaveDetail($causeListDate,$isOnLeave='',$jcode=0){
                $this->db->order_by('judge.judge_seniority', 'ASC');
                $this->db->where('sitting_plan_judges_leave_details.display','Y');
                if($isOnLeave!=''){
                    $this->db->where('sitting_plan_judges_leave_details.is_on_leave',$isOnLeave);
                }
                $this->db->where('sitting_plan_judges_leave_details.next_dt',$causeListDate);
                if($jcode!=0){
                    $this->db->where('sitting_plan_judges_leave_details.jcode',$jcode);
                }
                $this->db->from('sitting_plan_judges_leave_details');
                $this->db->join('judge', 'sitting_plan_judges_leave_details.jcode = judge.jcode');
                $this->db->select('judge.*,sitting_plan_judges_leave_details.id');
                $query = $this->db->get();
                //echo $this->db->last_query();
                return $query->result_array();
            }
            function saveSittingDeatils($dataForSittingPlan){
                $this->db->insert('sitting_plan_details', $dataForSittingPlan);
                return $this->db->insert_id();
            }
            function saveSittingPlan($data,$judges){
                $this->db->insert('sitting_plan_court_details', $data);
                $sitting_plan_court_details_id=$this->db->insert_id();
                foreach($judges as $judge){
                    $dataForInsert=array('sitting_plan_court_details_id'=>$sitting_plan_court_details_id,'jcode'=>$judge,'updated_on'=>date('Y-m-d H:i:s'),'usercode'=>$data['usercode'],'display'=>'Y');
                    $this->db->insert('sitting_plan_judges_details', $dataForInsert);
                }
            }
            function deleteSittingPlan($sitting_plan_details_id,$if_finalized,$usercode=1){
                if($if_finalized==1){
                    $updateQuery="UPDATE sitting_plan_details spd
                    INNER JOIN sitting_plan_court_details spcd ON spd.id = spcd.sitting_plan_details_id
                    LEFT JOIN sitting_plan_judges_details spjd ON spcd.id = spjd.sitting_plan_court_details_id
                    SET spd.no_of_times_modified_after_finalization = spd.no_of_times_modified_after_finalization+1,
                        spd.usercode=$usercode,
                        spd.updated_on=now(),
                        spcd.display = 'N',
                        spcd.usercode=$usercode,
                        spcd.updated_on=now(),
                        spjd.display = 'N',
                        spjd.usercode=$usercode,
                        spjd.updated_on=now()
                    WHERE spd.id=? and spcd.display=? and (spjd.display=? or spjd.display is null);";
                }
                else{
                    $updateQuery="UPDATE sitting_plan_details spd
                    INNER JOIN sitting_plan_court_details spcd ON spd.id = spcd.sitting_plan_details_id
                    LEFT JOIN sitting_plan_judges_details spjd ON spcd.id = spjd.sitting_plan_court_details_id
                    SET spd.no_of_times_modified_before_finalization = spd.no_of_times_modified_before_finalization+1,
                        spd.usercode=$usercode,
                        spd.updated_on=now(),
                        spcd.display = 'N',
                        spcd.usercode=$usercode,
                        spcd.updated_on=now(),
                        spjd.display = 'N',
                        spjd.usercode=$usercode,
                        spjd.updated_on=now()
                    WHERE spd.id=? and spcd.display=? and (spjd.display=? or spjd.display is null);";
                }
                $this->db->query($updateQuery,array($sitting_plan_details_id,'Y','Y'));
                //echo $this->db->last_query();
        
            }
            function getSittingPlan($causelistDate,$forPrint=0){
                $sql="select spcd.id as sitting_plan_court_details_id,spd.if_finalized,spd.id as sitting_plan_details_id,spd.next_dt,spcd.court_number,spcd.board_type,spcd.if_special_bench,spcd.header_remark,spcd.footer_remark,spcd.mainhead,spcd.if_in_printed,spcd.bench_start_time,group_concat(spjd.jcode order by j.judge_seniority) as judges
                    from sitting_plan_details spd inner join sitting_plan_court_details spcd on spd.id=spcd.sitting_plan_details_id inner join sitting_plan_judges_details spjd on spcd.id=spjd.sitting_plan_court_details_id
                    inner join judge j on spjd.jcode=j.jcode where spd.next_dt=? and spd.display=? and spcd.display=? and spjd.display=?
                    group by spd.next_dt,spcd.court_number,spcd.board_type,spcd.if_special_bench,spcd.header_remark,spcd.footer_remark,spcd.mainhead
                    order by court_number asc,board_type desc,if_special_bench asc";
                if($forPrint!=0){
                    $sql="select spcd.id as sitting_plan_court_details_id,spd.if_finalized,spd.id as sitting_plan_details_id,spd.next_dt,spcd.court_number,spcd.board_type,spcd.if_special_bench,spcd.header_remark,spcd.footer_remark,spcd.mainhead,spcd.if_in_printed,spcd.bench_start_time,group_concat(spjd.jcode order by j.judge_seniority) as judges
                    from sitting_plan_details spd inner join sitting_plan_court_details spcd on spd.id=spcd.sitting_plan_details_id inner join sitting_plan_judges_details spjd on spcd.id=spjd.sitting_plan_court_details_id
                    inner join judge j on spjd.jcode=j.jcode where spd.next_dt=? and spd.display=? and spcd.display=? and spjd.display=?
                    group by spd.next_dt,spcd.court_number,spcd.board_type,spcd.if_special_bench,spcd.header_remark,spcd.footer_remark,spcd.mainhead
                    order by court_number asc,board_type desc,case when bench_start_time!='00:00:00' then bench_start_time else '23:59:00' end asc,if_special_bench desc";
                }
        
                //order by court_number asc,board_type desc,case when bench_start_time!='00:00:00' then bench_start_time else '23:59:00' end asc,if_special_bench desc";
                $query = $this->db->query($sql,array($causelistDate,'Y','Y','Y'));
                return $query->result_array();
            }
            function getWorkingDayData($causelistDate){
                $this->db->where('display','Y');
                $this->db->where('working_date',$causelistDate);
                $this->db->from('sc_working_days');
                $this->db->select('working_date,is_nmd,is_holiday,holiday_description');
                $query = $this->db->get();
                return $query->result_array();
            }
            function finalizeSittingPlan($causelistDate){
                $result=false;
                $data=array('if_finalized'=>1);
                $this->db->where('next_dt', $causelistDate)->where('display','Y')->update('sitting_plan_details', $data);
                $result=true;
                return $result;
            }
            function generateRoster($causelistDate,$mainhead){
                $status=0;
                $this->db->trans_begin();
                $mf=1;
                if($mainhead=='F'){
                    $mf=2;
                }
                $sittingPlan=$this->getSittingPlan($causelistDate);
                $benchDetails=$this->getBenchDetail();
                $sitting_plan_details_id=0;
                //var_dump($sittingPlan);
                foreach ($sittingPlan as $plan){
                    $toDate=$plan['next_dt'];
                    if($mf==2){
                        $toDate='0000-00-00';
                    }
                    $rosterId=$this->getLastRosterId();
                    $judges=explode(',',$plan['judges']);
                    //Start get bench id
                    $coramSize=sizeof($judges);
                    $benchId=0;
                    foreach($benchDetails as $index=>$benchDetail){
                        if($benchDetail['abbr']==$coramSize && $benchDetail['board_type_mb']==$plan['board_type']){
                            $benchId=$benchDetail['id'];
                            //echo "<br/>Matched Coram size ".$coramSize." and board type is: ".$plan['board_type']." Id is: ".$benchDetail['id'];
                            unset($benchDetails[$index]);
                            break;
                        }
                    }
                    if($benchId==0){
                        $status=1;
                        //echo "<br/>Un-Matched Coram size ".$coramSize." and board type is: ".$plan['board_type'];
                    }
                    //END
                    $rosterId+=1;
                    $dataForRoster=array(
                        'id'=>$rosterId,
                        'bench_id'=>$benchId,
                        'from_date'=>$plan['next_dt'],
                        'to_date'=>$toDate,
                        'entry_dt'=>date('Y-m-d H:i:s'),
                        'display'=>'Y',
                        'courtno'=>$plan['court_number'],
                        'm_f'=>$mf,
                        'frm_time'=>$plan['bench_start_time'],
                        'session'=>'Whole Day',
                        'if_print_in'=>$plan['if_in_printed']);
        
                    $this->db->insert('roster', $dataForRoster);
                    //echo $this->db->last_query().'<br/>';
                    //$rosterId=$this->db->insert_id();
        
                    foreach($judges as $judge){
                        $dataForRosterJudge=array(
                            'roster_id'=>$rosterId,
                            'judge_id'=>$judge,
                            'display'=>'Y'
                        );
                        $this->db->insert('roster_judge', $dataForRosterJudge);
                    }
                    //Insert in Category_Allotment table
                    $dataForCategoryAllotment= array('stage_code'=>0,
                        'stage_nature'=>'C','ros_id'=>$rosterId,
                        'priority'=>1,'display'=>'Y',
                        'case_type'=>0,'submaster_id'=>0);
                    $this->db->insert('category_allottment', $dataForCategoryAllotment);
                    //END
                    //Update roster id misc./regular in sitting_plan_court_details
                    $sitting_plan_details_id=$plan['sitting_plan_details_id'];
                    $sitting_plan_court_details_id=$plan['sitting_plan_court_details_id'];
                    $this->db->where('id',$sitting_plan_court_details_id);
                    if($mainhead=='F'){
                        $this->db->update('sitting_plan_court_details', array('roster_id_regular'=>$rosterId));
                    }else{
                        $this->db->update('sitting_plan_court_details', array('roster_id_misc'=>$rosterId));
                    }
                    //END
                }
                //update if_roster_generated_misc misc./regular in sitting_plan_details table
                $this->db->where('id',$sitting_plan_details_id);
                if($mainhead=='F'){
                    $this->db->update('sitting_plan_details', array('if_roster_generated_regular'=>1));
                }else{
                    $this->db->update('sitting_plan_details', array('if_roster_generated_misc'=>1));
                }
                //END
                //$this->db->trans_complete(); # Completing transaction
        
                if ($this->db->trans_status() === FALSE) {
                    # Something went wrong.
                    $this->db->trans_rollback();
                    return FALSE;
                }
                else if($status==1){
                    $this->db->trans_rollback();
                    return FALSE;
                }
                else {
                    # Everything is Perfect.
                    # Committing data to the database.
                    $this->db->trans_commit();
                    return TRUE;
                }
            }
            function getLastRosterId(){
                $this->db->select_max('id');
                $this->db->from('roster');
                $query = $this->db->get();
                return $query->result()[0]->id;
            }
            function getBenchDetail(){
                $sql="select rb.id,rb.bench_id,rb.bench_no,rb.priority,mb.abbr,mb.board_type_mb
              from roster_bench rb inner join master_bench mb on mb.id=rb.bench_id
              where rb.display=? and mb.display=?
              order by bench_id,bench_no";
                $query = $this->db->query($sql,array('Y','Y'));
                return $query->result_array();
            }
            function doCopySittingPlan($fromDate,$toDate){
                $this->db->trans_begin();
                $usercode=1;//TODO:: Set it from session.
                //Copy sitting_plan_judges_leave_details;
                $insertQuery="insert into sitting_plan_judges_leave_details(next_dt,jcode,is_on_leave,usercode,updated_on,display)
                      select ?,jcode,is_on_leave,?,now(),display
                      from sitting_plan_judges_leave_details where next_dt=? and display=?";
                $this->db->query($insertQuery,array($toDate,$usercode,$fromDate,'Y'));
                //copy sitting_plan_details;
                $dataForSittingPlan=array(
                    'next_dt'=>$toDate,
                    'display'=>'Y',
                    'usercode'=>$usercode,
                    'updated_on'=>date('Y-m-d H:i:s'));
                $this->db->insert('sitting_plan_details', $dataForSittingPlan);
                $sittingPlanDetailId=$this->db->insert_id();
                //copy sitting_plan_court_details;
                $sittingPlan=$this->getSittingPlan($fromDate);
                //var_dump($sittingPlan);
                foreach ($sittingPlan as $plan){
                    $dataCourtDetails=array('sitting_plan_details_id'=>$sittingPlanDetailId,'court_number'=>$plan['court_number'],'board_type'=>$plan['board_type'],'if_special_bench'=>$plan['if_special_bench'],'header_remark'=>$plan['header_remark'],'footer_remark'=>$plan['footer_remark'],'usercode'=>$usercode,'updated_on'=>date('Y-m-d H:i:s'),'display'=>'Y','if_in_printed'=>$plan['if_in_printed'],'bench_start_time'=>$plan['bench_start_time']);
                    $this->db->insert('sitting_plan_court_details', $dataCourtDetails);
                    $sittingPlanCourtDetailId=$this->db->insert_id();
                    $judges=explode(',',$plan['judges']);
                    foreach($judges as $judge){
                        $dataForSittingPlanJudgesDetails=array(
                            'sitting_plan_court_details_id'=>$sittingPlanCourtDetailId,
                            'jcode'=>$judge,
                            'updated_on'=>date('Y-m-d H:i:s'),
                            'usercode'=>$usercode,
                            'display'=>'Y'
                        );
                        $this->db->insert('sitting_plan_judges_details', $dataForSittingPlanJudgesDetails);
                    }
                }
                if ($this->db->trans_status() === FALSE) {
                    # Something went wrong.
                    $this->db->trans_rollback();
                    return FALSE;
                }
                else {
                    # Everything is Perfect.
                    # Committing data to the database.
                    $this->db->trans_commit();
                    return TRUE;
                }
            }
        
            function ropNotUploaded($causeListFromDate, $causeListToDate, $pJudge)
            {
                $causeListFromDate = $this->parseDate($causeListFromDate);
                $causeListToDate = $this->parseDate($causeListToDate);
                $sql = "select DISTINCT listed.* from
                (SELECT DISTINCT
                rj.roster_id,
                m.diary_no,
                hd.board_type,
                hd.next_dt AS listing_date,
                m.pet_name AS petitioner_name,
                m.res_name AS respondent_name,
                r.courtno AS court_number,
                hd.brd_slno AS item_number,
                CASE
                    WHEN hd.listed_ia = '' THEN m.reg_no_display
                    ELSE CONCAT('IA ',
                            hd.listed_ia,
                            ' in ',
                            m.reg_no_display)
                END AS registration_number_desc,
                m.pno,
                m.rno
        
            FROM
                heardt hd
                    INNER JOIN
                main m ON hd.diary_no = m.diary_no
                    INNER JOIN
                roster_judge rj ON hd.roster_id = rj.roster_id
                    INNER JOIN
                roster r ON rj.roster_id = r.id
                    INNER JOIN
                cl_printed cp ON hd.roster_id = cp.roster_id
                    AND hd.next_dt = cp.next_dt
                    AND hd.brd_slno BETWEEN cp.from_brd_no AND cp.to_brd_no
                    AND hd.clno = cp.part
                    left join case_remarks_multiple crm on hd.diary_no=crm.diary_no and hd.next_dt=crm.cl_date and r_head!=19
            WHERE
                cp.display = 'Y'
                    AND hd.main_supp_flag != 0
                    AND (hd.conn_key IS NULL OR hd.conn_key = 0
                    OR hd.conn_key = hd.diary_no)
                    AND hd.brd_slno IS NOT NULL
                    AND hd.brd_slno > 0
                    AND hd.next_dt between ? and ?
                    AND rj.judge_id = ?
            UNION
            SELECT DISTINCT
                rj.roster_id,
                m.diary_no,
                hd.board_type,
                hd.next_dt AS listing_date,
                m.pet_name AS petitioner_name,
                m.res_name AS respondent_name,
                r.courtno AS court_number,
                hd.brd_slno AS item_number,
                CASE
                    WHEN hd.listed_ia = '' THEN m.reg_no_display
                    ELSE CONCAT('IA ',
                            hd.listed_ia,
                            ' in ',
                            m.reg_no_display)
                END AS registration_number_desc,
                m.pno,
                m.rno
            FROM
                last_heardt hd
                    INNER JOIN
                main m ON hd.diary_no = m.diary_no
                    INNER JOIN
                roster_judge rj ON hd.roster_id = rj.roster_id
                    INNER JOIN
                roster r ON rj.roster_id = r.id
                    INNER JOIN
                cl_printed cp ON hd.roster_id = cp.roster_id
                    AND hd.next_dt = cp.next_dt
                    AND hd.brd_slno BETWEEN cp.from_brd_no AND cp.to_brd_no
                    AND hd.clno = cp.part
                    left join case_remarks_multiple crm on hd.diary_no=crm.diary_no and hd.next_dt=crm.cl_date and r_head!=19
            WHERE
                cp.display = 'Y'
                    AND hd.main_supp_flag != 0
                    AND (hd.conn_key IS NULL OR hd.conn_key = 0
                    OR hd.conn_key = hd.diary_no)
                    AND hd.brd_slno IS NOT NULL
                    AND hd.brd_slno > 0
                    AND hd.bench_flag = ''
                   AND hd.next_dt between ? and ?
                    AND rj.judge_id = ?
                    ) listed
                    left join ordernet o on listed.diary_no=o.diary_no and listed.listing_date=o.orderdate and o.display='Y' and o.type='O'
                    where o.diary_no is null order by listing_date,board_type,court_number,item_number";
                $query = $this->db->query($sql,array($causeListFromDate, $causeListToDate, $pJudge,$causeListFromDate, $causeListToDate, $pJudge));
                //echo $this->db->last_query();
                return $query->result_array();
            }
        
            function getUsercode($order_date,$roster_id,$diary_no,$court_no,$item_no)
            {
                $sql_generated_by="select generated_by from proceedings where roster_id=$roster_id and order_date='".$order_date."' and display='Y' and court_number=$court_no and item_number=$item_no and diary_no=$diary_no";
                $query_generated_by=$this->db->query($sql_generated_by);
                $result_generated_by= $query_generated_by->result_array();
                if($result_generated_by) {
                    $generated_by = $result_generated_by[0]['generated_by'];
                    // $generated_by = explode(',', $generated_by);
                    $generated_by =trim($generated_by ,",");
                    $sql = "select group_concat(name) as user_name from users where usercode in($generated_by)";
                    //exit(0);
                    $query = $this->db->query($sql);
                    return $query->result_array();
                }
                else
                    return false;
            }
        
            function ifRostergenerated($causeListDate,$mainhead){
                $sql="select case when ?='M' then if_roster_generated_misc else if_roster_generated_regular end as if_generated
                    from sitting_plan_details where next_dt=? and display=?";
                $query = $this->db->query($sql,array($mainhead,$causeListDate,'Y'));
                $res= $query->result();
                return $res[0]->if_generated;
            }
            //END
        
            function getCasesDetails($diaryNumber=null)
            {
                //echo 'ForDiary'.$diaryNumber;
                $sql="SELECT s.section_name as user_section,s.id,b.diary_no,
                                    date_format(b.diary_no_rec_date, '%Y-%m-%d') as diary_date,b.c_status,tentative_cl_dt,
                                    next_dt, mainhead, subhead, brd_slno, a.usercode, ent_dt, pet_name, res_name,
                                    active_fil_no, b.reg_no_display,dacode, a.conn_key, stagename, main_supp_flag, u.name alloted_to_da,
                                    descrip, u1.name updated_by, listorder,
                                    br1.name as pet_adv_name,br2.name as res_adv_name,
                                    br1.aor_code as pet_aor_code,br2.aor_code as res_aor_code,
                                    sb.sub_name1, sb.sub_name4,sb.category_sc_old,active_reg_year
                                    FROM main b
                                    left outer JOIN heardt a ON a.diary_no = b.diary_no
                                    LEFT outer  JOIN subheading c ON a.subhead = c.stagecode
                                    AND c.display = 'Y'
                                    LEFT outer JOIN users u ON u.usercode = b.dacode
                                    AND u.display = 'Y'
                                    LEFT outer JOIN users u1 ON u1.usercode = a.usercode
                                    AND u1.display = 'Y'
                                    LEFT outer JOIN master_main_supp mms ON mms.id = a.main_supp_flag
                                    left outer JOIN listing_purpose lp ON lp.code = a.listorder AND lp.display = 'Y'
                                    left outer join usersection s on s.id=u.section and s.display='Y'
                                    left outer join bar br1 on b.pet_adv_id=br1.bar_id
                                    left outer join bar br2 on b.res_adv_id=br2.bar_id
                                    left outer join mul_category mc on a.diary_no=mc.diary_no and mc.display='Y'
                                    left outer join submaster sb on mc.submaster_id=sb.id
                                    WHERE b.diary_no=$diaryNumber";
        
                $query = $this->db->query($sql);
                if($query -> num_rows() >= 1)
                {
                    return $query->result_array();
                }
                else
                {
                    return false;
                }
        
            }
            function getUploadedJudgment($diaryNumber=null,$orderDate=null,$orderType=null){
                $causelistDate = $this->parseDate($orderDate);
                $sql="select * from ordernet where diary_no=? and orderdate=? and type=? and display='Y'";
                $query = $this->db->query($sql,array($diaryNumber,$causelistDate,$orderType));
                //echo $this->db->last_query();
                return $query->result_array();
            }
            function insertJudgmentsInOrderNet($data,$myhash,$myhashWithDateTime)
            {
                $isReportable='N';
                if($data['is_reportable']==1){
                    $isReportable='Y';
                }
                $perj=0;
                if(isset($data['presiding_judge'])){
                    $perj=$data['presiding_judge'];
                }
                $result=0;
                $ordernet_id=0;
        
                $queryString="insert into ordernet(diary_no,perj,orderdate,pdfname,usercode,ent_dt,type,h_p,afr,display,roster_id,c_type,c_num,c_year,orderTextData,pdf_hash_value,pdf_hash_value_date_time)
                            values(?,?,?,?,?,now(),?,?,?,?,?,?,?,?,?,?,?)";
                //echo "<br/>".$queryString;
                $this->db->query($queryString,array($data['diary_no'],$perj,$data['order_date'],$data['pdf_name'],$data['usercode'],$data['type'],'P',$isReportable,'Y',$data['roster_id'],$data['case_type'],$data['registration_number'],$data['registration_year'],$data['orderTextData'],$myhash,$myhashWithDateTime));
                /*$ordernet_id= $this->db->insert_id();
                if($ordernet_id!=0){
                    if($data['type']=='O'){
                        $updateString="update proceedings set upload_flag=1,uploaded_by=".$data['usercode'].",upload_date_time=now(),ordernet_id=".$ordernet_id." where
                         diary_no=".$data['diary_no']." and order_date='".$data['order_date']."' and roster_id=".$data['roster_id']."";
                        $this->db->query($updateString);
                    }*/
                $ordernet_id= $this->db->insert_id();
                if($ordernet_id!=0){
                    $result=1;
                    $this->send_whatsapp($data['diary_no'],$data['order_date'],$data['type'],$data['pdf_name'],'N',$data['roster_id'],'C');
                    //$this->send_whatsapp($data['diary_no']);
                }
                //	}
        
        
                return $result;
            }
        
            function getNCDetails($diary_no,$order_date)
            {
                $sql="select * from neutral_citation where diary_no=$diary_no and dispose_order_date='$order_date'";
                $query = $this->db->query($sql);
                //echo $this->db->last_query();
                return $query->result_array();
            }
        
            function getReport($getDate)
            {
                $date = date('Y-m-d',strtotime($getDate));
                $nextDate = date('Y-m-d', strtotime("+1 day", strtotime($getDate)));
        
                $this->db->select('p.order_date,p.diary_no,p.court_number,p.item_number,o.pdfname,m.reg_no_display,u.name');
                $this->db->from('proceedings as p');
                $this->db->join('ordernet as o','o.diary_no = p.diary_no and o.orderdate = p.order_date');
                $this->db->join('main as m','m.diary_no = p.diary_no');
                $this->db->join('users as u','p.uploaded_by = u.usercode');
                $this->db->where('p.upload_date_time between "'.$date.'" and "'.$nextDate.'" ');
                $this->db->where('upload_flag','1');
                $this->db->order_by('p.order_date,p.court_number,p.item_number');
                $qur = $this->db->get();
                //echo $this->db->last_query();
        
                if($qur->num_rows() > 0){
                    return $qur->result_array();
                }else{
                    return [];
                }
        
            }
        
            function advocate_mobile($diary_no)
            {
                $wh_mobileno='';
                $sql_advocate_mob = "Select distinct mobile from advocate a join bar b on a.advocate_id=b.bar_id
                    where diary_no in($diary_no) and display='Y'";
                $query_advocate_mob = $this->db->query($sql_advocate_mob);
                if($query_advocate_mob -> num_rows() >= 1) {
                    $row_advocate_mob = $query_advocate_mob->result_array();
                    foreach($row_advocate_mob as $row) {
                        if ($row['mobile'] != '' && strlen($row['mobile']) == '10') {
                            $wh_mobileno.="91".$row['mobile'].',';
                        }
                    }
        
                }
                $wh_mobileno=rtrim($wh_mobileno,',');
                // $wh_mobileno.=",919871754198,919540028941,919871922703,918763332660,919881397172";
        
                if(!empty($wh_mobileno))
                    return $wh_mobileno;
                else
                    return false;
            }
        
            function getListedConnectedMatters($diary_no,$orderdate,$roster_id)
            {
                $sql_connected="select  group_concat(diary_no) as connected_matters from
                      (select m.diary_no from heardt hd inner join main m on hd.diary_no=m.diary_no
                      left outer join conct ct on m.diary_no=ct.diary_no
                      where ct.list='Y' and hd.next_dt='$orderdate' and hd.conn_key='$diary_no' and
                      hd.conn_key<>hd.diary_no and hd.roster_id='$roster_id'
                      union
                      select m.diary_no from last_heardt hd inner join main m on hd.diary_no=m.diary_no
                      left outer join conct ct on m.diary_no=ct.diary_no
                      where hd.next_dt='$orderdate' and hd.bench_flag='' and hd.conn_key='$diary_no' and
                      hd.conn_key<>hd.diary_no and hd.roster_id='$roster_id'
                      union
                      select m.diary_no from mention_memo hd inner join main m on hd.diary_no=m.diary_no
                      left outer join conct ct on m.diary_no=ct.diary_no
                      where hd.display='Y' and hd.date_on_decided='$orderdate' and hd.m_conn_key='$diary_no' and
                      hd.m_conn_key<>hd.diary_no and hd.m_roster_id='$roster_id') aa";
                $query=$this->db->query($sql_connected);
                $result=$query->result_array();
                $connected_matters=$result[0]['connected_matters'];
                if(!empty($connected_matters))
                    return $connected_matters;
                else
                    return false;
            }
            function send_whatsapp($diary_no,$orderdate,$type,$pdf_name,$revised_status,$roster_id,$position)
            {
        
                $pdf=explode('/',$pdf_name);
                //$file_name=$pdf[3];
                $diary_year=substr($diary_no,-4);
                $diary_number=substr($diary_no,0,-4);
        
                $sql_main="select pet_name,res_name from main where diary_no=$diary_no";
                $query_main=$this->db->query($sql_main);
                $result_main=$query_main->result_array();
                $pet=$result_main[0]['pet_name'];
                $res=$result_main[0]['res_name'];
                $connected_matters=$this->getListedConnectedMatters($diary_no,$orderdate,$roster_id);
                if($connected_matters!=false)
                    $all_matters=$diary_no.','.$connected_matters;
                else
                    $all_matters=$diary_no;
                $wh_mobileno='';
                $wh_mobileno=$this->advocate_mobile($all_matters);
        //echo "connected matters"; var_dump($connected_matters);
        //echo "mobile"; var_dump($wh_mobileno); exit();
                if($type=='O')
                    $type_msg='Order';
                else if($type=='J')
                    $type_msg='Judgment';
                $date_msg=date('d-m-Y',strtotime($orderdate));
                $file_name=$type_msg.'-'.$diary_number.'_'.$diary_year.'-'.$date_msg.'.pdf';
        
                $purpose='judgement_rop';
                $module='CourtMaster';
                //$wh_mobileno1 = array("919871754198","919540028941","919871922703","919810003580","918763332660","919881397172");
                //$wh_mobileno1 = array("919871754198");
                //$wh_mobileno2 = implode(',', $wh_mobileno1);
                $sql_pool="insert into whatsapp_pool(module,purpose,mobile,diary_no,msg_status,display,entry_time,is_revised)
        values('$module','$purpose','$wh_mobileno',$diary_no,'','Y',now(),'$revised_status')";
                $query_pool=$this->db->query($sql_pool);
                $pool_id= $this->db->insert_id();
                if($pool_id!=0){
                    echo "Data inserted successfully";
                }
                else
                {
                    echo "Data not inserted for $diary_no";
                }
                $sql_user="select * from users where usercode=$_SESSION[dcmis_user_idd]";
                $query_user=$this->db->query($sql_user);
                $result_user=$query_user->result_array();
                $emp_name=$result_user[0]['name'];
                $emp_code=$result_user[0]['empid'];
                $templateCode="icmis::case::judgement_rop::sharing";
                //$file_url='https://webapi.sci.gov.in/'.$pdf_name;
                //$file_url='https://www.sci.gov.in/wp-admin/admin-ajax.php?action=get_judgements_pdf&diary_no='.$diary_no.'&type='.strtolower($type).'&order_date='.$orderdate;
                $file_url='http://10.25.78.60/supreme_court/jud_ord_html_pdf/'.$pdf_name;
                $sms_params=array($type_msg,$pet . " vs " . $res,'diary no. '.$diary_number.'/'.$diary_year,$date_msg );
                $wh_mobileno=explode(',',$wh_mobileno);
                $created_by_user= array("name"=>$emp_name,"id"=>$_SESSION[dcmis_user_idd],"employeeCode"=>$emp_code,"organizationName"=>'SCI');
                $response= $this->send_sms_whatsapp_through_uni_notify(2,$wh_mobileno,$templateCode, $sms_params,null, $purpose,$created_by_user,$module,'ICMIS',$file_name,$file_url);
        
            }
        
        
            function send_sms_whatsapp_through_uni_notify($api_type=null,$mobile_nos=[],$templateCode=null,$sms_params=[],$scheduledAt=null,$purpose=null,$created_by_user=[],$module=null,$project=null,$file_name=null,$file_url=null)
            {
                $sms_request_filters = [
                    "providerCode" => "wa",
                    "recipients" => [
                        "mobileNumbers" => $mobile_nos
                    ],
                    "templateCode" => $templateCode,
                    "templateVariables" => $sms_params,
                    "scheduledAt" => $scheduledAt,
                    "purpose" => $purpose,
                    "createdByUser" => $created_by_user,
                    "module" => $module,
                    "project" => $project
                ];
                if($api_type==2)
                {
                    $fileArray = [
                        "files" => [
                            [
                                "name" => $file_name,
                                "url" => $file_url,
                                "downloadAtStage" => "job_execution",
                                "mimeType" => "application/pdf"
        
                            ]
                        ]
                    ];
                    $sms_request_filters=array_merge($sms_request_filters, $fileArray);
                }
                //echo json_encode($sms_request_filters);
                $curl = curl_init();
        
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'http:///api/v1/send',
                    //CURLOPT_URL => 'http://10.25.78.70:36521/api/v1/send',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 05,               //curl timeout to 5 seconds
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => array('data' => json_encode($sms_request_filters)),
                    CURLOPT_HTTPHEADER => array(
                        'Accept: application/json',
                        'Authorization: Bearer sdfmsdbfjh327654t3ufb57'
                    ),
                ));
        
                $response = curl_exec($curl);
                curl_close($curl);
                //var_dump(curl_error());
                //echo $response;
            }
        }
        ent
        $segment3 = $uri->getSegment(3); // Third segment (if exists)
      
    }

    public function index()
    {
        $diary_no = $this->diary_no;
        $getCaseType = $this->model->getCaseType();
        $usercode = session()->get('login')['usercode'];
        $data['caseTypes'] = $getCaseType;
        $data['usercode'] = $usercode;


        return view('Court/CourtMaster/showEmbedQR', $data);
    }

    public function proceedings()
    {
        $diary_no = $this->diary_no;

        $judge = $this->model->getJudge();
        $usercode = session()->get('login')['usercode'];

        $data['app_name'] = 'Generate ROP';
        $data['judge'] = $judge;
        $data['usercode'] = $usercode;

        return view('Court/CourtMaster/selectDetailForROP', $data);
    }

    public function getBench()
    {
        extract($this->request->getGet());
        $main_head = '';
        $main_supp_flag = '';
        $board_type = '';
        switch ($causelistType) {
            case 1:
                $main_head = 'F';
                $main_supp_flag = '1';
                $board_type = 'J';
                break;
            case 2:
                $main_head = 'F';
                $main_supp_flag = '2';
                $board_type = 'J';
                break;
            case 3:
                $main_head = 'M';
                $main_supp_flag = '1';
                $board_type = 'J';
                break;
            case 4:
                $main_head = 'M';
                $main_supp_flag = '2';
                $board_type = 'J';
                break;
            case 5:
                //$main_head='M';
                $main_supp_flag = '1';
                $board_type = 'C';
                break;
            case 6:
                //$main_head='M';
                $main_supp_flag = '2';
                $board_type = 'C';
                break;
            case 7:
                $main_head = 'M';
                $main_supp_flag = '1';
                $board_type = 'R';
                break;
            case 8:
                $main_head = 'M';
                $main_supp_flag = '2';
                $board_type = 'R';
                break;
            case 9:
                $main_head = 'M';
                $main_supp_flag = '1';
                $board_type = 'CC';
                break;
            case 11:
                $main_head = 'M';
                $main_supp_flag = '1';
                $board_type = 'S';
                break;
        }
        $benches = $this->model->getBenchByJudge($main_head, $board_type, $causelistDate, $pJudge);
        $result = "<option value=''>Select Bench</option>";
        $bench_desc = "";
        foreach ($benches as $bench) {
            // pr($bench);
            $court = "";
            if ($bench['courtno'] == 21)
                $court = "R1";
            else if ($bench['courtno'] == 22)
                $court = "R2";
            else if ($bench['courtno'] > 30 && $bench['courtno'] <= 60) {
                $court = "VC- " . ($bench['courtno'] - 30);
            } else if ($bench['courtno'] > 60 && $bench['courtno'] <= 62) {
                $court = "RVC- " . ($bench['courtno'] - 60);
            } else
                $court = $bench['courtno'];
            if ($bench['session'] == 'Whole Day' && $bench['courtno'] != "" && $bench['courtno'] != 0 && $bench['courtno'] != null) {
                $bench_desc = $bench['session'] . ' in Court ' . $court;
            } else if ($bench['courtno'] != "" && $bench['courtno'] != 0 && $bench['courtno'] != null) {
                $bench_desc = $bench['session'] . ' @ ' . $bench['frm_time'] . ' in Court ' . $court;
            } else {
                $bench_desc = $bench['session'];
            }
            $result .= "<option value='" . $bench['roster_id'] . "'>" . $bench_desc . "</option>";
        }
        echo $result;
    }
	
	public function getBenchMM()
    {
        extract($this->request->getGet());
        $main_head = '';
        $main_supp_flag = '';
        $board_type = '';
        switch ($causelistType) {
            case 1:
                $main_head = 'F';
                $main_supp_flag = '1';
                $board_type = 'J';
                break;
            case 2:
                $main_head = 'F';
                $main_supp_flag = '2';
                $board_type = 'J';
                break;
            case 3:
                $main_head = 'M';
                $main_supp_flag = '1';
                $board_type = 'J';
                break;
            case 4:
                $main_head = 'M';
                $main_supp_flag = '2';
                $board_type = 'J';
                break;
            case 5:
                //$main_head='M';
                $main_supp_flag = '1';
                $board_type = 'C';
                break;
            case 6:
                //$main_head='M';
                $main_supp_flag = '2';
                $board_type = 'C';
                break;
            case 7:
                $main_head = 'M';
                $main_supp_flag = '1';
                $board_type = 'R';
                break;
            case 8:
                $main_head = 'M';
                $main_supp_flag = '2';
                $board_type = 'R';
                break;
            case 9:
                $main_head = 'M';
                $main_supp_flag = '1';
                $board_type = 'CC';
                break;
                case 1:
                    $main_head = 'F';
                    $main_supp_flag = '1';
                    $board_type = 'J';
                    break;
                case 2:
                    $main_head = 'F';
                    $main_supp_flag = '2';
                    $board_type = 'J';
                    break;
                case 3:
                    $main_head = 'M';
                    $main_supp_flag = '1';
                    $board_type = 'J';
                    break;
                case 4:
                    $main_head = 'M';
                    $main_supp_flag = '2';
                    $board_type = 'J';
                    break;
                case 5:
                    //$main_head='M';
                    $main_supp_flag = '1';
                    $board_type = 'C';
                    break;
                case 6:
                    //$main_head='M';
                    $main_supp_flag = '2';
                    $board_type = 'C';
	   case 11:
                $main_head = 'M';
                $main_supp_flag = '1';
                $board_type = 'S';
                break;

        }
        //$data['bench']=$this->CourtMasterModel->getBenchByCourt();
        $benches = $this->model->getBenchByJudgeModified($causelistDate, $pJudge, $main_head,$board_type);
        //var_dump($city);
        $result = "<option value=''>Select Bench</option>";
        $bench_desc = "";
        foreach ($benches as $bench) {
            $court = "";
            if ($bench['courtno'] == 21)
                $court = "R1";
            else if ($bench['courtno'] == 22)
                $court = "R2";
            else if ($bench['courtno'] > 30 && $bench['courtno'] <= 60) {
                $court = "VC- ".($bench['courtno']-30);
            }
            else if ($bench['courtno'] > 60 && $bench['courtno'] <= 62) {
                $court = "RVC- ".($bench['courtno']-60);
            }
            else
                $court = $bench['courtno'];
            if ($bench['session'] == 'Whole Day' && $bench['courtno'] != "" && $bench['courtno'] != 0 && $bench['courtno'] != null) {
                $bench_desc = $bench['session'] . ' in Court ' . $court;
            } else if ($bench['courtno'] != "" && $bench['courtno'] != 0 && $bench['courtno'] != null) {
                $bench_desc = $bench['session'] . ' @ ' . $bench['frm_time'] . ' in Court ' . $court;
            } else {
                $bench_desc = $bench['session'];
            }
            /*if($bench['session']=='Whole Day' && $bench['courtno']!="" && $bench['courtno']!=0 && $bench['courtno']!=null){
                $bench_desc= $bench['session'].' in Court '.$bench['courtno'];
            }
            else if($bench['courtno']!="" && $bench['courtno']!=0 && $bench['courtno']!=null){
                $bench_desc= $bench['session'].' @ '.$bench['frm_time'].' in Court '.$bench['courtno'];
            }
            else{
                $bench_desc= $bench['session'];
            }*/
            $result .= "<option value='" . $bench['roster_id'] . "'>" . $bench_desc . "</option>";
            //$result.="<option value='".$bench['roster_id']."'>".$bench['judgename']."</option>";
        }
        echo $result;

    }

	
    public function getCasesForGeneration()
    {

        extract($this->request->getGet());
        $main_head = '';
        $main_supp_flag = '';
        $board_type = '';
        switch ($causelistType) {
            case 1:
                $main_head = 'F';
                $main_supp_flag = '1';
                $board_type = 'J';
                break;
            case 2:
                $main_head = 'F';
                $main_supp_flag = '2';
                $board_type = 'J';
                break;
            case 3:
                $main_head = 'M';
                $main_supp_flag = '1';
                $board_type = 'J';
                break;
            case 4:
                $main_head = 'M';
                $main_supp_flag = '2';
                $board_type = 'J';
                break;
            case 5:
                //$main_head='M';
                $main_supp_flag = '1';
                $board_type = 'C';
                break;
            case 6:
                //$main_head='M';
                $main_supp_flag = '2';
                $board_type = 'C';
                break;
            case 7:
                $main_head = 'M';
                $main_supp_flag = '1';
                $board_type = 'R';
                break;
            case 8:
                $main_head = 'M';
                $main_supp_flag = '2';
                $board_type = 'R';
                break;
            case 9:
                $main_head = 'M';
                $main_supp_flag = '1';
                $board_type = 'C';
                break;
            case 11:
                $main_head = 'M';
                $main_supp_flag = '1';
                $board_type = 'S';
                break;
        }
        $data['judge'] = $this->model->getJudge();
        $data['cmnsh'] = $this->model->getCmNsh();
        $userdetail = $this->model->getUserDetail($usercode);
        $courtJudges = $this->model->getCoramInCourt($bench);
        $data['caseList'] = $this->model->getCaseGenerationList($bench, $main_head, $main_supp_flag, $board_type, $causelistDate);

        $judgeinitial = array('0', '0', '0', '0', '0');
        $judgedetail = explode(",", $courtJudges[0]['coram']);
        $courtno = $courtJudges[0]['courtno'];
        $courtNoDisplay = "";
        if ($courtno == 21)
            $courtNoDisplay = "R1";
        else if ($courtno == 22)
            $courtNoDisplay = "R2";
        else if ($courtno > 30 && $courtno <= 60) {
            $courtNoDisplay = "Virtual Court " . ($courtno - 30);
        } else if ($courtno > 60 && $courtno <= 62) {
            $courtNoDisplay = "Registrar Virtual Court- " . ($courtno - 60);
        } else
            $courtNoDisplay = $courtno;

        $username = $userdetail[0]['name'];
        $judgesize = count($judgedetail);
        if ($judgesize > 0) {
            for ($i = 0; $i < $judgesize; $i++) {
                $judgeinitial[$i] = $judgedetail[$i];
            }
        }

        $data['judgeinitial'] = $judgeinitial;
        $data['courtNoDisplay'] = $courtNoDisplay;
        $data['username'] = $username;
        $data['courtno'] = $courtno;

        return view('Court/CourtMaster/casesForROPGeneration', $data);
    }

    public function generateRop()
    {
        extract($this->request->getPost());

        $diary_no = $roster_id = $court_no = $item_number = "";
        $checkedCases = "";
        $reg = [];
        foreach (array_keys($judge, '0') as $key) {
            unset($judge[$key]);
        }
        $coram = rtrim(implode(',', $judge), ',');

        //echo '<pre>';
        foreach ($proceeding as $case) {

            $reportable = 0;
            $reportable = $this->request->getPost($case);

            $docDataString = "";
            $datatest = explode('#', $case);

            $diary_no = $datatest[0];
            $roster_id = $datatest[1];
            $court_no = $datatest[2];
            $courtNoDisplay = 0;

            if ($court_no == 21) {
                $courtNoDisplay = 1;
            } elseif ($court_no == 22) {
                $courtNoDisplay = 2;
            } elseif ($court_no > 30 && $court_no <= 60) {
                $courtNoDisplay = "Court " . ($court_no - 30) . " (Video Conferencing)";
            } elseif ($court_no > 60 && $court_no <= 62) {
                $courtNoDisplay = "" . ($court_no - 60) . " THROUGH VIDEO CONFERENCE";
            }

            $item_number = $datatest[3];
            $caseDetails = $this->model->getCaseDetails($diary_no, $roster_id, $causelistDate);
            $connectedCaseDetails = $this->model->connectedCaseDetails($diary_no, $roster_id, $causelistDate);

            $connectedDiaries = [];
            foreach ($connectedCaseDetails as $connC) {
                array_push($connectedDiaries, $connC['diary_no']);
            }
            array_push($connectedDiaries, $diary_no);

            $pAdvDetails = $this->model->getAdvocateDetails($connectedDiaries, 'P');
            $rAdvDetails = $this->model->getAdvocateDetails($connectedDiaries, 'R');

            $lowerCourtDetails = $this->model->getLowerCourtDetails($diary_no);

            if ($caseDetails !== false) {
                $section_name = $caseDetails->section_name;
                if ($caseDetails->section_name == null || $caseDetails->section_name == '') {
                    //$tentativeSection = $this->model->getTentativeSection($diary_no);
                    //$section_name = $tentativeSection->section;
                }

                $docDataString .= "</w:t> <w:cr/><w:t>" . "</w:t> <w:cr/><w:t>";
                $docDataString .= "</w:t> <w:cr/><w:t>" . "</w:t> <w:cr/><w:t>";
                $docDataString .= "</w:t> <w:cr/><w:t>" . "</w:t> <w:cr/><w:t>";

                $docDataString .= "ITEM NO." . $caseDetails->item_number;

                if ($causelistType == 9)
                    $docDataString .= "                        " . "               SECTION " . $section_name;
                else if ($causelistType == 7)
                   $docDataString .= "        REGISTRAR COURT           SECTION " . $section_name;
                else if ($court_no >= 31 && $court_no <= 47)
                    $docDataString .= "     " . $courtNoDisplay . "          SECTION " . $section_name;
                else
                    $docDataString .= "               COURT NO." . $court_no . "               SECTION " . $section_name;
                $docDataString .= "</w:t> <w:cr/><w:t>" . "</w:t> <w:cr/><w:t>" . "               S U P R E M E  C O U R T  O F  I N D I A" . "</w:t> <w:cr/><w:t>" . "                       RECORD OF PROCEEDINGS" . "</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";

                if ($causelistType == 7) {
                    $judges = $this->model->getJudgeName($coram);
                    foreach ($judges as $j) {
                        $docDataString .= "</w:t> <w:cr/><w:t>" . "                     BEFORE THE REGISTRAR " . $j['first_name'] . " " . $j['sur_name'] . "</w:t> <w:cr/><w:t>" . "</w:t> <w:cr/><w:t>";
                    }
                }

                /* if (($caseDetails->casetype_id != 13) && ($caseDetails->casetype_id != 14) && $caseDetails->ia != null && !($caseDetails->ia == "") && !($caseDetails->ia == "null")) {
                    $docDataString .= 'IA ' . $caseDetails->ia . " in ";
                } */
                $regNoDisplay = substr($caseDetails->reg_no_display, (strripos($caseDetails->reg_no_display, " ")));

                if ($caseDetails->registration_number == "" || $caseDetails->registration_number == null) {
                    $diarynumber = $caseDetails->diary_no;
                    $docDataString .= $caseDetails->diary_casetype . " Diary No(s). " . substr($diarynumber, 0, -4) . "/" . substr($diarynumber, -4);
                } else if (explode('-', $caseDetails->registration_number)[0] == 31) {
                    $docDataString .= $caseDetails->reg_no_display;
                } else {
                    if ($caseDetails->casetype_id == 1)
                        $docDataString .= "Petition(s) for Special Leave to Appeal (C) ";
                    else if ($caseDetails->casetype_id == 2)
                        $docDataString .= "Petition(s) for Special Leave to Appeal (Crl.) ";
                    else if ($caseDetails->casetype_id == 3)
                        $docDataString .= "Civil Appeal ";
                    else if ($caseDetails->casetype_id == 4)
                        $docDataString .= "Criminal Appeal ";
                    else if ($caseDetails->casetype_id == 5)
                        $docDataString .= "Writ Petition(s)(Civil) ";
                    else if ($caseDetails->casetype_id == 6)
                        $docDataString .= "Writ Petition(s)(Criminal) ";
                    else if ($caseDetails->casetype_id == 7)
                        $docDataString .= "Transfer Petition(s)(Civil) ";
                    else if ($caseDetails->casetype_id == 8)
                        $docDataString .= "Transfer Petition(s)(Criminal) ";
                    else if ($caseDetails->casetype_id == 9)
                        $docDataString .= " " . $caseDetails->reg_no_display;
                    else if ($caseDetails->casetype_id == 10)
                        $docDataString .= " " . $caseDetails->reg_no_display;
                    else if ($caseDetails->casetype_id == 11)
                        $docDataString .= "Transfer Case (Civil) ";
                    else if ($caseDetails->casetype_id == 12)
                        $docDataString .= "Transfer Case (Criminal) ";
                    else if ($caseDetails->casetype_id == 13)
                        $docDataString .= "Petition(s) for Special Leave to Appeal (C)......CC ";
                    else if ($caseDetails->casetype_id == 14)
                        $docDataString .= "Petition(s) for Special Leave to Appeal (Crl.)...... CRLMP ";
                    else if ($caseDetails->casetype_id == 15)
                        $docDataString .= "Petition(s) for Writ (Civil)........CC ";
                    else if ($caseDetails->casetype_id == 16)
                        $docDataString .= "Petition(s) for Writ (Criminal)..........CRLMP ";
                    else if ($caseDetails->casetype_id == 17)
                        $docDataString .= "Original Suit (s).";
                    else if ($caseDetails->casetype_id == 18)
                        $docDataString .= "Petition(s) for Death Reference Case ";
                    else if ($caseDetails->casetype_id == 19)
                        $docDataString .= " " . $caseDetails->reg_no_display;
                    else if ($caseDetails->casetype_id == 20)
                        $docDataString .= " " . $caseDetails->reg_no_display;
                    else if ($caseDetails->casetype_id == 21)
                        $docDataString .= "Petition(s) for Tax Reference Case ";
                    else if ($caseDetails->casetype_id == 22)
                        $docDataString .= "Petition(s) for Special Reference Case ";
                    else if ($caseDetails->casetype_id == 23)
                        $docDataString .= "Petition(s) for Election (Civil) ";
                    else if ($caseDetails->casetype_id == 24)
                        $docDataString .= "Petition(s) for Arbitration ";
                    else if ($caseDetails->casetype_id == 25)
                        $docDataString .= " " . $caseDetails->reg_no_display;
                    else if ($caseDetails->casetype_id == 26)
                        $docDataString .= " " . $caseDetails->reg_no_display;
                    else if ($caseDetails->casetype_id == 27)
                        $docDataString .= "Petition(s) for REF. U/A 317(1) ";
                    else if ($caseDetails->casetype_id == 39) {
                        //$docDataString .="Miscellaneous Application ";
                        $docDataString .= $this->str_replace_once('MA', 'Miscellaneous Application No. ', $caseDetails->reg_no_display);
                    } else if ($caseDetails->casetype_id == 28)
                        $docDataString .= "Petition(s) for Motion ";
                    else if ($caseDetails->casetype_id == 29)
                        $docDataString .= "Petition(s) for Diary ";
                    else if ($caseDetails->casetype_id == 30)
                        $docDataString .= "Petition(s) for File ";
					else if ($caseDetails->casetype_id == 32)
					$docDataString .= "SUO MOTO WRIT PETITION(CIVIL) ";
					else if ($caseDetails->casetype_id == 33)
					$docDataString .= "SUO MOTO WRIT PETITION(CRIMINAL) ";
					else if ($caseDetails->casetype_id == 34)
					$docDataString .= "SUO MOTO CONTEMPT PETITION(CIVIL) ";
					else if ($caseDetails->casetype_id == 35)
					$docDataString .= "SUO MOTO CONTEMPT PETITION(CRIMINAL) ";
					else if ($caseDetails->casetype_id == 36)
					$docDataString .= "REF. U/S 143 ";
					else if ($caseDetails->casetype_id == 37)
					$docDataString .= "REF. U/S 14 RTI ";
					else if ($caseDetails->casetype_id == 38)
					$docDataString .= "REF. U/S 17 RTI ";
					else if ($caseDetails->casetype_id == 40)
					$docDataString .= "SUO MOTO TRANSFER PETITION(CIVIL) ";
					else if ($caseDetails->casetype_id == 41)
					$docDataString .= "SUO MOTO TRANSFER PETITION(CRIMINAL) ";

                    if ($caseDetails->casetype_id != 39 && $caseDetails->casetype_id != 9 && $caseDetails->casetype_id != 10 && $caseDetails->casetype_id != 19 && $caseDetails->casetype_id != 20 && $caseDetails->casetype_id != 25 && $caseDetails->casetype_id != 26 && $caseDetails->registration_number != "" && $caseDetails->registration_number != null) {
                        $docDataString .= " No(s). " . $regNoDisplay;
                    }
                }

                $docDataString .= "</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";

                if ($causelistType != 5 && $causelistType != 7) {
                    if (($caseDetails->casetype_id != 3) && ($caseDetails->casetype_id != 4) && ($caseDetails->casetype_id != 5) && ($caseDetails->casetype_id != 6) && ($caseDetails->casetype_id != 7) && ($caseDetails->casetype_id != 8) && ($caseDetails->casetype_id != 19) && ($caseDetails->casetype_id != 20)) {
                        $lowerCourtCaseNumber = $judgementdt = $agencyname = "";
                        if (count($lowerCourtDetails) > 0) {
							$docDataString .= "[Arising out of impugned final judgment and order dated ";
                            foreach ($lowerCourtDetails as $lowerDetail) {
                                if ($lowerDetail['casetype'] != null && $lowerDetail['lct_caseno'] != null && $lowerDetail['casetype'] != "" && $lowerDetail['lct_caseno'] != "" && $lowerDetail['lct_dec_dt'] != null) {
                                    $lowerCourtCaseNumber = $lowerDetail['casetype'] . " No. " . $lowerDetail['lct_caseno'] . "/" . $lowerDetail['lct_caseyear'];
                                    $judgementdt = date("d-m-Y", strtotime($lowerDetail['lct_dec_dt']));
                                    $docDataString .= " " . $judgementdt . " in " . $lowerCourtCaseNumber . ",";
                                    $agencyname = $lowerDetail['agency_name'];
                                    $docDataString .= ",";
                                }
                                $docDataString = substr($docDataString, 0, strlen($docDataString) - 2);
                            }
                            // $agencyname = $this->CourtMasterModel->convertToTitleCase($agencyname);
                            $docDataString .= " passed by the " . $agencyname . "]</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                        } else {
                            $docDataString .= " ";
                        }
                    }
                }

                $petitionername = "";
                if ($caseDetails->pno > 2)
                    $petitionername = $caseDetails->pet_name . " & ORS.";
                else if ($caseDetails->pno == 2)
                    $petitionername = $caseDetails->pet_name . " & ANR.";
                else
                    $petitionername = $caseDetails->pet_name;


                $applength = strlen($petitionername);
                $linelength = (51 - $applength);
                $docDataString .= $petitionername;
                for ($l = 1; $l <= $linelength; $l++) {
                    $docDataString .= " ";
                }
                if ($caseDetails->casetype_id == 3 || $caseDetails->casetype_id == 4) {
                    $docDataString .= "Appellant(s)" . "</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                } else {
                    $docDataString .= "Petitioner(s)" . "</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                }
                $docDataString .= "                                VERSUS</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";


                $respondentname = "";
                if ($caseDetails->rno > 2)
                    $respondentname = $caseDetails->res_name . " & ORS.";
                else if ($caseDetails->rno == 2)
                    $respondentname = $caseDetails->res_name . " & ANR.";
                else
                    $respondentname = $caseDetails->res_name;

                $applength = strlen($respondentname);
                $linelength = (51 - $applength);
                $docDataString .= $respondentname;
                for ($l = 1; $l <= $linelength; $l++) {
                    $docDataString .= " ";
                }
                $docDataString .= "Respondent(s)" . "</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";

                //For fetching IA details
                $listed_ia = "";
                $doc_desrip = "";
                $iaDetails = [];
                $listed_ia = (is_null($caseDetails->ia)) ? $caseDetails->ia : rtrim(trim($caseDetails->ia), ",");
                if ($listed_ia != null && $listed_ia != "")
                    $iaDetails = $this->model->getIADetails($listed_ia, $diary_no);


                if (count($iaDetails) > 0) {
                    foreach ($iaDetails as $iaDetail) {
                        $doc_desrip .= "</w:t><w:cr/> <w:t>";
                        $doc_desrip .= "IA No. " . $iaDetail['docnum'] . "/" . $iaDetail['docyear'] . " - " . $iaDetail['docdesp'];
						$doc_desrip .= "</w:t><w:t>";
                    }
                }

                if ($caseDetails->remark != null && $caseDetails->remark != "") {
                    $remarks = strip_tags($caseDetails->remark);
                    $docDataString .= $remarks . " ";
                }

                $docDataString .= $doc_desrip . "</w:t> <w:cr/><w:t> ";

                //For Conncted matters and their Applications
                if (count($connectedCaseDetails) > 0) {
                    $docDataString .= "</w:t> <w:cr/><w:t>WITH</w:t> <w:cr/><w:t>";
                }

                foreach ($connectedCaseDetails as $connDetails) {
                    $connCaseSection = "";
                    $connCaseSection = $connDetails['section_name'];
                    if ($connDetails['section_name'] == null || $connDetails['section_name'] == '') {
                        //$tentativeSectionConn = $this->model->getTentativeSection($connDetails['diary_no']);
                        //$connCaseSection = $tentativeSectionConn->section;
                    }
                    if ($connDetails['reg_no_display'] != null)
                        $docDataString .= $connDetails['reg_no_display'] . " (" . $connCaseSection . ")</w:t> <w:cr/><w:t>";
                    else
                        $docDataString .= "Diary No(s). " . substr($connDetails['diary_no'], 0, -4) . "/" . substr($connDetails['diary_no'], -4) . " (" . $connCaseSection . ")</w:t> <w:cr/><w:t>";

                    //For fetching IA details
                    $conn_listed_ia = "";
                    $conn_doc_desrip = "";
                    $connIaDetails = [];

                    $conn_listed_ia = (is_null($connDetails['ia'])) ? $connDetails['ia'] : rtrim(trim($connDetails['ia']), ",");

                    if ($conn_listed_ia != null && $conn_listed_ia != "")
                        $connIaDetails = $this->model->getIADetails($conn_listed_ia, $connDetails['diary_no']);
                    if (count($connIaDetails) > 0) {
                        foreach ($connIaDetails as $connIaDetail) {
                            $conn_doc_desrip .= "</w:t><w:cr/> <w:t>";
                            $conn_doc_desrip .= "IA No. " . $connIaDetail['docnum'] . "/" . $connIaDetail['docyear'] . " - " . $connIaDetail['docdesp'];
                            $conn_doc_desrip .= "</w:t> <w:t>";
                        }
                    }
                    //END 


                    if (($connDetails['remark'] != null && $connDetails['remark'] != "") ||($conn_doc_desrip != null && $conn_doc_desrip != "")) {
						$docDataString .=  $connDetails['remark']. $conn_doc_desrip . "</w:t> <w:cr/><w:t> ";

                    }

                }

                $listingdate = date("d-m-Y", strtotime($causelistDate));

                if ($caseDetails->ia != null && $caseDetails->ia != "" && $caseDetails->ia != 'null') {
                    $iaArray = explode(',', $caseDetails->ia);
                    if (count($connectedCaseDetails) > 0) {
                        $docDataString .= "</w:t> <w:cr/><w:t>Date : " . $listingdate . " These matters were called on for hearing today.</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                    } else {
                        $docDataString .= "</w:t> <w:cr/><w:t>Date : " . $listingdate . " This matter was called on for hearing today.</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                    }
                } else if ($caseDetails->casetype_id == 3 || $caseDetails->casetype_id == 4) {
                    if ($caseDetails->registration_number != null) {
                        $reg = explode("-", $caseDetails->registration_number);
                        if (count($reg) > 2) {
                            if ($reg[1] == $reg[2])
                                $docDataString .= "</w:t> <w:cr/><w:t>Date : " . $listingdate . " This appeal was called on for hearing today.</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                            else
                                $docDataString .= "</w:t> <w:cr/><w:t>Date : " . $listingdate . " These appeals were called on for hearing today.</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                        } else if (count($reg) > 1) {
                            $docDataString .= "</w:t> <w:cr/><w:t>Date : " . $listingdate . " This appeal was called on for hearing today.</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                        } else {
                            $docDataString .= "</w:t> <w:cr/><w:t>Date : " . $listingdate . " This appeal was called on for hearing today.</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                        }
                    } else {
                        $docDataString .= "</w:t> <w:cr/><w:t>Date : " . $listingdate . " This appeal was called on for hearing today.</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                    }
                } else if ($caseDetails->casetype_id == 9 || $caseDetails->casetype_id == 10 || $caseDetails->casetype_id == 25 || $caseDetails->casetype_id == 26) {
                    $docDataString .= "</w:t> <w:cr/><w:t>Date : " . $listingdate . " This petition was circulated today.</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                } else if ($caseDetails->casetype_id != 3 && $caseDetails->casetype_id != 4 && $caseDetails->casetype_id != 9 && $caseDetails->casetype_id != 10) {
                    if ($caseDetails->registration_number != null) {
                        $reg = explode("-", $caseDetails->registration_number);
                        if (count($reg) > 2) {
                            if ($reg[1] == $reg[2])
                                $docDataString .= "</w:t> <w:cr/><w:t>Date : " . $listingdate . " This petition was called on for hearing today.</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                            else
                                $docDataString .= "</w:t> <w:cr/><w:t>Date : " . $listingdate . " These petitions were called on for hearing today.</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                        } else if (count($reg) > 1) {
                            $docDataString .= "</w:t> <w:cr/><w:t>Date : " . $listingdate . " This petition was called on for hearing today.</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                        } else {
                            $docDataString .= "</w:t> <w:cr/><w:t>Date : " . $listingdate . " This petition was called on for hearing today.</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                        }
                    } else {
                        $docDataString .= "</w:t> <w:cr/><w:t>Date : " . $listingdate . " This petition was called on for hearing today.</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";
                    }
                }

                //Printing Coram Data here
                if ($causelistType != 7) {
                    $docDataString .= "CORAM : ";

                    $judges = $this->model->getJudgeName($coram);
                    //var_dump($judges);
                    foreach ($judges as $judge) {
                        if ($judge['jname'] != null && $judge['jname'] != "") {
                            $docDataString .= "</w:t> <w:cr/><w:t>         " . strtoupper($judge['jname']);
                        }
                    }
                    if ($causelistType == 5)
                        $docDataString .= "</w:t> <w:cr/><w:t>                           [IN CHAMBER]</w:t> <w:cr/><w:t>";

                    $docDataString .= "</w:t> <w:cr/><w:t>";
                }
                //END of Coram

                //For Petitioner advocate
                if ($causelistType != 9) {
                    if ($caseDetails->casetype_id == 3 || $caseDetails->casetype_id == 4)
                         $docDataString .= "</w:t> <w:cr/><w:t>For Appellant(s) : ";
                    else
                         $docDataString .= "</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>For Petitioner(s) : ";

                    if (count($pAdvDetails) > 0) {
                        $pAdvFinal = $pAdv = "";
                        foreach ($pAdvDetails as $pAdvd) {
                            if ($pAdvd['advoate_code'] == 584) {
                                $pAdvFinal .= $pAdvd['title'] . ' ' . $this->convertToTitleCase($pAdvd['advocate_name']) . "</w:t> <w:cr/><w:t>";
                            } else {
                                $addedForAppearance = [];
                                //$addedForAppearance = $this->model->getAdvocateAppearanceDetails($connectedDiaries, $pAdvd['pet_res'], $causelistDate, $pAdvd['aor_code']);
                                if (count($addedForAppearance) > 0) {
                                    /*$pAdvFinal .= "</w:t> <w:cr/><w:t>                   ";
                                    $AORtoIncludeORexclude = $this->model->getAdvocateAppearanceAORIncludeORExclude($connectedDiaries, $pAdvd['pet_res'], $causelistDate, $pAdvd['aor_code']);
                                    if(count($AORtoIncludeORexclude)==0){
                                        $pAdvFinal .= $pAdvd['title'] . ' ' .$this->convertToTitleCase($pAdvd['advocate_name']) . ", AOR</w:t> <w:cr/><w:t>                   ";
                                    }
                                    foreach ($addedForAppearance as $addedForAppearance) {
                                        $pAdvFinal .= $addedForAppearance['advocate_title'] . ' ' .$this->convertToTitleCase($addedForAppearance['advocate_name']) . ", ".$addedForAppearance['advocate_type']."</w:t> <w:cr/><w:t>                   ";
                                    }
                                    $pAdvFinal .= "</w:t> <w:cr/><w:t>                   ";*/

                                    //sql getAdvocateAppearanceDetails inside this table appearing_in_diary is not exist & not used.
                                } else {
                                    $pAdvFinal .= $pAdvd['title'] . ' ' . $this->convertToTitleCase($pAdvd['advocate_name']) . ", AOR</w:t> <w:cr/><w:t>";
                                }
                            }
                        }
                        $docDataString .= $pAdvFinal;
                    }
                     $docDataString .= "</w:t> <w:cr/><w:t>For Respondent(s) : ";

                    if (count($rAdvDetails) > 0) {
                        $rAdvFinal = $rAdv = "";
                        foreach ($rAdvDetails as $rAdvd) {

                            if ($rAdvd['advoate_code'] == 585) {
                                $rAdvFinal .= $rAdvd['title'] . ' ' . $this->convertToTitleCase($rAdvd['advocate_name']) . "</w:t> <w:cr/><w:t>";
                            } else {
                                $addedForAppearance = [];
                                //$addedForAppearance = $this->model->getAdvocateAppearanceDetails($connectedDiaries, $rAdvd['pet_res'], $causelistDate, $rAdvd['aor_code']);

                                if (count($addedForAppearance) > 0) {

                                    /*$rAdvFinal .= "</w:t> <w:cr/><w:t>                   ";

                                    $AORtoIncludeORexclude = $this->model->getAdvocateAppearanceAORIncludeORExclude($connectedDiaries, $rAdvd['pet_res'], $causelistDate, $rAdvd['aor_code']);

                                    if(count($AORtoIncludeORexclude)==0){
                                        $rAdvFinal .= $rAdvd['title'] . ' ' .$this->model->convertToTitleCase($rAdvd['advocate_name']) . ", AOR</w:t> <w:cr/><w:t>                   ";
                                    }
                                    foreach ($addedForAppearance as $addedForAppearance) {
                                        $rAdvFinal .= $addedForAppearance['advocate_title'] . ' ' .$this->model->convertToTitleCase($addedForAppearance['advocate_name']) . ", ".$addedForAppearance['advocate_type']."</w:t> <w:cr/><w:t>                   ";
                                    }
                                    $rAdvFinal .= "</w:t> <w:cr/><w:t>                   ";*/

                                    //sql getAdvocateAppearanceDetails inside this table appearing_in_diary is not exist & not used.
                                } else {
                                    $rAdvFinal .= $rAdvd['title'] . ' ' . $this->convertToTitleCase($rAdvd['advocate_name']) . ", AOR</w:t> <w:cr/><w:t>";
                                }
                            }
                        }
                        $docDataString .= $rAdvFinal;
                    }
                    //END

                    if ($causelistType == 9) {
                        $docDataString .= "</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>                    By Circulation</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>" . "</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>          UPON perusing papers the Court made the following";
                    } else if ($causelistType == 7) {
                        $docDataString .= "</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>          UPON hearing the counsel the Court made the following";
                    } else {
                        $docDataString .= "</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>          UPON hearing the counsel the Court made the following";
                    }
                    $docDataString .= "</w:t> <w:cr/><w:t>                             O R D E R</w:t> <w:cr/><w:t></w:t> <w:cr/><w:t></w:t> <w:cr/><w:t>";

                    if ($causelistType != 7) {
                        $signatory1 = $this->model->getUserNameAndDesignation($usercode);
                        $signatory2 = $this->model->getUserNameAndDesignation($user2);
                        $linelength = strlen($signatory1->name);
                        $docDataString .= "(" . $signatory1->name . ")";
                        for ($l = 1; $l <= (46 - $linelength); $l++)
                            $docDataString .= " ";
                        $docDataString .= "(" . $signatory2->name . ")" . "</w:t> <w:cr/><w:t>";
                        $designation1 = $signatory1->type_name;
                        $linelength = strlen($designation1);
                        $docDataString .= $designation1;
                        for ($l = 1; $l <= (45 - $linelength); $l++)
                            $docDataString .= " ";
                        $docDataString .= " " . $signatory2->type_name . "</w:t> <w:cr/><w:t>";
                    } else if ($causelistType == 7) {
                        $judges = $this->model->getJudgeName($coram);
                        foreach ($judges as $j) {
                            $registrarName = str_replace('MR. ', '', $j['first_name']) . " " . $j['sur_name'];
                            $registrarName = str_replace('SH. ', '', $registrarName);
                            $registrarName = str_replace('MS. ', '', $registrarName);
                            $judgeslength = strlen($registrarName);
                            $judgeslinelength = (62 - $judgeslength);
                            $docDataString .= "</w:t> <w:cr/><w:t>";
                            for ($l = 1; $l <= $judgeslinelength; $l++)
                                $docDataString .= " ";
                            $docDataString .= $registrarName . "</w:t> <w:cr/><w:t>                                                     Registrar";
                        }
                    }

                    $filename = "";
                    $fileNameDetail = $this->model->getFileName($diary_no, date("Y-m-d", strtotime($listingdate)), $roster_id, $item_number);

                    if ($fileNameDetail->file_name != null && $fileNameDetail->file_name != "") {
                        $filename = $fileNameDetail->file_name;
                    } else {
                        if ($fileNameDetail->registration_number != null && $fileNameDetail->registration_number != "" && explode('-', $caseDetails->registration_number)[0] != 31) {
                            $regNo = explode("-", $fileNameDetail->registration_number);
                            if (count($regNo) > 2) {
                                if ($regNo[1] == $regNo[2]) {
                                    $filename = $fileNameDetail->casetype_desc . (int)$regNo[1] . $fileNameDetail->registration_year;
                                } else {
                                    $filename = $fileNameDetail->casetype_desc . (int)$regNo[1] . '-' . (int)$regNo[2] . $fileNameDetail->registration_year;
                                }
                            } else if (count($reg) > 1) {
                                $filename = $fileNameDetail->casetype_desc . (int)$regNo[1] . $fileNameDetail->registration_year;
                            } else {
                                $filename = 'DI' . $fileNameDetail->diary_no;
                            }
                        } else {
                            $filename = 'DI' . $fileNameDetail->diary_no;
                        }
                        $filename .= '#' . $roster_id . '#' . $court_no . '#' . $item_number;
                    }

                    $docDataString = str_replace('&amp;', '&', $docDataString);
                    $docDataString = str_replace('&AMP;', '&', $docDataString);
                    $docDataString = str_replace('&', '&amp;', $docDataString);
                    $dir = "rop_" . $listingdate . "_" . $usercode;
                    //$this->create_docx($dir, $filename, $docDataString);
                    $dataForUpdate = array('order_date' => date("Y-m-d", strtotime($listingdate)), 'court_number' => $court_no, 'item_number' => $item_number, 'diary_no' => $diary_no, 'order_details' => $docDataString, 'generated_by' => $usercode, 'file_name' => $filename, 'order_type' => 'O', 'is_oral_mentioning' => 0, 'roster_id' => $roster_id, 'is_reportable' => $reportable);
                    $this->model->updateProceedingsDetail($dataForUpdate);
                }
            }
        }

        $zip_file = $dir . '.zip';
        $path = "uploaded_documents/assets/courtMaster";
        $rootPath = $path . '/' . $dir;
        if (!file_exists($rootPath)) {
            mkdir($rootPath, 0755, true);
        }
        $zip = new ZipArchive();
        $zip->open($rootPath . '/' . $zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                // $relativePath = substr($filePath, $rootPath);
                $zip->addFile($filePath, $rootPath);
            }
        }
        $zip->close();
        ob_clean();

        $data = file_get_contents($rootPath . '/' . $zip_file);
        $name = $zip_file;
        force_download($name, $data);
        if (file_exists($rootPath . '/' . $zip_file)) {
            //var_dump($path . '/' . $zip_file);
            try {
                array_map('unlink', glob($path . '/' . "$dir/*.*"));
                rmdir($path . '/' . $dir);
                unlink($rootPath . '/' . $zip_file);
            } catch (Exception $e) {
                echo $e . message();
            }
        }
    }

    private function str_replace_once($str_pattern, $str_replacement, $string)
    {

        if (strpos($string, $str_pattern) !== false) {
            $occurrence = strpos($string, $str_pattern);
            return substr_replace($string, $str_replacement, strpos($string, $str_pattern), strlen($str_pattern));
        }

        return $string;
    }

    public function convertToTitleCase($str)
    {
        return str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($str))));
    }

    private function create_docx($folder, $fileName, $text)
    {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/supreme_court/Copying/assets/courtMaster';
        $template_file = $path . '/courtmasterTemplate.docx';
        $fileName = $fileName . ".docx";
        $fullpath = $path . '/' . $folder . '/' . $fileName;
        if (!file_exists($path . '/courtmasterTemplate.docx')) {
            copy($path . '/template/courtmasterTemplate.docx', $path . '/courtmasterTemplate.docx');
        }
        try {
            //Create the Result Directory if Directory is not created already
            if (!file_exists($path . '/' . $folder)) {
                mkdir($path . '/' . $folder);
            }
            //Copy the Template file to the Result Directory
            copy($template_file, $fullpath);
            $zip = new ZipArchive;
            //Docx file is nothing but a zip file. Open this Zip File
            if ($zip->open($fullpath) == true) {
                //In the Open XML Wordprocessing format content is stored
                //in the document.xml file located in the word directory.

                $key_file_name = 'word/document.xml';
                $message = $zip->getFromName($key_file_name);

                //Replace the Placeholders with actual values
                $message = str_replace("myText", $text, $message);


                //Replace the content with the new content created above.
                $zip->addFromString($key_file_name, $message);
                $zip->close();
            }
        } catch (Exception $e) {
            $error_message = "Error creating the Word Document";
            //TODO : Handle the error message
        }
    }

    public function generate()
    {
        $diary_no = $this->diary_no;
        $number = uniqid();
        extract($this->request->getPost());
        if (isset($causelistDate) && empty($causelistDate)) {
            return $this->index();
        }

        if (!empty($this->request->getFiles('fileROPList'))) {

            $fileROPList = $this->request->getFiles('fileROPList');

            $desired_dir = "uploaded_documents/qr_assets/" . $number;

            // $desired_dir = "/home/reports/supremecourt/qr_assets/" . uniqid();

            foreach ($fileROPList['fileROPList'] as $key => $fileROPListVal) {

                $file_name = $fileROPListVal->getName();
                $file_tmp = $fileROPListVal->getTempName();
                $fileNameWithoutExtension = pathinfo($file_name, PATHINFO_FILENAME);
                $fileNameWithoutExtensionList = explode('_', $fileNameWithoutExtension);
                $fileNameWithoutExtension = $fileNameWithoutExtensionList[0];
                $fileExtension = pathinfo($file_name, PATHINFO_EXTENSION);

                $causelistDate = date('Y-m-d', strtotime($causelistDate));

                $res = $this->model->getFileProceedingDetail($fileNameWithoutExtension, $causelistDate);

                if ($fileExtension == "pdf") {
                    if ($res) {
                        $fileProceedingDetail = $res[0];
                        if ($fileProceedingDetail != "" && $fileProceedingDetail != null) {
                            $diarynumber = $fileProceedingDetail->diary_no;
                            $diary_number_only = substr($diarynumber, 0, -4);
                            $diary_year = substr($diarynumber, -4);


                            $orderDateFile = date_create($fileProceedingDetail->order_date);
                            $orderDateFile = date_format($orderDateFile, 'd-M-Y');

                            $desired_dir_in_db = "supremecourt/" . $diary_year . "/" . $diary_number_only;

                            $uploadedFileName = $diary_number_only . "_" . $diary_year . "_" . $fileProceedingDetail->court_number . "_" . $fileProceedingDetail->item_number . "_" . $fileProceedingDetail->roster_id . "_Order_" . $orderDateFile . "." . $fileExtension;


                            $file_url_on_web = env('LIVE_URL') . $desired_dir_in_db . "/" . $uploadedFileName;


                            if (is_dir($desired_dir) == false) {
                                mkdir($desired_dir, 0755, true); // Create directory if it does not exist
                            }
                            if (is_dir("$desired_dir/" . $file_name) == false) {


                                $fileROPListVal->move("$desired_dir/after_qr_embed/", $file_name);

                                $this->generateQR($file_url_on_web, $fileNameWithoutExtension, $desired_dir);
                            }
                        }
                    }
                }
            }


            ////////Code for making zip file and force download
            if (!is_dir($desired_dir)) {
                if (!mkdir($desired_dir, 0755, true)) {
                    die('Failed to create directories...');
                }
            }
            if (is_dir($desired_dir)) {
                // pr($desired_dir."/" . $number . ".pdf");
                $moveFile = move_uploaded_file($file_tmp, $desired_dir . "/" . $number . ".pdf");
                if (is_dir($desired_dir . "/after_qr_embed") == false) {
                    mkdir($desired_dir . "/after_qr_embed", 0755, true);
                    // pr($desired_dir."/" ."after_qr_embed/" . $number . ".pdf");
                    move_uploaded_file($file_tmp, $desired_dir . "/" . "after_qr_embed/" . $number . ".pdf");
                }

                if (!$moveFile) {
                    die('Failed to move uploaded file...');
                }
                $file_url_on_web = $desired_dir . "/" . $number . ".pdf";
                $this->generateQR($file_url_on_web, $number, $desired_dir);
            } else {
                die('Failed to create directory...');
            }

            $data = file_get_contents($desired_dir . '/' . 'after_qr_embed/' . $number . '.pdf');
            $name = $file_name;
            // $this->delete_directory($desired_dir);
            force_download($name, $data);


            // $rootPath = $desired_dir . "/after_qr_embed/";
            // pr($rootPath);
            // $zip = new ZipArchive();
            // $zip->open($desired_dir . '/after_qr_embed.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
            // if (!is_dir($rootPath)) {
            //     echo "Directory does not exist.";
            // }
            // $files = new RecursiveIteratorIterator(
            //     new RecursiveDirectoryIterator($rootPath),
            //     RecursiveIteratorIterator::LEAVES_ONLY
            // );

            // foreach ($files as $name => $file) {
            //     if (!$file->isDir()) {
            //         $filePath = $file->getRealPath();
            //         $relativePath = substr($filePath, strlen($rootPath));
            //         $zip->addFile($filePath, $relativePath);
            //     }
            // }
            // $zip->close();
            // ob_clean();
            // $data = file_get_contents($desired_dir . '/after_qr_embed.zip'); //assuming my file is on localhost
            // $name = $causelistDate . "_withqr.zip";
            // // $this->delete_directory($desired_dir);
            // force_download($name, $data);

            //after that code level is not cleared
        }
    }

    private function delete_directory($folderName)
    {
        // Load the file helper
        helper('file');

        // Check if the directory exists
        if (is_dir($folderName)) {
            // Delete all files in the directory
            delete_files($folderName, true);

            // Delete the directory itself
            rmdir($folderName);

            return true;
        }
        return false;
    }
    private function delete_directory2($folderName)
    {
        helper('file');
        if (is_dir($folderName)) {
            delete_files($folderName, true); // Delete files into the folder
            rmdir($folderName); // Delete the folder
            return true;
        }
        return false;
    }

    private function generateQR($file_url_on_web, $file_name, $desired_dir)
    {
        if (is_dir($desired_dir) == false) {
            mkdir($desired_dir, 0755, true);  // Create directory if it does not exist

        }
        if (is_dir($desired_dir . "/after_qr_embed") == false) {
            mkdir($desired_dir . "/after_qr_embed", 0777, true);
        }
        ini_set('display_errors', 1);
        $finalfile = $desired_dir . "/after_qr_embed/" . $file_name . ".pdf";
        $file = $desired_dir . "/" . $file_name . "_qr.png";
        $qr_file_url = $desired_dir . "/" . $file_name . "_qr.pdf";
        $pdf_file = $desired_dir . "/" . $file_name . ".pdf";
        $ecc = 'L';
        $pixel_Size = 10;
        $frame_Size = 10;
        $this->qrlib->QRcodePng($file_url_on_web, $file, $ecc, $pixel_Size, $frame_Size);
        $this->Fpdf->AddPage();
        $this->Fpdf->Image($file, $this->Fpdf->Getx() + 140, $this->Fpdf->GetY() - 5, 25.00); //For TOP right
        $this->Fpdf->Output($qr_file_url, 'F');

        $nm_s =  shell_exec('pdftk ' . $pdf_file . ' dump_data | grep NumberOfPages');
        $NumberOfPages = !empty($nm_s) ? str_replace('NumberOfPages: ', '', $nm_s) : 0;
        if ($NumberOfPages == 1) {
            shell_exec("pdftk " . $pdf_file . " background " . $qr_file_url . " output " . $finalfile . " ");
        } else {
            $page_one_file_name = $desired_dir . "/after_qr_embed/page_1_" . $file_name . ".pdf";
            $page_one_file_name_with_qr = $desired_dir . "/after_qr_embed/page_1_with_qr_" . $file_name . ".pdf";
            shell_exec("pdftk " . $pdf_file . " cat 1 output " . $page_one_file_name . " ");
            shell_exec("pdftk " . $page_one_file_name . " background " . $qr_file_url . " output " . $page_one_file_name_with_qr . " ");
            shell_exec("pdftk A=" . $page_one_file_name_with_qr . " B=" . $pdf_file . " cat A1 B2-end output " . $finalfile . " ");

            if (is_dir($page_one_file_name != false)) {
                unlink($page_one_file_name);
            }

            if (is_dir($page_one_file_name != false)) {
                unlink($page_one_file_name_with_qr);
            }
        }
        move_uploaded_file($pdf_file, $desired_dir . "/" . "after_qr_embed/" . $file_name . ".pdf");
    }


    public function rePrint()
    {
        $diary_no = $this->diary_no;
        return view('Court/CourtMaster/rePrint');
    }

    public function getReprintJO()
    {

        $diary_no = $this->diary_no;

        $txt_o_frmdt = date('Y-m-d', strtotime($this->request->getGet('txt_o_frmdt')));
        $txt_o_todt = date('Y-m-d', strtotime($this->request->getGet('txt_o_todt')));

        if ($this->request->getGet('order_upload') == 'O') {
            $order_upload = 'orderdate';
        } elseif ($this->request->getGet('order_upload') == 'U') {
            $order_upload = 'date(ent_dt)';
        }

        $data['getOrdernet'] = $this->model->getOrdernet($order_upload, $txt_o_frmdt, $txt_o_todt);
        $data['txt_o_frmdt'] = $txt_o_frmdt;
        $data['txt_o_todt'] = $txt_o_todt;
        $data['order_upload'] = $order_upload;
        $data['getRosterJudge'] =  new CourtMasterModel();
        return view('Court/CourtMaster/getReprintJOData', $data);
    }

    public function getRosterJudge($roster_id)
    {

        $getRosterJudge = $this->model->getRosterJudge($roster_id);

        $jud_name = '';
        foreach ($getRosterJudge as $getRosterJudgeVal):
            if ($jud_name == '') {
                $jud_name = $getRosterJudgeVal['jname'];
            } else {
                $jud_name = $jud_name . ',' . $getRosterJudgeVal['jname'];
            }
        endforeach;

        return $jud_name;
    }

    public function getPdfName()
    {

        $docid = $this->request->getGet('docid');

        $getPdfname = $this->model->getPdfname($docid);

        if (sizeof($getPdfname) > 0) {
            $path = $getPdfname[0]['pdfname'];
            echo '../jud_ord_html_pdf/' . $path;
        } else {
            echo '0';
        }
    }

    public function UploadOneByOne()
    {
        $diary_no = $this->diary_no;
        $getCaseDetailsForReplace = $this->model->getCaseDetailsForReplace($diary_no);
        $data['caseDetails'] = $getCaseDetailsForReplace;
        $usercode = session()->get('login')['usercode'];
        $data['usercode'] = $usercode;
        $getCaseType = $this->model->getCaseType();
        $data['caseTypes'] = $getCaseType;

        return view('Court/CourtMaster/replaceProceedings', $data);
    }

    public function getListedDetails()
    {

        extract($this->request->getPost());
        $usercode = session()->get('login')['usercode'];
        $listedInfo = explode('#', $id);
        $diary_no = $listedInfo[0];
        $listing_date = $listedInfo[1];
        $roster_id = $listedInfo[2];
        $court_number = $listedInfo[3];
        $item_number = $listedInfo[4];

        $data['languages'] = $this->model->getLanguage($usercode);
        $data['judge'] = $this->model->getAllJudge($usercode);
        $courtJudges = $this->model->getCoramInCourt($roster_id);
        $judges = explode(',', $courtJudges[0]['coram']);
        $coram = $this->model->getJudge($judges);

        $judgesInCoram = "";
        foreach ($coram as $index => $j) {
            if ($index == 0) {
                $firstJudge = $j['jcode'];
                $judgesInCoram = $j['jname'];
            } else {
                $judgesInCoram = $judgesInCoram . ',' . $j['jname'];
            }
        }

        $data['firstJudge'] = $firstJudge;
        $data['judgesInCoram'] = $judgesInCoram;

        return view('Court/CourtMaster/showReplaceProceedings', $data);
    }


    public function embedQRCaseWise()
    {
        // pr($_FILES);
        // var_dump($this->request->getPost());
        // var_dump($_FILES['fileROPList']);

        extract($this->request->getPost());
        $data = explode('#', $listingDates);
        if (!isset($is_reportable)) {
            $is_reportable = 0;
        }
        $diary_number = $data[0];
        $order_date = $data[1]; // This is a string
        $roster_id = $data[2];
        $court_number = $data[3];
        $item_number = $data[4];
        $number = uniqid();
        $desired_dir = "uploaded_documents/qr_assets/" . $number;
        if (isset($_FILES['fileROPList'])) {
            $file_name = $_FILES['fileROPList']['name'];
            $file_size = $_FILES['fileROPList']['size'];
            $file_tmp = $_FILES['fileROPList']['tmp_name'];
            $file_type = $_FILES['fileROPList']['type'];
            $fileNameWithoutExtension = pathinfo($file_name, PATHINFO_FILENAME);
            $fileNameWithoutExtensionList = explode('_', $fileNameWithoutExtension);
            $fileNameWithoutExtension = $fileNameWithoutExtensionList[0];
            $fileExtension = pathinfo($file_name, PATHINFO_EXTENSION);
            $diary_number_only = substr($diary_number, 0, -4);
            $diary_year = substr($diary_number, -4);
            $orderDateFile = date_create($order_date);
            $orderDateFile = date_format($orderDateFile, 'd-M-Y');
            //$desired_dir = "/home/reports/supremecourt/qr_assets/".uniqid();
            $desired_dir_in_db = "uploaded_documents/" . $diary_year . "/" . $diary_number_only;
            $uploadedFileName = $diary_number_only . "_" . $diary_year . "_" . $court_number . "_" . $item_number . "_" . $roster_id . "_" . $orderType . "_" . $orderDateFile . "." . $fileExtension;
            // $file_url_on_web = LIVE_URL . $desired_dir_in_db . "/" . $uploadedFileName;
            if (!is_dir($desired_dir)) {
                if (!mkdir($desired_dir, 0755, true)) {
                    die('Failed to create directories...');
                }
            }
            if (is_dir($desired_dir)) {
                // pr($desired_dir."/" . $number . ".pdf");
                $moveFile = move_uploaded_file($file_tmp, $desired_dir . "/" . $number . ".pdf");
                if (is_dir($desired_dir . "/after_qr_embed") == false) {
                    mkdir($desired_dir . "/after_qr_embed", 0755, true);
                    // pr($desired_dir."/" ."after_qr_embed/" . $number . ".pdf");
                    move_uploaded_file($file_tmp, $desired_dir . "/" . "after_qr_embed/" . $number . ".pdf");
                }

                if (!$moveFile) {
                    die('Failed to move uploaded file...');
                }
                $file_url_on_web = $desired_dir . "/" . $number . ".pdf";
                $this->generateQR($file_url_on_web, $number, $desired_dir);
            } else {
                die('Failed to create directory...');
            }
        }
        $data = file_get_contents($desired_dir . '/' . 'after_qr_embed/' . $number . '.pdf');
        $name = $file_name;
        // $this->delete_directory($desired_dir);
        force_download($name, $data);
    }

    public function replaceROP()
    {

        extract($this->request->getPost());

        $data = explode('#', $listingDates);
        if (!isset($is_reportable)) {
            $is_reportable = 0;
        }
        $diary_number = $data[0];
        $order_date = $data[1];
        $roster_id = $data[2];
        $court_number = $data[3];
        $item_number = $data[4];
        $orderTextData = "";
        $fileListStatus = array();

        $usercode = session()->get('login')['usercode'];

        if (!empty($this->request->getFiles('fileROPList'))) {

            $fileROPList = $this->request->getFiles('fileROPList');

            $file_name = $fileROPList['fileROPList']->getName();
            $file_size = $fileROPList['fileROPList']->getSize();
            $file_tmp = $fileROPList['fileROPList']->getTempName();
            $file_type = $fileROPList['fileROPList']->getMimeType();

            $fileListStatus += array($file_name => "");
            $fileProceedingDetail = "";
            $fileNameWithoutExtension = pathinfo($file_name, PATHINFO_FILENAME);
            $fileNameWithoutExtensionList = explode('_', $fileNameWithoutExtension);
            $fileNameWithoutExtension = $fileNameWithoutExtensionList[0];
            $fileExtension = pathinfo($file_name, PATHINFO_EXTENSION);

            $orderTypeShort = 'O';
            if ($orderType == 'Judgement') {
                $orderTypeShort = 'J';
            } else if ($orderType == 'FinalOrder') {
                $orderTypeShort = 'FO';
            }

            $c_type = null;

            $languages = explode('#', $language);
            $languageId = $languages[0];
            $languageName = $languages[1];
            $nc_status = 1;      // added on 8.6.23

            $res = $this->model->getDiaryProceedingDetail($diary_number, $order_date, $court_number, $item_number, $roster_id, $orderType);

            if ($orderType == 'Judgement') { //added on 8.6.23

                $nc_res = $this->model->getNCDetails($diary_number, $order_date);
                if (!$nc_res) {
                    $nc_status = 0;
                }
            }
            //added on 8.6.23 ends

            $resVernacular = $this->model->getVernacularJudgmentDetail($diary_number, $order_date, $orderTypeShort, $languageId);

            if ($fileExtension == "pdf") {
                if ($res) {
                    $fileProceedingDetail = $res[0];
                    if ($fileProceedingDetail != "" && $fileProceedingDetail != null) {
                        $orderTextData = "";
                        if ($language == 1)
                            $orderTextData = $this->getTextFromPDF($file_tmp);

                        $diarynumber = $fileProceedingDetail->diary_no;
                        $diary_number_only = substr($diarynumber, 0, -4);
                        $diary_year = substr($diarynumber, -4);
                        $desired_dir = "/home/reports/supremecourt/" . $diary_year . "/" . $diary_number_only;
                        $desired_dir_in_db = "supremecourt/" . $diary_year . "/" . $diary_number_only;
                        $orderDateFile = date_create($fileProceedingDetail->order_date);
                        $orderDateFile = date_format($orderDateFile, 'd-M-Y');
                        $uploadedFileName = $diary_number_only . "_" . $diary_year . "_" . $fileProceedingDetail->court_number . "_" . $fileProceedingDetail->item_number . "_" . $fileProceedingDetail->roster_id . "_" . $orderType . "_" . $orderDateFile . "." . $fileExtension;

                        if (($fileProceedingDetail->pdfname == '' || $fileProceedingDetail->pdfname == null) && $languageId == 1) {
                            if ($fileProceedingDetail->upload_flag == 0 || $fileProceedingDetail->upload_flag == null || $fileProceedingDetail->type == null) {
                                if (is_dir($desired_dir) == false) {
                                    mkdir("$desired_dir", 0755, true); // Create directory if it does not exist
                                }
                                if (is_dir("$desired_dir/" . $uploadedFileName) == false) {
                                    $fileROPList['fileROPList']->move("$desired_dir/", $uploadedFileName);
                                }
                                $c_type = null;
                                if ($fileProceedingDetail->registration_number != null && $fileProceedingDetail->registration_number != "") {
                                    $c_type = substr($fileProceedingDetail->registration_number, 0, 2);
                                }
                                if ($orderType == 'Judgement') {
                                    $dataForUpdate = array('diary_no' => $diarynumber, 'order_date' => date_format(date_create($fileProceedingDetail->order_date), 'Y-m-d'), 'pdf_name' => $desired_dir_in_db . '/' . $uploadedFileName, 'usercode' => $usercode, 'type' => 'J', 'roster_id' => $fileProceedingDetail->roster_id, 'case_type' => (int)$c_type, 'registration_number' => $fileProceedingDetail->registration_number, 'registration_year' => $fileProceedingDetail->registration_year, 'filename' => $fileNameWithoutExtension, 'orderTextData' => $orderTextData, 'is_reportable' => $is_reportable, 'presiding_judge' => $presiding_judge);
                                } elseif ($orderType == 'FinalOrder') {
                                    $dataForUpdate = array('diary_no' => $diarynumber, 'order_date' => date_format(date_create($fileProceedingDetail->order_date), 'Y-m-d'), 'pdf_name' => $desired_dir_in_db . '/' . $uploadedFileName, 'usercode' => $usercode, 'type' => 'FO', 'roster_id' => $fileProceedingDetail->roster_id, 'case_type' => (int)$c_type, 'registration_number' => $fileProceedingDetail->registration_number, 'registration_year' => $fileProceedingDetail->registration_year, 'filename' => $fileNameWithoutExtension, 'orderTextData' => $orderTextData, 'is_reportable' => $is_reportable, 'presiding_judge' => $presiding_judge);
                                } else {
                                    $dataForUpdate = array('diary_no' => $diarynumber, 'order_date' => date_format(date_create($fileProceedingDetail->order_date), 'Y-m-d'), 'pdf_name' => $desired_dir_in_db . '/' . $uploadedFileName, 'usercode' => $usercode, 'type' => 'O', 'roster_id' => $fileProceedingDetail->roster_id, 'case_type' => (int)$c_type, 'registration_number' => $fileProceedingDetail->registration_number, 'registration_year' => $fileProceedingDetail->registration_year, 'filename' => $fileNameWithoutExtension, 'orderTextData' => $orderTextData, 'is_reportable' => $fileProceedingDetail->is_reportable, 'presiding_judge' => $presiding_judge);
                                }

                                if ($nc_status == 1) {
                                    $result = $this->model->insertProceedingsInOrderNet($dataForUpdate);

                                    if ($result == 1) {
                                        $fileListStatus[$file_name] = "Uploaded Successfully.";
                                    } else {
                                        $fileListStatus[$file_name] = "File is already uploaded using one-by-one option.";
                                    }
                                } else {
                                    $fileListStatus[$file_name] = "Please first generate Neutral Citation number and QR Code for the case.";
                                }
                            } else {
                                $fileListStatus[$file_name] = "ROP for this case was already uploaded.";
                            }
                        } elseif (($fileProceedingDetail->pdfname == '' || $fileProceedingDetail->pdfname == null) && $languageId != 1) {
                            $fileListStatus[$file_name] = "English Version Judgment is not uploaded. Please contact concerned Court Master.";
                        } else {

                            if ($languageId == 1) {
                                $new_replaced_name = explode('.', $fileProceedingDetail->pdfname);
                                $new_replaced_name = $new_replaced_name[0] . '_' . date('d-M-Y_h_i_s_A', strtotime($fileProceedingDetail->ent_dt)) . '_' . $fileProceedingDetail->usercode . '.pdf';

                                $success = rename("/home/reports/" . $fileProceedingDetail->pdfname, "/home/reports/" . $new_replaced_name);
                                if ($success) {
                                    $fileROPList['fileROPList']->move("$desired_dir/", $uploadedFileName);

                                    if ($orderType == 'Judgement') {
                                        $dataForUpdate = array('diary_no' => $diarynumber, 'order_date' => date_format(date_create($fileProceedingDetail->order_date), 'Y-m-d'), 'pdf_name' => $desired_dir_in_db . '/' . $uploadedFileName, 'usercode' => $usercode, 'type' => 'J', 'roster_id' => $fileProceedingDetail->roster_id, 'case_type' => (int)$c_type, 'registration_number' => $fileProceedingDetail->registration_number, 'registration_year' => $fileProceedingDetail->registration_year, 'filename' => $fileNameWithoutExtension, 'orderTextData' => $orderTextData, 'is_reportable' => $is_reportable, 'presiding_judge' => $presiding_judge);
                                    } elseif ($orderType == 'FinalOrder') {
                                        $dataForUpdate = array('diary_no' => $diarynumber, 'order_date' => date_format(date_create($fileProceedingDetail->order_date), 'Y-m-d'), 'pdf_name' => $desired_dir_in_db . '/' . $uploadedFileName, 'usercode' => $usercode, 'type' => 'FO', 'roster_id' => $fileProceedingDetail->roster_id, 'case_type' => (int)$c_type, 'registration_number' => $fileProceedingDetail->registration_number, 'registration_year' => $fileProceedingDetail->registration_year, 'filename' => $fileNameWithoutExtension, 'orderTextData' => $orderTextData, 'is_reportable' => $is_reportable, 'presiding_judge' => $presiding_judge);
                                    } else {
                                        $dataForUpdate = array('diary_no' => $diarynumber, 'order_date' => date_format(date_create($fileProceedingDetail->order_date), 'Y-m-d'), 'pdf_name' => $desired_dir_in_db . '/' . $uploadedFileName, 'usercode' => $usercode, 'type' => 'O', 'roster_id' => $fileProceedingDetail->roster_id, 'case_type' => (int)$c_type, 'registration_number' => $fileProceedingDetail->registration_number, 'registration_year' => $fileProceedingDetail->registration_year, 'filename' => $fileNameWithoutExtension, 'orderTextData' => $orderTextData, 'is_reportable' => $fileProceedingDetail->is_reportable, 'presiding_judge' => $presiding_judge);
                                    }

                                    if ($nc_status == 1) {
                                        $result = $this->model->insertProceedingsInOrderNet($dataForUpdate);

                                        if ($result == 1) {
                                            $fileListStatus[$file_name] = "Uploaded and replaced successfully.";
                                        }
                                    } else {
                                        $fileListStatus[$file_name] = "Please first generate Neutral Citation number and QR Code for the case.";
                                    }
                                } else {
                                    $fileListStatus[$file_name] = "Error while replacing uploaded file.";
                                }
                            } elseif ($language != 1) {

                                $desired_dir_vernacular = "/home/reports/supremecourt_vernacular/" . $diary_year . "/" . $diary_number_only;
                                $desired_dir_in_db_vernacular = "supremecourt_vernacular/" . $diary_year . "/" . $diary_number_only;
                                $ifExist = 0;
                                if ($resVernacular) {
                                    $resVernacularDetail = $resVernacular[0];
                                    if ($resVernacularDetail->pdf_name != '' && $resVernacularDetail->pdf_name != null) {
                                        $ifExist = 1;
                                        $new_replaced_vernacular_name = explode('.', $resVernacularDetail->pdf_name);
                                        $new_replaced_vernacular_name = $new_replaced_vernacular_name[0] . '_' . date('d-M-Y_h_s_i_A', strtotime($resVernacularDetail->entry_date)) . '_' . $resVernacularDetail->user_code . '.pdf';

                                        $success = rename("/home/reports/" . $resVernacularDetail->pdf_name, "/home/reports/" . $new_replaced_vernacular_name);
                                        if ($success) {
                                            $fileROPList['fileROPList']->move("/home/reports/", $resVernacularDetail->pdf_name);
                                            $dataForUpdate = array('diary_no' => $diarynumber, 'order_date' => date_format(date_create($fileProceedingDetail->order_date), 'Y-m-d'), 'ref_vernacular_languages_id' => $languageId, 'pdf_name' => $resVernacularDetail->pdf_name, 'user_code' => $usercode, 'entry_date' => date('Y-m-d h:i:s'), 'order_type' => $orderTypeShort, 'display' => 'Y', 'web_status' => '0');

                                            $result = $this->model->insertVernacularOrdersJudgments($dataForUpdate);
                                            if ($result == 1) {
                                                $fileListStatus[$file_name] = "Vernacular File Uploaded and replaced successfully.";
                                            }
                                        } else {
                                            $fileListStatus[$file_name] = "Error while replacing uploaded Vernacular file.";
                                        }
                                    }
                                }
                                if ($ifExist == 0) {
                                    if (is_dir($desired_dir_vernacular) == false) {
                                        mkdir($desired_dir_vernacular, 0755, true);
                                    }
                                    $uploadedFileName = $diary_number_only . "_" . $diary_year . "_" . $fileProceedingDetail->court_number . "_" . $fileProceedingDetail->item_number . "_" . $fileProceedingDetail->roster_id . "_" . $orderType . "_" . $orderDateFile . "_" . $languageName . "." . $fileExtension;

                                    $fileROPList['fileROPList']->move("$desired_dir_vernacular/", $uploadedFileName);
                                    $dataForUpdate = array('diary_no' => $diarynumber, 'order_date' => date_format(date_create($fileProceedingDetail->order_date), 'Y-m-d'), 'ref_vernacular_languages_id' => $languageId, 'pdf_name' => $desired_dir_in_db_vernacular . '/' . $uploadedFileName, 'user_code' => $usercode, 'entry_date' => date('Y-m-d h:i:s'), 'order_type' => $orderTypeShort, 'display' => 'Y', 'web_status' => '0');

                                    $result = $this->model->insertVernacularOrdersJudgments($dataForUpdate);
                                    if ($result == 1) {
                                        $fileListStatus[$file_name] = "Uploaded successfully.";
                                    }
                                }
                            }
                        }
                    } else {
                        $fileListStatus[$file_name] = "ROP is not generated for this case yet.";
                    }
                } else {
                    $fileListStatus[$file_name] = "ROP is not generated for this case yet.";
                }
            } else {
                $fileListStatus[$file_name] = "Only PDF is permitted";
            }

            echo '<script>alert("' . $fileListStatus[$file_name] . '")</script>';
            echo '<script>window.location.href="' . base_url('Court/CourtMasterController/UploadOneByOne') . '"</script>';
        }
    }


    function getTextFromPDF($file)
    {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($file);
        $text = $pdf->getText();
        return $text;
    }

    //........New added 29-08-2024.....//
    public function get_session_upload()
    {
        // $this->session->set_userdata('dcmis_user_idd', $session);

        return view('Court/CourtMaster/UploadProceedings/upload');
    }

    // public function getUploadPage()
    // {
    //     //$this->session->set_userdata('dcmis_user_idd', $session);
    //     $this->load->view('CourtMaster/uploadProceedings.php');
    // }

    public function getCasesForUploading()
    {
        extract($_GET);
        $usercode = session()->get('login')['usercode'];
        $cause_list_date = $causelistDate;
        $data['cmaDol'] = $cause_list_date;
        $data['cmaU'] = $usercode;
        $data['cmaUn'] = $this->model->getUserNameAndDesignation($usercode);
        $data['caseList'] = $this->model->getCaseUploadingList($cause_list_date, $usercode);

        return view('Court/CourtMaster/UploadProceedings/casesForROPUploading', $data);
    }

    public function uploadROP()
    {
        extract($_POST);
        $usercode = session()->get('login')['usercode'];
        $orderTextData = "";
        $fileListStatus = array();
        if (isset($_FILES['fileROPList'])) {
            //var_dump($_FILES['fileROPList']);
            //$data += array($category => $question);
            foreach ($_FILES['fileROPList']['tmp_name'] as $key => $tmp_name) {
                $file_name = $_FILES['fileROPList']['name'][$key];
                $file_size = $_FILES['fileROPList']['size'][$key];
                $file_tmp = $_FILES['fileROPList']['tmp_name'][$key];
                $file_type = $_FILES['fileROPList']['type'][$key];

                $myhash = md5_file($file_tmp);

                $myhashWithDateTime = md5(file_get_contents($file_tmp) . date('Y-m-d h:i:s'));

                $fileListStatus += array($file_name => "");
                $fileProceedingDetail = "";
                $fileNameWithoutExtension = pathinfo($file_name, PATHINFO_FILENAME);
                $fileNameWithoutExtensionList = explode('_', $fileNameWithoutExtension);
                $fileNameWithoutExtension = $fileNameWithoutExtensionList[0];
                $fileExtension = pathinfo($file_name, PATHINFO_EXTENSION);
                //$res=$this->model->getFileProceedingDetail($fileNameWithoutExtension);
                $causelistDate = date('Y-m-d', strtotime($causelistDate));
                $res = $this->model->getFileProceedingDetail($fileNameWithoutExtension, $causelistDate);
                if ($fileExtension == "pdf") {
                    if ($res) {
                        $fileProceedingDetail = $res[0];
                        if ($fileProceedingDetail != "" && $fileProceedingDetail != null) {
                            $generatedByList = explode(',', $fileProceedingDetail->generated_by);
                            if (in_array($usercode, $generatedByList)) {
                                if ($fileProceedingDetail->upload_flag == 0 || $fileProceedingDetail->upload_flag == null) {
                                    $orderTextData = $this->getTextFromPDF($file_tmp);
                                    $diarynumber = $fileProceedingDetail->diary_no;
                                    $diary_number_only = substr($diarynumber, 0, -4);
                                    $diary_year = substr($diarynumber, -4);
                                    //$desired_dir="/home/ubuntu/reports/supremecourt/".$diary_year."/".$diary_number_only;
                                    $desired_dir = getBasePath() . "/reports/supremecourt/" . $diary_year . "/" . $diary_number_only;
                                    $desired_dir_in_db = "supremecourt/" . $diary_year . "/" . $diary_number_only;
                                    $orderDateFile = date_create($fileProceedingDetail->order_date);
                                    $orderDateFile = date_format($orderDateFile, 'd-M-Y');
                                    //$uploadedFileName = $diary_number_only . "_" . $diary_year . "_Order_" . $orderDateFile . "." . $fileExtension;
                                    $uploadedFileName = $diary_number_only . "_" . $diary_year . "_" . $fileProceedingDetail->court_number . "_" . $fileProceedingDetail->item_number . "_" . $fileProceedingDetail->roster_id . "_Order_" . $orderDateFile . "." . $fileExtension;
                                    //echo $uploadedFileName;
                                    /*if($this->isStringInFile($file_tmp,"adbe.pkcs7.sha1")){*/
                                    if (is_dir($desired_dir) == false) {
                                        //echo "Inside to create directory: ".$desired_dir;
                                        mkdir("$desired_dir", 0755, true);        // Create directory if it does not exist
                                    }
                                    if (is_dir("$desired_dir/" . $uploadedFileName) == false) {
                                        move_uploaded_file($file_tmp, "$desired_dir/" . $uploadedFileName);
                                    }
                                    //mysql_query($query);
                                    $c_type = null;
                                    if ($fileProceedingDetail->registration_number != null && $fileProceedingDetail->registration_number != "") {
                                        $c_type = substr($fileProceedingDetail->registration_number, 0, 2);
                                    }
                                    $dataForUpdate = array('diary_no' => $diarynumber, 'order_date' => date_format(date_create($fileProceedingDetail->order_date), 'Y-m-d'), 'pdf_name' => $desired_dir_in_db . '/' . $uploadedFileName, 'usercode' => $usercode, 'type' => 'O', 'roster_id' => $fileProceedingDetail->roster_id, 'case_type' => (int)$c_type, 'registration_number' => $fileProceedingDetail->registration_number, 'registration_year' => $fileProceedingDetail->registration_year, 'filename' => $fileNameWithoutExtension, 'orderTextData' => $orderTextData, 'is_reportable' => $fileProceedingDetail->is_reportable);
                                    $result = $this->model->insertProceedingsInOrderNet($dataForUpdate, $myhash, $myhashWithDateTime);
                                    if ($result == 1) {
                                        $fileListStatus[$file_name] = "Uploaded Successfully.";
                                    } else {
                                        $fileListStatus[$file_name] = "File is already uploaded using one-by-one option.";
                                    }
                                    /*}
                                    else
                                        $fileListStatus[$file_name]="PDF File is not digitally signed";*/
                                } else {
                                    $fileListStatus[$file_name] = "ROP for this case was already uploaded.";
                                }
                            } else {
                                $fileListStatus[$file_name] = "You are not authorise to upload this file.";
                            }
                        } else {
                            $fileListStatus[$file_name] = "ROP is not generated for this case yet.";
                        }
                    } else {
                        $fileListStatus[$file_name] = "ROP is not generated for this case yet.";
                    }
                } else {
                    $fileListStatus[$file_name] = "Only PDF is permitted";
                }
            }
        }
        $data['fileListStatus'] = $fileListStatus;
        return view('Court/CourtMaster/UploadProceedings/uploadProceedingsStatus', $data);
    }

    //.......New added 30-08-2023.......//
    function vernacularJudgmentReport()
    {
        $this->data['app_name'] = 'vernacularJudgmentReport';
        $this->data['uploadedVernacularJudgmentsReport'] = '';
        $data['param'] = '';
        $this->data['msg'] = '';

        if ($_POST) {
            $fromDate = date('Y-m-d', strtotime($_POST['fromDate']));
            $toDate = date('Y-m-d', strtotime($_POST['toDate']));
            $this->data['uploadedVernacularJudgmentsReport'] = $this->model->getUploadedVernacularJudgmentsList($fromDate, $toDate);
            $this->data['param'] = array($fromDate, $toDate);
            // pr($this->data['uploadedVernacularJudgmentsReport']);
            // die;
        }
        if (is_array($this->data['uploadedVernacularJudgmentsReport'])) {
            $this->data['msg'] = '';
        } else {
            $this->data['msg'] = 'Record Not Found';
        }
        // $this->load->view('Court/VernacularJudgments/uploadedVernacularJudgmentReport',$this->data);
        return view('Court/CourtMaster/VernacularJudgments/uploadedVernacularJudgmentReport', $this->data);
    }

    public function getCaseListingDetails()
    {
        // Use the request object to get POST data
        $request = $this->request;

        // Extract POST data
        $search_type = $request->getPost('optradio');
        $case_type = $request->getPost('caseType');
        $case_number = $request->getPost('caseNo');
        $case_year = $request->getPost('caseYear');
        $diary_number = $request->getPost('diaryNumber');
        $diary_year = $request->getPost('diaryYear');
        $msg = $request->getPost('msg');

        // Initialize the variable for diary number search
        $diaryNumberForSearch = null;

        if (!empty($search_type) && $search_type != null) {
            if ($search_type == 'D') {

                $data = [
                    'search_type' => $search_type,
                    'diary_number' => $diary_number,
                    'diary_year' => $diary_year,
                ];
            } else {

                $data = [
                    'search_type' => $search_type,
                    'case_type' => $case_type,
                    'case_number' => $case_number,
                    'case_year' => $case_year,
                ];
            }
        } else {
            $data = [
                'search_type' => $search_type
            ];
        }


        if ($search_type == 'D') {
            $diary_no = $diary_number . $diary_year;
            $get_main_table = $this->Dropdown_list_model->get_diary_details_by_diary_no($diary_no);
        } elseif ($search_type == 'C') {
            $diary_no = get_diary_case_type($case_type, $case_number, $case_year);
            if (!empty($diary_no)) {
                $get_main_table = $this->Dropdown_list_model->get_diary_details_by_diary_no($diary_no);
            } else {
                $get_main_table = array();
            }
        }

        if (!empty($get_main_table)) {
            $diaryNumberForSearch = $get_main_table['diary_no'];
        }
        $data = [];
        $data['usercode'] = session()->get('login')['usercode'];

        if ($diaryNumberForSearch !== null) {
            $data['caseDetails'] = $this->model->getCaseDetailsForReplace($diaryNumberForSearch);
        } elseif (empty($msg)) {
            $data['msg'] = "No record found!!";
        }

        // Return the view with the data
        return view('Court/CourtMaster/detailsforQRUpdate', $data);
    }
}
