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


    <!-- Main content -->
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
                            <h4 class="page-header" style="margin-left: 1%">Date-Wise Received in R&I From Outside</h4>
                            <br><br>


                            <?php
                            $attribute = array('class' => 'form-horizontal','name' => 'push-form', 'id' => 'push-form', 'autocomplete' => 'off');
                            echo form_open(base_url('RI/ReceiptController/dateWiseReceived'), $attribute);
                            ?>

                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="form-group row">
                                        <label for="from" class="col-sm-4 col-form-label">From Date: </label>
                                        <div class="col-sm-7">
                                            <input type="date" id="fromDate" name="fromDate" class="form-control datepick" required placeholder="From Date" value="<?=!empty($fromDate)?$fromDate:null;?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-group row">
                                        <label for="to_date" class="col-sm-4 col-form-label">To Date:</label>
                                        <div class="col-sm-7">
                                            <input type="date" id="toDate" name="toDate" class="form-control datepick" required placeholder="From Date" value="<?=!empty($toDate)?$toDate:null;?>">
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-2">
                                    <button type="submit"  style="float:right" id="view" name="view" onclick="check(); " class="btn btn-block btn-primary">View</button>
                                </div>
                            </div>

                            <?php form_close();?>

                            <br><br>


                            <div class="row">
                                <?php
                                if(isset($fromDate) && isset($toDate)) {

                                    if (!empty($receiptData)) {
//                                        echo "<pre>";
//                                        print_r($receiptData);
//                                        die;
                                        ?>
                                <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4 query_builder_wrapper">

                                        <table id="reportTable1" class="table table-bordered table-striped datatable_report">
                                            <thead>
                                            <tr>
                                                <th >SNo.</th>
                                                <th >Diary Number</th>
                                                <th >Sent To</th>
                                                <th >Postal Type, Number & Date</th>
                                                <th >Sender Name & Address</th>
                                                <th >Case Number</th>
                                                <th >Remarks</th>
                                                <th >Subject</th>
                                                <th >Received on in R&I</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $s_no = 1;
                                            foreach ($receiptData as $case) {
//                                            echo "<pre>";
//                                            print_r($case);
//                                            die;
//                                                echo gettype($case['postal_date']);die;
                                                ?>
                                                <tr>
                                                    <td><?= $s_no++; ?></td>
                                                    <td>
                                                        <a href="<?=base_url();?>/RI/ReceiptController/completeDetail/<?=$case['id']?>" target="_blank">
                                                            <?= $case['diary']; ?></a>
                                                    </td>
                                                    <td>
                                                        <?= $case['address_to']; ?>
                                                    </td>
                                                    <td><?php
                                                        echo $case['postal_type'] . ',&nbsp;' . $case['postal_number'] . ',&nbsp;' ; echo !empty($case['postal_date']) ?  date("d-m-Y", strtotime($case['postal_date'])) :'';
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
                                                        $diarynumber = "Diary No. " . substr($diarynumber, 0, -4) . "/" . substr($diarynumber, -4) . "<br/>" . $case['reg_no_display'];
                                                    }
                                                    ?>
                                                    <td><?= $diarynumber; ?></td>
                                                    <td><?= !empty($case['remarks'])?$case['remarks']:null; ?></td>
                                                    <td><?= !empty($case['subject'])?$case['subject']:null; ?></td>
                                                    <td><?= $case['received_by'] ?>
                                                        On <?= date("d-m-Y h:i:s A", strtotime($case['received_on'])) ?></td>
                                                </tr>
                                                <?php
                                                $s_no++;
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                </div>
                                        <?php
                                    } else {
                                        ?>
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
<script>
    $(function () {
        $(".datatable_report").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
        }).buttons().container().appendTo('.query_builder_wrapper .col-md-6:eq(0)');

    });
    function check() {
        var fromDate = document.getElementById('fromDate').value;
        var toDate = document.getElementById('toDate').value;
        date1 = new Date(fromDate.split('-')[2], fromDate.split('-')[1] - 1, fromDate.split('-')[0]);
        date2 = new Date(toDate.split('-')[2], toDate.split('-')[1] - 1, toDate.split('-')[0]);
        if (date1 > date2) {
            alert("To Date must be greater than From date");
            return false;
        }
    }


</script>
