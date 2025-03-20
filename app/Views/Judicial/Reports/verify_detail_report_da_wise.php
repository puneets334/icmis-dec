<div style="text-align: center">
    <H3>CASES VERIFIED DETAIL REPORT</H3>

    <?php if(!empty($all_matters)) 
    { ?>
            
        Verified Matters of <?= $username_uby; ?> Listed on <?= $listed_date; ?>

        <table align="left" width="100%" border="0px;" style="table-layout: fixed;">

            <tr style="background: #918788;">
                <td width="5%" style="font-weight: bold; color: #dce38d;">SNo</td>
                <td width="13%" style="font-weight: bold; color: #dce38d;">Diary/Reg No</td>
                <!--<td width="5%" style="font-weight: bold; color: #dce38d;">ROP</td>-->
                <td width="15%" style="font-weight: bold; color: #dce38d;">Petitioner / Respondent</td>
                <td width="15%" style="font-weight: bold; color: #dce38d;">Advocate</td>
                <td width="10%" style="font-weight: bold; color: #dce38d;">Heading/Category</td>
                <td width="16%" style="font-weight: bold; color: #dce38d;">LastOrder / Statutory</td>
                <td width="12%" style="font-weight: bold; color: #dce38d;">IA</td>
                <td width="6%" style="font-weight: bold; color: #dce38d;">Purpose</td>
                <td width="14%" style="font-weight: bold; color: #dce38d;">Verification<br />Report</td>

            </tr>
            <?php
            $sno = 1;
            $psrno = 1;
            foreach($all_matters as $row) {
                $sno1 = $sno % 2;
                $dno = $row['diary_no'];
                $verify_time = "<br><span style='color:red'>Verify Time : " . date('h:i:s', strtotime($row['verified_on'])) . "</span>";
                $coram = $row['coram'];
                $purpose = $row['purpose'];
                $lastorder = $row['lastorder'];
                $stagename = $row['stagename'];
                $diary_no_rec_date = "Diary Dt " . date('d-m-Y', strtotime($row['diary_no_rec_date']));
                $fil_dt = "Reg Dt " . date('d-m-Y', strtotime($row['fil_dt']));

                $verify_str = $dno . "_" . $board_type . "_" . $mainhead;

                if ($sno1 == '1') { ?>
                    <tr style=" background: #ececec;" id="<?php echo $verify_str; ?>">
                    <?php } else { ?>
                    <tr style=" background: #f6e0f3;" id="<?php echo $verify_str; ?>">
                    <?php
                }
                if ($row['diary_no'] == $row['main_key'] or $row['main_key'] == 0 or $row['main_key'] == "") {
                    // $print_brdslno = $row['brd_slno'];
                    $print_srno = $psrno;
                    $con_no = "0";
                    $is_connected = "";
                    $is_main = "";
                    if ($row['diary_no'] == $row['main_key']) {
                        $print_srno = $psrno;
                        $con_no = "0";
                        $is_connected = "";
                        $is_main = "<span style='color:blue;'>Main</span><br/>";
                        //$is_connected = "<span style='color:red;'>Main</span><br/>";
                    }
                } else if ($row['listed'] == 1 or ($row['diary_no'] != $row['main_key'] and $row['main_key'] != null)) {
                    $is_main = "";
                    $is_connected = "<span style='color:red;'>Connected</span><br/>";
                }
                $m_f_filno = $row['active_fil_no'];
                $m_f_fil_yr = $row['active_reg_year'];
                //            }
                $filno_array = explode("-", $m_f_filno);
                if ($filno_array[1] == $filno_array[2]) {
                    $fil_no_print = ltrim($filno_array[1], '0');
                } else {
                    $fil_no_print = ltrim($filno_array[1], '0') . "-" . ltrim($filno_array[2], '0');
                }
                //if ($row['active_fil_no'] == "") {
                $diary_str = substr_replace($row['diary_no'], '-', -4, 0);
                $d_str = explode("-", $diary_str);
                $comlete_fil_no_prt = "Diary No. <a data-animation=\"fade\" data-reveal-id=\"myModal\" onclick=\"call_cs('$d_str[0]','$d_str[1]','','','');\" href='#'>" . $d_str[0] . '/' . $d_str[1] . "</a><br>" . $diary_no_rec_date;

                if (!empty($fil_no_print)) {
                    $comlete_fil_no_prt .= "<br>" . $row['short_description'] . "-" . $fil_no_print . "/" . $m_f_fil_yr . "<br>" . $fil_dt;
                } else {
                    $comlete_fil_no_prt .= "<br>Unreg.";
                }
                //}
                $padvname = "";
                $radvname = "";

                $radvname = $row["radvname"];
                $padvname = $row["padvname"];
                $impldname = $row["impldname"];

                if ($row['pno'] == 2) {
                    $pet_name = $row['pet_name'] . " AND ANR.";
                } else if ($row['pno'] > 2) {
                    $pet_name = $row['pet_name'] . " AND ORS.";
                } else {
                    $pet_name = $row['pet_name'];
                }
                if ($row['rno'] == 2) {
                    $res_name = $row['res_name'] . " AND ANR.";
                } else if ($row['rno'] > 2) {
                    $res_name = $row['res_name'] . " AND ORS.";
                } else {
                    $res_name = $row['res_name'];
                }

                if ($is_connected != '') {
                    $print_srno = "";
                } else {
                    $print_srno = $print_srno;
                    $psrno++;
                } ?>
                    <td align="right" style='vertical-align: top;'>
                        <br><strong><?php echo $print_srno; ?></strong>
                    </td>
                    <td align="left" style='vertical-align: top;'><?php
                                                                    echo $is_main . $is_connected . $comlete_fil_no_prt . "<br>(" . $row['section_name'] . ") " . $row['name'] . "<br/>";
                                                                    echo "<span class='tooltip'>" . $cat_code . "<span class='tooltiptext'>Tooltip text</span></span>";
                                                                    ?></td>


                    <td align="left" style='vertical-align: top;'><?php echo $pet_name . "<br/>Vs<br/>" . $res_name; ?></td>
                    <td align="left" style='vertical-align: top;'><?php echo str_replace(",", ", ", trim($padvname, ",")) . "<br/>Vs<br/>" . str_replace(",", ", ", trim($radvname, ",")); ?></td>
                    <td align="left" style='vertical-align: top;'><?php echo $row['stagename']; ?></td>
                    <td align="left" style='vertical-align: top;'><?php echo "<i>" . $lastorder . "</i><br>" . get_cl_brd_remark($dno); ?></td>
                    <!--                <td align="left" style='vertical-align: top;'><?php  ?></td>-->
                    <td align="left" style='vertical-align: top;'><?php f_get_docdetail($dno);  ?>
                    </td>

                    <td align="left" style='vertical-align: top;'><?php echo $purpose;  ?>
                    </td>
                    <td>
                        <?= $row['remarks_by_monitoring'] ?>
                        <br />
                        <span style="font-weight:bold; color: brown;">Verified By: <?= $row["verified_by"] ?></span>
                        <span style="font-weight:bold; color: brown;">Verified On: <?= $row["verified_on"] ?></span>
                    </td>

                    </tr>
            <?php $sno++; } ?>
        </table>
    <?php } else { ?>
        No Recrods Found
    <?php } ?>
</div>