<?php

namespace App\Models\Exchange;

use CodeIgniter\Model;

class SqlReportModel extends Model
{
    protected $eservicesdb;

    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
        $this->eservicesdb = \Config\Database::connect('eservices');
    }

    public function getProcessReport($frmDate, $toDate, $usercode)
    {
        $return = [];
        $sql = "SELECT 
                    TO_CHAR(transaction_date, 'DD-MM-YYYY') AS transaction_date, 
                    --sum(s1), sum(s2), sum(s3), sum(s4), sum(s5) 
                    s1,s2,s3,s4,s5 
                    FROM 
                    (
                        SELECT 
                        transaction_date, 
                        SUM(
                            CASE WHEN ref_file_movement_status_id = 1 THEN 1 ELSE 0 END
                        ) AS s1, 
                        SUM(
                            CASE WHEN ref_file_movement_status_id = 2 THEN 1 ELSE 0 END
                        ) AS s2, 
                        SUM(
                            CASE WHEN ref_file_movement_status_id = 3 THEN 1 ELSE 0 END
                        ) AS s3, 
                        SUM(
                            CASE WHEN ref_file_movement_status_id = 4 THEN 1 ELSE 0 END
                        ) AS s4, 
                        SUM(
                            CASE WHEN ref_file_movement_status_id = 5 THEN 1 ELSE 0 END
                        ) AS s5 
                        FROM 
                        (
                            SELECT 
                            CAST(cfmt.updated_on AS DATE) AS transaction_date, 
                            cfmt.ref_file_movement_status_id 
                            FROM 
                            causelist_file_movement_transactions cfmt 
                            LEFT JOIN causelist_file_movement cfm ON cfm.id = cfmt.causelist_file_movement_id 
                            LEFT JOIN master.ref_file_movement_status rfms ON cfmt.ref_file_movement_status_id = rfms.id 
                            WHERE 
                            CAST(cfmt.updated_on AS DATE) BETWEEN '$frmDate' AND '$toDate' 
                            AND cfmt.usercode = $usercode
                        ) AS a 
                        GROUP BY 
                        transaction_date
                    ) AS bb 
                    GROUP BY 
                    --transaction_date 
                    transaction_date, bb.s1, bb.s2, bb.s3, bb.s4, bb.s5
                    ORDER BY 
                    transaction_date";
        $res = $this->db->query($sql);
        if ($res->getNumRows() >= 1) {
            $return = $res->getResultArray();
        }
        
        return $return;
    }
    
    public function getSQLProcessReport($tDate, $sId, $usercode)
    {
        $return = [];
        $sql = "SELECT 
                CONCAT(
                    SUBSTRING(CAST(cfm.diary_no AS TEXT), 1, LENGTH(CAST(cfm.diary_no AS TEXT)) - 4),
                    '/',
                    SUBSTRING(CAST(cfm.diary_no AS TEXT), LENGTH(CAST(cfm.diary_no AS TEXT)) - 3, 4)
                ) AS diary_no,
                reg_no_display,
                CONCAT(pet_name, ' vs ', res_name) AS title,
                movement_status,
                u1.name AS da,
                u2.name AS nsh,
                u3.name AS attendant,
                CAST(cfmt.updated_on AS DATE) AS transaction_date,
                CAST(cfmt.updated_on AS TIME) AS transaction_time
                FROM 
                causelist_file_movement_transactions cfmt 
                LEFT JOIN causelist_file_movement cfm ON cfm.id = cfmt.causelist_file_movement_id
                JOIN main m ON m.diary_no = cfm.diary_no
                LEFT JOIN master.ref_file_movement_status rfms ON cfmt.ref_file_movement_status_id = rfms.id
                LEFT JOIN master.users u1 ON cfm.dacode = u1.usercode
                LEFT JOIN master.users u2 ON cfm.cm_nsh_usercode = u2.usercode 
                LEFT JOIN master.users u3 ON cfmt.attendant_usercode = u3.usercode
                WHERE 
                CAST(cfmt.updated_on AS DATE) = '$tDate' 
                AND cfmt.ref_file_movement_status_id = '$sId' 
                AND cfmt.usercode = '$usercode' 
                ORDER BY 
                transaction_date, transaction_time";
                //pr($sql);
        $res = $this->db->query($sql);
        if ($res->getNumRows() >= 1) {
            $return = $res->getResultArray();
        }
        
        return $return;
    }
}