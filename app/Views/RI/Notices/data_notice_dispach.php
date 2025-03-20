<input type='hidden' name='hd_ddl_oraz' id="hd_ddl_oraz" value="<?php echo $ddlOR; ?>" />
<div style="text-align: right;padding-right: 30px;display: none;">
    <input type="radio" name="rdn_link" id="rdn_not_link" onclick="get_lis_notlis(this.id)" value="0" <?php if ($_REQUEST['rd_ck_nt'] == '0') { ?> checked="checked" <?php } ?> /><span style="color: red">Not Linked</span>&nbsp;
    <input type="radio" name="rdn_link" id="rdn_link" onclick="get_lis_notlis(this.id)" value="1" <?php if ($_REQUEST['rd_ck_nt'] == '1') { ?> checked="checked" <?php }  ?> />
    <span style="color: red">Linked</span>
</div>
<?php if (isset($result) && sizeof($result) > 0 && is_array($result)) {
?>
    <div class="table-responsive">
        <table width="100%" id="reportTable" class="table table-striped custom-table">
            <thead>
                <h3 style="text-align: center;"> Process ID Record </h3>
                <tr>
                    <th rowspan='1'>SNo.</th>
                    <th rowspan='1'>
                        <span class="sp_red">Process Id</span><br><span class="sp_green">Notice Type</span>/<br>Diary No.
                    </th>
                    <th rowspan='1'>Name & Address</th>
                    <th rowspan='1'>State /Distric</th>
                    <th rowspan='1'>Remark</th>
                    <th> Station</th>
                    <th style="<?php if ($ddlOR == 'A') { ?> display:none <?php } ?>">
                        Weight
                    </th>
                    <th>
                        Stamp<br />(Rs)
                    </th>
                    <?php
                    if ($ddlOR == 'R' || $ddlOR == 'Z') {
                    ?>
                        <th>Barcode</th>
                    <?php } ?>
                    <th>
                        Remark
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $s_no = 0;

                $ckk_f_no = '';
                $ck_rec_dt = '';
                foreach ($result as $row) {
                    $dis_m = 0;

                ?>
                    <tr id="tr_sn<?php echo $s_no; ?>">

                        <td>
                            <?php
                            echo $s_no + 1; ?>

                        </td>
                        <td>
                            <div style="word-wrap:break-word;width: 90px">
                                <span class="sp_pid" id="idd_pid<?php echo $s_no; ?>"><span class="sp_red" id="sp_process_id<?php echo $s_no; ?>"><?php echo $row['process_id'] ?></span>/<br /><span class="sp_red" id="sp_rec_dt<?php echo $s_no; ?>"><?php echo date('d-m-Y',  strtotime($row['rec_dt']));  ?></span></span>
                                <input type="hidden" name="hd_talw_id<?php echo $s_no; ?>" id="hd_talw_id<?php echo $s_no; ?>" value="<?php echo $row['id']; ?>" />
                                <span id="spnottype_<?php echo $s_no; ?>" onclick="show_nt(this.id)" class="sp_green"><?php echo $row['nt_typ']; ?></span>
                                <span id="sp_diary_no<?php echo $s_no; ?>"><?php echo substr($row['diary_no'], 0, -4) ?>-<?php echo substr($row['diary_no'], -4) ?></span>
                                <br /><span id="sp_hd_noticetype<?php echo $s_no; ?>">
                                    <?php
                                    $get_case_details = get_case_details($row['diary_no']);
                                    echo $get_case_details[7] . ' ' . substr($get_case_details[0], 3) . '/' . $get_case_details[1]; ?>
                                </span>
                            </div>
                        </td>

                        <td>
                            <input type="checkbox" name="chkDispatch_<?php echo $s_no; ?>" id="chkDispatch_<?php echo $s_no; ?>" class="cl_chkbox" <?php if ($ddlOR == 'O') { ?> disabled="true" <?php } ?> onclick="ena_lnk_case(this.id)" />
                        </td>

                        <td>
                            <div style="word-wrap:break-word;width: 90px">
                                <?php
                                if ($row['name'] != '' && $row['copy_type'] == 0) {
                                    echo $row['name'];
                                }
                                if (trim($row['name'], ' ') != '' && $row['tw_sn_to'] != 0 && $row['copy_type'] == 0) {
                                    echo "<br/>Through ";
                                }
                                if ($row['tw_sn_to'] != 0) {
                                    $send_to_name = send_to_name($row['send_to_type'], $row['tw_sn_to']);
                                }

                                echo $send_to_name;
                                if ($row['tw_sn_to'] != 0 && $row['send_to_type'] == 3) {
                                    $send_to_address = send_to_address($row['send_to_type'], $row['tw_sn_to']);
                                    echo "<br><b>Address: </b><br>" . $send_to_address;
                                } else {
                                    echo "<br><b>Address: </b><br>" . $row['address'];
                                }
                                ?>
                            </div>
                            <div style="color: red">
                                <?php
                                if ($row['copy_type'] == 1) {
                                    echo "Copy";
                                }
                                ?>
                            </div>
                        </td>


                        <td>
                            <div style="word-wrap:break-word;width: 50px; <?php if ($row['state_code'] > 100) { ?>color:red; font-weight: bold;<?php } ?> ">
                                <?php
                                if ($row['tw_sn_to'] == 0) {
                                    $get_district = get_district($row['tal_state']);
                                    $get_state = get_state($row['tal_district']);
                                    $t_dis = $row['tal_district'];
                                } else {
                                    $get_district = get_district($row['sendto_district']);
                                    $get_state = get_state($row['sendto_state']);
                                    $t_dis = $row['sendto_district'];
                                }
                                echo $get_district; ?>/<br /><?php echo $get_state;
                                                                ?>
                            </div>
                        </td>

                        <td>
                            <?php

                            $tehsil = get_tehsil_frm_district($t_dis);
                            ?>
                            <select name="ddlTehsil<?php echo $s_no; ?>" id="ddlTehsil<?php echo $s_no; ?>" class="cl_ddl_dis" onfocus="hide_clr(this.id)" style="width:80px; <?php if ($row['state_code'] > 100) { ?>color:red; font-weight: bold;<?php } ?> ">

                                <option value="">Select</option>
                                <?php
                                $i_ck = 0;
                                $dis_nm = '';

                                for ($index = 0; $index < count($tehsil); $index++) {
                                ?>
                                    <option value="<?php echo $tehsil[$index][0]; ?>" <?php if ($tehsil[$index][0] == $t_dis) { ?> selected="selected" <?php } ?>><?php echo $tehsil[$index][1]; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </td>

                        <td <?php if ($ddlOR == 'A') { ?> style="display: none" <?php } ?>>
                            <input type="text" name="txtWeight<?php echo $s_no; ?>" id="txtWeight<?php echo $s_no; ?>" size="2" onblur="get_amount(this.id)" class="cl_tw_weight" <?php if ($ddlOR == 'O') { ?> disabled="true" <?php } else if (($ddlOR == 'R' || $ddlOR == 'Z') && ($row['nt_type'] == '51' || $row['nt_type'] == '52')) { ?> disabled="true" <?php }
                                                                                                                                                                                                                                                                                                                                                            if ($ddlOR == 'R' || $ddlOR == 'Z') { ?> value='0' <?php } ?> onfocus="hide_clr(this.id)" <?php if ($ddlOR == 'A') { ?> value="0" <?php } ?> />
                        </td>
                        <td>
                            <div id="price_<?php echo $s_no; ?>" <?php if ($ddlOR == 'O') { ?> contenteditable="false" <?php } else { ?>contenteditable="true" <?php } ?> style="width:100%;border: 1px solid black;height: 20px" onfocus="hide_clr(this.id)" class="cl_price"></div>
                        </td>

                        <?php
                        if ($ddlOR == 'R' || $ddlOR == 'Z') {
                        ?>
                            <td>
                                <input type="text" name="txt_bar_cd<?php echo $s_no; ?>" id="txt_bar_cd<?php echo $s_no; ?>" onfocus="hide_clr(this.id)" size="10" />
                            </td>
                        <?php } ?>
                        <td>
                            <input type="text" name="txtRemdis_<?php echo $s_no; ?>" id="txtRemdis_<?php echo $s_no; ?>" size="10" />
                        </td>
                        <td>
                            <input type="button" name="btnsinsub_<?php echo $s_no; ?>" id="btnsinsub_<?php echo $s_no; ?>" value="Submit" onclick="disp_data_sin(this.id)" class="cp_ind_sb" <?php if ($ddlOR == 'O') { ?> disabled="true" <?php } ?> />
                            <?php

                            if ($ckk_f_no != $row['fil_no'] . '_' . $row['rec_dt']) {
                                $fil_nm = "notices/" . $row['fil_no'] . '_' . $row['rec_dt'] . ".html";
                                $ds = fopen($fil_nm, 'r');
                                $b_z = fread($ds, filesize($fil_nm));
                                fclose($ds);
                                $msg = $msg . utf8_encode($b_z);
                                $ckk_f_no = $row['fil_no'] . '_' . $row['rec_dt']
                            ?>

                            <?php } ?>
                        </td>
                    </tr>
                <?php
                    $s_no++;
                }


                ?>
                <tr>
                    <td colspan="12" style="text-align: center">
                        <?php  //echo $s_no.'<br/>'.$_REQUEST['hd_res_sq_ct']; 
                        ?>
                        <input type="button" name="btnSubmit" id="btnSubmit" value="Submit" onclick="disp_data()" class="cl_submit" />
                    </td>
                </tr>

            <?php

        } else {
            ?>
                <tr>
                    <td colspan="12">
                        <div style="text-align: center"><b>Process Id not found.</b></div>
                    </td>
                </tr>
            <?php
        } ?>
        </table>
        <div id="hd_not_det">No Record Found</div>