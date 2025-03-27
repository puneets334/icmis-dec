<?php

namespace App\Models\Exchange;

use CodeIgniter\Model;

class Transaction extends Model
{
    protected $eservicesdb;

    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
        $this->eservicesdb = \Config\Database::connect('eservices');
    }

    public function getAllCaseType()
    {
        $queryString = "SELECT casecode, casename FROM master.casetype WHERE display = 'Y' ORDER BY casecode";
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

    public function transaction_process($searchby, $caseType = null, $caseNo = null, $caseYear = null, $dNo = null, $dYear = null)
    {
        $return = [];
        if($searchby == 2) {
            $diaryNumber = $dNo.$dYear;
            $queryString = "SELECT
                cfm.next_dt AS next_dt,
                DATE(cfmt.updated_on) AS transaction_date,
                CASE
                    WHEN cfmt.ref_file_movement_status_id = 1 THEN
                        movement_status || u2.name ||
                        CASE WHEN u3.name IS NULL THEN '' ELSE ' through ' || u3.name END ||
                        ' by ' || u1.name || '-' || TO_CHAR(cfmt.updated_on, 'HH24:MI:SS')
                    WHEN cfmt.ref_file_movement_status_id IN (2, 3) THEN
                        movement_status || u2.name ||
                        CASE WHEN u3.name IS NULL THEN '' ELSE ' through ' || u3.name END ||
                        '-' || TO_CHAR(cfmt.updated_on, 'HH24:MI:SS')
                    WHEN cfmt.ref_file_movement_status_id IN (4, 5) THEN
                        movement_status || '-' || u1.name ||
                        CASE WHEN u3.name IS NULL THEN '' ELSE ' through ' || u3.name END ||
                        '-' || TO_CHAR(cfmt.updated_on, 'HH24:MI:SS')
                    ELSE NULL
                END AS info
            FROM
                causelist_file_movement cfm 
            LEFT JOIN 
                causelist_file_movement_transactions cfmt ON cfm.id = cfmt.causelist_file_movement_id 
            LEFT JOIN 
                master.ref_file_movement_status rfms ON cfmt.ref_file_movement_status_id = rfms.id 
            LEFT JOIN 
                master.users u1 ON cfm.dacode = u1.usercode 
            LEFT JOIN 
                master.users u2 ON cfm.cm_nsh_usercode = u2.usercode 
            LEFT JOIN 
                master.users u3 ON cfmt.attendant_usercode = u3.usercode 
            WHERE 
                diary_no = $diaryNumber
            GROUP BY 
                cfm.next_dt, DATE(cfmt.updated_on), TO_CHAR(cfmt.updated_on, 'HH24:MI:SS'), cfmt.ref_file_movement_status_id, movement_status, u1.name, u2.name, u3.name
            ORDER BY 
                cfm.next_dt DESC, DATE(cfmt.updated_on) DESC, TO_CHAR(cfmt.updated_on, 'HH24:MI:SS') DESC";

            $query = $this->db->query($queryString);
            if ($query->getNumRows() >= 1) {
                $return =  $query->getResultArray();
            }
        } else if($searchby == 1) {
            $queryString = "SELECT 
                cfm.next_dt AS next_dt,
                DATE(cfmt.updated_on) AS transaction_date,
                CASE 
                    WHEN cfmt.ref_file_movement_status_id = 1 THEN 
                        CONCAT(movement_status, u2.name, 
                               CASE WHEN u3.name IS NULL THEN '' ELSE CONCAT(' through ', u3.name) END, 
                               ' by ', u1.name, '-', TO_CHAR(cfmt.updated_on, 'HH24:MI:SS'))
                    WHEN cfmt.ref_file_movement_status_id = 2 THEN 
                        CONCAT(movement_status, u2.name, 
                               CASE WHEN u3.name IS NULL THEN '' ELSE CONCAT(' through ', u3.name) END, 
                               '-', TO_CHAR(cfmt.updated_on, 'HH24:MI:SS'))
                    WHEN cfmt.ref_file_movement_status_id = 3 THEN 
                        CONCAT(movement_status, u2.name, 
                               CASE WHEN u3.name IS NULL THEN '' ELSE CONCAT(' through ', u3.name) END, 
                               '-', TO_CHAR(cfmt.updated_on, 'HH24:MI:SS'))
                    WHEN cfmt.ref_file_movement_status_id = 4 THEN 
                        CONCAT(movement_status, '-', u1.name, 
                               CASE WHEN u3.name IS NULL THEN '' ELSE CONCAT(' through ', u3.name) END, 
                               '-', TO_CHAR(cfmt.updated_on, 'HH24:MI:SS'))
                    WHEN cfmt.ref_file_movement_status_id = 5 THEN 
                        CONCAT(movement_status, '-', u1.name, 
                               CASE WHEN u3.name IS NULL THEN '' ELSE CONCAT(' through ', u3.name) END, 
                               '-', TO_CHAR(cfmt.updated_on, 'HH24:MI:SS'))
                    ELSE NULL 
                END AS info 
            FROM 
                causelist_file_movement cfm 
            LEFT JOIN 
                causelist_file_movement_transactions cfmt ON cfm.id = cfmt.causelist_file_movement_id 
            LEFT JOIN 
                master.ref_file_movement_status rfms ON cfmt.ref_file_movement_status_id = rfms.id 
            LEFT JOIN 
                master.users u1 ON cfm.dacode = u1.usercode 
            LEFT JOIN 
                master.users u2 ON cfm.cm_nsh_usercode = u2.usercode 
            LEFT JOIN 
                master.users u3 ON cfmt.attendant_usercode = u3.usercode 
            WHERE 
                diary_no = (
                    SELECT diary_no 
                    FROM main 
                    WHERE CAST(SUBSTRING(active_fil_no FROM 1 FOR 2) AS INTEGER) = $caseType 
                      AND active_reg_year = $caseYear 
                      AND (CAST(SUBSTRING(active_fil_no FROM 4 FOR 6) AS INTEGER) = $caseNo 
                        OR $caseNo BETWEEN CAST(NULLIF(SUBSTRING(active_fil_no FROM 4 FOR 6), '') AS INTEGER) 
                        AND CAST(NULLIF(SUBSTRING(active_fil_no FROM 11 FOR 6), '')  AS INTEGER)) Limit 1
                ) 
            GROUP BY 
                cfm.next_dt, 
                DATE(cfmt.updated_on), 
                TO_CHAR(cfmt.updated_on, 'HH24:MI:SS'), 
                cfmt.ref_file_movement_status_id, 
                movement_status, 
                u1.name, 
                u2.name, 
                u3.name 
            ORDER BY 
                cfm.next_dt DESC, 
                DATE(cfmt.updated_on) DESC, 
                TO_CHAR(cfmt.updated_on, 'HH24:MI:SS') DESC";
            $query = $this->db->query($queryString);
            if ($query->getNumRows() >= 1) {
                $return = $query->getResultArray();
            }
            
        }
        return $return;
        /*$query = $this->db->query($queryString);
        if ($query->getNumRows() >= 1)
        {
            return $query->getResultArray();
        }
        else
        {
            return [];
        }*/
    }

    public function fetchTransactionData($searchby, $caseType = null, $caseNo = null, $caseYear = null, $dNo = null, $dYear = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('causelist_file_movement cfm');

        $builder->select('cfm.next_dt as next_dt, date(cfmt.updated_on) as transaction_date');
        $builder->select("CASE
            WHEN cfmt.ref_file_movement_status_id = 1 THEN CONCAT(rfms.movement_status, ' ', u2.name, 
                CASE WHEN u3.name IS NULL THEN '' ELSE CONCAT(' through ', u3.name) END, 
                ' by ', u1.name, '-', TIME(cfmt.updated_on))
            WHEN cfmt.ref_file_movement_status_id = 2 THEN CONCAT(rfms.movement_status, ' ', u2.name, 
                CASE WHEN u3.name IS NULL THEN '' ELSE CONCAT(' through ', u3.name) END, '-', TIME(cfmt.updated_on))
            WHEN cfmt.ref_file_movement_status_id = 3 THEN CONCAT(rfms.movement_status, ' ', u2.name, 
                CASE WHEN u3.name IS NULL THEN '' ELSE CONCAT(' through ', u3.name) END, '-', TIME(cfmt.updated_on))
            WHEN cfmt.ref_file_movement_status_id = 4 THEN CONCAT(rfms.movement_status, '-', u1.name, 
                CASE WHEN u3.name IS NULL THEN '' ELSE CONCAT(' through ', u3.name) END, '-', TIME(cfmt.updated_on))
        
            END AS info");
        
        $builder->join('causelist_file_movement_transactions cfmt', 'cfm.id = cfmt.causelist_file_movement_id', 'left');
        $builder->join('master.ref_file_movement_status rfms', 'cfmt.ref_file_movement_status_id = rfms.id', 'left');
        $builder->join('master.users u1', 'cfm.dacode = u1.usercode', 'left');
        $builder->join('master.users u2', 'cfm.cm_nsh_usercode = u2.usercode', 'left');
        $builder->join('master.users u3', 'cfmt.attendant_usercode = u3.usercode', 'left');
        
        if ($searchby == 2) {
            $builder->where('diary_no', $dNo . $dYear);
        } else if ($searchby == 1) {
            $subquery = $db->table('main')
                ->select('diary_no')
                ->where('CAST(SUBSTRING(active_fil_no, 1, 2) AS INTEGER)', $caseType)  // Changed UNSIGNED to INTEGER
                ->where('active_reg_year', $caseYear)
                ->groupStart()
                ->where('CAST(SUBSTRING(active_fil_no, 4, 6) AS INTEGER)', $caseNo)
                ->orWhere('CAST(SUBSTRING(active_fil_no, 11, 6) AS INTEGER)', $caseNo)
                ->groupEnd()
                ->getCompiledSelect();
        
            $builder->where('diary_no IN (' . $subquery . ')');
        }
        
        $builder->groupBy('cfm.next_dt, date(cfmt.updated_on), time(cfmt.updated_on)');
        $builder->orderBy('cfm.next_dt', 'desc');
        $builder->orderBy('date(cfmt.updated_on)', 'desc');
        $builder->orderBy('time(cfmt.updated_on)', 'desc');
        
        $query = $builder->get();
        return $query->getResultArray();
        
    }
}
