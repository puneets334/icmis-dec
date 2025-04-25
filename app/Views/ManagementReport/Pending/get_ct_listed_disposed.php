<div id="prnnt" class="mt-4">
    
    <div class="row mt-2">
        <div class="col-md-4 text-left ml-n4">
            <div class="main_prnt_header">
                <input name="prnnt1" type="button" id="prnnt1" value="Print" class="btn btn-primary bk_out">
            </div>
        </div>
        <div class="col-md-4">
            <h3 class="text-center" style="text-align:center"><?php echo $h3_head; ?></h3>
        </div>
        <div class="col-md-4"></div>
    </div>
    <?php
    if (count($get_ct_listed_disposed) > 0) {
    ?>
        <table align="left" width="100%" class="table table-striped custom-table" style="font-size:10px; table-layout: fixed;">
            <thead>
                <tr>
                    <th width="10%" align="left"style="font-weight: bold;">SrNo.</th>
                    <th width="30%" align="left" style="font-weight: bold;">Case Type</th>
                    <th width="10%" align="left" style="font-weight: bold;">Listed Misc</th>
                    <th width="10%" align="left" style="font-weight: bold;">Listed Regular</th>
                    <th width="10%" align="left" style="font-weight: bold;">Listed Total</th>
                    <th width="10%" align="left" style="font-weight: bold;">Disposed Misc</th>
                    <th width="10%" align="left" style="font-weight: bold;">Disposed Regular</th>
                    <th width="10%" align="left" style="font-weight: bold;">Disposed Total</th>
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
                        ?> <td align="left" style='vertical-align: top;'><?php echo $sno; ?></td>
                        <td align="left" style='vertical-align: top;'><?php echo $ro['short_description']; ?></td>
                        <td align="left" style='vertical-align: top;'><?php
                            echo "<a data-toggle='modal' data-target='#newcs'' onclick='return call_fcs($ro[casecode],1);' href='#'>" . $ro['listed_misc_main'] . "</a> (+ ";
                            echo "<a data-toggle='modal' data-target='#newcs' onclick='return call_fcs($ro[casecode],2);' href='#'>" . $ro['listed_misc_conn'] . "</a>)";
                            ?></td>
                        <td align="left" style='vertical-align: top;'><?php
                                                                        echo "<a data-toggle='modal' data-target='#newcs' onclick='return call_fcs($ro[casecode],3);' href='#'>" . $ro['listed_regular_main'] . "</a> (+ ";
                                                                        echo "<a data-toggle='modal' data-target='#newcs' onclick='return call_fcs($ro[casecode],4);' href='#'>" . $ro['listed_regular_conn'] . "</a>)";
                                                                        ?></td>
                        <td align="left" style='vertical-align: top;'><?php
                                                                        echo "<a data-toggle='modal' data-target='#newcs' onclick='return call_fcs($ro[casecode],5);' href='#'>" . $ro['listed_total_main'] . "</a> (+ ";
                                                                        echo "<a data-toggle='modal' data-target='#newcs' onclick='return call_fcs($ro[casecode],6);' href='#'>" . $ro['listed_total_conn'] . "</a>)";
                                                                        ?></td>
                        <td align="left" style='vertical-align: top;'><?php
                                                                        echo "<a data-toggle='modal' data-target='#newcs' onclick='return call_fcs($ro[casecode],7);' href='#'>" . $ro['disposed_misc_main'] . "</a> (+ ";
                                                                        echo "<a data-toggle='modal' data-target='#newcs' onclick='return call_fcs($ro[casecode],8);' href='#'>" . $ro['disposed_misc_conn'] . "</a>)";
                                                                        ?></td>
                        <td align="left" style='vertical-align: top;'><?php
                                                                        echo "<a data-toggle='modal' data-target='#newcs' onclick='return call_fcs($ro[casecode],9);' href='#'>" . $ro['disposed_regular_main'] . "</a> (+ ";
                                                                        echo "<a data-toggle='modal' data-target='#newcs' onclick='return call_fcs($ro[casecode],10);' href='#'>" . $ro['disposed_regular_conn'] . "</a>)";
                                                                        ?></td>
                        <td align="left" style='vertical-align: top;'><?php
                                                                        echo "<a data-toggle='modal' data-target='#newcs' onclick='return call_fcs($ro[casecode],11);' href='#'>" . $ro['disposed_total_main'] . "</a> (+ ";
                                                                        echo "<a data-toggle='modal' data-target='#newcs' onclick='return call_fcs($ro[casecode],12);' href='#'>" . $ro['disposed_total_conn'] . "</a>)";

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
    } else {?>
        <div class="mt-10 center">No Recrods Found</div>
    <?php }
    ?>

</div>
<br />
<!-- Modal -->
<div class="modal fade" id="newcs" tabindex="-1" role="dialog" aria-labelledby="newcsLabel" aria-hidden="true">
  <div class="modal-dialog modalXl modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newcsLabel">Case Type Wise Listed/Disposed Matters</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body left" id="modData">
        
      </div>
    </div>
  </div>
</div>

<!--<div style="width: 100%; padding-bottom:1px; background-color: #ddf1f9; text-align: center; border-top: 1px solid #000; position: fixed; left: 0; right: 0; z-index: 0; display:block;">
    <span id="toggle_hw" style="color: #0066cc; font-weight: bold; cursor: pointer; padding-right: 1px;">
    </span>
    <input name="prnnt1" type="button" id="prnnt1" value="Print">
</div>-->