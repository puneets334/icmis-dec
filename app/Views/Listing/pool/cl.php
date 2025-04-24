<div id="prnnt" style="font-size:12px;">
    <div align=center style="font-size:12px;"><SPAN style="font-size:12px;" align="center"> 
        <img src="<?php echo base_url()?>/images/scilogo.png" width="50px" height="80px"/><br/>            
        SUPREME COURT OF INDIA
        <br/>
    </div>
    <table border="0" width="100%" style="font-size:12px; text-align: left; background: #ffffff;" cellspacing=0>
        <?php
        
        $heading_priority_rep = "0";
        if (!empty($res)){
            $psrno = 1;
            $mnhead_print_once = 1;
            foreach ($res as $row) {
            $diary_no = $row['diary_no'];
            if ($mnhead_print_once == 1) {
                ?>
                <thead>
                <tr style="font-weight: bold; background-color:#cccccc;">
                    <td style="width:5%;">SNo.</td>
                    <td style="width:20%;">Case No.</td>
                    <td style="width:35%;">Petitioner / Respondent</td>
                    <td style="width:40%;">Petitioner/Respondent Advocate</td>
                </tr>
                </thead>
                <?php
                $mnhead_print_once++;
            }
            
            if ($row['reg_no_display'] == "") {
                $comlete_fil_no_prt = "Diary No. " . substr_replace($row['diary_no'], '-', -4, 0);
            } else {
                $comlete_fil_no_prt = $row['reg_no_display'];
            }

            ?>
            <tbody>
                <tr style='padding-top:5px;'>
                <td style='vertical-align: top;'><?= $psrno ?></td>
                <td style='vertical-align: top;' rowspan="2">
                    <?= $comlete_fil_no_prt ?><br/><?= $row['section_name'] ?><br/>
                </td>
                <td style='vertical-align: top;'><?= $row['get_pet_name'] ?></td>
                <td style='vertical-align: top;'><?= $row['padvname'] ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td style="font-style: italic;">Versus</td>
                    <td style='font-style: italic;'></td>
                    </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td><?= $row['res_name'] ?></td>
                    <td><?= $row['radvname'] ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td style='vertical_align:top; text-align: left; padding-left:20px; padding-right:15px; font-weight:bold; color:blue;' valign='top'></td>
                    <td></td>
                </tr>  


            <?php
            if(isset($row['connected_cases']) && !empty($row['connected_cases'])) {
                $psrno_conc = 1;
                foreach($row['connected_cases'] as $row2) {
                    $comlete_fil_no_prt = ($row2['reg_no_display'] == "") ? "Diary No. " . substr_replace($row2['diary_no'], '-', -4, 0) : $row2['reg_no_display'];
                    $radvname = !empty($row2['advocate_by_old_cases']["r_n"]) ? str_replace(",", ", ", trim($row2['advocate_by_old_cases']["r_n"], ",")) : '';
                    $padvname = !empty($row2['advocate_by_old_cases']["p_n"]) ? str_replace(",", ", ", trim($row2['advocate_by_old_cases']["p_n"], ",")) : '';
                    $pet_name = ($row2['pno'] == 2) ? $row2['pet_name'] . " AND ANR." : (($row2['pno'] > 2) ? $row2['pet_name'] . " AND ORS." : $row2['pet_name']);
                    $res_name = ($row2['rno'] == 2) ? $row2['res_name'] . " AND ANR." : (($row2['rno'] > 2) ? $row2['res_name'] . " AND ORS." : $row2['res_name']);
                    ?>
        <tr>
            <td> <?= $psrno . '.' . $psrno_conc++ ?></td>
            <td rowspan="2">
                <span style='color:red;'>Connected</span><br />
                <?php echo $comlete_fil_no_prt ?> <br /> <?php echo $row2['section_name'] ?><br />
            </td>
            <td><?php echo $pet_name ?></td>
            <td><?php echo $padvname; ?></td>
        </tr>
        <tr>
            <td></td>
            <td style='font-style: italic;'>Versus</td>
            <td style='font-style: italic;'></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>
                <?php echo $res_name ?></td>
            <td><?php echo $radvname;?>
            </td>
        </tr>
        <?php } } ?>
        
            </tbody>
        <?php $psrno++;} } else { 
            echo "No Records Found";
            }
            ?>
    </table>    
    <br>
    <p align='left' style="font-size: 12px;"><b>NEW DELHI<BR/><?php date_default_timezone_set('Asia/Kolkata');
        echo date('d-m-Y H:i:s'); ?></b>&nbsp; &nbsp;</p>
    <br>
    <p align='right' style="font-size: 12px;"><b>ADDITIONAL REGISTRAR</b>&nbsp; &nbsp;</p>
</div>