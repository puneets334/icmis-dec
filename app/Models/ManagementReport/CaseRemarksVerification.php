<?php

namespace App\Models\ManagementReport;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class CaseRemarksVerification extends Model
{
    public function verification_get_data($usertype, $ucode, $mdacode, $verify_status, $to_dt, $from_dt, $if_bo)
    {
        if ($usertype == '14' and $ucode != 3564 and $ucode != 722 and $ucode != 184 and $ucode != 1182) {
            $sq_u = "SELECT GROUP_CONCAT(u2.usercode) as allda FROM master.users u LEFT JOIN master.users u2 ON u2.section = u.section WHERE u.display = 'Y' AND u.usercode = '$ucode' group by u2.section";
            $re_u = $this->db->query($sq_u);
            $ro_u =  $re_u->getResultArray();
            $all_da = $ro_u['allda'];
            $mdacode = "AND m.dacode IN ($all_da)";
            $if_bo = true;
        } else if (($usertype == '17' or $usertype == '50' or $usertype == '51') and ($ucode != 3564 and $ucode != 722 and $ucode != 1182 and $ucode != 184)) {
            $mdacode = "AND m.dacode = '$ucode'";
        } else if ($ucode == 1) {
            $mdacode = "";
            $if_bo = true;
        } else {
            $mdacode = "";
        }
        if ($verify_status == 0) {
            $list_print_flag = "(Verified/Not Verified )";
            $left_join_verify = "LEFT JOIN case_remarks_verification tt ON tt.diary_no = c.diary_no 
            and tt.cl_date = c.cl_date AND tt.display = 'Y' ";
            $left_join_verify_whr = "  ";
        } else if ($verify_status == 1) {
            $list_print_flag = "(Not Verified Cases)";
            $left_join_verify = "LEFT JOIN case_remarks_verification tt ON tt.diary_no = c.diary_no AND date(tt.cl_date) = date(c.cl_date) 
            AND tt.approved_on > c.e_date AND tt.display = 'Y' ";
            $left_join_verify_whr = " tt.diary_no IS NULL AND ";
        } else if ($verify_status == 2) {

            $list_print_flag = "(Verified Cases)";
            $left_join_verify = "LEFT JOIN case_remarks_verification tt ON tt.diary_no = c.diary_no AND c.cl_date = tt.cl_date AND tt.display = 'Y'
             AND tt.approved_on > c.e_date";
            $left_join_verify_whr = " tt.diary_no IS NOT NULL AND ";
        }

        $sql = "SELECT tt.status,(select name from master.users where usercode=tt.approved_by) as approved_by_user,tt.rejection_remark,
        tt.approved_on,h.next_dt, c.cl_date, m.diary_no,concat(m.reg_no_display, ' @ ', m.diary_no) as Case_No,
        concat(m.pet_name,' Vs. ',m.res_name) Cause_Title, 
        TO_CHAR(c.cl_date, 'DD-MM-YYYY')  Listing_On,
        COALESCE(
            (SELECT STRING_AGG(jname, ', ' ORDER BY judge_seniority) FROM master.judge WHERE jcode::text = ANY (string_to_array(c.jcodes, ','))),
            ''
        ) AS Heard_by, c.jcodes, c.clno
                FROM case_remarks_multiple c
                INNER JOIN (SELECT c.diary_no, MAX(cl_date) AS max_cl_dt FROM case_remarks_multiple c GROUP BY c.diary_no) AS c1 ON 
                c1.diary_no = c.diary_no AND c1.max_cl_dt = c.cl_date
                LEFT JOIN heardt h on h.diary_no = c.diary_no and h.next_dt >= CURRENT_DATE and h.clno > 0 and h.brd_slno > 0
                inner join master.users u on u.usercode = c.uid
                inner join main m on m.diary_no = c.diary_no
                $left_join_verify
                where $left_join_verify_whr date(c.e_date) between '$from_dt' and '$to_dt' and u.section not in (11,62,73) 
                and u.usertype in (17,51,50) $mdacode
                group by  c.diary_no ,tt.status,tt.approved_by,tt.rejection_remark,tt.approved_on,h.next_dt, c.cl_date, m.diary_no,c.jcodes,c.clno order by m.diary_no_rec_date";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

    public function get_case_remarks($dn, $cldate, $jcodes, $clno)
    {
        $sql_cr = "select 
                        name, 
                        h.cat_head_id, 
                        c.cl_date, 
                        c.jcodes, 
                        c.status, 
                        STRING_AGG(
                            h.head || CASE WHEN c.head_content != '' THEN ' [Rem:' || c.head_content || ']' ELSE '' END, 
                            ', '
                        ) AS crem, 
                        TO_CHAR(e_date, 'DD/MM/YYYY HH24:MI') AS edate, 
                        STRING_AGG(
                            c.r_head || '|' || c.head_content || '^^', 
                            ''
                        ) AS caseval, 
                        c.mainhead, 
                        c.clno AS caseval, 
                        c.mainhead, 
                        c.clno 
                        FROM 
                        case_remarks_multiple c 
                        inner join master.case_remarks_head h on c.r_head = h.sno 
                        join master.users on c.uid = users.usercode 
                        WHERE 
                        c.diary_no = " . $dn . " 
                        AND c.cl_date = '" . $cldate . "' 
                        AND c.jcodes = '" . $jcodes . "' 
                        AND c.clno = '" . $clno . "' 
                        GROUP BY 
                        h.priority, 
                        name, 
                        h.cat_head_id, 
                        c.cl_date, 
                        c.jcodes, 
                        c.status, 
                        c.mainhead, 
                        c.clno, 
                        e_date 
                        ORDER BY 
                        h.priority
                        ";

        $result_cr = $this->db->query($sql_cr);
        $num_rows = $result_cr->getNumRows();

        $cval = "";
        if ($num_rows > 0) {
            $row_cr = $result_cr->getRowArray();
            $crem = $row_cr['crem'];
        } else {
            $crem = '';
        }


        $sql_his = "
                    select CASE WHEN head_content <> '' then concat(head, '[Rem:', head_content, ']') else head end as remark, 
                    concat(name, '[', section_name, ']') as uname, 
                    TO_CHAR(e_date, 'DD/MM/YYYY HH24:MI') AS edate 
                    from 
                    case_remarks_multiple_history cr 
                    join master.case_remarks_head ch on cr.r_head = ch.sno 
                    join master.users on cr.uid = users.usercode 
                    join master.usersection on users.section = usersection.id 
                    and fil_no :: int = " . $dn . " 
                    and cl_date = '" . $cldate . "' 
                    order by 
                    e_date desc";

        $rs_his = $this->db->query($sql_his);
        $sdf =  $rs_his->getNumRows();


        if ($sdf > 0) {
            $row_his = $rs_his->getResultArray();
        } else {
            $row_his = [];
        }

        $sno = 1;
        $cr_his = "";
        foreach ($row_his as $row_his) {
            $h_remark = $row_his['remark'];
            $h_uname = $row_his['uname'];
            $h_edate = $row_his['edate'];
            $cr_his = $cr_his . $h_remark . " by " . $h_uname . " on " . $h_edate . "\n";
            $sno++;
        }
        if ($row_cr['name'] != '') {
            $row_cr1 = '(' . $row_cr['name'] . ') on ';
        }
        return $crem . "\n " . $row_cr1 . "" . $row_cr['edate'] . "?" . $cr_his . "?" . $sdf . "?" . $row_cr['name'];
    }

    public function ListedOnROP($listing_on, $diary_no)
    {
        $sql = "SELECT
                diary_no,jm AS pdfname,dated AS orderdate
                FROM
                (SELECT
                o.diary_no diary_no,
                o.pdfname jm,
               TO_CHAR(o.orderdate, 'DD-MM-YYYY') dated,
                CASE
                WHEN o.type = 'O' THEN 'ROP'
                WHEN o.type = 'J' THEN 'Judgement'
                END AS jo
                FROM
                ordernet o
                WHERE
                o.diary_no = " . $diary_no . " and o.orderdate = '" . date('Y-m-d', strtotime($listing_on)) . "') tbl1 WHERE jo='ROP'
                ORDER BY tbl1.dated DESC";
        $rs_his = $this->db->query($sql);
        $rs_his->getNumRows();
        $result = $rs_his->getResultArray();
        return $result;
    }

    public function response_case_remarks_verification_store($ucode, $dno, $cl_date, $rremark, $rejection_remark)
    {
        $builder = $this->db->table('case_remarks_verification');
        $data = [
            'diary_no' => $dno,
            'cl_date' => $cl_date,
            'status' => $rremark,
            'approved_by' => $ucode,
            'rejection_remark' => $rejection_remark
        ];

        if ($builder->insert($data)) {
            return $this->db->affectedRows();
        } else {
            $error = $this->db->error();
            return "Error: " . $error['message'];
        }
    }

    public function get_section_name($empid)
    {
        $sql = "SELECT section_name FROM master.user_sec_map a LEFT JOIN master.usersection b ON usec=b.id WHERE empid=$empid AND a.display='Y' AND b.display='Y'";
        $result = $this->db->query($sql);
        return $result->getNumRows();
    }

    public function workdone_verify_get_data($usertype, $section, $chk_user_sec_map, $date, $empid)
    {

        if ($usertype != 1) {
            $yoursection = "SELECT usec FROM master.user_sec_map a LEFT JOIN master.usersection b ON usec=b.id WHERE empid=$empid AND a.display='Y' AND b.display='Y'";
            $result = $this->db->query($yoursection);
            $rowCount = $result->getNumRows();

            if ($rowCount > 0) {
                $sec = '';
                foreach ($result->getResultArray() as $row) {
                    $sec .= ',' . $row['usec'];
                }
                $sec = ltrim($sec, ',');
                $section = " AND section IN ($sec) ";
                $chk_user_sec_map = 1;
            } else {
                $chk_user_sec_map = 0;
            }
        }
        if ($chk_user_sec_map == 1) {
            $query = "SELECT * FROM 
        (
            SELECT usercode,u.name,empid,section_name, type_name,section,usertype FROM master.users u 
            LEFT JOIN master.usersection us ON section=us.id
            LEFT JOIN master.usertype ut ON usertype=ut.id
            WHERE isda='Y' AND u.display='Y' AND us.display='Y' AND usertype IN (17,50,51) $section
        )t1 
        
        LEFT JOIN 
        (
            SELECT m.dacode upby, count(h.diary_no) da_case FROM heardt h    
            inner join main m on h.diary_no = m.diary_no
            inner join master.users u on u.usercode = m.dacode
            where h.main_supp_flag = 0 and u.usertype IN (17,50,51) and u.display = 'Y' AND h.ent_dt::date ='" . revertDate_hiphen($date) . "'
            GROUP BY m.dacode    
        )t2 ON t1.usercode=upby
            
        LEFT JOIN 
        (
            SELECT m.dacode upby_o, 
            sum(case when tt.bo_ent_dt is not null THEN 1 ELSE 0 END ) bo_v,
            sum(case when (tt.bo_ent_dt is null OR tt.bo_ent_dt IS NULL) THEN 1 ELSE 0 END) bo_nv,
            
            sum(case when tt.ar_ent_dt is not null THEN 1 ELSE 0 END ) ar_v,
            SUM(CASE WHEN tt.bo_ent_dt is not null AND tt.ar_ent_dt is null THEN 1 ELSE 0 END) AS ar_nv,
            
            sum(case when tt.dy_ent_dt is not null THEN 1 ELSE 0 END ) dy_v,
            SUM(CASE WHEN tt.ar_ent_dt is not null AND tt.dy_ent_dt is null THEN 1 ELSE 0 END) AS dy_nv
            
            FROM heardt h    
            inner join main m on h.diary_no = m.diary_no
            inner join master.users u on u.usercode = m.dacode
            LEFT JOIN case_verify_by_sec tt ON tt.diary_no = h.diary_no AND tt.bo_ent_dt > h.ent_dt AND tt.display = 'Y'
            where h.main_supp_flag = 0 and u.usertype IN (17,50,51) AND u.display = 'Y' 
             AND h.ent_dt::date ='" . revertDate_hiphen($date) . "'
            GROUP BY m.dacode    
        )t3 ON t1.usercode=upby_o
        
        ORDER BY section_name, case when usertype = 17 then 1 when usertype = 51 then 2 else 3 end asc,empid";
            $result = $this->db->query($query);
            return $result->getResultArray();
        }
    }

    public function workdone_verify_get_full($type, $flag, $date, $name, $id, $usertype){
	    
		$cs_v_leftjoin = false;
		$cs_v_leftjoin_sq = "";

		if ($type == 'dacase') {
			switch ($flag) {
				case 2:
					$cs_v_leftjoin = true;
					$cs_v_leftjoin_sq = "tt.bo_ent_dt != '0001-01-01 00:00:00'";
					break;
				case 3:
					$cs_v_leftjoin = true;
					$cs_v_leftjoin_sq = "(tt.bo_ent_dt = '0001-01-01 00:00:00' OR tt.bo_ent_dt IS NULL)";
					break;
				case 4:
					$cs_v_leftjoin = true;
					$cs_v_leftjoin_sq = "tt.ar_ent_dt != '0001-01-01 00:00:00'";
					break;
				case 5:
					$cs_v_leftjoin = true;
					$cs_v_leftjoin_sq = "tt.bo_ent_dt != '0001-01-01 00:00:00' AND tt.ar_ent_dt = '0001-01-01 00:00:00'";
					break;
				case 6:
					$cs_v_leftjoin = true;
					$cs_v_leftjoin_sq = "tt.dy_ent_dt != '0001-01-01 00:00:00'";
					break;
				case 7:
					$cs_v_leftjoin = true;
					$cs_v_leftjoin_sq = "tt.ar_ent_dt != '0001-01-01 00:00:00' AND tt.dy_ent_dt = '0001-01-01 00:00:00'";
					break;
			}
		}

		$builder = $this->db->table('heardt h')
					->select("u.name as updatedby, h.diary_no, h.next_dt, h.ent_dt, h.mainhead, h.subhead, h.listorder, h.board_type, 
							 m.lastorder, m.reg_no_display, s.stagename, l.purpose, b.remark")
					->join('main m', 'm.diary_no = h.diary_no', 'inner')
					->join('master.listing_purpose l', "l.code = h.listorder AND l.display = 'Y'", 'left')
					->join('master.subheading s', "s.stagecode = h.subhead AND s.display = 'Y' AND s.listtype = 'M'", 'left')
					->join('brdrem b', 'b.diary_no = h.diary_no', 'left')
					->join('master.users u', 'u.usercode = h.usercode', 'left');
		if ($cs_v_leftjoin) {
			$builder->join('case_verify_by_sec tt', "tt.diary_no = h.diary_no AND tt.bo_ent_dt > h.ent_dt AND tt.display = 'Y'", 'left');
		}

		$whereConditions = [
			'h.main_supp_flag' => 0,
			'DATE(h.ent_dt)' => $date,
			'm.dacode' => $id
		]; 

		if (!empty($cs_v_leftjoin_sq)) {
			$builder->where($cs_v_leftjoin_sq);
		}

		$builder->where($whereConditions);
		
		$builder->orderBy('h.ent_dt', 'ASC');
		//pr($builder->getCompiledSelect()); die;
		$query = $builder->get();
		return $query->getResultArray(); 
    }
	
	public function sql_get_oc($id){
			$sectionQuery = $this->db->table('master.users')
							->select('section')
							->where('usercode', $id)
							->get()
							->getRow(); 

			if (!$sectionQuery) {
				return []; 
			}
			
		 $section = $sectionQuery->section;

		$builder = $this->db->table('master.users u');
		$builder->select('u.*');
		$builder->where('u.section', $section);
		$builder->whereIn('u.usertype', [14, 9, 6, 4]);
		$builder->where('u.display', 'Y');

		$query = $builder->get();
		return $query->getResultArray();
	}
	
	public function workdone_verify_response_status_update($ucode,$dno,$board_type,$mainhead,$next_dt,$userType){
			if($userType == 14){
				$data = [
					'diary_no'  => $dno,
					'next_dt'   => $next_dt,
					'm_f'       => $mainhead,
					'board_type'=> $board_type,
					'bo_ent_dt' => date('Y-m-d H:i:s'), 
					'bo_ucode'  => $ucode,
				];

				$this->db->table('case_verify_by_sec')->insert($data);
				return $this->db->affectedRows(); 
			}else{
			    $data = [];

				if ($userType == 9) {
					$data = [
							'b.ar_ent_dt' => date('Y-m-d H:i:s'),
							'b.ar_ucode'  => $ucode
						];
					} elseif ($userType == 4 || $userType == 6) {
						$data = [
							'b.dy_ent_dt' => date('Y-m-d H:i:s'),
							'b.dy_ucode'  => $ucode
						];
					}

				if (!empty($data)) {
						$subquery = $this->db->table('case_verify_by_sec')
									   ->select('diary_no, max(bo_ent_dt) AS max_entdt')
									   ->where('diary_no', $dno)
									   ->groupBy('diary_no')
									   ->getCompiledSelect();  

						$builder = $this->db->table('case_verify_by_sec b');
						$builder->join('(' . $subquery . ') a', 'b.diary_no = a.diary_no', 'inner');
						$builder->set($data); 
						$builder->where('a.max_entdt', 'b.bo_ent_dt');
						$builder->where('a.diary_no', $dno);
						$builder->update();
						//echo $builder->getCompiledSelect(); die;
						return $this->db->affectedRows(); 
				}
			}
       return 0;  
	}
	

    public function workdone_verify_get_from_data($empid, $date, $date2, $model, $chk_user_sec_map, $section, $usertype)
    {

        if ($usertype != 1) {
            $yoursection = "SELECT usec FROM master.user_sec_map a LEFT JOIN master.usersection b ON usec=b.id WHERE empid=$empid AND a.display='Y' AND b.display='Y'";
            $result = $this->db->query($yoursection);
            $rowCount = $result->getNumRows();
            if ($rowCount > 0) {;
                $sec = '';
                foreach ($result->getResultArray() as $row) {
                    $sec .= ',' . $row['usec'];
                }
                $sec = ltrim($sec, ',');
                $section = " AND section IN ($sec) ";
                $chk_user_sec_map = 1;
            } else {
                $chk_user_sec_map = 0;
            }
        }

        $chk_user_sec_map = 1;
        if ($chk_user_sec_map == 1) {
            $query = "SELECT * FROM 
        (
            SELECT usercode,u.name,empid,section_name, type_name,section,usertype FROM master.users u 
            LEFT JOIN master.usersection us ON section=us.id
            LEFT JOIN master.usertype ut ON usertype=ut.id
            WHERE isda='Y' AND u.display='Y' AND us.display='Y' AND usertype IN (17,50,51) $section
        )t1 
    
    LEFT JOIN 
    (
        SELECT m.dacode upby, count(h.diary_no) da_case FROM heardt h    
            inner join main m on h.diary_no = m.diary_no
            inner join master.users u on u.usercode = m.dacode
            where h.main_supp_flag = 0 and u.usertype IN (17,50,51) and u.display = 'Y' AND DATE(h.ent_dt) between'" . revertDate_hiphen($date) . "' and '" . revertDate_hiphen($date2) . "'
        GROUP BY m.dacode    
    )t2 ON t1.usercode=upby

    LEFT JOIN 
    (
        SELECT m.dacode upby_o, 
        sum(case when tt.bo_ent_dt is not null then 1 else 0 end) bo_v,
        sum(case when (tt.bo_ent_dt is null  OR tt.bo_ent_dt is null) then 1 else 0 end) bo_nv,
        
        sum(case when tt.ar_ent_dt is not null then 1 else 0 end) ar_v,
        sum(case when tt.bo_ent_dt is not null AND tt.ar_ent_dt is null  then 1 else 0 end) ar_nv,
        
        sum(case when tt.dy_ent_dt is not null then 1 else 0 end) dy_v,
        sum(case when tt.ar_ent_dt is not null and tt.dy_ent_dt is null  then 1 else 0 end) dy_nv
        
        FROM heardt h    
        inner join main m on h.diary_no = m.diary_no
        inner join master.users u on u.usercode = m.dacode
        LEFT JOIN case_verify_by_sec tt ON tt.diary_no = h.diary_no AND tt.bo_ent_dt > h.ent_dt AND tt.display = 'Y'
        where h.main_supp_flag = 0 and u.usertype IN (17,50,51) and u.display = 'Y' and DATE(h.ent_dt) between '" . revertDate_hiphen($date) . "' and '" . revertDate_hiphen($date2) . "'
        GROUP BY m.dacode    
    )t3 ON t1.usercode=upby_o
    
    ORDER BY section_name, case when usertype = 17 then 1 when usertype = 51 then 2 else 3 end asc,empid";
            $result = $this->db->query($query);
            return $result->getResultArray();
        }
    }

    public function case_listed_Advance_Daily_dawise($usercode)
    {
        $sql_da = "  SELECT  users.name,users.empid,
                    COALESCE(STRING_AGG(us.id::text, ','), us.id::text) AS us_id,
                    ut.id AS ut_id
                    FROM 
                    master.users 
                    INNER JOIN 
                    master.user_sec_map AS um ON users.empid = um.empid AND um.display = 'Y'
                    LEFT JOIN 
                     master.usersection AS us ON users.section = us.id
                    LEFT JOIN 
                    master.usertype AS ut ON ut.id = users.usertype
                    WHERE 
                    users.display = 'Y' 
                    AND users.attend = 'P' 
                    AND usercode='" . $usercode . "'
                    GROUP BY 
                    users.name, users.empid, ut.id,us.id;";

        $query2 = $this->db->query($sql_da);
        $result = $query2->getResultArray();
        if (sizeof($result) > 0) {
            $ut_id = $result[0]['ut_id'];
            $us_id = $result[0]['us_id'];
        }
        $cond = "";
        if ($ut_id == 14) {
            $cond = " where us.id=$us_id";
        } else if ($ut_id == 6 or $ut_id == 9 or $ut_id == 4 or $ut_id == 12) {
            $cond = " where us.id in ($us_id)";
        } else if ($ut_id == 1 or $ut_id == 3) {
            $cond = "";
        } else if ($ut_id != 14 && $ut_id != 4 && $ut_id != 6 && $ut_id != 9 && $ut_id != 12 && $ut_id != 1) {
            $cond = " where u.usercode=" . $usercode;
        }
        $sql = "SELECT 
                ListType,
                TO_CHAR(a.next_dt, 'DD-MM-YYYY') AS cl_date,
                CASE 
                    WHEN board_type = 'J' THEN 'COURT' 
                    WHEN board_type = 'C' THEN 'CHAMBER' 
                    WHEN board_type = 'R' THEN 'REGISTRAR' 
                END AS board_type,
                courtno,
                clno,
                a.brd_slno,
                CONCAT(m.reg_no_display, '@ D.No.', SUBSTRING(m.diary_no::TEXT, 1, LENGTH(m.diary_no::TEXT) - 4), '/', SUBSTRING(m.diary_no::TEXT, LENGTH(m.diary_no::TEXT) - 3, 4)) AS CaseNo,
                m.pet_name,
                m.res_name,
                CONCAT(u.name, '@', u.empid, ' SEC ', us.section_name) AS uid
            FROM (
                SELECT 
                    'Final List' AS ListType, 
                    h.diary_no, 
                    h.next_dt, 
                    h.board_type,
                    Rt.courtno,
                    h.clno,
                    h.brd_slno 
                FROM 
                    main m
                    INNER JOIN heardt h ON h.diary_no = m.diary_no 
                    LEFT JOIN master.roster Rt ON Rt.id = h.roster_id
                    LEFT JOIN cl_printed cl ON cl.next_dt = h.next_dt 
                        AND cl.m_f = h.mainhead 
                        AND cl.part = h.clno 
                        AND cl.main_supp = h.main_supp_flag 
                        AND cl.roster_id = h.roster_id 
                        AND cl.display = 'Y'
                WHERE 
                    cl.next_dt IS NOT NULL 
                    AND h.next_dt >= CURRENT_DATE 
                    AND m.c_status = 'P' 
                    AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)

                UNION

                SELECT 
                    'Advance List' AS ListType, 
                    h.diary_no, 
                    h.next_dt, 
                    h.board_type,
                    '0' AS courtno,
                    h.clno,
                    h.brd_slno 
                        FROM 
                            main m
                            LEFT JOIN advance_allocated h ON m.diary_no = h.diary_no 
                                AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
                        WHERE 
                            h.next_dt >= CURRENT_DATE 
                            AND m.c_status = 'P' 
                            AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
                    ) a
                    INNER JOIN main m ON m.diary_no = a.diary_no
                    LEFT JOIN master.users u ON u.usercode = m.dacode 
                        AND (u.display = 'Y' OR u.display IS NULL)      
                    LEFT JOIN master.usersection us ON us.id = u.section 
                        AND us.display = 'Y'
                        $cond
                    ORDER BY 
                        CASE WHEN ListType = 'Advance List' THEN 1 ELSE 2 END ASC, 
                        CASE WHEN board_type = 'J' THEN 1 WHEN board_type = 'C' THEN 2 ELSE 3 END, 
                        cl_date DESC;
                    ";
        $query = $this->db->query($sql);
        // $result =  $query->getResultArray();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return $query->getResultArray();
        }
    }

