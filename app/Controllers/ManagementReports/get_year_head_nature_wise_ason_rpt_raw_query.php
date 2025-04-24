
   
   public function get_year_head_nature_wise_ason_rpt()
    {
        // echo "<pre>";

        $str = $this->get_case_type();

        $bench = '';
        $benchInput = $this->request->getGet('bench');

        if ($benchInput === 'all') {
            $bench = '';
        } elseif ($benchInput === '2') {
            $bench = " AND h.judges LIKE '%,%'";
        } elseif ($benchInput === '3') {
            $bench = " AND h.judges LIKE '%,%,%'";
        } elseif ($benchInput === '5') {
            $bench = " AND h.judges LIKE '%,%,%,%,%'";
        } elseif ($benchInput === '7') {
            $bench = " AND h.judges LIKE '%,%,%,%,%,%,%'";
        } elseif ($benchInput === '9') {
            $bench = " AND h.judges LIKE '%,%,%,%,%,%,%,%,%'";
        } else {
            $bench = " AND h.judges NOT LIKE '%%,%'";
        }


        if ($this->request->getGet('ason_type') == 'dt') {
            $til_date = explode("-", $this->request->getGet('til_date'));
            $til_dt = $til_date[2] . "-" . $til_date[1] . "-" . $til_date[0];

            $ason_str = " CASE WHEN d.rj_dt IS NOT NULL THEN d.rj_dt >= DATE '" . $til_dt . "'
        WHEN d.disp_dt IS NOT NULL THEN d.disp_dt >= DATE '" . $til_dt . "'
        ELSE TO_DATE(CONCAT(COALESCE(d.year::text, '0000'), '-', LPAD(COALESCE(d.month::text, '01'), 2, '0'), '-01'), 
        'YYYY-MM-DD' ) >= DATE '" . $til_dt . "' END";

            $ason_str_res = "IF(disp_rj_dt != '0000-00-00', disp_rj_dt >= '" . $til_dt . "',
                    IF(r.disp_dt IS NOT NULL, r.disp_dt >= '" . $til_dt . "', 
                    CONCAT(r.disp_year::text, '-', LPAD(r.disp_month, 2, 0), '-01') >= '" . $til_dt . "'))";

            $exclude_cond = "CASE WHEN r.disp_dt IS NOT NULL 
                AND r.conn_next_dt IS NOT NULL THEN '" . $til_dt . "' NOT BETWEEN r.disp_dt AND conn_next_dt 
            ELSE r.disp_dt IS NULL OR r.conn_next_dt IS NULL END OR r.fil_no IS NULL";

            $exclude_cond_other = " CASE WHEN r.disp_dt IS NOT NULL AND r.conn_next_dt IS NOT NULL
            THEN '" . $til_dt . "' NOT BETWEEN r.disp_dt AND conn_next_dt 
            ELSE r.disp_dt IS NULL OR r.conn_next_dt IS NULL END";
        } else if ($this->request->getGet('ason_type') == 'month') {
            $til_dt = $this->request->getGet('lst_year') . "-" . str_pad($this->request->getGet('lst_month'), 2, "0", STR_PAD_LEFT) . "-01";

            $ason_str = " IF(d.rj_dt IS NOT NULL, d.rj_dt >= '" . $til_dt . "', 
                            IF(d.month = 0, d.disp_dt >= '" . $til_dt . "', CONCAT(d.year, '-',LPAD(d.month::text, 2, '0'), '-01') >= '" . $til_dt . "'))";

            $ason_str_res = " IF(r.disp_rj_dt != '0000-00-00', r.disp_rj_dt >= '" . $til_dt . "', 
                            IF(r.disp_month = 0, r.disp_dt >= '" . $til_dt . "', CONCAT(r.disp_year, '-', LPAD(r.disp_month, 2, 0), '-01') >= '" . $til_dt . "'))";

            $exclude_cond = " CASE 
            WHEN r.disp_month != '0' AND r.disp_month IS NOT NULL AND r.month != '0' AND r.month IS NOT NULL 
            THEN '" . $til_dt . "' NOT BETWEEN CONCAT(r.disp_year, '-', LPAD(r.disp_month, 2, '0'), '-01') AND CONCAT(r.year, '-', LPAD(r.month, 2, '0'), '-01') 
            WHEN r.month != '0' AND r.month IS NOT NULL 
            THEN CONCAT(r.year, '-', LPAD(r.month, 2, '0'), '-01') != '" . $til_dt . "'
            ELSE r.disp_month = '0' OR r.`disp_month` IS NULL OR r.month = '0' OR r.month IS NULL END OR r.fil_no IS NULL";

            $exclude_cond_other = " CASE 
            WHEN r.disp_month != '0' AND r.disp_month IS NOT NULL AND r.month != '0' AND r.month IS NOT NULL 
            THEN '" . $til_dt . "' NOT BETWEEN CONCAT(r.disp_year, '-', LPAD(r.disp_month, 2, '0'), '-01') 
            AND CONCAT(r.year, '-', LPAD(r.month, 2, '0'), '-01') 
            WHEN r.month != '0' AND r.month IS NOT NULL 
            THEN CONCAT(r.year, '-', LPAD(r.month, 2, '0'), '-01') != '" . $til_dt . "'
            ELSE r.disp_month = '0' OR r.`disp_month` IS NULL OR r.month = '0' OR r.month IS NULL END";
        } else if ($this->request->getGet('ason_type') == 'ent_dt') {
            $til_date = explode("-", $this->request->getGet('til_date'));
            $til_dt = $til_date[2] . "-" . $til_date[1] . "-" . $til_date[0];

            $ason_str = " d.ent_dt >= '" . $til_dt . "'";

            $ason_str_res = " r.disp_ent_dt >= '" . $til_dt . "'";

            $exclude_cond = " CASE WHEN  r.`entry_date` IS NOT NULL 
                        AND DATE(r.disp_ent_dt) != '0000-00-00' AND r.disp_ent_dt IS NOT NULL
            THEN '" . $til_dt . "' NOT BETWEEN DATE(r.disp_ent_dt) AND `entry_date` 
            ELSE DATE(r.`disp_ent_dt`) = '0000-00-00' OR r.`disp_ent_dt` IS NULL OR DATE(r.entry_date) = '0000-00-00' OR r.entry_date IS NULL END 
            OR r.fil_no IS NULL";

            $exclude_cond_other = " CASE WHEN  r.`entry_date` IS NOT NULL 
                        AND DATE(r.disp_ent_dt) != '0000-00-00' AND r.disp_ent_dt IS NOT NULL
            THEN '" . $til_dt . "' NOT BETWEEN DATE(r.disp_ent_dt) AND `entry_date` 
            ELSE DATE(r.`disp_ent_dt`) = '0000-00-00' OR r.`disp_ent_dt` IS NULL OR DATE(r.entry_date) = '0000-00-00' OR r.entry_date IS NULL END";
        }

        if ($this->request->getGet('rpt_purpose') == 'sw') {
            $subhead_name = "subhead_n";
            $mainhead_name = "mainhead_n";
        } else {
            $subhead_name = "subhead";
            $mainhead_name = "mainhead";
        }

        if ($this->request->getGet('subhead') == 'all,' || $this->request->getGet('subhead') == '') {
            $subhead = '';
            $subhead_if_last_heardt = " ";
            $subhead_condition = " ";
            $head_subhead = " ";
        } else {
            $subhead = " AND l." . $subhead_name . " IN (" . substr($this->request->getGet('subhead'), 0, -1) . ")";
            $subhead_if_heardt = " AND h." . $subhead_name . " IN (" . substr($this->request->getGet('subhead'), 0, -1) . ")";
            $subhead_if_last_heardt = " AND f2." . $subhead_name . " IN (" . substr($this->request->getGet('subhead'), 0, -1) . ")";

            $subhead_if_heardt_con = " h." . $subhead_name . " IN (" . substr($this->request->getGet('subhead'), 0, -1) . ")";
            $subhead_if_last_heardt_con = " f2." . $subhead_name . " IN (" . substr($this->request->getGet('subhead'), 0, -1) . ")";

            if ($this->request->getGet('til_date') != date('d-m-Y')) {
                $subhead_condition = " AND IF(DATE(h.ent_dt) < '" . $til_dt . "' AND DATE(h.ent_dt) > med, " . $subhead_if_heardt_con . ", " . $subhead_if_last_heardt_con . ")";
                $head_subhead = $this->stagename(substr($this->request->getGet('subhead'), 0, -1));
            } else {
                $subhead_condition = " AND " . $subhead_if_heardt_con;
                $head_subhead = $this->stagename(substr($this->request->getGet('subhead'), 0, -1));
            }
        }

        if ($this->request->getGet('concept') == 'new') {

            if ($this->request->getGet('mf') == 'M') {
                $mf_f2_table = " f2." . $mainhead_name . " = 'M' AND (admitted = '' OR admitted IS NULL)";
                $mf_h_table = " h." . $mainhead_name . " = 'M' AND (admitted = '' OR admitted IS NULL)";
            }
            if ($this->request->getGet('mf') == 'F') {
                $mf_f2_table = " (f2." . $mainhead_name . " = 'F' OR (admitted != '' AND admitted IS NOT NULL)) ";
                $mf_h_table = "( h." . $mainhead_name . " = 'F' OR (admitted != '' AND admitted IS NOT NULL))";
            }
            if ($this->request->getGet('mf') == 'N') {
                $mf_f2_table = " (f2." . $mainhead_name . " NOT IN ('M', 'F')) ";
                $mf_h_table = "( h." . $mainhead_name . " NOT IN ('M', 'F'))";
            }
        } elseif ($this->request->getGet('concept') == 'old') {
            if ($this->request->getGet('mf') == 'M') {
                $mf_f2_table = " f2." . $mainhead_name . " = '" . $this->request->getGet('mf') . "' ";
                $mf_h_table = " h." . $mainhead_name . " = '" . $this->request->getGet('mf') . "' ";
            }
            if ($this->request->getGet('mf') == 'F') {
                $mf_f2_table = " f2." . $mainhead_name . " = '" . $this->request->getGet('mf') . "' ";
                $mf_h_table = " h." . $mainhead_name . " = '" . $this->request->getGet('mf') . "' ";
            }
            if ($this->request->getGet('mf') == 'N') {
                $mf_f2_table = " (f2." . $mainhead_name . " NOT IN ('M', 'F')) ";
                $mf_h_table = "( h." . $mainhead_name . " NOT IN ('M', 'F'))";
            }
        }



        if (trim($this->request->getGet('subject')) != 'all,' || trim($this->request->getGet('act')) != 'all,' || trim($this->request->getGet('act_msc')) != '') {
            $mul_cat_join = " LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no
                              LEFT JOIN master.submaster s ON mc.submaster_id = s.id";
        } else {
            $mul_cat_join = " ";
        }
        if (trim($this->request->getGet('subcat2')) == 'all,') {


            if (trim($this->request->getGet('subcat')) == 'all,') {
                if (trim($this->request->getGet('cat')) == 'all,') {
                    if (trim($this->request->getGet('subject')) == 'all,') {
                        $all_category = " ";
                    } else {
                        $all_category = "s.subcode1 IN (" . substr($this->request->getGet('subject'), 0, -1) . ")";
                    }
                } else {
                    $head1 = explode(',', $this->request->getGet('cat'));
                    $str_all_cat = "";
                    for ($m = 0; $m < $this->request->getGet('cat_length'); $m++) {
                        $head = explode('|', $head1[$m]);
                        if ($m == 0) {
                            $str_all_cat = "(s.subcode1 = '" . $head[0] . "' AND s.subcode2 = '" . $head[1] . "')";
                        } else {
                            $str_all_cat = "((s.subcode1 = '" . $head[0] . "' AND s.subcode2 = '" . $head[1] . "') OR " . $str_all_cat . ")";
                        }
                    }
                   
                   
                    $all_category = $str_all_cat;
                }
            } else {
                $head1 = explode(',', $this->request->getGet('subcat'));
                $str_all_cat = "";
                for ($m = 0; $m < $this->request->getGet('subcat_length'); $m++) {
                    $head = explode('|', $head1[$m]);
                    if ($m == 0) {
                        $str_all_cat = "(s.subcode1 = '" . $head[0] . "' AND s.subcode2 = '" . $head[1] . "' AND s.subcode3 = '" . $head[2] . "')";
                    } else {
                        $str_all_cat = "((s.subcode1 = '" . $head[0] . "' AND s.subcode2 = '" . $head[1] . "' AND s.subcode3 = '" . $head[2] . "') OR " . $str_all_cat . ")";
                    }
                }
                $all_category = $str_all_cat;
            }
        } else {
            $head1 = explode(',', $this->request->getGet('subcat2'));
            $str_all_cat = "";
            for ($m = 0; $m < $this->request->getGet('subcat2_length'); $m++) {
                $head = explode('|', $head1[$m]);
                if ($m == 0) {
                    $str_all_cat = "(s.subcode1 = '" . $head[0] . "' AND s.subcode2 = '" . $head[1] . "' AND s.subcode3 = '" . $head[2] . "' AND s.subcode4 = '" . $head[3] . "')";
                } else {
                    $str_all_cat = "((s.subcode1 = '" . $head[0] . "' AND s.subcode2 = '" . $head[1] . "' AND s.subcode3 = '" . $head[2] . "' AND s.subcode4 = '" . $head[3] . "') OR " . $str_all_cat . ")";
                }
            }
            $all_category = $str_all_cat;
        }

        if (trim($this->request->getGet('act')) == 'all,') {
            $all_act = " ";
        } else {
            if (trim($this->request->getGet('subject')) == 'all,') {
                $all_act = " a.act IN (" . substr($this->request->getGet('act'), 0, -1) . ")";
            } else {
                $all_act = " OR a.act IN (" . substr($this->request->getGet('act'), 0, -1) . ")";
            }
        }
      
        if (trim($this->request->getGet('act')) == 'all,' && trim($this->request->getGet('subject')) == 'all,') {
            $cat_and_act = " ";
        } else {
            $cat_and_act = " AND ( " . $all_category . " " . $all_act . " )";
        }

        if ($this->request->getGet('from_year') == '' || $this->request->getGet('to_year') == '') {
            if ($this->request->getGet('from_year') == '' && $this->request->getGet('to_year') != '') {
                $year_main = " AND SUBSTR(m.diary_no::text, -4) <= '" . $this->request->getGet('to_year') . "' ";
                $year_lastheardt = " AND SUBSTR(m.diary_no::text, -4) <= '" . $this->request->getGet('to_year') . "' ";
            } elseif ($this->request->getGet('from_year') != '' && $this->request->getGet('to_year') == '') {
                $year_main = " AND SUBSTR(m.diary_no::text, -4) >= '" . $this->request->getGet('from_year') . "' ";
                $year_lastheardt = " AND SUBSTR(m.diary_no::text, -4) >= '" . $this->request->getGet('from_year') . "' ";
            } else {
                $year_main = " ";
                $year_lastheardt = " ";
            }
        } else {
            $year_main = " AND SUBSTR(m.diary_no::text, -4) BETWEEN '" . $this->request->getGet('from_year') . "' AND '" . $this->request->getGet('to_year') . "' ";
            $year_lastheardt = " AND SUBSTR(m.diary_no::text, -4) BETWEEN '" . $this->request->getGet('from_year') . "' AND '" . $this->request->getGet('to_year') . "' ";
        }

        $Brep = "";
        $Brep1 = "";
        $act_join = '';
        $registration = '';
        $main_connected = '';
        $pc_act = $women = $children = $land = $cr_compound = $commercial_code = $party_name = $pet_res = $act_msc = '';
        $from_fil_dt = $this->request->getGet('from_fil_dt') ?
            " AND DATE(m.diary_no_rec_date) > '" . date('Y-m-d', strtotime($this->request->getGet('from_fil_dt'))) . "' " : " ";

        $upto_fil_dt = $this->request->getGet('upto_fil_dt') ?
            " AND DATE(m.diary_no_rec_date) < '" . date('Y-m-d ', strtotime($this->request->getGet('upto_fil_dt'))) . "' " : " ";
       
        $add_table = '';
        $case_status_id = " ";
        if ($this->request->getGet('case_status_id') == 'all,') {
            $case_status_id = " AND case_status_id IN (1, 2, 3, 6, 7, 9) ";
            $add_table = '';
        } elseif ($this->request->getGet('case_status_id') == '103,' || $this->request->getGet('case_status_id') == 103) {
            
            $registration = " ";
        } elseif ($this->request->getGet('case_status_id') == 101 || $this->request->getGet('case_status_id') == '101,') {
            $registration = " (active_fil_no = '' OR active_fil_no IS NULL) AND ";
            
        } elseif ($this->request->getGet('case_status_id') == 102 || $this->request->getGet('case_status_id') == '102,') {
            $registration = " NOT (active_fil_no = '' OR active_fil_no IS NULL)  AND ";
          
        } elseif ($this->request->getGet('case_status_id') == 104 || $this->request->getGet('case_status_id') == '104,') {
          
            $Brep = " INNER JOIN
            (SELECT CASE WHEN os.diary_no IS NULL THEN m.diary_no ELSE 0 END AS dd FROM main m
             INNER JOIN docdetails b ON m.diary_no = b.diary_no
             LEFT OUTER JOIN
            (SELECT DISTINCT diary_no FROM obj_save WHERE rm_dt IS NULL AND display = 'Y')
            os ON m.diary_no = os.diary_no
             WHERE c_status = 'P' AND (active_fil_no IS NULL OR active_fil_no = '')
            AND (
            (doccode = '8' AND doccode1 = '28') OR 
            (doccode = '8' AND doccode1 = '95') OR 
            (doccode = '8' AND doccode1 = '214') OR 
            (doccode = '8' AND doccode1 = '215')
            )
            AND b.iastat = 'P') aa ON m.diary_no = aa.dd ";
        } elseif ($this->request->getGet('case_status_id') == 105 || $this->request->getGet('case_status_id') == '105,') {
            
            $Brep = " INNER JOIN
            (SELECT CASE WHEN os.diary_no IS NULL THEN m.diary_no ELSE 0 END AS dd FROM main m
             INNER JOIN docdetails b ON m.diary_no = b.diary_no
             LEFT OUTER JOIN
            (SELECT DISTINCT diary_no FROM obj_save WHERE rm_dt IS NULL AND display = 'Y')
            os ON m.diary_no=os.diary_no
             WHERE  c_status = 'P' AND (active_fil_no IS NULL OR active_fil_no='')
            AND(
            (doccode = '8' AND doccode1 = '16') OR 
            (doccode = '8' AND doccode1 = '79') OR 
            (doccode = '8' AND doccode1 = '99') OR 
            (doccode = '8' AND doccode1 = '300')
            )
            AND b.iastat='P') aa ON m.diary_no=aa.dd ";
        } elseif ($this->request->getGet('case_status_id') == 106 || $this->request->getGet('case_status_id') == '106,') {
            
            $Brep = " LEFT OUTER JOIN (SELECT DISTINCT diary_no FROM obj_save WHERE rm_dt IS NULL AND display = 'Y')
                                os ON m.diary_no=os.diary_no
                                ";
            $Brep1 = " and os.diary_no IS NOT NULL and c_status = 'P' AND (active_fil_no IS NULL OR  active_fil_no='') AND h.board_type='J'";
        } elseif ($this->request->getGet('case_status_id') == 107 || $this->request->getGet('case_status_id') == '107,') {
            $Brep = " INNER JOIN docdetails b ON m.diary_no=b.diary_no
            INNER JOIN
            (SELECT DISTINCT diary_no FROM obj_save WHERE rm_dt IS NULL AND display = 'Y' AND DATEDIFF(NOW(),save_dt)>60) os
            ON m.diary_no=os.diary_no ";
            $Brep1 = " and m.c_status = 'P' AND (m.active_fil_no IS NULL OR  m.active_fil_no='')
            AND doccode = '8' AND doccode1 = '226' AND b.iastat='P' ";
        } elseif ($this->request->getGet('case_status_id') == 108 || $this->request->getGet('case_status_id') == '108,') {
            $Brep = " INNER JOIN docdetails b ON m.diary_no=b.diary_no
            INNER JOIN
            (SELECT DISTINCT diary_no FROM obj_save WHERE rm_dt IS NULL AND display = 'Y' AND DATEDIFF(NOW(),save_dt)<=60) os
            ON m.diary_no=os.diary_no ";
            $Brep1 = " and  m.c_status = 'P' AND (m.active_fil_no IS NULL OR  m.active_fil_no='')
            AND doccode = '8' AND doccode1 = '226' AND b.iastat='P' ";
        } elseif ($this->request->getGet('case_status_id') == 109 || $this->request->getGet('case_status_id') == '109,') {
            $Brep = " LEFT JOIN (SELECT DISTINCT CASE WHEN os.diary_no IS NULL THEN m.diary_no ELSE 0 END AS dd FROM main m
             INNER JOIN docdetails b ON m.diary_no = b.diary_no
             LEFT OUTER JOIN
            (SELECT DISTINCT diary_no FROM obj_save WHERE rm_dt IS NULL AND display = 'Y')
            os ON m.diary_no=os.diary_no
             WHERE  c_status = 'P' AND (active_fil_no IS NULL OR active_fil_no='')
            AND (((
            (doccode = '8' AND doccode1 = '28') OR 
            (doccode = '8' AND doccode1 = '95') OR 
            (doccode = '8' AND doccode1 = '214') OR 
            (doccode = '8' AND doccode1 = '215') OR 
            (doccode = '8' AND doccode1 = '16') OR 
            (doccode = '8' AND doccode1 = '79') OR 
            (doccode = '8' AND doccode1 = '99') OR 
            (doccode = '8' AND doccode1 = '300') OR
            (doccode = '8' AND doccode1 = '226') OR 
            (doccode = '8' AND doccode1 = '288') OR 
            (doccode = '8' AND doccode1 = '322')
            )
            AND b.iastat='P' ))) aa ON m.diary_no=aa.dd
            LEFT OUTER JOIN
                                (SELECT DISTINCT diary_no FROM obj_save WHERE
                                (rm_dt IS NULL OR rm_dt='0000-00-00 00:00:00') AND display='Y')
                                os1 ON m.diary_no=os1.diary_no ";
            $Brep1 = " and m.c_status = 'P' AND IF((m.active_fil_no IS NULL OR m.active_fil_no=''),(aa.dd !=0 OR (os1.diary_no IS NOT NULL AND h.board_type='J')),3=3) ";
        }
        /*elseif($_GET['case_status_id']==101){
        $case_status_id=" and o.rm_dt = '0000-00-00 00:00:00' 
            AND o.display = 'Y' 
            AND m.c_status = 'P' 
            AND (m.fil_no IS NULL 
              OR m.fil_no = '')"; 
        $add_table=' LEFT JOIN obj_save o ON o.diary_no = m.diary_no ';
        }
        elseif($_GET['case_status_id']==102){
        //$case_status_id=" and m.diary_no NOT IN (SELECT DISTINCT diary_no FROM `obj_save` WHERE rm_dt = '0000-00-00 00:00:00' AND display = 'Y' ) "; 
         $case_status_id=" and (!(m.fil_no IS NULL OR m.fil_no = '')) "; 
            $add_table='';
        }*/ else {
            $case_status_id = " and case_status_id in (" . substr($this->request->getGet('case_status_id'), 0, -1) . ")";
            $add_table = '';
        }
      

        if ($this->request->getGet('mf') != 'ALL') {
            if ($this->request->getGet('til_date') != date('d-m-Y')) {
                echo '<br>';
                $t = "CREATE TEMPORARY TABLE vw2 
                        SELECT diary_no, MAX(ent_dt) AS med, " . $subhead_name . ", " . $mainhead_name . "
                        FROM `last_heardt` l
                        WHERE DATE(ent_dt) < '" . $til_dt . "' " . $year_lastheardt . " 
                        GROUP BY diary_no";
                $this->db->query($t);

                $t2 = "CREATE INDEX id_index ON vw2 (diary_no)";
                $db->query($t2);

                $t3 = "CREATE TEMPORARY TABLE vw3 
                        SELECT l.diary_no, l." . $subhead_name . ", l.judges, med, next_dt, l." . $mainhead_name . "
                        FROM vw2 
                        INNER JOIN last_heardt l ON vw2.diary_no = l.diary_no
                        AND l.ent_dt = med
                        AND l." . $mainhead_name . " = '" . $this->request->getGet('mf') . "' " . $subhead;
                $db->query($t3);

                $t4 = "CREATE INDEX id_index2 ON vw3 (diary_no)";
                $db->query($t4);
            }
        }
        

        if ($this->request->getGet('mf') != 'ALL') {
            if ($this->request->getGet('til_date') != date('d-m-Y')) {
                $sql = "
                SELECT SUBSTR(diary_no::text, -4) AS year ," . $str . " FROM 
                (
                    SELECT m.diary_no, m.fil_dt, c_status, d.rj_dt, d.month, d.year, d.disp_dt, active_casetype_id, casetype_id
                    FROM main m " . $Brep . " 
                    LEFT JOIN heardt h ON m.diary_no = h.diary_no 
                    LEFT JOIN dispose d ON m.diary_no = d.diary_no
                    LEFT JOIN restored r ON m.diary_no = r.diary_no
                    LEFT JOIN vw3 f2 ON m.diary_no = f2.diary_no 
                    LEFT JOIN act_main a ON a.diary_no = m.diary_no " . $add_table . $mul_cat_join . $act_join . "
                    WHERE 1=1 " . $Brep1 . $registration . " " . $bench . " " . $cat_and_act . " " . $year_main . " " . $from_fil_dt . " " . $upto_fil_dt . " " . $case_status_id . "
                    AND IF(med > h.ent_dt AND f2." . $mainhead_name . " IS NOT NULL, " . $mf_f2_table . " " . $subhead_if_last_heardt . ", " . $mf_h_table . " " . $subhead_if_last_heardt . ") 
                    AND (
                        " . $exclude_cond . "
                    ) " . $subhead_condition . " AND DATE(m.diary_no_rec_date) < '" . $til_dt . "' AND c_status = 'P' 
                    OR (
                        c_status = 'D' " . $cat_and_act . " " . $year_main . " " . $from_fil_dt . " " . $upto_fil_dt . "
                        AND IF(med > h.ent_dt AND f2." . $mainhead_name . " IS NOT NULL, " . $mf_f2_table . " " . $subhead_if_last_heardt . ", " . $mf_h_table . " " . $subhead_if_last_heardt . ")
                        AND " . $ason_str . " AND DATE(m.diary_no_rec_date) < '" . $til_dt . "' " . $year_main . " " . $from_fil_dt . " " . $upto_fil_dt . " " . $cat_and_act . " " . $bench . " " . $pc_act . " " . $women . " " . $children . " " . $land . " " . $cr_compound . " " . $commercial_code . " " . $party_name . " " . $pet_res . " " . $act_msc . " AND " . $exclude_cond_other . "
                    )
                    OR ( 
                        " . $ason_str_res . "
                        AND IF(med > h.ent_dt AND f2." . $mainhead_name . " IS NOT NULL, " . $mf_f2_table . " " . $subhead_if_last_heardt . ", " . $mf_h_table . " " . $subhead_if_last_heardt . ")
                        AND DATE(m.diary_no_rec_date) < '" . $til_dt . "' " . $year_main . " " . $from_fil_dt . " " . $upto_fil_dt . " " . $cat_and_act . " " . $bench . " 
                        " . $pc_act . " " . $women . " " . $children . " " . $land . " " . $cr_compound . " " . $commercial_code . " " . $party_name . " " . $pet_res . " " . $act_msc . " AND " . $exclude_cond_other . "
                    )
                    GROUP BY m.diary_no, fil_dt, c_status, d.rj_dt, d.month, d.year, d.disp_dt, active_casetype_id, casetype_id
                ) t
                GROUP BY ROLLUP(SUBSTR(diary_no::text, -4) ) ";
            } else {
               
                $sql = "
                SELECT SUBSTR(diary_no::text, -4) AS year ," . $str . " FROM 
                (
                    SELECT m.diary_no, m.fil_dt, c_status, d.rj_dt, d.month, d.year, d.disp_dt, active_casetype_id, casetype_id
                    FROM main m " . $Brep . " 
                    LEFT JOIN dispose d ON m.diary_no = d.diary_no
                    LEFT JOIN heardt h ON m.diary_no = h.diary_no 
                    LEFT JOIN restored r ON m.diary_no = r.diary_no
                    LEFT JOIN act_main a ON a.diary_no = m.diary_no
                    " . $add_table . $mul_cat_join . $act_join . "
                    WHERE " . $registration . " " . $mf_h_table . " " . $cat_and_act . " " . $year_main . " " . $from_fil_dt . " " . $upto_fil_dt . " " . $case_status_id . $Brep1 . "
                    AND case_status_id IN (1, 2, 3, 6, 7, 9) 
                    AND (c_status = 'P' AND DATE(m.diary_no_rec_date) < '" . $til_dt . "') " . $subhead_condition . "
                    GROUP BY m.diary_no, fil_dt, c_status, d.rj_dt, d.month, d.year, d.disp_dt, active_casetype_id, casetype_id 
                ) t
                GROUP BY ROLLUP(SUBSTR(diary_no::text, -4) ) ";
            }
        } else {
            if ($this->request->getGet('til_date') != date('d-m-Y')) {
                $sql = "
                SELECT SUBSTR(diary_no::text, -4) AS year ," . $str . " FROM 
                (
                    SELECT m.diary_no, m.fil_dt, c_status, d.rj_dt, d.month, d.year, d.disp_dt, active_casetype_id, casetype_id 
                    FROM main m " . $Brep . " 
                    LEFT JOIN heardt h ON m.diary_no = h.diary_no 
                    LEFT JOIN dispose d ON m.diary_no = d.diary_no 
                    LEFT JOIN restored r ON m.diary_no = r.diary_no 
                    LEFT JOIN act_main a ON a.diary_no = m.diary_no " . $add_table . $mul_cat_join . " " . $act_join . "
                    WHERE 1=1 " . $Brep1 . $registration . " " . $cat_and_act . " " . $year_main . " " . $from_fil_dt . " " . $upto_fil_dt . " " . $case_status_id . " " . $main_connected . " AND 
                    (
                        " . $exclude_cond . "
                    ) AND DATE(m.diary_no_rec_date) < '" . $til_dt . "' AND c_status = 'P' 
                    OR 
                    (
                        c_status = 'D' AND " . $ason_str . " " . $cat_and_act . " " . $year_main . " " . $from_fil_dt . " " . $upto_fil_dt . " AND DATE(m.diary_no_rec_date) < '" . $til_dt . "' AND " . $exclude_cond_other . " " . $main_connected . "
                    )
                    GROUP BY m.diary_no, fil_dt, c_status, d.rj_dt, d.month, d.year, d.disp_dt, active_casetype_id, casetype_id 
                ) t
                GROUP BY ROLLUP(SUBSTR(diary_no::text, -4) ) ";
            } else {
                $sql = "
                SELECT SUBSTR(diary_no::text, -4) AS year ," . $str . " FROM 
                (
                    SELECT m.diary_no, m.fil_dt, c_status, d.rj_dt, d.month, d.year, d.disp_dt, active_casetype_id, casetype_id
                    FROM main m " . $Brep . " 
                    LEFT JOIN dispose d ON m.diary_no = d.diary_no
                    LEFT JOIN restored r ON m.diary_no = r.diary_no
                    LEFT JOIN heardt h ON m.diary_no = h.diary_no 
                    LEFT JOIN act_main a ON a.diary_no = m.diary_no " . $add_table . $mul_cat_join . " " . $act_join . "
                    WHERE 2=2 " . $Brep1 . $registration . " " . $bench . " " . $cat_and_act . " " . $year_main . " " . $from_fil_dt . " " . $upto_fil_dt . " " . $case_status_id . " AND (c_status = 'P' AND DATE(m.diary_no_rec_date) <= '" . $til_dt . "')
                    GROUP BY m.diary_no, fil_dt, c_status, d.rj_dt, d.month, d.year, d.disp_dt, active_casetype_id, casetype_id 
                ) t
                GROUP BY ROLLUP(SUBSTR(diary_no::text, -4) ) ";
                
            }
        }
        $query = $this->db->query($sql);
        $data['results'] = $results = $query->getResultArray();
        $data['tot_row'] = count($results);
        $data['civil_colspan'] = $this->tot_case_in_nature('C');
        $data['cr_colspan'] = $this->tot_case_in_nature('R');
        $data['til_dt']   = $til_dt;
        $data['head_subhead'] = $head_subhead;
        $data['rpt_type'] = $this->request->getGet('rpt_type');
        $data['db'] = \Config\Database::connect();
        return view('ManagementReport/Pending/get_year_head_nature_wise_ason_rpt', $data);
    }
