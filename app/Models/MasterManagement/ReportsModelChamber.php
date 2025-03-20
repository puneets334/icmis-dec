<?php

namespace App\Models\MasterManagement;
use CodeIgniter\Model;

class ReportsModelChamber extends Model
{


    public function __construct(){
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    function get_aor_detail($aorCode, $fromDate, $toDate, $flag)
    {
    /*      
        $sql="select distinct concat(diary_number,'/',diary_year) as diary,advocate_code as aor_code,advocate_name as advName,registration_number_display,concat(petitioner_name,' Vs ',respondent_name) as title,date(filing_date) as filing_date,date(registration_date) as reg_date,case when ref_case_status_id in(8,10,16) then 'Dismissed' else case when ref_case_status_id in(7,9,12) then 'Pending' end end as status,case when ref_case_status_id in(8,10,16) then date(ec.order_date)  end as disposal_date,date(ecaa.updated_on) as ent_date,case when is_child='f' then 'Main' else case when is_child='t' then 'Connected' end end as main
    ,advocate_type from cis_full.ec_case ec join cis_full.ec_case_additional_advocate ecaa on ec.id=ecaa.ec_case_id 
    left outer join cis_full.ec_case_listing_details ecld on ec.ec_case_listing_details_id=ecld.id 
    join cis_full.org_advocate oa on ecaa.org_advocate_id=oa.id where advocate_code=$aorCode and date(ecaa.updated_on) between '".$fromDate."' and '".$toDate."' order by ent_date;";
    */


        // $sql = "select distinct concat(ec.diary_number,'/',diary_year) diary,advocate_code as aor_code,
        //             advocate_name as advName,registration_number_display,concat(petitioner_name,' Vs ',respondent_name) as title,
        //             date(filing_date) as filing_date,date(registration_date) as reg_date,advocate_type
        //             from  cis_full.ec_case ec 
        //             left join cis_full.ec_case_additional_advocate ecaa on ec.id=ecaa.ec_case_id
        //             join cis_full.org_advocate oa on ecaa.org_advocate_id=oa.id
        //             where advocate_code=$aorCode and date(ecaa.updated_on) between '".$fromDate."' and '".$toDate."'  order by filing_date";
        // $query = $this->db->query($sql);
        // $result = $query->getResultArray();
        // if (!empty($result)) {
        //     return $result;
        // } else {
        //     return false;
        // }

        $builder = $this->db->table('cis_full.ec_case ec');
        $builder->join('cis_full.ec_case_additional_advocate ecaa', 'ec.id = ecaa.ec_case_id', 'left');
        $builder->join('cis_full.org_advocate oa', 'ecaa.org_advocate_id = oa.id');
        $builder->select('DISTINCT CONCAT(ec.diary_number, \'/\', ec.diary_year) AS diary');
        $builder->select('advocate_code AS aor_code');
        $builder->select('advocate_name AS advName');
        $builder->select('registration_number_display');
        $builder->select('CONCAT(petitioner_name, \' Vs \', respondent_name) AS title');
        $builder->select('DATE(filing_date) AS filing_date');
        $builder->select('DATE(registration_date) AS reg_date');
        $builder->select('advocate_type');
        $builder->where('advocate_code', $aorCode);
        $builder->where('DATE(ecaa.updated_on) >=', $fromDate);
        $builder->where('DATE(ecaa.updated_on) <=', $toDate);
        $builder->orderBy('filing_date');
        $result = $builder->get()->getResultArray();
        if (!empty($result)) {
            return $result;
        } else {
            return false;
        }
    }

  }
