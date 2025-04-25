<?php

/**
 * Created by PhpStorm.
 * User: Anshu
 * Date: 26/8/23
 * Time: 11:30 AM
 */


function getClientIP()
{
    if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
        return  $_SERVER["HTTP_X_FORWARDED_FOR"];
    } else if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
        return $_SERVER["REMOTE_ADDR"];
    } else if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
        return $_SERVER["HTTP_CLIENT_IP"];
    }

    return '';
}


function getClientMAC()
{
    if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
        $ipAddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
    } else if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
        $ipAddress = $_SERVER["REMOTE_ADDR"];
    } else if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
        $ipAddress = $_SERVER["HTTP_CLIENT_IP"];
    }

    //$ipAddress = getClientIP();

    //echo "<br>";
    //$ipAddress = "172.16.182.57";
    ob_start();
    system('ping -c 2 ' . $ipAddress);
    $macfull = ob_get_contents();
    ob_clean();
    //echo $macfulll;

    ob_start();
    system('arp -an ' . $ipAddress);
    $macfull = ob_get_contents();
    ob_clean();
    //echo $macfull;

    $pmac = strpos($macfull, $ipAddress);
    $mac = substr($macfull, ($pmac + 18), 17);
    return $mac;
}
function send_mail_JIO($to_email,$subject,$message,$files=array())
{
    if (isset($to_email) && isset($subject) && isset($message)){
        if (!is_array($to_email)){ $to_email=[$to_email]; }
        $metadata = json_encode(array("providerCode" => "email","recipients" => array("emailAddresses" => array("to" =>$to_email)),"body" =>$message,"scheduledAt" => null,"purpose" => $subject,"subject" => $subject, "sender" => array("name" => "SC-eFM","emailAddress" => "icmis@sci.nic.in"),"createdByUser" => array("id" => LIVE_EMAIL_KEY,"name" => "SC-eFM","employeeCode" => LIVE_EMAIL_KEY,"organizationName" => "SC-eFM"),"module" => "SC-eFM","project" => "SC-eFM","files" => $files));
        $curl = curl_init();
        curl_setopt_array($curl,array(
            CURLOPT_URL => NEW_MAIL_SERVER_HOST_JIO_URL,
            CURLOPT_RETURNTRANSFER => true,CURLOPT_ENCODING => '',CURLOPT_MAXREDIRS => 10,CURLOPT_TIMEOUT => 0,CURLOPT_FOLLOWLOCATION => true,CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,CURLOPT_HEADER => 0,CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>"$metadata",
            CURLOPT_HTTPHEADER => array('Content-Type: application/json','Accept: application/json','Authorization: Bearer '.LIVE_EMAIL_KEY_JIO_CLOUD),
        ));
        $response = curl_exec($curl);
        if ($response){
            $json2 = json_decode($response);
            if (isset($json2->data) && !empty($json2->data) && !empty($json2->data->job_batch_id)){
                $json2=$json2->data->job_batch_id;
            }else{ $json2=false;}
        }else{ $json2 = $response; }
        curl_close($curl);
        if ($json2!=false) { $json2 = 'success';} else { $json2 = 'failed'; }
    }else{
        $json2 = 'failed';
    }
    return $json2;
}
function sendSMS($mobile_no, $smsmsg, $template_id)
{
    
   
    if (empty($template_id)) {
        $template_id = 1107165900749762632;
    }
    
    $url = "http://10.25.78.5/eAdminSCI/a-push-sms-gw?mobileNos=" . $mobile_no . "&message=" . rawurlencode($smsmsg) . "&typeId=29&myUserId=NIC001001&myAccessId=root&templateId=" . $template_id;
    $result = (array)json_decode(file_get_contents($url));
    if($result['responseFlag']=='success'){
        return true;   
    }else{
        return false;
    }
    
}




if (!function_exists('escape_data')) {

    function escape_data($post)
    {
        return trim(pg_escape_string(strip_tags($post)));
    }
}
function associative_array_merged_key($array1, $array2, $key)
{
    $combinedArray = array();
    foreach (array_merge($array1, $array2) as $item) {
        if (!empty($item)) {
            $year = $item["$key"];
            if (!isset($combinedArray[$year])) {
                $combinedArray[$year] = array("$key" => $year);
            }
            $combinedArray[$year] = array_merge($combinedArray[$year], $item);
        }
    }
    $combinedArray = array_values($combinedArray);
    return $combinedArray;
}

function get_from_table_json($table_name, $condition = null, $column_names = null)
{
    
    $file = env('Json_master_table') . $table_name . '.json';
    if (file_exists($file)) {
        // $url = base_url('/' . $file);
        $json = file_get_contents($file);   ///$url
        $json_data = json_decode($json, true);
        $json_array = array();
        if ($json_data) {
            if (!empty($condition) && $condition != null && !empty($column_names) && $column_names != null) {
                foreach ($json_data as $subArray) {
                    if (isset($subArray[$column_names]) && $subArray[$column_names] == $condition) {
                        $json_array[] = $subArray;
                    }
                }
            } else {
                $json_array = $json_data;
            }
        } else {
            $json_array;
        }
        return $json_array;
    } else {
        return false;
    }
}

function get_diary_case_type($case_type, $case_number, $case_year, $if_return_array = '', $flag = '')
{
    $result = '';
    if ($flag == '') {
        $flag = 'AB';
    }
    if (!empty($case_type) && !empty($case_number) && !empty($case_year)) {
        $result = get_diary_case_type_details($case_type, $case_number, $case_year, '', '', $if_return_array, $flag);
        if (empty($result)) {
            $result = get_diary_case_type_details($case_type, $case_number, $case_year, 'Y', '', $if_return_array, $flag);
        }
        if (empty($result)) {
            $result = get_diary_case_type_details($case_type, $case_number, $case_year, '', '_a', $if_return_array, $flag);
        }
        if (empty($result)) {
            $result = get_diary_case_type_details($case_type, $case_number, $case_year, 'Y', '_a', $if_return_array, $flag);
        }
    }
  
    // if (empty($result)) { 
    //     $result='Data not found'; 
    // }
    return $result;
}

