<div id="dv_dup">
    <table class="c_vertical_align tbl_border" width="100%" align="center" cellpadding="5" cellspacing="5">
        <tr>
            <th>SNo</th>
            <th>Filing Number</th>
            <th style="background-color: #dde9ff;display: none">Receiving Date From RJ-I</th>
            <th style="background-color: #dde9ff;display: none">Time Given By RJ-I To Remove Default</th>
            <th>Petitioner <br /> vs <br />Respondent</th>
            <!--        <th >Respondent Name</th>-->
            <th>Default</th>
            <th>Select</th>
            <th>Remove</th>
            <th>
                Ignore
            </th>
            <!--      <th >Listing</th>-->
            <th>Remove Selected Default</th>
        </tr>
        <?php // 
        
        if (!empty($results)) {
            //$i=1;
            $check = '';
            $sno = 0;
            $sno1 = -1;
            $sno3 = 1;

            $sCount = 0;
            $chkOk = 0;
            $chkOk1 = 0;
            $c_mnlk = 1;
            $color = '#fff';
            foreach($results as $row) {
                if ($sno1 + 1 < $row['c']) {
                    // $sCount1++;

                    $sno1++;
                } else if (($sno1 + 1) == $row['c']) {
                    $chkOk1++;
                    $sno1 = 0;
                }

                $filing_no = $row['diary_no'];
                
                // navigate_diary($filing_no);
                ?>
                <tr id="tr_hd_sho<?php echo $chkOk1 . $sno1; ?>" class="jd">

                    <? //if($i==1){
                    ?>
                    <? if ($row['diary_no'] != $check) { ?>
                        <input type="hidden" name="hd_checke<?php echo $sCount; ?>" id="hd_checke<?php echo $sCount; ?>" value="<?php echo $row['c']; ?>" />
                        <input type="hidden" name="hd_fc<?php echo $sCount; ?>" id="hd_fc<?php echo $sCount; ?>" />
                        <input type="hidden" name="hd_fil_no<?php echo $sCount; ?>" id="hd_fil_no<?php echo $sCount; ?>" value="<?php echo $row['diary_no'] ?>" />

                        <!--<input type="hidden" name="hd_category<?php echo $sCount; ?>" id="hd_category<?php echo $sCount; ?>" value="<?php //echo $row['category'] ?>"/>-->

                        <td id="td_ssno<?php echo $chkOk1 . $sno1; ?>" align="left" width="10%" rowspan="<? //echo $row['c']; ?>"><?php echo @$sno3;$sno3++;//$c_mnlk ?></td> 

                        <td id="td_case_yr<?php echo $chkOk1 . $sno1; ?>" align="left" width="10%" rowspan="<? echo $row['c']; ?>"><span style="background-color: #474846;color:white "><b><?php echo substr($row['diary_no'], 0, -4) . '-' .  substr($row['diary_no'], -4); ?></b></span> <?php echo "<br/>" . $row['fdt']; ?><b>(F)</b><br /><?php echo $row['save_dt']; ?><b>(C)</b></td>
                        <td style="display: none" id="td_rcdt<?php echo $chkOk1 . $sno1; ?>" align="left" width="10%" rowspan="<? echo $row['c']; ?>"><?php echo $row['j1_sn_dt'] ?></td>
                        <td style="display: none" id="td_rem_dt<?php echo $chkOk1 . $sno1; ?>" align="left" width="10%" rowspan="<? echo $row['c']; ?>"><?php echo $row['j1_tot_da'] ?></td>
                        <td id="td_pet_nm<?php echo $chkOk1 . $sno1; ?>" align="middle" width="10%" rowspan="<? echo $row['c']; ?>"><?php echo $row['pet_name']; ?><br /><b>vs</b><br /><?php echo $row['res_name']; ?></td>
                        <!--        <td id="td_res_nm<?php //echo $chkOk1.$sno1; 
                                                        ?>"  align="left" width="15%" rowspan="<? //echo $row['c'];
                                                                                                                        ?>"><?php //echo $row['res_name'];
                                                                                                                                                ?></td>-->
                        <input type="hidden" name="hd_change<?php echo $chkOk1 . $sno1; ?>" id="hd_change<?php echo $chkOk1 . $sno1; ?>" />
                    <?

                        $sno = 0;
                        $chkOk = $sCount;
                        //$sno1=1;
                        $c_mnlk++;
                    } else {
                        $sno++;
                        //if($sno1<($row['c']-1))
                        //$sno1++;  
                    } ?>
                    <input type="hidden" name="hdId_<?php echo $chkOk . $sno; ?>" id="hdId_<?php echo $chkOk . $sno; ?>" value="<?php echo $row['id']; ?>" />
                    <input type="hidden" name="hdstatus_<?php echo $chkOk . $sno; ?>" id="hdstatus_<?php echo $chkOk . $sno; ?>" value="<?php echo $row['status']; ?>" />
                    <td align="left" width="50%" id="td_obj_name<?php echo $chkOk . $sno; ?>">
                        <?php
                        $se_va_l = '';
                        ######################## Table Missing Need to Uncomment #############################
                        // if ($row['status'] == '6') {
                        //     $se_va_l = '';
                        //     $sql_get = mysql_query("Select ia_nm_yr,status from  def_ia where def_id='$row[id]'");
                        //     if (mysql_num_rows($sql_get) > 0) {
                        //         while ($row_s = mysql_fetch_array($sql_get)) {
                        //             //   $se_va_l= $row_s['ia_nm_yr']; 
                        //             if ($se_va_l == '') {
                        //                 //                        $se_va_l= "For Orders On IA ".$row_s['ia_nm_yr']; 
                        //                 $se_va_l = $row_s['ia_nm_yr'];
                        //                 if ($row_s['status'] == 'D') {
                        //                     $se_va_l =  $se_va_l . " Disposed";
                        //                 } elseif ($row_s['status'] == 'W') {
                        //                     $se_va_l =  $se_va_l . " Wrongly Sent";
                        //                 }
                        //             } else {
                        //                 $se_va_l = $se_va_l . ',' . $row_s['ia_nm_yr'];
                        //                 if ($row_s['status'] == 'D') {
                        //                     $se_va_l = $se_va_l . " Disposed";
                        //                 } elseif ($row_s['status'] == 'W') {
                        //                     $se_va_l = $se_va_l . " Wrongly Sent";
                        //                 }
                        //             }
                        //         }
                        //         //  if($row['j1_tot_da']=='0000-00-00 00:00:00')
                        //         //                        if()
                        //         //                        {
                        //         //                            $se_va_l=$se_va_l." Wrongly Sent";
                        //         //                        }
                        //         //                      else if($row['j1_tot_da']!='0000-00-00 00:00:00')
                        //         //                        {
                        //         //                           $se_va_l=$se_va_l." Disposed";
                        //         //                        }
                        //     }
                        // } else {
                        //     $se_va_l = '';
                        // }
                        ?>
                        <span id="spo_obj_name<?php echo $chkOk . $sno; ?>" <?php if ($row['objcode'] == '612' || $row['objcode'] == '712' || $row['objcode'] == '812') { ?> style="color: blue" <?php } ?>><?php echo $row['obj_name'] . '<b>(' . $row['remark'] . ' ' . $row['mul_ent'] . ')</b><span style="color:green">' . $se_va_l . '</span>'; ?>
                        </span>
                    </td>
                    <td align="center" width="5%" id="td_ck_n<?php echo $chkOk . $sno; ?>">
                        <input type='checkbox' name='delete<?php echo $chkOk . $sno; ?>' id='delete<?php echo $chkOk . '^' . $sno; ?>' />
                    </td>
                    <td id="td_Remove<?php echo $chkOk . $sno; ?>"><input type="button" value="Remove" onclick="get_record(this.id)" name="btnRemove_<?php echo $chkOk . '^' . $sno; ?>" id="btnRemove_<?php echo $chkOk . '^' . $sno; ?>" /></td>
                    <td id="td_Ignore<?php echo $chkOk . $sno; ?>"><input type="button" value="Ignore" onclick="get_record(this.id)" name="btnIgnore_<?php echo $chkOk . '^' . $sno; ?>" id="btnIgnore_<?php echo $chkOk . '^' . $sno; ?>" /></td>
                    <? //if($i==1){
                    ?>
                    <?php if ($row['diary_no'] != $check) { ?>
                        <td id="td_removeAll<?php echo $chkOk1 . $sno1; ?>" rowspan="<? echo $row['c']; ?>"><input type="button" value="Remove All" onclick="RemoveAll(this.id)" name="RemoveAll_<?php echo $chkOk; ?>" id="RemoveAll_<?php echo $chkOk; ?>" /></td>
                        <input type="hidden" name="hd_tot_no<?php echo $chkOk; ?>" id="hd_tot_no<?php echo $chkOk; ?>" value="<?php echo $row['c'] ?>" />

                    <?php
                        $check = $row['diary_no'];
                        $sCount++;
                    } ?>
                    <?
                    //        if($i==$row['c'])
                    //            $i=1;
                    //        else
                    //            $i++;
                    ?>
                </tr>
        <?php

            }
        } else {
            echo "<tr><td colspan='8'><div style='text-align: center'>No Record Found</div>  </td></tr>";
        }

        //  if($sno1==0)
        //     $sno1++;

        ?>
    </table>
</div>