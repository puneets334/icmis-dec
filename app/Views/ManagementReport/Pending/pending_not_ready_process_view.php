<?php
if (!empty($res)) {
?>
    <div class="table-responsive" id="dv_content1">
        <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
            <center><h4 class="basic_heading">Pending Not Ready/Updation Awaited Matters Report Data</h4></center>
        </div>
        <table id="reportTable1" class="table table-striped table-bordered custom-table">
            <thead>
                <tr>
                    <th style="width:15%;" ><b>Head</b></th>
                    <th style="width:50%;"><b>Status</b></th>
                    <th style="width:35%;"><b>Main</b></th>
                    <th style="width:35%;"><b>Connected</b></th>
                    <th style="width:35%;"><b>Total</b></th>
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
                        <td><b>Not Ready</b></td>
                        <td><?= $data['misc_not_ready_main'] ?></td>
                        <td><?= $data['misc_not_ready_conn'] ?></td>
                        <td><?= $data['misc_not_ready'] ?></td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <td><b>Updation Awaited</b></td>
                        <td><?= $data['misc_updation_awaited_main'] ?></td>
                        <td><?= $data['misc_updation_awaited_conn'] ?></td>
                        <td><?= $data['misc_updation_awaited'] ?></td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <td><b>Chamber</b></td>
                        <td><?= $data['chamber_not_ready_main'] ?></td>
                        <td><?= $data['chamber_not_ready_conn'] ?></td>
                        <td><?= $data['chamber_not_ready'] ?></td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <td><b>Registrar</b></td>
                        <td><?= $data['registrar_not_ready_main'] ?></td>
                        <td><?= $data['registrar_not_ready_conn'] ?></td>
                        <td><?= $data['registrar_not_ready'] ?></td>
                    </tr>


                    <tr>
                        <td>&nbsp;</td>
                        <td><b>Total (Misc.)</b></td>
                        <td><b><?= $tot_misc_main ?></b></td>
                        <td><b><?= $tot_misc_conn ?></b></td>
                        <td><b><?= $tot_misc_total ?></b></td>
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
                        <td><b>Not Ready </b></td>
                        <td><?= $data['final_not_ready_main'] ?></td>
                        <td><?= $data['final_not_ready_conn'] ?></td>
                        <td><?= $data['final_not_ready'] ?></td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <td><b>Updation Awaited</b></td>
                        <td><?= $data['final_updation_awaited_main'] ?></td>
                        <td><?= $data['final_updation_awaited_conn'] ?></td>
                        <td><?= $data['final_updation_awaited'] ?></td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <td><b>Total (Regular)</b></td>
                        <td><b><?= $tot_reg_main ?></b></td>
                        <td><b><?= $tot_reg_conn ?></b></td>
                        <td><b><?= $tot_reg_total ?></b></td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <td><b>Grand Total</b></td>
                        <td><b><?= $grand_main ?></b></td>
                        <td><b><?= $grand_conn ?></b></td>
                        <td><b><?= $grand_total ?></b></td>
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