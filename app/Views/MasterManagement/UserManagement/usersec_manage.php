<?php if (!empty($results)) { ?>
    <table id="result_main" class="table table-striped custom-table">
        <thead>
            <tr>
                <th>S.No.</th>
                <th>ID</th>
                <th>User Section Name</th>
                <th>If DA</th>
                <th>Description</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>

            <?php
            //if (!empty($results)) {
            foreach ($results as $index => $result) { ?>
                <tr>
                    <td><?= $index + 1;  ?></td>
                    <td><?= $result['id'];  ?></td>
                    <td><?= $result['section_name'];  ?></td>
                    <td><?php if ($result['isda'] == 'Y') echo "YES";
                        else echo "NO"; ?></td>
                    <td><?= $out = strlen($result['description']) > 75 ? substr($result['description'], 0, 75) . "..." : $result['description']; ?>
                    </td>
                    <td class="text-center">
                        <input type="button" class="btn btn-primary" id="btnEdit<?php echo $result['id']; ?>" value="Edit" />
                        <input type="button" class="btn btn-primary" id="btnDelete<?php echo $result['id']; ?>" value="Remove" />
                    </td>
                </tr>
            <?php  } ?>
        </tbody>
    </table>
<?php
} else {
?>
    <div class="sorry">SORRY, NO RECORD FOUND!!!</div>
<?php } ?>
