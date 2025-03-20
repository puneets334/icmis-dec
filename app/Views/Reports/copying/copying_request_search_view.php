<div class="active tab-pane" id="CopyRequest">
    <?php
    $attribute = array('class' => 'form-horizontal copy_request_search_form','name' => 'copy_request_search_form', 'id' => 'copy_request_search_form', 'autocomplete' => 'off');
    echo form_open('#', $attribute); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="From" class="col-sm-5 col-form-label">From</label>
                                <div class="col-sm-7">
                                    <input  type="date" max="<?php echo date("Y-m-d"); ?>" class="form-control" id="from_date" name="from_date" placeholder="From Date" value="<?php if(!empty($formdata['from_date'])){ echo $formdata['from_date']; } ?>">
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-3">

                            <div class="form-group row">
                                <label for="To" class="col-sm-5 col-form-label">To</label>
                                <div class="col-sm-7">
                                    <input type="date" max="<?php echo date("Y-m-d"); ?>" class="form-control" id="to_date" name="to_date" placeholder="TO Date" value="<?php if(!empty($formdata['to_date'])){ echo $formdata['to_date']; } ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="Dairy No." class="col-sm-5 col-form-label">Case Status</label>
                                <div class="col-sm-7">
                                    <select class="form-control" id="case_status" name="case_status"  onchange="checkSection();">
                                        <option value="0">Select Case Status</option>
                                        <option value="D">Disposed</option>
                                        <option value="P">Pending</option>
                                    </select>     </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group row" id="section_div">
                                <label for="Year" class="col-sm-5 col-form-label">Section</label>
                                <div class="col-sm-7">
                                    <select class="form-control" name="section" id="section">
                                        <option value="0">Select section</option>
                                        <?php
                                        foreach($usersection as $section)
                                            echo "<option value='".$section['id']."'>".$section['section_name']."</option>";
                                        ?>
                                        <option value="76">Elimination</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group row">

                                <label for="delivery_mode" class="col-sm-5 col-form-label" >Delivery Mode</label>
                                <div class="col-sm-7">
                                <select class="form-control" id="deliver_mode" name="deliver_mode">
                                    <option value="0">Select Delivery Mode</option>
                                    <option value="1">By Post</option>
                                    <option value="2">By Hand</option>
                                </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="Document" class="col-sm-5 col-form-label">Document</label>
                                <div class="col-sm-7">
                                <select class="form-control" id="order_type" name="order_type">
                                        <option value="0">Select Document Type</option>
                                        <?php
                                        foreach($order_type as $doc)
                                            echo '<option value="'.$doc['id'].'">'.$doc['order_type'].'</option>';
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                        <div class="row">
                        <div class="col-sm-4">
                        </div>
                        <div class="col-sm-6">
                            <input type="submit" name="copy_request_search" id="copy_request_search"  class="copy_request_search btn btn-primary" value="Search">

                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

        </div>

    </div>
    <?= form_close();?>
</div>
<center><span id="loader"></span> </center>
<div id="result_data"></div>


<!-- /.card -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
</div>

<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

<script>
    $('#copy_request_search_form').on('submit', function () {
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

        if ($('#copy_request_search_form').valid()) {
            var validateFlag = true;
            var form_data = $(this).serialize();
            if(validateFlag){ //alert('readt post form');
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $("#loader").html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Reports/Copying/Report/copying_request_search'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $('.copy_request_search').val('Please wait...');
                        $('.copy_request_search').prop('disabled', true);
                        $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                    },
                    success: function (data) {
                        $("#loader").html('');
                        $('.copy_request_search').prop('disabled', false);
                        $('.copy_request_search').val('Search');
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

    function checkSection(){
        var case_status = $("#case_status").val();
        if(case_status == 'D'){
            $("#section").val('0');
            $("#section_div").css('display','none');
        }else{
            $("#section_div").css('display','');
        }
    }
</script>


