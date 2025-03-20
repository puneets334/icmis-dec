<?php

namespace App\Models\Entities;

use CodeIgniter\Model;

class IncompleteFDRModel extends Model
{
    protected $table = 'fil_trap';
    protected $primaryKey = 'uid';

    public function getAllIncompleteMatters()
    {
        $query = $this->db->query("
            SELECT DISTINCT 
                a.uid, 
                a.diary_no, 
                a.d_by_empid, 
                a.d_to_empid, 
                a.disp_dt, 
                a.remarks, 
                e.name AS d_by_name, 
                a.rece_dt, 
                CASE 
                    WHEN b.ack_id != 0 THEN 'Old E-filed' 
                    ELSE 'Counter filed' 
                END AS filing_type
          FROM fil_trap a 
          LEFT JOIN main b ON a.diary_no = b.diary_no
          LEFT JOIN master.users e ON e.empid = a.d_by_empid
          LEFT JOIN efiled_cases ne ON a.diary_no = ne.diary_no 
              AND ne.display = 'Y' 
              AND ne.efiled_type = 'new_case'
          WHERE 
              a.d_to_empid IN (
                  SELECT empid 
                  FROM master.users 
                  WHERE 
                      (usertype = 51 AND name ILIKE '%FILING DISPATCH RECEIVE%') 
                      OR 
                      (usertype = 59 AND name ILIKE '%ADVOCATE CHAMBER SUB-SECTION%')
              )
              AND (a.comp_dt IS NULL OR a.comp_dt = '1970-01-01 00:00:00') 
              AND b.c_status = 'P'
              AND ne.diary_no IS NULL 
          ORDER BY a.disp_dt DESC
      ");
        $result = $query->getResultArray();
        // pr($result);
        return $result;
    }


    public function getIncompleteMattersByDiaryNo($dno)
    {
        $query = $this->db->query("
              SELECT DISTINCT 
                  a.uid, 
                  a.diary_no, 
                  a.d_by_empid, 
                  a.d_to_empid, 
                  a.disp_dt, 
                  a.remarks, 
                  e.name AS d_by_name, 
                  -- a.pet_name, 
                  -- a.res_name, 
                  a.rece_dt, 
                  -- a.nature,
                  CASE 
                      WHEN b.ack_id != 0 THEN 'Old E-filed' 
                      ELSE 'Counter filed' 
                  END AS filing_type
              FROM fil_trap a 
              LEFT JOIN main b ON a.diary_no = b.diary_no
              LEFT JOIN master.users e ON e.empid = a.d_by_empid
              LEFT JOIN efiled_cases ne ON a.diary_no = ne.diary_no 
                  AND ne.display = 'Y' 
                  AND ne.efiled_type = 'new_case'
              WHERE 
                  a.diary_no = :dno: 
                  -- AND a.dyr = :dyr: 
                  AND a.d_to_empid IN (
                      SELECT empid 
                      FROM master.users 
                      WHERE 
                          (usertype = 51 AND name ILIKE '%FILING DISPATCH RECEIVE%') 
                          OR 
                          (usertype = 59 AND name ILIKE '%ADVOCATE CHAMBER SUB-SECTION%')
                  )
                  AND a.comp_dt = '0000-00-00 00:00:00' 
                  AND b.c_status = 'P'
                  AND ne.diary_no IS NULL 
              ORDER BY a.disp_dt DESC
          ", [
            'dno' => $dno
        ]);

        return $query->getResultArray();
    }


    public function receiveFile($id, $value)
    {
        // Assuming 'value' determines some status, adjust as needed
        $updateData = [
            'rece_dt' => date('Y-m-d H:i:s'),
            'remarks' => 'Received'
        ];

        $this->db->table('fil_trap')
            ->where('uid', $id)
            ->update($updateData);
    }


