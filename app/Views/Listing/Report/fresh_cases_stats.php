<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-md-10">
                                <h3 class="card-title">Fresh Cases Report</h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        echo form_open();
                        csrf_token();
                        ?>
                        <div id="dv_content1">
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="">From Date </label>
                                    <input type="text" size="10" class="form-control dtp" name='listing_dts_from' id='listing_dts_from' value="<?php echo date('d-m-Y'); ?>" readonly />
                                </div>
                                <div class="col-md-2">
                                    <label for="">To Date </label>
                                    <input type="text" size="10" class="form-control dtp" name='listing_dts_to' id='listing_dts_to' value="<?php echo date('d-m-Y'); ?>" readonly />
                                </div>
                                <div class="col-md-2 mt-4">
                                    <input type="button" id="btngetr" class="btn btn-block_ btn-primary" name="btngetr" value=" Get " />
                                </div>
                            </div>
                            <div id="res_loader"></div>
                            <div id="dv_res1"></div>
                        </div>
                        <?php echo form_close(); ?>
                        <br/>
                        <br/>
                        <hr/>
                        <div class="container">
                        <div id="printable">
                                <p align="center" style="font-weight: bold;">Freshly Filed Cases Listed & left over after allocation</p>
                        </div>
                            <div class="panel">
                                <table id="reportTable1" class="table table-striped table-hover " style="display:none;">
                                    <thead>
                                    <tr>
                                        <td>SrNo.</td>
                                        <td>Date of Listing</td>
                                        <td>Matters Available</td>
                                        <td>Matters Listed</td>
                                        <td>Matters Left after allocation</td>
                                        <td>No. of Courts</td>
                                    </tr>
                                    </thead>
                                    <tbody id="dv_res1">
                                    </tbody>
                                </table>
                                
                    </div>
                    </div>
                    </div>
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
</script>
<script>
    $(document).on("click", "#btngetr", function() {
        $('#dv_res1').html("");
        $('#reportTable1').show();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        var listing_dts_from = $("#listing_dts_from").val();
        var listing_dts_to = $("#listing_dts_to").val();
        var title = function() {
                return 'Freshly Filed Cases Listed & left over after allocation';  
           
        }
       
        $.ajax({
            url: '<?php echo base_url('Listing/Report/fresh_cases_stats_get'); ?>',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: csrf,
                listing_dts_from: listing_dts_from,
                listing_dts_to: listing_dts_to
            },
            beforeSend: function() {
                $('#dv_res1').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(Resultdata, status) { 
                 updateCSRFToken();
                console.log('Raw Resultdata:', Resultdata);
                
                // Parse the result data
                var rdata = JSON.parse(Resultdata);
                console.log('Parsed Data:', rdata);
               
                if (rdata.length === 0) {
                    //$('#reportTable1').hide();
                    if ($.fn.DataTable.isDataTable('#reportTable1')) {
                        $('#reportTable1').DataTable().destroy();
                    }
                   // $('#dv_res1').html("");
                    

                } else {
                // Initialize DataTable (or reinitialize if needed)
                var table = $('#reportTable1').DataTable({
                    "destroy": true, // Allow reinitialization
                    "paging": true,
                    "searching": true,
                    "lengthChange": true,
                    "data": rdata, // Pass the data to DataTable
                    "columns": [
                        { "data": "SNO" },
                        { "data": "Date_of_Listing" },
                        { "data": "Matters_Available" },
                        { "data": "Matters_Listed" },
                        { "data": "Matters_Left_after_allocation" },
                        { "data": "No_of_Courts" }
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

    $(document).on("click", "#prnnt1", function() {
        var prtContent = $("#prnnt").html();
        var temp_str = prtContent;
        var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,cellspacing=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>