<div class="col-12 col-sm-12 col-md-12 col-lg-12">
    <?php if (count($trapData) > 0): ?>
    <div class="table-responsive">
        <table id="tblCasesForReceive" class="table table-striped table-hover centerview">
            <thead>
                <tr>
                    <th><b>SNo.</b></th>
                    <th><b>Diary No.</b></th>
                    <th><b>Dispatch By</b></th>
                    <th><b>Dispatch On</b></th>
                    <th><b>Remarks</b></th>
                    <th><b>Dispatch To</b></th>
                    <th><b>Receive By</b></th>
                    <th><b>Receive On</b></th>
                    <th><b>Completed On</b></th>
                </tr>
            </thead>
            <tbody>
                <?php $sno = 1; ?>
                    <?php foreach ($trapData as $row): ?>
                        <tr>
                            <td><?= $sno; ?></td>
                            <td><?= substr($row['diary_no'], 0, -4) . '/' . substr($row['diary_no'], -4); ?></td>
                            <td><?= $row['d_by_name']; ?></td>
                            <td>
                                <?php 
                                if (!empty($row['disp_dt']) && $row['disp_dt'] != '0000-00-00 00:00:00') {
                                    echo date('d-m-Y h:i:s A', strtotime($row['disp_dt']));
                                }
                                ?>
                            </td>
                            <td><?= $row['remarks']; ?></td>
                            <td><?= $row['d_to_name']; ?></td>
                            <td><?= $row['r_by_name']; ?></td>
                            <td>
                                <?php 
                                if (!empty($row['rece_dt']) && $row['rece_dt'] != '0000-00-00 00:00:00') {
                                    echo date('d-m-Y h:i:s A', strtotime($row['rece_dt']));
                                }
                                ?>
                            </td>
                            <td>
                                <?php 
                                if (!empty($row['comp_dt']) && $row['comp_dt'] != '0000-00-00 00:00:00') {
                                    echo date('d-m-Y h:i:s A', strtotime($row['comp_dt']));
                                }
                                ?>
                                <?= $row['other'] != 0 ? '<br>' . $row['o_name'] : ''; ?>
                            </td>
                        </tr>
                    <?php $sno++; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <?php echo '<div class="nofound">SORRY NO RECORD FOUND</div>' ?>
    <?php endif; ?>
</div>