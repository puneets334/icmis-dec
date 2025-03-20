
<style>
    table tr:nth-child(even) td {
        background: none !important;
    }
    #head th {
    font-weight: bold;
    background-color: #cccccc !important;
}



    
</style>    
<div id="prnnt" style="font-size:12px;">
        <div align=center style="font-size:12px;"><b>
            <img src="<?= base_url('images/scilogo.png') ?>" width="50px" height="80px"/><br />SUPREME COURT OF INDIA<br/>
        </div>
        <table border="0" width="100%" style="font-size:12px; text-align: left; background: #ffffff;" cellspacing=0>
            <tr>
                <th colspan="4" style="text-align: center;">
                    <b>
                    <?php
                    $year = !empty($list_dt) ? "/".date('Y', strtotime($list_dt)):'';
                    $formated_date = !empty($list_dt) ? date('d-m-Y', strtotime($list_dt)) : '';
                    $advance_list_no = !empty($advance_list_no) ? "/".$advance_list_no : '';
                    
                    echo $nmd_note." FINAL ELIMINATION LIST - AL".$advance_list_no . $year . "<br/><br/>";
                    echo "<U>NOTICE</U><BR><BR>";
                    echo "THE FOLLOWING MATTERS NOTED FOR BEING LISTED ON ".$formated_date." HAVE BEEN ELIMINATED FROM THE FINAL LIST DUE TO EXCESS MATTERS/COMPELLING REASON. <BR><BR>";
                    //THE REASONS FOR THEIR ELIMINATION AND FURTHER DATES NOTED IN THE COMPUTER HAVE BEEN SHOWN AGAINST EACH MATTER.
                    ?>
                    </b>
                    <br>
                </th>
            </tr>
            <tr>
                <th colspan="4" style="text-align: left;">
                    <br>
                    <!-- NOTE : CHRONOLOGY IS BASED ON THE DATE OF INITIAL FILING<BR><BR>-->
                    <!--<u>CASES WHICH ARE DIRECTED TO BE LISTED DURING SUMMER VACATION</u>-->
                    <!--<br>INDIRECT/DIRECT TAX MATTERS TO BE LIST ON 04-09-2018<br><br>-->
                </th>
            </tr>

            <?php 
            if (!empty($final_eliminations)) {
                $head2013 = 1;
                $clnochk = 0;
                $subheading_rep = "0";
                $mnhead_print_once = 1;
                $is_connected = "";
                foreach($final_eliminations as $row) {
                    $diary_no = $row['diary_no'];
                    $psrno = $row['sno'];
                    $reason = "";
                    //$reason = "[".$row['reason'].". ]";
                    //NOW THIS MATTER IS NOTED FOR BEING LISTED ON ".date('d-m-Y', strtotime($row['next_dt_new']))."

                    if ($mnhead_print_once == 1) {
                        ?>
                        <tr id="head" style="font-weight: bold; background-color:#cccccc !important;">
                            <th style="width:5%;"><b>SNo.</b></th>
                            <th style="width:20%;"><b>Case No.</b></th>
                            <th style="width:35%;"><b>Petitioner / Respondent</b></th>
                            <th style="width:40%;"><b>Petitioner/Respondent Advocate</b></th>
                        </tr>
                    <?php
                        $mnhead_print_once++;
                    }
                        if($head2013 == 1 AND $row['listorder'] != '4' and $row['listorder'] != '5' and $row['listorder'] != '7' and $row['listorder'] != '8'){
                            $head2013++;
                            ?>

                    <?php
                        }
                    if ($row['reg_no_display'] == "") {
                        $comlete_fil_no_prt = "Diary No. " . substr_replace($row['diary_no'], '-', -4, 0);
                    } else {
                        $comlete_fil_no_prt = $row['reg_no_display'];
                    }
                    ?>
                    <tr>
                        <td><?php  echo $psrno ?></td>
                        <td rowspan=2>
                            <?php echo $is_connected . $comlete_fil_no_prt; ?><br/><?php echo $row['section_name']?><br/>
                        </td>
                        <td><?php echo $row['get_pet_name'] ?></td>
                        <td><?php str_replace(",", ", ", trim($row['padvname'], ",")); ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style='font-style: italic;'>Versus</td>
                        <td style='font-style: italic;'></td>
                    </tr>
                    <tr>
                        <td></td><td></td>
                        <td><?php echo $row['res_name']?></td><td><?php echo str_replace(",", ", ", trim($row['radvname'], ",")); ?></td>
                    </tr>
                    <tr>
                        <td></td><td></td>
                        <td style='vertical_align:top; text-align: left; padding-left:20px; padding-right:15px; font-weight:bold; color:blue;' align='top'></td>
                        <td></td>
                    </tr>
                    <?php
                    $x60 = 150;
                    $lines = explode("\n", wordwrap($reason, $x60));
                    for ($k = 0; $k < count($lines); $k++) { ?>
                        <tr>
                            <td></td><td></td>
                            <td style='vertical_align:top; text-align: left; padding-right:15px; font-weight:bold; color:blue;' valign='top'>
                            <?php echo $lines[$k]; ?>
                            </td>
                            <td></td>
                        </tr>
                    <?php } ?>

                    <?php
                    if(isset($row['old_cases']) && !empty($row['old_cases'] && isset($row['conn_key']))) {
                        if($row['diary_no'] == $row['conn_key']) {
                        $psrno_conc = "1";
                        foreach($row['old_cases'] as $row2) {
                            $reason = "";
                            if ($row2['reg_no_display'] == "") {
                                $comlete_fil_no_prt = "Diary No. " . substr_replace($row2['diary_no'], '-', -4, 0);
                            } else {
                                $comlete_fil_no_prt = $row2['reg_no_display'];
                            }
                            $padvname = "";
                            $radvname = "";
                            if (isset($row2['advocate_by_old_cases']) && !empty($row2['advocate_by_old_cases'])) {
                                $radvname = !empty($row2['advocate_by_old_cases']["r_n"]) ? str_replace(",", ", ", trim($row2['advocate_by_old_cases']["r_n"], ",")) : '';
                                $padvname = !empty($row2['advocate_by_old_cases']["p_n"]) ? str_replace(",", ", ", trim($row2['advocate_by_old_cases']["p_n"], ",")) : '';
                            }

                            if ($row2['pno'] == 2) {
                                $pet_name = $row2['pet_name'] . " AND ANR.";
                            } else if ($row2['pno'] > 2) {
                                $pet_name = $row2['pet_name'] . " AND ORS.";
                            } else {
                                $pet_name = $row2['pet_name'];
                            }
                            if ($row2['rno'] == 2) {
                                $res_name = $row2['res_name'] . " AND ANR.";
                            } else if ($row['rno'] > 2) {
                                $res_name = $row2['res_name'] . " AND ORS.";
                            } else {
                                $res_name = $row2['res_name'];
                            }
                            $cate_old_id1 = "";
                            ?>
                            <tr>
                                <td> <?php echo $psrno . '.' . $psrno_conc++ ?></td>
                                <td rowspan=2> 
                                    <span style='color:red;'>Connected</span><br/>
                                    <?php echo $comlete_fil_no_prt ?> <br/> <?php echo $row2['section_name'] ?><br/>
                                </td>
                                <td><?php echo $pet_name ?></td>
                                <td><?php echo $padvname; ?></td>
                            </tr>
                            <tr>
                                <td></td><td style='font-style: italic;'>Versus</td><td style='font-style: italic;'></td></tr>
                            <tr>
                                <td></td><td></td>
                                <td>
                                    <?php echo $res_name ?></td><td><?php echo $radvname;?>
                                </td>
                            </tr>

                                <?php
                                $x60 = 150;
                                $lines = explode("\n", wordwrap($reason, $x60));
                                for($k=0; $k<count($lines); $k++) { ?>
                                    <tr>
                                        <td></td><td></td>
                                        <td style='vertical_align:top; text-align: left; padding-left:20px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>
                                        <?php echo $lines[$k] ?>
                                        </td><td></td>
                                    </tr>
                                <?php } ?>
                        <?php } //END OF FOR LOOP ?>
                <?php } } //END OF IF CONDITIONS?>
                <?php } //END OF FIRST FOR LOOP?>
            <?php
            } else { ?>
                <tr><td rowspan=4> 
                <?php echo "<b>No Records Found</b>"; ?>
                </td>
            
                </tr>
            <?php } ?>
    </table>
<br>
<p align='left' style="font-size: 12px;"><b>NEW DELHI<BR />
<?php date_default_timezone_set('Asia/Kolkata'); echo date('d-m-Y H:i:s'); ?></b>&nbsp; &nbsp;</p>
<br>
<p align='right' style="font-size: 12px;"><b>ADDITIONAL REGISTRAR</b>&nbsp; &nbsp;</p>
</div>
<div class="footer bg-light text-center border-top fixed-bottom mrgB20">
    <?php
    //$final_eliminations_print = 0;
    if($final_eliminations_print > 0){
        echo "Already Printed";
    } else { ?>
        <input name="prnnt1" type="button" id="ebublish" value="e-Publish" class="btn btn-primary"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?php } ?>
    <input name="prnnt1" type="button" id="prnnt1" value="Print" class="btn btn-primary">
</div>