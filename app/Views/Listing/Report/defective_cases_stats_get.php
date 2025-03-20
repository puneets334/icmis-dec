<style>
    .customers {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
    }

    .customers td,
    #customers th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    .customers tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .customers tr:hover {
        background-color: #ddd;
    }

    .customers th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #4CAF50;
        color: white;
    }

    .button {
        border-radius: 3px;
        background-color: #f4511e;
        border: none;
        color: #FFFFFF;
        text-align: center;
        font-size: 20px;
        padding: 4px;
        width: 100px;
        transition: all 0.5s;
        cursor: pointer;
    }

    .button span {
        cursor: pointer;
        display: inline-block;
        position: relative;
        transition: 0.5s;
    }

    .button span:after {
        content: '\00bb';
        position: absolute;
        opacity: 0;
        top: 0;
        right: -20px;
        transition: 0.5s;
    }

    .button:hover span {
        padding-right: 25px;
    }

    .button:hover span:after {
        opacity: 1;
        right: 0;
    }
</style>
<div id="prnnt" style="text-align: center;">
    <h3 style="text-align:center;">STATISTICS ABOUT DEFECTIVE MATTERS LISTED BEFORE HON’BLE THE CHIEF JUSTICE OF INDIA [<?= date('d-m-Y H:i:s'); ?>]</h3>
    <?php
    if (isset($defective_cases_stats)) {
    ?>
        <table class="table table-striped custom-table">
            <!--<table align="left" width="100%" border="0px;" style=" padding: 10px; font-size:13px; table-layout: fixed;">-->

            <tr>
                <td>SrNo.</td>
                <td>Date of Listing</td>
                <td>Total Matters Listed</td>
                <td>Out of them as on date Disposed</td>
                <td>Out of them as on date Pending</td>
            </tr>
            <?php
            $sno = 1;
            foreach ($defective_cases_stats as $ro) {
                $sno1 = $sno % 2;
                if ($sno1 == '1') { ?>
                    <tr>
                    <?php } else { ?>
                    <tr>
                    <?php
                }
                    ?>
                    <td><?php echo $ro['sno']; ?></td>
                    <td><?php echo $ro['next_dt']; ?></td>
                    <td><?php echo $ro['listed']; ?></td>
                    <td><?php echo $ro['disposed']; ?></td>
                    <td><?php echo $ro['pending'];  ?></td>
                    </tr>
                <?php
                $sno++;
            }
                ?>
        </table>
    <?php } else {
        echo 'No Recrods Found';
    }
    ?>

    <h3 style="text-align:center;">TOTAL NO. OF PENDING DEFCTIVE MATTERS [DEFECTS NOTIFIED BUT NOT REFILED AND NOT LISTED] [<?= date('d-m-Y H:i:s'); ?>]</h3>
    <table class="table table-striped custom-table">
        <tr>
            <td>Total matters unregistered and not listed</td>
            <td><?=  
            
            $defectove_un_not_listed['total']; ?></td>
        </tr>
        <tr>
            <td>Defects notified but not refiled</td>
            <td><?=$defect_notified_not_listed['total']; ?></td>
        </tr>
        <tr>
            <td>Re-filing delay is >= 1 year</td>
            <td><?= $refiled_dealy_more_than_1_year['total']; ?></td>
        </tr>
        <tr>
            <td>Re-filing delay is < 1 year</td>
            <td><?= $refiled_dealy_less_than_1_year['total']; ?></td>
        </tr>
        <tr>
            <td>Defects not notified but not listed</td>
            <td><?= $defect_not_notified_not_listed ?></td>
        </tr>
    </table>
</div>