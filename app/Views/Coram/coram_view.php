z<?php if ($editcoram != 'editcoram') { ?>
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
                    <?php if ($editcoram != 'editcoram') { ?>

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
                    <?php } ?>

                    <!-- /.card-header -->

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff;">
                                    <?php
                                    $attribute = array('class' => 'form-horizontal', 'name' => 'subordinate_court_details', 'id' => 'subordinate_court_details', 'autocomplete' => 'off');
                                    echo form_open('#', $attribute);

                                    ?>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">

                                        <div class="active tab-pane">
                                            <h4 class="basic_heading"> Coram Details </h4><br>
                                            <div class="row ">

                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label class="col-sm-4 col-form-label">Judge</label>
                                                        <div class="col-sm-8">
                                                            <select name="" multiple class="custom-select rounded-0" style="height:159%">
                                                                <option value="">Select Judge</option>
                                                                <?php $sno = 1;
                                                                foreach ($judge_list as $judge_val) : ?>
                                                                    <option id="jdg<?php echo $sno ?>" value="<?php echo $judge_val['jcode']; ?>"><?php echo $judge_val['jname']; ?></option>
                                                                <?php $sno++;
                                                                endforeach; ?>
                                                                <input type="hidden" value="<?= $sno ?>" id="total_jdg" />
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label class="col-sm-4 col-form-label">Save as</label>
                                                        <div class="col-sm-8">
                                                            <select name="" id="select_save_as" class="custom-select rounded-0">
                                                                <option value="">Select</option>
                                                                <option value="B">List Before Selected Judges FOR SPECIAL BENCH </option>
                                                                <option value="N">Not List Before Selected Judges</option>
                                                                <option value="C">List Before Selected Coram</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label class="col-sm-4 col-form-label">Reason for Entry</label>
                                                        <div class="col-sm-8">
                                                            <select name="" id="show_reason" class="custom-select rounded-0">
                                                                <option value="">Select</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <center><input type="button" name="" onclick="save_call()" class="btn btn-primary" value="Save"></center>
                                                </div>
                                            </div>
                                        </div>

                                        <hr><br>
                                        <div id class="">
                                            <h4 class="basic_heading"> View </h4><br>
                                            <!-- <div class="row">
                                                <div class="col-md-12"> -->
                                            <div class="table-responsive">
                                                <table id="example1" class="table table-striped custom-table showData">
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
                                                        foreach ($coram_detail as $coram_val) :
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
                                                                <td><a onclick="delete_before(<?php echo $coram_val['jcode'] . "," . $coram_val['diary_no'] . ",'" . $coram_val['notbef'] . "'" ?>)" href="javascript:void(0)"><button class="btn btn-danger btn-sm" type="button"><i class="fas fa-trash" aria-hidden="true"></i></button></a></td>
                                                                <td><?php echo $notbef; ?></td>
                                                                <td><?php echo $coram_val['jname']; ?></td>
                                                                <td><?php echo $coram_val['res_add']; ?></td>
                                                                <td><?php echo $coram_val['entry_date']; ?></td>
                                                                <td><?php echo $coram_val['update_by']; ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- </div>
                                            </div> -->
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
    $(document).ready(function() {
        $('#select_save_as').click(function() {
            var save_as_val = $(this).val();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();

            $.ajax({
                url: "<?php echo base_url('Coram/Coram/get_reason/'); ?>",
                type: "post",
                data: {
                    CSRF_TOKEN: csrf,
                    save_as_val: save_as_val
                },
                success: function(result) {
                    console.log(result);
                    $('#show_reason').html(result);
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

        });
    });

    $(function() {
        $("#example1").DataTable({
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });

    $('#advocate').click(function() {
        $('#show_view').css({
            "display": "block"
        });
    });

    $('#search').click(function() {
        $('#show_view').css({
            "display": "none"
        });
    });


    function save_call() {
        if (document.getElementById('select_save_as').value == 0) {
            alert('Please Select List/Not List/Coram Save Type');
            document.getElementById('select_save_as').focus();
            return false;
        }
        if (document.getElementById('show_reason').value == '') {
            alert('Please Select List/Not List/Coram Before Reason');
            document.getElementById('show_reason').focus();
            return false;
        }

        var total_j = document.getElementById('total_jdg').value;
        var ctrl_j = 0;
        var judge_array = new Array();

        for (var i = 1; i < total_j; i++) {
            var chkbx_j = "jdg" + i;
            var chkbox = document.getElementById(chkbx_j);
            if (null != chkbox && true == chkbox.selected) {
                ctrl_j++;
                if (ctrl_j == 12) {
                    alert('You Can Not Select More than 11 Judges');
                    return false;
                }
                judge_array.push(document.getElementById(chkbx_j).value);
            }
        }

        if (ctrl_j == 0)
            alert('No Judge is Selected');
        else {

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();

            var select_save_as = document.getElementById('select_save_as').value;
            var show_reason = document.getElementById('show_reason').value;

            $.ajax({
                url: "<?php echo base_url('Coram/Coram/add/'); ?>",
                type: "post",
                data: {
                    CSRF_TOKEN: csrf,
                    ctrl: 'I',
                    j: judge_array,
                    save: select_save_as,
                    list_res: show_reason
                },
                success: function(result) {

                    var obj = JSON.parse(result);

                    if (obj.inserted) {
                        alert(obj.inserted);
                        window.location.href = '';
                    }

                    if (obj.delete_coram_msg) {
                        alert(obj.delete_coram_msg);
                        window.location.href = '';
                    }

                    //$('#part_name').html(result);
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
    }


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
                url: "<?php echo base_url('Coram/Coram/delete/'); ?>",
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
    <? //= view('sci_main_footer') ?>
<?php  } ?>