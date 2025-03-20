<?php if (!empty($serve_status)) { ?>
    <CENTER><?php echo $title ?> </CENTER>
    <!--<input type="button" onclick="printDiv('r')" value="Print" class="btn btn-primary quick-btn" />-->
    
    <div class="table-responsive" id="r">
        <table id="example1" class="table table-striped custom-table">
            <thead>
                <tr>
                    <th width="5%">SrNo.</td>
                    <th width="10%">Diary No</td>
                    <th width="20%">Reg No.</td>
                </tr>
            </thead>
            <tbody>
                <?php

                $sno = 1;

                foreach ($serve_status as $ro) {
                    $sno1 = $sno % 2;
                    $dno = $ro['diary_no'];
                    if (empty($ro['reg_no_display'])) {
                        $fil_no_print = "Unregistred";
                    } else {
                        $fil_no_print = $ro['reg_no_display'];
                    } ?>
                    <tr id="<?php echo $dno; ?>">
                        <td align="left" style='vertical-align: top;'><?php echo $sno; ?></td>
                        <td align="left" style='vertical-align: top;'><?php echo substr_replace($ro['diary_no'], '/', -4, 0); ?></td>
                        <td align="left" style='vertical-align: top;'><?php echo $fil_no_print; ?></td>
                    </tr>
                <?php
                    $sno++;
                }
                ?>
            </tbody>
        </table>
    </div>
<?php
} else { ?>
    <div class="mt-26 red-txt center">No Recrods Found</div>
<?php }
?>
<script>
    $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        "searching": false,
       buttons: [
            {
                extend: 'print',
                text: 'Print',
                className: 'btn-primary quick-btn',
                customize: function(win) {
                    $(win.document.body).find('h1').remove();
                }
            }
        ],
    });
    $('.buttons-print').removeClass('btn-secondary');
</script>

