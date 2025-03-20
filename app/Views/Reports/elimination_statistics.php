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
                                            <font size=+1>Cases Elimination Statistics</font>
                                        </th>
                                    </tr>

                                    <tr>
                                        <th>No. of times Eliminated</th>
                                        <th colspan="1">Old Cases</th>
                                        <th colspan="1">Fresh Cases</th>
                                    </tr>
                                </thead>
                                <?php
                                $i = 1;
                                $total_old = 0;
                                $total_fresh = 0;
                                foreach ($get_result as $row) {
                                    $total_old = $total_old + $row['old_cases'];
                                    $total_fresh = $total_fresh + $row['fresh_cases'];
                                ?>
                                    <tr>
                                        <td align=center><?php echo $row['no_of_time_deleted'];
                                                            ?></td>
                                        <td align=right><a href="<?php echo base_url(); ?>/Report/elimination_statistics_details?eliminated=<?php echo  $row['no_of_time_deleted']
                                                                                                                                            ?>&type=O"><?php echo $row['old_cases'];
                                                                                                                                                    ?></a></td>
                                        <td align=right><a href="<?php echo base_url(); ?>/Report/elimination_statistics_details?eliminated=<?php echo  $row['no_of_time_deleted']
                                                                                                                                            ?>&type=F"><?php echo $row['fresh_cases'];
                                                                                                                                                    ?></a></td>
                                    </tr>

                                <?php
                                } // while end
                                ?>
                                <tr>
                                    <td><b>Total</b></td>
                                    <td align=right><b><?php echo  $total_old
                                                        ?></b></td>
                                    <td align=right><b><?php echo $total_fresh
                                                        ?></b></td>
                                </tr>
                            </table>
                        </div>
                        <center><input name="cmdPrnRqs22" type="button" id="cmdPrnRqs22" onClick="CallPrint('prnTable');" value="PRINT"></center>
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