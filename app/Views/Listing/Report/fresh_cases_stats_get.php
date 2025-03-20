<?php if (count($result_array) > 0) { ?>
    <div id="prnnt" style="text-align: center;">
        <h3 style="text-align:center;">Freshly Filed Cases Listed & left over after allocation</h3>
        <table id="customers1" class="table table-striped">
            <!--<table align="left" width="100%" border="0px;" style=" padding: 10px; font-size:13px; table-layout: fixed;">-->

            <tr>
                <td>SrNo.</td>
                <td>Date of Listing</td>
                <td>Matters Available</td>
                <td>Matters Listed</td>
                <td>Matters Left after allocation</td>
                <td>No. of Courts</td>
            </tr>
            <?php
            $sno = 1;
            foreach ($result_array as $ro) {
                $sno1 = $sno % 2;
                if ($sno1 == '1') { ?>
                    <tr>
                    <?php } else { ?>
                    <tr>
                    <?php
                }
                    ?>
                    <td><?php echo $sno; ?></td>
                    <td><?php echo date('d-m-Y', strtotime($ro['next_dt'])); ?></td>
                    <td><?php echo $ro['listed'] + $ro['eliminated']; ?></td>
                    <td><?php echo $ro['listed']; ?></td>
                    <td><?php echo $ro['eliminated']; ?></td>
                    <td><?php echo $ro['Court'];  ?></td>
                    </tr>
                <?php
                $sno++;
            }
                ?>
        </table>
        
    </div>
    <input name="prnnt1" type="button" id="prnnt1" value="Print">
<?php } else {
    echo 'No Recode Found';
} ?>