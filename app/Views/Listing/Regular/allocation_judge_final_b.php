<fieldset class="border p-2">
    <legend class="text-center text-primary font-weight-bold">CORAM</legend>
    <?php if (!empty($allocationData)): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th rowspan="2" scope="col" class="text-center align-middle">
                            <input type="checkbox" name="chkall" id="chkall" value="ALL" onClick="chkall1(this);"> All
                        </th>
                        <th rowspan="2" scope="col" class="text-center align-middle">Judges</th>
                        <th colspan="3" scope="col" class="text-center">Listed</th>
                        <th colspan="3" scope="col" class="text-center">To Be Listed</th>
                    </tr>
                    <tr>
                        <th scope="col">FD</th>
                        <th scope="col">Other</th>
                        <th scope="col">TOT</th>
                        <th scope="col">FD</th>
                        <th scope="col">Other</th>
                        <th scope="col">TOT</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $fd_t = 0;
                    $ors_t = 0;
                    $ttt_t = 0;
                    foreach ($allocationData as $row):
                        $details = $roster->getListingDetails($cldt, $p1, $row['jcd']);
                        $fd_t += $details['fd'];
                        $ors_t += $details['ors'];
                        $ttt_t += $details['ttt'];
                    ?>
                        <tr>
                            <td class="align-middle">
                                <input type="checkbox" id="chkeeed" name="chk" value="<?php echo $row['jcd'] . "|" . $row['id'] . "|" . $row['abbr']; ?>">
                                <?php echo $row['courtno'] . " " . $row['board_type_mb']; ?>
                            </td>
                            <td><?php echo str_replace(",", " & ", $row['jnm']); ?></td>
                            <td><?php echo $details['fd']; ?></td>
                            <td><?php echo $details['ors']; ?></td>
                            <td><?php echo $details['ttt']; ?></td>
                            <td>
                                <select class="form-control" name="fd_<?php echo $row['id']; ?>" id="fd_<?php echo $row['id']; ?>" onchange="calc_tot(this.id)">
                                    <?php for ($i = 0; $i < 301; $i++): ?>
                                        <option value="<?php echo $i; ?>" <?php echo ($i == 5) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </td>
                            <td>
                                <select class="form-control" name="or_<?php echo $row['id']; ?>" id="or_<?php echo $row['id']; ?>" onchange="calc_tot(this.id)">
                                    <?php for ($i = 0; $i < 301; $i++): ?>
                                        <option value="<?php echo $i; ?>" <?php echo ($i == 5) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </td>
                            <td>
                                <select class="form-control" name="tot_<?php echo $row['id']; ?>" id="tot_<?php echo $row['id']; ?>" disabled>
                                    <?php for ($i = 0; $i < 1001; $i++): ?>
                                        <option value="<?php echo $i; ?>" <?php echo ($i == 10) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </td>
                        </tr>
                    <?php 
                endforeach; ?>

                    <tr class="table-primary">
                        <td colspan="2" class="text-right">TOTAL</td>
                        <td><?php echo $fd_t; ?></td>
                        <td><?php echo $ors_t; ?></td>
                        <td><?php echo $ttt_t; ?></td>
                        <td colspan="3"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <center>No Records Found</center>
        <?php endif; ?>
</fieldset>