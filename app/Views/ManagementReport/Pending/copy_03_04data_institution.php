<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report</title>
</head>
<body>
    <form>
        <?= csrf_field() ?>
        
        <?php if (!empty($report_data)) : ?>
            <div id="prnTable" align="center">
                <table cellpadding="1" cellspacing="0" border="1">
                    <tr>
                        <th colspan="7">
                            <font color="blue" size="+1">Report between: <?= esc($from_date) ?> to <?= esc($to_date) ?></font>
                        </th>
                    </tr>
                    <tr>
                        <th>Sno</th>
                        <th>Date</th>
                        <th>Case Type</th>
                        <th>Filed</th>
                        <th>Not Filed</th>
                        <th>Total</th>
                    </tr>
                    <?php 
                    $i = 1;
                    $tot = 0;
                    foreach ($report_data as $row) : ?>
                        <tr style="color: #0000FF">
                            <td><?= $i ?></td>
                            <td><?= esc($row['fil_dt']) ?></td>
                            <td><?= esc($row['short_description']) ?></td>
                            <td><?= esc($row['filed']) ?></td>
                            <td><?= esc($row['not_filed']) ?></td>
                            <td><b><?= esc($row['cnt']) ?></b></td>
                        </tr>
                        <?php 
                        $tot += $row['cnt'];
                        $i++;
                    endforeach; ?>

                    <tr style="color: #0000FF">
                        <td colspan="5" align="right"><b>Grand Total</b></td>
                        <td align="right"><b><?= esc($tot) ?></b></td>
                    </tr>
                </table>
            </div>
            <center>
                <input type="button" onclick="CallPrint('prnTable');" value="PRINT">
            </center>
        <?php else : ?>
            <center><h2>Record Not Found</h2></center>
        <?php endif; ?>
    </form>

    <script>
        function CallPrint(divId) {
            var printContents = document.getElementById(divId).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
</body>
</html>
