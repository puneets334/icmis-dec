<?php

namespace App\Models\Listing;

use CodeIgniter\Model;

class AllocationTp extends Model
{
    protected $table = 'main';
    protected $primaryKey = 'diary_no';
    protected $allowedFields = ['diary_no', 'remark', 'usercode', 'ent_dt'];


    public function getCaseDetails($dno)
    {
        if (empty($dno)) {
            return null;
        }
        $builder = $this->db->table('main a');
        $builder->select("aa.next_dt as advance_list_date,a.diary_no_rec_date,a.fil_dt,a.lastorder, a.pet_name, 
            a.res_name,a.c_status,b.listorder,b.next_dt,b.mainhead,b.subhead,b.clno,b.brd_slno,b.roster_id, 
            b.judges,b.board_type,b.main_supp_flag,b.tentative_cl_dt,b.sitting_judges,c.remark,b.is_nmd");

        $builder->join('heardt b', 'a.diary_no  = b.diary_no ', 'left');
        $builder->join('brdrem c', 'CAST(a.diary_no AS BIGINT) = CAST(c.diary_no AS BIGINT)', 'left');
        $builder->join('advance_allocated aa', 'CAST(b.diary_no AS BIGINT) = CAST(aa.diary_no AS BIGINT) AND b.next_dt = aa.next_dt', 'left');
        $builder->where('b.diary_no ', $dno);
        $query = $builder->get();

        return $query->getRowArray();
    }

    public function getAllocation($params)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('main m')
            ->select('m.*, group_concat(c.diary_no) as child_case')
            ->join('heardt h', 'm.diary_no = h.diary_no', 'left')
            ->join('listing_purpose l', 'l.code = h.listorder', 'left')
            ->join('casetype c', 'm.active_casetype_id = c.casecode', 'left')
            ->join('mul_category c2', 'c2.diary_no = h.diary_no AND c2.display = "Y" AND c2.submaster_id != 331 AND c2.submaster_id != ""', 'left')
            ->join('advance_allocated ad_al', 'ad_al.diary_no = h.diary_no AND ad_al.next_dt = "' . $params['listing_dt'] . '"', 'left')
            ->join('advanced_drop_note ad_dn', 'ad_dn.diary_no = ad_al.diary_no AND ad_dn.cl_date = ad_al.next_dt', 'left')
            ->groupBy('h.diary_no')
            ->where('l.display', 'Y');

        // Add filters based on POST data
        if ($params['is_nmd'] !== '0') {
            $builder->where('h.is_nmd', $params['is_nmd']);
        }
        if ($params['sitting_judges'] != 0) {
            $builder->whereIn('h.sitting_judges', [$params['sitting_judges']]);
        }
        if ($params['listing_purpose'] != 'all') {
            $builder->whereIn('h.listorder', $params['listing_purpose']);
        }
        if ($params['mainhead'] === 'F') {
            $builder->orderBy('CASE WHEN m.listorder in (4,5,7,8) THEN IF(' . $params['main_supp'] . ' = 2, m.next_dt = "' . $params['listing_dt'] . '", (m.next_dt BETWEEN "' . $params['listing_dt'] . '" AND ADDDATE("' . $params['listing_dt'] . '", INTERVAL 7 - DAYOFWEEK("' . $params['listing_dt'] . '") DAY) OR m.next_dt <= CURDATE())) ELSE m.next_dt > "1947-08-15" END, CAST(RIGHT(m.diary_no, 4) AS UNSIGNED) ASC, CAST(LEFT(m.diary_no, LENGTH(m.diary_no)-4) AS UNSIGNED) ASC');
        } else {
            $builder->orderBy('IF(date(ia_filing_dt) is not null,1,2), date(ia_filing_dt), CAST(RIGHT(m.diary_no, 4) AS UNSIGNED) ASC, CAST(LEFT(m.diary_no, LENGTH(m.diary_no)-4) AS UNSIGNED) ASC');
        }

        // Add more conditions based on params
        // Example:
        if ($params['from_yr'] != '0' && $params['to_yr'] != '0') {
            $builder->where('YEAR(m.diary_no_rec_date) BETWEEN', [$params['from_yr'], $params['to_yr']]);
        }
        if ($params['civil_criminal'] == 'C' || $params['civil_criminal'] == 'R') {
            $builder->where('m.case_grp', $params['civil_criminal']);
        }
        if ($params['bench'] != 'A') {
            $builder->where('h.board_type', $params['bench']);
        }

        // Other filters can be added similarly

        return $builder->get()->getResultArray();
    }

    public function getTotalInPool()
    {
        $builder = $this->db->table('main m');
        $builder->selectCount('m.diary_no', 'total', true)
            ->join('vacation_registrar_pool vrp', 'vrp.diary_no = m.diary_no')
            ->join('heardt h', 'h.diary_no = m.diary_no', 'left')
            ->join('master.users u', 'u.usercode = m.dacode', 'left')
            ->join('vacation_registrar_not_ready_cl v', 'v.diary_no = m.diary_no AND v.display = \'Y\' AND EXTRACT(YEAR FROM v.ent_dt) = EXTRACT(YEAR FROM CURRENT_DATE) AND EXTRACT(YEAR FROM v.list_dt) = EXTRACT(YEAR FROM CURRENT_DATE)', 'left')
            ->where('vrp.display', 'Y')
            ->where('v.diary_no IS NULL')
            ->where('m.c_status', 'P')
            ->where('EXTRACT(YEAR FROM vrp.ent_dt) = EXTRACT(YEAR FROM CURRENT_DATE)');
        //pr($builder->getCompiledSelect());
        $query = $builder->get();
        $result = $query->getRowArray();

        return $result['total'] ?? 0;
    }


    public function getRegistrars()
    {
        $builder = $this->db->table('master.judge');
        $builder->select('jcode, jname, first_name, sur_name');
        $builder->like('title', '%REGISTRAR%');
        $builder->where('display', 'Y');
        $builder->whereIn('jcode', [543, 544]);
        $builder->orderBy('jcode');

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getSingleJudgeFinalPoolMain($next_dt)
    {
        $formattedDate = date('Y-m-d', strtotime($next_dt));
        $builder = $this->db->table('main m');
        $builder->select('COUNT(DISTINCT m.diary_no) as total');
        $builder->join('heardt h', 'h.diary_no = m.diary_no', 'inner');
        $builder->join('mul_category mc', 'mc.diary_no = m.diary_no', 'inner');
        $builder->join('rgo_default rd', 'rd.fil_no = h.diary_no AND rd.remove_def = \'N\'', 'left');

        // Where conditions
        $builder->where('m.c_status', 'P');
        $builder->groupStart()
            ->where('CAST(m.diary_no AS text) = CAST(m.conn_key AS text)')  // Use CAST for type conversion
            ->orWhere('m.conn_key', '0')
            ->orWhere('m.conn_key IS NULL')
            ->groupEnd();
        $builder->whereNotIn('m.active_casetype_id', [9, 10, 25, 26]);
        $builder->whereNotIn('h.subhead', [0, 801, 817, 818, 819, 820, 848, 849, 850, 854]);
        $builder->where('h.main_supp_flag', 0);
        $builder->where('h.mainhead', 'M');
        $builder->where('h.roster_id', 0);
        $builder->where('h.brd_slno', 0);
        $builder->where('h.listorder !=', 32);
        $builder->where('h.board_type', 'S');
        $builder->where('h.next_dt <=', $formattedDate);
        $builder->where('mc.display', 'Y');
        $builder->where('rd.fil_no IS NULL');

        // Execute the query
        $query = $builder->get();

        // Check if there are results
        if ($query->getNumRows() > 0) {
            $row = $query->getRow();
            return $row->total;
        } else {
            return 0;
        }
    }




    public function getSingleJudgeFinalFreshCasesPool($next_dt)
    {
        // Format the date properly for the query
        $formattedDate = date('Y-m-d', strtotime($next_dt));

        // Using Query Builder to replicate the SQL query
        $builder = $this->db->table('main m');
        $builder->select('COUNT(DISTINCT m.diary_no) as total');

        // Joins
        $builder->join('heardt h', 'h.diary_no = m.diary_no', 'inner');
        $builder->join('mul_category mc', 'mc.diary_no = m.diary_no', 'inner');
        $builder->join('rgo_default rd', 'rd.fil_no = h.diary_no AND rd.remove_def = \'N\'', 'left'); // Use single quotes here

        // Where conditions
        $builder->where('m.c_status', 'P');
        $builder->groupStart()
            ->where("CAST(m.diary_no AS TEXT) = CAST(m.conn_key AS TEXT)") // Use CAST function
            ->orWhere('m.conn_key', '0')
            ->orWhere('m.conn_key', '')
            ->orWhere('m.conn_key IS NULL')
            ->groupEnd();
        $builder->whereNotIn('m.active_casetype_id', [9, 10, 25, 26]);
        $builder->whereNotIn('h.subhead', [0, 801, 817, 818, 819, 820, 848, 849, 850, 854]);
        $builder->where('h.main_supp_flag', 0);
        $builder->where('h.mainhead', 'M');
        $builder->where('h.roster_id', 0);
        $builder->where('h.brd_slno', 0);
        $builder->where('h.listorder', 32);
        $builder->where('h.board_type', 'S');
        $builder->where('h.next_dt <=', $formattedDate);
        $builder->where('mc.display', 'Y');
        $builder->where('rd.fil_no IS NULL');

        // Execute the query
        $query = $builder->get();

        // Check if there are results
        if ($query->getNumRows() > 0) {
            $row = $query->getRow();
            return $row->total;
        } else {
            return 0;
        }
    }

    public function getRecords($postData)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('main m');

        // Select specific columns from m table, and use STRING_AGG for child_case and cat1
        $builder->select("m.diary_no, m.active_casetype_id, m.case_grp, 
                          STRING_AGG(c.casecode::TEXT, ',') as child_case, 
                          h.*, 
                          l.purpose, 
                          STRING_AGG(c2.submaster_id::TEXT, ',') as cat1");

        $builder->join('heardt h', 'm.diary_no = h.diary_no', 'left');
        $builder->join('master.listing_purpose l', 'l.code = h.listorder', 'left');
        $builder->join('master.casetype c', 'm.active_casetype_id = c.casecode', 'left');
        $builder->join('mul_category c2', "c2.diary_no = h.diary_no AND c2.display = 'Y' AND c2.submaster_id != 331 AND c2.submaster_id IS NOT NULL", 'left');

        // Apply filters based on POST data
        if (isset($postData['is_nmd']) && $postData['is_nmd'] != '0') {
            $builder->where('h.is_nmd', $postData['is_nmd']);
        }

        if (!empty($postData['list_dt'])) {
            $listing_dt = date("Y-m-d", strtotime($postData['list_dt']));
            $builder->where("h.next_dt", $listing_dt);
        }

        if ($postData['civil_criminal'] != 'all') {
            $builder->where('m.case_grp', $postData['civil_criminal']);
        }

        if ($postData['bench'] != 'A') {
            $builder->where('h.board_type', $postData['bench']);
        }

        // Group by non-aggregated columns
        $builder->groupBy('m.diary_no, m.active_casetype_id, m.case_grp, h.diary_no, h.is_nmd, h.next_dt, h.board_type, l.purpose');

        // Execute and return the result set
        $query = $builder->get();
        return $query->getResultArray();
    }



    public function getCasesBk($data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('main m');

        // Build query
        $builder->select("
            CONCAT(COALESCE(m.reg_no_display,''),' @ ', SUBSTRING(CAST(m.diary_no AS TEXT), 1, LENGTH(CAST(m.diary_no AS TEXT))-4), '-', SUBSTRING(CAST(m.diary_no AS TEXT), -4)) AS case_no,
            t1.board_type,
            CONCAT(m.pet_name,' Vs. ',m.res_name) AS Cause_title,
            TO_CHAR(t1.next_dt, 'DD-MM-YYYY') AS Listed_On,
            COALESCE((SELECT CASE WHEN j.jtype = 'R' THEN 'Registrar' ELSE STRING_AGG(j.abbreviation, '#') END 
                     FROM master.judge j 
                     WHERE POSITION(','||jcode||',' IN ','||t1.judges||',') > 0 
                     GROUP BY j.jtype, j.abbreviation), '') AS Listed_Before,
            CASE 
                WHEN s.category_sc_old IS NOT NULL AND s.category_sc_old != '' AND s.category_sc_old::integer != 0 
                THEN '(' || s.category_sc_old || ')' || s.sub_name1 || '-' || s.sub_name4 
                ELSE '(' || s.subcode1 || s.subcode2 || ')' || s.sub_name1 || '-' || s.sub_name4 
            END AS subject_category,
            uhr.reason,
            t1.ent_dt,
            u.name AS username,
            u.empid
        ");

        // Sub-query for union
        $unionQuery = "
            SELECT diary_no, next_dt, mainhead, board_type, clno, roster_id, judges, usercode, ent_dt 
            FROM heardt 
            WHERE module_id = 10 AND " . $data['string_next_dt'] . " clno > 0 
            UNION 
            SELECT diary_no, next_dt, mainhead, board_type, clno, roster_id, judges, usercode, ent_dt 
            FROM last_heardt 
            WHERE module_id = 10 AND " . $data['string_next_dt'] . " clno > 0 AND (bench_flag = '' OR bench_flag IS NULL)";
        $builder->join("($unionQuery) t1", 'm.diary_no = t1.diary_no', 'inner');
        $builder->join('update_heardt_reason uhr', 'uhr.diary_no = m.diary_no AND DATE(uhr.ent_dt) = DATE(t1.ent_dt)', 'left');
        $builder->join('master.users u', 'u.usercode = t1.usercode', 'left');
        $builder->join('mul_category mcat', 'm.diary_no = mcat.diary_no AND mcat.display = \'Y\'', 'left');
        $builder->join('master.submaster s', 'mcat.submaster_id = s.id AND s.display = \'Y\'', 'left');

        // Correcting comparison of conn_key
        $builder->where("(CAST(m.diary_no AS TEXT) = CAST(m.conn_key AS TEXT) OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')");

        $builder->orderBy('t1.ent_dt DESC');
        // Optionally execute the query to get results
        return $builder->get()->getResultArray();
    }

    public function getCases($data)
    {
        $db = \Config\Database::connect();
        $return = [];
        $sql = "SELECT concat(COALESCE(m.reg_no_display, ''), ' @ ', concat(LEFT(CAST(m.diary_no AS TEXT),LENGTH(CAST(m.diary_no AS TEXT)) - 4), '-', SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3 FOR 4))) AS case_no, t1.board_type, concat(m.pet_name, ' Vs. ', m.res_name) AS Cause_title, TO_CHAR(t1.next_dt, 'DD-MM-YYYY') AS Listed_On, COALESCE(STRING_AGG(CASE WHEN judge.jtype = 'R' THEN 'Registrar' ELSE judge.abbreviation END, '#'), '') AS Listed_Before, CASE WHEN (s.category_sc_old IS NOT NULL AND s.category_sc_old != '' AND s.category_sc_old != '0') THEN concat('(', s.category_sc_old, ')', s.sub_name1, '-', s.sub_name4) ELSE concat('(', concat(s.subcode1, s.subcode2), ')', s.sub_name1, '-', s.sub_name4)END AS subject_category, uhr.reason, t1.ent_dt, u.name AS username, u.empid FROM (SELECT diary_no, next_dt, mainhead, board_type, clno, roster_id, judges, usercode, ent_dt FROM heardt WHERE module_id = 10 AND " . $data['string_next_dt'] . " clno > 0 UNION SELECT diary_no, next_dt, mainhead, board_type, clno, roster_id, judges, usercode, ent_dt FROM last_heardt WHERE module_id = 10 AND " . $data['string_next_dt'] . " clno > 0 AND (bench_flag = '' OR bench_flag IS NULL)) t1 INNER JOIN main m ON m.diary_no = t1.diary_no LEFT JOIN update_heardt_reason uhr ON uhr.diary_no = m.diary_no AND DATE(uhr.ent_dt) = DATE(t1.ent_dt) LEFT JOIN master.users u ON u.usercode = t1.usercode LEFT JOIN mul_category mcat ON m.diary_no = mcat.diary_no AND mcat.display = 'Y' LEFT JOIN master.submaster s ON mcat.submaster_id = s.id AND s.display = 'Y' LEFT JOIN master.judge judge ON judge.jcode = ANY (SELECT CAST(unnest(string_to_array(t1.judges, ',')) AS INTEGER)) WHERE (m.diary_no = CAST(NULLIF(m.conn_key, '') AS bigint) OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0') GROUP BY m.diary_no, t1.board_type, t1.next_dt, t1.ent_dt, m.pet_name, m.res_name, u.name, u.empid, s.category_sc_old, s.sub_name1, s.sub_name4, s.subcode1, s.subcode2, uhr.reason ORDER BY t1.ent_dt DESC";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $return = $query->getResultArray();
        }
        return $return;
    }

    //new
    public function getDiaryNo($ct, $cn, $cy)
    {
        return $this->db->query("SELECT 
            SUBSTR(diary_no, 1, LENGTH(diary_no) - 4) as dn, 
            SUBSTR(diary_no, -4) as dy 
            FROM {$this->table} 
            WHERE (SUBSTRING_INDEX(fil_no, '-', 1) = ? 
            AND CAST(?) AS UNSIGNED BETWEEN 
            (SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no, '-', 2), '-', -1)) 
            AND (SUBSTRING_INDEX(fil_no, '-', -1)) 
            AND IF(reg_year_mh=0, YEAR(fil_dt)=?, reg_year_mh=?)", [$ct, $cn, $cy, $cy])->getRowArray();
    }

    public function getCaseDetails1($diary_no)
    {
        return $this->db->table('heardt')
            ->select('diary_no_rec_date, fil_dt, lastorder, pet_name, res_name, c_status, listorder, next_dt, mainhead, subhead, clno, brd_slno, roster_id, judges, board_type, main_supp_flag, tentative_cl_dt, sitting_judges, c.remark, case_grp, b.is_nmd')
            ->join('brdrem c', 'main.diary_no = c.diary_no', 'left')
            ->where('main.diary_no', $diary_no)
            ->get()->getRowArray();
    }

    public function getCaseName($diary_no)
    {
        return $this->db->table('main')
            ->select('short_description')
            ->join('master.casetype', 'casetype_id = casecode', 'left')
            ->where('diary_no', $diary_no)
            ->get()
            ->getRowArray();
    }


    public function getNextDate($diary_no)
    {
        return $this->db->table('heardt')
            ->select('next_dt')
            ->where('diary_no', $diary_no)
            ->get()->getRowArray();
    }

    public function getVacationListReportsBk($from_Date, $to_Date, $type)
    {
        $from_Date = date('Y-m-d', strtotime($from_Date));
        $to_Date = date('Y-m-d', strtotime($to_Date));

        $builder = $this->db->table('vacation_advance_list val');
        $builder->join('main m', 'val.diary_no = m.diary_no', 'inner');

        // Left joins
        $builder->join('advocate adv', 'val.diary_no = adv.diary_no', 'left');
        $builder->join('master.bar b', 'adv.advocate_id = b.bar_id', 'left');
        $builder->join(
            'vacation_advance_list_advocate vala',
            'val.diary_no = vala.diary_no AND b.aor_code = vala.aor_code AND vala.vacation_list_year = EXTRACT(YEAR FROM NOW())',
            'left'
        );

        $builder->distinct();

        // Add select fields
        $builder->select([
            'm.diary_no',
            'val.is_fixed',
            "m.reg_no_display || ' @ ' || SUBSTR(CAST(m.diary_no AS TEXT), 1, LENGTH(CAST(m.diary_no AS TEXT)) - 4) || ' / ' || SUBSTR(CAST(m.diary_no AS TEXT), -4) AS case_no",
            "TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS filing_date",  // PostgreSQL's TO_CHAR instead of DATE_FORMAT
            "CONCAT(COALESCE(m.pet_name,''), ' Vs. ', COALESCE(m.res_name,'')) AS cause_title",
            "STRING_AGG(DISTINCT CONCAT(COALESCE(b.name,''), 
                '<font color=\"blue\" weight=\"bold\">(', 
                COALESCE(adv.pet_res,''), ')</font><font color=\"red\" weight=\"bold\">', 
                CASE WHEN vala.is_deleted = 't' THEN '(Declined)' ELSE '' END, 
                '</font>'), '<br/>') AS advocate",
            'val.is_deleted AS declined_by_admin',
            'val.updated_on'
        ]);

