<?= view('header') ?>

<style>
.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f6f6f6;
    width: 35vw;
    overflow: auto;
    border: 1px solid #ddd;
    z-index: 1;
}

.dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.dropdown-content a:hover {
    background-color: #ddd;
}

.card-header img {
    margin-bottom: 10px;
}

/* .modal {
    padding: 0 !important;  
}
.modal .modal-dialog {
    width: 100%;
    max-width: none;
    height: 100%;
    margin: 0;
}
.modal .modal-content {
    height: 100%;
    border: 0;
    border-radius: 0;
}
.modal .modal-body {
    overflow-y: auto;
} */
</style>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <h3 class="card-title">Library Resources List</h3>
                    </div>
                    <div class="card-body">
                    <p id="show_error"></p>
                        <form id="dateForm" method="post">
                            <?= csrf_field() ?>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="cause_date" class="col-form-label">List Date</label>
                                        <input type="text" class="form-control bg-white list_date" value="" aria-describedby="list_date_addon" placeholder="Date..." readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="flist" class="col-form-label">Court No.<span style="color:red;">*</span></label>
                                        <select class="form-control courtno" aria-describedby="courtno_addon">
                                        <option value="0">-All-</option>
                                        <?php for ($i = 1; $i <= 22; $i++): ?>
                                            <option value="<?= $i ?>"><?= $i ?></option>
                                        <?php endfor; ?>
                                        <option value="21">21 (Registrar)</option>
                                        <option value="22">22 (Registrar)</option>
                                    </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="sort" class="col-form-label">Status<span style="color:red;">*</span></label>
                                        <select class="form-control status" aria-describedby="status_addon">
                                            <option value="0">-All-</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Completed">Completed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-2 pl-4 mt-4">
                                    <input id="btn_search" name="btn_search" type="button" class="btn btn-success btn-block" value="Search">
                                </div>
                            </div>
                            

                        </form>

 
                        
                        <hr>                   
                        <div class="row " >
                            <div class="col-md-12" id="result"></div>
                            <div class="panel-footer" id="rslt"></div>               
                        </div>

                        

                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>

 

<div class="modal" id="myModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="height:100%;">
                <div class="modal-body" id="modal_body_section" style="height:100%;">
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script> -->
<script>
$(".list_date").datepicker({
    format:'dd-mm-yyyy'
});

$("#btn_search").click(function() {
    $("#result").html("");
    $('#show_error').html("");
    var list_date = $(".list_date").val();
    var courtno = $(".courtno").val();
    var status = $(".status").val();
    var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    if (list_date.length == 0) {
        $('#rslt').text("Please select cause list date").css("color", "red");  
        return false; 
    } 
    
    else
    
    {
        $.ajax({
            url: '<?php echo base_url('Library/ResourcesList/list_process');?>',
            cache: false,
            async: true,
            data: {
                list_date: list_date,
                courtno: courtno,
                status: status,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#result').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            type: 'POST',
            success: function(data) {
                updateCSRFToken();
                $('#rslt').text('');
                $("#result").html(data);
            },
            error: function(xhr) {
                updateCSRFToken();
                $('#rslt').text('');
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    }

});

$(document).on("click", '.btn_upload_modal', function () {
    var list_date = $(this).data('list_date');
    var diary_no = $(this).data('diary_no');
    var library_reference_material = $(this).data('library_reference_material');
    var i_status = $(this).data('i_status');
    var case_no = $(this).data('case_no');
    var cause_title = $(this).data('cause_title');
    var court_no = $(this).data('court_no');
    var item_no = $(this).data('item_no');
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    $("#modal_body_section").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');

    $.ajax({
        url: '<?php echo base_url('Library/ResourcesList/UploadModel'); ?>',
        type: 'POST',
        data: {
            list_date: list_date,
            diary_no: diary_no,
            library_reference_material: library_reference_material,
            i_status: i_status,
            case_no: case_no,
            cause_title: cause_title,
            court_no: court_no,
            item_no: item_no,
            CSRF_TOKEN: CSRF_TOKEN_VALUE
        },
        success: function(data) {
            updateCSRFToken();
            $('#modal_body_section').html(data);
            $('#myModal').modal('show'); 
        },
        error: function(xhr) {
            updateCSRFToken();
            alert("Error: " + xhr.status + " " + xhr.statusText);
        }
    });
});



$(document).on("click", '.btn_upload_save', function () {
        var data1 = new FormData($(this).parents('form')[0]);
        var document_retain = '';
        if($('#document_retain_option_yes').prop('checked') === true){
            document_retain = "Yes";
        }else{
            document_retain = "No";
        }
         
        $.ajax({
            url:'<?= base_url('Library/ResourcesList/upload_modal_save'); ?>',
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'text',
            //data: {form_data:form_data,list_date:list_date,diary_no:diary_no,document_retain:document_retain},
            data: data1,
            beforeSend:function(){
                //$('#modal_body_section').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();
                return myXhr;
            },
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken();
                if(data == 'Uploaded successfully'){
                    $("#myModal .close").click();
                    swal({title: "Success!",text: data,icon: "success",button: "success!"});
                    btn_search();
                }
                else{
                    swal({
                        title: "Error!",
                        text: data,
                        icon: "error",
                        button: "Try Again!"
                    });
                }
                ////
                //$('#modal_body_section').html(data);
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });


    });


   async function btn_search()
    {
        await updateCSRFTokenSync();
        $("#btn_search").click();

    }

</script>
