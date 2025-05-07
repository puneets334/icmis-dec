<?php

namespace App\Models\Listing;

use CodeIgniter\Model;

class DropRequestModel extends Model
{

    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
    }

    public function get_drop_request_by_diary_no($diary_no)
    {
        $subquery = $this->db->table('master.deptt')
        ->select('deptname')
        ->where('deptcode = pa.deptcode')
        ->getCompiledSelect();

        $builder = $this->db->table("party as pa");
        $builder->select('pa.diary_no,pa.sr_no, pa.pet_res, pa.ind_dep, pa.partyname, pa.sonof, pa.prfhname, pa.age, pa.sex, pa.caste, pa.addr1, pa.addr2,pa.pin, pa.state, pa.city, pa.email, pa.contact AS mobile, pa.deptcode, (' . $subquery . ') AS deptname, c.skey');
        $builder->join('main m', "m.diary_no = pa.diary_no AND pa.sr_no = 1 AND pa.pflag = 'P' AND pa.pet_res IN ('P', 'R')");
        $builder->join('master.casetype c', 'CAST(SUBSTRING(fil_no, 1, 2) AS INTEGER) = c.casecode', 'LEFT');
        $builder->where('m.diary_no', $diary_no);
        $builder->orderBy('pa.pet_res', 'ASC');
        $builder->orderBy('pa.sr_no', 'ASC');
        $query = $builder->get();
        
        $result = $query->getResultArray();
        return $result;
    }

    public function get_case_type($casetype_id)
    {
        $builder = $this->db->table('master.casetype')
        ->select('short_description')
        ->where('casecode', $casetype_id)
        ->where('display', 'Y');
        $query = $builder->get();
        $result = $query->getRow();
        return $result;
    }

    public function get_bench_name($bench_id)
    {
        $builder = $this->db->table('master.master_bench')
        ->select('bench_name')
        ->where('display', 'Y')
        ->where('id', $bench_id);
        $query = $builder->get();
        $result = $query->getRowArray();
        return isset($result['bench_name']) ? $result['bench_name'] : '';
    }

    public function get_party_details_by_diary_no($diary_no)
    {
        $subquery = $this->db->table('master.deptt')
        ->select('deptname')
        ->where('deptcode = pa.deptcode')
        ->getCompiledSelect();

        $builder = $this->db->table("party as pa");
        $builder->select('pa.diary_no,pa.sr_no, pa.pet_res, pa.ind_dep, pa.partyname, pa.sonof, pa.prfhname, pa.age, pa.sex, pa.caste, pa.addr1, pa.addr2,pa.pin, pa.state, pa.city, pa.email, pa.contact AS mobile, pa.deptcode, (' . $subquery . ') AS deptname, c.skey');
        $builder->join('main m', "m.diary_no = CAST(pa.diary_no as BIGINT)  AND pa.sr_no = 1 AND pa.pflag = 'P' AND pa.pet_res IN ('P', 'R')");
        $builder->join('master.casetype c', 'CAST(SUBSTRING(fil_no, 1, 2) AS INTEGER) = c.casecode', 'LEFT');
        $builder->where('m.diary_no', $diary_no);
        $builder->orderBy('pa.pet_res', 'ASC');
        $builder->orderBy('pa.sr_no', 'ASC');
        
        $query = $builder->get();
        //echo $this->db->getLastQuery();
        //die;
        $result = $query->getResultArray();
        return $result;
    }

    public function get_multiple_category($diary_no)
    {
        
        $builder = $this->db->table('mul_category a')
        ->select('b.subject_description, b.category_description')
        ->join('master.submaster b', 'a.submaster_id = b.id')
        ->where('a.diary_no',$diary_no)
        ->where('a.display', 'Y');
        // pr($builder->getCompiledSelect());
        $query = $builder->get();
        $category_details = $query->getResultArray();
        $category_nm = $mul_category = '';
        foreach($category_details as $category){
            $category_nm = $category['subject_description'] . ' - ' . $category['category_description'];
            if ($mul_category == '') {
                $mul_category = $category_nm;
            } else {
                $mul_category = $mul_category . ',<br> ' . $category_nm;
            }
        }
        return $mul_category;
    }

    public function get_act_section($diary_no)
    {
        $builder = $this->db->table('act_main a')
        ->select("a.act, STRING_AGG(b.section, ', ') AS section, c.act_name")
        ->join('master.act_section b', 'a.id = b.act_id', 'left')
        ->join('master.act_master c', 'c.id = a.act')
        ->where('a.display', 'Y')
        ->where('b.display', 'Y')
        ->where('c.display', 'Y')
        ->where('diary_no', $diary_no)
        ->groupBy('a.act, c.act_name');
        
        $query = $builder->get();
        $results = $query->getResultArray();
        $act_section = '';
        foreach($results as $act) {
            if ($act_section == '')
                $act_section = $act['act_name'] . '-' . $act['section'];
            else
                $act_section = $act_section . ', ' . $act['act_name'] . '-' . $act['section'];
        }
        return $act_section;
    }

    public function get_provision_of_law($actcode)
    {
        $builder = $this->db->table('master.caselaw')
        ->select('law')
        ->where('id', $actcode);
        $query = $builder->get();
        $result = $query->getRowArray();
        return isset($result['law']) ? $result['law'] : '';
    }

    public function get_tentative_date($diary_no)
    {
        $builder = $this->db->table('heardt')
        ->select('tentative_cl_dt')
        ->where('diary_no', $diary_no);
        
        $query = $builder->get();
        $result = $query->getRow();
        return isset($result->tentative_cl_dt) ? $result->tentative_cl_dt : '';
    }

    public function hearing_date_list($diary_no)
    {
        $builder = $this->db->table('heardt')
        ->select('*')
        ->where('diary_no',$diary_no);
        $query = $builder->get();
        $results = $query->getResultArray();
        //echo $this->db->getLastQuery();
        //die;
        //pr($results);
        foreach($results as $key => $date_list){
            if($date_list['mainhead'] == "M"){
                $date_list['subhead'] = 105;
                $t_stage = $this->get_stage($date_list['subhead'], 'M');
                $sub_cat = isset($date_list['sub_cat']) ? $date_list['sub_cat'] : '';
                $t_stage.= $this->get_stage($sub_cat, 'F');
            }
            if($date_list['mainhead'] == "F"){
                $sub_cat = isset($date_list['sub_cat']) ? $date_list['sub_cat'] : '';
                $t_stage = $this->get_stage($sub_cat, 'F');
            }

            $results[$key] = $date_list;
            $results[$key]['t_stage'] = $t_stage;

            $purpose =  $this->get_purpose($date_list['listorder']);
            $results[$key]['purpose'] = $purpose;

            $next_date =  $this->change_date_format($date_list['next_dt']);
            $results[$key]['next_date'] = $next_date;
        }
        
        return $results;
    }


    public function hearing_last_date_list($diary_no)
    {
        $builder = $this->db->table('last_heardt')
            ->select('*')
            ->where('diary_no', $diary_no);
        $query = $builder->get();
        $results = $query->getResultArray();
        //echo $this->db->getLastQuery();
        //die;
        //pr($results);
        foreach($results as $key => $date_list){
            if($date_list['mainhead'] == "M"){
                $date_list['subhead'] = 105;
                $t_stage = $this->get_stage($date_list['subhead'], 'M');
                $sub_cat = isset($date_list['sub_cat']) ? $date_list['sub_cat'] : '';
                $t_stage.= $this->get_stage($sub_cat, 'F');
            }
            if($date_list['mainhead'] == "F"){
                $sub_cat = isset($date_list['sub_cat']) ? $date_list['sub_cat'] : '';
                $t_stage = $this->get_stage($sub_cat, 'F');
            }
            
            $results[$key] = $date_list;
            $results[$key]['t_stage'] = $t_stage;
            $purpose =  $this->get_purpose($date_list['listorder']);
            $results[$key]['purpose'] = $purpose;
            $next_date =  $this->change_date_format($date_list['next_dt']);
            $results[$key]['next_date'] = $next_date;
        }
         
        

        return $results;
    }

    function get_stage($stage_code, $mainhead)
    {
        $stage = "";
        if($stage_code != "") {
            if($mainhead == "M") {
                $builder = $this->db->table('master.subheading');
                $builder->select('stagename');
                $builder->where('stagecode', $stage_code);
                $query = $builder->get();
                $result = $query->getRowArray();
                $stage = isset($result['stagename']) ? $result['stagename'] : "";
            }

            if($mainhead == "F"){
                $builder = $this->db->table('master.submaster');
                $builder->where('id', $stage_code);
                $query = $builder->get();
                $result1_p = $query->getResultArray();
                foreach ($result1_p as $row_p) {
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

    function get_purpose($purpose_code)
    {
        $purpose = "";
        if ($purpose_code != "") {
            $builder = $this->db->table('master.listing_purpose');
            $builder->select('purpose');
            $builder->where('code', $purpose_code);
            $query = $builder->get();
            $result = $query->getRowArray();
            $purpose = isset($result['purpose']) ? $result['purpose'] : "";
        }
        return $purpose;
    }

    function change_date_format($date){
        if($date=="" or $date=="0000-00-00")
            $date="";
        else
            $date=date('d-m-Y', strtotime($date));
        return $date;
    }

    function get_interlocutary_app($diary_no)
    {
        //$diary_no =162023;
        $builder = $this->db->table('docdetails a');
        $builder->select('a.doccode, a.doccode1, a.docnum, a.docyear, a.filedby, a.docfee, a.forresp, a.feemode, a.other1, a.iastat, b.docdesc, a.ent_dt,(select listed_ia from heardt where diary_no=a.diary_no) as listedia');
        $builder->join('master.docmaster b', 'a.doccode = b.doccode AND a.doccode1 = b.doccode1');
        $builder->where('a.diary_no',$diary_no);
        $builder->where('a.doccode',8);
        $builder->where('a.display','Y');
        $query = $builder->get();
        return $query->getResultArray();
    }

    function get_other_docs($diary_no)
    {
        $builder = $this->db->table('docdetails a');
        $builder->select('a.doccode, a.doccode1, a.docnum, a.docyear, a.filedby, a.docfee, a.forresp, a.feemode, a.other1, b.docdesc, a.ent_dt');
        $builder->join('master.docmaster b', 'a.doccode = b.doccode AND a.doccode1 = b.doccode1');
        $builder->where('a.diary_no',$diary_no);
        $builder->where('a.doccode !=', 8); // Exclude doccode 8
        $builder->where('a.display','Y');
        $query = $builder->get();
        return $query->getResultArray();
    }

    function get_case_details_by_diary_no($diary_no)
    {
        $builder = $this->db->table('main m');
        $builder->select('m.reg_no_display, m.pet_name, m.res_name, m.pno, m.rno, h.*');
        $builder->join('heardt h', 'h.diary_no = m.diary_no', 'left');
        $builder->where('m.diary_no', $diary_no);
        $builder->where('h.next_dt >=', date('Y-m-d'));
        $builder->groupStart(); // Grouping conditions for OR
        $builder->where('h.main_supp_flag', 1)
                ->orWhere('h.main_supp_flag', 2);
        $builder->groupEnd(); // End grouping conditions
        $builder->orderBy('h.next_dt', 'DESC'); // Order by next date descending
        $query = $builder->get();
        return $query->getRowArray();
    }

    function f_cl_is_printed($q_next_dt, $partno, $mainhead, $roster_id)
    {
        $result = 0;
        $roster_id_array = explode(',', $roster_id);
        $builder = $this->db->table('cl_printed');
        $builder->where('next_dt', $q_next_dt);
        $builder->where('part', $partno);
        $builder->where('m_f', $mainhead);
        $builder->whereIn('roster_id', $roster_id_array);
        $builder->where('display', 'Y');
        $exists = $builder->countAllResults() > 0;

        if ($exists) {
            $result = 1;
        }
        return $result;
    }

    function get_drop_note($dno, $brd_slno, $roster_id, $mainhead, $q_next_dt)
    {
        $builder = $this->db->table('drop_note');
        $builder->selectCount('diary_no');
        $builder->where('diary_no', $dno);
        $builder->where('clno', $brd_slno);
        $builder->where('roster_id', $roster_id);
        $builder->where('display', 'Y');
        $builder->where('mf', $mainhead);
        $builder->where('cl_date', $q_next_dt);
        $query = $builder->get();
        $count = $query->getRow()->diary_no;
        //pr($count);
        return $count;
    }

    function drop_reason() 
    {
        $builder = $this->db->table('master.drop_reason');
        $builder->where('reason_type',5);
        $builder->where('display','Y');
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    function drop_note_ins($ucode,$next_dt,$brd_slno,$drop_diary,$roster_id,$drop_rmk,$mainhead,$partno,$is_printed,$drop_reason_select,$ready_not)
    {
        $result = 0;
        $data = [
            'cl_date'=>$next_dt,
            'clno' =>$brd_slno,
            'diary_no'=>$drop_diary,
            'roster_id'=>$roster_id,
            'nrs' =>$drop_rmk,
            'usercode'=>$ucode,
            'ent_dt' =>date('Y-m-d H:i:s'),
            'mf' =>$mainhead,
            'part' =>$partno,
            'display' =>$is_printed,
            'reason_id' =>$drop_reason_select,
            'reason_type_id' =>$ready_not,
            'update_user' =>$ucode,
            'so_user' =>$ucode,
        ];

        $builder = $this->db->table('drop_note');
        $builder->insert($data);
        if ($this->db->affectedRows() > 0) {
            $result = 1;
        }
        return $result;
    }

    function f_cl_drop_case($dno, $ucode, $ldates, $ready_not)
    {
        $result = 0;
        if($dno == 0 OR $dno == ''){
            echo "0";
        } else {
            $sql = "INSERT INTO last_heardt (
                diary_no, conn_key, next_dt, mainhead, subhead, clno, brd_slno, roster_id, judges, coram, board_type, usercode, ent_dt, module_id, mainhead_n, subhead_n, main_supp_flag, listorder, tentative_cl_dt, lastorder, listed_ia, sitting_judges, list_before_remark, is_nmd, no_of_time_deleted, bench_flag
            )
        SELECT j.*, 'D'
        FROM (
            SELECT h.diary_no, h.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, h.board_type, h.usercode, h.ent_dt, h.module_id, h.mainhead_n, h.subhead_n, h.main_supp_flag, h.listorder, h.tentative_cl_dt, m.lastorder, h.listed_ia, h.sitting_judges, h.list_before_remark, h.is_nmd, h.no_of_time_deleted
            FROM main m
            INNER JOIN heardt h ON m.diary_no = h.diary_no 
            WHERE (h.conn_key = ? AND (h.diary_no = ? OR h.conn_key = ?)) 
            OR (h.conn_key != ? AND h.diary_no = ?) 
            AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
        ) j
        LEFT JOIN last_heardt l ON j.diary_no = l.diary_no
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
            AND (j.listed_ia IS NULL OR l.listed_ia = j.listed_ia)
            AND (j.list_before_remark IS NULL OR l.list_before_remark = j.list_before_remark)
            AND l.no_of_time_deleted = j.no_of_time_deleted
            AND l.is_nmd = j.is_nmd
            AND l.main_supp_flag = j.main_supp_flag
            AND (l.bench_flag = '' OR l.bench_flag IS NULL)
            WHERE l.diary_no IS NULL";
            $this->db->query($sql, [$dno, $dno, $dno, $dno, $dno]);

            $sql = "UPDATE heardt
                SET
                    next_dt = ?,
                    tentative_cl_dt = ?,
                    clno = '0',
                    brd_slno = '0',
                    roster_id = '0',
                    judges = '0',
                    usercode = ?,
                    ent_dt = NOW(),
                    module_id = '12',
                    main_supp_flag = ?
                WHERE
                    (conn_key = ? AND (diary_no = ? OR conn_key = ?))
                    OR (conn_key != ? AND diary_no = ?)
                    AND diary_no > 0
                    AND clno > 0
                    AND brd_slno > 0
                    AND roster_id > 0
                    AND (main_supp_flag = 1 OR main_supp_flag = 2)";
            $this->db->query($sql, [$ldates, $ldates, $ucode, $ready_not, $dno, $dno, $dno, $dno, $dno]);

            // Check if the query was successful
            if ($this->db->affectedRows() > 0) {
                $sql = "INSERT INTO advanced_drop_note (cl_date, clno, diary_no, roster_id, nrs, usercode, ent_dt, display, mf, part)
                        SELECT next_dt,brd_slno,diary_no,j1,'Released after final allocation' as nrs,? as usercode,NOW(),'R','M',clno
                        FROM advance_allocated h
                        WHERE (h.conn_key = ? AND (h.diary_no = ? OR h.conn_key = ?))
                            OR (h.conn_key != ? AND h.diary_no = ?)
                            AND h.next_dt = ?
                            AND h.diary_no > 0
                            AND h.clno > 0
                            AND h.brd_slno > 0
                            AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)";
                    $this->db->query($sql, [$ucode, $dno, $dno, $dno, $dno, $dno, $ldates]);
                    if ($this->db->affectedRows() > 0) {
                        $result = 1;
                    }
            }
        }
        return $result;
    }

    public function field_sel_ros_jgs()
    {
        $sql = "SELECT STRING_AGG(j.first_name || ' ' || j.sur_name, ', ' ORDER BY j.judge_seniority) AS jnm, a.* 
                FROM (
                    SELECT h.roster_id, h.judges 
                    FROM heardt h 
                    WHERE h.mainhead = 'M' 
                    AND h.board_type = 'J' 
                    AND h.next_dt >= CURRENT_DATE 
                    AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2) 
                    AND h.roster_id > 0 
                    GROUP BY h.roster_id, h.judges
                ) a
                LEFT JOIN master.roster_judge rj ON rj.roster_id = a.roster_id 
                LEFT JOIN master.judge j ON j.jcode = rj.judge_id 
                WHERE j.is_retired != 'Y' 
                AND j.display = 'Y' 
                AND rj.display = 'Y' 
                GROUP BY a.roster_id, a.judges, rj.roster_id";

        // Execute the query using CodeIgniter's database handler
        $query = $this->db->query($sql);

        // Fetch the result
        $results = $query->getResultArray();
        return $results;
    }

    public function field_sel_roster_dts()
    {
        $builder = $this->db->table('heardt');
        $builder->select('next_dt');
        $builder->where('mainhead', 'M');
        $builder->where('next_dt >=', date('Y-m-d'));
        $builder->groupStart() // Start grouping the conditions
            ->where('main_supp_flag', '1')
            ->orWhere('main_supp_flag', '2')
            ->groupEnd(); // End grouping
        $builder->groupBy('next_dt');

        // Execute the query
        $query = $builder->get();

        // Get the result as an array
        $results = $query->getResultArray();

        return $results;
    }

    public function get_cl_print_mainhead($mainhead, $board_type)
    {
        $builder = $this->db->table('heardt');
        $builder->select('next_dt');
        $builder->where('mainhead', $mainhead);
        $builder->where('next_dt >=', date('Y-m-d'));
        $builder->groupStart() // Start grouping the conditions
            ->where('main_supp_flag', '1')
            ->orWhere('main_supp_flag', '2')
            ->groupEnd(); // End grouping
        $builder->groupBy('next_dt');

        // Add the board_type condition if it's not '0'
        if ($board_type != '0') {
            $builder->where('board_type', $board_type);
        }

        // Execute the query
        $query = $builder->get();
        $results = $query->getResultArray();
        return $results;
    }

    public function get_cl_print_benches($cldt, $mainhead, $board_type)
    {
        // Build the subquery for the 'a' alias
        $subquery = $this->db->table('heardt h')
            ->select('h.roster_id, h.judges')
            ->where('h.mainhead', $mainhead)
            ->where('h.next_dt', $cldt);
            
        // Add the board_type condition if it's not '0'
        if ($board_type != '0') {
            $subquery->where('h.board_type', $board_type);
        }

        $subquery = $subquery
            ->groupStart()
                ->where('h.main_supp_flag', 1)
                ->orWhere('h.main_supp_flag', 2)
            ->groupEnd()
            ->where('h.roster_id >', 0)
            ->groupBy('h.roster_id, h.judges') // Include all selected columns in GROUP BY
            ->getCompiledSelect(); // Compile the subquery

        // Main query
        $builder = $this->db->table("($subquery) a")
            ->select("STRING_AGG(CONCAT(j.first_name, ' ', j.sur_name), ', ' ORDER BY j.judge_seniority) as jnm, a.*", false) // Use STRING_AGG for PostgreSQL
            ->join('master.roster_judge rj', 'rj.roster_id = a.roster_id', 'left')
            ->join('master.judge j', 'j.jcode = rj.judge_id', 'left')
            ->where('j.is_retired !=', 'Y')
            ->where('j.display', 'Y')
            ->where('rj.display', 'Y')
            ->groupBy('a.roster_id, a.judges'); // Group by the columns of the outer query

        $query = $builder->get();
        $results = $query->getResultArray();
        
        return $results;
    }

    public function get_cl_print_partno($mainhead, $list_dt, $roster_id, $board_type)
    {
        $builder = $this->db->table('heardt');
        $builder->select('clno')
            ->where('mainhead', $mainhead)
            ->where('next_dt', $list_dt)
            ->where('roster_id', $roster_id)
            ->groupStart()
                ->where('main_supp_flag', 1)
                ->orWhere('main_supp_flag', 2)
            ->groupEnd()
            ->groupBy('clno');

        // Add the board_type condition if it's not '0'
        if ($board_type != '0') {
            $builder->where('board_type', $board_type);
        }

        // Compile and execute the query
        $query = $builder->get();
        $results = $query->getResultArray();
        return $results;
    }

    public function note_field($roster_id)
    {
        // Initialize query builder for 'roster' table
        $builder = $this->db->table('master.roster r');

        // Select fields and apply STRING_AGG with the necessary ORDER BY clauses
        $builder->select("r.id,
            STRING_AGG(j.jcode::text, ',' ORDER BY j.judge_seniority) as jcd,
            STRING_AGG(CASE WHEN j.jtype = 'J' THEN j.jname ELSE CONCAT(j.first_name, ' ', j.sur_name) END, ',' ORDER BY j.judge_seniority) as jnm,
            r.courtno, rb.bench_no, mb.abbr, mb.board_type_mb, r.tot_cases, r.frm_time", false)
            ->join('master.roster_bench rb', 'rb.id = r.bench_id', 'left')
            ->join('master.master_bench mb', 'mb.id = rb.bench_id', 'left')
            ->join('master.roster_judge rj', 'rj.roster_id = r.id', 'left')
            ->join('master.judge j', 'j.jcode = rj.judge_id', 'left');

        // Add WHERE conditions for the necessary flags and filters
        $builder->where('j.is_retired !=', 'Y')
            ->where('j.display', 'Y')
            ->where('rj.display', 'Y')
            ->where('rb.display', 'Y')
            ->where('mb.display', 'Y')
            ->where('r.display', 'Y')
            ->where('r.id', $roster_id);

        // Apply GROUP BY for all non-aggregated fields
        $builder->groupBy('r.id, r.courtno, rb.bench_no, mb.abbr, mb.board_type_mb, r.tot_cases, r.frm_time, j.judge_seniority');

        // Apply ORDER BY clauses
        $builder->orderBy('r.id', 'ASC')
            ->orderBy('j.judge_seniority', 'ASC');

        // Execute the query and fetch results
        $query = $builder->get();
        $results = $query->getRowArray();
        //pr($results);
        return $results;

    }

    public function note_field1($roster_id)
    {
        // Initialize query builder for 'roster' table
        $builder = $this->db->table('master.roster r');

        // Select fields and apply STRING_AGG with sorting inside the aggregate function
        $builder->select("r.id,
            STRING_AGG(j.jcode::text, ',' ORDER BY j.judge_seniority) as jcd,
            STRING_AGG(CASE WHEN j.jtype = 'J' THEN j.jname ELSE CONCAT(j.first_name, ' ', j.sur_name) END, ',' ORDER BY j.judge_seniority) as jnm,
            r.courtno, rb.bench_no, mb.abbr, mb.board_type_mb, r.tot_cases, r.frm_time", false)
            ->join('master.roster_bench rb', 'rb.id = r.bench_id', 'left')
            ->join('master.master_bench mb', 'mb.id = rb.bench_id', 'left')
            ->join('master.roster_judge rj', 'rj.roster_id = r.id', 'left')
            ->join('master. judge j', 'j.jcode = rj.judge_id', 'left');

        // Add WHERE conditions for the necessary flags and filters
        $builder->where('j.is_retired !=', 'Y')
            ->where('j.display', 'Y')
            ->where('rj.display', 'Y')
            ->where('rb.display', 'Y')
            ->where('mb.display', 'Y')
            ->where('r.display', 'Y')
            ->where('r.id', $roster_id);

        // Apply GROUP BY for all non-aggregated fields
        $builder->groupBy('r.id, r.courtno, rb.bench_no, mb.abbr, mb.board_type_mb, r.tot_cases, r.frm_time');

        // Apply ORDER BY only on `r.id` since `judge_seniority` is already handled inside `STRING_AGG`
        $builder->orderBy('r.id', 'ASC');

        // Execute the query and fetch results
        $query = $builder->get();
        $results = $query->getResultArray();

        // Return the results
        return $results;

    }

    public function get_header_footer_print($list_dt, $mainhead, $roster_id, $part_no, $flag)
    {
        $builder = $this->db->table('headfooter');
        $builder->select('h_f_note')
            ->where('display', 'Y')
            ->where('next_dt', $list_dt)
            ->where('part', $part_no)
            ->where('mainhead', $mainhead)
            ->where('roster_id', $roster_id)
            ->where('h_f_flag', $flag)
            ->orderBy('ent_dt', 'ASC');

        // Execute the query
        $query = $builder->get();
        $results = $query->getResultArray();
        //pr($results);
        return $results;
    }

    public function get_drop_note_print($list_dt, $mainhead, $roster_id)
    {
        $builder = $this->db->table('drop_note d')
        ->select("
            m.c_status,
            h.roster_id AS p_r_id,
            h.next_dt AS p_next_dt,
            h.clno AS p_clno,
            h.brd_slno AS p_brd_slno,
            h.main_supp_flag AS p_ms_flag,
            d.clno,
            COALESCE(d.nrs, '-') AS nrs,
            d.mf,
            d.roster_id,
            d.diary_no,
            CASE
                WHEN m.active_reg_year IS NULL OR m.active_reg_year = 0
                THEN CONCAT('Dno ', LEFT(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 4), '-', RIGHT(CAST(m.diary_no AS TEXT), 4))
                ELSE CONCAT(
                    short_description, '/',
                    CASE
                        WHEN TRIM(LEADING '0' FROM SPLIT_PART(m.active_fil_no, '-', 1)) = TRIM(LEADING '0' FROM SPLIT_PART(m.active_fil_no, '-', 2))
                        THEN TRIM(LEADING '0' FROM SPLIT_PART(m.active_fil_no, '-', 1))
                        ELSE CONCAT(
                            TRIM(LEADING '0' FROM SPLIT_PART(m.active_fil_no, '-', 1)), '-',
                            TRIM(LEADING '0' FROM SPLIT_PART(m.active_fil_no, '-', 2))
                        )
                    END, '/',
                    m.active_reg_year
                )
            END AS case_no,
            CASE
                WHEN pno = 2 THEN CONCAT(m.pet_name, ' AND ANR.')
                WHEN pno > 2 THEN CONCAT(m.pet_name, ' AND ORS.')
                ELSE m.pet_name
            END AS pname,
            CASE
                WHEN rno = 2 THEN CONCAT(m.res_name, ' AND ANR.')
                WHEN rno > 2 THEN CONCAT(m.res_name, ' AND ORS.')
                ELSE m.res_name
            END AS rname
        ")
        ->join('main m', 'm.diary_no = d.diary_no', 'inner')
        ->join('heardt h', 'h.diary_no = m.diary_no', 'left')
        ->join('master.roster r', 'r.id = h.roster_id AND r.display = \'Y\'', 'left')
        ->join('master.casetype c', 'c.casecode = m.active_casetype_id', 'left')
        ->where('d.cl_date', $list_dt)
        ->where('d.display', 'Y')
        ->where('d.roster_id', $roster_id)
        ->where('d.mf', $mainhead)
        ->orderBy('d.clno', 'ASC');

        // Execute the query
        $query = $builder->get();
        $results = $query->getResultArray();
        return $results;
    }

    public function get_advocate($diary_no)
    {
        // Define your query as a string
        $sql = "WITH aggregated AS (
        SELECT
            a.diary_no,
            b.name,
            STRING_AGG(COALESCE(a.adv, ''), '') AS grp_adv,
            a.pet_res,
            a.adv_type,
            a.pet_res_no
        FROM advocate a
        LEFT JOIN master.bar b
            ON a.advocate_id = b.bar_id
            AND b.isdead != 'Y'
        WHERE a.diary_no = ?
          AND a.display = 'Y'
        GROUP BY a.diary_no, b.name, a.pet_res, a.adv_type, a.pet_res_no
        )
        SELECT 
            diary_no,
            STRING_AGG(CASE WHEN pet_res = 'R' THEN grp_adv ELSE '' END, '') AS r_n,
            STRING_AGG(CASE WHEN pet_res = 'P' THEN grp_adv ELSE '' END, '') AS p_n
        FROM aggregated
        GROUP BY diary_no
        ORDER BY diary_no;";

        // Run the query
        $query = $this->db->query($sql, [$diary_no]);
        $results = $query->getRowArray();
        return $results;
    }

    public function get_courtno($roster_id)
    {
        // Build the query
        $builder = $this->db->table('master.roster');
        $builder->select('courtno');
        $builder->where('id', $roster_id);
        $builder->where('display','Y');

        // Execute the query
        $query = $builder->get();

        // Fetch the result as an array
        $result = $query->getRowArray();

        // If you expect a single value, you can access it like this:
        $courtno = $result['courtno'] ?? null; // Use null coalescing operator to handle cases where no result is found
        return $courtno;
    }

}