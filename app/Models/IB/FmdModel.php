<?php

namespace App\Models\IB;

use CodeIgniter\Model;

class FmdModel extends Model
{

    function __construct()
    {
        parent::__construct();
        $db = \Config\Database::connect();
    }

    public function getDiaryNumber($ct, $cn, $cy)
    {
        // First query on 'main' table
        $builder = $this->db->table('main');
        $builder->select("SUBSTR(diary_no, 1, LENGTH(diary_no) - 4) AS dn, SUBSTR(diary_no, -4) AS dy");
        $builder->where("SUBSTRING_INDEX(fil_no, '-', 1)", $ct);
        $builder->where("CAST($cn AS UNSIGNED) BETWEEN SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no, '-', 2), '-', -1) AND SUBSTRING_INDEX(fil_no, '-', -1)");
        $builder->groupStart()
            ->where("reg_year_mh = 0 OR DATE(fil_dt) > DATE('2017-05-10')")
            ->groupStart()
            ->where("EXTRACT(YEAR FROM fil_dt)", $cy) // Use EXTRACT instead of YEAR
            ->groupEnd()
            ->orWhere("reg_year_mh", $cy)
            ->groupEnd();

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        }

        // Second query on 'main_casetype_history' table
        $builder = $this->db->table('main_casetype_history');
        $builder->select("SUBSTR(h.diary_no, 1, LENGTH(h.diary_no) - 4) AS dn, SUBSTR(h.diary_no, -4) AS dy,
        IF(h.new_registration_number != '', SUBSTRING_INDEX(h.new_registration_number, '-', 1), '') AS ct1,
        IF(h.new_registration_number != '', SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2), '-', -1), '') AS crf1,
        IF(h.new_registration_number != '', SUBSTRING_INDEX(h.new_registration_number, '-', -1), '') AS crl1");

        $builder->where("SUBSTRING_INDEX(h.new_registration_number, '-', 1)", $ct);
        $builder->where("CAST($cn AS UNSIGNED) BETWEEN SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2), '-', -1) AND SUBSTRING_INDEX(h.new_registration_number, '-', -1)");
        $builder->where("h.new_registration_year", $cy);
        $builder->where("h.is_deleted", 'f');

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        }

        return null; // Return null if no results
    }


    public function getCaseTypeDescription($ct)
    {
        return $this->db->table('master.casetype')
            ->select('short_description')
            ->where('casecode', $ct)
            ->where('display', 'Y')
            ->get()
            ->getRow();
    }


    public function getDiaryDetails($d_no, $d_yr)
    {
        $builder = $this->db->table('main');

        $builder->select("
            dacode,
            diary_no,
            conn_key,
            fil_dt,
            EXTRACT(YEAR FROM fil_dt) AS filyr,
            TO_CHAR(fil_dt, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_f,
            fil_no_fh,
            TO_CHAR(fil_dt_fh, 'DD-MM-YYYY HH12:MI AM') AS fil_dt_fh,
            actcode,
            pet_adv_id,
            res_adv_id,
            lastorder,
            c_status,
            CASE 
                WHEN diary_no::text != '' THEN SPLIT_PART(diary_no::text, '-', 1) 
                ELSE '' 
            END AS ct1,
            CASE 
                WHEN diary_no::text != '' THEN SPLIT_PART(diary_no::text, '-', 2) 
                ELSE '' 
            END AS crf1,
            CASE 
                WHEN diary_no::text != '' THEN SPLIT_PART(diary_no::text, '-', 3) 
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
            END AS crl2,
            CASE 
                WHEN conn_key != '' AND conn_key IS NOT NULL THEN 
                    CASE 
                        WHEN conn_key = diary_no::text THEN 'N' 
                        ELSE 'Y' 
                    END 
                ELSE 'N' 
            END AS ccdet,
            casetype_id,
            conn_key AS connto
        ");

        $builder->where('LENGTH(diary_no::text) >', 4);
        $builder->where('SUBSTRING(diary_no::text FROM 1 FOR LENGTH(diary_no::text) - 4)', $d_no);
        $builder->where('LENGTH(diary_no::text) >=', 4);
        $builder->where('SUBSTRING(diary_no::text FROM LENGTH(diary_no::text) - 3 FOR 4)', $d_yr);

        return $builder->get()->getRowArray();
    }

    public function getUserSection($ucode)
    {
        $builder = $this->db->table('master.users');
        $builder->select('section, usertype');
        $builder->where('usercode', $ucode);

        $query = $builder->get();

        return $query->getRowArray();
    }

    public function get_diary_details($diaryno)
    {
        $builder = $this->db->table('main a');
        $builder->select('a.usercode, b.name, us.section_name');
        $builder->join('master.users b', 'a.usercode = b.usercode', 'left');
        $builder->join('master.usersection us', 'b.section = us.id', 'left');
        $builder->where('a.diary_no', $diaryno);

        $query = $builder->get();

        return $query->getRowArray();
    }

    public function getShortDescription($caseCode)
    {
        $builder = $this->db->table('master.casetype');
        $builder->select('short_description');
        $builder->where('casecode', $caseCode);
        $builder->where('display', 'Y');

        $query = $builder->get();

        return $query->getRowArray();
    }

    public function getPartyDetails($diaryNo)
    {
        $sql = "SELECT
  SUBSTR(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4) AS case_no,
  SUBSTR(m.diary_no::text, LENGTH(m.diary_no::text) - 3, 4) AS year,
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
  c.skey,
  TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY HH12:MI AM') AS diary_no_rec_date
FROM
  party p
JOIN
  main m ON m.diary_no = p.diary_no
LEFT JOIN
  master.casetype c ON c.casecode::text = SUBSTRING(m.diary_no::text, 3, 3)
WHERE
  p.sr_no = 1
  AND p.pflag = 'P'
  AND p.pet_res IN ('P', 'R')
  AND m.diary_no = $diaryNo
ORDER BY
  p.pet_res, p.sr_no";

        $query = $this->db->query($sql);

        return $query->getResultArray();
    }

    public function getDistrictName($stateCode, $cityCode)
    {
        $builder = $this->db->table('master.state');
        $builder->select('name');
        $builder->where('state_code', $stateCode);
        $builder->where('district_code', $cityCode);
        $builder->where('sub_dist_code', 0);
        $builder->where('village_code', 0);
        $builder->where('display', 'Y');

        $query = $builder->get();

        return $query->getRowArray();
    }

    public function getLowerCourtDetails($diaryNo)
    {
        $builder = $this->db->table('lowerct a');
        $builder->select('a.lct_dec_dt, a.lct_caseno, a.lct_caseyear, ct.short_description AS type_sname');
        $builder->join('master.casetype ct', 'ct.casecode = a.lct_casetype AND ct.display = \'Y\'', 'left');
        $builder->where('a.diary_no', $diaryNo);
        $builder->where('a.lw_display', 'Y');
        $builder->where('ct_code', 4);
        $builder->orderBy('a.lct_dec_dt');

        return $builder->get()->getResultArray();
    }


    public function getActDetails($diaryNo)
    {
        $builder = $this->db->table('act_main a');
        $builder->select('a.act, STRING_AGG(b.section, \', \') AS section, c.act_name');
        $builder->join('master.act_section b', 'a.id = b.act_id', 'left');
        $builder->join('master.act_master c', 'c.id = a.act', 'join');
        $builder->where('a.display', 'Y');
        $builder->where('b.display', 'Y');
        $builder->where('c.display', 'Y');
        $builder->where('diary_no', $diaryNo);
        $builder->groupBy('a.act, c.act_name');

        return $builder->get()->getResultArray();
    }


    public function get_advocates($adv_id, $wen = '')
    {
        $t_adv = "";

        if ($adv_id != 0) {
            $builder = $this->db->table('master.bar');
            $builder->select('name, enroll_no, EXTRACT(YEAR FROM enroll_date) AS eyear, isdead'); // Changed to EXTRACT
            $builder->whereIn('bar_id', explode(',', $adv_id));
            $query = $builder->get();
            if ($query->getNumRows() > 0) {
                foreach ($query->getResultArray() as $row11a) {
                    $t_adv = $row11a['name'];
                    if ($row11a['isdead'] == 'Y') {
                        $t_adv = "<font color='red'>" . $t_adv . " (Dead / Retired / Elevated) </font>";
                    }
                    if ($wen == 'wen') {
                        $t_adv .= " [" . $row11a['enroll_no'] . "/" . $row11a['eyear'] . "]";
                    }
                }
            }
        }

        return $t_adv;
    }




    public function getCaseLaw($id)
    {
        return $this->db->table('master.caselaw')
            ->select('law')
            ->where('id', $id)
            ->get()
            ->getRowArray();
    }

    public function getAdvocatesByDiaryNo($diaryNo)
    {
        return $this->db->table('advocate')
            ->select('pet_res_no, adv, advocate_id, pet_res')
            ->where('diary_no', $diaryNo)
            ->where('display', 'Y')
            ->orderBy('pet_res')
            ->get()
            ->getResultArray();
    }

    public function getJudgmentDetails($diaryNo)
    {
        $builder = $this->db->table('dispose d')
            ->select('d.rj_dt, d.jud_id, DATE_FORMAT(d.ent_dt, "%d-%m-%Y") AS ddt, DATE_FORMAT(d.ord_dt, "%d-%m-%Y") AS odt, d.disp_dt, d.month, d.year, d.dispjud, d.disp_type, GROUP_CONCAT(j.jname ORDER BY j.judge_seniority SEPARATOR ", ") AS judges')
            ->join('master.judge j', 'FIND_IN_SET(j.jcode, d.jud_id)', 'left')
            ->where('d.diary_no', $diaryNo)
            ->groupBy('d.diary_no');

        return $builder->get()->getRowArray();
    }

    public function getJudgeNames($diaryNo)
    {
        return $this->db->query("
            SELECT GROUP_CONCAT(j.jname ORDER BY j.judge_seniority SEPARATOR ', ') AS judges
            FROM (
                SELECT jcodes FROM case_remarks_multiple WHERE diary_no = '$diaryNo' GROUP BY cl_date ORDER BY e_date DESC LIMIT 1, 1
            ) a
            LEFT JOIN master.judge j ON FIND_IN_SET(j.jcode, a.jcodes) > 0
        ")->getRowArray();
    }

    public function getDisposalType($disptype)
    {
        return $this->db->table('disposal')
            ->where('dispcode', $disptype)
            ->get()
            ->getRowArray();
    }

    public function getRgo($diaryNo)
    {
        $sql = "SELECT DISTINCT fil_no2 FROM rgo_default WHERE fil_no = ? AND remove_def = 'N'";
        return $this->db->query($sql, [$diaryNo])->getResultArray();
    }

    public function getTentativeListingDate($diaryNo)
    {
        $sql = "SELECT tentative_cl_dt FROM heardt WHERE diary_no = ?";
        return $this->db->query($sql, [$diaryNo])->getRowArray();
    }

    public function getCaseStatusFlag()
    {
        $sql = "SELECT display_flag, always_allowed_users 
            FROM master.case_status_flag 
            WHERE to_date IS NULL AND flag_name = 'tentative_listing_date'";
        return $this->db->query($sql)->getRowArray();
    }


    public function getConnectedCases($diaryNo)
    {
        $sql = "
            SELECT
                m.diary_no,
                (SELECT `list` FROM conct cc WHERE cc.diary_no = m.diary_no LIMIT 1) AS llist
            FROM
                main m
            WHERE
                (m.diary_no = ? OR m.conn_key IN (SELECT conn_key FROM main WHERE diary_no = ?))
                AND m.diary_no != m.conn_key
            ORDER BY
                m.fil_dt
        ";

        return $this->db->query($sql, [$diaryNo, $diaryNo])->getResultArray();
    }

    public function getDiaryByNo($diaryNo)
    {
        // Convert diaryNo to string if necessary
        $sql = "SELECT
                diary_no,
                pet_name,
                res_name,
                pet_adv_id,
                res_adv_id,
                c_status,
                lastorder,
                bench,
                (CASE 
                    WHEN (conn_key != '' AND conn_key IS NOT NULL) 
                    THEN (CASE 
                        WHEN conn_key::text = diary_no::text THEN 'N' 
                        ELSE 'Y' 
                    END) 
                    ELSE 'N' 
                END) AS ccdet,
                conn_key AS connto
            FROM
                main
            WHERE
                diary_no::text = :diary_no:";

        $query = $this->db->query($sql, ['diary_no' => (string)$diaryNo]);
        return $query->getRowArray();
    }


    public function getDocDetailsByDiaryNo($diaryNo)
    {
        $sql = "SELECT 
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
                    b.docdesc 
                FROM 
                    docdetails a 
                JOIN 
                    master.docmaster b ON a.doccode = b.doccode AND a.doccode1 = b.doccode1 
                WHERE 
                    a.diary_no = :diary_no: 
                    AND a.doccode = 8 
                ORDER BY 
                    a.ent_dt";

        $query = $this->db->query($sql, ['diary_no' => $diaryNo]);
        return $query->getResultArray();
    }

    public function getOtherDocDetailsByDiaryNo($diaryNo)
    {
        $sql = "SELECT 
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
                    b.docdesc 
                FROM 
                    docdetails a 
                JOIN 
                    master.docmaster b ON a.doccode = b.doccode AND a.doccode1 = b.doccode1 
                WHERE 
                    a.diary_no = :diary_no: 
                    AND a.doccode != 8 
                ORDER BY 
                    a.ent_dt";

        $query = $this->db->query($sql, ['diary_no' => $diaryNo]);
        return $query->getResultArray();
    }

    public function getSKeyByDiaryNo($diaryNo)
    {
        $caseCode = intval(substr($diaryNo, 2, 3));

        $sql = "SELECT skey FROM master.casetype WHERE casecode = :casecode: AND display = 'Y'";
        $query = $this->db->query($sql, ['casecode' => $caseCode]);

        return $query->getRowArray();
    }

    public function getNotBefore($diaryNo)
    {
        $sql = "SELECT a.diary_no, STRING_AGG(b.jname, ', ') AS jn, a.notbef 
            FROM not_before a 
            JOIN master.judge b ON (b.jcode = a.j1 OR b.jcode = a.j2 OR b.jcode = a.j3 OR b.jcode = a.j4 OR b.jcode = a.j5) 
            WHERE a.diary_no = :diary_no: 
            GROUP BY a.diary_no, a.notbef";

        return $this->db->query($sql, ['diary_no' => $diaryNo])->getResultArray();
    }


    public function getPartyNames($diaryNo)
    {
        $sql = "SELECT STRING_AGG(partyname, ', ' ORDER BY sr_no) AS pn, pet_res 
            FROM party 
            WHERE diary_no = :diary_no: AND sr_no > 1 
            GROUP BY pet_res";

        return $this->db->query($sql, ['diary_no' => $diaryNo])->getResultArray();
    }


    public function getHearings($diaryNo)
    {
        $sql = "SELECT * FROM heardt WHERE diary_no = :diary_no:";
        return $this->db->query($sql, ['diary_no' => $diaryNo])->getResultArray();
    }

    public function getLastHearings($diaryNo)
    {
        $sql = "SELECT * FROM last_heardt WHERE diary_no = $diaryNo AND next_dt IS NOT NULL ORDER BY ent_dt DESC";
        return $this->db->query($sql, ['diary_no' => $diaryNo])->getResultArray();
    }



    public function getUserDetails($userCode)
    {
        $sql = "SELECT section, is_CourtMaster, usertype FROM master.users WHERE usercode = $userCode";
        return $this->db->query($sql)->getRowArray();
    }

    public function get_diaryDetails($diaryNo)
    {
        $sql = "SELECT  
  COALESCE(m.dacode::text, '') AS dacode, 
  COALESCE(u.name, '') AS username, 
  u.empid 
FROM 
  main m 
LEFT JOIN 
  master.users u ON m.dacode = u.usercode 
WHERE 
  m.diary_no = $diaryNo";

        return $this->db->query($sql)->getRowArray();
    }



    public function getDocuments($diaryNo)
    {
        $builder = $this->db->table('docdetails a');
        $builder->select('a.diary_no, a.doccode, a.doccode1, a.docnum, a.docyear, a.filedby, a.docfee, a.forresp, a.feemode, a.ent_dt, a.other1, a.iastat, b.docdesc');
        $builder->join('master.docmaster b', 'a.doccode = b.doccode AND a.doccode1 = b.doccode1');
        $builder->where('a.diary_no', $diaryNo);
        $builder->where('a.doccode', 8);
        $builder->where('a.display', 'Y');
        $builder->orderBy('ent_dt');

        return $builder->get()->getResultArray();
    }

    public function getJudges()
    {
        $builder = $this->db->table('master.judge');
        $builder->select("jcode AS jcode, 
            CASE 
                WHEN (jname LIKE '%CHIEF JUSTICE%' OR jname LIKE '%Registrar%') 
                THEN CONCAT(TRIM(jname), ' (', first_name, ' ', sur_name, ')') 
                ELSE TRIM(jname) 
            END AS jname");
        $builder->where('display', 'Y');
        $builder->whereIn('jtype', ['J', 'R']);
        $builder->orderBy("CASE WHEN is_retired = 'N' THEN 0 ELSE 1 END, jtype, judge_seniority");
    
        return $builder->get()->getResultArray();
    }
    

    public function getCaseRemarks()
    {
        $sql = "SELECT *
FROM master.case_remarks_head
WHERE side = 'D'
  AND display = 'Y'
ORDER BY CASE WHEN cat_head_id < 1000 THEN 0 ELSE 1 END, head;
";
$query = $this->db->query($sql);

return $query->getResultArray();
    }
}
