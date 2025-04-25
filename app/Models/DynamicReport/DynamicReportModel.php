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
          $sql = "SELECT id, section_name FROM master.usersection WHERE display = 'Y' AND isda = 'Y' ORDER BY section_name";
          $query = $this->db->query($sql);
          if ($query->getNumRows() >= 1) {
               return $query->getResultArray();
          } else {
               return false;
          }
     }

     public function getMainSubjectCategory()
     {
          $sql = "SELECT subcode1, sub_name1 FROM master.submaster WHERE (flag_use = 'S' OR flag_use = 'L') AND display = 'Y' AND match_id != 0 AND flag = 'S' GROUP BY subcode1, sub_name1 ORDER BY subcode1";
          $query = $this->db->query($sql);
          if ($query->getNumRows() >= 1) {
               return $query->getResultArray();
          } else {
               return false;
          }
     }

     public function getCaseTypeList()
     {
          $sql = "SELECT casecode, skey, casename, short_description FROM master.casetype WHERE display = 'Y' AND casecode != 9999 ORDER BY short_description";
          $query = $this->db->query($sql);
          if ($query->getNumRows() >= 1) {
               return $query->getResultArray();
          } else {
               return false;
          }
     }

     public function getState()
     {
          $sql = "SELECT cmis_state_id, agency_state FROM master.ref_agency_state WHERE id != 9999 ORDER BY agency_state";
          $query = $this->db->query($sql);
          if ($query->getNumRows() >= 1) {
               return $query->getResultArray();
          } else {
               return false;
          }
     }

     public function getJudges()
     {
          $sql = "SELECT jcode, jname FROM master.judge WHERE is_retired = 'N' AND display = 'Y' AND jtype = 'J' ORDER BY jcode";
          $query = $this->db->query($sql);
          if ($query->getNumRows() >= 1) {
               return $query->getResultArray();
          } else {
               return false;
          }
     }

     public function getAor()
     {
          $sql = "SELECT bar_id, name, aor_code, CONCAT(name, '(', aor_code, ')') AS name_display FROM master.bar WHERE isdead = 'N' AND if_aor = 'Y' AND if_sen = 'N' ORDER BY aor_code";
          $query = $this->db->query($sql);
          if ($query->getNumRows() >= 1) {
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
          $sql = "SELECT usercode, CONCAT(name, '(', empid, ')') as name FROM master.users WHERE section = :section: AND display = 'Y' AND usertype IN (17, 50, 51)";
          $query = $this->db->query($sql, ['section' => $section]);
          if ($query->getNumRows() >= 1) {
               return $query->getResult();
          } else {
               return false;
          }
     }

     function get_agency_code($state, $agency)
     {
          if ($agency == 1)
               $condition = " and agency_or_court::integer=1";
          else if ($agency == 2)
               $condition = " and agency_or_court::integer in(5,6)";
          else
               $condition = "";
          $sql = "select * from master.ref_agency_code where cmis_state_id=$state and is_deleted='f' $condition order by agency_or_court,agency_name";
          $query = $this->db->query($sql);
          if ($query->getNumRows() >= 1) {
               return $query->getResult();
          } else {
               return false;
          }
     }
     
     function get_casetype($type)
     {
          $condition='';
          if($type!='b') {
             $condition=" and nature='$type'";
          }
          $sql="select casecode,casename from master.casetype where display='Y' and is_deleted='f' $condition order by casecode";
          $query = $this->db->query($sql);
          if($query->getNumRows() >= 1) {
               return $query->getResultArray();
          } else {
               return false;
          }
     }

     function get_result($option, $condition, $sort, $sortOption2, $joinCondition)
     {
          if(!str_contains($condition, 'and active_reg_year=') && !empty($condition)) {
               $condition = " $condition";
          } elseif(str_contains($condition, 'and active_reg_year=')) {
               $condition = "$condition";
          }
          if ($sort != '') {
               $sort = $sort.",";
               $condition .= " order by " . $sortOption2;
          }
          $sql = "select 
               row_number() over () as serial_number,p.* from (select distinct
               a.*
               from (
               select distinct
                    $sort
                    reg_no_display, 
                    pet_name, 
                    res_name, 
                    diary_no_rec_date, 
                    active_fil_dt, 
                    d.ord_dt, 
                    mf_active, 
                    agency_state, 
                    agency_name, 
                    (
                    select string_agg(
                              sub_name1 || '--' || sub_name4 || ' (' || category_sc_old || ')'
                              , ','
                              )
                    from mul_category mc 
                    left join master.submaster s 
                         on mc.submaster_id = s.id 
                    and s.display = 'Y' 
                    and s.flag = 's' 
                    and s.flag_use in ('S', 'L')
                    where mc.diary_no = m.diary_no 
                         and mc.display = 'Y'
                    ) as subject, 
                    u.name, 
                    u.empid, 
                    us.section_name, 
                    tentative_section(m.diary_no) as section, 
                    tentative_da(m.diary_no::integer) as da, 
                    c_status 
               from main m 
               left join master.ref_agency_state ras 
                    on m.ref_agency_state_id = ras.cmis_state_id 
               left join master.ref_agency_code rac 
                    on m.ref_agency_code_id = rac.id 
               left join master.users u 
                    on m.dacode = u.usercode 
               left join master.usersection us 
                    on u.section = us.id 
               left join (
                    select 
                         cl_date, 
                         string_agg(r_head::text, ',') as r_head, 
                         diary_no 
                    from case_remarks_multiple  group by cl_date,diary_no 
                    order by cl_date desc 
                    limit 1
               ) crm on m.diary_no = crm.diary_no ::int
               left join heardt h 
                    on m.diary_no = h.diary_no 
               left join dispose d 
                    on m.diary_no = d.diary_no 
               left join mul_category mc 
                    on m.diary_no = mc.diary_no and mc.display = 'Y' 
               left join master.submaster s 
                    on mc.submaster_id = s.id 
                    and s.display = 'Y' 
                    and s.flag = 's' 
                    and s.flag_use in ('S', 'L') 
               left join party p 
                    on m.diary_no = p.diary_no 
               left join advocate adv 
                    on m.diary_no = adv.diary_no 
                    and adv.display = 'Y'
                    $joinCondition $condition
               ) a)p;";
          $query = $this->db->query($sql);
          $rows = $query->getNumRows();
          if ($option == 1) {
               return $rows;
          } else if ($option == 2) {
               return $query->getResultArray();
          } else {
               return false;
          }
     }

}