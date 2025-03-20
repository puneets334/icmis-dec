<style>
    .green-text {
    color: green; 
}

.red-text {
    color: red; 
}
    </style>
<?php
    $ifPending=1;
    $ifMain=1;
    
  
?>
<div id='report_result'>

    <input type="hidden" id="fil_hd" value="<?= $dno ?>" />
    <input type="hidden" id="side_hd" value="<?= isset($details['side']) ? $details['side'] : '' ?>" />

    <table align="center" width="100%">
        <tr class="center blue-text">
            <th>
                <?php
                echo "<strong>Case No.- </strong>";
                if (!empty($casetype['fil_no'])) {
                    echo '[M]' . $casetype['short_description'] . substr($casetype['fil_no'], 3) . '/' . $casetype['m_year'];
                }

                if (!empty($casetype['fil_no_fh'])) {
                    $r_case = $casetype['short_description'];
                    echo ',[R]' . $r_case . substr($casetype['fil_no_fh'], 3) . '/' . $casetype['f_year'];
                }

                echo ", <strong> Diary No: </strong>" . substr($dno, 0, -4) . '/' . substr($dno, -4);
                // navigate_diary($_REQUEST['dno']); pending
                ?>
            </th>
        </tr>
    </table>

    <table align="center" id="tb_clr" cellspacing="3" cellpadding="2">
        <?php if (isset($details['c_status']) && $details['c_status'] == 'D'): 
            $ifPending=0;
            ?>
            <tr>
                <th colspan="4" class="center red-text">The Case is Disposed!!!</th>
            </tr>
        <?php endif; ?>

        
        <tr>
            <th colspan="4" class="center blue-text"><?= isset($details['pet_name']) ? $details['pet_name'] : '' ?>
                <span style="color:black"> <strong> - Vs - </strong> </span>
                <?= isset($details['res_name']) ? $details['res_name'] : '' ?>
            </th>
        </tr>

        <tr>
                <th colspan="4" class="center blue-text"><b>Category:</b> <span class="brown-text"><?= $category ?></span></th>
            </tr>
            
        <tr>
            <th colspan="4" class="text-center" style="font-size: 14px;">
                <?php if (isset($main_case['conn_key']) && $main_case['conn_key'] == $dno): ?>
                    This is Main Diary No
                <?php else: ?>
                    <?php $ifMain = 0; ?>
                    This is Connected Diary No, Main Diary No is
                    <span class="red-text"><?= isset($main_case['conn_key']) ? substr($main_case['conn_key'], 0, -4) . '/' . substr($main_case['conn_key'], -4) : '' ?></span>
                <?php endif; ?>
            </th>
        </tr>
    </table>


    <!-- <div align="center" style="border: 1px solid black;"> -->
    <table align="center" class="table-bordered table-striped">
        <tr>
            <th colspan="5" class="center" >Already Entries of List before and not before and coram</th>
        </tr>
        <tr>
    <th><strong>Sr.</strong></th>
    <th><strong>Before/Not before</strong></th>
    <th><strong>Hon. Judge</strong></th>
    <th><strong>Reason</strong></th>
    <th><strong>Entry Date</strong></th>
</tr>
        <?php
        $s = 1;
        if (!empty($hearingDetails)):
            foreach ($hearingDetails as $row):
                $notbef = '';
                if ($row['notbef'] === 'N') {
                    $notbef = 'Not before';
                } elseif ($row['notbef'] === 'B') {
                    $notbef = 'Before/SPECIAL BENCH';
                } elseif ($row['notbef'] === 'C') {
                    $notbef = 'Before Coram';
                }
        ?>
                <tr>
                    <td><?= $s++; ?></td>
                    <td><?= $notbef; ?></td>
                    <td><?= $row['jname']; ?></td>
                    <td><?= $row['res_add']; ?></td>
                    <td><?= $row['ent_dt']; ?></td>
                </tr>
            <?php endforeach; ?>
    </table>
    <!-- </div> -->
    <br>
<?php else: ?>
    <div style="text-align:center; padding:10px;">LIST BEFORE/NOT BEFORE/CORAM NOT FOUND</div>
<?php endif; ?>




<table align="center">
    <tr>
        <td> <strong> Filing Date: <strong></td>
        <td><?= !empty($details['diary_no_rec_date']) ? date('d-M-Y h:i A', strtotime($details['diary_no_rec_date'])) : '--' ?></td>
        <td><strong> Registration Date: <strong></td>
        <td><?= !empty($details['fil_dt']) ? date('d-M-Y h:i A', strtotime($details['fil_dt'])) : '--' ?></td>
    </tr>
    <tr>
        <td><strong> Tentative Cause-List Date: <strong></td>
        <td><?= isset($details['tentative_cl_dt']) ? $details['tentative_cl_dt'] : '--' ?></td>
        <td><strong> Last Order: <strong> </td>
        <td><?= !empty($details['lastorder']) ? $details['lastorder'] : '--' ?></td>
    </tr>
    <tr>
        <td><strong> Next Date: <strong></td>
        <td><?= isset($details['next_dt']) ? $details['next_dt'] : '--' ?></td>
    </tr>
    <tr>
    <td><strong> Advance List Date:<strong></td>
        <td>
            <?php
             $ifAdvanceAllocated=0;
              if ($advance_list):
                $ifAdvanceAllocated=1;
                 ?>
                <select id="advance_list_date" name="advance_list_date">
                    <?php foreach ($advance_list as $date_item): ?>
                        <option value="<?= $date_item['next_dt'] ?>"><?= $date_item['next_dt'] ?></option>
                    <?php endforeach; ?>
                </select>
            <?php else: ?>
                <span class="red-text">No un-published advance list dated <?= isset($details['next_dt']) ? $details['next_dt'] : '--' ?> exist!</span>
            <?php endif; ?>
        </td>

        <td> <strong> Ready/Not Ready : <strong></td>
        <td>
            <?php
            
            $ifReady=0;
            if (isset($details['main_supp_flag']) && $details['main_supp_flag'] == 0):  
                $ifReady=1;
            ?>
                <span class="green-text">Ready</span>
            <?php else: ?>
                <span class="red-text">Not Ready</span>
            <?php endif; ?>
        </td>
    </tr>

</table>



<?php
// pr($details);
  //  if(is_null($details['advance_list_date'])){
    if(!isset ($details['advance_list_date']))
    {
        
      
       if($ifAdvanceAllocated==1 && $ifPending==1 && $ifReady==1 && $ifMain==1)
        {
            ?>
            <div>
                <div>
                    <table align="center" id="tb_clr_n" border="1" style="border-collapse: collapse">
                        <tr>
                            <th colspan="5"><input type="button" value="Add in Advance List" name="savebutton"/>
                            </th>
                        </tr>
                    </table>
                </div>
            </div> 
            <?php
         }
    }
    else
    {
        
        ?>
        <div>
            <div>
                <span style="color: red; text-align: center">Alraedy Allocated in advance List dated <?=$details['advance_list_date']?>.</span>
            </div>
        </div>
        <?php

    }

        ?>

</div>




<style>
    div#sesframe {
        background-color: #c7fabf;
        color: #212221;
        opacity: 0.9;
        filter: alpha(opacity=90);
        /* For IE8 and earlier */
        padding: 5px;
        position: fixed;
        top: 0;
        left: 0;
        margin: auto;
        padding: 5px;
    }
</style>