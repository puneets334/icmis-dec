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


                        <?php
                        if ($flag == 'R')
                            $flag_display = "Registrar";
                        else if ($flag == 'J')
                            $flag_display = "Hon'ble";
                        else if ($flag == 'C')
                            $flag_display = "Chamber";
                        ?>
                        <br />
                        <div id="prnnt">
                            <table class="table table-striped table-hover ">
                                <thead>

                                    <tr>
                                        <h2 align="center">Matters Listed in <?= $flag_display ?> Court on <?php echo date('d-m-Y', strtotime($date)); ?> </h2>
                                    </tr>
                                    <?php
                                    if (isset($listed_detail_result) && sizeof($listed_detail_result) > 0) { ?>
                                        <tr>
                                            <th>#</th>
                                            <th>Case<br />Number</th>
                                            <th>Cause Title</th>
                                            <!--<th>Listing Date</th>-->
                                        </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $i = 0;
                                        foreach ($listed_detail_result as $result) {
                                            $i++;
                                    ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $result['diary_no'] . "<br/>" . $result['reg_no_display']; ?></td>
                                            <td><?php echo $result['title']; ?></td>
                                            <!-- <td><?php /*echo date('d-m-Y',strtotime($result['date1']));*/ ?></td>-->

                                        </tr>

                                    <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } else {
                                        echo '<br/><br/><br/>';
                                        echo "<font size='18px'; color='red';>No case Found!</font>";
                                    }
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
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