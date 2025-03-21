<?php

namespace App\Models\Reports;

use CodeIgniter\Model;

class ListedNotListedModel extends Model
{
	protected $table = 'fil_trap';
	
    public function GetListedData()
    {
        $builder = $this->db->table('master.listing_purpose');
        $builder->where('display', 'Y');
        $builder->orderBy('purpose', 'asc');
        $query  = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }
    public function get_result($from_date, $to_date, $main_conn, $purpose)
    {
        $from_date = date('Y-m-d', strtotime($from_date));
        $to_date = date('Y-m-d', strtotime($to_date));

        if ($main_conn == 'm') $main_conn = "  (h.diary_no=h.conn_key OR h.conn_key=0) ";
        if ($main_conn == 'c') $main_conn = "  NOT (h.diary_no=h.conn_key OR h.conn_key=0) ";
        if ($main_conn == 'all') $main_conn = " ";

        if ($purpose == 'all') $purpose = "";
        else $purpose = "l.code=" . $purpose;


        $builder = $this->db->table('main m');

        // Select statements
        $builder->select('h.subhead, s.stagename');
        $builder->select('COUNT(*) AS tot_cases');
        $builder->select('SUM(CASE WHEN fx_wk IN (\'F\', \'W\') THEN 1 ELSE 0 END) AS fixed');
        $builder->select('SUM(CASE WHEN fx_wk NOT IN (\'F\', \'W\') THEN 1 ELSE 0 END) AS not_fixed');
        $builder->select('SUM(CASE WHEN fx_wk IS NULL OR fx_wk = \'\' THEN 1 ELSE 0 END) AS fixed_null');
        $builder->select('SUM(CASE WHEN fx_wk IN (\'F\', \'W\') AND (clno = 0 AND brd_slno = 0 AND (judges = \'\' ) AND roster_id = 0) THEN 1 ELSE 0 END) AS not_listed_fixed');
        $builder->select('SUM(CASE WHEN fx_wk IN (\'F\', \'W\') AND NOT (clno = 0 AND brd_slno = 0 AND (judges = \'\') AND roster_id = 0) THEN 1 ELSE 0 END) AS listed_fixed');
        $builder->select('SUM(CASE WHEN fx_wk NOT IN (\'F\', \'W\') AND (clno = 0 AND brd_slno = 0 AND (judges = \'\') AND roster_id = 0) THEN 1 ELSE 0 END) AS not_listed_not_fixed');
        $builder->select('SUM(CASE WHEN fx_wk NOT IN (\'F\', \'W\') AND NOT (clno = 0 AND brd_slno = 0 AND (judges = \'\') AND roster_id = 0) THEN 1 ELSE 0 END) AS listed_not_fixed');
        // Joins
        $builder->join('heardt h', 'h.diary_no = m.diary_no', 'left');
        $builder->join('master.subheading s', 'h.subhead = s.stagecode', 'left');
        $builder->join('master.listing_purpose l', 'h.listorder = l.code', 'left');
       
        // Where clauses
        $builder->where('c_status', 'P');
        $builder->where('h.mainhead', 'M');
        $builder->whereIn('main_supp_flag', [0, 1, 2]);
        $builder->where('h.next_dt >=', $from_date);
        $builder->where('h.next_dt <=', $to_date);
        if ($main_conn) {
            $builder->where($main_conn);
        }
        if ($purpose) {
            $builder->where($purpose);
        }
        
        
        $builder->groupBy('s.stagename, h.subhead');
        $query  = $builder->get();
        $result = $query->getResultArray();
        return $result;
        
    }

