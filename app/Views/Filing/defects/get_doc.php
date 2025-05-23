<?php
//download function definition
$filing_details = session()->get('filing_details');
$user_details = session()->get('login');
$dairy_no = $filing_details['diary_no'];




$dname = array();
//getting pdf url
$vle =  $dairy_no;
$dir = 'defectpdf';
$court_dir = $dir . '/' . $vle;
$url = "http://xxxxxx/Api/Cases/get_list_doc_cases_efiled?diary_no=". $dairy_no;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Optional timeout
$response = curl_exec($ch);
if (curl_errno($ch)) {
    
    $error_msg = 'cURL error (' . curl_errno($ch) . '): ' . curl_error($ch);
    echo $error_msg;
} else {
    $data = json_decode($response, true);
    if ($data) {
        $dname = array(); $dpath = array(); $ddate = array();
        if (isset($data['doc_list']) && is_array($data['doc_list'])) {
            foreach ($data['doc_list'] as $sub_array) {
                if (isset($sub_array['doc_title'])) {
                    $dname[] = $sub_array['doc_title'];
                    $dpath[] = $sub_array['path'];
                    $ddate[] = $sub_array['uploaded_on'];
                }}}
        //for($i=0; $i<count($dname); $i++) {
            ?>
            <table id="docs" border="2px; black" style="text-align: center; font-size: 9px; text-wrap: normal " >
                <?php
                for ($j=0;$j<count($dname);$j=$j+3){ ?>
                    <tr  style="height: 50px;" id="tbl-hlite">
                        <?php $k=$j+1; $l=$j+2; ?>
                        <td
                            <?php
                            if ($dname[$j]!='') { ?>
                            onclick="showiframe('<?php echo $dir; ?>','<?php echo $dairy_no; ?>','<?php echo $dname[$j];?>'); bold_cell()"> <?php }
                            else{
                                echo "&nbsp";
                            }
                            ?>
                              <?php
                              if ($dname[$j]!='') {
                                  echo "$dname[$j] ($ddate[$j])";
                              }
                              else {
                                  echo "&nbsp";
                              }?>
                        </td>
                        <td
                            <?php
                            if ($dname[$k]!='') { ?>
                                onclick="showiframe('<?php echo $dir; ?>','<?php echo $dairy_no; ?>','<?php echo $dname[$k];?>'); bold_cell()"> <?php }
                            else{
                                echo "&nbsp";
                            }
                            ?>
                            <?php
                            if ($dname[$k]!='') {
                                echo "$dname[$k] ($ddate[$k])";
                            }
                            else {
                                echo "&nbsp";
                            }?>
                        </td>
                        <td
                            <?php
                                if ($dname[$l]!='') { ?>
                                onclick="showiframe('<?php echo $dir; ?>','<?php echo $dairy_no; ?>','<?php echo $dname[$l];?>'); bold_cell()"> <?php }
                                else{
                                    echo "&nbsp";
                                }
                                    ?>
                            <?php
                            if ($dname[$l]!='') {
                                echo "$dname[$l] ($ddate[$l])";
                            }
                            else {
                                echo "&nbsp";
                            }?>
                        </td>
                    </tr>
                <?php }
                ?>


            </table>
             <?php
        } else {
        echo "Failed to decode JSON response.";
    }}
curl_close($ch);

//downloading the files
for ($j=0; $j<count($dname); $j++) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777);
    }
    if (!is_dir($court_dir)) {
        mkdir($court_dir, 0777);
    }
    $destination[] = $court_dir . '/' . $dname[$j] . '.pdf';
    if (!file_exists($destination[$j])) {
        $result[] = download($dpath[$j], $destination[$j]);
    }
}


function download($file_source, $file_target)
{    set_time_limit(0);
    $opts = array(
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false,
        ),);
    $rh = fopen($file_source, 'rb', false, stream_context_create($opts));
    $wh = fopen($file_target, 'w+b');
    if (!$rh || !$wh) {
        return false;
    }
    while (!feof($rh)) {
        if (fwrite($wh, fread($rh, 4096000)) === FALSE) {
            return false;
        }
        echo ' ';
        flush();
    }
    fclose($rh);
    fclose($wh);
    return true;
}
?>

<iframe id="itest" height="600" width="600" src="about:blank"> </iframe>
