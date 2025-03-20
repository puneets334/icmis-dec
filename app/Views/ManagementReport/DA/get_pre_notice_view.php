<div class="table-responsive">
    <?php
    if (count($get_pre_notice_data) > 0) {
    ?>
        <h3><?php echo $h3_head . "<br>"; //$heading1;  
            ?></h3>
        <table class="table table-striped custom-table" id="example1">
            <thead>
                <tr>
                    <th width="5%">SrNo.</th>
                    <th width="15%">Reg No. / Diary No</th>
                    <!--<th width="10%">Tentative Date</th>-->
                    <th width="18%">Petitioner / Respondent</th>
                    <th width="18%">Advocate</th>
                    <th width="10%">Subhead</th>
                    <th width="10%">Purpose</th>
                    <th width="15%">Category</th>
                    <th width="5%">Status</th>
                    <th width="9%">Section</td>
                </tr>
            </thead>
            <tbody>
                <?php
                $sno = 1;
                foreach ($get_pre_notice_data as $ro) {
                    $advsql = $model->get_advocate_data($ro["diary_no"]);
                    pr($advsql);
                    $sno1 = $sno % 2;
                    $dno = $ro['diary_no'];
                    $conn_no = $ro['conn_key'];
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

                    if ($ro['reg_no_display']) {
                        $fil_no_print = $ro['reg_no_display'];
                    } else {
                        $fil_no_print = "Unregistred";
                    }
                    if ($sno1 == '1') { ?>
                        <tr id="<?php echo $dno; ?>">
                        <?php } else { ?>
                        <tr id="<?php echo $dno; ?>">
                        <?php
                    }

                    if ($ro['pno'] == 2) {
                        $pet_name = $ro['pet_name'] . " AND ANR.";
                    } else if ($ro['pno'] > 2) {
                        $pet_name = $ro['pet_name'] . " AND ORS.";
                    } else {
                        $pet_name = $ro['pet_name'];
                    }
                    if ($ro['rno'] == 2) {
                        $res_name = $ro['res_name'] . " AND ANR.";
                    } else if ($ro['rno'] > 2) {
                        $res_name = $ro['res_name'] . " AND ORS.";
                    } else {
                        $res_name = $ro['res_name'];
                    }
                    $padvname = "";
                    $radvname = "";

                    $advsql = $model->get_advocate_data($ro["diary_no"]);

                    if (count($advsql) > 0) {
                        $radvname =  $advsql["r_n"];
                        $padvname =  $advsql["p_n"];
                    }

                    if (($ro['section_name'] == null or $ro['section_name'] == '') and $ro['ref_agency_state_id'] != '' and $ro['ref_agency_state_id'] != 0) {
                        if ($ro['active_reg_year'] != 0)
                            $ten_reg_yr = $ro['active_reg_year'];
                        else
                            $ten_reg_yr = date('Y', strtotime($ro['diary_no_rec_date']));
                        if ($ro['active_casetype_id'] != 0)
                            $casetype_displ = $ro['active_casetype_id'];
                        else if ($ro['casetype_id'] != 0)
                            $casetype_displ = $ro['casetype_id'];
                        $section_ten_q = $model->get_advocate_data($ro['ref_agency_state_id'], $casetype_displ, $ten_reg_yr);

                        if (count($section_ten_q) > 0) {
                            $ro['section_name'] = $section_ten_q["section_name"];
                        }
                    } ?>
                        <td><?php echo $sno; ?></td>
                        <td><?php echo $fil_no_print . "<br>Diary No. " . substr_replace($ro['diary_no'], '-', -4, 0); ?></td>
                        <!--<td><?php /*echo date('d-m-Y', strtotime($ro['tentative_cl_dt']));  */ ?></td>-->

                        <td><?php echo $pet_name . "<br/>Vs<br/>" . $res_name; ?></td>
                        <td><?php echo str_replace(",", ", ", trim($padvname, ",")) . "<br/>Vs<br/>" . str_replace(",", ", ", trim($radvname, ",")); ?></td>
                        <td><?php echo $ro['stagename']; ?></td>
                        <td><?php echo $ro['purpose']; ?></td>
                        <td><?php
                            if ($ro['submaster_id'] == 0 or $ro['submaster_id'] == '' or $ro['submaster_id'] == null) {
                            } else {
                                f_get_cat_diary_basis($ro['submaster_id']);
                            }
                            ?>
                        </td>

                        <td><?php echo $ro['r_n_r']; ?></td>
                        <td><?php echo $ro['section_name'] . "<br/>" . $ro['name']; ?></td>

                        </tr>
            </tbody>
            ?>
        <?php
                    $sno++;
                }
        ?>
        </table>
    <?php
    } else {
        echo "No Recrods Found";
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
</script>