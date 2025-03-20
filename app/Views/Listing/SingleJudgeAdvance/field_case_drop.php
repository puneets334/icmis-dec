<div style="text-align:center;">
    <?php

    $chk_avl = $SingleJudgeNominate->getPetResName($dno);
    if (empty($chk_avl))
    {
        echo "Record Not Available/Case Not listed";
    }
    else
    {
        $chk_printed = 0;
        $chk_drop_note = 1;
        $from_heardt = $chk_avl;
        $q_next_dt = $from_heardt['next_dt'];
        $from_dt = $from_heardt['from_dt'];
        $to_dt = $from_heardt['to_dt'];
        $partno = $from_heardt['clno'];
        $brd_slno = $from_heardt['brd_slno'];
       
        if ($from_heardt['pno'] == 2) {
            $pet_name = $from_heardt['pet_name'] . " AND ANR.";
        } else if ($from_heardt['pno'] > 2) {
            $pet_name = $from_heardt['pet_name'] . " AND ORS.";
        } else {
            $pet_name = $from_heardt['pet_name'];
        }
        if ($from_heardt['rno'] == 2) {
            $res_name = $from_heardt['res_name'] . " AND ANR.";
        } else if ($from_heardt['rno'] > 2) {
            $res_name = $from_heardt['res_name'] . " AND ORS.";
        } else {
            $res_name = $from_heardt['res_name'];
        }
        echo $pet_name . "Vs.<br/>" . $res_name;
       
        echo "<br/>Weeky Date From " . date('d-m-Y', strtotime($from_dt)) . " To " . date('d-m-Y', strtotime($to_dt));
        ?>
        <br /> <input type="hidden" size="10" name='next_dt' id='next_dt' value="<?= $q_next_dt ?>" readonly />
        <br /> <input type="hidden" size="10" name='from_dt' id='from_dt' value="<?= $from_dt ?>" readonly />
        <br /> <input type="hidden" size="10" name='to_dt' id='to_dt' value="<?= $to_dt ?>" readonly />
        <br>
        <?php
        $cl_result = $SingleJudgeNominate->isPrinted($from_dt, $to_dt);
        
        if ($cl_result == 1)
        {
            echo "<br/>";
            echo "<br/>";
            $ro_sq = $SingleJudgeNominate->checkCount($dno,$brd_slno,$from_dt, $to_dt);
            $chk_drop_note =  $ro_sq['count'];
            
          
        }
        if ($chk_drop_note == 0)
        {
          
            echo "<br/><font color=red>Do Not Drop, Advance List Published, Drop Note Required before Case Drop</font><br/>";
               
            ?>
            <input name="next_dt" type="hidden" id="next_dt1" value="<?php echo $q_next_dt; ?>">
            <input type="hidden" size="10" name='from_dt' id='from_dt1' value="<?= $from_dt ?>" readonly />
            <input type="hidden" size="10" name='to_dt' id='to_dt1' value="<?= $to_dt ?>" readonly />
            <input name="brd_slno" type="hidden" id="brd_slno" value="<?php echo $brd_slno; ?>">
            <input name="partno" type="hidden" id="partno1" value="<?php echo $partno; ?>">
            <input name="drop_diary" type="hidden" id="drop_diary1" value="<?php echo $dno; ?>">
            <input name="drop_rmk" type="text" id="drop_rmk" maxlength="75" size="75"> (Max 75)
           <br />
            <input name="drop_btn_note" type="button" id="drop_btn_note" value="Click to Drop">
            <?php
        }
        else
        {
            ?>
            <br />
            <input name="drop_btn" type="button" id="drop_btn" value="Click to Drop">
            <input name="drop_diary" type="hidden" id="drop_diary" value="<?php echo $dno; ?>">
            <input name="next_dt" type="hidden" id="next_dt" value="<?php echo $q_next_dt; ?>">
            <input type="hidden" size="10" name='from_dt' id='from_dt' value="<?= $from_dt ?>" readonly />
            <input type="hidden" size="10" name='to_dt' id='to_dt' value="<?= $to_dt ?>" readonly />
            <?php
        }
    }
    ?>
</div>

<script>


     
</script>