<?= view('header') ?>
<style>
    .list-group-item:nth-child(even) {
        background-color: #e6f2ff;
    }

    .list-group-item:nth-child(odd) {
        background-color: #F5F5F5;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header heading">
                    <div class="row">
                        <div class="col-sm-10">
                            <h3 class="card-title">Report</h3>
                        </div>
                    </div>
                </div>

                <form method="post">
                    <?= csrf_field() ?>
                    <p id="show_error"></p>
                    <div class="card">
                        <div class="card-header bg-info text-white font-weight-bolder">Send VC Room URL (For Court Masters) &nbsp;&nbsp;&nbsp;
                            <label class="radio-inline text-black">
                                <input type="radio" class="selected_flag_radio" name="rdbtn_select" id="radio_all" value="1" checked> <span class="text-warning">ALL</span>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" class="selected_flag_radio" name="rdbtn_select" id="radio_selected" value="2"> <span class="text-warning">Selected (Ex. Consent received via Email, Portal etc.)</span>
                            </label>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="row ml-1">

                                    <div>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="next_dt_addon">Listing Date :<b> <?= date('d-m-Y') ?> </b> </span>
                                            </div>

                                            <input type="hidden" class="form-control datepick" id="next_dt" name="next_dt" placeholder="Select Date" value="<?= date('Y-m-d') ?>">
                                        </div>
                                    </div>

                                    <div>
                                        <div class="input-group mb-3">
                                            <div>
                                                <input id="btn_search" name="btn_search" type="button" class="btn btn-success" value="Search">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row col-md-12 m-0 p-0" id="loader"></div>
                                <div class="row col-md-12 m-0 p-0" id="result"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade " id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">

                </div>
            </div>
        </div>
    </div>
</div>
</section>



<script>
    $(document).on('click', '.selected_flag_radio', function() {
        $("#result").html('After appropriate selection, click on search button');
    });
    $(document).on('change', '#next_dt', function() {
        $("#result").html('After appropriate selection, click on search button');
    });
    $("#btn_search").click(function() {
        var next_dt = $("#next_dt").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        
        $('#result').html("");
        var flag = '';
        if ($("#radio_all").is(':checked')) {
            flag = $("#radio_all").val();
        } else if ($("#radio_selected").is(':checked')) {
            flag = $("#radio_selected").val();
        } else {
            alert("Radio button not selected.");
            return false;
        }

        if (next_dt.length == 0) {
            $('#next_dt').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Listing Date Required* </strong></div>');
            $("#next_dt").focus();
            return false;
        } else {
            $.ajax({
                
                  url: '<?php echo base_url('MasterManagement/VcRoom/courtMasterGet'); ?>',
                
                data: {
                    next_dt: next_dt,
                    flag: flag,
                    CSRF_TOKEN: csrf,
                },
                beforeSend: function() {
                    $('#result').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
                },
                type: 'POST',
                success: function(data, status) {
                    updateCSRFToken();
                    $("#result").html(data);
                },
                error: function(xhr) {
                    updateCSRFToken();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        }
    });


    $(document).on('click', '.btn_save_send', function() {
        var next_dt = $("#next_dt").val();
        var roster_id = $(this).data('roster_id');
        var courtno = $(this).data('courtno');
        var vc_url = $('.vc_url').filter('[data-roster_id="' + roster_id + '"]').val();
        var vc_item = $('.vc_item').filter('[data-roster_id="' + roster_id + '"]').val();
        var vc_item_csv = vc_item;
        var flag = '';
        if ($("#radio_all").is(':checked')) {
            flag = $("#radio_all").val();
        } else if ($("#radio_selected").is(':checked')) {
            flag = $("#radio_selected").val();
        } else {
            alert("Radio button not selected.");
            return false;
        }
        if (vc_item == '') {
            alert("Please enter valid item no.");
            return false;
        }
        if (vc_item == 0) {
            alert("Please enter valid item no.");
            return false;
        }
        console.log(vc_item_csv);
        if (next_dt.length == 0) {
            $('#next_dt').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Listing Date Required* </strong></div>');
            $("#next_dt").focus();
            return false;
        }
        //alert('next_dt='+next_dt); 14-01-2022
        $.ajax({
            url: 'vc_room_check.php',
            cache: false,
            async: true,
            data: {
                next_dt: next_dt,
                roster_id: roster_id,
                vc_url: encodeURI(vc_url),
                vc_items_csv: vc_item_csv,
                vc_item: vc_item,
                courtno: courtno
            },
            beforeSend: function() {
                $('#loader').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(data, status) {
                $('#loader').html('');
                var vc_item_csv = [vc_item];
                var resArr = data.split('@@@');
                if (resArr[0] == 1) {

                    swal({
                        title: "Are you sure?",
                        text: "Do you want to send eMail/SMS in Case No. " + resArr[1] + ' and Diary No. ' + resArr[2],
                        icon: "warning",
                        buttons: [
                            'No, cancel it!',
                            'Yes, I am sure!'
                        ],
                        dangerMode: true,
                    }).then(function(isConfirm) {
                        if (isConfirm) {
                            if (flag == 1) {
                                $.ajax({
                                    url: 'create_content_vc_email.php',
                                    cache: false,
                                    async: true,
                                    data: {
                                        next_dt: next_dt,
                                        roster_id: roster_id,
                                        vc_url: encodeURI(vc_url),
                                        vc_items_csv: vc_item_csv,
                                        vc_qry_from: 'vc_url_cm_a'
                                    },
                                    type: 'POST',
                                    success: function(data, status) {

                                    },
                                    error: function(xhr) {
                                        alert("Error: " + xhr.status + " " + xhr.statusText);
                                    }
                                });
                                $.ajax({
                                    url: 'create_content_vc_sms.php',
                                    cache: false,
                                    async: true,
                                    data: {
                                        next_dt: next_dt,
                                        roster_id: roster_id,
                                        vc_url: encodeURI(vc_url),
                                        vc_items_csv: vc_item_csv,
                                        vc_qry_from: 'vc_url_cm_a'
                                    },
                                    type: 'POST',
                                    success: function(data, status) {

                                    },
                                    error: function(xhr) {
                                        alert("Error: " + xhr.status + " " + xhr.statusText);
                                    }
                                });
                            } else {

                                $.ajax({
                                    url: 'create_content_vc_email_consent_recv.php',
                                    cache: false,
                                    async: true,
                                    data: {
                                        next_dt: next_dt,
                                        roster_id: roster_id,
                                        vc_url: encodeURI(vc_url),
                                        vc_items_csv: vc_item_csv,
                                        vc_qry_from: 'vc_url_cm_b'
                                    },
                                    type: 'POST',
                                    success: function(data, status) {

                                    },
                                    error: function(xhr) {
                                        alert("Error: " + xhr.status + " " + xhr.statusText);
                                    }
                                });
                                $.ajax({
                                    url: 'create_content_vc_sms_consent_recv.php',
                                    cache: false,
                                    async: true,
                                    data: {
                                        next_dt: next_dt,
                                        roster_id: roster_id,
                                        vc_url: encodeURI(vc_url),
                                        vc_items_csv: vc_item_csv,
                                        vc_qry_from: 'vc_url_cm_b'
                                    },
                                    type: 'POST',
                                    success: function(data, status) {

                                    },
                                    error: function(xhr) {
                                        alert("Error: " + xhr.status + " " + xhr.statusText);
                                    }
                                });
                                return false;
                            }

                            swal({
                                title: "Success!",
                                text: "Request Accepted",
                                icon: "success",
                                button: "Go Ahead!"
                            }).then(function() {
                                $('.vc_url_success').filter('[data-roster_id="' + roster_id + '"]').html("<div class='text-success'><strong>Send SMS/Email Success</strong></div>");
                            });

                        } else {
                            swal("Cancelled", "SMS/eMail not sent. Please try again:)", "error");
                            $('.vc_url_success').filter('[data-roster_id="' + roster_id + '"]').append("<div class='text-danger'>SMS/eMail Not Sent, Please try again!</div>");
                        }
                    });

                } else {
                    swal("Data not found", "Please try again :)", "error");
                    $('.vc_url_success').filter('[data-roster_id="' + roster_id + '"]').append("<div class='text-danger'>SMS/eMail Not Sent Please try again!</div>");
                }
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });

    });
</script>