<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Date Wise Matter Listed</h3>
                            </div>
                          
                        </div>
                    </div>
                    <div class="card-body">
                        <button type="button" style="width:15%;float:left" id="prnnt1" name="print" onclick="printDiv('printable')" class="btn btn-block btn-warning">Print</button>
                        <!-- <button type="button" style="width:15%;float:left" id="prnnt1" name="print" onclick="printDiv('printable')" class="btn btn-block btn-warning">Print</button> -->
                        <!-- <input name="prnnt1" style="width:15%;float:left" type="button" id="prnnt1" value="Print" class="btn btn-block btn-warning"> -->
                        <br />
                        <div id="printable">
                            <p style="text-align:center;vertical-align: middle;">
                            <h2 align="center">Matters Listed from <?= date('d-m-Y', strtotime($first_date)) ?> to <?= date('d-m-Y', strtotime($to_date)) ?></h2>
                            </p>
                            <?php
                            if (isset($listed_result) && sizeof($listed_result) > 0) {
                            ?>
                            <div id="prnnt" style="font-size:12px;">
                                <table class="table table-striped custom-table">
                                    <thead>
                                        <br />
                                        <tr>
                                            <th rowspan='2'>Sr.No.</th>
                                            <th rowspan='2'>Date</th>
                                            <th colspan='3'>Total No. of matters listed </th>
                                        </tr>
                                        <tr>
                                            <th>Hon'ble Court</th>
                                            <th>Chamber Court</th>
                                            <th>Registrar Court</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        $total_court = 0;
                                        $total_chamber = 0;
                                        $total_reg = 0;
                                        foreach ($listed_result as $result) {
                                            $i++;
                                        ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo  date('d-m-Y', strtotime($result['date1'])); ?></td>
                                                <td><a target="_blank" href="<?php echo base_url() ?>/listing/Report/listed_detail?date=<?= $result['date1']; ?>&flag=J"> <?php echo $result['court']; ?></a></td>
                                                <td><a target="_blank" href="<?php echo base_url() ?>/listing/Report/listed_detail?date=<?= $result['date1']; ?>&flag=C"> <?php echo $result['chamber']; ?></a></td>
                                                <td><a target="_blank" href="<?php echo base_url() ?>/listing/Report/listed_detail?date=<?= $result['date1']; ?>&flag=R"> <?php echo $result['reg']; ?></a></td>
                                            </tr>

                                        <?php
                                            $total_court += $result['court'];
                                            $total_chamber += $result['chamber'];
                                            $total_reg += $result['reg'];
                                        }
                                        ?>
                                        <tr style="font-weight: bold;">
                                            <td colspan="2">Total</td>
                                            <td><?= $total_court ?></td>
                                            <td><?= $total_chamber ?></td>
                                            <td><?= $total_reg ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                    </div>
                        </div>
                    <?php
                            }

                    ?>
                    <div class="box box-info">
                        <!-- <form class="form-horizontal" id="push-form" method="post" action="<?php echo base_url() ?>/report/listed_matter"> -->
                        <?php
                        $action = base_url('Listing/report/listed_matter');
                        $attribute = 'id="form" method="post"';
                        echo form_open($action, $attribute);
                        csrf_token();
                        ?>

                        <div class="box-body">
                            <div class="form-group row">
                                <div class="col-sm-2">
                                    <label for="from">From Date</label>
                                    <input type="text" id="from_date" name="from_date" class="form-control datepick" required placeholder="From Date">
                                </div>
                                <div class="col-sm-2">
                                    <label for="to">To Date</label>
                                    <input type="text" class="form-control datepick" id="to_date" required name="to_date" placeholder="To Date">
                                </div>
                                <div class="col-sm-4 mt-4">
                                    <div class="mt-3">
                                        <button type="submit" id="view" name="view" onclick="check(); " class="btn btn-block btn-primary">View</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<script>
    $(function() {
        $('.datepick').datepicker({
            dateFormat: 'dd-mm-yy',
            autoclose: true
        });
    });

    function check() {
        var fromDate = document.getElementById('from_date').value;
        var toDate = document.getElementById('to_date').value;
        date1 = new Date(fromDate.split('-')[2], fromDate.split('-')[1] - 1, fromDate.split('-')[0]);
        date2 = new Date(toDate.split('-')[2], toDate.split('-')[1] - 1, toDate.split('-')[0]);
        if (date1 > date2) {
            alert("To Date must be greater than From date");

            return false;
        }
    }
    $(document).on("click", "#prnnt1", function() {
        var prtContent = $("#prnnt").html();
        var temp_str = prtContent;
        var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>
</body>

</html>