<?php

/**
 * Created by PhpStorm.
 * User: Anshu
 * Date: 26/8/23
 * Time: 11:30 AM
 */

use CodeIgniter\Database\Config;
use CodeIgniter\Database\Database as DB;
use CodeIgniter\I18n\Time;

if (!function_exists('dump')) {

    function dump($data)
    {
        echo '<pre>';
        print_r($data);
    }
}

if (!function_exists('pr')) {

    function pr($data)
    {
        echo '<pre>';
        print_r($data);
        die;
    }
}

if (! function_exists('pra')) {
    function pra($request)
    {
        pr($request->toArray());
    }
}

function log_error($heading, $data)
{
    $log_file = WRITEPATH . "/logs/my_logs.txt";

    file_put_contents($log_file, "\n\n====================" . date("Y-m-d H:i:s") . ":" . strtoupper($heading) . "===================\n", FILE_APPEND);

    if (is_array($data)) {
        file_put_contents($log_file, json_encode($data), FILE_APPEND);
    } else {
        file_put_contents($log_file, $data, FILE_APPEND);
    }
}


function sanitize($val)
{
    $val = preg_replace('/string:/', '', $val);
    return $val;
}
function encrypt($val)
{
    $encrypter = \Config\Services::encrypter();
    $enc_id = bin2hex($encrypter->encrypt($val));
    return $enc_id;
}
function decrypt($val)
{
    $encrypter = \Config\Services::encrypter();
    $dec_id = $encrypter->decrypt(hex2bin($val));
    return $dec_id;
}
function url_encryption($val)
{
    $encrypter = \Config\Services::encrypter();
    $enc_id = bin2hex($encrypter->encrypt($val));
    return $enc_id;
}
function url_decryption($val)
{
    $encrypter = \Config\Services::encrypter();
    $dec_id = $encrypter->decrypt(hex2bin($val));
    return $dec_id;
}
function insert($table_name, $data)
{
    $db = \Config\Database::connect();
    $builder = $db->table($table_name);
    if ($builder->insert($data)) {
        return true;
    } else {
        return false;
    }
}
function update($table_name, $data, $condition)
{
    $db = \Config\Database::connect();
    $builder = $db->table($table_name);
    $builder->where($condition);
    if ($builder->update($data)) {
        return true;
    } else {
        return false;
    }
}

function getUser_dpdg_full_2($usercode)
{
    $db = \Config\Database::connect();

    try {
        // Ensure correct table name
        $query = $db->query("SELECT name, udept, usertype, section, display, attend, empid, service FROM users_22092000 WHERE usercode = ?", [$usercode]);

        // Get the result
        $result = $query->getRowArray();
        if ($result) {
            return $result['usertype'] . '~' . $result['udept'] . '~' . $result['section'] . '~' . $result['name'] . '~' . $result['attend'] . '~' . $result['display'] . '~' . $result['empid'] . '~' . $result['service'];
        } else {
            return '0';
        }
    } catch (\Exception $e) {
        log_message('error', "Query failed: " . $e->getMessage());
        return '0';
    }
}




if (!function_exists('next_court_working_date')) {

    function next_court_working_date(string $date): ?string
    {
        // pr($date);
        $db = \Config\Database::connect();
        $sql = "SELECT working_date 
                FROM master.sc_working_days 
                WHERE working_date >= ? 
                AND display = 'Y' 
                AND is_holiday = 0
                ORDER BY working_date asc 
                LIMIT 1";

        $query = $db->query($sql, [$date]);
        $row = $query->getRowArray();
        return $row['working_date'] ?? null;
    }
}

//...............New added on 10-10-2024..........//

if (!function_exists('get_diaryA_fm')) {
    function get_diaryA_fm($dn, $module)
    {
        $diary_no = substr($dn, 0, -4) . "/" . substr($dn, -4);
        $location_f = "";
        $ucode = session()->get('login')['usercode'];

        $db = \Config\Database::connect();

        $builder = $db->table('diary_copy_set');
        $builder->where('diary_no', $dn);
        $builder->where('copy_set', 'A');
        $query_set = $builder->get();

        $sno = 0;
        foreach ($query_set->getResultArray() as $row_set) {
            $builder = $db->table('diary_movement');
            $builder->where('diary_copy_set', $row_set['id']);
            $current_rs = $builder->get();
            $row = $current_rs->getRowArray();

            if ($current_rs->getNumRows() == 0) {
                $location = "<div style='color: red;'>Case is not in File Movement</div>";
                $masterhead = 1;
            } else {
                $location = "";
                if ($module == 'receive') {
                    if ($row['rece_by'] == $ucode) {
                        $location = "<span style='color:red;'> File is already Received by you. </span>";
                        $masterhead = 2;
                    } elseif ($row['disp_to'] != $ucode) {
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
                    } elseif ($row['rece_by'] != $ucode) {
                        $location = "<span style='color:red;'> File is not received by you. </span>";
                        $masterhead = 3;
                    } else {
                        $masterhead = 4;
                    }
                }

                if ($row['rece_by'] == 0) {
                    $location .= "Dispatched To: " . get_user_details($row['disp_to']) . " on " . date('d-m-Y, h:i A', strtotime($row['disp_dt']));
                } else {
                    $location .= "Received By: " . get_user_details($row['rece_by']) . " on " . date('d-m-Y, h:i A', strtotime($row['rece_dt']));
                }
            }

            $t_chk = ($masterhead == 1 || $masterhead == 4) ? "checked=checked" : "disabled=disabled";
            $sno++;
            $location_f .= '<input class="chk" type="checkbox" name="chk[]" value="' . $row_set['id'] . "-" . $masterhead . '-' . '" ' . $t_chk . '/>' . $location;
        }
        return $location_f;
    }
}

if (!function_exists('get_user_details')) {
    function get_user_details($usercode)
    {
        $user = "";

        $db = \Config\Database::connect();

        $builder = $db->table('master.users a');
        $builder->select('a.usercode, a.name, a.empid, a.service, a.udept, a.section, a.usertype, a.log_in, a.jcode, a.attend, b.dept_name, c.section_name, d.type_name, a.entdt, c.isda');
        $builder->join('master.userdept b', 'a.udept = b.id', 'left');
        $builder->join('master.usersection c', 'a.section = c.id', 'left');
        $builder->join('master.usertype d', 'a.usertype = d.id', 'left');
        $builder->where('a.usercode', $usercode);

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            $row = $query->getRowArray();
            $isda = ($row['isda'] == "Y") ? " [DA]" : "";
            $user = "Department: <font color=green>" . $row['dept_name'] . "</font>, Section: <font color=green>" . $row['section_name'] . $isda . "</font>, User Name: <font color=green>" . $row['name'] . ", " . $row['type_name'] . "</font>";
            return $user;
        } else {
            return null;
        }
    }
}

function remove_notbefore_judge($judge_group, $ntl_group)
{
    $ntl_group_exploded = explode(",", $ntl_group);
    for ($rowntl = 0; $rowntl < count($ntl_group_exploded); $rowntl++) {
        for ($row = 0; $row < count($judge_group); $row++) {
            $judge_group_exploded = explode(",", $judge_group[$row][1]);
            $ntl_group_exploded_exploded = explode(",", $ntl_group_exploded[$rowntl]);
            $result_before_not = array_intersect($judge_group_exploded, $ntl_group_exploded_exploded);
            if (count($result_before_not) === 0) {
            } else {
                unset($judge_group[$row]);
                $judge_group = array_values($judge_group);
            }
        }
    }

    return $judge_group;
}

function f_get_cji_code()
{
    $db = \Config\Database::connect();
    $sql = "SELECT jcode FROM master.judge WHERE is_retired != 'Y' AND cji_date IS NOT NULL LIMIT 1";
    $rs_crm = $db->query($sql);
    $row_coram = $rs_crm->getRowArray();
    return $row_coram['jcode'];
}

function check_list_before_bk($q_diary_no, $before_flag)
{
    $db = \Config\Database::connect();
    if ($before_flag == 'B') {
        $diary_numbers = explode(",", $q_diary_no);
        $int_diary_numbers = array_map('intval', $diary_numbers);

        $notbef = 'N';
        $is_retired = 'N';

        $postgres_array = '{' . implode(',', $int_diary_numbers) . '}';

        $sql = "
                SELECT string_agg(DISTINCT n.j1::text, ',' ORDER BY n.j1::text) AS j1
                FROM not_before n
                INNER JOIN master.judge j ON j.jcode = n.j1
                WHERE n.diary_no::int = ANY ('$postgres_array') AND n.notbef = ? AND j.is_retired = ?
            ";

        $res = $db->query($sql, [$notbef, $is_retired]);
    }
    if ($before_flag == 'N') {
        $diary_numbers = explode(",", $q_diary_no);
        $int_diary_numbers = array_map(function ($num) {
            return (int)trim($num);
        }, $diary_numbers);

        // Convert the PHP array to a PostgreSQL array string
        $postgres_array = '{' . implode(',', $int_diary_numbers) . '}';

        $sql = "
            SELECT string_agg(DISTINCT j1::text, ',' ORDER BY j1::text) as j1
            FROM (
                SELECT distinct org_judge_id as j1 FROM advocate a
                INNER JOIN master.ntl_judge n ON n.org_advocate_id = a.advocate_id
                WHERE a.diary_no = ANY ('$postgres_array') AND n.display = 'Y' AND a.display = 'Y'
                UNION
                SELECT distinct n.org_judge_id as j1 FROM party a
                INNER JOIN master.ntl_judge_dept n ON a.deptcode = dept_id
                WHERE a.diary_no = ANY ('$postgres_array') AND a.pflag != 'T' AND n.display = 'Y'
                UNION
                SELECT distinct n.j1 FROM not_before n
                INNER JOIN master.judge j ON j.jcode = n.j1
                WHERE n.diary_no = ANY ('$postgres_array') AND n.notbef = 'N' AND j.is_retired = 'N'
                UNION
                SELECT distinct n.org_judge_id as j1 FROM (SELECT s.id FROM (SELECT s.id, sub_name1 FROM mul_category c, master.submaster s WHERE s.id = submaster_id AND diary_no = ANY ('$postgres_array') AND c.display = 'Y' AND s.display = 'Y') a INNER JOIN master.submaster s ON s.sub_name1 = a.sub_name1 WHERE flag = 's') a
                INNER JOIN master.ntl_judge_category n ON n.cat_id = a.id WHERE n.display = 'Y'
            ) aa
        ";

        $res = $db->query($sql);
    }

    $judges_code = '';

    $res_count = $res->getNumRows();
    $res_data = $res->getResultArray();
    // if ($res_count > 1 and $before_flag == 'B') {
    //     $judges_code = '-1';
    // } else if ($res_count > 0 and $before_flag == 'N') {
    //     foreach ($res_data as $row) {
    //         $judges_code .= $row['j1'] . ',';
    //     }
    //     $judges_code = rtrim($judges_code, ',');
    // } else if ($res_count == 1 and $before_flag == 'B') {
    //     $row = $res_data;
    //     $judges_code = $row['j1'];
    // } else {
    //     $judges_code = '';
    // }


    if ($res_count > 0 && $before_flag == 'B') {
        $row = $res->getRowArray();
        $judges_code = $row['j1'];
    } else if ($res_count == 0) {
        $judges_code = '-1';
    } else {
        foreach ($res_data as $row) {
            $judges_code .= $row['j1'] . ',';
        }
        $judges_code = rtrim($judges_code, ',');
    }
    return $judges_code;
}



function check_list_before_advance($q_diary_no, $before_flag)
{
    $db = \Config\Database::connect();
    //$before_flag = 'N';
    if ($before_flag == 'B') {
        $sql = "
            SELECT DISTINCT STRING_AGG(n.j1::TEXT, ',' ORDER BY judge_seniority) AS j1
            FROM not_before n
            INNER JOIN master.judge j ON j.jcode = n.j1
            WHERE n.diary_no::bigint IN ($q_diary_no)
                AND j.is_retired = 'N'
                AND n.notbef = '$before_flag'
            GROUP BY diary_no;
        ";
    }
    if ($before_flag == 'N') {
        /*$sql1 = "SELECT STRING_AGG(j1::TEXT, ',' ORDER BY j1) AS j1
                    FROM (SELECT DISTINCT j1 FROM (SELECT DISTINCT org_judge_id AS j1 FROM advocate a INNER JOIN master.ntl_judge n ON n.org_advocate_id = a.advocate_id WHERE a.diary_no IN ($q_diary_no) AND n.display = 'Y' AND a.display = 'Y'
                    UNION
                    SELECT DISTINCT n.org_judge_id AS j1 FROM party a INNER JOIN master.ntl_judge_dept n ON a.deptcode = n.dept_id WHERE a.diary_no IN ($q_diary_no) AND a.pflag != 'T' AND n.display = 'Y'
                    UNION
                    SELECT DISTINCT n.j1 FROM not_before n INNER JOIN master.judge j ON j.jcode = n.j1 WHERE n.diary_no IN ('$q_diary_no') AND n.notbef = 'N' AND j.is_retired = 'N'
                    UNION
                    SELECT distinct n.org_judge_id as j1 FROM (SELECT s.id FROM (SELECT s.id, sub_name1 FROM mul_category c, master.submaster s WHERE s.id = submaster_id AND diary_no IN ($q_diary_no) AND c.display = 'Y' AND s.display = 'Y') a INNER JOIN master.submaster s ON s.sub_name1 = a.sub_name1 WHERE flag = 's') a INNER JOIN master.ntl_judge_category n ON n.cat_id = a.id WHERE n.display = 'Y') aa);";*/

        $sql = "
        SELECT string_agg(DISTINCT j1::text, ',' ORDER BY j1::text) as j1
        FROM (
            SELECT distinct org_judge_id as j1 FROM advocate a
            INNER JOIN master.ntl_judge n ON n.org_advocate_id = a.advocate_id
            WHERE a.diary_no IN ($q_diary_no) AND n.display = 'Y' AND a.display = 'Y'
            UNION
            SELECT distinct n.org_judge_id as j1 FROM party a
            INNER JOIN master.ntl_judge_dept n ON a.deptcode = dept_id
            WHERE a.diary_no IN ($q_diary_no) AND a.pflag != 'T' AND n.display = 'Y'
            UNION
            SELECT distinct n.j1 FROM not_before n
            INNER JOIN master.judge j ON j.jcode = n.j1
            WHERE n.diary_no IN ('$q_diary_no') AND n.notbef = 'N' AND j.is_retired = 'N'
            UNION
            SELECT distinct n.org_judge_id as j1 FROM (SELECT s.id FROM (SELECT s.id, sub_name1 FROM mul_category c, master.submaster s WHERE s.id = submaster_id AND diary_no IN ($q_diary_no) AND c.display = 'Y' AND s.display = 'Y') a INNER JOIN master.submaster s ON s.sub_name1 = a.sub_name1 WHERE flag = 's') a
            INNER JOIN master.ntl_judge_category n ON n.cat_id = a.id WHERE n.display = 'Y'
        ) aa";
    }

    $result = $db->query($sql);
    $judges_code = '';
    if ($result->getNumRows() > 1 && $before_flag == 'B') {
        $judges_code = '-1';  // not to be listed anywhere
    } elseif ($result->getNumRows() > 0 && $before_flag == 'N') {
        // Fetch the results as an arraybefore_flag
        $rows = $result->getResultArray();
        foreach ($rows as $row) {
            $judges_code .= $row['j1'] . ',';
        }
        // Remove the last comma
        $judges_code = rtrim($judges_code, ',');
    } elseif ($result->getNumRows() == 1 && $before_flag == 'B') {
        $row = $result->getRowArray();
        $judges_code = $row['j1'];
    } else {
        $judges_code = '';
    }

    return $judges_code;
}

function check_list_before_save_advance_list($q_diary_no, $before_flag)
{
    $db = \Config\Database::connect();
    //$before_flag = 'N';
    if ($before_flag == 'B') {
        $sql = "
 SELECT DISTINCT STRING_AGG(n.j1::TEXT, ',' ORDER BY judge_seniority) AS j1
 FROM not_before n
 INNER JOIN master.judge j ON j.jcode = n.j1
 WHERE n.diary_no IN ('$q_diary_no')
 AND j.is_retired = 'N'
 AND n.notbef = '$before_flag'
 GROUP BY diary_no;
 ";
    }
    if ($before_flag == 'N') {


        $sql = "
 SELECT string_agg(DISTINCT j1::text, ',' ORDER BY j1::text) as j1
 FROM (
 SELECT distinct org_judge_id as j1 FROM advocate a
 INNER JOIN master.ntl_judge n ON n.org_advocate_id = a.advocate_id
 WHERE a.diary_no IN ($q_diary_no) AND n.display = 'Y' AND a.display = 'Y'
 UNION
 SELECT distinct n.org_judge_id as j1 FROM party a
 INNER JOIN master.ntl_judge_dept n ON a.deptcode = dept_id
 WHERE a.diary_no IN ($q_diary_no) AND a.pflag != 'T' AND n.display = 'Y'
 UNION
 SELECT distinct n.j1 FROM not_before n
 INNER JOIN master.judge j ON j.jcode = n.j1
 WHERE n.diary_no IN ('$q_diary_no') AND n.notbef = 'N' AND j.is_retired = 'N'
 UNION
 SELECT distinct n.org_judge_id as j1 FROM (SELECT s.id FROM (SELECT s.id, sub_name1 FROM mul_category c, master.submaster s WHERE s.id = submaster_id AND diary_no IN ($q_diary_no) AND c.display = 'Y' AND s.display = 'Y') a INNER JOIN master.submaster s ON s.sub_name1 = a.sub_name1 WHERE flag = 's') a
 INNER JOIN master.ntl_judge_category n ON n.cat_id = a.id WHERE n.display = 'Y'
 ) aa";
    }

    $result = $db->query($sql);
    $judges_code = '';
    if ($result->getNumRows() > 1 && $before_flag == 'B') {
        $judges_code = '-1'; // not to be listed anywhere
    } elseif ($result->getNumRows() > 0 && $before_flag == 'N') {
        // Fetch the results as an arraybefore_flag
        $rows = $result->getResultArray();
        foreach ($rows as $row) {
            $judges_code .= $row['j1'] . ',';
        }
        // Remove the last comma
        $judges_code = rtrim($judges_code, ',');
    } elseif ($result->getNumRows() == 1 && $before_flag == 'B') {
        $row = $result->getRowArray();
        $judges_code = $row['j1'];
    } else {
        $judges_code = '';
    }

    return $judges_code;
}



function check_list_before($q_diary_no, $before_flag)
{
    $db = \Config\Database::connect();
    //$before_flag = 'N';
    if ($before_flag == 'B') {
        $sql = "
            SELECT DISTINCT STRING_AGG(n.j1::TEXT, ',' ORDER BY judge_seniority) AS j1
            FROM not_before n
            INNER JOIN master.judge j ON j.jcode = n.j1
            WHERE n.diary_no IN ('$q_diary_no')
                AND j.is_retired = 'N'
                AND n.notbef = '$before_flag'
            GROUP BY diary_no;
        ";
    }
    if ($before_flag == 'N') {
        /*$sql1 = "SELECT STRING_AGG(j1::TEXT, ',' ORDER BY j1) AS j1
                    FROM (SELECT DISTINCT j1 FROM (SELECT DISTINCT org_judge_id AS j1 FROM advocate a INNER JOIN master.ntl_judge n ON n.org_advocate_id = a.advocate_id WHERE a.diary_no IN ($q_diary_no) AND n.display = 'Y' AND a.display = 'Y'
                    UNION
                    SELECT DISTINCT n.org_judge_id AS j1 FROM party a INNER JOIN master.ntl_judge_dept n ON a.deptcode = n.dept_id WHERE a.diary_no IN ($q_diary_no) AND a.pflag != 'T' AND n.display = 'Y'
                    UNION
                    SELECT DISTINCT n.j1 FROM not_before n INNER JOIN master.judge j ON j.jcode = n.j1 WHERE n.diary_no IN ('$q_diary_no') AND n.notbef = 'N' AND j.is_retired = 'N'
                    UNION
                    SELECT distinct n.org_judge_id as j1 FROM (SELECT s.id FROM (SELECT s.id, sub_name1 FROM mul_category c, master.submaster s WHERE s.id = submaster_id AND diary_no IN ($q_diary_no) AND c.display = 'Y' AND s.display = 'Y') a INNER JOIN master.submaster s ON s.sub_name1 = a.sub_name1 WHERE flag = 's') a INNER JOIN master.ntl_judge_category n ON n.cat_id = a.id WHERE n.display = 'Y') aa);";*/

        $sql = "
        SELECT string_agg(DISTINCT j1::text, ',' ORDER BY j1::text) as j1
        FROM (
            SELECT distinct org_judge_id as j1 FROM advocate a
            INNER JOIN master.ntl_judge n ON n.org_advocate_id = a.advocate_id
            WHERE a.diary_no IN ($q_diary_no) AND n.display = 'Y' AND a.display = 'Y'
            UNION
            SELECT distinct n.org_judge_id as j1 FROM party a
            INNER JOIN master.ntl_judge_dept n ON a.deptcode = dept_id
            WHERE a.diary_no IN ($q_diary_no) AND a.pflag != 'T' AND n.display = 'Y'
            UNION
            SELECT distinct n.j1 FROM not_before n
            INNER JOIN master.judge j ON j.jcode = n.j1
            WHERE n.diary_no IN ('$q_diary_no') AND n.notbef = 'N' AND j.is_retired = 'N'
            UNION
            SELECT distinct n.org_judge_id as j1 FROM (SELECT s.id FROM (SELECT s.id, sub_name1 FROM mul_category c, master.submaster s WHERE s.id = submaster_id AND diary_no IN ($q_diary_no) AND c.display = 'Y' AND s.display = 'Y') a INNER JOIN master.submaster s ON s.sub_name1 = a.sub_name1 WHERE flag = 's') a
            INNER JOIN master.ntl_judge_category n ON n.cat_id = a.id WHERE n.display = 'Y'
        ) aa";
    }

    $result = $db->query($sql);
    $judges_code = '';
    if ($result->getNumRows() > 1 && $before_flag == 'B') {
        $judges_code = '-1';  // not to be listed anywhere
    } elseif ($result->getNumRows() > 0 && $before_flag == 'N') {
        // Fetch the results as an arraybefore_flag
        $rows = $result->getResultArray();
        foreach ($rows as $row) {
            $judges_code .= $row['j1'] . ',';
        }
        // Remove the last comma
        $judges_code = rtrim($judges_code, ',');
    } elseif ($result->getNumRows() == 1 && $before_flag == 'B') {
        $row = $result->getRowArray();
        $judges_code = $row['j1'];
    } else {
        $judges_code = '';
    }

    return $judges_code;
}

function f_advance_cl_allocation_bk($q_diary_no, $q_conn_key, $q_next_dt, $subhead, $board_type, $q_clno, $q_j1, $q_j2, $q_j3, $q_listorder, $q_usercode, $q_main_supp_flag): int
{
    $result = 0;
    $q_brd_slno = 1;
    $db = \Config\Database::connect();
    $builder = $db->table('advance_allocated');
    $builder->selectMax('brd_slno', 'max_brd_slno');
    $builder->where('next_dt', $q_next_dt);
    $query = $builder->get();

    if ($row = $query->getRow()) {
        $q_brd_slno = $row->max_brd_slno + 1;
    }

    $data = [
        'diary_no' => $q_diary_no,
        'conn_key' => $q_conn_key,
        'next_dt' => $q_next_dt,
        'subhead' => $subhead,
        'board_type' => $board_type,
        'clno' => $q_clno,
        'brd_slno' => $q_brd_slno,
        'j1' => $q_j1,
        'j2' => $q_j2,
        'j3' => $q_j3,
        'listorder' => $q_listorder,
        'usercode' => $q_usercode,
        'ent_dt' => date('Y-m-d H:i:s'),
        'main_supp_flag' => $q_main_supp_flag,
    ];

    $builder = $db->table('advance_allocated');
    $builder->insert($data);
    $afros = $db->affectedRows();

    if ($afros > 0) {
        if ($q_diary_no == $q_conn_key) {
            $builder = $db->table('advance_allocated h');
            $builder->select("c.diary_no AS conc_diary_no, m.conn_key, '$q_next_dt' as next_dt, '$subhead' as subhead, '$board_type' AS board_type, '$q_clno' as clno, '$q_brd_slno' AS brd_slno, '$q_j1' AS j1, '$q_j2' AS j2, '$q_j3' AS j3, '$q_listorder' as listorder, '$q_usercode' as usercode ,NOW() as enttime,'$q_main_supp_flag' as main_supp_flag");
            $builder->join('main m', 'm.diary_no = h.diary_no');
            $builder->join('conct c', 'c.conn_key = m.conn_key');
            $builder->where('c.list', 'Y');
            $builder->where('m.c_status', 'P');
            $builder->where('m.conn_key', $q_conn_key);
            $builder->where('h.next_dt', $q_next_dt);
            $builder->where('h.board_type', 'J');
            $builder->where('h.clno >', 0);
            $builder->where('h.brd_slno >', 0);

            $subQuery = $db->table('main m');
            $subQuery->select('m.diary_no');
            $subQuery->join('heardt h', 'm.diary_no = h.diary_no');
            $subQuery->where('m.c_status', 'P');
            $subQuery->where('h.next_dt !=', '0000-00-00');
            $subQuery->where('m.diary_no !=', $q_conn_key);
            $builder->whereIn('c.diary_no', $subQuery);

            $selectQuery = $builder->getCompiledSelect();

            $insertQuery = "INSERT IGNORE INTO advance_allocated (diary_no,conn_key,next_dt,subhead,board_type,clno,brd_slno,j1,j2,j3,listorder,usercode,ent_dt,main_supp_flag) " . $selectQuery;

            $db->query($insertQuery);
        }
        $result = 1;
    }

    return $result;
}

function f_advance_cl_allocation($q_diary_no, $q_conn_key, $q_next_dt, $subhead, $board_type, $q_clno, $q_j1, $q_j2, $q_j3, $q_listorder, $q_usercode, $q_main_supp_flag)
{
    $db = \Config\Database::connect();

    $result = 0;
    $q_brd_slno = 1;
    if($q_conn_key == null && $q_conn_key == ''){
        $q_conn_key = 0;
    }
    $sql_m = "SELECT MAX(brd_slno) AS max_brd_slno 
                FROM advance_allocated 
                WHERE next_dt = '$q_next_dt' ";


    $res_m = $db->query($sql_m);


    if ($res_m->getNumRows() > 0) {
        $rs = $res_m->getRowArray();
        $q_brd_slno = $rs['max_brd_slno'] + 1;
    }



    $sql = "INSERT INTO advance_allocated (diary_no,conn_key,next_dt,subhead,board_type,clno,brd_slno,j1,j2,j3,listorder,usercode,ent_dt,main_supp_flag) 
            VALUES ('$q_diary_no','$q_conn_key','$q_next_dt','$subhead','$board_type','$q_clno','$q_brd_slno','$q_j1','$q_j2','$q_j3','$q_listorder',
            '$q_usercode',NOW(),'$q_main_supp_flag')";



    $res = $db->query($sql);
    $res = $db->affectedRows();
    if ($res > 0) {
        if ($q_diary_no == $q_conn_key) {
           
            $sql2 = "INSERT  INTO advance_allocated (diary_no,conn_key,next_dt,subhead,board_type,clno,brd_slno,j1,j2,j3,listorder,usercode,ent_dt,main_supp_flag)
            SELECT a.* FROM (SELECT distinct c.diary_no AS conc_diary_no,
                m.conn_key::int, '$q_next_dt'::date as next_dt, '$subhead'::int as subhead, '$board_type' AS board_type, '$q_clno'::int as clno, '$q_brd_slno'::int AS brd_slno, '$q_j1'::int AS j1, 
                '$q_j2'::int AS j2, '$q_j3'::int AS j3, '$q_listorder'::int as listorder, '$q_usercode'::int as usercode ,NOW() as enttime,'$q_main_supp_flag'::int as main_supp_flag
                FROM advance_allocated h 
                INNER JOIN main m ON m.diary_no = CAST(h.diary_no AS bigint) 
            INNER JOIN conct c ON c.conn_key = CAST(m.conn_key AS bigint) 
                WHERE c.list = 'Y' and m.c_status = 'P' 
                        AND m.conn_key = '$q_conn_key'            
                        AND h.next_dt = '$q_next_dt'
                        AND h.board_type = 'J'
                        AND h.clno > 0
                        AND h.brd_slno > 0
                ) a
            INNER JOIN main m ON a.conc_diary_no = m.diary_no
            INNER JOIN heardt h ON a.conc_diary_no = h.diary_no
            WHERE m.c_status = 'P' and h.next_dt IS NOT NULL  and a.conc_diary_no != CAST(a.conn_key AS bigint) ";

            /*$sql2 = "INSERT  INTO advance_allocated (diary_no,conn_key,next_dt,subhead,board_type,clno,brd_slno,j1,j2,j3,listorder,usercode,ent_dt,main_supp_flag)
        SELECT a.* FROM (SELECT distinct c.diary_no AS conc_diary_no,
            m.conn_key, '$q_next_dt' as next_dt, '$subhead' as subhead, '$board_type' AS board_type, '$q_clno' as clno, '$q_brd_slno' AS brd_slno, '$q_j1' AS j1, 
            '$q_j2' AS j2, '$q_j3' AS j3, '$q_listorder' as listorder, '$q_usercode' as usercode ,NOW() as enttime,'$q_main_supp_flag' as main_supp_flag
            FROM advance_allocated h 
            INNER JOIN main m ON m.diary_no = CAST(h.diary_no AS bigint) 
            INNER JOIN conct c ON c.conn_key = CAST(m.conn_key AS bigint) 
            WHERE c.list = 'Y' and m.c_status = 'P' 
                    AND m.conn_key = '$q_conn_key'            
                    AND h.next_dt = '$q_next_dt'
                    AND h.board_type = 'J'
                    AND h.clno > 0
                    AND h.brd_slno > 0
            ) a
        INNER JOIN main m ON a.conc_diary_no = m.diary_no
        INNER JOIN heardt h ON a.conc_diary_no = h.diary_no
        WHERE m.c_status = 'P' and h.next_dt IS NOT NULL  and a.conc_diary_no != CAST(a.conn_key AS bigint) ";*/
            $res_m = $db->query($sql2);
        }


        $result = 1;
    }
    return $result;
}

function msort($array, $key, $sort_flags = SORT_REGULAR)
{

    if (is_array($array) && count($array) > 0) {
        if (!empty($key)) {
            $mapping = array();
            foreach ($array as $k => $v) {
                $sort_key = '';
                if (!is_array($key)) {
                    $sort_key = $v[$key];
                } else {
                    foreach ($key as $key_key) {
                        $sort_key .= $v[$key_key];
                    }
                    $sort_flags = SORT_STRING;
                }
                $mapping[$k] = $sort_key;
            }
            asort($mapping, $sort_flags);
            $sorted = array();
            foreach ($mapping as $k => $v) {
                $sorted[] = $array[$k];
            }
            return $sorted;
        }
    }
    return $array;
}

