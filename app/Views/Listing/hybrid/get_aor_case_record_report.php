<div class="p-2">
    <button name="prnnt1" class="btn btn-warning" id="prnnt1">Print</button>
</div>

<?php
if (count($result_array) > 0) {

    $psrno = "1";
?>
    <div class="row col-sm-10 p-2" id="print_area">
        <div class="col-sm-8">
            <h3>CASES LISTED ON <?= date('d-m-Y', strtotime($listing_dts ?? 'now')); ?></h3>
        </div>

        <table class="table table-responsive">
            <thead>
                <tr>
                    <th style="width: 5%">
                        SNo.
                    </th>

                    <th style="width: 5%">
                        Item No.
                    </th>
                    <th style="width: 25%">Case Details</th>
                    <th style="width: 25%">Court Details</th>
                    <th style="width: 40%">AOR/Party In Person</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sno = 1;
                foreach ($result_array as $row) { //echo '<pre>'; print_r($row); exit;
                    $HybridModel = new App\Models\Listing\HybridModel;

                    if ($row['diary_no'] == $row['conn_key'] or $row['conn_key'] == 0 or $row['conn_key'] == '' or $row['conn_key'] == null) {
                        $print_brdslno = $row['brd_slno'];
                        $con_no = "0";
                        $is_connected = "";
                    } else {
                        $print_brdslno = "&nbsp;" . $row["brd_slno"] . "." . ++$con_no;
                        $is_connected = "<br/><span style='color:red;'>Conn.</span>";
                    }
                    $count_advocates = "";
                    if ($row['conn_key'] != null and $row['conn_key'] > 0) {
                        $count_advocates = $HybridModel->f_get_advocate_count_with_connected($row["conn_key"], $row['next_dt']);
                        // $count_advocates = f_get_advocate_count_with_connected($row["conn_key"], $row['next_dt']);
                    } else {
                        $count_advocates = $row["total_advocates"];
                    }
                    $conn_case_count = 0;
                    if ($row['conn_key'] == $row['diary_no']) {
                        $conn_case_count = $HybridModel->f_connected_case_count_listed($row["conn_key"], $row['next_dt']);
                    }

                    $aorData = '';
                    $advocate_id = !empty($row['advocate_ids']) ? $row['advocate_ids'] : NULL;
                    $advocate_id = 584;
                    $response = '';
                    //PP
                    $consent_source_qry =  $consent_source;
                    //advocate
                    $response =  $HybridModel->get_data_consent_through($row['diary_no'], $row['next_dt'], $consent_source_qry, $advocate_id);

                    if (in_array($advocate_id, array(584, 585, 610, 616, 666, 940))) {

                        $response1 =  $HybridModel->get_data_bar_advocate_consent_through($row['diary_no'], $row['next_dt'], $consent_source_qry);
                    }

                    if (count($response) > 0 or count($response1) > 0) {
                        // pr('sdaf');

                ?>
                        <tr id="tr_<?= $row['diary_no'] ?>">
                            <td>
                                <?= $psrno++ ?>
                            </td>
                            <td>
                                <?= $print_brdslno . $is_connected ?>
                            </td>
                            <td>
                                <?php
                                echo $row['reg_no_display'] . ' @ ' . $row['diary_no'];
                                echo "<br>" . $row['pet_name'] . ' Vs. ' . $row['res_name'];
                                echo $conn_case_count > 0 ? "<br><span class='text-danger'>(Connected Cases : " . $conn_case_count . ")</span>" : '';
                                if ($count_advocates > 20) {
                                    echo "<br><span style='color:red;'><b>*** (More than 20 Advocates)</b></span>";
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $jcodes = $row['judges'];
                                $courtno = $row['courtno'];
                                echo f_get_judges_names_by_jcode($jcodes);
                                ?>
                                <br>
                                <span class="badge badge-secondary">
                                    Court No. :
                                    <?php
                                    if ($courtno > 60) {
                                        echo "VC R " . ($courtno - 60);
                                    } else if ($courtno > 30) {
                                        echo "VC " . ($courtno - 30);
                                    } else if ($courtno > 20) {
                                        echo "R " . $courtno;
                                    } else {
                                        echo $courtno;
                                    }
                                    ?>
                                </span>
                                <span class="badge badge-secondary">
                                    <?php
                                    if ($row['mainhead'] == 'F') {
                                        echo "Regular List";
                                    } else if ($row['mainhead'] == 'M' && $row['board_type'] == 'J') {
                                        echo "Misc. List";
                                    } else if ($row['mainhead'] == 'M' && $row['board_type'] == 'C') {
                                        echo "Chamber List";
                                    } else if ($row['mainhead'] == 'M' && $row['board_type'] == 'R') {
                                        echo "Registrar List";
                                    } else {
                                    }
                                    ?>
                                </span>


                                <?php
                                if ($row['main_supp_flag'] == 2) {
                                    echo '<span class="badge badge-secondary">Supplementary</span>';
                                }
                                ?>

                            </td>
                            <?php

                            $aor_pop_srno = 0;
                            if (count($response) > 0 or count($response1) > 0) {
                                $aorData .= '<div class="aorDataDiv">
                        <table>
                        <thead>
                        <tr>
                        <th width="12%">
                        SNo.
                        </th>

                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Consent Source</th>
                        </tr>
                        </thead>
                        <tbody>';

                                foreach ($response as $aorPpData) {
                                    ++$aor_pop_srno;

                                    if ($aorPpData['entry_source'] == 1) {
                                        $entry_source_cell = "eMail";
                                    } else if ($aorPpData['entry_source'] == 2) {
                                        $entry_source_cell = "Portal";
                                    } else {
                                        $entry_source_cell = "";
                                    }

                                    if ($aorPpData['id'] != null) {
                                        $applicant_selected = "checked";
                                    } else {
                                        $applicant_selected = "";
                                    }
                                    if (strlen($aorPpData['email']) > 5 || strlen($aorPpData['mobile']) == 10) {

                                        $aorData .= '<tr>
                        <td>' . $aor_pop_srno . '</td>    
                        <td>' . $aorPpData['name'] . ' (AOR - ' . $aorPpData['aor_code'] . ')</td>
                        <td>' . $aorPpData['email'] . '</td>
                        <td>' . $aorPpData['mobile'] . '</td>
                        <td>' . $entry_source_cell . '</td>
                        </tr>';
                                    } else {
                                        $aorData .= '<tr>
                        <td></td>    
                        <td>' . $aorPpData['name'] . ' (AOR - ' . $aorPpData['aor_code'] . ')</td>
                        <td>' . $aorPpData['email'] . '</td>
                        <td>' . $aorPpData['mobile'] . '</td>
                        <td>' . $entry_source_cell . '</td>

                        </tr>';
                                    }
                                    $ctn++;
                                }

                                foreach ($response1 as $ppData) { //echo '<pre>'; print_r($ppData['partyname']); exit;
                                    ++$aor_pop_srno;
                                    if ($ppData['entry_source'] == 1) {
                                        $entry_source_cell = "eMail";
                                    } else if ($ppData['entry_source'] == 2) {
                                        $entry_source_cell = "Portal";
                                    } else {
                                        $entry_source_cell = "";
                                    }
                                    if ($ppData['id'] != null) {
                                        $applicant_selected = "checked";
                                    } else {
                                        $applicant_selected = "";
                                    }
                                    if (strlen($ppData['email']) > 5 || strlen($ppData['contact']) == 10) {
                                        $aorData .= '<tr>
                         <td>' . $aor_pop_srno . '</td>        
                        <td>' . $ppData['partyname'] . ' (' . $ppData['name'] . ')</td>
                        <td>' . $ppData['email'] . '</td>
                        <td>' . $ppData['contact'] . '</td>
                        <td>' . $entry_source_cell . '</td>
                        </tr>';
                                    } else {
                                        $aorData .= '<tr>
                        <td></td>    
                        <td>' . $ppData['partyname'] . ' (' . $ppData['name'] . ')</td>
                        <td>' . $ppData['email'] . '</td>
                        <td>' . $ppData['contact'] . '</td>
                        <td>' . $entry_source_cell . '</td>
                        </tr>';
                                    }
                                    $ctn++;
                                }

                                $aorData .= "</tbody></table></div>";
                            }




                            ?>
                            <td><?= $aorData ?></td>
                            <?php
                            $aorData = '';
                            ?>

                        </tr>


                <?php
                    }
                }
                ?>
            </tbody>
        </table>
        <div class="text-primary p-3">
            <h5>Total Consents : <?php //$ctn
                                    ?></h5>
        </div>
    </div>
    <?php
} else {
    echo "<iv class='mt-5'>No Records Found</div>";
}
//  }
// }
// else{
// echo "Please select mandatory fields";
// }

    ?>
    
    <style>
        .aorDataDiv {
            border: 2px solid #ead5d5;
        }

        .ppDataDiv {
            border: 2px solid #078e7b;
        }
    </style>