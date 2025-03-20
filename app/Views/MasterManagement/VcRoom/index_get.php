<?php
 
    if($_POST['next_dt'] == ''){//pending from juducial section
        echo "Select Listing Date";
        exit();
    }
    else{
      

$result = $UserManagementModel->getCourtDetails(date("d-m-Y", strtotime($_POST['next_dt'])));
$srno = 1;
?>
        <input type="text" name="prnnt1" class="btn btn-warning" id="prnnt1" value="Print" style="width: auto;">
<div id="print_area" class="col-12 m-0 p-0" >
    <h3>
        <?php
        $list_date = date("d-m-Y", strtotime($_POST['next_dt']));
        if($_POST['flag'] == 1){ //all
            echo "Send VC URL to all AORs/Party-in-Person [List Date : ".$list_date."]";
        }
        if($_POST['flag'] == 2){//received through email/portal
            echo "Send VC URL to all AORs/Party-in-Person whose consent received through eMail/Portal [List Date : ".$list_date."]";
        }
        if($_POST['flag'] == 3){//only to pip
            echo "Send VC URL to Party-in-Person only [List Date : ".$list_date."]";
        }
        ?>
    </h3>
        <?php
        if(!empty($result)){
            ?>

        <table class="table"> 
            <thead>
                <tr>
                    <th>#</th>
                    <th>Court No.</th>
                    <th>Bench</th>
                    <th>Court Details</th>
                    <th>Room URL</th>
                    <th>Item Number(s)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
        <?php 
        foreach ($result as $row) {
            //if coram changed in same court
        

            $rs_is_coram_changed = $UserManagementModel->checkCoramChange($_POST['next_dt'], $row['roster_id']);
            
            $old_coram = ""; $old_url = "";
            if(!empty($rs_is_coram_changed)){
                
                $old_coram = str_replace(",", ", ",$rs_is_coram_changed['old_coram']);
                $old_url = $rs_is_coram_changed['old_url'];
            }
                ?>        
                <tr>
                    <td><?= $srno++; ?></td>
                    <td><?php
                        if($row['courtno'] > 60)
                            echo $courtno = 'R-VC '.($row['courtno'] - 60);
                        else if($row['courtno'] > 30)
                            echo $courtno = 'VC '.($row['courtno'] - 30);
                        else if($row['courtno'] > 20)
                            echo $courtno = 'R '.($row['courtno'] - 20);
                        else
                            echo $courtno = 'C '.$row['courtno'];

                        ?></td>
                    <td>
                        <?php
                        if(!empty($old_coram)){
                            ?><small class="text-danger">Coram changed - </small><br><?php
                        }
                        ?>
                        <?=str_replace(',','<br>',$row['judge_name'])?>
                        <?php
                        if(!empty($old_coram)){
                            ?><br><small class="text-danger"><u>Old Coram</u> : <?=$old_coram?></small><?php
                        }
                        ?>

                    </td>
                    <td><?php
                        if($row['frm_time']){
                            echo 'Time : '.$row['frm_time'].'<br>';
                        }
                        if($row['m_f'] == 'M'){
                            echo "Misc. List ";
                        }
                        else{
                            echo "Regular List ";
                        }
 
                        if($row['board_type_mb'] == 'J'){
                            echo "<br>Before Court ";
                        }
                        if($row['board_type_mb'] == 'S'){
                            echo "<br>Before Single Judge ";
                        }
                        if($row['board_type_mb'] == 'C'){
                            echo "<br>Before Chamber ";
                        }
                        if($row['board_type_mb'] == 'R'){
                            echo "<br>Before Registrar Court ";
                        }

                        ?>
                    </td>
                    <td><input type="text" class="form-control vc_url" data-roster_id="<?=$row['roster_id'];?>" value="<?= $row['vc_url'] != null ? $row['vc_url'] : '';  ?>" minlength="5"/>
                        <?php
                        if(!empty($old_url)){
                            ?><br><small class="text-danger" w-100 p-3"><u>URL Already Sent in this court </u>: <?=$old_url?></small><?php
                        }
                        ?>
                        <div class="vc_url_success" data-roster_id="<?=$row['roster_id'];?>"></div>
                    </td>
                    <td><input type="text" class="form-control vc_item" data-roster_id="<?=$row['roster_id'];?>" value="<?= $row['item_numbers'] != null ? $row['item_numbers'] : '';  ?>"/></td>
                    <td class="action_save_sent" data-roster_id="<?=$row['roster_id'];?>" >
                        <?php
  
                        $consent_data = 0;
                        if($_POST['flag'] == 2){//received through email/portal
                          

                            $consent_data = $UserManagementModel->getVCConsentCount($_POST['next_dt'], $row['roster_id']);
                        if($consent_data > 0){
                            ?>
                            <button type="button" class="btn_save_send p-1 btn btn-success inline" data-courtno="<?=$courtno?>" data-roster_id="<?=$row['roster_id'];?>"  >Save and Send<br>SMS/Email</button>
                            <br><span class="text-success">Total Consent : <?=$consent_data?></span>
                            <?php
                        }
                        else{
                            ?>
                            <br><span class="text-danger">No Consent Received</span>
                            <?php
                        }
                        }
                        else{
                            ?>
                            <button type="button" class="btn_save_send p-1 btn btn-success inline" data-courtno="<?=$courtno?>" data-roster_id="<?=$row['roster_id'];?>" >Save and Send<br>SMS/Email</button>
                        <?php
                        }
                        ?>
                    </td>
                </tr>
            <?php
        }
        ?>
                 </tbody>
        </table>
           <?php
        }
        else{
            echo '<div class="alert alert-danger alert-dismissible"><strong>No Records Found.</strong></div>';
        }

        ?>    
</div>
<?php }
?>

        




