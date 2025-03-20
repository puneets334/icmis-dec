<style>
    table tr:nth-child(even) td {
        background: none !important;
    }
    th, td {
        border: none;
        padding: 0px 10px !important;
        background: #cccccc;
        text-align: left !important;
    }
    
    th {
        text-align: left !important;
    }
    
</style>    
<div class="container" id="prnnt">
    <div align=center style="font-size:12px;"><b>
    <img src="<?= base_url('images/scilogo.png') ?>" width="50" height="80" alt="Supreme Court Logo"><br>
        SUPREME COURT OF INDIA
        <br/>
    </div>
    <?php
        $year = !empty($list_dt) ? "/" . date('Y', strtotime($list_dt)) : '';
        $formated_date = !empty($list_dt) ? date('d-m-Y', strtotime($list_dt)) : '';
        $advance_list_no = !empty($advance_list_no) ? "/" . $advance_list_no : '';
        ?>

    <h4 class="text-center" style="font-size:12px; text-align: center; font-weight: bold;">
        
    
        <?= $nmd_note . " ADVANCE ELIMINATION LIST - AL" . $advance_list_no . $year ?>
        <BR><BR><u>NOTICE</u><BR><BR>
        <p class="text-center">
            THE FOLLOWING MATTERS NOTED FOR BEING LISTED ON <?= $formated_date ?> HAVE BEEN ELIMINATED FROM THE
            ADVANCE LIST DUE TO EXCESS MATTERS/COMPELLING REASON.
        </p><BR><BR>
    </h4>

    <?php if (!empty($advance_eliminations)) : ?>
    <!--<table class="table table-bordered table-sm">-->
    <table border="0" width="100%" style="font-size:12px; background: #ffffff;" cellspacing=0>    
        <thead>
            <tr style="font-weight: bold; background-color:#cccccc !important;font-size: 14px; text-align: left !important;">
                <th style="width:5%; text-align: left;"><b>SNo.</b></th>
                <th style="width:20%; text-align: left;"><b>Case No.</b></th>
                <th style="width:35%; text-align: left;"><b>Petitioner / Respondent</b></th>
                <th style="width:40%; text-align: left;"><b>Petitioner/Respondent Advocate</b></th>
            </tr>
        </thead>
        
        <tbody>
            <?php
                    $psrno = 1;
                    foreach ($advance_eliminations as $row) :
                        $comlete_fil_no_prt = ($row['reg_no_display'] == "") ? "Diary No. " . substr_replace($row['diary_no'], '-', -4, 0) : $row['reg_no_display'];
                ?>
            <tr>
                <td><?= $psrno++ ?></td>
                <td rowspan="2">
                    <?= $comlete_fil_no_prt ?><br/><?= $row['section_name'] ?><br/>
                </td>
                <td><?= $row['get_pet_name'] ?></td>
                <td><?= $row['padvname'] ?></td>
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
                if(isset($row['old_cases']) && !empty($row['old_cases'])) {
                    $psrno_conc = 1;
                    foreach($row['old_cases'] as $row2) {
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
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else : ?>
    <p>No Records Found</p>
    <?php endif; ?>


    <br>
        <p align='left' style="font-size: 12px;"><b>NEW DELHI<BR/><?php date_default_timezone_set('Asia/Kolkata');
                echo date('d-m-Y H:i:s'); ?></b>&nbsp; &nbsp;
        </p>
        <br>
        <p align='right' style="font-size: 12px;"><b>ADDITIONAL REGISTRAR</b>&nbsp; &nbsp;</p>
    <!--<div class="row mt-3">
        <div class="col-6 text-start"  style="font-size: 12px;text-align:left">
            <b>NEW DELHI<BR>
                <?php //date_default_timezone_set('Asia/Kolkata');
                    //echo date('d-m-Y H:i:s'); ?>
            </b>
        </div>
        <div class="col-6 text-end" style="font-size: 12px;text-align:right">
            <b>ADDITIONAL REGISTRAR</b>
        </div>
    </div>-->
</div>

<div class="footer bg-light text-center border-top fixed-bottom mrgB20">
    <?php if ($advance_eliminations_print > 0) : ?>
    <span>Already Printed</span>
    <?php else : ?>
    <button class="btn btn-primary" id="ebublish">e-Publish</button>
    <?php endif; ?>
    <button class="btn btn-primary" id="prnnt1"">Print</button>
</div>
