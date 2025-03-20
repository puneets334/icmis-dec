<?= view('header') ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">

                <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">RECORD ROOM >> File Movement >> Bulk Receive Module FOR : <strong><?= $param[0]; ?></strong> of (Role Given to) Hall No. <strong><?= $param[4]; ?>(<?= $param[5]; ?>)</strong></h3>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div>
                    </div>


                    <div class="container-fluid">                       

                        <div class="row">
                            <div class="col-12">
                                <?= session()->getFlashdata('msg'); ?>
                            </div>
                        </div>


                        <form id="frmUploadRop" enctype="multipart/form-data" action="" method="post">
                            <input type="hidden" name="usercode" id="usercode" value="<?= session()->get('login')['usercode']; ?>">
                            <?= csrf_field(); ?>
                            <?php
                            $dateLabel1 = $param[1] == 110 ? "Order Date From" : "Dispatch Date From";
                            $dateLabel2 = $param[1] == 110 ? "Order Date To" : "Dispatch Date To";
                            ?>
                            <div class="row">
                                <div class="form-group col-sm-6 col-md-4">
                                    <label for="orderDateFrom"><?= $dateLabel1; ?></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input type="text" class="form-control datepick" name="orderDateFrom" id="orderDateFrom" placeholder="dd/mm/yyyy">
                                    </div>
                                </div>

                                <div class="form-group col-sm-6 col-md-4">
                                    <label for="orderDateTo"><?= $dateLabel2; ?></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input type="text" class="form-control datepick" name="orderDateTo" id="orderDateTo" placeholder="dd/mm/yyyy">
                                    </div>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>&nbsp;</label>
                                    <button type="button" id="btnGetCases" class="btn btn-info btn-block" onclick="getReceivedCasesList();" style="margin-top: 29px;">Get Cases</button>
                                </div>

                                <div id="divDisposedCasesList" class="col-12">
                                    <!-- Content for disposed cases will be injected here -->
                                </div>
                            </div>
                        </form>

                    </div>





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
    function getReceivedCasesList() {
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
                    url: "<?= base_url() ?>/Record_room/FileTrap/receiveCases",
                    beforeSend: function(xhr) {
                        $("#divDisposedCasesList").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?= base_url() ?>../images/load.gif'></div>");
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
            getReceivedCasesList();
            return false;
        }
        var fromDate = $('#orderDateFrom').val();
        var toDate = $('#orderDateTo').val();
        var usercode = $('#usercode').val();
        var allCheckedRceceivedCases = [];
        var consignmentRemarks = [];

        /*$("input:checkbox[name=receivedCases]:checked").each(function()
        {
            allCheckedRceceivedCases.push($(this).val());
        });*/

        /*$("textarea#consignmentRemarks").each (function () {
            consignmentRemarks.push($(this).val() );
        });*/
        $('#reportTable1 td input[type="checkbox"]:checked').each(function() {
            allCheckedRceceivedCases.push($(this).val());
            var this_textarea_value = $(this).closest('tr').find('textarea.consignmentRemarks').val();
            consignmentRemarks.push(this_textarea_value);
        });

        //alert(consignmentRemarks);

        if (countChecked > 0) {
            $.ajax({
                    type: 'POST',
                    url: "<?= base_url() ?>/Record_room/FileTrap/receiveAndDispatchCases",
                    beforeSend: function(xhr) {
                        $("#divDisposedCasesList").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?= base_url() ?>../images/load.gif'></div>");
                    },
                    data: {
                        dateFrom: fromDate,
                        dateTo: toDate,
                        usercode: usercode,
                        allReceivedCases: allCheckedRceceivedCases,
                        consignmentRemarks: consignmentRemarks
                    }
                    //allComplianceCases:allComplianceCases
                })
                .done(function(result) {
                    //$("#receiveAndDispatchStatus").html(result);
                    //alert(result);
                    //alert('Hello\nHow are you?');
                    var json = JSON.stringify(result);
                    json = $.parseJSON(json);
                    //console.log(json);
                    alert(json);

                    /*for (var i=0;i<json.length;i++)
                    {
                        //$('#results').append('<div class="name">'+json[i].name+'</>');
                        alert(json[0].name);
                    }*/
                    getReceivedCasesList();

                })
                .fail(function() {
                    alert("ERROR, Please Contact Server Room");
                });
        }
    }
</script>
<?php die; ?>