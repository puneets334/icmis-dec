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
                <input type="text" id="copy_reject_detail" class="form-control" placeholder="Max 100 Characters" aria-describedby="copy_reject_detail_add_addon" autocomplete="off" minlength="5" maxlength="100"> 
                                
            </div>
            
        </div>            
        </div>        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button id="btn_reject_request_add" class="btn btn-success" type="button" data-copyid="<?=$_POST['copyid'];?>">Reject</button>
        </div>
