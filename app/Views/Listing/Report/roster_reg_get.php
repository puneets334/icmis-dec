<?php
$ldates = date('Y-m-d', strtotime($_POST['ldates']));
    $h3_head = "Roster For Week Commencing Dated ".$ldates;
        ?>
<div id="prnnt">
    <div style="font-family: verdana; text-align: center; font-size:14px;"><?php echo $h3_head; ?>
    </div>
    <?php
$res = $RoserRegModel->getSubName($ldates);
if (!empty($res)) {
?>
    <table border="1" width="100%" id="example" class="display" cellspacing="0" style="font-family: verdana; font-size:8px;">
        <tr style="background: #918788;">
            <td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;">SrNo.</td>
            <td width="49%" style="text-align: center; font-weight: bold; color: #dce38d;">Category</td>
            <td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;">Ready</td>
            <td width="3%" style="text-align: center; font-weight: bold; color: #e50000;">Not Ready</td>
            <td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;">Total</td>

            <?php
            $sqlz = $RoserRegModel->getCourtNo($ldates);
            foreach ($sqlz as $roz) {
                for ($i = 1; $i <= 15; $i++) {
                    if ($roz['courtno'] == $i) {
                        echo "<td width='3%' style='text-align: center; font-weight: bold; color: #dce38d;'>" . ($roz['jjj'] ?? '') . "</td>";
                    }
                }
            }
            ?>
        </tr>
       
        <tr>
            <td colspan="5" align="center"> Court No. => </td>
            <?php for ($i = 1; $i <= 15; $i++) { ?>
                <td width="3%" style="text-align: center; font-weight: bold; color: #dce38d;"><?php echo $i; ?></td>
            <?php } ?>
        </tr>

        <?php
        $sno = 1;
        $tot_ready_with_cn = 0;
        $tot_not_ready_with_cn = 0;
        $tot_of_tot_cases = 0;
        $tot_ready_m = 0;
        $tot_not_ready_m = 0;
        $tot_of_tot_m_ready_not_redy = 0;

        foreach ($res as $ro) {
            $sno1 = $sno % 2;
            if ($sno1 == 1) {
                echo "<tr style='padding: 10px; background: #ececec;'>";
            } else {
                echo "<tr style='padding: 10px; background: #f6e0f3;'>";
            }

            // Accumulate totals
            $tot_ready_with_cn += $ro['ready_with_cn'];
            $tot_not_ready_with_cn += $ro['not_ready_with_cn'];
            $tot_of_tot_cases += $ro['tot_cases'];
            $tot_ready_m += $ro['ready_m'];
            $tot_not_ready_m += $ro['not_ready_m'];
            $tot_of_tot_m_ready_not_redy += $ro['tot_m_ready_not_redy'];
            ?>

            <td align="right" style='vertical-align: top;'><?php echo $sno; ?></td>
            <td align="left" style='vertical-align: top;'><?php echo $ro['sub_cat'] . " (" . str_replace(",", ", ", $ro['sccat']) . ")"; ?></td>
            <td align="right" style='vertical-align: top;'><?php echo $ro['ready_m'] ?? 0; ?></td>
            <td align="right" style='vertical-align: top;'><?php echo $ro['not_ready_m'] ?? 0; ?></td>
            <td align="right" style='vertical-align: top;'><?php echo $ro['tot_m_ready_not_redy'] ?? 0; ?></td>

            <?php
            for ($i = 1; $i <= 15; $i++) {
                echo "<td align='center' style='vertical-align: top;'>" . ($ro["court_$i"] ?? 0) . "</td>";
            }
            ?>
        </tr>

        <?php
            $sno++;
        }
        ?>

        <tr>
            <td align="right" colspan="3">Total</td>
            <td align="right"><?php echo $tot_ready_m; ?></td>
            <td align="right"><?php echo $tot_not_ready_m; ?></td>
            <td align="right"><?php echo $tot_of_tot_m_ready_not_redy; ?></td>

            <?php
            for ($i = 1; $i <= 15; $i++) {
                echo "<td align='right'>&nbsp;</td>"; // Add empty cells for the courts columns
            }
            ?>
        </tr>

        <tr>
            <td colspan="21" align="center">
                Note :
                (1) With Connected Ready: <?php echo $tot_ready_with_cn; ?> &nbsp;
                (2) With Connected Not Ready: <?php echo $tot_not_ready_with_cn; ?> &nbsp;
                (3) Total With Connected: <?php echo $tot_of_tot_cases; ?> &nbsp;
                <?php $ros = $RoserRegModel->getCount(); ?>
                (4) Pendency: <?php echo $ros['count']; ?>
            </td>
        </tr>
    </table>

<?php
} else {
    echo "No Records Found";
}
?>

    <BR/><BR/><BR/><BR/>
</div>

<div style="width: 100%; padding-bottom:1px; background-color: #ddf1f9; text-align: center; border-top: 1px solid #000; position: fixed; bottom: 0; left: 0; right: 0; z-index: 0; display:block;">   
    <span id="toggle_hw" style="color: #0066cc; font-weight: bold; cursor: pointer; padding-right: 1px;">    
    </span>
    <input name="prnnt1" type="button" id="prnnt1" value="Print" >
</div>       


