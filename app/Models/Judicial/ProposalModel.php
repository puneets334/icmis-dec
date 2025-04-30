<?php

namespace App\Models\Judicial;

use CodeIgniter\Model;
use CodeIgniter\Database\Exceptions\DatabaseException;

class ProposalModel extends Model
{

    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    public function upd_ins_conn_cases_proposal($data=[])
    {
        // Load the database connection
        $builder = $this->db->table('conct');

        // Fetch the connection data
        $query = $builder->select('*')
                        ->where('conn_key', $data['cc'])
                        ->where('diary_no', $data['dn'])
                        ->get();

        if ($query->getNumRows() > 0) {
            $row_conn = $query->getRowArray(); // Fetch result as associative array
            if ($row_conn['conn_key'] != $data['cc'] || $row_conn['diary_no'] != $data['dn'] || $row_conn['list'] != $data['list'] || $row_conn['usercode'] != $data['uc']) {

                // Insert into conct_history
                $builder_history = $this->db->table('conct_history');
                $builder_history->insert(
                    [
                        'conn_key' => $row_conn['conn_key'],
                        'diary_no' => $row_conn['diary_no'],
                        'list' => $row_conn['list'],
                        'usercode' => $data['uc'],
                        'ent_dt' => date('Y-m-d H:i:s') // Current date-time
                    ]
                );

                // Update the conct table
                $builder->set('list', $data['list'])
                        ->set('usercode', $data['uc'])
                        ->set('ent_dt', 'NOW()', false) // Use PostgreSQL NOW() function
                        ->where('conn_key', $data['cc'])
                        ->where('diary_no', $data['dn'])
                        ->update();
            }
        } else {
            echo 'ERROR: Something went wrong.';
        }
    }

    public function insert_rec_prop($data = [])
    {

        $builder = $this->db->table('heardt h');

        // Prepare the insert data based on the SELECT query
        $builder->select('h.diary_no, h.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, h.board_type, h.usercode, h.ent_dt, h.module_id, h.mainhead_n, h.subhead_n, h.main_supp_flag, h.listorder, h.tentative_cl_dt, m.lastorder, h.listed_ia, h.sitting_judges, h.list_before_remark, h.is_nmd, h.no_of_time_deleted')
            ->join('main m', 'h.diary_no = m.diary_no')
            ->where('h.diary_no', $data['diaryno']);

        // Insert into `last_heardt` using the data from the SELECT statement
        $row_query = $builder->getCompiledSelect();

        // Now perform the insert
        $insertQuery = "INSERT INTO last_heardt (diary_no, conn_key, next_dt, mainhead, subhead, clno, brd_slno, roster_id, judges, coram, board_type, usercode, ent_dt, module_id, mainhead_n, subhead_n, main_supp_flag, listorder, tentative_cl_dt, lastorder, listed_ia, sitting_judges, list_before_remark, is_nmd, no_of_time_deleted) "
            . $row_query;

        $this->db->query($insertQuery);

        // Initialize the variables
        $mainh_n = $subh_n = $msubh_n = "";
        if ($data['mf'] == 'F') {
            $mainh_n = $data['mf'];
            $subh_n = $data['sh'];
        }

        $current_mh = "";
        $da_upd = "";
        $da_upd_where = "";
        $da_upd_msg = "";

        // Check if a record exists for the given diary_no
        $builder = $this->db->table('heardt');
        $builder->select('*')->where('diary_no', $data['diaryno']);

        // If a record is found, update it
        if ($builder->countAll(false) > 0) {

            $t_row_sql2 = $builder->get()->getRowArray();

            $current_mh = $t_row_sql2['mainhead'];

            if ($mainh_n == '') {
                if ($t_row_sql2['mainhead_n'] == 'F' || $data['mf'] == 'L' || $data['mf'] == 'S') {
                    $mainh_n = $t_row_sql2['mainhead_n'];
                    $subh_n = $t_row_sql2['subhead_n'];
                } else {
                    $mainh_n = $data['mf'];
                    $subh_n = $data['sh'];
                }
            }

            // Default values for update
            $t_lst_case = "N";
            $t_brd_slno = 0;
            $t_clno = 0;
            $t_sremcode = 0;
            if ($data['sh'] == '') $sh = 0;

            $builder = $this->db->table('heardt');
            
            // Update the record
            $update_data = [
                'conn_key' => $data['conn_key'],
                'next_dt' => $data['thdt_new'],
                'mainhead' => $data['mf'],
                'subhead' => $data['sh'],
                'brd_slno' => $t_brd_slno,
                'clno' => $t_clno,
                'roster_id' => 0,
                'judges' => '',
                'board_type' => $data['jrc'],
                'usercode' => $data['ucode'],
                'ent_dt' => 'NOW()',  // PostgreSQL function, works in CI4 with `set`
                'module_id' => $data['module_id'],
                'mainhead_n' => $mainh_n,
                'subhead_n' => $subh_n,
                'main_supp_flag' => $data['t_rnr'],
                'listorder' => $data['lo'],
                'tentative_cl_dt' => $data['thdt_new'],
                'listed_ia' => $data['ias'],
                'sitting_judges' => $data['sj']
            ];

            $builder->where('diary_no', $data['diaryno']);
            $builder->update($update_data);

        } else {

            // If no record is found, insert a new one
            if ($data['sh'] == '') $data['sh'] = 0;
            
            $builder = $this->db->table('heardt');

            $insert_data = [
                'diary_no' => $data['diaryno'],
                'conn_key' => $data['conn_key'],
                'next_dt' => $data['thdt_new'],
                'mainhead' => $data['mf'],
                'subhead' => $data['sh'],
                'clno' => 0,
                'brd_slno' => 0,
                'roster_id' => 0,
                'judges' => '',
                'board_type' => $data['jrc'],
                'usercode' => $data['ucode'],
                'ent_dt' => 'NOW()', // Use PostgreSQL current timestamp
                'module_id' => $data['module_id'],
                'mainhead_n' => $data['mf'],
                'subhead_n' => $data['sh'],
                'main_supp_flag' => $data['t_rnr'],
                'listorder' => $data['lo'],
                'tentative_cl_dt' => $data['thdt_new'],
                'listed_ia' => $data['ias'],
                'sitting_judges' => $data['sj'],
                'list_before_remark' => 0
            ];

            $builder->insert($insert_data);
        }

        // Prepare $t_mf based on $mf value
        $t_mf = ($data['mf'] == 'F') ? 'F' : 'M';

        // Update 'main' table to set 'mf_active'
        $builder = $this->db->table('main');
        $builder->set('mf_active', $t_mf)
                ->where('diary_no', $data['diaryno'])
                ->update();


        // Prepare the builder for 'brdrem' table
        $builder = $this->db->table('brdrem');

        // Check if a record with the given diary_no exists
        $row_brdrem = $builder->where('diary_no', $data['diaryno'])->get()->getRowArray();

        if (!empty($row_brdrem)) {
            // If a record exists and the remark is different, we perform an update and insert into history
            if ($row_brdrem['remark'] != $data['br']) {
                // Insert into brdrem_his (history table)
                $data_brdrem_his = [
                    'diary_no' => $row_brdrem['diary_no'],
                    'remark' => $row_brdrem['remark'],
                    'usercode' => $row_brdrem['usercode'],
                    'ent_dt' => $row_brdrem['ent_dt'],
                    'bh_usercode' => $data['ucode'],
                    'bh_entdt' => date('Y-m-d H:i:s')
                ];

                $builder_his = $this->db->table('brdrem_his');
                $builder_his->insert($data_brdrem_his);

                // Update brdrem table
                $data_brdrem_update = [
                    'remark' => $data['br'],
                    'usercode' => $data['ucode'],
                    'ent_dt' => date('Y-m-d H:i:s')
                ];
                $builder->set($data_brdrem_update)
                        ->where('diary_no', $data['diaryno'])
                        ->update();
            }
        } else {
            // If no record exists, insert a new record into brdrem
            $data_brdrem_insert = [
                'diary_no' => $data['diaryno'],
                'remark' => $data['br'],
                'usercode' => $data['ucode'],
                'ent_dt' => date('Y-m-d H:i:s')
            ];
            $builder->insert($data_brdrem_insert);
        }

        if ($data['tcntr'] > 0) {
            foreach ($data['connlist'] as $key => $value) {
                $ccfil_no = $value[0];
                $ccia = $value[1];
                $ccbrdrem = addslashes($value[2]);
                $cclist = $value[3];
                $this->upd_ins_conn_cases_proposal($ccfil_no, $data['diaryno'], $cclist, $data['ucode']);
                if ($cclist == "Y" and $data['t_rnr'] == 0)
                    $t_msf = 0;
                else
                    $t_msf = 3;

                // Prepare the insert data based on the SELECT query
                $builder->select('h.diary_no, h.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, h.board_type, h.usercode, h.ent_dt, h.module_id, h.mainhead_n, h.subhead_n, h.main_supp_flag, h.listorder, h.tentative_cl_dt, m.lastorder, h.listed_ia, h.sitting_judges, h.list_before_remark, h.is_nmd, h.no_of_time_deleted')
                ->from('heardt h')
                ->join('main m', 'h.diary_no = m.diary_no')
                ->where('h.diary_no', $ccfil_no);

                // Insert into `last_heardt` using the data from the SELECT statement
                $row_query = $builder->getCompiledSelect();

                // Now perform the insert
                $insertQuery = "INSERT INTO last_heardt (diary_no, conn_key, next_dt, mainhead, subhead, clno, brd_slno, roster_id, judges, coram, board_type, usercode, ent_dt, module_id, mainhead_n, subhead_n, main_supp_flag, listorder, tentative_cl_dt, lastorder, listed_ia, sitting_judges, list_before_remark, is_nmd, no_of_time_deleted) "
                . $row_query;  // This will append the SELECT statement from above.

                $this->db->query($insertQuery);

                // Load the database connection
                $builder = $this->db->table('heardt');

                // Prepare the mainhead_n condition
                $mainh_n1 = '';
                if ($data['mf'] == 'F') {
                    $mainh_n1 = $data['mf'];
                }

                // Retrieve record from heardt table
                $t_sql2 = $builder->select('*')
                    ->where('diary_no', $ccfil_no)
                    ->get();

                if ($t_sql2->getNumRows() > 0) {
                    // Record found
                    $t_row_sql2 = $t_sql2->getRowArray();

                    // Determine the value of mainhead_n1
                    if (empty($mainh_n1)) {
                        if ($t_row_sql2['mainhead_n'] == 'F' || $data['mf'] == 'L' || $data['mf'] == 'S') {
                            $mainh_n1 = $t_row_sql2['mainhead_n'];
                        } else {
                            $mainh_n1 = $data['mf'];
                        }
                    }

                    $t_brd_slno = 0;
                    $t_clno = 0;

                    // Check for cclist and update the record accordingly
                    if ($cclist == 'Y') {
                        // Prepare update data
                        $update_data = [
                            'conn_key' => $data['conn_key'],
                            'next_dt' => $data['thdt_new'],
                            'mainhead' => $data['mf'],
                            'subhead' => $data['sh'],
                            'brd_slno' => $t_brd_slno,
                            'clno' => $t_clno,
                            'roster_id' => 0,
                            'judges' => '',
                            'board_type' => $data['jrc'],
                            'usercode' => $data['ucode'],
                            'ent_dt' => date('Y-m-d H:i:s'),  // Set current timestamp
                            'module_id' => $data['module_id'],
                            'mainhead_n' => $mainh_n1,
                            'subhead_n' => $subh_n,
                            'main_supp_flag' => $data['t_rnr'],
                            'listorder' => $data['lo'],
                            'tentative_cl_dt' => $data['thdt_new'],
                            'listed_ia' => $data['ccia'],
                            'sitting_judges' => $data['sj']
                        ];

                        // Update the heardt table
                        $builder->where('diary_no', $ccfil_no);
                        $builder->update($update_data);
                    } else {
                        // Update conn_key to empty if cclist is not 'Y'
                        $builder->set('conn_key', '')
                                ->where('diary_no', $ccfil_no)
                                ->update();
                    }
                } else {
                    // Record not found, insert a new record
                    if ($cclist == 'Y') {
                        $insert_data = [
                            'diary_no' => $ccfil_no,
                            'conn_key' => $data['conn_key'],
                            'next_dt' => $data['thdt_new'],
                            'mainhead' => $data['mf'],
                            'subhead' => $data['sh'],
                            'clno' => 0,
                            'brd_slno' => 0,
                            'roster_id' => 0,
                            'judges' => '',
                            'board_type' => $data['jrc'],
                            'usercode' => $data['ucode'],
                            'ent_dt' => date('Y-m-d H:i:s'),
                            'module_id' => $data['module_id'],
                            'mainhead_n' => $data['mf'],
                            'subhead_n' => $data['sh'],
                            'main_supp_flag' => $t_msf,
                            'listorder' => $data['lo'],
                            'tentative_cl_dt' => $data['thdt_new'],
                            'listed_ia' => $data['ccia'],
                            'sitting_judges' => $data['sj'],
                            'list_before_remark' => 0
                        ];

                        // Insert into the heardt table
                        $builder->insert($insert_data);
                    }
                }

                if ($cclist == "Y") {

                    // Get the database connection
                    $builder = $this->db->table('main');
                    $builder_brdrem = $this->db->table('brdrem');
                    $builder_brdrem_his = $this->db->table('brdrem_his');

                    // Update the 'main' table to set 'mf_active'
                    $builder->set('mf_active', $t_mf)
                            ->where('diary_no', $ccfil_no)
                            ->update();

                    // Check if a record exists in the 'brdrem' table
                    $brdrem_row = $builder_brdrem->select('*')
                                                ->where('diary_no', $ccfil_no)
                                                ->get()
                                                ->getRowArray();

                    if (!empty($brdrem_row)) {
                        // If the remark has changed, update the 'brdrem' table
                        if ($brdrem_row['remark'] != $ccbrdrem) {
                            // Insert into brdrem_his
                            $builder_brdrem_his->insert([
                                'diary_no' => $ccfil_no,
                                'remark' => $brdrem_row['remark'],
                                'usercode' => $data['ucode'],
                                'ent_dt' => date('Y-m-d H:i:s')
                            ]);

                            // Update the 'brdrem' table
                            $builder_brdrem->set([
                                'remark' => $ccbrdrem,
                                'usercode' => $data['ucode'],
                                'ent_dt' => date('Y-m-d H:i:s')
                            ])
                            ->where('diary_no', $ccfil_no)
                            ->update();
                        }
                    } else {
                        // If no record exists in brdrem, insert a new record
                        $builder_brdrem->insert([
                            'diary_no' => $ccfil_no,
                            'remark' => $ccbrdrem,
                            'usercode' => $data['ucode'],
                            'ent_dt' => date('Y-m-d H:i:s')
                        ]);
                    }
                }
            }
        }
    }

