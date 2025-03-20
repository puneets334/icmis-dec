<?php
if ($result != 0) {
?>
    <table>
        <tr>
            <th>SNo.</th>
            <th>ID</th>
            <th>UserDept Name</th>
            <th>Userside Flag</th>
            <th>Bounded User Section</th>
            <th>Action</th>
        </tr>
        <?php
        $sno = 1;
        foreach ($result as $select_type_row) {
        ?>
            <tr>
                <th><?php echo $sno; ?></th>
                <th><?php echo $select_type_row['id']; ?></th>
                <td><?php echo $select_type_row['dept_name']; ?></td>
                <td><?php echo $select_type_row['uside_flag']; ?></td>
                <td><?php echo $select_type_row['type_name']; ?></td>
                <td><input type="button" id="btnEdit<?php echo $select_type_row['id']; ?>" value="Edit" />
                    <input type="hidden" id="hd_utype_top_<?php echo $select_type_row['id']; ?>" value="<?php echo $select_type_row['utype_top']; ?>" />
                    <input type="button" id="btnDelete<?php echo $select_type_row['id']; ?>" value="Remove" />
                </td>
            </tr>
        <?php
            $sno++;
        }
        ?>
    </table>
<?php
} else {
?>
    <div class="sorry">SORRY, NO RECORD FOUND!!!</div>
<?php
}
echo "<>><<>><><>";
$get_Open_id;
