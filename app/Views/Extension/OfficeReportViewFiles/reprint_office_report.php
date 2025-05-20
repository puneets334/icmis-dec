<?= view('header') ?>
 
    <style>
    </style>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">Office Reports </h3>
                                </div>
                                <div class="col-sm-2">
                                    <div class="custom_action_menu">
                                        <a href="<?=base_url('Extension/OfficeReport');?>"><button class="btn btn-primary btn-sm" type="button"><i class="fas fa-pen	" aria-hidden="true"></i></button></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-4">

                                    <div class="card-body">
<!--                                        --><?//= view('Extension/OfficeReportViewFiles/office_report_menus'); ?>
<!--                                        --><?php
//                                        if($_SESSION['filing_details']['c_status']=='D')
//                                            echo '<br/><br/><span class="text-red">The searched case is disposed. Office report in disposed case is not allowed.</span>';
//                                        else{
                                            $attribute = array('class' => 'form-horizontal reprint','name' => 'reprint', 'id' => 'reprint', 'autocomplete' => 'off');
                                            echo form_open(base_url('#'), $attribute);
//                                            ?>


                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group row " >
                                                            <label for="from" class="col-sm-3 col-form-label">From Date: </label>
                                                            <div class="col-sm-7">
                                                                <input type="date" name="txtFromDate" class="form-control" id="txtFromDate" value="<?php echo date('d-m-Y'); ?>" maxlength="10" size="10" class="dtp"/>&nbsp;&nbsp;
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--</div>

                                                      <div class="row">-->
                                                    <div class="col-sm-4">
                                                        <div class="form-group row " >
                                                            <label for="from" class="col-sm-3 col-form-label">To Date: </label>
                                                            <div class="col-sm-7">

                                                                <input type="date" name="txtToDate" class="form-control" id="txtToDate" value="<?php echo date('d-m-Y'); ?>" size="10" maxlength="10" />&nbsp;&nbsp;
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <div class="form-group row">
                                                            <div class="col-sm-10">
                                                                <button type="submit" name="btnSubmit" id="btnSubmit" class="btn btn-primary" >Submit</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
<!--                                            --><?php //form_close();
//                                        }
//                                        ?>

                                        <div id="table_display"></div>

                                    </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
    <script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

    <script>

        $(function () {
            $(".datatable_report").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                    { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
            }).buttons().container().appendTo('.query_builder_wrapper .col-md-6:eq(0)');

        });

        var validationError = false;
        $('#reprint').on('submit', function () {
            var from_date = $("#txtFromDate").val();
            var to_date =$('#txtToDate').val();
            //var dno = '<?php //echo $_SESSION['filing_details']['diary_no']; ?>//';

            if (from_date=='') {
                alert("Please Enter From Date");
                $('#txtFromDate').focus();
                validationError = true;
                return false;
            }
            if (to_date=='') {
                alert("Please Enter To Date");
                $('#txtToDate').focus();
                validationError = true;
                return false;
            }
            if(validationError==false){
                //  var form_data = $(this).serialize();
                // alert(form_data);

                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Extension/OfficeReport/reprint'); ?>",
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        fdate: from_date,
                        tdate: to_date,
                        // dno:dno

                    },
                    beforeSend: function () {
                        $("#btnSubmit").prop("disabled", true);
                        $('#table_display').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                    },
                    success: function (data) {
                        updateCSRFToken();
                        $("#table_display").html(data);

                        $("#btnSubmit").attr("prop", false);
                    },
                    error: function (data) {
                        updateCSRFToken();
                        alert("Error occurred while processing your request. Please try again.");
                        $("#btnSubmit").attr("prop", false);
                        $('#table_display').html('');

                    }

                });
                return false;
            }
            else {
                return false;
            }
        });

    </script>