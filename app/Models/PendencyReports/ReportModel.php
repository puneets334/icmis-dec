<?php

namespace App\Models\PendencyReports;
use CodeIgniter\Model;

class ReportModel extends Model
{

    protected $db;

    public function __construct(){
       parent::__construct();
       $this->db = \Config\Database::connect();
	  
     }
	 
	 
	 public function report_DA(){
		 
		$sql = "SELECT 
						a.da,
						u.name,
						section_name,
						empid,
						COALESCE(total_pending, 0) AS total_pending_metters,
						COALESCE(total_pending_verified, 0) AS verified,
						COALESCE(total_pending_not_verified, 0) AS not_with_me,
						COALESCE(total_not_verified, 0) AS not_verified
					FROM 
						(
							SELECT 
								dacode AS da,
								COUNT(*) AS total_pending
							FROM 
								main
							WHERE 
								c_status = 'P'
							GROUP BY 
								dacode
						) AS a
					LEFT JOIN
						(
							SELECT 
								dacode AS da,
								COUNT(DISTINCT p.diary_no) AS total_pending_verified
							FROM 
								physical_verify p
							JOIN 
								main m ON m.diary_no = p.diary_no
							WHERE 
								avaliable_flag = 'Y' 
								AND display = 'Y' 
								AND c_status = 'P'
							GROUP BY 
								dacode
						) AS b ON a.da = b.da
					LEFT JOIN
						(
							SELECT 
								dacode AS da,
								COUNT(DISTINCT p.diary_no) AS total_pending_not_verified
							FROM 
								physical_verify p
							JOIN 
								main m ON m.diary_no = p.diary_no
							WHERE 
								avaliable_flag = 'N' 
								AND display = 'Y' 
								AND c_status = 'P'
							GROUP BY 
								dacode
						) AS c ON a.da = c.da
					LEFT JOIN
						(
							SELECT  
								dacode AS da,
								COUNT(1) AS total_not_verified 
							FROM 
								main 
							WHERE 
								c_status = 'P' 
								AND diary_no NOT IN (SELECT diary_no FROM physical_verify) 
							GROUP BY 
								dacode
						) AS d ON a.da = d.da
					LEFT JOIN 
						master.users u ON u.usercode = a.da
					LEFT JOIN 
						master.usersection us ON us.id = u.section
					ORDER BY 
						section_name";

					$query = $this->db->query($sql);
					return $query->getResultArray();
	 }
	 
	 
	 function report_Section() {

			$sql = "SELECT 
						us.section_name,
						COALESCE(total_pending, 0) AS total_pending_metters,
						COALESCE(total_pending_verified, 0) AS verified,
						COALESCE(total_pending_not_verified, 0) AS not_with_me,
						COALESCE(total_not_verified, 0) AS not_verified
					FROM 
						(
							SELECT 
								CASE WHEN us.id IS NULL THEN 0 ELSE us.id END AS da,
								COUNT(*) AS total_pending
							FROM
								main m
							LEFT JOIN 
								master.users u ON u.usercode = m.dacode
							LEFT JOIN 
								master.usersection us ON us.id = u.section
							WHERE 
								m.c_status = 'P'
							GROUP BY 
								us.id
						) AS a
					LEFT JOIN
						(
							SELECT 
								CASE WHEN us.id IS NULL THEN 0 ELSE us.id END AS da,
								COUNT(DISTINCT p.diary_no) AS total_pending_verified
							FROM 
								physical_verify p
							JOIN 
								main m ON m.diary_no = p.diary_no
							LEFT JOIN 
								master.users u ON u.usercode = m.dacode
							LEFT JOIN 
								master.usersection us ON us.id = u.section
							WHERE 
								p.avaliable_flag = 'Y' AND p.display = 'Y' AND m.c_status = 'P'
							GROUP BY 
								us.id
						) AS b ON a.da = b.da
					LEFT JOIN
						(
							SELECT 
								CASE WHEN us.id IS NULL THEN 0 ELSE us.id END AS da,
								COUNT(DISTINCT p.diary_no) AS total_pending_not_verified
							FROM 
								physical_verify p
							JOIN 
								main m ON m.diary_no = p.diary_no
							LEFT JOIN 
								master.users u ON u.usercode = m.dacode
							LEFT JOIN 
								master.usersection us ON us.id = u.section
							WHERE 
								p.avaliable_flag = 'N' AND p.display = 'Y' AND m.c_status = 'P'
							GROUP BY 
								us.id
						) AS c ON a.da = c.da
					LEFT JOIN
						(
							SELECT 
								CASE WHEN us.id IS NULL THEN 0 ELSE us.id END AS da,
								COUNT(1) AS total_not_verified
							FROM 
								main m
							LEFT JOIN 
								master.users u ON u.usercode = m.dacode
							LEFT JOIN 
								master.usersection us ON us.id = u.section
							WHERE 
								m.c_status = 'P' AND m.diary_no NOT IN (SELECT diary_no FROM physical_verify)
							GROUP BY 
								da
						) AS d ON a.da = d.da
					LEFT JOIN 
						master.usersection us ON us.id = a.da
					ORDER BY 
						section_name
					";

					$query = $this->db->query($sql);
					return $query->getResultArray();
	}
	
	
	 function Section_Reg_Report(){
        $sql="select section_name from master.usersection where isda='Y'";
        $query = $this->db->query($sql);
		return $query->getResultArray();
     }
	 
