<div id="prnnt" class="mt-4">
    <h3><?php echo $h3_head; ?></h3>
    <?php
    if (count($get_ct_listed_disposed) > 0) {
    ?>
        <table class="table table-striped custom-table">
            <thead>
                <tr>
                    <th>SrNo.</th>
                    <th>Case Type</th>
                    <th>Listed Misc</th>
                    <th>Listed Regular</th>
                    <th>Listed Total</th>
                    <th>Disposed Misc</th>
                    <th>Disposed Regular</th>
                    <th>Disposed Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sno = 1;
                $total_listed = 0;
                $total_disposed = 0;
                foreach ($get_ct_listed_disposed as $ro) {
                    $sno1 = $sno % 2;
                    if ($sno1 == '1') { ?>
                        <tr id="<?php // echo $dno; 
                                ?>">
                        <?php } else { ?>
                        <tr id="<?php // echo $dno; 
                                ?>">
                        <?php
                    }
                        ?> <td><?php echo $sno; ?></td>
                        <td><?php echo $ro['short_description']; ?></td>
                        <td><?php
                            echo "<a data-animation='fade' data-reveal-id='myModal' onclick='return call_fcs($ro[casecode],1);' href='#'>" . $ro['listed_misc_main'] . "</a> (+ ";
                            echo "<a data-animation='fade' data-reveal-id='myModal' onclick='return call_fcs($ro[casecode],2);' href='#'>" . $ro['listed_misc_conn'] . "</a>)";
                            ?></td>
                        <td align="left" style='vertical-align: top;'><?php
                                                                        echo "<a data-animation='fade' data-reveal-id='myModal' onclick='return call_fcs($ro[casecode],3);' href='#'>" . $ro['listed_regular_main'] . "</a> (+ ";
                                                                        echo "<a data-animation='fade' data-reveal-id='myModal' onclick='return call_fcs($ro[casecode],4);' href='#'>" . $ro['listed_regular_conn'] . "</a>)";
                                                                        ?></td>
                        <td align="left" style='vertical-align: top;'><?php
                                                                        echo "<a data-animation='fade' data-reveal-id='myModal' onclick='return call_fcs($ro[casecode],5);' href='#'>" . $ro['listed_total_main'] . "</a> (+ ";
                                                                        echo "<a data-animation='fade' data-reveal-id='myModal' onclick='return call_fcs($ro[casecode],6);' href='#'>" . $ro['listed_total_conn'] . "</a>)";
                                                                        ?></td>
                        <td align="left" style='vertical-align: top;'><?php
                                                                        echo "<a data-animation='fade' data-reveal-id='myModal' onclick='return call_fcs($ro[casecode],7);' href='#'>" . $ro['disposed_misc_main'] . "</a> (+ ";
                                                                        echo "<a data-animation='fade' data-reveal-id='myModal' onclick='return call_fcs($ro[casecode],8);' href='#'>" . $ro['disposed_misc_conn'] . "</a>)";
                                                                        ?></td>
                        <td align="left" style='vertical-align: top;'><?php
                                                                        echo "<a data-animation='fade' data-reveal-id='myModal' onclick='return call_fcs($ro[casecode],9);' href='#'>" . $ro['disposed_regular_main'] . "</a> (+ ";
                                                                        echo "<a data-animation='fade' data-reveal-id='myModal' onclick='return call_fcs($ro[casecode],10);' href='#'>" . $ro['disposed_regular_conn'] . "</a>)";
                                                                        ?></td>
                        <td align="left" style='vertical-align: top;'><?php
                                                                        echo "<a data-animation='fade' data-reveal-id='myModal' onclick='return call_fcs($ro[casecode],11);' href='#'>" . $ro['disposed_total_main'] . "</a> (+ ";
                                                                        echo "<a data-animation='fade' data-reveal-id='myModal' onclick='return call_fcs($ro[casecode],12);' href='#'>" . $ro['disposed_total_conn'] . "</a>)";

                                                                        $total_listed += $ro['listed_total_main'] + $ro['listed_total_conn'];
                                                                        $total_disposed += $ro['disposed_total_main'] + $ro['disposed_total_conn'];
                                                                        ?></td>
                        </tr>
                    <?php
                    $sno++;
                }
                    ?>
            </tbody>
        </table>
        <div>Total Listed : <?php echo $total_listed; ?>
            Total Disposed : <?php echo $total_disposed; ?>
        </div>
    <?php
    } else {
        echo "No Recrods Found";
    }
    ?>

</div>
<br />
<div id="newcs" style="display:none; margin-top:5%;" class="modal fade">
    <div class="container">
        <table width="100%" border="0" style="margin:0 0 0px; border-collapse: collapse">
            <tr style="background-color: #A9A9A9;">
                <td align="center">
                    <b>
                        <font color="black" style="font-size:14px;">Case Type Wise Listed/Disposed Matters</font>
                    </b>
                </td>
                <td>
                    <input style="float:right;" type="button" name="close_b" id="close_b" value="CLOSE WINDOW" onclick="close_wcs();" />
                </td>
            </tr>
        </table>
        
    </div>
    <div class="container">
        <div id="newcs123" style="overflow:auto; background-color: #FFF;"> </div>
        <!-- <input name="prnnt1" type="button" id="prnnt2" value="Print"> -->
    </div>
    <div class="container">
        <div id="newcs1" align="center">
            <table border="0" width="100%">
                <tr>
                    <td align="center" width="250px">
                        <input name="prnnt1" type="button" id="prnnt2" value="Print">
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div style="width: 100%; padding-bottom:1px; background-color: #ddf1f9; text-align: center; border-top: 1px solid #000; position: fixed; left: 0; right: 0; z-index: 0; display:block;">
    <span id="toggle_hw" style="color: #0066cc; font-weight: bold; cursor: pointer; padding-right: 1px;">
    </span>
    <input name="prnnt1" type="button" id="prnnt1" value="Print">
</div>

<?php //} 
?>