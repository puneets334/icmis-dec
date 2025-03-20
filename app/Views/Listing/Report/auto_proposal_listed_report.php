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
                                <h3 class="card-title"> Auto Proposal</h3>
                            </div>
                        </div>
                    </div>
                    <!-- Main content start -->
                    <div class="col-md-18">
                        <div class="card-body">
                        <form class="form-inline" >
                        <?= csrf_field() ?>
                        <div class="row">
                           
                            <div class="col-md-12">
                                <div class="form-group">
                                <label class="text-left">Click on Button if Court Remark in Misc. and Regular is Completely Done.</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mt-4">
                                <label class="text-left">Action<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></label>
                                <button id="btn_rpt_search" name="btn_rpt_search" type="button" class="p-2 btn btn-primary">Submit</button>
                                </div>
                            </div>
                            
                        </div>
                        <div class="row">
                           
                            
                        </div>
                        </form>
                            

                            <div class="form-row col-12 mt-4">
                                <div class="col-3 mb-3" id="Diary_no_cnt"></div>
                                <div class="col-4 mb-3"></div>
                            </div>
                            <div id="result_cnt"></div>
                            <div id="result_dis"></div>
                           
                        </div>
                    </div><!-- Main content end -->
                </div> <!-- /.card -->
            </div>
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<script>
   
    
    $("#btn_rpt_search").click(function(){
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        if (confirm("Are you sure want to Run Cron?")) {
        $.ajax({
            url: "<?= base_url('Listing/AutoProposal/auto_proposal_action'); ?>",
            cache: false,
            async: true,
            data: {search_rpt_cnt:'Rpt_search_cnt_diary', CSRF_TOKEN:CSRF_TOKEN_VALUE},
            beforeSend:function(){
                updateCSRFToken();
                $('#result_cnt').html('<table widht="100%" align="center"><tr><td><img src="../../../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(Cntdata, status) {
                updateCSRFToken();
                 $("#result_cnt").html('');
                 $('#result_dis').html(Cntdata);
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    } else {
        // User clicked Cancel, do nothing or optionally add some code here
        // e.g., console.log("User cancelled the report generation.");
    }
    });//END OF BTN_RPT_SEARCH()..

   
</script>