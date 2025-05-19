<?php

namespace App\Models\Filing;

use CodeIgniter\Model;

class LimitationModel extends Model
{
    protected $table = 'case_limit';
    protected $allowedFields = [
        'limit_days',
        'descr',
        'case_nature',
        'under_section',
        'o_s',
        'pol',
        'o_d',
        'f_d',
        'c_d_a',
        'd_o_d',
        'case_lim_display',
        'diary_no',
        'lowerct_id',
        'order_cof',
        'd_o_a',
        'case_lmt_user',
        'updated_on',
        'updated_by',
        'case_lmt_ent_dt',
        'updated_by_ip'
    ];

    public function __construct()
    {
        parent::__construct();
        $db = \Config\Database::connect();
        $this->db = db_connect();
    }
    public function getLegalCases($dairy_no)
    {

        $builder = $this->db->table("lowerct a");

        $builder->select('lct_dec_dt, l_dist, ct_code, l_state, "name"');
        $builder->select('CASE WHEN ct_code = 3 THEN (
    SELECT "name" FROM master.state s
    WHERE s.id_no = a.l_dist AND s.display = \'Y\' Limit 1
) ELSE (
    SELECT agency_name FROM master.ref_agency_code c
    WHERE c.cmis_state_id = a.l_state AND c.id = a.l_dist AND c.is_deleted = \'f\' Limit 1
) END AS agency_name', false);
        $builder->select('lct_casetype, lct_caseno, lct_caseyear');
        $builder->select('CASE WHEN ct_code = 4 THEN (
    SELECT skey FROM master.casetype ct
    WHERE ct.display = \'Y\' AND ct.casecode = a.lct_casetype Limit 1
) ELSE (
    SELECT type_sname FROM master.lc_hc_casetype d
    WHERE d.lccasecode = a.lct_casetype AND d.display = \'Y\' Limit 1
) END AS type_sname', false);
        $builder->select('a.lower_court_id, limit_days');

        $builder->join('master.state b', 'a.l_state = b.id_no AND b.display = \'Y\'', 'left');
        $builder->join('main e', 'e.diary_no = a.diary_no');
        $builder->join('case_limit cl', 'cl.lowerct_id = a.lower_court_id AND cl.case_lim_display = \'Y\'', 'left');
        $builder->where('a.diary_no', $dairy_no);
        $builder->where('a.lw_display', 'Y');
        $builder->where('a.is_order_challenged', 'Y');
        $builder->orderBy('a.lower_court_id');
        $builder->limit(10);
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }

    public function res_details($dairy_no)
    {
        $builder = $this->db->table("main");
        $builder->select('pet_name,res_name,casetype_id,diary_no_rec_date,actcode,nature');
        $builder->where('diary_no', $dairy_no);
        $query = $builder->get();
        $result = $query->getRowArray();
        return $result;
    }

    public function ch_cat($dairy_no)
    {
        $builder = $this->db->table("mul_category a");
        $builder->select('a.submaster_id');
        $builder->where('a.display', 'Y');
        $builder->where('diary_no', $dairy_no);
        $query = $builder->get();
        $sql = $this->db->getLastQuery();
        $result = $query->getResult();

        return $result;
    }

    public function chk_limi($submaster_id)
    {

        $currentDate = date('Y-m-d ');
        $builder = $this->db->table("master.m_limitation_period");
        $builder->select('order_by');
        $builder->where('display', 'Y');
        $builder->where('submaster_id', $submaster_id);
        $builder->where('case_law', 0);
        $builder->groupStart();
        $builder->where("'$currentDate'", 'BETWEEN', 'from_date', false);
        $builder->orWhere('from_date <=', $currentDate);
        $builder->orWhere('to_date IS NULL', null, false);
        $builder->groupEnd();
        $query = $builder->get();
        $sql = $this->db->getLastQuery();
        $result = $query->getResult();

        return $result;
    }

    public function insertCaseLimit($data)
    {
        $builder = $this->db->table('case_limit ');

        $data['case_lmt_ent_dt'] = date('Y-m-d H:i:s');
        return $this->insert($data);
    }

