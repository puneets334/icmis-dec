<div>
<div id="prnnt" class="text-center">
    <h3>Case Allocation Report for Dated <?php echo $list_dt; ?> (<?php echo $mainhead_descri; ?>)</h3>

    <?php if (count($result_array) > 0) { ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="font-weight: bold; color: #dce38d;background: #918788;">Court No.</th>
                        <th style="font-weight: bold; color: #dce38d;background: #918788;">Hon'ble Judges</th>
                        <th style="font-weight: bold; color: #dce38d;background: #918788;">Fixed</th>
                        <?php if ($mainhead == "M") { ?>
                            <th style="font-weight: bold; color: #dce38d;background: #918788;">Fresh</th>
                        <?php } ?>
                        <th style="font-weight: bold; color: #dce38d;background: #918788;">Other</th>
                        <th style="font-weight: bold; color: #dce38d;background: #918788;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sno = 1;
                    $fd_t = 0;
                    $fr_t = 0;
                    $ors_t = 0;
                    $ttt_t = 0;
                    foreach ($result_array as $row) { 
                        $sno1 = $sno % 2;   
                        if($sno1 == '1'){ 
                        ?>

                        <tr>
                            <td style="vertical-align: top;background: #ececec;"><?php echo $row['courtno']; ?></td>
                            <td style="vertical-align: top;background: #ececec;"><?php echo str_replace(",", "<br/>", $row['jnm']); ?></td>
                            <?php
                            $row1 = $ReportModel->get_m_data($mainhead, $board_type, $list_dt, $row["jcd"], $row["id"]);

                            if (!empty($row1)) { 
                            ?>
                                <td class="text-right" style="vertical-align: top;background: #ececec;"><?php echo $row1['fd'];
                                                                                    $fd_t += $row1['fd'] ?></td>
                                <?php if ($mainhead == "M") { ?>
                                    <td class="text-right" style="vertical-align: top;background: #ececec;"><?php echo $row1['fr'];
                                                                                        $fr_t += $row1['fr']; ?></td>
                                <?php } ?>
                                <td class="text-right" style="vertical-align: top;background: #ececec;"><?php echo $row1['ors'];
                                                                                    $ors_t += $row1['ors']; ?></td>
                                <td class="text-right" style="vertical-align: top;background: #ececec;"><?php echo $row1['ttt'];
                                                                                    $ttt_t += $row1['ttt']; ?></td>
                            <?php
                            } ?>
                        </tr>
                        
                           
                            <?php } else { ?>
                                <tr>
                            <td style="vertical-align: top;background: #f6e0f3;"><?php echo $row['courtno']; ?></td>
                            <td style="vertical-align: top;background: #f6e0f3;"><?php echo str_replace(",", "<br/>", $row['jnm']); ?></td>
                            <?php
                            $row1 = $ReportModel->get_m_data($mainhead, $board_type, $list_dt, $row["jcd"], $row["id"]);

                            if (!empty($row1)) { 
                            ?>
                                <td class="text-right" style="vertical-align: top;background: #f6e0f3;"><?php echo $row1['fd'];
                                                                                    $fd_t += $row1['fd'] ?></td>
                                <?php if ($mainhead == "M") { ?>
                                    <td class="text-right" style="vertical-align: top;background: #f6e0f3;"><?php echo $row1['fr'];
                                                                                        $fr_t += $row1['fr']; ?></td>
                                <?php } ?>
                                <td class="text-right" style="vertical-align: top;background: #f6e0f3;"><?php echo $row1['ors'];
                                                                                    $ors_t += $row1['ors']; ?></td>
                                <td class="text-right" style="vertical-align: top;background: #f6e0f3;"><?php echo $row1['ttt'];
                                                                                    $ttt_t += $row1['ttt']; ?></td>
                            <?php
                            } ?>
                        </tr>
                
            <?php        
            }     
            ?>

                            <?php
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
   
</div>
<?php if (count($result_array) > 0) { ?>
    <div style="text-align: center;">
    <button class="btn btn-primary ml-2" id="prnnt1">Print</button>
        </div>

    <?php } ?>