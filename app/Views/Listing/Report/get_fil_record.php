
<div class="table-responsive mt-5">
<table width="100%" class="table table-striped custom-table">
<thead>
    <tr>
        <th>
            S.No.
        </th>
        <th>
            Diary No.
        </th>
        <th>
            Dispatched By
        </th>
        <th>
            Dispatch Date
        </th>
        <?php
        if(($l_sp_split=='spcomp' && $ddl_users!='101') || $l_sp_split=='spcompr')
        {
            ?>
            <th>
                Dispatched To
            </th>
            <th>
                Dispatched To Date
            </th>
        <?php }
        if($l_sp_split=='sptwd')
        {
            ?>
            <th>
                Dispatched to
            </th>
            <th>
                Dispatched date
            </th>
            <th>
                Remark
            </th>
        <?php }
        ?>
        <th>
            Category
        </th>
        <th>
            N.B./B./C.
        </th>
        <?php
        if($ddl_users=='109')
        {
            ?>
            <th>
                Document No./Year
            </th>
            <th>
                Document
            </th>
            <?php
        }
        ?>
            <th>Pages</th>
        <?php
        if($ddl_users=='107' || $ddl_users=='9796')
        {
            ?>
            <th>
                <span style="color: red">Listed</span>/<span style="color: green">Updated</span> on
            </th>
        <?php } ?>
        <!--<th>Total Pages</th>-->
    </tr>
        </thead>
        <tbody>
    <?php
    $sno=1;
    $total_pages = 0; 
    foreach ($sql as $row ) {
       
        ?>
        <tr>
            <td>
                <?php echo $sno; ?>
            </td>
            <td>
                <?php   echo substr( $row['diary_no'], 0, strlen( $row['diary_no'] ) -4 ) ; ?>-<?php echo substr( $row['diary_no'] , -4 ); ?>
            </td>
            <td>
                <?php
                echo $d_to_empid= $Monitoring->get_usr_nm($row['d_to_empid']);
                ?>
            </td>
            <td>
                <?php echo date('d-m-Y H:i:s',strtotime($row['disp_dt'])); ?>
            </td>
          
      
            <?php

            if(($_REQUEST['l_sp_split']=='spcomp' && $ddl_users!='101') || $_REQUEST['l_sp_split']=='spcompr')
            {
                ?>

                <td>
                    <?php
                    if($row['rece_dt']=='0000-00-00 00:00:00') {
                        if($ddl_users=='105' || $ddl_users=='106')
                        echo $d_to_empid= $Monitoring->get_usr_nm($row['d_to_empid']);
                        else
                            echo '-';
                    }
                    else
                    {
                        if($ddl_users=='109')
                            echo $d_to_empid= $Monitoring->get_usr_nm_uid($row['d_to_empid']);
                        else
                        echo $d_to_empid= $Monitoring->get_usr_nm($row['d_to_empid']);

                    }
                    ?>
                </td>
                <td>
                    <?php
                    if($row['rece_dt']=='0000-00-00 00:00:00')
                        echo '-';
                    else
                        echo date('d-m-Y H:i:s',strtotime($row['rece_dt']));
                    ?>
                </td>
            <?php }

            if($_REQUEST['l_sp_split']=='sptwd')
            {
                ?>

                <td>
                    <?php
                    if($row['d_disp_dt']=='0000-00-00 00:00:00')
                        echo '-';

                    else
                        echo $d_d_to_empid= $Monitoring->get_usr_nm($row['d_d_to_empid']);
                    ?>
                </td>
                <td>
                    <?php
                    if($row['d_disp_dt']=='0000-00-00 00:00:00')
                        echo '-';
                    else
                        echo date('d-m-Y H:i:s',strtotime($row['d_disp_dt']));
                    ?>
                </td>
                <td>
                    <?php echo $row['remarks']; ?>
                </td>
            <?php }
            ?>
            <td>
                <?php
                $category=  $Monitoring->getCategory($row['diary_no']);
                if(!empty($category))
                {
                    foreach ($category as $row1) {
                        echo $row1['category_sc_old'].'- '.$row1['sub_name1'].': '.$row1['sub_name4'].'<br/>';
                    }
                }
                else
                {
                    echo '-';
                }
                ?>
            </td>
            <td>
                <?php
                //$n_b=  is_data_from_table('not_before', "diary_no='$row[diary_no]'", 'notbef,j1', $row = 'N');
               // $n_b="Select notbef,j1 from not_before where diary_no='$row[diary_no]' order by notbef";
               //pr($row['diary_no']);
                $n_b= $Monitoring->getNef($row['diary_no']);
               
                $tot_bef_jud='';
                $tot_nbef_jud='';
                $tot_coram='';
                if(!empty($n_b))
                {
                    $b_n='';

                    foreach ($n_b as $row2 ) {
                        if($b_n!=$row2['notbef'])
                        {
                            $be_nb='';
                            $clr='';
                            if($row2['notbef']=='B')
                            {
                                $be_nb="Before";
                                $clr="green";
                            }
                            else
                            {
                                $be_nb="Not Before";
                                $clr="red";
                            }
                            if($row2['notbef']=='B')
                                $tot_bef_jud_h="<span style=color:$clr;>$be_nb-</span>";
                            else   if($row2['notbef']=='N')
                                $tot_nbef_jud_h="<span style=color:$clr;>$be_nb-</span>";

                            $b_n=$row2['notbef'];
                        }
                        $get_judge_nm =  $Monitoring->get_judge_nm($row2['j1']);
                        if($row2['notbef']=='B')
                        {
                            if($tot_bef_jud=='')
                                $tot_bef_jud=$tot_bef_jud_h.$get_judge_nm;
                            else
                                $tot_bef_jud=$tot_bef_jud.', '.$get_judge_nm;
                        }
                        if($row2['notbef']=='N')
                        {
                            if($tot_nbef_jud=='')
                                $tot_nbef_jud=$tot_nbef_jud_h.$get_judge_nm;
                            else
                                $tot_nbef_jud=$tot_nbef_jud.', '.$get_judge_nm;
                        }
                    }
                    if($tot_bef_jud!='')
                        echo $tot_bef_jud;
                    if($tot_nbef_jud!='')
                    {
                        if($tot_bef_jud!='')
                            echo '<br/>';
                        echo $tot_nbef_jud;
                    }
                }
                
                //$r_get_b_c = is_data_from_table('heardt', "diary_no='$row[diary_no]'", 'coram', $row = 'N');
                $r_get_b_c = $Monitoring->getCoarm($row['diary_no']);
                if (is_array($r_get_b_c)) {

                    $r_get_b_c = implode(',', $r_get_b_c);
                }
                
                if ($r_get_b_c === null || $r_get_b_c === '') {
                    $ex_get_b_c = []; 
                } else {
                    $ex_get_b_c = explode(',', $r_get_b_c);
                }
                for ($index = 0; $index < count($ex_get_b_c); $index++) {
                    $get_judge_nm=  $Monitoring->get_judge_nm($ex_get_b_c[$index]);
                    if($tot_coram=='')
                        $tot_coram=$get_judge_nm;
                    else
                        $tot_coram=$tot_coram.', '.$get_judge_nm;
                }
                if($tot_coram!='')
                {
                    if($tot_bef_jud!='' || $tot_nbef_jud!='')
                        echo "<br/>";
                    ?>
                    <span style=color:#5d9c0a>BEFORE CORAM- </span><?php echo $tot_coram; ?>
                    <?php
                }
                ?>
            </td>
            <?php
            if($ddl_users=='109')
            {
                ?>
                <td>
                    <?php
                    echo $row['docnum'].'/'.$row['docyear'];
                    ?>
                </td>
                <td>
                    <?php
                    $other='';
                    if($row['other1']!='')
                        $other= '-'.$row['other1'];
                    echo $row['docdesc'].$other;
                    ?>
                </td>
                <?php
            }
            ?>

            <?php
            if($ddl_users=='107' || $ddl_users=='9796')
            { 
                // $r_h_dt= is_data_from_table('heardt', "diary_no='$row[diary_no]'", 'next_dt,clno,brd_slno', $row = 'A');
                //$r_h_dt="Select next_dt,clno,brd_slno from heardt where diary_no='$row[diary_no]'";
                $r_h_dt=$Monitoring->getNextDates($row['diary_no']);
                
               

                $clr='';
                if($r_h_dt['clno']==0 && $r_h_dt['brd_slno']==0)
                {
                    $clr="green";
                }
                else if($r_h_dt['clno']!=0 && $r_h_dt['brd_slno']!=0)
                {
                    $clr="red";
                }
                ?>

                <td>
                    <span style="color: <?php echo $clr; ?>"><?php echo date('d-m-Y',strtotime($r_h_dt['next_dt'])); ?></span>
                </td>
             
            <?php } ?>
                
                <?php 
            
            //$rs_query =  is_data_from_table('main', "diary_no=$row[diary_no]", 'case_pages', $row = 'A');
            $rs_query =  $Monitoring->getCasePage($row['diary_no']);
            
          // pr($rs_query[0]['case_pages']);
           // Initialize total_pages
           $total_pages += $rs_query[0]['case_pages'];
           foreach ($rs_query as $row_query) {
           
            $pages = $row_query['case_pages'];
           // $total_pages += (int)$pages;
            ?>
            <td><?php echo $pages; ?></td>
            <?php
        }
            ?>
        </tr>
        <?php
        $sno++;
    }
    echo "<center><font color ='red'><b><h2> Total pages ="."$total_pages</h2></b>";
    ?>
        </tbody>
</table>