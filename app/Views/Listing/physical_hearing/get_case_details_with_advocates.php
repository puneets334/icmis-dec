<?php if ($check_physical_hearing_pool_result == 0) {
    echo "<p style='text-align: center;color:red'> <strong>REQUEST CASE DATA NOT AVAILABLE IN THE PHYSICAL HEARING POOL</strong></p>";
    return;
}

if ($chk_heardt == 0) {
    echo "<p style='text-align: center;'><strong>DATA NOT IN HEARDT TABLE</strong></p>";
    return;
}
?>

<?php if (isset($details) && !empty($details)) { ?>
    <input type="hidden" id="searched_diary_no" value="<?php echo $diary_no ?>">
    <input type="hidden" id="side_hd" value="<?php echo $details['side']; ?>">

    <table align="center" width="100%">
        <tr align="center" style="color:blue">
            <th><?php
                echo "Case No.-";
                if ($fil_details['fil_no'] != '' || $fil_details['fil_no'] != NULL) {
                    echo '[M]' . $fil_details['short_description'] . SUBSTR($fil_details['fil_no'], 3) . '/' . $fil_details['m_year'];
                }
                if (isset($short_description_by_casecode)) {
                    echo ',[R]' . $short_description_by_casecode . SUBSTR($fil_details['fil_no_fh'], 3) . '/' . $fil_details['f_year'];
                }
                echo ", Diary No: " . $diary_number . ' / ' . $diary_year;
                ?>
            </th>
        </tr>
    </table>

    <table align="center" id="tb_clr" cellspacing="3" cellpadding="2">
        <?php if ($details['c_status'] == 'D') { ?>
            <tr>
                <th colspan="4" style="color:red">The Case is Disposed!!!</th>
            </tr>
        <?php } ?>
        <tr align="center">
            <th colspan="4" style="color:blue"><?php echo $details['pet_name'] . "<span style='color:black'> - Vs - </span>" . $details['res_name'] ?></th>
        </tr>
        <tr align="center">
            <?php
            $category = '';
            foreach ($multiple_category as $row_cate) {
                $category .= $row_cate['sub_name1'] . '-' . $row_cate['sub_name2'] . '-' . $row_cate['sub_name3'] . '-' . $row_cate['sub_name4'] . '<br>';
            } ?>
            <th colspan="4"><i>Category:</i> <span style="font-size:14px;color:brown"><?php echo $category; ?></span></th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;font-size: 14px;">
                <?php
                if (!empty($main_case)) {
                    if ($main_case['conn_key'] == $diary_no) {
                        echo "This is Main Diary No";
                    } else {
                        echo "This is Connected Diary No, Main Diary No is <span style='color:red'>" . substr($main_case['conn_key'], 0, -4) . '/' . substr($main_case['conn_key'], -4) . "</span>";
                        $diary_no = $main_case['conn_key'];
                ?>
                <?php
                    }
                }
                ?>
                <input type="hidden" id="fil_hd" value="<?php echo $diary_no; ?>">
            </th>
        </tr>
        <tr>
            <td style="">Filing Date:</td>
            <td style="">
                <?php if ($details['diary_no_rec_date'] != '') echo date('d-M-Y', strtotime($details['diary_no_rec_date'])) . ' on ' . date('h:i A', strtotime($details['diary_no_rec_date']));
                else echo '--'; ?>
            </td>
            <td style="">Registration Date:</td>
            <td style="">
                <?php if ($details['fil_dt'] != '') echo date('d-M-Y', strtotime($details['fil_dt'])) . ' on ' . date('h:i A', strtotime($details['fil_dt']));
                else echo '--'; ?>
            </td>
        </tr>
        <tr>
            <td>Last Order:</td>
            <td><?php if ($details['lastorder'] != '' || $details['lastorder'] != NULL) echo $details['lastorder'];
                else echo '--'; ?></td>
        </tr>
        <?php if ($details['c_status'] == 'P') { ?>
            <table id="caseAdvocateList" border="width=1px;" class="display table-bordered" width="100%" cellpadding="5" cellspacing="0">
                <tr>
                    <th colspan="4" style='text-align: right;'>
                        <button id="updateAdvocateConsentButton" class="ui-button ui-widget ui-corner-all" onclick="javascript:confirmBeforeUpdateConcent();">Save Data</button>
                    </th>
                </tr>
                <tr align="center" style="color:darkblue">
                    <th>#</th>
                    <th style="text-align: left" width="30%">Advocate Details</th>
                    <th style="text-align: left">Consent</th>
                </tr>
                <?php
                if (!empty($case_aor_list)) {
                    foreach ($case_aor_list as $list_index => $aor) { ?>
                        <tr>
                            <td style="text-align: center"><?= $list_index + 1; ?></td>
                            <td><?php echo $aor["adv_name"] ?> <strong><?php echo $aor["adv"] ?> </strong> , AOR Code : <?php echo $aor["aor_code"] ?></td>
                            <td>
                                <?php

                                $radioCondition2 = $radioCondition1 = '';
                                if ($aor['consent'] == 'P')
                                    $radioCondition1 = 'checked=checked';
                                if ($aor['consent'] == 'V')
                                    $radioCondition2 = 'checked=checked';

                                echo "<input type='radio' name='$aor[advocate_id]' id='vacationList' value='P_$aor[advocate_id]' $radioCondition1>Physical&nbsp;&nbsp;";
                                echo "<input type='radio' name='$aor[advocate_id]' id='vacationList' value='V_$aor[advocate_id]' $radioCondition2>Virtual";
                                ?>
                            </td>
                        </tr>
            <?php
                    }
                }
            } ?>
            </table>
        <?php } else { ?>
            <table align="center" width="100%">
                <tr align="center" style='text-align: right;'>
                    <?php echo "No Data Found"; ?>
                </tr>
            </table>
        <?php } ?>
        <script>
            function confirmBeforeUpdateConcent() {
                var allVals = [];
                var noOfCases;
                $("#caseAdvocateList input:radio:checked").each(function() {
                    allVals.push($(this).val());
                });

                noOfCases = allVals.length;
                if (noOfCases < 1) {
                    alert('Please give the consent for atleast one Advocate');
                    return false;
                } else {

                    var choice = confirm('Do you really want to update the Advocate consent...?');
                    if (choice == true) {
                        updateAdvocateConsent(allVals);
                    } else {
                        return false;
                    }
                }
            }

            function updateAdvocateConsent(allVals) {
                var diary_no = $("#searched_diary_no").val();
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.ajax({
                    url: "<?php echo base_url('Listing/PhysicalHearing/update_advocate_consent'); ?>",
                    type: "POST",
                    data: {
                        diary_no: diary_no,
                        advocate_ids: allVals,
                        CSRF_TOKEN: CSRF_TOKEN_VALUE
                    },
                    success: function(r) {
                        updateCSRFToken();
                        if (r != 0) {
                            console.log(r);
                            alert('Selected Cases Consent Successfully Updated');
                        } else {
                            alert("Invalid Diary No. !! Please try again...");
                        }
                    },
                    error: function() {
                        updateCSRFToken();
                        alert('ERROR');
                    }
                });
            }
        </script>