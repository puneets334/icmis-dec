<?php
$db = \Config\Database::connect();
        //get IA listing date

       $r =  $Model_case_status->getNextHearingDetails($diaryno,$flag);
        $ia_list_data = [];
        
        if(!empty($r))
        {
            foreach ($r as $row ) 
            {
                $ia_list_data[] = array(
                    'diary_no' => $row['diary_no'],
                    'list_dt' => date('d-m-Y', strtotime($row['next_dt'])),
                    'ias' =>  (!empty($row['listed_ia'])) ?  explode(',', rtrim($row['listed_ia'],',')) : '',
                );
            }
        }
         
        //get IA defects details
        $q1 = "select distinct diary_no, doc_id from doc_receive where diary_no ='$diaryno';";
        $query = $db->query($q1);
        $r1 = $query->getResultArray();       
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

        if (count($docid) > 0) {
            $docid_condition = "docd_id in (" . implode(',', $docid) . ") and display='Y'";
        } else {
            $docid_condition = "display='Y'";
        }
 

        $r2 = $Model_case_status->getDefectStatus($diaryno, $docid_condition);
        
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
        

        $result_ia = $Model_case_status->getIaDetails($diaryno);
       // pr($result_ia);
        if (!empty($result_ia)) {
            $output .= '<h3 class="pt-4" align="center">INTERLOCUTARY APPLICATION(s)</h3>';
            $output .= '<table border="0" class="table custom-table">';
            //$output .= '<thead><tr><th colspan="13" align="center">INTERLOCUTARY APPLICATION(s)</th></tr>';
            $output .= '<thead><tr><th>Sr. No.</th><th width="75px" align="center">Reg. No./I.A. No.</th><th>Particular</th><th>Remark</th><th>Filed By</th><th>Filing/Reg. Date</th><th>Status.</th><th>Entered By</th><th>Last Modified By</th><th>Disposed By</th><th>IA Listed on</th><th>IA Stage</th><th>Defect Details</th></tr></thead><tbody>';
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
                    if(!empty($ias['ias']))
                    {
                        foreach ($ias['ias'] as $item) {
                            if ($item == $docnum . "/" . $docyear) {
                                $ia_listdt[] = $ias['list_dt'];
                            }
                        }
                    }
                }

                
                $ia_defect_stat = '';
                $defects_ia = '';
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
               

                $row_docm = is_data_from_table('master.docmaster',  " doccode= $doccode and doccode1=$doccode1 and display='Y' ", '*', $row = '');
                $docdesc = "";
                if (!empty($row_docm)) {
                    
                    $docdesc = $row_docm['docdesc'];
                }
              
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
            $output .= '</tbody></table><br>';
            $chk1 = '';
        } else {
            $chk1 = "NF";
        }
        

        $result_dms = $Model_case_status->getDmsDetails($diaryno);
        
        if (!empty($result_dms)) {
            $output .= '<h3 class="pt-4" align="center">OTHER DOCUMENT(s)</h3>';
            $output .= '<table border="0" class="table custom-table">';
            //$output .= '<thead><tr><th colspan="8" align="center">OTHER DOCUMENT(s)</th></tr>';
            $output .= '<thead><tr ><th>Doc. No.</th><th>Document Type</th><th>Filed By</th><th>Filing Date</th><th>Enter By</th><th>Modified By</th><th>IA Stage</th><th>Defect Details</th></tr></thead><tbody>';
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
                $defects_ia = '';
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
                

                $row_docm = is_data_from_table('master.docmaster',  " doccode= $doccode and doccode1=$doccode1 and  (display='Y' or display='E') ", '*', $row = '');
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
            $output .= '</tbody></table>';
            $chk2 = '';
        } else {
            $chk2 = "NF";
        }
        
        if ($chk1 == "NF" && $chk2 == "NF")
            $output .= '<p><font color=red><b>INTERLOCUTARY APPLICATIONS / DOCUMENTS NOT FOUND</b></font></p>';
        echo $output;