	 public function get_section_pendingIA($section){
          $sql = "SELECT 
						us.section_name AS user_section, 
						u.name AS alloted_to_da,
						SUBSTR(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4) AS diary_no, 
						SUBSTR(m.diary_no::text, -4) AS diary_year,
						m.pet_name, 
						m.res_name, 
						CONCAT(m.reg_no_display, '@ D.No.', SUBSTR(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4), '/', SUBSTR(m.diary_no::text, -4)) AS CaseNo, 
						m.c_status, 
						CONCAT(SUBSTR(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4), '/', SUBSTR(m.diary_no::text, -4)) AS diary_no,
						STRING_AGG(
							CASE 
								WHEN d.doccode = 8 AND d.doccode1 = 19 
									THEN CONCAT(d.docnum, '/', d.docyear, '#->', dm.docdesc, ' - ', d.other1)
								ELSE CONCAT(d.docnum, '/', d.docyear, '#->', dm.docdesc)
							END,
							', '
						) AS ia_name
					FROM 
						main m 
					JOIN 
						docdetails d ON m.diary_no = d.diary_no 
					JOIN 
						master.docmaster dm ON d.doccode = dm.doccode AND d.doccode1 = dm.doccode1 
					LEFT JOIN 
						master.users u ON u.usercode = m.dacode AND (u.display = 'Y' OR u.display IS NULL) 
					LEFT JOIN 
						master.usersection us ON us.id = u.section AND us.display = 'Y' 
					WHERE 
						m.c_status = 'P' 
						AND d.iastat = 'P' 
						AND us.section_name = '$section' 
						AND d.doccode = 8 
					GROUP BY 
						m.diary_no, us.section_name, u.name, m.pet_name, m.res_name, m.reg_no_display, m.c_status
					ORDER BY 
						m.diary_no_rec_date ASC
					";

            $query = $this->db->query($sql);
			return $query->getResultArray();
    }
	
	public function getSections(){
		$sql="select id,section_name from master.usersection where display='Y' AND isda='Y' order by section_name";
        $query = $this->db->query($sql);
		return $query->getResultArray();
	}
	
	function getMainSubjectCategory(){
	    $sql = "SELECT subcode1, MAX(sub_name1) AS sub_name1
				FROM 
					master.submaster
				WHERE 
					(flag_use = 'S' OR flag_use = 'L') 
					AND display = 'Y' 
					AND match_id != 0 
					OR flag = 'S'
				GROUP BY 
					subcode1
				ORDER BY 
					subcode1;
				";
		$query = $this->db->query($sql);
		return $query->getResultArray();	
	}
	
	function get_Sub_SubjectCategory($Mcat) {
		$sql = "SELECT id, subcode1, category_sc_old, sub_name1, sub_name4,
				   CASE 
					   WHEN (category_sc_old IS NOT NULL AND category_sc_old != '' AND category_sc_old ~ '^[0-9]+$' AND CAST(category_sc_old AS INTEGER) != 0)
					   THEN CONCAT('', category_sc_old, '#-#', sub_name4)
					   ELSE CONCAT('', CONCAT(subcode1, '', subcode2), '#-#', sub_name4)
				   END AS dsc 
			FROM master.submaster 
			WHERE subcode1 = $Mcat AND subcode2 != '0'
			GROUP BY id, subcode1, category_sc_old, sub_name1, sub_name4";

		$query = $this->db->query($sql);
		return $query->getResultArray();
    }
	
