<?php if (count($view_rs) > 0) {
   
?>

    <table class="table table-bordered table-striped" style="margin-left: auto;margin-right: auto;margin-bottom: 120px;" cellpadding="6" id="mainbtl">

        <tr style="text-align: center;" class="for-print">
            <td colspan="12">REPORT FOR RECORD ROOM HALLs </td>
        </tr>
        <tr>
            <th>S.No.</th>
            <th>Hall No.</th>
            <th>Hall Location</th>
            <th class="notfor-print">Active From</th>
            <th>Status</th>
            <th>Hall Alloted Cases</th>
        </tr>
        <?php
        $sno = 1;
        foreach ($view_rs as $row) {
        ?>
            <tr>
                <td><?= $sno; ?></td>
                <td><span class="cl_manage" id="cl_manage_hall<?php echo $row['hall_no']; ?>"><?= $row['hall_no']; ?></span></td>
                <td><?= $row['description']; ?></td>
                <td>
                    <?= isset($row['active_from']) ? date('d-M-Y', strtotime($row['active_from'])) : 'N/A'; ?>
                </td>

                <td><?= $row['active_status']; ?></td>
                <td>
                    <div>
                        <?php
                        $chk_case = $model->get_chk_case($row['hall_no']);
                        if (count($chk_case) > 0) {
                            $currentYear = date('Y');
                            foreach ($chk_case as $row_chk) {
                        ?>
                                <div class="cl_chk_case">
                                    <?php

                                    if ($row_chk['case_from'] == 1 and $row_chk['case_to'] == 555555  and $row_chk['caseyear_from'] == 1950 and $row_chk['caseyear_to'] == @$currentYear) {
                                        echo $row_chk['short_description'] . '- ALL';
                                    } else {
                                        echo $row_chk['short_description'] . '-' . $row_chk['case_from'] . '-' . $row_chk['caseyear_from'] . '-' . $row_chk['case_to'] . '-' . $row_chk['caseyear_to'] . @$caseStage;
                                    }
                                    ?>
                                </div>
                        <?php

                            }
                        }

                        ?>
                    </div>
                </td>

            </tr>
        <?php
            $sno++;
        }
        ?>
        <tr style="text-align: center" class="notfor-print">
            <td colspan="13"><button onclick="get_print('result_main_um')">PRINT</button></td>
        </tr>
    </table>
<?php
} else {
?>
    <div style="margin: 0 auto;text-align: center;margin-top: 20px;font-size: 16px;color: #ff6666">SORRY, NO RECORD FOUND!!!</div>
<?php
}
?>
<div id="dv_sh_hd" style="display: none;position: fixed;top: 0;width: 100%;height: 100%;background-color: black;opacity: 0.6;left: 0;overflow: hidden;z-index: 103">
</div>
<div id="dv_fixedFor_P" style="display: none;position: fixed;top: 0;left: 0;width: 100%;height: 100%;z-index: 105">

    <div id="sar1" style="background-color: white;overflow: auto;margin: 60px 300px 30px 300px;height: 60%;">
        <div id="sp_close" style="text-align: right;cursor: pointer;float: right">
            <img src="<?= base_url('images/close_btn.png');?>" style="width: 20px;height: 20px;">
        </div>
        <div id="sar" style="border: 0px solid red"></div>
    </div>
</div>