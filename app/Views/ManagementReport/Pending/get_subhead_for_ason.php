<select name="subhead[]" id="subhead" multiple="multiple" size="6" style="width:600px">
    <option value="all" selected="selected">ALL Subhead</option>
    <?php
    foreach ($result_array as $row) {
        $text = $row['stagename'];
        $value = $row['stagecode'];
        echo "<option value='" . $value . "' style='font-size:12px'>" . $text . "</option>";
    }
    ?>
</select>