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
    <input type="file" class="custom-file-input form-control" id="browse_copy" accept="application/pdf">
    <label class="custom-file-label" for="browse_copy">Choose file</label>
  </div>
</div>    
        
        
<!--        <div class="row">
        <div class="custom-file-upload">
<label for="browse_copy" class="custom-file-upload1">
<i class="fa fa-upload" aria-hidden="true"></i> Browse
</label>
<input type="file" name="browse_copy" id="browse_copy" accept="application/pdf"/>
</div>    
        
    </div>
    -->
        
    </div>
    
    
    <!-- Modal footer -->
    <div class="modal-footer">
      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      <button id="btn_upload_request_add" class="btn btn-success" type="button" data-copyid="<?=$_POST['copyid'];?>">Upload</button>
    </div>
</form>