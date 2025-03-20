<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Single Judge Advance List</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
        }
        .subheading {
            font-weight: bold;
            background-color: #cccccc;
        }
        .doc-description {
            color: blue;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div id="prnnt">
        <div class="header">
        <tr><th colspan="4" style="text-align: center;"><img src="<?= base_url('images/scilogo.png'); ?>" width="50px" height="80px"/></th></tr>
            <br />
            SUPREME COURT OF INDIA
            <br />
            Advance List of Single Judge Bench Matters to be listed from 
            <?php
            if (!empty($from_dt)) {
                echo date('l d-m-Y', strtotime($from_dt));
            } else {
                echo 'N/A';
            }
            ?> 
            To 
            <?php
            if (!empty($to_dt)) {
                echo date('l d-m-Y', strtotime($to_dt));
            } else {
                echo 'N/A';
            }
            ?>
            <br /><br />
        </div>
        <table>
            <thead>
                <tr class="subheading">
                    <th>SNo.</th>
                    <th>Case No.</th>
                    <th>Petitioner / Respondent</th>
                    <th>Petitioner/Respondent Advocate</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($records)): ?>
                    <?php foreach ($records as $index => $row): ?>
                        <!-- Print details here -->
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= $row['diary_no'] ?><br><?= $row['if_sclsc'] ?> <?= $row['section_name'] ?></td>
                            <td><?= $row['pet_name'] ?></td>
                            <td><?= $row['res_name'] ?></td>
                        </tr>
                        <!-- Additional rows and information here -->
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">No records found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <br />
        <p style="font-size: 12px; text-align: left;">
            <b>NEW DELHI<br /><?= date('d-m-Y H:i:s') ?></b>
        </p>
        <p style="font-size: 12px; text-align: right;">
            <b>ADDITIONAL REGISTRAR</b>
        </p>
    </div>
  
    <div style="width: 100%; background-color: #ddf1f9; text-align: center; border-top: 1px solid #000; position: fixed; bottom: 0;">
    <?php if ($is_printed): ?>
        Already Printed
    <?php else: ?>
        <?php if (isset($advance_weekly_no) && isset($advance_weekly_year)): ?>
            <button id="ebublish" data-weekly_number="<?= $advance_weekly_no ?>" data-weekly_year="<?= $advance_weekly_year ?>">e-Publish</button>
        <?php endif; ?>
        <button id="print">Print</button>
    <?php endif; ?>
</div>

</body>
</html>
