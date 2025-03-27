<?php if ($editcoram != 'editcoram') { ?>
    <?= view('header'); ?>

<?php } ?>

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
                        <? //=view('Filing/filing_breadcrumb'); 
                        ?>
                        <?= view('Coram/coram_breadcrumb'); ?>
                        <!-- /.card-header -->
                    <?php } ?>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff;">
                                    <!-- <ul class="nav nav-pills">
                                            <li class="nav-item"><a id="coramA" class="nav-link active" href="#coram" data-toggle="tab">Coram</a></li>
                                            <li class="nav-item"><a id="nbj" class="nav-link" href="#not_before_judge" data-toggle="tab">Not before judges</a></li>
                                        </ul>  -->
                                    <?php
                                    $attribute = array('class' => 'form-horizontal', 'name' => 'subordinate_court_details', 'id' => 'subordinate_court_details', 'autocomplete' => 'off');
                                    echo form_open('#', $attribute);
                                    csrf_token();
                                    ?>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">

                                        <div class="active tab-pane" id="coram">
                                            <h4 class="basic_heading"> List before/Not Before/Coram </h4><br>
                                            <div class="row">
                                                <div class="col-12 text-center">
                                                    <?php
                                                    $diary_num = substr($_SESSION['filing_details']['diary_no'], 0, -4);
                                                    $diary_year = substr($_SESSION['filing_details']['diary_no'], -4);
                                                    ?>
                                                    Diary No. <?= $diary_num; ?>/<?= $diary_year; ?></br>
                                                    <?= strtoupper($_SESSION['filing_details']['pet_name']); ?></br>
                                                    Versus</br>
                                                    <?= strtoupper($_SESSION['filing_details']['res_name']); ?>
                                                </div>
                                            </div>
                                            <?php if (!empty($case_status) && isset($case_status[0]['c_status']) && $case_status[0]['c_status'] == 'P') { ?>
                                                <div class="row ">

                                                    <div class="col-md-12">
                                                        <div class="form-group row">
                                                            <label class="col-sm-2 col-form-label">Judge :</label>
                                                            <div class="col-sm-10">
                                                                <select name="" multiple class="custom-select rounded-0" style="height:20rem">
                                                                    <option value="" disabled>Select Judge</option>
                                                                    <?php $sno = 1;
                                                                    foreach ($judge_list as $judge_val) { ?>
                                                                        <option id="jdg<?php echo $sno ?>" value="<?php echo $judge_val['jcode']; ?>"><?php echo $judge_val['jname']; ?></option>
                                                                    <?php $sno++;
                                                                    } ?>
                                                                    <input type="hidden" value="<?= $sno ?>" id="total_jdg" />
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-4 col-form-label">Save as :</label>
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

                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-4 col-form-label">Reason for Entry :</label>
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
                                            <h4 class="basic_heading"> Already Entries of List before and not before </h4><br>
                                            <!-- <div class="row">
                                                <div class="col-md-12"> -->
                                            <div class="table-responsive">
                                                <table id="example1" class="table table-striped custom-table showData">
                                                    <thead>
                                                        <tr>
                                                            <th>Sr.</th>
                                                            <th>Before/Not before</th>
                                                            <th>Hon. Judge</th>
                                                            <th>Reason/Source</th>
                                                            <th>Entry Date</th>
                                                            <th>Updated By</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $i = 1;
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
                                                                <td><?php echo $i++; ?></td>
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
                                            <!-- </div>
                                            </div> -->
                                        </div>
                                    <?php } ?>

                                    <?php if (!empty($case_status) && isset($case_status[0]['c_status']) && $case_status[0]['c_status'] == 'D') { ?>
                                        <center>
                                            <div style="color:red;">!!!The Case is Disposed!!!</div>
                                        </center>
                                    <?php } ?>

                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                                <?php echo form_close(); ?>
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

<script type="text/javascript">
    $(document).ready(function() {
        $('#select_save_as').click(function() {
            var save_as_val = $(this).val();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();

            $.ajax({
                url: "<?php echo base_url('Filing/Coram/get_reason/'); ?>",
                type: "post",
                data: {
                    CSRF_TOKEN: csrf,
                    save_as_val: save_as_val
                },
                beforeSend: function() {
                    $('#dv_res').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function(result) {
                    // console.log(result);
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
            "buttons": [{
                    extend: "copy",
                    title: "Already Entries of List before and not before\n(As on <?php echo date('d-m-Y'); ?>)"
                },
                {
                    extend: "csv",
                    title: "Already Entries of List before and not before\n(As on <?php echo date('d-m-Y'); ?>)"
                },
                {
                    extend: "excel",
                    title: "Already Entries of List before and not before\n(As on <?php echo date('d-m-Y'); ?>)"
                },
                {
                    extend: "pdf",
                    title: "Already Entries of List before and not before\n(As on <?php echo date('d-m-Y'); ?>)",
                    customize: function(doc) {
                        doc.content.splice(0, 0, {
                            text: "Already Entries of List before and not before\n(As on <?php echo date('d-m-Y'); ?>)",
                            fontSize: 12,
                            alignment: "center",
                            margin: [0, 0, 0, 12]
                        });
                    }
                },
                {
                    extend: "print",
                    title: "",
                    messageTop: "<h3 style='text-align:center;'>ADVOCATE ON RECORD NOT GO BEFORE JUDGE<br>(As on <?php echo date('d-m-Y'); ?>)</h3>"
                }
            ]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
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
                url: "<?php echo base_url('Filing/Coram/add'); ?>",
                type: "post",
                data: {
                    CSRF_TOKEN: csrf,
                    ctrl: 'I',
                    j: judge_array,
                    save: select_save_as,
                    list_res: show_reason
                },
                beforeSend: function() {
                    $('#dv_res').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function(result) {
                    updateCSRFToken();
                    alert(result);
                    location.reload();
                    // var obj = JSON.parse(result);

                    // if (obj.inserted) {
                    //     alert(obj.inserted);
                    //     window.location.href = '';
                    // }

                    // if (obj.delete_coram_msg) {
                    //     alert(obj.delete_coram_msg);
                    //     window.location.href = '';
                    // }


                },
                error: function(jqXHR, textStatus, errorThrown) {
                    updateCSRFToken();
                    alert("Error: " + jqXHR.status + " " + errorThrown);
                }
            });

        }
    }
</script>