<?php

namespace App\Models\Reports\ILDStats;

use CodeIgniter\Model;

class Report_IPDModel extends Model
{
    protected $eservicesdb;

    public function __construct()
    {
        parent::__construct();
        // $this->db_icmis = db_connect();
        $this->db = db_connect();
        $this->eservicesdb = \Config\Database::connect('eservices');
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
        return $query->result_array();
    }

    function getMainConnDisposal_matters($condition)
    {

        $sql = "  select h.board_type, case when (m.diary_no = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0') then 'Main' else 'Connected' end as main_connected,count(*) as total from dispose d
        inner join main m on d.diary_no=m.diary_no and m.c_status='D'
        and (d.ord_dt) $condition inner join heardt h on m.diary_no=h.diary_no where board_type = 'J' group by h.board_type,main_connected order by main_connected ";

        $query = $this->db->query($sql);
        if ($query->num_rows() >= 1)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }

    function get_registered_for_IDP_report($condition)
    {
        $sql = "select count(*) as total_registered from main where active_fil_no!='' and active_fil_no is not null and date(active_fil_dt)!='0000-00-00' and date(active_fil_dt) $condition ";

        $query = $this->db->query($sql);
        // echo   $this->db->last_query();exit(0);

        if ($query->num_rows() >= 1)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }


