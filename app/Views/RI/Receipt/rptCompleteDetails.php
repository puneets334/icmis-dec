<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>R&I Complete Detail</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/skins/_all-skins.min.css">

    <style>
        table, th, td {
            border: 1px solid;
            text-align: center;
            padding: 15px;
        }
    </style>
</head>
<body class="hold-transition skin-blue layout-top-nav">
<div class="content-fluid">
    <!--<div class="container">-->
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <button class="btn btn-default" onclick="printDiv('DivIdToPrint')"><i class="fa fa-print"> Print</i></button>
        </div>
        <div class="row" id="DivIdToPrint">
            <div class="col-xs-12">
                <h4 class="control-label">R&I Diary Status as on <?=date('d-m-Y h:i:s A')?></h4>
            </div>
            <br/>
            <div class="col-xs-12">


                <?php

                if(!empty($completeDetails)){
                    $completeDetail=$completeDetails[0];
                }
                ?>
                <div class="form-group">
                    <p class="col-sm-6 control-label"><b>Diary Number : </b><?php echo $completeDetail['ri_diary_number']?></p>
                    <p class="col-sm-6 control-label"><b>Recived By : </b><?php echo $completeDetail['received_by']?>
                        <?php echo !empty($completeDetail['received_on']) ? ' On ' . date("d-m-Y h:i:s A", strtotime($completeDetail['received_on'])) : ''; ?>
                    </p>
                    <p class="col-sm-6 control-label"><b>Postal Number & Date : </b><?=$completeDetail['postal_type']?>&nbsp;&nbsp;<?=$completeDetail['postal_no']?>
                        <?php echo !empty($completeDetail['postal_date']) ? ' Dated ' . date("d-m-Y", strtotime($completeDetail['postal_date'])) : ''; ?>
                    </p>
                    <p class="col-sm-6 control-label"><b>Letter Number & Date : </b><?=$completeDetail['letter_no']?>
                        <?php echo !empty($completeDetail['letter_date']) ? ' Dated ' . date("d-m-Y", strtotime($completeDetail['letter_date'])) : ''; ?>
                    </p>
                    <p class="col-sm-6 control-label"><b>Sender Name & Address : </b><?=$completeDetail['sender_name']?>
                        <?php echo !empty($completeDetail['address']) ? ' Address: ' . $completeDetail['address'] : ''; ?>
                        <?php echo !empty($completeDetail['state']) ? ' State: ' . $completeDetail['state'] : ''; ?>
                    </p>
                    <p class="col-sm-6 control-label"><b>Subject : </b><?php echo $completeDetail['subject']?></p>
                    <p class="col-sm-6 control-label"><b>Case Number : </b> <?php echo !empty($completeDetail['case_no']) ?$completeDetail['case_no']: ''; ?></p>
                    <p class="col-sm-6 control-label"><b>Openable : </b>
                        <?php echo !empty($completeDetail['is_openable']) ? ($completeDetail['is_openable']=='t'?'Yes':'No') : ''; ?>
                    </p>
                    <p class="col-sm-6 control-label"><b>Is original Record : </b>
                        <?php echo !empty($completeDetail['is_original_record']) ? ($completeDetail['is_original_record']=='t'?'Yes':'No') : ''; ?>
                    </p>
                    <p class="col-sm-6 control-label"><b>PIL diary Number : </b>
                        <?php echo $completeDetail['pil_diary_number']?>
                    </p>
                    <p class="col-sm-6 control-label"><b>Remarks : </b>
                        <?php echo $completeDetail['remarks']?>
                    </p>

                </div>
                <br/><br/><br/><br/>
                <?php
                if(!empty($transactions)){?>
                    <div class="col-xs-12">
                        <div class="form-group">
                            <h3 class="control-label " style="color:red">Transactions</h3>
                            <table style="border-collapse: collapse;" >
                                <thead>
                                <th width="15%">Dispatched By/On</th>
                                <th width="10%">Dispatch To</th>
                                <th width="15%">Action Taken</th>
                                <th width="10%">Action Taken By/On</th>
                                <th width="10%">Status</th>
                                </thead>
                                <tbody>
                                <?php
                                $i = 0;
                                $rowserial = "odd";
                                foreach ($transactions as $case){
                                    $i++;
                                    if ($i % 2 == 0)
                                        $rowserial = "even";
                                    else {
                                        $rowserial = "odd";
                                    }
                                    ?>
                                    <tr role="row" class="<?= $rowserial ?>">
                                        <td><?= $case['dispatched_by'] ?> <?= !empty($case['dispatched_on']) ? ' On ' . date("d-m-Y h:i:s A", strtotime($case['dispatched_on'])) : ''; ?></td>
                                        <td><?=$case['address_to']?></td>
                                        <td><?=$case['action_taken']?></td>
                                        <td><?= $case['action_taken_by'] ?><?= !empty($case['action_taken_on']) ? ' On ' . date("d-m-Y h:i:s A", strtotime($case['action_taken_on'])) : ''; ?>
                                            <?=$case['return_reason']!=''?' Return Reason: '.$case['return_reason']:''?>
                                        </td>
                                        <td>&nbsp;</td>
                                    </tr>
                                <?php }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                <?php }
                ?>
            </div>
        </div>
    </section>
</div>
<script src="<?=base_url()?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="<?=base_url()?>assets/js/bootstrap.min.js"></script>
<script src="<?=base_url()?>assets/js/pil.js"></script>
<script>
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
</body>
</html>
