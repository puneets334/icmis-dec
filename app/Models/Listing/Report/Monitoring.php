<?php

namespace App\Models\Listing\Report;

use CodeIgniter\Model;

class Monitoring extends Model
{
  // protected $table = 'main';
  // protected $primaryKey = 'diary_no';
  // protected $allowedFields = ['diary_no', 'active_fil_no', 'fil_no', 'fil_no_old', 'pet_name', 'res_name', 'res_name_old', 'pet_adv_id', 'res_adv_id', 'actcode', 'claim_amt', 'bench', 'fixed', 'c_status', 'fil_dt', 'active_fil_dt', 'case_pages', 'relief', 'usercode', 'last_usercode', 'dacode', 'old_dacode', 'old_da_ec_case', 'last_dt', 'conn_key', 'case_grp', 'lastorder', 'fixeddet', 'bailno', 'prevno', 'head_code', 'scr_user', 'scr_time', 'scr_type', 'prevno_fildt', 'ack_id', 'ack_rec_dt', 'admitted', 'outside', 'diary_no_rec_date', 'diary_user_id', 'ref_agency_state_id', 'ref_agency_state_id_old', 'ref_agency_code_id', 'ref_agency_code_id_old', 'from_court', 'is_undertaking', 'undertaking_doc_type', 'undertaking_reason', 'casetype_id', 'active_casetype_id', 'padvt', 'radvt', 'total_court_fee', 'court_fee', 'valuation', 'case_status_id', 'brief_description', 'nature', 'fil_no_fh', 'fil_no_fh_old', 'fil_dt_fh', 'mf_active', 'active_reg_year', 'reg_year_mh', 'reg_year_fh', 'reg_no_display', 'pno', 'rno', 'if_sclsc', 'section_id', 'unreg_fil_dt', 'refiling_attempt', 'last_return_to_adv', 'create_modify', 'pet_name_hindi', 'hindi_timestamp', 'res_name_hindi', 'updated_by_ip', 'updated_by', 'updated_on', 'listorder', 'next_dt'];



  public function getListedNotVerified($txt_fd, $txt_td)
  {
    $sql = "SELECT aa.*, 
                       SUM(
                           (SELECT COUNT(diary_no) FROM heardt z 
                            WHERE z.diary_no = aa.diary_no 
                            AND clno > 0 
                            AND brd_slno > 0 
                            AND (main_supp_flag = 1 OR main_supp_flag = 2))
                           +
                           (SELECT COUNT(diary_no) FROM last_heardt zl 
                            WHERE zl.diary_no = aa.diary_no 
                            AND clno > 0 
                            AND brd_slno > 0 
                            AND (main_supp_flag = 1 OR main_supp_flag = 2)
                            AND (bench_flag IS NULL OR bench_flag = ''))
                       ) AS ss, 
                       pet_name, 
                       res_name, 
                       active_fil_no, 
                       EXTRACT(YEAR FROM active_fil_dt) AS active_fil_dt 
                FROM (
                    SELECT diary_no, next_dt 
                    FROM heardt 
                    WHERE next_dt BETWEEN '$txt_fd' AND '$txt_td'
                    AND clno > 0 
                    AND brd_slno > 0 
                    AND (main_supp_flag = 1 OR main_supp_flag = 2)
                    UNION 
                    SELECT diary_no, next_dt 
                    FROM last_heardt 
                    WHERE next_dt BETWEEN '$txt_fd' AND '$txt_td'
                    AND clno > 0 
                    AND brd_slno > 0 
                    AND (main_supp_flag = 1 OR main_supp_flag = 2)
                    AND (bench_flag IS NULL OR bench_flag = '')
                ) aa 
                JOIN main m ON aa.diary_no = m.diary_no 
                LEFT JOIN defects_verification bb ON aa.diary_no = bb.diary_no 
                WHERE (bb.diary_no IS NULL OR CAST(verification_status AS INTEGER) = 1) 
                AND (fil_no_fh IS NULL OR fil_no_fh = '')
                GROUP BY aa.diary_no, aa.next_dt, pet_name, res_name, active_fil_no, active_fil_dt 
                HAVING SUM(
                    (SELECT COUNT(diary_no) FROM heardt z 
                     WHERE z.diary_no = aa.diary_no 
                     AND clno > 0 
                     AND brd_slno > 0 
                     AND (main_supp_flag = 1 OR main_supp_flag = 2))
                    +
                    (SELECT COUNT(diary_no) FROM last_heardt zl 
                     WHERE zl.diary_no = aa.diary_no 
                     AND clno > 0 
                     AND brd_slno > 0 
                     AND (main_supp_flag = 1 OR main_supp_flag = 2)
                     AND (bench_flag IS NULL OR bench_flag = ''))
                ) = 1
                ORDER BY next_dt, 
                         CAST(SUBSTRING(aa.diary_no::TEXT, LENGTH(aa.diary_no::TEXT) - 3, 4) AS INTEGER), 
                         CAST(SUBSTRING(aa.diary_no::TEXT, 1, LENGTH(aa.diary_no::TEXT) - 4) AS INTEGER)";
    //pr($sql);

    $query = $this->db->query($sql);

    return $query->getResultArray();
  }


  public function getListedNotVerifiedpg($txt_fd, $txt_td)
  {
    $db = \Config\Database::connect();

    // Subquery for first part of the SUM (COUNT(diary_no))
    $subQuery1 = $db->table('heardt z')
      ->select('z.diary_no, COUNT(z.diary_no) AS count1')
      ->where('z.clno >', 0)
      ->where('z.brd_slno >', 0)
      ->whereIn('z.main_supp_flag', [1, 2])
      ->groupBy('z.diary_no');

    // Subquery for second part of the SUM (COUNT(diary_no))
    $subQuery2 = $db->table('last_heardt zl')
      ->select('zl.diary_no, COUNT(zl.diary_no) AS count2')
      ->where('zl.clno >', 0)
      ->where('zl.brd_slno >', 0)
      ->whereIn('zl.main_supp_flag', [1, 2])
      ->where('zl.bench_flag IS NULL OR zl.bench_flag = \'\'')
      ->groupBy('zl.diary_no');

    // Main query
    $builder = $db->table('heardt')
      ->select('aa.*, COALESCE(SUM(sub1.count1), 0) + COALESCE(SUM(sub2.count2), 0) AS ss')
      ->select('pet_name, res_name, active_fil_no, EXTRACT(YEAR FROM active_fil_dt) AS active_fil_dt')
      ->from('(SELECT diary_no, next_dt FROM heardt WHERE next_dt BETWEEN ' . $db->escape($txt_fd) . ' AND ' . $db->escape($txt_td) . ' AND clno > 0 AND brd_slno > 0 AND (main_supp_flag = 1 OR main_supp_flag = 2) UNION SELECT diary_no, next_dt FROM last_heardt WHERE next_dt BETWEEN ' . $db->escape($txt_fd) . ' AND ' . $db->escape($txt_td) . ' AND clno > 0 AND brd_slno > 0 AND (main_supp_flag = 1 OR main_supp_flag = 2) AND (bench_flag IS NULL OR bench_flag = \'\')) aa')
      ->join('main m', 'aa.diary_no = m.diary_no')
      ->join('defects_verification bb', 'aa.diary_no = bb.diary_no', 'left')
      ->join('(' . $subQuery1->getCompiledSelect() . ') sub1', 'sub1.diary_no = aa.diary_no', 'left')
      ->join('(' . $subQuery2->getCompiledSelect() . ') sub2', 'sub2.diary_no = aa.diary_no', 'left')
      ->where('bb.diary_no IS NULL OR CAST(verification_status AS INTEGER) = 1')
      ->where('fil_no_fh IS NULL OR fil_no_fh = \'\'')
      ->groupBy('aa.diary_no, aa.next_dt, pet_name, res_name, active_fil_no, active_fil_dt')
      ->having('COALESCE(SUM(sub1.count1), 0) + COALESCE(SUM(sub2.count2), 0) = 1')
      ->orderBy('next_dt')
      ->orderBy('CAST(SUBSTRING(aa.diary_no::TEXT, LENGTH(aa.diary_no::TEXT) - 3, 4) AS INTEGER)')
      ->orderBy('CAST(SUBSTRING(aa.diary_no::TEXT, 1, LENGTH(aa.diary_no::TEXT) - 4) AS INTEGER)');

    // Execute query
    $query = $builder->get();

    return $query->getResultArray();
  }



