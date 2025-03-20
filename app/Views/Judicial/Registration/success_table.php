<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th><b>Diary No</b></th>
                                    <th><b>Status</b></th>
                                    <th><b>Remarks</b></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($data as $dno => $row) { ?>
                                    <tr>
                                        <td><?= $dno; ?></td>
                                        <td><?= ($row['success']) ? 'Success' : 'Error' ?></td>
                                        <td><?= $row['message'] ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>