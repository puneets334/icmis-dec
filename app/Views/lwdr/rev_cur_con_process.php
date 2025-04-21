<style>
    table.dataTable>thead .sorting,
    table.dataTable>thead {
        background-color: #0d48be !important;
        color: #fff !important;
        white-space: nowrap;
    }

    table.dataTable>thead .sorting_disabled,
    table.dataTable>thead {
        background-color: #0d48be !important;
        color: #fff !important;
        white-space: nowrap;
    }

    table tfoot tr th {
        background-color: #0d48be !important;
        color: #fff !important;
        white-space: nowrap;
    }

    .dataTables_filter {
        margin-top: 10px;
    }
</style>

<?php
if (count($summary_details) >= 1) {
?>
    <h2 align="center"><?= "List of curative, review and contempt cases where category is not updated as on " . date("d-m-y h:i:sa") ?></h2>
    <hr>
    <div class="row table-responsive" style="width:100%">
        <table id="unverified_matters" class="table table-striped table-bordered table-hover table-sm" style="width:100%">
            <thead>
               <tr>
                    <th>SNo.</th>
                    <th>Diary No</th>
                    <th>Case No</th>
                    <th>Cause Title</th>
                    <th>Section Name</th>
                    <th>EmpId#DA Name</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $sno = 1;
                foreach ($summary_details as $data) {

                    ?>
                   <tr>
                            <td><?= $sno++; ?></td>
                            <td><?= $data['diary_no'];?></td>
                            <td><?= $data['case_no'];?></td>
                            <td><?= $data['cause_title'];?></td>
                            <td><?= $data['section_name'];?></td>
                            <td><?= $data['daname'];?></td>
                          </tr>
                    
                    <?php
                }
                ?>

            </tbody>
        </table>
    </div>
<?php
} else {
    echo "<p id = 'para' style='text-align:center;color: #e53333;font-size: 19px;font-weight: 600;'>No data Available!!!</p>";
}
?>


<script>
    $(function() {
        var table = $("#unverified_matters").DataTable({
            "responsive": true,
            "searching": true,
            "lengthChange": false,
            "autoWidth": false,
            "pageLength": 20,
            "buttons": [{
                    extend: 'excel',
                    title: 'rev_cur_con_process<?= date("d-m-Y_h-i-s_A") ?>',
                    filename: 'rev_cur_con_process<?= date("d-m-Y_h-i-s_A") ?>'
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'Orientation',
                    pageSize: 'LEGAL',
                    title: 'rev_cur_con_process<?= date("d-m-Y h:i:s A") ?>',
                    filename: 'rev_cur_con_process<?= date("d-m-Y_h-i-s_A") ?>'
                }
            ],
            "processing": true,
            "ordering": true,
            "paging": true
        });

        table.buttons().container().appendTo('#unverified_matters_wrapper .col-md-6:eq(0)');
    });
</script>