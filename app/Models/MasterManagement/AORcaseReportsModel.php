<?php

namespace App\Models\MasterManagement;
use CodeIgniter\Model;

class AORcaseReportsModel extends Model
{


    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
    }

    
function get_display_status_with_date_differnces($tentative_cl_dt)
{
    $tentative_cl_date_greater_than_today_flag="F";
    $curDate=date('d-m-Y');
    $tentativeCLDate = date('d-m-Y', strtotime($tentative_cl_dt));
    $datediff=strtotime($tentativeCLDate) - strtotime($curDate);
    $noofdays= round($datediff / (60 * 60 * 24));


    if(strtotime($tentativeCLDate) > strtotime($curDate) )
    {

        if($noofdays<=60 && $noofdays>0){
            $tentative_cl_date_greater_than_today_flag='T';
        }
    }
    else
    {
        $tentative_cl_date_greater_than_today_flag='F';
    }
    return $tentative_cl_date_greater_than_today_flag;
}

 

    function getcases_nb_gr_90days($section,$da)
    {
        $condition="";
        if($da!=0)
            $condition=" and m.dacode=$da";

        $sql="  SELECT distinct us.section_name AS user_section, 
                u.name , u.empid,
                SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) AS diary_no, 
                SUBSTR(m.diary_no, - 4) AS diary_year, DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date, 
                m.pet_name, m.res_name, m.reg_no_display,
                h.next_dt AS Hearing_Date, h.board_type FROM main m 
                left join heardt h ON m.diary_no = h.diary_no 
                left join last_heardt lh on m.diary_no=lh.diary_no
                left JOIN users u ON u.usercode = m.dacode AND (u.display = 'Y' or u.display is null) 
                left join usersection us on us.id=u.section and us.display='Y'
                WHERE m.c_status = 'P' AND us.id=$section $condition 
                AND((DATEDIFF(now(),lh.next_dt)>=90) AND (DATEDIFF(now(),h.next_dt)>=90))";

        $query = $this->db->query($sql);

        if($query -> num_rows() >= 1)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }


function matters_listed($section,$da,$stage,$fromDays,$toDays,$year,$daysRange)
    {
        if($section!=0) {
            $sql_section = "select section_name from usersection where id=$section";
            $query_section = $this->db->query($sql_section);
            $result_section=$query_section->result_array();
            $section_name=$result_section[0]['section_name'];

        }
        $condition="";
        if($daysRange=='D')
            $condition.=" and datediff(curdate(),str_to_date(lldt(m.diary_no),'%d-%m-%Y')) between $fromDays and $toDays";
        else if($daysRange=='Y')
            $condition.=" and datediff(curdate(),str_to_date(lldt(m.diary_no),'%d-%m-%Y'))>$year";
        else if($daysRange=='N')
            $condition.=" and (lldt(m.diary_no) is null or lldt(m.diary_no)='')";
        if($da!=0)
            $condition.=" and m.dacode=$da";
        else if($da==0 and $section!=0)
            $condition.=" and tentative_section(m.diary_no) like '$section_name'";
        else if($da==0 and $section==0)
        $condition.=" ";
        $sql="select concat(SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4),'/',SUBSTR(m.diary_no, - 4) ) AS 'Diary',
              reg_no_display 'Case No',concat(pet_name,' vs ',res_name) as 'Cause Title',tentative_section(m.diary_no) 'Section' ,
              tentative_da(m.diary_no) 'Dealing Assistant',lldt(m.diary_no) 'Last listed on',m.diary_no_rec_date,
              m.active_fil_dt, CASE WHEN h.main_supp_flag = 0 THEN 'Ready' ELSE 'Not Ready' END AS Ready_status,
              CASE WHEN (m.diary_no = m.conn_key OR m.conn_key = 0 OR m.conn_key = '' OR m.conn_key IS NULL)
              THEN 'M' ELSE 'C' END AS main_or_connected from main m  left join `heardt` h on m.diary_no=h.diary_no
              where c_status='P' and mf_active='$stage' 
               $condition
              order by 4,5,SUBSTR(m.diary_no, - 4)";

        $query = $this->db->query($sql);
        if($query -> num_rows() >= 1)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }
 function get_disposed_matters_data($fromdate, $todate, $jCode)
    {

        $from_date = date('Y-m-d', strtotime($fromdate));
        $to_date = date('Y-m-d', strtotime($todate));


        if ($jCode == 1) {
            $condition = " and 1=1";
        } else if ($jCode != 1 ) {
            $condition = " and p1=" . $jCode;
        }

        $query = "SELECT p1 as jcode,j.jname,p2.Misc_Matter,p2.Regular_Matter,p2.total_cases
    FROM judge_group pj
    inner join judge j ON pj.p1=j.jcode
    left join
    (
     SELECT  year(ord_dt),h.roster_id,d.jud_id,
    count(distinct CASE WHEN (m.mf_active='M') THEN m.diary_no END ) AS Misc_Matter,
    count(distinct CASE WHEN (m.mf_active='F') THEN m.diary_no END) AS Regular_Matter,
      count(*) AS total_cases
   FROM dispose d
     INNER JOIN main m ON m.diary_no=d.diary_no
    left JOIN heardt h ON m.diary_no=h.diary_no 
     WHERE d.ord_dt BETWEEN '" . $from_date . "'and'" . $to_date .
            "'and c_status='D' AND h.board_type ='J'
     group by SUBSTRING_INDEX( jud_id, ',', 1 )
    )p2 ON FIND_IN_SET (pj.p1, p2.jud_id)=1 and j.jcode=pj.p1
where pj.to_dt='0000-00-00'" . $condition . " order by pj.p1";

        $result = $this->db->query($query);

        return $result->result_array();

    }

    function getpresidingJudges()
    {
        $sql="SELECT p1 as jCode,j.jName FROM judge_group pj inner join judge j ON pj.p1=j.jcode where pj.to_dt='0000-00-00' order by j.jcode";

        $query = $this->db->query($sql);
        return $query->result_array();

    }
    
    function da_rog_report($section=0){
        if($section!='0')
        {
            $condition=" and section=$section";
        }
        else
            $condition="";
 $sql="select empid,dacode,name,type_name,section_name,count(distinct total) as total,count(distinct red) as red,count(distinct orange) as orange,count(distinct green) as green,count(distinct yellow) as yellow from
(select empid,dacode,name,type_name,section_name, m.diary_no as total,
case when if(tentative_cl_dt!='0000-00-00',DATEDIFF(h.tentative_cl_dt,date(now()))<2,1=1) and !( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL , if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1)) and (main_supp_flag=0 AND clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) ) and ( lastorder not like '%Not Reached%' and lastorder not like '%Case Not Receive%' and lastorder not like '%Heard & Reserved%'  OR lastorder IS NULL ) and (head_code!=5 OR head_code IS NULL) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,146,744,747,469,1485,742,1486,935,757,49,762) union select fil_no as diary_no from rgo_default where remove_def!='Y') then m.diary_no end as red,
case when DATEDIFF(h.tentative_cl_dt,date(now()))>1 and !( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL ,  if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1)) and (main_supp_flag=0 AND clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) ) and ( lastorder not like '%Not Reached%' and lastorder not like '%Case Not Receive%' and lastorder not like '%Heard & Reserved%' OR lastorder IS NULL ) and (head_code!=5 OR head_code IS NULL) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,146,744,747,469,1485,742,1486,935,757,49,762) union select fil_no as diary_no from rgo_default where remove_def!='Y') then m.diary_no end as orange,
case when ( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL ,if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1))) and ((main_supp_flag=0 AND clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0 ) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) OR ( lastorder like '%Not Reached%' OR lastorder like '%Case Not Receive%' ) OR head_code=5) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,146,744,747,469,1485,742,1486,935,757,49,762) union select fil_no as diary_no from rgo_default where remove_def!='Y') then m.diary_no end as green,
case when (h.main_supp_flag=3 and h.usercode in(559,146,744,747,469,1485,742,1486,935,757,49,762)) or rd.remove_def !='Y' OR lastorder like '%Heard & Reserved%' then m.diary_no end as yellow
from main  m 
 inner JOIN casetype c ON c.casecode =if ( (m.active_casetype_id!=null or m.active_casetype_id!=0), m.active_casetype_id,casetype_id) left JOIN heardt h ON m.diary_no = h.diary_no 
 left JOIN users user on m.dacode = user.usercode left join usertype ut on ut.id=user.usertype 
 left join rgo_default rd on m.diary_no=rd.fil_no
 left JOIN usersection b ON b.id = user.section LEFT JOIN subheading s ON h.subhead = s.stagecode  where  c_status='P'  
 $condition) a group by empid,dacode,name,type_name,section_name order by section_name,type_name desc,total";
