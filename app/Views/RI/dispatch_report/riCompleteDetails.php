<?php
/**
 * Created by PhpStorm.
 * User: Ram Gopal Verma
 * Date: 18/03/25
 * Time: 10:56 AM
 */
?>
<!-- <link rel="stylesheet" href="<?=base_url()?>assets/css/bootstrap.min.css">
<link rel="stylesheet" href="<?=base_url()?>assets/plugins/datatables/jquery.dataTables.min.css"> -->
<div class="col-sm-2 pull-right">
    <button class="btn btn-default" onclick="printDiv('DivIdToPrint')">Print</button>
</div>
<div class="content-fluid" id="DivIdToPrint">
    <h3 class="page-header">Letter Details</h3>
    <div class="col-sm-12">
        <div class="form-group row">
            <p class="col-sm-4 control-label">
                <?php
                 if ($RICompleteDetail->is_with_process_id == 1) { ?>
                    <b>Process Id: </b><?= $RICompleteDetail->process_id ?>/<?= $RICompleteDetail->process_id_year ?>
                <?php }
                else if($RICompleteDetail->is_case == 1){
                    echo "<b>Decree</b>";
                }
                else { ?>
                    <b>Reference No.: </b><?= $RICompleteDetail->reference_number ?>
                <?php } ?>
            </p>
            <p class="col-sm-4 control-label"><b>Case Number : </b><?=$RICompleteDetail->case_no?></p>
            <p class="col-sm-4 control-label"><b>Dispatch Mode : </b><?=$RICompleteDetail->postal_type_description?></p>
        </div>
        <div class="form-group row">
            <p class="col-sm-4 control-label">
                <b>Send To : </b><?=$RICompleteDetail->send_to_name?>
            </p>
            <p class="col-sm-4 control-label">
                <?= (($RICompleteDetail->send_to_address) != '') ? '<b>Address: </b>' . ($RICompleteDetail->send_to_address) : '' ?>
                <?= (($RICompleteDetail->district_name) != '') ? ' ,' . ($RICompleteDetail->district_name) : '' ?>
                <?= (($RICompleteDetail->state_name) != '') ? ' ,' . ($RICompleteDetail->state_name) : '' ?>
                <?= ($RICompleteDetail->pincode != 0) ? ' ,' . $RICompleteDetail->pincode : '' ?>
            </p>
            <p class="col-sm-4 control-label"><?=($RICompleteDetail->doc_type !='') ? '<b>Document Type: </b>'.$RICompleteDetail->doc_type : ''?></p>
        </div>
        <div class="form-group row">
            <p class="col-sm-4 control-label"><b>Waybill No.(Postal No.) : </b><?=$RICompleteDetail->waybill_number?></p>
            <p class="col-sm-4 control-label"><b>Weight (gr.) : </b><?=$RICompleteDetail->weight?></p>
            <p class="col-sm-4 control-label"><b>Postal Charges : </b><?=$RICompleteDetail->postal_charges?></p>
        </div>
        <div class="form-group row">
            <p class="col-sm-4 control-label"><b>Current Stage : </b><?=$RICompleteDetail->current_status?></p>
            <p class="col-sm-4 control-label"><b>From Section : </b><?=$RICompleteDetail->send_to_section?></p>
        </div>
        <div class="form-group row">
            <p class="col-sm-4 control-label"><b>Serve Stage : </b><?=$RICompleteDetail->serve_stage?></p>
            <p class="col-sm-4 control-label"><b>Serve/Un-Serve Type : </b><?=$RICompleteDetail->serve_type?></p>
            <p class="col-sm-4 control-label"><b>Serve/Un-Serve Remarks : </b><?=$RICompleteDetail->serve_remarks?></p>
        </div>
    </div>

<?php
if (isset($RICompleteDetail)) {
    //var_dump($RICompleteDetail);
    //var_dump($dispatchTransactions);
    ?>
<div class="col-sm-12">
    <h4>Dispatch Transactions</h4>
    <table id="reportTable1" style="width: 95%" class="table table-striped table-hover">
        <thead>
        <tr>
            <th width="4%">#</th>
            <th width="20%">Action Taken</th>
            <th width="15%">Action Taken by</th>
            <th width="10%">Action Taken On</th>
            <th width="25%">Remarks</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $s_no = 1;
        foreach ($dispatchTransactions as $case) {
            ?>
            <tr>
                <td><?=$s_no?></td>
                <td><?= $case['letter_stage'] ?></td>
                <td><?= $case['name'] ?>(<?=$case['empid']?>), <?=$case['section_name']?></td>
                <td><?= date("d-m-Y h:i:s A", strtotime($case['updated_on'])) ?></td>
                <td><?=$case['remarks']?></td>
            </tr>
            <?php
            $s_no++;
        }
        ?>
        </tbody>
    </table>
</div>

<?php }
else { ?>
    <div class="form-group col-sm-12">
            <label class="text-danger">&nbsp;Nothing to show!!</label>
        </div>
<?php }
?>
</div>
<script>
    $(document).ready(function() {
        $('#reportTable1').DataTable();
    } );
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
</script>
<!-- <script src="<?=base_url()?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="<?=base_url()?>assets/js/bootstrap.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/jquery.dataTables.min.js"></script> -->