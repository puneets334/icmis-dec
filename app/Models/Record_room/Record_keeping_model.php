<?php

namespace App\Models\Record_room;

use CodeIgniter\Model;

class Record_keeping_model extends Model
{

    public function case_types()
{
    $builder = $this->db->table('master.casetype');
    $builder->where('is_deleted', 'f')
            ->where('casecode !=', 9999)
            ->orderBy('casecode', 'ASC');
    
    $query = $builder->get();
    
    return $query->getResultArray();
}


    function get_case_details($case_type = 0, $case_number = 0, $case_year = 0, $diary_number = 0, $diary_year = 0)
    {
        if ($case_type != 0 && $case_number != 0 && $case_year !== 0)
            $condition = " cast(substring(active_fil_no,1,2) as unsigned)=$case_type and
                         active_reg_year=$case_year and (cast(substring(active_fil_no,4,6)
                          as unsigned)=$case_number or $case_number between
                          cast(substring(active_fil_no,4,6) as unsigned) and
                          cast(substring(active_fil_no,11,6) as unsigned))";

        else if ($diary_number != 0 && $diary_year != 0)
            $condition = " m.diary_no=" . $diary_number . $diary_year;

        $sql = "select m.diary_no as case_diary,c_status,concat(pet_name,' Vs ',res_name) as case_title,
                 us.section_name AS user_section,m.reg_no_display,
                 s.consignment_remarks,s.consignment_status, date_format(s.consignment_date, '%d-%m-%Y') as consignment_date,s.display,
                 date_format(d1.ord_dt, '%d-%m-%Y') as order_date,
                d2.dispname,(select group_concat(jname separator ', ') from judge where find_in_set(jcode,d1.jud_id)) as coram,
                u1.name as consignedBy
                from main m left join record_keeping s on m.diary_no=s.diary_no and s.display='Y'
                left join dispose d1 on m.diary_no=d1.diary_no
                left join  disposal d2 on d1.disp_type=d2.dispcode and d2.display='Y'
                left JOIN users u ON u.usercode = m.dacode AND (u.display = 'Y' || u.display is null)
                left JOIN users u1 ON u1.usercode = s.updated_by AND (u1.display = 'Y' || u1.display is null)
				left join usersection us on us.id=u.section and (us.display='Y' || us.display is null)
                where " . $condition . "";
        // echo $sql;

        $query = $this->db->query($sql);
        // echo $this->db->last_query();
        if ($query->num_rows() >= 1)
            //echo $sql;
            return $query->result_array();
        else
            return false;
    }
    function check_case($case_diary)
    {
        $sql1 = "Select count(diary_no) as count_no from record_keeping where diary_no='$case_diary' and display='Y'";

        $query1 = $this->db->query($sql1);

        if ($query1->num_rows() >= 1) {
            return $query1->result_array();
        } else
            return false;
    }

    function check_case_file_trap($case_diary)
    {
        $sql1 = "Select count(diary_no) as count_no from fil_trap where diary_no='$case_diary'";

        $query1 = $this->db->query($sql1);

        if ($query1->num_rows() >= 1) {
            return $query1->result_array();
        } else
            return false;
    }


    function get_consignment_report($fromDate = null, $toDate = null)
    {

        $sql = "select concat(SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4),' / ',SUBSTR(m.diary_no, - 4)) as diary_no,
                concat(pet_name,'<strong> Vs. </strong>',res_name) as case_title,
                us.section_name AS user_section,m.reg_no_display,
                s.consignment_remarks,date_format(s.consignment_date, '%d-%m-%Y') as consignment_date,
                date_format(d1.ord_dt, '%d-%m-%Y') as order_date,
                d2.dispname,(select group_concat(jname separator ', ')
                from judge where find_in_set(jcode,d1.jud_id)) as coram,
                u1.name as consignedBy
                from main m left join record_keeping s on m.diary_no=s.diary_no and s.display='Y'
                left join dispose d1 on m.diary_no=d1.diary_no
                left join  disposal d2 on d1.disp_type=d2.dispcode and d2.display='Y'
                left JOIN users u ON u.usercode = m.dacode AND (u.display = 'Y' || u.display is null)
                left JOIN users u1 ON u1.usercode = s.updated_by AND (u1.display = 'Y' || u1.display is null)
                left join usersection us on us.id=u.section and (us.display='Y' || us.display is null)
                where consignment_date between '$fromDate' and '$toDate'
                order by date(s.consignment_date)
                ";

        //echo $sql;

        $query = $this->db->query($sql);
        if ($query->num_rows() >= 1) {

            return $query->result_array();
            // var_dump($result);
        } else {
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">No Case Consigned between selected Date</div>');
            redirect("Record_keeping/ConsignmentReport");
        }
    }
    function get_ripe_cases_report($from_date = null, $to_date = null, $noOfDays = null, $offset = null)
    {

        $sql = "SELECT f.*, 
    CASE 
      WHEN f.no_of_days_since_disposal_date >= 365 THEN ' (Ripe Case)' 
      ELSE NULL 
    END AS case_remark
FROM (
  SELECT 
    m.reg_no_display || ' @ ' || 
    SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4) || '/' || 
    SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3 FOR 4) AS case_no,
     
