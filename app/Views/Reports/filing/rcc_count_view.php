<div class="active tab-pane" id="defective_case">
<?php $attribute = array('class' => 'form-horizontal rcc_count_form', 'name' => 'rcc_count_form', 'id' => 'rcc_count_form', 'autocomplete' => 'off');
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
                <input required type="text" max="<?php echo date("Y-m-d"); ?>" class="form-control dtp" id="from_date" name="from_date" placeholder="From Date" value="<?php if(!empty($formdata['from_date'])){ echo $formdata['from_date']; } ?>" >
            </div>
        </div>

    </div>
    <div class="col-sm-4">
        <div class="form-group row">
            <label for="To" class="col-form-label">To</label>
            <div class="col-sm-9">
                <input type="text" max="<?php echo date("Y-m-d"); ?>" class="form-control dtp" id="to_date" name="to_date" placeholder="To Date" value="<?php if(!empty($formdata['to_date'])){ echo $formdata['to_date']; } ?>">
            </div>
        </div>
    </div>
    <?php
        $caseType_model = new App\Models\Entities\Model_Casetype();
        $casetypes = $caseType_model->select('casecode,casename')->whereIn('casecode', [9,10,19,20,25,26])->where('display', 'Y')->orderBy('casename')->get()->getResultArray();
    ?>
    <div class="col-sm-4">
        <div class="form-group row">
            <label for="Section" class="col-form-label"></label>
            <div class="col-sm-6">
                <select name="report_for" id="report_for" class="custom-select rounded-0">
                    <option value="0">All</option>
                    <?php foreach ($casetypes as $casetype): ?>
                        <option value="<?=$casetype['casecode']?>"><?=$casetype['casename']?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-sm-5">
     </div>
      <div class="col-sm-7">
        <input type="submit" name="change_category_report_btn" id="change_category_report_btn"  class="change_category_report_btn btn btn-primary" value="Search">
        <input type="reset" name="reset_search" id="reset_search"  class="reset_search btn btn-primary" value="Reset">
    </div>
 </div>
</div>
<!-- /.card-body -->
</div>
<!-- /.card -->
</div>
</div>
<!--/.col (right) -->
<?= form_close()?>

</div>
<!-- /.DAK -->
<div id="dak_result_data"></div>
<div id="category_detailed_report"></div>
<!-- /.card -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
</div>
<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

 
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header" style="position: relative;border:1px solid #ccc;padding: 0px 12px;">
        <h5 class="modal-title" id="exampleModalLabel">R.P/Curative/Contempt Petition</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modal_id" style="padding-top:5px !important;">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>         
      </div>
    </div>
  </div>
</div>


<script>
$(document).ready(function() {
        $('.dtp').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'

        });

        $('#new_filing_date').trigger('focus');
    });

    $('#rcc_count_form').on('submit', function () {
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        if(from_date.length != 0) {
            var date1 = new Date(from_date.split('-')[0], from_date.split('-')[1] - 1, from_date.split('-')[2]);
            var date2 = new Date(to_date.split('-')[0], to_date.split('-')[1] - 1, to_date.split('-')[2]);
            if (date1 > date2 &&  date2 < date1  ) {
                alert("To Date must be greater than From date");
                $("#to_date").focus();
                validationError = false;
                return false;
            } else {
                if (from_date.length == 0) {
                    alert("Please select from date.");
                    $("#from_date").focus();
                    validationError = false;
                    return false;
                }
                else if (to_date.length == 0) {
                    alert("Please select to date.");
                    $("#to_date").focus();
                    validationError = false;
                    return false;
                }
            }
        }

        if ($('#rcc_count_form').valid()) {
            var validateFlag = true;
            var form_data = $(this).serialize();
            if(validateFlag){ //alert('readt post form');
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Reports/Filing/Report/get_rcc_count_report'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $('.change_category_report_btn').val('Please wait...');
                        $('.change_category_report_btn').prop('disabled', false);
                    },
                    success: function (data) {
                        $('.change_category_report_btn').prop('disabled', false);
                        $('.change_category_report_btn').val('Search');
                        $("#dak_result_data").html(data);
                        updateCSRFToken();
                    },
                    error: function () {
                        updateCSRFToken();
                        $("#dak_result_data").html(data);
                    }

                });
                return false;
            }
        } else {
            return false;
        }
    });

    function get_rcc_details(from_dt, to_date, condition, section){
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('.alert-error').hide();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('Reports/Filing/Report/get_rcc_case_details'); ?>",
            data: { from_dt : from_dt, to_date : to_date , condition : condition , section : section, CSRF_TOKEN:CSRF_TOKEN_VALUE },
            beforeSend: function () {
                //$('.change_category_report_btn').val('Please wait...');
                //$('.change_category_report_btn').prop('disabled', true);
            },
            success: function (data) {
                //$('.change_category_report_btn').prop('disabled', false);
                //$('.change_category_report_btn').val('Search');
                $("#modal_id").html(data);
                $('#exampleModal').modal('show');

                updateCSRFToken();
            },
            error: function () {
                updateCSRFToken();
                $("#modal_id").html(data);
            }

        });
        return false;
    }


    // function efiling_number(efiling_number) {
    //     var link = document.createElement("a")
    //     link.href = "<?php echo E_FILING_URL ?>/efiling_search/DefaultController/?efiling_number="+efiling_number
    //     link.target = "_blank"
    //     link.click()
    // }
</script>

