<?= view('header') ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Judicial / Recall >> Case Details To Recall</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>

                            <?php if (session()->getFlashdata('error')) { ?>
                                <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?= session()->getFlashdata('error') ?>
                                </div>
                            <?php } else if (session("message_error")) { ?>
                                <div class="alert alert-danger text-danger" style="color: red;">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?= session("message_error") ?>
                                </div>
                            <?php } else { ?>

                            <?php } ?>
                        </div>
                        <div class="col-md-12">
                            <div class="card-body">
                                <div class="tab-content">
                                    <!-- Page Content Start -->
                                    <div class="" data-ng-init="clearForm()">
                                        <form role="form" id="restoration-form">
                                            <?php echo csrf_field(); ?>
                                            <div class="col-md-12">
                                                <div class="well">
                                                    <div class="row">
                                                        <label class="col-sm-6">
                                                            <font style="font-weight: bold;font-size: 20px;"></font>
                                                        </label>
                                                        <input type="hidden" name="usercode" id="usercode" value="<?php echo $usercode; ?>" />
                                                    </div>
                                                    <br />
                                                    <div class="row  align-items-center">
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-2">
                                                            <div class="form-group clearfix">
                                                                <div class="icheck-primary d-inline">
                                                                    <input type="radio" class="search_type" id="search_type_d" name="search_type" value="D" checked>
                                                                    <label for="search_type_d">
                                                                        Diary
                                                                    </label>
                                                                </div>
                                                                <div class="icheck-primary d-inline">
                                                                    <input type="radio" class="search_type" id="search_type_c" name="search_type" value="C">
                                                                    <label for="search_type_c">
                                                                        Case Type
                                                                    </label>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 diary_section">
                                                            <div class="form-group row">
                                                                <label for="inputEmail3" class="col-sm-5 col-form-label">Diary No</label>
                                                                <input type="number" class="form-control" id="diary_number" name="diary_number" placeholder="Enter Diary No">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 diary_section">
                                                            <div class="form-group row">
                                                                <label for="inputEmail3" class="col-sm-5 col-form-label">Diary Year</label>
                                                                <?php $year = 1950;
                                                                $current_year = date('Y');
                                                                ?>
                                                                <select name="diary_year" id="diary_year" class="custom-select rounded-0">
                                                                    <?php for ($x = $current_year; $x >= $year; $x--) { ?>
                                                                        <option><?php echo $x; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 casetype_section" style="display: none;">
                                                            <div class="form-group row">
                                                                <label for="inputEmail3" class="col-sm-5 col-form-label">Case type</label>
                                                                <select name="case_type" id="case_type" class="custom-select rounded-0 select2" style="width: 100%;">
                                                                    <option value="">Select case type</option>
                                                                    <?php
                                                                    foreach ($case_type as $row) {
                                                                        echo '<option value="' . sanitize(($row['casecode'])) . '">' . sanitize(strtoupper($row['casename'])) . '</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>

                                                        </div>
                                                        <div class="col-md-2 casetype_section" style="display: none;">

                                                            <div class="form-group row ">
                                                                <label for="inputEmail3" class="col-sm-5 col-form-label">Case No</label>
                                                                <input type="number" class="form-control" id="case_number" name="case_number" placeholder="Enter Case No">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 casetype_section" style="display: none;">
                                                            <div class="form-group row">
                                                                <label for="inputEmail3" class="col-sm-5 col-form-label">Case Year</label>
                                                                <?php $year = 1950;
                                                                $current_year = date('Y');
                                                                ?>
                                                                <select name="case_year" id="case_year" class="custom-select rounded-0">
                                                                    <?php for ($x = $current_year; $x >= $year; $x--) { ?>
                                                                        <option><?php echo $x; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <button type="btton" class="btn btn-primary" id="submit" onclick="get_detail()">Search Case</button>
                                                        </div>
                                                        <div class="col-md-2"></div>
                                                    </div>
                                                    <br />
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class=""><span class="">Diary Number</span><label class="form-control" id="case_diary" name="case_diary"></label></div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class=""><span class="">Cause Title</span><label class="form-control" id="case_title" name="case_title"></label></div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class=""><span class="">Dismissal Date</span><label class="form-control" id="disp_date" name="disp_date"></label></div>
                                                        </div>
                                                    </div>
                                                    <br />
                                                    <div class="row" id="reasonRow">
                                                    <div class="col-md-12">
                                                        <div class="input-group input-group-sm">
                                                        <span class="input-group-text">Reason to Recall</span>
                                                        <div class="col-md-3">
                                                            <label><input type="radio" name="rdbt_reason" id="rdbt_reason_court" value="C"> Hon'ble Court's Order</label> 
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label><input type="radio" name="rdbt_reason" id="rdbt_reason_user" value="U"> User Mistake</label>
                                                        </div>
                                                        </div>
                                                    </div>
                                                    </div>
                                                    <br />
                                                    <div class="row" id="reasonText">
                                                        <div class="col-md-12">
                                                            <div class=""><span class="">Reason</span><input type="text" size="100%" name="reason" id="reason" required></div>
                                                        </div>
                                                    </div>
                                                    <br />
                                                    <div class="row">
                                                        <div class="col-md-offset-1 col-md-6 col-md-offset-3"><button type="button" id="btn-update" class="btn bg-olive btn-flat pull-right" onclick="update_case();"><i class="fa fa-save"></i> Update Case </button></div>
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                    <!-- Page Content End -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Main content End -->
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '.search_type', function() {
            //alert('dddd');
            var search_type = $("input[name=search_type]:checked").val();
            if (search_type == 'C') {
                $('.casetype_section').show();
                $('.diary_section').hide();
            } else {
                $('.casetype_section').hide();
                $('.diary_section').show();
            }
            //alert('search_type='+search_type);
        });
    });