    m.pet_name || ' Vs. ' || m.res_name AS cause_title,
     
    CASE 
      WHEN d.ord_dt != d.disp_dt THEN 
        'Order Date : ' || TO_CHAR(d.ord_dt, 'DD-MM-YYYY') || E'' || 
        'Dispose Date : ' || TO_CHAR(d.disp_dt, 'DD-MM-YYYY') 
      ELSE 
        TO_CHAR(d.disp_dt, 'DD-MM-YYYY') 
    END AS order_date,
     
    m.active_fil_dt, 
    m.active_reg_year, 
    m.active_fil_no,
     
    CASE 
      WHEN (m.fil_no IS NULL OR m.fil_no = '') AND 
         (m.active_fil_no IS NULL OR m.active_fil_no = '') 
      THEN 
        m.casetype_id 
      ELSE 
        m.active_casetype_id 
    END AS casetype_id,
     
    CASE 
      WHEN (m.fil_no IS NULL OR m.fil_no = '') AND 
         (m.active_fil_no IS NULL OR m.active_fil_no = '' OR m.active_reg_year = 0) 
      THEN 
        EXTRACT(YEAR FROM m.diary_no_rec_date) 
      ELSE 
        m.active_reg_year 
    END AS case_year,
     
    COALESCE((
      SELECT STRING_AGG(jname, ' , ') 
      FROM master.judge 
      WHERE jcode::text = ANY(string_to_array(d.jud_id, ','))), 
      '') AS coram,
     
    cd.consignment_date,
     
    CURRENT_DATE - d.disp_dt AS no_of_days_since_disposal_date,
    CURRENT_DATE - cd.consignment_date AS no_of_days_since_consignment_done
  FROM (
    SELECT r.diary_no, r.consignment_date
    FROM record_keeping r
    WHERE r.consignment_status = 'Y' AND r.display = 'Y'

    UNION ALL 

    SELECT f.diary_no, f.rece_dt AS consignment_date
    FROM fil_trap f
    WHERE f.remarks = 'RR-DA -> SEG-DA' 
  ) cd
  INNER JOIN main m ON cd.diary_no = m.diary_no
  INNER JOIN dispose d ON cd.diary_no = d.diary_no
  LEFT JOIN elimination e ON (cd.diary_no = e.fil_no AND e.display = 'Y')
  WHERE m.c_status = 'D'       
   AND d.disp_dt BETWEEN '$from_date' and '$to_date'
   AND e.ele_dt IS NULL
  ORDER BY d.ord_dt ASC
) f ";


