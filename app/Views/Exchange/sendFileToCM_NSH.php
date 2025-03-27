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
                                <h3 class="card-title">Send Causelist cases to Court Master</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <?php if (session()->getFlashdata('msg')): ?>
                                <?= session()->getFlashdata('msg') ?>
                            <?php endif; ?>
                            
                            <?php
                            $attribute = array(
                                'class' => 'form-horizontal appearance_search_form',
                                'id' => 'dispatchFile',
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
                                    <input type="text" id="causelistDate" name="causelistDate" class="form-control datepick" placeholder="Causelist Date" value="" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="from">Court No.</label>
                                    <select  class="form-control" name="courtNo" id="courtNo">
                                        <option value="0">All</option>
                                        <?php
                                        for($courtNo=1;$courtNo<=22;$courtNo++){
                                            if($courtNo==21)
                                                echo "<option value='".$courtNo."'>Registrar Court 1</option>";
                                            else if($courtNo==22)
                                                echo "<option value='".$courtNo."'>Registrar Court 2</option>";
                                            else if($courtNo<=17)
                                                echo "<option value='".$courtNo."'>Court No. ".$courtNo."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="from" class="text-right">&nbsp;</label>
                                    <button type="button" id="btnGetCases" class="btn btn-primary" style="width: 100%" onclick="check();">View</button>
                                </div>
                            </div>
                            <?= form_close()?>
                        </div>
                    </div>
                    <center><span id="loader"></span></center>
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
    $(function()
    {
        $('.datepick').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        });
    });

    function check()
    {
        var causelistDate = $("#causelistDate").val();
        if(causelistDate == "")
        {
            swal({
                title: "Warning",
                text: 'Select Causelist Date.',
                icon: "warning"
            }).then(() => {
                return false;
            });
            $("#causelistDate").focus();
            return false;
        }

        $(document).ready(function()
        {
            let CSRF_TOKEN = 'CSRF_TOKEN';
            let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            let causelistDate = $('#causelistDate').val();
            let courtNo = $('#courtNo').val();
            $.ajax({
                type: 'POST',
                data: { CSRF_TOKEN: CSRF_TOKEN_VALUE, causelistDate:causelistDate, courtNo:courtNo },
                url: "<?= site_url('Exchange/causeListFileMovement/listedCases') ?>",
                beforeSend: function () {
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function(result) {
                    $("#loader").html('');
                    $("#printable").html(result);
                    $('#tblDispatchDak').DataTable({
                        "bSort": false,
                        "bPaginate": false,
                        "bLengthChange": false,
                        dom: 'Bfrtip',
                        buttons: [
                            'print'
                        ],
                    } );
                    updateCSRFToken();
                },
                error: function(xhr, status, error)
                {
                    $("#loader").html('');
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                    updateCSRFToken();
                }
            });
        });
    }

    function doDispatch()
    {
        var selectedCases = [];
        $('#tblDispatchDak input:checked').each(function()
        {
            if($(this).attr('name')!='allCheck')
            {
                selectedCases.push($(this).attr('value'));
            }
        });
        if(selectedCases.length<=0)
        {
            alert("Please Select at least one dak for dispatch..");
            return false;
        }
        $.post("<?=base_url()?>index.php/RIController/doDispatchDak", {'selectedCases':selectedCases},function(result)
        {
            $("#printable").html(result);
            $('#tblDispatchReport').DataTable({
                "bSort": false,
                "bPaginate": false,
                "bLengthChange": false,
                dom: 'Bfrtip',
                buttons: [
                    'print'
                ],
            } );
        });
    }
</script>