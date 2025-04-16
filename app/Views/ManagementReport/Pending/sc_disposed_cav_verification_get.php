<?php
if (count($result_array) > 0) {
?>
<div id="prnnt" style="text-align: center;">
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-md-2 ml-n4 text-left"><input name="prnnt1" type="button" id="prnnt1" value="Print" class="btn btn-primary bk_out"></div>
            <div class="col-md-8 text-center">
                <h3 class="mt-3" style="text-align:center">Judgement Given Cases to be verify coram in disposed and pending cases</h3>
            </div>
            <div class="col-md-2"></div>
        </div>
    </div>
    <table id="customers" class="table table-striped custom-table">
        <thead>
            <tr>
                <th>SrNo.</th>
                <th>Judgement Case No. / Diary No.</th>
                <th>Judgement Given By</th>
                <th>Judgement Date</th>
                <th>Case No. Against Judgement</th>
                <th>Coram Against Judgement</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sno = 1;
            foreach ($result_array as $ro) {
                $sno1 = $sno % 2;
                if ($sno1 == '1') { ?>
            <tr>
                <?php } else { ?>
            <tr>
                <?php
                }
                    ?>
                <td><?php echo $sno; ?></td>
                <td><?php echo $ro['disposed_case_no'] . ' @ ' . $ro['disposed_diary_no']; ?></td>
                <td><?php echo $ro['disposed_by']; ?></td>
                <td><?php $rop_view = "";
                        if ($ro['conn_key'] != null and $ro['conn_key'] != '' and $ro['conn_key'] != 0) {
                            $rop_chk_dno = $ro['conn_key'];
                        } else {
                            $rop_chk_dno = $ro['disposed_diary_no'];
                        }
                        $this_result = new App\Models\ManagementReport\PendingModel;
                        $result_array2 = $this_result->sc_disposed_cav_verification_table_get($rop_chk_dno);

                        if (count($result_array2) > 0) {
                            $rop_view = "<span style='color:blue;'>";
                            foreach ($result_array2 as $ro_rop) {
                                $rjm = explode("/", $ro_rop['pdfname']);
                                if ($rjm[0] == 'supremecourt') {
                                    $rop_view .= '<a href="../../jud_ord_html_pdf/' . $ro_rop['pdfname'] . '" target="_blank">' . $ro_rop['orderdate'] . '</a>';
                                } else {
                                    $rop_view .= '<a href="../../judgment/' . $ro_rop['pdfname'] . '" target="_blank">' . $ro_rop['orderdate'] . '</a>';
                                }
                            }
                            $rop_view .= "</span>";
                        }
                        echo $rop_view; ?>
                </td>
                <?php
                    if ($ro['fil_no_fh'] != '') {
                        $in_array_var = $this_result->get_ma_info($ro['ct2'], $ro['crf2'], $ro['f_year']);
                    } elseif ($ro['fil_no'] != '') {
                        $in_array_var = $this_result->get_ma_info($ro['ct1'], $ro['crf1'], $ro['m_year']);
                    }
                    if (!(empty($in_array_var))) {
                        for ($i = 0; $i < count($in_array_var); $i++) {
                            $row_12 = $this_result->case_status($in_array_var[$i][0]);
                            if ($row_12) {
                                if ($i >= 1) {
                    ?>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <?php
                                }
                                
                                if ($row_12['c_status'] == 'P') {
                                    $case_status = "Pending";
                                } else {
                                    $case_status = "Disposed";
                                }
                    ?>
                <td>
                    <?php echo $row_12['reg_no_display'] . " @ " . $row_12['diary_no'] . "<br>Status - " . $case_status; ?>
                </td>
                <td><?php echo $row_12['new_coram'] ?></td>
            </tr>
            <?php
                            } else {
                ?>
            <td></td>
            <td></td>
            </tr>
            <?php
                            }
                        }
                        if ($i > 1) {
                ?>
            </tr>
            <?php
                        } else {
                            $i = 0;
                        }
                    } else {
            ?>
            <td></td>
            <td></td>
            </tr>
            <?php
                    }
        ?>
            <?php
                $sno++;
            }
    ?>
        </tbody>
    </table>
</div>

<?php
} else {
    echo "No Recrods Found";
}
?>