<?php

if(!empty($processIdDetails)){

?>

<div class="form-group col-sm-3 pull-right">
    <label>&nbsp;</label>
    <button type="button" id="btnNext" name="btnNext" class="btn btn-success btn-block pull-right" onclick="goNextFunction();">
        <i class="fa fa-fw fa-download"></i>&nbsp;Next </button>
</div>
<table id="tblMainLetter" style="width: 100%" class="table table-striped table-hover">
    <thead>
    <tr>
        <th width="3%">#</th>
        <th width="30%">Letter Detail</th>
        <th width="10%">Select</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $s_no = 1;
    //var_dump($dataToUpdateServeStatus);
    foreach ($processIdDetails as $case) {
       // print_r($processIdDetails);die();
        ?>
        <tr>
            <td><?= $s_no; ?></td>
            <td>
                <?php if ($case['is_with_process_id'] == 1) { ?>
                    <b>Process Id: <?= $case['process_id'] ?>/<?= $case['process_id_year'] ?></b>
                <?php } else { ?>
                    <b>Reference No.: <?= $case['reference_number'] ?></b>
                <?php } ?>
                <br/>
                <?php if ($case['is_case'] == 1) { ?>
                    <?php //= $case['case_no'] ?><br/>
                <?php } ?>
                <?= isset($case['send_to_name']) ? trim($case['send_to_name']) : '' ?><br/>
            <?= (isset($case['send_to_address']) && trim($case['send_to_address']) != '') ? '<b>Address: </b>' . trim($case['send_to_address']) : '' ?>
            <?= (isset($case['district_name']) && trim($case['district_name']) != '') ? ' ,' . trim($case['district_name']) : '' ?>
            <?php //= (isset($case['state_name']) && trim($case['state_name']) != '') ? ' ,' . trim($case['state_name']) : '' ?>
            <?= (isset($case['pincode']) && $case['pincode'] != 0) ? ' ,' . $case['pincode'] : '' ?><br/>

                <b>Document Type: </b> <?= $case['doc_type'] ?>

            </td>
            <td>
                <div class="radio" id="radioDiv">
                    <input type="radio" name="mainletter" id="mainletter" value="<?= $case['ec_postal_dispatch_id'] ?>">
                </div>
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
        echo "<h4>No Data Found!</h4>";
    }
    ?>

