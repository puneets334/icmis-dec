<?php if(is_array($report_data)){?>
<div id="prnTable" align="center">
            <table cellpadding=1 cellspacing=0 border=1>
                <tr>
                    <th colspan=13><font color=blue size=+1><?= $report_name ?> Report between :<?php echo date("d-m-Y", strtotime($firstDate)). ' to ' . date("d-m-Y", strtotime($lastDate)); ?></font></th>
                </tr>
                <tr><th colspan="4">Institution</th><th colspan="4">Disposal</th></tr>
                <tr>
                    <th>Admission</th>
                    <th>Regular</th>
                    <th>Civil</th>
                    <th>Criminal</th>
                    <th>Admission</th>
                    <th>Regular</th>
                    <th>Civil</th>
                    <th>Criminal</th></tr>

                <?php
                foreach ($report_data as $row) : ?>
                

               <?php //$row['misc_institution']  = "ssfsdfsdf";?>
               <?php //$row['criminal_dispose']  = "ssfsdfsasdasdf";?>
                <tr style='color:#0000FF'>
                    <td><?=$row['misc_institution']?></td>
                    <td><?=$row['reg_institution']?></td>
                    <td><?=$row['civil_institution']?></td>
                    <td><?=$row['criminal_institution']?></td>
                    <td><?=$row['misc_dispose']?></td>
                    <td><?=$row['reg_dispose']?></td>
                    <td><?=$row['civil_dispose']?></td>
                    <td><?=$row['criminal_dispose']?></td>
                    <?php
                    endforeach; ?>
                    
                </tr>
            </table>
        </div>
        <center><input name="cmdPrnRqs22" type="button" id="cmdPrnRqs22" onClick="CallPrint('prnTable');" value="PRINT">
        </center>
        <?php

    }//if end
    else
        echo "<center><h2>Record Not Found</h2></center>";
    ?>