function findInMultiDimensionalArray($products, $field, $value)
{
    foreach ($products as $key => $product) {
        if ($product[$field] === $value)
            return $key;
    }
    return false;
}

function get_report_limit($rd, $mf, $rur, $ct, $fdt, $tdt, $fst, $inc_val)
{
    $db = \Config\Database::connect();
    $usercode = session()->get('login')['usercode'];
    $criteria = "";
    if ($rd == 'R') {
        $criteria = " DATE(t1.rece_dt) BETWEEN '" . $fdt . "' AND '" . $tdt . "' ";
        $criteria .= " and t1.rece_by=" . $usercode;
        $rdt = "RECEIVED";
    }
    if ($rd == 'D') {
        $criteria = " DATE(t1.disp_dt) BETWEEN '" . $fdt . "' AND '" . $tdt . "' ";
        $criteria .= " and t1.disp_by=" . $usercode;
        $rdt = "DISPATCHED";
    }

    if ($mf == 'M' and $rur != 'U') {
        $criteria .= " and m.mf_active='M'";
    }
    if ($mf == 'F' and $rur != 'U') {
        $criteria .= " and m.mf_active='F'";
    }

    if ($rur == 'R') {
        $criteria .= " and m.fil_no!=''";
    }
    if ($rur == 'U') {
        $criteria .= " and (m.fil_no is NULL or m.fil_no='')";
    }

    if ($ct != 'all' and $rur != 'U') {
        $criteria .= " and m.active_casetype_id=" . $ct;
    }

    if ($rd == 'R') {
        /*$criteria .= " order by rece_dt asc, dcs.diary_no";*/
        $criteria .= " order by t1.rece_dt asc";
    }

    if ($rd == 'D') {
        /*$criteria .= " order by disp_dt asc, dcs.diary_no";*/
        $criteria .= " order by t1.disp_dt asc";
    }

    /*if($_REQUEST['nw_hd_fst'] != '' and $_REQUEST['nw_hd_fst'] != '' and $_REQUEST['u_t'] == '1')
    {
        $fst = intval($_REQUEST['nw_hd_fst']);
        $inc_val = intval($_REQUEST['inc_val']);
    }*/

    if (isset($_REQUEST['nw_hd_fst']) && !empty($_REQUEST['nw_hd_fst']) && isset($_REQUEST['u_t']) && $_REQUEST['u_t'] == '1') {
        $fst = intval($_REQUEST['nw_hd_fst']);
        $inc_val = intval($_REQUEST['inc_val']);
    }


    $ucode1 = get_user_details1($usercode);
    $output = "";

    $sql = "SELECT *
    FROM (
        SELECT DISTINCT
            t1.id,
            t1.diary_copy_set,
            t1.disp_by,
            t1.disp_to,
            t1.disp_dt,
            t1.rece_by,
            t1.rece_dt,
            t1.c_l,
            t1.remark,
            t1.flag,
            m.diary_no,
            dcs.copy_set
        FROM diary_movement t1
        INNER JOIN diary_copy_set dcs ON dcs.id = t1.diary_copy_set
        INNER JOIN main m ON m.diary_no = dcs.diary_no
        WHERE $criteria
        LIMIT $inc_val OFFSET $fst
    ) t2";

    $query = $db->query($sql);
    if ($query->getNumRows() >= 1) {
        $result = $query->getResultArray();
        $sn = 0;
        if (isset($_REQUEST['u_t']) && $_REQUEST['u_t'] == 0) {
            $sn = 0;
        } else if (isset($_REQUEST['u_t']) && $_REQUEST['u_t'] == 1) {
            $sn = $_REQUEST['inc_tot_pg'];
        }
        $output .= "<br><p align=center><b>CASES/PAPER BOOK " . $rdt . " BY " . $ucode1 . "</b></p>";
        $output .= "<table class='table_tr_th_w_clr c_vertical_align' border=1><tr><th><b>S.No.</b></th><th><b>Diary and Case No.</b></th><th><b>Set</b></th><th><b>Dispatch by</b></th><th><b>Dispatch on</b></th><th><b>Received By</b></th><th><b>Received on</b></th></tr>";
        foreach ($result as $key => $row) {
            $dn = get_real_diaryno($row['diary_no']);
            $dn .= "<br>" . get_casenos_comma($row['diary_no']);

            $dby = "";
            if ($row['disp_by'] != 0) {
                $dby = get_user_details1($row['disp_by']);
            }
            $don = "";
            //if ($row['disp_dt'] != '0000-00-00 00:00:00') {
            if (!empty($row['disp_dt'])) {
                $don = date("d-m-Y h:i:s A", strtotime($row['disp_dt']));
            }
            $rby = "";
            if ($row['rece_by'] != 0) {
                $rby = get_user_details1($row['rece_by']);
            }
            $ron = "";
            //if ($row['rece_dt'] != '0000-00-00 00:00:00') {
            if (!empty($row['rece_dt'])) {
                $ron = date("d-m-Y h:i:s A", strtotime($row['rece_dt']));
            }
            $output .= "<tr><td>" . ++$sn . "</td><td>" . $dn . "</td><td>" . $row['copy_set'] . "</td><td>" . $dby . "</td><td>" . $don . "</td><td>" . $rby . "</td><td>" . $ron . "</th></tr>";
        }
        $output .= "</table>";
        $output .= "<input type='hidden' name='inc_tot_pg' id='inc_tot_pg' value=" . $sn . " />";
    }
    echo $output;
}

function get_user_details1($usercode)
{
    $user = "";
    $db = \Config\Database::connect();
    $sql_user = "SELECT a.usercode, a.name, a.empid, a.service, a.udept, a.section, a.usertype, a.log_in, a.jcode, a.attend,
    b.dept_name, c.section_name, d.type_name, a.entdt, c.isda
    FROM master.users a
    LEFT JOIN master.userdept b ON a.udept = b.id
    LEFT JOIN master.usersection c ON a.section = c.id
    LEFT JOIN master.usertype d ON a.usertype = d.id
    WHERE a.usercode = $usercode";

    $query = $db->query($sql_user);

    if ($query->getNumRows() > 0) {
        $row = $query->getRowArray();
        $isda = ($row['isda'] == "Y") ? " [DA]" : "";
        $user = "<font color=blue>" . htmlspecialchars($row['name']) . "</font>, <font color=green>" . htmlspecialchars($row['section_name'] . $isda) . "</font>";
        return $user;
    }
    return $user;
}



function is_data_from_table($table, $condition = null, $column_names = '*', $row = 'A')
{
    $db = \Config\Database::connect();
    $builder = $db->table($table);
    if (!empty($condition) && $condition != null) {
        $query = $builder->select($column_names)->where($condition)->get();
    } else {
        $query = $builder->select($column_names)->get();
    }

    // echo $db->getLastQuery();
    // exit;


    if ($query->getNumRows() >= 1) {

        if ($row == 'A') {
            return $query->getResultArray();
        } elseif ($row == 'N') {
            return $query->getNumRows();
        } elseif ($row == 'Q') {
            return $db->getLastQuery();
        } else {
            return $query->getRowArray();
        }
    } elseif ($query->getNumRows() == 0) {
        if ($row == 'Q') {
            return $db->getLastQuery();
        } else {
            return false;
        }
    } else {
        return false; // Explicitly return false if no rows are found
    }
}

function is_data_from_table1($table, $condition = null, $column_names = '*', $row = 'A')
{
    $db = \Config\Database::connect();
    $builder = $db->table($table);
    if (!empty($condition) && $condition != null) {
        $query = $builder->select($column_names)->where($condition)->get();
    } else {
        $query = $builder->select($column_names)->get();
    }

    echo $db->getLastQuery();
    exit;


    if ($query->getNumRows() >= 1) {

        if ($row == 'A') {
            return $query->getResultArray();
        } elseif ($row == 'N') {
            return $query->getNumRows();
        } elseif ($row == 'Q') {
            return $db->getLastQuery();
        } else {
            return $query->getRowArray();
        }
    } elseif ($query->getNumRows() == 0) {
        if ($row == 'Q') {
            return $db->getLastQuery();
        } else {
            return false;
        }
    } else {
        return false; // Explicitly return false if no rows are found
    }
}






