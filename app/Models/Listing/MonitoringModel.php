<?php

namespace App\Models\Listing;

use CodeIgniter\Model;

class MonitoringModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    public function chksDate($dt)
    {
        // Query to check if the date exists in the holidays table
        $query = $this->db->table('master.holidays')
            ->select('COUNT(hdate) as date_count')
            ->where('hdate', $dt)
            ->get();

        $result = $query->getRow();

        if ($result->date_count > 0) {
            // If the date is a holiday, add 1 day and recheck
            $dt = date('Y-m-d', strtotime($dt . ' + 1 days'));
            return $this->chksDate($dt);  // Recursion to check the next date
        } else {
            // If not a holiday, return the date
            return $dt;
        }
    }

    public function get_sections()
    {
        $builder = $this->db->table('master.usersection');
        $builder->select('*');
        $builder->where('display', 'Y');
        $builder->where('isda', 'Y');
        $builder->orderBy('section_name');
        $query = $builder->get();
        $results = $query->getResultArray();
        return $results;
    }

    public function get_listing_purpose()
    {
        $builder = $this->db->table('master.listing_purpose');
        $builder->select('code, code || \'. \' || purpose AS lp');
        $builder->where('code !=', 22);
        $builder->where('purpose IS NOT NULL');
        $builder->where('display', 'Y');
        $builder->orderBy('priority');
        $query = $builder->get();
        $results = $query->getResultArray();
        return $results;
    }

    public function get_monitoring_data($usertype, $ucode, $data)
    {
        $mdacode = "";
        $list_dt = date('Y-m-d', strtotime($data['list_dt']));

        if ($usertype == '14' && !in_array($ucode, [770, 9919, 2229, 801, 2533, 3564, 803, 1366, 846, 9925, 10739])) {
            $builder = $this->db->table('master.users u');
            $builder->select('STRING_AGG(u2.usercode::text, \', \') as allda'); // Use STRING_AGG for PostgreSQL
            $builder->join('master.users u2', 'u2.section = u.section');
            $builder->where('u.display', 'Y');
            $builder->where('u.usercode', $ucode);
            $builder->groupBy('u2.section');
            $query = $builder->get();
            $ro_u = $query->getRowArray();
            if ($ro_u) {
                $all_da = $ro_u['allda'];
                $mdacode = "m.dacode IN ($all_da) ";
            }
        } else if (in_array($usertype, ['17', '50', '51']) && !in_array($ucode, [770, 9919, 2229, 801, 2533, 3564, 803, 1366, 846, 9925, 10739])) {
            $mdacode = "m.dacode = '$ucode' ";
        }

        $builder = $this->db->table('main m');
        $builder->distinct();
        $builder->select([
            'm.diary_no',
            'h.next_dt',
            'h.list_before_remark',
            'u.name',
            "CASE WHEN us.section_name IS NOT NULL THEN us.section_name ELSE tentative_section(m.diary_no) END AS section_name",
            'm.conn_key AS main_key',
            'l.purpose',
            's.stagename',
            'h.coram',
            'c1.short_description',
            'm.active_fil_no',
            'm.active_reg_year',
            'm.casetype_id',
            'm.active_casetype_id',
            'm.ref_agency_state_id',
            'm.reg_no_display',
            'EXTRACT(YEAR FROM m.fil_dt) AS fil_year',
            'm.fil_no',
            'm.fil_dt',
            'm.fil_no_fh',
            'm.reg_year_fh AS fil_year_f',
            'm.mf_active',
            'm.pet_name',
            'm.res_name',
            'm.lastorder',
            'm.pno',
            'm.rno',
            'm.diary_no_rec_date',
            "CASE WHEN (m.diary_no = CAST(m.conn_key AS bigint) OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL) THEN 0 ELSE 1 END AS main_or_connected",
            "(SELECT CASE WHEN diary_no IS NOT NULL THEN 1 ELSE 0 END FROM conct WHERE diary_no = m.diary_no AND LIST = 'Y') AS listed",
            "CASE WHEN m.conn_key != '' THEN SUBSTR(CAST(m.conn_key AS text), -4) ELSE SUBSTR(CAST(m.diary_no AS text), -4) END AS order_key",
            "CASE WHEN m.conn_key != '' THEN m.conn_key ELSE CAST(m.diary_no AS text) END AS order_value",
            "CASE WHEN m.conn_key = CAST(m.diary_no AS text) THEN 0 ELSE 1 END AS connection_check"
        ]);

        $builder->join('heardt h', 'h.diary_no = m.diary_no', 'INNER');
        $builder->join('master.listing_purpose l', 'l.code = h.listorder AND l.display = \'Y\'', 'LEFT');
        $builder->join('master.subheading s', 's.stagecode = h.subhead AND s.display = \'Y\' AND s.listtype = \'M\'', 'LEFT');
        $sec_id = $sec_id2 = $listorder_q = "";
        if ($data['sec_id'] !== "0") {
            $sec_id = "us.id = '" . $data['sec_id'] . "'";
            $builder->where($sec_id);
            $sec_id2 = "us.id is not null";
        }

        if ($data['listorder'] == 0) {
            $listorder_q = "";
        } else {
            $listorder_q = "h.listorder = $data[listorder] ";
        }

        $listed_no_q = "";
        $list_print_flag = "";
        $left_join_verify = "";
        $left_join_verify_whr = "";

        if ($data['listed_not'] == 0) {
            $listed_no_q = "h.clno = 0 AND h.main_supp_flag = 0 ";
            $list_print_flag = "(Not Listed/Ready Not Verified)";
            $left_join_verify = "LEFT JOIN case_verify tt ON tt.diary_no = h.diary_no AND tt.ent_dt > h.ent_dt AND tt.display = 'Y'";
            $left_join_verify_whr = "tt.diary_no IS NULL";
        } elseif ($data['listed_not'] == 1) {
            $listed_no_q = "h.clno > 0 ";
            $list_print_flag = "(Listed Not Verified Cases)";
            $left_join_verify = "LEFT JOIN case_verify tt ON tt.diary_no = h.diary_no AND tt.next_dt = h.next_dt AND tt.display = 'Y'";
            $left_join_verify_whr = "tt.diary_no IS NULL";
        } elseif ($data['listed_not'] == 2) {
            $listed_no_q = "h.clno > 0 ";
            $list_print_flag = "(Listed Verified Cases)";
            $left_join_verify = "LEFT JOIN case_verify tt ON tt.diary_no = h.diary_no AND tt.next_dt = h.next_dt AND tt.display = 'Y'";
            $left_join_verify_whr = "tt.diary_no IS NOT NULL";
        }

        if ($left_join_verify) {
            $builder->join('case_verify tt', "tt.diary_no = h.diary_no AND tt.ent_dt > h.ent_dt AND tt.display = 'Y'", 'LEFT');
        }

        $builder->join('master.casetype c1', 'm.active_casetype_id = c1.casecode', 'LEFT');
        $builder->join('master.users u', 'u.usercode = m.dacode AND u.display = \'Y\'', 'LEFT');
        $builder->join('master.usersection us', 'us.id = u.section', 'LEFT');

        if ($left_join_verify_whr) {
            $builder->where($left_join_verify_whr);
        }

        $builder->where('h.next_dt', $list_dt);
        if ($mdacode) {
            $builder->where($mdacode);
        }
        if ($sec_id2) {
            $builder->where($sec_id2);
        }
        if ($listorder_q) {
            $builder->where($listorder_q);
        }
        $builder->where('m.c_status', 'P');
        $builder->where('h.board_type', $data['board_type']);
        $builder->where('h.mainhead', $data['mainhead']);

        if (!empty($listed_no_q)) {
            $builder->where($listed_no_q);
        }
        //$builder->whereIn("TRIM(LEADING '0' FROM SPLIT_PART(m.fil_no, '-', 1))", [3, 15, 19, 31, 23, 24, 40, 32, 34, 22, 39, 11, 17, 13, 1, 7, 37, 9999, 38, 5, 21, 27, 4, 16, 20, 18, 33, 41, 35, 36, 28, 12, 14, 2, 8, 6]);
        $builder->groupStart();
        $builder->whereIn("CAST(TRIM(LEADING '0' FROM SPLIT_PART(m.fil_no, '-', 1)) AS integer)", [3, 15, 19, 31, 23, 24, 40, 32, 34, 22, 39, 11, 17, 13, 1, 7, 37, 9999, 38, 5, 21, 27, 4, 16, 20, 18, 33, 41, 35, 36, 28, 12, 14, 2, 8, 6]);
        $builder->orWhere('m.active_fil_no IS NULL');
        $builder->groupEnd();

        $builder->groupStart();
        $builder->where("(m.diary_no = CAST(m.conn_key AS BIGINT) OR m.conn_key = '0' OR m.conn_key IS NULL)");
        $builder->orWhere("(SELECT DISTINCT conn_key FROM conct WHERE diary_no = m.diary_no) IN (SELECT diary_no FROM heardt t1 WHERE t1.next_dt = h.next_dt)");
        $builder->groupEnd();
        $builder->groupBy([
            'm.diary_no',
            'h.next_dt',
            'h.list_before_remark',
            'u.name',
            'section_name',
            'main_key',
            'l.purpose',
            's.stagename',
            'h.coram',
            'c1.short_description',
            'm.active_fil_no',
            'm.active_reg_year',
            'm.casetype_id',
            'm.active_casetype_id',
            'm.ref_agency_state_id',
            'm.reg_no_display',
            'fil_year',
            'm.fil_no',
            'm.fil_dt',
            'm.fil_no_fh',
            'fil_year_f',
            'm.mf_active',
            'm.pet_name',
            'm.res_name',
            'm.lastorder',
            'm.pno',
            'm.rno',
            'm.diary_no_rec_date',
            'main_or_connected',
            'listed',
            'order_key',
            'order_value',
            'connection_check'
        ]);
        $builder->orderBy('order_key');
        $builder->orderBy('order_value');
        $builder->orderBy('connection_check', 'ASC');
        //pr($builder->getCompiledSelect());
        // Execute the query
        $query = $builder->get();
        $get_results = $query->getResultArray();

        foreach ($get_results as $row_index => $row) {
            if (($row['section_name'] == null or $row['section_name'] == '') and $row['ref_agency_state_id'] != '' and $row['ref_agency_state_id'] != 0) {
                if ($row['active_reg_year'] != 0) {
                    $ten_reg_yr = $row['active_reg_year'];
                } else {
                    $ten_reg_yr = date('Y', strtotime($row['diary_no_rec_date']));
                }

                if ($row['active_casetype_id'] != 0) {
                    $casetype_displ = $row['active_casetype_id'];
                } else if ($row['casetype_id'] != 0) {
                    $casetype_displ = $row['casetype_id'];
                }

                $section_ten_row  = $this->da_case_distribution($casetype_displ, $ten_reg_yr, $row['ref_agency_state_id']);
                if ($section_ten_row) {
                    $get_results[$row_index]['section_name'] = $section_ten_row["section_name"];
                }
            }
        }

        $results['monitoring_data'] = $get_results;
        $results['list_print_flag'] = $list_print_flag;
        return $results;
    }


    public function get_category_code($diary_numbers)
    {
        $cate_old = [];
        $builder = $this->db->table('mul_category mc');
        $builder->select('mc.diary_no, mc.od_cat');
        $builder->join('master.submaster s', 's.id = mc.submaster_id', 'INNER');
        $builder->where('mc.display', 'Y');
        $builder->whereIn('mc.diary_no', $diary_numbers);
        $query = $builder->get();
        $results = $query->getResultArray();
        $grouped = [];
        foreach ($results as $result) {
            $diary_no = $result['diary_no'];
            $od_cat = $result['od_cat'];
            if (!isset($grouped[$diary_no])) {
                $grouped[$diary_no] = [
                    'diary_no' => $diary_no,
                    'od_cat' => '',
                ];
            }

            if ($grouped[$diary_no]['od_cat'] === '') {
                $grouped[$diary_no]['od_cat'] = $od_cat;
            } else {
                $grouped[$diary_no]['od_cat'] .= ',' . $od_cat;
            }
        }

        return $grouped;
    }

    public function get_advocate_details($diary_numbers)
    {
        $subQuery = $this->db->table('advocate a')
            ->select([
                'a.diary_no',
                'b.name',
                "STRING_AGG(COALESCE(a.adv, ''), '' ORDER BY (CASE WHEN a.pet_res = 'I' THEN 99 ELSE 0 END), a.adv_type, a.pet_res_no ASC) AS grp_adv",
                'a.pet_res',
                'a.adv_type',
                'a.pet_res_no'
            ])
            ->join('master.bar b', 'a.advocate_id = b.bar_id AND b.isdead != \'Y\'', 'LEFT')
            ->whereIn('a.diary_no', $diary_numbers)
            ->where('a.display', 'Y')
            ->groupBy('a.diary_no, b.name, a.pet_res, a.adv_type,a.pet_res_no')
            ->getCompiledSelect();
        $builder = $this->db->table("($subQuery) AS a");
        $builder->select("a.diary_no, 
            a.name,
            STRING_AGG(a.name || CASE WHEN a.pet_res = 'R' THEN a.grp_adv ELSE '' END, '' ORDER BY a.adv_type DESC, a.pet_res_no ASC) AS r_n,
            STRING_AGG(a.name || CASE WHEN a.pet_res = 'P' THEN a.grp_adv ELSE '' END, '' ORDER BY a.adv_type DESC, a.pet_res_no ASC) AS p_n,
            STRING_AGG(a.name || CASE WHEN a.pet_res = 'I' THEN a.grp_adv ELSE '' END, '' ORDER BY a.adv_type DESC, a.pet_res_no ASC) AS i_n");

        $builder->groupBy('a.diary_no, a.name');
        $query = $builder->get();
        $results = $query->getResultArray();
        $diaryResults = [];
        foreach ($results as $result) {
            $diaryResults[$result['diary_no']] = $result;
        }
        return $diaryResults;
    }


    public function max_entry_date($diary_numbers)
    {
        $builder = $this->db->table('case_verify');
        $builder->select(['diary_no', 'MAX(ent_dt) AS max_edt']);
        $builder->whereIn('diary_no', $diary_numbers);
        $builder->groupBy('diary_no');
        $results = $builder->get()->getResultArray();
        $caseResults = [];
        foreach ($results as $result) {
            $caseResults[$result['diary_no']] = $result;
        }
        return $caseResults;
    }

    public function case_verify_details($earlier_verify)
    {
        $caseResults = $verifyResult = [];
        foreach ($earlier_verify as $diary_no => $verify) {
            $builder = $this->db->table('case_verify cv');
            $builder->select([
                'diary_no',
                'STRING_AGG(cr.remarks, \', \') AS rem_dtl',
                'cv.ent_dt'
            ]);
            $builder->join('master.case_verify_by_sec_remark cr', 'cr.id = ANY(string_to_array(cv.remark_id, \',\')::int[])', 'LEFT');
            $builder->where('cv.diary_no', $diary_no);
            $builder->where('cv.ent_dt', $verify['max_edt']);
            $builder->groupBy('cv.id');
            $verifyResult = $builder->get()->getResultArray();
            foreach ($verifyResult as $result) {
                $caseResults[$result['diary_no']][] = $result;
            }
        }
        return $caseResults;
    }

    public function remarks_list()
    {
        $builder = $this->db->table('master.case_verify_by_sec_remark');
        $builder->select(['id', 'remarks']);
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function get_not_before_details($diary_numbers)
    {
        $builder = $this->db->table('not_before n');
        $builder->select([
            "n.diary_no",
            "STRING_AGG(n.j1::text, ', ') AS bef_jud",  // Casting j1 to text
            "nr.res_add"
        ]);
        $builder->join('master.not_before_reason nr', 'nr.res_id = n.res_id');
        $builder->where('n.notbef', 'B');
        $builder->whereIn('n.diary_no', $diary_numbers);
        $builder->groupBy('n.diary_no, nr.res_add');
        $query = $builder->get();
        $results = $query->getResultArray(); // Use getResult() for objects
        $caseResults = [];
        foreach ($results as $result) {
            $caseResults[$result['diary_no']] = $result;
        }
        return $caseResults;
    }

    public function get_rop_data($diary_numbers)
    {
        $rop_data = [];
        foreach ($diary_numbers as $index => $dno) {
            $subQuery1 = $this->db->table('tempo o')
                ->select([
                    'o.diary_no AS diary_no',
                    'o.jm AS jm',
                    "TO_CHAR(CAST(o.dated AS DATE), 'YYYY-MM-DD') AS dated",
                    "CASE
                WHEN o.jt = 'rop' THEN 'ROP'
                WHEN o.jt = 'judgment' THEN 'Judgement'
                WHEN o.jt = 'or' THEN 'Office Report'
            END AS jo"
                ])
                ->where('o.diary_no', $dno);


            $subQuery2 = $this->db->table('ordernet o')
                ->select([
                    'o.diary_no AS diary_no',
                    'o.pdfname AS jm',
                    "TO_CHAR(CAST(o.orderdate AS DATE), 'YYYY-MM-DD') AS dated",
                    "CASE
                WHEN o.type = 'O' THEN 'ROP'
                WHEN o.type = 'J' THEN 'Judgement'
            END AS jo"
                ])
                ->where('o.diary_no', $dno);


            $subQuery3 = $this->db->table('rop_text_web.old_rop o')
                ->select([
                    'o.dn AS diary_no',
                    "CONCAT('ropor/rop/all/', o.pno, '.pdf') AS jm",
                    "TO_CHAR(CAST(o.orderDate AS DATE), 'YYYY-MM-DD') AS dated",
                    "'ROP' AS jo"
                ])
                ->where('o.dn', $dno);


            $subQuery4 = $this->db->table('scordermain o')
                ->select([
                    'o.dn AS diary_no',
                    "CONCAT('judis/', o.filename, '.pdf') AS jm",
                    "TO_CHAR(CAST(o.juddate AS DATE), 'YYYY-MM-DD') AS dated",
                    "'Judgment' AS jo"
                ])
                ->where('o.dn', $dno);



            $subQuery5 = $this->db->table('rop_text_web.ordertext o')
                ->select([
                    'o.dn AS diary_no',
                    "CONCAT('bosir/orderpdf/', o.pno, '.pdf') AS jm",
                    "TO_CHAR(CAST(o.orderdate AS DATE), 'YYYY-MM-DD') AS dated",
                    "'ROP' AS jo"
                ])
                ->where('o.dn', $dno)
                ->where('o.display', 'Y');


            $subQuery6 = $this->db->table('rop_text_web.oldordtext o')
                ->select([
                    'o.dn AS diary_no',
                    "CONCAT('bosir/orderpdfold/', o.pno, '.pdf') AS jm",
                    "TO_CHAR(CAST(o.orderdate AS DATE), 'YYYY-MM-DD') AS dated",
                    "'ROP' AS jo"
                ])
                ->where('o.dn', $dno);

            // Combine the subqueries using UNION ALL
            $finalQuery = $subQuery1->union($subQuery2)
                ->union($subQuery3)
                ->union($subQuery4)
                //->union($subQuery5)
                ->union($subQuery6)
                ->getCompiledSelect();

            // Execute the final query
            $builder = $this->db->table("($finalQuery) AS tbl1");
            $builder->select([
                'diary_no',
                'jm AS pdfname',
                'dated AS orderdate'
            ]);
            $builder->where('jo', 'ROP');
            $builder->orderBy('tbl1.dated', 'DESC');
            $query = $builder->get();
            $results = $query->getResultArray();
            $rop_data[$dno] = $results;
        }
        return $rop_data;
    }

    public function get_users($hd_ud)
    {
        $builder = $this->db->table('master.users');
        $builder->where('usercode', $hd_ud);
        $builder->where('display', 'Y');
        $query = $builder->get();
        $result = $query->getRowArray();
        return $result;
    }


    public function c_list($tpaps, $jcode)
    {
        $return = [];
        if (!($jcode == 0 && ($tpaps == 'RDR' || $tpaps == 'RDR_ABS'))) {
            $builder = $this->db->table('master.judge t1');
            if ($jcode == 0) {
                $builder->select('jcode AS jcode, TRIM(jname) AS jname')
                    ->where('display', 'Y')
                    ->where('is_retired', 'N')
                    ->whereIn('jtype', ['J', 'R'])
                    ->orderBy('jtype, judge_seniority');
            } else {
                $builder->select('t1.jcode AS jcode, TRIM(t1.jname) AS jname')
                    ->where('t1.jcode', $jcode)
                    ->where('t1.display', 'Y')
                    ->where('t1.is_retired', 'N')
                    ->whereIn('t1.jtype', ['J', 'R'])
                    ->orderBy('t1.jtype, t1.judge_seniority');
            }

            $query = $builder->get();
            $results2 = $query->getResultArray();
            $return = $results2;
            /*if (!empty($results2)) {
                foreach ($results2 as $row2) {
                    if ($this->request->getPost('aw1') == $row2['jcode']) {
                        echo '<option value="' . $row2['jcode'] . '" selected>' . str_replace("\\", "", $row2['jname']) . '</option>';
                    } else {
                        echo '<option value="' . $row2['jcode'] . '">' . str_replace("\\", "", $row2['jname']) . '</option>';
                    }
                }
            }*/
        }
        return $return;
    }

    public function case_remarks_head()
    {
        $builder = $this->db->table('master.case_remarks_head');
        $builder->where('side', 'P')->where('display', 'Y');
        $builder->orderBy("CASE WHEN cat_head_id < 1000 THEN 0 ELSE 1 END", '', false);
        $builder->orderBy('head');
        $query = $builder->get();
        return $query->getResultArray();
    }


    public function case_remarks_head_side()
    {
        $builder = $this->db->table('master.case_remarks_head');
        $builder->where('side', 'D')->where('display', 'Y');
        $builder->orderBy("CASE WHEN sno IN (134, 144, 27, 28, 30, 36) THEN 0 ELSE 1 END", "ASC", false);
        $builder->orderBy('head');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_advocates($adv_id)
    {
        $return = '';
        $builder = $this->db->table('master.bar');
        $builder->select('name');
        $builder->where('bar_id', $adv_id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $row = $query->getRow();
            $return =  $row->name;
        }
        return $return;
    }

    public function get_drop_note_print($list_dt, $mainhead, $roster_id)
    {
        $builder = $this->db->table('drop_note d');
        $builder->select("
            d.clno, 
            IFNULL(d.nrs, '-') AS nrs,
            d.mf,
            d.diary_no,
            IF((m.active_reg_year IS NULL OR m.active_reg_year = 0 OR m.active_reg_year = ''), 
                m.diary_no,
                CONCAT(short_description, '/', 
                    (CASE 
                        WHEN TRIM(LEADING '0' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(m.active_fil_no, '-', 2), '-', -1)) = TRIM(LEADING '0' FROM SUBSTRING_INDEX(m.active_fil_no, '-', -1))
                        THEN TRIM(LEADING '0' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(m.active_fil_no, '-', 2), '-', -1))
                        ELSE CONCAT(TRIM(LEADING '0' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(m.active_fil_no, '-', 2), '-', -1)), '-', TRIM(LEADING '0' FROM SUBSTRING_INDEX(m.active_fil_no, '-', -1)))
                    END), '/', m.active_reg_year)) AS case_no
        ");

        $builder->join('main m', 'm.diary_no = d.diary_no', 'INNER');
        $builder->join('casetype c', 'c.casecode = m.active_casetype_id', 'LEFT');
        $builder->where('d.cl_date', $list_dt);
        $builder->where('d.display', 'Y');
        $builder->where('d.roster_id', $roster_id);
        $builder->where('d.mf', $mainhead);
        $builder->orderBy('d.clno');
        $query = $builder->get();
        return $query->getResultArray();
    }


    public function get_judges($jcodes)
    {
        $jnames = "";
        if ($jcodes != '') {
            $t_jc = explode(",", $jcodes);
            $builder = $this->db->table('judge');
            foreach ($t_jc as $index => $jcode) {
                $builder->select('jname');
                $builder->where('jcode', trim($jcode));
                $query = $builder->get();
                if ($query->getNumRows() > 0) {
                    $row = $query->getRow();
                    if ($jnames == '') {
                        $jnames .= $row->jname;
                    } else {
                        if ($index == (count($t_jc) - 1)) {
                            $jnames .= " and " . $row->jname;
                        } else {
                            $jnames .= ", " . $row->jname;
                        }
                    }
                }
            }
        }
        return $jnames;
    }

    public function getCourtData($data)
    {
        $crt = $data['crt'];
        $dtd = $data['dtd'];
        $jcd = $data['jcd'];
        $mf = $data['mf'];
        $r_status = $data['r_status'];
        $vstats = $data['vstats'];

        $msg = "";
        $tdt = explode("-", $dtd);
        $tdt1 = $tdt[2] . "-" . $tdt[1] . "-" . $tdt[0];
        $printFrm = 0;
        $sql_t = $result = '';
        $results10 = [];
        if ($crt != '') {
            $stg = ($mf == 'M') ? 1 : ($mf == 'F' ? 2 : null);
            $t_cn = $this->getCourtCondition($crt, $tdt1);
            list($list_print_flag, $left_join_verify, $left_join_verify_whr) = $this->getVerificationStatus($vstats);

            // Fetch roster IDs
            $result = $this->getRosterIds($stg, $t_cn);

            // Prepare status condition
            $whereStatus = $this->getStatusCondition($r_status);

            // Build main SQL query
            $sql_t = $this->buildMainQuery($result, $tdt1, $mf, $left_join_verify, $left_join_verify_whr, $whereStatus);
        }

        if ($jcd != '') {
            $tmf = $this->get_mf_code($mf);
            $whereStatus = $this->getStatusCondition($r_status);

            // Build query for specific judges
            $sql_t = $this->buildJudgeQuery($jcd, $tdt1, $mf, $whereStatus);
        }

        if (!empty($sql_t)) {
            $results10 = $this->db->query($sql_t)->getResultArray();

            foreach ($results10 as $row_key => $row10) {
                $jcodes = "";
                $jcodes11 = "";
                $jnm = "";
                $sbdb = "";
                $sbdb1 = "";
                $jabr = "";
                $t_cl_dt = '';
                $cstatus = $row10["c_status"];
                $jnm = $row10["judges"];
                $jcodes = $row10["judges"];
                $jcodes11 = $row10["judges"];
                $t_fil_no = $row10['reg_no_display'];
                $jc = '';

                if ($jc != $jnm) {

                    $cntr = 0;
                    $jc = $jnm;
                    $stagec = "";
                    $mf = "";
                    $clno = 0;
                    $chk_pslno = 0;
                    $previous_brd_slno = 0;

                    //CHECK ROSTER
                    $bench_from_roster = "";
                    $ttt = 1;
                    $jcourt = '';
                    $builder = $this->db->table('master.roster');
                    $query = $builder->select('roster.id, roster.bench_id, CONCAT(master_bench.abbr, \' - \', roster_bench.bench_no) AS bnch, roster.m_f, roster.session, roster.frm_time, roster.courtno')
                        ->join('master.roster_bench', 'roster_bench.id = roster.bench_id AND roster.display = \'Y\'', 'inner')
                        ->join('master.master_bench', 'master_bench.id = roster_bench.bench_id AND roster_bench.display = \'Y\'', 'left')
                        ->where('roster.display', 'Y')
                        ->where('roster.id', $row10['roster_id']);

                    $row_rstr = $query->get()->getRowArray();
                    if ($row_rstr) {
                        $jcourt = $row_rstr['courtno'];
                        $results10[$row_key]['jcourt'] = $jcourt;
                    }
                }

                //$case_types = $this->get_case_type($row10['diary_no']);
                //$multiple_case_remarks = $this->multiple_case_remarks($row10['diary_no'], $tdt1, $jcodes11);

                //HIDE CODE FOR ADVOCATES FROM MAIN TABLE
                $padv = explode(",", trim($row10['pet_adv_id']));
                $padv1 = "";
                for ($k = 0; $k < count($padv); ++$k) {
                    if ($padv[$k] != 0) {
                        if ($k == 0)
                            $padv1 .= $this->get_advocates($padv[$k]);
                        else
                            $padv1 .= ", " . $this->get_advocates($padv[$k]);
                    }
                }

                $radv = explode(",", trim($row10['res_adv_id']));
                $radv1 = "";
                for ($k = 0; $k < count($radv); ++$k) {
                    if ($radv[$k] != 0) {
                        if ($k == 0)
                            $radv1 .= $this->get_advocates($radv[$k]);
                        else
                            $radv1 .= ", " . $this->get_advocates($radv[$k]);
                    }
                }

                $results10[$row_key]['padv1'] = $padv1;
                $results10[$row_key]['radv1'] = $padv1;

                $dispose = $this->dispose_details($row10['diary_no'], $tdt1);
                $jo_alottment_paps = $this->jo_alottment_paps($row10['diary_no'], $tdt1);
                $get_docdetails = $this->get_docdetails($row10['diary_no']);
                $get_last_heardt = $this->get_last_heardt($row10['diary_no']);
                $results10[$row_key]['result_drop'] = $this->drop_details($row10, $tdt1);
                $main_case_no = ($row10['conn_key'] > 0) ? $row10['conn_key'] : $row10['diary_no'];
                $results10[$row_key]['ordernet'] = $this->ordernet($main_case_no, $tdt1);
                $results10[$row_key]['brdrem'] = $this->get_brdrem($row10['diary_no']);
                $results10[$row_key]['subheading'] = $this->get_subheading($row10["subhead"]);
                $results10[$row_key]['showlcd'] = $this->showlcd($jcourt, $tdt1);
                $results10[$row_key]['rhead'] = $this->get_rhead($row10['diary_no'], $tdt1);
                $results10[$row_key]['head_deails'] = $this->get_head_deails($row10['diary_no'], $tdt1, $jcodes11);
                $results10[$row_key]['heardt_board_type'] = $this->heardt_board_type($row10['diary_no'], $tdt1, $jcodes11);
                $results10[$row_key]['case_verify_rop'] = $this->case_verify_rop($row10['diary_no'], $tdt1);
                $results10[$row_key]['showlcd1'] = $this->get_showlcd($jcourt, $tdt1);
            }
        }

        return $results10;
    }

    private function getCourtCondition($crt, $tdt1)
    {
        /*if ($crt == '') {
            return " AND IF(`board_type_mb` = 'R', to_date = '0000-00-00', from_date = '" . $tdt1 . "')";
        } elseif ($crt == "101") {
            return " AND `board_type_mb` = 'C' AND from_date = '" . $tdt1 . "'";
        } elseif ($crt == "102") {
            return " AND `board_type_mb` = 'R' AND to_date = '0000-00-00'";
        } else {
            return " AND `courtno` = '" . $crt . "' AND IF(to_date = '0000-00-00', from_date = '" . $tdt1 . "', '" . $tdt1 . "' BETWEEN from_date AND to_date)";
        }*/
        if ($crt == '') {
            return " IF(board_type_mb = 'R', to_date IS NULL, from_date = '" . $tdt1 . "')";
        } elseif ($crt == "101") {
            return " board_type_mb = 'C' AND from_date = '" . $tdt1 . "'";
        } elseif ($crt == "102") {
            return " board_type_mb = 'R' AND to_date IS NULL";
        } else {
            //return " courtno = '" . $crt . "' AND IF(to_date IS NULL, from_date = '" . $tdt1 . "', '" . $tdt1 . "' BETWEEN from_date AND to_date)";
            return " courtno = '" . $crt . "' AND (to_date IS NULL OR '" . $tdt1 . "' BETWEEN from_date AND to_date) ";
        }
    }

    private function getVerificationStatus($vstats)
    {
        switch ($vstats) {
            case 0:
                return ["(Verified/Not Verified)", "LEFT JOIN case_verify_rop tt ON tt.diary_no = h.diary_no AND tt.ent_dt > h.heardt_ent_dt and tt.cl_dt = h.next_dt AND tt.display = 'Y'", ""];
            case 2:
                return ["(Not Verified Cases)", "LEFT JOIN case_verify_rop tt ON tt.diary_no = h.diary_no AND DATE(tt.cl_dt) = DATE(h.next_dt) AND tt.ent_dt > h.heardt_ent_dt AND tt.display = 'Y'", " tt.diary_no IS NULL AND "];
            case 1:
                return ["(Verified Cases)", "LEFT JOIN case_verify_rop tt ON tt.diary_no = h.diary_no AND h.next_dt <= tt.cl_dt AND DATE(tt.cl_dt) <= DATE(h.next_dt) AND tt.display = 'Y'", " tt.diary_no IS NOT NULL AND "];
            default:
                return ["", "", ""];
        }
    }

    private function getRosterIds($stg, $t_cn)
    {
        $builder = $this->db->table('master.roster_judge rj');
        $builder->distinct();
        $builder->select(['rj.roster_id', 'mb.board_type_mb', 'rj.judge_id']);
        $builder->select("CASE WHEN courtno = '0' THEN '9999' ELSE courtno END AS ordered_courtno");
        $builder->select("CASE 
                WHEN mb.board_type_mb = 'J' THEN 1 
                WHEN mb.board_type_mb = 'C' THEN 2 
                WHEN mb.board_type_mb = 'CC' THEN 3 
                WHEN mb.board_type_mb = 'R' THEN 4 
                ELSE 5 END AS board_type_order");
        $builder->join('master.roster r', 'rj.roster_id = r.id');
        $builder->join('master.roster_bench rb', 'rb.id = r.bench_id AND rb.display = \'Y\'');
        $builder->join('master.master_bench mb', 'mb.id = rb.bench_id AND mb.display = \'Y\'');
        $builder->where('r.m_f', (string)$stg);
        $builder->where('rj.display', 'Y');
        $builder->where('r.display', 'Y');
        $builder->where($t_cn);
        $builder->orderBy('ordered_courtno', 'ASC');
        $builder->orderBy('board_type_order', 'ASC');
        $builder->orderBy('rj.judge_id', 'ASC');
        $query = $builder->get();
        $result = [];
        foreach ($query->getResultArray() as $row) {
            $result[] = $row['roster_id'];
        }
        return implode(',', $result);
    }

    private function getStatusCondition($r_status)
    {
        switch ($r_status) {
            case 'A':
                return '';
            case 'P':
                return " AND m.c_status='P'";
            case 'D':
                return " AND m.c_status='D'";
            default:
                return '';
        }
    }

    private function buildMainQuery1($result, $tdt1, $mf, $left_join_verify, $left_join_verify_whr, $whereStatus)
    {
        return "SELECT tt.remark_id, tt.cl_dt, 
                SUBSTR(CAST(m.diary_no AS TEXT), 1, LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS case_no, 
                SUBSTR(CAST(m.diary_no AS TEXT), -4) AS year, 
                 TO_CHAR(m.fil_dt, 'DD-MM-YYYY HH12:MI PM') AS fil_dt_f,
                CASE 
                    WHEN m.reg_year_mh = 0 THEN EXTRACT(YEAR FROM m.fil_dt) 
                    ELSE m.reg_year_mh 
                END AS m_year,
                m.diary_no, m.reg_no_display, m.mf_active, m.conn_key, h.judges, 
                h.mainhead, h.board_type, h.next_dt, h.subhead, h.clno, h.brd_slno,
                h.heardt_ent_dt, h.tentative_cl_dt, m.pet_name, m.res_name, 
                m.pet_adv_id, m.res_adv_id, m.c_status, 
               CASE 
                    WHEN cl.next_dt IS NULL THEN 'NA' 
                    ELSE CAST(h.brd_slno AS TEXT)
                END AS brd_prnt,
                h.roster_id, 
                TO_CHAR(m.fil_dt_fh, 'DD-MM-YYYY HH12:MI PM') AS fil_dt_fh,
                CASE 
                    WHEN m.reg_year_fh = 0 THEN EXTRACT(YEAR FROM m.fil_dt_fh) 
                    ELSE m.reg_year_fh 
                END AS f_year
                FROM (SELECT t1.diary_no, t1.next_dt, t1.roster_id, t1.judges, 
                      t1.mainhead, t1.board_type, t1.subhead, t1.clno, t1.brd_slno, 
                      t1.ent_dt AS heardt_ent_dt, t1.main_supp_flag, 
                      t1.tentative_cl_dt
                      FROM heardt t1 WHERE t1.next_dt = '$tdt1' 
                      AND t1.mainhead = '$mf' 
                      
                      AND CAST(t1.roster_id AS TEXT) = ANY(
                            STRING_TO_ARRAY('$result', ',')
                        ) 
                      AND (t1.main_supp_flag = 1 OR t1.main_supp_flag = 2)
                      UNION
                      SELECT t2.diary_no, t2.next_dt, t2.roster_id, t2.judges, 
                      t2.mainhead, t2.board_type, t2.subhead, t2.clno, t2.brd_slno, 
                      t2.ent_dt AS heardt_ent_dt, t2.main_supp_flag, 
                      t2.tentative_cl_dt 
                      FROM last_heardt t2 
                      WHERE t2.next_dt = '$tdt1' 
                      AND t2.mainhead = '$mf' 
                      AND CAST(t2.roster_id AS TEXT) = ANY(
                            STRING_TO_ARRAY('$result', ',')
                        ) 
                      AND (t2.main_supp_flag = 1 OR t2.main_supp_flag = 2)) h 
                INNER JOIN main m ON (h.diary_no = m.diary_no 
                AND h.next_dt = '$tdt1' AND h.mainhead = '$mf' 
                AND CAST(h.roster_id AS TEXT) = ANY(
                STRING_TO_ARRAY('$result', ',')
                ) 
                AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2))
                $left_join_verify
                LEFT JOIN cl_printed cl ON (cl.next_dt = h.next_dt 
                AND cl.m_f = h.mainhead AND cl.part = h.clno 
                AND cl.main_supp = h.main_supp_flag 
                AND cl.roster_id = h.roster_id AND cl.display = 'Y')
                WHERE $left_join_verify_whr cl.next_dt IS NOT NULL $whereStatus
                GROUP BY h.diary_no,tt.remark_id,tt.cl_dt,m.diary_no, h.judges,h.mainhead,h.board_type,h.next_dt,h.subhead,h.clno,h.brd_slno,h.heardt_ent_dt
                ,h.tentative_cl_dt,cl.next_dt,h.roster_id
                ORDER BY h.judges,CASE WHEN cl.next_dt IS NULL THEN 2 ELSE 1 END, h.brd_slno,
                CASE 
                    WHEN m.conn_key = CAST(h.diary_no AS TEXT) THEN '0000-00-00' 
                    ELSE TO_CHAR(m.fil_dt, 'YYYY-MM-DD') 
                END ASC";
    }

    private function buildMainQuery($result, $tdt1, $mf, $left_join_verify, $left_join_verify_whr, $whereStatus)
    {
        // Prepare your base query
        $query = "
        SELECT tt.remark_id, 
            tt.cl_dt, 
            SUBSTR(CAST(m.diary_no AS TEXT), 1, LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS case_no, 
            SUBSTR(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 3) AS year,
            TO_CHAR(m.fil_dt, 'DD-MM-YYYY HH12:MI PM') AS fil_dt_f,
            CASE 
                WHEN m.reg_year_mh = 0 THEN EXTRACT(YEAR FROM m.fil_dt) 
                ELSE m.reg_year_mh 
            END AS m_year,
            m.diary_no, m.reg_no_display, m.mf_active, m.conn_key, h.judges, 
            h.mainhead, h.board_type, h.next_dt, h.subhead, h.clno, h.brd_slno,
            h.heardt_ent_dt, h.tentative_cl_dt, m.pet_name, m.res_name, 
            m.pet_adv_id, m.res_adv_id, m.c_status, 
            CASE 
                WHEN cl.next_dt IS NULL THEN 'NA' 
                ELSE CAST(h.brd_slno AS TEXT)
            END AS brd_prnt,
            h.roster_id, 
            TO_CHAR(m.fil_dt_fh, 'DD-MM-YYYY HH12:MI PM') AS fil_dt_fh,
            CASE 
                WHEN m.reg_year_fh = 0 THEN EXTRACT(YEAR FROM m.fil_dt_fh) 
                ELSE m.reg_year_fh 
            END AS f_year
        FROM (
            SELECT t1.diary_no, t1.next_dt, t1.roster_id, t1.judges, 
                t1.mainhead, t1.board_type, t1.subhead, t1.clno, t1.brd_slno, 
                t1.ent_dt AS heardt_ent_dt, t1.main_supp_flag, 
                t1.tentative_cl_dt
            FROM heardt t1 
            WHERE t1.next_dt = '$tdt1' 
            AND t1.mainhead = '$mf' 
            " . (!empty($result) ? "AND CAST(t1.roster_id AS TEXT) = ANY(STRING_TO_ARRAY('$result', ','))" : "") . " 
            AND (t1.main_supp_flag = 1 OR t1.main_supp_flag = 2)
            
            UNION
            
            SELECT t2.diary_no, t2.next_dt, t2.roster_id, t2.judges, 
                t2.mainhead, t2.board_type, t2.subhead, t2.clno, t2.brd_slno, 
                t2.ent_dt AS heardt_ent_dt, t2.main_supp_flag, 
                t2.tentative_cl_dt 
            FROM last_heardt t2 
            WHERE t2.next_dt = '$tdt1' 
            AND t2.mainhead = '$mf' 
            " . (!empty($result) ? "AND CAST(t2.roster_id AS TEXT) = ANY(STRING_TO_ARRAY('$result', ','))" : "") . " 
            AND (t2.main_supp_flag = 1 OR t2.main_supp_flag = 2)
        ) h 
        INNER JOIN main m ON (h.diary_no = m.diary_no 
                            AND h.next_dt = '$tdt1' 
                            AND h.mainhead = '$mf' 
                            " . (!empty($result) ? "AND CAST(h.roster_id AS TEXT) = ANY(STRING_TO_ARRAY('$result', ','))" : "") . " 
                            AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
        )
        $left_join_verify
        LEFT JOIN cl_printed cl ON (cl.next_dt = h.next_dt 
                                    AND cl.m_f = h.mainhead 
                                    AND cl.part = h.clno 
                                    AND cl.main_supp = h.main_supp_flag 
                                    AND cl.roster_id = h.roster_id 
                                    AND cl.display = 'Y')
        WHERE $left_join_verify_whr cl.next_dt IS NOT NULL 
        $whereStatus
        GROUP BY h.diary_no, tt.remark_id, tt.cl_dt, m.diary_no, h.judges, h.mainhead, h.board_type, h.next_dt, h.subhead, h.clno, h.brd_slno, h.heardt_ent_dt, h.tentative_cl_dt, cl.next_dt, h.roster_id
        ORDER BY h.judges, 
                CASE WHEN cl.next_dt IS NULL THEN 2 ELSE 1 END, 
                h.brd_slno,
                CASE 
                    WHEN m.conn_key = CAST(h.diary_no AS TEXT) THEN '0000-00-00' 
                    ELSE TO_CHAR(m.fil_dt, 'YYYY-MM-DD') 
                END ASC
        ";
        return $query;
    }

    private function get_mf_code($mf)
    {
        switch ($mf) {
            case 'M':
                return '1';
            case 'F':
                return '2';
            case 'L':
                return '3';
            case 'S':
                return '4';
            default:
                return null;
        }
    }

    private function buildJudgeQuery1($jcd, $tdt1, $mf, $whereStatus)
    {

        return "SELECT SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) AS case_no, 
                SUBSTR(m.diary_no, -4) AS year, 
                DATE_FORMAT(m.fil_dt, '%d-%m-%Y %h:%i %p') AS fil_dt, 
                IF(m.reg_year_mh = 0, YEAR(m.fil_dt), m.reg_year_mh) AS m_year,
                m.diary_no, m.reg_no_display, m.mf_active, m.conn_key, h.judges, 
                h.mainhead, h.board_type, h.next_dt, h.subhead, s.stagename, 
                h.clno, h.brd_slno, h.tentative_cl_dt, m.pet_name, m.res_name, 
                m.pet_adv_id, m.res_adv_id, m.c_status, 
                IF(cl.next_dt IS NULL, 'NA', h.brd_slno) AS brd_prnt, 
                h.roster_id, DATE_FORMAT(m.fil_dt_fh, '%d-%m-%Y %h:%i %p') AS fil_dt_fh,
                IF(m.reg_year_fh = 0, YEAR(m.fil_dt_fh), m.reg_year_fh) AS f_year
                -- Add other fields as needed
                FROM (SELECT t1.diary_no, t1.next_dt, t1.roster_id, t1.judges, 
                      t1.mainhead, t1.board_type, t1.subhead, t1.clno, t1.brd_slno, 
                      t1.main_supp_flag, t1.tentative_cl_dt 
                      FROM heardt t1 WHERE t1.next_dt = '$tdt1' 
                      AND t1.mainhead = '$mf' 
                      AND FIND_IN_SET('$jcd', t1.judges) > 0 
                      AND (t1.main_supp_flag = 1 OR t1.main_supp_flag = 2)
                      UNION
                      SELECT t2.diary_no, t2.next_dt, t2.roster_id, t2.judges, 
                      t2.mainhead, t2.board_type, t2.subhead, t2.clno, t2.brd_slno, 
                      t2.main_supp_flag, t2.tentative_cl_dt 
                      FROM last_heardt t2 
                      WHERE t2.next_dt = '$tdt1' 
                      AND t2.mainhead = '$mf' 
                      AND FIND_IN_SET('$jcd', t2.judges) > 0 
                      AND (t2.main_supp_flag = 1 OR t2.main_supp_flag = 2) 
                      AND t2.bench_flag = '') h 
                INNER JOIN main m ON (h.diary_no = m.diary_no 
                AND h.next_dt = '$tdt1' AND h.mainhead = '$mf' 
                AND FIND_IN_SET('$jcd', h.judges) > 0 
                AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2))
                LEFT JOIN subheading s ON s.stagecode = h.subhead 
                AND s.display = 'Y' AND s.listtype = 'M'
                LEFT JOIN cl_printed cl ON (cl.next_dt = h.next_dt 
                AND cl.m_f = h.mainhead AND cl.part = h.clno 
                AND cl.main_supp = h.main_supp_flag 
                AND cl.roster_id = h.roster_id AND cl.display = 'Y')
                WHERE cl.next_dt IS NOT NULL $whereStatus
                ORDER BY FIND_IN_SET('$jcd', h.judges), 
                IF(brd_prnt = 'NA', 2, 1), h.brd_slno, 
                IF(m.conn_key = h.diary_no, '0000-00-00', m.fil_dt) ASC";
    }

    private function buildJudgeQuery($jcd, $tdt1, $mf, $whereStatus)
    {

        return "SELECT 
            SUBSTR(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4) AS case_no, 
            --SUBSTR(m.diary_no::text, -4) AS year, 
            SUBSTR(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 3) AS year,
            TO_CHAR(m.fil_dt, 'DD-MM-YYYY HH12:MI PM') AS fil_dt, 
            COALESCE(NULLIF(m.reg_year_mh, 0), EXTRACT(YEAR FROM m.fil_dt)) AS m_year,
            m.diary_no, 
            m.reg_no_display, 
            m.mf_active, 
            m.conn_key, 
            h.judges, 
            h.mainhead, 
            h.board_type, 
            h.next_dt, 
            h.subhead, 
            s.stagename, 
            h.clno, 
            h.brd_slno, 
            h.tentative_cl_dt, 
            m.pet_name, 
            m.res_name, 
            m.pet_adv_id, 
            m.res_adv_id, 
            m.c_status, 
            COALESCE(CAST(h.brd_slno AS text), 'NA') AS brd_prnt,  
            h.roster_id, 
            TO_CHAR(m.fil_dt_fh, 'DD-MM-YYYY HH12:MI PM') AS fil_dt_fh,
            COALESCE(NULLIF(m.reg_year_fh, 0), EXTRACT(YEAR FROM m.fil_dt_fh)) AS f_year
        FROM (
            SELECT 
                t1.diary_no, 
                t1.next_dt, 
                t1.roster_id, 
                t1.judges, 
                t1.mainhead, 
                t1.board_type, 
                t1.subhead, 
                t1.clno, 
                t1.brd_slno, 
                t1.main_supp_flag, 
                t1.tentative_cl_dt 
            FROM heardt t1 
            WHERE t1.next_dt = '$tdt1' 
                AND t1.mainhead = '$mf' 
                AND '$jcd' = ANY(string_to_array(t1.judges, ',')) 
                AND (t1.main_supp_flag = 1 OR t1.main_supp_flag = 2)
            
            UNION
            
            SELECT 
                t2.diary_no, 
                t2.next_dt, 
                t2.roster_id, 
                t2.judges, 
                t2.mainhead, 
                t2.board_type, 
                t2.subhead, 
                t2.clno, 
                t2.brd_slno, 
                t2.main_supp_flag, 
                t2.tentative_cl_dt 
            FROM last_heardt t2 
            WHERE t2.next_dt = '$tdt1' 
                AND t2.mainhead = '$mf' 
                AND '$jcd' = ANY(string_to_array(t2.judges, ',')) 
                AND (t2.main_supp_flag = 1 OR t2.main_supp_flag = 2) 
                AND t2.bench_flag = ''
        ) h 
        INNER JOIN main m ON (
            h.diary_no = m.diary_no 
            AND h.next_dt = '$tdt1' 
            AND h.mainhead = '$mf' 
            AND '$jcd' = ANY(string_to_array(h.judges, ',')) 
            AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
        )
        LEFT JOIN master.subheading s ON s.stagecode = h.subhead 
            AND s.display = 'Y' 
            AND s.listtype = 'M'
        LEFT JOIN cl_printed cl ON (
            cl.next_dt = h.next_dt 
            AND cl.m_f = h.mainhead 
            AND cl.part = h.clno 
            AND cl.main_supp = h.main_supp_flag 
            AND cl.roster_id = h.roster_id 
            AND cl.display = 'Y'
        )
        WHERE cl.next_dt IS NOT NULL $whereStatus
        ORDER BY 
            POSITION('$jcd' IN h.judges), 
            CASE WHEN h.brd_slno IS NULL THEN 2 ELSE 1 END, 
            h.brd_slno, 
            COALESCE(CAST(m.conn_key AS text), '0000-00-00', TO_CHAR(m.fil_dt, 'YYYY-MM-DD')) ASC";
    }

    public function get_case_type($diary_no)
    {
        $caseCode = intval(substr($diary_no, 2, 3));
        $builder = $this->db->table('master.casetype');
        $query = $builder->select('skey')
            ->where('casecode', $caseCode)
            ->where('display', 'Y');

        $result = $query->get()->getRowArray();
        return $result;
    }

    public function multiple_case_remarks($diary_no, $tdt1, $jcodes11)
    {
        $builder = $this->db->table('case_remarks_multiple');
        $query = $builder->select('r_head')
            ->where('diary_no', $diary_no)
            ->where('cl_date', $tdt1)
            ->where('jcodes', $jcodes11)
            ->where('remove', 0)
            ->orderBy('e_date', 'DESC');

        $result = $query->get()->getResultArray();
        return $result;
    }


    public function dispose_details($diary_no, $tdt1)
    {
        // Build the subquery for the 'dispose' table
        $builderA = $this->db->table('dispose');
        $subQuery = $builderA->select('*')
            ->where('diary_no', $diary_no)
            ->where('disp_dt', $tdt1)
            ->getCompiledSelect();

        // Build the main query for the 'disposal' table
        $builderB = $this->db->table('master.disposal b'); // Alias 'b' for disposal
        $finalQuery = $builderB->select('*')
            ->join("($subQuery) AS a", 'a.disp_type = b.dispcode', 'left') // Add alias 'a' here
            ->where('b.display', 'Y');

        // Execute the final query
        $result = $finalQuery->get()->getResult();
        return $result;
    }

    public function drop_details($row10, $tdt1)
    {
        $return = [];
        if (isset($row10["jud1"]) && isset($row10["jud1"]) && isset($tdt1)) {

            $subQuery = $this->db->table('judge')
                ->select("STRING_AGG(jname, ', ' ORDER BY CASE WHEN jsen = 0 THEN 99999 ELSE jsen END) AS jnm")
                ->where('jcode', $row10["jud1"])
                ->orWhere('jcode', $row10["jud2"])
                ->where('jcode !=', 0);

            // Main query
            $builder = $this->db->table('drop_note d');
            $builder->select('d.*, (' . $subQuery->getCompiledSelect() . ') AS jnm')
                ->where('d.diary_no', $row10["diary_no"])
                ->where('d.display', 'Y')
                ->where('d.cl_date', date("Y-m-d", strtotime($tdt1)))
                ->where('d.clno', $row10["brd_slno"])
                ->where('d.jud1', $row10["jud1"])
                ->where('d.jud2', $row10["jud2"])
                ->orderBy('d.ent_dt', 'ASC');
            $result = $builder->get()->getResultArray();
            $return = $result;
        }
        return $return;
    }

    public function jo_alottment_paps($diary_no, $tdt1)
    {
        $subQuery = $this->db->table('master.users')
            ->select('name')
            ->where('usercode = a.usercode')
            ->where('display', 'Y');

        $builder = $this->db->table('jo_alottment_paps a');
        $builder->select('a.usercode, (' . $subQuery->getCompiledSelect() . ') as uname')
            ->where('a.diary_no', $diary_no)
            ->where('a.display', 'Y')
            ->where('a.cl_date', date("Y-m-d", strtotime($tdt1)));

        $result = $builder->get()->getResultArray();
        return $result;
    }

    public function ordernet($main_case_no, $tdt1)
    {
        $subQuery = $this->db->table('ordernet o')
            ->select('o.diary_no AS diary_no, o.pdfname AS jm, TO_CHAR(o.orderdate, \'YYYY-MM-DD\') AS dated, 
            CASE 
                WHEN o.type = \'O\' THEN \'ROP\'
                WHEN o.type = \'J\' THEN \'Judgement\'
            END AS jo')
            ->where('o.diary_no', $main_case_no)
            ->where('o.orderdate', $tdt1);

        // Main query
        $builder = $this->db->table('(' . $subQuery->getCompiledSelect() . ') AS tbl1');
        $builder->select('diary_no, jm AS pdfname, dated AS orderdate')
            ->where('jo', 'ROP')
            ->orderBy('dated', 'DESC');
        $result = $builder->get()->getResultArray();
        return $result;
    }

    public function get_brdrem($diary_no)
    {
        $builder = $this->db->table('brdrem');
        $query = $builder->select('*')
            ->where('diary_no', $diary_no)
            ->get();

        $result = $query->getRowArray();
        return $result;
    }

    public function get_subheading($subhead)
    {
        $builder = $this->db->table('master.subheading');
        $query = $builder->select('*')
            ->where('stagecode', $subhead)
            ->where('display', 'Y')
            ->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function showlcd($court, $tdt1)
    {
        $builder = $this->db->table('showlcd');
        $query = $builder->select('mf, clno')
            ->where('court', $court)
            ->where('cl_dt', $tdt1)
            ->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function get_rhead($diary_no, $cl_date)
    {
        $builder = $this->db->table('case_remarks_multiple');
        $query = $builder->select('r_head')
            ->where('diary_no', $diary_no)
            ->where('cl_date <', $cl_date)
            ->where('remove', 0)
            ->whereIn('r_head', ['150', '151'])
            ->orderBy('cl_date', 'DESC')
            ->limit(1)
            ->get();
        $result = $query->getRowArray(); // Use getRow() for a single result
        return $result;
    }

    public function get_head_deails($diary_no, $cl_date, $jcodes)
    {
        $builder = $this->db->table('case_remarks_multiple c');
        $builder->select('c.r_head, h.side, c.head_content, h.head')
            ->join('master.case_remarks_head h', 'c.r_head = h.sno', 'inner')
            ->where('c.diary_no', $diary_no)
            ->where('c.cl_date', $cl_date)
            ->where('c.jcodes', $jcodes)
            ->where('c.remove', 0)
            ->orderBy('c.e_date', 'desc');

        $query = $builder->get();
        $result = $query->getResultArray(); // Use getResult() to get all results
        return $result;
    }

    public function heardt_board_type($diary_no, $tdt1)
    {
        $builder = $this->db->table('heardt h');
        $builder->select('u.name, u.empid, h.mainhead, h.board_type, s.stagename, l.purpose, h.tentative_cl_dt')
            ->join('master.users u', 'u.usercode = h.usercode AND u.display = \'Y\'', 'left')
            ->join('master.listing_purpose l', 'l.code = h.listorder AND l.display = \'Y\'', 'left')
            ->join('master.subheading s', 's.stagecode = h.subhead AND s.display = \'Y\' AND s.listtype = \'M\'', 'left')
            ->where('h.diary_no', $diary_no)
            ->where('h.next_dt !=', $tdt1)
            ->where('h.clno', 0)
            ->where('h.brd_slno', 0);

        $query = $builder->get();
        $result = $query->getRowArray();
        return $result;
    }

    public function case_verify_rop($diary_no, $tdt1)
    {
        $builder = $this->db->table('case_verify_rop cv');
        $builder->select('STRING_AGG(cr.remarks, \', \') as rem_dtl, cv.ent_dt')
            ->join('master.case_verify_by_sec_remark cr', 'cr.id = ANY(string_to_array(cv.remark_id, \',\')::int[])', 'left')
            ->where('cv.diary_no', $diary_no)
            ->where('cv.cl_dt >=', $tdt1)
            ->groupBy('cv.id');
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function case_verify_by_sec_remark()
    {
        $builder = $this->db->table('master.case_verify_by_sec_remark');
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }


    public function get_docdetails($diary_no)
    {
        $builder = $this->db->table('docdetails a');
        $builder->join('master.docmaster b', 'a.doccode = b.doccode AND a.doccode1 = b.doccode1');
        $builder->select('a.diary_no, a.doccode, a.doccode1, a.docnum, a.docyear, a.filedby, a.docfee, a.forresp, a.feemode, a.ent_dt, a.other1, a.iastat, b.docdesc, DATE(a.lst_mdf) as lstmdf');
        $builder->where('a.diary_no', $diary_no);
        $builder->where('a.doccode', 8);
        $builder->orderBy('a.ent_dt');
        $result = $builder->get()->getResultArray();
        return $result;
    }



    public function get_last_heardt($diaryNo)
    {
        $builderA = $this->db->table('last_heardt lh');
        $builderA->select('lh.diary_no AS filno, lh.next_dt AS cldate')
            ->where('lh.diary_no', $diaryNo)
            ->where('lh.mainhead', 'F')
            ->where('lh.clno >', 0)
            //->where('lh.jud1 !=', 200)
            //->where('lh.jud1 !=', 250)
            ->where('lh.next_dt <', date('Y-m-d')); // Using current date

        $builderB = $this->db->table('case_remarks_multiple cr');
        $builderB->select('CAST(cr.diary_no AS bigint), cr.cl_date AS cldate')
            ->where('cr.diary_no', $diaryNo)
            ->whereIn('cr.r_head', [81, 74, 75, 65, 2, 1, 94]);

        $combinedQuery = $builderA->getCompiledSelect() . " UNION " . $builderB->getCompiledSelect();
        $finalBuilder = $this->db->query("SELECT filno, TO_CHAR(cldate, 'DD-MM-YYYY') AS cldate FROM ($combinedQuery) AS z1 GROUP BY filno, z1.cldate ORDER BY cldate");
        $result = $finalBuilder->getRowArray();
        return $result;
    }

    public function get_showlcd($jcourt, $dt_t1)
    {
        $builder = $this->db->table('showlcd');
        $builder->select('msg')
            ->where('court', $jcourt)
            ->where('cl_dt', $dt_t1);
        $result = $builder->get()->getRowArray();
        return $result;
    }

    public function save_case_verify_rop($dno, $ucode, $remark)
    {
        $return = false;
        if (!empty($dno)) {
            $str_explo = explode("_", $dno);
            $dno = $str_explo[0];
            $board_type = $str_explo[1];
            $mainhead = $str_explo[2];
            $next_dt = $str_explo[3];
            $court = $str_explo[4];
            $t_dt = $str_explo[5];
            $data = [
                'diary_no'       => $dno,
                'cl_dt'          => $next_dt,
                'm_f'            => $mainhead,
                'board_type'     => $board_type,
                'ent_dt'         => date('Y-m-d H:i:s'),
                'ucode'          => $ucode,
                'remark_id'      => implode(',', $remark),
                'tentative_dt'   => $t_dt,
                'court'          => $court,
                'create_modify' => date("Y-m-d H:i:s"),
                'updated_by' => session()->get('login')['usercode'],
                'updated_by_ip' => getClientIP()
            ];

            $builder = $this->db->table('case_verify_rop');
            $is_saved = $builder->insert($data);
            if ($is_saved) {
                $builder = $this->db->table('master.users');
                $query = $builder->where('usercode', $ucode)->get();
                $user = $query->getRowArray();
                $username_empid = $user['name'] . " [" . $user['empid'] . "] ";

                $builder = $this->db->table('main m');
                $builder->select('reg_no_display, m.diary_no, m.dacode, u.empid')
                    ->join('master.users u', 'u.usercode = m.dacode')
                    ->where('m.diary_no', $dno);

                $ressel = $builder->get()->getRowArray();
                $arrays = array_diff($remark, ["1"]);
                $array = [];
                $i = 0;
                foreach ($arrays as $k => $item) {
                    $array[$i] = $item;
                    unset($arrays[$k]);
                    $i++;
                }

                $remarks_join = "";
                if (count($array) > 0) {
                    for ($k = 0; $k < count($array); $k++) {
                        $builder = $this->db->table('master.case_verify_by_sec_remark');
                        $builder->select('remarks')->where('id', $array[$k]);
                        $query = $builder->get();
                        $result = $query->getRow();

                        // Access the remarks
                        $remarks = $result ? $result->remarks : null;
                        $remarks_join .=  $remarks . ",";
                    }

                    $remarks_join = rtrim($remarks_join, ",");
                    if (!empty($ressel)) {
                        $msg = " AS PER COURT REMARKS FOR DATED " . date("d-m-Y", strtotime($next_dt)) . " IN ";
                        if ($ressel['reg_no_display']) {
                            $msg .= " CASE NO. " . $ressel['reg_no_display'];
                        }
                        $msg .= " DIARY NO. " . substr_replace($ressel['diary_no'], '-', -4, 0);
                        $msg .= " FOLLOWING DEFECTS RAISED BY MONITORING TEAM " . $username_empid . " : " . $remarks_join;
                        $empcode = $ressel['empid'];
                        $data = [
                            'to_user' => $empcode,
                            'from_user' => $user['empid'],
                            'msg' => $msg,
                            'ipadd' => 0,
                            'create_modify' => date("Y-m-d H:i:s"),
                            'updated_by' => session()->get('login')['usercode'],
                            'updated_by_ip' => getClientIP()
                        ];

                        $builder = $this->db->table('msg');
                        $builder->insert($data);
                    }
                }
            }
            $return = true;
        }
        return $return;
    }



    public function verify_report($list_dt)
    {
        //remove vkg
        //$list_dt = '2018-02-06';
        $list_dt = date('Y-m-d',strtotime($list_dt));
        $builder = $this->db->table('case_verify cv');
        $builder->select('
            u.name, 
            cv.ucode, 
            COUNT(*) AS tot,
            COUNT(CASE WHEN cv.remark_id = \'1\' THEN 1 END) AS accepted,
            COUNT(CASE WHEN cv.remark_id != \'1\' OR cv.remark_id IS NULL OR cv.remark_id = \'\' THEN 1 END) AS not_accepted
        ');
        $builder->join('master.users u', 'u.usercode = cv.ucode', 'inner');
        $builder->where('cv.display', 'Y');
        $builder->where('cv.ent_dt >=', $list_dt . ' 00:00:00');
        $builder->where('cv.ent_dt <', $list_dt . ' 23:59:59');
        $builder->groupBy('cv.ucode, u.name');
        $builder->orderBy('u.name');
        // echo $builder->getCompiledSelect();
        // die();
        $query = $builder->get();
        return $query->getResultArray();
    }


    public function f_get_docdetail($diary_numbers)
    {
        if (!is_array($diary_numbers)) {
            $diary_numbers = explode(',', $diary_numbers);
        }

        $builder = $this->db->table('docdetails dd')
            ->select('dm.docdesc, dd.diary_no')
            ->join('master.docmaster dm', 'dd.doccode1 = dm.doccode1 AND dd.doccode = dm.doccode', 'left')
            ->whereIn('dd.diary_no', $diary_numbers)  // This line expects an array
            ->where('dd.doccode', '8')
            ->where('dm.doccode', '8')
            ->where('dd.iastat', 'P')
            ->where('dd.display', 'Y')
            ->where('dm.display', 'Y')
            ->where('dm.docdesc !=', 'XTRA')
            ->groupBy('dm.docdesc, dd.diary_no');

        $query = $builder->get();
        $results = $query->getResultArray();

        $doc_results = [];
        foreach ($results as $result) {
            $doc_results[$result['diary_no']][] = $result;
        }

        return $doc_results;
    }


    public function get_cl_brd_remark($diary_numbers)
    {
        $builder = $this->db->table('brdrem')
            ->select('remark, diary_no')
            ->whereIn('diary_no', $diary_numbers);
        $query = $builder->get();
        $results = $query->getResultArray();
        $remark_results = [];
        foreach ($results as $result) {
            $remark_results[$result['diary_no']] = $result;
        }
        return $remark_results;
    }

    public function da_case_distribution($casetype_displ, $ten_reg_yr, $ref_agency_state_id)
    {
        $builder = $this->db->table('master.da_case_distribution a');
        $builder->select('a.dacode, c.section_name, b.name')
            ->join('master.users b', 'b.usercode = a.dacode', 'left')
            ->join('master.usersection c', 'b.section = c.id', 'left')
            ->where('a.case_type', $casetype_displ)
            ->where("$ten_reg_yr BETWEEN a.case_f_yr AND a.case_t_yr")
            ->where('a.state', $ref_agency_state_id)
            ->where('a.display', 'Y');

        $query = $builder->get();
        $results = $query->getRowArray();
        return $results;
    }


    public function save_case_verify($dno, $ucode, $remark)
    {
        $return = false;
        if (!empty($dno)) {
            $str_explo = explode("_", $dno);
            $dno = $str_explo[0];
            $board_type = $str_explo[1];
            $mainhead = $str_explo[2];
            $next_dt = $str_explo[3];
            $data = [
                'diary_no' => $dno,
                'next_dt' => $next_dt,
                'm_f' => $mainhead,
                'board_type' => $board_type,
                'ent_dt' => date('Y-m-d H:i:s'),
                'ucode' => $ucode,
                'remark_id' => implode(',', $remark),
                'create_modify' => date("Y-m-d H:i:s"),
                'updated_by' => session()->get('login')['usercode'],
                'updated_by_ip' => getClientIP()
            ];

            $builder = $this->db->table('case_verify');
            $is_saved = $builder->insert($data);
            if ($is_saved) {
                $return = true;
            }
        }
        return $return;
    }

    public function getUserName($userid_str)
    {
        $builder = $this->db->table('master.users u');
        $builder->select('name')
            ->where('usercode', $userid_str);
        $query = $builder->get();
        return $query->getRowArray();
    }


    public function getAllDa($ucode)
    {
        $builder = $this->db->table('master.users u');
        $builder->select('GROUP_CONCAT(u2.usercode) as allda');
        $builder->join('master.users u2', 'u2.section = u.section', 'left');
        $builder->where('u.display', 'Y');
        $builder->where('u.usercode', $ucode);
        $builder->groupBy('u2.section');
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function getROPPurpose($sec_id, $userid_str, $list_dt, $mdacode, $sec_id2, $condition)
    {
        $sql="SELECT DISTINCT ON (m.diary_no)
                        tt.remark_id,
                        m.diary_no,
                        h.next_dt,
                        u.name,
                        COALESCE(us.section_name, tentative_section(m.diary_no)) AS section_name,
                        m.conn_key AS main_key,
                        l.purpose,
                        s.stagename,
                        h.coram,
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
                        m.lastorder,
                        m.pno,
                        m.rno,
                        m.diary_no_rec_date,
                        CASE
                            WHEN (NULLIF(m.conn_key, '') IS NULL OR NULLIF(m.conn_key, '') ~ '^[0-9]+$' AND NULLIF(m.conn_key, '')::INTEGER = m.diary_no OR m.conn_key = '0')
                            THEN 0 ELSE 1
                        END AS main_or_connected,
                        (
                            SELECT CASE WHEN diary_no IS NOT NULL THEN 1 ELSE 0 END
                            FROM conct
                            WHERE diary_no = m.diary_no AND list = 'Y'
                        ) AS listed,
                        tt.ent_dt AS verify_dt
                    FROM main m
                    INNER JOIN public.heardt h ON h.diary_no = m.diary_no
                    INNER JOIN case_verify tt ON tt.diary_no = h.diary_no
                    LEFT JOIN master.listing_purpose l ON l.code = h.listorder AND l.display = 'Y'
                    LEFT JOIN master.subheading s ON s.stagecode = h.subhead AND s.display = 'Y' AND s.listtype = 'M'
                    LEFT JOIN master.casetype c1 ON m.active_casetype_id = c1.casecode
                    LEFT JOIN master.users u ON u.usercode = m.dacode AND u.display = 'Y'
                    LEFT JOIN master.usersection us ON us.id = u.section $sec_id
                    WHERE tt.display = 'Y'
                    AND tt.ucode = '$userid_str'
                    AND CAST(tt.ent_dt AS DATE) = TO_DATE('$list_dt', 'DD-MM-YYYY')
                    $mdacode $sec_id2
                    $condition 
                    AND (
                        TRIM(LEADING '0' FROM SPLIT_PART(m.fil_no, '-', 1)) ~ '^[0-9]+$'
                        AND TRIM(LEADING '0' FROM SPLIT_PART(m.fil_no, '-', 1))::INT
                        IN (3, 15, 19, 31, 23, 24, 40, 32, 34, 22, 39, 11, 17, 13, 1, 7, 37, 9999, 38, 5, 21, 27, 4, 16, 20, 18, 33, 41, 35, 36, 28, 12, 14, 2, 8, 6)
                        OR m.active_fil_no IS NULL OR m.active_fil_no = ''
                    )
                    AND (
                        CASE
                            WHEN (NULLIF(m.conn_key, '') IS NULL OR NULLIF(m.conn_key, '') ~ '^[0-9]+$' AND NULLIF(m.conn_key, '')::INTEGER = m.diary_no OR m.conn_key = '0')
                            THEN TRUE
                            ELSE EXISTS (
                                SELECT 1 FROM conct c WHERE c.diary_no = m.diary_no 
                                AND c.conn_key IN (
                                    SELECT diary_no FROM public.heardt t1 WHERE t1.next_dt = h.next_dt
                                )
                            )
                        END
                    )
                    ORDER BY 
                        m.diary_no,    RIGHT(COALESCE(NULLIF(m.conn_key, ''), m.diary_no::TEXT), 4),
                        COALESCE(
                            CASE WHEN NULLIF(m.conn_key, '') ~ '^[0-9]+$' THEN NULLIF(m.conn_key, '')::INTEGER ELSE NULL END,
                            m.diary_no
                        ),
                        CASE WHEN NULLIF(m.conn_key, '') ~ '^[0-9]+$' AND NULLIF(m.conn_key, '')::INTEGER = m.diary_no THEN 0 ELSE 1 END,
                        main_or_connected ASC";


        $query = $this->db->query($sql);
        return $query->getResultArray();
    }

    public function getEntDate($dno)
    {
        $builder = $this->db->table('case_verify');
        $builder->selectMax('ent_dt', 'max_edt');
        $builder->where('diary_no', $dno);
        $query = $builder->get();

        return $query->getRowArray();
    }

    public function getCatdata($dno)
    {
        $builder = $this->db->table('mul_category mc');
        $builder->select('category_sc_old');
        $builder->join('master.submaster s', 's.id = mc.submaster_id', 'inner');
        $builder->where('mc.display', 'Y');
        $builder->where('mc.diary_no', $dno);
        $query = $builder->get();

        return $query->getResultArray();
    }

    public function getAdv($diary_no)
    {
        $advsql = "SELECT 
                        a.diary_no,
                        STRING_AGG(
                            a.name || COALESCE(CASE WHEN pet_res = 'R' THEN grp_adv END, ''),
                            ',' ORDER BY adv_type DESC, pet_res_no ASC
                        ) AS r_n,
                        STRING_AGG(
                            a.name || COALESCE(CASE WHEN pet_res = 'P' THEN grp_adv END, ''),
                            ',' ORDER BY adv_type DESC, pet_res_no ASC
                        ) AS p_n,
                        STRING_AGG(
                            a.name || COALESCE(CASE WHEN pet_res = 'I' THEN grp_adv END, ''),
                            ',' ORDER BY adv_type DESC, pet_res_no ASC
                        ) AS i_n
                    FROM (
                        SELECT 
                            a.diary_no, 
                            b.name, 
                            STRING_AGG(
                                COALESCE(a.adv, ''),
                                ',' ORDER BY CASE WHEN pet_res = 'I' THEN 99 ELSE 0 END ASC, adv_type DESC, pet_res_no ASC
                            ) AS grp_adv, 
                            a.pet_res, 
                            a.adv_type, 
                            a.pet_res_no
                        FROM advocate a 
                        LEFT JOIN master.bar b 
                            ON a.advocate_id = b.bar_id 
                            AND b.isdead != 'Y' 
                        WHERE a.diary_no = '$diary_no' 
                            AND a.display = 'Y' 
                        GROUP BY a.diary_no, b.name, a.pet_res, a.adv_type, a.pet_res_no
                        ORDER BY CASE WHEN pet_res = 'I' THEN 99 ELSE 0 END ASC, adv_type DESC, pet_res_no ASC
                    ) a 
                    GROUP BY a.diary_no";

        $query = $this->db->query($advsql);
        return $query->getRowArray();
    }

    public function getAdv_builder($diary_no)
    {

        $builder = $this->db->table('advocate a');
        $builder->select(
            '
            a.diary_no, 
            b.name, 
            STRING_AGG(COALESCE(a.adv, \'\'), \',\' ORDER BY CASE WHEN pet_res = \'I\' THEN 99 ELSE 0 END ASC, adv_type DESC, pet_res_no ASC) AS grp_adv, 
            a.pet_res, 
            a.adv_type, 
            a.pet_res_no'
        );
        $builder->join('master.bar b', 'a.advocate_id = b.bar_id AND b.isdead != \'Y\'', 'left');
        $builder->where('a.diary_no', $diary_no);
        $builder->where('a.display', 'Y');
        $builder->groupBy('a.diary_no', 'b.name', 'a.pet_res', 'a.adv_type', 'a.pet_res_no');
        $innerQuery = $builder->getCompiledSelect();
        $outerBuilder = $this->db->query("
            SELECT 
                a.diary_no,
                STRING_AGG(a.name || COALESCE(CASE WHEN pet_res = 'R' THEN grp_adv END, ''), ',' ORDER BY adv_type DESC, pet_res_no ASC) AS r_n,
                STRING_AGG(a.name || COALESCE(CASE WHEN pet_res = 'P' THEN grp_adv END, ''), ',' ORDER BY adv_type DESC, pet_res_no ASC) AS p_n,
                STRING_AGG(a.name || COALESCE(CASE WHEN pet_res = 'I' THEN grp_adv END, ''), ',' ORDER BY adv_type DESC, pet_res_no ASC) AS i_n
            FROM ($innerQuery) a
            GROUP BY a.diary_no
        ");
        return $outerBuilder->getRowArray();
    }



    public function getSectionTenRs($casetype_displ, $ten_reg_yr, $ref_agency_state_id)
    {
        $builder = $this->db->table('master.da_case_distribution a');
        $builder->select('dacode, section_name, name');
        $builder->join('master.users b', 'b.usercode = a.dacode', 'left');
        $builder->join('master.usersection c', 'b.section = c.id', 'left');
        $builder->where('a.case_type', $casetype_displ);
        $builder->where("$ten_reg_yr BETWEEN a.case_f_yr AND a.case_t_yr");
        $builder->where('a.state', $ref_agency_state_id);
        $builder->where('a.display', 'Y');
        pr($builder->getCompiledSelect());
        die;
        $query = $builder->get();
        return $query->getRowArray();
    }

   

    public function getPdfName($dno)
    {
        $builder1 = $this->db->table('tempo o');
        $builder1->select('
        o.diary_no,
        o.jm,
        TO_CHAR(TO_DATE(o.dated, \'YYYY-MM-DD\'), \'YYYY-MM-DD\') AS orderdate,
        CASE 
            WHEN o.jt = \'rop\' THEN \'ROP\'
            WHEN o.jt = \'judgment\' THEN \'Judgement\'
            WHEN o.jt = \'or\' THEN \'Office Report\'
        END AS jo
        ');
        $builder1->where('o.diary_no', $dno);
        $builder2 = $this->db->table('ordernet o');
        $builder2->select('
        o.diary_no,
        o.pdfname AS jm,
        TO_CHAR(o.orderdate, \'YYYY-MM-DD\') AS orderdate,
        CASE
            WHEN o.type = \'O\' THEN \'ROP\'
            WHEN o.type = \'J\' THEN \'Judgement\'
        END AS jo
        ');
        $builder2->where('o.diary_no', $dno);
        $builder3 = $this->db->table('rop_text_web.old_rop o');
        $builder3->select('
        o.dn AS diary_no,
        CONCAT(\'ropor/rop/all/\', o.pno, \'.pdf\') AS jm,
        TO_CHAR(o.orderDate, \'YYYY-MM-DD\') AS orderdate,
        \'ROP\' AS jo
        ');
        $builder3->where('o.dn', $dno);
        $builder4 = $this->db->table('scordermain o');
        $builder4->select('
        o.dn AS diary_no,
        CONCAT(\'judis/\', o.filename, \'.pdf\') AS jm,
        TO_CHAR(TO_DATE(o.juddate, \'YYYY-MM-DD\'), \'YYYY-MM-DD\') AS orderdate,
        \'Judgment\' AS jo
        ');
        $builder4->where('o.dn', $dno);
        $builder5 = $this->db->table('rop_text_web.ordertext o');
        $builder5->select('
        o.dn AS diary_no,
        CONCAT(\'bosir/orderpdf/\', o.pno, \'.pdf\') AS jm,
        TO_CHAR(TO_DATE(o.orderdate, \'YYYY-MM-DD\'), \'YYYY-MM-DD\') AS orderdate,
        \'ROP\' AS jo
        ');
        $builder5->where('o.dn', $dno);
        $builder5->where('o.display', 'Y');
        $builder6 = $this->db->table('rop_text_web.oldordtext o');
        $builder6->select('
        o.dn AS diary_no,
        CONCAT(\'bosir/orderpdfold/\', o.pno, \'.pdf\') AS jm,
        TO_CHAR(TO_DATE(o.orderdate, \'YYYY-MM-DD\'), \'YYYY-MM-DD\') AS orderdate,
        \'ROP\' AS jo
        ');
        $builder6->where('o.dn', $dno);
        $query = $this->db->query("
        SELECT diary_no, jm AS pdfname, orderdate
        FROM (
            {$builder1->getCompiledSelect()}
            UNION 
            {$builder2->getCompiledSelect()}
            UNION 
            {$builder3->getCompiledSelect()}
            UNION 
            {$builder4->getCompiledSelect()}
            UNION 
            {$builder5->getCompiledSelect()}
            UNION 
            {$builder6->getCompiledSelect()}
        ) tbl1 
        WHERE jo = 'ROP'
        ORDER BY tbl1.orderdate DESC
        ");

        return $query->getResultArray();
    }
    public function getRemDtl($remark_id)
    {
        $builder = $this->db->table('master.case_verify_by_sec_remark');
        $builder->select('string_agg(remarks, \',\') AS rem_dtl');
        $builder->where("array_position(string_to_array('$remark_id', ','), id::text) >", 0);
        $query = $builder->get();
        return $query->getRowArray();
    }
}