    public function getJudges($list_dt)
    {
        $builder = $this->db->table('master.cat_jud_ratio');
        return $builder->select('j.jname, j.jcode')
            ->join('master.judge j', 'cat_jud_ratio.judge = j.jcode')
            ->where('cat_jud_ratio.next_dt', $list_dt)
            ->groupBy('j.jcode')
            ->orderBy('j.judge_seniority')
            ->get()
            ->getResultArray();
    }
    public function getJudgesSaved($list_dt)
    {
        return $this->db->table('master.cat_jud_ratio c')
            ->select('j.jname, j.jcode')
            ->join('master.judge j', 'c.judge = j.jcode')
            ->where('c.next_dt', $list_dt)
            ->groupBy('jcode')
            ->orderBy('j.judge_seniority')
            ->get()
            ->getResultArray();
    }

    public function getCategoryData($list_dt, $judge_code)
    {
        $builder = $this->db->table('master.cat_jud_ratio');
        return $builder->select('cat_id, cat_name, judge, next_dt, bail_top, orders, fresh, fresh_no_notice, an_fd, cnt, SUM(cnt) AS tot_cnt, ratio_cnt')
            ->where('next_dt', $list_dt)
            ->where('judge', $judge_code)
            ->groupBy('cat_name')
            ->orderBy('cat_name')
            ->get()
            ->getResultArray();
    }

