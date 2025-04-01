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
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Scanning > Movement > Receive / Return </h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form id="push-form" method="POST" action="">
                                                <?= csrf_field(); ?>
                                                <div class="row">
                                                    <div class="form-row col-12">
                                                        <div class="input-group col-3 mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="mainhead_addon">List Type</span>
                                                            </div>
                                                            <div class="border">
                                                                <label class="radio-inline text-black ml-1 mt-0 mb-0 p-1">
                                                                    <input type="radio" name="mainhead_select" id="radio_m" value="M" checked> Misc.
                                                                </label>
                                                                <label class="radio-inline mt-0 mb-0 p-1">
                                                                    <input type="radio" name="mainhead_select" id="radio_f" value="F"> Regular
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="input-group col-3 mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="list_date_addon">List Date</span>
                                                            </div>
                                                            <input type="text" class="form-control bg-white dtp list_date"
                                                                aria-describedby="list_date_addon" placeholder="Date..." readonly>
                                                        </div>
                                                        <div class="input-group col-3 mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="courtno_addon">Court No.<span style="color:red;">*</span></span>
                                                            </div>
                                                            <select class="form-control courtno" aria-describedby="courtno_addon">
                                                                <option value="0">-Select-</option>
                                                                <?php
                                                                $options = [
                                                                    31 => "1 (VC)",
                                                                    32 => "2 (VC)",
                                                                    33 => "3 (VC)",
                                                                    34 => "4 (VC)",
                                                                    35 => "5 (VC)",
                                                                    36 => "6 (VC)",
                                                                    37 => "7 (VC)",
                                                                    38 => "8 (VC)",
                                                                    39 => "9 (VC)",
                                                                    40 => "10 (VC)",
                                                                    41 => "11 (VC)",
                                                                    42 => "12 (VC)",
                                                                    43 => "13 (VC)",
                                                                    44 => "14 (VC)",
                                                                    45 => "15 (VC)",
                                                                    46 => "16 (VC)",
                                                                    47 => "17 (VC)",
                                                                    61 => "1 (VC R1)",
                                                                    62 => "2 (VC R2)",
                                                                    1 => "1",
                                                                    2 => "2",
                                                                    3 => "3",
                                                                    4 => "4",
                                                                    5 => "5",
                                                                    6 => "6",
                                                                    7 => "7",
                                                                    8 => "8",
                                                                    9 => "9",
                                                                    10 => "10",
                                                                    11 => "11",
                                                                    12 => "12",
                                                                    13 => "13",
                                                                    14 => "14",
                                                                    15 => "15",
                                                                    16 => "16",
                                                                    17 => "17",
                                                                    21 => "21 (Registrar)",
                                                                    22 => "22 (Registrar)"
                                                                ];

                                                                foreach ($options as $value => $label) {
                                                                    echo "<option value=\"$value\">$label</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <!--XXXXXXXXXXXXXXXX-->
                                                        <div class="input-group col-3 mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Diary Movement.<span style="color:red;">*</span></span>
                                                            </div>
                                                            <select class="form-control " id="mvmnt_type_id">
                                                                <option value="ALL">ALL</option>
                                                                <option value="receive">Eligible To Receive</option>
                                                                <option value="return">Eligible To Return</option>
                                                                <option value="already_return">Already Returned</option>
                                                            </select>
                                                        </div>
                                                        <!--XXXXXXXXXXXXXXX-->
                                                        <div class="col-3 pl-2 mb-3">
                                                            <input id="btn_search" name="btn_search" type="button" class="btn btn-success btn-block"
                                                                value="Search">
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Second Form Start Here -->
                                                <div class="row pt-3">
                                                    <div class="col-12 form-row">
                                                        <div class="input-group col-3 mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="case_search_addon">Search By</span>
                                                            </div>
                                                            <div class="border">
                                                                <label class="radio-inline text-black mt-0 mb-0 p-1">
                                                                    <input type="radio" name="rdbtn_select" id="radioct" value="1" checked> Case No.
                                                                </label>
                                                                <label class="radio-inline mt-0 mb-0 p-1">
                                                                    <input type="radio" name="rdbtn_select" id="radiodn" value="0"> Diary No.
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="input-group col-3 mb-3 search_case">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="selct_addon">Case Type</span>
                                                            </div>
                                                            <select id="selct" name="selct" class="form-control" aria-describedby="selct_addon">
                                                                <option value="-1">Select</option>
                                                                <?php
                                                                if (count($caseType) > 0) {
                                                                    foreach ($caseType as $c) {
                                                                ?>
                                                                        <option value="<?= $c['casecode']; ?>"><?= $c['casename']; ?></option>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>

                                                        </div>
                                                        <div class="input-group col-3 mb-3 search_case">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="caseno_addon">Case No.</span>
                                                            </div>
                                                            <input type="text" class="form-control" aria-describedby="caseno_addon" id="case_no" name="case_no" onkeypress="return isNumber(event)" maxlength="6">
                                                        </div>
                                                        <div class="input-group col-3 mb-3 search_case">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="caseyr_addon">Case Year</span>
                                                            </div>

                                                            <select id="case_yr" aria-describedby="caseyr_addon" class="form-control">
                                                                <?php
                                                                $currently_selected = date('Y');
                                                                $earliest_year = 1950;
                                                                $latest_year = date('Y');
                                                                foreach (range($latest_year, $earliest_year) as $i) {
                                                                    print '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
                                                                }
                                                                ?>
                                                            </select>

                                                        </div>
                                                        <div class="input-group col-3 mb-3 search_diary">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="diaryno_addon">Diary No.</span>
                                                            </div>
                                                            <input type="text" class="form-control" id="t_h_cno" name="t_h_cno" aria-describedby="diaryno_addon" onkeypress="return isNumber(event)" maxlength="5" />
                                                        </div>
                                                        <div class="input-group col-3 mb-3 search_diary">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="diaryyr_addon">Diary Year</span>
                                                            </div>

                                                            <select id="t_h_cyt" aria-describedby="diaryyr_addon" class="form-control">
                                                                <?php
                                                                $currently_selected = date('Y');
                                                                $earliest_year = 1950;
                                                                $latest_year = date('Y');
                                                                foreach (range($latest_year, $earliest_year) as $i) {
                                                                    print '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
                                                                }
                                                                ?>
                                                            </select>

                                                        </div>
                                                        <div class="input-group col-3 mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="movement_type_addon">Movement Type.<span style="color:red;">*</span></span>
                                                            </div>
                                                            <select class="form-control courtno" aria-describedby="movement_type_addon" id="mvmnt_type_case">
                                                                <option value="ALL">ALL</option>
                                                                <option value="receive">Eligible To Received</option>
                                                                <option value="return">Eligible To Return</option>
                                                                <option value="already_return">Already Returned</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-2 pl-4 mb-3">
                                                            <input id="btn_search_case" name="btn_search_case" type="button" class="btn btn-primary btn-block"
                                                                value="Search">
                                                        </div>
                                                    </div>
                                                    <!-- Second Form End Here -->
                                            </form>
                                            <div class="row col-md-12 m-0 p-0" id="result"></div>
                                            <div id="dv_data"></div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        function updateCSRFToken() {
            $.get('<?= site_url('Scanning/SupremeCourtScan/SupremeCourtScanController/getCSRF'); ?>', function(data) {
                $('input[name="<?= csrf_token() ?>"]').val(data.csrf_token);
            }, 'json');
        }
    });

    $(document).on("focus", ".list_date", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
    });
    $("#btn_search").click(function() {

        var movement_flag_type = $('#mvmnt_type_id').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $("#result").html("");
        $('#show_error').html("");
        var list_date = $(".list_date").val();
        var courtno = $(".courtno").val();

        if ($("#radio_m").is(':checked')) {
            var mainhead = $("#radio_m").val();
        }
        if ($("#radio_f").is(':checked')) {
            var mainhead = $("#radio_f").val();
        }

        if (list_date.length == 0) {
            $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select cause list date</strong></div>');
            $("#from_date").focus();
            return false;
        } else if (courtno == 0) {
            $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select court number</strong></div>');
            $("#applicant_type").focus();
            return false;
        } else {
            $.ajax({
                url: '<?= base_url('scanning/SaccaningController/scanMoveProcess') ?>', // Update this URL for CodeIgniter routing
                cache: false,
                async: true,
                data: {
                    search_flag: 'list_detail',
                    list_date: list_date,
                    courtno: courtno,
                    mainhead: mainhead,
                    movement_flag_type: movement_flag_type,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                },
                beforeSend: function() {
                    $('#result').html('<table width="100%" align="center"><tr><td><img src="<?= base_url("images/load.gif") ?>" /></td></tr></table>');
                },
                type: 'POST',
                success: function(data, status) {
                    updateCSRFToken();
                    $('#result').html('');
                    $("#dv_data").html(data);
                },
                error: function(xhr) {
                    updateCSRFToken();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });

        }
    });
    $(document).on('click', '#btn_search_case', function() {
        $("#result").html("Record Not Found").css({
            "color": "red",
            "text-align": "center",
            "display": "block",
            "font-weight": "bold" // Optional for emphasis
        });

    });


    $(document).on('click', ".action", function() {
        var saveID = $(this).attr('id');
        var trid = $(this).closest('tr').attr('id');

        var tdid1 = trid + 1;
        //var tdid2=trid + 2;
        var tdid3 = trid + 3;
        var tdid4 = trid + 4;
        var tdid5 = trid + 5;
        var tdid6 = trid + 6;
        var tdid7 = trid + 7;
        var tdid8 = trid + 8;
        var tdid9 = trid + 9;

        var id = $('#' + tdid1).text();
        // var diaryno=$('#'+tdid2).text();
        var cause_title = $('#' + tdid3).text();
        var movement = saveID;
        if (movement == 'receive') {
            var msg = 'Received';


        } else {
            var msg = 'Return';
        }

        //var event=$('#'+tdid5).text();
        var event_id = $('input[name="eventid"]:checked').val();
        var clientIP = "10.40.186.78";
        var roster_id = $('#' + tdid6).val();
        var item_no = $('#' + tdid7).val();
        var diaryno = $('#' + tdid8).val();
        var list_dt = $('#' + tdid9).val();
        var csrf = $('input[name="<?= csrf_token() ?>"]').val();

        if (typeof event_id == 'undefined' && movement == 'receive') {
            $('#' + tdid4).append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Event Type Required* </strong></div>');
            //$('#'+tdid4).focus();
            return false;
        } else {
            $.ajax({
                url: '<?= base_url('scanning/SaccaningController/add_update_scanning_movement') ?>',
                cache: false,
                async: true,
                data: {
                    id: id,
                    diaryno: diaryno,
                    cause_title: cause_title,
                    movement: movement,
                    event: event_id,
                    action: 'save_record',
                    clientIP: clientIP,
                    roster_id: roster_id,
                    item_no: item_no,
                    list_dt: list_dt,
                    '<?= csrf_token() ?>': csrf,
                },
                beforeSend: function() {
                    $('#result').html('<table widht="100%" align="center"><tr><td><img src="<?= base_url(); ?>/images/load.gif"/></td></tr></table>');
                },
                type: 'POST',
                success: function(resultData) {
                    /*alert(resultData);
                    console.log(resultData);
                    return;*/
                    
                    $('#result').html('');
                    var btn = '';
                    var event_btn = '';

                    if (resultData == 1) {

                        if (movement == 'receive') {
                            btn = "<button class='btn btn-primary action' data-ctn='1'  id='return' style=\'background-color: #8A2624\' >" + "Returned" + "</button>";
                            event_btn = "<td></td>";
                            $('#' + tdid4).html('');
                            $('#' + tdid4).html(event_btn);

                            // $('#'+trid).closest('tr').children('td,th').css('background-color','lightcoral');
                            $('#' + tdid5).html('');
                            $('#' + tdid5).html(btn);

                        } else {
                            $('#' + tdid4).html('');
                            //$('#' + tdid4).html(event_btn);
                            $('#' + tdid5).html("<span class='text-success'>Successfully returned</span>");
                            //$('#' + tdid5).html(btn);
                            //$('#'+trid).closest('tr').children('td,th').css('background-color','');
                        }

                        swal({
                                title: 'Diary ' + msg + ' Successfully!',
                                text: "You clicked the button!",
                                type: "success"
                            },
                            function() {
                                //location.reload();

                            });


                        //tdid5
                    } else if (resultData == 0) {
                        swal({
                                title: 'Diary ' + msg + ' Not Successfully!',
                                text: "You clicked the button!",
                                type: "error"
                            },
                            function() {
                                // location.reload();
                            });

                    } else {
                        //alert("User Not Found !")
                        swal({
                                title: "User Not Found !",
                                text: "You clicked the button!",
                                type: "error"
                            },
                            function() {
                                location.reload();
                            });
                    }
                } //END OF SUCCESS Function()..
            });
        }

    });
</script>


<script>
    $(function() {
        $('.list_date').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy'
        });
    });

    // $("#btn_search").click(function() {

    //     var movement_flag_type = $('#mvmnt_type_id').val();
    //     //alert(movement_flag_type);
    //     /*$("#result").html('');
    //     return;*/
    //     $("#result").html("");
    //     $('#show_error').html("");
    //     var list_date = $(".list_date").val();
    //     var courtno = $(".courtno").val();

    //     if ($("#radio_m").is(':checked')) {
    //         var mainhead = $("#radio_m").val();
    //     }
    //     if ($("#radio_f").is(':checked')) {
    //         var mainhead = $("#radio_f").val();
    //     }

    //     if (list_date.length == 0) {
    //         $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select cause list date</strong></div>');
    //         $("#from_date").focus();
    //         return false;
    //     } else if (courtno == 0) {
    //         $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select court number</strong></div>');
    //         $("#applicant_type").focus();
    //         return false;
    //     } else {
    //         $.ajax({
    //             url: 'scan_move_processs.php',
    //             cache: false,
    //             async: true,
    //             data: {
    //                 search_flag: 'list_detail',
    //                 list_date: list_date,
    //                 courtno: courtno,
    //                 mainhead: mainhead,
    //                 movement_flag_type: movement_flag_type
    //             },
    //             beforeSend: function() {
    //                 $('#result').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
    //             },
    //             type: 'POST',
    //             success: function(data, status) {
    //                 //$("#result").html('');
    //                 $("#result").html(data);
    //             },
    //             error: function(xhr) {
    //                 alert("Error: " + xhr.status + " " + xhr.statusText);
    //             }
    //         });

    //     }
    // });


    $(document).ready(function() {
        $(".search_diary").hide();

        $(document).on('click', '#radiodn', function() {
            $(".search_diary").show();
            $(".search_case").hide();
            $('#result').html('');
            $("#t_h_cno").removeAttr('disabled');
            $("#t_h_cyt").removeAttr('disabled');
            $("#selct").prop('disabled', true);
            $("#case_no").prop('disabled', true);
            $("#case_yr").prop('disabled', true);
            $("#selct").val("-1");
            $("#case_no").val("");
            $("#case_yr").val("");
        });
        //                        $("#radiodn").click(function(){
        //
        //    });
        $(document).on('click', '#radioct', function() {
            $(".search_diary").hide();
            $(".search_case").show();
            $('#result').html('');
            $("#t_h_cno").prop('disabled', true);
            $("#t_h_cyt").prop('disabled', true);
            $("#t_h_cno").val("");
            $("#t_h_cyt").val("");
            //
            $("#selct").removeAttr('disabled');
            $("#case_no").removeAttr('disabled');
            $("#case_yr").removeAttr('disabled');
        });

        function get_case_details_page() {
            var movement_flag_casetype = $('#mvmnt_type_case').val();
            var d_no = document.getElementById('t_h_cno').value;
            var d_yr = document.getElementById('t_h_cyt').value;
            get_res_all = '';
            var t_h_cno, t_h_cyt, cstype, csno, csyr, fno;
            var regNum = new RegExp('^[0-9]+$');
            var chk_status = 0;
            var csrf = $('input[name="<?= csrf_token() ?>"]').val();

            if ($("#radioct").is(':checked')) {
                cstype = $("#selct").val();
                csno = $("#case_no").val();
                csyr = $("#case_yr").val();
                chk_status = 1;
                if (!regNum.test(cstype)) {
                    alert("Please Select Casetype");
                    $("#selct").focus();
                    return false;
                }
                if (!regNum.test(csno)) {
                    alert("Please Fill Case No in Numeric");
                    $("#case_no").focus();
                    return false;
                }
                if (!regNum.test(csyr)) {
                    alert("Please Fill Case Year in Numeric");
                    $("#case_yr").focus();
                    return false;
                }
                if (csno == 0) {
                    alert("Case No Can't be Zero");
                    $("#case_no").focus();
                    return false;
                }
                if (csyr == 0) {
                    alert("Case Year Can't be Zero");
                    $("#case_yr").focus();
                    return false;
                }

            } else if ($("#radiodn").is(':checked')) {

                var t_h_cno = $('#t_h_cno').val();
                var t_h_cyt = $('#t_h_cyt').val();
                chk_status = 2;
                if (t_h_cno.trim() == '') {
                    alert("Please enter Diary No.");
                    $('#t_h_cno').focus();
                    return false;
                }
                if (t_h_cyt.trim() == '') {
                    alert("Please enter Diary Year");
                    $('#t_h_cyt').focus();
                    return false;
                }
                var fno = t_h_cno + t_h_cyt;
            }

            $.ajax({
                url: '<?= base_url('scanning/SaccaningController/scanningmovement_search_flagcase') ?>',
                cache: false,
                async: true,
                beforeSend: function() {
                    $('#result').html('<table widht="100%" align="center"><tr><td>Loading...</td></tr></table>');
                },
                data: {
                    search_flag: 'case',
                    d_no: d_no,
                    d_yr: d_yr,
                    fno: fno,
                    ct: cstype,
                    cn: csno,
                    cy: csyr,
                    chk_status: chk_status,
                    movement_flag_type: movement_flag_casetype,
                    '<?= csrf_token() ?>': csrf,
                },

                type: 'GET',
                success: function(data, status) {
                    /*alert(data);
                    console.log(data);
                    return;*/


                    $('#result').html(data);
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }

            });
        } //END OF FUNCTION get_case_details_page()..
        $(document).on('click', '#btn_search_case', function() {
            // alert("hulalalalal");
            get_case_details_page();

        });
    });


    function isNumber(e) {
        e = e || window.event;
        var charCode = e.which ? e.which : e.keyCode;
        return /\d/.test(String.fromCharCode(charCode));
    } //End of function isNumber ..
</script>