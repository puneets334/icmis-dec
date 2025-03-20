<select name="subcat[]" id="subcat" style="width:700px" multiple="multiple" size="4" onChange="getsubcat2(this.value)">
    <option value="all" selected="selected">ALL Sub Category</option>
    <?php foreach ($result_array as $row) {
        if ($row['main_head'] == 'Y') {
            $color = 'color:GREEN;';
        } else {
            $color = '';
        }
    ?>
        <option value="<?php echo $row['subcode1'] . "|" . $row['subcode2'] . "|" . $row['subcode3']; ?>"
            style="<?php echo $color; ?> font-size:10px">
            <?php echo $row['sub_name1'] . ' > ' . $row['sub_name2'] . ' > ' . $row['sub_name4']; ?>
        </option>
    <?php
    } ?>
</select>