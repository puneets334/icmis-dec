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
        $builder = $this->db->table('main m')
            ->select("m.diary_no, 
                m.conn_key, 
                m.reg_no_display, 
                m.pet_name, 
                m.res_name, 
                m.active_casetype_id, 
                m.casetype_id, 
                ct.nature,
                (SELECT id FROM master.usersection WHERE section_name = tentative_section(m.diary_no) AND display = 'Y') AS section_id,
                m.c_status, 
                fc.id AS faster_cases_id, 
                fc.last_step_id
            ", false) // false to prevent escaping
            ->join('master.casetype ct', 'm.casetype_id = ct.casecode')
            ->where('m.diary_no', $diaryNo);

        if ($ifCompleted) {
            $builder->join('faster_cases fc', 'm.diary_no = fc.diary_no AND fc.last_step_id = ' . COMPLETE, 'left');
        } 
        else {
            if (!empty($nextDate)) {
                $formattedDate = date('Y-m-d', strtotime($nextDate)); 
                $builder->join('faster_cases fc', "m.diary_no = fc.diary_no AND fc.next_dt = '" . $formattedDate . "' AND fc.last_step_id != " . COMPLETE, 'left');
            } else {
                $builder->join('faster_cases fc', 'm.diary_no = fc.diary_no AND fc.last_step_id != ' . COMPLETE, 'left');
            }
        }

        $query = $builder->get();

        return $query->getResultArray();
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
            $builder = $this->db->table('ordernet o')
                ->select(['o.orderdate', 'o.pdfname'])
                ->where('o.diary_no', $diaryNo)
                ->where('o.type', $ordertype)
                ->orderBy('o.orderdate', 'DESC');

            // Execute Query
            $query = $builder->get();
            return $query->getResultArray();
        } else if ($docType == DOCUMENT_MEMO_OF_PARTY) {
            $builder = $this->db->table('cause_title c')
                ->select(['c.created_on AS orderdate', 'c.path AS pdfname'])
                ->where('c.diary_no', $diaryNo)
                ->where('c.is_active', 1);

            // Execute Query
            $query = $builder->get();
            return $query->getResultArray();
        } else {
            //diaryyear/diary_number/t.id,"_",t.nt_type,"_",to.del_type
            //select * from tw_tal_del where diary_no=22018 and print=1 and display='Y' order by rec_dt desc ;
            //$this->db->select('concat(rec_dt," PID-",t.process_id) as orderdate,concat(SUBSTR(t.diary_no, - 4),"/",SUBSTR(t.diary_no, 1, LENGTH(t.diary_no) - 4),"/",t.id,"_",t.nt_type,"_",to.del_type,".pdf") as pdfname',false);

            // Initialize Query Builder
            $builder = $this->db->table('tw_tal_del t')
            ->select([
                "CONCAT(t.order_dt, ' PID-', t.process_id) AS orderdate",
                "CONCAT(SUBSTRING(t.diary_no::TEXT FROM LENGTH(t.diary_no::TEXT) - 3), '/', " .
                "SUBSTRING(t.diary_no::TEXT FROM 1 FOR LENGTH(t.diary_no::TEXT) - 4), '/', " .
                "t.id, '_', t.nt_type, '_', tor.del_type, '.pdf') AS pdfname"
            ])
            ->where('t.diary_no', $diaryNo)
            ->where('t.print', 1)
            ->where('t.display', 'Y')
            ->join('tw_o_r tor', 't.id = tor.tw_org_id AND tor.display = \'Y\'')
            ->orderBy('t.order_dt', 'DESC');
            
            // pr($builder->getCompiledSelect());
            // Execute Query
            $query = $builder->get();
            return $query->getResultArray();


        }
    }
    function getNoticeType($caseNature, $sectionId, $caseStatus, $casetypeId)
    {
        $builder = $this->db->table('master.tw_notice t')
            ->select('t.id, t.name')
            ->whereIn('t.display', ['Y', 'Z']);

        $nature = "";
        if ($caseNature == 'C') {
            $nature = 'Y';
        } elseif ($caseNature == 'R') {
            $nature = 'Z';
        }

        if ($casetypeId != 39) {
            $builder->groupStart()
                ->where('t.nature', $caseNature)
                ->orWhere('t.nature', '')
                ->orWhere('t.nature', $nature)
                ->groupEnd();
        }

        $builder->groupStart()
            ->where('t.section', $sectionId)
            ->orWhere('t.section', 0)
            ->groupEnd();

        $builder->groupStart()
            ->where('t.notice_status', $caseStatus)
            ->orWhere('t.notice_status', '')
            ->groupEnd();

        $builder->orderBy('t.name', 'ASC');
        // pr($builder->getCompiledSelect());
        $query = $builder->get();
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
        $builder = $this->db->table($tablename);
        $builder->insertBatch($data);
        return $this->db->affectedRows();
    }

    function updateInDB($tablename, $data, $wherecondition)
    {
        $builder = $this->db->table($tablename);
        $builder->where($wherecondition);
        $builder->update($data);
        return $this->db->affectedRows();
    }

    function fasterCases($diaryNo = "", $nextDate = "", $stage = "")
    {
        $builder = $this->db->table('faster_cases fc');
        $builder->select('*');

        if ($stage == 'P') {
            $builder->where('fc.last_step_id !=', COMPLETE);
        } elseif ($stage == 'C') {
            $builder->where('fc.last_step_id', COMPLETE);
        }

        if (!empty($diaryNo)) {
            $builder->where('fc.diary_no', $diaryNo);
        }

        if (!empty($nextDate)) {
            $builder->where('fc.next_dt', $nextDate);
        }

        $builder->where('fc.is_deleted', 0);
        $query = $builder->get();

        return $query->getResultArray();
    }

    function fasterSharedDocuments($diaryNo = "", $fasterCasesId = "", $noticeId = "", $processId = "", $dated = "", $stage = "")
    {

        $builder = $this->db->table('faster_cases fc')
            ->select([
                'fsdd.*',
                "TO_CHAR(fsdd.dated, 'DD-MM-YYYY') AS document_date",
                "TO_CHAR(fsdd.created_on, 'DD-MM-YYYY HH12:MI:SS AM') AS created_date",
                'fc.diary_no',
                'tn.name'
            ])
            ->join('faster_shared_document_details fsdd', 'fc.id = fsdd.faster_cases_id', 'inner')
            ->join('master.tw_notice tn', 'tn.id = fsdd.tw_notice_id', 'inner')
            ->where('fc.is_deleted', 0)
            ->where('fsdd.is_deleted', 0);

        // Apply Conditions Based on Variables
        if ($stage == 'C') {
            $builder->where('fc.last_step_id', COMPLETE);
        } else {
            $builder->where('fc.last_step_id !=', COMPLETE);
        }

        if (!empty($processId)) {
            $builder->where('fsdd.process_id', $processId);
        }

        if (!empty($diaryNo)) {
            $builder->where('fc.diary_no', $diaryNo);
            if (!empty($dated)) {
                $builder->where('fc.next_dt', $dated);
            }
        }

        if (!empty($fasterCasesId)) {
            $builder->where('fsdd.faster_cases_id', $fasterCasesId);
            if (!empty($dated)) {
                $builder->where('fc.next_dt', $dated);
            }
        }

        if (!empty($noticeId)) {
            $builder->where('fsdd.tw_notice_id', $noticeId);
        }

        // pr($builder->getCompiledSelect());

        // Execute Query
        $query = $builder->get();
        return $query->getResultArray();
    }

    function attachedDocumentById($id)
    {
        $builder = $this->db->table('faster_shared_document_details fsdt');
        $builder->select('fsdt.*, tn.name');
        $builder->join('master.tw_notice tn', 'fsdt.tw_notice_id = tn.id');
        $builder->where('fsdt.id', $id);
        $builder->where('fsdt.is_deleted', 0);
        $query = $builder->get();

        return $query->getResultArray();

    }

    function attachedDocumentByFasterCasesId($id)
    {
        $builder = $this->db->table('faster_cases fc')
            ->select('fsdt.*, tn.name')
            ->where('fc.id', $id)
            ->where('fsdt.is_deleted', 0)
            ->join('faster_shared_document_details fsdt', 'fc.id = fsdt.faster_cases_id')
            ->join('master.tw_notice tn', 'fsdt.tw_notice_id = tn.id');

        // Execute Query
        $query = $builder->get();
        return $query->getResultArray();

    }

    function getUserDetail($usercode)
    {
        // $this->db->select('u.name, u.empid, us.section_name, ut.type_name');
        // $this->db->where('usercode', $usercode);
        // $this->db->where('u.display', 'Y');
        // $this->db->from('users u')->join('usersection us', 'u.section = us.id')->join('usertype ut', 'u.usertype = ut.id');
        // $query = $this->db->get();
        // return $query->result_array();

        $query = $this->db->table('master.users u')
            ->select('u.name, u.empid, us.section_name, ut.type_name')
            ->join('master.usersection us', 'u.section = us.id')
            ->join('master.usertype ut', 'u.usertype = ut.id')
            ->where('u.usercode', $usercode)
            ->where('u.display', 'Y')
            ->get();
        // pr($query->getCompiledSelect());

        return $query->getResultArray();
    }
    
    function transationList($fasterCasesId, $step = NULL)
    {
        $builder = $this->db->table('faster_transactions ft');
        $builder->select([
            'ft.*',
            "TO_CHAR(ft.created_on, 'DD-MM-YYYY HH12:MI:SS AM') AS created_on_formatted",
            'rfs.description',
            "CONCAT(u.name, '(', u.empid, ')') AS userdetail"
        ]);

        $builder->join('master.ref_faster_steps rfs', 'ft.ref_faster_steps_id = rfs.id');
        $builder->join('master.users u', 'ft.created_by = u.usercode', 'left');
        $builder->where('ft.faster_cases_id', $fasterCasesId);

        if (!empty($step)) {
            $builder->where('ft.ref_faster_steps_id', $step);
        }

        $builder->orderBy('ft.created_on', 'DESC');
        $query = $builder->get();
        return $query->getResultArray();

    }

    function getCurrentStage($fasterCasesId)
    {
        $builder = $this->db->table('faster_cases');
        $builder->where('id', $fasterCasesId);
        $query = $builder->get();

        return $query->getResultArray();

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
        $builder = $this->db->table('faster_communication_details fcd');
        $builder->select([
            'fcd.*',
            'sd.id AS stakeholder_details_id',
            'sd.stakeholder_type_id AS stakeholder_type_id',
            'mst.description AS stakeholder_type',
            'jm.jail_name AS jail_name',
            '(SELECT state.Name FROM master.state WHERE state.State_code = s.State_code AND state.District_code = 0 AND state.Village_code = 0) AS state_name',
            '(CASE WHEN s.District_code <> 0 THEN s.Name ELSE \'\' END) AS district_name',
            'highcourt.agency_name AS highcourtname',
            'rac.agency_name AS agency_name',
            'a.authdesc AS designation',
            'sd.nodal_officer_name AS nodal_officer_name'
        ]);

        $builder->join('master.stakeholder_details sd', 'fcd.stakeholder_details_id = sd.id');
        $builder->where('fcd.faster_cases_id', $fasterCasesId);
        $builder->where('fcd.is_deleted', 0);
        $builder->join('master.state s', 's.id_no = sd.cmis_state_id', 'left');
        $builder->join('master.master_stakeholder_type mst', 'sd.stakeholder_type_id = mst.id', 'left');
        $builder->join('master.jail_master jm', 'sd.jail_id = jm.loc_id', 'left');
        $builder->join('master.authority a', 'cast(sd.nodal_officer_designation AS BIGINT) = CAST(a.authcode AS BIGINT)', 'left');
        $builder->join('master.ref_agency_code rac', 'sd.tribunal_id = rac.id', 'left');
        $builder->join('master.ref_agency_code highcourt', 'sd.bench_id = highcourt.id', 'left');
        $builder->orderBy('fcd.created_on', 'DESC');
        $query = $builder->get();

        return $query->getResultArray();
    }

    function casesMarkedForFaster($listingDate)
    {
        $builder = $this->db->table('faster_opted fo');
        $builder->select([
            "TO_CHAR(entry_date, 'DD-MM-YYYY HH24:MI:SS') AS sent_to_faster_time",
            "fo.*",
            "m.reg_no_display",
            "CONCAT(m.pet_name, ' Vs. ', m.res_name) AS causetitle",
            "STRING_AGG(DISTINCT CONCAT(o.type, '$$', o.pdfname), '----') AS rop_pdf",
            "ct.path AS causetitle_pdf",
            "STRING_AGG(DISTINCT CONCAT(
                ttd.name, '$$', 
                SUBSTRING(ttd.diary_no::TEXT FROM LENGTH(ttd.diary_no::TEXT) - 3), '/', 
                SUBSTRING(ttd.diary_no::TEXT FROM 1 FOR LENGTH(ttd.diary_no::TEXT) - 4), '/', 
                ttd.id, '_', ttd.nt_type, '_', tor.del_type, '.pdf'
            ), '----') AS notice_pdf",
            "fc.id AS faster_cases_id",
            "fc.next_dt AS faster_dated",
            "rfs.description AS faster_status",
            "TO_CHAR(ft.created_on, 'DD-MM-YYYY HH24:MI:SS') AS transaction_status_date_time"
        ]);

        // Join statements
        $builder->join('main m', 'fo.diary_no = m.diary_no AND fo.is_active = 1');
        $builder->join('ordernet o', 'fo.diary_no = o.diary_no AND fo.next_dt = o.orderdate AND o.display = \'Y\'', 'left');
        $builder->join('cause_title ct', 'fo.diary_no = ct.diary_no AND ct.is_active = 1', 'left');
        $builder->join('tw_tal_del ttd', 'fo.diary_no = ttd.diary_no AND fo.next_dt = ttd.order_dt AND ttd.display = \'Y\' AND ttd.print = 1', 'left');
        $builder->join('tw_o_r tor', 'ttd.id = tor.tw_org_id AND tor.display = \'Y\'', 'left');
        $builder->join('faster_cases fc', 'fo.diary_no = fc.diary_no AND fo.next_dt = fc.next_dt', 'left');
        $builder->join('master.ref_faster_steps rfs', 'fc.last_step_id = rfs.id', 'left');
        $builder->join('faster_transactions ft', 'ft.faster_cases_id = fc.id', 'left');

        $builder->where('fo.next_dt', $listingDate);

        $builder->groupBy([
            'fo.id', 'fo.diary_no', 'fo.entry_date', 'm.reg_no_display',
            'm.pet_name', 'm.res_name', 'ct.path', 'fc.id', 
            'fc.next_dt', 'rfs.description', 'ft.created_on', 
            'fo.court_no', 'fo.item_number'
        ]);

        $builder->orderBy('fo.court_no');
        $builder->orderBy('fo.item_number');

        $query = $builder->get();
        return $query->getResultArray();

    }

    function getAvailableDocumetsInICMIS($diaryNo, $orderDate)
    {
        $sql = "SELECT 
                diary_no, 
                cause_title_id AS id, 
                path, 
                NULL AS dated, 
                164 AS doctype, 
                'Schedule memo of Party' AS docname 
            FROM cause_title 
            WHERE diary_no = ? AND is_active = 1 

            UNION 

            SELECT 
                diary_no, 
                id, 
                pdfname AS path, 
                orderdate AS dated, 
                CASE 
                    WHEN type = 'O' THEN 162 
                    WHEN type = 'J' THEN 163 
                    WHEN type = 'S' THEN 165 
                END AS doctype, 
                CASE 
                    WHEN type = 'O' THEN 'Record of Proceedings' 
                    WHEN type = 'J' THEN 'Judgment' 
                    WHEN type = 'S' THEN 'Signed Order' 
                END AS docname 
            FROM ordernet 
            WHERE diary_no = ? AND orderdate = ? AND display = 'Y' 

            UNION 

            SELECT 
                t.diary_no, 
                t.id, 
                CONCAT(
                    RIGHT(CAST(t.diary_no AS TEXT), 4), '/', 
                    LEFT(CAST(t.diary_no AS TEXT), LENGTH(CAST(t.diary_no AS TEXT)) - 4), '/', 
                    t.id, '_', t.nt_type, '_', tor.del_type, '.pdf'
                ) AS path, 
                order_dt AS dated, 
                nt_type::int AS doctype, 
                tn.name AS docname 
            FROM tw_tal_del t 
            INNER JOIN tw_o_r tor 
                ON t.id = tor.tw_org_id 
                AND tor.display = 'Y' 
                AND t.print = 1 
                AND t.display = 'Y' 
                AND t.diary_no = ? 
                AND t.order_dt = ? 
            INNER JOIN master.tw_notice tn 
                ON t.nt_type::int = tn.id::int";
            
            $query = $this->db->query($sql, [$diaryNo, $diaryNo, $orderDate, $diaryNo, $orderDate]);

            // echo $this->db->getLastQuery();
            // die;
            return $query->getResultArray();
    }
    
    function getNextCertificateNumber()
    {
        $current_year = date('Y');
        $builder = $this->db->table('digital_certification_details');
        $builder->selectMax('certificate_number');
        $builder->where('certificate_year', $current_year);
        $query = $builder->get();
        $result = $query->getRow();

        return !empty($result->certificate_number) ? $result->certificate_number + 1 : 1;

    }

    public function existDiaryNextDt($table_name, $diary_no, $next_dt)
    {
        $output = false;

        if (!empty($diary_no) && !empty($next_dt) && !empty($table_name)) {
            $query = $this->db->table($table_name)
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
            $builder = $this->db->table('faster_opted fo');
            $builder->select([
                'm.reg_no_display',
                'm.diary_no',
                'CONCAT(m.res_name, \' Vs. \', m.pet_name) AS cause_title',
                "CASE 
                    WHEN fo.board_type = 'J' THEN 'Court' 
                    WHEN fo.board_type = 'R' THEN 'Registrar' 
                    WHEN fo.board_type = 'C' THEN 'Chamber' 
                    ELSE '' 
                END AS board_type",
                "CASE 
                    WHEN fo.mainhead = 'M' THEN 'Miscellaneous' 
                    WHEN fo.mainhead = 'F' THEN 'Regular' 
                    ELSE '' 
                END AS mainhead",
                "CASE 
                    WHEN fo.main_supp_flag = 1 THEN 'Main List' 
                    WHEN fo.main_supp_flag = 2 THEN 'Supplementary List' 
                    ELSE '' 
                END AS main_supp_flag",
                "fo.judges",
                "TO_CHAR(fo.next_dt, 'MM-DD-YYYY') AS next_dt",
                "TO_CHAR(fo.entry_date, 'MM-DD-YYYY') AS entry_date",
                "fo.is_active",
                "fo.user_ip",
                "u.name",
                "fo.court_no"
            ]);

            $builder->join('main m', 'm.diary_no = fo.diary_no', 'inner');
            $builder->join('master.users u', 'fo.user_id = u.usercode', 'inner');
            $builder->where('DATE(fo.next_dt)', $causelistDate);
            $builder->where('fo.is_active', 1);

            if (!empty($courtNo)) {
                $builder->where('fo.court_no', $courtNo);
            }

            $query = $builder->get();
            $output = $query->getResultArray();

        }
        return $output;
    }
    public function getJudgeName($judgeId)
    {
        $output = false;
        if (isset($judgeId) && !empty($judgeId)) {
            $builder = $this->db->table('master.judge');
            $builder->select('abbreviation');
            $builder->where('jcode', $judgeId);
            $query = $builder->get();
            $output = $query->getResultArray();

        }
        return $output;
    }

    function ListedInfo_OLD($courtNo, $causelistDate)
    {
        $builder = $this->db->table('main m');
        $builder->select("
            m.diary_no, 
            m.conn_key, 
            ct.ent_dt, 
            m.reg_no_display, 
            m.res_name, 
            m.pet_name, 
            h.brd_slno, 
            r.courtno, 
            STRING_AGG(j.jname, ', ') AS judge_name, 
            STRING_AGG(j.jcode::TEXT, ', ') AS judges,
            h.next_dt, 
            h.mainhead, 
            h.roster_id, 
            h.main_supp_flag, 
            h.board_type,
            CASE WHEN fo.is_active = 1 THEN 'Added' 
                WHEN fo.is_active = 0 THEN 'Modify' 
                ELSE '' END AS actionStatus,
            CASE WHEN fc.diary_no IS NOT NULL THEN '1' ELSE '0' END AS exist_faster_cases
        ", false);

        $builder->join('heardt h', 'h.diary_no = m.diary_no');
        $builder->join('master.roster r', 'r.id = h.roster_id');
        $builder->join('master.roster_judge rj', 'rj.roster_id = r.id');
        $builder->join('master.judge j', 'j.jcode = rj.judge_id');
        $builder->join('cl_printed cp', 'cp.next_dt = h.next_dt AND cp.roster_id = h.roster_id AND cp.display = \'Y\'', 'left');
        $builder->join('conct ct', 'm.diary_no = ct.diary_no AND ct.list = \'Y\'', 'left');
        $builder->join('faster_opted fo', 'h.diary_no = fo.diary_no AND h.next_dt = fo.next_dt', 'left');
        $builder->join('faster_cases fc', 'h.diary_no = fc.diary_no AND h.next_dt = fc.next_dt', 'left');

        $builder->where('h.next_dt', $causelistDate);
        $builder->where('r.display', 'Y');
        $builder->where('cp.next_dt IS NOT NULL');

        // Handling court number conditions
        if ($courtNo == 21) {
            $builder->where("(r.courtno = $courtNo OR r.courtno = 61)");
        } elseif ($courtNo == 22) {
            $builder->where("(r.courtno = $courtNo OR r.courtno = 62)");
        } else {
            $courtNoVC = $courtNo + 30;
            $builder->where("(r.courtno = $courtNo OR r.courtno = $courtNoVC)");
        }

        $builder->groupBy('m.diary_no');
        $query1 = $builder->getCompiledSelect();


        $builder1 = $this->db->table('main m');

        $builder1->select("
            m.diary_no, 
            m.conn_key, 
            ct.ent_dt, 
            m.reg_no_display, 
            m.res_name, 
            m.pet_name, 
            h.brd_slno, 
            r.courtno, 
            STRING_AGG(j.jname, ', ') AS judge_name, 
            STRING_AGG(j.jcode::TEXT, ', ') AS judges,
            h.next_dt, 
            h.mainhead, 
            h.roster_id, 
            h.main_supp_flag, 
            h.board_type,
            CASE 
                WHEN fo.is_active = 1 THEN 'Added' 
                WHEN fo.is_active = 0 THEN 'Modify' 
                ELSE '' 
            END AS actionStatus,
            CASE 
                WHEN fc.diary_no IS NOT NULL THEN '1' 
                ELSE '0' 
            END AS exist_faster_cases
        ", false);

        $builder1->join('last_heardt h', 'h.diary_no = m.diary_no');
        $builder1->join('master.roster r', 'r.id = h.roster_id');
        $builder1->join('master.roster_judge rj', 'rj.roster_id = r.id');
        $builder1->join('master.judge j', 'j.jcode = rj.judge_id');
        $builder1->join('cl_printed cp', 'cp.next_dt = h.next_dt AND cp.roster_id = h.roster_id AND cp.display = \'Y\'', 'left');
        $builder1->join('conct ct', 'm.diary_no = ct.diary_no AND ct.list = \'Y\'', 'left');
        $builder1->join('faster_opted fo', 'h.diary_no = fo.diary_no AND h.next_dt = fo.next_dt', 'left');
        $builder1->join('faster_cases fc', 'h.diary_no = fc.diary_no AND h.next_dt = fc.next_dt', 'left');

        $builder1->where('h.next_dt', $causelistDate);
        $builder1->where('r.display', 'Y');
        $builder1->where('cp.next_dt IS NOT NULL');

        // Handling court number conditions
        if ($courtNo == 21) {
            $builder1->where("(r.courtno = $courtNo OR r.courtno = 61)");
        } elseif ($courtNo == 22) {
            $builder1->where("(r.courtno = $courtNo OR r.courtno = 62)");
        } else {
            $courtNoVC = $courtNo + 30;
            $builder1->where("(r.courtno = $courtNo OR r.courtno = $courtNoVC)");
        }

        // Additional condition for bench_flag
        $builder1->where("(h.bench_flag IS NULL OR h.bench_flag = '')");

        // Grouping by diary_no
        $builder1->groupBy('m.diary_no');

        // Compiling the query
        $query2 = $builder1->getCompiledSelect();

        $sql = "SELECT * FROM ({$query1} UNION {$query2}) a 
                GROUP BY diary_no 
                ORDER BY 
                    brd_slno, 
                    CASE WHEN conn_key = diary_no THEN '0000-00-00' ELSE '99' END ASC, 
                    COALESCE(ent_dt, '999') ASC, 
                    CAST(RIGHT(diary_no, 4) AS INTEGER) ASC, 
                    CAST(LEFT(diary_no, LENGTH(diary_no) - 4) AS INTEGER) ASC";

        $result = $this->db->query($sql);
        return $result->getResultArray();
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
            $builder = $this->db->table('master.ref_agency_code');
            $builder->select('id, agency_name');
            $builder->where('CAST(agency_or_court AS BIGINT)', 1);
            $builder->where('is_deleted', 'f');
            $builder->where('main_branch', 1);
            $builder->where('id', 14);

            $query = $builder->get();
            $output = $query->getResultArray();

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
