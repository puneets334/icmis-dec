<!DOCTYPE html>
<html>
<head>
    <!-- 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="expires" content="Sun, 01 Jan 2014 00:00:00 GMT"/>
    <meta http-equiv="pragma" content="no-cache" />
    <title>Faster</title>-->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"> 
    <link rel="stylesheet" href="<?=base_url()?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="<?=base_url()?>/assets/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?=base_url()?>/assets/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?=base_url()?>/assets/jsAlert/dist/sweetalert.css">
    <link rel="stylesheet" href="<?=base_url()?>/assets/plugins/select2/select2.min.css">

    <link href="<?=base_url()?>/assets/css/googlefontcss.css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" >
    <link href="<?=base_url()?>/assets/css/multi_form_steps.css" rel="stylesheet"  type="text/css">

    <style>
        .container {
            padding-right: 15px;
            padding-left: 35px;
        }
    </style>
    <script>

        async function updateCSRFTokenSyncr() {
            const response = await $.ajax({
                url: "<?php echo base_url('Csrftoken'); ?>",
                dataType: 'json',
                success: function(data) {
                    $('[name="CSRF_TOKEN"]').val(data.CSRF_TOKEN_VALUE);
                }
            });

            return response;
        }
    </script>

</head>
<body  class="hold-transition skin-blue layout-top-nav">
<?php
// header("Cache-Control: no-store");
?>
<section>
    <div class="container" style="width: 100%">
        <div class="row">
            <div class="col-sm-12">
                <p class="text-right" style="padding: 0 !important; margin: 0 !important;">
                    <a href="<?=base_url()?>/Faster/FasterController" target="_self"> Search New Case <i class="glyphicon glyphicon-search"></i></a>
                </p>
            </div>
            <div class="board">
                <!-- <h2>Welcome to IGHALO!<sup>™</sup></h2>-->
                <div class="board-inner">
                    <ul class="nav nav-tabs" id="myTab">
                        <div class="liner"></div>
                        <li class="step_select <?= $multiStepFlag == 'AddDocuments' ? 'active' : '' ?>" data-step_flag="AddDocuments">
                            <a href="#AddDocuments" data-toggle="tab" title="Add Documents" >
                      <span class="round-tabs one">
                              <i class="glyphicon glyphicon-folder-open"></i>
                      </span>
                            </a></li>

                        <li class="step_select <?= $multiStepFlag == 'DigitalSign' ? 'active' : '' ?>" data-step_flag="DigitalSign" >
                     <a href="#DigitalSign" data-toggle="tab" title="Digital Signature" >
                     <span class="round-tabs two">
                         <i class="glyphicon glyphicon-pencil"></i>
                     </span>
                            </a>
                        </li>
                        <li class="step_select <?= $multiStepFlag == 'DigitalCertification' ? 'active' : '' ?>" data-step_flag="DigitalCertification">

                            <a href="#DigitalCertification" data-toggle="tab" title="Digital Certification">
                     <span class="round-tabs three">
                          <i class="glyphicon glyphicon-certificate"></i>
                     </span> </a>
                        </li>
                        <li class="step_select <?= $multiStepFlag == 'Download' ? 'active' : '' ?>" data-step_flag="Download">
                            <a href="#Download" data-toggle="tab" title="Download">
                         <span class="round-tabs four">
                              <i class="glyphicon glyphicon-cloud-download"></i>
                         </span>

                            </a></li>

                        <li class="step_select <?= $multiStepFlag == 'sendEmail' ? 'active' : '' ?>" data-step_flag="sendEmail">
                            <a href="#sendEmail" data-toggle="tab" title="Email">
                         <span class="round-tabs five">
                              <i class="glyphicon glyphicon-envelope"></i>
                         </span> </a>
                        </li>

                    </ul></div>

                <div class="tab-content">
                    <div class="tab-pane fade <?= $multiStepFlag == 'AddDocuments' ? 'in active' : '' ?>" id="AddDocuments">
                        <!-- Main content $usercode -->
                        <?php
                        if($multiStepFlag == 'AddDocuments'){
                            echo view("Faster/addDocument");
                        } ?>
                    </div>
                    <div class="tab-pane fade <?= $multiStepFlag == 'DigitalSign' ? 'in active' : '' ?>" id="DigitalSign">
                        <?php
                        if($multiStepFlag == 'DigitalSign'){
                            echo view("Faster/digitalSign");
                        }
                        ?>
                    </div>
                    <div class="tab-pane fade <?= $multiStepFlag == 'DigitalCertification' ? 'in active' : '' ?>" id="DigitalCertification">
                        <?php
                        if($multiStepFlag == 'DigitalCertification'){
                            echo view("Faster/digitalCertification");
                        } ?>
                    </div>
                    <div class="tab-pane fade <?= $multiStepFlag == 'Download' ? 'in active' : '' ?>" id="Download">
                        <?php
                        $data=array();
                        if(isset($transactions)){
                            $data['transactions']=$transactions;
                        }
                        if($multiStepFlag == 'Download'){
                            
                            echo view("Faster/download", $data);                            
                        } ?>
                    </div>
                    <div class="tab-pane fade <?= $multiStepFlag == 'sendEmail' ? 'in active' : '' ?>" id="sendEmail">
                        <?php
                        if($multiStepFlag == 'sendEmail'){
                            echo view("Faster/sendEmail");
                        } ?>
                    </div>
                    <div class="clearfix"></div>
                </div>

            </div>
        </div>
    </div>
