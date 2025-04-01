<?php
if (is_array($disposedCasesList)) {
    // echo "<pre>";
    // var_dump($disposedCasesList);
    // var_dump($param);
?>

    <div id="printable" class="box box-danger">
        <br>
        <?php if ($param[1] == 110) { ?>
            <div class=" col-sm-12">
                <button type="button" id="btnAcceptAndDispatch" name="btnAcceptAndDispatch" class="btn btn-success btn-block pull-right generateROP"
                    onclick="receiveAndAutoDispatch();"><i class="fa fa-check"></i>&nbsp;Received Cases and Dispatch to Segregation DA / Record Keeper</button>
            </div>
        <?php } ?>

        <?php if ($param[1] == 111) { ?>
            <div class=" col-sm-12">
                <button type="button" id="btnAcceptAndDispatch" name="btnAcceptAndDispatch" class="btn btn-success btn-block pull-right generateROP" 
                onclick="receiveAndAutoDispatch();"><i class="fa fa-check"></i>&nbsp;Received Cases and Dispatch to Scanning</button>
            </div>
        <?php } ?>

        <?php if ($param[1] == 58) { ?>
            <div class=" col-sm-12">
                <button type="button" id="btnAcceptAndDispatch" name="btnAcceptAndDispatch" class="btn btn-success btn-block pull-right generateROP" 
                onclick="receiveAndAutoDispatch();"><i class="fa fa-check"></i>&nbsp;Received Cases and Dispatch to RecordRoom DA</button>
            </div>
        <?php } ?>

        <?php if ($param[1] == 112) { ?>
            <div class=" col-sm-12">
                <button type="button" id="btnAcceptAndDispatch" name="btnAcceptAndDispatch" class="btn btn-success btn-block pull-right generateROP"
                 onclick="receiveAndAutoDispatch();"><i class="fa fa-check"></i>&nbsp;Received Cases</button>
            </div>
        <?php } ?>
        <br>
        <?php

        if ($app_name == 'disposedCasesList') {
        ?>
            <!-- <table id="reportTable1" class="table table-striped table-hover"> -->

            <div id="query_builder_wrapper" class="query_builder_wrapper dataTables_wrapper dt-bootstrap4">
                <div id="printable">
                    <table id="query_builder_report" class="query_builder_report table table-bordered table-striped">

                        <?php if ($param[1] == 110) { ?>
                            <caption>
                                <h4 style="text-align: center;">
                                    RECORD OF Disposed Cases BETWEEN &nbsp;<strong><?= date('d-m-Y', strtotime($param[2])); ?></strong> and <strong><?= date('d-m-Y', strtotime($param[3])); ?></strong> to Hall No.<strong><?= $param[4]; ?>(<?= $param[5]; ?>)</strong> </h4>
                                </h4>
                            </caption>
                        <?php } ?>
                        <?php if ($param[1] == 111) { ?>
                            <caption>
                                <h4 style="text-align: center;">
                                    RECORD TO BE RECEIVE AND DISPATCH BETWEEN &nbsp;<strong><?= date('d-m-Y', strtotime($param[2])); ?></strong> and <strong><?= date('d-m-Y', strtotime($param[3])); ?></strong> </h4>
                                </h4>
                            </caption>
                        <?php } ?>

                        <?php if ($param[1] == 58) { ?>
                            <caption>
                                <h4 style="text-align: center;">
                                    RECORD TO BE RECEIVE AND DISPATCH BETWEEN &nbsp;<strong><?= date('d-m-Y', strtotime($param[2])); ?></strong> and <strong><?= date('d-m-Y', strtotime($param[3])); ?></strong> </h4>
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
                                    <td><?= $result['dispathby']; ?></td>
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
                </div>
            </div>
        <?php
        } ?>

    </div>
<?PHP
}
?>

<script>
    $(function() {
        $("#query_builder_report").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": [
                "copy", "csv", "excel", {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL'
                },
                // {
                //     extend: 'colvis',
                //     text: 'Show/Hide'
                // }
            ],
            "processing": true, // Changed "bProcessing" to "processing"
            "ordering": false, // Added to disable sorting

        }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');
    });
</script>

<div id="div_print">
    <div id="header" style="background-color:White;"></div>
    <div id="footer" style="background-color:White;"></div>
</div>