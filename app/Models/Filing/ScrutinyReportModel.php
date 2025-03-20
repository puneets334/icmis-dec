<?php

namespace App\Models\Filing;

use CodeIgniter\Model;

class ScrutinyReportModel extends Model
{
    public $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    public function navigate_diary_data($dno)
    {
        $sql = "SELECT m.diary_no, c1.short_description, m.active_reg_year, m.active_fil_no,
                m.pet_name, m.res_name, pno, rno, m.diary_no_rec_date, m.active_fil_dt, m.lastorder, m.c_status FROM main m 
                left JOIN master.casetype c1 ON m.active_casetype_id = c1.casecode WHERE m.diary_no = '$dno'";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

    public function diary_data($dairy_no)
    {
        $p_r = "Select pet_name,res_name,casetype_id,fil_no,casetype_id,c_status from main where diary_no = '$dairy_no'";

        $query = $this->db->query($p_r);
        $result = $query->getRowArray();
        return $result;
    }

    public function order_dt_data($dairy_no)
    {
        $sql = "SELECT h.diary_no,  TO_CHAR(h.next_dt, 'DD-MM-YYYY') AS order_dt, STRING_AGG(crm.r_head::text, ', ') AS Disp_Remarks
                FROM 
                heardt h 
                INNER JOIN case_remarks_multiple crm 
                    ON crm.diary_no::text = h.diary_no::text 
                    AND cl_date = h.next_dt 
                INNER JOIN master.case_remarks_head crh 
                    ON crh.sno = crm.r_head 
                    AND (
                    crh.display = 'Y' 
                    OR crh.display IS NULL
                    ) 
                WHERE 
                h.diary_no ='$dairy_no' 
                AND h.clno != 0 
                AND h.brd_slno != 0 
                AND h.brd_slno IS NOT NULL 
                AND h.roster_id != 0 
                AND h.roster_id IS NOT NULL 
                AND main_supp_flag IN (1, 2) 
                AND h.next_dt <= CURRENT_DATE 
                AND h.next_dt = (
                    SELECT 
                    MAX(next_dt) 
                    FROM 
                    heardt b 
                    WHERE 
                    b.diary_no = h.diary_no 
                    AND b.clno != 0 
                    AND b.brd_slno != 0 
                    AND b.brd_slno IS NOT NULL 
                    AND b.roster_id != 0 
                    AND b.roster_id IS NOT NULL 
                    AND main_supp_flag IN (1, 2)
                ) 
                AND crm.r_head IN (
                    181, 182, 3, 183, 184, 1, 41, 176, 177, 
                    178, 27, 196, 200, 201
                ) 
                GROUP BY 
                h.diary_no, h.next_dt";
        $query = $this->db->query($sql);
        $order_dt = $query->getRowArray();
        if (!empty($order_dt)) {
            $order_date = $order_dt[1];
            return $order_date;
        } else {
            $sql_order_dt = "select h.diary_no, TO_CHAR(h.next_dt, 'DD-MM-YYYY') AS order_dt, STRING_AGG(crm.r_head::text, ', ') As Disp_Remarks 
                        from last_heardt h inner join case_remarks_multiple crm ON crm.diary_no::text = h.diary_no::text and cl_date = h.next_dt 
                        inner join master.case_remarks_head crh ON crh.sno = crm.r_head 
                        AND (
                            crh.display = 'Y' 
                            or crh.display is null
                        ) where h.diary_no = '$dairy_no' and h.clno != 0 and h.brd_slno != 0 and h.brd_slno is not null and h.roster_id != 0 and h.roster_id is not null 
                        and (
                            h.bench_flag is null 
                            or h.bench_flag = ''
                        ) 
                        and main_supp_flag in (1, 2) 
                        and h.next_dt <= CURRENT_DATE
                        and h.next_dt =(
                            select 
                            max(next_dt) 
                            from 
                            last_heardt b 
                            where 
                            b.diary_no = h.diary_no 
                            and b.clno != 0 
                            and b.brd_slno != 0 
                            and b.brd_slno is not null 
                            and b.roster_id != 0 
                            and b.roster_id is not null 
                            and (
                                b.bench_flag is null 
                                or b.bench_flag = ''
                            ) 
                            and main_supp_flag in (1, 2)
                        ) 
                        and crm.r_head in (
                            181, 182, 3, 183, 184, 1, 41, 176, 177, 
                            178, 27, 196, 200, 201
                        ) 
                        group by 
                        h.next_dt,h.diary_no";

            $query = $this->db->query($sql_order_dt);
            $order_dt = $query->getRowArray();
            //echo mysql_num_rows($dispose_dt);
            if (!empty($order_dt)) {
                $order_date = $order_dt[1];
                return $order_date;
            }
        }
    }

    public function diary_category($dairy_no)
    {
        $category="select * from mul_category where diary_no='$dairy_no' and display='Y'";
        $query = $this->db->query($category);
        $result = $query->getRowArray();
        return $result;
    }

    public function casetype_added($casetype_id){
        $casetype_added = "Select short_description from master.casetype where casecode='$casetype_id' and display='Y'";
        $query = $this->db->query($casetype_added);
        $result = $query->getRowArray()['short_description'];
        return $result;
    }
    
}
