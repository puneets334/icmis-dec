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
                                <h3 class="card-title">Report</h3>
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
                                                <!-- <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="csrf_token" /> -->
                                                <?=csrf_field(); ?>
                                                <div class="row">

                                                    <div class="col-md-5 diary_section">
                                                        <div class="form-group row">
                                                            <label for="inputEmail3" class="col-sm-5 col-form-label">List Date From</label>
                                                            <div class="col-sm-7">
                                                                <input type="date" id="to_date_addon" name="to_date_addon" class="form-control to_date" required="" placeholder="Date...">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5 diary_section">
                                                        <div class="form-group row">
                                                            <label for="inputEmail3" class="col-sm-5 col-form-label">Diary Movement.<span style="color:red;">*</span></label>
                                                            <div class="col-sm-5">
                                                                <select class="form-control " id="mvmnt_type_flag_id">
                                                                    <option value="ALL">ALL</option>
                                                                    <option value="receive">Received</option>
                                                                    <option value="return">Returned</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="form-group row">
                                                            <div class="col-sm-7">
                                                                <button type="button" id="btn_rpt_search" name="view" value="date_wise" class="btn btn-block btn-primary">Search</button>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="dv_data">
                                                    <table id="head" style="display:none;">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Search Item No</th>
                                                                <th>Search by Diary No</th>
                                                                <!--<th>Search by Contact Details</th>-->
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                        </thead>
                                                    </table>

                                                    <table id="reportTable1" class="m-1 table table-striped table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th style="width:5%; font-weight:bold;">#</th>
                                                                <th style="width:5%;font-weight:bold;">Item No</th>
                                                                <th style="width:15%;font-weight:bold;">Diary No</th>
                                                                <th style="width:15%;font-weight:bold;">List Date</th>
                                                                <th style="width:10%;font-weight:bold;">Movement</th>
                                                                <th style="width:10%;font-weight:bold;">Event</th>
                                                                <th style="width:10%;font-weight:bold;">Date / Time</th>
                                                                <th style="width:10%;font-weight:bold;">User Name</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </form>
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
   /*  $(document).ready(function() {
        $('#reportTable1').DataTable({
            "processing": true,
            "serverSide": false,
            "paging": true
        });
    }); */


    $("#btn_rpt_search").click(function() {
        // Get input values
        var To_date = $(".to_date").val();
        var movement_flag_type = $("#mvmnt_type_flag_id").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        // Make AJAX request
        $.ajax({ // Use base_url() to set the URL dynamically
            url: "<?php echo base_url('scanning/search_rpt'); ?>",
            type: 'POST',
            data: {
                search_rpt: 'Rpt_search',
                To_date: To_date,
                movement_flag_type: movement_flag_type,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            cache: false,
            async: true,
            success: function(data) {
                updateCSRFToken();
                // Parse the JSON response
                if (typeof data === 'object') {
                    var rdata = data;
                } else {
                    var rdata = JSON.parse(data); // Ensure this is valid JSON
                }
                // console.log(rdata);
                // Loop through the response data and add it to the DataTable
                $('#reportTable1 tbody').html(rdata.html);
        
                // If you want to refresh the DataTable after injecting HTML
                // table.rows.add($(rdata.html)).draw(); 
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });
</script>