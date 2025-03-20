<?php

namespace App\Models\Filing;

use CodeIgniter\Model;

class VerificationModel extends Model
{
    protected $session;

    protected $table1 = 'defects_verification';
    protected $table = 'fil_trap';
    protected $allowedFields = [
        'limit_days',
        'descr',
        'case_nature',
        'under_section',
        'o_s',
        'pol',
        'o_d',
        'f_d',
        'c_d_a',
        'd_o_d',
        'case_lim_display',
        'diary_no',
        'lowerct_id',
        'order_cof',
        'd_o_a',
        'case_lmt_user',
        'updated_on',
        'updated_by',
        'case_lmt_ent_dt',
        'updated_by_ip',
        'r_by_empid',
        'd_to_empid',
        'disp_dt',
        'rece_dt',
        'comp_dt',
        'd_by_empid',
        'other',
        'remarks',
        'create_modify',

    ];



    public function __construct()
    {
        parent::__construct();
        $db = \Config\Database::connect();
        $this->db = db_connect();
    }
    public function getUnverifiedDefects($dairy_no)
    {
        $builder = $this->db->table('defects_verification a');
        $builder->select('*');
        $builder->where('verification_status', '0');
        $builder->where('diary_no', $dairy_no);

        $query = $builder->get();
        return $query->getResult();
    }

    public function getPetDetailsByDiaryNo($dairy_no)
    {
        $builder = $this->db->table("public.advocate");
        $builder->select("pet_res_no,adv,adv_type,advocate_id");
        $builder->select("COALESCE(NULLIF(pet_res_show_no, ''), TRIM(CAST(pet_res_no AS VARCHAR)), TRIM(CAST(pet_res_show_no AS VARCHAR))) AS pet_res_show_no");
        $builder->select("name,is_ac,isdead,if_aor,if_sen,if_other");
        $builder->join("master.bar", "bar.bar_id=advocate.advocate_id");
        $builder->where('diary_no', $dairy_no);
        $builder->where('display', 'Y');
        $query = $builder->get();
        return $query->getResult();
    }

    public function verifyRecord($ucode, $result_casetype)
    {
        $builder = $this->db->table('users u');
        $builder->select('u.usercode, us.id AS section, usertype');
        $builder->join('usersection us', 'u.section = us.id');
        $builder->where('u.usercode', $ucode);
        $builder->where('u.display', 'Y');
        $query = $builder->get();

        if ($query->getNumRows() > 0 && $ucode != 1) {
            $check_section_user = $query->getRow();

            //echo "<pre>"; print_r($check_section_user); exit();

            if ($check_section_user->section != 19 && $ucode != 1494) {
                if ($check_section_user->usertype == 1 || $check_section_user->usertype == 6) {
                    $casetypes = ['9', '10', '19', '20', '25', '26', '39'];

                    if (!in_array($result_casetype, $casetypes)) {
                        return ['message' => 'Verification can be done in RP/CUR.P/CONT.P./MA'];
                    } else {
                        return ['hd_flag' => 1];
                    }
                } else {
                    return ['message' => 'Only AR/DR is authorized for Verification'];
                }
            }
        }

        return ['message' => 'Diary No. Not Found'];
    }

    public function checkSection($ucode)
    {
        $builder = $this->db->table('users u');
        $builder->select('*');
        $builder->join('usersection us', 'u.section = us.id');
        $builder->where('u.usercode', $ucode);
        $builder->where('u.display', 'Y');

        $query = $builder->get();
        return $query->getRow();
    }

    public function checkDisposed($dairy_no)
    {
        $builder = $this->db->table('main');
        $builder->select('c_status');
        $builder->where('diary_no', $dairy_no);

        $query = $builder->get();
        return $query->getResult();
    }

