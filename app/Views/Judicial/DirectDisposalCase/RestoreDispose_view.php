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
                                <h3 class="card-title">Judicial > Direct Disposal of Case > Restore</h3>
                            </div>
                        </div>
                    </div>
                    <? //view('Filing/filing_breadcrumb'); 
                    ?>
                    <!-- /.card-header -->
                    <link rel="stylesheet" href="<?php echo base_url(); ?>/dp/jquery-ui.css" type="text/css" />
                    <script src="<?php echo base_url(); ?>/js/menu_js.js"></script>
                    <script src="<?php echo base_url(); ?>/dp/jquery-ui.js"></script>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card" id="alert_header" style="display: none;">
                                <div class="card-body">
                                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <strong id="alert_message"></strong>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <?php
                            $attribute = array('class' => 'form-horizontal', 'name' => 'restoration-form', 'id' => 'restoration-form', 'autocomplete' => 'off');
                            echo form_open(base_url(''), $attribute);
                            ?>
                            <div class="card">
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <div class="row mb-4">
                                            <label class="col-sm-6">
                                                <font style="font-weight: bold;font-size: 20px;">Case Details To be restored</font>
                                            </label>
                                            <input type="hidden" name="usercode" id="usercode" value="<?= $usercode ?>" />
                                            <input type="hidden" name="usersection" id="usersection" value="<?= $details[0]['section'] ?>" />
                                            <input type="hidden" name="usertype" id="usertype" value="<?= $details[0]['usertype'] ?>" />
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="rdbt_select" id="radiocase" value="1" onchange="checkData(this.value);" checked>
                                                    <label class="form-check-label" for="radiocase"><b>Case Detail</b></label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group input-group-md">
                                                    <select class="form-control" id="case_type" name="case_type" onchange="get_detail()">
                                                        <option value="">Select Case Type</option>
                                                        <?php foreach ($case_type as $case_type): ?>
                                                            <option value="<?= $case_type['casecode'] ?>"><?= $case_type['skey'] ?> :: <?= $case_type['casename'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group input-group-md">
                                                    <input type="text" class="form-control" id="case_number" name="case_number" placeholder="Case number" onchange="get_detail()">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group input-group-md">
                                                    <select class="form-control" id="case_year" name="case_year" onchange="get_detail()">
                                                        <option value="">Select</option>
                                                        <?php for ($year = date('Y'); $year >= 1950; $year--): ?>
                                                            <option value="<?= $year ?>"><?= $year ?></option>
                                                        <?php endfor; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="rdbt_select" id="radiodiary" value="2" onchange="checkData(this.value);">
                                                    <label class="form-check-label" for="radiodiary"><b>Diary Detail</b></label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group input-group-md">
                                                    <input type="text" class="form-control" id="diary_number" name="diary_number" placeholder="Enter Diary number" onchange="get_detail()">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <select class="form-control" id="diary_year" name="diary_year" onchange="get_detail()">
                                                    <option value="">Select</option>
                                                    <?php for ($year = date('Y'); $year >= 1950; $year--): ?>
                                                        <option value="<?= $year ?>"><?= $year ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <div class="col-md-3">
                                                <label>Diary Number</label>
                                                <span class="form-control" id="case_diary" name="case_diary"></span>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Cause Title</label>
                                                <span class="form-control" id="case_title" name="case_title"></span>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Dismissal Date</label>
                                                <span class="form-control" id="disp_date" name="disp_date"></span>
                                            </div>
                                        </div>

                                        <div class="row mb-3" id="ias" hidden>
                                            <label class="col-md-6">
                                                <font style="font-weight: bold;font-size: 20px;">Disposed IAs </font>
                                                (disposed on the same day)<br />
                                                <font color="red">Select IAs to be pending</font>
                                            </label>
                                        </div>

                                        <div class="row mb-3" hidden id="ma_details2">

                                            <div class="col-md-3">
                                                <div class="input-group input-group-sm"><span class="input-group-addon">IA number</span><label class="form-control" id="ia_num" name="ia_num"></label></div>
                                            </div>
                                        </div>

                                        <!-- end -->
                                        <div class="row mb-3" id="radios" hidden>
                                            <label class="col-sm-2 ">
                                                <font style="font-weight: bold;font-size: 20px;">MA Details</font>
                                            </label>

                                        </div>

                                        <div class="row mb-3" hidden id="ma_details1">
                                            <!--<div class="col-xs-4"><div class="input-group input-group-sm"><span class="input-group-addon">MA Number</span><label class="form-control" id="ma_no" name="ma_no"></label></div></div>-->
                                            <div class="col-xs-3">
                                                <div class="input-group input-group-sm"><span class="input-group-addon">MA Diary No.</span><label class="form-control" id="ma_diary" name="ma_diary"></label></div>
                                            </div>
                                        </div>


                                        <div class="row mb-3" hidden id="ma_details3">
                                            <div class="col-md-4">
                                                <label>Restoration Date</label>
                                                <input type="text" id="restore_date" name="restore_date" class="form-control datepick" required placeholder="Restoration Date">
                                            </div>
                                        </div>
                                        <div class="row mb-3" hidden id="ma_details4">
                                            <div class="col-xs-offset-1 col-xs-6 col-xs-offset-3"><button type="button" id="btn-restore" class="btn bg-olive btn-flat pull-right" onclick="update_case();"><i class="fa fa-save"></i> Restore </button></div>
                                        </div>

                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <?php form_close(); ?>
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
    </div>
</section>
<!-- /.content -->

<script>
     $(function() {
        $('.datepick').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
    });
//    function updateCSRFToken() {
//         $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
//             $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
//         });
//     }

    function showErrorAlert(message) {
        $('#alert_message').text(message);
        $('#alert_header').fadeIn();
        setTimeout(function() {
            $('#alert_header').fadeOut();
        }, 5000);
    }

    $(document).ready(function() {
        $('#diary_number').prop('disabled', true);
        $('#diary_year').prop('disabled', true);
        $('#info-alert').hide();
    })


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


    function checkData($option) {
        if ($option == 1) {
            $('#diary_year').prop('disabled', true);
            $('#diary_number').prop('disabled', true);
            $('#case_number').prop('disabled', false);
            //$('#case_number').attr('required', true);
            $('#case_type').prop('disabled', false);
            $('#case_year').prop('disabled', false);
        }
        if ($option == 2) {
            $('#diary_year').prop('disabled', false);
            $('#diary_number').prop('disabled', false);
            $('#case_number').prop('disabled', true);
            $('#case_type').prop('disabled', true);
            $('#case_year').prop('disabled', true);
        }
    }

    function get_detail() {

 
        var option = $('input:radio[name=rdbt_select]:checked').val();
        var usersection = $('#usersection').val();
        var usertype = $('#usertype').val();
        var caseNumber = $('#case_number').val();
        var caseType = $('#case_type').val();
        var case_year = $('#case_year').val();
        var diaryNo = $('#diary_number').val();
        var diary_year = $('#diary_year').val();
        const csrfToken = $('input[name="<?= csrf_token() ?>"]').val();

        if (option == 1 && !isEmpty(caseNumber) && !isEmpty(caseType) && !isEmpty(case_year)) {
            $.post("<?= base_url('Judicial/DirectDisposalCase/RestoreDispose/get_details'); ?>", {
                '<?= csrf_token() ?>': csrfToken,
                case_number: caseNumber,
                case_type: caseType,
                case_year: case_year
            }, function(result) {
                updateCSRFToken();
                var obj = $.parseJSON(result);
                //console.log(obj);
                if (obj.case_detail[0]['section'] != usersection && (usertype != 14) && (usertype != 9)) {
                    if (!alert("Only Branch Officer/Assistant Registrar of Concerned Section can restore the case")) {
                        $('#btn-restore').prop('disabled', true);
                        location.reload();
                    }
                }
                if (obj.Check_Case_listing.length > 0) {
                    if (!alert("Case is listed. You cannot Restore the Case")) {
                        $('#btn-restore').prop('disabled', true);
                        location.reload();
                    }
                }
                if (obj.ma_details == false) {
                    if (!alert("MA Details of the searched case is not found!")) {
                        $('#btn-restore').prop('disabled', true);
                        location.reload();
                    }
                }
                if (obj.case_detail == false) {
                    if (!alert("The searched case is not found")) {
                        $('#btn-restore').prop('disabled', true);
                        location.reload();
                    }
                } else {
                    for (var i = 0; i < obj.ma_details.length; i++) {
                        document.getElementById('radios').hidden = false;
                        var d = obj.ma_details[i]['ma_diary'];
                        var dd = obj.ma_details[i]['restore_date'];
                        var s = obj.ma_details[i]['ma_status'];
                        var st = "";
                        if (s == 'D')
                            st = "Dismissed";
                        else if (s == 'P')
                            st = "Pending";
                        var n = obj.ma_details[i]['ma_no'];
                        if (n == null)
                            n = '-';
                        else
                            n = obj.ma_details[i]['ma_no'];
                        document.getElementById('radios').innerHTML = document.getElementById('radios').innerHTML + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " +
                            "<input type='radio' name='ma_details[]' id='ma" + d + "' value=" + d + "#" + dd + "#" + s + " onclick='showDetails(this.value)'>" + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" + d + " # " + n + " # " + st + "";
                    }
                    if ((obj.case_detail[0]['ia']) != null && (obj.case_detail[0]['ia']) != '') {
                        document.getElementById('ias').hidden = false;
                        var ia1 = obj.case_detail[0]['ia'].split(',');

                        for (var i = 0; i < ia1.length; i++) {
                            var ia2 = ia1[i].split('#');
                            var ia_id = ia2[0];
                            document.getElementById('ias').innerHTML = document.getElementById('ias').innerHTML + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " +
                                "<input type='checkbox' name='ia_details' id='ia" + ia_id + "' value=" + ia_id + " onclick='getIADetails(this)'>" + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" + ia2[1] + "";
                        }
                    }

                    $('#case_diary').text(obj.case_detail[0]['case_diary']);
                    $('#case_title').text(obj.case_detail[0]['case_title']);
                    $('#disp_date').text(obj.case_detail[0]['disp_date']);
                    /*$('#ma_no').text(obj.ma_details[0]['ma_no']);
                    $('#ma_diary').text(obj.ma_details[0]['ma_diary']);
                    $('#ma_title').text(obj.ma_details[0]['ma_title']);
                    $('#restore_date').val(obj.ma_details[0]['restore_date']);*/
                    var case_status = obj.case_detail[0]['c_status'];
                    var ma_status = obj.ma_details[0]['ma_status'];
                    if (case_status == 'P') {
                        if (!alert("The searched case is pending! You cannot restore this case")) {
                            $('#btn-restore').prop('disabled', true);
                            location.reload();
                        }
                    }
                    /*else if(ma_status=='P')
                        {  if(!alert("MA is pending! You cannot restore this case"))
                        { $('#btn-restore').prop('disabled',true);
                            location.reload();
                        }}*/
                }
            });
        } else if (option == 2 && !isEmpty(diaryNo) && !isEmpty(diary_year)) {
            $.post("<?= base_url('Judicial/DirectDisposalCase/RestoreDispose/get_details'); ?>", {
                '<?= csrf_token() ?>': csrfToken,
                diary_number: diaryNo,
                diary_year: diary_year
            }, function(result) {
                updateCSRFToken();
                var obj = $.parseJSON(result);
                console.log(obj);
                if (obj.case_detail[0]['section'] != usersection && (usertype != 14) && (usertype != 9)) {
                    if (!alert("Only Branch Officer of Concerned Section can restore the case")) {
                        $('#btn-restore').prop('disabled', true);
                        location.reload();
                    }
                }
                if (obj.Check_Case_listing.length > 0) {
                    if (!alert("Case is listed. You cannot Restore the Case")) {
                        $('#btn-restore').prop('disabled', true);
                        location.reload();
                    }
                }
                if (obj.ma_details == false) {
                    if (!alert("MA Details of the searched case is not found!")) {
                        $('#btn-restore').prop('disabled', true);
                        location.reload();
                    }
                }
                if (obj.case_detail == false) {
                    if (!alert("The searched case is not found")) {
                        $('#btn-restore').prop('disabled', true);
                        location.reload();
                    }
                } else {
                    for (var i = 0; i < obj.ma_details.length; i++) {
                        document.getElementById('radios').hidden = false;
                        var d = obj.ma_details[i]['ma_diary'];
                        var dd = obj.ma_details[i]['restore_date'];
                        var s = obj.ma_details[i]['ma_status'];
                        var st = "";
                        if (s == 'D')
                            st = "Dismissed";
                        else if (s == 'P')
                            st = "Pending";
                        var n = obj.ma_details[i]['ma_no'];
                        if (n == null)
                            n = '-';
                        else
                            n = obj.ma_details[i]['ma_no'];
                        document.getElementById('radios').innerHTML = document.getElementById('radios').innerHTML + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " +
                            "<input type='radio' name='ma_details[]' id='ma" + d + "' value=" + d + "#" + dd + "#" + s + " onclick='showDetails(this.value)'>" + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" + d + " # " + n + " # " + st + "";
                    }
                    if ((obj.case_detail[0]['ia']) != null && (obj.case_detail[0]['ia']) != '') {
                        document.getElementById('ias').hidden = false;
                        var ia1 = obj.case_detail[0]['ia'].split(',');

                        for (var i = 0; i < ia1.length; i++) {
                            var ia2 = ia1[i].split('#');
                            var ia_id = ia2[0];
                            document.getElementById('ias').innerHTML = document.getElementById('ias').innerHTML + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " +
                                "<input type='checkbox' name='ia_details' id='ia" + ia_id + "' value=" + ia_id + " onclick='getIADetails(this)'>" + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" + ia2[1] + "";
                        }
                    }

                    $('#case_diary').text(obj.case_detail[0]['case_diary']);
                    $('#case_title').text(obj.case_detail[0]['case_title']);
                    $('#disp_date').text(obj.case_detail[0]['disp_date']);
                    /* $('#ma_no').text(obj.ma_details[0]['ma_no']);
                        $('#ma_diary').text(obj.ma_details[0]['ma_diary']);
                        $('#ma_title').text(obj.ma_details[0]['ma_title']);
                        $('#restore_date').val(obj.ma_details[0]['restore_date']);*/
                    var case_status = obj.case_detail[0]['c_status'];

                    if (case_status == 'P') {
                        if (!alert("The searched case is pending! You cannot restore this case")) {
                            $('#btn-restore').prop('disabled', true);
                            location.reload();
                        }
                    }
                    /*else if(ma_status=='P')
                    {  if(!alert("MA is pending! You cannot restore this case"))
                    { $('#btn-restore').prop('disabled',true);
                        location.reload();
                    }}*/
                }
            });
        }
    }

    function showDetails(index) {
        document.getElementById('ma_details1').hidden = false;
        document.getElementById('ma_details3').hidden = false;
        document.getElementById('ma_details4').hidden = false;
        var res = index.split('#');
        if (res[2] == 'P') {
            if (!alert("MA is pending! You cannot restore this case")) {
                $('#ma_diary').text('');
                $('#restore_date').val('');
                $('#btn-restore').prop('disabled', true);
                // location.reload();
            }
        } else {
            $('#ma_diary').text(res[0]);
            if (res[1] != null) $('#restore_date').val(res[1]);
            $('#btn-restore').prop('disabled', false);
        }
    }

    function getIADetails(index) {
        var items = document.getElementsByName('ia_details');
        var selectedItems = "";
        for (var i = 0; i < items.length; i++) {
            if (items[i].type == 'checkbox' && items[i].checked == true)
                selectedItems += items[i].value + ",";
        }
        //document.getElementById('ma_details2').hidden=false;
        $('#ia_num').text(selectedItems.slice(0, -1));

    }

 
    async function update_case() {
 
        await updateCSRFTokenSync();
 
        var case_diary = $('#case_diary').text();
        var ma_diary = $('#ma_diary').text();
        var restore_date = $('#restore_date').val();
        var usercode = $('#usercode').val();
        var ianumbers = $('#ia_num').text();
        const csrfToken = $('input[name="<?= csrf_token() ?>"]').val();
        if (!isEmpty(case_diary) && !isEmpty(ma_diary) && !isEmpty(restore_date) && !isEmpty(usercode)) {
            $.post("<?= base_url(); ?>Judicial/DirectDisposalCase/RestoreDispose/restore_case", {
                case_diary: case_diary,
                ma_diary: ma_diary,
                restore_date: restore_date,
                usercode: usercode,
                ianum: ianumbers,
                '<?= csrf_token() ?>': csrfToken
            }, function(result) {
                if (!alert(result)) {
                    location.reload();
                }
            });
        } else {
            if (!alert("Enter Case Details")) {
                //$('#btn-restore').prop('disabled', true);
                // location.reload();
                $('#case_type').focus();
            }
        }
    }
</script>