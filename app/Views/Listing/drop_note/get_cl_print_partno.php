
<?php if(!empty($part_numbers)) { ?>
    <option value="0" selected>SELECT</option>
    <?php foreach($part_numbers as $part) { ?>
        <option value="<?php echo $part['clno']; ?>" ><?php echo $part['clno']; ?></option>
    <?php }
} else { ?>
    <option value="-1" selected>EMPTY</option>
<?php } ?>