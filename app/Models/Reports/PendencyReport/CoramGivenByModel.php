<?php

namespace App\Models\Reports\PendencyReport;

use CodeIgniter\Model;

class CoramGivenByModel extends Model
{
    protected $eservicesdb;

    public function __construct()
    {
        parent::__construct();
        $this->db_icmis = db_connect();
        $this->eservicesdb = \Config\Database::connect('eservices');
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

    public function removeCoram()
    {
        $ucode = session()->get('login')['usercode'];
        $judge = $_POST['judge'];
        $crm_dtl = $_POST['crm_dtl'];
        // pr($crm_dtl);
        $mainhead = $_POST['mainhead'];
        $html = '';

        if($crm_dtl == 1)
        {
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
                AND (m.diary_no = CAST(m.conn_key AS BIGINT) OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
                AND h.mainhead = '$mainhead'
                AND CAST($judge AS TEXT) = ANY (STRING_TO_ARRAY(h.coram, ','))
                AND h.list_before_remark = 11
            ORDER BY 
                CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3 FOR 4) AS INTEGER) ASC,
                CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER) ASC";
        }
        else if($crm_dtl == 2)
        {
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
                INNER JOIN not_before n ON m.diary_no = n.diary_no
                WHERE
                    m.c_status = 'P'
                    AND h.mainhead = '$mainhead'
                    AND (m.diary_no = CAST(m.conn_key AS BIGINT) OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
                    AND n.notbef = 'B'
                    AND n.res_id = 11
                    AND n.j1 = $judge
            ) m
            INNER JOIN not_before n ON n.diary_no = m.diary_no
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
        }
        else if($crm_dtl == 3)
        {
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
                INNER JOIN not_before n ON m.diary_no = n.diary_no
                WHERE 
                    m.c_status = 'P' 
                    AND h.mainhead = '$mainhead'
                    AND (m.diary_no = CAST(m.conn_key AS BIGINT) OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
                    AND n.notbef = 'B' 
                    AND n.res_id != 11 
                    AND n.j1 = $judge
            ) m
            INNER JOIN not_before n ON n.diary_no = m.diary_no
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
        }
        else if($crm_dtl == 4)
        {
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
                AND (m.diary_no = CAST(m.conn_key AS BIGINT) OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
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
        }
        else if($crm_dtl == 5)
        {
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
                not_before n ON n.diary_no = m.diary_no AND n.notbef = 'B' AND n.j1 = CAST($judge AS INTEGER)
            WHERE 
                n.j1 IS NULL 
                AND m.c_status = 'P' 
                AND h.mainhead = CAST('$mainhead' AS TEXT)
                AND (m.diary_no = CAST(m.conn_key AS BIGINT) OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
                AND CAST($judge AS TEXT) = ANY (STRING_TO_ARRAY(h.coram, ','))
                AND (h.subhead != '824' AND mc.submaster_id != '913') 
                AND h.list_before_remark != '11'
            GROUP BY 
                m.diary_no, h.diary_no, h.coram, m.reg_no_display, m.pet_name, m.res_name, m.lastorder
            ORDER BY 
                CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3 FOR 4) AS INTEGER) ASC,
                CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER) ASC"; 
        }
        else
        {
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
                AND (m.diary_no = CAST(m.conn_key AS BIGINT) OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
                AND CAST($judge AS TEXT) = ANY (STRING_TO_ARRAY(h.coram, ','))
            ORDER BY
                CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3 FOR 4) AS INTEGER) ASC,
                CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER) ASC";

        }

        $sqlString = $this->db->query($sql);
        if($sqlString->getNumRows() >= 1)
        {
            $result = $sqlString->getResultArray();
            $sno = 1;
            $theadRowStyle = 'style="font-weight: bold; color: #dce38d; background-color: #918788;"';
            $html .= '<div class = "table-responsive" id="prnnt" style="text-align: center; font-size:13px;">
                <table align="left" width="100%" border="0px;" style="padding: 10px; font-size:13px; table-layout: fixed;">
                <thead>
                    <tr>
                        <td width="5%" '.$theadRowStyle.'>SrNo.</td>
                        <td width="25%" '.$theadRowStyle.'>Case No. # Diary No.</td>
                        <td width="40%" '.$theadRowStyle.'>Cause Title</td>
                        <td width="10%" '.$theadRowStyle.'>Coram</td>
                        <td width="20%" '.$theadRowStyle.'>Last order</td>
                    </tr>
                </thead>';
            $html .= '<tbody>';
            foreach($result as $key => $value)
            {
                $sno1 = $sno % 2;
                $backgroundColor = ($sno1 == 1) ? '#ececec' : '#f6e0f3';

                $html .= '<tr style="padding: 10px; background-color: ' . $backgroundColor . ';">';
                $html .= '<td align="left" style="vertical-align: top;">' . $sno . '</td>';
                $html .= '<td align="left" style="vertical-align: top;">' . $value['reg_no_display'];

                if ($value['reg_no_display'])
                {
                    $html .= ' # ';
                }

                $html .= $value['dno'] . '-' . $value['dyr'] . '</td>';
                $html .= '<td align="left" style="vertical-align: top;">' . $value['pet_name'] . ' Vs ' . $value['res_name'] . '</td>';
                $html .= '<td align="left" style="vertical-align: top;">' . f_get_judge_names_inshort($value['coram']) . '</td>';
                $html .= '<td align="left" style="vertical-align: top;">' . $value['lastorder'] . '</td>';
                $html .= '</tr>';

                $sno++;
            }
            $html .= '</tbody></table></div>';
            $html .= '<div class="col-md-12" style="text-align: left; padding-bottom:10px;"><input name="prnnt1" type="button" id="prnnt1" value="Print"></div>';
            return $html;
        }
        else
        {
            $html .= '<p align="center"><font color=red>No Recrods Found</font></p>';
            return $html;
        }
    }
}
