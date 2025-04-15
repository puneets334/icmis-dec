<?php

namespace App\Models\ManagementReport;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class VCModel extends Model
{
    //protected $db_eFiling;
    protected $db;

    function __construct()
    {
        parent::__construct();
        //$this->db_eFiling =  \Config\Database::connect('e_filing');
        $this->db = \Config\Database::connect();
    }

    function getMainConnTakenup_matters($condition)
    {
        $sql = "select board_type,main_connected,count(1) as total from
                (select h.* from
                (select
                h.diary_no,case when (m.diary_no = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0') then 'Main' else 'Connected' end as main_connected, if(m.c_status = 'P', 'Pending', 'Disposed') case_status, h.next_dt, h.mainhead, h.brd_slno, h.clno, h.roster_id, h.judges, h.main_supp_flag, m.reg_no_display  ,h.board_type
                from main m
                inner join heardt h on m.diary_no = h.diary_no
                where
                h.next_dt $condition and h.main_supp_flag in (1,2)

                union
                select
                h.diary_no,case when (m.diary_no = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0') then 'Main' else 'Connected' end as main_connected, if(m.c_status = 'P', 'Pending', 'Disposed') case_status,
                h.next_dt, h.mainhead, h.brd_slno, h.clno, h.roster_id, h.judges, h.main_supp_flag, m.reg_no_display ,h.board_type
                from main m
                inner join last_heardt h on m.diary_no = h.diary_no
                where
                h.next_dt $condition
                and h.main_supp_flag in (1,2)
                and (h.bench_flag = '' or h.bench_flag is null)
                ) h
                left join cl_printed p on p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = 'Y'
                where p.next_dt is not null
                group by diary_no, roster_id
                ) a where board_type = 'J' group by board_type,main_connected order by main_connected";



        $query = $this->db->query($sql);
        //echo  $this->db->last_query(); exit(0);
        return $query->getResultArray();
    }

    function getMainConnDisposal_matters($condition)
    {

        $sql = "select h.board_type, case when (m.diary_no = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0') then 'Main' else 'Connected' end as main_connected,count(*) as total from dispose d
                inner join main m on d.diary_no=m.diary_no and m.c_status='D'
                and (d.ord_dt) $condition inner join heardt h on m.diary_no=h.diary_no where board_type = 'J' group by h.board_type,main_connected order by main_connected ";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function get_registered_for_IDP_report($condition)
    {
        $sql = "select count(*) as total_registered from main where active_fil_no!='' and active_fil_no is not null and date(active_fil_dt)!='0000-00-00' and date(active_fil_dt) $condition ";

        $query = $this->db->query($sql);
        // echo   $this->db->last_query();exit(0);

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }




    function get_total_judgment($condition)
    {

        $sql = " select count(*) as total from ordernet where orderdate  $condition and type='J'";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function get_MA_disposed($condition)
    {
        $sql = " select count(*) as total from dispose d
                inner join main m on d.diary_no=m.diary_no and m.c_status='D'
                and (d.ord_dt) $condition inner join heardt h on m.diary_no=h.diary_no where m.casetype_id=39";

        $query = $this->db->query($sql);

        // echo $this->db->last_query();exit(0);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function get_IA_Disposed($condition)
    {
        $sql = "select count(*) as total from docdetails where date(dispose_date) $condition and doccode=8 and iastat='D'";
        $query = $this->db->query($sql);

        // echo $this->db->last_query();exit(0);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function get_SLP_Appeals_disposed($condition)
    {

        $sql = " select count(*) as total from dispose d
                inner join main m on d.diary_no=m.diary_no and m.c_status='D'
                and (d.ord_dt) $condition inner join heardt h on m.diary_no=h.diary_no where m.casetype_id in (1,2,3,4)";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function get_Writ_Petitions_disposed($condition)
    {
        $sql = "
            select count(*) as total from dispose d
            inner join main m on d.diary_no=m.diary_no and m.c_status='D'
            and (d.ord_dt) $condition inner join heardt h on m.diary_no=h.diary_no where m.casetype_id in (5,6) ; ";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function get_Transfer_Petitions_disposed($condition)
    {

        $sql = "
            select count(*) as total from dispose d
            inner join main m on d.diary_no=m.diary_no and m.c_status='D'
            and (d.ord_dt) $condition inner join heardt h on m.diary_no=h.diary_no where m.casetype_id in (7,8) ; ";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function  get_total_filed($condition)
    {

        $sql = "select count(*) as total from main where date(diary_no_rec_date)  $condition  ";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function get_filing_SLP_Appeals($condition)
    {
        $sql = "select count(DISTINCT diary_no) as total from main where date(diary_no_rec_date)  $condition
             and (casetype_id in (1,2,3,4) or active_casetype_id in (1,2,3,4)) ";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function get_filing_MA($condition)
    {

        $sql = "select count(*) as total from main where date(diary_no_rec_date)  $condition
             and casetype_id=39 ";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function get_filing_IA($condition)
    {
        $sql = "select count(*) as total from docdetails where date(ent_dt)  $condition
             and doccode=8 and display='Y' ";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    //Code for new VC report

    function disposal_Vc_Stats($condition)
    {
        /*$sql="select mf_active,count(*) as total from dispose d inner join main m on d.diary_no=m.diary_no and m.c_status='D'
and (d.ord_dt) $condition and  mf_active in ('M','F') group by mf_active order by mf_active";*/

        $sql = "select count(case when mf_active='M' or mf_active='' then 1 end) as m_total,count(case when mf_active='F' then 1 end ) as r_total from(
        select distinct mf_active,m.diary_no from dispose d inner join main m on d.diary_no=m.diary_no where m.c_status='D' and date(d.ord_dt) $condition) a";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function Listed_Vc_Stats($condition)
    {

        $sql = "select count(case when mainhead='M' or mainhead='' then 1 end) as m_total,count(case when mainhead='F' or mainhead='' then 1 end) as r_total from(select mainhead,diary_no from
                (select h.* from
                (select
                h.diary_no, h.mainhead,h.next_dt, h.brd_slno, h.clno, h.roster_id
                from main m
                inner join heardt h on m.diary_no = h.diary_no
                where
                h.next_dt $condition and h.main_supp_flag in (1,2)
                union
                select
                h.diary_no, h.mainhead,h.next_dt, h.brd_slno, h.clno, h.roster_id
                from main m
                inner join last_heardt h on m.diary_no = h.diary_no
                where
                h.next_dt $condition
                and h.main_supp_flag in (1,2)
                and (h.bench_flag = '' or h.bench_flag is null) # and c_status = 'P'
                ) h
                left join cl_printed p on p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = 'Y'
                where p.next_dt is not null
                group by diary_no, roster_id
                ) a ) b";

        $query = $this->db->query($sql);

        //echo $this->db->last_query();exit(0);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function bench_Vc_Stats($condition)
    {

        $sql = "select count(*) total from (select
                *
                from (select h.* from
                (select
                h.diary_no,h.next_dt,  h.roster_id,h.mainhead,h.clno
                from main m
                inner join heardt h on m.diary_no = h.diary_no
                where
                h.next_dt $condition and h.main_supp_flag in (1,2)
                union
                select
                h.diary_no,h.next_dt,h.roster_id,h.mainhead,h.clno
                from main m
                inner join last_heardt h on m.diary_no = h.diary_no
                where
                h.next_dt $condition
                and h.main_supp_flag in (1,2)
                and (h.bench_flag = '' or h.bench_flag is null)
                ) h
                left join cl_printed p on p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = 'Y'
                where p.next_dt is not null
                group by diary_no, roster_id) a
                group by next_dt, roster_id) temp";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function Filed_Vc_Stats($condition)
    {
        $sql = "select count(*) as total from main where date(diary_no_rec_date) $condition";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function efiled_matters($condition)
    {
        $sql = "select sum(case when app_flag like 'filing%' and status_id=1  then 1 else 0 end ) as total from efiling_transaction_records where date(transaction_datetime) $condition";

        $query = $this->db_eFiling->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function judge_report($judge_code)
    {
        $condition = "1=1";

        if ($judge_code != 1) {
            $condition = "coram like '$judge_code%'";
        }

        $sql = "select @a:=@a+1 SNO, c.Case_NO, Cause_Title,
                coram,
                tentative_list_date,
                Section, DA
                from
                (select concat(m.reg_no_display,' @ ',m.diary_no) as Case_NO, concat(m.pet_name,' Vs. ',m.res_name) as Cause_Title,
                ifnull((select group_concat(abbreviation separator '#') from judge where is_retired = 'N' and display = 'Y' and find_in_set(jcode,h.coram) > 0 ),'') as coram,
                date_format(h.next_dt,'%d-%m-%Y') tentative_list_date, tentative_section(m.diary_no) Section, tentative_da(m.diary_no) DA
                from main m
                inner join heardt h on h.diary_no = m.diary_no
                LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                where rd.fil_no IS NULL AND $condition and c_status = 'P' and mainhead = 'M' and board_type = 'J' and subhead in (815,816)
                and main_supp_flag = 0 and h.next_dt != '0000-00-00'
                AND (m.diary_no = m.conn_key OR m.conn_key=0 OR m.conn_key = '' OR m.conn_key IS NULL)
                group by m.diary_no
                order by m.diary_no_rec_date) c, (SELECT @a:= 0) AS b";

        $query = $this->db->query($sql);
        // echo   $this->db->last_query();exit(0);

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function get_judges_name($jcode)
    {

        $this->db->SELECT("jname");
        $this->db->FROM('judge');
        $this->db->WHERE('jcode', $jcode);

        $query = $this->db->get();


        return $query->result();
    }

    function get_judge_code()
    {
        $ucode = $this->session->userdata('dcmis_user_idd');
        $this->db->SELECT("jcode");
        $this->db->FROM('users');
        $this->db->WHERE('usercode', $ucode);

        $query = $this->db->get();

        return $query->result();
    }


    //Section of Judges Report
    function get_judges_list()
    {
        // select * from judge where jtype = 'J' and display = 'Y' order by is_retired asc, jcode asc

        // Load the database library if it's not already loaded
        $builder = $this->db->table('master.judge');

        // Perform the query with conditions and ordering
        $builder->where('jtype', 'J')
                ->where('display', 'Y')
                ->orderBy('is_retired', 'ASC')
                ->orderBy('jcode', 'ASC');        
        // Execute the query and get the result
        $query = $builder->get();
        
        if($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return [];
        }
    }

    //Section of Judges Report
    function get_judges_list_current()
    {

        $this->db->SELECT("*");
        $this->db->FROM('judge');
        $this->db->WHERE('is_retired', 'N');
        $this->db->where('jtype', 'J');
        $this->db->where('display', 'Y');
        $this->db->ORDER_BY("jcode", "asc");
        $query = $this->db->get();


        return $query->result();
    }

    function get_judges_DOA_AOR($jcode)
    {
        // Load the database library if it's not already loaded
        $builder = $this->db->table('master.judge');

        // Perform the query with conditions and ordering
        $builder->where('jtype', 'J')
                ->where('jcode', $jcode);

        // Execute the query and get the result
        $query = $builder->get();
        
        if($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return [];
        }
    }

    function single_bench($jcode)
    {
        $sql = "
            SELECT COUNT(DISTINCT next_dt) AS total
            FROM (
                SELECT diary_no, h.next_dt, judges,
                    (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) AS TotalValue
                FROM heardt h
                JOIN cl_printed cl 
                    ON (cl.next_dt = h.next_dt 
                        AND cl.m_f = h.mainhead 
                        AND cl.part = h.clno 
                        AND cl.main_supp = h.main_supp_flag 
                        AND cl.roster_id = h.roster_id 
                        AND cl.display = 'Y' 
                        AND h.brd_slno BETWEEN cl.from_brd_no AND cl.to_brd_no)
                WHERE h.main_supp_flag IN (1, 2) 
                AND h.judges != '0' 
                AND h.judges != '' 
                AND h.clno != 0 
                AND h.brd_slno != 0 
                AND cl.next_dt IS NOT NULL 
                AND (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) = 0 
                AND judges = '$jcode'
                UNION
                SELECT diary_no, h.next_dt, judges,
                    (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) AS TotalValue
                FROM last_heardt h
                JOIN cl_printed cl
                    ON (cl.next_dt = h.next_dt 
                        AND cl.m_f = h.mainhead 
                        AND cl.part = h.clno 
                        AND cl.main_supp = h.main_supp_flag 
                        AND cl.roster_id = h.roster_id 
                        AND cl.display = 'Y' 
                        AND h.brd_slno BETWEEN cl.from_brd_no AND cl.to_brd_no)
                WHERE h.main_supp_flag IN (1, 2) 
                AND h.judges != '0' 
                AND h.judges != '' 
                AND (h.bench_flag = '' OR h.bench_flag IS NULL) 
                AND h.clno != 0 
                AND h.brd_slno != 0 
                AND cl.next_dt IS NOT NULL 
                AND (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) = 0 
                AND judges = '$jcode'
            ) temp
        ";

                // echo $sql;

        $query = $this->db->query($sql);

        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return [];
        }
    }

    function division_bench($jcode)
    {

        $sql = "
            SELECT COUNT(DISTINCT next_dt || ',' || judges) AS total
                FROM (
                    SELECT diary_no, h.next_dt, judges,
                        (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) AS TotalValue
                    FROM heardt h
                    JOIN cl_printed cl 
                        ON (cl.next_dt = h.next_dt 
                            AND cl.m_f = h.mainhead 
                            AND cl.part = h.clno 
                            AND cl.main_supp = h.main_supp_flag 
                            AND cl.roster_id = h.roster_id 
                            AND cl.display = 'Y' 
                            AND h.brd_slno BETWEEN cl.from_brd_no AND cl.to_brd_no)
                    WHERE h.main_supp_flag IN (1, 2) 
                    AND h.judges != '0' 
                    AND h.judges != '' 
                    AND h.clno != 0 
                    AND h.brd_slno != 0 
                    AND cl.next_dt IS NOT NULL 
                    AND (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) = 1 
                    AND array_position(string_to_array(judges, ','), '$jcode') = 2
                    UNION
                    SELECT diary_no, h.next_dt, judges,
                        (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) AS TotalValue
                    FROM last_heardt h
                    JOIN cl_printed cl 
                        ON (cl.next_dt = h.next_dt 
                            AND cl.m_f = h.mainhead 
                            AND cl.part = h.clno 
                            AND cl.main_supp = h.main_supp_flag 
                            AND cl.roster_id = h.roster_id 
                            AND cl.display = 'Y' 
                            AND h.brd_slno BETWEEN cl.from_brd_no AND cl.to_brd_no)
                    WHERE h.main_supp_flag IN (1, 2) 
                    AND h.judges != '0' 
                    AND h.judges != '' 
                    AND (h.bench_flag = '' OR h.bench_flag IS NULL) 
                    AND h.clno != 0 
                    AND h.brd_slno != 0 
                    AND cl.next_dt IS NOT NULL 
                    AND (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) = 1 
                    AND array_position(string_to_array(judges, ','), '$jcode') = 2
                ) temp
        ";

        // echo $sql;

        $query = $this->db->query($sql);

        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return [];
        }
    }


    function five($jcode)
    {
        // Point 5(No. of Division Bench Held in which His Lordship was presiding over the bench)

        $sql = "
            SELECT COUNT(DISTINCT next_dt || ',' || judges) AS total
            FROM (
                SELECT diary_no, h.next_dt, judges,
                    (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) AS TotalValue
                FROM heardt h
                JOIN cl_printed cl 
                    ON (cl.next_dt = h.next_dt 
                        AND cl.m_f = h.mainhead 
                        AND cl.part = h.clno 
                        AND cl.main_supp = h.main_supp_flag 
                        AND cl.roster_id = h.roster_id 
                        AND cl.display = 'Y' 
                        AND h.brd_slno BETWEEN cl.from_brd_no AND cl.to_brd_no)
                WHERE h.main_supp_flag IN (1, 2) 
                AND h.judges != '0' 
                AND h.judges != '' 
                AND h.clno != 0 
                AND h.brd_slno != 0 
                AND cl.next_dt IS NOT NULL 
                AND (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) = 1 
                AND array_position(string_to_array(judges, ','), '$jcode') = 1
                UNION
                SELECT diary_no, h.next_dt, judges,
                    (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) AS TotalValue
                FROM last_heardt h
                JOIN cl_printed cl 
                    ON (cl.next_dt = h.next_dt 
                        AND cl.m_f = h.mainhead 
                        AND cl.part = h.clno 
                        AND cl.main_supp = h.main_supp_flag 
                        AND cl.roster_id = h.roster_id 
                        AND cl.display = 'Y' 
                        AND h.brd_slno BETWEEN cl.from_brd_no AND cl.to_brd_no)
                WHERE h.main_supp_flag IN (1, 2) 
                AND h.judges != '0'
                AND h.judges != '' 
                AND (h.bench_flag = '' OR h.bench_flag IS NULL) 
                AND h.clno != 0 
                AND h.brd_slno != 0 
                AND cl.next_dt IS NOT NULL 
                AND (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) = 1 
                AND array_position(string_to_array(judges, ','), '$jcode') = 1
            ) temp
        ";

        // echo $sql;

        $query = $this->db->query($sql);

        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return [];
        }
    }


    function six($jcode)
    {
        //Point 6(No. of three Judge Bench held in which His Lordship was not presiding over the bench)

        $sql = "
                SELECT COUNT(DISTINCT next_dt || ',' || judges) AS total
                FROM (
                    SELECT diary_no, h.next_dt, judges, 
                        (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) AS TotalValue
                    FROM heardt h
                    JOIN cl_printed cl 
                        ON (cl.next_dt = h.next_dt 
                            AND cl.m_f = h.mainhead 
                            AND cl.part = h.clno 
                            AND cl.main_supp = h.main_supp_flag 
                            AND cl.roster_id = h.roster_id 
                            AND cl.display = 'Y' 
                            AND h.brd_slno BETWEEN cl.from_brd_no AND cl.to_brd_no)
                    WHERE h.main_supp_flag IN (1, 2) 
                    AND h.judges != '0' 
                    AND h.judges != '' 
                    AND h.clno != 0 
                    AND h.brd_slno != 0 
                    AND cl.next_dt IS NOT NULL 
                    AND (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) = 2 
                    AND (array_position(string_to_array(judges, ','), '$jcode') = 2 
                        OR array_position(string_to_array(judges, ','), '$jcode') = 3)
                    UNION
                    SELECT diary_no, h.next_dt, judges, 
                        (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) AS TotalValue
                    FROM last_heardt h
                    JOIN cl_printed cl 
                        ON (cl.next_dt = h.next_dt 
                            AND cl.m_f = h.mainhead 
                            AND cl.part = h.clno 
                            AND cl.main_supp = h.main_supp_flag 
                            AND cl.roster_id = h.roster_id 
                            AND cl.display = 'Y' 
                            AND h.brd_slno BETWEEN cl.from_brd_no AND cl.to_brd_no)
                    WHERE h.main_supp_flag IN (1, 2) 
                    AND h.judges != '0' 
                    AND h.judges != '' 
                    AND (h.bench_flag = '' OR h.bench_flag IS NULL) 
                    AND h.clno != 0 
                    AND h.brd_slno != 0 
                    AND cl.next_dt IS NOT NULL 
                    AND (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) = 2 
                    AND (array_position(string_to_array(judges, ','), '$jcode') = 2 
                        OR array_position(string_to_array(judges, ','), '$jcode') = 3)
                ) temp
        ";
        
        // echo $sql;

        $query = $this->db->query($sql);

        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return [];
        }
    }

    function seven($jcode)
    {
        //  Point 7(No. of three Judge Bench held in which His Lordship was presiding over the bench)

        $sql = "
            SELECT COUNT(DISTINCT next_dt || ',' || judges) AS total
            FROM (
                SELECT diary_no, h.next_dt, judges, 
                    (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) AS TotalValue
                FROM heardt h
                JOIN cl_printed cl 
                    ON (cl.next_dt = h.next_dt 
                        AND cl.m_f = h.mainhead 
                        AND cl.part = h.clno 
                        AND cl.main_supp = h.main_supp_flag 
                        AND cl.roster_id = h.roster_id 
                        AND cl.display = 'Y' 
                        AND h.brd_slno BETWEEN cl.from_brd_no AND cl.to_brd_no)
                WHERE h.main_supp_flag IN (1, 2) 
                AND h.judges != '0' 
                AND h.judges != '' 
                AND h.clno != 0 
                AND h.brd_slno != 0 
                AND cl.next_dt IS NOT NULL 
                AND (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) = 2 
                AND array_position(string_to_array(judges, ','), '$jcode') = 1
                UNION
                SELECT diary_no, h.next_dt, judges, 
                    (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) AS TotalValue
                FROM last_heardt h
                JOIN cl_printed cl 
                    ON (cl.next_dt = h.next_dt 
                        AND cl.m_f = h.mainhead 
                        AND cl.part = h.clno 
                        AND cl.main_supp = h.main_supp_flag 
                        AND cl.roster_id = h.roster_id 
                        AND cl.display = 'Y' 
                        AND h.brd_slno BETWEEN cl.from_brd_no AND cl.to_brd_no)
                WHERE h.main_supp_flag IN (1, 2) 
                AND h.judges != '0' 
                AND h.judges != '' 
                AND (h.bench_flag = '' OR h.bench_flag IS NULL) 
                AND h.clno != 0 
                AND h.brd_slno != 0 
                AND cl.next_dt IS NOT NULL 
                AND (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) = 2 
                AND array_position(string_to_array(judges, ','), '$jcode') = 1
            ) temp
        ";

        // echo $sql;

        $query = $this->db->query($sql);

        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return [];
        }
    }

    function eight($jcode)
    {
        //Point 8(No. of three Judge Constitution Bench held in which His Lordship was not presiding over the bench)

        $sql ="
            SELECT COUNT(DISTINCT next_dt || ',' || judges) AS total
            FROM (
                SELECT h.diary_no, h.next_dt, judges, 
                    (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) AS TotalValue
                FROM heardt h
                JOIN mul_category m ON h.diary_no = m.diary_no AND m.display = 'Y'
                JOIN cl_printed cl ON (cl.next_dt = h.next_dt 
                                    AND cl.m_f = h.mainhead 
                                    AND cl.part = h.clno 
                                    AND cl.main_supp = h.main_supp_flag 
                                    AND cl.roster_id = h.roster_id 
                                    AND cl.display = 'Y' 
                                    AND h.brd_slno BETWEEN cl.from_brd_no AND cl.to_brd_no)
                WHERE h.main_supp_flag IN (1, 2)
                AND h.judges != '0' 
                AND h.judges != '' 
                AND h.clno != 0 
                AND h.brd_slno != 0 
                AND cl.next_dt IS NOT NULL 
                AND (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) = 2
                AND (array_position(string_to_array(judges, ','), '$jcode') = 2 
                    OR array_position(string_to_array(judges, ','), '$jcode') = 3)
                AND submaster_id = 239
                UNION
                SELECT h.diary_no, h.next_dt, judges, 
                    (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) AS TotalValue
                FROM last_heardt h
                JOIN mul_category m ON h.diary_no = m.diary_no AND m.display = 'Y'
                JOIN cl_printed cl ON (cl.next_dt = h.next_dt 
                                    AND cl.m_f = h.mainhead 
                                    AND cl.part = h.clno 
                                    AND cl.main_supp = h.main_supp_flag 
                                    AND cl.roster_id = h.roster_id 
                                    AND cl.display = 'Y' 
                                    AND h.brd_slno BETWEEN cl.from_brd_no AND cl.to_brd_no)
                WHERE h.main_supp_flag IN (1, 2)
                AND h.judges != '0' 
                AND h.judges != '' 
                AND (h.bench_flag = '' OR h.bench_flag IS NULL) 
                AND h.clno != 0 
                AND h.brd_slno != 0 
                AND cl.next_dt IS NOT NULL 
                AND (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) = 2
                AND (array_position(string_to_array(judges, ','), '$jcode') = 2 
                    OR array_position(string_to_array(judges, ','), '$jcode') = 3)
                AND submaster_id = 239
            ) temp
        ";

        // echo $sql;

        $query = $this->db->query($sql);

        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return [];
        }
    }

    function nine($jcode)
    {

        //Point 9(No. of three Judge Constitution Bench held in which His Lordship was presiding over the bench)

        $sql ="
            SELECT COUNT(DISTINCT next_dt || ',' || judges) AS total
            FROM (
                SELECT h.diary_no, h.next_dt, judges, 
                    (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) AS TotalValue
                FROM heardt h
                JOIN mul_category m ON h.diary_no = m.diary_no AND m.display = 'Y'
                JOIN cl_printed cl ON (cl.next_dt = h.next_dt 
                                    AND cl.m_f = h.mainhead 
                                    AND cl.part = h.clno 
                                    AND cl.main_supp = h.main_supp_flag 
                                    AND cl.roster_id = h.roster_id 
                                    AND cl.display = 'Y' 
                                    AND h.brd_slno BETWEEN cl.from_brd_no AND cl.to_brd_no)
                WHERE h.main_supp_flag IN (1, 2)
                AND h.judges != '0' 
                AND h.judges != '' 
                AND h.clno != 0 
                AND h.brd_slno != 0 
                AND cl.next_dt IS NOT NULL 
                AND (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) = 2
                AND array_position(string_to_array(judges, ','), '$jcode') = 1
                AND submaster_id = 239
                UNION
                SELECT h.diary_no, h.next_dt, judges, 
                    (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) AS TotalValue
                FROM last_heardt h
                JOIN mul_category m ON h.diary_no = m.diary_no AND m.display = 'Y'
                JOIN cl_printed cl ON (cl.next_dt = h.next_dt 
                                    AND cl.m_f = h.mainhead 
                                    AND cl.part = h.clno 
                                    AND cl.main_supp = h.main_supp_flag 
                                    AND cl.roster_id = h.roster_id 
                                    AND cl.display = 'Y' 
                                    AND h.brd_slno BETWEEN cl.from_brd_no AND cl.to_brd_no)
                WHERE h.main_supp_flag IN (1, 2)
                AND h.judges != '0' 
                AND h.judges != '' 
                AND (h.bench_flag = '' OR h.bench_flag IS NULL)
                AND h.clno != 0 
                AND h.brd_slno != 0 
                AND cl.next_dt IS NOT NULL 
                AND (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) = 2
                AND array_position(string_to_array(judges, ','), '$jcode') = 1
                AND submaster_id = 239
            ) temp        
        ";

        // echo $sql;

        $query = $this->db->query($sql);

        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return [];
        }
    }

    function ten($jcode)
    {
        //Point 10(No. of Constitution Bench held in which His Lordship was not presiding over the bench)

        $sql = "
            SELECT COUNT(DISTINCT next_dt || ',' || judges) AS total
            FROM (
                SELECT h.diary_no, h.next_dt, judges, 
                    (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) AS TotalValue,
                    submaster_id
                FROM heardt h
                JOIN (
                    SELECT string_agg(submaster_id::text, ',') AS submaster_id, diary_no
                    FROM mul_category
                    WHERE display = 'Y'
                    GROUP BY diary_no
                ) x ON x.diary_no = h.diary_no
                AND x.submaster_id IN ('239', '240', '241', '242', '243')
                JOIN cl_printed cl ON (cl.next_dt = h.next_dt
                                    AND cl.m_f = h.mainhead
                                    AND cl.part = h.clno
                                    AND cl.main_supp = h.main_supp_flag
                                    AND cl.roster_id = h.roster_id
                                    AND cl.display = 'Y'
                                    AND h.brd_slno BETWEEN cl.from_brd_no AND cl.to_brd_no)
                WHERE h.main_supp_flag IN (1, 2)
                AND h.judges != '0'
                AND h.judges != ''
                AND h.clno != 0
                AND h.brd_slno != 0
                AND cl.next_dt IS NOT NULL
                AND (
                    array_position(string_to_array(judges, ','), '$jcode') IN (2, 3, 4, 5, 6, 7, 8, 9, 10, 11)
                )
                AND (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) > 2
                UNION
                SELECT h.diary_no, h.next_dt, judges, 
                    (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) AS TotalValue,
                    submaster_id
                FROM last_heardt h
                JOIN (
                    SELECT string_agg(submaster_id::text, ',') AS submaster_id, diary_no
                    FROM mul_category
                    WHERE display = 'Y'
                    GROUP BY diary_no
                ) x ON x.diary_no = h.diary_no
                AND x.submaster_id IN ('239', '240', '241', '242', '243')
                JOIN cl_printed cl ON (cl.next_dt = h.next_dt
                                    AND cl.m_f = h.mainhead
                                    AND cl.part = h.clno
                                    AND cl.main_supp = h.main_supp_flag
                                    AND cl.roster_id = h.roster_id
                                    AND cl.display = 'Y'
                                    AND h.brd_slno BETWEEN cl.from_brd_no AND cl.to_brd_no)
                WHERE h.main_supp_flag IN (1, 2)
                AND h.judges != '0'
                AND h.judges != ''
                AND h.clno != 0
                AND h.brd_slno != 0
                AND cl.next_dt IS NOT NULL
                AND (h.bench_flag = '' OR h.bench_flag IS NULL)
                AND (
                    array_position(string_to_array(judges, ','), '$jcode') IN (2, 3, 4, 5, 6, 7, 8, 9, 10, 11)
                )
                AND (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) > 2
            ) temp
        ";
        
        // echo $sql;

        $query = $this->db->query($sql);

        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return [];
        }
    }

    function eleven($jcode)
    {
        //Point 11(No. of Constitution Bench held in which His Lordship was presiding over the bench)

        $sql = "
            SELECT COUNT(DISTINCT next_dt || ',' || judges) AS total
            FROM (
                SELECT h.diary_no, h.next_dt, judges, 
                    (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) AS TotalValue,
                    submaster_id
                FROM heardt h
                JOIN (
                    SELECT string_agg(submaster_id::text, ',') AS submaster_id, diary_no
                    FROM mul_category
                    WHERE display = 'Y'
                    GROUP BY diary_no
                ) x ON x.diary_no = h.diary_no
                AND x.submaster_id IN ('239', '240', '241', '242', '243')
                JOIN cl_printed cl ON (cl.next_dt = h.next_dt
                                    AND cl.m_f = h.mainhead
                                    AND cl.part = h.clno
                                    AND cl.main_supp = h.main_supp_flag
                                    AND cl.roster_id = h.roster_id
                                    AND cl.display = 'Y'
                                    AND h.brd_slno BETWEEN cl.from_brd_no AND cl.to_brd_no)
                WHERE h.main_supp_flag IN (1, 2)
                AND h.judges != '0'
                AND h.judges != ''
                AND h.clno != 0
                AND h.brd_slno != 0
                AND cl.next_dt IS NOT NULL
                AND array_position(string_to_array(judges, ','), '$jcode') = 1
                AND (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) > 2
                UNION
                SELECT h.diary_no, h.next_dt, judges, 
                    (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) AS TotalValue,
                    submaster_id
                FROM last_heardt h
                JOIN (
                    SELECT string_agg(submaster_id::text, ',') AS submaster_id, diary_no
                    FROM mul_category
                    WHERE display = 'Y'
                    GROUP BY diary_no
                ) x ON x.diary_no = h.diary_no
                AND x.submaster_id IN ('239', '240', '241', '242', '243')
                JOIN cl_printed cl ON (cl.next_dt = h.next_dt
                                    AND cl.m_f = h.mainhead
                                    AND cl.part = h.clno
                                    AND cl.main_supp = h.main_supp_flag
                                    AND cl.roster_id = h.roster_id
                                    AND cl.display = 'Y'
                                    AND h.brd_slno BETWEEN cl.from_brd_no AND cl.to_brd_no)
                WHERE h.main_supp_flag IN (1, 2)
                AND h.judges != '0'
                AND h.judges != ''
                AND h.clno != 0
                AND h.brd_slno != 0
                AND cl.next_dt IS NOT NULL
                AND (h.bench_flag = '' OR h.bench_flag IS NULL)
                AND array_position(string_to_array(judges, ','), '$jcode') = 1
                AND (LENGTH(judges) - LENGTH(REPLACE(judges, ',', ''))) > 2
            ) temp
        ";

        // echo $sql;

        $query = $this->db->query($sql);

        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return [];
        }
    }

    function tweleve($jcode)
    {
        //Point 12(No. of Cases dealt with by His Lordship)

        $sql = "
            SELECT COUNT(DISTINCT diary_no) AS total
            FROM (
                SELECT h.diary_no, h.next_dt, h.judges
                FROM heardt h
                JOIN cl_printed cl ON (
                    cl.next_dt = h.next_dt
                    AND cl.m_f = h.mainhead
                    AND cl.part = h.clno
                    AND cl.main_supp = h.main_supp_flag
                    AND cl.roster_id = h.roster_id
                    AND cl.display = 'Y'
                    AND h.brd_slno BETWEEN cl.from_brd_no AND cl.to_brd_no
                )
                WHERE h.main_supp_flag IN (1, 2)
                AND h.judges != '0'
                AND h.judges != ''
                AND h.clno != 0
                AND h.brd_slno != 0
                AND cl.next_dt IS NOT NULL
                AND POSITION('$jcode' IN h.judges) > 0
                UNION
                SELECT h.diary_no, h.next_dt, h.judges
                FROM last_heardt h
                JOIN cl_printed cl ON (
                    cl.next_dt = h.next_dt
                    AND cl.m_f = h.mainhead
                    AND cl.part = h.clno
                    AND cl.main_supp = h.main_supp_flag
                    AND cl.roster_id = h.roster_id
                    AND cl.display = 'Y'
                    AND h.brd_slno BETWEEN cl.from_brd_no AND cl.to_brd_no
                )
                WHERE h.main_supp_flag IN (1, 2)
                AND h.judges != '0'
                AND h.judges != ''
                AND (h.bench_flag = '' OR h.bench_flag IS NULL)
                AND h.clno != 0
                AND h.brd_slno != 0
                AND cl.next_dt IS NOT NULL
                AND POSITION('$jcode' IN h.judges) > 0
            ) temp        
        ";

        // echo $sql;

        $query = $this->db->query($sql);

        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return [];
        }
    }


    function thirteen_total_disposed($jcode)
    {
        //Total disposal
        $sql = "
            SELECT count(distinct d.diary_no) AS total
            FROM dispose d
            JOIN main m ON d.diary_no = m.diary_no
            WHERE POSITION('$jcode' IN d.jud_id) > 0
            AND m.c_status = 'D'
        ";

        // echo $sql;

        $query = $this->db->query($sql);

        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return false;
        }
    }

    function thirteen_disposed_by_lordship($jcode)
    {
        //Disposed by his Lordship

        // $sql = "select count(distinct d.diary_no) as total from dispose d join main m
        //     on d.diary_no=m.diary_no where find_in_set($jcode,jud_id)
        //     and c_status='D' and dispjud=$jcode";

        $sql = "
            SELECT count(distinct d.diary_no) AS total 
            FROM dispose d 
            JOIN main m ON d.diary_no = m.diary_no 
            WHERE POSITION('$jcode' IN d.jud_id) > 0 
            AND m.c_status = 'D'
        ";
            
        // echo $sql;

        $query = $this->db->query($sql);

        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return [];
        }
    }


    function fourteen($jcode, $from_dt, $to_dt)
    {
        // Load the database connection (if not already loaded)
        $builder = $this->db->table('master.judge'); // 'judge' is the table name

        // Get the 'sur_name' column where 'jcode' equals the provided value
        $query = $builder->select('sur_name')
                        ->where('jcode', $jcode)
                        ->get();

        // Fetch the result as an array
        $result = $query->getRowArray(); // Assuming you want a single row result
        
        $surName = (!empty($result['sur_name'])) ? $result['sur_name'] : '';

        $sql = "
            SELECT COUNT(*) AS total
            FROM (
                SELECT 
                    o.diary_no AS diary_no,
                    o.orderdate::text AS dated, 
                    (
                        SELECT string_agg(jname, ',') 
                        FROM master.judge 
                        WHERE array_position(string_to_array(d1.jud_id::text, ','), jcode::text) > 0
                    ) AS bench, 
                    (
                        SELECT jname 
                        FROM master.judge 
                        WHERE array_position(string_to_array(o.perj::text, ','), jcode::text) > 0
                    ) AS judgmentBy 
                FROM ordernet o
                INNER JOIN master.roster_judge rj ON o.roster_id = rj.roster_id AND rj.display = 'Y'
                LEFT JOIN main m ON o.diary_no = m.diary_no
                LEFT JOIN dispose d1 ON m.diary_no = d1.diary_no
                WHERE rj.judge_id = '$jcode'
                AND o.orderdate BETWEEN '$from_dt' AND '$to_dt'
                AND o.pdfname != ''
                AND o.type = 'J'
                AND o.display = 'Y'
                UNION
                SELECT 
                    o.dn AS diary_no,
                    o.juddate AS dated, 
                    TRIM(BOTH ',' FROM concat(o.jud1, ',', o.jud2, ',', o.jud3, ',', o.jud4, ',', o.jud5)) AS bench,
                    '' AS judgmentBy
                FROM scordermain o
                LEFT JOIN main m ON o.dn = m.diary_no
                WHERE (o.jud1 = '$jcode' OR TRIM(BOTH ',' FROM concat(o.jud1, ',', o.jud2, ',', o.jud3, ',', o.jud4, ',', o.jud5)) LIKE '%$surName%')
                AND o.juddate BETWEEN '$from_dt' AND '$to_dt'
                AND o.dn != 0
            ) temp
        ";

        // echo $sql;

        $query = $this->db->query($sql);

        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return [];
        }
    }

    function get_filed_for_IDP_Reprot($condition)
    {
        $sql = "select count(*) as total_filed from main where date(diary_no_rec_date) $condition ";
        
        // $sql = "";
        // echo $sql;

        $query = $this->db->query($sql);

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return [];
        }
    }



    function get_notice_count($condition)
    {
        $sql = "select count(distinct diary_no ,cl_date) as total_notice from case_remarks_multiple where cl_date $condition and r_head in(3,62,181,182,183,184,203)";
        
        // $sql = "";
        // echo $sql;

        $query = $this->db->query($sql);

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return [];
        }
    }




    function fifteen($jcode, $from_dt, $to_dt)
    {

        // Load the database connection (if not already loaded)
        $builder = $this->db->table('master.judge'); // 'judge' is the table name

        // Get the 'sur_name' column where 'jcode' equals the provided value
        $query = $builder->select('sur_name')
                        ->where('jcode', $jcode)
                        ->get();

        // Fetch the result as an array
        $result = $query->getRowArray(); // Assuming you want a single row result
        
        $surName = (!empty($result['sur_name'])) ? $result['sur_name'] : '';

        //query for point 15
        
        $sql = "
            SELECT COUNT(DISTINCT diary_no) AS total
            FROM (
                SELECT diary_no, dated, bench, 
                    (LENGTH(bench) - LENGTH(REPLACE(bench, ',', '')) + 1) AS judge_count,
                    judgmentBy
                FROM (
                    SELECT 
                        o.diary_no AS diary_no,
                        o.orderdate::text AS dated, 
                        (
                            SELECT string_agg(jname, ',') 
                            FROM master.judge 
                            WHERE array_position(string_to_array(d1.jud_id::text, ','), jcode::text) > 0
                        ) AS bench, 
                        (
                            SELECT jname 
                            FROM master.judge 
                            WHERE array_position(string_to_array(o.perj::text, ','), jcode::text) > 0
                        ) AS judgmentBy
                    FROM ordernet o
                    INNER JOIN master.roster_judge rj 
                        ON o.roster_id = rj.roster_id 
                        AND rj.display = 'Y'
                    LEFT JOIN main m 
                        ON o.diary_no = m.diary_no
                    LEFT JOIN dispose d1 
                        ON m.diary_no = d1.diary_no
                    WHERE rj.judge_id = '$jcode'
                    AND o.orderdate BETWEEN '$from_dt' AND '$to_dt'
                    AND o.pdfname != ''
                    AND o.type = 'J'
                    AND o.display = 'Y'
                    UNION
                    SELECT 
                        o.dn AS diary_no,
                        o.juddate AS dated, 
                        TRIM(BOTH ',' FROM concat(o.jud1, ',', o.jud2, ',', o.jud3, ',', o.jud4, ',', o.jud5)) AS bench,
                        '' AS judgmentBy
                    FROM scordermain o
                    LEFT JOIN main m 
                        ON o.dn = m.diary_no
                    WHERE (o.jud1 = '$jcode' 
                        OR TRIM(BOTH ',' FROM concat(o.jud1, ',', o.jud2, ',', o.jud3, ',', o.jud4, ',', o.jud5)) LIKE '%Singh%')
                    AND o.juddate BETWEEN '$from_dt' AND '$to_dt'
                    AND o.dn != 0
                ) temp
            ) temp1
            WHERE judge_count > 3
            GROUP BY dated
            ORDER BY dated DESC
        ";

        // echo $sql;

        $query = $this->db->query($sql);

        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return [];
        }
    }
}
