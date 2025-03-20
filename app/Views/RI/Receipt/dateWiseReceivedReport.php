<?= view('header') ?>
     <style>
        .custom-radio {
            float: left;
            display: inline-block;
            margin-left: 10px;
        }

        .custom_action_menu {
            float: left;
            display: inline-block;
            margin-left: 10px;
        }

        .basic_heading {
            text-align: center;
            color: #31B0D5
        }

        .btn-sm {
            padding: 0px 8px;
            font-size: 14px;
        }

        .card-header {
            padding: 5px;
        }

        h4 {
            line-height: 0px;
        }

        .row {
            margin-right: 15px;
            margin-left: 15px;
        }


        .box.box-success {
            border-top-color: #00a65a;
        }
        .box {
            position: relative;
            border-radius: 3px;
            background: #ffffff;
            border-top: 3px solid #d2d6de;
            margin-bottom: 20px;
            width: 100%;
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        }

        .box-header {
            color: #444;
            display: block;
            padding: 10px;
            position: relative;
        }
        .box-header.with-border {
            border-bottom: 1px solid #f4f4f4;
        }
        .box.box-danger {
            border-top-color: #dd4b39;
        }
    </style>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">R & I >> Receipt </h3>
                                </div>


                            </div>
                            <br><br>

                            <?php if (session()->getFlashdata('infomsg')) { ?>
                                <div class="alert alert-success">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong> <?= session()->getFlashdata('infomsg') ?></strong>
                                </div>

                            <?php } ?>
                            <?php if (session()->getFlashdata('success_msg')) : ?>
                                <div class="alert alert-danger alert-dismissible">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong> <?= session()->getFlashdata('success_msg') ?></strong>
                                </div>
                            <?php endif; ?>



                        </div>


                        <span class="alert alert-error" style="display: none;">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <span class="form-response"> </span>
                                </span>

                        <?php //= view('RI/RIReceiptHeading'); ?>

                        <br><br>
                        <div class="container-fluid">
                            <h4 class="page-header" style="margin-left: 1%">Receipt By Section/Officer</h4>
                            <br><br>


                            <?php
                            $attribute = array('class' => 'form-horizontal','name' => 'push-form', 'id' => 'push-form', 'autocomplete' => 'off');
                            echo form_open(base_url('RI/ReceiptController/dateWiseReceivedByConcern'), $attribute);
                            ?>

                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group row">
                                        <label for="from" class="col-sm-4 col-form-label">From Date: </label>
                                        <div class="col-sm-7">

                                            <input type="date" id="fromDate" name="fromDate" class="form-control datepick" required placeholder="From Date" value="<?=!empty($fromDate)?$fromDate:null;?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group row">
                                        <label for="to_date" class="col-sm-4 col-form-label">To Date:</label>
                                        <div class="col-sm-7">

                                            <input type="date" id="toDate" name="toDate" class="form-control datepick" required placeholder="From Date" value="<?=!empty($toDate)?$toDate:null;?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group row">
                                        <label for="report_type" class="col-sm-4 col-form-label">Report Type:</label>
                                        <div class="col-sm-7">
                                            <?php
                                            $options = array("All", "Received", "Returned");
                                            ?>
                                            <select  class="form-control" name="reportType" id="reportType">
                                                <?php
                                                foreach($options as $index=>$option){
                                                    if(!empty($reportType)) {
                                                        if ($reportType == $index)
                                                            echo "<option value='" . $index . "' selected>" . $option . "</option>";
                                                    }
                                                    else
                                                        echo "<option value='".$index."'>".$option."</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-2">

                                    <button type="submit"  style="float:right" id="view" name="view" onclick="check(); " class="btn btn-block btn-primary">View</button>
                                </div>
                            </div>

                            <?php form_close();?>

                            <br><br>


                            <div id="printable" class="row">
                                <?php

                                if(!empty($_POST['fromDate']) && !empty($_POST['toDate'])) {
//                                    var_dump($receivedData);
//                                    die;
                                    if (!empty($receivedData)) {
                                        ?>

                                        <!--<table id="reportTable1" class="table table-striped table-hover">-->
                                        <table id="reportTable1" style="width: 95%" class="table table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th width="4%">#</th>
                                                <th width="5%">Diary Number</th>
                                                <th width="10%">Sent To</th>
                                                <th width="15%">Postal Type, Number & Date</th>
                                                <th width="20%">Sender Name & Address</th>
                                                <th width="8%">Case Number</th>
                                                <th width="8%">Remarks</th>
                                                <th width="5%">Subject</th>
                                                <th width="8%">Received on in R&I</th>
                                                <th width="8%">Dispatched By & On</th>
                                                <th width="8%">Received/Returned By Concern & On</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $s_no = 1;
                                            foreach ($receivedData as $case) {
                                                ?>
                                                <tr>
                                                    <td></td>
                                                    <td>
                                                        <a href="<?=base_url();?>index.php/RIController/completeDetail/<?=$case['id']?>" target="_blank">
                                                            <?= $case['diary'] ?>
                                                    </td>

                                                    <td><?= $case['address_to'] ?>
                                                        <?php /*if (!empty($case['judgename'])) {
                                        echo $case['judgename'];
                                    } elseif (!empty($case['officer_name'])) {
                                        echo $case['officer_name'];
                                    } else {
                                        echo $case['section_name'];
                                    }*/
                                                        ?>
                                                    </td>
                                                    <td><?php
                                                        echo $case['postal_type'] . ',&nbsp;' . $case['postal_number'] . ',&nbsp;' . date("d-m-Y", strtotime($case['postal_date']));
                                                        ?>
                                                    </td>
                                                    <td><?php
                                                        echo $case['sender_name'] . '&nbsp;' . $case['address'];
                                                        ?>
                                                    </td>
                                                    <?php
                                                    $diarynumber = "";
                                                    if (!empty($case['diary_number'])) {
                                                        $diarynumber = $case['diary_number'];
                                                        $diarynumber = "Diary No. " . substr($diarynumber, 0, -4) . "/" . substr($diarynumber, -4) . "<br/>" . $case['reg_no_display'];;
                                                    }
                                                    ?>
                                                    <td><?= $diarynumber; ?></td>
                                                    <td><?= $case['remarks'] ?></td>
                                                    <td><?= $case['subject'] ?></td>
                                                    <td><?= $case['received_by'] ?>
                                                        On <?= date("d-m-Y h:i:s A", strtotime($case['received_on'])) ?></td>
                                                    <td><?= $case['dispatched_by'] ?> <?= !empty($case['dispatched_on']) ? ' On ' . date("d-m-Y h:i:s A", strtotime($case['dispatched_on'])) : ''; ?></td>
                                                    <td><?=$case['action_taken']?> By <?= $case['action_taken_by'] ?><?= !empty($case['action_taken_on']) ? ' On ' . date("d-m-Y h:i:s A", strtotime($case['action_taken_on'])) : ''; ?></td>
                                                    <!--<td><?/*=$case['action_taken_by']*/
                                                    ?> On <?/*=date("d-m-Y h:i:s A", strtotime($case['action_taken_on']))*/
                                                    ?></td>-->
                                                </tr>
                                                <?php
                                                $s_no++;
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                        <?php
                                    } else {
                                        ?>
                                            <br>
                                        <div class="form-group col-sm-12">
                                            <h4 class="text-danger">&nbsp;No Record Found!!</h4>
                                        </div>

                                    <?php }
                                }

                                ?>
                            </div>



                            <!-- /.content -->
                            <!--</div>-->
                            <!-- /.container -->
                        </div>
                        <br>
                        <br>
                        <br>

                    </div> <!-- card div -->

                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.section -->