    public function receiveFDR($fdr, $fil_trap_type_row, $de, $emid, $id, $value)
    {

        if ($value == 'R') {
            $ext_rec = "";
            $ck_adv_rec = '';
            $token_no = 0;
            $token_val = "";
            if ($fdr == 1 || ($de == 1 && $fil_trap_type_row['usertype'] != 102) || $fdr == 2) {
                $ext_rec = ",other = CASE WHEN d_to_empid = $emid THEN 0 ELSE d_to_empid END";
            }

            $ck_adv_rec = " if(d_to_empid=29,d_to_empid,$emid)";

            if ($fdr == 1) {
                //echo  'current date'.date("Y-m-d");
                $chk_remark = "Select diary_no,remarks from fil_trap where uid='$id'";
                $chk_remark =  $this->db->query($chk_remark);
                $r_chk_remark =  $chk_remark->getRowArray();
                $r_remarks = $r_chk_remark['remarks'];
                $dno = $r_chk_remark['diary_no'];
            }

            if ($r_remarks == 'FDR -> AOR') {
                $query_to_update_refiling_attempt = "update main set refiling_attempt= NOW() where diary_no='$dno'";
                $query =$this->db->query($query_to_update_refiling_attempt);
            }


            $query = "UPDATE fil_trap SET rece_dt=NOW(),r_by_empid=$ck_adv_rec $ext_rec $token_val  WHERE uid=$id";
      
            $this->db->query($query);


            if ($fdr == 1) {
                if ($r_remarks == 'FDR -> AOR') {
                    $given_to = $this->allot_to_AOR($_REQUEST['id'], $_SESSION['icmic_empid'], $r_remarks, '1', $fil_type = null, $dno);
                    $given_to = explode('~', $given_to);
                    //         echo "<div style='text-align:center'>Completed Successfully And Automatically Allotted to: $given_to[1] [$given_to[0]]</div>";
                }
            }


            echo "Received Successfully";
        } else if ($value == 'C') {

            $chk_remark = "Select diary_no, remarks,r_by_empid from fil_trap where uid='$id'";
            $chk_remark =  $this->db->query($chk_remark);
            $r_chk_remark =  $chk_remark->getRowArray();
            $r_remarks = $r_chk_remark['remarks'];
            $dno = $r_chk_remark['diary_no'];

            if ($r_chk_remark['r_by_empid'] == '0') {
                $query = "UPDATE fil_trap SET rece_dt = NOW(),r_by_empid = CASE WHEN d_to_empid = 29 THEN d_to_empid ELSE 1 END WHERE uid = $id";
                $this->db->query($query);
            }
            if ($r_remarks == 'FDR -> AOR') {
                $query_to_update_refiling_attempt = "update main set refiling_attempt=NOW() where diary_no ='$dno'";
                $this->db->query($query_to_update_refiling_attempt);
            }
            if ($fdr == 1) {
                if ($r_remarks == 'FDR -> AOR') {
                    $this->allot_to_AOR($id, $emid, $r_remarks, '1', $fil_type = null, $dno);
                }
            }

            $sql_nature = "select count(*) from fil_trap  join main on fil_trap.diary_no=main.diary_no where uid =$id and ack_id <> 0";
            $rs_nature = $this->db->query($sql_nature);
            $rs_result = $rs_nature->getRowArray();

            foreach ($rs_result as $rw_scr) {
                $check = $rw_scr[0];
            }
            if ($check > 0) {
                $fil_type = 'E';
            } else {
                $fil_type = 'P';
            }

            if ($fdr == 1) {
                $chk_remark = "Select remarks from fil_trap where uid='$id'";
                $chk_remark =  $this->db->query($chk_remark);
                $r_chk_remark =  $chk_remark->getRowArray();
                $r_remarks =  $r_chk_remark['remarks'];
                $given_to = $this->allot_to_AOR($id, $emid, $r_remarks, $fil_trap_type_row, '2', $fil_type, $dno);
                $given_to = explode('~', $given_to ?? '');
            }

            if ($r_remarks != 'AOR -> FDR' && $r_remarks != 'FDR -> SCR' && $r_remarks != 'FDR -> AOR')
                echo "Completed Successfully ";
            if (isset($_REQUEST['nature']) && $_REQUEST['nature'] != 6 and $given_to[1] != '' and $given_to[1] != null) {
                echo " And Automatically Allotted to : $given_to[1] [$given_to[0]]";
            }
        }
    }

