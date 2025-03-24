<?php

namespace App\Models\Listing;

use CodeIgniter\Model;

class CaseInfoModel extends Model
{

    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }


    public function get_case_info()
    {
        $builder = $this->db->table('case_info');
        $builder->select('diary_no, message');

        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return [];
        }
    }

    public function case_details($diaryno)
    {
        $builder = $this->db->table('main m');

        // Case number and year
        $builder->select("substr(CAST(diary_no AS VARCHAR), 1, length(CAST(diary_no AS VARCHAR)) - 4) AS case_no");
        // $builder->select("substr(CAST(diary_no AS VARCHAR), -4) as year");
        $builder->select("RIGHT(CAST(diary_no AS TEXT), 4) AS year", false);

        // Basic details
        $builder->select("m.pet_name, m.res_name, m.bench, m.c_status");
        $builder->select("TO_CHAR(m.fil_dt, 'DD-MM-YYYY HH12:MI AM') as fil_dt_f");
        $builder->select("CASE WHEN (m.reg_year_mh = 0 OR m.fil_dt > '2017-05-10') THEN EXTRACT(YEAR FROM m.fil_dt) ELSE m.reg_year_mh END as m_year");

        // Case details
        $builder->select("m.diary_no, m.pet_adv_id, m.res_adv_id, m.actcode, m.conn_key, m.lastorder, m.fil_dt, m.bailno, m.nature, m.prevno");

        // Case code and date
        $builder->select("split_part(fil_no, '-', 1) as casecode");
        $builder->select("m.outside");
        $builder->select("TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY HH12:MI AM') as diary_no_rec_date");

        // File numbers
        $builder->select("m.fil_no, m.fil_no_fh");
        $builder->select("TO_CHAR(m.fil_dt_fh, 'DD-MM-YYYY HH12:MI AM') as fil_dt_fh");
        $builder->select("CASE WHEN reg_year_fh = 0 THEN EXTRACT(YEAR FROM m.fil_dt_fh) ELSE reg_year_fh END as f_year");

        // Substitutes for SUBSTRING_INDEX
        $builder->select("CASE WHEN fil_no != '' THEN split_part(fil_no, '-', 1) ELSE '' END as ct1");
        $builder->select("CASE WHEN fil_no != '' THEN split_part(fil_no, '-', 2) ELSE '' END as crf1");
        $builder->select("CASE WHEN fil_no != '' THEN split_part(fil_no, '-', 3) ELSE '' END as crl1");

        $builder->select("CASE WHEN fil_no_fh != '' THEN split_part(fil_no_fh, '-', 1) ELSE '' END as ct2");
        $builder->select("CASE WHEN fil_no_fh != '' THEN split_part(fil_no_fh, '-', 2) ELSE '' END as crf2");
        $builder->select("CASE WHEN fil_no_fh != '' THEN split_part(fil_no_fh, '-', 3) ELSE '' END as crl2");

        // Additional case details
        $builder->select("casetype_id, case_status_id, m.usercode, m.if_sclsc");


        $builder->where('m.diary_no', $diaryno);

        

        //  echo $this->db->getLastQuery();die;

        if ($builder->countAllResults(false) >= 1) {
            $query = $builder->get();
            return $query->getResultArray();
        } else {
            return [];
        }
    }

    public function get_diary_details_by_diary_no1($diary_no)
    {

        $builder = $this->db->table('case_info');
        //  $builder->select('case_info.*,  case_info.usercode AS u,to_char(insert_time, \'DD-MM-YYYY HH24:MI\') as insert_time,  to_char(deleted_on, \'DD-MM-YYYY HH24:MI\') as deleted_on, l.name as deleted_empname, case_info.display as if_active,  concat(users.name, \'[\', users.empid, \']\') as userinfo, main.reg_no_display as caseno');
        // $builder->join('master.users', 'case_info.usercode = users.usercode');
        // $builder->join('main', 'case_info.diary_no = main.diary_no','left');
        // $builder->join('master.users l', 'case_info.deleted_by = l.usercode', 'left');

        $builder->select('case_info.*, to_char(insert_time, \'DD-MM-YYYY HH24:MI\') as insert_time,to_char(deleted_on, \'DD-MM-YYYY HH24:MI\') as deleted_on,l.name as deleted_empname,concat(users.name, \'[\', users.empid, \']\') as userinfo,case_info.display as if_active');
        $builder->join('master.users', 'case_info.usercode = users.usercode');
        $builder->join('master.users l', 'case_info.deleted_by = l.usercode', 'left');

        $builder->where('case_info.diary_no', $diary_no);
        $query = $builder->get();

        //echo $this->db->getLastQuery();die;

        $get_main_table = $query->getResultArray();
        return $get_main_table;
    }


    public function get_diary_details_by_diary_no($diary_no)
    {
        $builder = $this->db->table('case_info');

        $builder->select("*, case_info.usercode as u, 
                  TO_CHAR(insert_time, 'DD-MM-YYYY HH24:MI') as insert_time, 
                  TO_CHAR(deleted_on, 'DD-MM-YYYY HH24:MI') as deleted_on, 
                  l.name as deleted_empname, case_info.display as if_active, 
                  concat(users.name, '[', users.empid, ']') as userinfo, 
                  main.reg_no_display as caseno");
        $builder->join('master.users', 'case_info.usercode = users.usercode');
        $builder->join('main', 'case_info.diary_no = main.diary_no');
        $builder->join('master.users l', 'case_info.deleted_by = l.usercode', 'left');
        $builder->where('case_info.diary_no', $diary_no);
        $builder->orderBy('case_info.id', 'asc');
        $query = $builder->get();
        $result = $query->getResultArray();

        return $result;
    }

    public function insert_case_info($data)
    {
        $builder = $this->db->table("case_info");
        // $builder->insert($data);

        // echo $this->db->getLastQuery();die;


        if ($builder->insert($data)) {
            return $this->db->insertID();
        } else {
            return false;
        }
    }

    public function delete_case_info($id)
    {
        $builder = $this->db->table("case_info");
        $builder->where('id', $id);
        if ($builder->delete()) {
            return true;
        } else {
            return false;
        }
    }

    function get_diary_case_type_notice($ct, $cn, $cy)
    {
        $builder = $this->db->table('main_casetype_history h');
        $builder->select("SUBSTR(h.diary_no, 1, LENGTH(h.diary_no) - 4) AS dn, SUBSTR(h.diary_no, -4) AS dy, IF(h.new_registration_number != '', SUBSTRING_INDEX(h.new_registration_number, '-', 1), '') as ct1, IF(h.new_registration_number != '', SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2), '-', -1), '') as crf1, IF(h.new_registration_number != '', SUBSTRING_INDEX(h.new_registration_number, '-', -1), '') as crl1");
        $builder->groupStart()
            ->where("SUBSTRING_INDEX(h.new_registration_number, '-', 1)", $ct)
            ->where("CAST($cn AS UNSIGNED) BETWEEN (SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2), '-', -1)) AND (SUBSTRING_INDEX(h.new_registration_number, '-', -1))")
            ->where("h.new_registration_year", $cy)
            ->groupEnd()
            ->orWhere(function ($q) use ($ct, $cn, $cy) {
                $q->where("SUBSTRING_INDEX(h.old_registration_number, '-', 1)", $ct)
                    ->where("CAST($cn AS UNSIGNED) BETWEEN 
                            (SUBSTRING_INDEX(SUBSTRING_INDEX(h.old_registration_number, '-', 2), '-', -1)) 
                            AND 
                            (SUBSTRING_INDEX(h.old_registration_number, '-', -1))")
                    ->where("h.old_registration_year", $cy)
                    ->where("h.is_deleted", 't');
            })
            ->where("h.is_deleted", 'f');

            $query = $builder->get();
    
            if ($query->getNumRows() > 0) {
                $result = $query->getRowArray();
                return $result;
            }

        return null;
    }

    function get_party_details($diary_no, $flag = null)
    {
        $builder1 = $this->db->table("party" . $flag . " p");
        $builder1->select("sr_no_show, partyname, prfhname, addr1, addr2, state, city, dstname, pet_res, remark_del, remark_lrs, pflag, partysuff, deptname, ind_dep");
        $builder1->join('master.deptt d', 'state_in_name = d.deptcode', 'LEFT');
        $builder1->where('diary_no', $diary_no);
        $builder1->whereNotIn('pflag', ['T', 'Z']);
        $builder1->orderBy("pet_res");
        $builder1->orderBy("COALESCE(CAST(NULLIF(split_part(sr_no_show, '.', 1), '') AS INTEGER), 0)");
        $builder1->orderBy("COALESCE(CAST(NULLIF(split_part(sr_no_show, '.', 2), '') AS INTEGER), 0)");
        $builder1->orderBy("COALESCE(CAST(NULLIF(split_part(sr_no_show, '.', 3), '') AS INTEGER), 0)");
        $builder1->orderBy("COALESCE(CAST(NULLIF(split_part(sr_no_show, '.', 4), '') AS INTEGER), 0)");
        $query = $builder1->get();

        // echo $this->db->getLastQuery();die;

        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return json_encode($result);
        } else {
            return false;
        }
    }

    public function get_message($case_id)
    {
        $builder = $this->db->table('case_info');
        $builder->select('message');
        $builder->where('id', $case_id);
        $query = $builder->get();
        $result = $query->getRowArray();
        return isset($result['message']) ? $result['message'] : '';
    }

    public function shortDesc($case_type, $disp = true)
    {
        $builder = $this->db->table('master.casetype');
        $builder->select('*');
        $builder->where('casecode', $case_type);
        if($disp){
            $builder->where('display', 'Y');
        }        
        $query = $builder->get();
        $result = $query->getRow();
        return isset($result) ? $result : null;
    }

    public function update_case_info($isedit, $data)
    {
        $return = false;
        $this->insert_case_info($data);
        $data = [
            'display' => 'N',
            'deleted_by' => $data['usercode'],
            'deleted_on' => $data['insert_time'],
            'deleted_user_ip' => $data['userip']
        ];

        $builder = $this->db->table('case_info');
        $builder->where('id', $isedit);
        $builder->update($data);

        if ($this->db->affectedRows() > 0) {
            $return = true;
        }
        return $return;
    }

    public function getOtherGroupSingleJudgeNominatedByJCode($all_judges_code_of_day)
    {
        $return = '';
        if(!empty($all_judges_code_of_day)){
            $builder = $this->db->table('master.single_judge_nominate');
            $builder->select("string_agg(DISTINCT jcode::text, ',') AS other_group_judge");
            $builder->where('is_active', 1);
            $builder->whereNotIn('jcode', explode(',', $all_judges_code_of_day));
            $builder->where('to_date', NULL);
            $builder->groupBy('is_active');
            $query = $builder->get();
            if ($query->getNumRows() >= 1) {
                $return = $query->getRow()->other_group_judge;
            }
        }
        return $return;
    }

    public function singleJudgeFinalProcessGetCases1($array)
    {
        $builder = $this->db->table('main m');

        $builder->select('dd.doccode1, mc.submaster_id, a.advocate_id, m.conn_key AS main_key, l.priority, aa.diary_no AS diary_in_advance_list, h.*');

        $builder->join('heardt h', 'h.diary_no = m.diary_no', 'INNER');
        $builder->join('master.listing_purpose l', 'l.code = h.listorder', 'INNER');
        $builder->join('mul_category mc', 'mc.diary_no = m.diary_no', 'INNER');

        $builder->join('docdetails dd', 'dd.diary_no = h.diary_no AND dd.iastat = \'P\' AND dd.doccode = 8 AND dd.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 3, 309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 102, 118, 131, 211, 309)', 'LEFT');
        $builder->join('rgo_default rd', 'rd.fil_no = h.diary_no AND rd.remove_def = \'N\'', 'LEFT');
        $builder->join('advocate a', 'a.diary_no = m.diary_no AND a.advocate_id IN (584, 585, 610, 616, 666, 940) AND a.display = \'Y\'', 'LEFT');
        $builder->join('advance_single_judge_allocated aa', 'aa.diary_no = h.diary_no AND h.next_dt BETWEEN aa.from_dt AND aa.to_dt AND h.next_dt = aa.next_dt', 'LEFT');
        $builder->join('single_judge_advanced_drop_note adn', 'adn.diary_no = aa.diary_no AND adn.from_dt = aa.from_dt AND adn.to_dt = aa.to_dt AND adn.display = \'Y\'', 'LEFT');

        $builder->where('adn.diary_no IS NULL');
        $builder->where('m.c_status', 'P');
        $builder->orWhere("m.conn_key ~ '^[0-9]+$' AND m.diary_no = m.conn_key::int", null, false);
        $builder->whereNotIn('m.active_casetype_id', [9, 10, 25, 26]);
        $builder->whereNotIn('h.subhead', [801, 817, 818, 819, 820, 848, 849, 850, 854, 0]);
        $builder->whereIn('h.listorder', explode(',', $array['listorder']));
        $builder->where('h.main_supp_flag', 0);
        $builder->where('h.mainhead', 'M');
        $builder->where('h.roster_id', 0);
        $builder->where('h.brd_slno', 0);
        $builder->where('h.board_type', 'S');
        $builder->where('mc.display', 'Y');
        $builder->where('rd.fil_no IS NULL');

        if ($array['main_supp'] == 2) {
            $builder->where('h.listorder IN (4, 5)');
            $builder->where('h.next_dt', '2023-01-02');
        } else {
            $builder->groupStart();
            $builder->where('h.listorder IN (4, 5)');
            $builder->where('h.next_dt <= CURRENT_DATE OR h.next_dt = \'2023-01-02\'', null, false);
            $builder->orGroupStart();
            $builder->whereNotIn('h.listorder', [4, 5]);
            $builder->where('h.next_dt <= \'2023-01-02\'', null, false);
            $builder->groupEnd();
            $builder->groupEnd();
        }

        $builder->groupBy('h.diary_no,m.diary_no, dd.doccode1, mc.submaster_id, a.advocate_id, m.conn_key, l.priority, aa.diary_no, h.*');

        $builder->orderBy('CASE WHEN h.next_dt = \'2023-01-02\' AND h.subhead = \'824\' THEN 1 ELSE 999 END', 'ASC', false);
        $builder->orderBy('CASE WHEN h.next_dt = \'2023-01-02\' AND h.listorder IN (4, 5, 7) THEN 2 ELSE 999 END', 'ASC', false);
        $builder->orderBy('CASE WHEN h.next_dt = \'2023-01-02\' AND a.advocate_id IS NOT NULL THEN 3 ELSE 999 END', 'ASC', false);
        $builder->orderBy('CASE WHEN aa.diary_no IS NOT NULL THEN 4 ELSE 999 END', 'ASC', false);
        $builder->orderBy('CASE WHEN h.subhead = \'804\' OR doccode1 IN (40, 41, 48, 49, 71, 72, 118, 131, 211, 309) OR submaster_id = 173 THEN 5 ELSE 999 END', 'ASC', false);
        $builder->orderBy('CASE WHEN h.listorder IN (25) THEN 6 ELSE 999 END', 'ASC', false);
        $builder->orderBy('CASE WHEN h.subhead IN (\'810\', \'802\', \'803\', \'807\') THEN 7 ELSE 999 END', 'ASC', false);
        $builder->orderBy('CASE WHEN doccode1 IN (56, 57, 102, 73, 99, 27, 124, 2, 16) THEN 8 ELSE 999 END', 'ASC', false);
        $builder->orderBy('priority', 'ASC');
        $builder->orderBy('h.no_of_time_deleted', 'DESC');
        $builder->orderBy('CASE WHEN h.coram IS NOT NULL AND h.coram != \'0\' AND TRIM(h.coram) != \'\' THEN 13 ELSE 999 END', 'ASC', false);
        $builder->orderBy('CAST(RIGHT(h.diary_no::text, 4) AS INTEGER)', 'ASC', false);
        $builder->orderBy('CAST(LEFT(h.diary_no::text, LENGTH(h.diary_no::text) - 4) AS INTEGER)', 'ASC', false);

        $query = $builder->get();
        return $query->getResultArray();
    }

    function check_list_before($q_diary_no, $before_flag)
    {
        if ($before_flag == 'B') {
            $sql = "
            SELECT DISTINCT STRING_AGG(n.j1::TEXT, ',' ORDER BY judge_seniority) AS j1
            FROM not_before n
            INNER JOIN master.judge j ON j.jcode = n.j1
            WHERE n.diary_no IN ('$q_diary_no')
              AND j.is_retired = 'N'
              AND n.notbef = '$before_flag'
            GROUP BY diary_no;
        ";
        }
        if ($before_flag == 'N') {
            $sql = "
            SELECT DISTINCT org_judge_id AS j1
            FROM advocate a
            INNER JOIN master.ntl_judge n ON n.org_advocate_id = a.advocate_id
            WHERE a.diary_no IN ($q_diary_no)
                AND n.display = 'Y'
                AND a.display = 'Y'
        
            UNION
        
            SELECT DISTINCT n.org_judge_id AS j1
            FROM party a
            INNER JOIN master.ntl_judge_dept n ON a.deptcode = dept_id
            WHERE a.diary_no IN ($q_diary_no)
                AND a.pflag != 'T'
                AND n.display = 'Y'
        
            UNION
        
            SELECT DISTINCT n.j1
            FROM not_before n
            INNER JOIN master.judge j ON j.jcode = n.j1
            WHERE n.diary_no IN ('$q_diary_no')
                AND n.notbef = 'N'
                AND j.is_retired = 'N'
        
            UNION
        
            SELECT DISTINCT n.org_judge_id AS j1
            FROM (
                SELECT s.id
                FROM (
                    SELECT s.id, sub_name1
                    FROM mul_category c, master.submaster s
                    WHERE s.id = submaster_id
                        AND diary_no IN ($q_diary_no)
                        AND c.display = 'Y'
                        AND s.display = 'Y'
                ) a
                INNER JOIN master.submaster s ON s.sub_name1 = a.sub_name1
                WHERE flag = 's'
            ) a
            INNER JOIN master.ntl_judge_category n ON n.cat_id = a.id
            WHERE n.display = 'Y';
            ";
        }
        $result = $this->db->query($sql)->getRow();

        if ($result !== null) {
            return $result->j1;
        } else {
            return null;
        }
    }

    public function single_judge_final_connected_cases_allocation($diary_no)
    {
        $db = \Config\Database::connect();

        // Insert Query (last_heardt)
        $insertSql = "INSERT INTO last_heardt (
            diary_no, conn_key, next_dt, mainhead, subhead, clno, brd_slno,
            roster_id, judges, coram, board_type, usercode, ent_dt, module_id,
            mainhead_n, subhead_n, main_supp_flag, listorder, tentative_cl_dt,
            lastorder, listed_ia, sitting_judges, list_before_remark, is_nmd,
            no_of_time_deleted
        )
        SELECT j.*
        FROM (
            SELECT
                a.conc_diary_no, a.conn_key, a.next_dt, a.mainhead, a.subhead, a.clno,
                a.brd_slno, a.roster_id, a.judges, a.coram, a.board_type, a.usercode,
                a.ent_dt, a.module_id, a.mainhead_n, a.subhead_n, a.main_supp_flag,
                a.listorder, a.tentative_cl_dt, a.lastorder, a.listed_ia,
                a.sitting_judges, a.list_before_remark, a.is_nmd, a.no_of_time_deleted
            FROM (
                SELECT
                    c.diary_no AS conc_diary_no, m.conn_key::bigint, h.next_dt, h.mainhead,
                    h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges, h.coram,
                    h.board_type, h.usercode, h.ent_dt, h.module_id, h.mainhead_n,
                    h.subhead_n, h.main_supp_flag, h.listorder, h.tentative_cl_dt,
                    m.lastorder, h.listed_ia, h.sitting_judges, h.list_before_remark,
                    h.is_nmd, h.no_of_time_deleted
                FROM heardt h
                INNER JOIN main m ON m.diary_no = h.diary_no
                INNER JOIN conct c ON c.conn_key = m.conn_key::bigint
                WHERE c.list = 'Y'
                  AND m.c_status = 'P'
                  AND m.diary_no::bigint = m.conn_key::bigint
                  AND h.diary_no = ?
                  AND h.roster_id > 0
            ) a
            INNER JOIN main m ON a.conc_diary_no = m.diary_no
            INNER JOIN heardt h ON a.conc_diary_no = h.diary_no
            WHERE m.c_status = 'P'
              AND (h.next_dt IS NOT NULL)
        ) j
        LEFT JOIN last_heardt l
            ON j.conc_diary_no = l.diary_no
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
    AND (
        (j.listed_ia IS NULL AND l.listed_ia IS NULL)
        OR (l.listed_ia = j.listed_ia)
    )
    AND (
        (j.list_before_remark IS NULL AND l.list_before_remark IS NULL)
        OR (l.list_before_remark = j.list_before_remark)
    )
    AND l.no_of_time_deleted = j.no_of_time_deleted
    AND l.is_nmd = j.is_nmd
    AND l.main_supp_flag = j.main_supp_flag
            AND (l.bench_flag = '' OR l.bench_flag IS NULL)
        WHERE l.diary_no IS NULL";

        $db->query($insertSql, [$diary_no]);

        // Update Query (heardt)
        $updateSql = "UPDATE public.heardt
        SET
            conn_key = x.conn_key, next_dt = x.next_dt, mainhead = x.mainhead,
            subhead = x.subhead, clno = x.clno, brd_slno = x.brd_slno,
            roster_id = x.roster_id, judges = x.judges, board_type = x.board_type,
            usercode = x.usercode, ent_dt = x.ent_dt, module_id = x.module_id,
            mainhead_n = x.mainhead_n, subhead_n = x.subhead_n,
            main_supp_flag = x.main_supp_flag, listorder = x.listorder,
            tentative_cl_dt = x.tentative_cl_dt, sitting_judges = x.sitting_judges,
            list_before_remark = x.list_before_remark, listed_ia = x.listed_ia,
            is_nmd = x.is_nmd, no_of_time_deleted = x.no_of_time_deleted
        FROM (
            SELECT
                c.diary_no AS conc_diary_no, m.conn_key::bigint AS conn_key, h.next_dt,
                h.mainhead, h.subhead, h.clno, h.brd_slno, h.roster_id, h.judges,
                h.board_type, h.usercode, h.ent_dt, h.module_id, h.mainhead_n,
                h.subhead_n, h.main_supp_flag, h.listorder, h.tentative_cl_dt,
                h.sitting_judges, h.listed_ia, h.list_before_remark, h.is_nmd,
                h.no_of_time_deleted
            FROM public.heardt h
            INNER JOIN public.main m ON m.diary_no = h.diary_no
            INNER JOIN public.conct c ON c.conn_key::text = m.conn_key
            WHERE c.list = 'Y'
              AND m.c_status = 'P'
              AND m.diary_no = m.conn_key::bigint
              AND h.diary_no = ?
              AND h.roster_id > 0
        ) x
        WHERE public.heardt.diary_no = x.conc_diary_no
          AND public.heardt.diary_no > 0";

        $db->query($updateSql, [$diary_no]);

        return $db->affectedRows();
    }


    public function singleJudgeFinalProcessGetCases($array)
    {
        $return = [];
        if(!empty($array)){
            $sql_query = "SELECT h.* from
            (select dd.doccode1, mc.submaster_id, a.advocate_id, m.conn_key AS main_key, l.priority, 
            aa.diary_no as diary_in_advance_list, h.*
            FROM main m
            inner JOIN heardt h ON h.diary_no = m.diary_no
            inner JOIN master.listing_purpose l ON l.code = h.listorder
            inner JOIN mul_category mc ON mc.diary_no= m.diary_no
            LEFT JOIN docdetails dd ON dd.diary_no = h.diary_no and dd.iastat = 'P' and dd.doccode = 8 AND dd.doccode1 IN (7,66,29,56,57,28,103,133,3,309,73,99,40,48,72,71,27,124,2,16,41,49,102,118,131,211,309)
            LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
            LEFT JOIN advocate a on a.diary_no = m.diary_no and a.advocate_id in (584,585,610,616,666,940) and a.display = 'Y'
            LEFT JOIN advance_single_judge_allocated aa on aa.diary_no = h.diary_no and h.next_dt between aa.from_dt and aa.to_dt and h.next_dt = aa.next_dt 
            left join single_judge_advanced_drop_note adn on adn.diary_no = aa.diary_no and adn.from_dt = aa.from_dt and adn.to_dt = aa.to_dt and adn.display = 'Y'    
            WHERE adn.diary_no is null and m.c_status = 'P' AND (m.diary_no = CAST(m.conn_key AS bigint) OR m.conn_key='0' OR m.conn_key IS NULL) 
            and m.active_casetype_id != 9 AND m.active_casetype_id != 10
            AND h.subhead != 801 AND h.subhead != 817 AND h.subhead != 818 AND h.subhead != 819 AND h.subhead != 820
            AND h.subhead != 848 AND h.subhead != 849 AND h.subhead != 850 AND h.subhead != 854 and h.subhead != 0
            AND m.active_casetype_id != 25 AND m.active_casetype_id != 26
            and h.listorder in (".$array['listorder'].") AND h.main_supp_flag = 0
            AND h.mainhead = 'M' AND h.roster_id = 0 AND h.brd_slno = 0 AND h.board_type = 'S'

            AND (
                CASE WHEN ".$array['main_supp'] ." = 2 THEN (h.listorder IN (4, 5) AND h.next_dt = '".$array['next_dt']."') 
                ELSE 
                    CASE WHEN h.listorder IN (4, 5) THEN (h.next_dt <= CURRENT_DATE OR h.next_dt = '".$array['next_dt']."') 
                    ELSE h.next_dt <= '".$array['next_dt']."'
                    END
                END
            )
            AND mc.display = 'Y' and rd.fil_no IS NULL
            GROUP BY m.diary_no, dd.doccode1, mc.submaster_id, a.advocate_id, l.priority, aa.diary_no, h.diary_no) h
            ORDER BY
            CASE WHEN h.next_dt = '".$array['next_dt']."' AND h.subhead = '824' THEN 1 ELSE 999 END ASC,
            CASE WHEN h.next_dt = '".$array['next_dt']."' AND h.listorder IN (4, 5, 7) THEN 2 ELSE 999 END ASC,
            CASE WHEN h.next_dt = '".$array['next_dt']."' AND advocate_id IS NOT NULL THEN 3 ELSE 999 END ASC,
            CASE WHEN diary_in_advance_list IS NOT NULL THEN 4 ELSE 999 END ASC,
            CASE WHEN (h.subhead = '804' OR doccode1 IN (40, 41, 48, 49, 71, 72, 118, 131, 211, 309) OR submaster_id = 173) THEN 5 ELSE 999 END ASC,
            CASE WHEN h.listorder IN (25) THEN 6 ELSE 999 END ASC,
            CASE WHEN (h.subhead IN ('810', '802', '803', '807')) THEN 7 ELSE 999 END ASC,
            CASE WHEN (doccode1 IN (56, 57, 102, 73, 99, 27, 124, 2, 16)) THEN 8 ELSE 999 END ASC,
            priority ASC,
            h.no_of_time_deleted DESC,
            CASE WHEN (h.coram IS NOT NULL AND h.coram != '0' AND TRIM(h.coram) != '') THEN 13 ELSE 999 END ASC,
            CAST(RIGHT(CAST(h.diary_no AS TEXT), 4) AS INTEGER) ASC,
            CAST(LEFT(CAST(h.diary_no AS TEXT), LENGTH(CAST(h.diary_no AS TEXT)) - 4) AS INTEGER) ASC;";
            //and if(".$array['main_supp']." = 2, (h.listorder in (4,5) AND h.next_dt = '".$array['next_dt']."'), h.next_dt <= '".$array['next_dt']."')
            //limit ".$array['number_of_cases']."
            $query = $this->db->query($sql_query);
            return $query->getResultArray();
        }
        return $return;
    }

    public function diaryDispos($diaryno){
        $builder = $this->db->table('main m');
        $builder->select('m.diary_no, d.disp_dt')
                ->join('dispose d', 'm.diary_no = d.diary_no')
                ->where('d.diary_no', $diaryno)
                ->where('m.c_status', 'D');

        $query = $builder->get();
        return $query->getResult();
    }

    public function shortDescMain($diaryno){
        $builder = $this->db->table('master.casetype c');
        $builder->select('c.short_description')
        ->join('main m', 'c.casecode = m.casetype_id')
        ->where('m.diary_no', $diaryno);
        $query = $builder->get();
        $result = $query->getRowArray();
        return isset($result['short_description']) ? $result['short_description'] : '';
    }

    public function getAdvocateId($diaryno){
        $builder = $this->db->table('advocate');
        $builder->select('pet_res_no, adv, advocate_id, pet_res')
                ->where('diary_no', $diaryno)
                ->where('display', 'Y')
                ->orderBy('pet_res', 'ASC');

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getBarDetails($bar_id){
        $builder = $this->db->table('master.bar');
        $builder->select('name, enroll_no, YEAR(enroll_date) as eyear, isdead')
                ->where('bar_id', $bar_id);

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getDefDays($diaryno){
        $subquery = $this->db->table('obj_save AS obj')
            ->select("(CURRENT_DATE - DATE(save_dt)) AS no_of_days", false) // false to avoid escaping
            ->join('main AS m', 'obj.diary_no = m.diary_no', 'left')
            ->where('obj.diary_no', $diaryno)
            ->where('rm_dt IS NULL')
            ->where('m.diary_no_rec_date >', '2018-10-14');

        $builder = $this->db->table('(' . $subquery->getCompiledSelect() . ') AS a');
        $result = $builder->selectMax('no_of_days')->countAllResults();
        return $result;
    }

    public function getDiaryRecall($diaryno){
        $builder = $this->db->table('recalled_matters');
        $builder->select('diary_no')
                ->where('diary_no', $diaryno);

        $query = $builder->get()->getRow();
        return $query;
    }

    public function getConsign($diaryno){
        $subquery1 = $this->db->table('record_keeping')
            ->select("diary_no, TO_CHAR(consignment_date, 'DD-MM-YYYY') AS consignment_date", false)
            ->where('diary_no', $diaryno)
            ->where('display', 'Y')
            ->where('consignment_status', 'Y');

        $subquery2 = $this->db->table('fil_trap_his')
            ->select("diary_no, TO_CHAR(rece_dt, 'DD-MM-YYYY') AS consignment_date", false)
            ->where('diary_no', $diaryno)
            ->where('remarks', 'DISPOSAL -> RR-DA');

        $subquery3 = $this->db->table('fil_trap')
            ->select("diary_no, TO_CHAR(rece_dt, 'DD-MM-YYYY') AS consignment_date", false)
            ->where('diary_no', $diaryno)
            ->where('remarks', 'RR-DA -> SEG-DA');

        $builder = $this->db->query(
            $subquery1->getCompiledSelect() . 
            ' UNION ' . 
            $subquery2->getCompiledSelect() . 
            ' UNION ' . 
            $subquery3->getCompiledSelect()
        );

        // Get results
        return $builder->getResultArray();

    }

    public function getSensitiveCase($diaryno, $ucode){
        $subquery = $this->db->table('master.sensitive_case_users')
            ->select('users_empid')
            ->getCompiledSelect(false);

        $builder = $this->db->table('sensitive_cases')
            ->select('diary_no')
            ->where('diary_no', $diaryno)
            ->where('display', 'Y')
            ->where("POSITION('$ucode' IN ({$subquery})) >= 1", null, false);

        return $builder->get()->getRow();
    }

    public function get_last_heardt($diaryno, $ent_dt){
        $subquery1 = $this->db->table('heardt')
            ->select("
                TO_CHAR(next_dt, 'DD-MM-YYYY') AS next_dt,
                clno,
                brd_slno AS brdslno,
                judges,
                subhead,
                mainhead,
                'H' AS tbl,
                diary_no AS filno,
                '' AS benchflag,
                next_dt AS next_dt_o,
                main_supp_flag,
                roster_id,
                board_type,
                tentative_cl_dt
            ", false)
            ->where('diary_no', $diaryno);
        if($ent_dt != ''){
            $subquery1->where('ent_dt <=', $ent_dt);
        }
            $subquery1->where('next_dt IS NOT NULL');

        $subquery2 = $this->db->table('last_heardt')
            ->select("
                TO_CHAR(next_dt, 'DD-MM-YYYY') AS next_dt,
                clno,
                0 AS brdslno,
                judges,
                subhead,
                mainhead,
                'L' AS tbl,
                diary_no AS filno,
                bench_flag AS benchflag,
                next_dt AS next_dt_o,
                main_supp_flag,
                roster_id,
                board_type,
                tentative_cl_dt
            ", false)
            ->where('diary_no', $diaryno);
            if($ent_dt != ''){
                $subquery2->where('ent_dt <=', $ent_dt);
            }
            $subquery2->groupStart()
                ->where('bench_flag', null)
                ->orWhere('bench_flag', '')
                ->orWhere('bench_flag', 'W')
            ->groupEnd()
            ->where('next_dt IS NOT NULL');

        $unionQuery = $subquery1->getCompiledSelect(false) . ' UNION ' . $subquery2->getCompiledSelect(false);

        $mainQuery = "
            SELECT t1.*,
                CASE 
                    WHEN t1.tbl = 'H' THEN CASE WHEN t1.main_supp_flag IN (1, 2) THEN 'L' ELSE 'P' END
                    ELSE CASE WHEN t1.main_supp_flag IN (1, 2) THEN 'L' ELSE 'P' END
                END AS porl
            FROM ({$unionQuery}) AS t1
            ORDER BY t1.tbl, t1.next_dt_o DESC";

        return $this->db->query($mainQuery)->getResultArray();
    }

    public function get_main_case($diaryno)
    {
        $ret_data = '';
        // $subquery = $this->db->table('master.casetype')
        //     ->select("
        //         CONCAT(
        //             (SELECT skey 
        //             FROM master.casetype 
        //             WHERE casecode = TRIM(LEADING '0' FROM SUBSTRING(main.conn_key FROM 3 FOR 3))::INTEGER),
        //             ' ',
        //             TRIM(LEADING '0' FROM SUBSTRING(main.conn_key FROM 6 FOR 5)),
        //             '/',
        //             SUBSTRING(main.conn_key FROM 11 FOR 4)
        //         )
        //     ", false)
        //     ->getCompiledSelect(false);

        // $builder = $this->db->table('main');
        // $builder->select("
        //     CASE 
        //         WHEN NOT (
        //             conn_key = '' 
        //             OR conn_key IS NULL 
        //             OR diary_no = conn_key::BIGINT
        //         ) THEN ({$subquery})
        //         ELSE ''
        //     END AS cn1
        // ", false);
        
        // $builder->where('diary_no', $diaryno);

        // $subquery = $this->db->table('master.casetype')
        //     ->select("CONCAT(
        //         skey, ' ', 
        //         TRIM(LEADING '0' FROM SUBSTRING(main.conn_key FROM 6 FOR 5)), 
        //         '/', 
        //         SUBSTRING(main.conn_key FROM 11 FOR 4)
        //     )")
        //     ->where("casecode = TRIM(LEADING '0' FROM SUBSTRING(main.conn_key FROM 3 FOR 3))::INTEGER", null, false)
        //     ->getCompiledSelect(); 

        // $builder = $this->db->table('main')
        //     ->select("CASE 
        //         WHEN NOT (
        //             conn_key = '' 
        //             OR conn_key IS NULL 
        //             OR diary_no = conn_key::BIGINT
        //         ) 
        //         THEN ($subquery)
        //         ELSE '' 
        //     END AS cn1", false)
        //     ->where('diary_no', $diaryno);

        // $subQuery = $this->db->table('master.casetype')
        //     ->select("CONCAT(skey, ' ', TRIM(LEADING '0' FROM SUBSTRING(main.conn_key FROM 6 FOR 5)), '/', SUBSTRING(main.conn_key FROM 11 FOR 4))", false)
        //     ->where("casecode = NULLIF(TRIM(LEADING '0' FROM SUBSTRING(main.conn_key FROM 3 FOR 3)), '')::INTEGER")
        //     ->limit(1);

        // $builder = $this->db->table('main');
        // $builder->select("
        //     CASE 
        //         WHEN NOT (conn_key = '' OR conn_key IS NULL OR diary_no = NULLIF(conn_key, '')::BIGINT) 
        //         THEN ($subQuery) 
        //         ELSE '' 
        //     END AS cn1
        // ", false);
        // $builder->where('diary_no', $diaryno);

        $subQuery = "(SELECT CONCAT(
                    skey, ' ', TRIM(LEADING '0' FROM SUBSTRING(main.conn_key FROM 6 FOR 5)), '/', 
                    SUBSTRING(main.conn_key FROM 11 FOR 4)
                ) 
                FROM master.casetype 
                WHERE casecode = NULLIF(TRIM(LEADING '0' FROM SUBSTRING(main.conn_key FROM 3 FOR 3)), '')::INTEGER
                LIMIT 1)";

        // Build the main query
        $builder = $this->db->table('main');
        $builder->select("
        CASE 
            WHEN NOT (conn_key = '' OR conn_key IS NULL OR diary_no = NULLIF(conn_key, '')::BIGINT) 
            THEN $subQuery 
            ELSE '' 
        END AS cn1
        ", false);
        $builder->where('diary_no', $diaryno);

        $query = $builder->get();
        $results = $query->getResult();

        if ($results[0]->cn1) {
            $ret_data = stripslashes(trim(strtoupper($results[0]->cn1)));
        }
        return $ret_data;
    }

    public function cl_printed($next_dt, $mainhead, $clno, $roster_id){
        $builder = $this->db->table('cl_printed');
        $builder->select('*');
        $builder->where('next_dt >=', date('Y-m-d'));
        $builder->where('next_dt', date('Y-m-d', strtotime($next_dt)) );
        $builder->where('m_f', $mainhead);
        $builder->where('part', $clno);
        $builder->where('roster_id', $roster_id);
        $builder->where('display', 'Y');

        return $query = $builder->countAllResults();
        return $query->getRow();
    }

    public function drop_details($diaryno, $next_dt, $roster_id){
        $builder = $this->db->table('drop_note d');
        $builder->select('d.*');
        $builder->where('d.diary_no', $diaryno);
        $builder->where('d.display', 'Y');
        $builder->where('d.cl_date', date('Y-m-d', strtotime($next_dt)));
        $builder->where('d.roster_id !=', $roster_id);
        $builder->orderBy('d.ent_dt', 'ASC');

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_Courtno($diaryno){
        $builder = $this->db->table('heardt');
        $builder->select('r.courtno');
        $builder->join('roster r', 'h.roster_id = r.id');
        $builder->where('h.diary_no', $diaryno);
        $builder->where('r.display', 'Y');
        
        if($builder->countAllResults(false)){
            $query = $builder->get();
            return $query->getResultArray()[0]['courtno'];
        }
        return null;
        
    }

    public function get_subheading($subhead) {
        $builder = $this->db->table('master.subheading');
        $builder->select('*');
        $builder->groupStart()
            ->where("CASE WHEN POSITION('$subhead' IN '$subhead') > 0 THEN stagecode IN ($subhead) ELSE stagecode = 830 END", null, false)
        ->groupEnd();
        $builder->where('display', 'Y');
        $builder->orderBy("CASE WHEN stagecode = $subhead THEN 0 ELSE 1 END", '', false);
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function tbl_schema() {
        $builder = $this->db->table('information_schema.tables');
        $builder->select([
            "table_schema || '.' || table_name AS tbl",
            "TO_CHAR(CURRENT_TIMESTAMP, 'DD-MM-YYYY HH12:MI AM') AS create_time"
        ], false);
        $builder->where('table_schema', 'demoas');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getSchematblRec($tbl, $diaryno, $row1_s) {
        $builder = $this->db->table($tbl);
        $builder->select('*');
        if ($row1_s["stagecode4"] > 0) {
            $builder->where('stagecode1', $row1_s["stagecode1"]);
            $builder->where('stagecode2', $row1_s["stagecode2"]);
            $builder->where('stagecode3', $row1_s["stagecode3"]);
            $builder->where('stagecode4', $row1_s["stagecode4"]);
        } elseif ($row1_s["stagecode3"] > 0) {
            $builder->where('stagecode1', $row1_s["stagecode1"]);
            $builder->where('stagecode2', $row1_s["stagecode2"]);
            $builder->where('stagecode3', $row1_s["stagecode3"]);
        } elseif ($row1_s["stagecode2"] > 0) {
            $builder->where('stagecode1', $row1_s["stagecode1"]);
            $builder->where('stagecode2', $row1_s["stagecode2"]);
        } elseif ($row1_s["stagecode1"] > 0) {
            $builder->where('stagecode1', $row1_s["stagecode1"]);
        }
        
        $builder->where('diary_no', $diaryno);
        $query = $builder->get();
        return $query->getResultArray();
    }

    function get_previous_stage($filno_1, $sc)
    {
        $subQuery = $this->db->table('heardt')
            ->select('subhead, ent_dt')
            ->where('diary_no', $filno_1)
            ->where('subhead !=', $sc)
            ->union(
                $this->db->table('last_heardt')
                    ->select('subhead, ent_dt')
                    ->where('diary_no', $filno_1)
                    ->where('subhead !=', $sc)
            );

        $builder = $this->db->table("({$subQuery->getCompiledSelect()}) AS a1")
            ->select("(SELECT stagename FROM master.subheading WHERE stagecode = a1.subhead) AS pstage", false)
            ->orderBy('a1.ent_dt', 'DESC')
            ->limit(1);

        if($builder->countAllResults(false)){
            $query = $builder->get();
            $result = $query->getRow();
            return $result->pstage;
        }
        return null;
    }

    public function get_Admit_dt($diaryno){
        $builder = $this->db->table('main');
        $builder->select('fil_dt_fh AS admit_dt');
        $builder->where('diary_no', $diaryno);
        if($builder->countAllResults(false)){
            $query = $builder->get();
            return $query->getRow();
        }
        return null;
    }

    public function get_fillClose($diaryno){
        // Subquery 1: last_heardt
        $subquery1 = $this->db->table('last_heardt lh')
        ->select([
            "CAST(lh.diary_no AS VARCHAR) AS filno",
            "CAST(lh.next_dt AS DATE) AS cldate"
        ], false)
        ->where('lh.diary_no', $diaryno)
        ->where('lh.mainhead', 'F')
        ->where("lh.next_dt < (CURRENT_DATE - INTERVAL '1 day' * (EXTRACT(DOW FROM CURRENT_DATE)::integer - 2))", null, false)
        ->groupStart()
            ->where('lh.bench_flag', '')
            ->orWhere('lh.bench_flag IS NULL', null, false)
        ->groupEnd();

        // Subquery 2: case_remarks_multiple
        $subquery2 = $this->db->table('case_remarks_multiple cr')
        ->select([
            "CAST(cr.diary_no AS VARCHAR) AS filno",
            "CAST(cr.cl_date AS DATE) AS cldate"
        ], false)
        ->where('cr.diary_no', $diaryno)
        ->whereIn('cr.r_head', [81, 74, 75, 65, 2, 1, 94]);

        // Combine subqueries with UNION
        $unionSql = $subquery1->getCompiledSelect(false) . ' UNION ' . $subquery2->getCompiledSelect(false);

        // Main query using the UNION subquery
        $builder = $this->db->table("($unionSql) z1")
        ->select([
            'z1.filno',
            "TO_CHAR(z1.cldate, 'DD-MM-YYYY') AS cldate"
        ], false)
        ->groupBy(['z1.filno', 'z1.cldate'])
        ->orderBy('z1.cldate');
        // Execute query
        if($builder->countAllResults(false)){
            $query = $builder->get();
            return $query->getRow();
        }
        return null;
    }

    public function case_remark($diaryno, $next_date){
        $builder = $this->db->table('case_remarks_multiple a');
        $builder->select([
            'a.status',
            "STRING_AGG(b.head || CASE WHEN a.head_content != '' THEN '(' || a.head_content || ')' ELSE '' END, ',') AS aggregated_heads"
        ], false)
        ->join('master.case_remarks_head b', 'a.r_head = b.sno')
        ->where('a.diary_no', $diaryno)
        ->where('a.cl_date', date('Y-m-d', strtotime($next_date)))
        ->groupBy('a.status');

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function list_purpose($listorder){
        $builder = $this->db->table('master.listing_purpose');
        $builder->select(["code || '. ' || purpose AS lp"], false)
        ->where('code', $listorder)
        ->orderBy('code');
        $query = $builder->get();
        return $query->getRow();
    }

    public function judgeDetails($jid, $con = true){
        $builder = $this->db->table('master.judge');
        $builder->select('*')
        ->where('jcode', $jid);
        if($con){
            $builder->where('display', 'Y');
        }
        
        $query = $builder->get();
        return $query->getRow();
    }

    public function mainUserinfo($diaryno){
        $builder = $this->db->table('main a');
        $builder->select([
            'fil_dt',
            "COALESCE(TO_CHAR(last_dt, 'DD-MM-YYYY HH12:MI AM'), '') AS last_dt",
            'a.usercode',
            "COALESCE(CAST(last_usercode AS TEXT), '') AS last_usercode",
            'b.name AS user',
            'c.name AS last_u'
        ]);
        $builder->join('master.users b', 'a.usercode = b.usercode', 'left');
        $builder->join('master.users c', 'a.last_usercode = c.usercode', 'left');
        $builder->where('diary_no', $diaryno);

        if($builder->countAllResults(false) > 0){
            $query = $builder->get();
            return $query->getResultArray()[0];
        }
        return [];
        
    }

    public function mainUsersectionInfo($diaryno){
        $builder = $this->db->table('main a');
        $builder->select(['a.usercode', 'name', 'section_name']);
        $builder->join('master.users b', 'a.diary_user_id = b.usercode', 'left');
        $builder->join('master.usersection us', 'b.section = us.id', 'left');
        $builder->where('diary_no', $diaryno);

        if($builder->countAllResults(false) > 0){
            $query = $builder->get();
            return $query->getResultArray()[0];
        }
        return [];
        
    }

    public function get_maininfo($diaryno) {
        $builder = $this->db->table('main m');
        $builder->select([
            "CONCAT(m.active_fil_no, ':', 
                CASE 
                    WHEN active_reg_year = 0 OR DATE(active_fil_dt) > DATE '2017-05-10' 
                    THEN EXTRACT(YEAR FROM active_fil_dt) 
                    ELSE active_reg_year 
                END, 
                ':', TO_CHAR(active_fil_dt, 'DD-MM-YYYY')
            ) AS ad",

            "CASE 
                WHEN fil_no_fh != active_fil_no AND fil_no_fh != fil_no AND fil_no_fh != '' 
                THEN CONCAT(m.fil_no_fh, ':', 
                    CASE 
                        WHEN reg_year_fh = 0 OR DATE(fil_dt_fh) > DATE '2017-05-10' 
                        THEN EXTRACT(YEAR FROM fil_dt_fh) 
                        ELSE reg_year_fh 
                    END, 
                    ':', TO_CHAR(fil_dt_fh, 'DD-MM-YYYY')
                ) 
                ELSE '' 
            END AS rd",

            "CASE 
                WHEN fil_no != active_fil_no AND fil_no_fh != fil_no AND fil_no != '' 
                THEN CONCAT(m.fil_no, ':', 
                    CASE 
                        WHEN reg_year_mh = 0 OR DATE(fil_dt) > DATE '2017-05-10' 
                        THEN EXTRACT(YEAR FROM fil_dt) 
                        ELSE reg_year_mh 
                    END, 
                    ':', TO_CHAR(fil_dt, 'DD-MM-YYYY')
                ) 
                ELSE '' 
            END AS md"
        ]);

        $builder->where('diary_no', $diaryno);

        $query = $builder->get();
        return $query->getRow();
    }

    public function getCasetypeHistory($diaryno){
        $subquery = $this->db->table('main_casetype_history m')
            ->select([
                'ROW_NUMBER() OVER () AS rowid',
                'm.*',
                "CASE 
                    WHEN ROW_NUMBER() OVER () = 1 
                        AND (old_registration_number IS NOT NULL AND old_registration_number <> '') 
                    THEN CONCAT(old_registration_number, ':', old_registration_year, ':', TO_CHAR(order_date, 'DD-MM-YYYY'))
                    ELSE ''
                END AS oldno"
            ])
            ->where('diary_no', $diaryno)
            ->where('is_deleted', 'f')
            ->orderBy('m.order_date, m.id')
            ->getCompiledSelect();

        $builder = $this->db->table("($subquery) t");
        $builder->select([
            't.oldno',
            "STRING_AGG(
                DISTINCT CONCAT(t.new_registration_number, ':', t.new_registration_year, ':', TO_CHAR(t.order_date, 'DD-MM-YYYY')),
                ',' ORDER BY CONCAT(t.new_registration_number, ':', t.new_registration_year, ':', TO_CHAR(t.order_date, 'DD-MM-YYYY'))
            ) AS newno"
        ]);
        $builder->groupBy('t.oldno');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getCasetypeLwr($diaryno){
        $builder = $this->db->table('lowerct a');
        $builder->select(['lct_dec_dt', 'lct_caseno', 'lct_caseyear', 'short_description AS type_sname']);
        $builder->join('master.casetype ct', "ct.casecode = a.lct_casetype AND ct.display = 'Y'", 'left');
        $builder->where('lw_display', 'Y');
        $builder->where('ct_code', 4);
        $builder->where('is_order_challenged', 'Y');
        $builder->where('a.diary_no', $diaryno);
        $builder->orderBy('a.lct_dec_dt, lct_caseno');

        if($builder->countAllResults(false) > 0){
            $query = $builder->get();
            return $query->getResultArray();
        }
        return [];
        
    }

    public function getmCaseStaus($case_status_id){
        $builder = $this->db->table('master.master_case_status');
        $builder->select('description');
        $builder->where('id', $case_status_id);

        $query = $builder->get();
        return $query->getRow();
        
    }

    public function getUsersectionDetails($diaryno){
        $builder = $this->db->table('main a');
        $builder->select([
            'dacode',
            'name',
            'section_name',
            'casetype_id',
            'active_casetype_id',
            'diary_no_rec_date',
            'reg_year_mh',
            'reg_year_fh',
            'active_reg_year',
            'ref_agency_state_id'
        ]);
        $builder->join('master.users b', 'a.dacode = b.usercode', 'left');
        $builder->join('master.usersection us', 'b.section = us.id', 'left');
        $builder->where('a.diary_no', $diaryno);

        $query = $builder->get();
        return $query->getRow();

    }

    public function main_tentative($diaryno){
        $builder = $this->db->table('main');
        $builder->select('tentative_section(diary_no) AS tentative_section');
        $builder->where('diary_no', $diaryno);
        
        $query = $builder->get();
        return $query->getRow();
    }

    public function getDaCaseDistribution($case_type, $year, $state){
        $builder = $this->db->table('master.da_case_distribution a');
        $builder->select([
            'dacode',
            'section_name',
            'name'
        ]);
        $builder->join('master.users b', 'b.usercode = a.dacode', 'left');
        $builder->join('master.usersection c', 'b.section = c.id', 'left');
        $builder->where('a.case_type', $case_type);
        $builder->where("$year BETWEEN a.case_f_yr AND a.case_t_yr", null, false);
        $builder->where('a.state', $state);
        $builder->where('a.display', 'Y');

        $query = $builder->get();
        return $query->getRow();
    }

    public function getlowerct($diaryno){
        $builder = $this->db->table('lowerct');
        $builder->select(['ct_code','l_state','lct_casetype','lct_caseno','lct_caseyear']);
        $builder->where('diary_no', $diaryno);
        $builder->where('lw_display', 'Y');

        $query = $builder->get();
        return $query->getRow();
        
    }


    public function getMainCaseHistory($c_typeId, $year, $lct_caseno){
        $builder = $this->db->table('main_casetype_history a');
        $builder->select([
            'a.diary_no',
            'a.new_registration_number',
            "SPLIT_PART(SPLIT_PART(a.new_registration_number, '-', 2), '-', -1)::INTEGER AS reg_start",
            "SPLIT_PART(a.new_registration_number, '-', -1)::INTEGER AS reg_end",
            'b.dacode',
            'c.name',
            'us.section_name',
            'b.casetype_id',
            'b.active_casetype_id',
            'b.diary_no_rec_date',
            'b.reg_year_mh',
            'b.reg_year_fh',
            'b.active_reg_year',
            'b.ref_agency_state_id'
        ]);
        $builder->join('main b', 'a.diary_no = b.diary_no', 'left');
        $builder->join('master.users c', 'b.dacode = c.usercode', 'left');
        $builder->join('master.usersection us', 'c.section = us.id', 'left');
        $builder->where('a.ref_new_case_type_id', $c_typeId);
        $builder->where('a.new_registration_year', $year);
        $builder->where('a.is_deleted', 'f');

        $builder->where("'$lct_caseno' BETWEEN SPLIT_PART(SPLIT_PART(a.new_registration_number, '-', 2), '-', -1)::INTEGER 
                                AND SPLIT_PART(a.new_registration_number, '-', -1)::INTEGER", null, false);

        
        $query = $builder->get();
        return $query->getRow();
    }

    public function get_dispose($diaryno){
        $builder = $this->db->table('dispose d');
        $builder->select([
            'd.rj_dt',
            'd.jud_id',
            "TO_CHAR(d.disp_dt, 'DD-MM-YYYY') AS ddt",
            "TO_CHAR(d.ord_dt, 'DD-MM-YYYY') AS odt",
            "TO_CHAR(d.ord_dt, 'DD-MM-YYYY') AS ord_dt",
            'd.disp_dt',
            'd.month',
            'd.year',
            'd.dispjud',
            'd.disp_type',
            "STRING_AGG(j.jname, ', ' ORDER BY j.judge_seniority) AS judges",
            'd.dispjud'
        ]);

        $builder->join('master.judge j', "CAST(j.jcode AS TEXT) = ANY(string_to_array(d.jud_id, ','))", 'left');
        $builder->where('d.diary_no', $diaryno);
        $builder->groupBy(['d.diary_no', 'd.rj_dt', 'd.jud_id', 'd.disp_dt', 'd.ord_dt', 'd.month', 'd.year', 'd.dispjud', 'd.disp_type']);

        if ($builder->countAllResults(false) >= 1) {
            $query = $builder->get();
            return $query->getResultArray()[0];
        } else {
            return null;
        }
    }

    public function getHeardtCat($diaryno){
        $builder = $this->db->table('heardt h');
        $builder->select('h.diary_no');
        $builder->join('mul_category mc', 'mc.diary_no = h.diary_no AND (mc.submaster_id = 912 OR h.subhead = 818)', 'left');
        $builder->where('mc.diary_no IS NOT NULL', null, false);
        $builder->where('h.diary_no', $diaryno);

        return $builder->countAllResults();
        // $query = $builder->get();
        // return $query->getResultArray();
    }

    public function judgeCaseRemark($diaryno){
        $subQuery = $this->db->table('case_remarks_multiple')
            ->select('jcodes')
            ->where('diary_no', $diaryno)
            ->groupBy(['cl_date', 'jcodes', 'e_date'])
            ->orderBy('e_date', 'DESC')
            ->limit(1, 1)
            ->getCompiledSelect();

        $builder = $this->db->table("($subQuery) as a");
        $builder->select("STRING_AGG(j.jname, ', ' ORDER BY j.judge_seniority) AS judges");
        $builder->join('master.judge j', "CAST(j.jcode AS TEXT) = ANY(string_to_array(a.jcodes, ','))", 'left');
        $query = $builder->get();
        return $query->getResultArray();  
    }

    public function get_disposal($dcode){
        $builder = $this->db->table('master.disposal');
        $builder->select('*')
        ->where('dispcode', $dcode);
        
        $query = $builder->get();
        return $query->getRow();
    }

    public function count_main($diaryno){
        $builder = $this->db->table('main');
        $builder->select('*');
        $builder->where('diary_no', $diaryno);
        $builder->whereIn('active_casetype_id', [9, 10, 25, 26]);

        return $builder->countAllResults();
    }


    public function count_mul_cat($diaryno){
        $builder = $this->db->table('mul_category');
        $builder->select('*');
        $builder->where('diary_no', $diaryno);
        $builder->where('display', 'Y');
        $builder->whereIn('submaster_id', [239, 240, 241, 242, 243]);

        return $builder->countAllResults();
    }

    public function count_caseRemark($diaryno){
        $subQuery = $this->db->table('case_remarks_multiple')
            ->select('MAX(cl_date)', false)
            ->where('diary_no', $diaryno)
            ->getCompiledSelect();

        $builder = $this->db->table('case_remarks_multiple');
        $builder->select('*');
        $builder->where('diary_no', $diaryno);
        $builder->where('r_head', 191);
        $builder->where("cl_date IN ($subQuery)", null, false);

        return $builder->countAllResults();
    }


    public function getCase_status($flag){
        $builder = $this->db->table('master.case_status_flag');
        $builder->select('display_flag, always_allowed_users');
        $builder->where('flag_name', $flag);
        $builder->where('to_date IS NULL', null, false);

        $query = $builder->get();
        return $query->getRow();
    }

    
    public function getbrdrem($diaryno){
        $builder = $this->db->table('brdrem');
        $builder->select("remark, TO_CHAR(ent_dt, 'DD-MM-YYYY') AS entdt", false);
        $builder->where('diary_no', $diaryno);

        $query = $builder->get();
        return $query->getRow();
    }

    public function get_rgo_default($diaryno){
        $builder = $this->db->table('rgo_default');
        $builder->select('fil_no2, hcourt_no, court_type');
        $builder->where('fil_no', $diaryno);
        $builder->where('remove_def', 'N');
        $builder->orderBy('ent_dt', 'DESC');
        $builder->limit(1);

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_lchc_casetype($lcode0, $lcode2, $courtp = false, $aCode = false){
        $builder = $this->db->table('master.lc_hc_casetype');
        $builder->select('lccasecode, type_sname as lccasename');
        $builder->where('cmis_state_id', $lcode0);
        $builder->where('lccasecode', $lcode2);        
        if($courtp){
            $builder->where('corttyp', $courtp);
        }

        if($aCode){
            $builder->where('ref_agency_code_id !=', 0);
        }
        
        $builder->where('display', 'Y');
        $builder->groupStart()
            ->where('type_sname !=', '')
            ->orWhere('type_sname IS NOT NULL', null, false)
        ->groupEnd();
        $builder->orderBy('lccasename');

        $query = $builder->get();
        return $query->getRow();

    }


    public function getbench($id){
        $builder = $this->db->table('master.master_bench');
        $builder->select('*');
        $builder->where('display', 'Y');
        $builder->where('id', $id);

        $query = $builder->get();
        return $query->getRow();
    }


    public function getnbf($diaryno){
        $builder = $this->db->table('not_before a');
        $builder->select("
            a.diary_no, 
            STRING_AGG(b.jname, ', ') AS jn, 
            a.notbef, 
            TO_CHAR(a.ent_dt, 'DD-MM-YYYY HH24:MI:SS') AS entdt", false);
        $builder->join('master.judge b', 'b.jcode = a.j1', 'inner');
        $builder->where('a.diary_no', $diaryno);
        $builder->groupBy('a.diary_no, a.notbef, a.ent_dt');

        $query = $builder->get();
        return $query->getResultArray();

    }


    public function get_actmain($diaryno){
        $builder = $this->db->table('act_main a');
        $builder->select("
            a.act, 
            STRING_AGG(b.section, ', ') AS section, 
            c.act_name", false);
        $builder->join('master.act_master c', "c.id = a.act AND c.display = 'Y'", 'inner');
        $builder->join('master.act_section b', "b.act_id = a.id AND b.display = 'Y'", 'left');
        $builder->where('a.diary_no', $diaryno);
        $builder->where('a.display', 'Y');
        $builder->groupBy('a.act, c.act_name');

        $query = $builder->get();
        return $query->getRow();
    }


    function get_Sub_actmain($diaryno) {
        $subQuery = $this->db->table('act_main')
                    ->select('act')
                    ->where('diary_no', $diaryno);

        $builder = $this->db->table("({$subQuery->getCompiledSelect()}) a");
        $builder->select('a.*, b.act_name');
        $builder->join('master.act_master b', 'a.act = b.id', 'inner');

        $query = $builder->get();
        return $query->getResultArray();

    }


    public function get_fil_trap($diaryno){
        $sql = "
            (SELECT diary_no, d_by_empid, disp_dt, remarks, r_by_empid, d_to_empid, rece_dt, comp_dt, other 
            FROM fil_trap 
            WHERE diary_no = $diaryno) 

            UNION ALL 

            (SELECT diary_no, d_by_empid, disp_dt, remarks, r_by_empid, d_to_empid, rece_dt, comp_dt, other 
            FROM fil_trap_his 
            WHERE diary_no = $diaryno 
            AND comp_dt = (SELECT MAX(comp_dt) FROM fil_trap_his WHERE diary_no = $diaryno))
        ";

        // Wrap the raw SQL as a subquery
        $builder = $this->db->table("({$sql}) a", false)
            ->select('a.*, u1.name AS d_by_name, u2.name AS r_by_name, u3.name AS o_name, u4.name AS d_to_name')
            ->join('master.users u1', 'a.d_by_empid = u1.empid', 'left')
            ->join('master.users u2', 'a.r_by_empid = u2.empid', 'left')
            ->join('master.users u3', 'a.other = u3.empid', 'left')
            ->join('master.users u4', 'a.d_to_empid = u4.empid', 'left')
            ->orderBy('a.disp_dt', 'DESC')
            ->orderBy('a.rece_dt', 'DESC');

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_diary_movement($diaryno){
        $builder = $this->db->table('diary_movement dm')
            ->select([
                'u1.name AS d_by_name',
                'us1.section_name AS dby_section',
                'u2.name AS d_to_name',
                'us2.section_name AS dto_section',
                'dm.disp_dt',
                'dm.remark',
                'dm.rece_dt'
            ])
            ->join('diary_copy_set ds', 'ds.id = dm.diary_copy_set', 'inner')
            ->join('main m', 'm.diary_no = ds.diary_no', 'inner')
            ->join('master.users u1', 'u1.usercode = dm.disp_by', 'inner')
            ->join('master.users u2', 'u2.usercode = dm.disp_to', 'inner')
            ->join('master.usersection us1', 'us1.id = u1.section AND us1.display = \'Y\'', 'left')
            ->join('master.usersection us2', 'us2.id = u2.section AND us2.display = \'Y\'', 'left')
            ->where('ds.diary_no', $diaryno)
            ->groupBy([
                'u1.name', 'us1.section_name', 
                'u2.name', 'us2.section_name', 
                'dm.disp_dt', 'dm.remark', 'dm.rece_dt', 
                'ds.diary_no'
            ]);


        $query = $builder->get();
        return $query->getResultArray();
    }

}
