<?php

namespace App\Models\Judicial;


use CodeIgniter\Model;

class AmendCausetitleModel extends Model
{

    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }


    public function get_casedesc($diary_no)
    {

        $ucode = $_SESSION['login']['usercode'];
        $dataArr = [];

        // $get_da_sec="Select section_id,dacode,diary_no_rec_date,fil_dt,pet_name,res_name,pno,rno,fil_no,casetype_id,dacode from main where diary_no='$diary_no'";
        // $get_da_sec=  mysql_query($get_da_sec) or die("Error: ".__LINE__.mysql_error());
        $builder2 = $this->db->table("main");
        $builder2->select("section_id,dacode,diary_no_rec_date,fil_dt,pet_name,res_name,pno,rno,fil_no,casetype_id,dacode");
        $builder2->where('diary_no', $diary_no);
        $query1 = $builder2->get();
        $get_da_sec = $query1->getResultArray();


        $get_da_sec_a = [];
        if (empty($get_da_sec)) {
            $builder2 = $this->db->table("main_a");
            $builder2->select("section_id,dacode,diary_no_rec_date,fil_dt,pet_name,res_name,pno,rno,fil_no,casetype_id,dacode");
            $builder2->where('diary_no', $diary_no);
            $query1 = $builder2->get();
            $get_da_sec_a = $query1->getResultArray();
        }

        // echo "<pre>"; print_r($get_da_sec_a); die;

        if ((count($get_da_sec) == 0) && (count($get_da_sec_a) == 0)) {
            $dataArr['casedet'] = "Case Not Found";
            return $dataArr;
            // exit();
        } else {
            $r_get_da_sec = !empty($get_da_sec) ? $get_da_sec[0] : $get_da_sec_a[0];
            if ($r_get_da_sec['dacode'] != $ucode && $ucode != 1) {
                $dataArr['casedet'] = "Only Concerned Dealing Assistant can generate Amended Cause Title!!!";
                return $dataArr;
                // exit();
            }


            if ($r_get_da_sec['dacode'] != 0) {
                // $get_sec="Select section_name from users a join usersection b on a.section=b.id where usercode='$r_get_da_sec[dacode]' and b.display='Y'";
                $builder3 = $this->db->table("master.users a");
                $builder3->select("section_name");
                $builder3->join("master.usersection b", 'a.section=b.id');
                $builder3->where('usercode', $r_get_da_sec['dacode']);
                $builder3->where('b.display', 'Y');
                $query3 = $builder3->get();
                $r_get_sec = $query3->getResultArray();
                if (!empty($r_get_sec)) {
                    $r_get_sec = $r_get_sec[0]['section_name'];
                    $dataArr['section'] = $r_get_sec;
                }
            } else {
                // $get_sec="Select section_name from usersection where id='$r_get_da_sec[section_id]' and display='Y'";
                $builder4 = $this->db->table("master.usersection");
                $builder4->select("section_name");
                $builder4->where('id', $r_get_da_sec['section_id']);
                $builder4->where('display', 'Y');
                $query4 = $builder4->get();
                $r_get_sec = $query4->getResultArray();
                if (!empty($r_get_sec)) {
                    $r_get_sec = $r_get_sec[0]['section_name'];
                    $dataArr['section'] = $r_get_sec;
                }
            }



            $fil_no_yr = '';
            if ($r_get_da_sec['fil_no'] != '' && $r_get_da_sec['fil_no'] != NULL) {
                $c_code = substr($r_get_da_sec['fil_no'], 0, 2);
                $fil_no_yr = ' ' . substr($r_get_da_sec['fil_no'], 3) . '/' . substr($r_get_da_sec['fil_dt'], 0, 4);
                $dataArr['fil_no_yr'] = $fil_no_yr;
                $dataArr['c_code'] = $c_code;
            } else {
                $c_code = $r_get_da_sec['casetype_id'];
                $dataArr['c_code'] = $c_code;
            }
            // $c_type="Select casename,nature from casetype where casecode='$c_code' and display='Y'";
            // echo  $c_code;
            $builder5 = $this->db->table("master.casetype");
            $builder5->select("casename,nature");
            $builder5->where('casecode', $c_code);
            $builder5->where('display', 'Y');
            $query5 = $builder5->get();
            $c_type = $query5->getResultArray();


            // $c_type=  mysql_query($c_type) or die("Error: ".__LINE__.mysql_error());
            $r_c_type = $c_type[0]['casename'] ?? 'Unknown';
            $r_nature = $c_type[0]['nature'] ?? 'C';
            $c_r = '';
            $ia_crmp = '';
            if ($r_nature == 'C') {
                $dataArr['c_r'] = "Civil";
                $dataArr['ia_crmp'] = "I.A.No.";
            }
            if ($r_nature == 'R') {
                $dataArr['c_r'] = "Criminal";
                $dataArr['ia_crmp'] = "Cr.M.P.No.";
            }



            $d_yr = substr($diary_no, -4);
            $d_no = str_replace($d_yr, "", $diary_no);


            // $c_type="Select casename,short_description,if(active_fil_no is null or active_fil_no='',fil_no,active_fil_no) active_fil_no,active_reg_year from main m join casetype c on if(m.active_casetype_id is null or m.active_casetype_id='', m.casetype_id=c.casecode, m.active_casetype_id=c.casecode) where diary_no='$diary_no'";
            // $c_type=  mysql_query($c_type) or die("Error: ".__LINE__.mysql_error());
            // $r_c_type=  mysql_fetch_array($c_type);


            // $builder6->select("casename, short_description, cast(COALESCE(NULLIF(active_fil_no, 0), fil_no) as as active_fil_no, active_reg_year");
            // $builder6->join('master.casetype c', "cast(COALESCE(NULLIF(m.active_casetype_id, 0), m.casetype_id) as integer) = c.casecode");
            // $builder6->where('diary_no', $diary_no);

            $builder6 = $this->db->table("main m");
            $builder6->select("casename, short_description, COALESCE(NULLIF(active_fil_no, ''), fil_no) AS active_fil_no, active_reg_year");
            $builder6->join('master.casetype c', 'CAST(COALESCE(NULLIF(m.active_casetype_id, 0), m.casetype_id) AS INTEGER) = c.casecode');
            $builder6->where('diary_no', $diary_no);
            $query6 = $builder6->get();
            $r_c_type = $query6->getResultArray();
            // echo "<pre>"; print_r($r_c_type); die;
            if (!empty($r_c_type)) {
                $r_c_type = $r_c_type[0];
                $active_fil_no = '';
                if ($r_c_type['active_fil_no'] == null || $r_c_type['active_fil_no'] == '' || $r_c_type['active_fil_no'] == 0) {
                    $active_fil_no .= ' D.no.' . $d_no . '/' . $d_yr;
                } else {
                    $a = explode('-', substr($r_c_type['active_fil_no'], 3));
                    $reg_no = '';
                    if (count($a) > 1) {
                        if ($a[0] == $a[1]) {
                            $reg_no = $a[0];
                        } else {
                            $reg_no = substr($r_c_type['active_fil_no'], 3);
                        }
                    } else {
                        $reg_no = substr($r_c_type['active_fil_no'], 3);
                    }
                    $active_fil_no = ' NO. ' . $reg_no . '/' . $r_c_type['active_reg_year'];
                }
                $dataArr['casename'] = $r_c_type['casename'] . ' ' . $active_fil_no;
            } else {
                $builder6 = $this->db->table("main_a m");
                $builder6->select("casename, short_description, COALESCE(NULLIF(active_fil_no, ''), fil_no) AS active_fil_no, active_reg_year");
                $builder6->join('master.casetype c', 'CAST(COALESCE(NULLIF(m.active_casetype_id, 0), m.casetype_id) AS INTEGER) = c.casecode');
                $builder6->where('diary_no', $diary_no);
                $query6 = $builder6->get();
                $r_c_type = $query6->getResultArray();
                // echo "<pre>"; print_r($r_c_type); die;
                if (!empty($r_c_type)) {
                    $r_c_type = $r_c_type[0];
                    $active_fil_no = '';
                    if ($r_c_type['active_fil_no'] == null || $r_c_type['active_fil_no'] == '' || $r_c_type['active_fil_no'] == 0) {
                        $active_fil_no .= ' D.no.' . $d_no . '/' . $d_yr;
                    } else {
                        // echo $r_c_type['active_fil_no']; die;
                        $a = explode('-', substr($r_c_type['active_fil_no'], 3));
                        // echo "<pre>"; print_r($a); die;
                        $reg_no = '';
                        if (count($a) > 1) {
                            if ($a[0] == $a[1]) {
                                $reg_no = $a[0];
                            } else {
                                $reg_no = substr($r_c_type['active_fil_no'], 3);
                            }
                        } else {
                            $reg_no = substr($r_c_type['active_fil_no'], 3);
                        }

                        $active_fil_no .= ' NO. ' . $reg_no . '/' . $r_c_type['active_reg_year'];
                    }
                    $dataArr['casename'] = $r_c_type['casename'] . ' ' . $active_fil_no;
                }
            }




            $diary_rec_date = $this->get_diary_rec_date($diary_no);
            $dataArr['filing_date'] = $diary_rec_date != '' ? date('dS F, Y', strtotime($diary_rec_date)) : '';
            $lower_court = $this->lower_court_conct($diary_no);
            // echo "<pre>"; print_r($lower_court); die;
            $lower_court_count = '';
            if (count($lower_court) > 0) {
                $lower_court_count = "Arising out of Judgment and final order dated ";
            }

            $judgTitle11 = '';
            $judgTitle1 = '';
            $judgTitle3 = '';
            for ($index1 = 0; $index1 < count($lower_court); $index1++) {
                // echo "<pre>"; print_r($lower_court[$index1]); die;
                $judgement_dt = $new_date = date('dS F, Y', strtotime($lower_court[$index1][0]));

                $agency_name = $lower_court[$index1][2];
                $skey = $lower_court[$index1][3];
                $lct_caseno = $lower_court[$index1][4];
                $lct_caseyear = $lower_court[$index1][5];
                if ($index1 > 0) {
                    $judgTitle1 = $judgTitle1 . " and dated ";
                }
                $judgTitle1 = $judgTitle1 . "<b>" . $judgement_dt . "</b>" . " of the <b>" . $agency_name . $lower_court[$index1][1] . "</b>" . " in ";

                if (!empty($skey)) {
                    $ex_skey =  explode(',', $skey);
                } else
                    $ex_skey =  '';
                $ex_lct_caseno = explode(',', $lct_caseno);
                $ex_lct_caseyear = explode(',', $lct_caseyear);
                for ($index2 = 0; $index2 < count($ex_lct_caseno); $index2++) {
                    if ($index2 > 0) {
                        $dataArr['judgTitle2'][] = ',';
                    }
                    if (!empty($skey)) {
                        $judgTitle3 = "<b>" . $ex_skey[$index2] . "</b>" . " No. <b>" . $ex_lct_caseno[$index2] . "</b> of <b>" . $ex_lct_caseyear[$index2] . "</b>";
                        $judgTitle1 = $judgTitle1 . $judgTitle3;
                    } else {
                        $judgTitle1 = '';
                    }
                }
            }

            // echo "<pre>";
            // print_r($lower_court_count);
            // print_r($judgTitle1);
            // die;
            $dataArr['headTtitle'] = $lower_court_count . $judgTitle1;


            if (count($lower_court) > 0) {
                $dataArr['judgTitle4'] = ")";
            }


            // $sql_p = "select sr_no_show,partyname,prfhname,addr1,addr2,state,city,dstname,pet_res,remark_del,remark_lrs,pflag,partysuff,deptname,ind_dep 
            //     from party left join deptt b on state_in_name=b.deptcode where diary_no='" . $diary_no . "' AND pflag not in ('T', 'Z') and pet_res='P' "
            //       . "ORDER BY pet_res,
            //     CAST(SUBSTRING_INDEX(sr_no_show,'.',1) AS UNSIGNED) ,
            //     CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(CONCAT(sr_no_show,'.0'),'.',2),'.',-1) AS UNSIGNED) ,
            //     CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(CONCAT(sr_no_show,'.0.0'),'.',3),'.',-1) AS UNSIGNED),
            //     CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(CONCAT(sr_no_show,'.0.0.0'),'.',4),'.',-1) AS UNSIGNED)";
            //     $result_p = mysql_query($sql_p) or die(mysql_error()." SQL:".$sql_p);

            $builder8 = $this->db->table("party");
            $builder8->select('sr_no_show, partyname, prfhname, addr1, addr2, state, city, dstname, pet_res, remark_del, remark_lrs, pflag, partysuff, deptname, ind_dep');
            $builder8->join('master.deptt b', 'state_in_name = b.deptcode', 'LEFT');
            $builder8->where("diary_no = '" . $diary_no . "' AND pflag NOT IN ('T', 'Z') AND pet_res = 'P'");
            $builder8->orderBy('pet_res');
            //  $builder8->orderBy("CAST(split_part(sr_no_show, '.', 1) AS INTEGER)");
            // $builder8->orderBy("CAST(split_part(split_part(CONCAT(sr_no_show, '.0'), '.', 2), '.', -1) AS INTEGER)" );
            //$builder8->orderBy("CAST(split_part(split_part(CONCAT(sr_no_show, '.0.0'), '.', 3), '.', -1) AS INTEGER)");
            //$builder8->orderBy("CAST(split_part(split_part(CONCAT(sr_no_show, '.0.0.0'), '.', 4), '.', -1) AS INTEGER)");
            $query8 = $builder8->get();
            if ($query8 !== false) {
                $petitioner_data = $query8->getResultArray();
                // Process the result
            } else {
                // Handle the error

                echo $this->db->error(); // Display the error
            }



            $pet_arr = [];
            if (empty($petitioner_data)) {
                $builder8 = $this->db->table("party_a");
                $builder8->select('sr_no_show, partyname, prfhname, addr1, addr2, state, city, dstname, pet_res, remark_del, remark_lrs, pflag, partysuff, deptname, ind_dep');
                $builder8->join('master.deptt b', 'state_in_name = b.deptcode', 'LEFT');
                $builder8->where("diary_no = '" . $diary_no . "' AND pflag NOT IN ('T', 'Z') AND pet_res = 'P'");
                $builder8->orderBy('pet_res');
                $builder8->orderBy("CAST(split_part(sr_no_show, '.', 1) AS INTEGER)");
                $builder8->orderBy("CAST(split_part(split_part(CONCAT(sr_no_show, '.0'), '.', 2), '.', -1) AS INTEGER)");
                $builder8->orderBy("CAST(split_part(split_part(CONCAT(sr_no_show, '.0.0'), '.', 3), '.', -1) AS INTEGER)");
                $builder8->orderBy("CAST(split_part(split_part(CONCAT(sr_no_show, '.0.0.0'), '.', 4), '.', -1) AS INTEGER)");
                $query8 = $builder8->get();
                if ($query8 !== false) {
                    $petitioner_data = $query8->getResultArray();
                } else {
                    $petitioner_data = '';
                }
                if (!empty($petitioner_data)) {
                    foreach ($petitioner_data as $val) {
                        $city_name = $this->get_state($val['city']);
                        $state_name = $this->get_state($val['state']);
                        $pet_arr[] = [
                            'sr_no_show' => $val['sr_no_show'],
                            'partyname' => $val['partyname'],
                            'prfhname' => $val['prfhname'],
                            'addr1' => $val['addr1'],
                            'addr2' => $val['addr2'],
                            'state' => $state_name,
                            'city' => $city_name,
                            'dstname' => $val['dstname'],
                            'pet_res' => $val['pet_res'],
                            'remark_del' => $val['remark_del'],
                            'remark_lrs' => $val['remark_lrs'],
                            'pflag' => $val['pflag'],
                            'partysuff' => $val['partysuff'],
                            'deptname' => $val['deptname'],
                            'ind_dep' => $val['ind_dep']
                        ];
                    }
                }
            } else {
                foreach ($petitioner_data as $val) {
                    $city_name = $this->get_state($val['city']);
                    $state_name = $this->get_state($val['state']);
                    $pet_arr[] = [
                        'sr_no_show' => $val['sr_no_show'],
                        'partyname' => $val['partyname'],
                        'prfhname' => $val['prfhname'],
                        'addr1' => $val['addr1'],
                        'addr2' => $val['addr2'],
                        'state' => $state_name,
                        'city' => $city_name,
                        'dstname' => $val['dstname'],
                        'pet_res' => $val['pet_res'],
                        'remark_del' => $val['remark_del'],
                        'remark_lrs' => $val['remark_lrs'],
                        'pflag' => $val['pflag'],
                        'partysuff' => $val['partysuff'],
                        'deptname' => $val['deptname'],
                        'ind_dep' => $val['ind_dep']
                    ];
                }
            }

            $dataArr['petitioner_data'] = $pet_arr;


            // $sql_p = "select sr_no_show,partyname,prfhname,addr1,addr2,state,city,dstname,pet_res,remark_del,remark_lrs,pflag,partysuff,deptname,ind_dep 
            //       from party left join deptt b on state_in_name=b.deptcode where diary_no='" . $diary_no . "' AND pflag not in ('T', 'Z') and pet_res='R' "
            //             . "ORDER BY pet_res,
            //     CAST(SUBSTRING_INDEX(sr_no_show,'.',1) AS UNSIGNED) ,
            //     CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(CONCAT(sr_no_show,'.0'),'.',2),'.',-1) AS UNSIGNED) ,
            //     CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(CONCAT(sr_no_show,'.0.0'),'.',3),'.',-1) AS UNSIGNED),
            //     CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(CONCAT(sr_no_show,'.0.0.0'),'.',4),'.',-1) AS UNSIGNED)";
            //     $result_p = mysql_query($sql_p) or die(mysql_error()." SQL:".$sql_p);

            $builder9 = $this->db->table("party");
            $builder9->select('sr_no_show, partyname, prfhname, addr1, addr2, state, city, dstname, pet_res, remark_del, remark_lrs, pflag, partysuff, deptname, ind_dep');
            $builder9->join('master.deptt b', 'state_in_name = b.deptcode', 'LEFT');
            $builder9->where("diary_no = '" . $diary_no . "' AND pflag NOT IN ('T', 'Z') AND pet_res = 'R'");
            $builder9->orderBy('pet_res');
            // $builder9->orderBy("CAST(split_part(sr_no_show, '.', 1) AS INTEGER)");
            // $builder9->orderBy("CAST(split_part(split_part(CONCAT(sr_no_show, '.0'), '.', 2), '.', -1) AS INTEGER)");
            // $builder9->orderBy("CAST(split_part(split_part(CONCAT(sr_no_show, '.0.0'), '.', 3), '.', -1) AS INTEGER)");
            //$builder9->orderBy("CAST(split_part(split_part(CONCAT(sr_no_show, '.0.0.0'), '.', 4), '.', -1) AS INTEGER)");
            // $queryString = $builder->getCompiledSelect();
            // echo $queryString;
            // exit();
            $query9 = $builder9->get();
            if ($query9 !== false) {
                $respondant_data = $query9->getResultArray();
            } else {
                $respondant_data = '';
            }

            $res_arr = [];
            if (empty($respondant_data)) {
                $builder9 = $this->db->table("party_a");
                $builder9->select('sr_no_show, partyname, prfhname, addr1, addr2, state, city, dstname, pet_res, remark_del, remark_lrs, pflag, partysuff, deptname, ind_dep');
                $builder9->join('master.deptt b', 'state_in_name = b.deptcode', 'LEFT');
                $builder9->where("diary_no = '" . $diary_no . "' AND pflag NOT IN ('T', 'Z') AND pet_res = 'R'");
                $builder9->orderBy('pet_res');
                // $builder9->orderBy("CAST(split_part(sr_no_show, '.', 1) AS INTEGER)");
                // $builder9->orderBy("CAST(split_part(split_part(CONCAT(sr_no_show, '.0'), '.', 2), '.', -1) AS INTEGER)");
                // $builder9->orderBy("CAST(split_part(split_part(CONCAT(sr_no_show, '.0.0'), '.', 3), '.', -1) AS INTEGER)");
                $builder9->orderBy("CAST(split_part(split_part(CONCAT(sr_no_show, '.0.0.0'), '.', 4), '.', -1) AS INTEGER)");
                // $queryString = $builder->getCompiledSelect();
                // echo $queryString;
                // exit();
                $query9 = $builder9->get();
                if ($query9 !== false) {
                    $respondant_data = $query9->getResultArray();
                } else {
                    $respondant_data = '';
                }
                if (!empty($respondant_data)) {
                    foreach ($respondant_data as $val) {
                        $city_name = $this->get_state($val['city']);
                        $state_name = $this->get_state($val['state']);
                        $res_arr[] = [
                            'sr_no_show' => $val['sr_no_show'],
                            'partyname' => $val['partyname'],
                            'prfhname' => $val['prfhname'],
                            'addr1' => $val['addr1'],
                            'addr2' => $val['addr2'],
                            'state' => $state_name,
                            'city' => $city_name,
                            'dstname' => $val['dstname'],
                            'pet_res' => $val['pet_res'],
                            'remark_del' => $val['remark_del'],
                            'remark_lrs' => $val['remark_lrs'],
                            'pflag' => $val['pflag'],
                            'partysuff' => $val['partysuff'],
                            'deptname' => $val['deptname'],
                            'ind_dep' => $val['ind_dep']
                        ];
                    }
                }
            } else {
                foreach ($respondant_data as $val) {
                    $city_name = $this->get_state($val['city']);
                    $state_name = $this->get_state($val['state']);
                    $res_arr[] = [
                        'sr_no_show' => $val['sr_no_show'],
                        'partyname' => $val['partyname'],
                        'prfhname' => $val['prfhname'],
                        'addr1' => $val['addr1'],
                        'addr2' => $val['addr2'],
                        'state' => $state_name,
                        'city' => $city_name,
                        'dstname' => $val['dstname'],
                        'pet_res' => $val['pet_res'],
                        'remark_del' => $val['remark_del'],
                        'remark_lrs' => $val['remark_lrs'],
                        'pflag' => $val['pflag'],
                        'partysuff' => $val['partysuff'],
                        'deptname' => $val['deptname'],
                        'ind_dep' => $val['ind_dep']
                    ];
                }
            }

            $dataArr['respondant_data'] = $res_arr;

            return $dataArr;
        }
    }



    function get_diary_rec_date($dairy_no)
    {
        // $sql = "Select diary_no_rec_date from main where diary_no='$dairy_no'";
        // $sql = mysql_query($sql)or die("Error: " . _LINE__ . mysql_error());
        // $res_sql = mysql_fetch_array($sql);
        $builder6 = $this->db->table("main");
        $builder6->select("diary_no_rec_date");
        $builder6->where('diary_no', $dairy_no);
        $query6 = $builder6->get();
        $res_sql = $query6->getResultArray();

        if (!empty($res_sql)) {
            $res_sql = $res_sql[0];
            return $res_sql['diary_no_rec_date'];
        }
    }

    function lower_court_conct($dairy_no)
    {
        // mysql_query("SET SESSION group_concat_max_len = 1000000");

        // $chk_casetype = "Select active_casetype_id from main where diary_no='$dairy_no'";
        // $chk_casetype = mysql_query($chk_casetype)or die("Error: " . __LINE__ . mysql_error());
        // $res_chk_casetype = mysql_result($chk_casetype, 0);
        $builder6 = $this->db->table("main");
        $builder6->select("active_casetype_id");
        $builder6->where('diary_no', $dairy_no);
        $query6 = $builder6->get();
        $res_chk_casetype = $query6->getResultArray();

        if (empty($res_chk_casetype)) {
            $builder6 = $this->db->table("main_a");
            $builder6->select("active_casetype_id");
            $builder6->where('diary_no', $dairy_no);
            $query6 = $builder6->get();
            $res_chk_casetype_a = $query6->getResultArray();
            $res_chk_casetype = $res_chk_casetype_a[0]['active_casetype_id'];
        } else {
            $res_chk_casetype = $res_chk_casetype[0]['active_casetype_id'];
        }



        $is_order_challenged = '';
        if ($res_chk_casetype != 25 && $res_chk_casetype != 26 && $res_chk_casetype != 7 && $res_chk_casetype != 8) {
            $is_order_challenged = " AND is_order_challenged = 'Y' ";
        }

        // $builder15 = $this->db->table(function ($builder1) {
        //     $builder1 = $this->db->table('lowerct a');
        //     $builder1->select([
        //         'lct_dec_dt',
        //         'l_dist',
        //         'ct_code',
        //         'l_state',
        //         'Name',
        //         "(CASE WHEN ct_code = 3 THEN (SELECT Name FROM master.state s WHERE s.id_no = a.l_dist AND s.display = 'Y')
        //                 WHEN ct_code = 4 THEN (SELECT short_description FROM master.casetype ct WHERE ct.display = 'Y' AND ct.casecode = a.lct_casetype)
        //                 ELSE (SELECT type_sname FROM master.lc_hc_casetype d WHERE d.lccasecode = a.lct_casetype AND d.display = 'Y') END) AS type_sname',
        //         '(CASE WHEN ct_code = 3 THEN (SELECT concat(agency_name, ', ', address) FROM master.ref_agency_code c WHERE c.cmis_state_id = a.l_state AND c.id = a.l_dist AND c.is_deleted = 'f')
        //                 ELSE (SELECT policestndesc FROM master.police p WHERE p.policestncd = a.polstncode AND p.display = 'Y' AND p.cmis_state_id = a.l_state AND p.cmis_district_id = a.l_dist) END) AS agency_name",
        //         'lct_casetype',
        //         'lct_caseno',
        //         'lct_caseyear',
        //         'a.lower_court_id',
        //         'is_order_challenged',
        //         'full_interim_flag',
        //         'judgement_covered_in',
        //     ]);
        //     $builder1->join('master.state b', 'a.l_state = b.id_no AND b.display = \'Y\'', 'left');
        //     $builder1->join('main e', 'e.diary_no = a.diary_no');
        //     $builder1->where('a.diary_no', '16892023');
        //     $builder1->where('lw_display', 'Y');
        //     $builder1->orderBy('a.lower_court_id');
        // });
        // $builder15->select('lct_dec_dt', 'l_dist', 'ct_code', 'l_state', 'Name', "string_agg(lct_casetype::character varying, ', ' ORDER BY lower_court_id) AS lct_casetype", "string_agg(lct_caseno, ', ' ORDER BY lower_court_id) AS lct_caseno", "string_agg(lct_caseyear::character varying, ', ' ORDER BY lower_court_id) AS lct_caseyear", "string_agg(type_sname, ', ' ORDER BY lower_court_id) AS type_sname");
        // $builder15->groupBy('lct_dec_dt', 'l_dist', 'ct_code', 'l_state', 'Name', 'agency_name');
        // $query15 = $builder15->get();

        $query = "SELECT
                    lct_dec_dt,
                    l_dist,
                    ct_code,
                    l_state,
                    Name,
                    agency_name,
                    string_agg(lct_casetype::character varying, ', ' ORDER BY lower_court_id) AS lct_casetype,
                    string_agg(lct_caseno::character varying, ', ' ORDER BY lower_court_id) AS lct_caseno,
                    string_agg(lct_caseyear::character varying, ', ' ORDER BY lower_court_id) AS lct_caseyear,
                    string_agg(type_sname::character varying, ', ' ORDER BY lower_court_id) AS type_sname
                    
                FROM (
                    SELECT
                        lct_dec_dt,
                        l_dist,
                        ct_code,
                        l_state,	    
                        Name,
                        CASE
                            WHEN ct_code = '3' THEN
                                (SELECT Name FROM master.state s WHERE s.id_no = a.l_dist AND s.display = 'Y')
                            ELSE
                                (SELECT concat(agency_name, ', ', address) FROM master.ref_agency_code c WHERE c.cmis_state_id = a.l_state AND c.id = a.l_dist AND c.is_deleted = 'f')
                    
                        END AS agency_name,
                        CASE
                            WHEN ct_code = '4' THEN
                            (SELECT short_description FROM master.casetype ct WHERE ct.display = 'Y' AND ct.casecode = a.lct_casetype)
                            ELSE
                                (SELECT type_sname FROM master.lc_hc_casetype d WHERE d.lccasecode = a.lct_casetype AND d.display = 'Y')
                        END AS type_sname,
                        polstncode,
                        (
                            SELECT policestndesc FROM master.police p 
                            WHERE p.policestncd = a.polstncode AND p.display = 'Y' AND p.cmis_state_id = a.l_state AND p.cmis_district_id = a.l_dist
                        ) policestndesc,
                        lct_casetype,
                        lct_caseno,
                        lct_caseyear,
                        lower_court_id,
                        is_order_challenged,
                        full_interim_flag,
                        judgement_covered_in
                    FROM lowerct a
                    LEFT JOIN master.state b ON a.l_state = b.id_no AND b.display = 'Y'
                    JOIN main e ON e.diary_no = a.diary_no
                    WHERE a.diary_no = $dairy_no AND lw_display = 'Y' $is_order_challenged  
                    ORDER BY a.lower_court_id
                ) aa
                GROUP BY lct_dec_dt, l_dist, ct_code, l_state, Name, agency_name";

        $result = $this->db->query($query);
        $result = $result->getResultArray();

        if (empty($result)) {
            $query = "SELECT
                        lct_dec_dt,
                        l_dist,
                        ct_code,
                        l_state,
                        Name,
                        agency_name,
                        string_agg(lct_casetype::character varying, ', ' ORDER BY lower_court_id) AS lct_casetype,
                        string_agg(lct_caseno::character varying, ', ' ORDER BY lower_court_id) AS lct_caseno,
                        string_agg(lct_caseyear::character varying, ', ' ORDER BY lower_court_id) AS lct_caseyear,
                        string_agg(type_sname::character varying, ', ' ORDER BY lower_court_id) AS type_sname
                        
                    FROM (
                        SELECT
                            lct_dec_dt,
                            l_dist,
                            ct_code,
                            l_state,	    
                            Name,
                            CASE
                                WHEN ct_code = '3' THEN
                                    (SELECT Name FROM master.state s WHERE s.id_no = a.l_dist AND s.display = 'Y')
                                ELSE
                                    (SELECT concat(agency_name, ', ', address) FROM master.ref_agency_code c WHERE c.cmis_state_id = a.l_state AND c.id = a.l_dist AND c.is_deleted = 'f')
                        
                            END AS agency_name,
                            CASE
                                WHEN ct_code = '4' THEN
                                (SELECT short_description FROM master.casetype ct WHERE ct.display = 'Y' AND ct.casecode = a.lct_casetype)
                                ELSE
                                    (SELECT type_sname FROM master.lc_hc_casetype d WHERE d.lccasecode = a.lct_casetype AND d.display = 'Y')
                            END AS type_sname,
                            polstncode,
                            (
                                SELECT policestndesc FROM master.police p 
                                WHERE p.policestncd = a.polstncode AND p.display = 'Y' AND p.cmis_state_id = a.l_state AND p.cmis_district_id = a.l_dist
                            ) policestndesc,
                            lct_casetype,
                            lct_caseno,
                            lct_caseyear,
                            lower_court_id,
                            is_order_challenged,
                            full_interim_flag,
                            judgement_covered_in
                        FROM lowerct_a a
                        LEFT JOIN master.state b ON a.l_state = b.id_no AND b.display = 'Y'
                        JOIN main_a e ON e.diary_no = a.diary_no
                        WHERE a.diary_no = $dairy_no AND lw_display = 'Y' $is_order_challenged  
                        ORDER BY a.lower_court_id
                    ) aa
                    GROUP BY lct_dec_dt, l_dist, ct_code, l_state, Name, agency_name";

            $result = $this->db->query($query);
            $result = $result->getResultArray();
            // echo "<pre>"; print_r($result); die;
            if (count($result) > 0) {
                $outer_array = array();
                // while ($row = mysql_fetch_array($sql)) {
                foreach ($result as $row) {
                    // echo "<pre>"; print_r($row); die;
                    $inner_array = array();
                    $inner_array[0] = $row['lct_dec_dt'];
                    $inner_array[1] = $row['name'];
                    $inner_array[2] = $row['agency_name'];
                    $inner_array[3] = $row['type_sname'];
                    $inner_array[4] = $row['lct_caseno'];
                    $inner_array[5] = $row['lct_caseyear'];
                    $inner_array[6] = $row['lct_casetype'];
                    $outer_array[] = $inner_array;
                }
                return $outer_array;
            } else {
                return [];
            }
        } else {
            if (count($result) > 0) {
                $outer_array = array();
                // while ($row = mysql_fetch_array($sql)) {
                foreach ($result as $row) {
                    // echo "<pre>"; print_r($row); die;
                    $inner_array = array();
                    $inner_array[0] = $row['lct_dec_dt'];
                    $inner_array[1] = $row['name'];
                    $inner_array[2] = $row['agency_name'];
                    $inner_array[3] = $row['type_sname'];
                    $inner_array[4] = $row['lct_caseno'];
                    $inner_array[5] = $row['lct_caseyear'];
                    $inner_array[6] = $row['lct_casetype'];
                    $outer_array[] = $inner_array;
                }
                return $outer_array;
            } else {
                return [];
            }
        }


        echo "<pre>";
        print_r($result);
        die;
    }

    function get_state($state = 0)
    {
        // $s_det = "Select Name from state where id_no='$state' and display='Y'";
        // $s_det = mysql_query($s_det) or die("Error: " . __LINE__ . mysql_error());
        // return $r_state = mysql_result($s_det, 0);

        // Fix the empty error
        $state = (!empty($state)) ? $state : 0;

        $builder6 = $this->db->table("master.state");
        $builder6->select("name");
        $builder6->where('id_no', $state);
        $query6 = $builder6->get();
        $res_sql = $query6->getResultArray();

        if (!empty($res_sql)) {
            $res_sql = $res_sql[0];
            return $res_sql['name'];
        }
    }
}
