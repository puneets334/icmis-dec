<?php

namespace App\Models\Reports\Copying;

use CodeIgniter\Model;

class ReportModel extends Model
{


    protected $eservices;

    public function __construct()
    {
        parent::__construct();
        $this->eservices = \Config\Database::connect('eservices');
    }

    public function getDesignation($empid)
    {
        $sql = "select usertype from master.users where empid=$empid";
        $query = $this->db->query($sql);
        $desig = $query->getResult();
        return $desig[0]['usertype'];
    }
    function getAORsignature()
    {
        $builder = $this->db->table("master.bar b");
        $builder->select("aor_code, CONCAT(title, ' ', name) AS aor_name,if_sen");
        $builder->where('if_aor', 'Y');
        $builder->limit(5000);
        return  $results = $builder->get()->getResult();
    }
    function getConsumedBarcode($data)
    {

        $builder = $this->db->table('post_bar_code_mapping p');
        $builder->select('p.barcode, c.crn, p.consumed_on');
        $builder->join('copying_order_issuing_application_new c', 'p.copying_application_id = c.id');
        $builder->where('DATE(p.consumed_on) >=', $data['from_date']);
        $builder->where('DATE(p.consumed_on) <=', $data['to_date']);
        $builder->where('p.is_consumed', '1');
        $builder->where('p.is_deleted', '0');
        $builder->orderBy('p.consumed_on');
        return   $result = $builder->get()->getResult();
    }
    function getCopyingRequest($data)
    {

        $builder = $this->db->table('copying_order_issuing_application_new app');
        $builder->select([
            'app.id',
            'app.application_number_display',
            'case when m.diary_no is not null then CONCAT(left(cast(m.diary_no as text),-4),\'/\',right(cast(m.diary_no as text),4)) else CONCAT(left(cast(ma.diary_no as text),-4),\'/\',right(cast(ma.diary_no as text),4)) end as diary_no_display',
            '(case when m.reg_no_display is null then ma.reg_no_display else m.reg_no_display end) as reg_no_display',
            'app.remarks',
            'app.application_receipt',
            '(case when us.section_name is null then us3.section_name else us.section_name end) as section_name',
            '(CURRENT_DATE - application_receipt::date) as diff',
            '(case when m.diary_no is null then case when (tentative_section(ma.diary_no)) is null  then us5.section_name else tentative_section(ma.diary_no) end else case when (tentative_section(m.diary_no)) is null then us2.section_name else tentative_section(m.diary_no) end end) as sec,
	tentative_da(case when m.diary_no is null then ma.diary_no::int else m.diary_no::int end) as tentative_da',
            '(case when m.c_status is null then ma.c_status else m.c_status end) as c_status',
            '(SELECT consignment_date FROM record_keeping rk WHERE display = \'Y\' AND rk.diary_no = app.diary) AS consignment_date',
            '(SELECT MAX(disp_dt) FROM dispose WHERE diary_no = app.diary) AS disposal_dt',
            '(case when u.name is null then u2.name else u.name end) as da',
            'STRING_AGG(CONCAT(rcd.order_type, \' d/t \', TO_CHAR(doc.order_date, \'DD/MM/YYYY\')), \', \') AS docs',
            '(case when u1.name is null then u3.name else u1.name end) AS updatedby',
            '(case when us1.section_name is null then us4.section_name else us1.section_name end) AS updatedbysection'
        ]);

        $builder->join('main m', 'm.diary_no = app.diary', 'LEFT');
        $builder->join('main_a ma', 'ma.diary_no = app.diary', 'LEFT');
        $builder->join('master.users u', 'u.usercode = m.dacode AND u.display = \'Y\'', 'LEFT');
        $builder->join('master.users u1', 'u1.usercode = m.last_usercode AND u1.display = \'Y\'', 'LEFT');
        $builder->join('master.users u2', 'u2.usercode = ma.dacode AND u2.display = \'Y\'', 'LEFT');
        $builder->join('master.users u3', 'u3.usercode = ma.last_usercode AND u3.display = \'Y\'', 'LEFT');
        $builder->join('master.usersection us', 'us.id = u.section', 'LEFT');
        $builder->join('master.usersection us1', 'us1.id = u1.section', 'LEFT');
        $builder->join('master.usersection us2', 'us2.id = m.section_id', 'LEFT');
        $builder->join('master.usersection us3', 'us3.id = u2.section', 'LEFT');
        $builder->join('master.usersection us4', 'us4.id = u3.section', 'LEFT');
        $builder->join('master.usersection us5', 'us5.id = ma.section_id', 'LEFT');
        $builder->join('copying_application_documents doc', 'doc.copying_order_issuing_application_id = app.id', 'LEFT');
        $builder->join('copying_application_documents docs', 'docs.copying_order_issuing_application_id = app.id', 'LEFT');

        $builder->join('master.ref_order_type rcd', 'rcd.id = doc.order_type', 'LEFT');
        $builder->where('app.application_status', 'A');
        if ($data['from_date']) {
            $builder->where('date(app.application_receipt) >=', $data['from_date']);
        }
        if ($data['to_date']) {
            $builder->where('date(app.application_receipt) <=', $data['to_date']);
        }
        if ($data['section'] != 0) {
            $builder->where("(u.section = " . $data['section'] . " OR u2.section = " . $data['section'] . ")");
        }
        if ($data['deliver_mode'] != 0) {
            $builder->where('app.delivery_mode', $data['deliver_mode']);
        }
        if ($data['order_type'] != 0) {
            $builder->where('doc.order_type', $data['order_type']);
        }
        if ($data['case_status'] != 0) {
            if ($data['case_status'] == 'P') {
                $builder->where('m.c_status', $data['case_status']);
            } else {
                $builder->where('ma.c_status', $data['case_status']);
            }
        }
        $builder->groupBy([
            'app.id', 'app.application_number_display', 'm.diary_no', 'ma.diary_no', 'm.reg_no_display', 'ma.reg_no_display',
            'app.remarks', 'app.application_receipt', 'us.section_name', 'us3.section_name', 'sec', 'tentative_da',
            'm.c_status', 'ma.c_status', 'consignment_date', 'disposal_dt', 'da', 'updatedby', 'updatedbysection'
        ]);

        $builder->orderBy('sec,diff');
        /* $builder->get();
        echo $this->db->getLastQuery();
        exit;*/
        return  $result = $builder->get()->getResult();
        //       /* main archive data */
        //        $builder2 = $this->db->table('copying_order_issuing_application_new app');
        //        $builder2->select([
        //            'app.id',
        //            'app.application_number_display',
        //            'CONCAT(LEFT(CAST(m.diary_no AS TEXT), -4), \'/\', RIGHT(CAST(m.diary_no AS TEXT), 4)) AS diary_no_display',
        //            'm.reg_no_display',
        //            'app.remarks',
        //            'app.application_receipt',
        //            'us.section_name',
        //            'COALESCE(m.diary_no::TEXT, us2.section_name::TEXT) AS sec',
        //            'm.diary_no AS tentative_da',
        //            'm.c_status',
        //            '(SELECT consignment_date FROM record_keeping rk WHERE display = \'Y\' AND rk.diary_no = app.diary) AS consignment_date',
        //            '(SELECT MAX(disp_dt) FROM dispose WHERE diary_no = app.diary) AS disposal_dt',
        //            'u.name AS da',
        //            'STRING_AGG(CONCAT(rcd.order_type, \' d/t \', TO_CHAR(doc.order_date, \'DD/MM/YYYY\')), \', \') AS docs',
        //            'u1.name AS updatedby',
        //            'us1.section_name AS updatedbysection'
        //        ]);
        //
        //        $builder2->join('main_a m', 'm.diary_no = app.diary', 'LEFT');
        //        $builder2->join('master.users u', 'u.usercode = m.dacode AND u.display = \'Y\'', 'LEFT');
        //        $builder2->join('master.users u1', 'u1.usercode = m.last_usercode AND u1.display = \'Y\'', 'LEFT');
        //        $builder2->join('master.usersection us', 'us.id = u.section', 'LEFT');
        //        $builder2->join('master.usersection us1', 'us1.id = u1.section', 'LEFT');
        //        $builder2->join('master.usersection us2', 'us2.id = m.section_id', 'LEFT');
        //        $builder2->join('copying_application_documents doc', 'doc.copying_order_issuing_application_id = app.id', 'LEFT');
        //        $builder2->join('master.ref_order_type rcd', 'rcd.id = doc.order_type', 'LEFT');
        //
        //        $builder2->where('app.application_status', 'A');
        //        if($data['from_date']){
        //            $builder2->where('date(app.application_receipt) >=', $data['from_date']);
        //        }
        //        if($data['to_date']){
        //            $builder2->where('date(app.application_receipt) <=', $data['to_date']);
        //        }
        //        if($data['section']!=0){
        //            $builder2->where('u.section',$data['section']);
        //        }
        //        if($data['deliver_mode']!=0){
        //            $builder2->where('app.delivery_mode',$data['deliver_mode']);
        //        }
        //        if($data['order_type']!=0){
        //            $builder2->where('doc.order_type',$data['order_type']);
        //        }
        //        if($data['case_status']!=0){
        //            $builder2->where('c_status',$data['case_status']);
        //        }
        //        $builder2->groupBy([
        //            'app.id', 'app.application_number_display', 'm.diary_no', 'm.reg_no_display',
        //            'app.remarks', 'app.application_receipt', 'us.section_name', 'sec', 'tentative_da',
        //            'm.c_status', 'consignment_date', 'disposal_dt', 'da', 'updatedby', 'updatedbysection'
        //        ]);
        //        $builder2->orderBy('sec');
        //
        //        $builder2 = $builder2->get()->getResult();
        //        return  $result = array_merge($builder, $builder2);



    }

