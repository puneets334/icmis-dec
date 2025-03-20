<?php

namespace App\Models\Listing;

use CodeIgniter\Model;

class CaseInfoDetails extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    function getCaseDetails($cn, $year){
        // "SELECT LEFT(CAST(diary_no AS TEXT), LENGTH(CAST(diary_no AS TEXT)) - 4) AS dn, 
        //     RIGHT(CAST(diary_no AS TEXT), 4) AS dy  
        //     FROM main 
        //     WHERE 
        //         SPLIT_PART(fil_no, '-', 1) = '01' 
        //         AND 1024 BETWEEN CAST(SPLIT_PART(SPLIT_PART(fil_no, '-', 2), '-', -1) AS INTEGER) 
        //         AND CAST(SPLIT_PART(fil_no, '-', -1) AS INTEGER)
        //     AND 
        //         CASE 
        //             WHEN (reg_year_mh = 0 OR DATE(fil_dt) > DATE '2017-05-10')
        //             THEN EXTRACT(YEAR FROM fil_dt) = 2023 
        //             ELSE reg_year_mh = 2023
        //         END";

        $builder = $this->db->table('main');
        $builder->select([
            "LEFT(CAST(diary_no AS TEXT), LENGTH(CAST(diary_no AS TEXT)) - 4) AS dn",
            "RIGHT(CAST(diary_no AS TEXT), 4) AS dy"
        ]);
        $builder->where("CAST(NULLIF(SPLIT_PART(fil_no, '-', 1), '') AS INTEGER) =", 1);
        $builder->where($cn." BETWEEN 
                        COALESCE(CAST(NULLIF(SPLIT_PART(SPLIT_PART(fil_no, '-', 2), '-', -1), '') AS INTEGER), 0) 
                        AND COALESCE(CAST(NULLIF(SPLIT_PART(fil_no, '-', -1), '') AS INTEGER), 0)", null, false);
        $builder->groupStart()
            ->groupStart()
                ->where('reg_year_mh', 0)
                ->orWhere('fil_dt >', '2017-05-10')
            ->groupEnd()
            ->where('EXTRACT(YEAR FROM fil_dt) =', $year, false)
            ->orWhere('reg_year_mh', $year)
        ->groupEnd();

        $query = $builder->get();
        return $query->getResultArray();
    }

    function get_case_details($diary_no)
    {
        $c_array = array();
        $case_details = "SELECT active_fil_no, year( active_fil_dt ) active_fil_dt , casename,pet_name,res_name,pno,rno FROM main a left JOIN casetype b ON substr(a.active_fil_no, 1, 2) = b.casecode AND b.display = 'Y' WHERE diary_no = '$diary_no' ";

        $case_details = mysql_query($case_details) or die("Error: " . __LINE__ . mysql_error());
        $r_case_details = mysql_fetch_array($case_details);
        $c_array[0] = $r_case_details[active_fil_no];
        $c_array[1] = $r_case_details[active_fil_dt];
        $c_array[2] = $r_case_details[casename];
        $c_array[3] = $r_case_details[pet_name];
        $c_array[4] = $r_case_details[res_name];
        $c_array[5] = $r_case_details[pno];
        $c_array[6] = $r_case_details[rno];
        return $c_array;
    }

    function get_emp_details($idd)
    {
        $o_array = array();
        $sql = "Select empid,name from users where usercode='$idd' and display='Y'";
        $sql =  mysql_query($sql) or die("Error: " . __LINE__ . mysql_error());
        $r_sql =  mysql_fetch_array($sql);
        $o_array[0] = $r_sql['empid'];
        $o_array[1] = $r_sql['name'];
        return $o_array;
    }

    function get_district($district)
    {
        $s_det = "Select Name from state where id_no='$district' and display='Y'";
        $s_det = mysql_query($s_det) or die("Error: " . __LINE__ . mysql_error());
        return $r_district = mysql_result($s_det, 0);
    }

    function send_to_name($id_val, $tw_sn_to)
    {
        if ($id_val == 2) {
            $sql = "Select desg from  tw_send_to where display='Y' and id='$tw_sn_to'";
        } else if ($id_val == 1) {

            $sql = "Select concat(name,'-',aor_code) desg from bar where bar_id='$tw_sn_to'";
        } else if ($id_val == 3) {
            $sql = "SELECT  concat(IF (ct_code =3, (SELECT Name FROM state s WHERE s.id_no = a.l_dist AND display = 'Y' ), (SELECT agency_name FROM ref_agency_code c WHERE c.cmis_state_id = a.l_state AND c.id = a.l_dist AND is_deleted = 'f' ) ) , ' ', b.Name) desg FROM `lowerct` a JOIN state b ON a.l_state = b.id_no WHERE lower_court_id='$tw_sn_to' AND lw_display = 'Y' AND b.display = 'Y' group by l_state,l_dist ";
        }

        $sql = mysql_query($sql) or die("Error: " . __LINE__ . mysql_error());
        $r_sql = mysql_fetch_array($sql);
        return $r_sql['desg'];
    }

