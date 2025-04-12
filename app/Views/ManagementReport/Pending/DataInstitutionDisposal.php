<?php if (is_array($report_data)) { ?>
    <div id="prnTable" class="container mt-4">
        <div class="text-center mb-3">
            <h5 class="text-primary">
                <?= $report_name ?> Report between:
                <?= date("d-m-Y", strtotime($firstDate)) . ' to ' . date("d-m-Y", strtotime($lastDate)); ?>
            </h5>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm text-center">
                <thead class="table-primary">
                    <tr>
                        <th colspan="4">Institution</th>
                        <th colspan="4">Disposal</th>
                    </tr>
                    <tr>
                        <th>Admission</th>
                        <th>Regular</th>
                        <th>Civil</th>
                        <th>Criminal</th>
                        <th>Admission</th>
                        <th>Regular</th>
                        <th>Civil</th>
                        <th>Criminal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report_data as $row): ?>
                        <tr class="text-primary">
                            <td><?= $row['misc_institution'] ?></td>
                            <td><?= $row['reg_institution'] ?></td>
                            <td><?= $row['civil_institution'] ?></td>
                            <td><?= $row['criminal_institution'] ?></td>
                            <td><?= $row['misc_dispose'] ?></td>
                            <td><?= $row['reg_dispose'] ?></td>
                            <td><?= $row['civil_dispose'] ?></td>
                            <td><?= $row['criminal_dispose'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

      
    </div>
    <div class="text-center mt-3">
            <button type="button" id="print1" class="btn btn-primary">PRINT</button>
        </div>
<?php
} else {
    echo "<div class='text-center mt-5'><h4 class='text-danger'>Record Not Found</h4></div>";
}
?>
