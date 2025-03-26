<?= view('header') ?>
<link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/datepicker/datepicker3.css">
<style type="text/css">
    .card-header {
    padding: .75rem 0;
}
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="card-title">Receive Files From DA</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2" style="width: 100% !important;">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <?php if (session()->getFlashdata('msg')): ?>
                                <?= session()->getFlashdata('msg') ?>
                            <?php endif; ?>
                            
                            <?php
                            $attribute = array(
                                'class' => 'form-horizontal appearance_search_form',
                                'id' => 'receiveFileFromDA',
                                'autocomplete' => 'off',
                                'enctype'=>'multipart/form-data',
                                'method' => 'post'
                            );
                            echo form_open(base_url('#'), $attribute);
                            ?>
                            <input type="hidden" name="usercode" id="usercode" value="<?php echo session()->get('login')['usercode']; ?>"/>
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label for="causelistDate">Causelist Date</label>
                                    <input type="text" id="causelistDate" name="causelistDate" class="form-control datepick" required placeholder="Causelist Date" value="">
                                </div>
                                <div class="col-md-3">
                                    <label for="from" class="text-right">&nbsp;</label>
                                    <button type="button" id="btnGetCases" class="btn btn-primary" style="width: 100%" onclick="check();">View</button>
                                </div>
                            </div>
                            <?= form_close()?>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div id="printable"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="<?= base_url() ?>/assets/js/sweetalert-2.1.2.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
    $(function() {
        $('.datepick').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        });
    });

    function check() {
        var causelistDate = $("#causelistDate").val();
        if (causelistDate === "")
        {
            alert("Select Causelist Date.");
            $("#causelistDate").focus();
            return false;
        }

        $(document).ready(function()
        {
            let CSRF_TOKEN = 'CSRF_TOKEN';
            let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            let causelistDate = $('#causelistDate').val();
            // Set up AJAX request
            $.ajax({
                type: 'POST',
                data: 
                { 
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    causelistDate:causelistDate
                },
                url: "<?= site_url('Exchange/causeListFileMovement/casesToReceiveFromDA') ?>",
                beforeSend: function ()
                {
                    $("#printable").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function(result)
                {
                    $("#printable").html('');
                    $("#printable").html(result);
                    $('#tblDispatchDak').DataTable({
                        "bSort": false,
                        "bPaginate": false,
                        "bLengthChange": false,
                        dom: 'Bfrtip',
                        buttons: [
                            'print'
                        ],
                    });
                    updateCSRFToken();
                },
                error: function(xhr, status, error)
                {
                    $("#printable").html('');
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                    updateCSRFToken();
                }
            });
        });

    }

    function doDispatch() {
        var selectedCases = [];
        $('#tblDispatchDak input:checked').each(function() {
            if ($(this).attr('name') !== 'allCheck') {
                selectedCases.push($(this).attr('value'));
            }
        });
        if (selectedCases.length <= 0) {
            alert("Please Select at least one dak for dispatch.");
            return false;
        }
        $.post("<?= site_url('RIController/doDispatchDak') ?>", {
            'selectedCases': selectedCases
        }, function(result) {
            $("#printable").html(result);
            $('#tblDispatchReport').DataTable({
                "bSort": false,
                "bPaginate": false,
                "bLengthChange": false,
                dom: 'Bfrtip',
                buttons: [
                    'print'
                ],
            });
        });
    }
</script>