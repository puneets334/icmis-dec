
<?php if(!empty($judge_list)) { ?>
    <option value="0" selected>SELECT</option>
    <?php foreach($judge_list as $judge) { ?>
        <option value="<?php echo $judge["judges"]."|".$judge["roster_id"]; ?>" ><?php echo $judge['jnm']; ?></option>
    <?php }
} else { ?>
    <option value="0" selected>EMPTY</option>
<?php } ?>