    function get_total_judgment($condition)
    {

        $sql = " select count(*) as total from ordernet where orderdate  $condition and type='J'";

        $query = $this->db->query($sql);
        if ($query->num_rows() >= 1)
        {
            return $query->result_array();
        }
        else
        {
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
        if ($query->num_rows() >= 1)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }

    function get_IA_Disposed($condition)
    {
        $sql="select count(*) as total from docdetails where date(dispose_date) $condition and doccode=8 and iastat='D'";
        $query = $this->db->query($sql);

        // echo $this->db->last_query();exit(0);
        if ($query->num_rows() >= 1)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }

    function get_SLP_Appeals_disposed($condition)
    {

        $sql = " select count(*) as total from dispose d inner join main m on d.diary_no=m.diary_no and m.c_status='D' and (d.ord_dt) $condition inner join heardt h on m.diary_no=h.diary_no where m.casetype_id in (1,2,3,4)";

        $query = $this->db->query($sql);
        if ($query->num_rows() >= 1)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }

    function get_Writ_Petitions_disposed($condition)
    {
        $sql = "select count(*) as total from dispose d inner join main m on d.diary_no=m.diary_no and m.c_status='D' and (d.ord_dt) $condition inner join heardt h on m.diary_no=h.diary_no where m.casetype_id in (5,6) ; ";

        $query = $this->db->query($sql);
        if ($query->num_rows() >= 1)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }

    function get_Transfer_Petitions_disposed($condition)
    {
        $sql = "select count(*) as total from dispose d inner join main m on d.diary_no=m.diary_no and m.c_status='D' and (d.ord_dt) $condition inner join heardt h on m.diary_no=h.diary_no where m.casetype_id in (7,8) ; ";

        $query = $this->db->query($sql);
        if ($query->num_rows() >= 1)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }

    function  get_total_filed($condition)
    {

        $sql = "select count(*) as total from main where date(diary_no_rec_date)  $condition  ";

        $query = $this->db->query($sql);
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    function get_filing_SLP_Appeals($condition)
    {
        $sql = "select count(DISTINCT diary_no) as total from main where date(diary_no_rec_date)  $condition
             and (casetype_id in (1,2,3,4) or active_casetype_id in (1,2,3,4)) ";

        $query = $this->db->query($sql);
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    function get_filing_MA($condition)
    {

        $sql = "select count(*) as total from main where date(diary_no_rec_date)  $condition
             and casetype_id=39 ";

        $query = $this->db->query($sql);
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    function get_filing_IA($condition)
    {
        $sql = "select count(*) as total from docdetails where date(ent_dt)  $condition
             and doccode=8 and display='Y' ";

        $query = $this->db->query($sql);
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }

    }

    //Code for new VC report

    function disposal_Vc_Stats($condition)
    {
        $sql="select count(case when mf_active='M' or mf_active='' then 1 end) as m_total,count(case when mf_active='F' then 1 end ) as r_total from(select distinct mf_active,m.diary_no from dispose d inner join main m on d.diary_no=m.diary_no where m.c_status='D' and date(d.ord_dt) $condition) a";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1)
        {
            return $query->getResultArray();
        }
        else
        {
            return false;
        }
    }

    function Listed_Vc_Stats($condition)
    {
        $sql = "
        SELECT count(CASE WHEN mainhead='M' OR mainhead='' THEN 1 END) AS m_total,
               count(CASE WHEN mainhead='F' OR mainhead='' THEN 1 END) AS r_total
        FROM (
            SELECT mainhead, diary_no 
            FROM (
                SELECT h.diary_no, h.mainhead, h.next_dt, h.brd_slno, h.clno, h.roster_id
                FROM (
                    SELECT h.diary_no, h.mainhead, h.next_dt, h.brd_slno, h.clno, h.roster_id
                    FROM main m
                    INNER JOIN heardt h ON m.diary_no = h.diary_no
                    WHERE h.next_dt $condition AND h.main_supp_flag IN (1, 2)
                    UNION
                    SELECT h.diary_no, h.mainhead, h.next_dt, h.brd_slno, h.clno, h.roster_id
                    FROM main m
                    INNER JOIN last_heardt h ON m.diary_no = h.diary_no
                    WHERE h.next_dt $condition
                      AND h.main_supp_flag IN (1, 2)
                      AND (h.bench_flag = '' OR h.bench_flag IS NULL) -- and c_status = 'P'
                ) h
                LEFT JOIN cl_printed p ON p.next_dt = h.next_dt 
                AND p.m_f = h.mainhead 
                AND p.part = h.clno 
                AND p.roster_id = h.roster_id 
                AND p.display = 'Y'
                WHERE p.next_dt IS NOT NULL
                GROUP BY h.diary_no, h.mainhead, h.next_dt, h.brd_slno, h.clno, h.roster_id
            ) a
        ) b";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1)
        {
            return $query->getResultArray();
        }
        else
        {
            return false;
        }
    }

    function bench_Vc_Stats($condition)
    {

        $sql = "select count(*) total from (select *
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
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    function Filed_Vc_Stats($condition)
    {
        $sql="select count(*) as total from main where date(diary_no_rec_date) $condition";

        $query = $this->db->query($sql);
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    function efiled_matters($condition)
    {
        $sql="select sum(case when app_flag like 'filing%' and status_id=1  then 1 else 0 end ) as total from efiling_transaction_records where date(transaction_datetime) $condition";

        $query = $this->db_eFiling->query($sql);
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    function judge_report($judge_code)
    {
        $condition = "1=1";

        if($judge_code!=1)
        {
            $condition="coram like '$judge_code%'";
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

        if ($query->num_rows() >= 1) {
            return $query->result_array();
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
        $ucode=$this->session->userdata('dcmis_user_idd');
        $this->db->SELECT("jcode");
        $this->db->FROM('users');
        $this->db->WHERE('usercode', $ucode);

        $query = $this->db->get();

        return $query->result();
    }


    //Section of Judges Report
    function get_judges_list()
    {
        $this->db->SELECT("*");
        $this->db->FROM('judge');
        //$this->db->WHERE('is_retired', 'N');
        $this->db->where('jtype','J');
        $this->db->where('display','Y');
        $this->db->ORDER_BY("is_retired", "asc");
        $this->db->ORDER_BY("jcode", "asc");
        $query = $this->db->get();

        return $query->result();
    }

    //Section of Judges Report
    function get_judges_list_current()
    {
        $this->db->SELECT("*");
        $this->db->FROM('judge');
        $this->db->WHERE('is_retired', 'N');
        $this->db->where('jtype','J');
        $this->db->where('display','Y');
        $this->db->ORDER_BY("jcode", "asc");
        $query = $this->db->get();
        return $query->result();
    }

    function get_judges_DOA_AOR($jcode)
    {
        $this->db->SELECT("*");
        $this->db->FROM('judge');
        $this->db->where('jtype','J');
        $this->db->WHERE('jcode', $jcode);

        $query = $this->db->get();
        return $query->result();
    }

    function single_bench($jcode)
    {
        $sql="select  count(distinct next_dt) as total from(select diary_no,h.next_dt,judges,(CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) ) as TotalValue
           from heardt h JOIN cl_printed cl ON (cl.next_dt = h.next_dt
        AND cl.m_f = h.mainhead
        AND cl.part = h.clno
        AND cl.main_supp = h.main_supp_flag
        AND cl.roster_id = h.roster_id
        AND cl.display = 'Y' and h.brd_slno between cl.from_brd_no and cl.to_brd_no) where  h.main_supp_flag in(1,2)
        and h.judges!=0 and h.judges !='' and h.clno
        !=0 and h.brd_slno !=0 and cl.next_dt IS NOT NULL and (CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) )=0 and judges=$jcode
        union
        select diary_no,h.next_dt,judges,(CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) ) as TotalValue
           from last_heardt h JOIN cl_printed cl ON (cl.next_dt = h.next_dt
        AND cl.m_f = h.mainhead
        AND cl.part = h.clno
        AND cl.main_supp = h.main_supp_flag
        AND cl.roster_id = h.roster_id
        AND cl.display = 'Y' and h.brd_slno between cl.from_brd_no and cl.to_brd_no) where  h.main_supp_flag in(1,2)
        and h.judges!=0 and h.judges !='' and (h.bench_flag='' or h.bench_flag is null) and h.clno!=0 and h.brd_slno !=0 and cl.next_dt IS NOT NULL and (CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) )=0 and judges=$jcode) temp;";

        $query = $this->db->query($sql);
        // echo   $this->db->last_query();exit(0);

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    function division_bench($jcode)
    {
        $sql= "select  count(distinct next_dt,judges) as total from(select diary_no,h.next_dt,judges,(CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) ) as TotalValue
           from heardt h JOIN cl_printed cl ON (cl.next_dt = h.next_dt
        AND cl.m_f = h.mainhead
        AND cl.part = h.clno
        AND cl.main_supp = h.main_supp_flag
        AND cl.roster_id = h.roster_id
        AND cl.display = 'Y' and h.brd_slno between cl.from_brd_no and cl.to_brd_no) where  h.main_supp_flag in(1,2)
        and h.judges!=0 and h.judges !='' and h.clno
        !=0 and h.brd_slno !=0 and cl.next_dt IS NOT NULL and (CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) )=1 and find_in_set('$jcode',judges)=2
        union
        select diary_no,h.next_dt,judges,(CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) ) as TotalValue
           from last_heardt h JOIN cl_printed cl ON (cl.next_dt = h.next_dt
        AND cl.m_f = h.mainhead
        AND cl.part = h.clno
        AND cl.main_supp = h.main_supp_flag
        AND cl.roster_id = h.roster_id
        AND cl.display = 'Y' and h.brd_slno between cl.from_brd_no and cl.to_brd_no) where  h.main_supp_flag in(1,2)
        and h.judges!=0 and h.judges !='' and (h.bench_flag='' or h.bench_flag is null) and h.clno!=0 and h.brd_slno !=0 and cl.next_dt IS NOT NULL and (CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) )=1
        and find_in_set('$jcode',judges)=2
        ) temp";


        $query = $this->db->query($sql);
        // echo   $this->db->last_query();exit(0);

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }


