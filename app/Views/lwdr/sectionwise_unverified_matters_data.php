
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
    /* .dataTables_filter
    {
        margin-top: -48px;
    } */
</style>

<?php
if ($num_row >= 1) {
?>
    <h2 align="center"><?= 'List of unverified matters of Section ' . $section_name . ' as on ' . date("d-m-Y h:i:s A") ?></h2>
    <hr>
    
    <div class="row table-responsive" style="width:97%">
    <table id="unverified_matters" class="table table-striped table-bordered table-hover table-sm" style="width:100%">
        <thead>
            
            <tr>
                <th>SNo.</th>
                <th>Diary No.</th>
                <th>Cause Title</th>
                <th>Empid#Da Name</th>
            </tr>
        </thead>
        <tbody>

            <?php
            $sno = 1;
            foreach ($details as $data) {
            ?>
                <tr>
                    <td style="width:5%;"><?= $sno++ ?></td>
                    <td style="width:12%;"><?= $data['diary_no']; ?></td>
                    <td style="width:45% !important;"><?= $data['title'] ?></td>
                    <td style="width:35%;"><?= $data['daname'] ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
    </div>
<?php
} else {
    echo "<p id='para'>No data Available!!!</p>";
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
            "buttons": [
                {
                    extend: 'excel',
                    title: 'Unverified_Matters_Section_<?= $section_name ?>_<?= date("d-m-Y_h-i-s_A") ?>',
                    filename: 'Unverified_Matters_Section_<?= $section_name ?>_<?= date("d-m-Y_h-i-s_A") ?>'
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'Orientation',
                    pageSize: 'LEGAL',
                    title: 'Unverified Matters of Section <?= $section_name ?> as on <?= date("d-m-Y h:i:s A") ?>',
                    filename: 'Unverified_Matters_Section_<?= $section_name ?>_<?= date("d-m-Y_h-i-s_A") ?>'
                }
            ],
            "processing": true,
            "ordering": true,
            "paging": true
        });

        table.buttons().container().appendTo('#unverified_matters_wrapper .col-md-6:eq(0)');
    });
</script>