<option value="0">NO CORAM</option>
<?php foreach($judge_rs as $row_judge) { ?>
<option value="<?php echo $row_judge['id'].'~'.$row_judge['jcd'].'~'.$row_judge['board_type_mb']; ?>">
    <?php 
    if($row_judge['m_f']=='1')
        echo "M - ";
    else if($row_judge['m_f']=='2')
        echo "R - ";
    echo $row_judge['board_type_mb'].' - '.$row_judge['abbr'].' - '.$row_judge['bench_no'].' - '.$row_judge['jnm']; ?></option>
<?php } ?>