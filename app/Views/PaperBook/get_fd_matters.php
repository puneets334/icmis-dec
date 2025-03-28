<div class="table-responsive">
    <?php if ($results) { ?>

        <div class="text-center my-4">
            <span class="custom-text font-weight-no">Fixed Date Matters</span> Ready for Listing for
            <span class="custom-text font-weight-bold"><?= $formated_next_date ?></span> as on
            <span class="custom-text font-weight-bold"><?= date('d-m-Y H:i:s') ?></span>
        </div>
        <table id="example1" class="table table-striped custom-table">
            <thead>
                <tr>
                    <th>SNo.</th>
                    <th>RegNo @ Diary_no</th>
                    <th>Cause Title</th>
                    <th>Last Listed On </th>
                    <th>Last Coram</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sno = 1;
                foreach ($results as $rw) {
                ?>
                    <tr>
                        <td><?php echo $sno; ?></td>
                        <td><?php echo $rw['diary_no']; ?></td>
                        <td><?php echo $rw['ct']; ?></td>
                        <td><?php echo $rw['last_date']; ?></td>
                        <td><?php echo $rw['jname']; ?></td>
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
        <div class="mt-26 red-txt center">SORRY, NO RECORD FOUND!!!</div>
    <?php
    } ?>
</div>
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
                title: 'Fixed Date Matters Ready for Listing for <?= $formated_next_date ?> as on <?= date('d-m-Y H:i:s') ?>', // Ensuring no unwanted title appears
                customize: function(win) {
                    $(win.document.body).css('text-align', 'center'); // Align all content centrally
                }
            }
        ],
    });
    $('.buttons-print').removeClass('btn-secondary');
</script>