    function allot_to_AOR($uid, $ucode, $r_remarks, $usertype, $rec_comp, $fil_type, $dno = null)
    {

        $usr_nm = '';
        $ins_remk = '';
        $emid = $_SESSION['login']['empid'];
        if ($r_remarks == 'SCR -> FDR') {
            $usr_nm = " usertype=59 AND name LIKE '%ADVOCATE CHAMBER SUB-SECTION%'";
            $ins_remk = 'FDR -> AOR';
            $query_to_update_last_return_to_adv_main = "update main set last_return_to_adv=NOW() where diary_no ='$dno'";
            $this->db->query($query_to_update_last_return_to_adv_main);
        } else  if ($r_remarks == 'FDR -> AOR') {
            $usr_nm = " usertype=51 AND name LIKE '%FILING DISPATCH RECEIVE%'";
            $ins_remk = 'AOR -> FDR';
        }

        if ($r_remarks == 'SCR -> FDR' || $r_remarks == 'FDR -> AOR') {
            $query = "SELECT usercode, name to_name, empid to_userno FROM master.users WHERE $usr_nm";
            $check_if_CAT_ava = $this->db->query($query);
            $first_row = $check_if_CAT_ava->getRowArray();
            if (!empty($first_row)) {
                //$first_row = mysql_fetch_array($check_if_CAT_ava);
                $this->insert_into_history($uid);

                $update_then = "UPDATE fil_trap SET d_by_empid=r_by_empid,other='$emid',d_to_empid='$first_row[to_userno]',disp_dt=NOW(),
                    remarks='$ins_remk',r_by_empid=0,rece_dt=null,comp_dt=null,disp_dt_seq=null WHERE uid='$uid'";

                $this->db->query($update_then);
                if ($r_remarks == 'FDR -> AOR') {
                    //         $up_aor_fdr = "UPDATE fil_trap SET comp_dt=NOW(),other='$emid' WHERE uid=$uid";
                    $ck_adv_rec = "CASE WHEN d_to_empid = 29 THEN d_to_empid ELSE $emid END";
                    $ext_rec = ",other = CASE WHEN d_to_empid = $emid THEN 0 ELSE d_to_empid END";
                    $up_aor_fdr = "UPDATE fil_trap SET rece_dt=NOW(),r_by_empid=$ck_adv_rec $ext_rec WHERE uid=$uid";
                    $up_aor_fdr =  $this->db->query($up_aor_fdr);
                }
                if ($rec_comp == 2 && $r_remarks != 'SCR -> FDR') {
                    //echo "line 972";
                    $given_to = $this->allot_to_SCR($uid, $ucode, $usertype, $fil_type);
                    $given_to = explode('~', $given_to ?? '');
                    //   echo "Completed Successfully And Automatically Allotted to: $given_to[1] [$given_to[0]]";
                    // echo "Defective Matter Dispatched to AOR";
                } else
                    return $first_row['to_userno'] . '~' . $first_row['to_name'];
            }
        } else  if ($r_remarks == 'AOR -> FDR') {
            //echo "line 984";
            $given_to = $this->allot_to_SCR($uid, $ucode, $usertype, $fil_type);
            $given_to = explode('~', $given_to ?? '');
            // pr($given_to);
            if (count($given_to) == 3) {
                echo "Completed Successfully And Automatically Allotted to: $given_to[1] [$given_to[0]]" . $given_to[2];
            } else
                echo "Completed Successfully And Automatically Allotted to: $given_to[1] [$given_to[0]]";
            // echo "Defective Matter Dispatched to AOR";

        }
    }

    function insert_into_history($uid)
    {
        if ($uid > 0) {
            $sql = "SELECT * FROM fil_trap WHERE uid=$uid";
            $query = $this->db->query($sql);
            $row = $query->getRowArray();

            if (!empty($row)) {

                $comp_dt = (!empty($row['comp_dt'])) ? " AND comp_dt = $row[comp_dt] " : 'AND comp_dt is null';
                $query = "SELECT * FROM fil_trap_his WHERE diary_no='$row[diary_no]' AND d_by_empid='$row[d_by_empid]' AND d_to_empid='$row[d_to_empid]'
                AND disp_dt='$row[disp_dt]' AND r_by_empid='$row[r_by_empid]' AND rece_dt='$row[rece_dt]' $comp_dt
                AND disp_dt_seq='$row[disp_dt_seq]' AND other='$row[other]' AND scr_lower='$row[scr_lower]'";

                $chk_row = $this->db->query($query);
                $chk_row_result = $chk_row->getRowArray();
                if (!empty($chk_row_result)) {
                    $insert = "INSERT INTO fil_trap_his(diary_no,d_by_empid,d_to_empid,disp_dt,remarks,r_by_empid,rece_dt,comp_dt,disp_dt_seq,thisdt,other,scr_lower,token_no)
                    VALUES('$row[diary_no]','$row[d_by_empid]','$row[d_to_empid]','$row[disp_dt]','$row[remarks]','$row[r_by_empid]','$row[rece_dt]',
                        now(),'$row[disp_dt_seq]',NOW(),'$row[other]','$row[scr_lower]','$row[token_no]')";
                    $this->db->query($insert);
                }
            }
        }
    }

