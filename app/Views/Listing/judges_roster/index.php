<?= view('header') ?>
<style>
    @media print {
        table {
            page-break-inside: avoid;
        }

        @page {
            margin-left: 0.65cm !important;
            margin-top: 0.78cm !important;
        }
    }

    .roundslider {
        display: inline-block;
    }
    
    .showSweetAlert{
        top: 326px;
    }
</style>

<link rel="stylesheet" href="<?php echo base_url('assets/vendor/sweetalert2/sweetalert2.css') ?>">

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title"> Judges Sitting List(Under Development) </h3>
                            </div>
                        </div>
                    </div>
                    <!-- Main content start -->
                    <div class="col-md-12">
                        <div class="card-body">
                            <form id="judgesOnLeave" method="post">
                                <div class="row" id="divCauselistDate">
                                    <div class="form-group col-sm-2">
                                        <label for="causelistDate">Listing Date</label>
                                        <input type="text" id="causelistDate" name="causelistDate" autocomplete="off" class="form-control dtp"
                                            required placeholder="Listing Date">
                                    </div>
                                </div>
                                <div id="judgesLeaveDetail"></div>
                            </form>
                            <div id="divResult"></div>
                            <?= csrf_field() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?= base_url('assets/js/multiselect.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/sweetalert2/sweetalert2.js') ?>"></script>

<script>
    $(function() {
        /*$('.dtp').datepicker({
            format: 'dd-mm-yy',
            todayHighlight: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'

        });*/


        $('.dtp').on('changeDate', async function(e) {
            e.preventDefault();
            var causelistDate = $("#causelistDate").val();
            var confirmation = false;
            if (causelistDate == "") {
                alert("Select causelistDate Date.");
                $("#causelistDate").focus();
                return false;
            }
            
            // await updateCSRFToken();
            await updateCSRFTokenSync();
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.post("<?php echo base_url('Listing/JudgesRoster/ifSittingPlanExist'); ?>", {
                'causelistDate': causelistDate,
                'confirmation': confirmation,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            }, async function(result) {
                // console.log(result);
                await updateCSRFTokenSync();
                //result ='holiday';
                if (result == 'holiday') {
                    Swal.fire({
                        icon: "warning",
                        text: 'It seems, dated ' + causelistDate + ' is a holiday. Do you still want to create Sitting Plan?',
                        showCloseButton: false,
                        showCancelButton: true,
                        confirmButtonText: "No, Enter New Date",
                        cancelButtonText: "Yes, Continue with the same",
                        customClass: {
                            confirmButton: "btn-danger",
                            cancelButton: "btn-success"
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            return false; // No, Enter New Date action
                        } else {
                            $("#judgesLeaveDetail").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                            setTimeout(function(){
                                // await updateCSRFTokenSync()
                                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                                confirmation = true;
                                $.post("<?= base_url('Listing/JudgesRoster/ifSittingPlanExist'); ?>", {
                                    'causelistDate': causelistDate,
                                    'confirmation': confirmation,
                                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                                }, function(result) {
                                    // updateCSRFTokenSync();
                                    $("#judgesLeaveDetail").html(result);
                                });
                            }, 1500)    
                        }
                    });
   
                } else {
                    $("#judgesLeaveDetail").html(result);
                }
            });

        });

    });

    async function check() {
        var causelistDate = $("#causelistDate").val();
        if (causelistDate == "") {
            alert("Select causelistDate Date.");
            $("#causelistDate").focus();
            return false;
        }
        selectAll();
        await updateCSRFTokenSync();
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        var formData = $("#judgesOnLeave").serialize() + "&CSRF_TOKEN=" + CSRF_TOKEN_VALUE;        
        $.ajax({
            url: "<?php echo base_url('Listing/JudgesRoster/saveJudgesOnLeave'); ?>",
            cache: false,
            async: true,
            data: formData,
            beforeSend: function(result) {
                $("#judgesLeaveDetail").hide();
                $("#divCauselistDate").hide();
                $("#divResult").show();
                $("#divResult").html(result);
            },
            type: 'POST',
            success: function(data, status) {
                $('#divResult').html(data);
            },
            error: function(xhr) {
                // updateCSRFTokenSync()
                //alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    }

    function selectAll() {
        for (var i = 0; i < document.getElementById("undo_redo_to").options.length; i++) {
            document.getElementById("undo_redo_to").options[i].selected = true;
        }

        for (var i = 0; i < document.getElementById("undo_redo").options.length; i++) {
            document.getElementById("undo_redo").options[i].selected = true;
        }
    }

    function checkIfLeaveDetailExist(data) {
        alert(data);
    }

    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }

    function finalizeSittingPlan() {
        causelistDate = $("#causelistDate").val();
        Swal.fire({
            title: "Are you sure?",
            text: "You will not be able to change the seating plan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: 'Yes, I am sure!',
            cancelButtonText: 'No, cancel it!',
            dangerMode: true,
        }).then((result) => {
            if (result.isConfirmed) {

                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.post("<?php echo base_url('Listing/JudgesRoster/finalizeSittingPlan'); ?>", {
                    'causelistDate': causelistDate,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                }, function(result) {
                    if (result == 1) {
                            Swal.fire({
                                title: 'Finalized',
                                text: 'Sitting Plan for dated ' + causelistDate + ' finalized.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                            $('.dtp').trigger('changeDate');
                    } else {
                        Swal.fire("Error", "There is some problem while finalizing sitting plan.", "error");
                    }
                });
            }
        });
    }
</script>