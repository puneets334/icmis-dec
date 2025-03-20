<?php

namespace App\Models\Filing;

use CodeIgniter\Model;
class Model_statistical_report extends Model
{

    public function data_generation()
    {
        $cur_time=date('H:i');
        $current_date=date('Y-m-d'); // current_date
        // $current_date='2023-10-06'; // for testing

        $sql=" select  sum(filed) filed,sum(old_efiled) old_efiled,sum(sc_efm_filed) as sc_efm_filed,sum(phy_filed) phy_filed,sum(registered) registered,sum(refiled) refiled,sum(checked_verified) checked_verified,sum(verified) verified,sum(tag_verified) tag_verified,sum(verification_re_filing) verification_re_filing,sum(verification_re_filing_reg) verification_re_filing_reg,sum(fil_allot) fil_allot,sum(fil_comp) fil_comp,sum(fil_rem) fil_rem,sum(refil_allot) refil_allot,sum(refil_comp) refil_comp,sum(refil_rem) refil_rem from(
select count(1) as filed,count(case when ack_id!=0 and ack_rec_dt is not null then 1 end) old_efiled,count(efiled_diary) as sc_efm_filed,
count(case when ack_id=0 and ack_rec_dt = '' and efiled_diary is null then 1 end) phy_filed,0 as registered, 0 as refiled,0 as checked_verified,0 as verified,0 as tag_verified,0 as verification_re_filing,0 as verification_re_filing_reg,0 as fil_allot,0 as fil_comp,0 as fil_rem,0 as refil_allot,0 as refil_comp,0 as refil_rem from
(select m.diary_no,ack_id,ack_rec_dt,fil_dt,ec.diary_no as efiled_diary from  main m left join efiled_cases ec on ec.diary_no=m.diary_no and efiled_type='new_case' where date(diary_no_rec_date)='$current_date') a
union
select 0 as filed,0 as old_efiled,0 as sc_efm_filed, 0 as phy_filed,count(1) as registered ,0 as refiled,0 as checked_verified,0 as verified,0 as tag_verified,0 as verification_re_filing,0 as verification_re_filing_reg,0 as fil_allot,0 as fil_comp,0 as fil_rem,0 as refil_allot,0 as refil_comp,0 as refil_rem  from main where date(fil_dt)='$current_date'
union
SELECT 0 as filed,0 as old_efiled,0 as sc_efm_filed, 0 as phy_filed,0 as registered ,count(distinct diary_no) refiled,0 as checked_verified,0 as verified,0 as tag_verified,0 as verification_re_filing,0 as verification_re_filing_reg,0 as fil_allot,0 as fil_comp,0 as fil_rem,0 as refil_allot,0 as refil_comp,0 as refil_rem  FROM obj_save WHERE DATE(rm_dt) = '$current_date' AND display='Y'
union
SELECT 0 as filed,0 as old_efiled,0 as sc_efm_filed, 0 as phy_filed,0 as registered ,0  refiled,sum(case when date(verification_date) ='$current_date' then 1 else 0 end) checked_verified,SUM(CASE when verification_status = '0' and date(verification_date) ='$current_date' THEN 1 ELSE 0 END)verified,sum(case when verification_status='1' and c_status='P' then 1 else 0 end ) tag_verified,0 as verification_re_filing,0 as verification_re_filing_reg,0 as fil_allot,0 as fil_comp,0 as fil_rem,0 as refil_allot,0 as refil_comp,0 as refil_rem FROM defects_verification d left join main m on  d.diary_no=m.diary_no
union
SELECT  0 as filed,0 as old_efiled,0 as sc_efm_filed, 0 as phy_filed,0 as registered ,0  refiled, 0 as checked_verified,0 as verified,0 as tag_verified,  COUNT(*) verification_re_filing,SUM(CASE WHEN fil_dt is null THEN 1 ELSE 0 END) verification_re_filing_reg,0 as fil_allot,0 as fil_comp,0 as fil_rem,0 as refil_allot,0 as refil_comp,0 as refil_rem FROM (SELECT a.diary_no, m.fil_no, m.fil_dt, (SELECT count( diary_no )
FROM obj_save aa
WHERE aa.diary_no = a.diary_no
AND aa.display = 'Y'
AND rm_dt is null
)ss FROM obj_save a LEFT JOIN heardt b ON a.diary_no = b.diary_no LEFT JOIN main m ON a.diary_no = m.diary_no
WHERE a.display = 'Y'
AND ((b.diary_no IS NULL) OR (b.next_dt is null AND b.listorder =0 AND subhead =0 AND mainhead = 'M') OR
(b.next_dt is null AND mainhead IS NULL )) AND c_status = 'P' GROUP BY a.diary_no,m.fil_no,m.fil_dt)bb WHERE ss =0
union
select  0 as filed,0 as old_efiled,0 as sc_efm_filed, 0 as phy_filed,0 as registered ,0  refiled, 0 as checked_verified,0 as verified,0 as tag_verified,  0 as verification_re_filing,0 as verification_re_filing_reg,fil_allot,fil_comp,fil_allot-fil_comp fil_rem,refil_allot,refil_comp,refil_allot-refil_comp refil_rem from(select count(distinct case when remarks like 'DE -> SCR' then diary_no end) fil_allot,count(distinct case when remarks like 'DE -> SCR' and date(comp_dt) is null then diary_no end) fil_comp,count(distinct case when remarks like 'FDR -> SCR' then diary_no end) refil_allot,count(distinct case when remarks like 'FDR -> SCR' and date(comp_dt) is null then diary_no end) refil_comp from(
select diary_no,remarks,comp_dt from fil_trap where (remarks like 'DE -> SCR' or remarks like 'FDR -> SCR') and date(disp_dt)='$current_date'
union
select diary_no,remarks,comp_dt from fil_trap_his where (remarks like 'DE -> SCR' or remarks like 'FDR -> SCR') and date(disp_dt)='$current_date')a) b)a
";
        $query = $this->db->query($sql);
        $response=false;
        if($query->getNumRows() >= 1) {
            $row=$query->getRowArray();
            if (!empty($row)){
                $filing_stats_data = [
                    'filing_date'=>date("Y-m-d"),
                    'updation_time'=>$cur_time,
                    'total_filed'=>$row['filed'],
                    'old_efiled'=>$row['old_efiled'],
                    'new_efiled'=>$row['sc_efm_filed'],
                    'physical_filed'=>$row['phy_filed'],
                    'registered'=>$row['registered'],
                    'refiled'=>$row['refiled'],
                    'checked_verified'=>$row['checked_verified'],

                    'verified'=>$row['verified'],
                    'tagging_verification'=>$row['tag_verified'],
                    'verification_refiled_total'=>$row['verification_re_filing'],
                    'verification_refiled_reg'=>$row['verification_re_filing_reg'],
                    'filing_alloted'=>$row['fil_allot'],
                    'filing_completed'=>$row['fil_comp'],
                    'filing_pending'=>$row['fil_rem'],
                    'refiled_alloted'=>$row['refil_allot'],
                    'refiled_completed'=>$row['refil_comp'],

                    'refiled_pending'=>$row['refil_rem'],

                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];
                $builder = $this->db->table('public.filing_stats');
                $response = $builder->insert($filing_stats_data);
            }

        }

        if ($response){
            return  'Data Generation successfully.';
        } else {
            return 'Data Not Generation successfully.';
        }
        exit();



    }
}