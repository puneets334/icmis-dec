
<div class="active tab-pane" id="CAV">
    <?php
    $attribute = array('class' => 'form-horizontal cav_search_form','name' => 'cav_search_form', 'id' => 'cav_search_form', 'autocomplete' => 'off');
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
                        <span class="input-group-append">
                        <input type="submit" name="cav_search" id="cav_search"  class="cav_search btn btn-primary" value="Search">
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
    $('#cav_search_form').on('submit', function () {
        if ($('#cav_search_form').valid()) {
            var validateFlag = true;
            var form_data = $(this).serialize();
            if(validateFlag){ //alert('readt post form');
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $("#loader").html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Reports/Court/Report/cav_search'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                        $('.cav_search').val('Please wait...');
                        $('.cav_search').prop('disabled', true);
                    },
                    success: function (data) {
                        $('.cav_search').prop('disabled', false);
                        $('.cav_search').val('Search');
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


