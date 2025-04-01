<?php

namespace App\Models\ARDRBM;

use CodeIgniter\Model;

class Model_IA extends Model
{
    public function get_docdetails($diary_no, $doc_id = '', $is_archival_table = '')
    {

        $builder = $this->db->table("docdetails$is_archival_table");
        $builder->select("*,concat(docnum,'/',docyear) as ia");
        $builder->where('diary_no', $diary_no);
        $builder->where('display', 'Y');
        if (!empty($doc_id)) {
            $builder->whereIn('docd_id', $doc_id);
        }
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_ia_entry_date_correction_list($diary_no, $is_archival_table = '')
    {
        $builder = $this->db->table("docdetails$is_archival_table a");
        $builder->select('docd_id,a.diary_no,a.doccode,a.doccode1,docnum,docyear,filedby,a.ent_dt,other1,a.remark,party,no_of_copy,advocate_id,docdesc,c.name advname,u.name entryuser,forresp,a.docfee,feemode,iastat');
        $builder->join('master.docmaster b', 'a.doccode=b.doccode AND a.doccode1=b.doccode1', 'left');
        $builder->join('master.bar c', 'advocate_id=bar_id', 'left');
        $builder->join('master.users u', 'a.usercode=u.usercode', 'left');
        $builder->where('diary_no', $diary_no);
        $builder->where('a.display', 'Y');
        $builder->where('b.display', 'Y');
        $builder->where('iastat', 'P');
        // $builder->where("(ent_dt is null or ent_dt='1900-01-01 00:00:00' or ent_dt='0200-09-11 00:00:00' or ent_dt='1933-07-30 00:00:00')");
        $builder->orderBy('doccode', 'ASC');
        $builder->orderBy('ent_dt', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }
    public function get_heardt($diary_no)
    {
        $current_date = date('Y-m-d');
        $builder = $this->db->table("public.heardt");
        $builder->select("*");
        $builder->where("next_dt >=", $current_date);
        $builder->where('brd_slno > ', 0);
        $builder->where('roster_id > ', 0);
        $builder->where('diary_no', $diary_no);
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return  $query->getResultArray();
        } else {
            $builder2 = $this->db->table("public.heardt_a");
            $builder2->select("*");
            $builder2->where("next_dt >=", $current_date);
            $builder2->where('brd_slno > ', 0);
            $builder2->where('roster_id > ', 0);
            $builder2->where('diary_no', $diary_no);
            $query2 = $builder2->get();
            return $query2->getResultArray();
        }
    }
    public function get_ia_entry_date_correction_content($docd_id)
    {
        $builder = $this->db->table("docdetails a");
        $builder->select('docd_id,a.diary_no,kntgrp,a.doccode,a.doccode1,docnum,docyear,filedby,a.ent_dt,other1,a.remark,party,no_of_copy,advocate_id,docdesc,
                                c.name advname,u.name entryuser,forresp,a.docfee,feemode,iastat,aor_code');
        $builder->join('master.docmaster b', 'a.doccode=b.doccode AND a.doccode1=b.doccode1', 'left');
        $builder->join('master.bar c', 'advocate_id=bar_id', 'left');
        $builder->join('master.users u', 'a.usercode=u.usercode', 'left');
        $builder->where('docd_id', $docd_id);
        $query = $builder->get();
        $result = $query->getResultArray();
        if (empty($result)) {
            $builder2 = $this->db->table("docdetails_a a");
            $builder2->select('docd_id,a.diary_no,kntgrp,a.doccode,a.doccode1,docnum,docyear,filedby,a.ent_dt,other1,a.remark,party,no_of_copy,advocate_id,docdesc,
                                c.name advname,u.name entryuser,forresp,a.docfee,feemode,iastat,aor_code');
            $builder2->join('master.docmaster b', 'a.doccode=b.doccode AND a.doccode1=b.doccode1', 'left');
            $builder2->join('master.bar c', 'advocate_id=bar_id', 'left');
            $builder2->join('master.users u', 'a.usercode=u.usercode', 'left');
            $builder2->where('docd_id', $docd_id);
            $query2 = $builder2->get();
            $result = $query2->getResultArray();
        }
        return $result;
    }
    public function get_docmaster($docd_id = null)
    {
        $builder = $this->db->table('master.docmaster');
        $builder->select('*');
        $builder->where('doccode1', 0);
        $builder->where('display', 'Y');
        if (!empty($docd_id)) {
            $builder->where('docd_id', $docd_id);
        }
        $builder->orderBy('doccode', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

    /*start IA UP-DATION*/
    public function get_ia_updation_list($diary_no, $is_archival_table = '')
    {
        $builder = $this->db->table("docdetails$is_archival_table a");
        $builder->select('docd_id,a.diary_no,a.doccode,a.doccode1,docnum,docyear,filedby,a.ent_dt,other1,a.remark,party,no_of_copy,advocate_id,docdesc,c.name advname,u.name entryuser,forresp,a.docfee,feemode, iastat, is_efiled');
        $builder->join('master.docmaster b', 'a.doccode=b.doccode AND a.doccode1=b.doccode1', 'left');
        $builder->join('master.bar c', 'advocate_id=bar_id', 'left');
        $builder->join('master.users u', 'a.usercode=u.usercode', 'left');
        $builder->where('diary_no', $diary_no);
        $builder->where('a.display', 'Y');
        $builder->where('b.display', 'Y');
        $builder->where('iastat', 'P');
        $builder->orderBy('docnum', 'ASC');
        $builder->orderBy('docyear', 'ASC');
        //$builder->orderBy('ent_dt','ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_section_officer_count($diary_no, $session_user)
    {
        $query = "select count(*) as t from main m
                  left join master.users u on  m.dacode = u.usercode
                  where u.display = 'Y' and m.diary_no = $diary_no 
                  and u.section in( select	s.usec from master.users u join master.usertype t on u.usertype = t.id join master.user_sec_map s on  u.empid = s.empid
                  where  t.id in(9, 6, 4, 60) and u.display = 'Y' and t.display = 'Y' and u.usercode = $session_user and s.display = 'Y'
                  ) ";
        $query = $this->db->query($query);
        $result = $query->getResultArray();
        if (empty($result)) {
            $query2 = "select count(*) as t from main m
                  left join master.users u on  m.dacode = u.usercode
                  where u.display = 'Y' and m.diary_no = $diary_no 
                  and u.section in( select	s.usec from master.users u join master.usertype t on u.usertype = t.id join master.user_sec_map s on  u.empid = s.empid
                  where  t.id in(9, 6, 4, 60) and u.display = 'Y' and t.display = 'Y' and u.usercode = $session_user and s.display = 'Y'
                  ) ";
            $query2 = $this->db->query($query2);
            $result = $query2->getResultArray();
        }
        return $result;


        $builder = $this->db->table("main m");
        $builder->select('count(*) as t');
        $builder->join('master.users u', 'm.dacode=u.usercode', 'left');
        $builder->where('u.display', 'Y');
        $builder->where('m.diary_no', $diary_no);
        $builder->whereIn('u.section', "(select s.usec from  users u  join usertype t on u.usertype=t.id join user_sec_map s on u.empid=s.empid where  u.display='Y' and t.display='Y' and u.usercode='$session_user' and s.display='Y' )");
        $query = $builder->get();
        $result = $query->getResultArray();
        if (empty($result)) {
            $builder2 = $this->db->table("docdetails_a a");
            $builder2->select('count(*) as t');
            $builder2->join('master.users u', 'm.dacode=u.usercode', 'left');
            $builder2->where('u.display', 'Y');
            $builder2->where('m.diary_no', $diary_no);
            $builder2->whereIn('u.section', "(select s.usec from  users u  join usertype t on u.usertype=t.id join user_sec_map s on u.empid=s.empid where  u.display='Y' and t.display='Y' and u.usercode='$session_user' and s.display='Y' )");
            $query2 = $builder2->get();
            $result = $query2->getResultArray();
        }
        return $result;
    }
    public function get_diary_with_short_description($diary_no)
    {
        $builder = $this->db->table('main a');
        $builder->select("c_status,fil_no,fil_dt,fil_no_fh,fil_dt_fh,pet_name,res_name,pno,rno,
        (case when reg_year_mh =0 then TO_CHAR(a.fil_dt, 'YYYY')::INTEGER else reg_year_mh end) as m_year,
        (case when reg_year_fh =0 then TO_CHAR(a.fil_dt_fh, 'YYYY')::INTEGER else reg_year_fh end) as f_year,short_description,casename
        ");
        $builder->join('master.casetype b', 'b.casecode = a.casetype_id', 'left');
        $builder->where('diary_no', $diary_no);

        $query = $builder->get();
        return $query->getRowArray();
    }
    public function get_ia_updation_content($diary_no, $doccode, $doccode1, $docnum, $docyear, $is_archival_table = '')
    {
        $builder = $this->db->table("docdetails$is_archival_table a");
        $builder->select('docd_id,a.diary_no,kntgrp,a.doccode,a.doccode1,docnum,docyear,filedby,a.ent_dt,other1,a.remark,party,no_of_copy,advocate_id,docdesc,
                                c.name advname,u.name entryuser,forresp,a.docfee,feemode,iastat,aor_code,is_efiled');
        $builder->join('master.docmaster b', ' a.doccode=b.doccode AND a.doccode1=b.doccode1', 'left');
        $builder->join('master.bar c', 'advocate_id=aor_code', 'left');
        $builder->join('master.users u', 'a.usercode=u.usercode', 'left');
        $builder->where('diary_no', $diary_no);
        $builder->where('a.display', 'Y');
        $builder->where('b.display', 'Y');
        $builder->where('iastat', 'P');
        $builder->where('a.doccode', $doccode);
        $builder->where('a.doccode1', $doccode1);
        $builder->where('a.docnum', $docnum);
        $builder->where('a.docyear', $docyear);
        $query = $builder->get();
        return $query->getRowArray();
    }
    public function get_ia_docdetails($diary_no, $doccode, $doccode1, $docnum, $docyear, $is_archival_table = '')
    {

        $builder = $this->db->table("docdetails$is_archival_table");
        $builder->select("*");
        $builder->where('diary_no', $diary_no);
        $builder->where('display', 'Y');
        $builder->where('iastat', 'P');
        $builder->where('doccode', $doccode);
        $builder->where('doccode1', $doccode1);
        $builder->where('docnum', $docnum);
        $builder->where('docyear', $docyear);
        $query = $builder->get();
        return $query->getRowArray();
    }
    public function get_heardt_by_ent_dt($diary_no, $ent_dt)
    {
        $builder = $this->db->table("public.heardt");
        $builder->select("*");
        $builder->where('diary_no', $diary_no);
        $builder->whereIn('module_id', [14, 15]);
        $builder->where("TO_CHAR(ent_dt,'YYYY-MM-DD h:i')", $ent_dt);
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return  $query->getResultArray();
        } else {
            $builder2 = $this->db->table("public.heardt_a");
            $builder2->select("*");
            $builder2->where('diary_no', $diary_no);
            $builder2->whereIn('module_id', [14, 15]);
            $builder2->where("TO_CHAR(ent_dt,'YYYY-MM-DD h:i')", $ent_dt);
            $query2 = $builder2->get();
            return $query2->getResultArray();
        }
    }
    public function get_last_heardt_by_ent_dt($diary_no, $ent_dt)
    {
        $builder = $this->db->table("public.last_heardt");
        $builder->select("*");
        $builder->where('diary_no', $diary_no);
        $builder->whereIn('module_id', [14, 15]);
        $builder->where("TO_CHAR(ent_dt,'YYYY-MM-DD h:i')", $ent_dt);
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return  $query->getResultArray();
        } else {
            $builder2 = $this->db->table("public.last_heardt_a");
            $builder2->select("*");
            $builder2->where('diary_no', $diary_no);
            $builder2->whereIn('module_id', [14, 15]);
            $builder2->where("TO_CHAR(ent_dt,'YYYY-MM-DD h:i')", $ent_dt);
            $query2 = $builder2->get();
            return $query2->getResultArray();
        }
    }
    function get_adv_name_aor($aorcode)
    {
        $builder = $this->db->table("master.bar");
        $builder->select("name,mobile,email,bar_id,DATE_PART('year',enroll_date) as enroll_year");
        $builder->WHERE('aor_code', $aorcode);
        $builder->WHERE('isdead !=', 'Y');
        $builder->WHERE('if_aor', 'Y');
        $builder->orderBy('name', 'ASC');
        $query = $builder->get(1);
        if ($query->getNumRows() >= 1) {
            return $query->getRowArray();
        } else {
            return '0';
        }
    }
    function getDoc_type($doctype, $q)
    {
        $json = array();
        $builder = $this->db->table("master.docmaster");
        $builder->distinct();
        $builder->select("doccode1,docdesc");
        $builder->where('doccode', 8);
        $builder->whereNotIn('doccode1', [0, 19]);
        //$builder->where('doctype',$doctype);
        //$builder->orWhere('doctype',0);
        $builder->where('doctype', true);
        $builder->orWhere('display', 'Y');
        $builder->like('docdesc', $q, '', '!', true);
        $builder->orderBy('docdesc', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $docmaster = $query->getResultArray();
            foreach ($docmaster as $row) {
                $json[] = array('value' => $row['doccode1'], 'label' => $row['docdesc']);
            }
            return $json;
        } else {
            return $json;
        }
    }
    public function is_ia_updation_content($diary_no, $doccode, $doccode1, $docnum, $docyear, $is_archival_table = '')
    {
        $builder = $this->db->table("docdetails$is_archival_table d");
        $builder->select('docd_id,d.diary_no,d.doccode,d.doccode1,docnum,docyear,d.remark,docdesc');
        $builder->join('master.docmaster m', ' d.doccode=m.doccode and d.doccode1=m.doccode1');
        $builder->where('diary_no', $diary_no);
        $builder->where('d.doccode', $doccode);
        $builder->where('d.doccode1', $doccode1);
        $builder->where('d.docnum', $docnum);
        $builder->where('d.docyear', $docyear);
        $builder->where('d.display', 'Y');
        $builder->where('m.display', 'Y');
        $query = $builder->get();
        return $query->getRowArray();
    }
    public function get_brdrem($diary_no, $is_archival_table = '')
    {
        $builder = $this->db->table("brdrem$is_archival_table");
        $builder->select('*');
        $builder->where('diary_no', $diary_no);
        $query = $builder->get();
        return $query->getRowArray();
    }





    public function getDiaryData($diaryNo)
    {
        $builder = $this->db->table('main')
            ->select("diary_no, CONCAT(pet_name, ' Vs. ', res_name) as cause_title, reg_no_display,c_status,active_fil_no,fil_no")
            ->where('diary_no', $diaryNo);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function searchDiary($ctype, $cno, $cyr)
    {
        $builder = $this->db->table('main')
            ->select("SUBSTR(diary_no, 1, LENGTH(diary_no) - 4) as dn, SUBSTR(diary_no, -4) as dy")
            ->where("SUBSTRING_INDEX(fil_no, '-', 1)", $ctype)
            ->where("CAST($cno AS UNSIGNED) BETWEEN SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no, '-', 2), '-', -1) AND SUBSTRING_INDEX(fil_no, '-', -1)")
            ->groupStart()
            ->where("reg_year_mh", 0)
            ->orWhere("DATE(fil_dt) >", '2017-05-10')
            ->orWhere("YEAR(fil_dt)", $cyr)
            ->groupEnd();
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function updateRegistrationNumber($diaryNo, $regno)
    {
        $update =  $this->db->table('main')
            ->where('diary_no', $diaryNo)
            ->update(['reg_no_display' => $regno]);
        return $update;
    }

    public function getSearchDiary($ctype, $cno, $cyr)
    {
        $builder = $this->db->table('main_casetype_history');

        // Create the necessary subqueries
        $subqueryNewReg = $builder->select([
            'dn' => 'SUBSTR(h.diary_no, 1, LENGTH(h.diary_no) - 4)',
            'dy' => 'SUBSTR(h.diary_no, -4)',
            'ct1' => "IF(h.new_registration_number != '', SUBSTRING_INDEX(h.new_registration_number, '-', 1), '')",
            'crf1' => "IF(h.new_registration_number != '', SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2), '-', -1), '')",
            'crl1' => "IF(h.new_registration_number != '', SUBSTRING_INDEX(h.new_registration_number, '-', -1), '')"
        ])
            ->where('is_deleted', 'f')
            ->groupStart()
            ->groupStart()
            ->where('SUBSTRING_INDEX(h.new_registration_number, "-", 1)', $ctype)
            ->where("CAST($cno AS UNSIGNED) BETWEEN SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2), '-', -1) AND SUBSTRING_INDEX(h.new_registration_number, '-', -1)")
            ->where('h.new_registration_year', $cyr)
            ->groupEnd()
            ->orGroupStart()
            ->where('SUBSTRING_INDEX(h.old_registration_number, "-", 1)', $ctype)
            ->where("CAST($cno AS UNSIGNED) BETWEEN SUBSTRING_INDEX(SUBSTRING_INDEX(h.old_registration_number, '-', 2), '-', -1) AND SUBSTRING_INDEX(h.old_registration_number, '-', -1)")
            ->where('h.old_registration_year', $cyr)
            ->where('h.is_deleted', 't')
            ->groupEnd()
            ->groupEnd();

        // Get the results
        $query = $subqueryNewReg->get();
        return $query->getRowArray();
    }

    public function searchDiary2($ctype, $cno, $cyr)
    {
        $builder = $this->db->table('main');
        $builder->select("SUBSTR(diary_no, 1, LENGTH(diary_no) - 4) AS dn, 
                          SUBSTR(diary_no, -4) AS dy");
        $builder->where("SUBSTRING_INDEX(fil_no, '-', 1)", $ctype);
        $builder->where("CAST($cno AS UNSIGNED) BETWEEN SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no, '-', 2), '-', -1) 
                          AND SUBSTRING_INDEX(fil_no, '-', -1)");
        $builder->groupStart()
            ->where("reg_year_mh", 0)
            ->orWhere("DATE(fil_dt) >", '2017-05-10')
            ->groupEnd();
        $builder->where("YEAR(fil_dt)", $cyr);

        $query = $builder->get();
        return $query->getRowArray();
    }

    public function getSearchDiary2($ctype, $cno, $cyr)
    {
        $builder = $this->db->table('main_casetype_history h');
        $builder->select("SUBSTR(h.diary_no, 1, LENGTH(h.diary_no) - 4) AS dn, 
                          SUBSTR(h.diary_no, -4) AS dy,
                          IF(h.new_registration_number != '', SUBSTRING_INDEX(h.new_registration_number, '-', 1), '') AS ct1, 
                          IF(h.new_registration_number != '', SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2), '-', -1), '') AS crf1, 
                          IF(h.new_registration_number != '', SUBSTRING_INDEX(h.new_registration_number, '-', -1), '') AS crl1");
        $builder->groupStart()
            ->where("SUBSTRING_INDEX(h.new_registration_number, '-', 1)", $ctype)
            ->where("CAST($cno AS UNSIGNED) BETWEEN SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2), '-', -1) 
                        AND SUBSTRING_INDEX(h.new_registration_number, '-', -1)")
            ->where("h.new_registration_year", $cyr)
            ->groupEnd()
            ->orGroupStart()
            ->where("SUBSTRING_INDEX(h.old_registration_number, '-', 1)", $ctype)
            ->where("CAST($cno AS UNSIGNED) BETWEEN SUBSTRING_INDEX(SUBSTRING_INDEX(h.old_registration_number, '-', 2), '-', -1) 
                        AND SUBSTRING_INDEX(h.old_registration_number, '-', -1)")
            ->where("h.old_registration_year", $cyr)
            ->where("h.is_deleted", 't')
            ->groupEnd()
            ->where("h.is_deleted", 'f');

        $query = $builder->get();
        return $query->getRowArray();
    }

    public function getSearchLowerct($diary_no)
    {
        $builder = $this->db->table('lowerct l');
        $builder->select("lower_court_id, CONCAT(type_sname, ' ', lct_caseno, '/', lct_caseyear, ' order dated: ', TO_CHAR(lct_dec_dt, 'DD-MM-YYYY')) AS caseno")
            ->join('master.lc_hc_casetype ct', 'l.lct_casetype = ct.lccasecode', 'left')
            ->where('lw_display', 'Y')
            ->where('is_order_challenged', 'Y')
            ->where('ct.display', 'Y')
            ->where('diary_no', $diary_no);

        $query = $builder->get();
        return $query->getResultArray();
    }





    public function updateHistory($dno, $newRegNo, $oldRegNo)
    {
        return $this->db->table('main_casetype_history')
            ->set(['new_registration_number' => $newRegNo])
            ->where('diary_no', $dno)
            ->where('is_deleted', 'f')
            ->where('new_registration_number', $oldRegNo)
            ->update();
    }

    public function updateCasetypeHistory($dno, $filNoNew, $filNo)
    {
        return $this->db->table('main_casetype_history')
            ->set(['old_registration_number' => $filNoNew])
            ->where('diary_no', $dno)
            ->where('is_deleted', 'f')
            ->where('old_registration_number', $filNo)
            ->update();
    }

    public function insertLowerCtHistory($data)
    {
        return $this->db->table('lowerct_history')->insert($data);
    }

    public function updateLowerCt($lowerCtIds)
    {
        return $this->db->table('lowerct')
            ->set(['is_order_challenged' => 'N', 'full_interim_flag' => 'N'])
            ->whereIn('lower_court_id', $lowerCtIds)
            ->update();
    }

    public function updateMain($condition, $dno, $reg_no_display_old, $reg_no_display_new)
    {
        return $this->db->table('main')
            ->set([$condition, 'reg_no_display' => "REPLACE(reg_no_display, '{$reg_no_display_old}', '{$reg_no_display_new}')"])
            ->where('diary_no', $dno)
            ->update();
    }

    public function updateCasetypeHistory2($dno, $active_fil_no_new, $active_fil_no)
    {
        return $this->db->table('main_casetype_history')
            ->set(['new_registration_number' => $active_fil_no_new])
            ->where('diary_no', $dno)
            ->where('is_deleted', 'f')
            ->where('new_registration_number', $active_fil_no)
            ->update();
    }

    public function getBfNbf($tfil_no)
    {
        $builder = $this->db->table('not_before a');
        $builder->select('a.diary_no, STRING_AGG(b.jname, \', \' ORDER BY b.jname) AS jn, a.notbef');
        //$builder->join('master.judge b', 'b.jcode IN (a.j1, a.j2, a.j3, a.j4, a.j5)', 'inner');
        $builder->join('master.judge b', 'b.jcode IN (a.j1)', 'inner');
        $builder->where('a.diary_no', $tfil_no);
        $builder->groupBy('a.diary_no, a.notbef');

        $query = $builder->get();

        $bf = '';
        $nbf = '';

        if ($query->getNumRows() > 0) {
            foreach ($query->getResultArray() as $rownb) {
                $t_jn = $rownb['jn'];
                $t_jn1 = stripslashes($t_jn); // Ensure this input is sanitized properly

                if ($rownb['notbef'] == "B") {
                    $bf .= ($bf ? ", " : "") . $t_jn1;
                } elseif ($rownb['notbef'] == "N") {
                    $nbf .= ($nbf ? ", " : "") . $t_jn1;
                }
            }
        }

        return $bf . "^|^" . $nbf;
    }

    public function get_next_working_date($dt)
    {
        $cdate = strtotime($dt);

        while (true) {
            // Format the date for database query
            $t_dtt1 = date('Y-m-d', $cdate);
            $builder = $this->db->table('holidays');
            $builder->where('hdate', $t_dtt1);
            $holidayCount = $builder->countAllResults();

            // Check if the current day is a weekend or a holiday
            $weekday = date('l', $cdate);
            if ($weekday === 'Saturday' || $weekday === 'Sunday' || $holidayCount > 0) {
                // Move to the next day
                $cdate = strtotime('+1 day', $cdate);
            } else {
                // If it's a working day, return the date
                return date('d-m-Y', $cdate);
            }
        }
    }

    public function getDiaryInfo($ct, $cn, $cy)
    {
        $builder = $this->db->table('main');
        $builder->select("SUBSTR(diary_no, 1, LENGTH(diary_no) - 4) AS dn, 
                              SUBSTR(diary_no, -4) AS dy");
        $builder->where("SUBSTRING_INDEX(fil_no, '-', 1) =", $ct);
        $builder->where("CAST($cn AS UNSIGNED) BETWEEN 
                             (SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no, '-', 2), '-', -1)) 
                             AND (SUBSTRING_INDEX(fil_no, '-', -1))");

        $builder->where("
                IF((reg_year_mh = 0 OR DATE(fil_dt) > DATE('2017-05-10')), 
                YEAR(fil_dt) = $cy, 
                reg_year_mh = $cy) 
                OR (SUBSTRING_INDEX(fil_no_fh, '-', 1) = $ct 
                AND CAST($cn AS UNSIGNED) BETWEEN 
                (SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no_fh, '-', 2), '-', -1)) 
                AND (SUBSTRING_INDEX(fil_no_fh, '-', -1)) 
                AND IF(reg_year_fh = 0, YEAR(fil_dt_fh) = $cy, reg_year_fh = $cy)
            ");
        // echo $builder->getCompiledSelect();die;
        $result = $builder->get()->getRowArray();
        return $result;
    }

    public function getDiaryInfo_builder($ct, $cn, $cy)
    {
        $builder = $this->db->table('main')
                ->select([
                    'DN' => 'SUBSTRING(DIARY_NO::TEXT FROM 1 FOR LENGTH(DIARY_NO::TEXT) - 4) as dn',
                    'DY' => 'RIGHT(DIARY_NO::TEXT, 4) as dy'
                ])
                ->where("CASE WHEN SPLIT_PART(FIL_NO, '-', $ct) ~ '^[0-9]+$' 
                        THEN SPLIT_PART(FIL_NO, '-', $ct)::INTEGER 
                        ELSE NULL END = 1")
                ->where("$cn BETWEEN COALESCE(
                        CASE WHEN SPLIT_PART(FIL_NO, '-', 2) ~ '^[0-9]+$' 
                            THEN SPLIT_PART(FIL_NO, '-', 2)::INTEGER 
                            ELSE NULL END, 0
                    ) AND COALESCE(
                        CASE WHEN SPLIT_PART(FIL_NO, '-', -1) ~ '^[0-9]+$' 
                            THEN SPLIT_PART(FIL_NO, '-', -1)::INTEGER 
                            ELSE NULL END, 0
                    )")
                ->groupStart()
                    ->groupStart()
                        ->where('reg_year_mh', 0)
                        ->orWhere('fil_dt >', '2017-05-10')
                    ->groupEnd()
                    ->where('EXTRACT(YEAR FROM fil_dt)', $cy)
                ->groupEnd()
                ->orGroupStart()
                    ->where("CASE WHEN SPLIT_PART(fil_no_fh, '-', 1) ~ '^[0-9]+$' 
                            THEN SPLIT_PART(fil_no_fh, '-', 1)::INTEGER 
                            ELSE NULL END = 1")
                    ->where("$cn BETWEEN COALESCE(
                            CASE WHEN SPLIT_PART(fil_no_fh, '-', 2) ~ '^[0-9]+$' 
                                THEN SPLIT_PART(fil_no_fh, '-', 2)::INTEGER 
                                ELSE NULL END, 0
                        ) AND COALESCE(
                            CASE WHEN SPLIT_PART(fil_no_fh, '-', -1) ~ '^[0-9]+$' 
                                THEN SPLIT_PART(fil_no_fh, '-', -1)::INTEGER 
                                ELSE NULL END, 0
                        )")
                    ->groupStart()
                        ->groupStart()
                            ->where('reg_year_fh', 0)
                            ->where('EXTRACT(YEAR FROM fil_dt_fh)', $cy)
                        ->groupEnd()
                        ->orWhere('reg_year_fh', $cy)
                    ->groupEnd()
                ->groupEnd();
            
            //echo $builder->getCompiledSelect();die;
            $result = $builder->get()->getRowArray();
            


        return $result;
    }

    public function getDiaryInfo2($ct, $cn, $cy)
    {
        $builder = $this->db->table('main_casetype_history h');
        $builder->select("SUBSTR(h.diary_no, 1, LENGTH(h.diary_no) - 4) AS dn,
                                  SUBSTR(h.diary_no, -4) AS dy,
                                  IF(h.new_registration_number != '', SUBSTRING_INDEX(h.new_registration_number, '-', 1), '') AS ct1,
                                  IF(h.new_registration_number != '', SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2), '-', -1), '') AS crf1,
                                  IF(h.new_registration_number != '', SUBSTRING_INDEX(h.new_registration_number, '-', -1), '') AS crl1");

        $builder->where("(
                    (SUBSTRING_INDEX(h.new_registration_number, '-', 1) = $ct AND 
                    CAST($cn AS UNSIGNED) BETWEEN 
                    (SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2), '-', -1)) 
                    AND (SUBSTRING_INDEX(h.new_registration_number, '-', -1)) 
                    AND h.new_registration_year = $cy) 
                    OR 
                    (SUBSTRING_INDEX(h.old_registration_number, '-', 1) = $ct AND 
                    CAST($cn AS UNSIGNED) BETWEEN 
                    (SUBSTRING_INDEX(SUBSTRING_INDEX(h.old_registration_number, '-', 2), '-', -1)) 
                    AND (SUBSTRING_INDEX(h.old_registration_number, '-', -1)) 
                    AND h.old_registration_year = $cy)
                ) AND h.is_deleted = 'f'");

        $result = $builder->get()->getRowArray();
        return $result;
    }

    public function caseTypeResult($ct)
    {
        $caseTypeBuilder = $this->db->table('master.casetype');
        $caseTypeBuilder->select('short_description');
        $caseTypeBuilder->where('casecode', $ct);
        $caseTypeBuilder->where('display', 'Y');
        $caseTypeResult = $caseTypeBuilder->get()->getRowArray();
        return $caseTypeResult;
    }

    public function getCaseDetails($diaryno)
    {
        $builder = $this->db->table('main m');
        $builder->select([
            'm.case_grp',
            'm.diary_no',
            'm.pet_name',
            'm.res_name',
            'm.pet_adv_id AS pet_adv',
            'm.res_adv_id AS res_adv',
            'm.c_status',
            'm.lastorder',
            "CASE 
            WHEN (m.conn_key != '' AND m.conn_key IS NOT NULL) THEN 
                CASE 
                    WHEN m.conn_key = CAST(m.diary_no AS varchar) THEN 'N' 
                    ELSE 'Y' 
                END 
            ELSE 'NA' 
        END AS ccdet",
            'm.conn_key AS connto',
            'm.fil_no',
            'm.fil_no_fh',
            'm.fil_dt',
            "TO_CHAR(m.fil_dt, 'DD-MM-YYYY HH12:MI AM') as fil_dt_f",
            "TO_CHAR(m.fil_dt_fh, 'DD-MM-YYYY HH12:MI AM') as fil_dt_fh",
            "CASE 
            WHEN (m.fil_no IS NOT NULL AND m.fil_no != '') 
            THEN (string_to_array(m.fil_no, '-'))[1] 
            ELSE '' 
        END as ct1",
            "CASE 
            WHEN m.fil_no != '' 
            THEN (string_to_array(m.fil_no, '-'))[2] 
            ELSE '' 
        END as crf1",
            "CASE 
            WHEN m.fil_no != '' 
            THEN (string_to_array(m.fil_no, '-'))[array_length(string_to_array(m.fil_no, '-'), 1)] 
            ELSE '' 
        END as crl1",
            "CASE 
            WHEN m.fil_no_fh != '' 
            THEN (string_to_array(m.fil_no_fh, '-'))[1] 
            ELSE '' 
        END as ct2",
            "CASE 
            WHEN m.fil_no_fh != '' 
            THEN (string_to_array(m.fil_no_fh, '-'))[2] 
            ELSE '' 
        END as crf2",
            "CASE 
            WHEN m.fil_no_fh != '' 
            THEN (string_to_array(m.fil_no_fh, '-'))[array_length(string_to_array(m.fil_no_fh, '-'), 1)] 
            ELSE '' 
        END as crl2"
        ]);

        // Ensure type matching
        if (is_numeric($diaryno)) {
            $builder->where('m.diary_no', (int)$diaryno); // assuming diary_no is bigint
        } else {
            $builder->where('m.diary_no', (string)$diaryno); // assuming diary_no is varchar
        }        
        return $builder->get()->getRowArray();
    }


    public function getOrderDetails($diaryno)
    {
        $builder = $this->db->table('heardt h');

        $builder->select([
            'h.diary_no',
            'TO_CHAR(h.next_dt, \'DD-MM-YYYY\') AS next_dt',
            'STRING_AGG(crm.r_head::text, \', \') AS Disp_Remarks'
        ]);

        $builder->join('case_remarks_multiple crm', 'crm.diary_no = h.diary_no AND crm.cl_date = h.next_dt', 'inner');
        $builder->join('master.case_remarks_head crh', 'crh.sno = crm.r_head AND (crh.display = \'Y\' OR crh.display IS NULL)', 'inner');

        // Other conditions remain unchanged
        $builder->where('h.diary_no', $diaryno);
        $builder->where('h.clno !=', 0);
        $builder->where('h.brd_slno !=', 0);
        $builder->where('h.brd_slno IS NOT NULL');
        $builder->where('h.roster_id !=', 0);
        $builder->where('h.roster_id IS NOT NULL');
        $builder->where('main_supp_flag IN (1, 2)');
        $builder->where('h.next_dt <= CURRENT_DATE');
        $builder->where('h.next_dt = (SELECT MAX(next_dt) FROM heardt b WHERE b.diary_no = h.diary_no AND b.clno != 0 AND b.brd_slno != 0 AND b.brd_slno IS NOT NULL AND b.roster_id != 0 AND b.roster_id IS NOT NULL AND main_supp_flag IN (1, 2))');
        $builder->where('crm.r_head IN (181, 182, 3, 183, 184, 1, 41, 176, 177, 178, 27, 196, 200, 201)');
        $builder->groupBy(['h.next_dt', 'h.diary_no']);

        $query = $builder->get();

        return $query->getRowArray();
    }



    public function getOrderDetails2($diaryno)
    {
        $builder = $this->db->table('last_heardt h');

        $builder->select([
            'h.diary_no',
            'TO_CHAR(h.next_dt, \'DD-MM-YYYY\') AS next_dt',
            'STRING_AGG(crm.r_head::text, \', \') AS Disp_Remarks'
        ]);
        $builder->join('case_remarks_multiple crm', 'crm.diary_no = h.diary_no AND crm.cl_date = h.next_dt', 'inner');
        $builder->join('master.case_remarks_head crh', 'crh.sno = crm.r_head AND (crh.display = \'Y\' OR crh.display IS NULL)', 'inner');

        $builder->where('h.diary_no', $diaryno);
        $builder->where('h.clno !=', 0);
        $builder->where('h.brd_slno !=', 0);
        $builder->where('h.brd_slno IS NOT NULL');
        $builder->where('h.roster_id !=', 0);
        $builder->where('h.roster_id IS NOT NULL');
        $builder->where('h.bench_flag IS NULL OR h.bench_flag = \'\'');
        $builder->where('main_supp_flag IN (1, 2)');
        $builder->where('h.next_dt <= CURRENT_DATE');

        // Subquery for max next_dt
        $subquery = "(SELECT MAX(next_dt) FROM last_heardt b WHERE b.diary_no = h.diary_no AND b.clno != 0 AND b.brd_slno != 0 AND b.brd_slno IS NOT NULL AND b.roster_id != 0 AND b.roster_id IS NOT NULL AND (b.bench_flag IS NULL OR b.bench_flag = '') AND main_supp_flag IN (1, 2) limit 2)";
        $builder->where("h.next_dt = {$subquery}");

        $builder->where('crm.r_head IN (181, 182, 3, 183, 184, 1, 41, 176, 177, 178, 27, 196, 200, 201)');
        $builder->groupBy(['h.next_dt', 'h.diary_no']);        
        $builder->limit(2);
        //$builder->get();
        //echo $this->db->getLastquery();die;
        return $builder->get()->getRowArray();
    }

    public function getConnectionDetails($diaryno)
    {
        // Ensure diary_no is treated as an integer
        $diaryno = (int)$diaryno;

        $builder = $this->db->table('main m');
        $builder->select([
            'm.active_casetype_id',
            'm.case_grp',
            'm.diary_no',
            'm.pet_name',
            'm.res_name',
            'm.c_status',
            "CASE 
                WHEN (CAST(m.diary_no AS TEXT) = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL) THEN 'M' 
                ELSE 'C' 
            END AS ct",
            "CASE 
                WHEN reg_year_mh = 0 THEN EXTRACT(YEAR FROM m.fil_dt) 
                ELSE reg_year_mh 
            END AS m_year",
            "CASE 
                WHEN reg_year_fh = 0 THEN EXTRACT(YEAR FROM m.fil_dt_fh) 
                ELSE reg_year_fh 
            END AS f_year",
            'm.fil_no',
            'm.fil_no_fh',
            "TO_CHAR(m.fil_dt, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_f",
            "TO_CHAR(m.fil_dt_fh, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_fh",
            "CASE 
                WHEN m.fil_no != '' THEN (STRING_TO_ARRAY(m.fil_no, '-'))[1] 
                ELSE '' 
            END AS ct1",
            "CASE 
                WHEN m.fil_no != '' THEN (STRING_TO_ARRAY(m.fil_no, '-'))[2] 
                ELSE '' 
            END AS crf1",
            "CASE 
                WHEN m.fil_no != '' THEN (STRING_TO_ARRAY(m.fil_no, '-'))[array_length(STRING_TO_ARRAY(m.fil_no, '-'), 1)] 
                ELSE '' 
            END AS crl1",
            "CASE 
                WHEN m.fil_no_fh != '' THEN (STRING_TO_ARRAY(m.fil_no_fh, '-'))[1] 
                ELSE '' 
            END AS ct2",
            "CASE 
                WHEN m.fil_no_fh != '' THEN (STRING_TO_ARRAY(m.fil_no_fh, '-'))[2] 
                ELSE '' 
            END AS crf2",
            "CASE 
                WHEN m.fil_no_fh != '' THEN (STRING_TO_ARRAY(m.fil_no_fh, '-'))[array_length(STRING_TO_ARRAY(m.fil_no_fh, '-'), 1)] 
                ELSE '' 
            END AS crl2",
            'm.casetype_id AS ctid'
        ]);

        // Use parameter binding to avoid type issues
        $builder->where('m.diary_no', $diaryno);
        return $builder->get()->getResultArray();
    }



    public function getLowerCourtCount($diary_no)
    {
        $builder = $this->db->table('lowerct');
        $builder->select('COUNT(*) AS cnt');
        $builder->where('diary_no', $diary_no);
        $builder->where('lw_display', 'Y');
        $builder->where('is_order_challenged', 'Y');
        return $builder->get()->getRowArray()['cnt'] ?? 0;
    }

    public function getCaseType($caseCode)
    {
        return $this->db->table('master.casetype')
            ->select('skey')
            ->where(['casecode' => $caseCode, 'display' => 'Y'])
            ->get()
            ->getRowArray();
    }

    public function getParties($diaryno)
    {
        return $this->db->table('party')
            ->select("STRING_AGG(partyname, ', ' ORDER BY sr_no) AS pn, pet_res")
            ->where('diary_no', $diaryno)
            ->where('sr_no >', 1)
            ->groupBy('pet_res')
            ->get()
            ->getResultArray();
    }


    public function getAdvocates($diaryno)
    {
        return $this->db->table('advocate')
            ->select('pet_res_no, adv, advocate_id, pet_res')
            ->where(['diary_no' => $diaryno, 'display' => 'Y'])
            ->orderBy('pet_res')
            ->get()
            ->getResultArray();
    }

    public function navigate_diary($dno)
    {
        $builder = $this->db->table('main m');
        $builder->select('m.diary_no, c1.short_description, m.active_reg_year, m.active_fil_no, 
                          m.pet_name, m.res_name, m.pno, m.rno, m.diary_no_rec_date, 
                          m.active_fil_dt, m.lastorder, m.c_status');
        $builder->join('master.casetype c1', 'm.active_casetype_id = c1.casecode', 'left');
        $builder->where('m.diary_no', $dno);

        $query = $builder->get();
        $result = $query->getRowArray();

        if ($result) {
            $filno_array = explode("-", $result['active_fil_no']);
            if (empty($filno_array[0])) {
                $fil_no_print = "Unreg.";
            } else {
                $fil_no_print = $result['short_description'] . "/" . ltrim($filno_array[1], '0');
                if (!empty($filno_array[2]) && $filno_array[1] != $filno_array[2]) {
                    $fil_no_print .= "-" . ltrim($filno_array[2], '0');
                }
                $fil_no_print .= "/" . $result['active_reg_year'];
            }

            $cstatus = ($result['c_status'] == "P") ? "Pending" : "Disposed";
            $diary_no_rec_date = !empty($result['diary_no_rec_date']) ? date('d-m-Y H:i:s', strtotime($result['diary_no_rec_date'])) : "";
            $active_fil_dt = !empty($result['active_fil_dt']) ? date('d-m-Y H:i:s', strtotime($result['active_fil_dt'])) : "";
            // Set session data
            session()->set([
                'session_c_status' => $cstatus,
                'session_pet_name' => $result['pet_name'],
                'session_res_name' => $result['res_name'],
                'session_lastorder' => $result['lastorder'],
                'session_diary_recv_dt' => $diary_no_rec_date,
                'session_active_fil_dt' => $active_fil_dt,
                'session_diary_no' => substr($dno, 0, -4),
                'session_diary_yr' => substr($dno, -4),
                'session_active_reg_no' => $fil_no_print
            ]);
        } else {
            return redirect()->to('/some/error/page');
        }
    }

    public function ifInFinalList($diaryNo)
    {
        $builder = $this->db->table('heardt h');
        $subQuery = $this->db->table('advance_allocated aa')
            ->select('aa.next_dt, ad.*')
            ->join('advanced_drop_note ad', 'aa.diary_no = ad.diary_no AND aa.next_dt = ad.cl_date', 'left')
            ->join('heardt h', 'h.diary_no = aa.diary_no AND h.next_dt = aa.next_dt', 'left')
            ->where('h.diary_no IS NOT NULL')
            ->where('aa.diary_no', $diaryNo)
            ->where('DATE(aa.next_dt) >= CURRENT_DATE')
            ->groupBy('aa.conn_key');

        $builder->select('h.next_dt')
            ->join("({$subQuery->getQuery()}) b", 'h.next_dt = b.next_dt')
            ->whereIn('main_supp_flag', [1, 2])
            ->where('h.diary_no', $diaryNo);

        $query = $builder->get();
        return $query->getNumRows() > 0;
    }

    public function ifInAdvanceList($diaryNo)
    {
        $builder = $this->db->table('advance_allocated aa');
        $subQuery = $this->db->table('advance_allocated aa')
            ->select('aa.next_dt, ad.*')
            ->join('advanced_drop_note ad', 'aa.diary_no = ad.diary_no AND aa.next_dt = ad.cl_date', 'left')
            ->join('heardt h', 'h.diary_no = aa.diary_no AND h.next_dt = aa.next_dt', 'left')
            ->where('h.diary_no IS NOT NULL')
            ->where('aa.diary_no', $diaryNo)
            ->where('DATE(aa.next_dt) >= CURRENT_DATE')
            ->groupBy('aa.conn_key');

        // Main query
        $query = $this->db->table("({$subQuery->getQuery()}) a")
            ->select('next_dt')
            ->where('a.cl_date IS NULL')
            ->get();

        return $query->getNumRows() > 0;
    }

    public function ifInAdvanceListSingleJudge($diaryNo)
    {
        $subQuery = $this->db->table('advance_allocated aa')
            ->select('aa.next_dt, ad.*')
            ->join('advanced_drop_note ad', 'aa.diary_no = ad.diary_no AND aa.next_dt = ad.cl_date', 'left')
            ->join('heardt h', 'h.diary_no = aa.diary_no AND h.next_dt = aa.next_dt', 'left')
            ->where('h.diary_no IS NOT NULL')
            ->where('h.board_type', 'S')
            ->where('aa.diary_no', $diaryNo)
            ->where('DATE(aa.next_dt) >= CURRENT_DATE')
            ->groupBy('aa.conn_key');

        // Main query
        $query = $this->db->table("({$subQuery->getQuery()}) a")
            ->select('next_dt')
            ->where('a.cl_date IS NULL')
            ->get();

        return $query->getNumRows() > 0;
    }

    public function ifInFinalListSingleJudge($diaryNo)
    {
        $subQuery = $this->db->table('advance_allocated aa')
            ->select('aa.next_dt, ad.*')
            ->join('advanced_drop_note ad', 'aa.diary_no = ad.diary_no AND aa.next_dt = ad.cl_date', 'left')
            ->join('heardt h', 'h.diary_no = aa.diary_no AND h.next_dt = aa.next_dt', 'left')
            ->where('h.diary_no IS NOT NULL')
            ->where('h.board_type', 'S')
            ->where('aa.diary_no', $diaryNo)
            ->where('DATE(aa.next_dt) >= CURRENT_DATE')
            ->groupBy('aa.conn_key');

        // Main query
        $builder = $this->db->table('heardt h')
            ->select('h.next_dt')
            ->join("({$subQuery->getQuery()}) a", 'h.next_dt = a.next_dt')
            ->whereIn('main_supp_flag', [1, 2])
            ->where('h.diary_no', $diaryNo);

        $query = $builder->get();

        return $query->getNumRows() > 0;
    }

    public function resultsListedAfter($diaryNo)
    {
        $builder = $this->db->table('heardt a');
        $builder->select("TO_CHAR(a.next_dt, 'DD-MM-YYYY') as next_dt, roster_id AS judgename1")
            ->where('a.diary_no', $diaryNo)
            ->where('a.next_dt >= CURRENT_DATE')
            ->whereIn('a.main_supp_flag', [1, 2]);
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getResultsOc($diaryNo)
    {   
        
        $subQuery = $this->db->table('main')
            ->select('conn_key')
            ->where('diary_no', $diaryNo);
        $subQuery = $subQuery->getCompiledSelect();

        $builder = $this->db->table('main m');
        //$builder->select('m.diary_no, (SELECT CONCAT(`list`,\'-\',conn_type) FROM conct cc WHERE cc.diary_no = m.diary_no ORDER BY `list` DESC LIMIT 1) AS llist')
        $builder->select('m.diary_no, (SELECT cc.list || \'-\' || cc.conn_type FROM conct cc WHERE cc.diary_no = m.diary_no ORDER BY cc.list DESC LIMIT 1) AS llist')
            ->where('m.diary_no', $diaryNo)
            ->orWhere('m.conn_key IN (' . $subQuery . ')')
            ->where('m.diary_no != CAST(m.conn_key AS bigint)')
            ->orderBy('m.fil_dt');
            
        $results_oc = $builder->get();
        return $results_oc->getResultArray();
    }

    public function getSqlHear($diaryNo)
    {
        $sql_hear = $this->db->table('heardt a')
            ->select('
                a.*, b.*,
                (SELECT stagename FROM subheading WHERE stagecode=a.subhead) AS stage1,
                (SELECT stagename FROM stage WHERE stagecode=a.purpose) AS purpose1,
                (SELECT GROUP_CONCAT(jname) FROM master.judge WHERE FIND_IN_SET(jcode, b.jcodes) > 0) AS judgename,
                (SELECT GROUP_CONCAT(jname) FROM master.judge WHERE FIND_IN_SET(jcode, a.judges) > 0) AS judgename1,
                (CASE
                    WHEN b.jcodes IS NULL THEN 0
                    WHEN (LENGTH(b.jcodes) - LENGTH(REPLACE(b.jcodes, ",", ""))) = 0 THEN ""
                    WHEN (LENGTH(b.jcodes) - LENGTH(REPLACE(b.jcodes, ",", ""))) = 1 THEN "SB"
                    WHEN (LENGTH(b.jcodes) - LENGTH(REPLACE(b.jcodes, ",", ""))) = 2 THEN "DB"
                    ELSE "FB"
                END) AS bench,
                a.lp
            ')
            ->join('(
                SELECT zz.diary_no, zz.cl_date, zz.jcodes, zz.status,
                GROUP_CONCAT(CONCAT("<b><u>",zz.cat1,"</u></b> : ",zz.hc) ORDER BY IF(zz.cat_head_id=0 OR zz.cat_head_id IS NULL, 10000, zz.cat_head_id) SEPARATOR "<br>") AS fhc,
                GROUP_CONCAT(zz.caseval SEPARATOR "") AS cval, zz.r_head, zz.head_content, zz.daysl
                FROM (
                    SELECT c.diary_no, c.r_head,
                    IF((c.r_head IN (24, 21, 59, 70), DATE_FORMAT(STR_TO_DATE(c.head_content, "%d/%m/%Y"), "%d-%m-%Y"), c.head_content) AS head_content,
                    h.side,
                    IF(h.cat_head_id != 0, (SELECT category FROM category_head WHERE cisid=h.cat_head_id), "") AS cat1,
                    GROUP_CONCAT(h.head, IF(c.head_content != "", CONCAT(" [",c.head_content,"]"), "") ORDER BY h.head SEPARATOR "<br>") AS hc,
                    h.cat_head_id, c.cl_date, c.jcodes, c.status,
                    GROUP_CONCAT(CONCAT(c.r_head, "|", c.head_content, "^^") SEPARATOR "") AS caseval,
                    DATE_FORMAT(DATE_ADD(c.cl_date, INTERVAL h.compliance_limit_in_day DAY), "%d-%m-%Y") AS daysl
                    FROM case_remarks_multiple c
                    JOIN case_remarks_head h ON c.r_head = h.sno
                    WHERE c.diary_no = "' . $diaryNo . '" AND c.remove = 0
                    GROUP BY c.cl_date, cat1
                ) zz
                GROUP BY zz.cl_date
            ) b', 'b.diary_no = a.diary_no AND a.next_dt = b.cl_date')
            ->where('a.diary_no', $diaryNo)
            ->where('a.next_dt !=', '0000-00-00')
            ->orderBy('a.tbl, a.next_dt DESC, a.ent_dt DESC');

        $results_s = $sql_hear->get();
        return $results_s->getResultArray();
    }

    public function get_advocates($adv_id, $wen = '')
    {
        $t_adv = "";

        // Check if adv_id is not zero
        if ($adv_id != 0) {

            // Build the query
            $builder = $this->db->table('master.bar');
            $builder->select('name, enroll_no, EXTRACT(YEAR FROM enroll_date) AS eyear, isdead');
            $builder->whereIn('bar_id', explode(',', $adv_id));

            $query = $builder->get();

            // Check if there are results
            if ($query->getNumRows() > 0) {
                foreach ($query->getResultArray() as $row) {
                    $t_adv = $row['name'];
                    if ($row['isdead'] === 'Y') {
                        $t_adv = "<font color='red'>" . $t_adv . " (Dead / Retired / Elevated) </font>";
                    }
                    if ($wen === 'wen') {
                        $t_adv .= " [" . $row['enroll_no'] . "/" . $row['eyear'] . "]";
                    }
                }
            }
        }

        return $t_adv;
    }

    public function get_mul_category($dn)
    {
        $mul_category = "";
        $id = null; // Initialize $id

        if ($dn != "") {
            // Use Query Builder to construct the query
            $builder = $this->db->table('mul_category a');
            $builder->select('b.*');
            $builder->join('master.submaster b', 'a.submaster_id = b.id');
            $builder->where('a.diary_no', $dn);
            $builder->where('a.display', 'Y');

            // Execute the query
            $query = $builder->get();

            // Check if there are results
            if ($query->getNumRows() > 0) {
                $category_nm = '';
                foreach ($query->getResultArray() as $row2) {
                    // Determine the category name based on the subcodes
                    if ($row2['subcode1'] > 0 && $row2['subcode2'] == 0 && $row2['subcode3'] == 0 && $row2['subcode4'] == 0) {
                        $category_nm = $row2['sub_name1'];
                    } elseif ($row2['subcode1'] > 0 && $row2['subcode2'] > 0 && $row2['subcode3'] == 0 && $row2['subcode4'] == 0) {
                        $category_nm = $row2['sub_name1'] . " : " . $row2['sub_name4'];
                    } elseif ($row2['subcode1'] > 0 && $row2['subcode2'] > 0 && $row2['subcode3'] > 0 && $row2['subcode4'] == 0) {
                        $category_nm = $row2['sub_name1'] . " : " . $row2['sub_name2'] . " : " . $row2['sub_name4'];
                    } elseif ($row2['subcode1'] > 0 && $row2['subcode2'] > 0 && $row2['subcode3'] > 0 && $row2['subcode4'] > 0) {
                        $category_nm = $row2['sub_name1'] . " : " . $row2['sub_name2'] . " : " . $row2['sub_name3'] . " : " . $row2['sub_name4'];
                    }

                    // Build the final output
                    if ($mul_category == '') {
                        $mul_category = $category_nm;
                    } else {
                        $mul_category .= ',<br> ' . $category_nm;
                    }
                    $id = $row2['id']; // Update the ID
                }
            }
        }

        return [$mul_category, $id]; // Return as an array
    }

    public function get_casetype_q($diaryno)
    {
        $builder = $this->db->table('main');
        $builder->select('active_casetype_id');
        $builder->where('diary_no', $diaryno);
        $query = $builder->get();

        return $query->getRowArray();
    }

    public function get_ct_rs($conversion_type)
    {
        $builder = $this->db->table('master.casetype');
        $builder->select('casecode, skey, casename, short_description');
        $builder->where('casecode', $conversion_type);
        $builder->where('display', 'Y');
        $builder->where('casecode !=', 9999);
        $builder->where('cs_m_f', 'F');
        $builder->orderBy('short_description');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_res_p_r($dairy_no)
    {
        $builder = $this->db->table('main');
        $query = $builder->select('pet_name, res_name, casetype_id, fil_no, c_status')
            ->where('diary_no', $dairy_no)
            ->get();
        return $query->getRowArray();
    }

    public function getCategoryCount($dairy_no)
    {
        $builder = $this->db->table('mul_category');
        $query = $builder->select('*')
            ->where('diary_no', $dairy_no)
            ->where('display', 'Y')
            ->get();
        return $query->getNumRows();
    }

    public function checkDefects($dairy_no)
    {
        $builder = $this->db->table('obj_save');
        $builder->where('diary_no', $dairy_no)
            ->where('display', 'Y')
            ->where('rm_dt', null);
        // echo $builder->getCompiledSelect();die();
        $query = $builder->get();         
        return $query->getRowArray();
    }

    public function getPendingIADetails($dairy_no)
    {
        $builder = $this->db->table('docdetails a');
        $builder->select('a.docnum, a.docyear, b.docdesc, a.other1');
        $builder->join('master.docmaster b', 'a.doccode = b.doccode AND a.doccode1 = b.doccode1');
        $builder->where('a.diary_no', $dairy_no);
        $builder->where('a.iastat', 'P');
        $builder->where('a.display', 'Y');
        $builder->where('b.display', 'Y');
        $builder->where('b.not_reg_if_pen', 1);
        // echo $builder->getCompiledSelect();die;
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_causetitle($diaryno)
    {
        $cause_title = '';
        $builder = $this->db->table('main');
        $builder->select('pet_name, res_name, pno, rno');
        $builder->where('diary_no', $diaryno);

        // Execute the query
        $query = $builder->get();

        // Check if there are results
        if ($query->getNumRows() > 0) {
            $cause_title_arr = $query->getRowArray();

            // Construct the cause title
            $cause_title .= $cause_title_arr['pet_name'];

            if ($cause_title_arr['pno'] == 2) {
                $cause_title .= "<font color='blue'> AND ANR </font>";
            } elseif ($cause_title_arr['pno'] > 2) {
                $cause_title .= "<font color='blue'> AND ORS </font>";
            }

            $cause_title .= "<font color='blue'> VS </font>" . $cause_title_arr['res_name'];

            if ($cause_title_arr['rno'] == 2) {
                $cause_title .= "<font color='blue'> AND ANR </font>";
            } elseif ($cause_title_arr['rno'] > 2) {
                $cause_title .= "<font color='blue'> AND ORS </font>";
            }
        }

        return $cause_title;
    }

    public function getLowerCourtDetails($dairy_no, $res_p_r)
    {
        $transfer_state = '';

        // Check the casetype_id
        if ($res_p_r['casetype_id'] == '7' || $res_p_r['casetype_id'] == '8') {
            $transfer_state = ", transfer_case_type, transfer_case_no, transfer_case_year, transfer_state, transfer_district, transfer_court";
        }

        // Build the query
        $builder = $this->db->table('lowerct a');
        $builder->select("lct_dec_dt, l_dist, ct_code, l_state, name,
            CASE    
                WHEN ct_code = 3 THEN (
                    SELECT Name
                    FROM master.state s
                    WHERE s.id_no = a.l_dist
                    AND display = 'Y'
                )
                ELSE (
                    SELECT agency_name
                    FROM master.ref_agency_code c
                    WHERE c.cmis_state_id = a.l_state
                    AND c.id = a.l_dist
                    AND is_deleted = 'f'
                )
            END AS agency_name,
            lct_casetype, lct_caseno, lct_caseyear,
            CASE
                WHEN ct_code = 4 THEN (
                    SELECT skey
                    FROM master.casetype ct
                    WHERE ct.display = 'Y'
                    AND ct.casecode = a.lct_casetype
                )
                ELSE (
                    SELECT type_sname
                    FROM master.lc_hc_casetype d
                    WHERE d.lccasecode = a.lct_casetype
                    AND d.display = 'Y'
                )
            END AS type_sname, a.lower_court_id, is_order_challenged $transfer_state");
        $builder->join('master.state b', 'a.l_state = b.id_no AND b.display = \'Y\'', 'left');
        $builder->join('main e', 'e.diary_no = a.diary_no');

        // Conditionally add the transfer petition join
        if ($res_p_r['casetype_id'] == '7' || $res_p_r['casetype_id'] == '8') {
            $builder->join('transfer_to_details ttd', 'ttd.lowerct_id = a.lower_court_id AND ttd.display = \'Y\'');
        }

        $builder->where('a.diary_no', $dairy_no);
        $builder->where('lw_display', 'Y');
        $builder->where('c_status', 'P');
        $builder->where('is_order_challenged', 'Y');

        $builder->orderBy('a.lower_court_id');
        //echo $builder->getCompiledSelect();die;
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getCourtName($transfer_court_id)
    {
        return $this->db->table('master.m_from_court')
            ->select('court_name')
            ->where('id', $transfer_court_id)
            ->where('display', 'Y')
            ->get()
            ->getRowArray();
    }

    public function getStateName($transfer_state)
    {
        return $this->db->table('master.state')
            ->select('Name')
            ->where('id_no', $transfer_state)
            ->where('display', 'Y')
            ->get()
            ->getRowArray();
    }

    public function getDistrictName($transfer_court, $transfer_district)
    {
        if ($transfer_court == '3') {
            // Query for state name
            return $this->db->table('state')
                ->select('Name')
                ->where('id_no', $transfer_district)
                ->where('display', 'Y')
                ->get()
                ->getRowArray();
        } else {
            // Query for agency name
            return $this->db->table('ref_agency_code')
                ->select('agency_name')
                ->where('id', $transfer_district)
                ->where('is_deleted', 'f')
                ->get()
                ->getRowArray();
        }
    }

    public function get_case_type($transfer_court, $transfer_case_type)
    {
        if ($transfer_court == '4') {
            return $this->db->table('casetype')
                ->select('skey')
                ->where('display', 'Y')
                ->where('casecode', $transfer_case_type)
                ->get()
                ->getRowArray();
        } else {
            return $this->db->table('lc_hc_casetype')
                ->select('type_sname AS skey')
                ->where('lccasecode', $transfer_case_type)
                ->where('display', 'Y')
                ->get()
                ->getRowArray();
        }
    }

    public function checkIA($diary_no, $casetype_id)
    {
        $not_reg_if_pen = '';

        if ($casetype_id == '1' || $casetype_id == '2') {
            $not_reg_if_pen = " (not_reg_if_pen = 1 OR not_reg_if_pen = 2)";
        } else {
            $not_reg_if_pen = " (not_reg_if_pen = 1)";
        }
        return $this->db->table('docdetails a')
            ->select('docnum, docyear, docdesc, other1')
            ->join('master.docmaster b', 'a.doccode = b.doccode AND a.doccode1 = b.doccode1')
            ->where('diary_no', $diary_no)
            ->where('iastat', 'P')
            ->where('a.display', 'Y')
            ->where('b.display', 'Y')
            ->where($not_reg_if_pen)
            ->get()
            ->getResultArray();
    }

    /*end IA UP-DATION*/
}
