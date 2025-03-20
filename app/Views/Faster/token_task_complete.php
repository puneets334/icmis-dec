<?php
exit();
date_default_timezone_set("Asia/Kolkata");
session_start();
if(isset($_POST['btn_sign2'])){
 //   include("copying_functions.php");
 //   include("config.php");
    $clientIP = get_client_ip();
    $doc_id = $_POST['doc_id'];

    $sign_str = "Signed By : ".$_SESSION['emp_name_login']."\n".$_SESSION['dcmis_usertype_name']."\n".$_SESSION['dcmis_multi_section_name'][0]."\nSupreme Court of India\n".date('jS \of F Y h:i:s A');
    //'http://10.40.186.15/token_sample.pdf'
    $x=array('certificateId'=>$_POST['dd_certificate'],
        'email'=>'',
        'font_size'=>7,
        'label'=>$_POST['token_label'],
        'pdf_path'=> urldecode("https://main.sci.gov.in/jonew/cl/2021-08-13/M_J_2_2_29268.pdf"),
        'pin'=>$_POST['token_pin'],
        'signature'=> $sign_str
        );
//        'sign_location':{'x':200,'y':200,'x1':55,'y1':70},
    ini_get('allow_url_fopen');
    json_encode( $x );
    $options = array(
        'http' => array(
            'method'  => 'POST',
            'content' => json_encode( $x ),
            'header'=>  "Content-Type: application/json\r\n" .
            "Accept: application/json\r\n"
        )
    );

    $context  = stream_context_create( $options );
    //$token_sign = "http://10.40.186.15:8100/api/v1/tokensigner";
    $token_sign = "http://$clientIP:8100/api/v1/tokensigner";
    $result = file_get_contents( $token_sign, false, $context );
    $json_result = json_decode($result);


    var_dump($json_result);
    var_dump($json_result[1]);
    $source_url = $json_result[1]->{signed_file};
    if(!empty($source_url) && $source_url != ''){
            $source_url = $data['signed_pdf_url'];




            $arrContextOptions=array(
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ),
            );


//
//        if(!file_put_contents( $destination_path,file_get_contents($source_url, false, stream_context_create($arrContextOptions)))){
//            echo "failed";
//        }
//        else {
//            echo "success";
//
//
//        }

    }
    else{
        echo "Error:No Response";
        exit();
    }

    echo "<script>alert('success'); window.close();</script>";
    exit();
}


?>


