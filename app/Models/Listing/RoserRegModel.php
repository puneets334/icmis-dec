<?php

namespace App\Models\Listing;

use CodeIgniter\Model;


class RoserRegModel extends Model
{

    public function getRoserReg($ldates)
    {
        $subQuery1 = $this->db->table('master.submaster')
            ->select('sub_name1, category_sc_old, old_sc_c_kk, subcode1, subcode2, subcode3, subcode4')
            ->select("TRIM(BOTH ',' FROM STRING_AGG(category_sc_old, ',')) AS sccat", false)
            ->where('display', 'Y')
            ->where('flag', 's')
            ->where('flag_use', 'S')
            ->groupBy('sub_name1, category_sc_old, old_sc_c_kk, subcode1, subcode2, subcode3, subcode4')
            ->getCompiledSelect();

        // Subquery 2 (kk): Create the subquery for main, heardt, and other related tables
        $subQuery2 = $this->db->table('master.submaster s')
            ->select('c.id, c.sub_name1, c.ready_m, c.not_ready_m, c.tot_m_ready_not_redy, c.ready_with_cn, c.not_ready_with_cn, c.tot_cases, c.cno')
            ->select("
        CASE WHEN '1' = ANY(STRING_TO_ARRAY(c.cno, ',')) THEN 'Y' ELSE '' END AS cji,
        CASE WHEN '2' = ANY(STRING_TO_ARRAY(c.cno, ',')) THEN 'Y' ELSE '' END AS court_2,
        CASE WHEN '3' = ANY(STRING_TO_ARRAY(c.cno, ',')) THEN 'Y' ELSE '' END AS court_3,
        CASE WHEN '4' = ANY(STRING_TO_ARRAY(c.cno, ',')) THEN 'Y' ELSE '' END AS court_4,
        CASE WHEN '5' = ANY(STRING_TO_ARRAY(c.cno, ',')) THEN 'Y' ELSE '' END AS court_5,
        CASE WHEN '6' = ANY(STRING_TO_ARRAY(c.cno, ',')) THEN 'Y' ELSE '' END AS court_6,
        CASE WHEN '7' = ANY(STRING_TO_ARRAY(c.cno, ',')) THEN 'Y' ELSE '' END AS court_7,
        CASE WHEN '8' = ANY(STRING_TO_ARRAY(c.cno, ',')) THEN 'Y' ELSE '' END AS court_8,
        CASE WHEN '9' = ANY(STRING_TO_ARRAY(c.cno, ',')) THEN 'Y' ELSE '' END AS court_9,
        CASE WHEN '10' = ANY(STRING_TO_ARRAY(c.cno, ',')) THEN 'Y' ELSE '' END AS court_10,
        CASE WHEN '11' = ANY(STRING_TO_ARRAY(c.cno, ',')) THEN 'Y' ELSE '' END AS court_11,
        CASE WHEN '12' = ANY(STRING_TO_ARRAY(c.cno, ',')) THEN 'Y' ELSE '' END AS court_12,
        CASE WHEN '13' = ANY(STRING_TO_ARRAY(c.cno, ',')) THEN 'Y' ELSE '' END AS court_13", false)
            ->join("(SELECT 
              a.*, b.courtno, STRING_AGG(b.courtno :: TEXT, ',') AS cno
            FROM 
              (SELECT 
                  s.id, s.sub_name1, 
                  SUM(CASE WHEN h.main_supp_flag = 0 AND (m.diary_no = m.conn_key::BIGINT OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL) THEN 1 ELSE 0 END) AS ready_m, 
                  SUM(CASE WHEN h.main_supp_flag != 0 AND (m.diary_no = m.conn_key::BIGINT OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL) THEN 1 ELSE 0 END) AS not_ready_m, 
                  SUM(CASE WHEN (m.diary_no = m.conn_key::BIGINT OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL) THEN 1 ELSE 0 END) AS tot_m_ready_not_redy, 
                  SUM(CASE WHEN h.main_supp_flag = 0 THEN 1 ELSE 0 END) AS ready_with_cn, 
                  SUM(CASE WHEN h.main_supp_flag != 0 THEN 1 ELSE 0 END) AS not_ready_with_cn, 
                  COUNT(*) AS tot_cases 
              FROM 
                master.submaster s 
                LEFT JOIN mul_category mc ON mc.submaster_id = s.id 
                LEFT JOIN main m ON m.diary_no = mc.diary_no 
                LEFT JOIN heardt h ON h.diary_no = m.diary_no 
              WHERE 
                m.c_status = 'P' AND h.mainhead = 'F' AND s.flag_use = 'S' AND mc.display = 'Y' 
                AND s.display = 'Y' AND s.flag = 's' 
              GROUP BY s.sub_name1, s.id) a
              LEFT JOIN (SELECT r.courtno, ss.sub_name1, ss.subcode1 
                         FROM master.roster r 
                         INNER JOIN category_allottment c ON c.ros_id = r.id 
                         INNER JOIN master.submaster s ON s.id = c.submaster_id 
                         LEFT JOIN master.submaster ss ON ss.subcode1 = s.subcode1 
                         WHERE ss.display = 'Y' AND s.display = 'Y' AND c.display = 'Y' AND r.display = 'Y' AND r.m_f = '2' AND r.from_date = '2024-09-20') b 
              ON b.sub_name1 = a.sub_name1 
              GROUP BY a.sub_name1, a.id, b.courtno,a.ready_m,a.not_ready_m,a.tot_m_ready_not_redy,a.ready_with_cn,a.not_ready_with_cn,a.tot_cases) c", 'c.sub_name1 = s.sub_name1', 'left')
            ->orderBy('s.sub_name1')
            ->getCompiledSelect();
        $builder = $this->db->table("($subQuery1) k")
            ->select('k.sccat, k.old_sc_c_kk, k.sub_name1 as sub_cat, category_sc_old, kk.*')
            ->join("($subQuery2) kk", 'k.sub_name1 = kk.sub_name1', 'left')
            ->orderBy('k.old_sc_c_kk');
        $query = $builder->get();
        // $sql = $builder->getCompiledSelect();
        // pr($sql);
        $result = $query->getResultArray();
        return $result;
    }

    public function getRosterRegData($ldates)
    {
        $subQuery = $this->db->table('master.roster r')
            ->select("r.id, r.courtno, STRING_AGG(j.abbreviation, ',' ORDER BY j.judge_seniority) AS jnm")
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
            ->where('r.m_f', '2')
            ->where('r.from_date', $ldates)
            ->groupBy('r.id', 'r.courtno')
            ->orderBy('r.courtno')
            ->orderBy('r.id')
            ->orderBy('j.judge_seniority')
            ->getCompiledSelect();  // Compile the subquery

        // Main query with split_part
        $builder = $this->db->table("($subQuery) a")
            ->select("a.courtno, split_part(a.jnm, ',', 1) AS jjj")
            ->groupBy('a.courtno');
        //    $sql = $query->getCompiledSelect();
        //  pr($sql);
        $query = $builder->get();

        $result = $query->getResultArray();
        return $result;
    }

    public function getSubName_old($ldates)
    {

        $sql="SELECT 
                    k.sccat, 
                    k.old_sc_c_kk, 
                    k.sub_name1 AS sub_cat, 
                    k.category_sc_old, 
                    kk.*
                FROM (
                SELECT 
                        sub_name1, 
                        MAX(category_sc_old) AS category_sc_old,
                        MAX(old_sc_c_kk) AS old_sc_c_kk,
                        MAX(subcode1) AS subcode1,
                        MAX(subcode2) AS subcode2,
                        MAX(subcode3) AS subcode3,
                        MAX(subcode4) AS subcode4,
                        TRIM(BOTH ',' FROM STRING_AGG(category_sc_old, ',')) AS sccat
                    FROM master.submaster
                    WHERE display = 'Y' 
                    AND flag = 's' 
                    AND flag_use = 'S'
                    GROUP BY sub_name1
                ) k
                LEFT JOIN (
                SELECT 
                        d.*, 
                        CASE WHEN POSITION(',1,' IN (',' || cno || ',')) > 0 THEN 'Y' ELSE '' END AS cji,
                    CASE WHEN POSITION(',2,' IN (',' || cno || ',')) > 0 THEN 'Y' ELSE '' END AS court_2,
                        CASE WHEN POSITION(',3,' IN (',' || cno || ',')) > 0 THEN 'Y' ELSE '' END AS court_3,
                        CASE WHEN POSITION(',4,' IN (',' || cno || ',')) > 0 THEN 'Y' ELSE '' END AS court_4,
                        CASE WHEN POSITION(',5,' IN (',' || cno || ',')) > 0 THEN 'Y' ELSE '' END AS court_5,
                        CASE WHEN POSITION(',6,' IN (',' || cno || ',')) > 0 THEN 'Y' ELSE '' END AS court_6,
                        CASE WHEN POSITION(',7,' IN (',' || cno || ',')) > 0 THEN 'Y' ELSE '' END AS court_7,
                        CASE WHEN POSITION(',8,' IN (',' || cno || ',')) > 0 THEN 'Y' ELSE '' END AS court_8,
                        CASE WHEN POSITION(',9,' IN (',' || cno || ',')) > 0 THEN 'Y' ELSE '' END AS court_9,
                    CASE WHEN POSITION(',10,' IN (',' || cno || ',')) > 0 THEN 'Y' ELSE '' END AS court_10,
                        CASE WHEN POSITION(',11,' IN (',' || cno || ',')) > 0 THEN 'Y' ELSE '' END AS court_11,
                        CASE WHEN POSITION(',12,' IN (',' || cno || ',')) > 0 THEN 'Y' ELSE '' END AS court_12,
                        CASE WHEN POSITION(',13,' IN (',' || cno || ',')) > 0 THEN 'Y' ELSE '' END AS court_13
                    FROM (
                        SELECT 
                            c.*, 
                            STRING_AGG(courtno::TEXT, ',') AS cno
                        FROM (
                            SELECT 
                                a.*, 
                                b.courtno
                            FROM (
                                SELECT 
                                    s.id, 
                                    s.sub_name1, 
                                    SUM(CASE 
                                        WHEN h.main_supp_flag = 0 
                                            AND (m.diary_no = m.conn_key::int OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL) 
                                        THEN 1 ELSE 0 END) AS ready_m,
                                    SUM(CASE 
                                        WHEN h.main_supp_flag != 0 
                                            AND (m.diary_no = m.conn_key::int OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL) 
                                        THEN 1 ELSE 0 END) AS not_ready_m,
                                    SUM(CASE 
                                        WHEN (m.diary_no = m.conn_key::int OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL) 
                                        THEN 1 ELSE 0 END) AS tot_m_ready_not_redy,
                        SUM(CASE WHEN h.main_supp_flag = 0 THEN 1 ELSE 0 END) AS ready_with_cn,
                                    SUM(CASE WHEN h.main_supp_flag != 0 THEN 1 ELSE 0 END) AS not_ready_with_cn,
                                    COUNT(*) AS tot_cases
                                FROM master.submaster s
                                LEFT JOIN mul_category mc 
                                    ON mc.submaster_id = s.id
                                LEFT JOIN main m 
                                    ON m.diary_no = mc.diary_no
                                LEFT JOIN public.heardt h 
                                    ON h.diary_no = m.diary_no
                                WHERE 
                                    m.c_status = 'P'
                                    AND h.mainhead = 'F'
                                    AND s.flag_use = 'S'
                                    AND mc.display = 'Y'
                                    AND (
                                        CASE 
                                            WHEN h.listorder IN (4,5,7,25,8) 
                                            THEN (
                                                    h.next_dt BETWEEN '$ldates' 
                                                                AND ('$ldates'::date + INTERVAL '7 days' - INTERVAL '1 day')
                                                OR h.next_dt <= CURRENT_DATE
                                                )
                                            ELSE h.next_dt > '1947-08-15'
                                        END
                        )
                                    AND s.display = 'Y'
                                    AND s.flag = 's'
                                GROUP BY s.sub_name1,s.id
                            ) a 
                            LEFT JOIN (
                                SELECT 
                                    r.courtno, 
                                    ss.sub_name1, 
                                    ss.subcode1
                                FROM master.roster r 
                                INNER JOIN category_allottment c
                                    ON c.ros_id = r.id
                                INNER JOIN master.submaster s 
                                    ON s.id = c.submaster_id
                                LEFT JOIN master.submaster ss 
                                    ON ss.subcode1 = s.subcode1
                                WHERE 
                                    ss.display = 'Y'
                                    AND s.display = 'Y'
                                    AND c.display = 'Y'
                                    AND r.display = 'Y'
                                    AND r.m_f = '2'
                                    AND r.from_date = '$ldates'
                            ) b 
                                ON b.sub_name1 = a.sub_name1
                            GROUP BY b.sub_name1,a.id,a.sub_name1,a.ready_m,a.not_ready_m,a.tot_m_ready_not_redy,
                a.ready_with_cn,a.not_ready_with_cn,a.tot_cases,b.courtno
                        ) c 
                        GROUP BY c.sub_name1,c.id,c.ready_m,c.not_ready_m,c.tot_m_ready_not_redy,c.ready_with_cn
                ,c.not_ready_with_cn,c.tot_cases,c.courtno
                    ) d
                    ORDER BY sub_name1
                ) kk 
                    ON k.sub_name1 = kk.sub_name1  
                ORDER BY k.old_sc_c_kk";
       
        $query = $this->db->query($sql);
        return $query->getResultArray();
    }

    public function getSubName($ldates)
    {
        $sql = "SELECT
                    k.sccat,
                    k.old_sc_c_kk,
                    k.sub_cat,
                    k.category_sc_old,
                    kk.*
                FROM
                    (
                        SELECT
                            sub_name1 as sub_cat,
                            category_sc_old,
                            old_sc_c_kk,
                            subcode1,
                            subcode2,
                            subcode3,
                            subcode4,
                            TRIM(BOTH ',' FROM string_agg(category_sc_old, ',')) AS sccat
                        FROM
                            master.submaster
                        WHERE
                            display = 'Y' AND is_old = 'N'
                        GROUP BY
                            sub_name1, category_sc_old, old_sc_c_kk, subcode1, subcode2, subcode3, subcode4
                    ) k
                LEFT JOIN
                    (
                        SELECT
                            d.*,
                            CASE WHEN strpos(CAST(cno AS TEXT), '1') > 0 THEN 'Y' ELSE '' END AS cji,
                            CASE WHEN strpos(CAST(cno AS TEXT), '2') > 0 THEN 'Y' ELSE '' END AS court_2,
                            CASE WHEN strpos(CAST(cno AS TEXT), '3') > 0 THEN 'Y' ELSE '' END AS court_3,
                            CASE WHEN strpos(CAST(cno AS TEXT), '4') > 0 THEN 'Y' ELSE '' END AS court_4,
                            CASE WHEN strpos(CAST(cno AS TEXT), '5') > 0 THEN 'Y' ELSE '' END AS court_5,
                            CASE WHEN strpos(CAST(cno AS TEXT), '6') > 0 THEN 'Y' ELSE '' END AS court_6,
                            CASE WHEN strpos(CAST(cno AS TEXT), '7') > 0 THEN 'Y' ELSE '' END AS court_7,
                            CASE WHEN strpos(CAST(cno AS TEXT), '8') > 0 THEN 'Y' ELSE '' END AS court_8,
                            CASE WHEN strpos(CAST(cno AS TEXT), '9') > 0 THEN 'Y' ELSE '' END AS court_9,
                            CASE WHEN strpos(CAST(cno AS TEXT), '10') > 0 THEN 'Y' ELSE '' END AS court_10,
                            CASE WHEN strpos(CAST(cno AS TEXT), '11') > 0 THEN 'Y' ELSE '' END AS court_11,
                            CASE WHEN strpos(CAST(cno AS TEXT), '12') > 0 THEN 'Y' ELSE '' END AS court_12,
                            CASE WHEN strpos(CAST(cno AS TEXT), '13') > 0 THEN 'Y' ELSE '' END AS court_13
                        FROM
                            (
                                SELECT
                                    c.*,
                                    string_agg(courtno::TEXT, '') AS cno
                                FROM
                                    (
                                        SELECT
                                            a.*,
                                            b.courtno
                                        FROM
                                            (
                                                SELECT
                                                    s.id,
                                                    s.sub_name1,
                                                    SUM(CASE WHEN h.main_supp_flag = 0 AND (m.diary_no::text = m.conn_key::text OR m.conn_key::text = '0' OR m.conn_key::text = '' OR m.conn_key::text IS NULL) THEN 1 ELSE 0 END) AS ready_m,
                                                    SUM(CASE WHEN h.main_supp_flag != 0 AND (m.diary_no::text = m.conn_key::text OR m.conn_key::text = '0' OR m.conn_key::text = '' OR m.conn_key::text IS NULL) THEN 1 ELSE 0 END) AS not_ready_m,
                                                    SUM(CASE WHEN (m.diary_no::text = m.conn_key::text OR m.conn_key::text = '0' OR m.conn_key::text = '' OR m.conn_key::text IS NULL) THEN 1 ELSE 0 END) AS tot_m_ready_not_redy,
                                                    SUM(CASE WHEN h.main_supp_flag = 0 THEN 1 ELSE 0 END) AS ready_with_cn,
                                                    SUM(CASE WHEN h.main_supp_flag != 0 THEN 1 ELSE 0 END) AS not_ready_with_cn,
                                                    COUNT(*) AS tot_cases
                                                FROM
                                                    master.submaster s
                                                LEFT JOIN
                                                    mul_category mc ON mc.submaster_id = s.id
                                                LEFT JOIN
                                                    main m ON m.diary_no = mc.diary_no
                                                LEFT JOIN
                                                    heardt h ON h.diary_no = m.diary_no
                                                WHERE
                                                    (
                                                        m.c_status = 'P' AND
                                                        h.mainhead = 'F' AND
                                                        mc.display = 'Y' AND
                                                        CASE
                                                            WHEN h.listorder IN (4, 5, 7, 25, 8)
                                                            THEN (h.next_dt BETWEEN '$ldates' AND ('$ldates'::date + interval '7 day' - interval '1 day' * extract(dow FROM '2025-04-29'::date)) OR h.next_dt <= CURRENT_DATE)
                                                            ELSE h.next_dt > '1947-08-15'
                                                        END AND
                                                        s.is_old = 'N' AND
                                                        s.display = 'Y'
                                                    )
                                                GROUP BY
                                                    s.sub_name1,s.id
                                            ) a
                                        LEFT JOIN
                                            (
                                                SELECT
                                                    r.courtno,
                                                    ss.sub_name1,
                                                    ss.subcode1
                                                FROM
                                                    master.roster r
                                                INNER JOIN
                                                    category_allottment c ON c.ros_id = r.id
                                                INNER JOIN
                                                    master.submaster s ON s.id = c.submaster_id
                                                LEFT JOIN
                                                    master.submaster ss ON ss.subcode1 = s.subcode1
                                                WHERE
                                                    ss.is_old = 'N' AND
                                                    ss.display = 'Y' AND
                                                    s.is_old = 'N' AND
                                                    s.display = 'Y' AND
                                                    c.display = 'Y' AND
                                                    r.display = 'Y' AND
                                                    r.m_f = '2' AND
                                                    r.from_date = '$ldates'
                                            ) b ON b.sub_name1 = a.sub_name1
                                        GROUP BY
                                            b.sub_name1, courtno,a.id,a.sub_name1,a.ready_m,a.not_ready_m,a.tot_m_ready_not_redy,a.ready_with_cn,a.not_ready_with_cn,a.tot_cases
                                    ) c
                                GROUP BY
                                    c.sub_name1,c.courtno,c.id,c.ready_m,c.not_ready_m,c.tot_m_ready_not_redy,c.ready_with_cn,c.not_ready_with_cn,c.tot_cases
                            ) d
                        ORDER BY
                            sub_name1
                    ) kk ON k.sub_cat = kk.sub_name1
                ORDER BY
                    k.old_sc_c_kk";
                $query = $this->db->query($sql);
                $result = $query->getResultArray();
                return $result;
    }

  
    public function getCourtNo($ldates)
    {
        // Remove vkg
        $subQuery = $this->db->table('master.roster r')
            ->select('r.id, r.courtno, STRING_AGG(j.abbreviation, \',\' ORDER BY j.judge_seniority) AS jnm')
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
            ->where('r.m_f', '2')
            ->where('r.from_date', $ldates)
            ->groupBy('r.id, r.courtno')
            ->getCompiledSelect();  
        $query = $this->db->table("($subQuery) a", false)  
            ->select('a.courtno, split_part(MIN(a.jnm), \',\', 1) AS jjj')
            ->groupBy('a.courtno')
            ->orderBy('a.courtno')
            ->get();

        return $query->getResultArray();
    }

    public function getCount()
    {
        $builder = $this->db->table('main m');
        $builder->join('heardt h', 'h.diary_no = m.diary_no', 'inner');
        $builder->where('m.c_status', 'P');
        $builder->where('h.mainhead', 'F');
        $builder->select('COUNT(*) as count');
        $query = $builder->get();
        return $query->getRowArray();
    }
}
