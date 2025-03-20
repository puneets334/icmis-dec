<?php

/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 2/8/21
 * Time: 12:16 PM
 */

namespace App\Models;

use CodeIgniter\Model;

class FasterModel extends Model
{
    function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    function caseDetails($diaryNo, $nextDate = "", $ifCompleted = false)
    {
        $this->db->select('m.diary_no,m.conn_key,m.reg_no_display,m.pet_name,m.res_name,m.active_casetype_id,m.casetype_id,ct.nature,
        (select id from usersection where section_name=tentative_section(m.diary_no) and display=\'Y\') as section_id,m.c_status,fc.id as faster_cases_id,fc.last_step_id', false);
        $this->db->where('m.diary_no', $diaryNo);
        $this->db->from('main m');
        $this->db->join('casetype ct', 'm.casetype_id=ct.casecode');
        if ($ifCompleted) {
            $this->db->join('faster_cases fc', 'm.diary_no=fc.diary_no and fc.last_step_id=' . COMPLETE, 'left');
        } else {
            //if(!empty($nextDate) && 1==2){
            if (!empty($nextDate)) {
                $this->db->join('faster_cases fc', "m.diary_no=fc.diary_no and fc.next_dt='" . $nextDate . "' and fc.last_step_id!=" . COMPLETE, 'left');
            } else {
                $this->db->join('faster_cases fc', 'm.diary_no=fc.diary_no and fc.last_step_id!=' . COMPLETE, 'left');
            }
        }

        $query = $this->db->get();

        //var_dump($query->result_array());
        //   echo $this->db->last_query();
        //  exit;
        return $query->result_array();
    }
    function documentsDates($diaryNo, $docType)
    {
        if (in_array($docType, unserialize(DOCUMENT_EXEMPTED_FROM_SIGNING))) {
            $ordertype = "O";
            if ($docType == DOCUMENT_JUDGMENT) {
                $ordertype = "J";
            } elseif ($docType == DOCUMENT_SIGNED_ORDER) {
                $ordertype = "S";
            }
            $this->db->select('orderdate,pdfname');
            $this->db->where('o.diary_no', $diaryNo);
            $this->db->where('o.type', $ordertype);
            $this->db->order_by('orderdate', 'desc');
            $this->db->from('ordernet o');
            $query = $this->db->get();
            /*echo $this->db->last_query();
            exit;*/
            return $query->result_array();
        } else if ($docType == DOCUMENT_MEMO_OF_PARTY) {
            $this->db->select('created_on as orderdate,path as pdfname');
            $this->db->where('c.diary_no', $diaryNo);
            $this->db->where('c.is_active', 1);
            $this->db->from('cause_title c');
            $query = $this->db->get();
            return $query->result_array();
        } else {
            //diaryyear/diary_number/t.id,"_",t.nt_type,"_",to.del_type
            //select * from tw_tal_del where diary_no=22018 and print=1 and display='Y' order by rec_dt desc ;
            //$this->db->select('concat(rec_dt," PID-",t.process_id) as orderdate,concat(SUBSTR(t.diary_no, - 4),"/",SUBSTR(t.diary_no, 1, LENGTH(t.diary_no) - 4),"/",t.id,"_",t.nt_type,"_",to.del_type,".pdf") as pdfname',false);
            $this->db->select('concat(order_dt," PID-",t.process_id) as orderdate,concat(SUBSTR(t.diary_no, - 4),"/",SUBSTR(t.diary_no, 1, LENGTH(t.diary_no) - 4),"/",t.id,"_",t.nt_type,"_",to.del_type,".pdf") as pdfname', false);
            $this->db->where('t.diary_no', $diaryNo);
            $this->db->where('t.print', 1);
            $this->db->where('t.display', 'Y');
            $this->db->order_by('order_dt', 'desc');
            //$this->db->from('tw_tal_del t');
            $this->db->from('tw_tal_del t');
            $this->db->join('tw_o_r to', 't.id=to.tw_org_id and to.display=\'Y\'');
            $query = $this->db->get();
            //echo $this->db->last_query();
            return $query->getResultArray();
        }
    }
    function getNoticeType($caseNature, $sectionId, $caseStatus, $casetypeId)
    {
        $this->db->select('t.id,t.name');
        $this->db->where_in('t.display', array('Y', 'Z'));
        $nature = "";
        if ($caseNature == 'C') {
            $nature = 'Y';
        } elseif ($caseNature == 'R') {
            $nature = 'Z';
        }
        if ($casetypeId != 39) {
            $this->db->group_start()->where('t.nature', $caseNature)->or_where('t.nature', '')->or_where('t.nature', $nature)->group_end();
        }
        $this->db->group_start()->where('t.section', $sectionId)->or_where('t.section', 0)->group_end();
        $this->db->group_start()->where('t.notice_status', $caseStatus)->or_where('t.notice_status', '')->group_end();
        $this->db->order_by('t.name', 'asc');
        $this->db->from('tw_notice t');
        $query = $this->db->get();
        return $query->getResultArray();
    }
    function insertInDB($tablename, $data)
    {
        $this->db->table($tablename)->insert($data);
        return $this->db->affectedRows();
    }
    function insertInDBwithInsertedId($tablename, $data)
    {
        $this->db->table($tablename)->insert($data);
        $insertId = $this->db->insertID();
        return  $insertId;
    }
    function batchInsertInDB($tablename, $data)
    {
        $this->db->insert_batch($tablename, $data);
        return $this->db->affectedRows();
    }
    function updateInDB($tablename, $data, $wherecondition)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($tablename);
        $builder->where($wherecondition);
        $builder->update($data);
        return $this->db->affectedRows();
    }
    function fasterCases($diaryNo = "", $nextDate = "", $stage = "")
    {
        $this->db->select('*');
        if ($stage == 'P') {
            $this->db->where('fc.last_step_id !=', COMPLETE);
        } elseif ($stage == 'C') {
            $this->db->where('fc.last_step_id', COMPLETE);
        }
        if (!empty($diaryNo)) {
            $this->db->where('fc.diary_no', $diaryNo);
        }
        if (!empty($nextDate)) {
            $this->db->where('fc.next_dt', $nextDate);
        }
        $this->db->where('fc.is_deleted', 0);
        $this->db->from('faster_cases fc');
        $query = $this->db->get();
        return $query->result_array();
    }
    function fasterSharedDocuments($diaryNo = "", $fasterCasesId = "", $noticeId = "", $processId = "", $dated = "", $stage = "")
    {

        $this->db->select('fsdd.*,DATE_FORMAT(fsdd.dated,\'%d-%m-%Y\') as document_date, DATE_FORMAT(fsdd.created_on,\'%d-%m-%Y %h:%i:%s %p\') as created_date,fc.diary_no,tn.name', false);
        //DATE_FORMAT(NOW(),'%d-%m-%Y %H:%i:%s')
        if ($stage == 'C') {
            $this->db->where('fc.last_step_id', COMPLETE);
        } else {
            $this->db->where('fc.last_step_id !=', COMPLETE);
        }
        if (!empty($processId)) {
            $this->db->where('fsdd.process_id', $processId);
        }
        if (!empty($diaryNo)) {
            $this->db->where('fc.diary_no', $diaryNo);
            if (!empty($dated)) {
                $this->db->where('fc.next_dt', $dated);
            }
        }
        if (!empty($fasterCasesId)) {
            $this->db->where('fsdd.faster_cases_id', $fasterCasesId);
            if (!empty($dated)) {
                $this->db->where('fc.next_dt', $dated);
            }
        }
        if (!empty($noticeId)) {
            $this->db->where('fsdd.tw_notice_id', $noticeId);
        }
        /*        if(!empty($dated)){
            $this->db->where('fsdd.dated' , $dated);
        }*/
        $this->db->where('fc.is_deleted', 0)->where('fsdd.is_deleted', 0);
        $this->db->from('faster_cases fc')->join('faster_shared_document_details fsdd', 'fc.id=fsdd.faster_cases_id')->join('tw_notice tn', 'tn.id=fsdd.tw_notice_id');
        $query = $this->db->get();
        //echo $this->db->last_query();
        //exit;
        return $query->result_array();
    }
    function attachedDocumentById($id)
    {
        $this->db->select('fsdt.*,tn.name');
        $this->db->where('fsdt.id', $id);
        $this->db->where('fsdt.is_deleted', 0);
        $this->db->from('faster_shared_document_details fsdt')->join('tw_notice tn', 'fsdt.tw_notice_id=tn.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    function attachedDocumentByFasterCasesId($id)
    {
        $this->db->select('fsdt.*,tn.name');
        $this->db->where('fc.id', $id);
        $this->db->where('fsdt.is_deleted', 0);
        $this->db->from('faster_cases fc')->join('faster_shared_document_details fsdt', 'fc.id=fsdt.faster_cases_id')->join('tw_notice tn', 'fsdt.tw_notice_id=tn.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    function getUserDetail($usercode)
    {
        $this->db->select('u.name, u.empid, us.section_name, ut.type_name');
        $this->db->where('usercode', $usercode);
        $this->db->where('u.display', 'Y');
        $this->db->from('users u')->join('usersection us', 'u.section = us.id')->join('usertype ut', 'u.usertype = ut.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    function transationList($fasterCasesId, $step = NULL)
    {
        if (!empty($step)) {
            $this->db->where('ft.ref_faster_steps_id', $step);
        }
        $this->db->select("ft.*,DATE_FORMAT(ft.created_on, '%d-%m-%Y %h:%i:%s %p') as created_on_formatted,rfs.description,concat(u.name,'(',u.empid,')') as userdetail", false)->where('ft.faster_cases_id', $fasterCasesId);
        $this->db->from('faster_transactions ft')->join('ref_faster_steps rfs', 'ft.ref_faster_steps_id=rfs.id')->join('users u', 'ft.created_by=u.usercode', 'left')->order_by('ft.created_on', 'desc');
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result_array();
    }
    function getCurrentStage($fasterCasesId)
    {
        $this->db->from('faster_cases')->where('id', $fasterCasesId);
        $query = $this->db->get();
        return $query->result_array();
    }
    function recipientDetails($fasterCasesId)
    {
        /* $sql="SELECT
        `sd`.`id` AS `stakeholder_details_id`,
        `sd`.`stakeholder_type_id` AS `stakeholder_type_id`,
        `mst`.`description` AS `stakeholder_type`,
        `jm`.`jail_name` AS `jail_name`,
        (SELECT 
                `state`.`Name`
            FROM
                `state`
            WHERE
                ((`state`.`State_code` = `s`.`State_code`)
                    AND (`state`.`District_code` = 0)
                    AND (`state`.`Village_code` = 0))) AS `state_name`,
        (CASE
            WHEN (`s`.`District_code` <> 0) THEN `s`.`Name`
            ELSE ''
        END) AS `district_name`,
        `highcourt`.`agency_name` AS `highcourtname`,
        `rac`.`agency_name` AS `agency_name`,
        `a`.`authdesc` AS `designation`,
        `sd`.`nodal_officer_name` AS `nodal_officer_name`
    FROM
		faster_communication_details  fcd inner join
        `stakeholder_details` `sd` on fcd.stakeholder_details_id=sd.id
        left JOIN `state` `s` ON `s`.`id_no` = `sd`.`cmis_state_id`
        left JOIN `master_stakeholder_type` `mst` ON `sd`.`stakeholder_type_id` = `mst`.`id`
        LEFT JOIN `jail_master` `jm` ON `sd`.`jail_id` = `jm`.`Loc_Id`
        LEFT JOIN `authority` `a` ON `sd`.`nodal_officer_designation` = `a`.`authcode`
        LEFT JOIN `ref_agency_code` `rac` ON `sd`.`tribunal_id` = `rac`.`id`
        LEFT JOIN `ref_agency_code` `highcourt` ON `sd`.`bench_id` = `highcourt`.`id`  
        where fcd.faster_cases_id=13;";*/
        $this->db->select('fcd.*,`sd`.`id` AS `stakeholder_details_id`,
        `sd`.`stakeholder_type_id` AS `stakeholder_type_id`,
        `mst`.`description` AS `stakeholder_type`,
        `jm`.`jail_name` AS `jail_name`,
        (SELECT 
                `state`.`Name`
            FROM
                `state`
            WHERE
                ((`state`.`State_code` = `s`.`State_code`)
                    AND (`state`.`District_code` = 0)
                    AND (`state`.`Village_code` = 0))) AS `state_name`,
        (CASE
            WHEN (`s`.`District_code` <> 0) THEN `s`.`Name`
            ELSE \'\'
        END) AS `district_name`,
        `highcourt`.`agency_name` AS `highcourtname`,
        `rac`.`agency_name` AS `agency_name`,
        `a`.`authdesc` AS `designation`,
        `sd`.`nodal_officer_name` AS `nodal_officer_name`', false);
        $this->db->from('faster_communication_details fcd')->join('`stakeholder_details` `sd`', 'fcd.stakeholder_details_id=sd.id')->where('fcd.faster_cases_id', $fasterCasesId)->where('fcd.is_deleted', 0);
        $this->db->join('`state` `s`', ' `s`.`id_no` = `sd`.`cmis_state_id`', 'left')->join('`master_stakeholder_type` `mst`', ' `sd`.`stakeholder_type_id` = `mst`.`id`', 'left')->join('`jail_master` `jm` ', '`sd`.`jail_id` = `jm`.`Loc_Id`', 'left');
        $this->db->join('authority` `a`', '`sd`.`nodal_officer_designation` = `a`.`authcode`', 'left')->join('`ref_agency_code` `rac`', '`sd`.`tribunal_id` = `rac`.`id`', 'left')->join('`ref_agency_code` `highcourt`', '`sd`.`bench_id` = `highcourt`.`id`', 'left');
        $this->db->order_by('fcd.created_on', 'DESC');
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result_array();
    }
    function casesMarkedForFaster($listingDate)
    {
        $query = "select date_format(entry_date, '%d-%m-%Y %H:%i:%s') sent_to_faster_time, fo.*,m.reg_no_display,concat(m.pet_name,' Vs. ',m.res_name) as causetitle,group_concat(distinct concat(o.type,'$$',o.pdfname) SEPARATOR '----') as rop_pdf,ct.path as causetitle_pdf,
                group_concat(distinct concat(ttd.name,'$$',concat(SUBSTR(ttd.diary_no, - 4),\"/\",SUBSTR(ttd.diary_no, 1, LENGTH(ttd.diary_no) - 4),\"/\",ttd.id,\"_\",ttd.nt_type,\"_\",tor.del_type,\".pdf\")) SEPARATOR '----') as notice_pdf,
                fc.id as faster_cases_id,fc.next_dt as faster_dated,rfs.description as faster_status,
                date_format(ft.created_on, '%d-%m-%Y %H:%i:%s') as transaction_status_date_time
                from faster_opted fo inner join main m on fo.diary_no=m.diary_no and fo.is_active=1
                left join ordernet o on fo.diary_no=o.diary_no and fo.next_dt=o.orderdate and o.display='Y'
                left join cause_title ct on fo.diary_no=ct.diary_no and ct.is_active=1
                left join tw_tal_del ttd on fo.diary_no=ttd.diary_no and fo.next_dt=ttd.order_dt and ttd.display='Y' and ttd.print=1
                left join tw_o_r tor on ttd.id=tor.tw_org_id and tor.display='Y'
                left join faster_cases fc on fo.diary_no=fc.diary_no and fo.next_dt=fc.next_dt
                left join ref_faster_steps rfs on fc.last_step_id=rfs.id
                left join faster_transactions ft on ft.faster_cases_id = fc.id
                where fo.next_dt=? group by fo.diary_no order by fo.court_no,fo.item_number";
        $query = $this->db->query($query, array($listingDate));
        return $query->result_array();
    }
    function getAvailableDocumetsInICMIS($diaryNo, $orderDate)
    {
        /*select ttd.*,m.reg_no_display,concat(m.pet_name,' Vs. ',m.res_name) as causetitle,group_concat(distinct concat(o.type,'$$',o.pdfname) SEPARATOR '----') as rop_pdf,ct.path as causetitle_pdf,
                group_concat(distinct concat(ttd.nt_type,'$$',ttd.name,'$$',concat(SUBSTR(ttd.diary_no, - 4),"/",SUBSTR(ttd.diary_no, 1, LENGTH(ttd.diary_no) - 4),"/",ttd.id,"_",ttd.nt_type,"_",tor.del_type,".pdf")) SEPARATOR '----') as notice_pdf,
                fc.id as faster_cases_id,fc.next_dt as faster_dated,rfs.description as faster_status
                from main m
                left join ordernet o on m.diary_no=o.diary_no and o.orderdate='2018-01-22' and o.display='Y'
                left join cause_title ct on m.diary_no=ct.diary_no and ct.is_active=1
                left join tw_tal_del_bck ttd on m.diary_no=ttd.diary_no and ttd.order_dt='2018-01-22' and ttd.display='Y' and ttd.print=1
                left join tw_o_r tor on ttd.id=tor.tw_org_id and tor.display='Y'
                left join faster_cases fc on m.diary_no=fc.diary_no and fc.next_dt='2018-01-22'
                left join ref_faster_steps rfs on fc.last_step_id=rfs.id
                where m.diary_no=1232018 group by m.diary_no;*/
        $sql = "select diary_no,cause_title_id as id,path,NULL as dated, 164 as doctype,'Schedule memo of Party' as docname from cause_title 
              where diary_no=$diaryNo and is_active=1
                union 
                select diary_no,id,pdfname as path,orderdate as dated,
                case when type='O' then 162 else case when type='J' then 163 else case when type='S' then 165 end end end as doctype,
                case when type='O' then 'Record of Proceedings' else case when type='J' then 'Judgment' else case when type='S' then 'Signed Order' end end end as docname
                from ordernet where diary_no=$diaryNo and orderdate='$orderDate' and display='Y'
                union 
                select diary_no,t.id,concat(SUBSTR(t.diary_no, - 4),\"/\",SUBSTR(t.diary_no, 1, LENGTH(t.diary_no) - 4),\"/\",t.id,\"_\",t.nt_type,\"_\",tor.del_type,\".pdf\") as path,order_dt as dated,nt_type as doctype,tn.name as docname 
                from tw_tal_del t inner join tw_o_r tor on t.id=tor.tw_org_id and tor.display='Y' and t.print=1 and t.display='Y' and t.diary_no=$diaryNo and t.order_dt='$orderDate'
                inner join tw_notice tn on t.nt_type=tn.id";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    function getNextCertificateNumber()
    {
        $current_year = date('Y');
        $this->db->select_max('certificate_number');
        $this->db->where('certificate_year', $current_year);
        $result = $this->db->get('digital_certification_details')->row();
        if (!empty($result->certificate_number)) {
            return $result->certificate_number + 1;
        } else {
            return 1;
        }
    }
    public function existDiaryNextDt($table_name, $diary_no, $next_dt)
    {
        $output = false;

        if (!empty($diary_no) && !empty($next_dt) && !empty($table_name)) {
            $db = \Config\Database::connect(); // Connect to DB

            $query = $db->table($table_name)
                ->select('diary_no,next_dt')
                ->where('diary_no', $diary_no)
                ->where('CAST(next_dt AS DATE) =', $next_dt) // PGSQL Date Conversion
                ->get();

            $output = $query->getResultArray(); // Fetch Data
        }

        return $output;
    }
    public function getFasterReport($causelistDate, $courtNo)
    {
        $output = false;
        if (isset($causelistDate) && !empty($causelistDate)) {
            $this->db->select('m.reg_no_display,m.diary_no, concat(m.res_name," Vs. ", m.pet_name) as cause_title,
                (case when fo.board_type= "J" then "Court" when fo.board_type= "R" then "Registrar"  when fo.board_type= "C" then "Chamber"   else "" end) as board_type,
                (case when fo.mainhead="M" then "Miscellaneous" when fo.mainhead="F" then "Regular" else "" end	) as mainhead,
                (case when fo.main_supp_flag=1 then "Main List" when fo.main_supp_flag=2 then "Supplementery List" else "" end) as main_supp_flag,
                fo.judges,date_format(fo.next_dt,"%m-%d-%Y") as next_dt,date_format(fo.entry_date,"%m-%d-%Y") as entry_date ,fo.is_active,fo.user_ip,u.name,fo.court_no');
            $this->db->from('faster_opted fo', 'fo.diary_no = m.diary_no');
            $this->db->join('main m', 'm.diary_no = fo.diary_no');
            $this->db->join('users u', 'fo.user_id=u.usercode');
            $this->db->order_by('');
            $this->db->where('date(fo.next_dt)', $causelistDate);
            $this->db->where('is_active', 1);
            if (isset($courtNo) && !empty($courtNo)) {
                $this->db->where('fo.court_no', $courtNo);
            }
            $query = $this->db->get();
            $output = $query->result_array();
        }
        return $output;
    }
    public function getJudgeName($judgeId)
    {
        $output = false;
        if (isset($judgeId) && !empty($judgeId)) {
            $this->db->select('abbreviation');
            $this->db->from('judge');
            $this->db->where('jcode', $judgeId);
            $query = $this->db->get();
            $output = $query->result_array();
        }
        return $output;
    }

    function ListedInfo_OLD($courtNo, $causelistDate)
    {
        $this->db->select('m.diary_no, m.conn_key, ct.ent_dt, m.reg_no_display, m.res_name, m.pet_name, h.brd_slno, r.courtno, group_concat(j.jname) as judge_name,group_concat(j.jcode) as judges,
        h.next_dt,h.mainhead,h.roster_id,h.main_supp_flag,h.board_type,
         (case when (fo.is_active=1)  then \'Added\' when (fo.is_active=0)  then \'Modify\' else \'\' end) as \'actionStatus\',
         (case when fc.diary_no is not null OR fc.diary_no !=0  OR fc.diary_no !=\'\' then \'1\' else 0 end) as \'exist_faster_cases\' ', false);
        $this->db->from('main m');
        $this->db->join('heardt h', 'h.diary_no = m.diary_no');
        $this->db->join('roster r', 'r.id = h.roster_id');
        $this->db->join('roster_judge rj', 'rj.roster_id = r.id');
        $this->db->join('judge j', 'j.jcode = rj.judge_id');
        $this->db->join('cl_printed cp', 'cp.next_dt = h.next_dt and cp.roster_id = h.roster_id and cp.display = "Y"', 'left');
        $this->db->join('conct ct', 'm.diary_no=ct.diary_no and ct.list="Y"', 'left');
        $this->db->join('faster_opted fo', 'h.diary_no=fo.diary_no and h.next_dt= fo.next_dt', 'left');
        $this->db->join('faster_cases fc', 'h.diary_no=fc.diary_no and h.next_dt= fc.next_dt', 'left');

        $this->db->where('h.next_dt', $causelistDate);
        $this->db->where('r.display', 'Y');
        $this->db->where('cp.next_dt is not null');
        if ($courtNo == 21) {
            $where = "(r.courtno =$courtNo or r.courtno = 61)";
        } else if ($courtNo == 22) {
            $where = "(r.courtno =$courtNo or r.courtno = 62)";
        } else {
            $courtNoVC = $courtNo + 30;
            $where = "(r.courtno =$courtNo or r.courtno = $courtNoVC)";
        }
        $this->db->where($where);
        $this->db->group_by('m.diary_no');
        $query1 = $this->db->get_compiled_select();

        $this->db->select('m.diary_no, m.conn_key, ct.ent_dt, m.reg_no_display, m.res_name, m.pet_name, h.brd_slno, r.courtno, group_concat(j.jname) as judge_name,group_concat(j.jcode) as judges,
        h.next_dt,h.mainhead,h.roster_id,h.main_supp_flag,h.board_type,
        (case when (fo.is_active=1)  then \'Added\' when (fo.is_active=0)  then \'Modify\' else \'\' end) as \'actionStatus\',
        (case when fc.diary_no is not null OR fc.diary_no !=0  OR fc.diary_no !=\'\' then \'1\' else 0 end) as \'exist_faster_cases\' ', false);
        $this->db->from('main m');
        $this->db->join('last_heardt h', 'h.diary_no = m.diary_no');
        $this->db->join('roster r', 'r.id = h.roster_id');
        $this->db->join('roster_judge rj', 'rj.roster_id = r.id');
        $this->db->join('judge j', 'j.jcode = rj.judge_id');
        $this->db->join('cl_printed cp', 'cp.next_dt = h.next_dt and cp.roster_id = h.roster_id and cp.display = "Y"', 'left');
        $this->db->join('conct ct', 'm.diary_no=ct.diary_no and ct.list="Y"', 'left');
        $this->db->join('faster_opted fo', 'h.diary_no=fo.diary_no and h.next_dt= fo.next_dt', 'left');
        $this->db->join('faster_cases fc', 'h.diary_no=fc.diary_no and h.next_dt= fc.next_dt', 'left');

        $this->db->where('h.next_dt', $causelistDate);
        $this->db->where('r.display', 'Y');
        $this->db->where('cp.next_dt is not null');
        if ($courtNo == 21) {
            $where = "(r.courtno =$courtNo or r.courtno = 61)";
        } else if ($courtNo == 22) {
            $where = "(r.courtno =$courtNo or r.courtno = 62)";
        } else {
            $courtNoVC = $courtNo + 30;
            $where = "(r.courtno =$courtNo or r.courtno = $courtNoVC)";
        }
        $where2 = "(h.bench_flag is null or h.bench_flag = '')";
        $this->db->where($where);
        $this->db->where($where2);
        $this->db->group_by('m.diary_no');
        //$this->db->order_by('brd_slno, if(conn_key=diary_no,"0000-00-00",99) ASC, if(ent_dt is not null,ent_dt,999) ASC, CAST(SUBSTRING(diary_no, - 4) AS SIGNED) ASC , CAST(LEFT(diary_no, LENGTH(diary_no) - 4) AS SIGNED) ASC');
        $query2 = $this->db->get_compiled_select();
        $result = $this->db->query('select * from (' . $query1 . ' UNION ' . $query2 . ') a GROUP BY diary_no ORDER BY brd_slno, if(conn_key=diary_no,"0000-00-00",99) ASC, if(ent_dt is not null,ent_dt,999) ASC, CAST(SUBSTRING(diary_no, - 4) AS SIGNED) ASC , CAST(LEFT(diary_no, LENGTH(diary_no) - 4) AS SIGNED) ASC');
        //echo $query1.' UNION '.$query2.' GROUP BY diary_no ORDER BY brd_slno, if(conn_key=diary_no,"0000-00-00",99) ASC, if(ent_dt is not null,ent_dt,999) ASC, CAST(SUBSTRING(diary_no, - 4) AS SIGNED) ASC , CAST(LEFT(diary_no, LENGTH(diary_no) - 4) AS SIGNED) ASC';

        return $result->result_array();
    }

    //........... New added 02-09-2024........//
    function ListedInfo($courtNo, $causelistDate)
    {
        $builder1 = $this->db->table('main m');
        // Selecting fields
        $builder1->select("
            m.diary_no, 
            m.conn_key::INTEGER AS conn_key,
            ct.ent_dt, 
            m.reg_no_display, 
            m.res_name, 
            m.pet_name, 
            h.brd_slno, 
            r.courtno, 
            string_agg(j.jname, ', ') AS judge_name, 
            string_agg(j.jcode::text, ', ') AS judges,
            h.next_dt, 
            h.mainhead, 
            h.roster_id, 
            h.main_supp_flag, 
            h.board_type::VARCHAR AS board_type,
            (CASE 
                 WHEN fo.is_active = 1 THEN 'Added'
    WHEN fo.is_active = 0 THEN 'Modify'
    ELSE '' 
            END) AS actionStatus,
            (CASE 
                WHEN fc.diary_no IS NOT NULL AND fc.diary_no != 0 THEN '1'
                ELSE '0'
            END) AS exist_faster_cases
        ", false);

        // Adding Joins
        $builder1->join('heardt h', 'h.diary_no = m.diary_no');
        $builder1->join('master.roster r', 'r.id = h.roster_id');
        $builder1->join('master.roster_judge rj', 'rj.roster_id = r.id');
        $builder1->join('master.judge j', 'j.jcode = rj.judge_id');
        $builder1->join('cl_printed cp', 'cp.next_dt = h.next_dt AND cp.roster_id = h.roster_id AND cp.display = \'Y\'', 'left');
        $builder1->join('conct ct', 'm.diary_no = ct.diary_no AND ct.list = \'Y\'', 'left');
        $builder1->join('faster_opted fo', 'h.diary_no = fo.diary_no AND h.next_dt = fo.next_dt', 'left');
        $builder1->join('faster_cases fc', 'h.diary_no = fc.diary_no AND h.next_dt = fc.next_dt', 'left');

        // Adding where clauses
        $builder1->where('h.next_dt', $causelistDate);
        $builder1->where('r.display', 'Y');
        $builder1->where('cp.next_dt IS NOT NULL');

        // Dynamic court number condition
        if ($courtNo == 21) {
            $where = "(r.courtno = 21 OR r.courtno = 61)";
        } elseif ($courtNo == 22) {
            $where = "(r.courtno = 22 OR r.courtno = 62)";
        } else {
            $courtNoVC = $courtNo + 30;
            $where = "(r.courtno = $courtNo OR r.courtno = $courtNoVC)";
        }
        $builder1->where($where);

        $builder1->groupBy('m.diary_no');
        $builder1->groupBy('ct.ent_dt');
        $builder1->groupBy('h.brd_slno');
        $builder1->groupBy('r.courtno');
        $builder1->groupBy('h.next_dt');
        $builder1->groupBy('h.mainhead');
        $builder1->groupBy('h.roster_id');
        $builder1->groupBy('h.main_supp_flag');
        $builder1->groupBy('h.board_type');
        $builder1->groupBy('fo.is_active');
        $builder1->groupBy('fc.diary_no');

        $query1 = $builder1->getCompiledSelect();


        $builder2 = $this->db->table('main m');
        $builder2->select("
            m.diary_no, 
            m.conn_key::INTEGER AS conn_key,
            ct.ent_dt, 
            m.reg_no_display, 
            m.res_name, 
            m.pet_name, 
            h.brd_slno, 
            r.courtno, 
            string_agg(j.jname, ', ') AS judge_name, 
            string_agg(j.jcode::text, ', ') AS judges,
            h.next_dt, 
            h.mainhead, 
            h.roster_id, 
            h.main_supp_flag, 
            h.board_type::VARCHAR AS board_type,
            (CASE 
                WHEN fo.is_active = 1 THEN 'Added'
    WHEN fo.is_active = 0 THEN 'Modify'
    ELSE ''  
            END) AS actionStatus,
            (CASE 
                WHEN fc.diary_no IS NOT NULL AND fc.diary_no != 0 THEN '1' 
                ELSE '0' 
            END) AS exist_faster_cases
        ", false);

        $builder2->join('last_heardt h', 'h.diary_no = m.diary_no');
        $builder2->join('master.roster r', 'r.id = h.roster_id');
        $builder2->join('master.roster_judge rj', 'rj.roster_id = r.id');
        $builder2->join('master.judge j', 'j.jcode = rj.judge_id');
        $builder2->join('cl_printed cp', 'cp.next_dt = h.next_dt AND cp.roster_id = h.roster_id AND cp.display = \'Y\'', 'left');
        $builder2->join('conct ct', 'm.diary_no = ct.diary_no AND ct.list = \'Y\'', 'left');
        $builder2->join('faster_opted fo', 'h.diary_no = fo.diary_no AND h.next_dt = fo.next_dt', 'left');
        $builder2->join('faster_cases fc', 'h.diary_no = fc.diary_no AND h.next_dt = fc.next_dt', 'left');

        $builder2->where('h.next_dt', $causelistDate);
        $builder2->where('r.display', 'Y');
        $builder2->where('cp.next_dt IS NOT NULL');

        // Court number conditions
        if ($courtNo == 21) {
            $where = "(r.courtno = 21 OR r.courtno = 61)";
        } elseif ($courtNo == 22) {
            $where = "(r.courtno = 22 OR r.courtno = 62)";
        } else {
            $courtNoVC = $courtNo + 30;
            $where = "(r.courtno = $courtNo OR r.courtno = $courtNoVC)";
        }
        $builder2->where($where);
        $where2 = "(h.bench_flag IS NULL OR h.bench_flag = '')";
        $builder2->where($where2);
        $builder2->groupBy('m.diary_no');
        $builder2->groupBy('ct.ent_dt');
        $builder2->groupBy('h.brd_slno');
        $builder2->groupBy('r.courtno');
        $builder2->groupBy('h.next_dt');
        $builder2->groupBy('h.mainhead');
        $builder2->groupBy('h.roster_id');
        $builder2->groupBy('h.main_supp_flag');
        $builder2->groupBy('h.board_type');
        $builder2->groupBy('fo.is_active');
        $builder2->groupBy('fc.diary_no');

        $query2 = $builder2->getCompiledSelect();

        // $result = $this->db->query('select * from ('.$query1.' UNION '.$query2.') a GROUP BY diary_no ORDER BY brd_slno, if(conn_key=diary_no,"0000-00-00",99) ASC, if(ent_dt is not null,ent_dt,999) ASC, CAST(SUBSTRING(diary_no, - 4) AS SIGNED) ASC , CAST(LEFT(diary_no, LENGTH(diary_no) - 4) AS SIGNED) ASC');

        $result = $this->db->query("
            SELECT * 
            FROM (
                $query1 
                UNION 
                $query2
            ) a 
            GROUP BY 
                a.diary_no, 
                a.conn_key,
                a.ent_dt, 
                a.reg_no_display, 
                a.res_name, 
                a.pet_name, 
                a.brd_slno, 
                a.courtno, 
                a.judge_name, 
                a.judges, 
                a.next_dt, 
                a.mainhead, 
                a.roster_id, 
                a.main_supp_flag, 
                a.board_type, 
                a.actionStatus, 
                a.exist_faster_cases 
            ORDER BY 
                a.brd_slno ASC,
                CASE 
                    WHEN a.conn_key = a.diary_no THEN '1900-01-01'::DATE
                    ELSE '9999-12-31'::DATE
                END ASC, 
                CASE 
                    WHEN a.ent_dt IS NOT NULL THEN a.ent_dt 
                    ELSE '9999-12-31'::DATE
                END ASC 
        ");




        // CAST(SUBSTRING(a.diary_no::TEXT FROM -4 FOR 4) AS INTEGER) ASC,
        // CAST(LEFT(a.diary_no::TEXT, LENGTH(a.diary_no::TEXT) - 4) AS INTEGER) ASC

        // echo("query 1 => ".$query1)."<br>";
        // echo("query 2 => ".$query2)."<br>";
        // echo  $query3 = $this->db->getLastQuery();
        // echo("result => ".$query3->getQuery())."<br>";
        // die;

        return $result->getResultArray();
    }

    function orderSendToFaster($orderDate)
    {
        $query = "select fo.*, m.reg_no_display,concat(m.pet_name, ' Vs. ', m.res_name) as causetitle
        from   faster_cases fo
        inner join main m on fo.diary_no = m.diary_no
        left join ordernet o on fo.diary_no = o.diary_no  and fo.next_dt = o.orderdate   and o.display = 'Y'
        left join cause_title ct on   fo.diary_no = ct.diary_no  and ct.is_active = 1
         where fo.next_dt ='" . $orderDate . "'
        order by fo.diary_no";
        //group by  fo.diary_no
        $query = $this->db->query($query);
        return $query->getResultArray();
    }

    public function getHighCourt($data)
    {
        $output = false;
        if (isset($data) && !empty($data)) {
            $this->db->select('id,agency_name');
            $this->db->from('ref_agency_code');
            $this->db->where('agency_or_court', 1);
            $this->db->where('is_deleted', 'f');
            $this->db->where('main_branch', 1);
            $this->db->where('id', 14);
            $query = $this->db->get();
            $output = $query->result_array();
        }
        return $output;
    }
    public function updateSendtoFaster($data)
    {
        $output = false;
        if (isset($data) && !empty($data)) {
            $idval = explode('_', $data['faster_id']);
            if ($idval[0] == 'p') {
                $update_column = "is_sent_to_new_faster=1,sent_to_new_faster_agency='" . $data['highCourtID'] . "',sent_to_new_faster_by='" . $data['session_user'] . "',sent_to_new_faster_on=now() where id=" . $idval[1];
            } else {
                if ($data['buttonID'] == 'delete') {
                    $update_column = "sent_to_new_faster_agency='" . $data['highCourtID'] . "',is_sent_to_new_faster=0,sent_to_new_faster_reverted_by='" . $data['session_user'] . "', sent_to_new_faster_reverted_on=now() where id=" . $idval[1];
                } else {
                    $update_column = "sent_to_new_faster_agency='" . $data['highCourtID'] . "',sent_to_new_faster_reverted_by='" . $data['session_user'] . "', sent_to_new_faster_reverted_on=now() where id=" . $idval[1];
                }
            }
            $sql = "update faster_cases set $update_column ";
            $query = $this->db->query($sql);
            $output = true;
        }
        return $output;
    }
}
