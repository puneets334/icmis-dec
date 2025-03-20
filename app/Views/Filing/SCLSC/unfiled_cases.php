<?php
//var_dump($unfiled_cases);
if ($unfiled_cases) {
?>
    <div class="container-fluid">
        <section>
            <div class="row">
                <div class="col-12 mt-3 mb-1">
                    <h5 class="text-uppercase">Cases Pending For Filing</h5>
                </div>
            </div>
            <table class="table table-striped custom-table table-hover dt-responsive">
                <thead>
                    <tr>
                        <th>SNo.</th>
                        <th>SCLSC Diary No.</th>
                        <th>Petitioner Name</th>
                        <th>Repondent Name</th>
                        <th>View Details</th>
                    </tr>
                </thead>
                <tbody>
                <?= csrf_field() ?>
                    <?php
                    $sno = 1;
                    foreach ($unfiled_cases as $row) {
                        //echo $row['diary_no'];
                    ?>
                        <tr>
                            <td><?= $sno++ ?></td>
                            <td><?= $row['diary_no'] ?></td>
                            <td><?= $row['pet_name'] ?></td>
                            <td><?= $row['res_name'] ?></td>
                            <td id="d_<?= $row['diary_no'] ?>">
                                <button input="button" data-diary_no="<?= $row['diary_no'] ?>" class="unfiled_case_details_modal btn btn-info" name="unfiled_case_details_modal">View</button>
                             
                            </td>
                        </tr>
                    <?php
                    }

                    ?>

                </tbody>
            </table>
        </section>


    </div>
<?php
} else {
    echo "No Records Found";
}
?>
<script>
    
</script>