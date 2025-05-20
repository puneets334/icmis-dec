<?php
$db = \Config\Database::connect();
        //get IA listing date
/*$q = "Select a.diary_no, a.next_dt, a.listed_ia from last_heardt a LEFT JOIN users b ON a.usercode=b.usercode LEFT JOIN usersection c ON b.section=c.id where diary_no='$diaryno' and next_dt!='0000-00-00' and a.bench_flag='' and (a.main_supp_flag=1 or a.main_supp_flag=2)
UNION
Select a.diary_no, a.next_dt, a.listed_ia from heardt a LEFT JOIN users b ON a.usercode=b.usercode LEFT JOIN usersection c ON b.section=c.id where diary_no='$diaryno' and a.next_dt!='0000-00-00' and (a.main_supp_flag=1 or a.main_supp_flag=2)";
        $r = mysql_query($q) or die(mysql_error()); */

       $r =  $Model_case_status->getNextHearingDetails($diaryno,$flag);
        $ia_list_data = [];
        
        if(!empty($r))
        {
            foreach ($r as $row ) 
            {
                $ia_list_data[] = array(
                    'diary_no' => $row['diary_no'],
                    'list_dt' => date('d-m-Y', strtotime($row['next_dt'])),
                    'ias' =>  (!empty($row['listed_ia'])) ?  explode(',', $row['listed_ia']) : '',
                );
            }
        }

        //get IA defects details
        $q1 = "select distinct diary_no, doc_id from doc_receive where diary_no ='$diaryno';";
        $query = $db->query($q1);
        $r1 = $query->getResultArray();
       // $r1 = is_data_from_table('doc_receive',  " diary_no ='$diaryno' ", 'distinct diary_no', $row = 'Q');
        $docid = [];
        $ia_det = [];
        if(!empty($r1))
        {
            foreach ($r1 as $row) {
                $docid[] = $row['doc_id'];
                $ia_det[] = array(
                    'd_no' => $row['diary_no'],
                    'doc_id' => $row['doc_id']
                );
            }
        }
       /* $r1 = mysql_query($q1) or die(mysql_error());
        $ia_det = [];
        while ($row = mysql_fetch_array($r1)) {
            $ia_det[] = array(
                'd_no' => $row['diary_no'],
                'doc_id' => $row['doc_id']
            );
        } */

        if (count($docid) > 0) {
            $docid_condition = "docd_id in (" . implode(',', $docid) . ") and display='Y'";
        } else {
            $docid_condition = "display='Y'";
        }

       /* $q2 = "select diary_no, docd_id, rm_dt, case when date_format(rm_dt, '%Y-%m-%d')!='0000-00-00' then 'Defects cured' else 'Defects notified' end as defect_status from obj_save_ia where diary_no='$diaryno' and $docid_condition;";
      
        $r2 = mysql_query($q2) or die(mysql_error()); */

        $r2 = $Model_case_status->getDefectStatus($diaryno, $docid_condition);
       // pr($r2);
        $defect_stat_ia = [];
        if(!empty($r2))
        {
            foreach ($r2 as $row) {
                $defect_stat_ia[] = array(
                    'd_no' => $row['diary_no'],
                    'doc_id' => $row['docd_id'],
                    'defect_status' => $row['defect_status'],
                );                
            }
        }




        $output = "";
        /* $ia = "select d.* ,(select `name` from users where usercode=d.usercode) as username,(select `name` from users where usercode=d.lst_user) as modify_username,(select `name` from users where usercode=d.last_modified_by) as disposedby from docdetails d where d.doccode='8' and d.diary_no='" . $diaryno . "' and (d.display='Y') order by d.ent_dt";
        $result_ia = mysql_query($ia) or die(mysql_error()); */

        $result_ia = $Model_case_status->getIaDetails($diaryno);
       // pr($result_ia);
        if (!empty($result_ia)) {
            $output .= '<table border="0">';
            $output .= '<tr><th colspan="13" align="center">INTERLOCUTARY APPLICATION(s)</th></tr>';
            $output .= '<tr><th>Sr. No.</th><th width="75px" align="center">Reg. No./I.A. No.</th><th>Particular</th><th>Remark</th><th>Filed By</th><th>Filing/Reg. Date</th><th>Status.</th><th>Entered By</th><th>Last Modified By</th><th>Disposed By</th><th>IA Listed on</th><th>IA Stage</th><th>Defect Details</th></tr>';
            $cntt = 0;
            foreach($result_ia as $row_ia) {

                $cntt++;
                $remark_ia = $row_ia['remark'];
                $docnum = $row_ia['docnum'];
                $docyear = $row_ia['docyear'];
                $docd_id = $row_ia['docd_id'];
                $ia = 'ia';
                $ia_listdt = [];
                foreach ($ia_list_data as $ias) {
                    foreach ($ias['ias'] as $item) {
                        if ($item == $docnum . "/" . $docyear) {
                            $ia_listdt[] = $ias['list_dt'];
                        }
                    }
                }
                $ia_defect_stat = '';
                if (count($defect_stat_ia) > 0) {

                    foreach ($defect_stat_ia as $item) {
                        if ($item['doc_id'] == $docd_id) {
                            $ia_defect_stat = $item['defect_status'];
                            $defects_ia = '<td><a href="'.base_url().'/Common/Case_status/fetch_defect_details?docd_id=' . $docd_id . '&diary_no=' . $diaryno .'&ia=' . $ia . '" target="_blank">Click to view</a></td>';

                        }

                    }

                } else {

                    foreach ($ia_det as $ia) {

                        if ($ia['doc_id'] == $docd_id) {
                            $ia_defect_stat = 'Pending Scrutiny';
                            
                        }
                    }
                }

                if (count($defect_stat_ia) > 0) {

                    foreach ($defect_stat_ia as $item) {
                        if ($item['doc_id'] == $docd_id) {
                            $defects_ia = '<td><a href="'.base_url().'/Common/Case_status/fetch_defect_details?docd_id=' . $docd_id . '&diary_no=' . $diaryno .'&doc=' . $doc . '" target="_blank">Click to view</a></td>';

                        }else{
                            $defects_ia = '';

                        }
                    }
                } 

                $doccode = $row_ia['doccode'];
                $doccode1 = $row_ia['doccode1'];
                $iastat = $row_ia['iastat'];
                $filedby = $row_ia['filedby'];
                $enteron = $row_ia['username'] . '<br>' . date("d-m-Y H:i:s", strtotime($row_ia['ent_dt']));
                $other1 = $row_ia['other1'];
                $modifiedby = '';
                if ($row_ia['modify_username'] != null)
                    $modifiedby = $row_ia['modify_username'] . '<br>' . date("d-m-Y H:i:s", strtotime($row_ia['lst_mdf']));
                if ($filedby == "")
                    $filedby = "-";
                $fildt = $row_ia['ent_dt'];
                $fildt1 = substr($fildt, 8, 2) . '-' . substr($fildt, 5, 2) . '-' . substr($fildt, 0, 4);
                $docdesc1 = $row_ia['other1'];
               /* $sql_docm = "select * from docmaster where doccode='" . $doccode . "' and doccode1='" . $doccode1 . "' and display='Y'";
                $result_docm = mysql_query($sql_docm); */

                $row_docm = is_data_from_table('master.docmaster',  " doccode= $doccode and doccode1=$doccode1 and display='Y' ", '*', $row = '');
                $docdesc = "";
                if (!empty($row_docm)) {
                    
                    $docdesc = $row_docm['docdesc'];
                }
               /* $docdesc = "";
                if (mysql_affected_rows() > 0) {
                    $row_docm = mysql_fetch_array($result_docm);
                    $docdesc = $row_docm['docdesc'];
                } */
                $disposed_by = '';
                if ($row_ia['iastat'] == 'D')
                    $disposed_by = $row_ia['disposedby'] . '<br>' . date("d-m-Y H:i:s", strtotime($row_ia['lst_mdf']));
                if (trim($docdesc) == 'OTHER')
                    $docdesc = $docdesc1;
                if (trim($docdesc) == 'XTRA')
                    $docdesc = $other1;
                if (($cntt % 2) == 1)
                    $bgcolor = "#FDFEFF";
                else
                    $bgcolor = "#F5F6F7";
                    $output .= '<tr height="25px" bgcolor="' . $bgcolor . '"><td>' . $cntt . '</td><td>' . $docnum . "/" . $docyear . '</td><td>' . $docdesc . '</td><td>' . $remark_ia . '</td><td>' . $filedby . '</td><td>' . $fildt1 . '</td><td>' . $iastat . '</td><td>' . $enteron . '</td><td>' . $modifiedby . '</td><td>' . $disposed_by . '</td><td>' . implode(', ', $ia_listdt) . '</td><td>' . $ia_defect_stat . '</td><td>' . $defects_ia . '</td></tr>';
            }
            $output .= '</table><br>';
        } else {
            $chk1 = "NF";
        }
        /* $dms = "select d.*, DATE_FORMAT(d.ent_dt,'%d-%m-%Y %h:%i %p') as entdt, (select `name` from users where usercode=d.usercode) as username,(select `name` from users where usercode=d.lst_user) as modify_username from docdetails d where d.doccode!='8' and d.diary_no='" . $diaryno . "' and (d.display='Y') order by d.ent_dt";
        $result_dms = mysql_query($dms); */

        $result_dms = $Model_case_status->getDmsDetails($diaryno);
        if (!empty($result_dms)) {
            $output .= '<table border="0">';
            $output .= '<tr><th colspan="8" align="center">OTHER DOCUMENT(s)</th></tr>';
            $output .= '<tr ><th>Doc. No.</th><th>Document Type</th><th>Filed By</th><th>Filing Date</th><th>Enter By</th><th>Modified By</th><th>IA Stage</th><th>Defect Details</th></tr>';
            $cntt = 0;
            foreach ($result_dms as $row_dms) {
                $cntt++;
                $docnum = $row_dms['docnum'];
                $docyear = $row_dms['docyear'];
                $doccode = $row_dms['doccode'];
                $doccode1 = $row_dms['doccode1'];
                $iastat = $row_dms['iastat'];
                $filedby = $row_dms['filedby'];
                $docd_id = $row_dms['docd_id'];
                $doc ='doc';
                $ia_defect_stat = '';
                if (count($defect_stat_ia) > 0) {

                    foreach ($defect_stat_ia as $item) {
                        if ($item['doc_id'] == $docd_id) {
                            $ia_defect_stat = $item['defect_status'];
                            $defects_ia = '<td><a href="'.base_url().'/Common/Case_status/fetch_defect_details?docd_id=' . $docd_id . '&diary_no=' . $diaryno .'&doc=' . $doc . '" target="_blank">Click to view</a></td>';

                        }
                    }
                } else {

                    foreach ($ia_det as $ia) {

                        if ($ia['doc_id'] == $docd_id) {
                            $ia_defect_stat = 'Pending Scrutiny';

                        }
                    }
                }
                if (count($defect_stat_ia) > 0) {

                    foreach ($defect_stat_ia as $item) {
                        if ($item['doc_id'] == $docd_id) {
                            $defects_ia = '<td><a href="'.base_url().'/Common/Case_status/fetch_defect_details?docd_id=' . $docd_id . '&diary_no=' . $diaryno .'&doc=' . $doc . '" target="_blank">Click to view</a></td>';

                        }else{
                            $defects_ia = '<td>-</td>';

                        }
                    }
                } 
                $enterby = $row_dms['username'] . '<br>' . $row_dms['entdt'];
                if ($filedby == "")
                    $filedby = "-";
                $forresp = $row_dms['forresp'];
                $feemode = $row_dms['feemode'];
                $fildt = $row_dms['entdt'];
                $modifiedby = '';
                if ($row_dms['modify_username'] != null)
                    $modifiedby = $row_dms['modify_username'] . '<br>' . date("d-m-Y H:i:s", strtotime($row_dms['lst_mdf']));
                $fildt1 = $fildt;
                //$fildt1 = substr($fildt, 8, 2) . '-' . substr($fildt, 5, 2) . '-' . substr($fildt, 0, 4);
                $docdesc1 = $row_dms['other1'];
               /* $sql_docm = "select * from docmaster where doccode='" . $doccode . "' and doccode1='" . $doccode1 . "' and (display='Y' or display='E')";
                $result_docm = mysql_query($sql_docm);
                $docdesc = "";
                if (mysql_affected_rows() > 0) {
                    $row_docm = mysql_fetch_array($result_docm);
                    $docdesc = $row_docm['docdesc'];
                } */

                $row_docm = is_data_from_table('master.docmaster',  " doccode= $doccode and doccode1=$doccode1 and and (display='Y' or display='E') ", '*', $row = '');
                $docdesc = "";
                if (!empty($row_docm)) {
                    
                    $docdesc = $row_docm['docdesc'];
                }


                if (trim($docdesc) == 'OTHER')
                    $docdesc = $docdesc1;

                if ($doccode == 7 && $doccode1 == 0)
                    $docno = $docnum . "/" . $docyear . ', Fees Mode: ' . $feemode . ', For Resp: ' . $forresp;
                else
                    $docno = $docnum . "/" . $docyear;
                if (($cntt % 2) == 1)
                    $bgcolor = "#FDFEFF";
                else
                    $bgcolor = "#F5F6F7";
                $output .= '<tr height="25px" bgcolor="' . $bgcolor . '"><td>' . $docno . '</td><td>' . $docdesc . '</td><td>' . $filedby . '</td><td>' . $fildt1 . '</td><td>' . $enterby . '</td><td>' . $modifiedby . '</td><td>' . $ia_defect_stat . '</td><td>' . $defects_ia . '</td></tr>';
            }
            $output .= '</table>';
        } else {
            $chk2 = "NF";
        }
        if ($chk1 == "NF" and $chk2 == "NF")
            $output .= '<p><font color=red><b>INTERLOCUTARY APPLICATIONS / DOCUMENTS NOT FOUND</b></font></p>';
        return $output;