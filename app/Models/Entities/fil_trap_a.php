<?php

namespace App\Models\Entities;

use CodeIgniter\Model;

class fil_trap_a extends Model
{

  protected $table      = 'fil_trap';
  public function __construct()
  {
    parent::__construct();
    $this->db = db_connect();
  }

  // protected $primaryKey = '';
  public function getTrapData($date, $limit = 10, $offset = 0)
  {
    // First part of the UNION (from fil_trap table)
    $builder1 = $this->db->table('fil_trap a')
      ->select("
            a.diary_no, a.d_by_empid, a.d_to_empid, a.r_by_empid, 
            a.disp_dt, a.rece_dt, a.comp_dt, a.remarks, a.other, b.name,
            COUNT(*) AS pending,
            SUM(CASE WHEN DATE(a.comp_dt) = '$date' AND a.r_by_empid = b.empid THEN 1 ELSE 0 END) AS comp,
            SUM(CASE WHEN DATE(a.disp_dt) = '$date' AND a.d_to_empid = b.empid THEN 1 ELSE 0 END) AS sent
        ")
      ->join('master.users b', 'a.d_to_empid = b.empid', 'left')
      ->groupBy('a.diary_no, a.d_by_empid, a.d_to_empid, a.r_by_empid, a.disp_dt, a.rece_dt, a.comp_dt, a.remarks, a.other, b.name, b.empid');

    // Second part of the UNION (from fil_trap_his table)
    $builder2 = $this->db->table('fil_trap_his a')
      ->select("
            a.diary_no, a.d_by_empid, a.d_to_empid, a.r_by_empid, 
            a.disp_dt, a.rece_dt, a.comp_dt, a.remarks, a.other, b.name,
            0 AS pending,
            SUM(CASE WHEN DATE(a.comp_dt) = '$date' AND a.r_by_empid = b.empid THEN 1 ELSE 0 END) AS comp,
            SUM(CASE WHEN DATE(a.disp_dt) = '$date' AND a.d_to_empid = b.empid THEN 1 ELSE 0 END) AS sent
        ")
      ->join('master.users b', 'a.d_to_empid = b.empid', 'left')
      ->groupBy('a.diary_no, a.d_by_empid, a.d_to_empid, a.r_by_empid, a.disp_dt, a.rece_dt, a.comp_dt, a.remarks, a.other, b.name, b.empid');

    // Combine the two parts using UNION
    $sql = $builder1->getCompiledSelect() . " UNION " . $builder2->getCompiledSelect();

    // Final query with pagination
    $finalSql = "
        SELECT diary_no, d_by_empid, d_to_empid, r_by_empid, disp_dt, rece_dt, comp_dt, remarks, other, name,
              SUM(pending) AS pending, SUM(comp) AS comp, SUM(sent) AS sent
        FROM ($sql) AS combined
        GROUP BY diary_no, d_by_empid, d_to_empid, r_by_empid, disp_dt, rece_dt, comp_dt, remarks, other, name
        ORDER BY d_to_empid
        LIMIT $limit OFFSET $offset
    ";

    // Execute the final query
    $query = $this->db->query($finalSql);
    return $query->getResult();
  }

  public function get_filtrap_mon_data($date)
  {
    $query = "SELECT d_to_empid, name, SUM(sent) sent, SUM(comp) comp, SUM(pending) pending
              FROM (
                SELECT diary_no, d_by_empid, d_to_empid, r_by_empid, disp_dt, rece_dt, comp_dt, remarks, other, b.name,
                      COUNT(*) AS pending,
                      SUM(CASE WHEN DATE(comp_dt) = '$date' AND r_by_empid = empid THEN 1 ELSE 0 END) AS comp,
                      SUM(CASE WHEN DATE(disp_dt) = '$date' AND d_to_empid = empid THEN 1 ELSE 0 END) AS sent
                FROM fil_trap a
                LEFT JOIN master.users b ON d_to_empid = empid
                GROUP BY empid, a.diary_no, a.d_by_empid, a.d_to_empid, a.r_by_empid, a.disp_dt, a.rece_dt, a.comp_dt, a.remarks, a.other, b.name
                UNION ALL
                SELECT diary_no, d_by_empid, d_to_empid, r_by_empid, disp_dt, rece_dt, comp_dt, remarks, other, b.name,
                      0 AS pending,
                      SUM(CASE WHEN DATE(comp_dt) = '$date' AND r_by_empid = empid THEN 1 ELSE 0 END) AS comp,
                      SUM(CASE WHEN DATE(disp_dt) = '$date' AND d_to_empid = empid THEN 1 ELSE 0 END) AS sent
                FROM fil_trap_his a
                LEFT JOIN master.users b ON d_to_empid = empid
                GROUP BY empid, a.diary_no, a.d_by_empid, a.d_to_empid, a.r_by_empid, a.disp_dt, a.rece_dt, a.comp_dt, a.remarks, a.other, b.name
              ) a
              GROUP BY d_to_empid, name
              ORDER BY d_to_empid";
        $query = $this->db->query($query);
        $result = $query->getResultArray();
        return $result;
        
  }




  // The date reversion function inside the model

}
