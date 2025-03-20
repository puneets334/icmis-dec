<?php

namespace App\Models\Filing;

use CodeIgniter\Model;

class Model_efiling extends Model
{
    public $e_filing;
    public $db;
    public function __construct()
    {
        parent::__construct();
        $db = \Config\Database::connect();
        $this->e_filing = \Config\Database::connect('eservices');
    }

    public function get_efiling_documents($id, $year, $transaction_no = 0)
    {
        $condition = "1=1";
        if ($transaction_no != 0) {
            $condition = "transaction_id=$transaction_no";
        }
        $query_diary = $this->db->table('main')->select("diary_no")->where(['ack_id' => $id, 'ack_rec_dt' => "$year"]);
        $res = $query_diary->get();
        if ($res->getNumRows() >= 1) {
            $diary_data = $res->getRowArray();
            $query = $this->db->table('indexing');
            $query->select("indexing.*, users.name, users.email_id, users.mobile_no, (select docdesc from master.docmaster where docmaster.doccode=indexing.doccode and doccode1=0 and (display='Y' or display='E') ) as docdesc");
            $query->select("LEFT(CAST(diary_no AS CHAR), -4) AS dy_prefix, RIGHT(CAST(diary_no AS CHAR), 4) AS dy_suffix");
            $query->select("CONCAT('https://main.sci.gov.in/e_filing/index_pdf/', RIGHT(CAST(diary_no AS text), 4), '/',LEFT(CAST(diary_no AS text), -4), '/', pdf_name) AS pdf_file");
            $query->join('master.users', "users.usercode=indexing.ucode", 'left');
            $query->where('diary_no', $diary_data['diary_no']);
            // $query->where('docmaster.display', 'Y');
            if ($transaction_no != 0) {
                $query->where('transaction_id', $transaction_no);
            }
            $query->orderBy('fp', 'asc');
          
            $query = $query->get();
            
            if ($query->getNumRows() >= 1) {
                $result = $query->getResultArray();
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function get_transactions_by_refID($ack_id, $ack_year)
    {

        $query = $this->db->table('e_filing.efiling_transaction_records t')
            ->select('*')
            ->join('e_filing.main m', 'm.diary_no = t.diary_no', 'left')
            ->where('status_id', 1)
            ->where('t.diary_no !=', 0)
            ->where('ack_id', $ack_id)
            ->where("TO_CHAR(ack_rec_dt, 'YYYY')", "$ack_year")
            ->where("t.transaction_id IS NOT NULL AND TRIM(t.transaction_id) !=", '')
            ->orderBy('t.transaction_datetime', 'asc')
            ->get();
        //$query=$this->db_eFiling->getLastQuery();echo (string) $query;exit();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }
    public function doc_for_refiling($ack_id, $ack_year, $marked_for_refiling)
    {
        // $sql = "update indexing set file_status=1, refiling_level=refiling_level+1 where diary_no=(select diary_no from main where ack_id=$ack_id and YEAR(ack_rec_dt)=$ack_year) and display='Y' and ind_id in (".implode(',',$marked_for_refiling).") ";
        $subQuery = $this->db->table('e_filing.main')
            ->select('diary_no')
            ->where('ack_id', $ack_id)
            ->where("TO_CHAR(ack_rec_dt, 'YYYY')", $ack_year)
            ->getCompiledSelect();

        $response = $this->db->table('e_filing.indexing')
            ->set('file_status', 1)
            ->set('refiling_level', 'refiling_level+1', false)
            ->where("diary_no = ($subQuery)", null, false)
            ->where('display', 'Y')
            ->whereIn('ind_id', $marked_for_refiling)
            ->update();
        if ($response) {
            return true;
        } else {
            return false;
        }
    }

    public function old_refiling_refiledcases($from_date, $to_date, $is_archival_table)
    {
        $loggegInUser = $_SESSION['login']['usercode'];
        $allowUsers = ['903', '1124'];

        /*$query =$this->db->table('refiled_old_efiling_case_efiled_docs ref_old');
        $query->select("concat(u.name, ' (', u.usercode, ')') as user_detail, 
            concat(b.title, ' ', b.name, ' (', b.aor_code, ')' ) as bar_adv,
        concat(LEFT(CAST(m.diary_no AS TEXT), -4) ,' / ', RIGHT(CAST(m.diary_no AS TEXT), 4)) as case_no,ref_old.*, m.pet_name, m.res_name, m.reg_no_display");
        $query->join("main$is_archival_table m", 'ref_old.diary_no = m.diary_no','left');
        $query->join('master.users u', 'ref_old.allocated_to = u.usercode','left');
        $query->join('master.bar b', 'ref_old.created_by = b.aor_code','left');
        $query->where('date(ref_old.created_at) >=', $from_date);
        $query->where('date(ref_old.created_at) <=', $to_date);
        $query->where('u.display', 'Y');
        $query->where('ref_old.display', 'Y');
       if(!in_array($loggegInUser, $allowUsers)){ $query->where('ref_old.allocated_to', $loggegInUser); }
        $query=$query->get();*/

        $builder = $this->db->table('refiled_old_efiling_case_efiled_docs ref_old');
        $builder->select("ref_old.diary_no,CONCAT(u.name, ' (', u.usercode, ')') AS user_detail");
        $builder->select("CONCAT(b.title, ' ', b.name, ' (', b.aor_code, ')') AS bar_adv");
        $builder->select("CONCAT(LEFT(CAST(ref_old.diary_no AS TEXT), -4), ' / ', RIGHT(CAST(ref_old.diary_no AS TEXT), 4)) AS case_no");
        $builder->select('ref_old.*, m.pet_name, m.res_name, m.reg_no_display');
        $builder->join('master.users u', 'ref_old.allocated_to = u.usercode', 'left');
        $builder->join('master.bar b', 'ref_old.created_by = b.aor_code', 'left');
        $builder->join("main m", 'ref_old.diary_no = m.diary_no', 'left');
        /*$builder->Join('(SELECT pet_name, res_name, diary_no, reg_no_display FROM public.main m1
                 UNION 
                 SELECT pet_name, res_name, diary_no, reg_no_display FROM public.main_a m2) m', 'm.diary_no = ref_old.diary_no','left');*/
        $builder->where("TO_CHAR(ref_old.created_at,'YYYY-MM-DD') between '$from_date' and '$to_date'");

        $builder->where('u.display', 'Y');
        $builder->where('ref_old.display', 'Y');
        if (!in_array($loggegInUser, $allowUsers)) {
            $builder->where('ref_old.allocated_to', $loggegInUser);
        }
        $query = $builder->get();
        //$query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray(); //echo '<pre>';print_r($result);exit();
        return $result;
    }
    public function docs_from_sc_diary_no_docsby($diary_number, $diary_year, $transaction_id, $is_archival_table)
    {
        $condition = "1=1";
        if (strlen($transaction_id) == 17)
        $condition = "transaction_id = '$transaction_id'";
        else {
            if ($transaction_id != 0)
            $condition = "transaction_id = $transaction_id";
        }
        $diary = $diary_number . $diary_year;

        $sql = "select a.*, b.pet_name, b.res_name, b.reg_no_display,users.name, users.email_id, users.mobile_no 
   from( select i.ind_id as id, $diary as diary_no,np, null as source_flag, d.docdesc as sub_doc, 
    (select docdesc from master.docmaster where doccode=i.doccode limit 1) as main_doc,
    CONCAT('https://main.sci.gov.in/e_filing/index_pdf/', RIGHT(CAST(diary_no AS text), 4), '/', LEFT(CAST(diary_no AS text), -4), '/', pdf_name) as pdf_file,
    entdt,null as transaction_id, ucode, 0 as org_doccode, 0 as org_doccode1, 0 as org_docnum, 0 as org_docyear 
   from indexing i left join master.docmaster d on d.doccode=i.doccode and d.doccode1=i.doccode1 
   where diary_no = (select diary_no from main where diary_no=$diary) and i.display='Y' and $condition 
    union
   select id, diary_no, CAST(pages as int), 'Additional_docs', d.docdesc as sub_doc, 
    (select docdesc from master.docmaster where doccode=a.doccode limit 1) as main_doc, 
    CONCAT('https://main.sci.gov.in/e_filing/add_pdf/', RIGHT(CAST(diary_no AS text), 4), '/', LEFT(CAST(diary_no AS text), -4), '/', pdf_name) as pdf_file,
    entdt,transaction_id, ucode, org_doccode, org_doccode1, org_docnum, org_docyear from e_filing.additional_docs a 
    left join master.docmaster d on d.doccode=a.doccode and d.doccode1=a.doccode1 where diary_no = $diary and a.display='Y' and $condition
    )a left join main$is_archival_table b on b.diary_no=$diary left join master.users on users.usercode=a.ucode order by entdt";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }


function docs_from_sc_diary_no($diary_number, $diary_year, $transaction_id=0, $is_archival_table=null){
    $condition = "1=1";
      if(strlen($transaction_id)==17)
            $condition = "transaction_id = '$transaction_id'";
        else
        {   
            if($transaction_id!=0)
                $condition = "transaction_id = '$transaction_id'";
    
        }
    
        $diary = $diary_number . $diary_year;
           $sql = "
            select a.*, b.pet_name, b.res_name, b.reg_no_display,users.name, users.email, users.mobile_no  from(
select CAST(i.ind_id as TEXT) as id, diary_no,cast(np as text) as np, cast(source_flag as text) as source_flag , d.docdesc as sub_doc, 
(select docdesc from e_filing.docmaster where doccode=i.doccode limit 1) as main_doc, CONCAT('https://main.sci.gov.in/e_filing/index_pdf/', 
substr( CAST(diary_no as TEXT) , -4 ), '/', substr( CAST(diary_no as TEXT), 1, length( CAST(diary_no as TEXT) ) -4 ), '/', pdf_name) as pdf_file,entdt,transaction_id, ucode, 
i.doccode, i.doccode1,  0 as org_docnum,  0 as org_docyear
from e_filing.indexing i
left join e_filing.docmaster d on d.doccode=i.doccode and d.doccode1=i.doccode1
where diary_no = (select diary_no from e_filing.main where org_diary_no=$diary)
and i.display='Y' and $condition
union
select CAST(id as TEXT) as id, diary_no, cast(pages as text) as pages, 'additional_docs', d.docdesc as sub_doc, 
(select docdesc from e_filing.docmaster where doccode=a.doccode limit 1) as main_doc, CONCAT('https://main.sci.gov.in/e_filing/add_pdf/', 
substr( CAST(diary_no as TEXT) , -4 ), '/', substr( CAST(diary_no as TEXT), 1, length( CAST(diary_no as TEXT) ) -4 ), '/', pdf_name) as pdf_file,entdt,transaction_id, ucode, 
a.doccode, a.doccode1, a.org_docnum, a.org_docyear
from e_filing.additional_docs a
left join e_filing.docmaster d on d.doccode=a.doccode and d.doccode1=a.doccode1
where diary_no = $diary and a.display='Y' and $condition
)a
left join public.main$is_archival_table b on b.diary_no=$diary
left join e_filing.users on users.id=a.ucode order by entdt ";
 
            $query = $this->db->query($sql);
            $old_data = $query->getResultArray();
    
            $sql1 = "select  * 
            from 
            (select efiling_no,ed.diary_no,efiled_type,ed.display,docdesc, created_at
            from public.efiled_docs ed 
            join public.docdetails d on ed.diary_no = d.diary_no 
            inner join master.docmaster d2 on d2.doccode=d.doccode and d2.doccode1=d.doccode1 and ed.docnum=d.docnum 
            where ed.diary_no = $diary
            union
            select efiling_no,ec.diary_no,efiled_type,ec.display,  'F' as  docnum,created_at from public.efiled_cases ec where ec.diary_no = $diary and ec.efiled_type='new_case'
            )ed
            
            where ed.display = 'Y' and docdesc is not null";
            $query = $this->db->query($sql1);
            $new_data = $query->getResultArray();

            return ['old'=>$old_data, 'new'=>$new_data];
            //return $query->result_array();
        }

    public function docs_from_sc_diary_no_SCEFM($diary_number, $diary_year, $is_archival_table)
    {
        $diary = $diary_number . $diary_year;
        $sql = "select  * 
        from 
        (select efiling_no,ed.diary_no,efiled_type,ed.display,docdesc, created_at
        from public.efiled_docs ed 
        join public.docdetails$is_archival_table d on ed.diary_no = d.diary_no 
        inner join master.docmaster d2 on d2.doccode=d.doccode and d2.doccode1=d.doccode1 and ed.docnum=d.docnum 
        where ed.diary_no = $diary
        union
        select efiling_no,ec.diary_no,efiled_type,ec.display,  'F' as  docnum,created_at 
        from public.efiled_cases ec where ec.diary_no = $diary and ec.efiled_type='new_case'
        )ed
        where ed.display = 'Y' and docdesc is not null";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return false;
        }
    }
    public function get_pip_report($fromDate, $toDate, $status_flag, $app_type, $action_type, $is_archival_table)
    {
        $session_r = $_SESSION['login']['usercode'];
        if (($app_type == '') || ($app_type == 1)) {
            $con = '';
        }
        if ($app_type == 2) {
            $con = "and app_flag='Filing'";
        }
        if ($app_type == 3) {
            $con = "and app_flag='Add Doc'";
        }
        if ($app_type == 4) {
            $con = "and app_flag='Deficit'";
        }
        if ($app_type == 5) {
            $con = "and app_flag='Deficit_DN'";
        }
        if ($app_type == 6) {
            $con = "and app_flag='Add Doc Sp'";
        }
        if ($app_type == 7) {
            $con = "and app_flag='Refiling'";
        }

        if (($action_type) == 'a') {
            $con_action = " and action_update in ('Y','N')";
        }
        if ($action_type == 'Y') {
            $con_action =  " and action_update = 'Y'";
        }

        if ($action_type == 'N') {
            $con_action =  " and action_update = 'N'";
        }
        if ($app_type == 3) {
            $sql = "select t.*, t.diary_no as d_no, pet_name, res_name, ack_id, SUBSTRING(ack_rec_dt::text FROM 1 FOR 4) AS ack_year, 
       (select casename from master.casetype where casecode=casetype_id)casename,bar.name as adv_name,bar.mobile,bar.email
                from e_filing.efiling_transaction_records t
                left join main m on  m.diary_no = t.diary_no
                               left join master.bar on m.pet_adv_id=bar.bar_id
                              left join master.users on m.last_usercode=users.usercode
                              left join e_filing.additional_docs ad on m.last_usercode=ad.ucode
          
                where status_id=$status_flag and t.diary_no!=0  and (t.transaction_id is not null and trim(t.transaction_id)!='')
                and date(t.transaction_datetime) between '$fromDate' and '$toDate' and (app_flag != 'Deficit_DN' and app_flag != 'Add Doc' and  app_flag != 'Add Doc Sp') $con  and users.attend='P' and ad.transaction_id is not null
                union
                select t.*, t.diary_no as d_no, pet_name, res_name, ack_id, SUBSTRING(ack_rec_dt::text FROM 1 FOR 4) AS ack_year, 
                       (select casename from master.casetype where casecode=casetype_id)casename,bar.name as adv_name,bar.mobile,bar.email
                from e_filing.efiling_transaction_records t

                                left join main$is_archival_table m on  m.diary_no = t.diary_no 
                                left join master.bar on m.pet_adv_id=bar.bar_id
                                left join docdetails$is_archival_table d on m.diary_no=d.diary_no
                                left join master.users on CAST(t.payment_userid as int) =users.usercode
                                
                where status_id=$status_flag and t.diary_no!=0  and (t.transaction_id is not null and trim(t.transaction_id)!='') 
                    and d.advocate_id in (799,800,868,892,1034,1372) 
                and date(t.transaction_datetime) between '$fromDate' and '$toDate' and (app_flag = 'Deficit_DN' or app_flag = 'Add Doc' or  app_flag = 'Add Doc Sp') $con   and users.attend='P' and d.display='Y' 
                order by transaction_datetime desc";
        } else {
            $sql = "select t.*, t.diary_no as d_no, pet_name, res_name, ack_id, SUBSTRING(ack_rec_dt::text FROM 1 FOR 4) AS ack_year, 
       (select casename from master.casetype where casecode=casetype_id)casename,bar.name as adv_name,bar.mobile,bar.email
                from e_filing.efiling_transaction_records t
                left join main m on  m.diary_no = t.diary_no
                               left join master.bar on m.pet_adv_id=bar.bar_id
                               left join master.users on m.last_usercode=users.usercode
          
                where status_id=$status_flag and t.diary_no!=0  and (t.transaction_id is not null and trim(t.transaction_id)!='')
                and date(t.transaction_datetime) between '$fromDate' and '$toDate' and (app_flag != 'Deficit_DN' and app_flag != 'Add Doc' and  app_flag != 'Add Doc Sp') $con  and users.attend='P' and m.pet_adv_id=0
                union
                select t.*, t.diary_no as d_no, pet_name, res_name, ack_id, SUBSTRING(ack_rec_dt::text FROM 1 FOR 4) AS ack_year, 
                       (select casename from master.casetype where casecode=casetype_id)casename,bar.name as adv_name,mobile,bar.email
                from e_filing.efiling_transaction_records t

                               left join main m on  m.diary_no = t.diary_no
                                left join master.bar on m.pet_adv_id=bar.bar_id
                where status_id=$status_flag and t.diary_no!=0  and (t.transaction_id is not null and trim(t.transaction_id)!='')
                and date(t.transaction_datetime) between '$fromDate' and '$toDate' and (app_flag = 'Deficit_DN' or app_flag = 'Add Doc' or  app_flag = 'Add Doc Sp') $con   and m.pet_adv_id in (799,800,868,892,1034,1372)
                order by transaction_datetime desc";
        }

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            //echo '<pre>';print_r($result);exit();
            return $result;
        } else {
            return false;
        }
    }

    public function get_transactions_by_date($fromDate, $toDate, $status_flag, $app_type, $session_r, $action_type, $is_archival_table)
    {
        $r = array();
        $query = $this->db->table('master.users')->select('*')->where('usercode', $session_r)->get(1);
        if ($query->getNumRows() >= 1) {
            $r = $query->getRowArray();
        }
        //echo '<pre>';print_r($r);exit();
        /* $sql_sec="select * from users where usercode =".$session_r;
        $rs= $this->db_icmis->query($sql_sec);
        //
        foreach ($rs->result_array() as $r)
        {
            // $r[section];
        }*/
        // echo $r[section];

        //var_dump($r); exit(0);

        if (($app_type == '') || ($app_type == 1)) {
            $con = '';
        }
        if ($app_type == 2) {
            $con = "and app_flag='Filing'";
        }
        if ($app_type == 3) {
            $con = "and app_flag='Add Doc'";
        }
        if ($app_type == 4) {
            $con = "and app_flag='Deficit'";
        }
        if ($app_type == 5) {
            $con = "and app_flag='Deficit_DN'";
        }
        if ($app_type == 6) {
            $con = "and app_flag='Add Doc Sp'";
        }
        if ($app_type == 7) {
            $con = "and app_flag='Refiling'";
        }

        if (($action_type) == 'a') {
            $con_action = " and action_update in ('Y','N')";
        }
        if ($action_type == 'Y') {
            $con_action =  " and action_update = 'Y'";
        }

        if ($action_type == 'N') {
            $con_action =  " and action_update = 'N'";
        }

        if (($r['section'] == "19") || ($r['section'] == "71") || ($r['section'] == "30"))  // for section IB users  and for super user
        {
            // echo " for section ib";
            $sql = "select  t.scheduler_datetime, t.transaction_id, t.amount, t.udf4, t.udf5, t.action_update, t.transaction_datetime, t.endpoint_transaction_id, t.app_flag, t.diary_no as d_no, pet_name, res_name, ack_id, TO_CHAR(ack_rec_dt, 'YYYY') as ack_year, org_diary_no, (select casename from e_filing.casetype where casecode=casetype_id)casename,name as adv_name,mobile,email
                from e_filing.efiling_transaction_records t
                left join e_filing.main m on  m.diary_no = t.diary_no
                               left join e_filing.bar on m.pet_adv_id=bar.bar_id
                where status_id=$status_flag and t.diary_no!=0  and (t.transaction_id is not null and trim(t.transaction_id)!='')
                and date(t.transaction_datetime) between '$fromDate' and '$toDate' and (app_flag != 'Deficit_DN' and app_flag != 'Add Doc' and  app_flag != 'Add Doc Sp') $con $con_action
                union
                select  t.scheduler_datetime, t.transaction_id, t.amount, t.udf4, t.udf5, t.action_update, t.transaction_datetime, t.endpoint_transaction_id, t.app_flag, t.diary_no as d_no, pet_name, res_name, ack_id, ack_rec_dt as ack_year, t.diary_no as org_diary_no, (select casename from e_filing.casetype where casecode=casetype_id)casename,name as adv_name,mobile,email
                from e_filing.efiling_transaction_records t

                    left join (select pet_name, res_name, diary_no,pet_adv_id,ack_id,ack_rec_dt,casetype_id from public.main m1 
                                union 
                                select pet_name, res_name, diary_no,pet_adv_id,ack_id,ack_rec_dt,casetype_id from public.main_a m2 )m on m.diary_no = t.diary_no
                                left join master.bar on m.pet_adv_id=bar.bar_id
                where status_id=$status_flag and t.diary_no!=0  and (t.transaction_id is not null and trim(t.transaction_id)!='')
                and date(t.transaction_datetime) between '$fromDate' and '$toDate' and (app_flag = 'Deficit_DN' or app_flag = 'Add Doc' or  app_flag = 'Add Doc Sp') $con $con_action
                union
				select 
				null as scheduler_datetime, i.transaction_id, '0' as amount, '0' as udf4, '0' as udf5, '' as action_update, i.entdt as transaction_datetime, '' as endpoint_transaction_id,
				i.source_flag as app_flag,
				m.diary_no as d_no, 
				pet_name, 
				res_name, 
				ack_id, 
				TO_CHAR(ack_rec_dt, 'YYYY') as ack_year, 
				org_diary_no, 
				(select casename from e_filing.casetype where casecode=casetype_id)casename,name as adv_name,mobile,email
				from e_filing.main m 
				join e_filing.indexing i on i.diary_no = m.diary_no 
				left join e_filing.bar on m.pet_adv_id=bar.bar_id
				where i.file_status=1 and (i.transaction_id is not null and trim(i.transaction_id)!='')
				and date(i.entdt) between '$fromDate' and '$toDate' 
				and i.transaction_id = 'exempted'
				union 
				select 
				null as scheduler_datetime, ad.transaction_id, '0' as amount, '0' as udf4, '0' as udf5, '' as action_update, ad.entdt as transaction_datetime, '' as endpoint_transaction_id,
				'Add Doc' as app_flag,
				'0' as d_no, 
				pet_name, 
				res_name, 
				ack_id, 
				ack_rec_dt as ack_year, 
				m.diary_no as org_diary_no, 
				(select casename from e_filing.casetype where casecode=casetype_id)casename,name as adv_name,mobile,email 
				from e_filing.additional_docs ad
				 
				    left join (select pet_name, res_name, diary_no,pet_adv_id,ack_id,ack_rec_dt,casetype_id from public.main m1 
                                union 
                                select pet_name, res_name, diary_no,pet_adv_id,ack_id,ack_rec_dt,casetype_id from public.main_a m2 )m on m.diary_no = ad.diary_no
				    
				 left join e_filing.bar on m.pet_adv_id=bar.bar_id
				where ad.display='Y' and (ad.transaction_id is not null and trim(ad.transaction_id)!='')
				and date(ad.entdt) between '$fromDate' and '$toDate' 
				and ad.transaction_id = 'exempted'
				order by action_update desc,transaction_datetime desc,scheduler_datetime desc limit 4000";

            $query = $this->db->query($sql);
            $result = $query->getResultArray();
            return $result;
        } else  // for section users only for bo
        {
            $x = array();
            $section = $r['section'];
            $sql = "select  t.scheduler_datetime, t.transaction_id, t.amount, t.udf4, t.udf5, t.action_update, t.transaction_datetime, t.endpoint_transaction_id, t.app_flag, t.diary_no as d_no, pet_name, res_name, ack_id, TO_CHAR(ack_rec_dt, 'YYYY') as ack_year, org_diary_no, (select casename from e_filing.casetype where casecode=casetype_id)casename,name as adv_name,mobile,email, (select section_id from public.main$is_archival_table x where x.diary_no=m.org_diary_no limit 1) section_id
         from e_filing.efiling_transaction_records t
         left join e_filing.main m on  m.diary_no = t.diary_no
         left join e_filing.bar on m.pet_adv_id=bar.bar_id

         where status_id=$status_flag and t.diary_no!=0  and (t.transaction_id is not null and trim(t.transaction_id)!='')
          and date(t.transaction_datetime)  between '$fromDate' and '$toDate' and (app_flag != 'Deficit_DN' and app_flag != 'Add Doc' and  app_flag != 'Add Doc Sp') $con $con_action
         union
         select t.scheduler_datetime, t.transaction_id, t.amount, t.udf4, t.udf5, t.action_update, t.transaction_datetime, t.endpoint_transaction_id, t.app_flag, t.diary_no as d_no, pet_name, res_name, ack_id, ack_rec_dt as ack_year, t.diary_no as org_diary_no, (select casename from e_filing.casetype where casecode=casetype_id)casename,name as adv_name,mobile,email,section_id
         from e_filing.efiling_transaction_records t
           left join (select pet_name, res_name, diary_no,pet_adv_id,ack_id,ack_rec_dt,casetype_id from public.main m1 
                                union 
                                select pet_name, res_name, diary_no,pet_adv_id,ack_id,ack_rec_dt,casetype_id from public.main_a m2 )m on m.diary_no = t.diary_no
           left join master.bar on m.pet_adv_id=bar.bar_id
         where status_id=$status_flag and t.diary_no!=0   and (t.transaction_id is not null and trim(t.transaction_id)!='')
          and section_id= $section and date(t.transaction_datetime)  between '$fromDate' and '$toDate' and (app_flag = 'Deficit_DN' or app_flag = 'Add Doc' or  app_flag = 'Add Doc Sp') $con $con_action
	union
	select  null as scheduler_datetime, i.transaction_id, '0' as amount, '0' as udf4, '0' as udf5, '' as action_update, i.entdt as transaction_datetime, '' as endpoint_transaction_id,
				i.source_flag as app_flag,
				m.diary_no as d_no, 
				pet_name, 
				res_name, 
				ack_id, 
				TO_CHAR(ack_rec_dt, 'YYYY') as ack_year, 
				org_diary_no, 
				(select casename from e_filing.casetype where casecode=casetype_id)casename,name as adv_name,mobile,email, (select section_id from public.main x where x.diary_no=m.org_diary_no limit 1) section_id
				from e_filing.main m 
				join e_filing.indexing i on i.diary_no = m.diary_no 
				left join e_filing.bar on m.pet_adv_id=bar.bar_id
				where i.file_status=1 and (i.transaction_id is not null and trim(i.transaction_id)!='')
				and date(i.entdt) between '$fromDate' and '$toDate' 
				and i.transaction_id = 'exempted'
union 
				select 
				null as scheduler_datetime, ad.transaction_id, '0' as amount, '0' as udf4, '0' as udf5, '' as action_update, ad.entdt as transaction_datetime, '' as endpoint_transaction_id,
				'Add Doc' as app_flag,
				'0' as d_no, 
				pet_name, 
				res_name, 
				ack_id, 
				ack_rec_dt as ack_year, 
				m.diary_no as org_diary_no, 
				(select casename from e_filing.casetype where casecode=casetype_id)casename,name as adv_name,mobile,email, m.section_id
				from e_filing.additional_docs ad
				left join (select pet_name, res_name, diary_no,pet_adv_id,ack_id,ack_rec_dt,casetype_id from public.main m1 
                                union 
                                select pet_name, res_name, diary_no,pet_adv_id,ack_id,ack_rec_dt,casetype_id from public.main_a m2 )m on m.diary_no = ad.diary_no
				left join e_filing.bar on m.pet_adv_id=bar.bar_id
				where ad.display='Y' and (ad.transaction_id is not null and trim(ad.transaction_id)!='')
				and date(ad.entdt) between '$fromDate' and '$toDate' 
				and ad.transaction_id = 'exempted'

                 order by action_update desc,transaction_datetime desc,scheduler_datetime limit 4000";
            $query = $this->db->query($sql);
            $result = $query->getResultArray();
            foreach ($result as $res) {
                if (is_null($res['section_id'])) {
                    // none to be done
                }
                if ($res['section_id'] == $r['section']) {
                    array_push($x, $res);
                }
            }
            return $x;
        }
    }

    public function get_efiling_application($fromDate, $toDate, $report_type)
    {
        if ($report_type == 1) {
            $sql = "SELECT 
                    m.diary_no, 
                    ack_id, 
                    ack_rec_dt AS ack_year, 
                    ack_rec_dt AS ack_rec_dt, 
                    pet_name, 
                    res_name, 
                    case_grp, 
                    --cnt_status, 
                    status_id, 
                    amount, 
                    transaction_id, 
                    --m.org_diary_no, -- Corrected column reference
                    reason, 
                    court_fee, 
                    (SELECT SUM(np) FROM indexing WHERE diary_no = a.diary_no AND display = 'Y') AS pages, 
                    casename, 
                    app_source, 
                    TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') AS active_fil_dt
                FROM 
                    main m
                LEFT JOIN (
                    SELECT 
                        diary_no, 
                        status_id, 
                        STRING_AGG(transaction_id, ', ') AS transaction_id, 
                        SUM(amount) AS amount, 
                        STRING_AGG(TO_CHAR(transaction_datetime, 'YYYY-MM-DD HH24:MI:SS'), ', ') AS transaction_datetime, 
                        STRING_AGG(CONCAT(amount, ' (', app_flag, ')'), ', ') AS app_source, 
                        STRING_AGG(endpoint_transaction_id, ', ') AS reason
                    FROM 
                        e_filing.efiling_transaction_records
                    WHERE 
                        status_id = 1 AND 
                        diary_no != 0
                    GROUP BY 
                        diary_no, 
                        status_id 
                ) a ON a.diary_no = m.diary_no
                INNER JOIN 
                    master.casetype c ON casecode = casetype_id
                WHERE 
                    ack_id != 0 AND ack_rec_dt >= :fromDate: AND ack_rec_dt <= :toDate:
                ORDER BY 
                    ack_id";
        } else if ($report_type == 2) {
            
            $sql = "SELECT DISTINCT 
                a.diary_no, 
                ack_id, 
                ack_rec_dt AS ack_year, 
                ack_rec_dt AS ack_rec_dt, 
                pet_name, 
                res_name, 
                case_grp, 
                --cnt_status, 
                status_id, 
                SUM(amount) AS amount, 
                STRING_AGG(DISTINCT transaction_id, ', ') AS transaction_id, 
                --org_diary_no, 
                STRING_AGG(DISTINCT fail_reason, ', ') AS reason, 
                court_fee, 
                (SELECT SUM(np) FROM indexing WHERE diary_no = a.diary_no AND display = 'Y') AS pages, 
                c.casename, 
                STRING_AGG(DISTINCT CONCAT(amount, ' (', app_flag, ')'), ', ') AS app_source, 
                TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') AS active_fil_dt
            FROM 
                main a
            INNER JOIN 
                e_filing.efiling_transaction_records b ON a.diary_no = b.diary_no
            INNER JOIN 
                master.casetype c ON casecode = casetype_id
            WHERE 
                b.diary_no IS NOT NULL 
                AND status_id = 0 
                AND transaction_id != '' AND
                ack_rec_dt >= :fromDate: AND ack_rec_dt <= :toDate:
            GROUP BY 
                a.diary_no, 
                ack_id, 
                ack_rec_dt, 
                pet_name, 
                res_name, 
                case_grp, 
                --cnt_status, 
                status_id, 
                --org_diary_no, 
                casename
            ORDER BY 
                ack_id";
        }

        // Execute the query with parameter binding
        $query = $this->db->query($sql, [
            'fromDate' => date('Y', strtotime($fromDate)),
            'toDate' => date('Y', strtotime($toDate))
        ]);

        return $query->getResultArray();
    }

    public function get_efiling_category($id, $year)
    {
        $sql = "SELECT * 
            FROM e_filing.submaster 
            WHERE id IN (
                SELECT submaster_id 
                FROM e_filing.mul_category 
                WHERE diary_no = (
                    SELECT diary_no 
                    FROM e_filing.main 
                    WHERE ack_id = :id: 
                    AND YEAR(ack_rec_dt) = :year:
                )
            )";

        $query = $this->db->query($sql, [
            'id' => $id,
            'year' => $year
        ]);

        return $query->getResultArray();
    }

    public function get_efiling_parties($id, $year)
    {
        $sql = "SELECT * 
            FROM e_filing.users 
            WHERE id = (
                SELECT MAX(usercode) 
                FROM e_filing.party 
                WHERE diary_no = (
                    SELECT diary_no 
                    FROM e_filing.main 
                    WHERE ack_id = :id: 
                    AND YEAR(ack_rec_dt) = :year:
                ) 
                AND sr_no = 1 
                AND pet_res = 'P'
                 
            )
            ";

        $query = $this->db->query($sql, [
            'id' => $id,
            'year' => $year
        ]);

        return $query->getResultArray();
    }

    public function t_main_icmis($ack_no)
    {
        $builder = $this->db->table('main');

        $query = $builder->where('ack_id', $ack_no)->get();

        return $query->getResultArray();
    }

    public function get_next_diary_no()
    {
        $builder = $this->db->table('cnt_diary_no');
        $query = $builder->select("max_diary_no + 1 AS max_diary_no")
            ->where('diary_no_year', date('Y'))
            ->get();

        return $query->getRow()->max_diary_no;
    }

    public function insert_into_table($table, $data)
    {
        $builder = $this->db->table($table);
        $builder->insert($data);
        return $this->db->insertID();
    }

    public function update_diary_no_counter()
    {
        $builder = $this->db->table('cnt_diary_no');
        $builder->set('max_diary_no', 'max_diary_no + 1', false)
            ->where('diary_no_year', date('Y'))
            ->update();
    }

    public function t_rows($table, $diary_no)
    {
        $builder = $this->db->table($table);
        $query = $builder->where('diary_no', $diary_no)->get();
        return $query->getResultArray();
    }

    public function t_rows_icmis($table, $diary_no)
    {
        $builder = $this->db->table($table);
        $query = $builder->where('diary_no', $diary_no)->get();
        return $query->getResultArray();
    }

    public function batch_insert_into_table($table, $data)
    {
        $builder = $this->db->table($table);
        $builder->insertBatch($data);
    }


    /* fucntion for refiling report */

    public function get_refiling_documents($fromDate, $toDate, $status_flag,$app_type,$session_r){



        $sql_sec="select * from master.users where usercode =".$session_r;
        $rs= $this->db->query($sql_sec);
        $r= $rs->getRowArray();
         
        $empcode=$r['empid'];
            // echo " the section is ".$r[section];

        

        // echo "the employee code is ".$empcode;
        //  exit();
        $con='';
        if(($app_type=='') || ($app_type==0))
        {
            $con='';
        }
        if($app_type ==1)
        {
            $con="and app_flag='Refiling'";
        }
        if($app_type ==2)
        {
            $con="and app_flag='Add Doc'";
        }

        // echo " the condition is ".$con;

        //  if(($r[section]==19)||($r[section]==71) || ($r[section]==30) )  // for section IB users  and for super user
        if(($r['section']==30) || $r['section']==54)  // for computer cell users  and for super user  // to view the entire report
        {


            $sql = "select  t.scheduler_datetime, t.transaction_id, t.amount, t.udf4, t.udf5, t.action_update, t.transaction_datetime, t.endpoint_transaction_id, t.app_flag, t.diary_no as d_no, pet_name, res_name, ack_id, TO_CHAR(ack_rec_dt::DATE, 'YYYY') as ack_year, t.diary_no as org_diary_no, (select casename from master.casetype where casecode=casetype_id)casename,name as adv_name,mobile,email
                from efiling_transaction_records t
                left join main m on  m.diary_no = t.diary_no
                               left join master.bar on m.pet_adv_id=bar.bar_id
                where status_id=$status_flag and t.diary_no!=0  and date(t.transaction_datetime) between '$fromDate' and '$toDate' AND m.diary_no IS NOT NULL and (app_flag != 'Deficit_DN' and app_flag != 'Add Doc' and  app_flag != 'Add Doc Sp') $con
                union
                select t.scheduler_datetime, t.transaction_id, t.amount, t.udf4, t.udf5, t.action_update, t.transaction_datetime, t.endpoint_transaction_id, t.app_flag, t.diary_no as d_no, pet_name, res_name, ack_id, TO_CHAR(ack_rec_dt::DATE, 'YYYY') as ack_year, t.diary_no as org_diary_no, (select casename from master.casetype where casecode=casetype_id)casename,name as adv_name,mobile,email
                from efiling_transaction_records t

                                left join main m on  m.diary_no = t.diary_no
                                left join master.bar on m.pet_adv_id=bar.bar_id
                where status_id=$status_flag and t.diary_no!=0  and date(t.transaction_datetime) between '$fromDate' and '$toDate' AND m.diary_no IS NOT NULL and (app_flag = 'Deficit_DN' or app_flag = 'Add Doc' or  app_flag = 'Add Doc Sp') $con and (m.active_fil_no is  null or m.active_fil_no= '')
		    union
				select 
				null as scheduler_datetime, i.transaction_id, '0' as amount, '0' as udf4, '0' as udf5, '' as action_update, i.entdt as transaction_datetime, '' as endpoint_transaction_id,
				i.source_flag as app_flag,
				m.diary_no as d_no, 
				pet_name, 
				res_name, 
				ack_id, 
				TO_CHAR(ack_rec_dt::DATE, 'YYYY') as ack_year, 
				org_diary_no, 
				(select casename from master.casetype where casecode=casetype_id)casename,name as adv_name,mobile,email
				from e_filing.main m 
				join e_filing.indexing i on i.diary_no = m.diary_no 
				left join master.bar on m.pet_adv_id=bar.bar_id
				where (i.transaction_id is not null and trim(i.transaction_id)!='')
				and date(i.entdt) between '$fromDate' and '$toDate'  AND m.diary_no IS NOT NULL
				and i.transaction_id = 'exempted' and i.source_flag='R'
				union 
				select 
				null as scheduler_datetime, ad.transaction_id, '0' as amount, '0' as udf4, '0' as udf5, '' as action_update, ad.entdt as transaction_datetime, '' as endpoint_transaction_id,
				'Add Doc' as app_flag,
				m.diary_no as d_no, 
				pet_name, 
				res_name, 
				ack_id, 
				ack_rec_dt as ack_year, 
				m.diary_no as org_diary_no, 
				(select casename from master.casetype where casecode=casetype_id)casename,name as adv_name,mobile,email 
				from e_filing.additional_docs ad
				left join main m on ad.diary_no = m.diary_no 
				left join master.bar on m.pet_adv_id=bar.bar_id
				where ad.display='Y' and (ad.transaction_id is not null and trim(ad.transaction_id)!='')
				and date(ad.entdt) between '$fromDate' and '$toDate'  AND m.diary_no IS NOT NULL
				and ad.transaction_id = 'exempted'

                order by transaction_datetime desc";

            $query = $this->db->query($sql);
            // var_dump($query->result_array());
            return $query->getResultArray();

        }
        else  // dealing assistant wise
        {
            //  echo  " Dealing assistant wise";

            $x[]='';
              $sql = "select t.scheduler_datetime, t.transaction_id, t.amount, t.udf4, t.udf5, t.action_update, t.transaction_datetime, t.endpoint_transaction_id, t.app_flag, t.diary_no as d_no, pet_name, res_name, ack_id, ack_rec_dt as ack_year,t.diary_no as org_diary_no, (select casename from master.casetype where casecode=casetype_id)casename,name as adv_name,mobile,email
                from e_filing.efiling_transaction_records t
                left join main m on  m.diary_no = t.diary_no
                               left join master.bar on m.pet_adv_id=bar.bar_id
                where status_id=$status_flag and t.diary_no!=0  and date(t.transaction_datetime) between '$fromDate' and '$toDate' AND m.diary_no IS NOT NULL and (app_flag != 'Deficit_DN' and app_flag != 'Add Doc' and  app_flag != 'Add Doc Sp') $con
                union
                select t.scheduler_datetime, t.transaction_id, t.amount, t.udf4, t.udf5, t.action_update, t.transaction_datetime, t.endpoint_transaction_id, t.app_flag, t.diary_no as d_no, pet_name, res_name, ack_id, ack_rec_dt as ack_year, t.diary_no as org_diary_no, (select casename from master.casetype where casecode=casetype_id)casename,name as adv_name,mobile,email
                from e_filing.efiling_transaction_records t

                                left join main m on  m.diary_no = t.diary_no
                                left join master.bar on m.pet_adv_id=bar.bar_id
                where status_id=$status_flag and t.diary_no!=0  and date(t.transaction_datetime) between '$fromDate' and '$toDate' AND m.diary_no IS NOT NULL and (app_flag = 'Deficit_DN' or app_flag = 'Add Doc' or  app_flag = 'Add Doc Sp') $con and (m.active_fil_no is  null or m.active_fil_no= '')
                union
				select 
				null as scheduler_datetime, i.transaction_id, '0' as amount, '0' as udf4, '0' as udf5, '' as action_update, i.entdt as transaction_datetime, '' as endpoint_transaction_id,
				i.source_flag as app_flag,
				m.diary_no  as d_no, 
				pet_name, 
				res_name, 
				ack_id, 
				TO_CHAR(ack_rec_dt::DATE, 'YYYY') as ack_year,
				org_diary_no, 
				(select casename from master.casetype where casecode=casetype_id)casename,name as adv_name,mobile,email
				from e_filing.main m 
				join e_filing.indexing i on i.diary_no = m.diary_no 
				left join master.bar on m.pet_adv_id=bar.bar_id
				where (i.transaction_id is not null and trim(i.transaction_id)!='')
				and date(i.entdt) between '$fromDate' and '$toDate'  AND m.diary_no IS NOT NULL
				and i.transaction_id = 'exempted' and i.source_flag='R'
				union 
				select 
				null as scheduler_datetime, ad.transaction_id, '0' as amount, '0' as udf4, '0' as udf5, '' as action_update, ad.entdt as transaction_datetime, '' as endpoint_transaction_id,
				'Add Doc' as app_flag,
				m.diary_no as d_no, 
				pet_name, 
				res_name, 
				ack_id, 
				ack_rec_dt as ack_year, 
				m.diary_no as org_diary_no, 
				(select casename from master.casetype where casecode=casetype_id)casename,name as adv_name,mobile,email 
				from e_filing.additional_docs ad
				left join main m on ad.diary_no = m.diary_no 
				left join master.bar on m.pet_adv_id=bar.bar_id
				where ad.display='Y' and (ad.transaction_id is not null and trim(ad.transaction_id)!='')
				and date(ad.entdt) between '$fromDate' and '$toDate' AND m.diary_no IS NOT NULL
				and ad.transaction_id = 'exempted'	order by transaction_datetime desc";
 
            $query = $this->db->query($sql);

            return $query->getResultArray();

            foreach ($query->getResultArray() as $res)
            {
                

                $check_scrutiny_user="select remarks,r_by_empid from fil_trap where diary_no=$res[org_diary_no] and remarks = 'DE -> SCR'
                  union
                  select remarks,r_by_empid from fil_trap_his where diary_no=$res[org_diary_no] and remarks = 'DE -> SCR'";
                $rs_scr_user= $this->db->query($check_scrutiny_user);

                foreach ($rs_scr_user->getResultArray() as $rs_scr)
                {
                    if($rs_scr['r_by_empid']==$empcode)
                        array_push($x,$res);
                }

            }

        }
        return $x;
    }


    public function docmaster(){
        $builder = $this->db->table('e_filing.docmaster');
        $builder->distinct();
        $builder->select('doccode, doccode1, docdesc')
        ->where('display','Y')->groupBy('doccode, doccode1, docdesc');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function set_actual_diary_no($diary_no, $diary_yr, $ack, $ack_year, $efile_diary){
        $org_diary_no = $diary_no.$diary_yr;
        $sql = "update e_filing.main set org_diary_no='$org_diary_no' where ack_id='$ack' and year(ack_rec_dt)=$ack_year and diary_no=$efile_diary";
        $this->db->query($sql);
        
        $update_sci_main="update main set ack_id ='$ack' , ack_rec_dt='$ack_year' where diary_no='$org_diary_no'";
        $this->db->query($update_sci_main) or die("Error in updation of acknowledgement id in icmis");

    }
    
}
