<div id="dv_content1">
    <div style="text-align: center">
        <h3>CASES VERIFICATION REPORT AS ON <?PHP date('d-m-Y'); ?></h3>
        <?php if (count($result_array) > 0) { ?>
            <table class="table table-striped custom-table">
                <tr style="background: #918788;">
                    <td>SNo</td>
                    <td>Section Name</td>
                    <td>User Name</td>
                    <td>Employee ID</td>
                    <td>Verified</td>
                    <td>Not Verified</td>
                    <td>Total</td>
                </tr>
                <?php
                $sno = 1;
                $verfied = 0;
                $notverifyed = 0;
                $total = 0;
                $dno=1;
                foreach ($result_array as $ro) {
                    $sno1 = $sno % 2;
                    if ($sno1 == '1') { ?>
                        <tr style=" background: #ececec;" id="<?= $dno; ?>">
                        <?php } else { ?>
                        <tr style=" background: #f6e0f3;" id="<?= $dno; ?>">
                        <?php
                    }
                        ?>
                        <td><?= $sno; ?></td>
                        <td><?= $ro['section_name']; ?></td>
                        <td><?= $ro['name']; ?></td>
                        <td><?= $ro['empid']; ?></td>
                        <td><?= $ro['verifyed'];
                            $verfied += $ro['verifyed']; ?></td>
                        <td><?= $ro['notverifyed'];
                            $notverifyed += $ro['notverifyed']; ?></td>
                        <td><?= $ro['verifyed'] + $ro['notverifyed'];
                            $total += $ro['verifyed'] + $ro['notverifyed']; ?></td>
                        </tr>
                    <?php
                    $sno++;
                }
                    ?>
                    <tr>
                        <td COLSPAN="4"><b>TOTAL</b></td>
                        <td align="right" style='vertical-align: top;'><b><?= $verfied; ?></b></td>
                        <td align="right" style='vertical-align: top;'><b><?= $notverifyed; ?></b></td>
                        <td align="right" style='vertical-align: top;'><b><?= $total; ?></b></td>
                    </tr>
            </table>
        <?php
         } else {
            echo "No Recrods Found";
        }
         ?>
    </div>
    <div id="dv_res1"></div>
</div>