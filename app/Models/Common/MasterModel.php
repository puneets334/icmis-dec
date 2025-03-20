<?php

namespace App\Models\Common;

use CodeIgniter\Model;

class MasterModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    function getOrderType()
    {
        $query = $this->db->table('master.ref_order_type')
            ->select('*')
            ->get();

        return $query->getResultArray();
    }

    function getSectionList()
    {
        $query = $this->db->table('master.usersection')
            ->select('*')
            ->where('display', 'Y')
            ->where('isda', 'Y')
            ->orWhere('id', '61')
            ->orderBy('isda desc, id ASC')
            ->get();

        return $query->getResultArray();
    }

    public function getDesignation($empid)
    {
        $query = $this->db->table('master.users')
            ->select('usertype')
            ->where("empid", $empid)
            ->get();

        $result = $query->getRowArray();

        return $result['usertype'];
    }

    public function getActionPendingReportDA($empid, $fromDate = '', $toDate = '', $deliver_mode = '', $order_type = '')
    {

        // $condition = " and 1=1 ";
        $condition = " 1=1 ";
        if ($fromDate != '' && $toDate != '') {

            $fromDate = date('Y-m-d',strtotime($fromDate));
            $toDate = date('Y-m-d',strtotime($toDate));
            if ($deliver_mode != '')
                $condition .= " and app.delivery_mode = '$deliver_mode' ";

            if ($order_type != '')
                $condition .= " and doc.order_type=$order_type ";

            // $empid = 1; // Example empid, replace as necessary
            // $fromDate = '2024-09-11'; // Example start date
            // $toDate = '2024-09-19'; // Example end date

            $builder = $this->db->table('copying_order_issuing_application_new app');
            $builder->select([
                'application_number_display',
                "CONCAT(SUBSTRING(diary::text FROM 1 FOR LENGTH(diary::text) - 4), '/', SUBSTRING(diary::text FROM LENGTH(diary::text) - 3 FOR 4)) AS diary_no_display",
                'reg_no_display',
                'remarks',
                'application_receipt',
                '(CURRENT_DATE - application_receipt) AS diff',
                'us.section_name',
                "COALESCE(tentative_section(main.diary_no::bigint), us2.section_name) AS sec",
                'tentative_da(main.diary_no::int) AS tentative_da',
                'c_status',
                "(SELECT consignment_date FROM record_keeping rk WHERE display = 'Y' AND rk.diary_no = diary) AS consignment_date",
                "(SELECT MAX(disp_dt) FROM dispose WHERE diary_no = diary) AS disposal_dt",
                'u.name AS da',
                "STRING_AGG(CONCAT(rcd.order_type, ' d/t ', TO_CHAR(order_date, 'DD/MM/YYYY')), ', ') AS docs",
                'u1.name AS updatedby',
                'us1.section_name AS updatedbysection'
            ]);

            $builder->join('main', 'main.diary_no = app.diary', 'left');
            $builder->join('master.users u', 'u.usercode = main.dacode AND u.display = \'Y\'', 'left');
            $builder->join('master.users u1', 'u1.usercode = main.last_usercode AND u1.display = \'Y\'', 'left');
            $builder->join('master.usersection us', 'us.id = u.section', 'left');
            $builder->join('master.usersection us1', 'us1.id = u1.section', 'left');
            $builder->join('master.usersection us2', 'us2.id = main.section_id', 'left');
            $builder->join('copying_application_documents doc', 'doc.copying_order_issuing_application_id = app.id', 'left');
            $builder->join('master.ref_order_type rcd', 'rcd.id = doc.order_type', 'left');

            $builder->where('application_status', 'A');
            $builder->where('c_status', 'P');
            $builder->where('u.empid', $empid);
            $builder->where($condition);
            //$builder->where("application_receipt BETWEEN TO_DATE('$fromDate', 'DD-MM-YYYY') AND TO_DATE('$toDate', 'DD-MM-YYYY')");
            $builder->where("application_receipt BETWEEN '$fromDate' AND '$toDate' ");

            $builder->groupBy([
                'app.id',
                'application_number_display',
                'diary',
                'reg_no_display',
                'remarks',
                'application_receipt',
                'us.section_name',
                'main.diary_no',
                'us2.section_name',
                'u.name',
                'u1.name',
                'us1.section_name'
            ]);

            $builder->orderBy('diff');
            // echo $builder->getCompiledSelect();
            // die();

            $query = $builder->get();

            return $query->getResultArray();
        } else {
            return false;
        }
    }
    public function get_cl_print_mainhead($mainhead, $board_type)
    {
        if ($mainhead == 'M')
            $m_f = '1';
        if ($mainhead == 'F')
            $m_f = '2';
        if ($board_type == '0') {
            $board_type_in = "";
        } else {
            $board_type_in = " and c.board_type = '$board_type'";
        }

        $sql = "SELECT 
                    c.next_dt 
                    FROM 
                    heardt c 
                    WHERE 
                    mainhead = $mainhead $board_type_in
                    AND c.next_dt >= CURRENT_DATE 
                    AND (
                        c.main_supp_flag = '1' 
                        OR c.main_supp_flag = '2'
                    ) 
                    GROUP BY 
                    c.next_dt;";
    echo $sql; die;
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
}
