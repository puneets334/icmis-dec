<?= view('header') ?>
 
    <style>
        .custom-radio {
            float: left;
            display: inline-block;
            margin-left: 10px;
        }

        .custom_action_menu {
            float: left;
            display: inline-block;
            margin-left: 10px;
        }

        .basic_heading {
            text-align: center;
            color: #31B0D5
        }

        .btn-sm {
            padding: 0px 8px;
            font-size: 14px;
        }

        .card-header {
            padding: 5px;
        }

        h4 {
            line-height: 0px;
        }

        .row {
            margin-right: 15px;
            margin-left: 15px;
        }
        a {color:darkslategrey}      /* Unvisited link  */

        a:hover {color:black}    /* Mouse over link */
        a:active {color:#0000FF;}  /* Selected link   */

        .box.box-success {
            border-top-color: #00a65a;
        }
        .box {
            position: relative;
            border-radius: 3px;
            background: #ffffff;
            border-top: 3px solid #d2d6de;
            margin-bottom: 20px;
            width: 100%;
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        }

        .box-header {
            color: #444;
            display: block;
            padding: 10px;
            position: relative;
        }
        .box-header.with-border {
            border-bottom: 1px solid #f4f4f4;
        }
        .box.box-danger {
            border-top-color: #dd4b39;
        }
       .usr-select-bx {
    width: fit-content;
    height: auto;
    position: relative;
}
.usr-select-bx button.multiselect.dropdown-toggle.btn.btn-default {
      width: 100% !important;
    min-height: 214px;
    display: flex;
    padding: 8px !important;
}
.usr-select-bx button.multiselect.dropdown-toggle.btn.btn-default span.multiselect-selected-text {
    text-wrap: wrap;
}
.usr-select-bx .btn .caret {
    display: none !important;
}
    </style>
    <!-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->

<!-- jQuery UI JS -->
<!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

 
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script> -->
    
<body>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <h3 class="card-title">R & I >> Print Address Slip</h3>
                    </div>    
                    <br><br>
                    <div class="container-fluid">
                        <form id="printAddressSlip" method="post">
                        <?= csrf_field(); ?>
                            
                            <div class="row text-center">
                                <!-- Users Selection -->
                                <div class="form-group col-md-3">
                                    <label for="from[]">Users</label>
                                    <div class="col-md-12 usr-select-bx">
                                    <select name="from[]" id="undo_redo" class="form-control" size="13" multiple="multiple">
                                        <?php foreach ($userList as $user): ?>
                                            <option value="<?= $user['usercode'] ?>"><?= $user['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                </div>

                                <!-- Buttons for Moving Selection -->
                                <div class="col-md-2">
                                    <br><br>
                                    <button type="button" id="undo_redo_rightAll" class="btn btn-primary btn-block" style="width: 100%;"><i class="fa fa-fast-forward"></i></button>
                                    <button type="button" id="undo_redo_rightSelected" class="btn btn-primary btn-block" style="width: 100%;"><i class="fa fa-chevron-right"></i></button>
                                    <button type="button" id="undo_redo_leftSelected" class="btn btn-primary btn-block"  style="width: 100%;"><i class="fa fa-chevron-left"></i></button>
                                    <button type="button" id="undo_redo_leftAll" class="btn btn-primary btn-block"  style="width: 100%;"><i class="fa fa-fast-backward"></i></button>
                                </div>

                                <!-- Selected Users -->
                                <div class="form-group col-md-3">
                                    <label for="to[]">Selected Users</label>
                                    <select name="to[]" id="undo_redo_to" class="form-control" size="13" multiple="multiple"></select>
                                </div>
                            </div>

                            <hr>

                            <!-- Other Input Fields -->
                            <div class="row">
                                <div class="form-group col-sm-2">
                                    <label for="receivedDate">Received Date</label>
                                    <input type="text" id="receivedDate" name="receivedDate" class="form-control datepick" placeholder="From Date" required>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="dispatchMode">Dispatch Mode</label>
                                    <select class="form-control" id="dispatchMode" name="dispatchMode">
                                        <option value="0">Select Mode</option>
                                        <?php foreach ($dispatchModes as $mode): ?>
                                            <option value="<?= $mode['id'] ?>"><?= $mode['postal_type_description'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="reportType">Report Type</label>
                                    <select class="form-control" id="reportType" name="reportType">
                                        <option value="1">Address Slip</option>
                                        <option value="2">Final Compiled Report</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-2">
                                    <label>&nbsp;</label>
                                    <button type="button" id="btnGetCases" class="btn btn-info form-control mt-4" onclick="return check();">View</button>
                                </div>
                            </div>
                        </form>

                        <div id="divResult"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
     function updateCSRFToken() {
        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        });
      }
    $(function () {
        $('.datepick').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });

        // Initialize Bootstrap Multiselect
        // $('#undo_redo').multiselect({
        //     keepRenderingSort: false
        // });

        // Event handlers for moving selected options
        $('#undo_redo_rightSelected').on('click', function () {
            $('#undo_redo_to').append($('#undo_redo option:selected'));
        });

        $('#undo_redo_leftSelected').on('click', function () {
            $('#undo_redo').append($('#undo_redo_to option:selected'));
        });

        $('#undo_redo_rightAll').on('click', function () {
            $('#undo_redo_to').append($('#undo_redo option'));
        });

        $('#undo_redo_leftAll').on('click', function () {
            $('#undo_redo').append($('#undo_redo_to option'));
        });
    });

    function check() {
        var receivedDate = $("#receivedDate").val();
        if (!receivedDate) {
            alert("Select Received Date.");
            return false;
        }

        var dispatchMode = $("#dispatchMode").val();
        if (dispatchMode === "0") {
            alert("Select Dispatch Mode.");
            return false;
        }

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('RI/DispatchController/getAddressReport')?>',
            beforeSend: function(xhr) {
                $("#divResult").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            data: $("#printAddressSlip").serialize()
        })
        .done(function(response) {
            updateCSRFToken();
            $("#divResult").html(response);
        })
        .fail(function() {
            updateCSRFToken();
            alert("ERROR, Please Contact Server Room");
        });

 
    }

    function printDiv(printable)
{      
    let printElement = document.getElementById(printable);
    var printWindow = window.open('', 'PRINT');
    printWindow.document.write(document.documentElement.innerHTML);
    setTimeout(() => { // Needed for large documents
      printWindow.document.title = "SC : CMIS";
      printWindow.document.body.style.margin = '0 0';
      printWindow.document.body.innerHTML = printElement.outerHTML;
      printWindow.document.close(); // necessary for IE >= 10
      printWindow.focus(); // necessary for IE >= 10*/
      printWindow.print();
      printWindow.close();
    }, 1000)
}
</script>
 