    public function get_tentative_date_after_change($diary_no, $mainhead_after_change, $board_type_after_change, $next_dt_after_change, $listorder_after_change, $prev_board, $lastListedOn)
    {
        // added by preeti on 17.6.2019
        $prev_next_dt = "";
        $prev_tentative_cl_dt = "";

        // $sql_heardt = "select mainhead, listorder, board_type, next_dt, tentative_cl_dt, is_nmd,coram from heardt h where diary_no='$diary_no' ";
        // $result_heardt = mysql_query($sql_heardt) or die("Error: " . __LINE__ . mysql_error());
        // $row_heardt = mysql_fetch_array($result_heardt);

        // Write the query using Query Builder
        $builder = $this->db->table('heardt');
        $builder->select('mainhead, listorder, board_type, next_dt, tentative_cl_dt, is_nmd, coram');
        $builder->where('diary_no', $diary_no);

        // echo $builder->getCompiledSelect();

        // Execute the query and get the result
        $query = $builder->get();

        // Fetch the result as an associative array
        $row_heardt = $query->getRowArray();
        // pr($row_heardt);
        //  $sql_senior_judge = "select group_concat(jcode) as jcode from (select jcode from judge where is_retired ='N' and display='Y' and jtype='J' order by date(appointment_date),judge_seniority limit 4)t";
        //  $result_judge = mysql_query($sql_senior_judge) or die("Error: " . __LINE__ . mysql_error());
        //  $row_judge = mysql_fetch_array($result_judge);

        $final_next_dt = "";
        $prev_board_type = "";
        $is_nmd = "";
        if (!empty($row_heardt)) {
            $prev_mainhead = $row_heardt['mainhead'];
            $prev_listorder = $row_heardt['listorder'];
            $prev_board_type = $row_heardt['board_type'];
            $prev_next_dt = $row_heardt['next_dt'];
            $prev_tentative_cl_dt = $row_heardt['tentative_cl_dt'];
            $final_next_dt = (!empty($row_heardt['tentative_cl_dt'])) ? date("d-m-Y", strtotime($row_heardt['tentative_cl_dt'])) : "";
            $is_nmd = $row_heardt['is_nmd'];
            $coram = strtok($row_heardt['coram'], ',');
        }

        // $sql_mc = "select submaster_id from mul_category h where diary_no='$diary_no' and display = 'Y' ";
        // $result_mc = mysql_query($sql_mc) or die("Error: " . __LINE__ . mysql_error());
        // $row_mc = mysql_fetch_array($result_mc);

        // // Write the query using Query Builder
        // $builder = $this->db->table('mul_category');
        // $builder->select('submaster_id');
        // $builder->where('diary_no', $diary_no);
        // $builder->where('display', 'Y');

        // // Execute the query and get the result
        // $query = $builder->get();

        // // Fetch the result as an associative array
        // $row_mc = $query->getRowArray();

        // $submaster_id = $row_mc['submaster_id'];

        // $short_categoary_array = array(343, 15, 16, 17, 18, 19, 20, 21, 22, 23, 341, 353, 157, 158, 159, 160, 161, 162, 163, 166, 173, 175, 176, 322, 222);  //commented on 8.4.2024 to remove short category and first 4 judges concept by preeti
        // $four_senior_judges = explode(',', $row_judge['jcode']);   //commented on 8.4.2024 to remove short category and first 4 judges concept by preeti
        $current_date = date('Y-m-d');
        $final_next_dt = "";
        if (($board_type_after_change == 'C' or $board_type_after_change == 'R') and ($prev_board_type == 'J')) {
            //$final_next_dt = date('d-m-Y', strtotime($current_date . '+7 day'));

            // $sql_next_date = "select working_date from sc_working_days where working_date>=(select date_add(curdate(), interval 7 day)) and is_holiday=0 and display='Y' order by working_date ASC limit 1";
            // $result_next_date = mysql_query($sql_next_date) or die("Error: " . __LINE__ . mysql_error());
            // $row_next_date = mysql_fetch_array($result_next_date);

            // Write the query using Query Builder
            $builder = $this->db->table('master.sc_working_days');
            $builder->select('working_date');
            $builder->where('working_date >=', "CURRENT_DATE + INTERVAL '7 days'", false); // Use false to prevent escaping
            $builder->where('is_holiday', 0);
            $builder->where('display', 'Y');
            $builder->orderBy('working_date', 'ASC');
            $builder->limit(1);

            // Execute the query and get the result
            $query = $builder->get();

            // Fetch the result as an associative array
            $row_next_date = $query->getRowArray();

            $final_next_dt = date('d-m-Y', strtotime($row_next_date['working_date']));
        } else if ($prev_board == 'J' and  $board_type_after_change == 'J' and $prev_board_type != 'J') { //$final_next_dt = $lastListedOn;
            $final_next_dt = date("d-m-Y", strtotime($lastListedOn));
            //echo "B";
        } else {
            //echo "C";
            //end

            //if($mainhead_after_change == 'F' OR $is_nmd == 'Y' OR in_array($submaster_id, $short_categoary_array)) {
            if ($mainhead_after_change == 'F' or $is_nmd == 'Y') {
                $is_nmd_short_case = 1;
                $is_nmd_condition = " EXTRACT(DOW FROM s.working_date) = 3 ";
                //  } else if (in_array($submaster_id, $short_categoary_array) and $board_type_after_change == 'J') {   //commented on 8.4.2024 to remove short category and first 4 judges concept by preeti
                /*$is_nmd_short_case = 0;*/
                // $is_nmd_short_case = 1;//on 25032019
            } else {
                $is_nmd_short_case = 0;
                $is_nmd_condition = " is_nmd=0 ";
            }
            if ($listorder_after_change == 4 or $listorder_after_change == 5 or $listorder_after_change == 49) {
                $final_next_dt = $next_dt_after_change;
            } else if ($prev_tentative_cl_dt == "" or $prev_tentative_cl_dt == "0000-00-00") {
                /*$working_day_as_per_fresh = nmd_misc_after_desired_dt($current_date);*/
                $working_day_as_per_fresh = $this->nmd_misc_after_desired_dt($is_nmd_short_case, $current_date); //on 25032019
                $final_next_dt = date("d-m-Y", strtotime($working_day_as_per_fresh));
            } /*else if($board_type_after_change == 'J' AND $mainhead_after_change=='M' and in_array($submaster_id, $short_categoary_array) and in_array($coram,$four_senior_judges))
            {
                $working_day_as_per_fresh = nmd_misc_after_desired_dt($current_date);
                $final_next_dt = date("d-m-Y", strtotime($working_day_as_per_fresh));
            }*/ else if ($prev_tentative_cl_dt <= date('Y-m-d')) {
                if ($listorder_after_change != 4 and $listorder_after_change != 5) {
                    if ($board_type_after_change == 'J' and $listorder_after_change != 32) {
                        if (($board_type_after_change == 'J') and ($mainhead_after_change == 'M' and $is_nmd == 'Y') /*and in_array($submaster_id, $short_categoary_array) and in_array($coram, $four_senior_judges)*/) {  //commented on 8.4.2024 to remove short category and first 4 judges concept by preeti
                            //$working_day_as_per_fresh = nmd_misc_after_desired_dt($current_date);
                            $working_day_as_per_fresh = $this->nmd_misc_after_desired_dt($is_nmd_short_case, $current_date); //on 25032019
                            $final_next_dt = date("d-m-Y", strtotime($working_day_as_per_fresh));
                        } else {
                            // $sql_future = "select s.working_date from (select working_date from (select s.working_date from sc_working_days s
                            //     left join advance_cl_printed a on a.next_dt = s.working_date and a.display = 'Y' and a.board_type='J'
                            //     where s.sec_list_dt <= curdate() and s.sec_list_dt != '0000-00-00' and s.is_holiday = 0 and s.display = 'Y'
                            //     and a.next_dt is null 
                            //     union 
                            //     select date_add(curdate(), interval 17 day) working_date
                            //     ) t order by working_date DESC limit 0,1) t
                            //     left join sc_working_days s on s.working_date >= t.working_date
                            //     where s.is_holiday = 0 and s.display = 'Y' and $is_nmd_condition
                            //     order by s.working_date ASC";
                            // $result_future = mysql_query($sql_future) or die("Error: " . __LINE__ . mysql_error());
                            // $row_future = mysql_fetch_array($result_future);

                            // Load the database connection
                            // $db = \Config\Database::connect();

                            // Define your base query for future working date
                            $builder = $this->db->table('master.sc_working_days s');
                            $builder->select('s.working_date');

                            // Subquery part (for the union query)
                            $subquery = $this->db->table('master.sc_working_days s')
                                ->select('s.working_date')
                                ->join('advance_cl_printed a', "a.next_dt = s.working_date AND a.display = 'Y' AND a.board_type = 'J'", 'left')
                                ->where('s.sec_list_dt <= CURRENT_DATE')
                                // ->where('s.sec_list_dt::text !=', '0000-00-00')
                                ->where('s.is_holiday', 0)
                                ->where('s.display', 'Y')
                                ->where('a.next_dt IS NULL')
                                ->union(
                                    $this->db->table('master.sc_working_days')->select('(CURRENT_DATE + 17) AS working_date')
                                )
                                ->orderBy('working_date', 'DESC')
                                ->limit(1);

                            // Main query joining with the subquery
                            $builder->join('(' . $subquery->getCompiledSelect() . ') t', 's.working_date >= t.working_date', 'left')
                                ->where('s.is_holiday', 0)
                                ->where('s.display', 'Y')
                                ->where($is_nmd_condition)  // Assuming $is_nmd_condition is a predefined variable for the condition
                                ->orderBy('s.working_date', 'ASC');

                            // Execute the query and get the result
                            $query = $builder->get();

                            // Fetch the result as an associative array
                            $row_future = $query->getRowArray();

                            $final_next_dt = date("d-m-Y", strtotime($row_future['working_date']));
                        }
                    } else {
                        //$working_day_as_per_fresh = nmd_misc_after_desired_dt($current_date);
                        $working_day_as_per_fresh = $this->nmd_misc_after_desired_dt($is_nmd_short_case, $current_date); //on 25032019
                        if (!empty($working_day_as_per_fresh)) {
                            $final_next_dt = date("d-m-Y", strtotime($working_day_as_per_fresh));
                        } else {
                            $final_next_dt = "";
                        }
                    }
                }
            } else {
                // Define the table name and create the query using Query Builder
                $builder = $this->db->table('master.sc_working_days s');

                // Add the WHERE conditions
                $builder->select('working_date');
                $builder->where('display', 'Y');
                $builder->where('is_holiday', 0);
                $builder->where($is_nmd_condition);  // Assuming $is_nmd_condition is a predefined condition
                $builder->where('working_date >=', $prev_tentative_cl_dt);

                // Order by working_date and limit the result to 1
                $builder->orderBy('working_date', 'ASC');
                $builder->limit(1);

                // Execute the query and get the result
                $query = $builder->get();

                // Fetch the result as an associative array
                $row_future = $query->getRowArray();


                // //already having future dates
                // $sql_future = "SELECT working_date FROM
                // sc_working_days s WHERE display = 'Y' and is_holiday = 0 and $is_nmd_condition and 
                // working_date >= '$prev_tentative_cl_dt' order by working_date asc LIMIT 1;";
                // $result_future = mysql_query($sql_future) or die("Error: " . __LINE__ . mysql_error());
                // $row_future = mysql_fetch_array($result_future);
                $final_next_dt = date("d-m-Y", strtotime($row_future['working_date']));
                $current_date_plus_3weeks = date('Y-m-d', strtotime($current_date . '+7 weeks'));
                if ($prev_board_type != 'R' and $prev_board_type != 'C' and $row_future['working_date'] > $current_date_plus_3weeks and $board_type_after_change != 'J' and $listorder_after_change != 4 and $listorder_after_change != 5 and $listorder_after_change != 49) {
                    //$working_day_as_per_fresh = nmd_misc_after_desired_dt($current_date);
                    $working_day_as_per_fresh = $this->nmd_misc_after_desired_dt(0, $current_date); //on 25032019
                    $final_next_dt = date("d-m-Y", strtotime($working_day_as_per_fresh));
                } else if (($board_type_after_change == 'J') and ($mainhead_after_change == 'M' and $is_nmd == 'Y') /*and in_array($submaster_id, $short_categoary_array) and in_array($coram, $four_senior_judges)*/ and $listorder_after_change != 4 and $listorder_after_change != 5 and $listorder_after_change != 49) {  //commented on 8.4.2024 to remove short category and first 4 judges concept by preeti
                    // $sql_future = "SELECT working_date FROM
                    // sc_working_days s WHERE display = 'Y' and is_holiday = 0 and $is_nmd_condition and 
                    // working_date >= '$prev_tentative_cl_dt' order by working_date asc LIMIT 1;";
                    // $result_future = mysql_query($sql_future) or die("Error: " . __LINE__ . mysql_error());
                    // $row_future = mysql_fetch_array($result_future);

                    // Define the table name and create the query using Query Builder
                    $builder = $this->db->table('master.sc_working_days s');

                    // Add the WHERE conditions
                    $builder->select('working_date');
                    $builder->where('display', 'Y');
                    $builder->where('is_holiday', 0);
                    $builder->where($is_nmd_condition);
                    $builder->where('working_date >=', $prev_tentative_cl_dt);

                    // Order by working_date and limit the result to 1
                    $builder->orderBy('working_date', 'ASC');
                    $builder->limit(1);

                    // Execute the query and get the result
                    $query = $builder->get();

                    // Fetch the result as an associative array
                    $row_future = $query->getRowArray();

                    $final_next_dt = date("d-m-Y", strtotime($row_future['working_date']));
                }
            }
        } //end of else part
        return $final_next_dt;
    }

    public function nmd_misc_after_desired_dt($flag, $dtfrom)
    {
        $ret1 = "";
        /*$today_time = date("H:i:s");
         commented on 07.09.2019 $sql = "select * from sc_working_days where working_date = '$dtfrom' and display = 'Y' limit 1";
        */
        // $sql = "select * from sc_working_days where working_date >= '$dtfrom' and is_holiday = 0 and display = 'Y' order by working_date asc limit 1";

        // $res = mysql_query($sql) or die("Error: " . __LINE__ . mysql_error());
        // if(mysql_num_rows($res)>0){
        //     $row = mysql_fetch_array($res);


        // Create a query builder instance for the sc_working_days table
        $builder = $this->db->table('master.sc_working_days');

        // Add the necessary conditions
        $builder->select('misc_dt1, nmd_dt');
        $builder->where('working_date >=', $dtfrom);
        $builder->where('is_holiday', 0);
        $builder->where('display', 'Y');

        // Order by working_date ascending and limit the result to 1
        $builder->orderBy('working_date', 'ASC');
        $builder->limit(1);
        
        // echo $builder->getCompiledSelect();die;

        // Execute the query
        $query = $builder->get();

        // Fetch the result as an associative array
        $row = $query->getRowArray();

        // Check if we have a result
        if ($row) {

            //$ret1 = $row[misc_dt1];
            if ($flag == 0)
                $ret1 = $row['misc_dt1'];
            if ($flag == 1)
                $ret1 = $row['nmd_dt'];
            //if($today_time <= strtotime('13:00:00'))
            /*if($today_time <= date("H:i:s", strtotime("13:00:00")))
                $ret1 = $row[misc_dt1];
            else
                $ret1 = $row[misc_dt2];*/
        }

        return $ret1;
    }

