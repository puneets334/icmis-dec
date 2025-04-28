<?php

namespace App\Models\Listing;

use CodeIgniter\Model;
use CodeIgniter\Database\RawSql;


class ReportModel extends Model
{

  public function getCaseType()
  {
    $builder = $this->db->table('master.casetype');
    $builder->select('casecode,short_description,nature,casename');
    $builder->where('display', 'Y');
    $builder->where('casecode !=',  9999);
    $builder->orderBy('CASE WHEN casecode IN (9, 10, 25, 26) THEN 1 ELSE 2 END', 'nature', 'short_description', 'ASC');
    $query = $builder->get();
    // $sql = $builder->getCompiledSelect();
    // pr($sql);
    $result = $query->getResultArray();
    return $result;
  }
  public function getSplCase()
  {
    $builder = $this->db->table('master.listing_purpose');
    $builder->where('display', 'Y');
    $builder->orderBy('priority');
    $query = $builder->get();

    $result = $query->getResultArray();
    return $result;
  }
  public function vac_reg_cl_get($list_dt,$reg_code,$sec_id){

    if($sec_id != '0'){
      $ten_sect = " tentative_section(m.diary_no) = '$sec_id' AND ";
    } else {
      $ten_sect = '';
    }
    


      $builder = $this->db->table('main m');

      $builder->select([
          'm.diary_no',
          'u.name',
          'v.list_dt',
          'v.display',
          'v.reg_jcode',
          'm.c_status',
          new RawSql("tentative_section(m.diary_no) AS section_name"),
          'm.active_fil_no',
          'm.active_reg_year',
          'm.casetype_id',
          'm.active_casetype_id',
          'm.ref_agency_state_id',
          'm.reg_no_display',
          new RawSql("EXTRACT(YEAR FROM m.fil_dt) AS fil_year"),
          'm.fil_no',
          'm.conn_key AS main_key',
          'm.fil_dt',
          'm.fil_no_fh',
          'm.reg_year_fh AS fil_year_f',
          'm.mf_active',
          'm.pet_name',
          'm.res_name',
          'pno',
          'rno',
          'm.diary_no_rec_date',
          new RawSql("CAST(RIGHT(CAST(m.diary_no AS TEXT), 4) AS INTEGER) AS order_right"),
          new RawSql("CAST(LEFT(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER) AS order_left"),
      ]);

      $builder->distinct();
      $builder->join('vacation_registrar_not_ready_cl v', 'v.diary_no = m.diary_no', 'inner');
      $builder->join('master.users u', 'u.usercode = m.dacode AND u.display = \'Y\'', 'left');

      // $builder->where(new RawSql("tentative_section(m.diary_no) = '20'"));
      $builder->where('v.display', 'Y');
      // $builder->where(new RawSql("EXTRACT(YEAR FROM v.ent_dt) = EXTRACT(YEAR FROM CURRENT_DATE)"));
      
      if($reg_code != '0'){
        $builder->where('v.reg_jcode', $reg_code);
      } 
      $builder->where('v.list_dt', $list_dt);
      $builder->where('m.c_status', 'P');

      $builder->groupBy(['m.diary_no', 'u.name', 'v.list_dt', 'v.display', 'v.reg_jcode']);
      $builder->orderBy('order_right', 'ASC');
      $builder->orderBy('order_left', 'ASC');

      $result = $builder->get()->getResultArray();
      // pr($result);
      // die();
      return $result;

  }

