
            <section class="content">
                <?php
                if (is_array($reports))
                {
                    ?>
                    <div id="printable" class="box box-danger">
                        <table width="100%" id="reportTable" class="table table-striped table-hover">
                            <thead>
                                <?php
                                if ($app_name == 'JudgeWiseMatterListedDisposal') {
                                    if ($_POST['jCode'] == '0') { ?>
                                        <h3 style="text-align: center;">
                                            Hon'ble Judge wise Matters Listed and Disposed between 
                                            <strong><?= $_POST['from_date'] ?></strong> and 
                                            <strong><?= $_POST['to_date'] ?></strong>
                                        </h3>
                                    <?php } else { ?>
                                        <h3 style="text-align: center;">
                                            <strong><?= $reports['disposal'][0]['jname'] ?> </strong> Matters Listed and Disposed between 
                                            <strong><?= $_POST['from_date'] ?></strong> and 
                                            <strong><?= $_POST['to_date'] ?></strong>
                                        </h3>
                                    <?php } ?>
                                <tr>
                                    <th>Hon'ble Judge Name</th>
                                    <th>Listed <br/>Misc</th>
                                    <th>Listed <br/>Regular</th>
                                    <th>Listed <br/>Total</th>
                                    <th>Disposed <br/>Misc</th>
                                    <th>Disposed <br/>Regular</th>
                                    <th>Disposed <br/>Total</th>
                                </tr><?php } ?>
                            </thead>
                            <tbody>
                                <?php
                                $total_listed_misc_main = $total_listed_misc_conn = $total_listed_regular_main = $total_listed_regular_conn = 0;
                                $total_disposed_misc_main = $total_disposed_misc_conn = $total_disposed_regular_main = $total_disposed_regular_conn = 0;

                                $s_no = 1;
                                $other_disposal = $reports['other_disposal'][0]['other_disp'];
                                foreach ($reports['disposal'] as $result) {
                                    $total_listed_judge_wise = $result['listed_total_main'] + $result['listed_total_conn'];
                                    $total_disposed_judge_wise = $result['disposed_total_main'] + $result['disposed_total_conn'];

                                    $total_listed_misc_main += $result['listed_misc_main'];
                                    $total_listed_misc_conn += $result['listed_misc_conn'];
                                    $total_listed_regular_main += $result['listed_regular_main'];
                                    $total_listed_regular_conn += $result['listed_regular_conn'];

                                    $total_disposed_misc_main += $result['disposed_misc_main'];
                                    $total_disposed_misc_conn += $result['disposed_misc_conn'];
                                    $total_disposed_regular_main += $result['disposed_regular_main'];
                                    $total_disposed_regular_conn += $result['disposed_regular_conn'];
                                    ?>
                                    <tr>
                                        <td><?= $result['jname']; ?> (<?= $result['jcode']; ?>)</td>
                                        <td><?= $result['listed_misc_main']; ?> (+ <?= $result['listed_misc_conn']; ?>)</td>
                                        <td><?= $result['listed_regular_main']; ?> (+ <?= $result['listed_regular_conn']; ?>)</td>
                                        <td><?= $result['listed_total_main']; ?> (+ <?= $result['listed_total_conn']; ?>) = <?= $total_listed_judge_wise; ?></td>
                                        <td><?= $result['disposed_misc_main']; ?> (+ <?= $result['disposed_misc_conn']; ?>)</td>
                                        <td><?= $result['disposed_regular_main']; ?> (+ <?= $result['disposed_regular_conn']; ?>)</td>
                                        <td><?= $result['disposed_total_main']; ?> (+ <?= $result['disposed_total_conn']; ?>) = <?= $total_disposed_judge_wise; ?></td>
                                    </tr>
                                    <?php
                                    $s_no++;
                                } // foreach

                                $total_misc_listed = $total_listed_misc_main + $total_listed_misc_conn;
                                $total_regular_listed = $total_listed_regular_main + $total_listed_regular_conn;
                                $total_misc_disposed = $total_disposed_misc_main + $total_disposed_misc_conn;
                                $total_regular_disposed = $total_disposed_regular_main + $total_disposed_regular_conn;
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3">Other Disposal :</th>
                                    <td colspan="3"><?= $other_disposal; ?></td>
                                </tr>
                                <tr>
                                    <th colspan="3">Total Listed :</th>
                                    <td colspan="3"><?= $total_misc_listed + $total_regular_listed; ?></td>
                                    <th colspan="3">Total Disposed :</th>
                                    <td colspan="3"><?= $total_misc_disposed + $total_regular_disposed; ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php } ?>
            </section>
       