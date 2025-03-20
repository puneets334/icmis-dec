<?php //echo $title; 

if (count($get_list_section) > 0) {
?>
    <table class="table table-striped custom-table">

        <tr>
            <td width="5%">SrNo.</td>
            <td width="5%">Item No.</td>
            <td width="7%">Diary No</td>
            <td width="15%">Reg No.</td>
            <td width="15%">Petitioner / Respondent</td>
            <td width="15%">Advocate</td>
            <td width="5%">Section Name</td>
            <td width="10%">DA Name</td>
            <td width="20%">Statutory Info.</td>
            <td width="7%">Listed Before</td>
            <td width="8%">Purpose</td>
            <td width="10%">Trap</td>
        </tr>
        <?php
        $sno = 1;

        foreach ($get_list_section as $ro) {
            $remark = $ro['remark'];
            $sno1 = $sno % 2;
            $dno = $ro['diary_no'];
            $diary_no_rec_date = date('d-m-Y', strtotime($ro['diary_no_rec_date']));
            $active_fil_dt = date('d-m-Y', strtotime($ro['active_fil_dt']));
            $conn_no = $ro['conn_key'];
            $m_c = "";
            if ($conn_no == $dno) {
                $m_c = "Main";
            }
            if ($conn_no != $dno and $conn_no > 0) {
                $m_c = "Conn.";
            }
            $coram = $ro['coram'];
            if ($ro['board_type'] == "J") {
                $board_type1 = "Court";
            }
            if ($ro['board_type'] == "C") {
                $board_type1 = "Chamber";
            }
            if ($ro['board_type'] == "R") {
                $board_type1 = "Registrar";
            }
            $filno_array = explode("-", $ro['active_fil_no']);

            if (empty($ro['reg_no_display'])) {
                $fil_no_print = "Unregistred";
            } else {
                $fil_no_print = $ro['reg_no_display'];
            }
            if ($sno1 == '1') { ?>
                <tr id="<?php echo $dno; ?>">
                <?php } else { ?>
                <tr id="<?php echo $dno; ?>">
                <?php
            }
            $padvname = "";
            $radvname = "";
            $impldname = "";               
                ?>
                <td><?php echo $sno; ?></td>
                <td><?php echo $ro['brd_slno'] . "<br>" . $m_c; ?></td>
                <td><?php echo substr_replace($ro['diary_no'], '/', -4, 0); ?></td> <!-- ."<br>Ddt ".$diary_no_rec_date //-->
                <td><?php echo $fil_no_print . "<br>Rdt " . $active_fil_dt; ?></td>
                <td><?php echo $pet_name . "<br/>Vs<br/>" . $res_name; ?></td>
                <td><?php echo str_replace(",", ", ", trim($padvname, ",")) . "<br/>Vs<br/>" . str_replace(",", ", ", trim($radvname, ",")) . " ", str_replace(",", ", ", trim($impldname, ",")); ?></td>
                <td><?php echo $ro['section_name']; ?></td>
                <td><?php echo $ro['name']; ?></td>
                <td><?php echo $remark ?></td>
                <td><?php echo $board_type1 ?></td>
                <td><?php echo $ro['purpose'] ?></td>
                <td></td>
                </tr>
            <?php
            $sno++;
        }
            ?>
    </table>
<?php

} else {
    if ($listtype == 'A')
        echo "Advance List for dated $list_dt is not published yet";
    else
        echo "Draft List for dated $list_dt is not published yet";
}
?>
</div>
<br>
<br>
<br>
<input name="prnnt1" type="button" id="prnnt1" value="Print">