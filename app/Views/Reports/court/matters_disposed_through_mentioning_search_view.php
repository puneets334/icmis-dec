
<div class="active tab-pane" id="mdtm">
    <?php
    $attribute = array('class' => 'form-horizontal mdtm_search_form','name' => 'mdtm_search_form', 'id' => 'mdtm_search_form', 'autocomplete' => 'off');
    echo form_open(base_url('#'), $attribute);
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="From" class="col-sm-4 col-form-label">From</label>
                                <div class="col-sm-8">
                                    <input type="text" max="<?php echo date("d-m-Y"); ?>" class="form-control dtp" id="from_date" name="from_date" placeholder="From Date"  value="<?php if(!empty($formdata['from_date'])){ echo $formdata['from_date']; } ?>" required >
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="To" class="col-sm-4 col-form-label">To</label>
                                <div class="col-sm-8">
                                    <input type="text" max="<?php echo date("d-m-Y"); ?>" class="form-control dtp" id="to_date" name="to_date" placeholder="TO Date" value="<?php if(!empty($formdata['to_date'])){ echo $formdata['to_date']; } ?>" required >
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="Year" class="col-sm-5 col-form-label">Mentioned in Court No.</label>
                                <div class="col-sm-7">
                                  <select name="courtno" id="courtno_" class="form-control select2" required>
                                            <option value="">SELECT</option>
                                            <option value="1">Hon'ble Court No.1</option>
                                            <option value="2">Hon'ble Court No.2</option>
                                            <option value="3">Hon'ble Court No.3</option>
                                            <option value="4">Hon'ble Court No.4</option>
                                            <option value="5">Hon'ble Court No.5</option>
                                            <option value="6">Hon'ble Court No.6</option>
                                            <option value="7">Hon'ble Court No.7</option>
                                            <option value="8">Hon'ble Court No.8</option>
                                            <option value="9">Hon'ble Court No.9</option>
                                            <option value="10">Hon'ble Court No.10</option>
                                            <option value="11">Hon'ble Court No.11</option>
                                            <option value="12">Hon'ble Court No.12</option>
                                            <option value="13">Hon'ble Court No.13</option>
                                            <option value="14">Hon'ble Court No.14</option>
                                            <option value="15">Hon'ble Court No.15</option>
                                            <option value="16">Hon'ble Court No.16</option>
                                            <option value="17">Hon'ble Court No.17</option>
                                            <option value="31">Hon'ble Virtual Court No.1</option>
                                            <option value="32">Hon'ble Virtual Court No.2</option>
                                            <option value="33">Hon'ble Virtual Court No.3</option>
                                            <option value="34">Hon'ble Virtual Court No.4</option>
                                            <option value="35">Hon'ble Virtual Court No.5</option>
                                            <option value="36">Hon'ble Virtual Court No.6</option>
                                            <option value="37">Hon'ble Virtual Court No.7</option>
                                            <option value="38">Hon'ble Virtual Court No.8</option>
                                            <option value="39">Hon'ble Virtual Court No.9</option>
                                            <option value="40">Hon'ble Virtual Court No.10</option>
                                            <option value="41">Hon'ble Virtual Court No.11</option>
                                            <option value="42">Hon'ble Virtual Court No.12</option>
                                            <option value="43">Hon'ble Virtual Court No.13</option>
                                            <option value="44">Hon'ble Virtual Court No.14</option>
                                            <option value="45">Hon'ble Virtual Court No.15</option>
                                            <option value="46">Hon'ble Virtual Court No.16</option>
                                            <option value="47">Hon'ble Virtual Court No.17</option>
                                        </select>
                                   </div>
                            </div>
                        </div>
                            <div class="col-sm-3">

                        <input type="submit" name="mdtm_search" id="mdtm_search"  class="mdtm_search btn btn-primary" value="Search">

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

<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

<script>

$(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            format: 'dd-mm-yyyy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
    });

    $('#mdtm_search_form').on('submit', function () {


        if ($('#mdtm_search_form').valid()) {
            var validateFlag = true;
            var form_data = $(this).serialize();
            if(validateFlag){ //alert('readt post form');
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $("#loader").html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Reports/Court/Report/matters_disposed_through_mm'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                        $('.mdtm_search').val('Please wait...');
                        $('.mdtm_search').prop('disabled', true);
                    },
                    success: function (data) {
                        $('.mdtm_search').prop('disabled', false);
                        $('.mdtm_search').val('Search');
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


