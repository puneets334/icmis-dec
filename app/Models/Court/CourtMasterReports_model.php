<?php

namespace App\Models\Court;

use CodeIgniter\Model;

class CourtMasterReports_model extends Model
{
    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
        
    }


    function getUsertype($usercode){
        $builder = $this->db->table('master.users');
        $builder->select("usertype");
        $builder->where('usercode', $usercode);
        $builder->where('display','Y');
        $query = $builder->get();
        return $query->getResultArray();
	}

	function getJudges($usertype, $usercode){
		if ($usertype == 1 || $usertype == 2 || $usertype == 3 || $usertype == 4 || $usercode == 213) {
            $builder = $this->db->table('master.judge j');
            $builder->select("j.jname, j.jcode");
            $builder->where('j.is_retired !=', 'Y');
            $builder->where('j.display', 'Y');
            $builder->where('j.jtype', 'J');
            $builder->orderBy('j.judge_seniority');
        } else {
            $builder = $this->db->table('master.judge j');
            $builder->select("j.jname, j.jcode");
            $builder->join('master.users u', 'u.jcode = j.jcode');
            $builder->where('j.is_retired !=', 'Y');
            $builder->where('j.display', 'Y');
            $builder->where('u.display', 'Y');
            $builder->where('j.jtype', 'J');
            $builder->where('u.usercode', $usercode);
        }
        
        $query = $builder->get();
        return $query->getResultArray();
	}
   
    function stats_point1($fromDate,$toDate,$jcode){
		$builder = $this->db->table('dispose d');

        $builder->select("
                DISTINCT CONCAT(
                    m.reg_no_display, ' @ ',
                    CONCAT(
                        SUBSTRING(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4),
                        '/',
                        SUBSTRING(m.diary_no::text, LENGTH(m.diary_no::text) - 3)
                    )
                ) AS REGNO_DNO,
                CONCAT(pet_name, ' Vs. ', res_name) AS TITLE,
                d.ord_dt
            ", false);

            $builder->join('main m', 'd.diary_no = m.diary_no');
            $builder->join('ordernet o', 'd.diary_no = o.diary_no AND d.ord_dt = o.orderdate', 'left');
            $builder->where("d.ord_dt BETWEEN '{$fromDate}' AND '{$toDate}'");
            $builder->where("'{$jcode}' = ANY(string_to_array(jud_id, ','))");
            $builder->orderBy('d.ord_dt');

            $query = $builder->get();
            return $query->getResultArray();
	}

    function stats_point2a($fromDate,$toDate,$jcode){
		
      $builder = $this->db->table('dispose d');

        $builder->select("
                DISTINCT CONCAT(
                    m.reg_no_display, ' @ ',
                    CONCAT(
                        SUBSTRING(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4),
                        '/',
                        SUBSTRING(m.diary_no::text, LENGTH(m.diary_no::text) - 3)
                    )
                ) AS REGNO_DNO,
                CONCAT(pet_name, ' Vs. ', res_name) AS TITLE,
                d.ord_dt
            ", false);

            $builder->join('main m', 'd.diary_no = m.diary_no');
            $builder->join('ordernet o', 'd.diary_no = o.diary_no AND d.ord_dt = o.orderdate', 'left');
            $builder->where("d.ord_dt BETWEEN '{$fromDate}' AND '{$toDate}'");
            $builder->where("'{$jcode}' = ANY(string_to_array(jud_id, ','))");
            $builder->where('afr', 'Y');
            $builder->orderBy('d.ord_dt');

            $query = $builder->get();
            return $query->getResultArray();
    }

    function stats_point2b($fromDate,$toDate,$jcode){


        $subBuilder = $this->db->table('dispose d');
        $subBuilder->select('DISTINCT d.diary_no', false);
        $subBuilder->join('ordernet o', 'd.diary_no = o.diary_no AND d.ord_dt = o.orderdate', 'left');
        $subBuilder->where("d.ord_dt BETWEEN '{$fromDate}' AND '{$toDate}'");
        $subBuilder->where("'{$jcode}' = ANY(string_to_array(jud_id, ','))");
        $subBuilder->where('afr', 'Y');

        $subQuery = $subBuilder->getCompiledSelect();

         $builder = $this->db->table('dispose d');

        $builder->select("
            DISTINCT CONCAT(
                m.reg_no_display, ' @ ',
                CONCAT(
                    SUBSTRING(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4),
                    '/',
                    SUBSTRING(m.diary_no::text, LENGTH(m.diary_no::text) - 3)
                )
            ) AS REGNO_DNO,
            CONCAT(pet_name, ' Vs. ', res_name) AS TITLE,
            d.ord_dt
        ", false);

        $builder->join('main m', 'd.diary_no = m.diary_no');
        $builder->join('ordernet o', 'd.diary_no = o.diary_no AND d.ord_dt = o.orderdate', 'left');
        $builder->where("d.ord_dt BETWEEN '{$fromDate}' AND '{$toDate}'");
        $builder->where("'{$jcode}' = ANY(string_to_array(jud_id, ','))");
        $builder->where('afr', 'N');
        $builder->where("d.diary_no NOT IN ($subQuery)", null, false);
        $builder->orderBy('d.ord_dt');

        $query = $builder->get();
        return $query->getResultArray();

   }


   function stats_point2c($fromDate,$toDate,$jcode){
    
        $builder = $this->db->table('dispose d');

        $builder->select("
                DISTINCT CONCAT(
                    m.reg_no_display, ' @ ',
                    CONCAT(
                        SUBSTRING(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4),
                        '/',
                        SUBSTRING(m.diary_no::text, LENGTH(m.diary_no::text) - 3)
                    )
                ) AS REGNO_DNO,
                CONCAT(pet_name, ' Vs. ', res_name) AS TITLE,
                d.ord_dt
            ", false);

            $builder->join('main m', 'd.diary_no = m.diary_no');
            $builder->join('ordernet o', 'd.diary_no = o.diary_no AND d.ord_dt = o.orderdate', 'left');
            $builder->where("d.ord_dt BETWEEN '{$fromDate}' AND '{$toDate}'");
            $builder->where("'{$jcode}' = ANY(string_to_array(jud_id, ','))");
            $builder->where('afr', Null);
            $builder->orderBy('d.ord_dt');
            $query = $builder->get();
            return $query->getResultArray();


    }

    function stats_point3($fromDate,$toDate,$jcode){

        $builder = $this->db->table('dispose d');

        $builder->select("
                DISTINCT CONCAT(
                    m.reg_no_display, ' @ ',
                    CONCAT(
                        SUBSTRING(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4),
                        '/',
                        SUBSTRING(m.diary_no::text, LENGTH(m.diary_no::text) - 3)
                    )
                ) AS REGNO_DNO,
                CONCAT(pet_name, ' Vs. ', res_name) AS TITLE,
                d.ord_dt
            ", false);

            $builder->join('main m', 'd.diary_no = m.diary_no');
            $builder->where("d.ord_dt BETWEEN '{$fromDate}' AND '{$toDate}'");
            $builder->where('dispjud', $jcode);
            $builder->orderBy('d.ord_dt');
            $query = $builder->get();
            return $query->getResultArray();
	}

    function stats_point3a($fromDate,$toDate,$jcode){
		$builder = $this->db->table('dispose d');

        $builder->select("
                DISTINCT CONCAT(
                    m.reg_no_display, ' @ ',
                    CONCAT(
                        SUBSTRING(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4),
                        '/',
                        SUBSTRING(m.diary_no::text, LENGTH(m.diary_no::text) - 3)
                    )
                ) AS REGNO_DNO,
                CONCAT(pet_name, ' Vs. ', res_name) AS TITLE,
                d.ord_dt
            ", false);

            $builder->join('main m', 'd.diary_no = m.diary_no');
            $builder->join('ordernet o', 'd.diary_no = o.diary_no AND d.ord_dt = o.orderdate', 'left');
            $builder->where("d.ord_dt BETWEEN '{$fromDate}' AND '{$toDate}'");
            $builder->where('dispjud', $jcode);
            $builder->where('afr', 'Y');
            $builder->orderBy('d.ord_dt');
            $query = $builder->get();
            return $query->getResultArray();

	}

	function stats_point3b($fromDate,$toDate,$jcode){
		
        $subBuilder = $this->db->table('dispose d');
        $subBuilder->select('DISTINCT d.diary_no', false);
        $subBuilder->join('ordernet o', 'd.diary_no = o.diary_no AND d.ord_dt = o.orderdate', 'left');
        $subBuilder->where("d.ord_dt BETWEEN '{$fromDate}' AND '{$toDate}'");
        $subBuilder->where('dispjud',$jcode);
        $subBuilder->where('afr', 'Y');

        $subQuery = $subBuilder->getCompiledSelect();

        $builder = $this->db->table('dispose d');

        $builder->select("
            DISTINCT CONCAT(
                m.reg_no_display, ' @ ',
                CONCAT(
                    SUBSTRING(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4),
                    '/',
                    SUBSTRING(m.diary_no::text, LENGTH(m.diary_no::text) - 3)
                )
            ) AS REGNO_DNO,
            CONCAT(pet_name, ' Vs. ', res_name) AS TITLE,
            d.ord_dt
        ", false);

        $builder->join('main m', 'd.diary_no = m.diary_no');
        $builder->join('ordernet o', 'd.diary_no = o.diary_no AND d.ord_dt = o.orderdate', 'left');
        $builder->where("d.ord_dt BETWEEN '{$fromDate}' AND '{$toDate}'");
        $builder->where('dispjud', $jcode);
        $builder->where('afr', 'N');
        $builder->where("d.diary_no NOT IN ($subQuery)", null, false);
        $builder->orderBy('d.ord_dt');

        $query = $builder->get();
        return $query->getResultArray();

    }


	function stats_point3c($fromDate,$toDate,$jcode){
		
        $builder = $this->db->table('dispose d');

        $builder->select("
                DISTINCT CONCAT(
                    m.reg_no_display, ' @ ',
                    CONCAT(
                        SUBSTRING(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4),
                        '/',
                        SUBSTRING(m.diary_no::text, LENGTH(m.diary_no::text) - 3)
                    )
                ) AS REGNO_DNO,
                CONCAT(pet_name, ' Vs. ', res_name) AS TITLE,
                d.ord_dt
            ", false);

            $builder->join('main m', 'd.diary_no = m.diary_no');
            $builder->join('ordernet o', 'd.diary_no = o.diary_no AND d.ord_dt = o.orderdate', 'left');
            $builder->where("d.ord_dt BETWEEN '{$fromDate}' AND '{$toDate}'");
            $builder->where('dispjud',$jcode);
            $builder->where('afr', Null);
            $builder->orderBy('d.ord_dt');
            $query = $builder->get();
            return $query->getResultArray();
	}

    function stats_judgment($fromDate,$toDate,$jcode){
		
        $builder = $this->db->table('main m');

        $builder->select("
                DISTINCT CONCAT(
                    m.reg_no_display, ' @ ',
                    CONCAT(
                        SUBSTRING(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4),
                        '/',
                        SUBSTRING(m.diary_no::text, LENGTH(m.diary_no::text) - 3)
                    )
                ) AS REGNO_DNO,
                CONCAT(pet_name, ' Vs. ', res_name) AS TITLE,
                o.orderdate
            ", false);

            $builder->join('ordernet o', 'm.diary_no = o.diary_no','left');
            $builder->where("o.orderdate BETWEEN '{$fromDate}' AND '{$toDate}'");
            $builder->where('perj',$jcode);
            $builder->where('type', 'J');
            $builder->orderBy('o.orderdate');
            $query = $builder->get();
            return $query->getResultArray();

	}

	function stats_notice($fromDate,$toDate,$jcode){   

		$builder = $this->db->table('case_remarks_multiple crm');
        $builder->select("
                CONCAT(
                    m.reg_no_display, ' @ ',
                    CONCAT(
                        SUBSTRING(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4),
                        '/',
                        SUBSTRING(m.diary_no::text, LENGTH(m.diary_no::text) - 3)
                    )
                ) AS REGNO_DNO,
                CONCAT(pet_name, ' Vs. ', res_name) AS TITLE,
                string_agg(TO_CHAR(cl_date, 'DD-MM-YYYY'), ', ') AS cl_date,
                MIN(cl_date) AS min_cl_date
            ", false);

            $builder->join('main m', 'crm.diary_no = m.diary_no');
            $builder->where("crm.cl_date BETWEEN '{$fromDate}' AND '{$toDate}'");
            $builder->where("'{$jcode}' = ANY(string_to_array(jcodes, ','))");
            $builder->whereIn('r_head', [3, 62, 181, 182, 183, 184, 203]);
            $builder->groupBy('m.reg_no_display, m.diary_no, pet_name, res_name');
            $builder->orderBy('min_cl_date', 'ASC');

            $query = $builder->get();
            return $query->getResultArray();
    }


	function stats_notice_disposal($fromDate,$toDate,$jcode){
		
        $builder = $this->db->table('dispose d');

        $builder->select("
                DISTINCT CONCAT(
                    m.reg_no_display, ' @ ',
                    CONCAT(
                        SUBSTRING(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4),
                        '/',
                        SUBSTRING(m.diary_no::text, LENGTH(m.diary_no::text) - 3)
                    )
                ) AS REGNO_DNO,
                CONCAT(pet_name, ' Vs. ', res_name) AS TITLE,
                d.ord_dt
            ", false);

            $builder->join('main m','d.diary_no=m.diary_no');
            $builder->join('heardt h','d.diary_no=h.diary_no and d.ord_dt=h.next_dt','left');
            $builder->where("d.ord_dt BETWEEN '{$fromDate}' AND '{$toDate}'");
            $builder->where('dispjud', $jcode);
            $builder->whereIn('subhead', [813,814]);
            $builder->orderBy('d.ord_dt');
            $query = $builder->get();
            return $query->getResultArray();
   }

	function stats_notice_disposal_misc($fromDate,$toDate,$jcode){
		
        $builder = $this->db->table('dispose d');

        $builder->select("
                DISTINCT CONCAT(
                    m.reg_no_display, ' @ ',
                    CONCAT(
                        SUBSTRING(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4),
                        '/',
                        SUBSTRING(m.diary_no::text, LENGTH(m.diary_no::text) - 3)
                    )
                ) AS REGNO_DNO,
                CONCAT(pet_name, ' Vs. ', res_name) AS TITLE,
                d.ord_dt
            ", false);

            $builder->join('main m','d.diary_no=m.diary_no');
            $builder->join('heardt h','d.diary_no=h.diary_no and d.ord_dt=h.next_dt','left');
            $builder->where("d.ord_dt  BETWEEN '{$fromDate}' AND '{$toDate}'");
            $builder->where('dispjud', $jcode);
            $builder->whereIn('subhead', [813,814]);
            $builder->where('mainhead!=','F');
            $builder->orderBy('d.ord_dt');
            $query = $builder->get();
            return $query->getResultArray();


	}

	function stats_notice_disposal_regular($fromDate,$toDate,$jcode){
		
        $builder = $this->db->table('dispose d');

        $builder->select("
                DISTINCT CONCAT(
                    m.reg_no_display, ' @ ',
                    CONCAT(
                        SUBSTRING(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4),
                        '/',
                        SUBSTRING(m.diary_no::text, LENGTH(m.diary_no::text) - 3)
                    )
                ) AS REGNO_DNO,
                CONCAT(pet_name, ' Vs. ', res_name) AS TITLE,
                d.ord_dt
            ", false);

            $builder->join('main m','d.diary_no=m.diary_no');
            $builder->join('heardt h','d.diary_no=h.diary_no and d.ord_dt=h.next_dt','left');
            $builder->where("d.ord_dt  BETWEEN '{$fromDate}' AND '{$toDate}'");
            $builder->where('dispjud', $jcode);
            $builder->whereIn('subhead', [813,814]);
            $builder->where('mainhead','F');
            $builder->orderBy('d.ord_dt');
            $query = $builder->get();
            return $query->getResultArray();

     }




}