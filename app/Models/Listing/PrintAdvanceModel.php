<?php

namespace App\Models\Listing;

use CodeIgniter\Model;

class PrintAdvanceModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    // public function getsqUsection()
    // {

    // }

    public function getCauseListSection($list_dt, $mainhead, $main_suppl_condition, $lp_condition, $board_type, $court_condition,  $sec_id, $sec_id2, $section, $orderby, $cl_print_jo)
    {
        $list_dt = date('Y-m-d', strtotime($list_dt));
        $builder = $this->db->table('heardt h');
        $builder->distinct();

        // **Selecting Columns**
        $builder->select("
            CAST(m.diary_no_rec_date AS DATE) AS diary_no_rec_date,
            tentative_section(h.diary_no) AS dno,
            m.conn_key AS main_key,
            c1.short_description,
            r.courtno,
            u.name,
            COALESCE(us.section_name, '9999') AS section_name,
            l.purpose,
            EXTRACT(YEAR FROM m.active_fil_dt) AS fyr,
            m.active_reg_year,
            m.active_fil_dt,
            m.active_fil_no,
            m.reg_no_display,
            m.pet_name,
            m.res_name,
            m.pno,
            m.rno,
            m.casetype_id,
            m.ref_agency_state_id,
            m.diary_no_rec_date,
            br.remark,
            h.*,
              CASE
                WHEN h.conn_key = h.diary_no THEN '0000-00-00'
                ELSE '99'
            END AS case_order,
            COALESCE(ct.ent_dt, '9999-12-31 23:59:59') AS ent_dt_order,

            CAST(RIGHT(m.diary_no::TEXT, 4) AS INTEGER) AS diary_sub_4,
            CAST(LEFT(m.diary_no::TEXT, LENGTH(m.diary_no::TEXT) - 4) AS INTEGER) AS diary_sub_num
        ");

        // **Joins**
        $builder->join('main m', 'm.diary_no = h.diary_no', 'inner');
        $builder->join('master.listing_purpose l', 'l.code = h.listorder AND l.display = \'Y\'', 'inner');
        $builder->join('master.roster r', "r.id = h.roster_id AND r.display = 'Y' {$court_condition}", 'inner');
        $builder->join('brdrem br', 'CAST(br.diary_no AS BIGINT) = CAST(m.diary_no AS BIGINT)', 'left');
        $builder->join('master.casetype c1', 'm.active_casetype_id = c1.casecode', 'left');
        $builder->join('master.users u', 'u.usercode = m.dacode AND (u.display = \'Y\' OR u.display IS NULL)', 'left');
        // $builder->join('master.usersection us', 'us.id = u.section', 'left');
        $builder->join('master.usersection us', "us.id = u.section {$sec_id}", 'left');

        $builder->join('cl_printed p', 'p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = \'Y\'', 'left');
        $builder->join('conct ct', 'm.diary_no = ct.diary_no AND ct.list = \'Y\'', 'left');

        // **Where Conditions**
        $builder->where('h.mainhead', $mainhead . '' . $main_suppl_condition . '' . $lp_condition);
        $builder->where('h.next_dt', $list_dt);
        $builder->where('m.diary_no IS NOT NULL');
        $builder->where('m.c_status', 'P');
        $builder->where('h.roster_id >', 0);
        $builder->whereIn('h.main_supp_flag', [1, 2]);

        // **Optional Filters**
        if (!empty($section)) {
            if (is_array($section)) {
                $builder->whereIn('h.section', $section);
            } else {
                $builder->where('h.section', $section);
            }
        }

        if (!empty($cl_print_jo)) {
            $builder->where($cl_print_jo, null, false);  // Prevents escaping issues
        }

        if (!empty($sec_id2)) {
            if ($sec_id2 === 'IS NOT NULL') {
                $builder->where('h.sec_id2 IS NOT NULL', null, false);
            }
        }

        // **Subquery Using Query Builder**
        $subQuery = $this->db->table('heardt h2')
            ->select('1', false)
            ->whereIn('h2.listorder', [4, 5, 25, 32, 24, 7, 8, 21, 48, 2, 16, 49])
            // ->where('h2.board_type', $board_type)
            ->whereIn('h2.main_supp_flag', [1, 2])
            ->where('h2.next_dt', $list_dt)
            ->where('h2.mainhead', $mainhead)
            ->where('CAST( h2.conn_key AS BIGINT) = CAST( m.conn_key AS BIGINT)', null, false);
            if($board_type !== '0'){
                $subQuery->where('h2.board_type', $board_type); 
            }

        $builder->where("EXISTS (" . $subQuery->getCompiledSelect() . ")", null, false);

        // **Ordering (Fixed for PostgreSQL)**
        $builder->groupBy([
            'h.diary_no',
            'm.diary_no_rec_date',
            'm.conn_key',
            'c1.short_description',
            'r.courtno',
            'u.name',
            'us.section_name',
            'l.purpose',
            'm.active_fil_dt',
            'm.active_reg_year',
            'm.active_fil_no',
            'm.reg_no_display',
            'm.pet_name',
            'm.res_name',
            'm.pno',
            'm.rno',
            'm.casetype_id',
            'm.ref_agency_state_id',
            'br.remark',
            'ct.ent_dt',
            'diary_sub_4',
            'diary_sub_num'
        ]);

        // ORDER BY CLAUSE
        $builder->orderBy('r.courtno', 'ASC');

        if (!empty($orderby)) {
            $builder->orderBy($orderby);
        }
        $builder->groupBy("CAST(RIGHT(m.diary_no::TEXT, 4) AS INTEGER)");
        $builder->groupBy("CAST(LEFT(m.diary_no::TEXT, LENGTH(m.diary_no::TEXT) - 4) AS INTEGER)");


        //$builder->orderBy("COALESCE(us.section_name, '9999')", 'ASC'); 
        $builder->orderBy("u.name", 'ASC');
        $builder->orderBy("h.brd_slno", 'ASC');
        $builder->orderBy("CASE WHEN h.conn_key = h.diary_no THEN '0000-00-00' ELSE '99' END", 'ASC');
        //$builder->orderBy("COALESCE(ct.ent_dt, '9999-12-31 23:59:59')", 'ASC'); 

        // $builder->orderBy("CAST(RIGHT(m.diary_no::TEXT, 4) AS INTEGER)", 'ASC');  
        // $builder->orderBy("CAST(LEFT(m.diary_no::TEXT, LENGTH(m.diary_no::TEXT) - 4) AS INTEGER)", 'ASC');  // Fixed syntax

        $builder->orderBy('case_order', 'ASC');
        $builder->orderBy('ent_dt_order', 'ASC');
        $builder->orderBy('diary_sub_4', 'ASC');
        $builder->orderBy('diary_sub_num', 'ASC');

        // Debugging Output (optional, remove in production)
        // echo $builder->getCompiledSelect();
        // exit();
        // **Execute Query**
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function no_of_times_listed($diaryno)
    {
        $builder = $this->db->table('heardt')
            ->selectCount('*', 'total')
            ->where('diary_no', $diaryno)
            ->whereIn('main_supp_flag', [1, 2])
            ->where('clno !=', 0)
            ->where('brd_slno !=', 0)
            ->where('judges !=', '')
            ->get()
            ->getRow();

        $builder2 = $this->db->table('last_heardt')
            ->selectCount('*', 'total')
            ->where('diary_no', $diaryno)
            ->whereIn('main_supp_flag', [1, 2])
            ->groupStart()
            ->where('bench_flag IS NULL')
            ->orWhere('bench_flag', '')
            ->groupEnd()
            ->where('clno !=', 0)
            ->where('brd_slno !=', 0)
            ->where('judges !=', '')
            ->get()
            ->getRow();

        return ($builder->total ?? 0) + ($builder2->total ?? 0);
    }

    public function last_listed_date($diary_no, $str)
    {
        $order = ($str == 1) ? 'ASC' : 'DESC';

        // First Query
        $query1 = $this->db->table('heardt')
            ->select('next_dt, board_type, tentative_cl_dt, judges')
            ->where('diary_no', $diary_no)
            ->where('clno !=', 0)
            ->where('brd_slno !=', 0)
            ->whereIn('main_supp_flag', [1, 2])
            ->where('next_dt <=', date('Y-m-d'))
            ->get()
            ->getResultArray();

        // Second Query
        $query2 = $this->db->table('last_heardt')
            ->select('next_dt, board_type, tentative_cl_dt, judges')
            ->where('diary_no', $diary_no)
            ->where('clno !=', 0)
            ->where('brd_slno !=', 0)
            ->whereIn('main_supp_flag', [1, 2])
            ->groupStart()
            ->where('bench_flag IS NULL')
            ->orWhere('bench_flag', '')
            ->groupEnd()
            ->where('next_dt <=', date('Y-m-d'))
            ->get()
            ->getResultArray();

        // Merge results from both queries
        $mergedResults = array_merge($query1, $query2);

        // Sort the merged results by `next_dt` in required order (ASC or DESC)
        usort($mergedResults, function ($a, $b) use ($order) {
            if ($order === 'ASC') {
                return strtotime($a['next_dt']) - strtotime($b['next_dt']);
            } else {
                return strtotime($b['next_dt']) - strtotime($a['next_dt']);
            }
        });

        // Get the first record (latest or earliest, depending on order)
        $query = reset($mergedResults);

        if (!empty($query)) {
            $c_array = [];
            $c_array[0] = date('F d, Y', strtotime($query['next_dt']));  // Formatted Date
            $c_array[1] = $query['next_dt'];  // Raw Date

            $c_array[2] = (!empty($query['tentative_cl_dt']) && $query['tentative_cl_dt'] !== '0000-00-00')
                ? date('d-m-Y', strtotime($query['tentative_cl_dt']))
                : '...... ';

            // Court Type Mapping
            $courtTypes = [
                'J' => "Hon'ble Court",
                'R' => "Ld. Registrar's Court",
                'C' => "Hon'ble Court (In Chambers)"
            ];
            $c_array[3] = $courtTypes[$query['board_type']] ?? '';

            $c_array[4] = $query['judges'];

            return $c_array;
        }

        return null;
    }



    // public function get_main_details($vac_record = '')
    // {
    //     // First Query
    //     $builder1 =  $this->db->table('heardt h')
    //         ->select('
    //         u.name,
    //         COALESCE(us.section_name, tentative_section(m.diary_no)) AS section_name,
    //         m.conn_key AS main_key,
    //         c1.short_description,
    //         h.*,
    //         l.purpose,
    //         active_fil_no,
    //         m.active_reg_year,
    //         m.casetype_id,
    //         m.active_casetype_id,
    //         m.ref_agency_state_id,
    //         m.reg_no_display,
    //         EXTRACT(YEAR FROM m.fil_dt) AS fil_year,
    //         m.fil_no,
    //         m.fil_dt,
    //         m.fil_no_fh,
    //         m.reg_year_fh AS fil_year_f,
    //         m.mf_active,
    //         m.pet_name,
    //         m.res_name,
    //         s.sub_name1,
    //         s.sub_name2,
    //         s.sub_name3,
    //         s.sub_name4,
    //         pno,
    //         rno,
    //         m.diary_no_rec_date,
    //         CASE
    //             WHEN (m.diary_no = CAST(m.conn_key AS bigint) OR CAST(m.conn_key AS bigint) IS NULL OR CAST(m.conn_key AS bigint) = 0) THEN 0
    //             ELSE 1
    //         END AS main_or_connected,
    //         (SELECT 
    //             CASE 
    //                 WHEN diary_no IS NOT NULL THEN 1 
    //                 ELSE 0 
    //             END 
    //          FROM conct 
    //          WHERE diary_no = m.diary_no AND LIST = \'Y\') AS listed,
    //         \'Y\' AS is_fixed
    //     ')
    //         ->join('main m', 'h.diary_no = m.diary_no')
    //         ->join('mul_category mcat', 'h.diary_no = mcat.diary_no')
    //         ->join('master.submaster s', 'mcat.submaster_id = s.id')
    //         ->join('master.casetype c1', 'm.active_casetype_id = c1.casecode', 'left')
    //         ->join('master.listing_purpose l', 'l.code = h.listorder', 'left')
    //         ->join('master.users u', 'u.usercode = m.dacode AND (u.display = \'Y\' OR u.display IS NULL)', 'left')
    //         ->join('master.usersection us', 'us.id = u.section', 'left')
    //         ->where('m.c_status', 'P')
    //         ->where('mcat.display', 'Y')
    //         ->where('s.display', 'Y')
    //         ->where('DATE(h.next_dt) >=', '2018-05-20')
    //         ->where('DATE(h.next_dt) <=', '2018-07-01')
    //         ->whereIn('h.listorder', [4, 5, 7, 8])
    //         ->where('m.mf_active', 'F')
    //         ->where('h.board_type', 'J');

    //     // Second Query
    //     $builder2 =  $this->db->table('heardt h')
    //         ->select('
    //         u.name,
    //         COALESCE(us.section_name, tentative_section(m.diary_no)) AS section_name,
    //         m.conn_key AS main_key,
    //         c1.short_description,
    //         h.*,
    //         l.purpose,
    //         active_fil_no,
    //         m.active_reg_year,
    //         m.casetype_id,
    //         m.active_casetype_id,
    //         m.ref_agency_state_id,
    //         m.reg_no_display,
    //         EXTRACT(YEAR FROM m.fil_dt) AS fil_year,
    //         m.fil_no,
    //         m.fil_dt,
    //         m.fil_no_fh,
    //         m.reg_year_fh AS fil_year_f,
    //         m.mf_active,
    //         m.pet_name,
    //         m.res_name,
    //          s.sub_name1,
    //         s.sub_name2,
    //         s.sub_name3,
    //         s.sub_name4,
    //         pno,
    //         rno,
    //         m.diary_no_rec_date,
    //         CASE
    //             WHEN (m.diary_no = CAST(m.conn_key AS bigint) OR CAST(m.conn_key AS bigint) IS NULL OR CAST(m.conn_key AS bigint) = 0) THEN 0
    //             ELSE 1
    //         END AS main_or_connected,
    //         (SELECT 
    //             CASE 
    //                 WHEN diary_no IS NOT NULL THEN 1 
    //                 ELSE 0 
    //             END 
    //          FROM conct 
    //          WHERE diary_no = m.diary_no AND LIST = \'Y\') AS listed,
    //         \'N\' AS is_fixed
    //     ')
    //         ->join('main m', 'h.diary_no = m.diary_no')
    //         ->join('mul_category mcat', 'h.diary_no = mcat.diary_no')
    //         ->join('master.submaster s', 'mcat.submaster_id = s.id')
    //         ->join('master.casetype c1', 'm.active_casetype_id = c1.casecode', 'left')
    //         ->join('master.listing_purpose l', 'l.code = h.listorder', 'left')
    //         ->join('master.users u', 'u.usercode = m.dacode', 'left')
    //         ->join('master.usersection us', 'us.id = u.section', 'left')
    //         ->where('m.c_status', 'P')
    //         ->where('mcat.display', 'Y')
    //         ->where('s.display', 'Y')
    //         ->where('m.mf_active', 'F')
    //         ->where('h.subhead !=', 818)
    //         ->where('mcat.submaster_id !=', 911)
    //         ->whereNotIn('s.id', function ($subQuery) {
    //             $subQuery->select('id')
    //                 ->from('master.submaster')
    //                 ->where('(category_sc_old IS NOT NULL AND category_sc_old != \'\')')
    //                 ->groupStart()
    //                 ->where('CAST(category_sc_old AS INTEGER) >=', 301)
    //                 ->where('CAST(category_sc_old AS INTEGER) <=', 324)
    //                 ->orGroupStart()
    //                 ->where('CAST(category_sc_old AS INTEGER) >=', 401)
    //                 ->where('CAST(category_sc_old AS INTEGER) <=', 436)
    //                 ->groupEnd()
    //                 ->orGroupStart()
    //                 ->where('CAST(category_sc_old AS INTEGER) >=', 801)
    //                 ->where('CAST(category_sc_old AS INTEGER) <=', 818)
    //                 ->groupEnd()
    //                 ->orGroupStart()
    //                 ->where('CAST(category_sc_old AS INTEGER) >=', 1001)
    //                 ->where('CAST(category_sc_old AS INTEGER) <=', 1010)
    //                 ->groupEnd()
    //                 ->orWhereIn('CAST(category_sc_old AS INTEGER)', [1401, 1413, 1424])
    //                 ->orGroupStart()
    //                 ->where('CAST(category_sc_old AS INTEGER) >=', 1803)
    //                 ->where('CAST(category_sc_old AS INTEGER) <=', 1816)
    //                 ->groupEnd()
    //                 ->orWhereIn('CAST(category_sc_old AS INTEGER)', [1818, 1900, 2000, 2100, 2200, 2300, 2401, 2811, 3700])
    //                 ->orGroupStart()
    //                 ->where('CAST(category_sc_old AS INTEGER) >=', 2403)
    //                 ->where('CAST(category_sc_old AS INTEGER) <=', 2407)
    //                 ->groupEnd()
    //                 ->orGroupStart()
    //                 ->where('CAST(category_sc_old AS INTEGER) >=', 2501)
    //                 ->where('CAST(category_sc_old AS INTEGER) <=', 2504)
    //                 ->groupEnd()
    //                 ->orGroupStart()
    //                 ->where('CAST(category_sc_old AS INTEGER) >=', 3001)
    //                 ->where('CAST(category_sc_old AS INTEGER) <=', 3004)
    //                 ->groupEnd()
    //                 ->orGroupStart()
    //                 ->where('CAST(category_sc_old AS INTEGER) >=', 4001)
    //                 ->where('CAST(category_sc_old AS INTEGER) <=', 4003)
    //                 ->groupEnd()
    //                 ->groupEnd();
    //         })
    //         ->whereNotIn('m.diary_no', function ($subQuery) {
    //             $subQuery->select('CAST(diary_no AS BIGINT)')
    //                 ->from('not_before')
    //                 ->where('res_id', 11);
    //         })
    //         ->where('DATE(m.diary_no_rec_date) <', '2014-01-01')
    //         ->where('h.board_type', 'J')
    //         ->whereNotIn('m.diary_no', function ($subQuery) {
    //             $subQuery->select('fil_no')
    //                 ->from('rgo_default')
    //                 ->where('remove_def', 'N');
    //         });

    //     // Combine Queries
    //     $query1 = $builder1->getCompiledSelect();
    //     $query2 = $builder2->getCompiledSelect();

    //     $finalQuery = $query1 . " UNION " . $query2;
    //     // Add a LIMIT clause based on $vac_record
    //     if ($vac_record == 'ALL') {
    //         // If "ALL", no limit is applied
    //         $finalQueryWithLimit = $finalQuery;
    //     } else {
    //         // Otherwise, apply the LIMIT
    //         $finalQueryWithLimit = $finalQuery . " LIMIT " . intval($vac_record);
    //     }
    //     //pr($finalQueryWithLimit);

    //     // Execute the Query
    //     $result = $this->db->query($finalQueryWithLimit)->getResultArray();

    //     return $result;
    // }


    public function get_main_details($vac_record = '')
{
    // First Query
    $builder1 =  $this->db->table('heardt h')
        ->select('
        u.name,
        COALESCE(us.section_name, tentative_section(m.diary_no)) AS section_name,
        m.conn_key AS main_key,
        c1.short_description,
        h.*,
        l.purpose,
        active_fil_no,
        m.active_reg_year,
        m.casetype_id,
        m.active_casetype_id,
        m.ref_agency_state_id,
        m.reg_no_display,
        EXTRACT(YEAR FROM m.fil_dt) AS fil_year,
        m.fil_no,
        m.fil_dt,
        m.fil_no_fh,
        m.reg_year_fh AS fil_year_f,
        m.mf_active,
        m.pet_name,
        m.res_name,
        s.sub_name1,
        s.sub_name2,
        s.sub_name3,
        s.sub_name4,
        pno,
        rno,
        m.diary_no_rec_date,
        CASE
            WHEN (m.diary_no = CAST(NULLIF(m.conn_key, \'\') AS bigint) OR CAST(NULLIF(m.conn_key, \'\') AS bigint) IS NULL OR CAST(NULLIF(m.conn_key, \'\') AS bigint) = 0) THEN 0
            ELSE 1
        END AS main_or_connected,
        (SELECT 
            CASE 
                WHEN diary_no IS NOT NULL THEN 1 
                ELSE 0 
            END 
        FROM conct 
        WHERE diary_no = m.diary_no AND LIST = \'Y\') AS listed,
        \'Y\' AS is_fixed
    ')
        ->join('main m', 'h.diary_no = m.diary_no')
        ->join('mul_category mcat', 'h.diary_no = mcat.diary_no')
        ->join('master.submaster s', 'mcat.submaster_id = s.id')
        ->join('master.casetype c1', 'm.active_casetype_id = c1.casecode', 'left')
        ->join('master.listing_purpose l', 'l.code = h.listorder', 'left')
        ->join('master.users u', 'u.usercode = m.dacode AND (u.display = \'Y\' OR u.display IS NULL)', 'left')
        ->join('master.usersection us', 'us.id = u.section', 'left')
        ->where('m.c_status', 'P')
        ->where('mcat.display', 'Y')
        ->where('s.display', 'Y')
        ->where('DATE(h.next_dt) >=', '2018-05-20')
        ->where('DATE(h.next_dt) <=', '2018-07-01')
        ->whereIn('h.listorder', [4, 5, 7, 8])
        ->where('m.mf_active', 'F')
        ->where('h.board_type', 'J');

    // Second Query
    $builder2 =  $this->db->table('heardt h')
        ->select('
        u.name,
        COALESCE(us.section_name, tentative_section(m.diary_no)) AS section_name,
        m.conn_key AS main_key,
        c1.short_description,
        h.*,
        l.purpose,
        active_fil_no,
        m.active_reg_year,
        m.casetype_id,
        m.active_casetype_id,
        m.ref_agency_state_id,
        m.reg_no_display,
        EXTRACT(YEAR FROM m.fil_dt) AS fil_year,
        m.fil_no,
        m.fil_dt,
        m.fil_no_fh,
        m.reg_year_fh AS fil_year_f,
        m.mf_active,
        m.pet_name,
        m.res_name,
         s.sub_name1,
        s.sub_name2,
        s.sub_name3,
        s.sub_name4,
        pno,
        rno,
        m.diary_no_rec_date,
        CASE
            WHEN (m.diary_no = CAST(NULLIF(m.conn_key, \'\') AS bigint) OR CAST(NULLIF(m.conn_key, \'\') AS bigint) IS NULL OR CAST(NULLIF(m.conn_key, \'\') AS bigint) = 0) THEN 0
            ELSE 1
        END AS main_or_connected,
        (SELECT 
            CASE 
                WHEN diary_no IS NOT NULL THEN 1 
                ELSE 0 
            END 
        FROM conct 
        WHERE diary_no = m.diary_no AND LIST = \'Y\') AS listed,
        \'N\' AS is_fixed
    ')
        ->join('main m', 'h.diary_no = m.diary_no')
        ->join('mul_category mcat', 'h.diary_no = mcat.diary_no')
        ->join('master.submaster s', 'mcat.submaster_id = s.id')
        ->join('master.casetype c1', 'm.active_casetype_id = c1.casecode', 'left')
        ->join('master.listing_purpose l', 'l.code = h.listorder', 'left')
        ->join('master.users u', 'u.usercode = m.dacode', 'left')
        ->join('master.usersection us', 'us.id = u.section', 'left')
        ->where('m.c_status', 'P')
        ->where('mcat.display', 'Y')
        ->where('s.display', 'Y')
        ->where('m.mf_active', 'F')
        ->where('h.subhead !=', 818)
        ->where('mcat.submaster_id !=', 911)
        ->whereNotIn('s.id', function ($subQuery) {
            $subQuery->select('id')
                ->from('master.submaster')
                ->where('(category_sc_old IS NOT NULL AND category_sc_old != \'\')')
                ->where('category_sc_old ~ \'^\d+$\'')  // Ensure it's numeric
                ->groupStart()
                ->where('CAST(category_sc_old AS INTEGER) >=', 301)
                ->where('CAST(category_sc_old AS INTEGER) <=', 324)
                ->orGroupStart()
                ->where('CAST(category_sc_old AS INTEGER) >=', 401)
                ->where('CAST(category_sc_old AS INTEGER) <=', 436)
                ->groupEnd()
                ->orGroupStart()
                ->where('CAST(category_sc_old AS INTEGER) >=', 801)
                ->where('CAST(category_sc_old AS INTEGER) <=', 818)
                ->groupEnd()
                ->orGroupStart()
                ->where('CAST(category_sc_old AS INTEGER) >=', 1001)
                ->where('CAST(category_sc_old AS INTEGER) <=', 1010)
                ->groupEnd()
                ->orWhereIn('CAST(category_sc_old AS INTEGER)', [1401, 1413, 1424])
                ->orGroupStart()
                ->where('CAST(category_sc_old AS INTEGER) >=', 1803)
                ->where('CAST(category_sc_old AS INTEGER) <=', 1816)
                ->groupEnd()
                ->orWhereIn('CAST(category_sc_old AS INTEGER)', [1818, 1900, 2000, 2100, 2200, 2300, 2401, 2811, 3700])
                ->orGroupStart()
                ->where('CAST(category_sc_old AS INTEGER) >=', 2403)
                ->where('CAST(category_sc_old AS INTEGER) <=', 2407)
                ->groupEnd()
                ->orGroupStart()
                ->where('CAST(category_sc_old AS INTEGER) >=', 2501)
                ->where('CAST(category_sc_old AS INTEGER) <=', 2504)
                ->groupEnd()
                ->orGroupStart()
                ->where('CAST(category_sc_old AS INTEGER) >=', 3001)
                ->where('CAST(category_sc_old AS INTEGER) <=', 3004)
                ->groupEnd()
                ->orGroupStart()
                ->where('CAST(category_sc_old AS INTEGER) >=', 4001)
                ->where('CAST(category_sc_old AS INTEGER) <=', 4003)
                ->groupEnd()
                ->groupEnd();
        })
        ->whereNotIn('m.diary_no', function ($subQuery) {
            $subQuery->select('CAST(diary_no AS BIGINT)')
                ->from('not_before')
                ->where('res_id', 11);
        })
        ->where('DATE(m.diary_no_rec_date) <', '2014-01-01')
        ->where('h.board_type', 'J')
        ->whereNotIn('m.diary_no', function ($subQuery) {
            $subQuery->select('fil_no')
                ->from('rgo_default')
                ->where('remove_def', 'N');
        });

    // Combine Queries
    $query1 = $builder1->getCompiledSelect();
    $query2 = $builder2->getCompiledSelect();

    $finalQuery = $query1 . " UNION " . $query2;
    
    // Add a LIMIT clause based on $vac_record
    if ($vac_record == 'ALL') {
        // If "ALL", no limit is applied
        $finalQueryWithLimit = $finalQuery;
    } else {
        // Otherwise, apply the LIMIT
        $finalQueryWithLimit = $finalQuery . " LIMIT " . intval($vac_record);
    }

    // Execute the Query
    $result = $this->db->query($finalQueryWithLimit)->getResultArray();

    return $result;
}


    public function getEliminationListPrint($list_dt, $mainhead, $sectionName)
    {

        $builder = $this->db->table('heardt h');
        $builder->distinct();
        $builder->select('u.name,
                 CASE WHEN us.section_name IS NOT NULL THEN us.section_name ELSE tentative_section(m.diary_no) END AS section_name,
                 m.conn_key AS main_key,
                 c1.short_description,
                 h.*,
                 l.purpose,
                 m.active_fil_no,
                 m.active_reg_year,
                 m.casetype_id,
                 m.active_casetype_id,
                 m.ref_agency_state_id,
                 m.reg_no_display,
                EXTRACT(YEAR FROM m.fil_dt) AS fil_year,
                 m.fil_no,
                 m.fil_dt,
                 m.fil_no_fh,
                 m.reg_year_fh AS fil_year_f,
                 m.mf_active,
                 m.pet_name,
                 m.res_name,
                 pno,
                 rno,
                 m.diary_no_rec_date,
                CASE
                     WHEN (m.diary_no = CAST(m.conn_key AS bigint) 
                     OR CAST(m.conn_key AS bigint) IS NULL 
                     OR CAST(m.conn_key AS bigint)= 0) 
                     THEN 0
                ELSE 1
                END AS main_or_connected,
                 (SELECT CASE WHEN diary_no IS NOT NULL THEN 1 ELSE 0 END FROM conct WHERE diary_no = m.diary_no AND LIST = \'Y\') AS listed,
    \'Y\' AS is_fixed,
    CASE 
        WHEN h.conn_key = 0 
        OR h.conn_key IS NULL 
        OR h.conn_key = h.diary_no 
        THEN TIMESTAMP \'epoch\' + h.diary_no * INTERVAL \'1 second\' 
        ELSE COALESCE(ct.ent_dt, \'9999-12-31\'::timestamp) 
    END AS order_time
    ');

        $builder->join('main m', 'h.diary_no = m.diary_no', 'left');
        $builder->join('master.casetype c1', 'm.active_casetype_id = c1.casecode', 'left');
        $builder->join('master.listing_purpose l', 'l.code = h.listorder', 'left');
        $builder->join('master.users u', 'u.usercode = m.dacode AND (u.display = \'Y\' OR u.display IS NULL)', 'left');
        $builder->join('master.usersection us', 'us.id = u.section', 'left');
        $builder->join('mul_category mcat', 'mcat.diary_no = h.diary_no AND mcat.display = \'Y\'', 'left');
        //$builder->join('mul_category mcat', 'h.diary_no = mcat.diary_no', 'left');
        $builder->join('conct ct', 'm.diary_no = ct.diary_no', 'left');
        $builder->where('mcat.diary_no IS NOT NULL');
        $builder->where('l.display', 'Y');
        $builder->where('h.board_type', 'J');
        $builder->where('m.c_status', 'P');
        $builder->where('h.main_supp_flag', '0');
        $builder->where('h.next_dt', $list_dt);
        $builder->where('h.mainhead', $mainhead);

        // List Orders
        $builder->whereIn('h.listorder', [4, 5, 25, 32, 24, 7, 8, 21, 48, 2, 16, 49]);
        $builder->groupStart();
        // File Number Condition for MySQL
        $builder->whereIn('TRIM(LEADING \'0\' FROM SPLIT_PART(m.fil_no::text, \'-\', 1))', [
            "3", "15", "19", "31", "23", "24", "40", "32", "34", "22",
            "39", "11", "17", "13", "1", "7", "37", "9999", "38", "5",
            "21", "27", "4", "16", "20", "18", "33", "41", "35", "36",
            "28", "12", "14", "2", "8", "6"
        ]);
        $builder->groupEnd();
        $builder->groupStart()
            //->where('m.diary_no =CAST(m.conn_key AS bigint) OR CAST(m.conn_key AS bigint) IS NULL OR CAST(m.conn_key AS bigint)= 0')
            ->where('m.diary_no = CAST(NULLIF(m.conn_key, \'\') AS BIGINT) OR m.conn_key IS NULL OR m.conn_key= \'0\'')
            ->groupEnd();

        // Subquery Condition
        $builder->orWhere('(SELECT DISTINCT conn_key FROM conct WHERE diary_no = m.diary_no LIMIT 1) IN 
           (SELECT diary_no FROM heardt WHERE listorder IN (4, 5, 25, 32, 24, 7, 8, 21, 48, 2, 16, 49)
            AND board_type = \'J\'        
            AND main_supp_flag = \'0\'
            AND next_dt = ' . $this->db->escape($list_dt) . '
            AND mainhead = ' . $this->db->escape($mainhead) . ')');
        // Adding Section Name Filter
        if (!empty($sectionName)) {
            $builder->where('us.section_name', $sectionName);
        }

        $builder->orderBy('order_time', 'ASC');
        $builder->orderBy('main_or_connected', 'ASC');
        $query = $builder->get();
        $results = $query->getResultArray();
        return $results;
    }

    public function getClBrdRemark($diary_no)
    {
        $query = $this->db->table('brdrem')
            ->select('remark')
            ->where('diary_no', $diary_no)
            ->get();

        $row = $query->getRowArray();
        return $row['remark'] ?? null; // Return null if no remark found
    }

    public function getFListOrder()
    {
        $query = $this->db->table('master.listing_purpose')
            ->select('code, purpose')
            ->where('display', 'Y')
            ->where('code !=', 99)
            ->orderBy('priority', 'ASC')
            ->get();
        return $query->getResult();
    }



    public function sc_working_days($list_dt)
    {
        $result = 0;
        $builder = $this->db->table('master.sc_working_days');
        $builder->select('is_nmd');
        $builder->where('display', 'Y');
        $builder->where('is_nmd', 1);
        $builder->where('is_holiday', 0);
        $builder->where('working_date', $list_dt);
        $exists = $builder->countAllResults() > 0;
        if ($exists) {
            $result = 1;
        }
        return $result;
    }

    public function getSectionName($diary_no)
    {
        $query = $this->db->query("SELECT tentative_section(?) AS section_name", [$diary_no]);

        if ($query->getNumRows() > 0) {
            $result = $query->getRowArray();
            return $result['section_name'] ?? null;
        }
        return null;
    }



    public function get_advocate($diary_no)
    {
        $subquery = $this->db->table('advocate a')
            ->select("
                a.diary_no,
                b.name,
                STRING_AGG(
                    COALESCE(a.adv, ''), ''
                ORDER BY 
                 CASE WHEN pet_res IN ('I', 'N') THEN 99 ELSE 0 END ASC,
                 'adv_type DESC', pet_res_no ASC
                ) AS grp_adv,
                a.pet_res,
                a.adv_type,
                a.pet_res_no
            ", false)
            ->join('master.bar b', 'a.advocate_id = b.bar_id AND b.isdead != \'Y\'', 'left')
            ->where('a.diary_no', $diary_no)
            ->where('a.display', 'Y')
            ->groupBy('a.diary_no, b.name, a.pet_res, a.adv_type, a.pet_res_no')
            ->orderBy("(CASE WHEN a.pet_res = 'I' THEN 99 ELSE 0 END) ASC")
            ->orderBy('(a.adv_type) DESC')
            ->orderBy('(a.pet_res_no) ASC')
            ->getCompiledSelect();


        $builder = $this->db->table("($subquery) a", false);
        // $builder->select("
        // a.*,
        // STRING_AGG(a.name || '' || (CASE WHEN pet_res = 'R' THEN grp_adv END), '') AS r_n,
        // STRING_AGG(a.name || '' || (CASE WHEN pet_res = 'P' THEN grp_adv END), '') AS p_n,
        // STRING_AGG(a.name || '' || (CASE WHEN pet_res = 'I' THEN grp_adv END), '') AS i_n", false);
        $builder->select("
            a.*,
            STRING_AGG(a.name || '' || (CASE WHEN a.pet_res = 'R' THEN a.grp_adv END), '') AS r_n,
            STRING_AGG(a.name || '' || (CASE WHEN a.pet_res = 'P' THEN a.grp_adv END), '') AS p_n,
            STRING_AGG(a.name || '' || (CASE WHEN a.pet_res = 'I' THEN a.grp_adv END), '') AS i_n,
            STRING_AGG(a.name || '' || (CASE WHEN a.pet_res = 'N' THEN a.grp_adv END), '') AS intervenor
        ", false);
        $builder->groupBy('a.diary_no, a.name, a.grp_adv, a.pet_res, a.adv_type, a.pet_res_no');
        // Debug the query (optional)
        // echo $builder->getCompiledSelect(); // Check the final query output
        // die();
        $query = $builder->get();
        $results = $query->getRowArray();
        return $results;
    }


    public function get_lowerct($diary_no)
    {
        $builder = $this->db->table('lowerct a');
        $builder->select('lct_dec_dt, lct_caseno, lct_caseyear, short_description AS type_sname');
        $builder->join('master.casetype ct', 'ct.casecode = a.lct_casetype AND ct.display = \'Y\'', 'left');
        $builder->where('a.diary_no', $diary_no);
        $builder->where('a.is_order_challenged', 'Y');
        $builder->where('a.lw_display', 'Y');
        $builder->where('a.ct_code', 4);
        $builder->orderBy('a.lct_dec_dt', 'DESC');
        //   echo $builder->getCompiledSelect(); // Check the final query output
        // die();
        $query = $builder->get();
        $results = $query->getResult();
        return $results;
    }


    public function get_mul_category($diary_no)
    {
        $builder = $this->db->table('mul_category mc');
        $builder->select('category_sc_old');
        $builder->join('master.submaster s', 's.id = mc.submaster_id');
        $builder->where('mc.display', 'Y');
        $builder->where('mc.diary_no', $diary_no);
        $builder->limit(1);
        $query = $builder->get();
        $results = $query->getResult();
        return $results;
    }


    public function doccode($diary_no)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('heardt h');
        // $builder->select('h.diary_no, d.docnum, d.docyear, d.doccode1, 
        //     (CASE WHEN dm.doccode1 = 19 THEN d.other1 ELSE d.docdesc END) AS docdesp, 
        //     d.other1, d.iastat');

        $builder->select('h.diary_no, d.docnum, d.docyear, d.doccode1, 
        (CASE WHEN dm.doccode1 = 19 THEN d.other1 ELSE d.remark END) AS docdesp, 
        d.other1, d.iastat');
        $builder->join('docdetails d', 'd.diary_no = h.diary_no');
        $builder->join('master.docmaster dm', 'dm.doccode1 = d.doccode1 AND dm.doccode = d.doccode');
        $builder->where('h.diary_no', $diary_no);
        $builder->where('d.doccode', 8);
        $builder->where('dm.display', 'Y');
        $builder->where("CONCAT(d.docnum, d.docyear) IN (
            SELECT UNNEST(STRING_TO_ARRAY(REPLACE(TRIM(BOTH ',' FROM listed_ia), '/', ''), ','))::TEXT
        )");
        $query = $builder->get();
        $results = $query->getResult();
        return $results;
    }


    public function getHFlistingDate()
    {
        $builder = $this->db->table('heardt');
        $builder->select('next_dt')
            ->where('mainhead', 'M')
            ->where('next_dt >=', date('Y-m-d'))
            ->groupStart()
            ->where('main_supp_flag', '1')
            ->orWhere('main_supp_flag', '2')
            ->groupEnd()
            ->groupBy('next_dt');
        //->orderBy('next_dt', 'ASC');
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function getHFlistingBenches()
    {
        $builder = $this->db->table('master.roster r');
        $builder->select('r.id, 
            STRING_AGG(j.jcode::text, \',\' ORDER BY j.judge_seniority) AS jcd, 
            STRING_AGG(CONCAT(j.first_name, \' \', j.sur_name), \',\' ORDER BY j.judge_seniority) AS jnm, 
            rb.bench_no, 
            mb.abbr, 
            r.tot_cases, 
            r.courtno, 
            mb.board_type_mb')
            ->join('master.roster_bench rb', 'rb.id = r.bench_id', 'left')
            ->join('master.master_bench mb', 'mb.id = rb.bench_id', 'left')
            ->join('master.roster_judge rj', 'rj.roster_id = r.id', 'left')
            ->join('master.judge j', 'j.jcode = rj.judge_id', 'left')
            ->where('j.is_retired !=', 'Y')
            ->where('mb.board_type_mb', 'J')
            ->where('j.display', 'Y')
            ->where('rj.display', 'Y')
            ->where('rb.display', 'Y')
            ->where('mb.display', 'Y')
            ->where('r.display', 'Y')
            ->where('r.m_f', '1')
            ->where('r.from_date >=', date('Y-m-d'))
            ->groupBy('r.id, rb.bench_no, mb.abbr, r.tot_cases, r.courtno, mb.board_type_mb')
            ->orderBy('r.courtno')
            ->orderBy('r.id')
            // ->orderBy('j.judge_seniority')
            ->orderBy('STRING_AGG(j.judge_seniority::text, \',\' ORDER BY j.judge_seniority)');
        //echo $builder->getCompiledSelect(); // Check the final query output
        //die();
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }


    function get_cl_brd_remark($diary_no)
    {
        $builder = $this->db->table('brdrem h');
        $builder->select('remark');
        $builder->where('diary_no', $diary_no);
        $query = $builder->get();
        //echo $this->db->getLastQuery(); // This will output the query
        $result = $query->getResult();
        return $result;
    }

    public function getSectionTen($casetype_displ, $ten_reg_yr, $ref_agency_state_id)
    {
        $builder = $this->db->table('master.da_case_distribution a');
        $builder->join('master.users b', 'b.usercode = a.dacode', 'left');
        $builder->join('master.usersection c', 'b.section = c.id', 'left');

        // Applying WHERE conditions
        $builder->where('a.case_type', $casetype_displ);
        $builder->where("$ten_reg_yr BETWEEN a.case_f_yr AND a.case_t_yr", null, false);  // Raw condition to prevent escaping
        $builder->where('a.state', $ref_agency_state_id);
        $builder->where('a.display', 'Y');
        $builder->select('a.dacode, c.section_name, b.name');

        return $builder->get()->getResultArray();
    }

    public function getSectionFilTrap($diary_no)
    {
        $builder = $this->db->table('fil_trap f');
        $builder->select('f.remarks, u.name');
        $builder->join('master.users u', 'u.empid = f.d_to_empid', 'inner');
        $builder->where('f.diary_no', $diary_no);
        $builder->limit(1);

        $query = $builder->get();

        $result = $query->getResultArray();
        return $result;
    }

    public function getOfficeReportDetails($diary_no, $list_dt)
    {
        $list_dt = date('Y-m-d', strtotime($list_dt));
        $builder = $this->db->table('office_report_details');
        $builder->select("
            SUBSTRING(CAST(diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(diary_no AS TEXT)) - 4) AS dno, 
            SUBSTRING(CAST(diary_no AS TEXT) FROM LENGTH(CAST(diary_no AS TEXT)) - 3) AS d_yr, 
            office_repot_name, 
            office_report_id, 
            order_dt, 
            rec_dt
        ");

        // Adding WHERE conditions
        $builder->where('diary_no', $diary_no);
        $builder->where('order_dt', $list_dt);
        $builder->where('display', 'Y');
        $builder->where('web_status', 1);

        $query = $builder->get();

        $result = $query->getResultArray();
        return $result;
    }


    public function get_prev_cl_printed_all($list_dt, $mainhead, $board_type, $main_suppl)
    {
        $builder = $this->db->table('cl_printed cp');
        $builder->select('cp.id, cl_content, cp.m_f, mb.board_type_mb');
        $builder->select('(SELECT COUNT(*) FROM master.roster_judge WHERE roster_id = cp.roster_id) as tot_judges');
        $builder->join('master.roster r', 'cp.roster_id = r.id');
        $builder->join('cl_text_save ct', 'cp.id = ct.clp_id');
        $builder->join('master.roster_bench rb', 'rb.id = r.bench_id', 'left');
        $builder->join('master.master_bench mb', 'mb.id = rb.bench_id', 'left');
        $builder->where('cp.main_supp', $main_suppl);
        $builder->where('cp.next_dt', $list_dt);
        $builder->where('cp.m_f', $mainhead);
        $builder->where('mb.board_type_mb', $board_type);
        $builder->where('rb.display', 'Y');
        $builder->where('mb.display', 'Y');
        $builder->where('r.display', 'Y');
        $builder->where('cp.display', 'Y');
        $builder->orderBy("(CASE WHEN r.courtno = 0 THEN 999 ELSE r.courtno END) ASC");
        $builder->orderBy('cp.m_f');
        $builder->orderBy("(CASE WHEN cp.from_brd_no > 500 AND r.courtno != 1 THEN 1 ELSE 2 END) ASC");
        $builder->orderBy('cp.from_brd_no');
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }


    public function get_prev_cl_printed($list_dt, $mainhead, $part_no, $jud_ros)
    {
        $builder = $this->db->table('cl_printed c');
        $builder->select('s.cl_content')
            ->join('cl_text_save s', 's.clp_id = c.id', 'left')
            ->where('s.display', 'Y')
            ->where('c.m_f', $mainhead)
            ->where('c.next_dt', $list_dt);
            
            
            if($jud_ros !== "0"){
                $builder->where('c.roster_id', $jud_ros);
            }
            if($part_no !== "-1"){
                $builder->where('part', $part_no);
            }
            
            
            // echo $builder->getCompiledSelect();
            // die();
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getClPrintedElimination($list_dt, $part_no, $mainhead)
    {
        $builder = $this->db->table('cl_printed c');
        $builder->select('s.cl_content')
            ->join('cl_text_save s', 's.clp_id = c.id', 'inner') // INNER JOIN as per original query
            ->where('s.display', 'Y')
            ->where('c.next_dt', $list_dt)
            ->where('c.part', $part_no)
            ->where('c.m_f', $mainhead)
            ->where('c.roster_id', 0) // Fixing the missing roster_id condition
            ->where('c.display', 'Y')
            ->limit(1); // Limit the results to 1

        $query = $builder->get();
        return $query->getRowArray();
    }


    public function getCauseListData($list_dt, $mainhead, $roster_id, $part_no, $board_type)
    {
        $builder = $this->db->table('heardt h');
        $builder->select('m.c_status, m.relief, u.name, us.section_name, h.*, l.purpose, c1.short_description, m.active_fil_no, m.active_reg_year, m.casetype_id, m.active_casetype_id, m.ref_agency_state_id, m.reg_no_display, YEAR(m.fil_dt) fil_year, m.fil_no, m.fil_dt, m.fil_no_fh, m.reg_year_fh AS fil_year_f, m.mf_active, m.pet_name, m.res_name, pno, rno, m.if_sclsc, m.diary_no_rec_date, s.stagename');
        $builder->join('main m', 'm.diary_no = h.diary_no', 'inner');
        $builder->join('master.casetype c1', 'active_casetype_id = c1.casecode', 'left');
        $builder->join('master.listing_purpose l', 'l.code = h.listorder', 'left');
        $builder->join('master.subheading s', 's.stagecode = h.subhead and s.display = \'Y\' and s.listtype = \'' . $mainhead . '\'', 'left');
        $builder->join('master.users u', 'u.usercode = m.dacode AND u.display = \'Y\'', 'left');
        $builder->join('master.usersection us', 'us.id = u.section', 'left');
        $builder->join('conct ct', 'm.diary_no=ct.diary_no and ct.list=\'Y\'', 'left');
        $builder->where('h.next_dt', $list_dt);
        $builder->where('h.mainhead', $mainhead);
        $builder->where('h.roster_id', $roster_id);
        $builder->where('h.clno', $part_no);
        $builder->where('h.brd_slno >', 0);
        $builder->where('l.display', 'Y');
        $builder->where('(h.main_supp_flag = 1 OR h.main_supp_flag = 2)');
        $builder->groupBy('h.diary_no');
        $builder->orderBy('h.brd_slno, if(h.conn_key=h.diary_no,\'0000-00-00\',99) ASC, if(ct.ent_dt is not null,ct.ent_dt,999) ASC, cast(SUBSTRING(m.diary_no,-4) as signed) ASC, cast(LEFT(m.diary_no,length(m.diary_no)-4) as signed ) ASC');

        return $builder->get()->getResultArray();
    }

    public function getRosterData($roster_id)
    {
        $builder = $this->db->table('master.roster r');
        $builder->select('r.id, GROUP_CONCAT(j.jcode ORDER BY j.judge_seniority) jcd, GROUP_CONCAT(j.jname ORDER BY j.judge_seniority) jnm, j.first_name, j.sur_name, title, r.courtno, rb.bench_no, mb.abbr, mb.board_type_mb, r.tot_cases, r.frm_time, r.session, r.if_print_in');
        $builder->join('master.roster_bench rb', 'rb.id = r.bench_id', 'left');
        $builder->join('master_bench mb', 'mb.id = rb.bench_id', 'left');
        $builder->join('master.roster_judge rj', 'rj.roster_id = r.id', 'left');
        $builder->join('master.judge j', 'j.jcode = rj.judge_id', 'left');
        $builder->where('j.is_retired !=', 'Y');
        $builder->where('j.display', 'Y');
        $builder->where('rj.display', 'Y');
        $builder->where('rb.display', 'Y');
        $builder->where('mb.display', 'Y');
        $builder->where('r.display', 'Y');
        $builder->where('r.id', $roster_id);
        $builder->groupBy('r.id');
        $builder->orderBy('r.id, j.judge_seniority');

        return $builder->get()->getRowArray();
    }

    public function getMainSuppFlag($list_dt, $part_no, $mainhead, $roster_id)
    {
        return $this->db->table('heardt')
            ->select("STRING_AGG(main_supp_flag::TEXT, ', ') as main_supp_flags, 
                      MIN(brd_slno) as min_brd_no, 
                      MAX(brd_slno) as max_brd_no")
            ->where('next_dt', $list_dt)
            ->where('clno', $part_no)
            ->where('mainhead', $mainhead)
            ->where('roster_id', $roster_id)
            ->groupBy('clno')
            ->get()
            ->getRowArray();
    }


    public function f_cl_is_printed($q_next_dt, $partno, $mainhead, $roster_ids)
    {

        $builder = $this->db->table('cl_printed');
        if (!is_array($roster_ids)) {
            $roster_ids = explode(',', $roster_ids);
        }
        $builder->select('*')
            ->where('next_dt', $q_next_dt)
            ->where('part', $partno)
            ->where('m_f', $mainhead)
            ->where('display', 'Y')
            ->whereIn('roster_id', $roster_ids);
        $query = $builder->get();
        return ($query->getNumRows() > 0) ? 1 : 0;
    }


    public function f_cl_reshuffle($listing_dt, $chk_jud_id, $mf, $partno, $chk_rs_id)
    {
        $result = 0;

        // Fetch max brd_slno for the given conditions
        $builder = $this->db->table('heardt')
            ->select("COALESCE(MAX(brd_slno), 0) AS new_no")
            ->where('judges', $chk_jud_id)
            ->where('next_dt', $listing_dt)
            ->where('mainhead', $mf)
            ->where('clno', $partno - 1);
        $query = $builder->get();
        $rowmx = $query->getRowArray();

        if (!empty($rowmx) && $rowmx['new_no'] > 0) {
            $new_no = $rowmx['new_no'];
        } else {
            $new_no = ($partno == 50) ? 1000 : (($partno == 99) ? 1500 : 0);
        }

        // Determine JOIN condition based on `mf`
        if ($mf != 'F') {
            $leftjoin_subhead = "LEFT JOIN master.subheading s ON s.stagecode = h.subhead 
                                AND s.display = 'Y' AND s.listtype = '$mf'";
            $order_by = "s.priority, SUBSTRING(h.diary_no FROM LENGTH(h.diary_no) - 3 FOR 4) ASC, 
                        SUBSTRING(h.diary_no FROM 1 FOR LENGTH(h.diary_no) - 4) ASC";
        } else {
            $leftjoin_subhead = "LEFT JOIN category_allottment c ON h.subhead = c.submaster_id 
                                AND c.ros_id = '$chk_rs_id' AND c.display = 'Y'";
            $order_by = "CASE WHEN h.subhead = 913 THEN 0 ELSE 9999 END ASC, 
                        SUBSTRING(h.diary_no FROM LENGTH(h.diary_no) - 3 FOR 4) ASC, 
                        SUBSTRING(h.diary_no FROM 1 FOR LENGTH(h.diary_no) - 4) ASC";
        }

        // Perform Update Query with Row Number Assignment in PostgreSQL
        $sql1 = "WITH ranked_data AS (
                    SELECT h.diary_no, m.conn_key::INTEGER AS conn_key, 
                        ROW_NUMBER() OVER (ORDER BY s.priority ASC, SUBSTRING(h.diary_no::TEXT FROM LENGTH(h.diary_no::TEXT) - 3 FOR 4) ASC) AS serial_number
                    FROM heardt h
                    INNER JOIN main m ON m.diary_no = h.diary_no
                    $leftjoin_subhead
                    WHERE (m.diary_no::text = m.conn_key::text OR m.conn_key::text = '0' OR m.conn_key::text IS NULL)
                    AND m.c_status = 'P' AND h.mainhead = ? 
                    AND h.next_dt = ? AND h.clno = ? AND h.brd_slno > 0 
                    AND h.judges = ? AND h.roster_id > 0
                )
                UPDATE heardt h
                SET brd_slno = ranked_data.serial_number, conn_key = ranked_data.conn_key
                FROM ranked_data
                WHERE h.diary_no = ranked_data.diary_no";
        
        $query1 = $this->db->query($sql1, [$mf, $listing_dt, $partno, $chk_jud_id]);
       
        if ($query1) {
            $result = 1;
        }

        // Insert into last_heardt if not exists
        $sql_conn = "INSERT INTO last_heardt (
    diary_no, conn_key, next_dt, mainhead, subhead, clno, brd_slno, roster_id, judges, coram,
    board_type, usercode, ent_dt, module_id, mainhead_n, subhead_n, main_supp_flag, listorder, tentative_cl_dt, lastorder,
    listed_ia, sitting_judges
        )
        SELECT  conc_diary_no, j.conn_key::int, j.next_dt, j.mainhead, j.subhead, j.clno, j.brd_slno, j.roster_id, j.judges, j.coram, j.board_type, j.usercode, j.ent_dt, j.module_id, j.mainhead_n, j.subhead_n, j.main_supp_flag, j.listorder, j.tentative_cl_dt, j.lastorder, j.listed_ia, j.sitting_judges 
        FROM (
            SELECT
                a.*
            FROM (
                SELECT
                    c.diary_no AS conc_diary_no,
                    m.conn_key,
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
                    h.sitting_judges
                FROM heardt h
                INNER JOIN main m ON m.diary_no = h.diary_no
                INNER JOIN conct c ON c.conn_key = m.conn_key::int 
                WHERE c.list = 'Y'
                AND m.c_status = 'P'
                AND m.diary_no::int  = m.conn_key::int 
				AND h.mainhead = '$mf' 
			  AND h.next_dt = '$listing_dt' 
			  AND h.clno = '$partno' 
			  AND h.brd_slno > 0 
			  AND h.judges = '$chk_jud_id' 
			  and h.roster_id > 0
			) a
            INNER JOIN main m ON a.conc_diary_no = m.diary_no
            INNER JOIN heardt h ON a.conc_diary_no = h.diary_no
        ) j
        LEFT JOIN last_heardt l ON j.conc_diary_no = l.diary_no
            AND l.next_dt = j.next_dt
			AND l.listorder = j.listorder
            AND l.mainhead = j.mainhead
            AND l.subhead = j.subhead
            AND l.judges = j.judges
            AND l.roster_id = j.roster_id
			AND l.clno = j.clno
            AND l.main_supp_flag = j.main_supp_flag
            AND (l.bench_flag = '' OR l.bench_flag IS NULL)
        WHERE l.diary_no IS NULL";
        //pr($sql_conn);

        $this->db->query($sql_conn);

        return $result;
    }


    public function f_cl_reshuffle_from_desired_no_old($listing_dt, $chk_jud_id, $mf, $partno, $chk_rs_id, $from_cl_no)
    {
        $from_cl_no = $from_cl_no - 1;
        $result = 0;

        // Determine the join and order by based on the value of $mf
        if ($mf != 'F') {
            $leftjoin_subhead = "LEFT JOIN subheading s ON s.stagecode = h.subhead AND s.display = 'Y' AND s.listtype = '$mf'";
            //$order_by = "s.priority, RIGHT(h.diary_no::text, 4) ASC, LEFT(h.diary_no::text, LENGTH(h.diary_no::text)-4) ASC";
            $order_by = "
    s.priority, 
    SUBSTRING(h.diary_no::TEXT FROM LENGTH(h.diary_no::TEXT) - 3 FOR 4) ASC, 
    SUBSTRING(h.diary_no::TEXT FROM 1 FOR LENGTH(h.diary_no::TEXT) - 4) ASC
";
        } else {
            $leftjoin_subhead = "LEFT JOIN category_allottment c ON h.subhead = c.submaster_id AND c.ros_id = '$chk_rs_id' AND c.display = 'Y'";
            $order_by = "CASE WHEN h.subhead = 913 THEN 0 ELSE 9999 END ASC, RIGHT(h.diary_no::text, 4) ASC, LEFT(h.diary_no::text, LENGTH(h.diary_no::text)-4) ASC";
        }


        // First Query: Update `heardt` table
        $subquery = $this->db->table('heardt h')
            ->select('h.diary_no, m.conn_key')
            ->join('main m', 'm.diary_no = h.diary_no')
            ->where("m.c_status", 'P')
            ->groupStart()
            ->where('m.diary_no = m.conn_key')
            ->orWhere('m.conn_key', 0)
            ->orWhere('m.conn_key', '')
            ->orWhere('m.conn_key IS NULL')
            ->groupEnd()
            ->where('h.mainhead', $mf)
            ->where('h.next_dt', $listing_dt)
            ->where('h.clno', $partno)
            ->where('h.brd_slno >', 0)
            ->where('h.judges', $chk_jud_id)
            ->where('h.roster_id >', 0)
            ->orderBy($order_by)
            ->getCompiledSelect();


        $updateQuery = "WITH numbered AS (
                SELECT 
                    ROW_NUMBER() OVER () + $from_cl_no AS serial_number, 
                    a.diary_no, 
                    a.conn_key::BIGINT
                FROM ($subquery) a
            )
            UPDATE heardt h
            SET 
                brd_slno = numbered.serial_number, 
                conn_key = numbered.conn_key
            FROM numbered
            WHERE h.diary_no = numbered.diary_no 
            AND h.diary_no > 0";
        //pr($updateQuery);

        $this->db->query($updateQuery);
        if ($this->db->affectedRows() > 0) {
            $result = 1;
        }

        // Second Query: Insert into `last_heardt` table
        $insertSubquery = $this->db->table('heardt h')
            ->select('c.diary_no AS conc_diary_no, m.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram, h.board_type, h.usercode, h.ent_dt, h.module_id, h.mainhead_n, h.subhead_n, h.main_supp_flag, h.listorder, h.tentative_cl_dt, m.lastorder, h.listed_ia, h.sitting_judges')
            ->join('main m', 'm.diary_no = h.diary_no')
            ->join('conct c', 'c.conn_key = m.conn_key')
            ->where('c.list', 'Y')
            ->where('m.c_status', 'P')
            ->where('m.diary_no = m.conn_key')
            ->where('h.mainhead', $mf)
            ->where('h.next_dt', $listing_dt)
            ->where('h.clno', $partno)
            ->where('h.brd_slno >', 0)
            ->where('h.judges', $chk_jud_id)
            ->where('h.roster_id >', 0)
            ->getCompiledSelect();

        $insertQuery = "INSERT INTO last_heardt (diary_no, conn_key, next_dt, mainhead, subhead, clno, brd_slno, roster_id, judges, coram, board_type, usercode, ent_dt, module_id, mainhead_n, subhead_n, main_supp_flag, listorder, tentative_cl_dt, lastorder, listed_ia, sitting_judges)
        SELECT j.*
        FROM ($insertSubquery) j
        LEFT JOIN last_heardt l ON j.conc_diary_no = l.diary_no AND l.next_dt = j.next_dt AND l.listorder = j.listorder AND l.mainhead = j.mainhead AND l.subhead = j.subhead AND l.judges = j.judges AND l.roster_id = j.roster_id AND l.clno = j.clno AND l.main_supp_flag = j.main_supp_flag AND (l.bench_flag = '' OR l.bench_flag IS NULL)
        WHERE l.diary_no IS NULL";

        $this->db->query($insertQuery);

        // Third Query: Update `heardt` table with connected cases
        $updateSubquery = $this->db->table('heardt h')
            ->select('c.diary_no AS conc_diary_no, m.conn_key, h.next_dt, h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.board_type, h.usercode, h.ent_dt, h.module_id, h.mainhead_n, h.subhead_n, h.main_supp_flag, h.listorder, h.tentative_cl_dt, h.sitting_judges')
            ->join('main m', 'm.diary_no = h.diary_no')
            ->join('conct c', 'c.conn_key = m.conn_key')
            ->where('c.list', 'Y')
            ->where('m.c_status', 'P')
            ->where('m.diary_no = m.conn_key')
            ->where('h.mainhead', $mf)
            ->where('h.next_dt', $listing_dt)
            ->where('h.clno', $partno)
            ->where('h.brd_slno >', 0)
            ->where('h.judges', $chk_jud_id)
            ->where('h.roster_id >', 0)
            ->getCompiledSelect();

        $updateConnectedQuery = "WITH updated AS (
            SELECT a.*
            FROM ($updateSubquery) a
            INNER JOIN main m ON a.conc_diary_no = m.diary_no
            INNER JOIN heardt h ON a.conc_diary_no = h.diary_no
            WHERE m.c_status = 'P'
        )
        UPDATE heardt h
        SET conn_key = updated.conn_key, next_dt = updated.next_dt, mainhead = updated.mainhead, subhead = updated.subhead, clno = updated.clno, brd_slno = updated.brd_slno, roster_id = updated.roster_id, judges = updated.judges, board_type = updated.board_type, usercode = updated.usercode, ent_dt = updated.ent_dt, module_id = updated.module_id, mainhead_n = updated.mainhead_n, subhead_n = updated.subhead_n, main_supp_flag = updated.main_supp_flag, listorder = updated.listorder, tentative_cl_dt = updated.tentative_cl_dt, sitting_judges = updated.sitting_judges
        FROM updated
        WHERE h.diary_no = updated.conc_diary_no AND h.diary_no > 0";

        $this->db->query($updateConnectedQuery);

        return $result;
    }

    public function f_cl_reshuffle_from_desired_no($listing_dt, $chk_jud_id, $mf, $partno, $chk_rs_id, $from_cl_no)
    {
        $from_cl_no = $from_cl_no - 1;
        $result = 0;

        if ($mf != 'F') {
            $leftjoin_subhead = "LEFT JOIN master.subheading s ON s.stagecode = h.subhead 
                                AND s.display = 'Y' AND s.listtype = '$mf'";
            $order_by = "s.priority, 
                         SUBSTRING(h.diary_no::TEXT FROM LENGTH(h.diary_no::TEXT)-3 FOR 4) ASC, 
                         SUBSTRING(h.diary_no::TEXT FROM 1 FOR LENGTH(h.diary_no::TEXT)-4) ASC";
        } else {
            $leftjoin_subhead = "LEFT JOIN category_allottment c ON h.subhead = c.submaster_id 
                                AND c.ros_id = '$chk_rs_id' AND c.display = 'Y'";
            $order_by = "CASE WHEN h.subhead = 913 THEN 0 ELSE 9999 END ASC, 
                         SUBSTRING(h.diary_no::TEXT FROM LENGTH(h.diary_no::TEXT)-3 FOR 4) ASC, 
                         SUBSTRING(h.diary_no::TEXT FROM 1 FOR LENGTH(h.diary_no::TEXT)-4) ASC";
        }

        $sql1 = "WITH ranked AS (
                    SELECT ROW_NUMBER() OVER (ORDER BY $order_by) AS serial_number, 
                            h.diary_no, 
                            COALESCE(NULLIF(m.conn_key, '')::VARCHAR, '0') AS conn_key
                    FROM heardt h
                    INNER JOIN main m ON m.diary_no = h.diary_no
                    $leftjoin_subhead
                    WHERE m.c_status = 'P' 
                    AND (m.diary_no = CAST(m.conn_key AS BIGINT) OR m.conn_key = '0' OR m.conn_key IS NULL)
                    AND h.mainhead = '$mf' 
                    AND h.next_dt = '$listing_dt' 
                    AND h.clno = '$partno' 
                    AND h.brd_slno > 0 
                    AND h.judges = '$chk_jud_id' 
                    AND h.roster_id > 0
                )
                UPDATE heardt h
                SET brd_slno = ranked.serial_number, 
                    conn_key = CAST(ranked.conn_key AS BIGINT)

                FROM ranked
                WHERE h.diary_no = ranked.diary_no 
                AND h.diary_no > 0;";



        $this->db->query($sql1);

        if ($this->db->affectedRows() > 0) {
            $result = 1;
        }

        // Inserting into last_heardt
        $sql_conn = "INSERT INTO last_heardt (diary_no, conn_key, next_dt, mainhead,subhead, clno, brd_slno, roster_id,judges, coram, board_type, usercode, 
                        ent_dt, module_id, mainhead_n, subhead_n,main_supp_flag, listorder, tentative_cl_dt,lastorder, listed_ia, sitting_judges) 
                            SELECT 
                            conc_diary_no, j.conn_key::int, j.next_dt, j.mainhead, j.subhead, j.clno, j.brd_slno, j.roster_id, j.judges, j.coram, j.board_type, j.usercode, j.ent_dt, j.module_id, j.mainhead_n, j.subhead_n, j.main_supp_flag, j.listorder, j.tentative_cl_dt, j.lastorder, j.listed_ia, j.sitting_judges 
                            FROM 
                            (
                                SELECT 
                                a.* 
                                FROM 
                                (
                                    SELECT 
                                    c.diary_no AS conc_diary_no, 
                                    m.conn_key, 
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
                                    h.sitting_judges
                                    FROM 
                                    heardt h 
                                    INNER JOIN main m ON m.diary_no = h.diary_no 
                                    INNER JOIN conct c ON c.conn_key = m.conn_key :: text :: bigint 
                                    WHERE 
                                    c.list = 'Y' 
                                    AND m.c_status = 'P' 
                                    AND m.diary_no = m.conn_key :: text :: bigint 
                                    AND h.mainhead = '$mf' 
                                    AND h.next_dt = '$listing_dt' 
                                    AND h.clno = '$partno' 
                                    AND h.brd_slno > 0 
                                    AND h.judges = '$chk_jud_id' 
                                    and h.roster_id > 0
                                ) a 
                                INNER JOIN main m ON a.conc_diary_no = m.diary_no 
                                INNER JOIN heardt h ON a.conc_diary_no = h.diary_no
                            ) j 
                            LEFT JOIN last_heardt l ON j.conc_diary_no = l.diary_no 
                            AND l.next_dt = j.next_dt 
                            AND l.mainhead = j.mainhead 
                            AND l.subhead = j.subhead 
                            AND l.judges = j.judges 
                            AND l.roster_id = j.roster_id 
                            AND l.clno = j.clno 
                            AND l.main_supp_flag = j.main_supp_flag 
                            AND (
                                l.bench_flag = '' 
                                OR l.bench_flag IS NULL
                            ) 
                            WHERE 
                            l.diary_no IS NULL";

        $this->db->query($sql_conn);

        // Updating heardt records
        $sql_conn1 = "WITH updated AS (
                        SELECT h.diary_no AS conc_diary_no, 
                            m.conn_key::INT AS conn_key,  
                            h.next_dt, 
                            h.mainhead, 
                            h.subhead, 
                            h.clno, 
                            h.brd_slno, 
                            h.roster_id, 
                            h.judges, 
                            h.board_type, 
                            h.usercode, 
                            h.ent_dt, 
                            h.module_id, 
                            h.mainhead_n, 
                            h.subhead_n, 
                            h.main_supp_flag, 
                            h.listorder, 
                            h.tentative_cl_dt, 
                            h.sitting_judges
                        FROM heardt h
                        INNER JOIN main m ON m.diary_no = h.diary_no
                        INNER JOIN conct c ON c.conn_key::INT = m.conn_key::INT 
                        WHERE c.list = 'Y' 
                        AND m.c_status = 'P' 
                        AND m.diary_no::INT = m.conn_key::INT     
                        AND h.mainhead = '$mf' 
                                            AND h.next_dt = '$listing_dt' 
                                            AND h.clno = '$partno' 
                                            AND h.brd_slno > 0 
                                            AND h.judges = '$chk_jud_id' 
                                            AND h.roster_id > 0
                    )
                    UPDATE heardt h
                    SET conn_key = updated.conn_key,  
                        next_dt = updated.next_dt, 
                        mainhead = updated.mainhead, 
                        subhead = updated.subhead, 
                        clno = updated.clno, 
                        brd_slno = updated.brd_slno, 
                        roster_id = updated.roster_id, 
                        judges = updated.judges, 
                        board_type = updated.board_type, 
                        usercode = updated.usercode, 
                        ent_dt = updated.ent_dt, 
                        module_id = updated.module_id, 
                        mainhead_n = updated.mainhead_n, 
                        subhead_n = updated.subhead_n, 
                        main_supp_flag = updated.main_supp_flag, 
                        listorder = updated.listorder, 
                        tentative_cl_dt = updated.tentative_cl_dt, 
                        sitting_judges = updated.sitting_judges
                    FROM updated
                    WHERE h.diary_no = updated.conc_diary_no 
                    AND h.diary_no > 0";
        $this->db->query($sql_conn1);

        return $result;
    }



    public function cl_print_save($data)
    {
        extract($data); // Extract data array into variables

        // Check if the record already exists in `cl_printed`
        $exists = $this->db->table('cl_printed')
            ->where('next_dt', $list_dt)
            ->where('part', $part_no)
            ->where('m_f', $mainhead)
            ->where('roster_id', $roster_id)
            ->where('display', 'Y')
            ->countAllResults();

        if ($exists > 0) {
            return "Already Printed.";
        }

        // Update `coram` in `heardt` if it's not a vacation day
        if ($mainhead == 'F') {
            $isVacation = $this->db->table('master.holidays')
                ->like('hname', 'Summer Vacation')
                ->where('hdate', $list_dt)
                ->countAllResults();

            if ($isVacation == 0) {
                $this->db->table('heardt')
                    ->set('coram', 'SUBSTRING_INDEX(judges, ",", 1)', false)
                    ->where('next_dt', $list_dt)
                    ->where('clno', $part_no)
                    ->where('brd_slno >', 0)
                    ->where('mainhead', $mainhead)
                    ->where('roster_id', $roster_id)
                    ->where('board_type', 'J')
                    ->whereIn('main_supp_flag', [1, 2])
                    ->update();
            }
        } else {
            $isVacation = $this->db->table('master.holidays')
                ->like('hname', 'Summer Vacation')
                ->where('hdate', $list_dt)
                ->countAllResults();

            if ($isVacation == 0) {
                //         $this->db->table('heardt')
                // ->join('main', 'heardt.diary_no = main.diary_no') 
                // ->set('coram', "CASE 
                //                     WHEN main.casetype_id IN (39,19,20,34,35) 
                //                         OR main.active_casetype_id IN (39,19,20,34,35) 
                //                     THEN heardt.judges 
                //                     ELSE SPLIT_PART(heardt.judges, ',', 1) 
                //                 END", false) 
                // ->set('list_before_remark', 15)
                // ->where('heardt.next_dt', $list_dt)
                // ->where('heardt.clno', $part_no)
                // ->where('heardt.brd_slno >', 0)
                // ->whereNotIn('heardt.subhead', [850, 817])
                // ->where('heardt.list_before_remark !=', 11)
                // ->where('heardt.mainhead', $mainhead)
                // ->where('heardt.roster_id', $roster_id)
                // ->where('heardt.board_type', 'J')
                // ->whereIn('heardt.main_supp_flag', [1, 2])
                // ->groupStart()
                // ->where('heardt.coram', '')
                // ->orWhere("COALESCE(NULLIF(heardt.coram, ''), '0')::INTEGER = 0", null, false) 
                // ->orWhere('heardt.coram IS NULL')
                // ->groupEnd()
                // ->update();
                $ip = getClientIP();
                $sql = "UPDATE heardt h 
                SET coram = CASE 
                                WHEN m.casetype_id IN (39, 19, 20, 34, 35) 
                                    OR m.active_casetype_id IN (39, 19, 20, 34, 35) 
                                THEN h.judges 
                                ELSE SPLIT_PART(h.judges, ',', 1) 
                            END, 
                    list_before_remark = 15,
                    create_modify = date('Y-m-d H:i:s'),
                    updated_by = session()->get('login')['usercode'],
                    updated_by_ip = $ip   
                FROM main m
                WHERE h.diary_no = m.diary_no -- ✅ Joining with `main` table
                AND h.next_dt = '$list_dt' 
                AND h.clno = '$part_no' 
                AND h.brd_slno > 0 
                AND h.subhead NOT IN (850, 817) 
                AND h.list_before_remark != 11 
                AND h.mainhead = '$mainhead' 
                AND h.roster_id = '$roster_id' 
                AND h.board_type = 'J' 
                AND h.main_supp_flag IN (1, 2) 
                AND (
                    h.coram = '' 
                    OR COALESCE(NULLIF(h.coram, ''), '0')::INTEGER = 0 
                    OR h.coram IS NULL
                )";


                // Update coram in connected matters
                $mainCases = $this->db->table('heardt h')
                    ->select('m.conn_key, h.coram')
                    ->join('main m', 'm.diary_no = h.diary_no')
                    ->where('m.c_status', 'P')
                    ->where('m.diary_no = CAST(m.conn_key AS BIGINT)') 
                    ->where('h.roster_id', $roster_id)
                    ->where('h.next_dt', $list_dt)
                    ->where('h.clno', $part_no)
                    ->where('h.brd_slno >', 0)
                    ->where('h.mainhead', 'M')
                    ->where('h.board_type', 'J')
                    ->get()
                    ->getResultArray();


                foreach ($mainCases as $case) {
                    $this->db->query("
                    UPDATE heardt AS h
                    SET coram = ? ,
                    create_modify = ? ,
                    updated_by = ? ,
                    updated_by_ip = ? 
                    FROM main AS m
                    WHERE m.diary_no = h.diary_no
                      AND m.conn_key = ?
                      AND m.diary_no != ?
                      AND h.next_dt = ?
                      AND h.brd_slno > 0
                      AND h.mainhead = 'M'
                      AND h.board_type = 'J'
                      AND h.coram != ?
                      AND h.roster_id = ?
                      AND h.clno = ?
                ", [
                    $case['coram'], 
                    date("Y-m-d H:i:s"),
                    session()->get('login')['usercode'],
                    getClientIP(), 
                    $case['conn_key'],  
                    $case['conn_key'],  
                    $list_dt,  
                    $case['coram'],  
                    $roster_id,  
                    $part_no  
                ]);
                
                }
            }
            if($board_type == 'R'){
                $cur_dt = date('Y-m-d');
                $mainBoard = $this->db->table('heardt h')
                ->select('h.diary_no, h.judges')
                ->where('h.roster_id', $roster_id)
                ->where('h.next_dt', $list_dt)
                ->where('h.clno', $part_no)
                ->where('h.mainhead', $mainhead)
                ->where('h.brd_slno >', 0)
                ->where('h.board_type', 'R')
                ->groupStart()
                ->where('h.main_supp_flag' , 1)
                ->orWhere('h.main_supp_flag', 2)
                ->groupEnd()
                ->get()
                ->getResultArray();
                    foreach($mainBoard as $reg_cor){
                        $builder = $this->db->table('coram');
                        
                        // First SELECT query
                        $existingCoram = $builder->select('*')
                            ->where('diary_no', $diary_no)
                            ->where('to_dt', '0000-00-00')
                            ->where('display', 'Y')
                            ->where('board_type', 'R')
                            ->get()
                            ->getRowArray();
                        
                        if ($existingCoram) {
                            if ($existingCoram['jud'] != $judges) {
                                // UPDATE query
                                $builder->set('to_dt', $cur_dt)
                                    ->set('del_reason', 'By cl_print_save')
                                    ->set('create_modify', date('Y-m-d H:i:s'))
                                    ->set('updated_by', session()->get('login')['usercode'])
                                    ->set('updated_by_ip', $ip )
                                    ->where('diary_no', $diary_no)
                                    ->where('board_type', 'R')
                                    ->update();
                        
                                // INSERT query
                                $data = [
                                    'diary_no' => $diary_no,
                                    'board_type' => 'R',
                                    'jud' => $judges,
                                    'res_id' => 2,
                                    'from_dt' => $cur_dt,
                                    'to_dt' => '0000-00-00',
                                    'usercode' => $ucode,
                                    'ent_dt' => date('Y-m-d H:i:s'), // Use CodeIgniter's time helper if needed
                                    'display' => 'Y',
                                    'create_modify' => date('Y-m-d H:i:s'),
                                    
                                ];
                                $builder->insert($data);
                            }
                        } else {
                            // INSERT query
                            $data = [
                                'diary_no' => $diary_no,
                                'board_type' => 'R',
                                'jud' => $judges,
                                'res_id' => 2,
                                'from_dt' => $cur_dt,
                                'to_dt' => '0000-00-00',
                                'usercode' => $ucode,
                                'ent_dt' => date('Y-m-d H:i:s'), // Use CodeIgniter's time helper if needed
                                'display' => 'Y',
                                'create_modify' => date('Y-m-d H:i:s'),
                            ];
                            $builder->insert($data);
                        }
                }
            }
        }

    $builder = $this->db->table('last_heardt');

    $builder->set('bench_flag', 'X')
        ->where('next_dt', $list_dt)
        ->where('clno', $part_no)
        ->where('brd_slno >', 0)
        ->where('mainhead', $mainhead)
        ->where('roster_id', $roster_id)
        ->groupStart()
            ->where('bench_flag', '')
            ->orWhere('bench_flag IS NULL', null, false) // Passing null and false prevents auto-escaping of NULL
        ->groupEnd()
        ->update();

        // Insert into `cl_printed` and `cl_text_save`
        $minMax = $this->db->table('heardt')
        ->select('ARRAY_AGG(main_supp_flag) AS main_supp_flags, MIN(brd_slno) as min_brd_no, MAX(brd_slno) as max_brd_no')
        ->where('next_dt', $list_dt)
        ->where('clno', $part_no)
        ->where('mainhead', $mainhead)
        ->where('roster_id', $roster_id)
        ->groupBy('clno')
        ->get()
        ->getRowArray();
        $mainSupp = is_array($minMax['main_supp_flags']) ? $minMax['main_supp_flags'][0] : explode(',', trim($minMax['main_supp_flags'], '{}'))[0];

        $this->db->table('cl_printed')
            ->insert([
                'next_dt' => $list_dt,
                'part' => $part_no,
                'main_supp' => (int) $mainSupp,  // 🔹 Ensure it's an integer
                'm_f' => $mainhead,
                'roster_id' => $roster_id,
                'from_brd_no' => $minMax['min_brd_no'],
                'to_brd_no' => $minMax['max_brd_no'],
                'usercode' => $ucode,
                'ent_time' => date('Y-m-d H:i:s'),
                'user_ip' => get_client_ip(),
               'deleted_by'=>0,
               'pdf_nm'=>'',
               'pdf_dtl_nm'=>'',
               'create_modify' => date('Y-m-d H:i:s'),
               
            ]);
        

        $insertedId = $this->db->insertID();

        $this->db->table('cl_text_save')
            ->insert([
                'clp_id' => $insertedId,
                'cl_content' => $cntt,
                'userid' => $ucode,
                'ent_dt' => date('Y-m-d H:i:s'),
                'create_modify' => date('Y-m-d H:i:s'),
            ]);

            $builder = $this->db->table('heardt');

            $builder->set('no_of_time_deleted', 0)
                ->set('create_modify', date('Y-m-d H:i:s'))
                ->set('updated_by', session()->get('login')['usercode'])
                ->set('updated_by_ip', $ip )
                ->where('next_dt', $list_dt)
                ->where('clno', $part_no)
                ->where('brd_slno >', 0)
                ->where('mainhead', $mainhead)
                ->where('roster_id', $roster_id)
                ->where('board_type', $board_type)
                ->where('(main_supp_flag = 1 OR main_supp_flag = 2)')
                ->update();
                if ($board_type == 'C') {
                    $builder = $this->db->table('roster r')
                        ->select('*')
                        ->join('roster_bench rb', 'rb.id = r.bench_id', 'inner')
                        ->where('r.id', $roster_id)
                        ->where('r.from_date', $list_dt)
                        ->where('r.display', 'Y')
                        ->where('rb.display', 'Y')
                        ->where('rb.bench_id', 14);
                
                    $rs_boardtype_cc = $builder->get()->getNumRows();
                
                    if ($rs_boardtype_cc > 0) {
                        $board_type = 'CC';
                    }
                }
           
        $data['flag'] = 1;
        $data['board_type'] = $board_type;
        return $data;
    }


    public function clPrintedInsert($list_dt, $mainhead, $ucode)
    {
        $fields = $this->db->getFieldData('cl_printed');

        $data = [
            'next_dt'     => $list_dt,
            'main_supp'   => 0,
            'm_f'         => $mainhead,
            'roster_id'   => 0,
            'from_brd_no' => 0,
            'to_brd_no'   => 0,
            'usercode'    => $ucode,
            'part'        => 0,
            'deleted_by'  => 0,
            'pdf_nm'      => '',
            'pdf_dtl_nm'  => '',
            'ent_time'    => date('Y-m-d H:i:s'),
            'user_ip'     => service('request')->getIPAddress(),
            'create_modify' => date("Y-m-d H:i:s"),
            'updated_by' => session()->get('login')['usercode'],
            'updated_by_ip' => getClientIP()
        ];

        return $this->db->table('cl_printed')->insert($data);
    }


    public function clTextSaveInsert($clp_id, $cntt, $ucode)
    {
        $data = [
            'clp_id' => $clp_id,
            'cl_content' => $cntt,
            'userid' => $ucode,
            'ent_dt' => date('Y-m-d H:i:s'),
            'create_modify' => date("Y-m-d H:i:s"),
            'updated_by' => session()->get('login')['usercode'],
            'updated_by_ip' => getClientIP()
        ];

        return $this->db->table('cl_text_save')->insert($data);
    }


    public function getUserSection($sec_id = '')
    {
        $builder = $this->db->table('master.usersection');
        $builder->select('id, section_name');

        if (!empty($sec_id)) {
            $builder->where('id', $sec_id);
        } else {
            $builder->where('isda', 'Y');
            $builder->where('display', 'Y');
            $builder->orderBy('section_name', 'ASC');
        }
        // Print compiled SQL query (for debugging)
        // echo $builder->getCompiledSelect(); 
        // die; // Stop execution to only show the query

        $query = $builder->get();

        return $query->getResultArray();
    }

    public function getUserDA($ucode = '')
    {
        $builder = $this->db->table('master.users u')
            ->select("STRING_AGG(u2.usercode::TEXT, ',') AS allda", false)
            ->join('master.users u2', 'u2.section = u.section', 'left')
            ->where('u.display', 'Y')
            ->where('u.usercode', $ucode);

        // Print the compiled query for debugging
        // echo $builder->getCompiledSelect();
        // die(); // Stop execution to only show the query

        $query = $builder->get();
        return $query->getRow();
    }

    public function getUserMappedSections($ucode = '')
    {
        $builder = $this->db->table('master.users u')
            ->select("empid")
            ->where('u.usercode', $ucode);
        $query = $builder->get();
        return $query->getRow();
    }

    public function checkMappingExists($uempid)
    {
        $builder = $this->db->table('master.user_sec_map')
            ->select("COUNT(*) AS count")
            ->where('display', 'Y')
            ->where('empid', $uempid);

        $query = $builder->get();
        return $query->getRow()->count ?? 0;
    }

    public function getUserSections($uempid)
    {
        $builder = $this->db->table('master.user_sec_map')
            ->select("STRING_AGG(DISTINCT usec::TEXT, ',') AS section_list", false)
            ->where('display', 'Y')
            ->where('empid', $uempid);

        $query = $builder->get();
        return $query->getRow()->section_list ?? '';
    }

    public function getSectionNames($sectionIds)
    {
        if (empty($sectionIds)) {
            return '';
        }

        $builder = $this->db->table('master.usersection')
            ->select("STRING_AGG(section_name, ',') AS sec_list", false)
            ->whereIn('id', explode(',', $sectionIds));

        $query = $builder->get();
        return $query->getRow()->sec_list ?? '';
    }

    public function getUsersInSections($sectionIds)
    {
        if (empty($sectionIds)) {
            return '';
        }

        $builder = $this->db->table('master.users')
            ->select("STRING_AGG(usercode::TEXT, ',') AS all_users", false)
            ->where('display', 'Y')
            ->whereIn('section', explode(',', $sectionIds));

        $query = $builder->get();
        return $query->getRow()->all_users ?? '';
    }

    public function getSectionAndUserData($uempid)
    {
        // Step 1: Check if mapping exists
        $exists = $this->checkMappingExists($uempid);

        if ($exists > 0) {
            // Step 2: Get user sections
            $sectionIds = $this->getUserSections($uempid);

            if (!empty($sectionIds)) {
                // Step 3: Get section names
                $sectionNames = $this->getSectionNames($sectionIds);

                // Step 4: Get all users in those sections
                $allUsers = $this->getUsersInSections($sectionIds);

                // Step 5: Build conditions for queries
                $mdacode = "AND (m.dacode IN ($allUsers) OR m.dacode = 0)";
                $secCondition = "AND (us.id IN ($sectionIds) OR tentative_section(h.diary_no) IN ('$sectionNames'))";

                return [
                    'mdacode' => $mdacode,
                    'sec_condition' => $secCondition,
                    'sectionIds' => $sectionIds,
                    'sectionNames' => $sectionNames,
                    'allUsers' => $allUsers
                ];
            }
        }

        return [
            'mdacode' => '',
            'sec_condition' => '',
            'sectionIds' => '',
            'sectionNames' => '',
            'allUsers' => '',
        ];
    }



    public function getFieldSelRosterDts()
    {
        $builder = $this->db->table('heardt c');

        $builder->select('c.next_dt')
            ->where('c.mainhead', 'M')
            ->where('c.next_dt >=', 'CURRENT_DATE - INTERVAL \'7 days\'', false)
            ->groupStart()
            ->where('c.main_supp_flag', '1')
            ->orWhere('c.main_supp_flag', '2')
            ->groupEnd()
            ->groupBy('c.next_dt');


        $query = $builder->get();

        return $query->getResultArray();
    }


    public function after_allocation($list_dt, $board_type, $mf_roster_flag, $main_suppl, $mainhead, $ucode)
    {

        $db = \Config\Database::connect();
        $builder = $db->table('master.listed_info');

        // Subquery for aggregation

        $sub_subquery = $this->db->table('roster r')
            ->select('r.id, 
        CAST(SUBSTRING_INDEX(GROUP_CONCAT(j.jcode ORDER BY j.judge_seniority), \',\', 1) AS signed) AS jcd,
        rb.bench_no, mb.abbr, r.tot_cases, r.courtno')
            ->join('master.roster_bench rb', 'rb.id = r.bench_id', 'left')
            ->join('master.master_bench mb', 'mb.id = rb.bench_id', 'left')
            ->join('master.roster_judge rj', 'rj.roster_id = r.id', 'left')
            ->join('master.judge j', 'j.jcode = rj.judge_id', 'left')
            ->where('j.is_retired !=', 'Y')
            ->where('mb.board_type_mb', $board_type)
            ->where('j.display', 'Y')
            ->where('rj.display', 'Y')
            ->where('rb.display', 'Y')
            ->where('mb.display', 'Y')
            ->where('r.display', 'Y')
            ->where('r.m_f', $mf_roster_flag)
            ->where('r.from_date', $list_dt)
            ->groupBy('r.id')
            ->orderBy('r.courtno')
            ->orderBy('r.id')
            ->orderBy('j.judge_seniority');
        $sub_subqueryString = $sub_subquery->getCompiledSelect();

        $subquery = $db->table('heardt h')
            ->select('h.next_dt, h.mainhead, h.board_type, jcd, h.subhead, d.doccode1, mc.submaster_id, h.listorder, h.diary_no, 
        COALESCE(SUM(CASE WHEN (h.listorder = 4) THEN 1 ELSE 0 END), 0) as fix_dt,
        COALESCE(SUM(CASE WHEN (h.listorder = 5) THEN 1 ELSE 0 END), 0) as mentioning,
        COALESCE(SUM(CASE WHEN (h.listorder = 7) THEN 1 ELSE 0 END), 0) as week_commencing,
        COALESCE(SUM(CASE WHEN (h.listorder = 32) THEN 1 ELSE 0 END), 0) as freshly_filed,
        COALESCE(SUM(CASE WHEN (h.listorder = 25) THEN 1 ELSE 0 END), 0) as freshly_filed_adj,
        COALESCE(SUM(CASE WHEN (h.subhead = 824 AND h.listorder NOT IN (4,5,7,32,25)) THEN 1 ELSE 0 END), 0) as part_heard,
        COALESCE(SUM(CASE WHEN (a.inperson = 1 AND bail != 1 AND h.subhead != 824 AND h.listorder NOT IN (4,5,7,32,25)) THEN 1 ELSE 0 END), 0) as inperson,
        COALESCE(SUM(CASE WHEN (bail = 1 AND a.inperson != 1 AND h.subhead != 824 AND h.listorder NOT IN (4,5,7,32,25)) THEN 1 ELSE 0 END), 0) as bail,
        COALESCE(SUM(CASE WHEN (a.inperson != 1 AND bail != 1 AND h.subhead != 824 AND h.listorder = 8) THEN 1 ELSE 0 END), 0) as after_week,
        COALESCE(SUM(CASE WHEN (a.inperson != 1 AND bail != 1 AND h.subhead != 824 AND h.listorder = 24) THEN 1 ELSE 0 END), 0) as imp_ia,
        COALESCE(SUM(CASE WHEN (a.inperson != 1 AND bail != 1 AND h.subhead != 824 AND h.listorder = 21) THEN 1 ELSE 0 END), 0) as ia_other_than_imp_ia,
        COALESCE(SUM(CASE WHEN (a.inperson != 1 AND bail != 1 AND h.subhead != 824 AND h.listorder = 48) THEN 1 ELSE 0 END), 0) as nradj_not_list,
        COALESCE(SUM(CASE WHEN (a.inperson != 1 AND bail != 1 AND h.subhead != 824 AND h.listorder = 2) THEN 1 ELSE 0 END), 0) as adm_order,
        COALESCE(SUM(CASE WHEN (a.inperson != 1 AND bail != 1 AND h.subhead != 824 AND h.listorder = 16) THEN 1 ELSE 0 END), 0) as ordinary,
        COUNT(*) as total')
            ->join('main m', 'm.diary_no = h.diary_no')
            ->join('docdetails d', 'd.diary_no = m.diary_no AND d.display = \'Y\' AND d.iastat = \'P\' AND d.doccode = 8 
        AND d.doccode1 IN (7,66,29,56,57,28,103,133,226,3,309,73,99,40,48,72,71,27,124,2,16,41,49,71,72,102,118,131,211,309)', 'left')
            ->join('mul_category mc', 'mc.diary_no = h.diary_no AND mc.display = \'Y\'', 'left')
            ->join('advocate a', 'a.diary_no = m.diary_no AND a.advocate_id = 584 AND a.display = \'Y\'', 'left')
            ->join("($sub_subqueryString) a", 'true')
            ->where('m.diary_no = m.conn_key OR m.conn_key = \'\' OR m.conn_key IS NULL OR m.conn_key = \'0\'')
            ->where('m.c_status = \'P\' AND h.board_type = ' . $board_type . ' AND h.mainhead = ' . $mainhead . ' AND h.next_dt = ' . $list_dt . ' AND h.main_supp_flag = 0')
            ->groupBy('m.diary_no');
        $subqueryString = $subquery->getCompiledSelect();

        $query = $builder->set('main_supp', $main_suppl)
            ->set('remark', 'After_Allocation')
            ->set('next_dt', $list_dt)
            ->set('mainhead', $mainhead)
            ->set('bench_flag', $board_type)
            ->set('roster_id', '0')
            ->set('fix_dt', 0)
            ->set('mentioning', 0)
            ->set('week_commencing', 0)
            ->set('freshly_filed', 0)
            ->set('freshly_filed_adj', 0)
            ->set('part_heard', 0)
            ->set('inperson', 0)
            ->set('bail', 0)
            ->set('after_week', 0)
            ->set('imp_ia', 0)
            ->set('ia', 0)
            ->set('nr_adj', 0)
            ->set('adm_order', 0)
            ->set('ordinary', 0)
            ->set('total', 0)
            ->set('usercode', 1)
            ->set('ent_dt', date('Y-m-d'))
            ->join("($subqueryString) a", 'true')
            ->join('master.listed_info l', 'l.next_dt = a.next_dt AND l.mainhead = a.mainhead AND l.bench_flag = a.board_type AND l.roster_id = a.coram AND l.main_supp = ' . $main_suppl . ' AND l.remark = \'After_Allocation\'', 'left')
            ->where('l.next_dt IS NULL');

        $insert = $query->getCompiledInsert();
        $results = $db->query($insert, [$query]);
        return $results;
    }

    public function translateBoardType($mainhead, $board_type)
    {

        if ($mainhead == 'M' && $board_type == 'J') {
            return "Misc. Court";
        } elseif ($mainhead == 'F' && $board_type == 'J') {
            return "Regular Court";
        } elseif ($board_type == 'S') {
            return "Single Judge Court";
        } elseif ($board_type == 'C') {
            return "Chamber Court";
        } elseif ($board_type == 'CC') {
            return "Review & Curative";
        } elseif ($board_type == 'R') {
            return "Registrar Court";
        } else {
            return "";
        }
    }

    public function translateBoardTypehindi($mainhead, $board_type)
    {

        if ($mainhead == 'M' && $board_type == 'J') {
            return "विविध कोर्ट";
        } elseif ($mainhead == 'F' && $board_type == 'J') {
            return "नियमित सुनवाई";
        } elseif ($board_type == 'S') {
            return "एकल न्यायाधीश  कोर्ट";
        } elseif ($board_type == 'C') {
            return "चैंबर कोर्ट";
        } elseif ($board_type == 'CC') {
            return "समीक्षा एवं उपचारात्मक";
        } elseif ($board_type == 'R') {
            return "रजिस्ट्रार कोर्ट";
        } else {
            return "";
        }
    }

    public function translateid($mainhead, $board_type)
    {
        if ($mainhead == 'M' && $board_type == 'J') {
            return "1";
        } elseif ($mainhead == 'F' && $board_type == 'J') {
            return "2";
        } elseif ($board_type == 'S') {
            return "3";
        } elseif ($board_type == 'C') {
            return "4";
        } elseif ($board_type == 'CC') {
            return "5";
        } elseif ($board_type == 'R') {
            return "6";
        } else {
            return "";
        }
    }


    public function get_cl_print_data($list_dt, $courtArray, $board_type)
    {
        $builder = $this->db->table('cl_printed c');
        $builder->distinct();
        $builder->select('c.roster_id');
        $builder->join('master.roster r', 'r.id = c.roster_id');
        $builder->join('master.roster_bench rb', 'r.bench_id = rb.id');
        $builder->join('master.master_bench mb', 'rb.bench_id = mb.id');
        $builder->where('c.next_dt', $list_dt);
        $builder->whereIn('r.courtno', $courtArray);
        $builder->where('mb.board_type_mb', $board_type);
        $builder->where('c.display', 'Y');
        $builder->where('r.display', 'Y');
        $builder->where('rb.display', 'Y');
        $builder->where('mb.display', 'Y');
        $query = $builder->get();
        return $query->getResultArray();
    }



    public function get_cl_print_causes($list_dt, $board_type_cc_handeled, $rosterStr, $main_suppl, $courtArrayStr)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('heardt h');

        $builder->select('DISTINCT r.courtno AS court_no, r.frm_time AS court_start_time, m.diary_no, m.conn_key AS conn_key, m.reg_no_display AS case_no, h.judges AS judge_code, h.mainhead, h.list_status AS list, ct2.casename_hindi as casename_hindi, m.active_fil_no, m.active_reg_year, pno, rno, m.if_sclsc, h.clno, mb.board_type_mb, 
            COALESCE(
                (SELECT STRING_AGG(CONCAT(jname) ORDER BY judge_seniority, ', ') 
                 FROM master.judge 
                 WHERE is_retired = \'N\' AND display = \'Y\' AND jcode = ANY(string_to_array(h.judges, \',\'))) 
                , \'\') AS listed_before, 
            COALESCE(
                (SELECT STRING_AGG(CONCAT(judgename_hindi) ORDER BY judge_seniority, ', ') 
                 FROM master.judge 
                 WHERE is_retired = \'N\' AND display = \'Y\' AND jcode = ANY(string_to_array(h.judges, \',\'))) 
                , \'\') AS listed_before_hindi, 
            h.next_dt, 
            m.pet_name, 
            m.res_name,
            CONCAT(m.pet_name_hindi, \' विरूद्ध \', m.res_name_hindi) AS hi_cause_title,
            CASE WHEN h.main_supp_flag = 1 THEN \'main\' ELSE \'suppli\' END AS main_supp_flag_text, 
            h.roster_id');

        $builder->join('main m', 'h.diary_no = m.diary_no');
        $builder->join('master.roster r', 'r.id = h.roster_id');
        $builder->join('master.roster_bench rb', 'r.bench_id = rb.id');
        $builder->join('master.master_bench mb', 'rb.bench_id = mb.id');
        $builder->join('cl_printed cl', 'cl.next_dt = h.next_dt AND cl.part = h.clno AND cl.roster_id = h.roster_id AND cl.display = \'Y\'', 'INNER');
        $builder->join('conct ct', 'm.diary_no = ct.diary_no AND ct.list = \'Y\'', 'LEFT');
        $builder->join('master.casetype ct2', 'm.active_casetype_id = ct2.casecode', 'LEFT');

        $subquery1 = $db->table('heardt')
            ->select('diary_no, next_dt, judges, roster_id, mainhead, board_type, clno, brd_slno, main_supp_flag, \'Heardt\' AS list_status, listed_ia')
            ->where('next_dt', $list_dt)
            ->where('board_type', $board_type_cc_handeled)
            ->whereIn('roster_id', explode(',', $rosterStr))
            ->where('main_supp_flag', $main_suppl);

        $subquery2 = $db->table('last_heardt')
            ->select('diary_no, next_dt, judges, roster_id, mainhead, board_type, clno, brd_slno, main_supp_flag, \'Last_Heardt\' AS list_status, listed_ia')
            ->where('next_dt', $list_dt)
            ->where('board_type', $board_type_cc_handeled)
            ->whereIn('roster_id', explode(',', $rosterStr))
            ->where('main_supp_flag', $main_suppl)
            ->where('bench_flag IS NULL');

        $subquery3 = $db->table('drop_note')
            ->select('diary_no, cl_date AS next_dt, \'\' AS judges, roster_id, mf AS mainhead, \'\' AS board_type, part AS clno, clno AS brd_slno, \'\' AS main_supp_flag, \'Deleted\' AS list_status, \'\' AS listed_ia')
            ->where('cl_date', $list_dt)
            ->whereIn('roster_id', explode(',', $rosterStr))
            ->where('display', 'Y');

        $builder->groupStart()
            ->addSubquery($subquery1)
            ->addSubquery($subquery2)
            ->addSubquery($subquery3)
            ->groupEnd();

        $builder->where('r.display', 'Y')
            ->where('rb.display', 'Y')
            ->where('mb.display', 'Y')
            ->whereIn('r.courtno', explode(',', $courtArrayStr))
            ->orderBy('r.courtno')
            ->orderBy('h.brd_slno')
            ->orderBy("(CASE WHEN m.conn_key = m.diary_no THEN \'0000-00-00\' ELSE \'99\' END) ASC")
            ->orderBy("(CASE WHEN ct.ent_dt IS NOT NULL THEN ct.ent_dt ELSE \'999\' END) ASC")
            ->orderBy("(CAST(SUBSTRING(m.diary_no, -4) AS INTEGER)) ASC")
            ->orderBy("(CAST(LEFT(m.diary_no, LENGTH(m.diary_no) - 4) AS INTEGER)) ASC");

        return $builder->get()->getResultArray();
    }


    public function get_remark($diary_no)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('brdrem');
        $builder->select('remark')
            ->where('diary_no', $diary_no);
        $query = $builder->get();
        $result = $query->getRowArray();
        return $result;
    }

    public function get_brd_slno($diary_no)
    {
        $db = \Config\Database::connect();
        return $item_no = $db->table('heardt')
            ->select('brd_slno')
            ->where('diary_no', $diary_no)
            ->get()
            ->getResultArray();
    }



    public function get_subheading($diary_no)
    {
        $query = $this->db->table('heardt AS h')
            ->select('h.subhead AS stagecode, s.stagename, s.stagename_hindi')
            ->join('master.subheading AS s', 'h.subhead = s.stagecode')
            ->where('s.display', 'Y')
            ->where('h.diary_no', $diary_no)
            ->distinct()
            ->get();

        // Print the last generated SQL query
        //echo $this->db->getLastQuery(); // This will output the query

        return $query->getResult(); // Return the result of the query
    }

    public function get_docs($diary_no)
    {
        return $doc_result = $this->db->query("
        SELECT * FROM (
            SELECT h.diary_no, d.docnum, d.docyear, d.doccode1, 
                (CASE WHEN dm.doccode1 = 19 THEN d.other1 ELSE d.docdesc END) AS docdesp, 
                d.other1, d.iastat
            FROM heardt h
            INNER JOIN docdetails d ON d.diary_no = h.diary_no 
            INNER JOIN master.docmaster dm ON dm.doccode1 = d.doccode1 AND dm.doccode = d.doccode
            WHERE h.diary_no = ? AND d.doccode = 8 AND dm.display = 'Y' 
            AND POSITION(CONCAT(d.docnum, d.docyear) IN REPLACE(TRIM(BOTH ',' FROM listed_ia), '/', '')) > 0
        ) AS a
    ", [$diaryNo])->getResultArray();
    }


    public function get_res_pets($diaryNo)
    {
        $query = $db->query("
            SELECT a.*, 
                STRING_AGG(a.name || CASE WHEN pet_res = 'R' THEN grp_adv END, '' ORDER BY adv_type DESC, pet_res_no ASC) AS r_n,
                STRING_AGG(a.name || CASE WHEN pet_res = 'P' THEN grp_adv END, '' ORDER BY adv_type DESC, pet_res_no ASC) AS p_n,
                STRING_AGG(a.name || CASE WHEN pet_res = 'I' THEN grp_adv END, '' ORDER BY adv_type DESC, pet_res_no ASC) AS i_n,
                STRING_AGG(a.name || CASE WHEN pet_res = 'N' THEN grp_adv END, '' ORDER BY adv_type DESC, pet_res_no ASC) AS intervenor,
                STRING_AGG(a.name_hindi || CASE WHEN pet_res = 'R' THEN grp_adv END, '' ORDER BY adv_type DESC, pet_res_no ASC) AS r_n_h,
                STRING_AGG(a.name_hindi || CASE WHEN pet_res = 'P' THEN grp_adv END, '' ORDER BY adv_type DESC, pet_res_no ASC) AS p_n_h,
                STRING_AGG(a.name_hindi || CASE WHEN pet_res = 'I' THEN grp_adv END, '' ORDER BY adv_type DESC, pet_res_no ASC) AS i_n_h,
                STRING_AGG(a.name_hindi || CASE WHEN pet_res = 'N' THEN grp_adv END, '' ORDER BY adv_type DESC, pet_res_no ASC) AS intervenor_h 
            FROM (
                SELECT a.diary_no, b.name, b.mobile, b.name_hindi,
                    STRING_AGG(COALESCE(a.adv, '') ORDER BY CASE WHEN pet_res IN ('I', 'N') THEN 99 ELSE 0 END ASC, adv_type DESC, pet_res_no ASC) AS grp_adv,
                    a.pet_res, a.adv_type, pet_res_no
                FROM advocate a 
                LEFT JOIN master.bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y' 
                WHERE a.diary_no = ? AND a.display = 'Y' 
                GROUP BY a.diary_no, b.name
                ORDER BY CASE WHEN pet_res IN ('I', 'N') THEN 99 ELSE 0 END ASC, adv_type DESC, pet_res_no ASC
            ) a 
            GROUP BY diary_no
        ", [$diaryNo]);

        return $results = $query->getRowArray();
    }


    public function get_header_footer_print($list_dt, $mainhead, $rosterStr, $main_suppl, $court_no, $part)
    {
        $result = [];

        $db = \Config\Database::connect();
        $builder = $db->table('headfooter hf');
        $builder->select('DISTINCT TRIM(hf.h_f_note) AS h_f_note')
            ->join('master.roster r', 'hf.roster_id = r.id')
            ->join('cl_printed cp', 'cp.roster_id = hf.roster_id AND cp.next_dt = ' . $this->db->escape($list_dt) . ' AND cp.display = "Y" AND cp.part = hf.part AND cp.main_supp = ' . (int)$main_suppl)
            ->where('hf.display', 'Y')
            ->where('hf.next_dt', $list_dt)
            ->where('hf.mainhead', $mainhead)
            ->whereIn('hf.roster_id', explode(',', $rosterStr))
            ->where('hf.part', $part)
            ->whereIn('hf.h_f_flag', ['F', 'H'])
            ->where('r.courtno', $court_no)
            ->orderBy('hf.ent_dt');


        $query = $builder->get();


        if ($query->getNumRows() > 0) {
            foreach ($query->getResultArray() as $row) {
                $result[] = [
                    "h_f_note" => trim($row['h_f_note']),
                ];
            }
        }

        return $result;
    }



    public function get_header_footer_print_h($list_dt, $mainhead, $rosterStr, $main_suppl, $court_no, $part)
    {

        $db = \Config\Database::connect();
        $builder = $db->table('headfooter hf');
        $builder->select('DISTINCT TRIM(hf.h_f_note) AS h_f_note')
            ->join('master.roster r', 'hf.roster_id = r.id')
            ->join('cl_printed cp', 'cp.roster_id = hf.roster_id AND cp.next_dt = ' . $this->db->escape($list_dt) . ' AND cp.display = "Y" AND cp.part = hf.part AND cp.main_supp = ' . (int)$main_suppl)
            ->where('hf.display', 'Y')
            ->where('hf.next_dt', $list_dt)
            ->where('hf.mainhead', $mainhead)
            ->whereIn('hf.roster_id', explode(',', $rosterStr))
            ->where('hf.part', $part)
            ->whereIn('hf.h_f_flag', ['F', 'H'])
            ->where('r.courtno', $court_no)
            ->orderBy('hf.ent_dt');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            foreach ($query->getResultArray() as $row) {
                $result[] = [
                    "h_f_note" => trim($row['h_f_note']),
                ];
            }
        }
        return $result;
    }


    public function get_purpose_list($diary_no)
    {
        $db = \Config\Database::connect();
        return $purpose_query = $db->table('heardt AS h')
            ->select('DISTINCT h.listorder AS code, p.purpose, p.purpose_hindi')
            ->join('master.listing_purpose AS p', 'h.listorder = p.code')
            ->where('p.display', 'Y')
            ->where('h.diary_no', $diary_no)
            ->get()
            ->getResultArray();
    }

    public function get_cl_print_category($diary_no)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('mul_category mc');
        $builder->select('subcode1, subcode2, sub_name1, sub_name4 ,sub_name1_hindi ,sub_name4_hindi');
        $builder->join('master.submaster s', 's.id = mc.submaster_id');
        $builder->where('mc.display', 'Y');
        $builder->where('mc.diary_no', $diary_no);
        $query = $builder->get();
        $results = $query->getResultArray();
        return $results;
    }

    public function get_cl_print_advocate($diary_no)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('advocate a');
        $builder->select('a.pet_res_no, a.adv, a.pet_res, CONCAT(b.title, b.name) AS aor_name, b.aor_code, b.name_hindi AS aor_name_hindi');
        $builder->join('master.bar b', 'a.advocate_id = b.bar_id');
        $builder->where('a.diary_no', $diary_no);
        $builder->where('a.display', 'Y');
        $builder->orderBy('a.pet_res');
        $query = $builder->get();
        return $builder->get()->getResultArray();
    }

    public function getRosterWithJudges($roster_ids)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);

        $builder->select('r.id, 
             STRING_AGG(j.jcode ORDER BY j.judge_seniority) AS jcd, 
             STRING_AGG(j.jname ORDER BY j.judge_seniority) AS jnm, 
             j.first_name, 
             j.sur_name, 
             j.title, 
             r.courtno, 
             rb.bench_no, 
             mb.abbr, 
             mb.board_type_mb, 
             r.tot_cases, 
             r.frm_time, 
             r.session, 
             r.if_print_in');

        $builder->join('master.roster_bench rb', 'rb.id = r.bench_id', 'left');
        $builder->join('master.master_bench mb', 'mb.id = rb.bench_id', 'left');
        $builder->join('master.roster_judge rj', 'rj.roster_id = r.id', 'left');
        $builder->join('master.judge j', 'j.jcode = rj.judge_id', 'left');

        $builder->where('j.is_retired !=', 'Y');
        $builder->where('j.display', 'Y');
        $builder->where('rj.display', 'Y');
        $builder->where('rb.display', 'Y');
        $builder->where('mb.display', 'Y');
        $builder->where('r.display', 'Y');
        $builder->whereIn('r.id', $roster_ids); // Using whereIn for multiple IDs

        $builder->groupBy('r.id, j.first_name, j.sur_name, j.title, r.courtno, rb.bench_no, mb.abbr, mb.board_type_mb, r.tot_cases, r.frm_time, r.session, r.if_print_in');
        $builder->orderBy('r.id');
        $builder->orderBy('j.judge_seniority');

        return $builder->get()->getResultArray();
    }

    public function cl_save_json()
    {

        $db = \Config\Database::connect();

        $mainhead_se_data =  session()->get('save_all_mainhead');
        $board_type_se_data =  session()->get('save_all_boardtype');
        $list_dt_se_data =  session()->get('save_all_listdate');
        $main_suppl_se_data =  session()->get('save_all_main_suppli');
        if (isset($mainhead_se_data)) {
            $mainhead = $mainhead_se_data;
        } else {
            return $this->response->setJSON(['status' => 'success', 'message' => "Error: 'save_all_mainhead' not found in session."]);
        }
        if (isset($board_type_se_data)) {
            $board_type = $board_type_se_data;
        } else {
            return $this->response->setJSON(['status' => 'success', 'message' => "Error: 'save_all_boardtype' not found in session."]);
        }
        if (isset($list_dt_se_data)) {
            $list_dt = $list_dt_se_data;
        } else {
            return $this->response->setJSON(['status' => 'success', 'message' => "Error: 'save_all_listdate' not found in session."]);
        }
        if (isset($main_suppl_se_data)) {
            $main_suppl = $main_suppl_se_data;
        } else {
            return $this->response->setJSON(['status' => 'success', 'message' => "Error: 'save_all_main_suppli' not found in session."]);
        }

        if ($board_type == 'CC') {
            $board_type_cc_handeled = 'C';
        } else {
            $board_type_cc_handeled = $board_type;
        }

        if ($main_suppl == 1) {
            $hi_main_suppl = 'मुख्य सूची';
        } else {
            $hi_main_suppl = 'अनुपूरक सूची';
        }
        $courtArray = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 21, 22];

        $courtArrayStr = implode(',', $courtArray);
        // $mainheadarray = "'" . implode("','", $mainhead) . "'";
        if ($mainhead == 'M' and $subheading != "FOR JUDGEMENT" and $subheading != "FOR ORDER") {
            $print_mainhead = "MISCELLANEOUS HEARING";
            $h_print_mainhead = "विविध सूची";
        } else {
            $print_mainhead = "REGULAR HEARING";
            $h_print_mainhead = "नियमित सुनवाई";
        }
        if ($mainhead == 'M' and $subheading != "FOR JUDGEMENT" and $subheading != "FOR ORDER") {
            if ($row['board_type'] == 'C') {
                if ($part_no != "50" and $part_no != "51") {
                    $print_mainhead = "CHAMBER MATTERS";
                }
            } else {
                if ($part_no == "50" or $part_no == "51") {
                } else {
                }
            }
        }
        if ($mainhead == 'L') {
            $print_mainhead = "LOK ADALAT HEARING";
        } else if ($mainhead == 'S') {
            $print_mainhead = "MEDIATION HEARING";
        }

        $cp_print_data = $this->get_cl_print_data($list_dt, $courtArray, $board_type);

        $roster = array();
        foreach ($cp_print_data as $row) {

            $roster[] = $row['roster_id'];
        }
        $rosterStr = implode(',', $roster);

        $noteprintdata  =  $this->get_drop_note_print($list_dt, $mainhead, $roster_id, $diary_no);

        if (!empty($noteprintdata)) {
            foreach ($noteprintdata as $row) {
                $deleted_item_no = $row['clno'];
                $case_no = $row['case_no'];
                $shifted_to = '';
                if ($row['p_r_id'] == 0) {
                    $shifted_to = "-";
                } else {
                    if ($row['p_r_id'] == $row['roster_id']) {
                        $shifted_to .= "Item No. " . $row['p_brd_slno'];
                    } else {
                        $builder = $this->db->table('cl_printed c')
                            ->select('courtno')
                            ->where('id', $row['p_r_id'])
                            ->where('display', 'Y');
                        $query = $builder->get();
                        $rowsqq = $query->getRowArray();

                        $shifted_to .= "Court No. " . $rowsqq['courtno'] . " as Item No. " . $row['p_brd_slno'] . " On " . date('d-m-Y', strtotime($row['p_next_dt']));
                    }
                }
                $reason = $row['nrs'];
                $caseInfo = array(
                    'deleted_item_no' => $deleted_item_no,
                    'deletsqle_reason' => $reason,
                    'shifted_to' => $shifted_to,
                    'diary_no' => $diary_no,
                    'case_no' => $case_no,
                    'hi_delete_reason' =>  $shifted_to,
                    'hi_shifted_to' =>  $reason
                );
            }
            return $caseInfo;
        }

        $courtcasesdata  = $this->get_cl_print_causes($list_dt, $mainhead, $roster_id, $diary_no);

        $output = array();
        $connectedCases = array();
        $connectedItemNo = 1;
        $i = 0;
        $data = array();
        foreach ($courtcasesdata as $row) {

            $main_supp_flag_text = $row['main_supp_flag_text'];
            if ($main_supp_flag_text == "suppli") {
                $supplementary_display_head = "SUPPLEMENTARY LIST";
            }
            $m_f_filno = $row['active_fil_no'];
            $m_f_fil_yr = $row['active_reg_year'];
            $filno_array = explode("-", $m_f_filno);
            if ($filno_array[1] == $filno_array[2]) {
                $fil_no_print = ltrim($filno_array[1], '0');
            } else {
                if ($filno_array[2] != '') {
                    $fil_no_print = ltrim($filno_array[1], '0') . '-' . ltrim($filno_array[2], '0');
                } else {
                    $fil_no_print = ltrim($filno_array[1], '0');
                }
            }


            $comlete_fil_no_prt = $row['casename_hindi'] . ' ' . $fil_no_print . "/" . $m_f_fil_yr;
            if (empty($row['case_no'])) {

                $comlete_fil_no_prt = "";
            }

            $translatedBoardType = $this->translateBoardType($row['mainhead'], $row['board_type_mb']);
            $translateid = $this->translateid($row['mainhead'], $row['board_type_mb']);
            $translatedBoardTypehindi = $this->translateBoardTypehindi($row['mainhead'], $row['board_type_mb']);

            if ($row['pno'] == 2) {
                $pet_name = $row['pet_name'] . " AND ANR.";
            } else if ($row['pno'] > 2) {
                $pet_name = $row['pet_name'] . " AND ORS.";
            } else {
                $pet_name = $row['pet_name'];
            }
            if ($row['rno'] == 2) {
                $res_name = $row['res_name'] . " AND ANR.";
            } else if ($row['rno'] > 2) {
                $res_name = $row['res_name'] . " AND ORS.";
            } else {
                $res_name = $row['res_name'];
            }
            if ($row['if_sclsc'] == 1) {
                $if_sclsc = "(SCLSC)";
            } else {
                $if_sclsc = "";
            }
            $part = $row['clno'];

            $diary_no = $row['diary_no'];
            $last_4_digits_yr = substr($diary_no, -4);
            $remaining_digits_dno = substr($diary_no, 0, -4);


            $remark_result = $this->get_remark($diary_no);
            $remarks = array();

            // $remark = isset($remarks['remark']) ? $remarks['remark'] : '';

            // $remark_query = "SELECT remark FROM brdrem WHERE diary_no = $diary_no";

            // $remark_result = mysql_query($remark_query) or die(mysql_error());


            $court_no = $row['court_no'];
            if ($court_no == 1) {
                $chief_name = "CHIEF JUSTICE'S COURT";
                $chief_name_h = "मुख्य न्यायाधीश का न्यायालय";
            } else {
                $chief_name = '';
                $chief_name_h = '';
            }
            if ($court_no > 1 and $court_no <= 20) {
                $print_court_no = $print_in_court_no . "COURT NO. : " . $court_no;
            }

            $specific_court_no = $court_no;
            $roster_id_of_diary = $row['roster_id'];
            $resultArray = $this->get_header_footer_print($list_dt, $mainhead, $roster_id_of_diary, $main_suppl, $court_no, $part);


            $specific_court_no = $court_no;
            $resultArray_h =  $this->get_header_footer_print_h($list_dt, $mainhead, $roster_id_of_diary, $main_suppl, $court_no, $part);

            foreach ($remark_result as $remark_row) {
                $remarks = $remark_row['remark'];
            }

            $category = array();
            $cateogry_result =  $this->get_cl_print_category($diary_no);
            foreach ($cateogry_result as $cateogry_row) {
                $category = array(
                    'subcode1' => $cateogry_row['subcode1'],
                    'subcode2' => $cateogry_row['subcode2'],
                    'sub_name1' => $cateogry_row['sub_name1'],
                    'sub_name4' => $cateogry_row['sub_name4'],
                    'hi_sub_name1' =>  $cateogry_row['sub_name1_hindi'],
                    'hi_sub_name4' =>  $cateogry_row['sub_name4_hindi']


                );
            }

            $query_rs = "SELECT tentative_section($diary_no) AS section_name";

            if ($query_rs) {

                $row['query_rs'] = $section_ten_row["section_name"];
            } else {
                $row['section_name'] = '';
            }


            $aor_result = $this->get_cl_print_advocate($diary_no);

            $aor_details = array();
            $aor_details_h = array();

            foreach ($aor_result as $aor_row) {
                $aor_details[] = array(

                    "pet_res_no" => $aor_row["pet_res_no"],
                    "adv" => $aor_row["adv"],
                    "pet_res" => $aor_row["pet_res"],
                    "aor_name" => $aor_row["aor_name"],
                    "aor_code" => $aor_row["aor_code"],
                    "hi_adv" =>  $aor_row["adv"],
                    "hi_pet_res" =>  $aor_row["pet_res"],
                    "hi_aor_name" =>  $aor_row["aor_name_hindi"]
                );
            }

            $rosterdatresult =  $this->getRosterWithJudges($roster_id_of_diary);

            $registrar_details = array();

            foreach ($rosterdatresult as $$aor_row1) {

                $bench_court = $aor_row1['courtno'];
                $print_in_court_no = "";
                $board_type_mb = $row_ros['board_type_mb'];
                $bench_judge_name = stripcslashes(str_replace(",", "<br/>", $row_ros['jnm']));
                $bench_reg_name = $row_ros['first_name'] . " " . $row_ros['sur_name'] . ", " . $row_ros['title'];
                $bench_session = $row_ros['session'];
                if (empty($aor_row1['frm_time'])) {
                    if ($bench_court == "21" || $bench_court == "22") {
                        $bench_time = '11:00 AM';
                    } else {
                        $bench_time = '10:30 AM';
                    }
                } else {
                    $bench_time = $aor_row1['frm_time'];
                }

                if ($bench_court == "21") {
                    $print_court_no = "Registrar Court No. 1";
                } else if ($bench_court == "22") {
                    $print_court_no = "Registrar Court No. 2";
                }
                if ($bench_court == "1") {
                    $print_court_no = "CHIEF JUSTICE'S COURT";
                } else {
                    $print_court_no = '';
                }
                if ($bench_court > 1 and $bench_court <= 20) {
                    $print_court_no = $print_in_court_no . "COURT NO. : " . $bench_court;
                }
                if ($bench_court == "21" || $bench_court == "22") {
                    $registrar_details[] = array(
                        "registrar_name" => $aor_row1['first_name'] . " " . $aor_row1['sur_name'] . ", " . $aor_row1['title'],
                        "court_name" => $print_court_no
                    );
                }
                if ($board_type_mb != "R") {
                    $bench_j_nmae = $bench_judge_name;
                } else {
                    $bench_r_nmae = $bench_reg_name;
                }
                if ($bench_session == "After Regular Bench") {
                    $bench_sess = "THIS BENCH WILL ASSEMBLE AFTER THE NORMAL COURT IS OVER";
                }
            }

            $rs_lct = $this->get_lowerct($diary_no);
            $ro_lct_details = array();

            foreach ($rs_lct as $ro_lct) {
                $ro_lct_details[] = " IN " . $ro_lct['type_sname'] . " - " . $ro_lct['lct_caseno'] . "/" . $ro_lct['lct_caseyear'] . ", ";
            }

            $item_result =  $this->get_brd_slno($diary_no);

            $item_no_array = array();

            foreach ($item_result as $item_row) {
                $item_no_array = $item_row['brd_slno'];
            }

            $subheading_result =  $this->get_subheading($diary_no);

            $subheading_data = array();

            foreach ($subheading_result as $subheading_row) {
                $subheading_data[] = array(
                    "stagecode" => $subheading_row["stagecode"],
                    "stagename" => $subheading_row["stagename"],

                );
                $hi_stagename = $subheading_row["stagename_hindi"];
            }

            $doc_result =  $this->get_docs($diary_no);
            $documents = array();

            foreach ($doc_result as $docsrow) {
                $documents[] = array(
                    "docnum" => $docsrow["docnum"],
                    "docyear" => $docsrow["docyear"],
                    "doccode1" => $docsrow["doccode1"],
                    "docdesp" => $docsrow["docdesp"],
                    "other1" => $docsrow["other1"],
                    "iastat" => $docsrow["iastat"],
                    "hi_docdesp" =>  $docsrow["docdesp"],
                    "hi_other1" => $docsrow["other1"],
                    "hi_iastat" => $docsrow["iastat"]
                );
            }
            $first_jcd_cc = $row['judge_code'];
            $first_jcd_cc_values = explode(',', $first_jcd_cc);
            $header_data = array();
            $header_data_h = array();

            $value = $first_jcd_cc_values[0];



            if ($mainhead == 'F') {
                $lines = array(
                    'Chronology is based on the date of initial filing.'
                );

                $lines1 = array(
                    'Chronology is based on the date of initial filing.'
                );
            } else {
                $lines = array();
                $lines1 = array();
            }


            if ($board_type == 'CC') {
                $header_data = array();
                $header_data_h = array();
                $split_judges_name = explode(",", $row['listed_before']);
                $cc_header = "LIST OF CURATIVE & REVIEW PETITIONS (BY CIRCULATION) IN THE CHAMBERS OF " . str_replace("\n", '\"', $split_judges_name[0]);
                $lines = array($cc_header);
                foreach ($lines as $line) {
                    $header_data[] = (object) array('header_name' => $line);
                }
                $header_data_h = array();
                $lines1 = array($cc_header);
                foreach ($lines1 as $lines1) {
                    $header_data_h[] = (object) array('header_name' => $lines1);
                }
            }
            if ($value == '280' && $mainhead == 'F') {
                $header_data = array();
                $header_data_h = array();

                array_push($lines, 'Parties to file list of dates and brief note of submissions not exceeding three pages, two days before the date of listing.');
                foreach ($lines as $line) {
                    $header_data[] = (object) array('header_name' => $line);
                }
                $header_data_h = array();
                array_push($lines1, 'Parties to file list of dates and brief note of submissions not exceeding three pages, two days before the date of listing.');


                foreach ($lines1 as $lines1) {
                    $header_data_h[] = (object) array('header_name' => $lines1);
                }
            }
            if ($value == '280' && $mainhead == 'M') {
                $header_data = array();
                $header_data_h = array();

                array_push($lines, 'Fresh matters including the pass over fresh matters will be taken up before After Notice matters.');
                foreach ($lines as $line) {
                    $header_data[] = (object) array('header_name' => $line);
                }
                $header_data_h = array();
                array_push($lines1, 'Fresh matters including the pass over fresh matters will be taken up before After Notice matters.');


                foreach ($lines1 as $lines1) {
                    $header_data_h[] = (object) array('header_name' => $lines1);
                }
            }
            if ($value == '270' && $mainhead == 'F') {
                $header_data = array();
                $header_data_h = array();

                array_push($lines, 'NOTE :- NO REQUEST FOR PASS OVER OR ADJOURNMENT WILL BE ENTERTAINED IN ITEM NOS. 101 TO 105. IN THE EVENT THE PARTIES ARE NOT REPRESENTED WHEN THE MATTERS ARE CALLED OUT, THE COURT WILL HEAR AND DECIDE THE MATTERS IN THEIR ABSENCE.');
                foreach ($lines as $line) {
                    $header_data[] = (object) array('header_name' => $line);
                }
                $header_data_h = array();
                array_push($lines1, 'NOTE :- NO REQUEST FOR PASS OVER OR ADJOURNMENT WILL BE ENTERTAINED IN ITEM NOS. 101 TO 105. IN THE EVENT THE PARTIES ARE NOT REPRESENTED WHEN THE MATTERS ARE CALLED OUT, THE COURT WILL HEAR AND DECIDE THE MATTERS IN THEIR ABSENCE.');


                foreach ($lines1 as $lines1) {
                    $header_data_h[] = (object) array('header_name' => $lines1);
                }
            }
            if ($value == '219') {
                $header_data = array();
                $header_data_h = array();

                array_push(
                    $lines,
                    'Whenever written submissions are directed to be filed by the Court in any proceeding, advocates and parties in person are requested to email a soft copy in a pdf form on or before the stipulated date to the following email id :',
                    'cmvc.dyc@gmail.com',
                    'The soft copies which are emailed should not be scanned copies of printed submissions. No other documents other than written submissions should be filed in this email.'
                );

                foreach ($lines as $line) {
                    $header_data[] = (object) array('header_name' => $line);
                }


                $header_data_h = array();
                array_push(
                    $lines1,
                    'Whenever written submissions are directed to be filed by the Court in any proceeding, advocates and parties in person are requested to email a soft copy in a pdf form on or before the stipulated date to the following email id :',
                    'cmvc.dyc@gmail.com',
                    'The soft copies which are emailed should not be scanned copies of printed submissions. No other documents other than written submissions should be filed in this email.'
                );

                foreach ($lines1 as $lines1) {
                    $header_data_h[] = (object) array('header_name' => $lines1);
                }
            }


            if ($value == '281') {
                $header_data = array();
                $header_data_h = array();
                array_push(
                    $lines,
                    'Whenever written submissions are directed to be filed by the Court in any proceeding, advocates and parties in person are requested to email a soft copy in a pdf form on or before the stipulated date to the following email id :',
                    'cmvc.hk@gmail.com',
                    'The soft copies which are emailed should not be scanned copies of printed submissions. No other documents other than written submissions should be filed in this email.'
                );


                foreach ($lines as $line) {
                    $header_data[] = (object) array('header_name' => $line);
                }


                $header_data_h = array();
                array_push(
                    $lines1,
                    'Whenever written submissions are directed to be filed by the Court in any proceeding, advocates and parties in person are requested to email a soft copy in a pdf form on or before the stipulated date to the following email id :',
                    'cmvc.hk@gmail.com',
                    'The soft copies which are emailed should not be scanned copies of printed submissions. No other documents other than written submissions should be filed in this email.'
                );

                foreach ($lines1 as $lines1) {
                    $header_data_h[] = (object) array('header_name' => $lines1);
                }
            }

            $rowadv =  $this->get_res_pets($diary_no);
            $advData = array();

            if (($rowadv) > 0) {
                $radvname = strtoupper($rowadv["r_n"]);
                $padvname = strtoupper($rowadv["p_n"]);
                $impldname = strtoupper($rowadv["i_n"]);
                $intervenorname = strtoupper($rowadv["intervenor"]);
                $radvname_h = strtoupper($rowadv["r_n_h"]);
                $padvname_h = strtoupper($rowadv["p_n_h"]);
                $impldname_h = strtoupper($rowadv["i_n_h"]);
                $intervenorname_h = strtoupper($rowadv["intervenor_h"]);
                $mergedNames = strtoupper($rowadv["r_n"])  . " " . strtoupper($rowadv["i_n"]) . " " . strtoupper($rowadv["intervenor"]);
                $mergedNames_h = strtoupper($rowadv["r_n_h"])  . " " . strtoupper($rowadv["i_n_h"]) . " " . strtoupper($rowadv["intervenor_h"]);
            }


            if ($board_type_mb != "R" and $part_no != "50" and $part_no != "51") {
                $heading = '[IT WILL BE APPRECIATED IF THE LEARNED ADVOCATES ON RECORD DO NOT SEEK ADJOURNMENT IN THE MATTERS LISTED BEFORE ALL THE COURTS IN THE CAUSE LIST]';
                $h_heading = 'यह सराहनीय होगा यदि, रिकॉर्ड पर विद्वान अधिवक्ता कारण सूची में सभी अदालतों के समक्ष सूचीबद्ध मामलों में स्थगन की मांग नहीं करते हैं।';
            }

            $new_format = date('Y-m-d', strtotime($list_dt));
            $cause_list_date = "DAILY CAUSE LIST FOR DATED: $new_format";
            $h_cause_list_date = "दैनिक कारण सूची दिनांक : $new_format";

            $purpose_result =  $this->get_purpose_list($diary_no);

            foreach ($purpose_result as $purpose_row) {
                $purpose = $purpose_row["purpose"];
                $hi_purpose = $purpose_row["purpose_hindi"];
            }

            if ($row['board_type_mb'] == $board_type_se_data && $row['mainhead'] == $mainhead_se_data) {

                if ($row['diary_no'] == $row['conn_key'] || $row['conn_key'] == '' || $row['conn_key'] == null || $row['conn_key'] == 0) {

                    $data[$i]['main_suppli'] = $row['main_supp_flag_text'];
                    $data[$i]['hi_main_suppl'] = implode(', ', array(str_replace('"', '\"', $hi_main_suppl)));

                    if (!empty($header_data)) {
                        $data[$i]['header'] = str_replace('"', '\"', $header_data);
                    } else {
                        $data[$i]['header'] = null;
                    }
                    if (!empty($header_data_h)) {
                        $data[$i]['hi_header'] = str_replace('"', '\"', $header_data_h);
                    } else {
                        $data[$i]['hi_header'] = null;
                    }
                    $data[$i]['heading'] = str_replace("\n", '\"', $heading);
                    $data[$i]['hi_heading'] = str_replace("\n", '\"', $h_heading);
                    if ($main_supp_flag_text == "suppli") {
                        $data[$i]['supplementary_display_head'] = $supplementary_display_head;
                    } else {
                        $data[$i]['supplementary_display_head'] = '';
                    }
                    $data[$i]['note'] =  str_replace("\n", '\"', $resultArray);
                    $data[$i]['hi_note'] =  str_replace("\n", '\"', $resultArray_h);
                    $data[$i]['cause_list_for_date'] = $cause_list_date;
                    $data[$i]['hi_cause_list_for_date'] = $h_cause_list_date;
                    $data[$i]['print_mainhead'] = $print_mainhead;
                    $data[$i]['hi_print_mainhead'] = $h_print_mainhead;
                    $data[$i]['list_mainhead'] = $translatedBoardType;
                    $data[$i]['hi_list_mainhead'] = $translatedBoardTypehindi;
                    $data[$i]['list_mainhead_id'] = $translateid;
                    $data[$i]['court_no'] = $row['court_no'];
                    if ($court_no == 1) {
                        $data[$i]['court_no_display'] = $chief_name;
                    } elseif ($court_no == '21') {
                        $data[$i]['court_no_display'] = 'Registrar Court No. 1';
                    } elseif ($court_no == '22') {
                        $data[$i]['court_no_display'] = 'Registrar Court No. 2';
                    } else {
                        $data[$i]['court_no_display'] = 'COURT NO. : ' . $row['court_no'];
                    }
                    if ($court_no == 1) {
                        $data[$i]['hi_court_no_display'] = $chief_name_h;
                    } elseif ($court_no == '21') {
                        $data[$i]['hi_court_no_display'] = 'प्रबंधक';
                    } elseif ($court_no == '22') {
                        $data[$i]['hi_court_no_display'] = 'प्रबंधक';
                    } else {
                        $data[$i]['hi_court_no_display'] = 'COURT NO. : ' . $row['court_no'];
                    }
                    $data[$i]['diary_no'] = $row['diary_no'];
                    $data[$i]['dno'] = $remaining_digits_dno;
                    $data[$i]['dyr'] = $last_4_digits_yr;
                    if (empty($row['case_no'])) {
                        $data[$i]['case_no'] = 'Diary No. ' . substr_replace($row['diary_no'], '-', -4, 0);;
                    } else {
                        $data[$i]['case_no'] = $row['case_no'];
                    }
                    if (empty($comlete_fil_no_prt)) {
                        $data[$i]['hi_case_no'] = 'डायरी नंबर ' . substr_replace($row['diary_no'], '-', -4, 0);
                    } else {
                        $data[$i]['hi_case_no'] = $comlete_fil_no_prt;
                    }
                    // if ($court_no == '21') {
                    //     $data[$i]['listed_before'] = 'SH. H. SHASHIDHARA SHETTY, REGISTRAR';
                    // } elseif ($court_no == '22') {
                    //     $data[$i]['listed_before'] = 'SH. VIVEK SAXENA, REGISTRAR';
                    // } else {
                    $data[$i]['listed_before'] = str_replace("\n", '\"', $row['listed_before']);
                    //}
                    // if ($court_no == '21') {
                    //     $data[$i]['hi_listed_before'] = 'रजिस्टर (कोर्ट)';
                    // } elseif ($court_no == '22') {
                    //     $data[$i]['hi_listed_before'] = 'रजिस्टर (कोर्ट एंड बिल्डिंग)';
                    // } else {
                    $data[$i]['hi_listed_before'] = str_replace("\n", '\"', $row['listed_before_hindi']);
                    //}
                    $data[$i]['bench_time'] = str_replace("\n", '\"', $bench_time);
                    $data[$i]['listed_before_judge_code'] = $row['judge_code'];
                    $data[$i]['item_no'] = $item_no_array;
                    $data[$i]['ia_description'] = str_replace("\n", '\"', $ia_description_details);
                    $data[$i]['hi_ia_description'] = str_replace("\n", '\"', $hi_ia_description);
                    $data[$i]['rs_lct'] = str_replace("\n", '\"', $ro_lct_details);
                    $data[$i]['hi_rs_lct'] = str_replace("\n", '\"', $ro_lct_details);
                    $data[$i]['next_dt'] = $list_dt;
                    if ($row['list'] == 'Deleted') {
                        $data[$i]['list_status'] = 'Deleted';
                        $deleted_info = get_drop_note_print($list_dt, $row['mainhead'], $roster_id_of_diary, $diary_no);
                    } else {
                        $data[$i]['list_status'] = 'Listed';
                        $deleted_info = '';
                    }
                    if ($row['list'] == 'Deleted') {
                        $data[$i]['hi_list_status'] = 'hi_Deleted';
                    } else {
                        $data[$i]['hi_list_status'] = 'hi_Listed';
                    }
                    $data[$i]['delete_info'] = str_replace("\n", '\"', $deleted_info);
                    $data[$i]['cause_title'] = str_replace("\n", '\"',  $pet_name . ' Versus ' . $res_name);
                    $data[$i]['hi_cause_title'] = str_replace("\n", '\"', $row['hi_cause_title']);
                    $data[$i]['section_name'] = str_replace("\n", '\"',  $row['section_name']);
                    if (!empty($row['section_name'])) {
                        $data[$i]['hi_section_name'] = str_replace("\n", '\"',   $row['section_name']);
                    } else {
                        $data[$i]['hi_section_name'] = '';
                    }
                    $data[$i]['if_sclsc'] = str_replace("\n", '\"',  $if_sclsc);
                    $data[$i]['board_remark'] = str_replace("\n", "", $remarks);
                    if (!empty($remarks)) {
                        $data[$i]['hi_board_remark'] = str_replace("\n", "",  $remarks);
                    } else {
                        $data[$i]['hi_board_remark'] = '';
                    }
                    $data[$i]['stagename'] = str_replace("\n", '\"',  $subheading_data[0]['stagename']);
                    $data[$i]['hi_stagename'] = str_replace("\n", '\"',  $hi_stagename);
                    if ($purpose == 'Mention Memo') {
                        $data[$i]['purpose'] = str_replace("\n", '\"', $purpose);
                    } else {
                        $data[$i]['purpose'] = '';
                    }
                    if ($hi_purpose == 'मेमो का उल्लेख करें') {
                        $data[$i]['hi_purpose'] = str_replace("\n", '\"', $hi_purpose);
                    } else {
                        $data[$i]['hi_purpose'] = '';
                    }
                    if (!empty($relief)) {
                        $data[$i]['relief'] = '';
                    } else {
                        $data[$i]['relief'] = '';
                    }
                    $data[$i]['category'] = str_replace("\n", '\"', $category);

                    if ($board_type == 'CC') {
                        $data[$i]['Advocate'] = '';
                        $data[$i]['petitioner_Advocate'] = '';
                        $data[$i]['hi_petitioner_Advocate'] = '';
                        $data[$i]['respondent_advocate'] = '';
                        $data[$i]['hi_respondent_advocate'] = '';
                    } else {
                        $data[$i]['Advocate'] = str_replace("\n", '\"', $aor_details);
                        $data[$i]['petitioner_Advocate'] = str_replace("\n", '\"', $padvname);
                        $data[$i]['hi_petitioner_Advocate'] = str_replace("\n", '\"',  $padvname_h);
                        $data[$i]['respondent_advocate'] = str_replace("\n", '\"', trim($mergedNames));
                        $data[$i]['hi_respondent_advocate'] = str_replace("\n", '\"', trim($mergedNames_h));
                    }


                    $con_no = 1;
                    $tempVar = $i;
                    $con_no1 = 1;
                    $i++;
                } else {
                    $data2 = array();
                    if ($con_no == 1) {
                        $data[$tempVar]['connected_cases'] = array();
                        $connected_item_no = 1;
                    }
                    $data2['main_suppli'] = $row['main_supp_flag_text'];
                    $data2['hi_main_suppl'] = implode(', ', array(str_replace('"', '\"', $hi_main_suppl)));
                    $data2['list_mainhead'] = $translatedBoardType;
                    $data2['hi_list_mainhead'] =  $translatedBoardType;
                    if ($main_supp_flag_text == "suppli") {
                        $data2['supplementary_display_head'] = $supplementary_display_head;
                    } else {
                        $data2['supplementary_display_head'] = '';
                    }
                    $data2['list_mainhead_id'] = $translateid;
                    $data2['court_no'] = $row['court_no'];
                    if ($court_no == 1) {
                        $data2['court_no_display'] = $chief_name;
                    } elseif ($court_no == 21) {
                        $data2['court_no_display'] = 'Registrar Court No. 1';
                    } elseif ($court_no == 22) {
                        $data2['court_no_display'] = 'Registrar Court No. 2';
                    } else {
                        $data2['court_no_display'] = 'COURT NO. : ' . $row['court_no'];
                    }
                    if ($court_no == 1) {
                        $data2['hi_court_no_display'] = $chief_name_h;
                    } elseif ($court_no == '21') {
                        $data2['hi_court_no_display'] = 'प्रबंधक';
                    } elseif ($court_no == '22') {
                        $data2['hi_court_no_display'] = 'प्रबंधक';
                    } else {
                        $data2['hi_court_no_display'] = 'COURT NO. : ' . $row['court_no'];
                    }
                    $data2['diary_no'] = $row['diary_no'];
                    $data2['dno'] = $remaining_digits_dno;
                    $data2['dyr'] = $last_4_digits_yr;
                    if (empty($row['case_no'])) {
                        $data2['case_no'] = 'Diary No. ' . substr_replace($row['diary_no'], '-', -4, 0);
                    } else {
                        $data2['case_no'] = $row['case_no'];
                    }
                    if (empty($comlete_fil_no_prt)) {
                        $data2['hi_case_no'] = 'डायरी नंबर ' . substr_replace($row['diary_no'], '-', -4, 0);
                    } else {
                        $data2['hi_case_no'] = $comlete_fil_no_prt;
                    }

                    $data2['listed_before'] = str_replace("\n", '\"', $row['listed_before']);

                    $data2['hi_listed_before'] = str_replace("\n", '\"', $row['listed_before_hindi']);


                    // $data2['bench_time'] = str_replace("\n", '\"', $bench_time);
                    // $data2['listed_before_judge_code'] = $row['judge_code'];
                    // $data2['ia_description'] = str_replace("\n", '\"', $ia_description_details);
                    // $data2['hi_ia_description'] = str_replace("\n", '\"', $hi_ia_description);
                    // $data2['rs_lct'] = str_replace("\n", '\"', $ro_lct_details);
                    // $data2['hi_rs_lct'] = str_replace("\n", '\"', $ro_lct_details);

                    $data2['bench_time'] = replaceNewlines($bench_time);
                    $data2['listed_before_judge_code'] = $row['judge_code'];
                    $data2['ia_description'] = replaceNewlines($ia_description_details);
                    $data2['hi_ia_description'] = replaceNewlines($hi_ia_description);
                    $data2['rs_lct'] = replaceNewlines($ro_lct_details);
                    $data2['hi_rs_lct'] = replaceNewlines($ro_lct_details);

                    if ($row['list'] == 'Deleted') {
                        $data2['list_status'] = 'Deleted';
                        $deleted_info = get_drop_note_print($list_dt, $row['mainhead'], $roster_id_of_diary, $diary_no);
                    } else {
                        $data2['list_status'] = 'Listed';
                        $deleted_info = '';
                    }
                    if ($row['list'] == 'Deleted') {
                        $data2['hi_list_status'] = 'hi_Deleted';
                    } else {
                        $data2['hi_list_status'] = 'hi_Listed';
                    }
                    $data2['delete_info'] = str_replace("\n", '\"', $deleted_info);
                    $data2['next_dt'] = $list_dt;
                    if ($row['list'] === 'Heardt') {
                        $data2['list_status'] = $row['list'];
                    }
                    if ($row['list'] === 'Heardt') {
                        $data2['hi_list_status'] =  $row['list'];
                    }
                    $data2['cause_title'] = str_replace("\n", '\"',  $pet_name . ' Versus ' . $res_name);
                    $data2['hi_cause_title'] = str_replace("\n", '\"', $row['hi_cause_title']);
                    $data2['section_name'] = str_replace("\n", '\"',  $row['section_name']);
                    if (!empty($row['section_name'])) {
                        $data2['hi_section_name'] = str_replace("\n", '\"',   $row['section_name']);
                    } else {
                        $data2['hi_section_name'] = '';
                    }
                    $data2['if_sclsc'] = str_replace("\n", '\"',  $if_sclsc);
                    $data2['board_remark'] = str_replace("\n", "", $remarks);
                    if (!empty($remarks)) {
                        $data2['hi_board_remark'] = str_replace("\n", "",  $remarks);
                    } else {
                        $data2['hi_board_remark'] = '';
                    }
                    $data2['connected_item_no'] =   $con_no1++;
                    $data2['stagename'] = str_replace("\n", '\"', $subheading_data[0]['stagename']);
                    $data2['stagename'] = str_replace("\n", '\"', $hi_stagename);
                    if ($purpose == 'Mention Memo') {
                        $data2['purpose'] = str_replace("\n", '\"', $purpose);
                    } else {
                        $data2['purpose'] = '';
                    }
                    if ($hi_purpose == 'मेमो का उल्लेख करें') {
                        $data2['hi_purpose'] = str_replace("\n", '\"', $hi_purpose);
                    } else {
                        $data2['hi_purpose'] = '';
                    }
                    if (!empty($relief)) {
                        $data2['relief'] = '';;
                    } else {
                        $data2['relief'] = '';
                    }

                    if ($board_type == 'CC') {
                        $data2['category'] = '';
                        $data2['Advocate'] = '';
                        $data2['petitioner_Advocate'] = '';
                        $data2['hi_petitioner_Advocate'] = '';
                        $data2['respondent_advocate'] = '';
                        $data2['hi_respondent_advocate'] = '';
                    } else {
                        $data2['category'] = str_replace("\n", '\"', $category);
                        $data2['Advocate'] = str_replace("\n", '\"', $aor_details);
                        $data2['petitioner_Advocate'] = str_replace("\n", '\"', $padvname);
                        $data2['hi_petitioner_Advocate'] = str_replace("\n", '\"',  $padvname_h);
                        $data2['respondent_advocate'] = str_replace("\n", '\"', trim($mergedNames));
                        $data2['hi_respondent_advocate'] = str_replace("\n", '\"', trim($mergedNames_h));
                    }


                    $data[$tempVar]['connected_cases'][] = $data2;
                    $con_no++;
                }
            }
        }
        $file_path = $mainhead . "_" . $board_type . "_" . $main_suppl;
        $path_dir = WRITEPATH . "home/judgment/cl/$list_dt/";
        $path = $path_dir;
        $filePath = $path . $file_path . ".json";
        $json_result = json_encode($data, JSON_PRETTY_PRINT);
        if (file_put_contents($filePath, $json_result) !== false) {
        } else {
        }
    }



    public function print_prevoius_CSV($list_dt, $board_type, $mainhead, $main_suppl)
    {

        $db = \Config\Database::connect();

        $builder = $db->table('heardt h')
            ->select('
                b.mobile, 
                b.email, 
                b.name AS advocate_name, 
                IFNULL((SELECT GROUP_CONCAT(abbreviation ORDER BY judge_seniority SEPARATOR "#") 
                        FROM judge WHERE display = "Y" AND is_retired != "Y" 
                        AND FIND_IN_SET(jcode, h.judges)), "") AS bench,
                CAST(courtno AS SIGNED) AS courtno,
                h.brd_slno AS item_no,
                IF(reg_no_display = "", CONCAT("Diary No. ", h.diary_no), CONCAT("Case No. ", reg_no_display)) AS Case_No,
                (CASE 
                    WHEN pno = 2 THEN CONCAT(m.pet_name, " AND ANR.") 
                    WHEN pno > 2 THEN CONCAT(m.pet_name, " AND ORS.") 
                    ELSE m.pet_name 
                END) AS petitioner,
                (CASE 
                    WHEN rno = 2 THEN CONCAT(m.res_name, " AND ANR.") 
                    WHEN rno > 2 THEN CONCAT(m.res_name, " AND ORS.") 
                    ELSE m.res_name 
                END) AS repondent
            ')
            ->join('main m', 'm.diary_no = h.diary_no')
            ->join('advocate a', 'a.diary_no = m.diary_no AND a.display = "Y" AND a.advocate_id != 0', 'left')
            ->join('master.bar b', 'b.bar_id = a.advocate_id AND b.isdead != "Y" AND LENGTH(b.mobile) = "10" AND SUBSTR(b.mobile, 1, 1) NOT BETWEEN "0" AND "6" AND b.mobile IS NOT NULL AND b.if_aor = "Y"', 'left')
            ->join('master.roster r', 'r.id = h.roster_id', 'left')
            ->join('cl_printed p', 'p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = "Y"', 'left')
            ->where('a.diary_no IS NOT NULL')
            ->where('b.bar_id IS NOT NULL')
            ->where('r.id IS NOT NULL')
            ->where('h.mainhead', $mainhead)
            ->where('h.board_type', $board_type)
            ->where('h.brd_slno > 0')
            ->where('h.main_supp_flag', $main_suppl)
            ->where('h.next_dt', $list_dt)
            ->groupBy('b.mobile, m.diary_no, h.next_dt')
            ->orderBy('courtno')
            ->orderBy('judge_seniority')
            ->orderBy('judge_seniority2')
            ->orderBy('h.brd_slno')
            ->orderBy("(IF(conn_key = h.diary_no, '0000-00-00', 99)) ASC")
            ->orderBy("(CAST(SUBSTRING(m.diary_no, -4) AS SIGNED)) ASC")
            ->orderBy("(CAST(LEFT(m.diary_no, LENGTH(m.diary_no) - 4) AS SIGNED)) ASC");

        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }


    public function field_sel_ros_jgs()
    {
        $db = \Config\Database::connect();

        $subquery = $this->db->table('heardt')
            ->select('roster_id, judges')
            ->where('mainhead', 'M')
            ->where('board_type', 'J')
            ->where('next_dt >= CURRENT_DATE')
            ->whereIn('main_supp_flag', ['1', '2'])
            ->where('roster_id > 0')
            ->groupBy('roster_id, judges')
            ->getCompiledSelect();

        $builder = $this->db->table('heardt h');
        $builder->select('string_agg(j.first_name || \' \' || j.sur_name, \', \' ORDER BY j.judge_seniority) AS jnm, h.roster_id, h.judges');
        $builder->join('master.roster_judge rj', 'rj.roster_id = h.roster_id', 'left');
        $builder->join('master.judge j', 'j.jcode = rj.judge_id', 'left');
        $builder->join("($subquery) a", 'a.roster_id = h.roster_id', 'left');
        $builder->where('j.is_retired !=', 'Y')
            ->where('j.display', 'Y')
            ->where('rj.display', 'Y')
            ->groupBy('h.roster_id, h.judges');
        $query = $builder->get();
        $results = $query->getResultArray();
        return $results;
    }

    public function get_cl_print_benches_from_roster_P($list_dt, $board_type, $mainhead)
    {

        $db = \Config\Database::connect();
        $option = '';


        $m_f = ($mainhead == 'M') ? '1' : '2';

        $builder = $this->db->table('master.roster r');
        $builder->select('r.id, 
            STRING_AGG(j.jcode::text, \',\' ORDER BY j.judge_seniority) AS jcd, 
            STRING_AGG(CONCAT(j.first_name, \' \', j.sur_name), \',\' ORDER BY j.judge_seniority) AS jnm, 
            rb.bench_no, mb.abbr, r.tot_cases, r.courtno, mb.board_type_mb');

        $builder->join('master.roster_bench rb', 'rb.id = r.bench_id', 'left');
        $builder->join('master.master_bench mb', 'mb.id = rb.bench_id', 'left');
        $builder->join('master.roster_judge rj', 'rj.roster_id = r.id', 'left');
        $builder->join('master.judge j', 'j.jcode = rj.judge_id', 'left');

        $builder->where('j.is_retired !=', 'Y');
        $builder->where('j.display', 'Y');
        $builder->where('rj.display', 'Y');
        $builder->where('rb.display', 'Y');
        $builder->where('mb.display', 'Y');
        $builder->where('r.display', 'Y');
        $builder->where('r.m_f', $m_f);

        if ($board_type == 'R') {
            $builder->where('r.from_date', '0000-00-00');
        } else {
            $builder->where('r.to_date', $list_dt);
        }

        if ($board_type == 'C') {
            $builder->whereIn('mb.board_type_mb', ['C', 'CC']);
        } else {
            $builder->where('mb.board_type_mb', $board_type);
        }

        $builder->groupBy('r.id, rb.bench_no, mb.abbr, r.tot_cases, r.courtno, mb.board_type_mb');
        $builder->orderBy('r.courtno');
        $builder->orderBy('r.id');
        // $builder->orderBy('j.judge_seniority');
        $builder->orderBy('STRING_AGG(j.judge_seniority::text, \',\' ORDER BY j.judge_seniority)');
        echo $builder->getCompiledSelect();
        die();
        $query = $builder->get();
        $result = $query->getResultArray();

        if (!empty($result)) {
            foreach ($result as $row) {
                $option .= ' <option value="' . $row["jcd"] . "|" . $row["id"] . '" > ' . $row["jnm"] . ' </option>';
            }
        } else {
            $option .= '<option value="0" selected>EMPTY</option>';
        }

        return $option;
    }



    public function dgerrt()
    {


        $subQuery1 = $this->db->table('heardt_webuse h')
            ->select('DISTINCT r.id, ro.session, ro.frm_time, board_type_mb, ro.courtno, r.jcd, r.next_dt, r.clno, r.mainhead, r.main_supp_flag, 
                CONCAT(r.mainhead, "_", IF(board_type_mb="CC", "CC", board_type_mb), "_", r.main_supp_flag, "_", r.clno, "_", r.id, ".pdf") AS pdf')
            ->join('master.roster ro', 'ro.id = r.id')
            ->join('master.roster_judge rj', 'rj.roster_id = r.id')
            ->join('master.roster_bench rb', 'rb.id = ro.bench_id')
            ->join('master.master_bench mb', 'mb.id = rb.bench_id')
            ->join('master.judge j', 'j.jcode = rj.judge_id')
            ->join('cl_printed clp', 'clp.roster_id = r.id AND clp.display = "Y" AND clp.next_dt = r.next_dt AND clp.part = r.clno AND clp.m_f = r.mainhead')
            ->where('j.is_retired != "Y"')
            ->where('j.display = "Y"')
            ->where('rj.display = "Y"')
            ->where('ro.courtno > 0')
            ->where('clp.roster_id IS NOT NULL')
            ->groupBy('r.id, r.next_dt, r.mainhead, r.clno, r.jcd, r.main_supp_flag');

        $subQuery2 = $this->db->table('advance_cl_printed')
            ->select('NULL as courtno, next_dt, NULL AS jm1, NULL AS jm2, NULL AS jf1, NULL AS jf2, NULL AS cm1, NULL AS cm2, NULL AS sm1, NULL AS sm2, NULL AS cc1, NULL AS cc2, NULL AS rm1, NULL AS rm2, NULL AS rf1, NULL AS rf2')
            ->where('next_dt > CURDATE() OR (next_dt = CURDATE() AND TIMEDIFF(TIME("17:30:00"), TIME(NOW())) > "00:00:00")')
            ->groupBy('courtno, next_dt');


        $finalQuery = $this->db->query("
            SELECT courtno, next_dt,
            GROUP_CONCAT(CASE WHEN (board_type_mb='J' AND mainhead='M' AND main_supp_flag=1) THEN pdf END ORDER BY clno) AS jm1,
            GROUP_CONCAT(CASE WHEN (board_type_mb='J' AND mainhead='M' AND main_supp_flag=2) THEN pdf END ORDER BY clno) AS jm2,
            GROUP_CONCAT(CASE WHEN (board_type_mb='J' AND mainhead='F' AND main_supp_flag=1) THEN pdf END ORDER BY clno) AS jf1,
            GROUP_CONCAT(CASE WHEN (board_type_mb='J' AND mainhead='F' AND main_supp_flag=2) THEN pdf END ORDER BY clno) AS jf2,
            GROUP_CONCAT(CASE WHEN (board_type_mb IN ('C') AND mainhead='M' AND main_supp_flag=1) THEN pdf END ORDER BY clno) AS cm1,
            GROUP_CONCAT(CASE WHEN (board_type_mb IN ('C') AND mainhead='M' AND main_supp_flag=2) THEN pdf END ORDER BY clno) AS cm2,
            GROUP_CONCAT(CASE WHEN (board_type_mb IN ('S') AND mainhead='M' AND main_supp_flag=1) THEN pdf END ORDER BY clno) AS sm1,
            GROUP_CONCAT(CASE WHEN (board_type_mb IN ('S') AND mainhead='M' AND main_supp_flag=2) THEN pdf END ORDER BY clno) AS sm2,
            GROUP_CONCAT(CASE WHEN (board_type_mb IN ('CC') AND mainhead='M' AND main_supp_flag=1) THEN pdf END ORDER BY clno) AS cc1,
            GROUP_CONCAT(CASE WHEN (board_type_mb IN ('CC') AND mainhead='M' AND main_supp_flag=2) THEN pdf END ORDER BY clno) AS cc2,
            GROUP_CONCAT(CASE WHEN (board_type_mb='R' AND mainhead='M' AND main_supp_flag=1) THEN pdf END ORDER BY clno) AS rm1,
            GROUP_CONCAT(CASE WHEN (board_type_mb='R' AND mainhead='M' AND main_supp_flag=2) THEN pdf END ORDER BY clno) AS rm2,
            GROUP_CONCAT(CASE WHEN (board_type_mb='R' AND mainhead='F' AND main_supp_flag=1) THEN pdf END ORDER BY clno) AS rf1,
            GROUP_CONCAT(CASE WHEN (board_type_mb='R' AND mainhead='F' AND main_supp_flag=2) THEN pdf END ORDER BY clno) AS rf2 
            FROM ($subQuery1) AS t1 
            GROUP BY courtno, next_dt 
            UNION 
            SELECT * FROM ($subQuery2) AS a 
            ORDER BY next_dt, courtno
        ");

        return $finalQuery->getResultArray();
    }


    // Date 01-10-2024 by Ashutosh
    function getBenches()
    {
        // $sql = "SELECT r.id, GROUP_CONCAT(j.jcode ORDER BY j.judge_seniority) jcd, GROUP_CONCAT(CONCAT(j.first_name,' ',j.sur_name) ORDER   BY j.judge_seniority) jnm, rb.bench_no, mb.abbr, r.tot_cases, r.courtno, mb.board_type_mb FROM roster r 
        // LEFT JOIN roster_bench rb ON rb.id = r.bench_id 
        // LEFT JOIN master_bench mb ON mb.id = rb.bench_id 
        // LEFT JOIN roster_judge rj ON rj.roster_id = r.id 
        // LEFT JOIN judge j ON j.jcode = rj.judge_id 
        // WHERE j.is_retired != 'Y' AND mb.board_type_mb = 'J' AND j.display = 'Y' AND rj.display = 'Y' AND rb.display = 'Y' 
        // AND mb.display = 'Y' AND r.display = 'Y' AND r.m_f = '1' AND r.from_date >= CURDATE() GROUP BY r.id ORDER BY r.courtno, r.id, j.judge_seniority";
        // $res = $this->db->query($sql);
        // return $res;
        $builder = $this->db->table('master.roster r');
        $builder->select('r.id, 
                        STRING_AGG(j.jcode::text, \',\' ORDER BY j.judge_seniority) AS jcd, 
                        STRING_AGG(CONCAT(j.first_name, \' \', j.sur_name), \',\' ORDER BY j.judge_seniority) AS jnm, 
                        rb.bench_no, 
                        mb.abbr, 
                        r.tot_cases, 
                        r.courtno, 
                        mb.board_type_mb')
            ->join('master.roster_bench rb', 'rb.id = r.bench_id', 'left')
            ->join('master.master_bench mb', 'mb.id = rb.bench_id', 'left')
            ->join('master.roster_judge rj', 'rj.roster_id = r.id', 'left')
            ->join('master.judge j', 'j.jcode = rj.judge_id', 'left')
            ->where('j.is_retired !=', 'Y')
            ->where('mb.board_type_mb', 'J')
            ->where('j.display', 'Y')
            ->where('rj.display', 'Y')
            ->where('rb.display', 'Y')
            ->where('mb.display', 'Y')
            ->where('r.display', 'Y')
            ->where('r.m_f', '1')
            ->where('r.from_date >=', date('Y-m-d'))
            ->groupBy('r.id, rb.bench_no, mb.abbr, r.tot_cases, r.courtno, mb.board_type_mb')
            ->orderBy('r.courtno')
            ->orderBy('r.id');
        $query = $builder->get();
        if (count($query->getResultArray()) >= 1) {
            return $query->getResultArray();
        }
        return []; // Return an empty array if no results found
    }

    public function get_cl_printed_roster_id($mainhead, $cldt, $board_type)
    {
        $option = '';
        if ($board_type == '0') {
            $board_type_in = "";
        } else if ($board_type == 'C') {
            $board_type_in = " and (mb.board_type_mb = 'C' OR mb.board_type_mb = 'CC')";
        } else {
            $board_type_in = " and mb.board_type_mb = '$board_type'";
        }


        $sql = "SELECT  * 
                FROM (
                SELECT 
                mb.board_type_mb, 
                r.roster_id, 
                STRING_AGG(j.jcode::text, ',' ORDER BY j.judge_seniority) AS jcd, 
                STRING_AGG(CONCAT(j.first_name, ' ', j.sur_name), ',' ORDER BY j.judge_seniority) AS jnm, 
                courtno 
                FROM cl_printed c 
                LEFT JOIN master.roster r1 ON r1.id = c.roster_id 
                LEFT JOIN master.roster_bench rb ON rb.id = r1.bench_id 
                LEFT JOIN master.master_bench mb ON mb.id = rb.bench_id 
                LEFT JOIN master.roster_judge r ON r.roster_id = c.roster_id 
                LEFT JOIN master.judge j ON j.jcode = r.judge_id 
                WHERE mb.display = 'Y' 
                AND rb.display = 'Y' 
                AND r1.display = 'Y' 
                AND r.display = 'Y' 
                AND c.display = 'Y' 
                $board_type_in
                AND c.m_f = '$mainhead' 
                AND c.next_dt = '$cldt' 
                GROUP BY part, main_supp, r.roster_id, mb.board_type_mb, r1.courtno 
                ORDER BY r1.courtno) a 
            GROUP BY roster_id , a.board_type_mb, a.jcd, a.jnm, a.courtno
            ORDER BY courtno";

        $res1 = $this->db->query($sql);
        if ($res1->getNumRows() >= 1) {
            $result = $res1->getResultArray();
            if (!empty($result)) {
                $option .= '<option value="0" selected>SELECT</option>';
                foreach ($result as $row) {
                    $option .= ' <option value="' . $row["roster_id"] . '" > ' . $row["jnm"] . ' </option>';
                }
            }
        } else {
            $option .= '<option value="0" selected>EMPTY</option>';
        }
        return $option;
    }

    public function get_cl_printed_partno_by_id($mainhead, $list_dt, $roster_id, $board_type)
    {
        $builder = $this->db->table('cl_printed');
        $builder->select('part')
            ->where('m_f', $mainhead)
            ->where('next_dt', $list_dt)
            ->where('roster_id', $roster_id)
            ->groupStart()
            ->where('main_supp', 1)
            ->orWhere('main_supp', 2)
            ->groupEnd()
            ->groupBy('part');
        $results = $builder->get()->getResultArray();
        $option = '';
        if ($results) {
            $option .= '<option value="0" selected>SELECT</option>';
            foreach ($results as $row) {
                $option .= ' <option value="' . $row["part"] . '" > ' . $row["part"] . ' </option>';
            }
        } else {
            $option .= '<option value="0" selected>EMPTY</option>';
        }

        return $option;
    }

    public function get_make_unprint($list_dt, $mainhead, $jud_ros, $part_no)
    {
        $return = [];
        $builder = $this->db->table('cl_printed c');
        $builder->select('id')
            ->where('c.m_f', $mainhead)
            ->where('c.next_dt', $list_dt)
            ->where('c.roster_id', $jud_ros)
            ->where('c.part', $part_no)
            ->where('c.display', 'Y');

        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $return = $query->getRowArray();
        }
        return $return;
    }


    public function get_make_unprint_updt($del_cl_id, $ucode)
    {
        $result = 0;
        $data = [
            'display' => 'N',
            'deleted_by' => $ucode,
            'deleted_on' => date('Y-m-d H:i:s')
        ];
        $this->db->table('cl_printed')->where('id', $del_cl_id)->set($data)->update();
        if ($this->db->affectedRows() > 0) {
            $result = 1;
        }
        return $result;
    }


    public function del_drop_hfnote($list_dt, $mainhead, $part_no, $jud_ros, $drop_note = 'true', $header_footer = 'true')
    {

        if ($drop_note == 'true') {
            $conditions = ['cl_date' => $list_dt, 'part' => $part_no, 'roster_id' => $jud_ros, 'mf' => $mainhead];
            $this->db->table('drop_note')->update(['display' => 'N'], $conditions);
        }
        if ($header_footer == 'true') {
            $headerConditions = ['next_dt' => $list_dt, 'part' => $part_no, 'roster_id' => $jud_ros, 'mainhead' => $mainhead];
            $this->db->table('headfooter')->update(['display' => 'N'], $headerConditions);
        }
    }

    public function getRosterTitle($roster_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('master.roster r');

        $builder->select([
            'r.id',
            "STRING_AGG(j.jcode::TEXT, ',' ORDER BY j.judge_seniority) AS jcd",
            "STRING_AGG(j.jname, ',' ORDER BY j.judge_seniority) AS jnm",
            "STRING_AGG(j.first_name, ',' ORDER BY j.judge_seniority) AS first_names",
            "STRING_AGG(j.sur_name, ',' ORDER BY j.judge_seniority) AS sur_names",
            "STRING_AGG(j.title, ',' ORDER BY j.judge_seniority) AS titles",
            'r.courtno',
            'rb.bench_no',
            'mb.abbr',
            'mb.board_type_mb',
            'r.tot_cases',
            'r.frm_time',
            'r.session',
            'r.if_print_in'
        ]);

        // Joining Tables
        $builder->join('master.roster_bench rb', 'rb.id = r.bench_id', 'left');
        $builder->join('master.master_bench mb', 'mb.id = rb.bench_id', 'left');
        $builder->join('master.roster_judge rj', 'rj.roster_id = r.id', 'left');
        $builder->join('master.judge j', 'j.jcode = rj.judge_id', 'left');

        // Conditions
        $builder->where('j.is_retired !=', 'Y');
        $builder->where('j.display', 'Y');
        $builder->where('rj.display', 'Y');
        $builder->where('rb.display', 'Y');
        $builder->where('mb.display', 'Y');
        $builder->where('r.display', 'Y');
        $builder->where('r.id', $roster_id);

        // Group By
        $builder->groupBy(['r.id', 'r.courtno', 'rb.bench_no', 'mb.abbr', 'mb.board_type_mb', 'r.tot_cases', 'r.frm_time', 'r.session', 'r.if_print_in']);

        // Order By
        $builder->orderBy('r.id');
        // echo $builder->getCompiledSelect();
        // die();

        $query = $builder->get();

        return $query->getRowArray();
    }

    public function getWorkingDays($list_dt)
    {
        $query = $this->db->table('master.sc_working_days')
            ->where('working_date', $list_dt)
            ->where('is_nmd', 0)
            ->where('display', 'Y')
            ->get();
        return $query->getRowArray();
    }
    public function getRelif($list_dt, $board_type, $mainhead, $roster_id, $part_no, $board_type_in)
    {
        if ($mainhead != 'F') {
            $sub_head_name = "s.stagename";
            $leftjoin_subhead = "LEFT JOIN master.subheading s ON s.stagecode = h.subhead AND s.display = 'Y' AND s.listtype = '$mainhead'";
            $leftjoin_submaster = "";
        } else {
            $sub_head_name = "sm.sub_name1, sm.sub_name2, sm.sub_name3, sm.sub_name4";
            $leftjoin_subhead = "LEFT JOIN category_allottment c ON  h.subhead = c.submaster_id AND c.ros_id = '$roster_id' AND c.display = 'Y'";

            $leftjoin_submaster = "LEFT JOIN master.submaster sm ON h.subhead = sm.id AND sm.display = 'Y'";
        }

        $sql = "SELECT 
                (SELECT STRING_AGG(DISTINCT h2.main_supp_flag::TEXT, ',') 
                FROM heardt h2 
                WHERE h2.next_dt = '$list_dt' 
                AND h2.board_type = '$board_type' 
                AND h2.mainhead = '$mainhead'
                AND h2.roster_id = '$roster_id' 
                AND h2.clno = $part_no 
                AND h2.brd_slno > 0
                ) AS multi_main_supp_flag,
                m.c_status, 
                m.relief, 
                u.name, 
                us.section_name, 
                h.*, 
                l.purpose, 
                c1.short_description, 
                m.active_fil_no, 
                m.active_reg_year, 
                m.casetype_id, 
                m.active_casetype_id, 
                m.ref_agency_state_id, 
                m.reg_no_display, 
                EXTRACT(YEAR FROM m.fil_dt) AS fil_year, 
                m.fil_no, 
                m.fil_dt, 
                m.fil_no_fh, 
                m.reg_year_fh AS fil_year_f, 
                m.mf_active, 
                m.pet_name, 
                m.res_name, 
                m.pno, 
                m.rno, 
                m.if_sclsc, 
                m.diary_no_rec_date, 
                $sub_head_name
            FROM heardt h
            INNER JOIN main m ON m.diary_no = h.diary_no 
            LEFT JOIN master.casetype c1 ON m.active_casetype_id = c1.casecode
            LEFT JOIN master.listing_purpose l ON l.code = h.listorder
   
            $leftjoin_submaster
            $leftjoin_subhead          
            LEFT JOIN master.users u ON u.usercode = m.dacode 
                                    AND u.display = 'Y'
            LEFT JOIN master.usersection us ON us.id = u.section  
            LEFT JOIN conct ct ON m.diary_no = ct.diary_no 
                                AND ct.list = 'Y'   
            WHERE h.next_dt = '$list_dt'  
            $board_type_in
            AND h.mainhead = '$mainhead' 
            AND h.roster_id = '$roster_id' 
            AND h.clno = $part_no
            AND h.brd_slno > 0 
            AND l.display = 'Y'  
            AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2) 
            GROUP BY h.diary_no, m.c_status, m.relief, u.name, us.section_name, 
                    l.purpose, c1.short_description, m.active_fil_no, m.active_reg_year, 
                    m.casetype_id, m.active_casetype_id, m.ref_agency_state_id, 
                    m.reg_no_display, m.fil_dt, m.fil_no, m.fil_no_fh, m.reg_year_fh, 
                    m.mf_active, m.pet_name, m.res_name, m.pno, m.rno, m.if_sclsc, 
                    m.diary_no_rec_date, s.stagename, h.brd_slno, ct.ent_dt, m.diary_no
            ORDER BY 
                h.brd_slno, 
                CASE WHEN h.conn_key = h.diary_no THEN '0000-00-00' ELSE '99' END ASC, 
                ct.ent_dt ASC NULLS LAST,  
                CAST(SUBSTRING(m.diary_no::TEXT FROM LENGTH(m.diary_no::TEXT) - 3 FOR 4) AS INTEGER) ASC, 
                CAST(LEFT(m.diary_no::TEXT, LENGTH(m.diary_no::TEXT) - 4) AS INTEGER) ASC";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

    //     public function getAdv($diary_no)
    //     {
    //         $advsql = "SELECT 
    //     a.diary_no,
    //     STRING_AGG(
    //         a.name || COALESCE(CASE WHEN a.pet_res = 'R' THEN a.grp_adv END, ''), 
    //         '' ORDER BY a.adv_type DESC, a.pet_res_no ASC
    //     ) AS r_n,
    //     STRING_AGG(
    //         a.name || COALESCE(CASE WHEN a.pet_res = 'P' THEN a.grp_adv END, ''), 
    //         '' ORDER BY a.adv_type DESC, a.pet_res_no ASC
    //     ) AS p_n,
    //     STRING_AGG(
    //         a.name || COALESCE(CASE WHEN a.pet_res = 'I' THEN a.grp_adv END, ''), 
    //         '' ORDER BY a.adv_type DESC, a.pet_res_no ASC
    //     ) AS i_n,
    //     STRING_AGG(
    //         a.name || COALESCE(CASE WHEN a.pet_res = 'N' THEN a.grp_adv END, ''), 
    //         '' ORDER BY a.adv_type DESC, a.pet_res_no ASC
    //     ) AS intervenor 
    // FROM 
    //     (SELECT 
    //         a.diary_no, 
    //         b.name, 
    //         b.mobile, 
    //         STRING_AGG(
    //             COALESCE(a.adv, ''), 
    //             '' ORDER BY 
    //                 CASE WHEN a.pet_res IN ('I', 'N') THEN 99 ELSE 0 END, 
    //                 a.adv_type DESC, 
    //                 a.pet_res_no ASC
    //         ) AS grp_adv, 
    //         a.pet_res, 
    //         a.adv_type, 
    //         a.pet_res_no
    //     FROM advocate a 
    //     LEFT JOIN master.bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y' 
    //     WHERE a.diary_no = '$diary_no' AND a.display = 'Y' 
    //     GROUP BY a.diary_no, b.name, b.mobile, a.pet_res, a.adv_type, a.pet_res_no
    //     ) a 
    // GROUP BY a.diary_no";

    //         $query = $this->db->query($advsql);
    //         $result = $query->getRowArray();
    //         return $result;
    //     }

    public function getAdv($diary_no)
    {
        $subquery = $this->db->table('advocate a')
            ->select("
            a.diary_no, 
            b.name, 
            b.mobile, 
            STRING_AGG(
                COALESCE(a.adv, ''), '' 
                ORDER BY 
                CASE WHEN a.pet_res IN ('I', 'N') THEN 99 ELSE 0 END, 
                a.adv_type DESC, 
                a.pet_res_no ASC
            ) AS grp_adv, 
            a.pet_res, 
            a.adv_type, 
            a.pet_res_no", false)
            ->join('master.bar b', 'a.advocate_id = b.bar_id AND b.isdead != \'Y\'', 'left')
            ->where('a.diary_no', $diary_no)
            ->where('a.display', 'Y')
            ->groupBy(['a.diary_no', 'b.name', 'b.mobile', 'a.pet_res', 'a.adv_type', 'a.pet_res_no'])
            ->getCompiledSelect();

        $query = $this->db->query("
        SELECT 
            a.diary_no,
            STRING_AGG(
                a.name || COALESCE(CASE WHEN a.pet_res = 'R' THEN a.grp_adv END, ''), 
                '' ORDER BY a.adv_type DESC, a.pet_res_no ASC
            ) AS r_n,
            STRING_AGG(
                a.name || COALESCE(CASE WHEN a.pet_res = 'P' THEN a.grp_adv END, ''), 
                '' ORDER BY a.adv_type DESC, a.pet_res_no ASC
            ) AS p_n,
            STRING_AGG(
                a.name || COALESCE(CASE WHEN a.pet_res = 'I' THEN a.grp_adv END, ''), 
                '' ORDER BY a.adv_type DESC, a.pet_res_no ASC
            ) AS i_n,
            STRING_AGG(
                a.name || COALESCE(CASE WHEN a.pet_res = 'N' THEN a.grp_adv END, ''), 
                '' ORDER BY a.adv_type DESC, a.pet_res_no ASC
            ) AS intervenor 
        FROM ({$subquery}) a 
        GROUP BY a.diary_no
    ");

        return $query->getRowArray();
    }

    public function getSectionName1($diary_no)
    {
        $query = $this->db->query("SELECT tentative_section(?) AS section_name", [$diary_no]);
        return $query->getRowArray();
    }
    public function getDocNumYear($diary_no)
    {
        $sql_dc = "SELECT * FROM (SELECT h.diary_no, d.docnum, d.docyear, d.doccode1, 
                    (CASE WHEN dm.doccode1 = 19 THEN other1 ELSE docdesc END) docdesp, 
                    d.other1, d.iastat FROM heardt h
                    INNER JOIN docdetails d ON d.diary_no = h.diary_no 
                    INNER JOIN master.docmaster dm ON dm.doccode1 = d.doccode1 AND dm.doccode = d.doccode
                    WHERE h.diary_no = '$diary_no' AND d.doccode = 8 AND dm.display = 'Y' AND d.iastat = 'P' 
                    AND CAST(CONCAT(d.docnum, d.docyear) AS TEXT) = ANY(string_to_array(REPLACE(REPLACE(REPLACE(h.listed_ia, '/', ''), ' ', ''), ' ', ''), ',')) 
                    -- AND EXISTS (
                    --     SELECT 1 
                    --     FROM unnest(string_to_array(replace(replace(replace(listed_ia, '/', ''), ' ', ''), ' ', ''), ',')) AS ia_item
                    --     WHERE CAST(CONCAT(docnum, docyear) AS INTEGER) = ia_item::INTEGER
                    -- )
                    ) a
                    WHERE docdesp != ''
                    ORDER BY docdesp";

        $query = $this->db->query($sql_dc);
        return $query->getResultArray();
    }

    public function getAllowedUser($ucode)
    {
        $sql_case_updation = "SELECT always_allowed_users 
                        FROM master.case_status_flag 
                        WHERE display_flag = '0' 
                        AND (to_date IS NULL OR to_date = '1970-01-01')  
                        AND flag_name = 'cl_publish' 
                        AND '$ucode'::TEXT = ANY(string_to_array(always_allowed_users, ','))";

        $query = $this->db->query($sql_case_updation);
        return $query->getRowArray();
    }


    public function getShortDescription($diary_no)
    {
        $query = $this->db->table('lowerct a')
            ->select([
                'a.lct_dec_dt',
                'a.lct_caseno',
                'a.lct_caseyear',
                'ct.short_description AS type_sname'
            ])
            ->join('master.casetype ct', 'ct.casecode = a.lct_casetype AND ct.display = \'Y\'', 'left')
            ->where('a.diary_no', $diary_no)
            ->where('a.is_order_challenged', 'Y')
            ->where('a.lw_display', 'Y')
            ->where('a.ct_code', 4)
            ->orderBy('a.lct_dec_dt', 'DESC')
            ->get();

        return $query->getResultArray();
    }
}
