<?php
if (isset($date_result) && sizeof($date_result) > 0) {
    $total_fd = 0;
    $total_frs = 0;
    $total_inperson = 0;
    $total_bail = 0;
    $total_aw = 0;
    $total_ia = 0;
    $total_ntu = 0;
    $total_notice = 0;
    $total_fdsp = 0;
    $total_misc = 0;
    $total = 0;
    ?>
    <div id="printable">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <h1 align="center"><?php echo "CauseList Date: " . $dateDisplay; ?></h1>
            </tr>
            <tr>
                <h4 align="center">Report generated on <?php echo date('d-m-Y H:i:s A'); ?></h4>
            </tr>
            <tr>
                <th><b>#</b></th>
                <th><b>Coram</b></th>
                <th><b>Fixed Date/<br>Mention </b></th>
                <th><b>Fresh/<br>Fresh Adjourned </b></th>
                <th><b>In Person</b></th>
                <th><b>Bail Matters</b></th>
                <th><b>After Week</b></th>
                <th><b>IMP IAs</b></th>
                <th><b> Not Taken Up/<br>Adjourned</b></th>
                <th><b>Notice</b></th>
                <th><b>Final Disposal</b></th>
                <th><b>Other Misc.</b></th>
                <th><b>Total</b></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $i = 0;
            foreach ($date_result as $result) {
                $i++;
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $result['abbreviation']; ?></td>
                    <td><?php echo $result['fd_not_list']; ?></td>
                    <td><?php echo $result['frs_adj_not_list']; ?></td>
                    <td><?php echo $result['inperson_not_list']; ?></td>
                    <td><?php echo $result['bail_not_list']; ?></td>
                    <td><?php echo $result['aw_not_list']; ?></td>
                    <td><?php echo $result['imp_ia_not_list']; ?></td>
                    <td><?php echo $result['nradj_not_list']; ?></td>
                    <td><?php echo $result['notice_not_list']; ?></td>
                    <td><?php echo $result['fdisp_not_list']; ?></td>
                    <td><?php echo $result['oth_not_list']; ?></td>
                    <td style="font-weight:bold;"><?php echo $result['fd_not_list'] + $result['frs_adj_not_list'] +
                            $result['inperson_not_list'] + $result['bail_not_list'] + $result['aw_not_list'] +
                            $result['imp_ia_not_list'] + $result['nradj_not_list'] + $result['notice_not_list'] +
                            $result['fdisp_not_list'] + $result['oth_not_list']; ?></td>
                </tr>
                <?php
                $total_fd += $result['fd_not_list'];
                $total_frs += $result['frs_adj_not_list'];
                $total_inperson += $result['inperson_not_list'];
                $total_bail += $result['bail_not_list'];
                $total_aw += $result['aw_not_list'];
                $total_ia += $result['imp_ia_not_list'];
                $total_ntu += $result['nradj_not_list'];
                $total_notice += $result['notice_not_list'];
                $total_fdsp += $result['fdisp_not_list'];
                $total_misc += $result['oth_not_list'];
            }
            $total += $total_fd + $total_frs + $total_inperson + $total_bail + $total_aw + $total_ia + $total_ntu +
                $total_notice + $total_fdsp + $total_misc;
            ?>
            <tr style="font-weight: bold;">
                <td colspan="2"><b>Total</b></td>
                <td><b><?php echo $total_fd; ?></b></td>
                <td><b><?php echo $total_frs; ?></b></td>
                <td><b><?php echo $total_inperson; ?></b></td>
                <td><b><?php echo $total_bail; ?></b></td>
                <td><b><?php echo $total_aw; ?></b></td>
                <td><b><?php echo $total_ia; ?></b></td>
                <td><b><?php echo $total_ntu; ?></b></td>
                <td><b><?php echo $total_notice; ?></b></td>
                <td><b><?php echo $total_fdsp; ?></b></td>
                <td><b><?php echo $total_misc; ?></b></td>
                <td><b><?php echo $total; ?></b></td>
            </tr>
            </tbody>
        </table>
    </div>
<?php } else { ?>
    <div class="" align="center">
        <h4>No data found</h4>
    </div>
<?php } ?>
