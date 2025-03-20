<?php

namespace App\Models\Judicial;

use CodeIgniter\Model;
use CodeIgniter\Database\Exceptions\DatabaseException;

class PrevCaseRemarksModel extends Model
{

    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    public function getFunctionDetails($diary_no=0)
    {

        return $result;
    }

    public function getRemarks($fno, $dt)
    {
        // $sql_crm = "select group_concat(concat(r_head,'|',head_content) separator'!') as remarks from case_remarks_multiple where diary_no=$fno  and cl_date='$dt'";
        // $result_crm = mysql_query($sql_crm) or die(mysql_error());

        $builder = $this->db->table('case_remarks_multiple');
        $builder->select("string_agg(concat(r_head, '|', head_content), '!') as remarks")
                ->where('diary_no', $fno)
                ->where('cl_date', $dt);

        // echo $builder->getCompiledSelect();die;

        $query = $builder->get();
        
        $result = $query->getResultArray();

        return $result ?? [];  // Returns the first row as an associative array
    }

    public function getCaseRemarksHeadDisposed()
    {
        $builder = $this->db->table('master.case_remarks_head')
        ->select('*')
        ->where('side', 'D')
        ->where('display', 'Y')
        ->whereNotIn('sno', [
            33, 42, 144, 163, 164, 40, 167, 29, 37, 31, 78, 73, 134, 168, 43, 41,
            166, 169, 161, 160, 44, 173, 45, 187, 165, 34
        ])
        ->orderBy('(CASE WHEN sno IN (134, 144, 27, 28, 30, 36) THEN 0 ELSE 1 END) ASC')  // Replacing IF() with CASE
        ->orderBy('head', 'ASC');  // Ordering by head column
    
        // Execute the query and get the results
        $results = $builder->get()->getResultArray();

        return $results;
    }

    public function getJudgesDetails()
    {
        // Load the database library (if it's not already loaded in your controller)
        $builder = $this->db->table('master.judge');

        // Build the query using the query builder
        $query = $builder->select('jcode, jname, first_name, sur_name')
                        ->where('is_retired', 'N')
                        ->where('display', 'Y')
                        ->where('jtype', 'J')
                        ->orderBy('judge_seniority')
                        ->get();

        // Fetch the result as an array
        $results = $query->getResultArray();

        return $results;
    }

    public function getCaseRemarksHeadPending()
    {
        
        $builder = $this->db->table('master.case_remarks_head')
        ->select('*')
        ->where('side', 'P')
        ->where('display', 'Y')
        ->whereNotIn('sno', [
            146, 104, 90, 91, 145, 130, 128, 125, 155, 32, 55, 57, 58, 117, 154, 156,
            105, 191, 84, 102, 83, 150, 106, 153, 159, 126, 38, 152, 148, 131, 118, 11,
            93, 25, 123, 122, 151, 60, 127, 59, 129, 157, 158, 69
        ])
        ->orderBy('(CASE WHEN cat_head_id < 1000 THEN 0 ELSE 1 END) ASC')  // Equivalent to IF() in MySQL
        ->orderBy('head', 'ASC');

        // echo $builder->getCompiledSelect();die;

        // Execute the query and get the results
        $results = $builder->get()->getResultArray();

        return $results;
    }

    public function getDocumentsFiled($diary_no=0)
    {

        $builder = $this->db->table('docdetails a')
        ->select('a.diary_no, a.doccode, a.doccode1, a.docnum, a.docyear, a.filedby, a.docfee, a.forresp, a.feemode, a.ent_dt, a.other1, b.docdesc')
        ->join('master.docmaster b', 'a.doccode = b.doccode AND a.doccode1 = b.doccode1')
        ->where('a.diary_no', $diary_no)
        ->where('a.doccode !=', 8)  // Using `!=` to exclude doccode 8
        ->where('a.display', 'Y')
        ->orderBy('a.ent_dt', 'ASC');  // Assuming ascending order on `ent_dt`

        // Execute the query and retrieve results
        
        $results = $builder->get()->getResultArray();

        return $results;
    }

    public function getInterlocutaryApplications($diary_no=0)
    {
        $builder = $this->db->table('docdetails a')
        ->select('a.diary_no, a.doccode, a.doccode1, a.docnum, a.docyear, a.filedby, a.docfee, a.forresp, a.feemode, a.ent_dt, a.other1, a.iastat, b.docdesc')
        ->join('master.docmaster b', 'a.doccode = b.doccode AND a.doccode1 = b.doccode1')
        ->where('a.diary_no', $diary_no)
        ->where('a.doccode', 8)
        ->where('a.display', 'Y')
        ->where('b.display', 'Y')
        ->orderBy('a.ent_dt', 'ASC');  // Assuming you want ascending order for `ent_dt`

        // Execute the query and retrieve results
        $results = $builder->get()->getResultArray();

        return $results;
    }

    public function getConnectedCases($diary_no=0, $next_dt='')
    {
            $builder = $this->db->table('heardt h')
            ->select("h.diary_no, CONCAT(h.diary_no, ';', m.reg_no_display) AS num")
            ->where('h.conn_key', $diary_no)
            ->where('h.clno !=', 0)
            ->where('h.brd_slno !=', 0)
            ->where('h.judges !=', '')
            ->whereIn('h.main_supp_flag', [1, 2])
            ->where('(h.diary_no)::text !=', 'h.conn_key')
            ->where('DATE(h.next_dt)', $next_dt)
            ->where('m.c_status', 'P')
            ->union(
                $this->db->table('last_heardt lh')
                    ->select("lh.diary_no, CONCAT(lh.diary_no, ';', m2.reg_no_display) AS num")
                    ->join('main m2', 'lh.diary_no = m2.diary_no')
                    ->where('lh.conn_key', $diary_no)
                    ->where('DATE(lh.next_dt)', $next_dt)
                    ->where('m2.c_status', 'P')
                    ->where('lh.clno !=', 0)
                    ->where('lh.brd_slno !=', 0)
                    ->where('lh.judges !=', '')
                    ->whereIn('lh.main_supp_flag', [1, 2])
                    ->where('lh.bench_flag', '')
                    ->where('(lh.diary_no)::text !=', 'lh.conn_key')
            )
            ->join('main m', 'm.diary_no = h.diary_no')
            ->orderBy('CASE WHEN m.conn_key = (h.diary_no)::text THEN \'0\' ELSE 99 END', 'ASC')
            ->orderBy('CAST(SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3 FOR 4) AS INTEGER)', 'ASC')
            ->orderBy('CAST(SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4) AS INTEGER)', 'ASC');

        // echo $builder->getCompiledSelect();die;

        $results = $builder->get()->getResultArray();

        return $results;
    }

    public function checkCourtMaster($ucode=0)
    {
        // Initialize the Query Builder for the 'users' table
        $builder = $this->db->table('master.users');

        // Build the query using the query builder
        $builder->select('is_courtmaster')
                ->where('usercode', $ucode); // Filter by 'usercode'

        // Execute the query and fetch the result
        $query = $builder->get();
        
        $result = $query->getRowArray(); // Fetch the first row as an associative array
        
        return $result['is_courtmaster'];
    }

    public function getWorkingDates($next_dt='')
    {

        // Initialize the Query Builder for the 'sc_working_days' table
        $builder = $this->db->table('master.sc_working_days');

        // Build the query using the query builder
        $builder->select("string_agg(working_date::text, ',') AS dates")
                ->where('holiday_for_registry', 0)  // Filter where holiday_for_registry is 0
                ->where('working_date >', $next_dt)  // Filter where working_date is greater than $last_listing_date
                ->limit(4);  // Limit the results to 4 records

        // Execute the query and fetch the result
        $query = $builder->get();
        
        $result = $query->getRowArray(); // Fetch the result as an associative array
        
        return $result;
    }

    public function getUserDetails($diary_no=0)
    {
        $builder = $this->db->table('main m');
        $builder->select([
            "COALESCE(m.dacode::text, '') AS dacode",
            "COALESCE(u.name, '') AS username",
            'u.empid'
        ]);
        $builder->join('master.users u', 'm.dacode = u.usercode', 'left');
        $builder->where('m.diary_no', $diary_no);

        // echo $builder->getCompiledSelect();die;

        // Execute the query and fetch the result
        $query = $builder->get();
        
        $result = $query->getRowArray(); // Fetch the first row as an associative array
        
        return $result;
    }

    public function getCaseRemarkMultiple($diary_no=0, $next_dt='')
    {
        $builder = $this->db->table('case_remarks_multiple c');
        $builder->select('h.cat_head_id, c.cl_date, c.jcodes, c.status,');
        $builder->select('string_agg(CONCAT(h.head, CASE WHEN c.head_content != \'\' THEN CONCAT(\' [\', c.head_content, \']\') ELSE \'\' END), \', \') AS crem');
        $builder->select('string_agg(CONCAT(c.r_head, \'|\', c.head_content, \'^^\'), \'\') AS caseval');
        $builder->select('c.mainhead, c.clno');
        $builder->join('master.case_remarks_head h', 'c.r_head = h.sno');
        $builder->where('c.diary_no', $diary_no);
        $builder->where('c.cl_date', $next_dt);
        $builder->groupBy('c.cl_date');
        $builder->groupBy('h.cat_head_id, c.jcodes, c.status, c.mainhead, c.clno, h.priority');
        $builder->orderBy('h.priority');

        // $query = $builder->get();


        // echo $builder->getCompiledSelect();die;

        // Execute the query and fetch the result
        $query = $builder->get();
        
        $result = $query->getRowArray(); // Fetch the first row as an associative array
        
        return $result;
    }

    public function getListingDetails($diary_no=0)
    {

        // Subquery for 'heardt' table
        $subquery1 = $this->db->table('heardt lh')
            ->select("string_agg(DISTINCT CONCAT(CONCAT(lh.diary_no::TEXT, ';'), reg_no_display), ',') AS connected")
            ->join('main m', 'm.diary_no = lh.diary_no')
            ->where('lh.conn_key = h.diary_no')
            ->where('lh.clno !=', 0)
            ->where('lh.brd_slno !=', 0)
            ->where('lh.judges !=', '')
            ->whereIn('lh.main_supp_flag', [1, 2])
            ->where('(lh.conn_key)::text !=', 'lh.diary_no')
            ->where('lh.next_dt = h.next_dt')
            ->groupBy('lh.next_dt', 'm.diary_no')
            ->getCompiledSelect(); // Compiles the subquery
        
        // Subquery for 'last_heardt' table
        $subquery2 = $this->db->table('last_heardt lh')
            ->select("string_agg(DISTINCT CONCAT(CONCAT(lh.diary_no::TEXT, ';'), reg_no_display), ',') AS connected")
            ->join('main m', 'm.diary_no = lh.diary_no')
            ->where('lh.conn_key = h.diary_no')
            ->where('lh.clno !=', 0)
            ->where('lh.brd_slno !=', 0)
            ->where('lh.judges !=', '')
            ->whereIn('lh.main_supp_flag', [1, 2])
            ->where('(lh.conn_key)::text !=', 'lh.diary_no')
            ->where('lh.next_dt = h.next_dt')
            ->groupBy('lh.next_dt', 'h.diary_no')
            ->getCompiledSelect(); // Compiles the subquery
        
        // Main query for selecting from 'heardt' and 'last_heardt' tables
        $builder = $this->db->table('heardt h');
        $builder->select("diary_no, next_dt, mainhead, subhead, listed_ia, listorder, sitting_judges, board_type, brd_slno, judges, 'H' AS hl, ent_dt, roster_id, main_supp_flag, ($subquery1) AS connected")
            ->where('diary_no', $diary_no)
            ->where('clno !=', 0)
            ->where('brd_slno !=', 0)
            ->where('judges !=', '')
            ->whereIn('main_supp_flag', [1, 2]);

        // Union with the second part of the query (from 'last_heardt' table)
        $builder->union(function($builder) use ($subquery2, $diary_no) {
            $builder->select("diary_no, next_dt, mainhead, subhead, listed_ia, listorder, sitting_judges, board_type, brd_slno, judges, 'L' AS hl, ent_dt, roster_id, main_supp_flag, ($subquery2) AS connected")
                ->from('last_heardt h')
                ->where('diary_no', $diary_no)
                ->where('clno !=', 0)
                ->where('brd_slno !=', 0)
                ->where('judges !=', '')
                ->whereIn('main_supp_flag', [1, 2])
                ->where('bench_flag', '');
        });
        
        // Group by, order by, and limit
        $builder->groupBy('next_dt, judges')
            ->groupBy('h.diary_no')
            ->orderBy('next_dt', 'desc')
            ->orderBy('ent_dt', 'desc');
        
        // echo $builder->getCompiledSelect();die;

        // Execute the query and fetch the result
        $query = $builder->get();
        
        $result = $query->getResultArray(); // Fetch all rows as an associative array
        
        return $result;
    }

    public function getCaseRemarksMultiple($diary_no=0)
    {

        // Initialize the Query Builder for the 'case_remarks_multiple' table
        $builder = $this->db->table('case_remarks_multiple');

        // Build the query using the query builder
        $builder->select('r_head, cl_date, head_content')
                ->where('diary_no', $diary_no)
                ->orderBy('cl_date', 'desc') // Order by cl_date in descending order
                ->limit(1); // Get only the most recent record

        // echo $builder->getCompiledSelect();die;

        // Execute the query and fetch the result
        $query = $builder->get();
        
        $result = $query->getRowArray(); // Fetch the first row as an associative array

        return $result;
    }

    public function getCaseStatusFlag($flag_name='tentative_listing_date')
    {

        // Initialize the Query Builder for the 'case_status_flag' table
        $builder = $this->db->table('master.case_status_flag');

        // Build the query using the query builder
        $builder->select('display_flag, always_allowed_users')
                ->where("to_date", null)  // Using DATE() to match the date as in your query
                ->where('flag_name', $flag_name);

        // Execute the query and fetch the result
        $query = $builder->get();
        
        $result = $query->getRowArray(); // Fetch the first row as an associative array

        return $result;
    }


    public function getTentativeDate($diary_no=0)
    {
        // Initialize the Query Builder for the 'heardt' table
        $builder = $this->db->table('heardt');

        // Build the query using the query builder
        $builder->select('tentative_cl_dt')
                ->where('diary_no', $diary_no);

        // echo $builder->getCompiledSelect();die;

        // Execute the query and fetch the result
        $query = $builder->get();
        
        $result = $query->getRowArray(); // Fetch the first row as an associative array

        return $result;
    }

    public function getNextHearingDate($diary_no=0)
    {
        // Initialize the Query Builder for the 'heardt' table
        $builder = $this->db->table('heardt');

        // Build the query using the query builder
        $builder->select('next_dt')
                ->where('diary_no', $diary_no)
                ->where('judges !=', '')  // Ensures judges are not empty
                ->where('judges !=', '0')   // Ensures judges are not 0
                ->where('clno >', 0)      // Ensures clno is greater than 0
                ->where('brd_slno >', 0)  // Ensures brd_slno is greater than 0
                ->where('roster_id >', 0); // Ensures roster_id is greater than 0

        // Execute the query and fetch the result
        $query = $builder->get();
        
        $result = $query->getRowArray(); // Fetch the first row as an associative array
        
        return $result;
    }

    public function getActLawDetails($actcode=0)
    {
                    
        // Initialize the Query Builder for the 'caselaw' table
        $builder = $this->db->table('master.caselaw');
        
        // Build the query using the query builder
        $builder->select('law')
                ->where('id', $actcode);
        
        // Execute the query and fetch the result
        $query = $builder->get();
        
        $result = $query->getRowArray(); // Fetch the first row as an associative array

        return $result;
    }

    public function getAdvocateDetails($diary_no=0)
    {
        // Initialize the Query Builder for the 'advocate' table
        $builder = $this->db->table('advocate a');

        // Build the query using the query builder
        $builder->select('a.pet_res_no, a.adv, a.advocate_id, a.pet_res, a.is_ac, b.if_aor, b.if_sen, b.if_other')
                ->join('master.bar b', 'a.advocate_id = b.bar_id', 'inner') // Join the 'bar' table
                ->where('a.diary_no', $diary_no)
                ->where('a.display', 'Y')
                ->orderBy('a.pet_res'); // Order by pet_res

        // Execute the query and fetch the result
        $query = $builder->get();
        
        $advocate_rows = $query->getResultArray(); // Get all rows

        return $advocate_rows;
    }

    public function getActDetails($diary_no=0)
    {
        // Initialize the Query Builder for the 'act_main' table
        $builder = $this->db->table('act_main a');
                            
        // Build the query using the query builder
        $builder->select("a.act, string_agg(b.section::text, ',') as section, c.act_name")
                ->join('master.act_section b', 'a.id = b.act_id', 'left')
                ->join('master.act_master c', 'c.id = a.act', 'inner') // Assuming this join is required as it was in your query
                ->where('a.diary_no', $diary_no)
                ->where('a.display', 'Y')
                ->where('b.display', 'Y')
                ->where('c.display', 'Y')
                ->groupBy('a.act, c.act_name');

        // echo $builder->getCompiledSelect();die;

        // Execute the query and fetch the result
        $query = $builder->get();
        
        $act_rows = $query->getResultArray(); // Get all matching rows

        return $act_rows;
    }

