<?php

$result=$this->copyRequestModel->getActiveRejectionReasons();
?>
<!-- Modal Header -->
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
                if (!empty($result)) {
                    foreach($result as $row) {
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
    <button id="btn_asset_reject" class="btn btn-success" type="button"
            data-diary_no="<?=$_POST['diary_no'];?>"
            data-mobile="<?=$_POST['mobile'];?>"
            data-email="<?=$_POST['email'];?>"
            data-asset_type="<?=$_POST['asset_type'];?>"
            data-crn="<?=$_POST['crn'];?>" data-asset-table-id='<?=$_POST['asset_table_id'];?>'>Reject</button>
</div>
