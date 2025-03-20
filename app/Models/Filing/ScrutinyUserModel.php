<?php

namespace App\Models\Filing;

use CodeIgniter\Model;

class ScrutinyUserModel extends Model
{

  protected $table = 'main';
  protected $primaryKey = 'diary_no';

  protected $allowedFields = ['diary_no', 'fil_no', 'fil_no_fh', 'reg_year_mh', 'fil_dt', 'reg_year_fh', 'fil_dt_fh', 'new_registration_number', 'old_registration_number', 'is_deleted'];
  protected $returnType = 'array';
  protected $useSoftDeletes = false;

  public function getDiaryNo($ct, $cn, $cy)
  {
      // Validate and sanitize inputs
      $ct = !empty($ct) ? $this->db->escapeString($ct) : '';
      $cy = !empty($cy) ? intval($cy) : 0;
      $cn = !empty($cn) ? intval($cn) : 0;
  
      $builder = $this->db->table('main');
  
      $builder->select("
          CASE
              WHEN LENGTH(diary_no) > 4 THEN
                  SUBSTRING(diary_no FROM 1 FOR (LENGTH(diary_no) - 4))
              ELSE
                  diary_no
          END AS dn,
          CASE
              WHEN LENGTH(diary_no) > 4 THEN
                  SUBSTRING(diary_no FROM LENGTH(diary_no::text) - 3 FOR 4)
              ELSE
                  ''
          END AS dy
      ");
  
      $builder->where("
          (SUBSTRING(fil_no FROM 1 FOR POSITION('-' IN fil_no) - 1) = '$ct'
          AND CAST('$cn' AS INTEGER) BETWEEN 
              COALESCE(CAST(SUBSTRING(fil_no FROM POSITION('-' IN fil_no) + 1 FOR POSITION('-' IN fil_no) - 1) AS INTEGER), 0)
              AND COALESCE(CAST(SUBSTRING(fil_no FROM POSITION('-' IN fil_no) + POSITION('-' IN fil_no) + 1 FOR LENGTH(fil_no)) AS INTEGER), 0)
          AND (reg_year_mh = 0 OR DATE(fil_dt) > DATE '2017-05-10')
          AND (EXTRACT(YEAR FROM fil_dt) = $cy OR reg_year_mh = $cy))
          OR 
          (SUBSTRING(fil_no_fh FROM 1 FOR POSITION('-' IN fil_no_fh) - 1) = '$ct'
          AND CAST('$cn' AS INTEGER) BETWEEN 
              COALESCE(CAST(SUBSTRING(fil_no_fh FROM POSITION('-' IN fil_no_fh) + 1 FOR POSITION('-' IN fil_no_fh) - 1) AS INTEGER), 0)
              AND COALESCE(CAST(SUBSTRING(fil_no_fh FROM POSITION('-' IN fil_no_fh) + POSITION('-' IN fil_no_fh) + 1 FOR LENGTH(fil_no_fh)) AS INTEGER), 0)
          AND (reg_year_fh = 0 OR EXTRACT(YEAR FROM fil_dt_fh) = $cy OR reg_year_fh = $cy))
      ");
  
      $query = $builder->get();
  
      if ($query->getNumRows() > 0) {
          return $query->getRowArray();
      }
  
      return null;
  }
  
  public function getCauseTitle($dairy_no)
  {
    $builder = $this->db->table('main');
    $builder->select("TRIM(pet_name) AS pet_name, TRIM(res_name) AS res_name");
    $builder->where('diary_no', $dairy_no);
    $query = $builder->get();

    return $query->getResultArray();
  }

  public function getHistoryDiaryNo($ct, $cn, $cy)
  {
      // Validate and sanitize inputs
      $ct = !empty($ct) ? $this->db->escapeString($ct) : '';
      $cy = !empty($cy) ? intval($cy) : 0;
      $cn = !empty($cn) ? intval($cn) : 0;
  
      $builder = $this->db->table('main_casetype_history');
  
      $builder->select("
          CASE
              WHEN LENGTH(h.diary_no) > 4 THEN
                  SUBSTRING(h.diary_no FROM 1 FOR (LENGTH(h.diary_no) - 4))
              ELSE
                  h.diary_no
          END AS dn,
          CASE
              WHEN LENGTH(h.diary_no) > 4 THEN
                  SUBSTRING(h.diary_no FROM LENGTH(h.diary_no) - 3 FOR 4)
              ELSE
                  ''
          END AS dy,
          COALESCE(SUBSTRING(h.new_registration_number FROM 1 FOR POSITION('-' IN h.new_registration_number) - 1), '') AS ct1,
          COALESCE(SUBSTRING(h.new_registration_number FROM POSITION('-' IN h.new_registration_number) + 1 FOR LENGTH(h.new_registration_number)) , '') AS crf1,
          COALESCE(SUBSTRING(h.new_registration_number FROM POSITION('-' IN h.new_registration_number) + 1 FOR LENGTH(h.new_registration_number)), '') AS crl1
      ");
  
      $builder->where("
          (SUBSTRING(h.new_registration_number FROM 1 FOR POSITION('-' IN h.new_registration_number) - 1) = '$ct'
          AND CAST('$cn' AS INTEGER) BETWEEN 
              COALESCE(CAST(SUBSTRING(h.new_registration_number FROM POSITION('-' IN h.new_registration_number) + 1 FOR LENGTH(h.new_registration_number)) AS INTEGER), 0)
              AND COALESCE(CAST(SUBSTRING(h.new_registration_number FROM POSITION('-' IN h.new_registration_number) + POSITION('-' IN h.new_registration_number) + 1 FOR LENGTH(h.new_registration_number)) AS INTEGER), 0)
          AND h.new_registration_year = $cy)
          OR
          (SUBSTRING(h.old_registration_number FROM 1 FOR POSITION('-' IN h.old_registration_number) - 1) = '$ct'
          AND CAST('$cn' AS INTEGER) BETWEEN 
              COALESCE(CAST(SUBSTRING(h.old_registration_number FROM POSITION('-' IN h.old_registration_number) + 1 FOR LENGTH(h.old_registration_number)) AS INTEGER), 0)
              AND COALESCE(CAST(SUBSTRING(h.old_registration_number FROM POSITION('-' IN h.old_registration_number) + POSITION('-' IN h.old_registration_number) + 1 FOR LENGTH(h.old_registration_number)) AS INTEGER), 0)
          AND h.old_registration_year = $cy)
      ");
  
      $builder->where('h.is_deleted', 'f');
  
      $query = $builder->get();
  
      if ($query->getNumRows() > 0) {
          return $query->getRowArray();
      }
  
      return null;
  }
  
  
}
