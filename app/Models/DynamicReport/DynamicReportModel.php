<?php

namespace App\Models\DynamicReport;

use CodeIgniter\Model;

class DynamicReportModel extends Model
{

  public function __construct()
  {
    parent::__construct();
    $this->db = db_connect();
  }

  public function getSections()
  {
    // Define the query
    $sql = "SELECT id, section_name 
              FROM master.usersection 
              WHERE display = 'Y' AND isda = 'Y' 
              ORDER BY section_name";

    // Execute the query
    $query = $this->db->query($sql);

    // Check if any rows are returned
    if ($query->getNumRows() >= 1) {
      // Return the result as an array
      return $query->getResultArray();
    } else {
      return false;
    }
  }
  public function getMainSubjectCategory()
  {
    // Define the query
    $sql = "SELECT subcode1, sub_name1 
              FROM master.submaster 
              WHERE (flag_use = 'S' OR flag_use = 'L') 
              AND display = 'Y' 
              AND match_id != 0 
              AND flag = 'S' 
              GROUP BY subcode1, sub_name1 
              ORDER BY subcode1";

    // Execute the query
    $query = $this->db->query($sql);

    // Check if any rows are returned
    if ($query->getNumRows() >= 1) {
      // Return the result as an array
      return $query->getResultArray();
    } else {
      return false;
    }
  }

  public function getCaseTypeList()
  {
    // Write the query
    $sql = "SELECT casecode, skey, casename, short_description 
            FROM master.casetype 
            WHERE display = 'Y' AND casecode != 9999 
            ORDER BY short_description";

    // Execute the query
    $query = $this->db->query($sql);

    // Check if there are any rows returned
    if ($query->getNumRows() >= 1) {
      // Return the result as an array
      return $query->getResultArray();
    } else {
      return false;
    }
  }

  public function getState()
  {
    // Define the query
    $sql = "SELECT cmis_state_id, agency_state 
            FROM master.ref_agency_state 
            WHERE id != 9999 
            ORDER BY agency_state";

    // Execute the query
    $query = $this->db->query($sql);

    // Check if any rows are returned
    if ($query->getNumRows() >= 1) {
      // Return the result as an array
      return $query->getResultArray();
    } else {
      return false;
    }
  }

  public function getJudges()
  {
    // Define the query
    $sql = "SELECT jcode, jname 
            FROM master.judge 
            WHERE is_retired = 'N' 
            AND display = 'Y' 
            AND jtype = 'J' 
            ORDER BY jcode";

    // Execute the query
    $query = $this->db->query($sql);

    // Check if any rows are returned
    if ($query->getNumRows() >= 1) {
      // Return the result as an array
      return $query->getResultArray();
    } else {
      return false;
    }
  }

  public function getAor()
  {
    // Define the query
    $sql = "SELECT bar_id, name, aor_code, CONCAT(name, '(', aor_code, ')') AS name_display 
            FROM master.bar
            WHERE isdead = 'N' 
            AND if_aor = 'Y' 
            AND if_sen = 'N' 
            ORDER BY aor_code";

    // Execute the query
    $query = $this->db->query($sql);

    // Check if any rows are returned
    if ($query->getNumRows() >= 1) {
      // Return the result as an array
      return $query->getResultArray();
    } else {
      return false;
    }
  }

  public function getSubSubjectCategory($Mcat)
  {
    $Mcat = (int) $Mcat;

    $sql = "select id, subcode1,category_sc_old,sub_name1,sub_name4,
    case when (category_sc_old is not null and category_sc_old != '' and category_sc_old::text != '0')
    then concat('',category_sc_old,'#-#',sub_name4)
    else concat('',concat(subcode1,'',subcode2),'#-#',sub_name4)
    end as dsc from master.submaster where subcode1= $Mcat AND subcode2 != '0' and flag='s' and flag_use='S' GROUP BY id,subcode1,category_sc_old, sub_name1,sub_name4";
    $query = $this->db->query($sql, ['Mcat' => (int)$Mcat]);

    if ($query->getNumRows() >= 1) {
      return $query->getResult();
    } else {
      return false;
    }
  }

  function getDa($section)
  {
    $sql = "SELECT usercode, CONCAT(name, '(', empid, ')') as name 
    FROM master.users 
    WHERE section = :section: 
    AND display = 'Y' 
    AND usertype IN (17, 50, 51)";

    // Execute the query with binding the $section parameter
    $query = $this->db->query($sql, ['section' => $section]);

    // Check if there are results
    if ($query->getNumRows() >= 1) {
      return $query->getResult();
    } else {
      return false;
    }
  }

  function get_agency_code($state,$agency)
    {
        if($agency==1)
            $condition=" and agency_or_court=1";
        else if($agency==2)
            $condition=" and agency_or_court in(5,6)";
        else
            $condition="";
        $sql="select * from master.ref_agency_code where cmis_state_id=$state and is_deleted='f' $condition order by agency_or_court,agency_name";
        $query = $this->db->query($sql);
        
        if ($query->getNumRows() >= 1) {
          return $query->getResult();
        } else {
          return false;
        }
    }

