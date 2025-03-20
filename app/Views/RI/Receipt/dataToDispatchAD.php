<?php

if(!empty($dataForADToDispatch))
{
 ?>
    <div class="form-group col-sm-6 pull-right">
        <label>&nbsp;</label>
        <button type="button" id="btnDispatchTop" name="btnDispatch" class="btn btn-success btn-block pull-right"
                onclick="return doDispatchAD();"><i class="fa fa-fw fa-download"></i>&nbsp;Dispatch to Section </button>
    </div>
    <!--<table id="reportTable1" class="table table-striped table-hover">-->
    <table id="tblDispatchAD" style="width: 100%" class="table table-striped table-hover">
        <thead>
        <tr>
            <th width="4%">#</th>
            <th width="36%">Letter Detail</th>
            <th width="22%">Serve Status</th>
            <th width="10%">Send To Section</th>
            <th width="15%">Barcode</th>
            <th width="10%"><label><input type="checkbox" id="allCheck" name="allCheck" onclick="selectallMe()">Select All</label></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $s_no = 1;
        foreach ($dataForADToDispatch as $case) {
            ?>
            <tr>
                <td><?= $s_no ?></td>
                <td><b><?= $case['serial_number'] ?>.</b> &nbsp;
                    <?php if($case['is_with_process_id']==1){ ?>
                        Process Id: <?= $case['process_id'] ?>/<?= $case['process_id_year'] ?></br>
                    <?php }
                    else{ ?>
                        <?=(trim($case['reference_number']) !='') ? 'Reference No.: '.trim($case['reference_number']).'<br/>' : ''?>
                    <?php } ?>

                    <?php if($case['is_case']==1){ ?>
                        <?= $case['case_no'] ?><br/>
                    <?php } ?>
                    <?= isset($case['send_to_name']) ? trim($case['send_to_name']) : '' ?><br/>
<?= (isset($case['send_to_address']) && trim($case['send_to_address']) != '') ? '<b>Address: </b>' . trim($case['send_to_address']) : '' ?>
<?= (isset($case['district_name']) && trim($case['district_name']) != '') ? ', ' . trim($case['district_name']) : '' ?>
<?= (isset($case['state_name']) && trim($case['state_name']) != '') ? ', ' . trim($case['state_name']) : '' ?>

                    <?=($case['pincode'] !=0) ? ' ,'.$case['pincode'] : ''?>
                    <?=($case['doc_type'] !='') ? '<br/><b>Document Type: </b>'.$case['doc_type'] : ''?>
                </td>
                <td><?= $case['serve_stage'] ?><br/>
                    <?= $case['serve_type'] ?>
                    <?=($case['serve_remarks'] !='') ? '<br/> Remarks: '.$case['serve_remarks'] : ''?>
                </td>
                <td>
                    <?=$case['send_to_section']?>
                    <input type="hidden" id="send_to_section_<?=$case['ec_postal_dispatch_id']?>" name="send_to_section" value="<?=$case['usersection_id']?>">
                </td>
                <td>
                    <?=$case['waybill_number']?>
                </td>
                <td><input type="checkbox" id="daks" name="daks[]" value="<?= $case['ec_postal_dispatch_id'] ?>">
                </td>
            </tr>
            <?php
            $s_no++;
        }
        ?>
        </tbody>
    </table>
    <?php
}
else{
    echo "<div class='col-sm-12'><h4 class='text-danger'>Nothing to Dispatch!!</h4></div>";
}
?>
<script type="text/javascript">
    $('.number').keypress(function(event) {
        if(event.which == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46)
            return true;
        else if((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57))
            event.preventDefault();
    });
    function doDispatchAD() {
            //alert('fsdf');
        var selectedCases = [];
        var dispatchModes = [];
        alert('assfaf');
        $('#tblDispatchAD input:checked').each(function() {
            if ($(this).attr('name') != 'allCheck') {
                var dispatchId = $(this).attr('value');
                selectedCases.push(dispatchId);
                dispatchModes.push($('#dispatchMode_' + dispatchId).val());
            }
        });

        if (selectedCases.length <= 0) {
            alert("Please select at least one dak for dispatch.");
            return false;
        }
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var formData = {
            'selectedCases': selectedCases,
            'dispatchModes': dispatchModes,
            CSRF_TOKEN: CSRF_TOKEN_VALUE
        };
        //alert(formData);
        // $.ajax({
        //     type: "POST",
        //     url: "<?php echo base_url('RI/DispatchController/insertDataToDispatchWithProcessId'); ?>",
        //     data: formData,
        //     success: function(data) {
        //         updateCSRFToken();
        //         alert("Selected Letters Dispatched to R&I Successfully!");
        //         $('#dispatchDakToRI').submit();
        //     },
        //     error: function(xhr, status, error) {
        //         console.error("Dispatch failed: " + error);
        //     }
        // });
    }
</script>

