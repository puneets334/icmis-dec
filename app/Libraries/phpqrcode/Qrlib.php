<?php

namespace App\Libraries\phpqrcode;

class Qrlib
{
	

	function __construct() 
	{	
		$QR_BASEDIR = dirname(__FILE__).DIRECTORY_SEPARATOR;
		include $QR_BASEDIR."qrconst.php";
		include $QR_BASEDIR."qrconfig.php";
		include $QR_BASEDIR."qrtools.php";
		include $QR_BASEDIR."qrspec.php";
		include $QR_BASEDIR."qrimage.php";
		include $QR_BASEDIR."qrinput.php";
		include $QR_BASEDIR."qrbitstream.php";
		include $QR_BASEDIR."qrsplit.php";
		include $QR_BASEDIR."qrrscode.php";
		include $QR_BASEDIR."qrmask.php";
		include $QR_BASEDIR."qrencode.php";
		
	}

	public function QRcodePng($file_url_on_web,$file,$ecc,$pixel_Size,$frame_Size){

        return QRcode::png($file_url_on_web,$file,$ecc,$pixel_Size,$frame_Size);
    }
}


