<?php

namespace App\Models\Reports\PendencyReport;

use CodeIgniter\Model;

class CoramGivenByModel extends Model
{
    protected $eservicesdb;

    public function __construct()
    {
        parent::__construct();
        //$this->db_icmis = db_connect();
        //$this->eservicesdb = \Config\Database::connect('eservices');
    }

    public function getJudgesList()
    {
        $returnArr = [];
        $query = "SELECT jcode, jname, abbreviation FROM master.judge WHERE is_retired = 'N' and jtype = 'J' order by judge_seniority";
        $queryString = $this->db->query($query);
        if($queryString->getNumRows() >= 1)
        {
            $returnArr = $queryString->getResultArray();
            return $returnArr;
        }
        else
        {
            return $returnArr;
        }
    }

    public function removeCoram($judge, $crm_dtl, $mainhead)
    {
        $return = [];
        if($crm_dtl == 1) {
            //Coram Given by CJI
            $sql = "SELECT 
                CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3 FOR 4) AS INTEGER) AS dyr,
                CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER) AS dno,
                h.diary_no, 
                m.reg_no_display, 
                m.pet_name, 
                m.res_name, 
                h.coram, 
                TRIM(BOTH ',' FROM REPLACE(REPLACE(h.coram, CAST($judge AS TEXT), ''), ',,', ',')) AS new_coram, 
                lastorder
            FROM 
                heardt h
            JOIN 
                main m ON m.diary_no = h.diary_no
            WHERE 
                m.c_status = 'P'
                AND (m.diary_no = CAST(NULLIF(m.conn_key, '') AS BIGINT) OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
                AND h.mainhead = '$mainhead'
                AND CAST($judge AS TEXT) = ANY (STRING_TO_ARRAY(h.coram, ','))
                AND h.list_before_remark = 11
            ORDER BY 
                CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3 FOR 4) AS INTEGER) ASC,
                CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER) ASC";
        } else if($crm_dtl == 2) {
            //Special Bench asigned by CJ
            $sql = "SELECT m.*, 
                   STRING_AGG(CAST(n.j1 AS TEXT), ',') AS coram, 
                   TRIM(BOTH ',' FROM REPLACE(REPLACE(STRING_AGG(CAST(n.j1 AS TEXT), ','), CAST($judge AS TEXT), ''), ',,', ',')) AS new_coram
            FROM
            (
                SELECT 
                    CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3 FOR 4) AS INTEGER) AS dyr,
                    CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER) AS dno,
                    h.diary_no,
                    m.reg_no_display,
                    m.pet_name,
                    m.res_name,
                    m.lastorder
                FROM heardt h
                INNER JOIN main m ON m.diary_no = h.diary_no
                INNER JOIN not_before n ON m.diary_no = CAST(n.diary_no AS BIGINT)
                WHERE
                    m.c_status = 'P'
                    AND h.mainhead = '$mainhead'
                    AND (m.diary_no = CAST(NULLIF(m.conn_key, '') AS BIGINT) OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
                    AND n.notbef = 'B'
                    AND n.res_id = 11
                    AND n.j1 = $judge
            ) m
            INNER JOIN not_before n ON CAST(n.diary_no AS BIGINT) = m.diary_no
            GROUP BY
                m.diary_no,
                m.dyr,
                m.dno,
                m.reg_no_display,
                m.pet_name,
                m.res_name,
                m.lastorder
            ORDER BY
                CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3 FOR 4) AS INTEGER) ASC,
                CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER) ASC";
        } else if($crm_dtl == 3) {
            //Special Bench
            $sql = "SELECT m.*, 
                   STRING_AGG(CAST(n.j1 AS TEXT), ',') AS coram, 
                   TRIM(BOTH ',' FROM REPLACE(REPLACE(STRING_AGG(CAST(n.j1 AS TEXT), ','), CAST($judge AS TEXT), ''), ',,', ',')) AS new_coram
            FROM
            (
                SELECT 
                    CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3 FOR 4) AS INTEGER) AS dyr, 
                    CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER) AS dno, 
                    h.diary_no, 
                    m.reg_no_display, 
                    m.pet_name, 
                    m.res_name, 
                    m.lastorder
                FROM heardt h
                INNER JOIN main m ON m.diary_no = h.diary_no 
                INNER JOIN not_before n ON m.diary_no = CAST(n.diary_no AS BIGINT)
                WHERE 
                    m.c_status = 'P' 
                    AND h.mainhead = '$mainhead'
                    AND (m.diary_no = CAST(NULLIF(m.conn_key, '') AS BIGINT) OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
                    AND n.notbef = 'B' 
                    AND n.res_id != 11 
                    AND n.j1 = $judge
            ) m
            INNER JOIN not_before n ON CAST(n.diary_no AS BIGINT) = m.diary_no
            GROUP BY 
                m.diary_no, 
                m.dyr, 
                m.dno, 
                m.reg_no_display, 
                m.pet_name, 
                m.res_name, 
                m.lastorder
            ORDER BY 
                CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3 FOR 4) AS INTEGER) ASC,
                CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER) ASC"; 
        } else if($crm_dtl == 4) {
            //Part Heard
            $sql = "SELECT 
                CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3 FOR 4) AS INTEGER) AS dyr,
                CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER) AS dno,
                h.diary_no, 
                h.coram, 
                m.reg_no_display, 
                m.pet_name, 
                m.res_name,
                TRIM(BOTH ',' FROM REPLACE(REPLACE(h.coram, CAST($judge AS TEXT), ''), ',,', ',')) AS new_coram,
                m.lastorder
            FROM 
                heardt h
            INNER JOIN 
                main m ON m.diary_no = h.diary_no 
            LEFT JOIN 
                mul_category mc ON mc.diary_no = m.diary_no AND mc.display = 'Y'
            WHERE 
                m.c_status = 'P'
                AND h.mainhead = '$mainhead'
                AND (m.diary_no = CAST(NULLIF(m.conn_key, '') AS BIGINT) OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
                AND CAST($judge AS TEXT) = ANY (STRING_TO_ARRAY(h.coram, ','))
                AND (h.subhead = '824' OR mc.submaster_id = '913')
            GROUP BY 
                m.diary_no, 
                h.diary_no, 
                h.coram, 
                m.reg_no_display, 
                m.pet_name, 
                m.res_name, 
                m.lastorder
            ORDER BY 
                CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3 FOR 4) AS INTEGER) ASC,
                CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER) ASC";   
        } else if($crm_dtl == 5) {
            //Other
            $sql = "SELECT 
                CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3 FOR 4) AS INTEGER) AS dyr,
                CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER) AS dno,
                h.diary_no, 
                h.coram, 
                m.reg_no_display, 
                m.pet_name, 
                m.res_name,
                TRIM(BOTH ',' FROM REPLACE(REPLACE(h.coram, CAST($judge AS TEXT), ''), ',,', ',')) AS new_coram,
                m.lastorder
            FROM 
                heardt h
            INNER JOIN 
                main m ON m.diary_no = h.diary_no 
            LEFT JOIN 
                mul_category mc ON mc.diary_no = m.diary_no AND mc.display = 'Y'
            LEFT JOIN 
                not_before n ON CAST(n.diary_no AS BIGINT) = m.diary_no AND n.notbef = 'B' AND n.j1 = CAST($judge AS INTEGER)
            WHERE 
                n.j1 IS NULL 
                AND m.c_status = 'P' 
                AND h.mainhead = CAST('$mainhead' AS TEXT)
                AND (m.diary_no = CAST(NULLIF(m.conn_key, '') AS BIGINT) OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
                AND CAST($judge AS TEXT) = ANY (STRING_TO_ARRAY(h.coram, ','))
                AND (h.subhead != '824' AND mc.submaster_id != '913') 
                AND h.list_before_remark != '11'
            GROUP BY 
                m.diary_no, h.diary_no, h.coram, m.reg_no_display, m.pet_name, m.res_name, m.lastorder
            ORDER BY 
                CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3 FOR 4) AS INTEGER) ASC,
                CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER) ASC"; 
        } else {
            //All
            $sql = "SELECT 
                CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3 FOR 4) AS INTEGER) AS dyr,
                CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER) AS dno,
                h.diary_no, 
                h.coram, 
                TRIM(BOTH ',' FROM REPLACE(REPLACE(h.coram, CAST($judge AS TEXT), ''), ',,', ',')) AS new_coram,
                m.reg_no_display, 
                m.pet_name, 
                m.res_name, 
                m.lastorder
            FROM 
                heardt h
            INNER JOIN 
                main m ON m.diary_no = h.diary_no
            WHERE 
                m.c_status = 'P'
                AND h.mainhead = CAST('$mainhead' AS TEXT)
                AND (m.diary_no = CAST(NULLIF(m.conn_key, '') AS BIGINT) OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
                AND CAST($judge AS TEXT) = ANY (STRING_TO_ARRAY(h.coram, ','))
            ORDER BY
                CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3 FOR 4) AS INTEGER) ASC,
                CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER) ASC";

        }
        //pr($sql);
        $sqlString = $this->db->query($sql);
        if($sqlString->getNumRows() >= 1) {
            $return = $sqlString->getResultArray();
        }
        
        return $return;   
    }
}