  public function CtRemarks_Changeby_user_data($on_date)
  {
    // Escape the date parameter to prevent SQL injection
    $on_date = $this->db->escape($on_date);

    $sql = "SELECT a.uid, 
      CONCAT(u.name, '@', u.empid, ' SEC ', us.section_name) AS uid_name,
      SUM(CASE WHEN newcnt = 0 THEN crm_count ELSE 0 END) AS current_total,
      SUM(CASE WHEN newcnt = 1 THEN crm_count ELSE 0 END) AS history_total,
      SUM(CASE WHEN newcnt >= 0 THEN crm_count ELSE 0 END) AS total 
        FROM (
          SELECT uid, COUNT(*) AS crm_count, 0 AS newcnt 
          FROM (
            SELECT DISTINCT diary_no , uid 
            FROM case_remarks_multiple 
            WHERE cl_date = {$on_date} 
          ) a 
          GROUP BY a.uid
  
          UNION ALL
  
          SELECT uid, COUNT(*) AS crm_count, 1 AS newcnt 
          FROM (
            SELECT uid, fil_no 
            FROM case_remarks_multiple_history 
            WHERE fil_no::int IN (
              SELECT DISTINCT diary_no ::int
              FROM case_remarks_multiple 
              WHERE cl_date = {$on_date}
            ) 
            AND cl_date = {$on_date} 
            GROUP BY fil_no, uid
          ) b 
          GROUP BY b.uid
        ) a
        LEFT JOIN master.users u ON u.usercode = a.uid AND (u.display = 'Y' OR u.display IS NULL)       
        LEFT JOIN master.usersection us ON us.id = u.section AND us.display = 'Y'
        GROUP BY a.uid, u.name, u.empid, us.section_name
        ORDER BY history_total DESC";
    // pr($sql);
    $query = $this->db->query($sql);
    if ($query->getNumRows() >= 1) {
      return $query->getResultArray();
    } else {
      return false;
    }
  }


