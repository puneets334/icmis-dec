<?php if($heading != 'F') { ?>
    <option value="">Select Sub Heading</option>   
    <?php
    foreach($subheading as $row) {
    ?>
    <option value="<?php echo $row['stagecode']; ?>"><?php echo $row['stagename']; ?></option>   
    <?php
    }
} else if($heading == 'F') { ?>
    <option value="">Select Sub Heading</option>
    <?php foreach($subheading as $row_cate){ ?>
    <option value="<?php echo $row_cate['submaster_id']; ?>"><?php echo $row_cate['sub_name1'].'-'.$row_cate['sub_name2'].'-'.$row_cate['sub_name3'].'-'.$row_cate['sub_name4']; ?></option>   
        <?php
    }
} ?>