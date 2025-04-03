<?php 
    $ucode =$_SESSION['login']['usercode'];
    //$send_to_path = $_POST['send_to_path'];
    if(sizeof($documentRow)>0){
    $data = $documentRow[0];
    //print_r($data);
    //die;                    
?>
<div class="row clearfix col-md-12 pb-1">

<?php 
if($data['sent_to_applicant_by'] == '' OR $data['sent_to_applicant_by'] == null){
    


if(!empty($data['pdf_embed_path'])){
?>
<div class='d-inline col-md-2'>
<!--<input type="button" class="btn btn-success float-left" value="Download" id="download_copy" data-document_id = '<?=$document_id;?>'
       data-send_to_path="<?=$data['pdf_embed_path'];?>" />-->
<!--
<button type="button" class="btn btn-success float-left" value="Download"
        onClick="window.open('<?=$data['pdf_embed_path'];?>')">Download</button>-->
<!--<a id="download_copy" data-document_id = "<?=$document_id;?>" data-application_id_id="<?=$_POST['application_id_id'];?>" href="../../../<?=$data['pdf_embed_path'];?>" download="<?=$document_id.'_'.date('d-m-Y H:i:s').'.pdf';?>">
    <button type="button" class="btn btn-success float-left">Download</button>
</a>

-->


    <!--<form id="myform" name="myform" target="_blank" action="http://10.40.186.15/webservice/index.php/Esigner/sign_doc" method="post">-->
    <!--<form id="myform" name="myform" target="_blank" action="http://XXXX:85/web_service/index.php/Esigner/sign_doc" method="post">-->
        <form id="myform" name="myform" target="_blank" action="<?=GET_SERVER_IP."/web_service/index.php/Esigner/sign_doc"?>" method="post">
            <input type="hidden" name="docId" id="url_doc_id" value="<?=$document_id;?>">
            <input type="hidden" name="url" id="url_pdf_embed_path" value="<?= GET_SERVER_IP."/".$data['pdf_embed_path'];?>">
            <input type="hidden" name="respone_url" value="<?=GET_SERVER_IP."/supreme_court/offline_copying/esign_test1.php" ?>">
            <input type="hidden" name="docType" value="copying">
            <input type="hidden" name="esignIndex" value="0">
            <input type="hidden" name="employeeName" value="<?=(!empty($_SESSION['emp_name_login'])?$_SESSION['emp_name_login']:'');?>">
            <input type="hidden" name="employeeCode" value="<?=!empty($_SESSION['icmic_empid'])?$_SESSION['icmic_empid']:'';?>">
            <input type="hidden" name="employeeDesignation" value="<?=!empty($_SESSION['dcmis_usertype_name'])?$_SESSION['dcmis_usertype_name']:'';?>">
            <input type="submit" name="btn_sign" value="eSigner" class="btn btn-success"/>

<!--    <button type="button" class="btn btn-success float-left btn_sign" data-document_id = "--><?//=$document_id;?><!--" data-application_id_id="--><?//=$_POST['application_id_id'];?><!--" data-send_to_path="--><?//=$data['pdf_embed_path'];?><!--">eSigner</button>-->

    </form>
</div>
    <div class='d-inline col-md-2'>
            <input type="button" name="btn_sign" value="Token" class="btn btn-info btn_token_signer"/>
    </div>









<?php    
}
?>

<?php 
if($data['pdf_downloaded_by'] != '' && $data['pdf_downloaded_by'] != null){
?>
<!--<div class='col-md-4'>-->
<!--<form method='post'  action='' enctype="multipart/form-data">
    <style>
        .custom-file-upload input[type="file"] {
    display: none;
}
.custom-file-upload .custom-file-upload1 {
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
}</style>
<div class="row clearfix">
<div class="custom-file-upload">
<label for="browse_digi_sign_copy" class="custom-file-upload1">
    <i class="fa fa-cloud-upload"></i> Browse
</label>
<input type="file" name="browse_digi_sign_copy" id="browse_digi_sign_copy" accept="application/pdf"/>
<span class="show_uploaded_file_name"></span>
</div>    
    <div class="pl-1">
<input type="button" class="btn btn-success float-left" value="Upload" id="upload_digi_sign_copy" data-application_id_id = '<?=$_POST['application_id_id'];?>' data-document_id = '<?=$document_id;?>' data-send_to_path="<?=$data['pdf_embed_path'];?>" />
</div>
</div>
</form>-->
<!--</div>    -->
<?php    
}
?>

<?php 
if($data['pdf_digital_signature_by'] != '' && $data['pdf_digital_signature_by'] != null){
?>
    <div class='col-md-2'>
        <input type="button" class="btn btn-success float-right" value="View Signed Pdf" id="view_signed_pdf" data-application_id_id="<?=$_POST['application_id_id'];?>"
               data-document_id = '<?=$document_id;?>' data-send_to_path="<?='http://'.$_SERVER['SERVER_ADDR'].'/'.$data['pdf_digital_signature_path'];?>"/>
    </div>

    <div class='col-md-2'>
<input type="button" class="btn btn-success float-right" data-source="pdf_file" value="Send" id="send_to_applicant" data-application_id_id="<?=$_POST['application_id_id'];?>"
       data-document_id = '<?=$document_id;?>' data-send_to_path="<?=$data['pdf_digital_signature_path'];?>"/>
</div>    
<?php    
}
}
?>
<div class='col-md-2'>
<button type="button" class="btn btn-danger float-right pdfbox">close pdf</button></div>               
</div>
        <span class="col-md-11" id="certificate_span"></span>
<?php } ?>