<?php
namespace App\Models\Listing;
use CodeIgniter\Model;

class EliminationModel extends Model
{
    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
    }

    public function advance_eliminated_dates()
    {
        $builder = $this->db->table('transfer_old_com_gen_cases c');
        $builder->select('c.next_dt_old');
        $builder->where('c.next_dt_old >=', date('Y-m-d'));
        $builder->where('c.listtype', 'A');
        $builder->groupBy('c.next_dt_old');
        $query = $builder->get();
        $results = $query->getResultArray();
        return $results;
    }

    public function sc_working_days($list_dt)
    {
        $result = 0;
        $builder = $this->db->table('master.sc_working_days');
        $builder->select('is_nmd');
        $builder->where('display', 'Y');
        $builder->where('is_nmd', 1);
        $builder->where('is_holiday', 0);
        $builder->where('working_date', $list_dt); // Assuming $list_dt contains the date
        $exists = $builder->countAllResults() > 0;
        if ($exists) {
            $result = 1;
        }
        return $result;
    }

    public function advance_list_no($list_dt, $board_type)
    {
        $builder = $this->db->table('advance_elimination_cl_printed');
        $builder->selectCount('next_dt', 'advance_list_no');
        $builder->where("EXTRACT(YEAR FROM next_dt) = ", date('Y', strtotime($list_dt)));
        $builder->where('board_type', $board_type);
        $query = $builder->get();
        $results = $query->getRowArray();
        return $results;
    }

    public function get_advance_eliminations($list_dt, $board_type){
        //$list_dt= '2023-02-17';
        $builder = $this->db->table('transfer_old_com_gen_cases t');
        // Select necessary columns
        $builder->select('dd.doccode1, a.advocate_id, submaster_id, h.coram, t.*,
                        m.reg_no_display, m.mf_active, m.pet_name, m.res_name,
                        pno, rno, m.diary_no_rec_date,
                        tentative_section(m.diary_no::text) as section_name');

        // Join related tables
        $builder->join('main m', 't.diary_no = m.diary_no', 'left');
        $builder->join('heardt h', 'h.diary_no = m.diary_no', 'left');
        $builder->join('master.listing_purpose l', 'l.code = h.listorder', 'left');
        $builder->join('mul_category mc', 'mc.diary_no = m.diary_no', 'left');
        $builder->join('docdetails dd', "dd.diary_no = h.diary_no AND dd.iastat = 'P' AND dd.doccode = 8 AND dd.doccode1 IN (40,41,48,49,71,72,118,131,211,309)", 'left');
        $builder->join('advocate a', "a.diary_no = m.diary_no AND a.advocate_id = 584 AND a.display = 'Y'", 'left');
        $builder->join('conct ct', "m.diary_no = ct.diary_no AND ct.list = 'Y'", 'left');

        // Apply the WHERE conditions
        $builder->where('mc.display', 'Y');
        $builder->where('m.c_status', 'P');
        $builder->groupStart()
                ->where('t.diary_no = t.conn_key')
                ->orWhere('t.conn_key', 0)
                ->orWhere('t.conn_key IS NULL')
                ->groupEnd();
        $builder->where('h.mainhead', 'M');
        $builder->where('t.board_type', $board_type);
        $builder->where('t.next_dt_old', $list_dt);
        $builder->where('t.listtype', 'A');
        $builder->where('t.listorder >', 0);
        $builder->where('t.listorder !=', 32);

        // Group by m.diary_no
        //$builder->groupBy('m.diary_no');

        // Order by conditions with CASE/IF-like logic using direct conditions
        $builder->orderBy("(CASE WHEN h.subhead IN ('824', '810', '802', '807', '804') OR dd.doccode1 IS NOT NULL OR a.advocate_id IS NOT NULL OR mc.submaster_id = 173 OR t.listorder IN (4, 5, 7) THEN 1 ELSE 999 END) ASC");
        $builder->orderBy('l.priority', 'asc');
        $builder->orderBy('h.no_of_time_deleted', 'desc');

        $builder->orderBy("(CASE WHEN h.coram IS NOT NULL AND TRIM(h.coram) IS NOT NULL THEN 1 ELSE 999 END) DESC");
        $builder->orderBy("(COALESCE(ct.ent_dt, '1970-01-01 00:00:00')) ASC");
        $builder->orderBy("(CAST(RIGHT(CAST(m.diary_no AS TEXT), 4) AS INTEGER)) DESC");
        $builder->orderBy("(CAST(LEFT(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER)) DESC");
        // Execute the query
        $query = $builder->get();
        $results = $query->getResultArray();
        $radvname = $padvname = '';
        foreach ($results as $index => $result) {
            $results[$index]['sno'] = $index + 1; // Serial number starting from 1
            $advocate = $this->get_advocate_bar($result['diary_no']);
            if(!empty($advocate)) {
                $radvname = $advocate["r_n"];
                $padvname = $advocate["p_n"];
            }
            if ($result['pno'] == 2) {
                $pet_name = $result['pet_name'] . " AND ANR.";
            } else if ($result['pno'] > 2) {
                $pet_name = $result['pet_name'] . " AND ORS.";
            } else {
                $pet_name = $result['pet_name'];
            }
            if ($result['rno'] == 2) {
                $res_name = $result['res_name'] . " AND ANR.";
            } else if ($result['rno'] > 2) {
                $res_name = $result['res_name'] . " AND ORS.";
            } else {
                $res_name = $result['res_name'];
            }

            $results[$index]['radvname'] = !empty($radvname) ? str_replace(",", ", ", trim($radvname, ",")) : '';
            $results[$index]['padvname'] = !empty($padvname) ? str_replace(",", ", ", trim($padvname, ",")) : '';
            $results[$index]['get_pet_name'] = $pet_name;
            $results[$index]['res_name'] = $res_name;

            if($result['diary_no'] == $result['conn_key']) {
                $old_cases = $this->get_transfer_old_cases($result['diary_no'], $list_dt, $board_type);
                $results[$index]['old_cases'] = $old_cases;
                foreach($old_cases as $old_case_index => $old_case) {
                    $advocate_by_old_cases = $this->get_advocate_bar($old_case['diary_no']);
                    $results[$index]['old_cases'][$old_case_index]['advocate_by_old_cases'] =  $advocate_by_old_cases;
                }
            }
        }
        return $results;
    }
    
    public function get_advocate_bar($diary_no)
    {
        $subquery = $this->db->table('advocate a')
        ->select("
            a.diary_no, 
            b.name, 
            STRING_AGG(COALESCE(a.adv, ''), '' ORDER BY CASE WHEN pet_res = 'I' THEN 99 ELSE 0 END ASC, 'adv_type DESC', pet_res_no ASC) AS grp_adv,
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
        ->orderBy('(a.pet_res_no) ASC');
        $builder = $this->db->table('(' . $subquery->getCompiledSelect() . ') a', false);
        $builder->select("
            a.diary_no, 
            STRING_AGG(a.name || CASE WHEN a.pet_res = 'R' THEN a.grp_adv END, ', ' ORDER BY a.adv_type DESC, a.pet_res_no ASC) AS r_n,
            STRING_AGG(a.name || CASE WHEN a.pet_res = 'P' THEN a.grp_adv END, ', ' ORDER BY a.adv_type DESC, a.pet_res_no ASC) AS p_n,
            STRING_AGG(a.name || CASE WHEN a.pet_res = 'I' THEN a.grp_adv END, ', ' ORDER BY a.adv_type DESC, a.pet_res_no ASC) AS i_n
        ", false);
        $builder->groupBy('a.diary_no');      
        $query = $builder->get();
        $results = $query->getRowArray();
        return $results;
    }

    public function get_advocate_bar_bk($diary_no) {
        $subquery = $this->db->table('advocate a')
            ->select("
                a.diary_no,
                b.name,
                STRING_AGG(
                    COALESCE(a.adv, ''), ''
                ORDER BY CASE WHEN pet_res = 'I' THEN 99 ELSE 0 END ASC, 'adv_type DESC', pet_res_no ASC
                ) AS grp_adv,
                a.pet_res,
                a.adv_type,
                a.pet_res_no
            ", false) // false prevents escaping the SQL
        ->join('master.bar b', 'a.advocate_id = b.bar_id AND b.isdead != \'Y\'', 'left')
        ->where('a.diary_no', $diary_no)
        ->where('a.display', 'Y')
        ->groupBy('a.diary_no, b.name, a.pet_res, a.adv_type, a.pet_res_no')
        ->orderBy("(CASE WHEN a.pet_res = 'I' THEN 99 ELSE 0 END) ASC")
                    ->orderBy('(a.adv_type) DESC')
                    ->orderBy('(a.pet_res_no) ASC')
        ->getCompiledSelect();

        // Now use the compiled subquery as a raw SQL string in the main query
        $builder = $this->db->table("($subquery) a", false); // Use the raw SQL string in parentheses
        $builder->select("
        a.*,
        STRING_AGG(a.name || '' || (CASE WHEN pet_res = 'R' THEN grp_adv END), '') AS r_n,
        STRING_AGG(a.name || '' || (CASE WHEN pet_res = 'P' THEN grp_adv END), '') AS p_n,
        STRING_AGG(a.name || '' || (CASE WHEN pet_res = 'I' THEN grp_adv END), '') AS i_n", false);
        $builder->groupBy('a.diary_no, a.name, a.grp_adv, a.pet_res, a.adv_type, a.pet_res_no');
        $query = $builder->get();
        $results = $query->getRowArray();
        return $results;
    }

    public function get_transfer_old_cases($diary_no, $list_dt, $board_type)
    {
        //$diary_no = '282422022';
        //$list_dt = '2023-05-19';

        $builder = $this->db->table('transfer_old_com_gen_cases t')
        ->select("tentative_section(m.diary_no::text) as section_name, submaster_id, h.coram, t.*, m.reg_no_display, m.mf_active, m.pet_name, m.res_name, pno, rno, m.diary_no_rec_date")
        ->join('main m', 't.diary_no = m.diary_no', 'left')
        ->join('heardt h', 'h.diary_no = m.diary_no', 'left')
        ->join('conct c', "c.conn_key = CAST(m.conn_key AS BIGINT) AND c.list = 'Y'", 'inner')
        ->join('master.listing_purpose l', 'l.code = h.listorder', 'left')
        ->join('rgo_default rd', "rd.fil_no = h.diary_no AND rd.remove_def = 'N'", 'left')
        ->join('mul_category mc', 'mc.diary_no = m.diary_no', 'left')
        ->where('rd.fil_no IS NULL')
        ->whereNotIn('m.active_casetype_id', [9, 10, 25, 26])
        ->where('mc.display', 'Y')
        ->whereNotIn('mc.submaster_id', [911, 912, 914, 239, 240, 241, 242, 243])
        ->groupStart()
            ->whereIn('h.listorder', [4, 5])
            ->orwhere('h.is_nmd', 'N')
        ->groupEnd()
        ->whereNotIn('mc.submaster_id', [343, 15, 16, 17, 18, 19, 20, 21, 22, 23, 341, 353, 157, 158, 159, 160, 161, 162, 163, 166, 175, 176, 322, 222])
        ->where('m.c_status', 'P')
        ->where('t.conn_key', $diary_no)
        ->where('t.diary_no != t.conn_key')
        ->whereNotIn('h.subhead', [801, 817, 818, 819, 820, 848, 849, 850, 854])
        ->where('h.mainhead', 'M')
        ->where('t.board_type', $board_type)
        ->where('t.next_dt_old', $list_dt)
        ->where('t.listtype', 'A')
        ->where('h.listorder >', 0)
        ->where('h.listorder !=', 32)
        ->groupBy('m.diary_no, mc.submaster_id, h.coram, t.diary_no, t.next_dt_old, t.next_dt_new, t.tentative_cl_dt_old,t.tentative_cl_dt_new, t.listorder, t.conn_key,t.ent_dt,t.test2, t.listorder_new,t.board_type,t.listtype, t.reason, t.updated_by_ip,t.updated_by,t.updated_on,t.create_modify')
        ->orderBy("(CAST(RIGHT(CAST(m.diary_no AS TEXT), 4) AS INTEGER)) DESC")
        ->orderBy("(CAST(LEFT(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER)) DESC");
        $query = $builder->get();
        $results = $query->getResultArray();
        return $results;
    }

    public function get_advance_elimination_cl_printed($list_dt, $board_type)
    {
        $builder = $this->db->table('advance_elimination_cl_printed');
        $builder->where('next_dt', $list_dt);
        $builder->where('board_type', $board_type);
        $builder->where('display', 'Y');
        $count = $builder->countAllResults();
        return $count;
    }

    public function save_advance_elimination_print($list_dt, $board_type, $ucode)
    {
        $return = false;
        $data = [
            'next_dt' => $list_dt,
            'board_type' => $board_type,
            'usercode' => $ucode,
            'ent_time' => date('Y-m-d H:i:s')
        ];
        $builder = $this->db->table('advance_elimination_cl_printed');
        if($builder->insert($data)) {
            return true;
        }
        return $return;
    }

    public function getListings($list_dt, $board_type)
    {
        //$list_dt = '2023-03-14';
        $sql = "WITH 
                    a AS (

                        SELECT 
                        row_number() OVER () AS sno, 
                        ct.ent_dt, 
                        dd.doccode1, 
                        a.advocate_id, 
                        submaster_id, 
                        t.diary_no, 
                        m.reg_no_display, 
                        m.pet_name, 
                        m.res_name, 
                        pno, 
                        rno, 
                        m.diary_no_rec_date, 
                        h.subhead, 
                        t.listorder, 
                        l.priority, 
                        h.no_of_time_deleted, 
                        h.coram,
                        tentative_section(t.diary_no::text) as section_name
                        FROM 
                        transfer_old_com_gen_cases t
                        LEFT JOIN main m ON t.diary_no = m.diary_no
                        LEFT JOIN heardt h ON h.diary_no = m.diary_no
                        LEFT JOIN master.listing_purpose l ON l.code = h.listorder
                        LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                        LEFT JOIN conct ct ON m.diary_no = ct.diary_no AND ct.list = 'Y'
                        LEFT JOIN mul_category mc ON mc.diary_no = m.diary_no
                        LEFT JOIN docdetails dd ON dd.diary_no = h.diary_no AND dd.iastat = 'P' AND dd.doccode = 8
                        AND dd.doccode1 IN (40, 41, 48, 49, 71, 72, 118, 131, 211, 309)
                        LEFT JOIN advocate a ON a.diary_no = m.diary_no AND a.advocate_id = 584 AND a.display = 'Y'
                        WHERE 
                        rd.fil_no IS NULL
                        AND m.active_casetype_id NOT IN (9, 10, 25, 26)
                        AND mc.display = 'Y'
                        AND mc.submaster_id NOT IN (911, 912, 914, 239, 240, 241, 242, 243)
                        AND (CASE WHEN h.listorder IN (4, 5) THEN TRUE ELSE h.is_nmd = 'N' END)
                        AND mc.submaster_id NOT IN (
                            343, 15, 16, 17, 18, 19, 20, 21, 22, 23, 341, 353, 157, 158, 159, 160, 161, 162, 163, 166, 175, 176, 322, 222
                        )
                        AND m.c_status = 'P'AND (t.diary_no = t.conn_key OR t.conn_key = 0 OR t.conn_key IS NULL)
                        AND h.subhead NOT IN (801, 817, 818, 819, 820, 848, 849, 850, 854)
                        AND h.mainhead = 'M'
                        AND t.board_type = '$board_type'
                        AND t.next_dt_old = '$list_dt'
                        AND t.listtype = 'F'
                        AND h.listorder > 0
                        GROUP BY m.diary_no, ct.ent_dt, dd.doccode1, a.advocate_id, mc.submaster_id, t.diary_no, h.subhead, t.listorder, l.priority, h.no_of_time_deleted, h.coram

                        UNION 
                            SELECT 
                        row_number() OVER () AS sno,    
                        ct.ent_dt, 
                        dd.doccode1, 
                        a.advocate_id, 
                        submaster_id, 
                        t.diary_no::bigint, 
                        m.reg_no_display, 
                        m.pet_name, 
                        m.res_name, 
                        pno, 
                        rno, 
                        m.diary_no_rec_date, 
                        h.subhead, 
                        aa.listorder, 
                        l.priority, 
                        h.no_of_time_deleted, 
                        h.coram,
                        tentative_section(t.diary_no) as section_name
                        FROM 
                        advanced_drop_note t
                        INNER JOIN advance_allocated aa ON aa.diary_no = t.diary_no AND aa.next_dt = t.cl_date
                        LEFT JOIN main m ON t.diary_no = m.diary_no::text
                        LEFT JOIN heardt h ON h.diary_no = m.diary_no
                        LEFT JOIN master.listing_purpose l ON l.code = h.listorder
                        LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                        LEFT JOIN mul_category mc ON mc.diary_no = m.diary_no
                        LEFT JOIN docdetails dd ON dd.diary_no = h.diary_no AND dd.iastat = 'P' AND dd.doccode = 8
                        AND dd.doccode1 IN (40, 41, 48, 49, 71, 72, 118, 131, 211, 309)
                        LEFT JOIN advocate a ON a.diary_no = m.diary_no AND a.advocate_id = 584 AND a.display = 'Y'
                        LEFT JOIN conct ct ON m.diary_no = ct.diary_no AND ct.list = 'Y'
                        WHERE 
                        rd.fil_no IS NULL
                        AND t.cl_date = '$list_dt'
                        AND t.display = 'Y'
                        AND m.c_status = 'P'
                        GROUP BY m.diary_no, ct.ent_dt, dd.doccode1,  a.advocate_id, mc.submaster_id, t.diary_no, h.subhead, aa.listorder, l.priority, h.no_of_time_deleted, h.coram, h.listorder
                    )
                    SELECT 
                        a.sno, 
                        a.*, 
                        a.section_name
                        FROM a
                        ORDER BY 
                        CASE 
                            WHEN a.subhead IN ('824', '810', '802', '807', '804') 
                                OR a.doccode1 IS NOT NULL 
                                OR a.advocate_id IS NOT NULL 
                                OR a.submaster_id = 173 
                                OR a.listorder IN (4, 5, 7) 
                            THEN 1 
                            ELSE 999 
                        END ASC, 
                        a.priority ASC, 
                        a.no_of_time_deleted DESC, 
                        CASE 
                            WHEN a.coram IS NOT NULL 
                            AND a.coram::bigint != 0 
                            AND TRIM(a.coram) != '' 
                            THEN 1 
                            ELSE 999 
                        END ASC,
                        COALESCE(a.ent_dt, null) ASC,
                        CAST(RIGHT(CAST(a.diary_no AS TEXT), 4) AS INTEGER) DESC,
                        CAST(LEFT(CAST(a.diary_no AS TEXT), LENGTH(CAST(a.diary_no AS TEXT)) - 4) AS INTEGER) ASC";

        $results = $this->db->query($sql)->getResultArray();
        foreach ($results as $index => $result) {
            $results[$index]['sno'] = $index + 1; // Serial number starting from 1
            $advocate = $this->get_advocate_bar($result['diary_no']);
            if (!empty($advocate)) {
                $radvname = $advocate["r_n"];
                $padvname = $advocate["p_n"];
            }
            if ($result['pno'] == 2) {
                $pet_name = $result['pet_name'] . " AND ANR.";
            } else if ($result['pno'] > 2) {
                $pet_name = $result['pet_name'] . " AND ORS.";
            } else {
                $pet_name = $result['pet_name'];
            }
            if ($result['rno'] == 2) {
                $res_name = $result['res_name'] . " AND ANR.";
            } else if ($result['rno'] > 2) {
                $res_name = $result['res_name'] . " AND ORS.";
            } else {
                $res_name = $result['res_name'];
            }

            $results[$index]['radvname'] = !empty($radvname) ? str_replace(",", ", ", trim($radvname, ",")) : '';
            $results[$index]['padvname'] = !empty($padvname) ? str_replace(",", ", ", trim($padvname, ",")) : '';
            $results[$index]['get_pet_name'] = $pet_name;
            $results[$index]['res_name'] = $res_name;

            if (isset($result['diary_no'])  && isset($result['conn_key'])) {
                $old_cases = $this->get_final_transfer_old_cases($result['diary_no'], $list_dt, $board_type);
                $results[$index]['old_cases'] = $old_cases;
                foreach ($old_cases as $old_case_index => $old_case) {
                    $advocate_by_old_cases = $this->get_advocate_bar($old_case['diary_no']);
                    $results[$index]['old_cases'][$old_case_index]['advocate_by_old_cases'] =  $advocate_by_old_cases;
                }
            }
        }
        return $results;
        //return $this->db->query($sql)->getResultArray();
    }
    
    public function getListings_bk($listingDts, $board_type)
    {
        $sql = "SELECT ROW_NUMBER() OVER (ORDER BY
        CASE
            WHEN (sub.subhead = '824' OR sub.subhead = '810' OR sub.subhead = '802' OR sub.subhead = '807' OR sub.subhead = '804' OR sub.doccode1 IS NOT NULL 
            OR sub.submaster_id = 173 OR sub.listorder IN (4, 5, 7)) THEN 1
            ELSE 999
        END ASC,
        sub.priority ASC,
        sub.no_of_time_deleted DESC,
        CASE
            WHEN (sub.coram IS NOT NULL AND NULLIF(TRIM(sub.coram), '') IS NOT NULL AND sub.coram ~ '^[0-9]+$' AND CAST(NULLIF(TRIM(sub.coram), '') AS INTEGER) != 0) THEN 1
            ELSE 999
        END ASC,
        CASE
            WHEN sub.ent_dt IS NOT NULL THEN sub.ent_dt
            ELSE '9999-12-31'::date
        END ASC,
        CAST(RIGHT(sub.diary_no::text, 4) AS INTEGER) DESC,
        CAST(LEFT(sub.diary_no::text, LENGTH(sub.diary_no::text) - 4) AS INTEGER) DESC
    ) AS sno, sub.*, tentative_section(sub.diary_no) AS section_name
    FROM (
        SELECT
            ARRAY_AGG(dd.doccode1) as doccode1,
            submaster_id,
            h.coram,
            h.subhead,
            h.no_of_time_deleted,
            t.diary_no as t_diary_no,
            t.conn_key,
            t.listtype,
            t.board_type,
            t.next_dt_old,
            t.listorder,
                    l.priority,
            ct.ent_dt,
            m.diary_no as diary_no,
            m.reg_no_display,
            m.mf_active,
            m.pet_name,
            m.res_name,
            pno,
            rno,
            m.diary_no_rec_date
        FROM
                transfer_old_com_gen_cases t
            LEFT JOIN main m ON t.diary_no = m.diary_no
            LEFT JOIN heardt h ON h.diary_no = m.diary_no
            LEFT JOIN master.listing_purpose l ON l.code = h.listorder
            LEFT JOIN conct ct ON m.diary_no = ct.diary_no AND ct.list = 'Y'
            LEFT JOIN mul_category mc ON mc.diary_no = m.diary_no
            LEFT JOIN docdetails dd ON dd.diary_no = h.diary_no AND dd.iastat = 'P' AND dd.doccode = 8
            LEFT JOIN advocate a ON a.diary_no = m.diary_no AND a.advocate_id = 584 AND a.display = 'Y'
            WHERE
                mc.display = 'Y'
                AND m.c_status = 'P'
                AND (t.diary_no = t.conn_key OR t.conn_key = 0 OR t.conn_key IS NULL)
                AND h.mainhead = 'M'
                AND t.board_type = '$board_type'
                AND t.next_dt_old = '$listingDts'
                AND t.listtype = 'A'
                AND t.listorder > 0
                AND t.listorder != 32
            GROUP BY
                m.diary_no,submaster_id,h.coram,h.subhead,h.no_of_time_deleted,t.diary_no,t.conn_key,t.listtype,t.board_type,t.next_dt_old,t.listorder,l.priority,ct.ent_dt,m.reg_no_display,m.mf_active,m.pet_name,m.res_name,pno,rno,m.diary_no_rec_date
            HAVING 40 = ANY(ARRAY_AGG(dd.doccode1))
        ) AS sub";
    return $this->db->query($sql)->getResultArray();
    }


    public function get_final_eliminations_bk($diary_no, $list_dt, $board_type)
    {
        $builder2 = $this->db->table('advanced_drop_note t')
            ->select('ct.ent_dt, dd.doccode1, a.advocate_id, submaster_id, t.diary_no, m.reg_no_display, m.pet_name, m.res_name, pno, rno, m.diary_no_rec_date, h.subhead, aa.listorder, l.priority, h.no_of_time_deleted, h.coram')
            ->join('advance_allocated aa', 'aa.diary_no = t.diary_no AND aa.next_dt = t.cl_date', 'inner')
            ->join('main m', 't.diary_no = m.diary_no', 'left')
            ->join('heardt h', 'h.diary_no = m.diary_no', 'left')
            ->join('master.listing_purpose l', 'l.code = h.listorder', 'left')
            ->join('rgo_default rd', 'rd.fil_no = h.diary_no AND rd.remove_def = \'N\'', 'left')
            ->join('mul_category mc', 'mc.diary_no = m.diary_no', 'left')
            ->join('docdetails dd', 'dd.diary_no = h.diary_no AND dd.iastat = \'P\' AND dd.doccode = 8 AND dd.doccode1 IN (40, 41, 48, 49, 71, 72, 118, 131, 211, 309)', 'left')
            ->join('advocate a', 'a.diary_no = m.diary_no AND a.advocate_id = 584 AND a.display = \'Y\'', 'left')
            ->join('conct ct', 'm.diary_no = ct.diary_no AND ct.list = \'Y\'', 'left')
            ->where('rd.fil_no IS NULL')
            ->where('t.cl_date', $list_dt)
            ->where('t.display', 'Y')
            ->where('m.c_status', 'P')
            ->groupBy('m.diary_no,ct.ent_dt, dd.doccode1, a.advocate_id, submaster_id, t.diary_no, m.reg_no_display, m.pet_name, m.res_name, pno, rno, m.diary_no_rec_date, h.subhead, aa.listorder, l.priority, h.no_of_time_deleted, h.coram');

        // Combine the queries with UNION using CodeIgniter's union() method
        $builder1->union($builder2);

        // Execute the final query with ordering
        $finalQuery = $this->db->table("({$builder1->getCompiledSelect()}) a")
            ->select('a.*, tentative_section(a.diary_no) as section_name')
            ->orderBy("(CASE WHEN a.subhead IN ('824', '810', '802', '807', '804') OR a.doccode1 IS NOT NULL OR a.advocate_id IS NOT NULL OR a.submaster_id = 173 OR a.listorder IN (4, 5, 7) THEN 1 ELSE 999 END) ASC")
            ->orderBy('a.priority', 'asc')
            ->orderBy('a.no_of_time_deleted', 'desc')
            ->orderBy("(CASE WHEN a.coram IS NOT NULL AND TRIM(a.coram) IS NOT NULL THEN 1 ELSE 999 END) ASC")
            ->orderBy("(COALESCE(a.ent_dt, '1970-01-01 00:00:00')) ASC")
            ->orderBy("(CAST(RIGHT(CAST(a.diary_no AS TEXT), 4) AS INTEGER)) DESC")
            ->orderBy("(CAST(LEFT(CAST(a.diary_no AS TEXT), LENGTH(CAST(a.diary_no AS TEXT)) - 4) AS INTEGER)) DESC");
        // Fetch the result
        $query = $finalQuery->get();
        $results = $query->getResultArray();

        foreach ($results as $index => $result) {
            $results[$index]['sno'] = $index + 1; // Serial number starting from 1
            $advocate = $this->get_advocate_bar($result['diary_no']);
            if(!empty($advocate)) {
                $radvname = $advocate["r_n"];
                $padvname = $advocate["p_n"];
            }
            if ($result['pno'] == 2) {
                $pet_name = $result['pet_name'] . " AND ANR.";
            } else if ($result['pno'] > 2) {
                $pet_name = $result['pet_name'] . " AND ORS.";
            } else {
                $pet_name = $result['pet_name'];
            }
            if ($result['rno'] == 2) {
                $res_name = $result['res_name'] . " AND ANR.";
            } else if ($result['rno'] > 2) {
                $res_name = $result['res_name'] . " AND ORS.";
            } else {
                $res_name = $result['res_name'];
            }

            $results[$index]['radvname'] = !empty($radvname) ? str_replace(",", ", ", trim($radvname, ",")) : '';
            $results[$index]['padvname'] = !empty($padvname) ? str_replace(",", ", ", trim($padvname, ",")) : '';
            $results[$index]['get_pet_name'] = $pet_name;
            $results[$index]['res_name'] = $res_name;

            if(isset($result['diary_no'])  && isset($result['conn_key'])) {
                $old_cases = $this->get_final_transfer_old_cases($result['diary_no'], $list_dt, $board_type);
                $results[$index]['old_cases'] = $old_cases;
                foreach($old_cases as $old_case_index => $old_case) {
                    $advocate_by_old_cases = $this->get_advocate_bar($old_case['diary_no']);
                    $results[$index]['old_cases'][$old_case_index]['advocate_by_old_cases'] =  $advocate_by_old_cases;
                }
            }

        }
        return $results;
    }

    public function get_final_transfer_old_cases_bk($diary_no, $list_dt, $board_type)
    {
        $builder = $this->db->table('transfer_old_com_gen_cases t')
        ->select("tentative_section(m.diary_no) as section_name, submaster_id, t.diary_no, m.reg_no_display, m.pet_name, m.res_name, pno, rno, m.diary_no_rec_date")
        ->join('main m', 't.diary_no = m.diary_no', 'left')
        ->join('heardt h', 'h.diary_no = m.diary_no', 'left')
        ->join('conct c', "c.conn_key = CAST(m.conn_key AS BIGINT) AND c.list = 'Y'", 'inner')
        ->join('master.listing_purpose l', 'l.code = h.listorder', 'left')
        ->join('rgo_default rd', "rd.fil_no = h.diary_no AND rd.remove_def = 'N'", 'left')
        ->join('mul_category mc', 'mc.diary_no = m.diary_no', 'left')
        ->where('rd.fil_no IS NULL')
        ->whereNotIn('m.active_casetype_id', [9, 10, 25, 26])
        ->where('mc.display', 'Y')
        ->whereNotIn('mc.submaster_id', [911, 912, 914, 239, 240, 241, 242, 243])
        ->groupStart() // Group for case/when conditional
            ->whereIn('h.listorder', [4, 5])
            ->orGroupStart()
                ->where('h.is_nmd', 'N')
                ->whereNotIn('mc.submaster_id', [343, 15, 16, 17, 18, 19, 20, 21, 22, 23, 341, 353, 157, 158, 159, 160, 161, 162, 163, 166, 175, 176, 322, 222])
            ->groupEnd()
        ->groupEnd() // End of case/when conditional
        ->where('m.c_status', 'P')
        ->where('t.conn_key', $diary_no)
        ->where('t.diary_no != t.conn_key')
        ->whereNotIn('h.subhead', [801, 817, 818, 819, 820, 848, 849, 850, 854])
        ->where('h.mainhead', 'M')
        ->where('t.board_type', $board_type)
        ->where('t.next_dt_old', $list_dt)
        ->where('t.listtype', 'F')
        ->where('h.listorder >', 0)
        ->groupBy('m.diary_no, mc.submaster_id, h.coram, t.diary_no, t.next_dt_old, t.next_dt_new, t.tentative_cl_dt_old,t.tentative_cl_dt_new, t.listorder, t.conn_key,t.ent_dt,t.test2, t.listorder_new,t.board_type,t.listtype, t.reason')
        ->orderBy("(CAST(RIGHT(CAST(m.diary_no AS TEXT), 4) AS INTEGER)) DESC")
        ->orderBy("(CAST(LEFT(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER)) DESC");
        $query = $builder->get();
        $results = $query->getResultArray();
        return $results;
    }

    public function list_no_from_final($list_dt, $board_type)
    {
        $year = date('Y', strtotime($list_dt));
        $builder = $this->db->table('final_elimination_cl_printed')
            ->select('COUNT(next_dt) AS advance_list_no')
            ->where("EXTRACT(YEAR FROM next_dt) =", $year, false)
            ->where('next_dt !=', $list_dt)
            ->where('board_type', $board_type);
        $query = $builder->get();
        $results = $query->getRowArray();
        return $results;
    }

    public function get_final_elimination_cl_printed($list_dt, $board_type)
    {
        $builder = $this->db->table('final_elimination_cl_printed');
        $builder->where('next_dt', $list_dt);
        $builder->where('board_type', $board_type);
        $builder->where('display', 'Y');
        $count = $builder->countAllResults();
        return $count;
    }

    public function get_cl_elimination_json()
    {
        $json_list_dt_board_type =  session()->get('json_list_dt_board_type');
        $json_elimination_board_type =  session()->get('json_elimination_board_type');
        if (isset($json_list_dt_board_type)) {
            $list_dt = $json_list_dt_board_type;
        } else {
            return $this->response->setJSON(['status' => 'success', 'message' => "Error: 'list_dt' not found in session."]);
        }
        if (isset($json_elimination_board_type)) {
            $board_type = $json_elimination_board_type;
        } else {
            return $this->response->setJSON(['status' => 'success', 'message' => "Error: 'board_type' not found in session."]);
        }
        if(!empty($list_dt) && !empty($board_type)) {
            $isExist = $this->sc_working_days($list_dt);
            $nmd_note = "";
            if($isExist){
                $nmd_note = "NMD ";
            }
            $advance_list_no = 1;
            $get_advance_list_no = $this->advance_list_no($list_dt, $board_type);
            $advance_list_no = !empty($get_advance_list_no) ? $get_advance_list_no['advance_list_no'] + 1 : 1;

            $advance_list_noo =  $nmd_note . " ADVANCE ELIMINATION LIST - AL/$advance_list_no/" . date('Y', strtotime($list_dt)) . "<br/><br/>";
            $elimination_content = 'THE FOLLOWING MATTERS NOTED FOR BEING LISTED ON ' . date('d-m-Y', strtotime($list_dt)) . ' HAVE BEEN ELIMINATED FROM THE ADVANCE LIST DUE TO EXCESS MATTERS/COMPELLING REASON.';

            $this->advance_eliminations_for_json($list_dt, $board_type, $advance_list_noo, $elimination_content);
            return true;
        }
    }

    public function advance_eliminations_for_json($list_dt, $board_type, $advance_list_noo, $elimination_content){
        $builder = $this->db->table('transfer_old_com_gen_cases t');
        // Select necessary columns
        $builder->select('dd.doccode1, a.advocate_id, pno, rno, 
            submaster_id, h.coram, t.*, m.reg_no_display, 
            m.pet_name, m.res_name, 
            CONCAT(m.pet_name_hindi, \' विरूद्ध \', m.res_name_hindi) AS hi_cause_title, 
            m.active_fil_no, m.active_reg_year, ct2.casename_hindi as casename_hindi, 
            h.brd_slno, m.mf_active, m.diary_no_rec_date, tentative_section(m.diary_no) as section_name');

        // Join related tables
        $builder->join('main m', 't.diary_no = m.diary_no', 'left');
        $builder->join('heardt h', 'h.diary_no = m.diary_no', 'left');
        $builder->join('master.listing_purpose l', 'l.code = h.listorder', 'left');
        $builder->join('mul_category mc', 'mc.diary_no = m.diary_no', 'left');
        $builder->join('docdetails dd', "dd.diary_no = h.diary_no AND dd.iastat = 'P' AND dd.doccode = 8 AND dd.doccode1 IN (40,41,48,49,71,72,118,131,211,309)", 'left');
        $builder->join('advocate a', "a.diary_no = m.diary_no AND a.advocate_id = 584 AND a.display = 'Y'", 'left');
        $builder->join('conct ct', "m.diary_no = ct.diary_no AND ct.list = 'Y'", 'left');
        $builder->join('master.casetype ct2', "m.active_casetype_id = ct2.casecode", 'left');

        // Apply the WHERE conditions
        $builder->where('mc.display', 'Y');
        $builder->where('m.c_status', 'P');
        $builder->groupStart()
                ->where('t.diary_no = t.conn_key')
                ->orWhere('t.conn_key', 0)
                ->orWhere('t.conn_key IS NULL')
                ->groupEnd();
        $builder->where('h.mainhead', 'M');
        $builder->where('t.board_type', $board_type);
        $builder->where('t.next_dt_old', $list_dt);
        $builder->where('t.listtype', 'A');
        $builder->where('t.listorder >', 0);
        $builder->where('t.listorder !=', 32);

        // Order by conditions with CASE/IF-like logic using direct conditions
        $builder->orderBy("(CASE WHEN h.subhead IN ('824', '810', '802', '807', '804') OR dd.doccode1 IS NOT NULL OR a.advocate_id IS NOT NULL OR mc.submaster_id = 173 OR t.listorder IN (4, 5, 7) THEN 1 ELSE 999 END) ASC");
        $builder->orderBy('l.priority', 'asc');
        $builder->orderBy('h.no_of_time_deleted', 'desc');

        $builder->orderBy("(CASE WHEN h.coram IS NOT NULL AND TRIM(h.coram) IS NOT NULL THEN 1 ELSE 999 END) DESC");
        $builder->orderBy("(COALESCE(ct.ent_dt, '1970-01-01 00:00:00')) ASC");
        $builder->orderBy("(CAST(RIGHT(CAST(m.diary_no AS TEXT), 4) AS INTEGER)) DESC");
        $builder->orderBy("(CAST(LEFT(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER)) DESC");

        // Execute the query
        $query = $builder->get();
        $results = $query->getResultArray();
        $radvname = $padvname = '';
        foreach ($results as $index => $result) {
            $psrno = $index + 1;;
            $diary_no = $result['diary_no'];
            $last_4_digits_yr = substr($diary_no, -4);
            $remaining_digits_dno = substr($diary_no, 0, -4);
            $m_f_filno = $result['active_fil_no'];
            $m_f_fil_yr = $result['active_reg_year'];
            $filno_array = explode("-", $m_f_filno);

            if ($filno_array[1] == $filno_array[2]) {
                $fil_no_print = ltrim($filno_array[1], '0');
            }
            $fil_no_print = ltrim($filno_array[1], '0');
            if (!empty($filno_array[2])) {
                $fil_no_print .= '-' . ltrim($filno_array[2], '0');
            }
            $fil_no_print = ltrim($filno_array[1], '0');
            $comlete_fil_no_prt = $result['casename_hindi'] . ' ' . $fil_no_print . "/" . $m_f_fil_yr;
            if (empty($row['reg_no_display'])) {
                $comlete_fil_no_prt = "";
            }

            $psrno_conc = "1";
            $connected_cases = [];
            if ($result['pno'] == 2) {
                $pet_name = $result['pet_name'] . " AND ANR.";
            } else if ($result['pno'] > 2) {
                $pet_name = $result['pet_name'] . " AND ORS.";
            } else {
                $pet_name = $result['pet_name'];
            }
            if ($result['rno'] == 2) {
                $res_name = $result['res_name'] . " AND ANR.";
            } else if ($result['rno'] > 2) {
                $res_name = $result['res_name'] . " AND ORS.";
            } else {
                $res_name = $result['res_name'];
            }

            $old_cases = $this->transfer_old_cases_for_json($diary_no, $list_dt, $board_type);
            $remarks = $this->get_remark($result['diary_no']);
            $remark = isset($remarks['remark']) ? $remarks['remark'] : '';

            $advocate_by_old_cases = $this->get_advocate_for_json($result['diary_no']);
            if(!empty($advocate_by_old_cases)) {
                $radvname = strtoupper($advocate_by_old_cases["r_n"]);
                $padvname = strtoupper($advocate_by_old_cases["p_n"]);
                $impldname = strtoupper($advocate_by_old_cases["i_n"]);
                $intervenorname = strtoupper($advocate_by_old_cases["intervenor"]);
                $radvname_h = strtoupper($advocate_by_old_cases["r_n_h"]);
                $padvname_h = strtoupper($advocate_by_old_cases["p_n_h"]);
                $impldname_h = strtoupper($advocate_by_old_cases["i_n_h"]);
                $intervenorname_h = strtoupper($advocate_by_old_cases["intervenor_h"]);
                $mergedNames = strtoupper($advocate_by_old_cases["r_n"])  . " " . strtoupper($advocate_by_old_cases["i_n"]) . " " . strtoupper($advocate_by_old_cases["intervenor"]);
                $mergedNames_h = strtoupper($advocate_by_old_cases["r_n_h"])  . " " . strtoupper($advocate_by_old_cases["i_n_h"]) . " " . strtoupper($advocate_by_old_cases["intervenor_h"]);
            }
            $case_no = empty($result['reg_no_display']) ? 'Diary No. ' . $diary_no : str_replace('"', '\"', $result['reg_no_display']);
            $case_no_h = empty($comlete_fil_no_prt) ? 'डायरी नंबर ' . $diary_no : str_replace('"', '\"', $comlete_fil_no_prt);

            foreach($old_cases as $old_case_index => $row2) {
                $diary_no = $row2['diary_no'];
                $last_4_digits_yr = substr($diary_no, -4);
                $remaining_digits_dno = substr($diary_no, 0, -4);
                $m_f_filno = $row2['active_fil_no'];
                $m_f_fil_yr = $row2['active_reg_year'];
                $filno_array = explode("-", $m_f_filno);
                if ($filno_array[1] == $filno_array[2]) {
                    $fil_no_print = ltrim($filno_array[1], '0');
                } else{
                    if ($filno_array[2] != '') {
                        $fil_no_print = ltrim($filno_array[1], '0').'-'.ltrim($filno_array[2], '0');
                    } else{
                        $fil_no_print = ltrim($filno_array[1], '0');
                    }
                }

                $comlete_fil_no_prt_h = $row2['casename_hindi'] . ' ' . $fil_no_print . "/" . $m_f_fil_yr;
                if (empty($row2['reg_no_display'])) {
                    $comlete_fil_no_prt_h = "";
                }

                $case_no2 = empty($row2['reg_no_display']) ? 'Diary No. ' . $diary_no : str_replace('"', '\"', $row2['reg_no_display']);
                $case_no_h_2 = empty($comlete_fil_no_prt_h) ? 'डायरी नंबर ' . $diary_no : str_replace('"', '\"', $comlete_fil_no_prt_h);
                if ($row2['pno'] == 2) {
                    $pet_name = $row2['pet_name'] . " AND ANR.";
                } else if ($row2['pno'] > 2) {
                    $pet_name = $row2['pet_name'] . " AND ORS.";
                } else {
                    $pet_name = $row2['pet_name'];
                }
                if ($row2['rno'] == 2) {
                    $res_name = $row2['res_name'] . " AND ANR.";
                } else if ($row2['rno'] > 2) {
                    $res_name = $row2['res_name'] . " AND ORS.";
                } else {
                    $res_name = $row2['res_name'];
                }

                $advocate_by_old_cases = $this->get_advocate_for_json($row2['diary_no']);
                if(!empty($advocate_by_old_cases)) {
                    $radvname = strtoupper($advocate_by_old_cases["r_n"]);
                    $padvname = strtoupper($advocate_by_old_cases["p_n"]);
                    $impldname = strtoupper($advocate_by_old_cases["i_n"]);
                    $intervenorname = strtoupper($advocate_by_old_cases["intervenor"]);
                    $radvname_h = strtoupper($advocate_by_old_cases["r_n_h"]);
                    $padvname_h = strtoupper($advocate_by_old_cases["p_n_h"]);
                    $impldname_h = strtoupper($advocate_by_old_cases["i_n_h"]);
                    $intervenorname_h = strtoupper($advocate_by_old_cases["intervenor_h"]);
                    $mergedNames = strtoupper($advocate_by_old_cases["r_n"])  . " " . strtoupper($advocate_by_old_cases["i_n"]) . " " . strtoupper($advocate_by_old_cases["intervenor"]);
                    $mergedNames_h = strtoupper($advocate_by_old_cases["r_n_h"])  . " " . strtoupper($advocate_by_old_cases["i_n_h"]) . " " . strtoupper($advocate_by_old_cases["intervenor_h"]);
                }


                $connected_case = array(
                    "list_mainhead" => "Advance Elimination List",
                    "hi_list_mainhead" => "अग्रिम उन्मूलन सूची",
                    "list_mainhead_id" => "8",
                    "elimination_content" => $elimination_content,
                    "diary_no" => $diary_no,
                    "dno" => $remaining_digits_dno,
                    "dyr" => $last_4_digits_yr,
                    "case_no" =>  str_replace('"', '\"', $case_no2),
                    "hi_case_no" => str_replace('"', '\"', $case_no_h_2),
                    "next_dt" => $list_dt,
                    "connected_item_no" =>  $psrno . '.' . $psrno_conc++,
                    "Advance_list_no" =>  str_replace('"', '\"', $advance_list_noo),
                    "hi_Advance_list_no" =>  str_replace('"', '\"', 'hi_' . $advance_list_noo),
                    "cause_title" => str_replace('"', '\"',  $pet_name . ' Versus ' . $res_name),
                    "hi_cause_title" => str_replace('"', '\"', $row2['hi_cause_title']),
                    "petitioner_Advocate" => str_replace('"', '\"', $padvname),
                    "hi_petitioner_Advocate" => str_replace('"', '\"', $padvname_h),
                    "respondent_advocate" => str_replace('"', '\"', $mergedNames),
                    "hi_respondent_advocate" => str_replace('"', '\"', $mergedNames_h),
                );
                $connected_cases[] = $connected_case;

            }

            $purpose_data = '';
            $case_data = array(
                "list_mainhead" => "Advance Elimination List",
                "hi_list_mainhead" => "अग्रिम उन्मूलन सूची",
                "elimination_content" => $elimination_content,
                "list_mainhead_id" => "8",
                "diary_no" => $diary_no,
                "dno" => $remaining_digits_dno,
                "dyr" => $last_4_digits_yr,
                "case_no" => str_replace('"', '\"', $case_no),
                "hi_case_no" => str_replace('"', '\"', $case_no_h),
                "next_dt" => $list_dt,
                "item_no" => $psrno,
                "Advance_list_no" => str_replace('"', '\"', $advance_list_noo),
                "hi_Advance_list_no" =>  str_replace('"', '\"', 'hi_' . $advance_list_noo),
                "cause_title" => str_replace('"', '\"',  $pet_name . ' Versus ' . $res_name),
                "hi_cause_title" => str_replace('"', '\"',  $result['hi_cause_title']),
                "purpose" => str_replace('"', '\"', $purpose_data),
                "petitioner_Advocate" => str_replace('"', '\"', $padvname),
                "hi_petitioner_Advocate" => str_replace('"', '\"', $padvname_h),
                "respondent_advocate" => str_replace('"', '\"', $mergedNames),
                "hi_respondent_advocate" => str_replace('"', '\"', $mergedNames_h),
                "connected_cases" => $connected_cases
            );

            $data[] = $case_data;
        }

        $mainhead = "M";
        $file_path = $mainhead . "_" . $board_type;
        $path_dir = WRITEPATH . "judgment/cl/advance_elimination/$list_dt/";
        $path = $path_dir;
        $filePath = $path . $file_path . ".json";
        $json_result = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($filePath, $json_result);
        return true;
    }


    public function transfer_old_cases_for_json($diary_no, $list_dt, $board_type)
    {
        $builder = $this->db->table('transfer_old_com_gen_cases t')
        ->select("tentative_section(m.diary_no) as section_name, submaster_id, h.coram, t.*, m.reg_no_display,
                     m.active_fil_no, m.active_reg_year,
                     ct2.casename_hindi as casename_hindi,
                     CONCAT(m.pet_name_hindi, ' विरूद्ध ', m.res_name_hindi) AS hi_cause_title,
                     m.mf_active,m.pet_name, m.res_name, pno, rno, m.diary_no_rec_date")
        ->join('main m', 't.diary_no = m.diary_no', 'left')
        ->join('heardt h', 'h.diary_no = m.diary_no', 'left')
        ->join('conct c', "c.conn_key = CAST(m.conn_key AS BIGINT) AND c.list = 'Y'", 'inner')
        ->join('master.listing_purpose l', 'l.code = h.listorder', 'left')
        ->join('rgo_default rd', "rd.fil_no = h.diary_no AND rd.remove_def = 'N'", 'left')
        ->join('mul_category mc', 'mc.diary_no = m.diary_no', 'left')
        ->join('master.casetype ct2', "m.active_casetype_id = ct2.casecode", 'left')
        ->where('rd.fil_no IS NULL')
        ->whereNotIn('m.active_casetype_id', [9, 10, 25, 26])
        ->where('mc.display', 'Y')
        ->whereNotIn('mc.submaster_id', [911, 912, 914, 239, 240, 241, 242, 243])
        ->groupStart() // Group for case/when conditional
            ->whereIn('h.listorder', [4, 5])
            ->orGroupStart()
                ->where('h.is_nmd', 'N')
                ->whereNotIn('mc.submaster_id', [343, 15, 16, 17, 18, 19, 20, 21, 22, 23, 341, 353, 157, 158, 159, 160, 161, 162, 163, 166, 175, 176, 322, 222])
            ->groupEnd()
        ->groupEnd() // End of case/when conditional
        ->where('m.c_status', 'P')
        ->where('t.conn_key', $diary_no)
        ->where('t.diary_no != t.conn_key')
        ->whereNotIn('h.subhead', [801, 817, 818, 819, 820, 848, 849, 850, 854])
        ->where('h.mainhead', 'M')
        ->where('t.board_type', $board_type)
        ->where('t.next_dt_old', $list_dt)
        ->where('t.listtype', 'A')
        ->where('h.listorder >', 0)
        ->where('h.listorder !=', 32)
        ->groupBy('m.diary_no, mc.submaster_id, h.coram, t.diary_no, t.next_dt_old, t.next_dt_new, t.tentative_cl_dt_old,t.tentative_cl_dt_new, t.listorder, t.conn_key,t.ent_dt,t.test2, t.listorder_new,t.board_type,t.listtype, t.reason, ct2.casename_hindi')
        ->orderBy("(CAST(RIGHT(CAST(m.diary_no AS TEXT), 4) AS INTEGER)) DESC")
        ->orderBy("(CAST(LEFT(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER)) DESC");
        $query = $builder->get();
        $results = $query->getResultArray();
        return $results;
    }


    public function get_remark($diary_no)
    {
        $builder = $this->db->table('brdrem');
        $builder->select('remark')
                ->where('diary_no', $diary_no);
        $query = $builder->get();
        $result = $query->getRowArray();
        return $result;
    }

    public function get_advocate_for_json($diary_no)
    {
        $sql = "SELECT a.*, 
                STRING_AGG(a.name || COALESCE((CASE WHEN pet_res = 'R' THEN grp_adv END), ''), '' ORDER BY adv_type DESC, pet_res_no ASC) AS r_n,
                STRING_AGG(a.name || COALESCE((CASE WHEN pet_res = 'P' THEN grp_adv END), ''), '' ORDER BY adv_type DESC, pet_res_no ASC) AS p_n,
                STRING_AGG(a.name || COALESCE((CASE WHEN pet_res = 'I' THEN grp_adv END), ''), '' ORDER BY adv_type DESC, pet_res_no ASC) AS i_n,
                STRING_AGG(a.name || COALESCE((CASE WHEN pet_res = 'N' THEN grp_adv END), ''), '' ORDER BY adv_type DESC, pet_res_no ASC) AS intervenor,
                STRING_AGG(a.name_hindi || COALESCE((CASE WHEN pet_res = 'R' THEN grp_adv END), ''), '' ORDER BY adv_type DESC, pet_res_no ASC) AS r_n_h,
                STRING_AGG(a.name_hindi || COALESCE((CASE WHEN pet_res = 'P' THEN grp_adv END), ''), '' ORDER BY adv_type DESC, pet_res_no ASC) AS p_n_h,
                STRING_AGG(a.name_hindi || COALESCE((CASE WHEN pet_res = 'I' THEN grp_adv END), ''), '' ORDER BY adv_type DESC, pet_res_no ASC) AS i_n_h,
                STRING_AGG(a.name_hindi || COALESCE((CASE WHEN pet_res = 'N' THEN grp_adv END), ''), '' ORDER BY adv_type DESC, pet_res_no ASC) AS intervenor_h
            FROM 
            (
                SELECT a.diary_no, b.name, b.mobile, b.name_hindi,
                    STRING_AGG(COALESCE(a.adv, ''), '' ORDER BY CASE WHEN pet_res IN ('I', 'N') THEN 99 ELSE 0 END ASC, adv_type DESC, pet_res_no ASC) AS grp_adv,
                    a.pet_res, a.adv_type, pet_res_no
                FROM advocate a
                LEFT JOIN master.bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y'
                WHERE a.diary_no = ? AND a.display = 'Y'
                GROUP BY a.diary_no, b.name, b.mobile, b.name_hindi,a.pet_res, a.adv_type, a.pet_res_no
                ORDER BY CASE WHEN pet_res IN ('I', 'N') THEN 99 ELSE 0 END ASC, adv_type DESC, pet_res_no ASC
            ) a 
            GROUP BY a.diary_no, a.name, a.mobile, a.name_hindi, a.grp_adv, a.pet_res, a.adv_type, a.pet_res_no
        ";
        $query = $this->db->query($sql, [$diary_no]);
        $results = $query->getRowArray();
        return $results;
    }

    public function save_final_elimination_print($list_dt, $board_type, $ucode)
    {
        $return = false;
        $data = [
            'next_dt' => $list_dt,
            'board_type' => $board_type,
            'usercode' => $ucode,
            'ent_time' => date('Y-m-d H:i:s')
        ];
        $builder = $this->db->table('final_elimination_cl_printed');
        if($builder->insert($data)) {
            return true;
        }
        return $return;
    }

    public function cl_final_elimination_json()
    {
        $final_eleimnation_lst_dt =  session()->get('final_eleimnation_lst_dt');
        $final_eleimnation_board_type =  session()->get('final_eleimnation_board_type');
        if (isset($final_eleimnation_lst_dt)) {
            $list_dt = $final_eleimnation_lst_dt;
        } else {
            return $this->response->setJSON(['status' => 'success', 'message' => "Error: 'list_dt' not found in session."]);
        }
        if (isset($final_eleimnation_board_type)) {
            $board_type = $final_eleimnation_board_type;
        } else {
            return $this->response->setJSON(['status' => 'success', 'message' => "Error: 'board_type' not found in session."]);
        }
        if(!empty($list_dt) && !empty($board_type)) {

            $final_elimination_content = "THE FOLLOWING MATTERS NOTED FOR BEING LISTED ON " . date('d-m-Y', strtotime($list_dt)) . " HAVE BEEN ELIMINATED FROM THE ADVANCE LIST DUE TO EXCESS MATTERS/COMPELLING REASON.";
            $isExist = $this->sc_working_days($list_dt);
            $nmd_note = "";
            if($isExist){
                $nmd_note = "NMD ";
            }
            $advance_list_no = 1;
            $get_advance_list_no = $this->list_no_from_final($list_dt, $board_type);
            $advance_list_no = !empty($get_advance_list_no) ? $get_advance_list_no['advance_list_no'] + 1 : 1;

            $final_content =  $nmd_note." FINAL ELIMINATION LIST - AL/$advance_list_no/". date('Y', strtotime($list_dt));

            $this->final_eliminations_for_json($list_dt, $board_type, $final_content, $final_elimination_content);
            return true;
        }
    }

    public function final_eliminations_for_json($list_dt, $board_type, $final_content, $final_elimination_content)
    {
        $builder1 = $this->db->table('transfer_old_com_gen_cases t')
        ->select('ct.ent_dt, dd.doccode1, a.advocate_id, submaster_id, t.diary_no, m.reg_no_display, m.pet_name, m.res_name, pno, rno, m.diary_no_rec_date, h.subhead, t.listorder, l.priority,
        m.active_fil_no, m.active_reg_year, ct2.casename_hindi as casename_hindi,
        CONCAT(m.pet_name_hindi, \' विरूद्ध \', m.res_name_hindi) AS hi_cause_title,
        h.no_of_time_deleted, h.coram')
        ->join('main m', 't.diary_no = m.diary_no', 'left')
        ->join('heardt h', 'h.diary_no = m.diary_no', 'left')
        ->join('master.listing_purpose l', 'l.code = h.listorder', 'left')
        ->join('rgo_default rd', 'rd.fil_no = h.diary_no AND rd.remove_def = \'N\'', 'left')
        ->join('conct ct', 'm.diary_no = ct.diary_no AND ct.list = \'Y\'', 'left')
        ->join('mul_category mc', 'mc.diary_no = m.diary_no', 'left')
        ->join('docdetails dd', 'dd.diary_no = h.diary_no AND dd.iastat = \'P\' AND dd.doccode = 8 AND dd.doccode1 IN (40,41,48,49,71,72,118,131,211,309)', 'left')
        ->join('advocate a', 'a.diary_no = m.diary_no AND a.advocate_id = 584 AND a.display = \'Y\'', 'left')
        ->join('master.casetype ct2', "m.active_casetype_id = ct2.casecode", 'left')
        ->where('rd.fil_no IS NULL')
        ->whereNotIn('m.active_casetype_id', [9, 10, 25, 26])
        ->where('mc.display', 'Y')
        ->whereNotIn('mc.submaster_id', [911, 912, 914, 239, 240, 241, 242, 243])
        ->groupStart()
            ->whereIn('h.listorder', [4, 5])
            ->orGroupStart()
                ->where('h.is_nmd', 'N')
                ->whereNotIn('mc.submaster_id', [343, 15, 16, 17, 18, 19, 20, 21, 22, 23, 341, 353, 157, 158, 159, 160, 161, 162, 163, 166, 175, 176, 322, 222])
            ->groupEnd()
        ->groupEnd()
        ->where('m.c_status', 'P')
        ->groupStart()
            ->where('t.diary_no = t.conn_key', null, false)
            ->orWhere('t.conn_key', 0)
            ->orWhere('t.conn_key IS NULL')
        ->groupEnd()
        ->whereNotIn('h.subhead', [801, 817, 818, 819, 820, 848, 849, 850, 854])
        ->where('h.mainhead', 'M')
        ->where('t.board_type', $board_type)
        ->where('t.next_dt_old', $list_dt)
        ->where('t.listtype', 'F')
        ->where('h.listorder >', 0)
        ->groupBy('m.diary_no, ct.ent_dt, dd.doccode1, a.advocate_id, submaster_id, t.diary_no, m.reg_no_display, m.pet_name, m.res_name, pno, rno, m.diary_no_rec_date, h.subhead, t.listorder, l.priority, h.no_of_time_deleted, h.coram, 
        ct2.casename_hindi');

        // Build the second part of the union query
        $builder2 = $this->db->table('advanced_drop_note t')
            ->select('ct.ent_dt, dd.doccode1, a.advocate_id, submaster_id, t.diary_no, m.reg_no_display, m.pet_name, m.res_name, pno, rno, m.diary_no_rec_date, h.subhead, aa.listorder, l.priority,
            m.active_fil_no, m.active_reg_year, ct2.casename_hindi as casename_hindi,
            CONCAT(m.pet_name_hindi, \' विरूद्ध \', m.res_name_hindi) AS hi_cause_title,
            h.no_of_time_deleted, h.coram')
            ->join('advance_allocated aa', 'aa.diary_no = t.diary_no AND aa.next_dt = t.cl_date', 'inner')
            ->join('main m', 't.diary_no = m.diary_no', 'left')
            ->join('heardt h', 'h.diary_no = m.diary_no', 'left')
            ->join('master.listing_purpose l', 'l.code = h.listorder', 'left')
            ->join('rgo_default rd', 'rd.fil_no = h.diary_no AND rd.remove_def = \'N\'', 'left')
            ->join('mul_category mc', 'mc.diary_no = m.diary_no', 'left')
            ->join('docdetails dd', 'dd.diary_no = h.diary_no AND dd.iastat = \'P\' AND dd.doccode = 8 AND dd.doccode1 IN (40, 41, 48, 49, 71, 72, 118, 131, 211, 309)', 'left')
            ->join('advocate a', 'a.diary_no = m.diary_no AND a.advocate_id = 584 AND a.display = \'Y\'', 'left')
            ->join('conct ct', 'm.diary_no = ct.diary_no AND ct.list = \'Y\'', 'left')
            ->join('master.casetype ct2', "m.active_casetype_id = ct2.casecode", 'left')
            ->where('rd.fil_no IS NULL')
            ->where('t.cl_date', $list_dt)
            ->where('t.display', 'Y')
            ->where('m.c_status', 'P')
            ->groupBy('m.diary_no,ct.ent_dt, dd.doccode1, a.advocate_id, submaster_id, t.diary_no, m.reg_no_display, m.pet_name, m.res_name, pno, rno, m.diary_no_rec_date, h.subhead, aa.listorder, l.priority, h.no_of_time_deleted, h.coram,
            ct2.casename_hindi');

        // Combine the queries with UNION using CodeIgniter's union() method
        $builder1->union($builder2);

        // Execute the final query with ordering
        $finalQuery = $this->db->table("({$builder1->getCompiledSelect()}) a")
            ->select('a.*, tentative_section(a.diary_no) as section_name')
            ->orderBy("(CASE WHEN a.subhead IN ('824', '810', '802', '807', '804') OR a.doccode1 IS NOT NULL OR a.advocate_id IS NOT NULL OR a.submaster_id = 173 OR a.listorder IN (4, 5, 7) THEN 1 ELSE 999 END) ASC")
            ->orderBy('a.priority', 'asc')
            ->orderBy('a.no_of_time_deleted', 'desc')
            ->orderBy("(CASE WHEN a.coram IS NOT NULL AND TRIM(a.coram) IS NOT NULL THEN 1 ELSE 999 END) ASC")
            ->orderBy("(COALESCE(a.ent_dt, '1970-01-01 00:00:00')) ASC")
            ->orderBy("(CAST(RIGHT(CAST(a.diary_no AS TEXT), 4) AS INTEGER)) DESC")
            ->orderBy("(CAST(LEFT(CAST(a.diary_no AS TEXT), LENGTH(CAST(a.diary_no AS TEXT)) - 4) AS INTEGER)) DESC");
        // Fetch the result
        $query = $finalQuery->get();
        $results = $query->getResultArray();

        $radvname = $padvname = '';
        foreach ($results as $index => $result) {
            $diary_no = $result['diary_no'];
            $last_4_digits_yr = substr($diary_no, -4);
            $remaining_digits_dno = substr($diary_no, 0, -4);
            $psrno = $index + 1;
            $section = $result['section_name'];
            $m_f_filno = $result['active_fil_no'];
            $m_f_fil_yr = $result['active_reg_year'];
            $filno_array = explode("-", $m_f_filno);

            if ($filno_array[1] == $filno_array[2]) {
                $fil_no_print = ltrim($filno_array[1], '0');
            }
            else{
                if ($filno_array[2] != '') {
                    $fil_no_print = ltrim($filno_array[1], '0').'-'.ltrim($filno_array[2], '0');
                }
                else{
                    $fil_no_print = ltrim($filno_array[1], '0');
                }
            }
        
            $comlete_fil_no_prt_h = $result['casename_hindi'] . ' ' . $fil_no_print . "/" . $m_f_fil_yr;
            if (empty($result['reg_no_display'])) {
                $comlete_fil_no_prt_h = "";
            }

            
            $connected_cases = [];
            if ($result['pno'] == 2) {
                $pet_name = $result['pet_name'] . " AND ANR.";
            } else if ($result['pno'] > 2) {
                $pet_name = $result['pet_name'] . " AND ORS.";
            } else {
                $pet_name = $result['pet_name'];
            }
            if ($result['rno'] == 2) {
                $res_name = $result['res_name'] . " AND ANR.";
            } else if ($result['rno'] > 2) {
                $res_name = $result['res_name'] . " AND ORS.";
            } else {
                $res_name = $result['res_name'];
            }
            
            //$old_cases = $this->transfer_old_cases_for_json($diary_no, $list_dt, $board_type);
            $remarks = $this->get_remark($result['diary_no']);
            $remark = isset($remarks['remark']) ? $remarks['remark'] : '';


            $advocate_details = $this->final_advocate_for_json($result['diary_no']);
            $aor_details = [];
            foreach($advocate_details as $aor_row) {
                $aor_details[] = array(
                    "pet_res_no" => str_replace('"', '\"', $aor_row["pet_res_no"] ?? ''),
                    "adv" => str_replace('"', '\"', $aor_row["adv"] ?? ''),
                    "pet_res" => str_replace('"', '\"', $aor_row["pet_res"] ?? ''),
                    "aor_name" => str_replace('"', '\"', $aor_row["aor_name"] ?? ''),
                    "aor_code" => str_replace('"', '\"', $aor_row["aor_code"] ?? ''),
                    "hi_adv" => str_replace('"', '\"', 'hi_' . ($aor_row["adv"] ?? '')),
                    "hi_pet_res" => str_replace('"', '\"', $aor_row["pet_res"] ?? ''),
                    "hi_aor_name" => str_replace('"', '\"', $aor_row["aor_name_hindi"] ?? '')
                );
            }

            $purpose_details = $this->get_purpose_for_json($result['diary_no']);
            $purpose = isset($purpose_details["purpose"]) ? $purpose_details["purpose"] : '';
            $hi_purpose = isset($purpose_details["purpose_hindi"]) ? $purpose_details["purpose_hindi"] : '';
            $advocate_by_old_cases = $this->get_advocate_for_json($result['diary_no']);
            if(!empty($advocate_by_old_cases)) {
                $radvname = strtoupper($advocate_by_old_cases["r_n"]);
                $padvname = strtoupper($advocate_by_old_cases["p_n"]);
                $impldname = strtoupper($advocate_by_old_cases["i_n"]);
                $intervenorname = strtoupper($advocate_by_old_cases["intervenor"]);
                $radvname_h = strtoupper($advocate_by_old_cases["r_n_h"]);
                $padvname_h = strtoupper($advocate_by_old_cases["p_n_h"]);
                $impldname_h = strtoupper($advocate_by_old_cases["i_n_h"]);
                $intervenorname_h = strtoupper($advocate_by_old_cases["intervenor_h"]);
                $mergedNames = strtoupper($advocate_by_old_cases["r_n"])  . " " . strtoupper($advocate_by_old_cases["i_n"]) . " " . strtoupper($advocate_by_old_cases["intervenor"]);
                $mergedNames_h = strtoupper($advocate_by_old_cases["r_n_h"])  . " " . strtoupper($advocate_by_old_cases["i_n_h"]) . " " . strtoupper($advocate_by_old_cases["intervenor_h"]);
            }
            $case_no = empty($result['reg_no_display']) ? 'Diary No. ' . $diary_no : str_replace('"', '\"', $result['reg_no_display']);
            $case_no_h_2 = empty($comlete_fil_no_prt_h) ? 'डायरी नंबर ' . $diary_no : str_replace('"', '\"', $comlete_fil_no_prt_h);

            $case_data = array(
                "list_mainhead" => "Final Elimination List",
                "hi_list_mainhead" => "अंतिम उन्मूलन सूची",
                "final_elimination_content" =>  str_replace('"', '\"', $final_elimination_content),
                "final_Advance_list_no" =>  str_replace('"', '\"', $final_content),
                "hi_final_Advance_list_no" =>  str_replace('"', '\"', $final_content),
                "list_mainhead_id" => "9",
                "diary_no" => $diary_no,
                "section_name" => $section,
                "dno" => $remaining_digits_dno,
                "dyr" => $last_4_digits_yr,
                "case_no" =>  $case_no,
                "hi_case_no" => $case_no_h_2,
                "next_dt" => $list_dt,
                "item_no" => $psrno,
                "cause_title" => (str_replace('"', '\"', $result['pet_name']) . ' Verses ' . str_replace('"', '\"', $result['res_name'])),
                "hi_cause_title" => (str_replace('"', '\"', $result['hi_cause_title'])),
                "purpose" => ($purpose == 'Mention Memo' && 'Fixed Date by Court') ? str_replace('"', '\"', $purpose) : '',
                "hi_purpose" => ($hi_purpose == 'मेमो का उल्लेख करें' && 'न्यायालय द्वारा निश्चित तिथि') ? str_replace('"', '\"', $hi_purpose) : '',
                "Advocate" => $aor_details,
                "petitioner_Advocate" => str_replace("\n", '\"', $padvname),
                "hi_petitioner_Advocate" => str_replace("\n", '\"',  $padvname_h),
                "respondent_advocate" => str_replace("\n", '\"', trim($mergedNames)),
                "hi_respondent_advocate" => str_replace("\n", '\"', trim($mergedNames_h)),
            );
            $data[] = $case_data;
        }

        $mainhead = "M";
        $file_path = $mainhead . "_" . $board_type;
        $path_dir = WRITEPATH . "judgment/cl/final_elimination/$list_dt/";
        $path = $path_dir;
        $filePath = $path . $file_path . ".json";
        $json_result = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($filePath, $json_result);
        return true;
    }

    public function final_advocate_for_json($diary_no){
        $builder = $this->db->table('advocate AS a');
        $builder->select('a.pet_res_no, a.adv, a.pet_res, CONCAT(b.title, b.name) AS aor_name, b.aor_code, b.name_hindi AS aor_name_hindi');
        $builder->join('master.bar AS b', 'a.advocate_id = b.bar_id');
        $builder->where('a.diary_no', $diary_no);
        $builder->where('a.display', 'Y');
        $builder->orderBy('a.pet_res');
        $query = $builder->get();
        $results = $query->getResultArray();
        return $results;
    }

    public function get_purpose_for_json($diary_no){
        $builder = $this->db->table('heardt AS h');
        $builder->distinct();
        $builder->select('h.listorder AS code, p.purpose, p.purpose_hindi');
        $builder->join('master.listing_purpose AS p', 'h.listorder = p.code');
        $builder->where('p.display', 'Y');
        $builder->where('h.diary_no', $diary_no);
        $query = $builder->get();
        $results = $query->getRowArray();
        return $results;
    }


    public function get_final_eliminations($list_dt, $board_type)
    {
        $builder1 = $this->db->table('transfer_old_com_gen_cases t')
            ->select('ct.ent_dt, dd.doccode1, a.advocate_id, submaster_id, t.diary_no, m.reg_no_display, m.pet_name, m.res_name, pno, rno, m.diary_no_rec_date, h.subhead, t.listorder, l.priority, h.no_of_time_deleted, h.coram')
            ->join('main m', 't.diary_no = m.diary_no', 'left')
            ->join('heardt h', 'h.diary_no = m.diary_no', 'left')
            ->join('master.listing_purpose l', 'l.code = h.listorder', 'left')
            ->join('rgo_default rd', 'rd.fil_no = h.diary_no AND rd.remove_def = \'N\'', 'left')
            ->join('conct ct', 'm.diary_no = ct.diary_no AND ct.list = \'Y\'', 'left')
            ->join('mul_category mc', 'mc.diary_no = m.diary_no', 'left')
            ->join('docdetails dd', 'dd.diary_no = h.diary_no AND dd.iastat = \'P\' AND dd.doccode = 8 AND dd.doccode1 IN (40,41,48,49,71,72,118,131,211,309)', 'left')
            ->join('advocate a', 'a.diary_no = m.diary_no AND a.advocate_id = 584 AND a.display = \'Y\'', 'left')
            ->where('rd.fil_no IS NULL')
            ->whereNotIn('m.active_casetype_id', [9, 10, 25, 26])
            ->where('mc.display', 'Y')
            ->whereNotIn('mc.submaster_id', [911, 912, 914, 239, 240, 241, 242, 243])
            ->groupStart()
                ->whereIn('h.listorder', [4, 5])
                ->orGroupStart()
                    ->where('h.is_nmd', 'N')
                    ->whereNotIn('mc.submaster_id', [343, 15, 16, 17, 18, 19, 20, 21, 22, 23, 341, 353, 157, 158, 159, 160, 161, 162, 163, 166, 175, 176, 322, 222])
                ->groupEnd()
            ->groupEnd()
            ->where('m.c_status', 'P')
            ->groupStart()
                ->where('t.diary_no = t.conn_key', null, false)
                ->orWhere('t.conn_key', 0)
                ->orWhere('t.conn_key IS NULL')
            ->groupEnd()
            ->whereNotIn('h.subhead', [801, 817, 818, 819, 820, 848, 849, 850, 854])
            ->where('h.mainhead', 'M')
            ->where('t.board_type', $board_type)
            ->where('t.next_dt_old', $list_dt)
            ->where('t.listtype', 'F')
            ->where('h.listorder >', 0)
            ->groupBy('m.diary_no, ct.ent_dt, dd.doccode1, a.advocate_id, submaster_id, t.diary_no, m.reg_no_display, m.pet_name, m.res_name, pno, rno, m.diary_no_rec_date, h.subhead, t.listorder, l.priority, h.no_of_time_deleted, h.coram');

        // Build the second part of the union query
        $builder2 = $this->db->table('advanced_drop_note t')
            ->select('ct.ent_dt, dd.doccode1, a.advocate_id, submaster_id, t.diary_no, m.reg_no_display, m.pet_name, m.res_name, pno, rno, m.diary_no_rec_date, h.subhead, aa.listorder, l.priority, h.no_of_time_deleted, h.coram')
            ->join('advance_allocated aa', 'aa.diary_no = t.diary_no AND aa.next_dt = t.cl_date', 'inner')
            ->join('main m', 't.diary_no = m.diary_no', 'left')
            ->join('heardt h', 'h.diary_no = m.diary_no', 'left')
            ->join('master.listing_purpose l', 'l.code = h.listorder', 'left')
            ->join('rgo_default rd', 'rd.fil_no = h.diary_no AND rd.remove_def = \'N\'', 'left')
            ->join('mul_category mc', 'mc.diary_no = m.diary_no', 'left')
            ->join('docdetails dd', 'dd.diary_no = h.diary_no AND dd.iastat = \'P\' AND dd.doccode = 8 AND dd.doccode1 IN (40, 41, 48, 49, 71, 72, 118, 131, 211, 309)', 'left')
            ->join('advocate a', 'a.diary_no = m.diary_no AND a.advocate_id = 584 AND a.display = \'Y\'', 'left')
            ->join('conct ct', 'm.diary_no = ct.diary_no AND ct.list = \'Y\'', 'left')
            ->where('rd.fil_no IS NULL')
            ->where('t.cl_date', $list_dt)
            ->where('t.display', 'Y')
            ->where('m.c_status', 'P')
            ->groupBy('m.diary_no,ct.ent_dt, dd.doccode1, a.advocate_id, submaster_id, t.diary_no, m.reg_no_display, m.pet_name, m.res_name, pno, rno, m.diary_no_rec_date, h.subhead, aa.listorder, l.priority, h.no_of_time_deleted, h.coram');

        // Combine the queries with UNION using CodeIgniter's union() method
        $builder1->union($builder2);

        // Execute the final query with ordering
        $finalQuery = $this->db->table("({$builder1->getCompiledSelect()}) a")
            ->select('a.*, tentative_section(a.diary_no) as section_name')
            ->orderBy("(CASE WHEN a.subhead IN ('824', '810', '802', '807', '804') OR a.doccode1 IS NOT NULL OR a.advocate_id IS NOT NULL OR a.submaster_id = 173 OR a.listorder IN (4, 5, 7) THEN 1 ELSE 999 END) ASC")
            ->orderBy('a.priority', 'asc')
            ->orderBy('a.no_of_time_deleted', 'desc')
            ->orderBy("(CASE WHEN a.coram IS NOT NULL AND TRIM(a.coram) IS NOT NULL THEN 1 ELSE 999 END) ASC")
            ->orderBy("(COALESCE(a.ent_dt, '1970-01-01 00:00:00')) ASC")
            ->orderBy("(CAST(RIGHT(CAST(a.diary_no AS TEXT), 4) AS INTEGER)) DESC")
            ->orderBy("(CAST(LEFT(CAST(a.diary_no AS TEXT), LENGTH(CAST(a.diary_no AS TEXT)) - 4) AS INTEGER)) DESC");
        // Fetch the result
        $query = $finalQuery->get();
        $results = $query->getResultArray();

        foreach ($results as $index => $result) {
            $results[$index]['sno'] = $index + 1; // Serial number starting from 1
            $advocate = $this->get_advocate_bar($result['diary_no']);
            if(!empty($advocate)) {
                $radvname = $advocate["r_n"];
                $padvname = $advocate["p_n"];
            }
            if ($result['pno'] == 2) {
                $pet_name = $result['pet_name'] . " AND ANR.";
            } else if ($result['pno'] > 2) {
                $pet_name = $result['pet_name'] . " AND ORS.";
            } else {
                $pet_name = $result['pet_name'];
            }
            if ($result['rno'] == 2) {
                $res_name = $result['res_name'] . " AND ANR.";
            } else if ($result['rno'] > 2) {
                $res_name = $result['res_name'] . " AND ORS.";
            } else {
                $res_name = $result['res_name'];
            }

            $results[$index]['radvname'] = !empty($radvname) ? str_replace(",", ", ", trim($radvname, ",")) : '';
            $results[$index]['padvname'] = !empty($padvname) ? str_replace(",", ", ", trim($padvname, ",")) : '';
            $results[$index]['get_pet_name'] = $pet_name;
            $results[$index]['res_name'] = $res_name;

            if(isset($result['diary_no'])  && isset($result['conn_key'])) {
                $old_cases = $this->get_final_transfer_old_cases($result['diary_no'], $list_dt, $board_type);
                $results[$index]['old_cases'] = $old_cases;
                foreach($old_cases as $old_case_index => $old_case) {
                    $advocate_by_old_cases = $this->get_advocate_bar($old_case['diary_no']);
                    $results[$index]['old_cases'][$old_case_index]['advocate_by_old_cases'] =  $advocate_by_old_cases;
                }
            }

        }
        return $results;
    }

    public function get_final_transfer_old_cases($diary_no, $list_dt, $board_type)
    {
        $builder = $this->db->table('transfer_old_com_gen_cases t')
        ->select("tentative_section(m.diary_no) as section_name, submaster_id, t.diary_no, m.reg_no_display, m.pet_name, m.res_name, pno, rno, m.diary_no_rec_date")
        ->join('main m', 't.diary_no = m.diary_no', 'left')
        ->join('heardt h', 'h.diary_no = m.diary_no', 'left')
        ->join('conct c', "c.conn_key = CAST(m.conn_key AS BIGINT) AND c.list = 'Y'", 'inner')
        ->join('master.listing_purpose l', 'l.code = h.listorder', 'left')
        ->join('rgo_default rd', "rd.fil_no = h.diary_no AND rd.remove_def = 'N'", 'left')
        ->join('mul_category mc', 'mc.diary_no = m.diary_no', 'left')
        ->where('rd.fil_no IS NULL')
        ->whereNotIn('m.active_casetype_id', [9, 10, 25, 26])
        ->where('mc.display', 'Y')
        ->whereNotIn('mc.submaster_id', [911, 912, 914, 239, 240, 241, 242, 243])
        ->groupStart() // Group for case/when conditional
            ->whereIn('h.listorder', [4, 5])
            ->orGroupStart()
                ->where('h.is_nmd', 'N')
                ->whereNotIn('mc.submaster_id', [343, 15, 16, 17, 18, 19, 20, 21, 22, 23, 341, 353, 157, 158, 159, 160, 161, 162, 163, 166, 175, 176, 322, 222])
            ->groupEnd()
        ->groupEnd() // End of case/when conditional
        ->where('m.c_status', 'P')
        ->where('t.conn_key', $diary_no)
        ->where('t.diary_no != t.conn_key')
        ->whereNotIn('h.subhead', [801, 817, 818, 819, 820, 848, 849, 850, 854])
        ->where('h.mainhead', 'M')
        ->where('t.board_type', $board_type)
        ->where('t.next_dt_old', $list_dt)
        ->where('t.listtype', 'F')
        ->where('h.listorder >', 0)
        ->groupBy('m.diary_no, mc.submaster_id, h.coram, t.diary_no, t.next_dt_old, t.next_dt_new, t.tentative_cl_dt_old,t.tentative_cl_dt_new, t.listorder, t.conn_key,t.ent_dt,t.test2, t.listorder_new,t.board_type,t.listtype, t.reason')
        ->orderBy("(CAST(RIGHT(CAST(m.diary_no AS TEXT), 4) AS INTEGER)) DESC")
        ->orderBy("(CAST(LEFT(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER)) DESC");
        $query = $builder->get();
        $results = $query->getResultArray();
        return $results;
    }

}