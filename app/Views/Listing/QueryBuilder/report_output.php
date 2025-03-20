

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report Output</title>
</head>
<body>
    <div id="prnnt" style="font-size:12px;">
        <div align="center" style="font-size:12px;">
            <b><img src="../images/scilogo.png" width="50px" height="80px"/><br/>
            SUPREME COURT OF INDIA<br/></b>
        </div>
        <table border="0" width="100%" style="font-size:12px; text-align: left; background: #ffffff;" cellspacing="0">
            <tr>
                <th colspan="4" style="text-align: center;">
                    <br><?= $listHeading ?><br>
                    <br><?= $inputTitle ?><br>
                </th>
            </tr>
            <tr>
                <th colspan="4" style="text-align: left;"><br></th>
            </tr>
            <?php if (!empty($reportData)): ?>
                <tr style="font-weight: bold; background-color:#cccccc;">
                    <td style="width:5%;">SNo.</td>
                    <td style="width:20%;">Case No.</td>
                    <td style="width:35%;">Petitioner / Respondent</td>
                    <td style="width:40%;">Petitioner/Respondent Advocate</td>
                </tr>
                <?php $psrno = 1; foreach ($reportData as $row): ?>
                    <tr>
                        <td><?= $psrno++ ?></td>
                        <td><?= $row['reg_no_display'] ?: 'Diary No. ' . substr_replace($row['diary_no'], '-', -4, 0) ?></td>
                        <td><?= $row['pet_name'] ?></td>
                        <td><?= $row['r_n'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4">No Records Found</td></tr>
            <?php endif; ?>
        </table>
        <p align='left' style="font-size: 12px;"><b>NEW DELHI<BR/><?php echo date('d-m-Y H:i:s'); ?></b>&nbsp; &nbsp;</p>
        <p align='right' style="font-size: 12px;"><b>ADDITIONAL REGISTRAR</b>&nbsp; &nbsp;</p>
    </div>
</body>
</html>