  public function registeredName(){
        $builder = $this->db->table('master.judge');
        $builder->select('jcode, jname, first_name, sur_name');
        $builder->like('title', '%REGISTRAR%');
        $builder->where('display', 'Y');
        $builder->whereIn('jcode', [543, 544]); // Use whereIn for OR conditions on the same column
        $builder->orderBy('jcode', 'ASC');

        $result = $builder->get()->getResultArray();
        return $result;
  }
  public function getDateSelect($list_dt, $mainhead,$lp_str, $board_type, $case_type_id_str, $act_ros)
  {

        if($lp_str == "all"){
            $lp = "";
        }
        else{
          $lp = "and h.listorder IN (".$lp_str.")";
        }
        $p_case_type = f_selected_values($_POST['case_type']);
        if($case_type_id_str == "all"){
            $case_type_id = "";
        }
        else{
          $case_type_id = "and (case when m.active_casetype_id = 0 then m.casetype_id else m.active_casetype_id end) IN (".$case_type_id_str.")";
        }    
        if($board_type == ""){
            $board_type = "";
            $act_ros = "";
        }
        else{
            $board_type = "AND h.board_type = '".$board_type."'";
            if($board_type == 'C'){
                $act_ros = "AND (mb.board_type_mb = 'C' OR mb.board_type_mb = 'CC')";
            }
            else{
                $act_ros = "AND mb.board_type_mb = '".$board_type."'";
            }
        }

        $sql ="SELECT
                    (SELECT STRING_AGG(jname, ',' ORDER BY judge_seniority) FROM master.judge WHERE jcode = ANY(string_to_array(a.jcd, ',')::int[])) AS jnm,
                    jcd,
                    STRING_AGG(diary_no::TEXT, ',') AS dno, 
                    COUNT(*) AS cnt
                FROM (
                    SELECT
                        STRING_AGG(b.j1::TEXT, ',' ORDER BY jj.judge_seniority) AS jcd,
                        m.diary_no,m.conn_key,h.next_dt,h.board_type,h.listorder,m.c_status,h.main_supp_flag,h.mainhead,b.notbef,b.j1
                    FROM main m
                    INNER JOIN heardt h ON m.diary_no = h.diary_no
                    INNER JOIN master.listing_purpose l ON l.code = h.listorder
                    LEFT JOIN master.casetype c ON m.active_casetype_id = c.casecode
                    INNER JOIN not_before b ON b.diary_no::text = h.diary_no::text
                    LEFT JOIN master.judge jj ON jj.jcode = b.j1
                  WHERE  m.c_status = 'P'
                  AND CASE WHEN l.fx_wk = 'F' THEN (h.next_dt = '$list_dt' OR h.next_dt <= CURRENT_DATE) ELSE h.next_dt <= '$list_dt' END
                  $board_type
                  $lp
                  AND h.main_supp_flag = '0'
                  AND h.mainhead = 'M'
                  AND b.notbef = 'B'
                  AND b.j1 != 0
                  $case_type_id
                  AND ((m.diary_no::text = m.conn_key::text)OR (m.conn_key::text = '0') OR (m.conn_key IS NULL))
                    GROUP BY h.diary_no, m.diary_no,m.conn_key,h.next_dt,h.board_type,h.listorder,m.c_status,h.main_supp_flag,h.mainhead,b.notbef,b.j1
                  order by h.next_dt
                ) AS a
                GROUP BY jcd";
             // die();

        $result = $this->db->query($sql)->getResultArray();
        
        $sno = 1;
        $html = '';
        foreach($result as $ro){
          $dno_exp = explode(",",$ro['dno']);
          $avl_jcd = $ro['jcd'];
          $cnt_dno_exp = count($dno_exp);
            $sno1 = $sno % 2;
            if($sno1 == '1'){ 
              $html .="<tr style='background: #ececec;'>";        
            } else { 
              $html .="<tr style='background: #f6e0f3;' >";
                   
            }         
               
            $html .="<td rowspan='".$cnt_dno_exp."' align='left' style='vertical-align: top;'>".$sno."</td>";                
            $html .="<td rowspan='".$cnt_dno_exp."' align='left' style='vertical-align: top;'>".str_replace(",", "<br>", $ro['jnm'])."</td>";
            $html .="<td rowspan='".$cnt_dno_exp."' align='left' style='vertical-align: top;'>".$ro['cnt']."</td>";
            for($i=0;$i<$cnt_dno_exp;$i++){                 
              if($i > 0){
         
                     if($sno1 == '1'){ 
                          $html .="<tr style='background: #ececec;'>";        
                         } else {
                            $html .="<tr style='background: #f6e0f3;' >";
                          }  
              }
              $reg_no_display = "";
              $builder = $this->db->table('main m');
              $builder->select('reg_no_display');
              $builder->where('m.diary_no', $dno_exp[$i]); // Parameter binding!

              $resultsmain = $builder->get();

              if (count($resultsmain->getResultArray()) > 0) {
                  $romain = $resultsmain->getRowArray();
                  $reg_no_display = $romain['reg_no_display'];
              } else {
                  $reg_no_display = null;
              }

              $padvname = ""; $radvname = "";
              $advsql = "SELECT
                              a.diary_no,
                              a.name,
                              STRING_AGG(CASE WHEN a.pet_res = 'R' THEN a.grp_adv ELSE NULL END, '' ORDER BY a.adv_type DESC, a.pet_res_no ASC) AS r_n,
                              STRING_AGG(CASE WHEN a.pet_res = 'P' THEN a.grp_adv ELSE NULL END, '' ORDER BY a.adv_type DESC, a.pet_res_no ASC) AS p_n
                          FROM (
                              SELECT
                                  a.diary_no,
                                  b.name,
                                  STRING_AGG(COALESCE(a.adv, ''), '' ORDER BY a.pet_res ASC, a.adv_type DESC, a.pet_res_no ASC) AS grp_adv,
                                  a.pet_res,
                                  a.adv_type,
                                  a.pet_res_no
                              FROM
                                  advocate a
                              LEFT JOIN
                                  master.bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y'
                              WHERE
                                  a.diary_no = '".$dno_exp[$i]."' 
                                  AND a.display = 'Y'
                              GROUP BY
                                  a.diary_no, b.name,a.pet_res,a.adv_type,a.pet_res_no,b.name
                              ORDER BY
                                  a.pet_res ASC, a.adv_type DESC, a.pet_res_no ASC
                          ) a
                          GROUP BY
                              a.diary_no,a.name";    
                $resultsadv = $this->db->query($advsql); 
               
              if (count($resultsadv->getResultArray()) > 0) {
                  $rowadv = $resultsadv->getRowArray();
                  $radvname = $rowadv['r_n'];
                  $padvname = $rowadv['p_n'];
              } else {
                  $radvname = null;
                  $padvname = null;
              }
              $padvname_str = is_null($padvname) ? "" : trim($padvname, ",");
              $radvname_str = is_null($radvname) ? "" : trim($radvname, ",");
              $html .="<td align='left' style='vertical-align: top;'>".$reg_no_display." @ ".substr_replace($dno_exp[$i], '-', -4, 0)."</td>";   
              $html .= "<td align='left' style='vertical-align: top;'>" . htmlspecialchars(str_replace(",", ", ", $padvname_str)) . " Vs " . htmlspecialchars(str_replace(",", ", ", $radvname_str)) . "</td>";
              if($i == 0){
              $html .="<td rowspan='".$cnt_dno_exp."' align='left' style='vertical-align: top;'>";
                 
                  if($mainhead == 'M'){
                      $r_mf = '1';
                  }
                  if($mainhead == 'F'){
                      $r_mf = '2';
                  }

                  $ros_sql = "SELECT a.*
                              FROM (
                                  SELECT
                                      r.id,
                                      STRING_AGG(j.jcode::text, ',' ORDER BY j.judge_seniority) AS jcd,
                                      STRING_AGG(j.jname, ',' ORDER BY j.judge_seniority) AS jnm
                                  FROM
                                      master.roster r
                                  INNER JOIN
                                      master.roster_bench rb ON rb.id = r.bench_id
                                  INNER JOIN
                                      master.master_bench mb ON mb.id = rb.bench_id
                                  LEFT JOIN
                                      master.roster_judge rj ON rj.roster_id = r.id
                                  LEFT JOIN
                                      master.judge j ON j.jcode = rj.judge_id
                                  WHERE
                                      j.is_retired != 'Y'
                                      AND j.display = 'Y'
                                      AND rj.display = 'Y'
                                      AND rb.display = 'Y'
                                      AND mb.display = 'Y'
                                      AND mb.board_type_mb = 'J'
                                      AND r.display = 'Y'
                                      AND r.m_f = '".$r_mf."'  
                                      AND '$list_dt' BETWEEN r.from_date AND r.to_date 
                                  GROUP BY
                                      r.id
                              ) a
                              WHERE
                                  a.jcd = '".$avl_jcd."'";    
                            $rs_ros_sql = $this->db->query($ros_sql); 
                          $roster_id = '200';
                          $partno = '1';
                          if (count($rs_ros_sql->getResultArray()) > 0) {
                              $row_ros = $rs_ros_sql->getRowArray();
                              $roster_id = $row_ros['id'];

                            if ($board_type == 'C') {
                                $partno = 50;
                            } else {
                                $partno = 1;
                            }
                          //}
                        
                                $html .="Part <select class='ele' name='clno_".$roster_id."' id='clno_". $roster_id."'>";                            
                                    
                                    for($j=1;$j<=100;$j++){
                                       
                                    $html .="<option value='".$j."' if($partno == $j){ 'selected=selected' } >".$j."</option>";
                                   
                                    }
                                   
                                $html .="</select>";
                                $html .="<br/>";
                                $html .=" M/S <select class='ele' name='main_suppl_".$roster_id."' id='main_suppl_". $roster_id."'>";                            
                                    $html .="<option value='1'>Main</option>"; 
                                    $html .="<option value='2'>Suppl.</option>";            
                                    $html .="</select>"; 
                                $html .="<input type='button' name='lstbtn' id='lstbtn' value='Submit' onClick='javascript:lst_casesdf(".$roster_id.")'/>"; 
                                $html .="<input type='hidden' value='".$list_dt."' id='ldt_".$roster_id."' name='ldt_".$roster_id."'/>"; 
                                $html .="<input type='hidden' value='".$mainhead."' id='mf_".$roster_id."' name='mf_".$roster_id."'/>"; 
                                $html .="<input type='hidden' value='".$avl_jcd."' id='avlj_".$roster_id."' name='avlj_".$roster_id."'/>"; 
                                $html .="<input type='hidden' value='".$ro['dno']."' id='avldno_".$roster_id."' name='avldno_".$roster_id."'/>"; 
                            } 
                                                     
                            }
        
                        $html .="</td>"; 
                        
                       
                        }
                        if($i > 0){
                                      
                                $html .="</tr>"; 
                           
                            }
                            $sno++;
        }
        
    return $html;
  }
  public function listing_matter($first_date, $to_date)
  {
    $subquery1 = $this->db->table('heardt')
      ->select("date(next_dt) AS next_dt, diary_no, board_type")
      ->where("(conn_key = 0 OR diary_no = conn_key)")
      ->whereIn('main_supp_flag', [1, 2])
      ->where('next_dt >=', $first_date)
      ->where('next_dt <=', $to_date)
      ->getCompiledSelect();

    $subquery2 = $this->db->table('last_heardt')
      ->select("date(next_dt) AS next_dt, diary_no, board_type")
      ->where("(conn_key = 0 OR diary_no = conn_key)")
      ->whereIn('main_supp_flag', [1, 2])
      ->where('bench_flag', '')
      ->where('date(next_dt) >=', $first_date)
      ->where('date(next_dt) <=', $to_date)
      ->getCompiledSelect();

    $unionQuery = "($subquery1) UNION ($subquery2)";


    $builder = $this->db->query("
    SELECT 
        date(next_dt) AS date1,
        SUM(CASE WHEN board_type = 'R' THEN 1 ELSE 0 END) AS reg,
        SUM(CASE WHEN board_type = 'J' THEN 1 ELSE 0 END) AS court,
        SUM(CASE WHEN board_type = 'C' THEN 1 ELSE 0 END) AS chamber
    FROM ($unionQuery) AS a
    GROUP BY date(next_dt)
");
    
    $result = $builder->getResultArray();
    return $result;
  }
  public function listed_detail($date, $flag)
  {
             
    $sql = "SELECT 
              CONCAT(
                SUBSTRING(h.diary_no::TEXT, 1, LENGTH(h.diary_no::TEXT) - 4), 
                '/', 
                SUBSTRING(h.diary_no::TEXT, LENGTH(h.diary_no::TEXT) - 3, 4)
              ) AS diary_no, 
              reg_no_display, 
              CONCAT(pet_name, ' Vs ', res_name) AS title, 
              DATE(h.next_dt) AS date1 
            FROM 
              heardt h 
              LEFT OUTER JOIN main m ON h.diary_no = m.diary_no 
            WHERE 
              (
                h.conn_key = 0 
                OR h.diary_no = h.conn_key
              ) 
              AND h.main_supp_flag IN (1, 2) 
              AND h.board_type = '$flag'
              AND DATE(h.next_dt) = '$date'

            UNION

            SELECT 
              CONCAT(
                SUBSTRING(lh.diary_no::TEXT, 1, LENGTH(lh.diary_no::TEXT) - 4), 
                '/', 
                SUBSTRING(lh.diary_no::TEXT, LENGTH(lh.diary_no::TEXT) - 3, 4)
              ) AS diary_no, 
              reg_no_display, 
              CONCAT(pet_name, ' Vs ', res_name) AS title, 
              DATE(lh.next_dt) AS date1 
            FROM 
              last_heardt lh 
              LEFT OUTER JOIN main m ON lh.diary_no = m.diary_no 
            WHERE 
              (
                lh.conn_key = 0 
                OR lh.diary_no = lh.conn_key
              ) 
              AND lh.bench_flag = '' 
              AND lh.main_supp_flag IN (1, 2) 
              AND lh.board_type = '$flag'
              AND DATE(lh.next_dt) = '$date'
            ORDER BY 
              diary_no";
    $query = $this->db->query($sql);
    $result = $query->getResultArray();
    return $result;
  }

  public function defective_cases_stats($date)
  {
    $builder = $this->db->table('defect_case_list_26032019 dc');
    $builder->select("ROW_NUMBER() OVER (ORDER BY dc.next_dt) AS SNO, TO_CHAR(dc.next_dt, '{$date}') AS next_dt, COUNT(dc.diary_no) AS listed, 
    SUM(CASE WHEN m.c_status = 'D' THEN 1 ELSE 0 END) AS disposed, SUM(CASE WHEN m.c_status = 'P' THEN 1 ELSE 0 END) AS pending");
    $builder->join('main m', 'm.diary_no = dc.diary_no', 'INNER');
    $builder->groupBy('dc.next_dt');
    $builder->orderBy('dc.next_dt', 'ASC');

    $rows = $builder->get();
    $result = $rows->getResultArray();

    return $result;
  }
  public function defectove_un_not_listed()
  {
    $builder = $this->db->table('main');
    $builder->where('c_status', 'P')
      ->groupStart()
      ->where('fil_no IS NULL')
      ->orWhere('fil_no', '')
      ->orWhere('fil_no', '0')
      ->groupEnd()
      ->where('lldt(diary_no::integer) IS NULL');
    $builder->selectCount('*', 'total');
    //  echo $builder->getCompiledSelect();
    // die();
    $query = $builder->get();
    $result = $query->getRowArray();
    return $result;
  }
  public function defect_notified_not_listed()
  {
    $builder = $this->db->table('main m');
    $builder->join('obj_save o', 'm.diary_no = o.diary_no', 'inner');
    $builder->join('(select diary_no from docdetails where display = \'Y\' and doccode = 8 and doccode1 = 226) d', 'm.diary_no = d.diary_no', 'left');
    $builder->where('m.c_status', 'P')
      ->groupStart()
      ->where('m.fil_no IS NULL')
      ->orWhere('m.fil_no', '')
      ->orWhere('m.fil_no', '0')
      ->groupEnd()
      ->where('lldt(d.diary_no::integer) IS NULL')
      ->where('d.diary_no IS NULL');
    $builder->selectCount('m.diary_no', 'total')->distinct();
    // echo $builder->getCompiledSelect();
    // die();
    $query = $builder->get();
    $result = $query->getRowArray();
    return $result;
  }

  public function refiled_dealy_more_than_1_year()
  {
    $builder = $this->db->table('main m');
   // $builder->select('m.diary_no', 'total');
    $builder->join('(SELECT diary_no, MIN(save_dt) AS save_dt FROM obj_save GROUP BY diary_no) o', 'm.diary_no = o.diary_no');
    $builder->join('(SELECT * FROM docdetails WHERE display = \'Y\' AND doccode = 8 AND doccode1 = 226) d', 'm.diary_no = d.diary_no', 'left');
    $builder->where('m.c_status', 'P')
      ->groupStart()
      ->where('m.fil_no IS NULL')
      ->orWhere('m.fil_no', '')
      ->orWhere('m.fil_no', '0')
      ->groupEnd()
      ->where('lldt(d.diary_no::integer) IS NULL')
      ->where('d.diary_no IS NULL')
      ->groupStart()
      ->where('CURRENT_DATE - o.save_dt >=', 'INTERVAL \'365 days\'', false) // Use raw SQL for INTERVAL
      ->orWhere('o.save_dt IS NULL')
      ->groupEnd();
    $builder->selectCount('m.diary_no','total')->distinct();
    $query = $builder->get();
    $result = $query->getRowArray();
    return $result;
  }
  public function refiled_dealy_less_than_1_year()
  {
    $builder = $this->db->table('main m');
    $subquery1 = '(SELECT diary_no, MIN(save_dt) AS save_dt FROM obj_save GROUP BY diary_no) o';
    $subquery2 = '(SELECT * FROM docdetails WHERE display = \'Y\' AND doccode = 8 AND doccode1 = 226) d';
    $builder->join($subquery1, 'm.diary_no = o.diary_no', 'inner')
      ->join($subquery2, 'm.diary_no = d.diary_no', 'left');
    $builder->where('m.c_status', 'P')
      ->groupStart() 
      ->where('m.fil_no IS NULL')
      ->orWhere('m.fil_no', '')
      ->orWhere('m.fil_no', '0')
      ->groupEnd()
      ->where('lldt(d.diary_no::integer) IS NULL') // Use false to prevent automatic escaping
      ->where('d.diary_no IS NULL')
      ->where('CURRENT_DATE - o.save_dt <', 'INTERVAL \'365 days\'', false); // Use false for raw SQL
    $builder->selectCount('m.diary_no','total')->distinct();
   
    $query = $builder->get();
    $result = $query->getRowArray();
    return $result;
  }
  public function defect_not_notified_not_listed()
  {
    $sql = "SELECT COUNT(DISTINCT diary_no) AS total
              FROM (
                  SELECT DISTINCT m.diary_no
                  FROM main m
                  LEFT JOIN (SELECT diary_no FROM obj_save GROUP BY diary_no) o ON m.diary_no = o.diary_no
                  WHERE m.c_status = 'P'
                    AND (m.fil_no IS NULL OR m.fil_no = '' OR m.fil_no = '0')
                    AND o.diary_no IS NULL
                    AND lldt(m.diary_no::integer) IS NULL

                  UNION ALL -- Use UNION ALL for better performance if duplicates don't matter

                  SELECT DISTINCT m.diary_no
                  FROM main m
                  JOIN (SELECT diary_no FROM obj_save WHERE rm_dt IS NOT NULL GROUP BY diary_no) o ON m.diary_no = o.diary_no
                  WHERE m.c_status = 'P'
                    AND (m.fil_no IS NULL OR m.fil_no = '' OR m.fil_no = '0')
                    AND o.diary_no IS NOT NULL
                    AND lldt(m.diary_no::integer) IS NULL
              ) AS aa";
        $query = $this->db->query($sql);
        $result = $query->getRowArray();
        $total = $result['total'] ?? 0;
        return $total;
  }
  // public function get_roster_j_c_old($from_dt)
  // {
  //   $sql = "SELECT 
  //           ro.session,
  //           ro.frm_time,
  //           mb.board_type_mb,
  //           ro.courtno,
  //           STRING_AGG(j.jcode::TEXT, ',' ORDER BY j.judge_seniority) AS jcd,
  //           STRING_AGG(j.jname, ',' ORDER BY j.judge_seniority) AS jnm,
  //           r.id,
  //           r.jcd
  //               FROM (
  //                   SELECT 
  //                       h.roster_id AS id,
  //                       h.judges AS jcd
  //                   FROM heardt h
  //                   WHERE h.next_dt = '$from_dt'
  //                     AND h.mainhead = 'M'
  //                     AND h.board_type = 'J'
  //                     AND h.roster_id > 0
  //                     AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
  //                   GROUP BY h.roster_id, h.judges
  //               ) r
  //               LEFT JOIN master.roster ro 
  //                   ON ro.id = r.id
  //               LEFT JOIN master.roster_judge rj 
  //                   ON rj.roster_id = r.id
  //               LEFT JOIN master.roster_bench rb 
  //                   ON rb.id = ro.bench_id
  //               LEFT JOIN master.master_bench mb 
  //                   ON mb.id = rb.bench_id
  //               LEFT JOIN master.judge j 
  //                   ON j.jcode = rj.judge_id
  //               WHERE 
  //                   j.is_retired != 'Y'
  //                   AND j.display = 'Y'
  //                   AND rj.display = 'Y'
  //                   AND mb.board_type_mb = 'J'
  //                   AND ro.courtno > 0
  //               GROUP BY 
  //                   ro.session,
  //                   ro.frm_time,
  //                   mb.board_type_mb,
  //                   ro.courtno,
  //                   r.id,
  //                   r.jcd
  //               ORDER BY 
  //                   ro.courtno, 
  //                   r.id";
  //   $query = $this->db->query($sql);
  //   $result = $query->getResultArray();
  //   return $result;
  // }

  public function get_roster_j_c($from_dt)
{
    // Subquery to get roster details
    $subQuery = $this->db->table('heardt h')
        ->select('h.roster_id AS id, h.judges AS jcd')
        ->where('h.next_dt', $from_dt)
        ->where('h.mainhead', 'M')
        ->where('h.board_type', 'J')
        ->where('h.roster_id >', 0)
        ->groupBy('h.roster_id, h.judges');

    // Main query
    $builder = $this->db->table('(' . $subQuery->getCompiledSelect() . ') r', false);
    $builder->select('
        ro.session,
        ro.frm_time,
        mb.board_type_mb,
        ro.courtno,
        STRING_AGG(j.jcode::TEXT, \',\' ORDER BY j.judge_seniority) AS jcd,
        STRING_AGG(j.jname, \',\' ORDER BY j.judge_seniority) AS jnm,
        r.id,
        r.jcd
    ')
    ->join('master.roster ro', 'ro.id = r.id', 'left')
    ->join('master.roster_judge rj', 'rj.roster_id = r.id', 'left')
    ->join('master.roster_bench rb', 'rb.id = ro.bench_id', 'left')
    ->join('master.master_bench mb', 'mb.id = rb.bench_id', 'left')
    ->join('master.judge j', 'j.jcode = rj.judge_id', 'left')
    ->where('j.is_retired !=', 'Y')
    ->where('j.display', 'Y')
    ->where('rj.display', 'Y')
    ->where('mb.board_type_mb', 'J')
    ->where('ro.courtno >', 0)
    ->groupBy('
        ro.session,
        ro.frm_time,
        mb.board_type_mb,
        ro.courtno,
        r.id,
        r.jcd
    ')
    ->orderBy('ro.courtno')
    ->orderBy('r.id');

    // Execute the query
    $query = $builder->get();
    return $query->getResultArray();
}

 


  public function showHeardt($fromDate, $toDate)
  {
    $subQuery = $this->db->table('heardt')
      ->select('DATE(ent_dt) AS ent_dt, module_id, COUNT(*) AS total')
      ->where('DATE(ent_dt) >=', '2017-05-08')
      ->where('DATE(ent_dt) >=', $fromDate)
      ->where('DATE(ent_dt) <=', $toDate)
      ->groupBy('DATE(ent_dt), module_id')
      ->getCompiledSelect();
    $finalQuery = "
    SELECT ent_dt, 
           SUM(total) AS total,
           SUM(CASE WHEN module_id = 2 THEN total ELSE 0 END) AS mod2,
           SUM(CASE WHEN module_id = 3 THEN total ELSE 0 END) AS mod3,
           SUM(CASE WHEN module_id = 4 THEN total ELSE 0 END) AS mod4,
           SUM(CASE WHEN module_id = 5 THEN total ELSE 0 END) AS mod5,
           SUM(CASE WHEN module_id = 7 THEN total ELSE 0 END) AS mod7,
           SUM(CASE WHEN module_id = 8 THEN total ELSE 0 END) AS mod8,
           SUM(CASE WHEN module_id = 9 THEN total ELSE 0 END) AS mod9,
           SUM(CASE WHEN module_id = 10 THEN total ELSE 0 END) AS mod10,
           SUM(CASE WHEN module_id = 12 THEN total ELSE 0 END) AS mod12,
           SUM(CASE WHEN module_id = 14 THEN total ELSE 0 END) AS mod14,
           SUM(CASE WHEN module_id = 16 THEN total ELSE 0 END) AS mod16,
           SUM(CASE WHEN module_id = 17 THEN total ELSE 0 END) AS mod17,
           SUM(CASE WHEN module_id = 18 THEN total ELSE 0 END) AS mod18,
           SUM(CASE WHEN module_id = 20 THEN total ELSE 0 END) AS mod20,
           SUM(CASE WHEN module_id = 21 THEN total ELSE 0 END) AS mod21,
           SUM(CASE WHEN module_id = 22 THEN total ELSE 0 END) AS mod22,
           SUM(CASE WHEN module_id = 23 THEN total ELSE 0 END) AS mod23,
           SUM(CASE WHEN module_id = 24 THEN total ELSE 0 END) AS mod24,
           SUM(CASE WHEN (module_id = 99 OR module_id = 0 OR module_id IS NULL) THEN total ELSE 0 END) AS mod99
    FROM ($subQuery) AS z
    GROUP BY ent_dt
    ORDER BY ent_dt
";

    $query = $this->db->query($finalQuery);
    $result = $query->getResultArray();
    return $result;
  }
  public function showUsers($date,$module)
    {
      
        $builder = $this->db->table('heardt h'); // Alias the table

        $builder->select('u.name, h.module_id, ut.type_name, u.empid, us.section_name, COUNT(*) AS count, mm.module_desc');

        $builder->join('master.users u', 'h.usercode = u.usercode');
        $builder->join('master.usersection us', 'u.section = us.id');
        $builder->join('master.master_module mm', 'mm.id = h.module_id');
        $builder->join('master.usertype ut', 'u.usertype = ut.id');

        $builder->where('DATE(h.ent_dt)', $date); // Use parameterized query
        $builder->where('h.module_id', $module);

        $builder->groupBy('u.empid, u.name, h.module_id, ut.type_name, us.section_name, mm.module_desc');

        $query = $builder->get();
        return $query->getResultArray();

    }
  public function get_cat_judge($mainhead, $list_dt)
  {
    $cldt =  date('Y-m-d', strtotime($list_dt));
    $subqueryA = $this->db->table('master.submaster')
      ->select('id, sub_name1, subcode1')
      ->where('flag_use', 'S')
      ->orWhereIn('subcode1', [19, 20, 21, 22, 23])
      ->where('display', 'Y')
      ->where('flag', 's')
      ->where('subcode1 !=', 8888)
      ->groupBy('sub_name1, id, subcode1');

    // Subquery for master.roster
    $subqueryB = $this->db->table('master.roster r');
    $subqueryB->select('rj.judge_id, r.courtno, ss.sub_name1, ss.subcode1');
    $subqueryB->join('category_allottment c', 'c.ros_id = r.id');
    $subqueryB->join('master.roster_judge rj', 'rj.roster_id = r.id');
    $subqueryB->join('master.submaster s', 's.id = c.submaster_id');
    $subqueryB->join('master.submaster ss', 'ss.subcode1 = s.subcode1', 'left');
    $subqueryB->where('ss.display', 'Y');
    $subqueryB->where('rj.display', 'Y');
    $subqueryB->where('s.display', 'Y');
    $subqueryB->where('c.display', 'Y');
    $subqueryB->where('r.display', 'Y');
    if ($mainhead == 'M') {
      $subqueryB->where('r.m_f', '1');
    } elseif ($mainhead == 'L') {
      $subqueryB->where('r.m_f', '3');
      $subqueryB->where('r.from_date', $cldt);
    } elseif ($mainhead == 'S') {
      $subqueryB->where('r.m_f', '4');
      $subqueryB->where('r.from_date', $cldt);
    }
    else {
      $subqueryB->where('r.m_f', '2');
      $subqueryB->where('r.from_date', $cldt);
    }
   
    // Main Query
    $query = $this->db->table('(' . $subqueryA->getCompiledSelect() . ') a')
      ->select('a.*, b.courtno, b.judge_id')
      ->join('(' . $subqueryB->getCompiledSelect() . ') b', 'b.sub_name1 = a.sub_name1', 'left')
      ->groupBy('a.sub_name1, b.courtno, b.judge_id, a.id, a.subcode1');

    $finalQuery = $this->db->table('(' . $query->getCompiledSelect() . ') c')
      ->select('c.sub_name1, c.id, c.subcode1, STRING_AGG(c.courtno::text, \',\') AS cno, STRING_AGG(c.judge_id::text, \',\') AS judge')
      ->groupBy('c.sub_name1, c.id, c.subcode1')
      ->orderBy('subcode1')
      ->orderBy('sub_name1');
      // echo $finalQuery->getCompiledSelect();
      // die();

    $result = $finalQuery->get()->getResultArray();

    return $result;
  }
  public function causelist_info($courtNo, $itemNo)
  {
        $date = date('Y-m-d');
        $builder = $this->db->table('heardt hd');
        $builder->select('bd.remark AS remark, m.reg_no_display, hd.brd_slno, r.courtno, hd.next_dt,
            CASE WHEN (hd.diary_no = hd.conn_key AND hd.conn_key IS NOT NULL) THEN \'M\' ELSE \'C\' END AS conn,
            CASE
                WHEN hd.board_type = \'C\' THEN \'Chamber\'
                WHEN hd.board_type = \'J\' THEN \'Judge\'
                WHEN hd.board_type = \'R\' THEN \'Registrar\'
                ELSE NULL
            END AS list,
            m.pet_name || \' Vs \' || m.res_name AS cause_title,
            \'[\' || b.aor_code || \'] \' || b.name || \'(\' || adv.pet_res || \')\' AS advocates'); // Corrected pet_res reference

        $builder->distinct();
        $builder->join('brdrem bd', 'cast(hd.diary_no as bigint) = bd.diary_no::bigint', 'left');
        $builder->join('main m', 'hd.diary_no = m.diary_no', 'left');
        $builder->join('master.roster_judge rj', 'hd.roster_id = rj.roster_id', 'left');
        $builder->join('master.roster r', 'rj.roster_id = r.id', 'left');
        $builder->join('advocate adv', 'hd.diary_no = adv.diary_no', 'left');
        $builder->join('master.bar b', 'adv.advocate_id = b.bar_id', 'left');

        $builder->where('hd.next_dt', $date);
        $builder->where('hd.brd_slno', $itemNo);
        $builder->where('r.courtno', $courtNo);
        $builder->limit(1000);

        $subquery = $builder->getCompiledSelect();

        $builder2 = $this->db->table("({$subquery}) a");
        $builder2->select('remark, reg_no_display, brd_slno, conn, list, cause_title, string_agg(advocates, \',\') AS advocates');
        $builder2->groupBy('reg_no_display, remark, brd_slno, conn, list, cause_title');
        $builder2->orderBy('conn', 'DESC');
        $query = $builder2->get();
        return $query->getResultArray();
  }
  public function sensitive_listed_get($from_date, $to_date)
  {
    $subquery1 = $this->db->table('heardt t1')
      ->select("
        s.reason, 
        t1.diary_no, 
        t1.next_dt, 
        t1.roster_id, 
        t1.coram, 
        t1.judges, 
        t1.mainhead, 
        t1.board_type, 
        t1.subhead, 
        t1.clno, 
        t1.brd_slno, 
        t1.main_supp_flag
    ")
      ->join('sensitive_cases s', 's.diary_no = t1.diary_no', 'inner')
      ->where('s.display', 'Y')
      ->where("cast(t1.next_dt as date) BETWEEN '$from_date' AND '$to_date'")
      ->groupStart()
      ->where('t1.main_supp_flag', 1)
      ->orWhere('t1.main_supp_flag', 2)
      ->groupEnd();

        $subquery2 = $this->db->table('last_heardt t1')
          ->select("
            s.reason, 
            t1.diary_no, 
            t1.next_dt, 
            t1.roster_id, 
            t1.coram, 
            t1.judges, 
            t1.mainhead, 
            t1.board_type, 
            t1.subhead, 
            t1.clno, 
            t1.brd_slno, 
            t1.main_supp_flag
        ")
          ->join('sensitive_cases s', 's.diary_no = t1.diary_no', 'inner')
          ->where('s.display', 'Y')
          ->where("t1.next_dt BETWEEN '$from_date' AND '$to_date'")
          ->groupStart()
          ->where('t1.bench_flag', '')
          ->orWhere('t1.bench_flag IS NULL')
          ->groupEnd();

          // Combine the two subqueries using UNION
          $subquery = "({$subquery1->getCompiledSelect()}) UNION ({$subquery2->getCompiledSelect()})";

          // Main query
          $builder = $this->db->table("($subquery) AS h");
          $builder->select("
          cl.next_dt AS is_published, 
          m.reg_no_display, 
          m.pet_name, 
          m.res_name, 
          STRING_AGG(j.jname, ', ' ORDER BY j.judge_seniority) AS judge_name, 
          COALESCE(
              (
                  SELECT STRING_AGG(jname, ', ' ORDER BY judge_seniority) 
                  FROM master.judge 
                  WHERE jtype = 'j' 
                  AND display = 'Y' 
                  AND jcode::text = ANY(string_to_array(h.coram, ','))
              ), 
              ''
          ) AS coram, 
          h.*
      ")
      ->join('cl_printed cl', "cl.next_dt = h.next_dt AND cl.m_f = h.mainhead AND cl.part = h.clno AND cl.main_supp = h.main_supp_flag AND cl.roster_id = h.roster_id AND cl.display = 'Y'", 'left')
      ->join('main m', 'm.diary_no = h.diary_no', 'inner')
      ->join('master.roster r', 'r.id = h.roster_id', 'inner')
      ->join('master.roster_judge rj', 'rj.roster_id = r.id', 'inner')
      ->join('master.judge j', 'j.jcode = rj.judge_id', 'inner')
      ->where('r.display', 'Y')
      ->where('rj.display', 'Y')
      ->groupStart()
      ->where('CAST(m.diary_no AS char) = m.conn_key')
      ->orWhere('m.conn_key', '0')
      ->orWhere('m.conn_key', '')
      ->orWhere('m.conn_key IS NULL')
      ->groupEnd()
      ->groupBy('h.diary_no, h.next_dt, m.reg_no_display, m.pet_name, m.res_name, cl.next_dt, h.coram, h.reason, h.roster_id, h.judges, h.mainhead, h.board_type, h.subhead, h.clno, h.brd_slno, h.main_supp_flag, r.courtno')
      ->orderBy('h.next_dt')
      ->orderBy('h.brd_slno')
      ->orderBy('r.courtno');
    // echo $sql = $builder->getCompiledSelect();
    // die();
      $result = $builder->get()->getResultArray();
    
    //AND (m.diary_no = m.conn_key OR m.conn_key = 0 OR m.conn_key = '' OR m.conn_key IS NULL)
    $arr_result['title'] = 'Sensitive Cases listed : between ' . date("Y-m-d", strtotime($from_date)) . ' and ' . date("Y-m-d", strtotime($to_date)) . '';
    if (isset($result)) {
      $i = 1;
      foreach($result as $ro){
        if (empty($ro['reg_no_display'])) {
          $case_no = 'Diary No. ' . substr_replace($ro['diary_no'], ' of ', -4, 0);
      } else {
          $case_no = $ro['reg_no_display'];
      }
        
        $arr_result['data'][] = [
          'Item_No' => $ro['brd_slno'],
          'Case_No' => $case_no,
          'Cause_Title' => $ro['pet_name'] . ' Vs. ' . $ro['res_name'],
          'List_Date' => date("d-m-Y", strtotime($ro['next_dt'])),
          'Coram' => $ro['coram'],
          'Listed_Before' => $ro['judge_name'],
          'Sensitive_Reason' => $ro['reason'],
          'List_Status' => $ro['is_published'] == null ? ' ' : '<span class="text-success">Published</span>'
      ];
      $i++;
      }
    }
    else{
      $arr_result['data'][] = [];
    }
      return  json_encode($arr_result);
  }

  public function ntl_judge()
  {
    $builder = $this->db->table('master.ntl_judge n');
    $query = $builder
      ->select('jname, b.name, b.aor_code')
      ->join('master.judge j', 'n.org_judge_id = j.jcode', 'inner')
      ->join('master.bar b', 'n.org_advocate_id = b.bar_id', 'inner')
      ->where('n.display', 'Y')
      ->where('j.is_retired !=', 'Y')
      ->orderBy('j.judge_seniority')
      ->orderBy('b.name')
      ->get();
    $result = $query->getResultArray();
    return $result;
  }

  public function vacation_advance_list()
  {
    $sql = "SELECT * FROM (
            SELECT
                CONCAT(m.reg_no_display, ' @ ', SUBSTRING(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4), ' / ', SUBSTRING(m.diary_no::text, -4)) AS case_no,
                CONCAT(m.pet_name, ' Vs. ', m.res_name) AS cause_title,
                TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS filing_date,
                CASE WHEN h.main_supp_flag = 0 THEN 'Ready' ELSE 'Not Ready' END AS status,
                CASE WHEN m.mf_active = 'F' THEN 'Regular' ELSE 'Misc.' END AS casestage,
                CASE WHEN (m.diary_no::text = m.conn_key OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL) THEN 'M' ELSE 'C' END AS main_or_connected,
                CASE WHEN (s.category_sc_old IS NOT NULL AND s.category_sc_old != '' AND s.category_sc_old != '0' AND s.category_sc_old ~ '^[0-9]+$') THEN CONCAT('(', s.category_sc_old, ') ', s.sub_name1, ' - ', s.sub_name4) ELSE CONCAT('(', s.subcode1, s.subcode2, ') ', s.sub_name1, ' - ', s.sub_name4) END AS subject_category,
                TO_CHAR(h.next_dt, 'DD-MM-YYYY') AS next_date,
                Tentative_section(m.diary_no) AS section,
                m.diary_no_rec_date
            FROM heardt h
            INNER JOIN main m ON h.diary_no = m.diary_no
            INNER JOIN mul_category mcat ON h.diary_no = mcat.diary_no
            INNER JOIN master.submaster s ON mcat.submaster_id = s.id
            LEFT JOIN master.users u ON u.usercode = m.dacode AND (u.display = 'Y' OR u.display IS NULL)
            LEFT JOIN master.usersection us ON us.id = u.section
            WHERE m.c_status = 'P'
              AND mcat.display = 'Y'
              AND s.display = 'Y'
              AND m.mf_active = 'F'
              AND h.subhead != 818
              AND mcat.submaster_id != 911
              AND s.id NOT IN (
                SELECT id
                FROM master.submaster
                WHERE (
                    s.category_sc_old ~ '^[0-9]+$' AND s.category_sc_old::int BETWEEN 301 AND 324 OR
                    s.category_sc_old ~ '^[0-9]+$' AND s.category_sc_old::int BETWEEN 401 AND 436 OR
                    s.category_sc_old ~ '^[0-9]+$' AND s.category_sc_old::int BETWEEN 801 AND 818 OR
                    s.category_sc_old ~ '^[0-9]+$' AND s.category_sc_old::int BETWEEN 1001 AND 1010 OR
                    s.category_sc_old ~ '^[0-9]+$' AND s.category_sc_old::int IN (1401, 1413, 1424) OR
                    s.category_sc_old ~ '^[0-9]+$' AND s.category_sc_old::int BETWEEN 1803 AND 1816 OR
                    s.category_sc_old ~ '^[0-9]+$' AND s.category_sc_old::int IN (1818, 1900, 2000, 2100, 2200, 2300, 2401, 2811, 3700) OR
                    s.category_sc_old ~ '^[0-9]+$' AND s.category_sc_old::int BETWEEN 2403 AND 2407 OR
                    s.category_sc_old ~ '^[0-9]+$' AND s.category_sc_old::int BETWEEN 2501 AND 2504 OR
                    s.category_sc_old ~ '^[0-9]+$' AND s.category_sc_old::int BETWEEN 3001 AND 3004 OR
                    s.category_sc_old ~ '^[0-9]+$' AND s.category_sc_old::int BETWEEN 4001 AND 4003 OR
                    subcode1 IN (44, 45, 46, 47)
                )
              )
              AND m.diary_no::text NOT IN (SELECT diary_no FROM not_before WHERE res_id = 11)
              AND m.diary_no_rec_date < '2014-01-01'
              AND h.main_supp_flag IN (0)
              AND h.board_type = 'J'

            UNION

            SELECT
                CONCAT(m.reg_no_display, ' @ ', SUBSTRING(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4), ' / ', SUBSTRING(m.diary_no::text, -4)) AS case_no,
                CONCAT(m.pet_name, ' Vs. ', m.res_name) AS cause_title,
                TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS filing_date,
                CASE WHEN h.main_supp_flag = 0 THEN 'Ready' ELSE 'Not Ready' END AS status,
                CASE WHEN m.mf_active = 'F' THEN 'Regular' ELSE 'Misc.' END AS casestage,
                CASE WHEN (m.diary_no::text = m.conn_key OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL) THEN 'M' ELSE 'C' END AS main_or_connected,
                CASE WHEN (s.category_sc_old IS NOT NULL AND s.category_sc_old != '' AND s.category_sc_old != '0' AND s.category_sc_old ~ '^[0-9]+$') THEN CONCAT('(', s.category_sc_old, ') ', s.sub_name1, ' - ', s.sub_name4) ELSE CONCAT('(', s.subcode1, s.subcode2, ') ', s.sub_name1, ' - ', s.sub_name4) END AS subject_category,
                TO_CHAR(h.next_dt, 'DD-MM-YYYY') AS next_date,
                Tentative_section(m.diary_no) AS section,
                m.diary_no_rec_date
            FROM heardt h
            INNER JOIN main m ON h.diary_no = m.diary_no
            INNER JOIN mul_category mcat ON h.diary_no = mcat.diary_no
            INNER JOIN master.submaster s ON mcat.submaster_id = s.id
            LEFT JOIN master.users u ON u.usercode = m.dacode AND (u.display = 'Y' OR u.display IS NULL)
            LEFT JOIN master.usersection us ON us.id = u.section
            WHERE m.c_status = 'P'
              AND mcat.display = 'Y'
              AND s.display = 'Y'
              AND h.next_dt BETWEEN '2024-09-18' AND '2024-09-27'
              AND h.listorder IN (4, 5, 7, 8)
              AND m.mf_active = 'F'
              AND h.board_type = 'J'
        ) AS final
        WHERE main_or_connected = 'M'
        ORDER BY final.diary_no_rec_date ASC";
    // echo $sql; die;
    $query = $this->db->query($sql);
    $result = $query->getResultArray();
    return $result;
  }
  public function get_elemination_transfer($list_dt,$sec_id)
  {
    if($sec_id == "0"){
      $sec_id = "";
      $sec_id2 = "";
      $section = "";
    }        
    else
    {     
      $sql_sec_name = $this->section_name_by_id($sec_id); 
      $sec_name= $sql_sec_name[0]['section_name'];
      $sec_id = " and (us.id ='".$sec_id."'  or tentative_section(m.diary_no) = '$sec_name' )";
      $sec_id2 = "AND us.id is not null";
      $section= " where section_name='$sec_name' ";         
    }
    $sql = "SELECT *
            FROM (
                SELECT DISTINCT
                    m.diary_no,
                tt.next_dt_old,
                    tt.listorder_new,
                    tt.next_dt_new,
                    u.name,
                    CASE
                        WHEN us.section_name IS NOT NULL THEN us.section_name
                        ELSE tentative_section(m.diary_no)
                    END AS section_name,
                    m.conn_key AS main_key,
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
                    pno,
                    rno,
                    m.diary_no_rec_date,
                    CASE
                        WHEN (tt.diary_no = tt.conn_key OR tt.conn_key = 0 OR tt.conn_key IS NULL) THEN 0
                        ELSE 1
                    END AS main_or_connected,
                    (SELECT CASE WHEN diary_no IS NOT NULL THEN 1 ELSE 0 END FROM conct WHERE diary_no = m.diary_no AND list = 'Y') AS listed
                FROM main m
                INNER JOIN transfer_old_com_gen_cases tt ON tt.diary_no = m.diary_no
                LEFT JOIN master.casetype c1 ON active_casetype_id = c1.casecode
                LEFT JOIN master.users u ON u.usercode = m.dacode AND u.display = 'Y'
                LEFT JOIN master.usersection us ON us.id = u.section
                LEFT JOIN mul_category c2 ON c2.diary_no = m.diary_no AND c2.display = 'Y'
                WHERE 
                tt.next_dt_old = '$list_dt' AND 
                  tt.next_dt_new > CURRENT_DATE
                  AND c2.diary_no IS NOT NULL
                  AND (
                      TRIM(LEADING '0' FROM SPLIT_PART(m.fil_no, '-', 1)) IN ('3', '15', '19', '31', '23', '24', '40', '32', '34', '22', '39', '11', '17', '13', '1', '7', '37', '9999', '38', '5', '21', '27', '4', '16', '20', '18', '33', '41', '35', '36', '28', '12', '14', '2', '8', '6')
                      OR (m.active_fil_no = '' OR m.active_fil_no IS NULL)
                  )
                  AND CASE
                      WHEN (tt.diary_no = tt.conn_key OR tt.conn_key = 0 OR tt.conn_key IS NULL) THEN TRUE
                      ELSE (
                          (SELECT DISTINCT conn_key FROM conct WHERE diary_no = m.diary_no) IN (SELECT diary_no FROM transfer_old_com_gen_cases t1 WHERE t1.next_dt_new = tt.next_dt_new AND t1.next_dt_new > CURRENT_DATE)
                      )
                  END
                GROUP BY m.diary_no, tt.next_dt_old,tt.listorder_new, tt.next_dt_new, u.name, us.section_name, m.conn_key, c1.short_description, active_fil_no, m.active_reg_year, m.casetype_id, m.active_casetype_id, m.ref_agency_state_id, m.reg_no_display, fil_year, m.fil_no, m.fil_dt, m.fil_no_fh, m.reg_year_fh, m.mf_active, m.pet_name, m.res_name, pno, rno, m.diary_no_rec_date, main_or_connected, listed
            ) AS aa 
            $section 
            ORDER BY 
                CASE WHEN main_key != '' THEN SUBSTRING(main_key::text, LENGTH(main_key::text) - 3) ELSE SUBSTRING(diary_no::text, LENGTH(diary_no::text) - 3) END,
                CASE WHEN main_key != '' THEN main_key::text ELSE diary_no::text END,
                CASE WHEN main_key::TEXT = diary_no::TEXT THEN 0 ELSE 1 END,
                main_or_connected ASC";
                // echo $sql;
                // die();
    $query = $this->db->query($sql);
    $result = $query->getResultArray();
    return $result;
  }
  public function tentative_listing_date()
  {
        $cur_ddt = date('Y-m-d', strtotime(' +1 day'));
        $next_court_work_day = date("d-m-Y", strtotime(chksDate($cur_ddt)));
        $builder = $this->db->table('master.sc_working_days s');
        $builder->select('s.working_date');
        $builder->join('advance_cl_printed a', 'a.next_dt = s.working_date AND a.display = \'Y\' AND a.board_type = \'J\'', 'left');
        $builder->where('s.sec_list_dt <=', date('Y-m-d')); 
        $builder->where('s.sec_list_dt !=', '0001-01-01');
        $builder->where('s.is_holiday', 0);
        $builder->where('s.display', 'Y');
        $builder->where('a.next_dt IS NULL');
        $builder->orderBy('s.working_date', 'ASC');
        // echo $builder->getCompiledSelect();
        // die();
        $result = $builder->get()->getResultArray();
        return $result;
  }
  public function section_name()
  {
    $builder = $this->db->table('master.usersection');
    $query = $builder->select('*')
      ->where('display', 'Y')
      ->where('isda', 'Y')
      ->orderBy('section_name');
    $query = $builder->get();
    $result = $query->getResultArray();
    return $result;
  }
  public function section_name_by_id($id)
  {
    $builder = $this->db->table('master.usersection');
    $query = $builder->select('*')
      ->where('display', 'Y')
      ->where('isda', 'Y')
      ->where('id', $id)
      ->orderBy('section_name');
    $query = $builder->get();
    $result = $query->getResultArray();
    return $result;
  }
  public function get_spread_out_data($from_dt,$board_type,$sec_id)
  {
    $usercode = session()->get('login')['usercode'];
    $usertype= session()->get('login')['usertype'];
        $builder = $this->db->table('heardt h');

        $builder->select([
            'm.reg_no_display',
            'mc.submaster_id',
            'u.name',
            'us.section_name',
            's.stagename',
            'l.purpose',
            'c1.short_description',
            'EXTRACT(YEAR FROM m.active_fil_dt) AS fyr',
            'm.active_reg_year',
            'm.active_fil_dt',
            'm.active_fil_no',
            'm.pet_name',
            'm.res_name',
            'm.pno',
            'm.rno',
            'm.casetype_id',
            'm.ref_agency_state_id',
            'm.diary_no_rec_date',
            'h.*',
            'CAST(RIGHT(CAST(m.diary_no AS TEXT), 4) AS INTEGER) AS order_right',
            'CAST(LEFT(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER) AS order_left',
        ]);

        $builder->join('main m', 'm.diary_no = h.diary_no', 'INNER');
        $builder->join('master.casetype c1', 'm.active_casetype_id = c1.casecode', 'LEFT');
        $builder->join('master.listing_purpose l', 'l.code = h.listorder AND l.display = \'Y\'', 'LEFT');
        $builder->join('master.subheading s', 's.stagecode = h.subhead AND s.display = \'Y\' AND s.listtype = \'M\'', 'LEFT');
        $builder->join('rgo_default rd', 'rd.fil_no = h.diary_no AND rd.remove_def = \'N\'', 'LEFT');
        $builder->join('mul_category mc', 'mc.diary_no = m.diary_no AND mc.display = \'Y\'', 'LEFT');
        $builder->join('master.users u', 'u.usercode = m.dacode AND u.display = \'Y\'', 'LEFT');
        $builder->join('master.usersection us', 'us.id = u.section AND us.id = \'20\'', 'LEFT');

        $builder->where([
            'mc.display' => 'Y',
            'h.mainhead' => 'M',
            'h.next_dt' => $from_dt,
            'h.tentative_cl_dt' => $from_dt,
            'h.board_type' => 'J',
            'm.c_status' => 'P',
        ]);
        $builder->where('h.listorder !=', 32);

        if($usertype == '14'){       
          $data['users_list'] = $this->ReportModel->users_list($usercode);
          $all_da = $data['users_list'][0]['allda']; 
          $builder->whereIn('m.dacode ', $all_da);
        }
        else if($usertype == '17' OR $usertype == '50' OR $usertype == '51'){
          $builder->where('m.dacode ', $usercode);
        }
        if($board_type !== "0"){
          $builder->where('h.board_type ', $board_type);
        }
     
        if($sec_id !== "0"){
          $builder->where('us.id ', $sec_id);
          $builder->where('us.id IS NOT NULL', null, false);
        } 

        $builder->orderBy('l.priority', 'ASC');
        $builder->orderBy('order_right', 'ASC');
        $builder->orderBy('order_left', 'ASC');
        // echo $builder->getCompiledSelect();
        // die();
        $result = $builder->get()->getResultArray();

        return $result;

  }
  public function users_list($ucode)
  {
    $builder = $this->db->table('master.users u');
    $builder->select("STRING_AGG(u2.usercode::text, ',') AS allda");
    $builder->join('master.users u2', 'u2.section = u.section', 'left');
    $builder->where('u.display', 'Y');
    $builder->where('u.usercode', $ucode);
    $builder->groupBy('u.section');
    $query = $builder->get();
    $result = $query->getResultArray();
    return $result;
  }
  public function ntl_judge_dept()
  {
    $result = $this->db->table('master.ntl_judge_dept n')
            ->select('jname, b.deptname')
            ->join('master.judge j', 'n.org_judge_id = j.jcode')
            ->join('master.deptt b', 'n.dept_id = b.deptcode')
            ->where('n.display', 'Y')
            ->where('j.is_retired !=', 'Y')
            ->orderBy('j.judge_seniority', 'ASC') // Explicitly specify ascending order
            ->orderBy('b.deptname', 'ASC') // Explicitly specify ascending order
            ->get()
            ->getResultArray();
    return $result;
  }
  public function fresh_cases_stats_get($listing_dts_from, $listing_dts_to)
  {
      $builder = $this->db->table('master.listed_info l'); // Alias the table

      $builder->select('l.next_dt, SUM(freshly_filed) AS listed');

      // Subquery for Court
      $subqueryCourt = $this->db->table('master.roster r')
          ->select('COUNT(DISTINCT courtno)')
          ->join('master.roster_bench rb', 'rb.id = r.bench_id')
          ->join('master.master_bench mb', 'mb.id = rb.bench_id')
          ->where('mb.board_type_mb', 'J')
          ->where('r.from_date', 'l.next_dt', false) // Important: Don't escape l.next_dt
          ->where('r.display', 'Y')
          ->where('cast(r.m_f as integer)', 1)  // Cast to integer
          ->getCompiledSelect(); // Get the compiled SQL

      $builder->select("($subqueryCourt) AS Court"); // Add subquery to main select


      // Subquery for eliminated
      $subqueryEliminated = $this->db->table('transfer_old_com_gen_cases m')
          ->select('COUNT(diary_no)')
          ->where('next_dt_old', 'l.next_dt', false)  // Important: Don't escape l.next_dt
          ->where('listorder', 32)
          ->where('board_type', 'J')
          ->where('listtype', 'F')
          ->groupStart()
              ->where('m.diary_no = m.conn_key', null, false) // Correct comparison
              ->orWhere('m.conn_key IS NULL')
              ->orWhere('m.conn_key', 0)
          ->groupEnd()
          ->getCompiledSelect(); // Get the compiled SQL

      $builder->select("($subqueryEliminated) AS eliminated"); // Add subquery to main select


      $builder->where('remark', 'Allocated');
      $builder->where('l.next_dt >=', $listing_dts_from); // Use >= and <= for date ranges
      $builder->where('l.next_dt <=', $listing_dts_to);
      $builder->where('l.main_supp', 1);
      $builder->where('l.bench_flag', 'J');
      $builder->where('l.mainhead', 'M');

      $builder->groupBy('l.next_dt');
      //$builder->limit(1);
      $query = $builder->get();
      $result = $query->getResultArray();

      $i = 1;
      foreach($result as $ro){
        $arr_result[] = [
          'SNO' => $i,
          'Date_of_Listing' => date('d-m-Y', strtotime($ro['next_dt'])),
          'Matters_Available' => $ro['listed'] + $ro['eliminated'],
          'Matters_Listed' => $ro['listed'],
          'Matters_Left_after_allocation' => $ro['eliminated'],
          'No_of_Courts' => $ro['court']
      ];
      $i++;
      }
      return  json_encode($arr_result); 
  }

  
  public function get_allocation_report($mainhead, $board_type, $list_dt)
  {
    $cldt =  date('Y-m-d', strtotime($list_dt));
    if ($mainhead == "M") {
      $m_f = "r.m_f = '1'";
      if ($board_type == 'R') {
        $from_to_dt = "r.to_date IS NULL";
      } else {
        $from_to_dt = "r.from_date = '$cldt'";
      }
    } else if ($mainhead == "L") {
      $m_f = "r.m_f = '3'";
      $from_to_dt = "r.from_date = '$cldt'";
    } else if ($mainhead == "S") {
      $m_f = "r.m_f = '4'";
      $from_to_dt = "r.from_date = '$cldt'";
    } else {
      $m_f = "r.m_f = '2'";
      $from_to_dt = "r.from_date = '$cldt'";
    }

    $builder = $this->db->table('master.roster r');

    $builder->select('r.id, 
                      STRING_AGG(j.jcode::text, \',\' ORDER BY j.judge_seniority) AS jcd, 
                      STRING_AGG(j.jname, \',\' ORDER BY j.judge_seniority) AS jnm, 
                      rb.bench_no, 
                      mb.abbr, 
                      r.tot_cases, 
                      r.courtno')
      ->join('master.roster_bench rb', 'rb.id = r.bench_id', 'left')
      ->join('master.master_bench mb', 'mb.id = rb.bench_id', 'left')
      ->join('master.roster_judge rj', 'rj.roster_id = r.id', 'left')
      ->join('master.judge j', 'j.jcode = rj.judge_id', 'left')
      ->where('j.is_retired !=', 'Y')
      ->where('mb.board_type_mb', 'J')
      ->where('j.display', 'Y')
      ->where('rj.display', 'Y')
      ->where('rb.display', 'Y')
      ->where('mb.display', 'Y')
      ->where('r.display', 'Y')
      ->groupBy('r.id, rb.bench_no, mb.abbr, r.tot_cases, r.courtno, j.judge_seniority')
      ->orderBy('r.courtno')
      ->orderBy('r.id')
      ->orderBy('j.judge_seniority');

    $builder->where($m_f);
    $builder->where($from_to_dt);

    $query = $builder->get();
    $result = $query->getResultArray();

    return $result;
  }


  public function get_m_data($mainhead, $board_type, $list_dt, $jcd, $id)
  {
    $cldt = date('Y-m-d', strtotime($list_dt));

    if ($board_type == "0") {
      $board_type = "";
      $board_type_heardt = "";
    } else {
      $board_type = $board_type;
      $board_type_heardt = " AND h.board_type = '" . $board_type . "'";
    }

    $builder = $this->db->table('heardt h');

    if ($mainhead == "M") {
      if ($cldt > date('Y-m-d')) {
        // Query for future dates
        $builder->select("COUNT(*) AS ttt, 
                                SUM(CASE WHEN l.code = 4 OR l.code = 5 THEN 1 ELSE 0 END) AS fd, 
                                SUM(CASE WHEN l.code = 32 THEN 1 ELSE 0 END) AS fr, 
                                SUM(CASE WHEN l.code != 4 AND l.code != 5 AND l.code != 32 THEN 1 ELSE 0 END) AS ors")
          ->join('main m', 'h.diary_no = m.diary_no', 'inner')
          ->join('master.listing_purpose l', 'l.code = h.listorder', 'inner')
          ->where('l.display', 'Y')
          ->where('h.next_dt', $cldt)
          ->where('h.judges', $jcd)
          ->where('h.roster_id', $id)
          ->where('h.mainhead', $mainhead)
          ->whereIn("h.main_supp_flag", [1, 2])
          ->where("m.diary_no = CAST(m.conn_key AS bigint) OR m.conn_key = '0' OR m.conn_key IS NULL OR m.conn_key = ''")
          ->groupBy('h.judges');
      } else {
        // Query for past dates
        $builder->select('total AS ttt, 
                                (fix_dt + mentioning) AS fd, 
                                freshly_filed AS fr, 
                                (total - freshly_filed - (fix_dt + mentioning)) AS ors')
          ->from('master.listed_info')
          ->where('remark', 'Allocated')
          ->where('master.listed_info.next_dt', $cldt)
          ->where('master.listed_info.roster_id', $id)
          ->where('master.listed_info.mainhead', $mainhead)
          ->where('bench_flag', $board_type)
          ->whereIn("main_supp", [1, 2])
          ->groupBy('master.listed_info.roster_id, master.listed_info.fix_dt, master.listed_info.total, master.listed_info.mentioning, master.listed_info.freshly_filed');
      }
    }

    if ($mainhead == "F") {
      if ($cldt > date('Y-m-d')) {
        $builder->select("COUNT(*) AS ttt, 
                                SUM(CASE WHEN l.code = 4 OR l.code = 5 THEN 1 ELSE 0 END) AS fd, 
                                SUM(CASE WHEN l.code != 4 AND l.code != 5 THEN 1 ELSE 0 END) AS ors")
          ->join('main m', 'h.diary_no = m.diary_no', 'inner')
          ->join('master.listing_purpose l', 'l.code = h.listorder', 'inner')
          ->join('cl_printed cl', 'cl.next_dt = h.next_dt AND cl.m_f = h.mainhead AND cl.part = h.clno AND cl.roster_id = h.roster_id AND cl.display = "Y"', 'left')
          ->where('cl.next_dt IS NOT NULL')
          ->where('l.display', 'Y')
          ->where('h.next_dt', $cldt)
          ->where('h.judges', $jcd)
          ->where('h.mainhead', $mainhead)
          ->whereIn("h.main_supp_flag", [1, 2])
          ->where("m.diary_no = m.conn_key OR m.conn_key = 0 OR m.conn_key IS NULL OR m.conn_key = ''")
          ->groupBy('h.judges');
      } else {
        $builder->select('total AS ttt,
                                (fix_dt + mentioning) AS fd,    
                                (total - (fix_dt + mentioning)) AS ors')
          ->from('master.listed_info')
          ->where('remark', 'After_Allocation')
          ->where('master.listed_info.next_dt', $cldt)
          ->where('master.listed_info.roster_id', $id)
          ->where('master.listed_info.mainhead', $mainhead)
          ->where('master.listed_info.bench_flag', $board_type)
          ->whereIn("main_supp", [1, 2])
          ->groupBy('master.listed_info.roster_id, master.listed_info.fix_dt, master.listed_info.total, master.listed_info.mentioning, master.listed_info.freshly_filed');
      }
    }

    // Execute the query
    $query = $builder->get();
    $result = $query->getRowArray();

    return $result;
  }

  public function non_presiding_coram_get($board_type, $mainhead, $reg_unreg)
  {
    if ($reg_unreg == 0) {
      $diary_reg_un = "";
    } else if ($reg_unreg == 1) {
      $diary_reg_un = " AND m.reg_no_display != '' ";
    } else {
      $diary_reg_un = " AND m.reg_no_display = '' ";
    }

        $query = "SELECT DISTINCT
                  m.diary_no::integer,
                  l.purpose,
                  s.stagename,
                  m.reg_no_display,
                  m.pno,
                  m.rno,
                  m.pet_name,
                  m.res_name,
                  (
                      SELECT string_agg(abbreviation, '#')
                      FROM master.judge
                      WHERE jcode IN (SELECT CAST(elem AS INTEGER) FROM unnest(string_to_array(h.coram, ',')) AS elem)
                  ) AS coram,
                  tentative_section(m.diary_no) AS section_name,
                  tentative_da(m.diary_no::integer) AS da_name
                  FROM main m
                  INNER JOIN heardt h ON h.diary_no = m.diary_no
                  LEFT JOIN judge_group jg ON jg.p1::TEXT = (split_part(h.coram, ',', 1))::TEXT AND jg.to_dt IS NULL AND jg.display = 'Y'
                  LEFT JOIN master.listing_purpose l ON l.code = h.listorder AND l.display = 'Y'
                  LEFT JOIN master.subheading s ON s.stagecode = h.subhead AND s.display = 'Y' AND s.listtype = 'M'
                  WHERE jg.p1 IS NULL
                  AND m.c_status = 'P'
                  AND h.board_type = '$board_type'
                  AND h.mainhead = '$mainhead'
                  AND (m.diary_no::TEXT = m.conn_key::TEXT OR m.conn_key::TEXT = '0' OR m.conn_key IS NULL)
                  AND (h.coram IS NOT NULL AND h.coram::TEXT != '0')
                  AND h.next_dt IS NULL
                  AND h.listorder::integer != 32
                  AND h.clno::TEXT = '0'
                  AND h.subhead IN (824, 810, 803, 802, 807, 804, 808, 811, 812, 813, 814, 815, 816)
                  GROUP BY m.diary_no, l.purpose, s.stagename, h.coram
                  ORDER BY
                  section_name,
                  da_name, 
                  m.diary_no::integer DESC";
                  // echo $query;
                  // die();
              $result = $this->db->query($query)->getResultArray();
        return $result;
  }

  public function matters_listed($section, $da, $stage, $fromDays, $toDays, $year, $daysRange)
  {
        if ($section != 0) {
          $sql_section = "SELECT section_name 
                          FROM master.usersection 
                          WHERE id = '$section'";
          $query_section = $this->db->query($sql_section);
          $result_section = $query_section->getResultArray();
          $section_name = $result_section[0]['section_name'];
        }
        $condition = "";
        // echo $section_name;
        // die();
        $builder = $this->db->table('main m'); // Alias the table as 'm'

        $builder->select(
            "SUBSTR(m.diary_no::TEXT, 1, LENGTH(m.diary_no::TEXT) - 4) || '/' || SUBSTR(m.diary_no::TEXT, -4) AS Diary,
            reg_no_display AS Case_No,
            pet_name || ' vs ' || res_name AS Cause_Title,
            tentative_section(m.diary_no) AS Section,
            tentative_da(m.diary_no::integer) AS Dealing_Assistant,
            lldt(m.diary_no::bigint) AS Last_listed_on,
            m.diary_no_rec_date,
            m.active_fil_dt,
            CASE WHEN h.main_supp_flag = 0 THEN 'Ready' ELSE 'Not Ready' END AS Ready_status,
            CASE WHEN (m.diary_no::text = m.conn_key::text OR m.conn_key::text = '0' OR m.conn_key IS NULL) THEN 'M' ELSE 'C' END AS main_or_connected"
        );

        $builder->join('heardt h', 'cast(m.diary_no as integer) = h.diary_no', 'left'); // Left join

        $builder->where('m.c_status', 'P');
        $builder->where('m.mf_active', 'M');

        if ($daysRange == 'D') {
          $builder->where("lldt(m.diary_no::bigint) BETWEEN '$fromDays' AND '$toDays'");
        } 
        else if ($daysRange == 'Y') {
          $builder->where("lldt(m.diary_no::bigint) > ", [$year]);
        } 
        else if ($daysRange == 'N') {
          $builder->where('m.diary_no IS NULL');
        }
        
        if ($da != 0) {
          $builder->where('m.dacode', $da);
        } 
        else if ($da == 0 && $section != 0) {
          $builder->where("tentative_section(m.diary_no) like ?", [$section_name]); // Assuming tentative_section is a DB function
        }
        $builder->orderBy('tentative_section(m.diary_no), tentative_da(m.diary_no::integer), SUBSTR(m.diary_no::text, -4)');
        
       // $builder->limit(3);
        // echo $builder->getCompiledSelect();
        // die();
        $query = $builder->get();
        $cases = $query->getResultArray();
        $arr_result['data'] = [];
        $arr_result['title'] = '';
        if (isset($cases)) {
        $i = 1;
        foreach($cases as $ro){
              $sectionname = $ro['section'];
              $da_name = $ro['dealing_assistant'];
              $daylist = '';
              if ($daysRange == 'D')
              $daylist = " listed between $fromDays days and $toDays days ";
              else if ($daysRange == 'Y')
                  $daylist = " listed more than $year days before ";
              else if ($daysRange == 'N')
                  $daylist = " never listed ";
              if ($stage == 'M')
                  $stge = "Miscelleneous ";
              else if ($stage == 'F')
                  $stge = "Regular ";
              if ($section != 0)
                  $section_name = " and dealt with by section-" . $sectionname;
              else if ($section == 0)
                  $section_name = "";
              if ($da != 0) {
                  $section_name = "";
                  $daname = " and dealt with by " . $da_name . " of section-" . $sectionname;
              } else if ($da == 0)
                  $daname = "";

          if($ro['diary_no_rec_date']!='0000-00-00 00:00:00' and $ro['diary_no_rec_date']!=null and $ro['diary_no_rec_date']!='' and $ro['diary_no_rec_date']!='1900-01-01 00:00:00') 
          $diary_on  = date('d-m-Y',strtotime($ro['diary_no_rec_date'])); 
          else $diary_on  = '';
          if($ro['active_fil_dt']!='0000-00-00 00:00:00' and $ro['active_fil_dt']!=null and $ro['active_fil_dt']!='' and $ro['active_fil_dt']!='1900-01-01 00:00:00') 
          $registered_on = date('d-m-Y',strtotime($ro['active_fil_dt'])); 
          else $registered_on = '';

          $arr_result['title'] = $stge .' Matters which were ' .$daylist.' '.$section_name.' '.$daname. ' as on ' .date('d-m-Y h:m:s A');
          $arr_result['data'][] = [
            'SNO' => $i,
            'Diary_no' => $ro['diary'],
            'Case_No' => $ro['Case_No'],
            'Cause_Title' => $ro['cause_title'],
            'Main_Connected' => $ro['main_or_connected'],
            'Diary_On' => $diary_on,
            'Registered_On' => $registered_on,
            'Last_Listed_On' => $ro['last_listed_on'],
            'Ready_Not_Ready' => $ro['ready_status'],
            'Dealing_Assistant' => $ro['dealing_assistant']."/".$ro['section']
        ];
        $i++;
        }
    }
    
      return  json_encode($arr_result); 
  }
  
  public function get_DA_sectionwise($section)
  {
    $query = "SELECT 
  u.usercode, 
  u.name || ', ' || ut.type_name AS name, 
  u.empid, 
  us.section_name 
FROM 
  master.users u 
  INNER JOIN master.usersection us ON u.section = us.id 
  INNER JOIN master.usertype ut ON ut.id = u.usertype 
WHERE 
  u.section = $section 
  AND u.display = 'Y' 
  AND ut.id IN (14, 50, 51, 17) 
ORDER BY 
  ut.type_name, 
  u.empid";
    $query = $this->db->query($query);
    $result = $query->getResultArray();
    return $result;
  }
  public function listing_info_get($list_dt, $board_type)
  {
    $sql = "SELECT CASE 
                  WHEN coram = '0' THEN 'Blank' 
                  ELSE j.abbreviation 
                  END AS abbreviation, 
                  b.* 
                  FROM (
                  SELECT 
                  next_dt, 
                  coram, 
                  SUM(CASE WHEN remark = 'Pre_Allocation' THEN total ELSE 0 END) AS total_pre_allocation, 
                  SUM(CASE WHEN remark = 'Allocated' THEN total ELSE 0 END) AS total_allocated, 
                  SUM(CASE WHEN remark = 'After_Allocation' THEN total ELSE 0 END) AS total_after_allocation 
                  FROM (
                  SELECT 
                    l.*, 
                    COALESCE(
                    (
                    SELECT 
                    SPLIT_PART(
                      STRING_AGG(j.judge_id::TEXT, ',' ORDER BY u.judge_seniority), 
                      ',', 
                      1
                    )
                    FROM 
                    master.roster_judge j 
                    INNER JOIN master.judge u ON u.jcode = j.judge_id 
                    WHERE 
                    j.roster_id = l.roster_id 
                    AND l.remark = 'Allocated' 
                    GROUP BY 
                    j.roster_id
                    ), 
                    l.roster_id::TEXT
                    ) AS coram 
                  FROM 
                    master.listed_info l 
                  WHERE 
                    next_dt = '$list_dt'
                    AND mainhead = 'M'  
                    AND bench_flag = '$board_type' 
                    AND (main_supp = 1 OR main_supp = 2)
                  ) AS a 
                  GROUP BY 
                    a.next_dt,
                  ROLLUP (coram)
                  ) AS b 
                  LEFT JOIN master.judge j ON j.jcode = b.coram::INTEGER
                  ORDER BY 
                  CASE 
                  WHEN coram IS NOT NULL AND coram != '0' THEN 1 
                  WHEN coram = '0' THEN 2 
                  ELSE 3 
                  END ASC, 
                  j.judge_seniority ASC";
    $query = $this->db->query($sql);
    $result = $query->getResultArray();
    return $result;
  }


  function showMatters($date, $module, $userid)
  {
    $builder = $this->db->table('heardt h');

        $builder->select("concat(SUBSTR(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4), '/', SUBSTR(m.diary_no::text, -4)) AS diary,
            m.reg_no_display,
            m.pet_name,
            m.res_name,
            u.name,
            u.empid,
            us.section_name,
            date(h.next_dt) AS next_dt,
            mm.module_desc");

        $builder->join('main m', 'h.diary_no = m.diary_no');
        $builder->join('master.users u', 'h.usercode = u.usercode');
        $builder->join('master.usersection us', 'u.section = us.id');
        $builder->join('master.master_module mm', 'mm.id = h.module_id');

        $builder->where('date(h.ent_dt)', $date);
        $builder->where('h.module_id', $module);
        $builder->where('u.empid', $userid);

        $builder->orderBy('us.section_name, date(h.next_dt), u.empid');

        $query = $builder->get();
        return $query->getResultArray();
  }

  public function getMatters($frm_dt, $to_dt, $advocate = '', $judge = '')
  {
    // First part of the UNION
    $query1 = $this->db->table('heardt a')
      ->select('a.diary_no, a.next_dt, b.conn_key, reg_no_display, active_fil_no, pet_name, res_name, pno, rno, brd_slno, a.roster_id, b.dacode, courtno, 
                  CASE WHEN b.conn_key = a.diary_no::text THEN \'0\' 
                       ELSE CONCAT(SUBSTR(a.diary_no::text, -4), SUBSTR(a.diary_no::text, 1, LENGTH(a.diary_no::text) - 4)) 
                  END AS sort_key')
      ->join('main b', 'a.diary_no = b.diary_no')
      ->join('cl_printed c', 'c.next_dt = a.next_dt AND c.m_f = a.mainhead AND c.part = a.clno AND a.roster_id = c.roster_id')
      ->join('master.roster y', 'y.id = a.roster_id')
      ->where('a.next_dt >=', $frm_dt)
      ->where('a.next_dt <=', $to_dt)
      ->where('clno !=', 0)
      ->where('brd_slno !=', 0)
      ->groupStart()
      ->where('main_supp_flag', '1')
      ->orWhere('main_supp_flag', '2')
      ->groupEnd()
      ->where('c_status', 'P')
      ->where('c.display', 'Y')
      ->getCompiledSelect();

    // Second part of the UNION
    $query2 = $this->db->table('last_heardt a')
      ->select('a.diary_no, a.next_dt, b.conn_key, reg_no_display, active_fil_no, pet_name, res_name, pno, rno, brd_slno, a.roster_id, b.dacode, courtno, 
                  CASE WHEN b.conn_key = a.diary_no::text THEN \'0\' 
                       ELSE CONCAT(SUBSTR(a.diary_no::text, -4), SUBSTR(a.diary_no::text, 1, LENGTH(a.diary_no::text) - 4)) 
                  END AS sort_key')
      ->join('main b', 'a.diary_no = b.diary_no')
      ->join('cl_printed c', 'c.next_dt = a.next_dt AND c.m_f = a.mainhead AND c.part = a.clno AND a.roster_id = c.roster_id')
      ->join('master.roster y', 'y.id = a.roster_id')
      ->where('a.next_dt >=', $frm_dt)
      ->where('a.next_dt <=', $to_dt)
      ->where('clno !=', 0)
      ->where('brd_slno !=', 0)
      ->groupStart()
      ->where('main_supp_flag', '1')
      ->orWhere('main_supp_flag', '2')
      ->groupEnd()
      ->groupStart()
      ->where('bench_flag IS NULL')
      ->orWhere('bench_flag', '')
      ->groupEnd()
      ->groupStart()
      ->where('b.conn_key', '')
      ->orWhere('b.conn_key', '0')
      ->orWhere('b.conn_key', 'NULL')
      ->orWhere('b.conn_key = a.diary_no::text')  // Cast diary_no to text
      ->groupEnd()
      ->where('c_status', 'P')
      ->where('c.display', 'Y')
      ->getCompiledSelect();

    // Combine the two queries with UNION
    $final_query = "$query1 UNION $query2 ORDER BY next_dt, courtno, roster_id, brd_slno, sort_key";


    // Execute the final query
    $result = $this->db->query($final_query);
    return $result->getResultArray();
  }


  public function getRosterJudge($roster_id)
  {
    // Load the database library in your controller
    $builder = $this->db->table('master.roster_judge a');
    $builder->join('master.judge b', 'a.judge_id = b.jcode');
    $builder->select('b.jname');
    $builder->where('a.roster_id', $roster_id);
    $builder->where('a.display', 'Y');
    $builder->where('b.display', 'Y');

    $query = $builder->get();
    return $query->getResultArray();
  }


  public function getSectionName($userCode)
  {
    $builder = $this->db->table('master.users a');
    $builder->join('master.usersection b', 'a.section = b.id');
    $builder->select('b.section_name');
    $builder->where('a.usercode', $userCode);
    $builder->where('a.display', 'Y');
    $builder->where('b.display', 'Y');

    $query = $builder->get();

    return $query->getRowArray(); // Return the result set
  }
  
  public function getSecListNewFun1($list_dt,$board_type,$sec_id,$ucode,$list_type,$usertype)
  { 
    $mainhead = "M"; 
    if ($usertype == '14') {
      $data['users_list'] = $this->ReportModel->users_list($ucode);
      $data['all_da'] = $data['users_list'][0]['allda'];
    } else if ($usertype == '17' or  $usertype == '50' or  $usertype == '51') {
      $mdacode = "AND m.dacode = '$ucode'";
    } else {
      $mdacode = "";
    }
    if($sec_id === "0"){
      $sec_id = "";
      $sec_id2 = "";
    }
    else{
        $sec_id = "AND us.id = '".$sec_id."'";
        $sec_id2 = "AND us.id is not null";
    }
    $output ='';
    
    $list_dt_con = date('d-m-Y', strtotime($list_dt));
    $list_dt_2 = date('Y-m-d', strtotime($list_dt));
    //$list_year = date('Y', strtotime($list_dt));
    if ($list_type == "All") {
      $data['loop_q'] = 3;
      $data['start_loop_q'] = 1;
    }
    if ($list_type == "SecList") {
      $data['loop_q'] = 1;
      $data['start_loop_q'] = 1;
    }
    if ($list_type == "Deletion") {
      $data['loop_q'] = 2;
      $data['start_loop_q'] = 2;
    }
    if ($list_type == "Addition") {
      $data['loop_q'] = 3;
      $data['start_loop_q'] = 3;
    }
    
    $output .="<table border='0' width='100%' style='font-size:12px; text-align: left; background: #f9e0de;' cellspacing='0'>
            <thead>
            <tr>
                <th colspan='4' style='text-align: center;'>
                    <span style='font-weight: bold;'>Section List</span><br><br><br><span style='font-weight: bold;'>DATE OF LISTING : </span> ".$list_dt_con."</th>
            </tr>
            </thead>
            
        </table>";
    for($q=$data['start_loop_q'];$q<=$data['loop_q'];$q++){
    
      if($q == 1 OR $q == 2){
        //$list_dt = '2018-02-20';

        if($q == 2){
          $deletion1 = "LEFT JOIN heardt h ON h.diary_no = tt.diary_no AND h.next_dt >= tt.next_dt_old AND h.clno = 0 AND h.board_type = 'J'";
          $deletion2 = " h.diary_no IS NULL AND ";
          $color = "#f9e0de";
       }
       else{
          $deletion1 = "";
          $deletion2 = "";
          $color = "#e4e3ff";        
              
              $builder = $this->db->table('draft_list');
              $builder->selectMin('ent_time', 'min_tm');
              $builder->where('next_dt_old', $list_dt_2);
              $builder->where('board_type', $board_type);
              $builder->where('display', 'Y');

              $result = $builder->get()->getRowArray();

              if ($result && isset($result['min_tm'])) {
                  $pub_time = "<span style='font-weight: bold;'>Publication Time : </span>" . date('d-m-Y h:i:s A', strtotime($result['min_tm'])) . "<br>";
                  $print_cont = $pub_time;
              } else {
                $print_cont = "Publication Time : N/A<br/><br/>"; // Or handle the absence of data appropriately
              }
       }
       if($q === 1){
       $output .="<div style='float:left;'>".$print_cont."</div>";
       }
       
  
       $sql = "SELECT *
                FROM (
                    SELECT DISTINCT
                        m.diary_no,
                        tt.next_dt_old AS next_dt,
                        u.name,
                        CASE
                            WHEN us.section_name IS NOT NULL THEN us.section_name
                            ELSE tentative_section(m.diary_no)
                        END AS section_name,
                        m.conn_key AS main_key,
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
                        pno,
                        rno,
                        m.diary_no_rec_date,
                        CASE
                            WHEN (tt.diary_no::text = tt.conn_key::text OR tt.conn_key = '0' OR tt.conn_key IS NULL) THEN 0
                            ELSE 1
                        END AS main_or_connected,
                        CASE
                            WHEN (tt.conn_key IS NOT NULL AND tt.conn_key != '0') THEN 1
                            ELSE 0
                        END AS listed
                    FROM main m
                    INNER JOIN draft_list tt ON tt.diary_no = m.diary_no 
                    $deletion1 
                    LEFT JOIN master.casetype c1 ON m.active_casetype_id = c1.casecode
                    LEFT JOIN master.users u ON u.usercode = m.dacode AND u.display = 'Y'
                    LEFT JOIN master.usersection us ON us.id = u.section
                    LEFT JOIN mul_category c2 ON c2.diary_no = m.diary_no AND c2.display = 'Y'
                    WHERE 
                       $deletion2 
                        tt.next_dt_old = '$list_dt_2' 
                        $mdacode $sec_id2 AND 
                        tt.board_type = '$board_type'
                        AND tt.list_type = 1
                        AND tt.display = 'Y'
                        AND c2.diary_no IS NOT NULL
                        AND (
                            TRIM(LEADING '0' FROM SPLIT_PART(m.fil_no, '-', 1)) IN ('3', '15', '19', '31', '23', '24', '40', '32', '34', '22', '39', '11', '17', '13', '1', '7', '37', '9999', '38', '5', '21', '27', '4', '16', '20', '18', '33', '41', '35', '36', '28', '12', '14', '2', '8', '6')
                            OR (m.active_fil_no = '' OR m.active_fil_no IS NULL)
                        )
                        AND CASE
                            WHEN (tt.diary_no::text = tt.conn_key::text OR tt.conn_key = '0' OR tt.conn_key IS NULL) THEN TRUE
                            ELSE (
                                (SELECT DISTINCT conn_key FROM conct WHERE diary_no = m.diary_no) IN (
                                    SELECT diary_no FROM draft_list t1 WHERE t1.next_dt_old = tt.next_dt_old AND t1.list_type = 1 AND t1.display = 'Y'
                                )
                            )
                        END
                    GROUP BY m.diary_no, tt.next_dt_old, u.name, us.section_name, m.conn_key, c1.short_description, active_fil_no, m.active_reg_year, m.casetype_id, m.active_casetype_id, m.ref_agency_state_id, m.reg_no_display, m.fil_dt, m.fil_no, m.fil_dt, m.fil_no_fh, m.reg_year_fh, m.mf_active, m.pet_name, m.res_name, pno, rno, m.diary_no_rec_date, tt.conn_key, tt.diary_no
                ) AS aa
                ORDER BY
                    CASE
                        WHEN (main_key != '') THEN SUBSTRING(main_key, LENGTH(main_key) - 3)
                        ELSE SUBSTRING(diary_no::TEXT, LENGTH(diary_no::TEXT) - 3) 
                    END,
                    CASE
                        WHEN (main_key IS NOT NULL) THEN main_key::TEXT
                        ELSE diary_no::TEXT
                    END,
                    CASE
                    WHEN (main_key::TEXT = diary_no::TEXT) THEN 0
                    ELSE 1
                    END,
                    main_or_connected ASC";
   
     
      }
      if($q == 3){
        $color = "#e0f2e0";
        //$next_dt = '2022-11-15';
        $sql = "SELECT aa.*
            FROM (
              SELECT DISTINCT
                    m.diary_no,
                    h.next_dt,
                u.name,
                    m.conn_key AS main_key,
                c1.short_description,
                CASE
                        WHEN us.section_name IS NOT NULL THEN us.section_name
                        ELSE tentative_section(m.diary_no)
                    END AS section_name,
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
                    m.pno,
                    m.rno,
                    m.diary_no_rec_date,
                        CASE
                            WHEN (tt.diary_no::text = tt.conn_key::text OR tt.conn_key = '0' OR tt.conn_key IS NULL) THEN 0
                            ELSE 1
                        END AS main_or_connected,
                        CASE
                            WHEN (tt.conn_key IS NOT NULL AND tt.conn_key != '0') THEN 1
                            ELSE 0
                        END AS listed
                FROM main m 
              INNER JOIN heardt h ON h.diary_no = m.diary_no 
              LEFT JOIN draft_list tt ON tt.diary_no = h.diary_no AND h.next_dt = tt.next_dt_old AND tt.board_type = 'J' AND tt.list_type = 1 AND tt.display = 'Y' 
              LEFT JOIN master.casetype c1 ON m.active_casetype_id = c1.casecode 
              LEFT JOIN master.users u ON u.usercode = m.dacode AND u.display = 'Y' 
              LEFT JOIN master.usersection us ON us.id = u.section $sec_id
              LEFT JOIN mul_category c2 ON c2.diary_no = m.diary_no AND c2.display = 'Y' 
              where h.board_type = 'J' 
                  AND h.next_dt = '".$list_dt_2."'
                  $mdacode $sec_id2 
                  AND tt.diary_no IS NULL
                    AND h.mainhead = 'M'
                    AND h.clno = 0 
                AND c2.diary_no IS NOT NULL
                AND (
                (SPLIT_PART(m.fil_no, '-', 1) <> '' AND TRIM(LEADING '0' FROM SPLIT_PART(m.fil_no, '-', 1))::integer IN (3, 15, 19, 31, 23, 24, 40, 32, 34, 22, 39, 11, 17, 13, 1, 7, 37, 9999, 38, 5, 21, 27, 4, 16, 20, 18, 33, 41, 35, 36, 28, 12, 14, 2, 8, 6))
                OR (m.active_fil_no IS NULL)
            ) 
                AND CASE
                        WHEN (cast(m.diary_no as TEXT) = m.conn_key::TEXT OR m.conn_key::TEXT = '0' OR m.conn_key IS NULL) THEN TRUE
                        ELSE (
                            (SELECT DISTINCT conn_key FROM conct WHERE diary_no = m.diary_no) IN (SELECT diary_no FROM heardt t1 WHERE t1.next_dt = h.next_dt)
                        )
                    END 
              GROUP BY m.diary_no, tt.conn_key,tt.diary_no,c1.short_description, u.name, us.section_name, h.next_dt, m.conn_key, m.active_fil_no, m.active_reg_year, m.casetype_id, m.active_casetype_id, m.ref_agency_state_id, m.reg_no_display, m.fil_dt, m.fil_no, m.fil_no_fh, m.reg_year_fh, m.mf_active, m.pet_name, m.res_name, m.pno, m.rno, m.diary_no_rec_date 
              order by h.next_dt
            ) AS aa
            ORDER BY
                CASE
                    WHEN aa.main_key != '' THEN SUBSTR(aa.main_key::text, -4)
                    ELSE SUBSTR(aa.diary_no::text, -4)
                END,
                CASE
                    WHEN aa.main_key != '' THEN aa.main_key::text
                    ELSE aa.diary_no::text
                END,
                CASE
                    WHEN cast(aa.main_key as TEXT) = aa.diary_no::TEXT THEN 0
                    ELSE 1
                END , main_or_connected ASC";
               // die();

      }
    }
    // echo $sql;
    // die();
    $result_data = $this->db->query($sql)->getResultArray();
 

            $psrno = "1";
            $clnochk = 0;
            $subheading_rep = "0";
            $mnhead_print_once = 1;
            $output .= "<table cellpadding='1' cellspacing='0' border='1' style='font-size:12px; text-align: left; background:".$color.";' >";
            foreach ($result_data as $row) {        
                $next_dt = date('d-m-Y', strtotime($row['next_dt']));                
                $diary_no = $row['diary_no']; 
                if ($mnhead_print_once == 1) {                   
                            $print_mainhead = "MISCELLANEOUS MATTERS";
                   
                        $output .= "<tr><th colspan='4'
                            style='text-align: center; text-decoration: underline;font-weight: bold;'>". $print_mainhead. "<br><br>"; 
                            if($q == 1){
                              $output .= "Section List Published";
                            }
                            if($q == 2){
                                    $output .= "Deleted/Listed Cases";
                            }
                            if($q == 3){
                                    $output .= "Addition after publication of section list";
                            }                            
                            $output .= "</th>";
                    $output .= "</tr>";
                    $output .= "<tr>
                        <td style='width:5%;font-weight: bold;'>SNo.</td>
                        <td style='width:20%;font-weight: bold;'>Case No.</td>
                        <td style='width:35%;font-weight: bold;'>Petitioner / Respondent</td>
                        <td style='width:40%;font-weight: bold;'> Petitioner/Respondent Advocate</td>
                    </tr>";

                   
                    $mnhead_print_once++;
                }


                if ($row['diary_no'] == $row['main_key'] OR $row['main_key'] == 0) {
                    $print_srno = $psrno;
                    $con_no = "0";
                    $is_connected = "";
                } else if ($row['listed'] == 1 OR ($row['diary_no'] != $row['main_key'] AND $row['main_key'] != null)) {                    
                    $is_connected = "<span style='color:red;'>Connected</span><br/>";
                    
                }
                $m_f_filno = $row['active_fil_no'];
                $m_f_fil_yr = $row['active_reg_year'];
                $filno_array = explode("-", $m_f_filno);
                if (count($filno_array) >= 2) {
                  if (count($filno_array) > 3 && $filno_array[1] == $filno_array[2]) {
                      $fil_no_print = ltrim($filno_array[1], '0');
                  } elseif (count($filno_array) >= 3) {
                      $fil_no_print = ltrim($filno_array[1], '0') . "-" . ltrim($filno_array[2], '0');
                  } else {
                    $fil_no_print = ltrim($filno_array[1], '0'); //Handles the case when there are only two elements
                  }
                  } else {
                      $fil_no_print = '';
                  }
                 
                if ($row['active_fil_no'] == "") {
                    $comlete_fil_no_prt = "Diary No. " . substr_replace($row['diary_no'], '-', -4, 0);
                }
                else {
                    $comlete_fil_no_prt = $row['short_description'] . "-" . $fil_no_print . "/" . $m_f_fil_yr;
                }
                
                $padvname = "";
                $radvname = "";
                $advsql = "SELECT
                                a.*,
                                STRING_AGG(CASE WHEN a.pet_res = 'R' THEN a.name || COALESCE(a.grp_adv, '') ELSE NULL END, '' ORDER BY a.adv_type DESC, a.pet_res_no ASC) AS r_n,
                                STRING_AGG(CASE WHEN a.pet_res = 'P' THEN a.name || COALESCE(a.grp_adv, '') ELSE NULL END, '' ORDER BY a.adv_type DESC, a.pet_res_no ASC) AS p_n,
                                STRING_AGG(CASE WHEN a.pet_res = 'I' THEN a.name || COALESCE(a.grp_adv, '') ELSE NULL END, '' ORDER BY a.adv_type DESC, a.pet_res_no ASC) AS i_n
                            FROM (
                                SELECT
                                    a.diary_no,
                                    b.name,
                                    STRING_AGG(COALESCE(a.adv, ''), '' ORDER BY CASE WHEN a.pet_res = 'I' THEN 99 ELSE 0 END ASC, a.adv_type DESC, a.pet_res_no ASC) AS grp_adv,
                                    a.pet_res,
                                    a.adv_type,
                                    a.pet_res_no
                                FROM
                                    advocate a
                                LEFT JOIN
                                    master.bar b ON a.advocate_id = b.bar_id AND b.isdead!= 'Y'
                                WHERE
                                    a.diary_no = '" . $row["diary_no"] . "'
                                    AND a.display = 'Y'
                                GROUP BY
                                    a.diary_no,
                                    b.name,
                                a.pet_res,
                                a.adv_type,
                                    a.pet_res_no
                                ORDER BY
                                    CASE WHEN a.pet_res = 'I' THEN 99 ELSE 0 END ASC,
                                    a.adv_type DESC,
                                    a.pet_res_no ASC
                            ) a
                            GROUP BY
                                a.diary_no, a.*,a.name,a.grp_adv,a.pet_res,a.adv_type,a.pet_res_no ";
                $resultsadv = $this->db->query($advsql)->getResultArray();
                
                if (count($resultsadv) > 0) {
                    $rowadv = $resultsadv[0];
                   
                    $radvname = $rowadv["r_n"];
                    $padvname = $rowadv["p_n"];
                    $impldname = $rowadv["i_n"];
                 
                }
                
                if ($row['pno'] == 2) {
                    $pet_name = $row['pet_name'] . " AND ANR.";
                } else if ($row['pno'] > 2) {
                    $pet_name = $row['pet_name'] . " AND ORS.";
                } else {
                    $pet_name = $row['pet_name'];
                }
                
                if ($row['rno'] == 2) {
                    $res_name = $row['res_name'] . " AND ANR.";
                } else if ($row['rno'] > 2) {
                    $res_name = $row['res_name'] . " AND ORS.";
                } else {
                    $res_name = $row['res_name'];
                }
               
                if (($row['section_name'] == null OR $row['section_name'] == '') AND $row['ref_agency_state_id'] != '' and $row['ref_agency_state_id'] != 0) {
                    if ($row['active_reg_year'] != 0)
                        $ten_reg_yr = $row['active_reg_year'];
                    else
                        $ten_reg_yr = date('Y', strtotime($row['diary_no_rec_date']));

                    if ($row['active_casetype_id'] != 0)
                        $casetype_displ = $row['active_casetype_id'];
                    else if ($row['casetype_id'] != 0)
                        $casetype_displ = $row['casetype_id'];
                        $section_ten_q = "SELECT dacode,section_name,name FROM da_case_distribution a
                        LEFT JOIN users b ON usercode=dacode
                        LEFT JOIN usersection c ON b.section=c.id
                        WHERE case_type=$casetype_displ AND $ten_reg_yr BETWEEN case_f_yr AND case_t_yr AND state='$row[ref_agency_state_id]' AND a.display='Y' ";

                    $section_ten_q = "SELECT
                                            a.dacode,
                                            c.section_name,
                                            b.name
                                        FROM
                                            master.da_case_distribution a
                                        LEFT JOIN
                                            master.users b ON a.dacode = b.usercode
                                        LEFT JOIN
                                            master.usersection c ON b.section = c.id
                                        WHERE
                                            a.case_type ='".$casetype_displ."' 
                                            AND $ten_reg_yr BETWEEN a.case_f_yr AND a.case_t_yr 
                                            AND a.state ='".$row['ref_agency_state_id']."'
                                            AND a.display = 'Y'";
                                            $section_ten_rs = $this->db->query($section_ten_q)->getResultArray();
                                          
                                            if (count($section_ten_rs) > 0) {
                                                $section_ten_row = $section_ten_rs[0];
                                                $row['section_name'] = $section_ten_row["section_name"];
                                            }
                                            else{
                                              $row['section_name'] = '';
                                            }

                if ($is_connected != '') {
                    $print_srno = "";

                } else {
                    $print_srno = $print_srno;
                    $psrno++;
                }
                 
                $output .= "<tr><td>$print_srno</td><td>" . $is_connected . "$comlete_fil_no_prt" . "<br/>" . $row['section_name'] ."<br/>" . $row['name'] . "</td><td>" . $pet_name . "</td><td>";

                  if ($padvname !== null) {
                      $output .= str_replace(",", ", ", trim($padvname, ","));
                  } else {
                      $output .= ""; 
                  }

                  $output .= "</td></tr>";


                $output .= "<tr><td></td><td style='font-style: italic;'>Versus</td><td style='font-style: italic;'>";
                $output .= "</td><td></td></tr>";
                $output .= "<tr><td></td><td></td><td>" . $res_name . "</td><td>";

                  $radvname_cleaned = "";
                  if (isset($radvname) && is_string($radvname)) {
                      $radvname_cleaned = str_replace(",", ", ", trim($radvname, ","));
                  }
                  $output .= $radvname_cleaned;

                  if (isset($impldname) && is_string($impldname)) {
                      $impldname_cleaned = str_replace(",", ", ", trim($impldname, ","));
                      if ($impldname_cleaned !== "") { 
                          $output .= "<br/>" . $impldname_cleaned;
                      }
                  }

                  $output .= "</td></tr>";
                if ($mainhead == "M" OR $mainhead == "F") {
                    $output .= "<tr><td colspan='2'></td><td colspan='2' style='font-weight:bold; color:blue;'>";
                    $output .= get_cl_brd_remark($diary_no) . "</td></tr>";
                }
                

            }//END OF WHILE LOOP
                                                                 
         
  }
  
  $output .= "<tr><td colspan='4' style='text-align:center;'>Record Not Found</td></tr>";
  $output .= "</table>";
  return $output;

}

  // public function get_allocation_report($board_type,$m_f,$from_to_dt){
  //   $sql = "SELECT r.id, GROUP_CONCAT(j.jcode ORDER BY j.judge_seniority) jcd, GROUP_CONCAT(CONCAT(j.jname) ORDER BY j.judge_seniority) jnm, rb.bench_no, mb.abbr, r.tot_cases, r.courtno FROM roster r 
  //               LEFT JOIN roster_bench rb ON rb.id = r.bench_id 
  //               LEFT JOIN master_bench mb ON mb.id = rb.bench_id 
  //               LEFT JOIN roster_judge rj ON rj.roster_id = r.id 
  //               LEFT JOIN judge j on j.jcode = rj.judge_id
  //               WHERE j.is_retired != 'Y' and mb.board_type_mb = '$board_type'
  //               and j.display  = 'Y' and rj.display = 'Y' and rb.display = 'Y' and mb.display = 'Y' and r.display = 'Y' $m_f $from_to_dt GROUP BY r.id ORDER BY r.courtno, r.id, j.judge_seniority";
  //               return ;
  // }


  public function getMattersReport($frm_dt, $to_dt, $advocate = '', $judge = '', $aor_code = '', $ddl_judge = '')
  {
    $query = $this->db->table('heardt a')
      ->select('a.diary_no, a.next_dt, b.conn_key, reg_no_display, active_fil_no, pet_name, res_name, 
                  pno, rno, a.brd_slno, a.roster_id, b.dacode, courtno')
      ->join('main b', 'a.diary_no = b.diary_no')
      ->join('cl_printed c', 'c.next_dt = a.next_dt AND c.m_f = a.mainhead AND c.part = a.clno AND a.roster_id = c.roster_id')
      ->join('master.roster y', 'y.id = a.roster_id')
      ->where('a.next_dt >=', $frm_dt)
      ->where('a.next_dt <=', $to_dt)
      ->where('a.clno !=', 0)
      ->where('a.brd_slno !=', 0)
      ->whereIn('a.main_supp_flag', ['1', '2'])
      ->where('c_status', 'P')
      ->where('c.display', 'Y');

    // Add condition for $aor_code if it's not empty
    if ($aor_code != '') {
      $query->join('advocate adv', 'adv.diary_no = b.diary_no AND adv.display = \'Y\'')
        ->join('master.bar z', 'z.bar_id = adv.advocate_id AND z.aor_code = \'' . $aor_code . '\'');
    }

    // Add condition for $ddl_judge if it's not empty
    if ($ddl_judge != '') {
      $query->join('master.roster_judge rj', 'rj.roster_id = a.roster_id AND rj.display = \'Y\' AND rj.judge_id = "' . $ddl_judge . '"');
    }

    // Perform the first SELECT (from 'heardt' table)
    $sql1 = $query->getCompiledSelect();

    // Now build the second part of the query (from 'last_heardt' table)
    $query2 = $this->db->table('last_heardt a')
      ->select('a.diary_no, a.next_dt, b.conn_key, reg_no_display, active_fil_no, pet_name, res_name, 
                  pno, rno, a.brd_slno, a.roster_id, b.dacode, courtno')
      ->join('main b', 'a.diary_no = b.diary_no')
      ->join('cl_printed c', 'c.next_dt = a.next_dt AND c.m_f = a.mainhead AND c.part = a.clno AND a.roster_id = c.roster_id')
      ->join('master.roster y', 'y.id = a.roster_id')
      ->where('a.next_dt >=', $frm_dt)
      ->where('a.next_dt <=', $to_dt)
      ->where('a.clno !=', 0)
      ->where('a.brd_slno !=', 0)
      ->whereIn('a.main_supp_flag', ['1', '2'])
      ->groupStart()
      ->where('bench_flag IS NULL')
      ->orWhere('bench_flag', '')
      ->groupEnd()
      ->groupStart()
      ->where('b.conn_key', '')
      ->orWhere('b.conn_key', '0')
      ->orWhere('b.conn_key', 'NULL')
      ->orWhere('b.conn_key = b.diary_no::text')
      ->groupEnd()
      ->where('c_status', 'P')
      ->where('c.display', 'Y');

    // Add condition for $aor_code if it's not empty
    if ($aor_code != '') {
      $query2->join('advocate adv', 'adv.diary_no = b.diary_no AND adv.display = \'Y\'')
        ->join('master.bar z', 'z.bar_id = adv.advocate_id AND z.aor_code = \'' . $aor_code . '\'');
    }

    // Add condition for $ddl_judge if it's not empty
    if ($ddl_judge != '') {
      $query2->join('master.roster_judge rj', 'rj.roster_id = a.roster_id AND rj.display = \'Y\' AND rj.judge_id = "' . $ddl_judge . '"');
    }

    // Perform the second SELECT (from 'last_heardt' table)
    $sql2 = $query2->getCompiledSelect();
    $combinedQuery = $sql1 . ' UNION ' . $sql2;
    $finalQuery = 'SELECT * FROM (
                      ' . $combinedQuery . ' 
                  ) AS combined_result
                  ORDER BY next_dt, courtno, roster_id, brd_slno, 
                  CASE 
                      WHEN conn_key = CAST(diary_no AS TEXT) THEN \'0\'
                      ELSE CONCAT(SUBSTRING(CAST(diary_no AS TEXT), -4), SUBSTRING(CAST(diary_no AS TEXT), 1, LENGTH(CAST(diary_no AS TEXT)) - 4)) 
                  END';
                  // echo $finalQuery;
                  // die();
    $result = $this->db->query($finalQuery);
    $data = $result->getResultArray();
    return $data;
  }

  public function get_holidays($current_year, $next_year)
  {
    $builder = $this->db->table('master.sc_working_days');

    $builder->select('working_date')
      ->where('is_holiday', 1)
      ->like('holiday_description', '%summer vacation%', 'before')
      ->groupStart()
      ->where('EXTRACT(YEAR FROM working_date)', $current_year)
      ->orWhere('EXTRACT(YEAR FROM working_date)', $next_year)
      ->groupEnd();

    $query = $builder->get();
    return $query->getResultArray();
  }

  public function get_ct_q()
  {
    $builder = $this->db->table('master.casetype');
    $builder->select('casecode, skey, casename, short_description')
      ->where('display', 'Y')
      ->where('casecode !=', 9999)
      ->orderBy('casecode')
      ->orderBy('short_description');
    $query = $builder->get();
    return $query->getResultArray();
  }

  
}
