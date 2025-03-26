<?php

namespace App\Models\Court;

use CodeIgniter\Model;

class CourtCausesListModel extends Model
{

    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
    }

public function insertShowCauselist($causeslistarrydata)
    {
        $builder = $this->db->table("showlcd");
        if ($builder->insert($causeslistarrydata)) {
            return true;
        } else {
            return false;
        }
    }
    public function insertShowCauselistHistory($causeslistarrHistoryydata)
    {
        $builder = $this->db->table("showlcd_history");
        if ($builder->insert($causeslistarrHistoryydata)) {
            return true;
        } else {
            return false;
        }
    }

    public function updateShowCauselist($updateData,$court){
        $builder = $this->db->table("showlcd");
        $builder->where('court', $court);
        if($builder->update($updateData)){
            return true;
        }else{return false;}
    }

    public function getRosterData($crt = null, $mf = null, $check_text = null, $tdt1 = null)
    {
        $db = \Config\Database::connect();
        $stg = ($mf === 'M') ? 1 : (($mf === 'F') ? 2 : 0);
        $t_cn = '';

        // Build the condition based on $crt and other variables
        if ($crt === null || $crt === '') {
            $t_cn = "AND CASE WHEN board_type_mb = 'R' THEN to_date = '0000-00-00' ELSE from_date = :tdt1: END";
        } elseif ($crt === '101') {
            $t_cn = "AND board_type_mb = 'C' AND from_date = :tdt1:";
        } elseif ($check_text !== null && $check_text !== '') {
            $t_cn = "AND courtno = :crt: AND board_type_mb = 'R' AND to_date = '0000-00-00'";
        } else {
            $t_cn = "AND courtno = :crt: AND 
                     CASE WHEN to_date IS NULL THEN from_date = :tdt1: 
                     ELSE :tdt1: BETWEEN from_date AND to_date END";
        }

        // Construct the SQL query
        $sql_ro = "
            SELECT DISTINCT
                rj.roster_id, 
                board_type_mb,
                rj.judge_id,
                r.courtno,
                    CASE 
                        WHEN board_type_mb = 'J' THEN 1
                        WHEN board_type_mb = 'C' THEN 2
                        WHEN board_type_mb = 'CC' THEN 3
                        WHEN board_type_mb = 'R' THEN 4
                    END AS board_type_order,
                CASE WHEN r.courtno = 0 THEN 9999 ELSE r.courtno END AS courtno_order
            FROM
                master.roster_judge rj
            JOIN master.roster r ON rj.roster_id = r.id
            JOIN master.roster_bench rb ON rb.id = r.bench_id AND rb.display = 'Y'
            JOIN master.master_bench mb ON mb.id = rb.bench_id AND mb.display = 'Y'
            WHERE cast(r.m_f as integer) = :stg: 
            $t_cn
            AND rj.display = 'Y'
            AND r.display = 'Y'
            ORDER BY 
                courtno_order,
                board_type_order, 
                rj.judge_id                
        ";

        // Execute the query with bound parameters
        $query = $db->query($sql_ro, [
            'stg' => $stg,
            'crt' => $crt,
            'tdt1' => $tdt1
        ]);
 
        return $query->getResultArray();
    }

    public function getCases($tdt1, $mf, $result, $whereStatus = '')
    {
        $db = \Config\Database::connect();

        /*$sql_t = "
            SELECT 
                SUBSTRING(m.diary_no::TEXT FROM 1 FOR LENGTH(m.diary_no::TEXT) - 4) AS case_no,
                SUBSTRING(m.diary_no::TEXT FROM LENGTH(m.diary_no::TEXT) - 3) AS year,
                mb.board_type_mb,
                TO_CHAR(m.fil_dt, 'DD-MM-YYYY HH:MI AM') AS fil_dt_f,
                CASE 
                    WHEN m.reg_year_mh = 0 THEN EXTRACT(YEAR FROM m.fil_dt)
                    ELSE m.reg_year_mh
                END AS m_year,
                h.board_type,
                m.diary_no,
                m.mf_active,
                m.conn_key,
                h.judges,
                h.mainhead,
                h.next_dt,
                h.subhead,
                h.clno,
                h.brd_slno,
                h.tentative_cl_dt,
                m.pet_name,
                m.res_name,
                m.pet_adv_id,
                m.res_adv_id,
                m.c_status,
                CASE 
                    WHEN cl.next_dt IS NULL THEN 'NA'
                    ELSE h.brd_slno::TEXT
                END AS brd_prnt,
                h.roster_id,
                TO_CHAR(m.fil_dt_fh, 'DD-MM-YYYY HH:MI AM') AS fil_dt_fh,
                CASE 
                    WHEN m.reg_year_fh = 0 THEN EXTRACT(YEAR FROM m.fil_dt_fh)
                    ELSE m.reg_year_fh
                END AS f_year,
                CASE 
                    WHEN m.fil_no IS NOT NULL AND m.fil_no != '' THEN SPLIT_PART(m.fil_no, '-', 1)
                    ELSE ''
                END AS ct1,
                CASE 
                    WHEN m.fil_no IS NOT NULL AND m.fil_no != '' THEN SPLIT_PART(m.fil_no, '-', 2)
                    ELSE ''
                END AS crf1,
                CASE 
                    WHEN m.fil_no IS NOT NULL AND m.fil_no != '' THEN SPLIT_PART(m.fil_no, '-', 3)
                    ELSE ''
                END AS crl1,
                CASE 
                    WHEN m.fil_no_fh IS NOT NULL AND m.fil_no_fh != '' THEN SPLIT_PART(m.fil_no_fh, '-', 1)
                    ELSE ''
                END AS ct2,
                CASE 
                    WHEN m.fil_no_fh IS NOT NULL AND m.fil_no_fh != '' THEN SPLIT_PART(m.fil_no_fh, '-', 2)
                    ELSE ''
                END AS crf2,
                CASE 
                    WHEN m.fil_no_fh IS NOT NULL AND m.fil_no_fh != '' THEN SPLIT_PART(m.fil_no_fh, '-', 3)
                    ELSE ''
                END AS crl2,
                m.casetype_id,
                m.case_status_id
            FROM (
                SELECT 
                    t1.diary_no,
                    t1.board_type,
                    t1.next_dt,
                    t1.roster_id,
                    t1.judges,
                    t1.mainhead,
                    t1.subhead,
                    t1.clno,
                    t1.brd_slno,
                    t1.main_supp_flag,
                    t1.tentative_cl_dt
                FROM 
                    heardt t1
                WHERE 
                    t1.next_dt = :tdt1: 
                    AND t1.mainhead = :mf:
                    AND POSITION(',' || t1.roster_id || ',' IN ',' || :result: || ',') > 0
                    AND (t1.main_supp_flag = 1 OR t1.main_supp_flag = 2)
                UNION 
                SELECT 
                    t2.diary_no,
                    t2.board_type,
                    t2.next_dt,
                    t2.roster_id,
                    t2.judges,
                    t2.mainhead,
                    t2.subhead,
                    t2.clno,
                    t2.brd_slno,
                    t2.main_supp_flag,
                    t2.tentative_cl_dt
                FROM 
                    last_heardt t2
                WHERE 
                    t2.next_dt = :tdt1: 
                    AND t2.mainhead = :mf:
                    AND POSITION(',' || t2.roster_id || ',' IN ',' || :result: || ',') > 0
                    AND (t2.main_supp_flag = 1 OR t2.main_supp_flag = 2)
                    AND t2.bench_flag = ''
            ) h
            INNER JOIN main m ON (
                h.diary_no = m.diary_no 
                AND h.next_dt = :tdt1:
                AND h.mainhead = :mf:
                AND POSITION(',' || h.roster_id || ',' IN ',' || :result: || ',') > 0
                AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
            )
            LEFT JOIN cl_printed cl ON (
                cl.next_dt = h.next_dt 
                AND cl.m_f = h.mainhead 
                AND cl.part = h.clno 
                AND cl.main_supp = h.main_supp_flag 
                AND cl.roster_id = h.roster_id 
                AND cl.display = 'Y'
            )
            JOIN master.roster r ON r.id = h.roster_id
            JOIN master.roster_bench rb ON r.bench_id = rb.id
            JOIN master.master_bench mb ON mb.id = rb.bench_id
            WHERE cl.next_dt IS NOT NULL
            $whereStatus
            ORDER BY 
                h.judges,
                CASE 
                    WHEN cl.next_dt IS NULL THEN 2 
                    ELSE 1 
                END,
                h.brd_slno,
                CASE WHEN cast(m.conn_key as BIGINT) = h.diary_no THEN '' ELSE '99' END ASC,
                CAST(SUBSTRING(m.diary_no::TEXT FROM LENGTH(m.diary_no::TEXT) - 3) AS INTEGER) ASC,
                CAST(SUBSTRING(m.diary_no::TEXT FROM 1 FOR LENGTH(m.diary_no::TEXT) - 4) AS INTEGER) ASC
        ";*/

        $sql = "
            SELECT 
                SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4) AS case_no,
                SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3) AS year,
                mb.board_type_mb,
                TO_CHAR(m.fil_dt, 'DD-MM-YYYY HH:MI AM') AS fil_dt_f,
                CASE 
                    WHEN m.reg_year_mh = 0 THEN EXTRACT(YEAR FROM m.fil_dt) 
                    ELSE m.reg_year_mh 
                END AS m_year,
                h.board_type,
                m.diary_no,
                m.mf_active,
                m.conn_key,
                h.judges,
                h.mainhead,
                h.next_dt,
                h.subhead,
                h.clno,
                h.brd_slno,
                h.tentative_cl_dt,
                m.pet_name,
                m.res_name,
                m.pet_adv_id,
                m.res_adv_id,
                m.c_status,
                CASE
                    WHEN cl.next_dt IS NULL THEN 'NA'
                    ELSE h.brd_slno::text
                END AS brd_prnt,
                h.roster_id,
                TO_CHAR(m.fil_dt_fh, 'DD-MM-YYYY HH:MI AM') AS fil_dt_fh,
                CASE 
                    WHEN m.reg_year_fh = 0 THEN EXTRACT(YEAR FROM m.fil_dt_fh) 
                    ELSE m.reg_year_fh 
                END AS f_year,
                CASE 
                    WHEN m.fil_no != '' THEN SPLIT_PART(m.fil_no, '-', 1) 
                    ELSE '' 
                END AS ct1,
                CASE 
                    WHEN m.fil_no != '' THEN SPLIT_PART(SPLIT_PART(m.fil_no, '-', 2), '-', -1) 
                    ELSE '' 
                END AS crf1,
                CASE 
                    WHEN m.fil_no != '' THEN SPLIT_PART(m.fil_no, '-', -1) 
                    ELSE '' 
                END AS crl1,
                CASE 
                    WHEN m.fil_no_fh != '' THEN SPLIT_PART(m.fil_no_fh, '-', 1) 
                    ELSE '' 
                END AS ct2,
                CASE 
                    WHEN m.fil_no_fh != '' THEN SPLIT_PART(SPLIT_PART(m.fil_no_fh, '-', 2), '-', -1) 
                    ELSE '' 
                END AS crf2,
                CASE 
                    WHEN m.fil_no_fh != '' THEN SPLIT_PART(m.fil_no_fh, '-', -1) 
                    ELSE '' 
                END AS crl2,
                m.casetype_id,
                m.case_status_id
            FROM (
                SELECT 
                    diary_no, fil_dt, reg_year_mh, mf_active, conn_key, fil_dt_fh, reg_year_fh,
                    fil_no, fil_no_fh, pet_name, res_name, pet_adv_id, res_adv_id, c_status,
                    casetype_id, case_status_id
                FROM main
                UNION ALL
                SELECT 
                    diary_no, fil_dt, reg_year_mh, mf_active, conn_key, fil_dt_fh, reg_year_fh,
                    fil_no, fil_no_fh, pet_name, res_name, pet_adv_id, res_adv_id, c_status,
                    casetype_id, case_status_id
                FROM main_a
            ) m
            INNER JOIN (
                SELECT 
                    t1.diary_no,
                    t1.board_type::TEXT,
                    t1.next_dt,
                    t1.roster_id,
                    t1.judges,
                    t1.mainhead,
                    t1.subhead,
                    t1.clno,
                    t1.brd_slno,
                    t1.main_supp_flag,
                    t1.tentative_cl_dt
                FROM heardt t1
                WHERE t1.next_dt = :tdt1:
                  AND t1.mainhead = :mf:
                  AND POSITION(',' || t1.roster_id || ',' IN ',' || :result: || ',') > 0
                  AND (t1.main_supp_flag = 1 OR t1.main_supp_flag = 2)

                UNION  
                SELECT ta1.diary_no,
                       ta1.board_type::TEXT,
                       ta1.next_dt,
                       ta1.roster_id,
                       ta1.judges,
                       ta1.mainhead,
                       ta1.subhead,
                       ta1.clno,
                       ta1.brd_slno,
                       ta1.main_supp_flag,
                       ta1.tentative_cl_dt
                FROM heardt_a ta1
                WHERE ta1.next_dt = :tdt1:
                  AND ta1.mainhead = :mf:
                 AND POSITION(',' || ta1.roster_id || ',' IN ',' || :result: || ',') > 0
                  AND (ta1.main_supp_flag = 1 OR ta1.main_supp_flag = 2)
                  
                UNION ALL
                SELECT 
                    t2.diary_no,
                    t2.board_type::TEXT,
                    t2.next_dt,
                    t2.roster_id,
                    t2.judges,
                    t2.mainhead,
                    t2.subhead,
                    t2.clno,
                    t2.brd_slno,
                    t2.main_supp_flag,
                    t2.tentative_cl_dt
                FROM last_heardt t2
                WHERE t2.next_dt = :tdt1:
                  AND t2.mainhead = :mf:
                  AND POSITION(',' || t2.roster_id || ',' IN ',' || :result: || ',') > 0
                  AND (t2.main_supp_flag = 1 OR t2.main_supp_flag = 2)
                  AND t2.bench_flag = ''
                UNION
                SELECT 
                    ta2.diary_no,
                    ta2.board_type::TEXT,
                    ta2.next_dt,
                    ta2.roster_id,
                    ta2.judges,
                    ta2.mainhead,
                    ta2.subhead,
                    ta2.clno,
                    ta2.brd_slno,
                    ta2.main_supp_flag,
                    ta2.tentative_cl_dt
                FROM last_heardt_a ta2
                WHERE ta2.next_dt = :tdt1:
                  AND ta2.mainhead = :mf:
                  AND POSITION(',' || ta2.roster_id || ',' IN ',' || :result: || ',') > 0
                  AND (ta2.main_supp_flag = 1 OR ta2.main_supp_flag = 2)
                  AND ta2.bench_flag = ''
            ) h
            ON (h.diary_no = m.diary_no
                AND h.next_dt = :tdt1:
                AND h.mainhead = :mf:
                AND POSITION(',' || h.roster_id || ',' IN ',' || :result: || ',') > 0
                AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2))
            LEFT JOIN cl_printed cl 
                ON (cl.next_dt = h.next_dt
                    AND cl.m_f = h.mainhead
                    AND cl.part = h.clno
                    AND cl.main_supp = h.main_supp_flag
                    AND cl.roster_id = h.roster_id
                    AND cl.display = 'Y')
            JOIN master.roster r 
                ON r.id = h.roster_id
            JOIN master.roster_bench rb 
                ON r.bench_id = rb.id
            JOIN master.master_bench mb 
                ON mb.id = rb.bench_id
            WHERE cl.next_dt IS NOT NULL
            ORDER BY 
                h.judges,
                CASE WHEN cl.next_dt IS NULL THEN 2 ELSE 1 END,
                h.brd_slno,
                CASE
                    WHEN m.conn_key IS NULL OR NULLIF(m.conn_key, '') IS NULL THEN '99'
                    WHEN COALESCE(NULLIF(m.conn_key, '')::BIGINT, 0) = h.diary_no THEN ''
                    ELSE '99'
                END ASC,
                CAST(SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3) AS INTEGER) ASC,
                CAST(SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4) AS INTEGER) ASC
        ";

        $query = $db->query($sql, [
            'tdt1' => $tdt1,
            'mf' => $mf,
            'result' => $result
        ]);
        //echo $db->getLastQuery();
        return $query->getResultArray();
    }


    public function getCasesjcd($tdt1, $mf, $jcd)
        {
            $db = \Config\Database::connect();

            $sql_t = "
                SELECT 
                    m.casetype_id,
                    SUBSTRING(m.diary_no::TEXT FROM 1 FOR LENGTH(m.diary_no::TEXT) - 4) AS case_no, 
                    SUBSTRING(m.diary_no::TEXT FROM LENGTH(m.diary_no::TEXT) - 3) AS year,
                    mb.board_type_mb,  
                    TO_CHAR(m.fil_dt, 'DD-MM-YYYY HH:MI AM') AS fil_dt_f,
                    CASE 
                        WHEN m.reg_year_mh = 0 THEN EXTRACT(YEAR FROM m.fil_dt)
                        ELSE m.reg_year_mh
                    END AS m_year,
                    h.board_type,
                    m.diary_no,
                    m.mf_active,
                    m.conn_key,
                    h.judges,
                    h.mainhead,
                    h.subhead,
                    h.clno,
                    h.brd_slno,
                    m.pet_name,
                    m.res_name,
                    m.pet_adv_id,
                    m.res_adv_id,
                    m.c_status,
                    CASE 
                        WHEN cl.next_dt IS NULL THEN 'NA'
                        ELSE h.brd_slno::TEXT
                    END AS brd_prnt,
                    h.roster_id,
                    TO_CHAR(m.fil_dt_fh, 'DD-MM-YYYY HH:MI AM') AS fil_dt_fh,
                    CASE 
                        WHEN m.reg_year_fh = 0 THEN EXTRACT(YEAR FROM m.fil_dt_fh)
                        ELSE m.reg_year_fh
                    END AS f_year,
                    CASE 
                        WHEN m.fil_no IS NOT NULL AND m.fil_no != '' THEN SPLIT_PART(m.fil_no, '-', 1)
                        ELSE ''
                    END AS ct1,
                    CASE 
                        WHEN m.fil_no IS NOT NULL AND m.fil_no != '' THEN SPLIT_PART(SPLIT_PART(m.fil_no, '-', 2), '-', -1)
                        ELSE ''
                    END AS crf1,
                    CASE 
                        WHEN m.fil_no IS NOT NULL AND m.fil_no != '' THEN SPLIT_PART(m.fil_no, '-', -1)
                        ELSE ''
                    END AS crl1,
                    CASE 
                        WHEN m.fil_no_fh IS NOT NULL AND m.fil_no_fh != '' THEN SPLIT_PART(m.fil_no_fh, '-', 1)
                        ELSE ''
                    END AS ct2,
                    CASE 
                        WHEN m.fil_no_fh IS NOT NULL AND m.fil_no_fh != '' THEN SPLIT_PART(SPLIT_PART(m.fil_no_fh, '-', 2), '-', -1)
                        ELSE ''
                    END AS crf2,
                    CASE 
                        WHEN m.fil_no_fh IS NOT NULL AND m.fil_no_fh != '' THEN SPLIT_PART(m.fil_no_fh, '-', -1)
                        ELSE ''
                    END AS crl2,
                    m.casetype_id,
                    m.case_status_id,
                    m.reg_no_display
                FROM heardt h 
                INNER JOIN main m 
                    ON h.diary_no = m.diary_no 
                    AND h.next_dt = :tdt1: 
                    AND h.mainhead = :mf: 
                    AND POSITION(',' || :jcd: || ',' IN ',' || h.judges || ',') > 0 
                    AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
                LEFT JOIN cl_printed cl 
                    ON cl.next_dt = h.next_dt 
                    AND cl.m_f = h.mainhead 
                    AND cl.part = h.clno 
                    AND cl.main_supp = h.main_supp_flag 
                    AND cl.roster_id = h.roster_id 
                    AND cl.display = 'Y'
                JOIN roster r ON r.id = h.roster_id
                JOIN roster_bench rb ON r.bench_id = rb.id
                JOIN master_bench mb ON mb.id = rb.bench_id
                WHERE cl.next_dt IS NOT NULL
                ORDER BY 
                    POSITION(',' || :jcd: || ',' IN ',' || h.judges || ','), 
                    CASE 
                        WHEN cl.next_dt IS NULL THEN 2 
                        ELSE 1 
                    END,
                    h.brd_slno,
                    CASE 
                        WHEN m.conn_key = h.diary_no THEN '0000-00-00'
                        ELSE '99'
                    END ASC,
                    CAST(SUBSTRING(m.diary_no::TEXT FROM LENGTH(m.diary_no::TEXT) - 3) AS INTEGER) ASC,
                    CAST(SUBSTRING(m.diary_no::TEXT FROM 1 FOR LENGTH(m.diary_no::TEXT) - 4) AS INTEGER) ASC
            ";

            $query = $db->query($sql_t, [
                'tdt1' => $tdt1,
                'mf' => $mf,
                'jcd' => $jcd
            ]);

            return $query->getResultArray();
        }


        public function getRosterDetails($tdt1, $rosterId)
            {
                $db = \Config\Database::connect();

                $sql_rstr = "
                    SELECT 
                        roster.id,
                        roster.bench_id,
                        master_bench.abbr || ' - ' || roster_bench.bench_no AS bnch,
                        roster.m_f,
                        roster.session,
                        roster.frm_time,
                        roster.courtno
                    FROM 
                        master.roster
                    INNER JOIN 
                        master.roster_bench 
                        ON roster_bench.id = roster.bench_id 
                        AND roster.display = 'Y'
                    LEFT JOIN 
                        master.master_bench 
                        ON master_bench.id = roster_bench.bench_id 
                        AND roster_bench.display = 'Y'
                    WHERE 
                        (
                            CASE 
                                WHEN roster.to_date IS NULL THEN 
                                    DATE(:tdt1:) >= roster.from_date
                                ELSE 
                                    DATE(:tdt1:) BETWEEN roster.from_date AND roster.to_date
                            END
                        )
                        AND roster.display = 'Y'
                        AND roster.id = :rosterId:
                ";

                $query = $db->query($sql_rstr, [
                    'tdt1' => $tdt1,
                    'rosterId' => $rosterId
                ]);

                return $query->getRowArray();
            }

        public function get_advocates($adv_id){
                
                $db = \Config\Database::connect();
                $t_adv = "";

                $builder = $db->table('master.bar');
                $builder->select('name,enroll_no,enroll_date as eyear, isdead');
                $builder->where(['bar_id' => $adv_id]);
                $query = $builder->get();
                $row11a = $query->getRowArray();                    
                    if (!empty($row11a)) {                        
                            $t_adv=$row11a['name'];
                        
                    }
                return $t_adv;
            }

    
            public function getJoAlottmentPaps($diaryNo, $tdt1)
            {
                $db = \Config\Database::connect();
            
                $sql_paps = "
                    SELECT 
                        a.usercode, 
                        (SELECT name 
                         FROM master.users 
                         WHERE usercode = a.usercode 
                           AND display = 'Y') AS uname 
                    FROM 
                        jo_alottment_paps a 
                    WHERE 
                        a.diary_no = :diaryNo: 
                        AND a.display = 'Y' 
                        AND a.cl_date = :clDate:
                ";
            
                $query = $db->query($sql_paps, [
                    'diaryNo' => $diaryNo,
                    'clDate'  => date("Y-m-d", strtotime($tdt1))
                ]);
            
                return $query->getResultArray();
            }
            
            
            public function getConnectedCases($tmpCaseNo)
            {
                $db = \Config\Database::connect();
            
                $sql_connected = "
                    SELECT 
                        CONCAT(h.diary_no, ';', m.reg_no_display) AS num 
                    FROM 
                        heardt h 
                    JOIN 
                        main m 
                    ON 
                        h.diary_no = m.diary_no 
                    WHERE 
                        h.conn_key = :tmpCaseNo: 
                        AND h.clno != 0 
                        AND h.brd_slno != 0 
                        AND h.judges != '' 
                        AND h.main_supp_flag IN (1, 2) 
                        AND h.diary_no != h.conn_key 
                    ORDER BY 
                        CASE WHEN cast(m.conn_key as BIGINT) = h.diary_no THEN '0000-00-00' ELSE '99' END ASC, 
                        CAST(SUBSTRING(h.diary_no::TEXT FROM LENGTH(h.diary_no::TEXT) - 3 FOR 4) AS INTEGER) ASC, 
                        CAST(SUBSTRING(h.diary_no::TEXT FROM 1 FOR LENGTH(h.diary_no::TEXT) - 4) AS INTEGER) ASC;
                ";
            
                $query = $db->query($sql_connected, [
                    'tmpCaseNo' => $tmpCaseNo
                ]);
            
                return $query->getResultArray();
            }
     
            public function getLowerCourtDetails($diaryNo)
            {
                $db = \Config\Database::connect();
            
                $sql_lct = "
                    SELECT 
                        a.lct_dec_dt, 
                        a.lct_caseno, 
                        a.lct_caseyear, 
                        ct.short_description AS type_sname
                    FROM 
                        lowerct a
                    LEFT JOIN 
                        master.casetype ct 
                    ON 
                        ct.casecode = a.lct_casetype 
                        AND ct.display = 'Y'
                    WHERE 
                        a.diary_no = :diary_no: 
                        AND a.lw_display = 'Y' 
                        AND a.ct_code = 4 
                        AND a.is_order_challenged = 'Y'
                    ORDER BY 
                        a.lct_dec_dt;
                ";
            
                $query = $db->query($sql_lct, [
                    'diary_no' => $diaryNo
                ]);
            
                return $query->getResultArray();
            }

            public function getFinalDetails($diaryNo)
            {
                $db = \Config\Database::connect();
            
                $sql_final = "
                    SELECT 
                        z1.filno,
                        TO_CHAR(z1.cldate, 'DD-MM-YYYY') AS cldate
                    FROM (
                        SELECT 
                            * 
                        FROM (
                            SELECT 
                                lh.diary_no AS filno, 
                                lh.next_dt AS cldate
                            FROM 
                                last_heardt lh
                            WHERE 
                                lh.diary_no = :diary_no:
                                AND lh.mainhead = 'F'
                                AND lh.clno > 0                                
                                AND lh.next_dt < CURRENT_DATE
            
                            UNION ALL
            
                            SELECT 
                                cast(cr.diary_no as BIGINT) AS filno, 
                                cr.cl_date AS cldate
                            FROM 
                                case_remarks_multiple cr
                            WHERE 
                                cr.diary_no = :diary_no:
                                AND cr.r_head IN (81, 74, 75, 65, 2, 1, 94)
                        ) z
                        ORDER BY z.cldate
                    ) z1
                    GROUP BY z1.filno, z1.cldate;
                ";
            
                $query = $db->query($sql_final, [
                    'diary_no' => $diaryNo
                ]);
            
                return $query->getRowArray();
            }

        
            public function getRegularJudges()
                {
                    $db = \Config\Database::connect();

                    $sql_reg = "
                        SELECT
                            t1.courtno,
                            CONCAT(t3.jname, ' ', t3.first_name, ' ', t3.sur_name) AS jname
                        FROM
                            master.roster t1
                        INNER JOIN
                            master.roster_judge t2 ON t1.id = t2.roster_id
                        INNER JOIN
                            master.judge t3 ON t3.jcode = t2.judge_id
                        WHERE
                            CURRENT_DATE >= t1.from_date
                            AND (t1.to_date IS NULL)
                            AND t3.jtype = 'R'
                            AND t3.is_retired = 'N'
                            AND t1.display = 'Y'
                            AND t2.display = 'Y'
                        ORDER BY
                            t3.jcode
                    ";

                    $query = $db->query($sql_reg);

                    return $query->getResultArray();
                }

        
                public function getCaseRemarksHead()
                {
                    $db = \Config\Database::connect();
                
                    $sql11 = "
                        SELECT * 
                        FROM master.case_remarks_head 
                        WHERE 
                            side = 'P' 
                            AND display = 'Y'
                            AND sno NOT IN (
                                146, 104, 90, 91, 145, 130, 128, 125, 155, 32, 55, 57, 58, 117, 154, 156, 
                                105, 191, 84, 102, 83, 150, 106, 153, 159, 126, 38, 152, 148, 131, 118, 11, 
                                93, 25, 123, 122, 151, 60, 127, 59, 129, 157, 158, 69
                            )
                        ORDER BY 
                            CASE 
                                WHEN cat_head_id < 1000 THEN 0 
                                ELSE 1 
                            END, 
                            head;
                    ";
                
                  return  $query = $db->query($sql11);
                
                   // return $query->getResultArray();
                }


        public function getCaseRemarksHeadForD()
        {
            $db = \Config\Database::connect();
        
            $sql11 = "
                SELECT * 
                FROM master.case_remarks_head 
                WHERE 
                    side = 'D' 
                    AND display = 'Y'
                    AND sno NOT IN (
                        33, 42, 144, 163, 164, 40, 167, 29, 37, 31, 78, 73, 134, 168, 43, 41, 
                        166, 169, 161, 160, 44, 173, 45, 187, 165, 34
                    )
                ORDER BY 
                    CASE 
                        WHEN sno IN (134, 144, 27, 28, 30, 36) THEN 0 
                        ELSE 1 
                    END, 
                    head;
            ";
        
            $query = $db->query($sql11);
        
            return $query->getResultArray();
        }
        
        
        public function getCaseRemarks($tmp_caseno, $dt_t1, $jcodes11)
        {
            $builder = $this->db->table('case_remarks_multiple c');
            $builder->select('c.r_head, h.side, c.head_content, h.head');
            $builder->join('master.case_remarks_head h', 'c.r_head = h.sno');
            $builder->where('c.diary_no', $tmp_caseno);
            $builder->where('c.cl_date', $dt_t1);
            $builder->where('c.jcodes', $jcodes11);
            $builder->where('c.remove', 0);
            $builder->orderBy('c.e_date', 'DESC');
            //echo $builder->getCompiledSelect();
            //die;
            return $builder->get()->getResultArray();
        }
        
        function getDropNotes($list_dt,$mainhead,$roster_id)
        {
            $builder = $this->db->table('drop_note d');
            $builder->select("
                d.clno, 
                COALESCE(d.nrs, '-') AS nrs,
                d.mf,
                d.diary_no,
                CASE
                    WHEN m.active_reg_year IS NULL OR m.active_reg_year = 0
                    THEN m.diary_no::TEXT
                    ELSE CONCAT(
                        c.short_description, '/',
                        CASE
                            WHEN TRIM(LEADING '0' FROM SPLIT_PART(SPLIT_PART(m.active_fil_no, '-', 2), '-', -1)) = TRIM(LEADING '0' FROM SPLIT_PART(m.active_fil_no, '-', -1))
                            THEN TRIM(LEADING '0' FROM SPLIT_PART(SPLIT_PART(m.active_fil_no, '-', 2), '-', -1))
                            ELSE CONCAT(
                                TRIM(LEADING '0' FROM SPLIT_PART(SPLIT_PART(m.active_fil_no, '-', 2), '-', -1)),
                                '-',
                                TRIM(LEADING '0' FROM SPLIT_PART(m.active_fil_no, '-', -1))
                            )
                        END, '/', m.active_reg_year
                    )
                END AS case_no
            ");
            $builder->join('main m', 'm.diary_no = d.diary_no');
            $builder->join('master.casetype c', 'c.casecode = m.active_casetype_id', 'left');
            $builder->where('d.cl_date', $list_dt);
            $builder->where('d.display', 'Y');
            $builder->where('d.roster_id', $roster_id);
            $builder->where('d.mf', $mainhead);
            $builder->orderBy('d.clno');
            //echo $builder->getCompiledSelect();
            //die;
            $res =  $builder->get()->getResultArray();

                   // $res=mysql_query($sql) or die(mysql_error());   
                    if(!empty($res)){
                        ?>
            <tr><td align="center" colspan="5">
                        <table class="mobview" border="1" style="font-size:12px; text-align: left; background: #ffffff;" cellspacing=0>
                            <tr><td style="text-align:left" colspan="3"><U>DROP NOTE</U>:-</td></tr>
                        <tr><td style="text-align:left">Item No.</td><td style="text-align:left">Case No.</td><td style="text-align:left">Reason</td></tr>
                    <?php
                        foreach($res as $row){    
                    ?>
                        <tr>
                            <td style="text-align:left">
                                <?php echo $row['clno'] ?>
                            </td>
                            <td style="text-align:left">
                                <?php echo $row['case_no'] ?>
                            </td>
                            <td style="text-align:left">
                                <?php echo $row['nrs'] ?>
                            </td>
                        </tr>      
                <?php
                }
                ?>   </table>
                    </td></tr><?php
                    }    
        } 
        
        

        public function getDocumentDetails($diaryNo)
            {
                $sql = "
                    SELECT 
                        a.diary_no,
                        a.doccode,
                        a.doccode1,
                        a.docnum,
                        a.docyear,
                        a.filedby,
                        a.docfee,
                        a.forresp,
                        a.feemode,
                        a.ent_dt,
                        a.other1,
                        a.iastat,
                        b.docdesc,
                        DATE(a.lst_mdf) AS lstmdf
                    FROM 
                        docdetails a
                    INNER JOIN 
                        master.docmaster b 
                        ON a.doccode = b.doccode AND a.doccode1 = b.doccode1
                    WHERE 
                        a.diary_no = :diary_no: 
                        AND a.doccode = 8 
                        AND a.display = 'Y' 
                        AND b.display = 'Y'
                    ORDER BY 
                        a.ent_dt
                ";

                return $this->db->query($sql, ['diary_no' => $diaryNo])->getResultArray();
            }


    public function clear_remarks($fno_o,$dt_o,$ucode1,$on)
    {
        
        $results_cis = is_data_from_table('case_remarks_multiple', " diary_no='$fno_o'  AND cl_date='$dt_o' GROUP BY status ", "status", $row = 'A');
        if(!empty($results_cis))
        {
            foreach($results_cis as $row_cis)
            {
                if($on=='H' || $on=='')
                {
                    if($row_cis["status"]=="D")
                    {
    
                        $str_del_disp="DELETE FROM dispose WHERE diary_no='".$fno_o."'";
                       // echo $str_del_disp1 = "INSERT INTO dispose_delete(diary_no, `month`, dispjud, `year`, ord_dt, disp_dt, disp_type, bench, jud_id, camnt, crtstat, usercode, ent_dt, jorder, rj_dt, disp_type_all) (SELECT diary_no, `month`, dispjud, `year`, ord_dt, disp_dt, disp_type, bench,jud_id, camnt, crtstat, usercode, ent_dt, jorder, rj_dt,disp_type_all FROM dispose where diary_no='".$fno_o."')";
                        $str_del_disp1 = "
                            INSERT INTO dispose_delete (
                                diary_no, month, dispjud, year, ord_dt, disp_dt, disp_type, bench, jud_id, camnt, crtstat, usercode, ent_dt, jorder, rj_dt, disp_type_all,dispose_updated_by
                            )
                            SELECT 
                                diary_no, month, dispjud, year, ord_dt, disp_dt, disp_type, bench, jud_id, camnt, crtstat, usercode, ent_dt, jorder, rj_dt, disp_type_all,$ucode1
                            FROM dispose
                            WHERE diary_no = $fno_o
                        ";
                        $this->db->query($str_del_disp1);
                        $this->db->query($str_del_disp);
      
                        $str_upd_main="UPDATE main SET last_dt=NOW(), lastorder='', c_status='P', last_usercode=".$ucode1." WHERE diary_no='".$fno_o."'";                        
                        $this->db->query($str_upd_main);

                        $str_up_heardt="UPDATE heardt SET listorder=CASE  WHEN listorder = 48 THEN 16 ELSE listorder END   where diary_no='".$fno_o."'";                       
                        $this->db->query($str_up_heardt);
     
                        $rgo="Update rgo_default set remove_def='N' WHERE fil_no2='".$fno_o."'";                      
                        $this->db->query($rgo);
     
                    }
                    if($row_cis["status"]=="P")
                    {
                        $str_upd_main="UPDATE main SET last_dt=NOW(), lastorder='', c_status='P', last_usercode=".$ucode1." WHERE diary_no='".$fno_o."'";                       
                        $this->db->query($str_upd_main);

                        $str_up_heardt="UPDATE heardt SET listorder=CASE  WHEN listorder = 48 THEN 16 ELSE listorder END  where diary_no='".$fno_o."'";
                        $this->db->query($str_up_heardt);
                       
                    }
                }
            }

            $queryres = $this->db->table('case_remarks_multiple')
             ->where('diary_no', $fno_o)
             ->where('cl_date', $dt_o)
             ->get()->getResultArray();

             if (!empty($queryres)) {
                $queryres = array_map(function($record) {
                    unset($record['diary_no']);
                    return $record;
                }, $queryres);

                $this->db->table('case_remarks_multiple_history')->insertBatch($queryres);
                 
            }  

             


           // $str_del_to_history="INSERT INTO case_remarks_multiple_history (SELECT * FROM case_remarks_multiple where diary_no='".$fno_o."' AND cl_date='".$dt_o."')";           
            //$this->db->query($str_del_to_history);
    
            $str_cis1 = "SELECT * FROM case_remarks_multiple where diary_no='".$fno_o."' AND cl_date='".$dt_o."' AND r_head IN (81,74,75,65,2,1,94)";
            $query = $this->db->query($str_cis1);
            $affectedRows = $query->getNumRows();
           
            if($affectedRows > 0)
            {
                $str_upmain1 = "UPDATE main SET admitted='' where diary_no='".$fno_o."' and admitted like 'admitted on ".$dt_o."%'";
                $this->db->query($str_upmain1);                
            }
    
            $str_del_cr="DELETE FROM case_remarks_multiple where diary_no='".$fno_o."' AND cl_date='".$dt_o."'";
            $this->db->query($str_del_cr);           
        }
    }

    public function getRegistrationNumberDisplay($diaryNo,$registrationNumber,$registrationYear)
    {
        $previousRegistrationNumber = $regNoDisplay = "";
        $caseType = substr($registrationNumber, 0, 2);
        $reg1 = substr($registrationNumber, 3, 6);
        if(strlen($registrationNumber)>9)
            $reg2 = substr($registrationNumber, 10, 6);
        else 
            $reg2 = substr($registrationNumber, 3, 6);
         
        $row = is_data_from_table('master.casetype', " casecode='$caseType' and display='Y' ", " short_description,cs_m_f ", $row = '');
        $res_ct_typ = $row['short_description'] ?? '';
        $res_ct_typ_mf = $row['cs_m_f'] ?? '';

        if ($caseType == 9 || $caseType == 10 || $caseType == 19 || $caseType == 20 || $caseType == 25 || $caseType == 26 || $caseType == 39) {
           

            $query = "
                SELECT m.reg_no_display
                FROM main m,
                    (SELECT lct_casetype, lct_caseno AS caseNumber, lct_caseyear AS caseYear 
                    FROM lowerct 
                    WHERE diary_no = ? AND ct_code = 4) ld
                WHERE (
                        CHAR_LENGTH(m.active_fil_no) > 10 AND 
                        (active_casetype_id = ld.lct_casetype AND 
                        CAST(ld.caseNumber AS INTEGER) 
                        BETWEEN CAST(SPLIT_PART(SPLIT_PART(active_fil_no, '-', 2), '-', -1) AS INTEGER) 
                        AND CAST(SPLIT_PART(active_fil_no, '-', -1) AS INTEGER) 
                        AND active_reg_year = ld.caseYear)
                    )
                OR (
                        CHAR_LENGTH(m.active_fil_no) = 9 AND 
                        (active_casetype_id = ld.lct_casetype AND 
                        CAST(ld.caseNumber AS INTEGER) = 
                        CAST(SPLIT_PART(SPLIT_PART(active_fil_no, '-', 2), '-', -1) AS INTEGER) 
                        AND active_reg_year = ld.caseYear)
                    )
                UNION
                SELECT main.reg_no_display
                FROM main_casetype_history mch, main,
                    (SELECT lct_casetype, lct_caseno AS caseNumber, lct_caseyear AS caseYear 
                    FROM lowerct 
                    WHERE diary_no = ? AND ct_code = 4) ld
                WHERE mch.diary_no = main.diary_no AND 
                    (
                        (CHAR_LENGTH(mch.old_registration_number) > 10 AND 
                        (ref_old_case_type_id = ld.lct_casetype AND 
                        CAST(ld.caseNumber AS INTEGER) 
                        BETWEEN CAST(SPLIT_PART(SPLIT_PART(old_registration_number, '-', 2), '-', -1) AS INTEGER) 
                        AND CAST(SPLIT_PART(old_registration_number, '-', -1) AS INTEGER) 
                        AND old_registration_year = ld.caseYear)
                        )
                    OR 
                        (CHAR_LENGTH(old_registration_number) = 9 AND 
                        (ref_old_case_type_id = ld.lct_casetype AND 
                        CAST(ld.caseNumber AS INTEGER) = 
                        CAST(SPLIT_PART(SPLIT_PART(old_registration_number, '-', 2), '-', -1) AS INTEGER) 
                        AND old_registration_year = ld.caseYear)
                        )
                    OR
                        (CHAR_LENGTH(mch.new_registration_number) > 10 AND 
                        (ref_new_case_type_id = ld.lct_casetype AND 
                        CAST(ld.caseNumber AS INTEGER) 
                        BETWEEN CAST(SPLIT_PART(SPLIT_PART(new_registration_number, '-', 2), '-', -1) AS INTEGER) 
                        AND CAST(SPLIT_PART(new_registration_number, '-', -1) AS INTEGER) 
                        AND new_registration_year = ld.caseYear)
                        )
                    OR 
                        (CHAR_LENGTH(new_registration_number) = 9 AND 
                        (ref_new_case_type_id = ld.lct_casetype AND 
                        CAST(ld.caseNumber AS INTEGER) = 
                        CAST(SPLIT_PART(SPLIT_PART(new_registration_number, '-', 2), '-', -1) AS INTEGER) 
                        AND new_registration_year = ld.caseYear)
                        )
                    )
            ";

            // Execute the query using CodeIgniter 4's Query Builder
             
            $row_result = $this->db->query($query, [$diaryNo, $diaryNo])->getResultArray();
              
            $previousRegistrationNumber = $row_result['reg_no_display'];
        }
        if ($reg1 == $reg2)
            $regNoDisplay = $res_ct_typ . " " . (int)$reg1 . '/' . $registrationYear;
        else
            $regNoDisplay = $res_ct_typ . " " . (int)$reg1 . '-' . (int)$reg2 . '/' . $registrationYear;

        if ($previousRegistrationNumber != "" && $previousRegistrationNumber != null) {
            $regNoDisplay .= " in " . $previousRegistrationNumber;
        }
        return $regNoDisplay;
    }


    public function check_section($dacode,$matter_section){
        $ucode = $_SESSION['login']['usercode'];;
        $da_section_qr="select * from users where usercode='$dacode' and display='Y'";
        $da_section_rs = $this->db->query($da_section_qr);
        $da_data = $da_section_rs->getRowArray();
        
        if($da_data['section'] != $matter_section){
            $this->db->query("insert into matters_with_wrong_section(diary_no,dacode,da_section_id,matter_section_id,ent_by,ent_on) values('$_REQUEST[dno]','$dacode','$da_data[section]','$matter_section','$ucode',now())");
            return;
        }   
    
    }


    public function get_da($dno)
    {
        $ucode = $_SESSION['login']['usercode'];;
            $sec_da_upto_disposal = array(21,55);
            $sec_da_diary=array();

            $builder = $this->db->table('main');
                $builder->select("
                    section_id,
                    dacode,
                    from_court,
                    ref_agency_state_id,
                    ref_agency_code_id,
                    CASE
                        WHEN (active_casetype_id = 0 OR active_casetype_id IS NULL OR active_casetype_id = '') THEN casetype_id
                        ELSE active_casetype_id
                    END AS casetype_id,
                    EXTRACT(YEAR FROM 
                        CASE
                            WHEN (active_fil_dt = '0000-00-00 00:00:00' OR active_fil_dt IS NULL OR active_fil_dt = '') THEN diary_no_rec_date
                            ELSE active_fil_dt
                        END
                    ) AS regyear,
                    DATE(diary_no_rec_date) AS fildate,
                    DATE(
                        CASE
                            WHEN (active_fil_dt = '0000-00-00 00:00:00' OR active_fil_dt IS NULL OR active_fil_dt = '') THEN diary_no_rec_date
                            ELSE active_fil_dt
                        END
                    ) AS filregdate
                ");

                // Add the WHERE condition
                $builder->where('diary_no', $dno);

                // Execute the query
                $query = $builder->get();
                $row_main = $query->getRowArray();

                 
                if(!empty($row_main)){
                    $rcasetype=array(1,3);                     
                    //check if dacode already exist and section is matching with da section  matters_with_wrong_section
                    if($row_main['dacode']!=0 && $row_main['dacode']!=''){
                        if(in_array($row_main['section_id'], $sec_da_upto_disposal)) {
                            return;
                        }
                    }

                    $previous_daname = array(39,9,10,19,20,25,26);
                    $forXandPIL = array(5,6);
                    if(in_array($row_main['casetype_id'], $previous_daname)){
                         $lower_case_temp_row = is_data_from_table('lowerct', " diary_no = '$dno' and lw_display='Y' ", " ct_code,l_state,lct_casetype,lct_caseno,lct_caseyear ", $row = '');
                        if(!empty($lower_case_temp_row)){
                            //$lower_case_temp_row = mysql_fetch_array($lower_case_temp);

                         

                            $lct_case_temp_no_padded = str_pad($lower_case_temp_row['lct_caseno'], 6, '0', STR_PAD_LEFT);

                            $builder = $this->db->table('main_casetype_history a');
                            $builder->select("
                                b.dacode,
                                a.diary_no,
                                new_registration_number,
                                split_part(split_part(new_registration_number, '-', 2), '-', -1) AS new_reg_start,
                                split_part(new_registration_number, '-', -1) AS new_reg_end,
                                b.dacode,
                                c.name,
                                us.section_name,
                                b.casetype_id,
                                b.active_casetype_id,
                                b.diary_no_rec_date,
                                b.reg_year_mh,
                                b.reg_year_fh,
                                b.active_reg_year,
                                b.ref_agency_state_id
                            ");
                            $builder->join('main b', 'a.diary_no = b.diary_no', 'left');
                            $builder->join('master.users c', 'b.dacode = c.usercode', 'left');
                            $builder->join('master.usersection us', 'c.section = us.id', 'left');
                            $builder->where('a.ref_new_case_type_id', $lower_case_temp_row['lct_casetype']);
                            $builder->where('a.new_registration_year', $lower_case_temp_row['lct_caseyear']);
                            $builder->where('a.is_deleted', 'f');
                            $builder->where(
                                "'$lct_case_temp_no_padded' BETWEEN 
                                split_part(split_part(new_registration_number, '-', 2), '-', -1)::int 
                                AND 
                                split_part(new_registration_number, '-', -1)::int"
                            );

                            // Execute the query
                            $query = $builder->get();
                            $row_da = $query->getRowArray();
                            
                           
                            if(!empty($row_da)){
                                
                                $this->check_section($row_da['dacode'],$row_main['section_id']);

                                $update = "UPDATE main SET dacode=$row_da[dacode], last_usercode=$ucode,last_dt=NOW() WHERE diary_no=$dno";
                                $this->db->query($update);
                                
                            }
                        }
                    }
                    else{
                        $dacodeallotted=0;
                        if(in_array($row_main['casetype_id'], $forXandPIL)){
                            $query = "SELECT submaster_id FROM mul_category WHERE diary_no='$dno' AND submaster_id
                            IN ( 349,118,119,120,121,122,123,124,125,126,127,128,129,130,131,132,133,318,332,567,568,569,570,571,572,573,574,575,576,577,578,579,580,581,582 ) AND display = 'Y'";
                            $submaster = $this->db->query($query);
                            $submaster_rs = $submaster->getRowArray();
                            if(!empty($submaster_rs)){

                                $this->check_section('690',$row_main['section_id']);

                                $update = "UPDATE main SET dacode=690, last_usercode=$ucode,last_dt=NOW() WHERE diary_no=$dno";
                                $this->db->query($update);
                                
                                $dacodeallotted=1;
                            }
                            else
                                $dacodeallotted=0;
                        }
                        else if($row_main['from_court']=='5')
                        {

                            $tribunal='';
                            
                            $ref_agency_code_id = $row_main['ref_agency_code_id'];
                            $tribunal_sec_arr = is_data_from_table('master.ref_agency_code', " id=$ref_agency_code_id ", " agency_or_court ", $row = '');
                            
                            if(!empty($tribunal_sec_arr)){
                               
                                $tribunal=$tribunal_sec_arr['agency_or_court'];
                            }
                            if($tribunal==5){
                               

                                $query = $this->db->table('master.da_case_distribution_tri')
                                    ->select('dacode')
                                    ->where('case_type', $row_main['casetype_id'])
                                    ->where("EXTRACT(YEAR FROM NOW()) BETWEEN case_f_yr AND case_t_yr")
                                    ->where("ref_agency IS NOT NULL")
                                    ->where("$row_main[ref_agency_code_id] = ANY(string_to_array(ref_agency, ','))")
                                    ->where('display', 'Y')
                                    ->get();                               
                                $numRows = $query->getNumRows();
                                if($numRows > 0)
                                {
                                    if($numRows > 1)
                                    {
                                        $dacodeallotted=0;
                                    }
                                    else{
                                        $row_da = $query->getRowArray();
                                        $this->check_section($row_da['dacode'],$row_main['section_id']);
                                        $update = "UPDATE main SET dacode=$row_da[dacode], last_usercode=$ucode,last_dt=NOW() WHERE diary_no=$dno";
                                       $this->db->query($update);
                                        $dacodeallotted=1;
                                    }
                                }
                                else if(in_array($row_main['casetype_id'],$rcasetype)){
                                    

                                    $rw_bo = is_data_from_table('master.users', " section=82 and usertype=14 and display='Y' ", " usercode ", $row = '');
                                    $bocode=$rw_bo['usercode'];

                                    $this->check_section($bocode,$row_main['section_id']);

                                    $update = "UPDATE main SET dacode='$bocode', last_usercode=$ucode,last_dt=NOW() WHERE diary_no=$dno";
                                    //mysql_query($update) or die(__LINE__.'->'.mysql_error());
                                    $this->db->query($update);
                                    $dacodeallotted=1;
                                }
                            }
                            else{

                               

                                $query = $this->db->table('master.da_case_distribution_tri')
                                    ->select('dacode')
                                    ->where('case_type', $row_main['casetype_id'])
                                    ->where("EXTRACT(YEAR FROM NOW()) BETWEEN case_f_yr AND case_t_yr")
                                    ->where("ref_agency IS NOT NULL")
                                    ->where("$row_main[ref_agency_code_id] = ANY(string_to_array(ref_agency, ','))")
                                    ->where('display', 'Y')
                                    ->get();
                               
                                $numRows = $query->getNumRows();

                                if($numRows >0 ){
                                    if($numRows > 1)
                                    {
                                        $dacodeallotted=0;
                                    }
                                    else{
                                         
                                        $row_da = $query->getRowArray();

                                        $this->check_section($row_da['dacode'],$row_main['section_id']);

                                        $update = "UPDATE main SET dacode=$row_da[dacode], last_usercode=$ucode,last_dt=NOW() WHERE diary_no=$dno";
                                       
                                        $this->db->query($update);
                                        $dacodeallotted=1;
                                    }
                                }
                                else if(in_array($row_main['casetype_id'],$rcasetype)){
                                     
                                    $rw_bo = is_data_from_table('master.users', " section=52 and usertype=14 and display='Y' ", " usercode ", $row = '');
                                    $bocode=$rw_bo['usercode'];

                                    $this->check_section($bocode,$row_main['section_id']);


                                    $update = "UPDATE main SET dacode='$bocode', last_usercode=$ucode,last_dt=NOW() WHERE diary_no=$dno";
                                     
                                    $this->db->query($update);
                                    $dacodeallotted=1;
                                }



                            }

                        }

                        //else
                        if($dacodeallotted==0)
                        {
                            //if da allocation to be done in diary matters which is diarized in previous year but allocated in current year
                            if($row_main['regyear']<date("Y") and  !in_array($row_main['section_id'], $sec_da_upto_disposal) )
                                $row_main['regyear']=date("Y");

                           
                            $number_for = $this->db->table('main a')
                                ->select('a.diary_no, a.fil_dt, ROW_NUMBER() OVER (ORDER BY a.fil_dt) AS rownum')
                                ->where('ref_agency_state_id', $row_main['ref_agency_state_id'])
                                ->where('active_casetype_id', $row_main['casetype_id'])
                                ->where("EXTRACT(YEAR FROM COALESCE(NULLIF(a.active_fil_dt, '0000-00-00 00:00:00'), a.diary_no_rec_date))", $row_main['regyear'])
                                ->get();

                            $number_for_rs = $number_for->getResultArray();


                            
                            $current_no = 1;
                            if(!empty($number_for_rs))
                            {
                                foreach($number_for_rs as $row_number_for){
                                    if($row_number_for['diary_no'] == $_REQUEST['dno'])
                                        $current_no = $row_number_for['rownum'];
                                }
                            }


                            if(in_array($row_main['section_id'], $sec_da_upto_disposal)){
                               
                                $query = $this->db->table('master.da_case_distribution_new')
                                    ->select('dacode')
                                    ->where('case_type', $row_main['casetype_id'])
                                    ->where("$current_no BETWEEN case_from AND case_to")
                                    ->where("'{$row_main['fildate']}'::DATE BETWEEN case_f_yr AND case_t_yr")
                                    ->groupStart() // Start grouping for OR condition
                                    ->where('state', $row_main['ref_agency_state_id'])
                                    ->orWhere('state', 0)
                                    ->groupEnd() // End grouping
                                    ->where('display', 'Y')
                                    ->get();

                                $result = $query->getResultArray();
                            }
                            else {                                

                                $query = $this->db->table('master.da_case_distribution_new')
                                    ->select('dacode')
                                    ->where('case_type', $row_main['casetype_id'])
                                    ->where("$current_no BETWEEN case_from AND case_to")
                                    ->where("DATE('{$row_main['filregdate']}') BETWEEN case_f_yr AND case_t_yr")
                                    ->groupStart() // Start grouping for OR condition
                                    ->where('state', $row_main['ref_agency_state_id'])
                                    ->orWhere('state', 0)
                                    ->groupEnd() // End grouping
                                    ->where('display', 'Y')
                                    ->get();

                                    $result = $query->getResultArray();


                                if(empty($result)) {                                    
                                    
                                        $query = $this->db->table('master.da_case_distribution_new')
                                            ->select('dacode')
                                            ->where('case_type', $row_main['casetype_id'])
                                            ->where("$current_no BETWEEN case_from AND case_to")
                                            ->where("{$row_main['regyear']}  BETWEEN case_f_yr AND case_t_yr")
                                            ->groupStart() // Start grouping for OR condition
                                            ->where('state', $row_main['ref_agency_state_id'])
                                            ->orWhere('state', 0)
                                            ->groupEnd() // End grouping
                                            ->where('display', 'Y')
                                            ->get();
        
                                        $result = $query->getResultArray();
                                }
                            }                             
                            if(!empty($result) && count($result) == 1){                                
                                    $row_da = $result;
                                    $this->check_section($row_da['dacode'],$row_main['section_id']);

                                    $update = "UPDATE main SET dacode=$row_da[dacode], last_usercode=$ucode,last_dt=NOW() WHERE diary_no=$dno";
                                    $this->db->query($update);                                     
                                 
                            }
                            else{
                            }
                        }
                    }
                }
            }

    
    //function end

    function update_cis($fno_o,$dt_o,$head_o,$head_cont_o,$hdt,$ucode1,$hl,$snop/*,$nextCourt*/)
    {

        $t_hr=0;$t_lo=0;$t_head_code='';$disp_code_all='';$is_nmd='N';$nmd_head_content="";
        $t_chk=0;//check for 37,78,73 Disposed in Default
            
        if($hl=="L")
                $row_cis = is_data_from_table('last_heardt', " diary_no='".$fno_o."' AND next_dt='".$dt_o."' and clno!=0 and brd_slno!=0 and judges!='' and main_supp_flag in (1,2) and bench_flag='' ", " diary_no, next_dt, judges, subhead,mainhead,listorder,mainhead_n,is_nmd ", $row = '');
        else
            $row_cis = is_data_from_table('heardt', " diary_no='".$fno_o."' AND next_dt='".$dt_o."' and clno!=0 and brd_slno!=0 and judges!='' and main_supp_flag in (1,2) ", " diary_no, next_dt, judges, subhead,mainhead,listorder,mainhead_n,is_nmd ", $row = '');
         
       
        if(!empty($row_cis))
        {
            $up_str="";$side="";$disp_code="";$subhead=0;$nature="";$disp_type_all="";                    
            $t_j1=explode(',',$row_cis["judges"]);
            $j1=$t_j1[0];
            $judges=$row_cis["judges"];
            $subhead=$row_cis["subhead"];
            $is_nmd='Y';


            $t_fix_month=0;
            for($i=0;$i<count($head_o);$i++)
            {
                if($head_o[$i]=="7")
                    $t_hr=1;
                if($head_o[$i]=="68")
                    $t_fix_month=1;
                

                if( $head_o[$i]=="53" or $head_o[$i]=="54" or $head_o[$i]=="23" or $head_o[$i]=="68" or $head_o[$i]=="8" or $head_o[$i]=="21")
                    $t_lo=2;
                //    if($head_o[$i]=="24") $t_lo=1;

                if($head_o[$i]=="37" or $head_o[$i]=="73" or $head_o[$i]=="78"){
                    $t_chk=1;
                }
                if($head_o[$i]=="180"){
                    $is_nmd='Y';
                    $nmd_head_content=$head_cont_o[$i];
                }

                if($head_o[$i]=="207")
                {
                $t_lo=3;
                }
                    if($head_o[$i]=="24")   // to check1
                        $t_lo = 1;

                
                $head_o_i = $head_o[$i];
                $row_cr = is_data_from_table('master.case_remarks_head', " sno=$head_o_i ", " sno,head,side,cis_disp_code ", $row = '');
                if(!empty($row_cr))
                {
                    if($i>0)
                        $up_str.=", ";                             
                    $up_str.=$row_cr["head"];

                    if($head_cont_o[$i]!="")
                        $up_str.=" (".$head_cont_o[$i].")";

                    $side=$row_cr["side"];
                    $disp_code=$row_cr["cis_disp_code"];
                    if($disp_type_all=='')
                        $disp_type_all.= $row_cr["cis_disp_code"];
                    else
                        $disp_type_all.= ",".$row_cr["cis_disp_code"];


                }
            }
                if($side=="")
                    $side="P";
                $tdt=explode("-",$dt_o);
                $up_str.="-Ord dt:".$tdt[2]."-".$tdt[1]."-".$tdt[0];
                
                //TENTATIVE DATE START

             

           /* $builder = $this->db->table('docdetails');
            $builder->select([
                "COUNT(CASE WHEN position(',' || doccode1 || ',' LIKE '%,40,%' OR doccode1 IN (40, 48, 49, 50, 63) THEN 1 END) AS c1",
                "COUNT(CASE WHEN position(',' || doccode1 || ',' LIKE '%,2,%' OR doccode1 IN (2, 27, 28, 56, 57) THEN 1 END) AS c2"
            ]);
            $builder->where('diary_no', $fno_o);
            $builder->where('doccode', 8);
            $builder->where("(position(',' || doccode1 || ',' LIKE '%,40,%' OR doccode1 IN (40, 48, 49, 50, 63)) > 0 
                            OR position(',' || doccode1 || ',' LIKE '%,2,%' OR doccode1 IN (2, 27, 28, 56, 57)) > 0");
            $builder->where('iastat', 'P');
            $builder->where('display', 'Y');
            $query = $builder->get();
            $row_ia = $query->getRow(); */

            $sql_check_ia = "SELECT
                COUNT(CASE WHEN doccode1 = ANY(ARRAY[40,48,49,50,63]) THEN 1 END) AS c1,
                COUNT(CASE WHEN doccode1 = ANY(ARRAY[2,27,28,56,57]) THEN 1 END) AS c2
            FROM docdetails
            WHERE diary_no = ? 
            AND doccode = 8
            AND doccode1 = ANY(ARRAY[40,48,49,50,63,2,27,28,56,57])
            AND iastat = 'P'
            AND display = 'Y'";

            $query = $this->db->query($sql_check_ia, [$fno_o]);
            $row_ia = $query->getRowArray();

            if($row_ia['c1'] > 0)
                $check_ia=" 1=1 ";
            else
                $check_ia=" 1=2 ";
            if($row_ia['c2'] > 0)
                $check_ia1=" 1=1 ";
            else
                $check_ia1=" 1=2 ";
            ///NEW CODE FOR NOT REACHED CASES
            if($row_cis["mainhead"]=="M")
                $check_mf=" 1=1 ";
            else
                $check_mf=" 1=2 ";

            $check_for_nr1=" b1.sno in (16,19) AND ".$check_ia1." AND ".$check_mf;
            $check_for_nr2=" string_to_array(r1.rhead, ',')::int[] && ARRAY[16,19] AND ".$check_ia1." AND ".$check_mf;

            if($row_cis["listorder"]==5)
                $check_mm=" 1=1 ";
            else
                $check_mm=" 1=2 ";
            ///NEW SQL
                    $tdt_str="";

                  
  $sql_td = "SELECT
 r1.*,
 (
     CASE 
         WHEN r1.pcnt > 1 THEN (
             SELECT
                 CONCAT(
                     CASE 
                         WHEN b1.compliance_limit_in_day > 0 THEN (
                             CASE 
                                 WHEN (b1.sno IN (16, 19, 125, 132, 145, 159) 
                                       AND $subhead != ANY(ARRAY[804,805,806,822,823]) 
                                       AND NOT $check_ia)
                                      OR (b1.sno IN (5, 146) AND $subhead = ANY(ARRAY[811,812])) 
                                 THEN (
                                     CASE 
                                         WHEN ($subhead != ANY(ARRAY[813,814]) AND NOT $check_for_nr1)
                                              OR b1.sno IN (125, 132, 145, 159)
                                              OR (b1.sno IN (5, 146) AND $subhead = ANY(ARRAY[811,812])) 
                                         THEN (
                                             CASE 
                                                 WHEN b1.sno IN (16, 19, 125, 132, 145, 159) 
                                                 THEN 
                                                     CAST('$dt_o' AS DATE) + (CASE WHEN '{$row_cis["mainhead"]}' = 'F' 
                                                                    THEN INTERVAL '3 days' 
                                                                    ELSE INTERVAL '6 days' END)
                                                 ELSE 
                                                    CAST('$dt_o' AS DATE) + INTERVAL '1 day' * (9 - EXTRACT(DOW FROM CAST('$dt_o' AS DATE)))
                                             END
                                         )
                                         ELSE (
                                             CASE 
                                                 WHEN ($subhead = ANY(ARRAY[813,814]) AND $check_mm) OR $check_for_nr1 
                                                 THEN 
                                                     CAST('$dt_o' AS DATE) + (CASE WHEN $check_for_nr1 
                                                                    THEN INTERVAL '3 days' 
                                                                    ELSE INTERVAL '2 days' END)
                                                 ELSE 
                                                    CAST('$dt_o' AS DATE) + INTERVAL '28 days'
                                             END
                                         )
                                     END
                                 )
                                 ELSE (
                                     CASE 
                                         WHEN (b1.sno IN (5, 146) AND ($subhead = ANY(ARRAY[804,805,806,822,823]) OR $check_ia)) 
                                         THEN CAST('$dt_o' AS DATE) + INTERVAL '2 days'
                                         WHEN (b1.sno = 130 AND ($subhead = ANY(ARRAY[804,805,806,822,823]) OR $check_ia)) 
                                         THEN CAST('$dt_o' AS DATE) + INTERVAL '7 days'
                                         ELSE 
                                             CAST('$dt_o' AS DATE) + INTERVAL '1 day' * (b1.compliance_limit_in_day + 1)
                                     END
                                 )
                             END
                         )
                         ELSE NULL
                     END,
                     '||', b1.sno
                 ) AS ttdt
             FROM master.case_remarks_head b1
             WHERE b1.sno = ANY(string_to_array(r1.rhead, ',')::int[])
             ORDER BY ttdt
             LIMIT 1
         )
         ELSE (
             CONCAT(
                 CASE 
                     WHEN r1.compliance_limit_in_day > 0 THEN (
                         CASE 
                             WHEN (string_to_array(r1.rhead, ',')::int[] && ARRAY[16, 19, 125, 132, 145, 159] 
                                   AND $subhead != ANY(ARRAY[804,805,806,822,823]) 
                                   AND NOT $check_ia)
                                  OR (string_to_array(r1.rhead, ',')::int[] && ARRAY[5, 146] AND $subhead = ANY(ARRAY[811,812])) 
                             THEN (
                                 CASE 
                                     WHEN ($subhead != ANY(ARRAY[813,814]) AND NOT $check_for_nr2)
                                          OR string_to_array(r1.rhead, ',')::int[] && ARRAY[125, 132, 145, 159]
                                          OR (string_to_array(r1.rhead, ',')::int[] && ARRAY[5, 146] AND $subhead = ANY(ARRAY[811,812])) 
                                     THEN (
                                         CASE 
                                             WHEN string_to_array(r1.rhead, ',')::int[] && ARRAY[16, 19, 125, 132, 145, 159] 
                                             THEN 
                                                 CAST('$dt_o' AS DATE) + (CASE WHEN '{$row_cis["mainhead"]}' = 'F' 
                                                                THEN INTERVAL '3 days' 
                                                                ELSE INTERVAL '6 days' END)
                                             ELSE 
                                                 CAST('$dt_o' AS DATE) + INTERVAL '1 day' * (9 - EXTRACT(DOW FROM CAST('$dt_o' AS DATE)))
                                         END
                                     )
                                     ELSE (
                                         CASE 
                                             WHEN ($subhead = ANY(ARRAY[813,814]) AND $check_mm) OR $check_for_nr2 
                                             THEN 
                                                 CAST('$dt_o' AS DATE) + (CASE WHEN $check_for_nr2 
                                                                THEN INTERVAL '3 days' 
                                                                ELSE INTERVAL '2 days' END)
                                             ELSE 
                                                 CAST('$dt_o' AS DATE) + INTERVAL '28 days'
                                         END
                                     )
                                 END
                             )
                             ELSE (
                                 CASE 
                                     WHEN (string_to_array(r1.rhead, ',')::int[] && ARRAY[5, 146] AND ($subhead = ANY(ARRAY[804,805,806,822,823]) OR $check_ia)) 
                                     THEN CAST('$dt_o' AS DATE) + INTERVAL '2 days'
                                     WHEN (string_to_array(r1.rhead, ',')::int[] && ARRAY[130] AND ($subhead = ANY(ARRAY[804,805,806,822,823]) OR $check_ia)) 
                                     THEN CAST('$dt_o' AS DATE) + INTERVAL '7 days'
                                     ELSE 
                                         CAST('$dt_o' AS DATE) + INTERVAL '1 day' * (r1.compliance_limit_in_day + 1)
                                 END
                             )
                         END
                     )
                     ELSE NULL
                 END,
                 '||', r1.rhead
             )
         )
     END
 ) AS tdate
FROM (
 SELECT 
     string_agg(a1.r_head::TEXT, ',') AS rhead,
     a1.head_content,
     a.compliance_limit_in_day,
     a.priority,
     CASE WHEN a.priority = 999 THEN 2 ELSE 1 END AS pcnt
 FROM master.case_remarks_head a
 JOIN case_remarks_multiple a1 ON a.sno = a1.r_head
 WHERE a1.diary_no = '$fno_o'
 AND a1.cl_date = '$dt_o'
 GROUP BY a.priority, a.compliance_limit_in_day, a1.head_content
 LIMIT 1
) r1;
";
 

$query = $this->db->query($sql_td);
 

 
                    $results_dt = $query->getRowArray();
                    if(!empty($results_dt))
                    {
                        $row_dt=$results_dt;

                        $t_tdt=explode("||", $row_dt["tdate"]);
                        //if(count($t_tdt)==2)
                        $t_head_code="";
                        if(count($t_tdt)>1)
                        {
                            $pdate = date('d-m-Y', strtotime($t_tdt[0]));
                            //if( $t_tdt[1]!=24 )
                            if( $t_tdt[1]==15 )
                                $pdate = "12-12-2099";
                            if( $t_tdt[1]==84 )
                                $pdate = "12-12-2088";
                            //code added by preeti on 24072019 to give next unpublish advance list date if List on NMD is selected
                            if($t_tdt[1]==180)
                            {
                                //query to get unpublished advanced list date
                                
                                $sql = "
                                    SELECT working_date
                                    FROM master.sc_working_days
                                    WHERE working_date > (
                                        SELECT MAX(next_dt)
                                        FROM advance_cl_printed
                                        WHERE next_dt > CURRENT_DATE
                                    )
                                    AND is_holiday = 0
                                    ORDER BY working_date ASC
                                    LIMIT 1
                                ";                               
                                $query = $this->db->query($sql);
                                $row_unpublish_cl = $query->getRowArray();
                                $pdate=$row_unpublish_cl['working_date'];

                            }
                            //code added on 24072019 ends
             
                            $oc1="N";
           
                            $pdate = date('Y-m-d', strtotime($pdate));
                            $pdate = $this->get_next_working_date_new($pdate,$t_tdt[1],$row_cis["mainhead"]);

            
                            if($row_cis["subhead"]=='817' && $row_cis["mainhead_n"]=='F' && $row_cis["mainhead"]=='M'){
                                //Added for urgent hearing regular matters listed in Misc head Dated: 20-07-2018
                                $is_nmd='Y';
                                $nmd_head_content="ANY";
                            }
                           
                            else {
                                if (($t_lo == 0 ||$t_lo==2) && $is_nmd == 'Y') {
                                     
                                    $pdate = $this->getNextNmdDateNew($pdate);
                                    
                                }
                            }
                            if($t_lo==3)
                            {
                                $pdate= $this->getNextNmdDateNew($dt_o);
                            }

                            
                            $t_date='';
                            //code added by preeti on 15072019
                            $court_remarks=0;
                            //query to get unpublished advanced list date                             
                            $sqlunpublishcl = "
                                SELECT working_date
                                FROM master.sc_working_days
                                WHERE working_date > (
                                    SELECT MAX(next_dt)
                                    FROM advance_cl_printed
                                    WHERE next_dt > CURRENT_DATE
                                )
                                AND is_holiday = 0
                                ORDER BY working_date ASC
                                LIMIT 1
                            ";

                            // Execute the query
                            $query = $this->db->query($sqlunpublishcl); 
                            $row_unpublish_cl = $query->getRowArray();


                            //query to know whether matter has been listed in hon'ble court                          

                            $sqllistcourt = "
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
 
                            $query = $this->db->query($sqllistcourt, [$fno_o, $fno_o]);
                            // Fetch the results
                            $row_list_court = $query->getRowArray();

                            for($i=0;$i<count($head_o);$i++) {
                                if ($head_o[$i] == "80" )
                                {
                                    $court_remarks=1;
                                    if($row_list_court['next_dt']==0 or $row_list_court['next_dt']==null) {

                                        $dt1= date('Y-m-d', strtotime($dt_o. ' + 7 days'));
                                    }
                                    else if($row_list_court['next_dt']!=0 and $row_list_court['next_dt']!=null)
                                    {

                                        $dt1=$row_unpublish_cl['working_date'] ?? '';
                                    }
                                }
                            }
                            if ($court_remarks==1 and $t_lo==0)
                            {
                                $tdt_str=" tentative_cl_dt='".$dt1."' " ;
                            //  $board=",board_type='".$nextCourt."'";
                                $t_date=$dt_o;
                            }
                            //new code end
                            else if ($t_hr==1 and $t_lo==0) {
            
                                $tdt_str=" tentative_cl_dt='".$dt_o."' " ;
                            // $board=",board_type='".$nextCourt."'";
                                $t_date=$dt_o;
                            }
                            else
                            {
                                
                                $tdt_str=" tentative_cl_dt='".$pdate."' ";
                            
                                $t_date=$pdate;
                            }
                            if($t_head_code=="")
                                $t_head_code.=$t_tdt[1];
                            else
                                $t_head_code.=",".$t_tdt[1];
                        }
                    }

            //TENTATIVE DATE END
                    if($hl=='H' || $hl=='' || $snop==1){
                        if($tdt_str!="" /*and $nextCourt!=""*/ ){
                            if($snop==1 && $hl=='L')
                                $str_up_heardt="UPDATE heardt SET ".$tdt_str." where diary_no='".$fno_o."' and next_dt < '".$t_date."'";
                            else
                                $str_up_heardt="UPDATE heardt SET ".$tdt_str." where diary_no='".$fno_o."'";
                            
                            $this->db->query($str_up_heardt);                            
                        }
                         
                    }
                    if($hl=='L'){
                        if($tdt_str!=""){
                            $str_up_heardt="UPDATE last_heardt SET ".$tdt_str." where diary_no='".$fno_o."' AND next_dt='".$dt_o."' and clno!=0 and brd_slno!=0 and judges!='' and main_supp_flag in (1,2) and bench_flag=''";
                            $this->db->query($str_up_heardt);                            
                        }
                    }
                    if(($hl=='H' || $hl=='') || ($hl=='L' && $snop==1 )){
                        $str_up_main = "UPDATE main SET last_dt=NOW(),lastorder='".addslashes($up_str)."', head_code='".$t_head_code."' ,c_status='".$side."', last_usercode=".$ucode1." where diary_no='".$fno_o."'";
                        $this->db->query($str_up_main);
                    }
 

                    if($side=="D")
                    {
                        $row_selm = is_data_from_table('main', " diary_no=$fno_o ", " bench ", $row = '');
                        $bench="";
                        if(!empty($row_selm))
                        {
                            $bench=$row_selm["bench"];
                        }                        
                        $results_disp = is_data_from_table('dispose', " diary_no=$fno_o ", " * ", $row = '');
            
                        $disp_str=$up_str;
            ///////ADDED for month year correct entry
                        $dday=$dmonth=$dyear=0;
                        $hdt1=explode("-",$hdt);
                        $dmonth=$hdt1[1];
                        $dyear=$hdt1[0];
                        $dday=$hdt1[2];
                        $t_month= $this->chk_disp_date($hdt);

                        if(intval($t_month)==1){
                            if(intval(date('d'))>=15){
                                $dmonth=date('m');
                                $dyear=date('Y');
                            }
            
                        }
                        if(intval($t_month)>=2){
                            $dmonth=date('m');
                            $dyear=date('Y');
                        }
            ////END ADDED for month year correct entry
                        if($t_chk==1){
                            $dmonth=date('m');
                            $dyear=date('Y');
                        }

                        if($row_cis["mainhead"]=="L")
                            $temp_mh="L";
                        else
                            $temp_mh="R";
                        if(!empty($results_disp))
                        {
                            
                            //$str_up_disp1 = "INSERT INTO dispose_delete(diary_no, `month`, dispjud, `year`, ord_dt, disp_dt, disp_type, bench, jud_id, camnt, crtstat, usercode, ent_dt, jorder, rj_dt, disp_type_all) (SELECT diary_no, `month`, dispjud, `year`, ord_dt, disp_dt, disp_type, bench,jud_id, camnt, crtstat, usercode, ent_dt, jorder, rj_dt,disp_type_all FROM dispose where diary_no='".$fno_o."')";
                            $str_up_disp1 = "
                                    INSERT INTO dispose_delete (
                                        diary_no, month, dispjud, year, ord_dt, disp_dt, disp_type, bench, jud_id, camnt, crtstat, usercode, ent_dt, jorder, rj_dt, disp_type_all,dispose_updated_by
                                    )
                                    SELECT 
                                        diary_no, month, dispjud, year, ord_dt, disp_dt, disp_type, bench, jud_id, camnt, crtstat, usercode, ent_dt, jorder, rj_dt, disp_type_all,$ucode1
                                    FROM dispose
                                    WHERE diary_no = '$fno_o'
                                ";
                            $this->db->query($str_up_disp1);

                            $str_up_disp = "UPDATE dispose SET month=".$dmonth.",year=".$dyear.",dispjud='".$j1."', ord_dt='".$dt_o."', disp_dt='".$hdt."',disp_type=".$disp_code.", bench='".$bench."',jud_id='".$judges."',ent_dt=NOW(),camnt=0,usercode=".$ucode1.",crtstat='".$temp_mh."',jorder='',disp_type_all='".$disp_type_all."' where diary_no='".$fno_o."'";
                            $this->db->query($str_up_disp);                            
                        }
                        else
                        {
                            $str_up_disp = "INSERT INTO dispose(diary_no, \"month\",\"year\",dispjud,ord_dt,disp_dt,disp_type,bench,jud_id,ent_dt,camnt,usercode,crtstat,jorder,disp_type_all) VALUES('".$fno_o."',".$dmonth.",".$dyear.",'".$j1."','".$dt_o."','".$hdt."',".$disp_code.",'".$bench."','".$judges."',NOW(),0,".$ucode1.",'".$temp_mh."','','".$disp_type_all."')";
                            $this->db->query($str_up_disp);                            
                        }                        
            
                        $rgo="Update rgo_default set remove_def='Y' WHERE fil_no2='".$fno_o."'";
                        $this->db->query($rgo);                         
            
                    }
            }
    }
//FUNCTION END

    public function chk_disp_date($disp_dt){
        
            $m1=date('m',strtotime($disp_dt));
            $y1=date('Y',strtotime($disp_dt));
            $m2=date('m');
            $y2=date('Y');
            $y=$y2-$y1;
            if($m2>=$m1){
                $m=$m2-$m1;
            }
            else
            {
                $m=12-($m1-$m2);
                $y--;
            }
            $rm=($y*12)+$m;
            return $rm;
        }

        public function getNextNmdDateNew($listing_date)
        {
            //removed is_nmd=1 and added DAYOFWEEK(working_date)=3 by preeti on 30.4.2024
            $heardtNmdQuery = "
                SELECT working_date AS working_date 
                FROM master.sc_working_days 
                WHERE EXTRACT(DOW FROM working_date) = 2  -- 2 for Tuesday
                AND is_holiday = 0 
                AND working_date > ? 
                ORDER BY working_date ASC 
                LIMIT 1
            ";
 
            $query = $this->db->query($heardtNmdQuery, [$listing_date]); 
            $row_cis = $query->getRowArray();

            if(!empty($row_cis) && !empty($row_cis['working_date'])){            
                return date($row_cis['working_date']);
            }else{
                return '';
            }
        }


        public function get_next_working_date_new($dt,$head_no,$mf) {
            if($head_no!=24){
                $start = strtotime($dt);
                $t_var='';
                $cdate1='';
                $ivar = 0;
        
                while($cdate1 == '')
                {
                    $t_loop=$ivar+15;
                    for($ivar; $ivar < $t_loop; $ivar++)
                    {
                        if($t_var=='')
                            $t_var.="select '".date('Y-m-d', strtotime("+".$ivar." day", $start))."' as cdates ";
                        else
                            $t_var.=" union select '".date('Y-m-d', strtotime("+".$ivar." day", $start))."'  as cdates ";
                    }
        
                    $sql = "
                    SELECT
                        *,
                        CASE
                            WHEN (wd = 0) THEN cdates
                        END AS c1,
                        CASE
                            WHEN (wk = 1) THEN MAX(cdates)
                            ELSE MIN(cdates)
                        END AS c2,
                        MIN(
                            CASE
                                WHEN (wd = 1 OR wd = 2 OR wd = 3) THEN cdates
                            END
                        ) AS r1
                    FROM
                        (
                            SELECT
                                t1.cdates,
                                EXTRACT(DOW FROM t1.cdates::DATE) AS wd,  -- Cast cdates to DATE
                                EXTRACT(WEEK FROM t1.cdates::DATE) - EXTRACT(WEEK FROM DATE '".$dt."') + 1 AS wk  -- Ensure date comparison
                            FROM
                                (".$t_var.") t1
                                LEFT JOIN master.holidays t2 ON t1.cdates::DATE = t2.hdate  -- Explicit cast to DATE
                            WHERE EXTRACT(DOW FROM t1.cdates::DATE) NOT IN (5, 6)  -- Skip Saturday (5) and Sunday (6)
                                AND t2.hdate IS NULL
                        ) z1
                    GROUP BY z1.wk,z1.cdates,z1.wd;
                ";
                
                $query = $this->db->query($sql);
                $results = $query->getResultArray();
                


                   if(!empty($results))
                   {
                        foreach($results as $row){
                            if($mf=='F'){
                                if(!(is_null($row['r1'])) && $cdate1==''){
                                    $cdate1=$row['r1'];
                                }
                            } else
                            {
                                if(!(is_null($row['c1'])) && $cdate1==''){
                                    $cdate1=$row['c1'];
                                }
                                if(!(is_null($row['c2'])) && $cdate1==''){
                                    $cdate1=$row['c2'];
                                }
                            }
                        }
                    }
                }
            }
            else{
                $cdate1=$dt;
            }
        
            return date('Y-m-d', strtotime($cdate1));
        }

    
    public function get_cases_mf($dn)
    {
        $str_pass="";
        $t_fil_no='';         

        $sql = "
            WITH main_query AS (
                SELECT 
                    CASE 
                        WHEN fil_no != '' THEN m.fil_no
                        ELSE ''
                    END AS cn1,
                    CASE 
                        WHEN fil_no_fh != fil_no AND fil_no_fh != '' THEN m.fil_no_fh
                        ELSE ''
                    END AS cn2
                FROM main m
                WHERE diary_no = ?
            ),
            history_query AS (
                SELECT 
                    t.cn1,
                    STRING_AGG(
                        t.new_registration_number::TEXT, ', ' ORDER BY t.order_date, t.id
                    ) AS cn2
                FROM (
                    SELECT 
                        ROW_NUMBER() OVER (ORDER BY mch.order_date, mch.id) AS rowid,
                        mch.*,
                        CASE 
                            WHEN ROW_NUMBER() OVER (ORDER BY mch.order_date, mch.id) = 1 AND 
                                (old_registration_number IS NOT NULL AND old_registration_number != '')
                            THEN old_registration_number
                            ELSE ''
                        END AS cn1
                    FROM main_casetype_history mch
                    WHERE diary_no = ? 
                    AND is_deleted = 'f'
                ) t
                GROUP BY t.diary_no, t.cn1
            )
            SELECT * FROM (
                SELECT * FROM main_query
                UNION
                SELECT * FROM history_query
            ) tbl;
        ";
 
        $query = $this->db->query($sql, [$dn, $dn]);     
        $result_main = $query->getResultArray();

        $cases="";
            if(!empty($result_main))
            {
                $tmp_cno='';
                $case_in_m=$case_in_m1=$case_in_f=$case_in_f1='';
                    foreach($result_main as $row_main){
                        $cn1=$row_main['cn1'];
                        $cn2=$row_main['cn2'];
                        if(substr($cn1,0,2)=='13' or substr($cn1,0,2)=='14')
                            $cn1='';
                        if(substr($cn2,0,2)=='13' or substr($cn2,0,2)=='14')
                            $cn2='';
                            if($cn1!=''){
                            $t_cn=explode(',',$cn1);
                                for($i=0; $i<count($t_cn); $i++){
                                     
                                    $t_cn_i = "CAST(SUBSTRING($t_cn[$i],1,2) as INTEGER)";
                                    $row = is_data_from_table('master.casetype', " casecode=$t_cn_i and display='Y' ", " casecode,cs_m_f,short_description ", $row = '');
                                    if(!empty($row)){
                                        
                                        if($row['cs_m_f']=='M' && $case_in_m==''){
                                            $case_in_m=$row['short_description'];
                                            $case_in_m1=$row['casecode'];
                                        }
                                        if($row['cs_m_f']=='F' && $case_in_f==''){
                                            $case_in_f=$row['short_description'];
                                            $case_in_f1=$row['casecode'];
                                        }
                                    }
                                }
                            }
                    
                        if($cn2!=''){
                        $t_cn=explode(',',$cn2);
                            for($i=0; $i<count($t_cn); $i++){
                                 $t_cn_i = "CAST(SUBSTRING($t_cn[$i],1,2) as INTEGER)";
                                $row = is_data_from_table('master.casetype', " casecode=$t_cn_i and display='Y' ", " casecode,cs_m_f,short_description ", $row = '');

                                if(!empty($row)){
                                     if($row['cs_m_f']=='M' and $case_in_m==''){
                                        $case_in_m=$row['short_description'];
                                        $case_in_m1=$row['casecode'];
                                    }
                                    if($row['cs_m_f']=='F' and $case_in_f==''){
                                        $case_in_f=$row['short_description'];
                                        $case_in_f1=$row['casecode'];
                                    }
                                }
                            }
                        }
                    }
            }
            if(trim($case_in_m)!='' and trim($case_in_f)=='' )
            {
                    $row_m = is_data_from_table('master.m_to_r_casetype_mapping', " m_casetype=$case_in_m1 and display='Y' ", " r_casetype ", $row = '');
                        if (!empty($row_m)) {
                            $str_pass="N#0||Y#".$row_m['r_casetype']."||";
                        }
                        else
                        $str_pass="N#0||N#0||";  
            }
            if(trim($case_in_m)!='' and trim($case_in_f)!='' )
            {
                $str_pass="N#0||N#0||"; 
            }
            $t_in_dn='';
         if(trim($case_in_m)=='' and trim($case_in_f)=='')
         {
             $str_pass="";
            $t_in_dn="yes";
            
            $builder = $this->db->table('master.casetype c');
            $builder->select('c.casecode, c.cs_m_f, c.short_description, m.casetype_id');
            $builder->join('main m', 'c.casecode = m.casetype_id');
            $builder->where('m.diary_no', $dn);  
            
            $query = $builder->get();
            $row_12 = $query->getRowArray();

            
                if (!empty($row_12)) 
                {
                     if($row_12['cs_m_f']=='M'){
                        $case_in_m=$row_12['short_description'];
                        $case_in_m1=$row_12['casecode'];
                        $str_pass.="Y#".$row_12['casecode']."||";

                         $casetype_id = $row_12['casetype_id'];
                        $row_m = is_data_from_table('master.m_to_r_casetype_mapping', " m_casetype=$casetype_id and display='Y' ", " r_casetype ", $row = '');
                                if (!empty($row_m)) {
                                    $str_pass.="Y#".$row_m['r_casetype']."||";
                                }
                                else
                                $str_pass.="N#0||";  
                    }
        
           
                    if($row_12['cs_m_f']=='F')
                    {
                        $case_in_f=$row_12['short_description'];
                        $case_in_f1=$row_12['casecode'];
                        $str_pass="N#0||Y#".$row_12['casecode']."||";
                    }
                }
         }
        return $t_in_dn.",".$case_in_m.",".$case_in_m1.",".$case_in_f.",".$case_in_f1.",".$str_pass.",";   
    }



    public function getOrderDetails($fromDate, $toDate, $o_o) {
        $fromDate = $this->db->escape($fromDate);
        $toDate = $this->db->escape($toDate);
        //$o_o = $this->db->escape($o_o);
    
        // Define the query
        $sql = "
            SELECT 
                ont.id, 
                ont.diary_no, 
                petn, 
                resp, 
                roster_id, 
                perj, 
                orderdate, 
                type, 
                prnt_name, 
                prnt_dt, 
                ent_dt, 
                pdfname, 
                pet_name, 
                res_name, 
                b.reg_no_display
            FROM 
                ordernet ont
            JOIN 
                main b ON ont.diary_no = b.diary_no
            WHERE 
                ent_dt IS NOT NULL
                AND $o_o BETWEEN $fromDate AND $toDate
                AND pdfname != ''
        ";
         
        // Execute the query
        $query = $this->db->query($sql);
    
        // Return the result set
        return $query->getResultArray();
    }

    public function getJudgeName($rosterId) {
        // Escape inputs to prevent SQL injection
        $rosterId = $this->db->escape($rosterId);
    
        // Define the query
        $sql = "
            SELECT 
                b.jname 
            FROM 
                master.roster_judge a
            JOIN 
                master.judge b ON a.judge_id = b.jcode
            WHERE 
                a.roster_id = $rosterId
                AND a.display = 'Y'
                AND b.display = 'Y'
        ";
    
        // Execute the query
        $query = $this->db->query($sql);
    
        // Return the result set
        return $query->getResultArray();
    }

    public function getUserDetails($judges)
    {
        
        $judges = (!empty($judges)) ? ' AND u.jcode IN (' .  $judges. ')' : ''; 
        $builder = $this->db->table('master.users u');
        $builder->select('u.usercode, u.name, uh.disp_flag');
        $builder->join(
            'master.usertype uh',
            'u.usertype = uh.id AND uh.display = \'Y\' AND u.display = \'Y\' AND u.usertype IN (16, 52) '.$judges,
            'inner'
        );
        $builder->orderBy('u.usertype, u.name');
        
        return $builder->get()->getResultArray();
    }


    public function getRosterDetailsReport($judgeCode)
    {
         
        $builder = $this->db->table('master.roster t1');
        $builder->select("DISTINCT (t1.courtno), CONCAT(t3.jname, ' ', t3.first_name, ' ', t3.sur_name) AS jname,t3.jcode");
        $builder->join('master.roster_judge t2', 't1.id = t2.roster_id', 'inner');
        $builder->join('master.judge t3', 't3.jcode = t2.judge_id', 'inner');
        $builder->join(
            'cl_printed cp',
            "cp.next_dt = '" . date('Y-m-d') . "' AND cp.roster_id = t1.id AND cp.display = 'Y'",
            'left'
        );
        $builder->where('cp.next_dt IS NOT NULL');
        $builder->where("'" . date('Y-m-d') . "' >= t1.from_date");
        $builder->where("t1.to_date IS NULL");
        $builder->where("t3.jtype", 'R');
        $builder->where("t3.is_retired", 'N');
        $builder->where("t1.display", 'Y');
        $builder->where("t2.display", 'Y');
        
        if (!empty($judgeCode)) {
            $builder->where($judgeCode);
        }
        
        $builder->orderBy('t3.jcode');
       // pr($builder->getCompiledSelect());
        return $builder->get()->getResultArray();
    }

    public function getRosterDetailsWithJudges($dtd, $judgeCode = '')
    {
        $builder = $this->db->table('master.roster r');
        $builder->select("
            r.id, 
            STRING_AGG(j.jcode::TEXT, ', ' ORDER BY j.judge_seniority) AS jcd, 
            STRING_AGG(j.jname, ', ' ORDER BY j.judge_seniority) AS jnm, 
            MIN(j.first_name) AS first_name, 
            MIN(j.sur_name) AS sur_name, 
            title, 
            r.courtno, 
            rb.bench_no, 
            mb.abbr, 
            mb.board_type_mb, 
            r.tot_cases, 
            r.frm_time, 
            r.session
        ");

        $builder->join('master.roster_bench rb', 'rb.id = r.bench_id', 'left');
        $builder->join('master.master_bench mb', 'mb.id = rb.bench_id', 'left');
        $builder->join('master.roster_judge rj', 'rj.roster_id = r.id', 'left');
        $builder->join('master.judge j', 'j.jcode = rj.judge_id', 'left');
        $builder->join('cl_printed cp', "cp.next_dt = " . $this->db->escape($dtd) . " AND cp.roster_id = r.id AND cp.display = 'Y'", 'left');

        // Apply WHERE conditions
        $builder->where('cp.next_dt IS NOT NULL');
        $builder->where('j.is_retired !=', 'Y');
        $builder->where('j.display', 'Y');
        $builder->where('rj.display', 'Y');
        $builder->where('rb.display', 'Y');
        $builder->where('mb.display', 'Y');
        $builder->where('r.display', 'Y');

        if (!empty($judgeCode)) {
            $builder->where($judgeCode);
        }

        // Apply GROUP BY and ORDER BY
        $builder->groupBy('r.id, title, r.courtno, rb.bench_no, mb.abbr, mb.board_type_mb, r.tot_cases, r.frm_time, r.session');
        $builder->orderBy('r.id');
        $builder->orderBy('MIN(j.judge_seniority)', 'ASC');

        // Execute and return results
        return $builder->get()->getRowArray();
    }
 

    public function getRosterAndCaseDetails($tdt1, $mf, $stg, $t_cn, $r_status)
        {
            $db = \Config\Database::connect();

            // Step 1: Fetch roster IDs
         /*   $rosterBuilder = $db->table('master.roster_judge rj');
$rosterBuilder->distinct() // ✅ Correct placement of DISTINCT
    ->select('rj.roster_id, mb.board_type_mb, r.courtno') // ✅ Added courtno
    ->join('master.roster r', 'rj.roster_id = r.id', 'inner')
    ->join('master.roster_bench rb', 'rb.id = r.bench_id AND rb.display = \'Y\'', 'inner')
    ->join('master.master_bench mb', 'mb.id = rb.bench_id AND mb.display = \'Y\'', 'inner')
    ->where('r.m_f', $stg)
    ->where('rj.display', 'Y')
    ->where('r.display', 'Y');

if (!empty($t_cn)) {
    $rosterBuilder->where($t_cn);
}

// ✅ Fix: Added courtno and board_type_mb to SELECT
$rosterBuilder->orderBy("(CASE WHEN r.courtno = 0 THEN 9999 ELSE r.courtno END)", false)
    ->orderBy("(CASE 
            WHEN mb.board_type_mb = 'J' THEN 1
            WHEN mb.board_type_mb = 'C' THEN 2
            WHEN mb.board_type_mb = 'CC' THEN 3
            WHEN mb.board_type_mb = 'R' THEN 4
            ELSE 5 
        END)", false)
    ->orderBy('rj.judge_id');

$rosterQuery = $rosterBuilder->get()->getResultArray(); */

            // Build the query
            $builder = $db->table('master.roster_judge rj');
            $builder->distinct()
                ->select('rj.roster_id, mb.board_type_mb, r.courtno, rj.judge_id')
                ->select('CASE WHEN r.courtno = 0 THEN 9999 ELSE r.courtno END AS courtno_sort', false)
                ->select("CASE 
                            WHEN mb.board_type_mb = 'J' THEN 1
                            WHEN mb.board_type_mb = 'C' THEN 2
                            WHEN mb.board_type_mb = 'CC' THEN 3
                            WHEN mb.board_type_mb = 'R' THEN 4
                            ELSE 5 
                        END AS board_type_sort", false)
                ->join('master.roster r', 'rj.roster_id = r.id', 'inner')
                ->join('master.roster_bench rb', 'rb.id = r.bench_id AND rb.display = \'Y\'', 'inner')
                ->join('master.master_bench mb', 'mb.id = rb.bench_id AND mb.display = \'Y\'', 'inner')
                ->where('r.m_f', $stg)
                ->where('rj.display', 'Y')
                ->where('r.display', 'Y')
                ->where('r.courtno', '21')
                ->where('r.to_date IS NULL OR \'2023-01-03\' BETWEEN r.from_date AND r.to_date');

            if (!empty($t_cn)) {
                $builder->where($t_cn);
            }

            // Order the results as per the SQL query
            $builder->orderBy('courtno_sort', 'ASC')
                ->orderBy('board_type_sort', 'ASC')
                ->orderBy('rj.judge_id', 'ASC');

            // Execute the query and return results
            $rosterQuery =  $builder->get()->getResultArray();

//************************************************************************************** */
           
            $rosterIds = array_column($rosterQuery, 'roster_id');
            $result = implode(',', $rosterIds);

            // Step 2: Determine whereStatus based on $r_status
            $whereStatus = '';
            if ($r_status === 'P') {
                $whereStatus = "AND m.c_status = 'P'";
            } elseif ($r_status === 'D') {
                $whereStatus = "AND m.c_status = 'D'";
            }

            // Step 3: Fetch case details
         /*   $caseBuilder = $db->table('(SELECT t1.diary_no, t1.next_dt, t1.judges, t1.roster_id, t1.mainhead, t1.board_type, t1.clno, t1.brd_slno, t1.main_supp_flag, \'Heardt\' as list_status
                                        FROM heardt t1
                                        WHERE t1.next_dt = ' . $db->escape($tdt1) . ' 
                                        AND t1.mainhead = ' . $db->escape($mf) . '
                                        AND FIND_IN_SET(t1.roster_id, ' . $db->escape($result) . ') > 0
                                        AND (t1.main_supp_flag = 1 OR t1.main_supp_flag = 2)
                                        UNION
                                        SELECT t2.diary_no, t2.next_dt, t2.judges, t2.roster_id, t2.mainhead, t2.board_type, t2.clno, t2.brd_slno, t2.main_supp_flag, \'Last_Heardt\' as list_status
                                        FROM last_heardt t2
                                        WHERE t2.next_dt = ' . $db->escape($tdt1) . '
                                        AND t2.mainhead = ' . $db->escape($mf) . '
                                        AND array_position(string_to_array('.$db->escape($result).', ','), t2.roster_id::TEXT) IS NOT NULL
                                         
                                        AND (t2.main_supp_flag = 1 OR t2.main_supp_flag = 2)
                                        AND (t2.bench_flag = \'\' OR t2.bench_flag IS NULL)
                                        UNION
                                        SELECT t3.diary_no, t3.cl_date as next_dt, \'Judges\' as judges, t3.roster_id, t3.mf as mainhead, \'Board_Type\' as board_type, t3.part as clno, t3.clno as brd_slno, \'Main_supp_flag\' as main_supp_flag, \'DELETED\' as list_status
                                        FROM drop_note t3
                                        WHERE t3.cl_date = ' . $db->escape($tdt1) . '
                                        AND t3.mf = ' . $db->escape($mf) . '
                                        AND array_position(string_to_array('.$db->escape($result).', ','), t3.roster_id::TEXT) IS NOT NULL
                                         
                                    ) h');

            $caseBuilder->select("
                SUBSTRING(m.diary_no, 1, LENGTH(m.diary_no) - 4) AS case_no,
                SUBSTRING(m.diary_no, -4) AS year,
                m.diary_no,
                m.reg_no_display,
                m.conn_key,
                h.mainhead,
                h.judges,
                h.board_type,
                h.next_dt,
                h.clno,
                h.brd_slno,
                m.pet_name,
                m.res_name,
                m.c_status,
                CASE WHEN cl.next_dt IS NULL THEN 'NA' ELSE h.brd_slno END AS brd_prnt,
                h.roster_id,
                m.casetype_id,
                m.case_status_id,
                short_description,
                list_status
            ");

            $caseBuilder->join('main m', 'h.diary_no = m.diary_no', 'inner')
                ->join('cl_printed cl', "cl.next_dt = h.next_dt AND cl.m_f = h.mainhead AND cl.part = h.clno AND cl.roster_id = h.roster_id AND cl.display = 'Y'", 'left')
                ->join('casetype c', 'm.casetype_id = c.casecode', 'left')
                ->join('conct ct', "m.diary_no = ct.diary_no AND ct.list = 'Y'", 'left')
                ->where("cl.next_dt IS NOT NULL $whereStatus")
                ->groupBy('h.diary_no')
                ->orderBy('h.roster_id')
                ->orderBy("CASE WHEN brd_prnt = 'NA' THEN 2 ELSE 1 END", false)
                ->orderBy('h.brd_slno')
                ->orderBy("CASE WHEN m.conn_key = m.diary_no THEN '0000-00-00' ELSE 99 END", false)
                ->orderBy('COALESCE(ct.ent_dt, 999)')
                ->orderBy('CAST(SUBSTRING(m.diary_no, -4) AS INTEGER)', false)
                ->orderBy('CAST(SUBSTRING(m.diary_no, 1, LENGTH(m.diary_no) - 4) AS INTEGER)', false); */

                $sql_t = "SELECT 
                SUBSTRING(m.diary_no::TEXT, 1, LENGTH(m.diary_no::TEXT) - 4) AS case_no,
                SUBSTRING(m.diary_no::TEXT, -4) AS year,  
                m.diary_no,
                m.reg_no_display,    
                m.conn_key,   
                h.mainhead,
                h.judges,
                h.board_type,
                h.next_dt,   
                h.clno,
                h.brd_slno,
                m.pet_name,
                m.res_name,
                m.c_status,
                CASE
                WHEN cl.next_dt IS NULL THEN 0   
                ELSE 1
                END AS brd_prnt,
                h.roster_id,    
                m.casetype_id,
                m.case_status_id,
                short_description,
                list_status
            FROM
                (
                SELECT 
                    t1.diary_no,
                    t1.next_dt,
                    t1.judges,
                    t1.roster_id,  
                    t1.mainhead,
                    t1.board_type,
                    t1.clno,
                    t1.brd_slno,  
                    t1.main_supp_flag,
                    'Heardt' as list_status
                FROM heardt t1 
                WHERE 
                    t1.next_dt = '" . $tdt1 . "' 
                    AND t1.mainhead = '" . $mf . "' 
                    AND array_position(string_to_array('" . $result . "', ','), t1.roster_id::TEXT) > 0
                    AND (t1.main_supp_flag = 1 OR t1.main_supp_flag = 2)
                UNION
                SELECT 
                    t2.diary_no,
                    t2.next_dt,
                    t2.judges,
                    t2.roster_id,  
                    t2.mainhead,
                    t2.board_type,
                    t2.clno,
                    t2.brd_slno,  
                    t2.main_supp_flag,
                    'Last_Heardt' as list_status
                FROM last_heardt t2 
                WHERE 
                    t2.next_dt = '" . $tdt1 . "' 
                    AND t2.mainhead = '" . $mf . "' 
                    AND array_position(string_to_array('" . $result . "', ','), t2.roster_id::TEXT) > 0
                    AND (t2.main_supp_flag = 1 OR t2.main_supp_flag = 2)
                    AND (t2.bench_flag = '' OR t2.bench_flag IS NULL)
                UNION
                SELECT 
                    t3.diary_no,
                    t3.cl_date as next_dt,
                    'Judges' as judges,
                    t3.roster_id,  
                    t3.mf as mainhead,
                    'Board_Type' as board_type,  
                    t3.part as clno,
                    t3.clno as brd_slno, 
                    NULL as main_supp_flag,
                    'DELETED' as list_status
                FROM drop_note t3 
                WHERE 
                    t3.cl_date = '" . $tdt1 . "' 
                    AND t3.mf = '" . $mf . "' 
                    AND array_position(string_to_array('" . $result . "', ','), t3.roster_id::TEXT) > 0
                ) h 
            INNER JOIN main m ON h.diary_no = m.diary_no   
            LEFT JOIN cl_printed cl 
                ON cl.next_dt = h.next_dt 
                AND cl.m_f = h.mainhead 
                AND cl.part = h.clno 
                AND cl.roster_id = h.roster_id 
                AND cl.display = 'Y'
            LEFT JOIN master.casetype c ON m.casetype_id = c.casecode
            LEFT JOIN conct ct ON m.diary_no = ct.diary_no AND ct.list = 'Y'
            WHERE 
                cl.next_dt IS NOT NULL 
                $whereStatus
            GROUP BY 
                h.diary_no,   
                m.diary_no,   
                m.reg_no_display,
                m.conn_key,
                h.mainhead,
                h.judges,
                h.board_type,
                h.next_dt,
                h.clno,
                h.brd_slno,
                m.pet_name,
                m.res_name,
                m.c_status,
                short_description,
                list_status,
                cl.next_dt,
                h.roster_id,
                ct.ent_dt
            ORDER BY 
                h.roster_id,
                brd_prnt,
                h.brd_slno,
                CASE WHEN m.conn_key::TEXT = m.diary_no::TEXT THEN '0000-00-00' ELSE '99' END ASC,
                COALESCE(ct.ent_dt, '1970-01-01 00:00:00'::timestamp) ASC,
                CAST(SUBSTRING(m.diary_no::TEXT, -4) AS INTEGER) ASC, 
                CAST(SUBSTRING(m.diary_no::TEXT, 1, LENGTH(m.diary_no::TEXT) - 4) AS INTEGER) ASC
            ";
            $result = $db->query($sql_t);    
            
            return $result->getResultArray();
        }


        public function getCourtDetails($dtd, $judgeCode = '')
        {
            $db = \Config\Database::connect();
        
            $builder = $db->table('master.roster t1');
            $builder->distinct()
                ->select("t1.courtno, CONCAT(t3.jname, ' ', t3.first_name, ' ', t3.sur_name) AS jname, t3.jcode") // ✅ Added t3.jcode
                ->join('master.roster_judge t2', 't1.id = t2.roster_id', 'inner')
                ->join('master.judge t3', 't3.jcode = t2.judge_id', 'inner')
                ->join('cl_printed cp', "cp.next_dt = " . $db->escape($dtd) . " AND cp.roster_id = t1.id AND cp.display = 'Y'", 'left')
                ->where("cp.next_dt IS NOT NULL")
                ->where("'" . $dtd . "' >= t1.from_date")
                ->where("t1.to_date IS NULL")
                ->where("t3.jtype", 'R')
                ->where("t3.is_retired", 'N')
                ->where("t2.display", 'Y');
        
            if (!empty($judgeCode)) {
                $builder->where("t3.jcode", $judgeCode); // ✅ Ensure correct condition format
            }
        
            //pr($builder->getCompiledSelect());
            $builder->orderBy('t3.jcode'); // ✅ Now t3.jcode is in SELECT list
        
            return $builder->get()->getResultArray();
        }
        


            public function getOfficeReportDetails($diary_no, $list_dt)
            {
                $db = \Config\Database::connect();
            
                $builder = $db->table('office_report_details o');
                $builder->select('*')
                    ->where('o.diary_no', $diary_no)
                    ->where('o.order_dt', $list_dt)
                    ->where('o.display', 'Y')
                    ->where('o.web_status', 1);
            
                return $builder->get()->getRowArray();
            }


            public function getOrgGist($diary_no, $list_dt)
                {
                    $db = \Config\Database::connect();

                    $builder = $db->table('or_gist org');
                    $builder->select('*')
                        ->where('org.diary_no', $diary_no)
                        ->where('org.list_dt', $list_dt)
                        ->where('org.display', 'Y');

                    return $builder->get()->getResultArray();
                }

            

    
    
            
    
}