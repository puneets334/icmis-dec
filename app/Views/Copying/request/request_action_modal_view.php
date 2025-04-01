<?php
if($_POST['doc_action'] == 'reject_copy'){
?>
    <div class="modal-header">
        <h4 class="modal-title">Reject Reason</h4>          
        <button type="button" class="close" data-dismiss="modal">×</button>
    </div>
    <!-- Modal body -->
    <div class="modal-body">
        <div class="row">
            <div class="input-group col-12 mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="copy_reject_detail_add_addon">Reject Cause<span style="color:red;">*</span></span>
                </div>
                <select class="form-control" id="copy_reject_detail" aria-describedby="copy_reject_detail_add_addon" >
                    <option value="0">-All-</option>
                    <?php
                    if (!empty($sql_role)) {
                        foreach ($sql_role as $row) {
                            ?>
                            <option value="<?=$row['reasons']?>"><?=$row['reasons']?></option>
                            <?php
                        }
                    }
                    ?>
                </select>

            </div>            
        </div>            
    </div>        
    <!-- Modal footer -->
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button id="btn_request_reject" class="btn btn-success" type="button" data-copy_status="<?= $_POST['copy_status']; ?>" data-crn="<?= $_POST['crn']; ?>" data-application_id="<?= $_POST['application_id']; ?>">Reject</button>
        <span id="loading_result"></span>
    </div>
<?php } 
else if($_POST['doc_action'] == 'upload_copy'){
?>
        <!-- Modal Header -->
   <form method='post'  action='' enctype="multipart/form-data">

    <div class="modal-header">
          <h4 class="modal-title">Upload Pdf</h4>
          
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
        
        <div class="form-group">
      
      <div class="custom-file">
        <input type="file" class="custom-file-input form-control" id="browse_copy" accept="application/pdf,application/zip,application/x-zip,application/x-zip-compressed,application/octet-stream">
        <label class="custom-file-label" for="browse_copy">Choose file</label>
      </div>
    </div>  
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button id="btn_request_upload" class="btn btn-success" type="button" data-copy_status="<?=$_POST['copy_status'];?>" data-crn="<?=$_POST['crn'];?>" data-application_id="<?=$_POST['application_id'];?>">Upload</button>
          <span id="loading_result"></span>
        </div>
</form>
<?php } else if($_POST['doc_action'] == 'sent_to_section_copy'){
?>


    <div class="modal-header">

        <h4 class="modal-title">Request Send To </h4>
        <button type="button" class="close" data-dismiss="modal">×</button>
    </div>
    <!-- Modal body -->

    <div class="modal-body">
        <div class="row">
            <div class="input-group col-12 mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">Send to </span>
                </div>
                
                <select class="form-control" name="rdbtn_section_send_to" id="rdbtn_section_send_to" required>
                    <?php foreach($sec_list as $row){ ?>
                        <?php //if($_POST['copy_status'] == 'C'){
                        if(in_array_any([10],$_POST['usersection'])){
                            ?>
                            <?php if($_POST['case_status'] == 'P' && $row->id==$section_id){
                                //if case pending than default selected concerned section
                                ?>
                                <option selected="selected" value="<?php echo $row->id;?>"><?php echo $row->section_name;?></option>

                            <?php } else if($_POST['case_status'] != 'P' && $row->id==61){
                                //if case disposed than default selected record room
                                ?>
                                <option selected="selected" value="<?php echo $row->id;?>"><?php echo $row->section_name;?></option>
                            <?php }else{?>
                                <option value="<?php echo $row->id;?>"><?php echo $row->section_name;?></option>
                            <?php }?>

                        <?php }
                        //else if($_POST['copy_status'] != 'C'  && $row->id==10){
                        else if(in_array_any([10],$_POST['usersection']) != true  && $row->id==10){
                            $sel='selected=selected';
                            ?>
                            <option <?php echo $sel;?> value="<?php echo $row->id;?>"><?php echo $row->section_name;?></option>
                        <?php }?>

                    <?php }?>
                </select>


            </div>
        </div>
        <div class="row">
            <div class="input-group col-12 mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="section_remark_add_addon">Remark</span>
                </div>
                <input type="text" id="section_remark" class="form-control" placeholder="Max 100 Characters" aria-describedby="section_remark_add_addon" autocomplete="off" >
            </div>
        </div>
    </div>
    <!-- Modal footer -->
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button id="btn_request_send_to_section" class="btn btn-success" type="button" data-copy_status="<?= $_POST['copy_status']; ?>" data-crn="<?= $_POST['crn']; ?>" data-application_id="<?= $_POST['application_id']; ?>">Send</button>
        <span id="loading_result"></span>
    </div>


<?php }  
else{ 
    echo "wrong flag";
}


?>