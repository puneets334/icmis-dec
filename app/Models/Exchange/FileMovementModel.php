<?php

namespace App\Models\Exchange;

use CodeIgniter\Model;

class FileMovementModel extends Model
{
    protected $eservicesdb;

    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
        $this->eservicesdb = \Config\Database::connect('eservices');
    }

    public function get_case_type()
    {
        $mr = $_REQUEST['mf'];
        if($mr != '')
        {
            if($mr == 'all')
            {
                $sql = "SELECT casecode, short_description FROM master.casetype WHERE display = 'Y' AND casecode NOT IN (9999, 30, 31) ORDER BY short_description";
            }
            else
            {
                $sql = "SELECT casecode, short_description FROM master.casetype WHERE display = 'Y' AND casecode NOT IN (9999, 30, 31) AND cs_m_f = '$mr' ORDER BY short_description";
            }
        }

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1)
        {
            return $query->getResultArray();
        }
        else
        {
            return [];
        }
    }

    public function dispatch_receive_report_process()
    {
        $usercode = session()->get('login')['usercode'];

        $rd = $_REQUEST['rd'];
        $mf = $_REQUEST['mf'];
        $rur = $_REQUEST['rur'];
        $ct = $_REQUEST['ct'];
        $fdt = $_REQUEST['dt1'];
        $tdt = $_REQUEST['dt2'];

        $fdt = date('Y-m-d', strtotime($fdt));
        $tdt = date('Y-m-d', strtotime($tdt));

        $criteria = "";

        if ($rd == 'R')
        {
            $criteria = " t1.rece_dt BETWEEN '$fdt' AND '$tdt' ";
            $criteria .= " AND t1.rece_by = $usercode ";
            $rdt = "RECEIVED";
        }
        elseif ($rd == 'D')
        {
            $criteria = " t1.disp_dt BETWEEN '$fdt' AND '$tdt' ";
            $criteria .= " AND t1.disp_by = $usercode ";
            $rdt = "DISPATCHED";
        }

        if($mf == 'M' && $rur != 'U')
        {
            $criteria .= " AND m.mf_active = 'M' ";
        }
        elseif($mf == 'F' && $rur != 'U')
        {
            $criteria .= " AND m.mf_active = 'F' ";
        }

        if($rur == 'R')
        {
            $criteria .= " AND m.fil_no IS NOT NULL AND m.fil_no != '' ";
        }
        elseif ($rur == 'U')
        {
            $criteria .= " AND m.fil_no IS NULL OR m.fil_no = '' ";
        }

        if($ct != 'all' && $rur != 'U')
        {
            $criteria .= " AND m.active_casetype_id = $ct ";
        }

        if($rd == 'R')
        {
            $criteria .= " ORDER BY t1.rece_dt ASC ";
        }
        elseif($rd == 'D')
        {
            $criteria .= " ORDER BY t1.disp_dt ASC ";
        }

        $sql_cnt = "
        SELECT COUNT(*)
        FROM (
            SELECT DISTINCT
                t1.id,
                t1.diary_copy_set,
                t1.disp_by,
                t1.disp_to,
                t1.disp_dt,
                t1.rece_by,
                t1.rece_dt,
                t1.c_l,
                t1.remark,
                t1.flag
            FROM diary_movement t1
            INNER JOIN diary_copy_set dcs ON dcs.id = t1.diary_copy_set
            INNER JOIN main m ON m.diary_no = dcs.diary_no
            WHERE $criteria
        ) t2";

        $query = $this->db->query($sql_cnt);
        if ($query->getNumRows() >= 1)
        {
            return $query->getRowArray();
        }
        else
        {
            return [];
        }
    }

    public function getCasesForDispatch()
    {
        $queryString = "SELECT casecode, skey, casename, short_description FROM master.casetype WHERE display = 'Y' AND casecode != 9999 ORDER BY short_description";
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

    public function get_s_file_rec($ucode,$module)
    {
        $mul_category = "";
        $act_section = "";
        $main_case = "";
        $t_slpcc = "";
        $t_spl = "";
        $html = '';
        if(isset($_REQUEST['ct']) && $_REQUEST['ct'] != '')
        {
            $ct = $_REQUEST['ct'];
            $cn = $_REQUEST['cn'];
            $cy = $_REQUEST['cy'];

            $queryString= "SELECT 
                SUBSTR(diary_no::TEXT, 1, LENGTH(diary_no::TEXT) - 4) AS dn, 
                SUBSTR(diary_no::TEXT, -4) AS dy
            FROM main 
            WHERE (
                    SPLIT_PART(fil_no, '-', 1) <> '' AND
                    SPLIT_PART(fil_no, '-', 1)::INTEGER = $ct
                    AND SPLIT_PART(fil_no, '-', 2) <> '' AND 
                    SPLIT_PART(fil_no, '-', 3) <> '' AND
                    CAST('$cn' AS INTEGER) BETWEEN 
                        CAST(SPLIT_PART(fil_no, '-', 2) AS INTEGER) AND 
                        CAST(SPLIT_PART(fil_no, '-', 3) AS INTEGER)
                    AND (
                        (reg_year_mh = 0 OR fil_dt > DATE '2017-05-10' AND EXTRACT(YEAR FROM fil_dt) = $cy) 
                        OR reg_year_mh = $cy
                    )
            ) 
            OR (
                    SPLIT_PART(fil_no_fh, '-', 1) <> '' AND
                    SPLIT_PART(fil_no_fh, '-', 1)::INTEGER = $ct
                    AND SPLIT_PART(fil_no_fh, '-', 2) <> '' AND 
                    SPLIT_PART(fil_no_fh, '-', 3) <> '' AND
                    CAST('$cn' AS INTEGER) BETWEEN 
                        CAST(SPLIT_PART(fil_no_fh, '-', 2) AS INTEGER) AND 
                        CAST(SPLIT_PART(fil_no_fh, '-', 3) AS INTEGER)
                    AND (reg_year_fh = 0 AND EXTRACT(YEAR FROM fil_dt_fh) = $cy OR reg_year_fh = $cy)
            )";

            $query = $this->db->query($queryString);
            // pr($query->getRowArray());
            if ($query->getNumRows() > 0)
            {
                $get_dno = $query->getRowArray();
                $_REQUEST['d_no'] = $get_dno['dn'];
                $_REQUEST['d_yr'] = $get_dno['dy'];
            }
            else
            {
                $queryString2 = "SELECT 
                    SUBSTR(h.diary_no::TEXT, 1, LENGTH(h.diary_no::TEXT) - 4) AS dn, 
                    SUBSTR(h.diary_no::TEXT, -4) AS dy,
                    CASE 
                        WHEN h.new_registration_number != '' THEN SPLIT_PART(h.new_registration_number, '-', 1)
                        ELSE ''
                    END AS ct1, 
                    CASE 
                        WHEN h.new_registration_number != '' THEN SPLIT_PART(h.new_registration_number, '-', 2)
                        ELSE ''
                    END AS crf1, 
                    CASE 
                        WHEN h.new_registration_number != '' THEN SPLIT_PART(h.new_registration_number, '-', 3)
                        ELSE ''
                    END AS crl1 
                FROM 
                    main_casetype_history h 
                WHERE 
                    (
                        (
                            SPLIT_PART(h.new_registration_number, '-', 1) <> ''
                            AND SPLIT_PART(h.new_registration_number, '-', 1)::INTEGER = $ct
                            AND SPLIT_PART(h.new_registration_number, '-', 2) <> '' 
                            AND SPLIT_PART(h.new_registration_number, '-', 3) <> '' 
                            AND CAST('$cn' AS INTEGER) BETWEEN 
                            (CAST(SPLIT_PART(h.new_registration_number, '-', 2) AS INTEGER)) 
                            AND (CAST(SPLIT_PART(h.new_registration_number, '-', 3) AS INTEGER)) 
                            AND h.new_registration_year = '$cy'
                        )
                    OR 
                        (
                            SPLIT_PART(h.old_registration_number, '-', 1) <> ''
                            AND SPLIT_PART(h.old_registration_number, '-', 1)::INTEGER = $ct
                            AND SPLIT_PART(h.old_registration_number, '-', 2) <> '' 
                            AND SPLIT_PART(h.old_registration_number, '-', 3) <> '' 
                            AND CAST('$cn' AS INTEGER) BETWEEN 
                            (CAST(SPLIT_PART(h.old_registration_number, '-', 2) AS INTEGER)) 
                            AND (CAST(SPLIT_PART(h.old_registration_number, '-', 3) AS INTEGER)) 
                            AND h.old_registration_year = '$cy' 
                        )
                    )
                    AND h.is_deleted = 'f'";

                $query2 = $this->db->query($queryString2);

                // pr($query2->getRowArray());

                if ($query2->getNumRows() > 0)
                {
                    $get_dno = $query2->getRowArray();
                    $_REQUEST['d_no'] = $get_dno['dn'];
                    $_REQUEST['d_yr'] = $get_dno['dy'];

                    $sql_ct_type = $this->db->query("
                        SELECT short_description 
                        FROM master.casetype 
                        WHERE casecode = 3 AND display = 'Y'
                    ");

                    if ($sql_ct_type->getNumRows() > 0)
                    {
                        $res_ct_typ = $sql_ct_type->getRow()->short_description;
                        $t_slpcc = $res_ct_typ . " " . $get_dno['crf1'] . " - " . $get_dno['crl1'] . " / " . $cy;
                    }
                }
                else
                {
                    $html .= '<p align=center><font color=red>Case Not Found</font></p>';
                    return $html;
                }
            }
        }
        if($_REQUEST['d_no']!='' && $_REQUEST['d_yr']!='')
        {
            $d_no = $_REQUEST['d_no'];
            $d_yr = $_REQUEST['d_yr'];
            $diaryNo = strval($d_no).''.strval($d_yr);

            $sql = "SELECT 
                diary_no,
                conn_key,
                fil_dt,
                EXTRACT(YEAR FROM fil_dt) AS filyr,
                TO_CHAR(fil_dt, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_f,
                fil_no_fh,
                TO_CHAR(fil_dt_fh, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_fh,
                actcode,
                pet_adv_id,
                res_adv_id,
                lastorder,
                c_status,

                CASE 
                  WHEN diary_no IS NOT NULL THEN SPLIT_PART(diary_no::TEXT, '-', 1) 
                  ELSE NULL 
                END AS ct1,
                CASE 
                  WHEN diary_no IS NOT NULL AND SPLIT_PART(diary_no::TEXT, '-', 2) IS NOT NULL AND SPLIT_PART(diary_no::TEXT, '-', 2) != '' 
                  THEN SPLIT_PART(diary_no::TEXT, '-', 2)
                  ELSE NULL 
                END AS crf1,
                CASE 
                  WHEN diary_no IS NOT NULL AND SPLIT_PART(diary_no::TEXT, '-', 3) IS NOT NULL AND SPLIT_PART(diary_no::TEXT, '-', 3) != '' 
                  THEN SPLIT_PART(diary_no::TEXT, '-', 3) 
                  ELSE NULL 
                END AS crl1,
                CASE 
                  WHEN fil_no_fh IS NOT NULL THEN SPLIT_PART(fil_no_fh, '-', 1) 
                  ELSE NULL 
                END AS ct2,
                CASE 
                  WHEN fil_no_fh IS NOT NULL AND SPLIT_PART(fil_no_fh, '-', 2) IS NOT NULL AND SPLIT_PART(fil_no_fh, '-', 2) != '' 
                  THEN SPLIT_PART(fil_no_fh, '-', 2) 
                  ELSE NULL 
                END AS crf2,
                CASE 
                  WHEN fil_no_fh IS NOT NULL AND SPLIT_PART(fil_no_fh, '-', 3) IS NOT NULL AND SPLIT_PART(fil_no_fh, '-', 3) != '' 
                  THEN SPLIT_PART(fil_no_fh, '-', 3) 
                  ELSE NULL 
                END AS crl2,
                CASE 
                  WHEN conn_key IS NOT NULL THEN 
                      CASE WHEN conn_key = diary_no::TEXT THEN 'N' ELSE 'Y' END 
                  ELSE 'N' 
                END AS ccdet,
                casetype_id,
                conn_key AS connto 
            FROM 
                main 
            WHERE 
                diary_no = '$diaryNo'";
                // pr($sql);

            $query3 = $this->db->query($sql);
            $main_fh_diary_no = "";
            if ($query3->getNumRows() > 0)
            {
                $fil_no = $query3->getRowArray();
                $isconn = $fil_no["ccdet"];
                $connto = $fil_no["connto"];
                $diaryno = $fil_no["diary_no"];

                if ( $fil_no["diary_no"] != $fil_no["conn_key"] && $fil_no["conn_key"] != "" )
                {
                    $check_for_conn = "N";
                }
                else
                {
                    $check_for_conn = "Y";
                }
                if ($fil_no["fil_no_fh"] != "")
                {
                    $main_fh_diary_no = "EXIST";
                }

                $conn_type = "";

                $diary_no = $_REQUEST["d_no"] . "/" . $_REQUEST["d_yr"];

                if ($fil_no["conn_key"] != "")
                {
                    if ($fil_no["conn_key"] == $fil_no["diary_no"])
                    {
                        $conn_type = "M";
                    }
                    else
                    {
                        $conn_type = "C";
                    }
                }

                $html .= '<div style="text-align: center"><strong>Diary No.- '.$_REQUEST["d_no"].' - '.$_REQUEST["d_yr"].'</strong></div>';

                $query4 = "SELECT 
                    fil_dt, 
                    COALESCE(TO_CHAR(last_dt, 'DD-MM-YYYY HH12:MI AM'), '') AS last_dt, 
                    a.usercode, 
                    COALESCE(CAST(last_usercode AS TEXT), '') AS last_usercode, 
                    b.name AS user, 
                    c.name AS last_u
                FROM 
                    main a
                LEFT JOIN 
                    master.users b ON a.usercode = b.usercode
                LEFT JOIN 
                    master.users c ON a.last_usercode = c.usercode
                WHERE 
                    diary_no = $diaryno";

                $fil_date_for = $this->db->query($query4)->getRowArray();

                $html .= '<table border="0"  align="left" width="100%">';

                if ($main_case != "")
                {
                    $main_case ="<br>&nbsp;&nbsp;<font color='red' >[Connected with : " . $main_case . "</font>]";
                }

                $u_name = "";

                $sql_da = "SELECT a.usercode, b.name, us.section_name 
                FROM 
                    main a 
                LEFT JOIN 
                    master.users b ON a.usercode = b.usercode 
                LEFT JOIN 
                    master.usersection us ON b.section = us.id 
                WHERE 
                    diary_no = $diaryno";

                $query5 = $this->db->query($sql_da);
                if($query5->getNumRows() > 0)
                {
                    $row_da = $query5->getRowArray();
                    $u_name .= " by <font color='blue'>" . $row_da["name"] . "</font>";
                    $u_name .= "<font> [SECTION: </font><font color='red'>" . $row_da["section_name"] . "</font>]<font style='font-size:12px;font-weight:bold;'></font>";
                }

                $t_res_ct_typ = $this->db->query("SELECT short_description FROM master.casetype WHERE casecode = " . $fil_no['casetype_id'] . " AND display = 'Y'")->getRowArray();
                $res_ct_typ = $t_res_ct_typ["short_description"];

                $queryString3 = "SELECT 
                    SUBSTR(m.diary_no::TEXT, 1, LENGTH(m.diary_no::TEXT) - 4) AS case_no, 
                    SUBSTR(m.diary_no::TEXT, -4) AS year,
                    p.sr_no, 
                    p.pet_res, 
                    p.ind_dep, 
                    p.partyname, 
                    p.sonof, 
                    p.prfhname, 
                    p.age, 
                    p.sex, 
                    p.caste, 
                    p.addr1, 
                    p.addr2,
                    p.pin, 
                    p.state, 
                    p.city, 
                    p.email, 
                    p.contact AS mobile,
                    p.deptcode,
                    (
                        SELECT deptname FROM master.deptt WHERE deptcode = p.deptcode
                    ) AS deptname,
                    c.skey, 
                    TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY HH12:MI AM') AS diary_no_rec_date
                FROM 
                    party p 
                INNER JOIN 
                    main m ON m.diary_no = p.diary_no AND p.sr_no = 1 AND p.pflag = 'P' AND p.pet_res IN ('P', 'R')
                LEFT JOIN 
                    master.casetype c ON c.casecode::bigint = SUBSTR(m.diary_no::TEXT, 3, 3)::bigint
                WHERE 
                    m.diary_no = '".$fil_no['diary_no']."'
                ORDER BY 
                    p.pet_res, p.sr_no";


                $queryString3Result = $this->db->query($queryString3);
                if($queryString3Result->getNumRows() > 0)
                {
                    $result = $queryString3Result->getResultArray();
                    foreach ($result as $key => $row)
                    {
                        
                        $temp_var = "";
                        $temp_var .= $row["partyname"];
                        if ($row["sonof"] != "")
                        {
                            $temp_var .= $row["sonof"] . "/o " . $row["prfhname"];
                        }

                        if ($row["deptname"] != "")
                        {
                            $temp_var .= "<br>Department : " . $row["deptname"];
                        }

                        $temp_var .= "<br>";
                        if ($row["addr1"] == "")
                        {
                            $temp_var .= $row["addr2"];
                        }
                        else
                        {
                            $temp_var .= $row["addr1"] . ", " . $row["addr2"];
                        }

                        $districtQuery = "SELECT Name as name
                        FROM master.state 
                        WHERE id_no = '".$row['city']."' AND Sub_Dist_code = 0 AND Village_code = 0 AND display = 'Y'";

                        // pr($districtQuery); 
                        $t_dist = $this->db->query($districtQuery)->getRowArray();
                     
                        $t_var = $t_dist["name"];

                        if ($t_var != "")
                        {
                            $temp_var .= ", District : " . $t_var;
                        }

                        if ($row["pet_res"] == "P")
                        {
                            $pet_name = $temp_var;
                        }
                        else
                        {
                            $res_name = $temp_var;
                        }
                        $case_no = $row["case_no"];
                        $year = $row["year"];
                        $diary_no_rec_date = $row["diary_no_rec_date"];
                    }

                    $html .= '<div class="cl_center"><strong>Case Details</strong></div>';
                    $html .= '<table class="table_tr_th_w_clr c_vertical_align" width="100%">';
                    $html .= '<tr>
                        <td width="140px">Diary No.</td>
                        <td>
                            <div width="100%"><font color = "blue" style = "font-size:12px;font-weight:bold;">'.$case_no.'/'.$year.'</font> Received on '.$diary_no_rec_date.' '.$u_name.' '.$main_case.'
                            </div>
                        </td>
                    </tr>';

                    $t_fil_no = get_case_nos($diaryno, "&nbsp;&nbsp;");

                    if (trim($t_fil_no) == "")
                    {
                        $sql12 = $this->db->query("SELECT short_description FROM master.casetype WHERE casecode = " . $fil_no['casetype_id'] . "");
                        if($sql12->getNumRows() > 0)
                        {
                            $row_12 = $sql12->getRowArray();
                            $t_fil_no = $row_12["short_description"];
                        }
                    }
                    if ($t_slpcc != "")
                    {
                       $t_slpcc = "<br>" . $t_slpcc;
                    }

                    $t_fil_no1 = "";

                    $sq_lct = "SELECT  lct_dec_dt, lct_caseno, lct_caseyear, short_description type_sname
                    FROM lowerct a
                    LEFT JOIN master.casetype ct ON ct.casecode = a.lct_casetype AND ct.display = 'Y'
                    WHERE a.diary_no = '" . $diaryno . "' AND lw_display = 'Y' AND ct_code =4 ORDER BY a.lct_dec_dt";

                    $rs_lct = $this->db->query($sq_lct);
                    if($rs_lct->getNumRows() > 0)
                    {
                        $ro_lct = $rs_lct->getResultArray();
                        $t_fil_no1 .= "";
                        foreach ($ro_lct as $key => $value)
                        {
                            if ($t_fil_no1 == "")
                            {
                                $t_fil_no1 .=
                                " IN " .
                                $value["type_sname"] .
                                " - " .
                                $value["lct_caseno"] .
                                "/" .
                                $value["lct_caseyear"];
                            }
                            else
                            {
                                $t_fil_no1 .=
                                ", " .
                                $value["type_sname"] .
                                " - " .
                                $value["lct_caseno"] .
                                "/" .
                                $value["lct_caseyear"];
                            }
                        }
                    }

                    $html .= "<tr>
                        <td>Case No.</td>
                        <td>
                            <div width='100%'>" . $t_fil_no . $t_slpcc . $t_fil_no1 ."</div>
                        </td>
                    </tr>";

                    if ($t_spl != "")
                    {
                        $html .= "<tr>
                            <td>Special Type</td>
                            <td>" . $t_spl . "</td>
                        </tr>";
                    }
                    $html .= "<tr>
                        <td style='width: 15%'> Petitioner </td>
                        <td>" . $pet_name . "</td>
                    </tr>
                    <tr>
                        <td style='width: 15%'> Respondant </td>
                        <td>" . $res_name . "</td>
                    </tr>";

                    $case_category = "";
                    $mul_category = get_mul_category($fil_no["diary_no"]);

                    $html .= "<tr>
                        <td style='width: 15%'> Case Category </td>
                        <td>" . $mul_category . " </td>
                    </tr>";

                    $act_query = "SELECT a.act, STRING_AGG(b.section, ', ') AS section, act_name 
                    FROM 
                        act_main a 
                    LEFT JOIN 
                        master.act_section b ON a.id = b.act_id 
                    JOIN 
                        master.act_master c ON c.id = a.act 
                    WHERE 
                        diary_no = " . $fil_no['diary_no'] . " AND a.display = 'Y' AND b.display = 'Y' AND c.display = 'Y' 
                    GROUP BY 
                        a.act, act_name";

                    $act = $this->db->query($act_query);

                    if($act->getNumRows() > 0)
                    {
                        $act_result = $act->getResultArray();
                        $act_section = "";
                        foreach ($act_result as $key => $row1)
                        {
                            if ($act_section == "")
                            {
                                $act_section = $row1["act_name"] . "-" . $row1["section"];
                            }
                            else
                            {
                                $act_section =
                                    $act_section .
                                    ", " .
                                    $row1["act_name"] .
                                    "-" .
                                    $row1["section"];
                            }
                        }
                    }

                    $html .= "<tr>
                        <td>Act</td>
                        <td>" . $act_section . " </td>
                    </tr>";

                    $t_pol = $this->db->query("SELECT law FROM master.caselaw WHERE id = ?", [$fil_no['actcode']])->getRowArray();
                    if(!empty($t_pol))
                    {
                        $pov_of_law = $t_pol["law"];
                    }
                    else
                    {
                        $pov_of_law = '';
                    }

                    $html .= "<tr>
                        <td>Provision of Law</td>
                        <td>" . $pov_of_law . " </td>
                    </tr>";

                    $pet_adv = get_advocates_new($fil_no["pet_adv_id"], "wen");
                    if(!empty($pet_adv))
                    {
                        $html .= "<tr>
                            <td style='width: 15%'>Petitioner Advocate</td>
                            <td>" . $pet_adv . " </td>
                        </tr>";
                    }
                    else
                    {
                        $html .= "<tr>
                            <td style='width: 15%'>Petitioner Advocate</td>
                            <td></td>
                        </tr>";
                    }

                    $res_adv = get_advocates_new($fil_no["res_adv_id"], "wen");
                    if(!empty($res_adv))
                    {
                        $html .= "<tr>
                            <td style='width: 15%'>Respondant Advocate</td>
                            <td>" . $res_adv . " </td>
                        </tr>";
                    }
                    else
                    {
                        $html .= "<tr>
                            <td style='width: 15%'>Respondant Advocate</td>
                            <td></td>
                        </tr>";
                    }

                    $html .= "<tr>
                            <td style='width: 15%'>Last Order</td>
                            <td>" . $fil_no["lastorder"] . " </td>
                    </tr>";

                    if ($fil_no["c_status"] == "P")
                    {
                        $rgo_sql = $this->db->table('rgo_default');
                        $rgo_sql->select('fil_no2')->distinct();  // Use distinct() method
                        $rgo_sql->where('fil_no', $fil_no["diary_no"]);
                        $rgo_sql->where('remove_def', 'N');
                        $rgo_query = $rgo_sql->get();

                        $t_rgo = "";

                        if ($rgo_query->getNumRows() > 0)
                        {
                            foreach ($rgo_query->getResultArray() as $res_rgo)
                            {
                                if ($t_rgo == "")
                                {
                                    $t_rgo = "D.No. " . get_real_diaryno($res_rgo["fil_no2"]) . "<br>" . str_replace("<br>", " ", get_casenos_comma($res_rgo["fil_no2"]));
                                }
                                else
                                {
                                    $t_rgo .= "<br> D.No. " . get_real_diaryno($res_rgo["fil_no2"]) . "<br>" . str_replace("<br>", " ", get_casenos_comma($res_rgo["fil_no2"]));
                                }
                            }
                        }

                        if ($t_rgo != "")
                        {
                            $html .= "<tr>
                                <td>Conditional Dispose</td>
                                <td style='font-size:12px;font-weight:100;'><b> <font style='font-size:12px;font-weight:100;'><b>" .
                                  $t_rgo . "</b></font></b>
                                </td>
                            </tr>";
                        }

                        $ttv = "SELECT tentative_cl_dt FROM heardt WHERE diary_no = ?";
                        $query = $this->db->query($ttv, $fil_no["diary_no"]);
                        $r_ttv = $query->getRowArray();

                        $sql_display = $this->db->query("SELECT display_flag, always_allowed_users FROM master.case_status_flag WHERE to_date IS null AND flag_name = 'tentative_listing_date'")->getRowArray();
                        // print_r($r_ttv);
                        // pr($sql_display);

                        if ( $sql_display["display_flag"] == 1 || in_array($ucode, explode(",", $sql_display["always_allowed_users"])) )
                        {
                            $tentative_date = '';
                            if ( get_display_status_with_date_differnces( $r_ttv["tentative_cl_dt"] ) == "T" )
                            {
                                $tentative_date = date('d-m-Y', strtotime($r_ttv["tentative_cl_dt"]));
                            }

                            $html .= "<tr>
                                <td style='width: 15%'>Tentative Date</td>
                                <td>" . $tentative_date . " </td>
                            </tr>";
                        }

                        if ($isconn == "Y")
                        {
                            $sql_oth_conn_query = "SELECT 
                                m.diary_no,
                                (SELECT list FROM conct cc WHERE cc.diary_no = m.diary_no LIMIT 1) AS llist
                            FROM 
                                main m 
                            WHERE 
                                (m.diary_no::text = '$diaryno' OR m.conn_key IN (SELECT conn_key FROM main WHERE diary_no::text = '$diaryno'))
                                AND m.diary_no::text != m.conn_key
                            ORDER BY 
                                m.fil_dt";

                            $connto = "<font color='red'>" . $connto . " </font>(Main Case)";
                            $sql_oth_conn = $this->db->query($sql_oth_conn_query);

                            if($sql_oth_conn->getNumRows() > 0)
                            {
                                $results_oc = $sql_oth_conn->getResultArray();
                                foreach ($results_oc as $key => $row_oc)
                                {
                                    $connto .= "<br><font color='blue'>" . $row_oc["diary_no"] . " </font>(Connected Case)";
                                }
                            }

                            $html .= "<tr valign='top'><td bgcolor='#F4F5F5'>Connected To </td><td><b>" . $connto . "</b></td></tr>";
                        }
                    }
                    else
                    {
                        $html .= '<tr>
                            <td>Case Status </td>
                            <td><font color=red>Case is Disposed</font></td>
                        </tr>';
                    }
                    $html .= '</table>';
                }

                if ($conn_type == "")
                {
                    $html .= get_diary_set_fm($fil_no["diary_no"], $module, $conn_type);
                }
                else
                {
                    $conncases = get_conn_cases($fil_no["diary_no"]);

                    foreach ($conncases as $row => $link)
                    {
                        if ($link["c_type"] != "")
                        {
                            if ($link["c_type"] == "M")
                            {
                               $html .= "<b>Main Case</b>";
                            }
                            if ($link["c_type"] == "C")
                            {
                               $html .= "<b>Connected Case</b>";
                            }
                            if ($link["c_type"] == "L")
                            {
                               $html .= "<b>Linked Cases</b>";
                            }
                            $html .= get_diary_set_fm($link["diary_no"], $module, $link["c_type"]);
                        }
                    }
                }

                if ($module == "receive")
                {
                    $html .= '<p align="center"><input type="button" name="receive" id="receive" value="Receive File"/></p>';
                }
                if ($module == "dispatch")
                {
                    $usercode = session()->get('login')['usercode'];
                    $usertype_query = $this->db->table('master.users')->select('usertype, udept')->where('usercode', $usercode)->get()->getRowArray();

                    if ($usertype_query)
                    {
                        /*$dept_query = $this->db->table('master.users')->select('DISTINCT udept, dept_name')->join('master.userdept', 'udept = userdept.id', 'left')->orderBy('udept')->get()->getResultArray();*/

                        $dept_query = $this->db->table('master.users')->distinct()->select('udept, dept_name')->join('master.userdept', 'users.udept = userdept.id', 'left')->orderBy('udept')->get()->getResultArray();
                    }

                    // Start generating HTML
                    $html .= '<div id="user_div"> 
                                <div class="inner_1">
                                    <label class="cl_wh">DEPARTMENT</label>
                                    <input type="hidden" value="' . esc($usertype_query['usertype']) . '" id="cur_user_type"/>
                                    <select class="form-control" id="department">';

                    // Add options based on user type
                    if ($usertype_query['usertype'] == 1)
                    {
                        $html .= '<option value="ALL">ALL</option>';
                    }
                    else
                    {
                        $html .= '<option value="">SELECT</option>';
                    }

                    // Populate department options
                    foreach ($dept_query as $dept_row)
                    {
                        $html .= '<option value="' . esc($dept_row['udept']) . '">' . esc($dept_row['dept_name']) . '</option>';
                    }

                    $html .= '</select></div>';

                    // SECTION dropdown
                    $html .= '<div class="inner_1">
                        <label class="cl_wh">SECTION</label>
                        <select class="form-control" id="section">';

                    if ($usertype_query['usertype'] == 1)
                    {
                        $html .= '<option value="ALL">ALL</option>';
                    }
                    else
                    {
                        $html .= '<option value="0">SELECT</option>';
                    }

                    $html .= '</select></div>';

                    // DESIGNATION dropdown
                    $html .= '<div class="inner_1">
                                <label class="cl_wh">DESIGNATION</label>
                                <select class="form-control" id="designation"><option value="ALL">ALL</option></select>
                              </div>';

                    // USER dropdown
                    $html .= '<div class="inner_1">
                                <label class="cl_wh">USER</label>
                                <select class="form-control" id="user"></select>
                              </div>';

                    $html .= '</div><br><br><br><br>';

                    $html .= '<div class="col-md-12" style="padding-top: 10px;"><p align="center"><input type="button" name="dispatch" id="dispatch" value="Dispatch File"/></p></div>';
                }
                return $html;
            }
            else
            {
                $html .= '<p align="center"><font color=red>Case Details Not Found</font></p>';
                return $html;
            }
        }
    }

    public function user_options()
    {
        $ucode = session()->get('login')['usercode'];
        $dept = !empty($_REQUEST['dept']) ? $_REQUEST['dept'] : '';
        $sec = !empty($_REQUEST['sec']) ? $_REQUEST['sec'] : '';
        $desig = !empty($_REQUEST['desig']) ? $_REQUEST['desig'] : '';

        $builder = $this->db->table('master.users');

        $builder->where('usercode !=', $ucode);
        $builder->where('display', 'Y');
        $builder->where('attend', 'P');

        if ($dept !== "" && $dept !== "ALL")
        {
            $builder->where('udept', $dept);
        }

        if ($sec !== "" && $sec !== "ALL")
        {
            $builder->where('section', $sec);
        }

        if ($desig !== "" && $desig !== "ALL")
        {
            $builder->where('usertype', $desig);
        }

        $builder->distinct()->select('name, usercode');

        $builder->orderBy('name');

        $section = $builder->get()->getResultArray();

        $option = '';
        foreach($section as $key => $row_sec)
        {
            $option .= '<option value = "'.trim($row_sec['usercode']).'">'.trim($row_sec['name']).'</option>';
        }
        return $option;
    }

    public function user_mgmt_multiple()
    {
        // pr($_REQUEST);
        $dynamic_option = '';
        if($_REQUEST['key']==1)
        {
            if(isset($_REQUEST['setter']) && $_REQUEST['setter'] != 'L')
            {
                if($_REQUEST['cur_user_type']==1 /*|| $_REQUEST['cur_user_type']==16*/)
                {
                    // $section = "SELECT DISTINCT section,section_name FROM users a LEFT JOIN usersection b ON section=b.id WHERE udept='$_REQUEST[deptname]' ORDER BY section";
                    $section_query = "SELECT DISTINCT section, section_name FROM master.users a LEFT JOIN master.usersection b ON section = b.id WHERE udept = '".$_REQUEST['deptname']."' ORDER BY section";
                    echo 'if';
                    pr($section_query);
                }
                else
                {
                    // $section = "SELECT DISTINCT section,section_name FROM users a LEFT JOIN usersection b ON section=b.id WHERE udept='$_REQUEST[deptname]' AND usertype != 2 ORDER BY section";
                    $section_query = "SELECT DISTINCT section,section_name FROM master.users a LEFT JOIN master.usersection b ON section=b.id WHERE udept = '".$_REQUEST['deptname']."' AND usertype != 2 ORDER BY section";
                }

                $section = $this->db()->query($section_query);
                if($section->getNumRows() > 0)
                {
                    $section_result = $section->getResultArray();
                    $dynamic_option .= '<option value="ALL">ALL</option>';

                    /*foreach ($section_result as $key => $row_sec)
                    {
                        $dynamic_option .= '<option value = "'.$row_sec['section']).'">"'.$row_sec['section_name'].'"</option>';
                    }*/

                    foreach ($section_result as $key => $row_sec)
                    {
                        $dynamic_option .= '<option value="' . esc($row_sec['section']) . '">' . esc($row_sec['section_name']) . '</option>';
                    }
                }
                else
                {
                    echo "RESET";
                    if($_REQUEST['cur_user_type']==1)
                    {
                        $dynamic_option .= '<option value="ALL">ALL</option>';
                    }
                    else
                    {
                        $dynamic_option .= '<option value="0">SELECT</option>';
                    }
                }
                return $dynamic_option;
            }
            else if(isset($_REQUEST['setter']) && $_REQUEST['setter'] == 'L')
            {
                // $section = "SELECT utype FROM user_d_t_map WHERE udept = '$_REQUEST[deptname]' ORDER BY utype";
                $section_query = "SELECT utype FROM user_d_t_map WHERE udept = '".$_REQUEST['deptname']."' ORDER BY utype";
                $section = $this->db()->query($section_query);
                if($section->getNumRows() > 0)
                {
                    $section_result = $section->getResultArray();

                    $dynamic_option .= '<option value="0">SELECT</option>';

                    /*foreach ($section_result as $key => $row_sec)
                    {
                        $dynamic_option .= '<option value = "'.$row_sec['utype']).'">"'.displayUsertype($row_sec['utype']).'"</option>';
                    }*/

                    foreach ($section_result as $key => $row_sec)
                    {
                        $dynamic_option .= '<option value="' . esc($row_sec['utype']) . '">' . esc(displayUsertype($row_sec['utype'])) . '</option>';
                    }
                }
                else
                {
                    $dynamic_option .= '<option value="0">SELECT</option>';
                }
                return $dynamic_option;
            }
        }
    }

    public function save_record()
    {
        $ucode = session()->get('login')['usercode'];
        $module = $_REQUEST['module'];
        $user = isset($_REQUEST['user']) ? $_REQUEST['user'] : '';
        $chk_arr = $_REQUEST['chk1'];
        if($ucode != "" && $module != "")
        {
            foreach ($chk_arr as $value)
            {
                $t_data = explode("-", $value);
                if ($module == 'dispatch')
                {
                    if ($user != "")
                    {
                        if ($t_data[1] == 1)
                        {
                            $data =
                            [
                                'diary_copy_set' => $t_data[0],
                                'disp_by' => $ucode,
                                'disp_to' => $user,
                                'disp_dt' => date('Y-m-d H:i:s'), // Use current date and time
                                'rece_by' => '0',
                                'rece_dt' => Null,
                                'c_l' => $t_data[2],
                                'remark' => $t_data[3],
                                'flag' => '0',
                            ];

                            $this->db->table('diary_movement')->insert($data);
                        }
                        if ($t_data[1] == 4)
                        {
                            // $this->db->query("INSERT INTO diary_movement_history SELECT *, NOW() FROM diary_movement WHERE diary_copy_set = ?", [$t_data[0]]);
                           
                             $this->db->query("INSERT INTO diary_movement_history (
                                            id, diary_copy_set, disp_by, disp_to, 
                                            disp_dt, rece_by, rece_dt, c_l, remark, 
                                            flag, updated_by_ip, updated_by, 
                                            updated_on, create_modify, ent_dt
                                            ) 
                                            SELECT 
                                            id, 
                                            diary_copy_set, 
                                            disp_by, 
                                            disp_to, 
                                            disp_dt, 
                                            rece_by, 
                                            rece_dt, 
                                            c_l, 
                                            remark, 
                                            flag, 
                                            updated_by_ip, 
                                            updated_by, 
                                            updated_on, 
                                            create_modify, 
                                            (
                                                CURRENT_DATE :: timestamp with time zone
                                            ) AT TIME ZONE 'UTC' 
                                            FROM 
                                            diary_movement 
                                            WHERE 
                                            diary_copy_set = ?",$t_data[0]);

                            // Delete from diary_movement
                            $this->db->table('diary_movement')->where('diary_copy_set', $t_data[0])->delete();

                            // Insert into diary_movement
                            $data =
                            [
                                'diary_copy_set' => $t_data[0],
                                'disp_by' => $ucode,
                                'disp_to' => $user,
                                'disp_dt' => date('Y-m-d H:i:s'), // Use current date and time
                                'rece_by' => '0',
                                'rece_dt' => Null,
                                'c_l' => $t_data[2],
                                'remark' => $t_data[3],
                                'flag' => '0',
                            ];

                            $this->db->table('diary_movement')->insert($data);
                        }
                    }
                }

                if ($module == 'receive')
                {
                    if ($t_data[1] == 1)
                    {
                        // Insert into diary_movement
                        $data = [
                            'diary_copy_set' => $t_data[0],
                            'disp_by' => '0',
                            'disp_to' => '0',
                            'disp_dt' => Null,
                            'rece_by' => $ucode,
                            'rece_dt' => date('Y-m-d H:i:s'), // Set current date and time
                            'c_l' => $t_data[2],
                            'flag' => '0',
                        ];

                        $this->db->table('diary_movement')->insert($data);
                    }
                    elseif ($t_data[1] == 4)
                    {
                        // Update diary_movement
                        $this->db->table('diary_movement')->where('diary_copy_set', $t_data[0])->update([
                            'rece_by' => $ucode,
                            'rece_dt' => date('Y-m-d H:i:s'), // Set current date and time
                            'c_l' => '',
                            'flag' => '0',
                        ]);
                    }
                }
            }
        }
    }


    public function get_case_details_by_case_no($ct, $cn, $cy)
    {
        $return = [];
        if(isset($ct) && $ct != '') {
            $builder = $this->db->table('main');
            $builder->groupStart()
                //->where("NULLIF(split_part(fil_no, '-', 1), '')::INTEGER =", $ct)
                //->where("$cn BETWEEN NULLIF(split_part(fil_no, '-', 2), '')::INTEGER AND NULLIF(split_part(fil_no, '-', -1), '')::INTEGER")
                ->where("(CASE WHEN SPLIT_PART(fil_no, '-', 1) ~ '^[0-9]+$' THEN SPLIT_PART(fil_no, '-', 1)::INTEGER ELSE 0 END)", $ct)
                ->where("CAST('{$cn}' AS INTEGER) BETWEEN 
                        (CASE
                        WHEN SPLIT_PART(fil_no, '-', 2) ~ '^[0-9]+$'
                            THEN SPLIT_PART(fil_no, '-', 2)::INTEGER ELSE 0 END) 
                    AND 
                        (CASE
                        WHEN SPLIT_PART(fil_no, '-', -1) ~ '^[0-9]+$'
                            THEN SPLIT_PART(fil_no, '-', -1)::INTEGER
                    ELSE 0 END)")
                ->groupStart()
                    ->where('reg_year_mh', 0)
                    ->orWhere('fil_dt >', '2017-05-10')
                ->groupEnd()
                ->where("EXTRACT(YEAR FROM fil_dt) =", $cy)
            ->groupEnd();

            $builder->orGroupStart()
                //->where("NULLIF(split_part(fil_no_fh, '-', 1), '')::INTEGER =", $ct)
                //->where("$cn BETWEEN NULLIF(split_part(fil_no_fh, '-', 2), '')::INTEGER AND NULLIF(split_part(fil_no_fh, '-', -1), '')::INTEGER")
                ->where("(CASE WHEN SPLIT_PART(fil_no_fh, '-', 1) ~ '^[0-9]+$' THEN SPLIT_PART(fil_no_fh, '-', 1)::INTEGER ELSE 0 END)", $ct)
                ->where("CAST('{$cn}' AS INTEGER) BETWEEN 
                        (CASE
                        WHEN SPLIT_PART(fil_no_fh, '-', 2) ~ '^[0-9]+$'
                            THEN SPLIT_PART(fil_no_fh, '-', 2)::INTEGER ELSE 0 END) 
                    AND 
                        (CASE
                        WHEN SPLIT_PART(fil_no_fh, '-', -1) ~ '^[0-9]+$'
                            THEN SPLIT_PART(fil_no_fh, '-', -1)::INTEGER
                    ELSE 0 END)")


                ->where('reg_year_fh', 0)
                ->where("EXTRACT(YEAR FROM fil_dt_fh) =", $cy)
            ->groupEnd();

            $builder->select([
                "SUBSTRING(diary_no::TEXT FROM 1 FOR CHAR_LENGTH(diary_no::TEXT) - 4) AS dn",
                "SUBSTRING(diary_no::TEXT FROM CHAR_LENGTH(diary_no::TEXT) - 3 FOR 4) AS dy"
            ]);
            $return = $builder->get()->getRowArray();
        }    
               
        return $return;
    }

    public function get_s_file_rec1($ucode,$module)
    {
        $mul_category = "";
        $act_section = "";
        $main_case = "";
        $t_slpcc = "";
        $t_spl = "";
        $html = '';
        if(isset($_REQUEST['ct']) && $_REQUEST['ct'] != '')
        {
            $ct = $_REQUEST['ct'];
            $cn = $_REQUEST['cn'];
            $cy = $_REQUEST['cy'];

            $queryString= "SELECT 
                SUBSTR(diary_no::TEXT, 1, LENGTH(diary_no::TEXT) - 4) AS dn, 
                SUBSTR(diary_no::TEXT, -4) AS dy
            FROM main 
            WHERE (
                    SPLIT_PART(fil_no, '-', 1) <> '' AND
                    SPLIT_PART(fil_no, '-', 1)::INTEGER = $ct
                    AND SPLIT_PART(fil_no, '-', 2) <> '' AND 
                    SPLIT_PART(fil_no, '-', 3) <> '' AND
                    CAST('$cn' AS INTEGER) BETWEEN 
                        CAST(SPLIT_PART(fil_no, '-', 2) AS INTEGER) AND 
                        CAST(SPLIT_PART(fil_no, '-', 3) AS INTEGER)
                    AND (
                        (reg_year_mh = 0 OR fil_dt > DATE '2017-05-10' AND EXTRACT(YEAR FROM fil_dt) = $cy) 
                        OR reg_year_mh = $cy
                    )
            ) 
            OR (
                    SPLIT_PART(fil_no_fh, '-', 1) <> '' AND
                    SPLIT_PART(fil_no_fh, '-', 1)::INTEGER = $ct
                    AND SPLIT_PART(fil_no_fh, '-', 2) <> '' AND 
                    SPLIT_PART(fil_no_fh, '-', 3) <> '' AND
                    CAST('$cn' AS INTEGER) BETWEEN 
                        CAST(SPLIT_PART(fil_no_fh, '-', 2) AS INTEGER) AND 
                        CAST(SPLIT_PART(fil_no_fh, '-', 3) AS INTEGER)
                    AND (reg_year_fh = 0 AND EXTRACT(YEAR FROM fil_dt_fh) = $cy OR reg_year_fh = $cy)
            )";
            
            $query = $this->db->query($queryString);
            
            if ($query->getNumRows() > 0)
            {
                $get_dno = $query->getRowArray();
                $_REQUEST['d_no'] = $get_dno['dn'];
                $_REQUEST['d_yr'] = $get_dno['dy'];
            }
            else
            {
                $queryString2 = "SELECT 
                    SUBSTR(h.diary_no::TEXT, 1, LENGTH(h.diary_no::TEXT) - 4) AS dn, 
                    SUBSTR(h.diary_no::TEXT, -4) AS dy,
                    CASE 
                        WHEN h.new_registration_number != '' THEN SPLIT_PART(h.new_registration_number, '-', 1)
                        ELSE ''
                    END AS ct1, 
                    CASE 
                        WHEN h.new_registration_number != '' THEN SPLIT_PART(h.new_registration_number, '-', 2)
                        ELSE ''
                    END AS crf1, 
                    CASE 
                        WHEN h.new_registration_number != '' THEN SPLIT_PART(h.new_registration_number, '-', 3)
                        ELSE ''
                    END AS crl1 
                FROM 
                    main_casetype_history h 
                WHERE 
                    (
                        (
                            SPLIT_PART(h.new_registration_number, '-', 1) <> ''
                            AND SPLIT_PART(h.new_registration_number, '-', 1)::INTEGER = $ct
                            AND SPLIT_PART(h.new_registration_number, '-', 2) <> '' 
                            AND SPLIT_PART(h.new_registration_number, '-', 3) <> '' 
                            AND CAST('$cn' AS INTEGER) BETWEEN 
                            (CAST(SPLIT_PART(h.new_registration_number, '-', 2) AS INTEGER)) 
                            AND (CAST(SPLIT_PART(h.new_registration_number, '-', 3) AS INTEGER)) 
                            AND h.new_registration_year = '$cy'
                        )
                    OR 
                        (
                            SPLIT_PART(h.old_registration_number, '-', 1) <> ''
                            AND SPLIT_PART(h.old_registration_number, '-', 1)::INTEGER = $ct
                            AND SPLIT_PART(h.old_registration_number, '-', 2) <> '' 
                            AND SPLIT_PART(h.old_registration_number, '-', 3) <> '' 
                            AND CAST('$cn' AS INTEGER) BETWEEN 
                            (CAST(SPLIT_PART(h.old_registration_number, '-', 2) AS INTEGER)) 
                            AND (CAST(SPLIT_PART(h.old_registration_number, '-', 3) AS INTEGER)) 
                            AND h.old_registration_year = '$cy' 
                        )
                    )
                    AND h.is_deleted = 'f'";

                $query2 = $this->db->query($queryString2);


                if ($query2->getNumRows() > 0)
                {
                    $get_dno = $query2->getRowArray();
                    $_REQUEST['d_no'] = $get_dno['dn'];
                    $_REQUEST['d_yr'] = $get_dno['dy'];

                    $sql_ct_type = $this->db->query("
                        SELECT short_description 
                        FROM master.casetype 
                        WHERE casecode = 3 AND display = 'Y'
                    ");

                    if ($sql_ct_type->getNumRows() > 0)
                    {
                        $res_ct_typ = $sql_ct_type->getRow()->short_description;
                        $t_slpcc = $res_ct_typ . " " . $get_dno['crf1'] . " - " . $get_dno['crl1'] . " / " . $cy;
                    }
                }
                else
                {
                    $html .= '<p align=center><font color=red>Case Not Found</font></p>';
                    return $html;
                }
            }
        }
        if($_REQUEST['d_no']!='' && $_REQUEST['d_yr']!='')
        {
            $d_no = $_REQUEST['d_no'];
            $d_yr = $_REQUEST['d_yr'];
            $diaryNo = strval($d_no).''.strval($d_yr);

            $sql = "SELECT 
                diary_no,
                conn_key,
                fil_dt,
                EXTRACT(YEAR FROM fil_dt) AS filyr,
                TO_CHAR(fil_dt, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_f,
                fil_no_fh,
                TO_CHAR(fil_dt_fh, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_fh,
                actcode,
                pet_adv_id,
                res_adv_id,
                lastorder,
                c_status,

                CASE 
                  WHEN diary_no IS NOT NULL THEN SPLIT_PART(diary_no::TEXT, '-', 1) 
                  ELSE NULL 
                END AS ct1,
                CASE 
                  WHEN diary_no IS NOT NULL AND SPLIT_PART(diary_no::TEXT, '-', 2) IS NOT NULL AND SPLIT_PART(diary_no::TEXT, '-', 2) != '' 
                  THEN SPLIT_PART(diary_no::TEXT, '-', 2)
                  ELSE NULL 
                END AS crf1,
                CASE 
                  WHEN diary_no IS NOT NULL AND SPLIT_PART(diary_no::TEXT, '-', 3) IS NOT NULL AND SPLIT_PART(diary_no::TEXT, '-', 3) != '' 
                  THEN SPLIT_PART(diary_no::TEXT, '-', 3) 
                  ELSE NULL 
                END AS crl1,
                CASE 
                  WHEN fil_no_fh IS NOT NULL THEN SPLIT_PART(fil_no_fh, '-', 1) 
                  ELSE NULL 
                END AS ct2,
                CASE 
                  WHEN fil_no_fh IS NOT NULL AND SPLIT_PART(fil_no_fh, '-', 2) IS NOT NULL AND SPLIT_PART(fil_no_fh, '-', 2) != '' 
                  THEN SPLIT_PART(fil_no_fh, '-', 2) 
                  ELSE NULL 
                END AS crf2,
                CASE 
                  WHEN fil_no_fh IS NOT NULL AND SPLIT_PART(fil_no_fh, '-', 3) IS NOT NULL AND SPLIT_PART(fil_no_fh, '-', 3) != '' 
                  THEN SPLIT_PART(fil_no_fh, '-', 3) 
                  ELSE NULL 
                END AS crl2,
                CASE 
                  WHEN conn_key IS NOT NULL THEN 
                      CASE WHEN conn_key = diary_no::TEXT THEN 'N' ELSE 'Y' END 
                  ELSE 'N' 
                END AS ccdet,
                casetype_id,
                conn_key AS connto 
            FROM 
                main 
            WHERE 
                diary_no = '$diaryNo'";
                

            $query3 = $this->db->query($sql);
            $main_fh_diary_no = "";
            if ($query3->getNumRows() > 0)
            {
                $fil_no = $query3->getRowArray();
                $isconn = $fil_no["ccdet"];
                $connto = $fil_no["connto"];
                $diaryno = $fil_no["diary_no"];

                if ( $fil_no["diary_no"] != $fil_no["conn_key"] && $fil_no["conn_key"] != "" ) {
                    $check_for_conn = "N";
                } else {
                    $check_for_conn = "Y";
                }
                if ($fil_no["fil_no_fh"] != "") {
                    $main_fh_diary_no = "EXIST";
                }

                $conn_type = "";
                $diary_no = $_REQUEST["d_no"] . "/" . $_REQUEST["d_yr"];

                if ($fil_no["conn_key"] != "") {
                    if ($fil_no["conn_key"] == $fil_no["diary_no"]) {
                        $conn_type = "M";
                    } else {
                        $conn_type = "C";
                    }
                }

                $html .= '<div style="text-align: center"><strong>Diary No.- '.$_REQUEST["d_no"].' - '.$_REQUEST["d_yr"].'</strong></div>';

                $query4 = "SELECT 
                    fil_dt, 
                    COALESCE(TO_CHAR(last_dt, 'DD-MM-YYYY HH12:MI AM'), '') AS last_dt, 
                    a.usercode, 
                    COALESCE(CAST(last_usercode AS TEXT), '') AS last_usercode, 
                    b.name AS user, 
                    c.name AS last_u
                FROM 
                    main a
                LEFT JOIN 
                    master.users b ON a.usercode = b.usercode
                LEFT JOIN 
                    master.users c ON a.last_usercode = c.usercode
                WHERE 
                    diary_no = $diaryno";

                $fil_date_for = $this->db->query($query4)->getRowArray();

                $html .= '<table border="0"  align="left" width="100%">';
                if ($main_case != "") {
                    $main_case ="<br>&nbsp;&nbsp;<font color='red' >[Connected with : " . $main_case . "</font>]";
                }

                $u_name = "";
                $sql_da = "SELECT a.usercode, b.name, us.section_name 
                FROM 
                    main a 
                LEFT JOIN 
                    master.users b ON a.usercode = b.usercode 
                LEFT JOIN 
                    master.usersection us ON b.section = us.id 
                WHERE 
                    diary_no = $diaryno";

                $query5 = $this->db->query($sql_da);
                if($query5->getNumRows() > 0)
                {
                    $row_da = $query5->getRowArray();
                    $u_name .= " by <font color='blue'>" . $row_da["name"] . "</font>";
                    $u_name .= "<font> [SECTION: </font><font color='red'>" . $row_da["section_name"] . "</font>]<font style='font-size:12px;font-weight:bold;'></font>";
                }

                $t_res_ct_typ = $this->db->query("SELECT short_description FROM master.casetype WHERE casecode = " . $fil_no['casetype_id'] . " AND display = 'Y'")->getRowArray();
                $res_ct_typ = $t_res_ct_typ["short_description"];
                $queryString3 = "SELECT 
                    SUBSTR(m.diary_no::TEXT, 1, LENGTH(m.diary_no::TEXT) - 4) AS case_no, 
                    SUBSTR(m.diary_no::TEXT, -4) AS year,
                    p.sr_no, 
                    p.pet_res, 
                    p.ind_dep, 
                    p.partyname, 
                    p.sonof, 
                    p.prfhname, 
                    p.age, 
                    p.sex, 
                    p.caste, 
                    p.addr1, 
                    p.addr2,
                    p.pin, 
                    p.state, 
                    p.city, 
                    p.email, 
                    p.contact AS mobile,
                    p.deptcode,
                    (
                        SELECT deptname FROM master.deptt WHERE deptcode = p.deptcode
                    ) AS deptname,
                    c.skey, 
                    TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY HH12:MI AM') AS diary_no_rec_date
                FROM 
                    party p 
                INNER JOIN 
                    main m ON m.diary_no = p.diary_no AND p.sr_no = 1 AND p.pflag = 'P' AND p.pet_res IN ('P', 'R')
                LEFT JOIN 
                    master.casetype c ON c.casecode::bigint = SUBSTR(m.diary_no::TEXT, 3, 3)::bigint
                WHERE 
                    m.diary_no = '".$fil_no['diary_no']."'
                ORDER BY 
                    p.pet_res, p.sr_no";

                $queryString3Result = $this->db->query($queryString3);
                if($queryString3Result->getNumRows() > 0) {   
                    $pet_name = $res_name = "";
                    $result = $queryString3Result->getResultArray();
                    foreach ($result as $key => $row) {
                        
                        $temp_var = "";
                        $temp_var .= $row["partyname"];
                        if ($row["sonof"] != "") {
                            $temp_var .= $row["sonof"] . "/o " . $row["prfhname"];
                        }

                        if ($row["deptname"] != "") {
                            $temp_var .= "<br>Department : " . $row["deptname"];
                        }

                        $temp_var .= "<br>";
                        if ($row["addr1"] == "") {
                            $temp_var .= $row["addr2"];
                        } else {
                            $temp_var .= $row["addr1"] . ", " . $row["addr2"];
                        }

                        $districtQuery = "SELECT Name as name
                        FROM master.state 
                        WHERE id_no = '".$row['city']."' AND Sub_Dist_code = 0 AND Village_code = 0 AND display = 'Y'";

                        
                        $t_dist = $this->db->query($districtQuery)->getRowArray();
                        $t_var = $t_dist["name"];

                        if ($t_var != "") {
                            $temp_var .= ", District : " . $t_var;
                        }

                        if ($row["pet_res"] == "P") {
                            $pet_name = $temp_var;
                        } else {
                            $res_name = $temp_var;
                        }
                        $case_no = $row["case_no"];
                        $year = $row["year"];
                        $diary_no_rec_date = $row["diary_no_rec_date"];
                    }

                    $html .= '<div class="cl_center"><strong>Case Details</strong></div>';
                    $html .= '<table class="table_tr_th_w_clr c_vertical_align" width="100%">';
                    $html .= '<tr>
                        <td width="140px">Diary No.</td>
                        <td>
                            <div width="100%"><font color = "blue" style = "font-size:12px;font-weight:bold;">'.$case_no.'/'.$year.'</font> Received on '.$diary_no_rec_date.' '.$u_name.' '.$main_case.'
                            </div>
                        </td>
                    </tr>';

                    $t_fil_no = get_case_nos($diaryno, "&nbsp;&nbsp;");

                    if (trim($t_fil_no) == "") {
                        $sql12 = $this->db->query("SELECT short_description FROM master.casetype WHERE casecode = " . $fil_no['casetype_id'] . "");
                        if($sql12->getNumRows() > 0) {
                            $row_12 = $sql12->getRowArray();
                            $t_fil_no = $row_12["short_description"];
                        }
                    }
                    if ($t_slpcc != "") {
                       $t_slpcc = "<br>" . $t_slpcc;
                    }

                    $t_fil_no1 = "";

                    $sq_lct = "SELECT  lct_dec_dt, lct_caseno, lct_caseyear, short_description type_sname
                    FROM lowerct a
                    LEFT JOIN master.casetype ct ON ct.casecode = a.lct_casetype AND ct.display = 'Y'
                    WHERE a.diary_no = '" . $diaryno . "' AND lw_display = 'Y' AND ct_code =4 ORDER BY a.lct_dec_dt";

                    $rs_lct = $this->db->query($sq_lct);
                    if($rs_lct->getNumRows() > 0) {
                        $ro_lct = $rs_lct->getResultArray();
                        $t_fil_no1 .= "";
                        foreach ($ro_lct as $key => $value) {
                            if ($t_fil_no1 == "") {
                                $t_fil_no1 .=
                                " IN " .
                                $value["type_sname"] .
                                " - " .
                                $value["lct_caseno"] .
                                "/" .
                                $value["lct_caseyear"];
                            } else {
                                $t_fil_no1 .=
                                ", " .
                                $value["type_sname"] .
                                " - " .
                                $value["lct_caseno"] .
                                "/" .
                                $value["lct_caseyear"];
                            }
                        }
                    }

                    $html .= "<tr>
                        <td>Case No.</td>
                        <td>
                            <div width='100%'>" . $t_fil_no . $t_slpcc . $t_fil_no1 ."</div>
                        </td>
                    </tr>";

                    if ($t_spl != "") {
                        $html .= "<tr>
                            <td>Special Type</td>
                            <td>" . $t_spl . "</td>
                        </tr>";
                    }
                    $html .= "<tr>
                        <td style='width: 15%'> Petitioner </td>
                        <td>" . $pet_name . "</td>
                    </tr>
                    <tr>
                        <td style='width: 15%'> Respondant </td>
                        <td>" . $res_name . "</td>
                    </tr>";

                    $case_category = "";
                    $mul_category = get_mul_category($fil_no["diary_no"]);

                    $html .= "<tr>
                        <td style='width: 15%'> Case Category </td>
                        <td>" . $mul_category . " </td>
                    </tr>";

                    $act_query = "SELECT a.act, STRING_AGG(b.section, ', ') AS section, act_name 
                    FROM 
                        act_main a 
                    LEFT JOIN 
                        master.act_section b ON a.id = b.act_id 
                    JOIN 
                        master.act_master c ON c.id = a.act 
                    WHERE 
                        diary_no = " . $fil_no['diary_no'] . " AND a.display = 'Y' AND b.display = 'Y' AND c.display = 'Y' 
                    GROUP BY 
                        a.act, act_name";

                    $act = $this->db->query($act_query);

                    if($act->getNumRows() > 0) {
                        $act_result = $act->getResultArray();
                        $act_section = "";
                        foreach ($act_result as $key => $row1) {
                            if ($act_section == "") {
                                $act_section = $row1["act_name"] . "-" . $row1["section"];
                            } else {
                                $act_section =
                                    $act_section .
                                    ", " .
                                    $row1["act_name"] .
                                    "-" .
                                    $row1["section"];
                            }
                        }
                    }

                    $html .= "<tr>
                        <td>Act</td>
                        <td>" . $act_section . " </td>
                    </tr>";

                    $t_pol = $this->db->query("SELECT law FROM master.caselaw WHERE id = ?", [$fil_no['actcode']])->getRowArray();
                    if(!empty($t_pol)) {
                        $pov_of_law = $t_pol["law"];
                    } else {
                        $pov_of_law = '';
                    }

                    $html .= "<tr>
                        <td>Provision of Law</td>
                        <td>" . $pov_of_law . " </td>
                    </tr>";

                    $pet_adv = get_advocates_new($fil_no["pet_adv_id"], "wen");
                    if(!empty($pet_adv)) {
                        $html .= "<tr>
                            <td style='width: 15%'>Petitioner Advocate</td>
                            <td>" . $pet_adv . " </td>
                        </tr>";
                    } else {
                        $html .= "<tr>
                            <td style='width: 15%'>Petitioner Advocate</td>
                            <td></td>
                        </tr>";
                    }

                    $res_adv = get_advocates_new($fil_no["res_adv_id"], "wen");
                    if(!empty($res_adv)) {
                        $html .= "<tr>
                            <td style='width: 15%'>Respondant Advocate</td>
                            <td>" . $res_adv . " </td>
                        </tr>";
                    } else {
                        $html .= "<tr>
                            <td style='width: 15%'>Respondant Advocate</td>
                            <td></td>
                        </tr>";
                    }

                    $html .= "<tr>
                            <td style='width: 15%'>Last Order</td>
                            <td>" . $fil_no["lastorder"] . " </td>
                    </tr>";

                    if ($fil_no["c_status"] == "P") {
                        $rgo_sql = $this->db->table('rgo_default');
                        $rgo_sql->select('fil_no2')->distinct();  // Use distinct() method
                        $rgo_sql->where('fil_no', $fil_no["diary_no"]);
                        $rgo_sql->where('remove_def', 'N');
                        $rgo_query = $rgo_sql->get();

                        $t_rgo = "";

                        if ($rgo_query->getNumRows() > 0) {
                            foreach ($rgo_query->getResultArray() as $res_rgo) {
                                if ($t_rgo == "") {
                                    $t_rgo = "D.No. " . get_real_diaryno($res_rgo["fil_no2"]) . "<br>" . str_replace("<br>", " ", get_casenos_comma($res_rgo["fil_no2"]));
                                } else {
                                    $t_rgo .= "<br> D.No. " . get_real_diaryno($res_rgo["fil_no2"]) . "<br>" . str_replace("<br>", " ", get_casenos_comma($res_rgo["fil_no2"]));
                                }
                            }
                        }

                        if ($t_rgo != "") {
                            $html .= "<tr>
                                <td>Conditional Dispose</td>
                                <td style='font-size:12px;font-weight:100;'><b> <font style='font-size:12px;font-weight:100;'><b>" .
                                  $t_rgo . "</b></font></b>
                                </td>
                            </tr>";
                        }

                        $ttv = "SELECT tentative_cl_dt FROM heardt WHERE diary_no = ?";
                        $query = $this->db->query($ttv, $fil_no["diary_no"]);
                        $r_ttv = $query->getRowArray();

                        $sql_display = $this->db->query("SELECT display_flag, always_allowed_users FROM master.case_status_flag WHERE to_date IS null AND flag_name = 'tentative_listing_date'")->getRowArray();
                        

                        if ( $sql_display["display_flag"] == 1 || in_array($ucode, explode(",", $sql_display["always_allowed_users"])) )
                        {
                            $tentative_date = '';
                            if ( get_display_status_with_date_differnces( $r_ttv["tentative_cl_dt"] ) == "T" )
                            {
                                $tentative_date = date('d-m-Y', strtotime($r_ttv["tentative_cl_dt"]));
                            }

                            $html .= "<tr>
                                <td style='width: 15%'>Tentative Date</td>
                                <td>" . $tentative_date . " </td>
                            </tr>";
                        }

                        if ($isconn == "Y")
                        {
                            $sql_oth_conn_query = "SELECT 
                                m.diary_no,
                                (SELECT list FROM conct cc WHERE cc.diary_no = m.diary_no LIMIT 1) AS llist
                            FROM 
                                main m 
                            WHERE 
                                (m.diary_no::text = '$diaryno' OR m.conn_key IN (SELECT conn_key FROM main WHERE diary_no::text = '$diaryno'))
                                AND m.diary_no::text != m.conn_key
                            ORDER BY 
                                m.fil_dt";

                            $connto = "<font color='red'>" . $connto . " </font>(Main Case)";
                            $sql_oth_conn = $this->db->query($sql_oth_conn_query);

                            if($sql_oth_conn->getNumRows() > 0)
                            {
                                $results_oc = $sql_oth_conn->getResultArray();
                                foreach ($results_oc as $key => $row_oc)
                                {
                                    $connto .= "<br><font color='blue'>" . $row_oc["diary_no"] . " </font>(Connected Case)";
                                }
                            }

                            $html .= "<tr valign='top'><td bgcolor='#F4F5F5'>Connected To </td><td><b>" . $connto . "</b></td></tr>";
                        }
                    }
                    else
                    {
                        $html .= '<tr>
                            <td>Case Status </td>
                            <td><font color=red>Case is Disposed</font></td>
                        </tr>';
                    }
                    $html .= '</table>';
                }

                if ($conn_type == "")
                {
                    $html .= get_diary_set_fm($fil_no["diary_no"], $module, $conn_type);
                }
                else
                {
                    $conncases = get_conn_cases($fil_no["diary_no"]);

                    foreach ($conncases as $row => $link)
                    {
                        if ($link["c_type"] != "")
                        {
                            if ($link["c_type"] == "M")
                            {
                               $html .= "<b>Main Case</b>";
                            }
                            if ($link["c_type"] == "C")
                            {
                               $html .= "<b>Connected Case</b>";
                            }
                            if ($link["c_type"] == "L")
                            {
                               $html .= "<b>Linked Cases</b>";
                            }
                            $html .= get_diary_set_fm($link["diary_no"], $module, $link["c_type"]);
                        }
                    }
                }

                if ($module == "receive")
                {
                    $html .= '<p align="center"><input type="button" name="receive" id="receive" value="Receive File" class="btn btn-primary"/></p>';
                }
                if ($module == "dispatch")
                {
                    $usercode = session()->get('login')['usercode'];
                    $usertype_query = $this->db->table('master.users')->select('usertype, udept')->where('usercode', $usercode)->get()->getRowArray();

                    if ($usertype_query)
                    {
                        /*$dept_query = $this->db->table('master.users')->select('DISTINCT udept, dept_name')->join('master.userdept', 'udept = userdept.id', 'left')->orderBy('udept')->get()->getResultArray();*/

                        $dept_query = $this->db->table('master.users')->distinct()->select('udept, dept_name')->join('master.userdept', 'users.udept = userdept.id', 'left')->orderBy('udept')->get()->getResultArray();
                    }

                    // Start generating HTML
                    $html .= '<div id="user_div"> 
                                <div class="inner_1">
                                    <label class="cl_wh">DEPARTMENT</label>
                                    <input type="hidden" value="' . esc($usertype_query['usertype']) . '" id="cur_user_type"/>
                                    <select class="form-control" id="department">';

                    // Add options based on user type
                    if ($usertype_query['usertype'] == 1)
                    {
                        $html .= '<option value="ALL">ALL</option>';
                    }
                    else
                    {
                        $html .= '<option value="">SELECT</option>';
                    }

                    // Populate department options
                    foreach ($dept_query as $dept_row)
                    {
                        $html .= '<option value="' . esc($dept_row['udept']) . '">' . esc($dept_row['dept_name']) . '</option>';
                    }

                    $html .= '</select></div>';

                    // SECTION dropdown
                    $html .= '<div class="inner_1">
                        <label class="cl_wh">SECTION</label>
                        <select class="form-control" id="section">';

                    if ($usertype_query['usertype'] == 1)
                    {
                        $html .= '<option value="ALL">ALL</option>';
                    }
                    else
                    {
                        $html .= '<option value="0">SELECT</option>';
                    }

                    $html .= '</select></div>';

                    // DESIGNATION dropdown
                    $html .= '<div class="inner_1">
                                <label class="cl_wh">DESIGNATION</label>
                                <select class="form-control" id="designation"><option value="ALL">ALL</option></select>
                              </div>';

                    // USER dropdown
                    $html .= '<div class="inner_1">
                                <label class="cl_wh">USER</label>
                                <select class="form-control" id="user"></select>
                              </div>';

                    $html .= '</div><br><br><br><br>';

                    $html .= '<div class="col-md-12" style="padding-top: 10px;"><p align="center"><input type="button" name="dispatch" id="dispatch" value="Dispatch File" class="btn btn-primary" /></p></div>';
                }
                return $html;
            }
            else
            {
                $html .= '<p align="center"><font color=red>Case Details Not Found</font></p>';
                return $html;
            }
        }
    }
}