  public function CtRemarks_user_details($cl_date, $flag, $usercode)
  {
    $db = \Config\Database::connect();
    $builder = $db->table('case_remarks_multiple'); // Reference the table used in the query

    $cond1 = "";
    $cond2 = "";
    $cond3 = "";
    $cond4 = " crm.uid=$usercode and crm.cl_date='$cl_date' group by crm.uid,crm.diary_no ";

    if ($flag == 'C') {
      $sql = "SELECT crm.diary_no,
              CONCAT(m.reg_no_display, '@ D.No.', SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4), '/', SUBSTRING(m.diary_no::text FROM -4)) AS CaseNo,
              h.clno, h.brd_slno, h.tentative_cl_dt, m.pet_name, m.res_name,
              STRING_AGG(DISTINCT CONCAT(crh.head, 
                    CASE WHEN crm.head_content != '' THEN CONCAT(' [', crm.head_content, ']') ELSE '' END, 
                    ' By:-> ', 
                    CONCAT(u.name, '@', u.empid, ' SEC ', us.section_name), 
                    ' On:- ', crm.e_date), ', ') AS Rmrk_Disp,
              STRING_AGG(DISTINCT crm.head_content, ', ') AS Head_Content,
              COALESCE(cl.next_dt::text, 'NA') AS brd_prnt,
              h.roster_id, Rt.courtno,
              CONCAT(u.name, '@', u.empid, ' SEC ', us.section_name) AS uid
            FROM case_remarks_multiple crm
            INNER JOIN main m ON m.diary_no = crm.diary_no::int
            LEFT JOIN master.users u ON u.usercode = crm.uid AND (u.display = 'Y' OR u.display IS NULL)
            LEFT JOIN master.usersection us ON us.id = u.section AND us.display = 'Y'
            LEFT JOIN master.case_remarks_head crh ON crh.sno = crm.r_head AND (crh.display = 'Y' OR crh.display IS NULL)
            INNER JOIN (
            SELECT t1.diary_no, t1.next_dt, t1.roster_id, t1.judges, t1.mainhead, t1.subhead, t1.clno,
              t1.brd_slno, t1.main_supp_flag, t1.tentative_cl_dt
            FROM heardt t1
            WHERE t1.next_dt = '$cl_date' AND 
              (t1.main_supp_flag = 1 OR t1.main_supp_flag = 2)
            UNION
            SELECT t2.diary_no, t2.next_dt, t2.roster_id, t2.judges, t2.mainhead, t2.subhead,
              t2.clno, t2.brd_slno, t2.main_supp_flag, t2.tentative_cl_dt
            FROM last_heardt t2
            WHERE t2.next_dt = '$cl_date' AND 
              (t2.main_supp_flag = 1 OR t2.main_supp_flag = 2) AND t2.bench_flag = ''
            ) h ON (h.diary_no = m.diary_no AND h.next_dt = '$cl_date' 
                AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2))
            LEFT JOIN master.roster Rt ON Rt.id = h.roster_id
            LEFT JOIN cl_printed cl ON (cl.next_dt = h.next_dt AND cl.m_f = h.mainhead AND cl.part = h.clno AND cl.main_supp = h.main_supp_flag AND cl.roster_id = h.roster_id AND cl.display = 'Y')
            WHERE crm.uid = '$usercode' AND crm.cl_date = '$cl_date'
            GROUP BY m.diary_no,crm.uid, crm.diary_no, m.reg_no_display, h.clno, h.brd_slno, h.tentative_cl_dt, m.pet_name, m.res_name, cl.next_dt, h.roster_id, Rt.courtno, u.name, u.empid, us.section_name;";
    } else {
      $sql = "SELECT crmh.fil_no,
              CONCAT(m.reg_no_display, '@ D.No.', SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4), '/', SUBSTRING(m.diary_no::text FROM -4)) AS CaseNo,
              h.clno, h.brd_slno, h.tentative_cl_dt, m.pet_name, m.res_name,
              STRING_AGG(DISTINCT CONCAT(crh.head, 
                    CASE WHEN crm.head_content != '' THEN CONCAT(' [', crm.head_content, ']') ELSE '' END, 
                    ' By:-> ', 
                    CONCAT(u.name, '@', u.empid, ' SEC ', us.section_name), 
                    ' On:- ', crm.e_date), ', ') AS Rmrk_Disp,
              STRING_AGG(DISTINCT crm.head_content, ', ') AS Head_Content,
              STRING_AGG(DISTINCT crmh.r_head::text, ',') AS Old_Disp_Remarks,
              STRING_AGG(DISTINCT CONCAT(crh1.head, 
                    CASE WHEN crmh.head_content != '' THEN CONCAT(' [', crmh.head_content, ']') ELSE '' END, 
                    ' <b>By:-> ', 
                    CONCAT(u1.name, '@', u1.empid, ' SEC ', us1.section_name), 
                    ' On:- ', crmh.e_date), ',</b> ') AS Old_Rmrk_Disp,
              STRING_AGG(DISTINCT crmh.head_content, ', ') AS old_Head_Content,
              CONCAT(u1.name, '@', u1.empid, ' SEC ', us1.section_name) AS old_uid,
              COALESCE(cl.next_dt::text, 'NA') AS brd_prnt,
              h.roster_id, Rt.courtno,
              CONCAT(u.name, '@', u.empid, ' SEC ', us.section_name) AS uid
            FROM case_remarks_multiple_history crmh
            INNER JOIN main m ON m.diary_no = crmh.fil_no::int
            LEFT JOIN master.users u1 ON u1.usercode = crmh.uid AND (u1.display = 'Y' OR u1.display IS NULL)
            LEFT JOIN master.usersection us1 ON us1.id = u1.section AND us1.display = 'Y'
            LEFT JOIN master.case_remarks_head crh1 ON crh1.sno = crmh.r_head AND (crh1.display = 'Y' OR crh1.display IS NULL)
            INNER JOIN case_remarks_multiple crm ON crmh.fil_no = crm.diary_no AND crm.cl_date = '$cl_date'
            LEFT JOIN master.users u ON u.usercode = crm.uid AND (u.display = 'Y' OR u.display IS NULL)
            LEFT JOIN master.usersection us ON us.id = u.section AND us.display = 'Y'
            LEFT JOIN master.case_remarks_head crh ON crh.sno = crm.r_head AND (crh.display = 'Y' OR crh.display IS NULL)
            INNER JOIN (
            SELECT t1.diary_no, t1.next_dt, t1.roster_id, t1.judges, t1.mainhead, t1.subhead, t1.clno,
              t1.brd_slno, t1.main_supp_flag, t1.tentative_cl_dt
            FROM heardt t1
            WHERE t1.next_dt = '$cl_date' AND 
              (t1.main_supp_flag = 1 OR t1.main_supp_flag = 2)
            UNION
            SELECT t2.diary_no, t2.next_dt, t2.roster_id, t2.judges, t2.mainhead, t2.subhead,
              t2.clno, t2.brd_slno, t2.main_supp_flag, t2.tentative_cl_dt
            FROM last_heardt t2
            WHERE t2.next_dt = '$cl_date' AND 
              (t2.main_supp_flag = 1 OR t2.main_supp_flag = 2) AND t2.bench_flag = ''
            ) h ON (h.diary_no = m.diary_no AND h.next_dt = '$cl_date' 
                AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2))
            LEFT JOIN master.roster Rt ON Rt.id = h.roster_id
            LEFT JOIN cl_printed cl ON (cl.next_dt = h.next_dt AND cl.m_f = h.mainhead AND cl.part = h.clno AND cl.main_supp = h.main_supp_flag AND cl.roster_id = h.roster_id AND cl.display = 'Y')
            WHERE crmh.uid = '$usercode' AND crmh.cl_date = '$cl_date'
            GROUP BY m.diary_no, crmh.uid, crmh.fil_no, m.reg_no_display, h.clno, h.brd_slno, h.tentative_cl_dt, m.pet_name, m.res_name, cl.next_dt, h.roster_id, Rt.courtno, u1.name, u1.empid, us1.section_name, u.name, u.empid, us.section_name;";
    }
    $query = $db->query($sql);
    $result = $query->getResultArray();

    return $result;
  }

  public function getNotVerifiedData($ddl_nv_r)
  {

    if ($ddl_nv_r == 2) {
      $db = \Config\Database::connect();
      try {
        $builder = $db->table('main a');
        $builder->select('a.diary_no, a.diary_no_rec_date, DATE_PART(\'day\', CURRENT_DATE - a.diary_no_rec_date) as s');
        $builder->join('defects_verification b', 'a.diary_no = b.diary_no', 'left');
        $builder->join('heardt c', 'c.diary_no = a.diary_no', 'left');
        $builder->where('a.diary_no_rec_date >=', '2017-06-01');
        $builder->groupStart()
          ->where('b.diary_no IS NULL')
          ->orWhere('b.verification_status', '1')
          ->groupEnd();
        $builder->where('c.diary_no IS NULL');
        $builder->orderBy("CAST(SUBSTRING(CAST(a.diary_no AS TEXT), LENGTH(CAST(a.diary_no AS TEXT)) - 3, 4) AS INTEGER), CAST(SUBSTRING(CAST(a.diary_no AS TEXT), 1, LENGTH(CAST(a.diary_no AS TEXT)) - 4) AS INTEGER)");

        $query = $builder->get();
        if ($query->getNumRows() > 0) {
          return $query->getResultArray();
        } else {
          return false;
        }
      } catch (\Exception $e) {
        return false;
      }
    } else {
      return false;
    }
  }

  function monitoring_Error_Dawise_count()
  {
    $sql = "
              SELECT 
      daName,
      SUM(CourtRemark) AS CourtRemark,
      SUM(Subhead) AS Subhead,
      SUM(Purpose) AS Purpose,
      SUM(CauseTitle) AS CauseTitle,
      SUM(AOR_NA) AS AOR_NA,
      SUM(StatutaryInfo) AS StatutaryInfo,
      SUM(ProposalMissing) AS ProposalMissing,
      SUM(IA) AS IA,
      SUM(ROP) AS ROP,
      SUM(Before_NotBefore) AS Before_NotBefore
        FROM (
      SELECT 
          f.daName,
          CASE WHEN f.remarks = 'Court Remark' THEN f.count_remarks END AS CourtRemark,
          CASE WHEN f.remarks = 'Subhead' THEN f.count_remarks END AS Subhead,
          CASE WHEN f.remarks = 'Purpose' THEN f.count_remarks END AS Purpose,
          CASE WHEN f.remarks = 'Cause Title' THEN f.count_remarks END AS CauseTitle,
          CASE WHEN f.remarks = 'AOR NA' THEN f.count_remarks END AS AOR_NA,
          CASE WHEN f.remarks = 'Statutary Info' THEN f.count_remarks END AS StatutaryInfo,
          CASE WHEN f.remarks = 'Proposal missing' THEN f.count_remarks END AS ProposalMissing,
          CASE WHEN f.remarks = 'IA' THEN f.count_remarks END AS IA,
          CASE WHEN f.remarks = 'ROP' THEN f.count_remarks END AS ROP,
          CASE WHEN f.remarks = 'Before / Not Before' THEN f.count_remarks END AS Before_NotBefore
        FROM (
          SELECT 
              c.diary_no, 
              r.id, 
              CONCAT(u1.empid, '@', u1.name, '@SEC ', us.section_name) AS daName, 
              us.section_name, 
              CONCAT(u.empid, '@', u.name) AS RmrkBy, 
              r.remarks, 
              COUNT(r.id) AS count_remarks
          FROM 
              case_verify c
          LEFT JOIN 
              master.users u ON u.usercode = c.ucode AND (u.display = 'Y' OR u.display IS NULL) 
          INNER JOIN 
              master.case_verify_by_sec_remark r ON STRING_TO_ARRAY(c.remark_id, ',')::int[] && ARRAY[r.id::int]
          INNER JOIN 
              main m ON m.diary_no = c.diary_no
          LEFT JOIN 
              master.users u1 ON u1.usercode = m.dacode AND (u1.display = 'Y' OR u1.display IS NULL)
          LEFT JOIN 
              master.usersection us ON us.id = u1.section AND us.display = 'Y'
          WHERE 
              m.c_status = 'P' 
              AND r.id NOT IN (1, 10)
              AND c.remark_id IS NOT NULL
              AND c.remark_id != 'undefined'  -- Exclude rows where remark_id is 'undefined'
          GROUP BY 
              c.diary_no, r.id, u1.empid, u1.name, us.section_name, u.empid, u.name, r.remarks
            ) AS f
        ) AS l 
        GROUP BY GROUPING SETS ((daName), ())";

    $query = $this->db->query($sql);

    if ($query->getNumRows() >= 1) {
      return $query->getResultArray();
    } else {
      return false;
    }
  }

  public function getActiveCaseTypes()
  {
    $sql = "SELECT casecode, casename FROM master.casetype WHERE display = 'Y' ORDER BY casename";
    return $this->db->query($sql)->getResultArray();
  }

  public function getRegisteredNotVerifiedNotListed($ddl_nv_r)
  {
    $db = \Config\Database::connect();
    $builder = $db->table('main');

    if ($ddl_nv_r !== 'All') {
      $builder->where('m.active_casetype_id', $ddl_nv_r);
    }

    $sql = "SELECT 
                  CONCAT(
                    SUBSTRING(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4), 
                    '/', 
                    SUBSTRING(m.diary_no::text, LENGTH(m.diary_no::text) - 3, 4)
                  ) AS diary_no, 
                  m.reg_no_display AS reg_no, 
                  TO_CHAR(m.active_fil_dt, 'DD-MM-YYYY') AS reg_date, 
                  TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS diary_date, 
                  CASE 
                    WHEN m.dacode = 0 THEN tentative_da(m.diary_no::INTEGER) 
                    ELSE CONCAT(u.name, '[', u.empid, ']') 
                  END AS daname, 
                  CASE 
                    WHEN m.section_id IS NULL OR m.section_id::text = '' THEN tentative_section(m.diary_no) 
                    ELSE us.section_name 
                  END AS section, 
                  m.pet_name, 
                  m.res_name 
                FROM 
                  main m 
                  LEFT JOIN defects_verification v ON m.diary_no = v.diary_no 
                  LEFT JOIN (
                    SELECT diary_no 
                    FROM heardt 
                    WHERE main_supp_flag IN (1, 2) 
                    AND clno != 0 
                    AND brd_slno != 0 
                    AND judges != '' 
                    AND judges != '0' 
                    UNION 
                    SELECT diary_no 
                    FROM last_heardt 
                    WHERE main_supp_flag IN (1, 2) 
                    AND clno != 0 
                    AND brd_slno != 0 
                    AND judges != '' 
                    AND judges != '0'
                  ) a ON m.diary_no = a.diary_no 
                  LEFT JOIN master.users u ON m.dacode = u.usercode 
                  LEFT JOIN master.usersection us ON m.section_id = us.id 
                WHERE 
                  m.diary_no_rec_date >= '2017-05-08' 
                  AND (m.active_fil_no IS NOT NULL AND m.active_fil_no != '') 
                  AND (v.diary_no IS NULL OR v.diary_no::text = '' OR verification_status = '1') 
                  AND (a.diary_no IS NULL OR a.diary_no::text = '') 
                  AND m.c_status = 'P' ";

    if ($ddl_nv_r !== 'All') {
      $sql .= " AND m.active_casetype_id = " . $db->escape($ddl_nv_r);
    }

    $sql .= " ORDER BY m.active_fil_dt";

    $query = $db->query($sql);
    return $query->getResultArray();
  }

  public function get_verification_data($txt_fd, $txt_td)
  {
    $builder = $this->db->table('main a');
    $builder->select("a.diary_no, fil_dt, verification_date, (verification_date - fil_dt) AS s");
    $builder->join('defects_verification b', 'a.diary_no = b.diary_no');
    $builder->where('fil_no !=', '');
    $builder->where('c_status', 'P');
    $builder->where('fil_dt <= verification_date');
    $builder->where('fil_dt >=', $txt_fd);
    $builder->where('fil_dt <=', $txt_td);
    $builder->orderBy('(verification_date - fil_dt)', 'ASC');
    $builder->orderBy('SUBSTR(a.diary_no::text, -4)');
    $builder->orderBy('SUBSTR(a.diary_no::text, 1, LENGTH(a.diary_no::text) - 4)');
    $builder->orderBy('fil_dt', 'ASC');
    $query = $builder->get();
    return $query->getResultArray();
  }


  public function getVerifiedMatters($condition)
  {

    $sql = "SELECT 
          v.verification_status,
          CONCAT(LEFT(m.diary_no::text, LENGTH(m.diary_no::text) - 4), '/', RIGHT(m.diary_no::text, 4)) AS diary_no,
          m.reg_no_display AS reg_no,
          TO_CHAR(m.active_fil_dt, 'DD-MM-YYYY') AS reg_date,
          TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS diary_date,
          CASE 
              WHEN m.dacode = 0 THEN tentative_da(m.diary_no::int) 
              ELSE CONCAT(u.name, '[', u.empid, ']') 
          END AS daname,
          CASE 
              WHEN (m.section_id IS NULL OR m.section_id::text = '') 
              THEN tentative_section(m.diary_no) 
              ELSE us.section_name 
          END AS section,
          m.pet_name,
          m.res_name
          FROM main m  
          JOIN defects_verification v ON m.diary_no = v.diary_no
          LEFT JOIN (
          SELECT diary_no 
          FROM public.heardt 
          WHERE main_supp_flag IN (1,2) 
              AND clno != 0 
              AND brd_slno != 0 
              AND judges != '' 
              AND judges != '0'
          UNION 
          SELECT diary_no 
          FROM public.last_heardt 
          WHERE main_supp_flag IN (1,2) 
              AND clno != 0 
              AND brd_slno != 0 
              AND judges != '' 
              AND judges != '0'
          ) a ON m.diary_no = a.diary_no
          LEFT JOIN master.users u ON m.dacode = u.usercode
          LEFT JOIN master.usersection us ON m.section_id = us.id
          WHERE 
                m.diary_no_rec_date >= '2017-05-08'
              AND (m.active_fil_no IS NOT NULL AND m.active_fil_no != '') 
              AND v.verification_status = '0'
              AND (a.diary_no IS NULL OR a.diary_no::text = '')
              AND m.c_status = 'P'  
              $condition 
          ORDER BY m.active_fil_dt";
    $query = $this->db->query($sql);
    return $query->getResultArray();
  }
  public function getCaseTitle($frm_dt, $to_dt, $emp_nm, $remarks, $jn_users_r)
  {
    // Remove vkg
    // $frm_dt='2023-01-02';
    // $to_dt='2025-02-04';

    $sql = "SELECT DISTINCT zz.* 
              FROM (
                  SELECT 
                      ft.diary_no,
                      ft.d_to_empid,
                      ft.r_by_empid,
                      ft.disp_dt,
                      ft.d_by_empid,
                      ft.rece_dt,
                      h.next_dt,
                      CONCAT(m.reg_no_display, ' @ ', 
                            LEFT(m.diary_no::text, LENGTH(m.diary_no::text) - 4), ' / ', 
                            RIGHT(m.diary_no::text, 4)) AS case_no, 
                      CONCAT(m.pet_name, ' Vs. ', m.res_name) AS cause_title
                  FROM fil_trap ft 
                  INNER JOIN main m ON m.diary_no = ft.diary_no 
                  INNER JOIN public.heardt h ON m.diary_no = h.diary_no
                  WHERE ft.rece_dt BETWEEN ' $frm_dt' AND '$to_dt'
                  $emp_nm
                  $remarks
                  AND ft.r_by_empid != 0
                  AND ft.diary_no NOT IN (SELECT diary_no FROM indexing WHERE file_id IS NOT NULL)
                  UNION ALL 
                  SELECT 
                      fts.diary_no,
                      fts.d_to_empid,
                      fts.r_by_empid,
                      fts.disp_dt,
                      fts.d_by_empid,
                      fts.rece_dt,
                      h.next_dt,
                      CONCAT(m.reg_no_display, ' @ ', 
                            LEFT(m.diary_no::text, LENGTH(m.diary_no::text) - 4), ' / ', 
                            RIGHT(m.diary_no::text, 4)) AS case_no, 
                      CONCAT(m.pet_name, ' Vs. ', m.res_name) AS cause_title
                  FROM fil_trap_his fts
                  INNER JOIN main m ON m.diary_no = fts.diary_no 
                  INNER JOIN public.heardt h ON m.diary_no = h.diary_no
                  WHERE fts.rece_dt BETWEEN ' $frm_dt' AND '$to_dt'
                $emp_nm
                $remarks
                  AND fts.r_by_empid != 0
                  AND fts.diary_no NOT IN (SELECT diary_no FROM indexing WHERE file_id IS NOT NULL)
              ) zz 
              $jn_users_r
              ORDER BY zz.next_dt";
    $query = $this->db->query($sql);
    $results = $query->getResultArray();
    return $results;
  }

  public function getNextdate($dairy_no)
  {
    $sql = "Select next_dt,clno,brd_slno from heardt where diary_no='$dairy_no'";
    $query = $this->db->query($sql);
    $results = $query->getRowArray();
    return $results;
  }

  public function getChkDa($usercode, $r_section, $r_usertype, $ddl_users)
  {
    if ($usercode != '1' && $r_section != '30' && $r_usertype != '4' && $r_section != '20') {
      $chk_users = " AND u.usercode = '$usercode'";
      $userQuery = $this->db->table('master.users')
        ->select('empid')
        ->where('usercode', $usercode)
        ->get();

      if ($userQuery->getNumRows() > 0) {
        $r_user_id = $userQuery->getRow()->empid;
        if ($ddl_users == '108' || $ddl_users == '105') {
          $chk_da = " AND r_by_empid = '$r_user_id'";
        } else {
          $chk_da = " AND d_to_empid = '$r_user_id'";
        }
        return $chk_da;
      }
    }
  }

  public function get_usr_nm_uid($d_to_empid)
  {
    $query = $this->db->query("SELECT name FROM master.users WHERE usercode = ?", [$d_to_empid]);
    $result = $query->getRowArray();
    return $result ? $result['name'] : null;
  }

  public function get_usr_nm($str)
  {
    $query = $this->db->query("SELECT name FROM master.users WHERE empid = ?", [$str]);
    $result = $query->getRowArray();
    return $result ? $result['name'] : null;
  }
  public function get_judge_nm($jud_id)
  {
    $query = $this->db->query("SELECT jname FROM master.judge WHERE jcode = ?", [$jud_id]);
    $result = $query->getRowArray();
    return $result ? $result['jname'] : null;
  }

  public function getCategory($diary_no)
  {
    $category = "SELECT sub_name1, sub_name4,category_sc_old FROM mul_category a JOIN master.submaster b ON a.submaster_id = b.id WHERE 
                diary_no = '$diary_no' AND a.display = 'Y' AND b.display = 'Y'";
    $query = $this->db->query($category);
    $results = $query->getResultArray();
    return $results;
  }

  public function getNef($diary_no)
  {
    $sql = "Select notbef,j1 from not_before where diary_no='$diary_no' order by notbef";
    $query = $this->db->query($sql);
    $results = $query->getResultArray();
    return $results;
  }

  public function getCoarm($diary_no)
  {
    $sql = "Select coram from heardt where diary_no= '$diary_no' ";

    $query = $this->db->query($sql);
    $results = $query->getRowArray();
    return $results;
  }

  public function getNextDates($diary_no)
  {
    $sql = "Select next_dt,clno,brd_slno from heardt where diary_no='$diary_no' ";
    $query = $this->db->query($sql);
    $results = $query->getResultArray();
    return $results;
  }

  public function getCasePage($diary_no)
  {
    $sql = "select case_pages from main where diary_no='$diary_no' ";
    $query = $this->db->query($sql);
    $results = $query->getResultArray();
    return $results;
  }

  public function getUser($ucode)
  {
    $builder = $this->db->table('master.users');
    $builder->select('name');
    $builder->where('usercode', $ucode);
    $query = $builder->get();
    return $query->getRowArray();
  }

  public function getAllDa($ucode)
{
    $builder = $this->db->table('master.users u');
    $builder->select("STRING_AGG(u2.usercode::TEXT, ',') AS allda");
    $builder->join('master.users u2', 'u2.section = u.section', 'left');
    $builder->where('u.display', 'Y');
    $builder->where('u.usercode', $ucode);
    $builder->groupBy('u2.section');

    $query = $builder->get();
    return $query->getRowArray(); 
}

