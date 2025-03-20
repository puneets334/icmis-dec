<?php
 $dcmis_user_idd=  session()->get('login')['usercode'];
 
			$fst = 0;
            $lst = 30;
            $inc_val = 30;
			$rmtable="";
			
        if ($res_sq_count > 0) 
		{
            $fst = 0;
            $lst = 30;
            $inc_val = 30;
 
            $tot_pg = ceil($res_sq_count / $inc_val);

            ?>

            <input type="hidden" name="hd_fst" id="hd_fst" value="<?php echo $fst; ?>"/>
            <input type="hidden" name="hd_lst" id="hd_lst" value="<?php echo $lst; ?>"/>
            <input type="hidden" name="inc_val" id="inc_val" value="<?php echo $inc_val; ?>"/>
            <input type="hidden" name="inc_tot" id="inc_tot" value="<?php echo $tot_pg; ?>"/>

            <input type="hidden" name="inc_count" id="inc_count" value="1"/>
            <div style="text-align: center" class="dv_right" id="dv_le_ri">
                <span id="sp_frst"><?php echo $fst + 1 ?></span>-<span
                        id="sp_last"><?php if ($res_sq_count < $inc_val) {
                        echo $res_sq_count;
                    } else {
                        echo($fst + $inc_val);
                    } ?></span> of <span id="sp_nf"><?php echo($res_sq_count) ?></span>
                <?php
                if ($res_sq_count > $inc_val) {
                    ?>
                    <input type="button" name="btn_left" id="btn_left" onClick="getbtn_left();" value="PREV"/>
                    <input type="button" name="btn_right" id="btn_right" onClick="getbtn_right();" value="NEXT"/>
                    <?php
                }
                ?></div>

            <?php
        }

        if ($_REQUEST['u_t'] == 1) {
            $fst = intval($_REQUEST['nw_hd_fst']);
            //$inc_val=intval($_REQUEST['inc_val']);
            $inc_val = $fst + $inc_val;
        }


        /****** Paging end *****/
       