/* SQL including matters in which advocate has expired and alternate arrangement is pending
 $sql="SELECT m.dacode,b.section_name,a.name,type_name,a.empid,count(distinct m.diary_no) total,
count(distinct case when h.mainhead='M' then m.diary_no end) tot_mh,count(distinct case when h.mainhead='F' then m.diary_no end) tot_fh,   
count(distinct case when ((if(tentative_cl_dt!='0000-00-00',DATEDIFF(h.tentative_cl_dt,date(now()))<2,1=1) and !( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL , if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1)) and (main_supp_flag=0 AND clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) ) and ( lastorder not like '%Not Reached%' and lastorder not like '%Case Not Receive%' and lastorder not like '%Heard & Reserved%'  OR lastorder IS NULL ) and (head_code!=5 OR head_code IS NULL) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,469) union select fil_no as diary_no from rgo_default where remove_def!='Y')) or(m.diary_no in(select distinct(advocate.diary_no) from advocate join main on advocate.diary_no=main.diary_no  where display='Y' and advocate_id in ( select bar_id from bar where isdead='Y')))) then m.diary_no end ) red,
count(distinct case when h.mainhead='M' and  ((if(tentative_cl_dt!='0000-00-00',DATEDIFF(h.tentative_cl_dt,date(now()))<2,1=1) and !( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL , if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1)) and (main_supp_flag=0 AND clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) ) and ( lastorder not like '%Not Reached%' and lastorder not like '%Case Not Receive%' and lastorder not like '%Heard & Reserved%'  OR lastorder IS NULL ) and (head_code!=5 OR head_code IS NULL) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,469) union select fil_no as diary_no from rgo_default where remove_def!='Y')) or(m.diary_no in(select distinct(advocate.diary_no) from advocate join main on advocate.diary_no=main.diary_no  where display='Y' and advocate_id in ( select bar_id from bar where isdead='Y')))) then m.diary_no end )red_mh,
count(distinct case when h.mainhead='F' and  ((if(tentative_cl_dt!='0000-00-00',DATEDIFF(h.tentative_cl_dt,date(now()))<2,1=1) and !( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL , if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1)) and (main_supp_flag=0 AND clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) ) and ( lastorder not like '%Not Reached%' and lastorder not like '%Case Not Receive%' and lastorder not like '%Heard & Reserved%'  OR lastorder IS NULL ) and (head_code!=5 OR head_code IS NULL) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,469) union select fil_no as diary_no from rgo_default where remove_def!='Y')) or(m.diary_no in(select distinct(advocate.diary_no) from advocate join main on advocate.diary_no=main.diary_no  where display='Y' and advocate_id in ( select bar_id from bar where isdead='Y')))) then m.diary_no end ) red_fh,
count(distinct case when DATEDIFF(h.tentative_cl_dt,date(now()))>1 and !( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL ,  if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1)) and (main_supp_flag=0 AND clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) ) and ( lastorder not like '%Not Reached%' and lastorder not like '%Case Not Receive%' and lastorder not like '%Heard & Reserved%' OR lastorder IS NULL ) and (head_code!=5 OR head_code IS NULL) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,469) union select fil_no as diary_no from rgo_default where remove_def!='Y' union select distinct(advocate.diary_no) from advocate join main on advocate.diary_no=main.diary_no  where display='Y' and advocate_id in ( select bar_id from bar where isdead='Y')) then m.diary_no end ) orange,
count(distinct case when h.mainhead='M' and DATEDIFF(h.tentative_cl_dt,date(now()))>1 and !( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL ,  if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1)) and (main_supp_flag=0 AND clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) ) and ( lastorder not like '%Not Reached%' and lastorder not like '%Case Not Receive%' and lastorder not like '%Heard & Reserved%' OR lastorder IS NULL ) and (head_code!=5 OR head_code IS NULL) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,469) union select fil_no as diary_no from rgo_default where remove_def!='Y' union select distinct(advocate.diary_no) from advocate join main on advocate.diary_no=main.diary_no  where display='Y' and advocate_id in ( select bar_id from bar where isdead='Y')) then m.diary_no end ) orange_mh,
count(distinct case when h.mainhead='F' and DATEDIFF(h.tentative_cl_dt,date(now()))>1 and !( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL ,  if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1)) and (main_supp_flag=0 AND clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) ) and ( lastorder not like '%Not Reached%' and lastorder not like '%Case Not Receive%' and lastorder not like '%Heard & Reserved%' OR lastorder IS NULL ) and (head_code!=5 OR head_code IS NULL) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,469) union select fil_no as diary_no from rgo_default where remove_def!='Y' union select distinct(advocate.diary_no) from advocate join main on advocate.diary_no=main.diary_no  where display='Y' and advocate_id in ( select bar_id from bar where isdead='Y')) then m.diary_no end ) orange_fh,
count(distinct case when ( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL ,if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1))) and ((main_supp_flag=0 AND clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0 ) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) OR ( lastorder like '%Not Reached%' OR lastorder like '%Case Not Receive%' OR lastorder like '%Heard & Reserved%' ) OR head_code=5) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,469) union select fil_no as diary_no from rgo_default where remove_def!='Y' union select distinct(advocate.diary_no) from advocate join main on advocate.diary_no=main.diary_no  where display='Y' and advocate_id in ( select bar_id from bar where isdead='Y'))  then m.diary_no end ) green,
count(distinct case when h.mainhead='M' and ( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL ,if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1))) and ((main_supp_flag=0 AND clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0 ) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) OR ( lastorder like '%Not Reached%' OR lastorder like '%Case Not Receive%' OR lastorder like '%Heard & Reserved%' ) OR head_code=5) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,469) union select fil_no as diary_no from rgo_default where remove_def!='Y' union select distinct(advocate.diary_no) from advocate join main on advocate.diary_no=main.diary_no  where display='Y' and advocate_id in ( select bar_id from bar where isdead='Y')) then m.diary_no end ) green_mh,
count(distinct case when h.mainhead='F' and ( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL ,if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1))) and ((main_supp_flag=0 AND clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0 ) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) OR ( lastorder like '%Not Reached%' OR lastorder like '%Case Not Receive%' OR lastorder like '%Heard & Reserved%' ) OR head_code=5) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,469) union select fil_no as diary_no from rgo_default where remove_def!='Y' union select distinct(advocate.diary_no) from advocate join main on advocate.diary_no=main.diary_no  where display='Y' and advocate_id in ( select bar_id from bar where isdead='Y')) then m.diary_no end ) green_fh,
count(distinct case when ((h.main_supp_flag=3 and h.usercode in(559,469)) or rd.remove_def !='Y') and (m.diary_no not in(select distinct(advocate.diary_no) from advocate join main on advocate.diary_no=main.diary_no  where display='Y' and advocate_id in ( select bar_id from bar where isdead='Y'))) then m.diary_no end) yellow,
count(distinct case when h.mainhead='M' and ((h.main_supp_flag=3 and h.usercode in(559,469)) or rd.remove_def !='Y') and (m.diary_no not in(select distinct(advocate.diary_no) from advocate join main on advocate.diary_no=main.diary_no  where display='Y' and advocate_id in ( select bar_id from bar where isdead='Y'))) then m.diary_no end)  yellow_mh,
count(distinct case when h.mainhead='F' and ((h.main_supp_flag=3 and h.usercode in(559,469)) or rd.remove_def !='Y') and (m.diary_no not in(select distinct(advocate.diary_no) from advocate join main on advocate.diary_no=main.diary_no  where display='Y' and advocate_id in ( select bar_id from bar where isdead='Y'))) then m.diary_no end)yellow_fh    
FROM `main` m INNER JOIN casetype c ON c.casecode=if ( (m.active_casetype_id!=null or m.active_casetype_id!=0), m.active_casetype_id,casetype_id) left JOIN heardt h ON m.diary_no = h.diary_no LEFT JOIN users a on m.dacode = a.usercode left join usertype ut on ut.id=a.usertype  left join rgo_default rd on m.diary_no=rd.fil_no LEFT JOIN usersection b ON b.id = a.section LEFT JOIN subheading s ON h.subhead = s.stagecode WHERE c_status = 'P' $condition group by empid,dacode,name,type_name,section_name order by section_name,type_name desc,total";
*/
       $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result_array();
    }


    function da_rog_cases($category,$dacode){
        switch ($category)
        {
            /* sql including matters in which advocate has expired and alternate arrangement is pending
            case 't':{$condition=" "; break;}
            case 'r':{$condition=" and ((if(tentative_cl_dt!='0000-00-00',DATEDIFF(h.tentative_cl_dt,date(now()))<2,1=1) and !( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL , if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1)) and (main_supp_flag=0 AND h.clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) ) and ( lastorder not like '%Not Reached%' and lastorder not like '%Case Not Receive%' and lastorder not like '%Heard & Reserved%' OR lastorder IS NULL ) and (head_code!=5 OR head_code IS NULL) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,469) union select fil_no as diary_no from rgo_default where remove_def!='Y')) or(m.diary_no in(select distinct(advocate.diary_no) from advocate join main on advocate.diary_no=main.diary_no where display='Y' and advocate_id in ( select bar_id from bar where isdead='Y'))))"; break;}
            case 'o':{$condition=" and DATEDIFF(h.tentative_cl_dt,date(now()))>1 and !( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL ,  if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1)) and (main_supp_flag=0 AND h.clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) ) and ( lastorder not like '%Not Reached%' and lastorder not like '%Case Not Receive%' and lastorder not like '%Heard & Reserved%' OR lastorder IS NULL ) and (head_code!=5 OR head_code IS NULL) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,469) union select fil_no as diary_no from rgo_default where remove_def!='Y' union select distinct(advocate.diary_no) from advocate join main on advocate.diary_no=main.diary_no  where display='Y' and advocate_id in ( select bar_id from bar where isdead='Y')) "; break;}
            case 'g':{$condition=" and ( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL ,if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1))) and ((main_supp_flag=0 AND h.clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0 ) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) OR ( lastorder like '%Not Reached%' OR lastorder like '%Case Not Receive%' OR lastorder like '%Heard & Reserved%' ) OR head_code=5) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,469) union select fil_no as diary_no from rgo_default where remove_def!='Y' union select distinct(advocate.diary_no) from advocate join main on advocate.diary_no=main.diary_no  where display='Y' and advocate_id in ( select bar_id from bar where isdead='Y'))"; break;}
            case 'y':{$condition=" and ((h.main_supp_flag=3 and h.usercode in(559,469)) or rd.remove_def !='Y') and (m.diary_no not in(select distinct(advocate.diary_no) from advocate join main on advocate.diary_no=main.diary_no  where display='Y' and advocate_id in ( select bar_id from bar where isdead='Y')))";break;}
            case 'd': {$condition=" and m.diary_no not in  (
    select m.diary_no from main m left join heardt h on m.diary_no=h.diary_no LEFT JOIN subheading s ON h.subhead = s.stagecode where c_status='P' and dacode=".$dacode." and ((if(tentative_cl_dt!='0000-00-00',DATEDIFF(h.tentative_cl_dt,date(now()))<2,1=1) and !( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL , if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1)) and (main_supp_flag=0 AND h.clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) ) and ( lastorder not like '%Not Reached%' and lastorder not like '%Case Not Receive%' and lastorder not like '%Heard & Reserved%' OR lastorder IS NULL ) and (head_code!=5 OR head_code IS NULL) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,469) union select fil_no as diary_no from rgo_default where remove_def!='Y')) or(m.diary_no in(select distinct(advocate.diary_no) from advocate join main on advocate.diary_no=main.diary_no where display='Y' and advocate_id in ( select bar_id from bar where isdead='Y')))) 
    union all
    select m.diary_no from main m left join heardt h on m.diary_no=h.diary_no LEFT JOIN subheading s ON h.subhead = s.stagecode where c_status='P' and dacode=".$dacode." and DATEDIFF(h.tentative_cl_dt,date(now()))>1 and !( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL ,  if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1)) and (main_supp_flag=0 AND h.clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) ) and ( lastorder not like '%Not Reached%' and lastorder not like '%Case Not Receive%' and lastorder not like '%Heard & Reserved%' OR lastorder IS NULL ) and (head_code!=5 OR head_code IS NULL) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,469) union select fil_no as diary_no from rgo_default where remove_def!='Y' union select distinct(advocate.diary_no) from advocate join main on advocate.diary_no=main.diary_no  where display='Y' and advocate_id in ( select bar_id from bar where isdead='Y')) 
    union all
    select m.diary_no from main m left join heardt h on m.diary_no=h.diary_no LEFT JOIN subheading s ON h.subhead = s.stagecode where c_status='P' and dacode=".$dacode." and ( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL ,if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1))) and ((main_supp_flag=0 AND h.clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0 ) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) OR ( lastorder like '%Not Reached%' OR lastorder like '%Case Not Receive%' OR lastorder like '%Heard & Reserved%' ) OR head_code=5) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,469) union select fil_no as diary_no from rgo_default where remove_def!='Y' union select distinct(advocate.diary_no) from advocate join main on advocate.diary_no=main.diary_no  where display='Y' and advocate_id in ( select bar_id from bar where isdead='Y'))
    union all
    select m.diary_no from main m left join heardt h on m.diary_no=h.diary_no where c_status='P' and dacode=".$dacode." and ((h.main_supp_flag=3 and h.usercode in(559,469)) or rd.remove_def !='Y') and (m.diary_no not in(select distinct(advocate.diary_no) from advocate join main on advocate.diary_no=main.diary_no  where display='Y' and advocate_id in ( select bar_id from bar where isdead='Y')))"; break;}
        */

            case 't':{$condition=" "; break;}
            case 'r':{$condition=" and if(tentative_cl_dt!='0000-00-00',DATEDIFF(h.tentative_cl_dt,date(now()))<2,1=1) and !( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL , if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1)) and (main_supp_flag=0 AND h.clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) ) and ( lastorder not like '%Not Reached%' and lastorder not like '%Case Not Receive%' and lastorder not like '%Heard & Reserved%'  OR lastorder IS NULL ) and (head_code!=5 OR head_code IS NULL) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,146,744,747,469,1485,742,1486,935,757,49,762) union select fil_no as diary_no from rgo_default where remove_def!='Y')"; break;}
            case 'o':{$condition=" and DATEDIFF(h.tentative_cl_dt,date(now()))>1 and !( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL ,  if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1)) and (main_supp_flag=0 AND h.clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) ) and ( lastorder not like '%Not Reached%' and lastorder not like '%Case Not Receive%' and lastorder not like '%Heard & Reserved%' OR lastorder IS NULL ) and (head_code!=5 OR head_code IS NULL) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,146,744,747,469,1485,742,1486,935,757,49,762) union select fil_no as diary_no from rgo_default where remove_def!='Y') "; break;}
            case 'g':{$condition=" and ( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL ,if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1))) and ((main_supp_flag=0 AND h.clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0 ) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) OR ( lastorder like '%Not Reached%' OR lastorder like '%Case Not Receive%' ) OR head_code=5) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,146,744,747,469,1485,742,1486,935,757,49,762) union select fil_no as diary_no from rgo_default where remove_def!='Y' )"; break;}
            case 'y':{$condition=" and ((h.main_supp_flag=3 and h.usercode in(559,146,744,747,469,1485,742,1486,935,757,49,762)) or rd.remove_def !='Y' OR lastorder like '%Heard & Reserved%' )";break;}
            case 'd': {$condition=" and m.diary_no not in  (
    select m.diary_no from main m left join heardt h on m.diary_no=h.diary_no LEFT JOIN subheading s ON h.subhead = s.stagecode where c_status='P' and dacode=".$dacode." and if(tentative_cl_dt!='0000-00-00',DATEDIFF(h.tentative_cl_dt,date(now()))<2,1=1) and !( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL , if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1)) and (main_supp_flag=0 AND h.clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) ) and ( lastorder not like '%Not Reached%' and lastorder not like '%Case Not Receive%' and lastorder not like '%Heard & Reserved%'  OR lastorder IS NULL ) and (head_code!=5 OR head_code IS NULL) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,146,744,747,469,1485,742,1486,935,757,49,762) union select fil_no as diary_no from rgo_default where remove_def!='Y') 
    union all
    select m.diary_no from main m left join heardt h on m.diary_no=h.diary_no LEFT JOIN subheading s ON h.subhead = s.stagecode where c_status='P' and dacode=".$dacode." and DATEDIFF(h.tentative_cl_dt,date(now()))>1 and !( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL ,  if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1)) and (main_supp_flag=0 AND h.clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) ) and ( lastorder not like '%Not Reached%' and lastorder not like '%Case Not Receive%' and lastorder not like '%Heard & Reserved%' OR lastorder IS NULL ) and (head_code!=5 OR head_code IS NULL) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,146,744,747,469,1485,742,1486,935,757,49,762) union select fil_no as diary_no from rgo_default where remove_def!='Y') 
    union all
    select m.diary_no from main m left join heardt h on m.diary_no=h.diary_no LEFT JOIN subheading s ON h.subhead = s.stagecode where c_status='P' and dacode=".$dacode." and ( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL ,if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1))) and ((main_supp_flag=0 AND h.clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0 ) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) OR ( lastorder like '%Not Reached%' OR lastorder like '%Case Not Receive%') OR head_code=5) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,146,744,747,469,1485,742,1486,935,757,49,762) union select fil_no as diary_no from rgo_default where remove_def!='Y')
    union all
    select m.diary_no from main m left join heardt h on m.diary_no=h.diary_no where c_status='P' and dacode=".$dacode." and ((h.main_supp_flag=3 and h.usercode in(559,146,744,747,469,1485,742,1486,935,757,49,762)) or rd.remove_def !='Y'))"; break;}
        }
        $sql = "select distinct active_fil_no,m.diary_no, tentative_section(m.diary_no) Section
                ,reg_no_display,pet_name,res_name,state.name,
                Case when (m.mf_active = 'M' OR (m.mf_active = 'F' AND crh.head = 24)) then tentative_cl_dt END as tentative,
                next_dt as next,
                CASE WHEN h.board_type='J' THEN 'Court'
					 WHEN h.board_type='C' THEN 'Chamber'
					 WHEN h.board_type ='R' THEN 'Registrar'
					END AS board_type,
                    GROUP_CONCAT(distinct concat(crh.head,' ',crm.head_content) SEPARATOR ',') As Rmrk_Disp from main m 
                INNER JOIN casetype c ON c.casecode=if ( (m.active_casetype_id!=null or m.active_casetype_id!=0), m.active_casetype_id,casetype_id) 
                left join heardt h on m.diary_no=h.diary_no  
                left join state state on m.ref_agency_state_id=state.id_no
                LEFT JOIN subheading s ON h.subhead = s.stagecode 
                left join rgo_default rd on m.diary_no=rd.fil_no
                left join case_remarks_multiple crm ON crm.diary_no=m.diary_no AND crm.cl_date=(select Max(cl_date) from case_remarks_multiple where diary_no=m.diary_no)
                left join case_remarks_head crh ON crh.sno=crm.r_head AND (crh.display = 'Y' or crh.display is null)
                where c_status='P'  and  dacode=".$dacode.$condition.
            " group by m.diary_no order by Section,active_reg_year,cast(substring(active_fil_no,1,2) as unsigned),cast(substring(active_fil_no,4,6) as unsigned)";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result_array();
    }



    function show_da_wise_report($emp_id){
        $sql = "select empid,dacode,name,type_name,section_name,count(distinct total) as total,count(distinct red) as red,count(distinct orange) as orange,count(distinct green) as green,count(distinct yellow) as yellow from
(select empid,dacode,name,type_name,section_name, m.diary_no as total,
case when if(tentative_cl_dt!='0000-00-00',DATEDIFF(h.tentative_cl_dt,date(now()))<2,1=1) and !( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL , if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1)) and (main_supp_flag=0 AND h.clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) ) and ( lastorder not like '%Not Reached%' and lastorder not like '%Case Not Receive%' and lastorder not like '%Heard & Reserved%'  OR lastorder IS NULL ) and (head_code!=5 OR head_code IS NULL) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,146,744,747,469,1485,742,1486,935,757,49,762) union select fil_no as diary_no from rgo_default where remove_def!='Y') then m.diary_no end as red,
case when DATEDIFF(h.tentative_cl_dt,date(now()))>1 and !( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL ,  if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1)) and (main_supp_flag=0 AND h.clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) ) and ( lastorder not like '%Not Reached%' and lastorder not like '%Case Not Receive%' and lastorder not like '%Heard & Reserved%' OR lastorder IS NULL ) and (head_code!=5 OR head_code IS NULL) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,146,744,747,469,1485,742,1486,935,757,49,762) union select fil_no as diary_no from rgo_default where remove_def!='Y') then m.diary_no end as orange,
case when ( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL ,if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1))) and ((main_supp_flag=0 AND h.clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0 ) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) OR ( lastorder like '%Not Reached%' OR lastorder like '%Case Not Receive%') OR head_code=5) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,146,744,747,469,1485,742,1486,935,757,49,762) union select fil_no as diary_no from rgo_default where remove_def!='Y') then m.diary_no end as green,
case when (h.main_supp_flag=3 and h.usercode in(559,146,744,747,469,1485,742,1486,935,757,49,762)) or rd.remove_def !='Y' OR lastorder like '%Heard & Reserved%' then m.diary_no end as yellow
 from main  m 
 inner JOIN casetype c ON c.casecode =if ( (m.active_casetype_id!=null or m.active_casetype_id!=0), m.active_casetype_id,casetype_id) left join rgo_default rd on m.diary_no=rd.fil_no left JOIN heardt h ON m.diary_no = h.diary_no 
 left JOIN users user on m.dacode = user.usercode left join usertype ut on ut.id=user.usertype 
 left JOIN usersection b ON b.id = user.section LEFT JOIN subheading s ON h.subhead = s.stagecode LEFT JOIN case_remarks_head u ON m.lastorder LIKE CONCAT( '%', if( u.pending_text = '', u.head, u.pending_text ) , '%' ) where  c_status='P' and
 user.empid=".$emp_id." ) a group by empid,dacode,name,type_name,section_name order by section_name,type_name desc,total";
        /*  SQL including matters in which advocate has expired and alternate arrangement is pending
        $sql="SELECT m.dacode,b.section_name,a.name,type_name,a.empid,count(distinct m.diary_no) total,
 count(distinct case when ((if(tentative_cl_dt!='0000-00-00',DATEDIFF(h.tentative_cl_dt,date(now()))<2,1=1) and !( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL , if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1)) and (main_supp_flag=0 AND clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) ) and ( lastorder not like '%Not Reached%' and lastorder not like '%Case Not Receive%' and lastorder not like '%Heard & Reserved%'  OR lastorder IS NULL ) and (head_code!=5 OR head_code IS NULL) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,469) union select fil_no as diary_no from rgo_default where remove_def!='Y')) or(m.diary_no in(select distinct(advocate.diary_no) from advocate join main on advocate.diary_no=main.diary_no  where display='Y' and advocate_id in ( select bar_id from bar where isdead='Y')))) then m.diary_no end ) red,
 count(distinct case when DATEDIFF(h.tentative_cl_dt,date(now()))>1 and !( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL ,  if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1)) and (main_supp_flag=0 AND clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) ) and ( lastorder not like '%Not Reached%' and lastorder not like '%Case Not Receive%' and lastorder not like '%Heard & Reserved%' OR lastorder IS NULL ) and (head_code!=5 OR head_code IS NULL) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,469) union select fil_no as diary_no from rgo_default where remove_def!='Y' union select distinct(advocate.diary_no) from advocate join main on advocate.diary_no=main.diary_no  where display='Y' and advocate_id in ( select bar_id from bar where isdead='Y')) then m.diary_no end ) orange,
 count(distinct case when ( if(h.mainhead='M',s.listtype='M' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL ,if(h.mainhead='S',s.listtype='S' AND s.listtype IS NOT NULL and s.display='Y' AND s.display IS NOT NULL, 1=1))) and ((main_supp_flag=0 AND clno =0 AND brd_slno =0 AND (judges='' OR judges=0) and roster_id=0 ) OR (next_dt!='0000-00-00' and next_dt >= date(now()) ) OR ( lastorder like '%Not Reached%' OR lastorder like '%Case Not Receive%' OR lastorder like '%Heard & Reserved%' ) OR head_code=5) and m.diary_no not in (select diary_no from heardt where main_supp_flag=3 and usercode in(559,469) union select fil_no as diary_no from rgo_default where remove_def!='Y' union select distinct(advocate.diary_no) from advocate join main on advocate.diary_no=main.diary_no  where display='Y' and advocate_id in ( select bar_id from bar where isdead='Y'))  then m.diary_no end ) green,
 count(distinct case when ((h.main_supp_flag=3 and h.usercode in(559,469)) or rd.remove_def !='Y') and (m.diary_no not in(select distinct(advocate.diary_no) from advocate join main on advocate.diary_no=main.diary_no  where display='Y' and advocate_id in ( select bar_id from bar where isdead='Y'))) then m.diary_no end) yellow
 FROM `main` m INNER JOIN casetype c ON c.casecode=if ( (m.active_casetype_id!=null or m.active_casetype_id!=0), m.active_casetype_id,casetype_id) left JOIN heardt h ON m.diary_no = h.diary_no LEFT JOIN users a on m.dacode = a.usercode left join usertype ut on ut.id=a.usertype  left join rgo_default rd on m.diary_no=rd.fil_no LEFT JOIN usersection b ON b.id = a.section LEFT JOIN subheading s ON h.subhead = s.stagecode WHERE c_status = 'P' and a.empid=".$emp_id." group by empid,dacode,name,type_name,section_name order by section_name,type_name desc,total";
        */
        $query = $this->db->query($sql);
         //echo $this->db->last_query();
         return $query->result_array();
     }


     function causelist_info($courtNo,$itemNo)
     {
         $date=date('Y-m-d');
         $sql="select remark,reg_no_display,brd_slno,conn,list,cause_title,group_concat(advocates) as advocates from
 (select distinct bd.remark as remark,reg_no_display,brd_slno,
 case when (hd.diary_no=hd.conn_key and hd.conn_key!='') then 'M' else 'C' end as conn,
 case when board_type='C' then 'Chamber' else case when board_type='J' then 'Judge' else case when board_type='R' then 'Registrar' end end end as list,
                                            concat( m.pet_name,' Vs ',m.res_name) as cause_title,
                                            concat('[',b.aor_code,'] ',b.name,'(',pet_res,')') as advocates
                                             from heardt hd left join brdrem bd on hd.diary_no=bd.diary_no 
                                             left join main m on hd.diary_no=m.diary_no 
                                             left join roster_judge rj on hd.roster_id=rj.roster_id 
                                             left join roster r on rj.roster_id=r.id 
                                             left join advocate adv on hd.diary_no=adv.diary_no
                                             left join bar b on adv.advocate_id=b.bar_id
                                             where hd.next_dt='$date' and brd_slno=$itemNo and courtno=$courtNo) a group by reg_no_display order by 4 desc";
      $query = $this->db->query($sql);
         //echo $this->db->last_query();
         return $query->result_array();
     }



 function getCurrentPendency($mainReportID=null,$subReportID=null,$from_date=null,$to_date=null,$id=null,$reportType1=null)
     {
         //echo $id;
         /* this function again added on 01.08.2018*/
        switch($id)
        {
            case 1:
            {
                ## A & E Registered pending matters
                $sql="SELECT
                        sum( case when (mf_active='M' or mf_active is null or mf_active ='') then 1 else 0 end) as Misc_Side_Pendency,
                        sum( case when mf_active='F' then 1 else 0 end) as Regular_Side_Pendency
                        FROM main m
                        WHERE c_status = 'P'
                        AND (active_fil_no IS NOT NULL AND active_fil_no != '')";
                break;
            }
            case 2:
            {
                ## B Un-registered matters
                $sql="select count(distinct diary_no) total from main m inner join
                        (select case when os.diary_no is null then m.diary_no else 0 end as dd from main m
                         inner JOIN docdetails b ON m.diary_no = b.diary_no
                         left outer join
                        (select distinct diary_no from obj_save where
                        (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y')
                        os on m.diary_no=os.diary_no
                         where  c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                        AND((doccode = '8'
                        AND doccode1 = '28'
                        ) || ( doccode = '8'
                        AND doccode1 = '95' ) || ( doccode = '8'
                        AND doccode1 = '214' ) || ( doccode = '8'
                        AND doccode1 = '215' )
                        )
                        AND b.iastat='P') aa on m.diary_no=aa.dd";
                break;


            }
            case 3:
            {
                ##C
                $sql="select count(distinct diary_no) total from main m inner join
                    (select case when os.diary_no is null then m.diary_no else 0 end as dd from main m
                     inner JOIN docdetails b ON m.diary_no = b.diary_no
                     left outer join
                    (select distinct diary_no from obj_save where
                    (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y')
                    os on m.diary_no=os.diary_no
                     where  c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                    AND((doccode = '8'
                    AND doccode1 = '16'
                    ) || ( doccode = '8'
                    AND doccode1 = '79' ) || ( doccode = '8'
                    AND doccode1 = '99' ) || ( doccode = '8'
                    AND doccode1 = '300' )
                    )
                    AND b.iastat='P') aa on m.diary_no=aa.dd";
                break;
            }
            case 4:
            {
                ## D Diary Number which are defective but are listed before courts

                $sql="select sum(case when os.diary_no is not null then 1 else 0 end) DTotal
                    FROM main m inner join heardt h on m.diary_no=h.diary_no
                    left outer join
                    (select distinct diary_no from obj_save where
                    (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y')
                    os on m.diary_no=os.diary_no
                    WHERE c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                     and h.board_type='J'";
                break;
            }
            case 5:
            {
                ## E-1
                $sql="select count(*) total
                        FROM main m inner join docdetails b on m.diary_no=b.diary_no
                        inner join
                        (select distinct diary_no from obj_save where
                        (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y' and DATEDIFF(now(),save_dt)>60) os
                        on m.diary_no=os.diary_no
                        WHERE  m.c_status = 'P' and (m.active_fil_no is null or  m.active_fil_no='')
                        AND doccode = '8' AND doccode1 = '226' AND b.iastat='P'";
                break;
            }
            case 6:
            {
                #E-2
                $sql="select count(*) total
                        FROM main m inner join docdetails b on m.diary_no=b.diary_no
                        inner join
                        (select distinct diary_no from obj_save where
                        (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y' and DATEDIFF(now(),save_dt)<=60) os
                        on m.diary_no=os.diary_no
                        WHERE  m.c_status = 'P' and (m.active_fil_no is null or  m.active_fil_no='')
                        AND doccode = '8' AND doccode1 = '226' AND b.iastat='P'";
              break;
            }
            case 7:
            {
                ## A details Matters
                 $sql="SELECT distinct
                    SUBSTR(m.diary_no,
                        1,
                        LENGTH(m.diary_no) - 4) AS diary_no,
                    SUBSTR(m.diary_no, - 4) AS diary_year,
                    DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                    m.reg_no_display,
                    m.pet_name,
                    m.res_name,
                     us.section_name AS user_section,
                    u.name alloted_to_da,
                    h.next_dt,
                    CASE
                        WHEN h.main_supp_flag = 0 THEN 'Ready'
                        ELSE 'Not Ready'
                    END AS 'Status',
                    m.mf_active ,
                    CASE
                        WHEN
                            (m.conn_key = 0 OR m.conn_key IS NULL
                                OR m.conn_key = m.diary_no)
                        THEN
                            'M'
                        ELSE CASE
                            WHEN
                                (m.conn_key != 0
                                    AND m.conn_key IS NOT NULL
                                    AND m.conn_key != m.diary_no)
                            THEN
                                'C'
                        END
                    END AS mainorconn
                FROM
                    main m
                    left outer join heardt h ON m.diary_no = h.diary_no
                        LEFT JOIN
                    users u ON u.usercode = m.dacode
                        AND u.display = 'Y'
                        LEFT JOIN
                    usersection us ON us.id = u.section
                WHERE
                    c_status = 'P'
                        AND (active_fil_no IS not NULL and active_fil_no != '')
                        and (m.mf_active='M' or m.mf_active is null or m.mf_active='')";
                break;
            }
            case 8:
            {
                ##B Deatils
                $sql="SELECT DISTINCT
                    us.section_name AS user_section,
                    u.name alloted_to_da,
                    SUBSTR(m.diary_no,
                        1,
                        LENGTH(m.diary_no) - 4) AS diary_no,
                    SUBSTR(m.diary_no, - 4) AS diary_year,
                    DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                    m.pet_name,
                    m.res_name,
                    h.next_dt,
                    CASE
                        WHEN h.main_supp_flag = 0 THEN 'Ready'
                        ELSE 'Not Ready'
                    END AS 'Status',
                    m.mf_active,
                    CASE
                        WHEN
                            (m.conn_key = 0 OR m.conn_key IS NULL
                                OR m.conn_key = m.diary_no)
                        THEN
                            'M'
                        ELSE CASE
                            WHEN
                                (m.conn_key != 0
                                    AND m.conn_key IS NOT NULL
                                    AND m.conn_key != m.diary_no)
                            THEN
                                'C'
                        END
                    END AS mainorconn
                FROM
                    main m left join
                    heardt h
                         ON m.diary_no = h.diary_no
                        LEFT JOIN
                    users u ON u.usercode = m.dacode
                        AND (u.display = 'Y' or u.display is null)
                        LEFT JOIN
                    usersection us ON us.id = u.section
                WHERE
                         m.diary_no in
                        (select distinct diary_no from main m inner join
                (select case when os.diary_no is null then m.diary_no else 0 end as dd from main m
                 inner JOIN docdetails b ON m.diary_no = b.diary_no
                 left outer join
                (select distinct diary_no from obj_save where
                (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y')
                os on m.diary_no=os.diary_no
                 where  c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                AND((doccode = '8'
                AND doccode1 = '28'
                ) || ( doccode = '8'
                AND doccode1 = '95' ) || ( doccode = '8'
                AND doccode1 = '214' ) || ( doccode = '8'
                AND doccode1 = '215' )
                )
                AND b.iastat='P') aa on m.diary_no=aa.dd)";
                break;
            }
            case 9:
            {
                $sql="SELECT distinct
                        us.section_name AS user_section,
                        u.name alloted_to_da,
                        SUBSTR(m.diary_no,
                            1,
                            LENGTH(m.diary_no) - 4) AS diary_no,
                        SUBSTR(m.diary_no, - 4) AS diary_year,
                        DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                        m.reg_no_display,
                        m.pet_name,
                        m.res_name,
                        h.next_dt,
                        CASE
                            WHEN h.main_supp_flag = 0 THEN 'Ready'
                            ELSE 'Not Ready'
                        END AS 'Status',
                        m.mf_active,
                        CASE
                            WHEN
                                (m.conn_key = 0 OR m.conn_key IS NULL
                                    OR m.conn_key = m.diary_no)
                            THEN
                                'M'
                            ELSE CASE
                                WHEN
                                    (m.conn_key != 0
                                        AND m.conn_key IS NOT NULL
                                        AND m.conn_key != m.diary_no)
                                THEN
                                    'C'
                            END
                        END AS mainorconn
                    FROM
                        main m
                        left outer join heardt h ON m.diary_no = h.diary_no
                            LEFT JOIN
                        users u ON u.usercode = m.dacode
                            AND u.display = 'Y'
                            LEFT JOIN
                        usersection us ON us.id = u.section
                    WHERE
                        c_status = 'P'
                            AND (active_fil_no IS not NULL and active_fil_no != '')
                            and m.mf_active='F'";
                break;

            }
            case 10:
            {
                $sql="SELECT distinct
                        us.section_name AS user_section,
                        u.name alloted_to_da,
                        SUBSTR(m.diary_no,
                            1,
                            LENGTH(m.diary_no) - 4) AS diary_no,
                        SUBSTR(m.diary_no, - 4) AS diary_year,
                        DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                        m.reg_no_display,
                        m.pet_name,
                        m.res_name,
                        h.next_dt,
                        CASE
                            WHEN h.main_supp_flag = 0 THEN 'Ready'
                            ELSE 'Not Ready'
                        END AS 'Status',
                        m.mf_active,
                        CASE
                            WHEN
                                (m.conn_key = 0 OR m.conn_key IS NULL
                                    OR m.conn_key = m.diary_no)
                            THEN
                                'M'
                            ELSE CASE
                                WHEN
                                    (m.conn_key != 0
                                        AND m.conn_key IS NOT NULL
                                        AND m.conn_key != m.diary_no)
                                THEN
                                    'C'
                            END
                        END AS mainorconn
                    FROM
                        main m
                        inner join heardt h ON m.diary_no = h.diary_no
                            LEFT JOIN
                        users u ON u.usercode = m.dacode
                            AND u.display = 'Y'
                            LEFT JOIN
                        usersection us ON us.id = u.section
                        left outer join
                    (select distinct diary_no from obj_save where
                    (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y')
                    os on m.diary_no=os.diary_no
                    WHERE
                        c_status = 'P' and
                            (fil_no is null or  fil_no='')
                            and os.diary_no is not null
                            and h.board_type='J'
                    ";
                break;
            }
            case 11:
                {
                    $sql="SELECT DISTINCT
                        us.section_name AS user_section,
                        u.name alloted_to_da,
                        SUBSTR(m.diary_no,
                            1,
                            LENGTH(m.diary_no) - 4) AS diary_no,
                        SUBSTR(m.diary_no, - 4) AS diary_year,
                        DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                        m.pet_name,
                        m.res_name,
                        h.next_dt,
                        CASE
                            WHEN h.main_supp_flag = 0 THEN 'Ready'
                            ELSE 'Not Ready'
                        END AS 'Status',
                        m.mf_active,
                        CASE
                            WHEN
                                (m.conn_key = 0 OR m.conn_key IS NULL
                                    OR m.conn_key = m.diary_no)
                            THEN
                                'M'
                            ELSE CASE
                                WHEN
                                    (m.conn_key != 0
                                        AND m.conn_key IS NOT NULL
                                        AND m.conn_key != m.diary_no)
                                THEN
                                    'C'
                            END
                        END AS mainorconn
                    FROM
                        main m left join
                        heardt h
                             ON m.diary_no = h.diary_no
                            LEFT JOIN
                        users u ON u.usercode = m.dacode
                            AND (u.display = 'Y' or u.display is null)
                            LEFT JOIN
                        usersection us ON us.id = u.section
                    WHERE
                             m.diary_no in
                            (select distinct diary_no from main m inner join
                    (select case when os.diary_no is null then m.diary_no else 0 end as dd from main m
                     inner JOIN docdetails b ON m.diary_no = b.diary_no
                     left outer join
                    (select distinct diary_no from obj_save where
                    (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y')
                    os on m.diary_no=os.diary_no
                     where  c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                    AND((doccode = '8'
                    AND doccode1 = '16'
                    ) || ( doccode = '8'
                    AND doccode1 = '79' ) || ( doccode = '8'
                    AND doccode1 = '99' ) || ( doccode = '8'
                    AND doccode1 = '300' )
                    )
                    AND b.iastat='P') aa on m.diary_no=aa.dd)";
                   break;
                }
            case 12:
            {
                $sql="SELECT DISTINCT
                    us.section_name AS user_section,
                    u.name alloted_to_da,
                    SUBSTR(m.diary_no,
                        1,
                        LENGTH(m.diary_no) - 4) AS diary_no,
                    SUBSTR(m.diary_no, - 4) AS diary_year,
                    DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                    m.pet_name,
                    m.res_name,
                    h.next_dt,
                    CASE
                        WHEN h.main_supp_flag = 0 THEN 'Ready'
                        ELSE 'Not Ready'
                    END AS 'Status',
                    m.mf_active,
                    CASE
                        WHEN
                            (m.conn_key = 0 OR m.conn_key IS NULL
                                OR m.conn_key = m.diary_no)
                        THEN
                            'M'
                        ELSE CASE
                            WHEN
                                (m.conn_key != 0
                                    AND m.conn_key IS NOT NULL
                                    AND m.conn_key != m.diary_no)
                            THEN
                                'C'
                        END
                    END AS mainorconn
                FROM
                    main m left join
                    heardt h
                         ON m.diary_no = h.diary_no
                        LEFT JOIN
                    users u ON u.usercode = m.dacode
                        AND (u.display = 'Y' or u.display is null)
                        LEFT JOIN
                    usersection us ON us.id = u.section
                    inner join docdetails b on m.diary_no=b.diary_no
                inner join
                (select distinct diary_no from obj_save where
                (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y' and DATEDIFF(now(),save_dt)>60) os
                on m.diary_no=os.diary_no
                WHERE  m.c_status = 'P' and (m.active_fil_no is null or  m.active_fil_no='')
                AND doccode = '8' AND doccode1 = '226' AND b.iastat='P'";
                break;
            }
            case 13:
            {
                $sql="SELECT DISTINCT
                            us.section_name AS user_section,
                            u.name alloted_to_da,
                            SUBSTR(m.diary_no,
                                1,
                                LENGTH(m.diary_no) - 4) AS diary_no,
                            SUBSTR(m.diary_no, - 4) AS diary_year,
                            DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                            m.pet_name,
                            m.res_name,
                            h.next_dt,
                            CASE
                                WHEN h.main_supp_flag = 0 THEN 'Ready'
                                ELSE 'Not Ready'
                            END AS 'Status',
                            m.mf_active,
                            CASE
                                WHEN
                                    (m.conn_key = 0 OR m.conn_key IS NULL
                                        OR m.conn_key = m.diary_no)
                                THEN
                                    'M'
                                ELSE CASE
                                    WHEN
                                        (m.conn_key != 0
                                            AND m.conn_key IS NOT NULL
                                            AND m.conn_key != m.diary_no)
                                    THEN
                                        'C'
                                END
                            END AS mainorconn
                        FROM
                            main m left join
                            heardt h
                                 ON m.diary_no = h.diary_no
                                LEFT JOIN
                            users u ON u.usercode = m.dacode
                                AND (u.display = 'Y' or u.display is null)
                                LEFT JOIN
                            usersection us ON us.id = u.section
                            inner join docdetails b on m.diary_no=b.diary_no
                        inner join
                        (select distinct diary_no from obj_save where
                        (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y' and DATEDIFF(now(),save_dt)<=60) os
                        on m.diary_no=os.diary_no
                        WHERE  m.c_status = 'P' and (m.active_fil_no is null or  m.active_fil_no='')
                        AND doccode = '8' AND doccode1 = '226' AND b.iastat='P'";
                break;
            }
            case 14:
            {
                //F11
                $sql="select sum(case when os.diary_no is null then 1 else 0 end) total
                        FROM main m
                        left outer join
                        (select distinct diary_no from obj_save where
                        (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y')
                        os on m.diary_no=os.diary_no
                        WHERE c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                        AND m.diary_no not in
                        (
                        select m.diary_no total
                        FROM main m inner join docdetails b on m.diary_no=b.diary_no
                        WHERE  c_status = 'P' and (active_fil_no is null or  active_fil_no='') and
                        ((doccode = '8'
                        AND doccode1 = '16'
                        ) || ( doccode = '8'
                        AND doccode1 = '79' ) || ( doccode = '8'
                        AND doccode1 = '99' ) || ( doccode = '8'
                        AND doccode1 = '300' ) || (doccode = '8'
                        AND doccode1 = '28'
                        ) || ( doccode = '8'
                        AND doccode1 = '95' ) || ( doccode = '8'
                        AND doccode1 = '214' ) || ( doccode = '8'
                        AND doccode1 = '215' )
                        )
                        AND b.iastat='P') and date(diary_no_rec_date)<'2014-08-19'";
                break;
            }
            case 15:
            {
                //F12
                $sql="select sum(case when os.diary_no is null then 1 else 0 end) total
                        FROM main m
                        left outer join
                        (select distinct diary_no from obj_save where
                        (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y')
                        os on m.diary_no=os.diary_no
                        WHERE c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                        AND m.diary_no not in
                        (
                        select m.diary_no total
                        FROM main m inner join docdetails b on m.diary_no=b.diary_no
                        WHERE  c_status = 'P' and (active_fil_no is null or  active_fil_no='') and
                        ((doccode = '8'
                        AND doccode1 = '16'
                        ) || ( doccode = '8'
                        AND doccode1 = '79' ) || ( doccode = '8'
                        AND doccode1 = '99' ) || ( doccode = '8'
                        AND doccode1 = '300' ) || (doccode = '8'
                        AND doccode1 = '28'
                        ) || ( doccode = '8'
                        AND doccode1 = '95' ) || ( doccode = '8'
                        AND doccode1 = '214' ) || ( doccode = '8'
                        AND doccode1 = '215' )
                        )
                        AND b.iastat='P') and date(diary_no_rec_date)>='2014-08-19'";
                break;
            }
            case 16:
            {
                //F21
                $sql="select sum(case when os.diary_no is not null  then 1 else 0 end) total
                    FROM main m 
                    left outer join
                    (select  diary_no,DATEDIFF(now(),max(save_dt)) as days from obj_save where
                    (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y'
                     group by diary_no)
                    os on m.diary_no=os.diary_no and os.days>90
                    WHERE c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                    and m.diary_no not in
					(select m.diary_no
                    FROM main m inner join heardt h on m.diary_no=h.diary_no
                    left outer join
                    (select distinct diary_no from obj_save where
                    (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y')
                    os on m.diary_no=os.diary_no
                    WHERE c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                     and h.board_type='J' and os.diary_no is not null

                     union

                     select m.diary_no
					FROM main m inner join docdetails b on m.diary_no=b.diary_no
					inner join
					(select distinct diary_no from obj_save where
					(rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y') os
					on m.diary_no=os.diary_no
					WHERE  m.c_status = 'P' and (m.active_fil_no is null or  m.active_fil_no='')
					AND doccode = '8' AND doccode1 = '226' AND b.iastat='P')";
                break;
            }
            case 17:
            {
                //F22
                $sql="select sum(case when os.diary_no is not null  then 1 else 0 end) total
                    FROM main m 
                    left outer join
                    (select  diary_no,DATEDIFF(now(),max(save_dt)) as days from obj_save where
                    (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y'
                     group by diary_no)
                    os on m.diary_no=os.diary_no and os.days<=90
                    WHERE c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                    and m.diary_no not in
					(select os.diary_no
                    FROM main m inner join heardt h on m.diary_no=h.diary_no
                    left outer join
                    (select distinct diary_no from obj_save where
                    (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y')
                    os on m.diary_no=os.diary_no
                    WHERE c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                     and h.board_type='J' and os.diary_no is not null
                     union
                     select m.diary_no
					FROM main m inner join docdetails b on m.diary_no=b.diary_no
					inner join
					(select distinct diary_no from obj_save where
					(rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y') os
					on m.diary_no=os.diary_no
					WHERE  m.c_status = 'P' and (m.active_fil_no is null or  m.active_fil_no='')
					AND doccode = '8' AND doccode1 = '226' AND b.iastat='P')";
                break;
            }
           case 18:
            {
                $sql="SELECT DISTINCT
                        us.section_name AS user_section,
                        u.name alloted_to_da,
                        SUBSTR(m.diary_no,
                            1,
                            LENGTH(m.diary_no) - 4) AS diary_no,
                        SUBSTR(m.diary_no, - 4) AS diary_year,
                        DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                        m.pet_name,
                        m.res_name,
                        h.next_dt,
                        CASE
                            WHEN h.main_supp_flag = 0 THEN 'Ready'
                            ELSE 'Not Ready'
                        END AS 'Status',
                        m.mf_active AS 'Misc or Regular',
                        CASE
                            WHEN
                                (m.conn_key = 0 OR m.conn_key IS NULL
                                    OR m.conn_key = m.diary_no)
                            THEN
                                'M'
                            ELSE CASE
                                WHEN
                                    (m.conn_key != 0
                                        AND m.conn_key IS NOT NULL
                                        AND m.conn_key != m.diary_no)
                                THEN
                                    'C'
                            END
                        END AS mainorconn
                    FROM
                        main m left join
                        heardt h
                             ON m.diary_no = h.diary_no
                             left outer join
                    (select distinct diary_no from obj_save where
                    (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y')
                    os on m.diary_no=os.diary_no
                            LEFT JOIN
                        users u ON u.usercode = m.dacode
                            AND (u.display = 'Y' or u.display is null)
                            LEFT JOIN
                        usersection us ON us.id = u.section
                    WHERE c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                    AND m.diary_no not in
                    (
                    select m.diary_no total
                    FROM main m inner join docdetails b on m.diary_no=b.diary_no
                    WHERE  c_status = 'P' and (active_fil_no is null or  active_fil_no='') and
                    ((doccode = '8'
                    AND doccode1 = '16'
                    ) || ( doccode = '8'
                    AND doccode1 = '79' ) || ( doccode = '8'
                    AND doccode1 = '99' ) || ( doccode = '8'
                    AND doccode1 = '300' ) || (doccode = '8'
                    AND doccode1 = '28'
                    ) || ( doccode = '8'
                    AND doccode1 = '95' ) || ( doccode = '8'
                    AND doccode1 = '214' ) || ( doccode = '8'
                    AND doccode1 = '215' )
                    )
                    AND b.iastat='P') and date(diary_no_rec_date)<'2014-08-19'
                    and os.diary_no is null";
                break;
            }
            case 19:
            {
                $sql="SELECT DISTINCT
                    us.section_name AS user_section,
                    u.name alloted_to_da,
                    SUBSTR(m.diary_no,
                        1,
                        LENGTH(m.diary_no) - 4) AS diary_no,
                    SUBSTR(m.diary_no, - 4) AS diary_year,
                    DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                    m.pet_name,
                    m.res_name,
                    h.next_dt,
                    CASE
                        WHEN h.main_supp_flag = 0 THEN 'Ready'
                        ELSE 'Not Ready'
                    END AS 'Status',
                    m.mf_active AS 'Misc or Regular',
                    CASE
                        WHEN
                            (m.conn_key = 0 OR m.conn_key IS NULL
                                OR m.conn_key = m.diary_no)
                        THEN
                            'M'
                        ELSE CASE
                            WHEN
                                (m.conn_key != 0
                                    AND m.conn_key IS NOT NULL
                                    AND m.conn_key != m.diary_no)
                            THEN
                                'C'
                        END
                    END AS mainorconn
                FROM
                    main m left join
                    heardt h
                         ON m.diary_no = h.diary_no
                         left outer join
                (select distinct diary_no from obj_save where
                (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y')
                os on m.diary_no=os.diary_no
                        LEFT JOIN
                    users u ON u.usercode = m.dacode
                        AND (u.display = 'Y' or u.display is null)
                        LEFT JOIN
                    usersection us ON us.id = u.section
                WHERE c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                AND m.diary_no not in
                (
                select m.diary_no total
                FROM main m inner join docdetails b on m.diary_no=b.diary_no
                WHERE  c_status = 'P' and (active_fil_no is null or  active_fil_no='') and
                ((doccode = '8'
                AND doccode1 = '16'
                ) || ( doccode = '8'
                AND doccode1 = '79' ) || ( doccode = '8'
                AND doccode1 = '99' ) || ( doccode = '8'
                AND doccode1 = '300' ) || (doccode = '8'
                AND doccode1 = '28'
                ) || ( doccode = '8'
                AND doccode1 = '95' ) || ( doccode = '8'
                AND doccode1 = '214' ) || ( doccode = '8'
                AND doccode1 = '215' )
                )
                AND b.iastat='P') and date(diary_no_rec_date)>='2014-08-19'
                and os.diary_no is null";
                break;
            }
            case 20:
            {
                $sql="select count(*) total FROM main m where  c_status = 'P'";
                break;
            }
            case 21:
            {
                $sql="SELECT DISTINCT
                            us.section_name AS user_section,
                            u.name alloted_to_da,
                            SUBSTR(m.diary_no,
                                1,
                                LENGTH(m.diary_no) - 4) AS diary_no,
                            SUBSTR(m.diary_no, - 4) AS diary_year,
                            DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                            m.pet_name,
                            m.res_name,
                            h.next_dt,
                            CASE
                                WHEN h.main_supp_flag = 0 THEN 'Ready'
                                ELSE 'Not Ready'
                            END AS 'Status',
                            m.mf_active AS 'Misc or Regular',
                            CASE
                                WHEN
                                    (m.conn_key = 0 OR m.conn_key IS NULL
                                        OR m.conn_key = m.diary_no)
                                THEN
                                    'M'
                                ELSE CASE
                                    WHEN
                                        (m.conn_key != 0
                                            AND m.conn_key IS NOT NULL
                                            AND m.conn_key != m.diary_no)
                                    THEN
                                        'C'
                                END
                            END AS mainorconn
                        FROM
                            main m left join
                            heardt h
                                 ON m.diary_no = h.diary_no
                                 left outer join
                        (select  diary_no,DATEDIFF(now(),max(save_dt)) as days from obj_save where
                        (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y'
                         group by diary_no)
                        os on m.diary_no=os.diary_no and os.days>90
                                LEFT JOIN
                            users u ON u.usercode = m.dacode
                                AND (u.display = 'Y' or u.display is null)
                                LEFT JOIN
                            usersection us ON us.id = u.section
                        WHERE c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                        and m.diary_no not in
                                  (select m.diary_no
                                            FROM main m inner join heardt h on m.diary_no=h.diary_no
                                            left outer join
                                            (select distinct diary_no from obj_save where
                                            (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y')
                                            os on m.diary_no=os.diary_no
                                            WHERE c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                                             and h.board_type='J' and os.diary_no is not null

                                             union

                                             select m.diary_no
                                  FROM main m inner join docdetails b on m.diary_no=b.diary_no
                                  inner join
                                  (select distinct diary_no from obj_save where
                                  (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y') os
                                  on m.diary_no=os.diary_no
                                  WHERE  m.c_status = 'P' and (m.active_fil_no is null or  m.active_fil_no='')
                                  AND doccode = '8' AND doccode1 = '226' AND b.iastat='P')
                                            and os.diary_no is not null";
                break;
            }
            case 22:
            {
                $sql="SELECT DISTINCT
                        us.section_name AS user_section,
                        u.name alloted_to_da,
                        SUBSTR(m.diary_no,
                            1,
                            LENGTH(m.diary_no) - 4) AS diary_no,
                        SUBSTR(m.diary_no, - 4) AS diary_year,
                        DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                        m.pet_name,
                        m.res_name,
                        h.next_dt,
                        CASE
                            WHEN h.main_supp_flag = 0 THEN 'Ready'
                            ELSE 'Not Ready'
                        END AS 'Status',
                        m.mf_active AS 'Misc or Regular',
                        CASE
                            WHEN
                                (m.conn_key = 0 OR m.conn_key IS NULL
                                    OR m.conn_key = m.diary_no)
                            THEN
                                'M'
                            ELSE CASE
                                WHEN
                                    (m.conn_key != 0
                                        AND m.conn_key IS NOT NULL
                                        AND m.conn_key != m.diary_no)
                                THEN
                                    'C'
                            END
                        END AS mainorconn
                    FROM
                        main m left join
                        heardt h
                             ON m.diary_no = h.diary_no
                             left outer join
                    (select  diary_no,DATEDIFF(now(),max(save_dt)) as days from obj_save where
                    (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y'
                     group by diary_no)
                    os on m.diary_no=os.diary_no and os.days<=90
                            LEFT JOIN
                        users u ON u.usercode = m.dacode
                            AND (u.display = 'Y' or u.display is null)
                            LEFT JOIN
                        usersection us ON us.id = u.section
                    WHERE c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                    and m.diary_no not in
                              (select m.diary_no
                                        FROM main m inner join heardt h on m.diary_no=h.diary_no
                                        left outer join
                                        (select distinct diary_no from obj_save where
                                        (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y')
                                        os on m.diary_no=os.diary_no
                                        WHERE c_status = 'P' and (active_fil_no is null or  active_fil_no='')
                                         and h.board_type='J' and os.diary_no is not null

                                         union

                                         select m.diary_no
                              FROM main m inner join docdetails b on m.diary_no=b.diary_no
                              inner join
                              (select distinct diary_no from obj_save where
                              (rm_dt is null or rm_dt='0000-00-00 00:00:00') and display='Y') os
                              on m.diary_no=os.diary_no
                              WHERE  m.c_status = 'P' and (m.active_fil_no is null or  m.active_fil_no='')
                              AND doccode = '8' AND doccode1 = '226' AND b.iastat='P')
                                        and os.diary_no is not null";
                break;
            }
            default:
                break;
        }


        //echo $sql.'<br><br>';
        $query = $this->db->query($sql);

        if($query -> num_rows() >= 1)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }

    }
    

    function da_details($dacode)
    {
        $sql="SELECT name,type_name,section_name,empid FROM users user left join usersection us on user.section=us.id
left join usertype ut on ut.id=user.usertype where usercode=".$dacode;
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result_array();

    }
    
   
  public function get_pendency($reportType,$categoryCode=NULL,$groupCountFrom=NULL,$groupCountTo=NULL,$caseCategory=NULL,$caseStatus=NULL,$caseType=NULL,$fromDate=NULL,$toDate=NULL,$reportType1=NULL,$jcode=null,$matterType=null,$matterStatus=null)

    {
        
             
        $sql="";
        switch($reportType)
        {
            case 1:
            {
                $sql=" SELECT  jcode, jname,count(*) as judge_wise_pendency,
                            sum(case when h.conn_key = 0 or h.conn_key=h.diary_no
                                    then 1 else 0
                                end) MainCaseCount,
                            sum(case when  h.conn_key != 0 and h.conn_key!=h.diary_no
                                    then 1 else 0 end) ConnectedCaseCount,
                                    case when j.is_retired='Y' THEN 1 else 2 end as is_retired
                        FROM heardt h
                        INNER JOIN main m ON h.diary_no = m.diary_no
                        LEFT JOIN judge j ON find_in_set( jcode, judges )=1
                        WHERE m.c_status = 'P'
                        AND judges != ''
                        AND judges != '0'
                        AND judges IS NOT NULL
                        GROUP BY jcode
                        order by judge_seniority desc";

                break;
            }
            case 2:
            {

$sql="select id,subcode1,category_sc_old,sub_name1,sub_name4, count(*) as total_pendency,
sum(case when mf_active!='F' and main_supp_flag in (0,1,2) then 1 else 0 end) as misc_ready,
sum(case when mf_active!='F' and (main_supp_flag not in (0,1,2) or main_supp_flag is null) then 1 else 0 end) as misc_not_ready,
sum(case when mf_active='F' and main_supp_flag in (0,1,2) then 1 else 0 end) as regular_ready,
sum(case when mf_active='F' and (main_supp_flag not in (0,1,2) or main_supp_flag is null) then 1 else 0 end) as regular_not_ready
                    from 
                    ( 
                        SELECT m.diary_no,m.fil_dt, 
                        s.id,s.subcode1,s.category_sc_old, s.sub_name1,s.sub_name4,
                        m.mf_active,h.main_supp_flag
                        FROM
                            heardt h
                            right JOIN main m ON h.diary_no = m.diary_no
                            INNER JOIN mul_category mcat ON m.diary_no = mcat.diary_no
                            INNER JOIN submaster s ON mcat.submaster_id = s.id
                            LEFT JOIN users u ON u.usercode = m.dacode AND (u.display = 'Y' || u.display is null)
                            LEFT JOIN usersection us ON us.id = u.section

                            WHERE m.c_status = 'P' AND mcat.display = 'Y'        
                            AND s.display = 'Y'    and flag='s' and flag_use in('S','L')                                           
                    ) a
                    GROUP BY id,subcode1,category_sc_old, sub_name1,sub_name4";    
                break;
            }
            case 3:{
                $jcode=$this->input->get('jcode');
                $sql="select m.* from
                          (
                            SELECT  j.jcode,j.jname,
                            s.section_name as user_section,substr(m.diary_no,1,length(m.diary_no)-4) AS diary_no,
                            substr(m.diary_no,-4) as diary_year,
                            date_format(m.diary_no_rec_date, '%Y-%m-%d') as diary_date,
                            next_dt, mainhead, subhead, brd_slno, h.usercode, ent_dt, pet_name, res_name,
                            active_fil_no, dacode, h.conn_key, stagename, main_supp_flag, u.name alloted_to_da,
                            descrip, u1.name updated_by, listorder
                            FROM heardt h
                            INNER JOIN main m ON h.diary_no = m.diary_no
                            LEFT JOIN judge j ON find_in_set(jcode, judges ) =1
                            LEFT JOIN subheading c ON h.subhead = c.stagecode AND c.display = 'Y'
                            LEFT JOIN users u ON u.usercode = m.dacode AND u.display = 'Y'
                            LEFT JOIN users u1 ON u1.usercode = h.usercode AND u1.display = 'Y'
                            LEFT JOIN master_main_supp mms ON mms.id = h.main_supp_flag
                            LEFT JOIN listing_purpose lp ON lp.code = h.listorder AND lp.display = 'Y'
                            LEFT join usersection s on s.id=u.section and s.display='Y'
                            WHERE m.c_status = 'P'
                            AND judges != ''
                            AND judges != '0'
                            AND judges IS NOT NULL
                          ) m
                        where m.jcode=$jcode";
                //echo $sql;

                break;
            }
            case 4:{
                $catregory_code=$this->input->get('categoryCode');
                       $sql="select m.* from
                          (
                            SELECT  case when (m.conn_key!=0 and m.conn_key is not null and m.conn_key!=m.diary_no) then (select reg_no_display from main where diary_no=m.conn_key) else 'Main' end as connected_with,
sm.category_sc_old, sm.sub_name1,sm.sub_name4,
                            s.section_name as user_section,substr(m.diary_no,1,length(m.diary_no)-4) AS diary_no,
                            substr(m.diary_no,-4) as diary_year,
                             m.reg_no_display,
                              m.mf_active,
                            date_format(m.diary_no_rec_date, '%Y-%m-%d') as diary_date,
                            next_dt, mainhead, subhead, brd_slno, h.usercode, ent_dt, pet_name, res_name,
                            active_fil_no, dacode, h.conn_key, stagename, main_supp_flag, u.name alloted_to_da,
                            descrip, u1.name updated_by, listorder
                            from mul_category mc
			    INNER JOIN main m ON mc.diary_no = m.diary_no
                            INNER JOIN heardt h ON m.diary_no=h.diary_no
                            left outer join submaster sm ON mc.submaster_id = sm.id AND sm.display='Y'
                            LEFT outer JOIN subheading c ON h.subhead = c.stagecode AND c.display = 'Y'
                            LEFT JOIN users u ON u.usercode = m.dacode AND u.display = 'Y'
                            LEFT JOIN users u1 ON u1.usercode = h.usercode AND u1.display = 'Y'
                            LEFT JOIN master_main_supp mms ON mms.id = h.main_supp_flag
                            LEFT JOIN listing_purpose lp ON lp.code = h.listorder AND lp.display = 'Y'
                            LEFT join usersection s on s.id=u.section and s.display='Y'
                            WHERE m.c_status = 'P'
                            and mc.display='Y'
                          ) m
                        where m.category_sc_old=$catregory_code";
                       break;
            }
case 5: {
                if(strcasecmp($matterType,'MF')==0)
                    $Type="1=1";
                else
                    $Type="m.mf_active='$matterType'";
                if(strcasecmp($matterStatus,'NR')==0)
                    $Status="1=1";
                elseif(strcasecmp($matterStatus,'N')==0)
                    $Status="h.main_supp_flag!=0";
                elseif(strcasecmp($matterStatus,'R')==0)
                    $Status="h.main_supp_flag=0";
                if(strcasecmp($categoryCode,'0')==0)
                    $code="1=1";
                else
                   $code=" s.subcode1 = $categoryCode";
               $sql="SELECT
                                us.section_name AS user_section,
                                    u.name alloted_to_da,
                                    CONCAT(s.subcode1) AS mainSubCategoryCode,
                                    sub_name1,
                                    SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) AS diary_no,
                                    SUBSTR(m.diary_no, - 4) AS diary_year,
                                    DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                                    m.reg_no_display,
                                    m.pet_name,
                                    m.res_name,
                                    m.mf_active,
                                    h.next_dt,
                                    CASE
                                        WHEN h.main_supp_flag = 0 THEN 'Ready'
                                        ELSE 'Not Ready'
                                    END AS 'Status',
                                    aa.total_connected AS group_count,
                                    CASE
                                        WHEN
                                            (m.conn_key = 0 OR m.conn_key IS NULL
                                                OR m.conn_key = m.diary_no)
                                        THEN
                                            'M'
                                        ELSE CASE
                                            WHEN
                                                (m.conn_key != 0
                                                    AND m.conn_key IS NOT NULL
                                                    AND m.conn_key != m.diary_no)
                                            THEN
                                                'C'
                                        END
                                    END AS mainorconn
                            FROM
                                heardt h
                            INNER JOIN main m ON h.diary_no = m.diary_no
                            INNER JOIN mul_category mcat ON h.diary_no = mcat.diary_no
                            INNER JOIN submaster s ON mcat.submaster_id = s.id
                            left join 
                            (
                            select n.conn_key,count(*) as total_connected from main m
                            inner join heardt h on m.diary_no=h.diary_no
                            inner join main n on m.diary_no=n.conn_key where n.diary_no!=n.conn_key and m.c_status='P'
                            group by n.conn_key having count(*)>=$groupCountFrom
                            ) aa on m.diary_no=aa.conn_key
                            LEFT JOIN users u ON u.usercode = m.dacode AND (u.display = 'Y' || u.display is null)
                            LEFT JOIN usersection us ON us.id = u.section
                            WHERE m.c_status = 'P' AND mcat.display = 'Y'
                            and (m.conn_key = 0 OR m.conn_key IS NULL OR m.conn_key = m.diary_no)
                            and (case when $groupCountFrom=0 then (total_connected >=$groupCountFrom or total_connected is null) else total_connected >=$groupCountFrom end) 
                            AND s.display = 'Y' and $Type and $Status and $code";

                 break;
            }
            case 6: {
        $jCode=$_POST['jCode'];                    
        $from_Date= date('Y-m-d', strtotime($_POST['from_date']));
        $to_Date=date('Y-m-d', strtotime($_POST['to_date']));
    if($jCode!='0')
        $condition=" and j.jcode=$jCode";
    else
        $condition=" and 1=1";
      $sql="SELECT listed.jcode, listed.jname,listed.Misc_Main AS listed_Misc_Main,listed.Misc_Conn AS listed_Misc_Conn,
            listed.Regular_Main AS listed_Regular_Main,listed.Regular_Conn AS listed_Regular_Conn,
            listed.total_Main AS listed_total_Main,listed.total_Conn AS listed_total_Conn,
            disposed.Misc_Main AS disposed_Misc_Main,disposed.Misc_Conn AS disposed_Misc_Conn,
            disposed.Regular_Main AS disposed_Regular_Main,disposed.Regular_Conn AS disposed_Regular_Conn,
            disposed.total_Main AS disposed_total_Main,disposed.total_Conn AS disposed_total_Conn
            FROM
            (SELECT jcode, jname,
            count(distinct CASE WHEN (m.mf_active='M' AND (m.conn_key=0 OR m.conn_key IS NULL OR m.conn_key=m.diary_no)) THEN m.diary_no END) AS Misc_Main,
            count(distinct CASE WHEN (m.mf_active='M' AND (m.conn_key!=0 AND m.conn_key IS NOT NULL AND m.conn_key!=m.diary_no)) THEN m.diary_no END) AS Misc_Conn,
            count(distinct CASE WHEN (m.mf_active<>'M' AND (m.conn_key=0 OR m.conn_key IS NULL OR m.conn_key=m.diary_no)) THEN m.diary_no END) AS Regular_Main,
            count(distinct CASE WHEN (m.mf_active<>'M' AND (m.conn_key!=0 AND m.conn_key IS NOT NULL AND m.conn_key!=m.diary_no)) THEN m.diary_no END) AS Regular_Conn,
            count(distinct CASE WHEN (m.conn_key=0 OR m.conn_key IS NULL OR m.conn_key=m.diary_no) THEN m.diary_no END) AS total_Main,
            count(distinct CASE WHEN (m.conn_key!=0 AND m.conn_key IS NOT NULL AND m.conn_key!=m.diary_no) THEN m.diary_no END) AS total_Conn FROM main m INNER JOIN
            (SELECT DISTINCT diary_no,next_dt,judges FROM
            (SELECT diary_no,next_dt,judges,board_type FROM heardt WHERE next_dt BETWEEN '".$from_Date."' AND '".$to_Date."' AND
            clno!=0 AND brd_slno!=0 AND roster_id!=0 AND judges!=0 AND roster_id NOT IN (29,30) AND board_type ='J'
            UNION ALL
            SELECT diary_no,next_dt,judges,board_type FROM last_heardt WHERE next_dt BETWEEN '".$from_Date."' AND '".$to_Date."'
            AND (bench_flag IS NULL OR bench_flag='') AND clno!=0 AND brd_slno!=0 AND roster_id!=0 AND judges!=0
            AND roster_id NOT IN (29,30) AND board_type ='J')bb) aa
            ON m.diary_no=aa.diary_no
            INNER JOIN judge j ON FIND_IN_SET (j.jcode, aa.judges)=1
            WHERE 1=1  $condition
            GROUP BY jcode, jname) listed
            LEFT JOIN
            (SELECT  jcode, jname,
            count(distinct CASE WHEN (m.mf_active='M' AND (m.conn_key=0 OR m.conn_key IS NULL OR m.conn_key=m.diary_no)) THEN m.diary_no END) AS Misc_Main,
            count(distinct CASE WHEN (m.mf_active='M' AND (m.conn_key!=0 AND m.conn_key IS NOT NULL AND m.conn_key!=m.diary_no)) THEN m.diary_no END) AS Misc_Conn,
            count(distinct CASE WHEN (m.mf_active<>'M' AND (m.conn_key=0 OR m.conn_key IS NULL OR m.conn_key=m.diary_no)) THEN m.diary_no END) AS Regular_Main,
            count(distinct CASE WHEN (m.mf_active<>'M' AND (m.conn_key!=0 AND m.conn_key IS NOT NULL AND m.conn_key!=m.diary_no)) THEN m.diary_no END) AS Regular_Conn,
            count(distinct CASE WHEN (m.conn_key=0 OR m.conn_key IS NULL OR m.conn_key=m.diary_no) THEN m.diary_no END) AS total_Main,
            count(distinct CASE WHEN (m.conn_key!=0 AND m.conn_key IS NOT NULL AND m.conn_key!=m.diary_no) THEN m.diary_no END) AS total_Conn FROM main m left JOIN heardt h
            ON m.diary_no=h.diary_no INNER JOIN dispose d
            ON m.diary_no=d.diary_no INNER JOIN judge j ON FIND_IN_SET (j.jcode, d.jud_id)=1
             WHERE d.ord_dt BETWEEN '".$from_Date."' AND '".$to_Date."' $condition  AND c_status='D' AND h.board_type ='J'
            GROUP BY jcode, jname) disposed
            ON listed.jcode=disposed.jcode ORDER BY listed.jcode";

      $sql2="SELECT                     
            count(distinct m.diary_no ) AS other_disp FROM main m 
            left JOIN heardt h ON m.diary_no=h.diary_no 
            INNER JOIN dispose d
            ON m.diary_no=d.diary_no INNER JOIN judge j ON FIND_IN_SET (j.jcode, d.jud_id)=1
             WHERE d.ord_dt BETWEEN '".$from_Date."' AND '".$to_Date."'  AND c_status='D' AND (h.board_type !='J' OR h.diary_no is NULL)";
      $query2=$this->db->query($sql2);

            break;
}           
            case 7:
            {
                break;
            }
            case 8:
            {
                $fromDate=date('Y-m-d', strtotime($fromDate));
                $toDate=date('Y-m-d', strtotime($toDate));

                //Judgwise Matter Listed and Disposed Query

                if($jcode!='0')
                    $condition1=" and j.jcode=$jcode";
                else
                    $condition1=" and 1=1";

                if($reportType1=='LMM')
                {
                    $condition2=" and m.mf_active='M' AND (m.conn_key=0 OR m.conn_key IS NULL OR m.conn_key=m.diary_no)";
                }
                else if($reportType1=='LMC')
                {
                    $condition2=" and m.mf_active='M' AND (m.conn_key!=0 AND m.conn_key IS NOT NULL AND m.conn_key!=m.diary_no)";
                }
                else if($reportType1=='LRM')
                {
                    $condition2=" and m.mf_active='F' AND (m.conn_key=0 OR m.conn_key IS NULL OR m.conn_key=m.diary_no)";
                }
                else if($reportType1=='LRC')
                {
                    $condition2=" and m.mf_active='F' AND (m.conn_key!=0 AND m.conn_key IS NOT NULL AND m.conn_key!=m.diary_no)";
                }
                else if($reportType1=='LTM')
                {
                    $condition2=" and (m.conn_key=0 OR m.conn_key IS NULL OR m.conn_key=m.diary_no)";
                }
                else if($reportType1=='LTC')
                {
                    $condition2=" and m.conn_key!=0 AND m.conn_key IS NOT NULL AND m.conn_key!=m.diary_no";
                }
                else if($reportType1=='DMM')
                {
                    $condition2=" and m.mf_active='M' AND (m.conn_key=0 OR m.conn_key IS NULL OR m.conn_key=m.diary_no)";
                }
                else if($reportType1=='DMC')
                {
                    $condition2=" and m.mf_active='M' AND (m.conn_key!=0 AND m.conn_key IS NOT NULL AND m.conn_key!=m.diary_no)";
                }
                else if($reportType1=='DRM')
                {
                    $condition2=" and m.mf_active='F' AND (m.conn_key=0 OR m.conn_key IS NULL OR m.conn_key=m.diary_no)";
                }
                else if($reportType1=='DRC')
                {
                    $condition2=" and m.mf_active='F' AND (m.conn_key!=0 AND m.conn_key IS NOT NULL AND m.conn_key!=m.diary_no)";
                }
                else if($reportType1=='DTM')
                {
                    $condition2=" and (m.conn_key=0 OR m.conn_key IS NULL OR m.conn_key=m.diary_no)";
                }
                else if($reportType1=='DTC')
                {
                    $condition2=" and m.conn_key!=0 AND m.conn_key IS NOT NULL AND m.conn_key!=m.diary_no";
                }
                else
                {
                    $condition2=" and 1=1";
                }

                //and j.jcode=108
                if($reportType1=='AL' || $reportType1=='LMM' || $reportType1=='LMC' || $reportType1=='LRM' || $reportType1=='LRC' || $reportType1=='LTM' || $reportType1=='LTC')
                {
                  $sql="SELECT j.jcode, j.jname,
                      us.section_name AS user_section,
                      u.name alloted_to_da,
                      SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) AS diary_no,
                      SUBSTR(m.diary_no, - 4) AS diary_year,
                      DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                      m.reg_no_display,
                      m.pet_name,
                      m.res_name,
                      m.mf_active,
                      aa.next_dt,
                      aa.brd_slno,
                      r.courtno,

                                    CASE
                                        WHEN
                                            (m.conn_key = 0 OR m.conn_key IS NULL
                                                OR m.conn_key = m.diary_no)
                                        THEN
                                            'M'
                         ELSE CASE
                            WHEN
                               (m.conn_key != 0
                                   AND m.conn_key IS NOT NULL
                                        AND m.conn_key != m.diary_no)
                                            THEN
                                                'C'
                                        END
                                    END AS mainorconn
                    FROM main m INNER JOIN
                    (
                    SELECT DISTINCT diary_no,judges,next_dt,brd_slno,roster_id FROM
                        (
                            SELECT diary_no,judges,board_type,next_dt,brd_slno,roster_id FROM heardt WHERE next_dt BETWEEN '".$fromDate."' AND '".$toDate."' AND
                            clno!=0 AND brd_slno!=0 AND roster_id!=0 AND judges!=0 AND roster_id NOT IN (29,30) AND board_type ='J'
                            UNION ALL
                            SELECT diary_no,judges,board_type,next_dt,brd_slno,roster_id FROM last_heardt WHERE next_dt BETWEEN '".$fromDate."' AND '".$toDate."'
                            AND (bench_flag IS NULL OR bench_flag='') AND clno!=0 AND brd_slno!=0 AND roster_id!=0 AND judges!=0
                            AND roster_id NOT IN (29,30) AND board_type ='J'
                        )bb
                    ) aa
                    ON m.diary_no=aa.diary_no
                    INNER JOIN judge j ON FIND_IN_SET (j.jcode, aa.judges)=1
                    LEFT JOIN users u ON u.usercode = m.dacode AND u.display = 'Y'
                    LEFT JOIN usersection us ON us.id = u.section
                    LEFT JOIN roster r on aa.roster_id=r.id
                    WHERE j.is_retired='N' $condition1 $condition2 order by aa.next_dt,r.courtno,aa.brd_slno";
                }
                if($reportType1=='AD' || $reportType1=='DMM' || $reportType1=='DMC' || $reportType1=='DRM' || $reportType1=='DRC' || $reportType1=='DTM' || $reportType1=='DTC')
                {
                     $sql="SELECT j.jcode, j.jname,
                      us.section_name AS user_section,
                      u.name alloted_to_da,
                      SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) AS diary_no,
                      SUBSTR(m.diary_no, - 4) AS diary_year,
                      DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                      m.reg_no_display,
                      m.pet_name,
                      m.res_name,
                      m.mf_active,
                     h.next_dt,
                     h.brd_slno,
                     r.courtno,

                                    CASE
                                        WHEN
                                            (m.conn_key = 0 OR m.conn_key IS NULL
                                                OR m.conn_key = m.diary_no)
                                        THEN
                                            'M'
                         ELSE CASE
                            WHEN
                               (m.conn_key != 0
                                   AND m.conn_key IS NOT NULL
                                        AND m.conn_key != m.diary_no)
                                            THEN
                                                'C'
                                        END
                                    END AS mainorconn
                    FROM main m INNER JOIN heardt h
                    ON m.diary_no=h.diary_no INNER JOIN dispose d
                    ON m.diary_no=d.diary_no INNER JOIN judge j ON FIND_IN_SET (j.jcode, d.jud_id)=1
                    LEFT JOIN users u ON u.usercode = m.dacode AND u.display = 'Y'
                    LEFT JOIN usersection us ON us.id = u.section
                     LEFT JOIN roster r on h.roster_id=r.id
                    WHERE j.is_retired='N' AND d.ord_dt BETWEEN '".$fromDate."' AND '".$toDate."'  AND c_status='D'
                    AND h.board_type ='J'  $condition1 $condition2 order by d.ord_dt,r.courtno,h.brd_slno";

                }
            }
            default:
                break;
        }
        //echo $sql;

        $query = $this->db->query($sql);

        if($reportType==6){
    $result['other_disposal'] = $query2->result_array();
    $result['disposal'] = $query->result_array();
    return $result;
}
        //echo ($query->num_rows());
        if($query -> num_rows() >= 1)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }
    function tagged_matter_report()
    {
        $sql="select b.main_case,concat(substring(b.diary_no,1,length(b.diary_no)-4),'/',substring(b.diary_no,-4)) as connected_case,
              connected from (select concat(substring(c.conn_key,1,length(c.conn_key)-4),'/',substring(c.conn_key,-4)) as main_case,
              c.diary_no, case when c.conn_type=\"C\" then \"connected\" else \"Linked\" end as connected from 
              (SELECT m.* FROM main m left join mul_category mc on mc.diary_no = m.diary_no and 
              mc.display = 'Y' and mc.submaster_id in (239,240) where (m.diary_no = m.conn_key) 
              and m.c_status = 'P' and mc.diary_no is not null group by m.diary_no) a
              left join conct c on c.conn_key = a.diary_no and c.diary_no != a.diary_no
              where c.list = 'Y' and date(c.ent_dt)>='2017-05-08') b
              left join heardt h on h.diary_no = b.diary_no 
              where (h.subhead in (811,812) or listorder = '32') order by main_case";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function loose_document_report($first_date,$to_date,$report)
    {
        $sql="";
        if($report==1)
        $sql="select date(ent_dt) as date1,count(*) as documents from docdetails  where
              display='Y' and date(ent_dt) between '$first_date' and '$to_date' 
              group by date(ent_dt); ";
        else if($report==2)
            $sql="select dc.usercode,name,empid,count(1) as documents from docdetails dc join users u on dc.usercode=u.usercode
                  where date(dc.ent_dt) between '$first_date' and '$to_date' and 
                  dc.display='Y' group by usercode";
        /*$sql="select distinct date(ent_dt) as date1,count(*) as documents from docdetails  where
              display='Y' and date(ent_dt) between '2017-05-08' and '2017-05-18'
              group by date(ent_dt); ";*/
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result_array();
    }

    /*function loose_document_detail_report($date=0,$first_date=0,$to_date=0,$user=0)
    {

        if($date!=0&&$first_date==0&&$to_date==0&&$user==0)
        $sql="select concat(substring(dc.diary_no,1,length(dc.diary_no)-4),'/',substring(dc.diary_no,-4)) as diary_no,concat(pet_name,' Vs ',res_name) as causetitle,docdesc,concat(docnum,'/',docyear) as document,filedby,
u_dak.name as dak_name,u_dak.empid as dak_empid,dc.ent_dt,da.name as da_name,da.empid as da_empid,
us.section_name as da_section from docdetails dc left join docmaster dm 
on dc.doccode=dm.doccode and dc.doccode1=dm.doccode1 left join main m on dc.diary_no=m.diary_no 
left join users u_dak on u_dak.usercode=dc.usercode left join users da on da.usercode=m.dacode 
left join usersection us on us.id=da.section where date(dc.ent_dt)='$date' and dm.display='Y' and dc.display='Y' order by document";

        else if($first_date!=0&&$to_date!=0&&$user!=0&&$date==0)

            $sql="select concat(substring(dc.diary_no,1,length(dc.diary_no)-4),'/',substring(dc.diary_no,-4)) as diary_no,concat(pet_name,' Vs ',res_name) as causetitle,docdesc,concat(docnum,'/',docyear) as document,filedby,
u_dak.name as dak_name,u_dak.empid as dak_empid,dc.ent_dt,da.name as da_name,da.empid as da_empid,
us.section_name as da_section from docdetails dc left join docmaster dm 
on dc.doccode=dm.doccode and dc.doccode1=dm.doccode1 left join main m on dc.diary_no=m.diary_no 
left join users u_dak on u_dak.usercode=dc.usercode left join users da on da.usercode=m.dacode 
left join usersection us on us.id=da.section where date(dc.ent_dt) between '$first_date' and '$to_date'
and dc.usercode=$user and dm.display='Y' and dc.display='Y' order by document";

        $query = $this->db->query($sql);
       // echo $this->db->last_query();
        return $query->result_array();
    }
    */
    
    function loose_document_detail_report($date=0,$first_date=0,$to_date=0,$user=0,$sorting=1)
    {
        if($sorting==1)
            $sorting_condition="document";
        else if($sorting==2)
            $sorting_condition="da_section";

        if($date!=0&&$first_date==0&&$to_date==0&&$user==0)
        $sql="select concat(substring(dc.diary_no,1,length(dc.diary_no)-4),'/',substring(dc.diary_no,-4)) as diary_no,concat(pet_name,' Vs ',res_name) as causetitle,docdesc,concat(docnum,'/',docyear) as document,filedby,
u_dak.name as dak_name,u_dak.empid as dak_empid,dc.ent_dt,da.name as da_name,da.empid as da_empid,
us.section_name as da_section,date(h.next_dt) as next_date,datediff(h.next_dt,now()) as diff from docdetails dc left join docmaster dm 
on dc.doccode=dm.doccode and dc.doccode1=dm.doccode1 left join main m on dc.diary_no=m.diary_no 
left join users u_dak on u_dak.usercode=dc.usercode left join users da on da.usercode=m.dacode 
left join usersection us on us.id=da.section left join heardt h on h.diary_no=dc.diary_no where date(dc.ent_dt)='$date' and dm.display='Y' and dc.display='Y' order by $sorting_condition";

        else if($first_date!=0&&$to_date!=0&&$user!=0&&$date==0)

            $sql="select concat(substring(dc.diary_no,1,length(dc.diary_no)-4),'/',substring(dc.diary_no,-4)) as diary_no,concat(pet_name,' Vs ',res_name) as causetitle,docdesc,concat(docnum,'/',docyear) as document,filedby,
u_dak.name as dak_name,u_dak.empid as dak_empid,dc.ent_dt,da.name as da_name,da.empid as da_empid,
us.section_name as da_section,date(h.next_dt) as next_date,datediff(h.next_dt,now()) as diff from docdetails dc left join docmaster dm 
on dc.doccode=dm.doccode and dc.doccode1=dm.doccode1 left join main m on dc.diary_no=m.diary_no 
left join users u_dak on u_dak.usercode=dc.usercode left join users da on da.usercode=m.dacode 
left join usersection us on us.id=da.section left join heardt h on h.diary_no=dc.diary_no where date(dc.ent_dt) between '$first_date' and '$to_date'
and dc.usercode=$user and dm.display='Y' and dc.display='Y' order by $sorting_condition";

        $query = $this->db->query($sql);
       // echo $this->db->last_query();
        return $query->result_array();
    }
    
   /* function loose_document_da_detail($first_date,$to_date,$user)  // old function 
{
    $sql="select concat(substring(dc.diary_no,1,length(dc.diary_no)-4),'/',substring(dc.diary_no,-4)) as diary_no,concat(pet_name,' Vs ',res_name) as causetitle,docdesc,concat(docnum,'/',docyear) as document,filedby,
u_dak.name as dak_name,u_dak.empid as dak_empid,dc.ent_dt,da.name as da_name,da.empid as da_empid,
us.section_name as da_section from docdetails dc left join docmaster dm 
on dc.doccode=dm.doccode and dc.doccode1=dm.doccode1 left join main m on dc.diary_no=m.diary_no 
left join users u_dak on u_dak.usercode=dc.usercode left join users da on da.usercode=m.dacode 
left join usersection us on us.id=da.section where date(ent_dt) between '$first_date' and '$to_date' and da.usercode= $user and dm.display='Y' and dc.display='Y' order by document";
    $query = $this->db->query($sql);
     //echo $this->db->last_query();
    return $query->result_array();
}*/

/*function loose_document_da_detail($first_date,$to_date,$user)
{$sql="select concat(substring(dc.diary_no,1,length(dc.diary_no)-4),'/',substring(dc.diary_no,-4)) as diary_no, m.reg_no_display ,concat(pet_name,' Vs ',res_name) as causetitle,docdesc,concat(docnum,'/',docyear) as document,filedby,
u_dak.name as dak_name,u_dak.empid as dak_empid,dc.ent_dt,da.name as da_name,da.empid as da_empid,
us.section_name as da_section from docdetails dc left join docmaster dm 
on dc.doccode=dm.doccode and dc.doccode1=dm.doccode1 left join main m on dc.diary_no=m.diary_no 
left join users u_dak on u_dak.usercode=dc.usercode left join users da on da.usercode=m.dacode 
left join usersection us on us.id=da.section where date(ent_dt) between '$first_date' and '$to_date' and da.usercode= $user and dm.display='Y' and dc.display='Y' order by document";
    $query = $this->db->query($sql);
     //echo $this->db->last_query();
    return $query->result_array();
}*/



function loose_document_da_detail($first_date,$to_date,$user)
{
       $da_section="Select section,usertype from users where usercode=$user";
         $query1 = $this->db->query($da_section);
      // $rs_section=mysql_query($da_section);

                $s=$query1->result_array();
       foreach($s as $result) {
           $sec = trim($result['section']);
           $type = trim($result['usertype']);
       }
 
 if( ($type==14) || ($type==12) || ($type==6) || ($type==9) )
 {

    // echo   "login as branch officer";

     $mcode="select group_concat(usercode)  as x from users where section = $sec";
     $query2 = $this->db->query($mcode);
     $mcd=$query2->result_array();
     foreach($mcd as $result) {
         $users_da = trim($result['x']);


     }
     //echo $users_da;

// branch officer login query

     $sql="select concat(substring(dc.diary_no,1,length(dc.diary_no)-4),'/',substring(dc.diary_no,-4)) as diary_no, m.reg_no_display ,concat(pet_name,' Vs ',res_name) as causetitle,docdesc,concat(docnum,'/',docyear) as document,filedby,
u_dak.name as dak_name,u_dak.empid as dak_empid,dc.ent_dt,da.name as da_name,da.empid as da_empid,
us.section_name as da_section,date(h.next_dt) as next_date,datediff(h.next_dt,now()) as diff from docdetails dc left join docmaster dm 
on dc.doccode=dm.doccode and dc.doccode1=dm.doccode1 left join main m on dc.diary_no=m.diary_no 
left join users u_dak on u_dak.usercode=dc.usercode left join users da on da.usercode=m.dacode 
left join usersection us on us.id=da.section left join heardt h on h.diary_no=dc.diary_no where date(dc.ent_dt) between '$first_date' and '$to_date' and da.usercode in ($users_da) and dm.display='Y' and dc.display='Y' order by da_name,document";






 }
 else


     {
       // echo "loggod on as a dealing assistant";

         $sql="select concat(substring(dc.diary_no,1,length(dc.diary_no)-4),'/',substring(dc.diary_no,-4)) as diary_no, m.reg_no_display ,concat(pet_name,' Vs ',res_name) as causetitle,docdesc,concat(docnum,'/',docyear) as document,filedby,
u_dak.name as dak_name,u_dak.empid as dak_empid,dc.ent_dt,da.name as da_name,da.empid as da_empid,
us.section_name as da_section,date(h.next_dt) as next_date,datediff(h.next_dt,now()) as diff from docdetails dc left join docmaster dm 
on dc.doccode=dm.doccode and dc.doccode1=dm.doccode1 left join main m on dc.diary_no=m.diary_no 
left join users u_dak on u_dak.usercode=dc.usercode left join users da on da.usercode=m.dacode 
left join usersection us on us.id=da.section left join heardt h on h.diary_no=dc.diary_no where date(dc.ent_dt) between '$first_date' and '$to_date' and da.usercode= $user and dm.display='Y' and dc.display='Y' order by document";


     }

       $query = $this->db->query($sql);
     //echo $this->db->last_query();
    return $query->result_array();
}


function listing_matter($first_date,$to_date)
   {
       $sql="select date(next_dt) as date1,sum(case when board_type='R' then 1 else 0 end) as reg,
             sum(case when board_type='J' then 1 else 0 end) as court,
             sum(case when board_type='C' then 1 else 0 end) as chamber from(
             select date(next_dt) as next_dt,diary_no,board_type from heardt where 
             (conn_key=0 or diary_no=conn_key) and main_supp_flag in (1,2)  
             and next_dt between '$first_date' and '$to_date'
             union
             select date(next_dt) as next_dt,diary_no,board_type from last_heardt 
             where (conn_key=0 or diary_no=conn_key) and  main_supp_flag in (1,2) 
             and bench_flag='' and date(next_dt) between '$first_date' and '$to_date')a  
             group by date(next_dt);";
       $query = $this->db->query($sql);
       //echo $this->db->last_query();
       return $query->result_array();
   }

   function listed_detail($date,$flag)
   {
       $sql="select concat(substring(h.diary_no,1,length(h.diary_no)-4),'/',substring(h.diary_no,-4)) as diary_no,
             reg_no_display,concat(pet_name,' Vs ',res_name) as title,date(next_dt) as date1 
             from heardt h left outer join main m on h.diary_no=m.diary_no where 
             (h.conn_key=0 or h.diary_no=h.conn_key) and main_supp_flag in (1,2) 
             and board_type='$flag' and date(next_dt)='$date'
             union
             select concat(substring(lh.diary_no,1,length(lh.diary_no)-4),'/',substring(lh.diary_no,-4)) as diary_no,
             reg_no_display,concat(pet_name,' Vs ',res_name) as title,date(next_dt) as date1 from 
             last_heardt lh left outer join main m on lh.diary_no=m.diary_no where 
             (lh.conn_key=0 or lh.diary_no=lh.conn_key) and bench_flag='' and  
             main_supp_flag in (1,2) and board_type='$flag' and date(next_dt)='$date' 
             order by diary_no";
       $query = $this->db->query($sql);
       //echo $this->db->last_query();
       return $query->result_array();
   }

 
	function getMainSubjectCategory()
	    {
	       // $sql="select distinct subcode1,sub_name1  from submaster where flag_use='S' and display='Y' and flag='S' order by subcode1";
		$sql="select subcode1,sub_name1  from submaster where (flag_use='S' OR flag_use='L') and display='Y' and match_id!=0 and flag='S' group by subcode1 order by subcode1";
		$query = $this->db->query($sql);

		if($query -> num_rows() >= 1)
		{
		    return $query->result_array();
		}
		else
		{
		    return false;
		}
	    }
    

function getSCLSC_Report($c_Type,$status)
    {
        $sql=""; $condition="";
        $extra_column="";
        $extra_join1="";
        //echo "c type".$c_Type;
        if(strcmp($c_Type, 'C')== 0)
            $condition="case_grp='C' and ";
        else if(strcmp($c_Type, 'R')== 0)
            $condition="case_grp='R' and ";

        if(strcmp($status,'P' )==0 )
            $c_status="m.c_status = 'P' and ";
        else if(strcmp($status,'D' )==0)
            $c_status="m.c_status = 'D' and ";
        else if(strcmp($status,'PD' )==0) {
            $c_status = "m.c_status = 'P' and ";
            $extra_column=",DATE_FORMAT( d.defect_notified_date, '%d-%m-%Y') as first_defect_notified_date,
  DATE_FORMAT( h.cl_dt, '%d-%m-%Y') as last_listed_on, d.NoOfDelayDays ";
            $extra_join1=" left join
                                ( select x.* from 
                                    (select h.diary_no,next_dt as cl_dt from heardt h 
                                    where h.main_supp_flag in (1,2) GROUP BY diary_no
                                    union 
                                    select h.diary_no,MAX(next_dt) as cl_dt from last_heardt h 
                                    where h.main_supp_flag in (1,2) GROUP BY diary_no
                                    union
                                    SELECT c.diary_no, MAX(cl_date) AS cl_dt FROM case_remarks_multiple c        
                                    GROUP BY c.diary_no)x
                                     inner join main m on x.diary_no=m.diary_no
                                    where m.c_status='P'
                                ) h on m.diary_no=h.diary_no
                                inner join 
                                (
                                    SELECT DISTINCT
                                    o.diary_no,rm_dt,
                                    MIN(save_dt) AS defect_notified_date,
                                    CASE
                                        WHEN rm_dt = '0000-00-00 00:00:00' THEN DATEDIFF(now(), MIN(save_dt))
                                        ELSE 0
                                    END AS NoOfDelayDays,display
                                FROM
                                    obj_save o
                                    inner join main m on o.diary_no=m.diary_no
                                WHERE
                                    (o.rm_dt = '0000-00-00' OR rm_dt IS NULL) AND o.display = 'Y'
                                    and m.c_status='P'
                                group by o.diary_no
                                )d on m.diary_no=d.diary_no";
            $extra_condition=" and m.c_status='P'";
        }

        $sql = "select 
        us.section_name AS user_section,
        u.name alloted_to_da,
        concat(m.reg_no_display,' @ ',concat(SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4),' / ',SUBSTR(m.diary_no, - 4))) as case_no,         
        m.pet_name, m.res_name, m.reg_no_display, m.pno, m.rno, m.c_status,
        GROUP_CONCAT(distinct b.name
        SEPARATOR '<br/>') as advocate,
        case
            when m.diary_no = m.conn_key or m.conn_key is null or m.conn_key = 0 then 'M'
        else 'C'
        end main_connected,
        case_grp $extra_column
        from
          main m
            left join
          advocate a ON m.diary_no = a.diary_no
            left join
          bar b ON a.advocate_id = b.bar_id
            LEFT JOIN
          users u ON u.usercode = m.dacode AND (u.display = 'Y' or u.display is null)
            LEFT JOIN
          usersection us ON us.id = u.section
          $extra_join1
        where
           $condition  $c_status if_sclsc = 1 and (a.display = 'Y' or a.display is null)
            $extra_condition
            group by m.diary_no
            order by case_grp";

        $query = $this->db->query($sql);

        // echo $this->db->last_query();

        if ($query->num_rows() >= 1) {
            return ($query->result_array());
        } else {
            return false;
        }
    }


  function getSittingJudges()
    {
        $sql="select jcode,jname from judge where is_retired='N' and jtype='J' order by jcode";
        $query = $this->db->query($sql);

        if($query -> num_rows() >= 1)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }  

function getDisposal_AsPer_Updation($fromDate=null, $toDate=null,$id=null)
    {
        if($id==1)
        {
        $sql="select
                (SELECT count(*)
                FROM `main` m
                WHERE DATE(fil_dt)  BETWEEN '".$fromDate."' AND '".$toDate."') as institution,
                (SELECT count(*)
                FROM `main` m
                WHERE fil_no is not null and fil_no!='' and c_status='P') as current_pendency,
                sum(case when m.fil_no is not null and m.fil_no !='' then 1 else 0 end) registered_disposal,
                sum(case when m.fil_no is null or m.fil_no ='' then 1 else 0 end) diary_disposal,
                count(*) total_disposal
                from main m inner join dispose d on m.diary_no=d.diary_no
                where date(d.ent_dt) between '".$fromDate."' and '".$toDate."' and m.c_status='D'";
        }
        if($id==2)
        {
            $sql="select sum(case when m.mf_active<>'F' then 1 else 0 end) as admission_matter,
             sum(case when mf_active<>'F' and main_supp_flag in (0,1,2) then 1 else 0 end) as Total_Complete,
                sum(case when mf_active<>'F' and (main_supp_flag not in (0,1,2) or main_supp_flag is null) then 1 else 0 end) as Total_Incomplete,
                            sum(case when m.mf_active='F' then 1 else 0 end) as final_matter,
                sum(case when mf_active='F' and main_supp_flag in (0,1,2) then 1 else 0 end) as Total_Ready,
                sum(case when mf_active='F' and (main_supp_flag not in (0,1,2) or main_supp_flag is null) then 1 else 0 end) as Total_NotReady,
                sum(case when (case_grp='C' or case_grp is null) then 1 else 0 end) civil_pendency,
                sum(case when case_grp='R' then 1 else 0 end) criminal_pendency,
                sum(case when date(fil_dt) < date(DATE_SUB(now(), INTERVAL 1 YEAR)) then 1 else 0 end) more_than_one_year_old,
                sum(case when fil_dt >= date(DATE_SUB(now(), INTERVAL 1 YEAR)) then 1 else 0 end) less_than_one_year_old,
                count(*) as total_pendency from main m left outer join heardt h on m.diary_no=h.diary_no
                            where m.c_status='P' and m.fil_no is not null and m.fil_no !=''";
        }
        if($id==3)
        {
            $sql="SELECT SUBSTR(m.diary_no,1,LENGTH(m.diary_no) - 4) AS diary_no,
                    SUBSTR(m.diary_no, - 4) AS diary_year,
                    reg_no_display,pet_name,res_name,fil_dt
                    FROM main m  WHERE DATE(fil_dt) between '".$fromDate."' and '".$toDate."'";

        }
        if($id==4)
        {
             $sql="SELECT SUBSTR(m.diary_no,1,LENGTH(m.diary_no) - 4) AS diary_no,
                    SUBSTR(m.diary_no, - 4) AS diary_year,
                    reg_no_display,pet_name,res_name,d.disp_dt,d.ent_dt
                    from main m inner join dispose d on m.diary_no=d.diary_no
                    where date(d.ent_dt) between '".$fromDate."' and '".$toDate."' and m.c_status='D'
                    and (m.fil_no is null or m.fil_no ='')";
        }
        if($id==5)
        {
            $sql=" SELECT SUBSTR(m.diary_no,1,LENGTH(m.diary_no) - 4) AS diary_no,
                    SUBSTR(m.diary_no, - 4) AS diary_year,
                    reg_no_display,pet_name,res_name,d.disp_dt,d.ent_dt
                    from main m inner join dispose d on m.diary_no=d.diary_no
                    where date(d.ent_dt) between '".$fromDate."' and '".$toDate."' and m.c_status='D'
                   ";
        }
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }
    function getDisposal_AsPer_OrderDate($fromDate=null, $toDate=null,$id=null)
    {
        if($id==1)
        {
            $sql="select
(SELECT count(*)
 FROM `main` m
  WHERE DATE(fil_dt)  between '".$fromDate."' and '".$toDate."') as institution,
   (SELECT count(*)
                FROM `main` m
                WHERE fil_no is not null and fil_no!='' and c_status='P') as current_pendency,
                sum(case when m.fil_no is not null and m.fil_no !='' then 1 else 0 end) registered_disposal,
                sum(case when m.fil_no is null or m.fil_no ='' then 1 else 0 end) diary_disposal,
                SUM(CASE WHEN m.mf_active='M' then 1 else 0 end) misc_disposal,
                SUM(CASE WHEN m.mf_active='F' then 1 else 0 end) regular_disposal,
                count(*) total_disposal
                FROM main m INNER JOIN heardt h
                ON m.diary_no=h.diary_no INNER JOIN dispose d
                ON m.diary_no=d.diary_no INNER JOIN judge j ON FIND_IN_SET (j.jcode, d.jud_id)=1
                WHERE j.is_retired='N' AND d.ord_dt
                between '".$fromDate."' and '".$toDate."' AND c_status='D' AND h.board_type ='J'
                and (mf_active='M' OR mf_active='F')";
        }
        if($id==2)
        {
            $sql="select sum(case when m.mf_active<>'F' then 1 else 0 end) as admission_matter,
                  sum(case when mf_active<>'F' and main_supp_flag in (0,1,2) then 1 else 0 end) as Total_Complete,
                  sum(case when mf_active<>'F' and (main_supp_flag not in (0,1,2) or main_supp_flag is null) then 1 else 0 end) as Total_Incomplete,
                  sum(case when m.mf_active='F' then 1 else 0 end) as final_matter,
                  sum(case when mf_active='F' and main_supp_flag in (0,1,2) then 1 else 0 end) as Total_Ready,
                  sum(case when mf_active='F' and (main_supp_flag not in (0,1,2) or main_supp_flag is null) then 1 else 0 end) as Total_NotReady,
                  sum(case when (case_grp='C' or case_grp is null) then 1 else 0 end) civil_pendency,
                  sum(case when case_grp='R' then 1 else 0 end) criminal_pendency,
                  sum(case when date(fil_dt) < date(DATE_SUB(now(), INTERVAL 1 YEAR)) then 1 else 0 end) more_than_one_year_old,
                  sum(case when fil_dt >= date(DATE_SUB(now(), INTERVAL 1 YEAR)) then 1 else 0 end) less_than_one_year_old,
                  count(*) as total_pendency from main m left outer join heardt h on m.diary_no=h.diary_no
                  where m.c_status='P' and m.fil_no is not null and m.fil_no !=''";
        }
        if($id==3)
        {
            $sql="SELECT SUBSTR(m.diary_no,1,LENGTH(m.diary_no) - 4) AS diary_no,
                    SUBSTR(m.diary_no, - 4) AS diary_year,
                    reg_no_display,pet_name,res_name,fil_dt
                    FROM main m  WHERE DATE(fil_dt) between '".$fromDate."' and '".$toDate."'";

        }
        if($id==4)
        {
            $sql="SELECT SUBSTR(m.diary_no,1,LENGTH(m.diary_no) - 4) AS diary_no,
                    SUBSTR(m.diary_no, - 4) AS diary_year,
                    reg_no_display,pet_name,res_name,d.disp_dt,d.ent_dt
                    FROM main m INNER JOIN heardt h
                    ON m.diary_no=h.diary_no INNER JOIN dispose d
                    ON m.diary_no=d.diary_no INNER JOIN judge j ON FIND_IN_SET (j.jcode, d.jud_id)=1
                    WHERE j.is_retired='N' AND d.ord_dt
                    between '".$fromDate."' and '".$toDate."' AND c_status='D' AND h.board_type ='J'
                    and (mf_active='M' OR mf_active='F')
                    and (m.fil_no is null or m.fil_no ='')";
        }
        if($id==5)
        {
            $sql=" SELECT SUBSTR(m.diary_no,1,LENGTH(m.diary_no) - 4) AS diary_no,
                    SUBSTR(m.diary_no, - 4) AS diary_year,
                    reg_no_display,pet_name,res_name,d.disp_dt,d.ent_dt
                    FROM main m INNER JOIN heardt h
                    ON m.diary_no=h.diary_no INNER JOIN dispose d
                    ON m.diary_no=d.diary_no INNER JOIN judge j ON FIND_IN_SET (j.jcode, d.jud_id)=1
                    WHERE j.is_retired='N' AND d.ord_dt
                    between '".$fromDate."' and '".$toDate."' AND c_status='D' AND h.board_type ='J'
                    and (mf_active='M' OR mf_active='F')
                   ";
        }
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }
    
    /*function get_aor_detail($aorCode,$fromDate,$toDate)
    {

        $sql="select distinct a.diary_no, concat(bar.title,' ',bar.name) as advName,bar.aor_code, pet_name, res_name, reg_no_display, c_status,
              diary_no_rec_date as filing_date, case when fil_dt != '0000-00-00 00:00:00' then fil_dt else 0  end  as reg_date, d.ord_dt as disposal_dt,
              b.mf_active,
              CASE
                        WHEN
                            (b.conn_key = 0 OR b.conn_key IS NULL
                                OR b.conn_key = b.diary_no)
                        THEN
                            'M'
                        ELSE CASE
                            WHEN
                                (b.conn_key != 0
                                    AND b.conn_key IS NOT NULL
                                    AND b.conn_key != b.diary_no)
                            THEN
                                'C'
                        END
                    END AS mainOrConn
                from advocate a
                left join main b on a.diary_no = b.diary_no
                left join dispose d on d.diary_no = a.diary_no
                left join bar on a.advocate_id=bar.bar_id
                where  DATE(diary_no_rec_date) between '".$fromDate."' and '".$toDate."'
                and bar.aor_code=$aorCode
                order by diary_no_rec_date desc";

        $query = $this->db->query($sql);
        if($query -> num_rows() >= 1)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }*/
    function get_aor_detail($aorCode, $fromDate, $toDate, $flag)
    {
        if ($flag == 1) {
            $date_type = 'diary_no_rec_date';
        } else if ($flag == 2) {
            $date_type = 'a.ent_dt';
        }
        $sql = "SELECT DISTINCT a.diary_no, 
                          CONCAT(br.title, ' ', br.name) AS advName, 
                          br.aor_code, 
                          pet_name, 
                          res_name, 
                          reg_no_display, 
                          c_status,
                          DATE(diary_no_rec_date) AS filing_date,
                          DATE(d.ord_dt) AS disposal_dt,
                          b.mf_active, 
                          DATE(a.ent_dt) AS adv_date,
                          CASE
                              WHEN
                                  (CAST(b.conn_key AS INTEGER) = 0 
                                   OR b.conn_key IS NULL
                                   OR CAST(b.conn_key AS INTEGER) = b.diary_no)
                              THEN
                                  'M'
                              ELSE 
                                  CASE
                                      WHEN
                                          (CAST(b.conn_key AS INTEGER) != 0
                                           AND b.conn_key IS NOT NULL
                                           AND CAST(b.conn_key AS INTEGER) != b.diary_no)
                                      THEN
                                          'C'
                                  END
                          END AS mainOrConn,
                          DATE(diary_no_rec_date) AS order_filing_date
                    FROM advocate a
                    LEFT JOIN main b ON a.diary_no = b.diary_no
                    LEFT JOIN dispose d ON d.diary_no = a.diary_no
                    LEFT JOIN master.bar br ON a.advocate_id = br.bar_id
                    WHERE DATE($date_type) BETWEEN '$fromDate' AND '$toDate'
                    AND br.aor_code = $aorCode
                    ORDER BY order_filing_date DESC";
    
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        if (!empty($result)) {
            return $result;
        } else {
            return false;
        }
    }
    

    
    
    
        


        function get_aor_detail2($aorCode) {
            $sql = "SELECT SUM(CASE WHEN c_status = 'P' AND (fil_dt IS NULL OR fil_dt = '1970-01-01 00:00:00'::timestamp) THEN 1 ELSE 0 END) AS unreg,
                        SUM(CASE WHEN c_status = 'P' AND fil_dt IS NOT NULL AND fil_dt <> '1970-01-01 00:00:00'::timestamp THEN 1 ELSE 0 END) AS reg,
                        SUM(CASE WHEN c_status = 'D' THEN 1 ELSE 0 END) AS disposed_cases
                    FROM (
                        SELECT 
                            m.diary_no, 
                            m.fil_dt, 
                            m.c_status
                        FROM master.bar b
                        INNER JOIN advocate a ON b.bar_id = a.advocate_id
                        INNER JOIN main m ON m.diary_no = a.diary_no
                        WHERE b.aor_code = '".$aorCode."' AND a.display = 'Y'
                        GROUP BY m.diary_no, m.fil_dt, m.c_status
                    ) a;";
            
            $query = $this->db->query($sql);
            $result = $query->getResultArray();
            
            if (!empty($result)) {
                return $result;
            } else {
                return false;
            }
        }
    

    function get_aor_detail2_report($aorCode, $type){
        if ($type == 1) {
            $condition = " AND c_status = 'P' AND (fil_dt IS NULL OR fil_dt = '1970-01-01 00:00:00'::timestamp)";
        } else if ($type == 2) {
            $condition = " AND c_status = 'P' AND (fil_dt IS NOT NULL AND fil_dt != '1970-01-01 00:00:00'::timestamp)";
        } else if ($type == 3) {
            $condition = " AND c_status = 'D' ";
        }
            $sql = "SELECT a.diary_no, 
                           CONCAT(bar.title, ' ', bar.name) AS advName,
                           bar.aor_code, 
                           pet_name, 
                           res_name, 
                           reg_no_display, 
                           c_status,
                           diary_no_rec_date AS filing_date, 
                           CASE 
                               WHEN fil_dt IS NOT NULL AND fil_dt != '1970-01-01 00:00:00'::timestamp 
                               THEN fil_dt 
                               ELSE NULL 
                           END AS reg_date, 
                           d.ord_dt AS disposal_dt,
                           b.mf_active,
                           CASE
                               WHEN (b.conn_key::INTEGER = 0 OR b.conn_key IS NULL OR b.conn_key::INTEGER = a.diary_no::INTEGER)
                               THEN 'M'
                               ELSE CASE
                                   WHEN (b.conn_key::INTEGER != 0 AND b.conn_key IS NOT NULL AND b.conn_key::INTEGER != a.diary_no::INTEGER)
                                   THEN 'C'
                               END
                           END AS mainOrConn
                    FROM advocate a
                    INNER JOIN master.bar ON a.advocate_id = bar.bar_id
                    INNER JOIN main b ON a.diary_no = b.diary_no
                    LEFT JOIN dispose d ON d.diary_no = a.diary_no                
                    WHERE bar.aor_code = $aorCode 
                      AND a.display = 'Y' 
                      $condition
                    GROUP BY a.diary_no, bar.title, bar.name, bar.aor_code, pet_name, res_name, reg_no_display, c_status, 
                             diary_no_rec_date, fil_dt, d.ord_dt, b.mf_active, b.conn_key, a.diary_no
                    ORDER BY diary_no_rec_date DESC";
            $query = $this->db->query($sql);
            $result = $query->getResultArray();
            if (!empty($result)) {
                return $result;
            } else {
                return false;
            }
        }
        

    //changes done on 23-12-2021 as per BOCC directions
    function get_aor_detail2_bck_23122021($aorCode){
        $sql="  select sum(case when bar.aor_code=$aorCode and diary_no_rec_date like '%1900-01-01%' and fil_dt not like '%1900-01-01%' then 1 else 0 end) as filing,
                sum(case when bar.aor_code=$aorCode and fil_dt like '%1900-01-01%' and diary_no_rec_date not like '%1900-01-01%' then 1 else 0 end) as reg,
                sum(case when bar.aor_code=$aorCode and fil_dt like '%1900-01-01%' and diary_no_rec_date like '%1900-01-01%' then 1 else 0 end) as fil_reg
                from advocate a
                left join main b on a.diary_no = b.diary_no
                left join dispose d on d.diary_no = a.diary_no
                left join bar on a.advocate_id=bar.bar_id";
        $query = $this->db->query($sql);
        if($query -> num_rows() >= 1)
            return $query->result_array();
        else
            return false;
    }

    //changes done on 23-12-2021 as per BOCC directions
    function get_aor_detail2_report_bck_23122021($aorCode, $type){
        $condition = "1=1";
        if($type==1)
            $condition = $condition." and bar.aor_code=$aorCode and diary_no_rec_date like '%1900-01-01%' and fil_dt not like '%1900-01-01%'";
        else if($type==2)
            $condition = $condition." and bar.aor_code=$aorCode and fil_dt like '%1900-01-01%' and diary_no_rec_date not like '%1900-01-01%'";
        else if($type==3)
            $condition = $condition." and bar.aor_code=$aorCode and fil_dt like '%1900-01-01%' and diary_no_rec_date like '%1900-01-01%'";
        $sql="select a.diary_no, concat(bar.title,' ',bar.name) as advName,bar.aor_code, pet_name, res_name, reg_no_display, c_status,
              diary_no_rec_date as filing_date, case when fil_dt != '0000-00-00 00:00:00' then fil_dt else 0  end  as reg_date, d.ord_dt as disposal_dt,
              b.mf_active,
              CASE
                        WHEN
                            (b.conn_key = 0 OR b.conn_key IS NULL
                                OR b.conn_key = b.diary_no)
                        THEN
                            'M'
                        ELSE CASE
                            WHEN
                                (b.conn_key != 0
                                    AND b.conn_key IS NOT NULL
                                    AND b.conn_key != b.diary_no)
                            THEN
                                'C'
                        END
                    END AS mainOrConn
                from advocate a
                left join main b on a.diary_no = b.diary_no
                left join dispose d on d.diary_no = a.diary_no
                left join bar on a.advocate_id=bar.bar_id
                where  $condition
                order by diary_no_rec_date desc";
        $query = $this->db->query($sql);
        if($query -> num_rows() >= 1)
            return $query->result_array();
        else
            return false;
    }
    
    function getCtMaster_Ason_DispRemarks($onDate)
    {
        $sql="SELECT 
    IF( m.reg_year_mh = 0, YEAR(m.fil_dt), m.reg_year_mh ) m_year, m.mf_active,
    substr(m.diary_no,1,length(m.diary_no)-4) AS diary_no, substr(m.diary_no,-4)
                as diary_year,
    CASE
       WHEN
          (m.conn_key = 0 OR m.conn_key IS NULL OR m.conn_key = m.diary_no)
      THEN 'M'
        ELSE CASE WHEN (m.conn_key != 0 AND m.conn_key IS NOT NULL AND m.conn_key != m.diary_no)
      THEN 'C'
      END
    END AS mainorconn,  
    substr(m.conn_key,1,length(m.conn_key)-4) AS main_diary_no, 
    substr(m.conn_key,-4) as main_diary_year,
    m.conn_key, h.judges, h.mainhead, h.next_dt, h.subhead, h.clno, h.brd_slno, h.tentative_cl_dt,
    m.pet_name, m.res_name, m.c_status, GROUP_CONCAT(crm.r_head SEPARATOR ',') As Disp_Remarks, 
    GROUP_CONCAT(crh.head SEPARATOR ',') As Rmrk_Disp,
    GROUP_CONCAT(crm.head_content SEPARATOR ',') AS Head_Content,
    IF( cl.next_dt IS NULL, 'NA', h.brd_slno ) AS brd_prnt, h.roster_id, Rt.courtno, crm.uid,    
    IF( m.fil_no != '', SUBSTRING_INDEX(m.fil_no, '-', 1), '' ) AS ct1, 
    IF( m.fil_no != '', SUBSTRING_INDEX( SUBSTRING_INDEX(m.fil_no, '-', 2), '-', - 1 ), '' ) AS crf1, 
    IF( m.fil_no != '', SUBSTRING_INDEX(m.fil_no, '-', - 1), '' ) AS crl1, 
    IF( m.fil_no_fh != '', SUBSTRING_INDEX(m.fil_no_fh, '-', 1), '' ) AS ct2, 
    IF( m.fil_no_fh != '', SUBSTRING_INDEX( SUBSTRING_INDEX(m.fil_no_fh, '-', 2), '-', - 1 ), '' ) AS crf2,
     IF( m.fil_no_fh != '', SUBSTRING_INDEX(m.fil_no_fh, '-', - 1), '' ) AS crl2, 
    m.casetype_id, m.case_status_id FROM 
    ( SELECT t1.diary_no, t1.next_dt, t1.roster_id, t1.judges, t1.mainhead, t1.subhead, t1.clno, 
    t1.brd_slno, t1.main_supp_flag, t1.tentative_cl_dt FROM heardt t1 
    WHERE t1.next_dt = '".$onDate."' and t1.mainhead = 'M' AND 
    ( t1.main_supp_flag = 1 OR t1.main_supp_flag = 2 ) 
    UNION SELECT t2.diary_no, t2.next_dt, t2.roster_id, t2.judges, t2.mainhead, t2.subhead, 
    t2.clno, t2.brd_slno, t2.main_supp_flag, t2.tentative_cl_dt FROM last_heardt t2 
    WHERE t2.next_dt = '".$onDate."' and t2.mainhead = 'M' AND 
    ( t2.main_supp_flag = 1 OR t2.main_supp_flag = 2 ) 
    AND t2.bench_flag = '' ) h INNER JOIN main m 
    ON ( h.diary_no = m.diary_no AND h.next_dt = '".$onDate."' and h.mainhead = 'M' AND 
    ( h.main_supp_flag = 1 OR h.main_supp_flag = 2 ) ) 
    inner join case_remarks_multiple crm ON crm.diary_no=m.diary_no
    left join roster Rt ON Rt.id=h.roster_id
    left join case_remarks_head crh ON crh.sno=crm.r_head AND (crh.display = 'Y' or crh.display is null)
    left join users u on u.usercode=crm.uid AND (u.display = 'Y' or u.display is null)
    LEFT JOIN cl_printed cl ON ( cl.next_dt = h.next_dt AND cl.m_f = h.mainhead AND cl.part = h.clno 
    AND cl.main_supp = h.main_supp_flag AND cl.roster_id = h.roster_id AND cl.display = 'Y' ) 
    where  date(crm.e_date)= '".$onDate."' AND cl.next_dt IS NOT NULL and m.c_status='D' group by
     m_year, m.diary_no, m.mf_active,
    m.conn_key, h.judges, h.mainhead, h.next_dt, h.subhead, h.clno, h.brd_slno, h.tentative_cl_dt,
    m.pet_name, m.res_name, m.c_status, 
     brd_prnt, h.roster_id, ct1, crf1, crl1,  ct2, crf2, crl2, 
    m.casetype_id, m.case_status_id    
    ORDER BY h.judges, IF(brd_prnt = 'NA', 2, 1), h.brd_slno, 
    IF( m.conn_key = h.diary_no, '0000-00-00', m.fil_dt ) ASC";

        $query = $this->db->query($sql);

        //echo $this->db->last_query();
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }
function get_case_type_wise_pendency()
    {
        /*$sql="select casetype_id,c.casecode,c.casename,count(1) as total,
sum(case when (conn_key=0 or conn_key is null or conn_key=diary) then 1 else 0 end) as Main,
sum(case when (conn_key!=0 and conn_key is not null and conn_key!=diary) then 1 else 0 end) as Connected
 from

(SELECT DISTINCT m.diary_no as diary,m.conn_key, m.active_casetype_id as casetype_id
FROM
  sci_cmis_final.main m
  LEFT JOIN main_casetype_history b ON (m.diary_no = b.diary_no AND b.is_deleted = 'f')
  LEFT JOIN sci_cmis_final.dispose d
    ON d.diary_no = m.diary_no
WHERE (
    c_status = 'P'
    OR (
      c_status = 'D'
      AND DATE(d.ord_dt) >= date(now())
    )
  ) AND
(b.new_registration_number = m.fil_no OR b.diary_no IS NULL) AND
IF(((
order_date != '0000-00-00 00:00:00'
AND order_date IS NOT NULL AND m.c_status != 'P'
)
AND DATE( fil_dt ) != DATE( order_date )),
IF(m.c_status='D' AND d.diary_no IS NOT NULL,(DATE(order_date)<=DATE(d.ord_dt) AND DATE(order_date)<=date(now())),DATE(order_date)<=date(now())),
IF(m.c_status='D' AND d.diary_no IS NOT NULL,(DATE(m.fil_dt)<=DATE(d.ord_dt) AND DATE(m.fil_dt)<=date(now())),DATE(m.fil_dt)<=date(now())))
AND (m.fil_no IS NOT NULL AND m.fil_no != '')

union

SELECT DISTINCT m.diary_no as diary,m.conn_key, casetype_id
FROM
  docdetails dd
  INNER JOIN main m
    ON m.diary_no = dd.diary_no
  LEFT JOIN main_casetype_history b
    ON (
      m.diary_no = b.diary_no
      AND b.is_deleted = 'f'
    )
  LEFT JOIN sci_cmis_final.dispose d
    ON d.diary_no = m.diary_no
  LEFT JOIN heardt h
    ON h.diary_no = dd.diary_no
  LEFT JOIN last_heardt lh
    ON lh.diary_no = dd.diary_no
  LEFT JOIN
    (
    SELECT `diary_no`, GROUP_CONCAT(IF(rm_dt IS NULL OR rm_dt = '0000-00-00 00:00:00','NR',NULL)) AS notr,MAX(rm_dt) AS rmdt
FROM `obj_save`
WHERE display='Y'
GROUP BY diary_no
) t2
    ON dd.diary_no = t2.diary_no
WHERE
(b.new_registration_number = m.fil_no OR b.diary_no IS NULL) AND
IF(
    (m.fil_no IS NULL
      OR m.fil_no = ''),
    1 = 1,
    IF(
      ((
          order_date != '0000-00-00 00:00:00'
          AND order_date IS NOT NULL AND m.c_status != 'P'
        )
        AND DATE(fil_dt) != DATE(order_date)
      ),
      DATE(order_date) > date(now()),
      DATE(m.fil_dt) > date(now())
    )
  )
  AND (
    doccode = '8'
    AND (
      doccode1 = '28'
      OR doccode1 = '95'
      OR doccode1 = '214'
      OR doccode1 = '215'
      OR doccode1 = '16'
      OR doccode1 = '79'
      OR doccode1 = '99'
      OR doccode1 = '300'
      OR doccode1 = '226'
      OR doccode1 = '288'
      OR doccode1 = '322'
    )
  )
  AND (
    DATE(h.next_dt) < date(now())
    OR DATE(lh.next_dt) < date(now())
  )
  AND (
    c_status = 'P'
    OR (
      c_status = 'D'
      AND DATE(d.ord_dt) >= date(now())
    )
  )
  AND t2.notr IS NULL AND IF(t2.rmdt IS NOT NULL,DATE(t2.rmdt)<=date(now()),'1=1')
  AND (
    (
      IF(
        dd.ent_dt IS NULL
        OR dd.ent_dt = '1900-01-01 00:00:00',
        DATE(dd.lst_mdf) < date(now()),
        DATE(dd.ent_dt) < date(now())
      )
      AND dd.display = 'Y'
    )
  )
)a left outer join casetype c on a.casetype_id=c.casecode
group by casetype_id";*/

        $sql="select active_casetype_id,casename,count(distinct diary_no) total,
sum(case when (conn_key=0 or conn_key is null or conn_key=diary_no) then 1 else 0 end) as Main,
sum(case when (conn_key!=0 and conn_key is not null and conn_key!=diary_no) then 1 else 0 end) as Connected
from main m inner join casetype c on m.active_casetype_id=c.casecode where
c_status='P' and active_fil_no is not null and active_fil_no!='' and display='Y' group by active_casetype_id,casename order by 1";




        $query = $this->db->query($sql);
        if($query -> num_rows() >= 1)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }


    public function getSection()
    {
        $sql="select section_name from usersection where isda='Y' and display='Y' order by section_name";
        $query = $this->db->query($sql);
        return $query->result_array();
    }


public function defectiveMattersNotListed($days,$section)
    {
        //echo $type;
        if($section!='0')
        $condition="  and (section_name in ($section) or tentative_section(m.diary_no) in ($section)) ";
        else
            $condition="";
        $sql="select distinct substring(m.diary_no,1,length(m.diary_no)-4) as diary_no,
              substring(m.diary_no,-4) as diary_year,concat(pet_name,' Vs ',res_name) as title,
              date(diary_no_rec_date) as diary_date,date(save_dt) as save_dt,
              datediff(now(),save_dt) as diff,(select tentative_section(m.diary_no)) as tentative_section,
              name,empid,section_name from obj_save os join main m on m.diary_no=os.diary_no 
              left outer join users u on u.usercode=m.dacode 
              left join usersection us on us.id=u.section  
              left outer join heardt h on os.diary_no=h.diary_no 
              left outer join last_heardt lh on os.diary_no=lh.diary_no 
              where datediff(now(),save_dt)>$days 
              and (rm_dt is null or rm_dt='0000-00-00 00:00:00') and os.display='Y' 
              and  c_status = 'P' and (fil_no is null or  fil_no='') 
              and h.diary_no is null and lh.diary_no is null $condition 
              order by diff desc";

        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }
    
    function get_main_subject_categorywise_pending_cases()
    {
        $sql="SELECT  s.id,s.category_sc_old,s.subcode1,s.flag_use, s.sub_name1,
sum(case when a.mf_active='M'  then 1 else 0 end) as Misc_Sub_Cat_pendency,
sum(case when a.mf_active='F'  then 1 else 0 end) as Regular_Sub_Cat_pendency,
sum(case when ((a.mf_active=' ' or a.mf_active is null) and (a.mf_active<>'M' or a.mf_active is null)) then 1 else 0 end) as Not_Misc_Not_Regular,
count(*) as Total_Sub_Cat_pendency
 from

(SELECT DISTINCT m.diary_no as diary,m.conn_key, m.active_casetype_id as casetype_id,m.mf_active
FROM
  main m 
  LEFT JOIN main_casetype_history b ON (m.diary_no = b.diary_no AND b.is_deleted = 'f')
  LEFT JOIN dispose d 
    ON d.diary_no = m.diary_no 
WHERE (
    c_status = 'P' 
    OR (
      c_status = 'D' 
      AND DATE(d.ord_dt) >= date(now())
    )
  ) AND  
(b.new_registration_number = m.fil_no OR b.diary_no IS NULL) AND
IF(((
order_date != '0000-00-00 00:00:00'
AND order_date IS NOT NULL AND m.c_status != 'P'
)
AND DATE( fil_dt ) != DATE( order_date )),
IF(m.c_status='D' AND d.diary_no IS NOT NULL,(DATE(order_date)<=DATE(d.ord_dt) AND DATE(order_date)<=date(now())),DATE(order_date)<=date(now())),
IF(m.c_status='D' AND d.diary_no IS NOT NULL,(DATE(m.fil_dt)<=DATE(d.ord_dt) AND DATE(m.fil_dt)<=date(now())),DATE(m.fil_dt)<=date(now())))
AND (m.fil_no IS NOT NULL AND m.fil_no != '')

union

SELECT DISTINCT m.diary_no as diary,m.conn_key, casetype_id,m.mf_active
FROM
  docdetails dd 
  INNER JOIN main m 
    ON m.diary_no = dd.diary_no 
  LEFT JOIN main_casetype_history b 
    ON (
      m.diary_no = b.diary_no 
      AND b.is_deleted = 'f'
    ) 
  LEFT JOIN dispose d 
    ON d.diary_no = m.diary_no 
  LEFT JOIN heardt h 
    ON h.diary_no = dd.diary_no 
  LEFT JOIN last_heardt lh 
    ON lh.diary_no = dd.diary_no 
  LEFT JOIN 
    (
    SELECT `diary_no`, GROUP_CONCAT(IF(rm_dt IS NULL OR rm_dt = '0000-00-00 00:00:00','NR',NULL)) AS notr,MAX(rm_dt) AS rmdt
FROM `obj_save`
WHERE display='Y'
GROUP BY diary_no
) t2 
    ON dd.diary_no = t2.diary_no 
WHERE 
(b.new_registration_number = m.fil_no OR b.diary_no IS NULL) AND
IF(
    (m.fil_no IS NULL 
      OR m.fil_no = ''),
    1 = 1,
    IF(
      ((
          order_date != '0000-00-00 00:00:00' 
          AND order_date IS NOT NULL AND m.c_status != 'P'
        ) 
        AND DATE(fil_dt) != DATE(order_date)
      ),
      DATE(order_date) > date(now()),
      DATE(m.fil_dt) > date(now())
    )
  ) 
  AND (
    doccode = '8' 
    AND (
      doccode1 = '28' 
      OR doccode1 = '95' 
      OR doccode1 = '214' 
      OR doccode1 = '215' 
      OR doccode1 = '16' 
      OR doccode1 = '79' 
      OR doccode1 = '99' 
      OR doccode1 = '300' 
      OR doccode1 = '226' 
      OR doccode1 = '288' 
      OR doccode1 = '322'
    )
  ) 
  AND (
    DATE(h.next_dt) < date(now()) 
    OR DATE(lh.next_dt) < date(now())
  ) 
  AND (
    c_status = 'P' 
    OR (
      c_status = 'D' 
      AND DATE(d.ord_dt) >= date(now())
    )
  ) 
  AND t2.notr IS NULL AND IF(t2.rmdt IS NOT NULL,DATE(t2.rmdt)<=date(now()),'1=1')
  AND (
    (
      IF(
        dd.ent_dt IS NULL 
        OR dd.ent_dt = '1900-01-01 00:00:00',
        DATE(dd.lst_mdf) < date(now()),
        DATE(dd.ent_dt) < date(now())
      ) 
      AND dd.display = 'Y'
    )
  )
)a left JOIN mul_category mc  ON a.diary=mc.diary_no
left JOIN submaster s ON mc.submaster_id = s.id
WHERE mc.display = 'Y'
GROUP BY s.subcode1 order by s.subcode1";

        $query = $this->db->query($sql);
        if($query -> num_rows() >= 1)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }
    
    function getSections()
    {
        $sql="select id,section_name from usersection where display='Y' AND isda='Y' order by section_name";
        $query = $this->db->query($sql);

        if($query -> num_rows() >= 1)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }


function getSection_Pending_Reports($category, $section, $reportType, $listCourtType, $dateType, $fromDate, $toDate, $mcat)
    {
        $sql="";

        if($reportType == 0 && ($category !="" || isset($category)) && ($section!="" || isset($section)))
        {
            $condition="sm.id =$category";
            if($category==0 && $mcat!=100)
                {
                    $condition=" and subcode1=$mcat";
                }
                else if($category==0 && $mcat==100)
                {
                    $condition="";
                }
                else
                {
                    $condition=" and sm.id =$category";
                }


               /* $sql = "SELECT us.section_name AS user_section, sm.category_sc_old,
                    u.name alloted_to_da,
                    SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) AS diary_no,
                    SUBSTR(m.diary_no, - 4) AS diary_year,
                    DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                    m.pet_name, m.res_name, m.reg_no_display, m.c_status, d.disp_dt,
                    h.next_dt AS Hearing_Date
                FROM main m
                left join `heardt` h
                 ON m.diary_no = h.diary_no
                LEFT JOIN users u ON u.usercode = m.dacode
                AND (u.display = 'Y' or u.display is null)
                left join usersection us on us.id=u.section and us.display='Y'
                LEFT JOIN users u1 ON u1.usercode = m.usercode AND u1.display = 'Y'
                left join mul_category mc ON mc.diary_no = m.diary_no
                 left join dispose d on m.diary_no=d.diary_no
                left outer join submaster sm ON mc.submaster_id = sm.id AND (sm.display='Y' or sm.display is null)
                LEFT outer JOIN subheading c ON h.subhead = c.stagecode AND (c.display = 'Y' or c.display is null)
                WHERE
                m.c_status = 'P' and us.id=$section and sm.subcode1=$category";*/

                 $sql="SELECT m.active_fil_dt,m.diary_no_rec_date, us.section_name AS user_section,
                    case when mf_active='F' then 'Regular' else 'Misc.' end as casestage,
                    aa.total_connected AS group_count,
                   CASE
                    WHEN
                        (m.diary_no = m.conn_key
                            OR m.conn_key = 0
                            OR m.conn_key = ''
                            OR m.conn_key IS NULL)
                    THEN
                        'M'
                    ELSE 'C'
                END AS main_or_connected, 
                  sm.sub_name1,
                case when (category_sc_old is not null and category_sc_old!='' and category_sc_old!=0)
                then concat('',category_sc_old,' - ',sub_name4)
                else concat('',concat(subcode1,'',subcode2),' - ',sub_name4)
                end as subject_category,sm.category_sc_old,u.name alloted_to_da, 
                SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) AS diary_no,
                SUBSTR(m.diary_no, - 4) AS diary_year,
                DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,    
                m.pet_name, m.res_name, m.reg_no_display, m.c_status, 
                concat(m.reg_no_display, '@ D.No.', SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) ,'/', SUBSTR(m.diary_no, - 4)) As CaseNo,
                CASE WHEN h.main_supp_flag = 0 THEN 'Ready' ELSE 'Not Ready' END AS Ready_status  ,
                h.next_dt AS Hearing_Date               
            FROM main m
            left join `heardt` h
             ON m.diary_no = h.diary_no
            LEFT JOIN users u ON u.usercode = m.dacode
            AND (u.display = 'Y' or u.display is null)
            left join usersection us on us.id=u.section and us.display='Y'
            LEFT JOIN users u1 ON u1.usercode = m.usercode AND u1.display = 'Y' 
            LEFT JOIN (select n.conn_key,count(*) as total_connected from main m
                       inner join heardt h on m.diary_no=h.diary_no
                       inner join main n on m.diary_no=n.conn_key where n.diary_no!=n.conn_key and m.c_status='P'
                       group by n.conn_key
                      ) aa on m.diary_no=aa.conn_key
            inner join mul_category mc ON mc.diary_no = m.diary_no AND (mc.display='Y')            
            inner join submaster sm ON mc.submaster_id = sm.id AND (sm.display='Y' or sm.display is null)            
            WHERE 
            m.c_status = 'P' and us.id=$section $condition order by m.diary_no_rec_date asc";

            //left join dispose d on m.diary_no=d.diary_no
        }
        else if($reportType == 1 && ($section!="" || isset($section))&& ($listCourtType!="" || isset($listCourtType)))
        {
            $sql="SELECT m.active_fil_dt,m.diary_no_rec_date,us.section_name AS user_section,
            case when mf_active='F' then 'Regular' else 'Misc.' end as casestage,
                                    aa.total_connected AS group_count,
                                    CASE
        WHEN
            (m.diary_no = m.conn_key
                OR m.conn_key = 0
                OR m.conn_key = ''
                OR m.conn_key IS NULL)
        THEN
            'M'
        ELSE 'C'
    END AS main_or_connected, 
            sm.category_sc_old, u.name alloted_to_da, 
            SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) AS diary_no, 
            SUBSTR(m.diary_no, - 4) AS diary_year, 
            DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
            m.pet_name, m.res_name, m.reg_no_display, 
            concat(m.reg_no_display, '@ D.No.', SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) ,'/', SUBSTR(m.diary_no, - 4)) As CaseNo,
            CASE WHEN h.main_supp_flag = 0 THEN 'Ready' ELSE 'Not Ready' END AS Ready_status,
            m.c_status,d.disp_dt,
            h.next_dt AS Hearing_Date, h.board_type FROM main m
            left join `heardt` h ON m.diary_no = h.diary_no
            LEFT JOIN users u ON u.usercode = m.dacode AND (u.display = 'Y' or u.display is null) 
            left join usersection us on us.id=u.section and us.display='Y' 
            LEFT JOIN users u1 ON u1.usercode = m.usercode AND u1.display = 'Y' 
            LEFT JOIN (select n.conn_key,count(*) as total_connected from main m
                       inner join heardt h on m.diary_no=h.diary_no
                       inner join main n on m.diary_no=n.conn_key where n.diary_no!=n.conn_key and m.c_status='P'
                       group by n.conn_key
                      ) aa on m.diary_no=aa.conn_key
            left join mul_category mc ON mc.diary_no = m.diary_no AND (mc.display='Y')
            left join dispose d on m.diary_no=d.diary_no 
            left outer join submaster sm ON mc.submaster_id = sm.id AND (sm.display='Y' or sm.display is null) LEFT outer JOIN subheading c ON h.subhead = c.stagecode AND (c.display = 'Y' or c.display is null) 
            WHERE m.c_status = 'P' AND us.id=$section AND h.board_type='$listCourtType' order by m.diary_no_rec_date asc";
        }
        else if($reportType == 2 && ($section!="" || isset($section)))
        {
            $query_ch="";
            if(strcmp($dateType,'F' )== 0) {
                $query_ch=' AND (h.next_dt >= DATE(NOW()))';
            }
            else {
                $query_ch=' AND (h.next_dt < DATE(NOW()))';
            }
            $sql = "SELECT m.active_fil_dt,m.diary_no_rec_date,us.section_name AS user_section, 
                        case when mf_active='F' then 'Regular' else 'Misc.' end as casestage,
                                    aa.total_connected AS group_count,
                                    CASE
                            WHEN
                                (m.diary_no = m.conn_key
                                    OR m.conn_key = 0
                                    OR m.conn_key = ''
                                    OR m.conn_key IS NULL)
                            THEN
                                'M'
                            ELSE 'C'
                        END AS main_or_connected,
                        sm.category_sc_old, u.name alloted_to_da, 
                        SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) AS diary_no, 
                        SUBSTR(m.diary_no, - 4) AS diary_year, 
                        DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                        m.pet_name, m.res_name, m.reg_no_display,
                        concat(m.reg_no_display, '@ D.No.', SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) ,'/', SUBSTR(m.diary_no, - 4)) As CaseNo,
                        CASE WHEN h.main_supp_flag = 0 THEN 'Ready' ELSE 'Not Ready' END AS Ready_status,
                        m.c_status, h.next_dt AS Hearing_Date, h.board_type FROM main m
                        INNER join `heardt` h ON m.diary_no = h.diary_no $query_ch 
                        LEFT JOIN users u ON u.usercode = m.dacode AND (u.display = 'Y' or u.display is null) 
                        left join usersection us on us.id=u.section and us.display='Y' 
                        LEFT JOIN users u1 ON u1.usercode = m.usercode AND u1.display = 'Y' 
                        LEFT JOIN (select n.conn_key,count(*) as total_connected from main m
                                   inner join heardt h on m.diary_no=h.diary_no
                                   inner join main n on m.diary_no=n.conn_key where n.diary_no!=n.conn_key and m.c_status='P'
                                   group by n.conn_key
                                  ) aa on m.diary_no=aa.conn_key
                        left join mul_category mc ON mc.diary_no = m.diary_no AND (mc.display='Y')
                        left outer join submaster sm ON mc.submaster_id = sm.id AND (sm.display='Y' or sm.display is null) LEFT outer JOIN subheading c ON h.subhead = c.stagecode AND (c.display = 'Y' or c.display is null) 
                        WHERE m.c_status = 'P' AND us.id=$section order by m.diary_no_rec_date asc";

        }
        else if($reportType == 4 && ($section!="" || isset($section)))
        {
            //echo "hello";
            $sql = "SELECT m.active_fil_dt,m.diary_no_rec_date,us.section_name AS user_section,
                        case when mf_active='F' then 'Regular' else 'Misc.' end as casestage,
                        aa.total_connected AS group_count,                        
                    sm.category_sc_old, u.name alloted_to_da, 
                    SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) AS diary_no, 
                    SUBSTR(m.diary_no, - 4) AS diary_year, 
                    DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
                    m.pet_name, m.res_name, m.reg_no_display,
                    concat(m.reg_no_display, '@ D.No.', SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) ,'/', SUBSTR(m.diary_no, - 4)) As CaseNo,
                    CASE WHEN h.main_supp_flag = 0 THEN 'Ready' ELSE 'Not Ready' END AS Ready_status, 
                    m.c_status, rc.agency_name,
                    h.next_dt AS Hearing_Date, h.board_type, d.disp_dt FROM main m
                    inner join ref_agency_code rc on rc.id=m.ref_agency_code_id
                    left join `heardt` h ON m.diary_no = h.diary_no 
                    LEFT JOIN users u ON u.usercode = m.dacode AND (u.display = 'Y' or u.display is null) 
                    left join usersection us on us.id=u.section and us.display='Y' 
                    LEFT JOIN users u1 ON u1.usercode = m.usercode AND u1.display = 'Y' 
                    LEFT JOIN (select n.conn_key,count(*) as total_connected from main m
                                   inner join heardt h on m.diary_no=h.diary_no
                                   inner join main n on m.diary_no=n.conn_key where n.diary_no!=n.conn_key 
                                   group by n.conn_key
                                  ) aa on m.diary_no=aa.conn_key
                    left join mul_category mc ON mc.diary_no = m.diary_no AND (mc.display='Y')
                    inner join dispose d on m.diary_no=d.diary_no 
                    left outer join submaster sm ON mc.submaster_id = sm.id AND (sm.display='Y' or sm.display is null) LEFT outer JOIN subheading c ON h.subhead = c.stagecode AND (c.display = 'Y' or c.display is null) 
                    WHERE m.c_status = 'D' AND (m.diary_no=m.conn_key OR m.conn_key is null) AND us.id=$section AND disp_dt BETWEEN '".$fromDate."' AND '".$toDate."' order by m.diary_no_rec_date asc";
        }
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }




   function Not_Listed_Report($sect=null)
    {
        $sql="SELECT 
    SUBSTR(m.diary_no,1,LENGTH(m.diary_no) - 4) AS diaryno,
    SUBSTR(m.diary_no, - 4) AS diaryyear,
    m.reg_no_display, u.name, us.section_name, mc.mul_category_idd,
    CONCAT(m.pet_name,
            (CASE
                WHEN m.pno = 2 THEN 'and anr.'
                WHEN m.pno > 2 THEN 'and ors.'
                ELSE ''
            END),
            ' vs ',
            m.res_name,
            (CASE
                WHEN m.rno = 2 THEN 'and anr.'
                WHEN m.rno > 2 THEN 'and ors.'
                ELSE ''
            END)) cause,
    m.active_fil_dt,
    CASE
        WHEN
            (m.conn_key = 0 OR m.conn_key IS NULL
                OR m.conn_key = m.diary_no)
        THEN
            'M'
        ELSE CASE
            WHEN
                (m.conn_key != 0 AND m.conn_key IS NOT NULL
                    AND m.conn_key != m.diary_no)
            THEN
                'C'
        END
    END AS mainorconn
FROM
    main m
    inner join users u on m.dacode=u.usercode
    inner join usersection us on u.section=us.id
    inner join mul_category mc on m.diary_no=mc.diary_no
WHERE
    m.c_status = 'P' AND m.fil_no IS NOT NULL
        AND m.fil_no != ''
        AND m.dacode in ((select usercode from users where section=(select id from usersection where section_name='$sect'))) and m.diary_no NOT IN (SELECT 
            h.diary_no
        FROM
            heardt h
        WHERE
            (h.clno != 0 AND h.clno IS NOT NULL)
                AND (h.brd_slno != 0
                AND h.brd_slno IS NOT NULL) UNION 
        SELECT 
            h.diary_no
        FROM
            last_heardt h
        WHERE
            (h.clno != 0 AND h.clno IS NOT NULL)
                AND (h.brd_slno != 0 AND h.brd_slno IS NOT NULL AND 
                        (bench_flag = '' OR bench_flag IS NULL)))
                order by active_fil_dt;";

        $query = $this->db->query($sql);

        if($query -> num_rows() >= 1)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }

    function Section_Reg_Report()
    {
        $sql="select section_name from usersection where isda='Y'";

        $query = $this->db->query($sql);

        if($query -> num_rows() >= 1)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }

    function Section_Reg_Report_Result($section=null)
    {

        $sql="select substr(diary_no,-4) as diary_year, count(diary_no) as numb from main 
where dacode in (select usercode from users where section=(select id from usersection where section_name='$section'))
and (fil_no is null or fil_no='') and (reg_no_display is null or reg_no_display='')
and c_status='P' and (fil_dt is null or fil_dt='')
group by diary_year
order by substring(diary_no,-4) asc";


        $query = $this->db->query($sql);

        if($query -> num_rows() >= 1)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }

    }
    function Section_Unreg_Report_Result($section=null)
    {
        $sql="select substr(diary_no,-4) as diary_year, count(diary_no) as numb
from main
where dacode in (select usercode from users where section=(select id from usersection where section_name='$section'))
and c_status='P' and (fil_no is not null and fil_no!='') and (reg_no_display is not null and reg_no_display!='')
and (fil_dt is not null and fil_dt!='')
group by diary_year
order by substring(diary_no,-4) asc;";

        $query=$this->db->query($sql);
        if ($query -> num_rows() >= 1)
        {
            return $query ->result_array();
        }
        else
        {
            return false;
        }
    }

    function Pend_Report_Mod($section=null)
    {
        $sql="select rs.Name as state, GROUP_CONCAT(distinct rc.agency_name) as agency_name, us.section_name, count(distinct m.diary_no) as total_pendency from main m
		LEFT JOIN casetype c ON m.casetype_id = c.casecode
        LEFT JOIN ref_agency_code rc ON rc.id = m.ref_agency_code_id
        LEFT JOIN state rs ON rs.id_no = m.ref_agency_state_id AND rs.display = 'Y'
        LEFT JOIN users u ON u.usercode = m.dacode
        LEFT JOIN usersection us ON us.id = u.section
where m.c_status='P' AND (fil_no is null or fil_no='') and (reg_no_display is null or reg_no_display='')
and (fil_dt is null or fil_dt='')
and dacode in (select usercode from users where section=(select id from usersection where section_name='$section'))
group by state
Order by fil_dt";

        $query=$this->db->query($sql);
        if ($query -> num_rows() >= 1)
        {
            return $query ->result_array();
        }
        else
        {
            return false;
        }
    }

    function Pend_Report_Mod1($section=null)
    {
        $sql="select rs.Name as state, GROUP_CONCAT(distinct rc.agency_name) as agency_name, us.section_name, count(distinct m.diary_no) as total_pendency from main m
		LEFT JOIN casetype c ON m.casetype_id = c.casecode
        LEFT JOIN ref_agency_code rc ON rc.id = m.ref_agency_code_id
        LEFT JOIN state rs ON rs.id_no = m.ref_agency_state_id AND rs.display = 'Y'
        LEFT JOIN users u ON u.usercode = m.dacode
        LEFT JOIN usersection us ON us.id = u.section
where c_status='P' and (fil_no is not null and fil_no!='') and (reg_no_display is not null and reg_no_display!='')
and (fil_dt is not null and fil_dt!='')
and dacode in (select usercode from users where section=(select id from usersection where section_name='$section'))
group by state
Order by fil_dt";

        $query=$this->db->query($sql);
        if ($query -> num_rows() >= 1)
        {
            return $query ->result_array();
        }
        else
        {
            return false;
        }
    }

    function Matters_List($section=null,$d_year=null)
    {
        $sql="select substr(diary_no,1,length(diary_no)-4) AS diary_no, substr(diary_no,-4) as diary_year, reg_no_display, CONCAT(pet_name,
            (CASE
                WHEN pno = 2 THEN 'and anr.'
                WHEN pno > 2 THEN 'and ors.'
                ELSE ''
            END),
            ' vs ',
            res_name,
            (CASE
                WHEN rno = 2 THEN 'and anr.'
                WHEN rno > 2 THEN 'and ors.'
                ELSE ''
            END)) cause, u.name
from main m
inner join users u on m.dacode=u.usercode
where dacode in (select usercode from users where section=(select id from usersection where section_name='$section'))
and c_status='P' and (fil_no is not null and fil_no!='') and (reg_no_display is not null and reg_no_display!='')
and (fil_dt is not null and fil_dt!='') 
having diary_year=$d_year
order by substring(diary_no,-4) asc;";

        $query=$this->db->query($sql);
        if ($query -> num_rows() >= 1)
        {
            return $query ->result_array();
        }
        else
        {
            return false;
        }
    }
    function Registered_State_List($section=null,$state=null,$court=null)
    {
        $condition="";
        if($state !='' || $state != null )
        {
            $condition =" and rs.Name='$state'";
        }
        else{
            $condition =" and rs.Name is null";
        }

        $sql="select distinct substr(m.diary_no,1,length(m.diary_no)-4) AS diary_number, substr(m.diary_no,-4) as diary_year, m.diary_no, m.reg_no_display, CONCAT(m.pet_name,
            (CASE
                WHEN m.pno = 2 THEN 'and anr.'
                WHEN m.pno > 2 THEN 'and ors.'
                ELSE ''
            END),
            ' vs ',
            m.res_name,
            (CASE
                WHEN m.rno = 2 THEN 'and anr.'
                WHEN m.rno > 2 THEN 'and ors.'
                ELSE ''
            END)) cause, u.name from main m
		LEFT JOIN casetype c ON m.casetype_id = c.casecode
        LEFT JOIN ref_agency_code rc ON rc.id = m.ref_agency_code_id
        LEFT JOIN state rs ON rs.id_no = m.ref_agency_state_id AND rs.display = 'Y'
        LEFT JOIN users u ON u.usercode = m.dacode
        LEFT JOIN usersection us ON us.id = u.section
where c_status='P' and (fil_no is not null and fil_no!='') and (reg_no_display is not null and reg_no_display!='')
and (fil_dt is not null and fil_dt!='') $condition
and dacode in (select usercode from users where section=(select id from usersection where section_name='$section'))
Order by fil_dt";

        $query=$this->db->query($sql);
        if ($query -> num_rows() >= 1)
        {
            return $query ->result_array();
        }
        else
        {
            return false;
        }
    }
    function Unregistered_Matters_List($section=null,$d_year=null)
    {
        $sql="select substr(diary_no,1,length(diary_no)-4) AS diary_no, substr(diary_no,-4) as diary_year, diary_no, reg_no_display, CONCAT(pet_name,
            (CASE
                WHEN pno = 2 THEN 'and anr.'
                WHEN pno > 2 THEN 'and ors.'
                ELSE ''
            END),
            ' vs ',
            res_name,
            (CASE
                WHEN rno = 2 THEN 'and anr.'
                WHEN rno > 2 THEN 'and ors.'
                ELSE ''
            END)) cause, u.name
from main m
inner join users u on m.dacode=u.usercode
where dacode in (select usercode from users where section=(select id from usersection where section_name='$section'))
and (fil_no is null or fil_no='') and (reg_no_display is null or reg_no_display='')
and c_status='P' and (fil_dt is null or fil_dt='')
having diary_year=$d_year
order by substring(diary_no,-4) asc;";

        $query=$this->db->query($sql);
        if ($query -> num_rows() >= 1)
        {
            return $query ->result_array();
        }
        else
        {
            return false;
        }
    }

    function Unregistered_State_List($section=null,$state=null,$court=null)
    {

        $condition="";
        if($state !='' || $state != null )
        {
            $condition =" and rs.Name='$state'";
        }
        else{
            $condition =" and rs.Name is null";
        }
        $sql="select distinct substr(m.diary_no,1,length(m.diary_no)-4) AS diary_number, substr(m.diary_no,-4) as diary_year, m.diary_no, m.reg_no_display, CONCAT(m.pet_name,
            (CASE
                WHEN m.pno = 2 THEN 'and anr.'
                WHEN m.pno > 2 THEN 'and ors.'
                ELSE ''
            END),
            ' vs ',
            m.res_name,
            (CASE
                WHEN m.rno = 2 THEN 'and anr.'
                WHEN m.rno > 2 THEN 'and ors.'
                ELSE ''
            END)) cause, u.name from main m
		LEFT JOIN casetype c ON m.casetype_id = c.casecode
        LEFT JOIN ref_agency_code rc ON rc.id = m.ref_agency_code_id
        LEFT JOIN state rs ON rs.id_no = m.ref_agency_state_id AND rs.display = 'Y'
        LEFT JOIN users u ON u.usercode = m.dacode
        LEFT JOIN usersection us ON us.id = u.section
where m.c_status='P' AND (fil_no is null or fil_no='') and (reg_no_display is null or reg_no_display='')
and (fil_dt is null or fil_dt='') $condition
and dacode in (select usercode from users where section=(select id from usersection where section_name='$section'))
Order by fil_dt";

        $query=$this->db->query($sql);
        if ($query -> num_rows() >= 1)
        {
            return $query ->result_array();
        }
        else
        {
            return false;
        }
    }
    
    function get_Sub_SubjectCategory($Mcat)
    {
       // $sql="select distinct subcode1,sub_name1  from submaster where flag_use='S' and display='Y' and flag='S' order by subcode1";
           $sql="select id, subcode1,category_sc_old,sub_name1,sub_name4,
                case when (category_sc_old is not null and category_sc_old!='' and category_sc_old!=0)
                then concat('',category_sc_old,'#-#',sub_name4)
                else concat('',concat(subcode1,'',subcode2),'#-#',sub_name4)
                end as dsc from submaster where subcode1= $Mcat AND subcode2!=0 GROUP BY id,subcode1,category_sc_old, sub_name1,sub_name4";
        $query = $this->db->query($sql);

        if($query -> num_rows() >= 1)
        {
            return $query->result();
        }
        else
        {
            return false;
        }
    }

function vacationAdvancedList()
    {
       $sql=" SELECT *
                    FROM   (SELECT Concat(m.reg_no_display, ' @ ', Concat(Substr(m.diary_no, 1,
                    Length(m.diary_no)
                    - 4),
                    ' / ',
                    Substr(m.diary_no, -4)))
                    AS
                    case_no,
                    Concat(m.pet_name, ' Vs. ', m.res_name)
                    AS cause_title,
                    Date_format(m.diary_no_rec_date, '%d-%m-%Y')
                    AS filing_date,
                    CASE
                    WHEN h.main_supp_flag = 0 THEN 'Ready'
                    ELSE 'Not Ready'
                    END
                    AS status,
                    CASE
                    WHEN mf_active = 'F' THEN 'Regular'
                    ELSE 'Misc.'
                    END
                    AS casestage,
                    CASE
                    WHEN ( m.diary_no = m.conn_key
                    OR m.conn_key = 0
                    OR m.conn_key = ''
                    OR m.conn_key IS NULL ) THEN 'M'
                    ELSE 'C'
                    END
                    AS main_or_connected,
                    CASE
                    WHEN ( s.category_sc_old IS NOT NULL
                    AND s.category_sc_old != ''
                    AND s.category_sc_old != 0 ) THEN
                    Concat('(', s.category_sc_old, ')', s.sub_name1, '-', s.sub_name4)
                    ELSE
                    Concat('(', Concat(s.subcode1, '', s.subcode2), ')', s.sub_name1, '-', s.sub_name4)
                    END                                                                     AS
                    subject_category,
                    Date_format(h.next_dt, '%d-%m-%Y')                                      AS
                    next_date,
                    Tentative_section(m.diary_no)                                           AS
                    section,
                    m.diary_no_rec_date
                    FROM   heardt h
                    INNER JOIN main m
                    ON h.diary_no = m.diary_no
                    INNER JOIN mul_category mcat
                    ON h.diary_no = mcat.diary_no
                    INNER JOIN submaster s
                    ON mcat.submaster_id = s.id
                    LEFT JOIN users u
                    ON u.usercode = m.dacode
                    AND ( u.display = 'Y'
                    || u.display IS NULL )
                    LEFT JOIN usersection us
                    ON us.id = u.section
                    WHERE  m.c_status = 'P'
                    AND mcat.display = 'Y'
                    AND s.display = 'Y'
                    AND m.mf_active = 'F'
                    AND h.subhead != 818
                    AND mcat.submaster_id != 911
                    AND s.id NOT IN (SELECT id
                    FROM   submaster
                    WHERE  category_sc_old BETWEEN 301 AND 324
                    OR category_sc_old BETWEEN 401 AND 436
                    OR category_sc_old BETWEEN 801 AND 818
                    OR category_sc_old BETWEEN 1001 AND 1010
                    OR category_sc_old IN ( 1401, 1413, 1424 )
                    OR category_sc_old BETWEEN 1803 AND 1816
                    OR category_sc_old IN ( 1818, 1900, 2000, 2100,
                    2200, 2300, 2401, 2811,
                    3700 )
                    OR category_sc_old BETWEEN 2403 AND 2407
                    OR category_sc_old BETWEEN 2501 AND 2504
                    OR category_sc_old BETWEEN 3001 AND 3004
                    OR category_sc_old BETWEEN 4001 AND 4003
                    OR subcode1 IN ( 44, 45, 46, 47 ))
                    AND m.diary_no NOT IN (SELECT diary_no
                    FROM   not_before
                    WHERE  res_id = 11)
                    AND Date(diary_no_rec_date) < '2014-01-01'
                    AND h.main_supp_flag IN ( 0 )
                    AND h.board_type = 'J'
                    UNION
 SELECT Concat(m.reg_no_display, ' @ ', Concat(Substr(m.diary_no, 1,
                    Length(m.diary_no) -
                    4), ' / ',
                    Substr(m.diary_no, -4))) AS case_no,
                    Concat(m.pet_name, ' Vs. ', m.res_name)                  AS cause_title,
                    Date_format(m.diary_no_rec_date, '%d-%m-%Y')             AS filing_date
                    ,
                    CASE
                    WHEN h.main_supp_flag = 0 THEN 'Ready'
                    ELSE 'Not Ready'
                    END                                                      AS status,
                    CASE
                    WHEN mf_active = 'F' THEN 'Regular'
                    ELSE 'Misc.'
                    END                                                      AS casestage,
                    CASE
                    WHEN ( m.diary_no = m.conn_key
                    OR m.conn_key = 0
                    OR m.conn_key = ''
                    OR m.conn_key IS NULL ) THEN 'M'
                    ELSE 'C'
                    END                                                      AS
                    main_or_connected,
                    CASE
                    WHEN ( s.category_sc_old IS NOT NULL
                    AND s.category_sc_old != ''
                    AND s.category_sc_old != 0 ) THEN
                    Concat('(', s.category_sc_old, ')', s.sub_name1, '-', s.sub_name4)
                    ELSE
                    Concat('(', Concat(s.subcode1, '', s.subcode2), ')', s.sub_name1, '-', s.sub_name4)
                    END                                                      AS subject_category,
                    Date_format(h.next_dt, '%d-%m-%Y')                       AS next_date,
                    Tentative_section(m.diary_no)                            AS section,
                    m.diary_no_rec_date
                    FROM   heardt h
                    INNER JOIN main m
                    ON h.diary_no = m.diary_no
                    INNER JOIN mul_category mcat
                    ON h.diary_no = mcat.diary_no
                    INNER JOIN submaster s
                    ON mcat.submaster_id = s.id
                    LEFT JOIN users u
                    ON u.usercode = m.dacode
                    AND ( u.display = 'Y'
                    || u.display IS NULL )
                    LEFT JOIN usersection us
                    ON us.id = u.section
                    WHERE  m.c_status = 'P'
                    AND mcat.display = 'Y'
                    AND s.display = 'Y'
                    AND Date(h.next_dt) BETWEEN '2018-05-20' AND '2018-07-01'
                    AND h.listorder IN ( 4, 5,7,8 )
                    AND m.mf_active = 'F'
                    AND h.board_type = 'J') final
                    WHERE  main_or_connected = 'M'
                    ORDER  BY diary_no_rec_date ASC  ";

        $query=$this->db->query($sql);
        if ($query -> num_rows() >= 1)
        {
            return $query ->result_array();
        }
        else
        {
            return false;
        }
    }
  

function getReg_J1_Reports($category, $section,  $mcat)
    {
        $sql="";

        if(($category !="" || isset($category)) && ($section!="" || isset($section)))
        {
            $condition="sm.id =$category";
            if($category==0)
            {
                $condition="subcode1=$mcat";
            }
                $sql="SELECT us.section_name AS user_section, case when mf_active='F' then 'Regular' else 'Misc.' end as casestage,
                aa.total_connected AS group_count, 
                CASE WHEN (m.diary_no = m.conn_key OR m.conn_key = 0 OR m.conn_key = '' OR m.conn_key IS NULL) THEN 'M' ELSE 'C' END AS main_or_connected,
                sm.sub_name1, case when (category_sc_old is not null and category_sc_old!='' and category_sc_old!=0) then concat('',category_sc_old,' - ',sub_name4) else concat('',concat(subcode1,'',subcode2),' - ',sub_name4) end as subject_category,
                sm.category_sc_old,u.name alloted_to_da, 
                SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) AS diary_no, 
                SUBSTR(m.diary_no, - 4) AS diary_year, DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date, 
                m.pet_name, m.res_name, m.reg_no_display, m.c_status, 
                concat(m.reg_no_display, '@ D.No.', SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) ,'/', SUBSTR(m.diary_no, - 4)) As CaseNo,
                CASE WHEN h.main_supp_flag = 0 THEN 'Ready' ELSE 'Not Ready' END AS Ready_status , concat(bb.name,'@',bb.mobile) as pet_adv, lp.question_of_law,
                h.next_dt AS Hearing_Date,
                (select group_concat(abbreviation separator '#') from judge where find_in_set(jcode,h.coram)>=1) as next_coram FROM main m 
                left join heardt h ON m.diary_no = h.diary_no 
                LEFT JOIN users u ON u.usercode = m.dacode AND (u.display = 'Y' or u.display is null) 
                left join usersection us on us.id=u.section and us.display='Y' 
                LEFT JOIN users u1 ON u1.usercode = m.usercode AND u1.display = 'Y' 
                left join advocate aa on aa.diary_no=m.diary_no AND aa.pet_res='P' AND pet_res_no=1 AND aa.display='Y'
                left join bar bb on bb.bar_id= aa.advocate_id
                left join law_points lp on lp.diary_no=m.diary_no AND lp.display='Y' AND lp.is_verified=1
                LEFT JOIN (
                select n.conn_key,count(*) as total_connected from main m 
                inner join heardt h on m.diary_no=h.diary_no 
                inner join main n on m.diary_no=n.conn_key 
                where n.diary_no!=n.conn_key and m.c_status='P' group by n.conn_key ) aa on m.diary_no=aa.conn_key 
                inner join mul_category mc ON mc.diary_no = m.diary_no AND (mc.display='Y') 
                inner join submaster sm ON mc.submaster_id = sm.id AND (sm.display='Y' or sm.display is null)         
                WHERE 
                m.c_status = 'P' and us.id=$section and $condition";


        }
        $query = $this->db->query($sql);
       // echo $this->db->last_query();
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }
    
    function get_orUplodStatus($onDate,$usercode)
    {     

       $sql_da="SELECT name,user.empid, CASE WHEN(group_concat(usec) is NULL) THEN us.id ELSE group_concat(usec) END AS us_id, ut.id as ut_id FROM users user 
				inner join user_sec_map um on user.empid=um.empid AND um.display='Y'
				left join usersection us on user.section=us.id 
				left join usertype ut on ut.id=user.usertype where user.display='Y' and user.attend='P' and usercode='".$usercode."';";

        $query2 = $this->db->query($sql_da);
        $result=$query2->result_array();
        if(sizeof($result)>0)
        {
            $ut_id = $result[0]['ut_id'];
            $us_id = $result[0]['us_id'];
        }
        $cond = "";
        if($ut_id==14)
        {
            $cond=" and us.id=$us_id";
        }
        else if($ut_id==6 OR $ut_id==9 OR $ut_id==4 OR $ut_id==12)
        {
            $cond=" and us.id in ($us_id)";
        }
        else if($ut_id==1)
        {
            $cond="";
        }
        else if($ut_id!=14 && $ut_id!=4 && $ut_id!=6 && $ut_id!=9 && $ut_id!=12 && $ut_id!=1)
        {
            $cond=" and u.usercode=$usercode";
        }

        $sql = "select m.diary_no,concat(SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4),' / ',SUBSTR(m.diary_no, - 4)) as d_no,
            concat(pet_name,' Vs. ',res_name) as cause_title, (select tentative_section(m.diary_no)) AS user_section,m.reg_no_display, 
            u.name as DA_Name, CASE WHEN o.web_status=1 THEN 'Upload' else 'Not Upload' END as web_status, Rt.courtno,IF( cl.next_dt IS NULL, 'NA', h.brd_slno ) AS brd_prnt
            from main m
            left JOIN users u ON u.usercode = m.dacode AND (u.display = 'Y' || u.display is null)
            left join usersection us on us.id=u.section and (us.display='Y' || us.display is null)    
            left join heardt h ON m.diary_no = h.diary_no AND roster_id !=0 AND coram !=0 AND clno !=0 AND h.next_dt='" . $onDate . "'
            left join office_report_details o on o.diary_no=h.diary_no AND (o.display='Y' OR o.display is null) AND (o.order_dt= '" . $onDate . "' OR o.order_dt is null)
            inner join roster Rt ON Rt.id=h.roster_id
            inner JOIN cl_printed cl ON ( cl.next_dt = h.next_dt AND cl.m_f = h.mainhead AND cl.part = h.clno 
            AND cl.main_supp = h.main_supp_flag AND cl.roster_id = h.roster_id AND cl.display = 'Y' ) 
            where h.next_dt = '" . $onDate . "'$cond". "order by user_section";

        $query = $this->db->query($sql);

        //echo $this->db->last_query();
        if ($query->num_rows() >= 1)
            return $query->result_array();
         else
            return false;

    }

    function get_same_other_conn_pendency()
    {
       $sql = "SELECT m.section_id_new, us.section_name, COUNT(diary_no) AS total, COUNT(distinct CASE WHEN (m.diary_no = m.conn_key OR m.conn_key = 0 OR m.conn_key = '' OR m.conn_key IS NULL) and c_status='P' THEN diary_no END) AS mainCount, count(distinct CASE WHEN m.conn_key != 0 AND m.conn_key != m.diary_no and c_status='P' THEN diary_no ELSE 0 END) AS connected, (SELECT COUNT(DISTINCT CASE WHEN section_id_new = m.section_id_new THEN diary_no END) FROM main WHERE conn_key IN (SELECT diary_no FROM main WHERE c_status = 'P' AND (diary_no = conn_key OR conn_key = 0 OR conn_key = '' OR conn_key IS NULL) AND section_id_new != m.section_id_new) AND c_status = 'P' AND diary_no != conn_key) AS connected_same_main_other, (SELECT COUNT(DISTINCT CASE WHEN section_id = m.section_id_new THEN diary_no END) FROM main WHERE conn_key IN ((SELECT diary_no FROM main WHERE c_status = 'P' AND (diary_no = conn_key OR conn_key = '' OR conn_key = 0 OR conn_key IS NULL) AND section_id_new = m.section_id_new)) AND c_status = 'P' AND diary_no != conn_key) AS samesection, (SELECT COUNT(DISTINCT CASE WHEN section_id_new != m.section_id_new THEN diary_no END) FROM main WHERE conn_key IN ((SELECT diary_no FROM main WHERE c_status = 'P' AND (diary_no = conn_key OR conn_key = '' OR conn_key = 0 OR conn_key IS NULL) AND section_id_new = m.section_id_new)) AND c_status = 'P' AND diary_no != conn_key) AS othersection FROM main m INNER JOIN usersection us ON m.section_id_new = us.id WHERE c_status = 'P' AND dacode IS NOT NULL AND dacode != '' AND dacode != '0' GROUP BY m.section_id_new order by us.section_name asc;";

        $query = $this->db->query($sql);

       // echo $this->db->last_query();
        if ($query->num_rows() >= 1)
            return $query->result_array();
        else
            return false;
    }
    
function monitoring_Error_Report()
    {
        $sql="select t.*, count(id) as count_remarks from (select distinct c.diary_no,r.id,concat(m.reg_no_display,'@DNo. ',concat(SUBSTR(c.diary_no, 1, LENGTH(c.diary_no) - 4),'/',SUBSTR(c.diary_no, - 4))) AS caseno, concat(u1.empid,'@',u1.name) AS daName,us.section_name, 
              concat(u.empid,'@',u.name) AS RmrkBy, 
              r.remarks, c.ent_dt from case_verify c
              LEFT JOIN users u ON u.usercode = c.ucode AND (u.display = 'Y' or u.display is null) 
              inner join case_verify_by_sec_remark r on find_in_set(r.id,c.remark_id)>0
              inner join main m on m.diary_no=c.diary_no
              left join users u1 ON u1.usercode = m.dacode AND (u1.display = 'Y' or u1.display is null) 
              left join usersection us on us.id=u1.section and us.display='Y'
              where m.c_status = 'P' AND r.id not in (1,10)) t group by diary_no,remarks order by diary_no";

        $query = $this->db->query($sql);

        if($query -> num_rows() >= 1)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }

    function monitoring_Error_details($diary_no,$rid)
    {

          $sql="select DATE_FORMAT(c.ent_dt, '%d/%m/%Y %H:%i:%s') as ent_dt from case_verify c
              LEFT JOIN users u ON u.usercode = c.ucode AND (u.display = 'Y' or u.display is null) 
              inner join case_verify_by_sec_remark r on find_in_set(r.id,c.remark_id)>0
              inner join main m on m.diary_no=c.diary_no
              left join users u1 ON u1.usercode = m.dacode AND (u1.display = 'Y' or u1.display is null) 
              left join usersection us on us.id=u1.section and us.display='Y'
              where m.c_status = 'P' AND r.id not in (1,10) AND c.diary_no=$diary_no AND r.id=$rid order by c.ent_dt desc;";


        $query = $this->db->query($sql);

        if($query -> num_rows() >= 1)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }


    function monitoring_Error_Dawise_count()
    {
        $sql="select daName,sum(CourtRemark) as CourtRemark,
                    sum(Subhead) as Subhead,
                    sum(Purpose) as Purpose,
                    sum(CauseTitle) as CauseTitle,
                    sum(AOR_NA) as AOR_NA,
                    sum(StatutaryInfo) as StatutaryInfo,
                    sum(ProposalMissing) as ProposalMissing,
                    sum(IA) as IA,
                    sum(ROP) as ROP,
                    sum(Before_NotBefore) as Before_NotBefore   
                    
                     from (select f.daName,CASE WHEN remarks='Court Remark' THEN f.count_remarks END as CourtRemark,
                    CASE WHEN remarks='Subhead' THEN f.count_remarks END as Subhead,
                    CASE WHEN remarks='Purpose' THEN f.count_remarks END as Purpose,
                    CASE WHEN remarks='Cause Title' THEN f.count_remarks END as CauseTitle,
                    CASE WHEN remarks='AOR NA' THEN f.count_remarks END as AOR_NA,
                    CASE WHEN remarks='Statutary Info' THEN f.count_remarks END as StatutaryInfo,
                    CASE WHEN remarks='Proposal missing' THEN f.count_remarks END as ProposalMissing,
                    CASE WHEN remarks='IA' THEN f.count_remarks END as IA,
                    CASE WHEN remarks='ROP' THEN f.count_remarks END as ROP,
                    CASE WHEN remarks='Before / Not Before' THEN f.count_remarks END as Before_NotBefore
                    
                     from  (select t.*, count(id) as count_remarks from (select distinct c.diary_no,r.id, concat(u1.empid,'@',u1.name,'@SEC ',us.section_name) AS daName,us.section_name, 
                                  concat(u.empid,'@',u.name) AS RmrkBy, 
                                  r.remarks from case_verify c
                                  LEFT JOIN users u ON u.usercode = c.ucode AND (u.display = 'Y' or u.display is null) 
                                  inner join case_verify_by_sec_remark r on find_in_set(r.id,c.remark_id)>0
                                  inner join main m on m.diary_no=c.diary_no
                                  left join users u1 ON u1.usercode = m.dacode AND (u1.display = 'Y' or u1.display is null) 
                                  left join usersection us on us.id=u1.section and us.display='Y'
                                  where m.c_status = 'P' AND r.id not in (1,10)) t group by daName,remarks)f group by daName,remarks)l group by daName with rollup;";

        $query = $this->db->query($sql);

        if($query -> num_rows() >= 1)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }

     public function get_section_pendingIA($section)
     {
           $sql = "select us.section_name AS user_section, u.name alloted_to_da,
            SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) AS diary_no, SUBSTR(m.diary_no, - 4) AS diary_year,
            m.pet_name, m.res_name, 
            concat(m.reg_no_display, '@ D.No.', SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) ,'/', SUBSTR(m.diary_no, - 4)) As CaseNo, 
            m.c_status, concat(substr(m.diary_no,1,length(m.diary_no)-4),'/',substr(m.diary_no,-4)) AS diary_no,
             group_concat(if(d.doccode=8 and d.doccode1=19,concat(d.docnum,'/',d.docyear,'#->',dm.docdesc,' - ',d.other1),concat(d.docnum,'/',d.docyear,'#->',dm.docdesc))) ia_name from main m 
             join docdetails d on m.diary_no=d.diary_no 
             join docmaster dm on d.doccode=dm.doccode and d.doccode1=dm.doccode1 
             LEFT JOIN users u ON u.usercode = m.dacode AND (u.display = 'Y' or u.display is null) 
             left join usersection us on us.id=u.section and us.display='Y' 
             where m.c_status='P' and d.iastat='P' and us.section_name='$section' and d.doccode=8 
             group by m.diary_no order by diary_no_rec_date asc;";

            $query = $this->db->query($sql);
                //echo $this->db->last_query();
            if ($query->num_rows() >= 1) {
            return $query->result_array();
            } else {
                return false;
            }
    }

public function loosedoc_verify_not_verify($from_date,$to_date,$usercode)
    {
        $sql_da="SELECT name,user.empid, CASE WHEN(group_concat(usec) is NULL) THEN us.id ELSE group_concat(usec) END AS us_id, ut.id as ut_id FROM users user 
				inner join user_sec_map um on user.empid=um.empid AND um.display='Y'
				left join usersection us on user.section=us.id 
				left join usertype ut on ut.id=user.usertype where user.display='Y' and user.attend='P' and usercode='".$usercode."';";

        $query2 = $this->db->query($sql_da);
        $result=$query2->result_array();
        if(sizeof($result)>0)
        {
            $ut_id = $result[0]['ut_id'];
            $us_id = $result[0]['us_id'];
        }
        $cond = "";
        if($ut_id==14)
        {
            $cond=" and us.id=$us_id";
        }
        else if($ut_id==6 OR $ut_id==9 OR $ut_id==4 OR $ut_id==12)
        {
            $cond=" and us.id in ($us_id)";
        }
        else if($ut_id==1)
        {
            $cond="";
        }
        else if($ut_id!=14 && $ut_id!=4 && $ut_id!=6 && $ut_id!=9 && $ut_id!=12 && $ut_id!=1)
        {
            $cond=" and u.usercode=$usercode";
        }


        $sql="SELECT date1,section,sec_id,SUM(documents) AS total, COALESCE(SUM(verify)) AS verify, COALESCE(SUM(not_verify)) AS not_verify FROM
            (SELECT date1,section, sec_id, documents, 
                    CASE WHEN verified = 'V' THEN documents END AS verify,
                    CASE WHEN verified != 'V' THEN documents END AS not_verify
            FROM
                (SELECT DATE(ent_dt) AS date1, group_concat(distinct section_name) as section,group_concat(distinct us.id) as sec_id, COUNT(*) AS documents, verified
                 FROM docdetails d
                inner join main m on m.diary_no=d.diary_no
                LEFT JOIN users u ON u.usercode = m.dacode AND (u.display = 'Y' or u.display is null) 
                left join usersection us on us.id=u.section and us.display='Y'
                WHERE d.display = 'Y' $cond and m.c_status='P'
                AND DATE(ent_dt) BETWEEN '$from_date' AND '$to_date'
                GROUP BY DATE(ent_dt), verified) a) b GROUP BY date1";

        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        if ($query->num_rows() >= 1)
            return $query->result_array();
         else
            return false;

    }

    function verify_Nverify_Details($date,$flag,$section,$usercode)
    {
        if($flag=='V')
           $cond2=" and dc.verified='$flag'";
        else if($flag=='N')
            $cond2=" and dc.verified!='V'";

         $sql_da="SELECT name,user.empid, CASE WHEN(group_concat(usec) is NULL) THEN us.id ELSE group_concat(usec) END AS us_id, ut.id as ut_id FROM users user 
				inner join user_sec_map um on user.empid=um.empid AND um.display='Y'
				left join usersection us on user.section=us.id 
				left join usertype ut on ut.id=user.usertype where user.display='Y' and user.attend='P' and usercode='".$usercode."';";

        $query2 = $this->db->query($sql_da);
        $result=$query2->result_array();
        if(sizeof($result)>0)
        {
            $ut_id = $result[0]['ut_id'];
            $us_id = $result[0]['us_id'];
        }
        $cond = "";
        if($ut_id==14)
        {
            $cond=" and us.id=$us_id";
        }
        else if($ut_id==6 OR $ut_id==9 OR $ut_id==4 OR $ut_id==12)
        {
            $cond=" and us.id in ($us_id)";
        }
        else if($ut_id==1)
        {
            $cond="";
        }
        else if($ut_id!=14 && $ut_id!=4 && $ut_id!=6 && $ut_id!=9 && $ut_id!=12 && $ut_id!=1)
        {
            $cond=" and da.usercode=$usercode";
        }


        $sql="select concat(substring(dc.diary_no,1,length(dc.diary_no)-4),'/',substring(dc.diary_no,-4)) as diary_no,
             concat(m.reg_no_display, '@ D.No.', SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) ,'/', SUBSTR(m.diary_no, - 4)) As CaseNo,
            concat(pet_name,' Vs ',res_name) as causetitle,docdesc,concat(docnum,'/',docyear) as document,filedby,
            u_dak.name as dak_name,u_dak.empid as dak_empid,dc.ent_dt,da.name as da_name,da.empid as da_empid,
            us.section_name as da_section from docdetails dc 
            left join docmaster dm on dc.doccode=dm.doccode and dc.doccode1=dm.doccode1 
            inner join main m on dc.diary_no=m.diary_no 
            left join users u_dak on u_dak.usercode=dc.usercode left join users da on da.usercode=m.dacode 
            left join usersection us on us.id=da.section 
            where m.c_status='P' and date(dc.ent_dt) ='$date' $cond2 $cond and dm.display='Y' and dc.display='Y'";


        $query = $this->db->query($sql);

        if($query -> num_rows() >= 1)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }

function CtRemarks_Changeby_user($on_date)
    {
        $sql="select a.uid, concat(u.name,'@',u.empid,' SEC ',us.section_name)as uid_name,
            sum(case when newcnt = 0 then crm_count else 0 end) current_total,
            sum(case when newcnt = 1 then crm_count else 0 end) history_total,
            sum(case when newcnt >= 0 then crm_count else 0 end) total from
            (select * from (select uid, count(*) as crm_count, 0 as newcnt from (select * from case_remarks_multiple where cl_date='$on_date' group by diary_no) a group by a.uid) aa
            union
            (select uid,count(*) as crm_count, 1 as newcnt from (select uid,fil_no,cl_date from case_remarks_multiple_history where fil_no in (select diary_no from case_remarks_multiple where cl_date='$on_date' group by diary_no) and cl_date='$on_date' group by fil_no,uid) b group by b.uid)
            ) a
            left join users u on u.usercode=a.uid AND (u.display = 'Y' or u.display is null)			
            left join usersection us on us.id=u.section and us.display='Y'
            group by uid order by history_total DESC;";

        $query = $this->db->query($sql);

        if($query -> num_rows() >= 1)
            return $query->result_array();

        else
            return false;
    }




    function CtRemarks_Changeby_user_details($cl_date,$flag,$usercode)
    {
        $cond1="";
        $cond2="";
        $cond3="";
        $cond4=" crm.uid=$usercode and crm.cl_date='$cl_date' group by crm.uid,crm.diary_no ";
        /*if($flag!='M')
        {
            $cond1=" GROUP_CONCAT(distinct crmh.r_head SEPARATOR ',') As Old_Disp_Remarks, GROUP_CONCAT(distinct CONCAT(crh1.head, if(crmh.head_content!='', concat(' [', crmh.head_content, ']'),''), ' <b>By:-> ',concat(u1.name,'@',u1.empid,' SEC ',us1.section_name),' On:- ',crmh.e_date) SEPARATOR ',</b> ' ) AS Old_Rmrk_Disp, GROUP_CONCAT(distinct crmh.head_content SEPARATOR ',') AS old_Head_Content, concat(u1.name,'@',u1.empid,' SEC ',us1.section_name)as old_uid, ";
            $cond2=" inner join case_remarks_multiple_history crmh on crm.diary_no=crmh.fil_no and crmh.cl_date = crm.cl_date ";
            $cond3=" left join case_remarks_head crh1 on crh1.sno=crmh.r_head AND (crh1.display = 'Y' or crh1.display is null) left join users u1 on u1.usercode=crmh.uid AND (u1.display = 'Y' or u1.display is null) left join usersection us1 on us1.id=u1.section and us1.display='Y' ";
            $cond4=" crmh.uid=$usercode and crmh.cl_date='$cl_date' group by crmh.uid,crmh.fil_no;";
        }*/
        if($flag=='C')
        {
            $sql="select crm.diary_no,
            concat(m.reg_no_display, '@ D.No.', SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) ,'/', SUBSTR(m.diary_no, - 4)) As CaseNo, h.clno, h.brd_slno, h.tentative_cl_dt,m.pet_name, m.res_name,
            GROUP_CONCAT(distinct CONCAT(crh.head, if(crm.head_content!='', concat(' [', crm.head_content, ']'),''), ' By:-> ',concat(u.name,'@',u.empid,' SEC ',us.section_name),' On:- ',crm.e_date) SEPARATOR ', ' ) AS Rmrk_Disp,
            GROUP_CONCAT(distinct crm.head_content SEPARATOR ',') AS Head_Content,            
            IF( cl.next_dt IS NULL, 'NA', h.brd_slno ) AS brd_prnt, h.roster_id, Rt.courtno, concat(u.name,'@',u.empid,' SEC ',us.section_name)as uid from case_remarks_multiple crm
            inner join main m on m.diary_no=crm.diary_no
            left join users u on u.usercode=crm.uid AND (u.display = 'Y' or u.display is null)			
            left join usersection us on us.id=u.section and us.display='Y'
            left join case_remarks_head crh ON crh.sno=crm.r_head AND (crh.display = 'Y' or crh.display is null)
            inner join ( SELECT t1.diary_no, t1.next_dt, t1.roster_id, t1.judges, t1.mainhead, t1.subhead, t1.clno, 
                        t1.brd_slno, t1.main_supp_flag, t1.tentative_cl_dt FROM heardt t1 
                        WHERE t1.next_dt = '$cl_date' AND
                ( t1.main_supp_flag = 1 OR t1.main_supp_flag = 2 ) 
                        UNION SELECT t2.diary_no, t2.next_dt, t2.roster_id, t2.judges, t2.mainhead, t2.subhead, 
                        t2.clno, t2.brd_slno, t2.main_supp_flag, t2.tentative_cl_dt FROM last_heardt t2 
                        WHERE t2.next_dt = '$cl_date' AND
                ( t2.main_supp_flag = 1 OR t2.main_supp_flag = 2 )
                AND t2.bench_flag = '' ) h INNER JOIN main m1
                        ON ( h.diary_no = m1.diary_no AND h.next_dt = '$cl_date' AND
                            ( h.main_supp_flag = 1 OR h.main_supp_flag = 2 ) ) 
            left join roster Rt ON Rt.id=h.roster_id
            LEFT JOIN cl_printed cl ON ( cl.next_dt = h.next_dt AND cl.m_f = h.mainhead AND cl.part = h.clno AND cl.main_supp = h.main_supp_flag AND cl.roster_id = h.roster_id AND cl.display = 'Y' )
            where uid='$usercode' and crm.cl_date='$cl_date' 
            group by crm.uid,crm.diary_no;";
        }
        else {
            $sql = "select crmh.fil_no,
            concat(m.reg_no_display, '@ D.No.', SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) ,'/', SUBSTR(m.diary_no, - 4)) As CaseNo,          
            h.clno, h.brd_slno, h.tentative_cl_dt,m.pet_name, m.res_name,
            GROUP_CONCAT(distinct CONCAT(crh.head, if(crm.head_content!='', concat(' [', crm.head_content, ']'),''), ' By:-> ',concat(u.name,'@',u.empid,' SEC ',us.section_name),' On:- ',crm.e_date) SEPARATOR ', ' ) AS Rmrk_Disp,
            GROUP_CONCAT(distinct crm.head_content SEPARATOR ',') AS Head_Content,
            GROUP_CONCAT(distinct crmh.r_head SEPARATOR ',') As Old_Disp_Remarks, 
            GROUP_CONCAT(distinct CONCAT(crh1.head, if(crmh.head_content!='', concat(' [', crmh.head_content, ']'),''), ' <b>By:-> ',concat(u1.name,'@',u1.empid,' SEC ',us1.section_name),' On:- ',crmh.e_date) SEPARATOR ',</b> ' ) AS Old_Rmrk_Disp, 
            GROUP_CONCAT(distinct crmh.head_content SEPARATOR ',') AS old_Head_Content, concat(u1.name,'@',u1.empid,' SEC ',us1.section_name)as old_uid,             
            IF( cl.next_dt IS NULL, 'NA', h.brd_slno ) AS brd_prnt, h.roster_id, Rt.courtno, concat(u.name,'@',u.empid,' SEC ',us.section_name)as uid
            from case_remarks_multiple_history crmh
            inner join main m on m.diary_no=crmh.fil_no
            left join users u1 on u1.usercode=crmh.uid AND (u1.display = 'Y' or u1.display is null) 
            left join usersection us1 on us1.id=u1.section and us1.display='Y' 
            left join case_remarks_head crh1 on crh1.sno=crmh.r_head AND (crh1.display = 'Y' or crh1.display is null)
            inner join case_remarks_multiple crm on crmh.fil_no=crm.diary_no and crm.cl_date='$cl_date'
            left join users u on u.usercode=crm.uid AND (u.display = 'Y' or u.display is null)			
            left join usersection us on us.id=u.section and us.display='Y'
            left join case_remarks_head crh ON crh.sno=crm.r_head AND (crh.display = 'Y' or crh.display is null)
            inner join ( SELECT t1.diary_no, t1.next_dt, t1.roster_id, t1.judges, t1.mainhead, t1.subhead, t1.clno, 
                        t1.brd_slno, t1.main_supp_flag, t1.tentative_cl_dt FROM heardt t1 
                        WHERE t1.next_dt = '$cl_date' AND
                ( t1.main_supp_flag = 1 OR t1.main_supp_flag = 2 ) 
                        UNION SELECT t2.diary_no, t2.next_dt, t2.roster_id, t2.judges, t2.mainhead, t2.subhead, 
                        t2.clno, t2.brd_slno, t2.main_supp_flag, t2.tentative_cl_dt FROM last_heardt t2 
                        WHERE t2.next_dt = '$cl_date' AND
                ( t2.main_supp_flag = 1 OR t2.main_supp_flag = 2 )
                AND t2.bench_flag = '' ) h INNER JOIN main m1
                        ON ( h.diary_no = m1.diary_no AND h.next_dt = '$cl_date' AND
                            ( h.main_supp_flag = 1 OR h.main_supp_flag = 2 ) ) 
            left join roster Rt ON Rt.id=h.roster_id
            LEFT JOIN cl_printed cl ON ( cl.next_dt = h.next_dt AND cl.m_f = h.mainhead AND cl.part = h.clno AND cl.main_supp = h.main_supp_flag AND cl.roster_id = h.roster_id AND cl.display = 'Y' )            
            where 
            crmh.uid=$usercode and crmh.cl_date='$cl_date' 
            group by crmh.uid,crmh.fil_no;";
        }

        $query = $this->db->query($sql);

        if($query -> num_rows() >= 1)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }

    }

function case_listed_Advance_Daily_dawise($usercode)
    {
        $sql_da="SELECT name,user.empid, CASE WHEN(group_concat(usec) is NULL) THEN us.id ELSE group_concat(usec) END AS us_id, ut.id as ut_id FROM users user 
        inner join user_sec_map um on user.empid=um.empid AND um.display='Y'
        left join usersection us on user.section=us.id 
        left join usertype ut on ut.id=user.usertype where user.display='Y' and user.attend='P' and usercode='".$usercode."';";

        $query2 = $this->db->query($sql_da);
        $result=$query2->result_array();
        if(sizeof($result)>0)
        {
            $ut_id = $result[0]['ut_id'];
            $us_id = $result[0]['us_id'];
        }
        $cond = "";
        if($ut_id==14)
        {
            $cond=" where us.id=$us_id";
        }
        else if($ut_id==6 OR $ut_id==9 OR $ut_id==4 OR $ut_id==12)
        {
            $cond=" where us.id in ($us_id)";
        }
        else if($ut_id==1 OR $ut_id==3)
        {
            $cond="";
        }
        else if($ut_id!=14 && $ut_id!=4 && $ut_id!=6 && $ut_id!=9 && $ut_id!=12 && $ut_id!=1)
        {
            $cond=" where u.usercode=$usercode";
        }

        $sql=   "select ListType,date_format(next_dt,'%d-%m-%Y') as cl_date,CASE WHEN board_type='J' THEN 'COURT' WHEN board_type='C' THEN 'CHAMBER' WHEN board_type='R' THEN 'REGISTRAR' END AS board_type,courtno,clno, brd_slno, concat(m.reg_no_display, '@ D.No.', SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) ,'/', SUBSTR(m.diary_no, - 4)) As CaseNo,  m.pet_name, m.res_name,
                concat(u.name,'@',u.empid,' SEC ',us.section_name) as uid
                             from (
                select 'Final List' ListType, h.diary_no, h.next_dt, h.board_type,Rt.courtno,h.clno,h.brd_slno from main m
                inner join heardt h on h.diary_no=m.diary_no 
                left join roster Rt ON Rt.id=h.roster_id
                LEFT JOIN cl_printed cl ON  cl.next_dt = h.next_dt AND cl.m_f = h.mainhead AND cl.part = h.clno 
                AND cl.main_supp = h.main_supp_flag AND cl.roster_id = h.roster_id AND cl.display = 'Y'
                where cl.next_dt is not null and 
                date(h.next_dt) >= curdate() AND m.c_status='P' and (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
                union
                select 'Advance List' ListType, h.diary_no, h.next_dt, h.board_type,'AL',h.clno,h.brd_slno from main m
                left join advance_allocated h on m.diary_no=h.diary_no and (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
                where date(h.next_dt) >= curdate() AND m.c_status='P' and (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
                ) a
                inner join main m on m.diary_no=a.diary_no
                left join users u on u.usercode=m.dacode AND (u.display = 'Y' or u.display is null)      
                left join usersection us on us.id=u.section and us.display='Y'
                $cond
                 order by if(ListType = 'Advance', 1,2) ASC, case when board_type = 'J' then 1 when board_type = 'C' then 2 else 3 end, cl_date desc";

        $query = $this->db->query($sql);

        if($query -> num_rows() >= 1)
           return $query->result_array();
        else
           return false;
    }

function getUploadedJudgmentOrdersList($reportType=null,$fromDate=null,$toDate=null)
    {

        $condition = "";

            if ($reportType == 1) // 1 for Receive Report
            {
                $condition = " and o.type='J'";
            } elseif ($reportType == 2) // 2 for Dispatch Report
            {
                $condition = " and o.type='O'";
            }

       $sql="SELECT m.diary_no,
            concat(m.reg_no_display,' @ ',concat(SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4),' / ',SUBSTR(m.diary_no, - 4))) as case_no,         
            concat(m.pet_name,' Vs. ',m.res_name) as cause_title,
            o.orderdate as order_date,
            '' as no_of_page,
            case when (s.category_sc_old is not null and s.category_sc_old!='' and s.category_sc_old!=0)
               then concat('(',s.category_sc_old,')',s.sub_name1,'-',s.sub_name4)
            else                                   
                concat('(',concat(s.subcode1,'',s.subcode2),')',s.sub_name1,'-',s.sub_name4)
            end as subject_category,
            concat('http://xxxx/supreme_court','/',o.pdfname) as disp_path,
            concat('/home/reports','/',o.pdfname) as path,
            rac.agency_name
            from ordernet o 
            inner join main m on o.diary_no=m.diary_no
            left JOIN mul_category mcat ON o.diary_no = mcat.diary_no
            left JOIN submaster s ON mcat.submaster_id = s.id  
            left join lowerct lct on lct.diary_no = m.diary_no
            left join ref_agency_code rac on lct.l_state=rac.cmis_state_id and lct.l_dist=rac.id
            where orderdate between  '".$fromDate."' and '".$toDate."'
            and o.display='Y'  and lct.is_order_challenged='Y'
            $condition         
            group by diary_no,o.orderdate
            order by orderdate asc";



        $query = $this->db->query($sql);
        if ($query->num_rows() >= 1) {
            //echo $sql;
            return $query->result_array();
        }
        else
            return false;
    }

    function get_DA_sectionwise($section=0)
    {
        $query="select usercode,concat(name,', ',type_name) as name,empid,section_name from users user 
                    inner join usersection us on user.section=us.id 
                    inner join usertype ut on ut.id=user.usertype
                    where section=$section and user.display='Y' and ut.id in (14,50,51,17)
                    order by type_name,empid";
        $result=$this->db->query($query);
        return $result->result_array();
    }

      
      

 
        

  }
  
