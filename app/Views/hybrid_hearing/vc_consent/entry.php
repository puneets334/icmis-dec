<?= view('header') ?>
<style>
    .input-group {
        position: relative;
        display: flex;
        flex-wrap: wrap;
        align-items: stretch;
        width: 100%;
    }
    .input-group>.form-control, .input-group>.form-floating, .input-group>.form-select {
        position: relative;
        flex: 1 1 auto;
        width: 1% !important;
        min-width: 0;
    }
    .input-group>:not(:first-child):not(.dropdown-menu):not(.valid-tooltip):not(.valid-feedback):not(.invalid-tooltip):not(.invalid-feedback) {
        margin-left: calc(var(--bs-border-width)* -1);
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div id="res_loader"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <h3 class="col-sm-12 card-title">VC Consent - Entry Module</h3>
                    </div>
                    <form id="dateForm" method="post">
                        <?= csrf_field() ?>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="listing_dts" class="form-label">Listing Date</label>
                                        <select class="form-control cus-form-ctrl select-box" name="listing_dts" id="listing_dts">
                                            <option value="">Select Listing Date</option>
                                            <?php if (!empty($listing_dates)): ?>
                                                <?php foreach ($listing_dates as $row): ?>
                                                    <option value="<?= $row['next_dt']; ?>"><?= date("d-m-Y", strtotime($row['next_dt'])); ?></option>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <option value="-1" selected>Listing not found</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="list_type" class="form-label">Listing Date</label>
                                        <select class="form-control cus-form-ctrl select-box" name="list_type" id="list_type">
                                            <option value="">List Type</option>
                                            <option value="0">ALL</option>
                                            <option value="4">Misc.</option>
                                            <option value="3">Regular</option>
                                            <option value="5">Chamber</option>
                                            <option value="6">Registrar</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="judge_code" class="form-label">Hon'ble Judges</label>
                                        <select class="form-control cus-form-ctrl select-box" name="judge_code" id="judge_code">
                                            <option value="">Select Hon'ble Judges</option>
                                            <?php if (!empty($judges)): ?>
                                                <?php foreach ($judges as $row): ?>
                                                    <option value="<?= $row['jcode']; ?>"><?= $row['judge_name']; ?></option>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <option value="-1" selected>Record not found</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="court_no" class="form-label">OR Court No.</label>
                                        <select class="form-control cus-form-ctrl select-box" name="court_no" id="court_no">
                                            <option value="0" selected>All</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                            <option value="13">13</option>
                                            <option value="14">14</option>
                                            <option value="15">15</option>
                                            <option value="16">16</option>
                                            <option value="17">17</option>
                                            <option value="21">21 (Registrar)</option>
                                            <option value="22">22 (Registrar)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-primary w-25" id="button_search">SUBMIT</button>
                        </div>
                    </form>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="text-center" id="result"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function() {
        $('.select-box').select2({
            selectOnClose: true
        });
        $('#button_search').on('click', function(e) {
            e.preventDefault();
            var button = $(this);
            var listing_dts = $("select#listing_dts option:selected").val();
            var list_type = $("select#list_type option:selected").val();
            var judge_code = $("select#judge_code option:selected").val();
            var court_no = $("select#court_no option:selected").val();
            var csrf = $('input[name="<?= csrf_token() ?>"]').val();
            if (!listing_dts) {
                swal({
                    title: "Alert!",
                    text: "Please select a listing date",
                    icon: "warning",
                    icon: "error",
                    button: "error!",
                })
                return;
            }
            if (!list_type) {
                swal({
                    title: "Alert!",
                    text: "Please select either Honble Judge Name or Court No.",
                    icon: "warning",
                    icon: "error",
                    button: "error!",
                })
                return;
            }
            button.prop('disabled', true); // Disable the button
            button.html('<i class="fa fa-spinner fa-spin"></i> Loading...');
            $.ajax({
                url: '<?= base_url('HybridHearing/VcConsent/getAorCaseData') ?>',
                method: 'POST',
                data: {
                    listing_dts: listing_dts,
                    list_type: list_type,
                    judge_code: judge_code,
                    court_no: court_no,
                    '<?= csrf_token() ?>': csrf
                },
                beforeSend: function() {
                    $('#res_loader').html('<div style="position: absolute;top: 50%;left: 50%;text-align: center;-webkit-transform: translate(-50%, -50%);transform: translate(-50%, -50%);"><img src="../../images/load.gif"/></div>');
                },
                success: function(response) {
                    if (response.status == '1') {
                        $('#result').html(response.html);
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                },
                complete: function() {
                    updateCSRFToken();
                    $('#res_loader').html('');
                    button.prop('disabled', false); // Enable the button again
                    button.html('Save'); // Reset button text
                }
            });
        });
        $(document).on("click", ".save_modify", function() {
            var updation_method = $(this).data('updation_method');
            var action = $(this).data('action');
            var button = $(this);
            if (updation_method === 'single') {
                var diary_no = $(this).data('diary_no');
                var conn_key = $(this).data('conn_key');
                var next_dt = $(this).data('next_dt');
                var roster_id = $(this).data('roster_id');
                var main_supp_flag = $(this).data('main_supp_flag');
                var clno = $(this).data('clno');
                var id = $(this).attr('id');
                var userArr = [];
                var chk_count = 0;
                var actionSuccess = action === 'save' ? "saved" : "modified";
                // Collect selected applicants
                $('input[name=' + diary_no + ']:checked').each(function() {
                    chk_count++;
                    userArr.push({
                        applicant_type: $(this).data('applicant_type'),
                        applicant_id: $(this).data('applicant_id')
                    });
                });
                // Check for at least one selected record
                if (chk_count === 0) {
                    swal({
                        title: "Error!",
                        text: "At least one record should be selected",
                        icon: "error",
                        button: "error!"
                    });
                    return false;
                }
                // Confirmation dialog
                swal({
                    title: "Are you sure?",
                    text: "Do you want to " + actionSuccess + ' ' + chk_count + " record(s)",
                    icon: "warning",
                    buttons: ['No, cancel it!', 'Yes, I am sure!'],
                    dangerMode: true,
                //}).then(function(isConfirm) {
                }).then((isConfirmed) => {
                    if (isConfirmed) {
                        var postData = {
                            diary_no: diary_no,
                            conn_key: conn_key,
                            roster_id: roster_id,
                            next_dt: next_dt,
                            userArr: userArr,
                            main_supp_flag: main_supp_flag,
                            clno: clno,
                            updation_method: updation_method,
                            action: action
                        };
                        // Disable the button and show loading state
                        button.prop('disabled', true);
                        button.html('<i class="fa fa-spinner fa-spin"></i> Loading...');
                        // AJAX request
                        $.ajax({
                            url: '<?= base_url('HybridHearing/VcConsent/saveAorCaseData') ?>',
                            method: 'POST',
                            cache: false,
                            data: postData,
                            dataType: "json",
                            beforeSend: function() {
                                $('#res_loader').html('<div style="position: absolute; top: 50%; left: 50%; text-align: center; transform: translate(-50%, -50%);"><img src="../../images/load.gif"/></div>');
                            },
                            success: function(data) {
                                if (data.status === 'success') {
                                    swal({
                                        title: "Success!",
                                        text: chk_count + " record(s) " + actionSuccess + " Successfully",
                                        icon: "success",
                                        button: "success!"
                                    });
                                } else {
                                    swal({
                                        title: "Error!",
                                        text: data.status,
                                        icon: "error",
                                        button: "error!"
                                    });
                                }
                            },
                            error: function(xhr) {
                                var errorMsg = "An error occurred. Please try again.";
                                if (xhr.status === 404) {
                                    errorMsg = "Requested resource not found.";
                                } else if (xhr.status === 500) {
                                    errorMsg = "Internal server error. Please contact support.";
                                }
                                swal({
                                    title: "Error!",
                                    text: errorMsg,
                                    icon: "error",
                                    button: "error!"
                                });
                            },
                            complete: function() {
                                button.prop('disabled', false); // Re-enable button
                                button.html(action === 'save' ? 'Save' : 'Modify');
                            }
                        });
                    } else {
                        swal("Cancelled", "Please try again", "error");
                    }
                });
            } 
            // Bulk Update Handling
            else if (updation_method === 'bulk') {
                var selectedDiaryNumbers = [];
                $('input[type=checkbox][name=' + diary_no + ']:checked').each(function() {
                    selectedDiaryNumbers.push($(this).data('diary_no'));
                });
                if (selectedDiaryNumbers.length === 0) {
                    swal({
                        title: "Error!",
                        text: "At least one record should be selected for bulk action.",
                        icon: "error",
                        button: "error!"
                    });
                    return false;
                }
                // Proceed with bulk action confirmation
                swal({
                    title: "Are you sure?",
                    text: "Do you want to " + actionSuccess + " selected records?",
                    icon: "warning",
                    buttons: ['No, cancel it!', 'Yes, I am sure!'],
                    dangerMode: true,
                }).then(function(isConfirm) {
                    if (isConfirm) {
                        // Prepare bulk data and make AJAX call
                        var bulkPostData = {
                            diary_nos: selectedDiaryNumbers,
                            action: action
                            // Add any additional parameters you need
                        };
                        $.ajax({
                            url: '<?= base_url('HybridHearing/VcConsent/saveAorCaseDataBulk') ?>',
                            method: 'POST',
                            cache: false,
                            data: bulkPostData,
                            dataType: "json",
                            success: function(data) {
                                if (data.status === 'success') {
                                    swal({
                                        title: "Success!",
                                        text: data.message || "Bulk action completed successfully.",
                                        icon: "success",
                                        button: "success!"
                                    });
                                } else {
                                    swal({
                                        title: "Error!",
                                        text: data.status,
                                        icon: "error",
                                        button: "error!"
                                    });
                                }
                            },
                            error: function(xhr) {
                                var errorMsg = "An error occurred. Please try again.";
                                if (xhr.status === 404) {
                                    errorMsg = "Requested resource not found.";
                                } else if (xhr.status === 500) {
                                    errorMsg = "Internal server error. Please contact support.";
                                }
                                swal({
                                    title: "Error!",
                                    text: errorMsg,
                                    icon: "error",
                                    button: "error!"
                                });
                            }
                        });
                    } else {
                        swal("Cancelled", "Please try again", "error");
                    }
                });
            }
        });
        // Select/Deselect All Checkboxes
        $(document).on("click", ".aorCheckboxAll", function() {
            var diary_no = $(this).attr("data-diaryid");
            var id = $(this).attr("id");
            if (diary_no && id) {
                var isChecked = document.getElementById(id).checked;
                $('input[type=checkbox][name=' + diary_no + ']').prop('checked', isChecked);
            }
        });
        function updateCSRFToken() {
            $.get('<?= site_url('HybridHearing/VcConsent/getCSRF'); ?>', function(data) {
                $('input[name="<?= csrf_token() ?>"]').val(data.csrf_token);
            }, 'json');
        }
    });
</script>