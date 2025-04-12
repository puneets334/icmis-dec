<?php

namespace App\Models\ManagementReport;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;
use Illuminate\Support\Facades\DB;


class RopuploadedModel extends Model
{

    protected $db;
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    function show_count_between_dates($from_date,$to_date)
    {        
        $sql="select listed.listing_date,count(distinct(listed.diary_no)) as listed,
                count(distinct(case when o.diary_no is not null then o.diary_no end)) rop_uploaded,
                count(distinct(case when crm.diary_no is not null then o.diary_no end)) rop_updated 
                from (select distinct rj.roster_id,m.diary_no ,hd.next_dt as listing_date,
                m.pet_name as petitioner_name,m.res_name as respondent_name,r.courtno as court_number,
                hd.brd_slno as item_number, 
                case when hd.listed_ia ='' then m.reg_no_display else concat('IA ',hd.listed_ia,' in ',m.reg_no_display) 
                end as registration_number_desc,m.pno,m.rno from heardt hd 
                inner join main m on hd.diary_no=m.diary_no 
                inner join master.roster_judge rj on hd.roster_id=rj.roster_id 
                inner join master.roster r on rj.roster_id=r.id 
                INNER JOIN cl_printed cp on hd.roster_id=cp.roster_id and hd.next_dt=cp.next_dt AND 
                hd.brd_slno between cp.from_brd_no and cp.to_brd_no AND hd.clno=cp.part 
                where cp.display='Y' and hd.main_supp_flag !=0 AND 
                (hd.conn_key is null or hd.conn_key=0 or hd.conn_key=hd.diary_no) 
                and hd.brd_slno is not null and hd.brd_slno>0 
                and hd.next_dt between '$from_date' and '$to_date' 
                union select distinct rj.roster_id,m.diary_no ,hd.next_dt as listing_date,
                m.pet_name as petitioner_name,m.res_name as respondent_name,r.courtno as court_number,
                hd.brd_slno as item_number, 
                case when hd.listed_ia ='' then m.reg_no_display else concat('IA ',hd.listed_ia,' in ',m.reg_no_display) end as registration_number_desc,
                m.pno,m.rno from last_heardt hd 
                inner join main m on hd.diary_no=m.diary_no 
                inner join master.roster_judge rj on hd.roster_id=rj.roster_id 
                inner join master.roster r on rj.roster_id=r.id 
                INNER JOIN cl_printed cp on hd.roster_id=cp.roster_id and hd.next_dt=cp.next_dt AND 
                hd.brd_slno between cp.from_brd_no and cp.to_brd_no AND hd.clno=cp.part 
                where cp.display='Y' and hd.main_supp_flag !=0 
                and (hd.conn_key is null or hd.conn_key=0 or hd.conn_key=hd.diary_no) 
                and hd.brd_slno is not null and hd.brd_slno>0 and hd.bench_flag='' 
                and hd.next_dt between '$from_date' and '$to_date'
                union select distinct rj.roster_id, m.diary_no, mm.date_on_decided AS listing_date,
                m.pet_name AS petitioner_name, m.res_name AS respondent_name, r.courtno AS court_number,
                mm.m_brd_slno AS item_number, m.reg_no_display AS registration_number_desc,
                m.pno,m.rno from mention_memo mm 
                INNER JOIN main m ON CAST(mm.diary_no AS bigint) = m.diary_no 
                INNER JOIN master.roster_judge rj ON mm.m_roster_id = rj.roster_id 
                INNER JOIN master.roster r ON rj.roster_id = r.id 
                where mm.display='Y' 
                and (m.conn_key IS NULL OR CAST(m.conn_key AS text) = '0' OR m.conn_key = mm.diary_no)
                AND mm.m_brd_slno IS NOT NULL AND mm.m_brd_slno > 0 
                AND mm.date_on_decided between '$from_date' and '$to_date' ) 
                listed left join ordernet o on listed.diary_no=o.diary_no 
                and listed.listing_date=o.orderdate and o.type='O' 
                left join case_remarks_multiple crm on listed.diary_no=crm.diary_no 
                and listed.listing_date=crm.cl_date group by listed.listing_date";        
        $query = $this->db->query($sql);
        return $query->getResultArray();
    }

