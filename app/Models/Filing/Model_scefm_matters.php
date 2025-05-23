<?php

namespace App\Models\Filing;

use CodeIgniter\Model;
class Model_scefm_matters extends Model
{
    public function get_all_matters_ib()
    {
        $officials=array(17,50,51);
        $officers=array(4,6,9);
        $logged_in_empid=session()->get('login')['empid'];
        $logged_in_usercode=session()->get('login')['usercode'];
        //print_r(session()->get('login'));
        $logged_in_usertype=session()->get('login')['usertype'];
        $query = $this->db->table('main m');
        $query->select('ec.id, casetype_id, short_description, m.diary_no, efiling_no, CONCAT(LEFT(CAST(m.diary_no AS TEXT), -4), \' / \', RIGHT(CAST(m.diary_no AS TEXT), 4)) AS diary_number');
        $query->select("TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') AS diary_date");
        $query->select("CONCAT(pet_name, ' Vs ', res_name) AS cause_title");
        $query->select("CASE WHEN ects.diary_update_by IS NOT NULL THEN 'Yes' ELSE 'No' END AS diary_modified");
        $query->select("CASE WHEN ects.party_update_by IS NOT NULL THEN 'Yes' ELSE 'No' END AS party_modified");
        $query->select('ref_special_category_filing_id, category_name, CONCAT(us.name, \'[\', empid, \']\') AS diary_user');

        $query->join('efiled_cases ec', 'm.diary_no = ec.diary_no AND efiled_type = \'new_case\' AND ec.display = \'Y\'');
        $query->join('master.casetype c', 'm.casetype_id = c.casecode');
        $query->join('master.users us', 'ec.created_by = us.usercode');

        $query->Join('public.efiled_cases_transfer_status ects', 'm.diary_no = ects.diary_no','left');
        $query->join('public.fil_trap ft', 'm.diary_no = ft.diary_no','left');
        $query->join('heardt h', 'm.diary_no = h.diary_no','left');
        $query->join('special_category_filing s', 'ec.diary_no = s.diary_no AND s.display = \'Y\'','left');
        $query->join('master.ref_special_category_filing r', 's.ref_special_category_filing_id = r.id AND r.display = \'Y\'','left');
        $query->where('(ects.updated_by IS NULL OR ects.updated_by = \'\')');
        $query->where('c_status', 'P');
        $query->where('(h.diary_no IS NULL OR DATE(m.diary_no_rec_date) >= \''.date('Y-m-d').'\')');
        //$query->where('DATE(ec.created_at) >', date('Y-m-d'));
        $query->where('ft.diary_no IS NULL');
        if(in_array($logged_in_usertype,$officials))
        {
            $query->where('ec.created_by', $logged_in_empid);
            $query->orWhere('ec.created_by', $logged_in_usercode);
        }
        
        $query->orderBy('diary_no_rec_date', 'DESC');
        $query = $query->get();
        $result = $query->getResultArray();
        return $result;

    }
    public function transfer_case($case_type,$diary_no)
    {
        $update_efiled_cases_transfer_status = [
            'updated_on'=>date("Y-m-d H:i:s"),
            'updated_by'=>$_SESSION['login']['usercode'],
            'updated_by_ip'=>getClientIP(),
        ];
        $usercode=$_SESSION['login']['usercode'];
        $loged_empid=$_SESSION['login']['empid'];
        $client_ip =getClientIP();
        $marked_to='';
        $crc = array(11, 12, 19, 25, 26, 9, 10, 39);

        if(in_array($case_type,$crc)) {
            $dacode=''; $section_id='';
                $result_lowerct = is_data_from_table('lowerct', ['diary_no' => $diary_no,'is_order_challenged' => 'Y','lw_display' => 'Y'],'lct_casetype,lct_caseno,lct_caseyear','R');
              if (empty($sql_challenged_case)){
                  $result_lowerct = is_data_from_table('lowerct_a', ['diary_no' => $diary_no,'is_order_challenged' => 'Y','lw_display' => 'Y'],'lct_casetype,lct_caseno,lct_caseyear','R');
              }
           if (!empty($result_lowerct)){
               $case_type = $result_lowerct['lct_casetype'];
               $case_number = $result_lowerct['lct_caseno'];
               $case_year = $result_lowerct['lct_caseyear'];
               if ($case_type != '' and $case_type != '0'  and $case_type != null and $case_type != 31) {
                   $result_get_dno = get_diary_case_type($case_type,$case_number,$case_year,'','A');
               }else if ($case_type != '' and $case_type != '0'  and $case_type != null) {
                   $result_get_dno = get_diary_case_type($case_type,$case_number,$case_year);
               }

               if (!empty($result_get_dno)) {
                   $lower_ct_diary_no = $result_get_dno;
                   if (($lower_ct_diary_no == 0 or $lower_ct_diary_no == '' or $lower_ct_diary_no == null) and $case_type == 31) {
                       $lower_ct_diary_no = $case_number . $case_year;
                   }
                   $result_da = is_data_from_table('main', ['diary_no' => $lower_ct_diary_no],'dacode,section_id','R');
                   if (empty($result_da)){
                       $result_da = is_data_from_table('main_a', ['diary_no' => $lower_ct_diary_no],'dacode,section_id','R');
                   }
                   if (!empty($result_da)){
                       $dacode = $result_da['dacode'];
                       $section_id = $result_da['section_id'];

                       if (($dacode == 0 or $dacode == '' or $dacode == null) && (!empty($section_id))) {
                           $section_bo = is_data_from_table('master.users', ['usertype' => 14,'display'=>'Y','section'=>$section_id],'usercode','R');
                          if (!empty($section_bo)){
                              $dacode = $section_bo['usercode'];
                          }

                       }
                   }

               }
           }
            if($dacode==0 or $dacode=='' or $dacode==null){
                $section_bo=$this->get_users_details_by_diary_no($diary_no,14);
                if (empty($section_bo)){
                    $section_bo=$this->get_users_details_by_diary_no($diary_no,14,'_a');
                }
                if (!empty($section_bo)) {
                    $dacode = $section_bo['usercode'];
                }
            }

            $is_efiled_cases_transfer_status=update('efiled_cases_transfer_status',$update_efiled_cases_transfer_status,['diary_no'=>$diary_no]);
            if ($is_efiled_cases_transfer_status) {
                if ($dacode==0 or $dacode=='' or $dacode==null) {
                    echo "1#Error! Da not found in the matter ";
                }else {
                    $update_marked_to_DA_main = [
                        'dacode'=>$dacode,
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by'=>$_SESSION['login']['usercode'],
                        'updated_by_ip'=>getClientIP(),
                    ];
                    $is_update_main=update('main',$update_marked_to_DA_main,['diary_no='=>$diary_no]);
                    if ($is_update_main) {
                        echo "2# Marked to DA Successfully";
                    }
                }
            }

        }else{
            $fil_no = $diary_no;
            $fil_type='E';
            $check_ava_row['to_userno']='';
            $check_ava_row['to_name']='';
            $assign_to='';
            $curdate=date("Y-m-d");

            $is_efiled_cases_transfer_status=update('efiled_cases_transfer_status',$update_efiled_cases_transfer_status,['diary_no'=>$diary_no]);
            if ($is_efiled_cases_transfer_status) {
                // to do from here
                $query_marking = is_data_from_table('mark_all_for_hc', ['display' => 'Y']);
                if (!empty($query_marking)) {
                    $result_check_qr = is_data_from_table('master.random_user_hc', ['ent_date' => $curdate], '*', 'R');
                    if (!empty($result_check_qr)) {
                        $result_emp = $result_check_qr['empid'];
                        $assign_to = explode('~', $result_check_qr['empid']);
                        $check_ava_row['to_userno'] = $assign_to[0];
                        $check_ava_row['to_name'] = $assign_to[1];
                        $delete_empid = delete('master.random_user_hc', ['empid' => $result_emp, 'ent_date' => $curdate]);
                    } else {
                        $check_if_EC_ava_res = $this->get_concat_empid_name_from_fil_trap_users(102);
                        if (!empty($check_if_EC_ava_res)) {
                            $empid = array();
                            foreach ($check_if_EC_ava_res as $value):
                                array_push($empid, $value['empid_name']);
                            endforeach;

                            shuffle($empid);

                            for ($i = 0; $i < sizeof($empid); $i++) {
                                $assign_to = explode('~', $empid[0]);
                                $check_ava_row['to_userno'] = $assign_to[0];
                                $check_ava_row['to_name'] = $assign_to[1];
                                if ($i > 0) {
                                    $insert_random_user = [
                                        'empid' => (!empty($empid[$i])) ? $empid[$i] : 0,
                                        'ent_date' => $curdate,
                                        'create_modify' => date("Y-m-d H:i:s"),
                                        'updated_by' => $_SESSION['login']['usercode'],
                                        'updated_by_ip' => getClientIP(),
                                    ];
                                    $is_insert_random_user_hc = insert('master.random_user_hc', $insert_random_user);

                                }
                            }
                        }
                    }

                } else {

                    $check_if_EC_ava = $this->get_concat_empid_name_from_fil_trap_users(102, $fil_type);
                    if (empty($check_if_EC_ava)) {
                        $user_availability = "";
                        if ($fil_type == 'P') {
                            $fil_type = 'E';
                            $user_availability = " [Counter-Filing Users not available, Marked to E-Filing User] ";
                        } else {
                            $fil_type = 'P';
                            $user_availability = " [E-Filing Users not available, Marked to Counter-Filing User] ";
                        }
                        $check_if_EC_ava = $this->get_concat_empid_name_from_fil_trap_users(102, $fil_type);
                    }

                    if (!empty($check_if_EC_ava)) {
                        $first_row = $check_if_EC_ava;
                        $utype = 'DE';
                        $check_ava_row = $this->check_if_SCR_available_with_fil_trap_seq($fil_type, 102, $utype);
                        if (!empty($check_ava_row)) {
                            if (($check_ava_row['to_usercode'] == NULL) || (empty($check_ava_row['to_usercode']))) {
                                $check_ava_row['to_userno'] = $first_row['empid'];
                                $check_ava_row['to_name'] = $first_row['name'];
                            }
                        } else {
                            if (empty($check_ava_row) || $check_ava_row['to_usercode'] == NULL) {
                                $check_ava_row['to_userno'] = $first_row['empid'];
                                $check_ava_row['to_name'] = $first_row['name'];
                            }

                        }

                    }
                }
                $select_for_deleted_filno = is_data_from_table('fil_trap', ['diary_no' => $fil_no]);
                if (!empty($select_for_deleted_filno)) {
                    $update_casemove_qur = delete('fil_trap', ['diary_no' => $fil_no]);
                }
                $select_for_deleted_filno_qur = is_data_from_table('fil_trap_his', ['diary_no' => $fil_no]);
                if (!empty($select_for_deleted_filno_qur)) {
                    $update_casemove = delete('fil_trap_his', ['diary_no' => $fil_no]);
                };
                $insert_data_fil_trap = [
                    'diary_no' => $fil_no,
                    'd_to_empid' => $check_ava_row['to_userno'],
                    'd_by_empid' => $_SESSION['login']['empid'],
                    'disp_dt' => date("Y-m-d H:i:s"),
                    'remarks' => 'FIL -> DE',

                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_by' => $_SESSION['login']['usercode'],
                    'updated_by_ip' => getClientIP(),
                    'r_by_empid'=> 0,
                    'other'=> 0
                ];
                $is_insert_fil_trap = insert('fil_trap', $insert_data_fil_trap);
                if ($is_insert_fil_trap) {
                $select_fil_trap_seq = is_data_from_table('fil_trap_seq', ['ddate' => $curdate, 'utype' => 'DE', 'user_type' => $fil_type]);
                if (empty($select_fil_trap_seq)) {
                    $insert_fil_trap_seq = [
                        'ddate' => date("Y-m-d"),
                        'utype' => 'DE',
                        'no' => (!empty($check_ava_row['to_userno'])) ? $check_ava_row['to_userno'] : 0,
                        'user_type' => (!empty($fil_type)) ? $fil_type : '',

                        'ctype' => 0,
                        'create_modify' => date("Y-m-d H:i:s"),
                        'updated_by' => $_SESSION['login']['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                    $is_insert_fil_trap_seq = insert('fil_trap_seq', $insert_fil_trap_seq);
                } else {
                    $update_fil_trap_seq = [
                        'no' => (!empty($check_ava_row['to_userno'])) ? $check_ava_row['to_userno'] : 0,
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => $_SESSION['login']['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];
                    $current_date = date("Y-m-d");
                    $utype = 'DE';
                    $is_update_fil_trap_refil_users = update('fil_trap_seq', $update_fil_trap_seq, ['ddate' => $current_date, 'utype' => $utype, 'user_type' => $fil_type]);
                }
            }
                echo "1# Transferred to ".$check_ava_row['to_userno'].'~'.$check_ava_row['to_name'];
            } else{
             echo "2#Error! Contact Computer Cell!";
            }
        }
    }



    public function get_concat_empid_name_from_fil_trap_users($usertype,$user_type='',$arrya='R'){
        $query = $this->db->table('fil_trap_users a');
            $query->select("CONCAT(b.empid, '~', b.name) as empid_name,b.empid,b.name,b.usercode");
            $query->join('master.users b', 'a.usercode = b.usercode');
            $query->where('a.usertype', $usertype);
            $query->where('a.display', 'Y');
            $query->where('b.display', 'Y');
            $query->where('b.attend', 'P');
            if (!empty($user_type)) { $query->where('a.user_type', $user_type); }
            $query->orderBy('empid');
           $result= $query->get();
           if ($arrya=='R'){
               return $result->getResultArray();
           }else{
               return $result->getRowArray();
           }

    }

    public function check_if_SCR_available_with_fil_trap_seq($fil_type,$usertype,$utype){
        $query = $this->db->table('fil_trap_users a')
            ->select('a.usercode as to_usercode, b.name as to_name, b.empid as to_userno, c.ddate, c.no as curno')
            ->join('master.users b', 'a.usercode = b.usercode')
            ->join('fil_trap_seq c', 'c.no < b.empid', 'left')
            ->where('a.usertype', $usertype)
            ->where('a.display', 'Y')
            ->where('b.display', 'Y')
            ->where('b.attend', 'P')
            ->where('a.user_type', $fil_type)
            ->where('c.user_type', $fil_type)
            ->where('c.utype', $utype)
            ->where('a.user_type', $fil_type)
            ->orderBy('to_userno ASC')
            ->get();
        return $result = $query->getRowArray();
    }
    public function get_users_details_by_diary_no($diary_no,$usertype='',$is_a='',$array='R'){

        $builder = $this->db->table('master.users u');
        $builder->select("u.*,us.section_name,us.description");
        $builder->JOIN('master.usersection us', 'u.section=us.id');
        $builder->JOIN("main$is_a m", 'us.id=m.section_id');
       if (!empty($usertype)) $builder->where('u.usertype', $usertype);
       if (!empty($diary_no)) $builder->where('m.diary_no', $diary_no);
        $builder->where('u.display', 'Y');
        $builder->where('us.display', 'Y');
        $query = $builder->get();
        if ($array=='R'){
            $result =$query->getRowArray();
        }else{
            $result = $query->getResultArray();
        }
        return $result;
    }


    public function show_datewise_matters($usercode,$from, $to)
	{
        $db = \Config\Database::connect();

		$sql= "select * from master.users where usercode=$usercode and display='Y'";
		$query = $this->db->query($sql);

		$logged_in_details=$query->getRowArray();
 
		$section = $logged_in_details['section'];
 
		$officials=array(17,50,51);
		$officers=array(14);
		$condition='';
		if(in_array($logged_in_details['usertype'],$officials))
		{
 

			$condition=" and m.dacode=".$usercode;
		}
		else if(in_array($logged_in_details['usertype'],$officers))
		{
 
			$users_da='';
			$mcode="select group_concat(usercode)  as x from master.users where section =".$logged_in_details['section']." and display='Y' ";
			$query2 = $this->db->query($mcode);
			$mcd=$query2->getResultArray();
			foreach($mcd as $result) {
				$users_da = $users_da.",".trim($result['x']);
			}
			$condition=' and m.dacode in('.ltrim($users_da,',').')';
		}

		if($section == '19')
		{
 
			$condition='';

		}
 

		
            $builder = $db->table('main m');

            $builder->select("
                ec.id,
                m.casetype_id,
                c.short_description,
                m.diary_no,
                ects.updated_on,
                ects.processed_by,
                ec.efiling_no,
               CONCAT(
                    SUBSTRING(CAST(m.diary_no AS TEXT), 1, LENGTH(CAST(m.diary_no AS TEXT)) - 4),
                    '/',
                    SUBSTRING(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 3, 4)
                ) AS diary_number,
                TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS diary_date,
                m.pet_name || ' Vs ' || m.res_name AS cause_title,
                CASE WHEN ects.updated_by IS NOT NULL THEN 'Yes' ELSE 'No' END AS diary_modified,
                s.ref_special_category_filing_id,
                r.category_name,
                us.name || '[' || us.empid || ']' AS diary_user,
                da.name || '[' || da.empid || ']' AS da,
                uss.section_name
            ");

            $builder->join('efiled_cases ec', "m.diary_no = ec.diary_no AND ec.efiled_type = 'new_case' AND ec.display = 'Y'");
            $builder->join('master.casetype c', 'm.casetype_id = c.casecode');
            $builder->join('efiled_cases_transfer_status ects', 'ects.diary_no = m.diary_no', 'left');
            $builder->join('fil_trap ft', 'ft.diary_no = m.diary_no', 'left');
            $builder->join('heardt h', 'm.diary_no = h.diary_no', 'left');
            $builder->join('special_category_filing s', "ec.diary_no = s.diary_no AND s.display = 'Y'", 'left');
            $builder->join('master.ref_special_category_filing r', "s.ref_special_category_filing_id = r.id AND r.display = 'Y'", 'left');
            $builder->join('master.users us', 'ec.created_by = us.usercode');
            $builder->join('master.users da', 'm.dacode = da.usercode', 'left');
            $builder->join('master.usersection uss', 'm.section_id = uss.id', 'left');

            // WHERE conditions
            $builder->where("ects.updated_by IS NOT NULL");
            $builder->where("ects.updated_by !=", '');
            $builder->where("DATE(ects.updated_on) >=", '2024-07-15');
            $builder->where("DATE(ects.updated_on) >=", $from);
            $builder->where("DATE(ects.updated_on) <=", $to);
            $builder->where("m.c_status", 'P');
            $builder->where("DATE(ec.created_at) >", '2023-07-29');
            $builder->where("ft.diary_no IS NULL");
            $builder->whereIn("m.casetype_id", [9, 10, 19, 20, 25, 26, 39]);

            // Extra condition passed as raw string (use cautiously)
            if (!empty($condition)) {
                $builder->where($condition);
            }

            return $builder->get()->getResultArray();


	}


    function show_sectionmatters($usercode)
	{
        $db = \Config\Database::connect();
        $condition='';
		$sql= "select * from master.users where usercode=$usercode and display='Y'";
		$query = $this->db->query($sql);
		$logged_in_details=$query->getRowArray();
        
		if($logged_in_details['section']!=19) {
			$officials = array(17, 50, 51);
			$officers = array(14);
			if (in_array($logged_in_details['usertype'], $officials)) {

				$condition = " and m.dacode=" . $usercode;
			} else if (in_array($logged_in_details['usertype'], $officers)) {
				$users_da = '';
				$mcode = "select group_concat(usercode)  as x from master.users where section =" . $logged_in_details['section'] . " and display='Y' ";
				$query2 = $this->db->query($mcode);
				$mcd = $query2->getResultArray();
				foreach ($mcd as $result) {
					$users_da = $users_da . "," . trim($result['x']);
				}
				$condition = ' and m.dacode in(' . ltrim($users_da, ',') . ')';
			}


 
    
        $builder = $db->table('main m');

            $builder->select("
                ec.id,
                m.casetype_id,
                c.short_description,
                m.diary_no,
                ects.updated_on,
                efiling_no,
                CONCAT(
                    SUBSTRING(CAST(m.diary_no AS TEXT), 1, LENGTH(CAST(m.diary_no AS TEXT)) - 4),
                    '/',
                    SUBSTRING(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 3, 4)
                ) AS diary_number,
                TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS diary_date,
                CONCAT(m.pet_name, ' Vs ', m.res_name) AS cause_title,
                CASE WHEN ects.updated_by IS NOT NULL THEN 'Yes' ELSE 'No' END AS diary_modified,
                s.ref_special_category_filing_id,
                r.category_name,
                CONCAT(us.name, '[', us.empid, ']') AS diary_user,
                CONCAT(da.name, '[', da.empid, ']') AS da,
                uss.section_name
            ");

            $builder->join('efiled_cases ec', 'm.diary_no = ec.diary_no AND ec.efiled_type = \'new_case\' AND ec.display = \'Y\'');
            $builder->join('master.casetype c', 'm.casetype_id = c.casecode');
            $builder->join('efiled_cases_transfer_status ects', 'ects.diary_no = m.diary_no', 'left');
            $builder->join('fil_trap ft', 'ft.diary_no = m.diary_no', 'left');
            $builder->join('heardt h', 'm.diary_no = h.diary_no', 'left');
            $builder->join('special_category_filing s', 'ec.diary_no = s.diary_no AND s.display = \'Y\'', 'left');
            $builder->join('master.ref_special_category_filing r', 's.ref_special_category_filing_id = r.id AND r.display = \'Y\'', 'left');
            $builder->join('master.users us', 'ec.created_by = us.usercode');
            $builder->join('master.users da', 'm.dacode = da.usercode', 'left');
            $builder->join('master.usersection uss', 'm.section_id = uss.id', 'left');

            $builder->where("(ects.updated_by IS NOT NULL OR ects.updated_by != '')", null, false);
            $builder->where('ects.processed_by IS NULL');
            $builder->where('m.c_status', 'P');
            $builder->where("DATE(ects.updated_on) >= '2024-07-15'", null, false);
            $builder->where("DATE(ec.created_at) > '2023-07-29'", null, false);
            $builder->where('ft.diary_no IS NULL', null, false);

            $builder->whereIn('m.casetype_id', [9, 10, 19, 20, 25, 26, 39]);

            // Custom condition if needed
            if ($condition) {
                $builder->where($condition, null, false);
            }

  
			 

			return $builder->get()->getResultArray();
		}else{
			return "section is IB";
		}
	}





}
