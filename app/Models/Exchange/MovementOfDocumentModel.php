<?php

namespace App\Models\Exchange;

use CodeIgniter\Model;

class MovementOfDocumentModel extends Model
{
    protected $eservicesdb;

    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
        $this->eservicesdb = \Config\Database::connect('eservices');
    }

    public function getDiaryNumber($ct, $cn, $cy)
    {

        // Prepare and execute the first query
        $builder = $this->db->table('main');
        $builder->select("SUBSTR(diary_no, 1, LENGTH(diary_no) - 4) AS dn, SUBSTR(diary_no, -4) AS dy");
        $builder->where("SUBSTRING_INDEX(fil_no, '-', 1)", $ct);
        $builder->where("CAST($cn AS UNSIGNED) BETWEEN SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no, '-', 2), '-', -1) AND SUBSTRING_INDEX(fil_no, '-', -1)");
        $builder->groupStart();
        $builder->where("IF((reg_year_mh = 0 OR DATE(fil_dt) > DATE('2017-05-10')), YEAR(fil_dt) = $cy, reg_year_mh = $cy)");
        $builder->groupEnd();

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        }

        // Prepare and execute the second query
        $builder = $this->db->table('main_casetype_history h');
        $builder->select("SUBSTR(h.diary_no, 1, LENGTH(h.diary_no) - 4) AS dn, SUBSTR(h.diary_no, -4) AS dy,
            IF(h.new_registration_number != '', SUBSTRING_INDEX(h.new_registration_number, '-', 1), '') AS ct1,
            IF(h.new_registration_number != '', SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2), '-', -1), '') AS crf1,
            IF(h.new_registration_number != '', SUBSTRING_INDEX(h.new_registration_number, '-', -1), '') AS crl1");

        $builder->groupStart();
        $builder->where("SUBSTRING_INDEX(h.new_registration_number, '-', 1)", $ct);
        $builder->where("CAST($cn AS UNSIGNED) BETWEEN SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2), '-', -1) AND SUBSTRING_INDEX(h.new_registration_number, '-', -1)");
        $builder->where("h.new_registration_year", $cy);
        $builder->groupEnd();

        $builder->orGroupStart();
        $builder->where("SUBSTRING_INDEX(h.old_registration_number, '-', 1)", $ct);
        $builder->where("CAST($cn AS UNSIGNED) BETWEEN SUBSTRING_INDEX(SUBSTRING_INDEX(h.old_registration_number, '-', 2), '-', -1) AND SUBSTRING_INDEX(h.old_registration_number, '-', -1)");
        $builder->where("h.old_registration_year", $cy);
        $builder->groupEnd();

        $builder->where("h.is_deleted", 'f');

        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getRowArray() : null;
    }

    public function getCaseTypeDescription($ct)
    {
        return $this->db->table('master.casetype')
            ->select('short_description')
            ->where('casecode', $ct)
            ->where('display', 'Y')
            ->get()
            ->getRowArray();
    }

    public function getRecentDocuments($ucode)
    {

        $sevenDaysAgo = date('Y-m-d', strtotime('-7 days'));
        $builder = $this->db->table('docdetails a');
        $builder->select('a.*, c.*, m.*');
        $builder->join('master.docmaster c', 'a.doccode = c.doccode AND a.doccode1 = c.doccode1', 'left');
        $builder->join('main m', 'a.diary_no = m.diary_no', 'left');

        $builder->where('a.ent_dt >', $sevenDaysAgo);
        $builder->where('a.diary_no !=', 0);
        $builder->where('a.display', 'Y');
        $builder->where('a.usercode', $ucode);

        $builder->where('a.diary_no NOT IN (SELECT diary_no FROM ld_move WHERE diary_no > 0 AND diary_no = a.diary_no AND doccode = a.doccode AND doccode1 = a.doccode1 AND docnum = a.docnum AND docyear = a.docyear)', null);

        $builder->orderBy('m.dacode');

        return $builder->get()->getResultArray();
    }

    public function getDiaryDetails($d_no, $d_yr)
    {


        $builder = $this->db->table('main')
            ->select("
                diary_no, conn_key, fil_dt, 
                EXTRACT(YEAR FROM fil_dt) AS filyr, 
                TO_CHAR(fil_dt, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_f, 
                fil_no_fh, 
                TO_CHAR(fil_dt_fh, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_fh, 
                actcode, 
                pet_adv_id, 
                res_adv_id, 
                lastorder, 
                c_status, 
                CASE WHEN diary_no IS NOT NULL AND diary_no != '' THEN split_part(diary_no, '-', 1) ELSE '' END AS ct1, 
                CASE WHEN diary_no IS NOT NULL AND diary_no != '' THEN split_part(diary_no, '-', 2) ELSE '' END AS crf1, 
                CASE WHEN diary_no IS NOT NULL AND diary_no != '' THEN split_part(diary_no, '-', 3) ELSE '' END AS crl1, 
                CASE WHEN fil_no_fh IS NOT NULL AND fil_no_fh != '' THEN split_part(fil_no_fh, '-', 1) ELSE '' END AS ct2, 
                CASE WHEN fil_no_fh IS NOT NULL AND fil_no_fh != '' THEN split_part(fil_no_fh, '-', 2) ELSE '' END AS crf2, 
                CASE WHEN fil_no_fh IS NOT NULL AND fil_no_fh != '' THEN split_part(fil_no_fh, '-', 3) ELSE '' END AS crl2, 
                CASE 
                    WHEN conn_key IS NOT NULL AND conn_key != '' THEN 
                        CASE WHEN conn_key = diary_no THEN 'N' ELSE 'Y' END
                    ELSE 'N' 
                END AS ccdet, 
                casetype_id, 
                conn_key AS connto
            ")
            ->where("SUBSTRING(diary_no FROM 1 FOR LENGTH(diary_no) - 4) =", $d_no)
            ->where("SUBSTRING(diary_no FROM LENGTH(diary_no) - 3 FOR 4) =", $d_yr);

        // Get the SQL query as a string (useful for debugging)
        $sqlQuery = $builder->getCompiledSelect();
        echo "<pre>$sqlQuery</pre>"; // Print SQL query for debugging
        die;

        // Execute the query
        $query = $builder->get();
        return $query->getRowArray();
    }






    public function get_fil_date_for($diaryno)
    {
        $builder = $this->db->table('main a');
        $builder->select("fil_dt, 
            IF(last_dt IS NULL, '', DATE_FORMAT(last_dt, '%d-%m-%Y %h:%i %p')) as last_dt, 
            a.usercode, 
            IF(last_usercode IS NULL, '', last_usercode) as last_usercode, 
            b.name AS user, 
            c.name AS last_u");
        $builder->join('users b', 'a.usercode = b.usercode', 'left');
        $builder->join('users c', 'a.last_usercode = c.usercode', 'left');
        $builder->where('diary_no', $diaryno);

        return $builder->get()->getResultArray();
    }

    public function getUserDetailsByDiaryNo($diaryno)
    {
        $builder = $this->db->table('main a');
        $builder->select("a.usercode, b.name, us.section_name");
        $builder->join('users b', 'a.usercode = b.usercode', 'left');
        $builder->join('usersection us', 'b.section = us.id', 'left');
        $builder->where('diary_no', $diaryno);

        return $builder->get()->getRowArray();
    }

    public function getShortDescription($casecode)
    {
        return $this->db->table('master.casetype')
            ->select('short_description')
            ->where('casecode', $casecode)
            ->where('display', 'Y')
            ->get()
            ->getRowArray();
    }

    public function get_diary_details($diaryNo)
    {

        $builder = $this->db->table('party p');
        $builder->select("SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) AS case_no, 
                          SUBSTR(m.diary_no, -4) AS year, 
                          p.sr_no, 
                          p.pet_res, 
                          p.ind_dep, 
                          p.partyname, 
                          p.sonof, 
                          p.prfhname, 
                          p.age, 
                          p.sex, 
                          p.caste, 
                          p.addr1, 
                          p.addr2, 
                          p.pin, 
                          p.state, 
                          p.city, 
                          p.email, 
                          p.contact AS mobile, 
                          p.deptcode, 
                          (SELECT deptname FROM deptt WHERE deptcode = p.deptcode) AS deptname, 
                          c.skey, 
                          DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y %h:%i %p') AS diary_no_rec_date");

        $builder->join('main m', 'm.diary_no = p.diary_no AND sr_no = 1 AND pflag = "P" AND pet_res IN ("P", "R")', 'INNER');
        $builder->join('casetype c', 'c.casecode = SUBSTRING(m.diary_no, 3, 3', 'LEFT');
        $builder->where('m.diary_no', $diaryNo);
        $builder->orderBy('p.pet_res, p.sr_no');

        return $builder->get()->getResultArray();
    }

    public function getDistrictName($stateCode, $cityCode)
    {
        return $this->db->table('master.state')
            ->select('Name')
            ->where('State_code', $stateCode)
            ->where('District_code', $cityCode)
            ->where('Sub_Dist_code', 0)
            ->where('Village_code', 0)
            ->where('display', 'Y')
            ->get()
            ->getRowArray();
    }

    public function getLowerCourtDetails($diaryNo)
    {
        return $this->db->table('lowerct a')
            ->select('a.lct_dec_dt, a.lct_caseno, a.lct_caseyear, ct.short_description AS type_sname')
            ->join('casetype ct', 'ct.casecode = a.lct_casetype AND ct.display = "Y"', 'left')
            ->where('a.diary_no', $diaryNo)
            ->where('a.lw_display', 'Y')
            ->where('ct.ct_code', 4)
            ->orderBy('a.lct_dec_dt')
            ->get()
            ->getResultArray();
    }

    public function getActSections($diaryNo)
    {
        return $this->db->table('act_main a')
            ->select('a.act, GROUP_CONCAT(b.section) AS section, act_name')
            ->join('act_section b', 'a.id = b.act_id', 'left')
            ->join('act_master c', 'c.id = a.act', 'inner')
            ->where('a.display', 'Y')
            ->where('b.display', 'Y')
            ->where('c.display', 'Y')
            ->where('diary_no', $diaryNo)
            ->groupBy('a.act')
            ->get()
            ->getResultArray();
    }

    public function getLawById($actCode)
    {
        return $this->db->table('master.caselaw')
            ->select('law')
            ->where('id', $actCode)
            ->get()
            ->getRowArray();
    }

    public function getDistinctFilNo($diaryNo)
    {
        return $this->db->table('rgo_default')
            ->distinct()
            ->select('fil_no2')
            ->where('fil_no', $diaryNo)
            ->where('remove_def', 'N')
            ->get()
            ->getResultArray();
    }

    public function getTentativeClDt($diaryNo)
    {
        return $this->db->table('heardt')
            ->select('tentative_cl_dt')
            ->where('diary_no', $diaryNo)
            ->get()
            ->getRowArray();
    }

    public function getDisplayFlag()
    {

        return $this->db->table('master.case_status_flag')
            ->select('display_flag, always_allowed_users')
            ->where('date(to_date)', '0000-00-00')
            ->where('flag_name', 'tentative_listing_date')
            ->get()
            ->getRowArray();
    }

    public function getConnectedCases($diaryNo)
    {
        return $this->db->table('main m')
            ->select('m.diary_no, (SELECT `list` FROM conct cc WHERE cc.diary_no = m.diary_no LIMIT 1) AS llist')
            ->where('m.diary_no', $diaryNo)
            ->orWhere('m.conn_key IN (SELECT conn_key FROM main WHERE diary_no = ?)', $diaryNo)
            ->where('m.diary_no != m.conn_key')
            ->orderBy('m.fil_dt')
            ->get()
            ->getResultArray();
    }

    public function getUserSection($ucode)
    {

        return $this->db->table('master.users')
            ->where('usercode', $ucode)
            ->where('display', 'Y')
            ->get()
            ->getResultArray();
    }

    public function getAllDAUsercodes($officerSection)
    {
        $result = $this->db->table('master.users')
            ->select("STRING_AGG(usercode::TEXT, ',') AS allDA")
            ->where('section', $officerSection)
            ->where('display', 'Y')
            ->whereIn('usertype', [51, 17, 50, 14, 9])
            ->groupBy('section')
            ->get()
            ->getRowArray();

        return $result ?? [];
    }


    public function get_select_rs($condition)
    {

        // $select_q = "SELECT date(m.diary_no_rec_date) AS diary_no_rec_date, m.casetype_id, a.diary_no, a.doccode, 
        // a.doccode1, kntgrp, b.docnum, b.docyear, docdesc, other1, m.diary_no, 
        // disp_to, disp_dt, disp_by, 
        // TO_CHAR(CAST(h.next_dt AS DATE), 'DD-MM-YYYY') AS next_dt, 
        // main_supp_flag 
        // FROM ld_move a
        // INNER JOIN docdetails b ON (a.diary_no = b.diary_no AND a.diary_no > 0 
        // AND b.diary_no > 0 AND a.doccode = b.doccode 
        // AND a.doccode1 = b.doccode1 AND a.docnum = b.docnum 
        // AND a.docyear = b.docyear AND b.display = 'Y' 
        // " . $condition . " 
        //     AND a.rece_by = 0)
        // INNER JOIN main m ON a.diary_no = m.diary_no 
        // LEFT JOIN master.docmaster c ON (a.doccode = c.doccode AND a.doccode1 = c.doccode1) 
        // LEFT JOIN heardt h ON m.diary_no = h.diary_no 
        // WHERE b.iastat = 'P' 
        // ORDER BY disp_dt DESC";


        //New php code 
        $select_q = "SELECT date(m.diary_no_rec_date) diary_no_rec_date,m.casetype_id,a.diary_no,a.doccode,a.doccode1,kntgrp,
        b.docnum,b.docyear,docdesc,other1,m.diary_no,disp_to,disp_dt,disp_by,TO_CHAR(CAST(h.next_dt AS DATE), 'DD-MM-YYYY') AS next_dt,main_supp_flag 
        FROM ld_move a
        INNER JOIN docdetails b ON (a.diary_no=b.diary_no 
        and a.diary_no>0 
        and b.diary_no >0 
        and a.doccode=b.doccode 
        AND a.doccode1=b.doccode1 
        and a.docnum=b.docnum 
        and a.docyear=b.docyear 
        and b.display='Y' 
        and a.rece_by=0)
        INNER JOIN main m ON a.diary_no=m.diary_no " . $condition . " 
        LEFT JOIN master.docmaster c ON (a.doccode=c.doccode AND a.doccode1=c.doccode1) 
        LEFT JOIN heardt h ON m.diary_no=h.diary_no where b.iastat='P' 
        Order By disp_dt desc";
        return $this->db->query($select_q)->getResultArray();
    }

    public function updateRecords($data, $ucode)
    {

        foreach ($data as $value) {
            $new_value = explode('-', $value);

            $exists = $this->db->table('ld_move')
                ->where('diary_no', $new_value[0])
                ->where('doccode', $new_value[1])
                ->where('doccode1', $new_value[2])
                ->where('docnum', $new_value[3])
                ->where('docyear', $new_value[4])
                ->where('disp_by', $new_value[5])
                ->countAllResults();

            if ($exists == 0) {
                continue;
            }

            $this->db->table('ld_move')
                ->set([
                    'rece_by' => $ucode,
                    'rece_dt' => date('Y-m-d H:i:s')
                ])
                ->where('diary_no', $new_value[0])
                ->where('doccode', $new_value[1])
                ->where('doccode1', $new_value[2])
                ->where('docnum', $new_value[3])
                ->where('docyear', $new_value[4])
                ->where('disp_by', $new_value[5])
                //->where('disp_to', $ucode)
                ->update();
        }
    }










































    //........created by Deepak........//
    public function verify_defect()
    {
        $usercode = session()->get('login')['usercode'];

        $query = $this->db->table('ld_move a')
            ->select('*')
            ->join(
                'docdetails b',
                'a.diary_no = b.diary_no 
            AND a.diary_no > 0 
            AND b.diary_no > 0 
            AND a.doccode = b.doccode 
            AND a.doccode1 = b.doccode1 
            AND a.docnum = b.docnum 
            AND a.docyear = b.docyear 
            AND b.display = \'Y\' 
            AND a.rece_by = ' . $this->db->escape($usercode) . ' 
            AND b.verified = \'\'',
                'inner'
            )
            ->join('main m', 'a.diary_no = m.diary_no', 'inner')
            ->join('master.docmaster c', 'a.doccode = c.doccode AND a.doccode1 = c.doccode1', 'left')
            ->where('b.iastat', 'P')
            ->orderBy('a.disp_dt', 'DESC')
            ->get();
        $output = '';
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            $output = '<div class="table-responsive"><table class="table c_vertical_align tbl_border" style="width: auto;">';
            $output .= '<tr><td colspan="8" align="center">RECORDS TO BE VERIFY<span id="enable-in-print"> FOR ' . get_user_details($usercode) . '</span></td></tr>';
            $output .= '<tr><td align="center">SNo.</td><td>Document No.</td><td>Document Type</td><td align="center">Diary No.</td><td>Case Nos.</td><td>Remarks</td><td>Dispatch By</td><td>Dispatch Date</td><td>Defect Remarks</td><td></td></tr>';

            $sno = 1;
            foreach ($result as $row) {
                $get_real_diaryno = !empty($row['diary_no']) ? get_real_diaryno($row['diary_no']) : '';
                $get_casenos_comma = !empty($row['diary_no']) ? get_casenos_comma($row['diary_no']) : '';
                $get_user_details = !empty($row['disp_by']) ? get_user_details($row['disp_by']) : '';
                $output .= '<tr>';
                $output .= '<td align="center">' . $sno . '</td>';
                $output .= '<td align="left"><span style="color:blue">' . $row['kntgrp'] . '</span>- ' . $row['docnum'] . '/' . $row['docyear'] . '</td>';
                $output .= '<td align="left">' . $row['docdesc'];
                if (!empty($row['other1'])) {
                    $output .= '-' . $row['other1'];
                }
                $output .= '</td>';
                $output .= '<td align="center">' . $get_real_diaryno . '</td>';
                $output .= '<td align="left">' . $get_casenos_comma . '</td>';
                $output .= '<td align="left">' . $row['remarks'] . '</td>';
                $output .= '<td align="left">' . $get_user_details . '</td>';
                $output .= '<td align="center">' . $row['disp_dt'] . '</td>';
                $output .= '<td align="center"><input type="text" name="tb' . $sno . '" id="tb' . $sno . '" value="' . $row['verified_remarks'] . '"/></td>';
                $output .= '<td align="center"><input type="checkbox" name="chk' . $sno . '" id="chk' . $sno . '" value="' . $row['diary_no'] . '-' . $row['doccode'] . '-' . $row['doccode1'] . '-' . $row['docnum'] . '-' . $row['docyear'] . '"/></td>';
                $output .= '</tr>';
                $sno++;
            }
            $output .= '<tr><td colspan="10" align="center"><input type="radio" name="vr" value="V" checked="checked"/>Verified <input type="radio" name="vr" value="R"/>Defective<input type="button" value="Verify" id="btnrece" onclick="verifyFunction()" /></td></tr>';
            $output .= '</table></div>';
        } else {
            $output = '<div class="nofound">SORRY!!!, NO RECORD FOUND</div>';
        }
        return $output;
    }

    public function verify_save()
    {
        $usercode = session()->get('login')['usercode'];
        $vr = $_REQUEST['vr'];

        foreach ($_REQUEST['alldata'] as $key => $value) {
            //$t_vr = $_REQUEST['tb'][$key];
            $new_value = explode('-', $value);
            $updateData =
                [
                    'verified' => $vr,
                    'verified_by' => $usercode,
                    'verified_on' => date('Y-m-d H:i:s'),
                    //'verified_remarks' => $t_vr,
                ];

            $this->db->table('docdetails')->where('diary_no', $new_value[0])->where('doccode', $new_value[1])->where('doccode1', $new_value[2])->where('docnum', $new_value[3])->where('docyear', $new_value[4])->update($updateData);
        }
    }

    public function getOldCases()
    {
        $queryString = "SELECT casecode, skey, casename, short_description FROM master.casetype
        WHERE display = 'Y'
        AND casecode != 9999
        AND casecode NOT IN (9999, 15, 16)
        ORDER BY casecode, short_description";

        $query = $this->db->query($queryString);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return [];
        }
    }

    public function old_verify_process()
    {

        if (isset($_REQUEST['ct']) && $_REQUEST['ct'] != '') {
            $get_dno = "SELECT 
            substr(cast(diary_no as text), 1, length(cast(diary_no as text)) - 4) as dn, 
            substr(cast(diary_no as text), -4) as dy
            FROM
            main 
            WHERE
            (
                split_part(fil_no, '-', 1) = CAST($_REQUEST[ct] AS text)
                AND CAST($_REQUEST[cn] AS INTEGER) BETWEEN (CAST(split_part(fil_no, '-', 2) AS INTEGER)) 
                AND (CAST(split_part(fil_no, '-', 3) AS INTEGER)) AND  EXTRACT(YEAR FROM fil_dt) = $_REQUEST[cy]
            )
            OR
            (
                split_part(fil_no_fh, '-', 1) = CAST($_REQUEST[ct] AS text)
                AND CAST($_REQUEST[cn] AS INTEGER) BETWEEN (CAST(split_part(fil_no_fh, '-', 2) AS INTEGER)) 
                AND (CAST(split_part(fil_no_fh, '-', 3) AS INTEGER)) AND  EXTRACT(YEAR FROM fil_dt_fh) = $_REQUEST[cy]
            )";
            $query = $this->db->query($get_dno);
            $get_dno = $query->getResultArray();
            $html = '<div class="cl_center"><b>No Record Found</b></div>';
            return $html;


            // $get_dno = mysql_query($get_dno) or die(__LINE__.'->'.mysql_error());
            // $get_dno = mysql_fetch_array($get_dno);
            // $_REQUEST['d_no'] = $get_dno['dn'];
            // $_REQUEST['d_yr'] = $get_dno['dy'];
        } else {
            $queryString = "SELECT 
                diary_no,
                conn_key,
                fil_no,
                fil_dt,
                EXTRACT(YEAR FROM fil_dt) AS filyr,
                TO_CHAR(fil_dt, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_f,
                fil_no_fh,
                TO_CHAR(fil_no_fh::timestamp, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_fh, 
                actcode,
                pet_adv_id,
                res_adv_id,
                lastorder,
                c_status,
                CASE 
                    WHEN fil_no != '' THEN SPLIT_PART(fil_no, '-', 1) 
                    ELSE '' 
                END AS ct1,
                CASE 
                    WHEN fil_no != '' THEN SPLIT_PART(fil_no, '-', 2)
                    ELSE '' 
                END AS crf1,
                CASE 
                    WHEN fil_no != '' THEN SPLIT_PART(fil_no, '-', 3) 
                    ELSE '' 
                END AS crl1,
                CASE 
                    WHEN fil_no_fh != '' THEN SPLIT_PART(fil_no_fh, '-', 1) 
                    ELSE '' 
                END AS ct2,
                CASE 
                    WHEN fil_no_fh != '' THEN SPLIT_PART(fil_no_fh, '-', 2) 
                    ELSE '' 
                END AS crf2,
                CASE 
                    WHEN fil_no_fh != '' THEN SPLIT_PART(fil_no_fh, '-', 3) 
                    ELSE '' 
                END AS crl2
            FROM 
                main 
            WHERE 
                SUBSTR(diary_no::text, 1, LENGTH(diary_no::text) - 4) = '" . $_REQUEST['d_no'] . "'
                AND SUBSTR(diary_no::text, -4) = '" . $_REQUEST['d_yr'] . "'";



            $query = $this->db->query($queryString);
            if ($query->getNumRows() >= 1) {

                // return $query->getResultArray();
                $html = '<div class="cl_center"><b>No Record Found</b></div>';
                return $html;
            } else {
                $html = '<div class="cl_center"><b>No Record Found</b></div>';
                return $html;
            }
        }
    }

    public function verified_defective()
    {
        $usercode = session()->get('login')['usercode'];
        $query = $this->db->table('ld_move a')
            ->select('*')
            ->join('docdetails b', 'a.diary_no = b.diary_no 
            AND a.diary_no > 0 
            AND b.diary_no > 0 
            AND a.doccode = b.doccode 
            AND a.doccode1 = b.doccode1 
            AND a.docnum = b.docnum 
            AND a.docyear = b.docyear 
            AND b.display = \'Y\' 
            AND a.rece_by = ' . $this->db->escape($usercode) . ' 
            AND b.verified = \'R\'', 'inner')
            ->join('main m', 'a.diary_no = m.diary_no', 'inner')
            ->join('master.docmaster c', 'a.doccode = c.doccode AND a.doccode1 = c.doccode1', 'left')
            ->orderBy('a.disp_dt', 'DESC')
            ->get();
        $html = '';
        if ($query->getNumRows() >= 1) {
            $records = $query->getResultArray();

            $html .= '<table class="c_vertical_align tbl_border">';
            $html .= '<tr><td colspan="8" align="center">RECORDS TO BE VERIFY';
            $html .= '<span id="enable-in-print">FOR ' . get_user_details($usercode) . '</span></td></tr>';
            $html .= '<tr><td align="center">SNo.</td><td>Document No.</td><td>Document Type</td>';
            $html .= '<td align="center">Diary No.</td><td>Case Nos.</td><td>Remarks</td>';
            $html .= '<td>Dispatch By</td><td>Dispatch Date</td><td>Defect Remarks</td><td></td></tr>';

            $sno = 1;
            foreach ($records as $row) {
                $html .= '<tr>';
                $html .= '<td align="center">' . $sno . '</td>';
                $html .= '<td align="left"><span style="color:blue">' . $row['kntgrp'] . '</span>- ' . $row['docnum'] . '/' . $row['docyear'] . '</td>';
                $html .= '<td align="left">' . $row['docdesc'];
                if (!empty($row['other1'])) {
                    $html .= '-' . $row['other1'];
                }
                $html .= '</td>';
                $html .= '<td align="center">' . get_real_diaryno($row['diary_no']) . '</td>';
                $html .= '<td align="left">' . get_casenos_comma($row['diary_no']) . '</td>';
                $html .= '<td align="left">' . $row['remarks'] . '</td>';
                $html .= '<td align="left">' . get_user_details($row['disp_by']) . '</td>';
                $html .= '<td align="center">' . $row['disp_dt'] . '</td>';
                $html .= '<td align="center"><input type="text" name="tb' . $sno . '" id="tb' . $sno . '" value="' . $row['verified_remarks'] . '"/></td>';
                $html .= '<td align="center"><input type="checkbox" name="chk' . $sno . '" id="chk' . $sno . '" value="' . $row['diary_no'] . '-' . $row['doccode'] . '-' . $row['doccode1'] . '-' . $row['docnum'] . '-' . $row['docyear'] . '"/></td>';
                $html .= '</tr>';
                $sno++;
            }
            $html .= '<tr><td colspan="8" align="center">';
            $html .= '<input type="radio" name="vr" value="V" checked="checked"/>Verified ';
            $html .= '<input type="radio" name="vr" value="R"/>Defective ';
            $html .= '<input type="button" value="Verify" id="btnrece" onclick="verifyFunction()" /></td></tr>';
            $html .= '</table>';
        } else {
            $html = '<div class="nofound">SORRY!!!, NO RECORD FOUND</div>';
        }
        return $html;
    }

    public function no_of_times_listed($diaryno)
    {
        $builder = $this->db->table('heardt')
            ->selectCount('*', 'total')
            ->where('diary_no', $diaryno)
            ->whereIn('main_supp_flag', [1, 2])
            ->where('clno !=', 0)
            ->where('brd_slno !=', 0)
            ->where('judges !=', '')
            ->get()
            ->getRow();

        $builder2 = $this->db->table('last_heardt')
            ->selectCount('*', 'total')
            ->where('diary_no', $diaryno)
            ->whereIn('main_supp_flag', [1, 2])
            ->groupStart()
            ->where('bench_flag IS NULL')
            ->orWhere('bench_flag', '')
            ->groupEnd()
            ->where('clno !=', 0)
            ->where('brd_slno !=', 0)
            ->where('judges !=', '')
            ->get()
            ->getRow();

        return ($builder->total ?? 0) + ($builder2->total ?? 0);
    }

    public function verified_defectives($diary_no, $doccode, $doccode1, $docnum, $docyear)
    {
        $sql = "SELECT fil_no FROM ld_move WHERE diary_no='$diary_no' AND doccode='$doccode' AND doccode1='$doccode1' AND docnum='$docnum' AND docyear='$docyear'";
        $query = $this->db->query($sql);
        $result = $query->getRowArray();
        return $result;
    }



    public function insertDatas($new_value0, $new_value1, $new_value2, $new_value3, $new_value4, $ucode, $new_value5, $now, $fil_no = null, $remarks = null, $rece_by = null, $other = null)
    {
        $data = [
            'diary_no'  => $new_value0,
            'doccode'   => $new_value1,
            'doccode1'  => $new_value2,
            'docnum'    => $new_value3,
            'docyear'   => $new_value4,
            'disp_by'   => $ucode,
            'disp_to'   => $new_value5,
            'disp_dt'   => $now,
            'fil_no'    => $fil_no ?? 0,  // adding because db error accer
            'remarks'    => $remarks ?? 0, // adding because db error accer
            'rece_by'    => $rece_by ?? 0, // adding because db error accer
            'other'    => $other ?? 0 // adding because db error accer
        ];

        return $this->db->table('ld_move')->insert($data);
    }

    public function getDiaryNumber1($ct, $cn, $cy)
    {
        $sql = "SELECT SUBSTRING(diary_no::TEXT FROM 1 FOR CHAR_LENGTH(diary_no::TEXT) - 4) AS dn, SUBSTRING(diary_no::TEXT FROM CHAR_LENGTH(diary_no::TEXT) - 3 FOR 4) AS dy
                FROM main
                WHERE   (
                (CASE WHEN SPLIT_PART(fil_no, '-', 1) ~ '^[0-9]+$' THEN SPLIT_PART(fil_no, '-', 1)::INTEGER ELSE 0 END) = $ct
                AND CAST($cn AS INTEGER) BETWEEN 
                                    (CASE
                                    WHEN SPLIT_PART(fil_no, '-', 2) ~ '^[0-9]+$'
                                        THEN SPLIT_PART(fil_no, '-', 2)::INTEGER ELSE 0 END) 
                                AND 
                                    (CASE
                                    WHEN SPLIT_PART(fil_no, '-', -1) ~ '^[0-9]+$'
                                        THEN SPLIT_PART(fil_no, '-', -1)::INTEGER
                                ELSE 0 END)
                AND    (
                reg_year_mh = 0
                OR fil_dt > '2017-05-10'
                )
                AND EXTRACT(YEAR FROM fil_dt) = $cy
                )
                OR   (
                (CASE WHEN SPLIT_PART(fil_no_fh, '-', 1) ~ '^[0-9]+$' THEN SPLIT_PART(fil_no_fh, '-', 1)::INTEGER ELSE 0 END) = $ct
                AND CAST($cn AS INTEGER) BETWEEN 
                                    (CASE
                                    WHEN SPLIT_PART(fil_no_fh, '-', 2) ~ '^[0-9]+$'
                                        THEN SPLIT_PART(fil_no_fh, '-', 2)::INTEGER ELSE 0 END) 
                                AND 
                                    (CASE
                                    WHEN SPLIT_PART(fil_no_fh, '-', -1) ~ '^[0-9]+$'
                                        THEN SPLIT_PART(fil_no_fh, '-', -1)::INTEGER
                                ELSE 0 END)
                AND reg_year_fh = 0
                AND EXTRACT(YEAR FROM fil_dt_fh) = $cy
                )";
        $query = $this->db->query($sql);
        $get_dno = $query->getRowArray();
        return $get_dno;
    }




    public function getDiaryDetails1($d_no, $d_yr)
    {
        //$d_no = '142192009'; // remove 
        $query = $this->db->table('main')
            ->select([
                'diary_no',
                'conn_key',
                'fil_no',
                'fil_dt',
                'EXTRACT(YEAR FROM fil_dt) AS filyr',
                "TO_CHAR(fil_dt, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_f",
                'fil_no_fh',
                "TO_CHAR(fil_dt_fh, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_fh",
                'actcode',
                'pet_adv_id',
                'res_adv_id',
                'lastorder',
                'c_status',
                'casetype_id',
                'bench',
                "CASE WHEN NULLIF(fil_no, '') IS NOT NULL THEN split_part(fil_no, '-', 1) ELSE '' END AS ct1",
                "CASE WHEN NULLIF(fil_no, '') IS NOT NULL THEN split_part(fil_no, '-', 2) ELSE '' END AS crf1",
                "CASE WHEN NULLIF(fil_no, '') IS NOT NULL THEN split_part(fil_no, '-', 3) ELSE '' END AS crl1",
                "CASE WHEN NULLIF(fil_no_fh, '') IS NOT NULL THEN split_part(fil_no_fh, '-', 1) ELSE '' END AS ct2",
                "CASE WHEN NULLIF(fil_no_fh, '') IS NOT NULL THEN split_part(fil_no_fh, '-', 2) ELSE '' END AS crf2",
                "CASE WHEN NULLIF(fil_no_fh, '') IS NOT NULL THEN split_part(fil_no_fh, '-', 3) ELSE '' END AS crl2"
            ])
            ->where("LEFT(CAST(diary_no AS TEXT), LENGTH(CAST(diary_no AS TEXT)) - 4) =", $d_no)
            ->where("RIGHT(CAST(diary_no AS TEXT), 4) =", $d_yr)
            ->get();

        return $query->getRowArray();
    }

    public function getBenchName($bench)
    {
        $query = $this->db->table('master.master_bench')
            ->select('bench_name')
            ->where('display', 'Y')
            ->where('id', $bench)
            ->get();

        return $query->getRowArray();
    }

    public function get_diary_details1($dno)
    {
        $query = $this->db->table('party p')
            ->select("
                p.sr_no, 
                p.pet_res, 
                p.ind_dep, 
                p.partyname, 
                p.sonof, 
                p.prfhname, 
                p.age, 
                p.sex, 
                p.caste, 
                p.addr1, 
                p.addr2, 
                p.pin, 
                p.state, 
                p.city, 
                p.email, 
                p.contact AS mobile, 
                p.deptcode, 
                (SELECT deptname FROM master.deptt WHERE deptcode = p.deptcode) AS deptname, 
                c.skey
            ")
            ->join('main m', 'm.diary_no = p.diary_no AND p.sr_no = 1 AND p.pflag = \'P\' AND p.pet_res IN (\'P\', \'R\')', 'inner')
            ->join('master.casetype c', 'c.casecode = CAST(SUBSTRING(m.fil_no FROM 3 FOR 3) AS INTEGER)', 'left')
            ->where('m.diary_no', $dno)
            ->orderBy('p.pet_res, p.sr_no')
            ->get();

        return $query->getResultArray();
    }


    public function getDistrictName1($stateCode, $cityCode)
    {
        $builder = $this->db->table('master.state')
            ->select('name')
            ->where('sub_dist_code', 0)
            ->where('village_code', 0)
            ->where('display', 'Y');

        if (!empty($stateCode)) {
            $builder->where('state_code', $stateCode);
        }
        if (!empty($cityCode)) {
            $builder->where('district_code', $cityCode);
        }

        return $builder->get()->getRowArray();
    }



    public function getActSections1($diaryNo)
    {
        $query = $this->db->table('act_main a')
            ->select("a.act, STRING_AGG(b.section, ', ') AS section, c.act_name")
            ->join('master.act_section b', 'a.id = b.act_id AND b.display = \'Y\'', 'left')
            ->join('master.act_master c', 'c.id = a.act')
            ->where('a.display', 'Y')
            ->where('c.display', 'Y')
            ->where('a.diary_no', $diaryNo)
            ->groupBy('a.act, c.act_name')
            ->get();

        return $query->getResultArray();
    }

    public function getDisplayFlag1()
    {
        return $this->db->table('master.case_status_flag')
            ->select('display_flag, always_allowed_users')
            ->where('flag_name', 'tentative_listing_date')
            ->groupStart()
            ->where('to_date IS NULL')
            ->orWhere('to_date', '1970-01-01')
            ->groupEnd()
            ->get()
            ->getRowArray();
    }







    public function getIANDoccode($diary_no)
    {
        //$diary_no = '142192009'; // remove 
        $builder = $this->db->table('docdetails a');
        $builder->select('
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
            a.verified
        ');
        $builder->join('master.docmaster b', 'a.doccode = b.doccode AND a.doccode1 = b.doccode1', 'inner');
        $builder->where('a.diary_no', $diary_no);
        $builder->where('a.doccode', 8);
        $builder->where('a.display', 'Y');
        $builder->where('b.display', 'Y');
        $builder->orderBy('a.ent_dt', 'ASC');

        $query = $builder->get();
        return $query->getResultArray();
    }


    public function getOtherDoccode($diary_no)
    {
        // $diary_no = '142192009'; // remove 
        $db = \Config\Database::connect();

        return $db->table('docdetails a')
            ->select('a.diary_no, a.doccode, a.doccode1, a.docnum, a.docyear, a.filedby, 
                      a.docfee, a.forresp, a.feemode, a.ent_dt, a.other1, b.docdesc, a.verified')
            ->join('master.docmaster b', 'a.doccode = b.doccode AND a.doccode1 = b.doccode1', 'inner')
            ->where('a.diary_no', $diary_no)
            ->where('a.doccode !=', 8)
            ->where('a.display', 'Y')
            ->orderBy('a.ent_dt', 'ASC')
            ->get()
            ->getResultArray();
    }
}