    public function get_data()
    {
        $builder = $this->db->table('sci_cmis_final.heardt h');

        // Select columns
        $builder->select('
    tentative_section(h.diary_no) as dno, r.courtno, u.name, us.section_name, l.purpose, 
    c1.short_description, YEAR(m.active_fil_dt) AS fyr, m.active_reg_year, m.active_fil_dt,
    m.active_fil_no, m.reg_no_display, m.pet_name, m.res_name, m.pno, m.rno, m.casetype_id, 
    m.ref_agency_state_id, m.diary_no_rec_date, h.remark, h.*, br.* 
');

        // Joins
        $builder->join('sci_cmis_final.main m', 'm.diary_no = h.diary_no', 'inner');
        $builder->join('sci_cmis_final.listing_purpose l', 'l.code = h.listorder AND l.display = "Y"', 'inner');
        $builder->join('sci_cmis_final.roster r', 'r.id = h.roster_id AND r.display = "Y"' . $court_no, 'inner');
        $builder->join('brdrem br', 'br.diary_no = m.diary_no', 'left');
        $builder->join('sci_cmis_final.casetype c1', 'm.active_casetype_id = c1.casecode', 'left');
        $builder->join('users u', 'u.usercode = m.dacode AND (u.display = "Y" OR u.display IS NULL)', 'left');
        $builder->join('usersection us', 'us.id = u.section' . $sec_id, 'left');

        // Dynamic WHERE conditions
        if (isset($mainhead)) {
            $builder->where('h.mainhead', $mainhead);
        }

        if (isset($_POST['list_dt'])) {
            $builder->where('h.next_dt', $_POST['list_dt']);
        }

        // Additional WHERE conditions
        $builder->where('(h.main_supp_flag = 1 OR h.main_supp_flag = 2)');
        $builder->where('h.roster_id >', 0);
        $builder->where('m.diary_no IS NOT NULL');
        $builder->where('m.c_status', 'P');

        // Add additional dynamic conditions
        if (isset($main_suppl)) {
            $builder->where($main_suppl);
        }
        if (isset($sec_id2)) {
            $builder->where($sec_id2);
        }
        if (isset($mdacode)) {
            $builder->where($mdacode);
        }
        if (isset($lp)) {
            $builder->where($lp);
        }
        if (isset($board_type)) {
            $builder->where($board_type);
        }
        if (isset($section)) {
            $builder->where($section);
        }

        // Grouping and Ordering
        $builder->groupBy('h.diary_no');

        // $builder->orderBy($orderby);
        $builder->orderBy('r.courtno');
        $builder->orderBy('h.brd_slno');
        $builder->orderBy('IF(us.section_name IS NULL, 9999, 0)', 'ASC');
        $builder->orderBy('us.section_name');
        $builder->orderBy('u.name');
        $builder->orderBy('IF(h.conn_key = h.diary_no, "0000-00-00", m.diary_no_rec_date)', 'ASC');

        // Execute query
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }
    public function get_elimination_statistics()
    {
        $builder = $this->db->table('main m');
        $builder->select('h.no_of_time_deleted, 
              SUM(CASE WHEN h.listorder != 32 THEN 1 ELSE 0 END) AS old_cases, 
              SUM(CASE WHEN h.listorder = 32 THEN 1 ELSE 0 END) AS fresh_cases');
        $builder->join('heardt h', 'm.diary_no = h.diary_no', 'inner');
        $builder->where('h.no_of_time_deleted !=', 0);
        $builder->where('m.c_status', 'P');
        $builder->groupStart();
        $builder->where('CAST(m.diary_no AS TEXT) = CAST(m.conn_key AS TEXT)');
        $builder->orWhere('m.conn_key IS NULL');
        $builder->orWhere('m.conn_key', '');
        $builder->groupEnd(); // End the group
        $builder->where('h.mainhead', 'M');
        $builder->where('h.board_type', 'J');
        $builder->groupBy('h.no_of_time_deleted'); // Change to h.no_of_time_deleted
        $builder->orderBy('h.no_of_time_deleted', 'DESC'); // Change to h.no_of_time_deleted
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }
    public function get_elimination_statistics_detail($eliminated, $type)
    {

        $builder = $this->db->table('heardt h');
        $builder->select("
                        CONCAT(
                            COALESCE(m.reg_no_display, ''), 
                            ' @ ', 
                            CONCAT(
                                LEFT(m.diary_no::text, LENGTH(m.diary_no::text) - 4), 
                                '-', 
                                SUBSTRING(m.diary_no::text, -4)
                            )
                        ) AS case_no, 
                        CONCAT(m.pet_name, ' Vs. ', m.res_name) AS cause_title, 
                        tentative_section(m.diary_no) AS section, 
                        u.name AS daname, 
                        TO_CHAR(h.next_dt, 'DD-MM-YYYY') AS next_date, 
                        CASE 
                            WHEN h.main_supp_flag = 0 THEN 'Ready' 
                            ELSE 'Not Ready' 
                        END AS case_status
                    ");
        $builder->join('main m', 'm.diary_no = h.diary_no');
        $builder->join('master.users u', 'm.dacode = u.usercode', 'left');
        if ($type == 'F') {
            $builder->where('h.listorder', 32);
        }
        if ($type == 'O') {
            $builder->where('h.listorder !=', 32);
        }
        $builder->where('no_of_time_deleted', $eliminated);
        $builder->where('c_status', 'P');
        $builder->groupStart()
            ->where('m.diary_no', 'm.conn_key::int', false)
            ->orWhere('m.conn_key IS NULL')
            ->orWhere('m.conn_key', '')
            ->groupEnd();
        $builder->where('h.mainhead', 'M');
        $builder->where('h.board_type', 'J');
        $query = $builder->get();
        $result = $query->getResultArray();
        
        return $result;
    }
    public function get_scrutiny_details($dateFrom, $dateTo)
    {
        $sql = "SELECT 
                        u.name, 
                        u.empid, 
                        us.type_name, 
                        us.id, 
                        SUM(CASE WHEN diary_no::text != '' THEN 1 ELSE 0 END) AS total, 
                        SUM(CASE WHEN comp_dt IS NOT NULL AND comp_dt is not null THEN 1 ELSE 0 END) AS completed,
                        SUM(CASE WHEN comp_dt IS NULL OR comp_dt is null THEN 1 ELSE 0 END) AS pending 
                        FROM (
                        SELECT diary_no, d_to_empid, disp_dt, remarks, comp_dt 
                        FROM fil_trap f 
                        WHERE remarks IN ('DE -> SCR') 
                        AND disp_dt BETWEEN '$dateFrom' AND '$dateTo' 
                        UNION
                        SELECT diary_no, d_to_empid, disp_dt, remarks, comp_dt 
                        FROM fil_trap_his h 
                        WHERE remarks IN ('DE -> SCR') 
                        AND disp_dt BETWEEN '$dateFrom' AND '$dateTo' 
                        ) AS temp 
                        JOIN master.users u ON temp.d_to_empid = u.empid 
                        JOIN master.usertype us ON u.usertype = us.id 
                        GROUP BY u.empid, u.name, us.type_name, us.id 
                        ORDER BY 
                        CASE us.id 
                            WHEN '14' THEN 1 
                            WHEN '17' THEN 2 
                            WHEN '51' THEN 3 
                            WHEN '50' THEN 4 
                            ELSE 5 
                        END, 
                        u.empid ASC";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    } 
	
	// module Scrutiny Matters

     public function get_scrutiny_matters_details($frm_dt, $to_dt, $empid, $case){
		
		if ($case == 'spallot' || $case == 'sptotal') {
                  
			$query1 = $this->db->table('fil_trap')
				->select('diary_no, d_to_empid, disp_dt, remarks, comp_dt')
				->whereIn('remarks', ['DE -> SCR'])
				->where('DATE(disp_dt) >=', $frm_dt)
				->where('DATE(disp_dt) <=', $to_dt);
			if($case!= 'sptotal'){
				$query1->where('d_to_empid', $empid);
			}

			$query2 = $this->db->table('fil_trap_his')
				->select('diary_no, d_to_empid, disp_dt, remarks, comp_dt')
				->whereIn('remarks', ['DE -> SCR'])
				->where('DATE(disp_dt) >=', $frm_dt)
				->where('DATE(disp_dt) <=', $to_dt);
			if($case!= 'sptotal'){
				$query2->where('d_to_empid', $empid);
			}
			
			$finalQuery = $query1->union($query2);

			$finalQuery = $this->db->table('(' . $finalQuery->getCompiledSelect() . ') AS temp')
				->select('m.diary_no, CONCAT(pet_name, \' vs \', res_name) as causetitle, disp_dt')
				->join('main m', 'm.diary_no = temp.diary_no');


			return $finalQuery->get()->getResultArray();

		}elseif($case == 'spcomp' || $case == 'spcomplete') {
			
			$query1 = $this->db->table('fil_trap')
				->select('diary_no, d_to_empid, disp_dt, remarks, comp_dt')
				->whereIn('remarks', ['DE -> SCR'])
				->where('DATE(disp_dt) >=', $frm_dt)
				->where('DATE(disp_dt) <=', $to_dt)
				->where('comp_dt IS NOT NULL');
			
			if($case!= 'spcomplete'){
				$query1->where('d_to_empid', $empid);
			}	

			$query2 = $this->db->table('fil_trap_his')
				->select('diary_no, d_to_empid, disp_dt, remarks, comp_dt')
				->whereIn('remarks', ['DE -> SCR'])
				->where('DATE(disp_dt) >=', $frm_dt)
				->where('DATE(disp_dt) <=', $to_dt)
				->where('comp_dt IS NOT NULL');  
				
			if($case!= 'spcomplete'){
				$query2->where('d_to_empid', $empid);
			}	

			$finalQuery = $query1->union($query2);

			$finalQuery = $this->db->table('(' . $finalQuery->getCompiledSelect() . ') AS temp')
				->select('m.diary_no, CONCAT(pet_name, \' vs \', res_name) as causetitle, disp_dt')
				->join('main m', 'm.diary_no = temp.diary_no');


			$result = $finalQuery->get()->getResult();
			return $result;
			
		}elseif($case == 'spnotcomp' || $case == 'sppend') {
		
				$query1 = $this->db->table('fil_trap')
						->select('diary_no, d_to_empid, disp_dt, remarks, comp_dt')
						->whereIn('remarks', ['DE -> SCR'])
						->where('DATE(disp_dt) >=', $frm_dt)
						->where('DATE(disp_dt) <=', $to_dt)
						->where('comp_dt IS NULL');
				
				if($case!= 'sppend'){
				   $query1->where('d_to_empid', $empid);
			    }				
				

				$query2 = $this->db->table('fil_trap_his')
					->select('diary_no, d_to_empid, disp_dt, remarks, comp_dt')
					->whereIn('remarks', ['DE -> SCR'])
					->where('DATE(disp_dt) >=', $frm_dt)
					->where('DATE(disp_dt) <=', $to_dt)
					->where('comp_dt IS NULL');
                
				if($case!= 'sppend'){
				   $query2->where('d_to_empid', $empid);
			    }

				$finalQuery = $query1->union($query2);

				$finalQuery = $this->db->table('(' . $finalQuery->getCompiledSelect() . ') AS temp')
						->select('m.diary_no, CONCAT(pet_name, \' vs \', res_name) as causetitle, disp_dt')
						->join('main m', 'm.diary_no = temp.diary_no');


			    return $finalQuery->get()->getResultArray();
       }
	}
	
	
}
