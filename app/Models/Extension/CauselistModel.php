<?php
namespace App\Models\Extension;

use CodeIgniter\Model;

class CauselistModel extends Model
{


    public function __construct()
    {
        parent::__construct();

        $db = \Config\Database::connect();
    }

    function getListingDates()
    {
        $db = \Config\Database::connect();
        $query = $db->table('heardt')
            ->where('mainhead','M')->where('next_dt>=',date('Y-m-d'))
            ->groupStart()->where('main_supp_flag','1')->orWhere('main_supp_flag','2')->groupEnd()
            ->select('next_dt')->groupBy('next_dt');

        $result=$query->get()->getResultArray();
        return $result;

    }

    function get_data($data)
    {
        $board_condition=$ma_condition=$section_condition=$main_condition=$court_condition=$received_condition=$scanned_condition=$order_condition='';
        if($data['board_type']!=0)
        {
            $board_condition=" and h.board_type='$data[board_type]'";
        }
        if($data['main_suppl']==0)
        {
            $main_condition=" and (h.main_supp_flag = 1 OR h.main_supp_flag = 2)";
        }
        else if($data['main_suppl']==1)
        {
            $main_condition=" and (h.main_supp_flag = 1)";
        }
        else if($data['main_suppl']==2)
        {
            $main_condition=" and (h.main_supp_flag = 2)";
        }
        if($data['ma_cc_crlm']==0)
        {
            $ma_condition=" and (m.casetype_id NOT IN (39,13,14,15,16,9,10,19,20,25,26) AND m.nature != '6')";
        }

        if($data['courtno']!=0)
        {
            $ma_condition=" AND r.courtno =$data[courtno]";
        }
        if(!empty($data['sec_id']) && $data['sec_id']!=0)
        {
            $selected_section=get_selected_values($data['sec_id']);
            $section_condition=" and da_us.id in($selected_section)";

        }
        if($data['received']!=0)
        {
            if($data['received']==1)
                $received_condition=" AND (ft.remarks='SCN -> IB-Ex' and ft.r_by_empid!=0)";
            else if($data['received']==2)
                $received_condition=" AND ((ft.remarks='SCN -> IB-Ex' and ft.r_by_empid=0) OR (ft.remarks!='SCN -> IB-Ex'))";
        }

        if($data['scn_sts']!=0)
        {
            if($data['scn_sts']==1)
                $received_condition=" and i.diary_no is not null";
            else if($data['scn_sts']==2)
                $received_condition=" and i.diary_no is null";
        }

        if($data['orderby']!=0)
        {
            if($data['orderby'] == "1"){
                $orderby = "courtno, ";
            }
            else if($data['orderby'] == "2"){
                $orderby = "da_us.id, ";
            }
            else if($data['orderby'] == "3"){
                $orderby = "courtno, brd_slno, ";
            }
        }

        $next_dt = '';
        if($data['listing_date'] != '0')
        {
            $next_dt = " and h.next_dt = '$data[listing_date]' ";
        }
      
      $sql="select j.* from(select distinct cct.ent_dt conct_ent_dt,i.file_id,cl.id as is_printed,r.courtno, lp.purpose,
                                ct.short_description,date_part('year',m.active_fil_dt) fyr,active_reg_year, active_fil_dt, reg_no_display, 
                                active_fil_no, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date,
                                h.diary_no,h.next_dt,h.brd_slno,h.conn_key,da.name as da_name, da.empid as da_empid,da_us.section_name as da_sec,
                                case when ft_u.empid is not null then ft_u.name||'['||ft_u.empid||']'||to_char(ft.rece_dt,'dd-mm-YYYY HH24:MI:SS') 
                                else case when fth_u.empid is not null then fth_u.name||'['||fth_u.empid||']'||to_char(fth.rece_dt,'dd-mm-YYYY HH24:MI:SS')  else 'Not Received' end end as ib_ext,
                                concat(ft_u_d.name,'[',ft_u_d.empid,']') as ib_da,string_agg(distinct concat(docnum,'/',docyear,'-',docdesc),',') as doc_details,
                                string_agg(distinct concat(b_p.name,a_p.adv,'(',a_p.adv_type,')'),',') as pet_adv, string_agg(distinct concat(b_r.name,a_r.adv,'(',a_r.adv_type,')'),',') as res_adv 
                                from heardt h join main m on m.diary_no=h.diary_no join master.listing_purpose lp on lp.code=h.listorder and lp.display='Y' 
                                join master.roster r on r.id=h.roster_id and r.display='Y' 
                                left join cl_printed cl on cl.next_dt =h.next_dt and cl.m_f =h.mainhead and cl.part=h.clno and cl.roster_id =h.roster_id and cl.display ='Y' 
                                left join master.casetype ct on m.active_casetype_id =ct.casecode left join indexing i on i.diary_no=m.diary_no and i.display='Y' and i.file_id is not null 
                                left join fil_trap ft on m.diary_no=ft.diary_no left join conct cct on m.diary_no =cct.diary_no and cct.list='Y' 
                                left join fil_trap_his fth on m.diary_no=fth.diary_no left join master.users ft_u on ft.r_by_empid=ft_u.empid and ft_u.section = 77 and ft_u.display = 'Y' 
                                left join master.users fth_u on fth_u.empid=fth.r_by_empid and fth_u.section = 77 and fth_u.display = 'Y' 
                                left join advocate a_p on h.diary_no=a_p.diary_no and a_p.display='Y' and a_p.pet_res='P' 
                                left join master.bar b_p on a_p.advocate_id=b_p.bar_id and b_p.isdead !='Y' 
                                left join advocate a_r on h.diary_no=a_r.diary_no and a_r.display='Y' and a_r.pet_res='R' 
                                left join master.bar b_r on a_r.advocate_id=b_r.bar_id and b_r.isdead !='Y'
                                left join master.users ft_u_d on ft.d_to_empid=ft_u_d.empid and ft.remarks like '%IB-Ex%' 
                                left join docdetails d on h.diary_no=d.diary_no and d.display='Y' 
                                left join master.docmaster dm on d.doccode1=dm.doccode1 and d.doccode=dm.doccode and dm.display='Y'
                                left join master.users da on m.dacode=da.usercode left join master.usersection da_us on da.section=da_us.id
                                where h.mainhead = '$data[main_regular]' $next_dt $court_condition $board_condition $main_condition $ma_condition $section_condition $received_condition $scanned_condition
                                AND m.c_status = 'P' AND h.clno > 0 AND h.brd_slno > 0 AND h.roster_id > 0 AND m.diary_no IS NOT NULL 
                                group by h.diary_no,cct.ent_dt,i.file_id,cl.id,r.courtno, lp.purpose, ct.short_description,m.active_fil_dt,active_reg_year, 
                                active_fil_dt, reg_no_display, active_fil_no, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, 
                                diary_no_rec_date,ft_u.empid,fth_u.empid,ft_u.name,fth_u.name,ft.rece_dt,fth.rece_dt,ft_u_d.name,ft_u_d.empid,da.name,da.empid,da_us.section_name) j 
                                left join last_heardt lh on j.diary_no = lh.diary_no AND j.next_dt != lh.next_dt AND lh.judges != '0' 
                                AND lh.judges IS NOT NULL AND lh.brd_slno > 0 AND (lh.bench_flag = '' OR lh.bench_flag IS NULL) 
                                WHERE lh.diary_no IS null ORDER BY $order_condition courtno, j.brd_slno, case when j.conn_key=j.diary_no then null 
                                else diary_no_rec_date end ,case when j.conct_ent_dt is not null then j.conct_ent_dt end asc";
                                // pr($sql);
        $query = $this->db->query($sql);
        // pr($query);
        $result = $query->getResultArray();
//        echo $query->getNumRows();
//        echo "ttt";
//        echo $this->db->getLastQuery(); exit();
        if($query->getNumRows() > 0)
        {
            return $result;
        }else{
            return false;
        }
        
    }



}


?>