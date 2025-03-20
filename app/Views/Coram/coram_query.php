<?php if ($Url_Coram != 'Url_Coram') { ?>
    <?= view('header') ?>

<?php  } ?>
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
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <?php if ($Url_Coram != 'Url_Coram') { ?>

                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">Coram</h3>
                                </div>
                                <div class="col-sm-2">
                                    <div class="custom_action_menu">
                                        <a href="<?= base_url() ?>/Filing/Diary"><button class="btn btn-success btn-sm" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i></button></a>
                                        <a href="<?= base_url() ?>/Filing/Diary/search"><button class="btn btn-primary btn-sm" type="button"><i class="fas fa-pencil" aria-hidden="true"></i></button></a>
                                        <a href="<?= base_url() ?>/Filing/Diary/deletion"><button class="btn btn-danger btn-sm" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?= view('Coram/coram_breadcrumb'); ?>
                        <!-- /.card-header -->

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header p-2" style="background-color: #fff;">
                                        <?php
                                        $attribute = array('class' => 'form-horizontal', 'name' => 'coram', 'id' => 'coram', 'autocomplete' => 'off');
                                        echo form_open('#', $attribute);

                                        ?>
                                    </div><!-- /.card-header -->
                                <?php } ?>

                                <div class="card-body">
                                    <div class="tab-content">
                                        <div id class="">
                                            <?php if ($Url_Coram != 'Url_Coram') { ?>

                                                <h3 class="basic_heading"> <?php echo "Diary No " . substr($diary_no, 0, -4) . '/' . substr($diary_no, -4); ?> </h3><br>
                                                <center>
                                                    <table>
                                                        <tr>
                                                            <th style="color:blue">
                                                                <center>
                                                                    <?php if (!empty($main_row) && isset($main_row[0]['pet_name'])) : ?>
                                                                        <?php echo htmlspecialchars($main_row[0]['pet_name']); ?>
                                                                    <?php else : ?>
                                                                        <div>No pet name available.</div>
                                                                    <?php endif; ?>
                                                                </center>

                                                                <center>
                                                                    <font style="color:black">Versus</font>
                                                                </center>
                                                                <center>
                                                                    <?php if (!empty($main_row) && isset($main_row[0]['res_name'])) : ?>
                                                                        <?php echo htmlspecialchars($main_row[0]['res_name']); ?>
                                                                    <?php else : ?>
                                                                        <div>No resource name available.</div>
                                                                    <?php endif; ?>
                                                                </center>

                                                            </th>
                                                            <?php if (!empty($main_row) && isset($main_row[0]['c_status']) && $main_row[0]['c_status'] == 'D') : ?>
                                                        <tr>
                                                            <th style="color:red;">
                                                                <center>!!!The Case is Disposed!!!</center>
                                                            </th>
                                                        </tr>
                                                    <?php endif; ?>

                                                    </tr>
                                                    </table>
                                                </center>
                                            <?php } ?>

                                            <!-- <div class="row"> -->
                                            <!-- <div class="col-md-12"> -->
                                            <?php //if ($main_row[0]['c_status'] == 'P') : 
                                            ?>

                                            <?php if (!empty($advocate_ntl_judge)) { ?>
                                                <div class="table-responsive">
                                                    <table id="example1" class="table table-striped custom-table_ showData">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="3">
                                                                    <center>AOR Not to List before Judge</center>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th>AOR Code</th>
                                                                <th>AOR Name</th>
                                                                <th>Hon. Judge</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $sno = 1;
                                                            foreach ($advocate_ntl_judge as $advocate_ntl_judge_val) :

                                                                if ($advocate_ntl_judge_val['display'] == 'Y') {
                                                                    $style = "style='color: green'";
                                                                } elseif ($advocate_ntl_judge_val['display'] == 'N') {
                                                                    $style = "style='color: red; display: none'";
                                                                }
                                                            ?>
                                                                <tr <?php echo $style; ?>>
                                                                    <td><?php echo $advocate_ntl_judge_val['aor_code']; ?></td>
                                                                    <td><?php echo $advocate_ntl_judge_val['name']; ?></td>
                                                                    <td><?php echo $advocate_ntl_judge_val['jname'] . " [" . $advocate_ntl_judge_val['abbreviation'] . "]"; ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php } ?>

                                            <?php if (!empty($party_ntl_judge)) { ?>
                                                <div class="table-responsive">
                                                    <table id="example1" class="table table-striped custom-table showData">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="2">
                                                                    <center>Department Not to List before Judge</center>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th>Department Name</th>
                                                                <th>Hon. Judge</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $sno = 1;
                                                            foreach ($party_ntl_judge as $party_ntl_judge_val) :

                                                                if ($party_ntl_judge_val['display'] == 'Y') {
                                                                    $style = "style='color: green'";
                                                                } elseif ($party_ntl_judge_val['display'] == 'N') {
                                                                    $style = "style='color: red; display: none'";
                                                                }
                                                            ?>
                                                                <tr <?php echo $style; ?>>
                                                                    <td><?php echo $party_ntl_judge_val['deptname']; ?></td>
                                                                    <td><?php echo $party_ntl_judge_val['jname'] . " [" . $party_ntl_judge_val['abbreviation'] . "]"; ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php } ?>

                                            <?php if (!empty($coram_detail_data)) { ?>
                                                <div class="table-responsive">
                                                    <table id="example1" class="table table-striped custom-table showData">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="5">
                                                                    <center>Already Entries of List before and not before</center>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th>Sr.</th>
                                                                <th>Before/Not before</th>
                                                                <th>Hon. Judge</th>
                                                                <th>Reason</th>
                                                                <th>Entry Date and Updated by</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $s = 1;
                                                            foreach ($coram_detail_data as $coram_detail_data_val) :

                                                                if ($coram_detail_data_val['notbef'] == 'N') {
                                                                    $notbef = 'Not before';
                                                                }
                                                                if ($coram_detail_data_val['notbef'] == 'B') {
                                                                    $notbef = 'Before/SPECIAL BENCH';
                                                                }
                                                                if ($coram_detail_data_val['notbef'] == 'C') {
                                                                    $notbef = 'Before Coram';
                                                                }

                                                                if ($coram_detail_data_val['display'] == 'Y') {
                                                                    $style = "style='color: green'";
                                                                } elseif ($coram_detail_data_val['display'] == 'N') {
                                                                    $style = "style='color: red; display: none'";
                                                                }
                                                            ?>
                                                                <tr <?php echo $style; ?>>
                                                                    <td><?php echo $s; ?></td>
                                                                    <td><?php echo $notbef; ?></td>
                                                                    <td><?php echo $coram_detail_data_val['jname']; ?></td>
                                                                    <td><?php echo $coram_detail_data_val['res_add']; ?></td>
                                                                    <td><?php echo $coram_detail_data_val['entry_date']; ?></td>
                                                                </tr>
                                                            <?php $s++;
                                                            endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php } else {
                                                echo "<div style=text-align:center;padding:10px;color:red><span id='edit_lb'></span>LIST BEFORE/NOT BEFORE/CORAM NOT FOUND</div>";
                                            } ?>


                                            <?php //endif; 
                                            ?>
                                            <!-- </div> -->
                                            <!-- </div> -->
                                        </div>
                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                                <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>


                        <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
</section>
<!-- /.content -->

<?php if ($Url_Coram != 'Url_Coram') { ?>

    <script type="text/javascript">
        $(function() {
            $("#example1").DataTable({
                "responsive": false,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
<?php  } ?>


<?php if ($Url_Coram != 'Url_Coram') { ?>
    <? //=view('sci_main_footer') 
    ?>
<?php  } ?>