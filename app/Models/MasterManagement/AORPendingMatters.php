<?php

namespace App\Models\MasterManagement;
use CodeIgniter\Model;

class AORPendingMatters extends Model
{

    protected $table = 'advocate';

    protected $primaryKey = 'diary_no';
    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
    }

    public function getPendingMatters(){    
    $builder = $this->db->table('master.bar');
    $builder->select('aor_code');
    $builder->select('CONCAT(title, \' \', name) AS adv_name');
    $builder->where('isdead', 'N');
    $builder->where('if_aor', 'Y');
    $builder->where('if_sen', 'N');
    $builder->orderBy('aor_code');
    $query = $builder->get();
    return $query->getResultArray();
    }


      public function getCaseType(){    
      $builder = $this->db->table('master.casetype');
      $builder->select('casecode');
      $builder->select('skey');
      $builder->select('casename');
      $builder->select('short_description');
      $builder->where('display', 'Y');
      $builder->where('casecode !=', 9999);
      $builder->whereNotIn('casecode', [9999, 15, 16]);      
      $builder->orderBy('casecode');
      $builder->orderBy('short_description');
      $query = $builder->get();
      return $query->getResultArray();
      }

      public function getCases($bar_id, $from_dt1 = null, $from_dt2 = null, $status = null, $caseType = null)
      {
          $builder = $this->db->table('advocate a')
              ->select("CONCAT(SUBSTRING(CAST(a.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(a.diary_no AS TEXT)) - 4), '/', SUBSTRING(CAST(a.diary_no AS TEXT) FROM LENGTH(CAST(a.diary_no AS TEXT)) - 3 FOR 4)) AS Diary_no")
              ->select("CONCAT(reg_no_display, ' @ ', CONCAT(SUBSTRING(CAST(a.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(a.diary_no AS TEXT)) - 4), '/', SUBSTRING(CAST(a.diary_no AS TEXT) FROM LENGTH(CAST(a.diary_no AS TEXT)) - 3 FOR 4))) AS No")
              ->select("SUBSTRING(CAST(a.diary_no AS TEXT) FROM LENGTH(CAST(a.diary_no AS TEXT)) - 3 FOR 4) AS dyear")
              ->select("CONCAT(pet_name, ' VS ', res_name) AS Causetitle")
              ->select("CASE WHEN h.conn_key = 0 THEN 'MAIN' WHEN h.diary_no = h.conn_key THEN 'Main' ELSE 'Connected' END AS Main_Connected")
              ->select("CASE WHEN mainhead = 'M' THEN 'MISC' ELSE 'FINAL' END AS Misc_Regular")
              ->select("CASE WHEN h.main_supp_flag = 0 THEN 'READY' WHEN h.main_supp_flag = 3 THEN 'NOT READY' ELSE 'OTHERS' END AS Ready_NotReady")
              ->select("d.section_name")
              ->select("name")
              ->select("CASE WHEN b.c_status = 'P' THEN 'Pending' ELSE 'Disposed' END AS status")
              ->join('main b', 'a.diary_no = b.diary_no', 'inner')
              ->join('master.users c', 'c.usercode = b.dacode AND c.display = \'Y\'', 'left')
              ->join('heardt h', 'h.diary_no = b.diary_no', 'left')
              ->join('master.usersection d', 'd.id = c.section AND d.display = \'Y\'', 'left')
              ->join('master.casetype e', 'e.casecode = CAST(SUBSTRING(b.fil_no FROM 1 FOR 2) AS INTEGER) AND e.display = \'Y\'', 'left')
              ->where('a.advocate_id', $bar_id)
              ->where('a.display', 'Y');
      
          if ($from_dt1 && $from_dt2) {
              $builder->where('DATE(b.diary_no_rec_date) >=', $from_dt1)
                  ->where('DATE(b.diary_no_rec_date) <=', $from_dt2);
          }
      
          if ($status) {
              $builder->where('b.c_status', $status);
          }
      
          if ($caseType) {
              $builder->where("COALESCE(b.active_casetype_id, b.casetype_id) = ", $caseType);
          }
      
          $builder->orderBy('d.section_name')
              ->orderBy('name')
              ->orderBy('dyear')
              ->orderBy('a.diary_no');
        //   pr($builder->getCompiledSelect());
          // Debugging: Print the SQL query
        //   echo $this->db->getLastQuery();
      
          return $builder->get()->getResultArray();
      }
      
      
      public function getAorName($aor)
      {
          return $this->db->table('master.bar')->where('aor_code', $aor)->get()->getRow();
      }


      public function getStates()
      {
            $builder = $this->db->query("
                SELECT State_code, Name, id_no
                FROM master.state
                WHERE District_code = 0
                AND Sub_Dist_code = 0
                AND Village_code = 0
                AND display = 'Y'
                AND State_code < 100
                ORDER BY Name
            ");
          return $builder->getResultArray();
      }
      

      public function checkRecordExists($enroll_no, $year, $state_id)
      {
        // pr($year);
        $builder = $this->db->table('master.bar');
        $builder->where('enroll_no', $enroll_no);
        $builder->where("EXTRACT(YEAR FROM enroll_date::DATE)", $year);
        $builder->where('state_id', $state_id);
        $query = $builder->get();
        return $query->getRow();
      }

      public function getNextAORCode()
      {
        $builder = $this->db->table('master.bar');
        //   $builder->select('MAX(aor_code) + 1 AS code');
        //   $builder->where('if_aor', 'Y');
        //   $builder->whereNotIn('aor_code', [4075]);
        //   $builder->orderBy('aor_code', 'DESC');
        //   $query = $builder->get();
        //   return $query->getRow()->code;
        $builder->select('COALESCE(MAX(aor_code), 0) + 1 AS code');
        $builder->where('if_aor', 'Y');
        $builder->whereNotIn('aor_code', [4075]);
        $query = $builder->get();
        $result = $query->getRow();
        return $result ? $result->code : 1; 
      }


      
      public function getDetails($state, $enroll, $year, $aor)
      {
          $builder = $this->db->table('master.bar');
          
          if ($aor === '') {
                $builder->where('state_id', $state);
                $builder->where('enroll_no', $enroll);
                $builder->where("EXTRACT(YEAR FROM enroll_date)", $year);
          } else {
              $builder->where('aor_code', $aor);
          }
          $query = $builder->get()->getRowArray(); 
          return $query;
      }


     public function getFullDetails($stateId = null, $enrollNo = null, $year = null, $aor = null)
      {
         $builder = $this->db->table('master.bar');
        if ($aor === '') {
            $builder = $this->barModel->builder();
            $builder->where('state_id', $stateId)
                    ->where('enroll_no', $enrollNo)
                    ->where('YEAR(enroll_date)', $year);
                    return $builder->get()->getRowArray();
        } else {
            $builder->where('aor_code', $aor);
            return $builder->get()->getRowArray();
        }
      }
      
      

 
        

  }
  
