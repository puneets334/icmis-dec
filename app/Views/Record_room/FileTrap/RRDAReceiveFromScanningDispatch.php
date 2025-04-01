<?= view('header') ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">RECORD ROOM >> File Movement >> Bulk File Revert(Receive) Module FOR : <strong><?= $param[0]; ?></strong> From SCANNING</h3>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div>
                    </div>



                    <?= session()->getFlashdata('msg'); ?>
                    <form id="frmUploadRop" enctype="multipart/form-data" action="" method="post">
                        <input type="hidden" name="usercode" id="usercode" value="<?= session()->get('login')['usercode']; ?>">
                        <?= csrf_field(); ?>
                        <div class="form-row mt-3">
                            <div class="form-group col-md-4 ml-3">
                                <?php
                                $dateLabel1 = '';
                                $dateLabel2 = '';
                                if ($param[1] == 110) {
                                    $dateLabel1 = "Dispatch Date From";
                                    $dateLabel2 = "Dispatch Date To";
                                }
                                ?>
                                <label for="orderDateFrom"><?= $dateLabel1; ?></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control datepick" name="orderDateFrom" id="orderDateFrom" placeholder="dd/mm/yyyy">
                                </div>
                            </div>

                            <div class="form-group col-md-4  ml-3">
                                <label for="orderDateTo"><?= $dateLabel2; ?></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control datepick" name="orderDateTo" id="orderDateTo" placeholder="dd/mm/yyyy">
                                </div>
                            </div>

                            <div class="form-group col-md-3 mt-3">
                                <label>&nbsp;</label>
                                <button type="button" id="btnGetCases" class="btn btn-info btn-block" onclick="getReceivedCasesFromScanningList();" style="margin-top: 10px;">Get Cases</button>
                            </div>
                        </div>

                        <div id="divDisposedCasesList" class="col-12">
                            <!-- Content for disposed cases list will be dynamically added here -->
                        </div>
                    </form>




                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(".alert").delay(4000).slideUp(200, function() {
        $(this).alert('close');
    });
    $(function() {

        $("#orderDateFrom").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
        $("#orderDateFrom").datepicker("setDate", '01-01-2018');
        $("#orderDateTo").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
        $("#orderDateTo").datepicker("setDate", new Date());
    });
</script>

<script type="text/javascript">
    function getReceivedCasesFromScanningList() {
        //alert("1");
        var fromDate = $('#orderDateFrom').val();
        var toDate = $('#orderDateTo').val();

        var usercode = $('#usercode').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        if (fromDate == "") {
            alert("Please Select Order Date From");
            $('#orderDateFrom').focus();
            return false;
        }
        if (toDate == "") {
            alert("Please Select Order Date To");
            $('#orderDateTo').focus();
            return false;
        }
        if (fromDate != "" && toDate != "") {

            $.ajax({
                    type: 'POST',
                    url: "<?= base_url() ?>/Record_room/FileTrap/receiveCasesFromScanning",
                    beforeSend: function(xhr) {
                        $("#divDisposedCasesList").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?= base_url() ?>/images/load.gif'></div>");
                    },
                    data: {
                        orderDateFrom: fromDate,
                        orderDateTo: toDate,
                        usercode: usercode,
                        CSRF_TOKEN: CSRF_TOKEN_VALUE
                    }
                })
                .done(function(result) {
                    updateCSRFToken();
                    $("#divDisposedCasesList").html(result);
                    $('#reportTable1').DataTable({
                        "bSort": false,
                        "bPaginate": true,
                        "bLengthChange": false,
                        "bInfo": true
                    });



                })
                .fail(function() {
                    updateCSRFToken();
                    alert("ERROR, Please Contact Server Room");
                });
        }
    }

    function receiveAndAutoDispatch() {
        var countChecked = $("input:checkbox[name=receivedCases]:checked").length; // count the Received checked rows
        if (countChecked == 0) {
            alert("Please select any Case to Receive and Dispatch.");
            getReceivedCasesFromScanningList();
            return false;
        }
        var fromDate = $('#orderDateFrom').val();
        var toDate = $('#orderDateTo').val();
        var usercode = $('#usercode').val();
        var allCheckedRceceivedCases = [];
        //var allComplianceCases = [];
        var consignmentRemarks = [];

        /* $("input:checkbox[name=receivedCases]:checked").each(function(){
             allCheckedRceceivedCases.push($(this).val());
         });
         $("input:checkbox[name=IsUrgencyInCompliance]:checked").each(function(i){
             allComplianceCases.push( i.value );
         });*/
        $('#reportTable1 td input[type="checkbox"]:checked').each(function() {
            allCheckedRceceivedCases.push($(this).val());
            var this_textarea_value = $(this).closest('tr').find('textarea.consignmentRemarks').val();
            consignmentRemarks.push(this_textarea_value);
        });
        // alert(countChecked);exit;

        if (countChecked > 0) {
            $.ajax({
                    type: 'POST',
                    url: "<?= base_url() ?>index.php/FileTrap/receiveAndDispatchCasesToRC",
                    beforeSend: function(xhr) {
                        $("#divDisposedCasesList").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?= base_url() ?>/images/load.gif'></div>");
                    },
                    data: {
                        dateFrom: fromDate,
                        dateTo: toDate,
                        usercode: usercode,
                        allReceivedCases: allCheckedRceceivedCases,
                        consignmentRemarks: consignmentRemarks
                    }
                })
                .done(function(result) {

                    var json = JSON.stringify(result);
                    json = $.parseJSON(json);
                    //console.log(json);
                    alert(json);
                    getReceivedCasesFromScanningList();

                })
                .fail(function() {
                    alert("ERROR, Please Contact Server Room");
                });
        }
    }
</script>
<?php die; ?>