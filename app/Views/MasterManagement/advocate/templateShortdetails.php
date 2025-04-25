<div class="container mt-4">
    <?php if (!empty($bar)): ?>
        <input type="hidden" value="<?php if (isset($enroll)) echo $enroll ?>" id="hd_enr">
        <input type="hidden" value="<?php if (isset($year)) echo $year ?>" id="hd_en_yr">
        <input type="hidden" value="<?php if (isset($aor)) echo $aor ?>" id="hd_aor">
        <div class="row_">
            <div class="card" style=" margin: auto; ">
                <div class="tab-content table-responsive cardsss">
                    <table class="table table-bordered">
                        <thead>
                            <tr style="color: crimson;">
                                <th><b>Field</b></th>
                                <th><b>Value</b></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><b>Title</b></td>
                                <td>
                                    <select class="form-select" id="title" name="title">
                                        <option value="">Select</option>
                                        <option value="Mr." <?= $bar['title'] == 'Mr.' ? 'selected' : '' ?>>Mr.</option>
                                        <option value="Ms." <?= $bar['title'] == 'Ms.' ? 'selected' : '' ?>>Ms.</option>
                                        <option value="Mrs." <?= $bar['title'] == 'Mrs.' ? 'selected' : '' ?>>Mrs.</option>
                                        <option value="Dr." <?= $bar['title'] == 'Dr.' ? 'selected' : '' ?>>Dr.</option>
                                        <option value="M/S." <?= $bar['title'] == 'M/S.' ? 'selected' : '' ?>>M/S.</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Name</b></td>
                                <td><input type="text" class="form-control" value="<?= esc($bar['name']) ?>" id="name"></td>
                            </tr>
                            <tr>
                                <td><b>Current Address</b></td>
                                <td><input type="text" class="form-control" value="<?= esc($bar['caddress']) ?>" id="cadd" onblur="remove_apos(this.value,this.id)" /></td>
                            </tr>
                            <tr>
                                <td><b>Current City</b></td>
                                <td><input type="text" class="form-control" value="<?= esc($bar['ccity']) ?>" id="ccity" onblur="remove_apos(this.value,this.id)" maxlength="30"/></td>
                            </tr>
                            <tr>
                                <td><b>Mobile</b></td>
                                <td><input type="text" class="form-control" value="<?= esc($bar['mobile']) ?>" id="adv_mob" maxlength="10" required /></td>
                            </tr>
                            <tr>
                                <td><b>Email</b></td>
                                <td><input type="text" class="form-control" value="<?= esc($bar['email']) ?>" id="adv_email" /></td>
                            </tr>
                            <tr>
                                <td><b>IF Senior</b></td>
                                <td>
                                    <select class="form-select" id="adv_sen">
                                        <option value="Y" <?= $bar['if_sen'] == 'Y' ? 'selected' : '' ?>>YES</option>
                                        <option value="N" <?= $bar['if_sen'] == 'N' ? 'selected' : '' ?>>NO</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><b>AOR/NAOR</b></td>
                                <td>
                                    <select class="form-select" id="adv_aor">
                                        <option value="Y" <?= $bar['if_aor'] == 'Y' ? 'selected' : '' ?>>AOR</option>
                                        <option value="N" <?= $bar['if_aor'] == 'N' ? 'selected' : '' ?>>NAOR</option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="row-aor-code" style="<?= $bar['if_aor'] == 'Y' ? 'display:table-row' : 'display:none' ?>">
                                <td><b>AOR Code</b></td>
                                <td><input type="text" class="form-control" maxlength="5" id="adv_aor_code" value="<?= esc($bar['aor_code']) ?>" /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="text-end mt-3" style="text-align: end;">
            <button type="button" class="btn btn-primary" id="btnout">SAVE</button>
        </div>
        <input type="hidden" id="aor_code_from_db" value="<?= esc($bar['aor_code']) ?>" />
        <br>

    <?php else: ?>
        <div class="alert alert-danger" role="alert">
            SORRY, NO RECORD FOUND!!!
        </div>
    <?php endif; ?>
</div>
<script>
    
</script>