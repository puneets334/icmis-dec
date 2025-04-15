<style>
    table thead tr th {
    background: #6f6a6a;
}
</style>
<?php if (sizeof($data) > 0) { ?>
    <br><br><div align="left"><input name="cmdPrnRqs2" type="button" id="cmdPrnRqs2" onClick="CallPrint('prnTable');" value="PRINT"></div>
    <div class="table-responsive" id="prnTable">
        <!-- <table id="customers" class="table table-striped custom-table"> -->
        <table cellpadding="1" cellspacing="0" border="1" align="left">
            <thead>
            <tr>
            <td colspan="21" align=center><font color="blue" size="+1">DA WISE RED ORANGE GREEN YELLOW CASES DATE :<?php echo date('d-m-Y'); ?></font></td></tr>
            <tr style="background:#6F6A6A;">
                    <th rowspan="2">Sno</th>
                    <th rowspan="2">DA NAME</th>
                    <th style="color:#FF0000" colspan="3">RED</th>
                    <th style="color:#FFA500" colspan="3">ORANGE</th>
                    <th style="color:#00FF00" colspan="3">GREEN</th>
                    <th style="color:#FFFF00" colspan="3">Yellow <br>(Conditional Listing) </th>
                    <th colspan="3"><b>TOTAL</b></th>
                </tr>

                <tr>
                    <th>Misc.</th>
                    <th>Regular</th>
                    <th><b>Total</b></th>
                    <th>Misc.</th>
                    <th>Regular</th>
                    <th><b>Total</b></th>
                    <th>Misc.</th>
                    <th>Regular</th>
                    <th><b>Total</b></th>
                    <th>Misc.</th>
                    <th>Regular</th>
                    <th><b>Total</b></th>
                    <th>Misc.</th>
                    <th>Regular</th>
                    <th><b>Total</b></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                $total = 0;
                $red = 0;
                $red_MH = 0;
                $red_FH = 0;
                $orange = 0;
                $orange_MH = 0;
                $orange_FH = 0;
                $green = 0;
                $green_MH = 0;
                $green_FH = 0;
                $yellow = 0;
                $yellow_mh = 0;
                $yellow_fh = 0;
                $total_mh = 0;
                $total_fh = 0;
                $daname = '';
                // pr($data);
                foreach ($data as $row) {

                    if ($row['name'] == '') {

                        $daname = 'NOT ALLOTED CASES';
                        $red_not_alloted = $row['red'];
                        $red_MH_not_alloted = $row['red_mh'];
                        $red_FH_not_alloted = $row['red_fh'];

                        $orange_not_alloted = $row['orange'];
                        $orange_MH_not_alloted = $row['orange_mh'];
                        $orange_FH_not_alloted = $row['orange_fh'];

                        $green_not_alloted = $row['green'];
                        $green_MH_not_alloted = $row['green_mh'];
                        $green_FH_not_alloted = $row['green_fh'];
                        // $green_not_MFLS_not_alloted = $row['green_not_MFLS'];
                        $yellow = $row['yellow'];
                        $yellow_mh = $row['yellow_mh'];
                        $yellow_fh = $row['yellow_fh'];
                        $total = $row['tot'];
                        $total_mh = $row['tot_mh'];
                        $total_fh = $row['tot_fh'];
                    } else {
                        $daname = $row['name'];


                        if ($row['dacode'] != '1') {
                            $red = $red + $row['red'];
                        }

                        $red_MH = $red_MH + $row['red_mh'];

                        $red_FH = $red_FH + $row['red_fh'];

                        $orange = $orange + $row['orange'];
                        $orange_MH = $orange_MH + $row['orange_mh'];
                        $orange_FH = $orange_FH + $row['orange_fh'];

                        $green = $green + $row['green'];
                        $green_MH = $green_MH + $row['green_mh'];
                        $green_FH = $green_FH + $row['green_fh'];

                        $yellow = $yellow + $row['yellow'];
                        $yellow_mh = $yellow_mh + $row['yellow_mh'];
                        $yellow_fh = $yellow_fh + $row['yellow_fh'];

                        $total = $total + $row['tot'];
                        $total_mh = $total_mh + $row['tot_mh'];
                        $total_fh = $total_fh + $row['tot_fh'];
                    }



                    if ($row['name'] != '' && $row['dacode'] != '1') {
                        $i++;

                        echo "<tr style='color:#0000FF'>
					<td>" . $i . "</td>
					<td>" . $row['section_name'] . " / <font color=red>" . $daname . "</font> / " . $row['empid'] . "</td>
					<td style='background:#FAEBF5;' align=right>" . $row['red_mh'] . "</td>
					<td style='background:#FAEBF5;' align=right>" . $row['red_fh'] . "</td>
					<td style='background:#FAEBF5;' align=right><b>" . $row['red'] . "</b></td>
					
					
					<td style='background:#fee9cc;' align=right>" . $row['orange_mh'] . "</td>
					<td style='background:#fee9cc;' align=right>" . $row['orange_fh'] . "</td>	
					<td style='background:#fee9cc;' align=right><b>" . $row['orange'] . "</b></td>
					
					<td style='background:#CCFFCC;' align=right>" . $row['green_mh'] . "</td>
					<td style='background:#CCFFCC;' align=right>" . $row['green_fh'] . "</td>
					<td style='background:#CCFFCC;' align=right><b>" . $row['green'] . "</b></td>
					<td style='background:#FFFF00;' align=right><b>" . $row['yellow_mh'] . "</b></td>
					<td style='background:#FFFF00;' align=right><b>" . $row['yellow_fh'] . "</b></td>
					<td style='background:#FFFF00;' align=right><b>" . $row['yellow'] . "</b></td>
					<td align=right><b>" . ($row['red_mh'] + $row['orange_mh'] + $row['green_mh'] + $row['yellow_mh']) . "</b></td>
					<td align=right><b>" . ($row['red_fh'] + $row['orange_fh'] + $row['green_fh'] + $row['yellow_fh']) . "</b></td>
					<td align=right><b>" . ($row['red'] + $row['orange'] + $row['green'] + $row['yellow']) . "</b></td>
					</tr>";
                    }
                }

                echo "<tr><td colspan=2 align=right>TOTAL</td>
            <td style='background:#FAEBF5;' align=right>" . $red_MH . "</td>
			<td style='background:#FAEBF5;' align=right>" . $red_FH . "</td>
			<td style='background:#FAEBF5;' align=right><b>" . $red . "</b></td>
			
			
			<td style='background:#fee9cc;' align=right>" . $orange_MH . "</td>
			<td style='background:#fee9cc;' align=right>" . $orange_FH . "</td>
			<td style='background:#fee9cc;' align=right><b>" . $orange . "</b></td>
			
			
			<td style='background:#CCFFCC;' align=right>" . $green_MH . "</td>
			<td style='background:#CCFFCC;' align=right>" . $green_FH . "</td>
			<td style='background:#CCFFCC;' align=right><b>" . $green . "</b></td>
			<td style='background:#FFFF00;' align=right><b>" . $yellow_mh . "</b></td>
			<td style='background:#FFFF00;' align=right><b>" . $yellow_fh . "</b></td>
			<td style='background:#FFFF00;' align=right><b>" . $yellow . "</b></td>
			<td align=right><b>" . ($red_MH + $orange_MH + $green_MH + $yellow_mh) . "</b></td>
			<td align=right><b>" . ($red_FH + $orange_FH + $green_FH + $yellow_fh) . "</b></td>
			<td align=right><b>" . ($red + $orange + $green + $yellow) . "</b></td>
			</tr>";

                ?>
            </tbody>
        </table>
    </div>
<?php
} else {
    echo "<center><h2>Record Not Found</h2></center>";
}
?>


<script>
    $("#customers").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        "buttons": ["excel", "pdf"]
    });
</script>