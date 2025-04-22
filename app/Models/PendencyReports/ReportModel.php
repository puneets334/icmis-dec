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
   
    function Da_Pendency($section=null){
        $sql="select distinct main.dacode as usercode, users.name, count(diary_no) as pendency from main 
						inner join master.users on users.usercode=main.dacode
						where users.section = (select id from master.usersection where section_name='$section')
						and main.c_status='P' and users.display='Y'
						group by main.dacode, users.name;";

        $query=$this->db->query($sql);
        return $query->getResultArray();
    }
	
	function Da_Pendency_result($usercode){
				$sql = "SELECT distinct 
						substr(diary_no::text, 1, length(diary_no::text) - 4) AS diary_number, 
						substr(diary_no::text, -4) as diary_year, 
						reg_no_display, 
						CONCAT(pet_name, ' ', 
							(CASE
								WHEN pno = 2 THEN 'and anr.'
								WHEN pno > 2 THEN 'and ors.'
								ELSE ''
							END),
							' vs ',
							res_name, ' ',
							(CASE
								WHEN rno = 2 THEN 'and anr.'
								WHEN rno > 2 THEN 'and ors.'
								ELSE ''
							END)
						) as cause 
				FROM main 
				WHERE dacode = '$usercode' AND c_status = 'P';";

		$query = $this->db->query($sql);
        return $query->getResultArray();
    }
    
	
	function Not_Listed_Report($sect=null){
		  $sql = "SELECT 
			SUBSTR(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4) AS diaryno,
			SUBSTR(m.diary_no::text, -4) AS diaryyear,
			m.reg_no_display, 
			u.name, 
			us.section_name, 
			mc.mul_category_idd,
			CONCAT(m.pet_name,
				(CASE
					WHEN m.pno = 2 THEN 'and anr.'
					WHEN m.pno > 2 THEN 'and ors.'
					ELSE ''
				END),
				' vs ',
				m.res_name,
				(CASE
					WHEN m.rno = 2 THEN 'and anr.'
					WHEN m.rno > 2 THEN 'and ors.'
					ELSE ''
				END)) AS cause,
			m.active_fil_dt,
			CASE
				WHEN (m.conn_key = '0' OR m.conn_key IS NULL OR m.conn_key = m.diary_no::text)
				THEN 'M'
				ELSE CASE
					WHEN (m.conn_key != '0' AND m.conn_key IS NOT NULL AND m.conn_key != m.diary_no::text)
					THEN 'C'
				END
			END AS mainorconn
		FROM
			main m
			INNER JOIN master.users u ON m.dacode = u.usercode
			INNER JOIN master.usersection us ON u.section = us.id
			INNER JOIN mul_category mc ON m.diary_no = mc.diary_no
		WHERE
			m.c_status = 'P' 
			AND m.fil_no IS NOT NULL
			AND m.fil_no != ''
			AND m.dacode IN (
				SELECT usercode 
				FROM master.users 
				WHERE section = (
					SELECT id 
					FROM master.usersection 
					WHERE section_name = '$sect'
				)
			) 
			AND m.diary_no NOT IN (
				SELECT h.diary_no
				FROM heardt h
				WHERE (h.clno != 0 AND h.clno IS NOT NULL)
					AND (h.brd_slno != 0 AND h.brd_slno IS NOT NULL)
				UNION
				SELECT h.diary_no
				FROM last_heardt h
				WHERE (h.clno != 0 AND h.clno IS NOT NULL)
					AND (h.brd_slno != 0 AND h.brd_slno IS NOT NULL AND 
						(bench_flag = '' OR bench_flag IS NULL))
			)
		ORDER BY m.active_fil_dt;";

        $query = $this->db->query($sql);
		return $query->getResultArray();
    }
	
	
	function CaseType_Count($section=null){

        $sql="SELECT 
				MAX(SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4)) AS diary_number,
				GROUP_CONCAT(DISTINCT TRIM(rs.Name)) AS state,
				GROUP_CONCAT(DISTINCT rc.agency_name) AS agency,
				m.ref_agency_state_id AS stateid,
				MAX(SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3)) AS diary_year,
				COALESCE(c.short_description, 'Total') AS short_description,
				m.active_casetype_id, 
				COUNT(DISTINCT m.diary_no) AS total_pendency
			FROM main m 
			LEFT JOIN master.casetype c ON m.active_casetype_id = c.casecode 
			LEFT JOIN master.ref_agency_code rc ON rc.id = m.ref_agency_code_id 
			LEFT JOIN master.state rs ON rs.id_no = m.ref_agency_state_id AND rs.display = 'Y' 
			LEFT JOIN master.users u ON u.usercode = m.dacode 
			LEFT JOIN master.usersection us ON us.id = u.section 
			WHERE m.c_status = 'P' 
				AND (fil_no IS NOT NULL AND fil_no != '') 
				AND (reg_no_display IS NOT NULL AND reg_no_display != '') 
				AND fil_dt IS NOT NULL
				AND section_name = '$section' 
			GROUP BY short_description, m.ref_agency_state_id,m.active_casetype_id
			ORDER BY total_pendency;";

        $query=$this->db->query($sql);
        return $query->getResultArray();
    }
	
    function UnRegCases_Count($section=null){

        $sql="SELECT 
				MAX(SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4)) AS diary_number,
				STRING_AGG(DISTINCT TRIM(rs.Name), ', ') AS state,
				STRING_AGG(DISTINCT rc.agency_name, ', ') AS agency,
				m.ref_agency_state_id AS stateid,
				MAX(SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3)) AS diary_year,
				'Un-Registered' AS short_description,
				COUNT(DISTINCT m.diary_no) AS total_pendency
			FROM main m 
			LEFT JOIN master.ref_agency_code rc ON rc.id = m.ref_agency_code_id 
			LEFT JOIN master.state rs ON rs.id_no = m.ref_agency_state_id AND rs.display = 'Y' 
			LEFT JOIN master.users u ON u.usercode = m.dacode 
			LEFT JOIN master.usersection us ON us.id = u.section 
			WHERE m.c_status = 'P' 
				AND (fil_no IS NULL OR fil_no = '') 
				AND (reg_no_display IS NULL OR reg_no_display = '') 
				AND section_name = '$section'
			GROUP BY m.ref_agency_state_id, short_description
			ORDER BY total_pendency;";

        $query=$this->db->query($sql);
        return $query->getResultArray();
    }

    function Misc_Reg_Count($section=null){
       $sql="SELECT 
				SUM(CASE WHEN m.mf_active = 'M' THEN 1 ELSE 0 END) AS Misc_count,
				SUM(CASE WHEN m.mf_active = 'F' THEN 1 ELSE 0 END) AS Reg_count,
				COUNT(*) AS total
			FROM main m
			LEFT JOIN master.users u ON u.usercode = m.dacode 
			LEFT JOIN master.usersection us ON us.id = u.section 
			WHERE m.c_status = 'P' 
			  AND section_name = '$section';";

        $query=$this->db->query($sql);
        return $query->getResultArray();
    }
	
	function CaseType_YearWise_Count($section=null,$casetype=null){
        if($casetype == null || $casetype == '' || $casetype == 0) {
			$active_cond = 'm.active_casetype_id = 0,';
			$join_Reg = "";
			$condition = " AND (fil_no IS NULL) AND (reg_no_display IS NULL OR reg_no_display = '')";
		} else {
			$active_cond = "m.active_casetype_id, c.short_description,";
			$join_Reg = "LEFT JOIN master.casetype c ON m.active_casetype_id = c.casecode";
			$condition = "AND (fil_no IS NOT NULL) 
						  AND (reg_no_display IS NOT NULL AND reg_no_display != '') 
						  AND (fil_dt IS NOT NULL) 
						  AND m.active_casetype_id = $casetype";
		}

		$sql = "SELECT 
					$active_cond 
					us.section_name,
					SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3) AS diary_year,
					COUNT(DISTINCT m.diary_no) AS total_pendency 
				FROM main m
				$join_Reg
				LEFT JOIN master.users u ON u.usercode = m.dacode
				LEFT JOIN master.usersection us ON us.id = u.section AND us.display = 'Y'
				WHERE m.c_status = 'P' 
				$condition
				AND section_name = '$section'";
				if ($casetype == null || $casetype == '' || $casetype == 0) {
				$sql.= " GROUP BY diary_year, us.section_name , m.active_casetype_id,m.active_casetype_id";
			}else{
				$sql.= " GROUP BY diary_year, us.section_name , m.active_casetype_id,m.active_casetype_id, c.short_description";
			}	
				
			$sql.= " ORDER BY diary_year;";

		$query = $this->db->query($sql);
         return $query->getResultArray();
    }
	
	function CaseType_StateWise_Count($section=null,$casetype=null) {
        if ($casetype == null || $casetype == '' || $casetype == 0) {
			$active_cond = 'm.active_casetype_id = 0,';
			$join_Reg = "";
			$condition = " AND (fil_no IS NULL) AND (reg_no_display IS NULL OR reg_no_display = '')";
		} else {
			$active_cond = "m.active_casetype_id, c.short_description,";
			$join_Reg = "LEFT JOIN master.casetype c ON m.active_casetype_id = c.casecode";
			$condition = "AND (fil_no IS NOT NULL) 
						  AND (reg_no_display IS NOT NULL AND reg_no_display != '') 
						  AND (fil_dt IS NOT NULL) 
						  AND m.active_casetype_id = $casetype";
		}

		$sql = "SELECT 
					$active_cond 
					us.section_name,
					rc.agency_name,
					m.from_court,
					m.ref_agency_code_id,
					m.ref_agency_state_id AS stateid,
					SUBSTRING(CAST(MAX(m.diary_no) AS TEXT) FROM LENGTH(CAST(MAX(m.diary_no) AS TEXT)) - 3) AS diary_year,
					COUNT(DISTINCT m.diary_no) AS total_pendency
				FROM main m
				$join_Reg
				LEFT JOIN master.ref_agency_code rc ON rc.id = m.ref_agency_code_id
				LEFT JOIN master.state rs ON rs.id_no = m.ref_agency_state_id AND rs.display = 'Y'
				LEFT JOIN master.users u ON u.usercode = m.dacode
				LEFT JOIN master.usersection us ON us.id = u.section
				WHERE m.c_status = 'P' 
				$condition
				AND section_name = '$section'";
			if ($casetype == null || $casetype == '' || $casetype == 0) {
				$sql.= " GROUP BY agency_name, m.active_casetype_id, us.section_name, m.from_court, m.ref_agency_code_id, m.ref_agency_state_id";
			}else{
				$sql.= " GROUP BY agency_name, m.active_casetype_id, us.section_name, m.from_court, m.ref_agency_code_id, m.ref_agency_state_id, c.short_description";
			}	
				
			$sql.= " ORDER BY total_pendency;";

		$query = $this->db->query($sql);

        return $query->getResultArray();
    }
	
	function Total_Pendency($section=null,$type=null){

        if($type=="Diary") {
			$Reg_condition = " AND (fil_no IS NULL OR fil_no = '') AND (reg_no_display IS NULL OR reg_no_display = '')";
		} else if($type != null) {
			$Reg_condition = " AND (fil_no IS NOT NULL AND fil_no != '') AND (reg_no_display IS NOT NULL AND reg_no_display != '') AND (fil_dt IS NOT NULL) AND casecode = $type";
		} else {
			$Reg_condition = " AND (fil_no IS NOT NULL AND fil_no != '') AND (reg_no_display IS NOT NULL AND reg_no_display != '') AND (fil_dt IS NOT NULL)";
		}

		$sql = "SELECT DISTINCT 
					substr(CAST(m.diary_no AS TEXT), 1, length(CAST(m.diary_no AS TEXT)) - 4) AS diary_number, 
					c.short_description, 
					SUBSTRING(m.diary_no::TEXT FROM LENGTH(m.diary_no::TEXT) - 3 FOR 4) AS diary_year,
					m.reg_no_display, 
					rs.Name AS state, 
					CONCAT(m.pet_name, ' ', 
						   (CASE
							   WHEN m.pno = 2 THEN 'and anr.'
							   WHEN m.pno > 2 THEN 'and ors.'
							   ELSE ''
						   END),
						   ' vs ',
						   m.res_name,' ',
						   (CASE
							   WHEN m.rno = 2 THEN 'and anr.'
							   WHEN m.rno > 2 THEN 'and ors.'
							   ELSE ''
						   END)) AS cause, 
					u.name,
					m.active_fil_dt,
					m.diary_no_rec_date
				FROM main m
				LEFT JOIN master.casetype c ON m.active_casetype_id = c.casecode
				LEFT JOIN master.ref_agency_code rc ON rc.id = m.ref_agency_code_id
				LEFT JOIN master.state rs ON rs.id_no = m.ref_agency_state_id AND rs.display = 'Y'
				LEFT JOIN master.users u ON u.usercode = m.dacode
				LEFT JOIN master.usersection us ON us.id = u.section
				WHERE section_name = '$section'
				AND m.c_status = 'P' 
				$Reg_condition
				ORDER BY diary_year ASC;";

          $query=$this->db->query($sql);
          return $query->getResultArray();
    }
	
	function Misc_Reg_Pendency($section=null,$type=null){

        if($type=="M")
            $condition= " AND m.mf_active='M' ";
        else if($type=="F")
            $condition=" AND m.mf_active='F' ";
        else
            $condition="";
           $sql="SELECT DISTINCT 
					substr(CAST(m.diary_no AS text), 1, length(CAST(m.diary_no AS text)) - 4) AS diary_number, 
					c.short_description, 
					substr(CAST(m.diary_no AS text), -4) AS diary_year, 
					m.reg_no_display, 
					rs.Name AS state, 
					CONCAT(m.pet_name, ' ', 
						(CASE
							WHEN m.pno = 2 THEN 'and anr.'
							WHEN m.pno > 2 THEN 'and ors.'
							ELSE ''
						END),
						' vs ',
						m.res_name, ' ',
						(CASE
							WHEN m.rno = 2 THEN 'and anr.'
							WHEN m.rno > 2 THEN 'and ors.'
							ELSE ''
						END)) AS cause, 
					u.name, m.active_fil_dt, m.diary_no_rec_date
				FROM
					main m
					LEFT JOIN master.casetype c ON m.active_casetype_id = c.casecode
					LEFT JOIN master.ref_agency_code rc ON rc.id = m.ref_agency_code_id
					LEFT JOIN master.state rs ON rs.id_no = m.ref_agency_state_id AND rs.display = 'Y'
					LEFT JOIN master.users u ON u.usercode = m.dacode
					LEFT JOIN master.usersection us ON us.id = u.section
				WHERE 
					section_name = '$section' 
					AND m.c_status = 'P' 
					$condition 
				ORDER BY diary_year ASC;";

        $query=$this->db->query($sql);
        return $query->getResultArray();
    }
	
	
	 function view_Cases_Result($section=null,$type=null,$diary_year=null,$ref_id=null){
	/* 	if ($diary_year != null)
			$condition = " AND substr(CAST(m.diary_no AS text), -4) = '$diary_year' ";  // Directly filter by diary_year
		else
			$condition = "AND m.ref_agency_code_id = $ref_id"; 

        if($type == null || $type == '' || $type == 0)
			$Reg_condition = " AND (fil_no IS NULL OR fil_no = '') 
							   AND (reg_no_display IS NULL OR reg_no_display = '') 
							   AND (fil_dt IS NULL)";  // Check if fil_dt is NULL

			else
				$Reg_condition = "AND (fil_no IS NOT NULL AND fil_no != '') 
								   AND (reg_no_display IS NOT NULL AND reg_no_display != '') 
								   AND (fil_dt IS NOT NULL) 
								   AND m.active_casetype_id = $type";
					 
				   $sql = "SELECT DISTINCT 
					substr(CAST(m.diary_no AS text), 1, length(CAST(m.diary_no AS text)) - 4) AS diary_number, 
					c.short_description, 
					substr(CAST(m.diary_no AS text), -4) AS diary_year, 
					m.reg_no_display, 
					rs.Name AS state, 
					CONCAT(m.pet_name, ' ', 
						(CASE
							WHEN m.pno = 2 THEN 'and anr.'
							WHEN m.pno > 2 THEN 'and ors.'
							ELSE ''
						END),
						' vs ',
						m.res_name, ' ',
						(CASE
							WHEN m.rno = 2 THEN 'and anr.'
							WHEN m.rno > 2 THEN 'and ors.'
							ELSE ''
						END)) AS cause, 
					u.name, 
					c.casename, 
					m.active_fil_dt, 
					m.diary_no_rec_date
				FROM
					main m
					LEFT JOIN master.casetype c ON m.active_casetype_id = c.casecode
					LEFT JOIN master.ref_agency_code rc ON rc.id = m.ref_agency_code_id
					LEFT JOIN master.state rs ON rs.id_no = m.ref_agency_state_id AND rs.display = 'Y'
					LEFT JOIN master.users u ON u.usercode = m.dacode
					LEFT JOIN master.usersection us ON us.id = u.section
				WHERE
					m.c_status = 'P'
					$Reg_condition
					AND section_name = '$section'
					$condition
				ORDER BY diary_year ASC;";
				//echo $sql;die;
        $query=$this->db->query($sql);
		return $query->getResultArray();  */
		
		
		$builder = $this->db->table('main m');

		   $builder->select([
				'SUBSTRING(m.diary_no::TEXT FROM 1 FOR LENGTH(m.diary_no::TEXT) - 4) AS diary_number',
				'c.short_description',
				'SUBSTRING(m.diary_no::TEXT FROM -4) AS diary_year',
				'm.reg_no_display',
				'rs.name AS state',
				"m.pet_name || ' ' || CASE
					WHEN m.pno = 2 THEN 'and anr.'
					WHEN m.pno > 2 THEN 'and ors.'
					ELSE ''
				END || ' vs ' || m.res_name || ' ' || CASE
					WHEN m.rno = 2 THEN 'and anr.'
					WHEN m.rno > 2 THEN 'and ors.'
					ELSE ''
				END AS cause",
				'u.name',
				'c.casename',
				'm.active_fil_dt',
				'm.diary_no_rec_date'
			]);

    
			$builder->join('master.casetype c', 'm.active_casetype_id = c.casecode', 'left');
			$builder->join('master.ref_agency_code rc', 'rc.id = m.ref_agency_code_id', 'left');
			$builder->join('master.state rs', "rs.id_no = m.ref_agency_state_id AND rs.display = 'Y'", 'left');
			$builder->join('master.users u', 'u.usercode = m.dacode', 'left');
			$builder->join('master.usersection us', 'us.id = u.section', 'left');

    
			$builder->where('m.c_status', 'P');

    
			if ($diary_year !== null) {
				$builder->where("SUBSTRING(m.diary_no::TEXT FROM LENGTH(m.diary_no::TEXT) - 3 FOR 4) =", $diary_year);
			} else {
				$builder->where('m.ref_agency_code_id', $ref_id);
			}

   
			if ($type === null || $type == 0) {
				$builder->groupStart();  
				$builder->where('fil_no IS NULL');
				$builder->orWhere('fil_no', '');
				$builder->where('reg_no_display IS NULL');
				$builder->orWhere('reg_no_display', '');
				$builder->groupEnd();  
			} else {
				$builder->groupStart();  
				$builder->where('fil_no IS NOT NULL');
				$builder->where('fil_no !=', '');
				$builder->where('reg_no_display IS NOT NULL');
				$builder->where('reg_no_display !=', '');
				$builder->where('fil_dt IS NOT NULL');
				$builder->where('m.active_casetype_id', $type);
				$builder->groupEnd();  
			}

		$builder->where('section_name', $section);

		$builder->orderBy('diary_year', 'ASC');

		$query = $builder->get();
		return $query->getResultArray();
 }



	 
	 
	 
	 
}
