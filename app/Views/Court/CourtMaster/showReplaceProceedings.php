<div class="row">
    <div class="col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-form-label" style="font-weight:bold;"><span class="text-primary">Coram : </span> <?=$judgesInCoram;?>)</label>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" style="font-weight:bold;"><span class="text-primary">Presiding Judge/Persented By </span></label>
            <div class="col-sm-8">
                <select class="form-control" id="presiding_judge" name="presiding_judge">
                    <option value="0">Select Judge</option>
                    <?php
                    foreach($judge as $j1):
                        if ($firstJudge==$j1['jcode']){
                            echo '<option value="'.$j1['jcode'].'" selected="selected">'.$j1['jcode'].' - '.$j1['jname'].'</option>';
                        }
                        else
                            echo '<option value="'.$j1['jcode'].'" >'.$j1['jcode'].' - '.$j1['jname'].'</option>';
                    endforeach;
                    ?>
                </select>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" style="font-weight:bold;"><span class="text-primary">Order Date : </span></label>
            <label class="col-sm-3 col-form-label" style="font-weight:bold;"><input type="radio" name="orderType" value="Order" checked>&nbsp;Record of Proceedings</label>
            <label class="col-sm-4 col-form-label" style="font-weight:bold;"><input type="radio" name="orderType" value="Judgement">&nbsp;&nbsp;Judgment / Reportable Order / Signed Order with Reason</label>
            <label class="col-sm-3 col-form-label" style="font-weight:bold;"><input type="radio" name="orderType" value="FinalOrder">&nbsp;Final Order</label>
            <br>
            <label class="col-sm-2 col-form-label"></label>
            <label class="col-sm-3 col-form-label" style="font-weight:bold;"><input type="checkbox" name="is_reportable" value="1"><span class="text-danger">&nbsp;Reportable</span></label>
            <label class="col-sm-4 col-form-label" style="font-weight:bold;"><input type="checkbox" name="chkInFirstPage" value="1"><span class="text-danger">&nbsp;Show on First Page of Website</span></label>
            <label class="col-sm-3 col-form-label"></label>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" style="font-weight:bold;"><span class="text-primary">Select Digitally Signed Files </span></label>
            <div class="col-sm-3">
                <input type="file" name="fileROPList" id="fileROPList" accept="application/pdf">
            </div>
            <label class="col-sm-3 col-form-label" style="font-weight:bold;"><span class="text-primary">Language </span></label>
            <div class="col-sm-3">
                <select  class="form-control" name="language" tabindex="1" id="language" required>
                    <?php
                        foreach($languages as $language){
                            echo '<option value="' . $language['id'] .'#'.$language['short_name']. '">'. $language['display_name'] .'&nbsp;(' .$language['name']. ')</option>';
                        }
                    ?>
                </select>
            </div>
        </div>
    </div>
</div>

<p><center><font style="color: red;">Note:- In case of Judgment, Please ensure that the Pdf file has QR code and Neutral Citation number on the top left corner</font></center></p>

<div class="col-md-12">
    <button type="button" id="btnUploadROP" onclick="return validateData();" class="btn btn-success col-sm-12"><i class="fa fa-fw fa-upload"></i>&nbsp;Upload
    </button>
</div>

<script>
    function validateData() {
    if ($('#fileROPList').val() == "" && 1 == 2) {
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        
        // Prepare data to send
        var dataToSend = $("#frmCaseWiseQR").serialize();
        console.log(dataToSend);

        $.post("<?= base_url('Court/CourtMasterController/embedQRCaseWise');?>", dataToSend)
            .done(function(result) {
                $("#divQREmbedResult").html(result);
                updateCSRFToken();
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                updateCSRFToken();
                console.error("Error occurred: " + textStatus, errorThrown);
                alert("An error occurred while processing your request. Please try again.");
            });
    }
    $("#frmCaseWiseQR").submit();
}
</script>