    function five($jcode)
    {
        // Point 5(No. of Division Bench Held in which His Lordship was presiding over the bench)

        $sql="select  count(distinct next_dt,judges) as total from(select diary_no,h.next_dt,judges,(CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) ) as TotalValue
           from heardt h JOIN cl_printed cl ON (cl.next_dt = h.next_dt
        AND cl.m_f = h.mainhead
        AND cl.part = h.clno
        AND cl.main_supp = h.main_supp_flag
        AND cl.roster_id = h.roster_id
        AND cl.display = 'Y' and h.brd_slno between cl.from_brd_no and cl.to_brd_no) where  h.main_supp_flag in(1,2)
        and h.judges!=0 and h.judges !='' and h.clno
        !=0 and h.brd_slno !=0 and cl.next_dt IS NOT NULL and (CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) )=1 and find_in_set('$jcode',judges)=1
        union
        select diary_no,h.next_dt,judges,(CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) ) as TotalValue
           from last_heardt h JOIN cl_printed cl ON (cl.next_dt = h.next_dt
        AND cl.m_f = h.mainhead
        AND cl.part = h.clno
        AND cl.main_supp = h.main_supp_flag
        AND cl.roster_id = h.roster_id
        AND cl.display = 'Y' and h.brd_slno between cl.from_brd_no and cl.to_brd_no) where  h.main_supp_flag in(1,2)
        and h.judges!=0 and h.judges !='' and (h.bench_flag='' or h.bench_flag is null) and h.clno!=0 and h.brd_slno !=0 and cl.next_dt IS NOT NULL and (CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) )=1
        and find_in_set('$jcode',judges)=1
        ) temp";


        $query = $this->db->query($sql);
        // echo   $this->db->last_query();exit(0);

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }


    function six($jcode)
    {
        //Point 6(No. of three Judge Bench held in which His Lordship was not presiding over the bench)

        $sql="select  count(distinct next_dt,judges) as total from(select diary_no,h.next_dt,judges,(CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) ) as TotalValue
           from heardt h JOIN cl_printed cl ON (cl.next_dt = h.next_dt
        AND cl.m_f = h.mainhead
        AND cl.part = h.clno
        AND cl.main_supp = h.main_supp_flag
        AND cl.roster_id = h.roster_id
        AND cl.display = 'Y' and h.brd_slno between cl.from_brd_no and cl.to_brd_no) where  h.main_supp_flag in(1,2)
        and h.judges!=0 and h.judges !='' and h.clno
        !=0 and h.brd_slno !=0 and cl.next_dt IS NOT NULL and (CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) )=2 and (find_in_set($jcode,judges)=2 or find_in_set($jcode,judges)=3)
        union
        select diary_no,h.next_dt,judges,(CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) ) as TotalValue
           from last_heardt h JOIN cl_printed cl ON (cl.next_dt = h.next_dt
        AND cl.m_f = h.mainhead
        AND cl.part = h.clno
        AND cl.main_supp = h.main_supp_flag
        AND cl.roster_id = h.roster_id
        AND cl.display = 'Y' and h.brd_slno between cl.from_brd_no and cl.to_brd_no) where  h.main_supp_flag in(1,2)
        and h.judges!=0 and h.judges !='' and (h.bench_flag='' or h.bench_flag is null) and h.clno!=0 and h.brd_slno !=0 and cl.next_dt IS NOT NULL and (CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) )=2
        and (find_in_set($jcode,judges)=2 or find_in_set($jcode,judges)=3)
        ) temp";

        $query = $this->db->query($sql);
        // echo   $this->db->last_query();exit(0);

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    function seven($jcode)
    {
        //  Point 7(No. of three Judge Bench held in which His Lordship was presiding over the bench)
        $sql="
        select count( distinct next_dt,judges) as total from(select diary_no,h.next_dt,judges,(CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) ) as TotalValue
           from heardt h JOIN cl_printed cl ON (cl.next_dt = h.next_dt
        AND cl.m_f = h.mainhead
        AND cl.part = h.clno
        AND cl.main_supp = h.main_supp_flag
        AND cl.roster_id = h.roster_id
        AND cl.display = 'Y' and h.brd_slno between cl.from_brd_no and cl.to_brd_no) where  h.main_supp_flag in(1,2)
        and h.judges!=0 and h.judges !='' and h.clno
        !=0 and h.brd_slno !=0 and cl.next_dt IS NOT NULL and (CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) )=2 and (find_in_set($jcode,judges)=1 )
        union
        select diary_no,h.next_dt,judges,(CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) ) as TotalValue
           from last_heardt h JOIN cl_printed cl ON (cl.next_dt = h.next_dt
        AND cl.m_f = h.mainhead
        AND cl.part = h.clno
        AND cl.main_supp = h.main_supp_flag
        AND cl.roster_id = h.roster_id
        AND cl.display = 'Y' and h.brd_slno between cl.from_brd_no and cl.to_brd_no) where  h.main_supp_flag in(1,2)
        and h.judges!=0 and h.judges !='' and (h.bench_flag='' or h.bench_flag is null) and h.clno!=0 and h.brd_slno !=0 and cl.next_dt IS NOT NULL and (CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) )=2
        and (find_in_set($jcode,judges)=1)
        ) temp";

        $query = $this->db->query($sql);
        // echo   $this->db->last_query();exit(0);

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    function eight($jcode)
    {
        //Point 8(No. of three Judge Constitution Bench held in which His Lordship was not presiding over the bench)


        $sql="select count( distinct next_dt,judges) as total from(select h.diary_no,h.next_dt,judges,(CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) ) as TotalValue
           from heardt h join mul_category m on h.diary_no=m.diary_no and m.display='Y' JOIN cl_printed cl ON (cl.next_dt = h.next_dt
        AND cl.m_f = h.mainhead
        AND cl.part = h.clno
        AND cl.main_supp = h.main_supp_flag
        AND cl.roster_id = h.roster_id
        AND cl.display = 'Y' and h.brd_slno between cl.from_brd_no and cl.to_brd_no) where  h.main_supp_flag in(1,2)
        and h.judges!=0 and h.judges !='' and h.clno
        !=0 and h.brd_slno !=0 and cl.next_dt IS NOT NULL and (CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) )=2 and (find_in_set($jcode,judges)=2 or find_in_set($jcode,judges)=3 ) and submaster_id=239
        union
        select h.diary_no,h.next_dt,judges,(CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) ) as TotalValue
           from last_heardt h join mul_category m on h.diary_no=m.diary_no and m.display='Y' JOIN cl_printed cl ON (cl.next_dt = h.next_dt
        AND cl.m_f = h.mainhead
        AND cl.part = h.clno
        AND cl.main_supp = h.main_supp_flag
        AND cl.roster_id = h.roster_id
        AND cl.display = 'Y' and h.brd_slno between cl.from_brd_no and cl.to_brd_no) where  h.main_supp_flag in(1,2)
        and h.judges!=0 and h.judges !='' and (h.bench_flag='' or h.bench_flag is null) and h.clno!=0 and h.brd_slno !=0 and cl.next_dt IS NOT NULL and (CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) )=2
        and (find_in_set($jcode,judges)=2 or find_in_set($jcode,judges)=3) and submaster_id=239
        ) temp";

        $query = $this->db->query($sql);
        // echo   $this->db->last_query();exit(0);

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    function nine($jcode)
    {

        //Point 9(No. of three Judge Constitution Bench held in which His Lordship was presiding over the bench)
        $sql="
        select  count(distinct next_dt,judges) as total from(select h.diary_no,h.next_dt,judges,(CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) ) as TotalValue
           from heardt h join mul_category m on h.diary_no=m.diary_no and m.display='Y' JOIN cl_printed cl ON (cl.next_dt = h.next_dt
        AND cl.m_f = h.mainhead
        AND cl.part = h.clno
        AND cl.main_supp = h.main_supp_flag
        AND cl.roster_id = h.roster_id
        AND cl.display = 'Y' and h.brd_slno between cl.from_brd_no and cl.to_brd_no) where  h.main_supp_flag in(1,2)
        and h.judges!=0 and h.judges !='' and h.clno
        !=0 and h.brd_slno !=0 and cl.next_dt IS NOT NULL and (CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) )=2 and (find_in_set($jcode,judges)=1 ) and submaster_id=239
        union
        select h.diary_no,h.next_dt,judges,(CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) ) as TotalValue
           from last_heardt h join mul_category m on h.diary_no=m.diary_no and m.display='Y' JOIN cl_printed cl ON (cl.next_dt = h.next_dt
        AND cl.m_f = h.mainhead
        AND cl.part = h.clno
        AND cl.main_supp = h.main_supp_flag
        AND cl.roster_id = h.roster_id
        AND cl.display = 'Y' and h.brd_slno between cl.from_brd_no and cl.to_brd_no) where  h.main_supp_flag in(1,2)
        and h.judges!=0 and h.judges !='' and (h.bench_flag='' or h.bench_flag is null) and h.clno!=0 and h.brd_slno !=0 and cl.next_dt IS NOT NULL and (CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) )=2
        and (find_in_set($jcode,judges)=1) and submaster_id=239
        ) temp";

        $query = $this->db->query($sql);
        // echo   $this->db->last_query();exit(0);

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    function ten($jcode)
    {
        //Point 10(No. of Constitution Bench held in which His Lordship was not presiding over the bench)

        $sql="select count( distinct next_dt,judges) as total  from(select h.diary_no,h.next_dt,judges,(CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) ) as TotalValue,submaster_id
           from heardt h join (select group_concat(submaster_id) as submaster_id,diary_no from mul_category where display='Y' group by diary_no ) x on x.diary_no=h.diary_no and x.submaster_id in(239,240,241,242,243) JOIN cl_printed cl ON (cl.next_dt = h.next_dt
        AND cl.m_f = h.mainhead
        AND cl.part = h.clno
        AND cl.main_supp = h.main_supp_flag
        AND cl.roster_id = h.roster_id
        AND cl.display = 'Y' and h.brd_slno between cl.from_brd_no and cl.to_brd_no) where  h.main_supp_flag in(1,2)
        and h.judges!=0 and h.judges !='' and h.clno
        !=0 and h.brd_slno !=0 and cl.next_dt IS NOT NULL  and (find_in_set($jcode,judges) =2 or find_in_set($jcode,judges) =3
        or find_in_set($jcode,judges) =4 or find_in_set($jcode,judges) =5 or find_in_set($jcode,judges) =6 or find_in_set($jcode,judges) =7 or
        find_in_set($jcode,judges) =8 or find_in_set($jcode,judges) =9 or find_in_set($jcode,judges) =10 or find_in_set($jcode,judges) =11) and
        (CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) )  >2
        union
        select h.diary_no,h.next_dt,judges,(CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) ) as TotalValue,submaster_id
           from last_heardt h join (select group_concat(submaster_id) as submaster_id,diary_no from mul_category where display='Y' group by diary_no ) x on x.diary_no=h.diary_no and x.submaster_id in(239,240,241,242,243) JOIN cl_printed cl ON (cl.next_dt = h.next_dt
        AND cl.m_f = h.mainhead
        AND cl.part = h.clno
        AND cl.main_supp = h.main_supp_flag
        AND cl.roster_id = h.roster_id
        AND cl.display = 'Y' and h.brd_slno between cl.from_brd_no and cl.to_brd_no) where  h.main_supp_flag in(1,2)
        and h.judges!=0 and h.judges !='' and h.clno
        !=0 and h.brd_slno !=0 and cl.next_dt IS NOT NULL  and (h.bench_flag='' or h.bench_flag is null) and (find_in_set($jcode,judges) =2
         or find_in_set($jcode,judges) =3 or find_in_set($jcode,judges) =4 or find_in_set($jcode,judges) =5 or find_in_set($jcode,judges) =6 or
          find_in_set($jcode,judges) =7 or find_in_set($jcode,judges) =8 or find_in_set($jcode,judges) =9 or find_in_set($jcode,judges) =10 or
          find_in_set($jcode,judges) =11) and (CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) )  >2
        ) temp";


        $query = $this->db->query($sql);
        // echo   $this->db->last_query();exit(0);

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    function eleven($jcode)
    {
        //Point 11(No. of Constitution Bench held in which His Lordship was presiding over the bench)
        $sql="select  count(distinct next_dt,judges) as total from(select h.diary_no,h.next_dt,judges,(CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) ) as TotalValue,submaster_id
           from heardt h join (select group_concat(submaster_id) as submaster_id,diary_no from mul_category where display='Y' group by diary_no ) x on x.diary_no=h.diary_no and x.submaster_id in(239,240,241,242,243) JOIN cl_printed cl ON (cl.next_dt = h.next_dt
        AND cl.m_f = h.mainhead
        AND cl.part = h.clno
        AND cl.main_supp = h.main_supp_flag
        AND cl.roster_id = h.roster_id
        AND cl.display = 'Y' and h.brd_slno between cl.from_brd_no and cl.to_brd_no) where  h.main_supp_flag in(1,2)
        and h.judges!=0 and h.judges !='' and h.clno
        !=0 and h.brd_slno !=0 and cl.next_dt IS NOT NULL  and (find_in_set($jcode,judges) =1) and (CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) )  >2
        union
        select h.diary_no,h.next_dt,judges,(CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) ) as TotalValue,submaster_id
           from last_heardt h join (select group_concat(submaster_id) as submaster_id,diary_no from mul_category where display='Y' group by diary_no ) x on x.diary_no=h.diary_no and x.submaster_id in(239,240,241,242,243) JOIN cl_printed cl ON (cl.next_dt = h.next_dt
        AND cl.m_f = h.mainhead
        AND cl.part = h.clno
        AND cl.main_supp = h.main_supp_flag
        AND cl.roster_id = h.roster_id
        AND cl.display = 'Y' and h.brd_slno between cl.from_brd_no and cl.to_brd_no) where  h.main_supp_flag in(1,2)
        and h.judges!=0 and h.judges !='' and h.clno
        !=0 and h.brd_slno !=0 and cl.next_dt IS NOT NULL  and (h.bench_flag='' or h.bench_flag is null) and (find_in_set($jcode,judges) =1) and (CHAR_LENGTH(judges) -
        CHAR_LENGTH(REPLACE(judges, ',', '')) )  >2
        ) temp";

        $query = $this->db->query($sql);
        // echo   $this->db->last_query();exit(0);

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    function tweleve($jcode)
    {
        //Point 12(No. of Cases dealt with by His Lordship)
        $sql="select  count(distinct diary_no) as total from(select h.diary_no,h.next_dt,judges
           from heardt h  JOIN cl_printed cl ON (cl.next_dt = h.next_dt
        AND cl.m_f = h.mainhead
        AND cl.part = h.clno
        AND cl.main_supp = h.main_supp_flag
        AND cl.roster_id = h.roster_id
        AND cl.display = 'Y' and h.brd_slno between cl.from_brd_no and cl.to_brd_no) where  h.main_supp_flag in(1,2)
        and h.judges!=0 and h.judges !='' and h.clno
        !=0 and h.brd_slno !=0 and cl.next_dt IS NOT NULL  and (find_in_set($jcode,judges) )
        union
        select h.diary_no,h.next_dt,judges
           from last_heardt h  JOIN cl_printed cl ON (cl.next_dt = h.next_dt
        AND cl.m_f = h.mainhead
        AND cl.part = h.clno
        AND cl.main_supp = h.main_supp_flag
        AND cl.roster_id = h.roster_id
        AND cl.display = 'Y' and h.brd_slno between cl.from_brd_no and cl.to_brd_no) where  h.main_supp_flag in(1,2)
        and h.judges!=0 and h.judges !='' and (h.bench_flag='' or h.bench_flag is null) and h.clno!=0 and h.brd_slno !=0 and cl.next_dt IS NOT NULL
        and (find_in_set($jcode,judges) )
        ) temp";

        $query = $this->db->query($sql);
        // echo   $this->db->last_query();exit(0);

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }


    function thirteen_total_disposed($jcode)
    {
        //Total disposal

        $sql="select count(distinct d.diary_no) as total from dispose d join main m on d.diary_no=m.diary_no where find_in_set($jcode,jud_id) and c_status='D'";

        $query = $this->db->query($sql);
        // echo   $this->db->last_query();exit(0);

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    function thirteen_disposed_by_lordship($jcode)
    {
        //Disposed by his Lordship

        $sql="select count(distinct d.diary_no) as total from dispose d join main m on d.diary_no=m.diary_no where find_in_set($jcode,jud_id) and c_status='D' and dispjud=$jcode";

        $query = $this->db->query($sql);
        // echo   $this->db->last_query();exit(0);

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }


    function fourteen($jcode,$from_dt,$to_dt)
    {
        $sql1="  select sur_name from judge where jcode=$jcode";
        $query = $this->db->query($sql1);
        $name= $query->result();
        $surname=$name[0]->sur_name;

        $sql="select count(*) as total from (SELECT o.diary_no diary_no,o.orderdate dated,
             (select group_concat(jname separator ',') from judge where find_in_set(jcode,d1.jud_id)) as bench,
            (select jname from judge where find_in_set(jcode,o.perj)) as judgmentBy
            FROM ordernet o INNER JOIN roster_judge rj ON (o.roster_id=rj.roster_id AND rj.display='Y')
            LEFT JOIN main m ON o.diary_no = m.diary_no
             left join dispose d1 on m.diary_no=d1.diary_no
            WHERE (rj.judge_id = $jcode) AND orderdate BETWEEN '$from_dt' and '$to_dt' AND pdfname!='' AND o.type='J' and o.display='Y'
            union
            SELECT o.dn diary_no,o.juddate dated,
            TRIM(BOTH ',' FROM (concat(jud1,',',jud2,',',jud3,',',jud4,',',jud5))) as bench, '' as judgmentBy
            FROM scordermain o LEFT JOIN main m ON o.dn = m.diary_no
            WHERE (o.jud1 = $jcode or TRIM(BOTH ',' FROM (concat(jud1,',',jud2,',',jud3,',',jud4,',',jud5))) LIKE '%$surname%')
            and o.juddate BETWEEN '$from_dt' and '$to_dt' and o.dn!=0
            order by dated desc) temp";


        $query = $this->db->query($sql);
        // echo   $this->db->last_query();exit(0);

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    function get_filed_for_IDP_Reprot($condition)
    {
        $sql = "select count(*) as total_filed from main where date(diary_no_rec_date) $condition ";

        $query = $this->db->query($sql);

        if ($query->getNumRows() >= 1)
        {
            return $query->getResultArray();
        }
        else
        {
            return false;
        }
    }

    function get_notice_count($condition)
    {
        $sql="select count(distinct diary_no ,cl_date) as total_notice from case_remarks_multiple where cl_date $condition and r_head in(3,62,181,182,183,184,203)";
        $query = $this->db->query($sql);
        // echo   $this->db->last_query();exit(0);

        if ($query->num_rows() >= 1)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }




    function fifteen($jcode,$from_dt,$to_dt)
    {

        $sql1="  select sur_name from judge where jcode=$jcode";
        $query = $this->db->query($sql1);

        $name= $query->result();

        $surname=$name[0]->sur_name;

        //query for point 15
        $sql="select count(distinct diary_no) as total from(select diary_no,dated,bench,(CHAR_LENGTH(bench) -CHAR_LENGTH(REPLACE(bench, ',', '')) + 1) as judge_count,judgmentBy from(SELECT o.diary_no diary_no,o.orderdate dated,
             (select group_concat(jname separator ',') from judge where find_in_set(jcode,d1.jud_id)) as bench,
            (select jname from judge where find_in_set(jcode,o.perj)) as judgmentBy
            FROM ordernet o INNER JOIN roster_judge rj ON (o.roster_id=rj.roster_id AND rj.display='Y') LEFT JOIN main m ON o.diary_no = m.diary_no
             left join dispose d1 on m.diary_no=d1.diary_no
            WHERE (rj.judge_id = $jcode) AND orderdate BETWEEN '$from_dt' and '$to_dt'
            AND pdfname!='' AND o.type='J' and o.display='Y'
            union
            SELECT o.dn diary_no,o.juddate dated,
            TRIM(BOTH ',' FROM (concat(jud1,',',jud2,',',jud3,',',jud4,',',jud5))) as bench, '' as judgmentBy
            FROM scordermain o LEFT JOIN main m ON o.dn = m.diary_no
            WHERE (o.jud1 = $jcode or TRIM(BOTH ',' FROM (concat(jud1,',',jud2,',',jud3,',',jud4,',',jud5))) LIKE '%$surname%')
            and o.juddate BETWEEN '$from_dt' and '$to_dt' and o.dn!=0) temp) temp1 where judge_count>3
            order by dated desc";

        $query = $this->db->query($sql);
        //echo   $this->db->last_query();exit(0);

        if ($query->num_rows() >= 1)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }
}