    function get_result($option,$condition,$sort,$joinCondition)
    {
        // if($sort!='')
        //     $condition.=" order by ".$sort;
        // $sql="select @count:=@count+1 serial_number ,a.* from (select distinct m.diary_no,reg_no_display,
        //         pet_name,res_name,diary_no_rec_date,active_fil_dt,d.ord_dt,mf_active,/*main_supp_flag,*/
        //         agency_state,agency_name,(select group_concat(concat(sub_name1,'--',sub_name4,' (',category_sc_old,')')) subject 
        //         from mul_category mc left join submaster s on mc.submaster_id=s.id AND s.display = 'Y' 
        //         and flag='s' and flag_use in('S','L') where mc.diary_no=m.diary_no and mc.display='Y') 'subject',u.name,
        //         u.empid,us.section_name,tentative_section(m.diary_no) as section,tentative_da(m.diary_no) as da,
        //         c_status from main m 
        //         left join ref_agency_state ras on m.ref_agency_state_id=ras.cmis_state_id 
        //         left join ref_agency_code rac on m.ref_agency_code_id=rac.id
        //         left join users u on m.dacode=u.usercode 
        //         left join usersection us on u.section=us.id
        //         left join (select cl_date,group_concat(r_head) r_head,diary_no from case_remarks_multiple 
        //         order by cl_date desc limit 1 ) crm on m.diary_no=crm.diary_no
        //         left join heardt h on m.diary_no=h.diary_no
        //         left join dispose d on m.diary_no=d.diary_no 
        //         left join mul_category mc on m.diary_no=mc.diary_no and mc.display='Y' 
        //         left join submaster s on mc.submaster_id=s.id AND s.display = 'Y' and flag='s' and flag_use in('S','L') 
        //         left join party p on m.diary_no=p.diary_no
        //         left join advocate adv on m.diary_no=adv.diary_no and adv.display='Y' $joinCondition 
        //         where $condition) a , (SELECT @count:= 0) AS count";
        // $query = $this->db->query($sql);
        // //echo $this->db->last_query();
        // //exit(0);
        // $rows=$query->getNumRows();
        // if($option==1)
        //     return $rows;
        // else if($option==2)
        //     return $query->getResultArray();
        // else
        //     return false;

		$builder = $this->db->table('main m');
		$builder->select('DISTINCT m.diary_no, reg_no_display, pet_name, res_name, diary_no_rec_date, active_fil_dt, d.ord_dt, mf_active, agency_state, agency_name, (SELECT GROUP_CONCAT(CONCAT(sub_name1, \'--\', sub_name4, \' (\', category_sc_old, \')\'))  FROM mul_category mc  LEFT JOIN submaster s ON mc.submaster_id = s.id AND s.display = \'Y\' WHERE mc.diary_no = m.diary_no AND mc.display = \'Y\') AS subject, u.name, u.empid, us.section_name, tentative_section(m.diary_no) AS section, tentative_da(m.diary_no) AS da, c_status');
		$builder->join('ref_agency_state ras', 'm.ref_agency_state_id = ras.cmis_state_id', 'left');
		$builder->join('ref_agency_code rac', 'm.ref_agency_code_id = rac.id', 'left');
		$builder->join('users u', 'm.dacode = u.usercode', 'left');
		$builder->join('usersection us', 'u.section = us.id', 'left');
		$builder->join('(SELECT cl_date, GROUP_CONCAT(r_head) AS r_head, diary_no FROM case_remarks_multiple 
						GROUP BY diary_no ORDER BY cl_date DESC LIMIT 1) crm', 'm.diary_no = crm.diary_no', 'left');
		$builder->join('heardt h', 'm.diary_no = h.diary_no', 'left');
		$builder->join('dispose d', 'm.diary_no = d.diary_no', 'left');
		$builder->join('mul_category mc', 'm.diary_no = mc.diary_no AND mc.display = \'Y\'', 'left');
		$builder->join('submaster s', 'mc.submaster_id = s.id AND s.display = \'Y\' AND flag = \'s\' AND flag_use IN (\'S\', \'L\')', 'left');
		$builder->join('party p', 'm.diary_no = p.diary_no', 'left');
		$builder->join('advocate adv', 'm.diary_no = adv.diary_no AND adv.display = \'Y\'', 'left');
		if (!empty($condition)) {
			$builder->where($condition);
		}
		if (!empty($sort)) {
			$builder->orderBy($sort);
		}
		$countQuery = '(SELECT @count := 0) AS count';
		$subQuery = $builder->getCompiledSelect() . ', ' . $countQuery;
		$query = $this->db->query("SELECT @count := @count + 1 AS serial_number, a.* FROM ($subQuery) a");
		$rows = $query->getNumRows();
		if ($option == 1) {
			return $rows;
		} elseif ($option == 2) {
			return $query->getResultArray();
		} else {
			return false;
		}
    }
}
