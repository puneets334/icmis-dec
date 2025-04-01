<?php

namespace App\Models\Listing;

use CodeIgniter\Model;

class HybridModel extends Model
{

    protected $table = 'master_list_type';
    //protected $primaryKey = 'diary_no';
    // protected $allowedFields = ['fil_no', 'fil_dt', 'lastorder', 'pet_name', 'res_name', 'c_status'];
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    public function getMasterList()
    {
        $builder = $this->db->table('master.master_list_type');
        $builder->select('*')
            ->where('id', 1);
        $query = $builder->get();
        $resultArray = $query->getResultArray();
        return $resultArray;
    }

    public function getfreezeData($courtno, $list_type)
    {
        $builder = $this->db->table('hybrid_physical_hearing_consent_freeze');
        $builder->select('id');
        $builder->where('is_active', 't');
        $builder->where('list_type_id', $list_type);
        $builder->where('to_date >', date('Y-m-d'));
        $builder->where('court_no', $courtno);
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function getweekelyList($courtno)
    {
        $builder = "SELECT 
                hy.consent, 
                hy.hearing_from_time, 
                hy.hearing_to_time, 
                h1.next_dt, 
                h1.mainhead, 
                h1.board_type, 
                m.reg_no_display, 
                m.pet_name, 
                m.res_name, 
                -- h.* 
                h.id, 
                h.item_no, 
                h.diary_no, 
                h.conn_key, 
                h.next_dt, 
                h.from_dt, 
                h.to_dt, 
                h.courtno, 
                h.judges_code, 
                h.listorder, 
                h.usercode, 
                h.ent_dt, 
                h.weekly_no, 
                h.weekly_year, 
                h.updated_by_ip,
                h.updated_by,
                h.updated_on,
                h.create_modify
            FROM 
                (
                    SELECT wl1.*
                    FROM weekly_list wl1
                    INNER JOIN 
                        (
                            SELECT 
                                MAX(weekly_no) AS max_weekly_no, 
                                MAX(weekly_year) AS max_weekly_year 
                            FROM weekly_list 
                            WHERE weekly_year = 
                                (SELECT MAX(weekly_year) FROM weekly_list)
                        ) wl2 
                    ON wl2.max_weekly_no = wl1.weekly_no 
                    AND wl2.max_weekly_year = wl1.weekly_year
                    WHERE courtno = $courtno
                ) h
            INNER JOIN main m ON m.diary_no = h.diary_no
            LEFT JOIN heardt h1 ON h1.diary_no = m.diary_no
            LEFT JOIN conct ct ON m.diary_no = ct.diary_no AND ct.list = 'Y'
            LEFT JOIN hybrid_physical_hearing_consent hy ON hy.diary_no = m.diary_no AND hy.to_dt >= CURRENT_DATE
            WHERE m.c_status = 'P' 
            AND (m.diary_no = CAST(NULLIF(m.conn_key, '') AS bigint) OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
            GROUP BY 
                m.diary_no, 
                hy.consent, 
                hy.hearing_from_time, 
                hy.hearing_to_time, 
                h1.next_dt, 
                h1.mainhead, 
                h1.board_type, 
                m.reg_no_display, 
                m.pet_name, 
                m.res_name, 
                h.id, 
                h.item_no, 
                h.diary_no, 
                h.conn_key, 
                h.next_dt, 
                h.from_dt, 
                h.to_dt, 
                h.courtno, 
                h.judges_code, 
                h.listorder, 
                h.usercode, 
                h.ent_dt, 
                h.weekly_no, 
                h.weekly_year, 
                ct.ent_dt,
                h.updated_by_ip,
                h.updated_by,
                h.updated_on,
                h.create_modify
            ORDER BY 
                LENGTH(h.judges_code) DESC, 
                h.next_dt, 
                h.item_no, 
                CASE WHEN h.conn_key = h.diary_no THEN NULL ELSE '9999-12-31' END ASC, 
                CASE WHEN ct.ent_dt IS NOT NULL THEN ct.ent_dt ELSE '9999-12-31' END ASC,
                CAST(SUBSTRING(CAST(m.diary_no AS text) FROM LENGTH(CAST(m.diary_no AS text)) - 3 FOR 4) AS INTEGER) ASC, 
                CAST(LEFT(CAST(m.diary_no AS text), LENGTH(CAST(m.diary_no AS text)) - 4) AS INTEGER) ASC";
        
        $queryBuilder = $this->db->query($builder);
        $result = $queryBuilder->getResultArray();
        return $result;
    }

    public function saveConsentRegistry()
    {
        $usrSess = session()->get('login')['usercode'];        
        if ($_REQUEST['conn_key'] != null && $_REQUEST['conn_key'] > 0) 
        {
            $queryBuilder = "INSERT INTO hybrid_physical_hearing_consent_log 
                (id, diary_no, conn_key, consent, hearing_from_time, hearing_to_time, from_dt, to_dt, list_type_id, list_number, 
                list_year, mainhead, board_type, user_id, entry_date, user_ip, court_no, 
                updated_by, updated_date, updated_user_ip)
                SELECT a.id, a.diary_no, a.conn_key, a.consent, a.hearing_from_time, a.hearing_to_time, a.from_dt, a.to_dt, a.list_type_id, a.list_number, a.list_year, a.mainhead, a.board_type, a.user_id, a.entry_date, a.user_ip, a.court_no, $usrSess, NOW(), '".$_REQUEST['ip']."'
                FROM hybrid_physical_hearing_consent a WHERE a.diary_no = '".$_REQUEST['diary_no']."'";
                        
            $this->db->query($queryBuilder);
                        
            // Prepare SQL for deleting from the main table
            $queryBuilder1 = "DELETE FROM hybrid_physical_hearing_consent WHERE conn_key = ?";
            $this->db->query($queryBuilder1, [$_REQUEST['conn_key']]);

            $queryBuilder3 = "INSERT INTO public.hybrid_physical_hearing_consent 
                (diary_no, conn_key, consent, hearing_from_time, hearing_to_time, from_dt, to_dt, list_type_id, list_number, 
                list_year, mainhead, board_type, court_no, user_id, user_ip)
            SELECT m.diary_no, m.conn_key::BIGINT conn_key, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            FROM (
                SELECT m.* 
                FROM main m 
                WHERE m.conn_key::TEXT = ? AND m.c_status = 'P'
                UNION
                SELECT m.* 
                FROM main m
                INNER JOIN conct ct ON ct.conn_key::bigint = m.conn_key::bigint
                WHERE m.conn_key::TEXT = ? AND ct.list = 'Y' AND m.c_status = 'P'
            ) m
            INNER JOIN heardt h ON h.diary_no = m.diary_no
            WHERE h.clno > 0 AND h.next_dt = ?";

            // Execute the query with parameters
            $result = $this->db->query($queryBuilder3, [
                $_REQUEST['update_flag'],
                $_REQUEST['from_time'],
                $_REQUEST['to_time'],
                $_REQUEST['from_dt'],
                $_REQUEST['to_dt'],
                $_REQUEST['list_type_id'],
                $_REQUEST['list_number'],
                $_REQUEST['list_year'],
                $_REQUEST['mainhead'],
                $_REQUEST['board_type'],
                $_REQUEST['courtno'],
                $usrSess,
                $_REQUEST['ip'],
                $_REQUEST['conn_key'],
                $_REQUEST['conn_key'],
                $_REQUEST['next_dt']
            ]);

            // Check affected rows
            $affectedRow = $this->db->affectedRows();
            if ($affectedRow > 0) {
                $return_arr = array("status" => "success");
            } else {
                $return_arr = array("status" => "Error: Not Saved");
            }
        } 
        else
        {
            $conn_key = (isset($_REQUEST['conn_key']) && !empty($_REQUEST['conn_key'])) ? $_REQUEST['conn_key'] : 0;
            $queryBuilder1 = "INSERT INTO hybrid_physical_hearing_consent_log 
                (id, diary_no, conn_key, consent, hearing_from_time, hearing_to_time, from_dt, to_dt, list_type_id, list_number, 
                list_year, mainhead, board_type, user_id, entry_date, user_ip, court_no, 
                updated_by, updated_date, updated_user_ip)
                SELECT a.id, a.diary_no, a.conn_key, a.consent, a.hearing_from_time, a.hearing_to_time, a.from_dt, a.to_dt, a.list_type_id, a.list_number, a.list_year, a.mainhead, a.board_type, a.user_id, a.entry_date, a.user_ip, a.court_no, $usrSess, NOW(), '".$_REQUEST['ip']."'
                FROM hybrid_physical_hearing_consent a WHERE a.diary_no = '".$_REQUEST['diary_no']."'";
            
            $this->db->query($queryBuilder1);
            
            $queryBuilder2 = "DELETE FROM hybrid_physical_hearing_consent WHERE diary_no = ?";
            $this->db->query($queryBuilder2, [$_REQUEST['diary_no']]);

            $from_time = ($_REQUEST['from_time']) ? $_REQUEST['from_time'] : '00:00:00';
            $to_time = ($_REQUEST['to_time']) ? $_REQUEST['to_time'] : '00:00:00';

            $builder = $this->db->table('hybrid_physical_hearing_consent');
            $builder->insert([
                'diary_no'          => $_REQUEST['diary_no'],
                'conn_key'          => $conn_key,
                'consent'           => $_REQUEST['update_flag'],
                'hearing_from_time' => $from_time,
                'hearing_to_time'   => $to_time,
                'from_dt'           => $_REQUEST['from_dt'],
                'to_dt'             => $_REQUEST['to_dt'],
                'list_type_id'      => $_REQUEST['list_type_id'],
                'list_number'       => $_REQUEST['list_number'],
                'list_year'         => $_REQUEST['list_year'],
                'mainhead'          => $_REQUEST['mainhead'],
                'board_type'        => $_REQUEST['board_type'],
                'court_no'          => $_REQUEST['courtno'],
                'user_id'           => $usrSess,
                'user_ip'           => $_REQUEST['ip']
            ]);

            $affectedRows = $this->db->affectedRows();

            if ($affectedRows > 0) {
                $return_arr = array("status" => "success");
            } else {
                $return_arr = array("status" => "Error: Not Saved");
            }
        }
        return $return_arr;
    }

    public function getFreezeProcess()
    {
        $builder = $this->db->table('weekly_list');
        $subQuery = $this->db->table('weekly_list')->select('MAX(weekly_year)', false);
        $builder->select('weekly_year AS max_weekly_year, MAX(to_dt) AS max_to_dt, MAX(weekly_no) AS max_weekly_no', false);
        $builder->where('to_dt >=', date('Y-m-d'));
        $builder->groupBy('weekly_year');
        $builder->having('weekly_year', $subQuery, false);
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        }
        return [];
    }

    public function getProcessData()
    {
        $builder = $this->db->table('weekly_list wl');

        $subQuery = $this->db->table('weekly_list')
            ->select('weekly_year, MAX(weekly_no) AS weekly_no', false)
            ->where('to_dt >=', date('Y-m-d'))
            ->groupBy('weekly_year')
            ->having('weekly_year', $this->db->table('weekly_list')->select('MAX(weekly_year)', false), false); 

        $builder->distinct();
        $builder->select('wl.courtno');
        $builder->join("({$subQuery->getCompiledSelect()}) aa", 'aa.weekly_year = wl.weekly_year AND aa.weekly_no = wl.weekly_no', 'inner', false);
        $builder->where('wl.to_dt >=', date('Y-m-d'));
        $builder->orderBy('wl.courtno', 'ASC');

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getHybridData($list_type, $i, $max_weekly_no, $max_weekly_year) // getHybridData()
    {
        $builder = $this->db->table('hybrid_physical_hearing_consent_freeze');
        $builder->select('id');
        $builder->where('list_type_id', $list_type);
        $builder->where('court_no', $i);
        $builder->where('list_number', $max_weekly_no);
        $builder->where('list_year', $max_weekly_year);
        $builder->where('is_active', 't');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function freeze_save()
    {
        $sql = "insert into hybrid_physical_hearing_consent_freeze (list_type_id, list_number, list_year, user_id, user_ip, to_date, court_no)
        VALUES ('" . $_POST['list_type_id'] . "', '" . $_POST['max_weekly_no'] . "', '" . $_POST['max_weekly_year'] . "', 
        '" . $_SESSION['dcmis_user_idd'] . "', '" . $_POST['ip'] . "', '" . $_POST['max_to_dt'] . "', '" . $_POST['courtno'] . "')";
        $rs = $this->db->query($sql);
        $afros = $rs->getAffectedRows();
        if ($afros > 0) {
            $return_arr = array("status" => "success");
        } else {
            $return_arr = array("status" => "Error:Not Saved");
        }
        return $return_arr;
    }

    public function freeze_delete($usercode, $post)
    {
        $courtNo = $post['courtno']; // Get court number from POST request

        $builder = $this->db->table('hybrid_physical_hearing_consent_freeze f');
        $builder->select('f.court_no, r.*, c.*');
        $builder->join('master.roster r', 'r.courtno = f.court_no AND r.m_f = \'2\' AND r.to_date >= CURRENT_DATE AND r.display = \'Y\'', 'inner');
        $builder->join('cl_printed c', 'c.roster_id = r.id AND c.display = \'Y\' AND c.next_dt >= CURRENT_DATE', 'inner');
        $builder->where('f.court_no', $courtNo);

        $query = $builder->get(); 
        if ($query->getNumRows() > 0) 
        {
            $return_arr = array("status" => "Error:Not Allowed, Daily List Already Published");
        } 
        else {
            $data = [
                'is_active'        => 'f',
                'unfreezed_by'     => $usercode,
                'unfreezed_date'   => date('Y-m-d H:i:s'),
                'unfreezed_user_ip' => $post['ip']
            ];
            
            $builder = $this->db->table('hybrid_physical_hearing_consent_freeze');
            $builder->where('id', $post['freeze_id']);
            $builder->where('is_active', 't');
            $builder->update($data);
            
            if ($this->db->affectedRows() > 0) {
                $return_arr = array("status" => "success");
            } else {
                $return_arr = array("status" => "Error:Unable to Save");
            }
        }
        return $return_arr;
    }

    public function getListDetails()
    {
        $subquery = $this->db->table('hybrid_physical_hearing_consent_freeze')
               ->select('list_number, list_year')
               ->where('list_type_id', 1)
               ->where('is_active', 't')
               ->orderBy('entry_date', 'DESC');

        $builder = $this->db->newQuery();
        $builder->select('list_number, list_year')
                ->distinct()
                ->fromSubquery($subquery, 'subquery')
                ->orderBy('list_number', 'DESC');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getConsentReport()
    {
        $weeklyno = isset($_POST['weeklyno']) ? $_POST['weeklyno']:null;
        $builder = $this->db->table('hybrid_physical_hearing_consent hy')
            ->select('hy.consent, hy.court_no, hy.hearing_from_time, hy.hearing_to_time, 
                    hy.from_dt, hy.to_dt, m.diary_no, m.reg_no_display, 
                    m.pet_name, m.res_name, m.conn_key, hy.consent, ct.ent_dt')
            ->join('main m', 'm.diary_no = hy.diary_no', 'inner')
            ->join('heardt h', 'h.diary_no = m.diary_no', 'left')
            ->join('conct ct', 'm.diary_no = ct.diary_no AND ct.list = \'Y\'', 'left')
            ->where('m.c_status', 'P')
            ->groupStart()
                ->where('m.diary_no = CAST(m.conn_key AS BIGINT)', null, false)
                ->orWhere('m.conn_key', '')
                ->orWhere('m.conn_key IS NULL', null, false)
                ->orWhere('m.conn_key', '0')
            ->groupEnd()
            ->where('hy.list_type_id', $_POST['list_type'])
            ->where('hy.court_no', $_POST['courtno'])
            ->where('hy.list_number', $weeklyno)
            ->where('hy.list_year', $_POST['weeklyyear'])
            ->groupBy('m.diary_no, hy.consent, hy.court_no, hy.hearing_from_time, hy.hearing_to_time, 
                    hy.from_dt, hy.to_dt, ct.ent_dt')
            ->orderBy('m.diary_no')
            ->orderBy('CASE WHEN CAST(m.conn_key AS BIGINT) = m.diary_no THEN 1 ELSE 99 END', 'ASC', false)
            ->orderBy('COALESCE(ct.ent_dt, \'9999-12-31 23:59:59\'::TIMESTAMP)', 'ASC', false)
            ->orderBy('CAST(RIGHT(m.diary_no::TEXT, 4) AS INTEGER)', 'ASC', false)
            ->orderBy('CAST(LEFT(m.diary_no::TEXT, LENGTH(m.diary_no::TEXT) - 4) AS INTEGER)', 'ASC', false);

        echo $builder->getCompiledSelect();
        die;

        $query = $builder->get();
        return $query->getResultArray();
    }
    public function listing_date()
    {
        $builder = $this->db->table('heardt c');
        $builder->select('c.next_dt');
        $builder->where('c.next_dt >= CURRENT_DATE');
        $builder->whereIn('c.main_supp_flag', ['1', '2']);
        $builder->groupBy('c.next_dt');
        $builder->orderBy('c.next_dt', 'DESC');
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }
    public function honble_judges()
    {
        $builder = $this->db->table('master.judge');
        $builder->select('jcode');
        $builder->select("CASE WHEN jtype = 'J' THEN jname ELSE first_name || ' ' || sur_name || ', ' || jname END AS judge_name");
        $builder->where('display', 'Y');
        $builder->where('is_retired', 'N');
        $builder->orderBy("CASE WHEN jtype = 'J' THEN 1 ELSE 2 END");
        $builder->orderBy('judge_seniority');
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }
    public function get_aor_case_record_report($listing_dts, $list_type, $judge_code, $consent_source, $court_no)
    {
        $list_type = $list_type;
        $judge_code = $judge_code;
        $court_no = $court_no;
        $currentDate = date('Y-m-d');
        $mainhead_query = '';
        $board_type_query = '';
        if (isset($list_type) && !empty($list_type) && $list_type != 0) {
            if ($list_type == 4) {
                $mainhead_query = "AND h.mainhead = 'M'";
                $board_type_query = "AND h.board_type = 'J'";
            } else if ($list_type == 3) {
                $mainhead_query = "AND h.mainhead = 'F'";
                $board_type_query = "AND h.board_type = 'J'";
            } else if ($list_type == 5) {
                $mainhead_query = "AND h.mainhead = 'M'";
                $board_type_query = "AND h.board_type = 'C'";
            } else if ($list_type == 6) {
                $mainhead_query = "AND h.mainhead = 'M'";
                $board_type_query = "AND h.board_type = 'R'";
            } else {
                echo "List Type Not Defined";
                exit();
            }
        }
        if ($court_no > 0) {
            if ($court_no < 20) {
                $judge_code_query = "AND (r.courtno = " . $court_no . " OR r.courtno = " . ($court_no + 30) . ")";
            } else if ($court_no >= 21) {
                $judge_code_query = "AND (r.courtno = " . $court_no . " OR r.courtno = " . ($court_no + 40) . ")";
            }
        } else if ($judge_code > 0) {
            $judge_code_query = "AND rj.judge_id = $judge_code";
        } else {
            $judge_code_query = "";
        }


        if ($consent_source == 1) {
            $consent_source_qry = "AND c.entry_source = 1";
        } else if ($consent_source == 1) {
            $consent_source_qry = "AND c.entry_source = 2";
        } else {
            $consent_source_qry = "";
        }
        $ctn = 0;

        $sql = "SELECT 
                    cl.id AS is_printed, 
                    m.reg_no_display,
                    m.pet_name,
                    m.res_name, 
                    h.main_supp_flag,
                    h.board_type,
                    h.judges,
                    h.roster_id,
                    h.brd_slno,
                    h.clno,
                    h.mainhead,
                    h.next_dt,
                    h.conn_key,
                    h.diary_no,
                    r.courtno,
                    STRING_AGG(a.advocate_id::TEXT, ',') AS advocate_ids, 
                    COUNT(DISTINCT a.advocate_id) AS total_advocates 
                    FROM 
                    main m 
                    INNER JOIN heardt h ON m.diary_no = h.diary_no
                    LEFT JOIN conct ct ON m.diary_no = ct.diary_no AND ct.list = 'Y'
                    INNER JOIN master.roster r ON h.roster_id = r.id
                    INNER JOIN master.roster_judge rj ON rj.roster_id = r.id
                    INNER JOIN advocate a ON m.diary_no = a.diary_no
                    INNER JOIN cl_printed cl ON h.next_dt = cl.next_dt 
                                                AND cl.part = h.clno 
                                                AND h.roster_id = cl.roster_id 
                                                AND cl.display = 'Y' 
                    WHERE 
                    a.display = 'Y' 
                    AND r.display = 'Y' 
                    AND rj.display = 'Y' 
                    AND h.next_dt = '$listing_dts' 
                    $mainhead_query
                    $board_type_query
                    $judge_code_query
                    AND h.brd_slno > 0 
                    GROUP BY 
                    m.diary_no,cl.id,h.main_supp_flag,h.board_type,h.judges,h.roster_id,h.brd_slno,h.clno,h.mainhead,h.next_dt,h.conn_key,h.diary_no,r.courtno,ct.ent_dt
                    ORDER BY 
                    r.courtno, 
                    h.next_dt, 
                    h.brd_slno,
                    CASE WHEN h.conn_key = h.diary_no THEN 1 ELSE 99 END ASC,
                    CASE WHEN ct.ent_dt IS NOT NULL THEN ct.ent_dt ELSE '999-12-31' END ASC,
                    CAST(SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3 FOR 4) AS INTEGER) ASC,
                    CAST(LEFT(m.diary_no::text, LENGTH(m.diary_no::text) - 4) AS INTEGER) ASC";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
    public function f_get_advocate_count_with_connected($conn_key, $next_dt)
    {

        $sql = "SELECT 
                COUNT(DISTINCT a.advocate_id) AS count_adv 
                FROM 
                (
                    SELECT 
                    m.diary_no 
                    FROM 
                    main m 
                    WHERE 
                    m.conn_key = '$conn_key' 
                    AND m.c_status = 'P' 
                    
                    UNION 
                    
                    SELECT 
                    m.diary_no 
                    FROM 
                    main m 
                    INNER JOIN conct ct ON ct.conn_key = m.conn_key ::Int
                    WHERE 
                    m.conn_key = '292024' 
                    AND ct.list = 'Y' 
                    AND m.c_status = 'P'
                ) AS subquery
                INNER JOIN advocate a ON subquery.diary_no = a.diary_no 
                INNER JOIN heardt h ON h.diary_no = subquery.diary_no 
                WHERE 
                h.clno > 0 
              AND h.next_dt = '$next_dt' 
                AND a.display = 'Y'";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
    public function f_connected_case_count_listed($conn_key, $next_dt)
    {
        $sql = "SELECT 
                    COUNT(subquery.diary_no) AS total_connected 
                    FROM 
                    (
                        SELECT 
                        m.diary_no, 
                        m.conn_key 
                        FROM 
                        main m 
                        WHERE 
                        m.conn_key = '$conn_key' 
                        AND m.c_status = 'P' 
                        
                        UNION 
                        
                        SELECT 
                        m.diary_no, 
                        m.conn_key 
                        FROM 
                        main m 
                        INNER JOIN conct ct ON ct.conn_key = m.conn_key ::int
                        WHERE 
                        m.conn_key = '2024' 
                        AND ct.list = 'Y' 
                        AND m.c_status = 'P'
                    ) AS subquery
                    INNER JOIN heardt h ON h.diary_no = subquery.diary_no 
                    WHERE 
                    subquery.diary_no != subquery.conn_key ::bigint 
                    AND h.clno > 0 
                    AND h.next_dt = '$next_dt'";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
    public function get_data_consent_through($diary_no, $next_dt, $consent_source_qry, $advocate_id)
    {
        $sql = "SELECT 
                c.entry_source, 
                c.id, 
                b.bar_id, 
                b.name, 
                b.email, 
                b.mobile, 
                b.aor_code, 
                b.if_aor
                FROM 
                master.bar b 
                INNER JOIN consent_through_email c ON c.diary_no = $diary_no
                  AND c.next_dt = '$next_dt'
                AND c.advocate_id = b.bar_id 
                WHERE 
                c.is_deleted IS NULL 
                  AND b.bar_id IN ($advocate_id) 
                AND b.if_aor = 'Y' 
                AND b.isdead = 'N' 
                AND b.if_sen = 'N' 
                AND b.bar_id NOT IN (584, 585, 610, 616, 666, 940) 
                ORDER BY 
                b.aor_code";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
    public function get_data_bar_advocate_consent_through($diary_no, $next_dt, $consent_source_qry)
    {
        $sql = "SELECT 
                    c.entry_source, 
                    c.id, 
                    b.bar_id, 
                    b.name, 
                    b.email, 
                    b.mobile, 
                    b.aor_code, 
                    b.if_aor 
                    FROM 
                    master.bar b
                    INNER JOIN consent_through_email c ON c.diary_no = $diary_no 
                    AND c.next_dt = '$next_dt' 
                    AND c.advocate_id = b.bar_id 
                    WHERE 
                    c.is_deleted IS NULL 
                    AND b.bar_id IN ($consent_source_qry) 
                    AND b.if_aor = 'Y' 
                    AND b.isdead = 'N' 
                    AND b.if_sen = 'N' 
                    AND b.bar_id NOT IN (584, 585, 610, 616, 666, 940) 
                    ORDER BY 
                    b.aor_code";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
}