//exit();
	if ($result_da_status > 0) 
	{
		
		$sqlresult = $HcorModel->getRankedData($condition, $fst, $inc_val);
	 
        if ($res_sq_count > 0) 
		{
			
            
            if (!empty($sqlresult)) {
                
               
			  $row_verify =  (!empty($HcorModel->getDefects($diary_no))) ? $HcorModel->getDefects($diary_no) : '';
			  $count_verify =  (!empty($HcorModel->getDefects($diary_no))) ? count($HcorModel->getDefects($diary_no)) : 0;
			  
                ?>
                <div id="dv_include" style="text-align: center;width: 100%">
                    <div align="center">
                        <table class="table_tr_th_w_clr" cellpadding="5" cellspacing="5">
                            <tr>
                                <th>
                                    S.No.
                                </th>
                                <th>
                                    Diary No.
                                </th>

                                <th>
                                    Case No.
                                </th>
                                <th>
                                    Status
                                </th>

                                    <th>
                                        State
                                    </th>
                                <?php
                                if ($count_verify < 1 AND $dcmis_user_idd==$row_lp123["dacode"]) { ?>
                                    <th>
                                        Details
                                    </th>
                                   <?php }else if($count_verify > 0 AND in_array($dcmis_user_idd, $users_to_ignore) and $row_verify[0]['if_verified']=='V'){ ?>
                                    <th>
                                        Details
                                    </th>
                                    <?php }
                                   if($count_verify < 1 AND $dcmis_user_idd==$row_lp123["dacode"]){?>
                                    <th>
                                        Verify Record
                                    </th>
                                <?php } ?>
                            </tr>

                            <?php
                            $sno = 1;
                            $ifConfirmed=0;
                            $diary_no = '';
                          
                            if ($_REQUEST['u_t'] == 0)
                                $sno = 1;
                            else if ($_REQUEST['u_t'] == 1)
                                $sno = $_REQUEST['inc_tot_pg'];

                            foreach($sqlresult as $row1) {
                                ?>
                                <tr class="tr_diary<?php if ($diary_no != $row1['diary_no']) {
                                    echo $sno;
                                } else {
                                    echo $sno - 1;
                                } ?>">
                                    <?php

                                    if ($diary_no != $row1['diary_no']) {                                       
										
										$res_sql = $HcorModel->getlowerctDiaryCount($row1['diary_no']);
                                        ?>
                                        <td rowspan="<?php echo $res_sql; ?>">
                                            <?php echo $sno; ?>
                                        </td>
                                        <td rowspan="<?php echo $res_sql; ?>">
                                        <span id="sp_diary_no<?php echo $sno; ?>"
                                              class="cl_c_diary"><?php echo substr($row1['diary_no'], 0, -4) . '-' . substr($row1['diary_no'], -4); ?></span>
                                        </td>
                                        <?php 
                                    }

                                    ?>
                                    <td>
                                        <?php echo $row1['type_sname']; ?>-<?php echo $row1['lct_caseno']; ?>
                                        -<?php echo $row1['lct_caseyear']; ?>
                                    </td>
                                    <?php
                                    if ($diary_no != $row1['diary_no']) {
                                        ?>
                                        <td rowspan="<?php echo $res_sql; ?>">
                                            <?php
                                            if ($row1['conformation'] == '0')
                                                echo "Not Completed";
                                            else if ($row1['conformation'] == '1'){
                                                echo "Completed";
                                                $ifConfirmed=1;
                                            }
                                            ?> 
                                        </td>
                                            <td rowspan="<?php echo $res_sql; ?>">
                                                <?php
                                                echo $row1['agency_name'];
                                                ?>
                                            </td>
                                        <?php
                                        if ($count_verify < 1 AND $dcmis_user_idd==$row_lp123["dacode"]) { ?>
                                            <td rowspan="<?php echo $res_sql; ?>">
                                                <span class="sp_details" id="sp_d_<?php echo $sno; ?>">Details</span>
                                            </td>
                                        <?php }else if($count_verify > 0 AND in_array($dcmis_user_idd, $users_to_ignore) and $row_verify[0]['if_verified']=='V'){ ?>
                                            <td rowspan="<?php echo $res_sql; ?>">
                                                <span class="sp_details" id="sp_d_<?php echo $sno; ?>">Details</span>
                                            </td>
                                        <?php }

                                        if($ifConfirmed){
                                            if($count_verify < 1 AND $dcmis_user_idd==$row_lp123["dacode"]){?>
                                                <td rowspan="<?php echo $res_sql; ?>">
                                                <span class="sp_verify"
                                                      id="spv-<?php echo substr($row1['diary_no'], 0, -4) . '-' . substr($row1['diary_no'], -4); ?>">verify</span>

                                                </td>
                                            <?php }
                                        } 
                                        $diary_no = $row1['diary_no'];
                                        $sno++; 
                                    } ?>
                                </tr>
                                <?php

                            }
                            ?>
                        </table>


                        <input type="hidden" name="hd_cnt_no" id="hd_cnt_no"/>
                        <input type="hidden" name="hd_fil_no" id="hd_fil_no"/>
                        <input type='hidden' name='inc_tot_pg' id='inc_tot_pg' value="<?php echo $sno; ?>"/>
                    </div>
                </div>
                <?php
                if ($count_verify > 0) { 
					$sql_verify_found = $HcorModel->getDefectVerification($diary_no);
					
                    if (!empty($sql_verify_found)) {
                        foreach ($sql_verify_found as $row_verify) {
                            if (!in_array($dcmis_user_idd, $users_to_ignore)) {
                                ?>
                                <div class="cl_center" style="margin-top:20px;"><b style="font-size: large">
                                        Verified By: <?php echo $row_verify['name']; ?>
                                        on <?php echo $row_verify['verify_on']; ?>
                                        <?php if($row_verify['if_verified']=='D'){
                                            echo "<br><span style='color:red;font-size: large'>Defective :". $row_verify['remarks']."</span>";
                                            echo "<br/>(Please re-open for Updation from High Court)</b>";
                                            ?>
                                            <br/>
                                            <input type="hidden" name="hdn_diary_no" id="hdn_diary_no" value="<?=$diary_no?>">
                                            <?php if($ifConfirmed){
                                                echo "<br/><input type=\"button\" style=\"color: red; font-weight: bold\" name=\"btnReOpen\" id=\"btnReOpen\" value=\"Re-Open For Updation\" onclick=\"doReOpen();\">";
                                            }
                                        }
                                        else{
                                            echo "<br><span style='color:green;font-size: large'>Verified :". $row_verify['remarks']."</span>";
                                            echo "<br/>(Please contact Section V for viewing Record.)</b>";
                                        }
                                        ?>
                                        </div>
                            <?php }
                            else{?>
                                 <div class="cl_center" style="margin-top:20px; color:red;"><b style="font-size: large">
                                         Verified By: <?php echo $row_verify['name']; ?>
                                         on <?php echo $row_verify['verify_on']; ?>
                                         <?php if($row_verify['if_verified']=='D'){
                                         echo "<br><span style='color:red;font-size: large'>Defective :". $row_verify['remarks']."</span>";
                                        }
                                        else{
                                            echo "<br><span style='color:green;font-size: large'>Verified :". $row_verify['remarks']."</span>";
                                        } ?></b></div>
                           <?php }
                        }
                    }
                }

                else
                {
                   
					$sql_verify_pending = $HcorModel->getPendingVerification($diary_no);
                    if (!empty($sql_verify_pending)) {
                        foreach ($sql_verify_pending as $row_verify_pending) { ?>
                            <div class="cl_center" style="margin-top:20px; color:red;"><b style="font-size: large">
                                    Verification is pending from DA: <?php echo $row_verify_pending['name']; ?>
                                    </b></div>
                        <?php }
                    }
                }?>

                <div id="dv_sh_hd" style="display: none;position: fixed;top: 0;width: 100%;height: 100%;background-color: black;opacity: 0.6;left: 0;overflow: hidden;z-index: 103" >
				   &nbsp;
				</div>
				<div id="dv_fixedFor_P" style="position: fixed;top:0;display: none;left:0;width:100%;height:100%;z-index: 105;">
					<div id="sp_close" style="text-align: right;cursor: pointer;width: 40px;float: right" onclick="closeData()" ><b><img src="../images/close_btn.png" style="width:30px;height:30px"/></b></div>
					<div  style="width: auto;background-color: white;overflow: scroll;height: 500px;margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;word-wrap: break-word;" id="ggg" onkeypress="return  nb(event)" onmouseup="checkStat()"></div>
				</div>
           <?php } else {
                ?>
                <div class="cl_center"><b>No Record Found</b></div>
                <?php
            }


        } else {
            ?>
            <div class="cl_center"><b>No Record Found</b></div>
            <?php
        }
    }
    else{
        $rmtable.= "<center><b><font color='red' style='font-size:16px;'>".$result_da[1]."</font></b></center>";
    }
    echo $rmtable;

    ?>