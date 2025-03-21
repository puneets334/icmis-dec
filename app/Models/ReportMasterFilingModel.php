<?php

namespace App\Models;
use CodeIgniter\Model;

class ReportMasterFilingModel extends Model
{

    protected $db;

    public function __construct(){
       parent::__construct();
       $this->db = \Config\Database::connect();
	  
     }

    public function get_case_usertype_details($usercode, $r_section, $r_usertype){
        $row = array();
        if($usercode!='1' && $r_section!='30' && $r_usertype!='4' && $r_section!='20'){
                $query = $this->db->table("master.users")
                        ->select("empid, usertype")
                        ->where("usercode", $usercode)
                        ->get();

                if ($query->getNumRows() > 0) {
                    $row = $query->getRowArray(); 
                }
        } 

        $builder = $this->db->table("master.users a");
        $builder->distinct()->select("c.type_name, c.id");
        $builder->join("fil_trap_users b", "a.usercode = b.usercode AND b.display = 'Y'");
        $builder->join("master.usertype c", "(c.id = b.usertype AND (c.display = 'Y' OR c.display = 'E'))");
        $builder->whereIn("a.section", ["19", "77", "18"]);
        $builder->where("a.display", "Y");
        
        if (!empty($row)) {
            $builder->where("a.empid", $row["empid"]);
        }
        
        $builder->orderBy("c.id", "ASC");
        $query = $builder->get();
		//echo $this->db->getLastQuery();die;
        return $query->getResultArray();
                 
    }
   


