<?php if (!empty($get_roster_j_c)) { ?>
    <div id="prnnt">
        <div class="row">

            <h3 style="text-align: center; margin: 0 auto;"><?php echo $h3_head; ?></h3>
            <table  border="1" width="100%" id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr style="background: #918788;">
                        <td width="15%" style="font-weight: bold; color: #dce38d;">Court No.</td>
                        <td width="85%" style="font-weight: bold; color: #dce38d;">Judges</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sno = 1;
                    $dno = '';
                    foreach ($get_roster_j_c as $ro) {
                        $sno1 = $sno % 2;
                        if ($sno1 == '1') { ?>
                            <tr id="<?php echo $dno; ?>">
                            <?php } else { ?>
                            <tr id="<?php echo $dno; ?>">
                            <?php
                        }
                            ?>
                            <td align="left" style='vertical-align: top;'><?php echo $ro['courtno'] . "<br/>" . $ro['frm_time'];  ?></td>
                            <td align="left" style='vertical-align: top;'><?php echo str_replace(",", "<br>", $ro['jnm']); ?></td>
                            </tr>
                        <?php
                        $sno++;
                    }
                        ?>
                </tbody>
            </table>

        </div>
        <div style="text-align: center;">
            <input name="prnnt1" type="button" id="prnnt1" value="Print" style="margin: 0 auto;">
        </div>
    <?php
} else {
    echo '<div style="text-align: center; margin: 0 auto;">No Records Found</div>';
}
    ?>


    </div>