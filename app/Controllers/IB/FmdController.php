<?php

namespace App\Controllers\IB;

use App\Controllers\BaseController;
use App\Models\IB\FmdModel;

class FmdController extends BaseController
{
    public $FmdModel;

    function __construct()
    {
        $this->FmdModel = new FmdModel();
    }

    public function set_dispose()
    {
        return view('IB/set_dispose');
    }

    public function set_dispose_process()
    {
        $data['ucode'] = session()->get('login')['usercode'];
        $data['ct'] = $this->request->getGet('ct');
        $data['cn'] = $this->request->getGet('cn');
        $data['cy'] = $this->request->getGet('cy');
        $data['d_no'] = $this->request->getGet('d_no');
        $data['d_yr'] = $this->request->getGet('d_yr');

        $data['model'] = $this->FmdModel;

        return view('IB/set_dispose_process', $data);
    }

    function chk_disp_date($disp_dt)
    {
        $m1 = date('m', strtotime($disp_dt));
        $y1 = date('Y', strtotime($disp_dt));
        $m2 = date('m');
        $y2 = date('Y');
        $y = $y2 - $y1;
        if ($m2 >= $m1) {
            $m = $m2 - $m1;
        } else {
            $m = 12 - ($m1 - $m2);
            $y--;
        }
        $rm = ($y * 12) + $m;
        return $rm;
    }

    public function insert_rec_an_disp()
    {
        $ucode = session()->get('login')['usercode'];
        $check_for_regular_case = "";
        $temp_mh = '';
        $tdt_str = '';
        $parameters = $this->request->getPost('parameters');
        $str = $parameters['str'];
        $str1 = $parameters['str1'];
        $dt = $parameters['dt'];
        $hdt = $parameters['hdt'];
        $rjdt = $parameters['rjdt'] ?? null;

        if (isset($parameters['concstr']))
            $concstr = $parameters['concstr'];
        else
            $concstr = "";

        if ($concstr != '') {
            $cncases = explode(',', $concstr);
            $cncntr = count($cncases);
        } else {
            $cncntr = 1;
        }

        $bench = '';
        $uip1 = '';
        $umac1 = '';
        $rec = explode("#", $str);
        $fno = $rec[0];
        $status = $rec[1];
        $rec_rem = explode("!", $rec[2]);
        $subh = $rec[3];
        $jcodes = $str1;
        $jcodes1 = explode(",", $jcodes);
        $j1 = $jcodes1[0];
        $err = "";
        $rcount = count($rec_rem);
        $head_r = array();
        $head_c = array();
        $i_cnt = 0;
        $check_case_withdraw = "";
        for ($i = 0; ($i < ($rcount - 1)); $i++) {
            $rec1 = explode("|", $rec_rem[$i]);
            //$rem=$rec1[1];
            $head = $rec1[0];
            $head_cont = $rec1[1];
            if ($head == 35) {
                $check_case_withdraw = "YES";
            }
            if ($head != 16) {
                $head_r[$i_cnt] = $rec1[0];
                $head_c[$i_cnt] = $rec1[1];
            } else {
                $i_cnt--;
            }
            $i_cnt++;
        }
        $up_str = "";
        $side = "";
        $disp_code = "";
        $nature = "";
        $disp_code_all = "";

        for ($i = 0; $i < count($head_r); $i++) {
            $results_cr = is_data_from_table("master.case_remarks_head", "sno=" . $head_r[$i] . "", "sno,head,side,cis_disp_code,pending_text", "");

            if (!empty($results_cr)) {
                if ($i > 0)
                    $up_str .= ", ";


                if (trim($results_cr["pending_text"]) != "")
                    $up_str .= $results_cr["pending_text"];
                else
                    $up_str .= $results_cr["head"];

                if ($head_c[$i] != "")
                    $up_str .= " (" . $head_c[$i] . ")";
                $side = $results_cr["side"];
                $disp_code = $results_cr["cis_disp_code"];
                $disp_code_all .= $results_cr["cis_disp_code"] . ",";
            }
        }
        $side = $status;
        $tdt = explode("-", $dt);
        $up_str .= "-Ord dt:" . $tdt[2] . "/" . $tdt[1] . "/" . $tdt[0];
        $dday = $dmonth = $dyear = 0;
        if ($rjdt != "" and $rjdt != "0000-00-00") {
            $rjdt1 = explode("-", $rjdt);
            $dmonth = $rjdt1[1];
            $dyear = $rjdt1[0];
            $dday = $rjdt1[2];
            $t_month = $this->chk_disp_date($rjdt);
        } else {
            $hdt1 = explode("-", $hdt);
            $dmonth = $hdt1[1];
            $dyear = $hdt1[0];
            $dday = $hdt1[2];
            $t_month = $this->chk_disp_date($hdt);
        }
        if (intval($t_month) == 1) {
            if (intval(date('d')) >= 15) {
                $dmonth = date('m');
                $dyear = date('Y');
            }
        }
        if (intval($t_month) >= 2) {
            $dmonth = date('m');
            $dyear = date('Y');
        }

        for ($ivar = 0; $ivar < $cncntr; $ivar++) {
           
            if ($ivar > 0)
                $fno = $cncases[$ivar - 1];
            if ($tdt_str == "")
                $str_up_main = $this->db->query("UPDATE main SET last_dt=NOW(), lastorder='" . $up_str . "',c_status='" . $side . "', last_usercode=" . $ucode . " where diary_no='" . $fno . "'");
                
            else
                $str_up_main = $this->db->query("UPDATE main SET last_dt=NOW(), lastorder='" . $up_str . "',c_status='" . $side . "', last_usercode=" . $ucode . $tdt_str . " where diary_no='" . $fno . "'");

            if ($results_cr["side"] == "D") {
                $results_disp = is_data_from_table("dispose","diary_no='" . $fno . "'","*","");
                $disp_str = $up_str;

                if (!empty($results_disp)) {
                    $rjdt_value = ($rjdt == "0000-00-00" || $rjdt == '') ? "NULL" : "'" . $rjdt . "'";

                    $str_up_disp = $this->db->query("UPDATE dispose SET month=" . $dmonth . ",year=" . $dyear . ",dispjud=" . $j1 . ", ord_dt='" . $dt . "', disp_dt='" . $hdt . "',disp_type=" . $disp_code . ",disp_type_all='" . $disp_code_all . "', bench='" . $bench . "',jud_id='" . $jcodes . "',rj_dt=" . $rjdt_value . ",ent_dt=NOW(),camnt=0,usercode=" . $ucode . ",crtstat='" . $temp_mh . "',jorder='' where diary_no='" . $fno . "'");

                    $str_up_disp1 = $this->db->query("INSERT INTO dispose_delete(diary_no, month, dispjud, year, ord_dt, disp_dt, disp_type, bench, jud_id, camnt, crtstat, usercode, ent_dt, jorder, rj_dt, disp_type_all,dispose_updated_by) (SELECT diary_no, month, dispjud, year, ord_dt, disp_dt, disp_type, bench,jud_id, camnt, crtstat, usercode, ent_dt, jorder, rj_dt,disp_type_all,'$ucode' FROM dispose where diary_no='" . $fno . "')");
                   
                    $str_ia_d = $this->db->query("UPDATE docdetails SET iastat='D', lst_mdf=NOW(),dispose_date='$dt',last_modified_by=$ucode WHERE diary_no='" . $fno . "' AND iastat='P' AND doccode=8 AND display='Y'");
                 
                } else {
                    $rjdt_value = ($rjdt == "0000-00-00" || $rjdt == '') ? "NULL" : "'" . $rjdt . "'";
                    
                    $str_up_disp = $this->db->query("INSERT INTO dispose(diary_no, month,year,dispjud,ord_dt,disp_dt,disp_type,bench,jud_id,ent_dt,camnt,usercode,crtstat,jorder,rj_dt,disp_type_all) VALUES('" . $fno . "'," . $dmonth . "," . $dyear . "," . $j1 . ",'" . $dt . "','" . $hdt . "'," . $disp_code . ",'" . $bench . "','" . $jcodes . "',NOW(),0," . $ucode . ",'" . $temp_mh . "','', $rjdt_value ,'" . $disp_code_all . "')");
                                    
                    $str_ia_d = $this->db->query("UPDATE docdetails SET iastat='D', lst_mdf=NOW(),dispose_date='$dt',last_modified_by=$ucode WHERE diary_no='" . $fno . "' AND iastat='P' AND doccode=8 AND display='Y'");
                 
                 
                }
            }
            $rgo = $this->db->query("Update rgo_default set remove_def='Y' WHERE fil_no2=" . $fno); 
          
        }
    }

