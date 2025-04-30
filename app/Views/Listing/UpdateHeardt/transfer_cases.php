<div id="prnnt" style="text-align: center; font-size:13px; padding-bottom:10px;">
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
            background-color: #0d48be;
            color: white;
        }
    </style>
    <?php $theadRowStyle = 'style="text-align: left;font-weight: bold; color: #fff; background-color: #0d48be; padding: 10px;"'; ?>
    <div class="row mt-10">
        <div class="col-md-2 ml-n4 text-left">
            <input name="prnnt1" type="button" id="prnnt1" value="Print" class="btn btn-primary bk_out">
        </div>
        <div class="col-md-8 text-center">
            <h3 class="mt-3" style="text-align:center;">Cases Listed by Update Heardt Module <?= $string_heading ?? ''; ?></h3>
        </div>
        <div class="col-md-2"></div>
    </div>    
    <?php if (!empty($cases)): ?>
        <table id="customers">
            <tr style="background: #0d48be;">
                <th width="5%" <?= $theadRowStyle ?>>SrNo.</th>
                <th width="15%" <?= $theadRowStyle ?>>Case No. / Diary No.</th>
                <th width="15%" <?= $theadRowStyle ?>>Cause Title</th>
                <th width="8%" <?= $theadRowStyle ?>>Cause List Date</th>
                <th width="7%" <?= $theadRowStyle ?>>Board Type</th>
                <th width="10%" <?= $theadRowStyle ?>>Listed Before</th>
                <th width="10%" <?= $theadRowStyle ?>>Category</th>
                <th width="10%" <?= $theadRowStyle ?>>Reason</th>
                <th width="10%" <?= $theadRowStyle ?> class="bk_out" >Updated By</th>
                <th width="10%" <?= $theadRowStyle ?> class="bk_out" >Entry Date/Time</th>
            </tr>

            <?php foreach ($cases as $index => $case): ?>
                <tr>
                    <td style='vertical-align: top;'><?= $index + 1; ?></td>
                    <td style='vertical-align: top;'><?= $case['case_no']; ?></td>
                    <td style='vertical-align: top;'><?= $case['cause_title']; ?></td>
                    <td style='vertical-align: top;'><?= $case['listed_on']; ?></td>
                    <td style='vertical-align: top;'><?= ($case['board_type'] == 'J' ? 'Court' : ($case['board_type'] == 'C' ? 'Chamber' : 'Registrar')); ?></td>
                    <td style='vertical-align: top;'><?= $case['listed_before']; ?></td>
                    <td style='vertical-align: top;'><?= $case['subject_category']; ?></td>
                    <td style='vertical-align: top;'><?= $case['reason']; ?></td>
                    <td class="bk_out"><?= $case['username'] . " [" . $case['empid'] . "]"; ?></td>
                    <td class="bk_out"><?= date('d-m-Y H:i:s', strtotime($case['ent_dt'])); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No Records Found</p>
    <?php endif; ?>
</div>