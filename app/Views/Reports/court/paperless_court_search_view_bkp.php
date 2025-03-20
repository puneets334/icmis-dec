
<div class="active tab-pane" id="Paperless">
    <?php
    $attribute = array('class' => 'form-horizontal paperless_search_form','name' => 'paperless_search_form', 'id' => 'paperless_search_form', 'autocomplete' => 'off');
    echo form_open(base_url('#'), $attribute);
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="From" class="col-sm-5 col-form-label">Cause List Date</label>
                                <div class="col-sm-7">
                                    <input type="date"  required max="<?php echo date("Y-m-d"); ?>" class="form-control" id="cause_list_date" name="cause_list_date" placeholder="Cause List Date"  value="<?php if(!empty($formdata['cause_list_date'])){ echo $formdata['cause_list_date']; } ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="Year" class="col-sm-5 col-form-label">Court No.</label>
                                <div class="col-sm-7">
                                       <select required name="courtno" id="courtno" class="form-control">
                                        <option value="0">-Select-</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                        <option value="21">21 (Registrar)</option>
                                        <option value="22">22 (Registrar)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                        <span class="input-group-append">
                        <input type="submit" name="paperless_court_search" id="paperless_court_search"  class="paperless_court_search btn btn-primary" value="Search">
                          </span>
                        </div>
                    </div>

            </div>
            <!-- /.card -->
        </div>

    </div>
       <?= form_close()?>

 </div>
         <div id="result_data"></div>
      </div>
   </div>
 </div>

<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

<script>
    $('#paperless_search_form').on('submit', function () {
        var cause_list_date = $("#cause_list_date").val();
        if ($('#paperless_search_form').valid()) {
            var validateFlag = true;
            var form_data = $(this).serialize();
            if(validateFlag){ //alert('readt post form');
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Reports/Court/Report/paperless_court'); ?>",
                    data: form_data,
                    beforeSend: function () {
                     //   $('.paperless_court_search').val('Please wait...');
                      //  $('.paperless_court_search').prop('disabled', true);
                    },
                    success: function (data) {
                       // $('.paperless_court_search').prop('disabled', false);
                        //$('.paperless_court_search').val('Search');
                        $("#result_data").html(data);

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