    public function getLatestData()
    {
        $builder = $this->db->table('case_limit');
        $builder->select('*');
        $builder->orderBy('id', 'DESC');
        $builder->limit(1);
        $query = $builder->get();

        return $query->getRow();
    }
    public function mul_cat($diary_no)
    {
        $builder = $this->db->table('mul_category');
        $builder->select('submaster_id');
        $builder->where('diary_no', $diary_no);

        $query = $builder->get();

        return $query->getRow();
    }
    public function getNature($diary_no)
    {
        $builder = $this->db->table('main');
        $builder->select('pet_name,res_name,casetype_id,diary_no_rec_date,actcode,nature');
        $builder->where('diary_no', $diary_no);
        $query = $builder->get();
        return $query->getRow();
    }
    public function get_court_type_list()
    {
        $builder = $this->db->table('master.m_from_court');
        $builder->select('court_name');
        $builder->where('display', 'Y');
        $query = $builder->get();
        return $query->getRow();
    }
    public function casename($nature)
    {
        $builder = $this->db->table('master.casetype');
        $builder->select('casename');
        $builder->where('display', 'Y');
        $builder->where('casecode', $nature);
        $query = $builder->get();
        return $query->getRow();
    }
    public function deleteRecord($id)
    {
        $builder = $this->db->table('case_limit');
        $builder->where('id', $id);
        $builder->update(['case_lim_display' => 'N']);
    }
    public function getcasestatus($diary_no)
    {
        $builder = $this->db->table('case_limit');
        $builder->select('*');
        $builder->where('diary_no', $diary_no);
        $query = $builder->get();
        return $query->getRow();
    }
    public function getcasestatuswithrows($diary_no)
    {
        $builder = $this->db->table('case_limit');
        $builder->select('*');
        $builder->where('diary_no', $diary_no);
        $query = $builder->get();

        $rows = $query->getResult();

        // Get the number of rows
        $numRows = count($rows);

        return $numRows;
    }


    public function getLimitationPeriod($nature)
    {
        // Check if $nature is empty
        if (empty($nature)) {
            return "Error: casetype_id cannot be empty.";
        }

        $builder = $this->db->table('master.m_limitation_period');
        $builder->select('category_subcode, category_subcode1, category_subcode2, limitation, order_cof');
        $builder->where('display', 'Y');
        $builder->orderBy('order_cof','desc');
        if (!empty($nature)) {
            $builder->where('casetype_id', $nature);
        }

        $builder->where('submaster_id', '0');
        $builder->where('case_law', '0');
        $builder->getCompiledSelect();
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getHolidays($dt, $chk_tot_days)
    {
        $builder = $this->db->table('master.sc_working_days');
        $builder->selectCount('working_date');
        $builder->where('working_date', $dt);
        $builder->where("holiday_for_registry = '1'");

        $query = $builder->get();
        $res_h = $query->getRow()->working_date;
        if ($res_h > 0) {
            if (strtotime($dt) == strtotime($chk_tot_days))
                return $dt;
            else
                $dt = date('Y-m-d', strtotime($dt . ' + 1 days'));

            return chksDate_vac_reg_add($dt, $chk_tot_days);
        } else {
            return $dt;
        }
    }
    public function chk_curr_dat_holiday($dt)
    {
        $builder = $this->db->table('master.sc_working_days');
        $builder->selectCount('working_date');
        $builder->where('working_date', $dt);
        $builder->where("holiday_for_registry = '1'");

        $query = $builder->get();
        $res_h = $query->getRow()->working_date;

        if ($res_h > 0) {

            $dt = date('Y-m-d', strtotime($dt . ' + 1 days'));


            return $dt;
        } else {
            return $dt;
        }
    }
    public function updateCaseLimitDisplay($id)
    {
        $builder = $this->db->table('case_limit ');
        $builder->where($this->primaryKey, $id);
        $builder->update();
        return $this->db->affectedRows() > 0;
    }

    public function getSubmasterId($diary_no)
    {
        $builder = $this->db->table('mul_category');
        $builder->select('submaster_id');
        $builder->where('display', 'Y');
        $builder->where('diary_no', $diary_no);
        

        $query = $builder->get();
        return $query->getRowArray();
    }

    public function getlimitation1($casetype_id, $submaster_id, $case_law)
    {

        $currentDate = date('Y-m-d ');
        $builder = $this->db->table("master.m_limitation_period");
        $builder->select('order_by');
        $builder->where('display', 'Y');
        $builder->where('casetype_id', $casetype_id);
        $builder->where('submaster_id', $submaster_id);
        $builder->where('case_law', $case_law);
        $builder->groupStart();
        $builder->where("'$currentDate'", 'BETWEEN', 'from_date', false);
        $builder->orWhere('from_date <=', $currentDate);
        $builder->orWhere('to_date IS NULL', null, false);
        $builder->groupEnd();
        $query = $builder->get();
        $sql = $this->db->getLastQuery();
        $result = $query->getResultArray();

        return $result;
    }

    public function getlimitation($casetype_id=0, $submaster_id=0, $case_law=0)
    {
        $return = [];
        $currentDate = date('Y-m-d');
        $sql = "SELECT order_by
                FROM master.m_limitation_period
                WHERE casetype_id = $casetype_id
                AND display = 'Y'
                AND submaster_id = $submaster_id
                AND case_law = $case_law
                AND (('$currentDate' BETWEEN from_date AND to_date) OR (from_date <= '$currentDate' AND to_date is null))";
                   //pr($sql);
         
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $return = $query->getRowArray();
        }
        return $return;
    }

