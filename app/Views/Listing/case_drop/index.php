<?=view('header') ?>
<style>
 
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">CASE DROP MODULE</h3>
                            </div>
                           
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <span class="alert-danger"><?=\Config\Services::validation()->listErrors()?></span>

                                    <?php if(session()->getFlashdata('error')){ ?>
                                        <div class="alert alert-danger text-white ">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?= session()->getFlashdata('error')?>
                                        </div>
                                    <?php } else if(session("message_error")){ ?>
                                        <div class="alert alert-danger">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?=session()->getFlashdata("message_error")?>
                                        </div>
                                    <?php }else{?>
                                        <br/>
                                    <?php }?>

                                    <?php
                                   
                                    echo form_open();
                                    ?>
                                    <?php echo component_html();?>

                                   
                                           <center> <button type="submit" class="btn btn-primary" name="btnGetR" id="submit">GET DETAILS</button></center>
                                    <?php form_close();?>

                                     <div id="report_result"></div>
                                     

                                   <!--  <center><span id="loader"></span> </center>
                                    <span class="alert alert-error" style="display: none; color: red;">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <span class="form-response"> </span>
                                    </span> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
    <script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>
    <script>
        // $(document).ready(function()
        // {
        //     $('#component_search').on('submit', function ()
        //     {

        //         var search_type = $("input[name='search_type']:checked").val();


        //         if (search_type.length == 0) {
        //             alert("Please select case type");
        //             validationError = false;
        //             return false;
        //         }
        //         var diary_number = $("#diary_number").val();
        //         var diary_year =$('#diary_year :selected').val();
                

        //         var case_type =$('#case_type :selected').val();
        //         var case_number = $("#case_number").val();
        //         var case_year =$('#case_year :selected').val();

        //         if (search_type=='D') {
                
        //             if (diary_number.length == 0) {
                        
        //                 alert("Please enter diary number");
        //                 validationError = false;
        //                 return false;
        //             }else if (diary_year.length == 0) {
        //                 alert("Please select diary year");
        //                 validationError = false;
        //                 return false;
        //             }
        //         }else if (search_type=='C') {
        //             //alert('Case details');

        //             if (case_type.length == 0) {
        //                 alert("Please select case type");
        //                 validationError = false;
        //                 return false;
        //             }else if (case_number.length == 0) {
        //                 alert("Please enter case number");
        //                 validationError = false;
        //                 return false;
        //             }else if (case_year.length == 0) {
        //                 alert("Please select case year");
        //                 validationError = false;
        //                 return false;
        //             }

        //         }

        //         if ($('#component_search').valid()) {
        //             var validateFlag = true;
        //             var form_data = $(this).serialize();
        //             //console.log(form_data);

        //             if(validateFlag){
        //                 var CSRF_TOKEN = 'CSRF_TOKEN';
        //                 var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        //                 $('.alert-error').hide(); $(".form-response").html("");
        //                 $("#loader").html('');
                       
        //                 $.ajax({
        //                     type: "POST",
        //                     url: "<?php echo base_url('Listing/DropNoteAdvance/case_drop_info/'); ?>",
        //                     data: form_data,
        //                     beforeSend: function () {
                               
        //                         $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
        //                     },
        //                       success: function (data) {
        //                       // console.log(data);
        //                         updateCSRFToken();
        //                         var resArr1 = data;
        //                         var resArr = data.split('@@@');
        //                         if (resArr1) {
        //                             updateCSRFToken();
        //                             $('.alert-error').hide();
        //                             $(".form-response").html("");
        //                             $('#report_result').html(resArr1);
        //                         } else{
        //                             $('#div_result').html('');
        //                             $('.alert-error').show();
        //                             $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
        //                         }
        //                     },
        //                     error: function() {
        //                         updateCSRFToken();
        //                         alert('Something went wrong! please contact computer cell');
        //                     }
        //                 });
        //                 return false;
        //             }
        //         } else {
        //             return false;
        //         }
        //     });
        // });
        $(document).ready(function () {
            $("input[name=btnGetR]").click(function () {
                alert('hlo');
        //         $('#show_fil').html(""); 
        //         $('#di_rslt').html("");          
        call_getDetails(0);
    });
 
    });

    // Initialize datepicker with unavailable dates


    // Toggle fields based on the radio button selection
    $("#radiodn").click(function () {
        $("#dno").removeProp('disabled');
        $("#dyr").removeProp('disabled');
        $("#selct").prop('disabled', true);
        $("#case_no").prop('disabled', true);
        $("#case_yr").prop('disabled', true);
        $("#selct").val("-1");
        $("#case_no").val("");
        $("#case_yr").val("");
    });

    $("#radioct").click(function () {
        $("#dno").prop('disabled', true);
        $("#dyr").prop('disabled', true);
        $("#dno").val("");
        $("#dyr").val("");
        $("#selct").removeProp('disabled');
        $("#case_no").removeProp('disabled');
        $("#case_yr").removeProp('disabled');
    });

   
    
    });

    // Fetch drop reasons based on selection
    $(document).on("change", "#ready_not", function () {
        var ready_not = $("#ready_not").val();
        $.ajax({
            url: 'case_drop_get_reason.php',
            cache: false,
            async: true,
            data: { ready_not: ready_not },
            type: 'POST',
            success: function (data) {
                $('#drop_reason_select').html(data);
            },
            error: function (xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });

    // Drop button click event to handle case drop
    $(document).on("click", "#drop_btn", function () {
        $('#di_rslt').html("");
        var dno = $("#drop_diary").val();
        var ldates = $("#ldates").val();
        var ready_not = $("#ready_not").val();
        $.ajax({
            url: 'case_drop_now.php',
            cache: false,
            async: true,
            data: { dno: dno, ldates: ldates, ready_not: ready_not },
            type: 'POST',
            beforeSend: function () {
                $('#di_rslt').html('<table width="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            success: function (data) {
                $('#show_fil').html("");
                $('#di_rslt').html(data);
            },
            error: function (xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });

    // Drop button with note click event
    $(document).on("click", "#drop_btn_note", function () {
        var next_dt = $("#next_dt").val();
        var brd_slno = $("#brd_slno").val();
        var partno = $("#partno").val();
        var dno = $("#drop_diary").val();
        var roster_id = $("#roster_id").val();
        var drop_reason_select = $("#drop_reason_select").val();
        var drop_rmk = $("#drop_rmk").val();
        var mainhead = $("#mainhead").val();
        var ldates = $("#ldates").val();
        var ready_not = $("#ready_not").val();
        var is_printed = $("#is_printed").val();

        if (is_printed === 'B' && drop_reason_select == 0) {
            alert('Please Select Reason.');
            $("#drop_reason_select").focus();
            return false;
        }

        if (drop_rmk.trim().length < 8 && (is_printed === 'Y' || (is_printed === 'B' && ready_not == 6))) {
            alert('Please Enter Drop Reason with minimum 8 characters.');
            $("#drop_rmk").focus();
            return false;
        }

        $.ajax({
            url: 'drop_note_now.php',
            cache: false,
            async: true,
            data: {
                next_dt: next_dt,
                brd_slno: brd_slno,
                dno: dno,
                roster_id: roster_id,
                drop_rmk: drop_rmk,
                mainhead: mainhead,
                ldates: ldates,
                ready_not: ready_not,
                partno: partno,
                is_printed: is_printed,
                drop_reason_select: drop_reason_select
            },
            type: 'POST',
            beforeSend: function () {
                $('#di_rslt').html('<table width="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            success: function (data) {
                $('#show_fil').html("");
                $('#di_rslt').html(data);
            },
            error: function (xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });

    // Function to get case details based on input
    function call_getDetails(fno_from_del) {
        var diaryno, diaryyear, cstype, csno, csyr;
        var regNum = new RegExp('^[0-9]+$');
        var radio_flag = "";

        if ($("#radioct").is(':checked')) {
            cstype = $("#selct").val();
            csno = $("#case_no").val();
            csyr = $("#case_yr").val();
            radio_flag = "F";

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
            diaryno = $("#dno").val();
            diaryyear = $("#dyr").val();
            radio_flag = "D";

            if (!regNum.test(diaryno)) {
                alert("Please Enter Diary No in Numeric");
                $("#dno").focus();
                return false;
            }
            if (!regNum.test(diaryyear)) {
                alert("Please Enter Diary Year in Numeric");
                $("#dyr").focus();
                return false;
            }
            if (diaryno == 0) {
                alert("Diary No Can't be Zero");
                $("#dno").focus();
                return false;
            }
            if (diaryyear == 0) {
                alert("Diary Year Can't be Zero");
                $("#dyr").focus();
                return false;
            }
        } else {
            alert('Please Select Any Option');
            return false;
        }

        $.ajax({
            type: 'POST',
            url: "field_case_drop.php",
            data: { dno: diaryno, dyr: diaryyear, ct: cstype, cn: csno, cy: csyr, radio_flag: radio_flag },
            beforeSend: function () {
                $("#show_fil").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../../images/load.gif'></div>");
            },
            success: function (msg) {
                $("#show_fil").html(msg);
            },
            error: function () {
                alert("ERROR, Please Contact Server Room");
            }
        });
    }
});

    </script>
