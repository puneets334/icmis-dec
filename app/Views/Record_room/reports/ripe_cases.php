<?= view('header') ?>
<!-- <link rel="stylesheet" type="text/css" href="<?= base_url('/css/aor.css') ?>"> -->

<section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                    <div class="card-header heading">
    <div class="row">
        <div class="col-sm-10">
            <h3 class="card-title">Record Room >> Report >>&nbsp; Ripe Cases</h3>
        </div>

    </div>
</div>
<br>
<div class="container-fluid">


<div class="row">
        <?=session()->getFlashdata('msg'); ?>
    </div>
    <div class="">
        <form class="form-horizontal" id="push-form" name="push-form" >
        <?= csrf_field(); ?>
            <div class="box-body">
                <div class="row">


                    <div class="col-sm-4">
                    <div class="row" >
                        <div class="col-sm-6">
                            <label for="from_date" >Dispose Date From:</label>
                            <input type="text" id="fromDate" name="fromDate" class="form-control datepickFrom"  placeholder="From Date" value="01-01-2019">
                        </div>
                        <div class="col-sm-6">
                            <label for="to_date" >Dispose Date To:</label>
                            <input type="text" class="form-control datepickTo" id="toDate"  name="toDate" placeholder="To Date" value="31-12-2019">
                        </div>
                        </div>
                    </div>
              
                    <div class="col-sm-4">
                    <div class="row ">

                        <label class="col-sm-12 radio-label">Report Type</label>
                        <div class="col-sm-6 mt-3">
                            <input type="radio" class="form-check-input"  name="report_type" value="1" checked onchange="func_hall()">
                            &nbsp;&nbsp;&nbsp;&nbsp; Consolidated
                        </div>
                        <div class="col-sm-6  mt-3">
                            <input type="radio" class="form-check-input"  name="report_type" value="2" onchange="func_hall()">
                            &nbsp;&nbsp;&nbsp;&nbsp; Hall wise



                            <div class="row" id="div-hall">
                            <label class="col-sm-12">Select Hall </label>
                            <div class="col-sm-12">
                                <select class="form-control" id="hall_no" name="hall_no">
                                    <option value="">----</option>
                                    <?php
                                        for($x=1; $x<=15;$x++){
                                    ?>
                                    <option value="<?=$x?>">Hall No <?=$x?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>


                        </div>
                    </div>                       
                    </div>
               
                    <div  class="col-sm-4 mt-4">
                        <button type="button" id="view" name="view"  class="form-control btn btn-primary">View</button>
                    </div>
                </div>
            </div>
        </form>
        <div class="row">
             <div class="col-md-12">
    <div id="divRipeCases"></div>
</div></div>
</div>

<br/>
    </div> <!-- card div -->



</div>
<!-- /.col -->
</div>
<!-- /.row -->




</div>
<!-- /.container-fluid -->
</section>
<script>

    document.getElementById("div-hall").style.display = "none";
    function func_hall() {
        if(document.querySelector('input[name="report_type"]:checked').value==1)
            document.getElementById("div-hall").style.display = "none";
        else
            document.getElementById("div-hall").style.display = "block";
    }
    
    function getDate(date,addDays=null)
    {
        var numbers = date.match(/\d+/g);
        var formattedDate = new Date(numbers[2],numbers[1]-1, numbers[0]);
        return formattedDate;
    }

    $(document).ready(function() {
        var start_date = "01-01-2019"; // will be fixed as per requirement of BO,Record room
        var end_date = "31-12-2019"; // will be fixed as per requirement of BO,Record room
        var currentDateForStart=getDate(start_date);
        var endDateForStart=getDate(end_date);

        $(function () {
            $('.datepickFrom').datepicker({
                format: 'dd-mm-yyyy',
                autoclose:true
            }).datepicker("setDate", currentDateForStart);
        });
        $(function () {
            $('.datepickTo').datepicker({
                format: 'dd-mm-yyyy',
                autoclose:true
            }).datepicker("setDate", endDateForStart);
        });

    });

    $(".alert").delay(4000).slideUp(200, function() {
        $(this).alert('close');
    });

    $('#view').click(function() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var from_date = $('#fromDate').val();
        var to_date = $('#toDate').val();
        var formatted_from_date = getDate(from_date);
        var formatted_to_date = getDate(to_date);
        var diff = new Date(formatted_to_date - formatted_from_date);
        var days = diff / 1000 / 60 / 60 / 24;

        if (days <= 1365 && days >= 0) {
            $('#view').prop('disabled', true); // Disable the button
            $.ajax({
                type: 'POST',
                url: "<?=base_url();?>/Record_room/record/ripe_cases",
                beforeSend: function (xhr) {
                    $("#divRipeCases").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?=base_url()?>/images/load.gif'></div>");
                },
                data: {
                    fromDate: from_date,
                    toDate: to_date,
                    reportType: document.querySelector('input[name="report_type"]:checked').value,
                    hall_no: $('#hall_no').val(),
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                }
            })
            .done(function (result) {
                updateCSRFToken();
                $("#divRipeCases").html(result);
                $('#view').prop('disabled', false); // Enable the button after data is loaded
            })
            .fail(function () {
                updateCSRFToken();
                alert("ERROR, Please Contact Server Room");
                $("#divRipeCases").html();
                $('#view').prop('disabled', false); // Enable the button in case of failure
            });

        } else if (days <= 0) {
            alert("To date cannot be less than from date");
            $("#divRipeCases").html();
        } else {
            alert("Date differences cannot be exceeded than 1 year(365 days)");
            $("#divRipeCases").html();
        }
    });


</script>