</section>

<div class="content-fluid">


<script type="text/javascript" src="<?=base_url()?>/assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>/assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>/assets/plugins/select2/select2.full.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>/assets/js/app.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>/assets/plugins/datatables/jquery.dataTables.min.js"></script>

<script>
    $(function(){
        $('a[title]').tooltip();
        $('.js-example-basic-multiple').select2();
    });


    $(document).on('click', '.step_select', function () {
        var stepFlag = $(this).data('step_flag');
        if(stepFlag == 'AddDocuments')
            window.location = "<?=base_url()?>/Faster/FasterController/getFasterCaseDetailsDNo";
        else if(stepFlag == 'DigitalSign')
            window.location = "<?=base_url()?>/Faster/FasterController/getFasterDigitalSign";
        else if(stepFlag == 'DigitalCertification')
            window.location = "<?=base_url()?>/Faster/FasterController/getFasterDigitalCertification";
        else if(stepFlag == 'Download')
            window.location = "<?=base_url()?>/Faster/FasterController/getFasterDownload";
        else if(stepFlag == 'sendEmail')
            window.location = "<?=base_url()?>/Faster/FasterController/getFasterSendEmail";
        else{
            alert("Wrong Way!");
            return false;
        }
    });


    $(".alert").delay(4000).slideUp(500, function() {
        $(this).alert('close');
    });
    /*$(function() {
        showAttachedDocumentsList();
    });*/

    function getDates(id){
        // var CSRF_TOKEN = 'CSRF_TOKEN';
        // var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $("#divDocDated").show();
        $.get("<?=base_url()?>/Faster/FasterController/getDocumentsDates", {
            docType: id.value
        },function(result){
            $("#docDate").html(result);
            updateCSRFTokenSync();
        });
    }

    function showPDF(docType="",path="",dated=""){
        if(docType==""){
            docType=$("#docType").val();
        }
        else{
            $("#docType").val(docType).change();
        }
        if(path==""){
            path=$("#docDate").val();
        }
        else{
            $("#docDate").val(path).change();
        }
        if(dated==""){
            dated=$("#docDate option:selected").text();
        }
        $("#hiddenDocDate").val(dated);
        /*var docType=$("#docType").val();
        var path=$("#docDate").val();*/

        $("#divShowPdf").show();
        $.get("<?=base_url()?>/Faster/FasterController/showPDF", {docType: docType,'path': path},function(result){
            if(result.indexOf("alert alert-danger") != -1){
                $("#divBtnAttachPdf").css("display", "none");
            }
            else{
                $("#divBtnAttachPdf").css("display", "block");
            }
            $("#divShowPdf").html(result);
            showAttachedDocumentsList();
        });
    }

    function confirmBeforeAdd() {
        var choice = confirm('Do you really want to attach this file?');
        if(choice === true) {
            $.post("<?=base_url()?>/Faster/FasterController/attachDocument", $("#frmAddDocument").serialize(),function(result){
                response = $.parseJSON(result);
                //alert(response.message.msg);
                if(response.message.code=="SUCCESS"){
                    $("#divMessage").html("<div class=\"alert alert-success\" role=\"alert\">"+ response.message.msg+"!</div>");
                    $("#divMessage").show();
                    setTimeout(function() { $("#divMessage").hide(); }, 5000);
                    showAttachedDocumentsList();
                }
                else if(response.message.code=="ERROR"){
                    $("#divMessage").html("<div class=\"alert alert-danger\" role=\"alert\"> "+ response.message.msg+"!</div>");
                    $("#divMessage").show();
                    setTimeout(function() { $("#divMessage").hide(); }, 5000);
                }
                showAttachedDocumentsList();
            });
            return true;
        }
        return false;
    }
    function showAttachedDocumentsList(){
        $.get("<?=base_url()?>/Faster/FasterController/getSharedDocuments",function(result){
            //alert(result);
            response = $.parseJSON(result);
            $('#tblSharedDocuments tbody').empty();
            $.each(response, function(i, item) {
                var document_date_check = item.document_date;
                if(document_date_check == NULL){
                    document_date_check = "";
                }
                else{
                    document_date_check = "("+item.document_date+")";
                }
                $('<tr>').append(
                    $('<td>').text(item.name+" "+document_date_check),
                    $('<td>').text(item.created_date),
                    $('<td>').html("<a href=\"#\" data-toggle=\"tooltip\" title=\"View PDF\"><button type='button' id=view_"+item.id+" onclick='showAttachedFile(this.id)'><i class=\"glyphicon glyphicon-eye-open\"></i></button></a>&nbsp;&nbsp;&nbsp;<a href=\"#\" data-toggle=\"tooltip\" title=\"Delete Document\"><button type='button' id=delete_"+item.id+" onclick='dodelete(this.id);'><i class=\"glyphicon glyphicon-trash text-danger\"></i></button></a>")
                ).appendTo('#tblSharedDocuments tbody');
            });

        });
    }

    async function showAttachedFile(id,hideBtn=true,showDigitallySignedFile=false,showDigitallyCertifiedFile=false){
        $("#divShowPdf").html('<table widht="100%" align="center"><tr><td><img src="<?=base_url()?>/assets/images/load.gif"/></td></tr></table>');
        $("#divShowPdf").show();
        var res = await updateCSRFTokenSyncr();
        var CSRF_TOKEN_VALUE = res.CSRF_TOKEN_VALUE
        $.post("<?=base_url()?>/Faster/FasterController/showAttachedFile",{CSRF_TOKEN: CSRF_TOKEN_VALUE,'documentId':id,'showDigitallySignedFile':showDigitallySignedFile,'showDigitallyCertifiedFile':showDigitallyCertifiedFile},function(result){
            $("#divShowPdf").show();
            if(hideBtn){
                $("#divBtnAttachPdf").css("display", "none");
            }
            $("#divShowPdf").html(result);
        });
    }

    function dodelete(id){
        var choice = confirm('Do you really want to delete this file?');
        if(choice === true) {
            $.post("<?=base_url()?>/Faster/FasterController/deleteAttachedFile",{'documentId':id},function(result){
                if(result=="SUCCESS"){
                    $("#divMessage").html("<div class=\"alert alert-success\" role=\"alert\"> Record deleted!</div>");
                    $("#divMessage").show();
                    setTimeout(function() { $("#divMessage").hide(); }, 5000);
                    showAttachedDocumentsList();
                }
                else if(result=="ERROR"){
                    $("#divMessage").html("<div class=\"alert alert-danger\" role=\"alert\"> Unable to delete Record!</div>");
                    $("#divMessage").show();
                    setTimeout(function() { $("#divMessage").hide(); }, 5000);
                }
            });
            return true;
        }
        return false;
    }

    $(document).on("click", ".doActionDigiSign", function () {
        $("#actionDigiSign").html('<table widht="100%" align="center"><tr><td><img src="<?=base_url()?>/assets/images/load.gif"/></td></tr></table>');

        var file_path = $(this).data('file_path');
        var faster_shared_doc_id = $(this).data('faster_shared_doc_id');
        var faster_case_id = $(this).data('faster_case_id');
        showAttachedFile(faster_shared_doc_id,false);
        $.ajax({
            type: "POST",
            url: "<?=base_url()?>/Faster/FasterController/getDigitalSignInput",
            data: {faster_case_id:faster_case_id, file_path:file_path,faster_shared_doc_id:faster_shared_doc_id},
            cache: false,
            dataType: "text",
            success: function (data) {
                if (!$.trim(data)){
                    $("#actionDigiSign").html("<div class='alert alert-danger'>No Digital Signature Token Found!</div>");
                }
                else{
                    $("#actionDigiSign").html(data);
                }
            }
        });
    });

    $(document).on("click", ".doActionDigiCertify", function () {
        $("#actionDigitalCertify").html('<table widht="100%" align="center"><tr><td><img src="<?=base_url()?>/assets/images/load.gif"/></td></tr></table>');

        var file_path = $(this).data('file_path');
        var faster_shared_doc_id = $(this).data('faster_shared_doc_id');
        var faster_case_id = $(this).data('faster_case_id');
        showAttachedFile(faster_shared_doc_id,true,true);
        $.ajax({
            type: "POST",
            url: "<?=base_url()?>/Faster/FasterController/getDigitalCertificateInput",
            data: {faster_case_id:faster_case_id, file_path:file_path,faster_shared_doc_id:faster_shared_doc_id},
            cache: false,
            dataType: "text",
            success: function (data) {
                if (!$.trim(data)){
                    $("#actionDigitalCertify").html("<div class='alert alert-danger'>No Digital Certification Token Found!</div>");
                }
                else{
                    $("#actionDigitalCertify").html(data);
                }
            }
        });
    });

    $(document).on("click", ".btn_token_pdf_sign", function () {
        $.post("<?=base_url()?>/Faster/FasterController/setTokenTask", $("#token_certificate_form").serialize(),function(result){
            $("#divShowPdf").show();
            $("#divShowPdf").html(result);
            showDocumentsList();
        });
        return true;
    });

    $(document).on("click", ".btn_token_pdf_certify", function () {
        $.post("<?=base_url()?>/Faster/FasterController/setTokenCertificate", $("#token_certification_form").serialize(),function(result){
            $("#divShowPdf").show();
            $("#divShowPdf").html(result);
            showDocumentsListForCertification();
        });
        return true;
    });

    function showDocumentsList(){
        $.get("<?=base_url()?>/Faster/FasterController/getSharedDocuments",function(result){
            //alert(result);
            response = $.parseJSON(result);
            $('#tblDigiSign tbody').empty();
            $.each(response, function(i, item) {
                var actionButton="";
                var rootPath="<?=WEB_ROOT_URL?>";
                if(item.is_digitally_signed==0){
                    actionButton='<button type="button" data-faster_case_id="'+item.faster_cases_id+'" data-faster_shared_doc_id="'+item.id+'" data-file_path="'+rootPath+"/supreme_court/jud_ord_html_pdf"+item.file_path+item.file_name+'" class="btn success doActionDigiSign" title="I Want to Sign Digitally"><i class="glyphicon glyphicon-pencil"></i></button>';
                }
                else{
                    actionButton='<button type="button" data-faster_case_id="'+item.faster_cases_id+'"  id="view_'+item.id+'" onclick="showAttachedFile(this.id,true,true)" title="View Digitally Signed File" ><i class="glyphicon glyphicon-eye-open info"></i></button>';
                }
                var document_date_check = item.document_date;
                if(document_date_check == '00-00-0000'){
                    document_date_check = "";
                }
                else{
                    document_date_check = "("+item.document_date+")";
                }
                $('<tr>').append(
                    $('<td>').text(item.name+" "+document_date_check),
                    $('<td>').text(item.created_date),
                    $('<td>').html(actionButton)
                ).appendTo('#tblDigiSign tbody');
            });

        });
    }

    function showDocumentsListForCertification(){
        $.get("<?=base_url()?>/Faster/FasterController/getSharedDocuments",function(result){
            response = $.parseJSON(result);
            $('#tblDigiCertify tbody').empty();
            $.each(response, function(i, item) {
                var actionButton="";
                var rootPath="<?=WEB_ROOT_URL?>";
                if(item.is_digitally_certified==0){
                    if($.inArray(item.tw_notice_id, ['<?=DOCUMENT_ROP?>','<?=DOCUMENT_JUDGMENT?>','<?=DOCUMENT_SIGNED_ORDER?>']) !== -1){
                        actionButton='<button type="button" data-faster_case_id="'+item.faster_cases_id+'" data-faster_shared_doc_id="'+item.id+'" data-file_path="'+rootPath+"/supreme_court/jud_ord_html_pdf"+item.file_path+item.file_name+'" class="btn success doActionDigiCertify" title="Digitally Certify"><i class="glyphicon glyphicon-certificate text-danger"></i></button>';
                    }
                    else{
                        var filename=item.file_name.split('.').slice(0, -1).join('.');
                        filename=filename+"_Signed.pdf";
                        actionButton='<button type="button" data-faster_case_id="'+item.faster_cases_id+'" data-faster_shared_doc_id="'+item.id+'" data-file_path="'+rootPath+"/supreme_court/jud_ord_html_pdf"+item.file_path+filename+'" class="btn success doActionDigiCertify" title="Digitally Certify"><i class="glyphicon glyphicon-certificate text-danger"></i></button>';
                    }
                }
                else{
                    actionButton='<button type="button" data-faster_case_id="'+item.faster_cases_id+'"  id="view_'+item.id+'" onclick="showAttachedFile(this.id,true,true,true)" title="View Digitally Certified File" ><i class="glyphicon glyphicon-eye-open info"></i></button>';
                }
                var document_date_check = item.document_date;
                if(document_date_check == '00-00-0000'){
                    document_date_check = "";
                }
                else{
                    document_date_check = "("+item.document_date+")";
                }
                $('<tr>').append(
                    $('<td>').text(item.name+" "+document_date_check),
                    $('<td>').text(item.created_date),
                    $('<td>').html(actionButton)
                ).appendTo('#tblDigiCertify tbody');
            });

        });
    }
    function showtransactions(step){
        $.get("<?=base_url()?>/Faster/FasterController/getTransactions",{step:step},function(result){
            // alert(result);
            response = $.parseJSON(result);
            $('#tbl_history tbody').empty();
            $.each(response, function(i, item) {
                $('<tr>').append(
                    $('<td>').text(i+1),
                    $('<td>').text(item.userdetail),
                    $('<td>').text(item.created_on_formatted),
                    $('<td>').text(item.created_by_ip)
                ).appendTo('#tbl_history tbody');
            });
            $('#tbl_history').DataTable();

        });
    }

    function downloadAll(){
        updateCSRFTokenSyncr();
        $("#frmDownload").submit();
        setTimeout(function(){ showtransactions(<?=DOWNLOAD?>); }, 1000);
    }

    // $(document).ready(function () {
    //     $('#frmDownload').on('submit', function (e) {
    //         e.preventDefault();
    //         var url = $(this).attr('action');
    //         $.ajax({
    //             type: 'GET',
    //             url: url,
    //             data:$("#pdfForm").serialize(),
    //             success: function (resData){
    //                 // $('#alert-msg').html(resData);
    //             }
    //         })

    //         updateCSRFTokenSyncr();
    //     });
    // });

    async function recipientDetails(){
        $("#hiddenEmailIds").empty();
        emailIds="";
        var res = await updateCSRFTokenSyncr();
        var CSRF_TOKEN_VALUE = res.CSRF_TOKEN_VALUE
        $.post("<?=base_url()?>/Faster/FasterController/getRecipientDetails",{CSRF_TOKEN: CSRF_TOKEN_VALUE},function(result){

            response = $.parseJSON(result);
            $('#tbl_communications tbody').empty();
            updateCSRFTokenSyncr();
            $.each(response, function(i, item) {
                emailIds=emailIds+item.email_id+",";
                $('<tr>').append(
                    $('<td>').text(item.stakeholder_type),
                    $('<td>').text(item.nodal_officer_name),
                    $('<td>').text(item.stakeholder_type),
                    $('<td>').text(item.email_id),
                    $('<td>').text(item.mobile_number),
                    $('<td>').text(item.created_on),
                    $('<td>').html('<a href="#" data-toggle="tooltip" title="Delete Email"><button type="button" id="email_'+item.id+'" onclick="return dodeleteEmail(this)"><i class="glyphicon glyphicon-trash text-danger"></i></button></a>')
                ).appendTo('#tbl_communications tbody');
            });
            $('#tbl_communications').DataTable();
            emailIds = emailIds.replace(/,\s*$/, "");
            $("#hiddenEmailIds").val(emailIds);

        });
    }

    function addEmailid(id) {
        if($("#stakeholderDetails").val()==0){
            alert("Please select Stakeholder detail!");
            return false;
        }
        updateCSRFTokenSyncr();
        $.post("<?=base_url()?>/Faster/FasterController/addEmailId", $("#frmAddEmail").serialize(),function(result){
            if(result=="SUCCESS"){
                $("#divMessage").html("<div class=\"alert alert-success\" role=\"alert\"> Record Added Successfully!</div>");
                $("#divMessage").show();
                setTimeout(function() { $("#divMessage").hide(); }, 5000);
                updateCSRFTokenSyncr();
                recipientDetails();
            }
            else if(result=="ERROR"){
                $("#divMessage").html("<div class=\"alert alert-danger\" role=\"alert\"> Unable to add Record!</div>");
                $("#divMessage").show();
                updateCSRFTokenSyncr();
                setTimeout(function() { $("#divMessage").hide(); }, 5000);
            }
            return true;
        });
        return false;
    }

    function dodeleteEmail(id){
        var choice = confirm('Do you really want to remove this email?');
        if(choice === true) {
            updateCSRFTokenSync();
            $.post("<?=base_url()?>/Faster/FasterController/doDeleteContact",{'fasterCommunicationDetailsId':id.id},function(result){
                if(result=="SUCCESS"){
                    $("#divMessage").html("<div class=\"alert alert-success\" role=\"alert\"> Record deleted!</div>");
                    $("#divMessage").show();
                    setTimeout(function() { $("#divMessage").hide(); }, 5000);
                    recipientDetails();
                }
                else if(result=="ERROR"){
                    $("#divMessage").html("<div class=\"alert alert-danger\" role=\"alert\"> Unable to delete Record!</div>");
                    $("#divMessage").show();
                    setTimeout(function() { $("#divMessage").hide(); }, 5000);
                }
            });
            return true;
        }
        return false;
    }

    function setClipboard() {
        //alert("Hi"+$("#hiddenEmailIds").val());
        var tempInput = document.createElement("input");
        tempInput.style = "position: absolute; left: -1000px; top: -1000px";
        tempInput.value = $("#hiddenEmailIds").val();
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
        $("#divMessage").html("<div class=\"alert alert-success\" role=\"alert\"> Email Ids copied!</div>");
        $("#divMessage").show();
        setTimeout(function() { $("#divMessage").hide(); }, 5000);
    }

    function sendSMS(){
        var choice = confirm('Do you really want to send SMS notification to recipient(s)?');
        if(choice === true) {
            $.post("<?=base_url()?>/Faster/FasterController/sendSMSNotification",function(result){
                if(result=="SUCCESS"){
                    $("#divMessage").html("<div class=\"alert alert-success\" role=\"alert\"> SMS Notification sent successfully.</div>");
                    $("#divMessage").show();
                    setTimeout(function() { $("#divMessage").hide(); }, 5000);
                }
                else if(result=="ERROR"){
                    $("#divMessage").html("<div class=\"alert alert-danger\" role=\"alert\"> Unable to send SMS Notification!</div>");
                    $("#divMessage").show();
                    setTimeout(function() { $("#divMessage").hide(); }, 5000);
                }
            });
            return true;
        }
        return false;
    }

    function sendEmail(){
        var choice = confirm('Do you really want to send Email with attched document(s) to recipient(s)?');
        if(choice === true) {
            $.post("<?=base_url()?>/Faster/FasterController/sendEmail",function(result){
                if(result=="SUCCESS"){
                    $("#divMessage").html("<div class=\"alert alert-success\" role=\"alert\"> SMS Notification sent successfully.</div>");
                    $("#divMessage").show();
                    setTimeout(function() { $("#divMessage").hide(); }, 5000);
                }
                else if(result=="ERROR"){
                    $("#divMessage").html("<div class=\"alert alert-danger\" role=\"alert\"> Unable to send SMS Notification!</div>");
                    $("#divMessage").show();
                    setTimeout(function() { $("#divMessage").hide(); }, 5000);
                }
            });
            return true;
        }
        return false;
    }
</script>
</div>
<?php
// pr($multiStepFlag);
?>
</body>
</html>