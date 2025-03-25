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
<link rel="stylesheet" href="<?= base_url() ?>/assets/vendor/sweetalert2/sweetalert2.css">
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
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
                                    echo form_open('#', $attribute);?>
                                    <?= csrf_field() ?>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">

                                        <div class="active tab-pane">
                                            <h3 class="basic_heading"> Judge Not go before AOR MODULE </h3><br>
                                            <div class="row ">

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Judge</label>
                                                        <div class="col-sm-10">
                                                            <select id="judge" name="judge" class="custom-select rounded-0">
                                                                <option value="">Select Judge</option>
                                                                <?php foreach ($judge_list as $judge_val): ?>
                                                                    <option value="<?php echo $judge_val['jcode']; ?>"><?php echo $judge_val['jname']; ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">AOR</label>
                                                        <div class="col-sm-10">
                                                            <select id="aor" name="aor" class="custom-select rounded-0">
                                                                <option value="">Select</option>
                                                                <?php foreach ($get_bar as $get_bar_val): ?>
                                                                    <option value="<?php echo $get_bar_val['bar_id'] ?>"><?php echo $get_bar_val['name'] . " (" . $get_bar_val['aor_code'] . ")"; ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <center><input type="button" name="btngetr" id="btngetr" class="btn btn-primary" value="Submit"></center>
                                                </div>
                                            </div>
                                        </div>

                                        <hr><br>
                                        <div id class="">
                                            <h3 class="basic_heading"> ADVOCATE ON RECORD NOT GO BEFORE JUDGE<br>(As on <?php echo date('d-m-Y'); ?>) </h3><br>
                                            <!-- <div class="row mx-0"> -->
                                            <!-- <div class="col-md-12"> -->
                                            <div class="table-responsive">
                                                <table id="example1" class="table table-striped custom-table showData">
                                                    <thead>
                                                        <tr>
                                                            <th>Action</th>
                                                            <th>SrNo.</th>
                                                            <th>Judge</th>
                                                            <th>AOR Name</th>
                                                            <th>AOR Code</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $sno = 1;
                                                        foreach ($get_ntl_judge as $get_ntl_judge_val):

                                                            $sno1 = $sno % 2;
                                                            $jd_adv = $get_ntl_judge_val['org_judge_id'] . "_" . $get_ntl_judge_val['org_advocate_id'];
                                                        ?>
                                                            <tr>
                                                                <td><a onclick="javascript:deleteRecord('<?php echo $jd_adv; ?>')" href="javascript:void(0)"><button class="btn btn-danger btn-sm" type="button"><i class="fas fa-trash" aria-hidden="true"></i></button></a></td>
                                                                <td><?php echo $sno; ?></td>
                                                                <td><?php echo $get_ntl_judge_val['jname']; ?></td>
                                                                <td><?php echo $get_ntl_judge_val['name']; ?></td>
                                                                <td><?php echo $get_ntl_judge_val['aor_code']; ?></td>
                                                            </tr>
                                                        <?php $sno++;
                                                        endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <!-- </div> -->
                                            <!-- </div>  -->
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

<script src="<?= base_url() ?>/assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>
<script type="text/javascript">
    $(document).on("click", "#btngetr", function() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        var judge = $("#judge").val();
        var aor = $("#aor").val();

        if (judge == 0) {
            alert("Please Select Judge Name")
            $("#judge").focus()
            return false
        }
        if (aor == 0) {
            alert("Please Select AOR")
            $("#aor").focus()
            return false
        }

        $.ajax({
            url: "<?php echo base_url('Coram/Aor/insert_aor/'); ?>",
            type: "post",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                judge: judge,
                aor: aor
            },
            success: function(result) {
                updateCSRFToken();
                Swal.fire({
                    text: result,
                    icon: "success",
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.reload();
                    }
                });
             
            },
            error: function() {
                updateCSRFToken();
                alert('Error while saving data.');
            }
        });
    });

    function deleteRecord(dno) {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?php echo base_url('Coram/Aor/ntl_judge_delete_response/'); ?>",
                    type: "post",
                    data: {
                        CSRF_TOKEN: csrf,
                        dno: dno
                    },
                    success: function(response) {
                        if (response == 1) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Record has been deleted.",
                                icon: "success"
                            }).then(() => {
                                window.location.reload(); // Reload the page after deletion
                            });
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: "Record could not be deleted.",
                                icon: "error"
                            }).then(() => {
                                window.location.reload(); // Reload the page even on failure
                            });
                        }
                        updateCSRFToken();
                    },
                    error: function() {
                        Swal.fire({
                            title: "Error!",
                            text: "There was an error while deleting the record.",
                            icon: "error"
                        }).then(() => {
                            window.location.reload(); // Reload the page even if there's an error
                        });
                        updateCSRFToken();
                    }
                });
            }
        });
    }

    $(function() {
        $("#example1").DataTable({
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>


<? ///=view('sci_main_footer') 
?>