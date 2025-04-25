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
                                <h3 class="card-title">Reports</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">Cases Listed In Advance And Daily List</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                        <?php
                                        echo form_open();
                                        csrf_token();
                                        ?>
                                               
                                                <input type="hidden" name="usercode" id="usercode" value="<?php echo $usercode; ?>" />
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <button type="button"  id="view" name="view" class="quick-btn mt-26">View REPORT</button>
                                                    </div>
                                                </div>
                                            
                                           
                            <?php echo form_close(); ?>
                            <div id="result" style="text-align: center;"></div>
                            <div id="reportTableContainer"></div>
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
    var reportTitle = "Cases Listed in Advance and Daily List";
    $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        "buttons": [
        {
            extend: 'excelHtml5',
            title: reportTitle
        },
        {
            extend: 'pdfHtml5',
            title: reportTitle
        },
        {
            extend: 'print',
            title: reportTitle
        }
    ]
    });

    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });

    });
    $(document).on("click", "#view", function() {
        var usercode = $('#usercode').val();
        $('#reportTableContainer').html("");
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: '<?php echo base_url('/ManagementReports/DA/DA/case_listed_Advance_Daily_dawise_get'); ?>',
            type: 'POST',
            data: {
                usercode:usercode,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                updateCSRFToken();
                $('#reportTableContainer').html('<table widht="100%" align="center"><tr><td><img src="../../../images/load.gif"/></td></tr></table>');
            },
            success: function(data) { 
                updateCSRFToken();
                console.log(data);
                $('#reportTableContainer').html('');
                $('#result').append(data);   
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });    
   
</script>