    public function getLowerctTentativeSection($casetype_id=0, $case_number=0, $case_year=0)
    {

        $builder = $this->db->table('main_casetype_history a');

        // Select fields
        $builder->select('a.diary_no');
        $builder->select('new_registration_number');
        $builder->select("split_part(new_registration_number, '-', 2) AS part1");
        $builder->select("split_part(new_registration_number, '-', -1) AS part2");
        $builder->select('dacode');
        $builder->select('name');
        $builder->select('section_name');
        $builder->select('casetype_id');
        $builder->select('active_casetype_id');
        $builder->select('diary_no_rec_date');
        $builder->select('reg_year_mh');
        $builder->select('reg_year_fh');
        $builder->select('active_reg_year');
        $builder->select('ref_agency_state_id');

        // Join with other tables
        $builder->join('main b', 'a.diary_no = b.diary_no', 'left');
        $builder->join('master.users c', 'b.dacode = c.usercode', 'left');
        $builder->join('master.usersection us', 'c.section = us.id', 'left');

        // Where conditions
        $builder->where('ref_new_case_type_id', $casetype_id);
        $builder->where('new_registration_year', $case_year);
        $builder->where('is_deleted', 'f');

        // Handling the dynamic case number
        $caseNo = (int) $case_number;
        $builder->where("$caseNo BETWEEN cast(split_part(new_registration_number, '-', 2) as INTEGER) AND cast(split_part(new_registration_number, '-', -1) as INTEGER)");
        
        // echo $builder->getCompiledSelect();die;

        // Execute the query and fetch the result
        $query = $builder->get();

        $for_da_temp_row = $query->getRowArray();

        return $for_da_temp_row;
    }

    public function getLowerctCaseTypeDetails($diary_no=0)
    {
        // Initialize the Query Builder for the 'lowerct' table
        $builder = $this->db->table('lowerct');

        // Build the query using the query builder
        $builder->select('ct_code, l_state, lct_casetype, lct_caseno, lct_caseyear')
                ->where('diary_no', $diary_no)
                ->where('lw_display', 'Y');

        // Execute the query and fetch the result
        $query = $builder->get();
        
        $lower_case_temp_row = $query->getRowArray();

        return $lower_case_temp_row;
    }

    public function getTentativeSection($casetype_displ=0, $state_id=0, $ten_reg_yr=0)
    {
        // Initialize the Query Builder for the 'da_case_distribution' table
        $builder = $this->db->table('master.da_case_distribution a');
        
        // Build the query using the query builder
        $builder->select('a.dacode, c.section_name, b.name')
                ->join('master.users b', 'b.usercode = a.dacode', 'left')
                ->join('master.usersection c', 'b.section = c.id', 'left')
                ->where('a.case_type', $casetype_displ)
                ->where('a.display', 'Y')
                ->where('a.state', $state_id)
                ->where('a.case_f_yr <=', $ten_reg_yr)
                ->where('a.case_t_yr >=', $ten_reg_yr);
        
        // Execute the query and fetch the result
        $query = $builder->get();
        $section_ten_row = $query->getRowArray();

        return $section_ten_row;
    }

    public function getDADetails($diary_no=0)
    {
    
        // Initialize the Query Builder for the 'main' table
        $builder = $this->db->table('main a');

        // Build the query using the query builder
        $builder->select('a.dacode, b.name, us.section_name, a.casetype_id, a.active_casetype_id, a.diary_no_rec_date, a.reg_year_mh, a.reg_year_fh, a.active_reg_year, a.ref_agency_state_id')
                ->join('master.users b', 'a.dacode = b.usercode', 'left')
                ->join('master.usersection us', 'b.section = us.id', 'left')
                ->where('a.diary_no', $diary_no);

        // echo $builder->getCompiledSelect();die;

        // Execute the query and fetch the result
        $query = $builder->get();
        
        $row_da = $query->getRowArray();

        return $row_da ?? [];
    }

    public function getLowerctDetails($diary_no=0)
    {
        // Initialize the Query Builder for the 'lowerct' table
        $builder = $this->db->table('lowerct a');
                            
        // Create the query using the builder
        $builder->select('a.lct_dec_dt, a.lct_caseno, a.lct_caseyear, ct.short_description AS type_sname')
                ->join('master.casetype ct', "ct.casecode = a.lct_casetype AND ct.display = 'Y' AND a.is_order_challenged = 'Y'", 'left')
                ->where('a.diary_no', $diary_no)
                ->where('a.lw_display', 'Y')
                ->where('a.ct_code', 4)
                ->orderBy('a.lct_dec_dt');

        // Execute the query and fetch the results
        $query = $builder->get();

        $result = $query->getResultArray();

        return $result;
    }
    
    public function getDistrictName($state_code='', $city_code='')
    {
        // Query using CI4 Query Builder
        $builder = $this->db->table('master.state');

        // Add the necessary WHERE conditions
        $builder->select('name')
                ->where('state_code', $state_code)
                ->where('district_code', $city_code)
                ->where('sub_dist_code', 0)
                ->where('village_code', 0)
                ->where('display', 'Y');

        // echo $builder->getCompiledSelect();die;

        // Execute the query
        $query = $builder->get();

        $result = $query->getRowArray();

        return $result;
    }

