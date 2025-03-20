<?php if ($eliminationData): ?>
    <?php
    $status = "";
    $eliminationDate = "";
    $actionButton = "";
    $actionRequired = "";
    $disp_weededby = "";
    $judgeinitial = array_fill(0, 5, '0');
    $disposaljudge = 0;

    if ($eliminationData[0]['c_status'] == 'D') {
        $status = "Disposed";
        $actionButton = '<button type="button" id="btn-save-elimination" class="btn btn-block bg-olive btn-flat" onclick="saveElimination()"><i class="fa fa-save"></i> SAVE </button>';
        $disposaljudge = $eliminationData[0]['dispjud'];
        $disp_weededby = $eliminationData[0]['weeded_by'];
        $judgedetail = explode(",", $eliminationData[0]['jud_id']);
        $judgeinitial = array_map('trim', $judgedetail); // Use trim if needed
    } else if ($eliminationData[0]['c_status'] == 'P') {
        $status = "Pending";
        $actionButton = '<button type="button" id="btn-save-elimination" class="btn btn-block bg-olive btn-flat" disabled><i class="fa fa-save"></i> SAVE </button>';
    }

    $actionRequired = ($eliminationData[0]['ele_dt'] == null || $eliminationData[0]['ele_dt'] == '') ? "I" : "U";
    $eliminationDate = '<input type="text" class="form-control" name="eliminationDate" id="eliminationDate" placeholder="Elimination Date" value="' . ($actionRequired == "I" ? date("d-m-Y") : date("d-m-Y", strtotime($eliminationData[0]['ele_dt']))) . '">';
    ?>

    <form id="eliminationDetail" method="post">
        <table class="table table-striped">
            <tbody>
                <tr>
                    <td colspan="2"><input type="hidden" name="diary_no" id="diary_no" value="<?= esc($eliminationData[0]['diary_no']) ?>" /></td>
                    <td colspan="2"><input type="hidden" name="actionRequired" id="actionRequired" value="<?= esc($actionRequired) ?>" />
                        <input type="hidden" name="usercode" id="usercode" value="<?= esc(session()->get('login')['usercode']) ?>" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><label>Case No.</label></td>
                    <td colspan="2"><?= esc($eliminationData[0]['reg_no_display']) . ' @DNo. ' . esc($eliminationData[0]['dno']) . '/' . esc($eliminationData[0]['dyear']) ?></td>
                </tr>
                <tr>
                    <td><label>Party</label></td>
                    <td><?= esc($eliminationData[0]['pet_name']) . ' vs. ' . esc($eliminationData[0]['res_name']) ?></td>
                    <td><label>Status</label></td>
                    <td><?= esc($status) ?></td>
                </tr>
                <tr>
                    <td><label>Elimination Date</label></td>
                    <td>
                        <div class="input-group"><?= $eliminationDate ?></div>
                    </td>
                    <td><label>Remark</label></td>
                    <td><input type="text" class="form-control" id="remark" name="remark" placeholder="Remark" value="<?= esc($eliminationData[0]['remark']) ?>"></td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Disposal Detail</h3>
                            </div>
                        </div>
                    </td>
                </tr>

                <?php if ($eliminationData[0]['c_status'] == 'D'): ?>
                    <tr>
                        <td><label>Cause List/Order Date </label></td>
                        <td><input type="text" class="form-control" name="orderDate" id="orderDate" placeholder="Order Date" value="<?= esc(date("d-m-Y", strtotime($eliminationData[0]['ord_dt']))) ?>"></td>
                        <td><label>Disposal/Hearing Date </label></td>
                        <td><input type="text" class="form-control" name="disposalDate" id="disposalDate" placeholder="Disposal Date" value="<?= esc(date("d-m-Y", strtotime($eliminationData[0]['disp_dt']))) ?>"></td>
                    </tr>

                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <tr>
                            <td><label>Judge <?= $i ?></label></td>
                            <td>
                                <select class="form-control" id="judge<?= $i ?>" name="judge<?= $i ?>">
                                    <option value="0">Select Judge</option>
                                    <?php foreach ($judge as $j): ?>
                                        <option value="<?= esc($j['jcode']) ?>" <?= $judgeinitial[$i - 1] == $j['jcode'] ? 'selected' : '' ?>>
                                            <?= esc($j['jcode']) ?> - <?= esc($j['jname']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                    <?php endfor; ?>

                    <tr>
                        <td><label>Disposal Judge </label></td>
                        <td>
                            <select class="form-control" id="dispJudge" name="dispJudge">
                                <option value="0">Select Judge</option>
                                <?php foreach ($judge as $dispJudge): ?>
                                    <option value="<?= esc($dispJudge['jcode']) ?>" <?= $disposaljudge == $dispJudge['jcode'] ? 'selected' : '' ?>>
                                        <?= esc($dispJudge['jcode']) ?> - <?= esc($dispJudge['jname']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Awarded Amount </label></td>
                        <td><input type="text" class="form-control" id="amount" name="amount" placeholder="Amount" value="<?= esc($eliminationData[0]['camnt']) ?>"></td>
                        <td><label>CRTSTAT </label></td>
                        <td><input type="text" class="form-control" id="crtstat" name="crtstat" placeholder="CRTSTAT" value="<?= esc($eliminationData[0]['crtstat']) ?>"></td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <table>
                                <?php foreach ($caseRemarksHead as $caseRemark): ?>
                                    <tr>
                                        <td style="width: 33%">
                                            <label style="cursor: pointer;">
                                                <input class="myCheckbox" type="checkbox" name="caseRemarksHead[]" value="<?= esc($caseRemark['cis_disp_code']) ?>" <?= $caseRemark['cis_disp_code'] == $eliminationData[0]['disp_type'] ? 'checked' : '' ?> />
                                                <?= esc($caseRemark['head']) ?>
                                            </label>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Weeded By </label></td>
                        <td>
                            <select class="form-control" id="weededby" name="weededby">
                                <option value="0">Select Weeded By</option>
                                <?php foreach ($weededBy as $type): ?>
                                    <option value="<?= esc($type['usercode']) ?>" <?= $disp_weededby == $type['usercode'] ? 'selected' : '' ?>>
                                        <?= esc($type['empid']) ?> - <?= esc($type['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="4"><label> Disposal detail is not available.</label></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td colspan="4"><?= $actionButton ?></td>
                </tr>
            </tbody>
        </table>
    </form>

    <script>
        $(function() {
            $("#eliminationDate, #orderDate, #disposalDate").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            });
        });
    </script>

<?php else: ?>
    <p id="rslt">Case Not Found</p>
<?php endif; ?>