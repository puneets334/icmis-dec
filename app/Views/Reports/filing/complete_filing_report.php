<div class="active tab-pane" id="defective_case">
    <?php $attribute = array('class' => 'form-horizontal complete_filing_search_form', 'name' => 'complete_filing_search_form', 'id' => 'complete_filing_search_form', 'autocomplete' => 'off');
    echo form_open('#', $attribute);  ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <div class="row col-md-12">
                        <div class="col-sm-4">
                            <div class="form-group row">
                                <label for="From" class="col-form-label">From</label>
                                <div class="col-sm-9">
                                    <input required type="date" max="<?php echo date("Y-m-d"); ?>" class="form-control" id="report_date" name="report_date" placeholder="Enter Date" value="<?php if (!empty($formdata['report_date'])) {
                                                                                                                                                                                                echo $formdata['report_date'];
                                                                                                                                                                                            } ?>">
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-4">
                            <div class="form-group row">
                                <label for="Section" class="col-form-label"></label>
                                <div class="col-sm-6">
                                    <select name="report_for" id="report_for" class="custom-select rounded-0">
                                        <option value="0">All</option>
                                        <option value="584">Person In Petition</option>
                                        <option value="C">Caveat</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-5">
                        </div>
                        <div class="col-sm-7">
                            <input type="submit" name="complete_filing_report_btn" id="complete_filing_report_btn" class="complete_filing_report_btn btn btn-primary" value="Search">
                            <input type="reset" name="reset_search" id="reset_search" class="reset_search btn btn-primary" value="Reset">
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!--/.col (right) -->
    <?= form_close() ?>

</div>
<!-- /.DAK -->
<div id="dak_result_data"></div>
<div id="category_detailed_report_loading"></div>

<div id="model-show-proposal" data-bs-backdrop='static' data-bs-keyboard="false" class="modal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header" style="position: relative;">
            <h5 class="modal-title">Records for Pending Verification after Re-filing</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>        
        <div class="modal-body" style="padding-top: 0 !important;" id="category_detailed_report" ></div>
    </div>
  </div>
</div>

 
<!-- <script src="<?php //echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php //echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script> -->

<script>
    $('#complete_filing_search_form').on('submit', function() {
        //$("#category_detailed_report").html('');
        var report_date = $("#report_date").val();
        if (report_date.length == 0) {
            alert("Please select from date.");
            $("#report_date").focus();
            validationError = false;
            return false;
        }      
            
            var form_data = $(this).serialize();
           
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Reports/Filing/Report/get_complete_filing_report'); ?>",
                    data: form_data,
                    beforeSend: function() {
                        $('.complete_filing_report_btn').val('Please wait...');
                        $('.complete_filing_report_btn').prop('disabled', true);
                        $('#category_detailed_report_loading').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                    },
                    success: function(data) {
                        updateCSRFToken();
                        $('#category_detailed_report_loading').html('');
                        $('.complete_filing_report_btn').prop('disabled', false);
                        $('.complete_filing_report_btn').val('Search');
                        $("#dak_result_data").html(data);
                       
                    },
                    error: function() {
                        updateCSRFToken();
                        $('#category_detailed_report_loading').html('');
                        $("#dak_result_data").html(data);
                    }
                });
                return false;
            
         
    });

    function get_complete_filing_details(report_type, type) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('.alert-error').hide();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('Reports/Filing/Report/get_complete_filing_details'); ?>",
            data: {
                report_for: report_type,
                type: type,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#category_detailed_report_loading').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            success: function(data) {
                $('#category_detailed_report_loading').html('');
                $("#category_detailed_report").html(data);
                $('#model-show-proposal').modal('show');
                
                updateCSRFToken();
            },
            error: function() {
                updateCSRFToken();
                $('#category_detailed_report_loading').html('');
                $('#model-show-proposal').modal('show');
                $("#category_detailed_report").html(data);
            }

        });
        return false;
    }


    $(document).one("click", "#print2", function () {
    var prtContent = $("#printDiv2").html();
    var WinPrint = window.open('', '', 'left=10,top=0,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
    WinPrint.document.write(prtContent);
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
   // WinPrint.close();
});
</script> 