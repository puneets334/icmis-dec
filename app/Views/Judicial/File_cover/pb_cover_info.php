<div class="text-end my-3">
    <button class="btn btn-primary" id="prnntCoverButton">Print</button>
</div>

<div id='prnntSection'>
    <div align="center">
        <b style="font-size: 20px">Section: <?php if (isset($section_name)) {
                                                echo $c_r;
                                            } ?></b>
    </div>

    <div align='center'>
        <div style="text-align: center; font-size: 20px; margin-top: 30px">
            IN THE SUPREME COURT OF INDIA
        </div>

        <div style="text-align: center; margin-bottom: 15px; font-size: 20px">
            <b>(<?php if (isset($c_r)) {
                    echo $c_r;
                }   ?> Appellate Jurisdiction)</b>
        </div>

        <b>
            <u>
                <div style="font-size: 20px; margin-top: 50px">
                    <?php echo $casename . $fil_no_yr; ?>
                </div>
            </u>
        </b>

        <div align="center" style="width: 100%; clear: both; margin-top: 70px">
            <table cellpadding="10" cellspacing="10" style="width: 100%">
                <tr>
                    <td style="font-size: 13pt">
                        <?= $r_get_da_sec['pet_name'] ?>
                        <?php if ($r_get_da_sec['pno'] == 2) echo " AND ANOTHER";
                        elseif ($r_get_da_sec['pno'] > 2) echo " AND OTHERS"; ?>
                    </td>
                    <td rowspan="2" style="vertical-align: middle; font-size: 13pt; text-align: center">VERSUS</td>
                    <td style="font-size: 13pt; text-align: right">...Petitioner(s)/Appellant(s)</td>
                </tr>
                <tr>
                    <td style="font-size: 13pt; text-align: left">
                        <?= $r_get_da_sec['res_name'] ?>
                        <?php if ($r_get_da_sec['rno'] == 2) echo " AND ANOTHER";
                        elseif ($r_get_da_sec['rno'] > 2) echo " AND OTHERS"; ?>
                    </td>
                    <td style="font-size: 13pt; text-align: right">...Respondent(s)</td>
                </tr>
            </table>
        </div>
        <div style="text-align: center;;margin-top: 75px">
            <div style="font-size: 20px;">
                <b>PAPER BOOK</b>
            </div>
            <div style="font-size: 20px;">
                <b>[FOR INDEX KINDLY SEE INSIDE]</b>
            </div>
        </div>
        <div style="clear: both">
            <table width='100%'>
                <tr>
                    <td style="border-right: 1px solid black;vertical-align: top">
                        <?php
                        $pet_adv = get_pet_adv($diaryNo); //pr($pet_adv);
                        ?>
                        <table>
                            <tr>
                                <td><u>Advocate for the Petitioner(s)/Appellant(s) (AOR Code):</u></td>
                            </tr>

                            <?php
                            // Loop through the document details (whether one or multiple)
                            if (!empty($pet_adv)) {
                                $sno = 1;
                                foreach ($pet_adv as $row) { // Loop through each document
                            ?>
                                    <tr>
                                        <td width='10%'>
                                            <?php if ($sno != 1) { ?>
                                            <?php } ?>

                                        </td>
                                        <td>
                                            <b><?php echo  $row['name'] . '(' . $row['aor_code'] . ')'; ?></b>
                                        </td>
                                    </tr>
                            <?php
                                    $sno++; // Increment serial number
                                }
                            }
                            ?>

                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div align="center" style="width: 100%; clear: both; margin-top: 200px">
        <?php //foreach ($pet_advocates as $advocate): 
        ?>
        <b>Advocate for the Petitioner/Appellant: <? //= $advocate['name'] 
                                                    ?> (<? //= $advocate['aor_code'] 
                                                                                ?>)</b>
        <?php //endforeach; 
        ?>
    </div>

    <b>
        <u>
            <div style="font-size: 20px; margin-bottom: 5px; position: fixed; right: 0px; bottom: 0px;">
                <? //= $main_case_details['short_description'] . ' ' . $active_fil_no 
                ?>
            </div>
        </u>
    </b>
</div>

<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>
<script>
     $(document).on("click","#prnntCoverButton",function() {
        var prtContent = $("#prnntSection").html();
        var temp_str = prtContent;
        var mywindow = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,cellspacing=5, cellpadding=5');
        var is_chrome = Boolean(mywindow.chrome);
        mywindow.document.write(prtContent);
        if (is_chrome) {
            setTimeout(function () { // wait until all resources loaded
                mywindow.document.close(); // necessary for IE >= 10
                mywindow.focus(); // necessary for IE >= 10
                mywindow.print();  // change window to winPrint
               
            }, 20);
        }
        else {
            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10
            mywindow.print();
        }
    });

</script>