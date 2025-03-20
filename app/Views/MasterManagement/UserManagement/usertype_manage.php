<?php if (!empty($results)) { ?>
    <table id="result_main" class="table table-striped custom-table">
        <thead>
            <tr>
                <th>S.No.</th>
                <th>ID</th>
                <th>UserType Name</th>
                <th>Dispatch Flag</th>
                <th>Management Flag</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>

            <?php
            if (!empty($results)) {
                foreach ($results as $index => $result) { ?>
                    <tr>
                        <td><?= $index + 1;  ?></td>
                        <td><?= $result['id'];  ?></td>
                        <td><?= $result['type_name'];  ?></td>
                        <td><?= $result['disp_flag'];  ?></td>
                        <td><?= $result['mgmt_flag'];  ?></td>
                        <td class="text-center">
                            <input type="button" class="btn btn-primary" id="btnEdit<?php echo $result['id']; ?>" value="Edit" />
                            <input type="button" class="btn btn-primary" id="btnDelete<?php echo $result['id']; ?>" value="Remove" />
                        </td>
                    </tr>
            <?php  }
            } else {
            ?>
            <div class="sorry">SORRY, NO RECORD FOUND!!!</div>
            <?php } ?>
        </tbody>
    </table>
<?php
} else {
?>
    <div class="sorry">SORRY, NO RECORD FOUND!!!</div>
<?php } ?>
