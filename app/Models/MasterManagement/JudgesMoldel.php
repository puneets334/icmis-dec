<?php

namespace App\Models\MasterManagement;

use CodeIgniter\Model;

class JudgesMoldel extends Model
{


    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }


    public function insert_judges_data(
        $jtype = null,
        $jcode = null,
        $jname = null,
        $first_name = null,
        $title = null,
        $sur_name = null,
        $jcourt = null,
        $abbreviation = null,
        $from_date = null,
        $to_date = null,
        $usercode = null,
        $judge_seniority = null
    ) 
    {
        if ($jtype !== 'J') {
            $title = 'REGISTRAR';
        }

        $data = [
            'jcode'            => $jcode,
            'jname'            => $jname,
            'first_name'       => $first_name,
            'title'            => $title,
            'sur_name'         => $sur_name,
            'jcourt'           => $jcourt,
            'abbreviation'     => $abbreviation,
            'is_retired'       => 'N',
            'display'          => 'Y',
            'appointment_date' => ($from_date === '1970-01-01' || empty($from_date)) ? null : $from_date,
            'to_dt'            => ($to_date === '1970-01-01' || empty($to_date)) ? null : $to_date,
            'cji_date'         => null,
            'jtype'            => $jtype,
            'entuser'          => $usercode,
            'entdt'            => date('Y-m-d H:i:s'),
            'judge_seniority'  => $judge_seniority,
        ];

        $builder = $this->db->table('master.judge');
        $builder->insert($data);

        return ($this->db->affectedRows() > 0) ? 1 : 0;
    }





    function jcodesearch($jtype = null)
    {

        $sql = "select max(jcode)+1 as jcode,max(judge_seniority)+1 as sen from master.judge where jtype='$jtype' and jcode<2000";
        $query = $this->db->query($sql);
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }



    function display_Latest_Updates()
    {
        $sql = "SELECT mn.menu_name, f_date, t_date, title_en, ent_dt, user,ip FROM master.content_for_latestupdates
                inner join master.menu_for_latestupdates mn ON content_id=mn.mno
                order by ent_dt desc";

        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }



    function update_judges_data($jcode = null, $jname = null, $first_name = null, $title = null, $sur_name = null, $jcourt = null, $abbreviation = null, $retired = null, $display = null, $from_date = null, $to_date = null, $cji_date = null, $usercode = null, $judge_seniority = null)
    {
        if ($cji_date == null) {
            $cji_date = 0000 - 00 - 00;
        }
        if ($jcourt == null) {
            $jcourt = 0;
        }
        $head = null;
        $inserted = null;
        if ($title == '1') {
            $head = "REGISTRAR";
        } elseif ($title == '2') {
            $head = "HON''BLE MR. JUSTICE";
        } elseif ($title == '3') {
            $head = "HON''BLE MRS. JUSTICE";
        } elseif ($title == '4') {
            $head = "HON''BLE MS. JUSTICE";
        } elseif ($title == '5') {
            $head = "HON''BLE KUMARI JUSTICE";
        } elseif ($title == '6') {
            $head = "HON''BLE DR. JUSTICE";
        } elseif ($title == '7') {
            $head = "REGISTRAR (J-I)";
        } elseif ($title == '8') {
            $head = "REGISTRAR (J-II)";
        } elseif ($title == '9') {
            $head = "REGISTRAR (J-III)";
        } elseif ($title == '10') {
            $head = "REGISTRAR (J-IV)";
        } elseif ($title == '11') {
            $head = "REGISTRAR (J-V)";
        } elseif ($title == '12') {
            $head = "REGISTRAR (J-VI)";
        } elseif ($title == '13') {
            $head = "REGISTRAR (OSD)";
        } else {
            $head = "HON''BLE THE CHIEF JUSTICE";
        }

        if ($title == '1' || $title == '7' || $title == '8' || $title == '9' || $title == '10' || $title == '11' || $title == '12' || $title == '13') {
            $jtype = 'R';
            $jname = $first_name . " " . $sur_name;
        } else {
            $jtype = 'J';
            $jname = $head . " " . $first_name . " " . $sur_name;
        }



        $sql = "UPDATE master.judge
            SET jcode=$jcode, jname='$jname', first_name='$first_name', title='$head', sur_name='$sur_name', jcourt=$jcourt, abbreviation='$abbreviation', 
            is_retired='$retired', display='$display', 
                  appointment_date='$from_date', to_dt='$to_date', cji_date='$cji_date', jtype='$jtype', entuser=$usercode, entdt=now(), judge_seniority=$judge_seniority
            WHERE jcode=$jcode;";

        $query = $this->db->query($sql);
        if ($query == true) {
            return   $inserted = 1;
        } else {
            return $inserted = 0;
        }
    }



    function judgesname($jtype)
    {

        $sql = "select jname, jcode, first_name, sur_name, jtype from master.judge where jtype='$jtype' and display='Y' order by is_retired asc, jcode desc";
        $query = $this->db->query($sql);
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }



    function jsearch($jcode = null)
    {
        $sql = "SELECT *,
            TO_CHAR(appointment_date, 'DD-MM-YYYY') AS appointment_date,
            TO_CHAR(to_dt, 'DD-MM-YYYY') AS to_dt,
            TO_CHAR(cji_date, 'DD-MM-YYYY') AS cji_date,
            CASE
                WHEN title = 'REGISTRAR' THEN '1'
                WHEN title = 'HON''BLE MR. JUSTICE' THEN '2'
                WHEN title = 'HON''BLE MRS. JUSTICE' THEN '3'
                WHEN title = 'HON''BLE MS. JUSTICE' THEN '4'
                WHEN title = 'HON''BLE KUMARI JUSTICE' THEN '5'
                WHEN title = 'HON''BLE DR. JUSTICE' THEN '6'
                ELSE '7'
            END AS head
        FROM master.judge 
        WHERE jcode = $jcode;";

        $query = $this->db->query($sql);
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
}
