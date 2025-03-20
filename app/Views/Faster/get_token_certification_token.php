<?php
//TODO::TOBE ENABLE LATER
//$certificate_result = file_get_contents("http://10.40.186.15:8000/api/v2/certificates_list");
$certificate_result = file_get_contents("http://".get_client_ip().":8000/api/v2/certificates_list");
$json = json_decode($certificate_result, true);
if(count($json)>0){
    ?>

    <div class="container text-left">
    <form class="form-inline" id="token_certification_form" name="token_certification_form" enctype="multipart/form-data">
        <input type="hidden" name="faster_case_id" id="faster_case_id" value="<?=$faster_case_id?>">
        <input type="hidden" name="doc_id" id="doc_id" value="<?=$faster_shared_doc_id?>">
        <input type="hidden" name="url_pdf_embed_path" id="url_pdf_embed_path" value="<?=urlencode($file_path.$file_name)?>">


            <label for="dd_certificate">Certificate:</label>
            <select class="form-control " style="width:200px" name="dd_certificate" id="dd_certificate" >
                <?php
                foreach($json as $values)
                {
                    $token_label = $values[2];
                    ?>
                    <option value="<?=$values[0]?>"><?=$values[1]?></option>
                    <?php
                }
                ?>
            </select>
            <input type="hidden" name="token_label" id="token_label" value="<?=$token_label?>">



            <label for="token_pin">Key:</label>
            <input class="form-control" type="text" name="token_pin" id="token_pin" placeholder="Enter Key" value="" >

            <button type="button" id="sign_now" name="btn_sign2" class="form-control btn btn-default btn_token_pdf_certify">Certify Now</button>







    </form>
</div>
    <?php
}
/*}
else{
    echo "Error:false";
}*/
?>