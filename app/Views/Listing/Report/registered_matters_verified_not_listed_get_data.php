<?= view('header') ?>
<style>
    div.dataTables_wrapper div.dataTables_filter label {
        display: flex;
        justify-content: end;
    }

    div.dataTables_wrapper div.dataTables_filter label input.form-control {
        width: auto !important;
        padding: 4px;
    }
</style>
<div class="table-responsive">
    <h5 text-center>Registered & Verified Matters Which Are Not Listed</h5>
    <table id="example1" class="table table-striped custom-table">
        <thead>
            <tr>
                <th>SNo.</th>
                <th>Caseno@DNo</th>
                <th>Cause Title</th>
                <th>Filing Date</th>
                <th>Registration Date</th>
                <th>DA[Section]</th>
            </tr>
        </thead>
        <?php
        if (!empty($data)) {
            foreach ($data as $index => $row) {
        ?>
                <tbody>
                    <tr>
                        <td>' . ($index + 1) . '</td>
                        <td>' . $row['reg_no'] . '<br>Diary No. ' . $row['diary_no'] . '</td>
                        <td>' . $row['pet_name'] . '<br>vs<br>' . $row['res_name'] . '</td>
                        <td>' . $row['diary_date'] . '</td>
                        <td>' . $row['reg_date'] . '</td>
                        <td>' . $row['daname'] . '<br>Section: ' . $row['section'] . '</td>
                    </tr>;
                <?php
            }
        } else {
                ?>
                <tr>
                    <td colspan="6" style="text-align:center;">No Record Found</td>
                </tr>
            <?php } ?>
                </tbody>
    </table>
</div>
<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print"]
        });
    });
</script>