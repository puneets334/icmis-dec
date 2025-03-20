<?php

namespace App\Models\Filing;

use CodeIgniter\Model;

class TaggingModel extends Model
{


    public function __construct()
    {
        $this->db = db_connect();

    }


    public function getDiaryDetails($diary_no,$page,$is_archived_flag)
    {
        $builder = $this->db->table('main'.$is_archived_flag.' as m');
        $builder->select("m.diary_no, m.pet_name, m.res_name, m.pet_adv_id AS pet_adv, m.res_adv_id AS res_adv, m.c_status, m.lastorder,case when (m.reg_no_display is null or m.reg_no_display = '') then mc.short_description else m.reg_no_display end as reg_no_display, TO_CHAR(active_fil_dt, 'DD-MM-YYYY') as active_fil_dt");
        if($page == 'S'){
            $builder->select("(case when (m.conn_key !='' AND m.conn_key IS NOT NULL) then 'Y' else'N' end ) AS ccdet", false);
        }else{
            $builder->select('(CASE
                   WHEN (m.conn_key IS NOT NULL AND m.conn_key != \'0\' AND m.conn_key != \'\') THEN
                       CASE
                           WHEN CAST(m.conn_key AS BIGINT) = m.diary_no THEN \'N\'
                           ELSE \'Y\'
                       END
                   ELSE
                       \'NA\'
                   END) AS ccdet', false);
        }

        $builder->select('m.conn_key AS connto');
        $builder->join('master.casetype mc', 'mc.casecode = m.casetype_id', 'left');
        $builder->where('m.diary_no', $diary_no);

        $query = $builder->get();
       /* echo $this->db->getLastQuery();
        exit;*/
        if ($query->getNumRows() >= 1) {
            return $result = $query->getRowArray();
        } else {
            return 0;
        }

    }           
    public function getAllConnectedCases($diary_no,$is_archived_flag)
    {
        $builder = $this->db->table('main'.$is_archived_flag.' as m');
        $builder->select(" CAST(LEFT(CAST(m.diary_no AS TEXT), -4) AS INTEGER) AS dn,CAST( RIGHT(CAST(m.diary_no AS TEXT), 4) AS INTEGER) AS dy,m.diary_no, cc.list, m.pet_name, m.res_name, m.c_status, cc.conn_type,case when (m.reg_no_display is null or m.reg_no_display = '') then mc.short_description else m.reg_no_display end as reg_no_display,TO_CHAR(active_fil_dt, 'DD-MM-YYYY') as active_fil_dt");
        $builder->join('conct'.$is_archived_flag.' as cc', 'cc.conn_key = ' . $diary_no . ' AND cc.diary_no = m.diary_no', 'left');
        $builder->join('master.casetype mc', 'mc.casecode = m.casetype_id', 'left');
        $builder->where('m.conn_key', $diary_no);
        $builder->where('m.conn_key !=', 'm.diary_no::text', false);
        $builder->orderBy('dy, dn');
        $query = $builder->get();
        //echo $this->db->getLastQuery();
        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return 0;
        }

    }
    //need to change
    public function getConnectedCases($diary_no,$is_archived_flag)
    {
      /*  $builder = $this->db->table("main as m");
        $builder->select('m.diary_no');
        $builder->select(" CAST(LEFT(CAST(m.diary_no AS TEXT), -4) AS INTEGER) AS dn,CAST( RIGHT(CAST(m.diary_no AS TEXT), 4) AS INTEGER) AS dy,case when (m.reg_no_display is null or m.reg_no_display = '') then mc.short_description else m.reg_no_display end as reg_no_display,TO_CHAR(active_fil_dt, 'DD-MM-YYYY') as active_fil_dt");
        $builder->select("(SELECT CONCAT(cc.list, '-', cc.conn_type) FROM conct cc WHERE cc.diary_no = m.diary_no ORDER BY cc.list DESC LIMIT 1) AS llist");
        $builder->join('master.casetype mc', 'mc.casecode = m.casetype_id', 'left');
        $builder->where("(m.diary_no = $diary_no OR m.conn_key IN (SELECT conn_key FROM main WHERE diary_no = $diary_no))");
        $builder->where("(m.diary_no::text) != m.conn_key");
        $builder->orderBy('dy, dn');*/

        $builder = $this->db->table("main".$is_archived_flag." as m");
        $builder->select('m.diary_no');
        $builder->select(" CAST(LEFT(CAST(m.diary_no AS TEXT), -4) AS INTEGER) AS dn,CAST( RIGHT(CAST(m.diary_no AS TEXT), 4) AS INTEGER) AS dy,case when (m.reg_no_display is null or m.reg_no_display = '') then mc.short_description else m.reg_no_display end as reg_no_display,TO_CHAR(active_fil_dt, 'DD-MM-YYYY') as active_fil_dt");
        $builder->select("(SELECT CONCAT(cc.list, '-', cc.conn_type) FROM conct".$is_archived_flag." cc WHERE cc.diary_no = m.diary_no ORDER BY cc.list DESC LIMIT 1) AS llist");
        $builder->join('master.casetype mc', 'mc.casecode = m.casetype_id', 'left');
        
         

        $connKeyQuery = $this->db->table("main".$is_archived_flag)
            ->select("conn_key")
            ->where("diary_no", $diary_no)            
            ->where("conn_key != '0'")
            ->orWhere("conn_key != NULL")
            ->get();
           
        if ($connKeyQuery->getNumRows() > 0) {
            $builder->groupStart();
            $builder->where("m.diary_no", $diary_no);
            $builder->orWhere("m.conn_key IN (SELECT conn_key FROM main".$is_archived_flag." WHERE diary_no = $diary_no)");
            $builder->groupEnd();
        }else{
            $builder->where("m.diary_no", $diary_no);
        }  
        
        //$builder->where("(m.diary_no = $diary_no OR m.conn_key IN (SELECT conn_key FROM main".$is_archived_flag." WHERE diary_no = $diary_no))");
        $builder->where("(m.diary_no::text) != m.conn_key");
        $builder->orderBy('dy, dn');
        //echo $queryString = $builder->getCompiledSelect();
       // echo $queryString;
        //echo $this->db->getLastQuery();
      // die();
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return 0;
        }

    }



    public function getAdvocateDetails($diary_no)
    {
        $builder = $this->db->table('main m');
        $builder->select('m.diary_no, cc.list, m.pet_name, m.res_name, m.c_status, cc.conn_type,m.reg_no_display');
        $builder->join('conct cc', 'cc.conn_key = ' . $diary_no . ' AND cc.diary_no = m.diary_no', 'left');
        $builder->where('m.conn_key', $diary_no);
        $builder->where('m.conn_key !=', 'm.diary_no');
        $builder->orderBy('m.fil_dt');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return 0;
        }

    }


}


?>