<?php

namespace App\Models\Scanning\Scaned;

use CodeIgniter\Model;

class ScanedModel extends Model
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $db = \Config\Database::connect();
    }

    // Fetch court data
    public function getCourtData()
    {
        $builder = $this->db->table('master.m_from_court');
        $builder->select('id, court_name');
        $builder->where('display', 'Y');
        $builder->orderBy('order_by', 'ASC');
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    // Fetch state data
    public function getStateData()
    {
        $builder = $this->db->table('master.state');
        $builder->select('id_no, name');
        $builder->where('display', 'Y');
        $builder->orderBy('name', 'ASC');
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function getCaseTypes($ddl_st_agncy)
    {
        $builder = $this->db->table('master.ddl_st_agncy');
        $builder->select('lccasecode, type_sname');
        $builder->where('display', 'Y');
        $builder->where('cmis_state_id', $ddl_st_agncy);
        $builder->where('ref_agency_code_id', 0);
        $builder->where('corttyp', 'H');
        $builder->orderBy('type_sname');
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        }

        return false;
    }

    public function getRecordsBetweenDates($txt_fd, $txt_td)
    {
        $builder = $this->db->table('indexing a');
        $builder->select('a.*, b.docnum, b.docyear');

        // Join with docdetails table
        $builder->join('docdetails b', 'a.diary_no = b.diary_no AND a.src_of_ent = b.docd_id', 'inner');

        // Apply WHERE conditions
        $builder->where('a.display', 'Y');
        $builder->where('b.display', 'Y');
        $builder->where('a.src_of_ent !=', 0);

        // Date filtering using CAST function in PostgreSQL
        $builder->where('CAST(a.entdt AS DATE) >=', $txt_fd);
        $builder->where('CAST(a.entdt AS DATE) <=', $txt_td);

        // Order by diary_no and fp
        $builder->orderBy('a.diary_no', 'ASC');
        $builder->orderBy('a.fp', 'ASC');

        // Execute query
        $query = $builder->get();
        return $query->getResultArray();
    }

    //Diary No Details
    public function fetchDiaryDetails($txt_fd, $txt_td, $ddl_dt_type)
    {
        if ($ddl_dt_type == 1) {
            // Query for Registration Date
            $builder = $this->db->table('main a')
                                ->select('a.diary_no, 
                                          a.diary_no_rec_date, 
                                          SUBSTRING(a.fil_no FROM 4) as case_number, 
                                          SUBSTRING(a.fil_no FROM 1 FOR 2) as case_type, 
                                          EXTRACT(YEAR FROM a.fil_dt) as case_year, 
                                          CONCAT(a.pet_name, CASE WHEN a.pno > 1 THEN \' and others\' ELSE \'\' END) as pet_name, 
                                          CONCAT(a.res_name, CASE WHEN a.rno > 1 THEN \' and others\' ELSE \'\' END) as res_name, 
                                          b.name as petitioner_adv, 
                                          c.name as respondent_adv, 
                                          STRING_AGG(d.submaster_id::text, \',\') as subject_category')
                                ->join('master.bar b', 'a.pet_adv_id = b.bar_id', 'left')
                                ->join('master.bar c', 'a.res_adv_id = c.bar_id', 'left')
                                ->join('mul_category d', 'd.diary_no = a.diary_no', 'left')
                                ->where('a.fil_dt >=', $txt_fd)
                                ->where('a.fil_dt <=', $txt_td)
                                ->groupBy('a.diary_no, b.name, c.name');
        } else {
            // Query for Listing Date
            $builder = $this->db->table('heardt h')
                                ->select('a.diary_no, 
                                          a.diary_no_rec_date, 
                                          SUBSTRING(a.fil_no FROM 4) as case_number, 
                                          SUBSTRING(a.fil_no FROM 1 FOR 2) as case_type, 
                                          EXTRACT(YEAR FROM a.fil_dt) as case_year, 
                                          CONCAT(a.pet_name, CASE WHEN a.pno > 1 THEN \' and others\' ELSE \'\' END) as pet_name, 
                                          CONCAT(a.res_name, CASE WHEN a.rno > 1 THEN \' and others\' ELSE \'\' END) as res_name, 
                                          b.name as petitioner_adv, 
                                          c.name as respondent_adv, 
                                          STRING_AGG(d.submaster_id::text, \',\') as subject_category')
                                ->join('cl_printed cl', 'h.next_dt = cl.next_dt AND h.roster_id = cl.roster_id', 'inner')
                                ->join('main a', 'a.diary_no = h.diary_no', 'inner')
                                ->join('master.bar b', 'a.pet_adv_id = b.bar_id', 'left')
                                ->join('master.bar c', 'a.res_adv_id = c.bar_id', 'left')
                                ->join('mul_category d', 'd.diary_no = a.diary_no', 'left')
                                ->where('h.next_dt >=', $txt_fd)
                                ->where('h.next_dt <=', $txt_td)
                                ->groupBy('a.diary_no, b.name, c.name');
        }
    
        // Execute query and return result
        $query = $builder->get();
        return $query->getResultArray();
    }
    
}