//pr($sql);
        /*$query = $this->db->query($sql,array($from_date,$to_date));*/
        $query = $this->db->query($sql);

        return $query->getResultArray();

        //echo $this->db->last_query();exit();
        //return $query->getNumRows() > 0 ? $query->getResultArray() : [];
    }


    function get_ripe_cases_report_hallwise($from_date=null,$to_date=null,$hall_no=null)
    {
        $sql="SELECT DISTINCT x.*, hd.hall_no 
FROM (
    SELECT f.*, 
        CASE 
            WHEN f.no_of_days_since_disposal_date >= 365 THEN ' (Ripe Case)' 
            ELSE NULL 
        END AS case_remark
    FROM (
        SELECT 
            m.reg_no_display || ' @ ' || 
            SUBSTRING(m.diary_no::TEXT FROM 1 FOR LENGTH(m.diary_no::TEXT) - 4) || '/' || 
            SUBSTRING(m.diary_no::TEXT FROM LENGTH(m.diary_no::TEXT) - 3 FOR 4) AS case_no,
            
            m.pet_name || ' Vs. ' || m.res_name AS cause_title,
            CASE 
                WHEN d.ord_dt != d.disp_dt THEN 
                    'Order Date : ' || TO_CHAR(d.ord_dt, 'DD-MM-YYYY') || E' ' || 
                    'Dispose Date : ' || TO_CHAR(d.disp_dt, 'DD-MM-YYYY') 
                ELSE 
                    TO_CHAR(d.disp_dt, 'DD-MM-YYYY') 
            END AS order_date,
            
            m.diary_no,
            m.active_fil_dt,
            m.active_reg_year,
            m.active_fil_no,
            
            CASE 
                WHEN (m.fil_no IS NULL OR m.fil_no = '') AND 
                     (m.active_fil_no IS NULL OR m.active_fil_no = '') 
                THEN 
                    m.casetype_id 
                ELSE 
                    m.active_casetype_id 
            END AS casetype_id,
            
            CASE 
                WHEN (m.fil_no IS NULL OR m.fil_no = '') AND 
                     (m.active_fil_no IS NULL OR m.active_fil_no = '' OR m.active_reg_year = 0) 
                THEN 
                    EXTRACT(YEAR FROM m.diary_no_rec_date) 
                ELSE 
                    m.active_reg_year 
            END AS case_year,
            
            COALESCE((
                SELECT STRING_AGG(jname, ' , ') 
                FROM MASTER.judge 
                WHERE jcode::TEXT = ANY(string_to_array(d.jud_id, ','))
            ), '') AS coram,
            
            cd.consignment_date,
            CURRENT_DATE - d.disp_dt AS no_of_days_since_disposal_date,
            CURRENT_DATE - cd.consignment_date AS no_of_days_since_consignment_done
        FROM (
            SELECT r.diary_no, r.consignment_date
            FROM record_keeping r
            WHERE r.consignment_status = 'Y' AND r.display = 'Y'

            UNION ALL

            SELECT f.diary_no, f.rece_dt AS consignment_date
            FROM fil_trap f
            WHERE f.remarks = 'RR-DA -> SEG-DA'
        ) cd
        INNER JOIN main m ON cd.diary_no = m.diary_no
        INNER JOIN dispose d ON cd.diary_no = d.diary_no
        LEFT JOIN elimination e ON (cd.diary_no = e.fil_no AND e.display = 'Y')
        WHERE m.c_status = 'D'       
          AND d.disp_dt BETWEEN '$from_date' and '$to_date'
          AND e.ele_dt IS NULL
        ORDER BY d.ord_dt ASC
    ) f
) x
JOIN MASTER.rr_hall_case_distribution hd ON (
    (CASE 
        WHEN (x.active_fil_no = '' OR x.active_fil_no IS NULL) 
        THEN SUBSTRING(x.diary_no::TEXT FROM LENGTH(x.diary_no::TEXT) - 3 FOR 4)::TEXT 
        ELSE x.case_year::TEXT  
    END) BETWEEN hd.caseyear_from::TEXT AND hd.caseyear_to::TEXT
    AND hd.display = 'Y'
    AND (
        (CASE 
            WHEN (x.active_fil_no = '' OR x.active_fil_no IS NULL) 
            THEN CAST(NULLIF(SUBSTRING(x.diary_no::TEXT FROM 1 FOR LENGTH(x.diary_no::TEXT) - 4), '') AS INTEGER) 
            ELSE CAST(SUBSTRING(x.diary_no::TEXT FROM 4 FOR 6) AS INTEGER) 
         END) BETWEEN hd.case_from AND hd.case_to
        OR
        (CASE 
            WHEN (x.active_fil_no = '' OR x.active_fil_no IS NULL) 
            THEN CAST(NULLIF(SUBSTRING(x.diary_no::TEXT FROM 1 FOR LENGTH(x.diary_no::TEXT) - 4), '') AS INTEGER) 
            ELSE CAST(NULLIF(SUBSTRING(x.active_fil_no FROM 11 FOR 6), '') AS INTEGER) 
         END) BETWEEN hd.case_from AND hd.case_to
    )
    AND x.casetype_id = hd.casetype
    AND hd.hall_no IN ($hall_no)
)";
            //   pr($sql);
        $query = $this->db->query($sql);
        return $query->getNumRows() > 0 ? $query->getResultArray() : [];

    }

}
