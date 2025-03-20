<?= view('header'); ?>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header heading">
                            <h3 class="card-title">Sensitive Cases Listed</h3>
                        </div>
                        <div class="card-body">
                            <?php
                            echo form_open();
                            csrf_token();
                            ?>
                            <p id="show_error"></p> <!-- This Segment Displays The Validation Rule -->


                            <div class="card-body">



                                <div class="form-row ">


                                    <div class="input-daterange input-group mb-3 col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8" id="app_date_range">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="from_date_addon">Listing Date <span style="color:red;">*</span></span>
                                        </div>
                                        <input type="text" class="form-control bg-white from_date dtp" id="listing_dts_from" aria-describedby="from_date_addon" placeholder="From Date..." readonly>
                                        <span class="input-group-text" id="to_date_addon">to</span>
                                        <input type="text" class="form-control bg-white to_date dtp" id="listing_dts_to" aria-describedby="to_date_addon" placeholder="To Date..." readonly>

                                    </div>
                                </div>

                                <div class="form-row">
                                    <input id="btngetr" name="btn_search" type="button" class="btn btn-success ml-2" value="Search">
                                </div>



                            </div>
                            <?php echo form_close(); ?>
                            <br/>
                        <br/>
                        <hr/>
                        <div class="container">
                        <div id="printable">
                                <p align="center" id="tbltitle" style="font-weight: bold;"></p>
                        </div>
                            <div class="panel">
                                <table id="reportTable1" class="table table-striped table-hover " style="display:none;">
                                    <thead>
                                    <tr>
                                    <th>Item No.</th>
                                    <th>Case No.</th>
                                    <th>Cause Title</th>
                                    <th>List Date</th>
                                    <th>Coram</th>
                                    <th>Listed Before</th>
                                    <th>Sensitive Reason</th>
                                    <th>List Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                
                    </div>
                    </div>
                        </div>
                        <div class="col-md-12 m-2 p-2" id="result"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>


<script>
     $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
    });

    $(document).on("click", "#btngetr", function() {
        $('#dv_res1').html("");
        $('#reportTable1').show();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        var listing_dts_from = $("#listing_dts_from").val();
        var listing_dts_to = $("#listing_dts_to").val();
       
       
        $.ajax({
            url: '<?php echo base_url('Listing/Report/sensitive_listed_get'); ?>',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: csrf,
                from_date: listing_dts_from,
                to_date: listing_dts_to
            },
            beforeSend: function() {
                $('#dv_res1').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(Resultdata, status) { 
                 updateCSRFToken();
                console.log('Raw Resultdata:', Resultdata);
                
                // Parse the result data
                var res = JSON.parse(Resultdata);
                var rdata = res.data;
                var title = res.title;
                 $("#tbltitle").html(title);
                console.log('Parsed Data:', rdata);
               
                if (rdata.length === 0) {
                    //$('#reportTable1').hide();
                    if ($.fn.DataTable.isDataTable('#reportTable1')) {
                        $('#reportTable1').DataTable().destroy();
                    }

                } else {
                // Initialize DataTable (or reinitialize if needed)
                var table = $('#reportTable1').DataTable({
                    "destroy": true, // Allow reinitialization
                    "paging": true,
                    "searching": true,
                    "lengthChange": true,
                    "data": rdata, // Pass the data to DataTable
                    "columns": [
                        { "data": "Item_No" },
                        { "data": "Case_No" },
                        { "data": "Cause_Title" },
                        { "data": "List_Date" },
                        { "data": "Coram" },
                        { "data": "Listed_Before" },
                        { "data": "Sensitive_Reason" },
                        { "data": "List_Status" }
                    ],
                    dom: 'Bfrtip',  // Add the button options
                    buttons: [
                    {
                        extend: 'print',  // Print button
                        text: 'Print All Data',  // Button text
                        title: title,  // Title for the printed page
                        customize: function (win) {
                            $(win.document.body).css('font-size', '12pt');
                            $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Export to Excel',
                        title: title,
                        filename: title // Use the title as the filename
                    },
                    {
                        extend: 'pdf',
                        text: 'Export to PDF',
                        title: title,
                        filename: title,
                        orientation: 'landscape',  // Optional: Set orientation
                        pageSize: 'A4',         // Optional: Set page size
                        customize: function (doc) {
                            // Optional: Customize the PDF document
                            // For example, you can change the font size, etc.
                            doc.defaultStyle.fontSize = 10;
                        }
                    }
            ]
        });
                $('#dv_res1').html("");
                
    }
            },
            error: function(xhr) {
                $('#dv_res1').html("");
                console.log("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
        updateCSRFToken();
    });
    // $(document).on('click', '#btn_search', function() {
    //     var CSRF_TOKEN = 'CSRF_TOKEN';
    //     var csrf = $("input[name='CSRF_TOKEN']").val();
    //     var from_date = $(".from_date").val();
    //     var to_date = $(".to_date").val();
    //     alert("dkdskdsj");
    //     if (from_date == '') {
    //         $('#result').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>From Date Required</strong></div>');
    //         $(".from_date").focus();
    //         return false;
    //     }
    //     if (to_date == '') {
    //         $('#result').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>To Date Required</strong></div>');
    //         $(".to_date").focus();
    //         return false;
    //     }
    //     $.ajax({
    //         url: '<?php echo base_url('Listing/Report/sensitive_listed_get'); ?>',
    //         type: 'post',
    //         cache: false,
    //         async: true,

    //         // beforeSend: function() {
    //         //     $('#result').html('<table widht="100%" align="center"><tr><td>Loading...</td></tr></table>');
    //         // },
    //         data: {
    //             CSRF_TOKEN: csrf,
    //             from_date: from_date,
    //             to_date: to_date
    //         },

    //         success: function(data, status) {
    //             $('#result').html(data);
    //             updateCSRFToken();
    //         },
    //         error: function(xhr) {
    //             alert("Error: " + xhr.status + " " + xhr.statusText);
    //         }
    //     });
    //     updateCSRFToken();
    // });
</script>