    function get_case_remarks($dn, $cldate, $jcodes, $clno)
    {
        $sql_cr = "select h.cat_head_id, c.cl_date, c.jcodes, c.status, GROUP_CONCAT(CONCAT(h.head, if(c.head_content!='', concat(' [', c.head_content, ']'),'')) SEPARATOR ', ') AS crem, GROUP_CONCAT(CONCAT(c.r_head, '|', c.head_content, '^^') SEPARATOR '') AS caseval,c.mainhead,c.clno FROM     case_remarks_multiple c inner join case_remarks_head h on c.r_head=h.sno WHERE c.diary_no = " . $dn . " AND c.cl_date = '" . $cldate . "' AND c.jcodes='" . $jcodes . "' AND c.clno='" . $clno . "' GROUP BY c.cl_date ORDER BY h.priority";
        $result_cr = mysql_query($sql_cr) or die("Errror: " . __LINE__ . mysql_error());
        $cval = "";
        if (mysql_num_rows($result_cr) > 0) {
            $row_cr = mysql_fetch_array($result_cr);
            $crem = $row_cr['crem'];
        } else {
            $crem = '';
        }
        return $crem;
    }

    function get_judges($jcodes)
    {
        $jnames = "";
        if ($jcodes != '') {
            $t_jc = explode(",", $jcodes);
            for ($i = 0; $i < count($t_jc); $i++) {
                $sql11a = "SELECT jname  FROM  judge where jcode= " . $t_jc[$i];
                $t11a = mysql_query($sql11a);
                if (mysql_affected_rows() > 0) {
                    while ($row11a = mysql_fetch_array($t11a)) {
                        if ($jnames == '')
                            $jnames .= $row11a["jname"];
                        else {
                            if ($i == (count($t_jc) - 1))
                                $jnames .= " and " . $row11a["jname"];
                            else
                                $jnames .= ", " . $row11a["jname"];
                        }
                    }
                }
            }
        }
        return $jnames;
    }

    function get_ma_info($c_type, $c_no, $c_yr)
    {
        $ex_explode = explode('-', $c_no);
        $lct_caseno = '';
        for ($index = 0; $index < count($ex_explode); $index++) {
            if ($lct_caseno == '')
                $lct_caseno = $ex_explode[$index];
            else
                $lct_caseno = $lct_caseno . ',' . $ex_explode[$index];
        }
        $sql = "Select distinct diary_no from lowerct where lct_casetype='$c_type' and lct_caseno in ($lct_caseno) and lct_caseyear='$c_yr' and lw_display='Y'";
        $sql =  mysql_query($sql) or die("Error: " . __LINE__ . mysql_error());
        $outer_array = array();

        while ($row = mysql_fetch_array($sql)) {
            $inner_array = array();
            $inner_array[0] = $row['diary_no'];
            $outer_array[] = $inner_array;
        }
        return $outer_array;
    }


