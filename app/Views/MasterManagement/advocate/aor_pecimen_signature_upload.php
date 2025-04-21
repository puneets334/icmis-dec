<?= view('header') ?>
<style>
    .form-style-10 {
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background: #f9f9f9;
    }

    .form-label {
        font-weight: bold;
    }

    .datepicker {
        width: 100%;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Master Management >>Advocate </h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">AOR-Specimen Signature Upload</h4>
                                </div>
                                <div id="loginbox" style="margin-top:20px;" class="mainbox">
                                    <div class="panel panel-info" id="addMenusDiv">
                                        <div style="margin-top: 10px" class="panel-body">

                                            <div class="alert hide"></div>
                                            <div class="container mt-5">
                                                <form method="post" action="#" id="Refreshfrom">
                                                    <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                                                    <div class="">
                                                        <!-- <div class="text-center mb-4">
                                                                    <h2>AOR-Specimen Signature Upload</h2>
                                                                </div> -->
                                                        <div class="row mb-3">

                                                            <div class="col-sm-12 col-md-3 mb-3">
                                                                <label for="aor" class="">AOR Name:</label>
                                                                <select class="form-select AORCode" id="aor" onChange="Check_if_file_exists()">
                                                                    <!--  -->
                                                                    <option value="">Choose...!</option>
                                                                    <?php foreach ($advocate as $row) { ?>
                                                                        <option value=<?= $row['aor_code'] ?>><?= $row['aor_code'] ?>:<?= $row['adv_name'] ?></option>
                                                                    <?php } ?>
                                                                </select>

                                                            </div>

                                                            <div class="col-sm-12 col-md-3 mb-3">
                                                                <label for="status" class="">Select file:</label>
                                                                <input type="file" class="form-control" name="" accept="application/pdf" id="file1">
                                                                <img id="image" src="/supreme_court/images/load.gif" class="d-none mt-2">
                                                                <button type="button" class="quick-btn mt-26" id="upload" onclick="uploadpdf()">Upload</button>
                                                                <div class="text-center mt-2" id="record"></div>

                                                            </div>

                                                            <div class="col-sm-12 col-md-3 mb-3 div_reupload" style="display:none">
                                                                <label for="status" class="">Please select a new file:</label>
                                                                <input type="file" class="form-control" name="" id="file2" accept="application/pdf">
                                                                <button type="button" class="quick-btn mt-26" id="upload" onclick="uploadpdfrev()">Re-Upload</button>
                                                                <div class="text-center mt-2" id="record"></div>

                                                            </div>
                                                            <div class="text-center mt-3">
                                                                <div style="color:red" id="div_result" class="mt-2"></div>
                                                            </div>
                                                            <div class="text-center mt-3">
                                                                <img id="image" src="" style="display:none;" alt="Loading">
                                                            </div>
                                                            <div id="record" class="mt-3 Datacenter"></div>
                                                            <input type="hidden" id="old_file">
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="dv_res">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function Check_if_file_exists() {
        $('#div_result').html("");
        var aor_code = $('#aor').val();
        var file_name = aor_code + '.pdf';
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "GET",
            url: baseURL + "/MasterManagement/Advocate/CheckFilesExist",
            data: {
                fname: file_name,
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
            },
            success: function(response) {

                if (response.exists == true) {
                    var result = confirm("File Already Exists !!. Do you want to Reupload ?");
                    if (result == true) {
                        $('#div_result').html('');
                        $('.div_reupload').show();
                        $('#old_dv').hide();
                    }
                } else {
                    $('.div_reupload').hide();
                    $('#old_dv').show();
                    $('#div_result').html('');
                }
            },
            error: function() {
                alert("ERROR");
            }
        });
    }

    function uploadpdf() {
        var aor_code = $('#aor').val();
        var fileInput = document.getElementById('file1');
        var upd_file = fileInput.value;
        var file = fileInput.files[0];
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        if (!file) {
            alert('Please select a PDF file to upload');
            fileInput.focus();
            return false;
        }

        if (file.size > 12 * 1024 * 1024) {
            alert('File must be less than 12 MB');
            fileInput.focus();
            return false;
        }

        if (!/\.pdf$/i.test(upd_file)) {
            alert('Only PDF files are allowed');
            fileInput.focus();
            return false;
        }

        var data = new FormData();
        data.append('file', file);
        data.append('aor_code', aor_code);
        data.append('upd_file', upd_file);

        $('#submitBtn1').prop("disabled", true);

        $.ajax({
            url: baseURL + "/MasterManagement/Advocate/UploadFilestore",
            type: 'POST',
            data: data,
            cache: false,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-Token': CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#dv_res').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            success: function(response) {
                $('#dv_res').html(""); // Clear loading
                if (response.status == 0) {
                    alert('Successfully Uploaded AOR Specimen Form');
                    $('#div_result').html("Successfully Uploaded AOR Specimen Form");
                    $('#file1').val('');
                    location.reload();
                    updateCSRFToken();
                } else if (response.status == 1) {
                    alert('The file you are trying to upload already exists.');
                    $('#div_result').html("<font color='#DE3163'>File Already Exists !!</font>");
                    updateCSRFToken();
                }
                $('#submitBtn1').prop("disabled", false);
            },
            error: function(xhr) {
                $('#dv_res').html(""); // Clear loading
                alert('An error occurred: ' + xhr.status + ' ' + xhr.statusText);
                $('#submitBtn1').prop("disabled", false);
            }
        });
    }

    function uploadpdfrev() {
        var aor_code = $('#aor').val();
        var old_file = $('#old_file').val();
        var fileInput = document.getElementById('file2');
        var upd_file = fileInput.value;
        var file = fileInput.files[0];
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        if (!file) {
            alert("Please select a PDF file to upload");
            return false;
        }

        if (file.size > 12 * 1024 * 1024) {
            alert('File must be less than 12 MB');
            fileInput.focus();
            return false;
        }

        if (!/\.pdf$/i.test(upd_file)) {
            alert('Only PDF files are allowed');
            fileInput.focus();
            return false;
        }

        var data = new FormData();
        data.append('file', file);
        data.append('old_file', aor_code);
        data.append('upd_file', upd_file);

        $('#submitBtn2').prop("disabled", true);

        $.ajax({
            url: baseURL + "/MasterManagement/Advocate/ReUploadFiles",
            type: 'POST',
            data: data,
            cache: false,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-Token': CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#dv_res').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            success: function(data) {
                $('#dv_res').html(""); // Clear loading
                if (data.status == 200) {
                    alert(data.message);
                    location.reload();
                    updateCSRFToken();
                }
                $('#file2').val('');
                $('#file1').val('');
                $('#submitBtn2').prop("disabled", false);
            },
            error: function(xhr) {
                $('#dv_res').html(""); // Clear loading
                alert("Error: " + xhr.status + " " + xhr.statusText);
                updateCSRFToken();
                $('#submitBtn2').prop("disabled", false);
            }
        });
    }
</script>