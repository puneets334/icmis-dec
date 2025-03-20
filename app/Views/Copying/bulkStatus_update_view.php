<?=view('header') ?>
<style>
    .item {
        border: 1px solid #eee;
        box-shadow: 0 0 10px -3px #ccc;
        border-radius: 5px;
        margin-bottom: 30px;
        padding: 25px;
    }
</style>
<!-- caveat start -->
<div class="tab-pane" id="ReceivedBy">
    <?php  $attribute = array('class' => 'form-horizontal bulk_status_form','name' => 'bulk_status_form', 'id' => 'bulk_status_form', 'autocomplete' => 'off');
    echo form_open('#', $attribute);             ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header heading">

                    <div class="row">
                        <div class="col-sm-10">
                            <h3 class="card-title">Bulk Update Status </h3>
                        </div>
                    </div>
                </div>
                <?= view('Copying/copying_registration_breadcrum'); ?>
                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                    <h4 class="basic_heading">Bulk Update Status </h4>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group row">
                                <label for="From" class="col-sm-6 col-form-label">
                                    From</label>
                                <div class="col-sm-6">
                                    <input required type="date" class="form-control" id="from_date" name="from_date"  onblur="updatetoDate();" placeholder="From Date" value="<?php if(!empty($formdata['from_date'])){ echo $formdata['from_date']; } ?>">
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-4">

                            <div class="form-group row">
                                <label for="To" class="col-sm-5 col-form-label">To</label>
                                <div class="col-sm-7">
                                    <input type="date" class="form-control" id="to_date" name="to_date" placeholder="TO Date" value="<?php if(!empty($formdata['to_date'])){ echo $formdata['to_date']; } ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group row">
                                <label for="userName" class="col-sm-3 col-form-label">User Name</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="userName" style="width: 100%;" id="userName" data-placeholder="Select User">
                                        <option value="0">All Users</option>
                                        <?php
                                        foreach ($all_copying_users as $row) {
                                            ?>
                                            <option value="<?= $row['usercode'] ?>"><?= $row['empid'] . ' :: ' . $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">

                            <div class="form-group row">
                                <label for="To" class="col-sm-5 col-form-label"></label>
                                <div class="col-sm-7">
                                    <input type="submit" name="view" id="view"  class="view btn btn-primary"  value="Search">
                                </div>
                            </div>
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
<!-- /.caveat -->
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
    $('#bulk_status_form').on('submit', function () {
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        if(from_date.length != 0) {
            // alert('fromDate='+from_date+'fromDate='+to_date);
            var date1 = new Date(from_date.split('-')[0], from_date.split('-')[1] - 1, from_date.split('-')[2]);
            var date2 = new Date(to_date.split('-')[0], to_date.split('-')[1] - 1, to_date.split('-')[2]);

            if (date1 > date2) {
                // $('#result_load').hide();
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

        if ($('#bulk_status_form').valid()) {
            var validateFlag = true;
            var form_data = $(this).serialize();
            if(validateFlag){ //alert('readt post form');
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $("#loader").html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Copying/Copying/bulk_status_get_data'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $('.view').val('Please wait...');
                        $('.view').prop('disabled', true);
                        $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");

                    },
                    success: function (data) {
                        $("#loader").html('');
                        $('.view').prop('disabled', false);
                        $('.view').val('Search');
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

    function updatetoDate(){
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val(from_date);
    }

    function checkCheckbox() {
        if ($('input:checkbox').is(':checked') == false){
            alert("Select atleast one checkbox!");
            return false;
        }
        else
        {
            idSelected="";
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            var userName = $("#userName").val();

            var userLoggedIn=$('#userLoggedIn').val();
            $('input.chkbox:checkbox:checked').each(function (){
                idSelected+=$(this).val()+","});
            idSelected = idSelected.replace(/,\s*$/, "");
            $.ajax({
                url: '<?php echo base_url('Copying/Copying/bulkStatusUpdate'); ?>',
                cache: false,
                async: true,
                data: {
                    idSelected: idSelected,
                    from_date:from_date,
                    to_date:to_date,
                    userName:userName,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                type: 'POST',
                success: function(data) {
                    alert("Data Updated Successfully.")
                    $("#result_data").html(data);
                    updateCSRFToken();
                },
                error: function(xhr) {
                    updateCSRFToken();
                }
            });
        }
    }
function checkallCheckbox(){
    $("input:checkbox").each(function() {
        $(".chkbox").prop('checked', $(this).prop('checked'));
    });
}

</script>