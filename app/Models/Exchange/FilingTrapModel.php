<?php

namespace App\Models\Exchange;

use CodeIgniter\Model;

class FilingTrapModel extends Model
{
    protected $eservicesdb;

    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
        $this->eservicesdb = \Config\Database::connect('eservices');
    }

    public function get_trap($dno)
    {
        $queryString="SELECT a.*, u1.name AS d_by_name, u2.name AS r_by_name, u3.name AS o_name, u4.name AS d_to_name, 
        order_seq FROM
        (
            SELECT uid, diary_no, d_by_empid, disp_dt, remarks, r_by_empid, d_to_empid, rece_dt, comp_dt, other, 1 AS order_seq 
            FROM fil_trap 
            WHERE diary_no = $dno
            UNION
            SELECT uid, diary_no, d_by_empid, disp_dt, remarks, r_by_empid, d_to_empid, rece_dt, comp_dt, other, 2 AS order_seq 
            FROM fil_trap_his 
            WHERE diary_no = $dno
        ) a 
        LEFT JOIN master.users u1 ON d_by_empid = u1.empid
        LEFT JOIN master.users u2 ON r_by_empid = u2.empid
        LEFT JOIN master.users u3 ON other = u3.empid
        LEFT JOIN master.users u4 ON d_to_empid = u4.empid
        ORDER BY a.order_seq, disp_dt DESC, rece_dt DESC";

        $query = $this->db->query($queryString);
        if ($query->getNumRows() >= 1)
        {
            return $query->getResultArray();
        }
        else
        {
            return [];
        }
    }
}