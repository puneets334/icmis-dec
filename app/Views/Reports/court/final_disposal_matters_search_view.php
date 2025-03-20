
<div class="active tab-pane" id="FinalDisposal">
    <?php
    $attribute = array('class' => 'form-horizontal final_disposal_search_form','name' => 'final_disposal_search_form', 'id' => 'final_disposal_search_form', 'autocomplete' => 'off');
    echo form_open(base_url('#'), $attribute);
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="judgeName" class="col-sm-5 col-form-label">Hon'ble Judge Name</label>
                                <div class="col-sm-7">
                                    <select id="judge" name="judge" class="form-control">
                                        <option value="k">All</option>
                                        <!--                                   --><?php
                                        //                                  foreach ($judge as $row) { ?>
                                        <!---->
                                        <!--                                    <option value=--><?//=sanitize(($row['casecode']))  ?><!-- --><?php //if(!empty($formdata['case_type_casecode'])&& sanitize(($row['casecode']==$formdata['case_type_casecode']))){  echo 'selected'; }?><!-->--><?//=sanitize(strtoupper($row['casename']))?><!--</option>-->
                                        <!--                                    --><?php //}
                                        //                                    ?>

                                        <option value="219">HON'BLE THE CHIEF JUSTICE</option><option value="254">HON'BLE MR. JUSTICE SANJAY KISHAN KAUL</option><option value="263">HON'BLE MR. JUSTICE K.M. JOSEPH</option><option value="266">HON'BLE MR. JUSTICE M.R. SHAH</option><option value="267">HON'BLE MR. JUSTICE AJAY RASTOGI</option><option value="269">HON'BLE MR. JUSTICE SANJIV KHANNA</option><option value="270">HON'BLE MR. JUSTICE B.R. GAVAI</option><option value="271">HON'BLE MR. JUSTICE SURYA KANT</option><option value="272">HON'BLE MR. JUSTICE ANIRUDDHA BOSE</option><option value="273">HON'BLE MR. JUSTICE A.S. BOPANNA</option><option value="274">HON'BLE MR. JUSTICE KRISHNA MURARI</option><option value="275">HON'BLE MR. JUSTICE S. RAVINDRA BHAT</option><option value="276">HON'BLE MR. JUSTICE V. RAMASUBRAMANIAN</option><option value="277">HON'BLE MR. JUSTICE HRISHIKESH ROY</option><option value="278">HON'BLE MR. JUSTICE ABHAY S. OKA</option><option value="279">HON'BLE MR. JUSTICE VIKRAM NATH</option><option value="280">HON'BLE MR. JUSTICE J.K. MAHESHWARI</option>
                                    </select>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                        <span class="input-group-append">
                        <input type="submit" name="final_disposal_search" id="final_disposal_search"  class="final_disposal_search btn btn-primary" value="Search">
                          </span>
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

<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

<script>
    $('#final_disposal_search_form').on('submit', function () {

        if ($('#final_disposal_search_form').valid()) {
            var validateFlag = true;
            var form_data = $(this).serialize();
            if(validateFlag){ //alert('readt post form');
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $("#loader").html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Reports/Court/Report/final_disposal_matters'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                        $('.final_disposal_search').val('Please wait...');
                        $('.final_disposal_search').prop('disabled', true);
                    },
                    success: function (data) {
                        $('.final_disposal_search').prop('disabled', false);
                        $('.final_disposal_search').val('Search');
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

</script>