        // Use raw expression for vacation_list_year comparison
        $builder->where('val.vacation_list_year = EXTRACT(YEAR FROM NOW())', null, false);
        $builder->where('adv.display', 'Y');

        // Adjusted where clause to cast conn_key to bigint for comparison
        // $builder->where("(m.diary_no = m.conn_key::bigint OR m.conn_key IS NULL OR m.conn_key::bigint = 0)");

        $builder->where('b.isdead', 'N');
        $builder->where('b.if_aor', 'Y');

        if ($type == 'R') {
            $builder->where('val.is_deleted', 'f');
        } else {
            $builder->where('val.is_deleted', 't');
            $builder->where('DATE(val.updated_on) >=', $from_Date);
            $builder->where('DATE(val.updated_on) <=', $to_Date);
        }

        // Group by necessary fields
        $builder->groupBy([
            'm.diary_no',
            'val.is_fixed',
            'm.reg_no_display',
            'm.diary_no_rec_date',
            'm.pet_name',
            'm.res_name',
            'val.is_deleted',
            'val.updated_on'
        ]);

        // Uncomment these lines for order by if needed
        // $builder->orderBy("(CASE WHEN val.is_fixed = 'Y' THEN 1 ELSE 99 END)", 'ASC');
        // $builder->orderBy("COALESCE(NULLIF(val.conn_key, ''), val.diary_no)", 'ASC');
        //echo $builder->getCompiledSelect();die;
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function getCases1($listing_dt_from, $listing_dt, $is_nmd, $listorder, $mainhead, $order_by)
    {
        $listorder = f_selected_values($listorder);
        $builder = $this->db->table('main m');
        $builder->select('m.diary_no, c.short_description'); // Add other fields as necessary
        $builder->join('heardt h', 'm.diary_no = h.diary_no', 'left');
        $builder->join('master.casetype c', 'm.casetype_id = c.casecode', 'left');
        $builder->where("h.mainhead = '$mainhead'");
        //$builder->where("h.listorder IN ($listorder)");
        $builder->where("m.fil_dt BETWEEN '$listing_dt_from' AND '$listing_dt'");
        //$builder->where($is_nmd);
        $builder->orderBy($order_by);
        if (!empty($is_nmd)) {
            $builder->where($is_nmd);
        }
        if ($listorder != "all") {
            $builder->where("h.listorder IN ($listorder)");
        }
        return $builder->get();
    }

    public function getCasesUsingCon($listing_dt_from, $listing_dt, $conditions)
    {
        $builder = $this->builder();
        if ($conditions['is_nmd'] !== null) {
            $builder->where('h.is_nmd', $conditions['is_nmd']);
        }

        if ($conditions['from_year'] && $conditions['to_year']) {
            $builder->where("YEAR(m.fil_dt) BETWEEN {$conditions['from_year']} AND {$conditions['to_year']}");
        }

        if ($conditions['civil_criminal']) {
            $builder->where('m.case_grp', $conditions['civil_criminal']);
        }

        if ($conditions['bench'] && $conditions['bench'] !== "A") {
            $builder->where('h.board_type', $conditions['bench']);
        }
        $builder->orderBy('date(ia_filing_dt) IS NOT NULL', 'ASC');
        $builder->orderBy('date(ia_filing_dt)');

        return $builder->get()->getResult();
    }

    public function transferCasesBk($listing_dt_from, $listing_dt, $main_supp, $partno, $mainhead, $chked_jg_arry_to, $from_tran_jd_rs, $ucode, $from_tran_part_no_fr)
    {
        $db = \Config\Database::connect();
        $output = "";
        $db->transStart();
        $builder = $db->table('last_heardt');
        $builder->select('j.*, "X" as bench_flag');
        $builder->join('main m', 'm.diary_no = h.diary_no', 'inner');
        $subQuery = $db->table('heardt h')
            ->select('h.diary_no, h.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, h.board_type, h.usercode, h.ent_dt, h.module_id, h.mainhead_n, h.subhead_n, h.main_supp_flag, h.listorder, h.tentative_cl_dt, m.lastorder, h.listed_ia, h.sitting_judges, h.is_nmd')
            ->join('main m', 'm.diary_no = h.diary_no')
            ->where('h.next_dt', $listing_dt_from)
            ->where('h.mainhead', $mainhead)
            ->where('h.roster_id', $from_tran_jd_rs[1])
            ->where('h.clno', $from_tran_part_no_fr)
            ->where('h.brd_slno >', 0)
            ->where('h.board_type', $this->request->getPost('bench'));

        $builder->join("($subQuery) j", 'j.diary_no = last_heardt.diary_no', 'left');

        $builder->where("last_heardt.diary_no IS NULL");
        $builder->insert($builder->getCompiledInsert());
        $builder = $db->table('heardt h');
        $builder->join('main m', 'm.diary_no = h.diary_no');
        $builder->set('h.next_dt', $listing_dt);
        $builder->set('h.tentative_cl_dt', $listing_dt);
        $builder->set('h.clno', $partno);
        $builder->set('h.roster_id', $chked_jg_arry_to[1]);
        $builder->set('h.judges', $chked_jg_arry_to[0]);
        $builder->set('h.main_supp_flag', $main_supp);
        $builder->set('h.module_id', 24);
        $builder->set('h.usercode', $ucode);
        $builder->set('h.ent_dt', date('Y-m-d H:i:s'));
        $builder->where('m.diary_no = h.diary_no');
        $builder->where('h.next_dt', $listing_dt_from);
        $builder->where('h.mainhead', $mainhead);
        $builder->where('h.roster_id', $from_tran_jd_rs[1]);
        $builder->where('h.clno', $from_tran_part_no_fr);
        $builder->where('h.brd_slno >', 0);
        $builder->where('h.board_type', $this->request->getPost('bench'));
        $builder->where('m.diary_no >', 0);
        $res = $builder->update();
        if ($res) {
            $output = "Cases Transferred Successfully as it is.";
        } else {
            $output = "Error: Cases Not Transferred as it is.";
            $builder = $db->table('drop_note');
            $builder->set('cl_date', $listing_dt);
            $builder->set('clno', 'clno');
            $builder->set('diary_no', 'diary_no');
            $builder->set('roster_id', $chked_jg_arry_to[1]);
            $builder->set('nrs', 'nrs');
            $builder->set('usercode', $ucode);
            $builder->set('ent_dt', date('Y-m-d H:i:s'));
            $builder->set('mf', 'mf');
            $builder->set('part', $partno);

            $builder->where('cl_date', $listing_dt_from);
            $builder->where('part', $from_tran_part_no_fr);
            $builder->where('roster_id', $from_tran_jd_rs[1]);
            $builder->where('mf', $mainhead);
            $builder->where('display', 'Y');

            $builder->insert();
            $builder = $db->table('drop_note');
            $builder->set('display', 'N');
            $builder->where('cl_date', $listing_dt_from);
            $builder->where('part', $from_tran_part_no_fr);
            $builder->where('roster_id', $from_tran_jd_rs[1]);
            $builder->where('mf', $mainhead);
            $builder->where('display', 'Y');

            $builder->update();
            $db->transComplete();

            return $output;
        }
    }




