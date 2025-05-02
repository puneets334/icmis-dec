<?php if (!empty($get_roster_j_c)) { ?>
    <div id="prnnt">
        <div class="row">

            <h3 style="text-align: center; margin: 0 auto;"><?php echo $h3_head; ?></h3>
            <table  border="1" width="100%" id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <td width="15%" style="font-weight: bold; color: #dce38d;background: #918788 !important;">Court No.</td>
                        <td width="85%" style="font-weight: bold; color: #dce38d;background: #918788 !important;">Judges</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sno = 1;
                    $dno = '';
                    foreach ($get_roster_j_c as $ro) {
                        $sno1 = $sno % 2;
                        if ($sno1 == '1') { ?>
                            <tr id="<?php echo $dno; ?>"><td align="left" style='vertical-align: top;background: #ececec;'><?php echo $ro['courtno'] . "<br/>" . $ro['frm_time'];  ?></td>
                            <td align="left" style='vertical-align: top;background: #ececec;'><?php echo str_replace(",", "<br>", $ro['jnm']); ?></td></tr>
                            <?php } else { ?>
                            <tr id="<?php echo $dno; ?>">
                            
                            <td align="left" style='vertical-align: top;background: #f6e0f3;'><?php echo $ro['courtno'] . "<br/>" . $ro['frm_time'];  ?></td>
                            <td align="left" style='vertical-align: top;background: #f6e0f3;'><?php echo str_replace(",", "<br>", $ro['jnm']); ?></td>
                            </tr>
                            <?php
                        }
                            ?>
                        <?php
                        $sno++;
                    }
                        ?>
                </tbody>
            </table>

        </div>
       
    <?php
} else {
    echo '<div style="text-align: center; margin: 0 auto;">No Records Found</div>';
}
    ?>


    </div>
    <?php if (!empty($get_roster_j_c)) { ?>
    <div style="text-align: center;">
            <input name="prnnt1" type="button" id="prnnt1" value="Print" style="margin: 0 auto;">
        </div>
        <?php } ?>