<?php
namespace App\Models\Filing;

use CodeIgniter\Model;

class CronModel extends Model
{


    public function __construct()
    {
        parent::__construct();

        $db = \Config\Database::connect();
    }

    function filing_stats()
    {
        $cur_dttime = date('d-m-Y H:i');
        $curdate=date('Y-m-d');
        $cur_time=date('H:i');
        $ip=getClientIP();
//       $sql_filing="insert into filing_stats(filing_date, updation_time, total_filed, old_efiled,new_efiled,physical_filed,registered,refiled, checked_verified, verified, tagging_verification, verification_refiled_total, verification_refiled_reg, filing_alloted, filing_completed, filing_pending,
//              refiled_alloted, refiled_completed, refiled_pending,create_modify,updated_on,updated_by,updated_by_ip) select now(),'$cur_time',sum(filed) filed,sum(old_efiled) old_efiled,sum(sc_efm_filed) as sc_efm_filed,sum(phy_filed) phy_filed,sum(registered) registered,sum(refiled) refiled,sum(checked_verified) checked_verified,sum(verified) verified,sum(tag_verified) tag_verified,sum(verification_re_filing) verification_re_filing,sum(verification_re_filing_reg) verification_re_filing_reg,sum(fil_allot) fil_allot,sum(fil_comp) fil_comp,sum(fil_rem) fil_rem,sum(refil_allot) refil_allot,sum(refil_comp) refil_comp,sum(refil_rem) refil_rem,now(),now(),'1','$ip' from(
// select count(1) as filed,count(case when ack_id!=0 and ack_rec_dt is not null then 1 end) old_efiled,count(efiled_diary) as sc_efm_filed,
// count(case when ack_id=0 and ack_rec_dt = '' and efiled_diary is null then 1 end) phy_filed,0 as registered, 0 as refiled,0 as checked_verified,0 as verified,0 as tag_verified,0 as verification_re_filing,0 as verification_re_filing_reg,0 as fil_allot,0 as fil_comp,0 as fil_rem,0 as refil_allot,0 as refil_comp,0 as refil_rem from
// (select m.diary_no,ack_id,ack_rec_dt,fil_dt,ec.diary_no as efiled_diary from  main m left join efiled_cases ec on ec.diary_no=m.diary_no and efiled_type='new_case' where date(diary_no_rec_date)=now()) a
// union
// select 0 as filed,0 as old_efiled,0 as sc_efm_filed, 0 as phy_filed,count(1) as registered ,0 as refiled,0 as checked_verified,0 as verified,0 as tag_verified,0 as verification_re_filing,0 as verification_re_filing_reg,0 as fil_allot,0 as fil_comp,0 as fil_rem,0 as refil_allot,0 as refil_comp,0 as refil_rem  from (select distinct diary_no from main where date(fil_dt)=now() union select distinct diary_no from main_a where date(fil_dt)=now()) temp 
// union
// SELECT 0 as filed,0 as old_efiled,0 as sc_efm_filed, 0 as phy_filed,0 as registered ,count(distinct diary_no) refiled,0 as checked_verified,0 as verified,0 as tag_verified,0 as verification_re_filing,0 as verification_re_filing_reg,0 as fil_allot,0 as fil_comp,0 as fil_rem,0 as refil_allot,0 as refil_comp,0 as refil_rem  FROM obj_save WHERE DATE(rm_dt) = now() AND display='Y'
// union
// SELECT 0 as filed,0 as old_efiled,0 as sc_efm_filed, 0 as phy_filed,0 as registered ,0  refiled,sum(case when date(verification_date) =now() then 1 else 0 end) checked_verified,SUM(CASE when verification_status = '0' and date(verification_date) =now() THEN 1 ELSE 0 END)verified,sum(case when verification_status='1' and c_status='P' and lldt(d.diary_no::integer) IS NULL then 1 else 0 end ) tag_verified,0 as verification_re_filing,0 as verification_re_filing_reg,0 as fil_allot,0 as fil_comp,0 as fil_rem,0 as refil_allot,0 as refil_comp,0 as refil_rem FROM defects_verification d left join main m on  d.diary_no=m.diary_no
// union
// select 0 as filed,0 as old_efiled,0 as sc_efm_filed, 0 as phy_filed,0 as registered ,0  refiled, 0 as checked_verified,0 as verified,0 as tag_verified, count(*) as verification_re_filing,SUM(CASE WHEN fil_dt is not null THEN 1 ELSE 0 END) verification_re_filing_reg,0 as fil_allot,0 as fil_comp,0 as fil_rem,0 as refil_allot,0 as refil_comp,0 as refil_rem
//  from(select os.diary_no,m.fil_dt,NULLIF(min(COALESCE(rm_dt,'1949-12-31')),'1949-12-31') as min_rm_dt  from obj_save  os join main m on m.diary_no=os.diary_no  LEFT JOIN heardt b ON os.diary_no = b.diary_no where  ((b.diary_no IS NULL) OR (b.next_dt is null AND listorder =0 AND subhead =0 AND mainhead = 'M') OR
// (b.next_dt is null AND mainhead IS NULL )) and c_status='P' and os.display='Y' group by os.diary_no,m.fil_dt) temp  where min_rm_dt is not null 
// union
// select  0 as filed,0 as old_efiled,0 as sc_efm_filed, 0 as phy_filed,0 as registered ,0  refiled, 0 as checked_verified,0 as verified,0 as tag_verified,  0 as verification_re_filing,0 as verification_re_filing_reg,fil_allot,fil_comp,fil_allot-fil_comp fil_rem,refil_allot,refil_comp,refil_allot-refil_comp refil_rem from(select count(distinct case when remarks like 'DE -> SCR' then diary_no end) fil_allot,count(distinct case when remarks like 'DE -> SCR' and date(comp_dt) is not null then diary_no end) fil_comp,count(distinct case when remarks like 'FDR -> SCR' then diary_no end) refil_allot,count(distinct case when remarks like 'FDR -> SCR' and date(comp_dt) is not null then diary_no end) refil_comp from(
// select diary_no,remarks,comp_dt from fil_trap where (remarks like 'DE -> SCR' or remarks like 'FDR -> SCR') and date(disp_dt)=now()
// union
// select diary_no,remarks,comp_dt from fil_trap_his where (remarks like 'DE -> SCR' or remarks like 'FDR -> SCR') and date(disp_dt)=now())a) b)a";
            $sql_filing ="INSERT INTO filing_stats (
                filing_date,
                updation_time,
                total_filed,
                old_efiled,
                new_efiled,
                physical_filed,
                registered,
                refiled,
                checked_verified,
                verified,
                tagging_verification,
                verification_refiled_total,
                verification_refiled_reg,
                filing_alloted,
                filing_completed,
                filing_pending,
                refiled_alloted,
                refiled_completed,
                refiled_pending,
                create_modify,
                updated_on,
                updated_by,
                updated_by_ip
            )
            SELECT
                NOW()::DATE,
                '$cur_time'::TIME,
                SUM(filed) AS filed,
                SUM(old_efiled) AS old_efiled,
                SUM(sc_efm_filed) AS sc_efm_filed,
                SUM(phy_filed) AS phy_filed,
                SUM(registered) AS registered,
                SUM(refiled) AS refiled,
                SUM(checked_verified) AS checked_verified,
                SUM(verified) AS verified,
                SUM(tag_verified) AS tag_verified,
                SUM(verification_re_filing) AS verification_re_filing,
                SUM(verification_re_filing_reg) AS verification_re_filing_reg,
                SUM(fil_allot) AS fil_allot,
                SUM(fil_comp) AS fil_comp,
                SUM(fil_rem) AS fil_rem,
                SUM(refil_allot) AS refil_allot,
                SUM(refil_comp) AS refil_comp,
                SUM(refil_rem) AS refil_rem,
                NOW(),
                NOW(),
                '1',
                '$ip'
            FROM
                (
                    SELECT
                        COUNT(1) AS filed,
                        COUNT(CASE WHEN ack_id != 0 AND ack_rec_dt IS NOT NULL THEN 1 END) AS old_efiled,
                        COUNT(efiled_diary) AS sc_efm_filed,
                        COUNT(CASE WHEN ack_id = 0 AND ack_rec_dt = '' AND efiled_diary IS NULL THEN 1 END) AS phy_filed,
                        0 AS registered,
                        0 AS refiled,
                        0 AS checked_verified,
                        0 AS verified,
                        0 AS tag_verified,
                        0 AS verification_re_filing,
                        0 AS verification_re_filing_reg,
                        0 AS fil_allot,
                        0 AS fil_comp,
                        0 AS fil_rem,
                        0 AS refil_allot,
                        0 AS refil_comp,
                        0 AS refil_rem
                    FROM
                        (
                            SELECT
                                m.diary_no,
                                ack_id,
                                ack_rec_dt,
                                fil_dt,
                                ec.diary_no AS efiled_diary
                            FROM
                                main m
                            LEFT JOIN
                                efiled_cases ec ON ec.diary_no = m.diary_no AND efiled_type = 'new_case'
                            WHERE
                                DATE(diary_no_rec_date) = NOW()::DATE
                        ) a
                    UNION ALL
                    SELECT
                        0 AS filed,
                        0 AS old_efiled,
                        0 AS sc_efm_filed,
                        0 AS phy_filed,
                        COUNT(1) AS registered,
                        0 AS refiled,
                        0 AS checked_verified,
                        0 AS verified,
                        0 AS tag_verified,
                        0 AS verification_re_filing,
                        0 AS verification_re_filing_reg,
                        0 AS fil_allot,
                        0 AS fil_comp,
                        0 AS fil_rem,
                        0 AS refil_allot,
                        0 AS refil_comp,
                        0 AS refil_rem
                    FROM
                        (
                            SELECT DISTINCT
                                diary_no
                            FROM
                                main
                            WHERE
                                DATE(fil_dt) = NOW()::DATE
                            UNION ALL
                            SELECT DISTINCT
                                diary_no
                            FROM
                                main_a
                            WHERE
                                DATE(fil_dt) = NOW()::DATE
                        ) temp
                    UNION ALL
                    SELECT
                        0 AS filed,
                        0 AS old_efiled,
                        0 AS sc_efm_filed,
                        0 AS phy_filed,
                        0 AS registered,
                        COUNT(DISTINCT diary_no) AS refiled,
                        0 AS checked_verified,
                        0 AS verified,
                        0 AS tag_verified,
                        0 AS verification_re_filing,
                        0 AS verification_re_filing_reg,
                        0 AS fil_allot,
                        0 AS fil_comp,
                        0 AS fil_rem,
                        0 AS refil_allot,
                        0 AS refil_comp,
                        0 AS refil_rem
                    FROM
                        obj_save
                    WHERE
                        DATE(rm_dt) = NOW()::DATE AND display = 'Y'
                    UNION ALL
                    SELECT
                        0 AS filed,
                        0 AS old_efiled,
                        0 AS sc_efm_filed,
                        0 AS phy_filed,
                        0 AS registered,
                        0 AS refiled,
                        SUM(CASE WHEN DATE(verification_date) = NOW()::DATE THEN 1 ELSE 0 END) AS checked_verified,
                        SUM(CASE WHEN verification_status = '0' AND DATE(verification_date) = NOW()::DATE THEN 1 ELSE 0 END) AS verified,
                        SUM(CASE WHEN verification_status = '1' AND c_status = 'P' AND lldt(d.diary_no::INTEGER) IS NULL THEN 1 ELSE 0 END) AS tag_verified,
                        0 AS verification_re_filing,
                        0 AS verification_re_filing_reg,
                        0 AS fil_allot,
                        0 AS fil_comp,
                        0 AS fil_rem,
                        0 AS refil_allot,
                        0 AS refil_comp,
                        0 AS refil_rem
                    FROM
                        defects_verification d
                    LEFT JOIN
                        main m ON d.diary_no = m.diary_no
                    UNION ALL
                    SELECT
                        0 AS filed,
                        0 AS old_efiled,
                        0 AS sc_efm_filed,
                        0 AS phy_filed,
                        0 AS registered,
                        0 AS refiled,
                        0 AS checked_verified,
                        0 AS verified,
                        0 AS tag_verified,
                        COUNT(*) AS verification_re_filing,
                        SUM(CASE WHEN fil_dt IS NOT NULL THEN 1 ELSE 0 END) AS verification_re_filing_reg,
                        0 AS fil_allot,
                        0 AS fil_comp,
                        0 AS fil_rem,
                        0 AS refil_allot,
                        0 AS refil_comp,
                        0 AS refil_rem
                    FROM
                        (
                            SELECT
                                os.diary_no,
                                m.fil_dt,
                                NULLIF(MIN(COALESCE(rm_dt, '1949-12-31'::DATE)), '1949-12-31'::DATE) AS min_rm_dt
                            FROM
                                obj_save os
                            JOIN
                                main m ON m.diary_no = os.diary_no
                            LEFT JOIN
                                heardt b ON os.diary_no = b.diary_no
                            WHERE
                                (
                                    (b.diary_no IS NULL)
                                    OR (b.next_dt IS NULL AND listorder = 0 AND subhead = 0 AND mainhead = 'M')
                                    OR (b.next_dt IS NULL AND mainhead IS NULL)
                                )
                                AND c_status = 'P'
                                AND os.display = 'Y'
                            GROUP BY
                                os.diary_no,
                                m.fil_dt
                        ) temp
                    WHERE
                        min_rm_dt IS NOT NULL
                    UNION ALL
                    SELECT
                        0 AS filed,
                        0 AS old_efiled,
                        0 AS sc_efm_filed,
                        0 AS phy_filed,
                        0 AS registered,
                        0 AS refiled,
                        0 AS checked_verified,
                        0 AS verified,
                        0 AS tag_verified,
                        0 AS verification_re_filing,
                        0 AS verification_re_filing_reg,
                        fil_allot,
                        fil_comp,
                        fil_allot - fil_comp AS fil_rem,
                        refil_allot,
                        refil_comp,
                        refil_allot - refil_comp AS refil_rem
                    FROM
                        (
                            SELECT
                                COUNT(DISTINCT CASE WHEN remarks LIKE 'DE -> SCR' THEN diary_no END) AS fil_allot,
                                COUNT(DISTINCT CASE WHEN remarks LIKE 'DE -> SCR' AND DATE(comp_dt) IS NOT NULL THEN diary_no END) AS fil_comp,
                                COUNT(DISTINCT CASE WHEN remarks LIKE 'FDR -> SCR' THEN diary_no END) AS refil_allot,
                                COUNT(DISTINCT CASE WHEN remarks LIKE 'FDR -> SCR' AND DATE(comp_dt) IS NOT NULL THEN diary_no END) AS refil_comp
                            FROM
                                (
                                    SELECT
                                        diary_no,
                                        remarks,
                                        comp_dt
                                    FROM
                                        fil_trap
                                    WHERE
                                        (remarks LIKE 'DE -> SCR' OR remarks LIKE 'FDR -> SCR') AND DATE(disp_dt) = NOW()::DATE
                                    UNION ALL
                                    SELECT
                                        diary_no,
                                        remarks,
                                        comp_dt
                                    FROM
                                        fil_trap_his
                                    WHERE
                                        (remarks LIKE 'DE -> SCR' OR remarks LIKE 'FDR -> SCR') AND DATE(disp_dt) = NOW()::DATE
                                ) a
                        ) b
                ) a";
        $query = $this->db->query($sql_filing);
        if($query)
        {
            return true;
        }else{
            return false;
        }
    }
}