    public function checkIfVerified($dairy_no)
    {
        $builder = $this->db->table('defects_verification a');
        $builder->select('*');
        $builder->where('verification_status', 0);
        $builder->where('diary_no', $dairy_no);

        $query = $builder->get();
        return $query->getResult();
    }
    public function get_diary_case_type($ct, $cn, $cy)
    {
        $builder = $this->db->table('main');
        $builder->select('SUBSTR(diary_no, 1, LENGTH(diary_no) - 4) AS dn, 
                          SUBSTR(diary_no, -4) AS dy');
        $builder->where("(SUBSTRING_INDEX(fil_no, '-', 1) = '$ct' AND CAST($cn AS UNSIGNED) 
                            BETWEEN (SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no, '-', 2), '-', -1)) 
                            AND (SUBSTRING_INDEX(fil_no, '-', -1)) AND (reg_year_mh=0 OR fil_dt > '2017-05-10' OR YEAR(fil_dt) = $cy)) 
                         OR (SUBSTRING_INDEX(fil_no_fh, '-', 1) = '$ct' 
                            AND CAST($cn AS UNSIGNED) BETWEEN (SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no_fh, '-', 2), '-', -1)) 
                            AND (SUBSTRING_INDEX(fil_no_fh, '-', -1)) AND (reg_year_fh=0 OR YEAR(fil_dt_fh) = $cy))");
        $query = $builder->get();
        $result = $query->getRow();

        if ($result) {
            return $result->dn . $result->dy;
        } else {
            $builder = $this->db->table('main_casetype_history');
            $builder->select('SUBSTR(diary_no, 1, LENGTH(diary_no) - 4) AS dn, 
                              SUBSTR(diary_no, -4) AS dy');
            $builder->where("((SUBSTRING_INDEX(new_registration_number, '-', 1) = '$ct' AND 
                                CAST($cn AS UNSIGNED) BETWEEN (SUBSTRING_INDEX(SUBSTRING_INDEX(new_registration_number, '-', 2), '-', -1)) 
                                AND (SUBSTRING_INDEX(new_registration_number, '-', -1)) AND new_registration_year = $cy) 
                            OR (SUBSTRING_INDEX(old_registration_number, '-', 1) = '$ct' 
                                AND CAST($cn AS UNSIGNED) BETWEEN (SUBSTRING_INDEX(SUBSTRING_INDEX(old_registration_number, '-', 2), '-', -1)) 
                                AND (SUBSTRING_INDEX(old_registration_number, '-', -1)) AND old_registration_year = $cy))
                            AND is_deleted = 'f'");
            $query = $builder->get();
            $result = $query->getRow();

            if ($result) {
                return $result->dn . $result->dy;
            }
        }

        return null;
    }
    public function getCaveatAdvocates($diary_no)
    {
        $builder = $this->db->table('caveat_diary_matching a');
        $builder->select('b.*, name');
        $builder->join('caveat_advocate b', 'a.caveat_no = b.caveat_no', 'left');
        $builder->join('master.bar', 'b.advocate_id = bar.bar_id', 'left');
        $builder->join('advocate adv', 'adv.diary_no = a.diary_no AND b.advocate_id = adv.advocate_id', 'left');
        $builder->where('a.diary_no', $diary_no);
        $builder->where('a.display', 'Y');
        $builder->where('adv.display', 'Y');
        $query = $builder->get();

        return $query->getResult();
    }
    public function getadvocate($diary_no)
    {
        $builder = $this->db->table('advocate a');
        $builder->select('pet_res_no,adv, advocate_id, pet_res');
        $builder->where('a.diary_no', $diary_no);
        $builder->where('a.display', 'Y');
        $query = $builder->get();

        return $query->getResult();
    }
    public function getDocDetailsByDiaryNo($diary_no)
    {
        $builder = $this->db->table('docdetails a');
        $builder->select('docnum, docyear, other1, a.doccode1, docdesc, a.ent_dt');
        $builder->join('master.docmaster b', 'a.doccode = b.doccode AND a.doccode1 = b.doccode1', 'left');
        $builder->where('diary_no', $diary_no);
        $builder->where('a.doccode', 8);
        $builder->where('a.display', 'Y');
        $builder->where('b.display', 'Y');
        $builder->where('iastat', 'P');
        $builder->orderBy('a.ent_dt');

        return $builder->get()->getResult();
    }
    public function getcategory($diary_no)
    {
        $builder = $this->db->table('mul_category');
        $builder->select('b.id, category_sc_old, submaster_id, subcode1, subcode2, sub_name1, sub_name2, sub_name3, sub_name4');
        $builder->join('master.submaster b', 'submaster_id = b.id', 'left');
        $builder->where('diary_no', $diary_no);
        $builder->where('mul_category.display', 'Y');
        
        return $builder->get()->getResult();
    }
    public function getcategory1($diary_no)
    {
        $builder = $this->db->table('mul_category a');
        $builder->select('*');
        $builder->where('a.display', 'Y');
        $builder->whereIn('a.submaster_id', [222, 176]);
        $builder->where('a.diary_no', $diary_no);

        $query = $builder->get();
        $data = $query->getResult();
        $chk_hr = count($data);

        return ['count' => $chk_hr, 'data' => $data];
    }

    public function other_categoryinfo($diary_no)
    {
        $builder = $this->db->table('other_category a');
        $builder->select('*');
        $builder->where('a.display', 'Y');
        $builder->where('diary_no', $diary_no);

        return $builder->get()->getResult();
    }



    public function checkFiltrapuser($ucode)
    {
        $builder = $this->db->table('fil_trap_users a');
        $builder->select('usertype, b.type_name, disp_flag');
        $builder->join('master.usertype b', 'a.usertype = b.id');
        $builder->where('b.display', 'E');
        $builder->where('a.usercode', $ucode);
        $builder->where('a.display', 'Y');

        $query = $builder->get();

        return $query->getResult();
    }

    public function checkTaggingUser($ucode)
    {
        $builder = $this->db->table('fil_trap_users a');
        $builder->select('a.usercode, b.name, empid');
        $builder->join('master.users b', 'a.usercode = b.usercode');
        $builder->where('a.usertype', 106);
        $builder->where('a.display', 'Y');
        $builder->where('b.display', 'Y');
        $builder->where('attend', 'P');
        $builder->where('b.usercode', $ucode);

        $query = $builder->get();

        return $builder->get()->getResult();
    }

    public function checkproof($diary_no)
    {
        $builder = $this->db->table('docdetails');
        $builder->select('docd_id');
        $builder->where('diary_no', $diary_no);
        $builder->where('doccode', '18');

        $query = $builder->get();
    }
    public function getNature($diaryNo)
    {
        $builder = $this->db->table('main a');
        $builder->select('short_description');
        $builder->join('master.casetype b', 'b.casecode = a.casetype_id', 'left');
        $builder->where('diary_no', $diaryNo);

        $query = $builder->get();
        return $query->getRow();
    }
    public function getbench($diary_no)
    {
        $builder = $this->db->table('main a');
        $builder->select('a.pno, a.rno, a.from_court, pet_name, res_name, c_status, ref_agency_state_id, ref_agency_code_id, active_fil_no, active_reg_year,
        name as agency_state, agency_name, short_description, active_fil_dt, diary_no_rec_date, h.next_dt, roster_id, clno, h.brd_slno, h.board_type, h.main_supp_flag');
        $builder->join('master.state b', 'ref_agency_state_id = b.id_no', 'left');
        $builder->join('master.ref_agency_code c', 'ref_agency_code_id = c.id', 'left');
        $builder->join('master.casetype d', 'active_casetype_id = casecode', 'left');
        $builder->join('heardt h', 'a.diary_no = h.diary_no', 'left');
        $builder->join('master.judge j', "h.judges LIKE '%' || jcode || '%'", 'left');

        $builder->where('a.diary_no', $diary_no);
        $query = $builder->get();
        return $query->getRow();
    }




    public function coram_detail($diary_no)
    {

        $builder1 = $this->db->table("public.coram c");
        $builder1->select("j.jcode,string_agg(concat(j.jname,'[',j.abbreviation,']'),'') as jname,c.diary_no,'C' AS notbef,c.ent_dt,n.res_add, c.jud as coram,j.judge_seniority,c.usercode");
        $builder1->join("master.judge j", "j.jcode = ANY(string_to_array(CAST(c.jud AS VARCHAR), ',')::int[]) > '0'");
        $builder1->join("master.not_before_reason n", "n.res_id = c.res_id", "left");
        $builder1->where("c.diary_no", $diary_no);
        $builder1->where("c.to_dt", NULL);
        $builder1->where("c.display", "Y");
        $builder1->groupBy("c.diary_no,j.jcode,c.ent_dt,n.res_add,c.jud,c.usercode");


        $builder2 = $this->db->table("public.heardt h");
        $builder2->select("j.jcode,string_agg(concat(j.jname,'[',j.abbreviation,']'),'') as jname,h.diary_no,'C' AS notbef,h.ent_dt,n.res_add, CAST(h.coram AS int),j.judge_seniority,h.usercode");
        $builder2->join("master.judge j", "j.jcode = ANY(string_to_array(CAST(h.coram AS VARCHAR), ',')::int[]) > '0'");
        $builder2->join("master.not_before_reason n", "n.res_id = h.list_before_remark", "left");
        $builder2->where("h.diary_no", $diary_no);
        $builder2->groupBy("h.diary_no,j.jcode,h.ent_dt,n.res_add,h.coram,h.usercode");


        $builder3 = $this->db->table("public.not_before n");
        $builder3->select("j.jcode,concat(j.jname,'[',j.abbreviation,']') as jname,n.diary_no,n.notbef,n.ent_dt,nbs.res_add, 0,j.judge_seniority,n.usercode");
        $builder3->join("master.judge j", "j.jcode = n.j1", "left");
        $builder3->join("master.not_before_reason nbs", "n.res_id = nbs.res_id", "left");
        $builder3->where("n.diary_no", $diary_no);
        $builder3->orderBy("j.judge_seniority");

        $subquery = $builder1->union($builder2)->union($builder3);

        $final_query  = $this->db->newQuery()->select('a.*, u.empid, u.name')->fromSubquery($subquery, 'a')->join("master.users u", "u.usercode = a.usercode", "left");

        $query = $final_query->get();

        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return [];
        }
    }

    public function get_coram_entry_date($diary_no, $coram)
    {

        $builder1 = $this->db->table("public.coram");
        $builder1->select("jud as coram,ent_dt,usercode");
        $builder1->where("diary_no", $diary_no);
        $builder1->where("to_dt", NULL);
        $builder1->where("display", "Y");
        $builder1->where("jud", $coram);

        $builder2 = $this->db->table("public.heardt");
        $builder2->select("CAST(coram as BIGINT),ent_dt,usercode");
        $builder2->where("diary_no", $diary_no);
        $builder2->where("coram", $coram);

        $builder3 = $this->db->table("public.last_heardt");
        $builder3->select("CAST(coram as BIGINT),ent_dt,usercode");
        $builder3->where("diary_no", $diary_no);
        $builder3->where("coram", $coram);

        $subquery = $builder1->union($builder2)->union($builder3);

        $final_query  = $this->db->newQuery()->select('a.*, u.empid, u.name')->fromSubquery($subquery, 'a')->join("master.users u", "u.usercode = a.usercode", "left");

        $query = $final_query->get();

        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return [];
        }
    }

    public function get_connected($diary_no)
    {
        $builder = $this->db->table('main m');
        $builder->select("COALESCE(m.conn_key, '') as connkey");
        $builder->where('m.diary_no', $diary_no);
        $rs_main = $builder->get();

        $output = "";

        if ($rs_main->getNumRows() > 0) {
            $ro_main = $rs_main->getRowArray();
            $conn_case = $ro_main["connkey"];

            if ($conn_case != "" && $conn_case != 0) {
                $builder = $this->db->table('main m');
                $builder->select("
    (SELECT name FROM master.users WHERE usercode = t.usercode) AS username,
    SUBSTRING(CAST(m.diary_no AS TEXT), 1, LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS case_no,
    SUBSTRING(CAST(m.diary_no AS TEXT), -4) AS year,
    m.pet_name,
    m.res_name,
    t.list,
    m.c_status,
    TO_CHAR(m.fil_dt, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_f,
    CASE
        WHEN m.reg_year_mh = 0 OR m.fil_dt > '2017-05-10' THEN EXTRACT(YEAR FROM m.fil_dt)::TEXT
        ELSE m.reg_year_mh::TEXT
    END AS m_year,
    CASE
        WHEN m.diary_no = '" . $conn_case . "' THEN 'M'
        ELSE 'C'
    END AS mc1,
    TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY HH12:MI AM') AS diary_no_rec_date,
    m.fil_no,
    m.fil_no_fh,
    TO_CHAR(m.fil_dt_fh, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_fh,
    CASE
        WHEN m.reg_year_fh = 0 THEN EXTRACT(YEAR FROM m.fil_dt_fh)::TEXT
        ELSE m.reg_year_fh::TEXT
    END AS f_year,
    COALESCE(SPLIT_PART(m.fil_no, '-', 1), '') AS ct1,
    COALESCE(SPLIT_PART(SPLIT_PART(m.fil_no, '-', 2), '-', -1), '') AS crf1,
    COALESCE(SPLIT_PART(m.fil_no, '-', -1), '') AS crl1,
    COALESCE(SPLIT_PART(m.fil_no_fh, '-', 1), '') AS ct2,
    COALESCE(SPLIT_PART(SPLIT_PART(m.fil_no_fh, '-', 2), '-', -1), '') AS crf2,
    COALESCE(SPLIT_PART(m.fil_no_fh, '-', -1), '') AS crl2,
    m.casetype_id,
    m.case_status_id,
    TO_CHAR(m.fil_dt, 'DD-MM-YYYY HH12:MI AM') AS fldt,
    TRIM(LEADING '0' FROM SUBSTRING(CAST(m.diary_no AS TEXT), 3, 3)) AS ccode,
    m.diary_no,
    t.conn_type,
    TO_CHAR(t.ent_dt, 'DD-MM-YYYY HH12:MI AM') AS endt
");

                $builder->join('conct t', "CAST(t.conn_key AS text) = m.conn_key AND m.diary_no = t.diary_no", 'left');
                $builder->where('m.conn_key', $conn_case);
                $builder->orderBy('mc1 DESC, endt, diary_no_rec_date');
                $query = $builder->get();

                if ($query->getNumRows() > 0) {
                    $output .= '<table border="1" style="border-collapse: collapse; border-color: black;">';
                    $output .= '<tr><th>&nbsp;</th><th>&nbsp;</th><th align="center">Case No.</th><th>Petitioner vs. Respondent</th><th>List</th><th>Status</th><th>Stat. Info.</th><th>IA</th><th>DA</th><th>Entry By & Date</th></tr>';
                    $cntt = 0;

                    foreach ($query->getResultArray() as $row_conn) {
                        // $t_fil_no = get_case_nos($row_conn['diary_no'], '&nbsp;&nbsp;');

                        // if (trim($t_fil_no) == '') {
                        //     $sql12 = "SELECT short_description FROM casetype WHERE casecode='" . $row_conn['casetype_id'] . "'";
                        //     $results12 = pg_query($sql12) or die(pg_last_error() . " SQL:" . $sql12);
                        //     if (pg_affected_rows($results12) > 0) {
                        //         $row_12 = pg_fetch_array($results12);
                        //         $t_fil_no = $row_12['short_description'];
                        //     }
                        // }

                        // if (trim($t_fil_no) == '') {
                        //     $sql12 =   "SELECT short_description from casetype where casecode='" . $row_conn['casetype_id'] . "'";
                        //     $results12 = mysql_query($sql12) or die(mysql_error() . " SQL:" . $sql12);
                        //     if (mysql_affected_rows() > 0) {
                        //         $row_12 = mysql_fetch_array($results12);
                        //         $t_fil_no = $row_12['short_description'];
                        //     }
                        // }
                        $cntt++;
                        // if (trim($t_fil_no) == '') {
                        //     $sql12 = "SELECT short_description FROM casetype WHERE casecode='" . pg_escape_string($row_conn['casetype_id']) . "'";
                        //     $results12 = pg_query($sql12) or die(pg_last_error() . " SQL:" . $sql12);
                        //     if (pg_num_rows($results12) > 0) {
                        //         $row_12 = pg_fetch_array($results12);
                        //         $t_fil_no = $row_12['short_description'];
                        //     }
                        // }
                        // DA NAME START FOR CONNECTED
                        $da_name_conn = "";
                        $sql_da_conn = "SELECT dacode, name, section_name FROM main a LEFT JOIN master.users b ON dacode = b.usercode LEFT JOIN master.usersection us ON b.section = us.id WHERE diary_no = '" . $row_conn["diary_no"] . "' AND dacode != 0 ";

                        $results_da_conn = $this->db->query($sql_da_conn);

                        if ($results_da_conn->getNumRows() > 0) {
                            $row_da_conn = $results_da_conn->getRowArray();
                            $da_name_conn = "<font color='blue' style='font-size:12px;font-weight:bold;'>" . $row_da_conn["name"] . "</font><br>";
                            if (!empty($row_da_conn["dacode"]))
                                $da_name_conn .= "<font style='font-size:12px;font-weight:bold;'> [SECTION : </font><font color='red' style='font-size:12px;font-weight:bold;'>" . $row_da_conn["section_name"] . "</font><font style='font-size:12px;font-weight:bold;'>]</font>";
                        }
                        // DA NAME ENDS FOR CONNECTED
                        $bgcolor = ($cntt % 2 == 1) ? "#FDFEFF" : "#F5F6F7";
                        $t_mc = ($row_conn["mc1"] == "M") ? "<font style='color:blue;'>M</font>" : "<font style='color:blue;'>" . $row_conn["conn_type"] . "</font>";


                        // echo "<pre>"; print_r($row_conn); exit();
                        $output .= '<tr height="25px" bgcolor="' . $bgcolor . '"><td><b>' . $cntt . '</b></td><td><b>' . $t_mc . '</b></td>';
                        $output .= '<td>' . $row_conn['case_no']  . '/' . substr($row_conn['year'], -4) . '<br><font size=-1 color=grey>(' . $row_conn["diary_no_rec_date"] . ')</font></br>'  . '</td>';
                        $output .= '<td>' . $row_conn['pet_name'] . ' vs.<br>' . $row_conn['res_name'] . '</td><td align=center>';

                        if ($row_conn["mc1"] != "M") {
                            $output .= ($row_conn['list'] == 'N') ? "<font style='color:red;'>N</font>" : "<font color=red;>Y</font>";
                        } else {
                            $output .= "<font style='color:red;'>-</font>";
                        }

                        $output .= '</td><td align=center>';
                        $output .= ($row_conn['c_status'] == 'D') ? "<font style='color:red;'>D</font>" : "<font color=red;>P</font>";

                        $ia_conn = "";
                        $ia = "SELECT * FROM docdetails WHERE doccode='8' AND diary_no='" . $row_conn["diary_no"] . "' AND display='Y' ORDER BY ent_dt";
                        $result_ia = $this->db->query($ia)->getResultArray();
                        $other1 = '';
                        if (!empty($result_ia)) {
                            foreach ($result_ia as $row_ia) {
                                $docnum = $row_ia['docnum'];
                                $docyear = $row_ia['docyear'];
                                $doccode = $row_ia['doccode'];
                                $doccode1 = $row_ia['doccode1'];
                                $docdesc1 = $row_ia['other1'];

                                $sql_docm = "SELECT * FROM master.docmaster WHERE doccode='" . $doccode . "' AND doccode1='" . $doccode1 . "' AND display='Y'";
                                $query_docm = $this->db->query($sql_docm);
                                $result_docm = $query_docm->getResultArray();
                                if (!empty($result_docm)) {
                                    $row_docm = $result_docm[0]; // Assuming it returns only one row
                                    $docdesc = $row_docm['docdesc'];

                                    if (trim($docdesc) == 'OTHER') {
                                        $docdesc = $docdesc1;
                                    }
                                    if (trim($docdesc) == 'XTRA') {
                                        $docdesc = $other1;
                                    }

                                    $iastat = ($row_ia['iastat'] == "D") ? "(<font color=red>" . $row_ia['iastat'] . "</font>)" : "(<font color=blue>" . $row_ia['iastat'] . "</font>)";
                                    if ($ia_conn == "") {
                                        $ia_conn .= $docnum . "/" . $docyear . " " . $iastat . " <br><font color=green>" . $docdesc . "</font>";
                                    } else {
                                        $ia_conn .= "<br>" . $docnum . "/" . $docyear . " " . $iastat . " <br><font color=green>" . $docdesc . "</font>";
                                    }
                                }
                            }
                        }


                        $t_bfnbf = "";
                        $builder_stat = $this->db->table('brdrem');
                        $builder_stat->select('remark');
                        $builder_stat->where('diary_no', $row_conn["diary_no"]);
                        $query_stat = $builder_stat->get();

                        if ($query_stat->getNumRows() > 0) {
                            $row_stat = $query_stat->getRowArray();
                            $t_bfnbf = $row_stat["remark"];
                        }

                        $enteredby = $row_conn['username'] . '<br>' . $row_conn['endt'];
                        $output .= '</td><td>' . $t_bfnbf . '</td><td>' . $ia_conn . '</td><td>' . $da_name_conn . '</td><td>' . $enteredby . '</td></tr>';
                    }
                    $output .= '</table>';
                } else {
                    $output .= '<p align=center><font color=red><b>CONNECTED MATTERS NOT FOUND</b></font></p>';
                }
            }
        }

        return $output;
    }


    public function checkCaveat($hd_diary_nos)
    {
        return $this->db->table('caveat_diary_matching')
            ->selectCount('diary_no')
            ->where('diary_no', $hd_diary_nos)
            ->where('display', 'Y')
            ->get()
            ->getRowArray();
    }

    public function checkWithDocument($hd_diary_nos)
    {
        return $this->db->table('docdetails')
            ->where('diary_no', $hd_diary_nos)
            ->where('doccode', 8)
            ->where('doccode1', 16)
            ->where('iastat', 'P')
            ->where('display', 'Y')
            ->countAllResults();
    }

    public function checkDocument($hd_diary_nos)
    {
        return $this->db->table('docdetails')
            ->selectCount('diary_no')
            ->where('diary_no', $hd_diary_nos)
            ->where('display', 'Y')
            ->where('doccode', 18)
            ->where('doccode1', 0)
            ->get()
            ->getRowArray();
    }
    public function checkDefectsVerificationCount($hd_diary_nos)
    {
        return $this->db->table('defects_verification')
            ->selectCount('id')
            ->where('diary_no', $hd_diary_nos)
            ->get()
            ->getRowArray();
    }

    public function insertDefectsVerification($data)
    {
        return $this->db->table('defects_verification')->insert($data);
    }

    public function updateDefectsVerification($data, $hd_diary_nos)
    {
        return $this->db->table('defects_verification')
            ->where('diary_no', $hd_diary_nos)
            ->update($data);
    }

    public function getuid($diary_no)
    {
        $builder = $this->db->table('fil_trap u');
        $builder->select('uid');
        $builder->where('diary_no', $diary_no);

        $query = $builder->get();
        return $query->getRow();
    }
    public function getfiltap($diary_no)
    {
        $builder = $this->db->table('fil_trap u');
        $builder->select('*');
        $builder->where('diary_no', $diary_no);

        $query = $builder->get();
        return $query->getRow();
    }
    public function getUidByDiaryNo($diary_no)
    {
        $builder = $this->db->table('fil_trap u');
        $builder->select('uid');
        $builder->where('diary_no', $diary_no);
        $query = $builder->get();
        $result = $query->getRow();
        return $result ? $result->uid : null;
    }
    public function getremarks($diary_no)
    {
        $builder = $this->db->table('fil_trap u');
        $builder->select('remarks');
        $builder->where('diary_no', $diary_no);
        $query = $builder->get();
        $result = $query->getRow();
        return $result ? $result->remarks : null;
    }


    public function updateFilTrap($uid, $data)
    {

        $this->where('uid', $uid)
            ->set($data)
            ->update();
    }
    public function updateheardt($diary_no, $data)
    {

        try {
            $builder = $this->db->table('heardt');
            $builder->where('diary_no', $diary_no);
            $builder->set($data);
            $builder->update();

            return true;
        } catch (Exception $e) {
            log_message('error', 'Error updating data in the database: ' . $e->getMessage());
            return false;
        }
    }

    public function insertIntoFilTrap($data)
    {

        return $this->db->table('fil_trap')->insert($data);
    }
    public function updateFilTraphis($uid, $data)
    {

        try {
            $builder = $this->db->table('fil_trap_his');
            $builder->where('uid', $uid);
            $builder->set($data);
            $builder->update();

            return true;
        } catch (Exception $e) {
            log_message('error', 'Error updating data in the database: ' . $e->getMessage());
            return false;
        }
    }
    public function insertIntoFilTrapHis($data)
    {
        try {
            $this->db->table('fil_trap_his')->insert($data);
            return true;
        } catch (Exception $e) {
            log_message('error', 'Error inserting data into the database: ' . $e->getMessage());
            return false;
        }
    }

    public function check_if_CAT_ava()
    {
        $builder = $this->db->table('master.users a');
        $builder->select('usercode, name as to_name, empid as to_userno');
        $builder->where('a.usertype', 59);
        $builder->like('a.name', 'CATEGORIZATION');
        $query = $builder->get();

        $result = $query->getResult();

        $toUsernos = array_column($result, 'to_userno');

        return $toUsernos;
    }
    public function check_if_TAG_ava1()
    {
        $builder = $this->db->table('master.users a');
        $builder->select('usercode, name as to_name, empid as to_userno');
        $builder->where('a.usertype', 106);
        $query = $builder->get();
        $result = $query->getResult();
        $toUsernos = array_column($result, 'to_userno');

        return $toUsernos;
    }
    public function check_if_TAG_ava()
    {
        $builder = $this->db->table('fil_trap_users a');
        $builder->select('a.usercode, b.name, empid');
        $builder->join('master.users b', 'a.usercode = b.usercode');
        $builder->where('a.usertype', 106);
        $builder->where('a.display', 'Y');
        $builder->where('b.display', 'Y');
        $builder->where('attend', 'P');
        $builder->orderBy('empid');
        $query = $builder->get();
        $result = $query->getResult();
        $rowCount = count($result);
        return [
            'result' => $result,
            'rowCount' => $rowCount
        ];
    }

    public function check_ava_q()
    {
        $builder = $this->db->table('fil_trap_users a');
        $builder->select('a.usercode as to_usercode, b.name as to_name, empid as to_userno, ddate, c.no as curno');
        $builder->join('master.users b', 'a.usercode = b.usercode');
        $builder->join('fil_trap_seq c', 'c.no < empid', 'left');
        $builder->where('a.usertype', 106);
        $builder->where('a.display', 'Y');
        $builder->where('b.display', 'Y');
        $builder->where('attend', 'P');
        $builder->where('utype', 'TAG');
        $builder->where('ddate', 'CURRENT_DATE', false);
        $builder->orderBy('to_userno');
        $query = $builder->get();
        $result = $query->getResult();
        $rowCount = count($result);
        return [
            'result' => $result,
            'rowCount' => $rowCount
        ];
    }
    public function main($diary_no)
    {
        $builder = $this->db->table('main a');
        $builder->select('actcode,fixed,bailno,fil_dt,pet_name,res_name,case_grp,casetype_id,active_fil_no,active_fil_dt,nature');
        $builder->where('a.diary_no', $diary_no);
        $query = $builder->get();
        return $query->getResult();
    }
    public function getHeardtByDiaryNo($diary_no)
    {
        $builder = $this->db->table('heardt');
        $builder->select('*');
        $builder->where('diary_no', $diary_no);
        $query = $builder->get();
        $data = $query->getResult();
        $chk_hr = count($data);
        return ['count' => $chk_hr, 'data' => $data];
    }
    public function getlastHeardtByDiaryNo($diary_no)
    {
        $builder = $this->db->table('last_heardt');
        $builder->select('*');
        $builder->where('diary_no', $diary_no);
        $query = $builder->get();
        $data = $query->getResult();
        $chk_hr = count($data);
        return ['count' => $chk_hr, 'data' => $data];
    }
    public function check_mention_memo($diary_no)
    {
        $currentDate = date('Y-m-d');
        $builder = $this->db->table('mention_memo');
        $builder->select('diary_no');
        $builder->where('diary_no', $diary_no);
        $builder->where('result', 'Y');
        $builder->where('date_for_decided >', $currentDate);
        $query = $builder->get();
        $data = $query->getResult();
        $chk_hr = count($data);
        return ['count' => $chk_hr, 'data' => $data];
    }

    public function ia($diary_no)
    {
        $builder = $this->db->table('docdetails as a');
        $builder->select('a.doccode, a.doccode1, a.docnum, a.docyear, a.filedby, a.other1, a.ent_dt, b.docdesc');
        $builder->join('master.docmaster as b', 'a.doccode = b.doccode AND a.doccode1 = b.doccode1');
        $builder->where('a.doccode', '8');
        $builder->where('a.diary_no', $diary_no);
        $builder->where('a.iastat', 'P');
        $builder->where('a.display', 'Y');
        $builder->where('b.display', 'Y');
        $builder->orderBy('CASE WHEN a.doccode1 = 28 THEN 1 ELSE a.doccode1 END', '', false);
        $query = $builder->get();
        $data = $query->getResult();
        $chk_hr = count($data);
        return ['count' => $chk_hr, 'data' => $data];
    }
    public function ia_of_case($diary_no)
    {
        $builder = $this->db->table('docdetails as a');
        $builder->distinct();
        $builder->select('listable');
        $builder->select("CASE WHEN listable = 'J' THEN 0 ELSE 1 END as listable_order");
        $builder->join('master.docmaster as b', 'a.doccode = b.doccode AND a.doccode1 = b.doccode1', 'LEFT');
        $builder->where('a.diary_no', $diary_no);
        $builder->where('a.doccode', 8);
        $builder->where('a.display', 'Y');
        $builder->where('b.display', 'Y');
        $builder->where('a.iastat', 'P');
        $builder->where('LENGTH(listable) =', 1);
        $builder->orderBy('listable_order', 'ASC');
        $builder->orderBy('listable', 'ASC');
        $query = $builder->get();
        $data = $query->getResult();
        $chk_hr = count($data);

        return ['count' => $chk_hr, 'data' => $data];
    }
    public function category($diary_no)
    {
        $builder = $this->db->table('mul_category a');
        $builder->select('submaster_id, subcode1, subcode2');
        $builder->join('master.submaster b', 'a.submaster_id = b.id', 'LEFT');
        $builder->where('a.diary_no', $diary_no);
        $builder->where('a.display', 'Y');
        $query = $builder->get();
        $data = $query->getResult();
        $chk_hr = count($data);
        return ['count' => $chk_hr, 'data' => $data];
    }
    public function act_main($diary_no)
    {
        $builder = $this->db->table('act_main a');
        $builder->select('id,act');
        $builder->where('a.diary_no', $diary_no);
        $builder->where('a.display', 'Y');
        $query = $builder->get();
        $data = $query->getResult();
        $chk_hr = count($data);

        return ['count' => $chk_hr, 'data' => $data];
    }
    public function caveat_mat($diary_no)
    {
        $builder = $this->db->table('caveat_diary_matching a');
        $builder->select('*');
        $builder->where('a.diary_no', $diary_no);
        $builder->where('a.display', 'Y');
        $query = $builder->get();
        $data = $query->getResult();
        $chk_hr = count($data);
        return ['count' => $chk_hr, 'data' => $data];
    }
    public function proof($diary_no)
    {
        $builder = $this->db->table('docdetails a');
        $builder->select('docd_id');
        $builder->where('a.doccode', '18');
        $builder->where('a.diary_no', $diary_no);
        $builder->where('a.display', 'Y');
        $query = $builder->get();
        $data = $query->getResult();
        $chk_hr = count($data);

        return ['count' => $chk_hr, 'data' => $data];
    }
    public function chk_w($diary_no)
    {
        $builder = $this->db->table('docdetails a');
        $builder->select('docd_id');
        $builder->where('a.doccode', '18');
        $builder->where('a.diary_no', $diary_no);
        $builder->where('a.display', 'Y');
        $query = $builder->get();
        $data = $query->getResult();
        $chk_hr = count($data);

        return ['count' => $chk_hr, 'data' => $data];
    }
    public function pipchk($diary_no)
    {
        $builder = $this->db->table('advocate a');
        $builder->select(' p.state as state'); // Select the state column from both tables
        $builder->join('party p', "a.diary_no=p.diary_no AND p.state = '490506' AND p.pet_res IN ('P', 'R')", 'left');
        $builder->where('a.diary_no', $diary_no);
        $builder->whereIn('a.adv_type', ['M', 'A']);
        $builder->whereIn('a.pet_res', ['P', 'R']);
        $builder->where('a.display', 'Y');
        $builder->whereIn('a.advocate_id', [584, 585, 610, 616, 666, 940]);
        $builder->groupBy('a.diary_no, p.state');
        $query = $builder->get();
        $data = $query->getResult();
        $chk_hr = count($data);

        return ['count' => $chk_hr, 'data' => $data];
    }
    public function shortCatCase($diary_no)
    {
        $ret1 = 1;
        $submasterIds = [343, 15, 16, 17, 18, 19, 20, 21, 22, 23, 341, 353, 157, 158, 159, 160, 161, 162, 163, 166, 173, 175, 176, 322, 222];
        $builder = $this->db->table('mul_category');
        $builder->select('*');
        $builder->where('diary_no', $diary_no);
        $builder->whereIn('submaster_id', $submasterIds);
        $builder->where('display', 'Y');
        $query = $builder->get();
        $count = $query->getNumRows();
        if ($count > 0) {
            $ret1 = 2;
        }

        return $ret1;
    }
    public function top4CourtCase($diary_no)
    {
        $ret1 = 2;
        $builder1 = $this->db->table('heardt h');
        $builder1->select("(h.coram::text)");
        $builder1->where('h.diary_no', $diary_no);
        $builder1->where("POSITION(',' IN h.coram) > 0");
        $builder1->where("POSITION(SUBSTRING(h.coram FROM 1 FOR POSITION(',' IN h.coram) - 1) IN (
            SELECT STRING_AGG(j.jcode::text, ',')
            FROM (
                SELECT j.jcode
                FROM master.judge j
                WHERE j.is_retired = 'N' AND j.display = 'Y' AND j.jtype = 'J'
                ORDER BY j.judge_seniority ASC
                LIMIT 4
            ) AS j
        )) > 0");

        $builder2 = $this->db->table('not_before n');
        $builder2->select("(n.j1::text)");
        $builder2->where('n.diary_no', $diary_no);
        $builder2->where('n.notbef', 'B');
        $builder2->where("POSITION(n.j1::text IN (
            SELECT STRING_AGG(j.jcode::text, ',')
            FROM (
                SELECT j.jcode
                FROM master.judge j
                WHERE j.is_retired = 'N' AND j.display = 'Y' AND j.jtype = 'J'
                ORDER BY j.judge_seniority ASC
                LIMIT 4
            ) AS j
        )) > 0");

        $query = $builder1->get();
        $results1 = $query->getResult();
        $query = $builder2->get();
        $results2 = $query->getResult();
        $results = array_merge($results1, $results2);
        if (!empty($results)) {
            $ret1 = 1;
        }

        return $ret1;
    }
    public function nmd_misc_after_desired_dt($flag, $dtfrom)
    {
        $ret1 = "";
        if ($flag == 1) {
            $dtfromP = "misc_dt1 >= '$dtfrom'";
            $orderBy = "misc_dt1";
        } elseif ($flag == 2) {
            $dtfromP = "nmd_dt >= '$dtfrom'";
            $orderBy = "nmd_dt";
        }
        $todayTime = date("H:i:s");
        $builder = $this->db->table('master.sc_working_days');
        $builder->where($dtfromP);
        $builder->where('display', 'Y');
        $builder->orderBy($orderBy);
        $builder->limit(1);
        $query = $builder->get();
        $result = $query->getResult();
        if (!empty($result)) {
            $row = $result[0];
            if ($flag == 1) {
                $ret1 = $row['misc_dt1'];
            } elseif ($flag == 2) {
                $ret1 = $row['nmd_dt'];
            }
        }

        return $ret1;
    }
    public function nmd_misc_dt($flag)
    {
        $ret1 = "";
        $todayDate = date("Y-m-d");
        $builder = $this->db->table('master.sc_working_days');
        $builder->where('working_date', $todayDate);
        $builder->where('display', 'Y');
        $query = $builder->get();
        $result = $query->getResult();
        if (!empty($result)) {
            $row = $result[0];
            if ($flag == 1) {
                $ret1 = $row->misc_dt1;
            } elseif ($flag == 2) {
                $ret1 = $row->nmd_dt;
            }
        }

        return $ret1;
    }
    public function check_ra($diary_no, $nxt_dt)
    {
        $builder = $this->db->table('heardt');
        $builder->select('diary_no, listorder, next_dt, board_type');
        $builder->where('diary_no', $diary_no);
        $builder->whereIn('listorder', [4, 5, 7, 8, 25, 32]);
        $builder->where('next_dt >=', date('Y-m-d'));
        if ($nxt_dt !== "0000-00-00") {
            $builder->where('next_dt <=', $nxt_dt);
        } else {
            $builder->where('next_dt IS NULL'); // Corrected to check for NULL
        }

        $query = $builder->get();
        $count = $query->getNumRows();

        return ($count > 0) ? 2 : 0;
    }

    public function conn_chk_q($diary_no)
    {
        $builder = $this->db->table('conct a');
        $builder->select('a.conn_key, next_dt, b.coram, mainhead, subhead, mainhead_n, subhead_n, roster_id, judges, clno, brd_slno, jname, is_retired, jcode, c_status');
        $builder->join('heardt b', 'a.conn_key = b.diary_no', 'left');
        $builder->join('master.judge j', "CAST(j.jcode AS TEXT) = ANY(string_to_array(b.coram, ',')) AND j.is_retired='N' AND j.display='Y'", 'left');
        $builder->join('main m', 'a.conn_key = m.diary_no', 'left');
        $builder->where('a.diary_no', $diary_no);
        $builder->where('a.conn_key !=', $diary_no);
        $builder->orderBy('judge_seniority');
        $query = $builder->get();
        $data = $query->getResult();
        $chk_hr = count($data);
        return ['count' => $chk_hr, 'data' => $data];
    }
    public function check_if_FD($conn_key)
    {
        $builder = $this->db->table('case_remarks_multiple a');
        $builder->select('*');
        $builder->join('heardt b', 'a.diary_no = b.diary_no', 'left');
        $builder->join('master.judge j', "CAST(j.jcode AS TEXT) = ANY(string_to_array(b.coram, ',')) AND j.is_retired='N' AND j.display='Y'", 'left');
        $builder->join('main m', 'a.diary_no = m.diary_no', 'left');
        $builder->where('a.diary_no', $conn_key);
        $builder->where('a.cl_date', "(SELECT MAX(cl_date) FROM case_remarks_multiple WHERE diary_no = '$conn_key')", FALSE);
        $builder->where('a.r_head', 24);
        $query = $builder->get();
        $data = $query->getResult();
        $chk_hr = count($data);
        return ['count' => $chk_hr, 'data' => $data];
    }
    function revertDate($date)
    {
        $date = explode('-', $date);
        $date = $date[2] . '-' . $date[1] . '-' . $date[0];
        return $date;
    }

    public function check_if_FD2($conn_key)
    {
        $builder = $this->db->table('case_remarks_multiple a');
        $builder->select('a.diary_no, head_content, b.tentative_cl_dt');
        $builder->join('heardt b', 'a.diary_no = b.diary_no');
        $builder->where('a.diary_no', $conn_key);
        $builder->where("a.cl_date = (SELECT MAX(cl_date) FROM case_remarks_multiple WHERE diary_no = $conn_key)");
        $builder->where('listorder !=', 2);
        $builder->whereIn('a.r_head', [8, 23, 53, 54, 68]);
        $query = $builder->get();
        $data = $query->getResult();
        $chk_hr = count($data);
        return ['count' => $chk_hr, 'data' => $data];
    }


    public function check_ra1($conn_key)
    {
        $builder = $this->db->table('heardt');
        $builder->select('diary_no, listorder, next_dt');
        $builder->whereIn('listorder', [22, 5, 4, 7, 8, 25, 32]);
        $builder->where('diary_no', $conn_key);
        $query = $builder->get();
        $data = $query->getResult();
        $chk_hr = count($data);
        return ['count' => $chk_hr, 'data' => $data];
    }
    public function chk_in_l_h($conn_key, $if_fixed, $board_type, $headings_conn_main_case, $next_fixed)
    {

        if ($if_fixed == 0) {
            $sel_from_heardt = $this->db->query("SELECT * FROM heardt WHERE diary_no=?", [$conn_key])->getRowArray();
            if (!empty($sel_from_heardt)) {
                $chk_in_l_h = $this->db->table('last_heardt')
                    ->where([
                        'diary_no' => $sel_from_heardt['diary_no'],
                        'next_dt' => $next_fixed,
                        'mainhead' => $sel_from_heardt['mainhead'],
                        'subhead' => $sel_from_heardt['subhead'],
                        'mainhead_n' => $sel_from_heardt['mainhead_n'],
                        'subhead_n' => $sel_from_heardt['subhead_n'],
                        'clno' => $sel_from_heardt['clno'],
                        'brd_slno' => $sel_from_heardt['brd_slno'],
                        'roster_id' => $sel_from_heardt['roster_id'],
                        'board_type' => $sel_from_heardt['board_type'],
                        'main_supp_flag' => $sel_from_heardt['main_supp_flag'],
                        'listorder' => $sel_from_heardt['listorder'],
                        'sitting_judges' => $sel_from_heardt['sitting_judges'],
                        'usercode' => $sel_from_heardt['usercode'],
                        'coram' => $sel_from_heardt['coram'],
                        'is_nmd' => $sel_from_heardt['is_nmd'],
                        'no_of_time_deleted' => $sel_from_heardt['no_of_time_deleted']
                    ])->get()->getRow();

                if (empty($chk_in_l_h)) {
                    $insert_data = [
                        'diary_no' => $sel_from_heardt['diary_no'],
                        'conn_key' => $sel_from_heardt['conn_key'],
                        'next_dt' => $next_fixed,
                        'mainhead' => $sel_from_heardt['mainhead'],
                        'subhead' => $sel_from_heardt['subhead'],
                        'clno' => $sel_from_heardt['clno'],
                        'brd_slno' => $sel_from_heardt['brd_slno'],
                        'roster_id' => $sel_from_heardt['roster_id'],
                        'judges' => $sel_from_heardt['judges'],
                        'coram' => $sel_from_heardt['coram'],
                        'board_type' => $sel_from_heardt['board_type'],
                        'usercode' => $sel_from_heardt['usercode'],
                        'ent_dt' => $sel_from_heardt['ent_dt'],
                        'module_id' => $sel_from_heardt['module_id'],
                        'mainhead_n' => $sel_from_heardt['mainhead_n'],
                        'subhead_n' => $sel_from_heardt['subhead_n'],
                        'main_supp_flag' => $sel_from_heardt['main_supp_flag'],
                        'listorder' => $sel_from_heardt['listorder'],
                        'tentative_cl_dt' => $sel_from_heardt['tentative_cl_dt'],
                        'listed_ia' => $sel_from_heardt['listed_ia'],
                        'sitting_judges' => $sel_from_heardt['sitting_judges'],
                        'list_before_remark' => $sel_from_heardt['list_before_remark'],
                        'bench_flag' => '',
                        'lastorder' => '',
                        'coram_del_res' => $sel_from_heardt['coram_prev'],
                        'is_nmd' => $sel_from_heardt['is_nmd'],
                        'no_of_time_deleted' => $sel_from_heardt['no_of_time_deleted'],
                        'create_modify' => date("Y-m-d H:i:s"),
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];

                    $this->db->table('last_heardt')->insert($insert_data);
                }
            }



            $board_type_condition = "'" . $board_type . "'";
            $next_date = $sel_from_heardt['next_dt'];
            $update_main_case_query = "UPDATE heardt SET mainhead='M', next_dt='$next_date', listorder=32, board_type=$board_type_condition, usercode=9777, listed_ia='', ent_dt=NOW(), main_supp_flag=0, brd_slno=0, clno=0, roster_id='0', judges='0', module_id='2' WHERE diary_no=$conn_key";
            $this->db->query($update_main_case_query, [$next_date, $board_type_condition, $conn_key]);
        }
    }
    public function updateMainCase($conn_key, $update_main_case, $board_type, $mainhead_conn_main_case, $next_fixed)
    {
        if ($update_main_case == 1) {
            $sel_from_heardt = $this->db->query("SELECT * FROM heardt WHERE diary_no=?", [$conn_key])->getRowArray();


            if (!empty($sel_from_heardt)) {
                $chk_in_l_h = $this->db->table('last_heardt')
                    ->where([
                        'diary_no' => $sel_from_heardt['diary_no'],
                        'next_dt' => $next_fixed,
                        'mainhead' => $sel_from_heardt['mainhead'],
                        'subhead' => $sel_from_heardt['subhead'],
                        'mainhead_n' => $sel_from_heardt['mainhead_n'],
                        'subhead_n' => $sel_from_heardt['subhead_n'],
                        'clno' => $sel_from_heardt['clno'],
                        'brd_slno' => $sel_from_heardt['brd_slno'],
                        'roster_id' => $sel_from_heardt['roster_id'],
                        'board_type' => $sel_from_heardt['board_type'],
                        'main_supp_flag' => $sel_from_heardt['main_supp_flag'],
                        'listorder' => $sel_from_heardt['listorder'],
                        'sitting_judges' => $sel_from_heardt['sitting_judges'],
                        'usercode' => $sel_from_heardt['usercode'],
                        'coram' => $sel_from_heardt['coram'],
                        'is_nmd' => $sel_from_heardt['is_nmd'],
                        'no_of_time_deleted' => $sel_from_heardt['no_of_time_deleted']
                    ])->get()->getRow();

                if (empty($chk_in_l_h)) {
                    $insert_data = [
                        'diary_no' => $sel_from_heardt['diary_no'],
                        'conn_key' => $sel_from_heardt['conn_key'],
                        'next_dt' => $next_fixed,
                        'mainhead' => $sel_from_heardt['mainhead'],
                        'subhead' => $sel_from_heardt['subhead'],
                        'clno' => $sel_from_heardt['clno'],
                        'brd_slno' => $sel_from_heardt['brd_slno'],
                        'roster_id' => $sel_from_heardt['roster_id'],
                        'judges' => $sel_from_heardt['judges'],
                        'coram' => $sel_from_heardt['coram'],
                        'board_type' => $sel_from_heardt['board_type'],
                        'usercode' => $sel_from_heardt['usercode'],
                        'ent_dt' => $sel_from_heardt['ent_dt'],
                        'module_id' => $sel_from_heardt['module_id'],
                        'mainhead_n' => $sel_from_heardt['mainhead_n'],
                        'subhead_n' => $sel_from_heardt['subhead_n'],
                        'main_supp_flag' => $sel_from_heardt['main_supp_flag'],
                        'listorder' => $sel_from_heardt['listorder'],
                        'tentative_cl_dt' => $sel_from_heardt['tentative_cl_dt'],
                        'listed_ia' => $sel_from_heardt['listed_ia'],
                        'sitting_judges' => $sel_from_heardt['sitting_judges'],
                        'list_before_remark' => $sel_from_heardt['list_before_remark'],
                        'bench_flag' => '',
                        'lastorder' => '',
                        'coram_del_res' => $sel_from_heardt['coram_prev'],
                        'is_nmd' => $sel_from_heardt['is_nmd'],
                        'no_of_time_deleted' => $sel_from_heardt['no_of_time_deleted'],
                        'create_modify' => date("Y-m-d H:i:s"),
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    ];

                    $this->db->table('last_heardt')->insert($insert_data);
                }
            }
            $board_type_condition = "'" . $board_type . "'";
            $next_date = $sel_from_heardt['next_dt'];
            $update_main_case_query = "UPDATE heardt SET mainhead='M', next_dt='$next_date', listorder=32, board_type=$board_type_condition, usercode=9777, listed_ia='', ent_dt=NOW(), main_supp_flag=0, brd_slno=0, clno=0, roster_id='0', judges='0', module_id='2' WHERE diary_no=$conn_key";
            $this->db->query($update_main_case_query, [$next_date, $board_type_condition, $conn_key]);
        }
    }
    public function updateCoram($conn_key_disp, $diary_no)
    {
        $newCoram = '';
        $arrayCurRev = [9, 10, 25, 26];
        if ($newCoram == '0') {
            // Fetch judges for a specific case
            $judges = $this->db->query("
                SELECT diary_no, disp_dt, jud_id, jcode, is_retired 
                FROM dispose a
                LEFT JOIN judge j ON FIND_IN_SET(jcode, jud_id) AND is_retired='N' AND display='Y'
                WHERE diary_no='$conn_key_disp'
                ORDER BY judge_seniority
            ")->getResultArray();

            // Process fetched judges
            foreach ($judges as $row_judge) {
                if ($row_judge['is_retired'] == 'N') {
                    $newCoram .= ',' . $row_judge['jcode'];
                }
            }
        } else {
            // Fetch judges for a lower court case
            $lowerCaseTemp = $this->db->query("
                SELECT ct_code, l_state, lct_casetype, lct_caseno, lct_caseyear, l_dist, lct_dec_dt 
                FROM lowerct 
                WHERE diary_no='$diary_no' AND lw_display='Y' AND is_order_challenged='Y'
            ")->getRowArray();

            if (empty($lowerCaseTemp)) {
                $forDaTemp = $this->db->query("
                    SELECT a.diary_no, jud_id, is_order_challenged 
                    FROM lowerct a
                    JOIN dispose b ON a.diary_no=b.diary_no
                    WHERE ct_code='$lowerCaseTemp[ct_code]' AND l_state='$lowerCaseTemp[l_state]'
                    AND lct_casetype='$lowerCaseTemp[lct_casetype]' AND lct_caseno='$lowerCaseTemp[lct_caseno]'
                    AND lct_caseyear='$lowerCaseTemp[lct_caseyear]' AND l_dist='$lowerCaseTemp[l_dist]'
                    AND lct_dec_dt='$lowerCaseTemp[lct_dec_dt]' AND is_order_challenged='Y' AND a.diary_no!='$diary_no'
                    ORDER BY disp_dt DESC, jud_id
                ")->getRowArray();

                if (!empty($forDaTemp)) {
                    $getJudge = $this->db->query("
                        SELECT jcode, is_retired 
                        FROM judge 
                        WHERE FIND_IN_SET(jcode, '$forDaTemp[jud_id]') AND is_retired='N' AND display='Y' 
                        ORDER BY judge_seniority
                    ")->getResultArray();

                    // Process fetched judges
                    foreach ($getJudge as $row_judge) {
                        if ($row_judge['is_retired'] == 'N') {
                            $newCoram .= ',' . $row_judge['jcode'];
                        }
                    }
                }
            }
        }

        return $newCoram;
    }
    public function getSelFromHeardt($diary_no)
    {
        return $this->db->table('heardt')
            ->where('diary_no', $diary_no)
            ->get()
            ->getRowArray();
    }
    public function checkLastHeardt($selFromHeardt)
    {
        return $this->db->table('last_heardt')
            ->where('next_dt', $selFromHeardt['next_dt'])
            ->where('mainhead', $selFromHeardt['mainhead'])
            ->where('subhead', $selFromHeardt['subhead'])
            ->where('mainhead_n', $selFromHeardt['mainhead_n'])
            ->where('subhead_n', $selFromHeardt['subhead_n'])
            ->where('clno', $selFromHeardt['clno'])
            ->where('brd_slno', $selFromHeardt['brd_slno'])
            ->where('roster_id', $selFromHeardt['roster_id'])
            ->where('board_type', $selFromHeardt['board_type'])
            ->where('main_supp_flag', $selFromHeardt['main_supp_flag'])
            ->where('listorder', $selFromHeardt['listorder'])
            ->where('sitting_judges', $selFromHeardt['sitting_judges'])
            ->where('usercode', $selFromHeardt['usercode'])
            ->where('coram', $selFromHeardt['coram'])
            ->where('is_nmd', $selFromHeardt['is_nmd'])
            ->where('no_of_time_deleted', $selFromHeardt['no_of_time_deleted'])
            ->get()
            ->getRowArray();
    }

    public function insertLastHeardt($selFromHeardt)
    {
        return $this->db->table('last_heardt')
            ->insert([
                'diary_no' => $selFromHeardt['diary_no'],
                'conn_key' => $selFromHeardt['conn_key'],
                'next_dt' => $selFromHeardt['next_dt'],
                'mainhead' => $selFromHeardt['mainhead'],
                'subhead' => $selFromHeardt['subhead'],
                'clno' => $selFromHeardt['clno'],
                'brd_slno' => $selFromHeardt['brd_slno'],
                'roster_id' => $selFromHeardt['roster_id'],
                'judges' => $selFromHeardt['judges'],
                'coram' => $selFromHeardt['coram'],
                'board_type' => $selFromHeardt['board_type'],
                'usercode' => $selFromHeardt['usercode'],
                'ent_dt' => date('Y-m-d H:i:s'),
                'module_id' => $selFromHeardt['module_id'],
                'mainhead_n' => $selFromHeardt['mainhead_n'],
                'subhead_n' => $selFromHeardt['subhead_n'],
                'main_supp_flag' => $selFromHeardt['main_supp_flag'],
                'bench_flag' => isset($selFromHeardt['bench_flag']) ? $selFromHeardt['bench_flag'] : '',
                'lastorder' => isset($selFromHeardt['lastorder']) ? $selFromHeardt['lastorder'] : '',
                'listorder' => $selFromHeardt['listorder'],
                'tentative_cl_dt' => $selFromHeardt['tentative_cl_dt'],
                'listed_ia' => $selFromHeardt['listed_ia'],
                'sitting_judges' => $selFromHeardt['sitting_judges'],
                'list_before_remark' => $selFromHeardt['list_before_remark'],
                'coram_del_res' => $selFromHeardt['coram_prev'],
                'is_nmd' => $selFromHeardt['is_nmd'],
                'no_of_time_deleted' => $selFromHeardt['no_of_time_deleted'],
                'create_modify' => date("Y-m-d H:i:s"),
                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by' => session()->get('login')['usercode'],
                'updated_by_ip' => getClientIP(),
            ]);
    }
    public function insertheardt($data)
    {
        try {
            $this->db->table('heardt')->insert($data);
            return true;
        } catch (Exception $e) {
            log_message('error', 'Error inserting data into the database: ' . $e->getMessage());
            return false;
        }
    }
}
