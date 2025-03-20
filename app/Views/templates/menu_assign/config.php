<?Php

	error_reporting(0);
	$db_host = '10.25.78.67';
	$dbname = "sci_cmis_final_250723_cc";
        $dbuser = "anshu";
	$dbpass = "Anshu@#2020";
	$port="3306";

	try {
	    $dbo = new PDO("mysql:host={$db_host};dbname={$dbname};port={$port}", $dbuser, $dbpass);
    	$dbo->exec("set names utf8");
	    $dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $dbo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	} catch (PDOException $ex) {
	    die($ex->getMessage().'<br>Error in config file');
	}

/*$db_host_ph = '';
$dbname_ph = "physical_hearing";
$dbuser_ph = "phy_her_60";
$dbpass_ph = "Vidhi@#2020";
$dbport_ph = 56982;
try {
    $dbo_ph = new PDO("mysql:host={$db_host_ph};dbname={$dbname_ph};port={$dbport_ph}", $dbuser_ph, $dbpass_ph);
    $dbo_ph->exec("set names utf8");
    $dbo_ph->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbo_ph->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    //echo "Connected successfully";
} catch (PDOException $ex) {
    die($ex->getMessage().'<br>Error in config file');
    echo "Connection failed: " . $ex->getMessage();
}*/

$db_host2 = '10.25.78.67';
$dbname2 = "e_services";
$dbuser2 = "anshu";
$dbpass2 = "Anshu@#2020";

        try {
	    $dbo_eservices = new PDO("mysql:host={$db_host2};dbname={$dbname2};port={$port}", $dbuser2, $dbpass2);
            $dbo_eservices->exec("set names utf8");
	    $dbo_eservices->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $dbo_eservices->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        // echo "Connected successfully";
           } catch (PDOException $ex) {
	    die($ex->getMessage().'<br>Error in config file');
            echo "Connection failed: " . $ex->getMessage();
        }
/*
$db_host3 = '';
$dbname3 = "sci_cmis_final_250723_cc";
$dbuser3 = "";
$dbpass3 = "";
*/
	$db_host3 = '10.25.78.67';
	$dbname3 = "sci_cmis_final_250723_cc";
    $dbuser3 = "anshu";
	$dbpass3 = "Anshu@#2020";


try {
    $dbo_icmis_read = new PDO("mysql:host={$db_host3};dbname={$dbname3};port={$port}", $dbuser3, $dbpass3);
    $dbo_icmis_read->exec("set names utf8");
    $dbo_icmis_read->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbo_icmis_read->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    //echo "Connected successfully";
} catch (PDOException $ex) {
    die($ex->getMessage().'<br>Error in config file');
    echo "Connection failed: " . $ex->getMessage();
}






?>
