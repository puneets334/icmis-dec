
<?php if(!empty($listing_dates)) { ?>
    <option value="0" selected>SELECT</option>
    <?php foreach($listing_dates as $date) { ?>
        <option value="<?php echo $date['next_dt']; ?>" ><?php echo date("d-m-Y", strtotime($date['next_dt'])); ?></option>
    <?php }
} else { ?>
    <option value="0" selected>EMPTY</option>
<?php } ?>