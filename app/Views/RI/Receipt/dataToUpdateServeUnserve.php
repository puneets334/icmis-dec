<?php
if(!empty($dataToUpdateServeStatus)){
?>

<div class="form-group col-sm-6 pull-right">
    <label>&nbsp;</label>
    <button type="button" id="btnReceiveTop" name="btnReceive" class="btn btn-primary pull-right" onclick="return doUpdateServeUnserve();">
        <i class="fa fa-fw fa-download"></i>&nbsp;Update Serve Status
    </button>
</div>
<table id="tblUpdateServeUnserve" style="width: 100%" class="table table-striped table-hover">
    <thead>
    <tr>
        <th width="3%">#</th>
        <th width="30%">Letter Detail</th>
        <th width="15%">Serve Stage</th>
        <th width="15%">Serve Type</th>
        <th width="15%">Remarks</th>
        <th width="10%"><label><input type="checkbox" id="allCheck" name="allCheck" onclick="selectallMe()">Select All</label></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $s_no = 1;
    //var_dump($dataToUpdateServeStatus);
    foreach ($dataToUpdateServeStatus as $case) {
        ?>
        <tr>
            <td><?= $s_no ?></td>
            <td>
                <?php if ($case['is_with_process_id'] == 1) { ?>
                    <b>Process Id: <?= $case['process_id'] ?>/<?= $case['process_id_year'] ?></b>
                <?php } else { ?>
                    <b>Reference No.: <?= $case['reference_number'] ?></b>
                <?php } ?>
                <br/>
                <?php if ($case['is_case'] == 1) { ?>
                    <?= $case['case_no'] ?><br/>
                <?php } ?>
                <?= isset($case['send_to_name']) ? trim($case['send_to_name']) : '' ?><br/>

                <?= (isset($case['send_to_address']) && trim($case['send_to_address']) != '') 
                    ? '<b>Address: </b>' . trim($case['send_to_address']) : '' ?>

                <?= (isset($case['district_name']) && trim($case['district_name']) != '') 
                    ? ' ,' . trim($case['district_name']) : '' ?>

                <?= (isset($case['state_name']) && trim($case['state_name']) != '') 
                    ? ' ,' . trim($case['state_name']) : '' ?>
                <?= ($case['pincode'] != 0) ? ' ,' . $case['pincode'] : '' ?> <br/>
                <b>Document Type: </b> <?= $case['doc_type'] ?>

            </td>

            <td><?= csrf_field(); ?>
                <select class="form-control" id="serveStage_<?= $case['ec_postal_dispatch_id'] ?>" onchange="getServeType(<?= $case['ec_postal_dispatch_id'] ?>)">
                    <option value="0">Select Serve Stage</option>
                    <?php
                    if(!empty($serveStage)) {
                        foreach ($serveStage as $stage) {
                            echo '<option value="' . $stage['serve_stage'] . '">' . $stage['name'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </td>
            <td id="tdServeType_<?= $case['ec_postal_dispatch_id'] ?>">

            </td>
            <td>
                <input type="text" id="remarks_<?= $case['ec_postal_dispatch_id'] ?>" name="remarks"
                       class="form-control" placeholder="Remarks" value="">
                       
            </td>

            <td><input type="checkbox" id="daks" name="daks[]" value="<?= $case['ec_postal_dispatch_id'] ?>">
            </td>
        </tr>
        <?php
        $s_no++;
    }
    ?>
    </tbody>
    <?php
    }
    else{
        echo "<br><div class='col-sm-12'><h4 class='text-danger'>No Data Found!</h4></div>";
    }
    ?>
    <script type="text/javascript">
        $('.number').keypress(function(event) {
            if(event.which == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46)
                return true;
            else if((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57))
                event.preventDefault();
        });
        function updateCSRFToken() {
        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        });
      }
        function getServeType(id){
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            stage=$("#serveStage_"+id).val();

            //$.get("<?//=base_url('RI/ReceiptController/getServeType')?>//", {'stage':stage,'id':id}, function (result) {
            //
            //    $("#tdServeType_"+id).html(result);
            //});

            $.ajax({
                type: 'POST',
                url: '<?= base_url('RI/ReceiptController/getServeType') ?>',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN_VALUE
                },
                data: {
                    'stage': stage,
                    'id': id
                },
                success: function(result) {
                    $("#tdServeType_" + id).html(result);
                    updateCSRFToken();
                },
                error: function() {
                    alert("Error, Something Went Wrong!!qq");
                    updateCSRFToken();
                }
            });
        }


        function doUpdateServeUnserve() {
        var selectedCases = [];
        var serveStage= [];
        var serveType= [];
        var remarks= [];
        var toExit=0;
        $('#tblUpdateServeUnserve input:checked').each(function () {
            if ($(this).attr('name') != 'allCheck'){
                var dispatchId=$(this).attr('value');
                selectedCases.push(dispatchId);
                if($('#serveStage_'+dispatchId).val()==0){
                    alert("Select Serve Stage.");
                    $("#serveStage_"+dispatchId).focus();
                    toExit=1;
                    return false;
                }
                else if($('#serveType_'+dispatchId).length && $('#serveType_'+dispatchId).val()==0){
                    alert("Select Serve Type.");
                    $("#serveType_"+dispatchId).focus();
                    toExit=1;
                    return false;
                }
                serveStage.push($('#serveStage_'+dispatchId).val());
                serveType.push($('#serveType_'+dispatchId).val());
                remarks.push($('#remarks_'+dispatchId).val().trim());
            }
        });
        if(toExit==1){
            return false;
        }
        if (selectedCases.length <= 0) {
            alert("Please Select at least one dak for dispatch..");
            return false;
        }
        // $.post("<?=base_url()?>index.php/RIController/doUpdateServeUnServe", 
        // {'selectedCases': selectedCases,'serveStage': serveStage,'serveType': serveType,'remarks': remarks}, function (result) {
        //     if(result>0){
        //         alert(result+ " Selected Letters serve status updated Sccessfully!");
        //         check();
        //     }
        // });
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
                type: 'POST',
                url: '<?= base_url('RI/ReceiptController/doUpdateServeUnServe') ?>',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN_VALUE
                },
                data: {
                    'selectedCases': selectedCases,'serveStage': serveStage,'serveType': serveType,
                    'remarks': remarks
                   
                },
                success: function(result) {
                    alert(result+ " Selected Letters serve status updated Sccessfully!");
                   // $("#tdServeType_" + id).html(result);
                    updateCSRFToken();
                },
                error: function() {
                    alert("Error, Something Went Wrong!!qq");
                    updateCSRFToken();
                }
            });
    }

</script>

    </script>