public function categoryScOld($dno)
{
  //remove vkg
  $sql="SELECT category_sc_old FROM mul_category mc INNER JOIN master.submaster s ON s.id = mc.submaster_id WHERE mc.display = 'Y' and mc.diary_no = '$dno' ";
  $query = $this->db->query($sql);
  $results = $query->getResultArray();
  return $results;

}

public function getMainQuery($sec_id ,$list_dt ,$checkDaCode)
{
 //remove vkg
 //$list_dt=2023-09-22;
  
  $sql = "SELECT DISTINCT
            m.diary_no,
            h.next_dt,
            u.name,
            CASE 
              WHEN us.section_name IS NOT NULL THEN us.section_name 
              ELSE tentative_section(m.diary_no) 
            END AS section_name,
            m.conn_key AS main_key,
            l.purpose,
            s.stagename,
            h.coram,
            c1.short_description,
            active_fil_no,
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
            pno,
            rno,
            m.diary_no_rec_date,
            CASE 
              WHEN (m.diary_no = m.conn_key::int OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL)
                THEN 0
              ELSE 1
            END AS main_or_connected,
            (
              SELECT CASE WHEN diary_no IS NOT NULL THEN 1 ELSE 0 END
              FROM conct
              WHERE diary_no = m.diary_no AND LIST = 'Y'
            ) AS listed,
            to_char(tt.ent_dt, 'DD-MM-YYYY HH12:MI AM') AS verified_on,
            (
              SELECT replace(STRING_AGG(remarks, E'\n'), ',', E'\n')
              FROM master.case_verify_by_sec_remark
              WHERE id::text = ANY(string_to_array(tt.remark_id, ','))
            ) AS remarks_by_monitoring,
            (
              SELECT CONCAT(name, '(', empid, ')')
              FROM master.users
              WHERE usercode = tt.ucode
            ) AS verified_by
          FROM main m
            INNER JOIN heardt h ON h.diary_no = m.diary_no
            INNER JOIN case_verify tt ON tt.diary_no = h.diary_no
            LEFT JOIN master.listing_purpose l ON l.code = h.listorder AND l.display = 'Y'
            LEFT JOIN master.subheading s ON s.stagecode = h.subhead AND s.display = 'Y' AND s.listtype = 'M'
            LEFT JOIN master.casetype c1 ON m.active_fil_no = c1.casecode::text
            LEFT JOIN master.users u ON u.usercode = m.dacode AND u.display = 'Y'
            LEFT JOIN master.usersection us ON us.id = u.section $sec_id
          WHERE tt.display = 'Y'
           AND date(h.next_dt) = '$list_dt'
              $checkDaCode 
              AND (
              COALESCE(NULLIF(TRIM(LEADING '0' FROM split_part(m.fil_no, '-', 1)), '')::INTEGER, 0) IN 
              (3,15,19,31,23,24,40,32,34,22,39,11,17,13,1,7,37,9999,38,5,21,27,4,16,20,18,33,41,35,36,28,12,14,2,8,6)
              OR m.active_fil_no = ''
              OR m.active_fil_no IS NULL
          )

              AND CASE 
              WHEN (m.diary_no = m.conn_key::int OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL)
                  THEN TRUE
              ELSE EXISTS (
                  SELECT 1 
                  FROM conct 
                  WHERE diary_no = m.diary_no 
                    AND conn_key IN (SELECT diary_no FROM heardt t1 WHERE t1.next_dt = h.next_dt)
              )
          END

          GROUP BY m.diary_no,h.next_dt,u.name,us.section_name,l.purpose,s.stagename,h.coram,c1.short_description,tt.ent_dt,tt.remark_id,tt.ucode
          ORDER BY
          1,2,
            main_or_connected ASC
            LIMIT 20"; // remove limt
           
            $query = $this->db->query($sql);
            $results = $query->getResultArray();
            return $results;
}

