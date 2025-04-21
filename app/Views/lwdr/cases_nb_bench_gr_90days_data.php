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

</style>
<?php
if (!empty($case_result) && count($case_result) > 0 && is_array($case_result)) {
?>
    <h2 align="center">Cases Not Listed Before Any Bench Greater Than 90 Days</h2>
    <hr>
    
    <table id="unverified_matters" class="table table-striped table-bordered table-hover table-sm table-responsive">
            <thead>
                <tr>
                    <th style="width: 5%;" rowspan='1'>SNo.</th>
                    <th style="width: 5%;" rowspan='1'>Diary no.</th>
                    <th style="width: 10%;" rowspan='1'>Case No.</th>
                    <th style="width: 30%;" rowspan='1'>Cause Title</th>
                    <th style="width: 10%;" rowspan='1'>DA</th>
                    <th style="width: 10%;" rowspan='1'>user section</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $s_no = 1;
                foreach ($case_result as $result) {
                ?>
                    <tr>
                        <td><?= $s_no; ?></td>
                        <td><?php echo $result['diary_no'] . '/' . $result['diary_year']; ?></td>
                        <td><?php echo $result['reg_no_display']; ?></td>
                        <td><?php echo $result['pet_name'] . ' vs. ' . $result['res_name']; ?></td>
                        <td><?php echo $result['name'] . ' - ' . $result['empid']; ?></td>
                        <td><?php echo $result['user_section']; ?></td>
                    </tr>
                <?php
                    $s_no++;
                }
                ?>
            </tbody>
        </table>
<?php
} else {
    echo "<p id = 'para' style='text-align: center;color: #fff;font-size: 16px;font-weight: 600;background: #e53333;padding: 5px 8px;'>No Data Available!!!</p>";
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
                    title: 'cases_not_listed_before_any_bench_greater_than_90_days<?= date("d-m-Y h-i-s A") ?>',
                    filename: 'cases_not_listed_before_any_bench_greater_than_90_days<?= date("d-m-Y h-i-s A") ?>'
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    title: 'cases_not_listed_before_any_bench_greater_than_90_days<?= date("d-m-Y h-i-s A") ?>',
                    filename: 'cases_not_listed_before_any_bench_greater_than_90_days<?= date("d-m-Y h-i-s A") ?>'
                }
            ],
            "processing": true,
            "ordering": true,
            "paging": true
        });

        table.buttons().container().appendTo('#unverified_matters_wrapper .col-md-6:eq(0)');
    });
</script>
