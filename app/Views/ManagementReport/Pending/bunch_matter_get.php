<div class="table-responsive">
    <?php if ($results) { ?>
        <div class="font-weight-bold text-center mt-26">
            <?php echo $mainhead_description; ?>, Bunch Matters as on <?php echo date('d-m-Y'); ?><br />(Group Having More Than <?php echo $grp_hv; ?> Cases)
        </div>
        <table id="example1" class="table table-striped custom-table">
            <thead>
                <tr>
                    <th width="5%">SNo.</th>
                    <?php if ($bunch_type == 1) { ?>
                        <th width="40%">Category</th>
                    <?php } ?>
                    <?php if ($bunch_type == 2) { ?>
                        <th width="25%">Diary No.</th>
                        <th width="25%">Registration No.</th>
                    <?php } ?>
                    <th width="15%">Bunch Count</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sno = 1;
                foreach ($results as $row) {
                    $dno = '';
                    if ($bunch_type == 2) {
                        $dno = $row['diary_no'];
                    }
                ?>
                    <tr id="<?php echo $dno; ?>">
                        <td><?php echo $sno; ?></td>
                        <?php if ($bunch_type == 2) { ?>
                            <td style="vertical-align: top;">
                                <?php echo substr_replace($row['diary_no'], '-', -4, 0); ?>
                            </td>
                            <td style="vertical-align: top;">
                                <?php
                                $m_f_filno = $row['active_fil_no'];
                                $m_f_fil_yr = $row['active_reg_year'];
                                $filno_array = explode("-", $m_f_filno);
                                if ($filno_array[1] == $filno_array[2]) {
                                    $fil_no_print = ltrim($filno_array[1], '0');
                                } else {
                                    $fil_no_print = ltrim($filno_array[1], '0') . "-" . ltrim($filno_array[2], '0');
                                }
                                if ($row['active_fil_no'] == "") {
                                    $comlete_fil_no_prt = "UnReg.";
                                } else {
                                    $comlete_fil_no_prt = $row['short_description'] . "-" . $fil_no_print . "/" . $m_f_fil_yr;
                                }
                                echo $comlete_fil_no_prt; ?>
                            </td>
                        <?php }
                        if ($bunch_type == 1) {
                        ?>
                            <td style="vertical-align: top;">
                                <?php echo $row['sub_name1']; ?>
                            </td>
                        <?php
                        }
                        ?>
                        <td style="vertical-align: top;">
                            <?php
                            echo "" . $row['cnt'] . " ";
                            $arr_ex = is_string($row['cdno']) ? explode(",", $row['cdno']) : [];
                            if ($row['cnt'] != 0) {
                                for ($i = 0; $i < count($arr_ex); $i++) {

                                    echo "<a data-toggle='modal' data-target='#exampleModal' onclick='return call_fcs($arr_ex[$i]);' href='#'>" . f_get_reg_no($arr_ex[$i]) . "</a>";
                                   
                                    if ((count($arr_ex) - 1) == $i) {
                                    } else {
                                        echo ", ";
                                    }
                                }
                            }
                            ?>
                        </td>
                    </tr>
                <?php
                    $sno++;
                }
                ?>
            </tbody>
        </table>
    <?php
    } else {
    ?>
        <div class="mt-26 red-txt center">No Recrods Found</div>
    <?php
    } ?>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modalXl modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Bunch Matters</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body left" id="modData">
      </div>
    </div>
  </div>
</div>

<script>
    $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        "searching": false,
        "buttons": [{
                extend: 'print',
                text: 'Print',
                className: 'btn-primary quick-btn',
                customize: function(win) {
                    $(win.document.body).find('h1').remove();
                }
            }]
    });
</script>