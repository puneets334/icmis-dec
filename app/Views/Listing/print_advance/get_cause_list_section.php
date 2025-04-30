<style>
      table thead tr th {
        background-color: #0d48be !important;
        color: #fff !important;
        white-space: nowrap;
    }

</style>
<div id="prnnt" style="text-align: center; font-size:10px;">
    <h3 class="h4">Cause List for Dated <?php echo $list_dt; ?> (<?php echo $mainhead_descri; ?>)<br><?php echo $main_suppl_descri; ?></h3>
    <?php if (!empty($getCases)) { ?>
        <table align="left" width="100%" border="0" style="font-size: 14px;" class="table table-bordered table-stripped table-responsive">
            <thead>
            <tr>
                <th width="5%">SrNo.</th>
                <th width="5%">Court No.</th>
                <th width="5%">Item No.</th>
                <th width="7%">Diary No</th>
                <th width="14%">Reg No.</th>
                <th width="13%">Petitioner / Respondent</th>
                <th width="13%">Advocate</th>
                <th width="5%">Section Name</th>
                <th width="10%">DA Name</th>
                <th width="18%">Statutory Info.</th>
                <th width="7%">Listed Before</th>
                <th width="7%">Published On</th>
                <th width="8%">Purpose</th>
                <th width="10%">Trap</th>
                <th width="9%">Office<br>Report</th>
            </tr>
            </thead>
            <?php
            if (!empty($getCases)) {
                $sno = 1;
                $today = date('Y-m-d');
                $case_type = array(39, 9, 10, 19, 20, 25, 26);

                foreach ($getCases as $ro) {
                    if (strtotime($ro['diary_no_rec_date']) >= strtotime('2017-05-08')) {
                        $times_listed = $ro['times_listed'] ?? 0;
                        $last_listed_date = $ro['last_listed'][1] ?? null;
    
                        if ($times_listed == 0 && !in_array($ro['casetype_id'], $case_type) && $ro['board_type'] != 'R' && $ro['board_type'] != 'C') {
                            continue;
                        } elseif ($times_listed == 1 && !in_array($ro['casetype_id'], $case_type) && $ro['board_type'] != 'R' && $ro['board_type'] != 'C') {
                            if (!empty($last_listed_date) && strtotime($last_listed_date) >= strtotime($today)) {
                                continue;
                            }
                        }
                    }

                    $remark = $ro['remark'];
                    $sno1 = $sno % 2;
                    $dno = $ro['diary_no'];
                    $diary_no_rec_date = date('d-m-Y', strtotime($ro['diary_no_rec_date']));
                    $active_fil_dt = date('d-m-Y', strtotime($ro['active_fil_dt']));
                    $conn_no = $ro['conn_key'];
                    $m_c = "";
                    if ($conn_no == $dno) {
                        $m_c = "Main";
                    }
                    if ($conn_no != $dno && $conn_no > 0) {
                        $m_c = "Conn.";
                    }
                    $coram = $ro['coram'];
                    if ($ro['board_type'] == "J") {
                        $board_type1 = "Court";
                    }
                    if ($ro['board_type'] == "C") {
                        $board_type1 = "Chamber";
                    }
                    if ($ro['board_type'] == "R") {
                        $board_type1 = "Registrar";
                    }
                    $filno_array = explode("-", $ro['active_fil_no']);

                    if (empty($ro['reg_no_display'])) {
                        $fil_no_print = "Unregistred";
                    } else {
                        $fil_no_print = $ro['reg_no_display'];
                    }

                    if ($sno1 == '1') { ?>
                        <tr style="background: #ececec;" id="<?php echo $dno; ?>">
                        <?php } else { ?>
                        <tr style="background: #f6e0f3;" id="<?php echo $dno; ?>">
                        <?php } ?>

                        <?php
                        if ($ro['pno'] == 2) {
                            $pet_name = $ro['pet_name'] . " AND ANR.";
                        } elseif ($ro['pno'] > 2) {
                            $pet_name = $ro['pet_name'] . " AND ORS.";
                        } else {
                            $pet_name = $ro['pet_name'];
                        }

                        if ($ro['rno'] == 2) {
                            $res_name = $ro['res_name'] . " AND ANR.";
                        } elseif ($ro['rno'] > 2) {
                            $res_name = $ro['res_name'] . " AND ORS.";
                        } else {
                            $res_name = $ro['res_name'];
                        }

                        $radvname = "";
                        $padvname = "";
                        $impldname = "";

                        if (!empty($ro['advocate'])) {
                            // foreach ($ro['advocate'] as $rowadv) {
                            //     $radvname = (!empty($rowadv["r_n"])) ? $rowadv["r_n"] : '';
                            //     $padvname = (!empty($rowadv["p_n"])) ? $rowadv["p_n"] : '';
                            //     $impldname = (!empty($rowadv["i_n"])) ? $rowadv["i_n"] : '';
                            // }
                            $radvname = (!empty($ro['advocate']["r_n"])) ?  $ro['advocate']["r_n"] : '';
                            $padvname = (!empty($ro['advocate']["p_n"])) ?  $ro['advocate']["p_n"] : '';
                            $impldname = (!empty($ro['advocate']["i_n"])) ? $ro['advocate']["i_n"] : '';
                        }

                        if (($ro['section_name'] == null || $ro['section_name'] == '') && $ro['ref_agency_state_id'] != '' && $ro['ref_agency_state_id'] != 0) {
                            if ($ro['active_reg_year'] != 0) {
                                $ten_reg_yr = $ro['active_reg_year'];
                            } else {
                                $ten_reg_yr = date('Y', strtotime($ro['diary_no_rec_date']));
                            }

                            if ($ro['active_casetype_id'] != 0) {
                                $casetype_displ = $ro['active_casetype_id'];
                            } elseif ($ro['casetype_id'] != 0) {
                                $casetype_displ = $ro['casetype_id'];
                            }

                            if (!empty($ro['sectionTen'])) {
                                foreach ($ro['sectionTen'] as $section_ten_row) {
                                    if ($ro['section_name'] == '') {
                                        $ro['section_name'] = $ro['dno'];
                                    } else {
                                        echo $ro['section_name'] = $section_ten_row["section_name"];
                                    }
                                    if ($section_ten_row["dacode"] == 0) {
                                        $ro['name'] = "no dacode";
                                    }
                                }
                            }
                        }
                        ?>

                        <td align="left" style='vertical-align: top;'><?php echo $sno; ?></td>
                        <td align="left" style='vertical-align: top;'>
                            <?php
                            if ($ro['courtno'] == 31) {
                                echo 'VC 1';
                            } elseif ($ro['courtno'] == 32) {
                                echo 'VC 2';
                            } elseif ($ro['courtno'] == 33) {
                                echo 'VC 3';
                            } elseif ($ro['courtno'] == 34) {
                                echo 'VC 4';
                            } elseif ($ro['courtno'] == 35) {
                                echo 'VC 5';
                            } elseif ($ro['courtno'] == 36) {
                                echo 'VC 6';
                            } elseif ($ro['courtno'] == 37) {
                                echo 'VC 7';
                            } elseif ($ro['courtno'] == 38) {
                                echo 'VC 8';
                            } elseif ($ro['courtno'] == 39) {
                                echo 'VC 9';
                            } elseif ($ro['courtno'] == 40) {
                                echo 'VC 10';
                            } elseif ($ro['courtno'] == 41) {
                                echo 'VC 11';
                            } elseif ($ro['courtno'] == 42) {
                                echo 'VC 12';
                            } elseif ($ro['courtno'] == 43) {
                                echo 'VC 13';
                            } elseif ($ro['courtno'] == 44) {
                                echo 'VC 14';
                            } elseif ($ro['courtno'] == 45) {
                                echo 'VC 15';
                            } elseif ($ro['courtno'] == 46) {
                                echo 'VC 16';
                            } elseif ($ro['courtno'] == 47) {
                                echo 'VC 17';
                            } elseif ($ro['courtno'] == 21) {
                                echo 'R 1';
                            } elseif ($ro['courtno'] == 22) {
                                echo 'R 2';
                            } elseif ($ro['courtno'] == 61) {
                                echo 'R VC 1';
                            } elseif ($ro['courtno'] == 62) {
                                echo 'R VC 2';
                            } else {
                                echo $ro['courtno'];
                            }
                            ?>
                        </td>
                        <td align="left" style='vertical-align: top;'><?php echo $ro['brd_slno'] . "<br>" . $m_c; ?></td>
                        <td align="left" style='vertical-align: top;'><?php echo substr_replace($ro['diary_no'], '/', -4, 0); ?></td>
                        <td align="left" style='vertical-align: top;'><?php echo $fil_no_print . "<br>Rdt " . $active_fil_dt; ?></td>
                        <td align="left" style='vertical-align: top;'><?php echo $pet_name . "<br/>Vs<br/>" . $res_name; ?></td>
                        <td align="left" style='vertical-align: top;'><?php echo str_replace(",", ", ", trim($padvname, ",")) . "<br/>Vs<br/>" . str_replace(",", ", ", trim($radvname, ",")) . " ", str_replace(",", ", ", trim($impldname, ",")); ?></td>
                        <td align="left" style='vertical-align: top;'><?php echo $ro['section_name']; ?></td>
                        <td align="left" style='vertical-align: top;'><?php echo $ro['name']; ?></td>
                        <td align="left" style='vertical-align: top;'><?php echo $remark; ?></td>
                        <td align="left" style='vertical-align: top;'><?php echo $board_type1; ?></td>
                        <td align="left" style='vertical-align: top;'>
                            <?php
                            if ($ro['ent_dt']) {
                                echo date('d-m-Y H:i:s', strtotime($ro['ent_dt']));
                            } else {
                                echo "<span style='color:red;'>Not Published</span>";
                            }
                            ?>
                        </td>
                        <td align="left" style='vertical-align: top;'><?php echo $ro['purpose']; ?></td>
                        <td align="left" style='vertical-align: top;'>
                            <?php
                            if (!empty($ro['filTrap'])) {
                                foreach ($ro['filTrap'] as $trap_rs) {
                                    echo $trap_rs['remarks'] . '<br>(' . $trap_rs['name'] . ')';
                                }
                            }
                            ?>
                        </td>
                        <td align="left" style='vertical-align: top;'>
                            <?php
                            if (!empty($ro['ord'])) {
                                echo '<center><table>';
                                echo '<tr><td>Uploaded on </td></tr>';
                                foreach ($ro['ord'] as $office_report) {
                                    $res_office_report = $office_report['office_repot_name'];
                                    $res_max_o_r = $office_report['office_report_id'];
                                    if ($res_max_o_r == 0) {
                                        $res_max_o_r = "&nbsp;";
                                    }
                                    $dno = $office_report['dno'];
                                    $d_yr = $office_report['d_yr'];
                                    $order_dt = $office_report['order_dt'];
                                    $rec_dt = $office_report['rec_dt'];
                                    $fil_nm = "../../officereport/" . $d_yr . '/' . $dno . '/' . $res_office_report;

                                    $pos = stripos($res_office_report, '.pdf');
                                    if ($pos !== false) {
                                        echo '<a href=' . $fil_nm . '>' . date('d-m-Y', strtotime($rec_dt)) . '</a>';
                                    } else {
                                        echo '<a href=' . $fil_nm . '>' . date('d-m-Y', strtotime($rec_dt)) . '</a>';
                                    }
                                }
                                echo '</table></center>';
                            }
                            ?>
                        </td>
                        </tr>
                    <?php
                    $sno++;
                }
                    ?>
        </table>
<?php }
    } else {
        echo "<h5 class='text-danger'>No Records Found</h5>";
    }
?>
</div>

<div style="width: 100%; padding-bottom:1px; background-color: #ddf1f9; text-align: center; border-top: 1px solid #000; position: fixed; bottom: 0; left: 0; right: 0; z-index: 0; display:block;">
    <span id="toggle_hw" style="color: #0066cc; font-weight: bold; cursor: pointer; padding-right: 1px;"></span>
    <input name="prnnt1" type="button" id="prnnt1" value="Print">
</div>