    function show_count($listing_date)
    {
        $sql="select listed.court_number as court_number,count(distinct(listed.diary_no)) as listed,
            count(distinct(case when o.diary_no is not null then o.diary_no end)) rop_uploaded,
            count(distinct(case when crm.diary_no is not null then o.diary_no end)) rop_updated 
            from (select distinct rj.roster_id,m.diary_no ,hd.next_dt as listing_date,
            m.pet_name as petitioner_name,m.res_name as respondent_name,r.courtno as court_number,
            hd.brd_slno as item_number, case when hd.listed_ia ='' then m.reg_no_display 
            else concat('IA ',hd.listed_ia,' in ',m.reg_no_display) 
            end as registration_number_desc,m.pno,m.rno from heardt hd 
            inner join main m on hd.diary_no=m.diary_no 
            inner join master.roster_judge rj on hd.roster_id=rj.roster_id 
            inner join master.roster r on rj.roster_id=r.id 
            INNER JOIN cl_printed cp on hd.roster_id=cp.roster_id AND 
            hd.next_dt=cp.next_dt and hd.brd_slno between cp.from_brd_no 
            and cp.to_brd_no AND hd.clno=cp.part where cp.display='Y' 
            and hd.main_supp_flag !=0 and (hd.conn_key is null or hd.conn_key=0 or hd.conn_key=hd.diary_no) 
            and hd.brd_slno is not null and hd.brd_slno>0 and hd.next_dt='$listing_date' 
            union select distinct rj.roster_id,m.diary_no ,hd.next_dt as listing_date,
            m.pet_name as petitioner_name,m.res_name as respondent_name,r.courtno as court_number,
            hd.brd_slno as item_number, case when hd.listed_ia ='' THEN 
            m.reg_no_display else concat('IA ',hd.listed_ia,' in ',m.reg_no_display) 
            end as registration_number_desc,m.pno,m.rno from last_heardt hd 
            inner join main m on hd.diary_no=m.diary_no inner join master.roster_judge rj on hd.roster_id=rj.roster_id 
            inner join master.roster r on rj.roster_id=r.id INNER JOIN cl_printed cp on hd.roster_id=cp.roster_id AND 
            hd.next_dt=cp.next_dt and hd.brd_slno between cp.from_brd_no and cp.to_brd_no 
            AND hd.clno=cp.part where cp.display='Y' and hd.main_supp_flag !=0 
            and (hd.conn_key is null or hd.conn_key=0 or hd.conn_key=hd.diary_no) 
            and hd.brd_slno is not null and hd.brd_slno>0 and hd.bench_flag='' and hd.next_dt='$listing_date' 
            union select distinct rj.roster_id, m.diary_no, mm.date_on_decided AS listing_date, 
            m.pet_name AS petitioner_name, m.res_name AS respondent_name, r.courtno AS court_number,
            mm.m_brd_slno AS item_number, m.reg_no_display AS registration_number_desc,m.pno,
            m.rno from mention_memo mm
            INNER JOIN main m ON CAST(mm.diary_no AS bigint) = m.diary_no 
            INNER JOIN master.roster_judge rj ON mm.m_roster_id = rj.roster_id 
            INNER JOIN master.roster r ON rj.roster_id = r.id 
            where mm.display='Y'
            and (m.conn_key IS NULL OR CAST(m.conn_key AS text) = '0' OR m.conn_key = mm.diary_no)
            AND mm.m_brd_slno IS NOT NULL AND mm.m_brd_slno > 0 AND mm.date_on_decided = '$listing_date' ) 
            listed left join ordernet o on listed.diary_no=o.diary_no and listed.listing_date=o.orderdate 
            and o.type='O' left join case_remarks_multiple crm on listed.diary_no=crm.diary_no AND 
            listed.listing_date=crm.cl_date group by listed.court_number";                                      
            $query = $this->db->query($sql);
            return $query->getResultArray();
    }

