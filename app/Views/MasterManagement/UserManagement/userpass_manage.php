    <input type="hidden" id="hd_id_for_userpass_reset" value="<?php echo $newdata['0'] ?? ''; ?>">
    <div class="table-responsive">
        <table id="users" class="table table-striped custom-table">
            <tr class="font-weight-bold">
                <td>Name</td>
                <td><?php echo $newdata['1'] ?? ''; ?></td>
            </tr>
            <tr class="font-weight-bold">
                <td>Department</td>
                <td><?php echo $newdata['3'] ?? ''; ?></td>
            </tr>
            <tr class="font-weight-bold">
                <td>Section</td>
                <td><?php echo $newdata['5'] ?? ''; ?></td>
            </tr class="font-weight-bold">
            <tr class="font-weight-bold">
                <td>Type</td>
                <td><?php echo $newdata['4'] ?? ''; ?></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><input type="button" value="RESET" onclick="resetMyPass_um()" /></td>
            </tr>
        </table>
    </div>
    <div id="2result"></div>