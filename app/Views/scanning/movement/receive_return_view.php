<table class="table table-responsive table_print">
    <thead>
        <tr>
            <th style="width:8%;">
                Item No.
            </th>
            <th style="width:20%;">
                Case No.
            </th>
            <th style="width:32%;">
                Cause Title
            </th>
            <th style="width:25%;">
                Event
            </th>
            <th style="width:15%;">
                Action
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
        $roster_id_rep = 0;
        $list_type_print = $mainhead == 'M' ? 'Misc.' : 'Regular';
        $sr = 1;
        $srtr = '';

        foreach ($results as $row) {
            $sr_td = 1;
            $jcodes = $row['judges'];
            $previous_rop_date = "";

            if ($row['roster_id'] != $roster_id_rep) {
                echo "<tr><td class='p-3' colspan='4' style='font-size:15px;'><span class='text-success font-13 font-weight-bold'><u>" . get_judges($jcodes) . "</u></span><br><span class='font-weight-bolder'>[List Date " . $list_date_dmy . ", Court No. ";
                if ($courtno > 60) {
                    echo "VC " . ($courtno - 60);
                } else if ($courtno > 30) {
                    echo "VC " . ($courtno - 30);
                } else {
                    echo $courtno;
                }
                echo ", List Type " . $list_type_print . "] </span> </td></tr>";
                $roster_id_rep = $row['roster_id'];
            }
            $is_draft_list = "";
            if ($row['brd_prnt'] == 'NA') {
                $is_draft_list = '<br><span class="text-danger">DRAFT</span>';
            }
            $con_no = "0";
            if ($row['diary_no'] == $row['conn_key'] or $row['conn_key'] == 0) {
                $print_brdslno = $row['brd_slno'];
                $con_no = "0";
                $is_connected = "";
                $rop_diary_no = $row['diary_no'];
            } else {
                $print_brdslno = "&nbsp;" . $row["brd_slno"] . "." . ++$con_no;
                $is_connected = "<br/><span style='color:red;'>Conn.</span>";
                $rop_diary_no = $row['conn_key'];
            }

        ?>
            <tr id="<?php echo 'row' . '_' . $sr; ?>">
                <td class="align-top" id="<?php echo 'row' . '_' . $sr . $sr_td++; ?>"><?php echo $print_brdslno . $is_connected; ?></td>
                <td class="align-top" id="<?php echo 'row' . '_' . $sr . $sr_td++; ?>"><?php echo $row['case_number'] . $is_draft_list; ?></td>
                <td class="align-top" id="<?php echo 'row' . '_' . $sr . $sr_td++; ?>"><?php echo $row['cause_title']; ?></td>
                <?php
                $db = \Config\Database::connect();
                $sql_chk_btn = "SELECT movement_flag, entry_date_time, roster_id FROM scan_movement WHERE dairy_no='" . $row['diary_no'] . "' AND is_active='T'";
                $res_btn = $db->query($sql_chk_btn)->getRowArray();
                if (
                    !empty($res_btn) && isset($res_btn['movement_flag']) && isset($res_btn['roster_id']) &&
                    (($res_btn['movement_flag'] == '' || $res_btn['movement_flag'] == null) ||
                        ($res_btn['movement_flag'] == 'return' && $res_btn['roster_id'] != $row['roster_id']))
                ) {
                ?>
                
                    <td class="event_div" id="<?php echo 'row' . '_' . $sr . $sr_td++; ?>">
                        <input type="radio" id="fresh" name="eventid" value="fresh">
                        <label for="fresh">Fresh</label><br>
                        <input type="radio" id="inclusion" name="eventid" value="inclusion">
                        <label for="inclusion">Inclusion</label><br>
                        <input type="radio" id="verification" name="eventid" value="verification">
                        <label for="verification">Verification</label><br>
                        <input type="radio" id="circulation" name="eventid" value="circulation">
                        <label for="circulation">Circulation</label><br>
                        <input type="radio" id="received_soft" name="eventid" value="Received Soft">
                        <label for="received_soft">Received Soft</label>
                    </td>
                <?php
                } else {
                ?>
                    <td id="<?php echo 'row' . '_' . $sr . $sr_td++; ?>"></td>
                <?php
                }
                ?>
                <td class="align-top" id="<?php echo 'row' . '_' . $sr . $sr_td++; ?>">
                    <?php
                    if ($res_btn && ($res_btn['movement_flag'] == '' || $res_btn['movement_flag'] == null)) { ?>
                        <button class="btn btn-primary action" data-ctn="2" id="receive"> Receive </button>
                    <?php } elseif ($res_btn && $res_btn['movement_flag'] == 'receive') { ?>
                        <button class="btn btn-primary action" data-ctn="1" id="return" style="background-color: #8A2624"> Return </button>
                    <?php } elseif ($res_btn && $res_btn['movement_flag'] == 'return' && $res_btn['roster_id'] == $row['roster_id']) { ?>
                        <span class="text-success">Already returned on - <?= (!empty($res_btn['entry_date_time'])) ? date("d-m-Y H:i:s", strtotime($res_btn['entry_date_time'])) : ''; ?>s</span>
                        <?php } elseif ($res_btn && $res_btn['movement_flag'] == 'return' && $res_btn['roster_id'] != $row['roster_id']) { ?>
                        <button type="button" class="btn btn-primary action" data-ctn="2" id="receive"> Receive </button>
                    <?php } ?>
                </td>
                <input type="hidden" id="<?php echo 'row' . '_' . $sr . $sr_td++; ?>" value="<?php echo $row['roster_id']; ?>"><!--roster_id-->
                <input type="hidden" id="<?php echo 'row' . '_' . $sr . $sr_td++; ?>" value="<?php echo $row['brd_slno']; ?>"><!--item_no-->
                <input type="hidden" id="<?php echo 'row' . '_' . $sr . $sr_td++; ?>" value="<?php echo $row['diary_no']; ?>"><!--diary_no-->
                <input type="hidden" id="<?php echo 'row' . '_' . $sr . $sr_td++; ?>" value="<?php echo $row['next_dt']; ?>"><!--list_dt-->
            </tr>
        <?php

            $sr++;
            $sr_td++;
            if ($sr_td == 9) {
                $sr_td = 0;
            }
        } //END OF FOREACH LOOP..
        ?>
    </tbody>
</table>