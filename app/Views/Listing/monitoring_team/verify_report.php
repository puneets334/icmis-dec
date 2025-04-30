<?php

{
?>
<div style="text-align: center">
    <H3>Verified Cases Report  </H3>
    <?php 

  
 if (!empty($result_array)) { ?>
    <div><h3><?= esc("Verified on " . $verify_dt); ?></h3></div>

    <table align="left" width="100%" border="0" style="table-layout: fixed;">
        <tr>
            <td width="5%" style="font-weight: bold; color: #dce38d;background: #918788;">SNo</td>
            <td width="30%" style="font-weight: bold; color: #dce38d;background: #918788;">Employee Name</td>
            <td width="20%" style="font-weight: bold; color: #dce38d;background: #918788;">Accepted</td>
            <td width="20%" style="font-weight: bold; color: #dce38d;background: #918788;">Defective</td>
            <td width="20%" style="font-weight: bold; color: #dce38d;background: #918788;">Total Cases Verified</td>
        </tr>

        <?php
        $sno = 1;
        $tot = 0;
        $acc_tot = 0;
        $not_acc_tot = 0;

        foreach ($result_array as $row) {
            $str = esc($verify_dt . "_" . $row['ucode']);
        ?>
            <tr>
                <td align="right" style="vertical-align: top;background: #f6e0f3;"><?= $sno; ?></td>
                <td align="left" style="vertical-align: top;background: #f6e0f3;"><?= esc($row['name']); ?></td>
                <td align="left" style="vertical-align: top;background: #f6e0f3;">
                    <a href="<?= base_url("listing/MonitoringTeam/verify_detail_report?str=$str&remarks=1") ?>" target="_blank">
                        <?= esc($row['accepted']); ?>
                    </a>
                    <?php $acc_tot += $row['accepted']; ?>
                </td>
                <td align="left" style="vertical-align: top;background: #f6e0f3;">
                    <a href="<?= base_url("listing/MonitoringTeam/verify_detail_report?str=$str&remarks=2") ?>" target="_blank">
                        <?= esc($row['not_accepted']); ?>
                    </a>
                    <?php $not_acc_tot += $row['not_accepted']; ?>
                </td>
                <td align="left" style="vertical-align: top;background: #f6e0f3;">
                    <a href="<?= base_url("listing/MonitoringTeam/verify_detail_report?str=$str&remarks=0") ?>" target="_blank">
                        <?= esc($row['tot']); ?>
                    </a>
                    <?php $tot += $row['tot']; ?>
                </td>
            </tr>
        <?php
            $sno++;
        } // End foreach loop
        ?>
    </table>

    <br/><br/>
    <h4>
        Total Cases Accepted : <?= esc($acc_tot); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Total Defective Cases : <?= esc($not_acc_tot); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Total Cases Verified : <?= esc($tot); ?>
    </h4>

<?php } else { ?>
    <p>No Records Found</p>
<?php } ?>

</div>

<div id="dv_res1"></div>
    <div id="overlay" style="display:none;">&nbsp;</div>
<?php } ?>
