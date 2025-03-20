<?php

$db = \Config\Database::connect();
if (empty($main_row_details)) {
?>
    <table align="center">
        <tr align="center">
            <th>Record Not Found!!!</th>
        </tr>
    </table>
<?php
} else {
    $main_row = $main_row_details;
    //pr($main_row_details);
    foreach ($main_row_details as $int_row) {
        //pr($int_row);
        $main_row['c_status'] = $int_row['c_status'];
        $main_row['q'][] = $int_row['q'];
    }

    // $pnc = explode(',', ltrim($main_row['q'],','));
    $pnc = $main_row['q'];

?>
    <div style="text-align:center;font-size: 15px;color: #ff5d4c;font-weight: bold;display: none" id="suc_msg">Record Updated Successfully!!!</div>
    <div style="color:blue;text-align: center;font-weight: bold">
        <?php
        echo "Case No.-";
        /* $casetype = "SELECT fil_no,fil_dt,fil_no_fh,fil_dt_fh,short_description,IF(reg_year_mh=0,YEAR(a.fil_dt),reg_year_mh) m_year,
        IF(reg_year_fh=0,YEAR(a.fil_dt_fh),reg_year_fh) f_year,pet_name,res_name,pno,rno FROM main a LEFT JOIN casetype b ON SUBSTR(fil_no,1,2)=casecode WHERE diary_no='$_REQUEST[dno]'";
            $casetype = mysql_query($casetype) or die(__LINE__.'->'.mysql_query());
            $casetype = mysql_fetch_array($casetype); */

        $casetype = $dModel->getCaseTypeData($diary_no);

        if ($casetype['fil_no'] != '' || $casetype['fil_no'] != NULL) {
            echo '[M]' . $casetype['short_description'] . SUBSTR($casetype['fil_no'], 3) . '/' . $casetype['m_year'];
        }

        if ($casetype['fil_no_fh'] != '' || $casetype['fil_no_fh'] != NULL) {
            $qr = "SELECT short_description FROM master.casetype WHERE casecode=" . SUBSTR($casetype['fil_no_fh'], 0, 2);
            // $r_case = mysql_query($r_case) or die(__LINE__.'->'.mysql_query());
            // $r_case = mysql_fetch_array($r_case);
            $query = $db->query($qr);
            $r_case = $query->getRowArray();
             $short_description =    $r_case['short_description'] ?? '';
            echo ',[R]' . $short_description . SUBSTR($casetype['fil_no_fh'], 3) . '/' . $casetype['f_year'];
        }
        
        echo ", Diary No: " . substr(session()->get('filing_details')['diary_no'],0,-4). '/' . substr(session()->get('filing_details')['diary_no'],-4);
        echo "<br>" . $casetype['pet_name'];
        if ($casetype['pno'] == 2) echo " <span style='color:#72bcd4'>AND ANR</span>";
        else if ($casetype['pno'] > 2) echo " <span style='color:#72bcd4'>AND ORS</span>";
        echo "<font style=color:black>&nbsp; Versus &nbsp;</font>";
        echo $casetype['res_name'];
        if ($casetype['rno'] == 2) echo " <span style='color:#72bcd4'>AND ANR</span>";
        else if ($casetype['rno'] > 2) echo " <span style='color:#72bcd4'>AND ORS</span>";
        ?>
    </div>
    <table align="center">
        <?php
        if ($main_row['c_status'] == 'D') {
        ?>
            <tr>
                <th style="color:red;">!!!The Case is Disposed!!!</th>
            </tr>
        <?php
            exit();
        }
        ?>
    </table>
    <table border="1" class="table table-bordered table-striped custom-table th-no-radius">
        <?php
        $i = 1;


        $rs = $dModel->getAdvocateData($diary_no);
        
        if (!empty($rs)) {
        ?>
        <thead>
            <tr>
                <th style="text-align: center!important;" colspan="11">Petitioner</th>
            </tr>
            <tr>
                <th>Petitioner Name</th><!--<th>State</th><th>Enroll No.</th><th>Enroll Year</th>-->
                <th>Category</th>
                <th>Advocate Details</th>
                <th>Advocate Name</th>
                <!--<th>Mobile</th><th>Email</th>-->
                <th>Type</th>
                <th>If [AG]</th>
                <th>STATE ADV[Pri/Gov]</th>
                <th></th>
            </tr>
        </thead>
            <?php
        }
        if (!empty($rs)) {
            foreach ($rs as $row) {
               
            ?>
                <input type="hidden" id="adv_pet_res<?php echo $i ?>" value="P" />
                <input type="hidden" id="r_no<?php echo $i ?>" value="<?php echo $row['r_no'] ?>" />
                <input type="hidden" value="<?php if ($row['pet_res_no'] != '') echo $row['pet_res_no'];
                                            else echo '0'; ?>" id="adv_p_no_hd<?php echo $i; ?>" />
                <tr id="row<?php echo $i; ?>" <?php if ($row['adv_type'] == 'M') {
                                                    echo "style=background-color: e2d8d3";
                                                } ?>>
                    <td style="border:none">
                        <span id="adv_p_no<?php echo $i; ?>" value="<?php echo $row['pet_res_no']; ?>" style="color: blue"><?php echo $row['pet_res_no']; ?></span>


                        <span><?php echo '-' . $row['partyname']; ?></span>
                    </td>
                    <td><select disabled="" id="adv_cat<?php echo $i; ?>" class="form-control">
                            <option <?php if ($row['adv_type'] == 'M') echo "selected"; ?>>Main</option>
                            <option <?php if ($row['adv_type'] == 'A') echo "selected"; ?>>Additional</option>
                        </select>
                        <input type="hidden" value="<?php echo $row['adv_type']; ?>" id="adv_type_hd<?php echo $i; ?>" />
                    </td>
                    <td style="border:none">
                        <select id="sel_adv_src<?php echo $i; ?>" class="form-control" onchange="activeAdvSrc(<?php echo $i; ?>,this.value)">
                            <option value="A" <?php if ($row['aor_state'] == 'A') echo "selected"; ?>>A</option>
                            <option value="S" <?php if ($row['aor_state'] == 'S') echo "selected"; ?>>S</option>
                        </select>
                        <input type="hidden" value="<?php echo $row['aor_state']; ?>" id="sel_adv_src_hd<?php echo $i; ?>" />
                        <?php
                        if ($row['aor_state'] == 'S') {
                        ?>
                            <span id="span_aor<?php echo $i; ?>" <?php if ($row['aor_state'] == 'S') { ?>style="display:none" <?php } ?>>
                                <input type="text" maxlength="6" class="form-control" size="4" id="adv_aor<?php echo $i; ?>" onkeypress="return onlynumbers(event,this.id)" onblur="getAdvocateAOR(<?php echo $i; ?>)" />
                                <input type="hidden" id="adv_aor_hd<?php echo $i; ?>" />
                            </span>
                            <span id="span_state<?php echo $i; ?>" <?php if ($row['aor_state'] == 'A') { ?>style="display: none" <?php } ?>>
                                <?php

                                $state = $dModel->getStateData(); ?>
                                <select id="adv_state<?php echo $i; ?>" onblur="getAdvocate(<?php echo $i; ?>)">
                                    <option value="">Select</option>
                                    <?php
                                    foreach ($state as $state_row) {
                                    ?>
                                        <option value="<?php echo $state_row['id_no']; ?>" <?php if ($row['state_id'] == $state_row['id_no']) echo "selected"; ?>><?php echo $state_row['Name']; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <input type="text" value="<?php echo $row['enroll_no'] ?>"  size="5" id="adv_no<?php echo $i; ?>" onkeypress="return onlynumbersadv(event,this.id)" onblur="getAdvocate(<?php echo $i; ?>)" />
                                <input type="text" value="<?php echo $row['enroll_yr'] ?>" maxlength="4" size="4" id="adv_yr<?php echo $i; ?>" onkeypress="return onlynumbers(event,this.id)" onblur="getAdvocate(<?php echo $i; ?>)" />
                                <input type="hidden" value="<?php echo $row['state_id'] ?>" id="adv_state_hd<?php echo $i; ?>" />
                                <input type="hidden" value="<?php echo $row['enroll_no'] ?>" id="adv_no_hd<?php echo $i; ?>" />
                                <input type="hidden" value="<?php echo $row['enroll_yr'] ?>" id="adv_yr_hd<?php echo $i; ?>" />
                            </span>
                        <?php
                        } else if ($row['aor_state'] == 'A') {
                        ?>
                            <span id="span_aor<?php echo $i; ?>" <?php if ($row['aor_state'] == 'S') { ?>style="display:none" <?php } ?>>
                                <input class="form-control" type="text" <?php if ($row['advocate_id'] == 0) { ?> style="display: none" <?php } ?> value="<?php echo $row['aor_code'] ?>" maxlength="6" size="4" id="adv_aor<?php echo $i; ?>" onkeypress="return onlynumbers(event,this.id)" onblur="getAdvocateAOR(<?php echo $i; ?>)" />
                                <input type="hidden" value="<?php echo $row['aor_code'] ?>" id="adv_aor_hd<?php echo $i; ?>" />
                            </span>
                            <span id="span_state<?php echo $i; ?>" <?php if ($row['aor_state'] == 'A') { ?>style="display: none" <?php } ?>>
                                <?php

                                ?>
                                <select class="form-control" id="adv_state<?php echo $i; ?>" onblur="getAdvocate(<?php echo $i; ?>)">
                                    <option value="">Select</option>
                                    <?php
                                    $state = $dModel->getStateData();
                                    foreach ($state as $state_row) {
                                    ?>
                                        <option value="<?php echo $state_row['id_no']; ?>"><?php echo $state_row['Name']; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <input class="form-control" type="text"  size="5" id="adv_no<?php echo $i; ?>" onkeypress="return onlynumbersadv(event,this.id)" onblur="getAdvocate(<?php echo $i; ?>)" />
                                <input class="form-control" type="text" maxlength="4" size="4" id="adv_yr<?php echo $i; ?>" onkeypress="return onlynumbers(event,this.id)" onblur="getAdvocate(<?php echo $i; ?>)" />
                                <input type="hidden" id="adv_state_hd<?php echo $i; ?>" />
                                <input type="hidden" id="adv_no_hd<?php echo $i; ?>" />
                                <input type="hidden" id="adv_yr_hd<?php echo $i; ?>" />
                            </span>
                        <?php
                        }
                        ?>
                    </td>
                    <td style="border:none"><input type="hidden" value="<?php echo $row['name'] . $row['adv'] ?>" id="adv_name_hd<?php echo $i; ?>" />
                        <?php
                        $write = 'N';

                        if ($row['advocate_id'] != 0) {
                        ?>
                            <span id="adv_name<?php echo $i; ?>" <?php if ($write == 'Y') echo "style='display:none'"; ?>><?php echo $row['name'] . $row['adv']; ?></span>
                            <input type="text" id="adv_name_write<?php echo $i; ?>" style="display:<?php if ($write == 'Y') echo 'block';
                                                                                                    else if ($write == 'N') echo 'none'; ?>;text-transform:uppercase;width:200px;" onblur="copyToSpan(<?php echo $i; ?>)" value="<?php echo $row['adv'] ?>" />
                            <br><span id="p_span_inperson<?php echo $i; ?>" <?php /*if($row['inperson_email']=='' && $row['inperson_email']=='')*/ if (in_array($row['advocate_id'], array(584, 666, 940))) {
                                                                                echo 'style="display:block"';
                                                                            } else {
                                                                                echo 'style="display:none"';
                                                                            } ?>>
                                <input type="text" maxlength="10" size="10" id="p_inperson_mob<?php echo $i; ?>" placeholder="mobile no." onkeypress="return onlynumbers(event,this.id)" value="<?php echo $row['inperson_mobile'] ?>" />
                                <br> <input type="text" id="p_inperson_email<?php echo $i; ?>" placeholder="email id" value="<?php echo $row['inperson_email'] ?>" style="width:200px" />
                            </span>
                    </td>

                <?php } else { ?>
                    <span id="adv_name<?php echo $i; ?>" style='display:none'><?php echo $row['name'] . $row['adv']; ?></span>
                    <input type="text" id="adv_name_write<?php echo $i; ?>" onkeypress="return advName(event)" style="display: block; text-transform: uppercase;width: 200px;" onblur="copyToSpan(<?php echo $i; ?>)" value="<?php echo $row['name'] . $row['adv'] ?>" /></td>
                <?php } ?>

                <?php
                $having_ag = 0;
                if ($row['advocate_id'] == 0) {
                    $type =  (!empty($row['adv'])) ? explode('[', rtrim($row['adv'], ']')) : '';
                } else {
                    $type = (!empty($row['adv'])) ? explode('[', rtrim($row['adv'], ']')) : '';

                    //--
                    /*if($row['adv_code']==12)
                            print_r($type);*/
                    //--
                    if (!empty($type)) {
                        for ($kk = 0; $kk < sizeof($type); $kk++) {
                            if ($type[$kk] == 'AG')
                                $having_ag = 1;
                        }
                    }

                    if (!empty($type[2]) && $row['pet_res_no'] != 0)
                        $type[1] = $type[2] ?? '';

                    if (!empty($type[2]) && $type[2] == 'LR/S')
                        $type[1] = $type[2] ?? '';
                }
                ?>
                <td style="border:none"><select id="adv_type<?php echo $i; ?>" class="form-control" <?php if ($row['advocate_id'] == 0) { ?> style="display: none" <?php } ?>>
                        <option value='N' <?php if (!empty($type[1]) && $type[1] == 'N') { ?>selected <?php } ?>>None</option>
                        <!--<option value='OBJ' <?php if (!empty($type[1]) && $type[1] == 'OBJ') { ?>selected <?php } ?>>OBJ</option>-->
                        <option value='SURETY' <?php if (!empty($type[1]) && $type[1] == 'SURETY') { ?>selected <?php } ?>>SURETY</option>
                        <option value='INT' <?php if (!empty($type[1]) && $type[1] == 'INT') { ?>selected <?php } ?>>INTERVENOR</option>
                        <option value='LR/S' <?php if (!empty($type[1]) && $type[1] == 'LR/S') { ?>selected <?php } ?>>LR/S</option>
                        <!-- <option value='AMICUS CURIAE' <?php /*if($type[1]=='AMICUS CURIAE'){*/ ?>selected <?php /*}*/ ?>>AMICUS CURIAE</option>-->
                        <option value='DRW' <?php if (!empty($type[1]) && $type[1] == 'DRW') { ?>selected <?php } ?>>DRAWNBY</option>
                        <option value='SCLSC' <?php if (!empty($type[1]) && $type[1] == 'SCLSC') { ?>selected <?php } ?>>SCLSC</option>
                    </select></td>
                <td style="border:none"><select id='ifag<?php echo $i; ?>' class="form-control" <?php if ($row['advocate_id'] == 0) { ?> style="display: none" <?php } ?>>
                        <option value='N' <?php if ($having_ag == 0) echo "selected"; ?>>No</option>
                        <option value='AG' <?php if ($having_ag == 1) echo "selected"; ?>>ATTORNY GENERAL</option>
                    </select></td>
                <td style="border:none"><select id='statepg<?php echo $i; ?>' class="form-control">
                        <option value='N' <?php if ($row['stateadv'] == 'N') echo "selected"; ?>>No</option>
                        <option value='P' <?php if ($row['stateadv'] == 'P') echo "selected"; ?>>Private</option>
                        <option value='G' <?php if ($row['stateadv'] == 'G') echo "selected"; ?>>Government</option>
                    </select>
                    <input type='hidden' value='<?php echo $row['stateadv']; ?>' id='statepg_hd<?php echo $i; ?>' />
                </td>

                <td style="border:none">
                    <input type="button" name="button_update_<?php echo $i; ?>" class="btn btn-primary" value="Update" onclick="update_advocate(<?php echo $i; ?>);" />
                    <input type="button" name="button_delete_<?php echo $i; ?>" class="btn btn-warning" value="Delete" />
                    <span id="adv_span<?php echo $i; ?>" style="color: green;font-size: 12px;text-wrap: nowrap;"> </span>

                </td>
                <!--onclick="del_adv('<?php //echo $i;
                                        ?>')" -->
                </tr>
                <?php
                $i++;
            }
        }
     echo '</tbale>'; 
        $rs = $dModel->getAdvocateData_P($diary_no);
        if (!empty($rs)) { 
            //$i = 1;
            ?>

<table border="1" class="table table-bordered table-striped custom-table th-no-radius">
            <thead>
            <tr>
                <td colspan="11" style="border-left:0px;">&nbsp;</td>
            </tr>
            <tr>
                <th style="text-align: center!important;" colspan="11">Respondent</th>
            </tr>
            <tr>
                <th>Respondent Name</th><!--<th>State</th><th>Enroll No.</th><th>Enroll Year</th>-->
                <th>Category</th>
                <th>Advocate Details</th>
                <th>Advocate Name</th>
                <!--<th>Mobile</th><th>Email</th>-->
                <th>Type</th>
                <th>If [AG]</th>
                <th>STATE ADV[Pri/Gov]</th>
                <th></th>
            </tr>
            </thead>
            <?php

            foreach ($rs as $row) {

                if ($row['aor_state'] == 'S')
                    $row['aor_state'] = 'A';
                ?>
                <input type="hidden" id="adv_pet_res<?php echo $i ?>" value="R" />
                <input type="hidden" id="r_no<?php echo $i ?>" value="<?php echo $row['r_no'] ?>" />
                <input type="hidden" value="<?php if ($row['pet_res_no'] != '') echo $row['pet_res_no'];
                                            else echo '0'; ?>" id="adv_p_no_hd<?php echo $i; ?>" />
                <tr id="row<?php echo $i; ?>">
                    <td style="border:none">
                        <?php

                        ?>

                        <span id="adv_p_no<?php echo $i; ?>" value="<?php echo $row['pet_res_no']; ?>" style="color: blue"><?php echo $row['pet_res_no']; ?></span>

                        <span><?php echo '-' . $row['partyname']; ?></span>
                    </td>
                    <td><select class="form-control" disabled="" id="adv_cat<?php echo $i; ?>">
                            <option <?php if ($row['adv_type'] == 'M') echo "selected"; ?>>Main</option>
                            <option <?php if ($row['adv_type'] == 'A') echo "selected"; ?>>Additional</option>
                        </select>
                        <input type="hidden" value="<?php echo $row['adv_type']; ?>" id="adv_type_hd<?php echo $i; ?>" />
                    </td>
                    <td style="border:none">
                        <select class="form-control" id="sel_adv_src<?php echo $i; ?>" onchange="activeAdvSrc(<?php echo $i; ?>,this.value)">
                            <option value="A" <?php if ($row['aor_state'] == 'A') echo "selected"; ?>>A</option>
                            <option value="S" <?php if ($row['aor_state'] == 'S') echo "selected"; ?>>S</option>
                        </select>
                        <input type="hidden" value="<?php echo $row['aor_state']; ?>" id="sel_adv_src_hd<?php echo $i; ?>" />
                        <?php
                        if ($row['aor_state'] == 'S') {
                        ?>
                            <span id="span_aor<?php echo $i; ?>" <?php if ($row['aor_state'] == 'S') { ?>style="display:none" <?php } ?>>
                                <input type="text" maxlength="6" size="4" id="adv_aor<?php echo $i; ?>" onkeypress="return onlynumbers(event,this.id)" onblur="getAdvocateAOR(<?php echo $i; ?>)" />
                                <input type="hidden" id="adv_aor_hd<?php echo $i; ?>" />
                            </span>
                            <span id="span_state<?php echo $i; ?>" <?php if ($row['aor_state'] == 'A') { ?>style="display: none" <?php } ?>>

                                <select class="form-control" id="adv_state<?php echo $i; ?>" onblur="getAdvocate(<?php echo $i; ?>)">
                                    <option value="">Select</option>
                                    <?php
                                    $state = $dModel->getStateData();
                                    foreach ($state as $state_row) {
                                    ?>
                                        <option value="<?php echo $state_row['id_no']; ?>" <?php if ($row['state_id'] == $state_row['id_no']) echo "selected"; ?>><?php echo $state_row['Name']; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <input class="form-control" type="text" value="<?php echo $row['enroll_no'] ?>"  size="5" id="adv_no<?php echo $i; ?>" onkeypress="return onlynumbersadv(event,this.id)" onblur="getAdvocate(<?php echo $i; ?>)" />
                                <input class="form-control" type="text" value="<?php echo $row['enroll_yr'] ?>" maxlength="4" size="4" id="adv_yr<?php echo $i; ?>" onkeypress="return onlynumbers(event,this.id)" onblur="getAdvocate(<?php echo $i; ?>)" />
                                <input type="hidden" value="<?php echo $row['state_id'] ?>" id="adv_state_hd<?php echo $i; ?>" />
                                <input type="hidden" value="<?php echo $row['enroll_no'] ?>" id="adv_no_hd<?php echo $i; ?>" />
                                <input type="hidden" value="<?php echo $row['enroll_yr'] ?>" id="adv_yr_hd<?php echo $i; ?>" />
                            </span>
                        <?php
                        } else if ($row['aor_state'] == 'A') {
                        ?>
                            <span id="span_aor<?php echo $i; ?>" <?php if ($row['aor_state'] == 'S') { ?>style="display:none" <?php } ?>>
                                <input class="form-control" type="text" <?php if ($row['advocate_id'] == 0) { ?> style="display: none" <?php } ?> value="<?php echo $row['aor_code'] ?>" maxlength="6" size="4" id="adv_aor<?php echo $i; ?>" onkeypress="return onlynumbers(event,this.id)" onblur="getAdvocateAOR(<?php echo $i; ?>)" />
                                <input type="hidden" value="<?php echo $row['aor_code'] ?>" id="adv_aor_hd<?php echo $i; ?>" />
                            </span>
                            <span id="span_state<?php echo $i; ?>" <?php if ($row['aor_state'] == 'A') { ?>style="display: none" <?php } ?>>

                                <select class="form-control" id="adv_state<?php echo $i; ?>" onblur="getAdvocate(<?php echo $i; ?>)">
                                    <option value="">Select</option>
                                    <?php
                                    $state = $dModel->getStateData();
                                    foreach ($state as $state_row) {
                                    ?>
                                        <option value="<?php echo $state_row['id_no']; ?>"><?php echo $state_row['Name']; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <input class="form-control" type="text"  size="5" id="adv_no<?php echo $i; ?>" onkeypress="return onlynumbersadv(event,this.id)" onblur="getAdvocate(<?php echo $i; ?>)" />
                                <input class="form-control" type="text" maxlength="4" size="4" id="adv_yr<?php echo $i; ?>" onkeypress="return onlynumbers(event,this.id)" onblur="getAdvocate(<?php echo $i; ?>)" />
                                <input type="hidden" id="adv_state_hd<?php echo $i; ?>" />
                                <input type="hidden" id="adv_no_hd<?php echo $i; ?>" />
                                <input type="hidden" id="adv_yr_hd<?php echo $i; ?>" />
                            </span>
                        <?php
                        }
                        ?>
                    </td>
                    <td style="border:none"><input type="hidden" value="<?php echo $row['name'] . $row['adv'] ?>" id="adv_name_hd<?php echo $i; ?>" />
                        <?php
                        $write = 'N';

                        if ($row['advocate_id'] != 0) {
                        ?>
                            <span id="adv_name<?php echo $i; ?>" <?php if ($write == 'Y') echo "style='display:none'" ?>><?php echo $row['name'] . $row['adv'] ?></span>
                            <input class="form-control" type="text" id="adv_name_write<?php echo $i; ?>" style="display:<?php if ($write == 'Y') echo 'block';
                                                                                                    else if ($write == 'N') echo 'none'; ?>;text-transform:uppercase;width:200px;" onblur="copyToSpan(<?php echo $i; ?>)" value="<?php echo $row['name'] . $row['adv'] ?>" />

                            <br><span id="r_span_inperson<?php echo $i; ?>" <?php /*if($row['inperson_email']=='' && $row['inperson_email']=='')*/ if (in_array($row['advocate_id'], array(585, 616, 666, 940, 616))) {
                                                                                echo 'style="display:block"';
                                                                            } else {
                                                                                echo 'style="display:none"';
                                                                            } ?>>
                                <input class="form-control" type="text" maxlength="10" size="10" id="r_inperson_mob<?php echo $i; ?>" placeholder="mobile no." onkeypress="return onlynumbers(event,this.id)" value="<?php echo $row['inperson_mobile'] ?>" />
                                <br> <input class="form-control" type="text" id="r_inperson_email<?php echo $i; ?>" placeholder="email id" value="<?php echo $row['inperson_email'] ?>" style="width:200px" />
                            </span>

                    </td>

                <?php } else { ?>
                    <span id="adv_name<?php echo $i; ?>" style='display:none'><?php echo $row['name'] . $row['adv']; ?></span>
                    <input type="text" id="adv_name_write<?php echo $i; ?>" onkeypress="return advName(event)" style="display: block; text-transform: uppercase;width: 200px;" onblur="copyToSpan(<?php echo $i; ?>)" value="<?php echo $row['name'] . $row['adv'] ?>" /></td>
                <?php } ?>

                <?php
                $having_ag = 0;
                if ($row['pet_res_no'] == 0) {
                    $type = (!empty($row['adv'])) ?  (array)explode('[', rtrim($row['adv'], ']')) : array();
                } else {
                    $type =  (!empty($row['adv'])) ? (array)explode('[', rtrim($row['adv'], ']')) : array();
                    
                    for ($kk = 0; $kk < sizeof($type); $kk++) {
                        if ($type[$kk] == 'AG')
                            $having_ag = 1;
                    }
                    if ($row['pet_res_no'] != 0)
                        $type[1] = $type[2] ?? '';

                    if (!empty($type[2]) && $type[2] == 'LR/S')
                        $type[1] = $type[2];
                }

                ?>
                <td style="border:none"><select class="form-control" id="adv_type<?php echo $i; ?>" <?php if ($row['advocate_id'] == 0) { ?> style="display: none" <?php } ?>>
                        <option value='N' <?php if ($type[1] == 'N') { ?>selected <?php } ?>>None</option>
                        <option value='OBJ' <?php if ($type[1] == 'OBJ') { ?>selected <?php } ?>>OBJECTOR</option>
                        <option value='SURETY' <?php if ($type[1] == 'SURETY') { ?>selected <?php } ?>>SURETY</option>
                        <option value='INT' <?php if ($type[1] == 'INT') { ?>selected <?php } ?>>INTERVENOR</option>
                        <option value='IMPL' <?php if ($type[1] == 'IMPL') { ?>selected <?php } ?>>IMPLEADER</option>
                        <option value='COMP' <?php if ($type[1] == 'COMP') { ?>selected <?php } ?>>COMPLAINANT</option>
                        <option value='DRW' <?php if ($type[1] == 'DRW') { ?>selected <?php } ?>>DRAWNBY</option>
                        <option value='LR/S' <?php if ($type[1] == 'LR/S') { ?>selected <?php } ?>>LR/S</option>
                        <option value='SCLSC' <?php if ($type[1] == 'SCLSC') { ?>selected <?php } ?>>SCLSC</option>
                    </select></td>
                <td style="border:none"><select class="form-control" id='ifag<?php echo $i; ?>' <?php if ($row['advocate_id'] == 0) { ?> style="display: none" <?php } ?>>
                        <option value='N' <?php if ($having_ag == 0) echo "selected"; ?>>No</option>
                        <option value='AG' <?php if ($having_ag == 1) echo "selected"; ?>>ATTORNY GENERAL</option>
                    </select></td>
                <td style="border:none"><select class="form-control" id='statepg<?php echo $i; ?>'>
                        <option value='N' <?php if ($row['stateadv'] == 'N') echo "selected"; ?>>No</option>
                        <option value='P' <?php if ($row['stateadv'] == 'P') echo "selected"; ?>>Private</option>
                        <option value='G' <?php if ($row['stateadv'] == 'G') echo "selected"; ?>>Government</option>
                    </select>
                    <input type='hidden' value='<?php echo $row['stateadv']; ?>' id='statepg_hd<?php echo $i; ?>' />
                </td>

                <td style="border:none">
                    <input type="button" name="button_update_<?php echo $i; ?>" class="btn btn-primary" value="Update" onclick="update_advocate(<?php echo $i; ?>);" />

                    <input type="button" name="button_delete_<?php echo $i; ?>" class="btn btn-warning" value="Delete" />

                    <span id="adv_span<?php echo $i; ?>" style="color: green;font-size: 12px;text-wrap: nowrap;"> </span>
                </td>

                </tr>
            <?php
                $i++;
            }
        }?>
        </table>
       <?php $rs_ad = $dModel->getAdvocateDeatil($diary_no, 'I');
        if (!empty($rs_ad)) { 
            ?>
            <table border="1" class="table table-bordered table-striped custom-table th-no-radius">
                <thead>
                <tr>
                    <td colspan="8" style="border-left:0px;">&nbsp;</td>
                </tr>
                <tr>
                    <th  style="text-align: center!important;" colspan="8">Impleading</th>
                </tr>

                <tr>
                    <th colspan="7">Aor Details</th>
                    <th>Action</th>
                </tr>
                </thead>
                <?php



                foreach ($rs_ad as $row_im) {
                    $delval = "I-" . $row_im['aor_code'];
                    //echo $delval;

                ?>

                    <tr>
                        <td colspan="7"><?php echo $row_im['aor_code'] . "-"; ?><?php echo $row_im['name']; ?></td>


                        <td style="border:none">
                            <input type="button" name="del_int_<?php echo $delval; ?>" class="btn btn-warning" value="Delete" />
                        </td>
                    </tr>
                <?php  } ?>
            </table>
      <?php  } ?>
                </table>
     <?php   $rs_ad = $dModel->getAdvocateDeatil($diary_no, 'N');
        if (!empty($rs_ad)) {

            ?>
            <table border="1" class="table table-bordered table-striped custom-table th-no-radius">
           <thead>
            <tr>
                <th style="text-align: center!important;" colspan="8">Intervenor</th>
            </tr>

            <tr>
                <th colspan="3">Aor Details</th>
                <th colspan="2">Mobile</th>
                <th colspan="2">Email</th>
                <th colspan="1">Action</th>
            </tr>
        </thead>
            <?php



            foreach ($rs_ad as $row_im) {
                $delval = "N-" . $row_im['aor_code'];

            ?>

                <tr>
                    <td colspan="3"><?php echo $row_im['aor_code'] . "-"; ?><?php echo $row_im['name']; ?>


                    <td colspan="2"><?php echo $row_im['inperson_mobile'] ??''; ?></td>
                    <td colspan="2"><?php echo $row_im['inperson_email'] ?? ''; ?></td>


                    <td colspan="1" style="border:none">
                        <input type="button" name="del_int_<?php echo $delval; ?>" class="btn btn-warning" value="Delete" />
                    </td>
                </tr>
                </tr>




                <!-- ////////////////////////end of  code for impleading /////////////////////////////////////-->


            <?php

            }


          
        }
        ?>
        <input type="hidden" value="<?php echo $i; ?>" id="all" />

    </table>
<?php
}
?>

<script>

function getAdvocate(no)
{
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    //document.getElementById('container').innerHTML = '<table widht="100%" align="center"><tr><td style=color:red><blink>Please Wait<blink></td></tr></table>';
    xmlhttp.onreadystatechange=function()
    {
        if(xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            var val = xmlhttp.responseText;
            //alert(val);
            val = val.split('~');
            document.getElementById('adv_name'+no).innerHTML=val[0];
            document.getElementById('adv_name_hd'+no).value=val[0];
            //document.getElementById('adv_mob'+no).value=val[1];
            //document.getElementById('adv_email'+no).value=val[2];
            if(document.getElementById('adv_name_write'+no).style.display=='block'){
                document.getElementById('adv_name_write'+no).style.display='none';
                document.getElementById('adv_name'+no).style.display='inline';
                //document.getElementById('adv_mob'+no).style.display='inline';
                //document.getElementById('adv_email'+no).style.display='inline';
            }
        }
    }
    
    var url = base_url+"/Filing/Advocate/get_adv_name"+"?advno="+document.getElementById('adv_no'+no).value+"&advyr="+
        document.getElementById('adv_yr'+no).value+"&advstate="+document.getElementById('adv_state'+no).value;
    xmlhttp.open("GET",url,false);
    if(document.getElementById('adv_yr'+no).value!='')
    {    
        /*if(document.getElementById('adv_no'+no).value=='9999' && document.getElementById('adv_yr'+no).value=='2014')
        {    
            if(no!=9999)
                activeAdvEntry(no);
        }
        else
        {
            if(no!=9999)
                deactiveAdvEntry(no);
            xmlhttp.send(null); 
        }*/
        xmlhttp.send(null); 
    }
}

</script>