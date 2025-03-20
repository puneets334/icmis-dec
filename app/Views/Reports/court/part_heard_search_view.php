
<div class="active tab-pane" id="partHeard">
    <?php
    $attribute = array('class' => 'form-horizontal part_heard_search_form','name' => 'part_heard_search_form', 'id' => 'part_heard_search_form', 'autocomplete' => 'off');
    echo form_open(base_url('#'), $attribute);
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <div class="row">
                           <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="judgeName" class="col-sm-5 col-form-label">Hon'ble Judge Name</label>
                                <div class="col-sm-7">
                                  <select id="judge" name="judge" class="form-control">

                                   <?php
                                  foreach ($judge as $row) { ?>
                                      <?php  if(($usertype == 1 or $usertype == 2 or $usertype == 3 or $usertype == 4) && $row['jcode']==0) {?>
                                          <option value="0">All</option>
                                      <?php }else{ ?>

                                    <option value=<?=sanitize(($row['jcode']))  ?> <?php if(!empty($formdata['judge'])&& sanitize(($row['jcode']==$formdata['judge']))){  echo 'selected'; }?>><?=sanitize(strtoupper($row['jname']))?></option>
                                    <?php } } ?>
                                    </select>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="Mainhead" class="col-sm-5 col-form-label">Mainhead</label>
                                <div class="col-sm-7">
                                    <select id="mr" name="mr" class="form-control">
                                        <option value="l">ALL</option>
                                        <option value="M">Miscellaneous</option>
                                        <option value="F">Regular</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">

                            <div class="form-group row">
                                <label for="report_type" class="col-sm-5 col-form-label">Report Type</label>
                                <div class="col-sm-7">
                                    <select id="report_type" name="report_type" class="form-control">
                                        <option value="l">ALL</option>
                                        <option value="S">Special Bench (Except PH)</option>
                                        <option value="P">Part Heard</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                        <span class="input-group-append">
                        <input type="submit" name="part_heard_search" id="part_heard_search"  class="part_heard_search btn btn-primary" value="Search">
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
<center><span id="loader"></span> </center>
<div id="result_data"></div>
</div>
</div>
</div>

<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

<script>
    $('#part_heard_search_form').on('submit', function () {
        if ($('#part_heard_search_form').valid()) {
            var validateFlag = true;
            var form_data = $(this).serialize();
            if(validateFlag){ //alert('readt post form');
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $("#loader").html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Reports/Court/Report/part_heard_search'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                        $('.part_heard_search').val('Please wait...');
                        $('.part_heard_search').prop('disabled', true);
                    },
                    success: function (data) {
                        $('.part_heard_search').prop('disabled', false);
                        $('.part_heard_search').val('Search');
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


