<?php

namespace App\Models\Reports\DepartmentWise;

use CodeIgniter\Model;

class DepartmentWiseModel extends Model
{
    protected $eservicesdb;

    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
        $this->eservicesdb = \Config\Database::connect('eservices');
    }

    public function getMainDepartments()
    {
        $queryString = "SELECT deptcode, deptname, dm FROM master.deptt WHERE deptype = 'C' AND dm != 0 AND d1 = 0 AND d2 = 0 AND d3 = 0 AND display = 'Y' ORDER BY dm";
        $queryResult = $this->db->query($queryString);
        if($queryResult->getNumRows() >= 1)
        {
            $result = $queryResult->getResultArray();
            return $result;
        }
        else
        {
            return [];
        }
    }

    public function get_subdept1()
    {
        $option = '';
        if($_REQUEST['mdept'] != '')
        {
            $option .= '<option value="">ALL</option>';

            $mdept = $_REQUEST['mdept'];
            $queryString = "SELECT deptcode, deptname, dm, d1 FROM master.deptt WHERE deptype = 'C' AND dm = $mdept AND d1 != 0 AND d2 = 0 AND d3 = 0 AND display = 'Y' ORDER BY d1";
            $queryResult = $this->db->query($queryString);

            if($queryResult->getNumRows() >= 1)
            {
                $result = $queryResult->getResultArray();
                foreach ($result as $row_sdept1)
                {
                    $option .= '<option value="' . esc($row_sdept1['d1']) . '">' . esc($row_sdept1['d1']) . ' - ' . esc($row_sdept1['deptname']) . '</option>';
                }
            }
        }
        else
        {
            $option .= '<option value="">ALL</option>';
        }
        return $option;
    }

    public function get_department_wise_rpt()
    {
        $html = '';
        if($_REQUEST['mdept']!='')
        {
            $mdept = $_REQUEST['mdept'];
            if($_REQUEST['sdept1']=='')
            {
                $sel = "SELECT string_agg(deptcode::text, ',') AS deptcodes FROM master.deptt WHERE deptype = 'C' AND dm = $mdept AND display = 'Y'";
            }
            else
            {
                $sdept1 = $_REQUEST['sdept1'];
                $sel = "SELECT string_agg(deptcode::text, ',') AS deptcodes FROM master.deptt WHERE deptype = 'C' AND dm = $mdept AND d1 = $sdept1 AND display = 'Y'";
            }

            $sel_query = $this->db->query($sel);
            $sel_query_result = $sel_query->getRowArray();
            $deptcodes = $sel_query_result['deptcodes'];
            $result = " a.deptcode IN ($deptcodes) ";
        }
        else
        {
            $result = " a.deptcode!=0 ";
        }

        $city = "";
        /*if($_REQUEST['city'] != 0)
            $city = " AND city = ".$_REQUEST['city'];*/
        $dates = "";
        $fdate = !empty($_REQUEST['fdate']) ? date('Y-m-d', strtotime($_REQUEST['fdate'])) : '';
        $tdate = !empty($_REQUEST['tdate']) ? date('Y-m-d', strtotime($_REQUEST['tdate'])) : '';
        $fil_temp = '';

        if($_REQUEST['cst']=='P')
        {
            if(($_REQUEST['fdate']!='') && ($_REQUEST['tdate']!=''))
            {
                $dates = " AND c.diary_no_rec_date::date BETWEEN '$fdate' AND '$tdate' ";
            }
            else if(($_REQUEST['fdate']=='') && ($_REQUEST['tdate']!=''))
            {
                $dates = " AND c.diary_no_rec_date::date <= '$tdate' ";
            }
            else if(($_REQUEST['fdate']!='') && ($_REQUEST['tdate']==''))
            {
                $dates = " AND c.diary_no_rec_date::date >= '$fdate' ";
            }
        }
        else
        {
            if(($_REQUEST['fdate']!='') && ($_REQUEST['tdate']!=''))
            {
                $dates = " AND c.disp_dt::date BETWEEN '$fdate' AND '$tdate' ";
            }
            else if(($_REQUEST['fdate']=='') && ($_REQUEST['tdate']!=''))
            {
                $dates = " AND c.disp_dt::date <= '$tdate' ";
            }
            else if(($_REQUEST['fdate']!='') && ($_REQUEST['tdate']==''))
            {
                $dates = " AND c.disp_dt::date >= '$tdate' ";
            }
        }

        $maincases = "SELECT a.diary_no,
            active_fil_no,
            short_description,
            active_reg_year,
            pet_res,
            sr_no,
            sr_no_show,
            partysuff,
            partyname,
            authcode,
            addr1,
            addr2,
            pin,
            email,
            contact,
            dstname,
            a.deptcode,
            a.ent_dt,
            pet_name,
            res_name,
            deptname,
            dm,
            d1,
            d2
            -- s1.Name AS cityname,
            -- s2.Name AS statename
        FROM party a
        LEFT JOIN main c ON a.diary_no = c.diary_no
        LEFT JOIN master.casetype b ON active_casetype_id = casecode
        LEFT JOIN master.deptt d ON a.deptcode = d.deptcode
            -- LEFT JOIN state s1 ON city = s1.id_no
            -- LEFT JOIN state s2 ON state = s1.id_no
        LEFT JOIN dispose di ON a.diary_no = di.diary_no
        WHERE ind_dep = 'D2'
        AND $result
        AND pflag = 'P'
        AND c_status = '$_REQUEST[cst]'
        $dates
        $city
        ORDER BY short_description, active_fil_no, active_reg_year, dm, d1, d2, pet_res, sr_no";

        $maincases_query = $this->db->query($maincases);
        if($maincases_query->getNumRows() >= 1)
        {
            $maincases_result = $maincases_query->getResultArray();

            $html .= '<table class="deptres table table_tr_th_w_clr centerview" border="1" style="border-collapse: collapse">
            <tr>
                <th>SNo.</th>
                <th>Diary No.</th>
                <th>Reg No.</th>
                <th>Parties</th>
                <th>P/R - No.</th>
                <th>Party Name</th>
                <th>Address</th>
                <th>Department</th>
                <th>Last 3 Reply/Rejoinder</th>
                <!--<th>OIC<br><span style="font-weight: 500;font-size: 12px;">Name-Desg-Mob</span></th>-->
            </tr>';

            $serial = 0;
            foreach($maincases_result as $key => $row_main)
            {
                if($fil_temp != $row_main['diary_no'])
                {
                    $obj='';
                    if ($_REQUEST['rsta'] == 'RN')
                    {
                        $sql = "SELECT diary_no FROM docdetails WHERE diary_no = '$row_main[diary_no]' AND doccode = 2 AND display = 'Y'";

                        $query = $this->db->query($sql);

                        if ($query->getNumRows() > 0)
                        {
                            continue;
                        }
                    }
                    if ($_REQUEST['rsta'] == 'R')
                    {
                        $sql = "SELECT diary_no FROM docdetails WHERE diary_no = '$row_main[diary_no]' AND doccode = 2 AND display = 'Y'";

                        $query = $this->db->query($sql);

                        if ($query->getNumRows() == 0)
                        {
                            continue;
                        }
                    }

                    $serial++;
                    
                    $docinf = "SELECT
                    docnum,
                    docyear,
                    a.ent_dt,
                    docdesc
                    FROM docdetails As a
                    LEFT JOIN master.docmaster As b ON a.doccode = b.doccode
                    AND a.doccode1 = b.doccode1 
                    WHERE diary_no='$row_main[diary_no]' 
                    AND a.doccode IN (2,3) 
                    AND a.display='Y' 
                    ORDER BY a.ent_dt DESC LIMIT 3";

                    $docinf_query = $this->db->query($docinf);
                    if($docinf_query->getNumRows() >= 1)
                    {
                        $myser = 1;
                        $docinf_result = $docinf_query->getResultArray();
                        foreach ($docinf_result as $key => $docinf_row)
                        {
                            if($myser!=1)
                            {
                                $obj.="<br>";
                            }
                            else
                            {
                                $obj.= $docinf_row['docnum'].'/'.$docinf_row['docyear'].'-'.$docinf_row['docdesc'].' ON '.date('d-m-Y',strtotime($docinf_row['ent_dt']));
                            }
                        }
                    }
                }

                $html .= '<tr>';
                $html .= '<td>' . $serial . '</td>';
                $html .= '<td>' . substr($row_main["diary_no"], 0, -4) . "/" . substr($row_main["diary_no"], -4) . '</td>';
                $html .= '<td>' . $row_main["short_description"] . "/" . substr($row_main["active_fil_no"], 3) . "/" . $row_main["active_reg_year"] . '</td>';
                $html .= '<td style="font-size: 12px;">' . $row_main["pet_name"] . " <span style='color:blue'>Vs</span> " . $row_main["res_name"] . '</td>';

                $html .= '<td>';
                if ($row_main["pet_res"] == "P")
                {
                    $html .= "Pet";
                }
                else if ($row_main["pet_res"] == "R")
                {
                    $html .= "Res";
                }
                $html .= " - " . $row_main["sr_no_show"] . '</td>';

                $html .= '<td>' . $row_main["partyname"] . " " . $row_main["addr1"] . '</td>';

                $html .= '<td>';
                $html .= $row_main["addr2"] . ", " . $row_main["dstname"] . ", " . isset($row_main["cityname"]) . ", " . isset($row_main["statename"]);
                if ($row_main["pin"] != 0)
                {
                    $html .= ", " . $row_main["pin"];
                }
                $html .= '</td>';

                $html .= '<td>' . $row_main["deptname"] . '</td>';

                $html .= '<td>' . $obj . '</td>';
                $html .= '</tr>';

                $fil_temp = $row_main['diary_no'];
            }
        }
        else
        {
            $html .= '<h3 class="sorry">SORRY, No Record Found!!!</h3>';
        }
        return $html;
    }

    public function departmentRPTExcel()
    {
        $html = '';
        if($_REQUEST['hd_for_mdept']!='')
        {
            $hd_for_mdept = $_REQUEST['hd_for_mdept'];
            if($_REQUEST['hd_for_sdept']=='')
            {
                $sel = "SELECT string_agg(deptcode::text, ',') AS deptcodes FROM master.deptt WHERE deptype = 'C' AND dm = $hd_for_mdept AND display = 'Y'";
            }
            else
            {
                $hd_for_sdept = $_REQUEST['hd_for_sdept'];
                $sel = "SELECT string_agg(deptcode::text, ',') AS deptcodes FROM master.deptt WHERE deptype = 'C' AND dm = $hd_for_mdept AND d1 = $hd_for_sdept AND display = 'Y'";
            }

            $sel_query = $this->db->query($sel);
            $sel_query_result = $sel_query->getRowArray();
            $deptcodes = $sel_query_result['deptcodes'];
            $result = " a.deptcode IN ($deptcodes) ";
        }
        else
        {
            $result = " a.deptcode!=0 ";
        }

        $city = "";
        /*if($_REQUEST['city'] != 0)
            $city = " AND city = ".$_REQUEST['city'];*/
        $dates = "";
        $hd_for_fdate = !empty($_REQUEST['hd_for_fdate']) ? date('Y-m-d', strtotime($_REQUEST['hd_for_fdate'])) : '';
        $hd_for_tdate = !empty($_REQUEST['hd_for_tdate']) ? date('Y-m-d', strtotime($_REQUEST['hd_for_tdate'])) : '';
        $fil_temp = '';

        if($_REQUEST['hd_for_sts']=='P')
        {
            if(($_REQUEST['hd_for_fdate']!='') && ($_REQUEST['hd_for_tdate']!=''))
            {
                $dates = " AND c.diary_no_rec_date::date BETWEEN '$hd_for_fdate' AND '$hd_for_tdate' ";
            }
            else if(($_REQUEST['hd_for_fdate']=='') && ($_REQUEST['hd_for_tdate']!=''))
            {
                $dates = " AND c.diary_no_rec_date::date <= '$hd_for_tdate' ";
            }
            else if(($_REQUEST['hd_for_fdate']!='') && ($_REQUEST['hd_for_tdate']==''))
            {
                $dates = " AND c.diary_no_rec_date::date >= '$hd_for_fdate' ";
            }
        }
        else
        {
            if(($_REQUEST['hd_for_fdate']!='') && ($_REQUEST['hd_for_tdate']!=''))
            {
                $dates = " AND c.disp_dt::date BETWEEN '$hd_for_fdate' AND '$hd_for_tdate' ";
            }
            else if(($_REQUEST['hd_for_fdate']=='') && ($_REQUEST['hd_for_tdate']!=''))
            {
                $dates = " AND c.disp_dt::date <= '$hd_for_tdate' ";
            }
            else if(($_REQUEST['hd_for_fdate']!='') && ($_REQUEST['hd_for_tdate']==''))
            {
                $dates = " AND c.disp_dt::date >= '$hd_for_tdate' ";
            }
        }

        $maincases = "SELECT a.diary_no,
            active_fil_no,
            short_description,
            active_reg_year,
            pet_res,
            sr_no,
            sr_no_show,
            partysuff,
            partyname,
            authcode,
            addr1,
            addr2,
            pin,
            email,
            contact,
            dstname,
            a.deptcode,
            a.ent_dt,
            pet_name,
            res_name,
            deptname,
            dm,
            d1,
            d2
            -- s1.Name AS cityname,
            -- s2.Name AS statename
        FROM party a
        LEFT JOIN main c ON a.diary_no = c.diary_no
        LEFT JOIN master.casetype b ON active_casetype_id = casecode
        LEFT JOIN master.deptt d ON a.deptcode = d.deptcode
            -- LEFT JOIN state s1 ON city = s1.id_no
            -- LEFT JOIN state s2 ON state = s1.id_no
        LEFT JOIN dispose di ON a.diary_no = di.diary_no
        WHERE ind_dep = 'D2'
        AND $result
        AND pflag = 'P'
        AND c_status = '$_REQUEST[hd_for_sts]'
        $dates
        $city
        ORDER BY short_description, active_fil_no, active_reg_year, dm, d1, d2, pet_res, sr_no";

        $maincases_query = $this->db->query($maincases);
        if($maincases_query->getNumRows() >= 1)
        {
            $maincases_result = $maincases_query->getResultArray();

            $html .= '<table class="deptres table table_tr_th_w_clr centerview" border="1" style="border-collapse: collapse">
            <tr>
                <th>SNo.</th>
                <th>Diary No.</th>
                <th>Reg No.</th>
                <th>Parties</th>
                <th>P/R - No.</th>
                <th>Party Name</th>
                <th>Address</th>
                <th>Department</th>
                <th>Last 3 Reply/Rejoinder</th>
                <!--<th>OIC<br><span style="font-weight: 500;font-size: 12px;">Name-Desg-Mob</span></th>-->
            </tr>';

            $serial = 0;
            foreach($maincases_result as $key => $row_main)
            {
                if($fil_temp != $row_main['diary_no'])
                {
                    $obj='';
                    if ($_REQUEST['hd_for_rstatus'] == 'RN')
                    {
                        $sql = "SELECT diary_no FROM docdetails WHERE diary_no = '$row_main[diary_no]' AND doccode = 2 AND display = 'Y'";

                        $query = $this->db->query($sql);

                        if ($query->getNumRows() > 0)
                        {
                            continue;
                        }
                    }
                    if ($_REQUEST['hd_for_rstatus'] == 'R')
                    {
                        $sql = "SELECT diary_no FROM docdetails WHERE diary_no = '$row_main[diary_no]' AND doccode = 2 AND display = 'Y'";

                        $query = $this->db->query($sql);

                        if ($query->getNumRows() == 0)
                        {
                            continue;
                        }
                    }

                    $serial++;
                    
                    $docinf = "SELECT
                    docnum,
                    docyear,
                    a.ent_dt,
                    docdesc
                    FROM docdetails As a
                    LEFT JOIN master.docmaster As b ON a.doccode = b.doccode
                    AND a.doccode1 = b.doccode1 
                    WHERE diary_no='$row_main[diary_no]' 
                    AND a.doccode IN (2,3) 
                    AND a.display='Y' 
                    ORDER BY a.ent_dt DESC LIMIT 3";

                    $docinf_query = $this->db->query($docinf);
                    if($docinf_query->getNumRows() >= 1)
                    {
                        $myser = 1;
                        $docinf_result = $docinf_query->getResultArray();
                        foreach ($docinf_result as $key => $docinf_row)
                        {
                            if($myser!=1)
                            {
                                $obj.="<br>";
                            }
                            else
                            {
                                $obj.= $docinf_row['docnum'].'/'.$docinf_row['docyear'].'-'.$docinf_row['docdesc'].' ON '.date('d-m-Y',strtotime($docinf_row['ent_dt']));
                            }
                        }
                    }
                }

                $html .= '<tr>';
                $html .= '<td style="text-align: center;"><b>' . $serial . '</b></td>';
                $html .= '<td><b>' . substr($row_main["diary_no"], 0, -4) . "/" . substr($row_main["diary_no"], -4) . '</b></td>';
                $html .= '<td>' . $row_main["short_description"] . "/" . substr($row_main["active_fil_no"], 3) . "/" . $row_main["active_reg_year"] . '</td>';
                $html .= '<td style="font-size: 12px;">' . $row_main["pet_name"] . " <span style='color:blue'>Vs</span> " . $row_main["res_name"] . '</td>';

                $html .= '<td>';
                if ($row_main["pet_res"] == "P")
                {
                    $html .= "Pet";
                }
                else if ($row_main["pet_res"] == "R")
                {
                    $html .= "Res";
                }
                $html .= " - " . $row_main["sr_no_show"] . '</td>';

                $html .= '<td>' . $row_main["partyname"] . " " . $row_main["addr1"] . '</td>';

                $html .= '<td>';
                $html .= $row_main["addr2"] . ", " . $row_main["dstname"] . ", " . isset($row_main["cityname"]) . ", " . isset($row_main["statename"]);
                if ($row_main["pin"] != 0)
                {
                    $html .= ", " . $row_main["pin"];
                }
                $html .= '</td>';

                $html .= '<td>' . $row_main["deptname"] . '</td>';

                $html .= '<td>' . $obj . '</td>';
                $html .= '</tr>';

                $fil_temp = $row_main['diary_no'];
            }
        }
        else
        {
            $html .= '<h3 class="sorry">SORRY, No Record Found!!!</h3>';
        }
        return $html;
    }
}