    public function check_proposal($diaryno)
    {
    //     $sql = "SELECT * FROM heardt 
    //     WHERE diary_no = '" . $diaryno . "'
    //   AND next_dt >= CURDATE()
    //   AND main_supp_flag IN (1, 2) 
    //   AND clno>0 AND brd_slno>0 AND roster_id>0 and if(next_dt=CURDATE() and curtime()<='16:40:00',1=1,1=0) AND judges!='0'";
    //     $result = mysql_query($sql) or die(mysql_error() . $sql);
    //     if (mysql_affected_rows() > 0) {
    //         echo 'true';
    //     } else {
    //         echo 'false';
    //     }

        $builder = $this->db->table('heardt');

        // Build the query using Query Builder
        $builder->where('diary_no', $diaryno)
            ->where('next_dt >=', date('Y-m-d'))
            ->whereIn('main_supp_flag', [1, 2])
            ->where('clno >', 0)
            ->where('brd_slno >', 0)
            ->where('roster_id >', 0)
            ->where('judges !=', '0');

        $builder->groupStart()
            ->where('next_dt', date('Y-m-d'))
            ->where('\'16:40:00\' > CURRENT_TIME')
            ->groupEnd();

        // echo $builder->getCompiledSelect();

        // Execute the query
        $query = $builder->get();

        // Check the number of rows returned
        if ($query->getNumRows() > 0) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    public function get_mf_subhead($ucode, $mf, $sh, $jrc)
    {
        $condition_board_type = "";
        if ($jrc == 'C') {
            $condition_board_type = "";
        } else if ($jrc == 'S')         // Condition added to show subhead for Single Judge. Added by Preeti Agrawal on 17062022
        {
            $condition_board_type = " board_type ='J'";
        } else {
            $condition_board_type = " board_type ='$jrc' ";
        }
        $sql_t = "";
        if ($mf != "") {
            $listtype = 0;
            if ($mf == "M")
                $listtype = 1;
            if ($mf == "F")
                $listtype = 2;
            if ($mf == "N")
                $listtype = 4;
            if ($mf == "L")
                $listtype = 5;
            if ($mf == "S")
                $listtype = 7;
            if ($mf == "W")
                $listtype = 1;

            //stagecode!=853 and
            //$sql_t = "SELECT * FROM demoas.subheading WHERE listtype = ".$mf." and display='Y'";
            // echo $sql_t="select * from subheading where (listtype='$mf') $condition_board_type and display='Y' and !(stagecode>=201 and stagecode<=212) and !(stagecode>=501 and stagecode<=550) and stagecode!=809  order by stagecode";
            //$sql_t="select * from demoas.subheading where (listtype=$listtype or listtype=3) and display='Y' and stagecode!=809 ".$criteria." order by stagecode";

            // $sql_t="select * from subheading where (listtype='$mf') $condition_board_type and display='Y' and !(stagecode>=201 and stagecode<=212) and !(stagecode>=501 and stagecode<=550) and stagecode!=809  order by stagecode";
            // $results = mysql_query($sql_t);
            // while ($row = mysql_fetch_array($results)) {

            // Initialize the query builder for the subheading table
            $builder = $this->db->table('master.subheading');

            // Build the conditions
            $builder->select('*');
            $builder->where('listtype', $mf); // Bind the $mf variable
            $builder->where('display', 'Y');

            // Add custom conditions dynamically, assuming $condition_board_type is a string containing valid SQL
            if (!empty($condition_board_type)) {
                $builder->where($condition_board_type, null, false); // `false` ensures no escaping for raw SQL parts
            }

            // Add the conditions for stagecode
            $builder->where('stagecode < 201 OR stagecode > 212');
            $builder->where('stagecode < 501 OR stagecode > 550');
            $builder->where('stagecode !=', 809);

            // Order by stagecode
            $builder->orderBy('stagecode');

            // Execute the query and get the results
            $query = $builder->get();

            // Fetch the results as an array of rows
            $results = $query->getResultArray();

            // Loop through the results
            foreach ($results as $row) {

                if ($row["stagecode"] == $sh)
                    $sel = "selected";
                else
                    $sel = "";
                //if($mf=="M" and $admt=="YES" and $row["stagecode"]!="801" and $row["stagecode"]!="808" and $row["stagecode"]!="820" and $row["stagecode"]!="817" and $row["stagecode"]!="850")
                //if(($mf=="M" and $admt=="YES" and ($row["stagecode"]=="815" or $row["stagecode"]=="816")) or ($mf=="M" and $notice=="YES" and ($row["stagecode"]=="811" or $row["stagecode"]=="812")))
                //    if(($mf=="M" and $admt=="YES" and ($row["stagecode"]=="810" or $row["stagecode"]=="853" or $row["stagecode"]=="815" or $row["stagecode"]=="816")) )
                //echo '<option value="'.$row["stagecode"].'" '.$sel.' disabled=disabled>'.$row["stagename"].'</option>';
                //else 
                if (($row["stagecode"] == 818 or $row["stagecode"] == 819) and $ucode != 469)
                    echo '<option value="' . $row["stagecode"] . '" ' . $sel . ' disabled>' . $row["stagename"] . '</option>';
                else
                    echo '<option value="' . $row["stagecode"] . '" ' . $sel . '>' . $row["stagename"] . '</option>';
            }
        } else {
            echo "ERROR";
        }
    }

    public function getRemovalofDefaultList($diaryNo)
    {
        $hd_ud = '';
        $w_wo_dn = " and a.diary_no='$diaryNo'";

        $sql = "SELECT a.*, b.c 
                FROM (
                    SELECT o.*, b.* 
                    FROM (
                        SELECT DISTINCT id, rm_dt, status, a.diary_no, 
                            org_id AS objcode, pet_name, res_name, a.remark, 
                            TO_CHAR(main.diary_no_rec_date, 'YYYY-MM-DD HH24:MI:SS') AS fdt, 
                            save_dt, j1_tot_da, j1_sn_dt, mul_ent 
                        FROM obj_save a 
                        JOIN main ON a.diary_no = main.diary_no 
                        WHERE
                        --rm_dt::text = '0000-00-00 00:00:00'  
                        rm_dt IS NULL
                        AND (status = '7' OR fixed = '9' OR fixed::text = 'A') 
                        AND a.display = 'Y' 
                        $hd_ud 
                        AND main.c_status = 'P' 
                        $w_wo_dn
                    ) o 
                    JOIN (
                        SELECT objdesc AS obj_name, objcode AS oc 
                        FROM master.objection
                    ) b ON b.oc = o.objcode
                ) a 
                JOIN (
                    SELECT COUNT(org_id) AS c, a.diary_no, b.fil_no, rm_dt 
                    FROM obj_save a 
                    JOIN main b ON a.diary_no = b.diary_no 
                    WHERE 
                    -- rm_dt::text = '0000-00-00 00:00:00' 
                    rm_dt IS NULL
                    AND (status = '7' OR fixed = '9' OR fixed = '10') 
                    AND a.display = 'Y' 
                    $hd_ud 
                    AND b.c_status = 'P' 
                    $w_wo_dn 
                    GROUP BY a.diary_no, b.fil_no, rm_dt
                ) b ON a.diary_no = b.diary_no 
                ORDER BY a.diary_no, save_dt;
        ";

        //echo $sql;die;

        $query = $this->db->query($sql);

        if ($query->getNumRows() >= 1)
            return $query->getResultArray();
        else
            return [];
    }

    public function getLastProposed($diary_no = 0) {

        // Build the query using the query builder
         $builder = $this->db->table('last_heardt');
         
         $builder->select('board_type, next_dt, subhead')
         ->where('diary_no', $diary_no)
         ->where('next_dt > CURRENT_DATE') // Equivalent to curdate()
         ->orderBy('next_dt', 'desc')
         ->limit(1);
         
         $query = $builder->get();

        if ($query->getNumRows() >= 1)
            return $query->getRowArray();
        else
            return [];
    }

    public function getListingPurpose() {

        // Build the query using CodeIgniter 4's query builder
        $builder = $this->db->table('master.listing_purpose');
        
        $builder->select("code, CONCAT(code, '. ', purpose) AS lp")
        ->where('code !=', 22)
        ->where('purpose IS NOT NULL')
        ->where('display', 'Y')
        ->orderBy('code');
        
        $query = $builder->get();

        if ($query->getNumRows() >= 1)
            return $query->getResultArray();
        else
            return [];
    }

    public function getTotalHearings($diary_no=0) {

        // First SELECT query for the `heardt` table
        $query1 = $this->db->table('heardt')
        ->select('next_dt')
        ->where('diary_no', $diary_no)
        ->where('board_type', 'J')
        ->where('clno !=', 0)
        ->where('clno IS NOT NULL')
        ->where('brd_slno IS NOT NULL')
        ->where('brd_slno !=', 0)
        ->where('roster_id !=', 0)
        ->where('roster_id IS NOT NULL');

        // Second SELECT query for the `last_heardt` table
        $query2 = $this->db->table('last_heardt')
            ->select('next_dt')
            ->where('diary_no', $diary_no)
            ->where('board_type', 'J')
            ->where('clno !=', 0)
            ->where('clno IS NOT NULL')
            ->where('brd_slno IS NOT NULL')
            ->where('brd_slno !=', 0)
            ->where('roster_id !=', 0)
            ->where('roster_id IS NOT NULL')
            ->groupStart()  // Start a grouped condition
                ->where('bench_flag IS NULL')
                ->orWhere('TRIM(bench_flag)', '')
            ->groupEnd();  // End the grouped condition

        // Combine the two queries using UNION
        $query_list = $query1->union($query2);

        // Execute the query and get the result
        $results = $query_list->get();

        // Get the number of rows returned
        return $results->getNumRows();
    }

    public function getSCHolidays() {
        
        $holiday_dates = [];
        $current_year = date('Y');
        $next_year = $current_year + 1;

        $builder = $this->db->table('master.sc_working_days');
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

    public function getCaseNo($fil_no = [])
    {

        $t_fil_no1 = "";

        $query = $this->db->table('lowerct a')
            ->select('lct_dec_dt, lct_caseno, lct_caseyear, short_description AS type_sname')
            ->join('master.casetype ct', 'ct.casecode = a.lct_casetype', 'left')
            ->where('ct.display', 'Y')
            ->where('a.is_order_challenged', 'Y')
            ->where('a.diary_no', $fil_no['diary_no'])
            ->where('a.lw_display', 'Y')
            ->where('a.ct_code', 4)
            ->orderBy('a.lct_dec_dt')
            ->get();

        $result = $query->getResultArray();

        if (!empty($result)) {

            foreach ($result as $ro_lct) {

                if ($t_fil_no1 == '')
                    $t_fil_no1 .= " IN " . $ro_lct['type_sname'] . " - " . $ro_lct['lct_caseno'] . "/" . $ro_lct['lct_caseyear'];
                else
                    $t_fil_no1 .= ", " . $ro_lct['type_sname'] . " - " . $ro_lct['lct_caseno'] . "/" . $ro_lct['lct_caseyear'];
            }
        }

        $t_fil_no = get_case_nos($fil_no['diary_no'], '&nbsp;&nbsp;') . $t_fil_no1;

        if (trim($t_fil_no) == '') {
            $sql12 =   "SELECT short_description from casetype where casecode='" . $fil_no['casetype_id'] . "'";
            $results12 = mysql_query($sql12) or die(mysql_error() . " SQL:" . $sql12);
            if (mysql_affected_rows() > 0) {
                $row_12 = mysql_fetch_array($results12);
                $t_fil_no = $row_12['short_description'];
            }
        }

        // Getting the from Case filter but we are using diary filter 
        // $t_slpcc = '';
        // if ($t_slpcc != '')
        //     $t_slpcc = "<br>" . $t_slpcc;

        // return $t_fil_no . $t_slpcc

        return $t_fil_no;
    }

    public function getDaName($fil_no = [])
    {
        //DA NAME START
        $da_name = "";

        $builder = $this->db->table('public.main a');
        $builder->select('mf_active, a.dacode, name, section_name, casetype_id, active_casetype_id, diary_no_rec_date, reg_year_mh, reg_year_fh, active_reg_year, ref_agency_state_id');
        $builder->join('master.users b', 'a.dacode = b.usercode', 'left');
        $builder->join('master.usersection us', 'b.section = us.id', 'left');
        $builder->where('diary_no', $fil_no['diary_no']);
        // echo $builder->getCompiledSelect();die;
        $results_da = $builder->get();

        // $query->getNumRows();
        // $row_da = $query->getRowArray();
        // pr($row_da);
        // $sql_da = "SELECT mf_active,dacode,name,section_name,casetype_id,active_casetype_id,diary_no_rec_date,reg_year_mh,reg_year_fh,active_reg_year,ref_agency_state_id FROM main a LEFT JOIN users b ON a.dacode = b.usercode LEFT JOIN usersection us ON b.section=us.id WHERE diary_no = '" . $fil_no['diary_no'] . "'  ";
        // $results_da = mysql_query($sql_da) or die(mysql_error() . " SQL:" . $sql_da);

        if ($results_da->getNumRows() > 0) {
            $row_da = $results_da->getRowArray();

            $da_name = "<font color='blue' style='font-size:12px;font-weight:bold;'>" . $row_da["name"] . "</font>";
            //if ($row_da["username"] != "")
            //$da_name.="<font style='font-size:12px;font-weight:bold;'> [</font><font color='green' style='font-size:12px;font-weight:bold;'>" . $row_da["username"] . "</font><font style='font-size:12px;font-weight:bold;'>]</font>";
            if ($row_da["dacode"] != "0")
                $da_name .= "<font style='font-size:12px;font-weight:bold;'> [SECTION: </font><font color='red' style='font-size:12px;font-weight:bold;'>" . $row_da["section_name"] . "</font><font style='font-size:12px;font-weight:bold;'>]</font>";
            else {
                if ($row_da['active_reg_year'] != 0)
                    $ten_reg_yr = $row_da['active_reg_year'];
                else if ($row_da['reg_year_fh'] != 0)
                    $ten_reg_yr = $row_da['reg_year_fh'];
                else if ($row_da['reg_year_mh'] != 0)
                    $ten_reg_yr = $row_da['reg_year_mh'];
                else
                    $ten_reg_yr = date('Y', strtotime($row_da['diary_no_rec_date']));

                if ($row_da['active_casetype_id'] != 0)
                    $casetype_displ = $row_da['active_casetype_id'];
                else if ($row_da['casetype_id'] != 0)
                    $casetype_displ = $row_da['casetype_id'];
                //echo "query ";

                $builder = $this->db->table('public.main');
                $builder->select("tentative_section(diary_no) AS section");
                $builder->where('diary_no', $fil_no['diary_no']);

                $row_section = $builder->get();
                $sec = $row_section->getRowArray();

                $builder = $this->db->table('master.da_case_distribution a');
                $builder->select('a.dacode, c.section_name, b.name');
                $builder->join('master.users b', 'b.usercode = a.dacode', 'left');
                $builder->join('master.usersection c', 'b.section = c.id', 'left');
                $builder->where('case_type', $casetype_displ);
                $builder->where("$ten_reg_yr BETWEEN case_f_yr AND case_t_yr");
                $builder->where('state', $row_da['ref_agency_state_id']);
                $builder->where('a.display', 'Y');

                $query = $builder->get();

                if ($query->getNumRows() > 0) {
                    $section_ten_row = $query->getRowArray();
                    $da_name .= "<font style='font-size:12px;font-weight:bold;'> [Tentative SECTION: </font><font color='red' style='font-size:12px;font-weight:bold;'>" . $sec['section'] . "</font><font style='font-size:12px;font-weight:bold;'>]</font>";
                } else {
                    // echo "no da hence else condition";
                    $diff_da_sec_name = array(5, 6, 7, 8, 39, 9, 10, 19, 20, 25, 26);
                    //  echo $casetype_displ;
                    if (in_array($casetype_displ, $diff_da_sec_name)) {
                        // "dfsdf";
                        $builder = $this->db->table('public.lowerct');
                        $builder->select('ct_code, l_state, lct_casetype, lct_caseno, lct_caseyear');
                        $builder->where('diary_no', $fil_no['diary_no']);
                        $builder->where('lw_display', 'Y');

                        $lower_case_temp = $builder->get();

                        // $lower_case_temp = "SELECT ct_code,l_state,lct_casetype,lct_caseno,lct_caseyear FROM `lowerct` WHERE diary_no = '" . $fil_no['diary_no'] . "'  and lw_display='Y' ";
                        // $lower_case_temp = mysql_query($lower_case_temp) or die(__LINE__ . '->' . mysql_error());

                        if ($lower_case_temp->getRowArray() > 0) {

                            $lower_case_temp_row = $lower_case_temp->getRowArray();

                            /*$for_da_temp = "SELECT diary_no,new_registration_number,SUBSTRING_INDEX(SUBSTRING_INDEX(new_registration_number, '-', 2),'-',-1),SUBSTRING_INDEX(new_registration_number, '-', -1)
                            FROM `main_casetype_history` where ref_new_case_type_id=$lower_case_temp[lct_casetype] and new_registration_year=$lower_case_temp[lct_caseyear]
                            and is_deleted='f' and '".str_pad($lower_case_temp[lct_caseno], 6, 0, STR_PAD_LEFT)."' between SUBSTRING_INDEX(SUBSTRING_INDEX(new_registration_number, '-', 2),'-',-1) and SUBSTRING_INDEX(new_registration_number, '-', -1)";*/

                            $builder = $this->db->table('public.main_casetype_history a');
                            $builder->select([
                                'a.diary_no',
                                'new_registration_number',
                                "SUBSTRING(new_registration_number::text FROM '^[^-]*-([^\\-]*)-?') AS part1",
                                "SUBSTRING(new_registration_number::text FROM '([^\\-]*)$') AS part2",
                                'dacode',
                                'name',
                                'section_name',
                                'casetype_id',
                                'active_casetype_id',
                                'diary_no_rec_date',
                                'reg_year_mh',
                                'reg_year_fh',
                                'active_reg_year',
                                'ref_agency_state_id'
                            ]);

                            $builder->join('public.main b', 'a.diary_no = b.diary_no', 'left');
                            $builder->join('master.users c', 'b.dacode = c.usercode', 'left');
                            $builder->join('master.usersection us', 'c.section = us.id', 'left');

                            $lowerCasetype = $lower_case_temp_row['lct_casetype'];
                            $lowerCaseYear = $lower_case_temp_row['lct_caseyear'];
                            $lctCaseno = str_pad($lower_case_temp_row['lct_caseno'], 6, '0', STR_PAD_LEFT);

                            $builder->where('ref_new_case_type_id', $lowerCasetype);
                            $builder->where('new_registration_year', $lowerCaseYear);
                            $builder->where('is_deleted', 'f');
                            $builder->where("$lctCaseno BETWEEN NULLIF(SUBSTRING(new_registration_number::text FROM '^[^-]*-([^\\-]*)-?'), '')::int AND NULLIF(SUBSTRING(new_registration_number::text FROM '([^\\-]*)$'), '')::int", null, false);

                            // echo $builder->getCompiledSelect();
                            // pr("hello");
                            $for_da_temp = $builder->get();

                            $for_da_temp_row = $for_da_temp->getRowArray();


                            // $for_da_temp = "SELECT a.diary_no,new_registration_number,SUBSTRING_INDEX(SUBSTRING_INDEX(new_registration_number, '-', 2),'-',-1),SUBSTRING_INDEX(new_registration_number, '-', -1),
                            // dacode,name,section_name,casetype_id,active_casetype_id,diary_no_rec_date,reg_year_mh,reg_year_fh,active_reg_year,ref_agency_state_id
                            // FROM `main_casetype_history` a
                            // LEFT JOIN main b ON a.diary_no=b.diary_no
                            // LEFT JOIN users c ON b.dacode = c.usercode
                            // LEFT JOIN usersection us ON c.section=us.id
                            // where ref_new_case_type_id=$lower_case_temp_row[lct_casetype] and new_registration_year=$lower_case_temp_row[lct_caseyear]
                            // and is_deleted='f' and '" . str_pad($lower_case_temp_row['lct_caseno'], 6, 0, STR_PAD_LEFT) . "'
                            // between SUBSTRING_INDEX(SUBSTRING_INDEX(new_registration_number, '-', 2),'-',-1) and SUBSTRING_INDEX(new_registration_number, '-', -1)";
                            //         $for_da_temp = mysql_query($for_da_temp) or die(__LINE__ . '->' . mysql_error());

                            // $for_da_temp_row = mysql_fetch_array($for_da_temp);

                            if ($for_da_temp_row['section_name'] != NULL || $for_da_temp_row['section_name'] != '') {
                                $da_name .= "<font style='font-size:12px;font-weight:bold;'> [Tentative SECTION: </font><font color='red' style='font-size:12px;font-weight:bold;'>" . $for_da_temp_row["section_name"] . "</font><font style='font-size:12px;font-weight:bold;'>]</font>";
                            } else {
                                if ($for_da_temp_row['active_reg_year'] != 0)
                                    $ten_reg_yr = $for_da_temp_row['active_reg_year'];
                                else if ($for_da_temp_row['reg_year_fh'] != 0)
                                    $ten_reg_yr = $for_da_temp_row['reg_year_fh'];
                                else if ($for_da_temp_row['reg_year_mh'] != 0)
                                    $ten_reg_yr = $for_da_temp_row['reg_year_mh'];
                                else
                                    $ten_reg_yr = date('Y', strtotime($for_da_temp_row['diary_no_rec_date']));

                                if ($for_da_temp_row['active_casetype_id'] != 0)
                                    $casetype_displ = $for_da_temp_row['active_casetype_id'];
                                else if ($for_da_temp_row['casetype_id'] != 0)
                                    $casetype_displ = $for_da_temp_row['casetype_id'];


                                $builder = $this->db->table('master.da_case_distribution a');
                                $builder->select('a.dacode, c.section_name, b.name');
                                $builder->join('master.users b', 'b.usercode = a.dacode', 'left');
                                $builder->join('master.usersection c', 'b.section = c.id', 'left');

                                $builder->where('case_type', $casetype_displ);
                                $builder->where("$ten_reg_yr BETWEEN case_f_yr AND case_t_yr", null, false);
                                $builder->where('state', $for_da_temp_row['ref_agency_state_id']);
                                $builder->where('a.display', 'Y');

                                $query = $builder->get();

                                //         $section_ten_q = "SELECT dacode,section_name,name FROM da_case_distribution a
                                // LEFT JOIN users b ON usercode=dacode
                                // LEFT JOIN usersection c ON b.section=c.id
                                // WHERE case_type=$casetype_displ AND $ten_reg_yr BETWEEN case_f_yr AND case_t_yr AND state='$for_da_temp_row[ref_agency_state_id]' AND a.display='Y' ";
                                //         $section_ten_rs = mysql_query($section_ten_q) or die(__LINE__ . '->' . mysql_error());
                                //         $section_ten_row = mysql_fetch_array($section_ten_rs);

                                if ($query->getNumRows() > 0) {
                                    $section_ten_row = $query->getRowArray();
                                    $da_name .= "<font style='font-size:12px;font-weight:bold;'> [Tentative SECTION: </font><font color='red' style='font-size:12px;font-weight:bold;'>" . $section_ten_row["section_name"] . "</font><font style='font-size:12px;font-weight:bold;'>]</font>";
                                }
                            }
                        } else {

                            $builder = $this->db->table('public.main');
                            $builder->select('tentative_section(diary_no) AS section');
                            $builder->where('diary_no', $fil_no['diary_no']);

                            $rs_section = $builder->get();
                            $row_section = $rs_section->getRowArray();

                            $da_name .= "<font style='font-size:12px;font-weight:bold;'> [Tentative SECTION: </font><font color='red' style='font-size:12px;font-weight:bold;'>" . $row_section['section'] . ".</font><font style='font-size:12px;font-weight:bold;'>]</font>";
                        }
                    }
                }
            }
        }

        return ['da_name' => $da_name, 'row_da' => $row_da];
        //DA NAME ENDS
    }


    public function getPetName()
    {
        $sql = mysql_query("Select pet_name,res_name,diary_no,conn_key,fil_no,fil_dt, YEAR(fil_dt) as filyr, DATE_FORMAT(fil_dt,'%d-%m-%Y %h:%i %p') as fil_dt_f, fil_no_fh,DATE_FORMAT(fil_dt_fh,'%d-%m-%Y %h:%i %p') as fil_dt_fh, actcode, pet_adv_id, res_adv_id, lastorder, c_status, if(fil_no!='',SUBSTRING_INDEX(fil_no, '-', 1),'') as ct1,
        if(fil_no!='',SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no, '-', 2), '-', -1 ),'') as crf1, if(fil_no!='',SUBSTRING_INDEX(fil_no, '-', -1),'') as crl1, if(fil_no_fh!='',SUBSTRING_INDEX(fil_no_fh, '-', 1),'') as ct2,
        if(fil_no_fh!='',SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no_fh, '-', 2), '-', -1 ),'') as crf2, if(fil_no_fh!='',SUBSTRING_INDEX(fil_no_fh, '-', -1),'') as crl2,casetype_id from main where substr( diary_no, 1, length( diary_no ) -4 )='" . $_REQUEST['d_no'] . "' and substr( diary_no , -4 )='" . $_REQUEST['d_yr'] . "'") or die('Error: ' . __LINE__ . mysql_error());
        $main_fh_fil_no = "";
        if (mysql_num_rows($sql) > 0) {
            $fil_no = mysql_fetch_array($sql);
            if ($fil_no['diary_no'] != $fil_no['conn_key'] and $fil_no['conn_key'] != '' and $fil_no['conn_key'] != '0')
                $check_for_conn = "N";
            else
                $check_for_conn = "Y";
            if ($fil_no['fil_no_fh'] != '')
                $main_fh_fil_no = "EXIST";
?>
            <!--     <h4 align="center">
                        Supreme Court of India
                    </h4>-->
            <div style="text-align: center">
                <h3>Diary No.- <?php echo $_REQUEST['d_no']; ?> - <?php echo $_REQUEST['d_yr']; ?></h3>
            </div>

<?php
            include("../d_navigation/func.php");
            $d_no_yr = $_REQUEST['d_no'] . $_REQUEST['d_yr'];
            navigate_diary($d_no_yr);
            $sql_ct_type = mysql_query("Select short_description from casetype where casecode='" . $fil_no['casetype_id'] . "' and
                                    display='Y'") or die("Error" . __LINE__ . mysql_error());
            // $res_ct_typ = mysql_result($sql_ct_type);

            $t_res_ct_typ = mysql_fetch_array($sql_ct_type);
            $res_ct_typ = $t_res_ct_typ['short_description'];



            //        $bn_sql = mysql_query("select bench_name from master_bench where display='Y' and id='".$fil_no['bench']."'") or
            //                die("Error: " . __LINE__ . mysql_error());
            // if (mysql_num_rows($bn_sql) > 0) {
            //        $res_bnch = mysql_result($bn_sql, 0);
            // }
            $sql = "SELECT p.sr_no, p.pet_res,p.ind_dep, p.partyname, p.sonof,p.prfhname, p.age,p.sex,p.caste, p.addr1, p.addr2,
		p.pin, p.state, p.city,p.email, p.contact AS mobile,
		p.deptcode,
		(SELECT deptname  FROM  deptt WHERE deptcode=p.deptcode)deptname,c.skey
	      FROM party p
		INNER JOIN main m ON m.diary_no=p.diary_no  and sr_no=1 and pflag in('P','O') and pet_res in ('P','R')
        LEFT JOIN casetype c ON c.casecode=SUBSTRING(m.fil_no,3,3)
        where m.diary_no='" . $fil_no['diary_no'] . "'  order by p.pet_res,p.sr_no";

            $result = mysql_query($sql) or die("Errror: " . __LINE__ . mysql_error() . " party");
            $ctr_p = 0; //for counting petining
            $ctr_r = 0; // for couting respondent

            if (mysql_num_rows($result) > 0) {
                $grp_pet_res = '';
                $pet_name = $res_name = "";
                while ($row = mysql_fetch_array($result)) {
                    $temp_var = "";
                    $t_var = "";
                    $temp_var .= $row['partyname'];
                    if ($row['sonof'] != '') {
                        $temp_var .= $row['sonof'] . "/o " . $row['prfhname'];
                    }
                    if ($row['deptname'] != "") {
                        $temp_var .= "<br>Department : " . $row['deptname'];
                    }
                    $temp_var .= "<br>";
                    if ($row['addr1'] == '')
                        $temp_var .= $row['addr2'];
                    else
                        $temp_var .= $row['addr1'] . ', ' . $row['addr2'];

                    $district = "Select Name from state where State_code='" . $row['state'] . "' and District_code='" . $row['city'] . "' and Sub_Dist_code=0 and Village_code=0 and
                                   display='Y'";
                    $district = mysql_query($district) or die("Error: " . __LINE__ . mysql_error());
                    if (mysql_num_rows($district) > 0) {
                        $t_var = mysql_result($district, 0);
                    }
                    if ($t_var != "")
                        $temp_var .= ", District : " . $t_var;

                    if ($row['pet_res'] == 'P') {
                        $pet_name = $temp_var;
                    } else {
                        $res_name = $temp_var;
                    }
                }

                $pet_name = $fil_no['pet_name'];
                $res_name = $fil_no['res_name'];
            }
        }
    }

