<?php if(isset($dataToReciveInRI) && sizeof($dataToReciveInRI) > 0) { ?>
    <div class="form-group col-sm-6 pull-right">
        <label>&nbsp;</label>
        <button type="button" id="btnReceiveTop" name="btnReceive" class="btn btn-success btn-block pull-right"
                onclick="return doReceive();"><i class="fa fa-fw fa-download"></i>&nbsp;Receive
        </button>
    </div>
    <!--<table id="reportTable1" class="table table-striped table-hover">-->
    <table id="tblReceiveDak" style="width: 100%" class="table table-striped table-hover">
        <thead>
        <tr>
            <th width="4%">#</th>
            <th width="36%">Letter Detail</th>
            <th width="12%">Letter Type</th>
            <th width="20%">Dispatch Mode</th>
            <th width="8%">Weight<br/>(in gr.)</th>
            <th width="8%">Amount (Rs.)</th>
            <th width="15%">Barcode</th>
            <th width="10%"><label><input type="checkbox" id="allCheck" name="allCheck" onclick="selectallMe()">Select
             All</label></th>
        </tr>
        </a>
        </thead>
        <tbody>
        <?php
        $s_no = 1;
        foreach ($dataToReciveInRI as $case) {
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
                    <?= ($case['send_to_name']) ?><br/>
                    <?= (($case['send_to_address']) != '') ? '<b>Address: </b>' . ($case['send_to_address']) : '' ?>
                    <?= (($case['district_name']) != '') ? ' ,' . ($case['district_name']) : '' ?>
                    <?= (($case['state_name']) != '') ? ' ,' . ($case['state_name']) : '' ?>
                    <?= ($case['pincode'] != 0) ? ' ,' . $case['pincode'] : '' ?>

                </td>
                <td><?= $case['doc_type'] ?></td>
                <td>
                    <select class="form-control" id="dispatchMode_<?= $case['ec_postal_dispatch_id'] ?>">
                        <option value="0">Select Mode</option>
                        <?php
                        foreach ($dispatchModes as $mode) {
                            if ($case['ref_postal_type_id'] == $mode['id'])
                                echo '<option value="' . $mode['id'] . '" selected="selected">' . $mode['postal_type_description'] . '</option>';
                            else
                                echo '<option value="' . $mode['id'] . '">' . $mode['postal_type_description'] . '</option>';
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <input type="number" id="weight_<?= $case['ec_postal_dispatch_id'] ?>" name="weight"
                           class="number form-control" placeholder="Weight ig g." value="">
                </td>
                <td>
                    <input type="number" id="amount_<?= $case['ec_postal_dispatch_id'] ?>" name="amount"
                           class="number form-control" placeholder="amount" value="">
                </td>
                <td>
                    <input type="text" id="barcode_<?= $case['ec_postal_dispatch_id'] ?>" name="barcode"
                           class="form-control" placeholder="barcode" value="">
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
    echo "<div class='col-sm-12'><h4 class='text-danger'>Nothing to Receive!!</h4></div>";
}
?>
    <script type="text/javascript">
        $('.number').keypress(function(event) {
            if(event.which == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46)
                return true;
            else if((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57))
                event.preventDefault();
        });
    </script>
