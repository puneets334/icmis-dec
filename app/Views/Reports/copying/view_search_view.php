<div class="active tab-pane" id="viewSearch">
    <?php
    $attribute = array('class' => 'form-horizontal view_search_form', 'name' => 'view_search_form', 'id' => 'view_search_form', 'autocomplete' => 'off');
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
                                    <input required type="date" max="<?php echo date("Y-m-d"); ?>" class="form-control" id="from_date" name="from_date" placeholder="From Date" value="<?php if (!empty($formdata['from_date'])) {
                                                                                                                                                                                            echo $formdata['from_date'];
                                                                                                                                                                                        } ?>">
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-3">

                            <div class="form-group row">
                                <label for="To" class="col-sm-5 col-form-label">To</label>
                                <div class="col-sm-7">
                                    <input type="date" max="<?php echo date("Y-m-d"); ?>" class="form-control" id="to_date" name="to_date" placeholder="TO Date" value="<?php if (!empty($formdata['to_date'])) {
                                                                                                                                                                            echo $formdata['to_date'];
                                                                                                                                                                        } ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="category" class="col-sm-5 col-form-label">Copy Category</label>
                                <div class="col-sm-7">
                                    <select class="form-control" id="category" name="category" oplaceholder="Copy Category">
                                        <option value="0">All</option>
                                        <?php
                                        foreach ($copy_category as $category1)
                                            echo '<option value="' . $category1['id'] . '">' . $category1['code'] . ' :: ' . $category1['description'] . '</option>';
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="Application Type" class="col-sm-5 col-form-label">Application Status</label>
                                <div class="col-sm-7">
                                    <select class="form-control" id="application_status" name="application_status">
                                        <option value="0">All</option>
                                        <?php
                                        foreach ($copy_status as $status1)
                                            echo '<option value="' . $status1['status_code'] . '">' . $status1['status_description'] . '</option>';
                                        ?>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Document</label>
                                <div class="col-sm-7"><select class="form-control" id="document" name="document">
                                        <option value="0">All</option>
                                        <?php
                                        foreach ($order_type as $doc)
                                            echo '<option value="' . $doc['id'] . '">' . $doc['order_type'] . '</option>';
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Source</label>
                                <div class="col-sm-7"><select class="form-control" id="case_source" name="case_source">
                                        <option value="0">All</option>
                                        <?php
                                        foreach ($case_source as $case_sources)
                                            echo '<option value="' . $case_sources['id'] . '">' . $case_sources['description'] . '</option>';
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label>Date option </label>
                        </div>
                        <div class="col-sm-10">
                            <div class="form-group row">

                                <input type="radio" name="radiodate" id="radiodate" value="1" checked><b>Application Receiving</b>&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="radiodate" id="radiodate" value="2"><b>Requisition sent to Judicial Section</b>&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="radiodate" id="radiodate" value="3"><b>File received in Copying Section</b>&nbsp;&nbsp;&nbsp;&nbsp;
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                        </div>
                        <div class="col-sm-6">
                            <input type="submit" id="view_search" name="view_search" onclick="return check(); " class="view_search btn btn-primary" value="Search">

                        </div>

                    </div>

                    <?= form_close(); ?>
                </div>
                <!-- /.diary -->
                <div id="result_data"></div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
    <script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>
    <script>
        $(function() {
            $('.datepick').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            });
        });

        function check() {
            var fromDate = document.getElementById('from_date').value;
            var toDate = document.getElementById('to_date').value;
            date1 = new Date(fromDate.split('-')[2], fromDate.split('-')[1] - 1, fromDate.split('-')[0]);
            date2 = new Date(toDate.split('-')[2], toDate.split('-')[1] - 1, toDate.split('-')[0]);
            if (date1 > date2) {
                alert("To Date must be greater than From date");

                return false;
            }
            var date = $('input[name="radiodate"]:checked').val();
            var status = document.getElementById('application_status').value;
            if (date == 2 && status != 'A') {
                alert("Status should be Requisition Sent to Section");
                document.getElementById('application_status').focus();
                return false;
            } else if (date == 3 && status != 'B') {
                alert("Status should be File Received in Section");
                document.getElementById('application_status').focus();
                return false;
            }
            return true;
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#view_search_form').on('submit', function() {
                var fromDate = document.getElementById('from_date').value;
                var toDate = document.getElementById('to_date').value;
                date1 = new Date(fromDate.split('-')[2], fromDate.split('-')[1] - 1, fromDate.split('-')[0]);
                date2 = new Date(toDate.split('-')[2], toDate.split('-')[1] - 1, toDate.split('-')[0]);
                if (date1 > date2) {
                    alert("To Date must be greater than From date");
                    return false;
                }
                var date = $('input[name="radiodate"]:checked').val();
                var status = document.getElementById('application_status').value;
                if (date == 2 && status != 'A') {
                    alert("Status should be Requisition Sent to Section");
                    document.getElementById('application_status').focus();
                    return false;
                } else if (date == 3 && status != 'B') {
                    alert("Status should be File Received in Section");
                    document.getElementById('application_status').focus();
                    return false;
                }

                if ($('#view_search_form').valid()) {
                    var validateFlag = true;
                    var form_data = $(this).serialize();
                    if (validateFlag) {
                        var CSRF_TOKEN = 'CSRF_TOKEN';
                        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                        $('.alert-error').hide();
                        $("#loader").html('');
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url('Reports/Copying/Report/view_search'); ?>",
                            data: form_data,
                            beforeSend: function() {
                                $('.view_search').val('Please wait...');
                                $('.view_search').prop('disabled', true);
                                $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                            },
                            success: function(data) {
                                $("#loader").html('');
                                $('.view_search').prop('disabled', false);
                                $('.view_search').val('Search');
                                $("#result_data").html(data);
                                updateCSRFToken();
                            },
                            error: function() {
                                updateCSRFToken();
                            }

                        });
                        return false;
                    }
                } else {
                    return false;
                }
            });
        });
    </script>