    function getDawise($ucode)
    {

        $builder = $this->db->table('main m');
        $builder->select('rcd.order_type, DATE(order_date) as order_date, copy_category,left((cast(m.diary_no as text)),-4) as dn, 
        right((cast(m.diary_no as text)),4) as dy');
        $builder->join('copying_order_issuing_application_new coian', 'm.diary_no = coian.diary', 'left');
        $builder->join('copying_application_documents cad', 'cad.copying_order_issuing_application_id = coian.id', 'left');
        $builder->join('master.ref_order_type rcd', 'cad.order_type = rcd.id', 'inner');
        $builder->where('m.dacode =', $ucode);
        $builder->where('coian.application_status', 'P');
        $builder->whereIn('case_status_id', [7, 9, 12]);
        $builder = $builder->get()->getResult();
        $builder2 = $this->db->table('main_a m');
        $builder2->select('rcd.order_type, DATE(order_date) as order_date, copy_category, left((cast(m.diary_no as text)),-4) as dn,
         right((cast(m.diary_no as text)),4) as dy');
        $builder2->join('copying_order_issuing_application_new coian', 'm.diary_no = coian.diary', 'left');
        $builder2->join('copying_application_documents cad', 'cad.copying_order_issuing_application_id = coian.id', 'left');
        $builder2->join('master.ref_order_type rcd', 'cad.order_type = rcd.id', 'inner');
        $builder2->where('m.dacode =', $ucode);
        $builder2->where('coian.application_status', 'P');
        $builder2->whereIn('case_status_id', [7, 9, 12]);
        //  echo $this->db->getLastquery();exit;
        $builder2 = $builder2->get()->getResult();
        return  $result = array_merge($builder, $builder2);
    }
    function getEcopyStatus($data)
    {
        $from_date = $data['from_date'];
        $to_date = $data['to_date'];

        $query = $this->db->query("SELECT
        a.id,
        a.description,
        a.code,
        COUNT(b.crn) AS total_appl,
        COALESCE(SUM(b.copying_fee_in_stamp), 0) AS copying_fee_in_stamp,
        COALESCE(SUM(b.copying_service_charges), 0) AS copying_service_charges,
        COALESCE(SUM(b.postage), 0) AS postage,
        SUM(CASE WHEN b.application_status NOT IN ('F', 'R', 'D', 'C', 'W') THEN 1 ELSE 0 END) AS pending,
        SUM(CASE WHEN b.application_status IN ('F', 'R', 'D', 'C', 'W') THEN 1 ELSE 0 END) AS disposed,
        SUM(CASE WHEN b.delivery_mode = '1' THEN 1 ELSE 0 END) AS post_mode,
        SUM(CASE WHEN b.delivery_mode = '2' THEN 1 ELSE 0 END) AS counter_mode
      FROM
        master.copy_category a
      LEFT JOIN
        (SELECT
          a.crn,
          a.application_status,
          a.copy_category,
          a.delivery_mode,
          SUM(CASE WHEN b.payment_type_id = 9527 THEN CAST(b.amount AS NUMERIC) ELSE 0 END) AS copying_fee_in_stamp,
          SUM(CASE WHEN b.payment_type_id = 9528 THEN CAST(b.amount AS NUMERIC) ELSE 0 END) AS copying_service_charges,
          SUM(CASE WHEN b.payment_type_id = 9525 THEN CAST(b.amount AS NUMERIC) ELSE 0 END) AS postage
        FROM
          copying_order_issuing_application_new a
        LEFT JOIN
          dblink('host=10.18.1.35 user=postgres password=postgres dbname=e_services_06_08 port=5432', 
              'SELECT payment_type_id, amount, order_batch_merchant_batch_code FROM bharat_kosh_request_batch') 
          AS b(payment_type_id BIGINT, amount TEXT, order_batch_merchant_batch_code TEXT)
        ON
          b.order_batch_merchant_batch_code = a.crn
        WHERE
          a.source = 6
          AND a.application_receipt BETWEEN '2022-01-11' AND '2024-04-01'
        GROUP BY
          a.crn, a.application_status, a.copy_category, a.delivery_mode
        ) b ON b.copy_category = a.id
      GROUP BY
        a.id, a.description, a.code;
    ", array($from_date, $to_date));

        return $query->getResult();
        
    }




    function getEpay($data)
    {

        // print_r($data);
        // exit;
        $from_date = $data['from_date'];
        $to_date = $data['to_date'];
        $payhead1 = $data['pay_heads'];
        $crn1 = $data['rdbtn_select'];
        $crn = $data['crn'];
        $title = "eCopying Payment Report : ";

        if ($payhead1 != '') {
            $pay_head = " and b.payment_type_id = " . $payhead1;
        } else {
            $pay_head = "";
        }
        if ($crn1 == 'CRN') {
            $title .= " Received against CRN - " . $crn;

            $flag_qry = " and b.order_batch_merchant_batch_code = '" . $crn . "' ";
        } else {
            $title .= " received date between " . $from_date . " and " . $to_date;

            $flag_qry = " and date(s.entry_time) between '$from_date' and '$to_date' ";
        }
        // print_r($flag_qry);
        // exit;
        $query = $this->db->query("SELECT DISTINCT
            r.order_code, 
            s.entry_time, 
            r.shipping_first_name, 
            r.shipping_last_name, 
            string_agg(b.cart_description || ' - Rs. ' || b.amount, ', ') AS description,
            SUM(b.amount::numeric) AS total_sum,
            r.order_batch_total_amount 
        FROM
            dblink('host=10.18.1.35 user=postgres password=postgres dbname=e_services_06_08 port = 5432', 'SELECT order_code, response_status, entry_time FROM bharat_kosh_status') AS s(order_code TEXT, response_status TEXT, entry_time TIMESTAMP)
        INNER JOIN 
            dblink('host=10.18.1.35 user=postgres password=postgres dbname=e_services_06_08 port = 5432', 'SELECT order_code, shipping_first_name, shipping_last_name, order_batch_total_amount FROM bharat_kosh_request') AS r(order_code TEXT, shipping_first_name TEXT, shipping_last_name TEXT, order_batch_total_amount NUMERIC) ON s.order_code = r.order_code 
        LEFT JOIN 
            dblink('host=10.18.1.35 user=postgres password=postgres dbname=e_services_06_08 port = 5432', 'SELECT cart_description, amount, order_batch_merchant_batch_code FROM bharat_kosh_request_batch') AS b(cart_description TEXT, amount TEXT, order_batch_merchant_batch_code TEXT) ON b.order_batch_merchant_batch_code = r.order_code
        WHERE 
            s.response_status = 'SUCCESS' 
            AND s.order_code NOT LIKE 'DF%'
            $flag_qry
        GROUP BY 
            r.order_code, s.entry_time, r.shipping_first_name, r.shipping_last_name, r.order_batch_total_amount
        ORDER BY 
            s.entry_time;
        
        ");
        // echo $this->db->getLastquery();
        // exit;
        return $query->getResult();
    }

    function ordertype($orderCodes)
    {

        $query = $this->db->query("select application_number_display from copying_order_issuing_application_new where crn = '" . $orderCodes . "'
");

        return $query->getResult();
    }
    function getFileRequest()
    {
        $builder = $this->db->table('copying_order_issuing_application_new app')
            ->select('app.diary, m.pet_name, m.res_name, m.reg_no_display, app.application_number_display, app.application_receipt, us.section_name, users.name, app.remarks')
            ->join('copying_application_documents docs', 'app.id = docs.copying_order_issuing_application_id', 'left')
            ->join('main m', 'm.diary_no = app.diary', 'left')
            ->join('master.users', 'users.usercode = m.dacode', 'left')
            ->join('master.usersection us', 'us.id = m.section_id', 'left')
            ->where('app.application_status', 'P')
            ->where('docs.order_type', 11)
            ->where('m.dacode', 1);

        $builder = $builder->get()->getResult();

        $builder2 = $this->db->table('copying_order_issuing_application_new app')
            ->select('app.diary, m.pet_name, m.res_name, m.reg_no_display, app.application_number_display, app.application_receipt, us.section_name, users.name, app.remarks')
            ->join('copying_application_documents docs', 'app.id = docs.copying_order_issuing_application_id', 'left')
            ->join('main_a m', 'm.diary_no = app.diary', 'left')
            ->join('master.users', 'users.usercode = m.dacode', 'left')
            ->join('master.usersection us', 'us.id = m.section_id', 'left')
            ->where('app.application_status', 'P')
            ->where('docs.order_type', 11)
            ->where('m.dacode', 1);

        $builder2 = $builder2->get()->getResult();
        return  $result = array_merge($builder, $builder2);
    }

    function getReceivedbyri($data)
    {

        $from_date = $data['from_date'];
        $to_date = $data['to_date'];
        $query = $this->db->query("SELECT p.envelope_weight, p.barcode, p.consumed_on, c.application_receipt, c.postal_fee, c.name, c.mobile, c.address,
                                        c.application_number_display, c.crn, c.email, u.name as username, u.empid, e.received_on FROM post_bar_code_mapping p inner 
                                        join copying_order_issuing_application_new c on p.copying_application_id = c.id inner join post_envelope_movement e on e.barcode = p.barcode and 
                                        e.display = 'Y' inner join master.users u on u.usercode = e.received_by where p.is_consumed = '1' and p.is_deleted = '0' and 
                                        date(e.received_on) between '$from_date' and '$to_date' order by e.received_on; ");
        return   $results = $query->getResult();
    }
    function getDiaryorceseSearch($diary_no)
    {
        $query = $this->db->table('copying_order_issuing_application_new coian')
            ->select('coian.id,(case when m.reg_no_display is null then ma.reg_no_display else m.reg_no_display end) as reg_no_display, application_number_display, coian.court_fee')
            ->select("CONCAT(coian.name,
        CASE
            WHEN filed_by = 1 THEN ' (Adv)'
            ELSE CASE
                WHEN filed_by = 2 THEN ' (Party)'
                ELSE CASE
                    WHEN filed_by = 3 THEN ' (AC)'
                    ELSE CASE
                        WHEN filed_by = 4 THEN ' (Other)'
                    END
                END
            END
        END) AS name")
            ->select('rcs.status_description AS status, application_receipt AS received_on')
            ->join('main m', 'coian.diary = m.diary_no', 'left')
            ->join('main_a ma', 'coian.diary = ma.diary_no', 'left')
            ->join('master.ref_copying_status rcs', 'coian.application_status = rcs.status_code', 'left outer')
            ->where('diary', $diary_no)
            ->get();

        return $result = $query->getResult();
    }
    function getUserwise($data)
    {
        $from_date = $data['from_date'];
        $to_date = $data['to_date'];
        $query = $this->db->query("select adm_updated_by,name,empid,sum(catg1) as catg1,sum(catg2) as catg2,sum(catg3) as catg3, sum(catg4) as catg4 from 
                ( select adm_updated_by,name,empid, case when copy_category=1 then count else 0 end as catg1, case when copy_category=2 then count else 0 end as catg2, 
                  case when copy_category=3 then count else 0 end as catg3, case when copy_category=4 then count else 0 end as catg4 from 
                (SELECT COUNT(1) as count,ADM_UPDATED_BY,COPY_CATEGORY,empid , u.name FROM copying_order_issuing_application_new coian left join master.users as u on 
                    u.usercode=coian.adm_updated_by WHERE date(APPLICATION_RECEIPT) between '$from_date' and '$to_date' GROUP BY ADM_UPDATED_BY,COPY_CATEGORY,u.name,empid) a 
                group by adm_updated_by,copy_category,name,empid,a.count ) b group by adm_updated_by,name,empid order by empid asc;");
        return   $results = $query->getResult();
    }
    function getView($status, $category, $fromDate, $toDate, $document, $case_source, $radiodate)
    {
        $condition = "";
        if ($status != '0')
            $condition = " application_status='" . $status . "' and";
        if ($category != '0')
            $condition .= " copy_category=" . $category . " and ";
        if ($document != '0')
            $condition .= " rot.id=" . $document . " and ";
        if ($case_source != '0')
            $condition .= " source=" . $case_source . " and ";
        if ($radiodate == '2' || $radiodate == '3')
            $date_option = "coian.updated_on";
        else
            $date_option = "application_receipt";
        $query = $this->db->query("SELECT DISTINCT
            \"user\".name AS \"user\",
            \"user\".empid,
            COALESCE(user_da.name, user_da_ma.name) AS da_name,
            COALESCE(user_da.empid, user_da_ma.empid) AS da_empid,
            COALESCE(da_section.section_name, 'No Section') AS da_section,
            coian.id,
            -- rot.order_type,
            copy_category,
            application_reg_number,
            application_number_display,
            diary AS diary,
            coian.court_fee,
            CONCAT(coian.name, 
                CASE 
                    WHEN filed_by = 1 THEN ' (Adv)' 
                    WHEN filed_by = 2 THEN ' (Party)' 
                    WHEN filed_by = 3 THEN ' (AC)' 
                    WHEN filed_by = 4 THEN ' (Other)' 
                END) AS coian_name,
            rcs.status_description AS status,
            application_receipt AS received_on,
            rcsr.description,
            coian.updated_on AS updated,
            application_receipt, 
            copy_category, 
            coian.application_status, 
            application_reg_number
        FROM 
            copying_order_issuing_application_new coian
        LEFT JOIN 
            master.ref_copying_status rcs ON coian.application_status = rcs.status_code
        LEFT JOIN 
            copying_application_documents cad ON cad.copying_order_issuing_application_id = coian.id
            LEFT JOIN 
            copying_application_documents_a cada ON cada.copying_order_issuing_application_id = coian.id
        LEFT JOIN 
            master.ref_order_type rot ON rot.id = cad.order_type
        LEFT JOIN 
            master.users \"user\" ON coian.adm_updated_by = \"user\".usercode
        LEFT JOIN 
            main m ON coian.diary = m.diary_no
            LEFT JOIN 
            main_a ma ON coian.diary = ma.diary_no
        LEFT JOIN 
            master.ref_copying_source rcsr ON rcsr.id = coian.source
        LEFT OUTER JOIN 
            master.users user_da ON m.dacode = user_da.usercode
        LEFT OUTER JOIN 
            master.users user_da_ma ON ma.dacode = user_da_ma.usercode
        LEFT OUTER JOIN 
            master.usersection da_section ON COALESCE(user_da.section, user_da_ma.section) = da_section.id
        WHERE 
            " . $condition . "  date($date_option) 
              BETWEEN '" . $fromDate . "' AND '" . $toDate . "'
        ORDER BY 
            application_receipt, copy_category, coian.application_status, application_reg_number;");


        //   echo $this->db->getLastquery();exit;



        return   $results = $query->getResult();
    }


    function show_user_cases($category, $user, $from_date, $to_date)
    {
        $sql = "select u.name as user,u.empid,coian.id,copy_category,
                                        application_reg_number,application_number_display,
                                        concat(LEFT(CAST(diary AS TEXT), -4),'/', RIGHT(CAST(diary AS TEXT), 4) ) as diary,court_fee,
                                        concat(coian.name,case when filed_by=1 then ' (Adv)' else 
                                        case when filed_by=2 then ' (Party)' else case when filed_by=3 then ' (AC)' else case when 
                                        filed_by=4 then ' (Other)' end end end end) as name, rcs.status_description as 
                                        status,application_receipt as received_on,count(cad.id)+count(cad1.id) as documents from 
                                        copying_order_issuing_application_new coian left join 
                                        master.ref_copying_status rcs on coian.application_status=rcs.status_code 
                                        left join master.users u on coian.adm_updated_by=u.usercode left 
                                        join copying_application_documents cad on coian.id=cad.copying_order_issuing_application_id
                                        left join copying_application_documents_a cad1 on coian.id=cad1.copying_order_issuing_application_id
                                        where date(application_receipt) between '$from_date' and '$to_date' and 
                                        adm_updated_by=$user and copy_category=$category group by coian.id,u.name, u.empid,
                                        coian.copy_category,coian.application_reg_number,coian.application_number_display,coian.diary,
                                        coian.court_fee,coian.name,coian.filed_by,rcs.status_description,
                                        coian.application_receipt order by application_receipt";
        $query = $this->db->query($sql);

        return   $results = $query->getResultArray();
    }


    public function trap($app_no)
    {
        $sql = "select prev.status_description as prev,new.status_description as new,name,empid,ct.updated_on 
              from copying_trap ct left join master.ref_copying_status prev on prev.status_code=ct.previous_value 
              left join master.ref_copying_status new on new.status_code=ct.new_value 
              left join master.users u on u.usercode=ct.updated_by 
              where copying_application_id=$app_no order by ct.id";
        $query = $this->db->query($sql);
        return   $results = $query->getResultArray();
    }



    function documents($id, $flag)
    {
        $sql = "SELECT rot.order_type,date(order_date) as order_date,number_of_copies FROM copying_application_documents" . $flag . " cad left join master.ref_order_type rot on cad.order_type=rot.id
where copying_order_issuing_application_id=" . $id;
        $query = $this->db->query($sql);

        return $results = $query->getResultArray();
    }

    public function defects_history($app_no)
    {
        $sql = "select description,case when remark!='' then concat('(',remark,')')else '' end as remark,
              defect_notification_date,defect_cure_date,def.name as def_name,
              def.empid as def_empid,cure.name as cure_name,cure.empid as cure_empid 
              from copying_application_defects cad 
              left join master.users def on def.usercode=cad.defect_notified_by 
              left join master.users cure on cure.usercode=cad.defect_cured_by
              left join master.ref_order_defect rod on rod.id=cad.ref_order_defect_id
              where copying_order_issuing_application_id=$app_no order by cad.id";

        $query = $this->db->query($sql);
        return $results = $query->getResultArray();
    }
    public function order_type()
    {
        $query = $this->db->table('master.ref_order_type')->get();
        return $query->getResultArray();
    }
}
