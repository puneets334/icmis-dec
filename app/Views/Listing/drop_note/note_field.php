<?php
    //$bench_no = $note_field['bench_no'];
    $bench_time = isset($note_field['frm_time']) ? $note_field['frm_time'] : '';
    $bench_judge_name = isset($note_field['jnm']) ? stripcslashes(str_replace(",", "<br/>", $note_field['jnm'])) : '';
    $bench_court = isset($note_field['courtno']) ? $note_field['courtno'] : '';
    $jcd_rp = isset($note_field['jcd']) ? $note_field['jcd'] : '';
    $board_type_mb = isset($note_field['board_type_mb']) ? $note_field['board_type_mb'] : ''; 
    $frm_time = isset($note_field['frm_time']) ? $note_field['frm_time'] : '';
    if($bench_court == "1") {
       $print_court_no = "CHIEF JUSTICE'S COURT";
    }
    else if($bench_court == "61") {
        $print_court_no = "Registrar Virtual Court No. : 1";
    }
    else if($bench_court == "62") {
        $print_court_no = "Registrar Virtual Court No. : 2";
    }
    else if($bench_court == "21") {
        $print_court_no = "Registrar Court No. : 1";
    }
    else if($bench_court == "22") {
        $print_court_no = "Registrar Court No. : 2";
    }
    else{
       $print_court_no = "COURT NO. : ". $bench_court;
    }

    ?>
<div id="prnnt" style="font-size:12px;">
    <table border="0" width="100%" style="font-size:12px; text-align: left; background: #ffffff;" cellspacing=0>
        <tr>
            <th colspan="4" style="text-align: center;">
                <img src="<?= base_url('images/scilogo.png') ?>" width="50px" height="80px"/>
            </th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;">SUPREME COURT OF INDIA</th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;">DAILY CAUSE LIST FOR DATED : <?php echo date('d-m-Y', strtotime($list_dt)); ?> </th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;"><?php echo $print_court_no; ?> </th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;"><?php echo $bench_judge_name; ?></th>
        </tr>
        <?php if ($bench_time) { ?>
            <tr>
                <th colspan="4" style="text-align: center;">(TIME : <?php echo $bench_time; ?>)</th>
            </tr>
        <?php } ?>
        <tr>
            <td colspan='4' style='font-size:13px;font-weight:bold; text-decoration:underline; text-align:center;'>SUPPLEMENTARY LIST</td>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;">
                <!-- get_header_footer_print -->
                <table border="0" cellspacing="0">
                <tr><td style="text-align:left"><U>NOTE</U>:-</td></tr>
                <?php foreach($h_notes as $h_note) { ?>
                    <tr>
                        <td style="text-align:left">
                            <?php echo $h_note['h_f_note'] ?>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </th>
        </tr>

        <tr>
            <th colspan="4" style="text-align: center; vertical-align: middle;"> 
                <!-- get_drop_note_print -->
                <?php if(!empty($drop_notes)) { ?>
                    <div style="text-align: center;">
                        <table border="1" style="font-size:12px; text-align: center; background: #ffffff;" cellspacing=0>
                        <tr><td style="text-align:left" colspan="6"><U>DROP NOTE</U>:-</td></tr>
                        <tr>
                            <td style="text-align:left">Item No.</td><td style="text-align:left">Case No.</td>
                            <td style="text-align:left">Petitioner/Respondent</td>
                            <td style="text-align:left">Advocate</td>
                            <td style="text-align:left">Shifted to</td>
                            <td style="text-align:left">Reason</td>
                        </tr>
                        <?php foreach($drop_notes as $drop_note){ ?>
                            <tr>
                                <td style="text-align:left">
                                    <?php echo $drop_note['clno'] ?>
                                </td>
                                <td style="text-align:left">
                                    <?php echo $drop_note['case_no'] ?>
                                </td>
                                <td style="text-align:left">
                                    <?php echo $drop_note['pname'];
                                    if($drop_note['rname'] != ""){
                                        echo "<br>Vs.<br/>". $drop_note['rname'];
                                    }
                                    ?>
                                </td>
                                <td style="text-align:left">
                                   <?php echo $drop_note['advocate']?>
                                </td>
                                <td style="text-align:left">
                                 <?php echo $drop_note['shifted_to']?>
                                </td>
                                <td style="text-align:left">
                                    <?php echo $drop_note['nrs'] ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </table></div>
                <?php } ?>    
            </th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;">
                <!-- get_header_footer_print -->
                <table border="0" cellspacing="0">
                    <tr><td style="text-align:left"><U>NOTE</U>:-</td></tr>
                    <?php foreach($f_notes as $f_note){ ?>
                        <tr>
                            <td style="text-align:left">
                                <?php echo $f_note['h_f_note'] ?>
                            </td>
                        </tr>
                        <?php } ?>
                </table>
            </th>
        </tr>
    </table>
    <br>
    <p align='left' style="font-size: 12px;"><b>NEW DELHI<br />
    <?php date_default_timezone_set('Asia/Kolkata'); echo date('d-m-Y H:i:s'); ?></b>&nbsp; &nbsp;</p>
    <br>
    <p align='right' style="font-size: 12px;"><b>ADDITIONAL REGISTRAR</b>&nbsp; &nbsp;</p>
</div>
<div style="width: 100%; padding-bottom:1px; background-color: #ddf1f9; text-align: center; border-top: 1px solid #000; position: fixed; bottom: 0; left: 0; right: 0; z-index: 0; display:block;">
    <span id="toggle_hw" style="color: #0066cc; font-weight: bold; cursor: pointer; padding-right: 1px;">
    </span>
    <input name="prnnt1" type="button" id="prnnt1" value="Print">

</div>