<?php 
    $condition="";
    $condition1="";
?>
<div align="center">
    <button id="declineButton" class="ui-button ui-widget ui-corner-all" onclick="javascript:confirmBeforeDecline();">Save Data</button>
    </div>
<br>


<table id="example20" border="1px solid black" class="display table table-bordered" width="90%" cellspacing="0">
        <thead>
        <tr>
            <th>#</th>
            <th>Case No @ Diary No.</th>
            <th width="40%">Cause Title</th>
            <!--<th>Advocate</th>-->
            <th width="20%" style="text-align: center;">
                Consent
               <!-- <button id="declineButton" class="ui-button ui-widget ui-corner-all" onclick="javascript:confirmBeforeDecline();">Save Data</button>-->
            </th>

        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>#</th>
            <th>Case No @ Diary No.</th>
            <th width="40%">Cause Title</th>
            <!--<th>Advocate</th>-->
            <th width="20%"  style="text-align: center;">Physical/Virtual</th>
        </tr>
        </tfoot>
        <tbody>
        <?php
        $psrno = "1";
        $srNo=0;
        foreach($vacation_advance_list as $r) {
            //var_dump($r);
                if ($r['diary_no'] == $r['conn_key'] OR $r['conn_key'] == 0) {
                    // $print_brdslno = $row['brd_slno'];
                    $print_brdslno=$psrno;
                    $print_srno = $psrno;
                    $con_no = "0";
                    $is_connected = "";
                } else if ($r['main_or_connected'] == 1) {
                    $print_brdslno = "&nbsp;".$print_srno.".".++$con_no;
                    $is_connected = "<span style='color:red;'>Connected</span><br/>";
                }
                          //$srNo++;
                ?>
                <tr>
             <td style="text-align: center;"><?= $print_brdslno;?>
                 <?php
                 if ($is_connected != '') {
                     //$print_srno = "";

                 } else {
                     $print_srno = $print_srno;
                     $psrno++;
                 }

             ?></td>
            <td><?= $r['case_no'];?><br>
            <?=$is_connected;?>
            </td>
                    <td><?= sprintf('%s' ,  $r['cause_title']);?></td>
           <!-- <td><?/*=$r['advocate'];*/?></td>-->


            <td style="text-align: center;">
                <?php // echo $r['declined_by_admin']; //echo $r['is_deleted']; ?>
                    <a>
                    <?PHP
                    if ($r['declined_by_admin'] == 't') {
                        echo "<a class='btn btn-xs btn-danger'   title=\"List\"  onclick=\"javascript:confirmBeforeList($r[diary_no]);\">";
                        ?>
                        <span style='color:red;text-align: center !important;margin-left: 45%;' id="deleteButton" class="ui-icon ui-icon-closethick"></span> Declined</a>
                        <?php
                    } else {
                            if($r['is_fixed'] != 'Y') {
                                /*if ($r['diary_no'] == $r['conn_key'] OR $r['conn_key'] == 0) {
                                    echo "<input type='checkbox' name='vacationList' id='vacationList' value='$r[diary_no]'>";
                                }*/
                                $radioCondition1='';
                                $radioCondition2='';

                                if($r['consent'] == 'P')
                                    $radioCondition1 = 'checked=checked';

                                if($r['consent'] == 'V')
                                    $radioCondition2 = 'checked=checked';



                                echo "<input type='radio' name='$r[diary_no]' id='P_$r[diary_no]' value='P_$r[diary_no]' $radioCondition1><label for='P_".$r['diary_no']."' >Physical</label>&nbsp;&nbsp;";
                                echo "<input type='radio' name='$r[diary_no]' id='V_$r[diary_no]' value='V_$r[diary_no]' $radioCondition2><label for='V_".$r['diary_no']."'>Virtual</label>";
                            }
                            else
                            {
                                echo "<span style='color:green;'>Fixed For <br> Vacation</span><br/>";
                            }
                    }

                ?>
            </td>

        </tr>
    <?php
    }
    ?>
    </tbody>
</table>