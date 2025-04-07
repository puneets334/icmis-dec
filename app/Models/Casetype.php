<?php

namespace App\Models;

use CodeIgniter\Model;

class Casetype extends Model
{
  protected $table = 'master.casetype';
  protected $primaryKey = 'casecode';
  protected $allowedFields = ['casecode', 'skey', 'casename', 'short_description', 'display'];

  public function getCaseTypes()
  {
    $tbl = is_table_a('casetype');

    $builder = $this->db->table("public." . $tbl);
    $builder->select("partyname, sr_no, sr_no_show");
    $builder->where('casecode !=', '9999');
    $builder->orderBy("short_description");

    $query = $builder->get();

    if ($query->getNumRows() >= 1) {
      return $query->getResultArray();
    } else {
      return [];
    }
  }

  public function getCaseType()
  {
    $builder = $this->db->table('master.casetype');
    $builder->select('casecode, casename');
    $builder->where('display', 'Y');
    $builder->orderBy('casename', 'ASC');
    $query = $builder->get();

    $result = $query->getResultArray();
    // pr($result);

    return $result;
  }


  public function get_diary_details_by_diary_no1($diary_no)
  {
    $builder = $this->db->table('case_info');
    $builder->select('case_info.*, case_info.usercode AS u, to_char(insert_time, \'DD-MM-YYYY HH24:MI\') as insert_time, to_char(deleted_on, \'DD-MM-YYYY HH24:MI\') as deleted_on, l.name as deleted_empname, case_info.display as if_active, concat(users.name, \'[\', users.empid, \']\') as userinfo, main.reg_no_display as caseno');
    $builder->join('master.users', 'case_info.usercode = users.usercode');
    $builder->join('main', 'case_info.diary_no = main.diary_no');
    $builder->join('master.users l', 'case_info.deleted_by = l.usercode', 'left');
    $builder->where('case_info.diary_no', $diary_no);

    $query = $builder->get(1);
    //echo $this->db->getLastQuery();die;

    if ($query->getNumRows() >= 1) {
      return $query->getRowArray();
    } else {
      $builder2 = $this->db->table("main_a");
      $builder2->select("*");
      $builder2->where('diary_no', $diary_no);
      $query2 = $builder2->get(1);

      if ($query2->getNumRows() >= 1) {
        return $query2->getRowArray();
      } else {
        return []; // Return an empty array instead of false
      }
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

  public function getUserByUsercode($usercode)
  {

    $builder = $this->db->table('master.users');
    $builder->select('empid, usertype');
    $builder->where('usercode', (int)$usercode);
    $query = $builder->get();
    $result = $query->getResultArray();
    // pr($result);
    return $result;
  }


  // public function getFilteredUsers($chk_da)
  // {
  //   $query = "SELECT DISTINCT type_name, c.id 
  //                 FROM master.users a 
  //                 JOIN fil_trap_users b ON a.usercode = b.usercode AND b.display = 'Y' 
  //                 JOIN master.usertype c ON c.id = b.usertype AND (c.display = 'Y' OR c.display = 'E') 
  //                 WHERE (section = '19' OR section = '77' OR section = '18') AND a.display = 'Y' $chk_da 
  //                 ORDER BY c.id";

  //   return $this->db->query($query)->getResultArray();
  // }

  public function getFilteredUsers($chk_da)
  {
    $builder = $this->db->table('master.users a');
    $builder->distinct();

    $builder->select('type_name, c.id');

    $builder->join('fil_trap_users b', 'a.usercode = b.usercode AND b.display = \'Y\'');
    $builder->join('master.usertype c', 'c.id = b.usertype AND (c.display = \'Y\' OR c.display = \'E\')');

    $builder->where('a.display', 'Y');
    $builder->whereIn('section', ['19', '77', '18']);

    if (!empty($chk_da)) {
      $builder->where($chk_da);
    }

    $builder->orderBy('c.id');


    return $builder->get()->getResultArray();
  }





  public function monitoring_Error_Report()
  {
      $sql="SELECT DISTINCT t.*, 
            COUNT(t.id) OVER (PARTITION BY t.diary_no, t.remarks) AS count_remarks
      FROM (
        SELECT DISTINCT 
          c.diary_no,
          r.id,
          CONCAT(m.reg_no_display, '@DNo. ', CONCAT(SUBSTRING(c.diary_no::text FROM 1 FOR LENGTH(c.diary_no::text) - 4), '/', RIGHT(c.diary_no::text, 4))) AS caseno,
          CONCAT(u1.empid, '@', u1.name) AS daName,
          us.section_name,
          CONCAT(u.empid, '@', u.name) AS RmrkBy,
          r.remarks,
          c.ent_dt
        FROM case_verify c
        LEFT JOIN master.users u 
          ON u.usercode = c.ucode 
          AND (u.display = 'Y' OR u.display IS NULL)
        INNER JOIN master.case_verify_by_sec_remark r 
          ON r.id::text = ANY(string_to_array(c.remark_id, ','))
        INNER JOIN main m 
          ON m.diary_no = c.diary_no
        LEFT JOIN master.users u1 
          ON u1.usercode = m.dacode 
          AND (u1.display = 'Y' OR u1.display IS NULL)
        LEFT JOIN master.usersection us 
          ON us.id = u1.section 
          AND us.display = 'Y'
        WHERE m.c_status = 'P'
          AND r.id NOT IN (1, 10)
      ) t
      ORDER BY t.diary_no
      LIMIT 2500";  // remove vkg limit

  
    $query = $this->db->query($sql);
    if ($query->getNumRows() >= 1) {
      return $query->getResultArray();
    } else {
      return false;
    }
  }

  public function monitoring_Error_details($diary_no, $id)
  {
    $builder = $this->db->table('case_verify c');
    $builder->select("TO_CHAR(c.ent_dt, 'DD/MM/YYYY HH24:MI:SS') as ent_dt");
    $builder->join('master.users u', 'u.usercode = c.ucode AND (u.display = \'Y\' OR u.display IS NULL)', 'left');
    $builder->join('master.case_verify_by_sec_remark r', "POSITION(',' || r.id::text || ',' IN ',' || c.remark_id || ',') > 0", 'inner');
    $builder->join('main m', 'm.diary_no = c.diary_no', 'inner');
    $builder->join('master.users u1', 'u1.usercode = m.dacode AND (u1.display = \'Y\' OR u1.display IS NULL)', 'left');
    $builder->join('master.usersection us', 'us.id = u1.section AND us.display = \'Y\'', 'left');

    // Adding where conditions
    $builder->where('m.c_status', 'P');
    $builder->whereNotIn('r.id', [1, 10]);
    $builder->where('c.diary_no', $diary_no);
    $builder->where('r.id', $id);

    $builder->orderBy('c.ent_dt', 'desc');
    $query = $builder->get();
    if ($query->getNumRows() >= 1) {
      return $query->getResultArray();
    } else {
      return false;
    }
  }



  public function get_filling($frm_dt, $to_dt, $ddl_users, $chk_da, $chk_users)
  {
   

    $remarks = '';
    $remarks1 = '';
    $remarks_1 = '';
    $remark_rf = '';

    if ($ddl_users == '102') {
      $remarks = " and remarks='FIL -> DE'";
      $remarks_1 = "  remarks='FIL -> DE'";
    } elseif ($ddl_users == '103') {
      $remarks_1 = "  remarks='DE -> SCR'";
      $remarks = " and remarks='DE -> SCR'";
      $remarks1 = " AND (remarks = 'AOR -> SCR' or remarks = 'FDR -> SCR')";
      $remark_rf = "  (remarks = 'AOR -> SCR' or remarks = 'FDR -> SCR')";
    }
    if ($ddl_users == '105') {
      $remarks = " and (remarks='SCR -> CAT' or remarks = 'AUTO -> CAT')";
      $remarks_1 = "  (remarks='SCR -> CAT' or remarks = 'AUTO -> CAT')";
    }
    if ($ddl_users == '106') {
      $remarks = " and remarks='CAT -> TAG'";
      $remarks_1 = "  remarks='CAT -> TAG'";
    }

    if ($ddl_users == '9796') {
      $remarks = " and (remarks='TAG -> SCN' or remarks='CAT -> SCN')";
      $remarks_1 = "  (remarks='TAG -> SCN' or remarks='CAT -> SCN')";
    }
    if ($ddl_users == '107') {
      $remarks = " and remarks='SCN -> IB-Ex'";
      $remarks_1 = "  remarks='SCN -> IB-Ex'";
    }
    if ($ddl_users == '108') {
      $remarks_1 = "  remarks='SCR -> FDR'";
      $remarks = " and remarks='SCR -> FDR'";
      $remarks1 = " AND remarks='AOR -> FDR'";
      $remark_rf = "  remarks='AOR -> FDR'";
    }

    if ($ddl_users == '101') {
      $sql = "Select  u.empid d_to_empid ,COUNT(diary_no) ss from  master.users u 
        JOIN fil_trap_users t_u 
          ON u.usercode = t_u.usercode
        left join main c on c.diary_user_id=u.usercode  and date(diary_no_rec_date) 
        between '$frm_dt' AND '$to_dt'
        WHERE t_u.usertype = '$ddl_users' $chk_users
        AND t_u.display = 'Y'  and u.display='Y' $chk_users GROUP BY u.empid ";
    } elseif ($ddl_users == '103' || $ddl_users == '108') {
      $ck_comp_fil_x = '';
      $ck_comp_fil_w = '';
      $ck_comp_refil_x = '';
      $ck_comp_refil_w = '';

      if ($ddl_users == '103')
      {

        $ck_comp_fil_x = " (xx.remarks = 'SCR -> AOR' or xx.remarks = 'AUTO -> CAT' or xx.remarks = 'SCR -> CAT' OR xx.remarks = 'SCR -> REF' OR xx.remarks = 'SCR -> FDR') ";
        $ck_comp_fil_w = " (ww.remarks = 'SCR -> AOR' or ww.remarks = 'AUTO -> CAT' or ww.remarks = 'SCR -> CAT' OR ww.remarks = 'SCR -> REF' OR ww.remarks = 'SCR -> FDR') ";
        $ck_comp_refil_x = " (xx.remarks = 'SCR -> AOR' or xx.remarks = 'AUTO -> CAT' or xx.remarks = 'SCR -> CAT' or xx.remarks = 'SCR -> FDR') ";
        $ck_comp_refil_w = " (ww.remarks = 'SCR -> AOR' or ww.remarks = 'AUTO -> CAT' or ww.remarks = 'SCR -> CAT' or ww.remarks = 'SCR -> FDR') ";

        $sql = "SELECT  SUM(s) s,
                          SUM(ss) ss,
                          SUM(sss) sss,SUM(r_s) r_s,SUM(r_ss) r_ss,SUM(r_sss) r_sss, d_to_empid,SUM(ssss) ssss,SUM(r_ssss) r_ssss,SUM(sssss) sssss,SUM(r_sssss) r_sssss
                          FROM  (SELECT 
                          SUM(s) s,
                          SUM(ss) ss,
                          SUM(sss) sss,NULL r_s,NULL r_ss,NULL r_sss,NULL r_ssss,SUM(ssss) ssss,SUM(sssss) sssss,NULL r_sssss,
                          u.empid d_to_empid ,u.usercode
                        FROM
                          master.users u 
                          JOIN fil_trap_users t_u 
                            ON u.usercode = t_u.usercode 
                          LEFT JOIN 
                            (SELECT 
                              SUM(s) AS s,
                                NULL::bigint AS ss,
                                NULL::bigint AS sss,
                                NULL::bigint AS ssss,
                                NULL::bigint AS sssss,
                                d_to_empid 
                            FROM
                              (SELECT 
                                COUNT(uid) s,
                                d_to_empid 
                              FROM
                                fil_trap 
                              WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
                                AND '$to_dt' 
                                $remarks $chk_da
                              GROUP BY d_to_empid 
                              UNION
                              ALL 
                              SELECT 
                                COUNT(uid) s,
                                d_to_empid 
                              FROM
                                fil_trap_his 
                              WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
                                AND '$to_dt' 
                                $remarks $chk_da
                              GROUP BY d_to_empid) aa 
                            GROUP BY d_to_empid 
                            UNION
                            ALL 
                            SELECT 
                              NULL::bigint AS s,
                                COUNT(d_to_empid) AS ss,
                                NULL::bigint AS sss,
                                NULL::bigint AS ssss,
                                NULL::bigint AS sssss,
                                d_to_empid 
                            FROM
                              (SELECT 
                              distinct zz.*,
                                xx.diary_no d_no,
                                ww.diary_no d_no2,
                                mn.fil_no 
                              FROM
                                (SELECT 
                                  diary_no,
                                  d_to_empid,
                                  r_by_empid 
                                FROM
                                  fil_trap 
                                WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
                                  AND '$to_dt' 
                                  $remarks $chk_da
                                  
                                UNION
                                ALL 
                                SELECT 
                                  diary_no,
                                  d_to_empid,
                                  r_by_empid 
                                FROM
                                  fil_trap_his 
                                WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
                                  AND '$to_dt' 
                                  $remarks $chk_da
                                
                                  ) zz 
                                LEFT JOIN fil_trap xx 
                                  ON xx.diary_no = zz.diary_no 
                                  AND $ck_comp_fil_x
                                LEFT JOIN fil_trap_his ww 
                                  ON ww.diary_no = zz.diary_no 
                                  AND $ck_comp_fil_w
                                LEFT JOIN main mn 
                                  ON mn.diary_no = zz.diary_no 
                              WHERE (
                                  xx.diary_no IS NOT NULL 
                                  OR ww.diary_no IS NOT NULL 
                                  OR mn.fil_no IS NOT NULL
                                )) aa 
                            GROUP BY d_to_empid 
                            UNION
                            ALL 
                          SELECT 
                                NULL::bigint AS s,
                                NULL::bigint AS ss,
                                COUNT(d_to_empid) AS sss,
                                NULL::bigint AS ssss,
                                NULL::bigint AS sssss,
                                d_to_empid 
                              FROM
                                (SELECT DISTINCT 
                                  zz.*,
                                  xx.diary_no d_no,
                                  ww.diary_no d_no2,
                                  mn.fil_no
                                
                                FROM
                                  (SELECT 
                                    diary_no,
                                    d_to_empid,
                                    r_by_empid 
                                  FROM
                                    fil_trap 
                                  WHERE  $remarks_1 $chk_da and disp_dt>='2018-06-30'
                                  
                                  UNION
                                  ALL 
                                  SELECT 
                                    diary_no,
                                    d_to_empid,
                                    r_by_empid 
                                  FROM
                                    fil_trap_his 
                                  WHERE  $remarks_1 $chk_da and disp_dt>='2018-06-30'
                                  ) zz 
                                  LEFT JOIN fil_trap xx 
                                    ON xx.diary_no = zz.diary_no 
                                    AND $ck_comp_fil_x
                                  LEFT JOIN fil_trap_his ww 
                                    ON ww.diary_no = zz.diary_no 
                                    AND $ck_comp_fil_w
                                  LEFT JOIN main mn 
                                    ON mn.diary_no = zz.diary_no 
                                WHERE (
                                    xx.diary_no IS  NULL 
                                    AND ww.diary_no IS  NULL 
                                    AND (mn.fil_no IS  NULL or mn.fil_no='')
                                  )) aa 
                              GROUP BY d_to_empid 
                              UNION ALL
                            SELECT 
                              NULL::bigint AS s,
                              NULL::bigint AS ss,
                            NULL::bigint AS sss, 
                            COUNT(r_by_empid) AS ssss,
                            NULL::bigint AS sssss,
                            r_by_empid d_to_empid
                            FROM
                              (SELECT 
                              distinct zz.*,
                                xx.diary_no d_no,
                                ww.diary_no d_no2,
                                mn.fil_no 
                              FROM
                                (SELECT 
                                  diary_no,
                                  d_to_empid,
                                  r_by_empid 
                                FROM
                                  fil_trap 
                                WHERE DATE(comp_dt) BETWEEN '$frm_dt' 
                                  AND '$to_dt' 
                                  $remarks $chk_da
                                  
                                UNION
                                ALL 
                                SELECT 
                                  diary_no,
                                  d_to_empid,
                                  r_by_empid 
                                FROM
                                  fil_trap_his 
                                WHERE DATE(comp_dt) BETWEEN '$frm_dt' 
                                  AND '$to_dt' 
                                  $remarks $chk_da
                                
                                  ) zz 
                                LEFT JOIN fil_trap xx 
                                  ON xx.diary_no = zz.diary_no 
                                  AND $ck_comp_fil_x
                                LEFT JOIN fil_trap_his ww 
                                  ON ww.diary_no = zz.diary_no 
                                  AND $ck_comp_fil_w
                                LEFT JOIN main mn 
                                  ON mn.diary_no = zz.diary_no 
                              WHERE (
                                  xx.diary_no IS NOT NULL 
                                  OR ww.diary_no IS NOT NULL 
                                  OR mn.fil_no IS NOT NULL
                                )) aa 
                            GROUP BY r_by_empid
                            
                        union all
                        SELECT 
                              NULL::bigint AS s,
                              NULL::bigint AS ss,
                              NULL::bigint AS sss,
                              NULL::bigint AS ssss, 
                              COUNT(r_by_empid) AS sssss,
                              r_by_empid  d_to_empid
                            FROM
                              
                                (SELECT 
                                  diary_no,
                                  d_to_empid,
                                  r_by_empid 
                                FROM
                                  fil_trap 
                                WHERE DATE(rece_dt) BETWEEN '$frm_dt' 
                                  AND '$to_dt' 
                                  $remarks $chk_da
                                  
                                UNION
                                ALL 
                                SELECT 
                                  diary_no,
                                  d_to_empid,
                                  r_by_empid 
                                FROM
                                  fil_trap_his 
                                WHERE DATE(rece_dt) BETWEEN '$frm_dt' 
                                  AND '$to_dt' 
                                  $remarks $chk_da
                                AND r_by_empid != 0
                                  ) zz 
                                
                            GROUP BY r_by_empid
                            ) bb 
                            ON u.empid = bb.d_to_empid 
                        WHERE t_u.usertype = '$ddl_users' 
                          AND t_u.display = 'Y'  and u.display='Y' $chk_users
                        GROUP BY u.usercode 

                        UNION ALL

                        SELECT 
                        NULL::bigint AS s,
                        NULL::bigint AS ss,
                        NULL::bigint AS sss,
                          SUM(s) r_s,
                          SUM(ss) r_ss,
                          SUM(sss) r_sss, SUM(ssss) r_ssss, NULL::bigint ssss,NULL sssss, SUM(sssss) r_sssss,
                          u.empid d_to_empid ,u.usercode
                        FROM
                          master.users u 
                          JOIN fil_trap_users t_u 
                            ON u.usercode = t_u.usercode 
                          LEFT JOIN 
                            (SELECT 
                              SUM(s) s,
                              NULL::bigint AS ss,
                              NULL::bigint AS sss,
                              NULL::bigint AS ssss,
                              NULL::bigint AS sssss,
                              d_to_empid 
                            FROM
                              (SELECT 
                                COUNT(uid) s,
                                d_to_empid 
                              FROM
                                fil_trap 
                              WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
                                AND '$to_dt' 
                              $remarks1 $chk_da
                              GROUP BY d_to_empid 
                              UNION
                              ALL 
                              SELECT 
                                COUNT(uid) s,
                                d_to_empid 
                              FROM
                                fil_trap_his 
                              WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
                                AND '$to_dt' 
                              $remarks1 $chk_da
                              GROUP BY d_to_empid) aa 
                            GROUP BY d_to_empid 
                            UNION
                            ALL 
                            SELECT 
                            NULL::bigint AS s,
                                NULL::bigint AS ss,
                                COUNT(d_to_empid) AS sss,
                                NULL::bigint AS ssss,
                                NULL::bigint AS sssss,
                                d_to_empid
                            FROM
                              (SELECT 
                              distinct zz.*,
                                xx.diary_no d_no,
                                ww.diary_no d_no2,
                                mn.fil_no ,
                              (ARRAY_AGG(xx.rece_dt ORDER BY xx.rece_dt))[1] AS rece_dt,
                              (ARRAY_AGG(ww.rece_dt ORDER BY ww.rece_dt))[1] AS yy
                              FROM
                                (SELECT 
                                  diary_no,
                                  d_to_empid,
                                  r_by_empid ,disp_dt
                                FROM
                                  fil_trap 
                                WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
                                  AND '$to_dt' 
                                $remarks1  $chk_da
                                  AND r_by_empid != 0 
                                UNION
                                ALL 
                                SELECT 
                                  diary_no,
                                  d_to_empid,
                                  r_by_empid ,disp_dt
                                FROM
                                  fil_trap_his 
                                WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
                                  AND '$to_dt' 
                                $remarks1 $chk_da
                                  AND r_by_empid != 0
                                  ) zz 
                                LEFT JOIN fil_trap xx 
                                  ON xx.diary_no = zz.diary_no 
                                  AND $ck_comp_refil_x   AND xx.disp_dt>=zz.disp_dt
                                LEFT JOIN fil_trap_his ww 
                                  ON ww.diary_no = zz.diary_no 
                                  AND $ck_comp_refil_w AND ww.disp_dt>=zz.disp_dt
                                LEFT JOIN main mn 
                                  ON mn.diary_no = zz.diary_no 
                              WHERE (
                                  xx.diary_no IS NOT NULL 
                                  OR ww.diary_no IS NOT NULL 
                                  OR mn.fil_no IS NOT NULL
                                )  GROUP BY zz.diary_no,zz.d_to_empid,zz.disp_dt,zz.d_to_empid,xx.diary_no,ww.diary_no,mn.fil_no,zz.r_by_empid) aa 
                            GROUP BY d_to_empid 
                            UNION
                            ALL 
                            SELECT 
                                NULL::bigint AS s,
                                NULL::bigint AS ss,
                                COUNT(d_to_empid) AS sss,
                                NULL::bigint AS ssss,
                                NULL::bigint AS sssss,
                                d_to_empid
                              FROM
                                (SELECT DISTINCT 
                                  zz.*,
                                  xx.diary_no d_no,
                                  ww.diary_no d_no2,
                                  mn.fil_no,
                                  (ARRAY_AGG(xx.rece_dt ORDER BY xx.rece_dt))[1] AS rece_dt,
                                  (ARRAY_AGG(ww.rece_dt ORDER BY ww.rece_dt))[1] AS yy
                                FROM
                                  (SELECT 
                                    diary_no,
                                    d_to_empid,
                                    r_by_empid ,disp_dt
                                  FROM
                                    fil_trap 
                                  WHERE   $remark_rf $chk_da and disp_dt>='2018-06-30'
                                  
                                  UNION ALL 
                                  SELECT 
                                    diary_no,
                                    d_to_empid,
                                    r_by_empid ,disp_dt
                                  FROM
                                    fil_trap_his 
                                  WHERE  $remark_rf $chk_da and disp_dt>='2018-06-30'
                                  ) zz 
                                  LEFT JOIN fil_trap xx 
                                    ON xx.diary_no = zz.diary_no 
                                    AND $ck_comp_refil_x   AND xx.disp_dt>=zz.disp_dt
                                  LEFT JOIN fil_trap_his ww 
                                    ON ww.diary_no = zz.diary_no 
                                    AND  $ck_comp_refil_w AND ww.disp_dt>=zz.disp_dt
                                  LEFT JOIN main mn 
                                    ON mn.diary_no = zz.diary_no 
                                WHERE (
                                    xx.diary_no IS  NULL 
                                    AND ww.diary_no IS  NULL 
                                    AND (mn.fil_no IS  NULL or mn.fil_no='')
                                  ) GROUP BY zz.diary_no,zz.d_to_empid,zz.disp_dt,zz.d_to_empid,xx.diary_no,ww.diary_no,mn.fil_no,zz.r_by_empid ) aa 
                              GROUP BY d_to_empid 
                              union all
                            SELECT 
                              NULL::bigint AS s,
                              NULL::bigint AS ss,
                              NULL::bigint AS sss,
                              COUNT(r_by_empid) AS ssss,
                              NULL::bigint AS sssss,
                              r_by_empid d_to_empid
                            FROM
                              (SELECT 
                              distinct zz.*,
                                xx.diary_no d_no,
                                ww.diary_no d_no2,
                                mn.fil_no ,
                              (ARRAY_AGG(xx.rece_dt ORDER BY xx.rece_dt))[1] AS rece_dt,
                              (ARRAY_AGG(ww.rece_dt ORDER BY ww.rece_dt))[1] AS yy
                              FROM
                                (SELECT 
                                  diary_no,
                                  d_to_empid,
                                  r_by_empid ,disp_dt
                                FROM
                                  fil_trap 
                                WHERE DATE(comp_dt) BETWEEN '$frm_dt' 
                                  AND '$to_dt' 
                                $remarks1  $chk_da
                                  AND r_by_empid != 0 
                                UNION
                                ALL 
                                SELECT 
                                  diary_no,
                                  d_to_empid,
                                  r_by_empid ,disp_dt
                                FROM
                                  fil_trap_his 
                                WHERE DATE(comp_dt) BETWEEN '$frm_dt' 
                                  AND '$to_dt' 
                                $remarks1 $chk_da
                                  AND r_by_empid != 0
                                  ) zz 
                                LEFT JOIN fil_trap xx 
                                  ON xx.diary_no = zz.diary_no 
                                  AND $ck_comp_refil_x   AND xx.disp_dt>=zz.disp_dt
                                LEFT JOIN fil_trap_his ww 
                                  ON ww.diary_no = zz.diary_no 
                                  AND $ck_comp_refil_w AND ww.disp_dt>=zz.disp_dt
                                LEFT JOIN main mn 
                                  ON mn.diary_no = zz.diary_no 
                              WHERE (
                                  xx.diary_no IS NOT NULL 
                                  OR ww.diary_no IS NOT NULL 
                                  OR mn.fil_no IS NOT NULL
                                )  GROUP BY zz.diary_no,zz.r_by_empid,zz.disp_dt,zz.d_to_empid,xx.diary_no,ww.diary_no,mn.fil_no) aa 
                            GROUP BY r_by_empid  
                            
                          UNION ALL

                            SELECT 
                              NULL::bigint AS s,
                                NULL::bigint AS ss,
                                NULL::bigint AS sss,
                                NULL::bigint AS ssss,
                                COUNT(r_by_empid) AS sssss,
                                r_by_empid AS d_to_empid
                            FROM
                              (SELECT 
                              distinct diary_no,
                                  d_to_empid,
                                  r_by_empid ,disp_dt
                                FROM
                                  fil_trap 
                                WHERE DATE(rece_dt) BETWEEN '$frm_dt' 
                                  AND '$to_dt' 
                                $remarks1  $chk_da
                                  AND r_by_empid != 0 
                                UNION
                                ALL 
                                SELECT 
                                  diary_no,
                                  d_to_empid,
                                  r_by_empid ,disp_dt
                                FROM
                                  fil_trap_his 
                                WHERE DATE(rece_dt) BETWEEN '$frm_dt' 
                                  AND '$to_dt' 
                                $remarks1 $chk_da
                                  AND r_by_empid != 0
                                  ) zz 
                            GROUP BY r_by_empid  
                            ) bb 
                            ON u.empid = bb.d_to_empid 
                        WHERE t_u.usertype = '$ddl_users' 
                          AND t_u.display = 'Y' and u.display='Y' $chk_users
                        GROUP BY u.usercode ) tt  GROUP BY tt.usercode,tt.d_to_empid
                        ORDER BY s DESC";
        //pr($sql);
      }
      else if ($ddl_users == '108')
      {
        $ck_comp_fil_x = " xx.remarks = 'FDR -> AOR'  ";
        $ck_comp_fil_w = " ww.remarks = 'FDR -> AOR' ";
        $ck_comp_refil_x = " xx.remarks = 'FDR -> SCR'  ";
        $ck_comp_refil_w = " ww.remarks = 'FDR -> SCR'  ";
        $sql = "SELECT  SUM(s) s,
                      SUM(ss) ss,
                      SUM(sss) sss,
                      SUM(r_s) r_s,
                      SUM(r_ss) r_ss,
                      SUM(r_sss) r_sss, 
                      r_by_empid d_to_empid,
                      SUM(ssss) ssss,
                      SUM(sssss) sssss,
                      sum(r_ssss) r_ssss, 
                      sum(r_sssss) r_sssss 
                      FROM  (SELECT 
                      SUM(s) s,
                      SUM(ss) ss,
                      SUM(sss) sss,NULL r_s,NULL r_ss,NULL r_sss, NULL r_ssss, NULL r_sssss,SUM(ssss) ssss,SUM(sssss) sssss,
                      u.empid r_by_empid ,u.usercode
                        FROM
                          master.users u 
                          JOIN fil_trap_users t_u 
                            ON u.usercode = t_u.usercode 
                          LEFT JOIN 
                            (SELECT 
                              SUM(s) s,
                              NULL::bigint AS ss,
                              NULL::bigint AS sss,
                              NULL::bigint AS ssss,
                              NULL::bigint AS sssss,
                              r_by_empid 
                            FROM
                              (SELECT 
                                COUNT(uid) s,
                              r_by_empid  
                              FROM
                                fil_trap 
                              WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
                                AND '$to_dt' 
                                $remarks $chk_da
                              GROUP BY r_by_empid 
                              UNION
                              ALL 
                              SELECT 
                                COUNT(uid) s,
                              r_by_empid   
                              FROM
                                fil_trap_his 
                              WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
                                AND '$to_dt' 
                                $remarks $chk_da
                              GROUP BY r_by_empid) aa 
                            GROUP BY r_by_empid 
                            UNION
                            ALL 
                            SELECT 
                              NULL::bigint AS s,
                              COUNT(r_by_empid) AS ss,
                              NULL::bigint AS sss, 
                              NULL::bigint AS ssss,
                              NULL::bigint AS sssss,
                              r_by_empid   
                            FROM
                              (SELECT 
                              distinct zz.*,
                                xx.diary_no d_no,
                                ww.diary_no d_no2,
                                mn.fil_no 
                              FROM
                                (SELECT 
                                  diary_no,
                                  d_to_empid,
                                  r_by_empid ,disp_dt
                                FROM
                                  fil_trap 
                                WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
                                  AND '$to_dt' 
                                  $remarks $chk_da
                                  
                                UNION
                                ALL 
                                SELECT 
                                  diary_no,
                                  d_to_empid,
                                  r_by_empid ,disp_dt
                                FROM
                                  fil_trap_his 
                                WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
                                  AND '$to_dt' 
                                  $remarks $chk_da
                                
                                  ) zz 
                                LEFT JOIN fil_trap xx 
                                  ON xx.diary_no = zz.diary_no 
                                  AND $ck_comp_fil_x
                                LEFT JOIN fil_trap_his ww 
                                  ON ww.diary_no = zz.diary_no 
                                  AND $ck_comp_fil_w
                                LEFT JOIN main mn 
                                  ON mn.diary_no = zz.diary_no 
                              WHERE (
                                  xx.diary_no IS NOT NULL 
                                  OR ww.diary_no IS NOT NULL 
                                  OR mn.fil_no IS NOT NULL
                                )) aa 
                            GROUP BY r_by_empid 
                            UNION
                            ALL 
                          SELECT 
                                NULL::bigint AS s,
                                NULL::bigint AS ss, 
                                COUNT(r_by_empid) AS sss, 
                                NULL::bigint AS ssss,
                                NULL::bigint AS sssss,
                                r_by_empid 
                              FROM
                                (SELECT DISTINCT 
                                  zz.*,
                                  xx.diary_no d_no,
                                  ww.diary_no d_no2,
                                  mn.fil_no
                                
                                FROM
                                  (SELECT 
                                    diary_no,
                                    d_to_empid,
                                    r_by_empid 
                                  FROM
                                    fil_trap 
                                  WHERE  $remarks_1 $chk_da and disp_dt>='2018-06-30'
                                  
                                  UNION
                                  ALL 
                                  SELECT 
                                    diary_no,
                                    d_to_empid,
                                    r_by_empid 
                                  FROM
                                    fil_trap_his 
                                  WHERE  $remarks_1 $chk_da and disp_dt>='2018-06-30'
                                  ) zz 
                                  LEFT JOIN fil_trap xx 
                                    ON xx.diary_no = zz.diary_no 
                                    AND $ck_comp_fil_x
                                  LEFT JOIN fil_trap_his ww 
                                    ON ww.diary_no = zz.diary_no 
                                    AND $ck_comp_fil_w
                                  LEFT JOIN main mn 
                                    ON mn.diary_no = zz.diary_no 
                                WHERE (
                                    xx.diary_no IS  NULL 
                                    AND ww.diary_no IS  NULL 
                                    AND (mn.fil_no IS  NULL or mn.fil_no='')
                                  )) aa 
                              GROUP BY r_by_empid

                                UNION ALL
                                SELECT 
                                      NULL::bigint AS s, 
                                      NULL::bigint AS ss,
                                      NULL::bigint AS sss,
                                      COUNT(r_by_empid) AS ssss, 
                                      NULL::bigint AS sssss,
                                      r_by_empid   
                                    FROM
                                      (SELECT 
                                      distinct zz.*,
                                        xx.diary_no d_no,
                                        ww.diary_no d_no2,
                                        mn.fil_no 
                                      FROM
                                        (SELECT 
                                          diary_no,
                                          d_to_empid,
                                          r_by_empid ,disp_dt
                                        FROM
                                          fil_trap 
                                        WHERE DATE(comp_dt) BETWEEN '$frm_dt' 
                                          AND '$to_dt' 
                                          $remarks $chk_da
                                          
                                        UNION
                                        ALL 
                                        SELECT 
                                          diary_no,
                                          d_to_empid,
                                          r_by_empid ,disp_dt
                                        FROM
                                          fil_trap_his 
                                        WHERE DATE(comp_dt) BETWEEN '$frm_dt' 
                                          AND '$to_dt' 
                                          $remarks $chk_da
                                        
                                          ) zz 
                                        LEFT JOIN fil_trap xx 
                                          ON xx.diary_no = zz.diary_no 
                                          AND $ck_comp_fil_x
                                        LEFT JOIN fil_trap_his ww 
                                          ON ww.diary_no = zz.diary_no 
                                          AND $ck_comp_fil_w
                                        LEFT JOIN main mn 
                                          ON mn.diary_no = zz.diary_no 
                                      WHERE (
                                          xx.diary_no IS NOT NULL 
                                          OR ww.diary_no IS NOT NULL 
                                          OR mn.fil_no IS NOT NULL
                                        )) aa 
                                    GROUP BY r_by_empid 
                                    
                                UNION ALL

                                SELECT 
                                      NULL::bigint AS s, 
                                      NULL::bigint AS ss,
                                      NULL::bigint AS sss, 
                                      NULL::bigint AS sssss,
                                      COUNT(r_by_empid) AS sssss,
                                    r_by_empid   
                                    FROM
                                      (SELECT 
                                        distinct  diary_no,
                                          d_to_empid,
                                          r_by_empid ,disp_dt
                                        FROM
                                          fil_trap 
                                        WHERE DATE(rece_dt) BETWEEN '$frm_dt' 
                                          AND '$to_dt' 
                                          $remarks $chk_da
                                          
                                        UNION
                                        ALL 
                                        SELECT 
                                          diary_no,
                                          d_to_empid,
                                          r_by_empid ,disp_dt
                                        FROM
                                          fil_trap_his 
                                        WHERE DATE(rece_dt) BETWEEN '$frm_dt' 
                                          AND '$to_dt' 
                                          $remarks $chk_da
                                        
                                          ) zz 
                                        
                                    GROUP BY r_by_empid ) bb 
                                    ON u.empid = bb.r_by_empid 
                                    WHERE t_u.usertype = '$ddl_users' 
                                    AND t_u.display = 'Y'  and u.display='Y' $chk_users
                                    GROUP BY u.usercode 


                              UNION ALL

                              SELECT NULL::bigint AS s,NULL::bigint AS ss,NULL::bigint AS sss,
                                SUM(s) r_s,
                                SUM(ss) r_ss,
                                SUM(sss) r_sss, SUM(ssss) r_ssss, SUM(sssss) r_sssss, NULL::bigint ssss,NULL sssss,
                                u.empid r_by_empid ,u.usercode
                              FROM
                                master.users u 
                                JOIN fil_trap_users t_u 
                                  ON u.usercode = t_u.usercode 
                                LEFT JOIN 
                                  (SELECT 
                                    SUM(s) s,
                                    NULL::bigint AS ss,
                                    NULL::bigint AS sss, 
                                    NULL::bigint AS ssss,
                                    NULL::bigint AS sssss,
                                    r_by_empid 
                                  FROM
                                    (SELECT 
                                      COUNT(uid) s,
                                      r_by_empid 
                                    FROM
                                      fil_trap 
                                    WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
                                      AND '$to_dt' 
                                    $remarks1 $chk_da
                                    GROUP BY r_by_empid 
                                    UNION
                                    ALL 
                                    SELECT 
                                      COUNT(uid) s,
                                      r_by_empid 
                                    FROM
                                      fil_trap_his 
                                    WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
                                      AND '$to_dt' 
                                    $remarks1 $chk_da
                                    GROUP BY r_by_empid) aa 
                                  GROUP BY r_by_empid 
                                  UNION
                                  ALL 
                                  SELECT 
                                    NULL::bigint AS s,
                                    COUNT(r_by_empid) AS ss,
                                    NULL::bigint AS sss, 
                                    NULL::bigint AS ssss,
                                    NULL::bigint AS sssss,
                                    r_by_empid 
                                  FROM
                                    (SELECT 
                                    distinct zz.*,
                                      xx.diary_no d_no,
                                      ww.diary_no d_no2,
                                      mn.fil_no ,
                                    (ARRAY_AGG(xx.rece_dt ORDER BY xx.rece_dt))[1] AS rece_dt,
                                    (ARRAY_AGG(ww.rece_dt ORDER BY ww.rece_dt))[1] AS yy
                                    FROM
                                      (SELECT 
                                        diary_no,
                                        d_to_empid,
                                        r_by_empid ,disp_dt
                                      FROM
                                        fil_trap 
                                      WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
                                        AND '$to_dt' 
                                      $remarks1  $chk_da
                                        AND r_by_empid != 0 
                                      UNION
                                      ALL 
                                      SELECT 
                                        diary_no,
                                        d_to_empid,
                                        r_by_empid ,disp_dt
                                      FROM
                                        fil_trap_his 
                                      WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
                                        AND '$to_dt' 
                                      $remarks1 $chk_da
                                        AND r_by_empid != 0
                                        ) zz 
                                      LEFT JOIN fil_trap xx 
                                        ON xx.diary_no = zz.diary_no 
                                        AND $ck_comp_refil_x   AND xx.disp_dt>=zz.disp_dt
                                      LEFT JOIN fil_trap_his ww 
                                        ON ww.diary_no = zz.diary_no 
                                        AND $ck_comp_refil_w AND ww.disp_dt>=zz.disp_dt
                                      LEFT JOIN main mn 
                                        ON mn.diary_no = zz.diary_no 
                                    WHERE (
                                        xx.diary_no IS NOT NULL 
                                        OR ww.diary_no IS NOT NULL 
                                        OR mn.fil_no IS NOT NULL
                                      )  GROUP BY zz.diary_no,zz.r_by_empid,zz.disp_dt,zz.d_to_empid,xx.diary_no,ww.diary_no,mn.fil_no) aa 
                                  GROUP BY r_by_empid 
                                  UNION
                                  ALL 
                                  SELECT 
                                      NULL::bigint AS s,
                                      NULL::bigint AS ss,
                                      COUNT(r_by_empid) AS sss, 
                                      NULL::bigint AS ssss,
                                      NULL::bigint AS sssss,
                                      r_by_empid 
                                    FROM
                                      (SELECT DISTINCT 
                                        zz.*,
                                        xx.diary_no d_no,
                                        ww.diary_no d_no2,
                                        mn.fil_no,
                                        (ARRAY_AGG(xx.rece_dt ORDER BY xx.rece_dt))[1] AS rece_dt,
                                        (ARRAY_AGG(ww.rece_dt ORDER BY ww.rece_dt))[1] AS yy
                                      FROM
                                        (SELECT 
                                          diary_no,
                                          d_to_empid,
                                          r_by_empid ,disp_dt
                                        FROM
                                          fil_trap 
                                        WHERE   $remark_rf $chk_da and disp_dt>='2018-06-30'
                                        
                                        UNION ALL 
                                        SELECT 
                                          diary_no,
                                          d_to_empid,
                                          r_by_empid ,disp_dt
                                        FROM
                                          fil_trap_his 
                                        WHERE  $remark_rf $chk_da and disp_dt>='2018-06-30'
                                        ) zz 
                                        LEFT JOIN fil_trap xx 
                                          ON xx.diary_no = zz.diary_no 
                                          AND $ck_comp_refil_x   AND xx.disp_dt>=zz.disp_dt
                                        LEFT JOIN fil_trap_his ww 
                                          ON ww.diary_no = zz.diary_no 
                                          AND  $ck_comp_refil_w AND ww.disp_dt>=zz.disp_dt
                                        LEFT JOIN main mn 
                                          ON mn.diary_no = zz.diary_no 
                                      WHERE (
                                          xx.diary_no IS  NULL 
                                          AND ww.diary_no IS  NULL 
                                          AND (mn.fil_no IS  NULL or mn.fil_no='')
                                        ) GROUP BY zz.diary_no,zz.r_by_empid,zz.disp_dt,zz.d_to_empid,xx.diary_no,ww.diary_no,mn.fil_no ) aa 
                                    GROUP BY r_by_empid
                                    
                                UNION
                                    ALL 
                                    SELECT 
                                      NULL::bigint AS s,
                                      NULL::bigint AS ss,
                                      NULL::bigint AS sss, 
                                      NULL::bigint ssss, 
                                      COUNT(r_by_empid) AS sssss,
                                      r_by_empid 
                                    FROM
                                      (SELECT 
                                      distinct zz.*,
                                        xx.diary_no d_no,
                                        ww.diary_no d_no2,
                                        mn.fil_no ,
                                      (ARRAY_AGG(xx.rece_dt ORDER BY xx.rece_dt))[1] AS rece_dt,
                                      (ARRAY_AGG(ww.rece_dt ORDER BY ww.rece_dt))[1] AS yy
                                      FROM
                                        (SELECT 
                                          diary_no,
                                          d_to_empid,
                                          r_by_empid ,disp_dt
                                        FROM
                                          fil_trap 
                                        WHERE DATE(comp_dt) BETWEEN '$frm_dt' 
                                          AND '$to_dt' 
                                        $remarks1  $chk_da
                                          AND r_by_empid != 0 
                                        UNION
                                        ALL 
                                        SELECT 
                                          diary_no,
                                          d_to_empid,
                                          r_by_empid ,disp_dt
                                        FROM
                                          fil_trap_his 
                                        WHERE DATE(comp_dt) BETWEEN '$frm_dt' 
                                          AND '$to_dt' 
                                        $remarks1 $chk_da
                                          AND r_by_empid != 0
                                          ) zz 
                                        LEFT JOIN fil_trap xx 
                                          ON xx.diary_no = zz.diary_no 
                                          AND $ck_comp_refil_x   AND xx.disp_dt>=zz.disp_dt
                                        LEFT JOIN fil_trap_his ww 
                                          ON ww.diary_no = zz.diary_no 
                                          AND $ck_comp_refil_w AND ww.disp_dt>=zz.disp_dt
                                        LEFT JOIN main mn 
                                          ON mn.diary_no = zz.diary_no 
                                      WHERE (
                                          xx.diary_no IS NOT NULL 
                                          OR ww.diary_no IS NOT NULL 
                                          OR mn.fil_no IS NOT NULL
                                        )  GROUP BY zz.diary_no,zz.r_by_empid,zz.disp_dt,zz.d_to_empid,xx.diary_no,ww.diary_no,mn.fil_no) aa 
                                    GROUP BY r_by_empid 
          

                                    UNION ALL 
                                        SELECT 
                                          NULL::bigint AS s,
                                          NULL::bigint AS ss,
                                          NULL::bigint AS sss, 
                                          COUNT(r_by_empid) AS ssss,
                                          NULL::bigint AS sssss,
                                          r_by_empid 
                                          
                                          FROM
                                            (SELECT 
                                              diary_no,
                                              d_to_empid,
                                              r_by_empid ,disp_dt
                                            FROM
                                              fil_trap 
                                            WHERE DATE(rece_dt) BETWEEN '$frm_dt' 
                                              AND '$to_dt' 
                                            $remarks1  $chk_da
                                              AND r_by_empid != 0 
                                            UNION
                                            ALL 
                                            SELECT 
                                              diary_no,
                                              d_to_empid,
                                              r_by_empid ,disp_dt
                                            FROM
                                              fil_trap_his 
                                            WHERE DATE(rece_dt) BETWEEN '$frm_dt' 
                                              AND '$to_dt' 
                                            $remarks1 $chk_da
                                              AND r_by_empid != 0
                                              ) zz 
                                            
                                        GROUP BY r_by_empid
                                    ) bb 
                                        ON u.empid = bb.r_by_empid 
                                    WHERE t_u.usertype = '$ddl_users' 
                                      AND t_u.display = 'Y' and u.display='Y' $chk_users
                                    GROUP BY u.usercode ) tt  GROUP BY tt.usercode,tt.r_by_empid
                                    ORDER BY s DESC";
      }
    } elseif ($ddl_users == '105')
    {
      $sql = "SELECT 
          SUM(s) s,
          SUM(ss) ss,
          SUM(sss) sss,
        SUM(ssss) ssss, 
        SUM(sssss) sssss,
          u.empid d_to_empid,
          u.usercode 
        FROM
          master.users u 
          JOIN fil_trap_users t_u 
            ON u.usercode = t_u.usercode 
          LEFT JOIN 
            (SELECT 
              SUM(s) s,
              NULL::bigint AS ss,
              NULL::bigint AS sss,
              NULL::bigint ssss,
              NULL sssss,
              r_by_empid 
            FROM
              (SELECT 
                COUNT(uid) s,
              r_by_empid  
              FROM
                fil_trap 
              WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
                AND '$to_dt' 
              $remarks $chk_da
              GROUP BY r_by_empid 
              UNION
              ALL 
              SELECT 
                COUNT(uid) s,
              r_by_empid   
              FROM
                fil_trap_his 
              WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
                AND '$to_dt' 
                $remarks $chk_da
              GROUP BY r_by_empid) aa 
            GROUP BY r_by_empid 

            UNION ALL  

            SELECT 
              NULL::bigint AS s,
              COUNT(r_by_empid) AS ss,
              NULL::bigint AS sss, 
              NULL::bigint ssss,
              NULL::bigint AS sssss,
          r_by_empid    
            FROM
              (SELECT DISTINCT 
                zz.*,
                xx.diary_no d_no,
                ww.diary_no d_no2
              FROM
                (SELECT 
                  diary_no,
                d_to_empid  ,
                  r_by_empid 
                FROM
                  fil_trap 
                WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
                  AND '$to_dt' 
                  $remarks  $chk_da
                
                UNION
                ALL 
                SELECT 
                  diary_no,
                  d_to_empid,
                  r_by_empid 
                FROM
                  fil_trap_his 
                WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
                  AND '$to_dt' 
                  $remarks $chk_da
                  AND r_by_empid != 0) zz 
                LEFT JOIN fil_trap xx 
                  ON xx.diary_no = zz.diary_no 
                  AND (xx.remarks = 'CAT -> TAG' or xx.remarks = 'CAT -> SCN' or xx.remarks = 'CAT -> IB-EX') 
                LEFT JOIN fil_trap_his ww 
                  ON ww.diary_no = zz.diary_no 
                  AND (ww.remarks = 'CAT -> TAG' or ww.remarks = 'CAT -> SCN' or ww.remarks = 'CAT -> IB-EX') 
              
              WHERE (
                  xx.diary_no IS NOT NULL 
                  OR ww.diary_no IS NOT NULL 
                  
                )) aa 
            GROUP BY r_by_empid 
            UNION ALL 
            SELECT 
              NULL::bigint AS s,
              NULL::bigint AS ss,
              COUNT(r_by_empid) AS sss, 
              NULL::bigint ssss,
              NULL::bigint sssss,
              r_by_empid 
            FROM
              (SELECT DISTINCT 
                zz.*,
                xx.diary_no d_no,
                ww.diary_no d_no2
                
              FROM
                (SELECT 
                  diary_no,
                  d_to_empid,
                  r_by_empid 
                FROM
                  fil_trap 
                WHERE $remarks_1 $chk_da and disp_dt>='2018-06-30'
                UNION
                ALL 
                SELECT 
                  diary_no,
                  d_to_empid,
                  r_by_empid 
                FROM 
                  fil_trap_his 
                WHERE $remarks_1 $chk_da and disp_dt>='2018-06-30') zz 
                LEFT JOIN fil_trap xx 
                  ON xx.diary_no = zz.diary_no 
                  AND (xx.remarks = 'CAT -> TAG' or xx.remarks = 'CAT -> SCN' or xx.remarks = 'CAT -> IB-EX') 
                LEFT JOIN fil_trap_his ww 
                  ON ww.diary_no = zz.diary_no 
                  AND (ww.remarks = 'CAT -> TAG' or ww.remarks = 'CAT -> SCN' or ww.remarks = 'CAT -> IB-EX') 
              
              WHERE (
                  xx.diary_no IS NULL 
                  AND ww.diary_no IS NULL 
                
                )) aa 
            GROUP BY r_by_empid
            
              UNION ALL 

            SELECT 
              NULL::bigint AS s,
              NULL::bigint AS ss,
              NULL::bigint AS sss, 
              COUNT(r_by_empid) AS ssss,
              NULL::bigint AS sssss,
              r_by_empid    
            FROM
              (SELECT DISTINCT 
                zz.*,
                xx.diary_no d_no,
                ww.diary_no d_no2
              FROM
                (SELECT 
                  diary_no,
                d_to_empid  ,
                  r_by_empid 
                FROM
                  fil_trap 
                WHERE DATE(comp_dt) BETWEEN '$frm_dt' 
                  AND '$to_dt' 
                  $remarks  $chk_da
                  
                UNION
                ALL 
                SELECT 
                  diary_no,
                  d_to_empid,
                  r_by_empid 
                FROM
                  fil_trap_his 
                WHERE DATE(comp_dt) BETWEEN '$frm_dt' 
                  AND '$to_dt' 
                  $remarks $chk_da
                  AND r_by_empid != 0) zz 
                LEFT JOIN fil_trap xx 
                  ON xx.diary_no = zz.diary_no 
                  AND (xx.remarks = 'CAT -> TAG' or xx.remarks = 'CAT -> SCN' or xx.remarks = 'CAT -> IB-EX') 
                LEFT JOIN fil_trap_his ww 
                  ON ww.diary_no = zz.diary_no 
                  AND (ww.remarks = 'CAT -> TAG' or ww.remarks = 'CAT -> SCN' or ww.remarks = 'CAT -> IB-EX') 
              
              WHERE (
                  xx.diary_no IS NOT NULL 
                  OR ww.diary_no IS NOT NULL 
                  
                )) aa 
            GROUP BY r_by_empid 
            
      union all
      SELECT NULL::bigint AS s, NULL::bigint AS ss, NULL::bigint AS sss,  NULL::bigint ssss, COUNT( r_by_empid ) sssss, r_by_empid
      FROM (

      SELECT diary_no, d_to_empid, r_by_empid
      FROM fil_trap
      WHERE DATE( rece_dt )
      BETWEEN '$frm_dt' AND '$to_dt' $remarks  $chk_da
      AND r_by_empid !=0
      UNION ALL SELECT diary_no, d_to_empid, r_by_empid
      FROM fil_trap_his
      WHERE DATE( rece_dt )
      BETWEEN  '$frm_dt' AND '$to_dt' $remarks $chk_da
      AND r_by_empid !=0
      )zz
      GROUP BY r_by_empid

      ) bb 
            ON u.empid = bb.r_by_empid 
        WHERE t_u.usertype = '$ddl_users' 
          AND t_u.display = 'Y' 
          AND u.display = 'Y' $chk_users
        GROUP BY u.usercode ";
    }
    elseif ($ddl_users == '109')
    {
      $sql = "Select  u.empid d_to_empid ,COUNT(diary_no) ss from  master.users u 
        JOIN fil_trap_users t_u 
          ON u.usercode = t_u.usercode
        left join ld_move c on c.disp_by=u.usercode  and date(disp_dt) 
        between '$frm_dt' AND '$to_dt'
        WHERE t_u.usertype = '$ddl_users' $chk_users
        AND t_u.display = 'Y'  and u.display='Y' $chk_users GROUP BY u.empid ";
    }
    else
    {
      $res_rmk = '';
      if ($ddl_users == '102') {
        $res_rmk = "DE -> SCR";
      } elseif ($ddl_users == '106') {
        $res_rmk = "TAG -> SCN";
      } else if ($ddl_users == '9796') {
        $res_rmk = "SCN -> IB-Ex";
      } else if ($ddl_users == '107') {
        $res_rmk = "IB-Ex -> Crt";
      }

      $sql = "SELECT 
          SUM(s) s,
          SUM(ss) ss,
          SUM(sss) sss,
        SUM(ssss) ssss,
        SUM(sssss) sssss,
          u.empid d_to_empid,
          u.usercode 
        FROM
          master.users u 
          JOIN fil_trap_users t_u 
            ON u.usercode = t_u.usercode 
          LEFT JOIN 
            (SELECT 
              SUM(s) s,
            NULL::bigint ss,
            NULL::bigint sss, 
            NULL::bigint ssss, 
            NULL::bigint sssss,
              d_to_empid 
            FROM
              (SELECT 
                COUNT(uid) s,
                d_to_empid 
              FROM
                fil_trap 
              WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
                AND '$to_dt' 
              $remarks $chk_da
              GROUP BY d_to_empid 
              UNION
              ALL 
              SELECT 
                COUNT(uid) s,
                d_to_empid 
              FROM
                fil_trap_his 
              WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
                AND '$to_dt' 
                $remarks $chk_da
              GROUP BY d_to_empid) aa 
            GROUP BY d_to_empid 
            UNION
            ALL 
            SELECT 
              NULL::bigint AS s,
              COUNT(d_to_empid) ss,
              NULL::bigint AS sss,
              NULL::bigint ssss,
              NULL::bigint sssss,
              d_to_empid 
            FROM
              (SELECT DISTINCT 
                zz.*,
                xx.diary_no d_no,
                ww.diary_no d_no2
              FROM
                (SELECT 
                  diary_no,
                  d_to_empid,
                  r_by_empid 
                FROM
                  fil_trap 
                WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
                  AND '$to_dt' 
                  $remarks  $chk_da
                  
                UNION
                ALL 
                SELECT 
                  diary_no,
                  d_to_empid,
                  r_by_empid 
                FROM
                  fil_trap_his 
                WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
                  AND '$to_dt' 
                  $remarks $chk_da
                  AND r_by_empid != 0) zz 
                LEFT JOIN fil_trap xx 
                  ON xx.diary_no = zz.diary_no 
                  AND (xx.remarks = '$res_rmk') 
                LEFT JOIN fil_trap_his ww 
                  ON ww.diary_no = zz.diary_no 
                  AND (ww.remarks = '$res_rmk') 
              
              WHERE (
                  xx.diary_no IS NOT NULL 
                  OR ww.diary_no IS NOT NULL 
                  
                )) aa 
            GROUP BY d_to_empid 
            UNION
            ALL 
            SELECT 
              NULL::bigint AS s,
              NULL::bigint AS ss,
              NULL::bigint AS sss,
              COUNT(d_to_empid) AS ssss,
              NULL::bigint AS sssss,
              d_to_empid 
            FROM
              (SELECT DISTINCT 
                zz.*,
                xx.diary_no d_no,
                ww.diary_no d_no2
                
              FROM
                (SELECT 
                  diary_no,
                  d_to_empid,
                  r_by_empid 
                FROM
                  fil_trap 
                WHERE $remarks_1 $chk_da and disp_dt>='2018-06-30'
                UNION
                ALL 
                SELECT 
                  diary_no,
                  d_to_empid,
                  r_by_empid 
                FROM
                  fil_trap_his 
                WHERE $remarks_1 $chk_da and disp_dt>='2018-06-30') zz 
                LEFT JOIN fil_trap xx 
                  ON xx.diary_no = zz.diary_no 
                  AND (xx.remarks = '$res_rmk') 
                LEFT JOIN fil_trap_his ww 
                  ON ww.diary_no = zz.diary_no 
                  AND (ww.remarks = '$res_rmk') 
              
              WHERE (
                  xx.diary_no IS NULL 
                  AND ww.diary_no IS NULL 
                
                )) aa 
            GROUP BY d_to_empid

          UNION ALL

          SELECT 
                  NULL::bigint AS s,
                      NULL::bigint AS ss,
                      NULL::bigint AS sss,
                      COUNT(r_by_empid) AS ssss,
                      NULL::bigint AS sssss,
                      r_by_empid AS d_to_empid
                FROM
              (SELECT DISTINCT 
                zz.*,
                xx.diary_no d_no,
                ww.diary_no d_no2
              FROM
                (SELECT 
                  diary_no,
                  d_to_empid,
                  r_by_empid 
                FROM
                  fil_trap 
                WHERE DATE(comp_dt) BETWEEN '$frm_dt' 
                  AND '$to_dt' 
                  $remarks  $chk_da
                  
                UNION
                ALL 
                SELECT 
                  diary_no,
                  d_to_empid,
                  r_by_empid 
                FROM
                  fil_trap_his 
                WHERE DATE(comp_dt) BETWEEN '$frm_dt' 
                  AND '$to_dt' 
                  $remarks $chk_da
                  AND r_by_empid != 0
                  ) zz 
                LEFT JOIN fil_trap xx 
                  ON xx.diary_no = zz.diary_no 
                  AND (xx.remarks = '$res_rmk') 
                LEFT JOIN fil_trap_his ww 
                  ON ww.diary_no = zz.diary_no 
                  AND (ww.remarks = '$res_rmk') 
              
              WHERE (
                  xx.diary_no IS NOT NULL 
                  OR ww.diary_no IS NOT NULL 
                  
                )) aa 
            GROUP BY r_by_empid 
      UNION ALL
      SELECT 
              NULL::bigint AS s,
              NULL::bigint AS ss,
              NULL::bigint AS sss,
              NULL::bigint AS ssss,
              COUNT(r_by_empid) AS sssss,
              r_by_empid AS d_to_empid 
            FROM
              
                (SELECT 
                  diary_no,
                  d_to_empid,
                  r_by_empid 
                FROM
                  fil_trap 
                WHERE DATE(rece_dt) BETWEEN '$frm_dt' 
                  AND '$to_dt' 
                  $remarks  $chk_da
                  
                UNION
                ALL 
                SELECT 
                  diary_no,
                  d_to_empid,
                  r_by_empid 
                FROM
                  fil_trap_his 
                WHERE DATE(rece_dt) BETWEEN '$frm_dt' 
                  AND '$to_dt' 
                  $remarks $chk_da
                  AND r_by_empid != 0) zz 
            GROUP BY r_by_empid 

      ) bb 
            ON u.empid = bb.d_to_empid 
        WHERE t_u.usertype = '$ddl_users' 
          AND t_u.display = 'Y' 
          AND u.display = 'Y'  $chk_users
        GROUP BY u.usercode ";
    }

    $query = $this->db->query($sql);

    return $query->getResultArray();
  }

  public function get_pen_cat($ddl_users, $frm_dt, $to_dt, $chk_da, $chk_users)
  {
    $rmk_x = '';
    $rmk_w = '';
    $sec_id = '';
    $remarks = '';
    $remarks1 = '';
    $remarks_1 = '';
    $remark_rf = '';

    if ($ddl_users == '102') {
      $remarks = " and remarks='FIL -> DE'";
      $remarks_1 = "  remarks='FIL -> DE'";
    } elseif ($ddl_users == '103') {
      $remarks_1 = "  remarks='DE -> SCR'";
      $remarks = " and remarks='DE -> SCR'";
      $remarks1 = " AND (remarks = 'AOR -> SCR' or remarks = 'FDR -> SCR')";
      $remark_rf = "  (remarks = 'AOR -> SCR' or remarks = 'FDR -> SCR')";
    }
    if ($ddl_users == '105') {
      $remarks = " and (remarks='SCR -> CAT' or remarks = 'AUTO -> CAT')";
      $remarks_1 = "  (remarks='SCR -> CAT' or remarks = 'AUTO -> CAT')";
    }
    if ($ddl_users == '106') {
      $remarks = " and remarks='CAT -> TAG'";
      $remarks_1 = "  remarks='CAT -> TAG'";
    }

    if ($ddl_users == '9796') {
      $remarks = " and (remarks='TAG -> SCN' or remarks='CAT -> SCN')";
      $remarks_1 = "  (remarks='TAG -> SCN' or remarks='CAT -> SCN')";
    }
    if ($ddl_users == '107') {
      $remarks = " and remarks='SCN -> IB-Ex'";
      $remarks_1 = "  remarks='SCN -> IB-Ex'";
    }
    if ($ddl_users == '108') {
      $remarks_1 = "  remarks='SCR -> FDR'";
      $remarks = " and remarks='SCR -> FDR'";
      $remarks1 = " AND remarks='AOR -> FDR'";
      $remark_rf = "  remarks='AOR -> FDR'";
    }

    if ($ddl_users == '105') {
      $rmk_x = " xx.remarks = 'CAT -> TAG' OR xx.remarks = 'CAT -> SCN'";
      $rmk_w = " ww.remarks = 'CAT -> TAG' OR ww.remarks = 'CAT -> SCN'";
      $sec_id = "27";
      $pend_cat = "SELECT 
        SUM(s) s,
        SUM(ss) sss,
        u.empid d_to_empid,
        u.usercode ,NAME
        FROM
        master.users u 
        LEFT JOIN 
        (SELECT 
        SUM(s) s,
        NULL ss,
        d_to_empid FROM

        (SELECT 
          COUNT(uid) s,
          d_to_empid 
        FROM
          fil_trap 
        WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
              AND '$to_dt' 
        $remarks
          AND r_by_empid = 0 
          AND d_to_empid = '$sec_id' 
        GROUP BY d_to_empid 
        UNION
        ALL 
        SELECT 
          COUNT(uid) s,
          d_to_empid 
        FROM
          fil_trap_his 
        WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
              AND '$to_dt' 
        $remarks
          AND r_by_empid = 0 
          AND d_to_empid = '$sec_id'  GROUP BY d_to_empid) aa
        GROUP BY d_to_empid
        UNION
        ALL 
        SELECT 
        NULL::bigint AS s,
        COUNT(d_to_empid) AS sss,
        d_to_empid 
        FROM
        (SELECT DISTINCT 
        zz.*,
        xx.diary_no d_no,
        ww.diary_no d_no2 
        FROM
        (SELECT 
          diary_no,
          d_to_empid,
          r_by_empid 
        FROM
          fil_trap 
        WHERE $remarks_1
          AND disp_dt >= '2018-06-01' 
          AND r_by_empid = 0 
          AND d_to_empid = '$sec_id' 
        UNION
        ALL 
        SELECT 
          diary_no,
          d_to_empid,
          r_by_empid 
        FROM
          fil_trap_his 
        WHERE $remarks_1
          AND disp_dt >= '2018-06-01' 
          AND r_by_empid = 0 
          AND d_to_empid = '$sec_id') zz 
        LEFT JOIN fil_trap xx 
          ON xx.diary_no = zz.diary_no 
          AND (
          $rmk_x
          ) 
        LEFT JOIN fil_trap_his ww 
          ON ww.diary_no = zz.diary_no 
          AND (
            $rmk_w
          ) 
        WHERE (
          xx.diary_no IS NULL 
          AND ww.diary_no IS NULL
        )) aa 
        GROUP BY d_to_empid) bb 

        ON u.empid = bb.d_to_empid   WHERE   u.usercode ='$sec_id'
        GROUP BY u.empid, u.usercode, u.NAME;";
    } else if ($ddl_users == '108') {
      $ck_comp_fil_x = " xx.remarks = 'FDR -> AOR'  ";
      $ck_comp_fil_w = " ww.remarks = 'FDR -> AOR' ";
      $ck_comp_refil_x = " xx.remarks = 'FDR -> SCR'  ";
      $ck_comp_refil_w = " ww.remarks = 'FDR -> SCR'  ";
      $sec_id = "9798";
      $pend_cat = "
            SELECT  SUM(s) s,
        SUM(ss) ss,
        SUM(sss) sss,SUM(r_s) r_s,SUM(r_ss) r_ss,SUM(r_sss) r_sss,  d_to_empid FROM  (SELECT 
        SUM(s) s,
        SUM(ss) ss,
        SUM(sss) sss,NULL r_s,NULL r_ss,NULL r_sss,
        u.empid d_to_empid ,u.usercode
        FROM
        master.users u 
        JOIN 
        (SELECT 
          SUM(s) s,
          NULL ss,
          NULL sss,
          d_to_empid 
        FROM
          (SELECT 
            COUNT(uid) s,
          d_to_empid  
          FROM
            fil_trap 
          WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
            AND '$to_dt' 
            $remarks $chk_da  AND r_by_empid = 0 
          AND d_to_empid = '$sec_id' 
          GROUP BY d_to_empid 
          UNION
          ALL 
          SELECT 
            COUNT(uid) AS s,
          d_to_empid   
          FROM
            fil_trap_his 
          WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
            AND '$to_dt' 
            $remarks $chk_da AND r_by_empid = 0 
          AND d_to_empid = '$sec_id' 
          GROUP BY d_to_empid) aa 
        GROUP BY d_to_empid 
        UNION
        ALL 
        SELECT 
           NULL::bigint AS s,
          COUNT(d_to_empid) AS ss,
           NULL::bigint AS sss,
        d_to_empid   
        FROM
          (SELECT 
          DISTINCT zz.*,
            xx.diary_no d_no,
            ww.diary_no d_no2,
            mn.fil_no 
          FROM
            (SELECT 
              diary_no,
              d_to_empid,
              r_by_empid ,disp_dt
            FROM
              fil_trap 
            WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
              AND '$to_dt' 
              $remarks $chk_da AND r_by_empid = 0 
          AND d_to_empid = '$sec_id' 
             
            UNION
            ALL 
            SELECT 
              diary_no,
              d_to_empid,
              r_by_empid ,disp_dt
            FROM
              fil_trap_his 
            WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
              AND '$to_dt' 
              $remarks $chk_da AND r_by_empid = 0 
          AND d_to_empid = '$sec_id' 
            
              ) zz 
            LEFT JOIN fil_trap xx 
              ON xx.diary_no = zz.diary_no 
              AND $ck_comp_fil_x
            LEFT JOIN fil_trap_his ww 
              ON ww.diary_no = zz.diary_no 
              AND $ck_comp_fil_w
            LEFT JOIN main mn 
              ON mn.diary_no = zz.diary_no 
          WHERE (
              xx.diary_no IS NOT NULL 
              OR ww.diary_no IS NOT NULL 
              OR mn.fil_no IS NOT NULL
            )) aa 
        GROUP BY d_to_empid 
        UNION
        ALL 
        SELECT 
             NULL::bigint AS s,
             NULL::bigint AS ss, 
             COUNT(d_to_empid) AS sss,
            d_to_empid 
          FROM
            (SELECT DISTINCT 
              zz.*,
              xx.diary_no d_no,
              ww.diary_no d_no2,
              mn.fil_no
            
            FROM
              (SELECT 
                diary_no,
                d_to_empid,
                r_by_empid 
              FROM
                fil_trap 
              WHERE  $remarks_1 $chk_da AND disp_dt>='2018-06-30'
              
              UNION
              ALL 
              SELECT 
                diary_no,
                d_to_empid,
                r_by_empid 
              FROM
                fil_trap_his 
              WHERE  $remarks_1 $chk_da AND disp_dt>='2018-06-30'
              ) zz 
              LEFT JOIN fil_trap xx 
                ON xx.diary_no = zz.diary_no 
                AND $ck_comp_fil_x
              LEFT JOIN fil_trap_his ww 
                ON ww.diary_no = zz.diary_no 
                AND $ck_comp_fil_w
              LEFT JOIN main mn 
                ON mn.diary_no = zz.diary_no 
            WHERE (
                xx.diary_no IS  NULL 
                AND ww.diary_no IS  NULL 
                AND (mn.fil_no IS  NULL OR mn.fil_no='')
              )) aa 
          GROUP BY d_to_empid ) bb 
        ON u.empid = bb.d_to_empid 
        WHERE 
        u.display='Y' $chk_users
        GROUP BY u.usercode 
        UNION ALL
        SELECT 
         NULL::bigint AS s,
         NULL::bigint AS ss,
         NULL::bigint AS sss,
        SUM(s) r_s,
        SUM(ss) r_ss,
        SUM(sss) r_sss,
        u.empid d_to_empid ,u.usercode
        FROM
        master.users u 
        LEFT JOIN 
        (SELECT 
          SUM(s) s,
           NULL::bigint AS ss,
           NULL::bigint AS sss,
          d_to_empid 
        FROM
          (SELECT 
            COUNT(uid) s,
            d_to_empid 
          FROM
            fil_trap 
          WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
            AND '$to_dt' 
          $remarks1 $chk_da AND r_by_empid = 0 
          AND d_to_empid = '$sec_id' 
          GROUP BY d_to_empid 
          UNION
          ALL 
          SELECT 
            COUNT(uid) s,
            d_to_empid 
          FROM
            fil_trap_his 
          WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
            AND '$to_dt' 
          $remarks1 $chk_da AND r_by_empid = 0 
          AND d_to_empid = '$sec_id' 
          GROUP BY d_to_empid) aa 
        GROUP BY d_to_empid 
        UNION
        ALL 
        SELECT 
           NULL::bigint AS s,
          COUNT(d_to_empid) AS ss,
           NULL::bigint AS sss,
          d_to_empid 
        FROM
          (SELECT 
          DISTINCT zz.*,
            xx.diary_no d_no,
            ww.diary_no d_no2,
            mn.fil_no ,
          (ARRAY_AGG(xx.rece_dt ORDER BY xx.rece_dt))[1] AS rece_dt,
        (ARRAY_AGG(ww.rece_dt ORDER BY ww.rece_dt))[1] AS yy
          FROM
            (SELECT 
              diary_no,
              d_to_empid,
              r_by_empid ,disp_dt
            FROM
              fil_trap 
            WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
              AND '$to_dt' 
            $remarks1  $chk_da AND r_by_empid = 0 
          AND d_to_empid = '$sec_id' 
              
            UNION
            ALL 
            SELECT 
              diary_no,
              d_to_empid,
              r_by_empid ,disp_dt
            FROM
              fil_trap_his 
            WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
              AND '$to_dt' 
            $remarks1 $chk_da AND r_by_empid = 0 
          AND d_to_empid = '$sec_id' 
              AND r_by_empid != 0
              ) zz 
            LEFT JOIN fil_trap xx 
              ON xx.diary_no = zz.diary_no 
              AND $ck_comp_refil_x   AND xx.disp_dt>=zz.disp_dt
            LEFT JOIN fil_trap_his ww 
              ON ww.diary_no = zz.diary_no 
              AND $ck_comp_refil_w AND ww.disp_dt>=zz.disp_dt
            LEFT JOIN main mn 
              ON mn.diary_no = zz.diary_no 
          WHERE (
              xx.diary_no IS NOT NULL 
              OR ww.diary_no IS NOT NULL 
              OR mn.fil_no IS NOT NULL
            )  GROUP BY zz.diary_no,zz.d_to_empid,zz.disp_dt,zz.d_to_empid,xx.diary_no,ww.diary_no,mn.fil_no,zz.r_by_empid) aa 
        GROUP BY d_to_empid 
        UNION
        ALL 
        SELECT 
             NULL::bigint AS s,
           NULL::bigint AS ss,
          COUNT(d_to_empid) AS sss,
          d_to_empid 
          FROM
            (SELECT DISTINCT 
              zz.*,
              xx.diary_no d_no,
              ww.diary_no d_no2,
              mn.fil_no,
              (ARRAY_AGG(xx.rece_dt ORDER BY xx.rece_dt))[1] AS rece_dt,
              (ARRAY_AGG(ww.rece_dt ORDER BY ww.rece_dt))[1] AS yy
            FROM
              (SELECT 
                diary_no,
                d_to_empid,
                r_by_empid ,disp_dt
              FROM
                fil_trap 
              WHERE   $remark_rf $chk_da AND disp_dt>='2018-06-30'
              
              UNION ALL 
              SELECT 
                diary_no,
                d_to_empid,
                r_by_empid ,disp_dt
              FROM
                fil_trap_his 
              WHERE  $remark_rf $chk_da AND disp_dt>='2018-06-30'
              ) zz 
              LEFT JOIN fil_trap xx 
                ON xx.diary_no = zz.diary_no 
                AND $ck_comp_refil_x   AND xx.disp_dt>=zz.disp_dt
              LEFT JOIN fil_trap_his ww 
                ON ww.diary_no = zz.diary_no 
                AND  $ck_comp_refil_w AND ww.disp_dt>=zz.disp_dt
              LEFT JOIN main mn 
                ON mn.diary_no = zz.diary_no 
            WHERE (
                xx.diary_no IS  NULL 
                AND ww.diary_no IS  NULL 
                AND (mn.fil_no IS  NULL OR mn.fil_no='')
              ) GROUP BY zz.diary_no,zz.d_to_empid,zz.disp_dt,zz.d_to_empid,xx.diary_no,ww.diary_no,mn.fil_no,zz.r_by_empid ) aa 
          GROUP BY d_to_empid ) bb 
        ON u.empid = bb.d_to_empid 
        WHERE 
        u.display='Y' $chk_users and   u.empid ='$sec_id'
        GROUP BY u.usercode ) tt  GROUP BY tt.usercode,tt.d_to_empid 
        ORDER BY s DESC";
    }
    $query = $this->db->query($pend_cat);

    return $query->getResultArray();
  }

  public function get_fil_record($frm_dt, $to_dt, $ddl_users, $chk_users, $l_sp_split, $jn_users, $emp_nm, $remarks1, $remarks, $total_pages, $usercode, $r_section, $r_usertype, $hd_nm_id, $r_sp_split)
  {
    //jn_users

    //spcomp
    //r_sp_split
    // $ddl_users = '102';
    // $l_sp_split = 'sptotpen';
    $r_sp_split = 0;

    if ($ddl_users == '103') {
      $remarks = " AND remarks = 'DE -> SCR'";
      $remarkss = " remarks = 'DE -> SCR'";
      $remarks1 = " AND (remarks = 'AOR -> SCR' OR remarks = 'FDR -> SCR')";
      $remarks1_s = " (remarks = 'AOR -> SCR' OR remarks = 'FDR -> SCR')";
    } elseif ($ddl_users == '102') {
      $remarks = " AND remarks = 'FIL -> DE'";
      $remarkss = " remarks = 'FIL -> DE'";
    } elseif ($ddl_users == '106') {
      $remarks = " AND remarks = 'CAT -> TAG'";
      $remarkss = " remarks = 'CAT -> TAG'";
    } elseif ($ddl_users == '9796') {
      $remarks = " AND (remarks = 'TAG -> SCN' OR remarks = 'CAT -> SCN')";
      $remarkss = " (remarks = 'TAG -> SCN' OR remarks = 'CAT -> SCN')";
    } elseif ($ddl_users == '107') {
      $remarks = " AND (remarks = 'SCN -> IB-Ex' OR remarks = 'TAG -> IB-Ex' OR remarks = 'CAT -> IB-Ex')";
      $remarkss = " (remarks = 'SCN -> IB-Ex' OR remarks = 'TAG -> IB-Ex' OR remarks = 'CAT -> IB-Ex')";
    } elseif ($ddl_users == '105') {
      $remarks = " AND (remarks = 'SCR -> CAT' OR remarks = 'AUTO -> CAT')";
      $remarkss = " (remarks = 'SCR -> CAT' OR remarks = 'AUTO -> CAT')";
    }

    if ($ddl_users == '108') {
      $remarks = " AND remarks = 'SCR -> FDR'";
      $remarkss = " remarks = 'SCR -> FDR'";
      $remarks1 = " AND remarks = 'AOR -> FDR'";
      $remarks1_s = " remarks = 'AOR -> FDR'";
    }


    $com_rmk = '';
    $mn_fil = '';
    $jn_mn = '';
    $f_no = '';
    $cat_m = '';
    $cat_m1 = '';
    $mn_c = '';
    $r_user_id =  is_data_from_table('master.users', "empid=$hd_nm_id", 'usercode', '')['usercode'];
    if ($ddl_users == '103') {
      $com_rmk = "SCR -> AOR";
      $mn_fil = "  AND (mn.fil_no IS  NULL  or mn.fil_no='')";
      $jn_mn = "   LEFT JOIN main mn 
              ON mn.diary_no = zz.diary_no ";
      $f_no = " ,mn.fil_no ";
      $mn_c = "  OR mn.fil_no IS NOT NULL";

      $cat_m = " or xx.remarks = 'AUTO -> CAT' or xx.remarks = 'SCR -> CAT' OR xx.remarks = 'SCR -> REF' OR xx.remarks = 'SCR -> FDR'";
      $cat_m1 = " or ww.remarks = 'AUTO -> CAT' or ww.remarks = 'SCR -> CAT' OR ww.remarks = 'SCR -> REF' OR ww.remarks = 'SCR -> FDR'";
    } else if ($ddl_users == '108') {
      $com_rmk = "FDR -> AOR";
      $mn_fil = "  AND (mn.fil_no IS  NULL  or mn.fil_no='')";
      $jn_mn = "   LEFT JOIN main mn 
              ON mn.diary_no = zz.diary_no ";
      $f_no = " ,mn.fil_no ";
      $mn_c = "  OR mn.fil_no IS NOT NULL";

      $cat_m = " or xx.remarks = 'FDR -> SCR'";
      $cat_m1 = " or ww.remarks = 'FDR -> SCR'";
    } else if ($ddl_users == '102') {
      $com_rmk = "DE -> SCR";
    } else if ($ddl_users == '106') {
      $com_rmk = "TAG -> SCN";
      $cat_m = " or  xx.remarks = 'TAG -> IB-EX'";
      $cat_m1 = " or  ww.remarks = 'TAG -> IB-EX' ";
    } else if ($ddl_users == '9796') {
      $com_rmk = "SCN -> IB-Ex";
    } else if ($ddl_users == '107') {
      $com_rmk = "IB-Ex -> Crt";
    } else if ($ddl_users == '105') {
      $com_rmk = "CAT -> TAG";
      $cat_m = " or xx.remarks = 'CAT -> SCN' or xx.remarks = 'CAT -> IB-EX'";
      $cat_m1 = " or ww.remarks = 'CAT -> SCN' or ww.remarks = 'CAT -> IB-EX' ";
    }



    if ($r_sp_split != 0) {
      if ($ddl_users == '101') {
        // $user_id = "Select usercode from master.users where empid='$hd_nm_id'";
        // $user_id = mysql_query($user_id) or die("Error: " . __LINE__ . mysql_error());
        // $r_user_id = mysql_result($user_id, 0);
        $emp_nm = " and diary_user_id='$r_user_id'";
      } elseif ($ddl_users == '109') {
        // $user_id = "Select usercode from master.users where empid='$hd_nm_id'";
        // $user_id = mysql_query($user_id) or die("Error: " . __LINE__ . mysql_error());
        // $r_user_id = mysql_result($user_id, 0);
        $emp_nm = " and disp_by='$r_user_id'";
      } else {
        if (($ddl_users == '108' &&  $hd_nm_id != '9798') || ($ddl_users == '105' && $hd_nm_id != '27'))
          $emp_nm = " and r_by_empid='$hd_nm_id'";
        else {
          $emp_nm = " and d_to_empid='$hd_nm_id'";
          if ($ddl_users == '105' && $hd_nm_id == '27') {
            $emp_nm = $emp_nm . ' and r_by_empid=0';
          }
        }
      }
    } else if ($r_sp_split == '0') {
      if ($ddl_users == '101') {

        if ($usercode != 1 && $r_section != '30' && $r_usertype != '4')
          $emp_nm = " and diary_user_id='$usercode'";
        $jn_users = " join master.users u on u.usercode = c.disp_by and u.display='Y'
                    JOIN fil_trap_users t_u 
                  ON u.usercode = t_u.usercode and t_u.usertype = '$ddl_users' 
                AND t_u.display = 'Y'";
      } else if ($ddl_users == '109') {
        if ($usercode != 1 && $r_section != '30' && $r_usertype != '4')
          $emp_nm = " and disp_by='$usercode'";
        $jn_users = " join master.users u on u.usercode = c.disp_by and u.display='Y'
                    JOIN fil_trap_users t_u 
              ON u.usercode = t_u.usercode and t_u.usertype = '$ddl_users' 
            AND t_u.display = 'Y'";
      } else {

        if ($usercode != 1 && $r_section != '30' && $r_usertype != '4') {
          // $user_id = "Select empid  from users where usercode='$usercode'";
          // $user_id = mysql_query($user_id) or die("Error: " . __LINE__ . mysql_error());
          $r_user_id =  is_data_from_table('master.users', "empid='$hd_nm_id'", 'usercode', '')['usercode'];
          // $r_user_id = mysql_result($user_id, 0);
          if (($ddl_users == '108' &&  $hd_nm_id != '9798') || ($ddl_users == '105' && $hd_nm_id != '27'))
            $emp_nm = " and r_by_empid='$r_user_id'";
          else
            $emp_nm = " and d_to_empid='$r_user_id'";
        }
        $jn_users = " join master.users u on u.empid = zz.d_to_empid and u.display='Y'
                    JOIN fil_trap_users t_u 
        ON u.usercode = t_u.usercode and t_u.usertype = '$ddl_users' 
      AND t_u.display = 'Y'";

        $jn_users_r = " join master.users u on u.empid = zz.r_by_empid and u.display='Y'
                    JOIN fil_trap_users t_u 
        ON u.usercode = t_u.usercode and t_u.usertype = '$ddl_users' 
      AND t_u.display = 'Y'";
      }
    }


    if ($l_sp_split == 'sptotpen') {
      $sql = "SELECT DISTINCT 
              zz.*,
              xx.diary_no d_no,
              ww.diary_no d_no2 $f_no
            
            FROM
              (SELECT 
                diary_no, 
                d_to_empid,
                r_by_empid ,d_by_empid,disp_dt
              FROM
                fil_trap 
              WHERE  $remarkss $emp_nm and disp_dt>='2018-06-30'
              
              UNION
              ALL 
              SELECT 
                diary_no,
                d_to_empid,
                r_by_empid ,d_by_empid,disp_dt
              FROM
                fil_trap_his 
              WHERE  $remarkss $emp_nm and disp_dt>='2018-06-30'
              ) zz 
              LEFT JOIN fil_trap xx 
                ON xx.diary_no = zz.diary_no 
                AND (xx.remarks = '$com_rmk' $cat_m) 
              LEFT JOIN fil_trap_his ww 
                ON ww.diary_no = zz.diary_no 
                AND (ww.remarks = '$com_rmk' $cat_m1) 
            $jn_mn
              $jn_users
            WHERE (
                xx.diary_no IS  NULL 
                AND ww.diary_no IS  NULL 
              $mn_fil
              )order by disp_dt,d_by_empid";
    } else if ($l_sp_split == 'spallot') {
      if ($ddl_users == '101') {
        $sql = "Select diary_no,  diary_user_id d_to_empid,diary_no_rec_date disp_dt from main c $jn_users
                  where date(diary_no_rec_date) 
      between '$frm_dt' AND '$to_dt' $emp_nm ";
      } elseif ($ddl_users == '109') {
        $sql = "SELECT c.diary_no,  disp_by d_to_empid, disp_dt, c.docnum, c.docyear, disp_to, docdesc, other1
    FROM ld_move c $jn_users
    JOIN docmaster b ON c.doccode = b.doccode
    AND c.doccode1 = b.doccode1
    JOIN docdetails a ON a.diary_no = c.diary_no
    AND a.doccode = c.doccode
    AND a.doccode1 = c.doccode1
    AND a.docnum = c.docnum
    AND a.docyear = c.docyear
    WHERE date( disp_dt )
    BETWEEN '$frm_dt' AND '$to_dt' $emp_nm

    AND b.display = 'Y'
    AND a.display = 'Y'";
      } else {
        $sql = "SELECT diary_no, d_by_empid,d_to_empid,disp_dt,r_by_empid,rece_dt FROM fil_trap WHERE
          date(disp_dt) between '$frm_dt' and '$to_dt' $emp_nm $remarks 
        
          union all 
          SELECT diary_no,d_by_empid,d_to_empid,disp_dt,r_by_empid,rece_dt FROM fil_trap_his WHERE date(disp_dt) 
          between '$frm_dt' and '$to_dt' $emp_nm $remarks order by disp_dt,d_by_empid";
      }
    } else if ($l_sp_split == 'spcomp') {
      // vkg

      if ($ddl_users == '101') {
        //remove vkg

        // $sql = "Select diary_no, diary_user_id d_to_empid,diary_no_rec_date disp_dt from main c $jn_users
        //        where date(diary_no_rec_date) 
        //       between '$frm_dt' AND '$to_dt' $emp_nm ";
        $sql = "Select diary_no, diary_user_id d_to_empid,diary_no_rec_date disp_dt
                  from main c  
                  join master.users u on u.usercode = c.usercode 
                  and u.display='Y'
                  JOIN fil_trap_users t_u 
                  ON u.usercode = t_u.usercode and t_u.usertype = '101' 
                  AND t_u.display = 'Y'
                  where date(diary_no_rec_date) 
                  between '2021-02-01' AND '2025-02-08'  ";
        // echo $sql;
        // die;


      } elseif ($ddl_users == '109') {
        $sql = "SELECT c.diary_no,  disp_by d_to_empid, disp_dt, c.docnum, c.docyear, disp_to d_to_empid, docdesc, other1
                                FROM ld_move c $jn_users
                                JOIN docmaster b ON c.doccode = b.doccode
                                AND c.doccode1 = b.doccode1
                                JOIN docdetails a ON a.diary_no = c.diary_no
                                AND a.doccode = c.doccode
                                AND a.doccode1 = c.doccode1
                                AND a.docnum = c.docnum
                                AND a.docyear = c.docyear
                                WHERE date( disp_dt )
                                BETWEEN '$frm_dt' AND '$to_dt' $emp_nm

                                AND b.display = 'Y'
                                AND a.display = 'Y'";
      } elseif ($ddl_users == '103' || $ddl_users == '108') {
        $sql = "SELECT 
                      distinct zz.*,
                        xx.diary_no d_no,
                        ww.diary_no d_no2 $f_no
                      FROM
                        (SELECT 
                          diary_no, 
                          d_to_empid,
                          r_by_empid ,disp_dt,d_by_empid,rece_dt
                        FROM
                          fil_trap 
                        WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
                          AND '$to_dt'  $emp_nm
                          $remarks 
                          
                        UNION
                        ALL 
                        SELECT 
                          diary_no, 
                          d_to_empid,
                          r_by_empid ,disp_dt,d_by_empid,rece_dt
                        FROM
                          fil_trap_his 
                        WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
                          AND '$to_dt' $emp_nm
                          $remarks 
                        
                          ) zz 
                        LEFT JOIN fil_trap xx 
                          ON xx.diary_no = zz.diary_no 
                          AND (xx.remarks = '$com_rmk' $cat_m) 
                        LEFT JOIN fil_trap_his ww 
                          ON ww.diary_no = zz.diary_no 
                          AND (ww.remarks = '$com_rmk' $cat_m1) 
                      $jn_mn
                      WHERE (
                          xx.diary_no IS NOT NULL 
                          OR ww.diary_no IS NOT NULL 
                      $mn_c
                        ) 
                      order by zz.disp_dt,zz.d_by_empid";
      } else if ($ddl_users == '105' || $ddl_users == '106') {

        $sql = "SELECT DISTINCT
                      zz.*
                  FROM
                      (SELECT 
                          diary_no,
                              d_to_empid,
                              r_by_empid,
                              disp_dt,
                              d_by_empid,
                              rece_dt
                      FROM
                          fil_trap
                      WHERE
                          DATE(disp_dt) BETWEEN '$frm_dt' 
                            AND '$to_dt'  
                              AND  d_by_empid='$hd_nm_id' 
                              AND (remarks = 'CAT -> IB-Ex'
                              OR remarks = 'TAG -> IB-Ex'  or remarks='CAT -> TAG')
                              UNION ALL SELECT 
                          diary_no,
                              d_to_empid,
                              r_by_empid,
                              disp_dt,
                              d_by_empid,
                              rece_dt
                      FROM
                          fil_trap_his
                      WHERE
                          DATE(disp_dt) BETWEEN '$frm_dt' 
                            AND '$to_dt'  
                              AND  d_by_empid='$hd_nm_id' 
                              AND (remarks = 'CAT -> IB-Ex'
                              OR remarks = 'TAG -> IB-Ex' or remarks='CAT -> TAG')
                              ) zz ORDER BY zz.disp_dt , zz.d_by_empid";
      } else {
        $sql = "SELECT 
                    distinct zz.*,
                      xx.diary_no d_no,
                      ww.diary_no d_no2 $f_no
                    FROM
                      (SELECT 
                        diary_no, 
                        d_to_empid,
                        r_by_empid ,disp_dt,d_by_empid,rece_dt
                      FROM
                        fil_trap 
                      WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
                        AND '$to_dt'  $emp_nm
                        $remarks 
                        AND r_by_empid != 0 
                      UNION
                      ALL 
                      SELECT 
                        diary_no,
                        d_to_empid,
                        r_by_empid ,disp_dt,d_by_empid,rece_dt
                      FROM
                        fil_trap_his 
                      WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
                        AND '$to_dt' $emp_nm
                        $remarks 
                        AND r_by_empid != 0) zz 
                      LEFT JOIN fil_trap xx 
                        ON xx.diary_no = zz.diary_no 
                        AND (xx.remarks = '$com_rmk' $cat_m) 
                      LEFT JOIN fil_trap_his ww 
                        ON ww.diary_no = zz.diary_no 
                        AND (ww.remarks = '$com_rmk' $cat_m1) 
                    $jn_mn
                    WHERE (
                        xx.diary_no IS NOT NULL 
                        OR ww.diary_no IS NOT NULL 
                    $mn_c
                      ) 
                    order by zz.disp_dt,zz.d_by_empid";
      }
    } else if ($l_sp_split == 'spnotcomp') {
      $sql = "SELECT 
          distinct zz.*,
            xx.diary_no d_no,
            ww.diary_no d_no2 $f_no
          FROM
            (SELECT 
              diary_no, 
              d_to_empid,
              r_by_empid ,disp_dt,d_by_empid,rece_dt
            FROM
              fil_trap 
            WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
              AND '$to_dt'  $emp_nm
              $remarks 
            
            UNION
            ALL 
            SELECT 
              diary_no, 
              d_to_empid,
              r_by_empid ,disp_dt,d_by_empid,rece_dt
            FROM
              fil_trap_his 
            WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
              AND '$to_dt' $emp_nm
              $remarks 
            ) zz 
            LEFT JOIN fil_trap xx 
              ON xx.diary_no = zz.diary_no l_sp_split
              AND (xx.remarks = '$com_rmk' $cat_m) 
            LEFT JOIN fil_trap_his ww 
              ON ww.diary_no = zz.diary_no 
              AND (ww.remarks = '$com_rmk' $cat_m1) 
          $jn_mn
          WHERE (
            
              
                xx.diary_no IS  NULL 
                AND ww.diary_no IS  NULL 
                $mn_fil
            ) 
          order by zz.disp_dt,zz.d_by_empid
                        ";
    } else if ($l_sp_split == 'spallotr') {


      $sql = "SELECT 
            diary_no,  d_by_empid,d_to_empid,disp_dt,r_by_empid,rece_dt 
          FROM
            fil_trap 
          WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
            AND '$to_dt' 
        $emp_nm  $remarks1
        
          UNION
          ALL 
          SELECT 
          diary_no, d_by_empid,d_to_empid,disp_dt,r_by_empid,rece_dt 
          FROM
            fil_trap_his 
          WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
            AND '$to_dt' 
        $emp_nm  $remarks1
        order by disp_dt,d_by_empid";
    } else if ($l_sp_split == 'spcompr') {
      $r_chk_sec_x = '';
      $r_chk_sec_w = '';
      if ($ddl_users == '103') {
        $r_chk_sec_x = " (xx.remarks = 'SCR -> AOR' or xx.remarks = 'AUTO -> CAT' or xx.remarks = 'SCR -> CAT' or xx.remarks = 'SCR -> FDR') ";
        $r_chk_sec_w = " (ww.remarks = 'SCR -> AOR' or ww.remarks = 'AUTO -> CAT' or ww.remarks = 'SCR -> CAT' or ww.remarks = 'SCR -> FDR') ";
      } elseif ($ddl_users == '108') {
        $r_chk_sec_x = " xx.remarks = 'FDR -> SCR' ";
        $r_chk_sec_w = " ww.remarks = 'FDR -> SCR' ";
      }
      $sql = "SELECT 
          distinct zz.*,
            xx.diary_no d_no,
            ww.diary_no d_no2,
            mn.fil_no ,
            if(SUBSTRING_INDEX(GROUP_CONCAT(xx.rece_dt ORDER BY xx.rece_dt ),',',1) is null,
            SUBSTRING_INDEX(GROUP_CONCAT(ww.rece_dt ORDER BY ww.rece_dt),',',1),
              SUBSTRING_INDEX(GROUP_CONCAT(xx.rece_dt ORDER BY xx.rece_dt ),',',1)) rece_dt
            
          FROM
            (SELECT 
              diary_no, 
              d_to_empid,
              r_by_empid ,disp_dt,d_by_empid
            FROM
              fil_trap 
            WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
              AND '$to_dt' 
            $emp_nm $remarks1 
              AND r_by_empid != 0 
            UNION
            ALL 
            SELECT 
              diary_no, 
              d_to_empid,
              r_by_empid ,disp_dt,d_by_empid
            FROM
              fil_trap_his 
            WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
              AND '$to_dt' 
            $emp_nm $remarks1
              AND r_by_empid != 0
              ) zz 
            LEFT JOIN fil_trap xx 
              ON xx.diary_no = zz.diary_no 
              AND $r_chk_sec_x   AND xx.disp_dt>=zz.disp_dt
            LEFT JOIN fil_trap_his ww 
              ON ww.diary_no = zz.diary_no 
              AND  $r_chk_sec_w AND ww.disp_dt>=zz.disp_dt
            LEFT JOIN main mn 
              ON mn.diary_no = zz.diary_no 
          WHERE (
              xx.diary_no IS NOT NULL 
              OR ww.diary_no IS NOT NULL 
              OR mn.fil_no IS NOT NULL
            )  GROUP BY diary_no,d_to_empid,disp_dt
        order by disp_dt,d_by_empid
              ";
    } else if ($l_sp_split == 'spnotcompr') {

      $r_chk_sec_x = '';
      $r_chk_sec_w = '';
      if ($ddl_users == '103') {
        $r_chk_sec_x = " (xx.remarks = 'SCR -> AOR' or xx.remarks = 'AUTO -> CAT' or xx.remarks = 'SCR -> CAT' or xx.remarks = 'SCR -> FDR') ";
        $r_chk_sec_w = " (ww.remarks = 'SCR -> AOR' or ww.remarks = 'AUTO -> CAT' or ww.remarks = 'SCR -> CAT' or ww.remarks = 'SCR -> FDR') ";
      } elseif ($ddl_users == '108') {
        $r_chk_sec_x = " xx.remarks = 'FDR -> SCR' ";
        $r_chk_sec_w = " ww.remarks = 'FDR -> SCR' ";
      }
      $sql = "SELECT 
          distinct zz.*,
            xx.diary_no d_no,
            ww.diary_no d_no2,
            mn.fil_no ,
            if(SUBSTRING_INDEX(GROUP_CONCAT(xx.rece_dt ORDER BY xx.rece_dt ),',',1) is null,
            SUBSTRING_INDEX(GROUP_CONCAT(ww.rece_dt ORDER BY ww.rece_dt),',',1),
              SUBSTRING_INDEX(GROUP_CONCAT(xx.rece_dt ORDER BY xx.rece_dt ),',',1)) rece_dt
            
          FROM
            (SELECT 
              diary_no, 
              d_to_empid,
              r_by_empid ,disp_dt,d_by_empid
            FROM
              fil_trap 
            WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
              AND '$to_dt' 
            $emp_nm $remarks1 
            
            UNION
            ALL 
            SELECT 
              diary_no, 
              d_to_empid,
              r_by_empid ,disp_dt,d_by_empid
            FROM
              fil_trap_his 
            WHERE DATE(disp_dt) BETWEEN '$frm_dt' 
              AND '$to_dt' 
            $emp_nm $remarks1
            ) zz 
            LEFT JOIN fil_trap xx 
              ON xx.diary_no = zz.diary_no 
              AND $r_chk_sec_x  AND xx.disp_dt>=zz.disp_dt
            LEFT JOIN fil_trap_his ww 
              ON ww.diary_no = zz.diary_no 
              AND $r_chk_sec_w AND ww.disp_dt>=zz.disp_dt
            LEFT JOIN main mn 
              ON mn.diary_no = zz.diary_no 
          WHERE (
              xx.diary_no IS  NULL 
              and ww.diary_no IS  NULL 
              and (mn.fil_no IS  NULL or mn.fil_no='')
            )  GROUP BY diary_no,d_to_empid,disp_dt
        order by disp_dt,d_by_empid
                        ";
    } else if ($l_sp_split == 'sptotref') {
      $r_chk_sec_x = '';
      $r_chk_sec_w = '';
      if ($ddl_users == '103') {
        $r_chk_sec_x = " (xx.remarks = 'SCR -> AOR' or xx.remarks = 'AUTO -> CAT' or xx.remarks = 'SCR -> CAT' or xx.remarks = 'SCR -> FDR')  ";
        $r_chk_sec_w = " (ww.remarks = 'SCR -> AOR' or ww.remarks = 'AUTO -> CAT' or ww.remarks = 'SCR -> CAT' or ww.remarks = 'SCR -> FDR') ";
      } elseif ($ddl_users == '108') {
        $r_chk_sec_x = " xx.remarks = 'FDR -> SCR' ";
        $r_chk_sec_w = " ww.remarks = 'FDR -> SCR' ";
      }

      $sql = "SELECT DISTINCT 
              zz.*,
              xx.diary_no d_no,
              ww.diary_no d_no2,
              mn.fil_no,
            if(SUBSTRING_INDEX(GROUP_CONCAT(xx.rece_dt ORDER BY xx.rece_dt ),',',1) is null,
            SUBSTRING_INDEX(GROUP_CONCAT(ww.rece_dt ORDER BY ww.rece_dt),',',1),
              SUBSTRING_INDEX(GROUP_CONCAT(xx.rece_dt ORDER BY xx.rece_dt ),',',1)) rece_dt
            FROM
              (SELECT 
                diary_no, 
                d_to_empid,
                r_by_empid ,disp_dt,d_by_empid
              FROM
                fil_trap 
              WHERE   $remarks1_s $emp_nm and disp_dt>='2018-06-30'
              
              UNION ALL 
              SELECT 
                diary_no, 
                d_to_empid,
                r_by_empid ,disp_dt,d_by_empid
              FROM
                fil_trap_his 
              WHERE  $remarks1_s $emp_nm and disp_dt>='2018-06-30'
              ) zz 
              LEFT JOIN fil_trap xx 
                ON xx.diary_no = zz.diary_no 
                AND $r_chk_sec_x   AND xx.disp_dt>=zz.disp_dt
              LEFT JOIN fil_trap_his ww 
                ON ww.diary_no = zz.diary_no 
                AND $r_chk_sec_w AND ww.disp_dt>=zz.disp_dt
              LEFT JOIN main mn 
                ON mn.diary_no = zz.diary_no 
                $jn_users
            WHERE (
                xx.diary_no IS  NULL 
                AND ww.diary_no IS  NULL 
                AND (mn.fil_no IS  NULL or mn.fil_no='')
              ) GROUP BY diary_no,d_to_empid,disp_dt order by disp_dt,d_by_empid";
    } else if ($l_sp_split == 'sptwd') {
      if ($r_sp_split != 0)
        $emp_nm = " and r_by_empid='$hd_nm_id'";
      $sql = "SELECT DISTINCT zz.*, 
       xx.diary_no AS d_no,
       ww.diary_no AS d_no2 $f_no,
       COALESCE(xx.remarks, ww.remarks) AS remarks,
       COALESCE(xx.d_to_empid, ww.d_to_empid) AS d_d_to_empid,
       COALESCE(xx.disp_dt, ww.disp_dt) AS d_disp_dt
FROM (
    SELECT diary_no, 
           d_to_empid,
           r_by_empid,
           disp_dt,
           d_by_empid,
           rece_dt
    FROM fil_trap
    WHERE CAST(comp_dt AS DATE) BETWEEN '$frm_dt' AND '$to_dt' $emp_nm
      $remarks 
      AND r_by_empid != 0
    UNION ALL
    SELECT diary_no, 
           d_to_empid,
           r_by_empid,
           disp_dt,
           d_by_empid,
           rece_dt
    FROM fil_trap_his
    WHERE CAST(comp_dt AS DATE) BETWEEN '$frm_dt' AND '$to_dt'
      $remarks 
      AND r_by_empid != 0
) zz
LEFT JOIN fil_trap xx 
    ON xx.diary_no = zz.diary_no 
   AND (xx.remarks = '$com_rmk' $cat_m) 
LEFT JOIN fil_trap_his ww 
    ON ww.diary_no = zz.diary_no 
    AND (ww.remarks = '$com_rmk' $cat_m1) 
$jn_mn $jn_users_r
WHERE xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL $mn_c
ORDER BY zz.disp_dt, zz.d_by_empid";
    } else if ($l_sp_split == 'sptotpenr') {
      if ($r_sp_split != 0)
        $emp_nm = " and r_by_empid='$hd_nm_id'";
      $sql = "select distinct zz.* from (SELECT 
              diary_no, 
              d_to_empid,
              r_by_empid ,disp_dt,d_by_empid,rece_dt
            FROM
              fil_trap 
            WHERE DATE(rece_dt) BETWEEN '$frm_dt' 
              AND '$to_dt'  $emp_nm
              $remarks 
              AND r_by_empid != 0 
            UNION
            ALL 
            SELECT 
              diary_no,
              d_to_empid,
              r_by_empid ,disp_dt,d_by_empid,rece_dt
            FROM
              fil_trap_his 
            WHERE DATE(rece_dt) BETWEEN '$frm_dt' 
              AND '$to_dt' $emp_nm
              $remarks 
              AND r_by_empid != 0) zz $jn_users_r
          order by disp_dt,d_by_empid";
    } else if ($l_sp_split == 'sptwdr') {
      if ($r_sp_split != 0)
        $emp_nm = " and r_by_empid='$hd_nm_id'";


      $sql = "select distinct zz.* from (SELECT 
              diary_no,
              d_to_empid,
              r_by_empid ,disp_dt,d_by_empid
            FROM
              fil_trap 
            WHERE DATE(rece_dt) BETWEEN '$frm_dt' 
              AND '$to_dt' 
            $emp_nm $remarks1 
              AND r_by_empid != 0 
            UNION
            ALL 
            SELECT 
              diary_no, 
              d_to_empid,
              r_by_empid ,disp_dt,d_by_empid
            FROM
              fil_trap_his 
            WHERE DATE(rece_dt) BETWEEN '$frm_dt' 
              AND '$to_dt' 
            $emp_nm $remarks1
              AND r_by_empid != 0) zz $jn_users_r
          order by disp_dt,d_by_empid";
    } else if ($l_sp_split == 'sptwdd') {
      if ($r_sp_split != 0)
        $emp_nm = " and r_by_empid='$hd_nm_id'";


      $r_chk_sec_x = '';
      $r_chk_sec_w = '';
      if ($ddl_users == '103') {
        $r_chk_sec_x = " (xx.remarks = 'SCR -> AOR' or xx.remarks = 'AUTO -> CAT' or xx.remarks = 'SCR -> CAT' or xx.remarks = 'SCR -> FDR') ";
        $r_chk_sec_w = " (ww.remarks = 'SCR -> AOR' or ww.remarks = 'AUTO -> CAT' or ww.remarks = 'SCR -> CAT' or ww.remarks = 'SCR -> FDR') ";
      } elseif ($ddl_users == '108') {
        $r_chk_sec_x = " xx.remarks = 'FDR -> SCR' ";
        $r_chk_sec_w = " ww.remarks = 'FDR -> SCR' ";
      }
      $sql = "SELECT 
          distinct zz.*,
            xx.diary_no d_no,
            ww.diary_no d_no2,
            mn.fil_no ,
            if(SUBSTRING_INDEX(GROUP_CONCAT(xx.rece_dt ORDER BY xx.rece_dt ),',',1) is null,
            SUBSTRING_INDEX(GROUP_CONCAT(ww.rece_dt ORDER BY ww.rece_dt),',',1),
              SUBSTRING_INDEX(GROUP_CONCAT(xx.rece_dt ORDER BY xx.rece_dt ),',',1)) rece_dt
            
          FROM
            (SELECT 
              diary_no, 
              d_to_empid,
              r_by_empid ,disp_dt,d_by_empid
            FROM
              fil_trap 
            WHERE DATE(comp_dt) BETWEEN '$frm_dt' 
              AND '$to_dt' 
            $emp_nm $remarks1 
              AND r_by_empid != 0 
            UNION
            ALL 
            SELECT 
              diary_no,
              d_to_empid,
              r_by_empid ,disp_dt,d_by_empid
            FROM
              fil_trap_his 
            WHERE DATE(comp_dt) BETWEEN '$frm_dt' 
              AND '$to_dt' 
            $emp_nm $remarks1
              AND r_by_empid != 0
              ) zz 
            LEFT JOIN fil_trap xx 
              ON xx.diary_no = zz.diary_no 
              AND $r_chk_sec_x   AND xx.disp_dt>=zz.disp_dt
            LEFT JOIN fil_trap_his ww 
              ON ww.diary_no = zz.diary_no 
              AND  $r_chk_sec_w AND ww.disp_dt>=zz.disp_dt
            LEFT JOIN main mn 
              ON mn.diary_no = zz.diary_no $jn_users_r
          WHERE (
              xx.diary_no IS NOT NULL 
              OR ww.diary_no IS NOT NULL 
              OR mn.fil_no IS NOT NULL
            )  GROUP BY diary_no,d_to_empid,disp_dt
        order by disp_dt,d_by_empid
              ";
    }
    $query = $this->db->query($sql);

    return $query->getResultArray();
  }

  public function getUserName($d_to_empid)
  {
    $builder = $this->db->table('master.users');
    $builder->select('name');
    $builder->where('empid', $d_to_empid);
    $query = $builder->get()->getRow();
    return $query ? $query->name : null;
  }

  public function getUsrNameByUserCode($userCode)
  {
    $builder = $this->db->table('master.users');
    $builder->select('name');
    $builder->where('usercode', $userCode);
    $query = $builder->get()->getRow();
    return $query ? $query->name : null;
  }

 
}
