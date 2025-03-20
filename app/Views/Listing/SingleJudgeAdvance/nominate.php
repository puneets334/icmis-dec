<?= view('header') ?>
<style>
    p {
        color: red;
    }

    .select2-container {
        width: 100% !important;
    }
</style>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/smoothness/jquery-ui.css">



<section class="content">
    <div class="container-fluid">
        <!-- <div class="row">
            <div class="col-12"> -->
        <div class="card">
            <div class="card-header heading">

                <div class="row">
                    <div class="col-sm-10">
                        <!-- <h3 class="card-title">Nominate for Single Bench : Add </h3> -->
                        <h4 class="card-title">Nominate for Single Bench : Add</h4>
                    </div>


                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success show" style="width: auto; margin-left: auto; margin-right: auto; text-align: center;">
                                <?= session()->getFlashdata('success') ?>
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger show" style="width: auto; margin-left: auto; margin-right: auto; text-align: center;">
                                <?= session()->getFlashdata('error') ?>
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-12">
                        <!-- Placeholder for flash messages -->
                        <div class="flash-messages"></div>
                    </div>
                </div>


<!-- Add -->

                <form id="singleJudgeNominateForm" name="singleJudgeNominateForm" action="<?= base_url('Listing/SingleJudgeAdvance/singleJudgeNominateAddSubmit') ?>">

                    <?= csrf_field() ?>
                    <?php
                    $nominated_judge_modify_select = "";
                    $Monday_day_type_modify_select = "";
                    $Friday_day_type_modify_select = "";
                    $from_date_modify_select = "";
                    if (isset($nominated_judge_modify) && !empty($nominated_judge_modify)) {
                        $selected_judge_code = $nominated_judge_modify['jcode'];
                        if ($nominated_judge_modify['day_type'] == "Monday") {
                            $Monday_day_type_modify_select = "selected=selected";
                        }
                        if ($nominated_judge_modify['day_type'] == "Friday") {
                            $Friday_day_type_modify_select = "selected=selected";
                        }
                        $from_date_modify_select = date('d-m-Y', strtotime($nominated_judge_modify['from_date']));
                        $submit_action_type = "update";
                    ?>
                        <input type="hidden" name="update_id" id="update_id" value="<?= $nominated_judge_modify['id'] ?>" class="btn btn-primary" />
                    <?php
                    } else {
                        $submit_action_type = "insert";
                    }


                    ?>
                    <input type="hidden" name="submit_action_type" id="submit_action_type" value="<?= $submit_action_type ?>" class="btn btn-primary" />
                    <!-- <div class="col-md-12"> -->
                    <div>

                        <div class="row">
                            <div class="col-sm-12 col-md-4 mb-3">

                                <label for="judge">Nominate Hon'ble Judge <span style="color:red;">*</span></label>
                                <select class="form-control" id="judge" name="judge">
                                    <option value="">Select Judge Name</option>
                                    <?php if (isset($judge) && !empty($judge)): ?>
                                        <?php foreach ($judge as $v): ?>
                                            <option value="<?= trim($v['jcode']) ?>" <?= isset($selected_judge_code) && $selected_judge_code === trim($v['jcode']) ? 'selected' : '' ?>>
                                                <?= strtoupper($v['jname']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <span id="error_judge"></span>

                                <span id="error_judge"></span>
                            </div>

                            <div class="col-sm-12 col-md-4 mb-3">
                                <label for="judge">Day<span style="color:red;">*</span></label>
                                <select class="form-control" id="day_type" name="day_type">
                                    <option value="Monday" <?= $Monday_day_type_modify_select ?>>Monday</option>
                                    <option value="Friday" <?= $Friday_day_type_modify_select ?>>Friday</option>
                                </select>
                                <span id="error_day_type"></span>
                            </div>
                            <!-- Add Effected date  -->
                            <div class="col-sm-12 col-md-4 mb-3">
                                <label for="used_from">Effect Date<span style="color:red;">*</span></label>
                                <input type="text" size="10" class="form-control ddtp" name="effect_date" id="effect_date" readonly />
                                <span id="error_effect_date"></span>
                            </div>
                            <br><br>

                            <div class="col-sm-12 text-left">
                               
                                <button type="submit" name="single_judge_nominate_submit" id="single_judge_nominate_submit" class="quick-btn">Save</button>
                            </div>
                        </div>
                    </div>
                    <!-- </div> -->
                </form>
            </div>
        </div>
    </div>

    <!-- Edit -->
    <div class="modal fade" id="myModaledit" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content myModalDeActiveContent">
                <div class="modal-header">
                    <h4>Edit</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <form id="singleJudgeNominateForm1" name="singleJudgeNominateForm1" action="<?= base_url('Listing/SingleJudgeAdvance/singleJudgeNominateUpdate') ?>">

                        <?= csrf_field() ?>

                        <input type="hidden" name="update_id" id="update_id" value="" />
                        <input type="hidden" name="submit_action_type" id="submit_action_type" value="update" />

                        <?php
                        $nominated_judge_modify_select = "";
                        $Monday_day_type_modify_select = "";
                        $Friday_day_type_modify_select = "";
                        $from_date_modify_select = "";
                        if (isset($nominated_judge_modify) && !empty($nominated_judge_modify)) {
                            $selected_judge_code = $nominated_judge_modify['jcode'];
                            if ($nominated_judge_modify['day_type'] == "Monday") {
                                $Monday_day_type_modify_select = "selected=selected";
                            }
                            if ($nominated_judge_modify['day_type'] == "Friday") {
                                $Friday_day_type_modify_select = "selected=selected";
                            }
                            $from_date_modify_select = date('d-m-Y', strtotime($nominated_judge_modify['from_date']));
                            $submit_action_type = "update";
                        ?>
                            <input type="hidden" name="update_id" id="update_id" value="<?= $nominated_judge_modify['id'] ?>" class="btn btn-primary" />
                        <?php
                        } else {
                            $submit_action_type = "insert";
                        }


                        ?>
                        <input type="hidden" name="submit_action_type" id="submit_action_type" value="<?= $submit_action_type ?>" class="btn btn-primary" />
                        <!-- <div class="col-md-12"> -->
                        <div>
                            <!-- <br> -->
                            <div class="row">
                                <div class="col-sm-12  mb-3">

                                    <label for="judge">Nominate Hon'ble Judge <span style="color:red;">*</span></label>
                                    <select class="form-control" id="judge1" name="judge">
                                        <option value="">Select Judge Name</option>
                                        <?php if (isset($judge) && !empty($judge)): ?>
                                            <?php foreach ($judge as $v): ?>
                                                <option value="<?= trim($v['jcode']) ?>" <?= isset($selected_judge_code) && $selected_judge_code === trim($v['jcode']) ? 'selected' : '' ?>>
                                                    <?= strtoupper($v['jname']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <span id="error_judge"></span>

                                    <span id="error_judge"></span>
                                </div>
                            </div>
                            <!-- <br> -->

                            <div class="row">
                                <div class="col-sm-12  mb-3">
                                    <label for="judge">Day<span style="color:red;">*</span></label>
                                    <select class="form-control" id="day_type1" name="day_type">
                                        <option value="Monday" <?= $Monday_day_type_modify_select ?>>Monday</option>
                                        <option value="Friday" <?= $Friday_day_type_modify_select ?>>Friday</option>
                                    </select>
                                    <span id="error_day_type"></span>
                                </div>
                            </div>
                        </div>
                        <!-- <br> -->

                        <div class="row ">
                            <div class="col-sm-12 mb-3">
                                <label for="used_from">Effect Date <span style="color:red;">*</span></label>

                                <!-- <input type="text" class="form-control" name="effect_date" id="effect_date1" autocomplete="off" /> -->
                                <input type="text" size="10" class="form-control ddtp" name="effect_date" id="effect_date1" readonly />
                                <span id="error_effect_date"></span>
                            </div>
                        </div>


                        <!-- </div> -->
                </div>
                <div class="modal-footer">
                    <div class="row w-100">
                        <div class="col-md-12 text-center">
                           
                            <button type="submit" name="single_judge_nominate_submit" id="single_judge_nominate_update" class="quick-btn" single_judge_id_fetch1="0">Update</button>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
        <!-- </div>
        </div> -->
    </div>
    <!-- DeActive modal -->
    <div class="modal fade" id="myModalClose" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content myModalDeActiveContent">
                <div class="modal-header">
                    <h4>De-Active / Close</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <form>
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label for="to_date">Closing Date:</label>

                            <input type="text" size="10" class="form-control ddtp" name="to_date" id="to_date" readonly />

                            <span id="error_to_date"></span>
                        </div>


                    </form>
                </div>
                <div class="modal-footer">
                    <div class="row w-100">
                        <div class="col-md-12 text-center">
                            <button type="button" class="quick-btn" id="btn_single_judge_close_save" data-single_judge_id_fetch="0">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete modal -->
    <div class="modal fade" id="myModalDelete" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content myModalDeleteContent">
                <div class="modal-header">
                    <h4>Delete</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <form>
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label for="to_date">Enter Delete Reason:</label>
                            <input type="text" class="form-control" id="delete_reason" autocomplete="off">
                            <span id="error_delete_reason"></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="row w-100">
                        <div class="col-md-12 text-center">
                            <button type="button" class="quick-btn" id="btn_single_judge_delete_save" data-single_judge_id_fetch="0">Save</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    </div>
    </div>
    <div class="row" id="loader_image"></div>
    <div class="container-fluid">
        <div class="panel panel-default" id="tableDiv">
            <div class="panel-heading card-header heading">
                <h4 class="card-title">Active Nominated Judges for Single Bench</h4>
            </div>
            <div class="panel-body">
                <table id="singleJudgeNominateTable" class="table table-striped custom-table table-hover display">
                    <thead>
                        <tr>
                            <th width="1%">Sno.</th>
                            <th>Judge Name</th>
                            <th>Day Type</th>
                            <th>Effected Date</th>
                            <th>User Name</th>
                            <th>Entry Date Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="single_data">
                    </tbody>
                </table>
            </div>
        </div>

</section>

<script>
    var leavesOnDates = <?= next_holidays_new(); ?>;

    $(function() {
        var date = new Date();
        date.setDate(date.getDate());
        $('.ddtp').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            startDate: date,
            todayHighlight: true,
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050',
            datesDisabled: leavesOnDates,
            isInvalidDate: function(date) {
                return (date.day() == 0 || date.day() == 6);
            },
        });
    });
