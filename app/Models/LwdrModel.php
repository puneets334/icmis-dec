<?php

namespace App\Models;

use CodeIgniter\Model;

class LwdrModel extends Model
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $db = \Config\Database::connect();
        $this->db = db_connect();
    }

    public function sectionwise_name()
    {
        $builder = $this->db->table('master.usersection');
        $builder->distinct();
        $builder->select('id,section_name');
        $builder->where('isda', 'Y');
        $builder->where('display', 'Y');
        $query = $builder->get();
        return $query->getResultArray();
    }


    public function get_sectionwise_matters($section_name)
    {
        $builder = $this->db->table('main m');
        $builder->select("CONCAT(SUBSTR(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4), '/', SUBSTR(m.diary_no::text, -4)) as diary_no, 
                  CONCAT(m.pet_name, ' Vs. ', m.res_name) as TITLE, 
                  m.dacode, 
                  SUBSTR(m.diary_no::text, -4) as dyear, 
                  TO_CHAR(m.diary_no_rec_date::date, 'DD-MM-YYYY') AS diary_date,
                  u.usercode, 
                  us.section_name, 
                  CONCAT(u.empid, ' # ', u.name) as daname");
        $builder->join('defects_verification bb', 'm.diary_no = bb.diary_no', 'left');
        $builder->join('master.users u', 'm.dacode = u.usercode', 'left');
        $builder->join('master.usersection us', 'u.section = us.id', 'left');
        $builder->where('DATE(m.diary_no_rec_date) >=', '2017-05-08');
        $builder->where('c_status', 'P');
        $builder->groupStart()
            ->where('bb.diary_no IS NULL')
            ->orWhere('verification_status', '1')
            ->groupEnd();
        $builder->where('us.section_name', $section_name);
        $builder->orderBy('daname, dyear, m.diary_no');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function diarized_summary()
    {
        $builder = $this->db->table('main m');
        $builder->select("
            CONCAT(SUBSTR(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4), '/', SUBSTR(m.diary_no::text, -4)) AS diary_no,
            TO_CHAR(DATE(m.diary_no_rec_date), 'DD-MM-YYYY') as diary_date,
            SUBSTR(m.diary_no::text, -4) as dyear,
            CASE WHEN m.dacode = 0 THEN tentative_da(m.diary_no::integer) ELSE concat(u.name, '[', u.empid, ']') END AS daname,
            CASE WHEN m.section_id IS NULL THEN tentative_section(m.diary_no::text) ELSE us.section_name END AS section,
            m.pet_name,
            m.res_name");
        $builder->join("(SELECT diary_no FROM heardt WHERE main_supp_flag IN (1, 2) AND clno != 0 AND brd_slno != 0 AND judges != '' AND judges != '0'
                 UNION 
                 SELECT diary_no FROM last_heardt WHERE main_supp_flag IN (1, 2) AND clno != 0 AND brd_slno != 0 AND judges != '' AND judges != '0') a", 'm.diary_no = a.diary_no', 'left', false);
        $builder->join('master.users u', 'm.dacode = u.usercode', 'left');
        $builder->join('master.usersection us', 'm.section_id = us.id', 'left');
        $builder->where('DATE(m.diary_no_rec_date) >=', '2017-05-08');
        $builder->where('m.active_fil_no IS NULL');
        $builder->where('a.diary_no IS NULL');
        $builder->where('m.c_status', 'P');
        $builder->groupStart()
            ->whereIn('active_casetype_id', [9, 10, 25, 26, 19, 20, 39])
            ->orWhereIn('casetype_id', [9, 10, 25, 26, 19, 20, 39])
            ->groupEnd();
        $builder->orderBy('section,daname,  m.diary_no_rec_date');
        $query = $builder->get();
        return $query->getResultArray();
    }


    public function rev_curprocess()
    {
        $subQuery = $this->db->table('mul_category')->distinct()->select('diary_no')->get()->getResultArray();
        $subQuery = array_column($subQuery, 'diary_no');

        $builder = $this->db->table('main m');
        $builder->select("CONCAT(SUBSTR(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4), '/', SUBSTR(m.diary_no::text, -4)) as diary_no,
            SUBSTR(m.diary_no::text, -4) as dyear,
            m.reg_no_display as case_no,CONCAT(m.pet_name, ' Vs. ', m.res_name) as cause_title,m.dacode,
            us.section_name,CONCAT(u.empid, '#', u.name) as daname,m.active_casetype_id,m.c_status");
        $builder->join('master.users u', 'm.dacode = u.usercode', 'left');
        $builder->join('master.usersection us', 'u.section = us.id', 'left');
        $builder->where('m.c_status', 'P');
        $builder->whereIn('m.active_casetype_id', [9, 10, 19, 20, 25, 26]);
        $builder->whereNotIn("m.diary_no", $subQuery);
        $builder->orderBy('us.section_name, u.empid, dyear, m.diary_no');
        $query = $builder->get();
        return $query->getResultArray();
    }

    function get_Sub_SubjectCategory($Mcat)
    {
        $builder = $this->db->table('master.submaster');
        $builder->select("id, subcode1, category_sc_old, sub_name1, sub_name4, CASE 
            WHEN (category_sc_old IS NOT NULL AND category_sc_old != '' AND category_sc_old != '0') 
            THEN CONCAT('', category_sc_old, '#-#', sub_name4) 
            ELSE CONCAT('', CONCAT(subcode1, '', subcode2), '#-#', sub_name4) END AS dsc");
        $builder->where('subcode1', $Mcat);
        $builder->where('subcode2 !=', '0');
        $builder->groupBy('id, subcode1, category_sc_old, sub_name1, sub_name4');
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function getMainSubjectCategory()
    {
        $builder = $this->db->table('master.submaster');
        $builder->select('subcode1, sub_name1');
        $builder->whereIn('flag_use', ['S', 'L']);
        $builder->where('display', 'Y')->where('match_id !=', 0)->where('flag', 'S');
        $builder->groupBy('subcode1, sub_name1');
        $builder->orderBy('subcode1');
        $query = $builder->get();
        return $query->getResultArray();
    }


    function getSection_Pending_Reports($category, $section, $reportType, $listCourtType, $dateType, $fromDate, $toDate, $mcat)
    {

        $sql = "";

        if ($reportType == 0 && ($category != "" || isset($category)) && ($section != "" || isset($section))) {
            $condition = "sm.id ='$category'";
            if ($category == 0 && $mcat != 100) {
                $condition = " subcode1=$mcat";
            } else if ($category == 0 && $mcat == 100) {
                $condition = "";
            } else {
                $condition = " sm.id ='$category'";
            }


            $builder = $this->db->table('main m');
            $builder->select("m.active_fil_dt,m.diary_no_rec_date,us.section_name AS user_section,
                CASE WHEN mf_active = 'F' THEN 'Regular' ELSE 'Misc.' END AS casestage,aa.total_connected AS group_count,
                CASE WHEN (m.diary_no::text = m.conn_key OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL)
                    THEN 'M' ELSE 'C' END AS main_or_connected,sm.sub_name1,CASE 
                    WHEN (category_sc_old IS NOT NULL AND category_sc_old != '' AND category_sc_old != '0')
                    THEN CONCAT(category_sc_old, ' - ', sub_name4) ELSE CONCAT(subcode1, subcode2, ' - ', sub_name4)
                END AS subject_category,sm.category_sc_old,u.name AS alloted_to_da,
                SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4) AS diary_no,
                SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3 FOR 4) AS diary_year,
                TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS diary_date,m.pet_name,m.res_name,m.reg_no_display,m.c_status,
                CONCAT(m.reg_no_display, '@ D.No.', SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4), '/', SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3 FOR 4)) AS CaseNo,
                CASE WHEN h.main_supp_flag = 0 THEN 'Ready' ELSE 'Not Ready' END AS Ready_status,h.next_dt AS Hearing_Date");

            $builder->join("heardt h", "m.diary_no = h.diary_no", "left");
            $builder->join("master.users u", "u.usercode = m.dacode AND (u.display = 'Y' OR u.display IS NULL)", 'left');
            $builder->join("master.usersection us", "us.id = u.section AND us.display = 'Y'", "left");
            $builder->join("master.users u1", "u1.usercode = m.usercode AND u1.display = 'Y'", "left");
            $builder->join("(SELECT n.conn_key, COUNT(*) AS total_connected 
                             FROM main m
                             INNER JOIN heardt h ON m.diary_no = h.diary_no
                             INNER JOIN main n ON CAST(m.diary_no AS TEXT) = n.conn_key
                             WHERE n.diary_no::TEXT != n.conn_key AND m.c_status = 'P'
                             GROUP BY n.conn_key) aa", 'm.diary_no::text = aa.conn_key', 'left', false);
            $builder->join("mul_category mc", "mc.diary_no = m.diary_no AND mc.display = 'Y'", 'inner');
            $builder->join("master.submaster sm", "mc.submaster_id = sm.id AND (sm.display = 'Y' OR sm.display IS NULL)", 'inner');
            $builder->where('m.c_status', 'P');
            $builder->where('us.id', $section);
            if (!empty($condition))
                $builder->where($condition);
            $builder->orderBy('m.diary_no_rec_date', 'asc');
            $sql = $builder->get();
        }
        // SUBSTR(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4) AS diary_no,m.c_status, h.board_type
        //  SUBSTR(m.diary_no::text, -4) AS diary_year,TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS diary_date, m.reg_no_display,

        else if ($reportType == 1 && ($section != "" || isset($section)) && ($listCourtType != "" || isset($listCourtType))) {
            $builder = $this->db->table('main m');
            $builder->select("m.active_fil_dt, m.diary_no_rec_date,sm.sub_name1,us.section_name AS user_section,
                CASE WHEN mf_active = 'F' THEN 'Regular' ELSE 'Misc.' END AS casestage,
                aa.total_connected AS group_count,
                CASE WHEN (m.diary_no::text = m.conn_key OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL)
                    THEN 'M' ELSE 'C' END AS main_or_connected,sm.category_sc_old, u.name AS alloted_to_da,
                m.pet_name, m.res_name,CASE 
                    WHEN (category_sc_old IS NOT NULL AND category_sc_old != '' AND category_sc_old != '0')
                    THEN CONCAT(category_sc_old, ' - ', sub_name4) ELSE CONCAT(subcode1, subcode2, ' - ', sub_name4)
                END AS subject_category,
                CONCAT(m.reg_no_display, '@ D.No.', SUBSTR(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4), '/', SUBSTR(m.diary_no::text, -4)) AS CaseNo,
                CASE WHEN h.main_supp_flag = 0 THEN 'Ready' ELSE 'Not Ready' END AS Ready_status,d.disp_dt,
                h.next_dt AS Hearing_Date");
            $builder->join("heardt h", "m.diary_no = h.diary_no", 'left');
            $builder->join("master.users u", "u.usercode = m.dacode AND (u.display = 'Y' OR u.display IS NULL)", 'left');
            $builder->join("master.usersection us", "us.id = u.section AND us.display = 'Y'", 'left');
            $builder->join("master.users u1", "u1.usercode = m.usercode AND u1.display = 'Y'", 'left');
            $builder->join("(SELECT n.conn_key, COUNT(*) AS total_connected 
                             FROM main m
                             INNER JOIN heardt h ON m.diary_no = h.diary_no
                             INNER JOIN main n ON m.diary_no::text = n.conn_key
                             WHERE n.diary_no::text != n.conn_key AND m.c_status = 'P'
                             GROUP BY n.conn_key) aa", 'm.diary_no::text = aa.conn_key', 'left', false);
            $builder->join("mul_category mc", "mc.diary_no = m.diary_no AND mc.display = 'Y'", 'left');
            $builder->join("dispose d", "m.diary_no = d.diary_no", 'left');
            $builder->join("master.submaster sm", "mc.submaster_id = sm.id AND (sm.display = 'Y' OR sm.display IS NULL)", 'left');
            $builder->join("master.subheading c", "h.subhead = c.stagecode AND (c.display = 'Y' OR c.display IS NULL)", 'left');
            $builder->where('m.c_status', 'P');
            $builder->where('us.id', $section);
            $builder->where('h.board_type', $listCourtType);
            $builder->orderBy('m.diary_no_rec_date', 'asc');
            $sql = $builder->get();
        }

        // else if($reportType == 2 && ($section!="" || isset($section)))
        // {
        //     $query_ch="";
        //     if(strcmp($dateType,'F' )== 0) {
        //         $query_ch=' AND (h.next_dt >= DATE(NOW()))';
        //     }
        //     else {
        //         $query_ch=' AND (h.next_dt < DATE(NOW()))';
        //     }
        //     $sql = "SELECT m.active_fil_dt,m.diary_no_rec_date,us.section_name AS user_section, 
        //                 case when mf_active='F' then 'Regular' else 'Misc.' end as casestage,
        //                             aa.total_connected AS group_count,
        //                             CASE
        //                     WHEN
        //                         (m.diary_no = m.conn_key
        //                             OR m.conn_key = 0
        //                             OR m.conn_key = ''
        //                             OR m.conn_key IS NULL)
        //                     THEN
        //                         'M'
        //                     ELSE 'C'
        //                 END AS main_or_connected,
        //                 sm.category_sc_old, u.name alloted_to_da, 
        //                 SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) AS diary_no, 
        //                 SUBSTR(m.diary_no, - 4) AS diary_year, 
        //                 DATE_FORMAT(m.diary_no_rec_date, '%d-%m-%Y') AS diary_date,
        //                 m.pet_name, m.res_name, m.reg_no_display,
        //                 concat(m.reg_no_display, '@ D.No.', SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) ,'/', SUBSTR(m.diary_no, - 4)) As CaseNo,
        //                 CASE WHEN h.main_supp_flag = 0 THEN 'Ready' ELSE 'Not Ready' END AS Ready_status,
        //                 m.c_status, h.next_dt AS Hearing_Date, h.board_type FROM main m
        //                 INNER join `heardt` h ON m.diary_no = h.diary_no $query_ch 
        //                 LEFT JOIN users u ON u.usercode = m.dacode AND (u.display = 'Y' or u.display is null) 
        //                 left join usersection us on us.id=u.section and us.display='Y' 
        //                 LEFT JOIN users u1 ON u1.usercode = m.usercode AND u1.display = 'Y' 
        //                 LEFT JOIN (select n.conn_key,count(*) as total_connected from main m
        //                            inner join heardt h on m.diary_no=h.diary_no
        //                            inner join main n on m.diary_no=n.conn_key where n.diary_no!=n.conn_key and m.c_status='P'
        //                            group by n.conn_key
        //                           ) aa on m.diary_no=aa.conn_key
        //                 left join mul_category mc ON mc.diary_no = m.diary_no AND (mc.display='Y')
        //                 left outer join submaster sm ON mc.submaster_id = sm.id AND (sm.display='Y' or sm.display is null) LEFT outer JOIN subheading c ON h.subhead = c.stagecode AND (c.display = 'Y' or c.display is null) 
        //                 WHERE m.c_status = 'P' AND us.id=$section order by m.diary_no_rec_date asc";

        // }
        else if ($reportType == 4 && ($section != "" || isset($section))) {
            $builder = $this->db->table('main m');
            $builder->select("m.diary_no_rec_date,us.section_name AS user_section,
                    CASE WHEN mf_active = 'F' THEN 'Regular' ELSE 'Misc.' END AS casestage,aa.total_connected AS group_count, 
                    sm.category_sc_old, u.name AS alloted_to_da, SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4) AS diary_no, 
                    SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3 FOR 4) AS diary_year, TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS diary_date,
                    m.pet_name, m.res_name, m.reg_no_display, CONCAT(m.reg_no_display, '@ D.No.', 
                        SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4), 
                        '/', 
                        SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3 FOR 4)) AS CaseNo,
                    CASE WHEN h.main_supp_flag = 0 THEN 'Ready' ELSE 'Not Ready' END AS Ready_status, 
                    m.c_status, rc.agency_name,h.next_dt AS Hearing_Date, h.board_type, d.disp_dt");

            $builder->join("master.ref_agency_code rc", "rc.id = m.ref_agency_code_id", "inner");
            $builder->join("heardt h", "m.diary_no = h.diary_no", "left");
            $builder->join("master.users u", "u.usercode = m.dacode AND (u.display = 'Y' OR u.display IS NULL)", "left");
            $builder->join("master.usersection us", "us.id = u.section AND us.display = 'Y'", "left");
            $builder->join("master.users u1", "u1.usercode = m.usercode AND u1.display = 'Y'", "left");
            $builder->join("(SELECT n.conn_key, COUNT(*) AS total_connected 
                                FROM main m INNER JOIN heardt h ON m.diary_no = h.diary_no
                                INNER JOIN main n ON m.diary_no::text = n.conn_key 
                                WHERE n.diary_no::text != n.conn_key GROUP BY n.conn_key) aa", "m.diary_no::text = aa.conn_key", "left", false);
            $builder->join("mul_category mc", "mc.diary_no = m.diary_no AND mc.display = 'Y'", "left");
            $builder->join("dispose d", "m.diary_no = d.diary_no", "inner");
            $builder->join("master.submaster sm", "mc.submaster_id = sm.id AND (sm.display = 'Y' OR sm.display IS NULL)", "left");
            $builder->join("master.subheading c", "h.subhead = c.stagecode AND (c.display = 'Y' OR c.display IS NULL)", "left");
            $builder->where("m.c_status", "D");
            $builder->where("(m.diary_no::text = m.conn_key OR m.conn_key IS NULL)", null, false);
            $builder->where("us.id", $section);
            $builder->where("disp_dt >=", $fromDate);
            $builder->where("disp_dt <=", $toDate);
            $builder->orderBy("m.diary_no_rec_date", "ASC");
            $sql = $builder->get();
        }
        if ($sql->getNumRows() > 0) {
            return $sql->getResultArray();
        } else {
            return 0;
        }
    }


    function getcases_nb_gr_90days($section, $da)
    {
        $builder = $this->db->table('main m');
        $builder->distinct();
        $builder->select("us.section_name AS user_section,
            u.name,u.empid,SUBSTR(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4) AS diary_no,
            SUBSTR(m.diary_no::text, -4) AS diary_year,TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS diary_date,
            m.pet_name,m.res_name,m.reg_no_display,h.next_dt AS Hearing_Date,h.board_type");
        $builder->join("heardt h", "m.diary_no = h.diary_no", "left");
        $builder->join("last_heardt lh", "m.diary_no = lh.diary_no", "left");
        $builder->join("master.users u", "u.usercode = m.dacode AND (u.display = 'Y' OR u.display IS NULL)", "left");
        $builder->join("master.usersection us", "us.id = u.section AND us.display = 'Y'", "left");
        $builder->where('m.c_status', 'P');
        $builder->where('us.id', $section);
        if ($da != 0)
            $builder->where('m.dacode', $da);

        $builder->where("(CURRENT_DATE - lh.next_dt >= 90 AND CURRENT_DATE - h.next_dt >= 90)", null, false);
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function get_DA_sectionwise($section = 0)
    {
        $builder = $this->db->table('master.users user');
        $builder->select("usercode, CONCAT(name, ', ', type_name) as name, empid, section_name");
        $builder->join('master.usersection us', 'user.section = us.id', 'inner');
        $builder->join('master.usertype ut', 'ut.id = user.usertype', 'inner');
        $builder->where('section', $section);
        $builder->where('user.display', 'Y');
        $builder->whereIn('ut.id', [14, 50, 51, 17]);
        $builder->orderBy('type_name, empid');
        $query = $builder->get();
        return $query->getResultArray();
    }

    function Section_Reg_Report()
    {
        $builder = $this->db->table('master.usersection');
        $builder->select('section_name');
        $builder->where('isda', 'Y');
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function Section_Reg_Report_Result($section = null)
    {
        $builder = $this->db->table('main');
        $builder->select("SUBSTR(diary_no::text, -4) AS diary_year, COUNT(diary_no) AS numb");
        $builder->whereIn('dacode', function ($subQuery) use ($section) {
            $subQuery->select('usercode')
                ->from('master.users')
                ->whereIn('section', function ($innerSubQuery) use ($section) {
                    $innerSubQuery->select('id')
                        ->from('master.usersection')
                        ->where('section_name', $section);
                });
        });
        $builder->where('fil_no IS NULL')->where('reg_no_display IS NULL')->where('c_status', 'P')->where('fil_dt IS NULL');
        $builder->groupBy("SUBSTR(diary_no::text, -4)");
        $builder->orderBy("SUBSTR(diary_no::text, -4) ASC");
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function Section_Unreg_Report_Result($section = null)
    {
        $builder = $this->db->table('main');
        $builder->select("SUBSTR(diary_no::text, -4) AS diary_year, COUNT(diary_no) AS numb");
        $builder->whereIn('dacode', function ($subQuery) use ($section) {
            $subQuery->select('usercode')
                ->from('master.users')
                ->whereIn('section', function ($innerSubQuery) use ($section) {
                    $innerSubQuery->select('id')
                        ->from('master.usersection')
                        ->where('section_name', $section);
                });
        });
        $builder->where('c_status', 'P')->where('fil_no IS NOT NULL')->where('reg_no_display IS NOT NULL');
        $builder->where('fil_dt IS NOT NULL');
        $builder->groupBy("SUBSTR(diary_no::text, -4)");
        $builder->orderBy("SUBSTR(diary_no::text, -4) ASC");
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }


    function Pend_Report_Mod($section = null)
    {
        $builder = $this->db->table('main m');
        $builder->select("rs.name as state, GROUP_CONCAT(DISTINCT rc.agency_name) as agency_name, us.section_name,
         COUNT(DISTINCT m.diary_no) as total_pendency, MAX(m.fil_dt) AS latest_fil_dt");
        $builder->join("master.casetype c", "m.casetype_id = c.casecode", "left");
        $builder->join("master.ref_agency_code rc", "rc.id = m.ref_agency_code_id", "left");
        $builder->join("master.state rs", "rs.id_no = m.ref_agency_state_id AND rs.display = 'Y'", "left");
        $builder->join("master.users u", "u.usercode = m.dacode", "left");
        $builder->join("master.usersection us", "us.id = u.section", "left");
        $builder->where('m.c_status', 'P');
        $builder->where('m.fil_no IS NULL');
        $builder->where('(m.reg_no_display IS NULL OR m.reg_no_display = \'\' )');
        $builder->where('m.fil_dt IS NULL');
        $builder->whereIn('m.dacode', function ($subQuery) use ($section) {
            $subQuery->select('usercode')
            ->from('master.users')
            ->whereIn('section', function ($innerSubQuery) use ($section) {
                $innerSubQuery->select('id')
                ->from('master.usersection')
                ->where('section_name', $section);
            });
        });
        $builder->groupBy(['rs.name', 'us.section_name']);
        $builder->orderBy('latest_fil_dt');
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function Pend_Report_Mod1($section = null)
    {
        $builder = $this->db->table('main m');
        $builder->select("rs.name as state, GROUP_CONCAT(DISTINCT rc.agency_name) as agency_name, us.section_name, 
        COUNT(DISTINCT m.diary_no) as total_pendency, MAX(m.fil_dt) AS latest_fil_dt");
        $builder->join("master.casetype c", "m.casetype_id = c.casecode", "left");
        $builder->join("master.ref_agency_code rc", "rc.id = m.ref_agency_code_id", "left");
        $builder->join("master.state rs", "rs.id_no = m.ref_agency_state_id AND rs.display = 'Y'", "left");
        $builder->join("master.users u", "u.usercode = m.dacode", "left");
        $builder->join("master.usersection us", "us.id = u.section", "left");
        $builder->where('m.c_status', 'P');
        $builder->where("m.fil_no IS NOT NULL");
        $builder->where("m.reg_no_display IS NOT NULL");
        $builder->where('m.fil_dt IS NOT NULL');
        $builder->whereIn('m.dacode', function ($subQuery) use ($section) {
            $subQuery->select('usercode')
                ->from('master.users')
                ->whereIn('section', function ($innerSubQuery) use ($section) {
                    $innerSubQuery->select('id')
                        ->from('master.usersection')
                        ->where('section_name', $section);
                });
        });
        $builder->groupBy(['rs.name', 'us.section_name']);
        $builder->orderBy('latest_fil_dt');
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function Matters_List($section=null,$d_year=null)
    {
        $builder = $this->db->table('main m');
        $builder->select("SUBSTR(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4) AS diary_no,
            SUBSTR(m.diary_no::text, -4) AS diary_year,m.reg_no_display,
            CONCAT(m.pet_name,
            CASE 
                WHEN m.pno = 2 THEN 'and anr.'
                WHEN m.pno > 2 THEN 'and ors.'
                ELSE ''
            END,
            ' vs ',
            m.res_name,
            CASE
                WHEN m.rno = 2 THEN 'and anr.'
                WHEN m.rno > 2 THEN 'and ors.'
                ELSE ''
            END
            ) AS cause,u.name");
        $builder->join('master.users u', 'm.dacode = u.usercode', 'inner');
        $builder->whereIn('m.dacode', function ($subQuery) use ($section) {
            $subQuery->select('usercode')
            ->from('master.users')
            ->whereIn('section', function ($innerSubQuery) use ($section) {
                $innerSubQuery->select('id')
                ->from('master.usersection')
                ->where('section_name', $section);
            });
        });
        $builder->where('m.c_status', 'P');
        $builder->where('m.fil_no IS NOT NULL');
        $builder->where('m.reg_no_display IS NOT NULL');
        $builder->where('m.fil_dt IS NOT NULL');
        $builder->groupBy(['SUBSTR(m.diary_no::text, -4)', 'm.diary_no', 'm.reg_no_display', 'm.pet_name', 'm.res_name', 'm.pno', 'm.rno', 'u.name']);
        $builder->having('SUBSTR(m.diary_no::text, -4)', $d_year);
        $builder->orderBy('SUBSTR(m.diary_no::text, -4) ASC');

        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function Unregistered_Matters_List($section=null,$d_year=null)
    {
        $builder = $this->db->table('main m');
        $builder->select("SUBSTR(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4) AS diary_no,
            SUBSTR(m.diary_no::text, -4) AS diary_year,
            m.diary_no,
            m.reg_no_display,
            CONCAT(
            m.pet_name,
            CASE
                WHEN m.pno = 2 THEN 'and anr.'
                WHEN m.pno > 2 THEN 'and ors.'
                ELSE ''
            END,
            ' vs ',
            m.res_name,
            CASE
                WHEN m.rno = 2 THEN 'and anr.'
                WHEN m.rno > 2 THEN 'and ors.'
                ELSE ''
            END
            ) AS cause,
            u.name");
        $builder->groupBy(['m.diary_no','m.reg_no_display','m.pet_name','m.res_name','m.pno','m.rno','u.name']);
        $builder->join('master.users u', 'm.dacode = u.usercode', 'inner');
        $builder->whereIn('m.dacode', function ($subQuery) use ($section) {
            $subQuery->select('usercode')
            ->from('master.users')
            ->whereIn('section', function ($innerSubQuery) use ($section) {
                $innerSubQuery->select('id')
                ->from('master.usersection')
                ->where('section_name', $section);
            });
        });
        $builder->where('m.fil_no IS NULL');
        $builder->where('m.reg_no_display IS NULL');
        $builder->where('m.c_status', 'P');
        $builder->where('m.fil_dt IS NULL');
        $builder->having('SUBSTR(m.diary_no::text, -4)', $d_year);
        $builder->orderBy('SUBSTR(m.diary_no::text, -4) ASC');

        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function Unregistered_State_List($section=null,$state=null,$court=null)
    {
        $builder = $this->db->table('main m');
        $builder->distinct();
        $builder->select("SUBSTR(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4) AS diary_number,
            SUBSTR(m.diary_no::text, -4) AS diary_year,
            m.diary_no,m.reg_no_display,m.fil_dt,
            CONCAT(
            m.pet_name,
            CASE
                WHEN m.pno = 2 THEN 'and anr.'
                WHEN m.pno > 2 THEN 'and ors.'
                ELSE ''
            END,
            ' vs ',
            m.res_name,
            CASE
                WHEN m.rno = 2 THEN 'and anr.'
                WHEN m.rno > 2 THEN 'and ors.'
                ELSE ''
            END
            ) AS cause,
            u.name");
        $builder->join("master.casetype c", "m.casetype_id = c.casecode", "left");
        $builder->join("master.ref_agency_code rc", "rc.id = m.ref_agency_code_id", "left");
        $builder->join("master.state rs", "rs.id_no = m.ref_agency_state_id AND rs.display = 'Y'", "left");
        $builder->join("master.users u", "u.usercode = m.dacode", "left");
        $builder->join("master.usersection us", "us.id = u.section", "left");
        $builder->where("m.c_status", "P");
        $builder->where("m.fil_no IS NULL");
        $builder->where("m.reg_no_display IS NULL");
        $builder->where("m.fil_dt IS NULL");

        if (!empty($state)) {
            $builder->where("rs.name", $state);
        } else {
            $builder->where("rs.name IS NULL", null, false);
        }

        $builder->whereIn("m.dacode", function ($subQuery) use ($section) {
            $subQuery->select("usercode")
            ->from("master.users")
            ->whereIn("section", function ($innerSubQuery) use ($section) {
                $innerSubQuery->select("id")
                ->from("master.usersection")
                ->where("section_name", $section);
            });
        });

        $builder->orderBy("m.fil_dt", "ASC");
        $builder->groupBy(["m.diary_no","m.reg_no_display","m.fil_dt","m.pet_name","m.res_name","m.pno","m.rno","u.name","rs.name"]);

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
    

    function Registered_State_List($section=null,$state=null,$court=null)
    {
        $builder = $this->db->table('main m');
        $builder->distinct();
        $builder->select("SUBSTR(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4) AS diary_number,
            SUBSTR(m.diary_no::text, -4) AS diary_year,
            m.diary_no,
            m.reg_no_display,
            m.fil_dt,
            CONCAT(
            m.pet_name,
            CASE
                WHEN m.pno = 2 THEN 'and anr.'
                WHEN m.pno > 2 THEN 'and ors.'
                ELSE ''
            END,
            ' vs ',
            m.res_name,
            CASE
                WHEN m.rno = 2 THEN 'and anr.'
                WHEN m.rno > 2 THEN 'and ors.'
                ELSE ''
            END
            ) AS cause,
            u.name
        ");
        $builder->join("master.casetype c", "m.casetype_id = c.casecode", "left");
        $builder->join("master.ref_agency_code rc", "rc.id = m.ref_agency_code_id", "left");
        $builder->join("master.state rs", "rs.id_no = m.ref_agency_state_id AND rs.display = 'Y'", "left");
        $builder->join("master.users u", "u.usercode = m.dacode", "left");
        $builder->join("master.usersection us", "us.id = u.section", "left");
        $builder->where("m.c_status", "P");
        $builder->where("m.fil_no IS NOT NULL");
        $builder->where("m.reg_no_display IS NOT NULL");
        $builder->where("m.fil_dt IS NOT NULL");

        if (!empty($state)) {
            $builder->where('rs.name', $state);
        } else {
            $builder->where('rs.name IS NULL', null, false);
        }

        $builder->whereIn('m.dacode', function ($subQuery) use ($section) {
            $subQuery->select('usercode')
            ->from('master.users')
            ->whereIn('section', function ($innerSubQuery) use ($section) {
                $innerSubQuery->select('id')
                ->from('master.usersection')
                ->where('section_name', $section);
            });
        });

        $builder->orderBy('m.fil_dt', 'ASC');
        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
}
