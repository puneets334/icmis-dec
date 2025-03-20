<?= view('header') ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title); ?></title>
   
</head>
<body>
    <div id="print_area" class="col-12 m-0 p-0">
        <h3><?= esc($title); ?> (As on <?= date("d-m-Y H:i:s"); ?>)</h3>
        <?php if (!empty($results)): ?>
            <div class="box box-primary" id="tachelist">
                <div class="box-body">
                    <div class="table-responsive mailbox-messages">
                        <table class="table table-striped table-bordered table-hover example" id="members">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Court No.</th>
                                    <th>Bench</th>
                                    <th>Court Details</th>
                                    <th>Room URL</th>
                                    <th>Item Number(s)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $srno = 1; ?>
                                <?php foreach ($results as $row): ?>
                                    <?php if (!empty($row['vc_url'])): ?>
                                        <tr>
                                            <td><?= $srno++; ?></td>
                                            <td>
                                                <?= ($row['courtno'] > 60) ? 'R-VC ' . ($row['courtno'] - 60) : 
                                                    (($row['courtno'] > 30) ? 'VC ' . ($row['courtno'] - 30) : 
                                                    (($row['courtno'] > 20) ? 'R ' . ($row['courtno'] - 20) : 
                                                    'C ' . $row['courtno'])); ?>
                                            </td>
                                            <td><?= str_replace(',', '<br>', $row['judge_name']); ?></td>
                                            <td>
                                                <?php
                                                echo $row['frm_time'] ? 'Time : ' . $row['frm_time'] . '<br>' : '';
                                                echo ($row['m_f'] == 'M') ? 'Misc. List ' : 'Regular List ';
                                                if ($row['board_type_mb'] == 'J') echo "<br>Before Court ";
                                                if ($row['board_type_mb'] == 'S') echo "<br>Before Single Judge ";
                                                if ($row['board_type_mb'] == 'C') echo "<br>Before Chamber ";
                                                if ($row['board_type_mb'] == 'R') echo "<br>Before Registrar Court ";
                                                ?>
                                            </td>
                                            <td><?= esc($row['vc_url']); ?></td>
                                            <td style="text-align: left;"><?= esc($row['item_numbers']); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-danger alert-dismissible"><strong>No Records Found.</strong></div>
        <?php endif; ?>
    </div>

    
</body>
</html>
