<?php if (session()->getFlashdata('msg')): ?>
    <?= session()->getFlashdata('msg') ?>
<?php endif; ?>

<?php
if (is_array($disposedCasesList)) {

?>

    <div id="printable" class="box box-danger">
        <br>
        <?php if ($param[1] == 110) { ?>
            <div class=" col-sm-12">
                <button type="submit" id="btnAcceptAndDispatch" name="btnAcceptAndDispatch" class="btn btn-success btn-block pull-right generateROP" onclick="receiveAndAutoDispatch();"><i class="fa fa-check"></i>&nbsp;Received Revert Cases and Dispatch to Record Keeper</button>
            </div>
        <?php } ?>
        <br>
        <?php
        if ($app_name == 'disposedCasesList') {
        ?>
            <table id="reportTable1" class="table table-striped table-hover">
                <?php if ($param[1] == 110) { ?>
                    <caption>
                        <h4 style="text-align: center;">
                            Record of File Revert Cases BETWEEN &nbsp;<strong><?= date('d-m-Y', strtotime($param[2])); ?></strong> and <strong><?= date('d-m-Y', strtotime($param[3])); ?></strong> </h4>
                        </h4>
                    </caption>
                <?php } ?>
                <thead>
                    <tr>
                        <th style="width:5px;">S.No.</th>
                        <th style="width:40%;">Case No.</th>
                        <!--<th>Urgency in Compliance</th>-->
                        <th>Consignment<br>Remarks</th>
                        <th>Dispatch By</th>
                        <th>Dispatch Date</th>
                        <th>Remarks</th>
                        <th>Receive</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $s_no = 1;
                    foreach ($disposedCasesList as $result) {
                    ?>
                        <tr>
                            <td><?= $s_no; ?></td>
                            <td><strong><?= $result['case_no']; ?></strong><br>
                                <?= $result['cause_title']; ?>
                                <br>Order Dt.:<?= $result['order_date']; ?>
                                <br>Coram: <?= $result['coram']; ?>

                            </td>
                            <!--<td style="text-align: center;"><input type="checkbox" name="IsUrgencyInCompliance" value=<?/*=$result['diary_no'];*/ ?>></td>-->
                            <td style="text-align: center;"><textarea class="consignmentRemarks" name="consignmentRemarks">
                                        <?php if ($result['consignment_remark'] != null and  $result['consignment_remark'] != '') { ?><?= trim($result['consignment_remark']); ?><?php } ?>

                                    </textarea></td>
                            <td><?= $result['dispathBy']; ?></td>
                            <td> <?= date('d-m-Y', strtotime($result['dispatchDate'])); ?> </td>
                            <td><?= $result['remarks']; ?></td>
                            <td style="text-align: center;"><input type="checkbox" id="receivedCases" name="receivedCases" value=<?= $result['diary_no']; ?>></td>

                        </tr>
                    <?php
                        $s_no++;
                    }   //for each
                    ?>
                </tbody>
            </table>
        <?php
        } ?>

    </div>
<?PHP
}
?>