</script>
<script type="text/javascript">
    $(function() {
        $('.datepick').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
    });

    function isEmpty(obj) {
        if (obj == null) return true;
        if (obj.length > 0) return false;
        if (obj.length === 0) return true;
        if (typeof obj !== "object") return true;

        // Otherwise, does it have any properties of its own?
        // Note that this doesn't handle
        // toString and valueOf enumeration bugs in IE < 9
        for (var key in obj) {
            if (hasOwnProperty.call(obj, key)) return false;
        }

        return true;
    }
    // $(document).ready(function() {
    //     $('#diary_number').prop('disabled', true);
    //     $('#diary_year').prop('disabled', true);

    // });

    // function checkData($option) {
    //     if ($option == 1) {
    //         $('#diary_year').prop('disabled', true);
    //         $('#diary_number').prop('disabled', true);
    //         $('#case_number').prop('disabled', false);
    //         //$('#case_number').attr('required', true);
    //         $('#case_type').prop('disabled', false);
    //         $('#case_year').prop('disabled', false);
    //     }
    //     if ($option == 2) {
    //         $('#diary_year').prop('disabled', false);
    //         $('#diary_number').prop('disabled', false);
    //         $('#case_number').prop('disabled', true);
    //         $('#case_type').prop('disabled', true);
    //         $('#case_year').prop('disabled', true);
    //     }
    // }

    async function get_detail() {

        await updateCSRFTokenSync();

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        var option = $('input:radio[name=search_type]:checked').val();
        var caseNumber = $('#case_number').val();
        var caseType = $('#case_type').val();
        var case_year = $('#case_year').val();
        var diaryNo = $('#diary_number').val();
        var diary_year = $('#diary_year').val();
        var usercode = $('#usercode').val();
        if (option == 'C' && !isEmpty(caseNumber) && !isEmpty(caseType) && !isEmpty(case_year)) {
            $.post("<?= base_url(); ?>/Judicial/Recall/get_details", {
                case_number: caseNumber,
                case_type: caseType,
                case_year: case_year,
                usercode: usercode,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            }, function(result) {
                updateCSRFToken();
                
                var obj = $.parseJSON(result);
                if (obj.case_detail == false) {
                    if (!alert("The searched case is not found")) {
                        $('#btn-restore').prop('disabled', true);
                        location.reload();
                    }
                } else {
                    $('#case_diary').text(obj.case_detail[0]['case_diary']);
                    $('#case_title').text(obj.case_detail[0]['case_title']);
                    $('#disp_date').text(obj.case_detail[0]['disp_date']);
                    var case_status = obj.case_detail[0]['c_status'];
                    $('#reasonRow').show();
                    $('#reasonText').show();
                    if (case_status == 'P') {
                        if (!alert("The searched case is pending! You cannot recall this case")) {
                            $('#btn-restore').prop('disabled', true);
                            location.reload();
                        }
                    }
                    /*
                    else if(obj.status=='Dismissal')
                    {
                        if(!alert("Dismissal Letter has been generated for the searched case! You cannot recall this case"))
                        { $('#btn-restore').prop('disabled',true);
                            location.reload();
                        }
                    }
                    done on 04.05.2019 */
                    else if (obj.status == 'Allowed') {

                    } else {
                        if (!alert("The searched case can be recalled by either \n" + obj.status[0]['name'].replace(',', ' or '))) {
                            $('#btn-restore').prop('disabled', true);
                            location.reload();
                        }
                    }
                }
            });
        } else if (option == 'D' && !isEmpty(diaryNo) && !isEmpty(diary_year)) {
            $.post("<?= base_url(); ?>/Judicial/Recall/get_details", {
                diary_number: diaryNo,
                diary_year: diary_year,
                usercode: usercode,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            }, function(result) {
                updateCSRFToken();

                var obj = $.parseJSON(result);

                if (obj.case_detail == false) {
                    if (!alert("The searched case is not found")) {
                        $('#btn-restore').prop('disabled', true);
                        location.reload();
                    }
                } else {
                    $('#case_diary').text(obj.case_detail[0]['case_diary']);
                    $('#case_title').text(obj.case_detail[0]['case_title']);
                    $('#disp_date').text(obj.case_detail[0]['disp_date']);
                    $('#reasonRow').show();
                    $('#reasonText').show();
                    var case_status = obj.case_detail[0]['c_status'];
                    console.log(obj);
                    if (case_status == 'P') {
                        if (!alert("The searched case is pending! You cannot recall this case")) {
                            $('#btn-restore').prop('disabled', true);
                            location.reload();
                        }
                    }
                    /*     
                    else if(obj.status=='Dismissal')
                     {
                         if(!alert("Dismissal Letter has been generated for the searched case! You cannot recall this case"))
                         { $('#btn-restore').prop('disabled',true);
                             location.reload();
                         }
                     }
                      done on 04.05.2019 */
                    else if (obj.status == 'Allowed') {

                    } else {

                        if (!alert("The searched case can be recalled by either \n" + obj.status[0]['name'].replace(',', ' or '))) {
                            $('#btn-restore').prop('disabled', true);
                            location.reload();
                        }
                    }
                }
            });
        }
    }

    async function update_case() {
        
        await updateCSRFTokenSync();

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        var case_diary = $('#case_diary').text();
        var usercode = $('#usercode').val();
        var reason = $('#reason').val();
        var reason_option = $('input:radio[name=rdbt_reason]:checked').val();

        console.log("Start Recall");
        console.log(case_diary);
        console.log(usercode);
        console.log(reason_option);
        console.log(reason);

        if (!isEmpty(case_diary) && !isEmpty(usercode) && !isEmpty(reason_option) && !isEmpty(reason)) {
            $.post("<?= base_url(); ?>/Judicial/Recall/recall_case", {
                case_diary: case_diary,
                usercode: usercode,
                reason: reason,
                reason_option: reason_option,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            }, function(result) {
                updateCSRFToken();

                alert(result)
                location.reload();
            });
        } else {
            if (case_diary == '') {
                if (!alert("Enter Case Details")) {
                    //$('#btn-restore').prop('disabled', true);
                    // location.reload();
                    $('#case_type').focus();
                }
            } else if ($('input:radio[name=rdbt_reason]:checked').length == 0) {
                alert("Select one Reason to Recall the case");
            } else if (reason == '') {
                if (!alert("Enter Reason to Recall the case")) {
                    //$('#btn-restore').prop('disabled', true);
                    // location.reload();
                    $('#reason').focus();
                }
            }

        }
    }
</script>