	function getReg_J1_Reports($category, $section,  $mcat){
		$sql = "";

		if (($category != "" || isset($category)) && ($section != "" || isset($section))) {
			$condition = "sm.id = $category";
			
			if ($category == 0) {
				$condition = "subcode1 = $mcat";
			}

			$sql = "SELECT us.section_name AS user_section, 
						   CASE 
							   WHEN mf_active = 'F' THEN 'Regular' 
							   ELSE 'Misc.' 
						   END AS casestage,
						   aa_subquery.total_connected AS group_count,  
						   CASE 
							   WHEN (m.diary_no = CAST(m.conn_key AS BIGINT) OR CAST(m.conn_key AS BIGINT) = 0 OR m.conn_key = '' OR m.conn_key IS NULL) THEN 'M' 
							   ELSE 'C' 
						   END AS main_or_connected,
						   sm.sub_name1, 
						   CASE 
							   WHEN (category_sc_old IS NOT NULL AND category_sc_old != '' AND CAST(category_sc_old AS BIGINT) != 0) THEN CONCAT('', category_sc_old, ' - ', sub_name4) 
							   ELSE CONCAT('', CONCAT(subcode1, '', subcode2), ' - ', sub_name4) 
						   END AS subject_category,
						   sm.category_sc_old,
						   u.name AS alloted_to_da, 
						   SUBSTR(CAST(m.diary_no AS TEXT), 1, LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS diary_no,  
						   SUBSTR(CAST(m.diary_no AS TEXT), - 4) AS diary_year,  
						   TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS diary_date,  
						   m.pet_name, 
						   m.res_name, 
						   m.reg_no_display, 
						   m.c_status, 
						   CONCAT(m.reg_no_display, '@ D.No.', SUBSTR(CAST(m.diary_no AS TEXT), 1, LENGTH(CAST(m.diary_no AS TEXT)) - 4), '/', SUBSTR(CAST(m.diary_no AS TEXT), - 4)) AS CaseNo,
						   CASE 
							   WHEN h.main_supp_flag = 0 THEN 'Ready' 
							   ELSE 'Not Ready' 
						   END AS Ready_status, 
						   CONCAT(bb.name, '@', bb.mobile) AS pet_adv, 
						   lp.question_of_law,
						   h.next_dt AS Hearing_Date,
						   STRING_AGG(j.abbreviation, '#') AS next_coram  
					FROM main m 
					LEFT JOIN heardt h ON m.diary_no = h.diary_no 
					LEFT JOIN master.users u ON u.usercode = m.dacode AND (u.display = 'Y' OR u.display IS NULL) 
					LEFT JOIN master.usersection us ON us.id = u.section AND us.display = 'Y' 
					LEFT JOIN master.users u1 ON u1.usercode = m.usercode AND u1.display = 'Y' 
					LEFT JOIN advocate aa ON aa.diary_no = m.diary_no AND aa.pet_res = 'P' AND pet_res_no = 1 AND aa.display = 'Y'
					LEFT JOIN master.bar bb ON bb.bar_id = aa.advocate_id
					LEFT JOIN law_points lp ON lp.diary_no = m.diary_no AND lp.display = 'Y' AND lp.is_verified = '1'  
					LEFT JOIN (
						SELECT n.conn_key, COUNT(*) AS total_connected 
						FROM main m 
						INNER JOIN heardt h ON m.diary_no = h.diary_no 
						INNER JOIN main n ON m.diary_no = CAST(n.conn_key AS BIGINT)  
						WHERE n.diary_no != CAST(n.conn_key AS BIGINT) AND m.c_status = 'P'  
						GROUP BY n.conn_key 
					) aa_subquery ON m.diary_no = CAST(aa_subquery.conn_key AS BIGINT)  
					LEFT JOIN master.judge j ON CAST(j.jcode AS TEXT) = ANY(string_to_array(h.coram, ','))  
					INNER JOIN mul_category mc ON mc.diary_no = m.diary_no AND mc.display = 'Y' 
					INNER JOIN master.submaster sm ON mc.submaster_id = sm.id AND (sm.display = 'Y' OR sm.display IS NULL)         
					WHERE m.c_status = 'P' 
					  AND us.id = $section 
					  AND $condition
					GROUP BY us.section_name, sm.sub_name1, sm.category_sc_old, u.name, m.diary_no, m.pet_name, 
							 m.res_name, m.reg_no_display, m.c_status, h.main_supp_flag, bb.name, bb.mobile, lp.question_of_law, 
							 h.next_dt, sm.sub_name4, category_sc_old, subcode1, subcode2, aa_subquery.total_connected";
		}

		$query = $this->db->query($sql);
		return $query->getResultArray();
   
      
    }
    
	 
	 
	 
	 
}
