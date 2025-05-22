<?php
if(!empty($res) && count($res)>0){
    $pet_name=$res['pet_name'];
    $res_name = $res['res_name'];
    $cause_title= $pet_name . "  VS  ".$res_name;
}

 ?>
 <center><b><h2><?php //echo $cause_title; ?></b></center><h2>
 <table  width="100%" class="c_vertical_align tbl_border" align="center" id="tb_bn" cellpadding="5" cellspacing="5">
    <?php
        $def_notify_qry = _getwhere($def_notify,'yes');
        if(!empty($def_notify_qry) && count($def_notify_qry)>0){    
            foreach($def_notify_qry as $result)
            {
                $def_notify_date = $result['save_dt'];
                $rem__date = $result['rm_dt'];
                  $df=$result['df'];  // defects notification date 
                  
               //   echo "defects notification date = ".$df;
                if($rem__date==null) {
                    echo " <center><b><span style='color: red;' >No defects found</span></b></center><br>";
                    exit();
                } 
                if ($rem__date != '0000-00-00') {
                    echo " <center><b><span style='color: red;' >No defects found</span></b></center><br>";
                    exit();
                }
            }
        }

        $soft_copy_user=0;
        $softcopy_user_rs = _getwhere($def_notify,'no');
        if(!empty($softcopy_user_rs) && count($softcopy_user_rs )>0)
        {    
            $soft_copy_user=1;
        }
        if($soft_copy_user!=1)
        {
            $check_fil_trap_rs = _getwhere($check_fil_trap,'no');
            if(!empty($check_fil_trap_rs) && count($check_fil_trap_rs )>0 && $ucode != 1 )
            {
                    $a= $check_fil_trap_rs;
                    if($a[usercode]!=$ucode){
                        echo '<div style="text-align: center; color:red"><h3>Defects can be cured by '.$a[d_to_empid].'-'.$a[name] .'</h3></div>';
                        exit();
                    }
            }
            else{
                echo '<div style="text-align: center;color :red" ><h3>Defects can not be Cured as matter is not Marked from File Dispatch Receive for Refiling</h3></div>';
             exit();
            }

        }
        $soft_copy_defect=0;
        if($soft_copy_user==1) {
            $rs_softcopy_def = _getwhere($softcopy_def,'yes');
            if(!empty($rs_softcopy_def) && count($rs_softcopy_def)>0)
            {
                $soft_copy_defect = 1;
            }
        }
        $c_date=date('Y-m-d');
        $refil_date =date('Y-m-d');

        $get_no_of_days = _getwhere($get_no_of_days_qry,'no');
        $res_no_of_days=$get_no_of_days;
        $i = 0;
        $def_rem_max_date=date(date("Y-m-d", strtotime($def_notify_date)) . " +".$i."days");
        $def_rem_max_date = date('Y-m-d', strtotime($def_notify_date . ' + ' . $res_no_of_days . ' days'));
        $nextdate = next_date_defect($def_rem_max_date, 1);             
        $last_day_of_refiling=$nextdate;

        if($soft_copy_defect==0)
        {
            $find_doc = _getwhere($find_doc_qry,'no'); 
            if(!empty($find_doc) && count($find_doc)>0){
                echo " <br><center><b><span style='color: red;' >IA FOR DELAY IN REFILING HAS BEEN FILED </span></b></center>";
            }
            else{
                echo " <br><center><b><span style='color: red;' >There is delay of ".$total." days. PLEASE FILE IA FOR DELAY IN REFILING FIRST !!!!</span></b></center><br> ";
                // exit();
                // echo " <br><center><b><span style='color: red;' >There is delay of ".$diff." days. sfsfsfs</span></b></center>";
                $chk_diff=1;
            }
        }
        else{
            if($soft_copy_user==1 && $soft_copy_defect==0){
                echo " <br><center><b><span style='color: red;' >Soft copy defect not found</span></b></center><br>";
                exit();
            }
            echo " <br><center><b><span style='color: Blue;' >Remove Default</span></b></center><br>";
        }



     ?>

     <tr>
            <th width="2%">SNo</th>
            <th width="70%">Default</th>
            <th width="20%">Default notified by</th>
            <?php
            if($chk_diff==0)
            {
                ?>
                <th >Select    <input type='checkbox' class='checkbox' name='all' id='all' /> </th>
                <th >Remove</th>
                <th >Listing</th>
                <th >Remove Selected Default</th>
            <?php } ?>
        </tr>
        <?php
        if(!empty($res_wdn) && count($res_wdn)>0){
            $f=0;
            $check = '';
            $sno = 0;
            $sno1 = -1;

            $sCount = 0;
            $chkOk = 0;
            $chkOk1 = 0;
            $c_mnlk = 1;
            $color = '#fff';
            foreach($res_wdn as $row)
            {
                if ($sno1 + 1 < $row['c']) {
                    // $sCount1++;

                    $sno1++;
                } else if (($sno1 + 1) == $row['c']) {
                    $chkOk1++;
                    $sno1 = 0;
                }
        ?>
                <tr id="tr_hd_sho<?php echo $chkOk1 . $sno1; ?>" class="jd">
                    <td><?php echo ++$f; ?></td>
                    <?php if (($row['diary_no']) != $check) { ?>
                        <input type="hidden" name="hd_checke<?php echo $sCount; ?>"
                               id="hd_checke<?php echo $sCount; ?>"
                               value="<?php echo $row['c']; ?>"/>
                        <input type="hidden" name="hd_fc<?php echo $sCount; ?>"
                               id="hd_fc<?php echo $sCount; ?>"/>
                        <input type="hidden" name="hd_fil_no<?php echo $sCount; ?>"
                               id="hd_fil_no<?php echo $sCount; ?>"
                               value="<?php echo $row['diary_no']; ?>"/>

                        <!--<input type="hidden" name="hd_category<?php echo $sCount; ?>" id="hd_category<?php echo $sCount; ?>" value="<?php echo $row['category'] ?>"/>-->

                        <!-- <td id="td_ssno<?php //echo $chkOk1 . $sno1; ?>" align="left" width="10%"
                                                rowspan="<? //echo $row['c']; ?>"><?php //echo $c_mnlk ?></td>-->

                        <!-- <td id="td_case_yr<?php //echo $chkOk1 . $sno1; ?>" align="left" width="10%"
                                                rowspan="<? //echo $row['c']; ?>">-->
                        <!--  <span>
                                                <b><?php //echo substr($row['diary_no'], 0, -4) . '-' . substr($row['diary_no'], -4); ?></b>
                                            </span> <?php // echo "<br/>" . date('d-m-Y', strtotime($row['fdt'])); ?>
                                                <b>(F)</b><br/><?php //echo date('d-m-Y H:i:s', strtotime($row['save_dt'])); ?>
                                                <b>(C)</b></td>
                                            <td id="td_pet_nm<?php echo $chkOk1 . $sno1; ?>" align="middle" width="10%"
                                                rowspan="<?  //echo $row['c']; ?>"><?php //echo $row['pet_name']; ?><br/><b>vs</b><br/><?php //echo $row['res_name']; ?>
                                            </td>-->

                        <input type="hidden" name="hd_change<?php echo $chkOk1 . $sno1; ?>"
                               id="hd_change<?php echo $chkOk1 . $sno1; ?>"/>
                        <?php
                        $sno = 0;
                        $chkOk = $sCount;
                        //$sno1=1;
                        $c_mnlk++;
                    } else {
                        //echo "rows <0";
                        $sno++;
                        //if($sno1<($row['c']-1))
                        //$sno1++;
                    }
                    ?>
                    <input type="hidden" name="hdId_<?php echo $chkOk . $sno; ?>"
                           id="hdId_<?php echo $chkOk . $sno; ?>"
                           value="<?php echo $row['id']; ?>"/>
                    <input type="hidden" name="hdstatus_<?php echo $chkOk . $sno; ?>"
                           id="hdstatus_<?php echo $chkOk . $sno; ?>"
                           value="<?php echo $row['status']; ?>"/>
                    <td align="left" width="50%" id="td_obj_name<?php echo $chkOk . $sno; ?>"><span
                                id="spo_obj_name<?php echo $chkOk . $sno; ?>"><?php echo $row['obj_name'] . '<b>(' . $row['remark'] . ' ' . $row['mul_ent'] . ')</b>'; ?></span>
                    </td>
                    <?php
                        if($chk_diff==0)
                        {
                    ?>
                            <td align="center" width="20%"> <?php echo $row['name']?></td>
                            <td align="center" width="5%" id="td_ck_n<?php echo $chkOk . $sno; ?>">

                                <input type='checkbox' class='checkbox' name='delete<?php echo $chkOk . $sno; ?>'
                                    id='delete<?php echo $chkOk . '^' . $sno; ?>'/>

                            </td>
                            <td id="td_Remove<?php echo $chkOk . $sno; ?>"><input type="button"
                                                                                value="Remove"
                                                                                onclick="get_record(this.id)"
                                                                                name="btnRemove_<?php echo $chkOk . '^' . $sno; ?>"
                                                                                id="btnRemove_<?php echo $chkOk . '^' . $sno; ?>"
                                                                                style="display: none"/>
                            </td>
                            <?php if (($row['diary_no']) != $check) { ?>

                               <td id="td_btnListing<?php echo $chkOk1 . $sno1; ?>"
                                rowspan="<?php echo $row['c']; ?>"><input type="button" value="Listing"
                                                                    onclick="slt_rj1_ct(this.id)"
                                                                    name="btnListing_<?php echo $chkOk; ?>"
                                                                    id="btnListing_<?php echo $chkOk; ?>"/>
                            </td>
                            <td id="td_removeAll<?php echo $chkOk1 . $sno1; ?>"
                                rowspan="<?php echo $row['c']; ?>"><input type="button" value="Remove All"
                                                                    onclick="RemoveAll(this.id)"
                                                                    name="RemoveAll_<?php echo $chkOk; ?>"
                                                                    id="RemoveAll_<?php echo $chkOk; ?>"/>
                            </td>

                            <input type="hidden" name="hd_tot_no<?php echo $chkOk; ?>"
                                id="hd_tot_no<?php echo $chkOk; ?>" value="<?php echo $row['c'] ?>"/> 

                    <?php
                        $check = $row['diary_no'];
                            $sCount++;
                        } 
                    } ?>


                </tr>
        
        <?php } ?>

        <tr>
                <td colspan="<?php if($chk_diff==0) { echo '8';} else { echo '2';} ?>">
                    <div style="text-align: center">


                        <span style="color: red;">Please click SMS button if defect(s) still present after refiling.</span>
                        <input type="button" name="btn_sms" id="btn_sms" value="SMS"/>
                        <div id="sp_sms_status" style="text-align: center"></div>
                    </div>
                </td>
        </tr>

        <?php    
        }else{
            echo "  <tr><td colspan='8'><div style='text-align: center'>No Record Found</div>  </td></tr>";
        }
         ?>   
         
         
     

</table> 