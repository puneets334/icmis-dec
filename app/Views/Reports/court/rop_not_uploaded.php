
<div class="active tab-pane" id="fixedDateMatter">
    <?php
    $attribute = array('class' => 'form-horizontal fixed_date_matters_form','name' => 'fixed_date_matters_form', 'id' => 'fixed_date_matters_form', 'autocomplete' => 'off');
    echo form_open(base_url('#'), $attribute);
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <div class="row">
                    <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="From" class="col-sm-6 col-form-label">Causelist From Date</label>
                                <div class="col-sm-6">
                                    <input required type="text" class="form-control pickDate" id="causelistFromDate" name="causelistFromDate" placeholder="From Date" value="<?php if(!empty($formdata['from_date'])){ echo $formdata['from_date']; } ?>">
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-3">

                            <div class="form-group row">
                                <label for="To" class="col-sm-5 col-form-label">To Date</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control pickDate" id="causelistToDate" name="causelistToDate" placeholder="TO Date" value="<?php if(!empty($formdata['to_date'])){ echo $formdata['to_date']; } ?>">
                                </div>

                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group row">
                                <label for="From" class="col-sm-5 col-form-label">Select Hon’ble Judge </label>
                                <div class="col-sm-7">
                                    <select id="pJudge" name="pJudge" class="form-control">
                                                                                                         <?php
                                            foreach ($judge as $row) { ?>

                                                <option value="<?=sanitize(($row['jcode']))  ?>"><?=sanitize(strtoupper($row['jname']))?></option>
                                      <?php }?>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-2">
                        <span class="input-group-append">
                        <button type="button" id="btnGetCases" class="btn btn-info form-control" onclick="getList();">Get Cases </button>
                          </span>
                        </div>


                    </div>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>

    </div>
       <?= form_close()?>

 </div>
    <div id="result_data"></div>
    <center><span id="loader"></span> </center>
      </div>
   </div>
 </div>

<!-- <script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script> -->
<script>

$(function () {
        $(".pickDate").datepicker({
            format: 'dd-mm-yyyy',
            autoclose:true
        });
    });

    $('#fixed_date_matters_form_').on('submit', function () {
        if ($('#fixed_date_matters_form').valid()) {
            var validateFlag = true;
            var form_data = $(this).serialize();
            if(validateFlag){ //alert('readt post form');
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $("#loader").html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Reports/Court/Report/getROPNotUploaded'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $("#result_data").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                        //$('.fixed_date_matters').val('Please wait...');
                        //$('.fixed_date_matters').prop('disabled', true);
                    },
                    success: function (data) {
                        $('.fixed_date_matters').prop('disabled', false);
                        $('.fixed_date_matters').val('Search');
                        $("#result_data").html(data);
                        $("#loader").html('');

                        updateCSRFToken();
                    },
                    error: function () {
                        updateCSRFToken();
                    }

                });
                return false;
            }
        } else {
            return false;
        }
    });

    

    function getList() {
        //alert("1");
        var causelistFromDate =$('#causelistFromDate').val();
        var causelistToDate = $('#causelistToDate').val();
       
        date1 = new Date(causelistFromDate.split('-')[2],causelistFromDate.split('-')[1]-1,causelistFromDate.split('-')[0]);
        date2 = new Date(causelistToDate.split('-')[2],causelistToDate.split('-')[1]-1,causelistToDate.split('-')[0]);
     
        var timeDiff = Math.abs(date2.getTime() - date1.getTime());
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
        var pJudge = $('#pJudge').val();

        if(diffDays>15)
        {
            alert("Please Select time period of 15 days!");
            return false;
        }
        if(causelistFromDate == ""){
            alert("Please Select Causelist From Date..");
            $('#causelistFromDate').focus();
            return false;
        }
        if(causelistToDate == ""){
            alert("Please Select Causelist To Date..");
            $('#causelistFromDate').focus();
            return false;
        }
        if(pJudge == "" || pJudge == "0"){
            alert("Please Select Presiding Judge..");
            $('#pJudge').focus();
            return false;
        }


        judgeName=$("#pJudge option:selected").text();
        $("#hdnJudgeName").val(judgeName);
       
        if (causelistFromDate != "" && causelistToDate != "" && pJudge != ""){

            var CSRF_TOKEN = 'CSRF_TOKEN';
		    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $('#btn_submit').prop('disabled',true);
            $.ajax({
                    url: "<?php echo base_url('Reports/Court/Report/getROPNotUploaded'); ?>",                
                    data: {
                            CSRF_TOKEN: CSRF_TOKEN_VALUE,
                            causelistFromDate: causelistFromDate,
                            causelistToDate:causelistToDate,
                            pJudge:pJudge,
                          },
                    beforeSend: function () {
                        $('#result_data').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                    },
                    type: 'POST',
                    success: function(data, status) {
                        updateCSRFToken();
                        $('#result_data').html(data);
                        $('#btn_submit').prop('disabled',false);
                        
                    },
                    error: function(xhr) {
                        updateCSRFToken();
                        $('#btn_submit').prop('disabled',false);
                        alert("Error: " + xhr.status + " " + xhr.statusText);
                    }
            });


            
           /* $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            //alert("2");
            $.post("<?php echo base_url('Reports/Court/Report/getROPNotUploaded'); ?>", $("#frmROPNotUploaded").serialize(),function(result){
                updateCSRFToken();
                //alert(usercode);
                $('#loader').hide();
                $("#result_data").html(result);
                

            }); */
        }
    }

</script>


