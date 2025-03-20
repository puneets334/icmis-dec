<?php

/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 24/7/19
 * Time: 4:51 PM
 */

use CodeIgniter\Database\Query;

function encrypt_partyname($partyname)
{
    // Store the cipher method
    $ciphering = "AES-128-CTR";

    // Use OpenSSl Encryption method
    $iv_length = openssl_cipher_iv_length($ciphering);


    // Non-NULL Initialization Vector for encryption
    $encryption_iv = '1234567891011121';

    // Store the encryption key
    $encryption_key = "IcmisEncryptedPartyName";


    // Use openssl_encrypt() function to encrypt the data
    $encryption = openssl_encrypt($partyname, $ciphering, $encryption_key, 0, $encryption_iv);
    return $encryption;
}
function decrypt_partyname($encrypted_partyname)
{
    // Store the cipher method
    $ciphering = "AES-128-CTR";

    // Non-NULL Initialization Vector for decryption
    $decryption_iv = '1234567891011121';

    // Store the decryption key
    $decryption_key = "IcmisEncryptedPartyName";

    // Use openssl_decrypt() function to decrypt the data
    $decryption = openssl_decrypt($encrypted_partyname, $ciphering, $decryption_key, 0, $decryption_iv);
    return $decryption;
}




function last_listed_date($diaryno)
{
    // Get the database connection
    $db = \Config\Database::connect();

    // Build the query
    $builder = $db->table('heardt')
        ->select('MAX(nextdt) as nextdt')
        ->where('diary_no', $diaryno)
        ->whereIn('main_supp_flag', [1, 2])
        ->where('clno !=', 0)
        ->where('brd_slno !=', 0)
        ->where('judges !=', '');

    // Union with the second table
    $builder->union(
        $db->table('last_heardt')
            ->select('diary_no, next_dt as nextdt')
            ->where('diary_no', $diaryno)
            ->whereIn('main_supp_flag', [1, 2])
            ->where('bench_flag IS NULL OR bench_flag = ""')
            ->where('clno !=', 0)
            ->where('brd_slno !=', 0)
            ->where('judges !=', '')
    );

    // Execute the query and get the result
    $query = $builder->get();
    $result = $query->getRowArray();

    // Return the last listed date or 0 if not found
    return $result ? $result['nextdt'] : 0;
}


function get_causetitle($diaryno)
{
    // Get the database connection
    $db = \Config\Database::connect();

    // Build the query
    $query = $db->table('main')
        ->select('pet_name, res_name, pno, rno')
        ->where('diary_no', $diaryno)
        ->get();

    // Initialize cause title
    $cause_title = '';

    // Check if any results were returned
    if ($query->getNumRows() > 0) {
        $cause_title_arr = $query->getRowArray();
        $cause_title .= $cause_title_arr['pet_name'];

        // Append "AND ANR" or "AND ORS" based on pno
        if ($cause_title_arr['pno'] == 2) {
            $cause_title .= "<font color='blue'> AND ANR </font>";
        } elseif ($cause_title_arr['pno'] > 2) {
            $cause_title .= "<font color='blue'> AND ORS </font>";
        }

        // Append "VS" and res_name
        $cause_title .= "<font color='blue'> VS </font>" . $cause_title_arr['res_name'];

        // Append "AND ANR" or "AND ORS" based on rno
        if ($cause_title_arr['rno'] == 2) {
            $cause_title .= "<font color='blue'> AND ANR </font>";
        } elseif ($cause_title_arr['rno'] > 2) {
            $cause_title .= "<font color='blue'> AND ORS </font>";
        }
    }

    return $cause_title;
}

function get_da($diaryno)
{
    // Get the database connection
    $db = \Config\Database::connect();

    // Build the query
    $query = $db->table('main')
        ->select('dacode')
        ->where('diary_no', $diaryno)
        ->get();

    // Initialize DA
    $da = 0;

    // Check if any results were returned
    if ($query->getNumRows() > 0) {
        $result = $query->getRowArray();
        $da = $result['dacode'];
    }

    return $da;
}
function is_holiday1($date)
{
    // Get the database connection
    $db = \Config\Database::connect();

    // Build the query
    $query = $db->table('holidays')
        ->select('hdate')
        ->where('hdate', $date)
        ->where('emp_hol <>', 0)
        ->get();

    // Check if any results were returned
    return $query->getNumRows() > 0 ? 1 : 0;
}


function get_defect_days1($df, $refil_date, $last_day_of_refiling, $diary_no)
{
    $db = \Config\Database::connect();
    $total = 0;

    // Before COVID-19 period
    if ($last_day_of_refiling <= '2020-03-07') {
        // Calculate days before COVID
        $daysQuery = $db->query("SELECT DATEDIFF('2020-03-06', '$last_day_of_refiling') AS days");
        $bl = $daysQuery->getRow()->days;

        echo "<tr><td>Delay till 06-03-2020    * pre-covid <b>(a)</b></td><td><font color='red'><b>" . date_format(date_create($last_day_of_refiling), 'd-m-Y') . "  to (06-03-2020) =  " . $bl . " days </font></b></td></tr>";

        // Calculate post-COVID refile date delays
        if ($refil_date <= '2022-07-11') {
            $l2 = 0;
        } else {
            $l2 = date_diff(date_create('2022-06-01'), date_create($refil_date))->days + 1;
        }
        $total = $bl + 0 + $l2;

        echo "<tr><td>Dead(corona) Period     <b>(b)</b></td><td><font color='red'><b>(07-03-2020)  to (28-02-2022) =  0 days </font></b></td></tr>";
        echo "<tr><td>Delay Days Calculated   <b>(c)</b></td><td><b> (01-06-2022 to " . date_format(date_create($refil_date), 'd-m-Y') . ") = " . $l2 . " days </b></td></tr>";
        echo "<tr><td>total Delay   <b> [(a) + (b) + (c)] </b></td><td><b>" . $total . " days </b></td></tr>";
    }

    // COVID-19 limitation expired period
    if ($last_day_of_refiling > '2020-03-07' && $last_day_of_refiling <= '2022-02-28') {
        $l1 = 0;

        if ($refil_date > '2022-07-11') {
            $dff = '2022-06-01';
            $daysQuery = $db->query("SELECT DATEDIFF('$refil_date', '$dff') AS days");
            $l2 = $daysQuery->getRow()->days + 1;
        } else {
            $l2 = 0;
        }

        echo "<tr><td>Dead(corona) Period     <b>(a)</b></td><td><font color='red'><b>(07-03-2020)  to (28-02-2022) =  " . $l1 . " days </font></b></td></tr>";
        echo "<tr><td>Delay Days Calculated  <b>(b)</b></td><td><b>" . date_format(date_create($dff), 'd-m-Y') . " to " . date_format(date_create($refil_date), 'd-m-Y') . " = " . $l2 . " days</b></td></tr>";
        echo "<tr><td>total Delay   <b> [(a)  + (b)]  </b></td><td><b>" . $l2 . " days </b></td></tr>";

        $total = $l2;
    }

    // Post COVID-19
    if ($last_day_of_refiling > '2022-02-28') {
        if ($refil_date > '2022-07-11') {
            $daysQuery = $db->query("SELECT DATEDIFF('$refil_date', '$last_day_of_refiling') AS days");
            $total = $daysQuery->getRow()->days;
        }
    }

    return $total;
}
