<?php

if (!empty($dataToPrintAddressSlip) && is_array($dataToPrintAddressSlip)) {
    //var_dump($dataToPrintAddressSlip); ?>
    <div class="row">
        <button class="btn btn-primary pull-right" onclick="printDiv('printable')">
            <i class="fa fa-print"> Print</i>
        </button>
    </div>
    <div id="printable" class="row">
        <table class="col-xs-12">
            <?php
            foreach ($dataToPrintAddressSlip as $index => $case) {
                if ($index % 2 == 0 || $index == 0) {
                    echo "<tr style='border-bottom: dashed; border-width: 1mm'>";
                }
                ?>
                <td style="border-right: dashed; padding-bottom: 0.113cm !important; padding-top: 0.113cm !important;"
                    class="col-xs-6">
                    <div style="min-height: 4.4cm !important; max-height: 4.4cm !important; overflow:hidden; horiz-align: center">
                <span class="medium">
                    <b><?= $index + 1 ?>.</b>
                    <?php if ($case['is_with_process_id'] == 1) { ?>
                        PId: <?= $case['process_id'] ?>/<?= $case['process_id_year'] ?>&nbsp;&nbsp;(<?= $case['section_name'] ?>)
                        <br/>
                    <?php } else { ?>
                        <?= (trim($case['reference_number']) != '') ? 'Ref No.: ' . trim($case['reference_number']) : 'Decree' ?>&nbsp;&nbsp;(<?= $case['section_name'] ?>)
                        <br/>
                    <?php } ?>
                    <?php if ($case['is_case'] == 1) { ?>
                        <?= $case['case_no'] ?><br/>
                    <?php } ?>
                    <?= ($case['send_to_name']) ?><br/>
                    <?= (($case['send_to_address']) != '') ? '<b>Address: </b>' . ($case['send_to_address']) : '' ?>
                    <?= (($case['district_name']) != '') ? ' ,' . ($case['district_name']) : '' ?>
                    <?= (($case['state_name']) != '') ? ' ,' . ($case['state_name']) : '' ?>
                    <?= ($case['pincode'] != '' && $case['pincode'] != 0 && strlen($case['pincode']) == 6) ? ' ,' . $case['pincode'] : '' ?>
                </span>
                    </div>
                </td>
                <?php
            }
            ?>
        </table>
    </div>
<?php } 
else {
    ?>
    <div class="form-group col-sm-12">
        <label class="text-danger">&nbsp;No Record Found!!</label>
    </div>
<?php
}
