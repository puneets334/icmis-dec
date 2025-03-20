<?php

namespace App\Models\Listing;

use CodeIgniter\Model;


class SpreadOutCertificateModel extends Model
{

    public function getCaseType($list_dt, $list_dt_to)
    { 
        $builder = $this->db->table('master.subheading s');
        $builder->select("
                s.stagecode, 
                s.stagename, 
                s.priority,
                COALESCE(b.listed, 0) AS listed,
                COALESCE(b.fd_list, 0) AS fd_list,
                COALESCE(b.aw_list, 0) AS aw_list,
                COALESCE(b.frs_list, 0) AS frs_list,
                COALESCE(b.imp_ia_list, 0) AS imp_ia_list,
                COALESCE(b.oth_list, 0) AS oth_list,
                COALESCE(c.not_listed, 0) AS not_listed,
                COALESCE(c.fd_not_list, 0) AS fd_not_listed,
                COALESCE(c.aw_not_list, 0) AS aw_not_listed,
                COALESCE(c.frs_not_list, 0) AS frs_not_listed,
                COALESCE(c.imp_ia_not_list, 0) AS imp_ia_not_listed,
                COALESCE(c.oth_not_list, 0) AS oth_not_listed
            ");

        // Left Join b subquery
        $builder->join("
            (
                SELECT 
                    subhead,
                    COUNT(subhead) AS listed,
                    SUM(CASE WHEN (listorder = 4 OR listorder = 5 OR listorder = 7) THEN 1 ELSE 0 END) AS fd_list,
                    SUM(CASE WHEN (listorder = 8) THEN 1 ELSE 0 END) AS aw_list,
                    SUM(CASE WHEN (listorder = 25 OR listorder = 32) THEN 1 ELSE 0 END) AS frs_list,
                    SUM(CASE WHEN doccode1 IS NOT NULL AND listorder NOT IN (8, 4, 5, 7, 25, 32) THEN 1 ELSE 0 END) AS imp_ia_list,
                    SUM(CASE WHEN doccode1 IS NULL AND listorder NOT IN (8, 4, 5, 7, 25, 32) THEN 1 ELSE 0 END) AS oth_list
                FROM
                (
                    SELECT
                        d.doccode1,
                        h.diary_no,
                        h.subhead,
                        h.listorder,
                        next_dt
                    FROM heardt h
                    INNER JOIN main m ON m.diary_no = h.diary_no
                    LEFT JOIN docdetails d ON d.diary_no = m.diary_no
                        AND d.display = 'Y'
                        AND d.iastat = 'P'
                        AND d.doccode = 8
                        AND d.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 226, 3, 309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 71, 72, 102, 118, 131, 211, 309)
                    WHERE
                        (m.diary_no::char = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
                        AND next_dt BETWEEN next_dt AND next_dt
                        AND clno > 0
                        AND brd_slno > 0
                        AND h.board_type = 'J'
                        AND h.subhead IN (824, 810, 803, 802, 807, 804, 808, 811, 812, 813, 814, 815, 816)
                    GROUP BY h.diary_no, d.doccode1
                ) a
                GROUP BY subhead
            ) b", 's.stagecode = b.subhead', 'left');
        $builder->join("
            (
                SELECT
                    th.subhead,
                    COUNT(th.diary_no) AS not_listed,
                    SUM(CASE WHEN (th.listorder = 4 OR th.listorder = 5 OR th.listorder = 7) THEN 1 ELSE 0 END) AS fd_not_list,
                    SUM(CASE WHEN (th.listorder = 8) THEN 1 ELSE 0 END) AS aw_not_list,
                    SUM(CASE WHEN (th.listorder = 25 OR th.listorder = 32) THEN 1 ELSE 0 END) AS frs_not_list,
                    SUM(CASE WHEN doccode1 IS NOT NULL AND th.listorder NOT IN (8, 4, 5, 7, 25, 32) THEN 1 ELSE 0 END) AS imp_ia_not_list,
                    SUM(CASE WHEN doccode1 IS NULL AND th.listorder NOT IN (8, 4, 5, 7, 25, 32) THEN 1 ELSE 0 END) AS oth_not_list
                FROM
                (
                    SELECT
                        d.doccode1,
                        h.subhead,
                        h.listorder,
                        h.diary_no
                    FROM heardt h
                    INNER JOIN main m ON m.diary_no = h.diary_no
                    LEFT JOIN docdetails d ON d.diary_no = m.diary_no
                        AND d.display = 'Y'
                        AND d.iastat = 'P'
                        AND d.doccode = 8
                        AND d.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 226, 3, 309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 71, 72, 102, 118, 131, 211, 309)
                    WHERE
                        m.c_status = 'P'
                        AND (m.diary_no::char = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
                        AND next_dt BETWEEN '$list_dt' AND '$list_dt_to'
                        AND h.board_type = 'J'
                        AND h.subhead IN (824, 810, 803, 802, 807, 804, 808, 811, 812, 813, 814, 815, 816)
                    GROUP BY h.diary_no, d.doccode1, h.subhead, h.listorder
                ) th
                GROUP BY th.subhead
            ) c", 's.stagecode = c.subhead', 'left');
        $builder->where('s.listtype', 'M');
        $builder->where('s.board_type', 'J');
        $builder->where('s.display', 'Y');
        $builder->whereNotIn('s.stagecode', [817, 818, 819]);
        $builder->groupBy('s.stagecode, b.listed, b.fd_list, b.aw_list, b.frs_list, b.imp_ia_list, b.oth_list, c.not_listed, c.fd_not_list, c.aw_not_list, c.frs_not_list, c.imp_ia_not_list, c.oth_not_list');
        $builder->orderBy('s.priority');
        //echo $builder->getCompiledSelect();
          //  die();
        $query = $builder->get();
        $result = $query->getResultArray();
        
        return $result;
    }

    public function getCaseNotListedReason($list_dt, $list_dt_to)
    {
        $sql="SELECT 
            SUM(CASE WHEN t.listorder IN (4,5,7) AND  fil_no2 IS NOT NULL THEN 1 ELSE 0 END) fd_depend_on_other_diary,
            SUM(CASE WHEN t.listorder IN (4,5,7) AND notbef = 'B' THEN 1 ELSE 0 END) fd_before_jud, 
            SUM(CASE WHEN t.listorder IN (4,5,7) AND notbef = 'N' THEN 1 ELSE 0 END) fd_not_before_jud,
            SUM(CASE WHEN t.listorder IN (4,5,7) AND submaster_id = '331' THEN 1 ELSE 0 END) fd_defect_cat,
            SUM(CASE WHEN t.listorder IN (4,5,7) AND submaster_id IN (239,240,241,242,243,824,914) THEN 1 ELSE 0 END) fd_const_spl_bench,
            SUM(CASE WHEN t.listorder IN (4,5,7) AND submaster_id IS NULL THEN 1 ELSE 0 END) fd_cat_blank,
            SUM(CASE WHEN t.listorder IN (4,5,7) AND submaster_id IN (343,15,16,17,18,19,20,21,22,23,341,353,157,158,159,160,161,162,163,166,173,175,176,322,222) THEN 1 ELSE 0 END) fd_short_cat,
            SUM(CASE WHEN t.listorder IN (4,5,7) THEN 1 ELSE 0 END) fd_tot,

            SUM(CASE WHEN t.listorder IN (8) AND  fil_no2 IS NOT NULL THEN 1 ELSE 0 END) aw_depend_on_other_diary,
            SUM(CASE WHEN t.listorder IN (8) AND notbef = 'B' THEN 1 ELSE 0 END) aw_before_jud, 
            SUM(CASE WHEN t.listorder IN (8) AND notbef = 'N' THEN 1 ELSE 0 END) aw_not_before_jud,
            SUM(CASE WHEN t.listorder IN (8) AND submaster_id = '331' THEN 1 ELSE 0 END) aw_defect_cat,
            SUM(CASE WHEN t.listorder IN (8) AND submaster_id IN (239,240,241,242,243,824,914) THEN 1 ELSE 0 END) aw_const_spl_bench,
            SUM(CASE WHEN t.listorder IN (8) AND submaster_id IS NULL THEN 1 ELSE 0 END) aw_cat_blank,
            SUM(CASE WHEN t.listorder IN (8) AND submaster_id IN (343,15,16,17,18,19,20,21,22,23,341,353,157,158,159,160,161,162,163,166,173,175,176,322,222) THEN 1 ELSE 0 END) aw_short_cat,
            SUM(CASE WHEN t.listorder IN (8) THEN 1 ELSE 0 END) aw_tot,

            SUM(CASE WHEN t.listorder IN (25,32) AND  fil_no2 IS NOT NULL THEN 1 ELSE 0 END) frs_depend_on_other_diary,
            SUM(CASE WHEN t.listorder IN (25,32) AND notbef = 'B' THEN 1 ELSE 0 END) frs_before_jud, 
            SUM(CASE WHEN t.listorder IN (25,32) AND notbef = 'N' THEN 1 ELSE 0 END) frs_not_before_jud,
            SUM(CASE WHEN t.listorder IN (25,32) AND submaster_id = '331' THEN 1 ELSE 0 END) frs_defect_cat,
            SUM(CASE WHEN t.listorder IN (25,32) AND submaster_id IN (239,240,241,242,243,824,914) THEN 1 ELSE 0 END) frs_const_spl_bench,
            SUM(CASE WHEN t.listorder IN (25,32) AND submaster_id IS NULL THEN 1 ELSE 0 END) frs_cat_blank,
            SUM(CASE WHEN t.listorder IN (25,32) AND submaster_id IN (343,15,16,17,18,19,20,21,22,23,341,353,157,158,159,160,161,162,163,166,173,175,176,322,222) THEN 1 ELSE 0 END) frs_short_cat,
            SUM(CASE WHEN t.listorder IN (25,32) THEN 1 ELSE 0 END) frs_tot,

            SUM(CASE WHEN doccode1 IS NOT NULL AND t.listorder NOT IN (4,5,7,8,25,32) AND  fil_no2 IS NOT NULL THEN 1 ELSE 0 END) imp_ia_depend_on_other_diary,
            SUM(CASE WHEN doccode1 IS NOT NULL AND t.listorder NOT IN (4,5,7,8,25,32) AND notbef = 'B' THEN 1 ELSE 0 END) imp_ia_before_jud, 
            SUM(CASE WHEN doccode1 IS NOT NULL AND t.listorder NOT IN (4,5,7,8,25,32) AND notbef = 'N' THEN 1 ELSE 0 END) imp_ia_not_before_jud,
            SUM(CASE WHEN doccode1 IS NOT NULL AND t.listorder NOT IN (4,5,7,8,25,32) AND submaster_id = '331' THEN 1 ELSE 0 END) imp_ia_defect_cat,
            SUM(CASE WHEN doccode1 IS NOT NULL AND t.listorder NOT IN (4,5,7,8,25,32) AND submaster_id IN (239,240,241,242,243,824,914) THEN 1 ELSE 0 END) imp_ia_const_spl_bench,
            SUM(CASE WHEN doccode1 IS NOT NULL AND t.listorder NOT IN (4,5,7,8,25,32) AND submaster_id IS NULL THEN 1 ELSE 0 END) imp_ia_cat_blank,
            SUM(CASE WHEN doccode1 IS NOT NULL AND t.listorder NOT IN (4,5,7,8,25,32) AND submaster_id IN (343,15,16,17,18,19,20,21,22,23,341,353,157,158,159,160,161,162,163,166,173,175,176,322,222) THEN 1 ELSE 0 END) imp_ia_short_cat,
            SUM(CASE WHEN doccode1 IS NOT NULL AND t.listorder NOT IN (4,5,7,8,25,32) THEN 1 ELSE 0 END) imp_ia_tot,


            SUM(CASE WHEN doccode1 IS NULL AND t.listorder NOT IN (4,5,7,8,25,32) AND  fil_no2 IS NOT NULL THEN 1 ELSE 0 END) comp_depend_on_other_diary,
            SUM(CASE WHEN doccode1 IS NULL AND t.listorder NOT IN (4,5,7,8,25,32) AND notbef = 'B' THEN 1 ELSE 0 END) comp_before_jud, 
            SUM(CASE WHEN doccode1 IS NULL AND t.listorder NOT IN (4,5,7,8,25,32) AND notbef = 'N' THEN 1 ELSE 0 END) comp_not_before_jud,
            SUM(CASE WHEN doccode1 IS NULL AND t.listorder NOT IN (4,5,7,8,25,32) AND submaster_id = '331' THEN 1 ELSE 0 END) comp_defect_cat,
            SUM(CASE WHEN doccode1 IS NULL AND t.listorder NOT IN (4,5,7,8,25,32) AND submaster_id IN (239,240,241,242,243,824,914) THEN 1 ELSE 0 END) comp_const_spl_bench,
            SUM(CASE WHEN doccode1 IS NULL AND t.listorder NOT IN (4,5,7,8,25,32) AND submaster_id IS NULL THEN 1 ELSE 0 END) comp_cat_blank,
            SUM(CASE WHEN doccode1 IS NULL AND t.listorder NOT IN (4,5,7,8,25,32) AND submaster_id IN (343,15,16,17,18,19,20,21,22,23,341,353,157,158,159,160,161,162,163,166,173,175,176,322,222) THEN 1 ELSE 0 END) comp_short_cat,
            SUM(CASE WHEN doccode1 IS NULL AND t.listorder NOT IN (4,5,7,8,25,32) THEN 1 ELSE 0 END) comp_tot


            FROM (
            SELECT d.doccode1, rd.fil_no2, nb.notbef, mc.submaster_id, m.c_status, h.listorder, h.diary_no 

                FROM heardt h
                INNER JOIN main m ON m.diary_no = h.diary_no      
                            
                LEFT JOIN mul_category mc ON mc.diary_no = m.diary_no AND mc.display = 'Y'    
                LEFT JOIN not_before nb ON nb.diary_no::TEXT = m.diary_no::TEXT          
                LEFT JOIN rgo_default rd ON rd.fil_no = m.diary_no AND rd.remove_def = 'N'              
                LEFT JOIN docdetails d ON d.diary_no = m.diary_no AND d.display = 'Y' AND d.iastat = 'P' AND d.doccode = 8 
                AND d.doccode1 IN  (7,66,29,56,57,28,103,133,226,3,309,73,99,40,48,72,71,27,124,2,16,41,49,71,72,102,118,131,211,309) 
                WHERE c_status = 'P' AND (m.diary_no::TEXT = m.conn_key::TEXT OR m.conn_key IS NULL OR m.conn_key = '0') 
                AND h.next_dt between '$list_dt' AND '$list_dt_to' AND h.board_type = 'J' AND h.mainhead = 'M' 
                AND h.clno = 0 AND h.brd_slno = 0 AND h.main_supp_flag = 0 
                
                AND h.subhead IN (824,810,803,802,807,804,808,811,812,813,814,815,816) GROUP BY h.diary_no ,d.doccode1,rd.fil_no2,nb.notbef,mc.submaster_id,m.c_status               
                    ) t
                    GROUP BY c_status";
                  
        $result = $this->db->query($sql)->getResultArray();
        return $result;
    }

    public function getNotDisp($list_dt, $list_dt_to,$purpose)
    {
        $builder = $this->db->table('heardt h');

        $subquery = $this->db->table('main m')
            ->select([
                'rd.fil_no2',
                'd.doccode1',
                'm.active_fil_no',
                'm.active_reg_year',
                'm.reg_no_display',
                'm.active_casetype_id',
                'm.fil_no',
                'm.fil_dt',
                'EXTRACT(YEAR FROM m.fil_dt) AS fil_year',
                'm.lastorder',
                'm.diary_no_rec_date',
                'h.*',
                'l.purpose',
                'STRING_AGG(mc.submaster_id::TEXT, \',\') AS cat1',
                'h.diary_no'
            ])
            ->join('heardt h', 'm.diary_no = h.diary_no', 'INNER')
            ->join('master.listing_purpose l', 'l.code = h.listorder', 'LEFT')
            ->join('mul_category mc', 'mc.diary_no = m.diary_no AND mc.display = \'Y\'', 'LEFT')
            ->join('not_before nb', 'cast(nb.diary_no as TEXT) = m.diary_no::TEXT', 'LEFT')
            ->join('rgo_default rd', 'rd.fil_no = m.diary_no AND rd.remove_def = \'N\'', 'LEFT')
            ->join('docdetails d', 'd.diary_no = m.diary_no AND d.display = \'Y\' AND d.iastat = \'P\' AND d.doccode = 8 AND d.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 226, 3, 309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 71, 72, 102, 118, 131, 211, 309)', 'LEFT')
            ->where('m.c_status', 'P')
            ->where('(cast(m.diary_no as TEXT) = m.conn_key::TEXT OR m.conn_key IS NULL OR m.conn_key = \'\' OR m.conn_key = \'0\')')
            ->where("h.next_dt BETWEEN '$list_dt' AND '$list_dt_to'")
            ->where('h.board_type', 'J')
            ->where('h.mainhead', 'M')
            ->where('h.clno', 0)
            ->where('h.brd_slno', 0)
            ->where('h.main_supp_flag', 0)
            ->whereIn('h.subhead', [824, 810, 803, 802, 807, 804, 808, 811, 812, 813, 814, 815, 816])
            ->groupBy('rd.fil_no2, d.doccode1, m.active_fil_no, m.active_reg_year, m.reg_no_display, m.active_casetype_id, m.fil_no, m.fil_dt, m.lastorder, m.diary_no_rec_date, h.diary_no, l.purpose');

        $subquerySql = $subquery->getCompiledSelect();

        $builder->select('t.*')
            ->from('(' . $subquerySql . ') t')
            ->where('t.fil_no2 IS NOT NULL');
        IF($purpose == 'f'){
            $builder->whereIn('t.listorder', [4, 5, 7]);
        }
        IF($purpose == 'fr'){
            $builder->whereIn('t.listorder', [25, 32]);
        }
        IF($purpose == 'aw'){
            $builder->whereIn('t.listorder', [8]);
        }
        IF($purpose == 'imp'){
            $builder->whereNOTIn('t.listorder', [4,5,7,8,25,32]);
            $builder->where('t.doccode1 IS NOT NULL');
        }
        IF($purpose == 'cmp'){
            $builder->whereNOTIn('t.listorder', [4,5,7,8,25,32]);
            $builder->where('t.doccode1 IS NULL');
        }
        
        $result = $builder->get()->getResultArray();

        return $result;
    }
    public function getSpecialBenchMatters($list_dt, $list_dt_to,$purpose)
    {
        $subquery = $this->db->table('heardt h')
            ->select([
                'nb.notbef',
                'd.doccode1',
                'm.active_fil_no',
                'm.active_reg_year',
                'm.reg_no_display',
                'm.active_casetype_id',
                'm.fil_no',
                'm.fil_dt',
                'EXTRACT(YEAR FROM m.fil_dt) AS fil_year',
                'm.lastorder',
                'm.diary_no_rec_date',
                'h.*',
                'l.purpose',
                'STRING_AGG(mc.submaster_id::TEXT, \',\') AS cat1',
                'h.diary_no'
            ])
            ->join('main m', 'm.diary_no = h.diary_no', 'INNER')
            ->join('master.listing_purpose l', 'l.code = h.listorder', 'LEFT')
            ->join('mul_category mc', 'mc.diary_no = m.diary_no AND mc.display = \'Y\'', 'LEFT')
            ->join('not_before nb', 'cast(nb.diary_no as TEXT) = m.diary_no::TEXT', 'LEFT')
            ->join('rgo_default rd', 'rd.fil_no = m.diary_no AND rd.remove_def = \'N\'', 'LEFT')
            ->join('docdetails d', 'd.diary_no = m.diary_no AND d.display = \'Y\' AND d.iastat = \'P\' AND d.doccode = 8 AND d.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 226, 3, 309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 71, 72, 102, 118, 131, 211, 309)', 'LEFT')
            ->where('m.c_status', 'P')
            ->where('(cast(m.diary_no as TEXT) = m.conn_key::TEXT OR m.conn_key IS NULL OR m.conn_key = \'\' OR m.conn_key = \'0\')')
            ->where("h.next_dt BETWEEN '$list_dt' AND '$list_dt_to'")
            ->where('h.board_type', 'J')
            ->where('h.mainhead', 'M')
            ->where('h.clno', 0)
            ->where('h.brd_slno', 0)
            ->where('h.main_supp_flag', 0)
            ->whereIn('h.subhead', [824, 810, 803, 802, 807, 804, 808, 811, 812, 813, 814, 815, 816])
            ->groupBy('nb.notbef, d.doccode1, m.active_fil_no, m.active_reg_year, m.reg_no_display, m.active_casetype_id, m.fil_no, m.fil_dt, m.lastorder, m.diary_no_rec_date, h.diary_no, l.purpose');

        $subquerySql = $subquery->getCompiledSelect();

        $builder = $this->db->table('(' . $subquerySql . ') t')
            ->select('t.*')
            ->where('t.notbef', 'B');

            IF($purpose == 'f'){
                $builder->whereIn('t.listorder', [4, 5, 7]);
            }
            IF($purpose == 'fr'){
                $builder->whereIn('t.listorder', [25, 32]);
            }
            IF($purpose == 'aw'){
                $builder->whereIn('t.listorder', [8]);
            }
            IF($purpose == 'imp'){
                $builder->whereNOTIn('t.listorder', [4,5,7,8,25,32]);
                $builder->where('t.doccode1 IS NOT NULL');
            }
            IF($purpose == 'cmp'){
                $builder->whereNOTIn('t.listorder', [4,5,7,8,25,32]);
                $builder->where('t.doccode1 IS NULL');
            }
            // echo $builder->getCompiledSelect();
            // die();
            $result = $builder->get()->getResultArray();
    
            return $result;
    }
    public function getNotBefore($list_dt, $list_dt_to,$purpose)
    {
        $subquery = $this->db->table('heardt h')
                ->select([
                    'nb.notbef',
                    'd.doccode1',
                    'm.active_fil_no',
                    'm.active_reg_year',
                    'm.reg_no_display',
                    'm.active_casetype_id',
                    'm.fil_no',
                    'm.fil_dt',
                    'EXTRACT(YEAR FROM m.fil_dt) AS fil_year',
                    'm.lastorder',
                    'm.diary_no_rec_date',
                    'h.*',
                    'l.purpose',
                    'STRING_AGG(mc.submaster_id::TEXT, \',\') AS cat1',
                    'h.diary_no'
                ])
                ->join('main m', 'm.diary_no = h.diary_no', 'INNER')
                ->join('master.listing_purpose l', 'l.code = h.listorder', 'LEFT')
                ->join('mul_category mc', 'mc.diary_no = m.diary_no AND mc.display = \'Y\'', 'LEFT')
                ->join('not_before nb', 'cast(nb.diary_no as TEXT) = m.diary_no::TEXT', 'LEFT')
                ->join('rgo_default rd', 'rd.fil_no = m.diary_no AND rd.remove_def = \'N\'', 'LEFT')
                ->join('docdetails d', 'd.diary_no = m.diary_no AND d.display = \'Y\' AND d.iastat = \'P\' AND d.doccode = 8 AND d.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 226, 3, 309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 71, 72, 102, 118, 131, 211, 309)', 'LEFT')
                ->where('m.c_status', 'P')
                ->where('(cast(m.diary_no as TEXT) = m.conn_key::TEXT OR m.conn_key IS NULL OR m.conn_key = \'\' OR m.conn_key = \'0\')')
                ->where("h.next_dt BETWEEN '$list_dt' AND '$list_dt_to'")
                ->where('h.board_type', 'J')
                ->where('h.mainhead', 'M')
                ->where('h.clno', 0)
                ->where('h.brd_slno', 0)
                ->where('h.main_supp_flag', 0)
                ->whereIn('h.subhead', [824, 810, 803, 802, 807, 804, 808, 811, 812, 813, 814, 815, 816])
                ->groupBy('nb.notbef, d.doccode1, m.active_fil_no, m.active_reg_year, m.reg_no_display, m.active_casetype_id, m.fil_no, m.fil_dt, m.lastorder, m.diary_no_rec_date, h.diary_no, l.purpose');

            $subquerySql = $subquery->getCompiledSelect();

            $builder = $this->db->table('(' . $subquerySql . ') t')
                ->select('t.*')
                ->where('t.notbef', 'N');
                IF($purpose == 'f'){
                    $builder->whereIn('t.listorder', [4, 5, 7]);
                }
                IF($purpose == 'fr'){
                    $builder->whereIn('t.listorder', [25, 32]);
                }
                IF($purpose == 'aw'){
                    $builder->whereIn('t.listorder', [8]);
                }
                IF($purpose == 'imp'){
                    $builder->whereNOTIn('t.listorder', [4,5,7,8,25,32]);
                    $builder->where('t.doccode1 IS NOT NULL');
                }
                IF($purpose == 'cmp'){
                    $builder->whereNOTIn('t.listorder', [4,5,7,8,25,32]);
                    $builder->where('t.doccode1 IS NULL');
                }
                // echo $builder->getCompiledSelect();
                // die();
                $result = $builder->get()->getResultArray();
                return $result;
    }
    public function getDefectiveCategory($list_dt, $list_dt_to,$purpose)
    {
        $subquery = $this->db->table('heardt h')
                ->select([
                    'nb.notbef',
                    'd.doccode1',
                    'm.active_fil_no',
                    'm.active_reg_year',
                    'm.reg_no_display',
                    'm.active_casetype_id',
                    'm.fil_no',
                    'm.fil_dt',
                    'EXTRACT(YEAR FROM m.fil_dt) AS fil_year',
                    'm.lastorder',
                    'm.diary_no_rec_date',
                    'h.*',
                    'l.purpose',
                    'mc.submaster_id',
                    'STRING_AGG(mc.submaster_id::TEXT, \',\') AS cat1',
                    'h.diary_no'
                ])
                ->join('main m', 'm.diary_no = h.diary_no', 'INNER')
                ->join('master.listing_purpose l', 'l.code = h.listorder', 'LEFT')
                ->join('mul_category mc', 'mc.diary_no = m.diary_no AND mc.display = \'Y\'', 'LEFT')
                ->join('not_before nb', 'cast(nb.diary_no as TEXT) = m.diary_no::TEXT', 'LEFT')
                ->join('rgo_default rd', 'rd.fil_no = m.diary_no AND rd.remove_def = \'N\'', 'LEFT')
                ->join('docdetails d', 'd.diary_no = m.diary_no AND d.display = \'Y\' AND d.iastat = \'P\' AND d.doccode = 8 AND d.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 226, 3, 309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 71, 72, 102, 118, 131, 211, 309)', 'LEFT')
                ->where('m.c_status', 'P')
                ->where('(cast(m.diary_no as TEXT) = m.conn_key::TEXT OR m.conn_key IS NULL OR m.conn_key = \'\' OR m.conn_key = \'0\')')
                ->where("h.next_dt BETWEEN '$list_dt' AND '$list_dt_to'")
                ->where('h.board_type', 'J')
                ->where('h.mainhead', 'M')
                ->where('h.clno', 0)
                ->where('h.brd_slno', 0)
                ->where('h.main_supp_flag', 0)
                ->whereIn('h.subhead', [824, 810, 803, 802, 807, 804, 808, 811, 812, 813, 814, 815, 816])
                ->groupBy('nb.notbef, d.doccode1, m.active_fil_no, m.active_reg_year, m.reg_no_display, m.active_casetype_id, m.fil_no, m.fil_dt, m.lastorder, m.diary_no_rec_date, h.diary_no, l.purpose, mc.submaster_id');

            $subquerySql = $subquery->getCompiledSelect();

            $builder = $this->db->table('(' . $subquerySql . ') t')
                ->select('t.*')
                ->where('t.submaster_id', '331');
            IF($purpose == 'f'){
                $builder->whereIn('t.listorder', [4, 5, 7]);
            }
            IF($purpose == 'fr'){
                $builder->whereIn('t.listorder', [25, 32]);
            }
            IF($purpose == 'aw'){
                $builder->whereIn('t.listorder', [8]);
            }
            IF($purpose == 'imp'){
                $builder->whereNOTIn('t.listorder', [4,5,7,8,25,32]);
                $builder->where('t.doccode1 IS NOT NULL');
            }
            IF($purpose == 'cmp'){
                $builder->whereNOTIn('t.listorder', [4,5,7,8,25,32]);
                $builder->where('t.doccode1 IS NULL');
            }
                // echo $builder->getCompiledSelect();
                // die();
            $result = $builder->get()->getResultArray();
            return $result;
    }
    public function getConstitutionBenchMatters($list_dt, $list_dt_to,$purpose)
    {
        $subquery = $this->db->table('heardt h')
            ->select([
                'nb.notbef',
                'd.doccode1',
                'm.active_fil_no',
                'm.active_reg_year',
                'm.reg_no_display',
                'm.active_casetype_id',
                'm.fil_no',
                'm.fil_dt',
                'EXTRACT(YEAR FROM m.fil_dt) AS fil_year',
                'm.lastorder',
                'm.diary_no_rec_date',
                'h.*',
                'l.purpose',
                'mc.submaster_id',
                'STRING_AGG(mc.submaster_id::TEXT, \',\') AS cat1',
                'h.diary_no'
            ])
            ->join('main m', 'm.diary_no = h.diary_no', 'INNER')
            ->join('master.listing_purpose l', 'l.code = h.listorder', 'LEFT')
            ->join('mul_category mc', 'mc.diary_no = m.diary_no AND mc.display = \'Y\'', 'LEFT')
            ->join('not_before nb', 'cast(nb.diary_no as TEXT) = m.diary_no::TEXT', 'LEFT')
            ->join('rgo_default rd', 'rd.fil_no = m.diary_no AND rd.remove_def = \'N\'', 'LEFT')
            ->join('docdetails d', 'd.diary_no = m.diary_no AND d.display = \'Y\' AND d.iastat = \'P\' AND d.doccode = 8 AND d.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 226, 3, 309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 71, 72, 102, 118, 131, 211, 309)', 'LEFT')
            ->where('m.c_status', 'P')
            ->where('(cast(m.diary_no as TEXT) = m.conn_key::TEXT OR m.conn_key IS NULL OR m.conn_key = \'\' OR m.conn_key = \'0\')')
            ->where("h.next_dt BETWEEN '$list_dt' AND '$list_dt_to'")
            ->where('h.board_type', 'J')
            ->where('h.mainhead', 'M')
            ->where('h.clno', 0)
            ->where('h.brd_slno', 0)
            ->where('h.main_supp_flag', 0)
            ->whereIn('h.subhead', [824, 810, 803, 802, 807, 804, 808, 811, 812, 813, 814, 815, 816])
            ->groupBy('nb.notbef, d.doccode1, m.active_fil_no, m.active_reg_year, m.reg_no_display, m.active_casetype_id, m.fil_no, m.fil_dt, m.lastorder, m.diary_no_rec_date, h.diary_no, l.purpose, mc.submaster_id');

        $subquerySql = $subquery->getCompiledSelect();

        $builder = $this->db->table('(' . $subquerySql . ') t')
            ->select('t.*')
            ->whereIn('t.submaster_id', [239, 240, 241, 242, 243, 824, 914]);
            IF($purpose == 'f'){
                $builder->whereIn('t.listorder', [4, 5, 7]);
            }
            IF($purpose == 'fr'){
                $builder->whereIn('t.listorder', [25, 32]);
            }
            IF($purpose == 'aw'){
                $builder->whereIn('t.listorder', [8]);
            }
            IF($purpose == 'imp'){
                $builder->whereNOTIn('t.listorder', [4,5,7,8,25,32]);
                $builder->where('t.doccode1 IS NOT NULL');
            }
            IF($purpose == 'cmp'){
                $builder->whereNOTIn('t.listorder', [4,5,7,8,25,32]);
                $builder->where('t.doccode1 IS NULL');
            }
            // echo $builder->getCompiledSelect();
            // die();
        $result = $builder->get()->getResultArray();
        return $result;
    }
    public function getCatNot($list_dt, $list_dt_to,$purpose)
    {
        $subquery = $this->db->table('heardt h')
            ->select([
                'nb.notbef',
                'd.doccode1',
                'm.active_fil_no',
                'm.active_reg_year',
                'm.reg_no_display',
                'm.active_casetype_id',
                'm.fil_no',
                'm.fil_dt',
                'EXTRACT(YEAR FROM m.fil_dt) AS fil_year',
                'm.lastorder',
                'm.diary_no_rec_date',
                'h.*',
                'l.purpose',
                'mc.submaster_id',
                'STRING_AGG(mc.submaster_id::TEXT, \',\') AS cat1',
                'h.diary_no'
            ])
            ->join('main m', 'm.diary_no = h.diary_no', 'INNER')
            ->join('master.listing_purpose l', 'l.code = h.listorder', 'LEFT')
            ->join('mul_category mc', 'mc.diary_no = m.diary_no AND mc.display = \'Y\'', 'LEFT')
            ->join('not_before nb', 'cast(nb.diary_no as TEXT) = m.diary_no::TEXT', 'LEFT')
            ->join('rgo_default rd', 'rd.fil_no = m.diary_no AND rd.remove_def = \'N\'', 'LEFT')
            ->join('docdetails d', 'd.diary_no = m.diary_no AND d.display = \'Y\' AND d.iastat = \'P\' AND d.doccode = 8 AND d.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 226, 3, 309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 71, 72, 102, 118, 131, 211, 309)', 'LEFT')
            ->where('m.c_status', 'P')
            ->where('(cast(m.diary_no as TEXT) = m.conn_key::TEXT OR m.conn_key IS NULL OR m.conn_key = \'\' OR m.conn_key = \'0\')')
            ->where("h.next_dt BETWEEN '$list_dt' AND '$list_dt_to'")
            ->where('h.board_type', 'J')
            ->where('h.mainhead', 'M')
            ->where('h.clno', 0)
            ->where('h.brd_slno', 0)
            ->where('h.main_supp_flag', 0)
            ->whereIn('h.subhead', [824, 810, 803, 802, 807, 804, 808, 811, 812, 813, 814, 815, 816])
            ->groupBy('nb.notbef, d.doccode1, m.active_fil_no, m.active_reg_year, m.reg_no_display, m.active_casetype_id, m.fil_no, m.fil_dt, m.lastorder, m.diary_no_rec_date, h.diary_no, l.purpose, mc.submaster_id');

        $subquerySql = $subquery->getCompiledSelect();

        $builder = $this->db->table('(' . $subquerySql . ') t')
            ->select('t.*')
            ->where('t.submaster_id IS NULL');

            IF($purpose == 'f'){
                $builder->whereIn('t.listorder', [4, 5, 7]);
            }
            IF($purpose == 'fr'){
                $builder->whereIn('t.listorder', [25, 32]);
            }
            IF($purpose == 'aw'){
                $builder->whereIn('t.listorder', [8]);
            }
            IF($purpose == 'imp'){
                $builder->whereNOTIn('t.listorder', [4,5,7,8,25,32]);
                $builder->where('t.doccode1 IS NOT NULL');
            }
            IF($purpose == 'cmp'){
                $builder->whereNOTIn('t.listorder', [4,5,7,8,25,32]);
                $builder->where('t.doccode1 IS NULL');
            }
            // echo $builder->getCompiledSelect();
            // die();
        $result = $builder->get()->getResultArray();
        return $result;
    }
    public function getShortCategoryMatters($list_dt, $list_dt_to,$purpose)
    {
       
        $subquery = $this->db->table('heardt h')
            ->select([
                'nb.notbef',
                'd.doccode1',
                'm.active_fil_no',
                'm.active_reg_year',
                'm.reg_no_display',
                'm.active_casetype_id',
                'm.fil_no',
                'm.fil_dt',
                'EXTRACT(YEAR FROM m.fil_dt) AS fil_year',
                'm.lastorder',
                'm.diary_no_rec_date',
                'h.*',
                'l.purpose',
                'mc.submaster_id',
                'STRING_AGG(mc.submaster_id::TEXT, \',\') AS cat1',
                'h.diary_no'
            ])
            ->join('main m', 'm.diary_no = h.diary_no', 'INNER')
            ->join('master.listing_purpose l', 'l.code = h.listorder', 'LEFT')
            ->join('mul_category mc', 'mc.diary_no = m.diary_no AND mc.display = \'Y\'', 'LEFT')
            ->join('not_before nb', 'cast(nb.diary_no as TEXT) = m.diary_no::TEXT', 'LEFT')
            ->join('rgo_default rd', 'rd.fil_no = m.diary_no AND rd.remove_def = \'N\'', 'LEFT')
            ->join('docdetails d', 'd.diary_no = m.diary_no AND d.display = \'Y\' AND d.iastat = \'P\' AND d.doccode = 8 AND d.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 226, 3, 309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 71, 72, 102, 118, 131, 211, 309)', 'LEFT')
            ->where('m.c_status', 'P')
            ->where('(cast(m.diary_no as TEXT) = m.conn_key::TEXT OR m.conn_key IS NULL OR m.conn_key = \'\' OR m.conn_key = \'0\')')
            ->where("h.next_dt BETWEEN '$list_dt' AND '$list_dt_to'")
            ->where('h.board_type', 'J')
            ->where('h.mainhead', 'M')
            ->where('h.clno', 0)
            ->where('h.brd_slno', 0)
            ->where('h.main_supp_flag', 0)
            ->whereIn('h.subhead', [824, 810, 803, 802, 807, 804, 808, 811, 812, 813, 814, 815, 816])
            ->groupBy('nb.notbef, d.doccode1, m.active_fil_no, m.active_reg_year, m.reg_no_display, m.active_casetype_id, m.fil_no, m.fil_dt, m.lastorder, m.diary_no_rec_date, h.diary_no, l.purpose, mc.submaster_id');

        $subquerySql = $subquery->getCompiledSelect();

        $builder = $this->db->table('(' . $subquerySql . ') t')
            ->select('t.*')
            ->whereIn('t.submaster_id', [343, 15, 16, 17, 18, 19, 20, 21, 22, 23, 341, 353, 157, 158, 159, 160, 161, 162, 163, 166, 173, 175, 176, 322, 222]);
            IF($purpose == 'f'){
                $builder->whereIn('t.listorder', [4, 5, 7]);
            }
            IF($purpose == 'fr'){
                $builder->whereIn('t.listorder', [25, 32]);
            }
            IF($purpose == 'aw'){
                $builder->whereIn('t.listorder', [8]);
            }
            IF($purpose == 'imp'){
                $builder->whereNOTIn('t.listorder', [4,5,7,8,25,32]);
                $builder->where('t.doccode1 IS NOT NULL');
            }
            IF($purpose == 'cmp'){
                $builder->whereNOTIn('t.listorder', [4,5,7,8,25,32]);
                $builder->where('t.doccode1 IS NULL');
            }
            // echo $builder->getCompiledSelect();
            // die();
        $result = $builder->get()->getResultArray();
        return $result;

    }
    public function getExcessMatters($list_dt, $list_dt_to,$purpose)
    {
        $subquery = $this->db->table('heardt h')
            ->select([
                'fil_no2',
                'nb.notbef',
                'd.doccode1',
                'm.active_fil_no',
                'm.active_reg_year',
                'm.reg_no_display',
                'm.active_casetype_id',
                'm.fil_no',
                'm.fil_dt',
                'EXTRACT(YEAR FROM m.fil_dt) AS fil_year',
                'm.lastorder',
                'm.diary_no_rec_date',
                'h.*',
                'l.purpose',
                'mc.submaster_id',
                'STRING_AGG(mc.submaster_id::TEXT, \',\') AS cat1',
                'h.diary_no'
            ])
            ->join('main m', 'm.diary_no = h.diary_no', 'INNER')
            ->join('master.listing_purpose l', 'l.code = h.listorder', 'LEFT')
            ->join('mul_category mc', 'mc.diary_no = m.diary_no AND mc.display = \'Y\'', 'LEFT')
            ->join('not_before nb', 'CAST(nb.diary_no AS TEXT) = m.diary_no::TEXT', 'LEFT')
            ->join('rgo_default rd', 'rd.fil_no = m.diary_no AND rd.remove_def = \'N\'', 'LEFT')
            ->join('docdetails d', 'd.diary_no = m.diary_no AND d.display = \'Y\' AND d.iastat = \'P\' AND d.doccode = 8 AND d.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 226, 3, 309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 71, 72, 102, 118, 131, 211, 309)', 'LEFT')
            ->where('m.c_status', 'P')
            ->where('(CAST(m.diary_no AS TEXT) = m.conn_key::TEXT OR m.conn_key IS NULL OR m.conn_key = \'\' OR m.conn_key = \'0\')')
            ->where("h.next_dt BETWEEN '$list_dt' AND '$list_dt_to'")
            ->where('h.board_type', 'J')
            ->where('h.mainhead', 'M')
            ->where('h.clno', 0)
            ->where('h.brd_slno', 0)
            ->where('h.main_supp_flag', 0)
            ->whereIn('h.subhead', [824, 810, 803, 802, 807, 804, 808, 811, 812, 813, 814, 815, 816])
            ->groupBy('fil_no2, nb.notbef, d.doccode1, m.active_fil_no, m.active_reg_year, m.reg_no_display, m.active_casetype_id, m.fil_no, m.fil_dt, m.lastorder, m.diary_no_rec_date, h.diary_no, l.purpose, mc.submaster_id');

        $subquerySql = $subquery->getCompiledSelect();

        $builder = $this->db->table('(' . $subquerySql . ') t')
            ->select('t.*')
            ->where('t.fil_no2 IS NULL')
            ->where('t.notbef IS NULL')
            ->where('t.submaster_id IS NOT NULL')
            ->whereNotIn('t.submaster_id', [331, 239, 240, 241, 242, 243, 824, 914, 343, 15, 16, 17, 18, 19, 20, 21, 22, 23, 341, 353, 157, 158, 159, 160, 161, 162, 163, 166, 173, 175, 176, 322, 222]);
            IF($purpose == 'f'){
                $builder->whereIn('t.listorder', [4, 5, 7]);
            }
            IF($purpose == 'fr'){
                $builder->whereIn('t.listorder', [25, 32]);
            }
            IF($purpose == 'aw'){
                $builder->whereIn('t.listorder', [8]);
            }
            IF($purpose == 'imp'){
                $builder->whereNOTIn('t.listorder', [4,5,7,8,25,32]);
                $builder->where('t.doccode1 IS NOT NULL');
            }
            IF($purpose == 'cmp'){
                $builder->whereNOTIn('t.listorder', [4,5,7,8,25,32]);
                $builder->where('t.doccode1 IS NULL');
            }
            // echo $builder->getCompiledSelect();
            // die();
        $result = $builder->get()->getResultArray();
        return $result;

    }
    public function getTotalNotListed($list_dt, $list_dt_to,$purpose)
    {
        $subquery = $this->db->table('heardt h')
            ->select([
                'fil_no2',
                'nb.notbef',
                'd.doccode1',
                'm.active_fil_no',
                'm.active_reg_year',
                'm.reg_no_display',
                'm.active_casetype_id',
                'm.fil_no',
                'm.fil_dt',
                'EXTRACT(YEAR FROM m.fil_dt) AS fil_year',
                'm.lastorder',
                'm.diary_no_rec_date',
                'h.*',
                'l.purpose',
                'mc.submaster_id',
                'STRING_AGG(mc.submaster_id::TEXT, \',\') AS cat1',
                'h.diary_no'
            ])
            ->join('main m', 'm.diary_no = h.diary_no', 'INNER')
            ->join('master.listing_purpose l', 'l.code = h.listorder', 'LEFT')
            ->join('mul_category mc', 'mc.diary_no = m.diary_no AND mc.display = \'Y\'', 'LEFT')
            ->join('not_before nb', 'CAST(nb.diary_no AS TEXT) = m.diary_no::TEXT', 'LEFT')
            ->join('rgo_default rd', 'rd.fil_no = m.diary_no AND rd.remove_def = \'N\'', 'LEFT')
            ->join('docdetails d', 'd.diary_no = m.diary_no AND d.display = \'Y\' AND d.iastat = \'P\' AND d.doccode = 8 AND d.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 226, 3, 309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 71, 72, 102, 118, 131, 211, 309)', 'LEFT')
            ->where('m.c_status', 'P')
            ->where('(CAST(m.diary_no AS TEXT) = m.conn_key::TEXT OR m.conn_key IS NULL OR m.conn_key = \'\' OR m.conn_key = \'0\')')
            ->where("h.next_dt BETWEEN '$list_dt' AND '$list_dt_to'")
            ->where('h.board_type', 'J')
            ->where('h.mainhead', 'M')
            ->where('h.clno', 0)
            ->where('h.brd_slno', 0)
            ->where('h.main_supp_flag', 0)
            ->whereIn('h.subhead', [824, 810, 803, 802, 807, 804, 808, 811, 812, 813, 814, 815, 816])
            ->groupBy('fil_no2, nb.notbef, d.doccode1, m.active_fil_no, m.active_reg_year, m.reg_no_display, m.active_casetype_id, m.fil_no, m.fil_dt, m.lastorder, m.diary_no_rec_date, h.diary_no, l.purpose, mc.submaster_id');

        $subquerySql = $subquery->getCompiledSelect();

        $builder = $this->db->table('(' . $subquerySql . ') t')
            ->select('t.*');
            IF($purpose == 'f'){
                $builder->whereIn('t.listorder', [4, 5, 7]);
            }
            IF($purpose == 'fr'){
                $builder->whereIn('t.listorder', [25, 32]);
            }
            IF($purpose == 'aw'){
                $builder->whereIn('t.listorder', [8]);
            }
            IF($purpose == 'imp'){
                $builder->whereNOTIn('t.listorder', [4,5,7,8,25,32]);
                $builder->where('t.doccode1 IS NOT NULL');
            }
            IF($purpose == 'cmp'){
                $builder->whereNOTIn('t.listorder', [4,5,7,8,25,32]);
                $builder->where('t.doccode1 IS NULL');
            }
            // echo $builder->getCompiledSelect();
            // die();
        $result = $builder->get()->getResultArray();
        return $result;

    }
   

}