    public function get_coram(){
        $diary_no = $_POST['diary_no'];
        $cl_date = $_POST['cl_dt'];
        $cl_date = date('Y-m-d', strtotime($cl_date));
    
        if ($diary_no != '' AND $cl_date != '') {
         
              $sql = $this->db->query("SELECT j.jcode, j.jname
                            FROM master.judge j
                            JOIN (
                                SELECT string_to_array(judges, ',') AS judge_codes
                                FROM last_heardt
                                WHERE diary_no = '$diary_no'
                                AND clno != 0
                                AND brd_slno != 0
                                AND roster_id != 0
                                AND judges != ''
                                AND (bench_flag = '' OR bench_flag IS NULL)
                                AND next_dt = '$cl_date'
                                GROUP BY judges
                            ) sub
                            ON j.jcode::text = ANY(sub.judge_codes)");
              
              $result = $sql->getResultArray();
            
            // $result=mysql_query($sql) or die("Error: " . __LINE__ . mysql_error());
            $output="";
            if(!empty($result)) {
                $count=1;
                foreach($result as $row ){
                    //$val=$row['jcode']."||".str_replace("\\","",$row2["jname"]);
                    $output.='<tr><td><input type="checkbox" id="hd_chk_jd'.$count.'" value="'.$row["jcode"].'||'.str_replace("\\","",$row["jname"]).'" checked><label style="color: red; font-weight: bold;">'.$row["jname"].'</label></td></tr>';
                }
    
            }
            else {
    
               $sql = $this->db->query("SELECT j.jcode, j.jname
                        FROM master.judge j
                        JOIN (
                            SELECT string_to_array(judges, ',') AS judge_codes
                            FROM heardt
                            WHERE diary_no = '$diary_no'
                            AND clno != 0
                            AND brd_slno != 0
                            AND roster_id != 0
                            AND judges != ''
                            AND next_dt = '$cl_date'
                            GROUP BY judges
                        ) sub
                        ON j.jcode::text = ANY(sub.judge_codes)");
                      
                $result =  $sql->getResultArray();;
                $output = "";
                if (!empty($result)) {
                    $count = 1;
                    foreach ($result as $row ) {
                        //$val=$row['jcode']."||".str_replace("\\","",$row2["jname"]);
                        $output .= '<tr><td><input type="checkbox" id="hd_chk_jd' . $count . '" value="' . $row["jcode"] . '||' . str_replace("\\", "", $row["jname"]) . '" checked><label style="color: red; font-weight: bold;">' . $row["jname"] . '</label></td></tr>';
                    }
    
                }
            }
            echo $output;
        }
    }
}