public function getAdv($diary_no)
{
  $advsql = "SELECT a.*, 
              STRING_AGG(
                  a.name || COALESCE(CASE WHEN pet_res = 'R' THEN grp_adv END, ''), ', ' 
                  ORDER BY adv_type DESC, pet_res_no ASC
              ) AS r_n,
              STRING_AGG(
                  a.name || COALESCE(CASE WHEN pet_res = 'P' THEN grp_adv END, ''), ', ' 
                  ORDER BY adv_type DESC, pet_res_no ASC
              ) AS p_n,
              STRING_AGG(
                  a.name || COALESCE(CASE WHEN pet_res = 'I' THEN grp_adv END, ''), ', ' 
                  ORDER BY adv_type DESC, pet_res_no ASC
              ) AS i_n
          FROM (
              SELECT a.diary_no, 
                    b.name, 
                    STRING_AGG(
                          COALESCE(a.adv, ''), ', ' 
                          ORDER BY CASE WHEN pet_res = 'I' THEN 99 ELSE 0 END ASC, adv_type DESC, pet_res_no ASC
                    ) AS grp_adv, 
                    a.pet_res, 
                    a.adv_type, 
                    a.pet_res_no
              FROM advocate a 
              LEFT JOIN master.bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y' 
            -- WHERE a.diary_no = '52018' 
              WHERE a.diary_no = '$diary_no' 
                AND a.display = 'Y' 
              GROUP BY a.diary_no, b.name, a.pet_res, a.adv_type, a.pet_res_no
          ) a 
          GROUP BY a.diary_no,a.name,a.grp_adv,a.pet_res,a.adv_type,a.pet_res_no";

    $query = $this->db->query($advsql);
    $results = $query->getRowArray();
    return $results;
}

