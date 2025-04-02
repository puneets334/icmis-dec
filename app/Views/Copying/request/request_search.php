<?=view('header');?> 
<?php
$session=session();
?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Online Requests - Verification Module</h3>
                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                            </div>
                        </div>
                    </div>
                    <!--<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">-->
                    <div class="card-body">
                        
                    <form method="post">
                <p id="show_error"></p> <!-- This Segment Displays The Validation Rule -->
                <div class="card">
                    <div class="card-header bg-primary text-white font-weight-bolder">Online Requests - Verification Module
                    </div>
                    <div class="card-body">
                        <div class="form-row">

                            <div class="row col-12 pl-2">

                                    <div class="input-group mb-3">



                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="copy_status_addon">Application Status<span style="color:red;">*</span></span>
                                        </div>


                                        <!--start Status-->
                                        <label class="radio-inline ml-1">
                                            <input type="radio" name="copy_status" id="copy_status" value="P" checked> Pending
                                        </label>
                                        <label class="radio-inline ml-1">
                                            <input type="radio" name="copy_status" id="copy_status" value="D"> Disposed
                                            <!-- value to be change -->
                                        </label>
                                        <!--end Status-->
                                    </div>
                            </div>

                            <div class="row daterange_action d-none col-12 pl-2">


                                    <div class="input-daterange input-group mb-3" id="app_date_range">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="from_date_addon">From<span style="color:red;">*</span></span>
                                        </div>
                                        <input type="text" class="form-control bg-white from_date" aria-describedby="from_date_addon"  placeholder="From Date..." readonly>
                                        <span class="input-group-text" id="to_date_addon">to</span>
                                        <input type="text" class="form-control bg-white to_date" aria-describedby="to_date_addon"  placeholder="To Date..." readonly>
                                    </div>

                            </div>

                            <!--start section-->
                            <?php
                            $section_list=array(); $multiple=''; $d_none_class = '';
                            $multiple='multiple="multiple"';

                            // Load the database connection
                                $db = \Config\Database::connect();

                                // Check the value of $dcmis_section and set the SQL query accordingly
                                if (!empty($dcmis_section) && ($dcmis_section == 10 || $dcmis_section == 1)) {
                                    $sql_section = "SELECT id, section_name, display, isda 
                                                    FROM master.usersection 
                                                    WHERE display = 'Y' 
                                                    ORDER BY 
                                                        CASE WHEN id IN (10, 61) THEN 1 ELSE 999 END ASC, 
                                                        CASE WHEN isda = 'Y' THEN 2 ELSE 999 END ASC, 
                                                        section_name ASC";
                                } else {
                                   // $d_none_class = "d-none";
                                    $sql_section = "SELECT id, section_name, display, isda 
                                                    FROM master.usersection 
                                                    WHERE display = 'Y' AND id = ?";
                                }

                                // Execute the query
                                if (!empty($dcmis_section) && ($dcmis_section == 10 || $dcmis_section == 1)) {
                                    $query = $db->query($sql_section);
                                } else {
                                    $query = $db->query($sql_section, [$dcmis_section]);
                                }

                                // Fetch results as an object (similar to mysql_fetch_object)
                                $section_list = [];
                                $section_count = $query->getNumRows();

                                if ($section_count > 0) {
                                    $result_section_list = $query->getResultObject();
                                    foreach ($result_section_list as $row) {
                                        $section_list[] = $row;
                                    }
                                }
                            
                            ?>
                            <div class="row col-12 pl-2">

                                <div class="input-group mb-3 <?=$d_none_class?>">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="usersection_addon">Section<span style="color:red;">*</span></span>
                                    </div>
                                    <select class="form-control" name="usersection" id="usersection" <?=$multiple;?>>
                                        <?php foreach($section_list as $row){ $sel = ($dcmis_section==$row->id) ? "selected=selected" : ''; ?>
                                            <option <?php echo $sel;?>  value="<?php echo $row->id;?>"><?php echo $row->section_name;?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                            <!--end section-->

                            <div class="row col-12 pl-2 <?=($dcmis_user_idd != 1 && $dcmis_user_idd!=10) ? 'd-none' : '' ?>">

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="applicant_type_addon">Applicant Type<span style="color:red;">*</span></span>
                                    </div>
                                    <select class="form-control" multiple="multiple" id="applicant_type" aria-describedby="applicant_type_addon" >
                                        <?php
                                        if(in_array_any( [10], $dcmis_multi_section_id ) && ($dcmis_usertype == 50 OR $_SESSION['dcmis_usertype'] == 51 OR $dcmis_usertype == 17)){
                                            if(in_array(1,$applicant_type_array)){
                                                ?>
                                                <option value="1">Advocate on Record</option>
                                                <?php
                                            }
                                            if(in_array(2,$applicant_type_array)){
                                                ?>
                                                <option value="2">Party/Party-in-person</option>
                                                <?php
                                            }
                                            if(in_array(3,$applicant_type_array)){
                                                ?>
                                                <option value="3">Appearing Counsel</option>
                                                <?php
                                            }
                                            if(in_array(4,$applicant_type_array)){
                                                ?>
                                                <!--<option value="4">Third Party</option>-->
                                                <?php
                                            }
                                            if(in_array(6,$applicant_type_array)){
                                                ?>
                                                <option value="6">Authenticated By AOR</option>
                                                <?php
                                            }
                                        }
                                        else if((in_array_any( [10], $dcmis_multi_section_id) && dcmis_usertype != 50 && $dcmis_usertype != 51 && $dcmis_usertype != 17) OR $dcmis_user_idd == 1){
                                            ?>
                                            <option value="1">Advocate on Record</option>
                                            <option value="2">Party/Party-in-person</option>
                                            <option value="3">Appearing Counsel</option>
                                            <!--<option value="4">Third Party</option>-->
                                            <option value="6">Authenticated By AOR</option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row col-12 pl-2 mb-3">
                                <input id="btn_search" name="btn_search" type="button" class="btn btn-success btn-block btn_search_click" value="Search">
                            </div>


                            <div class="row col-md-12 m-0 p-0" id="result"></div>


                            <!--                            <div class="col-md-7" >

                                                        </div>-->

                        </div>


                    </div>

                </div>
            </form>
                </div>
            <div class="col-7 pl-3 mh-100 overflow-auto py-2">
            <!--start pdf split merge-->
            <?php if($session->get('dcmis_section')==10) { ?>
            <div id="pdf_spilt_merge_pages_result" style="display: none;">
                <div class="row">
                    <div class="input-group col-12 mb-3">
                        <input type="text" name="pdf_spilt_merge_pages" id="pdf_spilt_merge_pages" class="form-control" autocomplete="off" onkeyup="this.value=this.value.replace(/[^0-9,\,\-\s]/g,'');" placeholder="1-5,7,9-10">
                        <span id="pdf_spilt_merge_pages_upload_save_button">Merge Now</span>
                    </div>
                </div>
            </div>
            <?php } ?>
            <!--end  pdf split merge-->
            <div class="row" id="pdf_actions" ></div>
            <div class="row mh-100" id="pdf_result" ></div>
        </div>
                </div>
                <div>
        <div class="modal fade " id="myModal" >
            <div class="modal-dialog">
                <div class="modal-content myModal_content">

                </div>
            </div>
        </div>
    </div>
 </div>
        <div id="myModal_full_screen" class="modal">
            <span class="myModal_full_screen_content"></span>
        </div>
        <div class="modal hide fade" id="pdfSplitDialog" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="pdfSplitDialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg" role="document" style="margin-left:5%;min-width:90%;">
            <div class="modal-content myModal_content2">
                <div class="intro">
                    <div class="modal-header">
                        <input type="hidden" class="form-control " name="filePath" id="filePath" value=""/>
                        <input type="hidden" class="form-control " name="totPageCount" id="totPageCount" value=""/>

                        <div class="form-row align-items-center">
                            <div class="col-auto"  >
                                <span class="modal-title " id="pdfSplitDialog" style="font-size: large;font-weight: bold;padding: 3px;">PDF SPLITTING </span>
                            </div>

                            <div class="col-auto" >
                                <label class="sr-only" style="margin-left:25%;" for="inlineFormInput">From Page</label>
                                <input type="number" class="form-control mb-2" id="from_page" placeholder="From Page">
                            </div>
                            <div class="col-auto">
                                <span > <label class="sr-only" for="inlineFormInputGroup" >To Page</label></span>
                                <div class="input-group mb-2">
                                    <input type="number" class="form-control" id="to_page" placeholder="To Page">
                                </div>
                            </div>

                            <div class="col-auto">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="button" class="splitAndUpload btn btn-primary ">Split & Upload</button>
                            </div>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="box-body">
                            <div class="form-row align-items-center">
                                <div class="col-6" style="margin-left:8%" >
                                    <span>From Page: <span id="page_num_from"></span> / <span id="page_count"></span></span>
                                </div>
                                <div class="col-auto">
                                    <span>To Page: <span id="page_num_to"></span> / <span id="page_count"></span></span>
                                </div>
                            </div>
                            <div class="form-row align-items-center">
                                <div class="col-auto"  >
                                    <canvas id="from_page-canvas"></canvas>
                                </div>
                                <div class="col-auto"  >
                                    <canvas id="to_page-canvas"></canvas>
                                </div>
                            </div>



                        </div>

                    </div>
                    <div class="modal-footer">
                    </div>
                </div>

            </div>
        </div>
    </div>
        </div>
        <div class="modal hide fade" id="request_to_fee_clc_for_certificationDialog" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="request_to_fee_clc_for_certificationDialog" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg" role="document" style="margin-left:5%;min-width:90%;">
                <div class="modal-content myModal_content2">
                    <div class="intro">
                        <div class="modal-header">

                            <div class="form-row align-items-center">
                                <div class="col-auto"  >
                                    <span class="modal-title " id="request_to_fee_clc_for_certificationDialog" style="font-size: large;font-weight: bold;padding: 3px;">Enter No. of Documents for Certification & Un-certification with its pages </span>
                                </div>


                            </div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                             <div class="box-body">


                                         <div id="certificationFormLoad"></div>


                            </div>
                              <div class="modal-footer"></div>
                        </div>

                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
    </div>
    <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<script>
    $(function(){
        $('#app_date_range').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            container: '#app_date_range'
        });




    });
    $(document).on("change", "#copy_status", function () {
        var copy_status = $(this).val();
        if(copy_status == 'D'){
            $(".daterange_action").removeClass("d-none");
        }
        else{
            $(".daterange_action").addClass("d-none");
        }
    });
    $("#btn_search").click(function(){
        //$(document).on("click", "#btn_search", function () {
        //var app_status = $("#app_status").val();
        $("#result").html("");
        var usersection = $("#usersection").val();
        //var copy_status = $("#copy_status").val();
        var copy_status = $('input[name="copy_status"]:checked').val();
        var from_date = $(".from_date").val();
        var to_date = $(".to_date").val();
        var applicant_type = $("#applicant_type").val();
        var ignore_applicant_type = '<?=$ignore_applicant_type?>';
        var isda = '<?=$isda?>';
        $('#show_error').html("");
        /*if (copy_status.length == 0) {
            $('#result').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Select Copy Status Required* </strong></div>');
            $("#copy_status").focus();
            return false;
        }*/
        if (copy_status =='' || copy_status==null || copy_status=='undefined') {
            $('#result').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Select Copy Status Required* </strong></div>');
            $("#copy_status").focus();
            return false;
        }
        else if (from_date.length == 0 && copy_status == 'D') {
            $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Application From Date Required* </strong></div>');
            $("#from_date").focus();
            return false;
        } else if (to_date.length == 0 && copy_status == 'D') {
            $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Application To Date Required* </strong></div>');
            $("#to_date").focus();
            return false;
        }
        else if (applicant_type == null && ignore_applicant_type == 'N') {
            $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Applicant Type Required* </strong></div>');
            $("#applicant_type").focus();
            return false;
        }
        else if (usersection == null || usersection =='') {
            $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Section Required* </strong></div>');
            $("#applicant_type").focus();
            return false;
        }
        else{
            $.ajax({
                url:'request_search_get',
                cache: false,
                async: true,
                data: {usersection:usersection,from_date:from_date,to_date:to_date,copy_status:copy_status,applicant_type:applicant_type,isda:isda,ignore_applicant_type:ignore_applicant_type,CSRF_TOKEN:$('[name="CSRF_TOKEN"]').val()},
                beforeSend:function(){
                    $('#result').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                },
                type: 'POST',
                success: function(data, status) {
                    $("#result").html(data);
                    updateCSRFToken();
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                    updateCSRFToken();
                }
            });

        }
    });


    $(document).on('click', '.uploaded_attachments', function () {
        $("#pdf_result").html("");
        var asset_type = $(this).data('asset_type');
        var doc_action = $(this).data('doc_action');
        var crn = $(this).parents('.item_no').data('crn');
        var asset_table_id = $(this).data('asset-table-id');
        var diary_no = $(this).parents('.item_no').data('diary_no');
        var mobile = $(this).data('mobile');
        var email = $(this).data('email');
        var copy_reject_detail = '';
        if(doc_action == 'view'){
            if(asset_type !=6) {
                var id_proof_masterid = $(this).data('id_proof_masterid');

                if(asset_type == 5){
                    $.ajax({
                        type: "POST",
                        url: "party_details_get",
                        data: {diary_no:diary_no,CSRF_TOKEN:$('[name="CSRF_TOKEN"]').val()},
                        cache: false,
                        success: function (data) {
                            $("#pdf_result").html(data);
                            updateCSRFToken();
                        }
                    });
                }
                if (id_proof_masterid == 6) {
                    $.ajax({
                        type: "POST",
                        url: "get_aadhaar_offline_details",
                        data: {mobile: mobile, email: email,CSRF_TOKEN:$('[name="CSRF_TOKEN"]').val()},
                        cache: false,
                        success: function (data) {
                            $("#pdf_result").html(data);
                            updateCSRFToken();
                        }
                    });
                }
                else {
                    var url = $(this).data('attached_path');
                    if (asset_type == 2) {
                        show_png_doc(url);
                    }
                    else if (asset_type == 3) {
                        show_video_doc(url);
                    }
                    else {
                        show_pdf_doc(url);
                    }
                }
            }
        }
        else if(doc_action == 'reject'){
            $("#myModal").modal({backdrop: false});
            $.ajax({
                type: "POST",
                url: "asset_reject",
                data:{diary_no:diary_no, mobile: mobile, email: email,crn:crn,doc_action:doc_action,asset_type:asset_type,asset_table_id:asset_table_id, copy_reject_detail:copy_reject_detail,CSRF_TOKEN:$('[name="CSRF_TOKEN"]').val()},
                cache: false,
                success: function (data) {
                    $(".myModal_content").html(data);
                    updateCSRFToken();
                }
            });
        }
        else{

            swal.fire({
                title: "Are you sure?",
                text: "You want to Accept",
                icon: "warning",
                buttons: [
                    'No, cancel it!',
                    'Yes, I am sure!'
                ],
                dangerMode: true,
            }).then(function(isConfirm) {
                if (isConfirm) {


            $.ajax({
                url:'uploaded_attachments_action',
                cache: false,
                async: true,
                data: {diary_no:diary_no, mobile: mobile, email: email,crn:crn,doc_action:doc_action,asset_type:asset_type,asset_table_id:asset_table_id,copy_reject_detail:copy_reject_detail,CSRF_TOKEN:$('[name="CSRF_TOKEN"]').val()},
                type: 'POST',
                dataType: "json",
                success: function(data, status) {
                    updateCSRFToken();
                    if(data.status == 'success'){
                        swal.fire({
                            title: "Success!",
                            text: "Request Completed",
                            icon: "success",
                            button: "Go Ahead!"
                        }).then(function(){
                            $('.attached_doc_action').filter('[data-doc_id_action="'+asset_table_id+'"]').html("<div class='text-success'><strong>Success</strong></div>");
                        });
                    }
                    else{
                        swal.fire({
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


                } else {
                    swal.fire("Cancelled", "Please try again :)", "error");
                }
            })


        }
    });



    $(document).on("click", "#btn_asset_reject", function () {
        var diary_no = $(this).data('diary_no');
        var mobile = $(this).data('mobile');
        var email = $(this).data('email');
        var asset_type = $(this).data('asset_type');
        var crn = $(this).data('crn');
        var asset_table_id = $(this).data('asset-table-id');
        var copy_reject_detail = $("#copy_reject_detail").val();
        var doc_action = 'reject';
        $(".validation").remove(); // remove it
        if(copy_reject_detail == 0){
            $("#copy_reject_detail").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1 ml-3"><strong>Reject Cause Required</strong></div>');
            $("#copy_reject_detail").focus(); return false;
        }

        $.ajax({
            type: "POST",
            url: "uploaded_attachments_action",
            data: {diary_no:diary_no, mobile: mobile, email: email,crn:crn,doc_action:doc_action,asset_type:asset_type,asset_table_id:asset_table_id,copy_reject_detail:copy_reject_detail,CSRF_TOKEN:$('[name="CSRF_TOKEN"]').val()},
            cache: false,
            dataType: "json",
            success: function (data) {
                //    $(".modal-content").html(data);
                updateCSRFToken();
                if(data.status == 'success'){
                    Swal.fire({
                          title: "Success",
                          text: "Request Rejected",
                          icon: "success",
                          button: "Go Ahead!"
                          }).then((result) => {
                           if (result.isConfirmed) {
                            $("#myModal .close").click();
                            $('.attached_doc_action').filter('[data-doc_id_action="'+asset_table_id+'"]').html("<div class='text-danger'><strong>Rejected</strong></div>");
                           }
                          });
                }
                else{
                    
                    Swal.fire({
                          title: "Error!",
                          text: data.status,
                          icon: "error",
                          button: "Try Again!"
                          }).then((result) => {
                           if (result.isConfirmed) {
                               //location.reload();
                           }
                          });
                }
            }
        });
    });
    $(document).on('click', '.appearing_council', function () {
        $("#pdf_result").html("");
        var doc_action = $(this).data('doc_action');
        var application_id = $(this).data('application_id');
        var crn = $(this).parents('.item_no').data('crn');
        var email = $(this).parents('.item_no').data('email');
        var mobile = $(this).parents('.item_no').data('mobile');

        if(doc_action == 'view'){
            var attached_path = $(this).data('path');
            show_pdf_doc(attached_path);
        }

        else if(doc_action == 'reject'){
            $("#myModal").modal({backdrop: false});
            $.ajax({
                type: "POST",
                url: "appearing_council_reject",
                data:{crn:crn,application_id:application_id,doc_action:doc_action,mobile:mobile,email:email,CSRF_TOKEN:$('[name="CSRF_TOKEN"]').val()},
                cache: false,
                success: function (data) {
                    //$(".modal-content").html(data);
                    updateCSRFToken();
                    $(".myModal_content").html(data);
                }
            });
        }
        else if(doc_action == 'accept'){
            swal.fire({
                title: "Are you sure?",
                text: "You want to Accept",
                icon: "warning",
                buttons: [
                    'No, cancel it!',
                    'Yes, I am sure!'
                ],
                dangerMode: true,
            }).then(function(isConfirm) {
                if (isConfirm) {

            $.ajax({
                url:'appearing_council_accept',
                cache: false,
                async: true,
                data: {application_id:application_id,crn:crn,doc_action:doc_action,mobile:mobile,email:email,CSRF_TOKEN:$('[name="CSRF_TOKEN"]').val()},
                type: 'POST',
                dataType: "json",
                success: function(data, status) {
                    updateCSRFToken();
                    if(data.status == 'success'){
                        swal.fire({
                            title: "Success!",
                            text: "Request Accepted",
                            icon: "success",
                            button: "Go Ahead!"
                        }).then(function(){
                            $('.action_taken').filter('[data-application_id_action="'+application_id+'"]').html("<div class='text-success'><strong>Success</strong></div>");
                        });
                    }
                    else{
                        
                        swal.fire({
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
            } else {
                    swal("Cancelled", "Please try again :)", "error");
                }
            });
            
        }
        else{

        }
    });
    $(document).on("click", "#btn_appearing_council_reject", function () {
        var crn = $(this).data('crn');
        var application_id = $(this).data('application_id');
        var copy_reject_detail = $("#copy_reject_detail").val();
        var mobile = $(this).data('mobile');
        var email = $(this).data('email');
        var doc_action = 'reject';
        $(".validation").remove(); // remove it
        if(copy_reject_detail == 0){
            $("#copy_reject_detail").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1 ml-3"><strong>Reject Cause Required</strong></div>');
            $("#copy_reject_detail").focus(); return false;
        }
        $.ajax({
            type: "POST",
            url: "appearing_council_accept",
            data:{crn:crn,application_id:application_id,copy_reject_detail:copy_reject_detail,mobile: mobile, email: email,doc_action:doc_action,CSRF_TOKEN:$('[name="CSRF_TOKEN"]').val()},
            cache: false,
            dataType: "json",
            success: function (data) {
                //    $(".modal-content").html(data);
                updateCSRFToken();
                if(data.status == 'success'){
                    swal.fire({
                        title: "Success!",
                        text: "Request Rejected",
                        icon: "success",
                        button: "Go Ahead!"
                    }).then(function(){
                            //location.reload(true);
                            $("#myModal .close").click();
                            $('.action_taken').filter('[data-application_id_action="'+application_id+'"]').html("<div class='text-danger'><strong>Rejected</strong></div>");
                        }
                    );
                }
                else{
                    swal.fire({
                        title: "Error!",
                        text: data.status,
                        icon: "error",
                        button: "Try Again!"
                    });
                }
            }
        });
    });
    <!--start js pdf split merge-->
    $(document).on('click', '#pdf_spilt_merge_pages_upload_save', function () {
        //alert('welcome merge split pdf');
        var pdf_spilt_merge_pages = $('#pdf_spilt_merge_pages').val();
        var application_id = $(this).data('application_id');
        var crn = $(this).data('crn');
        var copy_status = $(this).data('copy_status');
        var path = $(this).data('path');
        var path_file = $(this).data('path_file');
        //alert('pdf_merge_spilt_pages='+pdf_spilt_merge_pages+'application_id='+application_id+'crn='+crn+'copy_status='+copy_status+'path='+path+'path_file='+path_file);
        if(pdf_spilt_merge_pages==null  || pdf_spilt_merge_pages==''){
            swal.fire({title: "Error!",text: "Page Nos. Required*",icon: "error",button: "error!"});
            return false;
        }
        else if(application_id==null  || application_id=='')
        {
            swal.fire({title: "Error!",text: "Application ID Required",icon: "error",button: "error!"});
            return false;
        }else if(crn==null  || crn==''){
            swal.fire({title: "Error!",text: "CRN Required",icon: "error",button: "error!"});
            return false;
        }else if(path==null  || path==''){
            swal.fire({title: "Error!",text: "PDF Path Required",icon: "error",button: "error!"});
            return false;
        }else if(path_file==null  || path_file==''){
            swal.fire({title: "Error!",text: "PDF Path File Required",icon: "error",button: "error!"});
            return false;
        }
        else {
            //return false;
            // $("#pdf_result").html('');
            $.ajax({
                type: "POST",
                url: "file_spilt_merge_upload",
                data:{crn:crn,application_id:application_id,copy_status:copy_status,file_url:path,path_file:path_file,pdf_spilt_merge_pages:pdf_spilt_merge_pages,CSRF_TOKEN:$('[name="CSRF_TOKEN"]').val()},
                cache: false,
                dataType: "json",
                success: function (data) {
                    updateCSRFToken();
                    //var resArr = data.split('@@@');
                    if (data.status == 1){
                        swal.fire({title: "Success!",text: "Your Request Successfully Saved",icon: "success",button: "success!"});
                        $("#pdf_result").html('');
                        $("#pdf_spilt_merge_pages").html('');
                        $("#pdf_spilt_merge_pages_upload_save_button").html('');
                        //$('#pdf_spilt_merge_pages_result').hide();
                        //$(".btn_search_click").click();
                        setTimeout(function () {
                            var c_v=crn+'_'+application_id;
                            var condition=$('#data_crn_application_row_'+c_v).val();
                            var conArr = condition.split('@@@');
                            $('#crn_application_row_'+c_v).html('<button type="button" class="p-0 btn btn-primary inline request_to_avaialble '+conArr[0]+'" id="crn_application_row_button_'+c_v+'" data-action_type="pdf_split_merge" data-doc_action="view" data-path_file="'+resArr[2]+'"  data-crn="'+crn+'" data-application_id="'+application_id+'" data-path="'+resArr[1]+'" title="View '+conArr[1]+'"><i class="fa fa-eye" aria-hidden="true"></i></button>');
                            //$("#crn_application_row_button_"+crn+'_'+application_id).click(); //CALL BY JS AFTER PDF MERGE
                            show_pdf_doc(resArr[1]); //resArr[1] as pdf url new path
                            $('#pdf_spilt_merge_pages').val(resArr[3]);
                            $('#pdf_spilt_merge_pages_upload_save_button').html(' <button id="pdf_spilt_merge_pages_upload_save" class="btn btn-success" type="button" data-copy_status="'+copy_status+'" data-crn="'+crn+'" data-application_id="'+application_id+'" data-path="'+resArr[1]+'"  data-path_file="'+resArr[2]+'">Merge Now</button>');

                        }, 2000);
                    }else{
                        swal.fire({title: "Error!",text: "Your Request not save please try again",icon: "error",button: "error!"});
                    }

                }
            });
        }


    });
    //end js pdf split merge
    //UNAVAILABLE DOCUMNETS REQUEST
    $(document).on('click', '.request_to_avaialble', function () {
        $("#pdf_result").html("");
        var doc_action = $(this).data('doc_action');
        var application_id = $(this).data('application_id');
        var crn = $(this).parents('.item_no').data('crn');
        var copy_status = $(this).parents('.item_no').data('copy_status');
        var case_status = $(this).parents('.item_no').data('case_status');
        var diary_no = $(this).parents('.item_no').data('diary_no');
        $('#pdf_spilt_merge_pages_result').hide();
        if(doc_action == 'view'){

            var url = $(this).data('path'); //alert('url='+url);
            //alert('url='+url);
            //var url = "../../../"+attached_path;
            show_pdf_doc(url);
            var action_type = $(this).data('action_type');
            if(action_type=='pdf_split_merge') {
                var crn = $(this).data('crn');
                $('#pdf_spilt_merge_pages').val('');
                var path_file = $(this).data('path_file');
                $('#pdf_spilt_merge_pages_result').show();
                $('#pdf_spilt_merge_pages_upload_save_button').html(' <button id="pdf_spilt_merge_pages_upload_save" class="btn btn-success" type="button" data-copy_status="' + copy_status + '" data-crn="' + crn + '" data-application_id="' + application_id + '" data-path="' + url + '"  data-path_file="' + path_file + '">Merge Now</button>');
            }
        }else if(doc_action == 'send_to_section_report') {
            $("#myModal_full_screen").modal({backdrop: false});
            $.ajax({
                type: "POST",
                url: "request_action_modal_full_screen",
                data: {doc_action: doc_action, crn: crn,CSRF_TOKEN:$('[name="CSRF_TOKEN"]').val()},
                cache: false,
                success: function (data) {
                    updateCSRFToken();
                    $(".myModal_full_screen_content").html(data);
                }
            });
        }else if(doc_action == 'list_DAA') {
            $("#myModal_full_screen").modal({backdrop: false});
            $.ajax({
                type: "POST",
                url: "request_action_modal_full_screen",
                data:{doc_action:doc_action,diary_no:diary_no,CSRF_TOKEN:$('[name="CSRF_TOKEN"]').val()},
                cache: false,
                success: function (data) {
                    updateCSRFToken();
                    $(".myModal_full_screen_content").html(data);
                }
            });

        }else if(doc_action == 'uploaded_previous_pdf_files'){
            $("#myModal_full_screen").modal({backdrop: false});
            $.ajax({
                type: "POST",
                url: "request_action_modal_full_screen",
                data:{crn:crn,application_id:application_id,doc_action:doc_action,copy_status:copy_status,diary_no:diary_no,CSRF_TOKEN:$('[name="CSRF_TOKEN"]').val()},
                cache: false,
                success: function (data) {
                    updateCSRFToken();
                    $(".myModal_full_screen_content").html(data);
                }
            });
        }else if(doc_action == 'reject_copy'){
            $("#myModal").modal({backdrop: false});
            $.ajax({
                type: "POST",
                url: "request_action_modal",
                data:{crn:crn,application_id:application_id,doc_action:doc_action,copy_status:copy_status,CSRF_TOKEN:$('[name="CSRF_TOKEN"]').val()},
                cache: false,
                success: function (data) {
                    updateCSRFToken();
                    $(".myModal_content").html(data);
                }
            });
        }
        else if(doc_action == 'upload_copy'){
            $("#myModal").modal({backdrop: false});
            $.ajax({
                type: "POST",
                url: "request_action_modal",
                data:{crn:crn,application_id:application_id,doc_action:doc_action,copy_status:copy_status,diary_no:diary_no,CSRF_TOKEN:$('[name="CSRF_TOKEN"]').val()},
                cache: false,
                success: function (data) {
                    updateCSRFToken();
                    $(".myModal_content").html(data);
                }
            });
        }
        else if(doc_action == 'sent_to_section_copy'){
            $("#myModal").modal({backdrop: false});
            var usersection = $("#usersection").val();
            $.ajax({
                type: "POST",
                url: "request_action_modal",
                data:{usersection:usersection,crn:crn,application_id:application_id,doc_action:doc_action,copy_status:copy_status,case_status:case_status,diary_no:diary_no,CSRF_TOKEN:$('[name="CSRF_TOKEN"]').val()},
                cache: false,
                success: function (data) {
                    updateCSRFToken();
                    $(".myModal_content").html(data);
                }
            });
        }
        else if(doc_action == 'accept'){
            swal.fire({
                title: "Are you sure?",
                text: "Ready to Accept and send to Requester",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "POST",
                        url: "request_accept_save",
                        data:{crn:crn,application_id:application_id,doc_action:doc_action,copy_status:copy_status,CSRF_TOKEN:$('[name="CSRF_TOKEN"]').val()},
                        cache: false,
                        success: function (data){
                            updateCSRFToken();
                            if(data.trim() == '1'){
                                swal.fire({
                                    title: "Success!",
                                    text: "Request Accepted",
                                    icon: "success",
                                    button: "Done"
                                }).then(function(){
                                    //location.reload(true);
                                    $("#myModal .close").click();
                                    $('.action_taken').filter('[data-application_id_action="'+application_id+'"]').html("<div class='text-success'><strong>Accepted</strong></div>");
                                });
                            }
                            else{
                                swal.fire({
                                    title: "Error!",
                                    text: data,
                                    icon: "error",
                                    button: "Try Again!"
                                });
                            }
                        }
                    });

                }
                else {
                    //swal("Record is safe!");
                }
            });

        }else{

        }
    });

$(document).on("click", "#btn_request_send_to_section", function () {
        var crn = $(this).data('crn');
        var application_id = $(this).data('application_id');
        var section_remark = $("#section_remark").val();
        var copy_status = $(this).data('copy_status');
        //var rdtn_section_send_to = $('input[name="rdbtn_section_send_to"]:checked').val();

        var rdtn_section_send_to=$('#rdbtn_section_send_to option:selected').val();
        //addon required
        if(rdtn_section_send_to ==null || rdtn_section_send_to == '')
        {
            swal.fire({
                title: "Error!",
                text: 'Send to field is required',
                icon: "error",
                button: "Try Again!"
            });
            return false;
        }


        $(".validation").remove(); // remove it

swal.fire({
                title: "Are you sure?",
                text: "You want to Send",
                icon: "warning",
                buttons: [
                    'No, cancel it!',
                    'Yes, I am sure!'
                ],
                dangerMode: true,
            }).then(function(isConfirm) {
                if (isConfirm) {

        $.ajax({
            type: "POST",
            url: "request_send_to_section",
            data:{crn:crn,application_id:application_id,rdtn_section_send_to:rdtn_section_send_to,copy_status:copy_status,section_remark:section_remark,CSRF_TOKEN:$('[name="CSRF_TOKEN"]').val()},
            cache: false,
            success: function (data) {
                //    $(".modal-content").html(data);
                updateCSRFToken();
                if(data.trim() == '1'){
                    swal.fire({
                        title: "Success!",
                        text: "Request Sent",
                        icon: "success",
                        button: "Done"
                    }).then(function(){
                            //location.reload(true);
                            $("#myModal .close").click();
                            $('.action_taken').filter('[data-application_id_action="'+application_id+'"]').html("<div class='text-danger'><strong>Sent</strong></div>");
                        }
                    );
                }
                else{
                    swal.fire({
                        title: "Error!",
                        text: data,
                        icon: "error",
                        button: "Try Again!"
                    });
                }
            }
        });
        
         } else {
                    swal.fire("Cancelled", "Request Cancelled :)", "error");
                }
        });

        
    });

    $(document).on("click", "#btn_request_reject", function () {
        var crn = $(this).data('crn');
        var application_id = $(this).data('application_id');
        var copy_reject_detail = $("#copy_reject_detail").val();

        $(".validation").remove(); // remove it
        if(copy_reject_detail == 0){
            $("#copy_reject_detail").parent().after('<div class="validation alert alert-danger alert-dismissible p-1 m-1 ml-3"><strong>Reject Cause Required</strong></div>');
            $("#copy_reject_detail").focus(); return false;
        }
        $.ajax({
            type: "POST",
            url: "request_reject_save",
            data:{crn:crn,application_id:application_id,copy_reject_detail:copy_reject_detail,CSRF_TOKEN:$('[name="CSRF_TOKEN"]').val()},
            cache: false,
            success: function (data) {
                //    $(".modal-content").html(data);
                updateCSRFToken();
                if(data.trim() == '1'){
                    swal.fire({
                        title: "Success!",
                        text: "Request Rejected",
                        icon: "success",
                        button: "Go Ahead!"
                    }).then(function(){
                            //location.reload(true);
                            $("#myModal .close").click();
                            $('.action_taken').filter('[data-application_id_action="'+application_id+'"]').html("<div class='text-danger'><strong>Rejected</strong></div>");
                        }
                    );
                }
                else{
                    swal.fire({
                        title: "Error!",
                        text: data,
                        icon: "error",
                        button: "Try Again!"
                    });
                }
            }
        });
    });

    $(document).on("click", "#btn_request_upload", function () {
        var crn = $(this).data('crn');
        var application_id = $(this).data('application_id');
        var copy_status = $(this).data('copy_status');

        var data1 = new FormData();
        data1.append('file',document.getElementById('browse_copy').files[0]);
        data1.append('CSRF_TOKEN',$('[name="CSRF_TOKEN"]').val());
        
        $.ajax
        ({
            url: "request_upload_save?crn="+crn+"&application_id="+application_id+"&copy_status="+copy_status,
            cache: false,
            dataType:"json",
            contentType: false,
            processData: false,
            data: data1,
            beforeSend:function(){
                $('#loading_result').html('Uploading...');
            },
            type: 'post',
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();
                return myXhr;
            },
            success: function(data)
            {
                updateCSRFToken();
                $('#loading_result').html('');
                if(data.status == '1'){
                    swal.fire({
                        title: "Success!",
                        text: "Uploaded",
                        icon: "success",
                        button: "Go Ahead!",
                    }).then(function(){
                            $("#myModal .close").click();
                            if(copy_status == 'C')
                                $('.action_taken').filter('[data-application_id_action="'+application_id+'"]').append('<button type="button" class="p-1 btn btn-success inline request_to_avaialble" data-doc_action="accept" data-application_id="'+application_id+'" title="Accept"><i class="fa fa-check" aria-hidden="true"></i></button><div class="text-danger"><strong>Uploaded</strong></div>');
                            else
                                $('.action_taken').filter('[data-application_id_action="'+application_id+'"]').html('<div class="text-danger"><strong>Uploaded</strong></div>');

                        }
                    );
                }
                else{
                    swal.fire({
                        title: "Error!",
                        text: data,
                        icon: "error",
                        button: "Try Again!",
                    })
                }
            }
        });
    });

$(document).on("click", ".request_to_fee_clc_for_certification", function () {
        event.preventDefault();
        var application_id = $(this).data('application_id');
        var request_status = $(this).data('request_status');
        var fee_clc_path = $(this).data('path');

        if(application_id==null  || application_id=='')
        {

            swal.fire({title: "Error!",text: "Application ID Cannot be left blank",icon: "error",button: "error!"});
            return false;
        }
        else {

            $("#fee_clc_application_id").val('');
            $("#fee_clc_request_status").val('');
            $("#fee_clc_path").val('');
            //alert('request_status='+request_status+'application_id='+application_id);
            $.ajax({
                type: "POST",
                url: "request_fee_clc_for_certification_save",
                data:{fee_clc_application_id:application_id,fee_clc_request_status:request_status,fee_clc_path:fee_clc_path,fee_clc_request_form_status:'display',CSRF_TOKEN:$('[name="CSRF_TOKEN"]').val()},
                cache: false,
                success: function (data)
                {
                    updateCSRFToken();
                    $('#certificationFormLoad').html(data);
                    //$("#fee_clc_application_id").val(application_id);
                    //$("#fee_clc_request_status").val(request_status);

                    $("#request_to_fee_clc_for_certificationDialog").modal({backdrop: false});
                    //   $("#myModal").modal("hide");
                    $('#request_to_fee_clc_for_certificationDialog').modal('show');

                }
            });




        }

    });

    $(document).on("click","#fee_clc_for_certificationSubmit",function() {
        var fee_clc_request_form_status = $("#fee_clc_request_form_status").val();
        var fee_clc_application_id = $("#fee_clc_application_id").val();
        var fee_clc_request_status = $("#fee_clc_request_status").val();
        var total_path_pages = $("#total_path_pages").val();
        var zero=0;
        if($("#certification_no_doc").val() == '' || $("#certification_no_doc").val() == 'undifined') { $("#certification_no_doc").val(zero); var certification_no_doc = zero; }else{var certification_no_doc = $("#certification_no_doc").val();}

        if($("#certification_pages").val() == '' || $("#certification_pages").val() == 'undifined') { $("#certification_pages").val(zero); var certification_pages = zero;}else{var certification_pages = $("#certification_pages").val();}

        if($("#uncertification_no_doc").val() == '' || $("#uncertification_no_doc").val() == 'undifined') { $("#uncertification_no_doc").val(zero); var uncertification_no_doc = zero;}else{var uncertification_no_doc = $("#uncertification_no_doc").val();}

        if($("#uncertification_pages").val() == '' || $("#uncertification_pages").val() == 'undifined') { $("#uncertification_pages").val(zero); var uncertification_pages = zero;}else{var uncertification_pages = $("#uncertification_pages").val();}


       // alert('fee_clc_application_id='+fee_clc_application_id+'certification_no_doc='+certification_no_doc+'certification_pages='+certification_pages+'uncertification_no_doc='+uncertification_no_doc+'uncertification_pages='+uncertification_pages+'fee_clc_request_form_status='+fee_clc_request_form_status);
        if(certification_no_doc == 0 && certification_pages == 0 && uncertification_no_doc == 0 && uncertification_pages == 0)
        {
            swal.fire({
                title: "Error!",
                text: 'field Can not be left blank one field is required',
                icon: "error",
                button: "Try Again!"
            });
            return false;
        }else if(certification_no_doc > 0 && certification_pages == 0){

            swal.fire({
                title: "Error!",
                text: 'Please enter certification pages number',
                icon: "error",
                button: "Try Again!"
            });
            return false;

        }else if(certification_no_doc == 0 && certification_pages > 0){

            swal.fire({
                title: "Error!",
                text: 'Please enter certification number of documents',
                icon: "error",
                button: "Try Again!"
            });
            return false;

        }else if(uncertification_no_doc > 0 && uncertification_pages == 0){

            swal.fire({
                title: "Error!",
                text: 'Please enter uncertification pages number',
                icon: "error",
                button: "Try Again!"
            });
            return false;

        }else if(uncertification_no_doc == 0 && uncertification_pages > 0){

            swal.fire({
                title: "Error!",
                text: 'Please enter uncertification pages number',
                icon: "error",
                button: "Try Again!"
            });
            return false;

        }


        if(fee_clc_application_id == '' || fee_clc_application_id == 0)
        {
            swal.fire({
                title: "Error!",
                text: 'Application-id cannot be left blank',
                icon: "error",
                button: "Try Again!"
            });
            return false;
        }
        if(fee_clc_request_status == '' || fee_clc_request_status == '')
        {
            swal.fire({
                title: "Error!",
                text: 'Request status cannot be left blank',
                icon: "error",
                button: "Try Again!"
            });
            return false;
        }
        else
        {
            swal.fire({
                title: "Are you sure?",
                text: "Entered data is ok",
                icon: "warning",
                buttons: [
                    'No, cancel it!',
                    'Yes, I am sure!'
                ],
                dangerMode: true,
            }).then(function(isConfirm) {
                if (isConfirm) {
                   // alert('fee_clc_application_id='+fee_clc_application_id+'certification_no_doc='+certification_no_doc+'certification_pages='+certification_pages+'uncertification_no_doc='+uncertification_no_doc+'uncertification_pages='+uncertification_pages+'fee_clc_request_form_status='+fee_clc_request_form_status);

                    $.ajax({
                        type: "POST",
                        url: "request_fee_clc_for_certification_save",
                        data:{fee_clc_application_id:fee_clc_application_id,fee_clc_request_status:fee_clc_request_status,certification_no_doc:certification_no_doc,certification_pages:certification_pages,uncertification_no_doc:uncertification_no_doc,uncertification_pages:uncertification_pages,total_path_pages:total_path_pages,fee_clc_request_form_status:fee_clc_request_form_status,CSRF_TOKEN:$('[name="CSRF_TOKEN"]').val()},
                        cache: false,
                        success: function (data)
                        {

                            updateCSRFToken();
                            var msg='';
                            switch (data){
                                case '1':
                                {
                                    swal.fire({title: "Success!",text: "Your Request Successfully Saved",icon: "success",button: "success!"});
                                    setTimeout(function () {

                                        $('#request_to_fee_clc_for_certificationDialog').data('bs.modal',null);
                                        $("#request_to_fee_clc_for_certificationDialog .close").click();

                                        $('#request_to_fee_clc_for_certificationDialog').modal('hide');
                                        //$(".btn_search_click").click();
                                    }, 1000);
                                    break;
                                }
                                case '2':
                                {
                                    swal({title: "Error!",text: "Data not saved : Entered pages and total pages in pdf not matched.",icon: "error",button: "error!"});
                                    break;

                                }

                                case '99':
                                {
                                    swal({title: "Error!",text: "Data not saved please try again",icon: "error",button: "error!"});
                                    break;

                                }
                            }

                        }
                    });

                } else {
                    swal("Cancelled", "Please try again :)", "error");
                }
            })

        }
    });


    $(document).on("click", ".showDspacePDF", function () {

        var dspace_URL = $(this).data('path');
        dspace_URL=dspace_URL.split("bitstream/")[1]
        var source = 'http://xxxx:5008/index.php/Dspace/display_dspace4_bitstream_content/'+dspace_URL;
        show_pdf_doc(source);
    });

    $(document).on("click", ".viewOpen", function () {
        event.preventDefault();
        var crn = $(this).parents('.item_no').data('crn');
        var dspace_URL = $(this).data('path');
        $("#filePath").val(dspace_URL);
        var crn =$("#splitRadio input[type='radio']:checked").data('crn');
        if(crn==null  || crn=='')
        {

            swal({title: "Error!",text: "Application ID Cannot be left blank",icon: "error",button: "error!"});
            return false;
        }
        else {

            $("#pdfSplitDialog").modal({backdrop: false});
         //   $("#myModal").modal("hide");
            $('#pdfSplitDialog').modal('show');
            //$('.myModal_content').html("");
            //$('.myModal_content2').html('');






            $("#from_page").val('');$("#to_page").val('');
            var dspace_URL = $(this).data('path');
            dspace_URL=dspace_URL.split("bitstream/")[1]
            var url = 'http://XXXX:5008/index.php/Dspace/display_dspace4_bitstream_content/'+dspace_URL;

            // If absolute URL from the remote server is provided, configure the CORS
            // header on that server.
            // var url = 'https://raw.githubusercontent.com/mozilla/pdf.js/ba2edeae/web/compressed.tracemonkey-pldi-09.pdf';

            // Loaded via <script> tag, create shortcut to access PDF.js exports.
            //var pdfjsLib = window['pdfjs-dist/build/pdf'];

            // The workerSrc property shall be specified.
            /*pdfjsLib.GlobalWorkerOptions.workerSrc = '//mozilla.github.io/pdf.js/build/pdf.worker.js';*/
            pdfjsLib.GlobalWorkerOptions.workerSrc = '../js/pdf_js/pdf.worker.js';
            var pdfDoc = null,
                pageNum = 1,
                pageRendering = false,
                pageNumPending = null,
                scale = 0.8,
                canvas = document.getElementById('from_page-canvas'),
                canvas1 = document.getElementById('to_page-canvas'),
                ctx = canvas.getContext('2d'),
                ctx1= canvas1.getContext('2d');

            /**
             * Get page info from document, resize canvas accordingly, and render page.
             * @param num Page number.
             */
            // code for pdf js start
            function renderPage(num) {

                pageRendering = true;
                // Using promise to fetch the page
                pdfDoc.getPage(num).then(function(page) {
                    var viewport = page.getViewport({scale: scale});
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page into canvas context
                    var renderContext = {
                        canvasContext: ctx,
                        viewport: viewport
                    };
                    var renderTask = page.render(renderContext);

                    // Wait for rendering to finish
                    renderTask.promise.then(function() {
                        pageRendering = false;
                        if (pageNumPending !== null) {
                            // New page rendering is pending
                            renderPage(pageNumPending);
                            pageNumPending = null;
                        }
                    });
                });

                // Update page counters
                document.getElementById('page_num_from').textContent = num;
            }

            function renderPage1(num) {
                pageRendering = true;
                // Using promise to fetch the page
                pdfDoc.getPage(num).then(function(page) {
                    var viewport = page.getViewport({scale: scale});
                    canvas1.height = viewport.height;
                    canvas1.width = viewport.width;

                    // Render PDF page into canvas context
                    var renderContext = {
                        canvasContext: ctx1,
                        viewport: viewport
                    };
                    var renderTask = page.render(renderContext);

                    // Wait for rendering to finish
                    renderTask.promise.then(function() {
                        pageRendering = false;
                        if (pageNumPending !== null) {
                            // New page rendering is pending
                            renderPage(pageNumPending);
                            pageNumPending = null;
                        }
                    });
                });

                // Update page counters
                document.getElementById('page_num_to').textContent = num;
            }

            /**
             * If another page rendering in progress, waits until the rendering is
             * finised. Otherwise, executes rendering immediately.
             */
            function queueRenderPage(num) {
                if (pageRendering) {
                    pageNumPending = num;
                } else {
                    renderPage(num);
                }
            }

            /**
             * Displays previous page.
             */
            function onPrevPage() {
                if (pageNum <= 1) {
                    return;
                }
                pageNum--;
                queueRenderPage(pageNum);
            }
            //document.getElementById('prev').addEventListener('click', onPrevPage);

            /**
             * Displays next page.
             */
            function onNextPage() {
                if (pageNum >= pdfDoc.numPages) {
                    return;
                }
                pageNum++;
                queueRenderPage(pageNum);
            }
            //document.getElementById('next').addEventListener('click', onNextPage);

            /**
             * Asynchronously downloads PDF.
             */
            pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
                pdfDoc = pdfDoc_;
                //$('.page_count').textContent = pdfDoc.numPages;
                $('span #page_count').html(pdfDoc.numPages);
                $('#totPageCount').val(pdfDoc.numPages);

                // Initial/first page rendering
                renderPage(pageNum);
                renderPage1(pdfDoc.numPages);
            });

            $("#from_page").blur(function() {
                var requestPage=parseInt($(this).val());
                totPages=$('#totPageCount').val();
                if (requestPage==null ||  requestPage=='')
                {
                    ('page_num_to').textContent = totPages;
                    renderPage(1);

                }

                else if(requestPage>totPages) {
                    swal.fire({
                        title: "Error!",
                        text: 'From page cannot be greater than total no of pages',
                        icon: "error",
                        button: "Try Again!"
                    });
                    $(this).val(1);
                    renderPage(1);
                }
                else {
                    renderPage(requestPage);
                }

            });
            /* $("#to_page").focus(function() {
             alert('focus in');
             }*/


            $("#to_page").blur(function() {
                var fromPageNo=parseInt($("#from_page").val());
                var requestPage=parseInt($(this).val());
                totPages= $('#totPageCount').val();

                if (requestPage==null ||  requestPage=='')
                {
                    ('page_num_to').textContent = totPages;
                    renderPage1(totPages);
                }
                else if(requestPage < fromPageNo) {
                    swal.fire({
                        title: "Error!",
                        text: 'To page cannot be less than from page',
                        icon: "error",
                        button: "Try Again!"
                    });
                    $(this).val('');

                    renderPage1(totPages);

                }
                else if(requestPage>totPages) {
                    //alert('hello');
                    swal.fire({
                        title: "Error!",
                        text: 'To page cannot be greater than total no of pages',
                        icon: "error",
                        button: "Try Again!"
                    });
                    $(this).val(totPages);
                    renderPage1(totPages);
                }
                else
                {
                    renderPage1(requestPage);
                }
            });

// code for pdf js end
        }

        //alert(dspace_URL);
        //show_pdf_doc(dspace_URL);
    });
    $('#pdfSplitDialog').on('hidden.bs.modal', function () {

        //$(this).clear();
        $(this).removeData();
        $('#pdfSplitDialog').data('bs.modal',null);

    });

    $(document).on("click",".splitAndUpload",function() {
        var fromPageNo=$("#from_page").val();
        var toPageNo=$("#to_page").val();
        if( fromPageNo == '' || toPageNo == '')
        {
            swal.fire({
                title: "Error!",
                text: 'From and To page No cannot be left blank',
                icon: "error",
                button: "Try Again!"
            });
            return false;
        }
        else if (parseInt(fromPageNo)>parseInt(toPageNo))
        {
            swal.fire({
                title: "Error!",
                text: 'From page cannot be greater than to page ',
                icon: "error",
                button: "Try Again!"
            });
            return false;
        }
        else
        {
            swal.fire({
                title: "Are you sure?",
                text: "You want to Split and Upload the selected portion of a file!",
                icon: "warning",
                buttons: [
                    'No, cancel it!',
                    'Yes, I am sure!'
                ],
                dangerMode: true,
            }).then(function(isConfirm) {
                if (isConfirm) {

                    var crn =$("#splitRadio input[type='radio']:checked").data('crn');
                    var copy_status = $(".item_no").data('copy_status');
                    var application_id=$("#splitRadio input[type='radio']:checked").data('application_id');



                    // alert(crn+"#"+application_id+"#"+copy_status);

                    var path = $("#filePath").val();
                    path=path.split("bitstream/")[1]
                    var source = 'http://XXXX:5008/index.php/Dspace/display_dspace4_bitstream_content/'+path;

                    var page_from = $("#from_page").val();
                    var page_to = $("#to_page").val();
                    $.ajax({
                        type: "POST",
                        url: "file_split_and_upload",
                        dataType:"JSON",
                        data:{file_url:path,from_page_no:page_from,to_page_no:page_to,crn:crn,application_id:application_id,copy_status:copy_status,CSRF_TOKEN:$('[name="CSRF_TOKEN"]').val()},
                        cache: false,
                        success: function (data)
                        {
                            updateCSRFToken();
                            $('#pdfSplitDialog').data('bs.modal',null);
                            $("#pdfSplitDialog .close").click();

                            $('#pdfSplitDialog').modal('hide');

                            //$('#pdfSplitDialog').data('bs.modal',null);
                            //$("#pdfSplitDialog").click();
                            var msg='';
                            switch (data.status){
                                case '1':
                                {
                                    swal.fire({title: "Success!",text: "PDF Split and data Save Successfully",icon: "success",button: "success!"});
                                    break;
                                }
                                case '2':
                                {
                                    swal.fire({title: "Error!",text: "Data not updated or pdf already uploaded",icon: "error",button: "error!"});
                                    msg='Data not updated or pdf already uploaded';
                                    break;
                                }
                                case '99':{swal.fire("Error!", "Error. PDF not split", "error");
                                    swal.fire({title: "Error!",text: "Error. PDF not split",icon: "error",button: "error!"});
                                    msg='Error. PDF not split';
                                    break;}
                            }
                            $('.action_taken').filter('[data-application_id_action="'+application_id+'"]').html('<div class="text-danger"><strong>'+msg+'</strong></div>');
                        }
                    });

                } else {
                    swal.fire("Cancelled", "Please try again :)", "error");
                }
            })

        }
    });

    function show_pdf_doc(url){
        //http://10.40.189.152:8080/sc/bitstream/123456789/81391/1/1-INDEX-CA-2400-2002.pdf_Sign.pdf
        $("#pdf_result").html('<div class="col-md-12">' +
            '<div class="row">' +
            '<object data="'+url+'" type="application/pdf" width="100%" height="900px" internalinstanceid="9" ></object>' +
            '</div>' +
            '</div>')
    }

    function show_png_doc(url){
        $("#pdf_result").html('<div class="p-5 col-md-12"><div class="row text-center"><object data="'+url+'" type="image/png" width="350" height="400px" internalinstanceid="9" ></object></div></div>')
    }
    function show_video_doc(url){
        $("#pdf_result").html('<div class="p-5 col-md-12"><div class="row"><object data="'+url+'" type="video/webm" width="400%" height="400px" internalinstanceid="9" ></object></div></div>')
    }

    $(document).on("change", "#browse_copy", function () {
        var fileName = $(this).val().replace('C:\\fakepath\\', " ");
        //replace the "Choose a file" label
        $(this).next('.custom-file-label').html(fileName);

    });

    $(function() {
        $('#applicant_type').multiselect({
            includeSelectAllOption: true,
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            filterPlaceholder: 'Search for applicant...'
        });
        /*$('#usersection').multiselect({
            includeSelectAllOption: true,
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            filterPlaceholder: 'Search for section...'
        });*/
        
        $('#usersection').multiselect({
            includeSelectAllOption: true,
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            filterPlaceholder: 'Search for section...'
        });
        

    });


    function call_tooltip(ById) {
        var x = document.getElementById(ById);
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
</script>
