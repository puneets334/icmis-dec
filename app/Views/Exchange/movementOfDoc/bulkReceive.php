<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="card-title">Bulk Receive</h3>
                            </div>
                        </div>
                    </div>



                    <form>
                        <div id="dv_content1" class="container mt-4">
                            <div class="text-center mb-4">
                                <?php if (count($select_rs) > 0): ?>
                                    <h4>RECORDS TO BE RECEIVED
                                        <span id="enable-in-print">FOR <?php echo get_user_details($ucode); ?></span>
                                    </h4>
                                    <table class="table table-bordered table-striped">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>SNo.</th>
                                                <th>Document No.</th>
                                                <th>Document Type</th>
                                                <th>Diary No.</th>
                                                <th>Case Nos.</th>
                                                <th>Dispatch By</th>
                                                <th>Dispatch Date</th>
                                                <th>Listing Date</th>
                                                <th>Select <input type="checkbox" name="all" id="all" /></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sno = 1;
                                            $today = date('Y-m-d');
                                            $case_type = array(39, 9, 10, 19, 20, 25, 26);
                                            foreach ($select_rs as $row):
                                                if (strtotime($row['diary_no_rec_date']) >= strtotime('2017-05-08')) {
                                                    if ($model->no_of_times_listed($row['diary_no']) == 0 && !in_array($row['casetype_id'], $case_type) && $row['board_type'] != 'R' && $row['board_type'] != 'C') {
                                                        continue;
                                                    } elseif ($model->no_of_times_listed($row['diary_no']) == 1 && !in_array($row['casetype_id'], $case_type) && $row['board_type'] != 'R' && $row['board_type'] != 'C') {
                                                        if (strtotime(last_listed_date($row['diary_no'])) >= strtotime($today)) {
                                                            continue;
                                                        }
                                                    }
                                                }
                                            ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $sno; ?></td>
                                                    <td><?php echo '<span class="text-primary">' . $row['kntgrp'] . '</span> - ' . $row['docnum'] . '/' . $row['docyear']; ?></td>
                                                    <td><?php echo $row['docdesc'] . (!empty($row['other1']) ? '-' . $row['other1'] : ''); ?></td>
                                                    <td class="text-center"><?php echo get_real_diaryno($row['diary_no']); ?></td>
                                                    <td><?php echo get_casenos_comma($row['diary_no']); ?></td>
                                                    <td><?php echo get_user_details($row['disp_by']); ?></td>
                                                    <td class="text-center"><?php echo htmlspecialchars($row['disp_dt']); ?></td>
                                                    <td class="text-center">
                                                        <?php
                                                        if (!empty($row['next_dt']) && strtotime($row['next_dt']) >= strtotime($cur_date) && strtotime($row['next_dt']) <= strtotime($new_date)) {
                                                            echo ($row['main_supp_flag'] == 1 || $row['main_supp_flag'] == 2)
                                                                ? "<span class='text-danger'>" . htmlspecialchars($row['next_dt']) . "</span>"
                                                                : htmlspecialchars($row['next_dt']);
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="chk<?php echo $sno; ?>" id="chk<?php echo $sno; ?>" value="<?php echo $row['diary_no'] . '-' . $row['doccode'] . '-' . $row['doccode1'] . '-' . $row['docnum'] . '-' . $row['docyear'] . '-' . $row['disp_by']; ?>" />
                                                    </td>
                                                </tr>
                                            <?php
                                                $sno++;
                                            endforeach;
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="9" class="text-center">
                                                    <button type="button" class="btn btn-primary" id="btnrece" onclick="receiveFunction()">Receive</button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                <?php else: ?>
                                    <div class="alert alert-warning text-center">SORRY!!! NO RECORD FOUND</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>





                </div>
            </div>
        </div>
    </div>
</section>
<script src="<?php echo base_url('/Ajaxcalls/menu_assign/bulk_receive.js'); ?>"></script>