// public function getSectionName($casetype_displ ,$ten_reg_yr,$ref_agency_state_id)
// {
//   $section_ten_q = "SELECT dacode,section_name,name FROM master.da_case_distribution a
//   LEFT JOIN master.users b ON usercode=dacode
//   LEFT JOIN master.usersection c ON b.section=c.id
//   WHERE case_type=$casetype_displ AND $ten_reg_yr BETWEEN case_f_yr AND case_t_yr AND state='$ref_agency_state_id' AND a.display='Y' ";
//   $query = $this->db->query($section_ten_q);
//   $results = $query->getRowArray();
//   return $results;
 
// }

public function getSectionName($casetype_displ, $ten_reg_yr, $ref_agency_state_id)
{
    $builder = $this->db->table('master.da_case_distribution a');
    $builder->select('a.dacode, c.section_name, b.name');
    $builder->join('master.users b', 'b.usercode = a.dacode', 'left');
    $builder->join('master.usersection c', 'b.section = c.id', 'left');
    $builder->where('a.case_type', $casetype_displ);
    $builder->where("$ten_reg_yr BETWEEN a.case_f_yr AND a.case_t_yr");
    $builder->where('a.state', $ref_agency_state_id);
    $builder->where('a.display', 'Y');

    $query = $builder->get();
    return $query->getRowArray(); 
}

