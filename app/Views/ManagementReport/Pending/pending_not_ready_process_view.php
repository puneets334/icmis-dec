<?php
if (!empty($res)) {
?>
    <div class="table-responsive" id="dv_content1">
        <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
            <center><h4 class="basic_heading">Pending Not Ready/Updation Awaited Matters Data Report</h4></center>
        </div>
        <table id="reportTable1" class="table table-striped custom-table">
            <thead>
                <tr>
                    <th style="width:15%;">Head</th>
                    <th style="width:50%;">Status</th>
                    <th style="width:35%;">Main</th>
                    <th style="width:35%;">Connected</th>
                    <th style="width:35%;">Total</th>
                </tr>
            </thead>
            <tbody>


                <?php

                foreach ($res as $data) {

                    $tot_misc_main = $data['misc_not_ready_main'] + $data['misc_updation_awaited_main'] + $data['chamber_not_ready_main'];
                    $tot_misc_main = $tot_misc_main + $data['registrar_not_ready_main'];

                    $tot_misc_conn = $data['misc_not_ready_conn'] + $data['misc_updation_awaited_conn'] + $data['chamber_not_ready_conn'];
                    $tot_misc_conn = $tot_misc_conn + $data['registrar_not_ready_conn'];

                    $tot_misc_total = $data['misc_not_ready'] + $data['misc_updation_awaited'] + $data['chamber_not_ready'];
                    $tot_misc_total = $tot_misc_total + $data['registrar_not_ready'];




                    $tot_reg_main = $data['final_not_ready_main'] + $data['final_updation_awaited_main'];
                    $tot_reg_conn = $data['final_not_ready_conn'] + $data['final_updation_awaited_conn'];
                    $tot_reg_total = $data['final_not_ready'] + $data['final_updation_awaited'];

                    $grand_main = $tot_misc_main + $tot_reg_main;
                    $grand_conn = $tot_misc_conn + $tot_reg_conn;
                    $grand_total = $tot_misc_total + $tot_reg_total;
                ?>

                    <tr>
                        <td><b>Miscellaneous</b></td>
                        <td>Not Ready</td>
                        <td><?= $data['misc_not_ready_main'] ?></td>
                        <td><?= $data['misc_not_ready_conn'] ?></td>
                        <td><?= $data['misc_not_ready'] ?></td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <td>Updation Awaited</td>
                        <td><?= $data['misc_updation_awaited_main'] ?></td>
                        <td><?= $data['misc_updation_awaited_conn'] ?></td>
                        <td><?= $data['misc_updation_awaited'] ?></td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <td>Chamber</td>
                        <td><?= $data['chamber_not_ready_main'] ?></td>
                        <td><?= $data['chamber_not_ready_conn'] ?></td>
                        <td><?= $data['chamber_not_ready'] ?></td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <td>Registrar</td>
                        <td><?= $data['registrar_not_ready_main'] ?></td>
                        <td><?= $data['registrar_not_ready_conn'] ?></td>
                        <td><?= $data['registrar_not_ready'] ?></td>
                    </tr>


                    <tr>
                        <td>&nbsp;</td>
                        <td>Total (Misc.)</td>
                        <td><?= $tot_misc_main ?></td>
                        <td><?= $tot_misc_conn ?></td>
                        <td><?= $tot_misc_total ?></td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>


                    <tr>
                        <td><b>Regular</b></td>
                        <td>Not Ready</td>
                        <td><?= $data['final_not_ready_main'] ?></td>
                        <td><?= $data['final_not_ready_conn'] ?></td>
                        <td><?= $data['final_not_ready'] ?></td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <td>Updation Awaited</td>
                        <td><?= $data['final_updation_awaited_main'] ?></td>
                        <td><?= $data['final_updation_awaited_conn'] ?></td>
                        <td><?= $data['final_updation_awaited'] ?></td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <td>Total (Regular)</td>
                        <td><?= $tot_reg_main ?></td>
                        <td><?= $tot_reg_conn ?></td>
                        <td><?= $tot_reg_total ?></td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <td><b>Grand Total</b></td>
                        <td><?= $grand_main ?></td>
                        <td><?= $grand_conn ?></td>
                        <td><?= $grand_total ?></td>
                    </tr>

                <?php } ?>
            </tbody>
        </table>
   

    </div>
    <div style="text-align: center;">
            <input name="print1" type="button" id="print1" value="Print">
        </div>
<?php } else {
?>
    <label class="text-danger" style="margin-left:32%; margin-top: 5%;">No Record(s) found!!</label>
<?php
} ?>