    public function getCases12($year)
    {
        $cases_in_which_accused_in_jail = [
            94152012,
            233452012,
            241342012,
            86242011,
            49172013,
            36192014,
            92282014,
            310222014,
            144802015,
            154202015,
            259222015,
            176892016,
            217232016,
            430472016,
            188102017,
            97682018,
            115892018,
            179862018,
            458082018,
            58962019
        ];

        $cases_in_which_accused_in_jail_for_sql = implode(',', $cases_in_which_accused_in_jail);


        $sql = "SELECT DISTINCT u.name, tentative_section(m.diary_no) AS section_name,
                    h.*, m.active_fil_no, m.active_reg_year, m.casetype_id, 
                    m.active_casetype_id, m.ref_agency_state_id, m.reg_no_display, 
                    EXTRACT(YEAR FROM m.fil_dt) AS fil_year, m.fil_no, m.conn_key AS main_key, 
                    m.fil_dt, m.fil_no_fh, m.reg_year_fh AS fil_year_f,
                    m.mf_active, m.pet_name, m.res_name, pno, rno, 
                    m.diary_no_rec_date, l.purpose,
                    (CASE WHEN h.next_dt BETWEEN '2023-05-22' AND '2023-07-01' AND h.listorder = 4 THEN 1 
                          WHEN h.next_dt BETWEEN '2023-05-22' AND '2023-07-01' AND h.listorder = 5 THEN 2 
                          WHEN h.next_dt BETWEEN '2023-05-22' AND '2023-07-01' AND h.listorder = 7 THEN 3
                          WHEN h.next_dt BETWEEN '2023-05-22' AND '2023-07-01' AND h.listorder = 8 THEN 4 
                          WHEN h.diary_no IN ($cases_in_which_accused_in_jail_for_sql) THEN 5 
                          ELSE 99 END) AS display_order,
                    SUBSTRING(m.diary_no::text, -4) AS diary_no_suffix, 
                    LEFT(m.diary_no::text, LENGTH(m.diary_no::text) - 4) AS diary_no_prefix
                FROM main m 
                INNER JOIN heardt h ON h.diary_no = m.diary_no
                INNER JOIN master.listing_purpose l ON l.code = h.listorder
                INNER JOIN vacation_advance_list v ON v.diary_no = m.diary_no    
                LEFT JOIN master.users u ON u.usercode = m.dacode AND u.display = 'Y'
                LEFT JOIN master.usersection us ON us.id = u.section
                LEFT JOIN mul_category mc ON mc.diary_no= m.diary_no AND mc.display = 'Y' 
                WHERE (v.is_deleted = 'f') 
                AND m.c_status = 'P' 
                AND v.vacation_list_year = ? 
                AND (m.diary_no = m.conn_key::bigint OR m.conn_key::bigint = 0 OR m.conn_key IS NULL) 
                ORDER BY display_order, diary_no_suffix, diary_no_prefix";

        return $this->db->query($sql, [$year])->getResultArray();
    }



    public function getAdvocates($diaryNo)
    {
        $diaryNo = 422024;
        $sql = "
            SELECT 
                STRING_AGG(CASE WHEN pet_res = 'R' THEN name END, '' ORDER BY adv_type DESC, pet_res_no ASC) AS r_n,
                STRING_AGG(CASE WHEN pet_res = 'P' THEN name END, '' ORDER BY adv_type DESC, pet_res_no ASC) AS p_n,
                STRING_AGG(CASE WHEN pet_res = 'I' THEN name END, '' ORDER BY adv_type DESC, pet_res_no ASC) AS i_n,
                STRING_AGG(CASE WHEN pet_res = 'N' THEN name END, '' ORDER BY adv_type DESC, pet_res_no ASC) AS intervenor
            FROM (
                SELECT 
                    a.diary_no, 
                    b.name,
                    a.pet_res,
                    a.adv_type,
                    a.pet_res_no
                FROM advocate a
                LEFT JOIN master.bar b 
                    ON a.advocate_id = b.bar_id AND b.isdead != 'Y'
                WHERE a.diary_no = $diaryNo AND a.display = 'Y'
            ) AS subquery
        ";

        return $this->db->query($sql, [$diaryNo])->getRowArray();
    }





    public function getCategoryOld($diaryNo)
    {
        $diaryNo = 422024;
        $sql = "SELECT category_sc_old 
                FROM mul_category mc 
                INNER JOIN master.submaster s ON s.id = mc.submaster_id 
                WHERE mc.diary_no = $diaryNo
                LIMIT 1";

        return $this->db->query($sql, [$diaryNo])->getRowArray();
    }

    public function doAllocation($data)
    {
        $out_result = 1;
        $output = "";
        $total_case_listed = 0;
        $rslt_is_printed_from = 0;
        $bench_flag_field = "";
        $bench_flag_inp = "";
        $curdate = date('Y-m-d');
        $ThatTime = "15:30:00";

        $leftjoin_field = $leftjoin_coram_r = $leftjoin_kword = $leftjoin_docdetl = $leftjoin_act = $leftjoin_section = $sub_cat_arry = $p_listorder = $case_grp = $casetype = '';
        $case_from_to_yr  = $subhead_select   = $kword_selected  = $docdetl_selected =   $act_selected  = $section_selected = $case_to_trans1  = $main_supp_falg_sbqry = $from_part_sbqry = $from_tran_rs_id = $rgo_dft_left = $rgo_dft_qry = $reg_unreg = '';
        $per_jd_listed = 0;


        $output = '<table border="0" width="100%" style="text-align: left; background:#f6fbf0;" cellspacing=1>';
        if ($data['is_nmd'] == '0') {
            $is_nmd = "";
        } else {
            $is_nmd = " AND h.is_nmd = '" . $data['is_nmd'] . "'";
        }

        //$listorder = f_selected_values($data['listing_purpose']);
        $listing_purpose = isset($params['listing_purpose']) ? $params['listing_purpose'] : [];
        $listorder = f_selected_values($listing_purpose);

        if (!empty($listorder) && $listorder != "all") {
            $p_listorder = "AND h.listorder IN ($listorder)";
        }

        // $order_by = " IF(date(ia_filing_dt) is not null,1,2),date(ia_filing_dt),
        // IF(h.coram != 0, CAST(SUBSTRING_INDEX(h.coram,',',1) AS UNSIGNED),9999) ASC,
        // CAST(RIGHT(m.diary_no, 4) AS UNSIGNED) ASC , CAST(LEFT(m.diary_no,LENGTH(m.diary_no)-4) AS UNSIGNED) ASC";


        $order_by = "CASE WHEN h.coram ~ '^\d+$' THEN CAST(SPLIT_PART(h.coram, ',', 1) AS INTEGER) ELSE 9999 END,
                    CAST(SUBSTRING(m.diary_no::text, -4) AS INTEGER),
                    CAST(LEFT(m.diary_no::text, LENGTH(m.diary_no::text) - 4) AS INTEGER) ";

        if ($data['diary_reg'] == 'D') {
            if ($data['from_yr'] != "0" and $data['to_yr'] != "0") {
                $case_from_to_yr = "AND YEAR(m.diary_no_rec_date) BETWEEN ('" . $data['from_yr'] . "') AND ('" . $data['to_yr'] . "')";
            }
        } else {
            //     $order_by = "YEAR(m.diary_no_rec_date) DESC, LEFT(m.diary_no,LENGTH(m.diary_no)-4) DESC";
            if ($data['from_yr'] != "0" and $data['to_yr'] != "0") {
                $case_from_to_yr = "AND YEAR(m.fil_dt) BETWEEN ('" . $data['from_yr'] . "') AND ('" . $data['to_yr'] . "')";
            }
        }
        if ($data['civil_criminal'] == 'C' or $data['civil_criminal'] == 'R') {
            $case_grp = "AND m.case_grp = '" . $data['civil_criminal'] . "'";
        }
        if ($data['param_bench'] != "A") {
            $bench = "AND h.board_type = '" . $data['param_bench'] . "'";
        }
        $get_ia_date = ', NULL as ia_filing_dt';
        $subhead_arry = '';
        if ($data['mainhead'] != 'F') {
            $subhead_arry = f_selected_values($data['subhead']);

            if (in_array('817', @explode(',', $subhead_arry))) {
                $get_ia_date = ",(select min(doc.ent_dt) from docdetails doc inner join main mn on doc.diary_no=mn.diary_no
                left join conct ct on mn.diary_no=ct.conn_key where doc.doccode=8 and doc.doccode1=3 and doc.iastat='P' and doc.display='Y' and (ct.list='Y' or ct.list is null)
                and (mn.diary_no=m.diary_no or mn.conn_key=m.diary_no) and mn.c_status='P') as ia_filing_dt ";
            }
            if ($subhead_arry != "all") {
                $subhead_select = "AND h.subhead IN ($subhead_arry)";
                //subhead 817 early hearing
            }
        }


        $sub_cat =  f_selected_values($data['subject_cat']);
        if ($sub_cat != "all") {
            $sub_cat_arry = "AND c2.submaster_id IN ($sub_cat) ";
        }

        $kword_arry = f_selected_values($data['kword']);
        if ($kword_arry != "all") {
            $leftjoin_kword = "LEFT JOIN ec_keyword ek ON ek.diary_no = h.diary_no and ek.display = 'Y'";
            $kword_selected = "AND keyword_id IN ($kword_arry)";
        }
        $ia_arry = f_selected_values($data['ia']);
        if ($ia_arry != "all") {
            $leftjoin_docdetl = "LEFT JOIN docdetails dd ON dd.diary_no = h.diary_no";
            $docdetl_selected = "AND dd.doccode1 IN ($ia_arry) and dd.iastat = 'P' and dd.display = 'Y' and dd.doccode = '8'";
        }
        $ia_arry = f_selected_values($data['act']);
        if ($ia_arry != "all") {
            $leftjoin_act = "LEFT JOIN act_main at ON at.diary_no = h.diary_no";
            $act_selected = "AND at.display = 'Y' and at.act IN ($ia_arry)";
            if ($data['section']) {
                $leftjoin_section = "LEFT JOIN master.act_section ast ON ast.act_id = at.id";
                $section_selected = "AND ast.section LIKE '" . $data['section'] . "%' AND ast.display = 'Y'";
            }
        }

        $only_regs = "";
        if ($data['reg_unreg'] == 1) {
            $reg_unreg = " OR (m.active_fil_no = '' OR m.active_fil_no IS NULL) "; //with unregistred
        } else {
            $only_regs = " AND m.active_fil_no != '' AND m.active_fil_no IS NOT NULL";
        }
        $casetype_array = f_selected_values($data['case_type']);

        if ($casetype_array != "all") {
            //$casetype = "AND (TRIM(LEADING '0' FROM SUBSTRING_INDEX(m.fil_no,'-',1) ) IN (" . f_selected_values($data['case_type']) . ") $reg_unreg )";
            $casetype = "AND (COALESCE(NULLIF(TRIM(LEADING '0' FROM split_part(m.fil_no, '-', 1)), '')::INTEGER, 0) IN (" . f_selected_values($data['case_type']) . ")  $reg_unreg )";
        }

        //$listing_dt_from = date("Y-m-d", strtotime($listing_dt_from));  
        //$listing_dt = date("Y-m-d", strtotime($listing_dt));  
        $listing_dt_from = !empty($data['listing_dt_from']) ? date('Y-m-d', strtotime($data['listing_dt_from'])) : $data['listing_dt_from'];
        $listing_dt = !empty($data['listing_dt']) ? date('Y-m-d', strtotime($data['listing_dt'])) : $data['listing_dt'];

        $main_supp = $data['main_supp'];
        $md_name = $data['md_name'];
        $partno = $data['partno'];

        $chked_jud = rtrim($data['get_chked_jud'], "JG");

        $chked_jg_arry = explode("JG", $chked_jud);
        $count_j = count($chked_jg_arry);

        $coram_sele_or_null = $coram_sele = "";
        if ($md_name == 'allocation') {

            //do only when board type J
            if (($data['param_bench'] == "J" or $data['param_bench'] == "S" or $data['param_bench'] == "R") and $subhead_arry != "817") {
                $roster_selected = $chked_jud;
                $explode_rs = explode("JG", $roster_selected);
                for ($i = 0; $i < (count($explode_rs)); $i++) {
                    $explode_rs_jg = explode("|", $explode_rs[$i]);
                    $coram_sele .= $explode_rs_jg[0] . ",";
                }

                if (rtrim($coram_sele, ",") == '') {
                    $cor_slse = "0";
                } else {
                    $cor_slse = rtrim($coram_sele, ",");
                }

                if ($data['param_bench'] == 'R') {
                    $coram_sele_or_null = " AND (cr.jud IS NULL OR cr.jud IN ($cor_slse)) ";
                    $leftjoin_coram_r = " LEFT JOIN coram cr ON cr.diary_no = h.diary_no AND cr.board_type = 'R' AND cr.to_dt = '0000-00-00' AND cr.display = 'Y'";
                    $leftjoin_field = " cr.jud as r_coram, ";
                } else {
                    //$coram_sele_or_null = " AND (h.coram IN ($cor_slse) or h.coram = 0 or h.coram is null or h.coram = '' ) ";
                    //$coram_sele_or_null = '';
                    $coram_sele_or_null = " AND (h.coram IN ('$cor_slse') or h.coram = '0' or h.coram is null ) ";     
                }
            }

            $md_module_id = "7";
            $noc = $data['get_noc'];

            $tot_to_be_list = $noc * $count_j;

            $case_to_trans1 = "";
            /*$main_supp_falg_sbqry = "AND m.c_status = 'P' AND h.main_supp_flag = '0' AND CASE WHEN l.fx_wk = 'F' THEN 
            if($main_supp = 2,h.next_dt = '$listing_dt', (h.next_dt = '$listing_dt' OR h.next_dt <= CURRENT_DATE) )
            ELSE h.next_dt <= '$listing_dt' END ";*/

            $main_supp_falg_sbqry = "AND m.c_status = 'P' AND h.main_supp_flag = '0' AND (
            (l.fx_wk = 'F' AND (h.next_dt = '$listing_dt' OR h.next_dt <= CURRENT_DATE))
            OR (l.fx_wk <> 'F' AND h.next_dt <= '$listing_dt')) ";