    function show_details($listing_date,$cno)
    {
        $sql="select distinct listed.diary_no,listed.listing_date,listed.petitioner_name,listed.respondent_name,
listed.court_number,listed.item_number ,listed.registration_number_desc,
case when o.diary_no is not null then 1 else 0 end if_uploaded,o.ent_dt as uploaded_on,
(select concat(name ,'(',empid,')') from master.users where usercode=o.usercode) as uploaded_by,
case when crm.diary_no is not null then 1 else 0 end if_updated,crm.e_date as updated_on,
(select concat(name ,'(',empid,')') from master.users where usercode=crm.uid) as updated_by 
from (select distinct rj.roster_id,m.diary_no ,hd.next_dt as listing_date,m.pet_name as petitioner_name,
m.res_name as respondent_name,r.courtno as court_number,hd.brd_slno as item_number, 
case when hd.listed_ia ='' then m.reg_no_display else concat('IA ',hd.listed_ia,' in ',m.reg_no_display) 
end as registration_number_desc,m.pno,m.rno from heardt hd 
inner join main m on hd.diary_no=m.diary_no 
inner join master.roster_judge rj on hd.roster_id=rj.roster_id 
inner join master.roster r on rj.roster_id=r.id 
INNER JOIN cl_printed cp on hd.roster_id=cp.roster_id AND 
hd.next_dt=cp.next_dt and hd.brd_slno between cp.from_brd_no and cp.to_brd_no 
AND hd.clno=cp.part where cp.display='Y' and hd.main_supp_flag !=0 
and (hd.conn_key is null or hd.conn_key=0 or hd.conn_key=hd.diary_no) AND 
hd.brd_slno is not null and hd.brd_slno>0 AND 
hd.next_dt='$listing_date' and r.courtno=$cno 
union select distinct rj.roster_id,m.diary_no ,hd.next_dt as listing_date,
m.pet_name as petitioner_name,m.res_name as respondent_name,
r.courtno as court_number,hd.brd_slno as item_number, 
case when hd.listed_ia ='' then m.reg_no_display 
else concat('IA ',hd.listed_ia,' in ',m.reg_no_display) end as registration_number_desc,
m.pno,m.rno from last_heardt hd inner join main m on hd.diary_no=m.diary_no 
inner join master.roster_judge rj on hd.roster_id=rj.roster_id 
inner join master.roster r on rj.roster_id=r.id 
INNER JOIN cl_printed cp on hd.roster_id=cp.roster_id 
and hd.next_dt=cp.next_dt and hd.brd_slno 
between cp.from_brd_no and cp.to_brd_no AND hd.clno=cp.part where cp.display='Y' 
and hd.main_supp_flag !=0 
and (hd.conn_key is null or hd.conn_key=0 or hd.conn_key=hd.diary_no) 
and hd.brd_slno is not null and hd.brd_slno>0 
and hd.bench_flag='' and hd.next_dt='$listing_date' and r.courtno=$cno 
union select distinct rj.roster_id, m.diary_no, 
mm.date_on_decided AS listing_date, m.pet_name AS petitioner_name, m.res_name AS respondent_name, 
r.courtno AS court_number,mm.m_brd_slno AS item_number, 
m.reg_no_display AS registration_number_desc,m.pno,m.rno from mention_memo mm 
INNER JOIN main m ON CAST(mm.diary_no AS bigint) = m.diary_no  
INNER JOIN master.roster_judge rj ON mm.m_roster_id = rj.roster_id 
INNER JOIN master.roster r ON rj.roster_id = r.id 
where mm.display='Y' 
and (m.conn_key IS NULL OR CAST(m.conn_key AS text) = '0' OR m.conn_key = mm.diary_no)
AND mm.m_brd_slno IS NOT NULL AND mm.m_brd_slno > 0 
AND mm.date_on_decided = '$listing_date' and r.courtno=$cno ) listed 
left join ordernet o on listed.diary_no=o.diary_no AND 
listed.listing_date=o.orderdate and o.type='O' 
left join case_remarks_multiple crm on listed.diary_no=crm.diary_no 
and listed.listing_date=crm.cl_date order by item_number";
        
        $query = $this->db->query($sql);
        return $query->getResultArray();
    }

    


}