    function get_case_nos($dn, $separator, $rby = '')
    {
        $t_fil_no = '';
        $sql_from_main = "SELECT CONCAT(m.active_fil_no, ':', IF((active_reg_year = 0 OR DATE(active_fil_dt) > DATE('2017-05-10')), YEAR(active_fil_dt), active_reg_year ), ':', DATE_FORMAT(active_fil_dt, '%d-%m-%Y')) ad, IF(fil_no_fh!=active_fil_no AND fil_no_fh!=fil_no AND fil_no_fh!='', CONCAT(
        m.fil_no_fh, ':', IF((reg_year_fh = 0 OR DATE(fil_dt_fh) > DATE('2017-05-10')), YEAR(fil_dt_fh), reg_year_fh), ':', DATE_FORMAT(fil_dt_fh, '%d-%m-%Y')),'') rd, IF(fil_no!=active_fil_no AND fil_no_fh!=fil_no AND fil_no!='', CONCAT(m.fil_no, ':', IF((reg_year_mh = 0 OR DATE(fil_dt) > DATE('2017-05-10')), YEAR(fil_dt), reg_year_mh), ':', DATE_FORMAT(fil_dt, '%d-%m-%Y')),'') md FROM main m WHERE `diary_no` = " . $dn;

        $result_main = mysql_query($sql_from_main) or die(mysql_error() . $sql_from_main);
        $cases = "";
        if (mysql_affected_rows() > 0) {
            $row_main = mysql_fetch_array($result_main);

            if ($row_main['ad'] != '') {
                $t_m_y = explode(':', $row_main['ad']);
                if ($t_m_y[0] != '') {
                    $cases .= $t_m_y[0] . ",";
                    $t_m1 = substr($t_m_y[0], 0, 2);
                    $t_m2 = substr($t_m_y[0], 3, 6);
                    $t_m21 = substr($t_m_y[0], 10, 6);
                    $t_m3 = $t_m_y[1];
                    $t_m4 = $t_m_y[2];
                    $sql_ct_type = mysql_query("Select short_description,cs_m_f from casetype where casecode='" . $t_m1 . "' and display='Y'") or die("Error" . __LINE__ . mysql_error());
                    $row = mysql_fetch_array($sql_ct_type);
                    $res_ct_typ = $row['short_description'];
                    $res_ct_typ_mf = $row['cs_m_f'];

                    if ($t_m2 == $t_m21 || $t_m21 == '')
                        $t_fil_no .= '<font color="#043fff" style=" white-space: nowrap;">' . $res_ct_typ . " " . $t_m2 . ' / ' . $t_m3 . '</font>' . $separator . "(Reg.Dt." . $t_m4 . ")<br>";
                    else
                        $t_fil_no .= '<font color="#043fff"  style=" white-space: nowrap;">' . $res_ct_typ . " " . $t_m2 . ' - ' . $t_m21 . ' / ' . $t_m3 . '</font>' . $separator . "(Reg.Dt." . $t_m4 . ")<br>";
                }
            }

            if ($row_main['rd'] != '') {
                $t_m_y = explode(':', $row_main['rd']);
                if ($t_m_y[0] != '') {
                    $cases .= $t_m_y[0] . ",";
                    $t_m1 = substr($t_m_y[0], 0, 2);
                    $t_m2 = substr($t_m_y[0], 3, 6);
                    $t_m21 = substr($t_m_y[0], 10, 6);
                    $t_m3 = $t_m_y[1];
                    $t_m4 = $t_m_y[2];
                    $sql_ct_type = mysql_query("Select short_description,cs_m_f from casetype where casecode='" . $t_m1 . "' and display='Y'") or die("Error" . __LINE__ . mysql_error());
                    $row = mysql_fetch_array($sql_ct_type);
                    $res_ct_typ = $row['short_description'];
                    $res_ct_typ_mf = $row['cs_m_f'];

                    if ($t_m2 == $t_m21)
                        $t_fil_no .= '<font color="#043fff" style=" white-space: nowrap;">' . $res_ct_typ . " " . $t_m2 . ' / ' . $t_m3 . '</font>' . $separator . "(Reg.Dt." . $t_m4 . ")<br>";
                    else
                        $t_fil_no .= '<font color="#043fff" style=" white-space: nowrap;">' . $res_ct_typ . " " . $t_m2 . ' - ' . $t_m21 . ' / ' . $t_m3 . '</font>' . $separator . "(Reg.Dt." . $t_m4 . ")<br>";
                }
            }

            if ($row_main['md'] != '') {

                $t_m_y = explode(':', $row_main['md']);
                if ($t_m_y[0] != '') {
                    $cases .= $t_m_y[0] . ",";
                    $t_m1   = substr($t_m_y[0], 0, 2);
                    $t_m2   = substr($t_m_y[0], 3, 6);
                    $t_m21  = substr($t_m_y[0], 10, 6);
                    $t_m3   = $t_m_y[1];
                    $t_m4   = $t_m_y[2];
                    $sql_ct_type = mysql_query("Select short_description,cs_m_f from casetype where casecode='" . $t_m1 . "' and display='Y'") or die("Error" . __LINE__ . mysql_error());
                    $row = mysql_fetch_array($sql_ct_type);
                    $res_ct_typ = $row['short_description'];
                    $res_ct_typ_mf = $row['cs_m_f'];

                    if ($t_m2 == $t_m21 || $t_m21 == '')
                        $t_fil_no .= '<font color="#043fff" style=" white-space: nowrap;">' . $res_ct_typ . " " . $t_m2 . ' / ' . $t_m3 . '</font>' . $separator . "(Reg.Dt." . $t_m4 . ")<br>";
                    else
                        $t_fil_no .= '<font color="#043fff" style=" white-space: nowrap;">' . $res_ct_typ . " " . $t_m2 . ' - ' . $t_m21 . ' / ' . $t_m3 . '</font>' . $separator . "(Reg.Dt." . $t_m4 . ")<br>";
                }
            }
        }

        $sql_mc_h = "SELECT t.oldno, GROUP_CONCAT(DISTINCT CONCAT(t.new_registration_number,':',t.new_registration_year,':',DATE_FORMAT(t.order_date,'%d-%m-%Y')) ORDER BY t.order_date,t.id ) AS newno FROM (SELECT @rowid:=@rowid+1 AS rowid,`main_casetype_history`.*, IF(@rowid=1,IF(old_registration_number='' OR old_registration_number IS NULL,'',CONCAT(old_registration_number,':',old_registration_year,':',DATE_FORMAT(order_date,'%d-%m-%Y'))),'') AS oldno FROM `main_casetype_history`, (SELECT @rowid:=0) AS init WHERE `diary_no` = " . $dn . "  AND is_deleted = 'f' ORDER BY `main_casetype_history`.`order_date`,id ) t ";

        $result_mc_h = mysql_query($sql_mc_h) or die(mysql_error() . $sql_mc_h);
        if (mysql_affected_rows() > 0) {
            $cnt = 0;
            while ($row_mc_h = mysql_fetch_array($result_mc_h)) {
                // echo $row_mc_h['oldno'].":".$row_mc_h['newno'].":<br>";

                if ($row_mc_h['oldno'] != '') {
                    $t_m = explode(',', $row_mc_h['oldno']);
                    $t_m_y = explode(':', $t_m[0]);
                    $pos = strpos($cases, $t_m_y[0]);

                    if ($pos === false) {
                        $cnt++;
                        if ($cnt % 2 == 0)
                            $bgcolor = "#ff0015";
                        else
                            $bgcolor = "#ff01c8";

                        $cases .= $t_m_y[0] . ",";
                        $t_m1 = substr($t_m_y[0], 0, 2);
                        $t_m2 = substr($t_m_y[0], 3, 6);
                        $t_m21 = substr($t_m_y[0], 10, 6);
                        $t_m3 = $t_m_y[1];
                        $t_m4 = $t_m_y[2];

                        $sql_ct_type = mysql_query("Select short_description,cs_m_f from casetype where casecode='" . $t_m1 . "' and display='Y'") or die("Error" . __LINE__ . mysql_error());
                        $row = mysql_fetch_array($sql_ct_type);
                        $res_ct_typ = $row['short_description'];
                        $res_ct_typ_mf = $row['cs_m_f'];

                        if ($t_m2 == $t_m21 || $t_m21 == '')
                            $t_fil_no .= '<font color="' . $bgcolor . '" style=" white-space: nowrap;">' . $res_ct_typ . " " . $t_m2 . ' / ' . $t_m3 . '</font>' . $separator . "(Reg.Dt." . $t_m4 . ")<br>";
                        else
                            $t_fil_no .= '<font color="' . $bgcolor . '" style=" white-space: nowrap;">' . $res_ct_typ . " " . $t_m2 . ' - ' . $t_m21 . ' / ' . $t_m3 . '</font>' . $separator . "(Reg.Dt." . $t_m4 . ")<br>";
                    }
                }
                $t_chk = "";

                if ($row_mc_h['newno'] != '') {
                    $t_m = explode(',', $row_mc_h['newno']);
                    for ($i = 0; $i < count($t_m); $i++) {
                        $t_m_y = explode(':', $t_m[$i]);
                        $pos = strpos($cases, $t_m_y[0]);

                        if ($pos === false) {
                            $cases .= $t_m_y[0] . ",";
                            $t_m1 = substr($t_m_y[0], 0, 2);
                            $t_m2 = substr($t_m_y[0], 3, 6);
                            $t_m21 = substr($t_m_y[0], 10, 6);
                            $t_m3 = $t_m_y[1];
                            $t_m4 = $t_m_y[2];
                            $t_fn = $t_m_y[0];

                            if ($t_chk != $t_fn) {
                                $cnt++;
                                if ($cnt % 2 == 0)
                                    $bgcolor = "#ff0015";
                                else
                                    $bgcolor = "#ff01c8";

                                $sql_ct_type = mysql_query("Select short_description,cs_m_f from casetype where casecode='" . $t_m1 . "' and display='Y'") or die("Error" . __LINE__ . mysql_error());
                                $row = mysql_fetch_array($sql_ct_type);
                                $res_ct_typ = $row['short_description'];
                                $res_ct_typ_mf = $row['cs_m_f'];

                                if ($t_m2 == $t_m21 || $t_m21 == '')
                                    $t_fil_no .= '<font color="' . $bgcolor . '" style=" white-space: nowrap;">' . $res_ct_typ . " " . $t_m2 . ' / ' . $t_m3 . '</font>' . $separator . "(Reg.Dt." . $t_m4 . ")<br>";
                                else
                                    $t_fil_no .= '<font color="' . $bgcolor . '" style=" white-space: nowrap;">' . $res_ct_typ . " " . $t_m2 . ' - ' . $t_m21 . ' / ' . $t_m3 . '</font>' . $separator . "(Reg.Dt." . $t_m4 . ")<br>";
                            }
                            $t_chk = $t_fn;
                        }
                    }
                }
            }
        }

        if (trim($t_fil_no) == '') {
            $sql12 =   "SELECT short_description from casetype where casecode='" . $row['casetype_id'] . "'";
            $results12 = mysql_query($sql12) or die(mysql_error() . " SQL:" . $sql12);
            if (mysql_affected_rows() > 0) {
                $row_12 = mysql_fetch_array($results12);
                $t_fil_no = $row_12['short_description'];
            }
        }
        return $t_fil_no;
    }


    function upd_ins_conn_cases_proposal($dn, $cc, $list, $uc)
    {
        //-NEW CODE FOR CONNECTED CASE
        $sql_conn = "SELECT * FROM conct WHERE conn_key='" . $cc . "' and diary_no='" . $dn . "'";
        $result_conn = mysql_query($sql_conn) or die(mysql_error());
        if (mysql_affected_rows() > 0) {
            $row_conn = mysql_fetch_array($result_conn);
            if ($row_conn['conn_key'] != $cc or $row_conn['diary_no'] != $dn or $row_conn['list'] != $list or $row_conn['usercode'] != $uc) {
                $sql_conn_ins_h = "INSERT INTO conct_history select *,'" . $uc . "',NOW() FROM conct WHERE conn_key='" . $cc . "' AND diary_no='" . $dn . "'";
                $sql_conn_upd = "UPDATE conct SET `list`='" . $list . "',usercode=" . $uc . ",ent_dt=NOW() WHERE conn_key='" . $cc . "' AND diary_no='" . $dn . "'";

                //$sql_conn_ins="INSERT INTO conct(conn_key,diary_no,`list`,usercode,ent_dt,conn_type) VALUES('".$cc."','".$dn."','".$list."',".$uc.",NOW(),'".$row_conn["conn_type"]."')";
                mysql_query($sql_conn_ins_h) or die(mysql_error());
                mysql_query($sql_conn_upd) or die(mysql_error());
            }
        } else {
            echo 'ERROR: Something gone wrong.';
        }
        //-NEW CODE FOR CONNECTED CASE 
    }
    function change_date_format($date)
    {
        if ($date == "" or $date == "0000-00-00")
            $date = "";
        else
            $date = date('d-m-Y', strtotime($date));
        return $date;
    }
    function get_purpose($purpose_code)
    {
        $purpose = "";
        if ($purpose_code != "") {
            $sql_p = "SELECT purpose FROM listing_purpose WHERE code='" . $purpose_code . "'";
            $result_p = mysql_query($sql_p) or die(mysql_error());
            $row_p = mysql_fetch_array($result_p);
            $purpose = $row_p['purpose'];
        }
        return $purpose;
    }
    function get_stage($stage_code, $mainhead)
    {
        $stage = "";
        if ($stage_code != "") {
            if ($mainhead == "M") {
                $sql_p = "SELECT stagename FROM subheading WHERE stagecode='" . $stage_code . "'";
                $result_p = mysql_query($sql_p) or die(mysql_error());
                $row_p = mysql_fetch_array($result_p);
                $stage = $row_p['stagename'];
            }
            if ($mainhead == "F") {
                $sql_p = "SELECT * FROM submaster WHERE id='" . $stage_code . "'";
                $result_p = mysql_query($sql_p) or die(mysql_error());
                $row_p = mysql_fetch_array($result_p);
                if ($row_p['subcode1'] > 0 and $row_p['subcode2'] == 0 and $row_p['subcode3'] == 0 and $row_p['subcode4'] == 0)
                    $stage =  $row_p['sub_name1'];
                elseif ($row_p['subcode1'] > 0 and $row_p['subcode2'] > 0 and $row_p['subcode3'] == 0 and $row_p['subcode4'] == 0)
                    $stage =  $row_p['sub_name1'] . " : " . $row_p['sub_name4'];
                elseif ($row_p['subcode1'] > 0 and $row_p['subcode2'] > 0 and $row_p['subcode3'] > 0 and $row_p['subcode4'] == 0)
                    $stage =  $row_p['sub_name1'] . " : " . $row_p['sub_name2'] . " : " . $row_p['sub_name4'];
                elseif ($row_p['subcode1'] > 0 and $row_p['subcode2'] > 0 and $row_p['subcode3'] > 0 and $row_p['subcode4'] > 0)
                    $stage =  $row_p['sub_name1'] . " : " . $row_p['sub_name2'] . " : " . $row_p['sub_name3'] . " : " . $row_p['sub_name4'];
            }
        }
        return $stage;
    }
    function get_subhead($stage_code, $mainhead)
    {
        $stage = "";
        if ($mainhead == "M") {
            $sql_p = "select * from subheading where (listtype=1 or listtype=3) and display='Y' and !(stagecode>=201 and stagecode<=212) and !(stagecode>=501 and stagecode<=550) and stagecode!=809  order by stagecode";
            $result_p = mysql_query($sql_p) or die(mysql_error());
            while ($row_p = mysql_fetch_array($result_p)) {
                if ($row_p["stagecode"] == $stage_code)
                    $stage .= '<option value="' . $row_p["stagecode"] . '" selected=selected>' . $row_p["stagename"] . '</option>';
                else
                    $stage .= '<option value="' . $row_p["stagecode"] . '" >' . $row_p["stagename"] . '</option>';
            }
        }
        return $stage;
    }
    ///FUNCTION
    function get_conn_cases($dn)
    {
        $me2 = array();
        $chk_for_main = '';
        if ($dn != "") {
            $sql_p1 = "SELECT conn_key FROM main WHERE (diary_no='" . $dn . "')";
            $result_p1 = mysql_query($sql_p1) or die(mysql_error());
            $conn_key = mysql_result($result_p1, 0);
            $sql_p = "SELECT diary_no,if(conn_key=diary_no, 'M',conn_type) as c_type,list FROM conct WHERE (conn_key='" . $conn_key . "') ORDER BY if(diary_no='" . $conn_key . "',0,1),c_type DESC";
            $result_p = mysql_query($sql_p) or die(mysql_error());
            while ($row = mysql_fetch_array($result_p)) {
                if ($chk_for_main == '' and $row['c_type'] != 'M') {
                    $me2[$conn_key]['diary_no'] = $conn_key;
                    $me2[$conn_key]['c_type'] = 'M';
                    $me2[$conn_key]['list'] = 'Y';
                    $chk_for_main = 'over';
                }
                $me2[$row['diary_no']]['diary_no'] = $row['diary_no'];
                $me2[$row['diary_no']]['c_type'] = $row['c_type'];
                $me2[$row['diary_no']]['list'] = $row['list'];
            }
        }
        return $me2;
    }
    ///FUNCTION
    function get_main_details($dn, $fields)
    {
        $data_array = array();
        if ($dn != "") {
            if ($fields == "")
                $fields = "*";
            $sql = mysql_query("Select " . $fields . " from main where diary_no='" . $dn . "'") or die('Error: ' . __LINE__ . mysql_error());
            if (mysql_num_rows($sql) > 0) {
                while ($row = mysql_fetch_assoc($sql)) {
                    foreach ($row as $key => $value) {
                        $data_array[$row['diary_no']][$key] = $value;
                    }
                }
            }
        }
        return $data_array;
    }
    function get_mul_category($dn)
    {
        $mul_category = "";
        if ($dn != "") {
            $category = "SELECT b.* FROM mul_category a, submaster b where a.diary_no='" . $dn . "'  and a.display='Y' and a.submaster_id=b.id";
            $category = mysql_query($category) or die("Error: " . __LINE__ . mysql_error());
            if (mysql_num_rows($category) > 0) {
                $category_nm = '';
                $mul_category = '';
                while ($row2 = mysql_fetch_array($category)) {
                    //                                        $category_nm = $row2['subject_description'] . ' - ' . $row2['category_description'];
                    if ($row2['subcode1'] > 0 and $row2['subcode2'] == 0 and $row2['subcode3'] == 0 and $row2['subcode4'] == 0)
                        $category_nm =  $row2['sub_name1'];
                    elseif ($row2['subcode1'] > 0 and $row2['subcode2'] > 0 and $row2['subcode3'] == 0 and $row2['subcode4'] == 0)
                        $category_nm =  $row2['sub_name1'] . " : " . $row2['sub_name4'];
                    elseif ($row2['subcode1'] > 0 and $row2['subcode2'] > 0 and $row2['subcode3'] > 0 and $row2['subcode4'] == 0)
                        $category_nm =  $row2['sub_name1'] . " : " . $row2['sub_name2'] . " : " . $row2['sub_name4'];
                    elseif ($row2['subcode1'] > 0 and $row2['subcode2'] > 0 and $row2['subcode3'] > 0 and $row2['subcode4'] > 0)
                        $category_nm =  $row2['sub_name1'] . " : " . $row2['sub_name2'] . " : " . $row2['sub_name3'] . " : " . $row2['sub_name4'];

                    if ($mul_category == '') {
                        $mul_category = $row2['category_sc_old'] . '-' . $category_nm;
                    } else {
                        $mul_category = $row2['category_sc_old'] . '-' . $mul_category . ',<br> ' . $category_nm;
                    }
                }
            }
        }
        return $mul_category;
    }
    //FUNCTION
    function get_brd_remarks($dn)
    {
        $brdrem = "";
        $sqlbr_conn = "select remark from brdrem where diary_no='" . $dn . "'";
        $results_br_conn = mysql_query($sqlbr_conn);
        if (mysql_affected_rows() > 0) {
            $row_br_conn = mysql_fetch_array($results_br_conn);
            $brdrem = $row_br_conn[remark];
        }
        return $brdrem;
    }
    //FUNCTION
    function get_ia($dn)
    {
        $ian_p_conn = "";
        $sql_ian_conn = "select a.diary_no,a.doccode,a.doccode1,a.docnum,a.docyear,a.filedby,a.docfee,a.forresp,a.feemode,a.ent_dt,a.other1,a.iastat,b.docdesc from docdetails a,  docmaster b  where a.doccode=b.doccode and a.doccode1=b.doccode1 and a.diary_no='" . $dn . "' and a.doccode=8 and a.display='Y' order by ent_dt";
        $results_ian_conn = mysql_query($sql_ian_conn);
        $iancntr_conn = 1;
        if (mysql_affected_rows() > 0) {
            $ian_p_inhdt = $listed_ia_conn = "";
            $sql_ian_inhdt = "select listed_ia from heardt  where diary_no='" . $dn . "'";
            $results_ian_inhdt = mysql_query($sql_ian_inhdt);
            if (mysql_affected_rows() > 0) {
                $row_ian_inhdt = mysql_fetch_array($results_ian_inhdt);
                $listed_ia_conn = $row_ian_inhdt["listed_ia"];
            }
            while ($row_ian_conn = mysql_fetch_array($results_ian_conn)) {
                if ($ian_p_conn == "" and $row_ian_conn["iastat"] == "P") {
                    $ian_p_conn = "<div style='overflow:auto; max-height:100px;'><table border='1' bgcolor='#F5F5FC' class='tbl_hr' width='98%' cellspacing='0' cellpadding='3'>";
                }
                if ($row_ian_conn["other1"] != "")
                    $t_part_conn = $row_ian_conn["docdesc"] . " [" . $row_ian_conn["other1"] . "]";
                else
                    $t_part_conn = $row_ian_conn["docdesc"];
                $t_ia_conn = "";
                if ($row_ian_conn["iastat"] == "P")
                    $t_ia_conn = "<font color='blue'>" . $row_ian_conn["iastat"] . "</font>";
                if ($row_ian_conn["iastat"] == "D")
                    $t_ia_conn = "<font color='red'>" . $row_ian_conn["iastat"] . "</font>";
                if ($row_ian_conn["iastat"] == "P") {
                    $t_iaval_conn = $row_ian_conn["docnum"] . "/" . $row_ian_conn["docyear"] . ",";
                    if (strpos($listed_ia_conn, $t_iaval_conn) !== false)
                        $check = "checked='checked'";
                    else
                        $check = "";
                    $ian_p_conn .= "<tr><td align='center'><input type='checkbox' name='cn_ia_" . $row_ian_conn["diary_no"] . "_" . $iancntr_conn . "' id='cn_ia_" . $row_ian_conn["diary_no"] . "_" . $iancntr_conn . "' value='" . $row_ian_conn["diary_no"] . "|#|" . $row_ian_conn["docnum"] . "/" . $row_ian_conn["docyear"] . "|#|" . str_replace("XTRA", "", $t_part_conn) . "' onClick='feed_rmrk_conn(\"" . $row_ian_conn["diary_no"] . "\");' " . $check . "></td><td align='center'>" . $row_ian_conn["docnum"] . "/" . $row_ian_conn["docyear"] . "</td><td align='left'>" . str_replace("XTRA", "", $t_part_conn) . "</td><td align='center'>" . date("d-m-Y", strtotime($row_ian_conn["ent_dt"])) . "</td></tr>";
                }
                $iancntr_conn++;
            }
        }
        if ($ian_p_conn != "")
            $ian_p_conn .= "</table></div>";
        return $ian_p_conn;
    }
    //FUNCTION
    function get_real_diaryno($dn)
    {
        $real_diary_no = "";
        if ($dn != "") {
            $real_diary_no = substr($dn, 0, -4) . "/" . substr($dn, -4);
        }
        return $real_diary_no;
    }

    function get_casenos_comma($dn)
    {
        $t_fil_no = '';
        $sql_from_main = "SELECT 
    CONCAT(
        m.active_fil_no,
        ':',
        IF(
          (
            active_reg_year = 0 
            OR DATE(active_fil_dt) > DATE('2017-05-10')
            ),
          YEAR(active_fil_dt),
          active_reg_year
          ),
        ':',
        DATE_FORMAT(active_fil_dt, '%d-%m-%Y')
        ) ad,
    IF(fil_no_fh!=active_fil_no AND fil_no_fh!=fil_no AND fil_no_fh!='', CONCAT(
        m.fil_no_fh,
        ':',
        IF(
          (
            reg_year_fh = 0 
            OR DATE(fil_dt_fh) > DATE('2017-05-10')
            ),
          YEAR(fil_dt_fh),
          reg_year_fh
          ),
        ':',
        DATE_FORMAT(fil_dt_fh, '%d-%m-%Y')
        ),'') rd,
    IF(fil_no!=active_fil_no AND fil_no_fh!=fil_no AND fil_no!='', CONCAT(
        m.fil_no,
        ':',
        IF(
          (
            reg_year_mh = 0 
            OR DATE(fil_dt) > DATE('2017-05-10')
            ),
          YEAR(fil_dt),
          reg_year_mh
          ),
        ':',
        DATE_FORMAT(fil_dt, '%d-%m-%Y')
        ),'') md
    FROM
    main m 
    WHERE `diary_no` = " . $dn;
        $result_main = mysql_query($sql_from_main) or die(mysql_error() . $sql_from_main);
        $cases = "";
        if (mysql_affected_rows() > 0) {
            $row_main = mysql_fetch_array($result_main);
            if ($row_main['ad'] != '') {
                $t_m_y = explode(':', $row_main['ad']);
                if ($t_m_y[0] != '') {
                    $cases .= $t_m_y[0] . ",";
                    $t_m1 = substr($t_m_y[0], 0, 2);
                    $t_m2 = substr($t_m_y[0], 3, 6);
                    $t_m21 = substr($t_m_y[0], 10, 6);
                    $t_m3 = $t_m_y[1];
                    $t_m4 = $t_m_y[2];
                    $sql_ct_type = mysql_query("Select short_description,cs_m_f from casetype where casecode='" . $t_m1 . "' and display='Y'") or die("Error" . __LINE__ . mysql_error());
                    $row = mysql_fetch_array($sql_ct_type);
                    $res_ct_typ = $row['short_description'];
                    $res_ct_typ_mf = $row['cs_m_f'];
                    if ($t_m2 == $t_m21)
                        $t_fil_no .= $res_ct_typ . " " . $t_m2 . ' / ' . $t_m3 . ",&nbsp;&nbsp;";
                    else
                        $t_fil_no .= $res_ct_typ . " " . $t_m2 . ' - ' . $t_m21 . ' / ' . $t_m3 . ",&nbsp;&nbsp;";
                }
            }
            if ($row_main['rd'] != '') {
                $t_m_y = explode(':', $row_main['rd']);
                if ($t_m_y[0] != '') {
                    $cases .= $t_m_y[0] . ",";
                    $t_m1 = substr($t_m_y[0], 0, 2);
                    $t_m2 = substr($t_m_y[0], 3, 6);
                    $t_m21 = substr($t_m_y[0], 10, 6);
                    $t_m3 = $t_m_y[1];
                    $t_m4 = $t_m_y[2];
                    $sql_ct_type = mysql_query("Select short_description,cs_m_f from casetype where casecode='" . $t_m1 . "' and display='Y'") or die("Error" . __LINE__ . mysql_error());
                    $row = mysql_fetch_array($sql_ct_type);
                    $res_ct_typ = $row['short_description'];
                    $res_ct_typ_mf = $row['cs_m_f'];
                    if ($t_m2 == $t_m21)
                        $t_fil_no .= $res_ct_typ . " " . $t_m2 . ' / ' . $t_m3 . ",&nbsp;&nbsp;";
                    else
                        $t_fil_no .= $res_ct_typ . " " . $t_m2 . ' - ' . $t_m21 . ' / ' . $t_m3 . ",&nbsp;&nbsp;";
                }
            }
            if ($row_main['md'] != '') {

                $t_m_y = explode(':', $row_main['md']);
                if ($t_m_y[0] != '') {
                    $cases .= $t_m_y[0] . ",";
                    $t_m1 = substr($t_m_y[0], 0, 2);
                    $t_m2 = substr($t_m_y[0], 3, 6);
                    $t_m21 = substr($t_m_y[0], 10, 6);
                    $t_m3 = $t_m_y[1];
                    $t_m4 = $t_m_y[2];
                    $sql_ct_type = mysql_query("Select short_description,cs_m_f from casetype where casecode='" . $t_m1 . "' and display='Y'") or die("Error" . __LINE__ . mysql_error());
                    $row = mysql_fetch_array($sql_ct_type);
                    $res_ct_typ = $row['short_description'];
                    $res_ct_typ_mf = $row['cs_m_f'];
                    if ($t_m2 == $t_m21)
                        $t_fil_no .= $res_ct_typ . " " . $t_m2 . ' / ' . $t_m3 . ",&nbsp;&nbsp;";
                    else
                        $t_fil_no .= $res_ct_typ . " " . $t_m2 . ' - ' . $t_m21 . ' / ' . $t_m3 . ",&nbsp;&nbsp;";
                }
            }
        }
        $sql_mc_h = "SELECT t.oldno,
    GROUP_CONCAT(DISTINCT CONCAT(t.new_registration_number,':',t.new_registration_year,':',DATE_FORMAT(t.order_date,'%d-%m-%Y')) ORDER BY t.order_date,t.id ) AS newno FROM
    (SELECT @rowid:=@rowid+1 AS rowid,`main_casetype_history`.*, IF(@rowid=1,IF(old_registration_number='' OR old_registration_number IS NULL,'',CONCAT(old_registration_number,':',old_registration_year,':',DATE_FORMAT(order_date,'%d-%m-%Y'))),'') AS oldno 
        FROM `main_casetype_history`, (SELECT @rowid:=0) AS init
        WHERE `diary_no` = " . $dn . " AND is_deleted='f'
        ORDER BY `main_casetype_history`.`order_date`,id ) t GROUP BY t.diary_no";

        $result_mc_h = mysql_query($sql_mc_h) or die(mysql_error() . $sql_mc_h);
        if (mysql_affected_rows() > 0) {

            while ($row_mc_h = mysql_fetch_array($result_mc_h)) {
                // echo $row_mc_h['oldno'].":".$row_mc_h['newno'].":<br>";
                if ($row_mc_h['oldno'] != '') {
                    $t_m = explode(',', $row_mc_h['oldno']);

                    $t_m_y = explode(':', $t_m[0]);
                    $pos = strpos($cases, $t_m_y[0]);

                    if ($pos === false) {
                        $cases .= $t_m_y[0] . ",";
                        $t_m1 = substr($t_m_y[0], 0, 2);
                        $t_m2 = substr($t_m_y[0], 3, 6);
                        $t_m21 = substr($t_m_y[0], 10, 6);
                        $t_m3 = $t_m_y[1];
                        $t_m4 = $t_m_y[2];
                        $sql_ct_type = mysql_query("Select short_description,cs_m_f from casetype where casecode='" . $t_m1 . "' and display='Y'") or die("Error" . __LINE__ . mysql_error());
                        $row = mysql_fetch_array($sql_ct_type);
                        $res_ct_typ = $row['short_description'];
                        $res_ct_typ_mf = $row['cs_m_f'];
                        if ($t_m2 == $t_m21)
                            $t_fil_no .= $res_ct_typ . " " . $t_m2 . ' / ' . $t_m3 . ",&nbsp;&nbsp;";
                        else
                            $t_fil_no .= $res_ct_typ . " " . $t_m2 . ' - ' . $t_m21 . ' / ' . $t_m3 . ",&nbsp;&nbsp;";
                    }
                }
                $t_chk = "";

                if ($row_mc_h['newno'] != '') {
                    $t_m = explode(',', $row_mc_h['newno']);
                    for ($i = 0; $i < count($t_m); $i++) {
                        $t_m_y = explode(':', $t_m[$i]);
                        $pos = strpos($cases, $t_m_y[0]);
                        if ($pos === false) {
                            $cases .= $t_m_y[0] . ",";
                            $t_m1 = substr($t_m_y[0], 0, 2);
                            $t_m2 = substr($t_m_y[0], 3, 6);
                            $t_m21 = substr($t_m_y[0], 10, 6);
                            $t_m3 = $t_m_y[1];
                            $t_m4 = $t_m_y[2];
                            $t_fn = $t_m_y[0];
                            if ($t_chk != $t_fn) {
                                $sql_ct_type = mysql_query("Select short_description,cs_m_f from casetype where casecode='" . $t_m1 . "' and display='Y'") or die("Error" . __LINE__ . mysql_error());
                                $row = mysql_fetch_array($sql_ct_type);
                                $res_ct_typ = $row['short_description'];
                                $res_ct_typ_mf = $row['cs_m_f'];
                                if ($t_m2 == $t_m21)
                                    $t_fil_no .= $res_ct_typ . " " . $t_m2 . ' / ' . $t_m3 . ",&nbsp;&nbsp;";
                                else
                                    $t_fil_no .= $res_ct_typ . " " . $t_m2 . ' - ' . $t_m21 . ' / ' . $t_m3 . ",&nbsp;&nbsp;";
                            }
                            $t_chk = $t_fn;
                        }
                    }
                }
            }
        }

        if (trim($t_fil_no) == '') {
            $sql12 =   "SELECT short_description from casetype where casecode='" . $row['casetype_id'] . "'";
            $results12 = mysql_query($sql12) or die(mysql_error() . " SQL:" . $sql12);
            if (mysql_affected_rows() > 0) {
                $row_12 = mysql_fetch_array($results12);
                $t_fil_no = $row_12['short_description'];
            }
        }
        return $t_fil_no;
    }



    function get_serve_type($serve_id)
    {
        $sql = "Select name from tw_serve where id='$serve_id' and display='Y'";
        $sql =  mysql_query($sql) or die("Error: " . __LINE__ . mysql_error());
        $res_sql =  mysql_result($sql, 0);
        return $res_sql;
    }
}
