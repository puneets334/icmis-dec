<div class="cl_center" style="color: red;font-weight: bold">
    <?= "Is " . $res_casetype_added . " ?"; ?>
    <input type='checkbox' name='casetype' id='casetype' value='<?= $res_p_r['casetype_id']; ?>' required><br>
</div>

<input type="hidden" value="<?= $res_p_r['casetype_id']; ?>" id="hd_casetype_id">

<?php if (in_array($res_p_r['casetype_id'], [5, 6, 17, 24, 32, 33, 34, 35, 27, 40, 41])) { ?>
    <?php
    // Assuming you have the logic for checking defects in your model
    $res_ck_def = $modelIA->checkDefects($dairy_no);
    if ($res_ck_def > 0): ?>
        <div style="text-align: center"><b>Please remove defects before generating Registration No.</b></div>
        <?php else:
        $check_ia = $modelIA->getPendingIADetails($dairy_no);
        if (count($check_ia) > 0):
            $ia_details = '';
            foreach ($check_ia as $row1) {
                $others = $row1['other1'] != '' ? '- ' . $row1['other1'] : '';
                if ($ia_details == '') {
                    $ia_details = $row1['docnum'] . '/' . $row1['docyear'] . '- ' . $row1['docdesc'] . $others;
                } else {
                    $ia_details .= ', ' . $row1['docnum'] . '/' . $row1['docyear'] . '- ' . $row1['docdesc'] . $others;
                }
            }
        ?>

            <div class="cl_center"><b>Can't register case because IA(s) <span style="color:red"><?= $ia_details; ?></span> is still pending.</b></div>
        <?php else: ?>
            <div style="text-align: center">
                <h3><b><?= $modelIA->get_causetitle($dairy_no); ?></b></h3>
                <input type="hidden" name="hd_casetype_id" id="hd_casetype_id" value="<?= $res_p_r['casetype_id']; ?>" />
            </div>

            <div style="text-align: center;margin-top: 20px">
                <b>Court/Registration Order Date</b>
                <input type="text" name="txt_order_dt" id="txt_order_dt" class="dtp" maxlength="10" size="9" <?= ($order_date != '') ? 'value="' . $order_date . '" disabled' : ''; ?> />
            </div>

            <?php if ($is_old_registration == 'Y'): ?>
                <div style="text-align: center;margin-top: 20px">
                    <b>Enter Year for which registration is to be done:</b>
                    <select id="previous_year">
                        <option value="">select</option>
                        <?php for ($i = date('Y') - 1; $i >= 1950; $i--): ?>
                            <option value="<?= $i; ?>"><?= $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            <?php endif; ?>

            <?php if ($order_date != ''): ?>
                <div style="text-align: center;margin-top: 20px">
                    <font style="color: darkgreen;font-weight: bold">
                        Note: If actual order date doesn't match with the date displayed above, Please update Previous Court Remarks in the actual Order date.
                    </font>
                </div>
            <?php endif; ?>

            <div style="text-align: center;margin-top: 20px">
                <input type="button" name="btn_generate_s" id="btn_generate_s" value="Generate" />
            </div>
            <div id="dv_load"></div>


            <?php
            /* $year=date('Y');
               $ucode=$_SESSION['dcmis_user_idd'];
              $max_case_ct="Select knt from kounter where year='$year' and casetype_id='$res_p_r[casetype_id]'";
             $max_case_ct=mysql_query($max_case_ct) or die("Error: ".__LINE__.mysql_error());
             $res_case_ct=mysql_result($max_case_ct,0);
              $cnt_no=$res_case_ct+1;
             $upd_case_ct="Update kounter set knt='$cnt_no'  where year='$year' and casetype_id='$res_p_r[casetype_id]'";
                if(!mysql_query($upd_case_ct))
                {
                   die("Error: ".__LINE__.mysql_error());
                }
             else
             {

                  $hd_casetype_id=strlen($res_p_r[casetype_id]);

                    $app_zero_ct='';
                      if($hd_casetype_id<2)
                      {

                          for ($index = $hd_casetype_id; $index < 2; $index++) {
                              if($app_zero_ct=='')
                                  $app_zero_ct='0';
                              else
                                  $app_zero_ct=$app_zero_ct.'0';
                          }
                      }
                      $hd_casetype_id1=$app_zero_ct.$res_p_r[casetype_id];

                 $hd_res_case_ct=strlen($cnt_no);

                    $app_zero_cno='';
                      if($hd_res_case_ct<6)
                      {

                          for ($index = $hd_res_case_ct; $index < 6; $index++) {
                              if($app_zero_cno=='')
                                  $app_zero_cno='0';
                              else
                                  $app_zero_cno=$app_zero_cno.'0';
                          }
                      }

                        $hd_res_case_ct1=$app_zero_cno.$cnt_no;

                 $fil_no=$hd_casetype_id1.$hd_res_case_ct1.$year;
                  $f_no=substr($fil_no,2,6);
                       $reg_no= $hd_casetype_id1.'-'.$f_no;

                 $sel_cur_det="Select fil_no,year(fil_dt) fil_dt from main where diary_no='$dairy_no'";
            $sel_cur_det=mysql_query($sel_cur_det) or die("Error: ".__LINE__.mysql_error());
            $res_cur_det=  mysql_fetch_array($sel_cur_det);

            $pre_case_type=substr($res_cur_det[fil_no],0,2);

            $txt_order_dt='0000-00-00';
           echo $sql_sel_his="Select count(id ) from main_casetype_history where diary_no='$dairy_no' and
                    old_registration_number='$res_cur_det[fil_no]' and old_registration_year='$res_cur_det[fil_dt]'
                    and new_registration_number='$reg_no' and new_registration_year='$year'
                    and order_date='$txt_order_dt'";
            $sql_sel_his=mysql_query($sql_sel_his)or die("Error: ".__LINE__.mysql_error());
            $res_sel_his=mysql_result($sql_sel_his,0);
            if($res_sel_his<=0)
            {
           echo  $upd_his="Insert Into main_casetype_history (diary_no,old_registration_number,old_registration_year,
                       new_registration_number,new_registration_year,order_date,ref_old_case_type_id,
                       ref_new_case_type_id,adm_updated_by,updated_on,is_deleted) values
                       ('$dairy_no','$res_cur_det[fil_no]','$res_cur_det[fil_dt]','$reg_no','$year','$txt_order_dt',
                       '$pre_case_type','$res_p_r[casetype_id]','$ucode',now(),'f' )";
           if(!mysql_query($upd_his))
           {
               die("Error: ".__LINE__.mysql_error());
           }
            }



                 $ins_sql="Insert into registered_cases (diary_no,fil_no,entuser,entdt,casetype_id,case_no,case_year) values
                          ('$dairy_no','$fil_no','$ucode',now(),
                              '$res_p_r[casetype_id]','$cnt_no','$year')";
                      $ins_sql=mysql_query($ins_sql) or die("Error: ".__LINE__.mysql_error());

           $upd_main="Update main set fil_no='$reg_no',fil_dt=now(),usercode='$ucode',mf_active='M',active_fil_no='$reg_no',
                   active_fil_dt=now(),active_reg_year='$year',active_casetype_id='$res_p_r[casetype_id]' where diary_no='$dairy_no'";
             $upd_main=mysql_query($upd_main) or die("Error: ".__LINE__.mysql_error());




             $skey="Select short_description from casetype where casecode='$res_p_r[casetype_id]' and display='Y'";
             $skey=mysql_query($skey) or die("Error: ".__LINE__.mysql_error());
             $res_skey=mysql_result($skey,0);
             ?>
        <div style="text-align: center"><h3><?php

                echo "Registration No.: ".$res_skey.'-'.$f_no."/".$year ;
           ?></h3></div>
        <?php
             }*/
            ?>
        <?php endif; ?>
    <?php endif; ?>
    <?php
} else {
    $lowerCourtDetails = $modelIA->getLowerCourtDetails($dairy_no, $res_p_r);

    if (count($lowerCourtDetails) > 0) {
        $res_ck_def = $modelIA->checkDefects($dairy_no);
        if ($res_ck_def) {
    ?>
            <div style="text-align: center"><b>Please remove defects before generating Registration No.</b></div>
        <?php
        } else {
        ?>
            <div style="text-align: center">
                <h3><b><?php echo $modelIA->get_causetitle($dairy_no); ?></b></h3>
                <input type="hidden" name="hd_casetype_id" id="hd_casetype_id" value="<?php echo $res_p_r['casetype_id']; ?>" />
            </div>
            <table class="table_tr_th_w_clr c_vertical_align" cellpadding="5" cellspacing="5" width="100%">
                <tr>
                    <th>
                        S.No.
                    </th>
                    <th>
                        Court
                    </th>
                    <th>
                        State
                    </th>
                    <th>
                        Bench
                    </th>
                    <th>
                        Case No.
                    </th>
                    <th>
                        Order Date
                    </th>
                    <th>
                        <?php
                        if ($res_p_r['casetype_id'] == '7' || $res_p_r['casetype_id'] == '8') {
                        ?>
                            Transfer To
                        <?php
                        } else {
                        ?>
                            Judgement Challenged
                        <?php } ?>
                    </th>
                    <!--        <th>
                                        Generate
                                    </th>-->
                </tr>

                <?php
                $sno = 0;
                $countchallange = 0;
                foreach ($lowerCourtDetails as $row) {
                ?>
                    <tr>
                        <td>
                            <?php echo $sno + 1; ?>
                        </td>
                        <td>
                            <?php
                            if ($row['ct_code'] == '1')
                                echo "High Court";
                            else if ($row['ct_code'] == '2')
                                echo "Other";
                            else if ($row['ct_code'] == '3')
                                echo "District Court";
                            else if ($row['ct_code'] == '4')
                                echo "Supreme Court";
                            else if ($row['ct_code'] == '5')
                                echo "State Agency";
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $row['Name'];
                            ?>
                            <input type="hidden" name="hd_lower_id<?php echo $sno; ?>" id="hd_lower_id<?php echo $sno; ?>" value="<?php echo $row['lower_court_id'];  ?>" />
                        </td>
                        <td>
                            <?php
                            echo $row['agency_name'];
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $row['type_sname'] . '-' . $row['lct_caseno'] . '-' . $row['lct_caseyear'];
                            ?>
                        </td>
                        <td>
                            <span id="sp_lct_dec_dt<?php echo $sno; ?>"><?php if ($row['lct_dec_dt'] == '0000-00-00') {
                                                                            echo '-';
                                                                        } else {
                                                                            echo date('d-m-Y', strtotime($row['lct_dec_dt']));
                                                                        } ?></span>
                        </td>
                        <td>
                            <?php

                            if ($res_p_r['casetype_id'] == '7' || $res_p_r['casetype_id'] == '8') {

                                if ($row['transfer_court'] != 0) {
                                    $r_court = $modelIA->getCourtName($row['transfer_court']);
                                    $res_court = $r_court['court_name'];

                                    $r_state = $modelIA->getStateName($row['transfer_state']);
                                    $res_state = $r_state['Name'];

                                    $r_district = $modelIA->getStateName($row['transfer_court'], $row['transfer_district']);
                                    $res_district = $row['transfer_court'] == '3' ? $r_district['Name'] : $r_district['agency_name'];
                                    $case_type = '';

                                    $case_type = $modelIA->get_case_type($row['transfer_court'], $row['transfer_case_type']);
                                    $r_case_type = $r_state['skey'];
                                }
                            ?>
                                <span class="cl_chk_jug_clnged" id="chk_jug_clnged<?php echo $sno; ?>">
                                    <?php if ($row['transfer_court'] == 0) {
                                        echo "-";
                                    } else {
                                        echo $res_court;
                                    } ?> /<?php if ($row['transfer_state'] == 0) {
                                                echo '-';
                                            } else {
                                                echo $res_state;
                                            } ?> / <?php if ($row['transfer_district'] == 0) {
                                                        echo '-';
                                                    } else {
                                                        echo $res_district;
                                                    } ?></span>

                                <?php if ($row['is_order_challenged'] == 'Y') {
                                    $countchallange++; ?>
                                <?php } ?>
                            <?php
                            } else {
                            ?>

                                <input type="checkbox" name="chk_jug_clnged<?php echo $sno; ?>" id="chk_jug_clnged<?php echo $sno; ?> "
                                    <?php if ($row['is_order_challenged'] == 'Y') {
                                        $countchallange++; ?> checked="checked"
                                    <?php } ?> class="cl_chk_jug_clnged" disabled />
                            <?php } ?>
                        </td>
                        <!--        <td>
            <input type="button" name="btn_generate<?php echo $sno; ?>" id="btn_generate<?php echo $sno; ?>" value="Generate" class="cl_generate"/>
        </td>-->
                    </tr>
                <?php
                    $sno++;
                }
                ?>
            </table>
            <div style="text-align: center;margin-top: 20px">

                <?php
                //check if IA is pending
                $ia_pending = '0';
                $check_ia = $modelIA->checkIA($dairy_no, $res_p_r['casetype_id']);

                if (count($check_ia) > 0) {
                    $ia_details = '';
                    foreach ($check_ia as $row1) {
                        $others = '';
                        if ($row1['other1'] != '')
                            $others = '- ' . $row1['other1'];
                        if ($ia_details == '')
                            $ia_details = $row1['docnum'] . '/' . $row1['docyear'] . '- ' . $row1['docdesc'] . $others;
                        else
                            $ia_details = $ia_details . ', ' . $row1['docnum'] . '/' . $row1['docyear'] . '- ' . $row1['docdesc'] . $others;
                    }
                ?>
                    <div class="cl_center"><b>Can't register case because IA(s) <span style="color:red"><?php echo $ia_details  ?></span> is still pending.</b></div>
                <?php

                    $ia_pending = 1;
                }
                if ($ia_pending == 1)
                    exit(0);

                ?>



                <?php if ($countchallange <= 0) {
                    echo "<font style='color: red;font-weight: bold'>No. Previous Court details are challenged.Please Check Previous Court Details !!!!</font>";
                    exit();
                ?>

                <?php  } else {

                ?>
                    <b>
                        <font style="color: red;font-weight: bold">Total registration no. to be generated
                            <?php if ($countchallange <= 1) echo "is";
                            else echo "are"; ?> &nbsp;<?php echo $countchallange; ?>&nbsp;?</font>&nbsp;&nbsp;
                        <input type='checkbox' name='regnocount' id='regnocount' value='$totalnogen' required>
                    </b>
                <?php } ?>



            </div>
            <div style="text-align: center;margin-top: 20px">
                <b>Court/Registration Order Date</b> <input type="text" name="txt_order_dt" id="txt_order_dt" class="dtp" maxlength="10" size="9" <?php if ($order_date != '') { ?>value="<?php echo $order_date; ?> " disabled <?php } ?> />
            </div>
            <?php if ($is_old_registration == 'Y') { ?>
                <div style="text-align: center;margin-top: 20px">
                    <b>Enter Year for which registration is to be done:</b>
                    <?php
                    $currently_selected = date('Y');
                    $earliest_year = 1950;
                    $latest_year = date('Y') - 1;
                    print '<select id="previous_year">';
                    print '<option value="">select</option>';
                    foreach (range($latest_year, $earliest_year) as $i) {

                        print '<option value="' . $i . '">' . $i . '</option>';
                    }
                    print '</select>'; ?>

                </div>
            <?php } ?>
            <?php if ($order_date != '') { ?>
                <div style="text-align: center;margin-top: 20px">
                    <font style="color: darkgreen;font-weight: bold">
                        Note: If actual order date doesn't match with the date displayed above, Please update Previous Court Remarks in the actual Order date.
                    </font>
                </div>
            <?php } ?>
            <div style="text-align: center;margin-top: 20px">


                <input type="button" name="btn_generate_r" id="btn_generate_r" value="Generate" />
            </div>
            <div id="dv_load"></div>
        <?php
            // } IA
        }
    } else if (($res_p_r['casetype_id'] == '19') || ($res_p_r['casetype_id'] == '20')) {

        ?><br>
        <div style="text-align: center"><b>In case of Criminal Contempt under Section 2(C) of Contempt of Courts Act 1971 <br>
                <center> OR</center> In case of Criminal Contempt, on Certificate of Atorney General or Solicitor General
            </b> <input type='checkbox' id='direct' onClick="f()">
        </div>

        <div id="direct_contempt" style="text-align: center;margin-top: 20px">
            <input size="1" value='1' type="hidden" name="num" id="num">

            <b>Court/Registration Order Date</b> <input type="text" name="txt_order_dt" id="txt_order_dt" class="dtp" maxlength="10" size="9" <?php if ($order_date != '') { ?>value="<?php echo $order_date; ?> " disabled <?php } ?> />
        </div>
        <?php if ($is_old_registration == 'Y') { ?>
            <div style="text-align: center;margin-top: 20px">
                <b>Enter Year for which registration is to be done:</b>
                <?php
                $currently_selected = date('Y');
                $earliest_year = 1950;
                $latest_year = date('Y') - 1;
                print '<select id="previous_year">';
                print '<option value="">select</option>';
                foreach (range($latest_year, $earliest_year) as $i) {

                    print '<option value="' . $i . '">' . $i . '</option>';
                }
                print '</select>'; ?>

            </div>
        <?php } ?>

        <div style="text-align: center;margin-top: 20px"> <input type="button" name="btn_generate" id="btn_generate" value="Generate" />
        </div>
        <div id="dv_load"></div>
    <?php
    } else {
    ?>
        <div style="text-align: center"><b>No. Previous Court details are challenged!</b></div>
<?php

    }
}
?>