function unique_multidim_array($array, $key)
{
    $temp_array = array();
    $i = 0;
    $key_array = array();
    foreach ($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}
function exists_from_table($table, $col_name1, $col_str1, $col_name2 = null, $col_str2 = null, $column_names = '*')
{
    if (!empty($col_name2) && !empty($col_str2) != null) {
        $lower = strtolower($col_str1);
        $lower2 = strtolower($col_str2);
        for ($x = 1; $x <= 5; $x++) {
            if ($x == 1) {
                $text = $lower;
                $text2 = $lower2;
            } elseif ($x == 2) {
                $text = strtoupper($lower);
                $text2 = strtoupper($lower2);
            } elseif ($x == 3) {
                $text = ucwords($lower);
                $text2 = ucwords($lower2);
            } elseif ($x == 4) {
                $text = ucfirst($lower);
                $text2 = ucfirst($lower2);
            } elseif ($x == 5) {
                $text = lcfirst($lower);
                $text2 = lcfirst($lower2);
            }
            $query = is_data_from_table($table, [$col_name1 => $text, $col_name2 => $text2, 'is_deleted' => false], $column_names);
            if ($query && !empty($query) && $query != false) {
                return $query;
            }
        }
    } elseif (!empty($col_name1) && $col_name1 != null) {
        $lower = strtolower($col_str1);
        for ($x = 1; $x <= 5; $x++) {
            if ($x == 1) {
                $text = $lower;
            } elseif ($x == 2) {
                $text = strtoupper($lower);
            } elseif ($x == 3) {
                $text = ucwords($lower);
            } elseif ($x == 4) {
                $text = ucfirst($lower);
            } elseif ($x == 5) {
                $text = lcfirst($lower);
            }
            $query = is_data_from_table($table, [$col_name1 => $text, 'is_deleted' => false], $column_names);
            if ($query && !empty($query) && $query != false) {
                return $query;
            }
        }
    } else {
        $query = is_data_from_table($table, '', $column_names);
    }
    if (!empty($query) && count($query) >= 1) {
        return $query;
    } else {
        return false;
    }
}

if (!function_exists('generateRandomString')) {
    function generateRandomString($length = 16)
    {

        // generates random string for login salt

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
if (!function_exists('get_diff_two_date')) {
    function get_diff_two_date($date1, $date2)
    {

        // Declare and define two dates
        //$date1 = strtotime("2016-06-01 22:45:00");
        // $date2 = strtotime("2018-09-21 10:44:01");
        $date1 = strtotime($date1);
        $date2 = strtotime($date2);

        // Formulate the Difference between two dates
        $diff = abs($date2 - $date1);

        // To get the year divide the resultant date into
        // total seconds in a year (365*60*60*24)
        $years = floor($diff / (365 * 60 * 60 * 24));

        // To get the month, subtract it with years and
        // divide the resultant date into
        // total seconds in a month (30*60*60*24)
        $months = floor(($diff - $years * 365 * 60 * 60 * 24)
            / (30 * 60 * 60 * 24));

        // To get the day, subtract it with years and
        // months and divide the resultant date into
        // total seconds in a days (60*60*24)
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 -
            $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

        // To get the hour, subtract it with years,
        // months & seconds and divide the resultant
        // date into total seconds in a hours (60*60)
        $hours = floor(($diff - $years * 365 * 60 * 60 * 24
            - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24)
            / (60 * 60));

        // To get the minutes, subtract it with years,
        // months, seconds and hours and divide the
        // resultant date into total seconds i.e. 60
        $minutes = floor(($diff - $years * 365 * 60 * 60 * 24
            - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24
            - $hours * 60 * 60) / 60);

        // To get the minutes, subtract it with years,
        // months, seconds, hours and minutes
        $seconds = floor(($diff - $years * 365 * 60 * 60 * 24
            - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24
            - $hours * 60 * 60 - $minutes * 60));
        $totalMinutes = ($hours * 60) + ($minutes);
        return $result = ['years' => $years, 'months' => $months, 'days' => $days, 'hours' => $hours, 'minutes' => $minutes, 'seconds' => $seconds, 'totalMinutes' => $totalMinutes];
        // Print the result
        /*printf("%d years, %d months, %d days, %d hours, "
             . "%d minutes, %d seconds", $years, $months,
                     $days, $hours, $minutes, $seconds);*/
    }
}

// Code Starts Here By- P.S

function get_advocate_mobile_number($diary_no, $party_type)
{
    $mobile = '';
    $db = \Config\Database::connect();
    $builder = $db->table("advocate a", false);
    $builder->select("mobile", false);
    $builder->join('master.bar b ', 'a.advocate_id=b.bar_id', 'inner', false);
    $builder->where('a.diary_no',  $diary_no)->where('a.display', 'Y')->where('a.pet_res', $party_type);
    $query2 = $builder->get();

    if ($query2->getNumRows() > 0) {
        //        echo "FDSAF";
        $result = $query2->getResultArray();
        //        echo $db->getLastQuery();exit;
        foreach ($result as $row) {
            if ($row['mobile'] != '' && strlen($row['mobile']) == '10') {
                if ($mobile == '') {
                    $mobile = $row['mobile'];
                    return $mobile;
                } else {
                    $mobile = $mobile . ',' . $row['mobile'];
                    return $mobile;
                }
            }
        }
    }
}

function get_party_mobile_number($diary_no, $party_type)
{
    $mobile = '';
    $db = \Config\Database::connect();
    $builder = $db->table('party');
    $builder->select("contact");
    $builder->where('diary_no', $diary_no)->where('pet_res', $party_type);
    $query = $builder->get();

    if ($query->getNumRows() > 0) {
        $result = $query->getResultArray();
        foreach ($result as $r_party) {
            if ($r_party['contact'] != '' && strlen($r_party['contact']) == '10') {
                if ($mobile == '') {
                    $mobile = $r_party['contact'];
                    return $mobile;
                } else {
                    $mobile = $mobile . ',' . $r_party['contact'];
                    return $mobile;
                }
            }
        }
    }
}



function component_case_status_process_popup($diary_no = '')
{
    $html = "";
    $data = getCaseDetails($diary_no);
    $data['component'] = 'component_for_case_status_process_popup';
    $html = view('Common/Component/case_status/case_status_process', $data);
    return $html;
}


function component_earlier_court_tab($diary_no = '')
{
    $html = "";
    $data = getEarlierCourtData($diary_no);
    $data['component'] = 'component_for_earlier_court';
    $html = view('Common/Component/case_status/get_earlier_court', $data);
    return $html;
}




function get_main_case($main_diary_number, $flag)
{
    $main_case = "";
    $db = \Config\Database::connect();

    $builder1 = $db->table("main" . $flag);
    $builder1->select("conn_key,active_casetype_id,casetype_id,active_fil_no,active_reg_year,diary_no");
    $builder1->where('diary_no', $main_diary_number);
    $builder1->where('conn_key is not null');
    $builder1->where('conn_key !=', '');
    $query = $builder1->get(1);


    $outer_array = array();
    if ($query->getNumRows() >= 1) {

        $main = $query->getRowArray();

        $active_casetype_id = $main['active_casetype_id'];
        $casetype_id = $main['casetype_id'];

        if (empty($active_casetype_id)) {
            $case_code = $casetype_id;
        } else {
            $case_code = $active_casetype_id;
        }

        $case_type_details = is_data_from_table('master.casetype', ['casecode' => $case_code], '*', 'R');

        if (!empty($case_type_details)) {
            $res = $case_type_details;

            if ($main['active_fil_no'] != '' && $main['active_fil_no'] != null) {
                $main_case = $res['short_description'] . " " . substr($main['active_fil_no'], 3) . "/" . $main['active_reg_year'];
            } else {
                $main_case = $res['short_description'] . " Diary no. " . substr($main['diary_no'], 0, strlen($main['diary_no']) - 4) . "/" . substr($main['diary_no'], -4);
            }
        }
    }

    return $main_case;
}

function send_sms($mobile, $message, $from, $templateId)
{

    if (empty($mobile)) {
        return " Mobile No. Empty.";
    } else if (empty($message)) {
        return " Message content Empty.";
    } else if (strlen($message) > 320) {
        return " Message length should be less than 320 characters.";
    } else if (empty($from)) {
        return " Sender Information Empty, contact to server room.";
    } else if (strlen($mobile) != '10') {
        return " Not a Proper Mobile No.";
    } else {
        $db = \Config\Database::connect();
        $fromAddress = trim($from);
        $smsLength = explode(",", trim($mobile));
        $count_sms = count($smsLength);
        $srno = 1;


        for ($i = 0; $i < $count_sms; $i++) {
            echo "<br/>";
            if (strlen(trim($smsLength[$i])) != '10') {
                return " " . $srno++ . " " . $smsLength[$i] . " Not a proper mobile number. \n";
            } else if (!is_numeric($smsLength[$i])) {
                //not a numeric value
                return " " . $srno++ . " " . $smsLength[$i] . " Mobile number contains invalid value. \n";
            } else {
                //header('Content-type: application/json;');
                $mm = trim($smsLength[$i]);
                $homepage = file_get_contents('http://10.2/eAdminSCI/a-push-sms-gw?mobileNos=' . $mm . '&message=' . urlencode($message) . '&typeId=29&myUserId=NIC001001&myAccessId=root&authCode=' . SMS_KEY . '&templateId=' . $templateId);

                $json = json_decode($homepage);
                if ($json->{'responseFlag'} == "success") {
                    //                    $sql = "INSERT INTO sms_pool (mobile,msg,table_name,c_status,ent_time,update_time, template_id) VALUES ('$mm','$cnt','$frm_adr','Y',NOW(),Now(),'$templateId')";
                    //                    mysql_query($sql) or die(mysql_errno());

                    $columnsData = array(
                        'mobile' => $mobile,
                        'msg' => $message,
                        'table_name' => $from,
                        'c_status' => 'Y',
                        'ent_time' => date("Y-m-d H:i:s"),
                        'template_id' => $templateId,
                        'create_modify' => date("Y-m-d H:i:s"),
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP()
                    );

                    $builder = $db->table('sms_pool');
                    $query1 = $builder->insert($columnsData);
                    return "Message Sent Successfully ";
                    //                    echo "  ".$srno++."  ".$smsLength[$i]."Success. SMS Sent \n";

                } else {
                    //                    $sql = "INSERT INTO sms_pool (mobile,msg,table_name,c_status,ent_time, template_id) VALUES ('$mm','$cnt','$frm_adr','N',NOW(),'$template_id')";
                    //                    mysql_query($sql) or die(mysql_errno());
                    $columnsData = array(
                        'mobile' => $mobile,
                        'msg' => $message,
                        'table_name' => $from,
                        'c_status' => 'N',
                        'ent_time' => date("Y-m-d H:i:s"),
                        'template_id' => $templateId,
                        'create_modify' => date("Y-m-d H:i:s"),
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP()
                    );

                    $builder = $db->table('sms_pool');
                    $query1 = $builder->insert($columnsData);

                    return "Error Message Not Sent ";

                    //                    echo " ".$srno++." ".$smsLength[$i]." Error:Not Sent, SMS may send later. \n";
                }
            }
        }
    }
}

function is_table_a($table_name)
{
    if (isset($_SESSION['filing_details'])) {
        $c_status = session()->get('filing_details')['c_status'];
        if (!empty($c_status) && $c_status == 'D') {
            return $table_name . '_a';
        }
    }
    return $table_name;
}

function delete($table_name, $condition)
{
    $db = \Config\Database::connect();
    $builder = $db->table($table_name);
    if ($builder->delete($condition)) {
        return true;
    } else {
        return false;
    }
}
function allot_to_EC($diary_no, $ucode, $fil_type = null)
{
    $db = \Config\Database::connect();
    $today = date('Y-m-d');
    $current_date = date("Y-m-d");
    $current_date_time = date("Y-m-d H:i:s");
    $user_availability = '';
    $check_marking = is_data_from_table('mark_all_for_hc', ['display' => 'Y']);
    if ($check_marking && !empty($check_marking)) {
        $check_qr = is_data_from_table('master.random_user_hc', ['ent_date' => $today], 'empid', 'R');
        if (!empty($check_qr) && $check_qr) {
            $empid = $check_qr['empid'];
            $assign_to = explode('~', $check_qr['empid']);
            $check_ava_row['to_userno'] = $assign_to[0];
            $check_ava_row['to_name'] = $assign_to[1];
            $delete_empid = delete('master.random_user_hc', ['empid' => $empid, 'ent_date' => $today]);
        } else {
            $check_if_EC_ava = get_fil_trap_users_empid(102);
            if (!empty($check_if_EC_ava) && $check_if_EC_ava) {
                $empid = array();
                foreach ($check_if_EC_ava as $row) {
                    array_push($empid, $row['empid']);
                }
                shuffle($empid);
                for ($i = 0; $i < sizeof($empid); $i++) {
                    $assign_to = explode('~', $empid[0]);
                    $check_ava_row['to_userno'] = $assign_to[0];
                    $check_ava_row['to_name'] = $assign_to[1];
                    if ($i > 0) {
                        $insert_random_user_hc = [
                            'empid' => $empid[$i],
                            'ent_date' => $today,

                            'create_modify' => date("Y-m-d H:i:s"),
                            'updated_by' => $_SESSION['login']['usercode'],
                            'updated_by_ip' => getClientIP(),
                        ];
                        $is_insert_random_user_hc = insert('master.random_user_hc', $insert_random_user_hc);
                    }
                }
            }
        }
    } else {
        $check_ava_row = array();
        $condition = "and a.user_type='$fil_type'";
        $check_if_EC_ava = get_fil_trap_users_empid(102, $fil_type);

        if (empty($check_if_EC_ava)) {
            if ($fil_type == 'P') {
                $fil_type = 'E';
                $user_availability = " [Counter-Filing Users not available, Marked to E-Filing User] ";
            } else {
                $fil_type = 'P';
                $user_availability = " [E-Filing Users not available, Marked to Counter-Filing User] ";
            }
        }
        if (!empty($check_if_EC_ava) && $check_if_EC_ava) {
            $assign_to = explode('~', $check_if_EC_ava[0]['empid']);
            $first_row['empid'] = $assign_to[0];
            $first_row['name'] = $assign_to[1];

            $check_ava_row = get_fil_trap_users_with_fil_trap_seq($fil_type, '102', 'DE', 'R');

            if (empty($check_ava_row)) {
                $check_ava_row['to_userno'] = $first_row['empid'];
                $check_ava_row['to_name'] = $first_row['name'];
            } else {
                if (!empty($check_ava_row)) {
                    if ($check_ava_row['to_usercode'] == NULL) {
                        $check_ava_row['to_userno'] = $first_row['empid'];
                        $check_ava_row['to_name'] = $first_row['name'];
                    }
                }
            }
        }
    }

    $select_for_deleted_filno = is_data_from_table('fil_trap', ['diary_no' => $diary_no], 'diary_no');
    if ($select_for_deleted_filno && !empty($select_for_deleted_filno)) {
        $update_casemove = delete('fil_trap', ['diary_no' => $diary_no]);
    }

    $select_for_deleted_filno_his = is_data_from_table('fil_trap_his', ['diary_no' => $diary_no], 'diary_no');
    if ($select_for_deleted_filno_his && !empty($select_for_deleted_filno_his)) {
        $update_casemove_his = delete('fil_trap_his', ['diary_no' => $diary_no]);
    }
    $insert_then = [
        'diary_no' => $diary_no,
        'd_to_empid' => isset($check_ava_row['to_userno']) ? $check_ava_row['to_userno'] : 0,
        'disp_dt' => $current_date_time,
        'd_by_empid' => $_SESSION['login']['empid'],
        'remarks' => 'FIL -> DE',
        'r_by_empid' => 0,
        'other' => 0,

        'create_modify' => date("Y-m-d H:i:s"),
        'updated_by' => $_SESSION['login']['usercode'],
        'updated_by_ip' => getClientIP(),
    ];
    $insert_then_result = insert('fil_trap', $insert_then);

    $check_fil_trap_seq = is_data_from_table('fil_trap_seq', ['ddate' => $current_date, 'utype' => 'DE', 'user_type' => $fil_type], 'id');
    if (empty($check_fil_trap_seq)) {
        $insert_query = [
            'ddate' => $current_date_time,
            'utype' => 'DE',
            'no' => isset($check_ava_row['to_userno']) ? $check_ava_row['to_userno'] : 0,
            'user_type' => $fil_type,
            'ctype' => 0,
            'create_modify' => date("Y-m-d H:i:s"),
            'updated_by' => $_SESSION['login']['usercode'],
            'updated_by_ip' => getClientIP(),
        ];
        $insert_query_result = insert('fil_trap_seq', $insert_query);
    } else {
        $update_query = [
            'no' => isset($check_ava_row['to_userno']) ? $check_ava_row['to_userno'] : 0,

            'updated_on' => date("Y-m-d H:i:s"),
            'updated_by' => $_SESSION['login']['usercode'],
            'updated_by_ip' => getClientIP(),
        ];
        $update_condition = ['ddate' => $current_date, 'utype' => 'DE', 'user_type' => 'P'];
        $update_query_result = update('fil_trap_seq', $update_query, $update_condition);
    }
    $final_response = '';
    if (!empty($check_ava_row)) {
        if ($user_availability != '') {
            $final_response = $check_ava_row['to_userno'] . '~' . $check_ava_row['to_name'] . '~' . $user_availability;
        } else {
            $final_response = $check_ava_row['to_userno'] . '~' . $check_ava_row['to_name'];
        }
    }
    return $final_response;
}
function get_fil_trap_users_empid($usertype, $user_type = null, $row = 'A')
{
    $db = \Config\Database::connect();
    $builder = $db->table('fil_trap_users a');
    $builder->select("CONCAT(b.empid, '~', b.name) as empid");
    $builder->join('master.users b', 'a.usercode = b.usercode');
    $builder->Join('master.specific_role s', "a.usercode = s.usercode AND s.display = 'Y' AND s.flag = 'P'", 'left');
    $builder->where('s.id', null);
    $builder->where('a.usertype', $usertype);
    if (!empty($user_type) && $user_type != null) {
        $builder->where('a.user_type', $user_type);
    }
    $builder->where('a.display', 'Y');
    $builder->where('b.display', 'Y');
    $builder->where('b.attend', 'P');
    $builder->orderBy('empid');
    $query = $builder->get();
    if ($query->getNumRows() >= 1) {
        if ($row == 'A') {
            $result = $query->getResultArray();
        } else {
            $result = $query->getRowArray();
        }
        return $result;
    } else {
        return [];
    }
}
function get_fil_trap_users_with_fil_trap_seq($fil_type, $usertype, $utype, $row = 'A')
{
    $db = \Config\Database::connect();
    $current_date = date("Y-m-d");
    $query = $db->table('fil_trap_users a')
        ->select('a.usercode as to_usercode, b.name as to_name, b.empid as to_userno, c.ddate, c.no as curno')
        ->join('master.users b', 'a.usercode = b.usercode')
        ->join('fil_trap_seq c', 'c.no < b.empid', 'left')
        ->where('a.usertype', $usertype)
        ->where('a.display', 'Y')
        ->where('b.display', 'Y')
        ->where('b.attend', 'P')
        ->where('c.utype', $utype)
        ->where('c.ddate', $current_date)
        ->where('a.user_type', $fil_type)
        ->where('c.user_type', $fil_type)
        ->orderBy('to_userno')
        ->get();
    if ($query->getNumRows() >= 1) {
        if ($row == 'A') {
            $result = $query->getResultArray();
        } else {
            $result = $query->getRowArray();
        }
        return $result;
    } else {
        return [];
    }
}
function check_duplicate_token($t)
{
    $db = \Config\Database::connect();
    $duplicate = 0;
    $builder = $db->table('fil_trap');
    $builder->select('token_no')
        ->where('DATE(disp_dt) = CURRENT_DATE')
        ->where('token_no', $t)
        ->where("(remarks='AOR -> FDR')");

    $builder->orWhere(function ($builder) use ($t) {
        $builder->table('fil_trap_his')
            ->select('token_no')
            ->where('DATE(disp_dt) = CURRENT_DATE')
            ->where('token_no', $t)
            ->where("(remarks='AOR -> FDR')");
    });
    $query = $builder->get();
    if ($query->getNumRows() >= 1) {
        $duplicate = 1;
    }
    return $duplicate;
}

/* Added by Shilpa on 06-12-20238 -- Start*/

function is_data_from_table_whereIn($table, $key = null, $arrv = null, $column_names = '*', $row = 'A')
{
    $db = \Config\Database::connect();
    $builder = $db->table($table);
    if (!empty($arrv) && $arrv != null && !empty($key) && $key != null) {
        $query = $builder->select($column_names)->whereIn($key, $arrv)->get();
    } else {
        $query = $builder->select($column_names)->get();
    }
    if ($query->getNumRows() >= 1) {
        if ($row == 'A') {
            $result = $query->getResultArray();
        } else {
            $result = $query->getRowArray();
        }
        return $result;
    } else {
        return [];
    }
}

/* Added by Shilpa on 06-12-20238 -- End*/


/* Added by P.S on 02-01-2024 -- Start*/

function getUserNameAndDesignation($usercode)
{
    $db = \Config\Database::connect();
    $builder = $db->table('master.users u');
    $query = $builder->select('u.name,ut.type_name,u.section,u.usertype')
        ->join('master.usertype ut', 'u.usertype=ut.id', 'inner')
        ->where('usercode', $usercode)->get()->getResultArray();

    if ($query) {
        foreach ($query as $row)
            return $row;
    } else {
        echo "Error Occurred";
    }
}



function getCaseDetails($diarySearchDetails)
{
    $db = \Config\Database::connect();
    $model = new \App\Models\Common\Component\Model_case_status();
    $dropdownlist_model = new \App\Models\Common\Dropdown_list_model();

    $main_diary_number = $diarySearchDetails['dn'] . $diarySearchDetails['dy'];

    $data['diary_disposal_date'] = array();
    $diary_details = is_data_from_table('main', ['diary_no' => $main_diary_number], '*', 'R');
    $flag = "";
    if (empty($diary_details)) {

        $flag = "_a";
        $diary_details = is_data_from_table('main_a', ['diary_no' => $main_diary_number], '*', 'R');
        $data['diary_disposal_date'] = json_decode($model->get_diary_disposal_date($main_diary_number), true);
    }else{
        if($diary_details['c_status'] == 'D')
        {
            $data['diary_disposal_date'] = json_decode($model->get_diary_disposal_date($main_diary_number), true);
        }
    }

    $data['diary_details'] = $diary_details;
    $data['party_details'] = json_decode($model->get_party_details($main_diary_number, $flag), true);
    $data['pet_res_advocate_details'] = json_decode($model->get_pet_res_advocate($main_diary_number, $flag), true);
    $data['old_category'] = json_decode($model->get_old_category($main_diary_number, $flag), true);
    $data['new_category'] = json_decode($model->get_new_category($main_diary_number, $flag), true);
    $category_nm = '';
    $mul_category = '';
    $data['main_case'] = '';
    $data['new_category_name'] = '';
    if (!empty($data['old_category'])) {
        foreach ($data['old_category'] as $old_category) {
            if ($old_category['subcode1'] > 0 and $old_category['subcode2'] == 0 and $old_category['subcode3'] == 0 and $old_category['subcode4'] == 0)
                $category_nm =  $old_category['sub_name1'];
            elseif ($old_category['subcode1'] > 0 and $old_category['subcode2'] > 0 and $old_category['subcode3'] == 0 and $old_category['subcode4'] == 0)
                $category_nm =  $old_category['sub_name1'] . " : " . $old_category['sub_name4'];
            elseif ($old_category['subcode1'] > 0 and $old_category['subcode2'] > 0 and $old_category['subcode3'] > 0 and $old_category['subcode4'] == 0)
                $category_nm =  $old_category['sub_name1'] . " : " . $old_category['sub_name2'] . " : " . $old_category['sub_name4'];
            elseif ($old_category['subcode1'] > 0 and $old_category['subcode2'] > 0 and $old_category['subcode3'] > 0 and $old_category['subcode4'] > 0)
                $category_nm =  $old_category['sub_name1'] . " : " . $old_category['sub_name2'] . " : " . $old_category['sub_name3'] . " : " . $old_category['sub_name4'];

            if ($mul_category == '') {
                $mul_category = $old_category['category_sc_old'] . '-' . $category_nm;
            } else {
                $mul_category = $old_category['category_sc_old'] . '-' . $mul_category . ',<br> ' . $category_nm;
            }
        }
        $data['old_category_name'] = $mul_category;
    }

    if (!empty($data['new_category'])) {
        $data['new_category_name'] = $data['new_category'][0]['category_sc_old'] . '-' . $data['new_category'][0]['sub_name1'] . ' : ' . $data['new_category'][0]['sub_name4'];
    }
    $data['no_of_defect_days'] = json_decode($model->get_defect_days($main_diary_number, $flag), true);

    $data['recalled_matters'] = json_encode($model->get_recalled_matters($main_diary_number), true, JSON_UNESCAPED_SLASHES);
    $data['consignment_status'] = json_decode($model->get_consignment_status($main_diary_number, $flag), true);
    $data['sensitive_case'] = json_decode($model->get_sensitive_cases($main_diary_number), true);
    $data['efiled_cases'] = json_decode($model->get_efiled_cases($main_diary_number), true);
    $data['heardt_case'] = json_decode($model->get_heardt_case($main_diary_number, $flag), true);
    // pr($data['heardt_case']);
    $last_listed_on = "";
    $last_listed_on_jud = "";
     
    if (!empty($data['heardt_case'])) {
        //while ($row1 = $data['heardt_case'] ) {
        $row1 = $data['heardt_case'];
        if ($row1['tbl'] == 'H') {
            $tentative_cl_dt = $row1['tentative_cl_dt'];
        }
        $mc = $row1["filno"];
        if (!empty($mc)) {
             
            //$main_case = get_main_case_in_case_status($mc, $flag)['cn1'];          
            $main_case = get_main_case($mc, $flag);          
            
            $data['main_case'] = $main_case;
        }
        $chk_next_dt = $row1["next_dt"];
        if ($row1["porl"] == "L" and $last_listed_on == "") {
            $next_dt = date("Y-m-d", strtotime($row1["next_dt"]));
            $cl_printed = $model->get_cl_printed_data($next_dt, $row1['mainhead'], $row1["clno"], $row1["roster_id"]);
            // echo ">>>". $cl_printed;
        }
        //}
    }
   

    // if (!empty($data['heardt_case'])) {
    //     foreach ($data['heardt_case'] as $row1) {
    //         if ($row1['tbl'] == 'H') {
    //             $tentative_cl_dt = $row1['tentative_cl_dt'];
    //         }
    //         $mc = $row1["filno"];
    //         if (!empty($mc)) {
    //             $main_case = get_main_case($mc, $flag);
    //             $data['main_case'] = $main_case;
    //         }
    //         $chk_next_dt = $row1["next_dt"];
    //         if ($row1["porl"] == "L" and $last_listed_on == "") {
    //             $next_dt = date("Y-m-d", strtotime($row1["next_dt"]));
    //             $cl_printed = $model->get_cl_printed_data($next_dt, $row1['mainhead'], $row1["clno"], $row1["roster_id"]);
    //         }
    //     }
    // }
    //pr($data['heardt_case']);
    $data['case_type_history'] = json_decode($model->get_case_type_history($main_diary_number, $flag), true);
    $data['fill_dt_case'] = json_decode($model->get_fill_dt_case($main_diary_number, $flag), true);
    $data['diary_section_details'] = json_decode($model->get_diary_section_details($main_diary_number, $flag), true);
    $data['da_section_details'] = json_decode($model->get_da_section_details($main_diary_number, $flag), true);
    $data['autodiary_details'] = json_decode($model->get_autodiary_details($main_diary_number), true);
    $data['filing_stage'] = json_decode($model->get_fil_trap_details($main_diary_number, $flag), true);
    $data['acts_sections'] = json_decode($model->get_acts_sections_details($main_diary_number), true);
    $data['diary_number'] = $main_diary_number;
    $data['IB_DA_Details'] = json_decode($model->get_IB_DA_Details($main_diary_number, $flag), true);
    $data['file_movement_data'] = json_decode($model->get_file_movement_data($main_diary_number, $flag), true);

    if (!empty($data['IB_DA_Details'])) {
        $IbDaName = "<font color='blue' style='font-size:12px;font-weight:bold;'>" . $data['IB_DA_Details']['name'] . " [" . $data['IB_DA_Details']["section_name"] . "]" . "</font>";;
    } else {
        if (!empty($data['diary_section_details'])) {
            $IbDaName = "<font color='blue' style='font-size:12px;font-weight:bold;'>" . $data['diary_section_details']["name"] . " [" .  $data['diary_section_details']["section_name"] . "]" . "</font>";;
        } else {
            $IbDaName = "<font color='blue' style='font-size:12px;font-weight:bold;'> </font>";;
        }
    }
    $section_da_name = (!empty($data['da_section_details'])) ? "<font color='blue' style='font-size:12px;font-weight:bold;'>" . $data['da_section_details']["name"] . "</font>" : '';
    if (!empty($data['da_section_details']) && $data['da_section_details']["dacode"] != "0") {
        $section_da_name .= "<font style='font-size:12px;font-weight:bold;'> [SECTION: </font><font color='red' style='font-size:12px;font-weight:bold;'>" . $data['da_section_details']["section_name"] . "</font><font style='font-size:12px;font-weight:bold;'>]</font>";
    } else {
        $tentative_section = json_decode($model->get_tentative_section($main_diary_number, $flag), true);

        $section_da_name .= (!empty($tentative_section)) ?  "<font style='font-size:12px;font-weight:bold;'> [Tentative SECTION: </font><font color='red' style='font-size:12px;font-weight:bold;'>" . $tentative_section['section_name'] . "</font><font style='font-size:12px;font-weight:bold;'>]</font>" : '';
    }
    if (!empty($data['fill_dt_case'])) {
        if ($data['fill_dt_case']['last_u'] != '')
            $data['last_updated_by'] = $data['fill_dt_case']['last_u'];
        if ($data['fill_dt_case']['last_dt'] != '') {
            $last_dt = date_create($data['fill_dt_case']['last_dt']);
            $last_dt = date_format($last_dt, "d-m-Y h:i A");
            $data['last_updated_by'] .= " On " . $last_dt;
        }
    }
    $pname = "";
    $rname = "";
    $impname = "";
    $intname = "";
    $padvname = "";
    $radvname = "";
    $iadvname = "";
    $nadvname = "";
    $ac_court = "";


    if (!empty($data['party_details'])) {
        foreach ($data['party_details']  as $row_p) {
            $tmp_addr = "";
            $tmp_name = "";

            if ($row_p["pflag"] == 'O')
                $tmp_name = $tmp_name . "<p style=color:red>&nbsp;&nbsp;";
            else if ($row_p["pflag"] == 'D')
                $tmp_name = $tmp_name . "<p style=color:#9932CC>&nbsp;&nbsp;";
            else
                $tmp_name = $tmp_name . "<p>&nbsp;&nbsp;";

            $tmp_name = $tmp_name . $row_p["sr_no_show"];
            $tmp_name = $tmp_name . " ";
            $tmp_name = $tmp_name . $row_p["partyname"];
            if ($row_p["prfhname"] != "")
                $tmp_name = $tmp_name . " S/D/W/Thru:- " . $row_p["prfhname"];
            if ($row_p["remark_lrs"] != '' || $row_p["remark_lrs"] != NULL)
                $tmp_name .= " [" . $row_p["remark_lrs"] . "]";

            if ($row_p["pflag"] == 'O' || $row_p["pflag"] == 'D')
                $tmp_name .= " [" . $row_p["remark_del"] . "]";

            if ($row_p["addr1"] != "")
                $tmp_addr = $tmp_addr . $row_p["addr1"] . ", ";
            if ($row_p['ind_dep'] != 'I' && !empty($row_p['deptname']))
                $tmp_addr = $tmp_addr . " " . trim(str_replace($row_p['deptname'], '', $row_p['partysuff'])) . ", ";
            if ($row_p["addr2"] != "")
                $tmp_addr = $tmp_addr . $row_p["addr2"] . " ";
            if ($row_p["city"] != "") {

                $dstName = '';
                if ($row_p["dstname"] != "") {
                    $dstName .= " , DISTRICT: " . $row_p["dstname"];
                }
                $city_name = get_state_data($row_p["city"])[0]['name'];

                $tmp_addr = $tmp_addr . $dstName . " ," . $city_name . " ";
            }
            if ($row_p["state"] != "") {
                $state_name = get_state_data($row_p["state"])[0]['name'];
                $tmp_addr = $tmp_addr . ", " . $state_name . " ";
            }
            if ($tmp_addr != "")
                $tmp_name = $tmp_name . "<br>&nbsp;&nbsp;" . $tmp_addr . "";
            $tmp_name = $tmp_name . "</p>";

            if ($row_p["pet_res"] == "P") {
                $pname .= $tmp_name;
            }
            if ($row_p["pet_res"] == "R") {
                $rname .= $tmp_name;
            }
            if ($row_p["pet_res"] == "I") {
                $impname .= $tmp_name;
            }
            if ($row_p["pet_res"] == "N") {
                $intname .= $tmp_name;
            }
        }
    }
    $data['IB_da_name'] = $IbDaName;
    $data['section_da_name'] = $section_da_name;
    $data['petitioner_name'] = $pname;
    $data['respondent_name'] = $rname;
    $data['impleader'] = $impname;
    $data['intervenor'] = $intname;

    if (!empty($data['pet_res_advocate_details'])) {
        foreach ($data['pet_res_advocate_details'] as $row_advp) {
            $tmp_advname =  "<p>&nbsp;&nbsp;";
            if ($row_advp['is_ac'] == 'Y') {
                if ($row_advp['if_aor'] == 'Y')
                    $advType = "AOR";
                else if ($row_advp['if_sen'] == 'Y')
                    $advType = "Senior Advocate";
                else if ($row_advp['if_aor'] == 'N' && $row_advp['if_sen'] == 'N')
                    $advType = "NON-AOR";
                else if ($row_advp['if_other'] == 'Y')
                    $advType = "Other";
                $ac_text = '[Amicus Curiae- ' . $advType . ']';
            } else
                $ac_text = '';
            if ($row_advp['is_ac'] == 'Y' && ($row_advp['pet_res'] == '' || empty($row_advp['pet_res']) || $row_advp['pet_res'] == null)) {
                $for_court = "[For Court]";
            } else {
                $for_court = "";
            }
            $t_adv = $row_advp['name'];
            if ($row_advp['isdead'] == 'Y') {
                $t_adv = "<font color=red>" . $t_adv . " (Dead / Retired / Elevated) </font>";
            }
            $tmp_advname = $tmp_advname . $t_adv .  $row_advp['adv'] . $ac_text . '</p>';
            if ($row_advp['pet_res'] == "P")
                $padvname .= $tmp_advname;
            if ($row_advp['pet_res'] == "R")
                $radvname .= $tmp_advname;
            if ($row_advp['pet_res'] == "I")
                $iadvname .= $tmp_advname;
            if ($row_advp['pet_res'] == "N")
                $nadvname .= $tmp_advname;
            if ($row_advp['is_ac'] == 'Y' && ($row_advp['pet_res'] == '' || empty($row_advp['pet_res']) || $row_advp['pet_res'] == null))
                $ac_court .= $tmp_advname;
        }
    }
    $data['ac_court'] = $ac_court;
    $data['padvname'] = $padvname;
    $data['radvname'] = $radvname;
    $data['respondent_name'] = $rname;
    $data['iadvname'] = $iadvname;
    $data['nadvname'] = $nadvname;

    //print_r($data);
    // Case NO
    $t_fil_no = get_case_nos($main_diary_number, '&nbsp;&nbsp;');

    if (trim($t_fil_no) == '') {
        $sql12 =   "SELECT short_description from master.casetype where casecode='" . $diary_details['casetype_id'] . "' and display='Y' ";
        $results12 = $db->query($sql12);
        $row_12 = $results12->getRowArray();

        if (!empty($row_12)) {
            // $row_12 = mysql_fetch_array($results12);
            $t_fil_no = $row_12['short_description'];
        }
    }

    $t_slpcc = '';
    if (isset($diarySearchDetails['new_registration_number'])  && $diarySearchDetails['new_registration_number'] != '') {
        $parts = explode('-', $diarySearchDetails['new_registration_number']);

        $crf1 = $parts[1] ?? '';
        $crl1 = $parts[2] ?? '';
        $t_slpcc = $t_fil_no . " " . $crf1 . " - " . $crl1 . " / " . $diarySearchDetails['cy'];
    }


    if ($t_slpcc != '')
        $t_slpcc = "<br>" . $t_slpcc;


    $t_fil_no1 = "";

    $rs_lct = $model->getLowerCourtDetails($main_diary_number);
    if (!empty($rs_lct)) {
        $t_fil_no1 .= "";
        foreach ($rs_lct as $ro_lct) {
            if ($t_fil_no1 == '')
                $t_fil_no1 .= " IN " . $ro_lct['type_sname'] . "  " . $ro_lct['lct_caseno'] . "/" . $ro_lct['lct_caseyear'];
            else
                $t_fil_no1 .= ", " . $ro_lct['type_sname'] . "  " . $ro_lct['lct_caseno'] . "/" . $ro_lct['lct_caseyear'];
        }
    }
    // pr($t_slpcc);
    $data['case_no'] = $t_fil_no.$t_slpcc.$t_fil_no1; //  $t_slpcc . $t_fil_no1;
    //pr($data);
    //print_r($data);
    //pr($t_fil_no1);
    return $data;
    //return $result_view = view('Common/Component/case_status/case_status_details_tab',$data);
}


function get_main_case_in_case_status($flno,$flag='')
{
    $db = \Config\Database::connect();

    $sql = "
        SELECT 
            CASE 
                WHEN NOT (
                    conn_key = '' OR conn_key IS NULL OR diary_no::TEXT = conn_key
                ) THEN (
                    SELECT 
                        (
                            SELECT skey 
                            FROM master.casetype 
                            WHERE casecode = TRIM(LEADING '0' FROM SUBSTRING(conn_key FROM 3 FOR 3))::integer
                        ) 
                        || ' ' || TRIM(LEADING '0' FROM SUBSTRING(conn_key FROM 6 FOR 5)) 
                        || '/' || SUBSTRING(conn_key FROM 11 FOR 4)
                    FROM master.casetype 
                    WHERE casecode = TRIM(LEADING '0' FROM SUBSTRING(diary_no::TEXT FROM 3 FOR 3))::integer
                )
                ELSE ''
            END AS cn1
        FROM main
        WHERE diary_no = '$flno'
    ";

    $query = $db->query($sql);
    return $query->getRowArray();
}

//Judicial Hc_Or
function getHcOrPendingVerification($diary_no)
{
    $db = \Config\Database::connect();
    // Using Query Builder for PostgreSQL
    $builder = $db->table('main m');
    $builder->select("CONCAT(u.name, ' (', u.empid, ') ', ', ', us.section_name) AS name");
    $builder->join('users u', 'u.usercode = m.dacode AND (u.display = \'Y\' OR u.display IS NULL)', 'left');
    $builder->join('usersection us', 'us.id = u.section', 'left');
    $builder->where('m.diary_no', $diary_no);

    // Execute the query
    $query = $builder->get();

    // Check for results
    if ($query->getNumRows() > 0) {
        $result = $query->getResultArray();

        foreach ($result as $row) {
            echo "
                <div class='text-center'>
                    <h3 class='text-danger mb-0'>
                        Verification is pending from DA: {$row['name']}
                    </h3>
                </div>
            ";
        }
    } else {
        echo "<div class='text-center'>
                <h4 class='text-success mb-0'>No pending verification.</h4>
              </div>";
    }
}

function getHcOrDefectVerification($diary_no, $ifConfirmed)
{

    $db = \Config\Database::connect();
    $builder = $db->table('lowercourt_data.defects vh');

    // Select columns with necessary formatting
    $builder->select([
        "TO_CHAR(vh.notified_on, 'DD/MM/YYYY HH24:MI:SS') AS verify_on",
        "CONCAT(u.name, ' (', u.empid, ') ', ', ', us.section_name) AS name",
        "COALESCE(vh.defects, 'No Remarks') AS remarks",
        "vh.if_verified"
    ])
        ->join('main m', 'm.diary_no = vh.diary_no', 'inner')
        ->join('master.users u', "u.usercode = vh.updated_by AND (u.display = 'Y' OR u.display IS NULL)", 'left')
        ->join('master.usersection us', 'us.id = u.section', 'left')
        ->where('vh.diary_no', $diary_no)
        ->groupStart() // Nested conditions for 'D'
        ->where('vh.defect_removed_on IS NULL')
        ->orWhere("vh.defect_removed_on IS NOT NULL AND vh.defect_removed_on > '1900-01-01 00:00:00'", null, false) // Skip invalid dates
        ->groupEnd();
    // ->groupStart() // Handle nested conditions for defect removal dates
    //     ->where('vh.defect_removed_on IS NULL')
    //     ->orWhere("vh.defect_removed_on > '1900-01-01 00:00:00'", null, false)
    // ->groupEnd();

    // Execute the query
    $query = $builder->get();

    // Handle results
    if ($query->getNumRows() > 0) {
        foreach ($query->getResultArray() as $row_verify) {
            echo "
                <div class='card text-center'>
                    <div class='card-body'>
                    <h3 class='mb-0'>
                        Verified By: {$row_verify['name']}
                        on {$row_verify['verify_on']}
            ";
            if ($row_verify['if_verified'] === 'D') {
                echo "
                    <br><span class='text-danger'>Defective: {$row_verify['remarks']}</span>
                    <br/>(Please re-open for Updation from High Court)
                    <br/><input type=\"hidden\" name=\"hdn_diary_no\" id=\"hdn_diary_no\" value=\"{$diary_no}\">
                ";
                if ($ifConfirmed > 0) {
                    echo "
                        <br/><input type=\"button\" style=\"color: red; font-weight: bold\" 
                        name=\"btnReOpen\" id=\"btnReOpen\" value=\"Re-Open For Updation\" onclick=\"doReOpen();\">
                    ";
                }
            } else {
                echo "
                    <br><span class='text-success'>Verified: {$row_verify['remarks']}</span>
                    <br/>(Please contact Section V for viewing Record.)
                ";
            }
            echo "</h3></div></div>";
        }
    } else {
        echo "<div class='bg-success p-2 text-center'><h4 class='mb-0 text-white'>No defects found for verification.</h4></div>";
    }
}


function get_state_data($id_no = null)
{
    $db = \Config\Database::connect();
    $builder = $db->table("master.state");
    $builder->select("id_no, name");
    $builder->WHERE('display', 'Y');
    if (!empty($id_no) && $id_no != null) {
        $builder->WHERE('id_no', $id_no);
    }
    $builder->orderBy('name', 'ASC');
    $query = $builder->get();
    if ($query->getNumRows() >= 1) {
        return $result = $query->getResultArray();
    } else {
        return false;
    }
}

/* Added by P.S on 02-01-2024 -- End*/
/* added by Shilpa */
function updateIn($table_name, $data, $key, $ids)
{
    $db = \Config\Database::connect();
    $builder = $db->table($table_name);
    $builder->whereIn($key, $ids);
    //$builder->set($data);$query= $builder->getCompiledUpdate();echo (string) $query; exit();
    if ($builder->update($data)) {
        return true;
    } else {
        return false;
    }
}
/* added by Shilpa end */
function component_html($component_type = '')
{
    $html = "";
    $data['component'] = 'component_diary_with_case';
    $data['component_type'] = $component_type;
    $html = view('Common/Component/index', $data);
    return $html;
}

function tentative_da($diary_no, $row = 'R')
{
    $db = \Config\Database::connect();
    $query = $db->table('main m')
        ->select('us.section_name, us.id AS sec_id, u.empid, u.name,u.usercode,m.diary_no,m.c_status')
        ->join('master.users u', 'm.dacode = u.usercode')
        ->join('master.usersection us', 'u.section = us.id')
        ->where('u.display', 'Y')
        ->where('us.display', 'Y')
        ->where('m.diary_no', $diary_no)
        ->orderBy('us.section_name')
        ->get();
    if ($query->getNumRows() >= 1) {
        if ($row == 'R') {
            $result = $query->getRowArray();
        } else {
            $result = $query->getResultArray();
        }
        return $result;
    } else {
        $query2 = $db->table('main_a m')
            ->select('us.section_name, us.id AS sec_id, u.empid, u.name,u.usercode,m.diary_no,m.c_status')
            ->join('master.users u', 'm.dacode = u.usercode')
            ->join('master.usersection us', 'u.section = us.id')
            ->where('u.display', 'Y')
            ->where('us.display', 'Y')
            ->where('m.diary_no', $diary_no)
            ->orderBy('us.section_name')
            ->get();
        if ($query2->getNumRows() >= 1) {
            if ($row == 'R') {
                $result2 = $query2->getRowArray();
            } else {
                $result2 = $query2->getResultArray();
            }
            return $result2;
        } else {
            return $result = '';
        }
    }
}
function procedure_function($function_name, $diary_no, $array = 'K')
{
    $db = \Config\Database::connect();
    $result2 = '';
    if (!empty($diary_no) && $diary_no != null && !empty($function_name)) {
        $query = "select $function_name($diary_no)";
    } else {
        return $result = 'Function name and diary number is required';
    }
    $query = $db->query($query);
    if ($query->getNumRows() >= 1) {
        if ($array == 'K') {
            $result = $query->getRowArray();
            $result2 = $result[$function_name];
        } elseif ($array == 'R') {
            $result2 = $query->getRowArray();
        } else {
            $result2 = $query->getResultArray();
        }
        return $result2;
    } else {
        return $result2 = '';
    }
}

function getEarlierCourtData($diary_no)
{
    $model = new \App\Models\Common\Component\Model_case_status();
    $diary_details = is_data_from_table('main', ['diary_no' => $diary_no], '*', 'R');
    $flag = "";
    if (empty($diary_details)) {
        $flag = "_a";
        $diary_details = is_data_from_table('main_a', ['diary_no' => $diary_no], '*', 'R');
    }
    $data['earlier_court'] = json_decode($model->getEarlierCourtData($diary_no, $flag), true);

    $data['all_ref_details'] =  json_decode($model->allReferenceDetailsByDiaryNo($diary_no, $flag), true);
    $data['all_gov_not_details'] =  json_decode($model->allGovernmentNotificationsByDiaryNo($diary_no, $flag), true);
    $data['all_relied_details'] =  json_decode($model->allReliedDetailsByDiaryNo($diary_no, $flag), true);
    $data['all_transfer_details'] =  json_decode($model->allTransferDetailsByDiaryNo($diary_no, $flag), true);
    $data['judges_details'] =  json_decode($model->getJudgeDetailsByDiary($diary_no, $flag), true);

    $data['diary_details'] = $diary_details;
    return $data;
}

function component_court_html($component_type = '')
{
    $Dropdown_list_model = new App\Models\Common\Dropdown_list_model;
    $data['state'] = get_from_table_json('state');
    $data['court_type_list'] = $Dropdown_list_model->get_court_type_list();
    $html = "";
    $data['component'] = 'component_court';
    $data['component_type'] = $component_type;
    $html = view('Common/Component/component_court_html', $data);
    return $html;
}

function get_judges($jcodes)
{
    $jnames = "";
    if ($jcodes != '') {
        $t_jc = explode(",", $jcodes);
        for ($i = 0; $i < count($t_jc); $i++) {
            $j_id = $t_jc[$i];
            $judges_data = is_data_from_table('master.judge', ['jcode' => $j_id], 'jname');

            if (!empty($judges_data)) {
                foreach ($judges_data as $row11a) {
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
function getBeforeNotBeforeData($diary_no)
{
    $db = \Config\Database::connect();
    $builder1 = $db->table("not_before a");
    $builder1->select("a.diary_no, string_agg(b.jname,',') as jn,a.notbef");
    $builder1->join('master.judge b', "a.j1=b.jcode");
    $builder1->where('diary_no', $diary_no);
    $builder1->groupBy('a.diary_no,a.notbef');
    $pr_bf = $nbf = $bf = "";
    $query = $builder1->get();

    if ($query->getNumRows() >= 1) {
        $result = $query->getResultArray();
        foreach ($result as $rownb) {
            $t_jn = $rownb["jn"];
            $t_jn1 = stripslashes($t_jn);
            if ($rownb["notbef"] == "B")
                if ($bf == "")
                    $bf .= $t_jn1;
                else
                    $bf .= ",  " . $t_jn1;
            if ($rownb["notbef"] == "N")
                if ($nbf == "")
                    $nbf .= $t_jn1;
                else
                    $nbf .= ",  " . $t_jn1;
        }
    }
    return $bf . "^|^" . $nbf;
}

function get_mul_category($diary_no, $flag = null)
{
    $db = \Config\Database::connect();
    $builder1 = $db->table("mul_category" . $flag . " mc");
    $builder1->select("s.*");
    $builder1->join('master.submaster s', "mc.submaster_id=s.id");
    $builder1->where('diary_no', $diary_no);
    $builder1->where('mc.display', 'Y');
    $query = $builder1->get();

    if ($query->getNumRows() >= 1) {
        $result = $query->getResultArray();
        $mul_category = "";
        foreach ($result as $row2) {
            if ($row2['subcode1'] > 0 and $row2['subcode2'] == 0 and $row2['subcode3'] == 0 and $row2['subcode4'] == 0)
                $category_nm =  $row2['sub_name1'];
            elseif ($row2['subcode1'] > 0 and $row2['subcode2'] > 0 and $row2['subcode3'] == 0 and $row2['subcode4'] == 0)
                $category_nm =  $row2['sub_name1'] . " : " . $row2['sub_name4'];
            elseif ($row2['subcode1'] > 0 and $row2['subcode2'] > 0 and $row2['subcode3'] > 0 and $row2['subcode4'] == 0)
                $category_nm =  $row2['sub_name1'] . " : " . $row2['sub_name2'] . " : " . $row2['sub_name4'];
            elseif ($row2['subcode1'] > 0 and $row2['subcode2'] > 0 and $row2['subcode3'] > 0 and $row2['subcode4'] > 0)
                $category_nm =  $row2['sub_name1'] . " : " . $row2['sub_name2'] . " : " . $row2['sub_name3'] . " : " . $row2['sub_name4'];

            if ($mul_category == '') {
                $mul_category = $row2['category_sc_old'] . '-' . $category_nm;
            } else {
                $mul_category = $row2['category_sc_old'] . '-' . $mul_category . ',<br> ' . $category_nm;
            }
        }
        return $mul_category;
    } else {
        return false;
    }
}

function validate_verification($diary_no, $flag = null)
{
    $db = \Config\Database::connect();
    $builder = $db->table('main' . $flag . ' as a');
    $builder->distinct();
    $builder->select('a.diary_no, pet_name, res_name, a.casetype_id ');
    $builder->join('defects_verification' . $flag . ' c', "a.diary_no = c.diary_no", 'left');
    $builder->where('a.diary_no', $diary_no);
    $builder->where('c_status', 'P');
    $builder->whereNotIn('a.casetype_id', [9, 10, 19, 20, 39, 11, 12, 25, 26]);
    $builder->where('a.diary_no_rec_date>', '2018-08-06');
    $builder->groupStart()
        ->where('c.diary_no is null')
        ->orWhere('c.diary_no IS NOT NULL')
        ->where('verification_status', '1')
        ->groupEnd();

    $builder2 = $db->table('main' . $flag . ' as a')
        ->distinct()
        ->select('a.diary_no, pet_name, res_name, a.casetype_id')
        ->join('docdetails b', 'a.diary_no = b.diary_no')
        ->join('master.docmaster c', 'b.doccode = c.doccode AND b.doccode1 = c.doccode1')
        ->join('defects_verification d', 'a.diary_no = d.diary_no', 'left')
        ->where('c_status', 'P')
        ->where('b.display', 'Y')
        ->where('c.display', 'Y')
        ->groupStart()
        ->where('not_reg_if_pen', 1)
        ->orWhere('not_reg_if_pen', 2)
        ->groupEnd()
        ->groupStart()
        ->where('d.diary_no', null)
        ->orWhere('verification_status', '1')
        ->groupEnd()
        ->where('a.diary_no', $diary_no)
        ->whereNotIn('a.casetype_id', [9, 10, 19, 20, 39, 11, 12, 25, 26])
        ->where("a.diary_no_rec_date >='2018-08-06'");

    $unionResult = $builder->union($builder2)->get();
    $ret = 0;
    if ($unionResult->getNumRows() >= 1) {
        $ret = 1;
        return $ret;
    } else {
        return $ret;
    }
}
function main_regular()
{ ?>
    <select name="main_regular" id="main_regular" class="form-control">
        <option value="M">Miscellaneous</option>
        <option value="R">Regular</option>
    </select>

<?php }

function board_type()
{ ?>
    <select name="board_type" id="board_type" class="form-control">
        <option value="0">-ALL-</option>
        <option value="J">Court</option>
        <option value="S">Single Judge</option>
        <option value="C">Chamber</option>
        <option value="R">Registrar</option>
    </select>
<?php }

function main_supp()
{ ?>
    <select class="form-control" name="main_suppl" id="main_suppl">
        <option value="0">-ALL-</option>
        <option value="1">Main</option>
        <option value="2">Suppl.</option>
    </select>
<?php }


function court_no()
{ ?>
    <select class="form-control" name="courtno" id="courtno">
        <option value="0">-ALL-</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">12</option>
        <option value="13">13</option>
        <option value="14">14</option>
        <option value="15">15</option>
        <option value="16">16</option>
        <option value="17">17</option>
        <option value="21">21 (RC1)</option>
        <option value="22">22 (RC2)</option>
    </select>
    <?php }

function get_selected_values($parm1)
{
    $dld = "";
    foreach ($parm1 as $key => $value) {
        $dld .= $value . ",";
    }
    return rtrim($dld, ',');
}

function send_email($to, $subject, $message, $files)
{

    foreach ($to as $to1) {

        $string = base64_encode(json_encode(array("allowed_key" => "G1Kp54WtuQ23", "sender" => "eService", "mailTo" => $to1, "subject" => $subject, "message" => $message, "files" => $files)));
        $content = http_build_query(array('a' => $string));
        $context = stream_context_create(array('http' => array('method' => 'POST', 'content' => $content,)));
        $json_return = @file_get_contents('http://10.25.78.60/supreme_court/Copying/index.php/Api/eMailSend', null, $context);
        $json2 = json_decode($json_return);
    }
}

function get_court_number()
{
    $result = array(
        1 => "Hon'ble Court No.1",
        2 => "Hon'ble Court No.2",
        3 => "Hon'ble Court No.3",
        4 => "Hon'ble Court No.4",
        5 => "Hon'ble Court No.5",
        6 => "Hon'ble Court No.6",
        7 => "Hon'ble Court No.7",
        8 => "Hon'ble Court No.8",
        9 => "Hon'ble Court No.9",
        10 => "Hon'ble Court No.10",
        11 => "Hon'ble Court No.11",
        12 => "Hon'ble Court No.12",
        13 => "Hon'ble Court No.13",
        14 => "Hon'ble Court No.14",
        15 => "Hon'ble Court No.15",
        16 => "Hon'ble Court No.16",
        17 => "Hon'ble Court No.17",
        31 => "Hon'ble Virtual Court No.1",
        32 => "Hon'ble Virtual Court No.2",
        33 => "Hon'ble Virtual Court No.3",
        34 => "Hon'ble Virtual Court No.4",
        35 => "Hon'ble Virtual Court No.5",
        36 => "Hon'ble Virtual Court No.6",
        37 => "Hon'ble Virtual Court No.7",
        38 => "Hon'ble Virtual Court No.8",
        39 => "Hon'ble Virtual Court No.9",
        40 => "Hon'ble Virtual Court No.10",
        41 => "Hon'ble Virtual Court No.11",
        42 => "Hon'ble Virtual Court No.12",
        43 => "Hon'ble Virtual Court No.13",
        44 => "Hon'ble Virtual Court No.14",
        45 => "Hon'ble Virtual Court No.15",
        46 => "Hon'ble Virtual Court No.16",
        47 => "Hon'ble Virtual Court No.17",
        101 => "Chamber"
    );
    return $result;
}


if (!function_exists('f_get_judge_names')) {
    /**
     * Retrieve judge names by their codes.
     *
     * @param string $chk_jud_id Comma-separated judge codes.
     * @return string Comma-separated judge names.
     */
    function f_get_judge_names($chk_jud_id)
    {
        // Get the database connection from CodeIgniter
        $db = \Config\Database::connect();

        // Ensure $chk_jud_id is a valid and safe string
        $chk_jud_id = trim($chk_jud_id, ',');

        // Build the query
        $sql = "SELECT first_name, sur_name 
                FROM master.judge 
                WHERE is_retired != 'Y' 
                AND jcode IN ($chk_jud_id)";

        // Execute the query
        $query = $db->query($sql);
        $result = $query->getResultArray();

        $jname = "";

        if (!empty($result)) {
            foreach ($result as $row) {
                $jname .= $row['first_name'] . " " . $row['sur_name'] . ", ";
            }
            $jname = rtrim($jname, ", ");
        }

        return $jname;
    }
}
function getBasePath()
{
    if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == "http://164.52.201.69/ICMIS/public/") {
        $path = '../../u01-nfs/home';
    } elseif (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == "http://localhost/ICMIS/public/") {
        $path = '../../u01-nfs/home';
    } elseif (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == '10.40.186.244:81') {
        $path = FCPATH;
    } else {
        // $path = '../../u01-nfs/home';
        $path = FCPATH;
    }
    return $path;
}

function getCaseType()
{
    $db = \Config\Database::connect();
    $query = $db->table('master.casetype')
        ->select('casecode, skey, casename, short_description')
        ->where('casecode !=', '9999')
        ->where('casecode !=', '15')
        ->where('casecode !=', '16')
        ->where('display', 'Y')
        ->orderBy('short_description')
        ->orderBy('casecode')
        ->get();
    if ($query->getNumRows() >= 1) {
        return $query->getResultArray();
    } else {
        return [];
    }
}

if (!function_exists('get_allocation_judge_advance_prepone')) {

    function get_allocation_judge_advance_prepone($p1, $cldt, $board_type)
    {
        $db = \Config\Database::connect();

        // Convert date format
        $cldtMMDDYYYY = date('d-m-Y', strtotime($cldt));
        $cldt = date('Y-m-d', strtotime($cldt));

        // Determine the conditions based on $p1 and $board_type
        if ($p1 == "M") {
            $m_f = "AND r.m_f = '1'";
            $from_to_dt = ($board_type == 'R') ? "AND r.to_date = '0000-00-00'" : "AND r.from_date = '$cldt'";
        } else if ($p1 == "L") {
            $m_f = "AND r.m_f = '3'";
            $from_to_dt = "AND r.from_date = '$cldt'";
        } else if ($p1 == "S") {
            $m_f = "AND r.m_f = '4'";
            $from_to_dt = "AND r.from_date = '$cldt'";
        } else {
            $m_f = "AND r.m_f = '2'";
            $from_to_dt = "AND r.from_date = '$cldt'";
        }

        // Check if the date is a working day
        $builder = $db->table('master.sc_working_days');
        $builder->select('is_nmd');
        $builder->where('working_date', $cldt);
        $builder->where('is_holiday', 0);
        $builder->where('display', 'Y');
        $query = $builder->get();
        $ro_isnmd = $query->getRowArray();

        // Error handling if $ro_isnmd is null
        if ($ro_isnmd === null) {
            return "<span style='color:red;'><b>Not a Working Day</b></span><br>";
        }

        if ($ro_isnmd['is_nmd'] == 1) {
            pr('4');
            echo "<span style='color:blue;'><b>Ready to list Regular Day Cases</b></span><br>";
        } else {
            echo "<span style='color:green;'><b>Ready to List Misc. Day Cases</b></span><br>";
        }

        // Build SQL query based on the condition
        if ($ro_isnmd['is_nmd'] == 1) {
            $sql = "SELECT jg.p1, jg.p2, jg.p3, j.abbreviation, 
                        (SELECT 5 old_limit FROM 
                        (SELECT (@a:=@a+1) SNo, s.* FROM sc_working_days s, (SELECT @a:= 0) AS b 
                        WHERE WEEK(working_date) = WEEK('$cldt') 
                        AND is_holiday = 0 
                        AND is_nmd = 1 
                        AND display = 'Y' 
                        AND YEAR(working_date) = YEAR('$cldt') 
                        ORDER BY working_date) a 
                        WHERE working_date = '$cldt') old_limit
                    FROM judge_group jg 
                    LEFT JOIN master.judge j ON j.jcode = jg.p1
                    WHERE jg.to_dt = '0000-00-00' 
                    AND jg.display = 'Y' 
                    AND j.is_retired != 'Y' 
                    ORDER BY j.judge_seniority";
        } else {
            $sql = "SELECT jg.p1, jg.p2, jg.p3, j.abbreviation, jg.fresh_limit, 5 as old_limit 
                    FROM judge_group jg 
                    LEFT JOIN master.judge j ON j.jcode = jg.p1
                    WHERE jg.to_dt = '0000-00-00' 
                    AND jg.display = 'Y' 
                    AND j.is_retired != 'Y' 
                    ORDER BY j.judge_seniority";
        }

        // Execute the query
        $query = $db->query($sql);
        $results = $query->getResultArray();

        if (!empty($results)) {
            // Output the results as a table
            $srno = 1;
            $tot_listed = 0;
            $tot_Pre_Notice = 0;
            $tot_After_Notice = 0;

            $html = '<br>
            <div id="prnnt2">
                <fieldset>
                    <legend style="text-align:center;color:#4141E0; font-weight:bold;">ADVANCE LIST ALLOCATION FOR DATED ' . $cldtMMDDYYYY . ' (Pre-ponement)</legend>
                    <table border="1" width="100%" style="border-collapse:collapse; border-color:black; vertical-align: bottom; text-align: left; background:#f6fbf0;" cellspacing=0>
                        <tr>
                            <th style="text-align: center; vertical-align: top;">SrNo.</th>
                            <th style="text-align: center; vertical-align: top;"><input type="checkbox" name="chkall" id="chkall" value="ALL" onClick="chkall1(this);"></th>
                            <th style="text-align: center; vertical-align: top;">Hon\'ble Judge</th>
                            <th style="text-align: center; vertical-align: top;">Pre Notice Listed</th>
                            <th style="text-align: center; vertical-align: top;">After Notice Listed</th>
                            <th style="text-align: center; vertical-align: top;">Total Listed</th>
                        </tr>';

            foreach ($results as $row) {
                $jcd_p1 = explode(",", $row["p1"]);
                $sql1 = "SELECT j1, COUNT(diary_no) listed,
                            SUM(CASE WHEN pre_after_notice = 'Pre_Notice' THEN 1 ELSE 0 END) Pre_Notice,
                            SUM(CASE WHEN pre_after_notice = 'After_Notice' THEN 1 ELSE 0 END) After_Notice
                        FROM (SELECT DISTINCT h.diary_no, h.j1,
                            CASE WHEN (c.diary_no IS NULL AND (m.fil_no_fh = '' OR m.fil_no_fh IS NULL) AND h.subhead NOT IN (813,814))
                            THEN 'Pre_Notice' ELSE 'After_Notice' END pre_after_notice
                            FROM advance_allocated h
                            LEFT JOIN main m ON h.diary_no = m.diary_no
                            LEFT JOIN advanced_drop_note d ON d.diary_no = h.diary_no AND d.cl_date = h.next_dt
                            LEFT JOIN case_remarks_multiple c ON c.diary_no = m.diary_no AND c.r_head IN (1,3,62,181,182,183,184)
                            WHERE d.diary_no IS NULL AND h.next_dt = '$cldt'
                            AND h.j1 = '" . $row["p1"] . "' AND h.board_type = '$board_type'
                            AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
                            AND h.clno = 2
                            AND (m.diary_no = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
                            GROUP BY m.diary_no) h
                        GROUP BY h.j1";

                $query1 = $db->query($sql1);
                $row1 = $query1->getRowArray();

                $html .= '<tr style="vertical-align: bottom;">
                            <td style="vertical-align: bottom;">' . $srno++ . '</td>
                            <td style="vertical-align: bottom;" colspan="2">
                                <input type="checkbox" id="chkeeed" name="chk" value="' . $row["p1"] . '">
                                ' . $row['abbreviation'] . '
                            </td>
                            <td style="vertical-align: bottom;">' . ($row1['Pre_Notice'] ?? 0) . '</td>
                            <td style="vertical-align: bottom;">' . ($row1['After_Notice'] ?? 0) . '</td>
                            <td style="vertical-align: bottom;">' . ($row1['listed'] ?? 0) . '</td>
                        </tr>';

                $tot_Pre_Notice += $row1['Pre_Notice'] ?? 0;
                $tot_After_Notice += $row1['After_Notice'] ?? 0;
                $tot_listed += $row1['listed'] ?? 0;
            }

            $html .= '<tr style="font-weight:bold;">
                        <td style="text-align:right;" colspan="3">TOTAL</td>
                        <td>' . $tot_Pre_Notice . '</td>
                        <td>' . $tot_After_Notice . '</td>
                        <td>' . $tot_listed . '</td>
                    </tr>
                </table>
            </fieldset>
        </div>';

            return $html;
        } else {
            return "<p>No records found for the given date and conditions.</p>";
        }
    }
}

if (!function_exists('change_date_format')) {
    function change_date_format($date)
    {
        if ($date == "" or $date == "0000-00-00")
            $date = "";
        else
            $date = date('d-m-Y', strtotime($date));
        return $date;
    }
}

if (!function_exists('get_display_status_with_date_differnces')) {
    function get_display_status_with_date_differnces($tentative_cl_dt)
    {
        $tentative_cl_date_greater_than_today_flag = "F";
        $curDate = Time::now()->format('d-m-Y');
        $tentativeCLDate = Time::parse($tentative_cl_dt)->format('d-m-Y');

        $datediff = strtotime($tentativeCLDate) - strtotime($curDate);
        $noofdays = round($datediff / (60 * 60 * 24));

        if (strtotime($tentativeCLDate) > strtotime($curDate)) {
            if ($noofdays <= 60 && $noofdays > 0) {
                $tentative_cl_date_greater_than_today_flag = 'T';
            }
        } else {
            $tentative_cl_date_greater_than_today_flag = 'F';
        }
        return $tentative_cl_date_greater_than_today_flag;
    }
}

if (!function_exists('get_diary_numyear')) {
    function get_diary_numyear($dn)
    {
        if ($dn != "") {
            return [substr($dn, 0, -4), substr($dn, -4)];
        }
    }
}

if (!function_exists('get_real_diaryno')) {
    function get_real_diaryno($dn)
    {
        $real_diary_no = "";
        if ($dn != "") {
            $real_diary_no = substr($dn, 0, -4) . "/" . substr($dn, -4);
        }
        return $real_diary_no;
    }
}

if (!function_exists('get_casenos_comma')) {
    function get_casenos_comma($dn)
    {
        $db = \Config\Database::connect();

        $t_fil_no = '';
        // echo $dn;
        if ($dn != '') {
            // Assuming $dn is already sanitized and holds the diary number
            // $dn = $db->escape($dn);

            $builder = $db->table('public.main m');
            $builder->select("
                casetype_id,
                CONCAT(
                    m.active_fil_no, 
                    ':', 
                    CASE 
                        WHEN active_reg_year = 0 OR active_fil_dt > DATE '2017-05-10' THEN EXTRACT(YEAR FROM active_fil_dt)
                        ELSE active_reg_year
                    END, 
                    ':', 
                    TO_CHAR(active_fil_dt, 'DD-MM-YYYY')
                ) AS ad,
                CASE 
                    WHEN fil_no_fh != active_fil_no AND fil_no_fh != fil_no AND fil_no_fh != '' THEN CONCAT(
                        m.fil_no_fh, 
                        ':', 
                        CASE 
                            WHEN reg_year_fh = 0 OR fil_dt_fh > DATE '2017-05-10' THEN EXTRACT(YEAR FROM fil_dt_fh)
                            ELSE reg_year_fh
                        END, 
                        ':', 
                        TO_CHAR(fil_dt_fh, 'DD-MM-YYYY')
                    ) 
                    ELSE ''
                END AS rd,
                CASE 
                    WHEN fil_no != active_fil_no AND fil_no_fh != fil_no AND fil_no != '' THEN CONCAT(
                        m.fil_no, 
                        ':', 
                        CASE 
                            WHEN reg_year_mh = 0 OR fil_dt > DATE '2017-05-10' THEN EXTRACT(YEAR FROM fil_dt)
                            ELSE reg_year_mh
                        END, 
                        ':', 
                        TO_CHAR(fil_dt, 'DD-MM-YYYY')
                    ) 
                    ELSE ''
                END AS md
            ");

            $builder->where('diary_no', $dn);

            $result_main = $builder->get();

            $row_main = [];
            $cases = "";
            if ($result_main->getNumRows() > 0) {
                $row_main = $result_main->getRowArray(); // Fetch the row as an associative array

                // $sql_from_main = "SELECT casetype_id,
                //     CONCAT(
                //         m.active_fil_no,
                //         ':',
                //         IF(
                //         (
                //             active_reg_year = 0 
                //             OR DATE(active_fil_dt) > DATE('2017-05-10')
                //         ),
                //         YEAR(active_fil_dt),
                //         active_reg_year
                //         ),
                //         ':',
                //         DATE_FORMAT(active_fil_dt, '%d-%m-%Y')
                //     ) ad,
                //     IF(fil_no_fh!=active_fil_no AND fil_no_fh!=fil_no AND fil_no_fh!='', CONCAT(
                //         m.fil_no_fh,
                //         ':',
                //         IF(
                //         (
                //             reg_year_fh = 0 
                //             OR DATE(fil_dt_fh) > DATE('2017-05-10')
                //         ),
                //         YEAR(fil_dt_fh),
                //         reg_year_fh
                //         ),
                //         ':',
                //         DATE_FORMAT(fil_dt_fh, '%d-%m-%Y')
                //     ),'') rd,
                //     IF(fil_no!=active_fil_no AND fil_no_fh!=fil_no AND fil_no!='', CONCAT(
                //         m.fil_no,
                //         ':',
                //         IF(
                //         (
                //             reg_year_mh = 0 
                //             OR DATE(fil_dt) > DATE('2017-05-10')
                //         ),
                //         YEAR(fil_dt),
                //         reg_year_mh
                //         ),
                //         ':',
                //         DATE_FORMAT(fil_dt, '%dcommon_he-%m-%Y')
                //     ),'') md
                //     FROM
                //     main m 
                //     WHERE `diary_no` = " . $dn;
                // $result_main = mysql_query($sql_from_main) or die(mysql_error() . $sql_from_main);
                // $cases = "";
                // if (mysql_affected_rows() > 0) {
                // $row_main = mysql_fetch_array($result_main);

                if ($row_main['ad'] != '') {
                    $t_m_y = explode(':', $row_main['ad']);
                    if ($t_m_y[0] != '') {
                        $cases .= $t_m_y[0] . ",";
                        $t_m1 = substr($t_m_y[0], 0, 2);
                        $t_m2 = substr($t_m_y[0], 3, 6);
                        $t_m21 = substr($t_m_y[0], 10, 6);
                        $t_m3 = $t_m_y[1];
                        $t_m4 = $t_m_y[2];

                        // Assuming $t_m1 is already sanitized
                        // $t_m1 = $db->escape($t_m1);
                        $t_m1 = (int) $t_m1;
                        $builder = $db->table('master.casetype');
                        $builder->select('short_description, cs_m_f');
                        $builder->where('casecode', $t_m1);
                        $builder->where('display', 'Y');

                        $sql_ct_type = $builder->get();
                        $row = $sql_ct_type->getRowArray(); // Fetch a single row as an associative array

                        // $sql_ct_type = mysql_query("Select short_description,cs_m_f from casetype where casecode='" . $t_m1 . "' and display='Y'") or die("Error" . __LINE__ . mysql_error());
                        // $row = mysql_fetch_array($sql_ct_type);

                        $res_ct_typ = isset($row['short_description']) ? $row['short_description'] : '';
                        $res_ct_typ_mf = isset($row['cs_m_f']) ? $row['cs_m_f'] : '';
                        if (trim($t_fil_no) != '')
                            $t_fil_no .= ",<br>";
                        if ($t_m2 == $t_m21)
                            $t_fil_no .= '<font color="#043fff" style=" white-space: nowrap;">' . $res_ct_typ . " " . ltrim($t_m2, '0') . '/' . $t_m3 . '</font>';
                        else
                            $t_fil_no .= '<font color="#043fff" style=" white-space: nowrap;">' . $res_ct_typ . " " . ltrim($t_m2, '0') . '-' . ltrim($t_m21, '0') . '/' . $t_m3 . '</font>';
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

                        // Assuming $t_m1 is already sanitized
                        // $t_m1 = $db->escape($t_m1);

                        $builder = $db->table('master.casetype');
                        $builder->select('short_description, cs_m_f');
                        $builder->where('casecode', $t_m1);
                        $builder->where('display', 'Y');

                        $sql_ct_type = $builder->get();
                        $row = $sql_ct_type->getRowArray(); // Fetch a single row as an associative array

                        // $sql_ct_type = mysql_query("Select short_description,cs_m_f from casetype where casecode='" . $t_m1 . "' and display='Y'") or die("Error" . __LINE__ . mysql_error());
                        // $row = mysql_fetch_array($sql_ct_type);

                        if ($row && isset($row['short_description'])) {
                            $res_ct_typ = $row['short_description'];
                        } else {
                            $res_ct_typ = '';
                        }

                        if ($row && isset($row['cs_m_f'])) {
                            $res_ct_typ_mf = $row['cs_m_f'];
                        } else {
                            $res_ct_typ_mf = '';
                        }

                        if (trim($t_fil_no) != '')
                            $t_fil_no .= ",<br>";
                        if ($t_m2 == $t_m21)
                            $t_fil_no .= '<font color="#043fff" style=" white-space: nowrap;">' . $res_ct_typ . " " . ltrim($t_m2, '0') . '/' . $t_m3 . '</font>';
                        else
                            $t_fil_no .= '<font color="#043fff" style=" white-space: nowrap;">' . $res_ct_typ . " " . ltrim($t_m2, '0') . '-' . ltrim($t_m21, '0') . '/' . $t_m3 . '</font>';
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

                        // Assuming $t_m1 is already sanitized
                        // $t_m1 = $db->escape($t_m1);

                        $builder = $db->table('master.casetype');
                        $builder->select('short_description, cs_m_f');
                        $builder->where('casecode', $t_m1);
                        $builder->where('display', 'Y');

                        $sql_ct_type = $builder->get();
                        $row = $sql_ct_type->getRowArray(); // Fetch a single row as an associative array

                        // $sql_ct_type = mysql_query("Select short_description,cs_m_f from casetype where casecode='" . $t_m1 . "' and display='Y'") or die("Error" . __LINE__ . mysql_error());
                        // $row = mysql_fetch_array($sql_ct_type);

                        $res_ct_typ = $row['short_description'];
                        $res_ct_typ_mf = $row['cs_m_f'];
                        if (trim($t_fil_no) != '')
                            $t_fil_no .= ",<br>";
                        if ($t_m2 == $t_m21)
                            $t_fil_no .= '<font color="#043fff" style=" white-space: nowrap;">' . $res_ct_typ . " " . ltrim($t_m2, '0') . '/' . $t_m3 . '</font>';
                        else
                            $t_fil_no .= '<font color="#043fff" style=" white-space: nowrap;">' . $res_ct_typ . " " . ltrim($t_m2, '0') . '-' . ltrim($t_m21, '0') . '/' . $t_m3 . '</font>';
                    }
                }
            }

            // Assuming $dn is already sanitized
            // $dn = $db->escape($dn);

            // Create a subquery using Query Builder
            $builder = $db->table('main_casetype_history');
            $builder->select('
                old_registration_number,
                old_registration_year,
                order_date,
                diary_no
            ');
            $builder->where('diary_no', $dn);
            $builder->where('is_deleted', 'f');
            $builder->orderBy('order_date');
            $builder->orderBy('id');

            $query = $builder->get();
            $results = $query->getResultArray(); // Fetch all results as an array

            $newno = [];
            $oldno = '';

            // Process results to build the desired output
            if (!empty($results)) {
                $rowid = 0;
                foreach ($results as $row) {
                    $rowid++;
                    if ($rowid == 1) {
                        $oldno = ($row['old_registration_number'] == '' || $row['old_registration_number'] === null) ? '' :
                            "{$row['old_registration_number']}:{$row['old_registration_year']}:" . date('d-m-Y', strtotime($row['order_date']));
                    }
                    $newno[] = (!empty($row['new_registration_number'])) ? "{$row['new_registration_number']}:{$row['new_registration_year']}:" . date('d-m-Y', strtotime($row['order_date'])) : '';
                }
            }

            // Combine the new numbers
            $newno_grouped = implode(',', array_unique($newno));

            // Prepare the final result
            $row_mc_h = [
                'oldno' => $oldno,
                'newno' => $newno_grouped
            ];

            // $sql_mc_h = "SELECT t.oldno,
            // GROUP_CONCAT(DISTINCT CONCAT(t.new_registration_number,':',t.new_registration_year,':',DATE_FORMAT(t.order_date,'%d-%m-%Y')) ORDER BY t.order_date,t.id ) AS newno FROM
            // (SELECT @rowid:=@rowid+1 AS rowid,`main_casetype_history`.*, IF(@rowid=1,IF(old_registration_number='' OR old_registration_number IS NULL,'',CONCAT(old_registration_number,':',old_registration_year,':',DATE_FORMAT(order_date,'%d-%m-%Y'))),'') AS oldno 
            // FROM `main_casetype_history`, (SELECT @rowid:=0) AS init
            // WHERE `diary_no` = " . $dn . " AND is_deleted='f'
            // ORDER BY `main_casetype_history`.`order_date`,id ) t GROUP BY t.diary_no";

            // $result_mc_h = mysql_query($sql_mc_h) or die(mysql_error() . $sql_mc_h);

            if (count($results) > 0) {
                $cnt = 0;
                // while ($row_mc_h = mysql_fetch_array($result_mc_h)) {
                // echo $row_mc_h['oldno'].":".$row_mc_h['newno'].":<br>";
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

                        // Assuming $t_m1 is already sanitized
                        // $t_m1 = $db->escape($t_m1);
                        $t_m1 = (int) $t_m1;
                        $builder = $db->table('master.casetype');
                        $builder->select('short_description, cs_m_f');
                        $builder->where('casecode', $t_m1);
                        $builder->where('display', 'Y');

                        $sql_ct_type = $builder->get();
                        $row = $sql_ct_type->getRowArray(); // Fetch a single row as an associative array

                        // $sql_ct_type = mysql_query("Select short_description,cs_m_f from casetype where casecode='" . $t_m1 . "' and display='Y'") or die("Error" . __LINE__ . mysql_error());
                        // $row = mysql_fetch_array($sql_ct_type);

                        $res_ct_typ = $row['short_description'];
                        $res_ct_typ_mf = $row['cs_m_f'];
                        if (trim($t_fil_no) != '')
                            $t_fil_no .= ",<br>";
                        if ($t_m2 == $t_m21)
                            $t_fil_no .= '<font color="' . $bgcolor . '" style=" white-space: nowrap;">' . $res_ct_typ . " " . ltrim($t_m2, '0') . '/' . $t_m3 . '</font>';
                        else
                            $t_fil_no .= '<font color="' . $bgcolor . '" style=" white-space: nowrap;">' . $res_ct_typ . " " . ltrim($t_m2, '0') . '-' . ltrim($t_m21, '0') . '/' . $t_m3 . '</font>';
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
                                if ($cnt % 2 == 0)
                                    $bgcolor = "#ff0015";
                                else
                                    $bgcolor = "#ff01c8";

                                // Assuming $t_m1 is already sanitized
                                $t_m1 = $db->escape($t_m1);

                                $builder = $db->table('master.casetype');
                                $builder->select('short_description, cs_m_f');
                                $builder->where('casecode', $t_m1);
                                $builder->where('display', 'Y');

                                $sql_ct_type = $builder->get();
                                $row = $sql_ct_type->getRowArray(); // Fetch a single row as an associative array

                                // $sql_ct_type = mysql_query("Select short_description,cs_m_f from casetype where casecode='" . $t_m1 . "' and display='Y'") or die("Error" . __LINE__ . mysql_error());
                                // $row = mysql_fetch_array($sql_ct_type);

                                $res_ct_typ = $row['short_description'];
                                $res_ct_typ_mf = $row['cs_m_f'];
                                if (trim($t_fil_no) != '')
                                    $t_fil_no .= ",<br>";
                                if ($t_m2 == $t_m21)
                                    $t_fil_no .= '<font color="' . $bgcolor . '" style=" white-space: nowrap;">' . $res_ct_typ . " " . ltrim($t_m2, '0') . '/' . $t_m3 . '</font>';
                                else
                                    $t_fil_no .= '<font color="' . $bgcolor . '" style=" white-space: nowrap;">' . $res_ct_typ . " " . ltrim($t_m2, '0') . '-' . ltrim($t_m21, '0') . '/' . $t_m3 . '</font>';
                            }
                            $t_chk = $t_fn;
                        }
                    }
                }
                // }
            }

            if (trim($t_fil_no) == '' && !empty($row_main['casetype_id'])) {
                // Assuming $row_main is already defined and contains 'casetype_id'
                $casetype_id = $row_main['casetype_id'];

                $builder = $db->table('master.casetype');
                $builder->select('short_description');
                $builder->where('casecode', $casetype_id);

                $sql12 = $builder->get();

                // $sql12 =   "SELECT short_description from casetype where casecode='" . $row_main['casetype_id'] . "'";
                // $results12 = mysql_query($sql12) or die(mysql_error() . " SQL:" . $sql12);

                if ($sql12->getNumRows() > 0) {

                    $row_12 = $sql12->getRowArray(); // Fetch a single row as an associative array

                    $t_fil_no = $row_12['short_description'];
                }
            }
        }

        return $t_fil_no;
    }
}




function get_allocation_judge_advance_b($p1, $cldt, $board_type)
{
    $db = \Config\Database::connect();
    $cldtMMDDYYYY =  date('d-m-Y', strtotime($cldt));
    $cldt =  date('Y-m-d', strtotime($cldt));



    if ($p1 == "M") {
        $m_f = "AND r.m_f = '1'";
        $from_to_dt = ($board_type == 'R') ? "AND r.to_date IS NULL" : "AND r.from_date = '$cldt'";
    } elseif ($p1 == "L") {
        $m_f = "AND r.m_f = '3'";
        $from_to_dt = "AND r.from_date = '$cldt'";
    } elseif ($p1 == "S") {
        $m_f = "AND r.m_f = '4'";
        $from_to_dt = "AND r.from_date = '$cldt'";
    } else {
        $m_f = "AND r.m_f = '2'";
        $from_to_dt = "AND r.from_date = '$cldt'";
    }

    $is_nmd = "SELECT is_nmd FROM master.sc_working_days 
                        WHERE working_date = '$cldt' 
                        AND is_holiday = 0 
                        AND display = 'Y'";

    $query_isnmd = $db->query($is_nmd);
    $ro_isnmd = $query_isnmd->getRowArray();

    if (!empty($ro_isnmd) && isset($ro_isnmd['is_nmd'])) {
        if ($ro_isnmd['is_nmd'] == 1) {

            echo "<span style='color:blue;'><b>Ready to list Regular Day Cases</b></span><br>";
        } elseif ($ro_isnmd['is_nmd'] == 0) {
            echo "<span style='color:green;'><b>Ready to List Misc. Day Cases</b></span><br>";
        }
    } else {
        echo "<span style='color:red;'><b>Not a Working Day</b></span><br>";
        //return;
    }



    if (isset($ro_isnmd['is_nmd']) == 1) {
        $sql = "SELECT
                        CONCAT(p1, ',', p2,
                            CASE WHEN p3 != 0 THEN CONCAT(',', p3) ELSE '' END) AS jcd,
                        jg.p1,
                        jg.p2,
                        jg.p3,
                        j.abbreviation,
                        (
                            SELECT
                                CASE WHEN SNo = 1 THEN 15 ELSE 10 END AS old_limit
                            FROM
                                (
                                    SELECT
                                        ROW_NUMBER() OVER (ORDER BY working_date) AS SNo,
                                        s.*
                                    FROM
                                        master.sc_working_days s
                                    WHERE
                                        EXTRACT(WEEK FROM working_date) = EXTRACT(WEEK FROM CAST('$cldt' AS DATE))
                                        AND is_holiday = 0
                                        AND is_nmd = 1
                                        AND display = 'Y'
                                        AND EXTRACT(YEAR FROM working_date) = EXTRACT(YEAR FROM CAST('$cldt' AS DATE))
                                ) a
                                WHERE
                                working_date = CAST('$cldt' AS DATE)
                            ) AS old_limit
                            FROM
                                judge_group jg
                            LEFT JOIN
                                master.judge j ON j.jcode = jg.p1
                            -- WHERE
                                -- jg.to_dt IS NULL
                                -- AND jg.display = 'Y'
                                -- AND j.is_retired != 'Y'
                            ORDER BY
                                j.judge_seniority";
    }
    if (isset($ro_isnmd['is_nmd']) == 0) {
        $sql = "SELECT
                                CONCAT(p1, ',', p2,
                                    CASE WHEN p3 != 0 THEN CONCAT(',', p3) ELSE '' END) AS jcd,
                                jg.p1,
                                jg.p2,
                                jg.p3,
                                j.abbreviation,
                                jg.fresh_limit,
                                jg.old_limit
                            FROM
                                judge_group jg
                            LEFT JOIN
                                master.judge j ON j.jcode = jg.p1
                            WHERE
                                jg.to_dt IS NULL
                                AND jg.display = 'Y'
                                AND j.is_retired != 'Y'
                            ORDER BY
                                j.judge_seniority";
    }


    $query = $db->query($sql);
    $results = $query->getResultArray();
    if ($results > 0) {

        echo "<br>";
        echo "<div id='prnnt2'>";
        echo "<fieldset>";
        echo "<legend style='text-align:center;color:#4141E0; font-weight:bold;'>ADVANCE LIST ALLOCATION FOR DATED $cldtMMDDYYYY </legend>";
        echo "<table border='1' width='100%' style='border-collapse:collapse; border-color:black; vertical-align: bottom; text-align: left; background:#f6fbf0;' cellspacing=0 class='table table-bordered table-striped'>";
        echo "<tr>
                    <th style='text-align: center; vertical-align: top;'>
                                                <input type='checkbox' name='chkall' id='chkall' value='ALL' onClick='chkall1(this);'>  <b>All</b>
                                                </th>
                                    <th style='text-align: center; vertical-align: top;'>  <b>Hon'ble Judge</b></th>
                                    <th><b>To be Listed</b></th>
                                        <th>  <b>TP</b></th>
                                            <th>  <b>Bail</b></th>
                                            <th>  <b>Old After Notice</b></th>
                                            <th>  <b>Pre Notice Listed</b></th>
                                            <th>  <b>After Notice Listed</b></th>
                                            <th>  <b>Total Listed</b></th>
                                </tr>";

        $srno = 1;
        $old_limit1 = 0;
        $tot_listed = 0;
        $tot_tp = 0;
        $tot_bail = 0;
        $tot_Pre_Notice = 0;
        $tot_After_Notice = 0;
        foreach ($results as $row) {



            $judgesCount = count(explode(',', $row["jcd"]));

            echo "<tr style='vertical-align: bottom;'>
                                    <td style='vertical-align: bottom;'>
                                    <input type='checkbox' id='chkeeed' name='chk' value='" . $row["p1"] . "|" . $row["jcd"] . "|" . $judgesCount . "'>
                               
                                    </td>
                                    <td style='vertical-align: bottom;'>
                                    " . $row['abbreviation'] . "
                                    </td>
                                    <td style='vertical-align: bottom;'>
                                    <select class='misc_selected_box make_zero' name='or_" . $row["p1"] . "' id='or_" . $row["p1"] . "' onchange='calc_tot(this.id)'>
                                        ";
            for ($i = 0; $i < 301; $i++) {
                $selected = ($i == $row["old_limit"]) ? 'selected' : '';
                echo "<option value='" . $i . "' " . $selected . ">" . $i . "</option>";
            }
            echo "</select>
                                    </td>";


            $jcd_p1 = explode(",", $row["jcd"]);

            $sql1 = "SELECT j1,
                                    COUNT(DISTINCT diary_no) AS listed,
                                    SUM(CASE WHEN pre_after_notice = 'TP' THEN 1 ELSE 0 END) AS TP,
                                    SUM(CASE WHEN pre_after_notice = 'Bail' THEN 1 ELSE 0 END) AS Bail,
                                    SUM(CASE WHEN pre_after_notice = 'Old_After_Notice' THEN 1 ELSE 0 END) AS Old_After_Notice,
                                    SUM(CASE WHEN pre_after_notice = 'Pre_Notice' THEN 1 ELSE 0 END) AS Pre_Notice,
                                    SUM(CASE WHEN pre_after_notice = 'After_Notice' THEN 1 ELSE 0 END) AS After_Notice  
                                    FROM
                                    (SELECT h.j1,h.subhead,
                                            CASE 
                                            WHEN h.subhead = 829 THEN 'TP'
                                                WHEN h.subhead = 804 THEN 'Bail'
                                                WHEN h.subhead = 831 THEN 'Old_After_Notice'
                                                WHEN (c.diary_no IS NULL AND (m.fil_no_fh = '' OR m.fil_no_fh IS NULL) AND h.subhead NOT IN (813, 814)) THEN 'Pre_Notice'
                                                ELSE 'After_Notice'
                                            END AS pre_after_notice,
                                            h.diary_no
                                        FROM
                                            advance_allocated h
                                        LEFT JOIN
                                            public.main m ON CAST(h.diary_no AS bigint) = m.diary_no
                                        LEFT JOIN
                                            advanced_drop_note d ON d.diary_no = h.diary_no AND d.cl_date = h.next_dt
                                        LEFT JOIN
                                            public.case_remarks_multiple c ON CAST(c.diary_no AS bigint) = m.diary_no
                                    -- WHERE
                                        --    d.diary_no IS NULL
                                        --    AND h.next_dt = '$cldt'  
                                        --   AND h.j1 = " . $row['p1'] . ",  
                                        --   AND h.board_type = 'J'
                                        --   AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
                                        --   AND (CAST(m.conn_key AS bigint) = m.diary_no OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
                                        GROUP BY
                                            h.j1, h.subhead, c.diary_no, m.fil_no_fh, h.diary_no  
                                    ) h
                                    GROUP BY
                                    h.j1
                                    ORDER BY
                                    h.j1";
            //pr($sql1);



            $res1 = $db->query($sql1);
            $row1 = $res1->getRowArray();


    ?>

            <td style='vertical-align: bottom;'>
                <?php echo $row1['tp']; ?>
                <?php $tot_tp += (int)$row1['tp']; ?>
            </td>
            <td style='vertical-align: bottom;'>
                <?php echo $row1['bail']; ?>
                <?php $tot_bail += (int)$row1['bail']; ?>
            </td>
            <td style='vertical-align: bottom;'>
                <?php echo $row1['old_after_notice']; ?>
                <?php $old_limit1 += (int)$row1['old_after_notice']; ?>
            </td>
            <td style='vertical-align: bottom;'>
                <?php echo $row1['pre_notice']; ?>
                <?php $tot_Pre_Notice += (int)$row1['pre_notice']; ?>
            </td>
            <td style='vertical-align: bottom;'>
                <?php echo $row1['after_notice']; ?>
                <?php $tot_After_Notice += (int)$row1['after_notice']; ?>
            </td>
            <td style='vertical-align: bottom;'>
                <?php echo $row1['listed']; ?>
                <?php $tot_listed += (int)$row1['listed']; ?>
            </td>
            </tr>
        <?php
        }

        ?>

        <tr style="font-weight:bold;">
            <td style="text-align:right;" colspan="2">TOTAL</td>
            <td><?php //echo $old_limit1; 
                ?></td>
            <td><?php echo $tot_tp; ?></td>
            <td><?php echo $tot_bail; ?></td>
            <td><?php echo $old_limit1; ?></td>
            <td><?php echo $tot_Pre_Notice; ?></td>
            <td><?php echo $tot_After_Notice; ?></td>
            <td><?php echo $tot_listed; ?></td>
        </tr>

        </table>
        </fieldset>
        </div>
        <br>
        <input name="prnnt_btn" type="button" id="prnnt_btn" value="Print" class="btn btn-sm">
        <br>

        <input type="button" name="doa" id="doa" value="Do Allottment" class="btn btn-sm">

    <?php
    } else {
        echo "<center>No Records Found</center>";
    }

    ?>


    <?php

    //die;



}


function getNotesAdvocate($diary_no)
{
    $db = \Config\Database::connect();
    $builder = "SELECT
                    a.diary_no,  
                    a.name,      
                    STRING_AGG(CASE WHEN pet_res = 'R' THEN grp_adv END, '' ORDER BY adv_type DESC, pet_res_no ASC) AS r_n,
                    STRING_AGG(CASE WHEN pet_res = 'P' THEN grp_adv END, '' ORDER BY adv_type DESC, pet_res_no ASC) AS p_n
                FROM (
                    SELECT
                        a.diary_no,
                        b.name,
                        STRING_AGG(COALESCE(a.adv, ''), '' ORDER BY pet_res ASC, adv_type DESC, pet_res_no ASC) AS grp_adv,
                        a.pet_res,
                        a.adv_type,
                        a.pet_res_no
                    FROM
                        advocate a
                    LEFT JOIN
                        master.bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y'
                    WHERE
                        a.diary_no = '$diary_no'  
                        AND a.display = 'Y'
                    GROUP BY
                        a.diary_no, b.name, a.pet_res, a.adv_type, a.pet_res_no  
                    ORDER BY
                        pet_res ASC, adv_type DESC, pet_res_no ASC
                ) a
                GROUP BY
                    a.diary_no, a.name;  ";
    $query = $db->query($builder);
    $results = $query->getResultArray();
}

function get_case_details($diary_no)
{
    $c_array = array();
    $db = \Config\Database::connect();
    /* $case_details = "SELECT active_fil_no, year( active_fil_dt ) active_fil_dt , casename,pet_name,res_name,pno,rno FROM main a
left JOIN casetype b ON substr(a.active_fil_no, 1, 2) = b.casecode AND b.display = 'Y' WHERE diary_no = '$diary_no' ";
    $case_details = mysql_query($case_details) or die("Error: " . __LINE__ . mysql_error());
    $r_case_details = mysql_fetch_array($case_details); */

    // Convert MySQL query to PostgreSQL
    $case_details = "
    SELECT 
        active_fil_no, 
        EXTRACT(YEAR FROM a.active_fil_dt) AS active_fil_dt, 
        casename, 
        pet_name, 
        res_name, 
        pno, 
        rno, 
        short_description, 
        fil_dt 
    FROM 
        main a 
    LEFT JOIN 
        master.casetype b 
     ON 
    ( 
        (CASE WHEN SUBSTRING(a.active_fil_no FROM 1 FOR 2) ~ '^[0-9]+$' 
        THEN CAST(SUBSTRING(a.active_fil_no FROM 1 FOR 2) AS INTEGER) 
        ELSE NULL 
        END) = b.casecode 
    )
    AND 
        b.display = 'Y' 
    WHERE 
        diary_no = '$diary_no'
";

    // Execute the query in CodeIgniter (assuming CI4)
    $query = $db->query($case_details);
    //echo $db->getLastQuery();
    $r_case_details = $query->getRowArray();
    //pr($r_case_details);
    if (!empty($r_case_details)) {
        $c_array[0] = $r_case_details['active_fil_no'];
        $c_array[1] = $r_case_details['active_fil_dt'];
        $c_array[2] = $r_case_details['casename'];
        $c_array[3] = $r_case_details['pet_name'];
        $c_array[4] = $r_case_details['res_name'];
        $c_array[5] = $r_case_details['pno'];
        $c_array[6] = $r_case_details['rno'];
        $c_array[7] = $r_case_details['short_description'];
        $c_array[8] = $r_case_details['fil_dt'];
    }
    return $c_array;
}

function getSCHolidays()
{

    $db = \Config\Database::connect();

    $holiday_dates = [];
    $current_year = date('Y');
    $next_year = $current_year + 1;

    $builder = $db->table('master.sc_working_days');
    $builder->select('working_date')
        ->where('is_holiday', 1)
        ->notLike('holiday_description', 'summer vacation')
        ->groupStart()
        ->where("EXTRACT(YEAR FROM working_date)", $current_year)
        ->orWhere("EXTRACT(YEAR FROM working_date)", $next_year)
        ->groupEnd();

    // echo $builder->getCompiledSelect();die;

    $query = $builder->get();
    $result_holidays = $query->getResultArray();

    foreach ($result_holidays as $row_holidays) {
        $holiday_dates[] = date("d-m-Y", strtotime($row_holidays['working_date']));
    }

    return $holiday_dates;
}

function getNextHolidays()
{

    $db = \Config\Database::connect();
    $query = $db->table('sc_working_days')
        ->select("DATE_FORMAT(working_date, '%e-%c-%Y') AS holidays")
        ->where('working_date >=', date('Y-m-d'))
        ->where('display', 'Y')
        ->where('is_holiday', 1)
        ->get();

    $result = $query->getResultArray();
    return $result;
}

if (!function_exists('getNextCourtWorkingDate')) {
    function getNextCourtWorkingDate($date)
    {
        $db = \Config\Database::connect();
        $query = $db->table('sc_working_days')
            ->select('working_date')
            ->where('working_date >=', $date)
            ->where('display', 'Y')
            ->where('is_holiday', 0)
            ->orderBy('working_date', 'asc')
            ->get();

        $result = $query->getRowArray();
        return $result;
    }
}


function in_array_any($needles, $haystack)
{
    // Ensure both $needles and $haystack are arrays
    if (!is_array($needles) || !is_array($haystack)) {
        return false; // Return false if either is not an array
    }

    // Find the intersection between $needles and $haystack
    $result = array_intersect($needles, $haystack);

    // Return true if there is at least one common element
    return !empty($result);
}

function revertDate_hiphen($date)
{
    $date = explode('-', $date);
    $date = $date[2] . '-' . $date[1] . '-' . $date[0];
    return $date;
}

function displayUsertype($type_int)
{
    switch ($type_int) {
        case 1:
            return "Super-User";
            break;
        case 2:
            return "Section Officer";
            break;
        case 3:
            return "Data Entry Operator";
            break;
        case 4:
            return "Receipt";
            break;
        case 5:
            return "Filing Clerk";
            break;
        case 6:
            return "Enquiry";
            break;
        case 7:
            return "Dispatch Judicial(Ordinary)";
            break;
        case 8:
            return "Scrutiny";
            break;
        case 9:
            return "Default";
            break;
        case 10:
            return "Entry";
            break;
        case 11:
            return "Dealing Asst.";
            break;
        case 12:
            return "Listing";
            break;
        case 13:
            return "Court Reader";
            break;
        case 14:
            return "Personal Asst.";
            break;
        case 15:
            return "Loose Doc.";
            break;
        case 16:
            return "SW";
            break;
        case 17:
            return "Record Room(P)";
            break;
        case 18:
            return "Copying";
            break;
        case 19:
            return "Filing Dispatch";
            break;
        case 20:
            return "Registrar";
            break;
        case 21:
            return "HC Monitoring";
            break;
        case 22:
            return "Paper Book";
            break;
        case 23:
            return "HC Leagal Service";
            break;
        case 24:
            return "Old User";
            break;
        case 25:
            return "Receipt Requisition";
            break;
        case 26:
            return "Translator";
            break;
        case 27:
            return "Dispatch Judicial(Requisition)";
            break;
        case 28:
            return "Dispatch Judicial(Registry)";
            break;
        case 29:
            return "Private Sec.";
            break;
        case 30:
            return "Stenographer";
            break;
        case 31:
            return "Asst. Registrar";
            break;
        case 32:
            return "Reader to R/AR/DR";
            break;
        case 33:
            return "Deputy Reg.";
            break;
        case 34:
            return "Head Asst.";
            break;
        case 35:
            return "Decree";
            break;
        case 36:
            return "Data Transfer";
            break;
        case 37:
            return "Lok Adalat";
            break;
        case 38:
            return "PhotoStat";
            break;
        case 39:
            return "Establishment";
            break;
        case 40:
            return "Filing Scan";
            break;
        case 41:
            return "Record Room(D)";
            break;
        case 42:
            return "Complaint";
            break;
        case 43:
            return "Inspection";
            break;
        case 44:
            return "Mediation";
            break;
        case 45:
            return "Supreme Court User";
            break;
        case 46:
            return "Court Manager";
            break;
        case 47:
            return "Checker";
            break;
        case 48:
            return "Appearance Clerk";
            break;
        case 49:
            return "Law Clerk";
            break;
        case 50:
            return "Incharge";
            break;
        case 51:
            return "Library";
            break;
        case 52:
            return "Joint Registrar";
            break;
        case 53:
            return "Protocol Officer";
            break;
        case 54:
            return "Asst Protocol Officer";
            break;
        case 55:
            return "Driver";
            break;
        case 56:
            return "Peon";
            break;
        case 57:
            return "Pension";
            break;
        case 58:
            return "Elimination Cell";
            break;
        case 59:
            return "Disposal Cell";
            break;
        case 60:
            return "Record Supplier";
            break;
        case 61:
            return "Compliance DA";
            break;
        case 62:
            return "Hardware";
            break;
        case 63:
            return "Software";
            break;
        case 64:
            return "Personal Staff";
            break;
        case 65:
            return "Accounts";
            break;
        case 66:
            return "Record Room(A)";
            break;
        case 67:
            return "Stationary";
            break;
        case 68:
            return "Works User";
            break;
        case 69:
            return "Dispatch Admin";
            break;
        case 70:
            return "Copy N Disp";
            break;
        case 71:
            return "Creche";
            break;
        case 72:
            return "EPABX Operator";
            break;
        case 73:
            return "Mechanic";
            break;
        case 74:
            return "Caretaker";
            break;
        case 75:
            return "Sweeper";
            break;
        case 76:
            return "Chowkidar";
            break;
        case 77:
            return "Program Manager";
            break;
        case 78:
            return "ILR";
            break;
        case 79:
            return "DE User";
            break;
        case 80:
            return "Budget User";
            break;
        case 81:
            return "Confidential User";
            break;
        case 82:
            return "Vigilance User";
            break;
        case 83:
            return "DJ Inspection";
            break;
        case 84:
            return "Exam User";
            break;
        case 85:
            return "Sr. Cmp Prg Asst";
            break;
        case 86:
            return "Office Typist";
            break;
        case 87:
            return "SAT User";
            break;
        case 88:
            return "RTI User";
            break;
        case 89:
            return "Director";
            break;
        case 90:
            return "Add. Director";
            break;
        case 91:
            return "Asst. Editor(ILR)";
            break;
        case 92:
            return "Binding";
            break;
        case 93:
            return "Digitalization Clerk";
            break;
        case 94:
            return "Judge";
            break;
        case 95:
            return "Vendor";
            break;
        case 96:
            return "Examiner";
            break;
        case 97:
            return "Segrigating Team";
            break;
        default:
            return "NA";
            break;
    }
}

function getDisp_to_side_flag($type_int)
{
    switch ($type_int) {
        case 1:
            return "SUP";
            break;
        case 2:
            return "SO";
            break;
        case 3:
            return "DEO";
            break;
        case 4:
            return "REC";
            break;
        case 5:
            return "FC";
            break;
        case 6:
            return "ENQ";
            break;
        case 7:
            return "DISJO";
            break;
        case 8:
            return "CHK";
            break;
        case 9:
            return "DEFC";
            break;
        case 10:
            return "EC";
            break;
        case 11:
            return "DA";
            break;
        case 12:
            return "LIS";
            break;
        case 13:
            return "RDR";
            break;
        case 14:
            return "PA";
            break;
        case 15:
            return "LD";
            break;
        case 16:
            return "SW";
            break;
        case 17:
            return "RKDRP";
            break;
        case 18:
            return "CPY";
            break;
        case 19:
            return "FDC";
            break;
        case 20:
            return "REG";
            break;
        case 21:
            return "HCMN";
            break;
        case 22:
            return "PPR";
            break;
        case 23:
            return "HCLS";
            break;
        case 24:
            return "OU";
            break;
        case 25:
            return "RECR";
            break;
        case 26:
            return "TRN";
            break;
        case 27:
            return "DISJR";
            break;
        case 28:
            return "DISJRG";
            break;
        case 29:
            return "PS";
            break;
        case 30:
            return "STNO";
            break;
        case 31:
            return "AREG";
            break;
        case 32:
            return "RDRREG";
            break;
        case 33:
            return "DREG";
            break;
        case 34:
            return "HA";
            break;
        case 35:
            return "DCR";
            break;
        case 36:
            return "DTRN";
            break;
        case 37:
            return "LA";
            break;
        case 38:
            return "PHTST";
            break;
        case 39:
            return "ESTB";
            break;
        case 40:
            return "FSCNC";
            break;
        case 41:
            return "RKDRD";
            break;
        case 42:
            return "CMPLNT";
            break;
        case 43:
            return "INCP";
            break;
        case 44:
            return "MED";
            break;
        case 45:
            return "SUCRT";
            break;
        case 46:
            return "CRTMNG";
            break;
        case 47:
            return "CKR";
            break;
        case 48:
            return "APC";
            break;
        case 49:
            return "LWC";
            break;
        case 50:
            return "INC";
            break;
        case 51:
            return "LIB";
            break;
        case 52:
            return "JRG";
            break;
        case 53:
            return "PCO";
            break;
        case 54:
            return "APCO";
            break;
        case 55:
            return "DRI";
            break;
        case 56:
            return "PEON";
            break;
        case 57:
            return "PNSN";
            break;
        case 58:
            return "ELE";
            break;
        case 59:
            return "DISP";
            break;
        case 60:
            return "RSUP";
            break;
        case 61:
            return "CMPD";
            break;
        case 62:
            return "HDW";
            break;
        case 63:
            return "SFW";
            break;
        case 64:
            return "PST";
            break;
        case 65:
            return "ACC";
            break;
        case 66:
            return "RRA";
            break;
        case 67:
            return "STN";
            break;
        case 68:
            return "WRK";
            break;
        case 69:
            return "DISA";
            break;
        case 70:
            return "CND";
            break;
        case 71:
            return "CRE";
            break;
        case 72:
            return "EPA";
            break;
        case 73:
            return "MCA";
            break;
        case 74:
            return "CART";
            break;
        case 75:
            return "SWP";
            break;
        case 76:
            return "CHO";
            break;
        case 77:
            return "PMG";
            break;
        case 78:
            return "ILR";
            break;
        case 79:
            return "DEU";
            break;
        case 80:
            return "BGT";
            break;
        case 81:
            return "CONF";
            break;
        case 82:
            return "VIG";
            break;
        case 83:
            return "DJI";
            break;
        case 84:
            return "EXM";
            break;
        case 85:
            return "SCPA";
            break;
        case 86:
            return "OFT";
            break;
        case 87:
            return "SAT";
            break;
        case 88:
            return "RTI";
            break;
        case 89:
            return "DIR";
            break;
        case 90:
            return "ADIR";
            break;
        case 91:
            return "AEIL";
            break;
        case 92:
            return "BIN";
            break;
        case 93:
            return "DCL";
            break;
        case 94:
            return "JUD";
            function get_purpose($purpose_code)
            {
                $purpose = "";
                if ($purpose_code != "") {
                    $sql_p = "SELECT purpose FROM listing_purpose WHERE code='" . $purpose_code . "'";
                    $result_p = mysql_query($sql_p) or die(mysql_error());
                    $row_p = mysql_fetch_array($result_p);
                    $purpose = $row_p['purpose'];
                }
                return $purpose;
            }
            break;
        case 95:
            return "VND";
            break;
        case 96:
            return "EMR";
            break;
        case 97:
            return "SEG";
            break;
        default:
            return "NA";
            break;
    }
}

function f_get_reg_no($diary_no)
{
    $db = \Config\Database::connect();
    $builder = $db->table('main m');
    $builder->select('m.diary_no, c1.short_description, m.active_reg_year, m.active_fil_no')
        ->join('master.casetype c1', 'm.active_casetype_id = c1.casecode', 'left')
        ->where('m.diary_no', $diary_no);

    $query = $builder->get();
    if ($query->getNumRows() > 0) {
        $row = $query->getRowArray();
        $filno_array = explode("-", $row['active_fil_no']);

        if (empty($filno_array[0])) {
            $fil_no_print = substr_replace($row['diary_no'], '-', -4, 0);
        } else {
            $fil_no_print = $row['short_description'] . "/" . ltrim($filno_array[1], '0');
            if (!empty($filno_array[2]) && $filno_array[1] != $filno_array[2]) {
                $fil_no_print .= "-" . ltrim($filno_array[2], '0');
            }

            $fil_no_print .= "/" . $row['active_reg_year'];
        }

        return $fil_no_print;
    }
    return null;
}

function get_diary_rec_date($diary_no)
{
    $db = \Config\Database::connect();
    $builder = $db->table('main m');
    $builder->select('m.diary_no_rec_date')
        ->where('m.diary_no', $diary_no);
    $query = $builder->get();
    if ($query->getNumRows() > 0) {
        $row = $query->getRowArray();
        $data = $row['diary_no_rec_date'];
        return $data;
    }
    return null;
}

function get_da_sec_code($data, $type)
{
    $db = \Config\Database::connect();
    $builder = null;
    if ($type === 'users') {
        $builder = $db->table('master.users a')
            ->select('b.section_name')
            ->join('master.usersection b', 'a.section = b.id')
            ->where('a.usercode', $data);
    } elseif ($type === 'usersection') {
        $builder = $db->table('master.usersection b')
            ->select('b.section_name')
            ->where('b.id', $data)
            ->where('b.display', 'Y');
    }

    if (!$builder) {
        return null;
    }

    $query = $builder->get();
    if ($query->getNumRows() > 0) {
        $row = $query->getRowArray();
        return $row['section_name'] ?? null;
    }

    return null;
}


function get_case_detailsNew($diary_no, $dn, $dyr, $type)
{
    $db = \Config\Database::connect();

    // Query to fetch case details
    $query = $db->table('main as m')
        ->select("c.casename, c.short_description, 
                  COALESCE(NULLIF(m.active_fil_no, ''), m.fil_no) as active_fil_no,
                  m.active_reg_year")
        ->join(
            'master.casetype as c',
            "COALESCE(NULLIF(m.active_casetype_id, NULL), m.casetype_id) = c.casecode",
            'inner'
        )
        ->where('m.diary_no', $diary_no)
        ->get();

    $result = $query->getRowArray();
    //pr($result);
    if (!$result) {
        return null; // Return null if no case details found
    }
    if ($type === 'H') {
        $active_fil_no = '';
        if (empty($result['active_fil_no']) || $result['active_fil_no'] == '0') {
            // Default active file number
            $active_fil_no = ' D.no.' . $dn . '/' . $dyr;
        } else {
            $file_no_parts = explode('-', $result['active_fil_no']); // Split by '-'

            // Check if there are exactly 2 parts after splitting
            if (count($file_no_parts) == 2) {
                $reg_no = ($file_no_parts[0] === $file_no_parts[1])
                    ? $file_no_parts[0]  // If parts are identical, use the first part
                    : $file_no_parts[1]; // Otherwise, use the second part
            } else {
                // If the format is unexpected (not two parts), use the whole number as the reg_no
                $reg_no = $result['active_fil_no'];
            }

            // Format the active file number
            $active_fil_no = ' NO. ' . $reg_no . '/' . $result['active_reg_year'];
        }

        // Return the formatted case name and active file number
        return $result['casename'] . $active_fil_no;
    } else if ($type === 'AF') {
        $active_fil_no = '';
        if (empty($result['active_fil_no']) || $result['active_fil_no'] == '0') {
            // Default active file number
            $active_fil_no = $result['short_description'] . ' D.no.' . $dn . '/' . $dyr;
        } else {
            $a = explode('-', substr($result['active_fil_no'], 3));

            $reg_no = '';

            // Check if there are at least two parts after the explode
            if (count($a) >= 2) {
                if ($a[0] == $a[1]) {
                    // If both parts are the same, use the first part
                    $reg_no = $a[0];
                } else {
                    // If they are different, use the second part
                    $reg_no = $a[1];
                }
            } else {
                // If there is no '-' or only one part, use the entire value after the 3rd character
                $reg_no = substr($result['active_fil_no'], 3);
            }

            $active_fil_no = $result['short_description'] . ' ' . $reg_no . '/' . $result['active_reg_year'];
        }

        return $active_fil_no;
    }
}


function get_main_case_af_verify($diary_no)
{
    $db = \Config\Database::connect();

    // Query to fetch case details
    $query = $db->table('conct as a')
        ->select("a.conn_key")
        ->where('a.diary_no', $diary_no)
        ->where('a.usercode !=', '9666')
        ->where('a.list !=', 'N')
        ->get();

    $result = $query->getRowArray();

    if (!$result) {
        return null;
    }
    return $result;
}

function get_rgo_default($diary_no)
{
    $db = \Config\Database::connect();

    // Query to fetch case details
    $query = $db->table('rgo_default as a')
        ->select("a.fil_no2")
        ->where('a.fil_no', $diary_no)
        ->get();

    $result = $query->getRowArray();

    if (!$result) {
        return null;
    }
    return $result;
}

function get_docdetails($diary_no)
{
    $db = \Config\Database::connect();

    // Query to fetch document details
    $query = $db->table('docdetails as a')
        ->select("a.docnum, a.docyear, b.docdesc, a.iastat")
        ->join('master.docmaster as b', 'a.doccode = b.doccode AND a.doccode1 = b.doccode1')
        ->where('a.display', 'Y')
        ->where('a.doccode', '8')
        ->where('b.display', 'Y')
        ->where('a.diary_no', $diary_no)
        // ->orderBy("FIELD(a.iastat, 'P', 'D')", '', false) // Use orderBy with FIELD equivalent in 
        //pr($query->getCompiledSelect());
        ->get();

    $result = $query->getRowArray();

    if (!$result) {
        return null;
    }
    return $result;
}

function get_pet_adv($diary_no)
{
    $db = \Config\Database::connect();

    // Query to fetch advocate details with concatenated adv values
    $query = $db->table('advocate as a')
        ->select("b.aor_code, b.name, string_agg(a.adv, ', ' ORDER BY a.pet_res_no) as tot_pet")
        ->join('master.bar as b', 'a.advocate_id = b.bar_id')
        ->where('a.display', 'Y')
        ->where('a.diary_no', $diary_no)
        ->where('a.pet_res', 'P')
        ->groupBy('b.aor_code, b.name')  // Corrected GROUP BY
        //->orderBy('a.pet_res_no')  // Corrected ORDER BY
        ->get();  // Use get() to fetch the results

    // Fetch all results as an array
    $result = $query->getResultArray();

    if (empty($result)) {
        return null;
    }

    return $result;  // Return the result as an array of rows
}





if (!function_exists('lower_court_conct')) {

    function lower_court_conct($dairy_no)
    {
        $db = \Config\Database::connect();

        // Check active_casetype_id from the 'main' table
        $res_chk_casetype = $db->table('main')
            ->select('active_casetype_id')
            ->where('diary_no', $dairy_no)
            ->get()
            ->getRowArray();

        $is_order_challenged = '';
        if (
            $res_chk_casetype['active_casetype_id'] != 25 &&
            $res_chk_casetype['active_casetype_id'] != 26 &&
            $res_chk_casetype['active_casetype_id'] != 7 &&
            $res_chk_casetype['active_casetype_id'] != 8
        ) {
            $is_order_challenged = "AND is_order_challenged = 'Y'";
        }

        // Query
        $builder = $db->table('lowerct a');
        $builder->select("
            a.lct_dec_dt, 
            a.l_dist, 
            a.ct_code, 
            a.l_state, 
            b.name AS state_name,
            CASE 
                WHEN a.ct_code = 3 THEN (
                    SELECT s.name 
                    FROM master.state s 
                    WHERE s.id_no = a.l_dist AND s.display = 'Y'
                )
                ELSE (
                    SELECT CONCAT(c.agency_name, ', ', c.address) 
                    FROM master.ref_agency_code c 
                    WHERE c.cmis_state_id = a.l_state AND c.id = a.l_dist AND c.is_deleted = 'f'
                )
            END AS agency_name,
            STRING_AGG(a.lct_casetype::TEXT, ',' ORDER BY a.lower_court_id) AS lct_casetype,
            STRING_AGG(a.lct_caseno::TEXT, ',' ORDER BY a.lower_court_id) AS lct_caseno,
            STRING_AGG(a.lct_caseyear::TEXT, ',' ORDER BY a.lower_court_id) AS lct_caseyear,
            STRING_AGG(
                CASE 
                    WHEN a.ct_code = 4 THEN (
                        SELECT short_description 
                        FROM master.casetype ct 
                        WHERE ct.casecode = a.lct_casetype AND ct.display = 'Y'
                    )
                    ELSE (
                        SELECT type_sname 
                        FROM master.lc_hc_casetype d 
                        WHERE d.lccasecode = a.lct_casetype AND d.display = 'Y'
                    )
                END, ',' ORDER BY a.lower_court_id
            ) AS type_sname
        ");

        $builder->join('master.state b', 'a.l_state = b.id_no AND b.display = \'Y\'', 'left');
        $builder->join(
            'master.ref_agency_code c',
            'c.cmis_state_id = a.l_state AND c.id = a.l_dist AND c.is_deleted = \'f\'',
            'left'
        );
        $builder->join(
            'master.police p',
            'p.policestncd = a.polstncode 
            AND p.cmis_state_id = a.l_state 
            AND p.cmis_district_id = a.l_dist 
            AND p.display = \'Y\'',
            'left'
        );
        $builder->join('main e', 'e.diary_no = a.diary_no');
        $builder->where('a.diary_no', $dairy_no);
        $builder->where('a.lw_display', 'Y');

        // Add optional condition dynamically
        if ($is_order_challenged) {
            $builder->where('a.is_order_challenged', 'Y');
        }

        $builder->groupBy('a.lct_dec_dt, a.l_dist, a.ct_code, a.l_state, b.name,a.lower_court_id',);
        $builder->orderBy('a.lower_court_id');
        // $s = $builder->getCompiledSelect();
        // pr($s);
        $query = $builder->get();

        // Check if the query returns any rows
        if ($query->getNumRows() > 0) {
            $outer_array = [];
            foreach ($query->getResultArray() as $row) {
                $inner_array = [];
                $inner_array[0] = $row['lct_dec_dt'];
                $inner_array[1] = $row['state_name']; // Adjusted column name to match the alias
                $inner_array[2] = $row['agency_name'];
                $inner_array[3] = $row['type_sname'];
                $inner_array[4] = $row['lct_caseno'];
                $inner_array[5] = $row['lct_caseyear'];
                $inner_array[6] = $row['lct_casetype'];
                $outer_array[] = $inner_array;
            }
            return $outer_array;
        }

        // Return an empty array if no rows are found
        return [];
    }
}



function get_stage($stage_code, $mainhead)
{
    $stage = "";
    if ($stage_code != "") {
        if ($mainhead == "M") {
            $row_p = is_data_from_table('master.subheading',  " stagecode= $stage_code ", 'stagename', $row = '');
            $stage = $row_p['stagename'] ?? '';
        }
        if ($mainhead == "F") {

            $row_p = is_data_from_table('master.submaster',  " id=$stage_code ", '*', $row = '');
            if (!empty($row_p)) {
                if ($row_p['subcode1'] > 0 and $row_p['subcode2'] == 0 and $row_p['subcode3'] == 0 and $row_p['subcode4'] == 0)
                    $stage =  $row_p['sub_name1'];
                elseif ($row_p['subcode1'] > 0 and $row_p['subcode2'] > 0 and $row_p['subcode3'] == 0 and $row_p['subcode4'] == 0)
                    $stage =  $row_p['sub_name1'] . " : " . $row_p['sub_name4'];
                elseif ($row_p['subcode1'] > 0 and $row_p['subcode2'] > 0 and $row_p['subcode3'] > 0 and $row_p['subcode4'] == 0)
                    $stage =  $row_p['sub_name1'] . " : " . $row_p['sub_name2'] . " : " . $row_p['sub_name4'];
                elseif ($row_p['subcode1'] > 0 and $row_p['subcode2'] > 0 and $row_p['subcode3'] > 0 and $row_p['subcode4'] > 0)
                    $stage =  $row_p['sub_name1'] . " : " . $row_p['sub_name2'] . " : " . $row_p['sub_name3'] . " : " . $row_p['sub_name4'];
            }
        }
    }
    return $stage;
}


function check_list_printed($roster_id, $mf, $part, $main_supp, $next_dt)
{
    if (!empty($next_dt)) {
        $next_dt = " AND next_dt = '$next_dt'";
    } else {
        $next_dt = " AND next_dt  IS NULL ";
    }
    $result = is_data_from_table('cl_printed',  " roster_id = $roster_id AND m_f = '$mf' AND part = $part AND main_supp = $main_supp $next_dt AND display = 'Y' ", '*', $row = 'Q');
    if (!empty($result))
        $list_printed = "YES";
    else
        $list_printed = "NO";
    return $list_printed;
}


function get_case_remarks($dn, $cldate, $jcodes, $clno)
{
    $db = \Config\Database::connect();

    $builder = $db->table('case_remarks_multiple c');
    $builder->select([
        'name',  // Use MAX to get the first name in each group
        'h.cat_head_id',
        'c.cl_date',
        'c.jcodes',
        'c.status',
        "STRING_AGG(
            CONCAT(h.head,
            CASE WHEN c.head_content != '' THEN
                CONCAT(' [Rem:', c.head_content, ']')
            ELSE '' END), ', ')
        AS crem",
        "TO_CHAR(e_date, 'DD/MM/YYYY HH24:MI') AS edate",
        "STRING_AGG(
            CONCAT(c.r_head, '|', c.head_content, '^^'), ''
        ) AS caseval",
        'c.mainhead',
        'c.clno'
    ]);
    $builder->join('master.case_remarks_head h', 'c.r_head = h.sno');
    $builder->join('master.users', 'c.uid = users.usercode');
    $builder->where('c.diary_no', $dn);
    $builder->where('c.cl_date', $cldate);
    $builder->where('c.jcodes', $jcodes);
    $builder->where('c.clno', $clno);
    $builder->groupBy('name');
    $builder->groupBy('h.cat_head_id', 'c.cl_date', 'c.jcodes', 'c.status', 'h.priority', 'e_date', 'c.mainhead', 'c.clno');
    $builder->groupBy('c.cl_date');
    $builder->groupBy('c.jcodes');
    $builder->groupBy('c.status');
    $builder->groupBy('h.priority');
    $builder->groupBy('e_date', 'c.mainhead', 'c.clno');
    $builder->groupBy('c.mainhead');
    $builder->groupBy('c.clno');
    //$builder->orderBy('h.priority');
    //echo $builder->getCompiledSelect();

    $query = $builder->get();
    $row_cr = $query->getRowArray();

    $cval = "";
    if (!empty($row_cr)) {
        $crem = $row_cr['crem'];
    } else {
        $crem = '';
    }


    //$sql_his="select if(head_content <> '' ,concat(head,'[Rem:',head_content,']'), head) as remark,concat(name,'[',section_name,']') as uname,DATE_FORMAT(e_date, '%d/%m/%Y %H:%i') as edate from case_remarks_multiple_history cr join case_remarks_head ch on cr.r_head= ch.sno join users on cr.uid=users.usercode join usersection on users.section=usersection.id and fil_no= ".$dn."  and cl_date='".$cldate."'  order by e_date desc";
    //$rs_his=mysql_query($sql_his);
    //$sdf=mysql_num_rows($rs_his);

    $builder = $db->table('case_remarks_multiple_history cr');

    $builder->select([
        "CASE WHEN head_content <> '' THEN CONCAT(head, '[Rem:', head_content, ']') ELSE head END AS remark",
        "CONCAT(name, '[', section_name, ']') AS uname",
        "TO_CHAR(e_date, 'DD/MM/YYYY HH24:MI') AS edate"
    ]);
    $builder->join('master.case_remarks_head ch', 'cr.r_head = ch.sno');
    $builder->join('master.users', 'cr.uid = users.usercode');
    $builder->join('master.usersection', 'users.section = usersection.id');
    $builder->where('fil_no', $dn);
    $builder->where('cl_date', $cldate);
    $builder->orderBy('e_date', 'DESC');

    $query = $builder->get();
    $rs_his = $query->getResultArray();
    $sdf = $query->getNumRows();;
    $sno = 1;
    $cr_his = "";

    foreach ($rs_his as $row_his) {
        $h_remark = $row_his['remark'];
        $h_uname = $row_his['uname'];
        $h_edate = $row_his['edate'];


        $cr_his = $cr_his . $h_remark . " by " . $h_uname . " on " . $h_edate . "\n";

        $sno++;
    }
    $row_cr1 = '';
    if (!empty($row_cr['name']) && $row_cr['name'] != '') {
        $row_cr1 = '(' . $row_cr['name'] . ') on ';
    }
    $edate = $row_cr['edate'] ?? '';
    $name = $row_cr['name'] ?? '';
    return $crem . "\n " . $row_cr1 . "" . $edate . "?" . $cr_his . "?" . $sdf . "?" . $name;

    //$cr_0=$crem."?".$cr_his;
    //return $cr_0;
}


function check_drop($diaryno, $cldate, $rosterid, $clno)
{
    $db = \Config\Database::connect();
    $drop_note = "";

    $builder = $db->table('drop_note d');
    $builder->select(['d.*', 'r.courtno']);
    $builder->join('master.roster r', 'd.roster_id = r.id');
    $builder->where('d.diary_no', $diaryno);
    $builder->where('clno', $clno);
    $builder->where('d.display', 'Y');
    $builder->where('d.cl_date', $cldate);
    $builder->where('d.roster_id', $rosterid);
    $builder->orderBy('d.ent_dt', 'ASC');

    $query = $builder->get();
    $result_drop = $query->getResultArray();

    if (!empty($result_drop))
        $drop_note = " <br><font color='red' style='font-size:11px;font-weight:bold;'>Drop Case</font>";
    foreach ($result_drop as $row_drop) {
        $drop_note .= " <br>[<font color='red' style='font-size:11px;font-weight:bold;'>Court No. " . $row_drop["courtno"] . " - CL.NO. : " . $row_drop["clno"] . " - " . $row_drop["nrs"] . "</font>]";
        $t_drp_jname = stripslashes($row_drop["jnm"]);
    }
    return $drop_note;
}


function getCaseStatusFlag()
{
    $db = \Config\Database::connect();
    $builder = $db->table('master.case_status_flag');
    $builder->select(['display_flag', 'always_allowed_users']);
    $builder->where('date(to_date) is NULL');
    $builder->where('flag_name', 'case_updated_for_but_not_listed_date');

    $query = $builder->get();
    return $result_array = $query->getRowArray();
}

function get_purpose($purpose_code)
{
    $purpose = "";
    if ($purpose_code != "") {

        $row_p = is_data_from_table('master.listing_purpose',  " code= $purpose_code ", 'purpose', $row = '');
        $purpose = $row_p['purpose'] ?? '';
    }
    return $purpose;
}


function get_serve_type($serve_id)
{

    $res_sql = is_data_from_table('master.tw_serve',  " id= $serve_id and display='Y' ", 'name', $row = '');

    return (!empty($res_sql)) ? $res_sql['name'] : '';
}


function get_district($district)
{
    $s_det = is_data_from_table('master.state',  " id_no= $district and display='Y' ", 'name', $row = '');
    return $r_district = (!empty($s_det)) ? $s_det['name'] : '';
}






function send_to_name($id_val, $tw_sn_to)
{
    
    $db = \Config\Database::connect();
    if ($id_val == 2) {
        $r_sql = is_data_from_table('master.tw_send_to',  " id= $tw_sn_to and display='Y' ", 'desg', $row = '');
    } else if ($id_val == 1) {
        $r_sql = is_data_from_table('master.bar',  " bar_id= $tw_sn_to ", "concat(name,'-',aor_code) desg", $row = '');
    } else if ($id_val == 3) {
        $builder = $db->table('lowerct a');
        $builder->select([
            "COALESCE(
                    CASE WHEN ct_code = 3 THEN
                        (SELECT name FROM master.state s WHERE s.id_no = a.l_dist AND display = 'Y')
                    ELSE
                        (SELECT agency_name FROM master.ref_agency_code c WHERE c.cmis_state_id = a.l_state AND c.id = a.l_dist AND is_deleted = 'f')
                    END,
                    ''
                ) AS desg",
            'b.name'
        ]);
        $builder->join('master.state b', 'a.l_state = b.id_no');
        $builder->where([
            'lower_court_id' => $tw_sn_to,
            'lw_display' => 'Y',
            'b.display' => 'Y'
        ]);
        $builder->groupBy('l_state');
        $builder->groupBy('l_dist');
        $builder->groupBy('a.ct_code');
        $builder->groupBy('b.name');
         
        $query = $builder->get();
        $r_sql = $query->getRowArray();
    }


    if (!empty($r_sql))
        return $r_sql['desg'];
    else
        return '';
}

function send_to_address($send_to_type, $tw_sn_to)
{
    $db = \Config\Database::connect();
    if ($send_to_type == 3) {
        $sql = "select address as ag_adrss from lowerct a
                join master.ref_agency_code r on a.l_state=r.cmis_state_id AND r.id = a.l_dist AND is_deleted = 'f'
                join master.state s  on s.id_no = a.l_dist AND display = 'Y'
                WHERE lower_court_id='$tw_sn_to' AND ct_code!=3 AND lw_display = 'Y' AND is_deleted = 'f'";
    }
    $query = $db->query($sql);
    $r_sql = $query->getRowArray();
    return (!empty($r_sql) && !empty($r_sql['ag_adrss']) ) ? $r_sql['ag_adrss'] : '';
}

function get_section($dairy_no)
{
    $db = \Config\Database::connect();
    $section = "SELECT section_name
                FROM main a
                JOIN master.users b ON a.dacode = b.usercode
                JOIN master.usersection c ON c.id = b.section
                WHERE diary_no = '$dairy_no'
                AND b.display = 'Y'
                AND c.display = 'Y'";
    $query =  $db->query($section);
    $res_section = $query->getRowArray();
    return  (!empty($res_section) && !empty($res_section['section_name'])) ? $res_section['section_name'] : '';
}

function get_advocate_address($tw_sn_to)
{
    $db = \Config\Database::connect();
    $get_address = "Select caddress from master.bar where bar_id='$tw_sn_to'";
    $query =  $db->query($get_address);
    $r_get_address = $query->getRowArray()['caddress'];
    return  (!empty($r_get_address) && !empty($r_get_address['caddress'])) ? $r_get_address['caddress'] : '';
}



function get_state($state)
{
    $s_det = is_data_from_table('master.state',  " id_no= $state and display='Y' ", 'name', '');
    return $r_state = (!empty($s_det)) ?  $s_det['name'] : '';
}

function get_tehsil_frm_district($district)
{
    $get_state_dis = is_data_from_table('master.state', " id_no='$district' and display='Y' ", 'state_code,district_code', '');

    $s_det = is_data_from_table('master.state', " state_code='$get_state_dis[state_code]' and district_code='$get_state_dis[district_code]' and village_code=0 and display='Y'", 'name,id_no', '');

    $o_array = array();

    foreach ($s_det as $row) {
        $i_array = array();
        $i_array[0] = $row['id_no'];
        $i_array[1] = $row['name'];
        $o_array[] = $i_array;
    }
    return $o_array;
}



function typenoyr1($m_casenum)
{
    $m_str1 = substr($m_casenum, 2, 3);
    $m_str1 = (int) $m_str1;
    //$sqlbh3="select * from casetype where casecode=$m_str1";
    //$result_sqlbh3 = mysql_query($sqlbh3) or die(mysql_error()." SQL:".$sqlbh3);

    $rowbh3 = is_data_from_table('casetype', " casecode=$m_str1 ", '*', $row = '');

    if (!empty($rowbh3)) {
        //$rowbh3 = mysql_fetch_array($result_sqlbh3);
        $mm_casetype = $rowbh3['skey'];
        $m_str2 = substr($m_casenum, 5, 5);
        $mm_casenum = (int) $m_str2;
        $m_str3 = substr($m_casenum, 10, 4);
        $mm_caseyear = (int) $m_str3;
        $t_caseno = $mm_casetype . " " . $mm_casenum . "/" . $mm_caseyear;
    } else
        $t_caseno = "";
    return $t_caseno;
}

function get_allocation_judge($p1, $cldt, $jud_count, $board_type)
{
    $db = \Config\Database::connect();
    $cldt =  date('Y-m-d', strtotime($cldt));

    if ($p1 == "M") {
        $m_f = "AND r.m_f = '1'";
        if ($board_type == 'R')
            $from_to_dt = "AND r.to_date is null";
        else
            $from_to_dt = "AND r.from_date = '$cldt' ";
    } else if ($p1 == "L") {
        $m_f = "AND r.m_f = '3'";
        $from_to_dt = "AND r.from_date = '$cldt' ";
    } else if ($p1 == "S") {
        $m_f = "AND r.m_f = '4'";
        $from_to_dt = "AND r.from_date = '$cldt' ";
    } else {
        $m_f = "AND r.m_f = '2'";
        $from_to_dt = "AND r.from_date = '$cldt' ";
    }

    //$cldt = '2023-07-18';
    //$from_to_dt = "AND r.from_date = '$cldt' ";

    $sql = "SELECT r.id, STRING_AGG(j.jcode::text, ',' ORDER BY j.judge_seniority) AS jcd, STRING_AGG(CONCAT(j.first_name, ' ', j.sur_name), ',' ORDER BY j.judge_seniority) AS jnm, rb.bench_no, mb.abbr, r.tot_cases, r.courtno, mb.board_type_mb FROM master.roster r 
    LEFT JOIN master.roster_bench rb ON rb.id = r.bench_id 
    LEFT JOIN master.master_bench mb ON mb.id = rb.bench_id 
    LEFT JOIN master.roster_judge rj ON rj.roster_id = r.id 
    LEFT JOIN master.judge j on j.jcode = rj.judge_id
    WHERE mb.display = 'Y' and mb.board_type_mb = '$board_type' and j.is_retired != 'Y' and j.display  = 'Y' and rj.display = 'Y' and rb.display = 'Y' and r.display = 'Y' $m_f $from_to_dt GROUP BY r.id, rb.bench_no, mb.abbr, r.tot_cases, r.courtno, mb.board_type_mb ORDER BY r.courtno, r.id";

    $query = $db->query($sql);
    if ($query->getNumRows() >= 1) {
        $results = $query->getResultArray();
    ?>

        <table border="0" width="100%" style="vertical-align: bottom; text-align: left; background:#f6fbf0;" cellspacing=1>
            <tr>
                <th style="vertical-align: bottom;"><input type="checkbox" name="chkall" id="chkall" value="ALL" onClick="chkall1(this);">All</th>
                <th>Judges</th>
                <th>C</th>
                <th>R</th>
                <th>Total</th>
            </tr>
            <?php
            foreach ($results as $row) { ?>
                <tr style="vertical-align: bottom;">
                    <td style="vertical-align: bottom;">
                        <input type="checkbox" id="chkeeed" name="chk" value="<?PHP echo $row["jcd"] . "|" . $row["id"]; ?>">
                        <?php echo $row['courtno'] . " " . $row['board_type_mb'] . " " . $row['bench_no']; ?>
                    </td>
                    <td><?php echo str_replace(",", " & ", $row['jnm']); ?></td>
                    <?php
                    $sql1 = "SELECT SUM(CASE WHEN m.case_grp = 'C' THEN 1 ELSE 0 END) civil, 
                                SUM(CASE WHEN m.case_grp = 'R' THEN 1 ELSE 0 END) criminal
                                FROM heardt h left join main m on h.diary_no = m.diary_no where h.next_dt = '$cldt' and h.judges = '" . $row["jcd"] . "' 
                                and h.board_type = '$board_type' and h.mainhead = '$p1' and (h.main_supp_flag = 1 OR h.main_supp_flag = 2) AND (h.diary_no = h.conn_key OR h.conn_key='0') GROUP BY h.judges";
                    $res1 = $db->query($sql1);
                    $row1 = [];
                    if ($res1->getNumRows() >= 1) {
                        $row1 = $res1->getRowArray();
                    }

                    ?>
                    <td style='color:blue' align=left><?php if (isset($row1['civil']) && $row1['civil']) {
                                                            echo $civil = $row1['civil'];
                                                        } else {
                                                            echo $civil = '0';
                                                        } ?></td>
                    <td style='color:blue' align=left><?php if (isset($row1['criminal']) && $row1['criminal']) {
                                                            echo $criminal = $row1['criminal'];
                                                        } else {
                                                            echo $criminal = '0';
                                                        } ?></td>
                    <td style='color:red' align=left><?php echo $c_r = $civil + $criminal; ?></td>
                </tr>
            <?php } ?>
        </table>
        <?php
    } else {
        echo "<center>No Records Found</center>";
    }
}

function get_judge_data($jcode)
{
    $db = \Config\Database::connect();
    $return = [];
    $builder = $db->table('master.judge');
    $builder->select('jcode, jname, first_name, sur_name, title');
    $builder->like('title', '%REGISTRAR%');
    $builder->where('display', 'Y');
    $builder->where('jcode', $jcode);
    $return = $builder->get()->getRowArray();
    return $return;
}

function get_advocate_data($diary_no)
{
    $db = \Config\Database::connect();
    $return = [];
    $sql = "SELECT
                a.*,
                STRING_AGG(a.name || CASE WHEN a.pet_res = 'R' THEN a.grp_adv ELSE '' END, '' ORDER BY a.adv_type DESC, a.pet_res_no ASC) AS r_n,
                STRING_AGG(a.name || CASE WHEN a.pet_res = 'P' THEN a.grp_adv ELSE '' END, '' ORDER BY a.adv_type DESC, a.pet_res_no ASC) AS p_n,
                STRING_AGG(a.name || CASE WHEN a.pet_res = 'I' THEN a.grp_adv ELSE '' END, '' ORDER BY a.adv_type DESC, a.pet_res_no ASC) AS i_n,
                STRING_AGG(a.name || CASE WHEN a.pet_res = 'N' THEN a.grp_adv ELSE '' END, '' ORDER BY a.adv_type DESC, a.pet_res_no ASC) AS intervenor
            FROM (
                SELECT
                    a.diary_no,
                    b.name,
                    STRING_AGG(COALESCE(a.adv, ''), '' ORDER BY CASE WHEN a.pet_res IN ('I', 'N') THEN 99 ELSE 0 END ASC, a.adv_type DESC, a.pet_res_no ASC) AS grp_adv,
                    a.pet_res,
                    a.adv_type,
                    a.pet_res_no
                FROM
                    advocate a
                LEFT JOIN
                    master.bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y'
                WHERE
                    a.diary_no = '" . $diary_no . "' AND a.display = 'Y'
                GROUP BY
                    a.diary_no, b.name, a.pet_res, a.adv_type, a.pet_res_no
                ORDER BY
                    CASE WHEN a.pet_res IN ('I', 'N') THEN 99 ELSE 0 END ASC, a.adv_type DESC, a.pet_res_no ASC
            ) a
            GROUP BY
                a.diary_no, a.name,a.grp_adv,a.pet_res,a.adv_type,a.pet_res_no";

    $query = $db->query($sql);
    $result = $query->getResultArray();
    return $result;
}
function vac_reg_cl_fun1($diary_no)
{
    $db = \Config\Database::connect();
    $builder = $db->table('mul_category mc');
    $builder->select('category_sc_old');
    $builder->join('master.submaster s', 's.id = mc.submaster_id', 'inner');
    $builder->where('mc.diary_no', $diary_no);
    $builder->limit(1);
    $result = $builder->get()->getRowArray();
    return $result;
}
function vac_reg_cl_fun2($diary_no)
{
    $db = \Config\Database::connect();
    $sql = "SELECT
                    a.lct_dec_dt,
                    a.lct_judge_name,
                    a.lctjudname2,
                    a.lctjudname3,
                    a.l_dist,
                    a.ct_code,
                    a.l_state,
                    b.Name,
                    a.brief_desc AS desc1,
                    a.sub_law AS usec2,
                    a.lct_judge_desg,
                    CASE
                        WHEN a.ct_code = 3 THEN
                            CASE
                                WHEN a.l_state = 490506 THEN (
                                    SELECT d.court_name
                                    FROM master.state s
                                    LEFT JOIN master.delhi_district_court d ON s.state_code = d.state_code AND s.district_code = d.district_code
                                    WHERE s.id_no = a.l_dist AND s.display = 'Y'
                                )
                                ELSE (
                                    SELECT s.Name
                                    FROM master.state s
                                    WHERE s.id_no = a.l_dist AND s.display = 'Y'
                                )
                            END
                        ELSE (
                            SELECT c.agency_name
                            FROM master.ref_agency_code c
                            WHERE c.cmis_state_id = a.l_state AND c.id = a.l_dist AND c.is_deleted = 'f'
                        )
                    END AS agency_name,
                    a.crimeno,
                    a.crimeyear,
                    a.polstncode,
                    (
                        SELECT p.policestndesc
                        FROM master.police p
                        WHERE p.policestncd = a.polstncode AND p.display = 'Y' AND p.cmis_state_id = a.l_state AND p.cmis_district_id = a.l_dist
                    ) AS policestndesc,
                    f.authdesc,
                    a.l_inddep,
                    a.l_orgname,
                    a.l_ordchno,
                    a.l_iopb,
                    a.l_iopbn,
                    a.l_org,
                    a.lct_casetype,
                    a.lct_caseno,
                    a.lct_caseyear,
                    CASE
                        WHEN a.ct_code = 4 THEN (
                            SELECT ct.skey
                            FROM master.casetype ct
                            WHERE ct.display = 'Y' AND ct.casecode = a.lct_casetype
                        )
                        ELSE (
                            SELECT d.type_sname
                            FROM master.lc_hc_casetype d
                            WHERE d.lccasecode = a.lct_casetype AND d.display = 'Y'
                        )
                    END AS type_sname,
                    a.lower_court_id,
                    a.is_order_challenged,
                    a.full_interim_flag,
                    a.judgement_covered_in,
                    a.vehicle_code,
                    a.vehicle_no,
                    h.code,
                    i.Post_name,
                    a.cnr_no,
                    a.ref_court,
                    a.ref_case_type,
                    a.ref_case_no,
                    a.ref_case_year,
                    a.ref_state,
                    a.ref_district,
                    a.gov_not_state_id,
                    a.gov_not_case_type,
                    a.gov_not_case_no,
                    a.gov_not_case_year,
                    a.gov_not_date,
                    rd.relied_court,
                    rd.relied_case_type,
                    rd.relied_case_no,
                    rd.relied_case_year,
                    rd.relied_state,
                    rd.relied_district,
                    t_t.transfer_case_type,
                    t_t.transfer_case_no,
                    t_t.transfer_case_year,
                    t_t.transfer_state,
                    t_t.transfer_district,
                    t_t.transfer_court
                FROM lowerct a
                LEFT JOIN master.state b ON a.l_state = b.id_no AND b.display = 'Y'
                JOIN main e ON e.diary_no = a.diary_no
                LEFT JOIN master.authority f ON f.authcode = a.l_iopb AND f.display = 'Y'
                LEFT JOIN master.rto h ON h.id = a.vehicle_code AND h.display = 'Y'
                LEFT JOIN master.Post_t i ON i.Post_code = a.lct_judge_desg AND i.display = 'Y'
                LEFT JOIN relied_details rd ON rd.lowerct_id = a.lower_court_id AND rd.display = 'Y'
                LEFT JOIN transfer_to_details t_t ON t_t.lowerct_id = a.lower_court_id AND t_t.display = 'Y'
                WHERE 
                a.diary_no = '$diary_no' AND 
                a.lw_display = 'Y'
                AND a.is_order_challenged = 'Y'
                ORDER BY a.lower_court_id";
    $query = $db->query($sql);
    $result = $query->getResultArray();
    return $result;
}
function vac_reg_cl_fun3($diary_no)
{
    $db = \Config\Database::connect();
    $builder = $db->table('obj_save a');
    $builder->select('MIN(a.save_dt) AS defect_notified');
    $builder->join('master.objection b', 'a.org_id = b.objcode', 'inner');
    $builder->where('a.diary_no', $diary_no);
    $builder->where('a.display', 'Y');
    $builder->orderBy('MIN(a.id)', 'ASC'); // CI4 requires direction, but using MIN(a.id) in order by is suspect.

    $result = $builder->get()->getRow();
    return $result;
}
function vac_reg_cl_fun4($diary_no)
{
    $db = \Config\Database::connect();
    $builder = $db->table('obj_save a');
    $builder->select('MAX(rm_dt) AS refiled, SUM(CASE WHEN rm_dt = \'0001-01-01 00:00:00\' THEN 1 ELSE 0 END) AS count_zero');
    $builder->join('master.objection b', 'a.org_id = b.objcode', 'inner');
    $builder->where('a.diary_no', $diary_no);
    $builder->where('a.display', 'Y');
    $builder->orderBy('MIN(a.id)', 'ASC'); //CI4 requires direction, but the order is a little unusual.

    $result = $builder->get()->getRow();
    return $result;
}
function vac_reg_week_fun5($diary_no)
{
    $db = \Config\Database::connect();
    $sql = "SELECT
                a.*,
                STRING_AGG(a.name || (CASE WHEN a.pet_res = 'R' THEN a.grp_adv ELSE '' END), '' ORDER BY a.adv_type DESC, a.pet_res_no ASC) AS r_n,
                STRING_AGG(a.name || (CASE WHEN a.pet_res = 'P' THEN a.grp_adv ELSE '' END), '' ORDER BY a.adv_type DESC, a.pet_res_no ASC) AS p_n,
                STRING_AGG(a.name || (CASE WHEN a.pet_res = 'I' THEN a.grp_adv ELSE '' END), '' ORDER BY a.adv_type DESC, a.pet_res_no ASC) AS i_n,
                STRING_AGG(a.name || (CASE WHEN a.pet_res = 'N' THEN a.grp_adv ELSE '' END), '' ORDER BY a.adv_type DESC, a.pet_res_no ASC) AS intervenor
            FROM (
                SELECT
                    a.diary_no,
                    b.name,
                    STRING_AGG(COALESCE(a.adv, ''), '' ORDER BY CASE WHEN a.pet_res IN ('I', 'N') THEN 99 ELSE 0 END ASC, a.adv_type DESC, a.pet_res_no ASC) AS grp_adv,
                    a.pet_res,
                    a.adv_type,
                    a.pet_res_no
                FROM
                    advocate a
                LEFT JOIN
                    master.bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y'
                WHERE
                    a.diary_no = '" . $diary_no . "' 
                    AND a.display = 'Y'
                GROUP BY
                    a.diary_no,
                    b.name,
                    a.pet_res,
                    a.adv_type,
                    a.pet_res_no
                ORDER BY
                    CASE WHEN a.pet_res IN ('I', 'N') THEN 99 ELSE 0 END ASC,
                    a.adv_type DESC,
                    a.pet_res_no ASC
            ) a
            GROUP BY
                a.diary_no,a.name,a.grp_adv,a.pet_res,a.adv_type,a.pet_res_no";

    $query = $db->query($sql);
    $result = $query->getResultArray();
    return $result;
}
function vac_reg_week_fun7($diary_no)
{
    $db = \Config\Database::connect();
    $sql = "SELECT
                tentative_section(j.diary_no) AS section_name,
                j.*
            FROM (
                SELECT
                    h.*,
                    m.active_fil_no,
                    m.active_reg_year,
                    m.casetype_id,
                    m.active_casetype_id,
                    m.ref_agency_state_id,
                    m.reg_no_display,
                    EXTRACT(YEAR FROM m.fil_dt) AS fil_year,
                    m.fil_no,
                    m.conn_key AS main_key,
                    m.fil_dt,
                    m.fil_no_fh,
                    m.reg_year_fh AS fil_year_f,
                    m.mf_active,
                    m.pet_name,
                    m.res_name,
                    pno,
                    rno,
                    m.diary_no_rec_date
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
                    FROM
                        heardt h
                    INNER JOIN
                        main m ON m.diary_no = h.diary_no
                    INNER JOIN
                        conct c ON c.conn_key::TEXT = m.conn_key::TEXT
                    WHERE
                        c.list = 'Y'
                        AND m.c_status = 'P'
                        AND m.diary_no::TEXT = m.conn_key::TEXT
                        AND m.conn_key = '" . $diary_no . "'
                ) a
                INNER JOIN
                    main m ON a.conc_diary_no = m.diary_no
                INNER JOIN
                    heardt h ON a.conc_diary_no = h.diary_no
                WHERE
                    m.c_status = 'P'
                    AND m.conn_key::TEXT != m.diary_no::TEXT
                    AND h.next_dt != '0001-01-01'
                ORDER BY
                    m.diary_no_rec_date
            ) j";
    $query = $db->query($sql);
    $result = $query->getResultArray();
    return $result;
}
function vac_reg_week_fun6($diary_no)
{
    $db = \Config\Database::connect();
    $sql = "SELECT
                a.*,
                STRING_AGG(a.name || (CASE WHEN a.pet_res = 'R' THEN a.grp_adv ELSE '' END), '' ORDER BY a.adv_type DESC, a.pet_res_no ASC) AS r_n,
                STRING_AGG(a.name || (CASE WHEN a.pet_res = 'P' THEN a.grp_adv ELSE '' END), '' ORDER BY a.adv_type DESC, a.pet_res_no ASC) AS p_n,
                STRING_AGG(a.name || (CASE WHEN a.pet_res = 'I' THEN a.grp_adv ELSE '' END), '' ORDER BY a.adv_type DESC, a.pet_res_no ASC) AS i_n,
                STRING_AGG(a.name || (CASE WHEN a.pet_res = 'N' THEN a.grp_adv ELSE '' END), '' ORDER BY a.adv_type DESC, a.pet_res_no ASC) AS intervenor
            FROM (
                SELECT
                    a.diary_no,
                    b.name,
                    STRING_AGG(COALESCE(a.adv, ''), '' ORDER BY CASE WHEN a.pet_res IN ('I', 'N') THEN 99 ELSE 0 END ASC, a.adv_type DESC, a.pet_res_no ASC) AS grp_adv,
                    a.pet_res,
                    a.adv_type,
                    a.pet_res_no
                FROM
                    advocate a
                LEFT JOIN
                    master.bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y'
                WHERE
                    a.diary_no = '" . $diary_no . "'
                    AND a.display = 'Y'
                GROUP BY
                    a.diary_no,
                    b.name,
                    a.pet_res,
                    a.adv_type,
                    a.pet_res_no
                ORDER BY
                    CASE WHEN a.pet_res IN ('I', 'N') THEN 99 ELSE 0 END ASC,
                    a.adv_type DESC,
                    a.pet_res_no ASC
            ) a
            GROUP BY
                a.diary_no,a.name,a.grp_adv,a.pet_res,a.adv_type,a.pet_res_no";

    $query = $db->query($sql);
    $result = $query->getResultArray();
    return $result;
}

function vac_reg_week_fun8($diary_no)
{
    $db = \Config\Database::connect();
    $sql = "SELECT
                a.*,
                STRING_AGG(a.name || CASE WHEN a.pet_res = 'R' THEN a.grp_adv ELSE '' END, '' ORDER BY a.adv_type DESC, a.pet_res_no ASC) AS r_n,
                STRING_AGG(a.name || CASE WHEN a.pet_res = 'P' THEN a.grp_adv ELSE '' END, '' ORDER BY a.adv_type DESC, a.pet_res_no ASC) AS p_n
            FROM (
                SELECT
                    a.diary_no,
                    b.name,
                    STRING_AGG(COALESCE(a.adv, ''), '' ORDER BY a.pet_res ASC, a.adv_type DESC, a.pet_res_no ASC) AS grp_adv,
                    a.pet_res,
                    a.adv_type,
                    a.pet_res_no
                FROM
                    advocate a
                LEFT JOIN
                    master.bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y'
                WHERE
                    a.diary_no = '" . $diary_no . "'  
                    AND a.display = 'Y'
                GROUP BY
                    a.diary_no,
                    b.name,
                    a.pet_res,
                    a.adv_type,
                    a.pet_res_no
                ORDER BY
                    a.pet_res ASC,
                    a.adv_type DESC,
                    a.pet_res_no ASC
            ) a
            GROUP BY
                a.diary_no,a.name,a.grp_adv,a.pet_res,a.adv_type,a.pet_res_no";
    $query = $db->query($sql);
    $result = $query->getResultArray();
    return $result;
}

function previous_ci_data()
{
    $db = \Config\Database::connect();
    $return = [];
    $sql = "SELECT string_agg(j.first_name || ' ' || j.sur_name, ',' ORDER BY j.judge_seniority) AS jnm, a.*
            FROM (
                SELECT h.roster_id, h.judges
                FROM heardt h
                WHERE mainhead = 'M'
                AND board_type = 'J'
                AND next_dt >= CURRENT_DATE
                AND (main_supp_flag = 1 OR main_supp_flag = 2)
                AND h.roster_id > 0
                GROUP BY h.roster_id,h.judges
            ) a
            LEFT JOIN master.roster_judge rj ON rj.roster_id = a.roster_id
            LEFT JOIN master.judge j ON j.jcode = rj.judge_id
            WHERE j.is_retired != 'Y'
            AND j.display = 'Y'
            AND rj.display = 'Y'
            GROUP BY rj.roster_id, a.roster_id, a.judges;";
    $query = $db->query($sql);
    $result = $query->getResultArray();
    return $result;
}


function f_cl_conn_key($q_diary_no)
{
    $db = \Config\Database::connect();
    $return = [];
    $builder = $db->table('main');
    $builder->select('STRING_AGG(diary_no::text, \',\') AS dno_c');
    $builder->where('conn_key', $q_diary_no);
    $builder->groupBy('conn_key');
    $query = $builder->get();
    $result = $query->getRowArray();
    if (isset($result['dno_c'])) {
        $return = $result['dno_c'];
    }
    return $return;
}

function f_selected_values($parm1)
{

    $dld = "";
    if ((count($parm1) > 1) && $parm1[0] == 'all') {
        unset($parm1[0]);
    }
    foreach ($parm1 as $key => $value) {

        $dld .= $value . ",";
    }
    return rtrim($dld, ',');
}

function f_selected_values1($parm1)
{
    $dld = "";
    if (is_string($parm1)) {
        $parm1 = explode(',', $parm1);
    }
    if ((count($parm1) > 1) && $parm1[0] == 'all') {
        unset($parm1[0]);
    }
    if (is_array($parm1)) {
        foreach ($parm1 as $key => $value) {
            $dld .= $value . ",";
        }
    }

    return rtrim($dld, ',');
}

function f_selected_values2($parm1)
{

    $dld = "";

    if (is_array($parm1)) {
        foreach ($parm1 as $key => $value) {
            $dld .= $value . ",";
        }
    }

    // Return the string without the trailing comma
    return rtrim($dld, ',');
}

function cl_print_func1($parm1)
{
    $db = \Config\Database::connect();
    $sql = "SELECT
                a.*,
                STRING_AGG(a.name || (CASE WHEN pet_res = 'R' THEN grp_adv ELSE '' END), '' ORDER BY adv_type DESC, pet_res_no ASC) AS r_n,
                STRING_AGG(a.name || (CASE WHEN pet_res = 'P' THEN grp_adv ELSE '' END), '' ORDER BY adv_type DESC, pet_res_no ASC) AS p_n,
                STRING_AGG(a.name || (CASE WHEN pet_res = 'I' THEN grp_adv ELSE '' END), '' ORDER BY adv_type DESC, pet_res_no ASC) AS i_n
            FROM (
                SELECT
                    a.diary_no,
                    b.name,
                    STRING_AGG(COALESCE(a.adv, ''), '' ORDER BY CASE WHEN pet_res = 'I' THEN 99 ELSE 0 END ASC, adv_type DESC, pet_res_no ASC) AS grp_adv,
                    a.pet_res,
                    a.adv_type,
                    pet_res_no
                FROM advocate a
                LEFT JOIN master.bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y'
                WHERE a.diary_no = '$parm1' AND a.display = 'Y'
                GROUP BY a.diary_no, b.name,a.pet_res,a.adv_type,a.pet_res_no
                ORDER BY CASE WHEN pet_res = 'I' THEN 99 ELSE 0 END ASC, adv_type DESC, pet_res_no ASC
            ) a
            GROUP BY a.diary_no, a.name, a.pet_res, a.adv_type, a.pet_res_no,a.grp_adv";

    $query = $db->query($sql);
    $result = $query->getResultArray();

    return $result;
}

function cl_print_func2($casetype_displ, $ten_reg_yr, $ref_agency_state_id)
{
    $db = \Config\Database::connect();
    $builder = $db->table('master.da_case_distribution a');

    $builder->select('dacode, section_name, name');
    $builder->join('master.users b', 'usercode = dacode', 'left');
    $builder->join('master.usersection c', 'b.section = c.id', 'left');
    $builder->where('case_type', $casetype_displ);
    $builder->where("'$ten_reg_yr' BETWEEN case_f_yr AND case_t_yr"); // or $builder->where("2017 BETWEEN case_f_yr AND case_t_yr");
    $builder->where('state', $ref_agency_state_id);
    $builder->where('a.display', 'Y');
    $result = $builder->get()->getResultArray();

    return $result;
}

function cl_print_func3($diary_no)
{
    $db = \Config\Database::connect();
    $sql = "SELECT u.usercode, u.name, u.empid, f.rece_dt FROM master.users u, fil_trap f where
                f.r_by_empid = u.empid and f.diary_no = '$diary_no' and u.section = 77 and u.display = 'Y'
                union
                SELECT u.usercode, u.name, u.empid, f.rece_dt FROM master.users u, fil_trap_his f where f.r_by_empid = u.empid 
                and f.diary_no = '$diary_no' and u.section = 77 and u.display = 'Y'";

    $query = $db->query($sql);
    $result = $query->getResultArray();
    return $result;
}
function cl_print_func4($diary_no)
{
    $db = \Config\Database::connect();
    $builder = $db->table('fil_trap');

    $builder->select('d_to_empid, name, diary_no');
    $builder->join('master.users', 'fil_trap.d_to_empid = master.users.empid');
    $builder->like('remarks', '%IB-Ex%');
    $builder->where('diary_no', $diary_no);

    $result = $builder->get()->getResultArray();
    return $result;
}
function cl_print_func5($diary_no)
{
    $db = \Config\Database::connect();
    $builder = $db->table('docdetails d');
    $builder->select('kntgrp');
    $builder->join('master.docmaster dm', 'dm.doccode1 = d.doccode1 AND dm.doccode = d.doccode');
    $builder->where('d.display', 'Y');
    $builder->where('dm.display', 'Y');
    $builder->where('d.diary_no', $diary_no);
    $builder->groupBy('kntgrp');

    $result = $builder->get()->getResultArray();
    return $result;
}

function advance_cl_printed($q_next_dt)
{
    $db = \Config\Database::connect();
    $result = 0;
    $sql = "SELECT * FROM advance_cl_printed WHERE next_dt = '$q_next_dt' AND board_type = 'J' AND display='Y'";
    $q_rs = $db->query($sql);
    if ($q_rs->getNumRows() > 0) {
        $result = 1;
    } else {
        $result = 0;
    }
    return $result;
}





function sel_jud1($m_jud)
{
    $m_jname = "";
    if ($m_jud != "") {
        $rowj1 = is_data_from_table('master.judge', " jcode=$m_jud ", '*', $row = '');
        if (!empty($rowj1)) {
            if ($rowj1["jcode"] == 0) {
                $m_jname = ucwords("wrong judge code");
            } else {
                $m_jname = stripslashes(trim(strtoupper($rowj1['jname'])));
            }
        }
    }
    return $m_jname;
}


function get_allocation_judge_m_alc_b($p1, $cldt, $board_type)
{
    $db = \Config\Database::connect();
    $cldt_mmddyyyy =  date('d-m-Y', strtotime($cldt));
    $cldt =  date('Y-m-d', strtotime($cldt));
    //$cldt = '2023-01-02';

    if ($p1 == "M") {
        $m_f = "AND r.m_f = '1'";
        if ($board_type == 'R')
            $from_to_dt = "AND r.to_date = IS NULL";
        else
            $from_to_dt = "AND r.from_date = '$cldt' ";
    } else if ($p1 == "L") {
        $m_f = "AND r.m_f = '3'";
        $from_to_dt = "AND r.from_date = '$cldt' ";
    } else if ($p1 == "S") {
        $m_f = "AND r.m_f = '4'";
        $from_to_dt = "AND r.from_date = '$cldt' ";
    } else {
        $m_f = "AND r.m_f = '2'";
        $from_to_dt = "AND r.from_date = '$cldt' ";
    }

    $builder = $db->table('master.sc_working_days');
    $builder->select('is_nmd');
    $builder->where('working_date', $cldt);
    $builder->where('is_holiday', 0);
    $builder->where('display', 'Y');

    $query = $builder->get();
    $ro_isnmd = $query->getRowArray();

    if (!empty($ro_isnmd)) {
        if ($ro_isnmd['is_nmd'] == 1) {
            echo "<span style='color:blue;'><b>Ready to list Regular Day Cases</b></span><br>";
        }
        if ($ro_isnmd['is_nmd'] == 0) {
            echo "<span style='color:green;'><b>Ready to List Misc. Day Cases</b></span><br>";
        }
    } else {
        echo "<span style='color:red;'><b>Not a Working Day</b></span><br>";
    }

    if ($ro_isnmd['is_nmd'] == 1) {
        $sql = "WITH working_days AS (
                        SELECT
                            ROW_NUMBER() OVER (ORDER BY working_date) AS SNo,
                            working_date
                        FROM
                            master.sc_working_days s
                        WHERE
                            EXTRACT(week FROM working_date) = EXTRACT(week FROM DATE '$cldt')
                            AND EXTRACT(year FROM working_date) = EXTRACT(year FROM DATE '$cldt')
                            AND is_holiday = 0
                            AND is_nmd = 1
                            AND display = 'Y'
                    ),
                    fresh_limit_data AS (
                        SELECT
                            CASE
                                WHEN SNo = 1 THEN 10
                                ELSE 5
                            END AS fresh_limit,
                            working_date
                        FROM working_days
                        WHERE working_date = '$cldt'
                    )
                    SELECT
                        a.*,
                        COALESCE(jg.p1, 0) AS presiding1,
                        fl.fresh_limit,
                        CASE
                            WHEN wd.SNo = 1 AND COALESCE(jg.p1, 0) != 111 THEN 15
                            ELSE 10
                        END AS old_limit
                    FROM
                        (
                            SELECT
                                r.id,
                                STRING_AGG(j.jcode::TEXT, ',' ORDER BY j.judge_seniority) AS jcd,
                                STRING_AGG(CONCAT(j.first_name, ' ', j.sur_name), ',' ORDER BY j.judge_seniority) AS jnm,
                                rb.bench_no,
                                mb.abbr,
                                r.tot_cases,
                                r.courtno,
                                mb.board_type_mb
                            FROM
                                master.roster r
                                LEFT JOIN master.roster_bench rb ON rb.id = r.bench_id
                                LEFT JOIN master.master_bench mb ON mb.id = rb.bench_id
                                LEFT JOIN master.roster_judge rj ON rj.roster_id = r.id
                                LEFT JOIN master.judge j ON j.jcode = rj.judge_id
                            WHERE
                                rb.bench_no NOT LIKE '%SPL%'
                                AND j.is_retired != 'Y'
                                AND mb.board_type_mb = '$board_type' 
                                AND r.courtno > 0
                                AND j.display = 'Y'
                                AND rj.display = 'Y'
                                AND rb.display = 'Y'
                                AND mb.display = 'Y'
                                AND r.display = 'Y'
                                $m_f
                                $from_to_dt
                            GROUP BY
                                r.id, rb.bench_no, mb.abbr, mb.board_type_mb
                        ) a
                        LEFT JOIN judge_group jg
                            ON POSITION(jg.p1::TEXT IN a.jcd) > 0
                            AND jg.display = 'Y'
                            AND jg.to_dt IS NULL
                        LEFT JOIN fresh_limit_data fl ON true
                        LEFT JOIN working_days wd ON wd.working_date = '$cldt'
                    ORDER BY
                        a.courtno,
                        a.id";
    }

    if ($ro_isnmd['is_nmd'] == 0) {

        $sql = "SELECT 
                DISTINCT a.*, 
                COALESCE(jg.p1, 0) AS presiding1, 
                COALESCE(jg.fresh_limit, 0) AS fresh_limit, 
                COALESCE(jg.old_limit, 0) AS old_limit 
                FROM 
                (
                    SELECT 
                    r.id, 
                    STRING_AGG(j.jcode::text, ',' ORDER BY j.judge_seniority) AS jcd,
                    STRING_AGG(CONCAT(j.first_name, ' ', j.sur_name)::text, ',' ORDER BY j.judge_seniority) AS jnm,
                    rb.bench_no, 
                    mb.abbr, 
                    r.tot_cases, 
                    r.courtno, 
                    mb.board_type_mb 
                    FROM 
                    master.roster r 
                    LEFT JOIN master.roster_bench rb ON rb.id = r.bench_id 
                    LEFT JOIN master.master_bench mb ON mb.id = rb.bench_id 
                    LEFT JOIN master.roster_judge rj ON rj.roster_id = r.id 
                    LEFT JOIN master.judge j ON j.jcode = rj.judge_id 
                    WHERE 
                    rb.bench_no NOT LIKE '%SPL%' 
                    AND j.is_retired != 'Y' 
                    AND mb.board_type_mb = '$board_type' 
                    AND r.courtno > 0 
                    AND j.display = 'Y' 
                    AND rj.display = 'Y' 
                    AND rb.display = 'Y' 
                    AND mb.display = 'Y' 
                    AND r.display = 'Y' 
                    $m_f 
                    $from_to_dt 
                    GROUP BY 
                    r.id, rb.bench_no, mb.abbr, mb.board_type_mb
                ) a 
                LEFT JOIN judge_group jg 
                    ON jg.p1::text = ANY(string_to_array(a.jcd, ',')) 
                    AND jg.display = 'Y' 
                    AND (jg.to_dt IS NULL OR jg.to_dt = '9999-12-31') 
                GROUP BY 
                a.id, a.abbr, a.jcd, a.jnm,a.bench_no,a.tot_cases,a.courtno,a.board_type_mb, jg.p1,jg.fresh_limit,jg.old_limit
                ORDER BY 
                a.courtno, 
                a.id";
    }


    $res1 = $db->query($sql);
    if ($res1->getNumRows() >= 1) {
        $res = $res1->getResultArray();

        if ($res) { ?>
            <div id="prnnt2">
                <fieldset>
                    <legend style="text-align:center;color:#4141E0; font-weight:bold;">FINAL LIST ALLOCATION FOR DATED <?php echo $cldt_mmddyyyy; ?> </legend>
                    <table border="1" width="100%" class='table table-bordered table-striped' style="border-collapse:collapse; border-color:black; vertical-align: bottom; text-align: left; background:#f6fbf0;" cellspacing=0>
                        <tr class="bold-row">
                            <th rowspan="2" style="text-align: left; vertical-align: top;">
                                <input type="checkbox" name="chkall" id="chkall" value="ALL" onClick="chkall1(this);"><span style="margin-left:-8px;">All</span>
                            </th>
                            <th rowspan="2" style="text-align: center; vertical-align: top;">Judges</th>

                            <th colspan="6" style="text-align: center;">Listed</th>
                            <th style="text-align: center;" colspan="3">To Be Listed</th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>TP</th>
                            <th>Bail</th>
                            <th>Old<br>After<br>Notice</th>
                            <th>Freshly<br>Filed</th>
                            <th>Pre<br>Notice</th>
                            <th>After<br>Notice</th>
                            <th>TOTAL</th>
                            <th>Freshly<br>Filed</th>
                            <th>Old</th>
                            <th>TOTAL</th>
                        </tr>

                        <?php
                        $tp_t = 0;
                        $bail_t = 0;
                        $fr_t = 0;
                        $pre_n_t = 0;
                        $after_n_t = 0;
                        $ttt_t = $old_after_notice_t = 0;
                        foreach ($res as $row) {

                            //$cldt = '2025-01-02';
                            //$row["jcd"] = '537,534';
                        ?>
                            <tr style="vertical-align: bottom;">
                                <td style="vertical-align: bottom;">
                                    <input type="checkbox" id="chkeeed" name="chk" value="<?PHP echo $row["jcd"] . "|" . $row["id"] . "|" . $row["abbr"]; ?>">
                                    <?php echo $row['courtno'] . " " . $row['board_type_mb']; ?>
                                </td>
                                <td><?php echo str_replace(",", " & ", $row['jnm']); ?></td>
                                <?php $jcd_p1 = explode(",", $row["jcd"]);

                                $sql1 = "SELECT 
                                                judges, 
                                                COUNT(diary_no) AS ttt,
                                                SUM(CASE WHEN pre_after_notice = 'TP' THEN 1 ELSE 0 END) AS TP,
                                                SUM(CASE WHEN pre_after_notice = 'Bail' THEN 1 ELSE 0 END) AS Bail,
                                                SUM(CASE WHEN pre_after_notice = 'old_after_notice' THEN 1 ELSE 0 END) AS old_after_notice,
                                                SUM(CASE WHEN pre_after_notice = 'fr' THEN 1 ELSE 0 END) AS fr,
                                                SUM(CASE WHEN pre_after_notice = 'Pre_Notice' THEN 1 ELSE 0 END) AS Pre_Notice,
                                                SUM(CASE WHEN pre_after_notice = 'After_Notice' THEN 1 ELSE 0 END) AS After_Notice
                                            FROM (
                                                SELECT DISTINCT 
                                                    h.diary_no, 
                                                    h.judges, 
                                                    h.subhead, 
                                                    h.listorder,
                                                    CASE 
                                                        WHEN h.subhead = 829 THEN 'TP'
                                                        WHEN h.subhead = 804 THEN 'Bail'
                                                        WHEN h.subhead = 831 THEN 'old_after_notice'
                                                        WHEN h.listorder = 32 
                                                            AND h.subhead NOT IN (829, 804, 831) THEN 'fr'
                                                        WHEN h.listorder != 32 
                                                            AND h.subhead NOT IN (829, 804, 831) 
                                                            AND c.diary_no IS NULL 
                                                            AND (m.fil_no_fh = '' OR m.fil_no_fh IS NULL) 
                                                            AND h.subhead NOT IN (813, 814, 831) THEN 'Pre_Notice'
                                                        ELSE 'After_Notice'
                                                    END AS pre_after_notice
                                                FROM 
                                                    heardt h 
                                                LEFT JOIN 
                                                    main m ON h.diary_no = m.diary_no 
                                                LEFT JOIN 
                                                    case_remarks_multiple c ON CAST(c.diary_no AS bigint) = m.diary_no 
                                                                            AND c.r_head IN (1, 3, 62, 181, 182, 183, 184)
                                                WHERE 
                                                    h.next_dt = '$cldt'                                         
                                                    AND h.judges = '" . $row["jcd"] . "' 
                                                    AND h.board_type = 'J'
                                                    AND h.mainhead = '$p1' 
                                                    AND h.main_supp_flag IN (1, 2) 
                                                    AND (m.diary_no = CAST(NULLIF(m.conn_key, '') AS BIGINT) OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
                                                GROUP BY 
                                                    h.diary_no, h.judges, h.subhead, h.listorder, m.diary_no, m.fil_no_fh, c.diary_no
                                            ) h
                                            GROUP BY 
                                                h.judges";
                                $row1 = [];

                                $res1 = $db->query($sql1);

                                if ($res1->getNumRows() >= 1) {
                                    $row1 = $res1->getRowArray();
                                }

                                $row1['tp'] = isset($row1['tp']) ? $row1['tp'] : 0;
                                $row1['bail'] = isset($row1['bail']) ? $row1['bail'] : 0;
                                $row1['old_after_notice'] = isset($row1['old_after_notice']) ? $row1['old_after_notice'] : 0;
                                $row1['fr'] = isset($row1['fr']) ? $row1['fr'] : 0;
                                $row1['pre_notice'] = isset($row1['pre_notice']) ? $row1['pre_notice'] : 0;
                                $row1['after_notice'] = isset($row1['after_notice']) ? $row1['after_notice'] : 0;
                                $row1['ttt'] = isset($row1['ttt']) ? $row1['ttt'] : 0;

                                ?>



                                <td align=left><?php echo $row1['tp'];
                                                $tp_t += $row1['tp']; ?></td>
                                <td align=left><?php echo $row1['bail'];
                                                $bail_t += $row1['bail']; ?></td>
                                <td align=left><?php echo $row1['old_after_notice'];
                                                $old_after_notice_t += $row1['old_after_notice']; ?></td>
                                <td align=left><?php echo $row1['fr'];
                                                $fr_t += $row1['fr']; ?></td>
                                <td align=left><?php echo $row1['pre_notice'];
                                                $pre_n_t += $row1['pre_notice']; ?></td>
                                <td align=left><?php echo $row1['after_notice'];
                                                $after_n_t += $row1['after_notice']; ?></td>
                                <td align=left><?php echo $row1['ttt'];
                                                $ttt_t += $row1['ttt']; ?></td>
                                <td align=left>
                                    <select class='misc_selcted_box make_zero' name="fr_<?php echo $row["id"]; ?>" id="fr_<?php echo $row["id"]; ?>" onchange="calc_tot(this.id)">
                                        <?php
                                        for ($i = 0; $i < 301; $i++) {
                                        ?>
                                            <option value="<?php echo $i; ?>" <?php if ($i == $row["fresh_limit"]) { ?> selected <?php } ?>><?php echo $i; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td align=left>
                                    <select class='misc_selcted_box make_zero' name="or_<?php echo $row["id"]; ?>" id="or_<?php echo $row["id"]; ?>" onchange="calc_tot(this.id)">
                                        <?php
                                        for ($i = 0; $i < 301; $i++) {
                                        ?>
                                            <option value="<?php echo $i; ?>" <?php if ($i == $row["old_limit"]) { ?> selected <?php } ?>><?php echo $i; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td align=left>
                                    <select class='misc_selcted_box make_zero' name="tot_<?php echo $row["id"]; ?>" id="tot_<?php echo $row["id"]; ?>" disabled="disabled">
                                        <?php
                                        $total_limit = "";
                                        $total_limit = $row["fresh_limit"] + $row["old_limit"];
                                        for ($i = 0; $i < 1001; $i++) {
                                        ?>
                                            <option value="<?php echo $i; ?>" <?php if ($i == $total_limit) { ?> selected <?php } ?>><?php echo $i; ?></option>
                                        <?php
                                        }
                                        ?>

                                    </select>
                                </td>
                            </tr>
                            <?php //} 
                            ?>
                        <?php } ?>

                        <tr class="bold-row">
                            <td style="text-align:right;" colspan="2">TOTAL</td>
                            <td><?php echo $tp_t; ?></td>
                            <td><?php echo $bail_t; ?></td>
                            <td><?php echo $old_after_notice_t; ?></td>
                            <td><?php echo $fr_t; ?></td>
                            <td><?php echo $pre_n_t; ?></td>
                            <td><?php echo $after_n_t; ?></td>
                            <td><?php echo $ttt_t; ?></td>
                            <td colspan="3"></td>
                        </tr>
                        <?php //}
                        ?>
                    </table>
                <?php   } else {
                echo "<center>No Records Found</center>";
            } ?>

                </fieldset>
            </div>
            <input name="prnnt_btn" type="button" id="prnnt_btn" value="Print" class="btn btn-primary">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="btn_to_make_zero" type="button" id="btn_to_make_zero" value="Set as Zero" onclick="make_zero()" class="btn btn-primary">

            <?php

        }
    }

    if (!function_exists('convertDateFormat')) {
        function convertDateFormat($str)
        {
            $day = substr($str, 0, 2);
            $month = substr($str, 3, 2);
            $year = substr($str, 6, 4);
            return $year . "-" . $month . "-" . $day;
        }
    }

    if (!function_exists('create_shorten')) {
        function create_shorten($base64_encode)
        {
            $db = \Config\Database::connect();
            $dbeservices = \Config\Database::connect('eservices');
            $base64_decode = base64_decode($base64_encode);
            $json = json_decode($base64_decode);
            $access_key = $json->key;
            $slug = '';
            $verify = $dbeservices->query("SELECT access_key FROM e_services.public.redirect_permit WHERE access_key = '" . $access_key . "' and access_ip = '" . $_SERVER['SERVER_ADDR'] . "' LIMIT 1");
            if ($verify->getNumRows() == 1) {
                $url = urldecode(trim($json->url));

                if (in_array($url, array('', 'about:blank', 'undefined', 'http://localhost/', 'https://anu.sci.gov.in/'))) {
                    $status = "Enter a Valid URL.";
                } else {
                    $stmt = $dbeservices->query("SELECT slug FROM public.redirect WHERE url = '" . $url . "' LIMIT 1");
                    if ($stmt->getNumRows() == 1) {
                        $nb = $stmt->getRow();
                        $slug = "https://anu.sci.gov.in/" . $nb->slug;
                        $status = "Short URL Already Available.";
                    } else {
                        $token = generateUniqueID();
                        if ($token != '') {
                            $insert_stmt = $dbeservices->query("INSERT INTO public.redirect (slug, url, date, hits) VALUES 
                                ($token, $url, NOW(), 0)");
                            if ($db->affectedRows() > 0) {
                                $status = "success";
                                $slug = "https://anu.sci.gov.in/" . $token;
                            } else {
                                $status = "Unable to insert record.";
                            }
                        } else {
                            $status = "Unable to create short url.";
                        }
                    }
                }
            } else {
                $status = "Access Denied.";
            }
            $content = json_encode(array("status" => $status, "slug" => $slug));
            return base64_encode($content);
        }
    }
    if (!function_exists('generateUniqueID')) {
        function generateUniqueID()
        {
            $db = \Config\Database::connect();
            $dbeservices = \Config\Database::connect('eservices');
            $token = substr(md5(uniqid(rand(), true)), 0, 7);
            $stmt = $dbeservices->query("SELECT * FROM public.redirect WHERE slug = $token");
            if ($stmt->getNumRows() > 0) {
                generateUniqueID();
            } else {
                return $token;
            }
        }
    }

    if (!function_exists('SMS_And_Email')) {
        function SMS_And_Email($mobile_no, $cnt, $frm_adr, $template_id)
        {
            $db = \Config\Database::connect();
            $sms_lengt = explode(",", trim($mobile_no));
            $count_sms = count($sms_lengt);
            for ($k = 0; $k < $count_sms; $k++) {
                $mobile_no = trim($sms_lengt[$k]);
                $mm = trim($mobile_no);
                $homepage = file_get_contents('http://XXXX/eAdminSCI/a-push-sms-gw?mobileNos=' . $mm . '&message=' . urlencode($cnt) . '&typeId=29&myUserId=NIC001001&myAccessId=root&authCode=' . SMS_KEY . '&templateId=' . $template_id);

                $json = json_decode($homepage);
                if ($json->{'responseFlag'} == "success") {
                    $sql = "INSERT INTO sms_pool (mobile,msg,table_name,c_status,ent_time,update_time, template_id) VALUES ('$mm','$cnt','$frm_adr','Y',NOW(),Now(),'$template_id')";
                    $db->query($sql);
                } else {
                    $sql = "INSERT INTO sms_pool (mobile,msg,table_name,c_status,ent_time, template_id) VALUES ('$mm','$cnt','$frm_adr','N',NOW(),'$template_id')";
                    $db->query($sql);
                }
            }
        }
    }

    if (!function_exists('mphc_sms')) {
        function mphc_sms($mobile, $cnt, $from_adr, $template_id)
        {
            $db = \Config\Database::connect();
            //validations
            if (empty($mobile)) {
                echo " Mobile No. Empty.";
            } else if (empty($cnt)) {
                echo " Message content Empty.";
            } else if (strlen($cnt) > 320) {
                echo " Message length should be less than 320 characters.";
            } else if (empty($from_adr)) {
                echo " Sender Information Empty, contact to server room.";
            }
            /*else if(strlen($mobile) != '10'){
            echo " Not a Proper Mobile No.";
        }*/ else {
                $frm_adr = trim($from_adr);
                $sms_lengt = explode(",", trim($mobile));
                $count_sms = count($sms_lengt);
                $srno = 1;
                for ($k = 0; $k < $count_sms; $k++) {
                    echo "<br/>";
                    if (strlen(trim($sms_lengt[$k])) != '10') {
                        echo "   " . $srno++ . "   " . $sms_lengt[$k] . "   Not a proper mobile number. \n";
                    } else if (!is_numeric($sms_lengt[$k])) {
                        echo "   " . $srno++ . "   " . $sms_lengt[$k] . " Mobile number contains invalid value. \n";
                    } else {
                        $mm = trim($sms_lengt[$k]);
                        $homepage = file_get_contents('http://XXXX/eAdminSCI/a-push-sms-gw?mobileNos=' . $mm . '&message=' . urlencode($cnt) . '&typeId=29&myUserId=NIC001001&myAccessId=root&authCode=' . SMS_KEY . '&templateId=' . $template_id);
                        $json = json_decode($homepage);
                        if ($json->{'responseFlag'} == "success") {
                            $sql = "INSERT INTO sms_pool (mobile,msg,table_name,c_status,ent_time,update_time, template_id) VALUES ('$mm','$cnt','$frm_adr','Y',NOW(),Now(),'$template_id')";
                            $db->query($sql);
                            echo "   " . $srno++ . "   " . $sms_lengt[$k] . "    Success. SMS Sent \n";
                        } else {
                            $sql = "INSERT INTO sms_pool (mobile,msg,table_name,c_status,ent_time, template_id) VALUES ('$mm','$cnt','$frm_adr','N',NOW(),'$template_id')";
                            $db->query($sql);
                            echo "   " . $srno++ . "   " . $sms_lengt[$k] . "   Error:Not Sent, SMS may send later. \n";
                        }
                    }
                }
            }
        }
    }


    if (!function_exists('sms_from_pool')) {
        function sms_from_pool()
        {
            $db = \Config\Database::connect();
            $cur_dttime = date('d-m-Y H:i:s');
            $startTime = explode(' ', microtime());
            set_time_limit(0);
            $tot_sms = 1;
            $sms_sleep = 1;
            //cont 53/17, wp 183/13, ma 2011/11
            $sql = "SELECT id, mobile, msg, template_id FROM sms_pool WHERE (c_status = 'N' OR c_status = '1' OR c_status = '2') ORDER BY CASE WHEN table_name = 'otp' THEN 0 
    WHEN table_name = 'web_mphc' THEN 1    
    WHEN table_name = 'comp1' THEN 1 
    WHEN table_name = 'RTI' THEN 2 WHEN table_name = 'cbacmis' THEN 3 WHEN table_name = 'sms_module' THEN 4 ELSE 5 END, table_name ASC, id DESC";
            $rs = $db->query($sql);
            $srno = 1;
            if ($rs->getNumRows() > 0) {
            ?>
                <style>
                    table,
                    td,
                    th {
                        border: 1pt solid black;
                        border-collapse: collapse;
                    }
                </style>
                <table>
                    <tr>
                        <td>Srno</td>
                        <td>mobile</td>
                        <td>status</td>
                    </tr>
                    <?php
                    $res = $rs->getResultArray();
                    foreach ($res as $ro) {
                        $sms_pool_id = $ro['id']; //$_REQUEST['mobile'];
                        $mobile = $ro['mobile']; //$_REQUEST['mobile'];
                        $cnt = trim($ro['msg']); //$_REQUEST['message'];
                        $template_id = trim($ro['template_id']);
                        $from_adr = "sms_module";

                        if (empty($mobile)) {
                            echo "<tr><td>" . $srno++ . "</td><td>" . $mobile . "</td><td><font color='red'>Mobile No. Empty.</font></td></tr>";
                        } else if (empty($cnt)) {
                            echo "<tr><td>" . $srno++ . "</td><td>" . $mobile . "</td><td><font color='red'>Message content Empty.</font></td></tr>";
                        } else if (empty($from_adr)) {
                            echo "<tr><td>" . $srno++ . "</td><td>" . $mobile . "</td><td><font color='red'>Sender Information Empty Contact to Server Room.</font></td></tr>";
                        } else {
                            $frm_adr = trim($from_adr);
                            $sms_lengt = explode(",", trim($mobile));
                            $count_sms = count($sms_lengt);

                            for ($k = 0; $k < $count_sms; $k++) {
                                if (strlen(trim($sms_lengt[$k])) != '10') {
                                    echo "<tr><td>" . $srno++ . "</td><td>" . $sms_lengt[$k] . "</td><td><font color='red'>Not a proper mobile number.</font></td></tr>";
                                } else if (!is_numeric($sms_lengt[$k])) {
                                    echo "<tr><td>" . $srno++ . "</td><td>" . $sms_lengt[$k] . "</td><td><font color='red'>Mobile number contains invalid value.</font></td></tr>";
                                } else {
                                    $mm = trim($sms_lengt[$k]);
                                    $homepage = file_get_contents('http://XXXX/eAdminSCI/a-push-sms-gw?mobileNos=' . $mm . '&message=' . urlencode($cnt) . '&typeId=29&myUserId=NIC001001&myAccessId=root&authCode=' . SMS_KEY . '&templateId=' . $template_id);
                                    $json = json_decode($homepage);
                                    if ($json->{'responseFlag'} == "success") {
                                        $sql = "update sms_pool set c_status = 'Y', update_time = NOW() where id = '$sms_pool_id'";
                                        $db->query($sql);
                                        echo "<tr><td>" . $srno++ . "</td><td>" . $sms_lengt[$k] . "</td><td><font color='green'>Success.</font></td></tr>";
                                    } else {
                                        $sql = "update sms_pool set c_status = case when c_status = 'N' then 1 when 1 then 2 else 3 end, update_time = NOW() where id = '$sms_pool_id'";
                                        $db->query($sql);
                                        echo "<tr><td>" . $srno++ . "</td><td>" . $sms_lengt[$k] . "</td><td><font color='red'>Error:Not Sent, SMS may send later.</font></td></tr>";
                                    }
                                }
                            }
                        }
                    } //end of while loop
                    ?>
                </table>
            <?php
            } else {
                $updt = $db->query("update sms_pool set c_status = case when c_status = 'N' then 1 when 1 then 2 else 3 end, update_time = NOW() where (c_status = 'N' OR c_status = '1' OR c_status = '2')");
            }

            $endTime = explode(' ', microtime());
            echo '<br/>Programme Started Time : <b>' . $cur_dttime . '</b><br/>Programme processed in <b/>' . round((($endTime[1] + $endTime[0]) - ($startTime[1] + $startTime[0])), 4) . '</b> seconds.';
        }
    }

    if (!function_exists('send_sms_whatsapp_through_uni_notify')) {
        function send_sms_whatsapp_through_uni_notify($api_type = null, $mobile_nos = [], $templateCode = null, $sms_params = [], $scheduledAt = null, $purpose = null, $created_by_user = [], $module = null, $project = null, $file_name = null, $file_url = null)
        {
            $sms_request_filters = [
                "providerCode" => "wa",
                "recipients" => [
                    "mobileNumbers" => $mobile_nos
                ],
                "templateCode" => $templateCode,
                "templateVariables" => $sms_params,
                "scheduledAt" => $scheduledAt,
                "purpose" => $purpose,
                "createdByUser" => $created_by_user,
                "module" => $module,
                "project" => $project
            ];

            if ($api_type == 2) {
                $fileArray = [
                    "files" => [
                        [
                            "name" => $file_name,
                            "url" => $file_url,
                            "downloadAtStage" => "job_execution",
                            "mimeType" => "application/pdf"
                        ]
                    ]
                ];
                $sms_request_filters = array_merge($sms_request_filters, $fileArray);
            }
            json_encode($sms_request_filters);
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://xxxx:36521/api/v1/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array('data' => json_encode($sms_request_filters)),
                CURLOPT_HTTPHEADER => array(
                    'Accept: application/json',
                    'Authorization: Bearer sdfmsdbfjh327654t3ufb57'
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            //echo $response;
        }
    }

    if (!function_exists('advocate_mobile')) {
        function advocate_mobile($diary_no)
        {
            $db = \Config\Database::connect();
            $wh_mobileno = '';
            $advocate_mob = "Select mobile from advocate a join bar b on a.advocate_id=b.bar_id
                where diary_no='$diary_no' and display='Y' and pet_res='P'";
            $advocate_mob = $db->query($advocate_mob);
            if ($advocate_mob->getNumRows() > 0) {
                $res = $advocate_mob->getResultArray();
                foreach ($res as $row) {
                    if ($row['mobile'] != '' && strlen($row['mobile']) == '10') {
                        $wh_mobileno .= "91" . $row['mobile'] . ',';
                    }
                }
            }
            $wh_mobileno = rtrim($wh_mobileno, ',');
            if (!empty($wh_mobileno))
                return $wh_mobileno;
            else
                return false;
        }
    }

    if (!function_exists('send_whatsapp')) {
        function send_whatsapp($diary_no, $listing_date, $file_path, $file_name)
        {
            $diary_year = substr($diary_no, -4);
            $diary_number = substr($diary_no, 0, -4);
            $db = \Config\Database::connect();
            $sql_main = "select concat(pet_name,' vs ',res_name) cause_title from main where diary_no=$diary_no";
            $query_main = $db->query($sql_main);
            $cause_title = $query_main->getRow()->cause_title;
            $wh_mobileno = '';
            $wh_mobileno = advocate_mobile($diary_no);
            $wh_mobileno = explode(',', $wh_mobileno);

            $file_name = "OR_" . $file_name;

            $purpose = 'Office Report uploading';
            $templateCode = "icmis::case::judgement_rop::sharing";
            $sms_params = array('Office Report', $cause_title, ' Diary no ' . $diary_number . '-' . $diary_year, $listing_date);
            $module = 'Office Report';

            $created_by_user = array("name" => $_SESSION['emp_name_login'], "id" => $_SESSION['dcmis_user_idd'], "employeeCode" => $_SESSION['icmic_empid'], "organizationName" => 'SCI');
            $response = send_sms_whatsapp_through_uni_notify(2, $wh_mobileno, $templateCode, $sms_params, null, $purpose, $created_by_user, $module, 'ICMIS', $file_name, $file_path);
        }
    }

    function pad_iv($iv, $required_length)
    {
        return str_pad($iv, $required_length, "\0");
    }

    function revertDate($date)
    {

        if (!is_string($date) || empty($date)) {
            return '--';
        }
        $dateParts = explode('-', $date);

        if (count($dateParts) !== 3) {
            return '--';
        }
        $revertedDate = $dateParts[2] . '/' . $dateParts[1] . '/' . $dateParts[0];
        return $revertedDate;
    }

    function navigate_diary1($dno)
    {
        $db = \Config\Database::connect();
        $sql = "SELECT m.diary_no,c1.short_description,m.active_reg_year,m.active_fil_no,m.pet_name,m.res_name,m.pno,m.rno,m.diary_no_rec_date, m.active_fil_dt, 
               m.lastorder,m.c_status 
               FROM
               main m 
               LEFT JOIN
               master.casetype c1 ON m.active_casetype_id = c1.casecode 
               WHERE m.diary_no = '$dno';";
        $query = $db->query($sql);
        $res = $query->getResultArray();

        foreach ($res as $ro) {
            $filno_array = explode("-", $ro['active_fil_no']);
            if (empty($filno_array[0])) {
                $fil_no_print = "Unreg.";
            } else {
                $fil_no_print = $ro['short_description'] . "/" . ltrim($filno_array[1], '0');
                if (!empty($filno_array[2]) and $filno_array[1] != $filno_array[2])
                    $fil_no_print .= "-" . ltrim($filno_array[2], '0');
                $fil_no_print .= "/" . $ro['active_reg_year'];
            }
            if ($ro['c_status'] == "P") {
                $cstatus = "Pending";
            } else {
                $cstatus = "Disposed";
            }
            $_SESSION['session_c_status'] = $cstatus;
            $_SESSION['session_pet_name'] = $ro['pet_name'];
            $_SESSION['session_res_name'] = $ro['res_name'];
            $_SESSION['session_lastorder'] = $ro['lastorder'];

            if (isset($ro['diary_no_rec_date']) && !is_null($ro['diary_no_rec_date'])) {
                $_SESSION['session_diary_recv_dt'] = date('d-m-Y H:i:s', strtotime($ro['diary_no_rec_date']));
            } else {
                $_SESSION['session_diary_recv_dt'] = 'Invalid date';
            }
            if (isset($ro['active_fil_dt']) && !is_null($ro['active_fil_dt'])) {
                $_SESSION['session_active_fil_dt'] = date('d-m-Y H:i:s', strtotime($ro['active_fil_dt']));
            } else {
                $_SESSION['session_active_fil_dt'] = 'Invalid date';
            }
            $_SESSION['session_diary_no'] = substr($dno, 0, -4);
            $_SESSION['session_diary_yr'] = substr($dno, -4);
            $_SESSION['session_active_reg_no'] = $fil_no_print;
        }
    }




    function get_roster_judges_t_bk($p1, $cldt, $jud_count, $board_type)
    {
        $db = \Config\Database::connect();
        $cldt =  date('Y-m-d', strtotime($cldt));

        if ($p1 == "M") {
            $m_f = "AND r.m_f = '1'";
            if ($board_type == 'R')
                $from_to_dt = "AND r.to_date is null";
            else
                $from_to_dt = "AND r.from_date = '$cldt' ";
        } else if ($p1 == "L") {
            $m_f = "AND r.m_f = '3'";
            $from_to_dt = "AND r.from_date = '$cldt' ";
        } else if ($p1 == "S") {
            $m_f = "AND r.m_f = '4'";
            $from_to_dt = "AND r.from_date = '$cldt' ";
        } else {
            $m_f = "AND r.m_f = '2'";
            $from_to_dt = "AND r.from_date = '$cldt' ";
        }

        //$cldt = '2023-07-18';
        //$from_to_dt = "AND r.from_date = '$cldt' ";

        $sql = "SELECT ro.courtno, STRING_AGG(j.jcode::text, ',' ORDER BY j.judge_seniority) AS jcd, STRING_AGG(CONCAT(j.first_name, ' ', j.sur_name), ',' ORDER BY j.judge_seniority) AS jnm, r.* FROM (SELECT roster_id AS id, judges AS jcd FROM heardt h WHERE next_dt = '$cldt' AND mainhead = '$p1' AND board_type = '$board_type' AND roster_id > 0 AND (main_supp_flag = 1 OR main_supp_flag = 2) GROUP BY roster_id, h.judges) r 
    LEFT JOIN master.roster ro ON ro.id = r.id 
    LEFT JOIN master.roster_judge rj ON rj.roster_id = r.id 
    LEFT JOIN master.judge j ON j.jcode = rj.judge_id WHERE j.is_retired != 'Y' AND j.display = 'Y' AND rj.display = 'Y' 
    GROUP BY r.id, ro.courtno, ro.courtno, r.jcd, j.jcode
    ORDER BY ro.courtno, r.id, j.jcode";
        //pr($sql);
        $query = $db->query($sql);
        if ($query->getNumRows() >= 1) {
            $results = $query->getResultArray();
            ?>

            <table border="0" width="100%" style="vertical-align: bottom; text-align: left; background:#f6fbf0;" cellspacing=1>
                <tr>
                    <td>
                        <select name="croam_from" id="coram_from">
                            <option value="-1">Select</option>
                            <?php
                            foreach ($results as $row) {
                            ?>
                                <option value="<?php echo $row["jcd"] . "|" . $row["id"]; ?>"><?php echo $row['courtno'] . " -> " . str_replace(",", " & ", $row['jnm']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
            </table>
    <?php
        } else {
            echo "<center>No Records Found</center>";
        }
    }


    function get_roster_judges_t($p1, $cldt, $jud_count, $board_type)
    {
        //$cldt = '2023-10-11';
        //$p1 = "F";
        $db = \Config\Database::connect();
        $cldt =  date('Y-m-d', strtotime($cldt));
        $return = [];
        if ($p1 == "M") {
            $m_f = "AND r.m_f = '1'";
            if ($board_type == 'R')
                $from_to_dt = "AND r.to_date is null";
            else
                $from_to_dt = "AND r.from_date = '$cldt' ";
        } else if ($p1 == "L") {
            $m_f = "AND r.m_f = '3'";
            $from_to_dt = "AND r.from_date = '$cldt' ";
        } else if ($p1 == "S") {
            $m_f = "AND r.m_f = '4'";
            $from_to_dt = "AND r.from_date = '$cldt' ";
        } else {
            $m_f = "AND r.m_f = '2'";
            $from_to_dt = "AND r.from_date = '$cldt' ";
        }


        //$cldt = '2024-07-19';
        //$from_to_dt = "AND r.from_date = '$cldt' ";

        $sql = "SELECT ro.courtno, STRING_AGG(j.jcode::text, ',' ORDER BY j.judge_seniority) AS jcd, STRING_AGG(CONCAT(j.first_name, ' ', j.sur_name), ',' ORDER BY j.judge_seniority) AS jnm, r.* FROM (SELECT roster_id AS id, judges AS jcd FROM heardt h WHERE next_dt = '$cldt' AND mainhead = '$p1' AND board_type = '$board_type' AND roster_id > 0 AND (main_supp_flag = 1 OR main_supp_flag = 2) GROUP BY roster_id, h.judges) r 
    LEFT JOIN master.roster ro ON ro.id = r.id 
    LEFT JOIN master.roster_judge rj ON rj.roster_id = r.id 
    LEFT JOIN master.judge j ON j.jcode = rj.judge_id WHERE j.is_retired != 'Y' AND j.display = 'Y' AND rj.display = 'Y' 
    GROUP BY r.id, ro.courtno, ro.courtno, r.jcd
    ORDER BY ro.courtno, r.id, min(j.jcode)";
        //pr($sql);
        $query = $db->query($sql);
        if ($query->getNumRows() >= 1) {
            $return = $query->getResultArray();
        }
        return $return;
    }

    function get_judge_rost_for_trans($p1, $cldt, $jud_count, $board_type)
    {
        //$cldt = '2023-10-11';
        //$p1 = "F";
        $db = \Config\Database::connect();
        $return = [];
        $cldt =  date('Y-m-d', strtotime($cldt));
        if ($p1 == "M") {
            $m_f = "AND r.m_f = '1'";
            if ($board_type == 'R')
                $from_to_dt = "AND r.to_date = '0000-00-00'";
            else
                $from_to_dt = "AND r.from_date = '$cldt' ";
        } else if ($p1 == "L") {
            $m_f = "AND r.m_f = '3'";
            $from_to_dt = "AND r.from_date = '$cldt' ";
        } else if ($p1 == "S") {
            $m_f = "AND r.m_f = '4'";
            $from_to_dt = "AND r.from_date = '$cldt' ";
        } else {
            $m_f = "AND r.m_f = '2'";
            $from_to_dt = "AND r.from_date = '$cldt' ";
        }

        if ($board_type == 'C') {
            $board_type_cc = "and (mb.board_type_mb = 'C' OR mb.board_type_mb = 'CC')";
        } else {
            $board_type_cc = "and mb.board_type_mb = '$board_type'";
        }

        $sql = "SELECT r.id, STRING_AGG(j.jcode::text, ',' ORDER BY j.judge_seniority) AS jcd, STRING_AGG(CONCAT(j.first_name, ' ', j.sur_name), ','
            ORDER BY j.judge_seniority) AS jnm, rb.bench_no, mb.abbr, r.tot_cases, r.courtno, mb.board_type_mb 
            FROM master.roster r 
            LEFT JOIN master.roster_bench rb ON rb.id = r.bench_id 
            LEFT JOIN master.master_bench mb ON mb.id = rb.bench_id 
            LEFT JOIN master.roster_judge rj ON rj.roster_id = r.id
            LEFT JOIN master.judge j ON j.jcode = rj.judge_id
            WHERE j.is_retired != 'Y' 
            AND j.display = 'Y' 
            AND rj.display = 'Y' 
            AND rb.display = 'Y' 
            AND mb.display = 'Y' 
            AND r.display = 'Y' 
            $board_type_cc 
            $m_f 
            $from_to_dt 
            GROUP BY r.id,rb.bench_no,mb.abbr,mb.board_type_mb ORDER BY r.courtno, r.id";
        //pr($sql);        
        $query = $db->query($sql);
        if ($query->getNumRows() >= 1) {
            $return = $query->getResultArray();
        }
        return $return;
    }


    function getCivilCriminalCounts($cldt, $p1, $roster_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('heardt h');
        $builder->select("SUM(CASE WHEN m.case_grp = 'C' THEN 1 ELSE 0 END) AS civil, 
                        SUM(CASE WHEN m.case_grp = 'R' THEN 1 ELSE 0 END) AS criminal");
        $builder->join('main m', 'h.diary_no = m.diary_no', 'left');
        $builder->where('h.next_dt', $cldt);
        $builder->where('h.roster_id', $roster_id);
        $builder->where('h.mainhead', $p1);
        $builder->whereIn('h.main_supp_flag', [1, 2]);
        $builder->groupStart()
            ->where('h.diary_no = h.conn_key')
            ->orWhere('CAST(h.conn_key AS text)', '')
            ->orWhere('h.conn_key IS NULL')
            ->groupEnd();
        $builder->groupBy('h.judges');
        $query = $builder->get();
        return $query->getRowArray();
    }


    function flashSession()
    {
        $session = session();
        $flash_msg = $session->get('flash_msg');
        if ($flash_msg) {
            session()->unset('flash_msg');
            return session()->setFlashdata('success', $flash_msg);
        }
    }


    function get_ma_info($c_type, $c_no, $c_yr)
    {
        $db = \Config\Database::connect();
        $ex_explode = explode('-', $c_no);
        $lct_caseno = [];

        for ($index = 0; $index < count($ex_explode); $index++) {
            $lct_caseno[] = $ex_explode[$index];
        }

        $builder = $db->table('lowerct');
        $builder->distinct()
            ->select('diary_no')
            ->where('lct_casetype', $c_type)
            ->whereIn('lct_caseno', $lct_caseno)
            ->where('lct_caseyear', $c_yr)
            ->where('lw_display', 'Y');
        $query = $builder->get();

        $results = $query->getResultArray();
        $outer_array = array();
        foreach ($results as $row) {
            $inner_array = array();
            $inner_array[0] = $row['diary_no'];
            $outer_array[]  = $inner_array;
        }
        return $outer_array;
    }

    function get_ia($dn)
    {
        $db = \Config\Database::connect();
        $ian_p_conn = "";
        $sql_ian_conn = "select a.diary_no,a.doccode,a.doccode1,a.docnum,a.docyear,a.filedby,a.docfee,a.forresp,a.feemode,a.ent_dt,a.other1,a.iastat,b.docdesc from docdetails a,  master.docmaster b  where a.doccode=b.doccode and a.doccode1=b.doccode1 and a.diary_no='" . $dn . "' and a.doccode=8 and a.display='Y' order by ent_dt";
        $results_ian_conn = $db->query($sql_ian_conn);
        $iancntr_conn = 1;
        if (!empty($results_ian_conn->getNumRows())) {
            $ian_p_inhdt = $listed_ia_conn = "";
            $sql_ian_inhdt = "select listed_ia from heardt  where diary_no='" . $dn . "'";
            $results_ian_inhdt = $db->query($sql_ian_inhdt);
            if (!empty($results_ian_inhdt->getNumRows())) {
                $row_ian_inhdt = $results_ian_inhdt->getRowArray();
                $listed_ia_conn = $row_ian_inhdt["listed_ia"];
            }
            $results_ian_conn->getResultArray();
            foreach ($results_ian_conn as $row_ian_conn) {
                if ($ian_p_conn == "" and $row_ian_conn["iastat"] == "P") {
                    $ian_p_conn = "<div style='overflow:auto; max-height:100px;'><table border='1' bgcolor='#F5F5FC' class='tbl_hr' width='98%' cellspacing='0' cellpadding='3'>";
                }
                if ($row_ian_conn["other1"] != "")
                    $t_part_conn = $row_ian_conn["docdesc"] . " [" . $row_ian_conn["other1"] . "]";
                else
                    $t_part_conn = $row_ian_conn["docdesc"];
                $t_ia_conn = "";
                if ($row_ian_conn["iastat"] == "P")
                    $t_ia_conn = "<font color='blue'>" . $row_ian_conn["iastat"] . "</font>";
                if ($row_ian_conn["iastat"] == "D")
                    $t_ia_conn = "<font color='red'>" . $row_ian_conn["iastat"] . "</font>";
                if ($row_ian_conn["iastat"] == "P")
                {
                    $t_iaval_conn = $row_ian_conn["docnum"] . "/" . $row_ian_conn["docyear"] . ",";
                    if (strpos($listed_ia_conn, $t_iaval_conn) !== false)
                        $check = "checked='checked'";
                    else
                        $check = "";
                    $ian_p_conn .= "<tr><td align='center'><input type='checkbox' name='cn_ia_" . $row_ian_conn["diary_no"] . "_" . $iancntr_conn . "' id='cn_ia_" . $row_ian_conn["diary_no"] . "_" . $iancntr_conn . "' value='" . $row_ian_conn["diary_no"] . "|#|" . $row_ian_conn["docnum"] . "/" . $row_ian_conn["docyear"] . "|#|" . str_replace("XTRA", "", $t_part_conn) . "' onClick='feed_rmrk_conn(\"" . $row_ian_conn["diary_no"] . "\");' " . $check . "></td><td align='center'>" . $row_ian_conn["docnum"] . "/" . $row_ian_conn["docyear"] . "</td><td align='left'>" . str_replace("XTRA", "", $t_part_conn) . "</td><td align='center'>" . date("d-m-Y", strtotime($row_ian_conn["ent_dt"])) . "</td></tr>";
                }
                $iancntr_conn++;
            }
        }
        if ($ian_p_conn != "")
            $ian_p_conn .= "</table></div>";
        return $ian_p_conn;
    }

    function revertDate_hiphen_upd($date)
    {
        
        if (empty($date) || !is_string($date)) {
            return '';
        }
        $parts = explode('-', $date);
        if (count($parts) !== 3) {
            return '';
        }
        return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
    }
