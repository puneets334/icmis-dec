<div id="prnnt" class="text-center">
    <h3>Case Allocation Report for Dated <?php echo $list_dt; ?> (<?php echo $mainhead_descri; ?>)</h3>

    <?php if (count($result_array) > 0) { ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Court No.</th>
                        <th>Hon'ble Judges</th>
                        <th>Fixed</th>
                        <?php if ($mainhead == "M") { ?>
                            <th>Fresh</th>
                        <?php } ?>
                        <th>Other</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sno = 1;
                    $fd_t = 0;
                    $fr_t = 0;
                    $ors_t = 0;
                    $ttt_t = 0;
                    foreach ($result_array as $row) { ?>
                        <tr>
                            <td><?php echo $row['courtno']; ?></td>
                            <td><?php echo str_replace(",", "<br/>", $row['jnm']); ?></td>
                            <?php
                            $row1 = $ReportModel->get_m_data($mainhead, $board_type, $list_dt, $row["jcd"], $row["id"]);

                            if (!empty($row1)) {
                            ?>
                                <td class="text-right" style="vertical-align: top;"><?php echo $row1['fd'];
                                                                                    $fd_t += $row1['fd'] ?></td>
                                <?php if ($mainhead == "M") { ?>
                                    <td class="text-right" style="vertical-align: top;"><?php echo $row1['fr'];
                                                                                        $fr_t += $row1['fr']; ?></td>
                                <?php } ?>
                                <td class="text-right" style="vertical-align: top;"><?php echo $row1['ors'];
                                                                                    $ors_t += $row1['ors']; ?></td>
                                <td class="text-right" style="vertical-align: top;"><?php echo $row1['ttt'];
                                                                                    $ttt_t += $row1['ttt']; ?></td>
                        </tr>
                <?php
                            }
                        }
                ?>
                </tbody>
                <tr style="font-weight:bold;">
                    <td style="text-align:right;" colspan="2">TOTAL</td>
                    <td align=right style="vertical-align: top;">
                        <?php
                        if ($ttt_t != 0) {
                            echo $fd_t . " (" . intval(($fd_t * 100) / $ttt_t) . "%)";
                        } else {
                            echo $fd_t . " (0%)";
                        }
                        ?>
                    </td>
                    <?php
                    if ($mainhead == "M") {
                    ?>
                        <td align=right style="vertical-align: top;">
                            <?php
                            if ($ttt_t != 0) {
                                echo $fr_t . " (" . intval(($fr_t * 100) / $ttt_t) . "%)";
                            } else {
                                echo $fr_t . " (0%)";
                            }
                            ?>
                        </td>
                    <?php
                    }
                    ?>
                    <td align=right style="vertical-align: top;">
                        <?php
                        if ($ttt_t != 0) {
                            echo $ors_t . " (" . intval(($ors_t * 100) / $ttt_t) . "%)";
                        } else {
                            echo $ors_t . " (0%)";
                        }
                        ?>
                    </td>
                    <td align=right style="vertical-align: top;"><?php echo $ttt_t; ?></td>
                </tr>

            </table>
        </div>
    <?php } else {
        echo '<p>No Record Found</p>';
    } ?>
    <div class="container-fluid fixed-bottom text-center py-1" style="z-index: 0;">
        <div class="d-flex justify-content-center">
            <!-- Optional buttons for notes or actions -->
            <!-- <button class="btn btn-link" id="sh4" onClick="toggle_note4(this.id)">Header Note</button>
        <button class="btn btn-link" id="sh5" onClick="toggle_note5(this.id)">Footer Note</button>
        <button class="btn btn-link" id="sh3" onClick="toggle_note3(this.id)">Drop Note</button> -->

            <!-- Print Button -->
            <button class="btn btn-primary ml-2" id="prnnt1">Print</button>
        </div>
    </div>
</div>