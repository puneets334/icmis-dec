<?php

namespace App\Models\Listing;

use CodeIgniter\Model;


class ListingStatisticsModel extends Model
{

    public function getListingStatistics($list_dt)
    {

        $builder = "SELECT
            (SELECT COUNT(diary_no) FROM advance_allocated aa WHERE next_dt = '".$list_dt."' AND (
                (aa.diary_no::TEXT = aa.conn_key::TEXT) OR aa.conn_key IS NULL 
            )) AS advance_list,
            (SELECT COUNT(diary_no) FROM transfer_old_com_gen_cases t WHERE next_dt_old = '".$list_dt."' AND listtype = 'A' AND (
                (t.diary_no::TEXT = t.conn_key::TEXT) OR t.conn_key IS NULL 
            )) AS advance_elimination;
        ";
        $result = $this->db->query($builder)->getResultArray();
        return $result;
       
    }

    public function getListingStatisticsAdvanceList($list_dt)
    {
        $builder = $this->db->table('(
            SELECT
                m.reg_no_display,
                m.pet_name,
                m.res_name,
                hh.listorder,
                h.*,
                (SELECT MIN(ac.ent_time) FROM advance_cl_printed ac WHERE ac.next_dt = h.next_dt) AS adv_time,
                (SELECT MAX(cl.ent_time) FROM cl_printed cl WHERE cl.next_dt = h.next_dt AND cl.main_supp = 1 AND cl.m_f = \'M\' AND cl.display = \'Y\' AND cl.from_brd_no BETWEEN 1 AND 99) AS final_time
            FROM (
                SELECT
                    t.diary_no,
                    t.next_dt,
                    MAX(t.ent_dt) AS cur_tm
                FROM (
                    SELECT
                        h.diary_no,
                        h.next_dt,
                        h.listorder,
                        h.ent_dt,
                        h.module_id
                    FROM heardt h
                    WHERE
                        h.next_dt = \'' . $list_dt . '\'
                        AND h.mainhead = \'M\'
                        AND h.board_type = \'J\'
                        AND h.clno = 0
                        AND h.main_supp_flag = 0
                    UNION ALL
                    SELECT
                        h.diary_no,
                        h.next_dt,
                        h.listorder,
                        h.ent_dt,
                        h.module_id
                    FROM last_heardt h
                    WHERE
                        h.next_dt = \'' . $list_dt . '\'
                        AND h.mainhead = \'M\'
                        AND h.board_type = \'J\'
                        AND h.clno = 0
                        AND h.main_supp_flag = 0
                        AND (h.bench_flag IS NULL OR h.bench_flag = \'\')
                ) AS t
                GROUP BY t.diary_no, t.next_dt
            ) AS h
            LEFT JOIN advance_allocated aa ON aa.diary_no::TEXT = h.diary_no::TEXT AND aa.next_dt = h.next_dt
            LEFT JOIN transfer_old_com_gen_cases tt ON tt.diary_no::TEXT = h.diary_no::TEXT AND tt.next_dt_old = h.next_dt AND tt.listtype = \'A\'
            LEFT JOIN heardt hh ON hh.diary_no = h.diary_no
            LEFT JOIN main m ON m.diary_no = hh.diary_no
            WHERE
                aa.diary_no IS NULL
                AND tt.diary_no IS NULL
                AND (m.diary_no::TEXT = m.conn_key::TEXT OR m.conn_key IS NULL OR m.conn_key = \'0\')
            GROUP BY m.diary_no, h.diary_no, h.next_dt, m.reg_no_display, m.pet_name, m.res_name, hh.listorder, h.cur_tm
        ) AS z');

        $builder->select('COUNT(z.diary_no) AS total, SUM(CASE WHEN COALESCE(z.listorder, 0) = 32 THEN 1 ELSE 0 END) AS fresh, SUM(CASE WHEN COALESCE(z.listorder, 0) != 32 THEN 1 ELSE 0 END) AS old');
        $builder->where('z.cur_tm BETWEEN z.adv_time AND z.final_time');
        $builder->groupBy('z.next_dt');
       
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function getListingStatisticsFinalIndex($list_dt)
    {
        $subquery1 = $this->db->table('heardt h')
            ->select('h.diary_no, h.next_dt, h.listorder')
            ->join('main m', 'm.diary_no = h.diary_no')
            ->where('h.next_dt', $list_dt)
            ->where('h.mainhead', 'M')
            ->where('h.board_type', 'J')
            ->where('h.clno >', 0)
            ->where('h.main_supp_flag', 1)
            ->where('(cast(m.diary_no as TEXT) = m.conn_key::TEXT OR m.conn_key IS NULL OR m.conn_key = \'0\')');

        $subquery2 = $this->db->table('last_heardt h')
            ->select('h.diary_no, h.next_dt, h.listorder')
            ->join('main m', 'm.diary_no = h.diary_no')
            ->where('h.next_dt', $list_dt)
            ->where('h.mainhead', 'M')
            ->where('h.board_type', 'J')
            ->where('h.clno >', 0)
            ->where('h.main_supp_flag', 1)
            ->where('(h.bench_flag = \'\' OR h.bench_flag IS NULL)')
            ->where('(cast(h.diary_no as TEXT) = h.conn_key::TEXT OR h.conn_key IS NULL OR h.conn_key = \'0\')')
            ->groupBy('h.diary_no, h.next_dt, h.listorder');

        $subquery =  $this->db->table('(' . $subquery1->getCompiledSelect() . ' UNION ALL ' . $subquery2->getCompiledSelect() . ') AS t');

        $builder =  $this->db->table('(' . $subquery->getCompiledSelect() . ') AS t');

        $builder->select('COUNT(t.diary_no) AS total, SUM(CASE WHEN t.listorder = 32 THEN 1 ELSE 0 END) AS fresh, SUM(CASE WHEN t.listorder != 32 THEN 1 ELSE 0 END) AS old');
        $builder->groupBy('t.next_dt');

        // echo $sql = $builder->getCompiledSelect();
        // die();
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function getListedFromAdvance($list_dt){
        $subquery1 = $this->db->table('heardt h')
            ->select('h.diary_no')
            ->where('h.next_dt', $list_dt)
            ->where('h.mainhead', 'M')
            ->where('h.board_type', 'J')
            ->where('h.clno >', 0)
            ->where('h.main_supp_flag', 1)
            ->getCompiledSelect();

        $subquery2 = $this->db->table('last_heardt h')
            ->select('h.diary_no')
            ->where('h.next_dt', $list_dt)
            ->where('h.mainhead', 'M')
            ->where('h.board_type', 'J')
            ->where('h.clno >', 0)
            ->where('h.main_supp_flag', 1)
            ->where('(h.bench_flag = \'\' OR h.bench_flag IS NULL)')
            ->getCompiledSelect();

        $subquery3 = $this->db->table('case_remarks_multiple c')
            ->select('cast(c.diary_no as bigint)')
            ->where('c.cl_date', $list_dt)
            ->where('c.mainhead', 'M')
            ->groupBy('c.diary_no')
            ->getCompiledSelect();

        $unionSubquery = '(' . $subquery1 . ' UNION ' . $subquery2 . ' UNION ' . $subquery3 . ') AS t';

        $builder = $this->db->table('advance_allocated aa');
        $builder->select('COUNT(DISTINCT aa.diary_no) AS listed_from_advance');
        $builder->join($unionSubquery, 'cast(t.diary_no as TEXT) = aa.diary_no::TEXT', 'left');
        $builder->where('t.diary_no IS NOT NULL');
        $builder->where('aa.next_dt', $list_dt);
        $builder->where('(cast(aa.diary_no as TEXT) = aa.conn_key::TEXT OR aa.conn_key IS NULL OR aa.conn_key = \'0\')');
        // echo $sql = $builder->getCompiledSelect();
        // die();
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;

    }
    public function getEliminatedFinalList($list_dt){

        $subquery = $this->db->table('transfer_old_com_gen_cases t')
            ->select('diary_no, next_dt_old, listorder')
            ->where('next_dt_old', $list_dt)
            ->where('listtype', 'F')
            ->where('(cast(t.diary_no as TEXT) = t.conn_key::TEXT OR t.conn_key IS NULL OR t.conn_key = \'0\')')
            ->groupBy('diary_no, next_dt_old, listorder')
            ->getCompiledSelect();

        $builder = $this->db->table('(' . $subquery . ') AS aa');

        $builder->select('COUNT(diary_no) AS total, SUM(CASE WHEN listorder = 32 THEN 1 ELSE 0 END) AS fresh, SUM(CASE WHEN listorder != 32 THEN 1 ELSE 0 END) AS old');
        $builder->groupBy('next_dt_old');
        // echo $sql = $builder->getCompiledSelect();
        // die();
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }
    public function getUpdatedAfterFinalList($list_dt){
        $sql = "SELECT
                    COUNT(z.diary_no) AS total,
                    SUM(CASE WHEN COALESCE(z.listorder, 0) = 32 THEN 1 ELSE 0 END) AS fresh,
                    SUM(CASE WHEN COALESCE(z.listorder, 0) != 32 THEN 1 ELSE 0 END) AS old
                FROM (
                    SELECT
                        m.reg_no_display,
                        m.pet_name,
                        m.res_name,
                        hh.listorder,
                        h.*,
                        (
                            SELECT MAX(cl.ent_time)
                            FROM cl_printed cl
                            WHERE cl.next_dt = h.next_dt
                                AND cl.main_supp = 1
                                AND cl.m_f = 'M'
                                AND cl.display = 'Y'
                                AND cl.from_brd_no BETWEEN 1 AND 99
                        ) AS final_time,
                        (
                            SELECT MAX(cl.ent_time)
                            FROM cl_printed cl
                            WHERE cl.next_dt = h.next_dt
                                AND cl.main_supp = 2
                                AND cl.m_f = 'M'
                                AND cl.display = 'Y'
                                AND cl.from_brd_no BETWEEN 1 AND 99
                        ) AS suppl_time
                    FROM (
                        SELECT
                            t.diary_no,
                            t.next_dt,
                            MAX(t.ent_dt) AS cur_tm
                        FROM (
                            SELECT
                                h.diary_no,
                                h.next_dt,
                                h.ent_dt,
                                h.module_id
                            FROM heardt h
                            WHERE
                                h.next_dt = '".$list_dt."'
                                AND h.mainhead = 'M'
                                AND h.board_type = 'J'
                                AND h.clno = 0
                                AND h.main_supp_flag = 0
                            UNION ALL
                            SELECT
                                h.diary_no,
                                h.next_dt,
                                h.ent_dt,
                                h.module_id
                            FROM last_heardt h
                            WHERE
                                h.next_dt = '".$list_dt."'
                                AND h.mainhead = 'M'
                                AND h.board_type = 'J'
                                AND h.clno = 0
                                AND h.main_supp_flag = 0
                                AND (h.bench_flag = '' OR h.bench_flag IS NULL)
                        ) AS t
                        GROUP BY
                            t.diary_no, t.next_dt
                    ) AS h
                    LEFT JOIN advance_allocated aa ON aa.diary_no::TEXT = h.diary_no::TEXT AND aa.next_dt = h.next_dt
                    LEFT JOIN transfer_old_com_gen_cases tt ON tt.diary_no::TEXT = h.diary_no::TEXT AND tt.next_dt_old = h.next_dt AND tt.listtype = 'A'
                    LEFT JOIN heardt hh ON hh.diary_no = h.diary_no
                    LEFT JOIN main m ON m.diary_no = hh.diary_no
                    WHERE
                        aa.diary_no IS NULL
                        AND tt.diary_no IS NULL
                        AND (m.diary_no::TEXT = m.conn_key::TEXT OR m.conn_key IS NULL OR m.conn_key = '0')
                    GROUP BY
                        m.reg_no_display, m.pet_name, m.res_name, hh.listorder, h.diary_no, h.next_dt, hh.ent_dt, hh.module_id, h.cur_tm
                ) AS z
                WHERE
                    z.cur_tm BETWEEN z.final_time AND z.suppl_time
                GROUP BY
                    z.next_dt;";
        
        $result = $this->db->query($sql)->getResultArray();
        return $result;

    }
    public function getAllocatedSupplementaryList($list_dt){

        $subquery1 = $this->db->table('heardt h')
            ->select('h.diary_no, h.next_dt, h.listorder')
            ->join('main m', 'm.diary_no = h.diary_no')
            ->where('h.next_dt', $list_dt)
            ->where('h.mainhead', 'M')
            ->where('h.board_type', 'J')
            ->where('h.clno >', 0)
            ->where('h.main_supp_flag', 2)
            ->where('(cast(m.diary_no as TEXT) = m.conn_key::TEXT OR m.conn_key IS NULL OR m.conn_key = \'0\')')
            ->getCompiledSelect();

        $subquery2 = $this->db->table('last_heardt h')
            ->select('h.diary_no, h.next_dt, h.listorder')
            ->join('main m', 'm.diary_no = h.diary_no')
            ->where('h.next_dt', $list_dt)
            ->where('h.mainhead', 'M')
            ->where('h.board_type', 'J')
            ->where('h.clno >', 0)
            ->where('h.main_supp_flag', 2)
            ->where('(h.bench_flag = \'\' OR h.bench_flag IS NULL)')
            ->where('(cast(h.diary_no as TEXT) = h.conn_key::TEXT OR h.conn_key IS NULL OR h.conn_key = \'0\')')
            ->groupBy('h.diary_no, h.next_dt, h.listorder')
            ->getCompiledSelect();

        $unionSubquery = '(' . $subquery1 . ' UNION ALL ' . $subquery2 . ')'; // Removed the extra closing parenthesis here.

        $builder = $this->db->table('(' . $unionSubquery . ') AS t');

        $builder->select('COUNT(t.diary_no) AS total, SUM(CASE WHEN t.listorder = 32 THEN 1 ELSE 0 END) AS fresh, SUM(CASE WHEN t.listorder != 32 THEN 1 ELSE 0 END) AS old');
        $builder->groupBy('t.next_dt');

        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;

    }
    public function getEliminatedSupplementaryList($list_dt){

        $subquery = $this->db->table('transfer_old_com_gen_cases t')
        ->select('diary_no, next_dt_old, listorder')
        ->where('next_dt_old', $list_dt)
        ->where('listtype', 'S')
        ->where('(cast(t.diary_no as TEXT) = t.conn_key::TEXT OR t.conn_key IS NULL OR t.conn_key = \'0\')')
        ->groupBy('diary_no, next_dt_old, listorder')
        ->getCompiledSelect();

        $builder = $this->db->table('(' . $subquery . ') AS aa');

        $builder->select('COUNT(diary_no) AS total, SUM(CASE WHEN listorder = 32 THEN 1 ELSE 0 END) AS fresh, SUM(CASE WHEN listorder != 32 THEN 1 ELSE 0 END) AS old');
        $builder->groupBy('next_dt_old');
        // echo $sql = $builder->getCompiledSelect();
        // die();
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }
    public function getUpdatedAfterSupplementaryList($list_dt){
        $supplTimeSubquery = $this->db->table('cl_printed cl')
            ->selectMax('cl.ent_time')
            ->where('cl.next_dt = h.next_dt')
            ->where('cl.main_supp', 2)
            ->where('cl.m_f', 'M')
            ->where('cl.display', 'Y')
            ->where('cl.from_brd_no BETWEEN 1 AND 99')
            ->getCompiledSelect();

        // Inner subquery (h)
        $innerSubquery = $this->db->table('(
            SELECT
                t.diary_no,
                t.next_dt,
                MAX(t.ent_dt) AS cur_tm
            FROM (
                SELECT
                    h.diary_no,
                    h.next_dt,
                    h.ent_dt,
                    h.module_id
                FROM heardt h
                WHERE
                    h.next_dt = \'' . $list_dt . '\'
                    AND h.mainhead = \'M\'
                    AND h.board_type = \'J\'
                    AND h.clno = 0
                    AND h.main_supp_flag = 0
                UNION ALL
                SELECT
                    h.diary_no,
                    h.next_dt,
                    h.ent_dt,
                    h.module_id
                FROM last_heardt h
                WHERE
                    h.next_dt = \'' . $list_dt . '\'
                    AND h.mainhead = \'M\'
                    AND h.board_type = \'J\'
                    AND h.clno = 0
                    AND h.main_supp_flag = 0
                    AND (h.bench_flag = \'\' OR h.bench_flag IS NULL)
            ) AS t
            GROUP BY
                t.diary_no, t.next_dt
        ) AS h');

        // Main subquery (z)
        $mainSubquery = $this->db->table('(' . $innerSubquery->getCompiledSelect() . ') AS h')
            ->select('m.reg_no_display, m.pet_name, m.res_name, hh.listorder, h.*, (' . $supplTimeSubquery . ') AS suppl_time')
            ->join('advance_allocated aa', 'cast(aa.diary_no as TEXT) = h.diary_no::TEXT AND aa.next_dt = h.next_dt', 'left')
            ->join('transfer_old_com_gen_cases tt', 'cast(tt.diary_no as TEXT) = h.diary_no::TEXT AND tt.next_dt_old = h.next_dt AND tt.listtype = \'A\'', 'left')
            ->join('heardt hh', 'hh.diary_no = h.diary_no', 'left')
            ->join('main m', 'm.diary_no = hh.diary_no', 'left')
            ->where('aa.diary_no IS NULL')
            ->where('tt.diary_no IS NULL')
            ->where('(cast(m.diary_no as TEXT) = m.conn_key::TEXT OR m.conn_key IS NULL OR m.conn_key = \'0\')')
            ->groupBy('m.reg_no_display, m.pet_name, m.res_name, hh.listorder, h.diary_no, h.next_dt, hh.ent_dt, hh.module_id, h.cur_tm')
            ->getCompiledSelect();

        // Final query
        $builder = $this->db->table('(' . $mainSubquery . ') AS z');
        $builder->select('COUNT(z.diary_no) AS total, SUM(CASE WHEN COALESCE(z.listorder, 0) = 32 THEN 1 ELSE 0 END) AS fresh, SUM(CASE WHEN COALESCE(z.listorder, 0) != 32 THEN 1 ELSE 0 END) AS old');
        $builder->where('z.cur_tm > z.suppl_time');
        $builder->groupBy('z.next_dt');
        // echo $sql = $builder->getCompiledSelect();
        // die();
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;

    }
    public function listing_statistics_details_al($type, $list_dt)
    {
        $lastListedSubquery = $this->db->table('last_heardt')
        ->select("TO_CHAR(MAX(next_dt), 'DD-MM-YYYY')")
        ->where('cast(diary_no as TEXT) = aa.diary_no::TEXT')
        ->where('clno IS NOT NULL AND clno != 0')
        ->where('brd_slno IS NOT NULL AND brd_slno != 0')
        ->where('roster_id IS NOT NULL AND roster_id != 0')
        ->where('board_type', 'J')
        ->where('mainhead', 'M')
        ->where('(bench_flag = \'\' OR bench_flag IS NULL)')
        ->where('next_dt < aa.next_dt')
        ->getCompiledSelect();

        // Subquery for subhead
        $subheadSubquery = $this->db->table('master.subheading')
            ->select('stagename')
            ->where('stagecode = aa.subhead')
            ->getCompiledSelect();

        // Subquery for purpose
        $purposeSubquery = $this->db->table('master.listing_purpose')
            ->select('purpose')
            ->where('code = aa.listorder')
            ->getCompiledSelect();

        // Subquery for module
        $moduleSubquery = $this->db->table('master.master_module')
            ->select('module_desc')
            ->where('id = h.module_id')
            ->getCompiledSelect();

        $builder = $this->db->table('main m')
            ->select("concat(COALESCE(m.reg_no_display, ''), ' @ ', substring(m.diary_no::TEXT, 1, length(m.diary_no::TEXT) - 4) || '-' || substring(m.diary_no::TEXT, length(m.diary_no::TEXT) - 3)) AS case_no, m.pet_name || ' Vs. ' || m.res_name AS cause_title, (" . $lastListedSubquery . ") AS last_listed, TO_CHAR(aa.next_dt, 'DD-MM-YYYY') AS next_date, (" . $subheadSubquery . ") AS subhead, (" . $purposeSubquery . ") AS purpose, TO_CHAR(h.ent_dt, 'DD-MM-YYYY HH12:MI:SS AM') AS updated_on, (" . $moduleSubquery . ") AS module")
            ->join('advance_allocated aa', 'cast(m.diary_no as TEXT) = aa.diary_no::TEXT', 'inner')
            ->join('heardt h', 'cast(m.diary_no as TEXT) = h.diary_no::TEXT', 'left')
            ->where('aa.next_dt', $list_dt)
            ->where('(cast(aa.diary_no as TEXT) = aa.conn_key::TEXT OR aa.conn_key IS NULL OR aa.conn_key = \'0\')');
        // echo $sql = $builder->getCompiledSelect();
        // die();
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }
    public function listing_statistics_details_ae($type, $list_dt)
    {
        $lastListedSubquery = $this->db->table('last_heardt')
        ->select("TO_CHAR(MAX(next_dt), 'DD-MM-YYYY')")
        ->where('cast(diary_no as TEXT) = aa.diary_no::TEXT')
        ->where('clno IS NOT NULL AND clno != 0')
        ->where('brd_slno IS NOT NULL AND brd_slno != 0')
        ->where('roster_id IS NOT NULL AND roster_id != 0')
        ->where('board_type', 'J')
        ->where('mainhead', 'M')
        ->where('(bench_flag = \'\' OR bench_flag IS NULL)')
        ->where('next_dt < aa.next_dt_old')
        ->getCompiledSelect();

        // Subquery for subhead
        $subheadSubquery = $this->db->table('master.subheading')
            ->select('stagename')
            ->where('stagecode = h.subhead')
            ->getCompiledSelect();

        // Subquery for purpose
        $purposeSubquery = $this->db->table('master.listing_purpose')
            ->select('purpose')
            ->where('code = aa.listorder')
            ->getCompiledSelect();

        // Subquery for module
        $moduleSubquery = $this->db->table('master.master_module')
            ->select('module_desc')
            ->where('id = h.module_id')
            ->getCompiledSelect();

        $builder = $this->db->table('main m')
            ->distinct()
            ->select("concat(COALESCE(m.reg_no_display, ''), ' @ ', substring(m.diary_no::TEXT, 1, length(m.diary_no::TEXT) - 4) || '-' || substring(m.diary_no::TEXT, length(m.diary_no::TEXT) - 3)) AS case_no, m.pet_name || ' Vs. ' || m.res_name AS cause_title, (" . $lastListedSubquery . ") AS last_listed, aa.next_dt_old, TO_CHAR(aa.next_dt_new, 'DD-MM-YYYY') AS next_date, (" . $subheadSubquery . ") AS subhead, (" . $purposeSubquery . ") AS purpose, TO_CHAR(h.ent_dt, 'DD-MM-YYYY HH12:MI:SS AM') AS updated_on, (" . $moduleSubquery . ") AS module")
            ->join('transfer_old_com_gen_cases aa', 'cast(m.diary_no as TEXT) = aa.diary_no::TEXT', 'inner')
            ->join('heardt h', 'cast(m.diary_no as TEXT) = h.diary_no::TEXT', 'left')
            ->where('aa.next_dt_old', $list_dt)
            ->where('(cast(aa.diary_no as TEXT) = aa.conn_key::TEXT OR aa.conn_key IS NULL OR aa.conn_key = \'0\')')
            ->where('listtype', 'A');
        // echo $sql = $builder->getCompiledSelect();
        // die();
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }
    public function listing_statistics_details_au($type, $list_dt)
    {
              // Subquery for last_listed
              $lastListedSubquery = $this->db->table('last_heardt')
                  ->select("TO_CHAR(MAX(next_dt), 'DD-MM-YYYY')")
                  ->where('diary_no = h.diary_no')
                  ->where('clno IS NOT NULL AND clno != 0')
                  ->where('brd_slno IS NOT NULL AND brd_slno != 0')
                  ->where('roster_id IS NOT NULL AND roster_id != 0')
                  ->where('board_type', 'J')
                  ->where('mainhead', 'M')
                  ->where('(bench_flag = \'\' OR bench_flag IS NULL)')
                  ->where('next_dt < h.next_dt')
                  ->getCompiledSelect();
      
              // Subquery for subhead
              $subheadSubquery = $this->db->table('master.subheading')
                  ->select('stagename')
                  ->where('stagecode = h.subhead')
                  ->getCompiledSelect();
      
              // Subquery for purpose
              $purposeSubquery = $this->db->table('master.listing_purpose')
                  ->select('purpose')
                  ->where('code = h.listorder')
                  ->getCompiledSelect();
      
              // Subquery for module
              $moduleSubquery = $this->db->table('master.master_module')
                  ->select('module_desc')
                  ->where('id = h.module_id')
                  ->getCompiledSelect();
      
              // Subquery for adv_time
              $advTimeSubquery = $this->db->table('advance_cl_printed ac')
                  ->select('ac.ent_time')
                  ->where('ac.next_dt = h.next_dt')
                  ->getCompiledSelect();
      
              // Subquery for final_time
              $finalTimeSubquery = $this->db->table('cl_printed cl')
                  ->selectMax('cl.ent_time')
                  ->where('cl.next_dt = h.next_dt')
                  ->where('cl.main_supp', 1)
                  ->where('cl.m_f', 'M')
                  ->where('cl.display', 'Y')
                  ->where('cl.from_brd_no BETWEEN 1 AND 99')
                  ->getCompiledSelect();
      
              // Inner subquery (h)
              $innerSubquery = $this->db->table('(
                  SELECT
                      t.diary_no,
                      t.next_dt,
                      MAX(t.ent_dt) AS cur_tm
                  FROM (
                      SELECT
                          h.diary_no,
                          h.next_dt,
                          h.listorder,
                          h.ent_dt,
                          h.module_id
                      FROM heardt h
                      WHERE
                          h.next_dt = \'' . $list_dt . '\'
                          AND h.mainhead = \'M\'
                          AND h.board_type = \'J\'
                          AND h.clno = 0
                          AND h.main_supp_flag = 0
                      UNION ALL
                      SELECT
                          h.diary_no,
                          h.next_dt,
                          h.listorder,
                          h.ent_dt,
                          h.module_id
                      FROM last_heardt h
                      WHERE
                          h.next_dt = \'' . $list_dt . '\'
                          AND h.mainhead = \'M\'
                          AND h.board_type = \'J\'
                          AND h.clno = 0
                          AND h.main_supp_flag = 0
                          AND (h.bench_flag = \'\' OR h.bench_flag IS NULL)
                  ) AS t
                  GROUP BY
                      t.diary_no, t.next_dt
              ) AS h');
      
              // Main subquery (z)
              $mainSubquery = $this->db->table('(' . $innerSubquery->getCompiledSelect() . ') AS h')
                  ->select('m.reg_no_display, m.pet_name, m.res_name, hh.listorder, h.*, (' . $advTimeSubquery . ') AS adv_time, (' . $finalTimeSubquery . ') AS final_time')
                  ->join('advance_allocated aa', 'cast(aa.diary_no as TEXT) = h.diary_no::TEXT AND aa.next_dt = h.next_dt', 'left')
                  ->join('transfer_old_com_gen_cases tt', 'tt.diary_no = h.diary_no AND tt.next_dt_old = h.next_dt AND tt.listtype = \'A\'', 'left')
                  ->join('heardt hh', 'cast(hh.diary_no as TEXT) = h.diary_no::TEXT', 'left')
                  ->join('main m', 'cast(m.diary_no as TEXT) = hh.diary_no::TEXT', 'left')
                  ->where('aa.diary_no IS NULL')
                  ->where('tt.diary_no IS NULL')
                  ->where('(cast(m.diary_no as TEXT) = m.conn_key::TEXT OR m.conn_key IS NULL OR m.conn_key = \'0\')')
                  ->groupBy('m.diary_no, m.reg_no_display, m.pet_name, m.res_name, hh.listorder, h.diary_no, h.next_dt, hh.ent_dt, hh.module_id, h.cur_tm, adv_time, final_time');
      
              // Final query
              $builder = $this->db->table('main m')
                  ->select("concat(COALESCE(m.reg_no_display, ''), ' @ ', substring(m.diary_no::TEXT, 1, length(m.diary_no::TEXT) - 4) || '-' || substring(m.diary_no::TEXT, length(m.diary_no::TEXT) - 3)) AS case_no, m.pet_name || ' Vs. ' || m.res_name AS cause_title, (" . $lastListedSubquery . ") AS last_listed, TO_CHAR(h.next_dt, 'DD-MM-YYYY') AS next_date, (" . $subheadSubquery . ") AS subhead, (" . $purposeSubquery . ") AS purpose, TO_CHAR(h.ent_dt, 'DD-MM-YYYY HH12:MI:SS AM') AS updated_on, (" . $moduleSubquery . ") AS module")
                  ->join('heardt h', 'm.diary_no = h.diary_no', 'left')
                  ->whereIn('m.diary_no', function($sub) use($mainSubquery) {
                      $sub->distinct()->select('diary_no')->from('(' . $mainSubquery->getCompiledSelect() . ') AS z')->where('cur_tm BETWEEN adv_time AND final_time');
                  });
        // echo $sql = $builder->getCompiledSelect();
        // die();
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }
    public function listing_statistics_details_fl($type, $list_dt)
    {
              $lastListedSubquery = $this->db->table('last_heardt')
              ->select("TO_CHAR(MAX(next_dt), 'DD-MM-YYYY')")
              ->where('diary_no = h.diary_no')
              ->where('clno IS NOT NULL AND clno != 0')
              ->where('brd_slno IS NOT NULL AND brd_slno != 0')
              ->where('roster_id IS NOT NULL AND roster_id != 0')
              ->where('board_type', 'J')
              ->where('mainhead', 'M')
              ->where('(bench_flag = \'\' OR bench_flag IS NULL)')
              ->where('next_dt < h.next_dt')
              ->getCompiledSelect();

          // Subquery for subhead
          $subheadSubquery = $this->db->table('master.subheading')
              ->select('stagename')
              ->where('stagecode = h.subhead')
              ->getCompiledSelect();

          // Subquery for purpose
          $purposeSubquery = $this->db->table('master.listing_purpose')
              ->select('purpose')
              ->where('code = h.listorder')
              ->getCompiledSelect();

          // Subquery for module
          $moduleSubquery = $this->db->table('master.master_module')
              ->select('module_desc')
              ->where('id = h.module_id')
              ->getCompiledSelect();

          // Inner subquery (t)
          $innerSubquery = $this->db->table('(
              SELECT h.diary_no, h.next_dt, h.listorder
              FROM heardt h
              JOIN main m ON cast(m.diary_no as TEXT) = h.diary_no::TEXT
              WHERE h.next_dt = \'' . $list_dt . '\'
                  AND h.mainhead = \'M\'
                  AND h.board_type = \'J\'
                  AND h.clno > 0
                  AND h.main_supp_flag = 1
                  AND (cast(m.diary_no as TEXT) = m.conn_key::TEXT OR m.conn_key IS NULL OR m.conn_key = \'0\')
              UNION
              SELECT h.diary_no, h.next_dt, h.listorder
              FROM last_heardt h
              JOIN main m ON m.diary_no = h.diary_no
              WHERE h.next_dt = \'' . $list_dt . '\'
                  AND h.mainhead = \'M\'
                  AND h.board_type = \'J\'
                  AND h.clno > 0
                  AND h.main_supp_flag = 1
                  AND (h.bench_flag = \'\' OR h.bench_flag IS NULL)
                  AND (cast(h.diary_no as TEXT) = h.conn_key::TEXT OR h.conn_key IS NULL OR h.conn_key = \'0\')
              GROUP BY h.diary_no, h.next_dt, h.listorder
          ) AS t');

          // Main query
          $builder = $this->db->table('main m')
              ->select("concat(COALESCE(m.reg_no_display, ''), ' @ ', substring(m.diary_no::TEXT, 1, length(m.diary_no::TEXT) - 4) || '-' || substring(m.diary_no::TEXT, length(m.diary_no::TEXT) - 3)) AS case_no, m.pet_name || ' Vs. ' || m.res_name AS cause_title, (" . $lastListedSubquery . ") AS last_listed, TO_CHAR(h.next_dt, 'DD-MM-YYYY') AS next_date, (" . $subheadSubquery . ") AS subhead, (" . $purposeSubquery . ") AS purpose, TO_CHAR(h.ent_dt, 'DD-MM-YYYY HH12:MI:SS AM') AS updated_on, (" . $moduleSubquery . ") AS module")
              ->join('heardt h', 'm.diary_no = h.diary_no', 'left')
              ->whereIn('m.diary_no', function($sub) use($innerSubquery) {
                  $sub->select('diary_no')->from('(' . $innerSubquery->getCompiledSelect() . ') AS t');
              });

        // echo $sql = $builder->getCompiledSelect();
        // die();
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }
    public function listing_statistics_details_fe($type, $list_dt)
    {
        $lastListedSubquery = $this->db->table('last_heardt')
          ->select("TO_CHAR(MAX(next_dt), 'DD-MM-YYYY')")
          ->where('cast(diary_no as TEXT) = aa.diary_no::TEXT')
          ->where('clno IS NOT NULL AND clno != 0')
          ->where('brd_slno IS NOT NULL AND brd_slno != 0')
          ->where('roster_id IS NOT NULL AND roster_id != 0')
          ->where('board_type', 'J')
          ->where('mainhead', 'M')
          ->where('(bench_flag = \'\' OR bench_flag IS NULL)')
          ->where('next_dt < aa.next_dt_old')
          ->getCompiledSelect();

        // Subquery for subhead
        $subheadSubquery = $this->db->table('master.subheading')
            ->select('stagename')
            ->where('stagecode = h.subhead')
            ->getCompiledSelect();

        // Subquery for purpose
        $purposeSubquery = $this->db->table('master.listing_purpose')
            ->select('purpose')
            ->where('code = aa.listorder')
            ->getCompiledSelect();

        // Subquery for module
        $moduleSubquery = $this->db->table('master.master_module')
            ->select('module_desc')
            ->where('id = h.module_id')
            ->getCompiledSelect();

        $builder = $this->db->table('main m')
            ->distinct()
            ->select("concat(COALESCE(m.reg_no_display, ''), ' @ ', substring(m.diary_no::TEXT, 1, length(m.diary_no::TEXT) - 4) || '-' || substring(m.diary_no::TEXT, length(m.diary_no::TEXT) - 3)) AS case_no, m.pet_name || ' Vs. ' || m.res_name AS cause_title, (" . $lastListedSubquery . ") AS last_listed, aa.next_dt_old, TO_CHAR(aa.next_dt_new, 'DD-MM-YYYY') AS next_date, (" . $subheadSubquery . ") AS subhead, (" . $purposeSubquery . ") AS purpose, TO_CHAR(h.ent_dt, 'DD-MM-YYYY HH12:MI:SS AM') AS updated_on, (" . $moduleSubquery . ") AS module")
            ->join('transfer_old_com_gen_cases aa', 'cast(m.diary_no as TEXT) = aa.diary_no::TEXT', 'inner')
            ->join('heardt h', 'cast(m.diary_no as TEXT) = h.diary_no::TEXT', 'left')
            ->where('aa.next_dt_old', $list_dt)
            ->where('(cast(aa.diary_no as TEXT) = aa.conn_key::TEXT OR aa.conn_key IS NULL OR aa.conn_key = \'0\')')
            ->where('listtype', 'F');
        // echo $sql = $builder->getCompiledSelect();
        // die();
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }
    public function listing_statistics_details_fu($type, $list_dt)
    {
      
              // Subquery for last_listed
              $lastListedSubquery = $this->db->table('last_heardt')
                  ->select("TO_CHAR(MAX(next_dt), 'DD-MM-YYYY')")
                  ->where('diary_no = h.diary_no')
                  ->where('clno IS NOT NULL AND clno != 0')
                  ->where('brd_slno IS NOT NULL AND brd_slno != 0')
                  ->where('roster_id IS NOT NULL AND roster_id != 0')
                  ->where('board_type', 'J')
                  ->where('mainhead', 'M')
                  ->where('(bench_flag = \'\' OR bench_flag IS NULL)')
                  ->where('next_dt < h.next_dt')
                  ->getCompiledSelect();
      
              // Subquery for subhead
              $subheadSubquery = $this->db->table('master.subheading')
                  ->select('stagename')
                  ->where('stagecode = h.subhead')
                  ->getCompiledSelect();
      
              // Subquery for purpose
              $purposeSubquery = $this->db->table('master.listing_purpose')
                  ->select('purpose')
                  ->where('code = h.listorder')
                  ->getCompiledSelect();
      
              // Subquery for module
              $moduleSubquery = $this->db->table('master.master_module')
                  ->select('module_desc')
                  ->where('id = h.module_id')
                  ->getCompiledSelect();
      
              // Subquery for final_time
              $finalTimeSubquery = $this->db->table('cl_printed cl')
                  ->selectMax('cl.ent_time')
                  ->where('cl.next_dt = h.next_dt')
                  ->where('cl.main_supp', 1)
                  ->where('cl.m_f', 'M')
                  ->where('cl.display', 'Y')
                  ->where('cl.from_brd_no BETWEEN 1 AND 99')
                  ->getCompiledSelect();
      
              // Subquery for suppl_time
              $supplTimeSubquery = $this->db->table('cl_printed cl')
                  ->selectMax('cl.ent_time')
                  ->where('cl.next_dt = h.next_dt')
                  ->where('cl.main_supp', 2)
                  ->where('cl.m_f', 'M')
                  ->where('cl.display', 'Y')
                  ->where('cl.from_brd_no BETWEEN 1 AND 99')
                  ->getCompiledSelect();
      
              // Inner subquery (h)
              $innerSubquery = $this->db->table('(
                  SELECT
                      t.diary_no,
                      t.next_dt,
                      MAX(t.ent_dt) AS cur_tm
                  FROM (
                      SELECT
                          h.diary_no,
                          h.next_dt,
                          h.ent_dt,
                          h.module_id
                      FROM heardt h
                      WHERE
                           h.mainhead = \'M\'
                          AND h.board_type = \'J\'
                          AND h.clno = 0
                          AND h.main_supp_flag = 0
                      UNION ALL
                      SELECT
                          h.diary_no,
                          h.next_dt,
                          h.ent_dt,
                          h.module_id
                      FROM last_heardt h
                      WHERE
                           
                          h.mainhead = \'M\'
                          AND h.board_type = \'J\'
                          AND h.clno = 0
                          AND h.main_supp_flag = 0
                          AND (h.bench_flag = \'\' OR h.bench_flag IS NULL)
                  ) AS t
                  GROUP BY
                      t.diary_no, t.next_dt
              ) AS h');
      
              // Main subquery (z)// h.next_dt = \'' . $listingDate . '\'
                          // AND h.next_dt = \'' . $listingDate . '\'
                          //AND
              $mainSubquery = $this->db->table('(' . $innerSubquery->getCompiledSelect() . ') AS h')
                  ->select('m.reg_no_display, m.pet_name, m.res_name, hh.listorder, h.*, (' . $finalTimeSubquery . ') AS final_time, (' . $supplTimeSubquery . ') AS suppl_time')
                  ->join('advance_allocated aa', 'cast(aa.diary_no as TEXT) = h.diary_no::TEXT AND aa.next_dt = h.next_dt', 'left')
                  ->join('transfer_old_com_gen_cases tt', 'cast(tt.diary_no as TEXT) = h.diary_no::TEXT AND tt.next_dt_old = h.next_dt AND tt.listtype = \'A\'', 'left')
                  ->join('heardt hh', 'cast(hh.diary_no as TEXT) = h.diary_no::TEXT', 'left')
                  ->join('main m', 'cast(m.diary_no as TEXT) = hh.diary_no::TEXT', 'left')
                  ->where('aa.diary_no IS NULL')
                  ->where('tt.diary_no IS NULL')
                  ->where('(cast(m.diary_no as TEXT) = m.conn_key::TEXT OR m.conn_key IS NULL OR m.conn_key = \'0\')')
                  ->groupBy('m.diary_no, m.reg_no_display, m.pet_name, m.res_name, hh.listorder, h.diary_no, h.next_dt, hh.ent_dt, hh.module_id, h.cur_tm, final_time, suppl_time');
      
              // Final query
              $builder = $this->db->table('main m')
                  ->select("concat(COALESCE(m.reg_no_display, ''), ' @ ', substring(m.diary_no::TEXT, 1, length(m.diary_no::TEXT) - 4) || '-' || substring(m.diary_no::TEXT, length(m.diary_no::TEXT) - 3)) AS case_no, m.pet_name || ' Vs. ' || m.res_name AS cause_title, (" . $lastListedSubquery . ") AS last_listed, h.next_dt, TO_CHAR(h.next_dt, 'DD-MM-YYYY') AS next_date, (" . $subheadSubquery . ") AS subhead, (" . $purposeSubquery . ") AS purpose, TO_CHAR(h.ent_dt, 'DD-MM-YYYY HH12:MI:SS AM') AS updated_on, (" . $moduleSubquery . ") AS module")
                  ->join('heardt h', 'm.diary_no = h.diary_no', 'left')
                  ->whereIn('m.diary_no', function($sub) use($mainSubquery) {
                      $sub->distinct()->select('diary_no')->from('(' . $mainSubquery->getCompiledSelect() . ') AS z')->where('cur_tm BETWEEN final_time AND suppl_time');
                  });
                  $builder->limit(1);
        // echo $sql = $builder->getCompiledSelect();
        // die();
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }
    public function listing_statistics_details_sl($type, $list_dt)
    {
        $sql = "SELECT
                concat(COALESCE(m.reg_no_display, ''), ' @ ', substring(m.diary_no::TEXT, 1, length(m.diary_no::TEXT) - 4) || '-' || substring(m.diary_no::TEXT, length(m.diary_no::TEXT) - 3)) AS case_no,
                m.pet_name || ' Vs. ' || m.res_name AS cause_title,
                (
                    SELECT TO_CHAR(MAX(next_dt), 'DD-MM-YYYY')
                    FROM last_heardt
                    WHERE diary_no = h.diary_no
                        AND clno IS NOT NULL AND clno != 0
                        AND brd_slno IS NOT NULL AND brd_slno != 0
                        AND roster_id IS NOT NULL AND roster_id != 0
                        AND board_type = 'J'
                        AND mainhead = 'M'
                        AND (bench_flag = '' OR bench_flag IS NULL)
                        AND next_dt < h.next_dt
                ) AS last_listed,
                TO_CHAR(h.next_dt, 'DD-MM-YYYY') AS next_date,
                (SELECT stagename FROM master.subheading WHERE stagecode = h.subhead) AS subhead,
                (SELECT purpose FROM master.listing_purpose WHERE code = h.listorder) AS purpose,
                TO_CHAR(h.ent_dt, 'DD-MM-YYYY HH12:MI:SS AM') AS updated_on,
                (SELECT module_desc FROM master.master_module WHERE id = h.module_id) AS module
            FROM
                main m
            LEFT JOIN
                heardt h ON m.diary_no = h.diary_no
            WHERE
                m.diary_no IN (
                    SELECT diary_no
                    FROM (
                        SELECT
                            h.diary_no,
                            h.next_dt,
                            h.listorder
                        FROM
                            heardt h
                        JOIN
                            main m ON m.diary_no = h.diary_no
                        WHERE
                            h.next_dt = '2018-11-12'
                            AND h.mainhead = 'M'
                            AND h.board_type = 'J'
                            AND h.clno > 0
                            AND h.main_supp_flag = 2
                            AND (m.diary_no::TEXT = m.conn_key::TEXT OR m.conn_key IS NULL OR m.conn_key = '0')
                        UNION ALL
                        SELECT
                            h.diary_no,
                            h.next_dt,
                            h.listorder
                        FROM
                            last_heardt h
                        JOIN
                            main m ON m.diary_no = h.diary_no
                        WHERE
                            h.next_dt = '2018-11-12'
                            AND h.mainhead = 'M'
                            AND h.board_type = 'J'
                            AND h.clno > 0
                            AND h.main_supp_flag = 2
                            AND (h.bench_flag = '' OR h.bench_flag IS NULL)
                            AND (h.diary_no::TEXT = h.conn_key::TEXT OR h.conn_key IS NULL OR h.conn_key = '0')
                        GROUP BY
                            h.diary_no, h.next_dt, h.listorder
                    ) AS t
                )";
       // die();
       $result = $this->db->query($sql)->getResultArray();
        return $result;
    }
    public function listing_statistics_details_se($type, $list_dt)
    {
      $lastListedSubquery = $this->db->table('last_heardt')
      ->select("MAX(next_dt)")
      ->where('diary_no = aa.diary_no')
      ->where('clno IS NOT NULL AND clno != 0')
      ->where('brd_slno IS NOT NULL AND brd_slno != 0')
      ->where('roster_id IS NOT NULL AND roster_id != 0')
      ->where('board_type', 'J')
      ->where('mainhead', 'M')
      ->where('(bench_flag = \'\' OR bench_flag IS NULL)')
      ->where('next_dt < aa.next_dt_old')
      ->getCompiledSelect();

    // Subquery for subhead
    $subheadSubquery = $this->db->table('master.subheading')
        ->select('stagename')
        ->where('stagecode = h.subhead')
        ->getCompiledSelect();

    // Subquery for purpose
    $purposeSubquery = $this->db->table('master.listing_purpose')
        ->select('purpose')
        ->where('code = aa.listorder')
        ->getCompiledSelect();

    // Subquery for module
    $moduleSubquery = $this->db->table('master.master_module')
        ->select('module_desc')
        ->where('id = h.module_id')
        ->getCompiledSelect();

    $builder = $this->db->table('main m')
        ->distinct()
        ->select("concat(COALESCE(m.reg_no_display, ''), ' @ ', substring(m.diary_no::TEXT, 1, length(m.diary_no::TEXT) - 4) || '-' || substring(m.diary_no::TEXT, length(m.diary_no::TEXT) - 3)) AS case_no, m.pet_name || ' Vs. ' || m.res_name AS cause_title, (TO_CHAR((" . $lastListedSubquery . "), 'DD-MM-YYYY')) AS last_listed, aa.next_dt_old, TO_CHAR(aa.next_dt_new, 'DD-MM-YYYY') AS next_date, (" . $subheadSubquery . ") AS subhead, (" . $purposeSubquery . ") AS purpose, TO_CHAR(h.ent_dt, 'DD-MM-YYYY HH12:MI:SS AM') AS updated_on, (" . $moduleSubquery . ") AS module")
        ->join('transfer_old_com_gen_cases aa', 'm.diary_no = aa.diary_no', 'inner')
        ->join('heardt h', 'm.diary_no = h.diary_no', 'left')
        ->where('aa.next_dt_old', $list_dt)
        ->where('(cast(aa.diary_no as TEXT) = aa.conn_key::TEXT OR aa.conn_key IS NULL OR aa.conn_key = \'0\')')
        ->where('listtype', 'S');
        // echo $sql = $builder->getCompiledSelect();
        // die();
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }
    public function listing_statistics_details_su($type, $list_dt)
    {
          $lastListedSubquery = $this->db->table('last_heardt')
          ->select("TO_CHAR(MAX(next_dt), 'DD-MM-YYYY')")
          ->where('diary_no = h.diary_no')
          ->where('clno IS NOT NULL AND clno != 0')
          ->where('brd_slno IS NOT NULL AND brd_slno != 0')
          ->where('roster_id IS NOT NULL AND roster_id != 0')
          ->where('board_type', 'J')
          ->where('mainhead', 'M')
          ->where('(bench_flag = \'\' OR bench_flag IS NULL)')
          ->where('next_dt < h.next_dt')
          ->getCompiledSelect();

          // Subquery for subhead
          $subheadSubquery = $this->db->table('master.subheading')
              ->select('stagename')
              ->where('stagecode = h.subhead')
              ->getCompiledSelect();

          // Subquery for purpose
          $purposeSubquery = $this->db->table('master.listing_purpose')
              ->select('purpose')
              ->where('code = h.listorder')
              ->getCompiledSelect();

          // Subquery for module
          $moduleSubquery = $this->db->table('master.master_module')
              ->select('module_desc')
              ->where('id = h.module_id')
              ->getCompiledSelect();

          // Subquery for suppl_time
          $supplTimeSubquery = $this->db->table('cl_printed cl')
              ->selectMax('cl.ent_time')
              ->where('cl.next_dt = h.next_dt')
              ->where('cl.main_supp', 2)
              ->where('cl.m_f', 'M')
              ->where('cl.display', 'Y')
              ->where('cl.from_brd_no BETWEEN 1 AND 99')
              ->getCompiledSelect();

          // Inner subquery (h)
          $innerSubquery = $this->db->table('(
            SELECT
                t.diary_no,
                t.next_dt,
                MAX(t.ent_dt) AS cur_tm
            FROM (
                SELECT
                    h.diary_no,
                    h.next_dt,
                    h.ent_dt,
                    h.module_id
                FROM heardt h
                WHERE
                    h.next_dt = \'' . $list_dt . '\'
                    AND h.mainhead = \'M\'
                    AND h.board_type = \'J\'
                    AND h.clno = 0
                    AND h.main_supp_flag = 0
                UNION ALL
                SELECT
                    h.diary_no,
                    h.next_dt,
                    h.ent_dt,
                    h.module_id
                FROM last_heardt h
                WHERE
                    h.next_dt = \'' . $list_dt . '\'
                    AND h.mainhead = \'M\'
                    AND h.board_type = \'J\'
                    AND h.clno = 0
                    AND h.main_supp_flag = 0
                    AND (h.bench_flag = \'\' OR h.bench_flag IS NULL)
            ) AS t
            GROUP BY
                t.diary_no, t.next_dt
        ) AS h');

        // Main subquery (z)
        $mainSubquery = $this->db->table('(' . $innerSubquery->getCompiledSelect() . ') AS h')
            ->select('m.reg_no_display, m.pet_name, m.res_name, hh.listorder, h.*, (' . $supplTimeSubquery . ') AS suppl_time')
            ->join('advance_allocated aa', 'cast(aa.diary_no as TEXT) = h.diary_no::TEXT AND aa.next_dt = h.next_dt', 'left')
            ->join('transfer_old_com_gen_cases tt', 'tt.diary_no = h.diary_no AND tt.next_dt_old = h.next_dt AND tt.listtype = \'A\'', 'left')
            ->join('heardt hh', 'hh.diary_no = h.diary_no', 'left')
            ->join('main m', 'm.diary_no = hh.diary_no', 'left')
            ->where('aa.diary_no IS NULL')
            ->where('tt.diary_no IS NULL')
            ->where('(cast(m.diary_no as TEXT) = m.conn_key::TEXT OR m.conn_key IS NULL OR m.conn_key = \'0\')')
            ->groupBy('m.reg_no_display, m.pet_name, m.res_name, hh.listorder, h.diary_no, h.next_dt, hh.ent_dt, hh.module_id, h.cur_tm');

        // Final query
        $builder = $this->db->table('main m')
            ->select("concat(COALESCE(m.reg_no_display, ''), ' @ ', substring(m.diary_no::TEXT, 1, length(m.diary_no::TEXT) - 4) || '-' || substring(m.diary_no::TEXT, length(m.diary_no::TEXT) - 3)) AS case_no, m.pet_name || ' Vs. ' || m.res_name AS cause_title, (" . $lastListedSubquery . ") AS last_listed, TO_CHAR(h.next_dt, 'DD-MM-YYYY') AS next_date, (" . $subheadSubquery . ") AS subhead, (" . $purposeSubquery . ") AS purpose, TO_CHAR(h.ent_dt, 'DD-MM-YYYY HH12:MI:SS AM') AS updated_on, (" . $moduleSubquery . ") AS module")
            ->join('heardt h', 'm.diary_no = h.diary_no', 'left')
            ->whereIn('m.diary_no', function($sub) use($mainSubquery) {
                $sub->select('diary_no')->from('(' . $mainSubquery->getCompiledSelect() . ') AS z')->where('cur_tm > suppl_time');
            });

        // echo $sql = $builder->getCompiledSelect();
        // die();
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function listing_statistics_details_all($type, $listingdate)
  {
    if ($type == 'AL') {
      $sql = "SELECT 
                CONCAT(
                  COALESCE(m.reg_no_display, ''), 
                  ' @ ', 
                  CONCAT(
                    LEFT(m.diary_no, LENGTH(m.diary_no) - 4), 
                    '-', 
                    SUBSTRING(m.diary_no, LENGTH(m.diary_no) - 3, 4)
                  )
                ) AS case_no, 
                CONCAT(m.pet_name, ' Vs. ', m.res_name) AS cause_title, 
                (
                  SELECT 
                    TO_CHAR(MAX(next_dt), 'DD-MM-YYYY') 
                  FROM 
                    last_heardt 
                  WHERE 
                    diary_no = aa.diary_no 
                    AND clno != 0 
                    AND clno IS NOT NULL 
                    AND brd_slno != 0 
                    AND brd_slno IS NOT NULL 
                    AND roster_id != 0 
                    AND roster_id IS NOT NULL 
                    AND board_type = 'J' 
                    AND mainhead = 'M' 
                    AND (
                      bench_flag = '' 
                      OR bench_flag IS NULL
                    ) 
                    AND next_dt < aa.next_dt
                ) AS last_listed, 
                TO_CHAR(aa.next_dt, 'DD-MM-YYYY') AS next_date, 
                (
                  SELECT 
                    stagename 
                  FROM 
                    subheading 
                  WHERE 
                    stagecode = aa.subhead
                ) AS subhead, 
                (
                  SELECT 
                    purpose 
                  FROM 
                    listing_purpose 
                  WHERE 
                    code = aa.listorder
                ) AS purpose, 
                TO_CHAR(h.ent_dt, 'DD-MM-YYYY HH12:MI:SS AM') AS updated_on, 
                (
                  SELECT 
                    module_desc 
                  FROM 
                    master_module 
                  WHERE 
                    id = h.module_id
                ) AS module 
              FROM 
                main m 
                INNER JOIN advance_allocated aa ON m.diary_no = aa.diary_no 
                LEFT JOIN heardt h ON m.diary_no = h.diary_no 
              WHERE 
                aa.next_dt = '$listingdate'
                AND (
                  aa.diary_no = aa.conn_key 
                  OR aa.conn_key = '' 
                  OR aa.conn_key IS NULL 
                  OR aa.conn_key = '0'
                )";
    } elseif ($type == 'AE') {
      $sql = "SELECT 
  DISTINCT CONCAT(
    COALESCE(m.reg_no_display, ''), 
    ' @ ', 
    CONCAT(
      LEFT(m.diary_no::TEXT, LENGTH(m.diary_no::TEXT)-4), 
      '-', 
      SUBSTRING(m.diary_no::TEXT, LENGTH(m.diary_no::TEXT)-3, 4)
    )
  ) AS case_no, 
  CONCAT(m.pet_name, ' Vs. ', m.res_name) AS cause_title, 
  (
    SELECT 
      MAX(next_dt) 
    FROM 
      last_heardt 
    WHERE 
      diary_no = aa.diary_no 
      AND clno != 0 
      AND clno IS NOT NULL 
      AND brd_slno != 0 
      AND brd_slno IS NOT NULL 
      AND roster_id != 0 
      AND roster_id IS NOT NULL 
      AND board_type = 'J' 
      AND mainhead = 'M' 
      AND (
        bench_flag = '' 
        OR bench_flag IS NULL
      ) 
      AND next_dt < aa.next_dt_old
  ) AS last_listed, 
  TO_CHAR(aa.next_dt_new, 'DD-MM-YYYY') AS next_date, 
  (
    SELECT 
      stagename 
    FROM 
      master.subheading 
    WHERE 
      stagecode = h.subhead
  ) AS subhead, 
  (
    SELECT 
      purpose 
    FROM 
      master.listing_purpose 
    WHERE 
      code = aa.listorder
  ) AS purpose, 
  TO_CHAR(h.ent_dt, 'DD-MM-YYYY HH12:MI:SS AM') AS updated_on, 
  (
    SELECT 
      module_desc 
    FROM 
      master.master_module 
    WHERE 
      id = h.module_id
  ) AS module 
FROM 
  main m 
  INNER JOIN transfer_old_com_gen_cases aa ON m.diary_no = aa.diary_no 
  LEFT JOIN heardt h ON m.diary_no = h.diary_no 
WHERE 
  aa.next_dt_old = '$listingdate'
  AND (
    aa.diary_no = aa.conn_key 
    OR aa.conn_key IS NULL 
    OR aa.conn_key = '0'
  ) 
  AND listtype = 'A'";
    } elseif ($type == "AU") {
      $sql = "SELECT 
              CONCAT(
                COALESCE(m.reg_no_display, ''), 
                ' @ ', 
                CONCAT(
                  LEFT(m.diary_no::TEXT, LENGTH(m.diary_no::TEXT)-4), 
                  '-', 
                  SUBSTRING(m.diary_no::TEXT, LENGTH(m.diary_no::TEXT)-3, 4)
                )
              ) AS case_no, 
              CONCAT(m.pet_name, ' Vs. ', m.res_name) AS cause_title, 
              (
                SELECT 
                  MAX(next_dt) 
                FROM 
                  last_heardt 
                WHERE 
                  diary_no = h.diary_no 
                  AND clno != 0 
                  AND clno IS NOT NULL 
                  AND brd_slno != 0 
                  AND brd_slno IS NOT NULL 
                  AND roster_id != 0 
                  AND roster_id IS NOT NULL 
                  AND board_type = 'J' 
                  AND mainhead = 'M' 
                  AND (
                    bench_flag = '' 
                    OR bench_flag IS NULL
                  ) 
                  AND next_dt < h.next_dt
              ) AS last_listed, 
              TO_CHAR(h.next_dt, 'DD-MM-YYYY') AS next_date, 
              (
                SELECT 
                  stagename 
                FROM 
                  master.subheading 
                WHERE 
                  stagecode = h.subhead
              ) AS subhead, 
              (
                SELECT 
                  purpose 
                FROM 
                  master.listing_purpose 
                WHERE 
                  code = h.listorder
              ) AS purpose, 
              TO_CHAR(h.ent_dt, 'DD-MM-YYYY HH12:MI:SS AM') AS updated_on, 
              (
                SELECT 
                  module_desc 
                FROM 
                  master.master_module 
                WHERE 
                  id = h.module_id
              ) AS module 
            FROM 
              main m 
              LEFT JOIN heardt h ON m.diary_no = h.diary_no 
            WHERE 
              m.diary_no IN (
                SELECT 
                  DISTINCT diary_no 
                FROM 
                  (
                    SELECT 
                      m.reg_no_display, 
                      m.pet_name, 
                      m.res_name, 
                      hh.listorder, 
                      h.*, 
                      (
                        SELECT 
                          ac.ent_time 
                        FROM 
                          advance_cl_printed ac 
                        WHERE 
                          ac.next_dt = h.next_dt
                      ) AS adv_time, 
                      (
                        SELECT 
                          MAX(cl.ent_time) 
                        FROM 
                          cl_printed cl 
                        WHERE 
                          cl.next_dt = h.next_dt 
                          AND main_supp = 1 
                          AND m_f = 'M' 
                          AND display = 'Y' 
                          AND from_brd_no BETWEEN 1 AND 99
                      ) AS final_time 
                    FROM 
                      (
                        SELECT 
                          t.diary_no, 
                          t.next_dt, 
                          MAX(ent_dt) AS cur_tm 
                        FROM 
                          (
                            SELECT 
                              h.diary_no, 
                              h.next_dt, 
                              h.listorder, 
                              h.ent_dt, 
                              h.module_id 
                            FROM 
                              heardt h 
                            WHERE 
                              next_dt = '$listingdate' 
                              AND mainhead = 'M' 
                              AND board_type = 'J' 
                              AND clno = 0 
                              AND main_supp_flag = 0 
                            UNION 
                            SELECT 
                              h.diary_no, 
                              h.next_dt, 
                              h.listorder, 
                              h.ent_dt, 
                              h.module_id 
                            FROM 
                              last_heardt h 
                            WHERE 
                              next_dt = '$listingdate' 
                              AND mainhead = 'M' 
                              AND board_type = 'J' 
                              AND clno = 0 
                              AND main_supp_flag = 0 
                              AND (
                                h.bench_flag = '' 
                                OR h.bench_flag IS NULL
                              )
                          ) t 
                        GROUP BY 
                          diary_no,t.next_dt,t.diary_no
                      ) h 
                      LEFT JOIN advance_allocated aa ON aa.diary_no = h.diary_no 
                      AND aa.next_dt = h.next_dt 
                      LEFT JOIN transfer_old_com_gen_cases tt ON tt.diary_no = h.diary_no 
                      AND tt.next_dt_old = h.next_dt 
                      AND tt.listtype = 'A' 
                      LEFT JOIN heardt hh ON hh.diary_no = h.diary_no 
                      LEFT JOIN main m ON m.diary_no = hh.diary_no 
                    WHERE 
                      aa.diary_no IS NULL 
                      AND tt.diary_no IS NULL 
                      AND (
                        m.diary_no = m.conn_key ::int
                        OR m.conn_key = '' 
                        OR m.conn_key IS NULL 
                        OR m.conn_key = '0'
                      ) 
                    GROUP BY 
                      m.diary_no,hh.listorder,h.diary_no,h.next_dt,h.cur_tm
                  ) z 
                WHERE 
                  cur_tm BETWEEN adv_time AND final_time
              )";
    } elseif ($type == 'FL') {
      $sql = "SELECT 
                CONCAT(
                COALESCE(m.reg_no_display, ''), 
                ' @ ', 
                CONCAT(
                  LEFT(m.diary_no::TEXT, LENGTH(m.diary_no::TEXT) - 4), 
                  '-', 
                  SUBSTRING(m.diary_no::TEXT, LENGTH(m.diary_no::TEXT) - 3, 4)
                )
                ) AS case_no, 
                CONCAT(m.pet_name, ' Vs. ', m.res_name) AS cause_title, 
                (
                SELECT 
                  TO_CHAR(MAX(next_dt), 'DD-MM-YYYY') 
                FROM 
                  last_heardt 
                WHERE 
                  diary_no = h.diary_no 
                  AND clno != 0 
                  AND clno IS NOT NULL 
                  AND brd_slno != 0 
                  AND brd_slno IS NOT NULL 
                  AND roster_id != 0 
                  AND roster_id IS NOT NULL 
                  AND board_type = 'J' 
                  AND mainhead = 'M' 
                  AND (
                  bench_flag = '' 
                  OR bench_flag IS NULL
                  ) 
                  AND next_dt < h.next_dt
                ) AS last_listed, 
                TO_CHAR(h.next_dt, 'DD-MM-YYYY') AS next_date, 
                (
                SELECT 
                  stagename 
                FROM 
                  master.subheading 
                WHERE 
                  stagecode = h.subhead
                ) AS subhead, 
                (
                SELECT 
                  purpose 
                FROM 
                  master.listing_purpose 
                WHERE 
                  code = h.listorder
                ) AS purpose, 
                TO_CHAR(h.ent_dt, 'DD-MM-YYYY HH12:MI:SS AM') AS updated_on, 
                (
                SELECT 
                  module_desc 
                FROM 
                  master.master_module 
                WHERE 
                  id = h.module_id
                ) AS module 
                FROM 
                main m 
                LEFT JOIN heardt h ON m.diary_no = h.diary_no 
                WHERE 
                m.diary_no IN (
                SELECT 
                  diary_no 
                FROM (
                  SELECT 
                  h.diary_no, 
                  h.next_dt, 
                  h.listorder 
                  FROM 
                  heardt h 
                  JOIN main m ON m.diary_no = h.diary_no 
                  WHERE 
                  m.next_dt = '$listingdate'
                  AND mainhead = 'M' 
                  AND board_type = 'J' 
                  AND clno > 0 
                  AND main_supp_flag = 1 
                  AND (
                  m.diary_no = m.conn_key ::int
                  OR m.conn_key = '' 
                  OR m.conn_key IS NULL 
                  OR m.conn_key = '0'
                  ) 
                  UNION 
                  SELECT 
                  h.diary_no, 
                  h.next_dt, 
                  h.listorder 
                  FROM 
                  last_heardt h 
                  JOIN main m ON m.diary_no = h.diary_no 
                  WHERE 
                  m.next_dt = '$listingdate' 
                  AND mainhead = 'M' 
                  AND board_type = 'J' 
                  AND clno > 0 
                  AND main_supp_flag = 1 
                  AND (
                  bench_flag = '' 
                  OR bench_flag IS NULL
                  ) 
                  AND (
                  h.diary_no = h.conn_key 
                  OR h.conn_key IS NULL 
                  OR h.conn_key = '0'
                  ) 
                  GROUP BY 
                  h.diary_no,h.next_dt,h.listorder
                ) t
                )";
    } else {
      $sql = '';
    }
    $query = $this->db->query($sql);
    $result = $query->getResultArray();
    return $result;
  }
}