<div class="abcd">
        <div style='font-size:12px; display: inline;width:70%;float:left;text-align:left'>
            <span style='font-style:bold;'><u>
                Application No. <?= $_POST['application_no'];?> Applicant : <?= $_POST['applicant_name'];?>, Pages : <?=$_POST['number_of_pages_in_pdf'];?>
                <?php
                if($_POST['delivery_mode'] == 3){
                 ?>
                <br>IT IS NOT A CERTIFIED COPY.</u>
                <?php
                }
                ?>
            </span>
        </div>        
    <div id="qrcode" style='display: inline;width:20%;float:right;text-align:right'></div>
</div>
<?php $application_id_id = $_POST['application_id_id']; 
//Fees Paid : Rs. $_POST['court_fee'];
?>
<script>
        //$('#qrcode').qrcode({render : "image",size:50,text:'https://registry.sci.gov.in/api/callback/bharat_kosh/online_copying/get_copy_details.php?id='});
        $('#qrcode').qrcode({render : "image",
            size:85,
//            mode: 2,
//        mSize: 0.2,
//        mPosX: 0.5,
//        mPosY: 0.5,
//
//        label: 'SCI',
//        fontname: 'sans',
//        fontcolor: '#000',
        //text:'https://registry.sci.gov.in/api/callback/bharat_kosh/online_copying/copy_search_verify_qr.php?crn=<?=$_POST['crn'];?>'});
        text:'<?=base_url()?>/Copying/Copying/copy_search_verify_qr?crn=<?=$_POST['crn'];?>'});
        
</script>