</script>
<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->



<script>
    // $(document).ready(function() {
    //     $('#effect_date').datepicker({
    //         dateFormat: 'dd-mm-yy'
    //     });
    // });



    // $(document).ready(function() {
    //     $('#effect_date1').datepicker({
    //         dateFormat: 'dd-mm-yy'
    //     });
    // });



    $(document).ready(function() {
        fetchSingleJudgeNominatedData();

    });

    function fetchSingleJudgeNominatedData()
    {
        $.ajax({
            url: base_url + "/Listing/SingleJudgeAdvance/getSingleJudgeNominatedData",
            type: 'GET',

            success: function(response) {

                $('#loader_image').html('');

                var length = response.length;
                const url = base_url + "/Listing/SingleJudgeAdvance/singleJudgeNominateAdd/";
                if (length > 0) {
                    for (var i = 0; i < length; i++) {
                        var res = response[i];


                        var id = res.id;
                        var finalUrl = url + id;
                        var judgeName = res.jname ? res.jname.toUpperCase() : '';
                        var userName = res.name ? res.name.toUpperCase() : '';
                        var actionLinks = '<a style="padding-left:5px;" data-single_judge_id="' + id + '" id="single_judge_edit" href="#" title="Edit">' +
                            '<i class="glyphicon glyphicon-pencil text-secondary"></i></a>' +
                            '<a style="padding-left:5px;" data-single_judge_id="' + id + '" id="single_judge_close" href="#" title="De-activate/Close">' +
                            '<i class="glyphicon glyphicon-eye-close text-success"></i></a>' +
                            '<a style="padding-left:5px;" data-single_judge_id="' + id + '" id="single_judge_delete" href="#" title="Delete - Due to directions changed or entry done accidentally.">' +
                            '<i class="glyphicon glyphicon-trash text-danger"></i></a>';

                        $('#single_data').append(`
                        <tr id=${id}>
                            <td>${i + 1}</td>
                            <td>${judgeName}</td>
                            <td>${res.day_type}</td>
                            <td>${res.effect_date}</td>
                            <td>${userName}</td>
                            <td>${res.created_on}</td>
                            <td>${actionLinks}</td>
                        </tr>
                    `);
                    }
                }
            },
            error: function(xhr) {
                console.error("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    }





    $(document).on('click', '#single_judge_nominate_submit', function(e)
    {
        e.preventDefault();
        var judge = $('select#judge option:selected').val();
        var day_type = $('select#day_type option:selected').val();
        var effect_date = $('#effect_date').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        if (judge == '') {
            $("#judge").focus();
            $("#judge").css({
                'border-color': 'red'
            });
            $('#error_judge').html('<p>Please select Honble Judge.</p>');
            alert("Please select Hon'ble Judge.");
            return false;
        } else if (day_type == '') {
            $("#day_type").focus();
            $("#day_type").css({
                'border-color': 'red'
            });
            $('#error_day_type').html('<p>Please select day.</p>');
            alert("Please fill day.");
            return false;
        } else if (effect_date == '') {
            $("#effect_date").focus();
            $("#effect_date").css({
                'border-color': 'red'
            });
            $('#error_effect_date').html('<p>Please fill effect Date.</p>');
            alert("Please fill effect date.");
            return false;
        } else
        {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                url: base_url + "/Listing/SingleJudgeAdvance/singleJudgeNominateJcodeDaytypeValidate",
                data: {
                    jcode: judge,
                    day_type: day_type,
                    effect_date: effect_date,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                type: 'POST',
                dataType: "json",
                success: function(data, status) {
                    updateCSRFToken()
                    console.log(data.status);
                    if (data.status == 'success')
                    {
                        updateCSRFToken()
                        $("#judge").focus();
                        $("#judge").css({
                            'border-color': 'red'
                        });
                        $('#error_judge').html('<p>Record already available.</p>');
                        alert("Record already available.");

                    } else {
                        updateCSRFToken()
                        $("#singleJudgeNominateForm").submit();
                    }
                },
                error: function(xhr) {

                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });

        }
    });

    $(document).on('click', '#single_judge_close', function(e)
    {

        $("#myModalClose").modal({
            backdrop: false
        });
        var id = $(this).data('single_judge_id');
        $('#btn_single_judge_close_save').attr("data-single_judge_id_fetch", id); //data setter
    });
    $(document).on('click', '#btn_single_judge_close_save', function(e)
    {

        e.preventDefault();
        var to_date = $('#to_date').val();
        var deactivate_flag = 'close';
        var single_judge_id = $(this).data('single_judge_id_fetch');
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        if (to_date == '') {
            alert('if');
            $("#to_date").focus();
            $("#to_date").css({
                'border-color': 'red'
            });
            $('#error_to_date').html('<p>Please fill closing date.</p>');
            alert("Please fill closing date.");
            return false;
        } else 
        {
            // De-Active code
            $.ajax({

                url: base_url + "/Listing/SingleJudgeAdvance/singleJudgeNominatedDeActive",

                data: {
                    to_date: to_date,
                    single_judge_id: single_judge_id,
                    deactivate_flag: deactivate_flag,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                type: 'POST',
                dataType: "json",
                success: function(data, status) {
                    if (data.status === 'success') {
                        updateCSRFToken();
                        $('#myModalClose').hide();
                        $('tr#' + single_judge_id).hide();
                        $('.flash-messages').html(`
                        <div class="alert alert-success show" style="width: auto; margin-left: auto; margin-right: auto; text-align: center;">
                            ${data.message}
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                        `);
                    } else {

                        $('.flash-messages').html(`
                            <div class="alert alert-danger show" style="width: auto; margin-left: auto; margin-right: auto; text-align: center;">
                                ${data.message}
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                        `);
                    }
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                },
            });
        }


    });

    // Delete code

    $(document).on('click', '#single_judge_delete', function(e)
    {
        $("#myModalDelete").modal({
            backdrop: false
        });
        var id = $(this).data('single_judge_id');
        $('#btn_single_judge_delete_save').attr("data-single_judge_id_fetch", id);
    });

    $(document).on('click', '#btn_single_judge_delete_save', function(e)
    {
        e.preventDefault();
        var delete_reason = $('#delete_reason').val();
        var single_judge_id = $(this).data('single_judge_id_fetch');
        var deactivate_flag = 'delete';
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        if (delete_reason.length < 10) {
            $("#delete_reason").focus();
            $("#delete_reason").css({
                'border-color': 'red'
            });
            $('#error_delete_reason').html('<p>Please enter delete reason.</p>');
            alert("Please enter delete reason (minimum 10 characters).");
            return false;
        } else {
            $.ajax({

                url: base_url + "/Listing/SingleJudgeAdvance/singleJudgeNominatedDeActive",

                data: {
                    delete_reason: delete_reason,
                    single_judge_id: single_judge_id,
                    deactivate_flag: deactivate_flag,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                type: 'POST',
                dataType: "json",
                success: function(data, status)
                {
                    if (data.status == 'success') {
                        updateCSRFToken();
                        $('#myModalDelete').hide();
                        $('tr#' + single_judge_id).hide();
                        $('.flash-messages').html(`
                        <div class="alert alert-success show" style="width: auto; margin-left: auto; margin-right: auto; text-align: center;">
                            ${data.message}
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                        `);
                    } else {

                        $('.flash-messages').html(`
                        <div class="alert alert-danger show" style="width: auto; margin-left: auto; margin-right: auto; text-align: center;">
                            ${data.message}
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                        `);
                    }
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        }


    });

    //Update code


    $(document).on('click', '#single_judge_edit', function(e)
    {
        e.preventDefault();
        $("#myModaledit").modal({
            backdrop: false
        });

        var single_judge_id = $(this).data('single_judge_id');
        $.ajax({
            url: '<?= base_url('Listing/SingleJudgeAdvance/getJudgeData') ?>',
            type: 'GET',
            data: {
                single_judge_id: single_judge_id
            },
            dataType: 'json',
            success: function(data) {
                $('#update_id').val(data.id);
                $('#judge1').val(data.jcode);
                $('#day_type1').val(data.day_type);
                $('#effect_date1').val(data.effect_date);
                $("#myModaledit").modal('show');

            },
            error: function(xhr, status, error) {
                console.error('Error fetching judge data:', error);
            }
        });
    });


    $(document).on('submit', '#singleJudgeNominateForm1', function(e)
    {
        e.preventDefault();

        $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(), 
        success: function(response) {
            if (response.status === 'success')
            {
                
                $('.flash-messages').html(`
                    <div class="alert alert-success show" style="text-align: center;">
                        ${response.message}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                `);
                
                $("#myModaledit").modal('hide');
                location.reload(); 
            } 
            else {
                
                $('.flash-messages').html(`
                    <div class="alert alert-danger show" style="text-align: center;">
                        ${response.message}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                `);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error updating data:', error);
            $('.flash-messages').html(`
                <div class="alert alert-danger show" style="text-align: center;">
                    An unexpected error occurred. Please try again.
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            `);
        }
    });
});
</script>