public function rop_verify_daily_court_remarks_process($crt,$dtd,$jcd,$mf,$r_status,$vstats){
  $ucode = session()->get('login')['usercode'];
  $usertype=session()->get('login')['usertype'];
  $tdt1 = date('Y-m-d',strtotime($dtd));
  $printFrm = 0;
  $pr_mf = $mf;
  $resultData = [];

  if($crt!=''){
    $resultData = $this->getROPVerificationsql1($mf, $crt, $vstats,$r_status,$tdt1,$ucode,$usertype);
  }
  if($jcd!=''){
    $resultData = $this->getROPVerificationsql2($mf, $jcd,$r_status,$tdt1,$ucode,$usertype);
  }

  return $resultData;

}
private function getROPVerificationsql2($mf, $jcd,$r_status,$tdt1,$ucode,$usertype)
{
if($mf == 'M') 
$tmf='1';
else if($mf == 'F') 
    $tmf='2';
else if($mf == 'L') 
    $tmf='3';
else if($mf == 'S') 
    $tmf='4';
///=====================================
  $whereStatus="";
if($r_status=='A'){
    $whereStatus='';
}
else if($r_status=='P'){
    $whereStatus=" and m.c_status='P'";
}
else if($r_status=='D'){
    $whereStatus=" and m.c_status='D'";
}

$get_users = $this->get_users_single($ucode);
  $get_users_allda = $this->get_users_allda($ucode);
  $username_uby = $get_users['name'];
  $checkDaCode="";
  if($ucode==1){
      $checkDaCode="";
  }
  else if($usertype == '14' AND $ucode != 3564 AND $ucode != 722 AND $ucode != 1182 AND $ucode != 184){
      $all_da = $get_users_allda['allda'];
      $checkDaCode="AND (m.dacode=$ucode or find_in_set(m.dacode,$all_da))";
      $mdacode = "";
  }
  else if(($usertype == '17' OR $usertype == '50' OR $usertype == '51') AND ($ucode != 3564 AND $ucode != 722 AND $ucode != 1182 AND $ucode != 184)){
      $mdacode = "";
      $checkDaCode = "AND m.dacode=$ucode";
  }
  else{
      $mdacode = "";
  }
  $sql_t="SELECT
            SUBSTRING(m.diary_no::TEXT FROM 1 FOR LENGTH(m.diary_no::TEXT) - 4) AS case_no,
            SUBSTRING(m.diary_no::TEXT FROM LENGTH(m.diary_no::TEXT) - 3 FOR 4) AS year,
            TO_CHAR(m.fil_dt, 'DD-MM-YYYY HH:MI AM') AS fil_dt_f,
            CASE WHEN m.reg_year_mh = 0 THEN EXTRACT(YEAR FROM m.fil_dt) ELSE m.reg_year_mh END AS m_year,
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
            CASE WHEN cl.next_dt IS NULL THEN 'NA' ELSE h.brd_slno::TEXT END AS brd_prnt,
            h.roster_id,
            TO_CHAR(m.fil_dt_fh, 'DD-MM-YYYY HH:MI AM') AS fil_dt_fh,
            CASE WHEN m.reg_year_fh = 0 THEN EXTRACT(YEAR FROM m.fil_dt_fh) ELSE m.reg_year_fh END AS f_year,
            CASE WHEN m.fil_no != '' THEN SPLIT_PART(m.fil_no, '-', 1) ELSE '' END AS ct1,
            CASE WHEN m.fil_no != '' THEN SPLIT_PART(SPLIT_PART(m.fil_no, '-', 2), '-', 1) ELSE '' END AS crf1,
            CASE WHEN m.fil_no != '' THEN SPLIT_PART(m.fil_no, '-', 3) ELSE '' END AS crl1,
            CASE WHEN m.fil_no_fh != '' THEN SPLIT_PART(m.fil_no_fh, '-', 1) ELSE '' END AS ct2,
            CASE WHEN m.fil_no_fh != '' THEN SPLIT_PART(SPLIT_PART(m.fil_no_fh, '-', 2), '-', 1) ELSE '' END AS crf2,
            CASE WHEN m.fil_no_fh != '' THEN SPLIT_PART(m.fil_no_fh, '-', 3) ELSE '' END AS crl2,
            m.casetype_id,
            m.case_status_id,
            (SELECT STRING_AGG(remarks, ',') FROM case_verify_by_sec_remark WHERE id = ANY(STRING_TO_ARRAY(cvr.remark_id, ',')::INT[])) AS remarks_by_monitoring,
            (SELECT name || '(' || empid || ')' FROM master.users WHERE usercode = cvr.ucode) AS verified_by,
            TO_CHAR(cvr.ent_dt, 'DD-MM-YYYY HH:MI AM') AS verified_on
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
            WHERE t1.next_dt = '" . $tdt1 . "' 
              AND t1.mainhead = '".$mf."' 
              AND t1.judges LIKE '%".$jcd."%' 
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
            WHERE t2.next_dt = '" . $tdt1 . "' 
              AND t2.mainhead = '".$mf."' 
              AND t2.judges LIKE '%".$jcd."%' 
              AND (t2.main_supp_flag = 1 OR t2.main_supp_flag = 2)
              AND t2.bench_flag = ''
        ) h
        INNER JOIN main m ON (h.diary_no = m.diary_no AND h.next_dt = '" . $tdt1 . "' AND h.mainhead = '".$mf."' AND h.judges LIKE '%".$jcd."%' AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2))
        LEFT JOIN master.subheading s ON s.stagecode = h.subhead AND s.display = 'Y' AND s.listtype = 'M'
        LEFT JOIN cl_printed cl ON (cl.next_dt = h.next_dt AND cl.m_f = h.mainhead AND cl.part = h.clno AND cl.main_supp = h.main_supp_flag AND cl.roster_id = h.roster_id AND cl.display = 'Y')
        INNER JOIN case_verify_rop cvr ON m.diary_no = cvr.diary_no AND h.next_dt = cvr.cl_dt
        WHERE cl.next_dt IS NOT NULL $whereStatus $checkDaCode 
        ORDER BY
            POSITION('".$jcd."' IN h.judges),
            CASE WHEN cl.next_dt IS NULL THEN 2 ELSE 1 END,
            h.brd_slno,
            CASE WHEN m.conn_key::text = h.diary_no::text THEN '0001-01-01' ELSE m.fil_dt::TEXT END ASC";
    $query = $this->db->query($sql_t);
    $resultData = $query->getResultArray();
    return $resultData;

}
private function getROPVerificationsql1($mf, $crt, $vstats,$r_status,$tdt1,$ucode,$usertype)
{

if ($mf == 'M') {
    $stg = 1;
} else if ($mf == 'F') {
    $stg = 2;
}

    if($vstats == 0){
        $list_print_flag = "(Verified/Not Verified )";
        $left_join_verify = "LEFT JOIN case_verify_rop tt ON tt.diary_no = h.diary_no AND tt.cl_dt = h.next_dt AND tt.display = 'Y' ";
        $left_join_verify_whr = "  ";
    }
    if($vstats == 2){
        $list_print_flag = "(Not Verified Cases)";
        $left_join_verify = "LEFT JOIN case_verify_rop tt ON tt.diary_no = h.diary_no AND tt.cl_dt = h.next_dt AND tt.display = 'Y' ";
        $left_join_verify_whr = " tt.diary_no IS NULL AND ";
    }
    if($vstats == 1){

        $list_print_flag = "(Verified Cases)";
        $left_join_verify = "LEFT JOIN case_verify_rop tt ON tt.diary_no = h.diary_no AND tt.cl_dt = h.next_dt AND tt.display = 'Y' ";
        $left_join_verify_whr = " tt.diary_no IS NOT NULL AND ";
    }

$sql_ro = $this->getRosterIds($stg, $crt, $tdt1);
$result_data=$sql_ro;

$whereStatus="";
if($r_status=='A'){
    $whereStatus='';
}
else if($r_status=='P'){
    $whereStatus=" and m.c_status='P'";
}
else if($r_status=='D'){
    $whereStatus=" and m.c_status='D'";
}
$get_users = $this->get_users_single($ucode);
  $get_users_allda = $this->get_users_allda($ucode);
  $username_uby = $get_users['name'];
  $checkDaCode="";
  if($ucode==1){
      $checkDaCode="";
  }
  else if($usertype == '14' AND $ucode != 3564 AND $ucode != 722 AND $ucode != 1182 AND $ucode != 184){
      $all_da = $get_users_allda['allda'];
      $checkDaCode="AND (m.dacode=$ucode or find_in_set(m.dacode,$all_da))";
      $mdacode = "";
  }
  else if(($usertype == '17' OR $usertype == '50' OR $usertype == '51') AND ($ucode != 3564 AND $ucode != 722 AND $ucode != 1182 AND $ucode != 184)){
      $mdacode = "";
      $checkDaCode = "AND m.dacode=$ucode";
  }
  else{
      $mdacode = "";
  }
$sql_1 = "SELECT
                tt.cl_dt,
                SUBSTRING(m.diary_no::TEXT FROM 1 FOR LENGTH(m.diary_no::TEXT) - 4) AS case_no,
                SUBSTRING(m.diary_no::TEXT FROM LENGTH(m.diary_no::TEXT) - 3 FOR 4) AS year,
                TO_CHAR(m.fil_dt, 'DD-MM-YYYY HH:MI AM') AS fil_dt_f,
                CASE WHEN m.reg_year_mh = 0 THEN EXTRACT(YEAR FROM m.fil_dt) ELSE m.reg_year_mh END AS m_year,
                m.diary_no,
                m.reg_no_display,
                m.mf_active,
                m.conn_key,
                h.judges,
                h.mainhead,
                h.board_type,
                h.next_dt,
                h.subhead,
                h.clno,
                h.brd_slno,
                h.tentative_cl_dt,
                m.pet_name,
                m.res_name,
                m.pet_adv_id,
                m.res_adv_id,
                m.c_status,
                CASE WHEN cl.next_dt IS NULL THEN 'NA' ELSE h.brd_slno::TEXT END AS brd_prnt,
                h.roster_id,
                TO_CHAR(m.fil_dt_fh, 'DD-MM-YYYY HH:MI AM') AS fil_dt_fh,
                CASE WHEN m.reg_year_fh = 0 THEN EXTRACT(YEAR FROM m.fil_dt_fh) ELSE m.reg_year_fh END AS f_year,
                CASE WHEN m.fil_no != '' THEN SPLIT_PART(m.fil_no, '-', 1) ELSE '' END AS ct1,
                CASE WHEN m.fil_no != '' THEN SPLIT_PART(SPLIT_PART(m.fil_no, '-', 2), '-', 1) ELSE '' END AS crf1,
                CASE WHEN m.fil_no != '' THEN SPLIT_PART(m.fil_no, '-', 3) ELSE '' END AS crl1,
                CASE WHEN m.fil_no_fh != '' THEN SPLIT_PART(m.fil_no_fh, '-', 1) ELSE '' END AS ct2,
                CASE WHEN m.fil_no_fh != '' THEN SPLIT_PART(SPLIT_PART(m.fil_no_fh, '-', 2), '-', 1) ELSE '' END AS crf2,
                CASE WHEN m.fil_no_fh != '' THEN SPLIT_PART(m.fil_no_fh, '-', 3) ELSE '' END AS crl2,
                m.casetype_id,
                m.case_status_id,
                (SELECT STRING_AGG(remarks, ',') FROM case_verify_by_sec_remark WHERE id = ANY(STRING_TO_ARRAY(cvr.remark_id, ',')::INT[])) AS remarks_by_monitoring,
                (SELECT concat(name, '(', empid, ')') FROM master.users WHERE usercode = cvr.ucode) AS verified_by,
                TO_CHAR(cvr.ent_dt, 'DD-MM-YYYY HH:MI AM') AS verified_on
            FROM
                (
                    SELECT
                        t1.diary_no,
                        t1.next_dt,
                        t1.roster_id,
                        t1.judges,
                        t1.mainhead,
                        t1.board_type,
                        t1.subhead,
                        t1.listorder,
                        t1.clno,
                        t1.brd_slno,
                        t1.main_supp_flag,
                        t1.tentative_cl_dt
                    FROM
                        heardt t1
                    WHERE
                        t1.next_dt = '" . $tdt1 . "' AND 
                        t1.mainhead = '".$mf."' 
                        -- AND t1.roster_id = ANY(STRING_TO_ARRAY('".$result_data."', ',')::INT[])
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
                        t2.listorder,
                        t2.clno,
                        t2.brd_slno,
                        t2.main_supp_flag,
                        t2.tentative_cl_dt
                    FROM
                        last_heardt t2
                    WHERE
                        t2.next_dt = '" . $tdt1 . "' AND 
                        t2.mainhead = '".$mf."' 
                        -- AND t2.roster_id = ANY(STRING_TO_ARRAY('".$result_data."', ',')::INT[])
                        AND (t2.main_supp_flag = 1 OR t2.main_supp_flag = 2)
                        AND t2.bench_flag = ''
                ) h
            INNER JOIN
                main m ON h.diary_no = m.diary_no AND h.next_dt = '" . $tdt1 . "' AND h.mainhead = '".$mf."' 
              -- AND h.roster_id = ANY(STRING_TO_ARRAY('".$result_data."', ',')::INT[]) 
              AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
            LEFT JOIN
                case_verify_rop tt ON tt.diary_no = h.diary_no AND tt.cl_dt = h.next_dt AND tt.display = 'Y'
            LEFT JOIN
                cl_printed cl ON cl.next_dt = h.next_dt AND cl.m_f = h.mainhead AND cl.part = h.clno AND cl.main_supp = h.main_supp_flag AND cl.roster_id = h.roster_id AND cl.display = 'Y'
            INNER JOIN case_verify_rop cvr ON m.diary_no = cvr.diary_no AND h.next_dt = cvr.cl_dt
            WHERE 
                $left_join_verify_whr cl.next_dt IS NOT NULL $whereStatus $checkDaCode
            ORDER BY
                h.judges,
                CASE WHEN cl.next_dt IS NULL THEN 2 ELSE 1 END,
                h.brd_slno,
                CASE WHEN m.conn_key::text = h.diary_no::text THEN '0001-01-01' ELSE m.fil_dt::TEXT END ASC
              limit 10";
  $query = $this->db->query($sql_1);
  $resultData = $query->getResultArray();
  return $resultData;

}
private function getRosterIds($stg, $crt, $tdt1)
{
if($crt==''){
$t_cn=" AND CASE
        WHEN mb.board_type_mb = 'R' THEN r.to_date = '0001-01-01'
        ELSE r.from_date = '2025-03-17'
        END ";
}
else if($crt=="101"){
  $t_cn=" AND mb.board_type_mb = 'C' AND r.from_date = '" . $tdt1 . "' "; 
}else if($crt=="102"){
  $t_cn=" AND mb.board_type_mb = 'R' AND r.to_date = '0000-00-00' "; 
}
else{
  $t_cn=" AND r.courtno = '".$crt."' AND if(r.to_date = '0001-01-01', r.from_date = '" . $tdt1 . "', '" . $tdt1 . "' BETWEEN r.from_date AND r.to_date) "; 
}
$sql_ro = "SELECT DISTINCT rj.roster_id, mb.board_type_mb,
          CASE
              WHEN r.courtno = 0 THEN 9999
              ELSE r.courtno
          END AS courtno_order,
          CASE
              WHEN mb.board_type_mb = 'J' THEN 1
              WHEN mb.board_type_mb = 'C' THEN 2
              WHEN mb.board_type_mb = 'CC' THEN 3
              WHEN mb.board_type_mb = 'R' THEN 4
          END AS board_type_order,
          rj.judge_id
      FROM master.roster_judge rj
      JOIN master.roster r ON rj.roster_id = r.id
      JOIN master.roster_bench rb ON rb.id = r.bench_id AND rb.display = 'Y'
      JOIN master.master_bench mb ON mb.id = rb.bench_id AND mb.display = 'Y'
      WHERE cast(r.m_f as TEXT) = '".$stg."'  ".$t_cn."
          AND rj.display = 'Y'
          AND r.display = 'Y'
      ORDER BY
          courtno_order,
          board_type_order,
          rj.judge_id";
$query = $this->db->query($sql_ro);

$result = [];
foreach ($query->getResultArray() as $row) {
    $result[] = $row['roster_id'];
}
return implode(',', $result);
}