    public function get_case_alloted_details($frm_dt, $to_dt,  $usercode, $ddl_users, $r_section, $r_usertype){
        if($ddl_users!='1' && $r_section!='30' && $r_usertype!='4' && $r_section!='20'){
                $query = $this->db->table("master.users")
                ->select("empid")
                ->where("usercode", $usercode)
                ->get();

                if ($query->getNumRows() > 0) {
                    $row = $query->getRowArray(); 
                }
        }

        if($usercode=='102'){
            $builder = $this->db->table("master.users as u");
            $builder->select('
                SUM(s) AS s, 
                SUM(ss) AS ss, 
                SUM(sss) AS sss, 
                SUM(ssss) AS ssss, 
                SUM(sssss) AS sssss, 
                u.empid AS d_to_empid, 
                u.usercode
            ');
            
            // Join with fil_trap_users (aliased as t_u)
            $builder->join('fil_trap_users as t_u', 'u.usercode = t_u.usercode');
            
            // Prepare the union subquery as a raw string. 
            // (Notice that we alias each column as needed and use proper formatting.)
            $subQuery = "
            (
                SELECT SUM(s) AS s, CAST(NULL AS BIGINT) AS ss, CAST(NULL AS BIGINT) AS sss, CAST(NULL AS BIGINT) AS ssss, CAST(NULL AS BIGINT) AS sssss, d_to_empid
                    FROM (
                        SELECT COUNT(uid) AS s, d_to_empid 
                        FROM fil_trap 
                        WHERE DATE(disp_dt) BETWEEN '{$frm_dt}' AND '{$to_dt}' AND remarks = 'FIL -> DE'
                        GROUP BY d_to_empid
                        UNION ALL
                        SELECT COUNT(uid) AS s, d_to_empid 
                        FROM fil_trap_his 
                        WHERE DATE(disp_dt) BETWEEN '{$frm_dt}' AND '{$to_dt}' AND remarks = 'FIL -> DE'
                        GROUP BY d_to_empid
                    ) aa
                    GROUP BY d_to_empid
                UNION ALL
            
                SELECT NULL AS s, COUNT(d_to_empid) AS ss, NULL AS sss, NULL AS ssss, NULL AS sssss, d_to_empid
                FROM (
                    SELECT DISTINCT zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2
                    FROM (
                        SELECT diary_no, d_to_empid, r_by_empid
                        FROM fil_trap
                        WHERE DATE(disp_dt) BETWEEN '{$frm_dt}' AND '{$to_dt}' 
                          AND remarks = 'FIL -> DE'
                          AND r_by_empid != 0
                        UNION ALL
                        SELECT diary_no, d_to_empid, r_by_empid
                        FROM fil_trap_his
                        WHERE DATE(disp_dt) BETWEEN '{$frm_dt}' AND '{$to_dt}' 
                          AND remarks = 'FIL -> DE'
                          AND r_by_empid != 0
                    ) zz
                    LEFT JOIN fil_trap AS xx ON xx.diary_no = zz.diary_no AND xx.remarks = 'DE -> SCR'
                    LEFT JOIN fil_trap_his AS ww ON ww.diary_no = zz.diary_no AND ww.remarks = 'DE -> SCR'
                    WHERE xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL
                ) aa
                GROUP BY d_to_empid
            
                UNION ALL
            
                SELECT NULL AS s, NULL AS ss, COUNT(d_to_empid) AS sss, NULL AS ssss, NULL AS sssss, d_to_empid
                FROM (
                    SELECT DISTINCT zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2
                    FROM (
                        SELECT diary_no, d_to_empid, r_by_empid
                        FROM fil_trap
                        WHERE remarks = 'FIL -> DE' AND disp_dt >= '2018-06-30'
                        UNION ALL
                        SELECT diary_no, d_to_empid, r_by_empid
                        FROM fil_trap_his
                        WHERE remarks = 'FIL -> DE' AND disp_dt >= '2018-06-30'
                    ) zz
                    LEFT JOIN fil_trap AS xx ON xx.diary_no = zz.diary_no AND xx.remarks = 'DE -> SCR'
                    LEFT JOIN fil_trap_his AS ww ON ww.diary_no = zz.diary_no AND ww.remarks = 'DE -> SCR'
                    WHERE xx.diary_no IS NULL AND ww.diary_no IS NULL
                ) aa
                GROUP BY d_to_empid
            
                UNION ALL
            
                SELECT NULL AS s, NULL AS ss, NULL AS sss, COUNT(r_by_empid) AS ssss, NULL AS sssss, r_by_empid AS d_to_empid
                FROM (
                    SELECT DISTINCT zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2
                    FROM (
                        SELECT diary_no, d_to_empid, r_by_empid
                        FROM fil_trap
                        WHERE DATE(comp_dt) BETWEEN '{$frm_dt}' AND '{$to_dt}' 
                          AND remarks = 'FIL -> DE'
                        UNION ALL
                        SELECT diary_no, d_to_empid, r_by_empid
                        FROM fil_trap_his
                        WHERE DATE(comp_dt) BETWEEN '{$frm_dt}' AND '{$to_dt}' 
                          AND remarks = 'FIL -> DE'
                    ) zz
                    LEFT JOIN fil_trap AS xx ON xx.diary_no = zz.diary_no AND xx.remarks = 'DE -> SCR'
                    LEFT JOIN fil_trap_his AS ww ON ww.diary_no = zz.diary_no AND ww.remarks = 'DE -> SCR'
                    WHERE xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL
                ) aa
                GROUP BY r_by_empid
            
                UNION ALL
            
                SELECT NULL AS s, NULL AS ss, NULL AS sss, NULL AS ssss, COUNT(r_by_empid) AS sssss, r_by_empid AS d_to_empid
                FROM (
                    SELECT diary_no, d_to_empid, r_by_empid
                    FROM fil_trap
                    WHERE DATE(rece_dt) BETWEEN '{$frm_dt}' AND '{$to_dt}' 
                      AND remarks = 'FIL -> DE'
                      AND r_by_empid != 0
                    UNION ALL
                    SELECT diary_no, d_to_empid, r_by_empid
                    FROM fil_trap_his
                    WHERE DATE(rece_dt) BETWEEN '{$frm_dt}' AND '{$to_dt}' 
                      AND remarks = 'FIL -> DE'
                      AND r_by_empid != 0
                ) zz
                GROUP BY r_by_empid
            ) bb
            ";
            
            // Now add the subquery join. The last parameter (false) tells CI not to escape the query string.
            $builder->join($subQuery, 'u.empid = bb.d_to_empid', 'left', false);
            
            // Add the WHERE conditions
            $builder->where('t_u.usertype', $usercode);
            $builder->where('t_u.display', 'Y');
            $builder->where('u.display', 'Y');
            
            // Group the results by u.usercode
            $builder->groupBy('u.usercode');
            
            // Get the results
            $query  = $builder->get();
           return  $query->getResultArray();
           
        }elseif($usercode=='103'){  
            $sql = "SELECT SUM(s) s, 
                       SUM(ss) ss, 
                       SUM(sss) sss,
                       SUM(r_s) r_s,
                       SUM(r_ss) r_ss,
                       SUM(r_sss) r_sss, 
                       d_to_empid,
                       SUM(ssss) ssss,
                       SUM(r_ssss) r_ssss,
                       SUM(sssss) sssss,
                       SUM(r_sssss) r_sssss
                FROM (
                  -- First main part
                  SELECT  SUM(s) s, 
                          SUM(ss) ss, 
                          SUM(sss) sss, 
                          NULL r_s,
                          NULL r_ss,
                          NULL r_sss,
                          NULL r_ssss,
                          SUM(ssss) ssss,
                          SUM(sssss) sssss,
                          NULL r_sssss,
                          u.empid d_to_empid,
                          u.usercode
                  FROM master.users u
                  JOIN fil_trap_users t_u ON u.usercode = t_u.usercode
                  LEFT JOIN (
                    -- Branch 1: counts from disp_dt in fil_trap and fil_trap_his
                    SELECT SUM(s) s, NULL::bigint ss, NULL::bigint sss, NULL::bigint ssss, NULL::bigint sssss, d_to_empid
                    FROM (
                        SELECT COUNT(uid) s, d_to_empid 
                        FROM fil_trap 
                        WHERE CAST(disp_dt AS DATE) BETWEEN ? AND ?
                          AND remarks = 'DE -> SCR'
                        GROUP BY d_to_empid
                        UNION ALL
                        SELECT COUNT(uid) s, d_to_empid 
                        FROM fil_trap_his 
                        WHERE CAST(disp_dt AS DATE) BETWEEN ? AND ?
                          AND remarks = 'DE -> SCR'
                        GROUP BY d_to_empid
                    ) aa
                    GROUP BY d_to_empid
                    UNION ALL
                    -- Branch 2: aggregated details from disp_dt (using MIN to pick a representative value)
                    SELECT NULL::bigint s, COUNT(d_to_empid) ss, NULL::bigint sss, NULL::bigint ssss, NULL::bigint sssss, d_to_empid
                    FROM (
                        SELECT 
                           zz.diary_no,
                           zz.d_to_empid,
                           zz.r_by_empid,
                           MIN(xx.diary_no) AS d_no,
                           MIN(ww.diary_no) AS d_no2,
                           MIN(mn.fil_no) AS fil_no,
                           MIN(xx.rece_dt) AS rece_dt,
                           MIN(ww.rece_dt) AS yy
                        FROM (
                            SELECT diary_no, d_to_empid, r_by_empid 
                            FROM fil_trap 
                            WHERE CAST(disp_dt AS DATE) BETWEEN ? AND ?
                              AND remarks = 'DE -> SCR'
                            UNION ALL
                            SELECT diary_no, d_to_empid, r_by_empid 
                            FROM fil_trap_his 
                            WHERE CAST(disp_dt AS DATE) BETWEEN ? AND ?
                              AND remarks = 'DE -> SCR'
                        ) zz
                        LEFT JOIN fil_trap xx 
                          ON xx.diary_no = zz.diary_no 
                          AND xx.remarks IN ('SCR -> AOR', 'AUTO -> CAT', 'SCR -> CAT', 'SCR -> REF', 'SCR -> FDR')
                        LEFT JOIN fil_trap_his ww 
                          ON ww.diary_no = zz.diary_no 
                          AND ww.remarks IN ('SCR -> AOR', 'AUTO -> CAT', 'SCR -> CAT', 'SCR -> REF', 'SCR -> FDR')
                        LEFT JOIN main mn 
                          ON mn.diary_no = zz.diary_no
                        WHERE (xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL OR mn.fil_no IS NOT NULL)
                        GROUP BY zz.diary_no, zz.d_to_empid, zz.r_by_empid
                    ) aa
                    GROUP BY d_to_empid
                    UNION ALL
                    -- Branch 3: counts from disp_dt with condition disp_dt >= '2018-06-30'
                    SELECT NULL::bigint s, NULL::bigint ss, COUNT(d_to_empid) sss, NULL::bigint ssss, NULL::bigint sssss, d_to_empid
                    FROM (
                        SELECT 
                           zz.diary_no,
                           zz.d_to_empid,
                           zz.r_by_empid,
                           MIN(xx.diary_no) AS d_no,
                           MIN(ww.diary_no) AS d_no2,
                           MIN(mn.fil_no) AS fil_no
                        FROM (
                            SELECT diary_no, d_to_empid, r_by_empid 
                            FROM fil_trap 
                            WHERE remarks = 'DE -> SCR' 
                              AND disp_dt >= '2018-06-30'
                            UNION ALL
                            SELECT diary_no, d_to_empid, r_by_empid 
                            FROM fil_trap_his 
                            WHERE remarks = 'DE -> SCR' 
                              AND disp_dt >= '2018-06-30'
                        ) zz
                        LEFT JOIN fil_trap xx 
                          ON xx.diary_no = zz.diary_no 
                          AND xx.remarks IN ('SCR -> AOR', 'AUTO -> CAT', 'SCR -> CAT', 'SCR -> REF', 'SCR -> FDR')
                        LEFT JOIN fil_trap_his ww 
                          ON ww.diary_no = zz.diary_no 
                          AND ww.remarks IN ('SCR -> AOR', 'AUTO -> CAT', 'SCR -> CAT', 'SCR -> REF', 'SCR -> FDR')
                        LEFT JOIN main mn 
                          ON mn.diary_no = zz.diary_no
                        WHERE (xx.diary_no IS NULL AND ww.diary_no IS NULL AND (mn.fil_no IS NULL OR mn.fil_no = ''))
                        GROUP BY zz.diary_no, zz.d_to_empid, zz.r_by_empid
                    ) aa
                    GROUP BY d_to_empid
                    UNION ALL
                    -- Branch 4: counts from comp_dt in fil_trap and fil_trap_his
                    SELECT NULL::bigint s, NULL::bigint ss, NULL::bigint sss, COUNT(r_by_empid) ssss, NULL::bigint sssss, r_by_empid d_to_empid
                    FROM (
                        SELECT 
                           zz.diary_no,
                           zz.d_to_empid,
                           zz.r_by_empid,
                           MIN(xx.diary_no) AS d_no,
                           MIN(ww.diary_no) AS d_no2,
                           MIN(mn.fil_no) AS fil_no
                        FROM (
                            SELECT diary_no, d_to_empid, r_by_empid 
                            FROM fil_trap 
                            WHERE CAST(comp_dt AS DATE) BETWEEN ? AND ?
                              AND remarks = 'DE -> SCR'
                            UNION ALL
                            SELECT diary_no, d_to_empid, r_by_empid 
                            FROM fil_trap_his 
                            WHERE CAST(comp_dt AS DATE) BETWEEN ? AND ?
                              AND remarks = 'DE -> SCR'
                        ) zz
                        LEFT JOIN fil_trap xx 
                          ON xx.diary_no = zz.diary_no 
                          AND xx.remarks IN ('SCR -> AOR', 'AUTO -> CAT', 'SCR -> CAT', 'SCR -> REF', 'SCR -> FDR')
                        LEFT JOIN fil_trap_his ww 
                          ON ww.diary_no = zz.diary_no 
                          AND ww.remarks IN ('SCR -> AOR', 'AUTO -> CAT', 'SCR -> CAT', 'SCR -> REF', 'SCR -> FDR')
                        LEFT JOIN main mn 
                          ON mn.diary_no = zz.diary_no
                        WHERE (xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL OR mn.fil_no IS NOT NULL)
                        GROUP BY zz.diary_no, zz.d_to_empid, zz.r_by_empid
                    ) aa
                    GROUP BY r_by_empid
                    UNION ALL
                    -- Branch 5: counts from rece_dt in fil_trap and fil_trap_his
                    SELECT NULL::bigint s, NULL::bigint ss, NULL::bigint sss, NULL::bigint ssss, COUNT(r_by_empid) sssss, r_by_empid d_to_empid
                    FROM (
                        SELECT diary_no, d_to_empid, r_by_empid 
                        FROM fil_trap 
                        WHERE CAST(rece_dt AS DATE) BETWEEN ? AND ?
                          AND remarks = 'DE -> SCR' 
                          AND r_by_empid != 0
                        UNION ALL
                        SELECT diary_no, d_to_empid, r_by_empid 
                        FROM fil_trap_his 
                        WHERE CAST(rece_dt AS DATE) BETWEEN ? AND ?
                          AND remarks = 'DE -> SCR' 
                          AND r_by_empid != 0
                    ) zz
                    GROUP BY r_by_empid
                  ) bb ON u.empid = bb.d_to_empid
                  WHERE t_u.usertype = ?
                    AND t_u.display = 'Y'
                    AND u.display = 'Y'
                  GROUP BY u.usercode
                  
                  UNION ALL
                  
                  -- Second main part
                  SELECT NULL::bigint s, NULL::bigint ss, NULL::bigint sss, 
                         SUM(s) r_s, 
                         SUM(ss) r_ss, 
                         SUM(sss) r_sss, 
                         SUM(ssss) r_ssss, 
                         NULL::bigint ssss, 
                         NULL::bigint sssss, 
                         SUM(sssss) r_sssss,
                         u.empid d_to_empid,
                         u.usercode
                  FROM master.users u
                  JOIN fil_trap_users t_u ON u.usercode = t_u.usercode
                  LEFT JOIN (
                    -- Branch 1 for second part
                    SELECT SUM(s) s, NULL::bigint ss, NULL::bigint sss, NULL::bigint ssss, NULL::bigint sssss, d_to_empid
                    FROM (
                        SELECT COUNT(uid) s, d_to_empid 
                        FROM fil_trap 
                        WHERE CAST(disp_dt AS DATE) BETWEEN ? AND ?
                          AND (remarks = 'AOR -> SCR' OR remarks = 'FDR -> SCR')
                        GROUP BY d_to_empid
                        UNION ALL
                        SELECT COUNT(uid) s, d_to_empid 
                        FROM fil_trap_his 
                        WHERE CAST(disp_dt AS DATE) BETWEEN ? AND ?
                          AND (remarks = 'AOR -> SCR' OR remarks = 'FDR -> SCR')
                        GROUP BY d_to_empid
                    ) aa
                    GROUP BY d_to_empid
                    UNION ALL
                    -- Branch 2 for second part: aggregated details with disp_dt and r_by_empid != 0
                    SELECT NULL::bigint s, COUNT(d_to_empid) ss, NULL::bigint sss, NULL::bigint ssss, NULL::bigint sssss, d_to_empid
                    FROM (
                        SELECT 
                           zz.diary_no,
                           zz.d_to_empid,
                           zz.r_by_empid,
                           MIN(xx.diary_no) AS d_no,
                           MIN(ww.diary_no) AS d_no2,
                           MIN(mn.fil_no) AS fil_no,
                           MIN(xx.rece_dt) AS rece_dt,
                           MIN(ww.rece_dt) AS yy
                        FROM (
                            SELECT diary_no, d_to_empid, r_by_empid, disp_dt 
                            FROM fil_trap 
                            WHERE CAST(disp_dt AS DATE) BETWEEN ? AND ?
                              AND (remarks = 'AOR -> SCR' OR remarks = 'FDR -> SCR')
                              AND r_by_empid != 0
                            UNION ALL
                            SELECT diary_no, d_to_empid, r_by_empid, disp_dt 
                            FROM fil_trap_his 
                            WHERE CAST(disp_dt AS DATE) BETWEEN ? AND ?
                              AND (remarks = 'AOR -> SCR' OR remarks = 'FDR -> SCR')
                              AND r_by_empid != 0
                        ) zz
                        LEFT JOIN fil_trap xx 
                          ON xx.diary_no = zz.diary_no 
                          AND xx.remarks IN ('SCR -> AOR', 'AUTO -> CAT', 'SCR -> CAT', 'SCR -> FDR')
                        LEFT JOIN fil_trap_his ww 
                          ON ww.diary_no = zz.diary_no 
                          AND ww.remarks IN ('SCR -> AOR', 'AUTO -> CAT', 'SCR -> CAT', 'SCR -> FDR')
                        LEFT JOIN main mn 
                          ON mn.diary_no = zz.diary_no
                        WHERE (xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL OR mn.fil_no IS NOT NULL)
                        GROUP BY zz.diary_no, zz.d_to_empid, zz.r_by_empid
                    ) aa
                    GROUP BY d_to_empid
                    UNION ALL
                    -- Branch 3 for second part: counts from disp_dt with condition disp_dt >= '2018-06-30'
                    SELECT NULL::bigint s, NULL::bigint ss, COUNT(d_to_empid) sss, NULL::bigint ssss, NULL::bigint sssss, d_to_empid
                    FROM (
                        SELECT 
                           zz.diary_no,
                           zz.d_to_empid,
                           zz.r_by_empid,
                           MIN(xx.diary_no) AS d_no,
                           MIN(ww.diary_no) AS d_no2,
                           MIN(mn.fil_no) AS fil_no
                        FROM (
                            SELECT diary_no, d_to_empid, r_by_empid, disp_dt 
                            FROM fil_trap 
                            WHERE (remarks = 'AOR -> SCR' OR remarks = 'FDR -> SCR') 
                              AND disp_dt >= '2018-06-30'
                            UNION ALL
                            SELECT diary_no, d_to_empid, r_by_empid, disp_dt 
                            FROM fil_trap_his 
                            WHERE (remarks = 'AOR -> SCR' OR remarks = 'FDR -> SCR') 
                              AND disp_dt >= '2018-06-30'
                        ) zz
                        LEFT JOIN fil_trap xx 
                          ON xx.diary_no = zz.diary_no 
                          AND xx.remarks IN ('SCR -> AOR', 'AUTO -> CAT', 'SCR -> CAT', 'SCR -> FDR')
                        LEFT JOIN fil_trap_his ww 
                          ON ww.diary_no = zz.diary_no 
                          AND ww.remarks IN ('SCR -> AOR', 'AUTO -> CAT', 'SCR -> CAT', 'SCR -> FDR')
                        LEFT JOIN main mn 
                          ON mn.diary_no = zz.diary_no
                        WHERE (xx.diary_no IS NULL AND ww.diary_no IS NULL AND (mn.fil_no IS NULL OR mn.fil_no = ''))
                        GROUP BY zz.diary_no, zz.d_to_empid, zz.r_by_empid
                    ) aa
                    GROUP BY d_to_empid
                    UNION ALL
                    -- Branch 4 for second part: aggregated details from comp_dt
                    SELECT NULL::bigint s, NULL::bigint ss, NULL::bigint sss, COUNT(r_by_empid) ssss, NULL::bigint sssss, r_by_empid d_to_empid
                    FROM (
                        SELECT 
                           zz.diary_no,
                           zz.d_to_empid,
                           zz.r_by_empid,
                           MIN(xx.diary_no) AS d_no,
                           MIN(ww.diary_no) AS d_no2,
                           MIN(mn.fil_no) AS fil_no,
                           MIN(xx.rece_dt) AS rece_dt,
                           MIN(ww.rece_dt) AS yy
                        FROM (
                            SELECT diary_no, d_to_empid, r_by_empid, disp_dt 
                            FROM fil_trap 
                            WHERE CAST(comp_dt AS DATE) BETWEEN ? AND ?
                              AND (remarks = 'AOR -> SCR' OR remarks = 'FDR -> SCR')
                              AND r_by_empid != 0
                            UNION ALL
                            SELECT diary_no, d_to_empid, r_by_empid, disp_dt 
                            FROM fil_trap_his 
                            WHERE CAST(comp_dt AS DATE) BETWEEN ? AND ?
                              AND (remarks = 'AOR -> SCR' OR remarks = 'FDR -> SCR')
                              AND r_by_empid != 0
                        ) zz
                        LEFT JOIN fil_trap xx 
                          ON xx.diary_no = zz.diary_no 
                          AND xx.remarks IN ('SCR -> AOR', 'AUTO -> CAT', 'SCR -> CAT', 'SCR -> FDR')
                        LEFT JOIN fil_trap_his ww 
                          ON ww.diary_no = zz.diary_no 
                          AND ww.remarks IN ('SCR -> AOR', 'AUTO -> CAT', 'SCR -> CAT', 'SCR -> FDR')
                        LEFT JOIN main mn 
                          ON mn.diary_no = zz.diary_no
                        WHERE (xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL OR mn.fil_no IS NOT NULL)
                        GROUP BY zz.diary_no, zz.d_to_empid, zz.r_by_empid
                    ) aa
                    GROUP BY r_by_empid
                    UNION ALL
                    -- Branch 5 for second part: counts from rece_dt
                    SELECT NULL::bigint s, NULL::bigint ss, NULL::bigint sss, NULL::bigint ssss, COUNT(r_by_empid) sssss, r_by_empid d_to_empid
                    FROM (
                        SELECT diary_no, d_to_empid, r_by_empid 
                        FROM fil_trap 
                        WHERE CAST(rece_dt AS DATE) BETWEEN ? AND ?
                          AND (remarks = 'AOR -> SCR' OR remarks = 'FDR -> SCR')
                          AND r_by_empid != 0
                        UNION ALL
                        SELECT diary_no, d_to_empid, r_by_empid 
                        FROM fil_trap_his 
                        WHERE CAST(rece_dt AS DATE) BETWEEN ? AND ?
                          AND (remarks = 'AOR -> SCR' OR remarks = 'FDR -> SCR')
                          AND r_by_empid != 0
                    ) zz
                    GROUP BY r_by_empid
                  ) bb ON u.empid = bb.d_to_empid
                  WHERE t_u.usertype = ?
                    AND t_u.display = 'Y'
                    AND u.display = 'Y'
                  GROUP BY u.usercode
                ) tt
                GROUP BY tt.usercode, tt.d_to_empid
                ORDER BY s DESC";
        
        $params = [
            // First main part:
            // Branch 1: fil_trap disp_dt
            $frm_dt, $to_dt,
            // Branch 1: fil_trap_his disp_dt
            $frm_dt, $to_dt,
            // Branch 2: fil_trap disp_dt
            $frm_dt, $to_dt,
            // Branch 2: fil_trap_his disp_dt
            $frm_dt, $to_dt,
            // Branch 3: fil_trap comp_dt
            $frm_dt, $to_dt,
            // Branch 3: fil_trap_his comp_dt
            $frm_dt, $to_dt,
            // Branch 4: fil_trap rece_dt
            $frm_dt, $to_dt,
            // Branch 4: fil_trap_his rece_dt
            $frm_dt, $to_dt,
            // t_u.usertype for first main part
            $usercode,
            
            // Second main part:
            // Branch 1: fil_trap disp_dt
            $frm_dt, $to_dt,
            // Branch 1: fil_trap_his disp_dt
            $frm_dt, $to_dt,
            // Branch 2: fil_trap disp_dt (with r_by_empid != 0)
            $frm_dt, $to_dt,
            // Branch 2: fil_trap_his disp_dt (with r_by_empid != 0)
            $frm_dt, $to_dt,
            // Branch 3: fil_trap comp_dt
            $frm_dt, $to_dt,
            // Branch 3: fil_trap_his comp_dt
            $frm_dt, $to_dt,
            // Branch 4: fil_trap rece_dt
            $frm_dt, $to_dt,
            // Branch 4: fil_trap_his rece_dt
            $frm_dt, $to_dt,
            // t_u.usertype for second main part
            $usercode
        ];
        
        $query = $this->db->query($sql, $params);
        return $query->getResultArray();
       }elseif($usercode=='105'){ 
                
                $subquery = "
                SELECT SUM(s) AS s, NULL::bigint AS ss, NULL::bigint AS sss, NULL::bigint AS ssss, NULL::bigint AS sssss, r_by_empid FROM (
                    SELECT COUNT(uid) AS s, r_by_empid 
                    FROM fil_trap 
                    WHERE DATE(disp_dt) BETWEEN '".$frm_dt."' AND '".$to_dt."' 
                    AND (remarks = 'SCR -> CAT' OR remarks = 'AUTO -> CAT') 
                    GROUP BY r_by_empid
                    UNION ALL
                    SELECT COUNT(uid) AS s, r_by_empid 
                    FROM fil_trap_his 
                    WHERE DATE(disp_dt) BETWEEN '".$frm_dt."' AND '".$to_dt."' 
                    AND (remarks = 'SCR -> CAT' OR remarks = 'AUTO -> CAT') 
                    GROUP BY r_by_empid
                ) aa
                GROUP BY r_by_empid
            
                UNION ALL
            
                SELECT NULL::bigint AS s, COUNT(r_by_empid) AS ss, NULL::bigint AS sss, NULL::bigint AS ssss, NULL::bigint AS sssss, r_by_empid FROM (
                    SELECT DISTINCT zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2 FROM (
                        SELECT diary_no, d_to_empid, r_by_empid 
                        FROM fil_trap 
                        WHERE DATE(disp_dt) BETWEEN '".$frm_dt."' AND '".$to_dt."' 
                        AND (remarks = 'SCR -> CAT' OR remarks = 'AUTO -> CAT') 
                        AND r_by_empid != 0
                        UNION ALL
                        SELECT diary_no, d_to_empid, r_by_empid 
                        FROM fil_trap_his 
                        WHERE DATE(disp_dt) BETWEEN '".$frm_dt."' AND '".$to_dt."' 
                        AND (remarks = 'SCR -> CAT' OR remarks = 'AUTO -> CAT') 
                        AND r_by_empid != 0
                    ) zz 
                    LEFT JOIN fil_trap xx ON xx.diary_no = zz.diary_no 
                    AND (xx.remarks = 'CAT -> TAG' OR xx.remarks = 'CAT -> SCN' OR xx.remarks = 'CAT -> IB-EX')
                    LEFT JOIN fil_trap_his ww ON ww.diary_no = zz.diary_no 
                    AND (ww.remarks = 'CAT -> TAG' OR ww.remarks = 'CAT -> SCN' OR ww.remarks = 'CAT -> IB-EX')
                    WHERE (xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL)
                ) aa
                GROUP BY r_by_empid
            
                UNION ALL
            
                SELECT NULL::bigint AS s, NULL::bigint AS ss, COUNT(r_by_empid) AS sss, NULL::bigint AS ssss, NULL::bigint AS sssss, r_by_empid FROM (
                    SELECT DISTINCT zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2 FROM (
                        SELECT diary_no, d_to_empid, r_by_empid 
                        FROM fil_trap 
                        WHERE (remarks = 'SCR -> CAT' OR remarks = 'AUTO -> CAT') 
                        AND disp_dt >= '2018-06-30'
                        UNION ALL
                        SELECT diary_no, d_to_empid, r_by_empid 
                        FROM fil_trap_his 
                        WHERE (remarks = 'SCR -> CAT' OR remarks = 'AUTO -> CAT') 
                        AND disp_dt >= '2018-06-30'
                    ) zz 
                    LEFT JOIN fil_trap xx ON xx.diary_no = zz.diary_no 
                    AND (xx.remarks = 'CAT -> TAG' OR xx.remarks = 'CAT -> SCN' OR xx.remarks = 'CAT -> IB-EX')
                    LEFT JOIN fil_trap_his ww ON ww.diary_no = zz.diary_no 
                    AND (ww.remarks = 'CAT -> TAG' OR ww.remarks = 'CAT -> SCN' OR ww.remarks = 'CAT -> IB-EX')
                    WHERE (xx.diary_no IS NULL AND ww.diary_no IS NULL)
                ) aa
                GROUP BY r_by_empid
            
                UNION ALL
            
                SELECT NULL::bigint AS s, NULL::bigint AS ss, NULL::bigint AS sss, COUNT(r_by_empid) AS ssss, NULL::bigint AS sssss, r_by_empid FROM (
                    SELECT DISTINCT zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2 FROM (
                        SELECT diary_no, d_to_empid, r_by_empid 
                        FROM fil_trap 
                        WHERE DATE(comp_dt) BETWEEN '".$frm_dt."' AND '".$to_dt."' 
                        AND (remarks = 'SCR -> CAT' OR remarks = 'AUTO -> CAT')
                        AND r_by_empid != 0
                        UNION ALL
                        SELECT diary_no, d_to_empid, r_by_empid 
                        FROM fil_trap_his 
                        WHERE DATE(comp_dt) BETWEEN '".$frm_dt."' AND '".$to_dt."' 
                        AND (remarks = 'SCR -> CAT' OR remarks = 'AUTO -> CAT')
                        AND r_by_empid != 0
                    ) zz 
                    LEFT JOIN fil_trap xx ON xx.diary_no = zz.diary_no 
                    AND (xx.remarks = 'CAT -> TAG' OR xx.remarks = 'CAT -> SCN' OR xx.remarks = 'CAT -> IB-EX')
                    LEFT JOIN fil_trap_his ww ON ww.diary_no = zz.diary_no 
                    AND (ww.remarks = 'CAT -> TAG' OR ww.remarks = 'CAT -> SCN' OR ww.remarks = 'CAT -> IB-EX')
                    WHERE (xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL)
                ) aa
                GROUP BY r_by_empid
            
                UNION ALL
            
                SELECT NULL::bigint AS s, NULL::bigint AS ss, NULL::bigint AS sss, NULL::bigint AS ssss, COUNT(r_by_empid) AS sssss, r_by_empid FROM (
                    SELECT diary_no, d_to_empid, r_by_empid 
                    FROM fil_trap 
                    WHERE DATE(rece_dt) BETWEEN '".$frm_dt."' AND '".$to_dt."' 
                    AND (remarks = 'SCR -> CAT' OR remarks = 'AUTO -> CAT') 
                    AND r_by_empid != 0
                    UNION ALL
                    SELECT diary_no, d_to_empid, r_by_empid 
                    FROM fil_trap_his 
                    WHERE DATE(rece_dt) BETWEEN '".$frm_dt."' AND '".$to_dt."' 
                    AND (remarks = 'SCR -> CAT' OR remarks = 'AUTO -> CAT') 
                    AND r_by_empid != 0
                ) zz
                GROUP BY r_by_empid
            ";
            
            // Build the main query using Query Builder
            $builder = $this->db->table('master.users u');
            $builder->select('
                SUM(s) AS s,
                SUM(ss) AS ss,
                SUM(sss) AS sss,
                SUM(ssss) AS ssss,
                SUM(sssss) AS sssss,
                u.empid AS d_to_empid,
                u.usercode
            ');
            $builder->join('fil_trap_users t_u', 'u.usercode = t_u.usercode');
            $builder->join("({$subquery}) bb", 'u.empid = bb.r_by_empid', 'left');
            $builder->where('t_u.usertype', $usercode);
            $builder->where('t_u.display', 'Y');
            $builder->where('u.display', 'Y');
            $builder->groupBy('u.usercode');
            
            // Execute the query
            $query = $builder->get();
            $result1 = $query->getResultArray();
           
           // data query second data        
            $usercode = '27';
            $subQuery = " 
                SELECT SUM(s) s, CAST(NULL AS BIGINT) AS ss, CAST(NULL AS BIGINT) AS sss, CAST(NULL AS BIGINT) AS ssss, CAST(NULL AS BIGINT) AS sssss, d_to_empid 
                FROM (
                    SELECT COUNT(uid) s, d_to_empid 
                    FROM fil_trap 
                    WHERE DATE(disp_dt) BETWEEN '{$frm_dt}' AND '{$to_dt}' 
                    AND (remarks = 'SCR -> CAT' OR remarks = 'AUTO -> CAT') 
                    AND r_by_empid = 0 
                    AND d_to_empid = '{$usercode}' 
                    GROUP BY d_to_empid 
                    UNION ALL 
                    SELECT COUNT(uid) s, d_to_empid 
                    FROM fil_trap_his 
                    WHERE DATE(disp_dt) BETWEEN '{$frm_dt}' AND '{$to_dt}' 
                    AND (remarks = 'SCR -> CAT' OR remarks = 'AUTO -> CAT') 
                    AND r_by_empid = 0 
                    AND d_to_empid = '{$usercode}' 
                    GROUP BY d_to_empid
                ) aa 
                GROUP BY d_to_empid 
                UNION ALL 
                SELECT 
                    CAST(NULL AS BIGINT) AS s, 
                    CAST(NULL AS BIGINT) AS ss, 
                    COUNT(d_to_empid) AS sss, 
                    CAST(NULL AS BIGINT) AS ssss, 
                    CAST(NULL AS BIGINT) AS sssss, 
                    d_to_empid 
                FROM (
                    SELECT DISTINCT zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2 
                    FROM (
                        SELECT diary_no, d_to_empid, r_by_empid 
                        FROM fil_trap 
                        WHERE (remarks = 'SCR -> CAT' OR remarks = 'AUTO -> CAT') 
                        AND disp_dt >= '2018-06-01' 
                        AND r_by_empid = 0 
                        AND d_to_empid = '{$usercode}' 
                        UNION ALL 
                        SELECT diary_no, d_to_empid, r_by_empid 
                        FROM fil_trap_his 
                        WHERE (remarks = 'SCR -> CAT' OR remarks = 'AUTO -> CAT') 
                        AND disp_dt >= '2018-06-01' 
                        AND r_by_empid = 0 
                        AND d_to_empid = '{$usercode}' 
                    ) zz 
                    LEFT JOIN fil_trap xx ON xx.diary_no = zz.diary_no 
                    AND (xx.remarks = 'CAT -> TAG' OR xx.remarks = 'CAT -> SCN') 
                    LEFT JOIN fil_trap_his ww ON ww.diary_no = zz.diary_no 
                    AND (ww.remarks = 'CAT -> TAG' OR ww.remarks = 'CAT -> SCN') 
                    WHERE (xx.diary_no IS NULL AND ww.diary_no IS NULL)
                ) aa 
                GROUP BY d_to_empid
            ";

            $builder = $this->db->table("master.users u");
            $builder->select("
                SUM(bb.s) AS s, 
                SUM(bb.ss) AS ss, 
                SUM(bb.sss) AS sss, 
                SUM(bb.ssss) AS ssss, 
                SUM(bb.sssss) AS sssss, 
                u.empid AS d_to_empid, 
                u.usercode
            ", false);

            // Join the fil_trap_users table
            $builder->join('fil_trap_users t_u', 'u.usercode = t_u.usercode');

            // Join the subquery as a derived table (escape disabled)
            $builder->join("({$subQuery}) bb", 'u.empid = bb.d_to_empid', 'left', false);

            // Apply conditions
            $builder->where('u.usercode', $usercode);
            $builder->groupBy('u.empid, u.usercode');

            // Execute the query and return results
            $query  = $builder->get();
            $result2 = $query->getResultArray();
           return array('result1' => $result1, 'result2' => $result2);
         }elseif($usercode=='106'){
            $subQuery = "(
                SELECT SUM(s) s, NULL::bigint ss, NULL::bigint sss, NULL::bigint ssss, NULL::bigint sssss, d_to_empid
                FROM (
                    SELECT COUNT(uid) s, d_to_empid
                    FROM fil_trap
                    WHERE DATE(disp_dt) BETWEEN '$frm_dt' AND '$to_dt'
                      AND remarks = 'CAT -> TAG'
                    GROUP BY d_to_empid
                    UNION ALL
                    SELECT COUNT(uid) s, d_to_empid
                    FROM fil_trap_his
                    WHERE DATE(disp_dt) BETWEEN '$frm_dt' AND '$to_dt'
                      AND remarks = 'CAT -> TAG'
                    GROUP BY d_to_empid
                ) aa
                GROUP BY d_to_empid
            
                UNION ALL
            
                SELECT NULL::bigint s, COUNT(d_to_empid) ss, NULL::bigint sss, NULL::bigint ssss, NULL::bigint sssss, d_to_empid
                FROM (
                    SELECT DISTINCT zz.*, xx.diary_no d_no, ww.diary_no d_no2
                    FROM (
                        SELECT diary_no, d_to_empid, r_by_empid
                        FROM fil_trap
                        WHERE DATE(disp_dt) BETWEEN '$frm_dt' AND '$to_dt'
                          AND remarks = 'CAT -> TAG'
                          AND r_by_empid != 0
                        UNION ALL
                        SELECT diary_no, d_to_empid, r_by_empid
                        FROM fil_trap_his
                        WHERE DATE(disp_dt) BETWEEN '$frm_dt' AND '$to_dt'
                          AND remarks = 'CAT -> TAG'
                          AND r_by_empid != 0
                    ) zz
                    LEFT JOIN fil_trap xx ON xx.diary_no = zz.diary_no 
                        AND (xx.remarks = 'TAG -> SCN')
                    LEFT JOIN fil_trap_his ww ON ww.diary_no = zz.diary_no 
                        AND (ww.remarks = 'TAG -> SCN')
                    WHERE (xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL)
                ) aa
                GROUP BY d_to_empid
            
                UNION ALL
            
                SELECT NULL::bigint s, NULL::bigint ss, COUNT(d_to_empid) sss, NULL::bigint ssss, NULL::bigint sssss, d_to_empid
                FROM (
                    SELECT DISTINCT zz.*, xx.diary_no d_no, ww.diary_no d_no2
                    FROM (
                        SELECT diary_no, d_to_empid, r_by_empid
                        FROM fil_trap
                        WHERE remarks = 'CAT -> TAG'
                          AND disp_dt >= '2018-06-30'
                        UNION ALL
                        SELECT diary_no, d_to_empid, r_by_empid
                        FROM fil_trap_his
                        WHERE remarks = 'CAT -> TAG'
                          AND disp_dt >= '2018-06-30'
                    ) zz
                    LEFT JOIN fil_trap xx ON xx.diary_no = zz.diary_no 
                        AND (xx.remarks = 'TAG -> SCN')
                    LEFT JOIN fil_trap_his ww ON ww.diary_no = zz.diary_no 
                        AND (ww.remarks = 'TAG -> SCN')
                    WHERE (xx.diary_no IS NULL AND ww.diary_no IS NULL)
                ) aa
                GROUP BY d_to_empid
            
                UNION ALL
            
                SELECT NULL::bigint s, NULL::bigint ss, NULL::bigint sss, COUNT(r_by_empid) ssss, NULL::bigint sssss, r_by_empid d_to_empid
                FROM (
                    SELECT DISTINCT zz.*, xx.diary_no d_no, ww.diary_no d_no2
                    FROM (
                        SELECT diary_no, d_to_empid, r_by_empid
                        FROM fil_trap
                        WHERE DATE(comp_dt) BETWEEN '$frm_dt' AND '$to_dt'
                          AND remarks = 'CAT -> TAG'
                        UNION ALL
                        SELECT diary_no, d_to_empid, r_by_empid
                        FROM fil_trap_his
                        WHERE DATE(comp_dt) BETWEEN '$frm_dt' AND '$to_dt'
                          AND remarks = 'CAT -> TAG'
                    ) zz
                    LEFT JOIN fil_trap xx ON xx.diary_no = zz.diary_no 
                        AND (xx.remarks = 'TAG -> SCN')
                    LEFT JOIN fil_trap_his ww ON ww.diary_no = zz.diary_no 
                        AND (ww.remarks = 'TAG -> SCN')
                    WHERE (xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL)
                ) aa
                GROUP BY r_by_empid
            
                UNION ALL
            
                SELECT NULL::bigint s, NULL::bigint ss, NULL::bigint sss, NULL::bigint ssss, COUNT(r_by_empid) sssss, r_by_empid d_to_empid
                FROM (
                    SELECT diary_no, d_to_empid, r_by_empid
                    FROM fil_trap
                    WHERE DATE(rece_dt) BETWEEN '$frm_dt' AND '$to_dt'
                      AND remarks = 'CAT -> TAG'
                      AND r_by_empid != 0
                    UNION ALL
                    SELECT diary_no, d_to_empid, r_by_empid
                    FROM fil_trap_his
                    WHERE DATE(rece_dt) BETWEEN '$frm_dt' AND '$to_dt'
                      AND remarks = 'CAT -> TAG'
                      AND r_by_empid != 0
                ) zz
                GROUP BY r_by_empid
            ) bb";
            
            $builder = $this->db->table("master.users u");
            $builder->select("SUM(s) s, SUM(ss) ss, SUM(sss) sss, SUM(ssss) ssss, SUM(sssss) sssss, u.empid d_to_empid, u.usercode", false);
            $builder->join('fil_trap_users t_u', 'u.usercode = t_u.usercode');
            $builder->join("{$subQuery}", "u.empid = bb.d_to_empid", 'left', false);
            $builder->where('t_u.usertype', $usercode);
            $builder->where('t_u.display', 'Y');
            $builder->where('u.display', 'Y');
            $builder->groupBy('u.usercode');
            
            $query = $builder->get();
            return $query->getResultArray();
        }elseif($usercode=='9796'){
            $sql = "
            SELECT 
                SUM(s) AS s, 
                SUM(ss) AS ss, 
                SUM(sss) AS sss, 
                SUM(ssss) AS ssss, 
                SUM(sssss) AS sssss, 
                u.empid AS d_to_empid, 
                u.usercode 
            FROM master.users u 
            JOIN fil_trap_users t_u ON u.usercode = t_u.usercode 
            LEFT JOIN (
                SELECT SUM(s) s, CAST(NULL AS BIGINT) AS ss, CAST(NULL AS BIGINT) AS sss, CAST(NULL AS BIGINT) AS ssss, CAST(NULL AS BIGINT) AS sssss, d_to_empid 
                FROM (
                    SELECT COUNT(uid) s, d_to_empid 
                    FROM fil_trap 
                    WHERE DATE(disp_dt) BETWEEN ? AND ? 
                      AND (remarks = 'TAG -> SCN' OR remarks = 'CAT -> SCN') 
                    GROUP BY d_to_empid 
                    UNION ALL 
                    SELECT COUNT(uid) s, d_to_empid 
                    FROM fil_trap_his 
                    WHERE DATE(disp_dt) BETWEEN ? AND ? 
                      AND (remarks = 'TAG -> SCN' OR remarks = 'CAT -> SCN') 
                    GROUP BY d_to_empid
                ) aa 
                GROUP BY d_to_empid 
        
                UNION ALL 
        
                SELECT CAST(NULL AS BIGINT) s, COUNT(d_to_empid) ss, CAST(NULL AS BIGINT) sss, CAST(NULL AS BIGINT) ssss, CAST(NULL AS BIGINT) sssss, d_to_empid 
                FROM (
                    SELECT DISTINCT zz.*, xx.diary_no d_no, ww.diary_no d_no2 
                    FROM (
                        SELECT diary_no, d_to_empid, r_by_empid 
                        FROM fil_trap 
                        WHERE DATE(disp_dt) BETWEEN ? AND ? 
                          AND (remarks = 'TAG -> SCN' OR remarks = 'CAT -> SCN') 
                          AND r_by_empid != 0 
                        UNION ALL 
                        SELECT diary_no, d_to_empid, r_by_empid 
                        FROM fil_trap_his 
                        WHERE DATE(disp_dt) BETWEEN ? AND ? 
                          AND (remarks = 'TAG -> SCN' OR remarks = 'CAT -> SCN') 
                          AND r_by_empid != 0
                    ) zz 
                    LEFT JOIN fil_trap xx ON xx.diary_no = zz.diary_no 
                        AND (xx.remarks = 'SCN -> IB-Ex') 
                    LEFT JOIN fil_trap_his ww ON ww.diary_no = zz.diary_no 
                        AND (ww.remarks = 'SCN -> IB-Ex') 
                    WHERE (xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL)
                ) aa 
                GROUP BY d_to_empid 
        
                UNION ALL 
        
                SELECT CAST(NULL AS BIGINT) s, CAST(NULL AS BIGINT) ss, COUNT(d_to_empid) sss, CAST(NULL AS BIGINT) ssss, CAST(NULL AS BIGINT) sssss, d_to_empid
                FROM (
                    SELECT DISTINCT zz.*, xx.diary_no d_no, ww.diary_no d_no2 
                    FROM (
                        SELECT diary_no, d_to_empid, r_by_empid 
                        FROM fil_trap 
                        WHERE (remarks = 'TAG -> SCN' OR remarks = 'CAT -> SCN') 
                          AND disp_dt >= '2018-06-30' 
                        UNION ALL 
                        SELECT diary_no, d_to_empid, r_by_empid 
                        FROM fil_trap_his 
                        WHERE (remarks = 'TAG -> SCN' OR remarks = 'CAT -> SCN') 
                          AND disp_dt >= '2018-06-30'
                    ) zz 
                    LEFT JOIN fil_trap xx ON xx.diary_no = zz.diary_no 
                        AND (xx.remarks = 'SCN -> IB-Ex') 
                    LEFT JOIN fil_trap_his ww ON ww.diary_no = zz.diary_no 
                        AND (ww.remarks = 'SCN -> IB-Ex') 
                    WHERE (xx.diary_no IS NULL AND ww.diary_no IS NULL)
                ) aa 
                GROUP BY d_to_empid 
        
                UNION ALL 
        
                SELECT CAST(NULL AS BIGINT) s, CAST(NULL AS BIGINT) ss, CAST(NULL AS BIGINT) sss, COUNT(r_by_empid) ssss, CAST(NULL AS BIGINT) sssss, r_by_empid AS d_to_empid 
                FROM (
                    SELECT DISTINCT zz.*, xx.diary_no d_no, ww.diary_no d_no2 
                    FROM (
                        SELECT diary_no, d_to_empid, r_by_empid 
                        FROM fil_trap 
                        WHERE DATE(comp_dt) BETWEEN ? AND ? 
                          AND (remarks = 'TAG -> SCN' OR remarks = 'CAT -> SCN') 
                        UNION ALL 
                        SELECT diary_no, d_to_empid, r_by_empid 
                        FROM fil_trap_his 
                        WHERE DATE(comp_dt) BETWEEN ? AND ? 
                          AND (remarks = 'TAG -> SCN' OR remarks = 'CAT -> SCN')
                    ) zz 
                    LEFT JOIN fil_trap xx ON xx.diary_no = zz.diary_no 
                        AND (xx.remarks = 'SCN -> IB-Ex') 
                    LEFT JOIN fil_trap_his ww ON ww.diary_no = zz.diary_no 
                        AND (ww.remarks = 'SCN -> IB-Ex') 
                    WHERE (xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL)
                ) aa 
                GROUP BY r_by_empid 
        
                UNION ALL 
        
                SELECT CAST(NULL AS BIGINT) s, CAST(NULL AS BIGINT) ss, CAST(NULL AS BIGINT) sss, CAST(NULL AS BIGINT) ssss, COUNT(r_by_empid) sssss, r_by_empid AS d_to_empid 
                FROM (
                    SELECT diary_no, d_to_empid, r_by_empid 
                    FROM fil_trap 
                    WHERE DATE(rece_dt) BETWEEN ? AND ? 
                      AND (remarks = 'TAG -> SCN' OR remarks = 'CAT -> SCN') 
                      AND r_by_empid != 0 
                    UNION ALL 
                    SELECT diary_no, d_to_empid, r_by_empid 
                    FROM fil_trap_his 
                    WHERE DATE(rece_dt) BETWEEN ? AND ? 
                      AND (remarks = 'TAG -> SCN' OR remarks = 'CAT -> SCN') 
                      AND r_by_empid != 0
                ) zz 
                GROUP BY r_by_empid 
            ) bb ON u.empid = bb.d_to_empid 
            WHERE t_u.usertype = $usercode 
              AND t_u.display = 'Y' 
              AND u.display = 'Y' 
            GROUP BY u.usercode";
        
        $params = [
            // For first UNION part (disp_dt)
            $frm_dt, $to_dt,
            $frm_dt, $to_dt,
            // For second UNION part (disp_dt)
            $frm_dt, $to_dt,
            $frm_dt, $to_dt,
            // For fourth UNION part (comp_dt)
            $frm_dt, $to_dt,
            $frm_dt, $to_dt,
            // For fifth UNION part (rece_dt)
            $frm_dt, $to_dt,
            $frm_dt, $to_dt
        ];
        
        $query = $this->db->query($sql, $params);
        return $query->getResultArray();
    }elseif($usercode=='107'){
       // Build the subquery (casting NULL values to bigint)
            $subQuery = "(
                SELECT SUM(s) as s, NULL::bigint as ss, NULL::bigint as sss, NULL::bigint as ssss, NULL::bigint as sssss, d_to_empid
                FROM (
                    SELECT COUNT(uid) as s, d_to_empid FROM fil_trap 
                    WHERE DATE(disp_dt) BETWEEN '{$frm_dt}' AND '{$to_dt}' AND remarks = 'SCN -> IB-Ex' 
                    GROUP BY d_to_empid
                    UNION ALL
                    SELECT COUNT(uid) as s, d_to_empid FROM fil_trap_his 
                    WHERE DATE(disp_dt) BETWEEN '{$frm_dt}' AND '{$to_dt}' AND remarks = 'SCN -> IB-Ex' 
                    GROUP BY d_to_empid
                ) aa
                GROUP BY d_to_empid

                UNION ALL

                SELECT NULL::bigint as s, COUNT(d_to_empid) as ss, NULL::bigint as sss, NULL::bigint as ssss, NULL::bigint as sssss, d_to_empid
                FROM (
                    SELECT DISTINCT zz.*, xx.diary_no as d_no, ww.diary_no as d_no2
                    FROM (
                        SELECT diary_no, d_to_empid, r_by_empid FROM fil_trap 
                        WHERE DATE(disp_dt) BETWEEN '{$frm_dt}' AND '{$to_dt}' AND remarks = 'SCN -> IB-Ex' AND r_by_empid != 0
                        UNION ALL
                        SELECT diary_no, d_to_empid, r_by_empid FROM fil_trap_his 
                        WHERE DATE(disp_dt) BETWEEN '{$frm_dt}' AND '{$to_dt}' AND remarks = 'SCN -> IB-Ex' AND r_by_empid != 0
                    ) zz
                    LEFT JOIN fil_trap xx ON xx.diary_no = zz.diary_no AND xx.remarks = 'IB-Ex -> Crt'
                    LEFT JOIN fil_trap_his ww ON ww.diary_no = zz.diary_no AND ww.remarks = 'IB-Ex -> Crt'
                    WHERE xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL
                ) aa
                GROUP BY d_to_empid

                UNION ALL

                SELECT NULL::bigint as s, NULL::bigint as ss, COUNT(d_to_empid) as sss, NULL::bigint as ssss, NULL::bigint as sssss, d_to_empid
                FROM (
                    SELECT DISTINCT zz.*, xx.diary_no as d_no, ww.diary_no as d_no2
                    FROM (
                        SELECT diary_no, d_to_empid, r_by_empid FROM fil_trap 
                        WHERE remarks = 'SCN -> IB-Ex' AND disp_dt >= '2018-06-30'
                        UNION ALL
                        SELECT diary_no, d_to_empid, r_by_empid FROM fil_trap_his 
                        WHERE remarks = 'SCN -> IB-Ex' AND disp_dt >= '2018-06-30'
                    ) zz
                    LEFT JOIN fil_trap xx ON xx.diary_no = zz.diary_no AND xx.remarks = 'IB-Ex -> Crt'
                    LEFT JOIN fil_trap_his ww ON ww.diary_no = zz.diary_no AND ww.remarks = 'IB-Ex -> Crt'
                    WHERE xx.diary_no IS NULL AND ww.diary_no IS NULL
                ) aa
                GROUP BY d_to_empid

                UNION ALL

                SELECT NULL::bigint as s, NULL::bigint as ss, NULL::bigint as sss, COUNT(r_by_empid) as ssss, NULL::bigint as sssss, r_by_empid as d_to_empid
                FROM (
                    SELECT DISTINCT zz.*, xx.diary_no as d_no, ww.diary_no as d_no2
                    FROM (
                        SELECT diary_no, d_to_empid, r_by_empid FROM fil_trap 
                        WHERE DATE(comp_dt) BETWEEN '{$frm_dt}' AND '{$to_dt}' AND remarks = 'SCN -> IB-Ex'
                        UNION ALL
                        SELECT diary_no, d_to_empid, r_by_empid FROM fil_trap_his 
                        WHERE DATE(comp_dt) BETWEEN '{$frm_dt}' AND '{$to_dt}' AND remarks = 'SCN -> IB-Ex'
                    ) zz
                    LEFT JOIN fil_trap xx ON xx.diary_no = zz.diary_no AND xx.remarks = 'IB-Ex -> Crt'
                    LEFT JOIN fil_trap_his ww ON ww.diary_no = zz.diary_no AND ww.remarks = 'IB-Ex -> Crt'
                    WHERE xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL
                ) aa
                GROUP BY r_by_empid

                UNION ALL

                SELECT NULL::bigint as s, NULL::bigint as ss, NULL::bigint as sss, NULL::bigint as ssss, COUNT(r_by_empid) as sssss, r_by_empid as d_to_empid
                FROM (
                    SELECT diary_no, d_to_empid, r_by_empid FROM fil_trap 
                    WHERE DATE(rece_dt) BETWEEN '{$frm_dt}' AND '{$to_dt}' AND remarks = 'SCN -> IB-Ex' AND r_by_empid != 0
                    UNION ALL
                    SELECT diary_no, d_to_empid, r_by_empid FROM fil_trap_his 
                    WHERE DATE(rece_dt) BETWEEN '{$frm_dt}' AND '{$to_dt}' AND remarks = 'SCN -> IB-Ex' AND r_by_empid != 0
                ) zz
                GROUP BY r_by_empid
            ) bb";

            // Build the main query using Query Builder.
            $builder = $this->db->table('master.users u');
            $builder->select('
                SUM(s) as s, 
                SUM(ss) as ss, 
                SUM(sss) as sss, 
                SUM(ssss) as ssss, 
                SUM(sssss) as sssss, 
                u.empid as d_to_empid, 
                u.usercode
            ');
            $builder->join('fil_trap_users t_u', 'u.usercode = t_u.usercode');
            $builder->join("{$subQuery}", 'u.empid = bb.d_to_empid', 'left');
            $builder->where('t_u.usertype', $usercode);
            $builder->where('t_u.display', 'Y');
            $builder->where('u.display', 'Y');
            $builder->groupBy('u.usercode');

            // Execute the query
            $query  = $builder->get();
            $result = $query->getResultArray();
            return $result;
    }elseif($usercode=='108'){
             
       // first query data here
            $subA1 = $this->db->table('fil_trap')
            ->select('COUNT(uid) as s, r_by_empid')
            ->where('DATE(disp_dt) >=', $frm_dt)
            ->where('DATE(disp_dt) <=', $to_dt)
            ->where('remarks', 'SCR -> FDR')
            ->groupBy('r_by_empid')
            ->getCompiledSelect();

            $subA2 = $this->db->table('fil_trap_his')
            ->select('COUNT(uid) as s, r_by_empid')
            ->where('DATE(disp_dt) >=', $frm_dt)
            ->where('DATE(disp_dt) <=', $to_dt)
            ->where('remarks', 'SCR -> FDR')
            ->groupBy('r_by_empid')
            ->getCompiledSelect();

            $subAUnion = $subA1 . " UNION ALL " . $subA2;
            $subA = "SELECT SUM(s) as s, 
                    CAST(NULL AS BIGINT) as ss, 
                    CAST(NULL AS BIGINT) as sss, 
                    CAST(NULL AS BIGINT) as ssss, 
                    CAST(NULL AS BIGINT) as sssss, 
                    r_by_empid 
                FROM ($subAUnion) aa 
                GROUP BY r_by_empid";

            // Subquery B: Count distinct diary_no records where corresponding FDR -> AOR exists
            $subB1 = $this->db->table('fil_trap')
            ->select('diary_no, d_to_empid, r_by_empid, disp_dt')
            ->where('DATE(disp_dt) >=', $frm_dt)
            ->where('DATE(disp_dt) <=', $to_dt)
            ->where('remarks', 'SCR -> FDR')
            ->getCompiledSelect();

            $subB2 = $this->db->table('fil_trap_his')
            ->select('diary_no, d_to_empid, r_by_empid, disp_dt')
            ->where('DATE(disp_dt) >=', $frm_dt)
            ->where('DATE(disp_dt) <=', $to_dt)
            ->where('remarks', 'SCR -> FDR')
            ->getCompiledSelect();

            $subBUnion = $subB1 . " UNION ALL " . $subB2;
            $subB = "SELECT NULL as s, COUNT(r_by_empid) as ss, NULL as sss, NULL as ssss, NULL as sssss, r_by_empid 
                FROM (
                    SELECT DISTINCT zz.*, 
                            xx.diary_no as d_no, 
                            ww.diary_no as d_no2, 
                            mn.fil_no
                    FROM ($subBUnion) zz
                    LEFT JOIN fil_trap xx ON xx.diary_no = zz.diary_no AND xx.remarks = 'FDR -> AOR'
                    LEFT JOIN fil_trap_his ww ON ww.diary_no = zz.diary_no AND ww.remarks = 'FDR -> AOR'
                    LEFT JOIN main mn ON mn.diary_no = zz.diary_no
                    WHERE (xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL OR mn.fil_no IS NOT NULL)
                ) aa 
                GROUP BY r_by_empid";

            // Subquery C: Count records for SCR -> FDR without any FDR -> AOR or main.fil_no value (starting from 2018-06-30)
            $subC1 = $this->db->table('fil_trap')
            ->select('diary_no, d_to_empid, r_by_empid')
            ->where('remarks', 'SCR -> FDR')
            ->where('disp_dt >=', '2018-06-30')
            ->getCompiledSelect();

            $subC2 = $this->db->table('fil_trap_his')
            ->select('diary_no, d_to_empid, r_by_empid')
            ->where('remarks', 'SCR -> FDR')
            ->where('disp_dt >=', '2018-06-30')
            ->getCompiledSelect();

            $subCUnion = $subC1 . " UNION ALL " . $subC2;
            $subC = "SELECT NULL as s, NULL as ss, COUNT(r_by_empid) as sss, NULL as ssss, NULL as sssss, r_by_empid 
                FROM (
                    SELECT DISTINCT zz.*, 
                            xx.diary_no as d_no, 
                            ww.diary_no as d_no2, 
                            mn.fil_no
                    FROM ($subCUnion) zz
                    LEFT JOIN fil_trap xx ON xx.diary_no = zz.diary_no AND xx.remarks = 'FDR -> AOR'
                    LEFT JOIN fil_trap_his ww ON ww.diary_no = zz.diary_no AND ww.remarks = 'FDR -> AOR'
                    LEFT JOIN main mn ON mn.diary_no = zz.diary_no
                    WHERE (xx.diary_no IS NULL AND ww.diary_no IS NULL AND (mn.fil_no IS NULL OR mn.fil_no = ''))
                ) aa 
                GROUP BY r_by_empid";

            // Subquery D: Count records where comp_dt is within the range and FDR -> AOR exists
            $subD1 = $this->db->table('fil_trap')
            ->select('diary_no, d_to_empid, r_by_empid, disp_dt')
            ->where('DATE(comp_dt) >=', $frm_dt)
            ->where('DATE(comp_dt) <=', $to_dt)
            ->where('remarks', 'SCR -> FDR')
            ->getCompiledSelect();

            $subD2 = $this->db->table('fil_trap_his')
            ->select('diary_no, d_to_empid, r_by_empid, disp_dt')
            ->where('DATE(comp_dt) >=', $frm_dt)
            ->where('DATE(comp_dt) <=', $to_dt)
            ->where('remarks', 'SCR -> FDR')
            ->getCompiledSelect();

            $subDUnion = $subD1 . " UNION ALL " . $subD2;
            $subD = "SELECT NULL as s, NULL as ss, NULL as sss, COUNT(r_by_empid) as ssss, NULL as sssss, r_by_empid 
                FROM (
                    SELECT DISTINCT zz.*, 
                            xx.diary_no as d_no, 
                            ww.diary_no as d_no2, 
                            mn.fil_no
                    FROM ($subDUnion) zz
                    LEFT JOIN fil_trap xx ON xx.diary_no = zz.diary_no AND xx.remarks = 'FDR -> AOR'
                    LEFT JOIN fil_trap_his ww ON ww.diary_no = zz.diary_no AND ww.remarks = 'FDR -> AOR'
                    LEFT JOIN main mn ON mn.diary_no = zz.diary_no
                    WHERE (xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL OR mn.fil_no IS NOT NULL)
                ) aa 
                GROUP BY r_by_empid";

            // Subquery E: Count records where rece_dt is within the range
            $subE1 = $this->db->table('fil_trap')
            ->select('diary_no, d_to_empid, r_by_empid, disp_dt')
            ->where('DATE(rece_dt) >=', $frm_dt)
            ->where('DATE(rece_dt) <=', $to_dt)
            ->where('remarks', 'SCR -> FDR')
            ->getCompiledSelect();

            $subE2 = $this->db->table('fil_trap_his')
            ->select('diary_no, d_to_empid, r_by_empid, disp_dt')
            ->where('DATE(rece_dt) >=', $frm_dt)
            ->where('DATE(rece_dt) <=', $to_dt)
            ->where('remarks', 'SCR -> FDR')
            ->getCompiledSelect();

            $subEUnion = $subE1 . " UNION ALL " . $subE2;
            $subE = "SELECT NULL as s, NULL as ss, NULL as sss, NULL as ssss, COUNT(r_by_empid) as sssss, r_by_empid 
                FROM ($subEUnion) zz 
                GROUP BY r_by_empid";

            // Combine all five parts for branch 1
            $subBranch1 = "($subA) UNION ALL ($subB) UNION ALL ($subC) UNION ALL ($subD) UNION ALL ($subE)";

            // --- Build the two branch queries ---

            // Branch 1 (SCR -> FDR)
            $builder1 = $this->db->table('master.users u')
            ->select("SUM(s) as s, SUM(ss) as ss, SUM(sss) as sss, 
                    NULL as r_s, NULL as r_ss, NULL as r_sss, 
                    NULL as r_ssss, NULL as r_sssss, 
                    SUM(ssss) as ssss, SUM(sssss) as sssss, 
                    u.empid as r_by_empid, u.usercode", false)
            ->join('fil_trap_users t_u', 'u.usercode = t_u.usercode')
            ->join("($subBranch1) bb", 'u.empid = bb.r_by_empid', 'left', false)
            ->where('t_u.usertype', $usercode)
            ->where('t_u.display', 'Y')
            ->where('u.display', 'Y')
            ->groupBy('u.usercode');

            $branch1Sql = $builder1->getCompiledSelect();

            // Branch 2 (AOR -> FDR) – using fixed zero/NULL values for the missing columns.
            // Replace these dummy values with your actual query logic when available.
            $builder2 = $this->db->table('master.users u')
            ->select("NULL as s, NULL as ss, NULL as sss, 
                    0 as r_s, 0 as r_ss, 0 as r_sss, 
                    0 as r_ssss, 0 as r_sssss, 
                    NULL as ssss, NULL as sssss, 
                    u.empid as r_by_empid, u.usercode", false)
            ->join('fil_trap_users t_u', 'u.usercode = t_u.usercode')
            // ->join(... add your AOR -> FDR subqueries and joins here if needed ...)
            ->where('t_u.usertype', $usercode)
            ->where('t_u.display', 'Y')
            ->where('u.display', 'Y')
            ->groupBy('u.usercode');

            $branch2Sql = $builder2->getCompiledSelect();

            // --- Combine both branches and produce the final SQL ---
            $finalUnion = $branch1Sql . " UNION ALL " . $branch2Sql;

                $finalSql = "SELECT 
                SUM(s) as s, 
                SUM(ss) as ss, 
                SUM(sss) as sss, 
                SUM(r_s) as r_s, 
                SUM(r_ss) as r_ss, 
                SUM(r_sss) as r_sss, 
                tt.r_by_empid as d_to_empid, 
                SUM(ssss) as ssss, 
                SUM(sssss) as sssss, 
                SUM(r_ssss) as r_ssss, 
                SUM(r_sssss) as r_sssss
            FROM (
                $finalUnion
            ) tt
            GROUP BY tt.usercode, tt.r_by_empid
            ORDER BY s DESC";
            $query = $this->db->query($finalSql);
            $result1 = $query->getResultArray();
           
            // second query data here 
            $empId  = '9798';

            $subA1 = $this->db->table('fil_trap')
                ->select("COUNT(uid) as s, d_to_empid")
                ->where("DATE(disp_dt) >=", $frm_dt)
                ->where("DATE(disp_dt) <=", $to_dt)
                ->where("remarks", "SCR -> FDR")
                ->where("r_by_empid", 0)
                ->where("d_to_empid", $empId)
                ->groupBy("d_to_empid");

            $subA2 = $this->db->table('fil_trap_his')
                ->select("COUNT(uid) as s, d_to_empid")
                ->where("DATE(disp_dt) >=", $frm_dt)
                ->where("DATE(disp_dt) <=", $to_dt)
                ->where("remarks", "SCR -> FDR")
                ->where("r_by_empid", 0)
                ->where("d_to_empid", $empId)
                ->groupBy("d_to_empid");

            $subAUnion = $subA1->getCompiledSelect() . " UNION ALL " . $subA2->getCompiledSelect();
            $subA = "SELECT SUM(s) as s, NULL::bigint as ss, NULL::bigint as sss, d_to_empid 
                    FROM ($subAUnion) aa 
                    GROUP BY d_to_empid";

            // --- Subquery Part B ---
            $subB1 = $this->db->table('fil_trap')
                ->select("diary_no, d_to_empid, r_by_empid, disp_dt")
                ->where("DATE(disp_dt) >=", $frm_dt)
                ->where("DATE(disp_dt) <=", $to_dt)
                ->where("remarks", "SCR -> FDR")
                ->where("r_by_empid", 0)
                ->where("d_to_empid", $empId);

            $subB2 = $this->db->table('fil_trap_his')
                ->select("diary_no, d_to_empid, r_by_empid, disp_dt")
                ->where("DATE(disp_dt) >=", $frm_dt)
                ->where("DATE(disp_dt) <=", $to_dt)
                ->where("remarks", "SCR -> FDR")
                ->where("r_by_empid", 0)
                ->where("d_to_empid", $empId);

            $subBUnion = $subB1->getCompiledSelect() . " UNION ALL " . $subB2->getCompiledSelect();
            $subB = "SELECT NULL::bigint as s, COUNT(d_to_empid) as ss, NULL::bigint as sss, d_to_empid 
                    FROM (
                        SELECT DISTINCT zz.*, 
                            xx.diary_no as d_no, 
                            ww.diary_no as d_no2, 
                            mn.fil_no
                        FROM ($subBUnion) zz
                        LEFT JOIN fil_trap xx ON xx.diary_no = zz.diary_no AND xx.remarks = 'FDR -> AOR'
                        LEFT JOIN fil_trap_his ww ON ww.diary_no = zz.diary_no AND ww.remarks = 'FDR -> AOR'
                        LEFT JOIN main mn ON mn.diary_no = zz.diary_no
                        WHERE (xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL OR mn.fil_no IS NOT NULL)
                    ) aa 
                    GROUP BY d_to_empid";

            // --- Subquery Part C ---
            $subC1 = $this->db->table('fil_trap')
                ->select("diary_no, d_to_empid, r_by_empid")
                ->where("remarks", "SCR -> FDR")
                ->where("disp_dt >=", "2018-06-30");

            $subC2 = $this->db->table('fil_trap_his')
                ->select("diary_no, d_to_empid, r_by_empid")
                ->where("remarks", "SCR -> FDR")
                ->where("disp_dt >=", "2018-06-30");

            $subCUnion = $subC1->getCompiledSelect() . " UNION ALL " . $subC2->getCompiledSelect();
            $subC = "SELECT NULL::bigint as s, NULL::bigint as ss, COUNT(d_to_empid) as sss, d_to_empid
                    FROM (
                        SELECT DISTINCT zz.*, 
                            xx.diary_no as d_no, 
                            ww.diary_no as d_no2, 
                            mn.fil_no
                        FROM ($subCUnion) zz
                        LEFT JOIN fil_trap xx ON xx.diary_no = zz.diary_no AND xx.remarks = 'FDR -> AOR'
                        LEFT JOIN fil_trap_his ww ON ww.diary_no = zz.diary_no AND ww.remarks = 'FDR -> AOR'
                        LEFT JOIN main mn ON mn.diary_no = zz.diary_no
                        WHERE (xx.diary_no IS NULL AND ww.diary_no IS NULL AND (mn.fil_no IS NULL OR mn.fil_no=''))
                    ) aa 
                    GROUP BY d_to_empid";

            // --- Combine Branch 1 join subqueries ---
            $subBranch1Join = "($subA) UNION ALL ($subB) UNION ALL ($subC)";

            // --- Build Branch 1 Query ---
            $builder1 = $this->db->table('master.users u')
                ->select("SUM(s) as s, SUM(ss) as ss, SUM(sss) as sss, 
                        CAST(NULL AS BIGINT) as r_s, CAST(NULL AS BIGINT) as r_ss, CAST(NULL AS BIGINT) as r_sss, 
                        u.empid as d_to_empid, u.usercode", false)
                ->join("($subBranch1Join) bb", "u.empid = bb.d_to_empid", "inner")
                ->where("u.empid", $empId)
                ->groupBy("u.usercode");

            $branch1Sql = $builder1->getCompiledSelect();

            // --- Build Branch 2 Query --- (Using dummy values; replace with your actual logic)
            $builder2 = $this->db->table('master.users u')
                ->select("CAST(NULL AS BIGINT) as s, CAST(NULL AS BIGINT) as ss, CAST(NULL AS BIGINT) as sss, 
                        0 as r_s, 0 as r_ss, 0 as r_sss, 
                        u.empid as d_to_empid, u.usercode", false)
                ->where("u.empid", $empId)
                ->groupBy("u.usercode");

            $branch2Sql = $builder2->getCompiledSelect();

            $finalUnion = $branch1Sql . " UNION ALL " . $branch2Sql;

            $finalSql = "SELECT 
                SUM(s) as s, 
                SUM(ss) as ss, 
                SUM(sss) as sss, 
                SUM(r_s) as r_s, 
                SUM(r_ss) as r_ss, 
                SUM(r_sss) as r_sss, 
                d_to_empid
            FROM ($finalUnion) tt
            GROUP BY tt.usercode, tt.d_to_empid
            ORDER BY s DESC";
            
            $query   = $this->db->query($finalSql);
            $result2 = $query->getResultArray();
            return array('result1' =>  $result1, 'result2'=>  $result2);
     }elseif($usercode=='101'){
        $builder = $this->db->table("master.users u");
        $builder->select('u.empid as d_to_empid, COUNT(diary_no) as ss');
        $builder->join('fil_trap_users t_u', 'u.usercode = t_u.usercode');
        $builder->join(
            'main c',
            "c.diary_user_id = u.usercode AND DATE(c.diary_no_rec_date) BETWEEN '{$frm_dt}' AND '{$to_dt}'",
            'left'
        );
        $builder->where('t_u.usertype', $usercode);
        $builder->where('t_u.display', 'Y');
        $builder->where('u.display', 'Y');
        $builder->groupBy('u.empid');
        $query = $builder->get();
        return $query->getResultArray();
    }elseif($usercode=='109'){
        $builder = $this->db->table("master.users u");
        $builder->select('u.empid as d_to_empid, COUNT(c.diary_no) as ss');
        $builder->join('fil_trap_users t_u', 'u.usercode = t_u.usercode');
        
        $builder->join(
            'ld_move c', 
            "c.disp_by = u.usercode AND DATE(c.disp_dt) BETWEEN '{$frm_dt}' AND '{$to_dt}'", 
            'left'
        );
        
        $builder->where('t_u.usertype', $usercode);
        $builder->where('t_u.display', 'Y');
        $builder->where('u.display', 'Y');
        $builder->groupBy('u.empid');
        $query = $builder->get();
        return $query->getResultArray();
    }
}

function get_case_alloted_popup_details($frm_dt, $to_dt, $ddl_users, $case, $row_id, $emp_id, $usercode, $r_section, $r_usertype){
	$userQuery = $this->db->table('master.users')
              ->select('usercode')
              ->where('empid', $emp_id)
              ->get();
  if ($userQuery->getNumRows() > 0) {
      $r_user_id = $userQuery->getRow()->usercode;
  } else {
     $r_user_id = null;
  }   
   
  if($ddl_users=='101'){
         if($case=='spallot'){
			  $builder = $this->db->table('main c')
					->select('c.diary_no, c.diary_user_id as d_to_empid, c.diary_no_rec_date as disp_dt')
					->join('master.users u', 'u.usercode = c.diary_user_id', 'inner')
					->where('DATE(c.diary_no_rec_date) >=',  $frm_dt)
					->where('DATE(c.diary_no_rec_date) <=', $to_dt);
				if ($row_id != '0') {
					 $builder->where('u.empid', $emp_id);
				} 

				$query = $builder->get();
				return $query->getResultArray();
		}elseif($case=='spcomp'){
				  $builder = $this->db->table('main c')
							->select('c.diary_no, c.diary_user_id as d_to_empid, c.diary_no_rec_date as disp_dt')
							->join('master.users u', 'u.usercode = c.diary_user_id', 'inner')
							->where('DATE(c.diary_no_rec_date) >=',  $frm_dt)
							->where('DATE(c.diary_no_rec_date) <=', $to_dt);
						if ($row_id != '0') {
							 $builder->where('u.empid', $emp_id);
						} 

						$query = $builder->get();
						return $query->getResultArray();

              }elseif($case=='spnotcomp'){
									
					$subQuery1 = $this->db->table('fil_trap')
						->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
						->where("DATE(disp_dt) >=", $frm_dt)
						->where("DATE(disp_dt) <=", $to_dt);
					if ($row_id != '0') {
						$subQuery1->where('r_by_empid', $emp_id);
					}

					$subQuery1 = $subQuery1->getCompiledSelect();
					

					$subQuery2 = $this->db->table('fil_trap_his')
						->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
						->where("DATE(disp_dt) >=", $frm_dt)
						->where("DATE(disp_dt) <=", $to_dt);
					if ($row_id != '0') {
						$subQuery2->where('r_by_empid', $emp_id);
					}

					$subQuery2 = $subQuery2->getCompiledSelect();

					
					$subQuery = "($subQuery1 UNION ALL $subQuery2) as zz";

					
					$builder = $this->db->table($subQuery)
						->distinct()
						->select('zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2')
						->join('fil_trap xx', "xx.diary_no = zz.diary_no AND (xx.remarks = '')", 'left')
						->join('fil_trap_his ww', "ww.diary_no = zz.diary_no AND (ww.remarks = '')", 'left')
						->where('xx.diary_no IS NULL')
						->where('ww.diary_no IS NULL')
						->orderBy('zz.disp_dt, zz.d_by_empid')
						->get();

					return $builder->getResultArray();
          
            }elseif($case=='sptotpenr'){
                
				$subQuery1 = $this->db->table('fil_trap')
						->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
						->where('r_by_empid !=', 0)
						->where("rece_dt >=", $frm_dt)
						->where("rece_dt <=", $to_dt);

				if ($row_id != '0') {
					$subQuery1->where('r_by_empid', $emp_id);
				}

				$subQuery1 = $subQuery1->getCompiledSelect();


				$subQuery2 = $this->db->table('fil_trap_his')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
					->where('r_by_empid !=', 0)
					->where("rece_dt >=", $frm_dt)
					->where("rece_dt <=", $to_dt);
				if ($row_id != '0') {
						$subQuery2->where('r_by_empid', $emp_id);
					}

				$subQuery2 = $subQuery2->getCompiledSelect();

				$unionQuery = "($subQuery1 UNION ALL $subQuery2) as zz";

				$builder = $this->db->table($unionQuery)
					->distinct()
					->select('zz.*')
					->orderBy('zz.disp_dt, zz.d_by_empid')
					->get();

				return $builder->getResultArray();


          }elseif($case=='sptwd'){
			
			$subQuery1 = $this->db->table('fil_trap')
				->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
				->where('DATE(comp_dt) >=', $frm_dt)
                ->where('DATE(comp_dt) <=', $to_dt)
				->where('r_by_empid !=', 0);
			if($row_id!='0'){
				  $subQuery1->where('r_by_empid', $emp_id);
			}
					
            $subQuery1=  $subQuery1->getCompiledSelect();   	
			

			$subQuery2 = $this->db->table('fil_trap_his')
				->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
				->where('DATE(comp_dt) >=', $frm_dt)
                ->where('DATE(comp_dt) <=', $to_dt)
				->where('r_by_empid !=', 0);
			if($row_id!='0'){
				    $subQuery2->where('r_by_empid', $emp_id);
			}
			
			$subQuery2=  $subQuery2->getCompiledSelect(); 
			
			$builder = $this->db->table("($subQuery1 UNION ALL $subQuery2) as zz")
					->select('zz.*, xx.diary_no as d_no, ww.diary_no as d_no2')
					->select('COALESCE(xx.remarks, ww.remarks) as remarks')
					->select('COALESCE(xx.d_to_empid, ww.d_to_empid) as d_d_to_empid')
					->select('COALESCE(xx.disp_dt, ww.disp_dt) as d_disp_dt')
					->join('fil_trap as xx', 'xx.diary_no = zz.diary_no AND (xx.remarks = \'\' OR xx.remarks IS NULL)', 'left')
					->join('fil_trap_his as ww', 'ww.diary_no = zz.diary_no AND (ww.remarks = \'\' OR ww.remarks IS NULL)', 'left')
					->where('(xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL)')
					->orderBy('zz.disp_dt, zz.d_by_empid');

				$query = $builder->get();
				return $query->getResultArray();
		  }
	 }elseif($ddl_users=='102'){
			if($case=='spallot'){
					$subQuery1 = $this->db->table('fil_trap')
						->select('diary_no, d_by_empid, d_to_empid, disp_dt, r_by_empid, rece_dt')
						->where('DATE(disp_dt) >=', $frm_dt)
						->where('DATE(disp_dt) <=', $to_dt);
						
					if($row_id!='0'){
						$subQuery1->where('d_to_empid', $emp_id);
					}
					
					$subQuery1 = $subQuery1->where('remarks', 'FIL -> DE')
						->getCompiledSelect();

					$subQuery2 = $this->db->table('fil_trap_his')
						->select('diary_no, d_by_empid, d_to_empid, disp_dt, r_by_empid, rece_dt')
						->where('DATE(disp_dt) >=', $frm_dt)
						->where('DATE(disp_dt) <=', $to_dt);
					
					if($row_id!='0'){
						$subQuery2->where('d_to_empid', $emp_id);
					}
						
					$subQuery2 = $subQuery2->where('remarks', 'FIL -> DE')
								->getCompiledSelect();

					$builder = $this->db->query("($subQuery1 UNION ALL $subQuery2) ORDER BY disp_dt, d_by_empid");
                    return  $builder->getResultArray();
				}elseif($case=='spcomp'){
					
						$subQuery1 = $this->db->table('fil_trap')
									->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
									->where('DATE(disp_dt) >=', $frm_dt)
									->where('DATE(disp_dt) <=', $to_dt);
							
							if($row_id!='0'){
								$subQuery1->where('d_to_empid', $emp_id);
							}
							
						$subQuery1= $subQuery1->where('d_to_empid', $emp_id)
									->where('remarks', 'FIL -> DE')
									->where('r_by_empid !=', 0)
									->getCompiledSelect();

						$subQuery2 = $this->db->table('fil_trap_his')
							->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
							->where('DATE(disp_dt) >=', $frm_dt)
							->where('DATE(disp_dt) <=', $to_dt);
							
							if($row_id!='0'){
								$subQuery2->where('d_to_empid', $emp_id);
							}
							
						$subQuery2 = $subQuery2->where('remarks', 'FIL -> DE')
										->where('r_by_empid !=', 0)
										->getCompiledSelect();

					$subQuery = "($subQuery1 UNION ALL $subQuery2) as zz";

					$builder = $this->db->table($subQuery)
								->distinct()
								->select('zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2')
								->join('fil_trap AS xx', "xx.diary_no = zz.diary_no AND xx.remarks = 'DE -> SCR'", 'left', false)
								->join('fil_trap_his AS ww', "ww.diary_no = zz.diary_no AND ww.remarks = 'DE -> SCR'", 'left', false)
								->where('(xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL)', null, false)
								->orderBy('zz.disp_dt, zz.d_by_empid');


					$query = $builder->get();
					return $query->getResultArray();
					
				}elseif($case=='spnotcomp'){
					
					$subQuery1 = $this->db->table('fil_trap')
						->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
						->where('DATE(disp_dt) >=', $frm_dt)
						->where('DATE(disp_dt) <=', $to_dt);
					if($row_id!='0'){
							$subQuery1->where('d_to_empid', $emp_id);
					}
					
					$subQuery1 = $subQuery1->where('remarks', 'FIL -> DE')
									->getCompiledSelect();

					$subQuery2 = $this->db->table('fil_trap_his')
						->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
						->where('DATE(disp_dt) >=', $frm_dt)
						->where('DATE(disp_dt) <=', $to_dt);
					if($row_id!='0'){
						$subQuery2->where('d_to_empid', $emp_id);
					}
					
					$subQuery2 = $subQuery2->where('d_to_empid', $emp_id)
									->where('remarks', 'FIL -> DE')
									->getCompiledSelect();

					$subQuery = "($subQuery1 UNION ALL $subQuery2) AS zz";

					$builder = $this->db->table($subQuery)
						->distinct()
						->select('zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2')
						->join('fil_trap AS xx', "xx.diary_no = zz.diary_no AND xx.remarks = 'DE -> SCR'", 'left', false)
						->join('fil_trap_his AS ww', "ww.diary_no = zz.diary_no AND ww.remarks = 'DE -> SCR'", 'left', false)
						->where('xx.diary_no IS NULL')
						->where('ww.diary_no IS NULL')
						->orderBy('zz.disp_dt, zz.d_by_empid');

						
					$query = $builder->get();
					return $query->getResultArray();
					
				}elseif($case=='sptotpen'){
										
					$subQuery1 = $this->db->table('fil_trap')
						->select('diary_no, d_to_empid, r_by_empid, d_by_empid, disp_dt')
						->where('remarks', 'FIL -> DE');
					if($row_id!='0'){
							$subQuery1->where('d_to_empid', $emp_id);
					}	
						
					$subQuery1 = $subQuery1->where('disp_dt >=', '2018-06-30')
						->getCompiledSelect();

					$subQuery2 = $this->db->table('fil_trap_his')
						->select('diary_no, d_to_empid, r_by_empid, d_by_empid, disp_dt')
						->where('remarks', 'FIL -> DE');
						if($row_id!='0'){
							$subQuery2->where('d_to_empid', $emp_id);
					    }
						
					$subQuery2 = $subQuery2->where('disp_dt >=', '2018-06-30')
									->getCompiledSelect();

					$subQuery = "($subQuery1 UNION ALL $subQuery2) AS zz";

					$builder = $this->db->table($subQuery)
						->distinct()
						->select('zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2')
						->join('fil_trap AS xx', "xx.diary_no = zz.diary_no AND xx.remarks = 'DE -> SCR'", 'left', false)
						->join('fil_trap_his AS ww', "ww.diary_no = zz.diary_no AND ww.remarks = 'DE -> SCR'", 'left', false)
						->where('xx.diary_no IS NULL')
						->where('ww.diary_no IS NULL')
						->orderBy('zz.disp_dt, zz.d_by_empid');

					$query = $builder->get();
					return $query->getResultArray();
					
					
				}elseif($case=='sptotpenr'){
						$subQuery1 = $this->db->table('fil_trap')
							->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
							->where('DATE(rece_dt) >=', $frm_dt)
							->where('DATE(rece_dt) <=', $to_dt);
						if($row_id!='0'){
							    $subQuery1->where('d_to_empid', $emp_id);
					    }
						$subQuery1 = $subQuery1->where('remarks', 'FIL -> DE')
							->where('r_by_empid !=', 0)
							->getCompiledSelect();

						 $subQuery2 = $this->db->table('fil_trap_his')
							->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
							->where('DATE(rece_dt) >=', $frm_dt)
							->where('DATE(rece_dt) <=', $to_dt);
						if($row_id!='0'){
							  $subQuery2->where('d_to_empid', $emp_id);
					     }
						 
						 $subQuery2 = $subQuery2->where('remarks', 'FIL -> DE')
										->where('r_by_empid !=', 0)
										->getCompiledSelect();

                          $subQuery = "($subQuery1 UNION ALL $subQuery2) AS zz";

						$builder = $this->db->table($subQuery)
							->distinct()
							->select('zz.*')
							->orderBy('zz.disp_dt, zz.d_by_empid');


						$query = $builder->get();
						return $query->getResultArray();
											
				}elseif($case=='sptwd'){
					$subQuery1 = $this->db->table('fil_trap')
						->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
						->where('DATE(comp_dt) >=', $frm_dt)
						->where('DATE(comp_dt) <=', $to_dt);
						if($row_id!='0'){
							  $subQuery1->where('r_by_empid', $emp_id);
					     }
					$subQuery1 = $subQuery1->where('remarks', 'FIL -> DE')
						->where('r_by_empid !=', 0)
						->getCompiledSelect();

					$subQuery2 = $this->db->table('fil_trap_his')
						->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
						->where('DATE(comp_dt) >=', $frm_dt)
						->where('DATE(comp_dt) <=', $to_dt);
					if($row_id!='0'){
						  $subQuery2->where('r_by_empid', $emp_id);
					 }
					
					$subQuery2= $subQuery2->where('remarks', 'FIL -> DE')
						->where('r_by_empid !=', 0)
						->getCompiledSelect();

					$subQuery = "($subQuery1 UNION ALL $subQuery2) AS zz";
                    $builder = $this->db->table($subQuery)
								->distinct()
								->select('zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2, 
										  COALESCE(xx.remarks, ww.remarks) AS remarks, 
										  COALESCE(xx.d_to_empid, ww.d_to_empid) AS d_d_to_empid, 
										  COALESCE(xx.disp_dt, ww.disp_dt) AS d_disp_dt')
								->join('fil_trap AS xx', "xx.diary_no = zz.diary_no AND xx.remarks = 'DE -> SCR'", 'left', false)
								->join('fil_trap_his AS ww', "ww.diary_no = zz.diary_no AND ww.remarks = 'DE -> SCR'", 'left', false)
								->where('(xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL)', null, false)
								->orderBy('zz.disp_dt, zz.d_by_empid');

					$query = $builder->get();
					return $query->getResultArray();
				}
	}elseif($ddl_users=='103'){
			if($case=='spallot'){
					$builder1 = $this->db->table('fil_trap')
						->select('diary_no, d_by_empid, d_to_empid, disp_dt, r_by_empid, rece_dt')
						->where('DATE(disp_dt) >=', $frm_dt)
						->where('DATE(disp_dt) <=', $to_dt)
						->where('remarks', 'DE -> SCR');
					
					if($row_id!='0'){
						  $builder1->where('d_to_empid', $emp_id);
					}	


					$builder2 = $this->db->table('fil_trap_his')
						->select('diary_no, d_by_empid, d_to_empid, disp_dt, r_by_empid, rece_dt')
						->where('DATE(disp_dt) >=', $frm_dt)
						->where('DATE(disp_dt) <=', $to_dt)
						->where('remarks', 'DE -> SCR');
						
					if($row_id!='0'){
						  $builder2->where('d_to_empid', $emp_id);
					}	

					$subQuery = $builder1->unionAll($builder2);

					$mainQuery = $this->db->table("({$subQuery->getCompiledSelect()}) as combined_query")
						->orderBy('disp_dt', 'ASC')
						->orderBy('d_by_empid', 'ASC')
						->get();

					return  $mainQuery->getResultArray();
					
			}elseif($case=='spcomp'){
								
					$query1 = $this->db->table('fil_trap')
						->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
						->where('DATE(disp_dt) >=', $frm_dt)
						->where('DATE(disp_dt) <=', $to_dt)
						->where('remarks', 'DE -> SCR');


					if ($row_id != '0') {
						$query1->where('d_to_empid', $emp_id);
					}


					$query2 = $this->db->table('fil_trap_his')
						->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
						->where('DATE(disp_dt) >=', $frm_dt)
						->where('DATE(disp_dt) <=', $to_dt)
						->where('remarks', 'DE -> SCR');


					if ($row_id != '0') {
						$query2->where('d_to_empid', $emp_id);
					}


					$unionQuery = $query1->getCompiledSelect() . ' UNION ALL ' . $query2->getCompiledSelect();

					$query = $this->db->query(
						'SELECT DISTINCT zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2, mn.fil_no ' .
						'FROM (' . $unionQuery . ') zz ' .
						'LEFT JOIN fil_trap xx ON xx.diary_no = zz.diary_no AND (xx.remarks IN (\'SCR -> AOR\', \'AUTO -> CAT\', \'SCR -> CAT\', \'SCR -> REF\', \'SCR -> FDR\')) ' .
						'LEFT JOIN fil_trap_his ww ON ww.diary_no = zz.diary_no AND (ww.remarks IN (\'SCR -> AOR\', \'AUTO -> CAT\', \'SCR -> CAT\', \'SCR -> REF\', \'SCR -> FDR\')) ' .
						'LEFT JOIN main mn ON mn.diary_no = zz.diary_no ' .
						'WHERE xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL OR mn.fil_no IS NOT NULL ' .
						'ORDER BY zz.disp_dt, zz.d_by_empid'
					);


					return $query->getResultArray();
				
			}elseif($case=='spnotcomp'){
				
					$query1 = $this->db->table('fil_trap')
						->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
						->where('DATE(disp_dt) >=', $frm_dt)
						->where('DATE(disp_dt) <=', $to_dt)
						->where('remarks', 'DE -> SCR');


					if ($row_id != '0') {
						$query1->where('d_to_empid', $emp_id);
					}


					$query2 = $this->db->table('fil_trap_his')
						->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
						->where('DATE(disp_dt) >=', $frm_dt)
						->where('DATE(disp_dt) <=', $to_dt)
						->where('remarks', 'DE -> SCR');

	
					if ($row_id != '0') {
						$query2->where('d_to_empid', $emp_id);
					}
					
					$unionQuery = $query1->getCompiledSelect() . ' UNION ALL ' . $query2->getCompiledSelect();

	
					$query = $this->db->query(
						'SELECT DISTINCT zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2, mn.fil_no ' .
						'FROM (' . $unionQuery . ') zz ' .
						'LEFT JOIN fil_trap xx ON xx.diary_no = zz.diary_no AND (xx.remarks IN (\'SCR -> AOR\', \'AUTO -> CAT\', \'SCR -> CAT\', \'SCR -> REF\', \'SCR -> FDR\')) ' .
						'LEFT JOIN fil_trap_his ww ON ww.diary_no = zz.diary_no AND (ww.remarks IN (\'SCR -> AOR\', \'AUTO -> CAT\', \'SCR -> CAT\', \'SCR -> REF\', \'SCR -> FDR\')) ' .
						'LEFT JOIN main mn ON mn.diary_no = zz.diary_no ' .
						'WHERE xx.diary_no IS NULL AND ww.diary_no IS NULL AND (mn.fil_no IS NULL OR mn.fil_no = \'\') ' .
						'ORDER BY zz.disp_dt, zz.d_by_empid'
					);


				return $query->getResultArray();
				
			}elseif($case=='spallotr'){
				$query1 = $this->db->table('fil_trap')
					->select('diary_no, d_by_empid, d_to_empid, disp_dt, r_by_empid, rece_dt')
					->where('DATE(disp_dt) >=', $frm_dt)
					->where('DATE(disp_dt) <=', $to_dt);
					if ($row_id != '0') {
						$query1->where('d_to_empid', $emp_id);
					}
				$query1 = $query1->groupStart()  
						->where('remarks', 'AOR -> SCR')
						->orWhere('remarks', 'FDR -> SCR')
					->groupEnd();  


				$query2 = $this->db->table('fil_trap_his')
					->select('diary_no, d_by_empid, d_to_empid, disp_dt, r_by_empid, rece_dt')
					->where('DATE(disp_dt) >=', $frm_dt)
					->where('DATE(disp_dt) <=', $to_dt);
					if ($row_id != '0') {
						$query2->where('d_to_empid', $emp_id);
					}
				$query2 = $query2->groupStart()  
						->where('remarks', 'AOR -> SCR')
						->orWhere('remarks', 'FDR -> SCR')
					->groupEnd();  

				$unionQuery = $query1->getCompiledSelect() . ' UNION ALL ' . $query2->getCompiledSelect();

				$query = $this->db->query(
					'SELECT * ' .
					'FROM (' . $unionQuery . ') AS zz ' .
					'ORDER BY zz.disp_dt, zz.d_by_empid'
				);

				return $query->getResultArray();
				
			}elseif($case=='spcompr'){

				$query1 = $this->db->table('fil_trap')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid')
					->where('DATE(disp_dt) >=', $frm_dt)
					->where('DATE(disp_dt) <=', $to_dt);
					if ($row_id != '0') {
						$query1->where('d_to_empid', $emp_id);
					}
				$query1 = $query1->groupStart()
						->where('remarks', 'AOR -> SCR')
						->orWhere('remarks', 'FDR -> SCR')
					->groupEnd()
					->where('r_by_empid !=', 0);


				$query2 = $this->db->table('fil_trap_his')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid')
					->where('DATE(disp_dt) >=', $frm_dt)
					->where('DATE(disp_dt) <=', $to_dt);
					if ($row_id != '0') {
						$query2->where('d_to_empid', $emp_id);
					}
				$query2 = $query2->groupStart()
						->where('remarks', 'AOR -> SCR')
						->orWhere('remarks', 'FDR -> SCR')
					->groupEnd()
					->where('r_by_empid !=', 0);

				$unionQuery = $query1->getCompiledSelect() . ' UNION ALL ' . $query2->getCompiledSelect();

				$query = $this->db->table('(' . $unionQuery . ') zz')
					->distinct()
					->select('zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2, mn.fil_no')
					->select('COALESCE(' .
						'SUBSTRING(string_agg(xx.rece_dt::text, \',\' ORDER BY xx.rece_dt), 1, 10), ' . 
						'SUBSTRING(string_agg(ww.rece_dt::text, \',\' ORDER BY ww.rece_dt), 1, 10)) AS rece_dt')
					->join('fil_trap xx', 'xx.diary_no = zz.diary_no AND (xx.remarks IN (\'SCR -> AOR\', \'AUTO -> CAT\', \'SCR -> CAT\', \'SCR -> FDR\')) AND xx.disp_dt >= zz.disp_dt', 'left')
					->join('fil_trap_his ww', 'ww.diary_no = zz.diary_no AND (ww.remarks IN (\'SCR -> AOR\', \'AUTO -> CAT\', \'SCR -> CAT\', \'SCR -> FDR\')) AND ww.disp_dt >= zz.disp_dt', 'left')
					->join('main mn', 'mn.diary_no = zz.diary_no', 'left')
					->where('xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL OR mn.fil_no IS NOT NULL')
					->groupBy('zz.diary_no, zz.d_to_empid, zz.disp_dt, zz.r_by_empid, zz.d_by_empid, xx.diary_no, ww.diary_no, mn.fil_no')  // Added missing columns to GROUP BY
					->orderBy('zz.disp_dt, zz.d_by_empid');


				return $query->get()->getResultArray();
				
				
			}elseif($case=='spnotcompr'){
			    $builder1 = $this->db->table('fil_trap');
				$query1 = $builder1->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid')
								   ->where('disp_dt >=', $frm_dt)
								   ->where('disp_dt <=', $to_dt)
								   ->whereIn('remarks', ['AOR -> SCR', 'FDR -> SCR']);
								   
								   
				if ($row_id != '0') {
						$query1->where('d_to_empid', $emp_id);
					}				   

				$builder2 = $this->db->table('fil_trap_his');
				$query2 = $builder2->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid')
								   ->where('disp_dt >=', $frm_dt)
								   ->where('disp_dt <=', $to_dt)
								   ->whereIn('remarks', ['AOR -> SCR', 'FDR -> SCR']);
								 
				if ($row_id != '0') {
						$query2->where('d_to_empid', $emp_id);
					}					 

				$query1Sql = $query1->getCompiledSelect(); 
				$query2Sql = $query2->getCompiledSelect();

				$unionQuery = "($query1Sql UNION ALL $query2Sql)";

				$zzQuery = $this->db->query("
					SELECT DISTINCT zz.*, 
						   xx.diary_no AS d_no,
						   ww.diary_no AS d_no2,
						   mn.fil_no,
						   COALESCE(
							   SPLIT_PART(STRING_AGG(xx.rece_dt::text, ',' ORDER BY xx.rece_dt), ',', 1),
							   SPLIT_PART(STRING_AGG(ww.rece_dt::text, ',' ORDER BY ww.rece_dt), ',', 1)
						   ) AS rece_dt
					FROM ($unionQuery) AS zz
					LEFT JOIN fil_trap xx 
						ON xx.diary_no = zz.diary_no 
						AND (xx.remarks IN ('SCR -> AOR', 'AUTO -> CAT', 'SCR -> CAT', 'SCR -> FDR'))
						AND xx.disp_dt >= zz.disp_dt
					LEFT JOIN fil_trap_his ww 
						ON ww.diary_no = zz.diary_no 
						AND (ww.remarks IN ('SCR -> AOR', 'AUTO -> CAT', 'SCR -> CAT', 'SCR -> FDR'))
						AND ww.disp_dt >= zz.disp_dt
					LEFT JOIN main mn 
						ON mn.diary_no = zz.diary_no
					WHERE (xx.diary_no IS NULL AND ww.diary_no IS NULL)
					  AND (mn.fil_no IS NULL OR mn.fil_no = '')
					GROUP BY 
						zz.diary_no, 
						zz.d_to_empid, 
						zz.r_by_empid, 
						zz.disp_dt, 
						zz.d_by_empid,
						mn.fil_no,
						xx.diary_no,
						ww.diary_no
					ORDER BY zz.disp_dt, zz.d_by_empid
				");

				return $zzQuery->getResultArray();

            }elseif($case=='sptotpen'){
				
				$query1 = $this->db->table('fil_trap')
					->select('diary_no, d_to_empid, r_by_empid, d_by_empid, disp_dt')
					->where('remarks', 'DE -> SCR')
				    ->where('disp_dt >=', '2018-06-30');
					
				if ($row_id != '0') {
					$query1->where('d_to_empid', $emp_id);
				}

				$query2 = $this->db->table('fil_trap_his')
					->select('diary_no, d_to_empid, r_by_empid, d_by_empid, disp_dt')
					->where('remarks', 'DE -> SCR')
					->where('disp_dt >=', '2018-06-30');
					
				if ($row_id != '0') {
					$query2->where('d_to_empid', $emp_id);
				}	

				$unionQuery = $query1->getCompiledSelect() . ' UNION ALL ' . $query2->getCompiledSelect();

				$query = $this->db->table('(' . $unionQuery . ') zz')
					->distinct()
					->select('zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2, mn.fil_no')
					->join('fil_trap xx', 'xx.diary_no = zz.diary_no AND (xx.remarks IN (\'SCR -> AOR\', \'AUTO -> CAT\', \'SCR -> CAT\', \'SCR -> REF\', \'SCR -> FDR\'))', 'left')
					->join('fil_trap_his ww', 'ww.diary_no = zz.diary_no AND (ww.remarks IN (\'SCR -> AOR\', \'AUTO -> CAT\', \'SCR -> CAT\', \'SCR -> REF\', \'SCR -> FDR\'))', 'left')
					->join('main mn', 'mn.diary_no = zz.diary_no', 'left')
					->where('xx.diary_no IS NULL')
					->where('ww.diary_no IS NULL')
					->groupStart()
						->where('mn.fil_no IS NULL')
						->orWhere('mn.fil_no', '')
					->groupEnd()
					->orderBy('zz.disp_dt')
					->orderBy('zz.d_by_empid');

					return $query->get()->getResultArray();
			
			}elseif($case=='sptotref'){

				$subQuery = "(SELECT diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid
							  FROM fil_trap
							  WHERE remarks IN ('AOR -> SCR', 'FDR -> SCR')";
							if ($row_id != '0') {
								$subQuery .= " AND d_to_empid = '$emp_id'"; 
							}  
								
				$subQuery .= "AND disp_dt::date >= '$frm_dt'
							  UNION ALL
							  SELECT diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid
							  FROM fil_trap_his
							  WHERE remarks IN ('AOR -> SCR', 'FDR -> SCR')";
							  if ($row_id != '0') {
								$subQuery .= " AND d_to_empid = '$emp_id'"; 
							}  
								
				$subQuery .= " AND disp_dt::date >= '$frm_dt')";


				$query = $this->db->newQuery()
					->select('zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2, mn.fil_no')
					->select("CASE 
						WHEN SPLIT_PART(STRING_AGG(xx.rece_dt::text, ',' ORDER BY xx.rece_dt), ',', 1) IS NULL 
							THEN SPLIT_PART(STRING_AGG(ww.rece_dt::text, ',' ORDER BY ww.rece_dt), ',', 1) 
						ELSE SPLIT_PART(STRING_AGG(xx.rece_dt::text, ',' ORDER BY xx.rece_dt), ',', 1) 
					END AS rece_dt", false)
					->from("($subQuery) AS zz", false)  // Fix alias issue
					->join('fil_trap AS xx', "xx.diary_no = zz.diary_no 
						AND (xx.remarks IN ('SCR -> AOR', 'AUTO -> CAT', 'SCR -> CAT', 'SCR -> FDR'))
						AND xx.disp_dt >= zz.disp_dt", 'left', false)
					->join('fil_trap_his AS ww', "ww.diary_no = zz.diary_no 
						AND (ww.remarks IN ('SCR -> AOR', 'AUTO -> CAT', 'SCR -> CAT', 'SCR -> FDR'))
						AND ww.disp_dt >= zz.disp_dt", 'left', false)
					->join('main AS mn', 'mn.diary_no = zz.diary_no', 'left')
					->where('xx.diary_no IS NULL')
					->where('ww.diary_no IS NULL')
					->groupStart()
						->where('mn.fil_no IS NULL')
						->orWhere('mn.fil_no', '')
					->groupEnd()
					->groupBy('zz.diary_no, zz.d_to_empid, zz.r_by_empid, zz.disp_dt, zz.d_by_empid, mn.fil_no, xx.diary_no, ww.diary_no')
					->orderBy('zz.disp_dt')
					->orderBy('zz.d_by_empid');

				return $query->get()->getResultArray();
			
	        }elseif($case=='sptotpenr'){
							
				$query1 = $this->db->table('fil_trap')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
					->where('remarks', 'DE -> SCR')
					->where('r_by_empid !=', 0)
					->where('DATE(rece_dt) >=', $frm_dt)
					->where('DATE(rece_dt) <=', $to_dt);
					
		        if ($row_id != '0') {
					$query1->where('r_by_empid', $emp_id);
				}				

				$query2 = $this->db->table('fil_trap_his')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
					->where('remarks', 'DE -> SCR')
					->where('r_by_empid !=', 0)
					->where('DATE(rece_dt) >=', $frm_dt)
					->where('DATE(rece_dt) <=', $to_dt);
					
				if ($row_id != '0') {
					$query2->where('r_by_empid', $emp_id);
				}	

				$unionQuery = $query1->getCompiledSelect() . ' UNION ALL ' . $query2->getCompiledSelect();

				$query = $this->db->table('(' . $unionQuery . ') as zz')
					->distinct()
					->orderBy('zz.disp_dt')
					->orderBy('zz.d_by_empid');

				return $query->get()->getResultArray();
				
			}elseif($case=='sptwd'){
				
				$query1 = $this->db->table('fil_trap')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
					->where('remarks', 'DE -> SCR')
					->where('r_by_empid !=', 0)
					->where('DATE(comp_dt) >=', $frm_dt)
					->where('DATE(comp_dt) <=', $to_dt);
				
				if ($row_id != '0') {
					$query1->where('r_by_empid', $emp_id);
				}

				$query2 = $this->db->table('fil_trap_his')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
					->where('remarks', 'DE -> SCR')
					->where('r_by_empid !=', 0)
					->where('DATE(comp_dt) >=', $frm_dt)
					->where('DATE(comp_dt) <=', $to_dt);
					
				if ($row_id != '0') {
					$query2->where('r_by_empid', $emp_id);
				}	

				$unionQuery = $query1->getCompiledSelect() . ' UNION ALL ' . $query2->getCompiledSelect();

				$query = $this->db->table('(' . $unionQuery . ') as zz')
					->distinct()
					->select('zz.*, 
							  xx.diary_no AS d_no, 
							  ww.diary_no AS d_no2, 
							  mn.fil_no, 
							  COALESCE(xx.remarks, ww.remarks) AS remarks,
							  COALESCE(xx.d_to_empid, ww.d_to_empid) AS d_d_to_empid,
							  COALESCE(xx.disp_dt, ww.disp_dt) AS d_disp_dt')
					->join('fil_trap xx', 'xx.diary_no = zz.diary_no AND (xx.remarks IN (\'SCR -> AOR\', \'AUTO -> CAT\', \'SCR -> CAT\', \'SCR -> REF\', \'SCR -> FDR\'))', 'left')
					->join('fil_trap_his ww', 'ww.diary_no = zz.diary_no AND (ww.remarks IN (\'SCR -> AOR\', \'AUTO -> CAT\', \'SCR -> CAT\', \'SCR -> REF\', \'SCR -> FDR\'))', 'left')
					->join('main mn', 'mn.diary_no = zz.diary_no', 'left')
					->where('xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL OR mn.fil_no IS NOT NULL')
					->limit(500) 
					->orderBy('zz.disp_dt', 'DESC')
					->orderBy('zz.d_by_empid');

				return $query->get()->getResultArray();

			}elseif($case=='sptwdr'){
					  $query1 = $this->db->table('fil_trap')
							->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid')
							->whereIn('remarks', ['AOR -> SCR', 'FDR -> SCR'])
							->where('r_by_empid !=', 0)
							->where('DATE(rece_dt) >=', $frm_dt)
							->where('DATE(rece_dt) <=', $to_dt);
							
							
					  if ($row_id != '0') {
						  $query1->where('r_by_empid', $emp_id);
					  }			

					$query2 = $this->db->table('fil_trap_his')
						->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid')
						->whereIn('remarks', ['AOR -> SCR', 'FDR -> SCR'])
						->where('r_by_empid !=', 0)
						->where('DATE(rece_dt) >=', $frm_dt)
						->where('DATE(rece_dt) <=', $to_dt);
						
					 if ($row_id != '0') {
						  $query2->where('r_by_empid', $emp_id);
					 }	

					$unionQuery = $query1->getCompiledSelect() . ' UNION ALL ' . $query2->getCompiledSelect();

					$query = $this->db->table('(' . $unionQuery . ') zz')
						->distinct()
						->select('zz.*')
						->orderBy('zz.disp_dt')
						->orderBy('zz.d_by_empid');

					return $query->get()->getResultArray();
			
			}elseif($case=='sptwdd'){
				
				$subQuery1 = $this->db->table('fil_trap')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid')
					->where("CAST(comp_dt AS DATE) BETWEEN '$frm_dt' AND '$to_dt'")
					->whereIn('remarks', ['AOR -> SCR', 'FDR -> SCR'])
					->where('r_by_empid!=', '0');
				 if($row_id != '0') {
					     $subQuery1->where('r_by_empid', $emp_id);
				 }
				 
				 $subQuery1 =  $subQuery1->getCompiledSelect();
					

				$subQuery2 = $this->db->table('fil_trap_his')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid')
					->where("CAST(comp_dt AS DATE) BETWEEN '$frm_dt' AND '$to_dt'")
					->whereIn('remarks', ['AOR -> SCR', 'FDR -> SCR'])
					->where('r_by_empid!=', '0');
				
				if ($row_id != '0') {
					  $subQuery2->where('r_by_empid', $emp_id);
				 }	
				 
				$subQuery2 = $subQuery2->getCompiledSelect();	

				$unionQuery = "($subQuery1 UNION ALL $subQuery2) AS union_trap";

				$builder = $this->db->table($unionQuery);
				$builder->select("
					union_trap.*, 
					xx.diary_no AS d_no, 
					ww.diary_no AS d_no2, 
					mn.fil_no,
					CASE 
						WHEN COALESCE(STRING_AGG(xx.rece_dt::TEXT, ',' ORDER BY xx.rece_dt), '') = '' 
						THEN COALESCE(STRING_AGG(ww.rece_dt::TEXT, ',' ORDER BY ww.rece_dt), '') 
						ELSE STRING_AGG(xx.rece_dt::TEXT, ',' ORDER BY xx.rece_dt) 
					END AS rece_dt
				");

				$builder->join('fil_trap AS xx', "xx.diary_no = union_trap.diary_no 
					AND xx.remarks IN ('SCR -> AOR', 'AUTO -> CAT', 'SCR -> CAT', 'SCR -> FDR') 
					AND xx.disp_dt >= union_trap.disp_dt", 'left');

				$builder->join('fil_trap_his AS ww', "ww.diary_no = union_trap.diary_no 
					AND ww.remarks IN ('SCR -> AOR', 'AUTO -> CAT', 'SCR -> CAT', 'SCR -> FDR') 
					AND ww.disp_dt >= union_trap.disp_dt", 'left');

				$builder->join('main AS mn', 'mn.diary_no = union_trap.diary_no', 'left');

				if ($row_id != '0') {
			      $builder->where('union_trap.r_by_empid', $emp_id);
				}

				$builder->where('(xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL OR mn.fil_no IS NOT NULL)');
				$builder->groupBy('union_trap.diary_no, union_trap.d_to_empid, union_trap.r_by_empid, 
										union_trap.disp_dt, union_trap.d_by_empid, mn.fil_no, xx.diary_no, ww.diary_no');

				$builder->orderBy('union_trap.disp_dt, union_trap.d_by_empid');

				return $builder->get()->getResultArray();
            }
	}elseif($ddl_users=='105'){
		    if($case=='spallot'){
								
				$subQuery1 = $this->db->table('fil_trap')
					->select('diary_no, d_by_empid, d_to_empid, disp_dt, r_by_empid, rece_dt')
					->where("DATE(disp_dt) >=", $frm_dt)
					->where("DATE(disp_dt) <=", $to_dt);
				if ($row_id != '0') {	
				   $subQuery1->where('r_by_empid', $emp_id);
				}
				
				$subQuery1 = $subQuery1->groupStart()
							->where('remarks', 'SCR -> CAT')
							->orWhere('remarks', 'AUTO -> CAT')
							->groupEnd()
							->getCompiledSelect();


				$subQuery2 = $this->db->table('fil_trap_his')
					->select('diary_no, d_by_empid, d_to_empid, disp_dt, r_by_empid, rece_dt')
					->where("DATE(disp_dt) >=", $frm_dt)
					->where("DATE(disp_dt) <=", $to_dt);
					
				if ($row_id != '0') {	
				   $subQuery2->where('r_by_empid', $emp_id);
				}	
				
				$subQuery2 = $subQuery2->groupStart()
							->where('remarks', 'SCR -> CAT')
							->orWhere('remarks', 'AUTO -> CAT')
						->groupEnd()
						->getCompiledSelect();

				$subQuery = "($subQuery1 UNION ALL $subQuery2) as zz";

				$builder = $this->db->table($subQuery)
					->select('zz.diary_no, zz.d_by_empid, zz.d_to_empid, zz.disp_dt, zz.r_by_empid, zz.rece_dt')
					->orderBy('zz.disp_dt, zz.d_by_empid')
					->get();
                return $builder->getResultArray();
				
			}elseif($case=='spcomp'){
				
				$subQuery1 = $this->db->table('fil_trap')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
					->where("DATE(disp_dt) BETWEEN '$frm_dt' AND '$to_dt'")
					->whereIn('remarks', ['CAT -> IB-Ex', 'TAG -> IB-Ex', 'CAT -> TAG']);
				if ($row_id != '0') {	
				      $subQuery1->where('d_by_empid', $emp_id);
				}	
				
				$subQuery1 = $subQuery1->getCompiledSelect();

				$subQuery2 = $this->db->table('fil_trap_his')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
					->where("DATE(disp_dt) BETWEEN '$frm_dt' AND '$to_dt'")
					->whereIn('remarks', ['CAT -> IB-Ex', 'TAG -> IB-Ex', 'CAT -> TAG']);
				if ($row_id != '0') {	
				      $subQuery2->where('d_by_empid', $emp_id);
				}
				$subQuery2 = $subQuery2->getCompiledSelect();

				$subQuery = "($subQuery1 UNION ALL $subQuery2) as zz";
				$builder = $this->db->table($subQuery)
					->distinct()
					->select('zz.*')
					->orderBy('zz.disp_dt, zz.d_by_empid')
					->get();

				return $builder->getResultArray();
				
			}elseif($case=='spnotcomp'){
				
				  $subQuery1 = $this->db->table('fil_trap')
						->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
						->where("DATE(disp_dt) BETWEEN '$frm_dt' AND '$to_dt'")
						->whereIn('remarks', ['SCR -> CAT', 'AUTO -> CAT']);
				  if ($row_id != '0') {	
				      $subQuery1->where('r_by_empid', $emp_id);
				  }		
					
				  $subQuery1 = $subQuery1->getCompiledSelect();


				$subQuery2 = $this->db->table('fil_trap_his')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
					->where("DATE(disp_dt) BETWEEN '$frm_dt' AND '$to_dt'")
					->whereIn('remarks', ['SCR -> CAT', 'AUTO -> CAT']);
				if ($row_id != '0') {	
				      $subQuery2->where('r_by_empid', $emp_id);
				}
				$subQuery2 = $subQuery2->getCompiledSelect();

				$subQuery = "($subQuery1 UNION ALL $subQuery2) as zz";

				$builder = $this->db->table($subQuery)
					->distinct()
					->select('zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2')
					->join('fil_trap xx', "xx.diary_no = zz.diary_no AND xx.remarks IN ('CAT -> TAG', 'CAT -> SCN', 'CAT -> IB-EX')", 'left')
					->join('fil_trap_his ww', "ww.diary_no = zz.diary_no AND ww.remarks IN ('CAT -> TAG', 'CAT -> SCN', 'CAT -> IB-EX')", 'left')
					->where('xx.diary_no IS NULL')
					->where('ww.diary_no IS NULL')
					->orderBy('zz.disp_dt, zz.d_by_empid')
					->get();

	
				return $builder->getResultArray();
				
			}elseif($case=='sptotpen'){
					$subQuery1 = $this->db->table('fil_trap')
						->select('diary_no, d_to_empid, r_by_empid, d_by_empid, disp_dt')
						->whereIn('remarks', ['SCR -> CAT', 'AUTO -> CAT'])
						->where('disp_dt >=', '2018-06-30');
					if ($row_id != '0') {	
						  $subQuery1->where('r_by_empid', $emp_id);
					}		
						
					$subQuery1 = $subQuery1->getCompiledSelect();


					$subQuery2 = $this->db->table('fil_trap_his')
						->select('diary_no, d_to_empid, r_by_empid, d_by_empid, disp_dt')
						->whereIn('remarks', ['SCR -> CAT', 'AUTO -> CAT'])
						->where('disp_dt >=', '2018-06-30');
					if ($row_id != '0') {	
						  $subQuery2->where('r_by_empid', $emp_id);
					}
					$subQuery2 = $subQuery2->getCompiledSelect();

				$subQuery = "($subQuery1 UNION ALL $subQuery2) as zz";

				$builder = $this->db->table($subQuery)
					->distinct()
					->select('zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2')
					->join('fil_trap xx', "xx.diary_no = zz.diary_no AND xx.remarks IN ('CAT -> TAG', 'CAT -> SCN', 'CAT -> IB-EX')", 'left')
					->join('fil_trap_his ww', "ww.diary_no = zz.diary_no AND ww.remarks IN ('CAT -> TAG', 'CAT -> SCN', 'CAT -> IB-EX')", 'left')
					->join('master.users u', "u.empid = zz.d_to_empid AND u.display = 'Y'", 'inner')
					->join('fil_trap_users t_u', "u.usercode = t_u.usercode AND t_u.usertype = '105' AND t_u.display = 'Y'", 'inner')
					->where('xx.diary_no IS NULL')
					->where('ww.diary_no IS NULL')
					->orderBy('zz.disp_dt, zz.d_by_empid')
					->get();

				return $builder->getResultArray();
				
			}elseif($case=='sptotpenr'){
				
				$subQuery1 = $this->db->table('fil_trap')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
					->whereIn('remarks', ['SCR -> CAT', 'AUTO -> CAT'])
					->where('r_by_empid !=', 0)
					->where("DATE(rece_dt) >=", $frm_dt)
					->where("DATE(rece_dt) <=", $to_dt);
					
				if ($row_id != '0') {	
						  $subQuery1->where('r_by_empid', $emp_id);
					}		
						
				$subQuery1 = $subQuery1->getCompiledSelect();


				$subQuery2 = $this->db->table('fil_trap_his')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
					->whereIn('remarks', ['SCR -> CAT', 'AUTO -> CAT'])
					->where('r_by_empid !=', 0)
					->where("DATE(rece_dt) >=", $frm_dt)
					->where("DATE(rece_dt) <=", $to_dt);
				if ($row_id != '0') {	
					  $subQuery2->where('r_by_empid', $emp_id);
				}
				$subQuery2 = $subQuery2->getCompiledSelect();

				$subQuery = "($subQuery1 UNION ALL $subQuery2) as zz";

				$builder = $this->db->table($subQuery)
					->distinct()
					->select('zz.*')
					->join('master.users u', "u.empid = zz.r_by_empid AND u.display = 'Y'", 'inner') // ✅ Fixed: Used single quotes
					->join('fil_trap_users t_u', "u.usercode = t_u.usercode AND t_u.usertype = '105' AND t_u.display = 'Y'", 'inner') // ✅ Fixed: Used single quotes
					->orderBy('zz.disp_dt, zz.d_by_empid')
					->get();

				return $builder->getResultArray();
				
			}elseif($case=='sptwd'){
				
				$subQuery1 = $this->db->table('fil_trap')
						->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
						->whereIn('remarks', ['SCR -> CAT', 'AUTO -> CAT'])
						->where('r_by_empid !=', 0)
						->where("DATE(comp_dt) >=", $frm_dt)
						->where("DATE(comp_dt) <=", $to_dt);
					if ($row_id != '0') {	
						$subQuery1->where('r_by_empid', $emp_id);
					}		
						
				$subQuery1 = $subQuery1->getCompiledSelect();


				$subQuery2 = $this->db->table('fil_trap_his')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
					->whereIn('remarks', ['SCR -> CAT', 'AUTO -> CAT'])
					->where('r_by_empid !=', 0)
					->where("DATE(comp_dt) >=", $frm_dt)
					->where("DATE(comp_dt) <=", $to_dt);
				if ($row_id != '0') {	
					  $subQuery2->where('r_by_empid', $emp_id);
				}
				$subQuery2 = $subQuery2->getCompiledSelect();

				$subQuery = "($subQuery1 UNION ALL $subQuery2) as zz";

				$builder = $this->db->table($subQuery)
					->distinct()
					->select("zz.*, 
							  COALESCE(xx.diary_no, 0) AS d_no,  -- FIXED: Using 0 instead of ''
							  COALESCE(ww.diary_no, 0) AS d_no2, 
							  COALESCE(xx.remarks, ww.remarks) AS remarks, 
							  COALESCE(xx.d_to_empid, ww.d_to_empid) AS d_d_to_empid, 
							  COALESCE(xx.disp_dt, ww.disp_dt) AS d_disp_dt")
					->join('fil_trap xx', "xx.diary_no = zz.diary_no AND (xx.remarks = 'CAT -> TAG' OR xx.remarks = 'CAT -> SCN' OR xx.remarks = 'CAT -> IB-EX')", 'left')
					->join('fil_trap_his ww', "ww.diary_no = zz.diary_no AND (ww.remarks = 'CAT -> TAG' OR ww.remarks = 'CAT -> SCN' OR ww.remarks = 'CAT -> IB-EX')", 'left')
					->join('master.users u', "u.empid = zz.r_by_empid AND u.display = 'Y'", 'inner')
					->join('fil_trap_users t_u', "u.usercode = t_u.usercode AND t_u.usertype = '105' AND t_u.display = 'Y'", 'inner')
					->where("(xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL)")
					->orderBy('zz.disp_dt, zz.d_by_empid')
					->get();

			return $builder->getResultArray();

		}
	}elseif($ddl_users=='106'){
		    if($case=='spallot'){
					$query1 = $this->db->table('fil_trap')
						->select('diary_no, d_by_empid, d_to_empid, disp_dt, r_by_empid, rece_dt')
						->where('DATE(disp_dt) >=', $frm_dt)
						->where('DATE(disp_dt) <=', $to_dt)
						->where('remarks', 'CAT -> TAG');
						
					if ($row_id != '0') {
						  $query1->where('d_to_empid', $emp_id);
					}		

					$query2 = $this->db->table('fil_trap_his')
						->select('diary_no, d_by_empid, d_to_empid, disp_dt, r_by_empid, rece_dt')
						->where('DATE(disp_dt) >=', $frm_dt)
						->where('DATE(disp_dt) <=', $to_dt)
						->where('remarks', 'CAT -> TAG');
						
					if ($row_id != '0') {
						  $query2->where('d_to_empid', $emp_id);
					}	

					$unionQuery = $query1->getCompiledSelect() . ' UNION ALL ' . $query2->getCompiledSelect();

					$query = $this->db->query($unionQuery . ' ORDER BY disp_dt, d_by_empid');

					return $query->getResultArray();
			}elseif($case=='spcomp'){
					$query1 = $this->db->table('fil_trap')
						->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
						->where('DATE(disp_dt) >=', $frm_dt)
						->where('DATE(disp_dt) <=', $to_dt);
					if ($row_id != '0') {
						$query1->where('d_by_empid', $emp_id);
					}	
					
					$query1 = $query1->groupStart()
							->where('remarks', 'CAT -> IB-Ex')
							->orWhere('remarks', 'TAG -> IB-Ex')
							->orWhere('remarks', 'CAT -> TAG')
						->groupEnd();

					$query2 = $this->db->table('fil_trap_his')
						->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
						->where('DATE(disp_dt) >=', $frm_dt)
						->where('DATE(disp_dt) <=', $to_dt);
						if ($row_id != '0') {
							$query2->where('d_by_empid', $emp_id);
						}	
					$query2 = $query2->groupStart()
							->where('remarks', 'CAT -> IB-Ex')
							->orWhere('remarks', 'TAG -> IB-Ex')
							->orWhere('remarks', 'CAT -> TAG')
						->groupEnd();

					$unionQuery = $query1->getCompiledSelect() . ' UNION ALL ' . $query2->getCompiledSelect();

					$query = $this->db->query('SELECT DISTINCT zz.* FROM (' . $unionQuery . ') zz ORDER BY zz.disp_dt, zz.d_by_empid');

					return $query->getResultArray();
				
			}elseif($case=='spnotcomp'){
					$query1 = $this->db->table('fil_trap')
						->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
						->where('DATE(disp_dt) >=', $frm_dt)
						->where('DATE(disp_dt) <=', $to_dt)
						->whereIn('remarks', ['CAT -> TAG']); 
						
					if ($row_id != '0') {
						$query1->where('d_to_empid', $emp_id);
					}		

					$query2 = $this->db->table('fil_trap_his')
						->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
						->where('DATE(disp_dt) >=', $frm_dt)
						->where('DATE(disp_dt) <=', $to_dt)
						->whereIn('remarks', ['CAT -> TAG']); 
						
					if ($row_id != '0') {
						$query2->where('d_to_empid', $emp_id);
					}	

					$unionQuery = $query1->getCompiledSelect() . ' UNION ALL ' . $query2->getCompiledSelect();

					$query = $this->db->query('SELECT DISTINCT zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2
						FROM (' . $unionQuery . ') zz
						LEFT JOIN fil_trap xx ON xx.diary_no = zz.diary_no
							AND (xx.remarks IN (\'TAG -> SCN\', \'TAG -> IB-EX\'))
						LEFT JOIN fil_trap_his ww ON ww.diary_no = zz.diary_no
							AND (ww.remarks IN (\'TAG -> SCN\', \'TAG -> IB-EX\'))
						WHERE (xx.diary_no IS NULL AND ww.diary_no IS NULL)
						ORDER BY zz.disp_dt, zz.d_by_empid');

				return $query->getResultArray();
				
			}elseif($case=='sptotpen'){
				
					$query1 = $this->db->table('fil_trap')
						->select('diary_no, d_to_empid, r_by_empid, d_by_empid, disp_dt')
						->where('remarks', 'CAT -> TAG')
						->where('disp_dt >=', '2018-06-30');
						
					if ($row_id != '0') {
						$query1->where('d_to_empid', $emp_id);
					}	

					$query2 = $this->db->table('fil_trap_his')
						->select('diary_no, d_to_empid, r_by_empid, d_by_empid, disp_dt')
						->where('remarks', 'CAT -> TAG')
						->where('disp_dt >=', '2018-06-30');
						
					if ($row_id != '0') {
						$query2->where('d_to_empid', $emp_id);
					}	

					$unionQuery = $query1->getCompiledSelect() . ' UNION ALL ' . $query2->getCompiledSelect();

					$query = $this->db->query('
						SELECT DISTINCT zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2
						FROM (' . $unionQuery . ') zz
						LEFT JOIN fil_trap xx ON xx.diary_no = zz.diary_no 
							AND (xx.remarks IN (\'TAG -> SCN\', \'TAG -> IB-EX\'))
						LEFT JOIN fil_trap_his ww ON ww.diary_no = zz.diary_no 
							AND (ww.remarks IN (\'TAG -> SCN\', \'TAG -> IB-EX\'))
						WHERE (xx.diary_no IS NULL AND ww.diary_no IS NULL)
						ORDER BY zz.disp_dt, zz.d_by_empid
					');

					return $query->getResultArray();
				
			}elseif($case=='sptotpenr'){
				    $builder1 = $this->db->table('fil_trap');
					$query1 = $builder1->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
							   ->where('DATE(rece_dt) >=', $frm_dt)
							   ->where('DATE(rece_dt) <=', $to_dt)
							   ->where('remarks', 'CAT -> TAG')
							   ->where('r_by_empid !=', 0);
							   
					if ($row_id != '0') {
						$query1->where('r_by_empid', $emp_id);
					}			   

					$builder2 = $this->db->table('fil_trap_his');
					$query2 = $builder2->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
									   ->where('DATE(rece_dt) >=', $frm_dt)
									   ->where('DATE(rece_dt) <=', $to_dt)
									   ->where('remarks', 'CAT -> TAG')
									   ->where('r_by_empid !=', 0);
									   
					if ($row_id != '0') {
						$query2->where('r_by_empid', $emp_id);
					}				   

					$query1Sql = $query1->getCompiledSelect(); 
					$query2Sql = $query2->getCompiledSelect(); 

					$unionQuery = "($query1Sql UNION ALL $query2Sql)";

					$zzQuery = $this->db->query("SELECT DISTINCT zz.*
												  FROM ($unionQuery) AS zz
												  ORDER BY zz.disp_dt, zz.d_by_empid");

					return $zzQuery->getResultArray();
				
				
			}elseif($case=='sptwd'){
				$builder1 = $this->db->table('fil_trap');
				$query1 = $builder1->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
								   ->where('DATE(comp_dt) >=', $frm_dt)
								   ->where('DATE(comp_dt) <=', $to_dt)
								   ->where('remarks', 'CAT -> TAG')
								   ->where('r_by_empid !=', 0);
								   
				if ($row_id != '0') {
					$query1->where('r_by_empid', $emp_id);
				}					   


				$builder2 = $this->db->table('fil_trap_his');
				$query2 = $builder2->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
								   ->where('DATE(comp_dt) >=', $frm_dt)
								   ->where('DATE(comp_dt) <=', $to_dt)
								   ->where('remarks', 'CAT -> TAG')
								   ->where('r_by_empid !=', 0);
						
				if ($row_id != '0') {
					$query2->where('r_by_empid', $emp_id);
				}		

				$query1Sql = $query1->getCompiledSelect(); 
				$query2Sql = $query2->getCompiledSelect(); 

				$unionQuery = "($query1Sql UNION ALL $query2Sql)";

				$zzQuery = $this->db->query("
					SELECT DISTINCT zz.*, 
						   xx.diary_no AS d_no,
						   ww.diary_no AS d_no2,
						   COALESCE(xx.remarks, ww.remarks) AS remarks,
						   COALESCE(xx.d_to_empid, ww.d_to_empid) AS d_d_to_empid,
						   COALESCE(xx.disp_dt, ww.disp_dt) AS d_disp_dt
					FROM ($unionQuery) AS zz
					LEFT JOIN fil_trap xx 
						ON xx.diary_no = zz.diary_no 
						AND (xx.remarks = 'TAG -> SCN' OR xx.remarks = 'TAG -> IB-EX')
					LEFT JOIN fil_trap_his ww 
						ON ww.diary_no = zz.diary_no 
						AND (ww.remarks = 'TAG -> SCN' OR ww.remarks = 'TAG -> IB-EX')
					WHERE (xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL)
					ORDER BY zz.disp_dt, zz.d_by_empid
				");

			  return $zzQuery->getResult();
				
			}
	}elseif($ddl_users=='107'){
		    if($case=='spallot'){
	               $builder = $this->db->table('fil_trap');
					$query1 = $builder->select('diary_no, d_by_empid, d_to_empid, disp_dt, r_by_empid, rece_dt')
									  ->where('DATE(disp_dt) >=', $frm_dt)
									  ->where('DATE(disp_dt) <=', $to_dt)
									  ->groupStart()
										  ->where('remarks', 'SCN -> IB-Ex')
										  ->orWhere('remarks', 'TAG -> IB-Ex')
										  ->orWhere('remarks', 'CAT -> IB-Ex')
									  ->groupEnd();
									  
					if ($row_id != '0') {
						$query1->where('d_to_empid', $emp_id);
					}					  

					$builder2 = $this->db->table('fil_trap_his');
					$query2 = $builder2->select('diary_no, d_by_empid, d_to_empid, disp_dt, r_by_empid, rece_dt')
									   ->where('DATE(disp_dt) >=', $frm_dt)
									   ->where('DATE(disp_dt) <=', $to_dt)
									    ->whereIn('remarks', ['SCN -> IB-Ex', 'TAG -> IB-Ex', 'CAT -> IB-Ex'])
									   ->groupStart()
										   ->where('remarks', 'SCN -> IB-Ex')
										   ->orWhere('remarks', 'TAG -> IB-Ex')
										   ->orWhere('remarks', 'CAT -> IB-Ex')
									   ->groupEnd();
								
					if ($row_id != '0') {
						$query2->where('d_to_empid', $emp_id);
					}			

					$query1Sql = $query1->getCompiledSelect(); 
					$query2Sql = $query2->getCompiledSelect(); 


				$unionQuery = "($query1Sql UNION ALL $query2Sql)";

				$finalQuery = $this->db->query("
					SELECT * 
					FROM ($unionQuery) AS combined
					ORDER BY disp_dt, d_by_empid
				");

				return $finalQuery->getResultArray();

			}elseif($case=='spcomp'){
				$builder1 = $this->db->table('fil_trap');
				$query1 = $builder1->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
								  ->where('DATE(disp_dt) >=', $frm_dt)
								  ->where('DATE(disp_dt) <=', $to_dt)
								  ->whereIn('remarks', ['SCN -> IB-Ex', 'TAG -> IB-Ex', 'CAT -> IB-Ex'])
								  ->where('r_by_empid !=', 0);
					if ($row_id != '0') {
						$query1->where('d_to_empid', $emp_id);
					}			  

				$builder2 = $this->db->table('fil_trap_his');
				$query2 = $builder2->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
								   ->where('DATE(disp_dt) >=', $frm_dt)
								   ->where('DATE(disp_dt) <=', $to_dt)
								   ->whereIn('remarks', ['SCN -> IB-Ex', 'TAG -> IB-Ex', 'CAT -> IB-Ex'])
								   ->where('r_by_empid !=', 0);
								
					if ($row_id != '0') {
						$query2->where('d_to_empid', $emp_id);
					}					

				$query1Sql = $query1->getCompiledSelect();
				$query2Sql = $query2->getCompiledSelect();

				$unionQuery = "($query1Sql UNION ALL $query2Sql)";

				$finalQuery = $this->db->query("
					SELECT DISTINCT zz.*, 
						   xx.diary_no AS d_no,
						   ww.diary_no AS d_no2
					FROM ($unionQuery) AS zz
					LEFT JOIN fil_trap xx 
						ON xx.diary_no = zz.diary_no 
						AND xx.remarks = 'IB-Ex -> Crt'
					LEFT JOIN fil_trap_his ww 
						ON ww.diary_no = zz.diary_no 
						AND ww.remarks = 'IB-Ex -> Crt'
					WHERE (xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL)
					ORDER BY zz.disp_dt, zz.d_by_empid
				");

			return  $finalQuery->getResultArray();


			}elseif($case=='spnotcomp'){
				$filTrapQuery = $this->db->table('fil_trap')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
					->where("DATE(disp_dt) BETWEEN '$frm_dt' AND '$to_dt'", null, false) // Fix applied here
					->whereIn('remarks', ['SCN -> IB-Ex', 'TAG -> IB-Ex', 'CAT -> IB-Ex']);
					
				if ($row_id != '0') {
					$filTrapQuery->where('d_to_empid', $emp_id);
				}	

				$filTrapHisQuery = $this->db->table('fil_trap_his')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
					->where("DATE(disp_dt) BETWEEN '$frm_dt' AND '$to_dt'", null, false) // Fix applied here
					->where('d_to_empid', $emp_id)
					->whereIn('remarks', ['SCN -> IB-Ex', 'TAG -> IB-Ex', 'CAT -> IB-Ex']);
					
				if ($row_id != '0') {
					$filTrapHisQuery->where('d_to_empid', $emp_id);
				}

					$combinedQuery = $filTrapQuery->unionAll($filTrapHisQuery);

					$combinedSubQuery = $combinedQuery->getCompiledSelect();

				$query = $this->db->table("($combinedSubQuery) AS zz")
					->join('fil_trap AS xx', "xx.diary_no = zz.diary_no AND xx.remarks = 'IB-Ex -> Crt'", 'left', false)
					->join('fil_trap_his AS ww', "ww.diary_no = zz.diary_no AND ww.remarks = 'IB-Ex -> Crt'", 'left', false)
					->where('xx.diary_no IS NULL')
					->where('ww.diary_no IS NULL')
					->select('zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2')
					->distinct()
					->orderBy('zz.disp_dt')
					->orderBy('zz.d_by_empid');

				return  $query->get()->getResultArray();
			
			}elseif($case=='sptotpen'){
				
				$filTrapQuery = $this->db->table('fil_trap')
						->select('diary_no, d_to_empid, r_by_empid, d_by_empid, disp_dt')
						->whereIn('remarks', ['SCN -> IB-Ex', 'TAG -> IB-Ex', 'CAT -> IB-Ex'])
						->where('disp_dt >=', '2018-06-30');
						
				if ($row_id != '0') {
					$filTrapQuery->where('d_to_empid', $emp_id);
				}		

				$filTrapHisQuery = $this->db->table('fil_trap_his')
					->select('diary_no, d_to_empid, r_by_empid, d_by_empid, disp_dt')
					->whereIn('remarks', ['SCN -> IB-Ex', 'TAG -> IB-Ex', 'CAT -> IB-Ex'])
					->where('disp_dt >=', '2018-06-30');
					
				if ($row_id != '0') {
					$filTrapHisQuery->where('d_to_empid', $emp_id);
				}	

				$combinedQuery = $filTrapQuery->unionAll($filTrapHisQuery);

				$combinedSubQuery = $combinedQuery->getCompiledSelect();


				$query = $this->db->table("($combinedSubQuery) AS zz")
					->join('fil_trap AS xx', "xx.diary_no = zz.diary_no AND xx.remarks = 'IB-Ex -> Crt'", 'left', false)
					->join('fil_trap_his AS ww', "ww.diary_no = zz.diary_no AND ww.remarks = 'IB-Ex -> Crt'", 'left', false)
					->where('xx.diary_no IS NULL')
					->where('ww.diary_no IS NULL')
					->select('zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2')
					->distinct()
					->orderBy('disp_dt')
					->orderBy('d_by_empid');


				return $query->get()->getResultArray();
				
			}elseif($case=='sptotpenr'){
								
				$filTrapQuery = $this->db->table('fil_trap')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
					->where("DATE(rece_dt) BETWEEN '$frm_dt' AND '$to_dt'", null, false) 
					->whereIn('remarks', ['SCN -> IB-Ex', 'TAG -> IB-Ex', 'CAT -> IB-Ex'])
					->where('r_by_empid !=', 0);
					
				if ($row_id != '0') {
					$filTrapQuery->where('r_by_empid', $emp_id);
				}	

				$filTrapHisQuery = $this->db->table('fil_trap_his')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
					->where("DATE(rece_dt) BETWEEN '$frm_dt' AND '$to_dt'", null, false) 
					->whereIn('remarks', ['SCN -> IB-Ex', 'TAG -> IB-Ex', 'CAT -> IB-Ex'])
					->where('r_by_empid !=', 0);
					
				if ($row_id != '0') {
					$filTrapHisQuery->where('r_by_empid', $emp_id);
				}	

				$combinedQuery = $filTrapQuery->unionAll($filTrapHisQuery);

				$combinedSubQuery = $combinedQuery->getCompiledSelect();

				$query = $this->db->table("($combinedSubQuery) AS zz")
					->select('DISTINCT zz.*', false) 
					->orderBy('disp_dt')
					->orderBy('d_by_empid');

				return $query->get()->getResultArray();
				
			}elseif($case=='sptwd'){
				
				$filTrapQuery = $this->db->table('fil_trap')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
					->where("DATE(comp_dt) BETWEEN '$frm_dt' AND '$to_dt'", null, false) // Date filtering
					->whereIn('remarks', ['SCN -> IB-Ex', 'TAG -> IB-Ex', 'CAT -> IB-Ex'])
					->where('r_by_empid !=', 0);
					
				if ($row_id != '0') {
				   $filTrapQuery->where('r_by_empid', $emp_id);
				}	

				$filTrapHisQuery = $this->db->table('fil_trap_his')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
					->where("DATE(comp_dt) BETWEEN '$frm_dt' AND '$to_dt'", null, false) // Date filtering
					->where('r_by_empid', $emp_id)
					->whereIn('remarks', ['SCN -> IB-Ex', 'TAG -> IB-Ex', 'CAT -> IB-Ex'])
					->where('r_by_empid !=', 0);
					
				if ($row_id != '0') {
					   $filTrapHisQuery->where('r_by_empid', $emp_id);
				}	

				$combinedQuery = $filTrapQuery->unionAll($filTrapHisQuery);

				$combinedSubQuery = $combinedQuery->getCompiledSelect();

				$query = $this->db->table("($combinedSubQuery) AS zz")
					->join('fil_trap AS xx', "xx.diary_no = zz.diary_no AND xx.remarks = 'IB-Ex -> Crt'", 'left', false)
					->join('fil_trap_his AS ww', "ww.diary_no = zz.diary_no AND ww.remarks = 'IB-Ex -> Crt'", 'left', false)
					->where('(xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL)', null, false) // Ensure proper filtering
					->select('zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2', false)
					->select("COALESCE(xx.remarks, ww.remarks) AS remarks", false) // Equivalent to IF(xx.remarks IS NULL, ww.remarks, xx.remarks)
					->select("COALESCE(xx.d_to_empid, ww.d_to_empid) AS d_d_to_empid", false) // Equivalent to IF(xx.d_to_empid IS NULL, ww.d_to_empid, xx.d_to_empid)
					->select("COALESCE(xx.disp_dt, ww.disp_dt) AS d_disp_dt", false) // Equivalent to IF(xx.disp_dt IS NULL, ww.disp_dt, xx.disp_dt)
					->distinct()
					->orderBy('zz.disp_dt')
					->orderBy('zz.d_by_empid');

                return $query->get()->getResultArray();
				
			}
	}elseif($ddl_users=='109'){
		    if($case=='spallot'){
				$query = $this->db->table('ld_move c')
						->select('c.diary_no, c.disp_by AS d_to_empid, c.disp_dt, c.docnum, c.docyear, c.disp_to, b.docdesc, a.other1')
						->join('master.users u', 'u.usercode = c.disp_by AND u.display = \'Y\'', 'inner', false)
						->join('fil_trap_users t_u', 'u.usercode = t_u.usercode AND t_u.usertype = \'109\' AND t_u.display = \'Y\'', 'inner', false)
						->join('master.docmaster b', 'c.doccode = b.doccode AND c.doccode1 = b.doccode1', 'inner', false)
						->join('docdetails a', 'a.diary_no = c.diary_no AND a.doccode = c.doccode AND a.doccode1 = c.doccode1 AND a.docnum = c.docnum AND a.docyear = c.docyear', 'inner', false)
						->where('DATE(c.disp_dt) >=', $frm_dt)  
						->where('DATE(c.disp_dt) <=', $to_dt)  
						->where('b.display', 'Y')
						->where('a.display', 'Y');
					if ($row_id != '0') {
						$query->where('c.disp_by', $r_user_id);
				    }	
						
				return $query->get()->getResultArray();
				
			}elseif($case=='spcomp'){
				
				$query = $this->db->table('ld_move c')
					->select('c.diary_no, c.disp_by AS d_to_empid, c.disp_dt, c.docnum, c.docyear, c.disp_to, b.docdesc, a.other1')
					->join('master.users u', 'u.usercode = c.disp_by AND u.display = \'Y\'', 'inner', false)
					->join('fil_trap_users t_u', 'u.usercode = t_u.usercode AND t_u.usertype = \'109\' AND t_u.display = \'Y\'', 'inner', false)
					->join('master.docmaster b', 'c.doccode = b.doccode AND c.doccode1 = b.doccode1', 'inner', false)
					->join('docdetails a', 'a.diary_no = c.diary_no AND a.doccode = c.doccode AND a.doccode1 = c.doccode1 AND a.docnum = c.docnum AND a.docyear = c.docyear', 'inner', false)
					->where('DATE(c.disp_dt) >=', $frm_dt)
					->where('DATE(c.disp_dt) <=', $to_dt)
					->where('b.display', 'Y')
					->where('a.display', 'Y');
					if ($row_id != '0') {
					     $query->where('c.disp_by', $r_user_id);
				    }	

				return $query->get()->getResultArray();
				
			}elseif($case=='spnotcomp'){
				
				$filTrapQuery = $this->db->table('fil_trap')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
					->where("disp_dt >=", $frm_dt)
					->where("disp_dt <=", $to_dt);
				
				if ($row_id != '0') {
					$filTrapQuery->where('r_by_empid', $emp_id);
				}

				$filTrapHisQuery = $this->db->table('fil_trap_his')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
					->where("disp_dt >=", $frm_dt)
					->where("disp_dt <=", $to_dt);
					
					
				if ($row_id != '0') {
					$filTrapHisQuery->where('r_by_empid', $emp_id);
				}	

				$combinedQuery = "({$filTrapQuery->getCompiledSelect()} UNION ALL {$filTrapHisQuery->getCompiledSelect()}) AS zz";

				$query = $this->db->table($combinedQuery, false) // False prevents automatic escaping of subquery
					->select('zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2')
					->join('fil_trap AS xx', 'xx.diary_no = zz.diary_no AND xx.remarks = \'\'', 'left')
					->join('fil_trap_his AS ww', 'ww.diary_no = zz.diary_no AND ww.remarks = \'\'', 'left')
					->where('xx.diary_no IS NULL')
					->where('ww.diary_no IS NULL')
					->orderBy('zz.disp_dt')
					->orderBy('zz.d_by_empid');

				return $query->get()->getResultArray();
			}
	}elseif($ddl_users=='108'){
		    if($case=='spallot'){
				// pending 
				$builder1 = $this->db->table('fil_trap')
					->select('diary_no, d_by_empid, d_to_empid, disp_dt, r_by_empid, rece_dt')
					->where('DATE(disp_dt) >=', $frm_dt)
					->where('DATE(disp_dt) <=', $to_dt)
					->where('remarks', 'SCR -> FDR');
				if ($row_id != '0') {
					$builder1->where('r_by_empid', $emp_id);
				}	

				$builder2 = $this->db->table('fil_trap_his')
					->select('diary_no, d_by_empid, d_to_empid, disp_dt, r_by_empid, rece_dt')
					->where('DATE(disp_dt) >=', $frm_dt)
					->where('DATE(disp_dt) <=', $to_dt)
					->where('remarks', 'SCR -> FDR');
					
				if ($row_id != '0') {
					$builder2->where('r_by_empid', $emp_id);
				}	

				$unionQuery = $builder1->getCompiledSelect() . ' UNION ALL ' . $builder2->getCompiledSelect();

				$query = $this->db->query($unionQuery . ' ORDER BY disp_dt, d_by_empid');

				return $query->getResultArray();
			
			}elseif($case=='spcomp'){
                   
				   $subquery = "
						(SELECT diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt
						 FROM fil_trap 
						 WHERE disp_dt::DATE BETWEEN '$frm_dt' AND '$to_dt'";

					if ($row_id != '0') {
						$subquery .= " AND r_by_empid = $emp_id";
					}

					$subquery .= " AND remarks = 'SCR -> FDR'
						 
						 UNION ALL
						 
						 SELECT diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt
						 FROM fil_trap_his 
						 WHERE disp_dt::DATE BETWEEN '$frm_dt' AND '$to_dt'";

					if ($row_id != '0') {
						$subquery .= " AND r_by_empid = $emp_id";
					}

					$subquery .= " AND remarks = 'SCR -> FDR'
						) AS zz";

					$builder = $this->db->query("
						SELECT DISTINCT zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2, mn.fil_no 
						FROM $subquery
						LEFT JOIN fil_trap xx ON xx.diary_no = zz.diary_no 
							AND (xx.remarks = 'FDR -> AOR' OR xx.remarks = 'FDR -> SCR')
						LEFT JOIN fil_trap_his ww ON ww.diary_no = zz.diary_no 
							AND (ww.remarks = 'FDR -> AOR' OR ww.remarks = 'FDR -> SCR')
						LEFT JOIN main mn ON mn.diary_no = zz.diary_no
						WHERE (xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL OR mn.fil_no IS NOT NULL)
						ORDER BY zz.disp_dt, zz.d_by_empid
					");

					return $builder->getResultArray();					 
			}elseif($case=='spnotcomp'){
				
					$subquery1 = $this->db->table('fil_trap')
						->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
						->where("disp_dt::DATE BETWEEN '$frm_dt' AND '$to_dt'", null, false)
						->where('remarks', 'SCR -> FDR');
					
					if ($row_id != '0') {
						$subquery1->where('r_by_empid', $emp_id);
					}	
					

					$subquery2 = $this->db->table('fil_trap_his')
						->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
						->where("disp_dt::DATE BETWEEN '$frm_dt' AND '$to_dt'", null, false)
						->where('remarks', 'SCR -> FDR');
						
					if ($row_id != '0') {
						$subquery2->where('r_by_empid', $emp_id);
					}		
						

					$zz = $subquery1->unionAll($subquery2);

					$builder = $this->db->table("({$zz->getCompiledSelect()}) AS zz")
						->distinct()
						->select("zz.*, xx.diary_no as d_no, ww.diary_no as d_no2, mn.fil_no")
						->join('fil_trap xx', 'xx.diary_no = zz.diary_no AND (xx.remarks = \'FDR -> AOR\' OR xx.remarks = \'FDR -> SCR\')', 'left')
						->join('fil_trap_his ww', 'ww.diary_no = zz.diary_no AND (ww.remarks = \'FDR -> AOR\' OR ww.remarks = \'FDR -> SCR\')', 'left')
						->join('main mn', 'mn.diary_no = zz.diary_no', 'left')
						->where('(xx.diary_no IS NULL AND ww.diary_no IS NULL AND (mn.fil_no IS NULL OR mn.fil_no = \'\'))', null, false)
						->orderBy('zz.disp_dt, zz.d_by_empid');

					$query = $builder->get();
					return $query->getResultArray();
				
			}elseif($case=='spallotr'){
					$subquery1 = $this->db->table('fil_trap')
						->select('diary_no, d_by_empid, d_to_empid, disp_dt, r_by_empid, rece_dt')
						->where("disp_dt::DATE BETWEEN '$frm_dt' AND '$to_dt'", null, false)
						->where('remarks', 'AOR -> FDR');
					if ($row_id != '0') {
							$subquery1->where('r_by_empid', $emp_id);
					}		

					$subquery2 = $this->db->table('fil_trap_his')
						->select('diary_no, d_by_empid, d_to_empid, disp_dt, r_by_empid, rece_dt')
						->where("disp_dt::DATE BETWEEN '$frm_dt' AND '$to_dt'", null, false)
						->where('remarks', 'AOR -> FDR');
					
					if ($row_id != '0') {
							$subquery2->where('r_by_empid', $emp_id);
					}

					$zz = $subquery1->unionAll($subquery2);

					$builder = $this->db->table("({$zz->getCompiledSelect()}) AS zz")
						->select('zz.diary_no, zz.d_by_empid, zz.d_to_empid, zz.disp_dt, zz.r_by_empid, zz.rece_dt')
						->orderBy('zz.disp_dt, zz.d_by_empid');

					$query = $builder->get();
					return $query->getResultArray();
				
				 
			}elseif($case=='spcompr'){
				
					$subquery1 = $this->db->table('fil_trap')
						->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid')
						->where("disp_dt::DATE BETWEEN '$frm_dt' AND '$to_dt'", null, false)
						->where('remarks', 'AOR -> FDR')
						->where('r_by_empid !=', 0);
						
					if ($row_id != '0') {
							$subquery1->where('r_by_empid', $emp_id);
					}	

					$subquery2 = $this->db->table('fil_trap_his')
						->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid')
						->where("disp_dt::DATE BETWEEN '$frm_dt' AND '$to_dt'", null, false)
						->where('remarks', 'AOR -> FDR')
						->where('r_by_empid !=', 0);
						
					if ($row_id != '0') {
							$subquery2->where('r_by_empid', $emp_id);
					}	

					$zz = $subquery1->unionAll($subquery2);

					$builder = $this->db->table("({$zz->getCompiledSelect()}) AS zz")
						->distinct()
						->select("zz.diary_no, zz.d_to_empid, zz.r_by_empid, zz.disp_dt, zz.d_by_empid,
							COALESCE(MIN(xx.rece_dt), MIN(ww.rece_dt)) AS rece_dt,
							xx.diary_no as d_no, 
							ww.diary_no as d_no2, 
							mn.fil_no")
						->join('fil_trap xx', 'xx.diary_no = zz.diary_no AND xx.remarks = \'FDR -> SCR\' AND xx.disp_dt >= zz.disp_dt', 'left')
						->join('fil_trap_his ww', 'ww.diary_no = zz.diary_no AND ww.remarks = \'FDR -> SCR\' AND ww.disp_dt >= zz.disp_dt', 'left')
						->join('main mn', 'mn.diary_no = zz.diary_no', 'left')
						->where('(xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL OR mn.fil_no IS NOT NULL)', null, false)
						->groupBy('zz.diary_no, zz.d_to_empid, zz.r_by_empid, zz.disp_dt, zz.d_by_empid, xx.diary_no, ww.diary_no, mn.fil_no')
						->orderBy('zz.disp_dt, zz.d_by_empid');


					$query = $builder->get();
					return $query->getResultArray();
				
			}elseif($case=='spnotcompr'){
						$subQuery = "(SELECT diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid 
							  FROM fil_trap 
							  WHERE DATE(disp_dt) BETWEEN '{$frm_dt}' AND '{$to_dt}'";
						if ($row_id != '0'){
							$subQuery .= " AND r_by_empid = $emp_id";
						}							  
						$subQuery .= " AND remarks = 'AOR -> FDR' 
							  UNION ALL 
							  SELECT diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid 
							  FROM fil_trap_his 
							  WHERE DATE(disp_dt) BETWEEN '{$frm_dt}' AND '{$to_dt}'";
						if ($row_id != '0'){
							$subQuery .= " AND r_by_empid = $emp_id";
						}								  
						$subQuery .= " AND remarks = 'AOR -> FDR')";

						$builder = $this->db->table("({$subQuery}) AS zz")
							->select("zz.diary_no, zz.d_to_empid, zz.r_by_empid, zz.disp_dt, zz.d_by_empid,
									  xx.diary_no AS d_no, ww.diary_no AS d_no2, mn.fil_no, 
									  COALESCE(
										(SELECT string_agg(xx.rece_dt::text, ',' ORDER BY xx.rece_dt) 
										 FROM fil_trap xx 
										 WHERE xx.diary_no = zz.diary_no),
										(SELECT string_agg(ww.rece_dt::text, ',' ORDER BY ww.rece_dt) 
										 FROM fil_trap_his ww 
										 WHERE ww.diary_no = zz.diary_no)
									  ) AS rece_dt")
							->join('fil_trap AS xx', 'xx.diary_no = zz.diary_no AND xx.remarks = \'FDR -> SCR\' AND xx.disp_dt >= zz.disp_dt', 'left')
							->join('fil_trap_his AS ww', 'ww.diary_no = zz.diary_no AND ww.remarks = \'FDR -> SCR\' AND ww.disp_dt >= zz.disp_dt', 'left')
							->join('main AS mn', 'mn.diary_no = zz.diary_no', 'left')
							->where('(xx.diary_no IS NULL AND ww.diary_no IS NULL AND (mn.fil_no IS NULL OR mn.fil_no = \'\'))')
							->groupBy('zz.diary_no, zz.d_to_empid, zz.r_by_empid, zz.disp_dt, zz.d_by_empid, xx.diary_no, ww.diary_no, mn.fil_no')
							->orderBy('zz.disp_dt, zz.d_by_empid')
							->get();

						return $builder->getResultArray();
            }elseif($case=='sptotpen'){
						$subQuery = "(SELECT diary_no, d_to_empid, r_by_empid, d_by_empid, disp_dt 
									  FROM fil_trap 
									  WHERE remarks = 'SCR -> FDR' AND disp_dt >= '2018-06-30'";

						if ($row_id != '0') {
							$subQuery .= " AND r_by_empid = '$emp_id'"; 
						}

						$subQuery .= " UNION ALL  --
									   SELECT diary_no, d_to_empid, r_by_empid, d_by_empid, disp_dt 
									   FROM fil_trap_his 
									   WHERE remarks = 'SCR -> FDR' AND disp_dt >= '2018-06-30'";

						if ($row_id != '0') {
							$subQuery .= " AND r_by_empid = '$emp_id'";  
						}

						$subQuery .= ")"; 

						$builder = $this->db->table("({$subQuery}) AS zz")
							->select("zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2, mn.fil_no")
							->join("fil_trap AS xx", "xx.diary_no = zz.diary_no AND (xx.remarks = 'FDR -> AOR' OR xx.remarks = 'FDR -> SCR')", "left")
							->join("fil_trap_his AS ww", "ww.diary_no = zz.diary_no AND (ww.remarks = 'FDR -> AOR' OR ww.remarks = 'FDR -> SCR')", "left")
							->join("main AS mn", "mn.diary_no = zz.diary_no", "left")
							->join('"master.users" AS u', "u.empid = zz.d_to_empid AND u.display = 'Y'", "inner")
							->join("fil_trap_users AS t_u", "u.usercode = t_u.usercode AND t_u.usertype = '108' AND t_u.display = 'Y'", "inner")
							->where("(xx.diary_no IS NULL AND ww.diary_no IS NULL AND (mn.fil_no IS NULL OR mn.fil_no = ''))")
							->orderBy("zz.disp_dt, zz.d_by_empid")
							->get();
 
					return  $builder->getResultArray();
				
			}elseif($case=='sptotref'){
					
					$subQuery = "(SELECT diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid 
								  FROM fil_trap 
								  WHERE remarks = 'AOR -> FDR' AND disp_dt >= '2018-06-30'";

					if ($row_id != '0') {
						$subQuery .= " AND r_by_empid = '$emp_id'"; 
					}       

					$subQuery .= " UNION ALL 
								  SELECT diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid 
								  FROM fil_trap_his 
								  WHERE remarks = 'AOR -> FDR' AND disp_dt >= '2018-06-30'";

					if ($row_id != '0') {
						$subQuery .= " AND r_by_empid = '$emp_id'"; 
					}       

					$subQuery .= ") as zz";

					// Now, using the Query Builder with the fixed syntax
					$builder = $this->db->table($subQuery)
						->select("zz.diary_no, zz.d_to_empid, zz.r_by_empid, zz.disp_dt, zz.d_by_empid,
								  COALESCE(CAST(xx.diary_no AS TEXT), '') as d_no, 
								  COALESCE(CAST(ww.diary_no AS TEXT), '') as d_no2, 
								  mn.fil_no, 
								  CASE 
									 WHEN STRING_AGG(xx.rece_dt::TEXT, ',' ORDER BY xx.rece_dt) IS NULL 
									 THEN STRING_AGG(ww.rece_dt::TEXT, ',' ORDER BY ww.rece_dt) 
									 ELSE STRING_AGG(xx.rece_dt::TEXT, ',' ORDER BY xx.rece_dt) 
								  END as rece_dt")
						->join("fil_trap xx", "xx.diary_no = zz.diary_no AND xx.remarks = 'FDR -> SCR' AND xx.disp_dt >= zz.disp_dt", "left")
						->join("fil_trap_his ww", "ww.diary_no = zz.diary_no AND ww.remarks = 'FDR -> SCR' AND ww.disp_dt >= zz.disp_dt", "left")
						->join("main mn", "mn.diary_no = zz.diary_no", "left")
						->join("master.users u", "u.empid = zz.d_to_empid AND u.display = 'Y'", "inner")
						->join("fil_trap_users t_u", "u.usercode = t_u.usercode AND t_u.usertype = '108' AND t_u.display = 'Y'", "inner")
						->where("(xx.diary_no IS NULL AND ww.diary_no IS NULL AND (mn.fil_no IS NULL OR mn.fil_no = ''))")
						->groupBy("zz.diary_no, zz.d_to_empid, zz.r_by_empid, zz.disp_dt, zz.d_by_empid, 
								   xx.diary_no, ww.diary_no, mn.fil_no")
						->orderBy("zz.disp_dt, zz.d_by_empid")
						->get();

					return $builder->getResultArray();
			}elseif($case=='sptotpenr'){
					$subQuery1 = $this->db->table('fil_trap')
						->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
						->where("rece_dt >=", $frm_dt)
						->where("rece_dt <=", $to_dt)
						->where('remarks', 'SCR -> FDR')
						->where('r_by_empid !=', 0);
					if ($row_id != '0') {
							$subQuery1->where('r_by_empid', $emp_id);
					}
					
					$subQuery1 = $subQuery1->getCompiledSelect();

					$subQuery2 = $this->db->table('fil_trap_his')
						->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
						->where("rece_dt >=", $frm_dt)
						->where("rece_dt <=", $to_dt)
						->where('remarks', 'SCR -> FDR')
						->where('r_by_empid !=', 0);
					if ($row_id != '0') {
							$subQuery2->where('r_by_empid', $emp_id);
					}	
					$subQuery2 = $subQuery2->getCompiledSelect();

					$subQuery = "($subQuery1 UNION ALL $subQuery2) as zz";

					$builder = $this->db->table($subQuery)
						->select('zz.*')
						->join('master.users u', 'u.empid = zz.r_by_empid AND u.display = \'Y\'', 'inner')
						->join('fil_trap_users t_u', 'u.usercode = t_u.usercode AND t_u.usertype = \'108\' AND t_u.display = \'Y\'', 'inner')
						->orderBy('zz.disp_dt, zz.d_by_empid')
						->get();

					return $builder->getResultArray();
				
			}elseif($case=='sptwd'){
				$subQuery1 = $this->db->table('fil_trap')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
					->where("comp_dt >=", $frm_dt)
					->where("comp_dt <=", $to_dt)
					->where('remarks', 'SCR -> FDR')
					->where('r_by_empid !=', 0);
					if ($row_id != '0') {
						$subQuery1->where('r_by_empid', $emp_id);
					}
					
				$subQuery1 = $subQuery1->getCompiledSelect();


				$subQuery2 = $this->db->table('fil_trap_his')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
					->where("comp_dt >=", $frm_dt)
					->where("comp_dt <=", $to_dt)
					->where('remarks', 'SCR -> FDR')
					->where('r_by_empid !=', 0);
					if ($row_id != '0') {
						$subQuery2->where('r_by_empid', $emp_id);
					}	
				$subQuery2 = $subQuery2->getCompiledSelect();

				$subQuery = "($subQuery1 UNION ALL $subQuery2) as zz";

				$builder = $this->db->table($subQuery)
					->select('zz.*, 
							  xx.diary_no as d_no, 
							  ww.diary_no as d_no2, 
							  mn.fil_no, 
							  COALESCE(xx.remarks, ww.remarks) as remarks, 
							  COALESCE(xx.d_to_empid, ww.d_to_empid) as d_d_to_empid, 
							  COALESCE(xx.disp_dt, ww.disp_dt) as d_disp_dt')
					->join('fil_trap xx', "xx.diary_no = zz.diary_no AND (xx.remarks = 'FDR -> AOR' OR xx.remarks = 'FDR -> SCR')", 'left')
					->join('fil_trap_his ww', "ww.diary_no = zz.diary_no AND (ww.remarks = 'FDR -> AOR' OR ww.remarks = 'FDR -> SCR')", 'left')
					->join('main mn', 'mn.diary_no = zz.diary_no', 'left')
					->join('master.users u', "u.empid = zz.r_by_empid AND u.display = 'Y'", 'inner')
					->join('fil_trap_users t_u', "u.usercode = t_u.usercode AND t_u.usertype = '108' AND t_u.display = 'Y'", 'inner')
					->where('(xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL OR mn.fil_no IS NOT NULL)')
					->orderBy('zz.disp_dt, zz.d_by_empid')
					->get();

				return $builder->getResultArray();
				
			}elseif($case=='sptwdr'){
				$subQuery1 = $this->db->table('fil_trap')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid')
					->where('remarks', 'AOR -> FDR')
					->where('r_by_empid !=', 0)
					->where("rece_dt >=", $frm_dt)
					->where("rece_dt <=", $to_dt);
				if ($row_id != '0') {
					$subQuery1->where('r_by_empid', $emp_id);
				}
					
				$subQuery1 = $subQuery1->getCompiledSelect();

				$subQuery2 = $this->db->table('fil_trap_his')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid')
					->where('remarks', 'AOR -> FDR')
					->where('r_by_empid !=', 0)
					->where("rece_dt >=", $frm_dt)
					->where("rece_dt <=", $to_dt);
				if ($row_id != '0') {
					$subQuery2->where('r_by_empid', $emp_id);
				}	
				$subQuery2 = $subQuery2->getCompiledSelect();	
				
				$subQuery = "($subQuery1 UNION ALL $subQuery2) as zz";


				$builder = $this->db->table($subQuery)
					->distinct()
					->select('zz.*')
					->join('master.users u', "u.empid = zz.r_by_empid AND u.display = 'Y'", 'inner')
					->join('fil_trap_users t_u', "u.usercode = t_u.usercode AND t_u.usertype = '108' AND t_u.display = 'Y'", 'inner')
					->orderBy('zz.disp_dt, zz.d_by_empid')
					->get();

				return $builder->getResultArray();
				
			}elseif($case=='sptwdd'){
					$subQuery1 = $this->db->table('fil_trap')
						->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid')
						->where('remarks', 'AOR -> FDR')
						->where('r_by_empid !=', 0)
						->where("comp_dt >=", $frm_dt)
						->where("comp_dt <=", $to_dt);
				if ($row_id != '0') {
					 $subQuery1->where('r_by_empid', $emp_id);
				}
					
				$subQuery1 = $subQuery1->getCompiledSelect();

				$subQuery2 = $this->db->table('fil_trap_his')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid')
					->where('remarks', 'AOR -> FDR')
					->where('r_by_empid !=', 0)
					->where("comp_dt >=", $frm_dt)
					->where("comp_dt <=", $to_dt);
				if ($row_id != '0') {
					$subQuery2->where('r_by_empid', $emp_id);
				}	
				$subQuery2 = $subQuery2->getCompiledSelect();	

				$subQuery = "($subQuery1 UNION ALL $subQuery2) as zz";

				$builder = $this->db->table($subQuery)
					->distinct()
					->select("
						zz.diary_no, zz.d_to_empid, zz.r_by_empid, zz.disp_dt, zz.d_by_empid, 
						COALESCE(CAST(xx.diary_no AS TEXT), '') AS d_no, 
						COALESCE(CAST(ww.diary_no AS TEXT), '') AS d_no2, 
						mn.fil_no, 
						COALESCE(
							STRING_AGG(xx.rece_dt::TEXT, ',' ORDER BY xx.rece_dt),
							STRING_AGG(ww.rece_dt::TEXT, ',' ORDER BY ww.rece_dt)
						) AS rece_dt
					")
					->join('fil_trap xx', "xx.diary_no = zz.diary_no AND xx.remarks = 'FDR -> SCR' AND xx.disp_dt >= zz.disp_dt", 'left')
					->join('fil_trap_his ww', "ww.diary_no = zz.diary_no AND ww.remarks = 'FDR -> SCR' AND ww.disp_dt >= zz.disp_dt", 'left')
					->join('main mn', 'mn.diary_no = zz.diary_no', 'left')
					->join('master.users u', "u.empid = zz.r_by_empid AND u.display = 'Y'", 'inner')
					->join('fil_trap_users t_u', "u.usercode = t_u.usercode AND t_u.usertype = '108' AND t_u.display = 'Y'", 'inner')
					->where("(xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL OR mn.fil_no IS NOT NULL)")
					->groupBy('zz.diary_no, zz.d_to_empid, zz.r_by_empid, zz.disp_dt, zz.d_by_empid, xx.diary_no, ww.diary_no, mn.fil_no')
					->orderBy('zz.disp_dt, zz.d_by_empid')
					->get();
				return $builder->getResultArray();
			}
	}elseif($ddl_users=='9796'){
				if($case=='spallot'){
						$subQuery1 = $this->db->table('fil_trap')
							->select('diary_no, d_by_empid, d_to_empid, disp_dt, r_by_empid, rece_dt')
							->whereIn('remarks', ['TAG -> SCN', 'CAT -> SCN'])
							->where("DATE(disp_dt) >=", $frm_dt)
							->where("DATE(disp_dt) <=", $to_dt)
							->getCompiledSelect();
					
					$subQuery2 = $this->db->table('fil_trap_his')
						->select('diary_no, d_by_empid, d_to_empid, disp_dt, r_by_empid, rece_dt')
						->whereIn('remarks', ['TAG -> SCN', 'CAT -> SCN'])
						->where("DATE(disp_dt) >=", $frm_dt)
						->where("DATE(disp_dt) <=", $to_dt)
						->getCompiledSelect();
					
					$subQuery = "($subQuery1 UNION ALL $subQuery2) as zz";


					$builder = $this->db->table($subQuery)
						->distinct()
						->select('zz.*')
						->orderBy('zz.disp_dt, zz.d_by_empid')
						->get();

					return $builder->getResultArray();
					
			}elseif($case=='spcomp'){
	
				$subQuery1 = $this->db->table('fil_trap')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
					->whereIn('remarks', ['TAG -> SCN', 'CAT -> SCN'])
					->where('r_by_empid !=', 0)
					->where("DATE(disp_dt) >=", $frm_dt)
					->where("DATE(disp_dt) <=", $to_dt)
					->getCompiledSelect();
				
				$subQuery2 = $this->db->table('fil_trap_his')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
					->whereIn('remarks', ['TAG -> SCN', 'CAT -> SCN'])
					->where('r_by_empid !=', 0)
					->where("DATE(disp_dt) >=", $frm_dt)
					->where("DATE(disp_dt) <=", $to_dt)
					->getCompiledSelect();
				
				$subQuery = "($subQuery1 UNION ALL $subQuery2) as zz";


				$builder = $this->db->table($subQuery)
					->distinct()
					->select('zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2')
					->join('fil_trap xx', "xx.diary_no = zz.diary_no AND xx.remarks = 'SCN -> IB-Ex'", 'left')
					->join('fil_trap_his ww', "ww.diary_no = zz.diary_no AND ww.remarks = 'SCN -> IB-Ex'", 'left')
					->where('(xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL)')
					->orderBy('zz.disp_dt, zz.d_by_empid')
					->get();

				return $builder->getResultArray();
				
			}elseif($case=='spnotcomp'){

				$subQuery1 = $this->db->table('fil_trap')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
					->whereIn('remarks', ['TAG -> SCN', 'CAT -> SCN'])
					->where("DATE(disp_dt) >=", $frm_dt)
					->where("DATE(disp_dt) <=", $to_dt)
					->getCompiledSelect();
				


				$subQuery2 = $this->db->table('fil_trap_his')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
					->whereIn('remarks', ['TAG -> SCN', 'CAT -> SCN'])
					->where("DATE(disp_dt) >=", $frm_dt)
					->where("DATE(disp_dt) <=", $to_dt)
					->getCompiledSelect();
				
				$subQuery = "($subQuery1 UNION ALL $subQuery2) as zz";

				$builder = $this->db->table($subQuery)
					->distinct()
					->select('zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2')
					->join('fil_trap xx', "xx.diary_no = zz.diary_no AND xx.remarks = 'SCN -> IB-Ex'", 'left')
					->join('fil_trap_his ww', "ww.diary_no = zz.diary_no AND ww.remarks = 'SCN -> IB-Ex'", 'left')
					->where('xx.diary_no IS NULL')
					->where('ww.diary_no IS NULL')
					->orderBy('zz.disp_dt, zz.d_by_empid')
					->get();

				return $builder->getResultArray();
				
			}elseif($case=='sptotpen'){
				
				$subQuery1 = $this->db->table('fil_trap')
					->select('diary_no, d_to_empid, r_by_empid, d_by_empid, disp_dt')
					->whereIn('remarks', ['TAG -> SCN', 'CAT -> SCN'])
					->where('disp_dt >=', '2018-06-30')
					->getCompiledSelect();
				
				$subQuery2 = $this->db->table('fil_trap_his')
					->select('diary_no, d_to_empid, r_by_empid, d_by_empid, disp_dt')
					->whereIn('remarks', ['TAG -> SCN', 'CAT -> SCN'])
					->where('disp_dt >=', '2018-06-30')
					->getCompiledSelect();
				
				$subQuery = "($subQuery1 UNION ALL $subQuery2) as zz";

				$builder = $this->db->table($subQuery)
					->distinct()
					->select('zz.*, xx.diary_no AS d_no, ww.diary_no AS d_no2')
					->join('fil_trap xx', "xx.diary_no = zz.diary_no AND xx.remarks = 'SCN -> IB-Ex'", 'left')
					->join('fil_trap_his ww', "ww.diary_no = zz.diary_no AND ww.remarks = 'SCN -> IB-Ex'", 'left')
					->join('master.users u', "u.empid = zz.d_to_empid AND u.display = 'Y'", 'inner')
					->join('fil_trap_users t_u', "u.usercode = t_u.usercode AND t_u.usertype = '9796' AND t_u.display = 'Y'", 'inner')
					->where('xx.diary_no IS NULL')
					->where('ww.diary_no IS NULL')
					->orderBy('zz.disp_dt, zz.d_by_empid')
					->get();

				return $builder->getResultArray();
				
			}elseif($case=='sptotpenr'){
				
				$subQuery1 = $this->db->table('fil_trap')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
					->whereIn('remarks', ['TAG -> SCN', 'CAT -> SCN'])
					->where('r_by_empid !=', 0)
					->where("DATE(rece_dt) >=", $frm_dt)
					->where("DATE(rece_dt) <=", $to_dt)
					->getCompiledSelect();


				$subQuery2 = $this->db->table('fil_trap_his')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
					->whereIn('remarks', ['TAG -> SCN', 'CAT -> SCN'])
					->where('r_by_empid !=', 0)
					->where("DATE(rece_dt) >=", $frm_dt)
					->where("DATE(rece_dt) <=", $to_dt)
					->getCompiledSelect();


				$subQuery = "($subQuery1 UNION ALL $subQuery2) as zz";

				$builder = $this->db->table($subQuery)
					->distinct()
					->select('zz.*')
					->join('master.users u', "u.empid = zz.r_by_empid AND u.display = 'Y'", 'inner')
					->join('fil_trap_users t_u', "u.usercode = t_u.usercode AND t_u.usertype = '9796' AND t_u.display = 'Y'", 'inner')
					->orderBy('zz.disp_dt, zz.d_by_empid')
					->get();

				return $builder->getResultArray();
				
			}elseif($case=='sptwd'){
				$subQuery1 = $this->db->table('fil_trap')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
					->whereIn('remarks', ['TAG -> SCN', 'CAT -> SCN'])
					->where('r_by_empid !=', 0)
					->where("DATE(comp_dt) >=", $frm_dt)
					->where("DATE(comp_dt) <=", $to_dt)
					->getCompiledSelect();


				$subQuery2 = $this->db->table('fil_trap_his')
					->select('diary_no, d_to_empid, r_by_empid, disp_dt, d_by_empid, rece_dt')
					->whereIn('remarks', ['TAG -> SCN', 'CAT -> SCN'])
					->where('r_by_empid !=', 0)
					->where("DATE(comp_dt) >=", $frm_dt)
					->where("DATE(comp_dt) <=", $to_dt)
					->getCompiledSelect();


				$subQuery = "($subQuery1 UNION ALL $subQuery2) as zz";

				$builder = $this->db->table($subQuery)
					->distinct()
					->select([
						'zz.*',
						'COALESCE(xx.diary_no, NULL) AS d_no',
						'COALESCE(ww.diary_no, NULL) AS d_no2',
						'COALESCE(xx.remarks, ww.remarks) AS remarks',
						'COALESCE(xx.d_to_empid, ww.d_to_empid) AS d_d_to_empid',
						'COALESCE(xx.disp_dt, ww.disp_dt) AS d_disp_dt'
					])
					->join('fil_trap xx', "xx.diary_no = zz.diary_no AND xx.remarks = 'SCN -&gt; IB-Ex'", 'left')
					->join('fil_trap_his ww', "ww.diary_no = zz.diary_no AND ww.remarks = 'SCN -&gt; IB-Ex'", 'left')
					->join('master.users u', "u.empid = zz.r_by_empid AND u.display = 'Y'", 'inner')
					->join('fil_trap_users t_u', "u.usercode = t_u.usercode AND t_u.usertype = '9796' AND t_u.display = 'Y'", 'inner')
					->where('(xx.diary_no IS NOT NULL OR ww.diary_no IS NOT NULL)', null, false)
					->orderBy('zz.disp_dt, zz.d_by_empid')
					->get();

				return $builder->getResultArray();
           }
	}
}
 


}