    public function getCaseLimit($diary_no, $hd_lower_id)
    {
        $builder = $this->db->table('case_limit');
        $builder->select('*');
        $builder->where('diary_no', $diary_no);
        $builder->where('lowerct_id', $hd_lower_id);
        $builder->where('case_lim_display', 'Y');
        //pr($builder->getCompiledSelect());
        $query = $builder->get();
        return $query->getRowArray();
    }
    public function getcasestatuswithrows1($diary_no)
    {
        $builder = $this->db->table('case_limit');
        $builder->select('*');
        $builder->where('diary_no', $diary_no);
        $query = $builder->get();

        $rows = $query->getResult();

        // Get the number of rows
        $numRows = count($rows);

        return $numRows;
    }

    public function getCaseName($casetype_id)
    {
        $builder = $this->db->table('master.casetype');
        $builder->select('casename');
        $builder->where('display', 'Y');
        $builder->where('casecode', $casetype_id);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function getSubmasterDescription($cat_id)
    {   
        $return = '';
        $sql = "SELECT subject_description || '-' || category_description AS s
                FROM master.submaster
                WHERE id = $cat_id AND display = 'Y'";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $result = $query->getRowArray();
            $return = !empty($result['s']) ? $result['s'] : '';
        }
        return $return;
    }

    public function getCaseLow($actcode)
    {
        $builder = $this->db->table('master.caselaw');
        $builder->select('law');
        $builder->where('display', 'Y');
        $builder->where('id', $actcode);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function getCaseType($casetype_id)
    {
        $builder = $this->db->table('master.casetype');
        $builder->select('casename');
        $builder->where('display', 'Y');
        $builder->where('casecode', $casetype_id);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function getLimitationForClaim($category_code)
    {
        $return = [];
        if(!empty($category_code)){
            $sql = "SELECT category_subcode,category_subcode1,category_subcode2,limitation,order_cof
                FROM master.m_limitation_period
                WHERE display = 'Y'
                AND $category_code";
               //pr($sql);
            $query = $this->db->query($sql);
            if ($query->getNumRows() >= 1) {
                $return = $query->getResultArray();
            }
        }
        
        return $return;
    }

    public function getlimitation2($casetype_id=0, $submaster_id=0, $case_law=0)
    {
        $return = [];
        $currentDate = date('Y-m-d');
        $sql = "SELECT count(id)
                FROM master.m_limitation_period
                WHERE casetype_id = $casetype_id
                AND display = 'Y'
                AND submaster_id = $submaster_id
                AND case_law = $case_law
                AND (('$currentDate' BETWEEN from_date AND to_date) OR (from_date <= '$currentDate' AND to_date is null))";
                   //pr($sql);
         
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $return = $query->getRowArray();
        }
        return $return;
    }

    public function updateCaseLimit($id)
    {
        $builder = $this->db->table('case_limit');
        $data = ['case_lim_display' => 'N'];
        $builder->where('id', $id);
        $builder->where('case_lim_display', 'Y');
        $builder->update($data);
        return $this->db->affectedRows() > 0;
    }

    public function getJailerSignDt($dairy_no)
    {
        $db = \Config\Database::connect(); 

        $builder = $db->table('jail_petition_details');
        $builder->select('jailer_sign_dt');
        $builder->where('diary_no', $dairy_no); 
        $builder->where('jail_display', 'Y');

        return $row = $builder->get()->getRowArray();

        
    }
    
}
