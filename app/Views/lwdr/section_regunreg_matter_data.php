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

    div.dataTables_wrapper div.dataTables_filter {
    margin-top: -61px;
}
</style>
<?php

if (is_array($case_result_registered)) {
?>


    <div id="printable" class="box-body">

        <h3 style="text-align: center;">SECTION <?php echo $target; ?> YEAR WISE REGISTERED MATTERS AS ON : <strong><?= @$forDecidedDate ?></strong> <?php echo date("d-m-Y"); ?></h3>

        <table id="reportTable1" class="table table-striped table-hover table-bordered">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>D.Year.</th>
                    <th>No. of matters</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $s_no = 1;
                foreach ($case_result_registered as $result) {
                ?>
                    <tr>

                        <td><?php echo $s_no; ?></td>
                        <td><?php echo $result['diary_year']; ?></td>
                        <td><a href="<?= base_url(); ?>/lwdr/section_reg2?d_year=<?php echo $result['diary_year']; ?>&sect=<?php echo $target; ?>" target="_blank"><?php echo $result['numb']; ?></td>

                    </tr>
                <?php
                    $s_no++;
                }
                ?>
            </tbody>
        </table>
    </div>
    <div id="printable2" class="box-body">
        <h3 style="text-align: center;"> SECTION <?php echo $target; ?> STATEWISE REGISTERED MATTERS AS ON : <strong><?= @$forDecidedDate ?></strong> <?php echo date("d-m-Y"); ?></h3>

        <table width="90%" id="reportTable2" class="table table-striped table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>STATE</th>
                                    <th>COURT</th>
                                    <th>No. of matters</th>

                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $s_no = 1;
                                foreach ($case_state_registered as $result) {
                                ?>
                                    <tr>

                                        <td><?php echo $s_no; ?></td>
                                        <td><?php echo $result['state']; ?></td>
                                        <td><?php echo $result['agency_name']; ?></td>
                                        <td><a href=" <?= base_url(); ?>/lwdr/state_reg2?state=<?php echo $result['state']; ?>&sect=<?php echo $target; ?>&court=<?php echo $result['agency_name']; ?>" target="_blank"><?php echo $result['total_pendency']; ?></a></td>

                        </tr>
                        <?php
                                $s_no++;
                            }
                        ?>
        </tbody>
        </table>
    </div>
    <?php
    if (!empty($case_result_unregistered)) {
    ?>

        <div id="printable1" class="box-body">
            <h3 style="text-align: center;"> SECTION <?php echo $target; ?> YEAR WISE UNREGISTERED MATTERS AS ON : <strong><?= @$forDecidedDate ?></strong> <?php echo date("d-m-Y"); ?></h3>

            <table width="90%" id="reportTable2" class="table table-striped table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>D.Year.</th>
                                    <th>No. of matters</th>

                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $s_no = 1;
                                foreach ($case_result_unregistered as $result) {
                                ?>
                                    <tr>

                                        <td><?php echo $s_no; ?></td>
                                        <td><?php echo $result['diary_year']; ?></td>
                                        <td><a href=" <?= base_url(); ?>/lwdr/section_unreg2?d_year=<?php echo $result['diary_year']; ?>&sect=<?php echo $target; ?>" target="_blank"><?php echo $result['numb']; ?></a></td>

                </tr>
            <?php
                                    $s_no++;
                                }
            ?>
            </tbody>
            </table>
        </div>
    <?php
    }

    if (!empty($case_state_unregistered)) {
    ?>

        <div id="printable2" class="box-body">
            <h3 style="text-align: center;"> SECTION <?php echo $target; ?> STATEWISE UNREGISTERED MATTERS AS ON : <strong><?= @$forDecidedDate ?></strong> <?php echo date("d-m-Y"); ?></h3>

            <table width="90%" id="reportTable2" class="table table-striped table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>STATE</th>
                                    <th>COURT</th>
                                    <th>No. of matters</th>

                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $s_no = 1;
                                foreach ($case_state_unregistered as $result) {
                                ?>
                                    <tr>

                                        <td><?php echo $s_no; ?></td>
                                        <td><?php echo $result['state']; ?></td>
                                        <td><?php echo $result['agency_name']; ?></td>
                                        <td><a href=" <?= base_url(); ?>/lwdr/state_unreg2?state=<?php echo $result['state']; ?>&sect=<?php echo $target; ?>&court=<?php echo $result['agency_name']; ?>" target="_blank"><?php echo $result['total_pendency']; ?></a></td>

                </tr>
            <?php
                                    $s_no++;
                                }
            ?>
            </tbody>
            </table>
        </div>
<?php
    }
}
?>


<script>
    $(function() {
        var table = $("#reportTable1 , #reportTable2").DataTable({
            "responsive": true,
            "searching": true,
            "lengthChange": false,
            "autoWidth": false,
            "pageLength": 20,
            "processing": true,
            "ordering": true,
            "paging": true
        });
    });
</script>