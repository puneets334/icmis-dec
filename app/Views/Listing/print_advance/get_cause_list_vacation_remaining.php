<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Case Report</title>
    <style>
        /* Add your CSS styles here */
        body {
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #cccccc;
        }
    </style>
</head>

<body>
    <div id="prnnt">
        <div align="center" style="font-size:12px;"><span style="font-size:12px;" align="center"><b>
            <img src="<?= base_url('images/scilogo.png') ?>" width="50px" height="80px"><br>
                    SUPREME COURT OF INDIA
                    <br>
                </b>
            </span>
        </div>
        <h2 style="text-align: center;">FINAL LIST OF READY REGULAR HEARING MATTERS TO BE LISTED DURING SUMMER VACATION <?= $year ?><br></h2>
        <p>NOTE: CHRONOLOGY IS BASED ON THE DATE OF INITIAL FILING</p>
        <u style="font-size: 18px; font-weight: normal">CASES WHICH ARE TO BE LISTED DURING SUMMER VACATION AS PER DIRECTIONS OF HON'BLE COURT</u>

        <table style="margin-top: 13px;">
            <tr>
                <th>SNo.</th>
                <th>Case No.</th>
                <th>Petitioner / Respondent</th>
                <th>Petitioner/Respondent Advocate</th>
            </tr>

            <?php if (!empty($cases)): ?>
                <?php $sno = 1;
                foreach ($cases as $case): ?>
                    <tr>
                        <td><?= $sno++ ?></td>
                        <td><?= !empty($case['reg_no_display']) ? $case['reg_no_display'] : 'Diary No. ' . substr_replace($case['diary_no'], '-', -4, 0) ?></td>
                        <td><?= $case['pet_name'] ?></td>
                        <td>
                            <?php

                            echo !empty($fetchAdvocates['p_n']) ? str_replace(",", ", ", trim($fetchAdvocates['p_n'], ",")) : '';
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No cases found.</td>
                </tr>
            <?php endif; ?>
        </table>

        <br>
        <p align='left' style="font-size: 12px;"><b>NEW DELHI<BR /><?php date_default_timezone_set('Asia/Kolkata');
                                                                    echo date('d-m-Y H:i:s'); ?></b>&nbsp; &nbsp;</p>
        <br>
        <p align='right' style="font-size: 12px;"><b>ADDITIONAL REGISTRAR</b>&nbsp; &nbsp;</p>
        <div style="width: 100%; padding-bottom:1px; background-color: #ddf1f9; text-align: center; border-top: 1px solid #000; position: fixed; bottom: 0; left: 0; right: 0; z-index: 0; display:block;">
            <input name="prnnt1" type="button" id="prnnt1" value="Print">
        </div>
    </div>