    public function getCaseCategories($fil_no = [])
    {
        $id = 0;
        $flag = null;
        $diary_no = $fil_no['diary_no'];
        $mul_category = "";

        $builder1 = $this->db->table("mul_category" . $flag . " mc");
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
                    $mul_category = $category_nm;
                } else {
                    $mul_category = $mul_category . ',<br> ' . $category_nm;
                }

                $id = $row2['id'];
            }
        }

        // list($mul_category, $category_id) = get_mul_category($fil_no['diary_no']);

        return ['mul_category' => $mul_category, 'category_id' => $id];
    }

    public function getActInfo($fil_no = [])
    {
        $act_section = "";

        $query = $this->db->table('act_main a')
            ->select('a.act, string_agg(b.section, \', \') AS section, a.act AS act_name')
            ->join('master.act_section b', 'a.id = b.act_id', 'left')
            ->join('master.act_master c', 'c.id = a.act')
            ->where('diary_no', $fil_no['diary_no'])
            ->where('a.display', 'Y')
            ->where('b.display', 'Y')
            ->where('c.display', 'Y')
            ->groupBy('a.act')
            ->get();

        $result = $query->getResultArray();

        if (!empty($result)) {
            foreach ($result as $row1) {
                // Process each row

                if ($act_section == '')
                    $act_section = $row1['act_name'] . '-' . $row1['section'];
                else
                    $act_section = $act_section . ', ' . $row1['act_name'] . '-' . $row1['section'];
            }
        }

        return $act_section;
    }

    public function getProvisionofLaw($fil_no = [])
    {
        // Assuming $fil_no['actcode'] contains the act code
        $actcode = $fil_no['actcode'];

        // Using Query Builder to fetch the law from caselaw table
        $builder = $this->db->table('master.caselaw');
        $builder->select('law');
        $builder->where('id', $actcode);

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRow()->law;
        }

        return '';
    }

    public function get_advocates($adv_id, $wen = '')
    {

        $t_adv = "";
        $adv_id_array = explode(',', $adv_id); // Ensure $adv_id is an array for IN clause

        $builder = $this->db->table('master.bar');
        $builder->select('name, enroll_no, EXTRACT(YEAR FROM enroll_date) AS eyear, isdead');
        $builder->whereIn('bar_id', $adv_id_array);

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            foreach ($query->getResultArray() as $row11a) {
                $t_adv = $row11a['name'];
                if ($row11a['isdead'] == 'Y')
                    $t_adv = "<font color=red>" . $t_adv . " (Dead / Retired / Elevated) </font>";
                if ($wen == 'wen')
                    $t_adv .= " [" . $row11a['enroll_no'] . "/" . $row11a['eyear'] . "]";
            }
        }


        //with Enrollment No.
        // $t_adv = "";
        // if ($adv_id != 0) {
        //     $sql11a = "SELECT name,enroll_no,YEAR(enroll_date) as eyear, isdead  FROM  bar where bar_id IN (" . $adv_id . ")";
        //     $t11a = mysql_query($sql11a);
        //     if (mysql_affected_rows() > 0) {
        //         while ($row11a = mysql_fetch_array($t11a)) {
        //             $t_adv = $row11a['name'];
        //             if ($row11a['isdead'] == 'Y')
        //                 $t_adv = "<font color=red>" . $t_adv . " (Dead / Retired / Elevated) </font>";
        //             if ($wen == 'wen')
        //                 $t_adv .= " [" . $row11a['enroll_no'] . "/" . $row11a['eyear'] . "]";
        //         }
        //     }
        // }

        return $t_adv;
    }

    public function getAmicusCurie($fil_no = [])
    {
        $padvname = $radvname = $ac_text = $for_court = $ac_court = $advType = "";

        $builder = $this->db->table('advocate a');
        $builder->select('pet_res_no, adv, advocate_id, pet_res, is_ac, if_aor, if_sen, if_other');
        $builder->join('master.bar b', 'a.advocate_id = b.bar_id');
        $builder->where('diary_no', $fil_no['diary_no']);
        $builder->where('display', 'Y');
        $builder->orderBy('pet_res');

        $query = $builder->get();

        // $sql_adv_p = "select pet_res_no,adv, advocate_id, pet_res,is_ac,if_aor,if_sen,if_other from advocate a join bar b on a.advocate_id=b.bar_id where diary_no='" . $fil_no['diary_no'] . "' and display='Y' ORDER BY pet_res";
        // $result_advp = mysql_query($sql_adv_p) or die(mysql_error() . " SQL:" . $sql_adv_p);

        if ($query->getNumRows() > 0) {
            foreach ($query->getResultArray() as $row_advp) {

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
                $tmp_advname = $tmp_advname . $this->get_advocates($row_advp['advocate_id'], '') . $row_advp['adv'] . $ac_text;
                //                if ($row_advp[advocate_id] != '')
                //                    $tmp_advname = $tmp_advname . " [" . $row_advp[2] . "/" . $row_advp[3] . "]";
                //                if ($row_advp[0] > 0)
                //                    $tmp_advname = $tmp_advname . " [".$row_advp[4]."-" . $row_advp[0] . "]";
                $tmp_advname = $tmp_advname . "</p>";

                if ($row_advp['pet_res'] == "P")
                    $padvname .= $tmp_advname;
                if ($row_advp['pet_res'] == "R")
                    $radvname .= $tmp_advname;
                if ($row_advp['is_ac'] == 'Y' && ($row_advp['pet_res'] == '' || empty($row_advp['pet_res']) || $row_advp['pet_res'] == null))
                    $ac_court .= $tmp_advname;
            }
        }

        return ['ac_court' => $ac_court, 'padvname' => $padvname, 'radvname' => $radvname];
    }


    public function getConditionalDispose($fil_no = [])
    {
        $t_rgo = ''; // Initialize the variable                

        if ($fil_no['c_status'] == 'P') {

            $builder = $this->db->table('rgo_default');
            $builder->select('fil_no2');
            $builder->where('fil_no', $fil_no['diary_no']);
            $builder->where('remove_def', 'N');
            $builder->orderBy('ent_dt', 'DESC');
            $builder->limit(1);

            $rgo_sql = $builder->get();

            // $rgo_sql = mysql_query("select fil_no2 from rgo_default where fil_no='" . $fil_no['diary_no'] . "' and remove_def='N' order by ent_dt desc limit 1") or
            //     die("Error: " . __LINE__ . mysql_error());
            // $t_rgo = '';

            if ($rgo_sql->getNumRows() > 0) {
                $res_rgo = $rgo_sql->getRowArray();
                if ($t_rgo == '')
                    $t_rgo = "D.No. " . get_real_diaryno($res_rgo['fil_no2']) . "<br>" . str_replace('<br>', ' ', get_casenos_comma($res_rgo['fil_no2']));
                else
                    $t_rgo = "<br> " . "D.No. " . get_real_diaryno($res_rgo['fil_no2']) . "<br>" . str_replace('<br>', ' ', get_casenos_comma($res_rgo['fil_no2']));
            }
        }

        return $t_rgo;
    }


    public function getTentativeDates($fil_no = [])
    {
        $t_ttv_date = '';

        $ucode = session()->get('login')['usercode'];

        $builder = $this->db->table('public.heardt');
        $builder->select('tentative_cl_dt, main_supp_flag');
        $builder->where('diary_no', $fil_no['diary_no']);

        $ttv = $builder->get();
        $r_ttv = $ttv->getRowArray(); // Fetch a single row as an associative array

        if (!empty($r_ttv)) {
            $r_nr = $r_ttv['main_supp_flag'];
            $t_ttv_date = $r_ttv['tentative_cl_dt'];
        } else {
            $r_ttv['tentative_cl_dt'] = '0000-00-00';
        }

        $builder = $this->db->table('master.case_status_flag');
        $builder->select('display_flag, always_allowed_users');
        $builder->where('date(to_date)::text', '0000-00-00');
        $builder->where('flag_name', 'tentative_listing_date');

        $result_sql_display = $builder->get();
        $result_array = $result_sql_display->getRowArray(); // Fetch a single row as an associative array

        // var_dump($result_array);
        if ($result_sql_display->getNumRows() > 0)
            if ($result_array['display_flag'] == 1 || in_array($ucode, explode(',', $result_array['always_allowed_users']))) {
                if ($r_ttv['tentative_cl_dt'] == '0000-00-00') {

                    $builder = $this->db->table('last_heardt');
                    $builder->select('tentative_cl_dt, main_supp_flag');
                    $builder->where('diary_no', $fil_no['diary_no']);
                    $builder->where('tentative_cl_dt !=', '0000-00-00');
                    $builder->orderBy('ent_dt', 'DESC');
                    $builder->limit(1);

                    $query = $builder->get();
                    $r_ttvq = $query->getRowArray(); // Use getRowArray() to fetch a single row

                    // $ttvq = "SELECT tentative_cl_dt,main_supp_flag FROM last_heardt WHERE diary_no='" . $fil_no['diary_no'] . "' and tentative_cl_dt!='0000-00-00' order by ent_dt DESC LIMIT 1 ";
                    // $ttvq = mysql_query($ttvq) or die("Error: " . __LINE__ . mysql_error());
                    // $r_ttvq = mysql_fetch_array($ttvq);

                    $t_ttv_date = $r_ttvq['tentative_cl_dt'];
                }

                $tentative_date = $t_ttv_date;

                if ($result_array['display_flag'] == 1 || in_array($ucode, explode(',', $result_array['always_allowed_users'])))
                    $t_ttv_date = change_date_format($tentative_date);
                elseif (get_display_status_with_date_differnces($tentative_date) == 'T') {
                    $t_ttv_date = change_date_format($tentative_date);
                }
            }

        return $t_ttv_date;
    }

    function get_case_remarks($dn, $cldate, $jcodes, $clno)
    {
        // Assuming $dn, $cldate, $jcodes, and $clno contain the appropriate values
        $dn = $this->db->escape($dn); // Escape the diary number
        $cldate = $this->db->escape($cldate); // Escape the date
        $jcodes = $this->db->escape($jcodes); // Escape the jcodes
        $clno = $this->db->escape($clno); // Escape the clno

        // Using Query Builder to fetch the data
        $builder = $this->db->table('case_remarks_multiple c');
        $builder->select("h.cat_head_id, c.cl_date, c.jcodes, c.status, 
            STRING_AGG(CONCAT(h.head, CASE WHEN c.head_content !='' THEN CONCAT('[', c.head_content, ']') ELSE '' END), ', ') AS crem, 
            STRING_AGG(CONCAT(c.r_head, '|', c.head_content, '^^'), '') AS caseval, 
            c.mainhead, c.clno");
        $builder->join('master.case_remarks_head h', 'c.r_head = h.sno');
        $builder->where("(c.diary_no)::text", $dn);
        $builder->where('c.cl_date', $cldate);
        $builder->where('c.jcodes', $jcodes);
        $builder->where('(c.clno)::text', $clno);
        $builder->groupBy('c.cl_date, h.cat_head_id, c.jcodes, c.status, c.mainhead, c.clno, h.priority');
        $builder->orderBy('h.priority');

        $result_cr = $builder->get();

        $cval = "";
        if ($result_cr->getNumRows() > 0) {
            $row_cr = $result_cr->getRowArray(); // Fetch the first row as an associative array
            $crem = $row_cr['crem'];
        } else {
            $crem = '';
        }

        return $crem;
    }

    function check_drop($diaryno, $cldate, $rosterid, $clno)
    {
        // Assuming $diaryno, $clno, $cldate, and $rosterid contain the appropriate values
        $diaryno = $this->db->escape($diaryno);
        $clno = $this->db->escape($clno);
        $cldate = $this->db->escape($cldate);
        $rosterid = $this->db->escape($rosterid);

        $drop_note = "";

        // Using Query Builder to fetch the data
        $builder = $this->db->table('public.drop_note d');
        $builder->select('d.*, r.courtno');
        $builder->join('master.roster r', 'd.roster_id = r.id');
        $builder->where('(d.diary_no)::text', $diaryno);
        $builder->where('(d.clno)::text', $clno);
        $builder->where('d.display', 'Y');
        $builder->where('d.cl_date', $cldate);
        $builder->where('(d.roster_id)::text', $rosterid);
        $builder->orderBy('d.ent_dt', 'ASC');

        $result_drop = $builder->get();

        if ($result_drop->getNumRows() > 0) {
            $drop_note = "<br><font color='red' style='font-size:11px;font-weight:bold;'>Drop Case</font>";
            foreach ($result_drop->getResultArray() as $row_drop) {
                $drop_note .= "<br>[<font color='red' style='font-size:11px;font-weight:bold;'>Court No. " . htmlspecialchars($row_drop["courtno"]) . " - CL.NO. : " . htmlspecialchars($row_drop["clno"]) . " - " . htmlspecialchars($row_drop["nrs"]) . "</font>]";
                $t_drp_jname = stripslashes($row_drop["jnm"]); // Be cautious with stripslashes; consider sanitizing input instead
            }
        }

        return $drop_note;


        // $drop_note = "";
        // $sql_drop = "SELECT d.*, r.courtno FROM drop_note d inner join roster r ON d.roster_id=r.id WHERE d.diary_no='" . $diaryno . "' AND clno='" . $clno . "' AND d.display='Y' AND d.cl_date='" . $cldate . "' AND d.roster_id='" . $rosterid . "' ORDER BY d.ent_dt ASC";
        // $result_drop = mysql_query($sql_drop) or die(mysql_error() . " SQL:" . $sql_drop);
        // if (mysql_affected_rows() > 0)
        //     $drop_note = " <br><font color='red' style='font-size:11px;font-weight:bold;'>Drop Case</font>";
        // while ($row_drop = mysql_fetch_array($result_drop)) {
        //     $drop_note .= " <br>[<font color='red' style='font-size:11px;font-weight:bold;'>Court No. " . $row_drop["courtno"] . " - CL.NO. : " . $row_drop["clno"] . " - " . $row_drop["nrs"] . "</font>]";
        //     $t_drp_jname = stripslashes($row_drop["jnm"]);
        // }
        // return $drop_note;
    }



    function check_list_printed($roster_id, $mf, $part, $main_supp, $next_dt)
    {

        // Using Query Builder to check if the list has been printed
        $builder = $this->db->table('cl_printed');
        $builder->where('roster_id', $roster_id);
        $builder->where('m_f', $mf);
        $builder->where('part', $part);
        $builder->where('main_supp', $main_supp);
        $builder->where('next_dt', $next_dt);
        $builder->where('display', 'Y');

        $query = $builder->get();

        // Check if any rows were returned
        if ($query->getNumRows() > 0) {
            $list_printed = "YES";
        } else {
            $list_printed = "NO";
        }

        return $list_printed;
    }

    function get_purpose($purpose_code)
    {
        $purpose = "";

        if ($purpose_code != "") {
            // Using Query Builder to fetch the purpose
            $builder = $this->db->table('master.listing_purpose');
            $builder->select('purpose');
            $builder->where('code', $purpose_code);

            $query = $builder->get();

            if ($query->getNumRows() > 0) {
                $row_p = $query->getRowArray(); // Fetch result as associative array
                $purpose = $row_p['purpose'];
            }
        }

        return $purpose;
    }

    function get_case_status_flag($flag_name = '')
    {
        // Using Query Builder to fetch the data
        $builder = $this->db->table('master.case_status_flag');
        $builder->select('display_flag, always_allowed_users');
        $builder->where("COALESCE(to_date::TEXT, '0000-00-00')", '0000-00-00'); // PostgreSQL uses '=' for equality
        $builder->where('flag_name', $flag_name);

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            $result_array = $query->getRowArray(); // Fetch result as associative array
            return $result_array;
        } else {
            return null; // Return null or handle no results as needed
        }
    }

    function get_judges($jcodes)
    {
        $jnames = "";

        if ($jcodes != '') {
            $t_jc = explode(",", $jcodes);
            $judges = [];

            foreach ($t_jc as $jcode) {
                // Using Query Builder to fetch judge names
                $builder = $this->db->table('master.judge');
                $builder->select('jname');
                $builder->where('jcode', $jcode);

                $query = $builder->get();

                if ($query->getNumRows() > 0) {
                    foreach ($query->getResultArray() as $row) {
                        $judges[] = $row["jname"];
                    }
                }
            }

            // Format the names
            if (count($judges) > 0) {
                $jnames = implode(", ", array_slice($judges, 0, -1)); // Join all but the last
                if (count($judges) > 1) {
                    $jnames .= " and " . end($judges); // Add 'and' before the last judge's name
                } else {
                    $jnames = $judges[0]; // If there's only one name
                }
            }
        }

        return $jnames;
    }

    function get_stage($stage_code, $mainhead)
    {
        $stage = "";

        if ($stage_code != "") {
            if ($mainhead == "M") {
                // Using Query Builder to fetch stage name from subheading
                $builder = $this->db->table('master.subheading');
                $builder->select('stagename');
                $builder->where('stagecode', $stage_code);

                $query = $builder->get();

                if ($query->getNumRows() > 0) {
                    $row_p = $query->getRowArray(); // Fetch result as associative array
                    $stage = $row_p['stagename'];
                }
            }

            if ($mainhead == "F") {
                // Using Query Builder to fetch data from submaster
                $builder = $this->db->table('master.submaster');
                $builder->where('id', $stage_code);

                $query = $builder->get();

                if ($query->getNumRows() > 0) {
                    $row_p = $query->getRowArray(); // Fetch result as associative array

                    // Construct stage name based on subcode conditions
                    if ($row_p['subcode1'] > 0 && $row_p['subcode2'] == 0 && $row_p['subcode3'] == 0 && $row_p['subcode4'] == 0) {
                        $stage = $row_p['sub_name1'];
                    } elseif ($row_p['subcode1'] > 0 && $row_p['subcode2'] > 0 && $row_p['subcode3'] == 0 && $row_p['subcode4'] == 0) {
                        $stage = $row_p['sub_name1'] . " : " . $row_p['sub_name4'];
                    } elseif ($row_p['subcode1'] > 0 && $row_p['subcode2'] > 0 && $row_p['subcode3'] > 0 && $row_p['subcode4'] == 0) {
                        $stage = $row_p['sub_name1'] . " : " . $row_p['sub_name2'] . " : " . $row_p['sub_name4'];
                    } elseif ($row_p['subcode1'] > 0 && $row_p['subcode2'] > 0 && $row_p['subcode3'] > 0 && $row_p['subcode4'] > 0) {
                        $stage = $row_p['sub_name1'] . " : " . $row_p['sub_name2'] . " : " . $row_p['sub_name3'] . " : " . $row_p['sub_name4'];
                    }
                }
            }
        }

        return $stage;
    }

    public function getProposalList($fil_no = [])
    {
        //Listing Start
        //$sql_listing="Select * from heardt where diary_no='".$fil_no['diary_no']."' ";
        $ucode = session()->get('login')['usercode'];
        $user_case_updation = $this->get_case_status_flag('case_updation');
        // echo $this->db->getLastQuery(); die;
        // pr($user_case_updation);

        // Using Query Builder to fetch the data
        $builder = $this->db->table('public.heardt a');
        $builder->select('a.*, c.section_name, b.name');
        $builder->join('master.users b', 'a.usercode = b.usercode', 'left');
        $builder->join('master.usersection c', 'b.section = c.id', 'left');
        $builder->where('a.diary_no', $fil_no['diary_no']);

        $result_listing = $builder->get();

        // added on 28.01.2020
        $pendingIAs = '';
        //SQL to get list of pending IAs
        $row_ia = $this->db->table('docdetails')
            ->select("STRING_AGG(doccode1::text, ',') AS ia")
            ->where('diary_no', $fil_no['diary_no'])
            ->where('doccode', 8)
            ->where('display', 'Y')
            ->where('iastat', 'P')
            ->get()
            ->getRowArray();

        $pendingIAs = (!empty($row_ia["ia"])) ? $row_ia["ia"] : "";

        // Query to fetch the latest remarks
        $row_remarks = $this->db->table('case_remarks_multiple')
            ->select('r_head, cl_date')
            ->where('diary_no', $fil_no['diary_no'])
            ->orderBy('cl_date', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray(); // Fetch the first row as an associative array

        $remarks = (!empty($row_remarks["r_head"])) ? $row_remarks["r_head"] : "";
        $last_cl_date = (!empty($row_remarks["cl_date"])) ? $row_remarks["cl_date"] : "";

        // Query to fetch sensitive cases
        $row_sensitive = $this->db->table('sensitive_cases')
            ->where('display', 'Y')
            ->where('diary_no', $fil_no['diary_no'])
            ->get()
            ->getRowArray(); // Fetch the first row as an associative array

        // Query to fetch advocate information
        $row_PIP = $this->db->table('advocate')
            ->where('display', 'Y')
            ->where('diary_no', $fil_no['diary_no'])
            ->groupStart() // Start grouping conditions
            ->where('adv_type', 'M')
            ->where('pet_res', 'P')
            ->where('pet_res_no', 1)
            ->where('advocate_id', 584)
            ->orWhere('advocate_id', 616)
            ->groupEnd() // End grouping conditions
            ->get()
            ->getRowArray(); // Fetch the first row as an associative array

        // Using Query Builder to fetch the data
        $builder = $this->db->table('public.last_heardt a');
        $builder->select('a.*, c.section_name, b.name');
        $builder->join('master.users b', 'a.usercode = b.usercode', 'left');
        $builder->join('master.usersection c', 'b.section = c.id', 'left');
        $builder->where('a.diary_no', $fil_no['diary_no']);
        $builder->where("date(a.next_dt)::text != '0000-00-00'");
        $builder->where('a.bench_flag', ''); // For empty string check
        $builder->orderBy('ent_dt', 'DESC');

        $result_listing1 = $builder->get();

        // $this->db->getLastQuery();

        // $sql_listing1 = "Select a.*,section_name,b.name from last_heardt a LEFT JOIN users b ON a.usercode=b.usercode LEFT JOIN usersection c ON b.section=c.id where diary_no='" . $fil_no['diary_no'] . "' and next_dt!='0000-00-00' and a.bench_flag='' order by ent_dt DESC";

        // $result_listing1 = mysql_query($sql_listing1) or die("Errror: " . __LINE__ . mysql_error());


        $listed_ia = "";
        $only_can_update_469 = "";
        $check_for_case_is_listed_after_current_date = "";
        $check_for_case_is_listed_after_current_date_remark = "";
        $t_table = "";
        $subhead = "";
        $is_nmd = "";
        $next_dt = "";
        $mainhead_kk = "";
        $lo = "";
        $sj = "";
        $bt = "";
        
        $result_array = $this->get_case_status_flag('tentative_listing_date');

        if ($result_listing->getNumRows() > 0 or $result_listing1->getNumRows() > 0) {
            $t_table = '<table class="table_tr_th_w_clr c_vertical_align" width="100%">';
            $t_table .= "<tr><td align='center'><b>CL Date</b></td><td><b>Misc./Regular</b></td><td><b>Stage</b></td><td><b>Purpose</b></td><td align='center'><b>Proposed/ List in</b></td><td><b>Judges</b></td><td><b>IA</b></td><td><b>Remarks</b></td><td><b>Updated By</b></td><td><b>Listed</b></td></tr>";

            foreach ($result_listing->getResultArray() as $row_listing) {

                $mainhead_kk = $row_listing['mainhead'];
                // $listing_date = $row_listing['next_dt'];
                // echo "ucode".$ucode;

                if (($row_listing['main_supp_flag'] == 3 or $row_listing['main_supp_flag'] == 6)  and (($row_sensitive == '' or $row_sensitive == null) and ($row_PIP == '' or $row_PIP == null)) and !($user_case_updation['display_flag'] == '1' || in_array($ucode, explode(',', $user_case_updation['always_allowed_users'])))) {
                    //if($row_listing['main_supp_flag']==3 AND ($row_listing['usercode'] != 1 and $row_listing['usercode'] != 559 and $row_listing['usercode'] != 1485)){
                    $only_can_update_469 = "Case Not to List, Contact to DEU-II Section";
                } else if (($row_listing['main_supp_flag'] == 3 or $row_listing['main_supp_flag'] == 6) and (($row_sensitive != null and $row_sensitive != '') or ($row_PIP != null and $row_PIP != '')) and $ucode != 1504 and $ucode != 94 and  !($user_case_updation['display_flag'] == '1' || in_array($ucode, explode(',', $user_case_updation['always_allowed_users'])))) {
                    //if($row_listing['main_supp_flag']==3 AND ($row_listing['usercode'] != 1 and $row_listing['usercode'] != 559 and $row_listing['usercode'] != 1485)){
                    $only_can_update_469 = "Case Not to List, Contact Addl. Reg.(I-B)";
                } else if ($remarks == 7) {
                    $only_can_update_469 = "Matter is Heard and Reserved.Contact Listing Section.";
                } else {
                    $only_can_update_469 = "";
                }

                if ($row_listing['judges'] != '' and $row_listing['judges'] != '0' and $row_listing['clno'] > 0 and $row_listing['brd_slno'] > 0 and $row_listing['roster_id'] > 0) {
                    date_default_timezone_set('GMT');
                    $temp = strtotime("+5 hours 30 minutes");
                    //$date = date("H:i:s",$temp);

                    //echo strtotime("18:10:00") - strtotime(date("H:i:s",$temp));
                    //  echo gmdate('Y-m-d H:i:s')."ddd".(strtotime("18:00:00") - strtotime(date('H:i:s')));
                    if ((strtotime($row_listing['next_dt']) > strtotime(date('Y-m-d'))) or (strtotime($row_listing['next_dt']) == strtotime(date('Y-m-d')) and (strtotime("17:00:00") - strtotime(date("H:i:s", $temp))) > 0)) {

                        $check_for_case_is_listed_after_current_date = "LISTED";
                        if (strtotime($row_listing['next_dt']) == strtotime(date('Y-m-d')))
                            $check_for_case_is_listed_after_current_date_remark = "Proposal is LOCKED as Case is Listed on " . date('d-m-Y', strtotime($row_listing['next_dt'])) . "<br>Case is available for updation after 5:00 PM";
                        else
                            $check_for_case_is_listed_after_current_date_remark = "Proposal is LOCKED as Case is Listed on " . date('d-m-Y', strtotime($row_listing['next_dt']));
                    }
                }

                $listed_ia = $row_listing['listed_ia'];
                if ($row_listing['mainhead'] == "M")
                    $t_mainhead = "Misc.";
                if ($row_listing['mainhead'] == "F")
                    $t_mainhead = "Regular";
                if ($row_listing['mainhead'] == "L")
                    $t_mainhead = "Lok Adalat";
                if ($row_listing['mainhead'] == "S")
                    $t_mainhead = "Mediation";
                $t_stage = "";
                $subhead = $row_listing['subhead'];

                if ($row_listing['clno'] != 0 and $row_listing['brd_slno'] != 0 and ($row_listing['main_supp_flag'] == "1" or $row_listing['main_supp_flag'] == "2") and $row_listing['judges'] != '' and $row_listing['judges'] != '0') {
                    $listed = "<font color=blue>LISTED</font>";
                } else if ($row_listing['main_supp_flag'] == "3") {
                    $listed = "<font color=red>NOT READY</font>";
                } else if ($row_listing['main_supp_flag'] == "0") {
                    $listed = "<font color=green>READY</font>";
                } else
                    $listed = "";
                if ($row_listing['mainhead'] == "M") {
                    $t_stage = $this->get_stage($row_listing['subhead'], 'M');
                }
                if ($row_listing['mainhead'] == "F") {
                    $t_stage = $this->get_stage($row_listing['subhead'], 'F');
                }

                //$next_dt=$row_listing['next_dt'];
                $next_dt = $row_listing['tentative_cl_dt'];
                $is_nmd = $row_listing['is_nmd'];
                $lo = $row_listing['listorder'];
                $sj = $row_listing['sitting_judges'];
                $bt = $row_listing['board_type'];
                if ($bt == 'J')
                    $bt = 'Judge';
                else if ($bt == 'C')
                    $bt = 'Chamber';
                else if ($bt == 'R')
                    $bt = 'Registrar';
                else if ($bt == 'S')
                    $bt = 'Single Judge';
                else
                    $bt = '';
                if ($row_listing['judges'] != '' and $row_listing['judges'] != '0') {
                    $cr = $this->get_case_remarks($row_listing['diary_no'], $row_listing['next_dt'], $row_listing['judges'], $row_listing['brd_slno']);
                } else {
                    $cr = "";
                }
                $check_drop = "";
                if ($row_listing['clno'] != 0 and $row_listing['roster_id'] != 0 and ($row_listing['judges'] != '' and $row_listing['judges'] != 0) and ($row_listing['main_supp_flag'] == 1 or $row_listing['main_supp_flag'] == 2)) {
                    $check_drop = $this->check_drop($row_listing['diary_no'], $row_listing['next_dt'], $row_listing['roster_id'], $row_listing['brd_slno']);
                }
                $chk_list_printed = $this->check_list_printed($row_listing['roster_id'], $row_listing['mainhead'], $row_listing['clno'], $row_listing['main_supp_flag'], $row_listing['next_dt']);
                // if($chk_list_printed == "NO" AND $ucode != 1 AND $ucode != 469 AND $ucode != 559 AND $ucode!=1485){
                if ($chk_list_printed == "NO" and !($user_case_updation['display_flag'] == '1' || in_array($ucode, explode(',', $user_case_updation['always_allowed_users'])))) {
                    $get_judge_name_print = "";
                } else {
                    $get_judge_name_print = $this->get_judges($row_listing['judges']);
                } //recently added

                $result_array = $this->get_case_status_flag('case_updated_for_but_not_listed_date');

                // $sql_display = "select display_flag, always_allowed_users from case_status_flag where date(to_date)='0000-00-00' and flag_name='case_updated_for_but_not_listed_date'";
                // $result_sql_display = mysql_query($sql_display) or die(mysql_error() . " SQL:" . $sql_display);
                // $result_array = mysql_fetch_assoc($result_sql_display);

                //var_dump($result_array);
                if (($result_array['display_flag'] == '1' || in_array($ucode, explode(',', $result_array['always_allowed_users'])))) { //end
                    $t_table .= "<tr><td align='center'>" . change_date_format($row_listing['next_dt']) . "</td><td>" . $t_mainhead . "</td><td>" . $t_stage . "</td><td>" . $this->get_purpose($row_listing['listorder']) . "</td><td align='center'>" . $bt . "</td><td>" . $get_judge_name_print . "</td><td align='center'>" . $row_listing['listed_ia'] . "</td><td>" . $cr . $check_drop . "</td>";
                    $t_table .= "<td><font style='font-size:10px;'>";
                    if ($row_listing['ent_dt'] == '0000-00-00 00:00:00' || $row_listing['ent_dt'] == '' || $row_listing['ent_dt'] == NULL) {
                        if (($row_listing['name'] == '' || $row_listing['name'] == NULL))
                            $t_table .= "";
                        else
                            $t_table .= $row_listing['name'];
                        if (($row_listing['section_name'] == '' || $row_listing['section_name'] == NULL))
                            $t_table .= "";
                        else
                            $t_table .= " [" . $row_listing['section_name'] . "]";
                    } else
                        $t_table .= $row_listing['name'] . " [" . $row_listing['section_name'] . "] ON " . date('d-m-Y h:i:s A', strtotime($row_listing['ent_dt']));
                    $t_table .= "</font></td>";
                    $t_table .= "<td>" . $listed . "</td>";
                    $t_table .= "</tr>";
                } else {
                    if (($row_listing['clno'] != 0 and $row_listing['brd_slno'] != 0 and ($row_listing['main_supp_flag'] == "1" or $row_listing['main_supp_flag'] == "2") and $row_listing['judges'] != '' and $row_listing['judges'] != '0')) {
                        $t_table .= "<tr><td align='center'>" . change_date_format($row_listing['next_dt']) . "</td><td>" . $t_mainhead . "</td><td>" . $t_stage . "</td><td>" . $this->get_purpose($row_listing['listorder']) . "</td><td align='center'>" . $bt . "</td><td>" . $get_judge_name_print . "</td><td align='center'>" . $row_listing['listed_ia'] . "</td><td>" . $cr . $check_drop . "</td>";
                        $t_table .= "<td><font style='font-size:10px;'>";
                        if ($row_listing['ent_dt'] == '0000-00-00 00:00:00' || $row_listing['ent_dt'] == '' || $row_listing['ent_dt'] == NULL) {
                            if (($row_listing['name'] == '' || $row_listing['name'] == NULL))
                                $t_table .= "";
                            else
                                $t_table .= $row_listing['name'];
                            if (($row_listing['section_name'] == '' || $row_listing['section_name'] == NULL))
                                $t_table .= "";
                            else
                                $t_table .= " [" . $row_listing['section_name'] . "]";
                        } else
                            $t_table .= $row_listing['name'] . " [" . $row_listing['section_name'] . "] ON " . date('d-m-Y h:i:s A', strtotime($row_listing['ent_dt']));
                        $t_table .= "</font></td>";
                        $t_table .= "<td>" . $listed . "</td>";
                        $t_table .= "</tr>";
                    } else {
                        $t_table .= "<tr><td align='center'>&nbsp;</td><td>" . $t_mainhead . "</td><td>" . $t_stage . "</td><td>" . $this->get_purpose($row_listing['listorder']) . "</td><td align='center'>" . $bt . "</td><td>" . $get_judge_name_print . "</td><td align='center'>" . $row_listing['listed_ia'] . "</td><td>" . $cr . $check_drop . "</td>";
                        $t_table .= "<td><font style='font-size:10px;'>";
                        if ($row_listing['ent_dt'] == '0000-00-00 00:00:00' || $row_listing['ent_dt'] == '' || $row_listing['ent_dt'] == NULL) {
                            if (($row_listing['name'] == '' || $row_listing['name'] == NULL))
                                $t_table .= "";
                            else
                                $t_table .= $row_listing['name'];
                            if (($row_listing['section_name'] == '' || $row_listing['section_name'] == NULL))
                                $t_table .= "";
                            else
                                $t_table .= " [" . $row_listing['section_name'] . "]";
                        } else
                            $t_table .= $row_listing['name'] . " [" . $row_listing['section_name'] . "] ON " . date('d-m-Y h:i:s A', strtotime($row_listing['ent_dt']));
                        $t_table .= "</font></td>";
                        $t_table .= "<td>" . $listed . "</td>";
                        $t_table .= "</tr>";
                    }
                }/*recently added */
            } //end

            foreach ($result_listing1->getResultArray() as $row_listing1) {
                if ($row_listing1['mainhead'] == "M")
                    $t_mainhead1 = "Misc.";
                if ($row_listing1['mainhead'] == "F")
                    $t_mainhead1 = "Regular";
                if ($row_listing1['mainhead'] == "L")
                    if ($row_listing1['mainhead'] == "L")
                        $t_mainhead1 = "Lok Adalat";
                if ($row_listing1['mainhead'] == "S")
                    $t_mainhead1 = "Mediation";
                $t_stage1 = "";
                if ($row_listing1['mainhead'] == "M") {
                    $t_stage1 = $this->get_stage($row_listing1['subhead'], 'M');
                }
                if ($row_listing1['mainhead'] == "F") {
                    $t_stage1 = $this->get_stage($row_listing1['subhead'], 'F');
                }
                $bt1 = $row_listing1['board_type'];
                if ($row_listing1['clno'] != 0 and $row_listing1['brd_slno'] != 0 and ($row_listing1['main_supp_flag'] == "1" or $row_listing1['main_supp_flag'] == "2") and $row_listing1['judges'] != '' and $row_listing1['judges'] != '0') {
                    $listed1 = "<font color=blue>LISTED</font>";
                } else if ($row_listing1['main_supp_flag'] == "3") {
                    $listed1 = "<font color=red>NOT READY</font>";
                } else if ($row_listing1['main_supp_flag'] == "0") {
                    $listed1 = "<font color=green>READY</font>";
                } else
                    $listed1 = "";
                if ($bt1 == 'J')
                    $bt1 = 'Judge';
                else if ($bt1 == 'C')
                    $bt1 = 'Chamber';
                else if ($bt1 == 'R')
                    $bt1 = 'Registrar';
                else if ($bt1 == 'S')
                    $bt1 = 'Single Judge';
                else
                    $bt1 = '';


                if ($row_listing1['judges'] != '' and $row_listing1['judges'] != '0') {
                    $cr = $this->get_case_remarks($row_listing1['diary_no'], $row_listing1['next_dt'], $row_listing1['judges'], $row_listing1['brd_slno']);
                } else {
                    $cr = "";
                }
                $check_drop = "";
                if ($row_listing1['clno'] != 0 and $row_listing1['roster_id'] != 0 and ($row_listing1['judges'] != '' and $row_listing1['judges'] != 0) and ($row_listing1['main_supp_flag'] == 1 or $row_listing1['main_supp_flag'] == 2)) {
                    $check_drop = $this->check_drop($row_listing1['diary_no'], $row_listing1['next_dt'], $row_listing1['roster_id'], $row_listing1['brd_slno']);
                }

                $chk_list_printed = $this->check_list_printed($row_listing1['roster_id'], $row_listing1['mainhead'], $row_listing1['clno'], $row_listing1['main_supp_flag'], $row_listing1['next_dt']);
                // if($chk_list_printed == "NO" AND $ucode != 1 AND $ucode != 469 AND $ucode != 559 AND $ucode!=1485){
                if ($chk_list_printed == "NO" and !($user_case_updation['display_flag'] == '1' || in_array($ucode, explode(',', $user_case_updation['always_allowed_users'])))) {
                    $get_judge_name_print = "";
                } else {
                    $get_judge_name_print = $this->get_judges($row_listing1['judges']);
                }

                //recently added
                // $sql_display = "select display_flag, always_allowed_users from case_status_flag where date(to_date)='0000-00-00' and flag_name='case_updated_for_but_not_listed_date'";
                // $result_sql_display = mysql_query($sql_display) or die(mysql_error() . " SQL:" . $sql_display);
                // $result_array = mysql_fetch_assoc($result_sql_display);

                $result_array = $this->get_case_status_flag('case_updated_for_but_not_listed_date');

                //var_dump($result_array);
                if (($result_array['display_flag'] == '1' || in_array($ucode, explode(',', $result_array['always_allowed_users'])))) { //end
                    $t_table .= "<tr><td align='center'>" . change_date_format($row_listing1['next_dt']) . "</td><td>" . $t_mainhead1 . "</td><td>" . $t_stage1 . "</td><td>" . $this->get_purpose($row_listing1['listorder']) . "</td><td align='center'>" . $bt1 . "</td><td>" . $get_judge_name_print . "</td><td align='center'>" . $row_listing1['listed_ia'] . "</td><td>" . $cr . $check_drop . "</td>";
                    $t_table .= "<td><font style='font-size:10px;'>";
                    if ($row_listing1['ent_dt'] == '0000-00-00 00:00:00' || $row_listing1['ent_dt'] == '' || $row_listing1['ent_dt'] == NULL) {
                        if (($row_listing1['name'] == '' || $row_listing1['name'] == NULL))
                            $t_table .= "";
                        else
                            $t_table .= $row_listing1['name'];
                        if (($row_listing1['section_name'] == '' || $row_listing1['section_name'] == NULL))
                            $t_table .= "";
                        else
                            $t_table .= " [" . $row_listing1['section_name'] . "]";
                    } else
                        $t_table .= $row_listing1['name'] . " [" . $row_listing1['section_name'] . "] ON " . date('d-m-Y h:i:s A', strtotime($row_listing1['ent_dt']));
                    $t_table .= "</font></td>";
                    $t_table .= "<td>" . $listed1 . "</td>";
                    $t_table .= "</tr>";
                } else {
                    if (($row_listing1['clno'] != 0 and $row_listing1['brd_slno'] != 0 and ($row_listing1['main_supp_flag'] == "1" or $row_listing1['main_supp_flag'] == "2") and $row_listing1['judges'] != '' and $row_listing1['judges'] != '0')) {
                        $t_table .= "<tr><td align='center'>" . change_date_format($row_listing1['next_dt']) . "</td><td>" . $t_mainhead1 . "</td><td>" . $t_stage1 . "</td><td>" . $this->get_purpose($row_listing1['listorder']) . "</td><td align='center'>" . $bt1 . "</td><td>" . $get_judge_name_print . "</td><td align='center'>" . $row_listing1['listed_ia'] . "</td><td>" . $cr . $check_drop . "</td>";
                        $t_table .= "<td><font style='font-size:10px;'>";
                        if ($row_listing1['ent_dt'] == '0000-00-00 00:00:00' || $row_listing1['ent_dt'] == '' || $row_listing1['ent_dt'] == NULL) {
                            if (($row_listing1['name'] == '' || $row_listing1['name'] == NULL))
                                $t_table .= "";
                            else
                                $t_table .= $row_listing1['name'];
                            if (($row_listing1['section_name'] == '' || $row_listing1['section_name'] == NULL))
                                $t_table .= "";
                            else
                                $t_table .= " [" . $row_listing1['section_name'] . "]";
                        } else
                            $t_table .= $row_listing1['name'] . " [" . $row_listing1['section_name'] . "] ON " . date('d-m-Y h:i:s A', strtotime($row_listing1['ent_dt']));
                        $t_table .= "</font></td>";
                        $t_table .= "<td>" . $listed1 . "</td>";
                        $t_table .= "</tr>";
                    } else {
                        $t_table .= "<tr><td align='center'>&nbsp;</td><td>" . $t_mainhead1 . "</td><td>" . $t_stage1 . "</td><td>" . $this->get_purpose($row_listing1['listorder']) . "</td><td align='center'>" . $bt1 . "</td><td>" . $get_judge_name_print . "</td><td align='center'>" . $row_listing1['listed_ia'] . "</td><td>" . $cr . $check_drop . "</td>";
                        $t_table .= "<td><font style='font-size:10px;'>";
                        if ($row_listing1['ent_dt'] == '0000-00-00 00:00:00' || $row_listing1['ent_dt'] == '' || $row_listing1['ent_dt'] == NULL) {
                            if (($row_listing1['name'] == '' || $row_listing1['name'] == NULL))
                                $t_table .= "";
                            else
                                $t_table .= $row_listing1['name'];
                            if (($row_listing1['section_name'] == '' || $row_listing1['section_name'] == NULL))
                                $t_table .= "";
                            else
                                $t_table .= " [" . $row_listing1['section_name'] . "]";
                        } else
                            $t_table .= $row_listing1['name'] . " [" . $row_listing1['section_name'] . "] ON " . date('d-m-Y h:i:s A', strtotime($row_listing1['ent_dt']));
                        $t_table .= "</font></td>";
                        $t_table .= "<td>" . $listed1 . "</td>";
                        $t_table .= "</tr>";
                    }
                }
            }
            $t_table .= "</table>";
        }

        // Getting data for proposal form Start
        $builder = $this->db->table('public.heardt');
        $builder->select('tentative_cl_dt, main_supp_flag');
        $builder->where('diary_no', $fil_no['diary_no']);

        $ttv = $builder->get();
        $r_ttv = $ttv->getRowArray(); // Fetch a single row as an associative array

        $r_nr = '';
        if (!empty($r_ttv)) {
            $r_nr = $r_ttv['main_supp_flag'];
        }

        $user_case_updation = $this->get_case_status_flag('case_updation');
        // Getting data for proposal form End

        return [
            'remarks' => $remarks,
            'last_cl_date' => $last_cl_date,
            'pendingIAs' => $pendingIAs,
            'row_sensitive' => $row_sensitive,
            'subhead' => $subhead,
            'result_array' => $result_array,
            'mainhead_kk' => $mainhead_kk,
            'user_case_updation' => $user_case_updation,
            'proposal_form' => ['r_nr' => $r_nr, 'next_dt' => $next_dt, 'is_nmd' => $is_nmd, 'lo' => $lo, 'sj' => $sj, 'bt' => $bt],
            'row_PIP' => $row_PIP,
            'only_can_update_469' => $only_can_update_469,
            'check_for_case_is_listed_after_current_date' => $check_for_case_is_listed_after_current_date,
            'check_for_case_is_listed_after_current_date_remark' => $check_for_case_is_listed_after_current_date_remark,
            'listed_ia' => $listed_ia,
            'html' => $t_table
        ];
        //Listing End

    }

    public function getFutureDates() {

        $future_dates = "";

        $query = $this->db->table('cl_printed')
            ->select("string_agg(DISTINCT next_dt::text, ',') AS dates")
            ->where('display', 'Y')
            ->where('next_dt >', date('Y-m-d'))
            ->get();

        if ($query->getNumRows() > 0) {
            $future_dates = $query->getRow()->dates;
        }

        return $future_dates;
    }

    public function getMainHead($diary_no=0) {
        $mainhead = "";

        // Query to fetch the mainhead based on the diary_no
        $query = $this->db->table('heardt')
        ->select('mainhead')
        ->where('diary_no', $diary_no)
        ->get();

        // Check if any row was returned
        if ($query->getNumRows() > 0) {
            // Fetch the row and get the mainhead value
            $row_h = $query->getRow();
            $mainhead = $row_h->mainhead;  // Accessing the mainhead field
        }

        return $mainhead;
    }

    public function getNextTuesday($next_date="") {
        
        $nexttuesday = "";

        // Check if $q_next_dt is greater than today's date
        if ($next_date > date("Y-m-d")) {
            // Build the query to fetch the next Wednesday
            $result_nm = $this->db->table('master.sc_working_days')
                ->select("TO_CHAR(working_date, 'DD-MM-YYYY') AS newdate")
                ->where('display', 'Y')
                ->where('is_holiday', 0)
                ->where('EXTRACT(DOW FROM working_date)', 3)  // Find Wednesday (DOW = 3)
                ->where('working_date >=', $next_date)
                ->orderBy('working_date', 'asc')
                ->limit(1)
                ->get();
        } else {
            // Build the query for 28 days after the current date for the next Wednesday
            $result_nm = $this->db->table('master.sc_working_days')
                ->select("TO_CHAR(working_date, 'DD-MM-YYYY') AS newdate")
                ->where('display', 'Y')
                ->where('is_holiday', 0)
                ->where('EXTRACT(DOW FROM working_date)', 3)  // Find Wednesday (DOW = 3)
                ->where('working_date > (CURRENT_DATE + 28)')
                ->orderBy('working_date', 'asc')
                ->limit(1)
                ->get();
        }

        // Check if any rows were returned
        if ($result_nm->getNumRows() > 0) {
            // Get the next Wednesday date
            $nexttuesday = $result_nm->getRow()->newdate;
        }

        return $nexttuesday;
    }

    public function getNextMonday($next_date="") {
        
        $nextmonday = "";

        // Check if $q_next_dt is greater than today's date
        if ($next_date > date("Y-m-d")) {
            // Build the query to fetch the next working date
            $result_nm = $this->db->table('master.sc_working_days')
                ->select("TO_CHAR(working_date, 'DD-MM-YYYY') AS newdate")
                ->where('display', 'Y')
                ->where('is_holiday', 0)
                ->where('is_nmd', 0)
                ->where('working_date >=', $next_date)
                ->orderBy('working_date', 'asc')
                ->limit(1)
                ->get();
        } else {
            // Build the query for 28 days after the current date
            $result_nm = $this->db->table('master.sc_working_days')
                ->select("TO_CHAR(working_date, 'DD-MM-YYYY') AS newdate")
                ->where('display', 'Y')
                ->where('is_holiday', 0)
                ->where('is_nmd', 0)
                ->where("working_date > (CURRENT_DATE + 28)")
                ->orderBy('working_date', 'asc')
                ->limit(1)
                ->get();
        }

        // Check if any rows were returned
        if ($result_nm->getNumRows() > 0) {
            // Get the next working date
            $nextmonday = $result_nm->getRow()->newdate;
        }

        return $nextmonday;
    }

    public function getInterlocutaryApplications($fil_no = [])
    {

        $listed_ia = $fil_no['listed_ia'];

        //IAN
        $fil_no_diary_no = $fil_no['diary_no'];
        $results_ian = $this->db->table('docdetails a')
            ->select('a.diary_no, a.doccode, a.doccode1, a.docnum, a.docyear, a.filedby, a.docfee, a.forresp, a.feemode, a.ent_dt, a.other1, a.iastat, b.docdesc')
            ->join('master.docmaster b', 'a.doccode = b.doccode AND a.doccode1 = b.doccode1')
            ->where('a.diary_no', $fil_no_diary_no)
            ->where('a.doccode', 8)
            ->where('a.display', 'Y')
            ->where('b.display', 'Y')
            ->orderBy('a.ent_dt', 'ASC')
            ->get();

        $iancntr = 1;
        $ian_p = "";
        $ian = "";

        if ($results_ian->getNumRows() > 0) {

            foreach ($results_ian->getResultArray() as $row_ian) {

                if ($ian_p == "" and $row_ian["iastat"] == "P") {
                    $ian_p =  '<table class="table_tr_th_w_clr c_vertical_align" width="100%">';
                    $ian_p .= "<tr><td align='center'><input class='cls_chkdn' type='checkbox' name='allchkdn' id='allchkdn' value='all' onclick='chk_all_cn();'></td><td align='center'><b>Reg.No.</b></td><td><b>Particular</b></td><td align='center'><b>Date</b></td></tr>";
                }
                if ($iancntr == 1) {
                    $ian = '<table class="table_tr_th_w_clr c_vertical_align" width="100%">';
                    $ian .= "<tr><td align='center' width='30px'><b>IA.NO.</b></td><td align='center' width='120px'><b>Reg.No.</b></td><td><b>Particular</b></td><td><b>Filed By</b></td><td align='center' width='80px'><b>Date</b></td><td align='center' width='70px'><b>Status</b></td></tr>";
                }
                if ($row_ian["other1"] != "")
                    $t_part = $row_ian["docdesc"] . " [" . $row_ian["other1"] . "]";
                else
                    $t_part = $row_ian["docdesc"];
                $t_ia = "";
                if ($row_ian["iastat"] == "P")
                    $t_ia = "<font color='blue'>" . $row_ian["iastat"] . "</font>";
                if ($row_ian["iastat"] == "D")
                    $t_ia = "<font color='red'>" . $row_ian["iastat"] . "</font>";
                $ian .= "<tr><td align='center'>" . $iancntr . "</td><td align='center'>" . $row_ian["docnum"] . "/" . $row_ian["docyear"] . "</td><td>" . str_replace("XTRA", "", $t_part) . "</td><td>" . $row_ian["filedby"] . "</td><td align='center'>" . date("d-m-Y", strtotime($row_ian["ent_dt"])) . "</td><td align='center'><b>" . $t_ia . "</b></td></tr>";
                if ($row_ian["iastat"] == "P") {
                    $t_iaval = $row_ian["docnum"] . "/" . $row_ian["docyear"] . ",";
                    if (!empty($listed_ia) && (strpos($listed_ia, $t_iaval) !== false))
                        $check = "checked='checked'";
                    else
                        $check = "";
                    //$ian_p.="<tr><td align='center'><input type='checkbox' name='iachbx" . $iancntr . "' id='iachbx" . $iancntr . "' value='" . $row_ian["docnum"] . "/" . $row_ian["docyear"] . "|#|" . str_replace("XTRA", "", $t_part) . "' onClick='feed_rmrk();'  " . $check . "></td><td align='center'>" . $row_ian["docnum"] . "/" . $row_ian["docyear"] . "</td><td align='left'>" . str_replace("XTRA", "", $t_part) . "</td><td align='center'>" . date("d-m-Y", strtotime($row_ian["ent_dt"])) . "</td></tr>";
                    $ian_p .= "<tr><td align='center'><input type='checkbox' name='iachbx" . $iancntr . "' id='iachbx" . $iancntr . "' value='" . $row_ian["docnum"] . "/" . $row_ian["docyear"] . "|#|" . str_replace("XTRA", "", $t_part) . "' onClick='feed_rmrkia();'  " . $check . "></td><td align='center'>" . $row_ian["docnum"] . "/" . $row_ian["docyear"] . "</td><td align='left'>" . str_replace("XTRA", "", $t_part) . "</td><td align='center'>" . date("d-m-Y", strtotime($row_ian["ent_dt"])) . "</td></tr>";
                }
                $iancntr++;
            }
        }
        if ($ian != "")
            $ian .= "</table><br>";
        if ($ian_p != "")
            $ian_p .= "</table><br><span style='font-align:left;'><font size=+1 color=blue>If any disposed IA is listed here then disposed it off using IA UPDATE module before proposal updation</font></span>";

        return ['ian_p' => $ian_p, 'html' => $ian];
    }

    public function get_result_1($idn = 0, $id = 0)
    {
        $sessionUser = session()->get('login')['usercode'];

        $builder = $this->db->table('obj_save');

        if ($idn == '0') {
            $builder->set('rm_dt', 'NOW()', false)
                    ->set('rm_user_id', $sessionUser)
                    ->where('id', $id)
                    ->where('display', 'Y');
        } elseif ($idn == '1') {
            $builder->set('display', 'I')
                    ->set('status', '7')
                    ->set('rm_user_id', $sessionUser)
                    ->where('id', $id)
                    ->where('display', 'Y');
        }

        // echo $builder->getCompiledUpdate();die;

        $builder->update();

        return ($this->db->affectedRows() > 0) ? 1 : 0;
    }

    public function getOtherDocuments($fil_no = [])
    {
        $fil_no_diary_no = $fil_no['diary_no']; // Make sure to sanitize input for security

        $results_od = $this->db->table('docdetails a')
            ->select('a.diary_no, a.doccode, a.doccode1, a.docnum, a.docyear, a.filedby, a.docfee, a.forresp, a.feemode, a.ent_dt, a.other1, b.docdesc')
            ->join('master.docmaster b', 'a.doccode = b.doccode AND a.doccode1 = b.doccode1')
            ->where('a.diary_no', $fil_no_diary_no)
            ->where('a.doccode !=', 8)
            ->where('a.display', 'Y')
            ->orderBy('a.ent_dt', 'ASC')
            ->get();

        $odcntr = 1;
        $oth_doc = "";

        if ($results_od->getNumRows() > 0) {

            foreach ($results_od->getResultArray() as $row_od) {

                if ($odcntr == 1) {
                    $oth_doc =  '<table class="table_tr_th_w_clr c_vertical_align" width="100%">';
                    $oth_doc .= "<tr><td align='center' width='30px'><b>S.N.</b></td><td align='center' width='120px'><b>Reg.No.</b></td><td><b>Document Type</b></td><td><b>Filed By</b></td><td align='center' width='80px'><b>Date</b></td><td align='center'><b>Other</b></td></tr>";
                }
                if (trim($row_od["docdesc"]) == 'OTHER')
                    $docdesc = $row_od["other1"];
                else
                    $docdesc = $row_od["docdesc"];
                if ($row_od["doccode"] == 7 and $row_od["doccode1"] == 0)
                    $doc_oth = ' Fees Mode: ' . $row_od["feemode"] . ' For Resp: ' . $row_od["forresp"];
                else
                    $doc_oth = '';
                $oth_doc .= "<tr><td align='center'>" . $odcntr . "</td><td align='center'>" . $row_od["docnum"] . "/" . $row_od["docyear"] . "</td><td>" . $docdesc . "</td><td>" . $row_od["filedby"] . "</td><td align='center'>" . date("d-m-Y", strtotime($row_od["ent_dt"])) . "</td><td align='center'>" . $doc_oth . "</td></tr>";
                $odcntr++;
            }
            if ($oth_doc != "")
                $oth_doc .= "</table><br>";
        }

        return $oth_doc;
    }

    public function ifInAdvanceListSingleJudge($diaryNo)
    {
        $result = false;

        // Sanitize input
        $diaryNo = $diaryNo; // Ensure it's an integer to prevent SQL injection

        $res = $this->db->table('advance_allocated aa')
            ->select('aa.next_dt')
            ->join('advanced_drop_note ad', 'aa.diary_no = ad.diary_no AND aa.next_dt = ad.cl_date', 'left')
            ->join('heardt h', 'h.diary_no::varchar(50) = aa.diary_no AND h.next_dt = aa.next_dt', 'left')
            ->where('h.diary_no IS NOT NULL')
            ->where('h.board_type', 'S')
            ->where('cast(aa.diary_no as int)', $diaryNo)
            ->where('aa.next_dt >=', date('Y-m-d')) // PostgreSQL date comparison
            ->groupBy('aa.conn_key, aa.next_dt, ad.cl_date')
            ->having('ad.cl_date IS NULL') // Using HAVING for aggregate conditions
            ->get();

        if ($res->getNumRows() > 0) {
            $result = true;
        }

        return $result;
    }

    public function ifInFinalListSingleJudge($diaryNo)
    {
        $result = false;

        // Sanitize input
        $diaryNo = $diaryNo; // Ensure it's an integer to prevent SQL injection

        $subQuery = $this->db->table('advance_allocated aa')
            ->select('aa.next_dt')
            ->join('advanced_drop_note ad', 'aa.diary_no = ad.diary_no AND aa.next_dt = ad.cl_date', 'left')
            ->join('heardt h', 'h.diary_no::varchar(50) = aa.diary_no AND h.next_dt = aa.next_dt', 'left')
            ->where('h.diary_no IS NOT NULL')
            ->where('h.board_type', 'S')
            ->where('aa.diary_no', $diaryNo)
            ->where('aa.next_dt >=', date('Y-m-d')) // PostgreSQL date comparison
            ->groupBy('aa.conn_key, aa.next_dt, ad.cl_date')
            ->having('ad.cl_date IS NULL');

        $res = $this->db->table('heardt h')
            ->select('h.next_dt')
            ->join('(' . $subQuery->getCompiledSelect() . ') b', 'h.next_dt = b.next_dt')
            ->whereIn('h.main_supp_flag', [1, 2])
            ->where('h.diary_no', $diaryNo)
            ->get();

        if ($res->getNumRows() > 0) {
            $result = true;
        }

        return $result;
    }

    public function ifInFinalList($diaryNo)
    {
        $result = false;
        // Sanitize input
        $diaryNo = $diaryNo; // Ensure it's an integer to prevent SQL injection
        // Subquery to get next_dt
        $subQuery = $this->db->table('advance_allocated aa')
            ->select('aa.next_dt')
            //->join('advanced_drop_note ad', 'aa.diary_no = ad.diary_no AND aa.next_dt = ad.cl_date', 'left')
            ->join('advanced_drop_note ad', 'aa.diary_no = ad.diary_no AND aa.next_dt = ad.cl_date', 'left')
            ->join('heardt h', 'h.diary_no::varchar(50) = aa.diary_no AND h.next_dt = aa.next_dt', 'left')
            ->where('h.diary_no IS NOT NULL')
            ->where('aa.diary_no', $diaryNo)
            ->where('aa.next_dt >=', date('Y-m-d')) // PostgreSQL date comparison
            ->groupBy('aa.conn_key, aa.next_dt, ad.cl_date')
            ->having('ad.cl_date IS NULL');
        // echo $subQuery->getCompiledSelect();
        // die;

        // Main query to check final list
        $res = $this->db->table('heardt h')
            ->select('h.next_dt')
            ->join('(' . $subQuery->getCompiledSelect() . ') b', 'h.next_dt = b.next_dt')
            ->whereIn('h.main_supp_flag', [1, 2])
            ->where('h.diary_no', $diaryNo)
            ->get();

        if ($res->getNumRows() > 0) {
            $result = true;
        }

        return $result;
    }



    public function ifInAdvanceList($diaryNo)
    {
        $result = false;

        // Sanitize input
        $diaryNo = $diaryNo; 
        // Ensure it's an integer to prevent SQL injection

        // $res = $this->db->table('advance_allocated aa')
        //     ->select('aa.next_dt')
        //     ->join('advanced_drop_note ad', "aa.diary_no = ad.diary_no AND aa.next_dt = ad.cl_date", 'left')
        //     ->join('heardt h', "h.diary_no = aa.diary_no AND h.next_dt = aa.next_dt", 'left')
        //     ->where('h.diary_no IS NOT NULL')
        //     ->where('aa.diary_no', $diaryNo)
        //     ->where('aa.next_dt >=', date('Y-m-d')) // PostgreSQL date comparison
        //     ->groupBy('aa.conn_key, aa.next_dt, ad.cl_date')
        //     ->having('ad.cl_date IS NULL') // Using HAVING for aggregate conditions
        //     ->get();

        $sql = "
            SELECT
                aa.next_dt
            FROM
                advance_allocated aa
                LEFT JOIN advanced_drop_note ad ON aa.diary_no = ad.diary_no
                AND aa.next_dt = ad.cl_date
                LEFT JOIN heardt h ON h.diary_no::text = aa.diary_no
                AND h.next_dt = aa.next_dt
            WHERE
                h.diary_no IS NOT NULL
                AND aa.diary_no::bigint = " . $diaryNo . "
                AND aa.next_dt >= '" . date('Y-m-d') . "'
            GROUP BY
                aa.conn_key,
                aa.next_dt,
                ad.cl_date
            HAVING
                ad.cl_date IS NULL";        
        $res = $this->db->query($sql);

        if ($res->getNumRows() > 0) {
            $result = true;
        }

        return $result;
    }

    public function checkUpdation($fil_no = [])
    {
        $ucode = session()->get('login')['usercode'];
        $usection = session()->get('login')['section'];

        $fil_no_diary_no = $fil_no['diary_no']; // Make sure to sanitize input for security

        $sq_ck_da_cd = $this->db->table('main m')
            ->select('COALESCE(m.dacode::text, \'\') AS dacode, COALESCE(u.name, \'\') AS username, u.empid')
            ->join('master.users u', 'm.dacode = u.usercode', 'left')
            ->where('m.diary_no', $fil_no_diary_no)
            ->get();

        if ($sq_ck_da_cd->getNumRows() > 0) {
            $row_lp123 = $sq_ck_da_cd->getRowArray(); // Fetch the first row as an associative array


            if ($row_lp123["username"] == "" and $row_lp123["dacode"] == "")
                $output1 = "0|#|NO DA INFORMATION AVAILABLE FOR THIS CASE|#|" . $row_lp123["empid"];
            else if ($row_lp123["username"] == "" and ($row_lp123["dacode"] != $ucode))
                $output1 = "0|#|UPDATION/MODIFICATION IN THIS CASE CAN BE DONE ONLY BY DA USER ID : " . $row_lp123["empid"] . " [DA NAME NOT AVAILABLE]|#|" . $row_lp123["dacode"];
            else if ($row_lp123["dacode"] != $ucode && $ucode != 646)
                $output1 = "0|#|UPDATION/MODIFICATION IN THIS CASE CAN BE DONE ONLY BY DA : " . $row_lp123["username"] . " [USER ID : " . $row_lp123["empid"] . "]|#|" . $row_lp123["dacode"];
            else
                $output1 = "1|#|RIGHT DA|#|" . $row_lp123["dacode"];
        }
        //$users_to_ignore = array(1, 469, 742, 646, 613, 3633,559,1068,1485);
        // $users_to_ignore = array(1, 559,1485);
        $users_to_ignore = array(1, 2229, 47, 770, 3564, 846, 1366, 1363, 586, 1224, 148, 2431, 723, 724, 109, 2977, 747, 742, 1504, 935, 9919, 49, 723, 757, 744, 94, 663, 722, 753, 985, 637, 10606, 10605, 12, 1169, 1206, 3623, 3625, 770, 9912, 9919, 9988, 633, 762, 10674, 10675, 10705, 10656, 10670, 10730, 10739, 3113, 10749); // 94 & 663 added on 22-12-2022 on suggetion of Preeti Agrawal
        $result_da = explode("|#|", $output1);
        $rmtable = "";
        //Added on 14.03.2019 by preeti to allow CM to change remarks for 2 days then DA for 3rd and 4th day and after that DMT or Listing Section
        $reslt_validate_caseInAdvanceList = $this->ifInAdvanceList($fil_no['diary_no']);
        $result_caseInFinalList = $this->ifInFinalList($fil_no['diary_no']);
        $reslt_validate_caseInAdvanceListSingleJudge = $this->ifInAdvanceListSingleJudge($fil_no['diary_no']);
        $result_caseInFinalListSingleJudge = $this->ifInFinalListSingleJudge($fil_no['diary_no']);

        // $sno += 1;
        //Query to check whether the user is working as court master also
        $is_courtMaster = "";


        // Check if the user is a court master
        $row_courtMaster = $this->db->table('master.users')
            ->select('is_courtmaster')
            ->where('usercode', $ucode)
            ->get()
            ->getRowArray(); // Fetch the first row as an associative array

        // Create subquery for last_heardt
        $subQuery1 = $this->db->table('last_heardt')
            ->select('next_dt')
            ->where('diary_no', $fil_no['diary_no'])
            ->where('clno !=', 0)
            ->where('brd_slno !=', 0)
            ->where('roster_id !=', 0)
            ->where('judges !=', '0')
            ->where('judges !=', '')
            ->groupStart() // Start grouping conditions
            ->where('bench_flag IS NULL')
            ->orWhere('bench_flag =', '')
            ->groupEnd() // End grouping conditions
            ->whereIn('main_supp_flag', [1, 2])
            ->getCompiledSelect(); // Get the SQL string for the subquery

        // Create subquery for heardt
        $subQuery2 = $this->db->table('heardt')
            ->select('next_dt')
            ->where('diary_no', $fil_no['diary_no'])
            ->where('clno !=', 0)
            ->where('brd_slno !=', 0)
            ->where('roster_id !=', 0)
            ->where('judges !=', '0')
            ->where('judges !=', '')
            ->whereIn('main_supp_flag', [1, 2])
            ->getCompiledSelect(); // Get the SQL string for the subquery

        // Combine both subqueries with UNION
        $combinedQuery = "SELECT MAX(next_dt) AS date FROM ($subQuery1 UNION $subQuery2) AS a";

        // Execute the combined query and fetch the result
        $row_last_listed = $this->db->query($combinedQuery)->getRowArray();


        // !! setting the current date if not get the last listed date from case. !!
        $last_listing_date = (isset($row_last_listed['date'])) ? $row_last_listed['date'] : date("Y-m-d");

        // $sql_last_listed = "select max(next_dt) as date from(select next_dt from last_heardt where diary_no='" . $fil_no['diary_no'] . "' and  clno!=0 and brd_slno!=0 and roster_id!=0 and judges!=0 and judges!='' and (bench_flag=null or bench_flag='') and main_supp_flag in(1,2)
        //                     union
        //                     select next_dt from heardt where diary_no='" . $fil_no['diary_no'] . "' and  clno!=0 and brd_slno!=0 and roster_id!=0 and judges!=0 and judges!='' and main_supp_flag in(1,2) )a";
        // $result_last_listed = mysql_query($sql_last_listed);
        // $row_last_listed = mysql_fetch_array($result_last_listed);
        // $last_listing_date = $row_last_listed['date'];

        // Query to get working dates
        $subQuery3 = $this->db->table('master.sc_working_days')
            ->select('working_date')
            ->where('holiday_for_registry', 0)
            ->where('working_date >', $last_listing_date)
            ->limit(4)
            ->getCompiledSelect(); // Get the SQL string for the subquery

        // echo $subQuery3; die;

        // Combine results into a single string
        $row_working_dates = $this->db->query("SELECT STRING_AGG(working_date::text, ', ') AS dates FROM ($subQuery3) AS a")->getRowArray();

        $working_dates = (string) $row_working_dates['dates'];
        $working_date = explode(',', $working_dates);

        $remarks = $fil_no['remarks'];
        $row_PIP = $fil_no['row_PIP'];
        $row_sensitive = $fil_no['row_sensitive'];
        $only_can_update_469 = $fil_no['only_can_update_469'];
        $check_for_case_is_listed_after_current_date_remark = $fil_no['check_for_case_is_listed_after_current_date_remark'];
        $user_case_updation = $this->get_case_status_flag('case_updation');
        $result_array = $this->get_case_status_flag('case_updated_for_but_not_listed_date');

        //echo "listing".$last_listing_date;
        //Code added by preeti agrawal on 18.6.21 to grant not-ready access to Walia Sir if matter is verified
        $reslt_validate_verification = validate_verification($fil_no['diary_no']);
        if ($reslt_validate_verification > 0 and ($ucode == 1504 or $ucode == 94) and (($row_sensitive != '' and $row_sensitive != null) or ($row_PIP != '' and $row_PIP != null))) {
            $only_can_update_469 = "Verification Pending from I-B section. Hence,updation is not allowed in the matter";
        }
        //code ends here
        $notice_remarks = array(3, 182, 183, 184, 203);
        $noticeissued = 0;
        $allowed = 0;
        /* ucode 1504 and 94 is for Addl. Reg.(I-B) to provide him access to make matter Ready or Not-Ready*/
        if (in_array($remarks, $notice_remarks))
            $noticeissued = 1;
        if ($result_array['display_flag'] == '1' || in_array($ucode, explode(',', $result_array['always_allowed_users'])))
            $allowed = 1;
        if ($result_da[0] > 0 or (in_array($ucode, $users_to_ignore))) {
            if (in_array($remarks, $notice_remarks) && $result_caseInFinalList == true)
                $rmtable = "<center><b><font color='red' style='font-size:16px;'>Case Listed in Final List, Contact to DEU-II Section</font></b></center>";
            else if (in_array($remarks, $notice_remarks) && $result_caseInFinalListSingleJudge == true)
                $rmtable = "<center><b><font color='red' style='font-size:16px;'>Case Listed in Final List before Single Judge, Contact to DEU-II Section</font></b></center>";

            else if ($reslt_validate_caseInAdvanceList == true && $ucode != 1504 && $ucode != 94 && !in_array($remarks, $notice_remarks) && !($result_array['display_flag'] == '1' || in_array($ucode, explode(',', $result_array['always_allowed_users'])))) {
                $rmtable = "<center><b><font color='red' style='font-size:16px;'>Case Listed in Advance List, Contact to DEU-II Section</font></b></center>";
            } else if ($reslt_validate_caseInAdvanceListSingleJudge == true && $ucode != 1504 && $ucode != 94 && !in_array($remarks, $notice_remarks) && !($result_array['display_flag'] == '1' || in_array($ucode, explode(',', $result_array['always_allowed_users'])))) {
                $rmtable = "<center><b><font color='red' style='font-size:16px;'>Case Listed in Advance List before Single Judge, Contact to DEU-II Section</font></b></center>";
            } else if ($check_for_case_is_listed_after_current_date_remark != "") {
                $rmtable .= "<center><b><font color='red' style='font-size:16px;'>" . $check_for_case_is_listed_after_current_date_remark . "</font></b></center>";
            } else {
                if ((strtotime($last_listing_date) < strtotime('2019-04-02') and strtotime($last_listing_date) != null and strtotime($last_listing_date) != '') /*or (in_array($ucode, $users_to_ignore)) or $ucode == 1*/) {  // before implementation of the modified code, no checking

                    if ($only_can_update_469 == "") {
                        $rmtable .= '<p align="center"><input type="button" value="Updation" onclick="call_f1();"></p>';
                    } else {
                        $rmtable .= "<center><b><font color='red' style='font-size:16px;'>" . $only_can_update_469 . "</font></b></center>";

                        if (($user_case_updation['display_flag'] == '1' || in_array($ucode, explode(',', $user_case_updation['always_allowed_users'])))) {
                            // if($ucode == 469 OR $ucode == 559 OR $ucode==1485){
                            $rmtable .= '<p align="center"><input type="button" value="Updation" onclick="call_f1();"></p>';
                        }
                    }
                } else {

                    if ($only_can_update_469 == "") {
                        if (($ucode == 1504 || $ucode == 94) and (($row_sensitive == '' or $row_sensitive == null) and ($row_PIP == '' or $row_PIP == null)))
                            $rmtable .= "<center><b><font color='red' style='font-size:16px;'>The Searched Matter is neither Sensitive Matter nor Petitioner-IN-Person. Hence, you are not authorized to update the matter.</font></b></center>";
                        else
                            $rmtable .= '<p align="center"><input type="button" value="Updation" onclick="call_f1();"></p>';
                    } else if ($only_can_update_469 != "") {
                        $rmtable .= "<center><b><font color='red' style='font-size:16px;'>" . $only_can_update_469 . "</font></b></center>";

                        if (($user_case_updation['display_flag'] == '1' || in_array($ucode, explode(',', $user_case_updation['always_allowed_users'])))/* or ($ucode==1504 and $row_sensitive!='')*/) {
                            // if($ucode == 469 OR $ucode == 559 OR $ucode==1485){
                            $rmtable .= '<p align="center"><input type="button" value="Updation" onclick="call_f1();"></p>';
                        }
                    } else if ((strtotime(date('Y-m-d')) == strtotime($last_listing_date) or (strtotime(date('Y-m-d')) == strtotime($working_date[0]))) && ($usection == '11' or $usection == '62' or $usection == '81' or $is_courtMaster == 'Y')) {  // if date diff=2 and CM
                        $rmtable .= '<p align="center"><input type="button" value="Updation" onclick="call_f1();"></p>';
                    } else if ((strtotime(date('Y-m-d')) == strtotime($last_listing_date) or (strtotime(date('Y-m-d')) == strtotime($working_date[0])))  && ($usection != '11' and $usection != '62' and $usection != '81' and $is_courtMaster == 'N')) { // if datediff=2 and not CM
                        $rmtable = "<center><b><font color='red' style='font-size:16px;'>Updation can be done by concerned Court Master for 2 days from date of listing.</font></b></center>";
                    } else if ((strtotime(date('Y-m-d')) == strtotime($working_date[1]) or (strtotime(date('Y-m-d')) == strtotime($working_date[2])))  and $ucode == $row_lp123["dacode"]) { // if datediff>=2 and <4 and right DA
                        $rmtable .= '<p align="center"><input type="button" value="Updation" onclick="call_f1();"></p>';
                    } else if ((strtotime(date('Y-m-d')) == strtotime($working_date[1]) or (strtotime(date('Y-m-d')) == strtotime($working_date[0])))  and $ucode != $row_lp123["dacode"]) { // if datediff>=2 and <4 and not right DA
                        $rmtable = "<center><b><font color='red' style='font-size:16px;'>Updation can be done by concerned Dealing Assistant only for 2 days after 2 days of listing.</font></b></center>";
                    } else if ((strtotime(date('Y-m-d')) >= strtotime($working_date[3])) and ($result_array['display_flag'] == '1' || in_array($ucode, explode(',', $result_array['always_allowed_users'])))) { //if datediff>4 and login user is from DMT or Listing
                        $rmtable .= '<p align="center"><input type="button" value="Updation" onclick="call_f1();"></p>';
                    } /*else if ($dateDiff < 0)    //if next date is of future date
                    {
                        $rmtable = "";
                    }*/ else if ($reslt_validate_caseInAdvanceList == true && !($result_array['display_flag'] == '1' || in_array($ucode, explode(',', $result_array['always_allowed_users'])))) { // if case is in Advance List
                        $rmtable = "<center><b><font color='red' style='font-size:16px;'>Case Listed in Advance List, Contact Data Monitoring Team or Listing Section</font></b></center>";
                    } else if ($reslt_validate_caseInAdvanceListSingleJudge == true && !($result_array['display_flag'] == '1' || in_array($ucode, explode(',', $result_array['always_allowed_users'])))) { // if case is in Advance List
                        $rmtable = "<center><b><font color='red' style='font-size:16px;'>Case Listed in Advance List before Single Judge, Contact Data Monitoring Team or Listing Section</font></b></center>";
                    } else { // default
                        $rmtable = "<center><b><font color='red' style='font-size:16px;'>Updation cannot be done.Contact Court Master for 2 days from date of listing then DA for next 2 days then Data Monitoring Team or Listing section.</font></b></center>";
                    }
                }
            }
        } else {
            $rmtable .= "<center><b><font color='red' style='font-size:16px;'>" . $result_da[1] . "</font></b></center>";
        }

        $output = '';
        if ($fil_no['c_status'] == 'P') {
            $output = $rmtable;
        }

        return ['rmtable' => $output, 'allowed' => $allowed, 'noticeissued' => $noticeissued];
    }

    public function get_conn_cases($diary_no)
    {
        $me2 = [];
        $chk_for_main = '';

        if ($diary_no != "") {
            // Query to get conn_key
            $conn_key = $this->db->table('main')
                ->select('conn_key')
                ->where('diary_no', $diary_no)
                ->where('conn_key IS NOT NULL')
                ->where('conn_key !=', '')
                ->where('conn_key !=', '0')
                ->get()
                ->getRowArray(); // Fetch as associative array

            // Check if conn_key was found
            if ($conn_key) {
                $conn_key = $conn_key['conn_key'];

                // Query to get connection types
                $result_p = $this->db->table('conct')
                    ->select("diary_no, CASE WHEN conn_key = diary_no THEN 'M' ELSE conn_type END AS c_type, list")
                    ->where('conn_key', $conn_key)
                    ->where('diary_no IS NOT NULL')
                    ->orderBy("CASE WHEN diary_no = '{$conn_key}' THEN 0 ELSE 1 END", 'ASC')
                    ->orderBy('c_type', 'DESC')
                    ->get();

                foreach ($result_p->getResultArray() as $row) {
                    if ($chk_for_main == '' && $row['c_type'] != 'M') {
                        $me2[$conn_key] = [
                            'diary_no' => $conn_key,
                            'c_type' => 'M',
                            'list' => 'Y'
                        ];
                        $chk_for_main = 'over';
                    }
                    $me2[$row['diary_no']] = [
                        'diary_no' => $row['diary_no'],
                        'c_type' => $row['c_type'],
                        'list' => $row['list']
                    ];
                }
            }
        }

        return $me2;
    }


    public function getConnectedLinkedCases($fil_no = [])
    {
        $conncases = $this->get_conn_cases($fil_no['diary_no']);

        $output = "";
        $connchks = "";

        if (count($conncases) > 0) {

            $connchks =  "<table class='table_tr_th_w_clr c_vertical_align' width='100%'><tr><td align='center' colspan='5'><font color='red'><b>CONNECTED CASES</b></font></td></tr>";
            $connchks .= "<tr><td align='center' width='30px'><input type='checkbox' name='checkall' id='checkall'/></td><td><b>Case No.</b></td><td><b>Petitioner Vs. Respondant</b></td><td align='center'><b>Status</b></td><td><b>IA</b></td></tr>";

            $sn = 0;
            $ttl_checked = 0;
            foreach ($conncases as $row => $link) {

                if ($link['c_type'] != "") {

                    $sn++;
                    $main_details = $this->get_main_details($link['diary_no'], 'diary_no,pet_name,res_name,c_status,fil_no_fh');
                    $t_pname = '';
                    $t_rname = '';
                    $t_link = '';
                    $t_status = '';
                    if (is_array($main_details)) {
                        foreach ($main_details as $rowm => $linkm) {
                            $t_pname = $linkm['pet_name'];
                            $t_rname = $linkm['res_name'];
                            if ($linkm['c_status'] == 'P')
                                $t_status = "<font color=blue>" . $linkm['c_status'] . "</font>";
                            else
                                $t_status = "<font color=red>" . $linkm['c_status'] . "</font>";
                            if ($link["list"] == 'Y')
                                $t_link = "<font color=blue>" . $link["list"] . "</font>";
                            else
                                $t_link = "<font color=red>" . $link["list"] . "</font>";
                            $t_fil_no_fh = $linkm['fil_no_fh'];
                            if ($link["list"] == "Y" and $link['c_type'] != "M") {
                                $chked = "checked";
                                $ttl_checked++;
                            } else
                                $chked = "";
                            if ($linkm['c_status'] == "D")
                                $chked = " disabled=disabled";
                        }
                    }
                    $t_brdrem = $this->get_brd_remarks($link['diary_no']);
                    $t_conn_type = "";
                    if ($link['c_type'] == "M") {
                        $t_conn_type = "<font color=red>Main Case</font>";
                    }
                    if ($link['c_type'] == "C") {
                        $t_conn_type = "<font color=blue>Connected</font>";
                    }
                    if ($link['c_type'] == "L") {
                        $t_conn_type = "<font color=green>Linked</font>";
                    }
                    //DA NAME START FOR CONNECTED
                    $da_name_conn = "";

                    $row_da_conn = $this->db->table('main a')
                        ->select('a.dacode, b.name, us.section_name')
                        ->join('master.users b', 'a.dacode = b.usercode', 'left')
                        ->join('master.usersection us', 'b.section = us.id', 'left')
                        ->where('a.diary_no', $link['diary_no'])
                        ->where('a.dacode !=', 0)
                        ->get()
                        ->getRowArray(); // Fetch the first row as an associative array

                    // Check if any row was returned
                    if ($row_da_conn) {
                        $da_name_conn = "<font color='blue' style='font-size:10px;'>" . $row_da_conn["name"] . "</font><br>";
                        //                if ($row_da_conn["username"] != "")111111
                        //                    $da_name_conn.="<font style='font-size:12px;font-weight:bold;'> [</font><font color='green' style='font-size:12px;font-weight:bold;'>" . $row_da_conn["username"] . "</font><font style='font-size:12px;font-weight:bold;'>]</font>";
                        if ($row_da_conn["dacode"] != "")
                            $da_name_conn .= "[<font color='red' style='font-size:10px;'>" . $row_da_conn["section_name"] . "</font>]";
                    }
                    //DA NAME ENDS FOR CONNECTED
                    //       echo "nnn".$link['diary_no'];
                    $category = get_mul_category($link['diary_no']);
                    $category = $category[0] ?? '';

                    $t_current_proposed = str_replace('|#|', '<br>', $this->get_listing_dates($link['diary_no']));
                    if ($link['c_type'] != "M")
                        $output .= "<tr><td align='center' width='30px'>" . $sn . "</td><td align=center><b>" . '<a href="' . base_url('Common/Case_status') . '?diaryno=' . $link['diary_no'] . '" target="_blank">' . get_real_diaryno($link['diary_no']) . "</a></b><br>" . $t_conn_type . "</td><td nowrap>" . get_casenos_comma($link['diary_no']) . "</td><td>" . $t_current_proposed . "</td><td>" . $t_pname . " Vs. " . $t_rname . "</td><td>" . $category . "</td><td align='center'>" . $t_status . "</td><td align='center'></td><td align='center'>" . $t_link . "</td><td>" . $da_name_conn . "</td></tr>";
                    else
                        $output .= "<tr><td align='center' width='30px'>" . $sn . "</td><td align=center><b>" . get_real_diaryno($link['diary_no']) . "</b><br>" . $t_conn_type . "</td><td nowrap>" . get_casenos_comma($link['diary_no']) . "</td><td>" . $t_current_proposed . "</td><td>" . $t_pname . " Vs. " . $t_rname . "</td><td>" . $category . "</td><td align='center'>" . $t_status . "</td><td align='center'></td><td align='center'>" . $t_link . "</td><td>" . $da_name_conn . "</td></tr>";

                    if ($link['c_type'] != "M") {
                        if ($t_fil_no_fh == '') {
                            // $t_check='<div class="fh_error" style="display:none;"><font color="red">Case is not registered in Regular Hearing (If Registration not required then ignore)</font></div>';
                            $t_check = '<div class="fh_error" style="display:none;"><font color="red">Check whether Direct Appeal or Not. If Not inform Computer Cell</font></div>';
                        } else
                            $t_check = '';

                        $connchks .= "<tr><td align='center'>";
                        if ($linkm['c_status'] != "D")
                            $connchks .= "<input type='checkbox' name='ccchk" . $link['diary_no'] . "' id='ccchk" . $link['diary_no'] . "' value='" . $link['diary_no'] . "' " . $chked . " >";
                        $connchks .= "</td><td>D.No. : " . get_real_diaryno($link['diary_no']) . "<br>" . get_case_nos($link['diary_no'], '&nbsp;&nbsp;') . "</td><td>" . $t_pname . " Vs. " . $t_rname . $t_check . "</td><td align='center'>" . $t_status . "</br>" . $t_current_proposed . "</td><td><input type='hidden' name='brdremh_" . $link['diary_no'] . "' id='brdremh_" . $link['diary_no'] . "' value=" . $t_brdrem . "><textarea style='width:95%' name='brdrem_" . $link['diary_no'] . "' id='brdrem_" . $link['diary_no'] . "' rows='3'>" . $t_brdrem . "</textarea>" . $this->get_ia($link['diary_no']) . "</td></tr>";
                    }
                }
            }
            $connchks .= "</table>";

            $connchks .= "<font color=red>Total Connected Cases Selected : <span id='ttlconn'>" . $ttl_checked . "</span></font>";
        }

        return ['conncases' => $conncases, 'connchks' => $connchks, 'html' => $output];
    }

    public function get_ia($dn)
    {
        $ian_p_conn = "";

        // Query to get document details
        $query_ian_conn = $this->db->table('docdetails a')
            ->select('a.diary_no, a.doccode, a.doccode1, a.docnum, a.docyear, a.filedby, a.docfee, a.forresp, a.feemode, a.ent_dt, a.other1, a.iastat, b.docdesc')
            ->join('master.docmaster b', 'a.doccode = b.doccode AND a.doccode1 = b.doccode1', 'inner')
            ->where('a.diary_no', $dn)
            ->where('a.doccode', 8)
            ->where('a.display', 'Y')
            ->orderBy('a.ent_dt')
            ->get();

        if ($query_ian_conn->getNumRows() > 0) {
            $ian_p_inhdt = $listed_ia_conn = "";

            // Query to get listed IA
            $query_ian_inhdt = $this->db->table('heardt')
                ->select('listed_ia')
                ->where('diary_no', $dn)
                ->get();

            if ($query_ian_inhdt->getNumRows() > 0) {
                $row_ian_inhdt = $query_ian_inhdt->getRowArray();
                $listed_ia_conn = $row_ian_inhdt["listed_ia"];
            }

            $iancntr_conn = 1;

            foreach ($query_ian_conn->getResultArray() as $row_ian_conn) {
                if ($ian_p_conn == "" && $row_ian_conn["iastat"] == "P") {
                    $ian_p_conn = "<div style='overflow:auto; max-height:100px;'><table border='1' bgcolor='#F5F5FC' class='tbl_hr' width='98%' cellspacing='0' cellpadding='3'>";
                }

                // Prepare document description
                $t_part_conn = !empty($row_ian_conn["other1"]) ? $row_ian_conn["docdesc"] . " [" . $row_ian_conn["other1"] . "]" : $row_ian_conn["docdesc"];

                // Determine status color
                $t_ia_conn = "";
                if ($row_ian_conn["iastat"] == "P") {
                    $t_ia_conn = "<font color='blue'>" . $row_ian_conn["iastat"] . "</font>";
                } elseif ($row_ian_conn["iastat"] == "D") {
                    $t_ia_conn = "<font color='red'>" . $row_ian_conn["iastat"] . "</font>";
                }

                // Checkbox logic
                if ($row_ian_conn["iastat"] == "P") {
                    $t_iaval_conn = $row_ian_conn["docnum"] . "/" . $row_ian_conn["docyear"] . ",";
                    //$check = (strpos($listed_ia_conn, $t_iaval_conn) !== false) ? "checked='checked'" : "";
                    $check = (strpos((string) $listed_ia_conn, $t_iaval_conn) !== false) ? "checked='checked'" : "";

                    $ian_p_conn .= "<tr>
                        <td align='center'>
                            <input type='checkbox' name='cn_ia_" . $row_ian_conn["diary_no"] . "_" . $iancntr_conn . "' 
                            id='cn_ia_" . $row_ian_conn["diary_no"] . "_" . $iancntr_conn . "' 
                            value='" . $row_ian_conn["diary_no"] . "|#|" . $row_ian_conn["docnum"] . "/" . $row_ian_conn["docyear"] . "|#|" . str_replace("XTRA", "", $t_part_conn) . "' 
                            onClick='feed_rmrk_conn(\"" . $row_ian_conn["diary_no"] . "\");' " . $check . ">
                        </td>
                        <td align='center'>" . $row_ian_conn["docnum"] . "/" . $row_ian_conn["docyear"] . "</td>
                        <td align='left'>" . str_replace("XTRA", "", $t_part_conn) . "</td>
                        <td align='center'>" . date("d-m-Y", strtotime($row_ian_conn["ent_dt"])) . "</td>
                    </tr>";
                }
                $iancntr_conn++;
            }
        }

        if ($ian_p_conn != "") {
            $ian_p_conn .= "</table></div>";
        }

        return $ian_p_conn;
    }

    public function get_listing_dates($diaryno)
    {
        $output = "";

        $sql = "
            SELECT b.diary_no, b.conn_key, a.next_dt, b.list, a.main_supp_flag,
                CASE 
                    WHEN a.board_type = 'J' THEN 'Court' 
                    WHEN a.board_type = 'C' THEN 'Chamber' 
                    WHEN a.board_type = 'R' THEN 'Registrar' 
                END AS bt
            FROM heardt a
            LEFT JOIN conct b ON a.diary_no = b.diary_no
            WHERE a.diary_no = '" . $diaryno . "';
        ";

        $query = $this->db->query($sql);

        if ($query->getNumRows() > 0) {
            $row = $query->getRowArray();

            // Determine the readiness status
            $t_rnr = "";
            if ($row['main_supp_flag'] == 0) {
                $t_rnr = " <br><font color=green>(Ready)</font>";
            } elseif ($row['main_supp_flag'] == 3) {
                $t_rnr = " <br><font color=blue>(Not Ready)</font>";
            }

            // Check listing status
            $list = "";
            if ($row['list'] == 'N' && $row['diary_no'] != $row['conn_key']) {
                $list = "<br><font color='red'>[NOT TO BE LISTED]</font>";
            } elseif ($row['list'] == 'Y') {
                $list = "<br><font color='green'>[LISTED]</font>";
            }

            // User code from session
            $ucode = session()->get('login')['usercode'];

            // Query for display flag
            $sql_display = $this->db->table('master.case_status_flag')
                ->select('display_flag, always_allowed_users')
                ->where('date(to_date)::text', '0000-00-00')
                ->where('flag_name', 'tentative_listing_date')
                ->get()
                ->getRowArray();

            // Check display permissions
            if (!empty($sql_display) && ($sql_display['display_flag'] == 1 || in_array($ucode, explode(',', $sql_display['always_allowed_users'])))) {
                $output = date('d-m-Y', strtotime($row['next_dt'])) . "|#|" . $row['bt'] . $t_rnr . $list;
            } else {
                $output = "|#|" . $row['bt'] . $t_rnr . $list;
            }
        }

        return $output;
    }

    public function get_brd_remarks($dn)
    {
        $brdrem = "";

        // Query to get remarks
        $result = $this->db->table('brdrem')
            ->select('remark')
            ->where('diary_no', $dn)
            ->get()
            ->getRowArray(); // Fetch the first row as an associative array

        // Check if any row was returned
        if ($result) {
            $brdrem = $result['remark'];
        }

        return $brdrem;
    }

    public function get_main_details($dn, $fields)
    {
        $data_array = [];

        if ($dn != "") {
            // Use "*" if no specific fields are provided
            if ($fields == "") {
                $fields = "*";
            }

            // Create the query using Query Builder
            $query = $this->db->table('main')
                ->select($fields)
                ->where('diary_no', $dn)
                ->get();

            // Check if any rows were returned
            if ($query->getNumRows() > 0) {
                foreach ($query->getResultArray() as $row) {
                    foreach ($row as $key => $value) {
                        $data_array[$row['diary_no']][$key] = $value;
                    }
                }
            }
        }

        return $data_array;
    }

    public function getReport() {}
    // public function getReport(){}

}
