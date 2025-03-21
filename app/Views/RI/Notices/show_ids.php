<?php 
    $or_re_de = '';

    $ful_frm = '';
    if ($_REQUEST['ddlOR'] == 'O') {

        $ful_frm = 'Ordinary';
    } else if ($_REQUEST['ddlOR'] == 'R') {

        $ful_frm = 'Registry';
    } else if ($_REQUEST['ddlOR'] == 'A') {
        $$ful_frm = 'Humdust';
    } else if ($_REQUEST['ddlOR'] == 'Z') {

        $ful_frm = 'Advocate Registry';
    }
?>
    <div style="text-align: center">Delivery Type <b><?php echo $ful_frm; ?></b> </div>
    <table width="100%" class="c_vertical_align tbl_border" cellspacing="5" cellpadding="5">
        <tr>

            <th>
                Dispatch Id
            </th>
            <th>
                Process Id
            </th>
            <th>
                Case No.
            </th>
            <th>
                Name
            </th>

            <th>
                Notice Type
            </th>
            <th>
                Station
            </th>
            <?php
            if ($_REQUEST['ddlOR'] != 'A') {
            ?>
                <th>
                    Weight
                </th>
            <?php } ?>
            <th>
                Stamp
            </th>
            <?php
            if ($_REQUEST['ddlOR'] == 'R' || $_REQUEST['ddlOR'] == 'Z') {
            ?>
                <th>
                    Barcode
                </th>
            <?php
            }
            ?>
            <th>
                Remark
            </th>
        </tr>

        <?php

        $var_st_cp_to = '';
        $chk_ln_nl = '';
        $get_dis_id = '0';
        //$_REQUEST['tot_id']=str_replace('@', '~',urldecode($_REQUEST['tot_id']));
        $_REQUEST['tot_id'] = urldecode($_REQUEST['tot_id']);
        $exx_al =  explode('@', $_REQUEST['tot_id']);
        if ($_REQUEST['ln_nl_val'] == 1)
            $chk_ln_nl = count($exx_al);
        for ($index = 0; $index < count($exx_al); $index++) {

            $_REQUEST['tot_id'] = $exx_al[$index];


//             echo $sql = "SELECT d.id,a.diary_no, process_id, a.name, address, b.name nt_typ, del_type, 
//       tw_sn_to, copy_type, send_to_type, fixed_for, rec_dt, office_notice_rpt,reg_no_display,
//       sendto_district,sendto_state,nt_type,tal_state,tal_district,dispatch_id,date(dispatch_dt) dispatch_dt,
//       weight,stamp,barcode,dis_remark,station
// FROM tw_tal_del a
// JOIN tw_notice b ON a.nt_type = b.id
// JOIN tw_o_r c ON c.tw_org_id = a.id
// JOIN tw_comp_not d ON d.tw_o_r_id = c.id
// join main m on a.diary_no=m.diary_no
// WHERE  a.display = 'Y'
// AND print =1
// AND b.display = 'Y'
// AND c.display = 'Y'
// AND d.display = 'Y' and dispatch_id!=0 and dispatch_dt!='0000-00-00 00:00:00' 
// and d.id='$_REQUEST[tot_id]'";
//             $sql =  mysql_query($sql) or die("Error: " . __LINE__ . mysql_error());


 

            foreach ($result as $row) {
            ?>
                <tr>
                    <?php if ($_REQUEST['ln_nl_val'] == 1 && $get_dis_id == '0') { ?>
                        <td rowspan="<?php echo $chk_ln_nl ?>">
                            <?php echo $row['dispatch_id']; ?>/<?php echo date('Y', strtotime($row['dispatch_dt'])); ?>
                        </td>
                    <?php } else if ($_REQUEST['ln_nl_val'] == 0) { ?>
                        <td>
                            <?php echo $row['dispatch_id']; ?>/<?php echo $row['dispatch_dt']; ?>
                        </td>
                    <?php }
                    ?>
                    <td>
                        <?php echo $row['process_id'] ?>/<?php echo date('Y', strtotime($row['rec_dt'])); ?>
                        <div style="color: red">

                        </div>
                    </td>
                    <td>
                        <?php
                        $get_case_details = get_case_details($row['diary_no']);
                        echo $get_case_details[7] . ' ' . substr($get_case_details[0], 3) . '/' . $get_case_details[1];
                        ?>

                    </td>
                    <td>
                        <div style="word-wrap:break-word;width: 90px">
                            <?php
                            if ($row['name'] != '' && $row['copy_type'] == 0) {
                                echo $row['name'];
                            }
                            if ($row['name'] != '' && $row['tw_sn_to'] != 0 && $row['copy_type'] == 0) {
                                echo "<br/>Through ";
                            }
                            if ($row['tw_sn_to'] != 0) {
                                $send_to_name = send_to_name($row['send_to_type'], $row['tw_sn_to']);
                            }

                            echo $send_to_name;
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
                        <?php echo $row['nt_typ']; ?>
                    </td>
                    <?php
                    $tehsil = get_district($row['station']);
                    if ($_REQUEST['ln_nl_val'] == 1 && $get_dis_id == '0') { ?>
                        <td rowspan="<?php echo $chk_ln_nl ?>">
                            <?php

                            echo $tehsil; ?>
                        </td>

                        <?php
                        if ($_REQUEST['ddlOR'] != 'A') {
                        ?>
                            <td rowspan="<?php echo $chk_ln_nl ?>">
                                <?php echo $row['weight']; ?>
                            </td>
                        <?php } ?>
                        <td rowspan="<?php echo $chk_ln_nl ?>">
                            <?php echo $row['stamp']; ?>
                        </td>
                    <?php } else if ($_REQUEST['ln_nl_val'] == 0) { ?>
                        <td>
                            <?php echo $tehsil; ?>
                        </td>

                        <?php
                        if ($_REQUEST['ddlOR'] != 'A') {
                        ?>
                            <td>
                                <?php echo $row['weight']; ?>
                            </td>
                        <?php } ?>
                        <td>
                            <?php echo $row['stamp']; ?>
                        </td>
                    <?php }

                    ?>
                    <?php
                    if ($_REQUEST['ddlOR'] == 'R' || $_REQUEST['ddlOR'] == 'Z') {
                    ?>
                        <td>
                            <?php echo $row['barcode']; ?>

                        </td>
                    <?php
                    }
                    ?>
                    <td>
                        <?php echo $row['dis_remark']; ?>
                    </td>

                </tr>
            <?php
                $get_dis_id++;
            }
            ?>

        <?php } ?>
    </table>
 