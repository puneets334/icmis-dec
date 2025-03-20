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
                                <h3 class="card-title">Judicial > Remove Conditional Dispose</h3>
                            </div>
                        </div>
                    </div>
                    <? //view('Filing/filing_breadcrumb'); 
                    ?>
                    <!-- /.card-header -->

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
                            <div class="card">
                                <?php
                                $attribute = array('class' => 'form-horizontal', 'name' => 'removedispose', 'id' => 'removedispose', 'autocomplete' => 'off');
                                echo form_open(base_url(''), $attribute);
                                ?>
                                <div class="card-header">
                                    <h3>Case to be Remove from Disposal Condition</h3>
                                    <div class="alert alert-info alert-dismissable fade in mt-4" id="info-alert">
                                        <button type="button" class="close" data-dismiss="alert">x</button>
                                        <strong>Info! </strong>
                                        No Record Found.
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="text-center" id="loader"></div>
                                    <input type="hidden" name="usercode" id="usercode" value="<?= $user_idd ?>" />
                                    <div class="row mb-4">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="rdbt_select_list" id="radiocase_list" value="1" onchange="checkData(this.value);" checked>
                                                <label class="form-check-label" for="radiocase_list"><b>Case Detail</b></label>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <select class="form-control" id="case_type_list" name="case_type_list" onchange="get_detail()">
                                                <option value="">Select Case Type</option>
                                                <?php foreach ($case_types as $case_type): ?>
                                                    <option value="<?= $case_type['casecode'] ?>"><?= $case_type['casename'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <input type="number" class="form-control" id="case_number_list" name="case_number_list" placeholder="Case number" onchange="get_detail()">
                                        </div>

                                        <div class="col-md-3">
                                            <select class="form-control" id="case_year_list" name="case_year_list" onchange="get_detail()">
                                                <option value="">Select</option>
                                                <?php for ($year = date('Y'); $year >= 1950; $year--): ?>
                                                    <option value="<?= $year ?>"><?= $year ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="rdbt_select_list" id="radiodiary_list" value="2" onchange="checkData(this.value);">
                                                <label class="form-check-label" for="radiodiary_list"><b>Diary Detail</b></label>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <input type="number" class="form-control" id="diary_number_list" name="diary_number_list" placeholder="Enter Diary number" onchange="get_detail()">
                                        </div>

                                        <div class="col-md-3">
                                            <select class="form-control" id="diary_year_list" name="diary_year_list" onchange="get_detail()">
                                                <option value="">Select</option>
                                                <?php for ($year = date('Y'); $year >= 1950; $year--): ?>
                                                    <option value="<?= $year ?>"><?= $year ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-9">
                                            <label>Cause Title</label>
                                            <span class="form-control" id="case_title_list" name="case_title_list"></span>
                                        </div>
                                        <div class="col-sm-3">
                                            <label>No. of Connected Matters</label>
                                            <span class="form-control" id="conn_list" name="conn_list"></span>
                                        </div>
                                        <input type="hidden" class="form-control" id="case_diary_list" name="case_diary_list">
                                    </div>
                                    <div class="row mb-3">
                                        <input type="hidden" class="form-control" id="case_diary_disp" name="case_diary_disp">
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-xs-offset-1 col-xs-6 col-xs-offset-3">
                                            <button type="button" id="btn-update" class="btn btn-primary btn-olive btn-flat pull-right" onclick="getAllNotices();"><i class="fa fa-save"></i> Submit </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div id="display" class="box box-danger">
                                        <table width="100%" id="reportTable1" class="table table-striped table-hover">
                                            <thead>
                                                <h3 style="text-align: center;"> Conditional Dispose Cases</h3>
                                                <tr>
                                                    <th>S No.</th>
                                                    <th>Restricted Case</th>
                                                    <th>After Disposal of Case</th>
                                                    <th>Court Type</th>
                                                    <th><i class="glyphicon glyphicon-tags"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php form_close(); ?>
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
    </div>
</section>
<!-- /.content -->

<script>
    function updateCSRFToken() {
        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        });
    }

    function showErrorAlert(message) {
        $('#alert_message').text(message);
        $('#alert_header').fadeIn();
        setTimeout(function() {
            $('#alert_header').fadeOut();
        }, 5000);
    }

    $(document).ready(function() {
        $('#diary_number_list').prop('disabled', true);
        $('#diary_year_list').prop('disabled', true);
        $("#display").hide();
        $('#info-alert').hide();
    })

    function checkData($option) {
        if ($option == 1) {
            $('#diary_year_list').prop('disabled', true);
            $('#diary_number_list').prop('disabled', true);
            $('#case_number_list').prop('disabled', false);
            $('#case_type_list').prop('disabled', false);
            $('#case_year_list').prop('disabled', false);
        } else if ($option == 2) {
            $('#diary_year_list').prop('disabled', false);
            $('#diary_number_list').prop('disabled', false);
            $('#case_number_list').prop('disabled', true);
            $('#case_type_list').prop('disabled', true);
            $('#case_year_list').prop('disabled', true);
        }

    }
    function updateCSRFToken() {
        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        });
    }

    async function get_detail() {

        await updateCSRFTokenSync();

        // Fetch selected options and inputs

        var option_list = $('input:radio[name=rdbt_select_list]:checked').val();
        var caseNumber_list = $('#case_number_list').val();
        var caseType_list = $('#case_type_list').val();
        var case_year_list = $('#case_year_list').val();
        var diaryNo_list = $('#diary_number_list').val();
        var diary_year_list = $('#diary_year_list').val();
        const csrfToken = $('input[name="<?= csrf_token() ?>"]').val();
        
        if (option_list == 1 && !isEmpty(caseNumber_list) && !isEmpty(caseType_list) && !isEmpty(case_year_list)) {
            $.ajax({
                type: "POST",
                url: "<?= base_url('Judicial/DirectDisposalCase/RemoveDisposeCondition/get_details'); ?>",
                data: {
                    search_type: option_list,
                    case_number_list: caseNumber_list,
                    case_type_list: caseType_list,
                    case_year_list: case_year_list,
                    '<?= csrf_token() ?>': csrfToken
                },
                beforeSend: function() {
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function(result) {
                    updateCSRFToken();
                    $("#loader").html(""); // Clear the loader
                    var obj = $.parseJSON(result);
                    if (obj.status === 'error') {
                        showErrorAlert(obj.message);
                        alert(obj.message);
                    } else {
                        var case_status = obj.case_status;
                        
                        if (case_status === 'P') {
                            $('#case_diary_list').val(obj.case_diary);
                            $('#case_title_list').text(obj.case_title);
                            $('#conn_list').text(obj.conn_details[0]['conn']);
                        } else if (case_status === 'D') {
                            if (!alert("The searched case is disposed!")) {
                                showErrorAlert("The searched case is disposed!");
                                $('#case_type_list').val('');
                                $('#case_number_list').val('');
                                $('#case_year_list').val('');
                                $('#case_diary_list').val('');
                                $('#case_title_list').text('');
                                $('#conn_list').text('');
                            }
                        }
                    }
                },
                error: function() {
                    updateCSRFToken();
                    showErrorAlert(obj.message);
                    alert(obj.message);
                }
            });
        } else if (option_list == 2 && !isEmpty(diaryNo_list) && !isEmpty(diary_year_list)) {
            $.ajax({
                type: "POST",
                url: "<?= base_url('Judicial/DirectDisposalCase/RemoveDisposeCondition/get_details'); ?>",
                data: {
                    search_type: option_list,
                    diary_number_list: diaryNo_list,
                    diary_year_list: diary_year_list,
                    '<?= csrf_token() ?>': csrfToken
                },
                beforeSend: function() {
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function(result) {
                    updateCSRFToken();
                    $("#loader").html(""); // Clear the loader
                    var obj = $.parseJSON(result);
                    if (obj.status === 'error') {
                        alert(obj.message);
                        showErrorAlert("The searched case is not found!");
                    } else {
                        var case_status = obj.case_status;
                        if (case_status == 'P') {
                            $('#case_diary_list').val(obj.case_diary);
                            $('#case_title_list').text(obj.case_title);
                            $('#conn_list').text(obj.conn_details[0]['conn']);
                        } else if (case_status == 'D') {
                            if (!alert("The searched case is disposed!")) {
                                showErrorAlert("The searched case is disposed!");
                                $('#diary_number_list').val('');
                                $('#diary_year_list').val('');
                                $('#case_diary_list').val('');
                                $('#case_title_list').text('');
                                $('#conn_list').text('');
                            }
                        }
                    }
                },
                error: function() {
                    $("#loader").html(""); // Clear the loader
                    updateCSRFToken();
                    alert(obj.message);
                    showErrorAlert(obj.message);
                }
            });
        }
    }




    function getAllNotices() {

        var list_diary = $('#case_diary_list').val();

        if (!list_diary) {
            showErrorAlert("The searched case is not found!");
            return; // Exit the function if no input is provided
        }

        const csrfToken = $('input[name="<?= csrf_token() ?>"]').val();

        $('#getNoticesButton').prop('disabled', true);

        // AJAX request
        // $.ajax({
        //     url: '<?= base_url('Judicial/DirectDisposalCase/RemoveDisposeCondition/get_Restrict_Cases_History'); ?>',
        //     type: "POST",
        //     data: {
        //         list_diary: list_diary,
        //         '<?= csrf_token() ?>': csrfToken
        //     },
        //     beforeSend: function() {
        //         console.log('request before');
        //         $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
        //     },
        //     success: function(result) {
        //         alert($result);
        //         var obj = $.parseJSON(result);
        //         console.log(obj);
        //         $("#loader").html(''); // Clear the loader

        //         if (obj.status === 'success' && obj.data.length > 0) {
        //             $("#display").show();
        //             $('#reportTable1 tbody').empty();
        //             let sno = 1;

        //             // Populate the table with data
        //             $.each(obj.data, function(index, item) {
        //                 const courtType = item.court_type || 'Supreme Court';
        //                 const courtName = courtType === 'S' || courtType === null ? 'Supreme Court' : 'Lower Court';
        //                 const caseNo = courtType === 'Supreme Court' ? item.sc_caseno : `${item.lc_casetype} No. ${item.lc_caseno}/${item.lc_caseyear}`;

        //                 $('#reportTable1 tbody').append(
        //                     `<tr>
        //                         <td>${sno}</td>
        //                         <td>${item.mcaseno || ''}</td>
        //                         <td>${caseNo || ''}</td>
        //                         <td>${courtName}</td>
        //                         <td><a class="del-button" onclick="delete_case();"><i class="fa fa-trash"></i></a></td>
        //                     </tr>`
        //                 );
        //                 sno++;
        //             });

        //             // Destroy and reinitialize DataTable
        //             if ($.fn.DataTable.isDataTable('#reportTable1')) {
        //                 $('#reportTable1').DataTable().destroy();
        //             }

        //             $('#reportTable1').DataTable({
        //                 "bSort": true,
        //                 dom: 'Bfrtip',
        //                 "scrollX": true,
        //                 iDisplayLength: 8,
        //                 buttons: [{
        //                     extend: 'print',
        //                     orientation: 'landscape',
        //                     pageSize: 'A4'
        //                 }]
        //             });
        //         } else {
        //             showErrorAlert(obj.message || 'No data found');
        //             $("#display").hide();
        //             $("#info-alert").show().fadeTo(2000, 500).slideUp(500);
        //         }
        //     },
        //     error: function(xhr, status, error) {
        //         console.error('AJAX Error:', {
        //             status: status,
        //             error: error,
        //             responseText: xhr.responseText
        //         });
        //         showErrorAlert("An error occurred while fetching the case details.");
        //     },
        //     complete: function() {
        //         updateCSRFToken(); // Update CSRF token after the request
        //         $('#getNoticesButton').prop('disabled', false); // Re-enable the button
        //     }
        // });
        $.ajax({
                url: '<?= base_url('Judicial/DirectDisposalCase/RemoveDisposeCondition/get_Restrict_Cases_History'); ?>',
                type: "POST",
                data: {
                    list_diary: list_diary,
                    '<?= csrf_token() ?>': csrfToken
                },
                beforeSend: function() {
                    console.log('AJAX request initiated.');
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function(result) {
                    //console.log('AJAX request successful. Raw response:', result);
                    try {
                        var obj = $.parseJSON(result);
                        console.log('Parsed response:', obj);
                    } catch (error) {
                        console.error('JSON Parsing Error:', error);
                        showErrorAlert("Invalid response from server.");
                        return;
                    }

                    $("#loader").html(''); // Clear the loader

                    if (obj.status === 'success' && obj.data.length > 0) {
                        $("#display").show();
                        $('#reportTable1 tbody').empty();
                        let sno = 1;

                        $.each(obj.data, function(index, item) {
                            const courtType = item.court_type || 'Supreme Court';
                            const courtName = courtType == 'S' || courtType == null ? 'Supreme Court' : 'Lower Court';
                            const caseNo = courtType == 'S' ? item.sc_caseno : `${item.lc_casetype || 'Unknown'} No. ${item.lc_caseno || 'Unknown'}/${item.lc_caseyear || 'Unknown'}`;
                            
                            // console.log(courtType+' | '+courtName+' | '+caseNo);
                            // console.log(item);

                            $('#reportTable1 tbody').append(
                                `<tr>
                                    <td>${sno}</td>
                                    <td>${item.mcaseno || ''}</td>
                                    <td>${caseNo || ''}</td>
                                    <td>${courtName}</td>
                                    <td><a class="del-button btn btn-danger" onclick="delete_case();"><i class="fa fa-trash"></i></a></td>
                                </tr>`
                            );
                            sno++;
                        });

                        if ($.fn.DataTable.isDataTable('#reportTable1')) {
                            $('#reportTable1').DataTable().destroy();
                        }

                        $('#reportTable1').DataTable({
                            "bSort": true,
                            dom: 'Bfrtip',
                            "scrollX": true,
                            iDisplayLength: 8,
                            buttons: [{
                                extend: 'print',
                                orientation: 'landscape',
                                pageSize: 'A4'
                            }]
                        });
                    } else {
                        showErrorAlert(obj.message || 'No data found');
                        $("#display").hide();
                        $("#info-alert").show().fadeTo(2000, 500).slideUp(500);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', {
                        status: status,
                        error: error,
                        responseText: xhr.responseText
                    });
                    showErrorAlert("An error occurred while fetching the case details.");
                },
                complete: function() {
                    console.log('AJAX request completed.');
                    updateCSRFToken(); // Update CSRF token after the request
                    $('#getNoticesButton').prop('disabled', false); // Re-enable the button
                }
            });

    }


    function delete_case() {
        updateCSRFToken(); // Ensure CSRF token is updated before the request
        var list_diary = $('#case_diary_list').val();
        var usercode = $('#usercode').val();

        if (!list_diary || !usercode) {
            showErrorAlert("Diary number or user code is missing.");
            return;
        }

        if (confirm('Do you want to remove this case?')) {
            const csrfToken = $('input[name="<?= csrf_token() ?>"]').val();

            $.ajax({
                url: '<?= base_url('Judicial/DirectDisposalCase/RemoveDisposeCondition/delete_Restricted_Case'); ?>',
                type: "POST",
                data: {
                    '<?= csrf_token() ?>': csrfToken,
                    list_diary: list_diary,
                    usercode: usercode
                },
                success: function(response) {
                    updateCSRFToken();

                    try {
                        var obj = $.parseJSON(response);
                        console.log('Parsed response:', obj);
                    } catch (error) {
                        console.error('JSON Parsing Error:', error);
                        showErrorAlert("Invalid response from server.");
                        alert("Invalid response from server.");
                        return;
                    }
                    $("#loader").html(''); // Clear the loader

                    if (obj && obj.Remove_Case === 'Deleted') {
                        showErrorAlert("Case removed successfully.");
                        alert("Case removed successfully.");
                        location.reload();
                    } else {
                        showErrorAlert("Unable to process the request. Please contact the Computer Cell.");
                        alert("Unable to process the request. Please contact the Computer Cell.");
                    }
                },
                error: function(xhr, status, error) {
                    updateCSRFToken();
                    
                    console.error("Error occurred: ", error);
                    showErrorAlert("Unable to process the request. Please contact the Computer Cell.");
                    alert("Unable to process the request. Please contact the Computer Cell.");
                },
                complete: function() {
                    updateCSRFToken(); // Ensure CSRF token is updated after the request
                }
            });
        }
    }



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
</script>


<?= view('sci_main_footer') ?>