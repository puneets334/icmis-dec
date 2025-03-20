<?php if(count($select_type) > 0){?>
<table class="table table-striped custom-table">
    <thead>
        <tr>
            <th>SNo.</th>
            <th>UserType Name</th>
            <th>Lower Range</th>
            <th>Upper Range</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sno = 1;
        foreach ($select_type as $select_type_row) {
        ?>
            <tr>
                <td><?php echo $sno; ?></td>
                <td><?php echo $select_type_row['type_name']; ?></td>
                <td><?php echo $select_type_row['low']; ?></td>
                <td><?php echo $select_type_row['up']; ?></td>
                <td><input type="button" id="btnEdit<?php echo $select_type_row['id']; ?>" value="Edit" />
                    <input type="button" id="btnDelete<?php echo $select_type_row['id']; ?>" value="Remove" />
                </td>
            </tr>
        <?php
            $sno++;
        }
        ?>
    </tbody>
</table>
<?php } ?>

