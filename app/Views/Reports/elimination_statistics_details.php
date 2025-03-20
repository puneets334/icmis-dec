<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-md-10">
                                <h3 class="card-title">Elimination Statistics</h3>
                            </div>
                           
                        </div>
                    </div>
                    <div class="card-body">
                        <br><br>
                        <div align="center"><input name="cmdPrnRqs2" type="button" id="cmdPrnRqs2" onClick="CallPrint('prnTable');" value="PRINT"></div>
                        <div id="prnTable" align="center">
                            <table class="table table-striped custom-table">
                                <thead>
                                    <tr>
                                        <th colspan=13>
                                            <font size=+1><?= $report_name ?> Cases Eliminated <?= $no_of_times_deleted ?> times</font>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Sl.No.</th>
                                        <th>Case Number</th>
                                        <th>Causetitle</th>
                                        <th>Proposed Date</th>
                                        <th>DA (Section)</th>
                                        <th>Case Status</th>
                                    </tr>
                                </thead>
                                <?php
                                $i = 1;
                                $total = 0;
                                foreach ($get_result as $row) {
                                ?>
                                    <tr>
                                        <td align=center><?= $i ?></td>
                                        <td align=left><?= $row['case_no']; ?></td>
                                        <td align=left><?= $row['cause_title']; ?></td>
                                        <td align=center><?= $row['next_date']; ?></td>
                                        <td align=left><?php echo $row['daname'] . '(' . $row['section'] . ')'; ?></td>
                                        <td align=left><?= $row['case_status']; ?></td>
                                    </tr>

                                <?php
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</body>

</html>
<script language="javascript">
    function CallPrint(strid) {
        var prtContent = document.getElementById(strid);
        var WinPrint = window.open('', '', 'letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');
        WinPrint.document.write(prtContent.innerHTML);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        //WinPrint.close();
        //prtContent.innerHTML=strOldOne;
    }
</script>