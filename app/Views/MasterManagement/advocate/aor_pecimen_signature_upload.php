<?= view('header') ?>

<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/menu_assign.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/style.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/all.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>">
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
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Master Management >> Advocate</h3>
                            </div>
                            <div class="col-sm-2"> </div>
                        </div>
                    </div>
                    <br /><br />
                    <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                    <!--start menu programming-->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12"> <!-- Right Part -->
                                <div class="form-div">

                                    <div class="d-block text-center">
                                        <!--<span class="btn btn-danger">Add Menus/ Child</span>-->

                                        <div class="alert alert-success hide" role="alert" id="msgDiv">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <strong></strong>
                                        </div>


                                        <div id="loginbox" style="margin-top:20px;" class="mainbox">
                                            <div class="panel panel-info" id="addMenusDiv">
                                                <div style="margin-top: 10px" class="panel-body">

                                                    <div class="alert hide"></div>
                                                    <div class="container mt-5">
                                                <form method="post" action="#" id="Refreshfrom">
                                                    <div class="form-style-10">
                                                        <div class="text-center mb-4">
                                                        <h2>AOR-Specimen Signature Upload</h2>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="aor" class="col-sm-3 col-form-label form-label">AOR Name:</label>
                                                            <div class="col-sm-6">
                                                                <select class="form-select AORCode" id="aor" onChange="Check_if_file_exists()">
                                                                <!--  -->
                                                                    <option value="">Choose...!</option>
                                                                    <?php foreach($advocate as $row) { ?>
                                                                        <option value=<?=$row['aor_code']?>><?=$row['aor_code']?>:<?=$row['adv_name']?></option>
                                                                     <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3" id="old_dv">
                                                            <label for="status" class="col-sm-3 col-form-label form-label">Select file:</label>
                                                            <div class="col-sm-6">
                                                               <input type="file" class="form-control" name="" accept="application/pdf" id="file1">
                                                                <img id="image" src="/supreme_court/images/load.gif" class="d-none mt-2">
                                                                <button type="button" class="btn btn-primary mt-2" id="upload"  onclick="uploadpdf()">Upload</button>
                                                                <div class="text-center mt-2" id="record"></div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3 div_reupload" style="display:none">
                                                            <label for="status" class="col-sm-3 col-form-label form-label">Please select a new file:</label>
                                                            <div class="col-sm-6">
                                                               <input type="file" class="form-control" name="" id="file2" accept="application/pdf">
                                                                <button type="button" class="btn btn-primary mt-2" id="upload" onclick="uploadpdfrev()">Re-Upload</button>
                                                                <div class="text-center mt-2" id="record"></div>
                                                            </div>
                                                        </div>
                                                        <div class="text-center mt-3">
                                                        <div  style="color:red" id="div_result" class="mt-2"></div>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
<script src="<?= base_url('/Ajaxcalls/menu_assign/menu_assign.js') ?>"></script>

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
            data: { fname: file_name,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                },
            success: function(response) {
              
                if (response.exists == true) {
                    var result  = confirm("File Already Exists !!. Do you want to Reupload ?");
                if (result == true) {
                    $('#div_result').html(''); 
                    $('.div_reupload').show();
                    $('#old_dv').hide();
                }                 
                }else {
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
    var upd_file = $('#file1').val();
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    document.getElementById('old_file').value = upd_file;
    if (upd_file === '') {
        alert('Please select a PDF file to upload');
            document.getElementById('file1').focus();
        return false;
    }
    if (!/\.pdf$/i.test(upd_file)) {
         alert('Only PDF files are allowed');
         document.getElementById('file1').focus();
         return false;
    }
    var data = new FormData();
    data.append('file', document.getElementById('file1').files[0]);
    data.append('aor_code', aor_code);
    data.append('upd_file', upd_file);
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
        success: function(response) {
            if (response.status == 0) {
                    alert('Successfully Uploaded AOR Specimen Form');
                    $('#div_result').html("Successfully Uploaded AOR Specimen Form");
                    $('#file1').val('');
                    location.reload();
                    updateCSRFToken();
                    // $('#Refreshfrom')[0].reset();
            } else if (response.status == 1) {
                       alert('The file you are trying to upload already exists.')
                    $('#div_result').html("<font color='#DE3163'>File Already Exists !!</font>");
                    updateCSRFToken();
                    // $('#Refreshfrom')[0].reset();

            }
        },
        error: function(xhr) {
         alert('An error occurred:' + xhr.status + ' ' + xhr.statusText);
        }
    });
}


    function uploadpdfrev() {
        var aor_code = $('#aor').val();
        var old_file = $('#old_file').val();
        var upd_file = $('#file2').val();
        console.log(aor_code,old_file,upd_file);
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        if (upd_file === '') {
            alert("Please select a PDF file to upload");
            return false;
        }
        if (!/\.pdf$/i.test(upd_file)) {
            
            alert('Only PDF files are allowed');
            document.getElementById('file2').focus();
        } else {
            var data = new FormData();
            data.append('file', document.getElementById('file2').files[0]);
            data.append('old_file', aor_code);
            data.append('upd_file', upd_file);
            
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
                success: function(data) {
                    console.log(data);
                    if (data.status == 200) {
                        // $('#div_result').html("Revised AOR Specimen Form Uploaded Successfully!! " + data.message);
                        // $('#div_result').html(data.message);
                        // $('.div_reupload').hide();
                        // $('#old_dv').show();
                        // $('.AORCode').val('');
                        alert(data.message);
                        location.reload();
                        updateCSRFToken();
                    }
                    $('#file2').val('');
                    $('#file1').val('');
                    // $('#Refreshfrom')[0].reset();
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                    // $('#Refreshfrom')[0].reset();
                    updateCSRFToken();
                }
            });
        }
    }

</script>
