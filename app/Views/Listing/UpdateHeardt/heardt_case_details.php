<?= view('header') ?>
<style>
    .rowmargin-0 {
        margin: 0 !important;
    }

    table {
        margin: auto;
        text-align: center;
    }

    table.table_tr_th_w_clr.c_vertical_align {
        border: 1px solid lightgray;
        border-collapse: collapse;
    }

    table.table_tr_th_w_clr.c_vertical_align th,
    table.table_tr_th_w_clr.c_vertical_align td {
        border: 1px solid lightgray;
    }

    table.table_tr_th_w_clr.c_vertical_align th {
        font-weight: 600;
    }

    textarea {
        height: 60px;
    }

    .form-group {
        margin-bottom: 10px;
    }

    label {
        padding-bottom: 0px;
    }
</style>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">UPDATE HEARDT TABLE</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>
                                    <?php if (session()->getFlashdata('error')) { ?>
                                        <div class="alert alert-danger text-white ">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?= session()->getFlashdata('error') ?>
                                        </div>
                                    <?php } else if (session("message_error")) { ?>
                                        <div class="alert alert-danger">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?= session()->getFlashdata("message_error") ?>
                                        </div>
                                    <?php } else { ?>
                                        <br />
                                    <?php } ?>

                                    <form>
                                        <?= csrf_field() ?>
                                        <table align="center" width="100%">
                                            <tr align="center" style="color:blue">
                                                <th>

                                                    <?php
                                                    echo "Case No.-";
                                                    if (isset($casetype['fil_no']) && ($casetype['fil_no'] != '' || $casetype['fil_no'] != NULL)) {
                                                        echo '[M]' . $casetype['short_description'] . SUBSTR($casetype['fil_no'], 3) . '/' . $casetype['m_year'];
                                                    }

                                                    if (isset($casetype['fil_no_fh']) && ($casetype['fil_no_fh'] != '' || $casetype['fil_no_fh'] != NULL)) {

                                                        echo ',[R]' . $r_case['short_description'] . SUBSTR($casetype['fil_no_fh'], 3) . '/' . $casetype['f_year'];
                                                    }
                                                    echo ", Diary No: " . substr($diary_no, 0, -4) . '/' . substr($diary_no, -4);
                                                    ?>
                                                </th>
                                            </tr>
                                        </table>


                                        <!-- Start New code implement -->
                                        <?php

                                        $cp_nxtDt = "";

                                        $check12 = $getNextDt;
                                        if (!empty($check12)) {
                                            $ch_det = $check12;
                                            if ($ch_det['next_dt'] != '') {
                                                $cp_nxtDt = $ch_det['next_dt'];
                                            }
                                        }


                                        $curdate = date('Y-m-d');

                                        if ((date("Y-m-d", strtotime($caseDetails['next_dt'])) >= date("Y-m-d", strtotime($curdate)))  && ($caseDetails['main_supp_flag'] == '1' || $caseDetails['main_supp_flag'] == '2')) {
                                            if ($cp_nxtDt != '') {
                                                echo "<span class='blink_me'>Case Already Listed (Published)</span>";
                                            } else {
                                                echo "<span class='blink_me'>Case Already Listed</span>";
                                            }
                                        }
                                        ?>

                                        <!-- End New code implement -->
                                        <input type="hidden" id="fil_hd" value="<?php echo $diary_no; ?>" />
                                        <input type="hidden" id="side_hd" value="<?php echo isset($caseDetails['case_grp']) ? $caseDetails['case_grp'] : '' ?>" />
                                        <table align="center" id="tb_clr" cellspacing="3" cellpadding="2">
                                            <?php if (isset($caseDetails['c_status']) && ($caseDetails['c_status'] == 'D')): ?>
                                                <tr>
                                                    <th colspan="4" style="color:red">The Case is Disposed!!!</th>
                                                </tr>
                                            <?php endif; ?>

                                            <tr>
                                                <th colspan="4" style="color:blue">
                                                    <?php
                                                    $res_name = isset($caseDetails['res_name']) ? $caseDetails['res_name'] : '';
                                                    echo (isset($caseDetails['pet_name']) ? $caseDetails['pet_name'] : '') . " <span style='color:black'> - Vs - </span> " . $res_name;
                                                    ?>
                                                </th>
                                            </tr>

                                            <tr>
                                                <th colspan="4">
                                                    <i><b>Category:</b></i>
                                                    <span style="font-size:14px;color:brown">
                                                        <?php foreach ($categories as $category): ?>
                                                            <?= $category['sub_name1'] . '-' . $category['sub_name2'] . '-' . $category['sub_name3'] . '-' . $category['sub_name4'] ?><br>
                                                        <?php endforeach; ?>
                                                    </span>
                                                </th>
                                            </tr>


                                            <tr>
                                                <th colspan="4" style="text-align: center;font-size: 14px;">
                                                    <?php
                                                    if ($main_case) {
                                                        if ($main_case['conn_key'] == $diary_no) {
                                                            echo "This is Main Diary No";
                                                        } else {
                                                            $connected_diary = '';
                                                            if (isset($diary_no) && isset($diary_year)) {
                                                                $connected_diary = substr($diary_no, 0, -4) . '/' . substr($diary_year, -4);
                                                            }
                                                            echo "This is Connected Diary No, Main Diary No is <span style='color:red'>" . $connected_diary . "</span>";
                                                        }
                                                    }
                                                    ?>
                                                </th>
                                            </tr>

                                            <!-- Coram Entries or 'Not Found' -->
                                            <?php if (!empty($getCoramEntries)): ?>
                                                <table class='table_tr_th_w_clr c_vertical_align W-50 mb-4'>
                                                    <tr>
                                                        <th colspan="5">Already Entries of List before and not before and coram</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Sr.</th>
                                                        <th>Before/Not before</th>
                                                        <th>Hon. Judge</th>
                                                        <th>Reason</th>
                                                        <th>Entry Date</th>
                                                    </tr>
                                                    <?php foreach ($getCoramEntries as $index => $entry):
                                                        $notbef = [
                                                            'N' => 'Not before',
                                                            'B' => 'Before/SPECIAL BENCH',
                                                            'C' => 'Before Coram'
                                                        ];
                                                    ?>
                                                        <tr>
                                                            <td><?= $index + 1 ?></td>
                                                            <td><?= $notbef[$entry['notbef']] ?? '' ?></td>
                                                            <td><?= $entry['jname'] ?></td>
                                                            <td><?= $entry['res_add'] ?></td>
                                                            <td><?= $entry['ent_dt'] ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <div style="text-align:center;padding:10px;">LIST BEFORE/NOT BEFORE/CORAM NOT FOUND</div>
                                                <?php endif; ?>
                                                </table>


                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Filing Date:</label>
                                                            <em><?php if ($caseDetails['diary_no_rec_date'] != '') echo date('d-M-Y', strtotime($caseDetails['diary_no_rec_date'])) . ' on ' . date('h:i A', strtotime($caseDetails['diary_no_rec_date']));
                                                                else echo '--'; ?></em>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label> Registration Date:</label>
                                                            <em><?php if ($caseDetails['fil_dt'] != '') echo date('d-M-Y', strtotime($caseDetails['fil_dt'])) . ' on ' . date('h:i A', strtotime($caseDetails['fil_dt']));
                                                                else echo '--'; ?></em>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Tentative Cause-List Date:</label>
                                                            <input type="text" id="tdt" value="<?= isset($caseDetails['tentative_cl_dt']) ? $caseDetails['tentative_cl_dt'] : ''; ?>" class="form-control dtpp" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Last Order:</label>
                                                            <em><?= !empty($caseDetails['lastorder']) ? $caseDetails['lastorder'] : '--'; ?></em>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Next Date:</label>
                                                            <input type="text" id="ndt" value="<?= isset($caseDetails['next_dt']) ? $caseDetails['next_dt'] : ''; ?>" class="form-control dtpp" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 p-0">
                                                        <div class="row rowmargin-0 w-100">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label style="color:red">Part:</label>
                                                                    <input type="text" class="form-control" id="session" size="2" maxlength="3" onkeypress="return onlynumbers(event)" value="<?= isset($caseDetails['clno']) ? $caseDetails['clno'] : ''; ?>" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label style="color:red">Board No:</label>
                                                                    <input type="text" class="form-control" id="brd_slno" size="1" maxlength="4" onkeypress="return onlynumbers(event)" value="<?= isset($caseDetails['brd_slno']) ? $caseDetails['brd_slno'] : ''; ?>" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Board Type:</label>
                                                            <?php $borad_type = isset($caseDetails['board_type']) ? $caseDetails['board_type'] : ''; ?>
                                                            <select class="form-control" id="board_type" onchange="getCoram()">
                                                                <option value="J" <?= ($borad_type == 'J') ? "selected" : ""; ?>>Judge</option>
                                                                <option value="S" <?= ($borad_type == 'S') ? "selected" : ""; ?>>Single Judge</option>
                                                                <option value="C" <?= ($borad_type == 'C') ? "selected" : ""; ?>>Chamber Judge</option>
                                                                <option value="R" <?= ($borad_type == 'R') ? "selected" : ""; ?>>Registrar</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Purpose of Listing:</label>
                                                            <select class="form-control" id="purList">
                                                                <?php $g_ = 0; ?>
                                                                <?php foreach ($listingPurpose as $purpose): ?>
                                                                    <option value="<?= $purpose['code']; ?>"
                                                                        <?php if ($purpose['code'] == $caseDetails['listorder']) {
                                                                            echo " selected";
                                                                            $g_ = 1;
                                                                        } ?>>
                                                                        <?= $purpose['code'] . ' - ' . $purpose['purpose']; ?>
                                                                    </option>
                                                                <?php endforeach; ?>

                                                                <?php if ($g_ == 0): ?>
                                                                    <option selected><?= isset($caseDetails['listorder']) ? $caseDetails['listorder'] : ''; ?></option>
                                                                <?php endif; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Heading:</label>
                                                            <?php $mainhead = isset($caseDetails['mainhead']) ? $caseDetails['mainhead'] : ''; ?>
                                                            <select class="form-control" id="heading" onchange="getSubheadingCoram(); ">
                                                                <option value="-1">-SELECT-</option>
                                                                <option value="M" <?= ($mainhead == "M") ? "selected" : ""; ?>>Miscelleneous</option>
                                                                <option value="F" <?= ($mainhead == "F") ? "selected" : ""; ?>>Regular</option>
                                                                <option value="L" <?= ($mainhead == "L") ? "selected" : ""; ?>>Lok Adalat</option>
                                                                <option value="S" <?= ($mainhead == "S") ? "selected" : ""; ?>>Mediation</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Sub Heading:</label>
                                                            <select class="form-control" id="subhead">
                                                                <option value="">Select Sub Heading</option>
                                                                <?php foreach ($subheadings as $subheading): ?>
                                                                    <option value="<?= $subheading['stagecode']; ?>"
                                                                        <?php if ($subheading['stagecode'] == $caseDetails['subhead']) {
                                                                            echo 'selected';
                                                                        } ?>>
                                                                        <?= $subheading['stagename']; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="coram">Coram:</label>
                                                            <select id="coram" style="width: 390px;">
                                                                <option value="0">NO CORAM</option>
                                                                <?php if (!empty($judgeData)): ?>
                                                                    <?php foreach ($judgeData as $row_judge): ?>
                                                                        <option value="<?= $row_judge['id'] . '~' . $row_judge['jcd'] . '~' . $row_judge['board_type_mb']; ?>" <?= ($row_judge['id'] == $caseDetails['roster_id']) ? "selected" : ""; ?>>
                                                                            <?= ($row_judge['m_f'] == '1' ? 'M - ' : ($row_judge['m_f'] == '2' ? 'R - ' : '')); ?>
                                                                            <?= $row_judge['board_type_mb'] . ' - ' . $row_judge['abbr'] . ' - ' . $row_judge['bench_no'] . ' - ' . $row_judge['jnm']; ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                <?php endif; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Main/Supplementary:</label>
                                                            <select class="form-control" id="main_supp">
                                                                <?php foreach ($mainSuppOptions as $option): ?>
                                                                    <option value="<?= $option['id']; ?>"
                                                                        <?= $option['id'] == $caseDetails['main_supp_flag'] ? 'selected' : ''; ?>>
                                                                        <?= esc($option['descrip']); ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Statutory Information:</label>
                                                            <textarea class="form-control" id="sinfo"><?php echo isset($caseDetails['remark']) ? $caseDetails['remark'] : ''; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Sitting Judges:</label>
                                                            <input type="text" class="form-control" id="sitt_jud" value="<?php echo isset($caseDetails['sitting_judges']) ? $caseDetails['sitting_judges'] : ''; ?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>

                                                <tr>
                                                    <td colspan="3">
                                                        <span style="color:red;font-weight: 700;">*Note: Please ensure proper format while entering Statutory Remarks. It is advised to give space between words and after comma for separating words/ IA numbers etc.</span>
                                                    </td>
                                                </tr>
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Reason*:</label>
                                                            <input type="text" name="reason_md" class="form-control" id="reason_md" size="50" maxlength="100" autocomplete="off" value="<?= esc($reason); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Is NMD Case:</label>
                                                            <select class="form-control" id="is_nmd" name="is_nmd">
                                                                <option value="N" <?= ($is_nmd == 'N') ? 'selected' : '' ?>>No</option>
                                                                <option value="Y" <?= ($is_nmd == 'Y') ? 'selected' : '' ?>>Yes</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                        </table>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h3 class="mt-4" style="text-align: center">INTERLOCUTARY APPLICATIONS OF CASE</h3>
                                            </div>
                                        </div>


                                        <?php if (!empty($applications)) : ?>
                                            <table id="tb_clr_n">
                                                <tr>
                                                    <th>IA No.</th>
                                                    <th>Annual Reg.No.</th>
                                                    <th>Particular</th>
                                                    <th>Filed By and Date</th>
                                                    <th>Status</th>
                                                </tr>


                                                <?php $sno = 1; ?>
                                                <?php foreach ($applications as $row) : ?>
                                                    <tr>
                                                        <td><?= $sno++; ?></td>
                                                        <td><?= $row['docnum'] . '/' . $row['docyear']; ?></td>
                                                        <td><?= trim($row['docdesc']) === "XTRA" ? $row['other1'] : $row['docdesc']; ?></td>
                                                        <td><?= $row['filedby'] . ' Dt.' . date('d-m-Y', strtotime($row['ent_dt'])); ?></td>
                                                        <td><?= $row['iastat'] === 'P' ? 'Pending' : ($row['iastat'] === 'D' ? 'Disposed' : ''); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                <tr>
                                                    <th colspan="5">
                                                        <input type="button" value="Submit" name="savebutton" />
                                                    </th>
                                                </tr>
                                            <?php else : ?>
                                                <tr>
                                                    <th colspan="5">

                                                    </th>
                                                </tr>
                                            <?php endif; ?>
                                            </table>
                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    <input type="button" value="Submit" name="savebutton" />
                                                </div>
                                            </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    /*$(document).ready(function() {
        $('#tdt').datepicker({
            dateFormat: 'dd-mm-yy' // Change format as needed
        });
    });*/

    var leavesOnDates = <?= next_holidays_new(); ?>;

    $(function() {
        var date = new Date();
        date.setDate(date.getDate());
        $('.dtpp').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            startDate: date,
            todayHighlight: true,
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050',
            datesDisabled: leavesOnDates,
            isInvalidDate: function(date) {
                return (date.day() == 0 || date.day() == 6);
            },
        });
    });

    $(document).on("click", "input[name=savebutton]", function() {
        if ($("#tdt").val() == '//' || $("#tdt").val() == '00/00/0000') {
            alert('Please Enter Tentative Cause List Date');
            $("#tdt").focus();
            return false;
        }
        if ($("#ndt").val() == '//' || $("#ndt").val() == '00/00/0000') {
            alert('Please Enter Next Date');
            $("#ndt").focus();
            return false;
        }
        if ($("#subhead").val() == '') {
            alert('Please Select Subheading');
            $("#subhead").focus();
            return false;
        }
        if ($("#sitt_jud").val() == '' || $("#sitt_jud").val() == 0) {
            alert('Sitting Judges Can Not Left Blank or Zero');
            $("#sitt_jud").focus();
            return false;
        }

        if ($("#subhead").val() == 848 || $("#subhead").val() == 849 || $("#subhead").val() == 850) {
            if ($("#board_type").val() != 'R') {
                alert('For Selected Subhead, Board Type Should be Registrar');
                $("#board_type").focus();
                return false;
            }
        }
        if ($("#reason_md").val() == '') {
            alert('Please Enter Reason to use module.');
            $("#reason_md").focus();
            return false;
        }

        let reason_md_str = $("#reason_md").val().trim();
        if (reason_md_str.length < 20) {
            alert('Please Enter Reason with minimum 20 characters.');
            $("#reason_md").focus();
            return false;
        }

        const CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
                type: 'POST',
                url: "<?php echo base_url('Listing/UpdateHeardt/new_up_he_check_part/'); ?>",
                data: {
                    date: $("#ndt").val(),
                    heading: $("#heading").val(),
                    coram: $("#coram").val(),
                    session: $("#session").val(),
                    main_supp_flag: $("#main_supp").val(),
                    is_nmd: $("#is_nmd").val(),
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                },
                dataType: 'json'
            })
            .done(function(msg) {

                if (msg.if_list_is_printed == 0) {
                    let objData = {
                        dno: $("#fil_hd").val(),
                        ndt: $("#ndt").val(),
                        tdt: $("#tdt").val(),
                        session: $("#session").val(),
                        brd_slno: $("#brd_slno").val(),
                        heading: $("#heading").val(),
                        subhead: $("#subhead").val(),
                        coram: $("#coram").val(),
                        main_supp_flag: $("#main_supp").val(),
                        sitting_jud: $("#sitt_jud").val(),
                        purList: $("#purList").val(),
                        sinfo: $("#sinfo").val(),
                        board_type: $("#board_type").val(),
                        hd_subhead: $("#hd_subhead").val(),
                        reason_md: $("#reason_md").val(),
                        is_nmd: $("#is_nmd").val()
                    };

                    save_proposal_heardt(objData);


                } else {
                    alert("This Part is Printed.");
                }
            })
            .fail(function() {
                alert("Error occurred while checking if the list is printed.");
            });
    });

    async function save_proposal_heardt(objData) {

        await updateCSRFTokenSync();
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        objData.CSRF_TOKEN = CSRF_TOKEN_VALUE;
        $.ajax({
                type: 'POST',
                url: "<?php echo base_url('Listing/UpdateHeardt/save_proposal_heardt'); ?>",
                data: objData,
            })
            .done(function(msg2) {
                if (msg2.message) {
                    alert(msg2.message);
                    window.location.reload(true);
                    //$("input[name=btnGetR]").click(); 
                }
            })
        // .fail(function () {
        //     alert("Error saving proposal. Please try again.");
        // });
    }

    // $(document).on("change","#ndt",function(){
    //     getCoram();
    // });


    async function getCoram() {
        await updateCSRFTokenSync();
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
                type: 'POST',
                url: "get_coram",
                data: {
                    date: $("#ndt").val(),
                    heading: $('#heading').val(),
                    board: $("#board_type").val(),
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                }
            })
            .done(function(msg) {
                $("#coram").html(msg);
                updateCSRFToken()
            })
            .fail(function() {
                alert("Error, Please Contact Server-Room");
                updateCSRFToken()
            });
    }

    async function getSubheadingCoram() {
        await updateCSRFTokenSync();
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
                type: 'POST',
                url: "get_coram",
                data: {
                    date: $("#ndt").val(),
                    heading: $('#heading').val(),
                    board: $("#board_type").val(),
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                }
            })
            .done(function(msg) {
                $("#coram").html(msg);
                getSubhead();
            })
            .fail(function() {
                alert("Error, Please Contact Server-Room");
            });
    }


    $(document).on("changeDate", "#ndt", function() {
        getCoram();
    });

    $(document).on("change", "#coram", function() {
        if ($(this).val() == 0) {
            $("#session").val("0");
            $("#brd_slno").val("0");
            $("#main_supp").val("0");
        }
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
                type: 'POST',
                url: "set_sitting_jud",
                data: {
                    coram: $(this).val(),
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                }
            })
            .done(function(msg) {
                var msg2 = msg.split('#');
                $("#sitt_jud").val(msg2[0]);
                $("#board_type").val(msg2[1]);
            })
            .fail(function() {
                alert("Error, Please Contact Server-Room");
            });
    });

    async function getSubhead() {
        await updateCSRFTokenSync();
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
                type: 'POST',
                url: "get_subhead_for_heardt",
                data: {
                    heading: $("#heading").val(),
                    side: $("#side_hd").val(),
                    dno: $("#fil_hd").val(),
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                }
            })
            .done(function(msg) {
                $("#subhead").html(msg);
                updateCSRFToken()
                previousHead();

            })
            .fail(function() {
                alert("Error, Please Contact Server-Room");
                updateCSRFToken()
            });
    }

    function previousHead() {
        //alert($("#heading").val()+'=='+$("#hd_mainhead").val());
        if ($("#heading").val() == $("#hd_mainhead").val()) {
            //alert('inside');
            $("#subhead").val($("#hd_subhead").val());
        }
    }

    function selectsubhead(val, bn, sd, csnm) {
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById('subhead').innerHTML = xmlhttp.responseText;
                if (val == 'F') {
                    document.getElementById('txx_wrn').style.display = 'block';
                    var xmlhttp1;
                    if (window.XMLHttpRequest) {
                        xmlhttp1 = new XMLHttpRequest();
                    } else {
                        xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp1.onreadystatechange = function() {
                        if (xmlhttp1.readyState == 4 && xmlhttp1.status == 200) {
                            if (xmlhttp1.responseText == 0) {
                                document.getElementById('sh1').style.display = 'none';
                                document.getElementById('sh2').style.display = 'none';
                                document.getElementById('sh3').style.display = 'none';
                                document.getElementById('sh4').style.display = 'none';
                                document.getElementById('subhead1').innerHTML = "<option value='-1'>Select</option>";
                                document.getElementById('subhead2').innerHTML = "<option value='-1'>Select</option>";
                                document.getElementById('subhead3').innerHTML = "<option value='-1'>Select</option>";
                                document.getElementById('subhead4').innerHTML = "<option value='-1'>Select</option>";
                            } else {
                                document.getElementById('sh1').style.display = 'block';
                                document.getElementById('subhead1').innerHTML = xmlhttp1.responseText;
                                document.getElementById('sh2').style.display = 'none';
                                document.getElementById('sh3').style.display = 'none';
                                document.getElementById('sh4').style.display = 'none';
                                document.getElementById('subhead2').innerHTML = "<option value='-1'>Select</option>";
                                document.getElementById('subhead3').innerHTML = "<option value='-1'>Select</option>";
                                document.getElementById('subhead4').innerHTML = "<option value='-1'>Select</option>";
                            }
                        }
                    }
                    var url = "get_subhead_for_heardt.php?ctrl=2&val=4&bn=" + bn + "&sd=" + sd + "&csnm=" + csnm;
                    //alert(url);
                    xmlhttp1.open("GET", url, true);
                    xmlhttp1.send(null);
                } else {
                    document.getElementById('txx_wrn').style.display = 'none';
                    document.getElementById('sh1').style.display = 'none';
                    document.getElementById('sh2').style.display = 'none';
                    document.getElementById('sh3').style.display = 'none';
                    document.getElementById('sh4').style.display = 'none';
                    document.getElementById('subhead1').innerHTML = "<option value='-1'>Select</option>";
                    document.getElementById('subhead2').innerHTML = "<option value='-1'>Select</option>";
                    document.getElementById('subhead3').innerHTML = "<option value='-1'>Select</option>";
                    document.getElementById('subhead4').innerHTML = "<option value='-1'>Select</option>";
                }

            }
        }
        var url = "get_subhead_for_heardt.php?ctrl=1&m_f=" + val;
        xmlhttp.open("GET", url, true);
        xmlhttp.send(null);
    }

    function jud_setter() {
        if (document.getElementById('j1').value == 250 && document.getElementById('listbench').value == 'S') {
            document.getElementById('j2').value = 0;
            document.getElementById('j3').value = 0;
            document.getElementById('j4').value = 0;
            document.getElementById('j5').value = 0;
            document.getElementById('brd_slno').value = 0;
            document.getElementById('session').value = 0;
        } else if (document.getElementById('j1').value == 200 && document.getElementById('listbench').value == 'D') {
            document.getElementById('j2').value = 999;
            document.getElementById('j3').value = 0;
            document.getElementById('j4').value = 0;
            document.getElementById('j5').value = 0;
            document.getElementById('brd_slno').value = 0;
            document.getElementById('session').value = 0;
        } else if (document.getElementById('j1').value == 200 && document.getElementById('listbench').value == 'F') {
            document.getElementById('j2').value = 999;
            document.getElementById('j3').value = 999;
            document.getElementById('j4').value = 0;
            document.getElementById('j5').value = 0;
            document.getElementById('brd_slno').value = 0;
            document.getElementById('session').value = 0;
        } else if (document.getElementById('j1').value == 514 && document.getElementById('listbench').value == 'S') {
            document.getElementById('j2').value = 0;
            document.getElementById('j3').value = 0;
            document.getElementById('j4').value = 0;
            document.getElementById('j5').value = 0;
            document.getElementById('brd_slno').value = 0;
            document.getElementById('session').value = 0;
            document.getElementById('subhead').value = 850;
        } else if (document.getElementById('j1').value == 514 && document.getElementById('listbench').value == 'D') {
            document.getElementById('j2').value = 999;
            document.getElementById('j3').value = 0;
            document.getElementById('j4').value = 0;
            document.getElementById('j5').value = 0;
            document.getElementById('brd_slno').value = 0;
            document.getElementById('session').value = 0;
            document.getElementById('subhead').value = 850;
        }
        document.getElementById('ldir').value = 'N';
    }

    function final_subhead(val, val_master, bn, sd, csnm) {
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if (val == 1) {
                    if (xmlhttp.responseText == 0) {
                        document.getElementById('sh2').style.display = 'none';
                        document.getElementById('sh3').style.display = 'none';
                        document.getElementById('sh4').style.display = 'none';
                        document.getElementById('subhead2').innerHTML = "<option value='-1'>Select</option>";
                        document.getElementById('subhead3').innerHTML = "<option value='-1'>Select</option>";
                        document.getElementById('subhead4').innerHTML = "<option value='-1'>Select</option>";
                    } else {
                        document.getElementById('sh2').style.display = 'block';
                        document.getElementById('subhead2').innerHTML = xmlhttp.responseText;
                        document.getElementById('sh3').style.display = 'none';
                        document.getElementById('sh4').style.display = 'none';
                        document.getElementById('subhead3').innerHTML = "<option value='-1'>Select</option>";
                        document.getElementById('subhead4').innerHTML = "<option value='-1'>Select</option>";
                    }
                } else if (val == 2) {
                    if (xmlhttp.responseText == 0) {
                        document.getElementById('sh3').style.display = 'none';
                        document.getElementById('sh4').style.display = 'none';
                        document.getElementById('subhead3').innerHTML = "<option value='-1'>Select</option>";
                        document.getElementById('subhead4').innerHTML = "<option value='-1'>Select</option>";
                    } else {
                        document.getElementById('sh3').style.display = 'block';
                        document.getElementById('subhead3').innerHTML = xmlhttp.responseText;
                        document.getElementById('sh4').style.display = 'none';
                        document.getElementById('subhead4').innerHTML = "<option value='-1'>Select</option>";
                    }
                } else if (val == 3) {
                    if (xmlhttp.responseText == 0) {
                        document.getElementById('sh4').style.display = 'none';
                        document.getElementById('subhead4').innerHTML = "<option value='-1'>Select</option>";
                    } else {
                        document.getElementById('sh4').style.display = 'block';
                        document.getElementById('subhead4').innerHTML = xmlhttp.responseText;
                    }
                }
            }
        }
        var url = '';
        if (val == 1)
            url = "get_subhead_for_heardt.php?ctrl=2&val=" + val + "&bn=" + bn + "&sd=" + sd + "&val_master=" + val_master + "&csnm=" + csnm;
        else if (val == 2)
            url = "get_subhead_for_heardt.php?ctrl=2&val=" + val + "&bn=" + bn + "&sd=" + sd + "&val_master=" + val_master + "&csnm=" + csnm + "&sbhd1=" + document.getElementById('subhead1').value;
        else if (val == 3)
            url = "get_subhead_for_heardt.php?ctrl=2&val=" + val + "&bn=" + bn + "&sd=" + sd + "&val_master=" + val_master + "&csnm=" + csnm + "&sbhd1=" + document.getElementById('subhead1').value + "&sbhd2=" + document.getElementById('subhead2').value;
        //alert(url);

        xmlhttp.open("GET", url, true);
        xmlhttp.send(null);
    }
</script>