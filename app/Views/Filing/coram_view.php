<?php if ($editcoram != 'editcoram') { ?>
    <?= view('header'); ?>

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
                    <?php if ($editcoram != 'editcoram') { ?>

                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">Filing</h3>
                                </div>
                                <?= view('Filing/filing_filter_buttons'); ?>
                            </div>
                        </div>
                        <?= view('Coram/coram_breadcrumb');
                        //= view('Filing/filing_breadcrumb');  
                        ?>
                        <!-- /.card-header -->
                    <?php } ?>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff;">
                                    <!-- <ul class="nav nav-pills">
                                            <li class="nav-item"><a id="coramA" class="nav-link active" href="#coram" data-toggle="tab">Coram</a></li>
                                            <li class="nav-item"><a id="nbj" class="nav-link" href="#not_before_judge" data-toggle="tab">Not before judges</a></li>
                                        </ul> -->
                                    <?php
                                    $attribute = array('class' => 'form-horizontal', 'name' => 'subordinate_court_details', 'id' => 'subordinate_court_details', 'autocomplete' => 'off');
                                    echo form_open('#', $attribute);

                                    ?>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">

                                        <div class="active tab-pane" id="coram">
                                            <h4 class="basic_heading"> List before/Not Before/Coram (Delete) </h4><br>
                                            <div class="row">
                                                <div class="col-12 text-center">
                                                    <?php
                                                    $diary_num = substr($_SESSION['filing_details']['diary_no'], 0, -4);
                                                    $diary_year = substr($_SESSION['filing_details']['diary_no'], -4);
                                                    ?>
                                                    Diary No. <?=$diary_num; ?>/<?=$diary_year; ?></br>
                                                    <?=strtoupper($_SESSION['filing_details']['pet_name']); ?></br>
                                                    Versus</br>
                                                    <?=strtoupper($_SESSION['filing_details']['res_name']); ?>
                                                </div>
                                            </div>
                                            <?php if (!empty($case_status) && isset($case_status[0]['c_status']) && $case_status[0]['c_status'] == 'P') { ?>
                                                <div class="row cus-mx-none">
                                                    <div class="col-md-12">
                                                        <div class="table-responsive">
                                                            <table id="example1" class="table table-striped custom-table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Action</th>
                                                                        <th>Before/Not before</th>
                                                                        <th>Hon. Judge</th>
                                                                        <th>Reason/Source</th>
                                                                        <th>Entry Date</th>
                                                                        <th>Updated By</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    foreach ($coram_detail as $coram_val) {
                                                                        if ($coram_val['notbef'] == 'N') {
                                                                            $notbef = 'Not before';
                                                                        }
                                                                        if ($coram_val['notbef'] == 'B') {
                                                                            $notbef = 'Before/SPECIAL BENCH';
                                                                        }
                                                                        if ($coram_val['notbef'] == 'C') {
                                                                            $notbef = 'Before Coram';
                                                                        }
                                                                    ?>
                                                                        <tr>
                                                                            <td>
                                                                                <a onclick="delete_before(<?php echo $coram_val['jcode'] . "," . $coram_val['diary_no'] . ",'" . $coram_val['notbef'] . "'" ?>)" href="javascript:void(0)"><button class="btn btn-danger btn-sm" type="button"><i class="fas fa-trash" aria-hidden="true"></i></button></a>
                                                                            </td>
                                                                            <td><?php echo $notbef; ?></td>
                                                                            <td><?php echo $coram_val['jname']; ?></td>
                                                                            <td><?php echo $coram_val['res_add']; ?></td>
                                                                            <td><?php echo $coram_val['entry_date']; ?></td>
                                                                            <td><?php echo $coram_val['update_by']; ?></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php if (!empty($case_status) && isset($case_status[0]['c_status'])) : ?>
                                                <?php if ($case_status[0]['c_status'] == 'D') : ?>
                                                    <center>
                                                        <div style="color:red;">!!!The Case is Disposed!!!</div>
                                                    </center>
                                                <?php endif; ?>
                                            <?php else : ?>
                                                <center>
                                                    <div style="color:gray;">No case status available.</div>
                                                </center>
                                            <?php endif; ?>


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


<!-- modal start -->
<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Reason for delete</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" name="" id="del_reason" class="form-control" placeholder="Enter Reason">
                <input type="hidden" name="" id="del_key_jcode">
                <input type="hidden" name="" id="del_key_diary_no">
                <input type="hidden" name="" id="del_key_notbef">
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" id="delete_entry" class="btn btn-info">Delete</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- modal end -->




<script type="text/javascript">
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });


    function delete_before(jcode, diary_no, notbef) {
        $('#modal-default').modal('toggle');

        $('#del_key_jcode').val(jcode);
        $('#del_key_diary_no').val(diary_no);
        $('#del_key_notbef').val(notbef);


    }

    $('#delete_entry').click(function() {
        if ($('#del_reason').val() == '') {
            alert('Please Enter Reason');
            $('#del_reason').focus();
        } else {

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();

            var del_key_jcode = $('#del_key_jcode').val();
            var del_key_diary_no = $('#del_key_diary_no').val();
            var del_key_notbef = $('#del_key_notbef').val();
            var del_reason = $('#del_reason').val();

            $.ajax({
                url: "<?php echo base_url('Filing/Coram/delete/'); ?>",
                type: "post",
                data: {
                    CSRF_TOKEN: csrf,
                    del_key_jcode: del_key_jcode,
                    del_key_diary_no: del_key_diary_no,
                    del_key_notbef: del_key_notbef,
                    del_reason: del_reason
                },
                success: function(result) {

                    var obj = JSON.parse(result);

                    if (obj.deleted) {
                        alert(obj.deleted);
                        window.location.href = '';
                    }

                    if (obj.jud_deleted) {
                        alert(obj.jud_deleted);
                        window.location.href = '';
                    }

                    $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                },
                error: function() {
                    $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });
        }
    });
</script>




<?php if ($editcoram != 'editcoram') { ?>

<?php  } ?>