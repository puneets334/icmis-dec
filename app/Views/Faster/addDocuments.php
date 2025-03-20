<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Add Documents</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/skins/_all-skins.min.css">

    <link rel="stylesheet" href="<?=base_url()?>assets/jsAlert/dist/sweetalert.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/jsAlert/dist/sweetalert.css">

    <script src="<?=base_url()?>assets/css/select2.css"></script>

</head>
<body class="hold-transition skin-blue layout-top-nav">
<div class="content-fluid">
    <!-- Main content -->
    <section class="content " >
        <h2>Add Documents</h2><hr>
        <!-------------Result Section ------------>
        <?php
        if(is_array($caseDetails) ) {

            ?>
            <form id="frmAddDocument" enctype="multipart/form-data" action="<?= base_url() ?>index.php/FasterController/attachDocument" method="post">
                <input type="hidden" name="usercode" id="usercode" value="<?=$usercode?>"/>

                <div class="col-sm-6">
                    <div class="col-sm-12 form-group">
                        <label for="diaryNumber" class="col-sm-6 control-label"><span class="text-primary">Case No: </span> <?=$caseDetails[0]['reg_no_display']?>&nbsp;(D No.<?=substr($caseDetails[0]['diary_no'], 0, -4)."/".substr($caseDetails[0]['diary_no'], -4)?>)</label>
                        <label for="causeTitle" class="col-sm-6 control-label"><span class="text-primary">Causetitle : </span> <?=$caseDetails[0]['pet_name']?> Vs. <?=$caseDetails[0]['res_name']?></label>
                    </div>
                    <div class="col-sm-4 form-group">
                        <label for="docType">Document Type</label>
                            <select class="form-control" id="docType" name="docType" placeholder="docType" onchange="return getDates(this);" >
                                <option value="0">Select Document Type</option>
                                <?php
                                foreach ($noticeTypes as $noticeType){
                                    echo '<option value="'.$noticeType[id].'">'.$noticeType[name].'</option>';
                                }
                                ?>
                            </select>
                    </div>
                    <div class="col-sm-4 form-group" id="divDocDated">
                        <label for="docType">Dated</label>
                        <select class="form-control" id="docDate" name="docDate" placeholder="docDate" >
                        </select>
                        <input type="hidden" id="hiddenDocDate" name="hiddenDocDate">
                    </div>
                    <div class="form-group col-sm-4">
                        <label>&nbsp;</label>
                        <button type="button" id="btnShowPDF" class="btn btn-primary form-control" onclick="showPDF();">View</button>
                    </div>
                    <div class="col-sm-12" id="divMessage"></div>
                    <div class="col-sm-12">
                        <h4>Attached Document(s)</h4>
                        <table id="tblSharedDocuments" class="table table-striped">
                            <thead>
                            <tr>
                            <th>Document Name (Dated)</th>
                            <th>Uploaded On</th>
                            <th>Action</th>
                            </tr></thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>


                </div>
                <div class="form-group col-sm-6">
                    <div class="form-group col-sm-12" id="divBtnAttachPdf" style="display: none">
                        <label>&nbsp;</label>
                        <button type="button" id="btnAttachPdf" class="btn btn-success form-control" onclick="return confirmBeforeAdd();">Attach PDF</button>
                    </div>
                    <div classs="form-group col-sm-12" id="divShowPdf">

                    </div>

                </div>

                <br/>

            </form>

        <?php }
        else
        {
            // echo "No data found";
        }
        ?>
    </section>
</div>

<script src="<?=base_url()?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="<?=base_url()?>assets/js/bootstrap.min.js"></script>
<script src="<?=base_url()?>assets/js/select2.full.min.js"></script>
<!--<script src="<?/*=base_url()*/?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="<?/*=base_url()*/?>assets/plugins/fastclick/fastclick.js"></script>-->
<script src="<?=base_url()?>assets/js/app.min.js"></script>
<!--<script src="<?/*=base_url()*/?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>-->


<!--<script src="<?=base_url()?>assets/js/reader_cl.js"></script>-->

<script>
    $(".alert").delay(4000).slideUp(500, function() {
        $(this).alert('close');
    });
    $(function() {
        showAttachedDocumentsList();
    });
    function getDates(id){
        if(id.value==<?=DOCUMENT_MEMO_OF_PARTY?>){
            $("#divDocDated").hide();
        }
        else{
            $("#divDocDated").show();
            $.post("<?=base_url()?>index.php/FasterController/getDocumentsDates", {docType: id.value},function(result){
                $("#docDate").html(result);
            });
        }
    }
    function showPDF(){
        var docType=$("#docType").val();
        var path=$("#docDate").val();
        $("#hiddenDocDate").val($("#docDate option:selected").text());
        $("#divShowPdf").show();
        $.post("<?=base_url()?>index.php/FasterController/showPDF", {docType: docType,'path': path},function(result){
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

            $.post("<?=base_url()?>index.php/FasterController/attachDocument", $("#frmAddDocument").serialize(),function(result){
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
        $.post("<?=base_url()?>index.php/FasterController/getSharedDocuments",function(result){
            //alert(result);
            response = $.parseJSON(result);
            $('#tblSharedDocuments tbody').empty();
                $.each(response, function(i, item) {
                    $('<tr>').append(
                        $('<td>').text(item.name+" ("+item.document_date+")"),
                        $('<td>').text(item.created_date),
                        $('<td>').html("<a href=\"#\" data-toggle=\"tooltip\" title=\"View PDF\"><button type='button' id=view_"+item.id+" onclick='showAttachedFile(this.id)'><i class=\"glyphicon glyphicon-eye-open\"></i></button></a>&nbsp;&nbsp;&nbsp;<a href=\"#\" data-toggle=\"tooltip\" title=\"Delete Document\"><button type='button' id=delete_"+item.id+" onclick='dodelete(this.id);'><i class=\"glyphicon glyphicon-trash text-danger\"></i></button></a>")
                    ).appendTo('#tblSharedDocuments tbody');
                });

        });
    }
   function showAttachedFile(id){
        $.post("<?=base_url()?>index.php/FasterController/showAttachedFile",{'documentId':id},function(result){
            $("#divShowPdf").show();
            $("#divBtnAttachPdf").css("display", "none");
            $("#divShowPdf").html(result);
        });
    }
    function dodelete(id){
        var choice = confirm('Do you really want to delete this file?');
        if(choice === true) {
            $.post("<?=base_url()?>index.php/FasterController/deleteAttachedFile",{'documentId':id},function(result){
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



</script>

</body>
</html>