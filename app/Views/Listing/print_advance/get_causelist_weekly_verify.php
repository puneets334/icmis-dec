<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Court List</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
<div id="prnnt">

<h1>Supreme Court of India</h1>

<?php if (empty($result)): ?>
    <p>No Records Found</p>
<?php else: ?>
    <table>
        <tr>
            <th>SNo.</th>
            <th>Case No.</th>
            <th>Petitioner / Respondent</th>
            <th>Petitioner/Respondent Advocate</th>
        </tr>

        <?php foreach ($result as $index => $row): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= $row['diary_no'] ?></td>
                <td><?= $row['judges'] ?></td>
                <td><!-- Add the Advocate Names Here --></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<p><b>NEW DELHI<BR/><?php date_default_timezone_set('Asia/Kolkata'); echo date('d-m-Y H:i:s');?></b>&nbsp; &nbsp;</p>
<p align='right' style="font-size: 10px;"><b>ADDITIONAL REGISTRAR</b>&nbsp; &nbsp;</p>

<div style="width: 100%; position: fixed; bottom: 0; left: 0; right: 0; z-index: 0; display: flex; justify-content: center; align-items: center; background-color: white; padding: 10px;">
    <input name="prnnt1" type="button" id="prnnt1" value="Print">
</div>

</div>

</body>
</html>
