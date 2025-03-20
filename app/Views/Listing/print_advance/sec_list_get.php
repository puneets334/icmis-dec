<style>
    body {
        font-size: 12px;
    }

    th,
    td {
        padding: 5px;
        text-align: left;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background-color: #f2f2f2;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 text-right">
                <?php if (!empty($filePath) && !file_exists($filePath)) { ?>
                    <button class="btn btn-primary" name="prnnt1" type="button" id="ebublish">e-Publish</button>
                <?php } else { ?>
                    <h3 class="text-success">Already Published</h3>
                <?php } ?>
            </div>

            <div class="col-md-6">
                <button class="btn btn-primary" name="prnnt1" type="button" id="prnnt1">Print</button>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div id="prnnt">
                            <div align="center" style="font-size:12px;">
                                <img src="<?= base_url('images/scilogo.png') ?>" width="50" height="80" />
                                <br />SUPREME COURT OF INDIA<br />
                                <b>SECTION LIST<br /><br />DATE OF LISTING : <?= date('d-m-Y', strtotime($list_date)) ?></b>
                            </div>

                            <table border="1">
                                <thead>
                                    <tr>
                                        <th colspan="4" style="text-align: center;">SECTION LIST</th>
                                    </tr>
                                    <tr>
                                        <th>SNo.</th>
                                        <th>Case No.</th>
                                        <th>Petitioner / Respondent</th>
                                        <th>Petitioner/Respondent Advocate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($results)): ?>
                                        <?php
                                        $sno = 1;
                                        foreach ($results as $row):
                                            $pet_name = ($row->pno > 1) ? $row->pet_name . " AND ANR." : $row->pet_name;
                                            $res_name = ($row->rno > 1) ? $row->res_name . " AND ORS." : $row->res_name;
                                            $case_no = $row->short_description . "-" . ltrim($row->active_fil_no, '0') . "/" . $row->active_reg_year;
                                        ?>
                                            <tr>
                                                <td><?= $sno++ ?></td>
                                                <td><?= $case_no ?></td>
                                                <td><?= $pet_name ?> / <?= $res_name ?></td>
                                                <td>
                                                    <?= $row->listed ? "<span style='color:red;'>Connected</span><br/>" : "" ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="fa-2x text-center text-danger">No records found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>