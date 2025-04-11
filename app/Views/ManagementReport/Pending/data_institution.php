<?php

?>
<html>

<head>
    <title>Untitled Document</title>
</head>


<body>

    <?php
    $rs = $model->get_institution_report($from_date, $to_date, $rpt_type);
    $tot_row = $rs;

    // -------------------------------------Process Start from here ----------------------------------------------------------
    if (!empty($tot_row)) {

    ?>
        <br><br>

        <div id="prnTable" align="center">

            <table class="table table-bordered table-striped table-hover" cellpadding="1" cellspacing="0">

                <tr>
                    <th colspan=13>
                        <center>
                            <font color="blue">
                                <?= $rpt_type ?> Report between :
                                <?php echo date('d/m/Y', strtotime($from_date)) . ' to ' . date('d/m/Y', strtotime($to_date)); ?>
                            </font>
                        </center>



                    </th>
                </tr>
                <tr>
                    <th>Sno</th>

                    <th colspan="1">Date</th>
                    <th colspan="1">Case Type</th>
                    <?php
                    ?>

                    <?php
                    ?>
                    <?php if ($rpt_type == 'defect') { ?> <th>Defective(IA)</th>
                        <th>Defective(Without IA)</th>
                        <th>Total Defective</th>
                        <th>Registered</th><?php } ?>
                    <th colspan="1">Total</th>
                    <?php
                    $i = 1;
                    $total = 0;
                    $tot = 0;
                    $defect_tot = 0;
                    $filed_tot = 0;
                    $not_filed_tot = 0;
                    $defect_ia_tot = 0;
                    $not_defect_tot = 0;

                    foreach ($rs as $row) {
                        //pr($row);


                    ?>
                <tr style='color:#0000FF'>
                    <td align=center><?php echo $i; ?></td>
                    <?php if ($rpt_type == 'registration' || $rpt_type == 'institution' || $rpt_type == 'filing' || $rpt_type == 'refiling') {
                    ?>
                      <td align=right><?php echo date('d/m/Y', strtotime($row['fil_dt'])); ?></td>

                        <td><?php echo $row['short_description']; ?></td>
                    <?php } ?>
                    <?php

                        if ($rpt_type == 'defect') { ?>
                        <td align=right><?php echo "<a href= JavaScript:newPopup('show_case_for_institution.php?ia=Y&defect=Y&from_date=" . $from_date . "&to_date=" . $to_date . "&rpt_type=" . $rpt_type . "&fil_dt=" . $row['fil_dt'] . "')>" . $row['defect_ia'] . "</a>"; ?>
                        </td>
                        <td align=right>
                            <?php echo "<a href= JavaScript:newPopup('show_case_for_institution.php?ia=N&&defect=Y&from_date=" . $from_date . "&to_date=" . $to_date . "&rpt_type=" . $rpt_type . "&fil_dt=" . $row['fil_dt'] . "')>" . ($row['defect'] - $row['defect_ia']) . "</a>"; ?>
                        </td>
                        <td align=right><?php echo "<a href= JavaScript:newPopup('show_case_for_institution.php?ia=all&defect=all&from_date=" . $from_date . "&to_date=" . $to_date . "&rpt_type=" . $rpt_type . "&fil_dt=" . $row['fil_dt'] . "')>" . $row['defect'] . "</a>"; ?> </td>
                        <td align=right><?php echo $row['not_defect']; ?></td>


                <?php
                        }
                        echo "<td align=right><b>" . $row['cnt'] . "</b></td>
                       
					</tr>";

                        $tot = $tot + $row['cnt'];

                        if ($rpt_type == 'defect') {
                            $defect_tot = $defect_tot + $row['defect'];
                            $not_defect_tot = $not_defect_tot + $row['not_defect'];
                            $defect_ia_tot = $defect_ia_tot + $row['defect_ia'];
                        }

                        if ($rpt_type == 'registration' || $rpt_type == 'institution') {
                            $filed_tot = $filed_tot + $row['filed'];

                            $not_filed_tot = $not_filed_tot + $row['not_filed'];
                        }




                        $i++;
                    }
                    if ($rpt_type == 'filing' || $rpt_type == 'registration' || $rpt_type == 'institution' || $rpt_type == 'refiling')  $colspan = " colspan=3 ";
                    else   $colspan = " colspan=2 ";

                    echo "<tr style='color:#0000FF'>
					<td " . $colspan . "><b>Grand Total</b></td> ";
                    if ($rpt_type == 'defect')
                        echo "<td align=right><b>" . $defect_ia_tot . "</b></td><td align=right><b>" . ($defect_tot - $defect_ia_tot) . "</b></td><td align=right><b>" . $defect_tot . "</b></td><td align=right><b>" . $not_defect_tot . "</b></td>";

                    echo "<td  align=right><b>" . $tot . "</b></td>					</tr>";

                ?>
            </table>
        </div>
        <center><input type="button" id="print1" value="PRINT"></center>
    <?php

    } else
        echo "<center><h2>Record Not Found</h2></center>";
    ?>

</body>

</html>