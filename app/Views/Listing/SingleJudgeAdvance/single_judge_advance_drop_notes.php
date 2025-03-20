<div id="prnnt" style="font-size:12px;">
    <table border="0" width="100%" style="font-size:12px; text-align: left; background: #ffffff;" cellspacing=0>
        <tr>
            <th colspan="4" style="text-align: center;"><img src="<?= base_url('images/scilogo.png'); ?>" width="50px" height="80px" /></th>
            </th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;">SUPREME COURT OF INDIA</th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;">SINGLE JUDGE ADVANCE CAUSE LIST DROP NOTES DATED FROM : <?= date('d-m-Y', strtotime($from_dt)) ?> TO <?= date('d-m-Y', strtotime($to_dt)) ?> </th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center; vertical-align: middle;">
                <?php
                    $padvname = "";
                    $radvname = "";
                    $res = $model->getDropNotes($from_dt, $to_dt);
                    if (!empty($res))
                    {
                        ?>
                        <div style="text-align: center;">
                            <table border="1" style="font-size:12px; text-align: center; background: #ffffff;" cellspacing=0>
                                <tr>
                                    <td style="text-align:left" colspan="6"><U>DROP NOTE</U>:-</td>
                                </tr>
                                <tr>
                                    <td style="text-align:left">Item No.</td>
                                    <td style="text-align:left">Case No.</td>
                                    <td style="text-align:left">Petitioner/Respondent</td>
                                    <td style="text-align:left">Advocate</td>
                                    <td style="text-align:left">Reason</td>
                                </tr>
                                <?php
                                foreach ($res as $row)
                                {
                                    ?>
                                    <tr>
                                        <td style="text-align:left">
                                            <?php echo $row['clno'] ?>
                                        </td>
                                        <td style="text-align:left">
                                            <?php echo $row['case_no'] ?>
                                        </td>
                                        <td style="text-align:left">
                                            <?php echo $row['pname'];
                                            if ($row['rname'] != "")
                                            {
                                                echo "<br>Vs.<br/>" . $row['rname'];
                                            }
                                            ?>
                                            </td>
                                            <td style="text-align:left">
                                                <?php
                                                    $padvname = "";
                                                    $radvname = "";                           
                                                    $resultsadv = $model->getPadvname($row["diary_no"]);
                                                    if (!empty($resultsadv))
                                                    {
                                                        $rowadv = $resultsadv;
                                                        $radvname =  $rowadv["r_n"];
                                                        $padvname =  $rowadv["p_n"];
                                                    }
                                                    $padvname = !empty($padvname) ? trim($padvname, ",") : "";
                                                    $radvname = !empty($radvname) ? trim($radvname, ",") : "";
                                                    
                                                    echo strtoupper(str_replace(",", ", ", $padvname)) . "<br/><br/>" . 
                                                         strtoupper(str_replace(",", ", ", $radvname));
                                                    

                                                ?>
                                            </td>
                                            <td style="text-align:left">
                                                <?php echo $row['nrs'] ?>
                                            </td>
                                        </tr>
                                    <?php
                                }
                                ?>
                            </table>
                        </div>
                        <?php
                    }

                ?>
            </th>
        </tr>

    </table>
    <br>
    <p align='left' style="font-size: 12px;"><b>NEW DELHI<BR /><?php date_default_timezone_set('Asia/Kolkata');
                                                                echo date('d-m-Y H:i:s'); ?></b>&nbsp; &nbsp;</p>
    <br>
    <p align='right' style="font-size: 12px;"><b>ADDITIONAL REGISTRAR</b>&nbsp; &nbsp;</p>
</div>
<div style="width: 100%; padding-bottom:1px; background-color: #ddf1f9; text-align: center; border-top: 1px solid #000; position: fixed; bottom: 0; left: 0; right: 0; z-index: 0; display:block;">
    <span id="toggle_hw" style="color: #0066cc; font-weight: bold; cursor: pointer; padding-right: 1px;">
    </span>
    <input name="prnnt1" type="button" id="prnnt1" value="Print">

</div>