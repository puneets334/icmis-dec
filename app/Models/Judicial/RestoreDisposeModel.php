<?php

namespace App\Models\Judicial;

use CodeIgniter\Model;

class RestoreDisposeModel extends Model
{

    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    function case_types()
    {
        // $query = $this->db->where('is_deleted', 'f')->where('casecode!=9999')->order_by('casecode', 'ASC')->get('master.casetype');
        $builder2 = $this->db->table("master.casetype");
        $builder2->select("*");
        $builder2->where('casecode !=', '9999');
        $builder2->where('is_deleted', 'f');
        $builder2->orderBy('casecode', 'ASC');
        $query2 = $builder2->get();
        $result = $query2->getResultArray();
        return $result;
    }


    function user_details($usercode)
    {
        //    $sql="select section,usertype from users where usercode=$usercode";
        //    $query=$this->db->query($sql);
        $builder2 = $this->db->table("master.users");
        $builder2->select("section,usertype");
        $builder2->where('usercode', $usercode);
        $query2 = $builder2->get();
        $result = $query2->getResultArray();
        return $result;
    }

    function get_case_details($case_type = 0, $case_number = 0, $case_year = 0, $diary_number = 0, $diary_year = 0)
    {
        $buildQuery = [];
        if (($case_type != 0 && $case_type != '') && ($case_number != 0 && $case_number != '') && ($case_year !== 0 && $case_year != '')) {
           
            $query = $this->db->table('main_a m');
            $query->select("
                m.active_fil_no, 
                m.diary_no as case_diary, 
                section, 
                STRING_AGG(CONCAT(dd.docd_id, '#', dd.docnum, '/', dd.docyear), ', ') as ia, 
                c_status, 
                fil_dt, 
                m.fil_no, 
                CONCAT(pet_name, ' Vs ', res_name) as case_title, 
                TO_CHAR(ord_dt, 'DD-MM-YYYY') as disp_date
            ");
            $query->join('dispose d', 'd.diary_no = m.diary_no', 'left');
            $query->join('master.users u', 'm.dacode = u.usercode', 'left');
            $query->join(
                "docdetails dd",
                "m.diary_no = dd.diary_no 
                 AND (DATE(d.ord_dt) = DATE(dd.lst_mdf) OR DATE(d.ord_dt) = DATE(dd.dispose_date)) 
                 AND dd.display = 'Y' 
                 AND dd.iastat = 'D'",
                'left'
            );
            
            $query->where("CAST(SPLIT_PART(NULLIF(m.active_fil_no, ''), '-', 1) AS INTEGER) = $case_type");
            $query->where("CAST(m.active_reg_year AS INTEGER) = $case_year");
            
            $query->groupStart()
                ->where("CAST(SPLIT_PART(NULLIF(m.active_fil_no, ''), '-', 2) AS INTEGER) = $case_number", NULL, FALSE)
                ->orWhere("
                    $case_number BETWEEN 
                    CAST(SPLIT_PART(NULLIF(m.active_fil_no, ''), '-', 2) AS INTEGER) 
                    AND CAST(SPLIT_PART(NULLIF(m.active_fil_no, ''), '-', -1) AS INTEGER)
                ", NULL, FALSE)
                ->orWhere("CAST(SPLIT_PART(NULLIF(m.active_fil_no, ''), '-', 2) AS INTEGER) IS NULL", NULL, FALSE)
                ->orWhere("CAST(SPLIT_PART(NULLIF(m.active_fil_no, ''), '-', -1) AS INTEGER) IS NULL", NULL, FALSE)
                ->groupEnd();
            
            $query->groupBy("m.diary_no, section, c_status, fil_dt, m.fil_no, CONCAT(pet_name, ' Vs ', res_name), TO_CHAR(ord_dt, 'DD-MM-YYYY')");
            
            //  $compiledQuery = $query->getCompiledSelect();

            // echo $compiledQuery; // Print the full SQL query
            // die(); // Stop further execution to focus on the query
            $result = $query->get();
            $buildQuery =  $result->getResultArray();
        } else if (($diary_number != 0 && $diary_number != '') && ($diary_year != 0 && $diary_year != '')) {
            
            $builder = $this->db->table('main_a m');
            $builder->select("m.diary_no AS case_diary, section, STRING_AGG(CONCAT(dd.docd_id, '#', dd.docnum, '/', dd.docyear), ', ') AS ia, c_status, fil_dt, m.fil_no, CONCAT(pet_name, ' Vs ', res_name) AS case_title, TO_CHAR(ord_dt, 'DD-MM-YYYY') AS disp_date");
            $builder->join('dispose d', 'd.diary_no = m.diary_no', 'left');
            $builder->join('master.users u', 'm.dacode = u.usercode', 'left');
            $builder->join('docdetails dd', "m.diary_no = dd.diary_no AND (DATE(d.ord_dt) = DATE(dd.lst_mdf) OR DATE(d.ord_dt) = DATE(dd.dispose_date)) AND dd.display = 'Y' AND dd.iastat = 'D'", 'left');
            $builder->where('m.diary_no', $diary_number . $diary_year);
            $builder->groupBy("m.diary_no, section, c_status, fil_dt, m.fil_no, CONCAT(pet_name, ' Vs ', res_name), TO_CHAR(ord_dt, 'DD-MM-YYYY')");
            // $queryString = $builder->getCompiledSelect();
            // echo $queryString;
            // exit();
            $query = $builder->get();
            $buildQuery =  $query->getResultArray();
            // echo "<pre>"; print_r($buildQuery); die;
        }

        if (count($buildQuery) >= 1) {
            return $buildQuery;
        } else {
            return [];
        }
    }

    

    function get_ma_details($diarynumber, $fil_dt, $fil_no)
    {
        $d_year = substr($diarynumber, -4);
        $d_num = substr($diarynumber, 0, strlen($diarynumber) - 4);

        //echo $fil_no;
        $queryresult = [];
        if ($fil_dt == '0000-00-00 00:00:00' || $fil_dt == '' || (strpos($fil_no, '13-') != false && strpos($fil_no, '14-') != false)) {
            // $condition= " where lct_caseno=".$d_num ." AND lct_caseyear=".$d_year ." AND  ct_code=4 AND lw_display='Y' AND is_order_challenged='Y'";
            // $sql="select concat(pet_name,' Vs ',res_name) as ma_title,c_status as ma_status,date_format(h.next_dt,'%d-%m-%Y') as restore_date,m.diary_no as ma_diary,
            // concat(short_description,' No. ',substring(m.active_fil_no,4,12),'/',active_reg_year) as ma_no
            // from main m left join casetype ct on m.active_casetype_id=ct.casecode
            // left join heardt h on h.diary_no=m.diary_no where
            // m.diary_no in (select distinct lt.diary_no from lowerct lt left join main m on lt.lct_caseyear=m.active_reg_year
            // and lt.lct_casetype=m.active_casetype_id and
            // (lt.lct_caseno= cast(substring(m.active_fil_no,4,6) as unsigned ) or lt.lct_caseno between cast(substring(m.active_fil_no,4,6) as unsigned ) and cast(substring(m.active_fil_no,11,6) as unsigned ))
            // inner join docdetails dd on dd.diary_no=lt.diary_no AND doccode=8 and (doccode1=101 or doccode1=154 or doccode1=240 or doccode1=111 or doccode1=223 or doccode1=383 or doccode1=371) where lct_caseno=".$d_num ." AND lct_caseyear=".$d_year ." AND  ct_code=4 AND lw_display='Y' AND is_order_challenged='Y') ";


            // $builder = $this->db->table('main AS m');
            // $builder->select("CONCAT(m.pet_name, ' Vs ', m.res_name) AS ma_title, 
            //       m.c_status AS ma_status, 
            //       TO_CHAR(h.next_dt, 'DD-MM-YYYY') AS restore_date, 
            //       m.diary_no AS ma_diary, 
            //       CONCAT(m.short_description, ' No. ', SUBSTRING(m.active_fil_no FROM 4 FOR 12), '/', m.active_reg_year) AS ma_no");
            // $builder->join('casetype AS ct', 'm.active_casetype_id = ct.casecode', 'LEFT');
            // $builder->join('heardt AS h', 'h.diary_no = m.diary_no', 'LEFT');

            // $builder->whereIn('m.diary_no', function ($subquery) {
            //     $subquery->select('DISTINCT lt.diary_no')
            //         ->from('lowerct AS lt')
            //         ->join('main AS m2', 'lt.lct_caseyear = m2.active_reg_year 
            //                         AND lt.lct_casetype = m2.active_casetype_id 
            //                         AND (
            //                             lt.lct_caseno = CAST(SUBSTRING(m2.active_fil_no FROM 4 FOR 6) AS INTEGER) 
            //                             OR lt.lct_caseno BETWEEN 
            //                             CAST(SUBSTRING(m2.active_fil_no FROM 4 FOR 6) AS INTEGER) 
            //                             AND CAST(SUBSTRING(m2.active_fil_no FROM 11 FOR 6) AS INTEGER)
            //                         )', 'LEFT')
            //         ->join('docdetails AS dd', 'dd.diary_no = lt.diary_no 
            //                              AND dd.doccode = 8 
            //                              AND dd.doccode1 IN (101, 154, 240, 111, 223, 383, 371)', 'INNER')
            //         ->where('lt.lct_caseno', $d_num)
            //         ->where('lt.lct_caseyear', $d_year)
            //         ->where('ct_code', 4)
            //         ->where('lw_display', 'Y')
            //         ->where('is_order_challenged', 'Y');
            // });

            $query = "
                SELECT 
                    CONCAT(m.pet_name, ' Vs ', m.res_name) AS ma_title, 
                    m.c_status AS ma_status, 
                    TO_CHAR(h.next_dt, 'DD-MM-YYYY') AS restore_date, 
                    m.diary_no AS ma_diary, 
                    CONCAT(ct.short_description, ' No. ', SUBSTRING(m.active_fil_no FROM 4 FOR 12), '/', m.active_reg_year) AS ma_no
                FROM 
                    main AS m
                LEFT JOIN 
                    master.casetype AS ct ON m.active_casetype_id = ct.casecode
                LEFT JOIN 
                    heardt AS h ON h.diary_no = m.diary_no
                WHERE 
                    m.diary_no IN (
                        SELECT DISTINCT lt.diary_no
                        FROM lowerct AS lt
                        LEFT JOIN main AS m2 ON lt.lct_caseyear = m2.active_reg_year 
                            AND lt.lct_casetype = m2.active_casetype_id 
                            AND (
                                lt.lct_caseno = CAST(SUBSTRING(m2.active_fil_no FROM 4 FOR 6) AS INTEGER)::TEXT 
                                OR lt.lct_caseno BETWEEN CAST(SUBSTRING(m2.active_fil_no FROM 4 FOR 6) AS INTEGER)::TEXT 
                                AND CAST(SUBSTRING(m2.active_fil_no FROM 11 FOR 6) AS INTEGER)::TEXT
                            )
                        INNER JOIN docdetails AS dd ON dd.diary_no = lt.diary_no 
                            AND dd.doccode = 8 
                            AND dd.doccode1 IN (101, 154, 240, 111, 223, 383, 371)
                        WHERE 
                            lt.lct_caseno = '$d_num' 
                            AND lt.lct_caseyear = '$d_year' 
                            AND ct_code = 4 
                            AND lw_display = 'Y' 
                            AND is_order_challenged = 'Y'
                    );";

            $result = $this->db->query($query);
            $queryresult = $result->getResultArray();
        } else if (strpos($fil_no, '31-') !== false) {
            // $condition= " where lct_caseno=".$d_num ." AND lct_caseyear=".$d_year ." AND  ct_code=4 AND lw_display='Y' AND is_order_challenged='Y'";
            // $sql="select concat(pet_name,' Vs ',res_name) as ma_title,c_status as ma_status,date_format(h.next_dt,'%d-%m-%Y') as restore_date,m.diary_no as ma_diary,
            // concat(short_description,' No. ',substring(m.active_fil_no,4,12),'/',active_reg_year) as ma_no
            // from main m left join casetype ct on m.active_casetype_id=ct.casecode
            // left join heardt h on h.diary_no=m.diary_no where
            // m.diary_no in (select distinct lt.diary_no from lowerct lt left join main m on lt.lct_caseyear=m.active_reg_year
            // and lt.lct_casetype=m.active_casetype_id and
            // (lt.lct_caseno= cast(substring(m.active_fil_no,4,6) as unsigned ) or lt.lct_caseno between cast(substring(m.active_fil_no,4,6) as unsigned ) and cast(substring(m.active_fil_no,11,6) as unsigned ))
            // inner join docdetails dd on dd.diary_no=lt.diary_no AND doccode=8 and (doccode1=101 or doccode1=154 or doccode1=240 or doccode1=111 or doccode1=223 or doccode1=383 or doccode1=371) where lct_caseno=".$d_num ." AND lct_caseyear=".$d_year ." AND  ct_code=4 AND lw_display='Y' AND is_order_challenged='Y') ";
            $builder = $this->db->table('main m');
            $builder->select("CONCAT(pet_name, ' Vs ', res_name) AS ma_title, c_status AS ma_status, TO_CHAR(h.next_dt, 'DD-MM-YYYY') AS restore_date, m.diary_no AS ma_diary, CONCAT(short_description, ' No. ', SUBSTRING(m.active_fil_no FROM 4 FOR 12), '/', active_reg_year) AS ma_no");
            $builder->join('casetype ct', 'm.active_casetype_id = ct.casecode', 'left');
            $builder->join('heardt h', 'h.diary_no = m.diary_no', 'left');
            $builder->whereIn('m.diary_no', function ($subquery) use ($d_num, $d_year) {
                $subquery->select('DISTINCT lt.diary_no')
                    ->from('lowerct lt')
                    ->join('main m', 'lt.lct_caseyear = m.active_reg_year AND lt.lct_casetype = m.active_casetype_id AND (lt.lct_caseno = CAST(SUBSTRING(m.active_fil_no FROM 4 FOR 6) AS INTEGER) OR lt.lct_caseno BETWEEN CAST(SUBSTRING(m.active_fil_no FROM 4 FOR 6) AS INTEGER) AND CAST(SUBSTRING(m.active_fil_no FROM 11 FOR 6) AS INTEGER))', 'left')
                    ->join('docdetails dd', 'dd.diary_no = lt.diary_no AND doccode = 8 AND doccode1 IN (101, 154, 240, 111, 223, 383, 371)', 'inner')
                    ->where('lct_caseno', $d_num)
                    ->where('lct_caseyear', $d_year)
                    ->where('ct_code', 4)
                    ->where('lw_display', 'Y')
                    ->where('is_order_challenged', 'Y');
            });
            $query = $builder->get();
            $queryresult = $query->getResultArray();
        } else {
            // $condition=" where m.diary_no= ".$diarynumber." AND ct_code=4 AND lw_display='Y' AND is_order_challenged='Y'";
            // $sql="select concat(pet_name,' Vs ',res_name) as ma_title,c_status as ma_status,date_format(h.next_dt,'%d-%m-%Y') as restore_date,m.diary_no as ma_diary,
            // concat(short_description,' No. ',substring(m.active_fil_no,4,12),'/',active_reg_year) as ma_no
            // from main m left join casetype ct on m.active_casetype_id=ct.casecode
            // left join heardt h on h.diary_no=m.diary_no where
            // m.diary_no in (select distinct lt.diary_no from lowerct lt left join main m on lt.lct_caseyear=m.active_reg_year
            // and lt.lct_casetype=m.active_casetype_id and
            // (lt.lct_caseno= cast(substring(m.active_fil_no,4,6) as unsigned ) or lt.lct_caseno between cast(substring(m.active_fil_no,4,6) as unsigned ) and cast(substring(m.active_fil_no,11,6) as unsigned ))
            // inner join docdetails dd on dd.diary_no=lt.diary_no AND doccode=8 and (doccode1=101 or doccode1=154 or doccode1=240 or doccode1=111 or doccode1=223 or doccode1=383 or doccode1=371) where m.diary_no= ".$diarynumber." AND ct_code=4 AND lw_display='Y' AND is_order_challenged='Y') ";
            // $builder = $this->db->table('main m');
            // $builder->select("CONCAT(pet_name, ' Vs ', res_name) AS ma_title, c_status AS ma_status, TO_CHAR(h.next_dt, 'DD-MM-YYYY') AS restore_date, m.diary_no AS ma_diary, CONCAT(short_description, ' No. ', SUBSTRING(m.active_fil_no FROM 4 FOR 12), '/', active_reg_year) AS ma_no");
            // $builder->join('master.casetype ct', 'm.active_casetype_id = ct.casecode', 'left');
            // $builder->join('heardt h', 'h.diary_no = m.diary_no', 'left');
            // $builder->whereIn('m.diary_no', function ($subquery) use ($diarynumber) {
            //     $subquery->select('DISTINCT lt.diary_no')
            //         ->from('lowerct lt')
            //         ->join('main m', 'lt.lct_caseyear = m.active_reg_year AND lt.lct_casetype = m.active_casetype_id AND (lt.lct_caseno = CAST(SUBSTRING(m.active_fil_no FROM 4 FOR 6) AS INTEGER) OR lt.lct_caseno BETWEEN CAST(SUBSTRING(m.active_fil_no FROM 4 FOR 6) AS INTEGER) AND CAST(SUBSTRING(m.active_fil_no FRsubqueryOM 11 FOR 6) AS INTEGER))', 'left')
            //         ->join('docdetails dd', 'dd.diary_no = lt.diary_no AND doccode = 8 AND doccode1 IN (101, 154, 240, 111, 223, 383, 371)', 'inner')
            //         ->where('m.diary_no', $diarynumber)
            //         ->where('ct_code', 4)
            //         ->where('lw_display', 'Y')
            //         ->where('is_order_challenged', 'Y');
            // });

            // echo $diarynumber; die;
            $builder = $this->db->table('main m');
            $builder->select("CONCAT(m.pet_name, ' Vs ', m.res_name) AS ma_title, m.c_status AS ma_status, TO_CHAR(h.next_dt, 'DD-MM-YYYY') AS restore_date, m.diary_no AS ma_diary, CONCAT(short_description, ' No. ', SUBSTRING(m.active_fil_no FROM 4 FOR 12), '/', active_reg_year) AS ma_no");
            $builder->join('master.casetype ct', 'm.active_casetype_id = ct.casecode', 'left');
            $builder->join('heardt h', 'h.diary_no = m.diary_no', 'left');
            $builder->whereIn('m.diary_no', function ($subquery) use ($diarynumber) {
                $subquery->select('DISTINCT lt.diary_no', false);
                $subquery->from('lowerct lt');
                $subquery->join('docdetails dd', 'dd.diary_no = lt.diary_no AND doccode = 8 AND doccode1 IN (101, 154, 240, 111, 223, 383, 371)', 'inner', false);
                $subquery->join('main m', 'lt.lct_caseyear = m.active_reg_year AND lt.lct_casetype = m.active_casetype_id AND (lt.lct_caseno::int = CAST(SUBSTRING(m.active_fil_no FROM 4 FOR 6) AS INTEGER) OR (lt.lct_caseno::int >= CAST(SUBSTRING(m.active_fil_no FROM 4 FOR 6) AS INTEGER) AND lt.lct_caseno::int <= CAST(SUBSTRING(m.active_fil_no FROM 11 FOR 6) AS INTEGER)))', 'left ', false);
                $subquery->where('m.diary_no', $diarynumber);
                $subquery->where('ct_code', 4);
                $subquery->where('lw_display', 'Y');
                $subquery->where('is_order_challenged', 'Y');
            });

            echo $builder->getCompiledSelect();die;
            // echo $queryString;
            // exit();
            $query = $builder->get();
            $queryresult = $query->getResultArray();
        }



        // $query = $this->db->query($sql);
        if (count($queryresult) >= 1) {
            return $queryresult;
        } else {
            return false;
        }
    }

    public function restore_archived_data($diary_no)
    {
        $output = [];

        // Call Restore Procedure
        try {
            $this->db->query("call restore_archived_data('$diary_no')");
            $output['success'] = 1;
            $output['message'] = "Case Restored.";
        } catch (\Exception $e) {
            log_error("Case Restore Error [$diary_no]: ", $e->getMessage());
            $output['success'] = 0;
            $output['message'] = "There is some problem. Please contact Computer-Cell.";
        }

        return $output;
    }

    public function restor_case($case_diary, $ma_diary, $restore_date, $usercode, $ianum)
    {
        // Restore Data from archived tables
        $this->restore_archived_data($case_diary);

        $ma_diary = 0;
        // $sql2="update main set c_status='P' where diary_no=$case_diary";
        $builder = $this->db->table('main');
        $builder->set('c_status', 'P');
        $builder->where('diary_no', $case_diary);
        $sql2 = $builder->update();

        // $sql4="insert into dispose_delete (diary_no,fil_no,month,dispjud,year,ord_dt,disp_dt,disp_type,bench,jud_id,camnt, crtstat,usercode,ent_dt,jorder,rj_dt,disp_type_all,entered_on,dispose_updated_by)select diary_no,fil_no,month,dispjud,year,ord_dt,case when (date(disp_dt)='0000-00-00' OR disp_dt is null) Then disp_dt_old else disp_dt END AS disp_dt,disp_type,bench,jud_id,camnt, crtstat,usercode,ent_dt,jorder,rj_dt,disp_type_all,now(),$usercode from dispose where diary_no=$case_diary";
        $builder1 = $this->db->table('dispose_delete');
        $builder1->set('diary_no', 'SELECT diary_no FROM dispose WHERE diary_no = ' . $case_diary, FALSE);
        $builder1->set('fil_no', 'SELECT fil_no FROM dispose WHERE diary_no = ' . $case_diary, FALSE);
        $builder1->set('month', 'SELECT month FROM dispose WHERE diary_no = ' . $case_diary, FALSE);
        $builder1->set('dispjud', 'SELECT dispjud FROM dispose WHERE diary_no = ' . $case_diary, FALSE);
        $builder1->set('year', 'SELECT year FROM dispose WHERE diary_no = ' . $case_diary, FALSE);
        $builder1->set('ord_dt', 'SELECT ord_dt FROM dispose WHERE diary_no = ' . $case_diary, FALSE);
        $builder1->set('disp_dt', "CASE WHEN (date(disp_dt) = '0000-00-00' OR disp_dt IS NULL) THEN disp_dt_old ELSE disp_dt END", FALSE);
        $builder1->set('disp_type', 'SELECT disp_type FROM dispose WHERE diary_no = ' . $case_diary, FALSE);
        $builder1->set('bench', 'SELECT bench FROM dispose WHERE diary_no = ' . $case_diary, FALSE);
        $builder1->set('jud_id', 'SELECT jud_id FROM dispose WHERE diary_no = ' . $case_diary, FALSE);
        $builder1->set('camnt', 'SELECT camnt FROM dispose WHEREcast(substring(active_fil_no diary_no = ' . $case_diary, FALSE);
        $builder1->set('crtstat', 'SELECT crtstat FROM dispose WHERE diary_no = ' . $case_diary, FALSE);
        $builder1->set('usercode', 'SELECT usercode FROM dispose WHERE diary_no = ' . $case_diary, FALSE);
        $builder1->set('ent_dt', 'SELECT ent_dt FROM dispose WHERE diary_no = ' . $case_diary, FALSE);
        $builder1->set('jorder', 'SELECT jorder FROM dispose WHERE diary_no = ' . $case_diary, FALSE);
        $builder1->set('rj_dt', 'SELECT rj_dt FROM dispose WHERE diary_no = ' . $case_diary, FALSE);
        $builder1->set('disp_type_all', 'SELECT disp_type_all FROM dispose WHERE diary_no = ' . $case_diary, FALSE);
        $builder1->set('entered_on', 'NOW()', FALSE);
        $builder1->set('dispose_updated_by', $usercode);
        $sql4 = $builder1->insert();


        // $sql5="delete from dispose where diary_no= $case_diary";
        $builder2 = $this->db->table('dispose');
        $builder2->where('diary_no', $case_diary);
        $sql5 = $builder2->delete();

        // $sql1="insert into restored(entry_date, diary_no, fil_no, res_by_diary_no, fil_no_res_by,
        //     diary_next_dt,conn_next_dt, judges, pet, res, disp_month, disp_year, dispjud, disp_dt,
        //     disp_type, disp_judges,disp_crtstat, disp_camnt, disp_ent_dt, disp_ord_dt, disp_usercode,
        //     reg_dt, restore_reason,disp_rj_dt, diary_rec_dt, usercode) select now(),m.diary_no,
        //     m.active_fil_no,$ma_diary,ma_detail.active_fil_no,hd.next_dt,$restore_date,
        //     ma_detail.judges,m.pet_name,m.res_name,d.month,d.year,d.dispjud,d.disp_dt,d.disp_type,
        //     d.jud_id,d.crtstat,d.camnt,d.ent_dt,d.ord_dt,d.usercode,m.active_fil_dt,'',d.rj_dt,
        //     m.diary_no_rec_date,$usercode from main m left join heardt hd on m.diary_no=hd.diary_no
        //     left join dispose d on d.diary_no=m.diary_no ,(select mi.active_fil_no,hd.next_dt,
        //     hd.judges from main mi left join heardt hd on mi.diary_no=hd.diary_no where
        //     mi.diary_no=$ma_diary) as ma_detail where m.diary_no=$case_diary";
        $builder3 = $this->db->table('restored');
        $builder3->set('entry_date', 'NOW()', false);
        $builder3->set('diary_no', $case_diary);
        $builder3->set('fil_no', 'm.active_fil_no', false);
        $builder3->set('res_by_diary_no', $ma_diary, false);
        $builder3->set('fil_no_res_by', 'ma_detail.active_fil_no', false);
        $builder3->set('diary_next_dt', 'hd.next_dt', false);
        $builder3->set('conn_next_dt', $restore_date);
        $builder3->set('judges', 'ma_detail.judges', false);
        $builder3->set('pet', 'm.pet_name', false);
        $builder3->set('res', 'm.res_name', false);
        $builder3->set('disp_month', 'd.month', false);
        $builder3->set('disp_year', 'd.year', false);
        $builder3->set('dispjud', 'd.dispjud', false);
        $builder3->set('disp_dt', 'd.disp_dt', false);
        $builder3->set('disp_type', 'd.disp_type', false);
        $builder3->set('disp_judges', 'd.jud_id', false);
        $builder3->set('disp_crtstat', 'd.crtstat', false);
        $builder3->set('disp_camnt', 'd.camnt', false);
        $builder3->set('disp_ent_dt', 'd.ent_dt', false);
        $builder3->set('disp_ord_dt', 'd.ord_dt', false);
        $builder3->set('disp_usercode', 'd.usercode', false);
        $builder3->set('reg_dt', 'm.active_fil_dt', false);
        $builder3->set('restore_reason', '');
        $builder3->set('disp_rj_dt', 'd.rj_dt', false);
        $builder3->set('diary_rec_dt', 'm.diary_no_rec_date', false);
        $builder3->set('usercode', $usercode);
        $sql1 = $builder3->insert();

        // $sql6="insert into docdetails_history select *,$usercode,now() from docdetails where docd_id in($ianum)";
        $builder4 = $this->db->table('docdetails_history');
        $builder4->select('*');
        $builder4->whereIn('docd_id', $ianum);
        $subQueryResult = $builder4->get();
        $sql6 = $this->db->table('docdetails_history')->insertBatch($subQueryResult->getResultArray());

        // $sql7="update docdetails set iastat='P', dispose_date='',last_modified_by='',disposal_remark='',lst_mdf=NOW() where docd_id in($ianum); ";
        // $this->db->query($sql1);
        $builder7 = $this->db->table('docdetails');
        $builder7->set('iastat', 'P');
        $builder7->set('dispose_date', 'P');
        $builder7->set('last_modified_by', 'P');
        $builder7->set('disposal_remark', 'P');
        $builder7->set('lst_mdf', 'NOW()');
        $builder7->whereIn('docd_id', $ianum);
        $sql7 = $builder7->update();

        if ($sql2 != 1) {
            echo "There is some problem. Please contact Computer-Cell.";
        }

        if ($sql7 != 1) {
            echo "There is some problem. Please contact Computer-Cell.";
        }

        if ($sql5 != 1) {
            echo "There is some problem. Please contact Computer-Cell.";
        }

        // if($this->db->affected_rows()!=1){
        //         echo "There is some problem. Please contact Computer-Cell.";
        // }
        //else
        if ($ianum != '' && $ianum != null) {
            // $this->db->query($sql6);
            if ($sql7 < 1) {
                echo "There is some problem. Please contact Computer-Cell.";
            } else {
                echo "Case Restored.";
            }
        } else {
            echo "Case Restored.";
        }
    }

    function Check_Case_listing($diary_number = 0)
    {
        // $sql_listing="Select a.*,section_name,b.name from heardt a LEFT JOIN master.users b ON a.usercode=b.usercode LEFT JOIN master.usersection c ON b.section=c.id where roster_id>0 AND brd_slno>0 AND clno>0 AND main_supp_flag !=3 AND next_dt!='0000-00-00' and next_dt>=date(now()) AND diary_no='".$diary_number."' ";
        // $query = $this->db->query($sql_listing);

        $builder = $this->db->table("heardt a");
        $builder->select('a.*, c.section_name, b.name');
        $builder->join('master.users b', 'a.usercode = b.usercode', 'left');
        $builder->join('master.usersection c', 'b.section = c.id', 'left');
        $builder->where('roster_id >', 0);
        $builder->where('brd_slno >', 0);
        $builder->where('clno >', 0);
        $builder->where('main_supp_flag !=', 3);
        $builder->where('next_dt IS NOT NULL', null, false);
        $builder->where('next_dt >=', 'CURRENT_DATE', false);
        $builder->where('diary_no', $diary_number);
        $query = $builder->get();
        $result = $query->getResultArray();
        if (count($result) >= 1) {
            return $result;
        } else {
            return false;
        }
    }
}
