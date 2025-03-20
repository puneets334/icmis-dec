<?php

namespace App\Models\Reports\Judicial;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class ReportModel extends Model
{


    public function __construct(){
        parent::__construct();
    }

    //Section of Judges Report
    function get_judges_list_current() {

        $builder = $this->db->table("master.judge j");
        $builder->select("*");
        $builder->where('is_retired', 'N');
        $builder->where('jtype', 'J');
        $builder->where('display', 'Y');
        $builder->orderBy('jcode');
        $builder->limit(5000);
       return  $builder = $builder->get()->getResultArray();

    }
    
    function getElimination_list($data){
        //print_r($data['listing_dts']);exit;
                    //CASE WHEN us.section_name IS NOT NULL THEN us.section_name ELSE "" END AS section_name,  
                    $builder = $this->db->table('main m');
                    $builder->select('tt.next_dt_old as date, us.id as us_id, m.diary_no, tt.listorder_new, tt.next_dt_new, u.name, us.section_name as section_name,
                        m.conn_key as main_key, c1.short_description, active_fil_no, m.active_reg_year, m.casetype_id,
                        m.active_casetype_id, m.ref_agency_state_id, m.reg_no_display, EXTRACT(YEAR FROM m.fil_dt) as fil_year,
                        m.fil_no, m.fil_dt, m.fil_no_fh, m.reg_year_fh as fil_year_f, m.mf_active, m.pet_name, m.res_name,
                        pno, rno, m.diary_no_rec_date,
                        CASE WHEN (tt.diary_no = tt.conn_key OR tt.conn_key IS NULL OR tt.conn_key = 0 OR tt.conn_key IS NULL) THEN 0 ELSE 1 END AS main_or_connected,
                        (SELECT CASE WHEN diary_no IS NOT NULL THEN 1 ELSE 0 END FROM conct WHERE diary_no = m.diary_no AND list = \'Y\' LIMIT 1) AS listed');
                        $builder->join('transfer_old_com_gen_cases tt', 'tt.diary_no = m.diary_no','left');
                        $builder->Join('master.casetype c1', 'm.active_casetype_id = c1.casecode','left');
                        $builder->Join('master.users u', 'u.usercode = m.dacode AND u.display = \'Y\'','left');
                        $builder->Join('master.usersection us', 'us.id = u.section','left');
                        $builder->Join('mul_category c2', 'c2.diary_no = m.diary_no AND c2.display = \'Y\'','left');
                        
                        if(!empty($data['sec_id'])) {
                            $builder->whereIn('us.id', (array) $data['sec_id']);
                        }

                        if(!empty($data['listing_dts'])){ 
                        $builder->where('tt.next_dt_old <=', $data['listing_dts']);
                        //$builder->where('tt.next_dt_new >=', date('Y-m-d'));
                        }
                        $builder->whereIn('m.diary_no', function ($subquery) {
                        $subquery->select('m2.diary_no')
                            ->from('main m2')
                            ->groupBy('m2.diary_no');
                    });
                    $builder->groupBy('tt.next_dt_old, us.id, m.diary_no, tt.listorder_new, tt.next_dt_new, u.name, us.section_name, c1.short_description, tt.diary_no, tt.conn_key');
                    $builder->orderBy('main_or_connected', 'asc');
                    $builder->orderBy('fil_year', 'asc');
                    $builder->orderBy('m.conn_key', 'asc');
                    $builder->orderBy('CASE WHEN (m.conn_key = CAST(m.diary_no as CHAR)) THEN 0 ELSE 1 END', 'asc');
                    $builder->limit(5000);
                
                    // echo $builder->getCompiledSelect();die;


                return $results = $builder->get()->getResult();
                //echo $this->db->getLastQuery();
            }


            public function getSection_list($data){
            //print_r($data);
            $order_by ='';
            if(!empty($data['orderby']) && $data['orderby'] != '0'){
                
                if($data['orderby'] == '1'){
                    $order_by = "r.courtno ";  
                }   
                elseif($data['orderby'] == '2'){ 
                    $order_by = "us.id ";
                }
                else{
                    $order_by = "";
                }
            }

            $builder = $this->db->table('heardt h');
            $builder->select('date(m.diary_no_rec_date) as diary_no_rec_date,m.diary_no,  h.brd_slno, m.casetype_id, p.ent_time, tentative_section(h.diary_no) as dno, r.courtno, u.name, us.section_name, l.purpose, c1.short_description, EXTRACT(YEAR FROM m.active_fil_dt) as fyr, active_reg_year, active_fil_dt, active_fil_no, m.reg_no_display, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date, remark');
            $builder->join('main m', 'm.diary_no = h.diary_no');
            $builder->join('master.listing_purpose l', 'l.code = h.listorder AND l.display = \'Y\'');
            $builder->join('master.roster r', 'r.id = h.roster_id AND r.display = \'Y\'');
            $builder->join('brdrem br', 'CAST(br.diary_no AS BIGINT) = m.diary_no', 'left');
            $builder->join('master.casetype c1', 'm.active_casetype_id = c1.casecode', 'left');
            $builder->join('master.users u', 'u.usercode = m.dacode AND (u.display = \'Y\' OR u.display IS NULL)', 'left');
            $builder->join('master.usersection us', 'us.id = u.section', 'left');
            $builder->join('cl_printed p', 'p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = \'Y\'', 'left');
            $builder->join('conct ct', 'm.diary_no = ct.diary_no AND ct.list = \'Y\'', 'left');
            
            if(!empty($data['mainhead']))
                $builder->where("h.mainhead", $data['mainhead']);

            if(!empty($data['listing_dts'] && $data['listing_dts'] != 0))
                $builder->where("h.next_dt", date('Y-m-d', strtotime($data['listing_dts'])));

            if(!empty($data['courtno']) && $data['courtno'] != 0 )
                $builder->where("r.courtno", $data['courtno']);
            
            if(!empty($data['board_type']) && is_string($data['board_type']))
                $builder->where("h.board_type", $data['board_type']);

            if(!empty($data['listing_purpose'])) 
                $builder->where("h.board_type", $data['board_type']);

                if(!empty($data['listing_purpose'])) 
                    $builder->where("h.board_type", $data['board_type']);
                if(!empty($data['listing_purpose']) && $data['listing_purpose'] != 0 ) 
                    $builder->where("h.listorder", $data['listing_purpose']);

                if(!empty($data['main_suppl']) && $data['main_suppl'] != 0 )  
                    $builder->where("h.main_supp_flag", $data['main_suppl']);

                if(!empty($data['sec_id']) && $data['sec_id'] != 0 )   
                    $builder->where("u.section", $data['sec_id']);    



            //$builder->whereIn("h.main_supp_flag", [1, 2]);
            //$builder->where("h.roster_id >", 0);
            $builder->where("m.diary_no IS NOT NULL");
            //$builder->where("m.c_status", 'P');
            $builder->groupBy('h.diary_no,m.diary_no_rec_date, m.casetype_id, p.ent_time, r.courtno, u.name, us.section_name, l.purpose, c1.short_description,
                        m.active_fil_dt,m.active_reg_year, m.active_fil_no, m.reg_no_display, m.pet_name, m.res_name, m.pno,m.rno, m.ref_agency_state_id, br.remark,
                        ct.ent_dt, m.diary_no');
            if(!empty($order_by)){
                $builder->orderBy($order_by .' ASC');
            }else{
                $builder->orderBy('r.courtno, COALESCE(CASE WHEN us.section_name IS NULL THEN 9999 ELSE 0 END, 9999) ASC');
                $builder->orderBy('us.section_name ASC');
                $builder->orderBy('u.name ASC');
                $builder->orderBy('h.brd_slno ASC');
            }

            
            //$builder->orderBy('CASE WHEN h.conn_key = h.diary_no THEN NULL::timestamp ELSE \'9999-12-31\'::timestamp ASC');
            //$builder->orderBy('COALESCE(NULLIF(ct.ent_dt, \'0001-01-01\'::timestamp), \'9999-12-31\'::timestamp) ASC');
            $builder->orderBy('CAST(SUBSTRING(CAST(m.diary_no AS VARCHAR) FROM -4) AS integer) ASC');
            $builder->orderBy('CAST(LEFT(CAST(m.diary_no AS VARCHAR), LENGTH(CAST(m.diary_no AS VARCHAR)) - 4) AS integer) ASC');
            $builder->limit(500);
            // echo $builder->getCompiledSelect();die;
            return $results = $builder->get()->getResult();
            //echo $this->db->getLastQuery();die;

            }

            function getWeekly_list_backup($data){
                //echo "sfdsd";
                //echo "<pre>";print_r($data);die;
                $builder = $this->db->table('heardt h');
                // SELECT columns
                $builder->select([
                    'ct.ent_dt',
                    'tentative_section(h.diary_no) AS dno',
                    'r.courtno',
                    'u.name',
                    'us.section_name',
                    'l.purpose',
                    'c1.short_description',
                    'EXTRACT(YEAR FROM m.active_fil_dt) AS fyr',
                    'active_reg_year',
                    'active_fil_dt',
                    'm.conn_key',
                    'active_fil_no',
                    'm.pet_name',
                    'm.res_name',
                    'm.pno',
                    'm.rno',
                    'casetype_id',
                    'ref_agency_state_id',
                    'diary_no_rec_date',
                    'remark',
                    'h.diary_no',
                    'h.next_dt',
                    'h.subhead',
                    'h.judges',
                    'h.coram',
                    'h.brd_slno',
                    'h.clno',
                    'h.listorder',
                    'm.reg_no_display'
                ]);

                // FROM heardt
                //$this->db->from('heardt h');

                // JOIN main
                $builder->join('main m', 'm.diary_no = h.diary_no', 'INNER');

                // JOIN master.listing_purpose
                $builder->join('master.listing_purpose l', 'l.code = h.listorder AND l.display = \'Y\'', 'INNER');

                // JOIN master.roster
                $builder->join('master.roster r', 'r.id = h.roster_id AND r.display = \'Y\' AND r.courtno = \'2\'', 'INNER');

                // LEFT JOIN brdrem
                $builder->join('brdrem br', 'CAST(br.diary_no AS BIGINT) = m.diary_no', 'LEFT');

                // LEFT JOIN master.casetype
                $builder->join('master.casetype c1', 'm.active_casetype_id = c1.casecode', 'LEFT');

                // LEFT JOIN master.users
                $builder->join('master.users u', 'u.usercode = m.dacode AND (u.display = \'Y\' OR u.display IS NULL)', 'LEFT');

                // LEFT JOIN master.usersection
                $builder->join('master.usersection us', 'us.id = u.section AND (us.id = \'20\' OR tentative_section(h.diary_no) = \'II\')', 'LEFT');

                // LEFT JOIN conct
                $builder->join('conct ct', 'm.diary_no = ct.diary_no AND ct.list = \'Y\'', 'LEFT');

                // WHERE conditions
                    // $builder->where('h.mainhead', 'F');
                    // $builder->where('h.main_supp_flag', '2');
                    // $builder->where('us.id IS NOT NULL');
                // Uncomment and provide appropriate values for date range
                // $this->db->where('h.next_dt BETWEEN \'--\' AND \'--\'');
                    // $builder->where('h.listorder', '5');
                    // $builder->where('h.board_type', 'J');
                    // $builder->whereIn('h.main_supp_flag', [1, 2]);
                    // $builder->where('h.roster_id >', 0);
                    // $builder->where('m.diary_no IS NOT NULL');
                    // $builder->where('m.c_status', 'P');

                // GROUP BY
                $builder->groupBy('h.diary_no, ct.ent_dt, r.courtno, u.name, us.section_name, l.purpose, c1.short_description, m.active_fil_dt, m.active_reg_year, m.conn_key,
                    m.active_fil_no, m.pet_name, m.res_name,
                    m.pno, m.rno, casetype_id, ref_agency_state_id, m.diary_no_rec_date, br.remark,
                    h.diary_no, h.next_dt, h.subhead, h.judges, h.coram, h.brd_slno, h.clno,
                    h.listorder, m.reg_no_display, h.board_type');

                // ORDER BY for the final combined query
                $builder->orderBy('r.courtno');
                $builder->orderBy('dno ASC');
                $builder->orderBy('us.section_name');
                $builder->orderBy('u.name');
                $builder->orderBy('h.brd_slno');
                $builder->orderBy("CASE WHEN COALESCE(NULLIF(m.conn_key, '')::integer, 0) = h.diary_no THEN '0000-00-00' ELSE '99' END asc");
                $builder->orderBy("CASE WHEN ct.ent_dt IS NOT NULL THEN EXTRACT(EPOCH FROM ct.ent_dt) ELSE 0 END ASC");
                $builder->orderBy("CAST(SUBSTRING(CAST(h.diary_no AS VARCHAR) FROM 5) AS INTEGER) ASC");
                $builder->orderBy("CAST(SUBSTRING(CAST(h.diary_no AS VARCHAR) FROM 1 FOR 4) AS INTEGER) ASC");
                $builder->limit(500);
                $builder2 = $this->db->table('last_heardt h');
                // SELECT columns for second query
                //$builder->select('h.board_type as board_type');
                //$builder2->select('h.board_type as board_type');
                $builder2->select([
                    'ct.ent_dt',
                    'tentative_section(h.diary_no) AS dno',
                    'r.courtno',
                    'u.name',
                    'us.section_name',
                    'l.purpose',
                    'c1.short_description',
                    'EXTRACT(YEAR FROM m.active_fil_dt) AS fyr',
                    'active_reg_year',
                    'active_fil_dt',
                    'm.conn_key',
                    'active_fil_no',
                    'm.pet_name',
                    'm.res_name',
                    'm.pno',
                    'm.rno',
                    'casetype_id',
                    'ref_agency_state_id',
                    'diary_no_rec_date',
                    'remark',
                    'h.diary_no',
                    'h.next_dt',
                    'h.subhead',
                    'h.judges',
                    'h.coram',
                    'h.brd_slno',
                    'h.clno',
                    'h.listorder',
                    'm.reg_no_display'
                ]);
                // JOIN main (same as in the first query)
                $builder2->join('main m', 'm.diary_no = h.diary_no', 'INNER');

                // JOIN master.listing_purpose (same as in the first query)
                $builder2->join('master.listing_purpose l', 'l.code = h.listorder AND l.display = \'Y\'', 'INNER');

                // JOIN master.roster (same as in the first query)
                $builder2->join('master.roster r', 'r.id = h.roster_id AND r.display = \'Y\' AND r.courtno = \'2\'', 'INNER');

                // LEFT JOIN brdrem (same as in the first query)
                $builder2->join('brdrem br', 'CAST(br.diary_no AS BIGINT) = m.diary_no', 'LEFT');

                // LEFT JOIN master.casetype (same as in the first query)
                $builder2->join('master.casetype c1', 'm.active_casetype_id = c1.casecode', 'LEFT');

                // LEFT JOIN master.users (same as in the first query)
                $builder2->join('master.users u', 'u.usercode = m.dacode AND (u.display = \'Y\' OR u.display IS NULL)', 'LEFT');

                // LEFT JOIN master.usersection (same as in the first query)
                $builder2->join('master.usersection us', 'us.id = u.section AND (us.id = \'20\' OR tentative_section(h.diary_no) = \'II\')', 'LEFT');

                // LEFT JOIN conct (same as in the first query)
                $builder2->join('conct ct', 'm.diary_no = ct.diary_no AND ct.list = \'Y\'', 'LEFT');

                // WHERE conditions (same as in the first query)
                    // $builder2->where('(h.bench_flag = \'\' OR h.bench_flag IS NULL)');
                    // $builder2->where('h.mainhead', 'F');
                    // $builder2->where('h.main_supp_flag', '2');
                    // $builder2->where('us.id IS NOT NULL');
                // Uncomment and provide appropriate values for date range
                // $this->db->where('h.next_dt BETWEEN \'--\' AND \'--\'');
                    // $builder2->where('h.listorder', '5');
                    // $builder2->where('h.board_type', 'J');
                    // $builder2->whereIn('h.main_supp_flag', [1, 2]);
                    // $builder2->where('h.roster_id >', 0);
                    // $builder2->where('m.diary_no IS NOT NULL');
                    // $builder2->where('m.c_status', 'P');

                // GROUP BY (same as in the first query)
                $builder2->groupBy('h.diary_no, ct.ent_dt,r.courtno,u.name, us.section_name, l.purpose, c1.short_description, m.active_fil_dt, m.active_reg_year, m.conn_key,
                    m.active_fil_no, m.pet_name, m.res_name,
                    m.pno, m.rno, casetype_id, ref_agency_state_id, m.diary_no_rec_date, br.remark,
                    h.diary_no, h.next_dt, h.subhead, h.judges, h.coram, h.brd_slno, h.clno,
                    h.listorder, m.reg_no_display,h.board_type');

               // ORDER BY for the second query in the UNION
                $builder2->orderBy('r.courtno');
                $builder2->orderBy('dno ASC');
                $builder2->orderBy('us.section_name');
                $builder2->orderBy('u.name');
                $builder2->orderBy('h.brd_slno');
                $builder2->orderBy("CASE WHEN COALESCE(NULLIF(m.conn_key, '')::integer, 0) = h.diary_no THEN '0000-00-00' ELSE '99' END asc");
                $builder2->orderBy("CASE WHEN ct.ent_dt IS NOT NULL THEN EXTRACT(EPOCH FROM ct.ent_dt) ELSE 0 END ASC");
                $builder2->orderBy("CAST(SUBSTRING(CAST(h.diary_no AS VARCHAR) FROM 5) AS INTEGER) ASC");
                $builder2->orderBy("CAST(SUBSTRING(CAST(h.diary_no AS VARCHAR) FROM 1 FOR 4) AS INTEGER) ASC");
                $builder2->limit(500);

                $query = $builder->union($builder2)->get();

                return $result = $query->getResultArray();
                //echo $this->db->getLastQuery();
                


            }

            function getWeekly_list($data)
            {
                //echo "sfdsd";
                //echo "<pre>";print_r($data);die;
                $order_by ='';
                if(!empty($data['orderby']) && $data['orderby'] != '0'){
                    
                    if($data['orderby'] == '1'){
                        $order_by = "r.courtno ";  
                    }   
                    elseif($data['orderby'] == '2'){ 
                        $order_by = "us.id ";
                    }
                    else{
                        $order_by = "";
                    }
                }
                $builder = $this->db->table('heardt h');
                // SELECT columns
                $builder->select([
                    'ct.ent_dt',
                    'tentative_section(h.diary_no) AS dno',
                    'r.courtno',
                    'u.name',
                    'us.section_name',
                    'l.purpose',
                    'c1.short_description',
                    'EXTRACT(YEAR FROM m.active_fil_dt) AS fyr',
                    'active_reg_year',
                    'active_fil_dt',
                    'm.conn_key',
                    'active_fil_no',
                    'm.pet_name',
                    'm.res_name',
                    'm.pno',
                    'm.rno',
                    'casetype_id',
                    'ref_agency_state_id',
                    'diary_no_rec_date',
                    'remark',
                    'h.diary_no',
                    'h.next_dt',
                    'h.subhead',
                    'h.judges',
                    'h.coram',
                    'h.brd_slno',
                    'h.clno',
                    'h.listorder',
                    'm.reg_no_display'
                ]);

                // FROM heardt
                //$this->db->from('heardt h');

                // JOIN main
                $builder->join('main m', 'm.diary_no = h.diary_no', 'INNER');

                // JOIN master.listing_purpose
                $builder->join('master.listing_purpose l', 'l.code = h.listorder AND l.display = \'Y\'', 'INNER');

                if (!empty($data['courtno']) && $data['courtno'] != 0) {
                    $builder->join('master.roster r', "r.id = h.roster_id AND r.display = 'Y' AND r.courtno = '{$data['courtno']}'", 'INNER');
                }
                else{
                    $builder->join('master.roster r', 'r.id = h.roster_id AND r.display = \'Y\' AND r.courtno = \'2\'', 'INNER');
                }
                // JOIN master.roster                
                

                // LEFT JOIN brdrem
                $builder->join('brdrem br', 'CAST(br.diary_no AS BIGINT) = m.diary_no', 'LEFT');

                // LEFT JOIN master.casetype
                $builder->join('master.casetype c1', 'm.active_casetype_id = c1.casecode', 'LEFT');

                // LEFT JOIN master.users
                $builder->join('master.users u', 'u.usercode = m.dacode AND (u.display = \'Y\' OR u.display IS NULL)', 'LEFT');

                // LEFT JOIN master.usersection
                $builder->join('master.usersection us', 'us.id = u.section AND (us.id = \'20\' OR tentative_section(h.diary_no) = \'II\')', 'LEFT');

                // LEFT JOIN conct
                $builder->join('conct ct', 'm.diary_no = ct.diary_no AND ct.list = \'Y\'', 'LEFT');

                // WHERE conditions
                
                     //$builder->where('r.courtno', $data['courtno']);
                    // $builder->where('h.main_supp_flag', '2');
                    // $builder->where('us.id IS NOT NULL');
                // Uncomment and provide appropriate values for date range
                // $this->db->where('h.next_dt BETWEEN \'--\' AND \'--\'');
                    // $builder->where('h.listorder', '5');
                    // $builder->where('h.board_type', 'J');
                    // $builder->whereIn('h.main_supp_flag', [1, 2]);
                    // $builder->where('h.roster_id >', 0);
                    // $builder->where('m.diary_no IS NOT NULL');
                    // $builder->where('m.c_status', 'P');

                    if (!empty($data['courtno']) && $data['courtno'] != 0) {
                        $builder->where('r.courtno', $data['courtno']);
                    }
                    if (!empty($data['listing_purpose']) && $data['listing_purpose'] != 'all') { 
                        $builder->where('h.listorder', $data['listing_purpose']);
                    }
                    if(!empty($data['main_suppl']) && $data['main_suppl'] != 0 )
                    {
                        $builder->where("h.main_supp_flag", $data['main_suppl']);    
                    } 
                    if (!empty($data['sec_id']) && $data['sec_id'] != 0) {
                        $builder->where('us.id IS NOT NULL')->where('us.id', $data['sec_id']);
                    } 
                    

                // GROUP BY
                    $builder->groupBy('h.diary_no, ct.ent_dt, r.courtno, u.name, us.section_name, l.purpose, c1.short_description, m.active_fil_dt, m.active_reg_year, m.conn_key,
                    m.active_fil_no, m.pet_name, m.res_name,
                    m.pno, m.rno, casetype_id, ref_agency_state_id, m.diary_no_rec_date, br.remark,
                    h.diary_no, h.next_dt, h.subhead, h.judges, h.coram, h.brd_slno, h.clno,
                    h.listorder, m.reg_no_display, h.board_type,us.id');
                    
                    if(!empty($order_by)){
                        $builder->orderBy($order_by .' ASC');
                    }else{
                        $builder->orderBy('r.courtno');                    
                    }                    
                    $builder->limit(40);
                    $query1 = $builder->getCompiledSelect();

                // $query = $builder->get();

                // $result = $query->getResultArray();
                // echo $this->db->getLastQuery();
                // die;
                
               //echo "fsdf";die;
                
                $builder2 = $this->db->table('last_heardt h');
                
                $builder2->select([
                    'ct.ent_dt',
                    'tentative_section(h.diary_no) AS dno',
                    'r.courtno',
                    'u.name',
                    'us.section_name',
                    'l.purpose',
                    'c1.short_description',
                    'EXTRACT(YEAR FROM m.active_fil_dt) AS fyr',
                    'active_reg_year',
                    'active_fil_dt',
                    'm.conn_key',
                    'active_fil_no',
                    'm.pet_name',
                    'm.res_name',
                    'm.pno',
                    'm.rno',
                    'casetype_id',
                    'ref_agency_state_id',
                    'diary_no_rec_date',
                    'remark',
                    'h.diary_no',
                    'h.next_dt',
                    'h.subhead',
                    'h.judges',
                    'h.coram',
                    'h.brd_slno',
                    'h.clno',
                    'h.listorder',
                    'm.reg_no_display'                    
                ]);
                $builder2->join('main m', 'm.diary_no = h.diary_no', 'INNER');

                
                $builder2->join('master.listing_purpose l', 'l.code = h.listorder AND l.display = \'Y\'', 'INNER');

                if (!empty($data['courtno']) && $data['courtno'] != 0) {
                    $builder2->join('master.roster r', "r.id = h.roster_id AND r.display = 'Y' AND r.courtno = '{$data['courtno']}'", 'INNER');
                }
                else{
                    $builder2->join('master.roster r', 'r.id = h.roster_id AND r.display = \'Y\' AND r.courtno = \'2\'', 'INNER');
                }
               
                $builder2->join('brdrem br', 'CAST(br.diary_no AS BIGINT) = m.diary_no', 'LEFT');

                
                $builder2->join('master.casetype c1', 'm.active_casetype_id = c1.casecode', 'LEFT');

                
                $builder2->join('master.users u', 'u.usercode = m.dacode AND (u.display = \'Y\' OR u.display IS NULL)', 'LEFT');

                
                $builder2->join('master.usersection us', 'us.id = u.section AND (us.id = \'20\' OR tentative_section(h.diary_no) = \'II\')', 'LEFT');

                
                $builder2->join('conct ct', 'm.diary_no = ct.diary_no AND ct.list = \'Y\'', 'LEFT');
                if (!empty($data['courtno']) && $data['courtno'] != 0) {
                    $builder2->where('r.courtno', $data['courtno']);
                }
                if (!empty($data['listing_purpose']) && $data['listing_purpose'] != 'all') { 
                    $builder2->where('h.listorder', $data['listing_purpose']);
                }
                if(!empty($data['main_suppl']) && $data['main_suppl'] != 0 )
                {
                    $builder2->where("h.main_supp_flag", $data['main_suppl']);    
                }
                if (!empty($data['sec_id']) && $data['sec_id'] != 0) {
                    $builder2->where('us.id IS NOT NULL')->where('us.id', $data['sec_id']);
                } 
                
                $builder2->groupBy('h.diary_no, ct.ent_dt,r.courtno,u.name, us.section_name, l.purpose, c1.short_description, m.active_fil_dt, m.active_reg_year, m.conn_key,
                    m.active_fil_no, m.pet_name, m.res_name,
                    m.pno, m.rno, casetype_id, ref_agency_state_id, m.diary_no_rec_date, br.remark,
                    h.next_dt, h.subhead, h.judges, h.coram, h.brd_slno, h.clno,
                    h.listorder, m.reg_no_display,h.board_type,us.id');
                
                if(!empty($order_by)){
                    $builder2->orderBy($order_by .' ASC');
                }else{
                    $builder2->orderBy('r.courtno');                    
                }                
                $builder2->limit(40);
                $query2 = $builder2->getCompiledSelect();
                $finalQuery = "({$query1}) UNION ({$query2}) ORDER BY courtno";
                return $result = $this->db->query($finalQuery)->getResultArray();
                //echo $this->db->getLastQuery();die;  



                // $query2 = $builder2->get();
                // echo $this->db->getLastQuery();die;  
                
                // $result = $query2->getResultArray();
                // die;            

                

                // $query = $builder->union($builder2)->get();

                // return $result = $query->getResultArray();
                //echo $this->db->getLastQuery();                


            }

            function getSec_list($data){
                
                $builder = $this->db->table('heardt h')
                                ->select('m.reg_no_display, mc.submaster_id, u.name, us.section_name, s.stagename, l.purpose, c1.casename, c1.short_description, EXTRACT(YEAR FROM m.active_fil_dt) AS fyr, active_reg_year, active_fil_dt, active_fil_no, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date, h.*')
                                ->join('main m', 'm.diary_no = h.diary_no', 'inner')
                                ->join('master.casetype c1', 'm.active_casetype_id = c1.casecode', 'left')
                                ->join('master.listing_purpose l', "l.code = h.listorder AND l.display = 'Y'", 'left')
                                ->join('master.subheading s', "s.stagecode = h.subhead AND s.display = 'Y' AND s.listtype = 'M'", 'left')
                                ->join('rgo_default rd', "rd.fil_no = h.diary_no AND rd.remove_def = 'N'", 'left')
                                ->join('mul_category mc', "mc.diary_no = m.diary_no AND mc.display = 'Y'", 'left')
                                ->join('master.users u', "u.usercode = m.dacode AND u.display = 'Y'", 'left')
                                ->join('master.usersection us', "us.id = u.section AND us.id = '21'", 'left')
                                ->where('mc.display', 'Y')
                                ->where('h.mainhead', 'M')
                                ->where('h.board_type', 'J')
                                ->where('us.id IS NOT NULL')                                                               
                                ->where('m.c_status', 'P')
                                ->where('h.listorder !=', 32)
                                ->where("COALESCE(NULLIF(h.diary_no::text, ''), '0') != '0'")
                                ->orderBy('l.priority')
                                ->orderBy('CAST(h.diary_no AS BIGINT)');
                                
                if (!empty($data['sec_id']) && $data['sec_id'] != 0) {
                    $builder->where('us.id IS NOT NULL')->where('us.id', $data['sec_id']);
                }
                if(!empty($data['ldates'] && $data['ldates'] != 0)){ 
                    $builder->where('h.tentative_cl_dt', $data['ldates']);                    
                }

                return $result = $builder->get()->getResultArray();
                //$result = $builder->getResultArray();
                //echo $this->db->getLastQuery();die;
            }

            function getVac_list($data)
            {                
                $query = $this->db->table('main m')
                                ->select("
                                    m.diary_no,
                                    u.name AS section_name,
                                    tentative_section(m.diary_no) AS section_name,
                                    m.active_fil_no,
                                    m.active_reg_year,
                                    m.casetype_id,
                                    m.active_casetype_id,
                                    m.ref_agency_state_id,
                                    m.reg_no_display,
                                    EXTRACT(YEAR FROM m.fil_dt) AS fil_year,
                                    m.fil_no,
                                    m.conn_key AS main_key,
                                    m.fil_dt,
                                    m.fil_no_fh,
                                    m.reg_year_fh AS fil_year_f,
                                    m.mf_active,
                                    m.pet_name,
                                    m.res_name,
                                    m.pno,
                                    m.rno,
                                    m.diary_no_rec_date,
                                    CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3) AS INTEGER) AS last_digits,
                                    CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER) AS first_digits
                                ")
                                ->join('vacation_registrar_not_ready_cl v', 'v.diary_no = m.diary_no', 'inner')
                                ->join('heardt h', 'h.diary_no = m.diary_no', 'left')
                                ->join('master.users u', 'u.usercode = m.dacode AND u.display = \'Y\' ', 'left')
                                ->join('master.usersection us', "us.id = u.section", 'left')
                                //->where('m.diary_no', $data['sec_id'])
                                ->where('v.display', 'Y')
                                //->where('v.list_dt', date('Y-m-d',strtotime($data['ldates'])))
                                //->where('v.reg_jcode', $data['reg_code'])
                                ->where('m.c_status', 'P')
                                ->notLike('m.lastorder', '%Heard & Reserved%')
                                ->whereNotIn('h.subhead', [818, 819])
                                //->groupStart()
                                //     ->orWhere('m.diary_no::text', 'm.conn_key::text', false)
                                //     ->orWhere('CAST(m.conn_key AS TEXT) = ?', '0', false)
                                //     ->orWhere('m.conn_key', '')
                                //     ->orWhere('m.conn_key IS NULL', null, false)
                                // ->groupEnd()
                                ->orderBy('last_digits', 'ASC')
                                ->orderBy('first_digits', 'ASC')
                                ->distinct();
                        if (!empty($data['sec_id']) && $data['sec_id'] != 0) {
                            $query->where('us.id IS NOT NULL')->where('us.id', $data['sec_id']);
                        }
                        if (!empty($data['reg_code']) && $data['reg_code'] != 0) {
                            $query->where('v.reg_jcode', $data['reg_code']);
                        }
                        if (!empty($data['ldates']) && $data['ldates'] != 0) {
                            $query->where('v.list_dt', $data['ldates']);
                        }                                        

                        //$result = $query->getResultArray();
                        return $result = $query->get()->getResultArray();            
                        //echo $this->db->getLastQuery();die;
            }


            function order_type(){
                $query = $this->db->table('master.ref_order_type')->select('*')->get();
                return $query->getResultArray();
            }

            function getDailycourtRemark(){

            }

            public function getAorwise_Matter($data){
                $query = $this->db->table('master.users u')
                ->select('t1.usercode, t1.name, empid, us."section_name", ut.type_name, u.section, u.usertype')
                ->join('master.usersection us', 'u.section = us.id', 'left')
                ->join('master.usertype ut', 'u.usertype = ut.id', 'left')
                ->where('isda', 'Y')
                ->where('u.display', 'Y')
                ->where('us.display', 'Y')
                ->whereIn('usertype', [17, 50, 51])
                ->union(
                    $this->db->table('master.users u')
                        ->select('t1.usercode, t1.name, empid, us."section_name", ut.type_name, u.section, u.usertype')
                        ->join('master.usersection us', 'u.section = us.id', 'left')
                        ->join('master.usertype ut', 'u.usertype = ut.id', 'left')
                        ->where('isda', 'Y')
                        ->where('u.display', 'Y')
                        ->where('us.display', 'Y')
                        ->whereIn('usertype', [17, 50, 51])
                        ->get()
                )
                ->getTempTable('t1');
            
            $subquery2 = $this->db->table('ld_move a')
                ->select('COUNT(*) as totdoc, dacode')
                ->join('docdetails d', 'a.diary_no = d.diary_no AND a.diary_no > 0 AND d.diary_no > 0 AND a.doccode = d.doccode AND a.doccode1 = d.doccode1 AND a.docnum = d.docnum AND a.docyear = d.docyear AND d.display = "Y" AND a.rece_by = 0', 'inner')
                ->join('main m', 'd.diary_no = m.diary_no', 'inner')
                ->join('master.docmaster dm', 'd.doccode = dm.doccode AND d.doccode1 = dm.doccode1', 'left')
                ->groupBy('disp_to, m.dacode');
            
            $query = $this->db->table($query)
                ->select('t2.*')
                ->joinSub($subquery2, 't2', 't1.usercode = t2.dacode', 'left');
            
            // Add more left joins for other subqueries in a similar manner
            
            $result = $query->orderBy('section_name')
                ->orderBy('usertype')
                ->get();
            

return $query->getResultArray();

    
    }


    function getorUplodStatus($date){

        $query = $this->db->table('main m')
        ->select('m.diary_no')
        ->select("CONCAT(SUBSTRING(CAST(m.diary_no AS TEXT), 1, LENGTH(m.diary_no::TEXT) - 4), ' / ', SUBSTRING(CAST(m.diary_no AS TEXT), -4)) AS d_no")
        ->select("CONCAT(pet_name, ' Vs. ', res_name) AS cause_title")
        ->select('(SELECT m.diary_no) AS user_section', false) // Corrected the SELECT statement
    
        ->select('m.reg_no_display')
        ->select('u.name AS DA_Name')
        ->select('o.web_status AS web_status')
        //->select('CASE WHEN o.web_status=1 THEN "Upload" ELSE "Not Upload" END AS web_status')
        ->select('Rt.courtno')
        ->select('CASE WHEN cl.next_dt IS NULL THEN \'0\' ELSE h.brd_slno END AS brd_prnt')
        ->join('master.users u', 'u.usercode = m.dacode AND (u.display = \'Y\' OR u.display IS NULL)', 'left')
        ->join('master.usersection us', 'us.id = u.section AND (us.display = \'Y\' OR us.display IS NULL)', 'left')
        ->join('heardt h', 'm.diary_no = h.diary_no AND roster_id != 0 ', 'left') //  AND h.next_dt = "2023-02-13"
        ->join('office_report_details o', 'o.diary_no = h.diary_no AND (o.display = \'Y\' OR o.display IS NULL) ', 'left')//AND (o.order_dt = "2023-02-13" OR o.order_dt IS NULL)
        ->join('master.roster Rt', 'Rt.id = h.roster_id', 'inner')
        ->join('cl_printed cl', 'cl.next_dt = h.next_dt AND cl.m_f = h.mainhead AND cl.part = h.clno AND cl.main_supp = h.main_supp_flag AND cl.roster_id = h.roster_id AND cl.display = \'Y\'', 'inner')
        ->where('h.next_dt', $date)
        ->orderBy('user_section', 'ASC');
    
    return $results = $query->get()->getResultArray();
    //echo $this->db->getLastQuery();
    


    }

    function getAdvocate_list($data){

        $query = $this->db->table('main m')
        ->distinct()
        ->select('m.diary_no, h.next_dt, u.name')
        ->select('(CASE WHEN us.section_name IS NOT NULL THEN us.section_name ELSE tentative_section(m.diary_no) END) AS section_name')
        ->select('m.conn_key AS main_key, l.purpose, s.stagename, h.coram, c1.short_description, active_fil_no, m.active_reg_year')
        ->select('m.casetype_id, m.active_casetype_id, m.ref_agency_state_id, m.reg_no_display, EXTRACT(YEAR FROM m.fil_dt) AS fil_year')
        ->select('m.fil_no, m.fil_dt, m.fil_no_fh, m.reg_year_fh AS fil_year_f, m.mf_active, m.pet_name, m.res_name, m.lastorder, pno, rno, m.diary_no_rec_date')
        ->select('(CASE WHEN m.diary_no::text = m.conn_key OR m.conn_key = \'0\' OR m.conn_key = \'\' OR m.conn_key IS NULL THEN 0 ELSE 1 END) AS main_or_connected')
        ->select('(CASE WHEN EXISTS (SELECT 1 FROM conct WHERE diary_no = m.diary_no AND LIST = \'Y\') THEN 1 ELSE 0 END) AS listed')
        ->select("TO_CHAR(tt.ent_dt, 'DD-MM-YYYY HH12:MI AM') AS verified_on")
        ->select("REPLACE(array_to_string(array_agg(tt.remark_id), E'\\n'), ',', '') AS remarks_by_monitoring")
        ->select("(SELECT CONCAT(name, '(', empid, ')') FROM master.users WHERE usercode = m.dacode) AS verified_by")
        ->join('heardt h', 'h.diary_no = m.diary_no')
        ->join('case_verify tt', 'tt.diary_no = h.diary_no')
        ->join('master.users u', 'u.usercode = m.dacode AND u.display = \'Y\'', 'left')
        ->join('master.usersection us', 'us.id = u.section', 'left')
        ->join('master.listing_purpose l', 'l.code = h.listorder AND l.display = \'Y\'', 'left')
        ->join('master.subheading s', 's.stagecode = h.subhead AND s.display = \'Y\' AND s.listtype = \'M\'', 'left')
        ->join('master.casetype c1', 'active_casetype_id = c1.casecode', 'left')
        ->where('tt.display', 'Y')
        ->where('DATE_TRUNC(\'day\', h.next_dt)', $data['ldate'])
        ->groupBy('m.diary_no, h.next_dt, u.name, us.section_name, m.conn_key, l.purpose, s.stagename, h.coram, c1.short_description, tt.ent_dt')
    
    
        ->limit('500');
    
        
    return $results = $query->get()->getResultArray();
    }

    function get_advlist2($data){
        $query = $this->db->table('main m')
                        ->distinct()
                        ->select('m.diary_no')
                        ->select('h.next_dt')
                        ->select('u.name')
                        ->select('(CASE WHEN us.section_name IS NOT NULL THEN us.section_name ELSE tentative_section(m.diary_no) END) AS section_name', false)
                        ->select('m.conn_key AS main_key')
                        ->select('l.purpose')
                        ->select('s.stagename')
                        ->select('h.coram')
                        ->select('c1.short_description')
                        ->select('active_fil_no')
                        ->select('m.active_reg_year')
                        ->select('m.casetype_id')
                        ->select('m.active_casetype_id')
                        ->select('m.ref_agency_state_id')
                        ->select('m.reg_no_display')
                        ->select('EXTRACT(YEAR FROM m.fil_dt) AS fil_year', false)
                        ->select('m.fil_no')
                        ->select('m.fil_dt')
                        ->select('m.fil_no_fh')
                        ->select('m.reg_year_fh AS fil_year_f')
                        ->select('m.mf_active')
                        ->select('m.pet_name')
                        ->select('m.res_name')
                        ->select('m.lastorder')
                        ->select('pno')
                        ->select('rno')
                        ->select('m.diary_no_rec_date')
                        ->select('(CASE WHEN m.diary_no::text = m.conn_key OR m.conn_key = \'0\' OR m.conn_key = \'\' OR m.conn_key IS NULL THEN 0 ELSE 1 END) AS main_or_connected', false)
                        ->select('(CASE WHEN EXISTS (SELECT 1 FROM conct WHERE diary_no = m.diary_no AND LIST = \'Y\') THEN 1 ELSE 0 END) AS listed', false)
                        ->select('TO_CHAR(tt.ent_dt, \'DD-MM-YYYY HH12:MI AM\') AS verified_on', false)
                        ->select('(SELECT REPLACE(array_to_string(array(SELECT remarks FROM master.case_verify_by_sec_remark WHERE id::text = ANY(string_to_array(tt.remark_id, \',\'))), \',\'), \',\', \'\')) AS remarks_by_monitoring', false)
                        ->select('(SELECT CONCAT(name, \'(\', empid, \')\') FROM master.users WHERE usercode = tt.ucode) AS verified_by', false)
                        ->join('heardt h', 'h.diary_no = m.diary_no')
                        ->join('case_verify tt', 'tt.diary_no = h.diary_no')
                        ->join('master.users u', 'u.usercode = m.dacode AND u.display = \'Y\'', 'left')
                        ->join('master.usersection us', 'us.id = u.section', 'left')
                        ->join('master.listing_purpose l', 'l.code = h.listorder AND l.display = \'Y\'', 'left')
                        ->join('master.subheading s', 's.stagecode = h.subhead AND s.display = \'Y\' AND s.listtype = \'M\'', 'left')
                        ->join('master.casetype c1', 'active_casetype_id = c1.casecode', 'left')
                        ->where('tt.display', 'Y')
                        ->where('DATE(h.next_dt)', $data['ldate'])
                        ->limit('500');

          return $results = $query->get()->getResultArray();
          //echo $this->db->getLastQuery();

    }

    function getWork_done($data){

//         $this->db->query('
//        SELECT *
// FROM (
//     SELECT
//         u.usercode,
//         u.name,
//         empid,
//         us."section_name",
//         ut.type_name,
//         u.section,
//         u.usertype
//     FROM
//         master.users u
//     LEFT JOIN master.usersection us ON section = us.id
//     LEFT JOIN master.usertype ut ON usertype = ut.id
//     WHERE
//         isda = "Y"
//         AND u.display = "Y"
//         AND us.display = "Y"
//         AND usertype IN (17, 50, 51)
    
//     UNION
    
//     SELECT
//         u.usercode,
//         u.name,
//         empid,
//         us."section_name",
//         ut.type_name,
//         u.section,
//         u.usertype
//     FROM
//         master.users u
//     LEFT JOIN master.usersection us ON section = us.id
//     LEFT JOIN master.usertype ut ON usertype = ut.id
//     WHERE
//         isda = "Y"
//         AND u.display = "Y"
//         AND us.display = "Y"
//         AND usertype IN (17, 50, 51)
// ) as t1
// LEFT JOIN (
//     SELECT
//         COUNT(*) as totdoc,
//         dacode
//     FROM
//         ld_move a
//     INNER JOIN docdetails d ON (
//         a.diary_no = d.diary_no
//         AND a.diary_no > 0
//         AND d.diary_no > 0
//         AND a.doccode = d.doccode
//         AND a.doccode1 = d.doccode1
//         AND a.docnum = d.docnum
//         AND a.docyear = d.docyear
//         AND d.display = "Y"
//         AND a.rece_by = 0
//     )
//     INNER JOIN main m ON d.diary_no = m.diary_no
//     LEFT JOIN master.docmaster dm ON (
//         d.doccode = dm.doccode
//         AND d.doccode1 = dm.doccode1
//     )
//     GROUP BY
//         disp_to, m.dacode
// ) as t2 ON t1.usercode = dacode
// LEFT JOIN (
//     SELECT
//         COUNT(*) as totup,
//         usercode as daheardt
//     FROM
//         (
//         SELECT
//             diary_no,
//             usercode
//         FROM
//             heardt
//         UNION
//         SELECT
//             diary_no,
//             usercode
//         FROM
//             last_heardt
//         GROUP BY
//             diary_no, usercode
//         ) as t1
//     GROUP BY
//         usercode
// ) as t3 ON t1.usercode = daheardt
// LEFT JOIN (
//     SELECT
//         DISTINCT m.dacode as dddcc,
//         SUM(CASE WHEN t1.usercode = 1 THEN 1 ELSE 0 END) as supuser
//     FROM
//         (
//         SELECT
//             diary_no,
//             usercode
//         FROM
//             heardt
//         UNION
//         SELECT
//             diary_no,
//             usercode
//         FROM
//             last_heardt
//         GROUP BY
//             diary_no, usercode
//         ) as t1
//     LEFT JOIN main m ON t1.diary_no = m.diary_no
//     LEFT JOIN master.users u ON u.usercode = m.dacode
//     WHERE
//         u.usertype IN (17, 50, 51)
//         AND u.display = "Y"
//     GROUP BY
//         m.dacode
// ) as t3a ON t1.usercode = dddcc
// LEFT JOIN (
//     SELECT
//         COUNT(*) as totoff,
//         rec_user_id
//     FROM
//         office_report_details
//     GROUP BY
//         rec_user_id
// ) as t4 ON t1.usercode = rec_user_id
// LEFT JOIN (
//     SELECT
//         COUNT(*) as totnot,
//         user_id
//     FROM
//         tw_tal_del
//     GROUP BY
//         user_id
// ) as t5 ON t1.usercode = user_id
// LEFT JOIN (
//     SELECT
//         dacode as rogy_da,
//         COUNT(distinct total) as total_tt,
//         COUNT(distinct red) as red,
//         COUNT(distinct orange) as orange,
//         COUNT(distinct green) as green,
//         COUNT(distinct yellow) as yellow
//     FROM
//         (
//         SELECT
//             empid,
//             dacode,
//             name,
//             type_name,
//             section_name,
//             m.diary_no as total,
//             CASE
//                 WHEN (
//                     CURRENT_DATE = CURRENT_DATE
//                 ) OR (
//                     h.mainhead = "M"
//                     AND s.listtype = "M"
//                     AND s.listtype IS NOT NULL
//                     AND s.display = "Y"
//                     AND s.display IS NOT NULL
//                 ) OR (
//                     h.mainhead = "S"
//                     AND s.listtype = "S"
//                     AND s.listtype IS NOT NULL
//                     AND s.display = "Y"
//                     AND s.display IS NOT NULL
//                 ) AND (
//                     main_supp_flag = 0
//                     AND clno = 0
//                     AND brd_slno = 0
//                     AND (judges = ''
//                     OR judges = 0)
//                     AND roster_id = 0
//                 ) OR (
//                     next_dt >= CURRENT_DATE
//                 ) AND (
//                     lastorder NOT LIKE "%Not Reached%"
//                     AND lastorder NOT LIKE "%Case Not Receive%"
//                     AND lastorder NOT LIKE "%Heard & Reserved%"
//                     OR lastorder IS NULL
//                 ) AND (
//                     head_code != 5
//                     OR head_code IS NULL
//                 ) AND m.diary_no NOT IN (
//                     SELECT
//                         diary_no
//                     FROM
//                         heardt
//                     WHERE
//                         main_supp_flag = 3
//                         AND usercode IN (559, 469)
//                     UNION
//                     SELECT
//                         fil_no as diary_no
//                     FROM
//                         rgo_default
//                     WHERE
//                         remove_def != "Y"
//                 )
//             THEN
//                 m.diary_no
//             END AS red,
//             CASE
//                 WHEN (
//                     CURRENT_DATE = CURRENT_DATE
//                 ) AND (
//                     h.mainhead = "M"
//                     AND s.listtype = "M"
//                     AND s.listtype IS NOT NULL
//                     AND s.display = "Y"
//                     AND s.display IS NOT NULL
//                 ) OR (
//                     h.mainhead = "S"
//                     AND s.listtype = "S"
//                     AND s.listtype IS NOT NULL
//                     AND s.display = "Y"
//                     AND s.display IS NOT NULL
//                 ) AND (
//                     main_supp_flag = 0
//                     AND clno = 0
//                     AND brd_slno = 0
//                     AND (judges = 0
//                     OR judges = 0)
//                     AND roster_id = 0
//                 ) OR (
//                     next_dt >= CURRENT_DATE
//                 ) AND (
//                     lastorder NOT LIKE "%Not Reached%"
//                     AND lastorder NOT LIKE "%Case Not Receive%"
//                     AND lastorder NOT LIKE "%Heard & Reserved%"
//                     OR lastorder IS NULL
//                 ) AND (
//                     head_code != 5
//                     OR head_code IS NULL
//                 ) AND m.diary_no NOT IN (
//                     SELECT
//                         diary_no
//                     FROM
//                         heardt
//                     WHERE
//                         main_supp_flag = 3
//                         AND usercode IN (559, 469)
//                     UNION
//                     SELECT
//                         fil_no as diary_no
//                     FROM
//                         rgo_default
//                     WHERE
//                         remove_def != "Y"
//                 )
//             THEN
//                 m.diary_no
//             END AS orange,
//             CASE
//                 WHEN (
//                     h.mainhead = "M"
//                     AND s.listtype = "M"
//                     AND s.listtype IS NOT NULL
//                     AND s.display = "Y"
//                     AND s.display IS NOT NULL
//                 ) OR (
//                     h.mainhead = "S"
//                     AND s.listtype = "S"
//                     AND s.listtype IS NOT NULL
//                     AND s.display = "Y"
//                     AND s.display IS NOT NULL
//                 ) AND (
//                     main_supp_flag = 0
//                     AND clno = 0
//                     AND brd_slno = 0
//                     AND (judges = NULL
//                     OR judges = 0)
//                     AND roster_id = 0
//                 ) OR (
//                     next_dt >= CURRENT_DATE
//                 ) OR (
//                     lastorder LIKE "%Not Reached%"
//                     OR lastorder LIKE "%Case Not Receive%"
//                     OR lastorder LIKE "%Heard & Reserved%"
//                 ) OR head_code = 5
//                 AND m.diary_no NOT IN (
//                     SELECT
//                         diary_no
//                     FROM
//                         heardt
//                     WHERE
//                         main_supp_flag = 3
//                         AND usercode IN (559, 469)
//                     UNION
//                     SELECT
//                         fil_no as diary_no
//                     FROM
//                         rgo_default
//                     WHERE
//                         remove_def != "Y"
//                 )
//             THEN
//                 m.diary_no
//             END AS green,
//             CASE
//                 WHEN (h.main_supp_flag = 3
//                 AND h.usercode IN (559, 469))
//                 OR rd.remove_def != "Y" THEN m.diary_no
//             END AS yellow
//         FROM
//             main m
//         INNER JOIN master.casetype c ON (
//             c.casecode = COALESCE(m.active_casetype_id,
//             casetype_id)
//         )
//         LEFT JOIN heardt h ON m.diary_no = h.diary_no
//         LEFT JOIN master.users u ON m.dacode = u.usercode
//         LEFT JOIN master.usertype ut ON ut.id = u.usertype
//         LEFT JOIN rgo_default rd ON m.diary_no = rd.fil_no
//         LEFT JOIN master.usersection b ON b.id = u.section
//         LEFT JOIN master.subheading s ON h.subhead = s.stagecode
//         WHERE
//             c_status = "P"
//     ) as a
//     GROUP BY
//         empid,
//         dacode,
//         name,
//         type_name,
//         section_name
//     ORDER BY
//         section_name,
//         type_name
// ) as t6 ON t1.usercode = t6.rogy_da
// LEFT JOIN (
//     SELECT
//         COUNT(a.diary_no) as d_notice_not_made,
//         m.dacode as d_notice_not_made_da
//     FROM
//         (
//         SELECT
//             diary_no,
//             MAX(cl_date) as cl_dt
//         FROM
//             case_remarks_multiple
//         WHERE
//             cl_date > "2018-01-01"
//             AND status = "D"
//         GROUP BY
//             diary_no
//         ) as a
//     LEFT JOIN tw_tal_del t ON (
//         t.diary_no = a.diary_no
//         AND t.rec_dt > cl_dt
//         AND t.display = "Y"
//     )
//     LEFT JOIN main m ON m.diary_no = a.diary_no
//     WHERE
//         t.diary_no IS NULL
//     GROUP BY
//         m.dacode
// ) as t7 ON t1.usercode = d_notice_not_made_da
// LEFT JOIN (
//     SELECT
//         COUNT(a.diary_no) as p_notice_not_made,
//         m.dacode as p_notice_not_made_da
//     FROM
//         (
//         SELECT
//             diary_no,
//             MAX(cl_date) as cl_dt
//         FROM
//             case_remarks_multiple
//         WHERE
//             cl_date > "2018-01-01"
//             AND r_head IN (3, 9, 113, 181, 182, 183, 184)
//         GROUP BY
//             diary_no
//         ) as a
//     LEFT JOIN tw_tal_del t ON (
//         t.diary_no = a.diary_no
//         AND t.rec_dt > cl_dt
//         AND t.display = "Y"
//     )
//     LEFT JOIN main m ON m.diary_no = a.diary_no
//     WHERE
//         t.diary_no IS NULL
//     GROUP BY
//         m.dacode
// ) as t8 ON t1.usercode = p_notice_not_made_da
// LEFT JOIN (
//     SELECT
//         COUNT(*) as totdoc_not,
//         disp_to as dacode_not_veri
//     FROM
//         ld_move a
//     INNER JOIN docdetails d ON (
//         a.diary_no = d.diary_no
//         AND a.diary_no > 0
//         AND d.diary_no > 0
//         AND a.doccode = d.doccode
//         AND a.doccode1 = d.doccode1
//         AND a.docnum = d.docnum
//         AND a.docyear = d.docyear
//         AND d.display = "Y"
//     )
//     INNER JOIN main m ON d.diary_no = m.diary_no
//     WHERE
//         (verified IS NULL OR verified = NULL)
//         AND d.iastat = "P"
//         AND d.display = "Y"
//     GROUP BY
//         disp_to, m.dacode
// ) as t9 ON t1.usercode = dacode_not_veri
// ORDER BY
//     section_name,
//     usertype');


    }

    function field_sel_roster_dts(){
        $builder = $this->db->table('heardt c')
                        ->select('c.next_dt')
                            ->where('c.mainhead', 'F')
                            ->where('next_dt >=', date('Y-m-d'))
                            ->whereIn('main_supp_flag', ['1', '2'])                        
                        ->groupBy('next_dt')
                        ->get();    
        return $result = $builder->getResultArray();
        //echo $this->db->getLastQuery();die;
    }

    function field_sel_roster_dts_custom(){
        $builder = $this->db->table('heardt c')
                        ->select('c.next_dt')
                            ->where('c.mainhead', 'F')
                            ->where('c.next_dt >=', date('Y-m-d', strtotime('-90 days')))
                            ->whereIn('main_supp_flag', ['1', '2'])                        
                        ->groupBy('next_dt')
                        ->get();    
    return $result = $builder->getResultArray();
    //echo $this->db->getLastQuery();die;
    }


}