    public function getPartyDetails($diary_no=0)
    {
        // Initialize the Query Builder
        $builder = $this->db->table('party p');
        
        // Create the query using the builder
        $builder->select('p.sr_no, p.pet_res, p.ind_dep, p.partyname, p.sonof, p.prfhname, p.age, p.sex, p.caste, p.addr1, p.addr2, 
                        p.pin, p.state, p.city, p.email, p.contact AS mobile, p.deptcode, 
                        (SELECT deptname FROM master.deptt WHERE deptcode=p.deptcode) AS deptname, c.skey')
                ->join('main m', "m.diary_no = p.diary_no AND sr_no = 1 AND pflag = 'P' AND pet_res IN ('P', 'R')", 'inner')
                ->join('master.casetype c', 'cast(c.casecode as TEXT) = SUBSTRING(m.diary_no::TEXT FROM 3 FOR 3)', 'left')
                ->where('m.diary_no', $diary_no)
                ->orderBy('p.pet_res')
                ->orderBy('p.sr_no');
            
        // echo $builder->getCompiledSelect();die;

        // Execute the query and fetch the results
        $query = $builder->get();
        
        $result = $query->getResultArray();
        
        return $result;
    }

    public function ifInAdvanceList($diaryNo=0)
    {
        $result = false;

        $builder = $this->db->table('advance_allocated aa');

        // Select the needed fields
        $builder->select('aa.next_dt');
        $builder->select('ad.*');

        // Join the tables
        $builder->join('advanced_drop_note ad', 'aa.diary_no = ad.diary_no AND aa.next_dt = ad.cl_date', 'left');
        $builder->join('heardt h', "aa.diary_no = (h.diary_no)::text AND h.next_dt = aa.next_dt", 'left');

        // Where conditions
        $builder->where('h.diary_no IS NOT NULL');
        $builder->where('aa.diary_no', $diaryNo);
        $builder->where('aa.next_dt >=', 'CURRENT_DATE', false);

        // Group by the conn_key field
        $builder->groupBy('aa.conn_key');
        $builder->groupBy('aa.next_dt, ad.id');

        // echo $builder->getCompiledSelect();die;

        // Perform the query
        $query = $builder->get();

        // Check if rows were returned
        if ($query->getNumRows() > 0) {
            $result = true;
        }

        return $result;
    }

    public function check_parties($cn, $dt)
    {
        $builder = $this->db->table('case_remarks_multiple');

        // Build the query
        $builder->where('diary_no', $cn);
        $builder->where('cl_date', $dt);
        $builder->where('r_head', 91);
    
        // Execute the query
        $query = $builder->get();

        // $sql_check = "SELECT * FROM case_remarks_multiple WHERE diary_no='" . $cn . "' AND cl_date='" . $dt . "' AND r_head=91";
        // $res = mysql_query($sql_check);

        if ($query->getNumRows() <= 0) {

            $builder = $this->db->table('abr_accused');

            // Perform the delete operation
            $builder->where('diary_no', $cn);
            $builder->where('ord_dt', $dt);
            $result = $builder->delete();

            // $sql_del = "DELETE FROM abr_accused WHERE diary_no='" . $cn . "' AND ord_dt='" . $dt . "'";
            // mysql_query($sql_del);
        }
    }

    public function update_lastheardt($fno_o, $dt_o, $head_o, $head_cont_o, $hdt, $ucode1, $snop_o)
    {
        $t_hr = $t_lo = 0;
        $t_chk = 0;
        //and clno!=0 and brd_slno!=0 and judges!='' and main_supp_flag in (1,2) and bench_flag=''
    
        $builder = $this->db->table('last_heardt');

        // Build the query with necessary conditions
        $builder->select('diary_no, next_dt, judges, subhead, mainhead, listorder');
        $builder->where('diary_no', $fno_o);
        $builder->where('next_dt', $dt_o);
        $builder->where('clno !=', 0);
        $builder->where('brd_slno !=', 0);
        $builder->where("judges != ''");
        $builder->whereIn('main_supp_flag', [1, 2]);
        $builder->where("bench_flag = ''");

        // Execute the query
        $results_cis = $builder->get();        
        
        // $str_cis = "SELECT diary_no, next_dt, judges, subhead,mainhead,listorder FROM last_heardt WHERE diary_no='" . $fno_o . "' AND next_dt='" . $dt_o . "' and clno!=0 and brd_slno!=0 and judges!='' and main_supp_flag in (1,2) and bench_flag=''";
        // $results_cis = mysql_query($str_cis) or die(mysql_error());

        if ($this->db->affectedRows() > 0) {
            $up_str = "";
            $side = "";
            $disp_code = "";
            $subhead = 0;
            $nature = "";
            $row_cis = $results_cis->getRowArray();
            $j1 = $row_cis["judges"];
            $subhead = $row_cis["subhead"];

            for ($i = 0; $i < count($head_o); $i++) {
                if ($head_o[$i] == "7")
                    $t_hr = 1;

                if ($head_o[$i] == "37" or $head_o[$i] == "73" or $head_o[$i] == "78") {
                    $t_chk = 1;
                    if ($head_o[$i] == "53" or $head_o[$i] == "54" or $head_o[$i] == "23" or $head_o[$i] == "68" or $head_o[$i] == "8" or $head_o[$i] == "21")
                        $t_lo = 2;
                    if ($head_o[$i] == "24")
                        $t_lo = 1;
                }

                $builder = $this->db->table('case_remarks_head');

                // Build the query with the condition
                $builder->select('sno, head, side, cis_disp_code');
                $builder->where('sno', $head_o[$i]);

                // Execute the query
                $results_cr = $builder->get();
                
                // $str_cr = "SELECT sno,head,side,cis_disp_code FROM case_remarks_head WHERE sno=" . $head_o[$i] . "";
                // $results_cr = mysql_query($str_cr) or die(mysql_error() . $str_cr);

                if ($this->db->affectedRows() > 0) {
                    if ($i > 0)
                        $up_str .= ", ";
                    $row_cr = $results_cr->getRowArray();

                    $up_str .= $row_cr["head"];
                    if ($head_cont_o[$i] != "")
                        $up_str .= " (" . $head_cont_o[$i] . ")";

                    $side = $row_cr["side"];
                    $disp_code = $row_cr["cis_disp_code"];
                }
            }

            $tdt = explode("-", $dt_o);
            $up_str .= "-Ord dt:" . $tdt[2] . "-" . $tdt[1] . "-" . $tdt[0];

            $t_head_code = '';

            $builder = $this->db->table('main');

            // Prepare the data to be updated
            $data = [
                'last_dt' => date('Y-m-d H:i:s'),  // Use current timestamp
                'lastorder' => $up_str,
                'head_code' => $t_head_code,
                'last_usercode' => $ucode1
            ];

            // Perform the update query
            $builder->set($data);
            $builder->where('diary_no', $fno_o);

            // Execute the query
            $builder->update();

            // $str_up_main = "UPDATE main SET last_dt=NOW(),lastorder='" . addslashes($up_str) . "', head_code='" . $t_head_code . "' , last_usercode=" . $ucode1 . " where diary_no='" . $fno_o . "'";
            // mysql_query($str_up_main) or die(mysql_error() . $str_up_main);
        }
    }

    public function update_lastorder($fno_o, $dt_o, $head_o, $head_cont_o, $hdt, $ucode1)
    {
        $t_hr = $t_lo = 0;
        $t_chk = 0;

        // Prepare the raw SQL query using UNION
        $sql = "
            SELECT diary_no, next_dt, judges, subhead, mainhead, listorder 
            FROM heardt 
            WHERE diary_no = ? AND next_dt = ? AND main_supp_flag IN (1, 2)
            UNION
            SELECT diary_no, next_dt, judges, subhead, mainhead, listorder 
            FROM last_heardt 
            WHERE diary_no = ? AND next_dt = ? AND main_supp_flag IN (1, 2)
        ";

        // Execute the query with parameters
        $results_cis = $this->db->query($sql, [$fno_o, $dt_o, $fno_o, $dt_o]);


        // $str_cis = "SELECT * FROM
        // (
        // (SELECT diary_no, next_dt, judges, subhead,mainhead,listorder FROM heardt WHERE diary_no='" . $fno_o . "' AND next_dt='" . $dt_o . "' AND main_supp_flag IN (1,2))
        // UNION
        // (SELECT diary_no, next_dt, judges, subhead,mainhead,listorder FROM last_heardt WHERE diary_no='" . $fno_o . "' AND next_dt='" . $dt_o . "' AND  main_supp_flag IN (1,2))
        //     ) a";
        // $results_cis = mysql_query($str_cis) or die(mysql_error());

        if ($this->db->affectedRows() > 0) {
            $up_str = "";
            $side = "";
            $disp_code = "";
            $subhead = 0;
            $nature = "";
            $row_cis = $results_cis->getRowArray();
            $j1 = $row_cis["judges"];
            $subhead = $row_cis["subhead"];

            for ($i = 0; $i < count($head_o); $i++) {
                if ($head_o[$i] == "7")
                    $t_hr = 1;

                if ($head_o[$i] == "37" or $head_o[$i] == "73" or $head_o[$i] == "78") {
                    $t_chk = 1;
                    if ($head_o[$i] == "53" or $head_o[$i] == "54" or $head_o[$i] == "23" or $head_o[$i] == "68" or $head_o[$i] == "8" or $head_o[$i] == "21")
                        $t_lo = 2;
                    if ($head_o[$i] == "24")
                        $t_lo = 1;
                }
                
                $builder = $this->db->table('case_remarks_head');

                // Build the query with the condition
                $builder->select('sno, head, side, cis_disp_code');
                $builder->where('sno', $head_o[$i]);

                // Execute the query
                $results_cr = $builder->get();

                if ($this->db->affectedRows() > 0) {
                    if ($i > 0)
                        $up_str .= ", ";
                    $row_cr = $results_cr->getRowArray();

                    $up_str .= $row_cr["head"];
                    if ($head_cont_o[$i] != "")
                        $up_str .= " (" . $head_cont_o[$i] . ")";

                    $side = $row_cr["side"];
                    $disp_code = $row_cr["cis_disp_code"];
                }
            }

            $tdt = explode("-", $dt_o);
            $up_str .= "-Ord dt:" . $tdt[2] . "-" . $tdt[1] . "-" . $tdt[0];

            $t_head_code = '';

            $builder = $this->db->table('main');

            // Prepare the data to be updated
            $data = [
                'last_dt' => date('Y-m-d H:i:s'), // Current timestamp
                'lastorder' => $up_str,
                'head_code' => $t_head_code,
                'last_usercode' => $ucode1
            ];

            // Perform the update query with the condition
            $builder->set($data)
                ->where('diary_no', $fno_o)
                ->groupStart()
                    ->where('lastorder', '')
                    ->orWhere('lastorder IS NULL')
                ->groupEnd();

            // Execute the update
            $builder->update();

            // $str_up_main = "UPDATE main SET last_dt=NOW(),lastorder='" . addslashes($up_str) . "', head_code='" . $t_head_code . "' , last_usercode=" . $ucode1 . " where diary_no='" . $fno_o . "' and (lastorder='' OR lastorder is NULL)";
            // mysql_query($str_up_main) or die(mysql_error() . $str_up_main);
        }
    }

    public function chk_disp_date($disp_dt)
    {
        //$datetime1 = date_create($disp_dt);
        //$datetime2 = date_create(date('Y-m-d'));
        //$interval = date_diff($datetime1, $datetime2);
        //return ($interval->format('%Y') * 12) + $interval->format('%m');
        $m1 = date('m', strtotime($disp_dt));
        $y1 = date('Y', strtotime($disp_dt));
        $m2 = date('m');
        $y2 = date('Y');
        $y = $y2 - $y1;
        if ($m2 >= $m1) {
            $m = $m2 - $m1;
        } else {
            $m = 12 - ($m1 - $m2);
            $y--;
        }
        $rm = ($y * 12) + $m;
        return $rm;
    }

    public function set_before($t_diary_no, $j1, $j2, $j3, $j4, $j5, $uid, $uip, $umac)
    {
        $sql = "
            SELECT
                t1.diary_no,
                t2.bailno,
                t3.notbef,
                t1.crimeno,
                t1.crimeyear,
                h.jud1,
                h.jud2,
                h.jud3,
                h.jud4,
                h.jud5,
                h.clno,
                h.brd_slno,
                h.next_dt,
                CASE WHEN h.next_dt IS NULL THEN 'N' ELSE 'Y' END AS listed,
                t3.res_add
            FROM
                (
                    SELECT
                        a.diary_no,
                        a.crimeno,
                        a.crimeyear
                    FROM
                        lowerct a
                    INNER JOIN
                        (
                            SELECT
                                diary_no,
                                l_dist,
                                polstncode,
                                crimeno,
                                crimeyear
                            FROM
                                lowerct
                            WHERE diary_no = ?
                        ) b
                    INNER JOIN main m ON m.diary_no = a.diary_no
                    INNER JOIN mul_category mulc ON mulc.diary_no = m.diary_no
                    WHERE
                        m.bailno = 1
                        AND m.category = 121
                        AND m.subcat = 2
                        AND (m.subcat1 = 4 OR m.subcat1 = 5)
                        AND a.l_dist = b.l_dist
                        AND a.polstncode = b.polstncode
                        AND a.crimeno = b.crimeno
                        AND a.crimeyear = b.crimeyear
                        AND a.diary_no != b.diary_no
                        AND SUBSTRING(a.diary_no FROM 3 FOR 3) = '052'
                        AND a.crimeno != 0
                        AND a.crimeyear != 0
                ) t1
            LEFT JOIN main t2 ON t1.diary_no = t2.diary_no
                AND t2.c_status = 'P'
                AND t2.bailno = 1
            LEFT JOIN not_before t3 ON t1.diary_no = t3.diary_no
                AND t3.notbef = 'B'
            LEFT JOIN heardt h ON t1.diary_no = h.diary_no
                AND h.jud1 NOT IN (200, 250, 514)
                AND h.next_dt >= CURRENT_DATE
                AND h.brd_slno > 0
                AND h.clno > 0
            HAVING
                t3.notbef IS NULL
                AND t2.bailno IS NOT NULL;
        ";

        // Execute the query with the provided parameter
        $query = $this->db->query($sql, [$t_diary_no]);

        // Fetch the results
        $results_before1 = $query->getResultArray();

        // $sql_before1 = "SELECT
        //     t1.diary_no,
        //     t2.bailno,
        //     t3.notbef,
        //     t1.crimeno,
        //     t1.crimeyear,
        //     h.jud1,
        //     h.jud2,
        //     h.jud3,
        //     h.jud4,
        //     h.jud5,
        //     h.clno,
        //     h.brd_slno,
        //     h.next_dt,
        //     IF(h.next_dt IS NULL, 'N', 'Y') AS listed, t3.res_add
        //         FROM
        //     (SELECT
        //         a.diary_no,
        //         a.crimeno,
        //         a.crimeyear
        //     FROM
        //         lowerct a
        //         INNER JOIN
        //         (SELECT
        //             diary_no,
        //             l_dist,
        //             polstncode,
        //             crimeno,
        //             crimeyear
        //         FROM
        //             lowerct
        //         WHERE diary_no = '" . $t_diary_no . "') b
        //         INNER JOIN main m
        //         ON m.diary_no = a.diary_no  inner join mul_category mulc on mulc.diary_no = m.diary_no inner join
        //         AND m.bailno = 1
        //         AND m.category = 121
        //         AND m.subcat = 2
        //         AND (m.subcat1 = 4 or m.subcat1 = 5)
        //         AND a.l_dist = b.l_dist
        //         AND a.polstncode = b.polstncode
        //         AND a.crimeno = b.crimeno
        //         AND a.crimeyear = b.crimeyear
        //         AND a.diary_no != b.diary_no
        //         AND SUBSTR(a.diary_no, 3, 3) = '052'
        //         AND a.crimeno != 0
        //         AND a.crimeyear != 0) t1
        //     LEFT JOIN main t2
        //         ON t1.diary_no = t2.diary_no
        //         AND t2.c_status = 'P'
        //         AND t2.bailno = 1
        //     LEFT JOIN not_before t3
        //         ON t1.diary_no = t3.diary_no
        //         AND t3.notbef = 'B'
        //     LEFT JOIN heardt h
        //         ON t1.diary_no = h.diary_no
        //         AND h.jud1 NOT IN (200, 250, 514)
        //         AND h.next_dt >= CURDATE()
        //         AND h.brd_slno > 0
        //         AND h.clno > 0
        //         HAVING t3.notbef IS NULL
        //         AND t2.bailno IS NOT NULL";
        // $result_before1 = mysql_query($sql_before1) or die(mysql_error() . $sql_before1);

        foreach($results_before1 as $row_before1) 
        {
            if ($row_before1["bailno"] == '1') 
            {
                //if($row_before1["listed"]=='N'){

                $builder = $this->db->table('not_before');

                // Prepare the data for insertion
                $data = [
                    'diary_no' => $row_before1['diary_no'],
                    'jud1' => $j1,
                    'jud2' => $j2,
                    'jud3' => $j3,
                    'jud4' => $j4,
                    'jud5' => $j5,
                    'notbef' => 'B',
                    'uid' => $uid,
                    'created_at' => date('Y-m-d H:i:s'),  // Current timestamp
                    'ip' => $uip,
                    'mac' => $umac,
                    'source' => 'RDR',
                    'note' => 'SAME CRIME NO.'
                ];

                // Insert the data into the 'not_before' table
                $builder->insert($data);

                // $sql_before2 = "INSERT INTO not_before VALUES('" . $row_before1["diary_no"] . "'," . $j1 . "," . $j2 . "," . $j3 . "," . $j4 . "," . $j5 . ",'B'," . $uid . ",NOW(),'" . $uip . "','" . $umac . "','RDR','SAME CRIME NO.')";
                // mysql_query($sql_before2) or die(mysql_error() . $sql_before2);

                //}
                //if($row_before1["listed"]=='Y'){
                //if($row_before1["jud1"]==$j1 and $row_before1["jud2"]==$j2 and $row_before1["jud3"]==$j3 and $row_before1["jud4"]==$j4 and $row_before1["jud5"]==$j5){
                //$sql_before3="INSERT INTO not_before VALUES('".$row_before1["diary_no"]."',".$j1.",".$j2.",".$j3.",".$j4.",".$j5.",'B',".$uid.",NOW(),'".$uip."','".$umac."','RDR','SAME CRIME NO.')";
                //mysql_query($sql_before3) or die(mysql_error());
                //}
                //}
            }
        }
    }

    public function undo_before($t_diary_no, $cldate, $uid, $uip, $umac)
    {
        // Prepare the raw SQL query
        $sql = "
            SELECT
                t1.diary_no,
                t2.bailno,
                t3.notbef,
                t3.j1, t3.j2, t3.j3, t3.j4, t3.j5, t3.usercode, t3.ent_dt, t3.u_ip, t3.u_mac, t3.enterby,
                t1.crimeno,
                t1.crimeyear,
                h.jud1,
                h.jud2,
                h.jud3,
                h.jud4,
                h.jud5,
                h.clno,
                h.brd_slno,
                h.next_dt,
                CASE WHEN h.next_dt IS NULL THEN 'N' ELSE 'Y' END AS listed,
                t3.res_add,
                t2.c_status,
                CASE WHEN (t2.c_status = 'D' AND DATE(t3.ent_dt) >= ? AND t3.enterby = 'RDR') THEN 1 ELSE 0 END AS dcheck
            FROM
                (SELECT
                    a.diary_no,
                    a.crimeno,
                    a.crimeyear
                FROM
                    lowerct a
                INNER JOIN
                    (SELECT
                        diary_no,
                        l_dist,
                        polstncode,
                        crimeno,
                        crimeyear
                    FROM
                        lowerct
                    WHERE diary_no = ?) b
                INNER JOIN main m
                    ON m.diary_no = a.diary_no
                    AND m.bailno = 1
                    AND m.category = 121
                    AND m.subcat = 2
                    AND (m.subcat1 = 4 OR m.subcat1 = 5)
                    AND a.l_dist = b.l_dist
                    AND a.polstncode = b.polstncode
                    AND a.crimeno = b.crimeno
                    AND a.crimeyear = b.crimeyear
                    AND a.diary_no != b.diary_no
                    AND SUBSTRING(a.diary_no FROM 3 FOR 3) = '052'
                    AND a.crimeno != 0
                    AND a.crimeyear != 0) t1
            LEFT JOIN main t2
                ON t1.diary_no = t2.diary_no
                AND t2.bailno = 1
            LEFT JOIN not_before t3
                ON t1.diary_no = t3.diary_no
                AND t3.notbef = 'B'
                AND t3.enterby = 'RDR'
                AND DATE(t3.ent_dt) >= ?
            LEFT JOIN heardt h
                ON t1.diary_no = h.diary_no
                AND h.jud1 NOT IN (200, 250, 514)
                AND h.next_dt > CURRENT_DATE
                AND h.brd_slno > 0
                AND h.clno > 0
            HAVING t3.notbef = 'B'
                AND t2.bailno IS NOT NULL
            ORDER BY t2.c_status ASC;
        ";

        // Execute the query with the parameters
        $query = $this->db->query($sql, [$cldate, $t_diary_no, $cldate]);

        // Fetch the results
        $results_before4 = $query->getResultArray();


        // $sql_before4 = "SELECT
        //     t1.diary_no,
        //     t2.bailno,
        //     t3.notbef,
        //     t3.j1,t3.j2,t3.j3,t3.j4,t3.j5,t3.usercode,t3.ent_dt,t3.u_ip,t3.u_mac,t3.enterby,
        //     t1.crimeno,
        //     t1.crimeyear,
        //     h.jud1,
        //     h.jud2,
        //     h.jud3,
        //     h.jud4,
        //     h.jud5,
        //     h.clno,
        //     h.brd_slno,
        //     h.next_dt,
        //     IF(h.next_dt IS NULL, 'N', 'Y') AS listed, t3.res_add, t2.c_status, IF((t2.c_status='D' AND DATE(t3.ent_dt)>='" . $cldate . "' AND t3.enterby='RDR'),1,0) AS dcheck
        //         FROM
        //         (SELECT
        //             a.diary_no,
        //             a.crimeno,
        //             a.crimeyear
        //         FROM
        //             lowerct a
        //             INNER JOIN
        //             (SELECT
        //                 diary_no,
        //                 l_dist,
        //                 polstncode,
        //                 crimeno,
        //                 crimeyear
        //             FROM
        //                 lowerct
        //             WHERE diary_no = '" . $t_diary_no . "') b
        //             INNER JOIN main m
        //             ON m.diary_no = a.diary_no
        //             AND m.bailno = 1
        //             AND m.category = 121
        //             AND m.subcat = 2
        //             AND (m.subcat1 = 4 or m.subcat1 = 5)
        //             AND a.l_dist = b.l_dist
        //             AND a.polstncode = b.polstncode
        //             AND a.crimeno = b.crimeno
        //             AND a.crimeyear = b.crimeyear
        //             AND a.diary_no != b.diary_no
        //             AND SUBSTR(a.diary_no, 3, 3) = '052'
        //             AND a.crimeno != 0
        //             AND a.crimeyear != 0) t1
        //         LEFT JOIN main t2
        //             ON t1.diary_no = t2.diary_no
        //             AND t2.bailno = 1
        //         LEFT JOIN not_before t3
        //             ON t1.diary_no = t3.diary_no
        //             AND t3.notbef = 'B' AND t3.enterby='RDR' AND DATE(t3.ent_dt)>='" . $cldate . "'
        //         LEFT JOIN heardt h
        //             ON t1.diary_no = h.diary_no
        //             AND h.jud1 NOT IN (200, 250, 514)
        //             AND h.next_dt > CURDATE()
        //             AND h.brd_slno > 0
        //             AND h.clno > 0
        //         HAVING t3.notbef='B'
        //         AND t2.bailno IS NOT NULL  ORDER BY  t2.c_status ASC";

        //  LEFT JOIN main t2
        //    ON t1.diary_no = t2.diary_no
        //   AND t2.c_status = 'P'
        //   AND t2.bailno = 1
        // $result_before4 = mysql_query($sql_before4) or die(mysql_error() . $sql_before4);

        $tj1 = $tj2 = $tj3 = $tj4 = $tj5 = "";
        foreach($results_before4 as $row_before4) {
            if ($row_before4["dcheck"] == 1 and $row_before4["c_status"] == 'D') {
                $tj1 = $row_before4["j1"];
                $tj2 = $row_before4["j2"];
                $tj3 = $row_before4["j3"];
                $tj4 = $row_before4["j4"];
                $tj5 = $row_before4["j5"];
            }
            if ($row_before4["bailno"] == 1) {
                //if($row_before4["listed"]=='N'){
                if (!($row_before4["j1"] == $tj1 and $row_before4["j2"] == $tj2 and $row_before4["j3"] == $tj3 and $row_before4["j4"] == $tj4 and $row_before4["j5"] == $tj5)) {
                    if ($row_before4["c_status"] == 'P') {

                        $builder = $this->db->table('not_before_his');

                        // Prepare the data for insertion
                        $data = [
                            'diary_no' => $row_before4['diary_no'],
                            'j1' => $row_before4['j1'],
                            'j2' => $row_before4['j2'],
                            'j3' => $row_before4['j3'],
                            'j4' => $row_before4['j4'],
                            'j5' => $row_before4['j5'],
                            'notbef' => $row_before4['notbef'],
                            'usercode' => $row_before4['usercode'],
                            'ent_dt' => $row_before4['ent_dt'],
                            'old_u_ip' => $row_before4['u_ip'],
                            'old_u_mac' => $row_before4['u_mac'],
                            'cur_u_ip' => $uip,
                            'cur_u_mac' => $umac,
                            'cur_ucode' => $uid,
                            'c_dt' => date('Y-m-d H:i:s'),  // Current timestamp
                            'action' => 'RDRW',
                            'old_res_add' => $row_before4['res_add'],
                            'enterby_old' => $row_before4['enterby']
                        ];

                        // Insert the data into the 'not_before_his' table
                        $builder->insert($data);

                        
                        // $sql_before5 = "INSERT INTO not_before_his(diary_no,j1,j2,j3,j4,j5,notbef,usercode,ent_dt,old_u_ip,old_u_mac,cur_u_ip,cur_u_mac,cur_ucode,c_dt,action,old_res_add,enterby_old) VALUES('" . $row_before4["diary_no"] . "'," . $row_before4["j1"] . "," . $row_before4["j2"] . "," . $row_before4["j3"] . "," . $row_before4["j4"] . "," . $row_before4["j5"] . ",'" . $row_before4["notbef"] . "'," . $row_before4["usercode"] . ",'" . $row_before4["ent_dt"] . "','" . $row_before4["u_ip"] . "','" . $row_before4["u_mac"] . "','" . $uip . "','" . $umac . "'," . $uid . ",NOW(),'RDRW','" . $row_before4["res_add"] . "','" . $row_before4["enterby"] . "')";
                        // mysql_query($sql_before5) or die(mysql_error() . $sql_before5);

                        $builder = $this->db->table('not_before');

                        // Prepare the conditions
                        $where = [
                            'diary_no' => $row_before4['diary_no'],
                            'j1' => $row_before4['j1'],
                            'j2' => $row_before4['j2'],
                            'j3' => $row_before4['j3'],
                            'j4' => $row_before4['j4'],
                            'j5' => $row_before4['j5'],
                            'notbef' => 'B'
                        ];

                        // Execute the DELETE query
                        $builder->delete($where);

                        // $sql_before6 = "DELETE FROM not_before WHERE diary_no='" . $row_before4["diary_no"] . "' AND j1=" . $row_before4["j1"] . " AND j2=" . $row_before4["j2"] . " AND j3=" . $row_before4["j3"] . " AND j4=" . $row_before4["j4"] . " AND j5=" . $row_before4["j5"] . " AND notbef='B'";
                        // mysql_query($sql_before6) or die(mysql_error() . $sql_before6);
                        //}
                    }
                }
            }
        }
    }

    public function get_next_working_date($dt, $head_no, $oc, $sh)
    {
        $cdate = $dt;
        $cdate1 = date('d-m-Y', strtotime($cdate));
        $wd = date("w", strtotime($cdate)) + 1;
        $wn = date("W", strtotime($cdate));
        $daydiff = $wd - 2;
        $checktillfriday = "N";
        $cdate2 = $cdate1;
        if ($head_no != 24) {
            if ($oc == "Y") {

                // Prepare the date for calculation
                $cdate = date('Y-m-d', strtotime($cdate));

                // Define the SQL query
                $sql = "
                    SELECT TO_CHAR(
                        (DATE '{$cdate}' + INTERVAL '1 day' * 
                            (CASE 
                                WHEN EXTRACT(DOW FROM DATE '{$cdate}') <= 3 
                                THEN 3 - EXTRACT(DOW FROM DATE '{$cdate}') 
                                ELSE 10 - EXTRACT(DOW FROM DATE '{$cdate}') 
                            END)),
                        'DD-MM-YYYY'
                    ) AS next_wednesday
                ";

                // Execute the query and get the result
                $results_wed = $this->db->query($sql);

                // $sql_wed = "SELECT
                //     DATE_FORMAT(DATE_ADD(
                //         '" . date('Y-m-d', strtotime($cdate)) . "',
                //         INTERVAL (IF(4-DAYOFWEEK('" . date('Y-m-d', strtotime($cdate)) . "')<0,(11-DAYOFWEEK('" . date('Y-m-d', strtotime($cdate)) . "')),4-DAYOFWEEK('" . date('Y-m-d', strtotime($cdate)) . "'))) DAY
                //     ),'%d-%m-%Y') AS NEXTWEDNESDAY";
                // $results_wed = mysql_query($sql_wed) or die(mysql_error());

                $row_wed = $results_wed->getRowArray();
                $cdate1 = date('d-m-Y', strtotime($row_wed[0]));
                $cdate = $row_wed[0];
            }
            for ($i = 1; $i <= 1; $i++) {
                $dateget = getdate(strtotime($cdate1));
                $t_dtt1 = $dateget['year'] . "-" . $dateget['mon'] . "-" . $dateget['mday'];

                // Start building the query using Query Builder
                $builder = $this->db->table('holidays');

                // Basic condition: match hdate
                $builder->selectCount('*')
                        ->where('hdate', $t_dtt1);

                // Check if $sh is 804, 805, or 806 to apply the weekday condition
                if (in_array($sh, ['804', '805', '806'])) {
                    $builder->where(function($builder) use ($t_dtt1) {
                        // This is where we check the weekday condition for the vacation logic
                        $builder->where("CASE WHEN hname LIKE '%vacation%' THEN EXTRACT(DOW FROM '{$t_dtt1}') NOT IN (0, 2) ELSE TRUE END", NULL, FALSE);
                    });
                }

                // Execute the query
                $query = $builder->get();

                // Fetch the result
                $row_count = $query->getRow()->count;


                // $sql = "SELECT count(*) FROM holidays WHERE hdate='" . $t_dtt1 . "'";
                // if ($sh == '804' or $sh == '805' or $sh == '806')
                //     $sql .= " AND (IF(hname LIKE '%vacation%', (WEEKDAY('" . $t_dtt1 . "')!=0 AND WEEKDAY('" . $t_dtt1 . "')!=2), '1=1'))";

                // $results = mysql_query($sql) or die(mysql_error());
                // $row = $results->getRowArray();

                if ($dateget['weekday'] == "Saturday" or $dateget['weekday'] == "Sunday" or $row_count > 0) {
                    if ($oc == "Y")
                        $cdate1 = date('d-m-Y', strtotime($cdate) + (24 * 3600 * 7));
                    elseif ($head_no == 8) {
                        if (date("W", strtotime($cdate1)) == $wn and (date("w", strtotime($cdate1)) + 1 == 6 or $checktillfriday == "Y") and $daydiff >= 0) {
                            if ($checktillfriday == "N") {
                                $cdate1 = $cdate2;
                                $checktillfriday = "Y";
                            }
                            if ($daydiff == 0) {
                                $cdate1 = date('d-m-Y', strtotime($cdate2) + (24 * 3600 * (7 - $wd)));
                            } else {
                                $cdate1 = date('d-m-Y', strtotime($cdate2) + (24 * 3600 * (($wd - $daydiff - 1) * (-1))));
                            }
                            $daydiff--;
                        } else {
                            $cdate1 = date('d-m-Y', strtotime($cdate) + (24 * 3600 * 1));
                        }
                    } else {
                        $cdate1 = date('d-m-Y', strtotime($cdate) + (24 * 3600 * 1));
                    }
                    $i--;
                }
                $cdate = $cdate1;
            }
        }
        return $cdate1;
    }

    public function get_next_working_date_new($dt, $head_no, $mf)
    {
        if ($head_no != 24) {
            $start = strtotime($dt);
            $t_var = '';
            $cdate1 = '';
            $ivar = 0;

            while ($cdate1 == '') {
                $t_loop = $ivar + 15;
                for ($ivar; $ivar < $t_loop; $ivar++) {
                    // Construct the dynamic dates string (using UNION to combine all date selections)
                    $newDate = date('Y-m-d', strtotime("+{$ivar} day", $start));
                    if ($t_var == '') {
                        $t_var .= "SELECT '{$newDate}' AS cdates ";
                    } else {
                        $t_var .= " UNION SELECT '{$newDate}' AS cdates ";
                    }
                }

                $subQuery = $this->db->table('(' . $t_var . ') t1')
                    ->select([
                        't1.cdates',
                        'EXTRACT(DOW FROM t1.cdates::date) AS wd',
                        "EXTRACT(WEEK FROM t1.cdates::date) - EXTRACT(WEEK FROM CAST('" . $dt . "' AS DATE)) + 1 AS wk",
                    ])
                    ->join('master.holidays t2', 't2.hdate = (t1.cdates)::date', 'left')
                    ->where('EXTRACT(DOW FROM t1.cdates::date) NOT IN (5, 6)')
                    ->where('t2.hdate IS NULL');

                // Main query
                $query = $this->db->table('(' . $subQuery->getCompiledSelect() . ') z1')
                    ->select([
                        '*',
                        'CASE WHEN (wd = 0) THEN cdates END AS c1',
                        'CASE WHEN (wk = 1) THEN MAX(cdates) ELSE MIN(cdates) END AS c2',
                        'MIN(CASE WHEN (wd = 1 OR wd = 2 OR wd = 3) THEN cdates END) AS r1',
                    ])
                    ->groupBy('z1.wk')
                    ->groupBy('z1.cdates, z1.wd');

                    // echo $query->getCompiledSelect();die;

                // Execute the query and process the results
                $results = $query->get()->getResultArray();

                foreach ($results as $row) {
                    if ($mf == 'F') {
                        if (!is_null($row['r1']) && $cdate1 == '') {
                            $cdate1 = $row['r1'];
                        }
                    } else {
                        if (!is_null($row['c1']) && $cdate1 == '') {
                            $cdate1 = $row['c1'];
                        }
                        if (!is_null($row['c2']) && $cdate1 == '') {
                            $cdate1 = $row['c2'];
                        }
                    }
                }
            }
        } else {
            $cdate1 = $dt;
        }

        // Return the next working date
        return date('Y-m-d', strtotime($cdate1));
    }

    /*Function added by preeti on 25.3.2019 to check if matter is on Misc. side,
    pertaining to short category and listed in first four court then Misc. date will be given */
    //removed on 8.4.2024 to remove short category concept by preeti
    public function shortCategoryinFirstFourCourt_removed($fno)
    {
        // Fetch data from 'heardt' table
        $builder = $this->db->table('heardt');
        $builder->select('mainhead, listorder, board_type, next_dt, tentative_cl_dt, is_nmd, coram');
        $builder->where('diary_no', $fno);
        $query = $builder->get();
        $row_heardt = $query->getRowArray();

        // Fetch the senior judges' codes from 'judge' table
        $builder = $this->db->table('judge');
        $builder->select('string_agg(jcode, \',\') as jcode');
        $builder->where('is_retired', 'N');
        $builder->where('display', 'Y');
        $builder->where('jtype', 'J');
        $builder->orderBy('appointment_date, judge_seniority');
        $builder->limit(4);
        $query = $builder->get();
        $row_judge = $query->getRowArray();

        // Fetch submaster_id from 'mul_category' table
        $builder = $this->db->table('mul_category');
        $builder->select('submaster_id');
        $builder->where('diary_no', $fno);
        $builder->where('display', 'Y');
        $query = $builder->get();
        $row_mc = $query->getRowArray();

        // Extract the submaster_id, short category array and judges' list
        $submaster_id = $row_mc['submaster_id'];
        $short_category_array = [
            343, 15, 16, 17, 18, 19, 20, 21, 22, 23, 341, 353, 157, 158, 159, 160, 161, 162, 163, 166, 173, 175, 176, 322, 222
        ];

        // If there are no judges or heardt data, return false early
        if (empty($row_judge['jcode']) || empty($row_heardt)) {
            return false;
        }

        // Split the judges' codes and get the coram value
        $four_senior_judges = explode(',', $row_judge['jcode']);
        $coram = strtok($row_heardt['coram'], ',');

        // Check the conditions
        if ($row_heardt['mainhead'] == 'M' && $row_heardt['board_type'] == 'J' && 
            in_array($submaster_id, $short_category_array) && in_array($coram, $four_senior_judges)) {
            return true;
        } else {
            return false;
        }
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

    public function get_main_details($dn, $fields)
    {
        $data_array = array();
        if ($dn != "") {
            if ($fields == "")
                $fields = "*";

            $query = $this->db->table('main')
            ->select($fields)
            ->where('diary_no', $dn)
            ->get();

            $data_array = [];

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

    function get_mul_category($diary_no, $flag = null)
    {
        $id = 0;
        $mul_category="";
        if($diary_no != "")
        {
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

                    $id=$row2['id'];
                }
            }
        }

        return array($mul_category, $id);
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
                    $check = (strpos($listed_ia_conn, $t_iaval_conn) !== false) ? "checked='checked'" : "";

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

    //function end

    function update_cis($fno_o, $dt_o, $head_o, $head_cont_o, $hdt, $ucode1, $hl, $snop/*,$nextCourt*/)
    {

        $t_hr = 0;
        $t_lo = 0;
        $t_head_code = '';
        $disp_code_all = '';
        $is_nmd = 'N';
        $nmd_head_content = "";
        $t_chk = 0; //check for 37,78,73 Disposed in Default
        //$str_cis = "SELECT * FROM heardt where diary_no='".$fno_o."' AND next_dt='".$dt_o."'";
        //     $str_cis = "SELECT * FROM
        //(
        //(SELECT diary_no, next_dt, judges, subhead,mainhead,listorder FROM heardt WHERE diary_no='".$fno_o."' AND next_dt='".$dt_o."' AND main_supp_flag IN (1,2))
        //UNION
        //(SELECT diary_no, next_dt, judges, subhead,mainhead,listorder FROM last_heardt WHERE diary_no='".$fno_o."' AND next_dt='".$dt_o."' AND  main_supp_flag IN (1,2))
        //    ) a";

        // Initialize the builder for query execution
        $builder = $this->db->table($hl == 'L' ? 'last_heardt' : 'heardt');

        // Build the query based on the value of $hl
        $builder->select('diary_no, next_dt, judges, subhead, mainhead, listorder, mainhead_n, is_nmd');
        $builder->where('diary_no', $fno_o);
        $builder->where('next_dt', $dt_o);
        $builder->where('clno !=', 0);
        $builder->where('brd_slno !=', 0);
        $builder->where('judges !=', '');
        $builder->whereIn('main_supp_flag', [1, 2]);

        // If not 'L', no need for the extra condition on 'bench_flag'
        if ($hl != 'L') {
            $builder->where('bench_flag', '');
        }

        // Execute the query
        $results_cis = $builder->get();
        
        // if ($hl == "L")
        //     $str_cis = "SELECT diary_no, next_dt, judges, subhead,mainhead,listorder,mainhead_n,is_nmd FROM last_heardt WHERE diary_no='" . $fno_o . "' AND next_dt='" . $dt_o . "' and clno!=0 and brd_slno!=0 and judges!='' and main_supp_flag in (1,2) and bench_flag=''";
        // else
        //     $str_cis = "SELECT diary_no, next_dt, judges, subhead,mainhead,listorder,mainhead_n,is_nmd FROM heardt WHERE diary_no='" . $fno_o . "' AND next_dt='" . $dt_o . "' and clno!=0 and brd_slno!=0 and judges!='' and main_supp_flag in (1,2)";
        // $results_cis = mysql_query($str_cis) or die(mysql_error());

        if ($this->db->affectedRows() > 0) {
            $up_str = "";
            $side = "";
            $disp_code = "";
            $subhead = 0;
            $nature = "";
            $disp_type_all = "";
            $row_cis = $results_cis->getRowArray();
            $t_j1 = explode(',', $row_cis["judges"]);
            $j1 = $t_j1[0];
            $judges = $row_cis["judges"];
            $subhead = $row_cis["subhead"];
            $is_nmd = 'Y';

            //nature
            //$sql_nature="SELECT nature from casetype where casecode=SUBSTR('".$fno_o."',3,3) and display='Y'";
            //$result_nature=mysql_query($sql_nature) or die(mysql_error());
            //$row_nature=  mysql_fetch_array($result_nature);
            //$nature=$row_nature["nature"];
            //nature
            //    $t_pdate="";
            $t_fix_month = 0;
            for ($i = 0; $i < count($head_o); $i++) {
                if ($head_o[$i] == "7")
                    $t_hr = 1;
                if ($head_o[$i] == "68")
                    $t_fix_month = 1;
                /*if condition changed by Preeti on 19.8.19 so that CM selects List after days/months/week/next week/fixed month/week commencing along with any other remarks then tentative date should be according to these remarks */

                if ($head_o[$i] == "53" or $head_o[$i] == "54" or $head_o[$i] == "23" or $head_o[$i] == "68" or $head_o[$i] == "8" or $head_o[$i] == "21")
                    $t_lo = 2;
                //    if($head_o[$i]=="24") $t_lo=1;

                if ($head_o[$i] == "37" or $head_o[$i] == "73" or $head_o[$i] == "78") {
                    $t_chk = 1;
                }
                if ($head_o[$i] == "180") {
                    $is_nmd = 'Y';
                    $nmd_head_content = $head_cont_o[$i];
                }

                if ($head_o[$i] == "207") {
                    $t_lo = 3;
                }
                if ($head_o[$i] == "24")   // to check1
                    //  if($head_o[$i]=="24" or (($head_o[$i]=="53" or $head_o[$i]=="54" or $head_o[$i]=="23" or $head_o[$i]=="68" or $head_o[$i]=="8" or $head_o[$i]=="21") and $head_o[$i]!="180" )) {
                    $t_lo = 1;
                //$str_cr = "SELECT sno,head,if(sno IN (50,87,48,49,89,88),'T',side) as side,sord,category_id,cis_disp_code,pending_text FROM case_remarks_head WHERE sno=".$head_o[$i]."";
                
                // Prepare the builder
                $builder = $this->db->table('master.case_remarks_head');

                // Use the query builder to select the desired columns
                $builder->select('sno, head, side, cis_disp_code');
                $builder->where('sno', $head_o[$i]);

                // Execute the query
                $results_cr = $builder->get();
                
                // $str_cr = "SELECT sno,head,side,cis_disp_code FROM case_remarks_head WHERE sno=" . $head_o[$i] . "";
                // $results_cr = mysql_query($str_cr) or die(mysql_error() . $str_cr);

                if ($this->db->affectedRows() > 0) {
                    if ($i > 0)
                        $up_str .= ", ";
                    $row_cr = $results_cr->getRowArray();

                    //        if(trim($row_cr["pending_text"])!="")
                    //        $up_str.=$row_cr["pending_text"];
                    //        else
                    $up_str .= $row_cr["head"];

                    if ($head_cont_o[$i] != "")
                        $up_str .= " (" . $head_cont_o[$i] . ")";

                    $side = $row_cr["side"];
                    $disp_code = $row_cr["cis_disp_code"];
                    if ($disp_type_all == '')
                        $disp_type_all .= $row_cr["cis_disp_code"];
                    else
                        $disp_type_all .= "," . $row_cr["cis_disp_code"];

                    //                    if($head_o[$i]=="24" or $head_o[$i]=="21" or $head_o[$i]=="59" or $head_o[$i]=="70")
                    //                    {
                    //                    $date1 = DateTime::createFromFormat('d/m/Y', $head_cont_o[$i]);
                    //                    $t_pdate= $date1->format('Y-m-d');
                    //                    }
                }
            }
            if ($side == "")
                $side = "P";
            $tdt = explode("-", $dt_o);
            $up_str .= "-Ord dt:" . $tdt[2] . "-" . $tdt[1] . "-" . $tdt[0];
            //TENTATIVE DATE START
            //$sql_check_ia="select count(*) from docdetails where diary_no='".$fno_o."' and doccode=8 and FIND_IN_SET(doccode1, '40,48,49,50,63') > 0 and iastat='P' and display='Y'";
            /*$sql_check_ia="SELECT
                COUNT(CASE WHEN FIND_IN_SET(doccode1, '40,48,49,50,63') > 0 THEN 1 END) AS c1,
                COUNT(CASE WHEN doccode1=2 THEN 1 END) AS c2
                FROM
                docdetails
                WHERE  diary_no='".$fno_o."' AND doccode = 8
                AND (FIND_IN_SET(doccode1, '40,48,49,50,63') > 0 OR doccode1=2)
                AND iastat = 'P'
                AND display = 'Y'"; */

            // Prepare the builder for the query
            $builder = $this->db->table('docdetails');
            
            // Perform the count query with conditions for doccode1 and iastat
            $builder->select("
                COUNT(CASE WHEN array_position(string_to_array('40,48,49,50,63', ','), doccode1::text) > 0 THEN 1 END) AS c1,
                COUNT(CASE WHEN array_position(string_to_array('2,27,28,56,57', ','), doccode1::text) > 0 THEN 1 END) AS c2
            ");
            $builder->where('diary_no', $fno_o);
            $builder->where('doccode', 8);
            $builder->whereIn('doccode1', ['40', '48', '49', '50', '63']);
            $builder->whereIn('doccode1', ['2', '27', '28', '56', '57']);
            $builder->where('iastat', 'P');
            $builder->where('display', 'Y');
            
            // echo $builder->getCompiledSelect();die;


            // Execute the query
            $results_ia = $builder->get();
            
            // $sql_check_ia = "SELECT
            //         COUNT(CASE WHEN FIND_IN_SET(doccode1, '40,48,49,50,63') > 0 THEN 1 END) AS c1,
            //         COUNT(CASE WHEN FIND_IN_SET(doccode1, '2,27,28,56,57') > 0 THEN 1 END) AS c2
            //         FROM
            //         docdetails
            //         WHERE  diary_no='" . $fno_o . "' AND doccode = 8
            //         AND (FIND_IN_SET(doccode1, '40,48,49,50,63') > 0 OR FIND_IN_SET(doccode1, '2,27,28,56,57') > 0)
            //         AND iastat = 'P'
            //         AND display = 'Y'";
            // $results_ia = mysql_query($sql_check_ia) or die(mysql_error());

            $row_ia = $results_ia->getRowArray();
            if ($row_ia['c1'] > 0)
                $check_ia = " 1=1 ";
            else
                $check_ia = " 1=2 ";
            if ($row_ia['c2'] > 0)
                $check_ia1 = " 1=1 ";
            else
                $check_ia1 = " 1=2 ";
            ///NEW CODE FOR NOT REACHED CASES
            if ($row_cis["mainhead"] == "M")
                $check_mf = " 1=1 ";
            else
                $check_mf = " 1=2 ";
            //$check_for_nr1=" b1.sno = 19 AND ".$check_ia1." AND ".$check_mf;
            //$check_for_nr2=" r1.rhead = 19 AND ".$check_ia1." AND ".$check_mf;
            $check_for_nr1 = " b1.sno in (16,19) AND " . $check_ia1 . " AND " . $check_mf;
            $check_for_nr2 = " r1.rhead::text in ('16','19') AND " . $check_ia1 . " AND " . $check_mf;

            if ($row_cis["listorder"] == 5)
                $check_mm = " 1=1 ";
            else
                $check_mm = " 1=2 ";
            ///NEW SQL
            $tdt_str = "";
            $sql_td = "SELECT
                r1.*,
                (
                    (CASE 
                        WHEN (r1.pcnt > 1)
                        THEN 
                        (SELECT CONCAT (
                                (CASE
                                    WHEN (b1.compliance_limit_in_day > 0)
                                    THEN
                                    (
                                        (CASE
                                            WHEN 
                                                (((b1.sno = 16 OR b1.sno = 19 OR b1.sno = 125 OR b1.sno = 132 OR b1.sno = 145 OR b1.sno = 159) AND (array_position(string_to_array('804,805,806,822,823', ','), '$subhead') IS NULL AND NOT " . $check_ia . ")) OR ((b1.sno = 5 or b1.sno=146) AND array_position(string_to_array('811,812', ','), '$subhead') > 0))
                                            THEN
                                            (
                                                CASE 
                                                    WHEN (
                                                            (array_position(string_to_array('813,814', ','), '$subhead') IS NULL AND NOT(" . $check_for_nr1 . ")) 
                                                            OR b1.sno = 125 OR b1.sno = 132 OR b1.sno = 145 OR b1.sno = 159 
                                                            OR ((b1.sno = 5 OR b1.sno = 146) AND array_position(string_to_array('811,812', ','), '$subhead') > 0)
                                                        ) AND NOT(" . $check_for_nr1 . ") 
                                                    THEN 
                                                        CASE 
                                                            WHEN (b1.sno = 16 OR b1.sno = 19 OR b1.sno = 125 OR b1.sno = 132 OR b1.sno = 145 OR b1.sno = 159)
                                                                THEN '" . $dt_o . "'::DATE + INTERVAL '1 days' * (CASE WHEN '" . $row_cis["mainhead"] . "' = 'F' THEN 3 ELSE 6 END)
                                                            ELSE 
                                                                '" . $dt_o . "'::DATE + INTERVAL '1 days' * cast(9 - EXTRACT(DOW FROM '" . $dt_o . "'::DATE) as integer)
                                                        END
                                                    ELSE 
                                                        CASE 
                                                            WHEN ((array_position(string_to_array('813,814', ','), '$subhead') > 0 AND " . $check_mm . ") OR " . $check_for_nr1 . ")
                                                            THEN 
                                                                CASE 
                                                                    WHEN (" . $check_for_nr1 . ") 
                                                                        THEN '" . $dt_o . "'::timestamp + INTERVAL '3 days'
                                                                    ELSE 
                                                                        '" . $dt_o . "'::timestamp + INTERVAL '2 days'
                                                                END
                                                            ELSE 
                                                                '" . $dt_o . "'::timestamp + INTERVAL '28 days'
                                                        END
                                                END

                                            )
                                            ELSE
                                            (
                                                CASE 
                                                    WHEN ((b1.sno = 5 OR b1.sno = 146) AND (array_position(string_to_array('804,805,806,822,823', ','), '$subhead') > 0 OR " . $check_ia . ")) 
                                                        THEN '" . $dt_o . "'::timestamp + INTERVAL '2 days'
                                                    WHEN (b1.sno = 130 AND (array_position(string_to_array('804,805,806,822,823', ','), '$subhead') > 0 OR " . $check_ia . ")) 
                                                        THEN '" . $dt_o . "'::timestamp + INTERVAL '7 days'
                                                    ELSE '" . $dt_o . "'::DATE + INTERVAL '1 days' * (b1.compliance_limit_in_day + 1)
                                                END
                                            )
                                        END)
                                    )
                                    ELSE
                                    (
                                        (CASE
                                            WHEN ( array_position(string_to_array('15,84,113', ',')::text[], b1.sno::text) > 0)
                                            THEN
                                                (CASE
                                                    WHEN b1.sno = 15
                                                        THEN '2099-12-12'::date
                                                    WHEN b1.sno = 84
                                                        THEN '2088-12-12'::date
                                                END)
                                            ELSE
                                            ( '2088-12-12'::date )
                                        END)
                                    )
                                END), '||', b1.sno ) AS ttdt
                            FROM
                                master.case_remarks_head b1
                            WHERE array_position(string_to_array(r1.rhead, ',')::integer[], b1.sno::integer) > 0
                            ORDER BY ttdt
                            LIMIT 1
                        )
                        ELSE
                        (
                            CONCAT(
                                (CASE
                                    WHEN (r1.compliance_limit_in_day > 0)
                                    THEN (
                                        (CASE
                                            WHEN (
                                                (
                                                    (r1.rhead::text = '16' OR r1.rhead::text = '19' OR r1.rhead::text = '125' OR r1.rhead::text = '132' OR r1.rhead::text = '145' OR r1.rhead::text = '159') 
                                                    AND (array_position(string_to_array('804,805,806,822,823', ',')::text[], '$subhead') IS NULL AND NOT " . $check_ia . ")
                                                ) 
                                                OR ((r1.rhead::text = '5' OR r1.rhead::text = '146') AND array_position(string_to_array('811,812', ',')::integer[], '$subhead'::integer) > 0)
                                            )
                                            THEN
                                            (
                                                (CASE
                                                    WHEN ((array_position(string_to_array('813,814', ',')::text[], '$subhead') IS NULL AND NOT(" . $check_for_nr2 . "))  OR r1.rhead::text = '125' OR r1.rhead::text = '132' OR r1.rhead::text = '145' OR r1.rhead::text = '159' OR ((r1.rhead::text = '5' OR r1.rhead::text = '146') AND array_position(string_to_array('811,812', ','), '$subhead') > 0)) AND NOT(" . $check_for_nr2 . ")
                                                    THEN
                                                        (CASE
                                                            WHEN (r1.rhead::text = '16' OR r1.rhead::text = '19' OR r1.rhead::text = '125' OR r1.rhead::text = '132' OR r1.rhead::text = '145' OR r1.rhead::text = '159')
                                                            THEN 
                                                                DATE('" . $dt_o . "'::DATE + INTERVAL '1 days' * (CASE WHEN '" . $row_cis["mainhead"] . "' = 'F' THEN 3 ELSE 6 END))
                                                            ELSE
                                                                DATE('" . $dt_o . "'::DATE + INTERVAL '1 days' * (9 - EXTRACT(DOW FROM '" . $dt_o . "'::DATE)))
                                                        END)
                                                    ELSE
                                                        (CASE
                                                            WHEN (( array_position(string_to_array('813,814', ',')::text[], '$subhead') > 0 and " . $check_mm . ") OR (" . $check_for_nr2 . "))
                                                            THEN
                                                                (CASE
                                                                    WHEN (" . $check_for_nr2 . ")
                                                                    THEN DATE('" . $dt_o . "'::DATE + INTERVAL '3 days')
                                                                    ELSE DATE('" . $dt_o . "'::DATE + INTERVAL '2 days')
                                                                END)
                                                            ELSE DATE('" . $dt_o . "'::DATE + INTERVAL '28 days')
                                                        END)
                                                END)
                                            )
                                            ELSE
                                            (
                                                (CASE
                                                    WHEN ((r1.rhead::text = '5' OR r1.rhead::text = '146') AND (array_position(string_to_array('804,805,806,822,823', ','), '$subhead') > 0 OR " . $check_ia . "))
                                                    THEN 
                                                        DATE('" . $dt_o . "'::DATE + INTERVAL '2 days')
                                                    ELSE 
                                                        (CASE
                                                            WHEN (r1.rhead::text = '130' AND (array_position(string_to_array('804,805,806,822,823', ','), '$subhead') > 0 OR " . $check_ia . "))
                                                            THEN 
                                                                DATE('" . $dt_o . "'::DATE + INTERVAL '7 days')
                                                            ELSE 
                                                                DATE('" . $dt_o . "'::DATE + INTERVAL '1 days' * (r1.compliance_limit_in_day + 1))
                                                        END)
                                                END)
                                            )
                                        END)
                                    )
                                    ELSE
                                    (CASE 
                                        WHEN (r1.rhead::text = '12')
                                            THEN (
                                                    SELECT MAX(hdate)
                                                    FROM master.holidays
                                                    WHERE hdate > '" . $dt_o . "' AND EXTRACT(YEAR FROM hdate) = EXTRACT(YEAR FROM '" . $dt_o . "'::date)
                                                    AND hname LIKE '%Vacation%'
                                                    GROUP BY hname
                                                    LIMIT 1
                                                )
                                        WHEN (
                                            r1.rhead::text = '21'
                                            OR r1.rhead::text = '24'
                                            OR r1.rhead::text = '59'
                                            OR r1.rhead::text = '131'
                                            OR r1.rhead::text = '70'
                                        )
                                            THEN TO_DATE(TO_CHAR(TO_DATE(r1.head_content, 'DD-MM-YYYY'), 'YYYY-MM-DD'), 'YYYY-MM-DD')
                                        WHEN (r1.rhead::text = '8' OR r1.rhead::text = '124')
                                            THEN DATE('" . $dt_o . "'::DATE + INTERVAL '7 days')
                                        WHEN (r1.rhead::text = '190' or r1.rhead::text = '181' or r1.rhead::text = '204' or r1.rhead::text = '205')
                                            THEN (CASE
                                                    WHEN (r1.head_content='' and (r1.rhead::text = '181' or r1.rhead::text = '204' or r1.rhead::text = '205'))
                                                        THEN DATE('" . $dt_o . "'::DATE + INTERVAL '45 days')
                                                    WHEN CAST(split_part(REPLACE(r1.head_content, 'D:', ''), ',', 1) AS INTEGER) > 0
                                                        THEN DATE('" . $dt_o . "'::DATE + INTERVAL '1 days' * (CAST(split_part(REPLACE(r1.head_content, 'D:', ''), ',', 1) AS INTEGER) + 1))
                                                    WHEN CAST(split_part(split_part(REPLACE(r1.head_content, 'W:', ''), ',', 2), ',', -1) AS INTEGER) > 0
                                                        THEN DATE('" . $dt_o . "'::DATE + INTERVAL '1 weeks' * (CAST(split_part(split_part(REPLACE(r1.head_content, 'W:', ''), ',', 2), ',', -1) AS INTEGER)) + INTERVAL '1 day')
                                                    WHEN CAST(split_part(split_part(REPLACE(r1.head_content, 'M:', ''), ',', 3), ',', -1) AS INTEGER) > 0
                                                        THEN DATE('" . $dt_o . "'::DATE + INTERVAL '1 months' * (CAST(split_part(split_part(REPLACE(r1.head_content, 'M:', ''), ',', 3), ',', -1) AS INTEGER)) + INTERVAL '1 day')
                                                    ELSE
                                                        DATE('" . $dt_o . "'::DATE + INTERVAL '8 days')
                                                END
                                            )
                                        WHEN (r1.rhead::text = '149' OR r1.rhead::text = '53' OR r1.rhead::text = '133')
                                            THEN DATE('" . $dt_o . "'::DATE + INTERVAL '1 days' * (r1.head_content::integer + 1))
                                        WHEN r1.rhead::text = '54' 
                                            THEN DATE('" . $dt_o . "'::DATE + INTERVAL '1 months' * (r1.head_content::integer))
                                        WHEN r1.rhead::text = '23' 
                                            THEN DATE('" . $dt_o . "'::DATE + INTERVAL '1 days' * ((r1.head_content::integer * 7) + 1))
                                        WHEN r1.rhead::text = '25' 
                                            THEN DATE('" . $dt_o . "'::DATE + INTERVAL '1 days' * (r1.head_content::integer + 1))
                                        WHEN r1.rhead::text = '123' 
                                            THEN DATE('" . $dt_o . "'::DATE + INTERVAL '1 months' * (r1.head_content::integer))
                                        WHEN r1.rhead::text = '122' 
                                            THEN DATE('" . $dt_o . "'::DATE + INTERVAL '1 days' * ((r1.head_content::integer * 7)))
                                        WHEN (r1.rhead::text = '68')
                                            THEN 
                                                CASE 
                                                WHEN EXTRACT(MONTH FROM '" . $dt_o . "'::DATE) > r1.head_content::integer 
                                                    THEN TO_DATE(CONCAT(EXTRACT(YEAR FROM '" . $dt_o . "'::DATE) + 1, '-', r1.head_content, '-01'), 'YYYY-MM-DD')
                                                ELSE TO_DATE(CONCAT(EXTRACT(YEAR FROM '" . $dt_o . "'::DATE), '-', r1.head_content, '-01'), 'YYYY-MM-DD')
                                            END
                                        END
                                    )
                                END)
                                , '||', r1.rhead::text
                            )
                        )
                    END)
                ) AS tdate
                FROM
                    (SELECT
                        STRING_AGG(a1.r_head::text, ',') AS rhead,
                        a1.head_content,
                        a.compliance_limit_in_day,
                        a.priority,
                        (CASE WHEN a.priority = 999 THEN 2 ELSE 1 END) AS pcnt
                    FROM
                        master.case_remarks_head a,
                        public.case_remarks_multiple a1
                    WHERE a1.diary_no = '" . $fno_o . "'
                        AND a1.cl_date = '" . $dt_o . "'
                        AND a.sno = a1.r_head
                    GROUP BY a.priority, a1.head_content, a.compliance_limit_in_day
                    LIMIT 1) r1 ";
            
            // echo $sql_td; die;

            $results_dt = $this->db->query($sql_td);

            if ($this->db->affectedRows() > 0) {
                $row_dt = $results_dt->getRowArray();

                $t_tdt = explode("||", $row_dt["tdate"]);
                //if(count($t_tdt)==2)
                $t_head_code = "";
                if (count($t_tdt) > 1) {
                    $pdate = date('d-m-Y', strtotime($t_tdt[0]));
                    //if( $t_tdt[1]!=24 )
                    if ($t_tdt[1] == 15)
                        $pdate = "12-12-2099";
                    if ($t_tdt[1] == 84)
                        $pdate = "12-12-2088";
                    //code added by preeti on 24072019 to give next unpublish advance list date if List on NMD is selected
                    if ($t_tdt[1] == 180) {
                        //query to get unpublished advanced list date
                        
                        // Create a subquery to get the max(next_dt) from the advance_cl_printed table
                        $subQuery = $this->db->table('advance_cl_printed')
                        ->select('MAX(next_dt) as max_next_dt')
                        ->where('next_dt >', date('Y-m-d'))  // Equivalent of CURDATE()
                        ->getCompiledSelect(); // Get the compiled subquery

                        // Now, use the query builder to select from sc_working_days
                        $builder = $this->db->table('master.sc_working_days');
                        $builder->select('working_date')
                        ->where("working_date >", "($subQuery)", false)  // Passing the subquery as a condition
                        ->where('is_holiday', 0)
                        ->orderBy('working_date', 'ASC')
                        ->limit(1);

                        // Execute the query
                        $results_unpublish_cl = $builder->get();

                        // $sql_unpublish_cl = "select working_date as working_date from sc_working_days where working_date>(select max(next_dt) from advance_cl_printed where next_dt>curdate()) and is_holiday = 0 order by working_date asc limit 0,1";
                        // $results_unpublish_cl = mysql_query($sql_unpublish_cl) or die(mysql_error());

                        $row_unpublish_cl = $results_unpublish_cl->getRowArray();
                        $pdate = $row_unpublish_cl['working_date'];
                    }
                    //code added on 24072019 ends
                    //            if( $t_tdt[1]==113 )
                    //                $pdate = "12-12-2077";
                    //OUTSIDE COUNCIL
                    // $sql_oc1="SELECT
                    //  *
                    //FROM
                    //  (
                    //    (SELECT
                    //advocate_id
                    //    FROM
                    //      `advocate`
                    //    WHERE diary_no = '".$fno_o."'
                    //      AND advocate_id != '0'
                    //      AND display = 'Y')
                    //    UNION
                    //    (SELECT
                    //      SUBSTRING_INDEX(
                    //        IF(
                    //          records.n = 1,
                    //          main.petadven,
                    //          main.`resadven`
                    //        ),
                    //        '/',
                    //        1
                    //      ) advno,
                    //      SUBSTRING_INDEX(
                    //        IF(
                    //          records.n = 1,
                    //          main.petadven,
                    //          main.`resadven`
                    //        ),
                    //        '/',
                    //        - 1
                    //      ) advyr
                    //    FROM
                    //      (SELECT
                    //        1 n
                    //      UNION
                    //      ALL
                    //      SELECT
                    //        2) records
                    //      INNER JOIN main
                    //        ON diary_no = '".$fno_o."')
                    //  ) a
                    //  INNER JOIN `bar` b
                    //    ON (
                    //      a.advno = b.enroll_no
                    //      AND a.advyr = YEAR(b.enroll_date)
                    //      AND a.advno != ''
                    //      AND b.outside = 'Y'
                    //    )";
                    //$results_oc1=mysql_query($sql_oc1);
                    $oc1 = "N";
                    //if($this->db->affectedRows()>0)
                    //$oc1="Y";
                    //else
                    //$oc1="N";
                    //$pdate = get_next_working_date($pdate,$t_tdt[1],$oc1,$subhead);
                    //             $pdate.$t_tdt[1].$row_cis["mainhead"];
                    $pdate = date('Y-m-d', strtotime($pdate));
                    $pdate = $this->get_next_working_date_new($pdate, $t_tdt[1], $row_cis["mainhead"]);

                    //Added code on 26-03-2018 for giving NMD date if List on NMD ticked by court master or case belongs to short category

                    /*  if(isNmdorShortCategorymatter($fno_o)){    // commented on 8.4.2024 to remove short category concept by preeti
                        $is_nmd='Y';
                        if($nmd_head_content==""){
                            $nmd_head_content="ANY";
                        }
                    }*/
                    if ($row_cis["subhead"] == '817' && $row_cis["mainhead_n"] == 'F' && $row_cis["mainhead"] == 'M') {
                        //Added for urgent hearing regular matters listed in Misc head Dated: 20-07-2018
                        $is_nmd = 'Y';
                        $nmd_head_content = "ANY";
                    }
                    /*if(shortCategortyinFirstFourCourt($fno_o))   // commented on 8.4.2024 to remove short category concept by preeti
                    {
                        $pdate=get_next_working_date_new($pdate,$t_tdt[1],'M');
                    }*/ else {
                        if (($t_lo == 0 || $t_lo == 2) && $is_nmd == 'Y') {
                            /*if ($t_fix_month == 1) {
                                //on fix month
                                $pdate = getNextNmdDate($pdate, $nmd_head_content);
                            } else {*/
                            $pdate = $this->getNextNmdDateNew($pdate);
                            // }
                        }
                    }
                    if ($t_lo == 3) {
                        $pdate = $this->getNextNmdDateNew($dt_o);
                    }

                    //  $is_nmd,$nmd_head_content
                    //get_next_working_date_new($pdate,$t_tdt[1],$row_cis["mainhead"]);
                    //$what_to_find = '7';
                    //if (preg_match('/\b' . $what_to_find . '\b/', $t_tdt[1])) {
                    // echo $t_hr.$t_lo;
                    $t_date = '';
                    //code added by preeti on 15072019
                    $court_remarks = 0;
                    //query to get unpublished advanced list date

                    // Create a subquery to get the max(next_dt) from the advance_cl_printed table
                    $subQuery = $this->db->table('advance_cl_printed')
                    ->select('MAX(next_dt) as max_next_dt')
                    ->where('next_dt >', date('Y-m-d'))  // Equivalent of CURDATE()
                    ->getCompiledSelect();  // Compile the subquery

                    // Now, use the query builder to select from sc_working_days
                    $builder = $this->db->table('master.sc_working_days');
                    $builder->select('working_date')
                    ->where("working_date >", "($subQuery)", false)  // Passing the subquery as a condition
                    ->where('is_holiday', 0)
                    ->orderBy('working_date', 'ASC')
                    ->limit(1);

                    // Execute the query
                    $results_unpublish_cl = $builder->get();


                    // $sql_unpublish_cl = "select working_date as working_date from sc_working_days where working_date>(select max(next_dt) from advance_cl_printed where next_dt>curdate()) and is_holiday = 0 order by working_date asc limit 0,1";
                    // $results_unpublish_cl = mysql_query($sql_unpublish_cl) or die(mysql_error());

                    $row_unpublish_cl = $results_unpublish_cl->getRowArray();

                    //query to know whether matter has been listed in hon'ble court
                    // Prepare the raw SQL for the UNION query
                    $sql = "
                        SELECT next_dt 
                        FROM heardt 
                        WHERE diary_no = ? 
                        AND board_type IN ('J', 'S') 
                        AND clno != 0 
                        AND clno IS NOT NULL 
                        AND brd_slno IS NOT NULL 
                        AND brd_slno != 0 
                        AND roster_id != 0 
                        AND roster_id IS NOT NULL
                        UNION
                        SELECT next_dt 
                        FROM last_heardt 
                        WHERE diary_no = ? 
                        AND board_type IN ('J', 'S') 
                        AND clno != 0 
                        AND clno IS NOT NULL 
                        AND brd_slno IS NOT NULL 
                        AND brd_slno != 0 
                        AND roster_id != 0 
                        AND roster_id IS NOT NULL 
                        AND (bench_flag IS NULL OR TRIM(bench_flag) = '')
                    ";

                    // Execute the query with parameter binding to prevent SQL injection
                    $results_list_court = $this->db->query($sql, [$fno_o, $fno_o]);

        //             $sql_list_court = "select next_dt from heardt where diary_no=$fno_o and board_type in('J','S') and clno!=0 and clno is not null and brd_slno is not null and brd_slno!=0 and roster_id!=0 and roster_id is not null
        // union
        // select next_dt from last_heardt where diary_no=$fno_o and board_type in('J','S') and clno!=0 and clno is not null and brd_slno is not null and brd_slno!=0 and roster_id!=0 and roster_id is not null and (bench_flag is null or trim(bench_flag)='')";
        //             $results_list_court = mysql_query($sql_list_court) or die(mysql_error());


                    $row_list_court = $results_list_court->getRowArray();

                    for ($i = 0; $i < count($head_o); $i++) {
                        if ($head_o[$i] == "80") {
                            $court_remarks = 1;
                            if ($row_list_court == 0 or $row_list_court == null) {

                                $dt1 = date('Y-m-d', strtotime($dt_o . ' + 7 days'));
                            } else if ($row_list_court != 0 and $row_list_court != null) {

                                $dt1 = $row_unpublish_cl['working_date'];
                            }
                        }
                    }
                    if ($court_remarks == 1 and $t_lo == 0) {
                        $tdt_str = " tentative_cl_dt='" . $dt1 . "' ";
                        //  $board=",board_type='".$nextCourt."'";
                        $t_date = $dt_o;
                    }
                    //new code end
                    else if ($t_hr == 1 and $t_lo == 0) {
                        //$tdt_str=" , tentative_cl_dt='".$dt_o."', ent_tentative_dt=NOW(), head_code='".$t_tdt[1]."' ";
                        $tdt_str = " tentative_cl_dt='" . $dt_o . "' ";
                        // $board=",board_type='".$nextCourt."'";
                        $t_date = $dt_o;
                    } else {
                        // $tdt_str=" , tentative_cl_dt='".$pdate."', ent_tentative_dt=NOW(), head_code='".$t_tdt[1]."' ";
                        $tdt_str = " tentative_cl_dt='" . $pdate . "' ";
                        // $board=",board_type='".$nextCourt."'";
                        $t_date = $pdate;
                    }
                    if ($t_head_code == "")
                        $t_head_code .= $t_tdt[1];
                    else
                        $t_head_code .= "," . $t_tdt[1];
                }
            }

            //TENTATIVE DATE END
            if ($hl == 'H' or $hl == '' or $snop == 1) {
                if ($tdt_str != "" /*and $nextCourt!=""*/) {
                    if ($snop == 1 and $hl == 'L') 
                    {
                        $sql = "UPDATE heardt SET $tdt_str where diary_no = ? and next_dt < ? ";
                        $results_list_court = $this->db->query($sql, [$fno_o, $t_date]);
                    }
                    else
                    {
                        $sql = "UPDATE heardt SET $tdt_str where diary_no = ?";
                        $results_list_court = $this->db->query($sql, [$fno_o]);
                    }

                    // if ($snop == 1 and $hl == 'L')
                    //     $str_up_heardt = "UPDATE heardt SET " . $tdt_str . " where diary_no='" . $fno_o . "' and next_dt < '" . $t_date . "'";
                    // else
                    //     $str_up_heardt = "UPDATE heardt SET " . $tdt_str . " where diary_no='" . $fno_o . "'";
                    // mysql_query($str_up_heardt) or die(mysql_error() . $str_up_heardt);
                }
                /*
                else if($tdt_str!="" and $nextCourt==""){
                    if($snop==1 and $hl=='L')
                $str_up_heardt="UPDATE heardt SET ".$tdt_str." where diary_no='".$fno_o."' and next_dt < '".$t_date."'";
                    else
                $str_up_heardt="UPDATE heardt SET ".$tdt_str." where diary_no='".$fno_o."'";
                mysql_query($str_up_heardt) or die(mysql_error().$str_up_heardt);
                }
                */
            }
            if ($hl == 'L') {
                if ($tdt_str != "") {
                    
                    $sql = "UPDATE last_heardt SET $tdt_str where diary_no=? AND next_dt=? and clno!=0 and brd_slno!=0 and judges!='' and main_supp_flag in (1,2) and bench_flag=''";
                    $str_up_heardt = $this->db->query($sql, [$fno_o, $dt_o]);

                    // $str_up_heardt = "UPDATE last_heardt SET " . $tdt_str . " where diary_no='" . $fno_o . "' AND next_dt='" . $dt_o . "' and clno!=0 and brd_slno!=0 and judges!='' and main_supp_flag in (1,2) and bench_flag=''";
                    // mysql_query($str_up_heardt) or die(mysql_error() . $str_up_heardt);
                }
            }
            if (($hl == 'H' or $hl == '') or ($hl == 'L' and $snop == 1)) {

                // Prepare the data to update (assuming $up_str, $t_head_code, $side, $ucode1 are variables)
                $data = [
                    'last_dt' => date('Y-m-d H:i:s'), // You can use the current timestamp here
                    'lastorder' => $up_str, // $up_str is already passed safely
                    'head_code' => $t_head_code,
                    'c_status' => $side,
                    'last_usercode' => $ucode1
                ];

                // Define the WHERE condition
                $where = [
                    'diary_no' => $fno_o
                ];

                // Use the query builder to update the "main" table
                $builder = $this->db->table('main');
                $str_up_main = $builder->update($data, $where);

                // $str_up_main = "UPDATE main SET last_dt=NOW(),lastorder='" . addslashes($up_str) . "', head_code='" . $t_head_code . "' ,c_status='" . $side . "', last_usercode=" . $ucode1 . " where diary_no='" . $fno_o . "'";
                // mysql_query($str_up_main) or die(mysql_error() . $str_up_main);
            }


            //$str_up_heardt="UPDATE heardt SET listorder=IF(listorder=48,16,listorder)  where diary_no='".$fno_o."'";


            if ($side == "D") {

                // Get the query builder
                $builder = $this->db->table('main');

                // Execute the query using Query Builder
                $results_selm = $builder->select('bench')
                                        ->where('diary_no', $fno_o)
                                        ->get();

                // $str_sel_main = "SELECT bench FROM main where diary_no='" . $fno_o . "'";
                // $results_selm = mysql_query($str_sel_main);

                $bench = "";
                if ($this->db->affectedRows() > 0) {
                    $row_selm = $results_selm->getRowArray();
                    $bench = $row_selm["bench"];
                }
                                        
                // $str_sel_disp = "SELECT * FROM dispose where diary_no='" . $fno_o . "'";
                // $results_disp = mysql_query($str_sel_disp);

                //$disp_str=$up_str."-Ord dt:".$tdt[2]."/".$tdt[1]."/".$tdt[0];
                $disp_str = $up_str;
                ///////ADDED for month year correct entry
                $dday = $dmonth = $dyear = 0;
                $hdt1 = explode("-", $hdt);
                $dmonth = $hdt1[1];
                $dyear = $hdt1[0];
                $dday = $hdt1[2];
                $t_month = $this->chk_disp_date($hdt);

                if (intval($t_month) == 1) {
                    if (intval(date('d')) >= 15) {
                        $dmonth = date('m');
                        $dyear = date('Y');
                    }
                    //    else{
                    //$dmonth=date('%m');
                    //$dyear=date('%Y');
                    //}
                }
                if (intval($t_month) >= 2) {
                    $dmonth = date('m');
                    $dyear = date('Y');
                }
                ////END ADDED for month year correct entry
                if ($t_chk == 1) {
                    $dmonth = date('m');
                    $dyear = date('Y');
                }

                if ($row_cis["mainhead"] == "L")
                    $temp_mh = "L";
                else
                    $temp_mh = "R";
                if ($this->db->affectedRows() > 0) {
                    
                    // Prepare the raw SQL for the insert operation
                    $sql = "
                        INSERT INTO dispose_delete (diary_no, \"month\", dispjud, \"year\", ord_dt, disp_dt, disp_type, bench, jud_id, camnt, crtstat, usercode, ent_dt, jorder, rj_dt, disp_type_all)
                        SELECT diary_no, \"month\", dispjud, \"year\", ord_dt, disp_dt, disp_type, bench, jud_id, camnt, crtstat, usercode, ent_dt, jorder, rj_dt, disp_type_all
                        FROM dispose
                        WHERE diary_no = ?;
                    ";
                    
                    $this->db->query($sql, [$fno_o]);

                    // $str_up_disp1 = "INSERT INTO dispose_delete(diary_no, `month`, dispjud, `year`, ord_dt, disp_dt, disp_type, bench, jud_id, camnt, crtstat, usercode, ent_dt, jorder, rj_dt, disp_type_all) (SELECT diary_no, `month`, dispjud, `year`, ord_dt, disp_dt, disp_type, bench,jud_id, camnt, crtstat, usercode, ent_dt, jorder, rj_dt,disp_type_all FROM dispose where diary_no='" . $fno_o . "')";

                    // Prepare the variables (ensure these are sanitized before usage)
                    $data = [
                        'month'        => $dmonth,
                        'year'         => $dyear,
                        'dispjud'      => $j1,
                        'ord_dt'       => $dt_o,
                        'disp_dt'      => $hdt,
                        'disp_type'    => $disp_code,
                        'bench'        => $bench,
                        'jud_id'       => $judges,
                        'ent_dt'       => date('Y-m-d H:i:s'), // Current timestamp
                        'camnt'        => 0,  // Default value
                        'usercode'     => $ucode1,
                        'crtstat'      => $temp_mh,
                        'jorder'       => '',
                        'disp_type_all'=> $disp_type_all
                    ];

                    // Define the condition (WHERE clause)
                    $where = ['diary_no' => $fno_o];

                    // Use the query builder to update the data in the "dispose" table
                    $builder = $this->db->table('dispose');
                    $builder->update($data, $where);

                    // $str_up_disp = "UPDATE dispose SET month=" . $dmonth . ",year=" . $dyear . ",dispjud='" . $j1 . "', ord_dt='" . $dt_o . "', disp_dt='" . $hdt . "',disp_type=" . $disp_code . ", bench='" . $bench . "',jud_id='" . $judges . "',ent_dt=NOW(),camnt=0,usercode=" . $ucode1 . ",crtstat='" . $temp_mh . "',jorder='',disp_type_all='" . $disp_type_all . "' where diary_no='" . $fno_o . "'";

                    // mysql_query($str_up_disp1) or die(mysql_error() . $str_up_disp1);
                    // mysql_query($str_up_disp) or die(mysql_error() . $str_up_disp);
                } 
                else 
                {
                    // Prepare the variables (ensure these are sanitized before usage)
                    $data = [
                        'diary_no'      => $fno_o,
                        'month'          => $dmonth,
                        'year'           => $dyear,
                        'dispjud'        => $j1,
                        'ord_dt'         => $dt_o,
                        'disp_dt'        => $hdt,
                        'disp_type'      => $disp_code,
                        'bench'          => $bench,
                        'jud_id'         => $judges,
                        'ent_dt'         => date('Y-m-d H:i:s'), // Get the current timestamp
                        'camnt'          => 0,  // Assuming 0 is the default value
                        'usercode'       => $ucode1,
                        'crtstat'        => $temp_mh,
                        'jorder'         => '',
                        'disp_type_all'  => $disp_type_all
                    ];

                    // Insert data using the query builder
                    $builder = $this->db->table('dispose');
                    $builder->insert($data);

                    // $str_up_disp = "INSERT INTO dispose(diary_no, `month`,`year`,dispjud,ord_dt,disp_dt,disp_type,bench,jud_id,ent_dt,camnt,usercode,crtstat,jorder,disp_type_all) VALUES('" . $fno_o . "'," . $dmonth . "," . $dyear . ",'" . $j1 . "','" . $dt_o . "','" . $hdt . "'," . $disp_code . ",'" . $bench . "','" . $judges . "',NOW(),0," . $ucode1 . ",'" . $temp_mh . "','','" . $disp_type_all . "')";

                    //$str_up_disp = "INSERT INTO dispose(diary_no, month,year,dispjud,ord_dt,disp_dt,disp_type,disp_rem,disp_stat,bench,jud1,jud2,jud3,jud4,jud5,side,ent_dt,camnt,usercode,crtstat,jorder) VALUES('".$fno_o."',".$dmonth.",".$dyear.",".$j1.",'".$dt_o."','".$hdt."',".$disp_code.",'".$disp_str."','".$side."', '".$bench."',".$j1.",".$j2.",".$j3.",".$j4.",".$j5.",'".$nature."',NOW(),0,".$ucode1.",'".$temp_mh."','')";
                    // mysql_query($str_up_disp) or die(mysql_error() . $str_up_disp);
                }
                //echo $str_up_disp;
                //rgo_default table update
                
                // Use the query builder to create the update query
                $builder = $this->db->table('rgo_default');

                // Perform the update
                $builder->set('remove_def', 'Y')
                        ->where('fil_no2', $fno_o);
                        
                // $rgo = "Update rgo_default set remove_def='Y' WHERE fil_no2='" . $fno_o . "'";
                // mysql_query($rgo) or die(mysql_error() . $rgo);
                
                //rgo_default table update End

                // Archival Process
                $this->archived_data($fno_o);
            }
        }
    } //FUNCTION END

    public function archived_data($diary_no)
    {
        $output = [];

        // Call Restore Procedure
        try {
            $this->db->query("call archival_backup('$diary_no')");
            $output['success'] = 1;
            $output['message'] = "Case Archived.";
        } catch (\Exception $e) {
            log_error("Case Archival Error [$diary_no]: ", $e->getMessage());
            $output['success'] = 0;
            $output['message'] = "There is some problem. Please contact Computer-Cell.";
        }

        return $output;
    }

    public function clear_remarks($fno_o, $dt_o, $ucode1, $on)
    {
        // Start by getting the status from case_remarks_multiple
        $builder = $this->db->table('case_remarks_multiple');
        $builder->select('status')
                ->where('diary_no', $fno_o)
                ->where('cl_date', $dt_o)
                ->groupBy('status');

        $results_cis = $builder->get();

        if ($results_cis->getNumRows() > 0) 
        {
            foreach ($results_cis->getResultArray() as $row_cis) 
            {
                if ($on == 'H' || $on == '') 
                {
                    if ($row_cis['status'] == 'D') 
                    {
                        // Delete from dispose and insert into dispose_delete
                        $this->db->table('dispose')
                            ->delete(['diary_no' => $fno_o]);

                        // Insert data into dispose_delete
                        $this->db->table('dispose_delete')
                            ->insertBatch($this->db->table('dispose')
                                ->select('diary_no, month, dispjud, year, ord_dt, disp_dt, disp_type, bench, jud_id, camnt, crtstat, usercode, ent_dt, jorder, rj_dt, disp_type_all')
                                ->where('diary_no', $fno_o)
                                ->get()->getResultArray());

                        // Update main table
                        $this->db->table('main')
                            ->update([
                                'last_dt' => date('Y-m-d H:i:s'),
                                'lastorder' => '',
                                'c_status' => 'P',
                                'last_usercode' => $ucode1
                            ], ['diary_no' => $fno_o]);

                        // Update heardt table
                        $this->db->table('heardt')
                            ->set('listorder', 'CASE WHEN listorder = 48 THEN 16 ELSE listorder END', false)
                            ->where('diary_no', $fno_o)
                            ->update();

                        // Update rgo_default table
                        $this->db->table('rgo_default')
                            ->update(['remove_def' => 'N'], ['fil_no2' => $fno_o]);
                    }

                    if ($row_cis['status'] == 'P') {
                        // Update main table for 'P' status
                        $this->db->table('main')
                            ->update([
                                'last_dt' => date('Y-m-d H:i:s'),
                                'lastorder' => '',
                                'c_status' => 'P',
                                'last_usercode' => $ucode1
                            ], ['diary_no' => $fno_o]);

                        // Update heardt table for 'P' status
                        $this->db->table('heardt')
                            ->set('listorder', 'CASE WHEN listorder = 48 THEN 16 ELSE listorder END', false)
                            ->where('diary_no', $fno_o)
                            ->update();
                    }
                }
            }

            // Insert into case_remarks_multiple_history
            $this->db->table('case_remarks_multiple_history')
                ->insertBatch($this->db->table('case_remarks_multiple')
                    ->select('cl_date,r_head,head_content,remark,e_date,jcodes,remove,mainhead,clno,uid,dw,status,usr_entry,comp_date,notice_type,comp_comp_date,comp_remarks,last_updated,diary_no as fil_no,updated_by_ip,updated_by,updated_on,create_modify')
                    ->where('diary_no', $fno_o)
                    ->where('cl_date', $dt_o)
                    ->get()->getResultArray());

            // Select case_remarks_multiple with specific r_head values
            $builder = $this->db->table('case_remarks_multiple');
            $builder->select('status')
                    ->where('diary_no', $fno_o)
                    ->where('cl_date', $dt_o)
                    ->whereIn('r_head', [81, 74, 75, 65, 2, 1, 94])
                    ->groupBy('status');
            
            $results_cis1 = $builder->get();

            if ($results_cis1->getNumRows() > 0) {
                // Update the main table for admitted status
                $this->db->table('main')
                    ->like('admitted', 'admitted on ' . $dt_o)
                    ->update(['admitted' => ''], ['diary_no' => $fno_o]);
            }

            // Delete from case_remarks_multiple
            $this->db->table('case_remarks_multiple')
                ->delete(['diary_no' => $fno_o, 'cl_date' => $dt_o]);
        }
    }

    public function isNmdorShortCategorymatter_removed($diaryNo)
    {
        // Load the database
        $builder = $this->db->table('heardt h');
        
        // Perform the query with the necessary joins and conditions
        $builder->select('h.diary_no')
                ->join('mul_category mcat', 'h.diary_no = mcat.diary_no', 'inner')
                ->join('submaster s', 'mcat.submaster_id = s.id', 'inner')
                ->where('h.diary_no', $diaryNo)
                ->groupStart()  // Start the OR condition block
                    ->whereIn('s.id', [343, 15, 16, 17, 18, 19, 20, 21, 22, 23, 341, 353, 157, 158, 159, 160, 161, 162, 163, 166, 173, 175, 176, 322, 222])
                    ->orWhereIn('h.is_nmd', ['Y', '3', '4', '5'])
                ->groupEnd()  // End the OR condition block
                ->where('mcat.display', 'Y')
                ->where('s.display', 'Y')
                ->where('h.mainhead', 'M')
                ->groupStart()  // Handle mainhead_n conditions
                    ->where('h.mainhead_n', 'M')
                    ->orWhere('h.mainhead_n IS NULL')
                    ->orWhere('TRIM(h.mainhead_n)', '')
                ->groupEnd();

        // Execute the query and get the count of results
        $resultCount = $builder->countAllResults();

        // Return true if any result is found, else false
        return $resultCount > 0;
    }

    public function getNmdDate($tentativeDate, $nmdHeadContent)
    {
        // Initialize the day condition
        $dayCondition = "";
        
        // Set the day of the week based on the nmdHeadContent
        if ($nmdHeadContent != "ANY") {
            $dayOfWeek = 0;
            switch ($nmdHeadContent) {
                case 'TUESDAY':
                    $dayOfWeek = 2; // 0 = Sunday, 2 = Tuesday in PostgreSQL
                    break;
                case 'WEDNESDAY':
                    $dayOfWeek = 3;
                    break;
                case 'THURSDAY':
                    $dayOfWeek = 4;
                    break;
            }
            // Add the day condition for PostgreSQL
            $dayCondition = " AND EXTRACT(DOW FROM working_date) = $dayOfWeek";
        }

        // Build the query for the first case
        $builder = $this->db->table('master.sc_working_days');
        $builder->select('working_date')
                ->where('is_holiday', 0)
                ->where('EXTRACT(WEEK FROM working_date)', date('W', strtotime($tentativeDate)))
                ->where('EXTRACT(YEAR FROM working_date)', date('Y', strtotime($tentativeDate)))
                ->orderBy('working_date', 'desc')
                ->limit(1);

        // Add the day condition if necessary
        if ($dayCondition) {
            $builder->where("EXTRACT(DOW FROM working_date)", $dayOfWeek);
        }

        // Execute the first query
        $query = $builder->get();
        $row = $query->getRowArray();

        // If a result is found, return the working date
        if ($row) {
            return date('Y-m-d', strtotime($row['working_date']));
        } else {
            // If no result is found, try the second query
            $builder->where('working_date >=', $tentativeDate)
                    ->where('is_holiday', 0)
                    ->orderBy('working_date', 'asc')
                    ->limit(1);

            // Execute the second query
            $query = $builder->get();
            $row = $query->getRowArray();

            // If a result is found, return the working date
            if ($row) {
                return date('Y-m-d', strtotime($row['working_date']));
            }
        }

        // If no valid date is found, return the tentative date
        return $tentativeDate;
    }

    public function getNextNmdDate($tentativeDate, $nmdHeadContent)
    {
        // Initialize the day condition
        $dayCondition = "";
        
        // Set the day of the week based on the nmdHeadContent
        if ($nmdHeadContent != "ANY") {
            $dayOfWeek = 0;
            switch ($nmdHeadContent) {
                case 'TUESDAY':
                    $dayOfWeek = 2; // 0 = Sunday, 2 = Tuesday in PostgreSQL
                    break;
                case 'WEDNESDAY':
                    $dayOfWeek = 3;
                    break;
                case 'THURSDAY':
                    $dayOfWeek = 4;
                    break;
            }
            $dayCondition = " AND EXTRACT(DOW FROM working_date) = $dayOfWeek";
        }

        // Start building the query
        $builder = $this->db->table('master.sc_working_days');
        $builder->select('working_date')
                ->where('is_holiday', 0)
                ->where('working_date >=', $tentativeDate)
                ->orderBy('working_date', 'asc')
                ->limit(1);

        // Add the day condition if necessary
        if (!empty($dayCondition)) {
            $builder->where('EXTRACT(DOW FROM working_date)', $dayOfWeek);
        }

        // Execute the first query
        $query = $builder->get();
        $row = $query->getRowArray();

        // If a result is found, return the working date
        if (!empty($row)) {
            return date('Y-m-d', strtotime($row['working_date']));
        } else {
            // If no result is found, try the second query
            $builder->where('working_date >=', $tentativeDate)
                    ->where('is_holiday', 0)
                    ->orderBy('working_date', 'asc')
                    ->limit(1);
            
            // Execute the second query
            $query = $builder->get();
            $row = $query->getRowArray();
            
            // If a result is found, return the working date
            if (!empty($row)) {
                return date('Y-m-d', strtotime($row['working_date']));
            }
        }

        // Return the tentative date if no valid date is found
        return $tentativeDate;
    }

    public function getNextNmdDateNew($listing_date)
    {
        // Prepare the query builder for the 'sc_working_days' table
        $builder = $this->db->table('master.sc_working_days');

        // Use the query builder methods to construct the query
        $builder->select('working_date');
        $builder->where('EXTRACT(DOW FROM working_date)', 2);  // 2 corresponds to Wednesday (DAYOFWEEK=3 in MySQL)
        $builder->where('is_holiday', 0);
        $builder->where('working_date >', $listing_date);
        $builder->orderBy('working_date', 'asc');
        $builder->limit(1);

        // Execute the query
        $query = $builder->get();

        // $heardtNmdQuery = "select working_date as working_date from sc_working_days where DAYOFWEEK(working_date)=3 and  is_holiday = 0 and
        // working_date > '$listing_date' order by working_date asc limit 0,1 ";    //removed is_nmd=1 and added DAYOFWEEK(working_date)=3 by preeti on 30.4.2024
        // $result = mysql_query($heardtNmdQuery) or die(mysql_error());
        
        if ($query->getNumRows() > 0) {
            $row_cis = $query->getRowArray();
            return date($row_cis['working_date']);
        }
    }


    public function check_section($dacode, $matter_section)
    {
        // Prepare the builder to query the 'users' table
        $builder = $this->db->table('users');

        // Use query builder methods to add conditions
        $builder->select('*');
        $builder->where('usercode', $dacode);
        $builder->where('display', 'Y');

        // Execute the query
        $da_section_rs = $builder->get();
        
        // $da_section_qr = "select * from users where usercode='$dacode' and display='Y'";
        // $da_section_rs = mysql_query($da_section_qr) or die(__LINE__ . '->' . mysql_error());

        $da_data = $da_section_rs->getRowArray();
        if ($da_data['section'] != $matter_section) {
            //echo "insert into matters_with_wrong_section(diary_no,dacode,da_section_id,matter_section_id,ent_by,ent_on) values('$_REQUEST[dno]','$dacode','$da_data[section]','$matter_section','$_SESSION[dcmis_user_idd]',now())";

            // Prepare the data to insert into the 'matters_with_wrong_section' table
            $data = [
                'diary_no'       => $_REQUEST['dno'],
                'dacode'         => $dacode,
                'da_section_id'  => $da_data['section'],
                'matter_section' => $matter_section,
                'ent_by'         => $_SESSION['dcmis_user_idd'],
                'ent_on'         => date('Y-m-d H:i:s') // CI4's way of getting current timestamp
            ];

            // Insert data into the table using the query builder
            $builder = $this->db->table('matters_with_wrong_section');
            $builder->insert($data);
            
            // mysql_query("insert into matters_with_wrong_section(diary_no,dacode,da_section_id,matter_section_id,ent_by,ent_on) values('$_REQUEST[dno]','$dacode','$da_data[section]','$matter_section','$_SESSION[dcmis_user_idd]',now())") or die(__LINE__ . '->' . mysql_error());

            return;
        }
    }

    public function get_da($dno)
    {
        $sec_da_upto_disposal = array(21, 55);
        $sec_da_diary = array();

        // Using the query builder for the SELECT statement
        $builder = $this->db->table('main');
        $builder->select("section_id, dacode, from_court, ref_agency_state_id, ref_agency_code_id, 
                        CASE WHEN (active_casetype_id = 0 OR active_casetype_id IS NULL OR active_casetype_id = '') 
                            THEN casetype_id 
                            ELSE active_casetype_id 
                        END AS casetype_id, 
                        EXTRACT(YEAR FROM 
                            CASE WHEN (active_fil_dt = '0000-00-00 00:00:00' OR active_fil_dt IS NULL OR active_fil_dt = '') 
                                THEN diary_no_rec_date 
                                ELSE active_fil_dt 
                            END) AS regyear, 
                        DATE(diary_no_rec_date) AS fildate, 
                        DATE(CASE WHEN (active_fil_dt = '0000-00-00 00:00:00' OR active_fil_dt IS NULL OR active_fil_dt = '') 
                                THEN diary_no_rec_date 
                                ELSE active_fil_dt 
                            END) AS filregdate")
                ->where('diary_no', $dno);

        // Executing the query
        $query = $builder->get();

        // $query = "SELECT section_id,dacode,from_court,ref_agency_state_id,ref_agency_code_id,
        // #casetype_id,
        // if((active_casetype_id=0 or active_casetype_id is null or active_casetype_id=''),casetype_id,active_casetype_id) casetype_id,
        // #YEAR(if((fil_dt='0000-00-00 00:00:00' or fil_dt is null or fil_dt=''),diary_no_rec_date,fil_dt))regyear,
        // YEAR(if((active_fil_dt='0000-00-00 00:00:00' or active_fil_dt is null or active_fil_dt=''),diary_no_rec_date,active_fil_dt))regyear,
        // #YEAR(fil_dt)regyear,
        // #DATE(if((active_fil_dt='0000-00-00 00:00:00' or active_fil_dt is null or active_fil_dt=''),diary_no_rec_date,active_fil_dt))regdate
        // DATE(diary_no_rec_date)fildate,
        // DATE(if((active_fil_dt='0000-00-00 00:00:00' or active_fil_dt is null or active_fil_dt=''),diary_no_rec_date,active_fil_dt)) filregdate
        // FROM main WHERE diary_no='$dno'";
        // $result = mysql_query($query) or die(__LINE__ . '->' . mysql_error());

        if ($query->getNumRows() > 0) {
            $rcasetype = array(1, 3);
            $row_main = $query->getRowArray();

            //check if dacode already exist and section is matching with da section  matters_with_wrong_section
            if ($row_main['dacode'] != 0 && $row_main['dacode'] != '') {
                if (in_array($row_main['section_id'], $sec_da_upto_disposal)) {
                    return;
                }
            }

            $previous_daname = array(39, 9, 10, 19, 20, 25, 26);
            $forXandPIL = array(5, 6);
            if (in_array($row_main['casetype_id'], $previous_daname)) {
                
                // Using the query builder to select from lowerct table
                $builder = $this->db->table('lowerct');
                $builder->select('ct_code, l_state, lct_casetype, lct_caseno, lct_caseyear')
                        ->where('diary_no', $dno)
                        ->where('lw_display', 'Y'); // Adds the condition for lw_display = 'Y'

                // Executing the query
                $lower_case_temp = $builder->get();
                
                // $lower_case_temp = "SELECT ct_code,l_state,lct_casetype,lct_caseno,lct_caseyear FROM `lowerct` WHERE `diary_no` = '$dno' and lw_display='Y' ";
                // $lower_case_temp = mysql_query($lower_case_temp) or die(__LINE__ . '->' . mysql_error());

                if ($lower_case_temp->getNumRows() > 0) {

                    $lower_case_temp_row = $lower_case_temp->getRowArray();

                    // Use CodeIgniter's query builder
                    $builder = $this->db->table('main_casetype_history a');
                    
                    // Join statements
                    $builder->select('b.dacode, a.diary_no, new_registration_number, 
                        split_part(new_registration_number, \'-\', 2) as reg_number_part_1, 
                        split_part(new_registration_number, \'-\', -1) as reg_number_part_2, 
                        dacode, name, section_name, casetype_id, active_casetype_id, diary_no_rec_date, 
                        reg_year_mh, reg_year_fh, active_reg_year, ref_agency_state_id')
                        ->join('main b', 'a.diary_no = b.diary_no', 'left')
                        ->join('users c', 'b.dacode = c.usercode', 'left')
                        ->join('usersection us', 'c.section = us.id', 'left');

                    // Where condition
                    $builder->where('ref_new_case_type_id', $lower_case_temp_row['lct_casetype']);
                    $builder->where('new_registration_year', $lower_case_temp_row['lct_caseyear']);
                    $builder->where('is_deleted', 'f');

                    // Additional condition with padded number
                    $lower_case_temp_row['lct_caseno'] = (int) $lower_case_temp_row['lct_caseno'];
                    $builder->where($lower_case_temp_row['lct_caseno']. " BETWEEN 
                        CAST(split_part(new_registration_number, '-', 2) AS INTEGER) 
                        AND CAST(split_part(new_registration_number, '-', -1) AS INTEGER)");

                    // Execute the query
                    $for_da_temp = $builder->get();

                    // $for_da_temp = "SELECT b.dacode,a.diary_no,new_registration_number,SUBSTRING_INDEX(SUBSTRING_INDEX(new_registration_number, '-', 2),'-',-1),SUBSTRING_INDEX(new_registration_number, '-', -1),
                    // dacode,name,section_name,casetype_id,active_casetype_id,diary_no_rec_date,reg_year_mh,reg_year_fh,active_reg_year,ref_agency_state_id
                    // FROM `main_casetype_history` a
                    // LEFT JOIN main b ON a.diary_no=b.diary_no
                    // LEFT JOIN users c ON b.dacode = c.usercode
                    // LEFT JOIN usersection us ON c.section=us.id
                    // where ref_new_case_type_id=$lower_case_temp_row[lct_casetype] and new_registration_year=$lower_case_temp_row[lct_caseyear]
                    // and is_deleted='f' and '" . str_pad($lower_case_temp_row['lct_caseno'], 6, 0, STR_PAD_LEFT) . "'
                    // between SUBSTRING_INDEX(SUBSTRING_INDEX(new_registration_number, '-', 2),'-',-1) and SUBSTRING_INDEX(new_registration_number, '-', -1)";
                    // $for_da_temp = mysql_query($for_da_temp) or die(__LINE__ . '->' . mysql_error());
                    
                    if ($for_da_temp->getNumRows() > 0) {

                        $row_da = $for_da_temp->getRowArray();

                        $this->check_section($row_da['dacode'], $row_main['section_id']);

                        // Prepare the data for updating
                        $data = [
                            'dacode'        => $row_da['dacode'],
                            'last_usercode' => $_SESSION['dcmis_user_idd'],
                            'last_dt'       => date('Y-m-d H:i:s')  // PostgreSQL uses standard timestamp format
                        ];

                        // Using CodeIgniter's query builder to update the table
                        $builder = $this->db->table('main');
                        $builder->set($data);
                        $builder->where('diary_no', $dno);
                        $builder->update();

                        // $update = "UPDATE main SET dacode=$row_da[dacode], last_usercode=$_SESSION[dcmis_user_idd],last_dt=NOW() WHERE diary_no=$dno";
                        // mysql_query($update) or die(__LINE__ . '->' . mysql_error());
                    }
                }
            } else {
                $dacodeallotted = 0;
                if (in_array($row_main['casetype_id'], $forXandPIL)) {
                    
                    // Define the list of submaster_ids to filter by
                    $submasterIds = [
                        349, 118, 119, 120, 121, 122, 123, 124, 125, 126, 127, 128, 129, 130, 
                        131, 132, 133, 318, 332, 567, 568, 569, 570, 571, 572, 573, 574, 575, 
                        576, 577, 578, 579, 580, 581, 582
                    ];

                    // Query to fetch the submaster_id from mul_category table
                    $builder = $this->db->table('mul_category');
                    $builder->select('submaster_id');
                    $builder->where('diary_no', $dno);
                    $builder->whereIn('submaster_id', $submasterIds);
                    $builder->where('display', 'Y');

                    // Execute the query
                    $submaster_rs = $builder->get();
                    
                    
                    // $submaster = "SELECT submaster_id FROM mul_category WHERE diary_no='$dno' AND submaster_id
                    // IN ( 349,118,119,120,121,122,123,124,125,126,127,128,129,130,131,132,133,318,332,567,568,569,570,571,572,573,574,575,576,577,578,579,580,581,582 ) AND display = 'Y'";
                    // $submaster_rs = mysql_query($submaster) or die(__LINE__ . '->' . mysql_error());

                    if ($submaster_rs->getNumRows() > 0) {

                        $this->check_section('690', $row_main['section_id']);

                        // Use CodeIgniter's query builder to update the 'main' table
                        $builder = $this->db->table('main');

                        // Prepare data for the update
                        $data = [
                            'dacode' => 690,  // DACODE value to update
                            'last_usercode' => $_SESSION['dcmis_user_idd'],  // User code from session
                            'last_dt' => date('Y-m-d H:i:s')  // Current date and time in PostgreSQL format
                        ];

                        // Perform the update query using 'where' to filter by diary_no
                        $builder->update($data, ['diary_no' => $dno]);

                        // $update = "UPDATE main SET dacode=690, last_usercode=$_SESSION[dcmis_user_idd],last_dt=NOW() WHERE diary_no=$dno";
                        // mysql_query($update) or die(__LINE__ . '->' . mysql_error());


                        $dacodeallotted = 1;
                    } else
                        $dacodeallotted = 0;
                } else if ($row_main['from_court'] == '5') {

                    $tribunal = '';

                    // Get the value of ref_agency_code_id
                    $builder = $this->db->table('ref_agency_code');
                    
                    // Perform the query to fetch 'agency_or_court' based on 'ref_agency_code_id'
                    $tribunal_sec_rs = $builder->select('agency_or_court')
                                    ->where('id', $row_main['ref_agency_code_id'])
                                    ->get();
                                    
                    // $tribunal_sec_qr = "select agency_or_court from ref_agency_code where id='$row_main[ref_agency_code_id]'";
                    // $tribunal_sec_rs = mysql_query($tribunal_sec_qr) or die(__LINE__ . '->' . mysql_error());
                    
                    if ($tribunal_sec_rs->getNumRows() > 0) {
                        $tribunal_sec_arr = $tribunal_sec_rs->getRowArray();
                        $tribunal = $tribunal_sec_arr['agency_or_court'];
                    }

                    if ($tribunal == 5) {

                        // Construct the query using CodeIgniter's query builder
                        $builder = $this->db->table('da_case_distribution_tri');
                        
                        // Using query builder to build the SELECT query
                        $builder->select('dacode')
                        ->where('case_type', $row_main['casetype_id'])
                        ->where('YEAR(now()) BETWEEN case_f_yr AND case_t_yr')
                        ->where('ref_agency IS NOT NULL')
                        ->where('POSITION(' . $this->db->escape($row_main['ref_agency_code_id']) . ' IN ref_agency) > 0')
                        ->where('display', 'Y');
                        
                        // Execute the query and fetch the result
                        $query = $builder->get();
                        
                        // $query = "SELECT dacode FROM da_case_distribution_tri WHERE case_type=$row_main[casetype_id]
                        //     AND YEAR(now()) between case_f_yr and case_t_yr and ref_agency is not null and FIND_IN_SET($row_main[ref_agency_code_id],ref_agency) and display='Y'";
                        // $result = mysql_query($query) or die(__LINE__ . '->' . mysql_error());

                        if ($query->getNumRows() > 0) {
                            if ($query->getNumRows() > 1) {
                                $dacodeallotted = 0;
                            } else {
                                $row_da = $query->getRowArray();

                                $this->check_section($row_da['dacode'], $row_main['section_id']);

                                // Prepare the data to be updated
                                $data = [
                                    'dacode' => $row_da['dacode'],
                                    'last_usercode' => $_SESSION['dcmis_user_idd'],
                                    'last_dt' => date('Y-m-d H:i:s') // Now() equivalent in PostgreSQL
                                ];

                                // Perform the update using query builder
                                $builder = $this->db->table('main');
                                $builder->set($data)
                                        ->where('diary_no', $dno)
                                        ->update();
                                        
                                // $update = "UPDATE main SET dacode=$row_da[dacode], last_usercode=$_SESSION[dcmis_user_idd],last_dt=NOW() WHERE diary_no=$dno";
                                // mysql_query($update) or die(__LINE__ . '->' . mysql_error());

                                $dacodeallotted = 1;
                            }
                        } else if (in_array($row_main['casetype_id'], $rcasetype)) {
                            
                            $builder = $this->db->table('users');
                            $builder->select('usercode')
                                    ->where('section', 82)
                                    ->where('usertype', 14)
                                    ->where('display', 'Y');
                            $rs_bo = $builder->get();

                            // $sql_bo = "select  usercode  from users where section=82 and usertype=14 and display='Y'";
                            // $rs_bo = mysql_query($sql_bo);
                            
                            $rw_bo = $rs_bo->getRowArray();
                            $bocode = $rw_bo['usercode'];

                            $this->check_section($bocode, $row_main['section_id']);

                            // Get the builder instance for the 'main' table
                            $builder = $this->db->table('main');

                            // Prepare data to update
                            $data = [
                                'dacode'        => $bocode,
                                'last_usercode' => $_SESSION['dcmis_user_idd'],
                                'last_dt'       => date('Y-m-d H:i:s') // Current timestamp in PostgreSQL format
                            ];

                            // Perform the update
                            $builder->set($data)
                                    ->where('diary_no', $dno)
                                    ->update();


                            // $update = "UPDATE main SET dacode='$bocode', last_usercode=$_SESSION[dcmis_user_idd],last_dt=NOW() WHERE diary_no=$dno";
                            // mysql_query($update) or die(__LINE__ . '->' . mysql_error());

                            $dacodeallotted = 1;
                        }
                    } else {

                        // Get the builder instance for the 'da_case_distribution_tri' table
                        $builder = $this->db->table('da_case_distribution_tri');

                        // Prepare the query using Query Builder
                        $builder->select('dacode')
                                ->where('case_type', $row_main['casetype_id'])
                                ->where('YEAR(now()) BETWEEN case_f_yr AND case_t_yr')  // PostgreSQL equivalent for YEAR(now())
                                ->where('ref_agency IS NOT NULL')
                                ->where("array_position(string_to_array(ref_agency, ','), ?) IS NOT NULL", [$row_main['ref_agency_code_id']])
                                ->where('display', 'Y');

                        // Execute the query and get the result
                        $query = $builder->get();

                        // $query = "SELECT dacode FROM da_case_distribution_tri WHERE case_type=$row_main[casetype_id]
                        //     AND YEAR(now()) between case_f_yr and case_t_yr and ref_agency is not null and FIND_IN_SET($row_main[ref_agency_code_id],ref_agency) and display='Y'";
                        // $result = mysql_query($query) or die(__LINE__ . '->' . mysql_error());

                        if ($query->getNumRows() > 0) {
                            if ($query->getNumRows() > 1) {
                                $dacodeallotted = 0;
                            } else {
                                $row_da = $query->getRowArray();

                                $this->check_section($row_da['dacode'], $row_main['section_id']);

                                // Prepare the data to be updated
                                $data = [
                                    'dacode' => $row_da['dacode'],
                                    'last_usercode' => $_SESSION['dcmis_user_idd'],
                                    'last_dt' => date('Y-m-d H:i:s') // Now() equivalent in PostgreSQL
                                ];

                                // Perform the update using query builder
                                $builder = $this->db->table('main');
                                $builder->set($data)
                                        ->where('diary_no', $dno)
                                        ->update();

                                // $update = "UPDATE main SET dacode=$row_da[dacode], last_usercode=$_SESSION[dcmis_user_idd],last_dt=NOW() WHERE diary_no=$dno";
                                // mysql_query($update) or die(__LINE__ . '->' . mysql_error());

                                $dacodeallotted = 1;
                            }
                        } else if (in_array($row_main['casetype_id'], $rcasetype)) {

                            // Get the builder instance for the 'users' table
                            $builder = $this->db->table('users');

                            // Prepare the query using Query Builder
                            $builder->select('usercode')
                                    ->where('section', 52)
                                    ->where('usertype', 14)
                                    ->where('display', 'Y');

                            // Execute the query and get the result
                            $rs_bo = $builder->get();

                            // $sql_bo = "select  usercode  from users where section=52 and usertype=14 and display='Y'";
                            // $rs_bo = mysql_query($sql_bo);

                            $rw_bo = $rs_bo->getRowArray();
                            $bocode = $rw_bo[0];

                            $this->check_section($bocode, $row_main['section_id']);

                            // Prepare the data to be updated
                            $data = [
                                'dacode' => $bocode,
                                'last_usercode' => $_SESSION['dcmis_user_idd'],
                                'last_dt' => date('Y-m-d H:i:s') // Now() equivalent in PostgreSQL
                            ];

                            // Perform the update using query builder
                            $builder = $this->db->table('main');
                            $builder->set($data)
                                    ->where('diary_no', $dno)
                                    ->update();

                            // $update = "UPDATE main SET dacode='$bocode', last_usercode=$_SESSION[dcmis_user_idd],last_dt=NOW() WHERE diary_no=$dno";
                            // mysql_query($update) or die(__LINE__ . '->' . mysql_error());
                            
                            $dacodeallotted = 1;
                        }
                    }
                }

                //else
                if ($dacodeallotted == 0) {
                    //if da allocation to be done in diary matters which is diarized in previous year but allocated in current year
                    if ($row_main['regyear'] < date("Y") and  !in_array($row_main['section_id'], $sec_da_upto_disposal))
                        $row_main['regyear'] = date("Y");

                    // Using PostgreSQL's window functions and date extraction to generate rownum
                    $builder = $this->db->table('main a');

                    // Using window function ROW_NUMBER() for generating rownum
                    $builder->select("a.diary_no, a.fil_dt, ROW_NUMBER() OVER (ORDER BY a.fil_dt) AS rownum")
                            ->where('a.ref_agency_state_id', $row_main['ref_agency_state_id'])
                            ->where('a.active_casetype_id', $row_main['casetype_id'])
                            ->where("EXTRACT(YEAR FROM COALESCE(NULLIF(a.active_fil_dt, '0000-00-00 00:00:00'), a.diary_no_rec_date)) = ", $row_main['regyear'])
                            ->orderBy('a.fil_dt');

                    // Execute the query
                    $query = $builder->get();

                    $number_for_rs = $query->getResultArray();

                    // $number_for = "SELECT a.diary_no, fil_dt, @rn := @rn +1 AS rownum
                    //                 FROM main a,(SELECT @rn :=0)x_sar
                    //                 WHERE ref_agency_state_id=$row_main[ref_agency_state_id]
                    //                 AND active_casetype_id=$row_main[casetype_id]
                    //                 AND YEAR(if((active_fil_dt='0000-00-00 00:00:00' or active_fil_dt is null or active_fil_dt=''),diary_no_rec_date,active_fil_dt))='$row_main[regyear]'
                    //                 ORDER BY fil_dt";
                    // $number_for_rs = mysql_query($number_for) or die(__LINE__ . '->' . mysql_error());

                    $current_no = 1;
                    foreach ($number_for_rs as $row_number_for) {
                        if ($row_number_for['diary_no'] == $_REQUEST['dno'])
                            $current_no = $row_number_for['rownum'];
                    }

                    // Get the builder instance for the 'da_case_distribution_new' table
                    $builder = $this->db->table('da_case_distribution_new');

                    if (in_array($row_main['section_id'], $sec_da_upto_disposal)) {

                        // Prepare the query using Query Builder
                        $builder->select('dacode')
                                ->where('case_type', $row_main['casetype_id'])
                                ->groupStart()
                                    ->where('state', $row_main['ref_agency_state_id'])
                                    ->orWhere('state', 0)
                                ->groupEnd()
                                ->where('display', 'Y');

                        // Add date conditions with proper formatting
                        $builder->where("$current_no BETWEEN case_from AND case_to")
                                ->where($row_main['fildate']. " BETWEEN case_f_yr AND case_t_yr");

                        // $query = "SELECT dacode FROM da_case_distribution_new WHERE case_type=$row_main[casetype_id] AND '$current_no' BETWEEN case_from AND case_to
                        //     AND '$row_main[fildate]' BETWEEN case_f_yr AND case_t_yr
                        //     AND (state=$row_main[ref_agency_state_id] OR state=0) AND display='Y'";
                    } else {

                        // Use the query builder to create the query
                        $builder = $this->db->table('da_case_distribution_new');
                        $builder->select('dacode')
                                ->where('case_type', $row_main['casetype_id'])
                                ->where("$current_no BETWEEN case_from AND case_to")
                                ->where($row_main['filregdate']." BETWEEN case_f_yr AND case_t_yr")
                                ->groupStart()
                                    ->where('state', $row_main['ref_agency_state_id'])
                                    ->orWhere('state', 0)
                                ->groupEnd()
                                ->where('display', 'Y');

                        // Execute the query
                        $query = $builder->get();

                        // $query = "SELECT dacode FROM da_case_distribution_new WHERE case_type=$row_main[casetype_id] AND '$current_no' BETWEEN case_from AND case_to
                        //     AND '$row_main[filregdate]' BETWEEN case_f_yr AND case_t_yr
                        //     AND (state=$row_main[ref_agency_state_id] OR state=0) AND display='Y'";

                        // $result = mysql_query($query) or die(__LINE__ . '->' . mysql_error());

                        if ($query->getNumRows() <= 0) {

                            // Use the query builder to create the query
                            $builder = $this->db->table('da_case_distribution');
                            $builder->select('dacode')
                                    ->where('case_type', $row_main['casetype_id'])
                                    ->where("$current_no BETWEEN case_from AND case_to")
                                    ->where($row_main['regyear']." BETWEEN case_f_yr AND case_t_yr")
                                    ->groupStart()
                                        ->where('state', $row_main['ref_agency_state_id'])
                                        ->orWhere('state', 0)
                                    ->groupEnd()
                                    ->where('display', 'Y');

                            // Execute the query
                            $query = $builder->get();

                            // $query = "SELECT dacode FROM da_case_distribution WHERE case_type=$row_main[casetype_id] AND '$current_no' BETWEEN case_from AND case_to
                            // AND '$row_main[regyear]' BETWEEN case_f_yr AND case_t_yr
                            // AND (state=$row_main[ref_agency_state_id] OR state=0) AND display='Y'";
                        }
                    }
                    
                    // Execute the query
                    $query = $builder->get();

                    // $result = mysql_query($query) or die(__LINE__ . '->' . mysql_error());

                    if ($query->getNumRows() > 0) {
                        if ($query->getNumRows() > 1) {
                        } else {
                            $row_da = $query->getRowArray();

                            $this->check_section($row_da['dacode'], $row_main['section_id']);

                            // Prepare the data to be updated
                            $data = [
                                'dacode' => $row_da['dacode'],
                                'last_usercode' => $_SESSION['dcmis_user_idd'],
                                'last_dt' => date('Y-m-d H:i:s') // Now() equivalent in PostgreSQL
                            ];

                            // Perform the update using query builder
                            $builder = $this->db->table('main');
                            $builder->set($data)
                                    ->where('diary_no', $dno)
                                    ->update();

                            // $update = "UPDATE main SET dacode=$row_da[dacode], last_usercode=$_SESSION[dcmis_user_idd],last_dt=NOW() WHERE diary_no=$dno";
                            // mysql_query($update) or die(__LINE__ . '->' . mysql_error());
                        }
                    } else {
                    }
                }
            }
        }
    }
}
