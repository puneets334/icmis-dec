
    <div class="card-body">
    <div id="prnnt" style="text-align: center; font-size:10px; padding-bottom:10px;">
    <style>
        #customers {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
        }

        #customers td,
        #customers th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #customers tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #customers tr:hover {
            background-color: #ddd;
        }

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #4CAF50;
            color: white;
        }

        .button {
            border-radius: 3px;
            background-color: #f4511e;
            border: none;
            color: #FFFFFF;
            text-align: center;
            font-size: 20px;
            padding: 4px;
            width: 100px;
            transition: all 0.5s;
            cursor: pointer;
        }

        .button span {
            cursor: pointer;
            display: inline-block;
            position: relative;
            transition: 0.5s;
        }

        .button span:after {
            content: '\00bb';
            position: absolute;
            opacity: 0;
            top: 0;
            right: -20px;
            transition: 0.5s;
        }

        .button:hover span {
            padding-right: 25px;
        }

        .button:hover span:after {
            opacity: 1;
            right: 0;
        }
    </style>
        <h3>Cases Listed by Update Heardt Module <?= $string_heading ?? ''; ?></h3>

        <?php if (!empty($cases)): ?>
            <table id="customers">
                <tr style="background: #918788;">
                    <th>SrNo.</th>
                    <th>Case No. / Diary No.</th>
                    <th>Cause Title</th>
                    <th>Cause List Date</th>
                    <th>Board Type</th>
                    <th>Listed Before</th>
                    <th>Category</th>
                    <th>Reason</th>
                    <th class="bk_out">Updated By</th>
                    <th class="bk_out">Entry Date/Time</th>
                </tr>

                <?php foreach ($cases as $index => $case): ?>
                    <tr>
                        <td><?= $index + 1; ?></td>
                        <td><?= $case['case_no']; ?></td>
                        <td><?= $case['cause_title']; ?></td>
                        <td><?= $case['listed_on']; ?></td>
                        <td><?= ($case['board_type'] == 'J' ? 'Court' : ($case['board_type'] == 'C' ? 'Chamber' : 'Registrar')); ?></td>
                        <td><?= $case['listed_before']; ?></td>
                        <td><?= $case['subject_category']; ?></td>
                        <td><?= $case['reason']; ?></td>
                        <td class="bk_out"><?= $case['username'] . " [" . $case['empid'] . "]"; ?></td>
                        <td class="bk_out"><?= date('d-m-Y H:i:s', strtotime($case['ent_dt'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No Records Found</p>
        <?php endif; ?>
    </div>
    <button class="btn btn-primary" id="prnnt1" >Print</button>
<div>