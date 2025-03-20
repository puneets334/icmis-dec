<?php

if (count($get_ct_listed_disposed_popup) > 0) {
?>
    <div id="prnnt2" style="text-align: center; font-size:14px;">
        <!-- <h3>Case Type Wise Listed/Disposed Cases</h3> -->
        <table class="table table-striped custom-table">
            <thead>
                <tr>
                    <th>Srno.</th>
                    <th>Reg No. / Diary No</th>
                    <?php
                    if ($flag == 1 or $flag == 2 or $flag == 3 or $flag == 4 or $flag == 5 or $flag == 6) { ?>
                        <th>Listing Date</th>
                    <?php }
                    if ($flag == 7 or $flag == 8 or $flag == 9 or $flag == 10 or $flag == 11 or $flag == 12) { ?>
                        <th>Dispose Date</th>
                    <?php } ?>
                    <th>Petitioner / Respondent</th>
                    <th>Advocate</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sno = 1;

                foreach ($get_ct_listed_disposed_popup as $ro) {
                    $sno1 = $sno % 2;
                    $dno = $ro['diary_no'];
                    $conn_no = $ro['conn_key'];
                    $next_dt = date('d-m-Y', strtotime($ro['dttt']));
                    $filno_array = explode("-", $ro['active_fil_no']);

                    if (empty($filno_array[0])) {
                        $fil_no_print = "Unregistred";
                    } else {
                        $fil_no_print = $ro['short_description'] . "/" . ltrim($filno_array[1], '0');
                        if (!empty($filno_array[2]) and $filno_array[1] != $filno_array[2])
                            $fil_no_print .= "-" . ltrim($filno_array[2], '0');
                        $fil_no_print .= "/" . $ro['active_reg_year'];
                    }
                    if ($sno1 == '1') { ?>
                        <tr style=" background: #ececec;" id="<?php echo $dno; ?>">
                        <?php } else { ?>
                        <tr style=" background: #f6e0f3;" id="<?php echo $dno; ?>">
                        <?php
                    }


                    if ($ro['pno'] == 2) {
                        $pet_name = $ro['pet_name'] . " AND ANR.";
                    } else if ($ro['pno'] > 2) {
                        $pet_name = $ro['pet_name'] . " AND ORS.";
                    } else {
                        $pet_name = $ro['pet_name'];
                    }
                    if ($ro['rno'] == 2) {
                        $res_name = $ro['res_name'] . " AND ANR.";
                    } else if ($ro['rno'] > 2) {
                        $res_name = $ro['res_name'] . " AND ORS.";
                    } else {
                        $res_name = $ro['res_name'];
                    }
                    $padvname = "";
                    $radvname = "";

                    $this_result = new App\Models\ManagementReport\PendingModel;
                    $result_array2 = $this_result->get_ct_listed_disposed_popup_table($ro["diary_no"]);
                    if (count($result_array2) > 0) {
                        $rowadv = $result_array2;
                        // if($jcd_rp !== "117,210" AND $jcd_rp != "117,198"){ 
                        $radvname =  $rowadv[0]["r_n"] ?? '';
                        $padvname =  $rowadv[0]["p_n"] ?? '';
                        // }
                    }
                    if ($ro['ref_agency_state_id'] != '' and $ro['ref_agency_state_id'] != 0) {
                        if ($ro['active_reg_year'] != 0)
                            $ten_reg_yr = $ro['active_reg_year'];
                        else
                            $ten_reg_yr = date('Y', strtotime($ro['diary_no_rec_date']));

                        // if ($ro['active_casetype_id'] != 0) {
                        //     $casetype_displ = $ro['active_casetype_id'];
                        // }

                        if ($ro['casetype_id'] != 0) {
                            $casetype_displ = $ro['casetype_id'];
                        }
                    }
                        ?>
                        <td align="left" style='vertical-align: top;'><?php echo $sno; ?></td>
                        <td align="left" style='vertical-align: top;'><?php echo $fil_no_print . "<br>Dno " . substr_replace($ro['diary_no'], '-', -4, 0);
                                                                        if ($ro['conn_key'] == $ro['diary_no']) {
                                                                            echo " Main";
                                                                        }
                                                                        ?></td>
                        <td align="left" style='vertical-align: top;'><?php echo $next_dt; ?></td>
                        <td align="left" style='vertical-align: top;'><?php echo $pet_name . "<br/>Vs<br/>" . $res_name; ?></td>
                        <td align="left" style='vertical-align: top;'>
                            <?php echo str_replace(",", ", ", trim($padvname, ",")) . "<br/>Vs<br/>" . str_replace(",", ", ", trim($radvname, ",")); ?>
                        </td>
                        </tr>
                    <?php
                    $sno++;
                }
                    ?>
            </tbody>
        </table>
    </div>

<?php
} else {
    echo "No Recrods Found";
}
?>