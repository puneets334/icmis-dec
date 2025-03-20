<?= view('header') ?>
<div class="modal-header">
    <h5 class="modal-title">Upload Document for <?= htmlspecialchars($cause_title); ?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="uploadForm" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="library_reference_material"
            value="<?= htmlspecialchars($library_reference_material); ?>">

        <div class="form-group">
            <label for="file_upload">Upload File:</label>
            <input type="file" id="file_upload" name="upload_document[]" class="form-control" required multiple>
            <small class="form-text text-muted">You can upload multiple files. Only PDF format is allowed.</small>
        </div>
        <button type="button" class="btn btn-success btn_upload_save">Submit</button>
    </form>
    <div id="loadingSpinner" style="display: none;">Loading...</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).on("click", '.btn_upload_save', function() {
    var data1 = new FormData($(this).parents('form')[0]);
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    data1.append('CSRF_TOKEN', CSRF_TOKEN_VALUE);

    $.ajax({
        url: '<?= base_url('Library/ResourcesList/upload'); ?>',
       // cache: false,
        contentType: false,
        processData: false,
        dataType: 'json', 
        data: data1,
        type: 'POST',
        success: function(data) {
            updateCSRFToken();
            if (data.status === 'Uploaded successfully') {
                $("#myModal .close").click();
                swal({
                    title: "Success!",
                    text: data.status,
                    icon: "success",
                    button: "success!"
                });
                // $("#btn_search").click();
            } else {
                swal({
                    title: "Error!",
                    text: data.status,
                    icon: "error",
                    button: "Try Again!"
                });
            }
        },
        error: function(xhr) {
            updateCSRFToken();
            alert("Error: " + xhr.status + " " + xhr.statusText);
        }
    });
});

</script>