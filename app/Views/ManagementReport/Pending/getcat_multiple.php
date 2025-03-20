<select name="cat[]" id="cat" onChange="getsubcat()" style="width:700px" multiple="multiple" size="4" class="form-control">
    <option value="all" selected="selected">ALL Category</option>
    <?php 
    $x = 1;
    $temp_head = '';
    foreach($getcat_multiple as $row){
    
    // foreach($getcat_multiple as $row) {
        if ($row['main_head'] == 'Y'){
            $color = 'color:GREEN;';
        }
        else{
            $color = '';
        }
            
    ?>
    <option value="<?php echo $row['subcode1'] . "|" . $row['subcode2']; ?>"
        style="<?php echo $color; ?> font-size:10px"><?php echo $row['sub_name1'] . ' > ' . $row['sub_name4']; ?>
    </option>
    <?php
     $temp_head = $row['subcode1'];
        $x++;
    }
    ?>
</select>