function get_diary_case_type_details($caseTypeId, $caseNo, $caseYear, $case_number_to = '', $is_archival_table = '', $if_return_array = '', $flag = 'AB')
{
    $db = \Config\Database::connect();
    $result = '';
    if ($flag == 'AB' || $flag == 'A') {
        $builder = $db->table("main$is_archival_table m");
        $builder->select("LEFT(CAST(m.diary_no AS TEXT), -4) AS dn, RIGHT(CAST(m.diary_no AS TEXT), 4) AS dy");
        if (!empty($case_number_to)) {
            $builder->join("(SELECT diary_no, 
                            CASE WHEN fil_no::text ~ '^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$'::text THEN CAST(SPLIT_PART(m.fil_no, '-', 1) AS INTEGER) ELSE 0 END AS case_type,
                            CASE WHEN fil_no::text ~ '^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$'::text THEN CAST(SPLIT_PART(m.fil_no, '-', 2) AS INTEGER) ELSE 0 END AS part1,
                            CASE WHEN fil_no::text ~ '^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$'::text THEN CAST(SPLIT_PART(m.fil_no, '-', 3) AS INTEGER) ELSE 0 END AS part2,
                            
                            CASE WHEN fil_no_fh::text ~ '^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$'::text THEN CAST(SPLIT_PART(m.fil_no_fh, '-', 1) AS INTEGER) ELSE 0 END AS fh_case_type,
                            CASE WHEN fil_no_fh::text ~ '^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$'::text THEN CAST(SPLIT_PART(m.fil_no_fh, '-', 2) AS INTEGER) ELSE 0 END AS fh_part1,
                            CASE WHEN fil_no_fh::text ~ '^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$'::text THEN CAST(SPLIT_PART(m.fil_no_fh, '-', 3) AS INTEGER) ELSE 0 END AS fh_part2,
                            
                            CASE WHEN reg_year_mh = 0 OR DATE(fil_dt) > '2017-05-10' THEN DATE_PART('year', fil_dt) ELSE reg_year_mh END AS mh_reg_year,
                            CASE WHEN reg_year_fh = 0 THEN DATE_PART('year', fil_dt_fh) ELSE reg_year_fh END AS fh_reg_year 
                            FROM main$is_archival_table m) t", "t.diary_no=m.diary_no", 'inner');
            $builder->where("((t.case_type = $caseTypeId AND $caseNo BETWEEN t.part1 AND t.part2 AND t.mh_reg_year = $caseYear) OR (t.fh_case_type = $caseTypeId AND $caseNo BETWEEN t.fh_part1 AND t.fh_part2 AND t.fh_reg_year = $caseYear))");
        } else {
            $builder->join("(SELECT diary_no, 
                            CASE WHEN fil_no::text ~ '^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$'::text THEN CAST(SPLIT_PART(m.fil_no, '-', 1) AS INTEGER) ELSE 0 END AS case_type,
                            CASE WHEN fil_no::text ~ '^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$'::text THEN CAST(SPLIT_PART(m.fil_no, '-', 2) AS INTEGER) ELSE 0 END AS part1,
                            CASE WHEN fil_no::text ~ '^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$'::text THEN CAST(SPLIT_PART(m.fil_no, '-', 2) AS INTEGER) ELSE 0 END AS part2,
                            
                            CASE WHEN fil_no_fh::text ~ '^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$'::text THEN CAST(SPLIT_PART(m.fil_no_fh, '-', 1) AS INTEGER) ELSE 0 END AS fh_case_type,
                            CASE WHEN fil_no_fh::text ~ '^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$'::text THEN CAST(SPLIT_PART(m.fil_no_fh, '-', 2) AS INTEGER) ELSE 0 END AS fh_part1,
                            CASE WHEN fil_no_fh::text ~ '^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$'::text THEN CAST(SPLIT_PART(m.fil_no_fh, '-', 2) AS INTEGER) ELSE 0 END AS fh_part2,
                            
                            CASE WHEN reg_year_mh = 0 OR DATE(fil_dt) > '2017-05-10' THEN DATE_PART('year', fil_dt) ELSE reg_year_mh END AS mh_reg_year,
                            CASE WHEN reg_year_fh = 0 THEN DATE_PART('year', fil_dt_fh) ELSE reg_year_fh END AS fh_reg_year 
                            FROM main$is_archival_table m) t", "t.diary_no=m.diary_no", 'inner');
            $builder->where("((t.case_type=$caseTypeId AND t.mh_reg_year=$caseYear AND t.part1=$caseNo) 
                                OR (t.fh_case_type=$caseTypeId AND t.fh_reg_year=$caseYear AND t.fh_part1=$caseNo))");
        }
       //  pr($builder->getCompiledSelect());
        //pr('sssss'.$case_number_to);
        $builder1 = $builder->get();
        if (count($builder1->getResultArray()) >= 1) {
            if (!empty($if_return_array) && $if_return_array == 'A') {
                $result = $builder1->getResultArray();
            } elseif (!empty($if_return_array) && $if_return_array == 'R') {
                $result = $builder1->getRowArray();
            } else {
                $result1 = $builder1->getRowArray();
                $result = $result1['dn'] . $result1['dy'];
            }
        }
    }
    if (empty($result)) {
       
        if ($flag == 'AB' || $flag == 'B') {
           
            $query = $db->table("main_casetype_history$is_archival_table h");
            $query->select('h.old_registration_number,h.diary_no, new_registration_number, left((cast(h.diary_no as text)),-4) AS dn, right((cast(h.diary_no as text)),4) as dy');
          
            if (!empty($case_number_to)) {
                $query->join('(SELECT diary_no,
                        CASE WHEN new_registration_number::text ~ \'^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$\'::text
                                THEN CAST((SPLIT_PART(h.new_registration_number, \'-\', 1)) AS INTEGER)
                                ELSE 0::INTEGER
                             END AS case_type,
                        CASE WHEN new_registration_number::text ~ \'^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$\'::text
                                THEN CAST((SPLIT_PART(h.new_registration_number, \'-\', 2)) AS INTEGER)
                                ELSE 0::INTEGER
                             END AS part1,
                        CASE WHEN new_registration_number::text ~ \'^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$\'::text
                                THEN CAST((SPLIT_PART(h.new_registration_number, \'-\', 3)) AS INTEGER)
                                ELSE 0::INTEGER
                             END AS part2,
                        new_registration_year,
                        
                        CASE WHEN old_registration_number::text ~ \'^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$\'::text
                                THEN CAST((SPLIT_PART(h.old_registration_number, \'-\', 1)) AS INTEGER)
                                ELSE 0::INTEGER
                             END AS old_case_type,
                        CASE WHEN old_registration_number::text ~ \'^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$\'::text
                                THEN CAST((SPLIT_PART(h.old_registration_number, \'-\', 2)) AS INTEGER)
                                ELSE 0::INTEGER
                             END AS old_part1,
                        CASE WHEN old_registration_number::text ~ \'^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$\'::text
                                THEN CAST((SPLIT_PART(h.old_registration_number, \'-\', 3)) AS INTEGER)
                                ELSE 0::INTEGER
                             END AS old_part2,
                        old_registration_number
                        
                  FROM main_casetype_history' . $is_archival_table . ' h
                  WHERE is_deleted=\'f\') t', 't.diary_no = h.diary_no AND 
                  t.new_registration_year = h.new_registration_year AND h.ref_new_case_type_id = t.case_type and
                  t.old_registration_number = h.old_registration_number AND h.ref_old_case_type_id = t.old_case_type
                  ', 'inner');

                $query->where("((case_type=$caseTypeId  and h.new_registration_year=$caseYear and  $caseNo between part1 and part2) or 
                         (old_case_type=$caseTypeId  and h.old_registration_year=$caseYear and  $caseNo between old_part1 and old_part2))");
            } else {
                  
                $query->join('(SELECT diary_no,
                        CASE WHEN new_registration_number::text ~ \'^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$\'::text
                                THEN CAST((SPLIT_PART(h.new_registration_number, \'-\', 1)) AS INTEGER)
                                ELSE 0::INTEGER
                             END AS case_type,
                        CASE WHEN new_registration_number::text ~ \'^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$\'::text
                                THEN CAST((SPLIT_PART(h.new_registration_number, \'-\', 2)) AS INTEGER)
                                ELSE 0::INTEGER
                             END AS part1,
                        CASE WHEN new_registration_number::text ~ \'^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$\'::text
                                THEN CAST((SPLIT_PART(h.new_registration_number, \'-\', 2)) AS INTEGER)
                                ELSE 0::INTEGER
                             END AS part2,
                        new_registration_year,
                        
                        CASE WHEN old_registration_number::text ~ \'^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$\'::text
                                THEN CAST((SPLIT_PART(h.old_registration_number, \'-\', 1)) AS INTEGER)
                                ELSE 0::INTEGER
                             END AS old_case_type,
                        CASE WHEN old_registration_number::text ~ \'^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$\'::text
                                THEN CAST((SPLIT_PART(h.old_registration_number, \'-\', 2)) AS INTEGER)
                                ELSE 0::INTEGER
                             END AS old_part1,
                        CASE WHEN old_registration_number::text ~ \'^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$\'::text
                                THEN CAST((SPLIT_PART(h.old_registration_number, \'-\', 2)) AS INTEGER)
                                ELSE 0::INTEGER
                             END AS old_part2,
                        old_registration_number
                        
                  FROM main_casetype_history' . $is_archival_table . ' h
                  WHERE is_deleted=\'f\') t', 't.diary_no = h.diary_no AND 
                  t.new_registration_year = h.new_registration_year AND h.ref_new_case_type_id = t.case_type and
                  t.old_registration_number = h.old_registration_number AND h.ref_old_case_type_id = t.old_case_type
                  ', 'inner');

                $query->where("((case_type=$caseTypeId  and h.new_registration_year=$caseYear and part1=$caseNo) or 
                         (old_case_type=$caseTypeId  and h.old_registration_year=$caseYear and old_part1=$caseNo))");
                $query->where('h.is_deleted', 'f');
               // pr($query->getCompiledSelect());
            //pr('sssss'.$case_number_to);
            }
            $query2 = $query->get();
            if (count($query2->getResultArray()) >= 1) {
                if (!empty($if_return_array) && $if_return_array == 'A') {
                    $result2 = $query2->getResultArray();
                } elseif (!empty($if_return_array) && $if_return_array == 'R') {
                    $result2 = $query2->getRowArray();
                } else {
                    $result_2 = $query2->getRowArray();
                    $result2 = $result_2['dn'] . $result_2['dy'];
                }
                $result = $result2;
            }
        }
    }
    return $result;
}
function get_ref_agency_code_details($ddl_st_agncy, $ddl_bench)
{
    $db = \Config\Database::connect();
    $get_dno = "select s.agency_state,c.agency_name from master.ref_agency_code c join master.ref_agency_state s on c.cmis_state_id= s.cmis_state_id where c.id='$ddl_bench' and s.cmis_state_id='$ddl_st_agncy'";
    $query = $db->query($get_dno);
    if ($query->getNumRows() >= 1) {
        $result = $query->getRowArray();
        return $result;
    }
}

/* Code added by Shilpa -- Start 7th Dec */


function copying_weight_calculator($total_pages, $total_red_wrappers)
{
    $weight = 0;
    if ($total_pages >= 1 and $total_pages <= 5) {
        //envelop no. 5 & addtional 1 gram for glue/pinup and barcode sticker
        $weight = 3 + 1;
    } else if ($total_pages >= 6 and $total_pages <= 8) {
        //envelop no. 6 & addtional 2 gram for glue/pinup and barcode sticker
        $weight = 6 + 2;
    } else if ($total_pages >= 9 and $total_pages <= 10) {
        //envelop no. 7 & addtional 3 gram for glue/pinup and barcode sticker
        $weight = 12 + 3;
    } else if ($total_pages >= 11 and $total_pages <= 20) {
        //envelop no. A4 & addtional 4 gram for glue/pinup and barcode sticker
        $weight = 20 + 4;
    } else if ($total_pages >= 21 and $total_pages <= 500) {
        //envelop no. 8 & addtional 5 gram for glue/pinup and barcode sticker
        $weight = 35 + 5;
    } else {
        //envelop no. 8 for above 500 pages & addtional 5 gram for glue/pinup and barcode sticker
        $additional_weight_times = ceil($total_pages / 500);
        $weight = (35 + 5) * $additional_weight_times;
    }
    //75 gsm page equal to 4 gram and wrap has 2 gram of weight
    $weight += ($total_pages * 4) + ($total_red_wrappers * 2);
    return $weight;
}

function force_download($filename = '', $data = '', $set_mime = FALSE)
{
    if ($filename === '' or $data === '') {
        return;
    } elseif ($data === NULL) {
        if (! @is_file($filename) or ($filesize = @filesize($filename)) === FALSE) {
            return;
        }

        $filepath = $filename;
        $filename = explode('/', str_replace(DIRECTORY_SEPARATOR, '/', $filename));
        $filename = end($filename);
    } else {
        $filesize = strlen($data);
    }

    // Set the default MIME type to send
    $mime = 'application/octet-stream';

    $x = explode('.', $filename);
    $extension = end($x);

    if ($set_mime === TRUE) {
        if (count($x) === 1 or $extension === '') {
            /* If we're going to detect the MIME type,
				 * we'll need a file extension.
				 */
            return;
        }

        // Load the mime types
        $mimes = &get_mimes();

        // Only change the default MIME if we can find one
        if (isset($mimes[$extension])) {
            $mime = is_array($mimes[$extension]) ? $mimes[$extension][0] : $mimes[$extension];
        }
    }

    /* It was reported that browsers on Android 2.1 (and possibly older as well)
		 * need to have the filename extension upper-cased in order to be able to
		 * download it.
		 *
		 * Reference: http://digiblog.de/2011/04/19/android-and-the-download-file-headers/
		 */
    if (count($x) !== 1 && isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/Android\s(1|2\.[01])/', $_SERVER['HTTP_USER_AGENT'])) {
        $x[count($x) - 1] = strtoupper($extension);
        $filename = implode('.', $x);
    }

    if ($data === NULL && ($fp = @fopen($filepath, 'rb')) === FALSE) {
        return;
    }

    // Clean output buffer
    if (ob_get_level() !== 0 && @ob_end_clean() === FALSE) {
        @ob_clean();
    }

    // Generate the server headers
    header('Content-Type: ' . $mime);
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Expires: 0');
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: ' . $filesize);
    header('Cache-Control: private, no-transform, no-store, must-revalidate');

    // If we have raw data - just dump it
    if ($data !== NULL) {
        exit($data);
    }

    // Flush 1MB chunks of data
    while (! feof($fp) && ($data = fread($fp, 1048576)) !== FALSE) {
        echo $data;
    }

    fclose($fp);
    exit;
}


/* Code added by Shilpa -- End */


//CODE STARTS HERE BY - P.S

function get_diary_no_from_casetype($caseTypeId = null, $caseNo = null, $caseYear = null)
{
    $db = \Config\Database::connect();
    if (($caseTypeId != null) && ($caseNo != null) && ($caseYear != null)) {
        $query = $db->table('main_casetype_history_a h');
        $query->select('h.diary_no, new_registration_number, left((cast(h.diary_no as text)),-4) AS dn, right((cast(h.diary_no as text)),4) as dy');
        $query->join('(SELECT diary_no,
                        CASE WHEN new_registration_number::text ~ \'^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$\'::text
                                THEN CAST((SPLIT_PART(h.new_registration_number, \'-\', 1)) AS INTEGER)
                                ELSE 0::INTEGER
                             END AS case_type,
                        CASE WHEN new_registration_number::text ~ \'^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$\'::text
                                THEN CAST((SPLIT_PART(h.new_registration_number, \'-\', 2)) AS INTEGER)
                                ELSE 0::INTEGER
                             END AS part1,
                        CASE WHEN new_registration_number::text ~ \'^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$\'::text
                                THEN CAST((SPLIT_PART(h.new_registration_number, \'-\', 3)) AS INTEGER)
                                ELSE 0::INTEGER
                             END AS part2,
                        new_registration_year
                  FROM main_casetype_history_a h
                  WHERE is_deleted=\'f\') t', 't.diary_no = h.diary_no AND t.new_registration_year = h.new_registration_year AND h.ref_new_case_type_id = t.case_type', 'inner');
        $query->where('case_type', $caseTypeId);
        $query->where('h.new_registration_year', $caseYear);
        $query->where("$caseNo BETWEEN part1 AND part2");
        $query->where('h.is_deleted', 'f');

        $query1 = $query->get();
        if (count($query1->getResultArray()) > 0) {
            $result = $query1->getResultArray();
            return $result;
        } else {
            $query = $db->table('main_casetype_history h');
            $query->select('h.diary_no, new_registration_number, left((cast(h.diary_no as text)),-4) AS dn, right((cast(h.diary_no as text)),4) as dy');
            $query->join('(SELECT diary_no,
                        CASE WHEN new_registration_number::text ~ \'^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$\'::text
                                THEN CAST((SPLIT_PART(h.new_registration_number, \'-\', 1)) AS INTEGER)
                                ELSE 0::INTEGER
                             END AS case_type,
                        CASE WHEN new_registration_number::text ~ \'^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$\'::text
                                THEN CAST((SPLIT_PART(h.new_registration_number, \'-\', 2)) AS INTEGER)
                                ELSE 0::INTEGER
                             END AS part1,
                        CASE WHEN new_registration_number::text ~ \'^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$\'::text
                                THEN CAST((SPLIT_PART(h.new_registration_number, \'-\', 3)) AS INTEGER)
                                ELSE 0::INTEGER
                             END AS part2,
                        new_registration_year
                  FROM main_casetype_history h
                  WHERE is_deleted=\'f\') t', 't.diary_no = h.diary_no AND t.new_registration_year = h.new_registration_year AND h.ref_new_case_type_id = t.case_type', 'inner');
            $query->where('case_type', $caseTypeId);
            $query->where('h.new_registration_year', $caseYear);
            $query->where("$caseNo BETWEEN part1 AND part2");
            $query->where('h.is_deleted', 'f');

            $query1 = $query->get();
            $result = $query1->getResultArray();
            return $result;
        }
    }
}
function array_SORT_ASC_DESC($array, $key_name, $SORT_BY = '')
{
    if (!empty($array)) {
        if (!empty($array)) {
            foreach ($array as $key => $row) {
                $casecodes[$key] = $row[$key_name];
            }
            if ($SORT_BY == '') {
                array_multisort($casecodes, SORT_ASC, $array);
            } else {
                array_multisort($casecodes, SORT_DESC, $array);
            }
            $array;
        } else {
            $array = 'Array key Name is required';
        }
    } else {
        $array = 'Array is required';
    }
    return $array;
}

function get_sub_menus_ardar($q_usercode, $menu_id)
{
    $Menu_model = new \App\Models\Menu_model();
    $sqrs = $Menu_model->get_sub_menus($q_usercode, $menu_id);
    return $sqrs;
}

function f_get_cat_diary_basis($parm1)
{
    $db = \Config\Database::connect();
    $sql = "select sub_name1, sub_name2, sub_name3, sub_name4,category_sc_old from master.submaster s where s.id IN ($parm1) and s.display = 'Y'";
    $builder = $db->query($sql);
    $result = $builder->getResultArray();

    if (!empty($result)) {
        foreach ($result as $row) {
            $retn = $row["sub_name1"];
            if ($row["sub_name2"])
                $retn .= " - " . $row["sub_name2"];
            if ($row["sub_name3"])
                $retn .= " - " . $row["sub_name3"];
            if ($row["sub_name4"])
                $retn .= " - " . $row["sub_name4"];
            echo nl2br($retn . ' (' . $row["category_sc_old"] . ') ' . "\n");
        }
    } else {
        return false;
    }
}
function f_get_judge_names_inshort($chk_jud_id)
{

    $db = \Config\Database::connect();
    if ($chk_jud_id) {
        $sql = "select abbreviation FROM master.judge WHERE is_retired != 'Y' and jcode IN (" . rtrim($chk_jud_id, ',') . ")";
        $builder = $db->query($sql);
        $result = $builder->getResultArray();
        $jname = "";
        if (!empty($result)) {
            foreach ($result as $row) {
                $jname .= $row['abbreviation'] . ", ";
            }
            return rtrim(trim($jname), ",");
        }
    }
}

function f_get_ntl_judge($parm1)
{
    $sql = "SELECT j.abbreviation FROM advocate a LEFT JOIN master.ntl_judge n ON a.advocate_id = n.org_advocate_id
 LEFT JOIN master.judge j ON j.jcode = n.org_judge_id WHERE a.diary_no = '$parm1' 
 and j.is_retired != 'Y' AND a.display ='Y' AND n.display = 'Y' AND org_advocate_id IS NOT NULL 
 AND j.jcode IS NOT NULL group by abbreviation";
    $db = \Config\Database::connect();
    $builder = $db->query($sql);
    $result = $builder->getResultArray();
    if (!empty($result)) {
        foreach ($result as $row) {
            echo nl2br("<font color='red'> AOR N : " . $row["abbreviation"] . "</font>");
        }
    }
}

function f_get_ndept_judge($parm1)
{
    $sql = "SELECT j.abbreviation FROM party a LEFT JOIN master.ntl_judge_dept n ON a.deptcode = n.dept_id 
            LEFT JOIN master.judge j ON j.jcode = n.org_judge_id WHERE n.display = 'Y' and a.diary_no = '$parm1' 
            AND a.pflag != 'T'  AND j.is_retired != 'Y'
            AND a.deptcode IS NOT NULL AND j.jcode IS NOT NULL";
    $db = \Config\Database::connect();
    $builder = $db->query($sql);
    $result = $builder->getResultArray();
    if (!empty($result)) {
        foreach ($result as $row) {
            echo nl2br("<font color='red'> Dept N : " . $row["abbreviation"] . "</font>");
        }
    }
}

function f_get_category_judge($parm1)
{
    $sql = "SELECT j.abbreviation FROM (SELECT s.id FROM (SELECT s.id, sub_name1 FROM mul_category c, master.submaster s WHERE s.id = submaster_id AND 
        diary_no = '$parm1' AND c.display = 'Y' AND s.display = 'Y') a 
INNER JOIN master.submaster s ON s.sub_name1 = a.sub_name1
WHERE flag = 's') a
INNER JOIN master.ntl_judge_category n ON n.cat_id = a.id
LEFT JOIN master.judge j ON j.jcode = n.org_judge_id 
WHERE n.display = 'Y' AND j.jcode IS NOT NULL";
    $db = \Config\Database::connect();
    $builder = $db->query($sql);
    $result = $builder->getResultArray();
    if (!empty($result)) {
        foreach ($result as $row) {
            echo nl2br("<font color='red'> Categ. N : " . $row["abbreviation"] . "</font>");
        }
    }
}

function f_get_not_before($parm1)
{
    $sql = "select j.abbreviation, notbef from not_before b 
left join master.judge j on j.jcode = b.j1 WHERE j.is_retired != 'Y' and b.diary_no = '$parm1' order by notbef";
    $db = \Config\Database::connect();
    $builder = $db->query($sql);
    $result = $builder->getResultArray();
    if (!empty($result)) {
        foreach ($result as $row) {
            if ($row["notbef"] == "N")
                echo nl2br("<font color='red'> NB- " . $row["notbef"] . " : " . $row["abbreviation"] . "</font>");
            else
                echo nl2br("<font color='green'> NB- " . $row["notbef"] . " : " . $row["abbreviation"] . "</font>");
        }
    }
}

function f_cl_rgo_default($q_diary_no)
{
    $num_rows = 0;
    $sql = "SELECT * FROM rgo_default WHERE fil_no = '$q_diary_no' AND remove_def = 'N'";
    $db = \Config\Database::connect();
    $builder = $db->query($sql);
    $result = $builder->getRowArray();
    if (!empty($result)) {
        $num_rows = $result['fil_no2'];
    }
    return $num_rows;
}

function f_get_section_name_fdno($parm1)
{
    $sql = "SELECT tentative_section($parm1) as sname";
    $db = \Config\Database::connect();
    $builder = $db->query($sql);
    $result = $builder->getResultArray();
    if (!empty($result)) {
        foreach ($result as $row) {
            echo "{" . $row["sname"] . "} ";
        }
    }
}

function f_get_user_name_fdno($parm1)
{
    $sql = "SELECT u.name FROM main m INNER JOIN master.users u ON u.usercode = m.dacode WHERE u.display = 'Y' and m.diary_no = '$parm1'";
    $db = \Config\Database::connect();
    $builder = $db->query($sql);
    $result = $builder->getResultArray();
    if (!empty($result)) {
        foreach ($result as $row) {
            echo nl2br($row["name"] . "\n");
        }
    }
}

function get_pet_respondentby_diary($diaryno)
{
    $sql = "select pet_res, string_agg(adv,',') as name from (
                SELECT pet_res,concat(name,string_agg(DISTINCT adv, ',')) as adv
                FROM advocate a
                JOIN master.bar b ON a.advocate_id=b.bar_id
                WHERE pet_res IN ('P','R','I')
                AND diary_no = '$diaryno'
                AND display = 'Y'
                AND isdead = 'N'
                group by pet_res,b.name,a.pet_res_show_no
                order by pet_res_show_no)a
                group by pet_res";
    $db = \Config\Database::connect();
    $builder = $db->query($sql);
    $result = $builder->getResultArray();
    return $result;
}

function get_office_report($diaryno, $listing_date)
{
    $sql = "Select LEFT(CAST(diary_no AS TEXT), -4) as dno, RIGHT(CAST(diary_no AS TEXT), 4) as d_yr, office_repot_name,office_report_id,order_dt,rec_dt from office_report_details where diary_no='$diaryno' 
          and order_dt='$listing_date' and display='Y' and web_status=1 ";
    $db = \Config\Database::connect();
    $builder = $db->query($sql);
    $result = $builder->getResultArray();
    return $result;
}

/* Start function case_nos Anshu */

function get_case_nos($diary_no, $separator, $rby = '')
{
    $Model_IA = new \App\Models\Judicial\Model_IA();
    $db = \Config\Database::connect();
    $builder = $db->table('main m');
    $builder->select('casetype_id');
    $builder->select("CONCAT(m.active_fil_no, ':', 
        CASE 
            WHEN (active_reg_year = 0 OR CAST(active_fil_dt AS DATE) > '2017-05-10') THEN EXTRACT(YEAR FROM active_fil_dt) 
            ELSE active_reg_year 
        END, 
        ':', TO_CHAR(active_fil_dt, 'DD-MM-YYYY')) ad", false);
    $builder->select("CASE 
        WHEN fil_no_fh != active_fil_no AND fil_no_fh != fil_no AND fil_no_fh != '' THEN CONCAT(m.fil_no_fh, ':', 
            CASE 
                WHEN (reg_year_fh = 0 OR CAST(fil_dt_fh AS DATE) > '2017-05-10') THEN EXTRACT(YEAR FROM fil_dt_fh) 
                ELSE reg_year_fh 
            END, 
            ':', TO_CHAR(fil_dt_fh, 'DD-MM-YYYY')) 
        ELSE '' 
        END rd", false);
    $builder->select("CASE 
        WHEN fil_no != active_fil_no AND fil_no_fh != fil_no AND fil_no != '' THEN CONCAT(m.fil_no, ':', 
            CASE 
                WHEN (reg_year_mh = 0 OR CAST(fil_dt AS DATE) > '2017-05-10') THEN EXTRACT(YEAR FROM fil_dt) 
                ELSE reg_year_mh 
            END, 
            ':', TO_CHAR(fil_dt, 'DD-MM-YYYY')) 
        ELSE '' 
    END md", false);
    $builder->where('diary_no', $diary_no);
    
    $query = $builder->get();
    $row_main = $query->getRowArray();
    $cases = "";
    $t_fil_no = '';
    if (!empty($row_main)) {
        if ($row_main['ad'] != '') {
            $t_m_y = explode(':', $row_main['ad']);
            if ($t_m_y[0] != '') {
                $cases .= $t_m_y[0] . ",";
                $t_m1 = substr($t_m_y[0], 0, 2);
                $t_m2 = substr($t_m_y[0], 3, 6);
                $t_m21 = substr($t_m_y[0], 10, 6);
                $t_m3 = $t_m_y[1];
                $t_m4 = $t_m_y[2];
                $sql_ct_type = $Model_IA->get_short_description($t_m1);
                $res_ct_typ = '';
                $res_ct_typ_mf = '';
                if (!empty($sql_ct_type)) {
                    $row = $sql_ct_type;
                    $res_ct_typ = $row['short_description'];
                    $res_ct_typ_mf = $row['cs_m_f'];
                }
                if ($t_m2 == $t_m21) {
                    $t_fil_no .= '<font color="#043fff" style=" white-space: nowrap;">' . $res_ct_typ . " " . $t_m2 . ' / ' . $t_m3 . '</font>' . $separator . "(Reg.Dt." . $t_m4 . ")<br>";
                } else {
                    $t_fil_no .= '<font color="#043fff" style=" white-space: nowrap;">' . $res_ct_typ . " " . $t_m2 . ' - ' . $t_m21 . ' / ' . $t_m3 . '</font>' . $separator . "(Reg.Dt." . $t_m4 . ")<br>";
                }
            }
        }
        if ($row_main['rd'] != '') {
            $t_m_y = explode(':', $row_main['rd']);
            if ($t_m_y[0] != '') {
                $cases .= $t_m_y[0] . ",";
                $t_m1 = substr($t_m_y[0], 0, 2);
                $t_m2 = substr($t_m_y[0], 3, 6);
                $t_m21 = substr($t_m_y[0], 10, 6);
                $t_m3 = $t_m_y[1];
                $t_m4 = $t_m_y[2];
                $sql_ct_type = $Model_IA->get_short_description($t_m1);
                $res_ct_typ = '';
                $res_ct_typ_mf = '';
                if (!empty($sql_ct_type)) {
                    $row = $sql_ct_type;
                    $res_ct_typ = $row['short_description'];
                    $res_ct_typ_mf = $row['cs_m_f'];
                }
                if ($t_m2 == $t_m21) {
                    $t_fil_no .= '<font color="#043fff" style=" white-space: nowrap;">' . $res_ct_typ . " " . $t_m2 . ' / ' . $t_m3 . '</font>' . $separator . "(Reg.Dt." . $t_m4 . ")<br>";
                } else {
                    $t_fil_no .= '<font color="#043fff" style=" white-space: nowrap;">' . $res_ct_typ . " " . $t_m2 . ' - ' . $t_m21 . ' / ' . $t_m3 . '</font>' . $separator . "(Reg.Dt." . $t_m4 . ")<br>";
                }
            }
        }
        if ($row_main['md'] != '') {

            $t_m_y = explode(':', $row_main['md']);
            if ($t_m_y[0] != '') {
                $cases .= $t_m_y[0] . ",";
                $t_m1 = substr($t_m_y[0], 0, 2);
                $t_m2 = substr($t_m_y[0], 3, 6);
                $t_m21 = substr($t_m_y[0], 10, 6);
                $t_m3 = $t_m_y[1];
                $t_m4 = $t_m_y[2];

                $sql_ct_type = $Model_IA->get_short_description($t_m1);
                $res_ct_typ = '';
                $res_ct_typ_mf = '';
                if (!empty($sql_ct_type)) {
                    $row = $sql_ct_type;
                    $res_ct_typ = $row['short_description'];
                    $res_ct_typ_mf = $row['cs_m_f'];
                }
                if ($t_m2 == $t_m21) {
                    $t_fil_no .= '<font color="#043fff" style=" white-space: nowrap;">' . $res_ct_typ . " " . $t_m2 . ' / ' . $t_m3 . '</font>' . $separator . "(Reg.Dt." . $t_m4 . ")<br>";
                } else {
                    $t_fil_no .= '<font color="#043fff" style=" white-space: nowrap;">' . $res_ct_typ . " " . $t_m2 . ' - ' . $t_m21 . ' / ' . $t_m3 . '</font>' . $separator . "(Reg.Dt." . $t_m4 . ")<br>";
                }
            }
        }
    }

    $registration_details = $Model_IA->get_new_old_registration_details($diary_no);

    if (!empty($registration_details)) {
        $cnt = 0;
        foreach ($registration_details as $row_mc_h) {
            if ($row_mc_h['oldno'] != '') {
                $t_m = explode(',', $row_mc_h['oldno']);

                $t_m_y = explode(':', $t_m[0]);
                $pos = strpos($cases, $t_m_y[0]);

                if ($pos === false) {
                    $cnt++;
                    if ($cnt % 2 == 0)
                        $bgcolor = "#ff0015";
                    else
                        $bgcolor = "#ff01c8";
                    $cases .= $t_m_y[0] . ",";
                    $t_m1 = substr($t_m_y[0], 0, 2);
                    $t_m2 = substr($t_m_y[0], 3, 6);
                    $t_m21 = substr($t_m_y[0], 10, 6);
                    $t_m3 = $t_m_y[1];
                    $t_m4 = $t_m_y[2];

                    $sql_ct_type = $Model_IA->get_short_description($t_m1);
                    $res_ct_typ = '';
                    $res_ct_typ_mf = '';
                    if (!empty($sql_ct_type)) {
                        $row = $sql_ct_type;
                        $res_ct_typ = $row['short_description'];
                        $res_ct_typ_mf = $row['cs_m_f'];
                    }
                    if ($t_m2 == $t_m21) {
                        $t_fil_no .= '<font color="' . $bgcolor . '" style=" white-space: nowrap;">' . $res_ct_typ . " " . $t_m2 . ' / ' . $t_m3 . '</font>' . $separator . "(Reg.Dt." . $t_m4 . ")<br>";
                    } else {
                        $t_fil_no .= '<font color="' . $bgcolor . '" style=" white-space: nowrap;">' . $res_ct_typ . " " . $t_m2 . ' - ' . $t_m21 . ' / ' . $t_m3 . '</font>' . $separator . "(Reg.Dt." . $t_m4 . ")<br>";
                    }
                }
            }
            $t_chk = "";

            if ($row_mc_h['newno'] != '') {
                $t_m = explode(',', $row_mc_h['newno']);
                for ($i = 0; $i < count($t_m); $i++) {
                    $t_m_y = explode(':', $t_m[$i]);
                    $pos = strpos($cases, $t_m_y[0]);
                    if ($pos === false) {
                        $cases .= $t_m_y[0] . ",";
                        $t_m1 = substr($t_m_y[0], 0, 2);
                        $t_m2 = substr($t_m_y[0], 3, 6);
                        $t_m21 = substr($t_m_y[0], 10, 6);
                        $t_m3 = $t_m_y[1];
                        $t_m4 = $t_m_y[2];
                        $t_fn = $t_m_y[0];
                        if ($t_chk != $t_fn) {
                            $cnt++;
                            if ($cnt % 2 == 0) {
                                $bgcolor = "#ff0015";
                            } else {
                                $bgcolor = "#ff01c8";
                            }
                            $sql_ct_type = $Model_IA->get_short_description($t_m1);
                            $res_ct_typ = '';
                            $res_ct_typ_mf = '';
                            if (!empty($sql_ct_type)) {
                                $row = $sql_ct_type;
                                $res_ct_typ = $row['short_description'];
                                $res_ct_typ_mf = $row['cs_m_f'];
                            }
                            if ($t_m2 == $t_m21) {
                                $t_fil_no .= '<font color="' . $bgcolor . '" style=" white-space: nowrap;">' . $res_ct_typ . " " . $t_m2 . ' / ' . $t_m3 . '</font>' . $separator . "(Reg.Dt." . $t_m4 . ")<br>";
                            } else {
                                $t_fil_no .= '<font color="' . $bgcolor . '" style=" white-space: nowrap;">' . $res_ct_typ . " " . $t_m2 . ' - ' . $t_m21 . ' / ' . $t_m3 . '</font>' . $separator . "(Reg.Dt." . $t_m4 . ")<br>";
                            }
                        }
                        $t_chk = $t_fn;
                    }
                }
            }
        }
    }
    if (trim($t_fil_no) == '') {
        if (!empty($row_main['casetype_id'])) {
            $get_short_description = $Model_IA->get_short_description($row_main['casetype_id']);
            if (!empty($get_short_description)) {
                $t_fil_no = $get_short_description['short_description'];
            }
        }
    }
    return $t_fil_no;
}

function get_advocates_by_id($adv_id)
{
    // Get database connection
    $db = \Config\Database::connect();

    $t_adv = "";
    // Assuming $adv_id is properly sanitized and an integer
    $adv_id = intval($adv_id);

    // Using CodeIgniter's Query Builder
    $builder = $db->table('master.bar');
    $builder->select('name');
    $builder->where('bar_id', $adv_id);

    $query = $builder->get();

    if ($query->getNumRows() > 0) {
        $row11a = $query->getRowArray(); // Fetch all results
        $t_adv = $row11a['name'];
    }

    return $t_adv;
}

function get_advocates($dairy_no)
{
    // Get database connection
    $db = \Config\Database::connect();
    // Build the query
    $builder = $db->table('advocate');
    $builder->select('advocate_id AS id, CONCAT(name, "-", aor_code) AS desg');
    $builder->join('master.bar', 'advocate.advocate_id = bar.bar_id', 'left');
    $builder->where(['advocate.display' => 'Y', 'bar.diary_no' => $dairy_no]);
    $builder->orderBy('pet_res');
    // echo $query = $builder->getCompiledSelect();
    // die;
    $query = $builder->get();

    // Fetch results
    $result = $query->getResultArray();

    // Process results
    $send_too = [];
    foreach ($result as $row) {
        $send_too[] = $row['id'] . '^' . $row['desg'];
    }

    return $send_too;
}

function get_advocates_new($adv_id, $wen = '')
{
    $db = \Config\Database::connect();
    $t_adv = "";

    $builder = $db->table('master.bar');
    $builder->select('name,enroll_no,enroll_date as eyear, isdead');
    $builder->where(['bar_id' => $adv_id]);
    $query = $builder->get();
    $sql11a = $query->getResultArray();


    if (!empty($sql11a)) {
        foreach ($sql11a as $row11a) {
            $t_adv = $row11a['name'];
            if ($row11a['isdead'] == 'Y')
                $t_adv = "<font color=red>" . $t_adv . " (Dead / Retired / Elevated) </font>";
            if ($wen == 'wen')
                $t_adv .= " [" . $row11a['enroll_no'] . "/" . $row11a['eyear'] . "]";
        }
    }
    return $t_adv;
}

function get_display_status_with_date_differnces($tentative_cl_dt)
{
    $tentative_cl_date_greater_than_today_flag = "F";
    $curDate = date('d-m-Y');
    $tentativeCLDate = date('d-m-Y', strtotime($tentative_cl_dt));
    $datediff = strtotime($tentativeCLDate) - strtotime($curDate);
    $noofdays = round($datediff / (60 * 60 * 24));


    if (strtotime($tentativeCLDate) > strtotime($curDate)) {

        if ($noofdays <= 60 && $noofdays > 0) {
            //echo "no of days ddd".$noofdays;
            $tentative_cl_date_greater_than_today_flag = 'T';
        }
    } else {
        $tentative_cl_date_greater_than_today_flag = 'F';
    }
    return $tentative_cl_date_greater_than_today_flag;
}

function get_lc_highcourt($dairy_no)
{
    $db = \Config\Database::connect();

    // Fetch the case type ID
    $builder = $db->table('main');
    $builder->select('active_casetype_id');
    $builder->where('diary_no', $dairy_no);
    $query = $builder->get();
    $res_casetype_id = $query->getRow()->active_casetype_id;

    // Initialize additional diary condition
    $additional_diary = '';

    // Check if the case type ID matches the specified values
    if (in_array($res_casetype_id, ['9', '10', '25', '26'])) {
        $builder = $db->table('lowerct');
        $builder->select('lct_casetype, lct_caseno, lct_caseyear');
        $builder->where('diary_no', $dairy_no);
        $builder->where('ct_code', '4');
        $builder->where('lw_display', 'Y');
        $builder->whereNotIn('lct_casetype', ['9', '10', '25', '26']);
        $query = $builder->get();

        if (count($query->getResultArray()) > 0) {
            $get_diary_case_type = function ($casetype, $caseno, $caseyear) {
                // Implement the logic for get_diary_case_type or include relevant file
                // This is a placeholder, replace with actual implementation
                return 'some_case_type';
            };

            $cases = $query->getResultArray();
            $diaries = [];
            foreach ($cases as $row) {
                $case_type = $get_diary_case_type($row['lct_casetype'], $row['lct_caseno'], $row['lct_caseyear']);
                $diaries[] = $case_type;
            }
            $additional_diary = " OR diary_no IN (" . implode(',', $diaries) . ")";
        }
    }

    // Determine if additional field for judge designation is needed
    $lct_judge_desg_s = '';
    if (in_array($res_casetype_id, ['7', '8'])) {
        $lct_judge_desg = ", lct_judge_desg";
    } else {
        $lct_judge_desg = '';
    }

    // Build and execute the main query
    $builder = $db->table('lowerct a');
    $builder->select("DISTINCT MIN(lower_court_id) AS id, CONCAT(
        IF (
            ct_code = 3, (
                SELECT Name
                FROM master.state s
                WHERE s.id_no = a.l_dist
                AND display = 'Y'
            ), (
                SELECT agency_name
                FROM master.ref_agency_code c
                WHERE c.cmis_state_id = a.l_state
                AND c.id = a.l_dist
                AND is_deleted = 'f'
            )
        ), ' ', b.Name
    ) AS desg $lct_judge_desg");
    $builder->join('master.state b', 'a.l_state = b.id_no');
    $builder->where('a.diary_no', $dairy_no);
    $builder->where('a.lw_display', 'Y');
    $builder->where('b.display', 'Y');
    $builder->groupBy('a.l_state, a.l_dist' . $lct_judge_desg);
    $query = $builder->get();

    // Fetch results
    $result = $query->getResultArray();

    // Process results
    $send_too = [];
    foreach ($result as $row) {
        if (!empty($row['lct_judge_desg']) && $row['lct_judge_desg'] != '0') {
            // Implement get_lower_court_judge or include relevant file
            // This is a placeholder, replace with actual implementation
            $get_lower_court_judge = function ($judge_desg) {
                return 'Judge Name';
            };
            $lct_judge_desg_s = $get_lower_court_judge($row['lct_judge_desg']) . ' - ';
        }
        $send_too[] = $row['id'] . '^' . $lct_judge_desg_s . $row['desg'];
    }

    return $send_too;
}

if (!function_exists('get_citys')) {
    function get_citys($str)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('master.state');

        $builder->select('id_no, name');
        $builder->where('state_code', function ($subQuery) use ($str) {
            $subQuery->select('state_code')
                ->from('master.state')
                ->where('id_no', $str)
                ->where('display', 'Y');
        });
        $builder->where('sub_dist_code', '0');
        $builder->where('district_code !=', 0);
        $builder->where('village_code', '0');
        $builder->orderBy('name');

        $query = $builder->get();
        $result = $query->getResultArray();

        $dis_state = [];
        foreach ($result as $row) {
            $dis_state[] = $row['id_no'] . "^" . $row['name'];
        }

        return $dis_state;
    }
}

// Added By Ashutosh

function f_get_judges_names_by_jcode($jcodes)
{
    $db = \Config\Database::connect();
    $jnames = "";
    if ($jcodes != '') {
        $t_jc = explode(",", $jcodes);
        for ($i = 0; $i < count($t_jc); $i++) {
            $sql11a = "SELECT jname FROM master.judge where jcode= " . $t_jc[$i] . ";";
            $query = $db->query($sql11a);
            $t11a = $query->getResultArray();
            if (count($t11a) > 0) {
                foreach ($t11a as $row11a) {
                    if ($jnames == '')
                        $jnames .= $row11a["jname"];
                    else {
                        if ($i == (count($t_jc) - 1))
                            $jnames .= " and " . $row11a["jname"];
                        else
                            $jnames .= ", " . $row11a["jname"];
                    }
                }
            }
        }
    }
    return $jnames;
}

function f_get_advocate_count_with_connected($conn_key, $next_dt)
{
    $db = \Config\Database::connect();
    $sql = "select count(distinct advocate_id) as count_adv from 
        (select m.diary_no from main m where m.conn_key = '$conn_key' and c_status = 'P'
        union
        select m.diary_no from main m
        inner join conct ct on ct.conn_key::int = m.conn_key::int
        where m.conn_key = '$conn_key' and ct.list = 'Y' and m.c_status = 'P') m
        inner join advocate a on m.diary_no = a.diary_no
        inner join heardt h on h.diary_no = m.diary_no 
        where h.clno > 0 and h.next_dt = '$next_dt' and a.display = 'Y'";

    $result = $db->query($sql);
    $value = $result->getRowObject();
    return $value->count_adv;
}

function f_get_advocate_count($diary_no)
{
    $db = \Config\Database::connect();
    $sql = "select count(distinct advocate_id) as count_adv from advocate where diary_no = '$diary_no' and display = 'Y'";
    $result = $db->query($sql);
    $value = $result->getRowObject();
    return $value->count_adv;
}

function f_connected_case_count_listed($conn_key, $next_dt)
{
    $db = \Config\Database::connect();
    $sql = "select count(m.diary_no) as total_connected from 
        (select m.diary_no, m.conn_key from main m where m.conn_key = '$conn_key' and c_status = 'P'
        union
        select m.diary_no, m.conn_key from main m
        inner join conct ct on ct.conn_key::bigint = m.conn_key::bigint
        where m.conn_key = '$conn_key' and ct.list = 'Y' and m.c_status = 'P') m
        inner join heardt h on h.diary_no = m.diary_no 
        where m.diary_no::bigint != m.conn_key::bigint and h.clno > 0 and h.next_dt = '$next_dt' ";

    $result = $db->query($sql);
    $value = $result->getRowObject();
    return $value->total_connected;
}

function get_client_ip()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function multi_attach_mail($to, $subject, $message, $senderMail, $senderName, $files)
{
    $from = $senderName . " <" . $senderMail . ">";
    $headers = "From: $from";
    // boundary
    $semi_rand = md5(time());
    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
    // headers for attachment
    $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";
    // multipart boundary
    $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n";
    // preparing attachments
    if (count($files) > 0) {
        for ($i = 0; $i < count($files); $i++) {
            if (is_file($files[$i])) {
                $message .= "--{$mime_boundary}\n";
                $fp =    @fopen($files[$i], "rb");
                $data =  @fread($fp, filesize($files[$i]));
                @fclose($fp);
                $data = chunk_split(base64_encode($data));
                $message .= "Content-Type: application/octet-stream; name=\"" . basename($files[$i]) . "\"\n" .
                    "Content-Description: " . basename($files[$i]) . "\n" .
                    "Content-Disposition: attachment;\n" . " filename=\"" . basename($files[$i]) . "\"; size=" . filesize($files[$i]) . ";\n" .
                    "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
            }
        }
    }
    $message .= "--{$mime_boundary}--";
    $returnpath = "-f" . $senderMail;
    //send email
    $mail = @mail($to, $subject, $message, $headers, $returnpath);
    // function return true, if email sent, otherwise return fasle
    if ($mail) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function field_mainhead()
{
?>
    <fieldset>
        <legend><b>Mainhead</b></legend>
        <input type="radio" name="mainhead" id="mainhead" value="M" title="Miscellaneous" checked="checked">M&nbsp;
        <input type="radio" name="mainhead" id="mainhead" value="F" title="Regular">R&nbsp;
        <!-- <input type="radio" name="mainhead" id="mainhead" value="L" title="Lok Adalat">L
        <input type="radio" name="mainhead" id="mainhead" value="S" title="Mediation">MD-->
    </fieldset>
<?php
}

function field_sel_roster_dts()
{
    $db = \Config\Database::connect();
?>
    <fieldset>
        <legend>Listing Dates</legend>
        <?php
        // $sql = "SELECT c.from_date FROM roster c WHERE m_f = '1' AND c.from_date >= CURDATE() AND c.display = 'Y' GROUP BY from_date";
        $sql = "SELECT c.next_dt FROM heardt c WHERE mainhead = 'M' AND c.next_dt >= CURRENT_DATE AND (c.main_supp_flag = '1' OR c.main_supp_flag = '2') GROUP BY next_dt";
        $res = $db->query($sql);
        ?>
        <select class="ele" name="listing_dts" id="listing_dts">
            <?php if (count($res->getResultArray()) > 0) { ?>
                <option value="-1" selected>SELECT</option>
                <?php foreach ($res->getResultArray() as $row) { ?>
                    <option value="<?php echo $row['next_dt']; ?>"><?php echo date("d-m-Y", strtotime($row['next_dt'])); ?></option>
                <?php
                }
            } else {
                ?>
                <option value="-1" selected>EMPTY</option>
            <?php } ?>
        </select>
    </fieldset>
<?php
}

function field_board_type()
{
?>
    <fieldset style="padding:5px; background-color:#F5FAFF; border:1px solid #0083FF;">
        <legend style="background-color:#E2F1FF; width:100%; text-align:center; border:1px solid #0083FF;"><b>Board Type</b></legend>
        <select class="ele" name="board_type" id="board_type">
            <option value="0">-ALL-</option>
            <option value="J">Court</option>
            <option value="S">Single Judge</option>
            <option value="C">Chamber</option>
            <option value="R">Registrar</option>
        </select>
    </fieldset>
<?php
}

function field_patno()
{
?>
    <fieldset>
        <legend>Part No.</legend>
        <select class="ele" name="part_no" id="part_no">
            <option value="-1" selected>EMPTY</option>
        </select>
    </fieldset>
<?php
}

function field_action_btn1()
{
?>
    <fieldset>
        <legend>Action</legend>
        <input type="button" name="btn1" id="btn1" value="Submit" />
    </fieldset>
<?php
}

function field_reshuffle()
{
?>
    <fieldset>
        <legend>Reshuffle</legend>
        <input type="text" name="resh_from_txt" id="resh_from_txt" value="0" maxlength="4" size="5" />
        <span id="resf_span" style="background: #5fa3f9; border: #ffffff; color: #ffffff; height: 12px; padding: 4px;"><b>FROM</b></span>
        <input type='button' name='re_shuffle' id='re_shuffle' value='Re-Shuffle' />
    </fieldset>
    <?php
}

function get_header_footer_print($list_dt, $mainhead, $roster_id, $part_no, $flag)
{
    $db = \Config\Database::connect();
    $sql = "SELECT h_f_note FROM headfooter WHERE display = 'Y' and next_dt = '$list_dt' AND part = '$part_no' and mainhead = '$mainhead' AND roster_id = '$roster_id' AND display='Y' AND h_f_flag = '$flag' ORDER BY ent_dt";
    
    $query = $db->query($sql);
    if ($query->getNumRows() >= 1) {
        $results = $query->getResultArray();
        if (count($results)) {
        ?>
            <table border="0" cellspacing="0">
                <tr>
                    <td style="text-align:left" class="text-bold"><U>NOTE</U>:-</td>
                </tr>
                <?php foreach ($results as $row) { ?>
                    <tr>
                        <td style="text-align:left" class="text-bold">
                            <?php echo $row['h_f_note'] ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        <?php
        }
    }    
}




if (!function_exists('get_header_footer_print_v1')) {
    function get_header_footer_print_v1($list_dt, $mainhead, $rosterStr, $main_suppl, $court_no, $part)
          
    {
        $db = \Config\Database::connect();
        $builder = $db->table('headfooter hf');

        $builder->distinct()
            ->select("TRIM(hf.h_f_note) AS h_f_note")
            ->join('master.roster r', 'hf.roster_id = r.id')
            ->join('cl_printed cp', 'cp.roster_id = hf.roster_id')
            ->where('cp.next_dt', $list_dt)
            ->where('cp.display', 'Y')
            ->where('cp.part = hf.part')
            ->where('cp.main_supp', $main_suppl)
            ->where('hf.display', 'Y')
            ->where('hf.next_dt', $list_dt)
            ->where('hf.mainhead', $mainhead)
            ->whereIn('hf.roster_id', explode(',', $rosterStr))
            ->where('hf.part', $part)
            ->whereIn('hf.h_f_flag', ['F', 'H'])
            ->where('r.courtno', $court_no)
            ->orderBy('hf.ent_dt');

        $query = $builder->get();
        return $query->getResultArray();
    }
}
if (!function_exists('getSpreadOutCertificateDetail')) {
function getSpreadOutCertificateDetail($diary_no)
{
    $diary_no = "'".$diary_no."'";
   $db = \Config\Database::connect();
   $sql = "SELECT
                diary_no,
                pdfname,
                orderdate
            FROM (
                SELECT
                    o.diary_no AS diary_no,
                    o.jm AS pdfname,
                    TO_CHAR(o.dated::DATE, 'YYYY-MM-DD') AS orderdate,
                    CASE
                        WHEN o.jt = 'rop' THEN 'ROP'
                        WHEN o.jt = 'judgment' THEN 'Judgement'
                        WHEN o.jt = 'or' THEN 'Office Report'
                    END AS jo
                FROM
                    tempo o
                WHERE
                    o.diary_no = $diary_no 
                UNION ALL
                SELECT
                    o.diary_no AS diary_no,
                    o.pdfname AS pdfname,
                    TO_CHAR(o.orderdate::DATE, 'YYYY-MM-DD') AS orderdate,
                    CASE
                        WHEN o.type = 'O' THEN 'ROP'
                        WHEN o.type = 'J' THEN 'Judgement'
                    END AS jo
                FROM
                    ordernet o
                WHERE
                    o.diary_no = $diary_no 
                UNION ALL
                SELECT
                    o.dn AS diary_no,
                    CONCAT('ropor/rop/all/', o.pno, '.pdf') AS pdfname,
                    TO_CHAR(o.orderdate::DATE, 'YYYY-MM-DD') AS orderdate,
                    'ROP' AS jo
                FROM
                    rop_text_web.old_rop o
                WHERE
                    o.dn = $diary_no 
                UNION ALL
                SELECT
                    o.dn AS diary_no,
                    CONCAT('judis/', o.filename, '.pdf') AS pdfname,
                    TO_CHAR(o.juddate::DATE, 'YYYY-MM-DD') AS orderdate,
                    'Judgment' AS jo
                FROM
                    scordermain o
                WHERE
                    o.dn = $diary_no  
                UNION ALL
                SELECT
                    o.dn AS diary_no,
                    CONCAT('bosir/orderpdf/', o.pno, '.pdf') AS pdfname,
                    TO_CHAR(o.orderdate::DATE, 'YYYY-MM-DD') AS orderdate,
                    'ROP' AS jo
                FROM
                    rop_text_web.ordertext o
                WHERE
                    o.dn = $diary_no  AND o.display = 'Y'
                UNION ALL
                SELECT
                    o.dn AS diary_no,
                    CONCAT('bosir/orderpdfold/', o.pno, '.pdf') AS pdfname,
                    TO_CHAR(o.orderdate::DATE, 'YYYY-MM-DD') AS orderdate,
                    'ROP' AS jo
                FROM
                    rop_text_web.oldordtext o
                WHERE
                    o.dn = $diary_no  
            ) AS tbl1
            WHERE
                jo = 'ROP'
            ORDER BY
                orderdate DESC"; 
                // echo $sql;
                // die();
    
                $query = $db->query($sql);
                $result = $query->getResultArray();
                return $result;
}
}



function dcr_get_drop_note_print($list_dt, $mainhead, $roster_id)
{
    $db = \Config\Database::connect();
    // Prepare the query with placeholders
    $sql = "
        SELECT
            d.clno, 
            COALESCE(d.nrs, '-') AS nrs,
            d.mf,
            d.diary_no,
            CASE 
                WHEN (m.active_reg_year IS NULL OR m.active_reg_year = 0 OR m.active_reg_year::text = '') THEN m.diary_no::text
                ELSE CONCAT(
                    short_description, 
                    '/', 
                    CASE 
                        WHEN TRIM(LEADING '0' FROM substring(m.active_fil_no FROM 1 FOR position('-' IN m.active_fil_no)-1)) = TRIM(LEADING '0' FROM substring(m.active_fil_no FROM position('-' IN m.active_fil_no)+1 FOR length(m.active_fil_no)))
                        THEN TRIM(LEADING '0' FROM substring(m.active_fil_no FROM 1 FOR position('-' IN m.active_fil_no)-1))
                        ELSE CONCAT(
                            TRIM(LEADING '0' FROM substring(m.active_fil_no FROM 1 FOR position('-' IN m.active_fil_no)-1)), 
                            '-',
                            TRIM(LEADING '0' FROM substring(m.active_fil_no FROM position('-' IN m.active_fil_no)+1 FOR length(m.active_fil_no)))
                        )
                    END, 
                    '/', 
                    m.active_reg_year
                )
            END AS case_no
        FROM
            drop_note d
            INNER JOIN main m ON m.diary_no = d.diary_no
            LEFT JOIN master.casetype c ON c.casecode = m.active_casetype_id
        WHERE 
            d.cl_date = ? 
            AND d.display = 'Y'  
            AND d.roster_id = ? 
            AND d.mf = ? 
        ORDER BY 
            d.clno;
    ";
    
    // print_r([$list_dt, $roster_id, $mainhead]);
    // pr($sql);

    // Bind the parameters and execute the query
    $query = $db->query($sql, [$list_dt, $roster_id, $mainhead]);

        
        // $sql="SELECT
        // d.clno, 
        // IFNULL(d.nrs,'-') AS nrs,
        // d.mf,
        // d.diary_no,
        // IF((m.active_reg_year IS NULL OR m.active_reg_year = 0 OR m.active_reg_year = ''), m.diary_no,
        // CONCAT(short_description,'/',(CASE WHEN
        // TRIM(LEADING '0' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(m.active_fil_no,'-',2),'-',-1)) = TRIM(LEADING '0' FROM SUBSTRING_INDEX(m.active_fil_no,'-',-1))
        // THEN TRIM(LEADING '0' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(m.active_fil_no,'-',2),'-',-1))
        // ELSE CONCAT(TRIM(LEADING '0' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(m.active_fil_no,'-',2),'-',-1)),'-',TRIM(LEADING '0' FROM SUBSTRING_INDEX(m.active_fil_no,'-',-1))) END),'/',m.active_reg_year)) AS case_no
        // FROM
        // drop_note d
        // INNER JOIN main m ON m.diary_no = d.diary_no
        // left JOIN casetype c ON c.casecode = m.active_casetype_id
        // WHERE d.cl_date = '$list_dt'
        // AND d.display = 'Y'  
        // AND d.roster_id = '$roster_id' 
        // AND d.mf = '$mainhead'
        // ORDER BY d.clno";
        // $res=mysql_query($sql) or die(mysql_error());

        if($query->getNumRows() > 0) {
            ?>        
            <table class="mobview" border="1" style="font-size:12px; text-align: left; background: #ffffff;" cellspacing=0>
                <tr><td style="text-align:left" colspan="3"><U>DROP NOTE</U>:-</td></tr>
            <tr><td style="text-align:left">Item No.</td><td style="text-align:left">Case No.</td><td style="text-align:left">Reason</td></tr>
        <?php
            // Fetch the result
            $results = $query->getResultArray();

            // Process the results
            foreach ($results as $row) {
        ?>
            <tr>
                <td style="text-align:left">
                    <?php echo $row['clno'] ?>
                </td>
                <td style="text-align:left">
                    <?php echo $row['case_no'] ?>
                </td>
                <td style="text-align:left">
                    <?php echo $row['nrs'] ?>
                </td>
            </tr>      
    <?php
            }
    ?>   </table><?php
        }    
}

function get_drop_note_print($list_dt, $mainhead, $roster_id)
{
    $db = \Config\Database::connect();
    // $sql = "SELECT m.c_status, h.roster_id AS p_r_id, h.next_dt AS p_next_dt, h.clno AS p_clno, h.brd_slno AS p_brd_slno, h.main_supp_flag AS p_ms_flag, d.clno, IFNULL(d.nrs,'-') AS nrs, d.mf, d.roster_id, d.diary_no, IF((m.active_reg_year IS NULL OR m.active_reg_year = 0 OR m.active_reg_year = ''), CONCAT('Dno ',LEFT(m.diary_no,LENGTH(m.diary_no)-4),'-',RIGHT(m.diary_no, 4)),
    //     CONCAT(short_description,'/',(CASE WHEN 
    //     TRIM(LEADING '0' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(m.active_fil_no,'-',2),'-',-1)) = TRIM(LEADING '0' FROM SUBSTRING_INDEX(m.active_fil_no,'-',-1))
    //     THEN TRIM(LEADING '0' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(m.active_fil_no,'-',2),'-',-1)) 
    //     ELSE CONCAT(TRIM(LEADING '0' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(m.active_fil_no,'-',2),'-',-1)),'-',TRIM(LEADING '0' FROM SUBSTRING_INDEX(m.active_fil_no,'-',-1))) END),'/',m.active_reg_year)) AS case_no,
    //     (CASE WHEN pno = 2 THEN CONCAT(m.pet_name, ' AND ANR.') WHEN pno > 2 THEN CONCAT(m.pet_name, ' AND ORS.') ELSE m.pet_name END) AS pname, 
    //     (CASE WHEN rno = 2 THEN CONCAT(m.res_name, ' AND ANR.') WHEN rno > 2 THEN CONCAT(m.res_name, ' AND ORS.') ELSE m.res_name END) AS rname
    //     FROM
    //     drop_note d
    //     INNER JOIN main m ON m.diary_no = d.diary_no
    //     left JOIN heardt h ON h.diary_no = m.diary_no
    //     LEFT JOIN master.roster r ON r.id = h.roster_id AND r.display = 'Y'
    //     left JOIN master.casetype c ON c.casecode = m.active_casetype_id
    //     WHERE d.cl_date = '$list_dt' 
    //     AND d.display = 'Y'   
    //     AND d.roster_id = '$roster_id'  
    //     AND d.mf = '$mainhead'
    //     ORDER BY d.clno";

    $sql = "SELECT m.c_status, h.roster_id AS p_r_id, h.next_dt AS p_next_dt, h.clno AS p_clno, 
            h.brd_slno AS p_brd_slno, h.main_supp_flag AS p_ms_flag, d.clno, 
            COALESCE(d.nrs, '-') AS nrs, d.mf, d.roster_id, d.diary_no, 
            CASE WHEN m.active_reg_year IS NULL OR m.active_reg_year = 0 OR m.active_reg_year::text = '' 
                THEN CONCAT('Dno ', LEFT(m.diary_no::text, LENGTH(m.diary_no::text) - 4), '-', RIGHT(m.diary_no::text, 4))
                ELSE CONCAT(short_description, '/', 
                            (CASE WHEN TRIM(BOTH '0' FROM SPLIT_PART(m.active_fil_no, '-', 2)) = 
                                    TRIM(BOTH '0' FROM SPLIT_PART(m.active_fil_no, '-', 3)) 
                                THEN TRIM(BOTH '0' FROM SPLIT_PART(m.active_fil_no, '-', 2)) 
                                ELSE CONCAT(TRIM(BOTH '0' FROM SPLIT_PART(m.active_fil_no, '-', 2)), '-', 
                                            TRIM(BOTH '0' FROM SPLIT_PART(m.active_fil_no, '-', 3))) 
                            END), '/', m.active_reg_year) 
            END AS case_no,
            CASE WHEN pno = 2 THEN CONCAT(m.pet_name, ' AND ANR.') 
                WHEN pno > 2 THEN CONCAT(m.pet_name, ' AND ORS.') 
                ELSE m.pet_name END AS pname, 
            CASE WHEN rno = 2 THEN CONCAT(m.res_name, ' AND ANR.') 
                WHEN rno > 2 THEN CONCAT(m.res_name, ' AND ORS.') 
                ELSE m.res_name END AS rname
            FROM drop_note d
            INNER JOIN main m ON m.diary_no = d.diary_no
            LEFT JOIN heardt h ON h.diary_no = m.diary_no
            LEFT JOIN master.roster r ON r.id = h.roster_id AND r.display = 'Y'
            LEFT JOIN master.casetype c ON c.casecode = m.active_casetype_id
            WHERE d.cl_date = '$list_dt' 
            AND d.display = 'Y'   
            AND d.roster_id = '$roster_id'  
            AND d.mf = '$mainhead'
            ORDER BY d.clno
        ";


    // pr($sql);

    $res = $db->query($sql);
    if (count($res->getResultArray())) {
    ?>
        <div style="text-align: center;">
            <table border="1" style="font-size:12px; text-align: center; background: #ffffff;" cellspacing=0>
                <tr>
                    <td style="text-align:left" colspan="6"><U>DROP NOTE</U>:-</td>
                </tr>
                <tr>
                    <td style="text-align:left">Item No.</td>
                    <td style="text-align:left">Case No.</td>
                    <td style="text-align:left">Petitioner/Respondent</td>
                    <td style="text-align:left">Advocate</td>
                    <td style="text-align:left">Shifted to</td>
                    <td style="text-align:left">Reason</td>
                </tr>
                <?php foreach ($res->getResultArray() as $row) { ?>
                    <tr>
                        <td style="text-align:left">
                            <?php echo $row['clno'] ?>
                        </td>
                        <td style="text-align:left">
                            <?php echo $row['case_no'] ?>
                        </td>
                        <td style="text-align:left">
                            <?php echo $row['pname'];
                            if ($row['rname'] != "") {
                                echo "<br>Vs.<br/>" . $row['rname'];
                            }
                            ?>
                        </td>
                        <td style="text-align:left">
                            <?php
                            $padvname = "";
                            $radvname = "";

                            $advsql = "SELECT  
                                    STRING_AGG(a.name || CASE WHEN pet_res = 'R' THEN grp_adv END, ',' ORDER BY adv_type DESC, pet_res_no ASC) AS r_n,
                                        STRING_AGG(a.name || CASE WHEN pet_res = 'P' THEN grp_adv END, ',' ORDER BY adv_type DESC, pet_res_no ASC) AS p_n 
                                    FROM (
                                        SELECT a.diary_no, b.name, 
                                            STRING_AGG(a.adv::text, ',' ORDER BY pet_res ASC, adv_type DESC, pet_res_no ASC) AS grp_adv, 
                                            a.pet_res, a.adv_type, pet_res_no
                                        FROM advocate a 
                                        LEFT JOIN master.bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y' 
                                        WHERE a.diary_no = '" . $row["diary_no"] . "' AND a.display = 'Y' 
                                        GROUP BY a.diary_no, b.name, a.pet_res, a.adv_type, a.pet_res_no
                                        ORDER BY pet_res ASC, adv_type DESC, pet_res_no ASC
                                    ) a 
                                    GROUP BY diary_no, a.name, a.grp_adv, a.pet_res, a.adv_type, a.pet_res_no
                                ";

                            // $advsql = "SELECT a.*, GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'R' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) r_n,
                            // GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'P' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) p_n FROM 
                            // (SELECT a.diary_no, b.name, 
                            // GROUP_CONCAT(IFNULL(a.adv,'') ORDER BY pet_res ASC, adv_type DESC, pet_res_no ASC) grp_adv, 
                            // a.pet_res, a.adv_type, pet_res_no
                            // FROM advocate a LEFT JOIN bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y' WHERE a.diary_no='" . $row["diary_no"] . "' AND a.display = 'Y' GROUP BY a.diary_no, b.name
                            // ORDER BY pet_res ASC, adv_type DESC, pet_res_no ASC) a GROUP BY diary_no";
                            
                            // pr($advsql);

                            $resultsadv = $db->query($advsql);
                            if ($resultsadv->getNumRows() > 0) {
                                $rowadv = $resultsadv->getRowArray();
                                // if($jcd_rp !== "117,210" AND $jcd_rp != "117,198"){
                                $radvname =  $rowadv["r_n"];
                                $padvname =  $rowadv["p_n"];
                                // }
                            }
                            echo strtoupper(str_replace(",", ", ", trim($padvname, ","))) . "<br/><br/>" . strtoupper(str_replace(",", ", ", trim($radvname, ",")));
                            ?>
                        </td>
                        <td style="text-align:left">
                            <?php
                            if ($row['p_r_id'] == 0) {
                                echo "-";
                            } else {
                                if ($row['p_r_id'] == $row['roster_id']) {
                                    echo "Item No. " . $row['p_brd_slno'];
                                } else {
                                    $sqq = "select courtno from roster where id = '" . $row['p_r_id'] . "' and display = 'Y'";
                                    $rs_sqq = $db->query($sqq);
                                    $rowsqq = $rs_sqq->getResultArray();
                                    if ($row['c_status'] == 'D') {
                                        $dispose_flag = " Disposed";
                                    } else {
                                        $dispose_flag = " ";
                                    }
                                    echo "Court No. " . $rowsqq['courtno'] . " as Item No. " . $row['p_brd_slno'] . " " . $dispose_flag . " On " . date('d-m-Y', strtotime($row['p_next_dt']));
                                }
                            }
                            ?>
                        </td>
                        <td style="text-align:left">
                            <?php echo $row['nrs'] ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
<?php
    }
}

function get_cl_brd_remark($diary_no)
{
    $db = \Config\Database::connect();
    $builder = $db->table('brdrem')
                  ->select('remark')
                  ->where('diary_no', $diary_no)
                  ->get();
    
    $row = $builder->getRowArray();
    return $row['remark'] ?? '';
}

function get_cl_brd_remarkV1($diary_no)
{
    $db = \Config\Database::connect();
    $builder = $db->table('brdrem h');
    $builder->select('remark');
    $builder->where('diary_no', $diary_no);
    $query = $builder->get();
    //echo $this->db->getLastQuery(); // This will output the query
    $result = $query->getRowArray();
    if ($result) {
        return $result['remark'];
    } else {
        return null;
    }
}


function f_cl_is_printed($q_next_dt, $partno, $mainhead, $roster_id)
{
    $db = \Config\Database::connect();
    $result = 0;
    $sql = "select * from cl_printed where next_dt = '$q_next_dt' AND part = '$partno' AND m_f = '$mainhead' AND roster_id IN ($roster_id) AND display='Y'";
    $q_rs = $db->query($sql);
    if (count($q_rs->getResultArray()) > 0) {
        $result = 1;
    }
    return $result;
    
}

// function get_cl_print_benches_from_roster_new($mainhead, $cldt, $board_type)
// {
//     $db = \Config\Database::connect();
//     $m_f = null;
//     if ($mainhead === 'M') {
//         $m_f = '1';
//     } elseif ($mainhead === 'F') {
//         $m_f = '2';
//     }
//     $from_to_dt = '';
//     if ($board_type === 'R') {
//         $from_to_dt = "r.to_date = '0000-00-00'";
//     } else {
//         $from_to_dt = "r.from_date = '$cldt'";
//     }
//     $board_type_in = '';
//     if ($board_type === 'C') {
//         $board_type_in = "mb.board_type_mb IN ('C', 'CC')";
//     } else {
//         $board_type_in = "mb.board_type_mb IN ('$board_type')";
//     }
//     $builder = $db->table('master.roster r');
//     $builder->select('r.id, 
//         STRING_AGG(j.jcode::text, \',\' ORDER BY j.judge_seniority) AS jcd, 
//         STRING_AGG(CONCAT(j.first_name, \' \', j.sur_name), \',\' ORDER BY j.judge_seniority) AS jnm, 
//         rb.bench_no, 
//         mb.abbr, 
//         r.tot_cases, 
//         r.courtno, 
//         mb.board_type_mb')
//         ->join('master.roster_bench rb', 'rb.id = r.bench_id', 'left')
//         ->join('master.master_bench mb', 'mb.id = rb.bench_id', 'left')
//         ->join('master.roster_judge rj', 'rj.roster_id = r.id', 'left')
//         ->join('master.judge j', 'j.jcode = rj.judge_id', 'left')
//         ->where('j.is_retired !=', 'Y')
//         ->where('j.display', 'Y')
//         ->where('rj.display', 'Y')
//         ->where('rb.display', 'Y')
//         ->where('mb.display', 'Y')
//         ->where('r.display', 'Y')
//         ->where('r.m_f', $m_f);
//     $builder->where($board_type_in);
//     if (!empty($from_to_dt)) {
//         $builder->where($from_to_dt);
//     }
//     $builder->groupBy('r.id, rb.bench_no, mb.abbr, r.tot_cases, r.courtno, mb.board_type_mb')
//         ->orderBy('r.courtno')
//         ->orderBy('r.id');
//         // ->orderBy('j.judge_seniority');
//     $query = $builder->get();
//     $options = [];
//     if (count($query->getResultArray()) > 0) {
//         $options[] = '<option value="0" selected>SELECT</option>';
//         foreach ($query->getResultArray() as $row) {
//             $options[] = '<option value="' . $row["jcd"] . '|' . $row["id"] . '">' . $row['jnm'] . '</option>';
//         }
//     } else {
//         $options[] = '<option value="0" selected>EMPTY</option>';
//     }
//     echo implode("\n", $options);
// }

function get_cl_print_mainhead($mainhead, $board_type)
{
    $db = \Config\Database::connect();
    $m_f = null;
    if ($mainhead === 'M') {
        $m_f = '1';
    } elseif ($mainhead === 'F') {
        $m_f = '2';
    }
    $board_type_in = '';
    if ($board_type !== '0') {
        $board_type_in = "c.board_type = '$board_type'";
    }
    $builder = $db->table('heardt c');
    $builder->select('c.next_dt')
        ->where('c.mainhead', $mainhead)
        ->where('c.next_dt >=', date('Y-m-d'))
        ->whereIn('c.main_supp_flag', ['1', '2']);
    if (!empty($board_type_in)) {
        $builder->where($board_type_in);
    }
    $builder->groupBy('c.next_dt');
   
    $query = $builder->get();
    
    $options = [];
    if (count($query->getResultArray()) > 0) {
        $options[] = '<option value="0" selected>SELECT</option>';
        foreach ($query->getResultArray() as $row) {
            $options[] = '<option value="' . $row['next_dt'] . '">' . date("d-m-Y", strtotime($row['next_dt'])) . '</option>';
        }
    } else {
        $options[] = '<option value="0" selected>EMPTY</option>';
    }
    echo implode("\n", $options);
}


/* end function case_nos Anshu */

function convertToYmd($date)
{
    //$myDate = DateTime::createFromFormat('d-m-Y', $date);
    //return $myDate->format('Y-m-d');
    return date("Y-m-d", strtotime($date));
}

function convertTodmY($date)
{
    return date("d-m-Y", strtotime($date));
    return date("d-m-Y", strtotime($date));
}



function judge()
{

    $rs = is_data_from_table('master.judge',  " jtype = 'J' and display = 'Y' and is_retired = 'N' order by judge_seniority ", ' jcode, jname ', $row = 'A');
    $option = '';
    if (!empty($rs)) {
        foreach ($rs as $row) {
            $option .= '<option value="' . $row["jcode"] . '">' . $row["jname"] . '</option>';
        }
    }
    return $option;
}

function subheading()
{

    $rs = is_data_from_table('master.subheading',  " listtype='M' and display='Y' order by priority, stagename ", '*', $row = 'A');
    $option = '';
    if (!empty($rs)) {
        foreach ($rs as $row) {
            $option .= '<option value="' . $row["stagecode"] . '">' . str_replace("]", "", str_replace("[", "", $row["stagename"])) . '</option>';
        }
    }
    return $option;
}


function listing_purpose()
{

    $rs = is_data_from_table('master.listing_purpose',  " display='Y' and code != 99 order by priority ", 'code, purpose', $row = 'A');
    $option = '';
    if (!empty($rs)) {
        foreach ($rs as $row) {
            $option .= '<option value="' . $row["code"] . '">' . $row["purpose"] . '</option>';
        }
    }
    return $option;
}


function category()
{
    /* GLOBAL $dbo_icmis_read;
    $sql="SELECT * FROM submaster WHERE display='Y' and flag = 's' and old_sc_c_kk != 0 and subcode2 = 0 and subcode3 = 0 and subcode4 = 0 ORDER BY subcode1";
    $rs = $dbo_icmis_read->prepare($sql);
    $rs->execute(); */
    //echo $sql="SELECT * FROM submaster WHERE display='Y' and flag = 's' and old_sc_c_kk != 0 and subcode2 = 0 and subcode3 = 0 and subcode4 = 0 ORDER BY subcode1";
    //die;
    $rs = is_data_from_table('master.submaster',  " display='Y' and flag = 'S' and old_sc_c_kk != 0 and (subcode2 IS NULL OR subcode2 = '0') and subcode3 = 0 and subcode4 = 0 ORDER BY subcode1 ", '*', 'A');
    $option = '';

    if (!empty($rs)) {
        foreach ($rs as $row) {
            /*  $sql2="SELECT * FROM submaster WHERE subcode1 = ".$row["subcode1"]." and display='Y' and flag = 's' and old_sc_c_kk != 0 and subcode2 != 0 ORDER BY subcode2";
            $rs2 = $dbo_icmis_read->prepare($sql2);
            $rs2->execute(); */

            $subcode1 = $row["subcode1"];
            $rs2 = is_data_from_table('master.submaster',  " subcode1 = $subcode1 AND display='Y' and flag = 'S' and old_sc_c_kk != 0 and (subcode2 IS NULL OR subcode2 = '0' ) ORDER BY subcode2 ", '*', 'A');

            if (!empty($rs2)) {
                $option .= "<optgroup label='" . $row["old_sc_c_kk"] . " - " . $row["sub_name4"] . "' >";
                foreach ($rs2 as $row2) {
                    $value =   (strlen($row2["sub_name4"]) > 40) ? substr($row2["sub_name4"], 0, 40) . "..." : $row2["sub_name4"];
                    $option .= '<option value="' . $row2["id"] . '">' . $row2["old_sc_c_kk"] . " - " . $value . '</option>';
                }
                $option .= "</optgroup>";
            } else {
                $option .= "<option value='" . $row["id"] . "'  >" . $row["old_sc_c_kk"] . " - " . $row["sub_name4"] . "</option>";
            }
        }
    }
    return $option;
}


function casetype()
{

    $rs = is_data_from_table('master.casetype',  " display='Y' ORDER BY nature, skey ", 'casecode,short_description,nature,casename', $row = 'A');
    $option = '';
    if (!empty($rs)) {
        foreach ($rs as $row) {
            $option .= '<option value="' . $row["casecode"] . '">' . str_replace("No.", "", $row["short_description"]) . '</option>';
        }
    }
    return $option;
}


function judicial_section()
{
    /*  GLOBAL $dbo_icmis_read;
    $sql="select id, section_name from usersection where isda = 'Y' and display = 'Y' order by section_name";
    $rs = $dbo_icmis_read->prepare($sql);
    $rs->execute(); */
    $rs = is_data_from_table('master.usersection',  " isda = 'Y' and display = 'Y' order by section_name ", ' id, section_name', $row = 'A');
    $option = '';
    if (!empty($rs)) {
        foreach ($rs as $row) {
            $option .= '<option value="' . $row["id"] . '">' . $row["section_name"] . '</option>';
        }
    }
    return $option;
}


function da()
{
    /*  GLOBAL $dbo_icmis_read;
    $sql="select usercode, name, empid from users u inner join usersection us on us.id = u.section where us.isda = 'Y' and us.display = 'Y' and u.display = 'Y' order by name";
    $rs = $dbo_icmis_read->prepare($sql);
    $rs->execute(); */
    //$rs = is_data_from_table('master.users u inner join usersection us on us.id = u.section ',  " us.isda = 'Y' and us.display = 'Y' and u.display = 'Y' order by name ", ' usercode, name, empid ', $row = 'A');
    $db = \Config\Database::connect();
    $builder = $db->table('master.users u');
    $builder->join('master.usersection us', 'us.id = u.section');
    $builder->select('u.usercode, u.name, u.empid');
    $builder->where('us.isda', 'Y');
    $builder->where('us.display', 'Y');
    $builder->where('u.display', 'Y');
    $builder->orderBy('u.name');

    $query = $builder->get();

    $rs =  $query->getResultArray();

    $option = '';
    if (!empty($rs)) {
        foreach ($rs as $row) {
            $option .= '<option value="' . $row["usercode"] . '">' . $row["name"] . ' - ' . $row["empid"] . '</option>';
        }
    }
    return $option;
}



    function next_holidays(){
        $db = \Config\Database::connect();
        /*$sql="select working_date as  holidays from master.sc_working_days where working_date >= CURRENT_DATE and display = 'Y' and is_holiday = 1";
        $query = $db->query($sql);
        $res = $query->getResultArray();*/

        $builder = $db->table('master.sc_working_days');
        $builder->select("TO_CHAR(working_date, 'FMDD-FMMM-YYYY') AS holidays");
        $builder->where('working_date >=', date('Y-m-d')); // CURRENT_DATE equivalent
        $builder->where('display', 'Y');
        $builder->where('is_holiday', 1);

        // Execute the query and get the result
        $query = $builder->get();
        $result = $query->getResultArray();
        
        $str = '';
        foreach($result as $row){
            $str .= '"'.$row['holidays'].'",';
        };
        return rtrim($str, ',');
    }

    function next_holidays_new(){
        $db = \Config\Database::connect();

        $builder = $db->table('master.sc_working_days');
        $builder->select("TO_CHAR(working_date, 'FMDD-FMMM-YYYY') AS holidays");
        $builder->where('working_date >=', date('Y-m-d')); // CURRENT_DATE equivalent
        $builder->where('display', 'Y');
        $builder->where('is_holiday', 1);

        // Execute the query and get the result
        $query = $builder->get();
        $result = $query->getResultArray();
        
        $arr = [];
        foreach($result as $row){
            $arr[] = $row['holidays'];
        };

        return json_encode($arr);
    }
    
    function navigate_diary($dno)
    {
        // Get the database connection
        $db = \Config\Database::connect();
        $builder = $db->table('main m');

        // Prepare the SQL query
        $builder->select('m.diary_no, c1.short_description, m.active_reg_year, m.active_fil_no, 
                      m.pet_name, m.res_name, pno, rno, m.diary_no_rec_date, 
                      m.active_fil_dt, m.lastorder, m.c_status')
            ->join('master.casetype c1', 'm.active_casetype_id = c1.casecode', 'left')
            ->where('m.diary_no', $dno);

        // Execute the query
        $query = $builder->get();
        $result = $query->getResultArray();

        // Process the result
        if (!empty($result)) {
            $ro = $result[0];
            $filno_array = explode("-", $ro['active_fil_no'] ?? '');
            

            if (empty($filno_array[0])) {
                $fil_no_print = "Unreg.";
            } else {
                // Initialize the output variable
                $fil_no_print = $ro['short_description'] . "/";

                // Check if we have at least 2 parts
                if (isset($filno_array[1])) {
                    $fil_no_print .= ltrim($filno_array[1], '0');
                } else {
                    $fil_no_print .= "N/A"; // or handle as needed
                }

                // Check if we have a third part
                if (isset($filno_array[2]) && $filno_array[1] != $filno_array[2]) {
                    $fil_no_print .= "-" . ltrim($filno_array[2], '0');
                }

                $fil_no_print .= "/" . $ro['active_reg_year'];
            }

            $cstatus = ($ro['c_status'] == "P") ? "Pending" : "Disposed";

            // Set session data
            $session = session();
            $session->set([
                'session_c_status' => $cstatus,
                'session_pet_name' => $ro['pet_name'],
                'session_res_name' => $ro['res_name'],
                'session_lastorder' => $ro['lastorder'],
                'session_diary_recv_dt' => !empty($ro['diary_no_rec_date']) ? date('d-m-Y H:i:s', strtotime($ro['diary_no_rec_date'])) : '',
                'session_active_fil_dt' => !empty($ro['active_fil_dt']) ? date('d-m-Y H:i:s', strtotime($ro['active_fil_dt'])) : '',
                'session_diary_no' => substr($dno, 0, -4),
                'session_diary_yr' => substr($dno, -4),
                'session_active_reg_no' => $fil_no_print
            ]);
        }
    }

    function get_diary_case_type_notice($ct, $cn, $cy)
    {
        $db = \Config\Database::connect();
        if ($ct != '') {
            // First Query
            $builder = $db->table('main');
            $builder->select("SUBSTR(diary_no, 1, LENGTH(diary_no) - 4) as dn, 
                          SUBSTR(diary_no, -4) as dy");
            $builder->where("SUBSTRING_INDEX(fil_no, '-', 1)", $ct);
            $builder->where("CAST($cn AS UNSIGNED) BETWEEN 
                          (SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no, '-', 2), '-', -1)) 
                          AND 
                          (SUBSTRING_INDEX(fil_no, '-', -1))");

            $builder->groupStart()
                ->where("reg_year_mh", 0)
                ->orWhere("DATE(fil_dt) >", '2017-05-10')
                ->groupEnd()
                ->where("YEAR(fil_dt)", $cy);

            $query = $builder->get();

            if ($query->getNumRows() > 0) {
                $result = $query->getRowArray();
                return $result['dn'] . $result['dy'];
            }

            // Second Query
            $builder = $db->table('main_casetype_history');
            $builder->select("SUBSTR(h.diary_no, 1, LENGTH(h.diary_no) - 4) AS dn, SUBSTR(h.diary_no, -4) AS dy, IF(h.new_registration_number != '', SUBSTRING_INDEX(h.new_registration_number, '-', 1), '') as ct1, IF(h.new_registration_number != '', SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2), '-', -1), '') as crf1, IF(h.new_registration_number != '', SUBSTRING_INDEX(h.new_registration_number, '-', -1), '') as crl1");
            $builder->groupStart()
                ->where("SUBSTRING_INDEX(h.new_registration_number, '-', 1)", $ct)
                ->where("CAST($cn AS UNSIGNED) BETWEEN (SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2), '-', -1)) AND (SUBSTRING_INDEX(h.new_registration_number, '-', -1))")
                ->where("h.new_registration_year", $cy)
                ->groupEnd()
                ->orWhere(function ($q) use ($ct, $cn, $cy) {
                    $q->where("SUBSTRING_INDEX(h.old_registration_number, '-', 1)", $ct)
                        ->where("CAST($cn AS UNSIGNED) BETWEEN 
                               (SUBSTRING_INDEX(SUBSTRING_INDEX(h.old_registration_number, '-', 2), '-', -1)) 
                               AND 
                               (SUBSTRING_INDEX(h.old_registration_number, '-', -1))")
                        ->where("h.old_registration_year", $cy);
                })
                ->where("h.is_deleted", 'f');

            $query = $builder->get();

            if ($query->getNumRows() > 0) {
                $result = $query->getRowArray();
                return $result['dn'] . $result['dy'];
            }
        }

        return null;
    }

    function get_next_working_date_new($dt, $head_no, $mf)
    {

        if ($head_no != 24 && $head_no != 180) {
            $start = strtotime($dt);
            $cdate1 = null; // Initialize to null
            $maxDays = 15; // Total number of days to check
            $db = \Config\Database::connect();
            for ($ivar = 0; $ivar < $maxDays; $ivar++) {
                $currentDate = date('Y-m-d', strtotime("+$ivar day", $start));
                $subQuery = "SELECT '$currentDate' AS cdates";
                $builder = $db->table("($subQuery) AS t1");
                $builder->select("t1.cdates,
                              EXTRACT(DOW FROM t1.cdates) AS wd,
                              EXTRACT(WEEK FROM t1.cdates) - EXTRACT(WEEK FROM '$dt') + 1 AS wk");
                $builder->join('holidays t2', 't1.cdates = t2.hdate', 'left');
                $builder->where("EXTRACT(DOW FROM t1.cdates) NOT IN (5, 6)"); // Skip Saturday and Sunday
                $builder->where('t2.hdate IS NULL');
                $result = $builder->get()->getRowArray();
                if ($result) {
                    if ($mf == 'F' && $result['wd'] == 0) {
                        $cdate1 = $result['cdates'];
                        break; // Found a valid date, exit the loop
                    } elseif ($result['wd'] == 0) {
                        $cdate1 = $result['cdates'];
                        break; // Found a valid date, exit the loop
                    }
                }
            }
        } else {
            $cdate1 = $dt;
        }
        return date('Y-m-d', strtotime($cdate1));
    }

    function getAdvisors($dairy_no)
    {
        $db = \Config\Database::connect();
        $subQuery = $db->table('main a')
            ->select("pet_name, res_name, pet_adv_id, res_adv_id, c_status, bench, lastorder, 
                  name, 
                  COALESCE(NULLIF(active_casetype_id, 0), casetype_id) AS casetype_id")
            ->join('master.bar b', 'b.bar_id = a.pet_adv_id', 'left')
            ->where('diary_no', $dairy_no)
            ->getCompiledSelect();

        $builder = $db->table("($subQuery) AS aa");
        $builder->select('aa.*, c.name AS res_adv_nm')
            ->join('master.bar c', 'c.bar_id = aa.res_adv_id', 'left');

        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        }

        return [];
    }

    function getTalDelData($dairy_no, $date)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tw_tal_del');
        $builder->select('COUNT(id) as count')
            ->where('diary_no', $dairy_no)
            ->where('rec_dt', $date)
            ->where('display', 'Y');
        $countResult = $builder->get()->getRowArray();
        if ($countResult['count'] > 0) {
            $builder->select('fixed_for, sub_tal, individual_multiple')
                ->where('diary_no', $dairy_no)
                ->where('rec_dt', $date)
                ->where('display', 'Y')
                ->where('print', 0)
                ->limit(1);
            $subResult = $builder->get()->getRowArray();
            return $subResult;
        }
        return null;
    }

    function get_max_dt($dm_fno, $var_st)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('case_remarks_multiple dcrm');

        // Subquery for latest date and diary number
        $latestSubquery = '(SELECT MAX(cl_date) AS cl, diary_no
                    FROM case_remarks_multiple
                    WHERE diary_no = ' . $db->escape($dm_fno) . '
                    GROUP BY diary_no) AS latest';

        $builder->select('dcrm.diary_no, dcrm.cl_date, dcrm.jcodes, 
                    STRING_AGG(dcrm1.r_head::text, \', \') AS r_head')
            ->join($latestSubquery, 'dcrm.diary_no = latest.diary_no AND dcrm.cl_date = latest.cl', 'INNER')
            ->join('case_remarks_multiple dcrm1', 'dcrm.diary_no = dcrm1.diary_no AND dcrm.cl_date = dcrm1.cl_date', 'INNER')
            ->groupBy('dcrm.diary_no, dcrm.cl_date, dcrm.jcodes');

        // Execute the query
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $rs_sq = $query->getRowArray();
            $rs_sq_s = explode(',', $rs_sq['r_head']);
            $ck_jcodes = '0';
            if (count($rs_sq_s) > 0) {
                $ck_jcodes = 1;
            }
            if ($ck_jcodes == 1) {
                return $rs_sq['cl_date'];
            } else {
                $var_st = "AND cl_date < '" . $rs_sq['cl_date'] . "'";
                return get_max_dt($dm_fno, $var_st);
            }
        }

        return null; // Return null if no records found
    }

    function getRemarkData($dairy_no, $ret_res, $row)
    {
        $db = \Config\Database::connect();
        $ex = !empty($row['lastorder']) ? explode("Ord dt:", $row['lastorder']) : date('d-m-Y');
        $dmy = is_array($ex) ? explode('-', $ex[1]) : explode('-', $ex);
        $Y = $dmy[2];
        $m = $dmy[1];
        $d = $dmy[0];
        $or_dt = date($Y . '-' . $m . '-' . $d);
        $get_sel_con = 0;
        $fx_dt = 0;
        $chk_remark = '';
        $r_heads = [
            '90',
            '91',
            '9',
            '10',
            '117',
            '62',
            '11',
            '60',
            '74',
            '75',
            '65',
            '2',
            '1',
            '94',
            '3',
            '4',
            '96',
            '57',
            '93',
            '59',
            '24',
            '21',
            '23',
            '8',
            '12',
            '20',
            '53',
            '54',
            '68',
            '131',
            '149',
            '113',
            '181'
        ];
        $builder = $db->table('case_remarks_multiple');
        $builder->select('COUNT(diary_no) AS ct_fn, STRING_AGG(r_head::text, \',\') AS r_head')
            ->where('diary_no', $dairy_no)
            ->whereIn('r_head', $r_heads)
            ->where('cl_date', $ret_res);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $res_sql_bnnn = $query->getRowArray();
            $res_sql_r_head = $res_sql_bnnn['r_head'];
            if (!empty($res_sql_r_head)) {
                $ex_res_sql_r_head = explode(',', $res_sql_r_head);
                foreach ($ex_res_sql_r_head as $value) {
                    if (in_array($value, ['24', '21', '59', '91', '23', '8', '12', '20', '53', '54', '68', '131', '113'])) {
                        $fx_dt = 1;
                        $chk_remark = $value;
                        $get_sel_con = 1;
                        break; // Exit loop on first match
                    }
                }
            } else {
                $fx_dt = 1;
                $chk_remark = '';
                $get_sel_con = 1;
            }
        }
        return [
            'get_sel_con' => $get_sel_con,
            'fx_dt' => $fx_dt,
            'chk_remark' => $chk_remark,
            'or_dt' => $or_dt
        ];
    }

    function chksDate($dt)
    {
        $db = \Config\Database::connect();
        $sql_we = $db->table('master.holidays')
            ->selectCount('hdate')
            ->where('hdate', $dt)
            ->get();
        $res_h = $sql_we->getRow()->hdate ?? 0; // Get the count or default to 0
        if ($res_h > 0) {
            $dt = date('Y-m-d', strtotime($dt . ' + 1 days'));
            return chksDate($dt);
        } else {
            return $dt;
        }
    }

    function getNotice($str, $res_section, $n_status, $casetype_id)
    {
        $nt = null;
        $db = \Config\Database::connect();
        if ($str == 'C' || $str == 'W') {
            $nt = 'Y';
        } else if ($str == 'R') {
            $nt = 'Z';
        }
        $ma_val = '';
        if ($casetype_id != '39') {
            $ma_val = " (nature = '$str' OR nature = '' OR nature = '$nt')";
        }
        $sql_not = $db->table('master.tw_notice')
            ->select('id, name')
            ->where('display', 'Y')
            ->where("notice_status = '$n_status' OR notice_status = ''");
        if (!empty($ma_val)) {
            $sql_not->where($ma_val, null, false);
        }
        if (!empty($res_section)) {
            $sql_not->where("section = '$res_section' OR section = '0'");
        }
        $sql_not->orderBy('name');
        $query = $sql_not->get();
        $notice = [];
        foreach ($query->getResultArray() as $row) {
            $notice[] = $row['id'] . '^' . $row['name'];
        }
        return $notice;
    }

    function send_to()
    {
        $db = \Config\Database::connect();
        $query = $db->table('master.tw_send_to')
            ->select('id, desg')
            ->where('display', 'Y')
            ->get();

        // Prepare the results
        $send_too = [];
        foreach ($query->getResultArray() as $row) {
            $send_too[] = $row['id'] . '^' . $row['desg'];
        }

        return $send_too;
    }

    function getState()
    {
        $db = \Config\Database::connect();
        $query = $db->table('master.state')
            ->select('id_no AS state_code, name')
            ->where('district_code', '0')
            ->where('sub_dist_code', '0')
            ->where('village_code', '0')
            ->orderBy('name')
            ->get();

        // Prepare the results
        $state = [];
        foreach ($query->getResultArray() as $row) {
            $state[] = $row['state_code'] . "^" . $row['name'];
        }

        return $state;
    }

    function getParties($diary_no, $date)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('party')
            ->select("NULL AS id, 
                partyname, 
                addr1, 
                addr2, 
                CAST(sr_no_show AS BIGINT) AS sr_no, 
                pet_res, 
                sonof, 
                prfhname, 
                NULL AS nt_type, 
                NULL AS amount, 
                state, 
                city, 
                NULL AS enrol_no, 
                NULL AS enrol_yr
            ")
            ->where('diary_no', '722022')
            ->where('pflag', 'P')
            ->where('partyname IS NOT NULL')
            ->where("partyname != ''")
            ->getCompiledSelect();

        // Subquery for tw_tal_del table
        $builder2 = $db->table('tw_tal_del')
            ->select("CAST(id AS BIGINT) AS id, 
                name AS partyname, 
                address AS addr1, 
                NULL AS addr2, 
                CAST(sr_no AS BIGINT) AS sr_no, 
                pet_res, 
                NULL AS sonof, 
                NULL AS prfhname, 
                nt_type, 
                amount, 
                tal_state::CHAR AS state, 
                tal_district::CHAR AS city, 
                enrol_no, 
                enrol_yr
            ")
            ->where('diary_no', '722022')
            ->where('rec_dt', '2025-03-28')
            ->where('display', 'Y')
            ->where('sr_no', '0')
            ->where('print', 0)
            ->getCompiledSelect();

        // Combining queries with UNION
        $query = $db->query("SELECT * FROM ($builder UNION ALL $builder2) AS p 
            ORDER BY 
                CASE 
                    WHEN sr_no = 1 THEN -1 
                    WHEN sr_no > 1 AND pet_res = 'P' THEN 0 
                    WHEN sr_no > 1 AND pet_res = 'R' THEN 1 
                    WHEN sr_no = 0 THEN 2 
                    ELSE sr_no 
                END,  
                CAST(split_part(sr_no::TEXT, '.', 1) AS INTEGER), 
                COALESCE(CAST(NULLIF(split_part(sr_no::TEXT, '.', 2), '') AS INTEGER), 0), 
                COALESCE(CAST(NULLIF(split_part(sr_no::TEXT, '.', 3), '') AS INTEGER), 0), 
                COALESCE(CAST(NULLIF(split_part(sr_no::TEXT, '.', 4), '') AS INTEGER), 0)
        ");

        $result = $query->getResultArray(); // Fetch the results

        // $result = $db->query($finalQuery)->getResultArray();

        return $result;
    }

    function getCityById($state)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('master.state');
        $subQuery = $db->table('master.state')
            ->select('state_code')
            ->where('id_no', $state)
            ->where('display', 'Y')
            ->getCompiledSelect();

        $query_city = $builder->select('id_no AS district_code, name')
            ->where('state_code IN (' . $subQuery . ')')
            ->where('sub_dist_code', '0')
            ->where('district_code !=', '0')
            ->where('village_code', '0')
            ->orderBy('name')
            ->get()
            ->getResultArray();

        return $query_city;

        if ($query_city === false) {
            die("Error: " . $db->error());
        }
    }

    function get_lower_court_judge($post_code)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('Post_t');
        $result = $builder->select('Post_name')
            ->where('Post_code', $post_code)
            ->where('display', 'Y')
            ->get()
            ->getRow();

        return $result ? $result->Post_name : null;
    }

    function get_conn_cases($dn)
    {
        $me2 = [];

        if ($dn != "") {
            $db = \Config\Database::connect();

            // Query to get the conn_key
            // $connKeyQuery = $db->table('main')->select('conn_key')->where('diary_no', $dn)->get();
            $connKeyQuery = is_data_from_table('main',"diary_no = $dn","conn_key",'');
            $connKey = $connKeyQuery['conn_key'];
        //   pr($connKey['conn_key']);
            if (!empty($connKey)) {
               
                // Prepare the main query based on the conn_key
                if ($connKey == $dn) {
                   
                    $sql = "SELECT m.diary_no, 
                               CASE 
                                   WHEN m.conn_key::text = m.diary_no::text THEN 'M' 
                                   ELSE c.conn_type 
                               END AS c_type 
                        FROM main m
                        LEFT JOIN conct c ON m.conn_key = c.conn_key::text
                        WHERE m.conn_key = ?";
                    $query = $db->query($sql, [$connKey]);
                } else {
                    $sql = "SELECT diary_no, 
                               CASE 
                                   WHEN conn_key = diary_no THEN 'M' 
                                   ELSE conn_type 
                               END AS c_type 
                        FROM conct 
                        WHERE conn_key = ?";
                    $query = $db->query($sql, [$connKey]);
                }

                // Fetch results
                foreach ($query->getResultArray() as $row) {
                    $me2[$row['diary_no']]['diary_no'] = $row['diary_no'];
                    $me2[$row['diary_no']]['c_type'] = $row['c_type'];
                }
            }
        }

        return $me2;
    }

    //..........New added 28-10-2024.............//

    function get_diary_set_fm($dn, $module, $cn_type)
    {
        $result_html = '';
        $db = \Config\Database::connect();
        $diary_no = substr($dn, 0, -4) . "/" . substr($dn, -4);
        $ucode = session()->get('login')['usercode'];
        $rs_set = $db->query("SELECT * FROM diary_copy_set WHERE diary_no = '" . $dn . "' ORDER BY copy_set")->getResultArray();

        $result_html .= "<br>";
        $result_html .= "<table align=center width='100%' cellpadding='1' cellspacing='1'class='table_tr_th_w_clr c_vertical_align'>
    <tr>
        <td width='15%'><input type='checkbox' id='ckbCheckSetA' onclick='return OptionsSelected(this)'/> Check-A <br><input type='checkbox' id='ckbCheckSetBCD' onclick='return OptionsSelected(this)'/> Check-BCD <br><input type='checkbox' id='ckbCheckAll' onclick='return OptionsSelected(this)'/> Check-All </td>
        <td align='center'>S.No.</td>
        <td align='center'>Diary No.</td>
        <td align='center'>Set</td>
        <td align='center' width='45%'>Location</td>
        <td align='center' width='40%'>Remarks</td>
    </tr>";
        $sno = 0;

        foreach ($rs_set as $key => $row_set) {
            $current_q = $db->query("SELECT * FROM diary_movement WHERE diary_copy_set='" . $row_set['id'] . "'");
            $row = $current_q->getRowArray();

            if ($current_q->getNumRows() == 0) {
                $location = "<div style='color: red;'>Case is not in File Movement</div>";
                $masterhead = 1;
            } else {
                $location = "";
                if ($module == 'receive') {
                    if ($row['rece_by'] == $ucode) {
                        $location = "<span style='color:red;'> File is already Received by you. </span>";
                        $masterhead = 2;
                    } else if ($row['disp_to'] != $ucode) {
                        $location = "<span style='color:red;'> File is not Dispatched to you. </span>";
                        $masterhead = 3;
                    } else {
                        $masterhead = 4;
                    }
                }
                if ($module == 'dispatch') {
                    if ($row['rece_by'] == 0 && $row['disp_by'] === $ucode) {
                        $location = "<span style='color:red;'> File is already Dispatched by you. </span>";
                        $masterhead = 2;
                    } else if ($row['rece_by'] != $ucode) {
                        $location = "<span style='color:red;'> File is not received by you. </span>";
                        $masterhead = 3;
                    } else {
                        $masterhead = 4;
                    }
                }
                if ($row['rece_by'] == 0) {
                    $location .= "Dispatched To :" . get_user_details($row['disp_to']) . " on " . date('d-m-Y, h:i A', strtotime($row['disp_dt']));
                } else {
                    $location .= "Received By : " . get_user_details($row['rece_by']) . " on " . date('d-m-Y, h:i A', strtotime($row['rece_dt']));
                }
            }

            if ($masterhead == 1 || $masterhead == 4) {
                $t_chk = " checked=checked ";
            } else {
                $t_chk = " disabled=disabled ";
            }

            $sno++;

            if ($module == 'dispatch') {
                $result_html .= '<tr><td align=center><input id="chk_' . $sno . '" class="chk chk_' . $sno . '" type="checkbox" name="chk[]"  value="' . $row_set['id'] . "-" . $masterhead . "-" . $cn_type . '" ' . $t_chk . '/></td><td align=center>' . $sno . '</td><td>' . $diary_no . '</td><td align=center>' . $row_set['copy_set'] . '</td><td>' . $location . '</td><td><input type="text" id="txt_Remarks_' . $sno . '" name="txt_remarks[]" class="txt_remarks" size="70" ' . $t_chk . '></td></tr>';
            } else {
                $remark = $row['remark'] ?? "";
                // pr($row);
                $result_html .= '<tr><td align=center><input id="chk_' . $sno . '" class="chk chk_' . $sno . '" type="checkbox" name="chk[]"  value="' . $row_set['id'] . "-" . $masterhead . "-" . $cn_type . '" ' . $t_chk . '/></td><td align=center>' . $sno . '</td><td>' . $diary_no . '</td><td align=center>' . $row_set['copy_set'] . '</td><td>' . $location . '</td><td align=center>' . $remark . '</td></tr>';
            }
            //echo $_POST['txt_Remarks'] ;
        }
        $result_html .= '</table>';
        return $result_html;
    }


    function helper_chksDate_vac_reg_add($dt, $chk_tot_days)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('master.holidays');
        $builder->select('COUNT(hdate) AS hdate_count');
        $builder->where('hdate', $dt);
        $builder->where('emp_hol IN (1, 2)');

        $query = $builder->get();
        $result = $query->getRowArray();

        $res_h = $result['hdate_count'];

        if ($res_h > 0) {
            if (strtotime($dt) == strtotime($chk_tot_days)) {
                return $dt;
            } else {
                $dt = date('Y-m-d', strtotime($dt . ' + 1 days'));
                return helper_chksDate_vac_reg_add($dt, $chk_tot_days);
            }
        } else {
            return $dt;
        }
    }

    function helper_chksDate_vac_reg_sub($dt, $chk_tot_days)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('master.holidays');
        $builder->select('COUNT(hdate) AS hdate_count');
        $builder->where('hdate', $dt);
        $builder->where('emp_hol IN (1, 2)');

        $query = $builder->get();
        $result = $query->getRowArray();

        $res_h = $result['hdate_count'];

        if ($res_h > 0) {
            if (strtotime($dt) == strtotime($chk_tot_days)) {
                return $dt;
            } else {
                $dt = date('Y-m-d', strtotime($dt . ' - 1 days'));
                return helper_chksDate_vac_reg_add($dt, $chk_tot_days);
            }
        } else {
            return $dt;
        }
    }



    function get_databy_rto_id($rto_id)
    {
        $table_name = 'rto';
        $file = env('Json_master_table') . $table_name . '.json';
        if (file_exists($file)) {
            $url = base_url('/' . $file);
            $json = file_get_contents($url, true);
            $json_data = json_decode($json, true);
            $json_array = false;
            if ($json_data) {
                $json_array = array_filter($json_data, function ($item) use ($rto_id) {
                    return $item['id'] == $rto_id && $item['display'] === 'Y';
                });
            }
            return array_values($json_array);
        } else {
            echo $table_name . ' table does not exist';
            exit();
        }
    }


    function getJudgeDetailsByLowerct($lower_court_id, $court_type)
    {
        $db = \Config\Database::connect();
        $builder = $db->table("lowerct_judges as lj");
        $builder->select('lj.judge_id');
        $builder->where('lj.lowerct_id', $lower_court_id);
        //echo $builder->getCompiledSelect();
        $query = $builder->get();
        $resultJudges = $query->getResultArray();

        $judgeIds = array_column($resultJudges, 'judge_id');

        if ($court_type == 4) { // supreme court
            $table_name = 'judge';
        } else {
            $table_name = 'org_lower_court_judges';
        }

        $file = env('Json_master_table') . $table_name . '.json';
        if (file_exists($file)) {
            $url = base_url('/' . $file);
            $json = file_get_contents($url, true);
            $json_data = json_decode($json, true);
            $json_array = false;

            if ($json_data) {
                if ($court_type == 4) {
                    $json_array = array_filter($json_data, function ($item) use ($judgeIds) {
                        return in_array($item['jcode'], $judgeIds);
                    });
                } else {
                    $json_array = array_filter($json_data, function ($item) use ($judgeIds) {
                        return in_array($item['id'], $judgeIds);
                    });
                }
            }
            // pr($json_array);
            return $json_array;
        } else {
            echo $table_name . ' table does not exist';
            exit();
        }
    }


    function get_police_station_name($police_station_id, $state_id, $district_id)
    {

        $table_name = 'police';
        $file = env('Json_master_table') . $table_name . '.json';
        if (file_exists($file)) {

            $url = base_url('/' . $file);
            $json = file_get_contents($url, true);
            $json_data = json_decode($json, true);
            $json_array = false;

            if ($json_data) {
                $json_array = array_filter($json_data, function ($item) use ($police_station_id, $state_id, $district_id) {
                    return $item['policestncd'] === $police_station_id && $item['cmis_state_id'] == $state_id && trim($item['cmis_district_id']) == $district_id && $item['display'] === 'Y';
                });
            }

            return $json_array;
        } else {
            echo $table_name . ' table does not exist';
            exit();
        }
    }

    function getlowercase($id)
    {
        // pr($dno);
        $db = \Config\Database::connect();

        $query = $db->table('party a');
        $query->select('sr_no_show AS no');
        $query->select("lower_court_id,lct_dec_dt,l_dist,polstncode,crimeno,crimeyear,ct_code,l_state,lct_casetype,lct_caseno,lct_caseyear");
        $query->select("CASE WHEN ct_code = 3 THEN (
                SELECT 'name' FROM master.state s
                WHERE s.id_no = l_dist AND s.display = 'Y'
            ) ELSE (
                SELECT agency_name FROM master.ref_agency_code c
                WHERE c.cmis_state_id = l_state AND c.id = l_dist AND c.is_deleted = 'f'
            ) END AS agency_name");
        $query->select("CASE WHEN ct_code = 4 THEN (
                SELECT skey FROM master.casetype ct
                WHERE ct.display = 'Y' AND ct.casecode = lct_casetype
            ) ELSE (
                SELECT type_sname FROM master.lc_hc_casetype d
                WHERE d.lccasecode = lct_casetype AND d.display = 'Y'
            ) END AS type_sname");
        $query->join('party_lowercourt b', "a.auto_generated_id = b.party_id AND b.display = 'Y'", 'LEFT');
        $query->join('lowerct l', "b.lowercase_id = l.lower_court_id AND l.lw_display = 'Y'", 'LEFT');
        $query->where('b.party_id', $id);
        $query->orderBy("CAST(split_part(sr_no_show, '.', 1) AS integer) DESC");
        $query->orderBy("CAST(split_part(split_part(CONCAT(sr_no_show, '.0'), '.', 2), '.', -1) AS integer) DESC");
        $query->orderBy("CAST(split_part(split_part(CONCAT(sr_no_show, '.0.0'), '.', 3), '.', -1) AS integer) DESC");
        $query->orderBy("CAST(split_part(split_part(CONCAT(sr_no_show, '.0.0.0'), '.', 4), '.', -1) AS integer) DESC");
        // $queryString = $query->getCompiledSelect();
        // echo $queryString;
        // exit();
        $query1 = $query->get();
        $result = $query1->getResultArray();
        $html = '';
        if (!empty($result) && is_array($result)) {
            $html .= '<ul>';
            foreach ($result as $res) {
                $html .= '<li>' . (!empty($res['type_sname']) ? $res['type_sname'] : '') . '/' . (!empty($res['lct_caseno']) ? $res['lct_caseno'] : '') . '/' . (!empty($res['lct_caseyear']) ? $res['lct_caseyear'] : '') . ' - ' . (!empty($res['agency_name']) ? $res['agency_name'] : '') . '</li>';
            }
            $html .= '</ul>';
        }
        return $html;
    }

    function f_cl_ntl_judge($q_diary_no, $judges)
    {
        //$judges = '719,218';
        //$q_diary_no = 14232011;

        $db = \Config\Database::connect();
        $builder = $db->table('master.ntl_judge');
        $builder->whereIn('org_judge_id', explode(',', rtrim($judges, ',')));
        $builder->where('display', 'Y');
        //pr($builder->getCompiledSelect());
        $query = $builder->get();
        $results = $query->getResultArray();
        //pr($results);
        $num_rows = 0;
        foreach ($results as $row) {

            $builder = $db->table('advocate');
            $builder->select('diary_no');
            $builder->whereIn('diary_no', explode(',', $q_diary_no));
            $builder->where('advocate_id', $row['org_advocate_id']);
            $builder->where('display', 'Y');
            $builder->groupBy('diary_no');
            //pr($builder->getCompiledSelect());    
            $query = $builder->get();
            $result = $query->getResultArray();
            $num_rows = !empty($result) ? count($result) : 0;

            if ($num_rows > 0) {
                $num_rows = 1;
                break;
            }
        }

        return $num_rows;
    }

    function f_cl_ntl_jud_dept($q_diary_no, $judges)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('master.ntl_judge_dept');
        $builder->whereIn('org_judge_id', explode(',', $judges));
        $builder->where('display', 'Y');
        //pr($builder->getCompiledSelect());
        $query = $builder->get();
        $results = $query->getResultArray();
        //pr($results);

        $num_rows = 0;
        foreach ($results as $row) {
            $builder = $db->table('party');
            $builder->select('diary_no');
            $builder->whereIn('diary_no', explode(',', $q_diary_no));
            $builder->where('deptcode', $row['dept_id']);
            $builder->where('pflag !=', 'T');
            $builder->groupBy('diary_no');
            //pr($builder->getCompiledSelect());
            $query = $builder->get();
            $result = $query->getRowArray();
            $num_rows = !empty($result) ? count($result) : 0;
            if ($num_rows > 0) {
                $num_rows = 1;
                break;
            }
        }

        return $num_rows;
    }

    function f_cl_not_before($q_diary_no, $judges1)
    {
        $db = \Config\Database::connect();
        $judges = explode(",", $judges1);
        //pr($q_diary_no);
        //$q_diary_no = 7133946;
        $subquery = $db->table('not_before n')
            ->select('n.j1, n.notbef, j.judge_seniority')
            ->join('master.judge j', 'j.jcode = n.j1', 'inner')
            ->where('j.is_retired !=', 'Y')
            ->whereIn('n.diary_no', explode(',', $q_diary_no))
            ->groupBy('n.j1, n.notbef, j.judge_seniority');


        $query = $db->table('(' . $subquery->getCompiledSelect() . ') a')
            //->select('STRING_AGG(j1 ORDER BY judge_seniority) AS j1, notbef')
            ->select('STRING_AGG(j1::text, \',\') AS j1, notbef')
            ->groupBy('notbef')
            ->orderBy('CASE WHEN notbef = \'N\' THEN 1 ELSE 2 END', 'ASC');
        // pr($query->getCompiledSelect());
        // Execute the query
        $results = $query->get()->getResultArray();
        //pr($results);
        $num_rows = 0;
        foreach ($results as $row) {
            $j1 = explode(",", $row['j1']);
            $result = array_intersect($judges, $j1);
            if ($row['notbef'] == 'N' and $result == true) {
                $num_rows = 1;
                break;
            } else {
                if ($row['notbef'] == 'B' and count(array_intersect($judges, $j1)) != count($j1)) {
                    $num_rows = "1";
                }
                if ($row['notbef'] == 'B' and count(array_intersect($judges, $j1)) == count($j1)) {
                    $num_rows = "0";
                    break;
                }
            }
        }
        return $num_rows;
    }

    function f_cl_same_vehicle($q_diary_no, $q_next_dt, $chk_roster_id)
    {
        $result = 0;
        $db = \Config\Database::connect();

        $subquery_a = $db->table('lowerct l')
            ->select('l.diary_no, l.vehicle_code, l.vehicle_no')
            ->where('l.diary_no', $q_diary_no)
            ->where('l.vehicle_code !=', 0)
            ->where('l.vehicle_no !=', '')
            ->getCompiledSelect();

        $subquery_f = "(SELECT b.diary_no FROM ($subquery_a) a
                    LEFT JOIN lowerct b ON a.vehicle_code = b.vehicle_code AND a.vehicle_no = b.vehicle_no AND a.diary_no != b.diary_no)";

        // Main query
        $builder = $db->table("($subquery_f) AS f")
            ->select('h.*')
            ->join('main m', 'm.diary_no = f.diary_no', 'left')
            ->join('heardt h', 'h.diary_no = m.diary_no', 'left')
            ->where('m.c_status', 'P')
            ->where('h.next_dt >=', date('Y-m-d'))
            ->groupStart()
            ->where('h.main_supp_flag', 1)
            ->orWhere('h.main_supp_flag', 2)
            ->groupEnd()
            ->where('h.board_type', 'J');

        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $row = $query->getRowArray();
            $rosid = $row['roster_id'];

            if ($chk_roster_id != $rosid) {
                $builder = $db->table('master.roster c')
                    ->select('c.*')
                    ->where('c.id', $rosid)
                    ->where('c.display', 'Y')
                    ->where('c.from_date <=', $q_next_dt)
                    ->where('c.to_date >=', $q_next_dt);

                $q_roster = $builder->get();
                if ($q_roster->getNumRows() > 0) {
                    $result = $row['diary_no'] . "|" . $row['judges'];
                }
            }
        }
        return $result;
    }

    function f_cl_same_crime($q_diary_no, $q_next_dt, $chk_roster_id)
    {
        $result = 0;
        $db = \Config\Database::connect();
        $subquery_a = $db->table('lowerct l')
            ->select('l.diary_no, l.l_state, l.l_dist, l.polstncode, l.crimeno, l.crimeyear')
            ->where('l.diary_no', $q_diary_no)
            ->where('l.l_state !=', 0)
            ->where('l.l_dist !=', 0)
            ->where('l.polstncode !=', 0)
            ->where('l.crimeno !=', '0')
            ->where('l.crimeyear !=', 0)
            ->getCompiledSelect();

        $subquery_f = "(SELECT b.diary_no 
                    FROM ($subquery_a) a
                    LEFT JOIN lowerct b ON a.l_state = b.l_state 
                    AND a.l_dist = b.l_dist 
                    AND a.polstncode = b.polstncode 
                    AND a.crimeno = b.crimeno 
                    AND a.crimeyear = b.crimeyear
                    AND a.diary_no != b.diary_no)";

        $builder = $db->table("($subquery_f) AS f")
            ->select('h.*')
            ->join('main m', 'm.diary_no = f.diary_no', 'left')
            ->join('heardt h', 'h.diary_no = m.diary_no', 'left')
            ->where('m.c_status', 'P')
            ->where('h.next_dt >=', date('Y-m-d'))
            ->groupStart()
            ->where('h.main_supp_flag', 1)
            ->orWhere('h.main_supp_flag', 2)
            ->groupEnd()
            ->where('h.board_type', 'J')
            ->groupBy('f.diary_no, h.diary_no');

        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $row = $query->getRowArray();
            $rosid = $row['roster_id'];

            // Check if roster_id is not the same as $chk_roster_id
            if ($chk_roster_id != $rosid) {
                $builder = $db->table('master.roster c')
                    ->select('c.*')
                    ->where('c.id', $rosid)
                    ->where('c.display', 'Y')
                    ->where('c.from_date <=', $q_next_dt)
                    ->where('c.to_date >=', $q_next_dt);

                $q_roster = $builder->get();
                if ($q_roster->getNumRows() > 0) {
                    $result = $row['diary_no'] . "|" . $row['judges'];
                }
            }
        }

        return $result;
    }

    function f_heardt_cl_update($q_diary_no, $q_next_dt, $q_clno, $q_brd_slno, $q_roster_id, $q_judges, $q_usercode, $q_module_id, $q_main_supp_flag, $mainhead, $cat1)
    {
        $db = \Config\Database::connect();
        $result = 0;
        if ($mainhead === 'F') {
            $builder = $db->table('master.submaster');
            $builder->select('id');
            $builder->where('display', 'Y');
            $builder->whereIn('id', explode(',', $cat1));
            $builder->limit(1);
            $submasterResult = $builder->get()->getRow();

            if ($submasterResult) {
                $builder = $db->table('heardt');
                $builder->set(['subhead' => $submasterResult->id]);
                $builder->where('diary_no', $q_diary_no);
                $builder->where('mainhead', 'F');
                $builder->where('diary_no >', 0);
                $builder->update();
            }
        }
        
        $builder = $db->table('heardt');
        $data = [
            'next_dt' => $q_next_dt,
            'clno' => $q_clno,
            'brd_slno' => $q_brd_slno,
            'roster_id' => $q_roster_id,
            'judges' => $q_judges,
            'usercode' => $q_usercode,
            'ent_dt' => date('Y-m-d H:i:s'),
            'module_id' => $q_module_id,
            'main_supp_flag' => $q_main_supp_flag,
            'tentative_cl_dt' => $q_next_dt,
        ];

        $builder->where('diary_no', $q_diary_no);
        $builder->where('diary_no >', 0);
        $afros = $builder->update($data);
        if ($afros > 0) {
            $result = 1;

            $sql_conn = $db->query("INSERT INTO last_heardt (
    diary_no, conn_key, next_dt, mainhead, subhead, clno, brd_slno, roster_id, judges, coram,
    board_type, usercode, ent_dt, module_id, mainhead_n, subhead_n, main_supp_flag, listorder, tentative_cl_dt, lastorder,
    listed_ia, sitting_judges, list_before_remark, is_nmd, no_of_time_deleted
        )
        SELECT j.*
        FROM (
            SELECT
                conc_diary_no,
                m.conn_key::bigint,
                h.next_dt,
                h.mainhead,
                h.subhead,
                h.clno,
                h.brd_slno,
                h.roster_id,
                h.judges,
                h.coram,
                h.board_type,
                h.usercode,
                h.ent_dt,
                h.module_id,
                h.mainhead_n,
                h.subhead_n,
                h.main_supp_flag,
                h.listorder,
                h.tentative_cl_dt,
                m.lastorder,
                h.listed_ia,
                h.sitting_judges,
                h.list_before_remark,
                h.is_nmd,
                h.no_of_time_deleted
            FROM (
                SELECT
                    c.diary_no AS conc_diary_no,
                    m.conn_key,
                    h.next_dt,
                    h.mainhead,
                    h.subhead,
                    h.clno,
                    h.brd_slno,
                    h.roster_id,
                    h.judges,
                    h.coram,
                    h.board_type,
                    h.usercode,
                    h.ent_dt,
                    h.module_id,
                    h.mainhead_n,
                    h.subhead_n,
                    h.main_supp_flag,
                    h.listorder,
                    h.tentative_cl_dt,
                    m.lastorder,
                    h.listed_ia,
                    h.sitting_judges,
                    h.list_before_remark,
                    h.is_nmd,
                    h.no_of_time_deleted
                FROM heardt h
                INNER JOIN main m ON m.diary_no = h.diary_no
                INNER JOIN conct c ON c.conn_key = m.conn_key::text::bigint
                WHERE c.list = 'Y'
                AND m.c_status = 'P'
                AND m.diary_no = m.conn_key::text::bigint
                AND h.diary_no = '$q_diary_no'
                AND h.roster_id > 0
            ) a
            INNER JOIN main m ON a.conc_diary_no = m.diary_no
            INNER JOIN heardt h ON a.conc_diary_no = h.diary_no
            WHERE m.c_status = 'P' AND h.next_dt IS NOT NULL
        ) j
        LEFT JOIN last_heardt l ON j.conc_diary_no = l.diary_no
            AND l.conn_key = j.conn_key
            AND l.next_dt = j.next_dt
            AND l.mainhead = j.mainhead
            AND l.board_type = j.board_type
            AND l.subhead = j.subhead
            AND l.clno = j.clno
            AND l.coram = j.coram
            AND l.judges = j.judges
            AND l.roster_id = j.roster_id
            AND l.listorder = j.listorder
            AND l.tentative_cl_dt = j.tentative_cl_dt
            AND (
                CASE
                    WHEN j.listed_ia IS NULL THEN true
                    ELSE l.listed_ia = j.listed_ia
                END
            )
            AND (
                CASE
                    WHEN j.list_before_remark IS NULL THEN true
                    ELSE l.list_before_remark = j.list_before_remark
                END
            )
            AND l.no_of_time_deleted = j.no_of_time_deleted
            AND l.is_nmd = j.is_nmd
            AND l.main_supp_flag = j.main_supp_flag
            AND (l.bench_flag = '' OR l.bench_flag IS NULL)
        WHERE l.diary_no IS NULL");

            $sql_conn = $db->query("UPDATE heardt h SET
                conn_key = x.conn_key::integer,
                next_dt = x.next_dt,
                mainhead = x.mainhead,
                subhead = x.subhead,
                clno = x.clno,
                brd_slno = x.brd_slno,
                roster_id = x.roster_id,
                judges = x.judges,
                board_type = x.board_type,
                usercode = x.usercode,
                ent_dt = x.ent_dt,
                module_id = x.module_id,
                mainhead_n = x.mainhead_n,
                subhead_n = x.subhead_n,
                main_supp_flag = x.main_supp_flag,
                listorder = x.listorder,
                tentative_cl_dt = x.tentative_cl_dt,
                sitting_judges = x.sitting_judges,
                list_before_remark = x.list_before_remark,
                listed_ia = x.listed_ia,
                is_nmd = x.is_nmd,
                no_of_time_deleted = x.no_of_time_deleted
            FROM (
                SELECT
                    a.conc_diary_no,
                    m.conn_key,
                    a.next_dt,
                    a.mainhead,
                    a.subhead,
                    a.clno,
                    a.brd_slno,
                    a.roster_id,
                    a.judges,
                    a.board_type,
                    a.usercode,
                    a.ent_dt,
                    a.module_id,
                    h.mainhead_n,
                    a.subhead_n,
                    a.main_supp_flag,
                    a.listorder,
                    a.tentative_cl_dt,
                    a.sitting_judges,
                    h.listed_ia,
                    h.list_before_remark,
                    h.is_nmd,
                    h.no_of_time_deleted
                FROM (
                    SELECT
                        c.diary_no AS conc_diary_no,
                        m.conn_key,
                        h.next_dt,
                        h.mainhead,
                        h.subhead,
                        h.clno,
                        h.brd_slno,
                        h.roster_id,
                        h.judges,
                        h.board_type,
                        h.usercode,
                        h.ent_dt,
                        h.module_id,
                        h.mainhead_n,
                        h.subhead_n,
                        h.main_supp_flag,
                        h.listorder,
                        h.tentative_cl_dt,
                        h.sitting_judges,
                        h.listed_ia,
                        h.list_before_remark,
                        h.is_nmd,
                        h.no_of_time_deleted
                    FROM heardt h
                    INNER JOIN main m ON m.diary_no = h.diary_no
                    INNER JOIN conct c ON c.conn_key = m.conn_key::bigint
                    WHERE c.list = 'Y'
                    AND m.c_status = 'P'
                    AND m.diary_no = m.conn_key::bigint
                    AND h.diary_no = '$q_diary_no'
                    AND h.roster_id > 0
                ) a
                INNER JOIN main m ON a.conc_diary_no = m.diary_no
                INNER JOIN heardt h ON a.conc_diary_no = h.diary_no
                WHERE m.c_status = 'P'
                AND h.next_dt IS NOT NULL
            ) x
            WHERE h.diary_no = x.conc_diary_no");
        }
        return $result;
    }

    function f_cl_reshuffle($listing_dt, $chk_jud_id, $mf, $partno, $chk_rs_id)
    {

        $result = 0;
        $db = \Config\Database::connect();
        $builder = $db->table('heardt');
        $builder->select('COALESCE(MAX(brd_slno), 0) AS new_no')
        ->where('judges', $chk_jud_id)
            ->where('next_dt', $listing_dt)
            ->where('mainhead', $mf)
            ->where('clno', $partno - 1);
        //pr($builder->getCompiledSelect());
        $query = $builder->get();
        $rowmx = $query->getRowArray();
        $new_no = 0;

        if (!empty($rowmx) && $rowmx['new_no'] > 0) {
            $new_no = $rowmx['new_no'];
        } else {
            if ($partno == 50) {
                $new_no = 1000;
            } elseif ($partno == 99) {
                $new_no = 1500;
            } else {
                $new_no = 0;
            }
        }


        /*if ($mf != 'F') {
            //$leftJoinSubhead = "LEFT JOIN master.subheading s ON s.stagecode = h.subhead AND s.display = 'Y' AND s.listtype = '$mf'";
            $builder->join('master.subheading s', 's.stagecode = h.subhead AND s.display = \'Y\' AND s.listtype = \'' . $mf . '\'', 'left');
            $orderBy = "s.priority, 
                CAST(RIGHT(CAST(h.diary_no AS TEXT), 4) AS INTEGER) ASC,
                CAST(LEFT(CAST(h.diary_no AS TEXT), LENGTH(CAST(h.diary_no AS TEXT)) - 4) AS INTEGER) ASC";
        } else {
            //$leftJoinSubhead = "LEFT JOIN category_allottment c ON h.subhead = c.submaster_id AND c.ros_id = ? AND c.display = 'Y'";
            $builder->join('category_allottment c', 'h.subhead = c.submaster_id AND c.ros_id = \'' . $chk_rs_id . '\' AND c.display = \'Y\'', 'left');
            $orderBy = "CASE WHEN h.subhead = 913 THEN 0 ELSE 9999 END ASC,
                CAST(RIGHT(CAST(h.diary_no AS TEXT), 4) AS INTEGER) ASC,
                CAST(LEFT(CAST(h.diary_no AS TEXT), LENGTH(CAST(h.diary_no AS TEXT)) - 4) AS INTEGER) ASC";        
        }*/


        $builder = $db->table('heardt h');
        $builder->select('h.diary_no, m.conn_key');
        $builder->join('main m', 'm.diary_no = h.diary_no');
        if ($mf != 'F') {
            //$leftJoinSubhead = "LEFT JOIN master.subheading s ON s.stagecode = h.subhead AND s.display = 'Y' AND s.listtype = '$mf'";
            $builder->join('master.subheading s', 's.stagecode = h.subhead AND s.display = \'Y\' AND s.listtype = \'' . $mf . '\'', 'left');
            $orderBy = "s.priority, 
                CAST(RIGHT(CAST(h.diary_no AS TEXT), 4) AS INTEGER) ASC, 
                CAST(LEFT(CAST(h.diary_no AS TEXT), LENGTH(CAST(h.diary_no AS TEXT)) - 4) AS INTEGER) ASC";
        } else {
            //$leftJoinSubhead = "LEFT JOIN category_allottment c ON h.subhead = c.submaster_id AND c.ros_id = ? AND c.display = 'Y'";
            $builder->join('category_allottment c', 'h.subhead = c.submaster_id AND c.ros_id = \'' . $chk_rs_id . '\' AND c.display = \'Y\'', 'left');
            $orderBy = "CASE WHEN h.subhead = 913 THEN 0 ELSE 9999 END ASC,
                CAST(RIGHT(CAST(h.diary_no AS TEXT), 4) AS INTEGER) ASC, 
                CAST(LEFT(CAST(h.diary_no AS TEXT), LENGTH(CAST(h.diary_no AS TEXT)) - 4) AS INTEGER) ASC";
        }
        //$builder->join('master.subheading s', 's.stagecode = h.subhead AND s.display = \'Y\' AND s.listtype = \'' . $mf . '\'', 'left');

        $builder->where('(
            m.diary_no = CAST(NULLIF(m.conn_key, \'\') AS BIGINT)
            OR m.conn_key = \'\' 
            OR m.conn_key IS NULL
        )');
        $builder->where('m.c_status', 'P');
        $builder->where('h.mainhead', $mf);
        $builder->where('h.next_dt', $listing_dt);
        $builder->where('h.clno', $partno);
        $builder->where('h.brd_slno >', 0);
        $builder->where('h.judges', $chk_jud_id);
        $builder->where('h.roster_id >', 0);
        $builder->orderBy($orderBy, '', false);
        
        // Execute the query
        $query = $builder->get();
        $results = $query->getResult();

        $new_serial_number = $new_no;
        
        $builder = $db->table('heardt');
        foreach ($results as $row) {

            $conn_key = isset($row->conn_key) ? $row->conn_key : 0;
            $row->serial_number = $new_serial_number++;
            $builder->set('brd_slno', $row->serial_number);
            $builder->set('conn_key', $conn_key);
            $builder->where('diary_no', $row->diary_no);
            $builder->where('diary_no >', 0);
            $builder->update();
        }
        $result = 1;


        $sql_conn = "INSERT INTO last_heardt (diary_no, conn_key, next_dt, mainhead, subhead, clno, brd_slno, roster_id, judges, coram, board_type, usercode, ent_dt, module_id, mainhead_n, subhead_n, main_supp_flag, listorder, tentative_cl_dt, lastorder, listed_ia, sitting_judges,list_before_remark, is_nmd, no_of_time_deleted) SELECT j.* FROM (SELECT DISTINCT conc_diary_no, CAST(m.conn_key AS bigint), h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, h.board_type, h.usercode, h.ent_dt, h.module_id, h.mainhead_n, h.subhead_n, h.main_supp_flag, h.listorder, h.tentative_cl_dt, m.lastorder, h.listed_ia, h.sitting_judges,h.list_before_remark, h.is_nmd, h.no_of_time_deleted FROM (SELECT c.diary_no AS conc_diary_no, m.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, h.board_type, h.usercode, h.ent_dt, h.module_id, h.mainhead_n, h.subhead_n, h.main_supp_flag, h.listorder, h.tentative_cl_dt, m.lastorder, h.listed_ia, h.sitting_judges, h.list_before_remark, h.is_nmd, h.no_of_time_deleted 
            FROM heardt h INNER JOIN main m ON m.diary_no = h.diary_no INNER JOIN conct c ON c.conn_key = CAST(m.conn_key AS bigint)
                WHERE
                    c.list = 'Y' 
                    AND m.c_status = 'P' 
                    AND m.diary_no = CAST(m.conn_key AS bigint) 
                    AND h.mainhead = '$mf'
                    AND h.next_dt = '$listing_dt'
                    AND h.clno = '$partno'
                    AND h.brd_slno > 0 
                    AND h.judges = '$chk_jud_id'
                    AND h.roster_id > 0
                    ) a 
                    INNER JOIN main m ON a.conc_diary_no = m.diary_no 
                    INNER JOIN heardt h ON a.conc_diary_no = h.diary_no 
                WHERE 
                    m.c_status = 'P' 
                    AND h.next_dt IS NOT NULL
                ) j 
                LEFT JOIN last_heardt l ON j.conc_diary_no = l.diary_no 
                AND l.conn_key = CAST(j.conn_key AS bigint)
                AND l.next_dt = j.next_dt 
                AND l.mainhead = j.mainhead 
                AND l.board_type = j.board_type 
                AND l.subhead = j.subhead 
                AND l.clno = j.clno 
                AND l.coram = j.coram 
                AND l.judges = j.judges 
                AND l.roster_id = j.roster_id 
                AND l.listorder = j.listorder 
                AND l.tentative_cl_dt = j.tentative_cl_dt 
                AND COALESCE(j.listed_ia::bigint, 1) = COALESCE(l.listed_ia::bigint, 1)
                AND COALESCE(j.list_before_remark::text, '') = COALESCE(l.list_before_remark::text, '')
                AND l.no_of_time_deleted = j.no_of_time_deleted 
                AND l.is_nmd = j.is_nmd 
                AND l.main_supp_flag = j.main_supp_flag 
                AND (l.bench_flag = '' OR l.bench_flag IS NULL) 
            WHERE 
                l.diary_no IS NULL";

        $sql_conn = "INSERT INTO last_heardt (diary_no, conn_key, next_dt, mainhead, subhead, clno, brd_slno, roster_id, judges, coram, 
                        board_type, usercode, ent_dt, module_id, mainhead_n, subhead_n, main_supp_flag, listorder, tentative_cl_dt, lastorder, 
                        listed_ia, sitting_judges, list_before_remark, is_nmd, no_of_time_deleted)
                        SELECT j.*
                        FROM (
                        SELECT DISTINCT
                            a.conc_diary_no AS diary_no,
                            a.conn_key::int,
                            a.next_dt,
                            a.mainhead,
                            a.subhead,
                            a.clno,
                            a.brd_slno,
                            a.roster_id,
                            a.judges,
                            a.coram,
                            a.board_type::last_heardt_board_type,
                            a.usercode,
                            a.ent_dt,
                            a.module_id,
                            a.mainhead_n,
                            a.subhead_n,
                            a.main_supp_flag,
                            a.listorder,
                            a.tentative_cl_dt,
                            a.lastorder,
                            a.listed_ia,
                            a.sitting_judges,
                            a.list_before_remark,
                            a.is_nmd,
                            a.no_of_time_deleted
                        FROM (
                            SELECT DISTINCT 
                                c.diary_no AS conc_diary_no,
                                m.conn_key,
                                h.next_dt,
                                h.mainhead,
                                h.subhead,
                                h.clno,
                                h.brd_slno,
                                h.roster_id,
                                h.judges,
                                h.coram,
                                h.board_type,
                                h.usercode,
                                h.ent_dt,
                                h.module_id,
                                h.mainhead_n,
                                h.subhead_n,
                                h.main_supp_flag,
                                h.listorder,
                                h.tentative_cl_dt,
                                m.lastorder,
                                h.listed_ia,
                                h.sitting_judges,
                                h.list_before_remark,
                                h.is_nmd,
                                h.no_of_time_deleted
                            FROM heardt h
                            INNER JOIN main m ON m.diary_no = h.diary_no
                            INNER JOIN conct c ON c.conn_key = m.conn_key::int
                            WHERE c.list = 'Y' 
                            AND m.c_status = 'P' 
                            AND m.diary_no = m.conn_key  ::int   
                            AND h.mainhead = '$mf'
                            AND h.next_dt = '$listing_dt'
                            AND h.clno = '$partno'
                            AND h.brd_slno > 0 
                            AND h.judges = '$chk_jud_id'
                            AND h.roster_id > 0
                        ) a
                        INNER JOIN main m ON a.conc_diary_no = m.diary_no
                        INNER JOIN heardt h ON a.conc_diary_no = h.diary_no
                        WHERE m.c_status = 'P' 
                        AND h.next_dt IS NOT NULL 
                    ) j
                    LEFT JOIN last_heardt l ON j.diary_no = l.diary_no
                        AND l.conn_key = j.conn_key::int     
                        AND l.next_dt = j.next_dt
                        AND l.mainhead = j.mainhead
                        AND l.board_type = j.board_type::text
                        AND l.subhead = j.subhead        
                        AND l.clno = j.clno
                        AND l.coram = j.coram
                        AND l.judges = j.judges
                        AND l.roster_id = j.roster_id
                        AND l.listorder = j.listorder
                        AND l.tentative_cl_dt = j.tentative_cl_dt
                        AND (j.listed_ia IS NULL OR l.listed_ia = j.listed_ia)
                        AND (j.list_before_remark IS NULL OR l.list_before_remark = j.list_before_remark)        
                        AND l.no_of_time_deleted = j.no_of_time_deleted        
                        AND l.is_nmd = j.is_nmd        
                        AND l.main_supp_flag = j.main_supp_flag
                        AND (l.bench_flag = '' OR l.bench_flag IS NULL)
                    WHERE l.diary_no IS NULL";
        
        $query = $db->query($sql_conn);

        $sql = "UPDATE heardt h
                    SET 
                    conn_key = CAST(x.conn_key AS bigint), 
                    next_dt = x.next_dt, 
                    mainhead = x.mainhead, 
                    subhead = x.subhead, 
                    clno = x.clno, 
                    brd_slno = x.brd_slno, 
                    roster_id = x.roster_id, 
                    judges = x.judges, 
                    board_type = x.board_type, 
                    usercode = x.usercode, 
                    ent_dt = x.ent_dt, 
                    module_id = x.module_id, 
                    mainhead_n = x.mainhead_n, 
                    subhead_n = x.subhead_n, 
                    main_supp_flag = x.main_supp_flag, 
                    listorder = x.listorder, 
                    tentative_cl_dt = x.tentative_cl_dt, 
                    sitting_judges = x.sitting_judges, 
                    list_before_remark = x.list_before_remark, 
                    listed_ia = x.listed_ia, 
                    is_nmd = x.is_nmd, 
                    no_of_time_deleted = x.no_of_time_deleted
                    FROM 
                    (
                        SELECT 
                    conc_diary_no, 
                    m.conn_key, 
                    a.next_dt, 
                    a.mainhead, 
                    a.subhead, 
                    a.clno, 
                    a.brd_slno, 
                    a.roster_id, 
                    a.judges, 
                    a.board_type, 
                    a.usercode, 
                    a.ent_dt, 
                    a.module_id, 
                    h.mainhead_n, 
                    a.subhead_n, 
                    a.main_supp_flag, 
                    a.listorder, 
                    a.tentative_cl_dt, 
                    a.sitting_judges, 
                    h.listed_ia, 
                    h.list_before_remark, 
                    h.is_nmd, 
                    h.no_of_time_deleted 
                    FROM 
                    (
                        SELECT 
                        c.diary_no AS conc_diary_no, 
                        m.conn_key, 
                        h.next_dt, 
                        h.mainhead, 
                        h.subhead, 
                        h.clno, 
                        h.brd_slno, 
                        h.roster_id, 
                        h.judges, 
                        h.board_type, 
                        h.usercode, 
                        h.ent_dt, 
                        h.module_id, 
                        h.mainhead_n, 
                        h.subhead_n, 
                        h.main_supp_flag, 
                        h.listorder, 
                        h.tentative_cl_dt, 
                        h.sitting_judges, 
                        h.listed_ia, 
                        h.list_before_remark, 
                        h.is_nmd, 
                        h.no_of_time_deleted 
                        FROM 
                        heardt h 
                        INNER JOIN main m ON m.diary_no = h.diary_no 
                        INNER JOIN conct c ON c.conn_key = CAST(m.conn_key AS bigint)  
                        WHERE 
                        c.list = 'Y' 
                        and m.c_status = 'P' 
                        AND m.diary_no = CAST(m.conn_key AS bigint) 
                        AND h.mainhead = '$mf'
                        AND h.next_dt = '$listing_dt'  
                        AND h.clno = '$partno' 
                        AND h.brd_slno > 0 
                        AND h.judges = '$chk_jud_id' 
                        and h.roster_id > 0
                    ) a 
                    INNER JOIN main m ON a.conc_diary_no = m.diary_no 
                    INNER JOIN heardt h ON a.conc_diary_no = h.diary_no 
                    WHERE 
                    m.c_status = 'P' 
                    and h.next_dt IS NOT NULL

                                ) x
                                WHERE 
                                h.diary_no = x.conc_diary_no
                                AND h.diary_no > 0";
        $query = $db->query($sql);

        $sql_bench_flag = "UPDATE last_heardt SET bench_flag = 'X' WHERE next_dt > CURRENT_DATE AND clno > 0 AND brd_slno > 0 AND (bench_flag IS NULL OR bench_flag = '')";
        
        $query = $db->query($sql_bench_flag);

        return $result;
    }

    function remove_exaust_limit_judge($judge_group, $possible_judges_exploded)
    {
        $judge_limit_detail = array();
        for ($row = 0; $row < count($judge_group); $row++) {
            $judge_group_exploded = explode(",", $judge_group[$row][1]);
            $result_before_not = array_intersect($judge_group_exploded, $possible_judges_exploded);
            if (count($result_before_not) > 0) {
                if ($judge_group[$row][3] < $judge_group[$row][2]) {
                    $judge_limit_detail[] = array($judge_group[$row][0], $judge_group[$row][1], $judge_group[$row][2], $judge_group[$row][3]);
                }
            }
        }
        return $judge_limit_detail;
    }


    function f_cl_is_freezed($q_next_dt,$board_type,$partno,$mainhead){
        //$q_next_dt = '2019-04-29';
        $result = 0;
        $db = \Config\Database::connect();
        $builder = $db->table('cl_freezed');
        $builder->where('next_dt', $q_next_dt)
                ->where('part', $partno)
                ->where('m_f', $mainhead)
                ->where('board_type', $board_type)
                ->where('display', 'Y');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result = 1;
        }
    
        return $result;
    }
    
    function insert_eliminated_cases($diary_no, $listing_date, $board_type, $list_type, $reason) {
        $result = 0;
        $db = \Config\Database::connect();
        $builder = $db->table('eliminated_cases');
        $subQuery = $db->table('heardt h')
        ->select("m.diary_no, '{$listing_date}' AS next_dt_old, h.tentative_cl_dt, h.listorder, NOW() AS ent_dt, 25 AS test2, '{$board_type}' AS board_type, '{$list_type}' AS listtype, '{$reason}' AS reason,  0 AS listorder_new")
        ->join('main m', 'h.diary_no = m.diary_no')
        ->where('m.diary_no', $diary_no)
        ->getCompiledSelect();
    
        $sql = "INSERT INTO eliminated_cases (diary_no, next_dt_old, tentative_cl_dt_old, listorder, ent_dt, test2, board_type, listtype, reason, listorder_new) {$subQuery} AND NOT EXISTS (SELECT 1 FROM eliminated_cases e WHERE e.diary_no = m.diary_no)";
    
        $db->query($sql);
        $afros = $db->affectedRows();
    
        if ($afros > 0) {
            $result = 1;
        } 
        return $result;
    }
    
    
    function q_from_heardt_to_last_heardt($dno){
        $result = 0;
        $db = \Config\Database::connect();
        $sql_conn = "INSERT INTO last_heardt (diary_no,conn_key,next_dt,mainhead,subhead,clno,brd_slno,roster_id,judges,coram,board_type,usercode,ent_dt,module_id, mainhead_n, subhead_n,main_supp_flag,listorder,tentative_cl_dt,lastorder,listed_ia,sitting_judges, list_before_remark,is_nmd,no_of_time_deleted) SELECT j.* FROM (SELECT h.diary_no,h.conn_key,h.next_dt,h.mainhead,h.subhead,h.clno,h.brd_slno,h.roster_id,h.judges,h.coram,h.board_type,h.usercode,h.ent_dt,h.module_id, h.mainhead_n, h.subhead_n,h.main_supp_flag,h.listorder,h.tentative_cl_dt,m.lastorder,h.listed_ia,h.sitting_judges,h.list_before_remark, h.is_nmd, h.no_of_time_deleted FROM main m LEFT JOIN heardt h ON m.diary_no = h.diary_no where h.diary_no = '$dno' and h.diary_no > 0) j LEFT JOIN last_heardt l ON j.diary_no = l.diary_no AND l.conn_key = j.conn_key AND l.next_dt = j.next_dt AND l.mainhead = j.mainhead AND l.board_type = j.board_type AND l.subhead = j.subhead AND l.clno = j.clno AND l.coram = j.coram AND l.judges = j.judges AND l.roster_id = j.roster_id AND l.listorder = j.listorder AND l.tentative_cl_dt = j.tentative_cl_dt AND (j.listed_ia IS NULL OR l.listed_ia = j.listed_ia) AND (j.list_before_remark IS NULL OR l.list_before_remark = j.list_before_remark) AND l.no_of_time_deleted = j.no_of_time_deleted AND l.is_nmd = j.is_nmd AND l.main_supp_flag = j.main_supp_flag AND (l.bench_flag = '' OR l.bench_flag IS NULL) WHERE l.diary_no IS NULL";
    
        #AND IF(j.listed_ia IS NULL, 1 = 1,l.listed_ia = j.listed_ia)
        #AND IF(j.list_before_remark IS NULL,1 = 1,l.list_before_remark = j.list_before_remark) 
        $db->query($sql_conn);
        $afros = $db->affectedRows();
    
        if ($afros > 0) {
            $result = 1;
        } 
        return $result;
    }
    
    function if_three_judge_cat_coram($judge_group){
        $judge_group_exploded=explode(",",$judge_group);
        $key = array_search ('0', $judge_group_exploded);
        if(count($judge_group_exploded)==3){
            return true;
        }
        return false;
    }
    
    function checkIfJudgeNotSelected($notSelectedArray,$judgeCode){
        if(count($notSelectedArray)>0){
            for($notSelected=0;$notSelected<count($notSelectedArray);$notSelected++){
                $judgeGroupArray=explode(",",$notSelectedArray[$notSelected][1]);
                $result_intersected=in_array($judgeCode, $judgeGroupArray);
                if($result_intersected){
                    return true;
                }
            }
        }
        return false;
    }
    
    function findInMultiDimensionalArrayInArray($products, $field, $value)
    {
        foreach($products as $key => $product) {
            $keydata=explode(",",$product[$field]);
            if ( $keydata[0] === $value ){
                return $key;
            }
        }
        return false;
    }
    
    function remove_exaust_limit_judge_final($judge_group, $possible_judges_exploded, $listorder){
        $judge_limit_detail = array();
        for ($row = 0; $row < count($judge_group); $row++) {
            $judge_group_exploded = explode(",", $judge_group[$row][1]);
            $result_before_not = array_intersect($judge_group_exploded, $possible_judges_exploded);
            if (count($result_before_not) > 0) {
                if($listorder==32) {
                    //if($judge_group[$row][5] < $judge_group[$row][3] OR $ifMandatoryCases == 1){
                    if($judge_group[$row][5] < $judge_group[$row][3]){
                        $judge_limit_detail[] = array($judge_group[$row][0], $judge_group[$row][1],$judge_group[$row][2],$judge_group[$row][3],$judge_group[$row][4],$judge_group[$row][5],$judge_group[$row][6]);
                    }
                } else{
                    //if($judge_group[$row][6] < $judge_group[$row][4] OR $ifMandatoryCases == 1){
                    if($judge_group[$row][6] < $judge_group[$row][4] OR $listorder==4 OR $listorder==5){
                        $judge_limit_detail[] = array($judge_group[$row][0], $judge_group[$row][1],$judge_group[$row][2],$judge_group[$row][3],$judge_group[$row][4],$judge_group[$row][5],$judge_group[$row][6]);
                    }
                }
    
            }
        }
        return $judge_limit_detail;
    }
    
    function judge_seniority_reset($possible_judges)
    {
        $numro = null;
        $db = \Config\Database::connect();
        $sql = "SELECT string_agg(jcode::text, ',' ORDER BY judge_seniority) AS jsn
                FROM master.judge
                WHERE jcode IN ($possible_judges)
                GROUP BY display";

        $query = $db->query($sql);
    
        // Check if rows were returned
        if ($query->getNumRows() > 0) {
            $row = $query->getRowArray();
            return $row['jsn'];
        } else {
            return $numro;
        }
    }
    
    function moveElement(&$array, $a, $b) {
        $out = array_splice($array, $a, 1);
        array_splice($array, $b, 0, $out);
    }

 

    function state_name($str)
    {        
        $db = \Config\Database::connect();
        $res_state = $db->table('master.state')       
            ->select('name')
            ->where('id_no', $str)
            ->where('display', 'Y')
            ->get()->getRowArray();
        return $res_state['name'];
    }

    function send_to_nm($str)
    {        
        $db = \Config\Database::connect();
        $res_send_to = $db->table('tw_send_to')       
            ->select('desg')
            ->where('id', $str)
            ->where('display', 'Y')
            ->get()->getRowArray();

        return $res_send_to['desg'];
    }

    function send_to_advocate($str,$pet_res='',$sr_no='',$tot_parties='')
    {
        $db = \Config\Database::connect();
        $res_side=''; 
        if($pet_res!='' && $pet_res!='[0]')
        {
            $res_side=' for '.$pet_res;
        }
       
        $res_send_to = $db->table('master.bar')       
            ->select('name,title,caddress')
            ->where('bar_id', $str)
            ->where('display', 'Y')
            ->get()->getRowArray();

        if($tot_parties!='')
            $tot_parties=" for ".$tot_parties;

        return  (!empty($res_send_to)) ? $res_send_to['title'].' '. $res_send_to['name'].' (Adv.)'.$tot_parties.$res_side.'<br/>'. $res_send_to['caddress'] : '';
    }


    function send_to_court($str,$chk_casetype='')
    {
        $db = \Config\Database::connect();
        $is_order_challenged='';
        if($chk_casetype!='7' && $chk_casetype!='8')
        {
            $is_order_challenged="AND is_order_challenged = 'Y'";
        }
        $res_send_to =  $db->table('lowerct a')
        ->select("a.name,
            CASE 
                WHEN a.ct_code = 3 THEN 
                    (SELECT s.name FROM state s WHERE s.id_no = a.l_dist AND s.display = 'Y') 
                ELSE 
                    (SELECT CONCAT(c.agency_name, ', ', c.address) 
                     FROM ref_agency_code c 
                     WHERE c.cmis_state_id = a.l_state 
                     AND c.id = a.l_dist 
                     AND c.is_deleted = 'f')
            END AS agency_name,
            a.lct_judge_desg
        ")
        ->join('master.state b', 'a.l_state = b.id_no AND b.display = \'Y\'', 'left')
        ->join('main e', 'e.diary_no = a.diary_no', 'inner')
        ->where('a.lower_court_id', $str)
        ->where('a.lw_display', 'Y')
        ->where($is_order_challenged) // Dynamic condition
        ->get()
        ->getResultArray();

 
        if($res_send_to['lct_judge_desg']!=0)
        {
 
            $get_lower_court_judge= get_lower_court_judge($res_send_to['lct_judge_desg']);
            $lct_judge_desg=$get_lower_court_judge;
            return $lct_judge_desg;
        }
        else
        {
            return $res_send_to['agency_name'].' '. $res_send_to['name'];
        }
    }


    function check_record_frm_which_cout($diary_no,$lowerct_id)
    {
        $db = \Config\Database::connect();         
        $res_sql = $db->table('lowerct')       
        ->select('ct_code')
        ->where('diary_no', $diary_no)
        ->where('lower_court_id', $lowerct_id)
        ->where('lw_display', 'Y')
        ->get()->getRowArray();

    return $res_sql=  $res_sql['ct_code'];
    }


    function lower_court_conct_tp($diary_no,$tw_send_to) 
    {
        $db = \Config\Database::connect();

        //$chk_casetype = "Select active_casetype_id from main where diary_no='$diary_no'";
        //$chk_casetype = mysql_query($chk_casetype)or die("Error: " . __LINE__ . mysql_error());
        //$res_chk_casetype = mysql_result($chk_casetype, 0);

        $chk_casetype = is_data_from_table('main',  " diary_no='$diary_no' "," active_casetype_id ",'');
        $res_chk_casetype = $chk_casetype['active_casetype_id'] ?? '';
        $is_order_challenged = '';
        if ($res_chk_casetype != 25 && $res_chk_casetype != 26 && $res_chk_casetype != 7 && $res_chk_casetype != 8) {
            $is_order_challenged = " AND is_order_challenged = 'Y' ";
        }
     
        $sql = "
            SELECT 
                lct_dec_dt, 
                l_dist, 
                ct_code, 
                l_state, 
                Name, 
                agency_name, 
                STRING_AGG(lct_casetype::TEXT, ', ' ORDER BY lower_court_id) AS lct_casetype,
                STRING_AGG(lct_caseno::TEXT, ', ' ORDER BY lower_court_id) AS lct_caseno,
                STRING_AGG(lct_caseyear::TEXT, ', ' ORDER BY lower_court_id) AS lct_caseyear,
                STRING_AGG(type_sname::TEXT, ', ' ORDER BY lower_court_id) AS type_sname
            FROM (
                SELECT 
                    a.lct_dec_dt, 
                    a.l_dist, 
                    a.ct_code, 
                    a.l_state, 
                    b.Name,
                    CASE 
                        WHEN a.ct_code = 3 THEN (
                            SELECT Name FROM state s WHERE s.id_no = a.l_dist AND display = 'true'
                        )
                        ELSE (
                            SELECT CONCAT(agency_name, ', ', address) FROM ref_agency_code c 
                            WHERE c.cmis_state_id = a.l_state 
                            AND c.id = a.l_dist 
                            AND is_deleted = 'false'
                        )
                    END AS agency_name,
                    a.lct_casetype,
                    a.lct_caseno,
                    a.lct_caseyear,
                    CASE 
                        WHEN a.ct_code = 4 THEN (
                            SELECT short_description FROM casetype ct WHERE ct.display = 'true' AND ct.casecode = a.lct_casetype
                        )
                        ELSE (
                            SELECT type_sname FROM lc_hc_casetype d WHERE d.lccasecode = a.lct_casetype AND d.display = 'true'
                        )
                    END AS type_sname,
                    a.lower_court_id
                FROM lowerct a
                LEFT JOIN state b ON a.l_state = b.id_no AND b.display = 'true'
                JOIN main e ON e.diary_no = a.diary_no
                WHERE a.diary_no = ?
                AND a.lw_display = 'true' 
                AND a.lower_court_id = ?
                $is_order_challenged
                ORDER BY a.lower_court_id
            ) AS aa
            GROUP BY lct_dec_dt, l_dist, ct_code, l_state, Name, agency_name;
        ";

        $results =  $db->query($sql, [$diary_no, $tw_send_to])->getResultArray();


        if (!empty($results)) {
            $outer_array = array();
            foreach ($results as $row) {
                $inner_array = array();
                $inner_array[0] = $row['lct_dec_dt'];
                $inner_array[1] = $row['Name'];
                $inner_array[2] = $row['agency_name'];
                $inner_array[3] = $row['type_sname'];
                $inner_array[4] = $row['lct_caseno'];
                $inner_array[5] = $row['lct_caseyear'];
                $inner_array[6] = $row['lct_casetype'];
                $outer_array[] = $inner_array;
            }
            return $outer_array;
        }
    }

    function f_get_subhead_basis($param){
        $builder = \Config\Database::connect()->table('master.subheading s');
        $builder->select('stagename')
            ->where('s.stagecode', $param)
            ->where('s.display', 'Y');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            foreach ($query->getResultArray() as $row) {
                echo $row['stagename'];
            }
        }
    }

    function f_get_brdrem($param){
        $builder = \Config\Database::connect()->table('brdrem b');
        $builder->select('remark')
            ->where('b.diary_no', $param);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            foreach ($query->getResultArray() as $row) {
                echo nl2br("\n" . $row['remark']);
            }
        }
    }

    function f_get_act_main($parm1) {
        $builder = \Config\Database::connect()->table('act_main a');
        $builder->select('b.act_name, c.section')
            ->join('master.act_master b', 'a.act = b.id', 'left')
            ->join('master.act_section c', 'c.act_id = a.id', 'left')
            ->where('a.diary_no', $parm1)
            ->where('a.display', 'Y')
            ->where('b.display', 'Y');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            foreach ($query->getResultArray() as $row) {
                echo nl2br("\n" . $row['act_name'] . " Section: " . $row['section']);
            }
        }

        // $sql="select b.act_name, c.section from act_main a left join act_master b ON a.act = b.id left join act_section c on c.act_id = a.id WHERE a.diary_no = '$parm1' and a.display = 'Y' and b.display = 'Y'";
        // $res=mysql_query($sql) or die(mysql_error());
        // if(mysql_num_rows($res)>0){
        //     while($row=mysql_fetch_array($res)){
        //         echo nl2br("\n".$row["act_name"]." Section :".$row["section"]);
        //     }
        // }
    }

    function f_get_docdetail($param){
        $builder = \Config\Database::connect()->table('docdetails dd');
        $builder->select('dm.docdesc')
            ->join('master.docmaster dm', 'dd.doccode1 = dm.doccode1 AND dd.doccode = dm.doccode', 'left')
            ->where('dd.diary_no', $param)
            ->where('dd.doccode', '8')
            ->where('dm.doccode', '8')
            ->where('dd.iastat', 'P')
            ->where('dd.display', 'Y')
            ->where('dm.display', 'Y')
            ->where('dm.docdesc !=', 'XTRA')
            ->groupBy('dm.docdesc');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            foreach ($query->getResultArray() as $row) {
                echo nl2br("\n" . $row['docdesc']);
            }
        }
    }
    
    function f_get_kword($param){
        $builder = \Config\Database::connect()->table('ec_keyword k');
        $builder->select('rk.keyword_description')
            ->join('master.ref_keyword rk', 'rk.id = k.keyword_id', 'left')
            ->where('k.diary_no', $param)
            ->where('k.display', 'Y');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            foreach ($query->getResultArray() as $row) {
                echo nl2br("\n" . $row['keyword_description']);
            }
        }
    }

    function get_header_footer_printed($list_dt, $mainhead, $roster_id, $part_no, $flag)
{
    $db = \Config\Database::connect();
    
    $sql = "SELECT h_f_note FROM headfooter 
            WHERE display = 'Y' 
            AND next_dt = ? 
            AND part = ? 
            AND mainhead = ? 
            AND roster_id = ? 
            AND h_f_flag = ? 
            ORDER BY ent_dt";

    // Use query binding to prevent SQL injection
    $query = $db->query($sql, [$list_dt, $part_no, $mainhead, $roster_id, $flag]);
    $res = $query->getResultArray(); // Fetch result as an associative array

    if (count($res)) {
    ?>
        <table border="0" cellspacing="0">
            <tr>
                <td style="text-align:left"><U>NOTE</U>:-</td>
            </tr>
            <?php foreach ($res as $row) { ?>
                <tr>
                    <td style="text-align:left">
                        <?php echo htmlspecialchars($row['h_f_note']); ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <?php
    }
}

 function get_user_name_info($emp_id){
	  $db = \Config\Database::connect();
	  $query = $db->table("master.users")
                        ->select("name")
                        ->where("empid", $emp_id)
                        ->get();

                if ($query->getNumRows() > 0) {
                    $row = $query->getRowArray(); 
					return $row['name'];
                }else{
					return '';
				}
 }
 
 function get_user_name_uid($emp_id){
	  $db = \Config\Database::connect();
	  $query = $db->table("master.users")
                        ->select("name")
                        ->where("usercode", $emp_id)
                        ->get();

                if ($query->getNumRows() > 0) {
                    $row = $query->getRowArray(); 
					return $row['name'];
                }else{
					return '';
				}
 }
 
 function get_mul_category_details($diary_no){
	 $db = \Config\Database::connect();
	 $builder =  $db->table('mul_category a')
			->select('b.sub_name1, b.sub_name4, b.category_sc_old')
			->join('master.submaster b', 'a.submaster_id = b.id', 'inner')
			->where('a.diary_no', $diary_no)
			->where('a.display', 'Y')
			->where('b.display', 'Y');
       $query = $builder->get();
	   return $query->getResultArray();
 }

 function get_not_before_details($diary_no){
	$db = \Config\Database::connect();
	$builder = $db->table('not_before')
			->select('notbef, j1')
			->where('diary_no', $diary_no)
			->orderBy('notbef', 'ASC'); // Change 'ASC' to 'DESC' if needed
  // echo $builder->getCompiledSelect(); die; 
		$query = $builder->get(); 
		return $query->getResultArray();
 }
 
 function get_judge_nm($jud_id){
	  $db = \Config\Database::connect();
	  $query = $db->table("master.judge")
                        ->select("jname")
                        ->where("jcode", $jud_id)
                        ->get();

                if ($query->getNumRows() > 0) {
                    $row = $query->getRowArray(); 
					return $row['jname'];
                }else{
					return '';
				}
 }
 
 function get_b_c($diary_no){
	  $db = \Config\Database::connect();
		$query = $db->table('heardt')
			->select('coram')
			->where('diary_no', $diary_no)
			->get();

		if ($query->getNumRows() > 0) {
			return $query->getRow()->coram; 
		} else {
			return ''; 
		}
}

 function r_h_dt($diary_no){
	  $db = \Config\Database::connect();
	  $builder = $db->table('heardt')
				->select('next_dt,clno,brd_slno')
				->where('diary_no', $diary_no)
				->orderBy('next_dt', 'ASC'); // Change 'ASC' to 'DESC' if needed
				$query = $builder->get();
		return $query->getRowArray();
 }
 
 
 
 function case_pages($diary_no){
	  $db = \Config\Database::connect();
	  $query = $db->table('main')
			->select('case_pages')
			->where('diary_no', $diary_no)
		    ->get();
        
	if ($query->getNumRows() > 0) {
		return $query->getRowArray(); 
	} else {
		return  null; 
	}
	  
}

function case_verification_report_popup_inside_details($id){
	
		$db = \Config\Database::connect();

		$builder = $db->table('tempo o');
		$builder->select("o.diary_no, o.jm AS pdfname, DATE(o.dated) AS orderdate, 
			CASE 
				WHEN o.jt = 'rop' THEN 'ROP'
				WHEN o.jt = 'judgment' THEN 'Judgement'
				WHEN o.jt = 'or' THEN 'Office Report'
			END AS jo");
		$builder->where('o.diary_no', $id);
		$tempo = $builder->get()->getResultArray();

	
		$builder = $db->table('ordernet o');
		$builder->select("o.diary_no, o.pdfname AS pdfname, DATE(o.orderdate) AS orderdate, 
			CASE 
				WHEN o.type = 'O' THEN 'ROP'
				WHEN o.type = 'J' THEN 'Judgement'
			END AS jo");
		$builder->where('o.diary_no', $id);
		$ordernet = $builder->get()->getResultArray();

	
		$builder = $db->table('rop_text_web.old_rop o');
		$builder->select("o.dn AS diary_no, CONCAT('ropor/rop/all/', o.pno, '.pdf') AS pdfname, DATE(o.orderDate) AS orderdate, 'ROP' AS jo");
		$builder->where('o.dn', $id);
		$old_rop = $builder->get()->getResultArray();

	
		$builder = $db->table('scordermain o');
		$builder->select("o.dn AS diary_no, CONCAT('judis/', o.filename, '.pdf') AS pdfname, DATE(o.juddate) AS orderdate, 'Judgment' AS jo");
		$builder->where('o.dn', $id);
		$scordermain = $builder->get()->getResultArray();

	
		$builder = $db->table('rop_text_web.ordertext o');
		$builder->select("o.dn AS diary_no, CONCAT('bosir/orderpdf/', o.pno, '.pdf') AS pdfname, DATE(o.orderdate) AS orderdate, 'ROP' AS jo");
		$builder->where('o.dn', $id);
		$builder->where('o.display', 'Y');
		$ordertext = $builder->get()->getResultArray();

	
		$builder = $db->table('rop_text_web.oldordtext o');
		$builder->select("o.dn AS diary_no, CONCAT('bosir/orderpdfold/', o.pno, '.pdf') AS pdfname, DATE(o.orderdate) AS orderdate, 'ROP' AS jo");
		$builder->where('o.dn', $id);
		$oldordtext = $builder->get()->getResultArray();

	
		$results = array_merge($tempo, $ordernet, $old_rop, $scordermain, $ordertext, $oldordtext);

	
		$results = array_filter($results, function ($row) {
			return $row['jo'] === 'ROP';
		});

	
		usort($results, function ($a, $b) {
			return strtotime($b['orderdate']) - strtotime($a['orderdate']);
		});

		return $results;
	 
	 
 }

 function get_advocates1($adv_id, $wen = '')
{
    $db = \Config\Database::connect();
    $t_adv = "";

    if ($adv_id != 0) {
        $query = $db->table('master.bar')
            ->select("name, enroll_no, YEAR(enroll_date) as eyear, isdead")
            ->whereIn('bar_id', explode(',', $adv_id)) // Convert CSV IDs into an array for safe query execution
            ->get();

        $result = $query->getResultArray();

        if (!empty($result)) {
            foreach ($result as $row) {
                $t_adv = $row['name'];

                if ($row['isdead'] == 'Y') {
                    $t_adv = "<font color=red>{$t_adv} (Dead / Retired / Elevated)</font>";
                }

                if ($wen == 'wen') {
                    $t_adv .= " [{$row['enroll_no']}/{$row['eyear']}]";
                }
            }
        }
    }

    return $t_adv;
}

function get_display_status_with_date_differences_new($tentative_cl_dt)
{
    $tentative_cl_date_greater_than_today_flag = "F";

    if (!empty($tentative_cl_dt)) {
        $curDate = date('d-m-Y');
        $tentativeCLDate = date('d-m-Y', strtotime($tentative_cl_dt));
        $datediff = strtotime($tentativeCLDate) - strtotime($curDate);
        $noofdays = round($datediff / (60 * 60 * 24));

        if (strtotime($tentativeCLDate) > strtotime($curDate)) {
            if ($noofdays <= 60 && $noofdays > 0) {
                $tentative_cl_date_greater_than_today_flag = 'T';
            }
        }
    }

    return $tentative_cl_date_greater_than_today_flag;
}