<?= view('header') ?>
<style>
    .select2-container .select2-selection--single{
        height: 38px;
    }

    div.dt-buttons {
        padding-left: 20px;
    }

    input[type="text"]{
        width: 0% !important;
    }

    .input-group {
        display: flex !important;    
    }

    #reportTable1_filter label {
        margin-right: 50px;  
    }

    .form-inline .form-control {
    display: inline-block;
    width: auto !important;
    vertical-align: middle;
}


</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"></script>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title"> Fresh Cases Never Listed Before Court</h3>
                            </div>
                        </div>
                    </div>
                    <!-- Main content start -->
                    <div class="col-md-18">
                        <div class="card-body">
                        <form class="form-inline" >
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-left">Diary Date From <span style="color:red;">*</span></label>
                                <input type="text" class="from_date form-control dtp bg-white" placeholder="From Date..." readonly />
                            </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-left">To <span style="color:red;">*</span></label>
                                    <input type="text" class="to_date form-control dtp bg-white" placeholder="To Date..." readonly />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-4">
                                <label class="text-left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:red;"></span></label>
                                    <button id="btn_rpt_search" name="btn_rpt_search" type="button" class="p-2 btn btn-primary">Search</button>
                                </div>
                            </div>
                        </div>
                        </form>
                            <!---<div class="p-2">
                                <form class="form-inline" >
                                    <?= csrf_field() ?>
                                    <div class="input-daterange input-group mb-3" id="app_date_range">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="from_date_addon">Diary Date From <span style="color:red;">*</span></span>
                                        </div>
                                        <input type="text" class="form-control bg-white from_date dtp" aria-describedby="from_date_addon" placeholder="From Date..." readonly>
                                        <span class="input-group-text" id="to_date_addon">to</span>
                                        <input type="text" class="form-control bg-white to_date" aria-describedby="to_date_addon" placeholder="To Date..." readonly>
                                        <span class="pl-2"></span>
                                        <button id="btn_rpt_search23" name="btn_rpt_search" type="button" class="p-2 btn btn-primary">Search</button>
                                    </div>
                                </form>
                            </div>-->

                            <div class="form-row col-12 mt-4">
                                <div class="col-3 mb-3" id="Diary_no_cnt"></div>
                                <div class="col-3 mb-3" id="Display_btn_rcd"></div>
                                <div class="col-4 mb-3"></div>
                            </div>
                            <div id="result_cnt"></div>
                            <div id="result_dis"></div>
                            <div class="panel">
                                <table id="reportTable1" class="table table-striped table-hover ">
                                    <thead>
                                        <tr>
                                            <th style="width:2%;">#</th>
                                            <th style="width:5%;">Case No.</th>
                                            <th style="width:8%;">Cause Title</th>
                                            <th style="width:4%;">Diary Date</th>
                                            <th style="width:4%;">Verification Date</th>
                                            <th style="width:4%;">Tentative Listing Date</th>
                                            <th style="width:4%;">Category</th>
                                            <th style="width:4%;">Coram</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- Main content end -->
                </div> <!-- /.card -->
            </div>
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<script>
    // $('#app_date_range').datepicker({
    //     autoclose: true,
    //     format: 'dd-mm-yyyy',
    //     container: '#app_date_range'
    // });
    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
    });
    
    $("#btn_rpt_search").click(function(){
        var from_date = $(".from_date").val();
        var to_date = $(".to_date").val();
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: "<?= base_url('Reports/Listing/FreshCase/fresh_cases_never_listed_report_action'); ?>",
            cache: false,
            async: true,
            data: {from_date:from_date,to_date:to_date,search_rpt_cnt:'Rpt_search_cnt_diary', CSRF_TOKEN:CSRF_TOKEN_VALUE},
            beforeSend:function(){
                updateCSRFToken();
                $('#result_cnt').html('<table widht="100%" align="center"><tr><td><img src="../../../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(Cntdata, status) {
                updateCSRFToken();
                var rpt_display_btn='';
                var Display_cnt_diary="<h3>"+ 'No. of Fresh Cases : '+ Cntdata+"</h3>" ;
                rpt_display_btn="<button class='btn btn-primary action' id='rpt_display_rcd' >" + "Display Record" + "</button>";
                $("#result_cnt").html('');
                $('#Diary_no_cnt').html(Display_cnt_diary);
                $('#Display_btn_rcd').html(rpt_display_btn);
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });

    });//END OF BTN_RPT_SEARCH()..

    
    $(document).ready(function () {
        $('#reportTable1').hide();


    });//End of document.ready functioin()..


    $(document).on('click', '#rpt_display_rcd', function() {
        $('#reportTable1').show();
        $('#total_rows').empty();
        
        var from_date = $(".from_date").val();
        var to_date = $(".to_date").val();
        var str_print_title_date = "";
        if(from_date == to_date){
            str_print_title_date = ' on dated '+from_date;
        }
        else{
            str_print_title_date = ' from dated '+from_date+' to '+to_date;
        }
        var currentdate = new Date();
        var datetime = " As on : " + currentdate.getDate() + "/"
            + (currentdate.getMonth()+1)  + "/"
            + currentdate.getFullYear() + " @ "
            + currentdate.getHours() + ":"
            + currentdate.getMinutes() + ":"
            + currentdate.getSeconds();

        $('#reportTable1').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'print',
                    title: function () {
                        return 'List of cases filed '+str_print_title_date+' which have not been listed even once '+ datetime
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7],
                        stripHtml: true
                    }
                }
            ]
        });

        //DataTable END
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: "<?= base_url('Reports/Listing/FreshCase/fresh_cases_never_listed_report_action'); ?>",
            cache: false,
            async: true,
            data: {
                Display_rpt:'Rpt_display_cases_data_list',
                diary_numbers:$("#diary_numbers").val(),
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                updateCSRFToken();
                $('#result_dis').html('<table widht="100%" align="center"><tr><td><img src="../../../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(Resultdata, status) {
                updateCSRFToken();
                console.log('Raw Resultdata:', Resultdata);
                
                // Parse the result data
                var rdata = JSON.parse(Resultdata);
                console.log('Parsed Data:', rdata);

                // Initialize DataTable (or reinitialize if needed)
                var table = $('#reportTable1').DataTable({
                    "destroy": true, // Allow reinitialization
                    "paging": true,
                    "searching": true,
                    "lengthChange": true,
                    "data": rdata, // Pass the data to DataTable
                    "columns": [
                        { "data": "SNO" },
                        { "data": "Case_NO" },
                        { "data": "Cause_Title" },
                        { "data": "Diary_Date" },
                        { "data": "Verification_Date" },
                        { "data": "Tentative_Listing_Date" },
                        { "data": "Category" },
                        { "data": "Coram" }
                    ],
                    dom: 'Bfrtip',  // Add the button options
                    buttons: [
                    {
                        extend: 'print',  // Print button
                        text: 'Print All Data',  // Button text
                        title: 'Data Report',  // Title for the printed page
                        customize: function (win) {
                            $(win.document.body).css('font-size', '12pt');
                            $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                        }
                    }
            ]
        });

        // Clear any loading text
        $("#result_dis").html('');
        },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    }); //END OF BTN_RPT_SEARCH()..

</script>