<br><br>
<div align="center"><input name="cmdPrnRqs2" type="button" id="cmdPrnRqs2" onClick="CallPrint('prnTable');" value="PRINT"></div>
<?php if (count($result_array) > 0) { ?>
    <div id="prnTable" align="center">
    <table cellpadding="1" cellspacing="0" border="1">
            <thead>
                <tr>
                    <th colspan=13>
                        <font size=+1><?php echo $report_name ?> </font>
                    </th>
                </tr>
                <tr>
                    <th>Sl.No.</th>
                    <th>Case Number</th>
                    <th>Causetitle</th>
                    <th>Last Listing Date</th>
                    <th>Next Listing Date</th>
                    <th>Subhead (Purpose)</th>
                    <th>Updated On (Module)</th>
            </thead>
            <?php
            $i = 1;
            $total = 0;
            foreach ($result_array as $row) {
            ?><tr>
                    <td><?= $i ?></td>
                    <td><?= $row['case_no']; ?></td>
                    <td><?= $row['cause_title']; ?></td>
                    <td><?= $row['last_listed']; ?></td>
                    <td><?= $row['next_date']; ?></td>
                    <td><?php echo $row['subhead'] . '(' . $row['purpose'] . ')'; ?></td>
                    <td><?= $row['updated_on'] . '(' . $row['module'] . ')'; ?></td>
                </tr>

            <?php
                $i++;
            }
            ?>
        </table>
    </div>
    <center><input name="cmdPrnRqs22" type="button" id="cmdPrnRqs22" onClick="CallPrint('prnTable');" value="PRINT"></center>
<?php

} else{
    echo "<center><h2>Record Not Found</h2></center>";
}
?>

</div>
<script language="javascript">

    function CallPrint(strid)
    {
        var prtContent = document.getElementById(strid);
        var WinPrint = window.open('','','letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');
        WinPrint.document.write(prtContent.innerHTML);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        //WinPrint.close();
        //prtContent.innerHTML=strOldOne;
    }

</script>