    function allot_to_SCR($uid, $ucode, $usertype, $fil_type)
    {
        $user_availability = "";
        $to_userno = 0;
        $to_name = '';
        $emid = $_SESSION['login']['empid'];
        $chk_j_c = 0;
        if ($ucode == '29' || $usertype == '108') {
            $available = '';
            $role = '';
            $display = '';

            $mark_to_inperson_scr = 0;
            $qr_inperson = "select * from fil_trap f join main a on f.diary_no=a.diary_no where a.pet_adv_id in(584,666) and  uid='$uid'";
            $rs_inperson = $this->db->query($qr_inperson);
            if (!empty($rs_inperson->getRowArray())) {
                $qr_inperson_scr = "select u.usercode,u.empid,u.name from specific_role s join users u on s.usercode=u.usercode where flag='P' and u.display='Y' and s.display='Y' limit 1";
                $rs_inperson_scr = $this->db->query($qr_inperson_scr);
                if (!empty($rs_inperson_scr->getRowArray())) {
                    $mark_to_inperson_scr = 1;
                    $inperson = $rs_inperson_scr->getRowArray();
                    $inperson_scr = $inperson[0];
                    $r_get_scr_usr = $to_userno = $inperson[1];
                    $r_user_name = $inperson[2];
                }
            }
            if ($mark_to_inperson_scr == 0) {
                $get_diary = "Select diary_no from fil_trap where uid='$uid'";
                $get_diary = $this->db->query($get_diary);
                $r_get_diary = $get_diary->getRowArray();

                $refil_user_flag = 0;

                $sql = "SELECT d_to_empid FROM (SELECT DISTINCT d_to_empid FROM ( SELECT d_to_empid, uid FROM  fil_trap_his WHERE diary_no = '$r_get_diary[diary_no]' AND (remarks = 'DE -> SCR' OR remarks = 'FDR -> SCR')) a JOIN master.users u ON a.d_to_empid = u.empid WHERE u.display = 'Y' AND u.section = 19) distinct_rows ORDER BY (SELECT uid FROM fil_trap_his WHERE d_to_empid = distinct_rows.d_to_empid LIMIT 1) ASC LIMIT 2";

                $query = $this->db->query($sql);
                $get_scr_usr = $query->getNumRows();

                if (!empty($get_scr_usr)) {
                    $j = $get_scr_usr;
                    for ($i = 0; $i < $j; $i++) {
                        $scr_usr = $query->getRowArray();
                        $user_avail = "SELECT b.attend,a.usertype,a.display,b.name FROM fil_trap_users a JOIN master.users b ON a.usercode=b.usercode
                        WHERE   empid='$scr_usr[d_to_empid]'  order by a.ent_dt desc limit 1";

                        $query1 = $this->db->query($user_avail);
                        $user_res = $query1->getRowArray();
                        if (!empty($user_res)) {
                            $user_avail = $user_res;
                            $available = $user_avail['attend'];
                            $role = $user_avail['usertype'];
                            $display = $user_avail['display'];
                        }
                        if ($available == 'P' and $role == '103' and $display == 'Y') {
                            $r_get_scr_usr = $to_userno = $scr_usr['d_to_empid'];
                            $r_user_name = $user_avail['name'];
                            $refil_user_flag = 1;
                            break;
                        }
                    }
                }

                //fresh scrutiny user or first refiling user not available then sequential refiling user allotment
                //starts here

                if ($refil_user_flag == 0) {
                    $sql_check_if_SCR_ava = "SELECT a.usercode,b.name,empid FROM fil_trap_users a JOIN master.users b ON a.usercode=b.usercode
                WHERE a.usertype=103 AND a.display='Y' AND b.display='Y' AND attend='P' AND user_type='P' AND user_type='$fil_type' ORDER BY empid";

                    $query_check_if_SCR_ava =$this->db->query($sql_check_if_SCR_ava);

                    $check_if_SCR_ava = $query_check_if_SCR_ava->getRowArray();

                    if (empty($check_if_SCR_ava)) {

                        $user_availability = "";
                        if ($fil_type == 'P') {
                            $fil_type = 'E';
                            $user_availability = " [Counter-Filing Users not available, Marked to E-Filing User] ";
                        } else {
                            $fil_type = 'P';
                            $user_availability = " [E-Filing Users not available, Marked to Counter-Filing User] ";
                        }
                        $check_ava_sql = "SELECT a.usercode,b.name,empid FROM fil_trap_users a JOIN master.users b ON a.usercode=b.usercode
                                     WHERE a.usertype=103 AND a.display='Y' AND b.display='Y' AND attend='P' and a.user_type='$fil_type' 
                                     ORDER BY empid";
                        $query_check_if_SCR_ava = $this->db->query($check_ava_sql);
                        $check_if_SCR_ava = $query_check_if_SCR_ava->getRowArray();
                        

                    }
                 

                    if (!empty($check_if_SCR_ava)) {
                     
                        $first_row = $check_if_SCR_ava;

                        $next_user = '';
                        $check_ava_q = "SELECT a.usercode to_usercode,b.name to_name,empid to_userno,ddate,c.no curno
                                        FROM fil_trap_users a
                                        JOIN master.users b ON a.usercode=b.usercode
                                        LEFT JOIN fil_trap_refil_users c ON c.no < empid
                                        WHERE a.usertype=103 AND a.display='Y' AND b.display='Y' AND attend='P'
                                        AND utype='SCR' AND ddate=(select ddate from fil_trap_seq where utype='SCR' and user_type='$fil_type' order by ddate desc limit 1)
                                        ORDER BY to_userno";
                                        
                        $check_ava_rs = $this->db->query($check_ava_q);
                        $check_ava_row = $check_ava_rs->getRowArray();

                        if (!empty($check_ava_row)) {
                            $next_user = $check_ava_row['to_userno'];
                            $to_userno = $check_ava_row['to_userno'];
                            $to_name = $check_ava_row['to_name'];
                        }


                        if (empty($check_ava_row) || $check_ava_row['to_usercode'] == NULL) {
                            $to_userno = $first_row['empid'];
                            $to_name = $first_row['name'];

                            $next_user = $to_userno;
                        }

                        $utype = 'SCR';

                        $check_sql = "SELECT id FROM fil_trap_refil_users WHERE ddate = CURRENT_DATE AND utype='$utype' ";
                        $check_query = $this->db->query($check_sql);
                        $check = $check_query->getRowArray();

                        if (empty($check) == 0)
                            $query = "INSERT INTO fil_trap_refil_users(ddate,utype,no) VALUES(CURRENT_DATE,'$utype',$to_userno)";
                        else
                            $query = "UPDATE fil_trap_refil_users SET no=$to_userno WHERE ddate=CURRENT_DATE AND utype='$utype'";
                            $this->db->query($query);
                    }
                    $r_get_scr_usr = $to_userno;
                    $r_user_name = $to_name;
                }
            }
            //ends here

        } else  if ($ucode == '9796') {

            $diary_no = "SELECT diary_no FROM fil_trap WHERE uid=$uid";
            $diary_no = $this->db->query($diary_no);
            $diary_no = $diary_no->getRowArray()['diary_no'];
          
            $chk_list_bef = "Select board_type from heardt where diary_no ='$diary_no'";
            $chk_list_bef =  $this->db->query($chk_list_bef);
            $r_chk_list_bef =  $chk_list_bef->getRowArray()['board_type'];

            $get_status = "Select c_status,pet_adv_id from main where diary_no='$diary_no'";
            $get_status =  $this->db->query($get_status);
            $r_get_status =  $get_status->getRowArray()['c_status'];
            $r_pet_adv_id = $get_status->getRowArray()['pet_adv_id'];

            if (($r_chk_list_bef == 'J' || $r_pet_adv_id == '584') && $r_get_status == 'P') {

                $check_if_SCR_ava = "SELECT a.usercode,b.name,empid FROM fil_trap_users a JOIN users b ON a.usercode=b.usercode
                                    WHERE a.usertype=107 AND a.display='Y' AND b.display='Y' AND attend='P' and user_type='$fil_type'  
                                    ORDER BY empid";

                $check_if_SCR_ava_query = $this->db->query($check_if_SCR_ava);
                $first_row = $check_if_SCR_ava_query->getRowArray();

                if (!empty($first_row)) {
                    $check_ava_q = "SELECT a.usercode to_usercode,b.name to_name,empid to_userno,ddate,c.no curno
                                    FROM fil_trap_users a
                                    JOIN master.users b ON a.usercode=b.usercode
                                    LEFT JOIN fil_trap_seq c ON c.no < empid
                                    WHERE a.usertype=107 AND a.display='Y' AND b.display='Y' AND attend='P'
                                    AND utype='IB-Ex' AND ddate=CURDATE()  and a.user_type='$fil_type' and  c.user_type='$fil_type'
                                    ORDER BY to_userno";

                    $check_ava_rs =  $this->db->query($check_ava_q);
                    $check_ava_row = $check_ava_rs->getRowArray();


                    if (!empty($check_ava_rs) || $check_ava_row['to_usercode'] == NULL) {
                        $to_userno = $first_row['empid'];
                        $to_name = $first_row['name'];
                    }

                }
            } else {
                $chk_j_c = 1;

                $check_ava_q = "Select empid,name,section_name from main a
                                join master.users b on a.dacode=b.usercode
                                left join master.usersection c on c.id=b.section where diary_no='$diary_no' and b.display='Y'";

                $check_ava_rs = $this->db->query($check_ava_q);
                $check_ava_row = $check_ava_rs->getRowArray();

                if (empty($check_ava_row)) {

                    $check_ava_q = "select empid,name,section_name from main a join master.users b on a.section_id=b.section and b.usertype='14'
                              left join master.usersection c on c.id=a.section_id where diary_no='$diary_no' and b.display='Y'";

                    $check_ava_rs = $this->db->query($check_ava_q);
                }
               
                $to_userno = $check_ava_row['empid'];
                if ($check_ava_row['name'] == '')
                    $to_name = '(' . $check_ava_row['section_name'] . ')';
                else
                    $to_name = $check_ava_row['name'] . '(' . $check_ava_row['section_name'] . ')';
            }
        } else {

            $mark_to_inperson_scr = 0;
            $qr_inperson = "select * from fil_trap f join main a on f.diary_no=a.diary_no where a.pet_adv_id in(584,666) and  uid='$uid'";
            $rs_inperson_query = $this->db->query($qr_inperson);
            $rs_inperson = $rs_inperson_query->getRowArray();

            if (!empty($rs_inperson)) {

                $qr_inperson_scr = "select u.usercode,u.empid,u.name from master.specific_role s join master.users u on s.usercode=u.usercode where flag='P' and u.display='Y' and s.display='Y' limit 1";

                $rs_inperson_scr = $this->db->query($qr_inperson_scr);
                $inperson = $rs_inperson_scr->getRowArray();

                if (!empty($inperson)) {
                    $mark_to_inperson_scr = 1;
                    $inperson_scr = $inperson['usercode'];
                    $to_userno = $inperson['empid'];
                    $to_name = $inperson['name'];
                }
            }

            if ($mark_to_inperson_scr == 0) {
                $chk_lc_usr = 0;
                $today = date('Y-m-d');
                $check_marking = "select * from mark_all_for_scrutiny where display='Y'";
                $check_marking_rs_query =  $this->db->query($check_marking);
                $check_marking_rs = $check_marking_rs_query->getRowArray();

                if (!empty($check_marking_rs)) {

                    $assign_to = '';
                    $check_qr = "select empid from random_user where ent_date='$today' limit 1";

                    $check_arr = $this->db->query($check_qr);
                    $row = $check_arr->getRowArray();

                    if (!empty($row)) {
                        $assign_to = explode('~', $row['empid'] ?? '');
                        $to_userno = $assign_to[0];
                        $to_name = $assign_to[1];
                        $delete_empid = "delete from  random_user where empid='$row[0]' and ent_date='$today'";
                       $this->db->query($delete_empid);

                    } else {
                       
                        $check_if_SCR_ava = "SELECT concat(empid,'~',name) empid FROM fil_trap_users a JOIN master.users b ON a.usercode=b.usercode left join master.specific_role s on a.usercode=s.usercode and s.display='Y' and s.flag='P' WHERE s.id is null and a.usertype=103 AND a.display='Y' AND b.display='Y' AND attend='P'  ORDER BY empid";

                        $check_if_SCR_ava =$this->db->query($check_if_SCR_ava);
                        $row = $check_if_SCR_ava->getResultArray();

                        if (!empty($row)) {
                            $empid = array();
                            foreach ($row as $data) {
                                array_push($empid, $data['empid']);
                            }
                            shuffle($empid);
                            for ($i = 0; $i < sizeof($empid); $i++) {
                                $assign_to = explode('~', $empid[0]);
                                $to_userno = $assign_to[0];
                                $to_name = $assign_to[1];
                                if ($i > 0) {
                                    $query = "INSERT INTO random_user(empid,ent_date) VALUES('$empid[$i]','$today' )";
                                    $this->db->query($query);
                                }
                            }
                        }
                    }
                } else {

                   
                    $check_if_SCR_ava = "SELECT a.usercode,b.name,empid FROM fil_trap_users a JOIN master.users b ON a.usercode=b.usercode left join master.specific_role s on a.usercode=s.usercode and s.display='Y' and s.flag='P' WHERE s.id is null and a.usertype=103 AND a.display='Y' AND b.display='Y' AND attend='P' and a.user_type='$fil_type' ORDER BY empid";

                    $check_if_SCR_ava = $this->db->query($check_if_SCR_ava);
                    $first_row = $check_if_SCR_ava->getRowArray();

                    if (empty($first_row)) {
                       
                        $user_availability = "";
                        if ($fil_type == 'P') {
                            $fil_type = 'E';
                            $user_availability = " [Counter-Filing Users not available, Marked to E-Filing User] ";
                        } else {
                            $fil_type = 'P';
                            $user_availability = " [E-Filing Users not available, Marked to Counter-Filing User] ";
                        }
                       
                        $check_ava = "SELECT a.usercode,b.name,empid FROM fil_trap_users a JOIN master.users b ON a.usercode=b.usercode left join master.specific_role s on a.usercode=s.usercode and s.display='Y' and s.flag='P' 
                        WHERE s.id is null and a.usertype=103 AND a.display='Y' AND b.display='Y' AND attend='P' and a.user_type='$fil_type' ORDER BY empid";
                        $check_if_SCR_ava = $this->db->query($check_ava);
                        $first_row = $check_if_SCR_ava->getRowArray();
                    }
                    if (!empty($first_row)) {
                       
                        $next_user = '';
                        

                        $check_ava_q = "SELECT a.usercode to_usercode,b.name to_name,empid to_userno,ddate,c.no curno
		        FROM fil_trap_users a
		        JOIN users b ON a.usercode=b.usercode
                left join specific_role s on a.usercode=s.usercode and s.display='Y' and s.flag='P'
		        LEFT JOIN fil_trap_seq c ON c.no < empid
		        WHERE  s.id is null and a.usertype=103 AND a.display='Y' AND b.display='Y' AND attend='P' and  a.user_type='$fil_type' and  c.user_type='$fil_type'
		        AND utype='SCR' AND ddate=(select ddate from fil_trap_seq where utype='SCR' and user_type='$fil_type'   order by ddate desc limit 1)
                ORDER BY to_userno";
                        $check_ava_rs = $this->db->query($check_ava_q);
                        $check_ava_row = $check_ava_rs->getRowArray();

                        if (!empty($check_ava_row) > 0) {
                            //$next_user = $check_ava_row['to_usercode'];
                            $next_user = $check_ava_row['to_userno'];
                            $to_userno = $check_ava_row['to_userno'];
                            $to_name = $check_ava_row['to_name'];
                        }

                        //if($check_ava_row['to_usercode'] == NULL){
                        if (empty($check_ava_row) == 0 || $check_ava_row['to_usercode'] == NULL) {
                            $to_userno = $first_row['empid'];
                            $to_name = $first_row['name'];
                            $next_user = $to_userno;
                        }
                    }
                }

                if ($ucode != '29' && $usertype != '108' && $chk_j_c == 0) {
                    $utype = '';
                    if ($ucode == '9796') {
                        $utype = 'IB-Ex';
                    } else {
                        $utype = 'SCR';
                    }
                    if ($chk_lc_usr == 0 || ($to_userno == $next_user && $chk_lc_usr == 1)) {
                        $check = "SELECT id FROM fil_trap_seq WHERE ddate=CURDATE() AND utype='$utype' and user_type='$fil_type'";

                        $check_query = $this->db->query($check);
                        $check = $check_query->getRowArray();

                        if (empty($check))
                            $query = "INSERT INTO fil_trap_seq(ddate,utype,no,user_type) VALUES(CURDATE(),'$utype',$to_userno,'$fil_type')";
                        else
                            // $query = "UPDATE fil_trap_seq SET no=$check_ava_row[to_userno],user_type='$fil_type' WHERE ddate=CURDATE() AND utype='$utype'";
                            $query = "UPDATE fil_trap_seq SET no=$to_userno WHERE ddate=CURDATE() AND utype='$utype' and user_type='$fil_type'";

                        $this->db->query($query);
                    }
                }
            }
        }
        $this->insert_into_history($uid);
        $remarks = '';
        if ($emid == '29' || $usertype == '108')
            $remarks = "FDR -> SCR";
        else if ($emid == '9796') {
            if ($chk_j_c == 0)
                $remarks = "SCN -> IB-Ex";
            else if ($chk_j_c == 1)
                $remarks = "SCN -> DA";
        } else
            $remarks = "DE -> SCR";

        $update_then = "UPDATE fil_trap SET d_by_empid='$emid',d_to_empid='$to_userno',disp_dt=NOW(),
        remarks='$remarks',r_by_empid='$to_userno',rece_dt=now(),comp_dt=null,disp_dt_seq=null,
            other='0'
        WHERE uid='$uid'";
        $this->db->query($update_then);

        $sql_diary_no = "SELECT CONCAT( SUBSTRING(diary_no::TEXT, 1, LENGTH(diary_no::TEXT) - 4), '/', SUBSTRING(diary_no::TEXT, -4)) AS diary_no FROM fil_trap WHERE uid='$uid'";
        $query2 = $this->db->query($sql_diary_no);
        $rs_diary_no = $query2->getResultArray();
        foreach ($rs_diary_no as $rw_diary_no ) {
            $f_dno = $rw_diary_no['diary_no'];
        }

        $sql_mobile = "select mobile_no from master.users where empid= " . $to_userno;
        $query3 = $this->db->query($sql_mobile);
        $rs_mobile = $query3->getResultArray();
        foreach ($rs_mobile as $rw_mob ) {
            $mobile_no = $rw_mob['mobile_no'];
        }
        $message = "Diary No: " . $f_dno . " alloted to you for Scrutiny/Rechecking" . "- Supreme Court of India";
        if ($mobile_no == 0) {
            echo " SMS could not be sent to " . $to_userno . "-" . $to_name  . " as no mobile number is updated in ICMIS";
        } else {
            $_REQUEST['mob'] = $mobile_no;
            $_REQUEST['sms_status'] = 'scrutiny';
            $_REQUEST['msg'] = $message;

            // include('../sms/send_sms.php'); --integrate the Api after Staging said by Manish Sir
           
        }
        /* END OF THE CODE */
        if ($ucode == '29' || $usertype == '108') {
            if ($user_availability != '')
                return $r_get_scr_usr . '~' . $r_user_name . '~' . $user_availability;
            else
                return $r_get_scr_usr . '~' . $r_user_name;
        } else {
            if ($user_availability != '')
                return $to_userno . '~' . $to_name . '~' . $user_availability;
            else
                return $to_userno . '~' . $to_name;
        }
    }

    public function get_data($condition1)
    {
        $select_q = "SELECT distinct a.uid,a.diary_no,d_by_empid,d_to_empid,disp_dt,remarks,e.name d_by_name,pet_name,res_name,rece_dt,nature,
       
        case when b.ack_id!='0' then 'Old E-filed' else 'Counter filed' end as filing_type
            FROM fil_trap a 
            LEFT JOIN main b ON a.diary_no = b.diary_no
            LEFT JOIN master.users e ON e.empid = a.d_by_empid
           
            LEFT JOIN efiled_cases ne on a.diary_no=ne.diary_no and ne.display='Y' and ne.efiled_type='new_case'
                    
            WHERE $condition1 d_to_empid in (SELECT empid FROM master.users WHERE ((usertype=51 AND name LIKE '%FILING DISPATCH RECEIVE%') or (usertype=59 AND name LIKE '%ADVOCATE CHAMBER SUB-SECTION%'))) AND comp_dt is null and b.c_status='P'
          
         /*and ne.diary_no is null */
            ORDER BY disp_dt desc";
        // pr($select_q);
        $result = $this->db->query($select_q);
        return $result->getResultArray();
    }
}
