<?= view('header') ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title"> Cases to be listed in Chamber Judge </h3>
                            </div>
                        </div>
                    </div>
                    <!-- Main content start -->
                    <div class="col-md-12">
                        <div class="card-body">
                            <form method="post" action="">
                                <div id="dv_content1">
                                    <div>
                                        <?php if (!empty($cases)) { ?>
                                            <table align='center' class="table_tr_th_w_clr c_vertical_align">
                                                <tr>
                                                    <th>
                                                        SNo.
                                                    </th>
                                                    <th>
                                                        Diary No
                                                    </th>
                                                    <th>
                                                        Total Days from <?php echo date('d-m-Y') ?>
                                                    </th>
                                                </tr>

                                                <?php
                                                $sno = 1;
                                                foreach ($cases as $row) {
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo $sno; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $row['diary_no']; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo isset($row['diff_days']) ? $row['diff_days'] : ''; ?>
                                                        </td>
                                                    </tr>
                                                <?php
                                                    $sno++;
                                                }
                                                ?>
                                            </table>
                                        <?php
                                        } else {
                                        ?>
                                            <div style="text-align: center"><h3>No Records Found</h3></div>
                                        <?php
                                        }


                                        ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- Main content end -->
                </div> <!-- /.card -->
            </div>
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>