<div id="getDetailsOfListedCases" style="margin-left: 15px;">
                                <div class="form-group col-sm-12">
                                    <?php
                                    if (isset($caseListedInfo)) {
                                        if (!empty($caseListedInfo)) {
                                    ?>
                                            <h4>Cause List Dated <?= date("d-m-Y", strtotime($_POST['causelistDate'])); ?> Court No. <?= $_POST['courtNo'] ?></h4>
                                            <hr>
                                            <div class="clearfix">
                                                <button class="btn btn-success m-1" id="sendForFaster">Send For Faster</button>
                                                <button class="btn btn-success  m-1" id="modifyFaster" style="float: right;">Revert</button>
                                            </div>

                                            <table id="tbl_history" class="custom-table table table-striped table-bordered">
                                                <thead>
                                                    <th>Item No.</th>
                                                    <th>Case No.</th>
                                                    <th>Cause Title</th>
                                                    <!--                        <th>Action</th>-->
                                                </thead>
                                                <tbody>

                                                    <?php
                                                    $isDisplayCourtDetails = "YES";
                                                    foreach ($caseListedInfo as $row) {

                                                        if ($isDisplayCourtDetails == "YES") {
                                                            $isDisplayCourtDetails = "NO";
                                                    ?>
                                                            <tr>
                                                                <td colspan="4" style="font-weight: bold;">
                                                                    <input type="checkbox" name="chkall" id="chkall" value="ALL" onClick="chkall(this);">
                                                                    <?= "Court No. " . $row['courtno'] . " - " . $row['judge_name'] ?>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        }
                                                        if ($row['diary_no'] == $row['conn_key'] or $row['conn_key'] == 0 or $row['conn_key'] == '' or $row['conn_key'] == null) {
                                                            $print_brdslno = $row['brd_slno'];
                                                            $con_no = "0";
                                                            $is_connected = "";
                                                        } else {
                                                            $print_brdslno = $row["brd_slno"] . "." . ++$con_no;
                                                            $is_connected = "<br/><span style='color:red;'>Conn.</span>";
                                                        }
                                                        if (empty($row['reg_no_display'])) {
                                                            $case_no = 'Diary No. ' . substr_replace($row['diary_no'], ' of ', -4, 0);
                                                        } else {
                                                            $case_no = $row['reg_no_display'];
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <?php
                                                                //  $checkBoxDisable = '';
                                                                if (!empty($row['exist_faster_cases']) && $row['exist_faster_cases'] == 1) {
                                                                    //  $checkBoxDisable = 'disabled';
                                                                    echo '<br><span style="color:red;font-size: 14px;"><b>Already Processed</b></span>';
                                                                } else {
                                                                    $isAleradyAdded = "";
                                                                    if (!empty($row['actionStatus']) && $row['actionStatus'] == 'Added') {
                                                                        $isAleradyAdded = 'checked="checked"';
                                                                    }

                                                                ?>
                                                                    <input <?= $isAleradyAdded ?> type="checkbox" id="chkeeed" name="chk"
                                                                        data-diary_no="<?= $row['diary_no'] ?>"
                                                                        data-conn_key="<?= $row['conn_key'] ?>"
                                                                        data-brd_slno="<?= $row['brd_slno'] ?>"
                                                                        data-courtno="<?= $row['courtno'] ?>"
                                                                        data-judges="<?= $row['judges'] ?>"
                                                                        data-next_dt="<?= $row['next_dt'] ?>"
                                                                        data-mainhead="<?= $row['mainhead'] ?>"
                                                                        data-roster_id="<?= $row['roster_id'] ?>"
                                                                        data-main_supp_flag="<?= $row['main_supp_flag'] ?>"
                                                                        data-board_type="<?= $row['board_type'] ?>" />
                                                                    <?= $print_brdslno . $is_connected ?>
                                                                <?php
                                                                }
                                                                ?>
                                                            </td>
                                                            <td><?= $case_no ?></td>
                                                            <td><?= $row['pet_name'] . '<br>Vs.<br>' . $row['res_name'] ?></td>
                                                            <!--                                <td id="diaryno_-->
                                                            <? //= $row['diary_no'] 
                                                            ?><!--">-->
                                                            <? //=$row['actionStatus'] 
                                                            ?><!--</td>-->
                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>

                                    <?php
                                        } else {
                                            echo "<span class='text-danger'>No Records Found</span>";
                                        }
                                    }
                                    ?>
                                </div>
                                <?php ?>
                            </div>