public function get_advocates($adv_id)
{
  $return = '';
  $builder = $this->db->table('master.bar');
  $builder->select('name');
  $builder->where('bar_id', $adv_id);
  $query = $builder->get();
  if (count($query->getResultArray()) > 0) {
      $row = $query->getRow();
      $return =  $row->name;
  }
  return $return;
}

public function get_drop_note_print($list_dt, $mainhead, $roster_id)
{
$results = [];
if($list_dt !== ''){
  $builder = $this->db->table('drop_note d');

  $builder->select("d.clno, COALESCE(d.nrs, '-') AS nrs, d.mf, d.diary_no,
      CASE
          WHEN m.active_reg_year IS NULL OR m.active_reg_year = 0 THEN m.diary_no::TEXT
          ELSE CONCAT(c.short_description, '/',
              CASE
                  WHEN TRIM(LEADING '0' FROM (STRING_TO_ARRAY(m.active_fil_no, '-'))[2]) = TRIM(LEADING '0' FROM (STRING_TO_ARRAY(m.active_fil_no, '-'))[3])
                  THEN TRIM(LEADING '0' FROM (STRING_TO_ARRAY(m.active_fil_no, '-'))[2])
                  ELSE CONCAT(TRIM(LEADING '0' FROM (STRING_TO_ARRAY(m.active_fil_no, '-'))[2]), '-',
                              TRIM(LEADING '0' FROM (STRING_TO_ARRAY(m.active_fil_no, '-'))[3]))
              END, '/', m.active_reg_year)
      END AS case_no");

  $builder->join('main m', 'm.diary_no = d.diary_no', 'INNER');
  $builder->join('master.casetype c', 'c.casecode = m.active_casetype_id', 'LEFT');
  $builder->where('d.cl_date', $list_dt);
  $builder->where('d.display', 'Y');
  $builder->where('d.roster_id', $roster_id);
  $builder->where('d.mf', $mainhead);
  $builder->orderBy('d.clno', 'ASC');

  $query = $builder->get();
  // To get the results as an array of objects:
  $results = $query->getResultArray();

  return $results;
}
return $results;


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
          if (count($query->getResultArray()) > 0) {
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

public function get_users($hd_ud)
{
  $builder = $this->db->table('master.users');
  $builder->where('usercode', $hd_ud);
  $builder->where('display', 'Y');
  $query = $builder->get();
  $result = $query->getRowArray();
  return $result;
}
public function get_users_single($hd_ud)
{
  $builder = $this->db->table('master.users');
  $builder->where('usercode', $hd_ud);
  $query = $builder->get();
  $result = $query->getRowArray();
  return $result;
}

public function get_users_allda($hd_ud)
{
$builder = $this->db->table('master.users u');

$builder->select("STRING_AGG(u2.usercode::TEXT, ',') AS allda");

$builder->join('master.users u2', 'u2.section = u.section', 'LEFT');

$builder->where('u.display', 'Y');
$builder->where('u.usercode', '1'); // Or use parameterized query

$builder->groupBy('u2.section');

$query = $builder->get();
  $result = $query->getRowArray();
  return $result;
}


}