    public function loosedoc_verify_not_verify($from_date, $to_date, $usercode)
    {
        $sql_da = "
        SELECT 
        users.name, 
        users.empid, 
       CASE 
          WHEN STRING_AGG(CAST(usec AS TEXT), ',') IS NULL 
          THEN CAST(us.id AS TEXT) 
          ELSE STRING_AGG(CAST(usec AS TEXT), ',') 
        END AS us_id, 
        ut.id AS ut_id
      FROM 
        master.users 
        inner join master.user_sec_map um on users.empid = um.empid 
        AND um.display = 'Y' 
        left join master.usersection us on users.section = us.id 
        left join master.usertype ut on ut.id = users.usertype 
      where 
        users.display = 'Y' 
        and users.attend = 'P' 
        and usercode='" . $usercode . "'
        GROUP BY 
        users.name, users.empid, ut.id,us.id;";
        $query2 = $this->db->query($sql_da);
        $result = $query2->getResultArray();
        if (sizeof($result) > 0) {
            $ut_id = $result[0]['ut_id'];
            $us_id = $result[0]['us_id'];
        }
        $cond = "";
        if ($ut_id == 14) {
            $cond = " and us.id=$us_id";
        } else if ($ut_id == 6 or $ut_id == 9 or $ut_id == 4 or $ut_id == 12) {
            $cond = " and us.id in ($us_id)";
        } else if ($ut_id == 1) {
            $cond = "";
        } else if ($ut_id != 14 && $ut_id != 4 && $ut_id != 6 && $ut_id != 9 && $ut_id != 12 && $ut_id != 1) {
            $cond = " and u.usercode=$usercode";
        }

        $sql = "SELECT date1, section, sec_id,SUM(documents) AS total,COALESCE(SUM(verify)) AS verify,COALESCE( SUM(not_verify)) AS not_verify 
            FROM 
            (
                SELECT 
                date1, 
                section, 
                sec_id, 
                documents, 
                CASE WHEN verified = 'V' THEN documents END AS verify, 
                CASE WHEN verified != 'V' THEN documents END AS not_verify 
                FROM 
                (
                    SELECT 
                    DATE(d.ent_dt) AS date1, 
                    STRING_AGG(DISTINCT section_name, ', ') as section, 
                    STRING_AGG(DISTINCT us.id::TEXT, ', ') as sec_id, 
                    COUNT(*) AS documents, 
                    verified 
                    FROM 
                    docdetails d 
                    inner join main m on m.diary_no = d.diary_no 
                    LEFT JOIN master.users u ON u.usercode = m.dacode 
                    AND (
                        u.display = 'Y' 
                        or u.display is null
                    ) 
                    left join master.usersection us on us.id = u.section 
                    and us.display = 'Y' 
                    WHERE 
                    d.display = 'Y' $cond
                    and m.c_status = 'P' 
                    AND DATE(d.ent_dt) BETWEEN '$from_date' AND '$to_date' 
                    GROUP BY 
                    DATE(d.ent_dt), 
                    verified
                ) a
            ) b 
            GROUP BY 
            date1,b.section,b.sec_id";

        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return $query->getResultArray();
        }
    }

    public function get_section_list()
    {
        $sql = "SELECT * FROM master.usersection WHERE display = 'Y' and isda = 'Y' ORDER BY section_name";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

    public function get_pre_notice_data($ucode, $usertype, $coram_having, $board_type, $sec_id, $rnr, $pre_after, $h3_head)
    {

        $heading1 = '';
        if ($usertype == '14') {
            $sq_u = "SELECT string_agg(u2.usercode::text, ', ') AS allda FROM master.users u LEFT JOIN master.users u2 ON u2.section = u.section WHERE u.display = 'Y' AND u.usercode = '$ucode' GROUP BY u2.section;";

            $re_u = $this->db->query($sq_u);
            $ro_u = $re_u->getResultArray();
            $all_da = $ro_u[0]['allda'];
            $mdacode = "AND m.dacode IN ($all_da)";
        } else if ($usertype == '17' or $usertype == '50' or $usertype == '51') {
            $mdacode = "AND m.dacode = '$ucode'";
        } else {
            $mdacode = "";
        }
        if ($pre_after == "0") {
            $pre_after = "";
            $heading1 .= " ";
        }
        if ($pre_after == "1") {
            $pre_after = " c.diary_no IS NULL AND (m.fil_no_fh = '' OR m.fil_no_fh IS NULL) AND h.subhead NOT IN (813, 814) AND ";
            $heading1 .= " Pre Notice ";
        }
        if ($pre_after == "2") {
            $pre_after = " ((c.diary_no is not null AND m.fil_no_fh != '' AND m.fil_no_fh is not null) OR h.subhead in (813,814) ) AND ";
            $heading1 .= " After Notice ";
        }
        if ($coram_having == "0") {
            $coram_having = "";
            $heading1 .= "";
        }
        if ($coram_having == "1") {
            $coram_having = " and h.coram::int != 0 and h.coram is not null and h.coram !='' ";
            $heading1 .= " Having Coram ";
        }
        if ($coram_having == "2") {
            $coram_having = " and (h.coram::int = 0 or h.coram is null or h.coram = '') ";
            $heading1 .= " Having No Coram ";
        }

        if ($board_type == "0") {
            $board_type = "";
        } else {
            $board_type = "AND h.board_type = '" . $board_type . "'";
        }

        if ($sec_id == "0") {
            $sec_id = "";
            $sec_id2 = "";
        } else {
            $sec_id = "AND us.id = '" . $sec_id . "'";
            $sec_id2 = "AND us.id is not null";
        }
        if ($rnr == "0") {
            $ready_not_ready = "";
        }
        if ($rnr == "1") {
            $ready_not_ready = " AND h.main_supp_flag = 0";
            $heading1 .= " Ready Cases ";
        }
        if ($rnr == "2") {
            $ready_not_ready = " AND h.main_supp_flag != 0";
            $heading1 .= " Not Ready Cases ";
        }

        $sql = "SELECT CASE WHEN h.main_supp_flag = 0 THEN 'Ready' ELSE 'Not Ready' END AS r_n_r, m.reg_no_display,  mc.submaster_id, u.name, us.section_name, 
                s.stagename, l.purpose, EXTRACT( YEAR FROM  m.active_fil_dt) AS fyr,active_reg_year, active_fil_dt, active_fil_no, m.pet_name, m.res_name, 
                m.pno, m.rno, casetype_id, ref_agency_state_id,diary_no_rec_date, h.* FROM heardt h INNER JOIN main m ON m.diary_no = h.diary_no LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no AND mc.display = 'Y' AND mc.submaster_id != 911 AND mc.submaster_id != 912 AND mc.submaster_id != 914 
                AND mc.submaster_id != 239 AND mc.submaster_id != 240 AND mc.submaster_id != 241 AND mc.submaster_id != 242 AND mc.submaster_id != 243 
                AND mc.submaster_id != 331 AND mc.submaster_id != 9999  left join case_remarks_multiple c on c.diary_no = m.diary_no and c.r_head in (1, 3, 62, 181, 182, 183, 184) left join master.subheading s on s.stagecode = h.subhead and s.display = 'Y' left join  master.listing_purpose l on l.code = h.listorder 
                LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no  AND rd.remove_def = 'N' LEFT JOIN master.users u ON u.usercode = m.dacode AND u.display = 'Y' 
                LEFT JOIN master.usersection us ON us.id = u.section $sec_id WHERE $pre_after m.c_status = 'P' AND h.board_type = 'J' AND h.mainhead = 'M'  and rd.fil_no is null AND mc.diary_no IS NOT NULL  and h.listorder != 49  AND m.active_casetype_id != 9 AND m.active_casetype_id != 10 
                AND m.active_casetype_id != 25 AND m.active_casetype_id != 26 
                AND (
                    m.diary_no = m.conn_key :: int 
                    OR m.conn_key = '' 
                    OR m.conn_key IS NULL 
                    OR m.conn_key = '0'
                ) 
                AND h.next_dt IS NOT NULL 
                AND h.subhead != 801 
                AND h.subhead != 817 
                AND h.subhead != 818 
                AND h.subhead != 819 
                AND h.subhead != 820 
                AND h.subhead != 848 
                AND h.subhead != 849 
                AND h.subhead != 850 
                AND h.subhead != 854 
                and h.subhead != 0 $coram_having $ready_not_ready $mdacode $sec_id2 
                group by 
                m.diary_no, 
                mc.submaster_id, 
                u.name, 
                us.section_name, 
                s.stagename, 
                l.purpose, 
                fyr, 
                m.active_reg_year, 
                m.active_fil_dt, 
                m.active_fil_no, 
                m.pet_name, 
                m.res_name, 
                m.pno, 
                m.rno, 
                m.casetype_id, 
                m.ref_agency_state_id, 
                m.diary_no_rec_date, 
                h.main_supp_flag, 
                h.diary_no 
                order by 
                tentative_section(m.diary_no), 
                tentative_da(m.diary_no), 
                CASE WHEN m.active_fil_dt is null THEN 2 ELSE 1 END ASC, 
                CASE WHEN m.active_fil_no = '' THEN 2 ELSE 1 END ASC, 
                EXTRACT(
                    YEAR 
                    FROM 
                    m.active_fil_dt
                ), 
                CAST(
                    SPLIT_PART(
                    SPLIT_PART(m.active_fil_no, '-', -1), 
                    ' ', 
                    1
                    ) AS INTEGER
                ), 
                CAST(
                    RIGHT(m.diary_no :: text, 4) AS INTEGER
                ) ASC, 
                CAST(
                    LEFT(
                    m.diary_no :: text, 
                    LENGTH(m.diary_no :: text) -4
                    ) AS INTEGER
                ) ASC
                ";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

    public function get_advocate_data($diary_no)
    {
        $advsql = "SELECT 
                a.*, 
                string_agg(
                    a.name || COALESCE(CASE WHEN pet_res = 'R' THEN grp_adv ELSE '' END, ''), 
                    '' ORDER BY adv_type DESC, pet_res_no ASC
                ) AS r_n,
                string_agg(
                    a.name || COALESCE(CASE WHEN pet_res = 'P' THEN grp_adv ELSE '' END, ''), 
                    '' ORDER BY adv_type DESC, pet_res_no ASC
                ) AS p_n
                FROM (
                SELECT 
                    a.diary_no, 
                    b.name,
                    string_agg(
                    COALESCE(a.adv::text, ''), '' ORDER BY pet_res ASC, adv_type DESC, pet_res_no ASC
                    ) AS grp_adv,
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
                    a.diary_no, b.name, a.pet_res, a.adv_type, a.pet_res_no
                ORDER BY 
                    pet_res ASC, adv_type DESC, pet_res_no ASC
                ) a
                GROUP BY 
                a.diary_no,a.name,a.grp_adv,a.pet_res,a.adv_type,a.pet_res_no;";
        $query = $this->db->query($advsql);
        $result = $query->getResultArray();
        return $result;
    }

    public function section_ten_q($casetype_displ, $ten_reg_yr, $ref_agency_state_id)
    {

        $sql = "SELECT dacode,section_name,name FROM master.da_case_distribution a
                LEFT JOIN master.users b ON usercode=dacode
                LEFT JOIN master.usersection c ON b.section=c.id
                WHERE case_type=$casetype_displ AND $ten_reg_yr BETWEEN case_f_yr AND case_t_yr AND state='$ref_agency_state_id' AND a.display='Y' ";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

    public function get_da_wise_rgo_data($condition, $section_name)
    {

        $sql = "SELECT 
                    m.dacode, 
                    b.section_name, 
                    a.name, 
                    a.empid, 
                    count(distinct m.diary_no) tot, 
                    count(
                        distinct case when h.mainhead = 'M' then m.diary_no end
                    ) tot_mh, 
                    count(
                        distinct case when h.mainhead = 'F' then m.diary_no end
                    ) tot_fh, 
                    count(
                        distinct case 
                            when (
                                CASE 
                                    WHEN h.tentative_cl_dt IS NOT NULL THEN 
                                        (h.tentative_cl_dt - CURRENT_DATE) < 2
                                    ELSE 
                                        true
                                END
                            )
                            and NOT (
                                CASE 
                                    WHEN h.mainhead = 'M' THEN 
                                        s.listtype = 'M' 
                                        AND s.listtype IS NOT NULL 
                                        AND s.display = 'Y' 
                                        AND s.display IS NOT NULL
                                    WHEN h.mainhead = 'S' THEN 
                                        s.listtype = 'S' 
                                        AND s.listtype IS NOT NULL 
                                        AND s.display = 'Y' 
                                        AND s.display IS NOT NULL
                                    ELSE 
                                        true
                                END
                                and (
                                    m.main_supp_flag = 0
                                    AND clno = 0 
                                    AND m.brd_slno = 0 
                                    AND (judges is null OR judges = '0') 
                                    AND roster_id = 0
                                )
                                OR (h.next_dt IS NOT NULL and h.next_dt >= CURRENT_DATE)
                            )
                            and (
                                lastorder NOT LIKE '%Not Reached%' 
                                and lastorder NOT LIKE '%Case Not Receive%' 
                                and lastorder NOT LIKE '%Heard & Reserved%' 
                                OR lastorder IS NULL
                            ) 
                            and (
                                head_code != '5' 
                                OR head_code IS NULL
                            ) 
                            and m.diary_no NOT IN (
                                select 
                                    diary_no 
                                from 
                                    public.heardt 
                                where 
                                    main_supp_flag = 3 
                                    and usercode in (559, 146, 744, 747, 469, 1485, 742, 1486, 935, 757, 49, 762)
                                union 
                                select 
                                    fil_no as diary_no 
                                from 
                                    rgo_default 
                                where 
                                    remove_def != 'Y'
                            ) 
                        then m.diary_no 
                        end
                    )
                    red, 
                    count(
                        distinct case 
                            when h.mainhead = 'M' 
                            and (
                                CASE 
                                    WHEN h.tentative_cl_dt IS NOT NULL THEN 
                                        (h.tentative_cl_dt - CURRENT_DATE) < 2
                                    ELSE 
                                        true
                                END
                            )
                            and NOT (
                                CASE 
                                    WHEN h.mainhead = 'M' THEN 
                                        s.listtype = 'M' 
                                        AND s.listtype IS NOT NULL 
                                        AND s.display = 'Y' 
                                        AND s.display IS NOT NULL
                                    WHEN h.mainhead = 'S' THEN 
                                        s.listtype = 'S' 
                                        AND s.listtype IS NOT NULL 
                                        AND s.display = 'Y' 
                                        AND s.display IS NOT NULL
                                    ELSE 
                                        true
                                END
                                and (
                                    m.main_supp_flag = 0 
                                    AND clno = 0 
                                    AND m.brd_slno = 0 
                                    AND (judges is null OR judges = '0') 
                                    and roster_id = 0
                                )
                                OR (h.next_dt IS NOT NULL and h.next_dt >= CURRENT_DATE)
                            )
                            and (
                                lastorder NOT LIKE '%Not Reached%' 
                                and lastorder NOT LIKE '%Case Not Receive%' 
                                and lastorder NOT LIKE '%Heard & Reserved%' 
                                OR lastorder IS NULL
                            ) 
                            and (
                                head_code != '5' 
                                OR head_code IS NULL
                            ) 
                            and m.diary_no NOT IN (
                                select 
                                    diary_no 
                                from 
                                    public.heardt 
                                where 
                                    main_supp_flag = 3 
                                    and usercode in (559, 146, 744, 747, 469, 1485, 742, 1486, 935, 757, 49, 762)
                                union 
                                select 
                                    fil_no as diary_no 
                                from 
                                    rgo_default 
                                where 
                                    remove_def != 'Y'
                            ) 
                        then m.diary_no 
                        end
                    )
                    red_mh, 
                    count(
                        distinct case 
                            when h.mainhead = 'F' 
                            and (
                                CASE 
                                    WHEN h.tentative_cl_dt IS NOT NULL THEN 
                                        (h.tentative_cl_dt - CURRENT_DATE) < 2
                                    ELSE 
                                        true
                                END
                            )
                            and NOT (
                                CASE 
                                    WHEN h.mainhead = 'M' THEN 
                                        s.listtype = 'M' 
                                        AND s.listtype IS NOT NULL 
                                        AND s.display = 'Y' 
                                        AND s.display IS NOT NULL
                                    WHEN h.mainhead = 'S' THEN 
                                        s.listtype = 'S' 
                                        AND s.listtype IS NOT NULL 
                                        AND s.display = 'Y' 
                                        AND s.display IS NOT NULL
                                    ELSE 
                                        true
                                END
                                and (
                                   m.main_supp_flag = 0 
                                    AND clno = 0 
                                    AND h.brd_slno = 0 
                                    AND (judges is null OR judges = '0') 
                                    and roster_id = 0
                                )
                                OR (
                                    h.next_dt IS NOT NULL 
                                    and h.next_dt >= CURRENT_DATE
                                )
                            )
                            and (
                                lastorder NOT LIKE '%Not Reached%' 
                                and lastorder NOT LIKE '%Case Not Receive%' 
                                and lastorder NOT LIKE '%Heard & Reserved%' 
                                OR lastorder IS NULL
                            ) 
                            and (
                                head_code != '5' 
                                OR head_code IS NULL
                            ) 
                            and m.diary_no NOT IN (
                                select 
                                    diary_no 
                                from 
                                    public.heardt 
                                where 
                                    main_supp_flag = 3 
                                    and usercode in (559, 146, 744, 747, 469, 1485, 742, 1486, 935, 757, 49, 762)
                                union 
                                select 
                                    fil_no as diary_no 
                                from 
                                    rgo_default 
                                where 
                                    remove_def != 'Y'
                            ) 
                        then m.diary_no 
                        end
                    )
                    red_fh, 
                    count(
                        distinct case 
                            when (h.tentative_cl_dt - CURRENT_DATE) > 1
                            and NOT (
                                CASE 
                                    WHEN h.mainhead = 'M' THEN 
                                        s.listtype = 'M' 
                                        AND s.listtype IS NOT NULL 
                                        AND s.display = 'Y' 
                                        AND s.display IS NOT NULL
                                    WHEN h.mainhead = 'S' THEN 
                                        s.listtype = 'S' 
                                        AND s.listtype IS NOT NULL 
                                        AND s.display = 'Y' 
                                        AND s.display IS NOT NULL
                                    ELSE 
                                        true
                                END
                                and (
                                    m.main_supp_flag = 0 
                                    AND clno = 0 
                                    AND h.brd_slno = 0 
                                    AND (judges is null OR judges = '0') 
                                    and roster_id = 0
                                )
                                OR (
                                    h.next_dt IS NOT NULL 
                                    and h.next_dt >= CURRENT_DATE
                                )
                            )
                            and (
                                lastorder NOT LIKE '%Not Reached%' 
                                and lastorder NOT LIKE '%Case Not Receive%' 
                                and lastorder NOT LIKE '%Heard & Reserved%' 
                                OR lastorder IS NULL
                            ) 
                            and (
                                head_code != '5' 
                                OR head_code IS NULL
                            ) 
                            and m.diary_no NOT IN (
                                select 
                                    diary_no 
                                from 
                                    public.heardt 
                                where 
                                    main_supp_flag = 3 
                                    and usercode in (559, 146, 744, 747, 469, 1485, 742, 1486, 935, 757, 49, 762)
                                union 
                                select 
                                    fil_no as diary_no 
                                from 
                                    rgo_default 
                                where 
                                    remove_def != 'Y'
                            ) 
                        then m.diary_no 
                        end
                    ) as orange,
                    count(
                        distinct case 
                            when h.mainhead = 'M' 
                                and (m.tentative_cl_dt::date - CURRENT_DATE) > 1 
                                and not (
                                    (
                                        (h.mainhead = 'M' and s.listtype = 'M' and s.listtype IS NOT NULL and s.display = 'Y' and s.display IS NOT NULL)
                                        or (h.mainhead = 'S' and s.listtype = 'S' and s.listtype IS NOT NULL and s.display = 'Y' and s.display IS NOT NULL)
                                        or (m.main_supp_flag = 0 and clno = 0 and h.brd_slno = 0 and (judges is null or judges = '0') and roster_id = 0)
                                    or (m.next_dt IS NOT NULL and m.next_dt::date >= CURRENT_DATE)
                                    )
                                )
                                and (
                                    (lastorder not like '%Not Reached%' and lastorder not like '%Case Not Receive%' and lastorder not like '%Heard & Reserved%')
                                    or lastorder IS NULL
                                )
                                and (head_code != '5' or head_code IS NULL)
                                and m.diary_no not in (
                                    select diary_no from public.heardt where main_supp_flag = 3 and usercode in (559, 146, 744, 747, 469, 1485, 742, 1486, 935, 757, 49, 762)
                                    union
                                    select fil_no as diary_no from rgo_default where remove_def != 'Y'
                                )
                            then m.diary_no 
                        end
                    ) as orange_mh, 

                    count(
                        distinct case 
                            when h.mainhead = 'F' 
                                and (m.tentative_cl_dt::date - CURRENT_DATE) > 1 
                                and not (
                                    (
                                        (h.mainhead = 'M' and s.listtype = 'M' and s.listtype IS NOT NULL and s.display = 'Y' and s.display IS NOT NULL)
                                        or (h.mainhead = 'S' and s.listtype = 'S' and s.listtype IS NOT NULL and s.display = 'Y' and s.display IS NOT NULL)
                                        or (m.main_supp_flag = 0 and clno = 0 and h.brd_slno = 0 and (judges is null or judges = '0') and roster_id = 0)
                                        or (m.next_dt IS NOT NULL and m.next_dt::date >= CURRENT_DATE)
                                    )
                                )
                                and (
                                    (lastorder not like '%Not Reached%' and lastorder not like '%Case Not Receive%' and lastorder not like '%Heard & Reserved%')
                                    or lastorder IS NULL
                                )
                                and (head_code != '5' or head_code IS NULL)
                                and m.diary_no not in (
                                    select diary_no from public.heardt where main_supp_flag = 3 and usercode in (559, 146, 744, 747, 469, 1485, 742, 1486, 935, 757, 49, 762)
                                    union
                                    select fil_no as diary_no from rgo_default where remove_def != 'Y'
                                )
                            then m.diary_no 
                        end
                    ) as orange_fh, 

                    count(
                        distinct case 
                            when (
                                (h.mainhead = 'M' and s.listtype = 'M' and s.listtype IS NOT NULL and s.display = 'Y' and s.display IS NOT NULL)
                                or (h.mainhead = 'S' and s.listtype = 'S' and s.listtype IS NOT NULL and s.display = 'Y' and s.display IS NOT NULL)
                            )
                                and (
                                    (m.main_supp_flag = 0 and clno = 0 and h.brd_slno = 0 and (judges is null or judges = '0') and roster_id = 0)
                                    or (m.next_dt IS NOT NULL and m.next_dt::date >= CURRENT_DATE)
                                    or (lastorder like '%Not Reached%' or lastorder like '%Case Not Receive%' or lastorder like '%Heard & Reserved%')
                                    or head_code = '5'
                                )
                                and m.diary_no not in (
                                    select diary_no from public.heardt where main_supp_flag = 3 and usercode in (559, 146, 744, 747, 469, 1485, 742, 1486, 935, 757, 49, 762)
                                    union
                                    select fil_no as diary_no from rgo_default where remove_def != 'Y'
                                )
                            then m.diary_no 
                        end
                    ) as green, 

                    count(
                        distinct case 
                            when h.mainhead = 'M' 
                                and (
                                    (h.mainhead = 'M' and s.listtype = 'M' and s.listtype IS NOT NULL and s.display = 'Y' and s.display IS NOT NULL)
                                    or (h.mainhead = 'S' and s.listtype = 'S' and s.listtype IS NOT NULL and s.display = 'Y' and s.display IS NOT NULL)
                                )
                                and (
                                    (m.main_supp_flag = 0 and clno = 0 and h.brd_slno = 0 and (judges is null or judges = '0') and roster_id = 0)
                                    or (m.next_dt IS NOT NULL and m.next_dt::date >= CURRENT_DATE)
                                    or (lastorder like '%Not Reached%' or lastorder like '%Case Not Receive%' or lastorder like '%Heard & Reserved%')
                                    or head_code = '5'
                                )
                                and m.diary_no not in (
                                    select diary_no from public.heardt where main_supp_flag = 3 and usercode in (559, 146, 744, 747, 469, 1485, 742, 1486, 935, 757, 49, 762)
                                    union
                                    select fil_no as diary_no from rgo_default where remove_def != 'Y'
                                )
                            then m.diary_no 
                        end
                    ) as green_mh, 

                    count(
                        distinct case 
                            when h.mainhead = 'F' 
                                and (
                                    (h.mainhead = 'M' and s.listtype = 'M' and s.listtype IS NOT NULL and s.display = 'Y' and s.display IS NOT NULL)
                                    or (h.mainhead = 'S' and s.listtype = 'S' and s.listtype IS NOT NULL and s.display = 'Y' and s.display IS NOT NULL)
                                )
                                and (
                                    (m.main_supp_flag = 0 and clno = 0 and h.brd_slno = 0 and (judges is null or judges = '0') and roster_id = 0)
                                    or (m.next_dt IS NOT NULL and m.next_dt::date >= CURRENT_DATE)
                                    or (lastorder like '%Not Reached%' or lastorder like '%Case Not Receive%' or lastorder like '%Heard & Reserved%')
                                    or head_code = '5'
                                )
                                and m.diary_no not in (
                                    select diary_no from public.heardt where main_supp_flag = 3 and usercode in (559, 146, 744, 747, 469, 1485, 742, 1486, 935, 757, 49)
                                    union
                                    select fil_no as diary_no from rgo_default where remove_def != 'Y'
                                )
                            then m.diary_no 
                        end
                    ) as green_fh, 

                    count(
                        distinct case 
                            when (
                                h.main_supp_flag = 3 
                                and h.usercode in (559, 146, 744, 747, 469, 1485, 742, 1486, 935, 757, 49, 762)
                            ) 
                            or rd.remove_def != 'Y' 
                            then m.diary_no 
                        end
                    ) as yellow, 

                    count(
                        distinct case 
                            when h.mainhead = 'M' 
                                and (
                                    (h.main_supp_flag = 3 
                                    and h.usercode in (559, 146, 744, 747, 469, 1485, 742, 1486, 935, 757, 49, 762))
                                    or rd.remove_def != 'Y'
                                ) 
                            then m.diary_no 
                        end
                    ) as yellow_mh, 

                    count(
                        distinct case 
                            when h.mainhead = 'F' 
                                and (
                                    (h.main_supp_flag = 3 
                                    and h.usercode in (559, 146, 744, 747, 469, 1485, 742, 1486, 935, 757, 49, 762))
                                    or rd.remove_def != 'Y'
                                ) 
                            then m.diary_no 
                        end
                    ) as yellow_fh
                    FROM 
                    main m 
                    INNER JOIN master.casetype c ON c.casecode = 
                        CASE 
                            WHEN m.active_casetype_id IS NOT NULL AND m.active_casetype_id != 0 THEN m.active_casetype_id
                            ELSE casetype_id
                        END

                    left JOIN heardt h ON m.diary_no = h.diary_no 
                    LEFT JOIN master.users a on m.dacode = a.usercode 
                    left join rgo_default rd on m.diary_no = rd.fil_no 
                    LEFT JOIN master.usersection b ON b.id = a.section 
                    LEFT JOIN master.subheading s ON h.subhead = s.stagecode 
                    WHERE 
                    c_status = 'P' $condition
                    group by 
                    m.dacode ,b.section_name, a.name,a.empid
                    order by 
                    section_name, 
                    empid";
        // pr($sql);
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

    public function date_given_by_da_get_data($list_dt, $list_dt_db)
    {
        $sql = "SELECT 
                us.section_name AS section_name_updated_by,
                CONCAT(COALESCE(m.reg_no_display, ''), ' @ ', 
                    CONCAT(LEFT(m.diary_no::text, LENGTH(m.diary_no::text) - 4), '-', SUBSTRING(m.diary_no::text, LENGTH(m.diary_no::text) - 3))) AS case_no,
                CONCAT(m.pet_name, ' Vs. ', m.res_name) AS Cause_title,
                TO_CHAR(t.next_dt, 'DD-MM-YYYY') AS Fixed_For,
                COALESCE(
                    (SELECT STRING_AGG(abbreviation, '#') 
                    FROM master.judge 
                    WHERE POSITION(',' || h.coram || ',' IN ',' || jcode || ',') > 0), 
                    ''
                ) AS Coram,
                CASE 
                    WHEN s.category_sc_old IS NOT NULL AND s.category_sc_old != '' AND s.category_sc_old != '0' THEN 
                    CONCAT('(', s.category_sc_old, ')', s.sub_name1, '-', s.sub_name4)
                    ELSE 
                    CONCAT('(', CONCAT(s.subcode1, '', s.subcode2), ')', s.sub_name1, '-', s.sub_name4)
                END AS subject_category,
                t.mainhead, 
                t.ent_dt, 
                t.board_type, 
                u.name AS username, 
                u.empid, 
                tentative_section(m.diary_no::text) AS section_name,
                tentative_da(m.diary_no::int) AS DA_Name
                FROM (
                SELECT diary_no, next_dt, mainhead, board_type, usercode, ent_dt 
                FROM heardt 
                WHERE next_dt = '$list_dt_db' AND module_id = 5 AND clno = 0 AND main_supp_flag = 0 AND listorder = 4
                UNION
                SELECT diary_no, next_dt, mainhead, board_type::Text, usercode, ent_dt 
                FROM last_heardt 
                WHERE next_dt = '$list_dt_db' AND module_id = 5 AND clno = 0 AND main_supp_flag = 0 AND listorder = 4
                ) t
                LEFT JOIN case_remarks_multiple c ON c.diary_no::int = t.diary_no AND c.r_head = 24 AND c.head_content = '$list_dt'
                LEFT JOIN main m ON m.diary_no = t.diary_no
                LEFT JOIN heardt h ON h.diary_no = m.diary_no
                LEFT JOIN master.users u ON u.usercode = t.usercode
                LEFT JOIN master.usersection us ON u.section = us.id
                LEFT JOIN mul_category mcat ON m.diary_no = mcat.diary_no AND mcat.display = 'Y'
                LEFT JOIN master.submaster s ON mcat.submaster_id = s.id AND s.display = 'Y'
                WHERE c.diary_no IS NULL 
                AND (m.diary_no = m.conn_key::int OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
                GROUP BY 
                us.section_name, m.reg_no_display, m.diary_no, m.pet_name, m.res_name, h.coram,
                t.next_dt, t.mainhead, t.ent_dt, t.board_type, u.name, u.empid, 
                s.category_sc_old, s.sub_name1, s.sub_name4, s.subcode1, s.subcode2
                ORDER BY 
                tentative_section(m.diary_no), tentative_da(m.diary_no::int)
                , m.diary_no_rec_date";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

    public function dropped_cases_get_data($list_dt, $list_dt_db, $string_heading, $datetype)
    {
        if ($datetype == 1) {
            $sql = "SELECT 
                    CONCAT(
                    COALESCE(m.reg_no_display, ''), ' @ ', 
                    CONCAT(
                    SUBSTRING(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4), 
                    '-', 
                    SUBSTRING(m.diary_no::text, -4)
                    )
                    ) AS case_no,
                    CONCAT(m.pet_name, ' Vs. ', m.res_name) AS cause_title,
                    l.mainhead AS mf,
                    l.board_type AS board_type_mb,
                    l.brd_slno AS item_no,
                    TO_CHAR(l.next_dt, 'DD-MM-YYYY') AS listed_on,
                    COALESCE(
                    (
                    SELECT 
                        STRING_AGG(
                        CASE 
                        WHEN judge.jtype = 'R' THEN 'Registrar'
                        ELSE judge.abbreviation
                        END, '#'
                        )
                    FROM  master.judge
                    WHERE POSITION(judge.jcode::text IN STRING_AGG(rj.judge_id::text, ',')) > 0
                    ), ''
                    ) AS listed_before,
                    CASE 
                    WHEN s.category_sc_old IS NOT NULL AND s.category_sc_old != '' AND s.category_sc_old != '0' THEN 
                    CONCAT('(', s.category_sc_old, ')', s.sub_name1, '-', s.sub_name4) 
                    ELSE 
                    CONCAT('(', CONCAT(s.subcode1, '', s.subcode2), ')', s.sub_name1, '-', s.sub_name4) 
                    END AS subject_category
                    FROM (
                    SELECT 
                    t1.*, 
                    MAX(l.ent_dt) AS max_ent_dt
                    FROM (
                    SELECT diary_no, next_dt, ent_dt 
                    FROM heardt 
                    WHERE module_id = 12
                    UNION
                    SELECT diary_no, next_dt, ent_dt 
                    FROM last_heardt 
                    WHERE module_id = 12 
                    AND (bench_flag = '' OR bench_flag IS NULL)
                    ) t1
                    INNER JOIN last_heardt l ON l.diary_no = t1.diary_no
                    WHERE l.ent_dt <= t1.ent_dt 
                    AND l.clno > 0 
                    AND l.brd_slno > 0
                    GROUP BY 
                    l.diary_no, 
                    t1.diary_no, 
                    t1.next_dt, 
                    t1.ent_dt
                    ) t
                    LEFT JOIN last_heardt l ON l.diary_no = t.diary_no AND l.ent_dt = t.max_ent_dt
                    INNER JOIN main m ON m.diary_no = l.diary_no
                    LEFT JOIN master.roster r ON r.id = l.roster_id
                    LEFT JOIN master.roster_bench rb ON rb.id = r.bench_id
                    LEFT JOIN master.master_bench mb ON mb.id = rb.bench_id
                    LEFT JOIN master.roster_judge rj ON rj.roster_id = r.id
                    LEFT JOIN mul_category mcat ON m.diary_no = mcat.diary_no AND mcat.display = 'Y'
                    LEFT JOIN master.submaster s ON mcat.submaster_id = s.id AND s.display = 'Y'
                    LEFT JOIN drop_note dn ON dn.diary_no = m.diary_no AND dn.cl_date = l.next_dt
                    WHERE 
                    dn.diary_no IS NULL 
                    AND l.clno > 0 
                    AND l.next_dt = '$list_dt_db' 
                    AND (
                    l.diary_no = l.conn_key 
                    OR l.conn_key IS NULL 
                    OR l.conn_key = 0
                    )
                    GROUP BY 
                    m.diary_no, 
                    l.next_dt, 
                    l.diary_no, 
                    m.reg_no_display, 
                    m.pet_name, 
                    m.res_name, 
                    l.mainhead, 
                    l.board_type, 
                    l.brd_slno, 
                    s.category_sc_old, 
                    s.sub_name1, 
                    s.sub_name4, 
                    s.subcode1, 
                    s.subcode2;";

            $query = $this->db->query($sql);
            $result = $query->getResultArray();
            return $result;
        }
        if ($datetype == 2) {
            $sql = "SELECT
                CONCAT(COALESCE(m.reg_no_display, ''), ' @ ', CONCAT(SUBSTRING(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4), '-', SUBSTRING(m.diary_no::text, -4))) AS case_no,
                CONCAT(m.pet_name, ' Vs. ', m.res_name) AS cause_title,
                d.mf,
                mb.board_type_mb,
                d.clno AS item_no,
                TO_CHAR(d.cl_date, 'DD-MM-YYYY') AS listed_on,
                COALESCE(
                    (
                    SELECT STRING_AGG(
                        CASE 
                        WHEN judge.jtype = 'R' THEN 'Registrar'
                        ELSE abbreviation
                        END, '#'
                    )
                    FROM master.judge
                    WHERE POSITION(judge.jcode::text IN STRING_AGG(rj.judge_id::text, ',')) > 0
                    ), ''
                ) AS listed_before,
                d.nrs AS reason,
                CASE 
                    WHEN s.category_sc_old IS NOT NULL AND s.category_sc_old != '' AND s.category_sc_old != '0' THEN 
                    CONCAT('(', s.category_sc_old, ')', s.sub_name1, '-', s.sub_name4) 
                    ELSE 
                    CONCAT('(', s.subcode1, s.subcode2, ')', s.sub_name1, '-', s.sub_name4) 
                END AS subject_category
                FROM
                drop_note d
                INNER JOIN 
                main m ON m.diary_no = d.diary_no
                LEFT JOIN 
                heardt h ON h.diary_no = m.diary_no
                LEFT JOIN 
                master.roster r ON r.id = d.roster_id
                LEFT JOIN 
                master.roster_bench rb ON rb.id = r.bench_id
                LEFT JOIN 
                 master.master_bench mb ON mb.id = rb.bench_id
                LEFT JOIN 
                master.roster_judge rj ON rj.roster_id = r.id
                LEFT JOIN 
                mul_category mcat ON m.diary_no = mcat.diary_no AND mcat.display = 'Y'
                LEFT JOIN 
                master.submaster s ON mcat.submaster_id = s.id AND s.display = 'Y'
                WHERE 
                d.cl_date = '$list_dt_db'
                AND d.display = 'Y' 
                AND (m.diary_no = m.conn_key::int OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
                GROUP BY 
                d.diary_no, m.reg_no_display, m.pet_name, m.res_name, d.mf, mb.board_type_mb, d.clno, d.cl_date, d.nrs,
                s.category_sc_old, s.sub_name1, s.sub_name4, s.subcode1, s.subcode2,m.diary_no,r.courtno
                ORDER BY 
                r.courtno, mb.board_type_mb, d.clno";
            $query = $this->db->query($sql);
            $result = $query->getResultArray();
            return $result;
        }
    }

    public function imp_ias_pending_get_data($ten_sect, $section1, $sec_id)
    {
        if ($sec_id != '0') {
            $ten_sect = " tentative_section(m.diary_no) = '$sec_id' AND ";
        }

        $sql = "SELECT 
                    ROW_NUMBER() OVER () AS SNO,
                    c.*
                FROM (
                    SELECT 
                        m.diary_no AS Diary_No,
                        COALESCE(m.conn_key, '') AS main_case_diary,
                        m.reg_no_display AS Case_NO,
                        CONCAT(pet_name, ' Vs. ', res_name) AS Cause_title,
                        TO_CHAR(h.next_dt, 'DD-MM-YYYY') AS Next_Listing_Dt,
                        TO_CHAR(d.ent_dt, 'DD-MM-YYYY') AS IA_Date1,
                        dm.docdesc,
                        tentative_section(m.diary_no) AS Section,
                        tentative_da(m.diary_no) AS DA
                    FROM 
                        heardt h
                    INNER JOIN main m ON m.diary_no = h.diary_no 
                    LEFT JOIN docdetails d ON d.diary_no = m.diary_no 
                        AND d.display = 'Y' 
                        AND d.iastat = 'P' 
                        AND d.doccode = 8 
                        AND d.doccode1 IN (
                            7, 66, 29, 56, 57, 28, 103, 133, 226, 309, 
                            73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 
                            41, 49, 71, 72, 102, 118, 131, 211, 309
                        ) 
                    LEFT JOIN master.docmaster dm ON dm.doccode = d.doccode 
                        AND dm.doccode1 = d.doccode1 
                        AND dm.display = 'Y' 
                    LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no 
                        AND mc.display = 'Y' 
                    LEFT JOIN master.submaster s ON mc.submaster_id = s.id 
                        AND s.display = 'Y' 
                    LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no 
                        AND rd.remove_def = 'N' 
                    WHERE $ten_sect
                         d.diary_no IS NOT NULL 
                        AND rd.fil_no IS NULL 
                        AND h.listorder != 32 
                        AND mc.diary_no IS NOT NULL 
                        AND m.c_status = 'P' 
                        AND h.board_type = 'J' 
                        AND h.mainhead = 'M' 
                        AND h.main_supp_flag = 0 
                    GROUP BY 
                       m.diary_no, h.next_dt,d.ent_dt,dm.docdesc,d.doccode1 
                    ORDER BY 
                        tentative_section(m.diary_no), 
                        tentative_da(m.diary_no), 
                        d.ent_dt) c";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

    public function pending_not_ready_process_data()
    {
        $sql = "SELECT 
  SUM(
    CASE WHEN mf_active != 'F' 
    AND board_type IN ('C') 
    AND (
      temp.conn_key = temp.diary_no :: text 
      OR temp.conn_key = '0' 
      OR temp.conn_key = '' 
      OR temp.conn_key is null
    ) THEN 1 ELSE 0 END
  ) chamber_not_ready_main, 
  SUM(
    CASE WHEN mf_active != 'F' 
    AND board_type IN ('C') 
    AND (
      temp.conn_key != temp.diary_no :: text 
      AND temp.conn_key > '0'
    ) THEN 1 ELSE 0 END
  ) chamber_not_ready_conn, 
  SUM(
    CASE WHEN mf_active != 'F' 
    AND board_type IN ('C') THEN 1 ELSE 0 END
  ) chamber_not_ready, 
  SUM(
    CASE WHEN mf_active != 'F' 
    AND board_type IN ('R') 
    AND (
      temp.conn_key = temp.diary_no :: text 
      OR temp.conn_key = '0' 
      OR temp.conn_key = '' 
      OR temp.conn_key is null
    ) THEN 1 ELSE 0 END
  ) Registrar_not_ready_main, 
  SUM(
    CASE WHEN mf_active != 'F' 
    AND board_type IN ('R') 
    AND (
      temp.conn_key != temp.diary_no :: text 
      AND temp.conn_key > '0'
    ) THEN 1 ELSE 0 END
  ) Registrar_not_ready_conn, 
  SUM(
    CASE WHEN mf_active != 'F' 
    AND board_type IN ('R') THEN 1 ELSE 0 END
  ) Registrar_not_ready, 
  SUM(
    CASE WHEN mf_active != 'F' 
    AND (main_supp_flag = 3) 
    AND board_type IN ('J') 
    AND (
      temp.conn_key = temp.diary_no :: text 
      OR temp.conn_key = '0' 
      OR temp.conn_key = '' 
      OR temp.conn_key is null
    ) THEN 1 ELSE 0 END
  ) misc_not_ready_main, 
  SUM(
    CASE WHEN mf_active != 'F' 
    AND main_supp_flag = 3 
    AND board_type IN ('J') 
    AND (
      temp.conn_key != temp.diary_no :: text 
      AND temp.conn_key > '0'
    ) THEN 1 ELSE 0 END
  ) misc_not_ready_conn, 
  SUM(
    CASE WHEN mf_active != 'F' 
    AND main_supp_flag = 3 
    AND board_type IN ('J') THEN 1 ELSE 0 END
  ) misc_not_ready, 
  SUM(
    CASE WHEN mf_active != 'F' 
    AND (
      (
        main_supp_flag IN (1, 2) 
        AND next_dt < CURRENT_DATE
      ) 
      OR (
        diary_no :: text != conn_key 
        AND list = 'N' 
        AND main_supp_flag != 3 
        AND NOT (
          main_supp_flag IN (1, 2) 
          AND next_dt < CURRENT_DATE
        )
      ) 
      OR main_supp_flag > 3
    ) 
    AND board_type IN ('J') 
    AND (
      temp.conn_key = temp.diary_no :: text 
      OR temp.conn_key = '0' 
      OR temp.conn_key = '' 
      OR temp.conn_key IS NULL
    ) THEN 1 ELSE 0 END
  ) AS misc_updation_awaited_main, 
  SUM(
    CASE WHEN mf_active != 'F' 
    AND (
      (
        main_supp_flag IN (1, 2) 
        AND next_dt < CURRENT_DATE
      ) 
      OR (
        diary_no :: text != conn_key 
        AND list = 'N' 
        AND main_supp_flag != 3 
        AND NOT (
          main_supp_flag IN (1, 2) 
          AND next_dt < CURRENT_DATE
        )
      ) 
      OR main_supp_flag > 3
    ) 
    AND board_type IN ('J') 
    AND temp.conn_key != temp.diary_no :: text 
    AND temp.conn_key > '0' THEN 1 ELSE 0 END
  ) AS misc_updation_awaited_conn, 
  SUM(
    CASE WHEN mf_active != 'F' 
    AND (
      (
        main_supp_flag IN (1, 2) 
        AND next_dt < CURRENT_DATE
      ) 
      OR (
        diary_no :: text != conn_key 
        AND list = 'N' 
        AND main_supp_flag != 3 
        AND NOT (
          main_supp_flag IN (1, 2) 
          AND next_dt < CURRENT_DATE
        )
      ) 
      OR main_supp_flag > 3
    ) 
    AND board_type IN ('J') THEN 1 ELSE 0 END
  ) AS misc_updation_awaited, 
  SUM(
    CASE WHEN mf_active != 'F' 
    AND (
      (
        main_supp_flag IN (1, 2) 
        AND next_dt < CURRENT_DATE
      ) 
      OR (
        diary_no :: text != conn_key 
        AND list = 'N' 
        AND main_supp_flag != 3 
        AND NOT (
          main_supp_flag IN (1, 2) 
          AND next_dt < CURRENT_DATE
        )
      ) 
      OR main_supp_flag >= 3
    ) 
    AND board_type IN ('J') 
    AND (
      temp.conn_key = temp.diary_no :: text 
      OR temp.conn_key = '0' 
      OR temp.conn_key = '' 
      OR temp.conn_key IS NULL
    ) THEN 1 ELSE 0 END
  ) AS misc_total_main, 
  SUM(
    CASE WHEN mf_active != 'F' 
    AND (
      (
        main_supp_flag IN (1, 2) 
        AND next_dt < CURRENT_DATE
      ) 
      OR (
        diary_no :: text != conn_key 
        AND list = 'N' 
        AND main_supp_flag != 3 
        AND NOT (
          main_supp_flag IN (1, 2) 
          AND next_dt < CURRENT_DATE
        )
      ) 
      OR main_supp_flag >= 3
    ) 
    AND board_type IN ('J') 
    AND (
      temp.conn_key != temp.diary_no :: text 
      AND temp.conn_key > '0'
    ) THEN 1 ELSE 0 END
  ) AS misc_total_conn, 
  SUM(
    CASE WHEN mf_active != 'F' 
    AND (
      (
        main_supp_flag IN (1, 2) 
        AND next_dt < CURRENT_DATE
      ) 
      OR (
        diary_no :: text != conn_key 
        AND list = 'N' 
        AND main_supp_flag != 3 
        AND NOT (
          main_supp_flag IN (1, 2) 
          AND next_dt < CURRENT_DATE
        )
      ) 
      OR main_supp_flag >= 3
    ) 
    AND board_type IN ('J') THEN 1 ELSE 0 END
  ) AS misc_total, 
  SUM(
    CASE WHEN mf_active = 'F' 
    AND main_supp_flag = 3 
    AND board_type IN ('J', 'C', 'R') 
    AND (
      temp.conn_key = temp.diary_no :: text 
      OR temp.conn_key = '0' 
      OR temp.conn_key = '' 
      OR temp.conn_key is null
    ) THEN 1 ELSE 0 END
  ) final_not_ready_main, 
  SUM(
    CASE WHEN mf_active = 'F' 
    AND main_supp_flag = 3 
    AND board_type IN ('J', 'C', 'R') 
    AND (
      temp.conn_key != temp.diary_no :: text 
      AND temp.conn_key > '0'
    ) THEN 1 ELSE 0 END
  ) final_not_ready_conn, 
  SUM(
    CASE WHEN mf_active = 'F' 
    AND main_supp_flag = 3 
    AND board_type IN ('J', 'C', 'R') THEN 1 ELSE 0 END
  ) final_not_ready, 
  SUM(
    CASE WHEN mf_active = 'F' 
    AND (
      (
        main_supp_flag IN (1, 2) 
        AND next_dt < CURRENT_DATE
      ) 
      OR (
        diary_no :: text != conn_key 
        AND list = 'N' 
        AND main_supp_flag != 3 
        AND NOT (
          main_supp_flag IN (1, 2) 
          AND next_dt < CURRENT_DATE
        )
      ) 
      OR main_supp_flag > 3
    ) 
    AND board_type IN ('J', 'C', 'R') 
    AND (
      temp.conn_key = temp.diary_no :: text 
      OR temp.conn_key = '0' 
      OR temp.conn_key = '' 
      OR temp.conn_key IS NULL
    ) THEN 1 ELSE 0 END
  ) AS final_updation_awaited_main, 
  SUM(
    CASE WHEN mf_active = 'F' 
    AND (
      (
        main_supp_flag IN (1, 2) 
        AND next_dt < CURRENT_DATE
      ) 
      OR (
        diary_no :: text != conn_key 
        AND list = 'N' 
        AND main_supp_flag != 3 
        AND NOT (
          main_supp_flag IN (1, 2) 
          AND next_dt < CURRENT_DATE
        )
      ) 
      OR main_supp_flag > 3
    ) 
    AND board_type IN ('J', 'C', 'R') 
    AND (
      temp.conn_key != temp.diary_no :: text 
      AND temp.conn_key > '0'
    ) THEN 1 ELSE 0 END
  ) AS final_updation_awaited_conn, 
  SUM(
    CASE WHEN mf_active = 'F' 
    AND (
      (
        main_supp_flag IN (1, 2) 
        AND next_dt < CURRENT_DATE
      ) 
      OR (
        diary_no :: text != conn_key 
        AND list = 'N' 
        AND main_supp_flag != 3 
        AND NOT (
          main_supp_flag IN (1, 2) 
          AND next_dt < CURRENT_DATE
        )
      ) 
      OR main_supp_flag > 3
    ) 
    AND board_type IN ('J', 'C', 'R') THEN 1 ELSE 0 END
  ) AS final_updation_awaited, 
  SUM(
    CASE WHEN mf_active = 'F' 
    AND (
      (
        main_supp_flag IN (1, 2) 
        AND next_dt < CURRENT_DATE
      ) 
      OR (
        diary_no :: text != conn_key 
        AND list = 'N' 
        AND main_supp_flag != 3 
        AND NOT (
          main_supp_flag IN (1, 2) 
          AND next_dt < CURRENT_DATE
        )
      ) 
      OR main_supp_flag >= 3
    ) 
    AND board_type IN ('J', 'C', 'R') 
    AND (
      temp.conn_key = temp.diary_no :: text 
      OR temp.conn_key = '0' 
      OR temp.conn_key = '' 
      OR temp.conn_key IS NULL
    ) THEN 1 ELSE 0 END
  ) AS final_total_main, 
  SUM(
    CASE WHEN mf_active = 'F' 
    AND (
      (
        main_supp_flag IN (1, 2) 
        AND next_dt < CURRENT_DATE
      ) 
      OR (
        diary_no :: text != conn_key 
        AND list = 'N' 
        AND main_supp_flag != 3 
        AND NOT (
          main_supp_flag IN (1, 2) 
          AND next_dt < CURRENT_DATE
        )
      ) 
      OR main_supp_flag >= 3
    ) 
    AND board_type IN ('J', 'C', 'R') 
    AND (
      temp.conn_key != temp.diary_no :: text 
      AND temp.conn_key > '0'
    ) THEN 1 ELSE 0 END
  ) AS final_total_conn, 
  SUM(
    CASE WHEN mf_active = 'F' 
    AND (
      (
        main_supp_flag IN (1, 2) 
        AND next_dt < CURRENT_DATE
      ) 
      OR (
        diary_no :: text != conn_key 
        AND list = 'N' 
        AND main_supp_flag != 3 
        AND NOT (
          main_supp_flag IN (1, 2) 
          AND next_dt < CURRENT_DATE
        )
      ) 
      OR main_supp_flag >= 3
    ) 
    AND board_type IN ('J', 'C', 'R') THEN 1 ELSE 0 END
  ) AS final_total 
FROM 
  (
    SELECT 
      DISTINCT a.diary_no, 
      a.conn_key, 
      next_dt, 
      mf_active, 
      main_supp_flag, 
      board_type, 
      case_grp, 
      fil_dt, 
      c.list 
    FROM 
      (
        SELECT 
          m.diary_no, 
          m.conn_key, 
          h.next_dt, 
          m.fil_dt, 
          c_status, 
          d.rj_dt, 
          d.month, 
          d.year, 
          d.disp_dt, 
          active_casetype_id, 
          casetype_id, 
          m.mf_active, 
          h.main_supp_flag, 
          h.board_type, 
          m.case_grp 
        FROM 
          main m 
          LEFT JOIN heardt h ON m.diary_no = h.diary_no 
          LEFT JOIN dispose d ON m.diary_no = d.diary_no 
          LEFT JOIN restored r ON m.diary_no = r.diary_no 
        WHERE 
          1 = 1 
          AND h.board_type IN ('J', 'C', 'R') 
          AND (
            CASE WHEN DATE(r.disp_dt) IS NOT NULL 
            AND r.disp_dt IS NOT NULL 
            AND DATE(r.conn_next_dt) IS NOT NULL 
            AND r.conn_next_dt IS NOT NULL THEN CURRENT_DATE NOT BETWEEN DATE(r.disp_dt) 
            AND DATE(conn_next_dt) ELSE DATE(r.disp_dt) IS NULL 
            OR r.disp_dt IS NULL 
            OR DATE(r.conn_next_dt) IS NULL 
            OR r.conn_next_dt IS NULL END 
            OR r.fil_no IS NULL
          ) 
          AND (
            CASE WHEN DATE(r.unreg_fil_dt) IS NOT NULL 
            AND (
              DATE(r.unreg_fil_dt) <= DATE(m.fil_dt) 
              OR DATE(m.fil_dt) IS NULL
            ) THEN DATE(r.unreg_fil_dt) <= CURRENT_DATE ELSE DATE(m.fil_dt) <= CURRENT_DATE 
            AND DATE(fil_dt) IS NOT NULL END
          ) 
          AND c_status = 'P' 
          OR (
            c_status = 'D' 
            AND (
              CASE WHEN DATE(d.rj_dt) IS NOT NULL THEN DATE(d.rj_dt) >= CURRENT_DATE 
              AND DATE(d.rj_dt) >= '1950-01-01' 
              AND NOT (
                DATE(d.rj_dt) > CURRENT_DATE
              ) WHEN DATE(d.disp_dt) IS NOT NULL THEN DATE(d.disp_dt) >= CURRENT_DATE 
              AND DATE(d.disp_dt) >= '1950-01-01' 
              AND NOT (
                DATE(d.disp_dt) > CURRENT_DATE
              ) ELSE TO_DATE(
                d.year || '-' || LPAD(d.month :: TEXT, 2, '0') || '-01', 
                'YYYY-MM-DD'
              ) >= CURRENT_DATE 
              AND DATE(d.disp_dt) >= '1950-01-01' 
              AND NOT (
                DATE(d.disp_dt) > CURRENT_DATE
              ) END
            ) 
            AND (
              CASE WHEN DATE(r.unreg_fil_dt) IS NOT NULL 
              AND (
                DATE(r.unreg_fil_dt) <= DATE(m.fil_dt) 
                OR DATE(m.fil_dt) IS NULL
              ) THEN DATE(r.unreg_fil_dt) <= CURRENT_DATE ELSE DATE(m.fil_dt) <= CURRENT_DATE 
              AND DATE(fil_dt) IS NOT NULL END
            ) 
            AND CASE WHEN DATE(r.disp_dt) IS NOT NULL 
            AND r.disp_dt IS NOT NULL 
            AND DATE(r.conn_next_dt) IS NOT NULL 
            AND r.conn_next_dt IS NOT NULL THEN CURRENT_DATE NOT BETWEEN DATE(r.disp_dt) 
            AND DATE(conn_next_dt) ELSE DATE(r.disp_dt) IS NULL 
            OR r.disp_dt IS NULL 
            OR DATE(r.conn_next_dt) IS NULL 
            OR r.conn_next_dt IS NULL END
          ) 
          AND (
            CAST(
              COALESCE(
                NULLIF(
                  SUBSTRING(
                    m.fil_no 
                    FROM 
                      1 FOR 2
                  ), 
                  ''
                ), 
                '0'
              ) AS INTEGER
            ) NOT IN (39)
          ) 
          OR m.fil_no = '' 
          OR m.fil_no IS NULL 
        GROUP BY 
          m.diary_no, 
          h.next_dt, 
          d.rj_dt, 
          d.month, 
          d.year, 
          d.disp_dt, 
          h.main_supp_flag, 
          h.board_type
      ) a 
      left join conct c on c.diary_no = a.diary_no
  ) temp";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

    public function get_inperson($mainhead, $ucode, $usertype, $sec_id, $board_type)
    {
        $sec_id2 = '';
        if ($usertype == '14') {
            $sq_u = "SELECT STRING_AGG(u2.usercode::TEXT, ',') AS allda FROM master.users u LEFT JOIN master.users u2 ON u2.section = u.section WHERE u.display = 'Y' AND u.usercode = '$ucode' group by u2.section";
            $re_u = $this->db->query($sq_u);
            $ro_u = $re_u->getResultArray();
            $all_da = $ro_u[0]['allda'];
            $mdacode = "AND m.dacode IN ($all_da)";
        } else if ($usertype == '17' or $usertype == '50' or $usertype == '51') {
            $mdacode = "AND m.dacode = '$ucode'";
        } else {
            $mdacode = "";
        }
        if ($board_type == "0") {
            $board_type = "";
        } else {
            $board_type = "AND h.board_type = '" . $board_type . "'";
        }

        if ($sec_id == "0") {
            $sec_id = "";
        } else {
            $sec_id = "AND us.id = '" . $sec_id . "'";
            $sec_id2 = "AND us.id is not null";
        }


        $sql = "SELECT u.name, 
                us.section_name, 
                l.purpose, 
                c1.short_description, 
                EXTRACT(YEAR FROM m.active_fil_dt) AS fyr,
                active_reg_year, 
                active_fil_dt, 
                active_fil_no, 
                m.pet_name, 
                m.res_name, 
                m.pno, 
                m.rno, 
                casetype_id, 
                ref_agency_state_id, 
                diary_no_rec_date, 
                h.* 
                FROM 
                heardt h 
                INNER JOIN main m ON m.diary_no = h.diary_no 
                LEFT JOIN master.casetype c1 ON m.active_casetype_id = c1.casecode 
                INNER JOIN master.listing_purpose l ON l.code = h.listorder 
                AND l.display = 'Y' 
                LEFT JOIN master.users u ON u.usercode = m.dacode 
                AND u.display = 'Y' 
                LEFT JOIN master.usersection us ON us.id = u.section $sec_id
                WHERE 
                m.pet_adv_id = '584' 
                and h.mainhead = '$mainhead' $mdacode $board_type $sec_id2
                AND m.c_status = 'P' 
                and h.main_supp_flag = 0 
                ORDER BY 
                CASE WHEN us.section_name IS NULL THEN 9999 ELSE 0 END ASC, 
                us.section_name, 
                RIGHT(h.diary_no::text, 4), 
                LEFT(m.diary_no::text, LENGTH(m.diary_no::text) - 4) ASC";
        // pr($sql);
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

    public function advocatesql($diary_no)
    {
        $advsql = "SELECT 
                    a.*,
                    STRING_AGG(
                        a.name || '' || 
                        CASE WHEN pet_res = 'R' THEN grp_adv ELSE '' END, 
                        ',' ORDER BY adv_type DESC, pet_res_no ASC
                    ) AS r_n,
                    STRING_AGG(
                        a.name || '' || 
                        CASE WHEN pet_res = 'P' THEN grp_adv ELSE '' END, 
                        ',' ORDER BY adv_type DESC, pet_res_no ASC
                    ) AS p_n
                    FROM (
                    SELECT 
                        a.diary_no, 
                        b.name, 
                        STRING_AGG(COALESCE(a.adv, ''), ',' ORDER BY pet_res ASC, adv_type DESC, pet_res_no ASC) AS grp_adv,
                        a.pet_res, 
                        a.adv_type, 
                        pet_res_no
                    FROM advocate a
                    LEFT JOIN bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y'
                    WHERE 
                        WHERE a.diary_no='" . $diary_no . "'
                        a.display = 'Y'
                    GROUP BY a.diary_no, b.name, a.pet_res, a.adv_type, pet_res_no
                    ORDER BY pet_res ASC, adv_type DESC, pet_res_no ASC
                    ) a
                    GROUP BY a.diary_no,a.name,a.grp_adv,a.pet_res,a.adv_type,a.pet_res_no";

        $query =  $this->db->query($advsql);
        $resultsadv = $query->getRowArray();
        return $resultsadv;
    }

    public function category_process_data($include_defects, $include_details, $for_date, $tdt1, $dt1)
    {

        if ($include_defects == 1) {
            $defects = 'Y';
        }
        if ($include_defects == 2) {
            $defects = 'N';
        }

        if ($include_details == 1) {
            $sql_cat = "WITH temp_data AS (
                    SELECT DISTINCT
                        p.*
                    FROM
                        pendency_report p
                    WHERE
                        p.ent_dt::date = '$dt1'
                        AND p.include_defect = '$defects'
                        AND p.display = 'Y'
                    ),
                    processed_data AS (
                    SELECT
                        s.id AS sid,
                        s.sub_name1 AS main_name,
                        CONCAT(s.subcode1::text, 
                            CASE WHEN LENGTH(s.subcode2::text) = 1 THEN '0' ELSE '' END, 
                            s.subcode2::text) AS full_code,
                        COALESCE(
                        CASE
                            WHEN s.category_sc_old IS NOT NULL 
                            AND s.category_sc_old != '' 
                            AND s.category_sc_old::integer != 0 
                            THEN s.sub_name1 || '-' || s.sub_name4
                            ELSE s.sub_name4
                        END,
                        'WITHOUT CATEGORY'
                        ) AS sub_name1,
                        COALESCE(s.subcode1, 99)::integer AS subcode1,
                        COALESCE(s.subcode2::integer, 0) AS subcode2,
                        temp.*
                    FROM
                        temp_data temp
                    LEFT JOIN master.submaster s 
                        ON s.id = temp.submaster_id
                        AND s.display = 'Y'
                        AND s.subcode1 != 146
                    ),
                    aggregated_data AS (
                    SELECT
                        sub_name1,
                        main_name,
                        subcode1 AS org_subcode1,
                        subcode2,
                        full_code AS subcode1,
                        SUM(main) AS main,
                        SUM(conn) AS conn,
                        SUM(main) + SUM(conn) AS pendency,
                        SUM(misc_main) AS misc_main,
                        SUM(misc_conn) AS misc_conn,
                        SUM(misc_main) + SUM(misc_conn) AS misc,
                        SUM(regular_main) AS regular_main,
                        SUM(regular_conn) AS regular_conn,
                        SUM(regular_main) + SUM(regular_conn) AS regular
                    FROM
                        processed_data
                    GROUP BY 
                        sub_name1, 
                        main_name, 
                        subcode1, 
                        subcode2, 
                        full_code
                    )
                    ,
                    row_numbered_data AS (
                    SELECT
                        ROW_NUMBER() OVER (ORDER BY 
                        CASE WHEN org_subcode1 = 99 THEN 1 ELSE 0 END,
                        org_subcode1,
                        subcode2
                        ) AS SNO,
                        *
                    FROM
                        aggregated_data
                    )
                    SELECT
                    *
                    FROM
                    row_numbered_data
                    ORDER BY
                    CASE WHEN org_subcode1 = 99 THEN SNO ELSE 1 END ASC,
                    org_subcode1,
                    subcode2,
                    org_subcode1
                    ";

            $query =  $this->db->query($sql_cat);
            $result = $query->getResultArray();
            return $result;
        }
        if ($include_details == 2) {
            $sql_cat = "SELECT 
                    a.sub_name1 AS sub_name1, 
                    a.subcode1 AS subcode1,
                    SUM(main) AS main, 
                    SUM(conn) AS conn, 
                    (SUM(main) + SUM(conn)) AS pendency,
                    SUM(misc_main) AS misc_main, 
                    SUM(misc_conn) AS misc_conn, 
                    (SUM(misc_main) + SUM(misc_conn)) AS misc,
                    SUM(regular_main) AS regular_main, 
                    SUM(regular_conn) AS regular_conn, 
                    (SUM(regular_main) + SUM(regular_conn)) AS regular
                    FROM (
                    SELECT 
                        COALESCE(s.sub_name1, 'WITHOUT CATEGORY') AS sub_name1,
                        COALESCE(CAST(s.subcode1 AS INTEGER), 999) AS subcode1,
                        p.main, p.conn, p.misc_main, p.misc_conn, p.regular_main, p.regular_conn
                    FROM (
                        SELECT DISTINCT 
                        p.main, p.conn, p.misc_main, p.misc_conn, p.regular_main, p.regular_conn, p.submaster_id
                        FROM pendency_report p
                        WHERE DATE(p.ent_dt) = '$dt1' 
                        AND p.include_defect = '$defects'
                        AND p.display = 'Y'
                    ) p
                    LEFT JOIN master.submaster s 
                        ON s.id = p.submaster_id 
                        AND s.display = 'Y' 
                        AND s.subcode1 != 146
                    ) a
                    GROUP BY ROLLUP(a.sub_name1, a.subcode1)
                    ORDER BY a.subcode1 ASC";

            $query =  $this->db->query($sql_cat);
            $result = $query->getResultArray();
            return $result;
        }
    }
}
