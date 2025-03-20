<?= view('header') ?>

<style>
    p {
        color: red;
    }

    .input_error {
        border-color: red;
    }

    .select2-container {
        width: 100% !important;
    }

    .badge-info {
        background-color: #3a87ad;
    }
</style>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/smoothness/jquery-ui.css">


</head>



<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Weekly Single Judge Advance - Allocation Module </h3>
                            </div>


                        </div>
                    </div>
                    <br>
                    <form role="form" name="list_date_form" id="list_date_form">
                        <?= csrf_field() ?>
                        <div class="col-md-12">
                            <div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label for="used_from">Weekly From Date <span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" name="from_date" id="from_date" autocomplete="off" />
                                        <span id="error_from_date"></span>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="used_from">Weekly To Date <span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" name="to_date" id="to_date" autocomplete="off" />
                                        <span id="error_to_date"></span>
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="btn_click">Action</label>
                                        <input type="button" name="btn_click" id="btn_click" value="Get Details" class="form-control btn btn-primary" />

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <br>

                </div>
                <div class="msg" id="msg" style="padding-top: 5px;"></div>

                <div id="single_judges_advance_allocation_inputs">
                </div>
            </div>




</section>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    var image_loader_url = "<?php echo base_url('images/load.gif'); ?>";



    $(document).ready(function() {
        $('#from_date').datepicker({
            format: 'dd-mm-yyyy',
            startDate: new Date(),
            minDate: 0,
            autoclose: true
        });
        $('#to_date').datepicker({
            format: 'dd-mm-yyyy',
            startDate: new Date(),
            minDate: 0,
            autoclose: true
        });
    });



    $(document).on('click', '#btn_click', function(e) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('.msg').html('');
        $('#error_from_date').html("");
        $('#error_to_date').html("");
        $("#from_date").removeClass("input_error");
        $("#to_date").removeClass("input_error");
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        /*var day_type = $('#day_type').val();*/
        if (from_date == '') {
            $("#from_date").focus();
            $("#from_date").addClass("input_error");
            $('#error_from_date').html('<p>From Date Required.</p>').show();
            return false;
        } else if (to_date == '') {
            $("#to_date").focus();
            $("#to_date").addClass("input_error");
            $('#error_to_date').html('<p>To Date Required.</p>').show();
            return false;
        } else {

        singleJudgeAdvanceGet(from_date ,to_date);
        }

  

    });
    async function singleJudgeAdvanceGet(from_date ,to_date)
    {
        await updateCSRFTokenSync();
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({

                url: base_url + "/Listing/SingleJudgeAdvance/singleJudgeAdvanceGet",
                data: {
                    from_date: from_date,
                    to_date: to_date,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                beforeSend: function() {
                    $('#single_judges_advance_allocation_inputs').html('<table width="100%" style="margin: 0 auto;"><tr><td style="text-align: center;"><img src="../../images/load.gif"/></td></tr></table>');

                },
                type: 'POST',
                success: function(data, status) {
                    updateCSRFToken()
                    $("#single_judges_advance_allocation_inputs").html(data);
                },
                error: function(xhr) {
                    updateCSRFToken()
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
                });
    }


    $(document).on('change', '#all_roster', function() {
        if (this.checked) {
            $(".chk_roster").each(function() {
                this.checked = true;
            })
        } else {
            $(".chk_roster").each(function() {
                this.checked = false;
            })
        }
    });

    $(document).on('change', '#all_lp', function() {
        if (this.checked) {
            $(".chk_lp").each(function() {
                this.checked = true;
            })
        } else {
            $(".chk_lp").each(function() {
                this.checked = false;
            })
        }
    });

    $(document).on('click', '#btn_allocate', function()
    {
        var formValues= $("#single_judge_advance_form").serialize();
        var from_date_selected = $('#from_date_selected').val();
        var to_date_selected = $('#to_date_selected').val();
        var to_date = $('#to_date').val();
        var number_of_cases = $('#number_of_cases').val();
        var chk_lp = $('input[name="chk_lp[]"]:checked').map(function() {
            return this.value;
        }).get();


        var from_date = $('#from_date').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",

            url: base_url + "/Listing/SingleJudgeAdvance/singleJudgeAdvanceAllocationAction",
            beforeSend: function() {
                // $('.msg').html('<table widht="100%" align="center"><tr><td><img src="' + image_loader_url + '"/></td></tr></table>');
                $('.msg').html('<table width="100%" style="margin: 0 auto;"><tr><td style="text-align: center;"><img src="../../images/load.gif"/></td></tr></table>');
            },
            data: {
                from_date_selected: from_date_selected,
                to_date_selected: to_date_selected,
                to_date: to_date,
                number_of_cases: number_of_cases,
                chk_lp: chk_lp,
                from_date: from_date,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            cache: false,
            dataType: "JSON",
            success: function(data) {
                if (data.status == 'success')
                {
                    updateCSRFToken()
                    console.log(data.status);
                    $("#btn_click").click();
                    $('.msg').html('<div class="callout callout-success text-bold"><i class="fa fa-bullhorn"></i>&nbsp;&nbsp;' + data.msg + '</div>');
                } else {
                    updateCSRFToken()
                    console.log(data.status);
                    $('.msg').html('<div class="callout callout-danger text-bold"><i class="fa fa-bullhorn"></i>&nbsp;&nbsp;' + data.msg + '</div>');
                }
            },
            error: function(xhr) {
                    updateCSRFToken()
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
        });
    });

    $(document).on('click', '#send_to_pool', function()
    {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Sending all the cases into pool.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, I am sure!',
            cancelButtonText: 'No, cancel it!',
            dangerMode: true,
        }).then((result) => {
            if (result.isConfirmed) {
                var formValues = $("#single_judge_advance_form").serialize();
                $.ajax({
                    url: base_url + "/Listing/SingleJudgeAdvance/singleJudgeAdvanceCasesSendToPool",
                    cache: false,
                    async: true,
                    data: formValues,
                    beforeSend: function() {
                        //$('.msg').append('<table width="100%" align="center"><tr><td><img src="' + image_loader_url + '"/></td></tr></table>');
                        $('.msg').html('<table width="100%" style="margin: 0 auto;"><tr><td style="text-align: center;"><img src="../../images/load.gif"/></td></tr></table>');
                        msg
                    },
                    type: 'POST',
                    dataType: "JSON",
                    success: function(data, status) {
                        updateCSRFToken()
                        console.log(data);
                        if (data.status === 'success') {
                            updateCSRFToken()

                            console.log(data.status);
                            $("#btn_click").click();
                            $('.msg').append('<div class="callout callout-success text-bold"><i class="fa fa-bullhorn"></i>&nbsp;&nbsp;' + data.msg + '</div>');

                        } else {
                            updateCSRFToken()
                            console.log(data.status);
                            $('.msg').append('<div class="callout callout-danger text-bold"><i class="fa fa-bullhorn"></i>&nbsp;&nbsp;' + data.msg + '</div>');
                        }
                    },
            error: function(xhr) {
                    updateCSRFToken()
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
                });
            } else {
                Swal.fire('Cancelled', '', 'error');
            }
        });
    });
</script>