            $from_part_sbqry = "";
        }


        if ($md_name == 'transfer') {
            $md_module_id = "8";
            $case_to_trans = rtrim($data['chk_tr'], ",");
            $expl_chk_tr = explode(",", $case_to_trans);
            $case_to_trans1 = "AND h.diary_no IN ($case_to_trans)";
            $tot_to_be_list = count($expl_chk_tr);
            $noc = ceil($tot_to_be_list / $count_j); //next integer value
            $main_supp_falg_sbqry = " AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2) ";
            $from_part_sbqry = "and h.clno = '" . $data['from_tran_partno'] . "'";
            $from_tran_jd_rs = explode("|", $data['get_from_tran_jd_rs']);
            $expl_from_tran_jd_rs = explode(",", $from_tran_jd_rs[0]);
            $l_jg_count = count($expl_from_tran_jd_rs); //to judges count            
            $from_tran_rs_id = "and h.roster_id = '" . $from_tran_jd_rs[1] . "'";
            $from_tran_part_no_fr = $data['from_tran_partno'];
            //bench flag

            $listing_dt_from . "," . $from_tran_part_no_fr . "," . $data['mainhead'] . "," . $from_tran_jd_rs[1];
            $rslt_is_printed_from = f_cl_is_printed($listing_dt_from, $from_tran_part_no_fr, $data['mainhead'], $from_tran_jd_rs[1]);
            //$listing_dt_from < $curdate AND 
            if ($rslt_is_printed_from == 0 and $listing_dt_from >= $curdate) {
                $bench_flag_field = ", bench_flag";
                $bench_flag_inp = ", 'X'";
            }
            if ($rslt_is_printed_from > 0 and $listing_dt_from < $curdate) {
                $rslt_is_printed_from = 0;
            }
            if ($rslt_is_printed_from > 0 and $curdate == $listing_dt_from) {
                // AND time() >= strtotime($ThatTime)
                $rslt_is_printed_from = 0;
            }
            echo "<br/>";
        }

        $j_c = 0; //counter


        //check list printed or not
        $rslt_is_printed = 0; //default

        if ($partno > 0) {
            if ($noc < 250) {
                for ($j = 0; $j < $count_j; ++$j) {
                    $chked_jg_arry[$j];
                    $chk_jg_loop = explode("|", $chked_jg_arry[$j]);
                    $l_rosid = $chk_jg_loop[1];
                    if ($md_name == 'transfer') {
                        $l_jg_count;
                    } else {
                        if ($data['param_bench'] != "R") {
                            $l_jg_count = (count(explode(",", $chk_jg_loop[0])) + 1); //s_j               
                        } else {
                            $l_jg_count = 1;
                        }
                    }

                    if ($rslt_is_printed_from == 0) {

                        $rslt_is_printed = f_cl_is_printed($listing_dt, $partno, $data['mainhead'], $l_rosid);

                        if ($rslt_is_printed == 0) {

                            if ($data['param_bench'] == 'J' or $data['param_bench'] == 'S') {
                                $rgo_dft_left = " LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N' ";
                                $rgo_dft_qry = " AND rd.fil_no IS NULL ";
                                $mul_cat_qry = " c2.diary_no IS NOT NULL AND ";
                            }

                            //verify cause list printed or not
                            $qry = "SELECT $leftjoin_field c.short_description  $get_ia_date, m.fil_no, m.fil_dt, EXTRACT(YEAR FROM m.fil_dt) AS fil_year, m.lastorder, m.             diary_no_rec_date, h.*, l.purpose, STRING_AGG(c2.submaster_id :: TEXT, ',') AS cat1
                                    FROM main m
                                    LEFT JOIN heardt h ON m.diary_no = h.diary_no 
                                    LEFT JOIN master.listing_purpose l ON l.code = h.listorder            
                                    LEFT JOIN master.casetype c ON m.casetype_id = c.casecode
                                    LEFT JOIN mul_category c2 ON c2.diary_no = h.diary_no AND c2.display = 'Y' and c2.submaster_id != 331 and c2.submaster_id IS NOT NULL     
                                    $rgo_dft_left
                                    $leftjoin_coram_r
                                    $leftjoin_kword    
                                    $leftjoin_docdetl    
                                    $leftjoin_act    
                                    $leftjoin_section    
                                    WHERE $mul_cat_qry l.display = 'Y' $sub_cat_arry $is_nmd $coram_sele_or_null 
                                    $rgo_dft_qry
                                    $p_listorder            
                                    $case_grp
                                        $only_regs
                                    $casetype   
                                    $bench    
                                    $case_from_to_yr  
                                    $subhead_select    
                                    $kword_selected  
                                    $docdetl_selected    
                                    $act_selected    
                                    $section_selected    
                                    $case_to_trans1 
                                    $main_supp_falg_sbqry 
                                    $from_part_sbqry    
                                    $from_tran_rs_id    
                                    AND (
                                    --m.diary_no = m.conn_key:: BIGINT 
                                    --OR m.conn_key:: BIGINT=0 
                                    m.diary_no = CAST(NULLIF(m.conn_key, '') AS BIGINT)
                                    OR m.conn_key = '0'
                                    OR m.conn_key = '' OR m.conn_key IS NULL) AND h.mainhead = '" . $data['mainhead'] . "' 
                                    GROUP BY h.diary_no,c.short_description,m.fil_no,m.fil_dt,m.lastorder,m.diary_no_rec_date,l.purpose,m.diary_no ORDER BY  $order_by LIMIT $noc 
                                    ";
                            $query = $this->db->query($qry);
                            if ($query->getNumRows() >= 1) {
                                $results = $query->getResultArray();

                                $qry_inst = "INSERT INTO last_heardt (diary_no,conn_key,next_dt,mainhead,subhead,clno,brd_slno,roster_id,judges,coram,board_type,usercode,ent_dt,module_id, mainhead_n, subhead_n,
                                                main_supp_flag,listorder,tentative_cl_dt,lastorder,listed_ia,sitting_judges,is_nmd $bench_flag_field) 
                                                SELECT j.diary_no ,j.conn_key,j.next_dt,j.mainhead,j.subhead,j.clno,j.brd_slno,j.roster_id,j.judges,j.coram,
                                                CAST(j.board_type AS last_heardt_board_type),
                                                j.usercode,j.ent_dt,j.module_id, j.mainhead_n, j.subhead_n,
                                                j.main_supp_flag,j.listorder,j.tentative_cl_dt,j.lastorder,j.listed_ia,j.sitting_judges, j.is_nmd 
                                                $bench_flag_inp                      
                                                FROM (SELECT h.diary_no $get_ia_date,h.conn_key,h.next_dt,h.mainhead,h.subhead,h.clno,h.brd_slno,h.roster_id,h.judges,h.coram,h.board_type,h.usercode,h.ent_dt,h.module_id, h.mainhead_n, h.subhead_n,
                                                h.main_supp_flag,h.listorder,h.tentative_cl_dt,m.lastorder,h.listed_ia,h.sitting_judges, h.is_nmd
                                                    FROM main m
                                                    LEFT JOIN heardt h ON m.diary_no = h.diary_no 
                                                    LEFT JOIN master.listing_purpose l ON l.code = h.listorder
                                                    LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                                                    LEFT JOIN master.casetype c ON m.casetype_id = c.casecode
                                                    LEFT JOIN mul_category c2 ON c2.diary_no = h.diary_no AND c2.display = 'Y'     
                                                    $leftjoin_coram_r
                                                    $leftjoin_kword    
                                                    $leftjoin_docdetl    
                                                    $leftjoin_act    
                                                    $leftjoin_section    
                                                    WHERE $mul_cat_qry rd.fil_no IS NULL AND l.display = 'Y' $sub_cat_arry $is_nmd $coram_sele_or_null
                                                    $p_listorder            
                                                    $case_grp
                                                        $only_regs
                                                    $casetype   
                                                    $bench    
                                                    $case_from_to_yr  
                                                    $subhead_select    
                                                    $kword_selected  
                                                    $docdetl_selected    
                                                    $act_selected    
                                                    $section_selected    
                                                    $case_to_trans1
                                                    $main_supp_falg_sbqry    
                                                        AND (
                                                        --m.diary_no = m.conn_key:: BIGINT 
                                                        --OR m.conn_key:: BIGINT=0 
                                                        m.diary_no = CAST(NULLIF(m.conn_key, '') AS BIGINT)
                                                        OR m.conn_key = '0'
                                                        OR m.conn_key = '' OR m.conn_key IS NULL) AND h.mainhead = '" . $data['mainhead'] . "'
                                                    GROUP BY h.diary_no,m.lastorder,m.diary_no ORDER BY  $order_by LIMIT $noc ) j                
                                                    LEFT JOIN last_heardt l ON j.diary_no = l.diary_no AND l.next_dt = j.next_dt AND l.listorder = j.listorder AND l.mainhead = j.mainhead 
                                                    AND l.subhead = j.subhead AND l.judges = j.judges AND l.roster_id = j.roster_id AND l.clno = j.clno 
                                                    AND l.main_supp_flag = j.main_supp_flag AND (l.bench_flag = '' OR l.bench_flag IS NULL) WHERE l.diary_no IS NULL";

                                //$result_inst = $db->query($qry_inst);
                                $result_inst = $this->db->query($qry_inst);


                                foreach ($results as $row) {
                                    $chk_x = explode("|", $chked_jg_arry[$j_c]);
                                    $chk_jud_id = $chk_x[0];
                                    $chk_roster_id = $chk_x[1];
                                    if ($data['param_bench'] == 'R') {
                                        $coram = $row['r_coram'];
                                    } else {
                                        $coram = $row['coram'];
                                    }
                                    $q_diary_no = $row['diary_no'];
                                    $q_subhead = $row['subhead'];
                                    $q_next_dt = $listing_dt;
                                    $q_clno = $partno;
                                    $q_brd_slno = "123";
                                    $q_roster_id = $chk_roster_id;
                                    $q_judges = $chk_jud_id;
                                    $q_usercode = $data['ucode'];
                                    //$q_module_id = "7"; 
                                    $q_main_supp_flag = $main_supp;

                                    //$dairy_with_conn_k = f_cl_conn_key($q_diary_no);

                                    if ($row['diary_no'] == $row['conn_key']) {
                                        $dairy_with_conn_k = f_cl_conn_key($q_diary_no);
                                    } else {
                                        $dairy_with_conn_k =  $q_diary_no;
                                    }
                                    $cat1 = $row['cat1'];
                                    //BAIL MATTERS START TO CHECK SAME CRIME
                                    $same_crime_verify = "0";
                                    if ((strpos($cat1, '173') !== false) or (strpos($cat1, '174') !== false) and ($row['board_type'] == 'J' or $row['board_type'] == 'S')) {
                                        $same_crime_verify = f_cl_same_crime($q_diary_no, $q_next_dt, $chk_roster_id);
                                    }
                                    $coram_verify = "0";
                                    /*testing */
                                    //echo "<br/> diary_no : ".$row['diary_no']." coram : ".$coram;
                                    if (($row['board_type'] == "J" or $row['board_type'] == "S" or $row['board_type'] == "R") and $q_subhead != "817") {
                                        if ($coram != 0 and $coram != null) {
                                            $coram_verify = "1";

                                            $chk_jud_id;

                                            $coram;
                                            if ($chk_jud_id != $coram) {

                                                $judges_explod = explode(",", $chk_jud_id);
                                                $coram_explod = explode(",", $coram);

                                                if ($judges_explod[0] == $coram_explod[0] and $judges_explod[0] != "") {
                                                    $chk_jud_id = $judges_explod[0];
                                                    $coram_verify = "0";
                                                } else if (isset($judges_explod[1]) && ($judges_explod[1] == $coram_explod[0]) and ($judges_explod[1] != "")) {
                                                    $chk_jud_id = $judges_explod[1];
                                                    $coram_verify = "0";
                                                } else if (isset($judges_explod[2]) && ($judges_explod[2] == $coram_explod[0]) and ($judges_explod[2] != "")) {
                                                    $chk_jud_id = $judges_explod[2];
                                                    $coram_verify = "0";
                                                }
                                            } else {
                                                $coram_verify = "0";
                                            }
                                        }
                                    }
                                    $same_vehicle_verify = "0";

                                    if ($row['board_type'] != "R") {
                                        $ntl_judge_verify = f_cl_ntl_judge($dairy_with_conn_k, $chk_jud_id);
                                        $ntl_judge_dept_verify = f_cl_ntl_jud_dept($dairy_with_conn_k, $chk_jud_id);
                                        $not_before_verify = f_cl_not_before($dairy_with_conn_k, $chk_jud_id);
                                        $same_vehicle_verify = f_cl_same_vehicle($q_diary_no, $q_next_dt, $chk_roster_id);

                                        //$coram_verify  = $ntl_judge_verify = $ntl_judge_dept_verify = $not_before_verify = $same_vehicle_verify = 0;
                                      
                                    }
                                    //if return 1 than dont list before that judge
                                    if ($ntl_judge_verify == 1) {
                                        $output .= "<tr><td class='class_red'>" . $out_result++ . ".</td><td class='class_red'>" . $q_diary_no . " Not to go AOR Before " . f_get_judge_names_inshort($chk_jud_id) . "</td></tr>";
                                    } else if ($ntl_judge_dept_verify == 1) {
                                        $output .= "<tr><td class='class_red'>" . $out_result++ . ".</td><td class='class_red'>" . $q_diary_no . " Not to go Department Before " . f_get_judge_names_inshort($chk_jud_id) . "</td></tr>";
                                    } else if ($coram_verify == 1) {
                                        $output .= "<tr><td class='class_red'>" . $out_result++ . ".</td><td class='class_red'>" . $q_diary_no . " for " . f_get_judge_names_inshort($coram) . " Coram Not Matched Before " . f_get_judge_names_inshort($chk_jud_id) . "</td></tr>";
                                    } else if ($not_before_verify == 1) {
                                        $output .= "<tr><td class='class_red'>" . $out_result++ . ".</td><td class='class_red'>" . $q_diary_no . " Not / Before " . f_get_judge_names_inshort($chk_jud_id) . "</td></tr>";
                                    } else if ($same_vehicle_verify != "0") {
                                        $scv_expl = explode("|", $same_crime_verify);
                                        $output .= "<tr><td class='class_red'>" . $out_result++ . ".</td><td class='class_red'>Same Vehicle Diary No. " . $scv_expl[0] . " already Listed Before " . $scv_expl[1] . "</td></tr>";
                                    } else {
                                        //echo "<br/> Listed before : ".$q_judges;
                                        $total_case_listed += f_heardt_cl_update($q_diary_no, $q_next_dt, $q_clno, $q_brd_slno, $q_roster_id, $q_judges, $q_usercode, $md_module_id, $q_main_supp_flag, $data['mainhead'], $cat1);
                                        $per_jd_listed++;
                                    }

                                    if ($per_jd_listed == $noc) {
                                        $j_c++;
                                        $per_jd_listed = 0;
                                        //echo "<br/> ddddd <br/>";
                                    }
                                    if ($j_c == $count_j)
                                        $j_c = 1;
                                }

                                //start reshuffle
                                for ($i = 0; $i < $count_j; $i++) {
                                    $chk_x = explode("|", $chked_jg_arry[$i]);
                                    $chk_jud_id = $chk_x[0];
                                    $chk_rs_id = $chk_x[1];
                                    $mf = $data['mainhead'];
                                    f_cl_reshuffle($listing_dt, $chk_jud_id, $mf, $partno, $chk_rs_id);
                                }
                                //end reshuffle
                                if ($total_case_listed == 0)
                                    $output .= "<tr><td class='class_red'>" . $out_result++ . ".</td><td class='class_red'>TOTAL CASES LISTED $total_case_listed</td></tr>";
                                else
                                    $output .= "<tr><td class='class_green'>" . $out_result++ . ".</td><td class='class_green'>TOTAL CASES LISTED $total_case_listed</td></tr>";
                                $total_case_listed = "0";
                            } else {
                                $output .= "<tr><td class='class_red align_center'>NO RECORDS FOUND</td></tr>";
                            }
                        } //end if cl printed        
                        else {
                            $output .= "<tr><td class='class_red'>" . $out_result++ . ".</td><td class='class_red'>YOU CAN NOT ALLOT CASES IN PART $partno. BECAUSE PART $partno FINALIZED.</td></tr>";
                        }
                    } //end if cl printed from 
                    else {
                        $output .= "<tr><td class='class_red'>" . $out_result++ . ".</td><td class='class_red'>YOU CAN NOT ALLOT CASES IN PART $from_tran_part_no_fr. BECAUSE PART $from_tran_part_no_fr FINALIZED.</td></tr>";
                    }
                }    //end of for loop
            } //end of noc more than 250
            else {
                $output .= "<tr><td class='class_red'>" . $out_result++ . ".</td><td class='class_red'>DON'T ALLOT MORE THAN 250 CASES PER JUDGE AT A TIME.</td></tr>";
            }
        } else {
            $output .= "<tr><td class='class_red'>" . $out_result++ . ".</td><td class='class_red'>YOU CAN NOT ALLOT CASES IN PART $partno</td></tr>";
        }
        $output .= '</table>';
        return $output;
        //echo '</table>';
    }


    public function getSingleJudgeRoster($next_dt)
    {
        // $next_dt = '2023-01-02';
        $builder = $this->db->table('master.roster r');
        $builder->select('sjn.day_type, r.id, j.jcode as judge_code, 
                      j.first_name || \' \' || j.sur_name as judge_name, 
                      rb.bench_no, mb.abbr, r.courtno, mb.board_type_mb');

        $builder->join('master.roster_bench rb', 'rb.id = r.bench_id', 'left');
        $builder->join('master.master_bench mb', 'mb.id = rb.bench_id', 'left');
        $builder->join('master.roster_judge rj', 'rj.roster_id = r.id', 'left');
        $builder->join('master.judge j', 'j.jcode = rj.judge_id', 'left');
        $builder->join('master.single_judge_nominate sjn', 'sjn.jcode = j.jcode AND sjn.is_active = 1 AND (sjn.to_date IS NULL OR sjn.to_date = \'1970-01-01\')', 'left');

        $builder->where('j.is_retired !=', 'Y');
        $builder->where('mb.board_type_mb', 'S');
        $builder->where('j.display', 'Y');
        $builder->where('rj.display', 'Y');
        $builder->where('rb.display', 'Y');
        $builder->where('mb.display', 'Y');
        $builder->where('r.display', 'Y');
        $builder->where('r.m_f', '1');
        $builder->where('r.from_date', $next_dt);
        $builder->groupBy('sjn.day_type');
        $builder->groupBy('r.id');
        $builder->groupBy('j.jcode');
        $builder->groupBy('j.first_name');
        $builder->groupBy('j.sur_name');
        $builder->groupBy('rb.bench_no');
        $builder->groupBy('mb.abbr');
        $builder->groupBy('r.courtno');
        $builder->groupBy('mb.board_type_mb');

        // Order results
        $builder->orderBy('r.courtno');
        $builder->orderBy('r.id');
        $builder->orderBy('j.jcode');

        // Execute the query
        $query = $builder->get();

        return $query->getResultArray();
    }

    public function getSingleJudgeFinalAllocationCount($next_dt)
    {
        $builder = $this->db->table('heardt h');
        $builder->select('h.roster_id, COUNT(h.diary_no) as total');
        $builder->select("SUM(CASE WHEN l.code = 4 OR l.code = 5 OR l.code = 7 THEN 1 ELSE 0 END) as fixed_date_listed", false);
        $builder->select("SUM(CASE WHEN l.code != 32 AND l.code != 4 AND l.code != 5 AND l.code != 7 THEN 1 ELSE 0 END) as other_listed", false);
        $builder->select("SUM(CASE WHEN l.code = 32 THEN 1 ELSE 0 END) as fresh_listed", false);
        $builder->join('main m', 'h.diary_no = m.diary_no', 'inner');
        $builder->join('master.listing_purpose l', 'l.code = h.listorder', 'inner');
        $builder->where('l.display', 'Y');
        $builder->where('h.next_dt', $next_dt);
        $builder->where('h.board_type', 'S');
        $builder->where('h.mainhead', 'M');
        $builder->whereIn('h.main_supp_flag', [1, 2]);
        $builder->groupStart()
            ->where('h.diary_no = h.conn_key')
            ->orWhere('h.conn_key', 0)
            ->orWhere('h.conn_key IS NULL')
            ->groupEnd();

        $builder->groupBy('h.roster_id');
        $query = $builder->get();
        return $query->getResultArray();
    }





    public function transfer_without_coram_check($post_data, $ucode)
    {
        $out_result = 1;
        $output = "";
        $total_case_listed = 0;
        $rslt_is_printed_from = 0;
        $bench_flag_field = "";
        $bench_flag_inp = "";
        $curdate = date('Y-m-d');
        $ThatTime = "15:30:00";

        $leftjoin_field = $leftjoin_coram_r = $leftjoin_kword = $leftjoin_docdetl = $leftjoin_act = $leftjoin_section = $sub_cat_arry = $p_listorder = $case_grp = $casetype = '';
        $case_from_to_yr  = $subhead_select   = $kword_selected  = $docdetl_selected =   $act_selected  = $section_selected = $case_to_trans1  = $main_supp_falg_sbqry = $from_part_sbqry = $from_tran_rs_id = $rgo_dft_left = $rgo_dft_qry = $reg_unreg = '';
        $per_jd_listed = 0;

        $output = '<table border="0" width="100%" style="text-align: left; background:#f6fbf0;" cellspacing=1>';
        if ($post_data['is_nmd'] == '0') {
            $is_nmd = "";
        } else {
            $is_nmd = " AND h.is_nmd = '" . $post_data['is_nmd'] . "'";
        }

        $listing_dt_from = $post_data['list_dt_from'];
        $listing_dt = $post_data['list_dt'];
        $sitting_judges = $post_data['sitting_judges']; //no. of judges to be sit
        $listorder = f_selected_values($post_data['listing_purpose']);
        $mainhead = $post_data['mainhead'];
        if ($listorder != "all") {
            $p_listorder = "AND h.listorder IN ($listorder)";
        }
        $order_by = "CAST(RIGHT(m.diary_no::text, 4) AS INTEGER) ASC, CAST(LEFT(m.diary_no::text,LENGTH(m.diary_no::text)-4) AS INTEGER) ASC";
        if ($post_data['diary_reg'] == 'D') {
            if ($post_data['from_yr'] != "0" and $post_data['to_yr'] != "0") {
                $case_from_to_yr = "AND YEAR(m.diary_no_rec_date) BETWEEN ('" . $post_data['from_yr'] . "') AND ('" . $post_data['to_yr'] . "')";
            }
        } else {
            if ($post_data['from_yr'] != "0" and $post_data['to_yr'] != "0") {
                $case_from_to_yr = "AND YEAR(m.fil_dt) BETWEEN ('" . $post_data['from_yr'] . "') AND ('" . $post_data['to_yr'] . "')";
            }
        }
        if ($post_data['civil_criminal'] == 'C' or $post_data['civil_criminal'] == 'R') {
            $case_grp = "AND m.case_grp = '" . $post_data['civil_criminal'] . "'";
        }
        if ($post_data['civil_criminal'] == 'C' or $post_data['civil_criminal'] == 'R') {
            $case_grp = "AND m.case_grp = '" . $post_data['civil_criminal'] . "'";
        }
        if ($post_data['civil_criminal'] == 'C' or $post_data['civil_criminal'] == 'R') {
            $case_grp = "AND m.case_grp = '" . $post_data['civil_criminal'] . "'";
        }
        if ($post_data['bench'] != "A") {
            $bench = "AND h.board_type = '" . $post_data['bench'] . "'";
        }
        if ($post_data['mainhead'] != 'F') {
            $subhead_arry = f_selected_values($post_data['subhead']);
            if ($subhead_arry != "all") {
                $subhead_select = "AND h.subhead IN ($subhead_arry)";
            }
        }
        $sub_cat =  f_selected_values($post_data['subject_cat']);
        if ($sub_cat != "all") {
            $sub_cat_arry = "AND c2.submaster_id IN ($sub_cat) ";
        }

        $kword_arry = f_selected_values($post_data['kword']);
        if ($kword_arry != "all") {
            $leftjoin_kword = "LEFT JOIN ec_keyword ek ON ek.diary_no = h.diary_no and ek.display = 'Y'";
            $kword_selected = "AND keyword_id IN ($kword_arry)";
        }
        $ia_arry = f_selected_values($post_data['ia']);
        if ($ia_arry != "all") {
            $leftjoin_docdetl = "LEFT JOIN docdetails dd ON dd.diary_no = h.diary_no";
            $docdetl_selected = "AND dd.doccode1 IN ($ia_arry) and dd.iastat = 'P' and dd.display = 'Y' and dd.doccode = '8'";
        }
        $ia_arry = f_selected_values($post_data['act']);
        if ($ia_arry != "all") {
            $leftjoin_act = "LEFT JOIN act_main at ON at.diary_no = h.diary_no";
            $act_selected = "AND at.display = 'Y' and at.act IN ($ia_arry)";
            if ($post_data['section']) {
                $leftjoin_section = "LEFT JOIN act_section ast at ON ast.act_id = at.id";
                $section_selected = "AND ast.section LIKE '" . $post_data['section'] . "%' AND ast.display = 'Y'";
            }
        }

        $only_regs = "";
        if ($post_data['reg_unreg'] == 1) {
            $reg_unreg = " OR (m.active_fil_no = '' OR m.active_fil_no IS NULL) "; //with unregistred
        } else {
            $only_regs = " AND m.active_fil_no != '' AND m.active_fil_no IS NOT NULL";
        }
        $casetype_array = f_selected_values($post_data['case_type']);
        if ($casetype_array != "all") {
            $casetype = "AND (TRIM(LEADING '0' FROM SUBSTRING_INDEX(m.fil_no,'-',1) ) IN (" . f_selected_values($post_data['case_type']) . ") $reg_unreg )";
        }
        $listing_dt_from = date("Y-m-d", strtotime($listing_dt_from));
        $listing_dt = date("Y-m-d", strtotime($listing_dt));
        $main_supp = $post_data['main_supp'];
        $md_name = $post_data['md_name'];
        $partno = $post_data['partno'];

        $chked_jud = rtrim($post_data['chked_jud'], "JG");

        $chked_jg_arry = explode("JG", $chked_jud);
        $count_j = count($chked_jg_arry);

        $coram_sele_or_null = "";

        if ($md_name == 'transfer') {
            $md_module_id = "8";
            $case_to_trans = rtrim($post_data['chk_tr'], ",");
            $expl_chk_tr = explode(",", $case_to_trans);
            $case_to_trans1 = "AND h.diary_no IN ($case_to_trans)";
            $tot_to_be_list = count($expl_chk_tr);
            $noc = ceil($tot_to_be_list / $count_j); //next integer value
            $main_supp_falg_sbqry = " AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2) ";
            $from_part_sbqry = "and h.clno = '" . $post_data['from_tran_partno'] . "'";
            $from_tran_jd_rs = explode("|", $post_data['from_tran_jd_rs']);
            $expl_from_tran_jd_rs = explode(",", $from_tran_jd_rs[0]);
            $l_jg_count = count($expl_from_tran_jd_rs); //to judges count
            $from_tran_rs_id = "and h.roster_id = '" . $from_tran_jd_rs[1] . "'";
            $from_tran_part_no_fr = $post_data['from_tran_partno'];
            //bench flag

            $listing_dt_from . "," . $from_tran_part_no_fr . "," . $mainhead . "," . $from_tran_jd_rs[1];
            $rslt_is_printed_from = f_cl_is_printed($listing_dt_from, $from_tran_part_no_fr, $mainhead, $from_tran_jd_rs[1]);
            //$listing_dt_from < $curdate AND
            if ($rslt_is_printed_from == 0 and $listing_dt_from >= $curdate) {
                $bench_flag_field = ", bench_flag";
                $bench_flag_inp = ", 'X'";
            }
            if ($rslt_is_printed_from > 0 and $listing_dt_from < $curdate) {
                $rslt_is_printed_from = 0;
            }
            if ($rslt_is_printed_from > 0 and $curdate == $listing_dt_from) {
                // AND time() >= strtotime($ThatTime)
                $rslt_is_printed_from = 0;
            }
            echo "<br/>";
        }

        $j_c = 0; //counter
        //check list printed or not
        $rslt_is_printed = 0; //default

        if ($partno > 0) {
            if ($noc < 250) {
                for ($j = 0; $j < $count_j; ++$j) {
                    $chked_jg_arry[$j];

                    $chk_jg_loop = explode("|", $chked_jg_arry[$j]);
                    $l_rosid = $chk_jg_loop[1];

                    if ($md_name == 'transfer') {
                        $l_jg_count;
                    } else {
                        if ($_POST['bench'] != "R") {
                            $l_jg_count = (count(explode(",", $chk_jg_loop[0])) + 1); //s_j
                        } else {
                            $l_jg_count = 1;
                        }
                    }
                    if ($rslt_is_printed_from == 0) {
                        $rslt_is_printed = f_cl_is_printed($listing_dt, $partno, $mainhead, $l_rosid);
                        if ($rslt_is_printed == 0) {
                            //verify cause list printed or not
                            $qry = "SELECT $leftjoin_field c.short_description, m.fil_no, m.fil_dt, EXTRACT(YEAR FROM m.fil_dt) AS fil_year, m.lastorder, m.diary_no_rec_date, h.*, l.purpose, STRING_AGG(c2.submaster_id :: TEXT, ',') AS cat1
                            FROM main m
                            LEFT JOIN heardt h ON m.diary_no = h.diary_no 
                            LEFT JOIN master.listing_purpose l ON l.code = h.listorder
                            LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                            LEFT JOIN master.casetype c ON m.casetype_id = c.casecode
                            LEFT JOIN mul_category c2 ON c2.diary_no = h.diary_no AND c2.display = 'Y' $sub_cat_arry    
                            $leftjoin_coram_r
                            $leftjoin_kword    
                            $leftjoin_docdetl    
                            $leftjoin_act    
                            $leftjoin_section    
                            WHERE c2.diary_no IS NOT NULL AND rd.fil_no IS NULL AND l.display = 'Y' $is_nmd $coram_sele_or_null 
                            $p_listorder            
                            $case_grp
                                $only_regs
                            $casetype   
                            $bench    
                            $case_from_to_yr  
                            $subhead_select    
                            $kword_selected  
                            $docdetl_selected    
                            $act_selected    
                            $section_selected    
                            $case_to_trans1 
                            $main_supp_falg_sbqry 
                            $from_part_sbqry    
                            $from_tran_rs_id    
                            AND (m.diary_no = m.conn_key:: BIGINT OR m.conn_key:: BIGINT=0 OR m.conn_key = '' OR m.conn_key IS NULL) AND h.mainhead = '" . $post_data['mainhead'] . "' 
                            GROUP BY h.diary_no, c.short_description, m.fil_no, m.fil_dt, m.lastorder, m.diary_no_rec_date, l.purpose, m.diary_no 
                            ORDER BY CASE WHEN h.coram != '0' THEN CAST(split_part(h.coram, ',', 1) AS INTEGER) ELSE 9999 END ASC, $order_by LIMIT $noc";

                            $query = $this->db->query($qry);
                            if ($query->getNumRows() >= 1) {
                                $qry_inst = "INSERT INTO last_heardt (diary_no,conn_key,next_dt,mainhead,subhead,clno,brd_slno,roster_id,judges,coram,board_type,usercode,ent_dt,module_id, mainhead_n, subhead_n,
                                            main_supp_flag,listorder,tentative_cl_dt,lastorder,listed_ia,sitting_judges,is_nmd $bench_flag_field) 
                                            SELECT j.* $bench_flag_inp FROM (SELECT h.diary_no,h.conn_key,h.next_dt,h.mainhead,h.subhead,h.clno,h.brd_slno,h.roster_id,h.judges,h.coram,h.board_type,h.usercode,h.ent_dt,h.module_id, h.mainhead_n, h.subhead_n,
                                            h.main_supp_flag,h.listorder,h.tentative_cl_dt,m.lastorder,h.listed_ia,h.sitting_judges, h.is_nmd
                                    FROM main m
                                    LEFT JOIN heardt h ON m.diary_no = h.diary_no 
                                    LEFT JOIN master.listing_purpose l ON l.code = h.listorder
                                    LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                                    LEFT JOIN master.casetype c ON m.casetype_id = c.casecode
                                    LEFT JOIN mul_category c2 ON c2.diary_no = h.diary_no AND c2.display = 'Y' $sub_cat_arry    
                                    $leftjoin_coram_r
                                    $leftjoin_kword    
                                    $leftjoin_docdetl    
                                    $leftjoin_act    
                                    $leftjoin_section    
                                    WHERE c2.diary_no IS NOT NULL AND rd.fil_no IS NULL AND l.display = 'Y' $is_nmd $coram_sele_or_null
                                    $p_listorder            
                                    $case_grp
                                        $only_regs
                                    $casetype   
                                    $bench    
                                    $case_from_to_yr  
                                    $subhead_select    
                                    $kword_selected  
                                    $docdetl_selected    
                                    $act_selected    
                                    $section_selected    
                                    $case_to_trans1
                                    $main_supp_falg_sbqry    
                                    AND (m.diary_no = m.conn_key:: BIGINT OR m.conn_key:: BIGINT=0 OR m.conn_key = '' OR m.conn_key IS NULL) AND h.mainhead = '" . $post_data['mainhead'] . "'
                                    GROUP BY h.diary_no, m.lastorder, m.diary_no
                                    ORDER BY CASE WHEN h.coram != '0' THEN CAST(split_part(h.coram, ',', 1) AS INTEGER) ELSE 9999 END ASC, $order_by LIMIT $noc ) j                
                                    LEFT JOIN last_heardt l ON j.diary_no = l.diary_no AND l.next_dt = j.next_dt AND l.listorder = j.listorder AND l.mainhead = j.mainhead 
                                    AND l.subhead = j.subhead AND l.judges = j.judges AND l.roster_id = j.roster_id AND l.clno = j.clno 
                                    AND l.main_supp_flag = j.main_supp_flag AND (l.bench_flag = '' OR l.bench_flag IS NULL) WHERE l.diary_no IS NULL";
                                $result_inst = $this->db->query($qry_inst);
                                $results = $query->getResultArray();
                                foreach ($results as $row) {
                                    $chk_x = explode("|", $chked_jg_arry[$j_c]);
                                    $chk_jud_id = $chk_x[0];
                                    $chk_roster_id = $chk_x[1];
                                    if ($_POST['bench'] == 'R') {
                                        $coram = $row['r_coram'];
                                    } else {
                                        $coram = $row['coram'];
                                    }
                                    $q_diary_no = $row['diary_no'];
                                    $q_subhead = $row['subhead'];
                                    $q_next_dt = $listing_dt;
                                    $q_clno = $partno;
                                    $q_brd_slno = $row['brd_slno'];
                                    $q_roster_id = $chk_roster_id;
                                    $q_judges = $chk_jud_id;
                                    $q_usercode = $ucode;
                                    //$q_module_id = "7";
                                    $q_main_supp_flag = $main_supp;
                                    if ($row['diary_no'] == $row['conn_key']) {
                                        $dairy_with_conn_k = f_cl_conn_key($q_diary_no);
                                    } else {
                                        $dairy_with_conn_k =  $q_diary_no;
                                    }
                                    $cat1 = $row['cat1'];
                                    //BAIL MATTERS START TO CHECK SAME CRIME
                                    $same_crime_verify = "0";
                                    if ((strpos($cat1, '173') !== false) or (strpos($cat1, '174') !== false) and $row['board_type'] == 'J') {
                                        $same_crime_verify = f_cl_same_crime($q_diary_no, $q_next_dt, $chk_roster_id);
                                    }
                                    $coram_verify = "0";
                                    if (($row['board_type'] == "J" or $row['board_type'] == "R") and $q_subhead != "817") {
                                        if ($coram != 0) {
                                            $coram_verify = "1";
                                            $chk_jud_id;
                                            $coram;
                                            if ($chk_jud_id != $coram) {
                                                $judges_explod = explode(",", $chk_jud_id);
                                                $coram_explod = explode(",", $coram);
                                                if ($judges_explod[0] == $coram_explod[0] and $judges_explod[0] != "") {
                                                    $chk_jud_id = $judges_explod[0];
                                                    $coram_verify = "0";
                                                } else if ($judges_explod[1] == $coram_explod[0] and $judges_explod[1] != "") {
                                                    $chk_jud_id = $judges_explod[1];
                                                    $coram_verify = "0";
                                                } else if ($judges_explod[2] == $coram_explod[0] and $judges_explod[2] != "") {
                                                    $chk_jud_id = $judges_explod[2];
                                                    $coram_verify = "0";
                                                } else {
                                                }
                                            } else {
                                                $coram_verify = "0";
                                            }
                                        }
                                    }
                                    $same_vehicle_verify = "0";
                                    $total_case_listed += f_heardt_cl_update($q_diary_no, $q_next_dt, $q_clno, $q_brd_slno, $q_roster_id, $q_judges, $q_usercode, $md_module_id, $q_main_supp_flag, $mainhead, $cat1);
                                    $per_jd_listed++;



                                    if ($per_jd_listed == $noc) {
                                        $j_c++;
                                        $per_jd_listed = 0;
                                    }
                                    if ($j_c == $count_j)
                                        $j_c = 1;
                                }

                                if ($total_case_listed == 0)
                                    $output .= "<tr><td class='class_red'>" . $out_result++ . ".</td><td class='class_red'>TOTAL CASES LISTED $total_case_listed</td></tr>";
                                else
                                    $output .= "<tr><td class='class_green'>" . $out_result++ . ".</td><td class='class_green'>TOTAL CASES LISTED $total_case_listed</td></tr>";
                                $total_case_listed = "0";
                            } else {
                                $output .= "<tr><td class='class_red'>NO RECORDS FOUND</td></tr>";
                            }
                        } //end if cl printed
                        else {
                            $output .= "<tr><td class='class_red'>" . $out_result++ . ".</td><td class='class_red'>YOU CAN NOT ALLOT CASES IN PART $partno. BECAUSE PART $partno FINALIZED.</td></tr>";
                        }
                    } //end if cl printed from
                    else {
                        $output .= "<tr><td class='class_red'>" . $out_result++ . ".</td><td class='class_red'>YOU CAN NOT ALLOT CASES IN PART $from_tran_part_no_fr. BECAUSE PART $from_tran_part_no_fr FINALIZED.</td></tr>";
                    }
                } //end of for loop
            } //end of noc more than 250
            else {
                $output .= "<tr><td class='class_red'>" . $out_result++ . ".</td><td class='class_red'>DON'T ALLOT MORE THAN 250 CASES PER JUDGE AT A TIME.</td></tr>";
            }
        } else {
            $output .= "<tr><td class='class_red'>" . $out_result++ . ".</td><td class='class_red'>YOU CAN NOT ALLOT CASES IN PART $partno</td></tr>";
        }
        $output .= '</table>';
        return $output;
    }


    public function transferCases($listing_dt_from, $listing_dt, $main_supp, $partno, $mainhead, $chked_jg_arry_to, $from_tran_jd_rs, $ucode, $from_tran_part_no_fr, $bench)
    {
        $output = "";
        $listing_dt_from = date("Y-m-d", strtotime($listing_dt_from));
        $listing_dt = date("Y-m-d", strtotime($listing_dt));
        $sql0 = "INSERT INTO last_heardt (diary_no,conn_key,next_dt,mainhead,subhead,clno,brd_slno,roster_id,judges,coram,board_type,usercode,ent_dt,module_id, mainhead_n, subhead_n, main_supp_flag,listorder,tentative_cl_dt,lastorder,listed_ia,sitting_judges,is_nmd,bench_flag) SELECT j.*, 'X' FROM (SELECT h.diary_no,h.conn_key,h.next_dt,h.mainhead,h.subhead,h.clno,h.brd_slno,h.roster_id,h.judges,h.coram,h.board_type,h.usercode,h.ent_dt,h.module_id, h.mainhead_n, h.subhead_n, h.main_supp_flag,h.listorder,h.tentative_cl_dt,m.lastorder,h.listed_ia,h.sitting_judges,h.is_nmd FROM main m INNER JOIN heardt h ON m.diary_no = h.diary_no where h.next_dt = '$listing_dt_from' and h.mainhead = '$mainhead' and h.roster_id = '" . $from_tran_jd_rs[1] . "' and h.clno = $from_tran_part_no_fr and h.brd_slno > 0 AND h.board_type = '" . $bench . "') j LEFT JOIN last_heardt l ON j.diary_no = l.diary_no AND l.next_dt = j.next_dt AND l.listorder = j.listorder AND l.mainhead = j.mainhead AND l.subhead = j.subhead AND l.judges = j.judges AND l.roster_id = j.roster_id AND l.clno = j.clno AND l.main_supp_flag = j.main_supp_flag AND (l.bench_flag = '' OR l.bench_flag IS NULL) WHERE l.diary_no IS NULL;";

        $insert = $this->db->query($sql0);

        //$sql = "UPDATE heardt h, main m SET h.next_dt = '$listing_dt', h.tentative_cl_dt = '$listing_dt', h.clno = $partno,  h.roster_id = '$chked_jg_arry_to[1]', h.judges = '$chked_jg_arry_to[0]', h.main_supp_flag = $main_supp, h.module_id = 24, h.usercode = '$ucode', h.ent_dt = NOW() WHERE m.diary_no = h.diary_no and h.next_dt = '$listing_dt_from' and h.mainhead = '$mainhead' and h.roster_id = '".$from_tran_jd_rs[1]."' and h.clno = $from_tran_part_no_fr and h.brd_slno > 0 AND h.board_type = '".$_POST['bench']."' and m.diary_no > 0";

        $sql = "UPDATE heardt SET next_dt = '$listing_dt', tentative_cl_dt = '$listing_dt', clno = $partno, roster_id = '$chked_jg_arry_to[1]', judges = '$chked_jg_arry_to[0]', main_supp_flag = $main_supp, module_id = 24, usercode = '$ucode', ent_dt = NOW() FROM main m WHERE m.diary_no = heardt.diary_no AND next_dt = '$listing_dt_from' AND mainhead = '$mainhead' AND roster_id = '" . $from_tran_jd_rs[1] . "' AND clno = $from_tran_part_no_fr AND brd_slno > 0 AND board_type = '" . $_POST['bench'] . "' AND m.diary_no > 0;";
        $isUpdated = $this->db->query($sql);
        if ($isUpdated > 0) {
            $output = "Cases Transferred Successfully as it is.";
        } else {
            $output = "Error : Cases Not Transferred as it is.";
        }

        $sql2 = "INSERT INTO drop_note (cl_date, clno, diary_no, roster_id, nrs, usercode, ent_dt, mf, part) select '$listing_dt' as cl_date, clno, diary_no, '" . $chked_jg_arry_to[1] . "', nrs,'$ucode' as usercode, NOW(), mf, '$partno' from drop_note where cl_date = '$listing_dt_from' and part = $from_tran_part_no_fr and roster_id = '" . $from_tran_jd_rs[1] . "' and mf = '$mainhead' and display = 'Y'";
        $insertNote = $this->db->query($sql2);

        $sql3 = "update drop_note set display = 'N' where cl_date = '$listing_dt_from' and part = $from_tran_part_no_fr and roster_id = '" . $from_tran_jd_rs[1] . "' and mf = '$mainhead' and display = 'Y'";
        $updateNote = $this->db->query($sql3);

        echo $output;
    }

    //START  Advance Tp Function vkg









    public function getListedData(string $board_type, int $p1, string $cldt): array
    {
       

        $sql = "SELECT 
                    j1, 
                    COUNT(diary_no) AS listed,
                    SUM(CASE WHEN pre_after_notice = 'Pre_Notice' THEN 1 ELSE 0 END) AS Pre_Notice,
                    SUM(CASE WHEN pre_after_notice = 'After_Notice' THEN 1 ELSE 0 END) AS After_Notice
                FROM (
                    SELECT DISTINCT 
                        h.diary_no, 
                        h.j1,
                        CASE 
                            WHEN (c.diary_no IS NULL AND 
                                (m.fil_no_fh = '' OR m.fil_no_fh IS NULL) 
                                AND h.subhead NOT IN (813, 814))
                        THEN 'Pre_Notice' 
                            ELSE 'After_Notice' 
                        END AS pre_after_notice
                    FROM advance_allocated h
                    LEFT JOIN main m ON h.diary_no::BIGINT = m.diary_no
                    LEFT JOIN advanced_drop_note d 
                        ON d.diary_no::int = h.diary_no::int
                        AND d.cl_date = h.next_dt
                    LEFT JOIN case_remarks_multiple c 
                        ON c.diary_no::BIGINT = m.diary_no::BIGINT 
                        AND c.r_head IN (1, 3, 62, 181, 182, 183, 184)
                    WHERE d.diary_no IS NULL
                    AND h.next_dt = '$cldt'
                    AND h.j1 = '$p1'
                    AND h.board_type = '$board_type'
                    AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
                    AND h.clno = 2
                    AND (
                        m.diary_no::BIGINT = m.conn_key::BIGINT 
                        OR m.conn_key = '' 
                        OR m.conn_key IS NULL 
                        OR m.conn_key = '0'
                    )
                ) h
                GROUP BY h.j1";
               
        $query = $this->db->query($sql);
        $result = $query->getRowArray();
        if (!$result) {
            return [];
        }
        return $result;
    }


    public function getisNMD($q_next_dt)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('master.sc_working_days');
        $builder->select('is_nmd');
        $builder->where('working_date', $q_next_dt); //vkg
        $builder->where('is_holiday', 0);
        $builder->where('display', 'Y');
        //echo $builder->getCompiledSelect();die;
        $query = $builder->get();
        $result = $query->getRowArray();
        return $result;
    }

    public function getJudgeGroupData($cldt, $ro_isnmd)
    {
        $builder = $this->db->table('judge_group jg');

        if ($ro_isnmd['is_nmd'] == 1) {
            $builder->select('CONCAT(p1, \',\', p2, 
                              CASE WHEN p3 != 0 THEN CONCAT(\',\', p3) ELSE \'\' END) AS jcd, 
                              jg.p1, jg.p2, jg.p3, j.abbreviation, 
                              (SELECT CASE WHEN SNo = 1 THEN 15 ELSE 10 END AS old_limit
                               FROM (SELECT ROW_NUMBER() OVER (ORDER BY working_date) AS SNo, s.* 
                                     FROM master.sc_working_days s
                                     WHERE EXTRACT(WEEK FROM working_date) = EXTRACT(WEEK FROM CAST(\'' . $cldt . '\' AS DATE)) 
                                     AND is_holiday = 0 AND is_nmd = 1 AND display = \'Y\' 
                                     AND EXTRACT(YEAR FROM working_date) = EXTRACT(YEAR FROM CAST(\'' . $cldt . '\' AS DATE))) a 
                               WHERE working_date = CAST(\'' . $cldt . '\' AS DATE)) AS old_limit');
        } else {
            $builder->select('jg.p1, jg.p2, jg.p3, j.abbreviation, jg.fresh_limit, 5 as old_limit');
        }

        $builder->join('master.judge j', 'j.jcode = jg.p1', 'left');
        $builder->where('jg.to_dt IS NULL');
        $builder->where('jg.display', 'Y');
        $builder->where('j.is_retired !=', 'Y');
        $builder->orderBy('j.judge_seniority');

        $query = $builder->get();

        return $query->getResultArray();
    }

    public function getPresidingJudge($q_next_dt, $selected_judges)
    {
        $sql = "SELECT jg.p1,jg.p2,jg.p3,j.abbreviation, COALESCE(listed, 0) AS listed FROM 
                       judge_group jg 
                   LEFT JOIN 
                       master.judge j ON j.jcode = jg.p1
                   LEFT JOIN (
                       SELECT 
                           h.j1, 
                           COUNT(h.diary_no) AS listed 
                       FROM 
                           advance_allocated h 
                   LEFT JOIN main m ON h.diary_no::text = m.diary_no::text
                       LEFT JOIN 
                           advanced_drop_note d ON d.diary_no = h.diary_no AND d.cl_date = h.next_dt
                       WHERE 
                           d.diary_no IS NULL 
                           AND h.next_dt = '$q_next_dt'                                          
                           AND h.board_type = 'J' 
                           AND h.clno = 2 
                           AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)  
                           AND (m.diary_no::text = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0') 
                       GROUP BY 
                           h.j1
                   ) b ON b.j1 = jg.p1
                   WHERE 
                       jg.p1 IN (" . $selected_judges . ") 
                       AND j.is_retired != 'Y' 
                       AND jg.to_dt IS NULL 
                       AND jg.display = 'Y' 
                   GROUP BY 
                       jg.p1, jg.p2, jg.p3, j.abbreviation ,b.listed,j.judge_seniority
                   ORDER BY 
                       j.judge_seniority";

        $rs = $this->db->query($sql);
        $result = $rs->getResultArray();
        return $result;
    }

    public function getPresidingJudgeP2($q_next_dt, $noc)
    {      
        $sql_p2 = "SELECT jg.p1,jg.p2,jg.p3,j.abbreviation,COALESCE(listed, 0) AS listed 
        FROM 
            judge_group jg 
        LEFT JOIN 
            master.judge j ON j.jcode = jg.p1
        LEFT JOIN (
            SELECT 
                h.j1, 
                COUNT(h.diary_no) AS listed 
            FROM 
                advance_allocated h 
            LEFT JOIN 
                main m ON h.diary_no::text = m.diary_no::text
            LEFT JOIN 
                advanced_drop_note d ON d.diary_no = h.diary_no AND d.cl_date = h.next_dt
            WHERE 
                d.diary_no IS NULL 
               AND h.next_dt = '$q_next_dt'                                    
                AND h.board_type = 'J' 
                AND h.clno = 2 
                AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)  
                AND (m.diary_no::text = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0') 
            GROUP BY 
                h.j1
        ) b ON b.j1 = jg.p1
        WHERE 
            j.is_retired != 'Y' 
            AND jg.to_dt IS NULL 
            AND jg.display = 'Y' 
        GROUP BY 
            jg.p1, jg.p2, jg.p3, j.abbreviation,b.listed,j.judge_seniority
        HAVING 
            COALESCE(b.listed, 0) = $noc
        ORDER BY 
            j.judge_seniority";
           // pr($sql_p2);

        $rs_p2 = $this->db->query($sql_p2);
        $result = $rs_p2->getResultArray();
        return $result;
    }

    public function getIsPerson($p_listorder, $p2_coram_check_where, $pre_after_notice_where_condition)
    {
        $sql_c = "SELECT * 
        FROM (
            SELECT 
                'YES' AS is_prepon,
                CASE 
                    WHEN (c.diary_no IS NULL AND (m.fil_no_fh = '' OR m.fil_no_fh IS NULL) AND h.subhead NOT IN (813, 814)) 
                    THEN 1 ELSE 2 
                END AS pre_notice,
                dd.doccode1, 
                a.advocate_id, 
                submaster_id, 
                m.conn_key AS main_key, 
                l.priority, 
                h.*,
                NULL AS old_advance_no,
                CASE 
                    WHEN submaster_id IN (343, 15, 16, 17, 18, 19, 20, 21, 22, 23, 341, 353, 157, 158, 159, 160, 161, 162, 163, 166, 173, 175, 176, 322, 222) 
                    THEN 'Yes' ELSE 'No' 
                END AS is_short_cat
            FROM main m
            INNER JOIN heardt h ON h.diary_no = m.diary_no
            INNER JOIN master.sc_working_days s ON s.working_date = h.next_dt
            LEFT JOIN master.listing_purpose l ON l.code = h.listorder
            LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
            LEFT JOIN mul_category mc ON mc.diary_no = m.diary_no 
                AND mc.display = 'Y' 
                AND mc.submaster_id NOT IN (911, 912, 914, 0, 239, 240, 241, 242, 243)
                AND mc.submaster_id IN (175, 176, 322, 222)
            LEFT JOIN docdetails dd ON dd.diary_no = h.diary_no 
                AND dd.iastat = 'P' 
                AND dd.doccode = 8 
                AND dd.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 3, 309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 102, 118, 131, 211, 309)
            LEFT JOIN case_remarks_multiple c ON c.diary_no::int = m.diary_no AND c.r_head IN (1, 3, 62, 181, 182, 183, 184)
            LEFT JOIN advocate a ON a.diary_no = m.diary_no 
                AND a.advocate_id IN (584, 585, 610, 616, 666, 940) 
                AND a.display = 'Y'
            WHERE 
                mc.diary_no IS NOT NULL 
                AND s.display = 'Y' 
                AND s.is_holiday = 0
                AND rd.fil_no IS NULL 
                AND m.active_casetype_id NOT IN (9, 10, 25, 26)
                AND m.c_status = 'P' 
                --AND (m.diary_no = m.conn_key::int OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL)
                 AND (m.diary_no = CAST(NULLIF(m.conn_key, '') AS INTEGER) OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL)	
              $p_listorder 
                AND h.main_supp_flag = 0
                AND h.subhead NOT IN (801, 817, 818, 819, 820, 848, 849, 850, 854, 0)
                AND h.mainhead = 'M' 
                AND h.next_dt is not null
                AND h.roster_id = 0 
                AND h.brd_slno = 0 
                AND h.board_type = 'J'
                AND h.next_dt > CURRENT_DATE + INTERVAL '1 month'
                $p2_coram_check_where
                AND (h.is_nmd = 'N' OR h.is_nmd IS NULL OR h.is_nmd = '0') 
                AND h.listorder NOT IN (4, 5, 32)
                AND h.no_of_time_deleted > 0        
            GROUP BY 
            h.diary_no,  m.diary_no, dd.doccode1, a.advocate_id, submaster_id, m.conn_key,h.subhead, c.diary_no,l.priority, h.*
        ) b
        $pre_after_notice_where_condition
        ORDER BY
            next_dt DESC,
            CASE WHEN old_advance_no IS NOT NULL THEN 9 ELSE 999 END ASC,
            priority ASC, 
            no_of_time_deleted DESC,
            CASE 
                WHEN (coram IS NOT NULL AND coram != '0' AND TRIM(coram) != '') 
                THEN 1 ELSE 999 
            END ASC,
            CAST(SUBSTRING(diary_no::text FROM LENGTH(diary_no::text) - 3 FOR 4) AS INTEGER) ASC,
            CAST(SUBSTRING(diary_no::text FROM 1 FOR LENGTH(diary_no::text) - 4) AS INTEGER) ASC";
            
            

        $rs_p2 = $this->db->query($sql_c);
        $result = $rs_p2->getResultArray();
        return $result;
    }

    public function getNewCoarm($coram)
    {

        $sql_crm = "SELECT 
        string_agg(CAST(jcode AS TEXT), ',' ORDER BY judge_seniority) AS new_coram 
        FROM 
        master.judge 
        WHERE 
        is_retired = 'N' 
        AND display = 'Y' 
        AND jtype = 'J' 
        AND jcode = ANY(string_to_array('$coram', ',')::INTEGER[])";

        $rs_crm = $this->db->query($sql_crm);
        $result = $rs_crm->getRowArray();
        return $result;
    }

    public function getAbbreviation($q_next_dt, $noc)
    {
        $sql_p2 = "SELECT jg.p1,jg.p2, jg.p3,j.abbreviation,COALESCE(listed, 0) AS listed 
                                            FROM judge_group jg 
                                            LEFT JOIN master.judge j ON j.jcode = jg.p1
                                             LEFT JOIN (SELECT h.j1,COUNT(h.diary_no) AS listed FROM advance_allocated h 
                                              LEFT JOIN main m ON h.diary_no::text = m.diary_no ::text
                                              LEFT JOIN advanced_drop_note d ON d.diary_no = h.diary_no AND d.cl_date = h.next_dt
                                              WHERE d.diary_no IS NULL 
                                              AND h.next_dt = '$q_next_dt'                                          
                                              AND h.board_type = 'J' 
                                               AND h.clno = 2 
                                               AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)  
                                               AND (m.diary_no::text = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0') 
                                               GROUP BY h.j1) 
                                               b ON b.j1 = jg.p1 WHERE jg.to_dt IS NULL 
                                                AND jg.display = 'Y' GROUP BY jg.p1, jg.p2, jg.p3, j.abbreviation,b.listed,j.judge_seniority
                                                 HAVING $noc > COALESCE(listed, 0) ORDER BY j.judge_seniority";
        $rs = $this->db->query($sql_p2);
        $result = $rs->getRowArray();
        return $result;
    }

    public function InsTransferOldComGenCases($q_next_dt)
    {
        $sql3 = "INSERT INTO transfer_old_com_gen_cases (diary_no, next_dt_old, next_dt_new, tentative_cl_dt_old,
        tentative_cl_dt_new, listorder, conn_key, ent_dt, test2, listorder_new, board_type, listtype)
        SELECT j.*, 'P' as listtype 
        FROM (
            SELECT 
                diary_no, 
                m.next_dt AS next_dt_old, 
                '$q_next_dt'::date AS next_dt_new, 
                m.tentative_cl_dt AS tentative_cl_dt_old, 
                '$q_next_dt'::date AS tentative_cl_dt_new, 
                m.listorder,
                m.conn_key::bigint, 
                NOW(), 
                'cron_a' AS cron, 
                (CASE WHEN m.listorder = 16 THEN 2 ELSE m.listorder END) AS listorder_new, 
                'J' AS btype 
            FROM (
                SELECT 
                    lp.priority AS lp_priority, 
                    m.diary_no_rec_date, 
                    s.stagename, 
                    s.priority, 
                    m.conn_key, 
                    active_fil_dt, 
                    active_fil_no, 
                    tentative_cl_dt, 
                    h.next_dt, 
                    h.coram, 
                    h.subhead, 
                    d.doccode1, 
                    mc.submaster_id, 
                    h.listorder, 
                    h.diary_no,
                    h.no_of_time_deleted,
                    CASE WHEN (h.subhead = 804 OR mc.submaster_id = 173 OR d.doccode1 IN (40, 41, 48, 49, 71, 72, 118, 131, 211, 309)) THEN 1 ELSE 0 END AS bail,
                    CASE WHEN a.advocate_id IN (584, 585, 610, 616, 666, 940) THEN 1 ELSE 0 END AS inperson,
                    CASE WHEN d.doccode1 IN (7, 66, 29, 56, 57, 28, 102, 103, 133, 226, 3, 73, 99, 27, 124, 2, 16) THEN 1 ELSE 0 END AS schm
                FROM 
                    advance_allocated ad_al
                LEFT JOIN heardt h ON h.diary_no = ad_al.diary_no::int
                LEFT JOIN master.listing_purpose lp ON lp.code = h.listorder AND lp.display = 'Y'
                LEFT JOIN master.subheading s ON s.stagecode = h.subhead AND s.listtype = 'M' AND s.display = 'Y'
                INNER JOIN main m ON m.diary_no = h.diary_no
                LEFT JOIN docdetails d ON d.diary_no = m.diary_no AND d.display = 'Y' AND d.iastat = 'P' AND d.doccode = 8
                    AND d.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 226, 3, 309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 71, 72, 102, 118, 131, 211, 309)
                LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no AND mc.display = 'Y'
                    AND mc.submaster_id != 911 AND mc.submaster_id != 912 AND mc.submaster_id != 239
                    AND mc.submaster_id != 240 AND mc.submaster_id != 241 AND mc.submaster_id != 242 AND mc.submaster_id != 243
                LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                LEFT JOIN advocate a ON a.diary_no = m.diary_no AND a.advocate_id IN (584, 585, 610, 616, 666, 940) AND a.display = 'Y'
                WHERE 
                    rd.fil_no IS NULL 
                    AND mc.diary_no IS NOT NULL 
                    AND m.c_status = 'P'
                    AND h.board_type = 'J' 
                    AND h.listorder != 32 
                    AND h.mainhead = 'M' 
                    AND h.clno = 0 
                    AND h.brd_slno = 0 
                    AND h.main_supp_flag = 0
                    AND h.next_dt > '$q_next_dt'::date
                    AND ad_al.next_dt = '$q_next_dt'::date 
                    AND ad_al.board_type = 'J'
                GROUP BY 
                    h.diary_no,lp.priority,m.diary_no_rec_date,s.stagename,s.priority,m.conn_key,m.active_fil_dt,m.active_fil_no
                ,d.doccode1,mc.submaster_id,a.advocate_id
            ) m 
        ) j
        LEFT JOIN transfer_old_com_gen_cases l ON j.diary_no = l.diary_no
            AND l.next_dt_old = j.next_dt_old
            AND l.next_dt_new = j.next_dt_new
        WHERE l.diary_no IS NULL";
        $rs = $this->db->query($sql3);
    }

    public function InsHeart($q_next_dt)
    {
        $sql4 = "INSERT INTO last_heardt (diary_no, conn_key, next_dt, mainhead, subhead, clno, brd_slno, roster_id, judges, coram, board_type, usercode, ent_dt, module_id, mainhead_n, subhead_n,
                                           main_supp_flag, listorder, tentative_cl_dt, lastorder, listed_ia, sitting_judges,
                                           list_before_remark, is_nmd, no_of_time_deleted)
                                           SELECT j.* 
                                           FROM (
                                               SELECT
                                                   m.diary_no, 
                                                   m.conn_key, 
                                                   m.next_dt, 
                                                   m.mainhead, 
                                                   m.subhead, 
                                                   m.clno, 
                                                   m.brd_slno, 
                                                   m.roster_id, 
                                                   m.judges, 
                                                   m.coram,
                                                   m.board_type::last_heardt_board_type, 
                                                   m.usercode, 
                                                   m.ent_dt, 
                                                   m.module_id, 
                                                   m.mainhead_n, 
                                                   m.subhead_n, 
                                                   m.main_supp_flag,
                                                   m.listorder, 
                                                   m.tentative_cl_dt, 
                                                   m.lastorder, 
                                                   m.listed_ia, 
                                                   m.sitting_judges,
                                                   m.list_before_remark, 
                                                   m.is_nmd, 
                                                   m.no_of_time_deleted 
                                               FROM (
                                                   SELECT 
                                                       lp.priority AS lp_priority, 
                                                       m.diary_no_rec_date, 
                                                       h.diary_no, 
                                                       h.conn_key, 
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
                                                       s.stagename, 
                                                       s.priority, 
                                                       active_fil_dt, 
                                                       active_fil_no, 
                                                       d.doccode1, 
                                                       mc.submaster_id,
                                                       h.no_of_time_deleted,
                                                       CASE WHEN (h.subhead = 804 OR mc.submaster_id = 173 OR d.doccode1 IN (40, 41, 48, 49, 71, 72, 118, 131, 211, 309)) THEN 1 ELSE 0 END AS bail,
                                                       CASE WHEN a.advocate_id IN (584, 585, 610, 616, 666, 940) THEN 1 ELSE 0 END AS inperson,
                                                       CASE WHEN d.doccode1 IN (7, 66, 29, 56, 57, 28, 102, 103, 133, 226, 3, 73, 99, 27, 124, 2, 16) THEN 1 ELSE 0 END AS schm
                                                   FROM 
                                                       advance_allocated ad_al
                                                   LEFT JOIN heardt h ON h.diary_no = ad_al.diary_no::int
                                                   LEFT JOIN master.listing_purpose lp ON lp.code = h.listorder AND lp.display = 'Y'
                                                   LEFT JOIN master.subheading s ON s.stagecode = h.subhead AND s.listtype = 'M' AND s.display = 'Y'
                                                   INNER JOIN main m ON m.diary_no = h.diary_no
                                                   LEFT JOIN docdetails d ON d.diary_no = m.diary_no AND d.display = 'Y' AND d.iastat = 'P' AND d.doccode = 8
                                                       AND d.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 226, 3, 309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 71, 72, 102, 118, 131, 211, 309)
                                                   LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no AND mc.display = 'Y'
                                                       AND mc.submaster_id != 911 AND mc.submaster_id != 912 AND mc.submaster_id != 239
                                                       AND mc.submaster_id != 240 AND mc.submaster_id != 241 AND mc.submaster_id != 242 AND mc.submaster_id != 243
                                                   LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                                                   LEFT JOIN advocate a ON a.diary_no = m.diary_no AND a.advocate_id IN (584, 585, 610, 616, 666, 940) AND a.display = 'Y'
                                                   WHERE 
                                                       rd.fil_no IS NULL 
                                                       AND mc.diary_no IS NOT NULL 
                                                       AND m.c_status = 'P' 
                                                       AND h.board_type = 'J' 
                                                       AND h.listorder != 32 
                                                       AND h.mainhead = 'M' 
                                                       AND h.clno = 0 
                                                       AND h.brd_slno = 0 
                                                       AND h.main_supp_flag = 0
                                                       AND h.next_dt > '$q_next_dt'::date
                                                       AND ad_al.next_dt = '$q_next_dt'::date 
                                                       AND ad_al.board_type = 'J'
                                                   GROUP BY 
                                                       h.diary_no,lp.priority,m.diary_no_rec_date,m.lastorder,s.stagename,s.priority,m.active_fil_dt,m.active_fil_no
                                                   ,d.doccode1,mc.submaster_id,a.advocate_id
                                               ) m
                                           ) j
                                           LEFT JOIN last_heardt l ON j.diary_no = l.diary_no
                                               AND l.next_dt = j.next_dt
                                               AND l.listorder = j.listorder
                                               AND l.mainhead = j.mainhead
                                               AND l.subhead = j.subhead
                                               AND l.roster_id = j.roster_id
                                               AND l.judges = j.judges
                                               AND l.clno = j.clno
                                               AND l.main_supp_flag = j.main_supp_flag
                                               AND l.ent_dt = j.ent_dt
                                               AND (l.bench_flag = '' OR l.bench_flag IS NULL)
                                           WHERE l.diary_no IS NULL";
        $rs = $this->db->query($sql4);
    }

    public function UpdateHeart($q_next_dt)
    {
        $sql5 = "UPDATE heardt h
        SET next_dt = '$q_next_dt',
            tentative_cl_dt = '$q_next_dt',
            usercode = '1',
            ent_dt = NOW(),
            module_id = '28',
            listorder = CASE WHEN t0.listorder = 16 THEN '2' ELSE t0.listorder END
        FROM (
            SELECT
                m.diary_no,
                m.conn_key,
                m.next_dt,
                m.mainhead,
                m.subhead,
                m.clno,
                m.brd_slno,
                m.roster_id,
                m.judges,
                m.coram,
                m.board_type,
                m.usercode,
                m.ent_dt,
                m.module_id,
                m.mainhead_n,
                m.subhead_n,
                m.main_supp_flag,
                m.listorder,
                m.tentative_cl_dt,
                m.lastorder,
                m.listed_ia,
                m.sitting_judges,
                m.is_nmd,
                m.no_of_time_deleted
            FROM (
                SELECT
                    lp.priority AS lp_priority,
                    m.diary_no_rec_date,
                    h.diary_no,
                    h.conn_key,
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
                    h.is_nmd,
                    h.no_of_time_deleted,
                    s.stagename,
                    s.priority,
                    active_fil_dt,
                    active_fil_no,
                    d.doccode1,
                    mc.submaster_id,
                    CASE WHEN (h.subhead = 804 OR mc.submaster_id = 173 OR d.doccode1 IN (40, 41, 48, 49, 71, 72, 118, 131, 211, 309)) THEN 1 ELSE 0 END AS bail,
                    CASE WHEN a.advocate_id IN (584, 585, 610, 616, 666, 940) THEN 1 ELSE 0 END AS inperson,
                    CASE WHEN d.doccode1 IN (7, 66, 29, 56, 57, 28, 102, 103, 133, 226, 3, 73, 99, 27, 124, 2, 16) THEN 1 ELSE 0 END AS schm
                FROM advance_allocated ad_al
                LEFT JOIN heardt h ON h.diary_no = ad_al.diary_no::bigint
                LEFT JOIN master.listing_purpose lp ON lp.code = h.listorder AND lp.display = 'Y'
                LEFT JOIN master.subheading s ON s.stagecode = h.subhead AND s.listtype = 'M' AND s.display = 'Y'
                INNER JOIN main m ON m.diary_no = h.diary_no
                LEFT JOIN docdetails d ON d.diary_no = m.diary_no AND d.display = 'Y' AND d.iastat = 'P' AND d.doccode = 8
                    AND d.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 226, 3, 309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 71, 72, 102, 118, 131, 211, 309)
                LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no AND mc.display = 'Y'
                    AND mc.submaster_id NOT IN (911, 912, 239, 240, 241, 242, 243)
                LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                LEFT JOIN advocate a ON a.diary_no = m.diary_no AND a.advocate_id IN (584, 585, 610, 616, 666, 940) AND a.display = 'Y'
                WHERE rd.fil_no IS NULL
                AND mc.diary_no IS NOT NULL
                AND m.c_status = 'P'
                AND h.board_type = 'J'
                AND h.listorder != 32
                AND h.mainhead = 'M'
                AND h.clno = 0
                AND h.brd_slno = 0
                AND h.main_supp_flag = 0
                AND h.next_dt > '$q_next_dt'
                AND ad_al.next_dt = '$q_next_dt'
                AND ad_al.board_type = 'J'
                GROUP BY h.diary_no,lp.priority,m.diary_no_rec_date,m.lastorder,
                s.stagename,s.priority,m.active_fil_dt,m.active_fil_no,d.doccode1,mc.submaster_id,a.advocate_id
            ) m
        ) t0
        WHERE t0.diary_no = h.diary_no";
        $rs = $this->db->query($sql5);
    }



    // END  Advance TP Function


    public function getVacationListReports($from_Date, $to_Date, $type) {
        $from_Date = date('Y-m-d', strtotime($from_Date));
        $to_Date = date('Y-m-d', strtotime($to_Date));
        if ($type == 'R') {
            $condition = "val.is_deleted='f'";
        } else {
            $condition = "val.is_deleted='t' AND DATE(val.updated_on) BETWEEN '$from_Date' AND '$to_Date'";
        }

        $queryBuilder = $this->db->table('vacation_advance_list val');
        $queryBuilder->distinct('m.diary_no');
        $queryBuilder->select('m.diary_no, val.is_fixed, CONCAT(m.reg_no_display, \' @ \', CONCAT(SUBSTRING(m.diary_no::TEXT FROM 1 FOR LENGTH(m.diary_no::TEXT) - 4), \' / \', SUBSTRING(m.diary_no::TEXT FROM LENGTH(m.diary_no::TEXT) - 3 FOR 4))) AS case_no')
            ->select('TO_CHAR(m.diary_no_rec_date, \'DD-MM-YYYY\') AS filing_date')
            ->select('CONCAT(COALESCE(m.pet_name, \'\'), \' Vs. \', COALESCE(m.res_name, \'\')) AS cause_title')
            ->select("STRING_AGG(DISTINCT CONCAT(COALESCE(b.name, ''), '<font color=\"blue\" weight=\"bold\">(', COALESCE(adv.pet_res, ''), ')</font>', '<font color=\"red\" weight=\"bold\">', CASE WHEN vala.is_deleted = 't' THEN '(Declined)' ELSE '' END, '</font>'), '<br/>') AS advocate")
            ->select('val.is_deleted AS declined_by_admin')
            ->select('val.updated_on')
            ->select('val.conn_key')
            ->select("CASE WHEN val.is_fixed = 'Y' THEN 1 ELSE 99 END AS fixed_order")
            ->select("CASE WHEN val.conn_key = 0 OR val.conn_key IS NULL OR val.conn_key = val.diary_no THEN val.diary_no ELSE val.conn_key END AS conn_key_order", false)
            ->join('main m', 'val.diary_no = m.diary_no', 'inner')
            ->join('advocate adv', 'val.diary_no = adv.diary_no', 'left')
            ->join('master.bar b', 'adv.advocate_id = b.bar_id', 'left')
            ->join('vacation_advance_list_advocate vala', 'val.diary_no = vala.diary_no AND b.aor_code = vala.aor_code AND vala.vacation_list_year = EXTRACT(YEAR FROM CURRENT_DATE)', 'left')
            ->where('val.vacation_list_year', date('Y'))
            ->where('adv.display', 'Y')
            ->groupStart()
            ->where("m.diary_no = CAST(m.conn_key AS bigint) OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL")
            ->groupEnd()
            ->where('b.isdead', 'N')
            ->where('b.if_aor', 'Y')
            ->where($condition)
            ->groupBy('m.diary_no, m.reg_no_display, m.diary_no_rec_date, m.pet_name, m.res_name, val.conn_key, val.is_fixed, val.is_deleted, val.updated_on, val.diary_no')
            ->orderBy('fixed_order')
            ->orderBy('conn_key_order', 'ASC');            
        $query = $queryBuilder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
}
