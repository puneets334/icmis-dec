<div id="prnnt" style="font-size:12px;">
    <table border="0" width="100%" style="font-size:12px; text-align: left; background: #ffffff;" cellspacing=0>
        <tr>
            <th colspan="4" style="text-align: center;"><img src="<?= base_url('images/scilogo.png') ?>" width="50px" height="80px" /></th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;">SUPREME COURT OF INDIA</th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;">ADVANCE CAUSE LIST DROP NOTES FOR DATED : <?= date('d-m-Y', strtotime($from_dt)) ?> </th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center; vertical-align: middle;">
                <?php $getNotes = $model->getNotes($list_dt ,$board_type)
               
                ?>
                 <?php if (!empty($getNotes)) : ?>
                    <div style="text-align: center;">
                        <table border="1" style="font-size:12px; text-align: center; background: #ffffff;" cellspacing=0 class="table table-striped table-bordered">
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
                            <?php foreach ($getNotes as $row) : ?>
                                <tr>
                                    <td style="text-align:left"><?= $row['clno'] ?></td>
                                    <td style="text-align:left"><?= $row['case_no'] ?></td>
                                    
                                    <td style="text-align:left">
                                        <?= $row['pname'] . ($row['rname'] != "" ? "<br>Vs.<br/>" . $row['rname'] : "") ?>
                                    </td>
                                    <td style="text-align:left">
                                        <?php
                                        $padvname = "";
                                        $radvname = "";
                                        $rowadv = $model->getAdvocate($row['diary_no']);
                                        if (!empty($rowadv)) {
                                            $radvname = $rowadv["r_n"];
                                            $padvname = $rowadv["p_n"];

                                            if (!empty($padvname)) {
                                                $padvname = strtoupper(str_replace(",", ", ", trim($padvname, ",")));
                                            }

                                            if (!empty($radvname)) {
                                                $radvname = strtoupper(str_replace(",", ", ", trim($radvname, ",")));
                                            }

                                            echo $padvname . "<br/><br/>" . $radvname;
                                        } else {
                                            echo "--"; 
                                        }
                                        ?>
                                    </td>

                                    <!-- <td style="text-align:left"> -->
                                        <?php
                                        /* NOTE -> p_r_id colunm not found in query

                                        $row['p_r_id']='';
                                        if($row['p_r_id'] == 0){
                                            echo "-";
                                        }
                                        else{
                                            if($row['p_r_id'] == $row['roster_id']){
                                                echo "Item No. ".$row['p_brd_slno'];
                                            }
                                            else{
                                                
                                            
                                                
                                                $rowsqq = $model->getCourtNo($row['p_r_id']);
                                                if($row['c_status'] == 'D'){
                                                    $dispose_flag = " Disposed";
                                                }
                                                else{
                                                    $dispose_flag = " ";
                                                }
                                                echo "Court No. ".$rowsqq['courtno']." as Item No. ".$row['p_brd_slno']." ".$dispose_flag." On ".date('d-m-Y', strtotime($row['p_next_dt']));
                                            }
                                        }
                                              */

                                        ?>
                                      
                                    <!-- </td> -->





                                    <td style="text-align:left"><?= $row['nrs'] ?></td>

                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                <?php else : ?>
                  
                    <span style="color: red; text-align: center">No records found</span>
                <?php endif; ?>
            </th>
        </tr>
    </table>
    <br>
    <p align='left' style="font-size: 12px;"><b>NEW DELHI<BR /><?= date('d-m-Y H:i:s') ?></b>&nbsp; &nbsp;</p>
    <br>
    <p align='right' style="font-size: 12px;"><b>ADDITIONAL REGISTRAR</b>&nbsp; &nbsp;</p>
</div>

<div style="width: 100%; padding-bottom:1px; background-color: #ddf1f9; text-align: center; border-top: 1px solid #000; position: fixed; bottom: 0; left: 0; right: 0; z-index: 0; display:block;">
    <input name="prnnt1" type="button" id="prnnt1" value="Print">
</div>