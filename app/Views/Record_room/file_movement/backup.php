
        <hr>
        <!--for da case allotment exclusing hall allotment start-->
        <?php

        $rkds_blk = "none";
        if ($currentUser['isda'] == 'Y' && $user_stat == 1)
            $rkds_blk = "block";
        ?>
        <div id="rkds_block" style="overflow: hidden;margin-left: auto;margin-right: auto;margin-top: 20px;margin-bottom: 20px;
             display: <?php echo $rkds_blk; ?>">
            <div style="margin: 0 auto;overflow: hidden">
                <div style="font-size: 18px;text-align: center">D.A. Cases Range Allotment </div>
                <br>
                <div style="margin: 0 auto;overflow: hidden">
                    <div style="float:left;margin-left: 20px">
                        <?php
                        $c_casetype = $model->getCaseType();
                        ?>
                        Case Type
                        <select style="display: block;width: auto;" id="r_csty">
                            <option value="">Select</option>
                            <?php
                            foreach ($c_casetype as $row_c) {
                            ?>
                                <option value="<?php echo $row_c['casecode']; ?>"><?php echo $row_c['short_description']; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div style="float:left;margin-left: 10px">
                        From No <input id="r_frmno" type="text" style="display: block;width: auto;" />
                    </div>
                    <div style="float:left;margin-left: 10px">
                        From Year
                        <select style="width: auto" id="r_frmyr">
                            <option value="0000">0000</option>
                            <?php
                            for ($i = 2018; $i >= 1956; $i--) {
                            ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div style="float:left;margin-left: 15px">
                        To No <input id="r_tono" type="text" style="display: block;width: auto;" />
                    </div>
                    <div style="float:left;margin-left: 10px">
                        To Year
                        <select style="width: 150px" id="r_toyr">
                            <option value="0000">0000</option>
                            <?php
                            for ($i = 2018; $i >= 1956; $i--) {
                            ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div style="float:left;margin-left: 10px">
                        <?php
                        $state = $model->getState();
                        ?>
                        <select style="display: block;width: 90px;" id="state">
                            <option value="">Select</option>
                            <option value="0">ALL</option>
                            <?php
                            foreach ($state as $row_s) {
                            ?>
                                <option value="<?php echo $row_s['id_no']; ?>"><?php echo $row_s['Name']; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div style="float:left;margin-left: 10px">
                        Type
                        <select style="display: block;width: 90px;" id="da_c_t">
                            <option value="">Select</option>
                            <option value="M">Miscellaneous</option>
                            <option value="F">Regular</option>
                        </select>
                    </div>
                </div>
                <div style="overflow: hidden;margin: 0 auto;font-size: 16px;float: left;margin: 7px 10px 0px 20px">
                    <input type="button" name="add-da-for" value="Add->" /><!--onclick="press_add_rkds()"-->
                </div>
                <div id="r_type_area" style="margin: 0 auto;overflow: hidden;font-size: 14px;float: left;margin: 2px 0px 0px 20px; border: 0px solid black;text-align: center">
                    <?php

                    $currentYear = date("Y");
                    $chk_case = $model->checkCaseDistribution($userid);
                    if (count($chk_case) > 0) {
                        foreach ($chk_case as $row_chk) {

                            $one_rkd_case = $row_chk['casetype'] . '_' . $row_chk['case_from'] . '_' . $row_chk['caseyear_from'] . '_' . $row_chk['case_to'] . '_' . $row_chk['caseyear_to'];
                    ?>
                            <div class="cl_chk_case_da" id="r_r_<?php echo $one_rkd_case; ?>">
                                <?php
                                if ($row_chk['case_from'] == 1 and $row_chk['case_to'] == 555555  and $row_chk['caseyear_from'] == 1950 and $row_chk['caseyear_to'] == $currentYear) {
                                    echo $row_chk['short_description'] . 'ALL';
                                } else {
                                    echo $row_chk['short_description'] . '-' . $row_chk['case_from'] . '-' . $row_chk['caseyear_from'] . '-' . $row_chk['case_to'] . '-' . $row_chk['caseyear_to'];
                                }
                                echo '-' . $type_de_case; ?>
                                <img style='width:7px;height:7px;margin-top:0px;margin-bottom:4px;cursor:pointer' src='<?= base_url('images/close_btn.png');?>'
                                    onclick="removeCase_rkhall('<?php echo $one_rkd_case; ?>')">
                                    <!-- /*if($row_chk['bailno']>'0') echo '-'.$row_chk['bailno'];*/ -->
                            </div>
                    <?php
                            @$hidden_rkds_case .= ',' . $one_rkd_case;
                        }
                    }


                    ?>
                </div>
                <input type="hidden" id="rkds_cases" value="<?php echo @$hidden_rkds_case; ?>" />
            </div>
            <div style="text-align: center;margin: 0 auto;overflow: hidden;display: block;margin-top: 20px;">
                <input type="button" name="al-rkd-case" value="ALLOT CASE" />
            </div>
        </div>
        <!--for da case allotment exclusing hall allotment end-->

        <?php
        $rkdcmpda_blk = "none";
        /*if($currentUser['usertype'] == 61 && $user_stat == 1)
            $rkdcmpda_blk = "block";*/
        ?>
        <div id="rkdcmpda_block" style="margin: 0 auto; overflow: hidden;margin-left: 30px;padding: 10px 10px 10px 10px;background-color: #819FF7;margin-top: 20px;margin-bottom: 20px;
             display: <?php echo $rkdcmpda_blk; ?>">
            <div style="margin: 0 auto;overflow: hidden">
                <div style="font-size: 18px;text-align: center">Compliance D.A. Nature Allotment</div>
                <div style="overflow: hidden;margin: 0 auto;font-size: 16px;float: left;margin: 0px 10px 0px 20px; border: 0px solid black">
                    <label>Nature</label>
                    <?php
                    $c_casetype = $model->get_c_casetype();
                    ?>
                    <select style="display: block;width: 90px;" id="cmp_csty">
                        <option value="">Select</option>
                        <?php
                        foreach ($c_casetype as $row_c) {
                        ?>
                            <option value="<?php echo $row_c['casecode']; ?>"><?php echo $row_c['skey']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div style="overflow: hidden;margin: 0 auto;font-size: 16px;float: left;margin: 7px 10px 0px 20px">
                    <input type="button" value="Add->" onclick="press_add_rkdcmpda()" />
                </div>
                <div id="cmp_type_area" style="margin: 0 auto;overflow: hidden;font-size: 14px;float: left;margin: 2px 0px 0px 20px; border: 0px solid black;text-align: center">
                    <?php
                    /*$chk_case = "SELECT a.casecode,bailno,IF(bailno = '0',skey,IF(a.casecode='52',IF(bailno='2',CONCAT(skey,' > ','1'),skey),skey))skey 
                                FROM chk_case a LEFT JOIN casetype b ON a.casecode = b.casecode WHERE chkcode = '$userid' AND a.display = 'Y'";*/
                    $chk_case = $model->get_rkdcmpda_case($userid);
                    if (count($chk_case) > 0) {
                        foreach ($chk_case as $row_chk) {
                    ?>
                            <div class="cl_chk_case" id="cmp_cmp_<?php echo $row_chk['nature']; /*if($row_chk['bailno']>'0') echo '-'.$row_chk['bailno'];*/ ?>"><?php echo $row_chk['skey']; ?>
                                <img style='width:7px;height:7px;margin-top:0px;margin-bottom:4px;cursor:pointer' src='usermgmt/close-button.gif'
                                    onclick="removeCase_rkdcmpda('<?php echo $row_chk['nature']; /*if($row_chk['bailno']>'0') echo '-'.$row_chk['bailno'];*/ ?>')">
                            </div>
                    <?php
                            $hidden_rkdcmpda_case .= ',' . $row_chk['nature'];
                            /*if($row_chk['bailno']>'0') 
                            $hidden_chk_case .= '~'.$row_chk['bailno'];*/
                        }
                    }
                    ?>
                </div>
                <input type="hidden" id="rkdcmpda_cases" value="<?php echo $hidden_rkdcmpda_case; ?>" />
            </div>
            <div style="text-align: center;margin: 0 auto;overflow: hidden;display: block;margin-top: 20px;">
                <input type="button" value="ALLOT CASE" onclick="allotCase_rkdcmpda()" />
            </div>
        </div>
        <?php
        $jud_blk = "none";
        /*if(($currentUser['dept_name'] == 'COURT' || $currentUser['dept_name'] == 'PAPS' ) && ($user_stat == 1) && ($currentUser['name'] != ''))
            $jud_blk = "block";*/
        ?>
        <div id="judge_block" style="margin: 0 auto; overflow: hidden;margin-left: 30px;padding: 10px 10px 10px 10px;background-color: #ffa186;margin-top: 20px;margin-bottom: 20px;
            display: <?php echo $jud_blk; ?>">
            <div style="margin: 0 auto;overflow: hidden">
                <div style="font-size: 18px;text-align: center;margin-bottom: 5px;">Judge Allotment For <?php echo $userdept; ?> Department</div>
                <div style="overflow: hidden;margin: 0 auto;font-size: 16px;margin: 0px 10px 0px 20px; border: 0px solid black">
                    <?php
                    $cuirrent_working_judge = $model->getJudge();
                    $if_available_rs = $model->getUsersJcode($userid);
                    $if_available = $if_available_rs['jcode'];
                    ?>
                    <select style="display: block;width: 400px; float: left" id="judge_for_user">
                        <option value="0">Select</option>
                        <?php
                        foreach ($cuirrent_working_judge as $row_jud) {
                        ?>
                            <option value="<?php echo $row_jud['jcode']; ?>"
                                <?php if ($if_available != 0) {
                                    if ($if_available == $row_jud['jcode']) echo "selected";
                                }  ?>><?php echo $row_jud['jname']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <input type="button" onclick="save_judge_info()" style="margin-left: 30px;" value="SAVE JUDGE" />
                </div>
            </div>
        </div>