<div class="container">
<?php if (isset($result) && !empty($result)): ?>
    <?php $row = $result; ?>
        <h1 class="my-4">Update Details</h1>
            <input type="hidden" value="<?php if(isset($state)) echo $state ?>" id="hd_state">
            <input type="hidden" value="<?php if(isset($enroll)) echo $enroll ?>" id="hd_enr">
            <input type="hidden" value="<?php if(isset($year)) echo $year ?>" id="hd_en_yr">
            <input type="hidden" value="<?php if(isset($aor)) echo $aor ?>" id="hd_aor">
                <div>
                    <table class="table table-bordered">
                        <tr><td><b>Name</b></td><td><?= $row['name'] ?></td></tr>
                        <tr><td><b>Address</b></td><td><?= $row['caddress'] ?></td></tr>
                        <tr><td><b>City</b></td><td><?= $row['ccity'] ?></td></tr>
                        <tr><td><b>Mobile</b></td><td><input type='text' value="<?= $row['mobile'] ?>" maxlength="10" class="form-control" disabled /></td></tr>
                        <tr><td><b>Email</b></td><td><input type='text' value="<?= $row['email'] ?>" class="form-control" disabled /></td></tr>
                        <tr><td><b>AOR/NAOR</b></td>
                            <td>
                                <select class="form-control" disabled>
                                    <option value="Y" <?= $row['if_aor'] == 'Y' ? 'selected' : '' ?>>AOR</option>
                                    <option value="N" <?= $row['if_aor'] == 'N' ? 'selected' : '' ?>>NAOR</option>
                                </select>
                            </td>
                        </tr>
                        <tr><td><b>Death/Elevation/Resignation/Deletion/Block</b></td>
                            <td>
                                <select class="form-control" id='adv_dead'>
                                    <option value="Y" <?= $row['isdead'] == 'Y' ? 'selected' : '' ?>>YES</option>
                                    <option value="N" <?= $row['isdead'] == 'N' ? 'selected' : '' ?>>NO</option>
                                </select>
                            </td>
                        </tr>
                        <!-- <tr>
                            <td></td>
                            <td ><input type="button" value="SAVE" id="btnout" class="btn btn-primary" /></td>
                        </tr> -->
                        <!-- <tr><th colspan="2"></th></tr> -->
                         <tr>
                            <td></td>
                            <td>
                            <div class="row" style="float: inline-end;">
                                <div class="col-3">
                                <!-- <input type="button" class="btn btn-primary" value="SAVE" id="btnout" /> -->
                                <button  value="SAVE" id="btnout"  class="btn btn-primary" onclick="save_data()">SAVE</button>
                                </div>
                            </div>
                            </td>
                         </tr>
                    </table>
                </div>
            <?php elseif (isset($error)): ?>
                <div class="sorry"><?= $error ?></div>
            <?php endif; ?>
    </div>