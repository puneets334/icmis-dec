<?php
foreach ($result_array as $ros12) {

    $this_result = new App\Models\ManagementReport\PendingModel;
    $result_array2 = $this_result->coram_wise_cat_tobe_list_table_get($ros12['jcode'], $list_dt, $board_type);

?> <div style="page-break-before:always;">
        <table class="table table-striped custom-table">
            <thead>
                <tr>
                    <th colspan="4" style="text-align: center;">
                        <?php echo $ros12['jname']; ?><br>
                        Coram Wise Cases to be list (with Category)
                        <BR>for dated : <?php echo date('d-m-Y', strtotime($list_dt)); ?>
                    </th>
                </tr>
            </thead>
        </table>
        <?php
        if (count($result_array2) > 0) {

        ?>
            <table class="table table-striped custom-table">

                <tr>
                    <td>SNo</td>
                    <td>HEAD</td>
                    <td>Court Dt (FX)</td>
                    <td>Court Dt (AW)</td>
                    <td>Comp Gen Fix Dt as per scheme</td>
                    <td>Comp Gen</td>
                    <td>TOTAL</td>
                </tr>
                <!--<tr>
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td>4</td>
                    <td>5</td>
                    <td>6</td>
                    <td>7</td>
                </tr>-->
                <?php
                $sno = 1;
                $t_fd_not_listed = 0;
                $t_aw_not_listed = 0;
                $t_imp_ia_not_listed = 0;
                $t_oth_not_listed = 0;
                $t_not_listed = 0;
                foreach ($result_array2 as $row) {
                    $sno1 = $sno % 2;
                    if ($sno == 1 or $sno == 2) { ?>
                        <tr>
                        <?php } else if ($sno >= 3 and $sno <= 10) {
                        ?>
                        <tr>
                        <?php } else { ?>
                        <tr>
                        <?php
                    }
                        ?>
                        <td><?php echo $sno++; ?></td>
                        <td><?php echo $row['sub_name1']; ?></td>
                        <td><?php $t_fd_not_listed += $row['fd_not_listed'];
                                                                        echo $row['fd_not_listed']; ?></td>
                        <td><?php $t_aw_not_listed += $row['aw_not_listed'];
                                                                        echo $row['aw_not_listed']; ?></td>
                        <td><?php $t_imp_ia_not_listed += $row['imp_ia_not_listed'];
                                                                        echo $row['imp_ia_not_listed']; ?></td>
                        <td><?php $t_oth_not_listed += $row['oth_not_listed'];
                                                                        echo $row['oth_not_listed']; ?></td>
                        <td><?php $t_not_listed += $row['not_listed'];
                                                                        echo $row['not_listed']; ?></td>
                        </tr>
                    <?php
                } //END OF WHILE LOOP
                    ?>
                    <tr>
                        <td colspan="2" align="right"> TOTAL </td>
                        <td><?php echo $t_fd_not_listed; ?></td>
                        <td><?php echo $t_aw_not_listed; ?></td>
                        <td><?php echo $t_imp_ia_not_listed; ?></td>
                        <td><?php echo $t_oth_not_listed; ?></td>
                        <td><?php echo $t_not_listed; ?></td>
                    </tr>
            </table>
            <br />
        <?php
        } else {
            echo "No Records Found";
        }
        ?>
    </div> <?php
        } //end of while loop judge
            ?>


<br><br><br><br><br>
</div>

<input name="prnnt1" type="button" id="prnnt1" value="Print">


<center></center>