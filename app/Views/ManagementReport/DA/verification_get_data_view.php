<style>
    .custom-table thead th:first-child,.custom-table thead th:last-child,.custom-table tbody td:first-child
    ,.custom-table tbody td:last-child{border-radius:0px!important;}
    </style>
<div class="table-responsive">
    <?php
    if (!empty($data) > 0) {
    ?>
        <table id="example1" class="table table-striped custom-table">
            <thead>
                <tr>
                    <th style="font-size:12px;font-weight:bold;">SNo</th>
                    <th style="font-size:12px;font-weight:bold;">Case No.</th>
                    <th style="font-size:12px;font-weight:bold;">Cause Title</th>
                    <th style="font-size:12px;font-weight:bold;">Case Remarks</th>
                    <th style="min-width:100px;font-size:12px;font-weight:bold;">Listed On (ROP)</th>
                    <th style="font-size:12px;font-weight:bold;">Listed Before</th>
                    <th style="font-size:12px;font-weight:bold;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sno = 1;
                foreach ($data as $row) {
                    $sno1 = $sno % 2;
                    $verify_str = "";
                    $verify_str = $row['diary_no'];
                ?>
                    <tr id="<?= $verify_str ?>" data-cl_date="<?= $row['cl_date'] ?>">
                        <td align="left" style='font-size:12px;vertical-align: top;'><?php echo $sno++; ?></td>
                        <td align="left" style='font-size:12px;vertical-align: top;'><?php echo $row['case_no']; ?></td>
                        <td align="left" style='font-size:12px;vertical-align: top;'><?php echo $row['cause_title']; ?></td>
                        <td align="left" style='font-size:12px;vertical-align: top;'>
                            <?Php
                            $cr =  $model->get_case_remarks($row['diary_no'], $row['cl_date'], $row['jcodes'], $row['clno']);
                            $cr1 = explode("?", $cr);
                            $cr_1 = $cr1[0];
                            $cr_his1 = $cr1[1];
                            $t = $cr1[2];
                            $n = $cr1[3];
                            ?>
                            <?php echo $cr_1 ?>
                            <br>
                            <?php
                            if ($cr_his1) {
                            ?>
                                <span class='tooltip'>History<span class='tooltiptext'><?php //echo $cr_his1 ?></span></span>
                            <?php
                             }
                            ?>


                        </td>
                        <td align="left" style='font-size:12px;vertical-align: top;'>
                            <?php
                            $rop_view = "";
                            $resus = $model->ListedOnROP($row['listing_on'], $row['diary_no']);
                            if (isset($resus)) {
                                $rop_view = "<span style='color:blue;'>";
                                foreach ($resus as $ro_rop) {
                                    $rjm = explode("/", $ro_rop['pdfname']);
                                    if ($rjm[0] == 'supremecourt') {
                                        $rop_view .= '<a href="../../jud_ord_html_pdf/' . $ro_rop['pdfname'] . '" target="_blank">' . $row['listing_on'] . '</a>';
                                    } else {
                                        $rop_view .= '<a href="../../judgment/' . $ro_rop['pdfname'] . '" target="_blank">' . $row['listing_on'] . '</a>';
                                    }
                                }
                                $rop_view .= "</span>";
                            } else {
                                $rop_view = "<span>" . $row['listing_on'] . "</span>";
                            }
                            echo $rop_view;
                            ?>
                        </td>
                        <td align="left" style='font-size:12px;vertical-align: top;'><?php echo $row['heard_by']; ?></td>

                        <td align="left" style='font-size:12px;vertical-align: top;'>
                            <?php 
                            if ($row['status']) {
                                if ($row['status'] == 'R') {
                                    echo "Rejected ";
                                } else {
                                    echo "Approved ";
                                }
                                echo "by " . $row['approved_by_user'] . " " . date('d-m-Y H:i:s', strtotime($row['approved_on'])) . " " . $row['rejection_remark'];
                            } else {



                                if ($row['next_dt']) {
                                    echo "Case Already Listed in Future Date " . date('d-m-Y', strtotime($row['next_dt']));
                                }
                                else if ($if_bo) {
                            ?>
                                <select class="ele form-control" name="rremark_<?php echo $row['diary_no']; ?>" id="rremark_<?php echo $row['diary_no']; ?>" onChange='javascript:show_reject_remark("<?php echo $verify_str; ?>")'>
                                    <option value="A">Approved</option>
                                    <option value="R">Rejected</option>
                                </select>
                                <textarea class="form-control mt-2" name="reject_remark_<?php echo $row['diary_no']; ?>" id="reject_remark_<?php echo $row['diary_no']; ?>" cols="12" rows="2" maxlength="100" style="display:none;"></textarea>
                                <br>
                                <button id="bsubmit" onclick="save_verification('<?php echo $verify_str; ?>')" class="quick-btn mt-26">Verify</button>
                                <?php  } 
                                ?>

                        </td>
                    </tr>
            <?php }
                        }
            ?>
            </tbody>
        </table>
    <?php
    } else {
        echo "No Records Found for selected To Dates";
    }
    ?>
</div>
<script>
    $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        "buttons": ["excel", "pdf"]
    });

    function show_reject_remark(dno) {
        if ($("#rremark_" + dno).val() == 'R') {
            $("#reject_remark_" + dno).val("").show(500);
        } else {
            $("#reject_remark_" + dno).val("").hide(500);
        }
    }
</script>