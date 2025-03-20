<?php
if (count($result_array) > 0) {
?>
    <div style="text-align: left;">
        <input name="prnnt1" type="button" id="prnnt1" value="Print">
    </div>
    <div id="prnnt" style="font-size:12px;">
        <H3>Cases Having No Coram</H3>
        <div style="font-size:12px;">
            <h3><?php
                if ($mainhead == 'M') {
                    echo " For Misc. Hearing";
                }
                if ($mainhead == 'F') {
                    echo " For Regular Hearing";
                }
                if ($board_type == 'J') {
                    echo " before Court";
                }
                if ($board_type == 'C') {
                    echo " before Chamber";
                }
                if ($board_type == 'R') {
                    echo " before Registrar";
                }
                ?></h3>
        </div>
        <table class="table table-striped table-bordered">

            <tr>
                <td>SNo</td>
                <td>Reg No/Diary</td>
                <td>Petitioner / Respondent</td>
                <td>Coram</td>
                <td>Heading</td>
                <td>Purpose</td>
                <td>Section/DA</td>

            </tr>
            <?php
            $sno = 1;
            $psrno = 1;
            foreach ($result_array as $row) {
                // pr($row);
                $sno1 = $sno % 2;
                $dno = $row['diary_no'];
                // $next_dt = $row['next_dt'];
                $purpose = $row['purpose'];
                $stagename = $row['stagename'];
                if ($row['pno'] == 2) {
                    $pet_name = $row['pet_name'] . " AND ANR.";
                } else if ($row['pno'] > 2) {
                    $pet_name = $row['pet_name'] . " AND ORS.";
                } else {
                    $pet_name = $row['pet_name'];
                }
                if ($row['rno'] == 2) {
                    $res_name = $row['res_name'] . " AND ANR.";
                } else if ($row['rno'] > 2) {
                    $res_name = $row['res_name'] . " AND ORS.";
                } else {
                    $res_name = $row['res_name'];
                }



                if ($sno1 == '1') { ?>
                    <tr>
                    <?php } else { ?>
                    <tr>
                    <?php
                }
                if ($row['reg_no_display']) {
                    $comlete_fil_no_prt = $row['reg_no_display'] . " @ " . substr_replace($row['diary_no'], '-', -4, 0);
                } else {
                    $comlete_fil_no_prt = substr_replace($row['diary_no'], '-', -4, 0);
                }


                    ?>
                    <td><?php echo $psrno++; ?></td>
                    <td><?php echo $comlete_fil_no_prt; ?></td>
                    <td><?php echo $pet_name . "<br/>Vs<br/>" . $res_name; ?></td>
                    <td><?php echo $row['coram']; ?></td>
                    <td><?php echo $row['stagename']; ?></td>
                    <td><?php echo $purpose; ?></td>
                    <td><?php echo $row['section_name'] . '<br>' . $row['da_name']; ?></td>
                    </tr>
                <?php
                $sno1++;
            }
                ?>
        </table>
    </div>
<?php
} else {
    echo "No Recrods Found";
}
?>

</div>

<div id="dv_res1"></div>