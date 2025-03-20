<?php

namespace App\Models\PaperBook;

use CodeIgniter\Model;

class DraftListModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    public function getCauseListDate()
    {
        $builder = $this->db->table('heardt c');
        $builder->select('c.next_dt')
                ->where('c.mainhead', 'M')
                ->where('c.next_dt >=', date('Y-m-d'))
                ->whereIn('c.main_supp_flag', ['1', '2'])
                ->groupBy('c.next_dt');

        
        $query = $builder->get();
        $results = $query->getResultArray();
        return $results;
    }

    public function getUserType($userCode)
    {
        $query = $this->db->table('master.users')
        ->select('usertype')
        ->where('usercode', $userCode)
        ->get();
        return $query->getRowArray();
    }

    public function getCaseType($list_type, $ucode, $utype, $ma, $cl_date)
    {
        $results['serve_status'] = [];
        $results['title'] = '';
        $ct = 0;
        if ($list_type == 1) {
            if (($ucode != 1) && ($utype != 14) && ($ucode != 630)) {
                $builder = $this->db->table('master.godown_user_allocation');
                $builder->select('STRING_AGG(casetype_id::text, \',\') as ct')
                        ->where('usercode', $ucode)
                        ->whereIn('casetype_id', [1, 3, 5, 7, 11, 13, 15, 17, 19, 21, 22, 23, 24, 25, 27, 32, 34, 40, 31])
                        ->where('caseyear', date('Y'));

                $query = $builder->get();
                $row_matters = $query->getRow();
                $ct = $row_matters ? $row_matters->ct : null;
                if($ct == null) {
                    echo " NO Fresh  Matters  ";
                    exit();
                }
            } else {
                $ct = $ma == 1 
                    ? '1,3,5,7,11,13,23,32,34,39,40,9,19,25' 
                    : '1,3,5,7,11,13,23,32,34,40,39';
            }

            $results['title'] = "LIST OF FRESH CIVIL MATTERS";
        }

        if ($list_type == 2) {
            if (($ucode != 1) && ($utype != 14) && ($ucode != 630)) {
                $builder = $this->db->table('master.godown_user_allocation');
                $builder->select('STRING_AGG(casetype_id::text, \',\') as ct')
                        ->where('usercode', $ucode)
                        ->whereNotIn('casetype_id', [1, 3, 5, 7, 11, 13, 23, 32, 34, 40, 9, 19, 25])
                        ->where('caseyear', date('Y'));

                $query = $builder->get();
                $row_matters = $query->getRow();
                $ct = $row_matters ? $row_matters->ct : null;
                if($ct == null) {
                    echo " NO Fresh  Matters  ";
                    exit();
                }
            } else {
                $ct = $ma == 1 
                    ? '2,4,6,8,12,14,33,35,41,10,20,26,39' 
                    : '2,4,6,8,12,14,33,35,41,39';
            }
            $results['title'] = "LIST OF FRESH CRIMINAL MATTERS";
        }
        
        if ($list_type == 3) {
            if (($ucode != 1) && ($utype != 14) && ($ucode != 630)) {
                
                echo "Matters can be shown in Branch Officer Login";
                exit();
            }

            $ctQuery = $this->db->table('master.godown_user_allocation')
                ->select('STRING_AGG(casetype_id::text, \',\') as ct')
                ->where('usercode', $ucode)
                ->whereIn('casetype_id', [1, 3, 5, 7, 9, 11, 13, 15, 17, 19, 21, 22, 23, 24, 25, 27, 32, 34, 40, 31])
                ->where('caseyear', date('Y'))
                ->get();
            $ct = $ctQuery->getRow()->ct;
            if ($ma == 1) {
                $ct = '1,3,5,7,11,13,23,32,34,40,9,19,25,39';
            } else {
                $ct = '1,3,5,7,11,13,23,32,34,40,39';
            }
            $results['title'] = "LIST OF FRESH DIARY CIVIL MATTERS";
        }

        if ($list_type == 4) {
            if (($ucode != 1) && ($utype != 14) && ($ucode != 630)) {
                
                echo "Matters can be shown in Branch Officer Login";
                exit();
            }
            $ct = 0;
            $ctQuery = $this->db->table('master.godown_user_allocation')
                ->select('STRING_AGG(casetype_id::text, \',\') as ct')
                ->where('usercode', $ucode)
                ->whereIn('casetype_id', explode(',', $ct))
                ->where('caseyear', date('Y'))
                ->get();

            $ct = $ctQuery->getRow()->ct;
            if ($ma == 1) {
                $ct = '2,4,6,8,12,14,33,35,41,10,20,26,39';
            } else {
                $ct = '2,4,6,8,12,14,33,35,41,39';
            }
            $results['title'] = "LIST OF FRESH DIARY CRIMINAL MATTERS";
        }

        $results['serve_status'] = $this->getServeStatus($ct, $cl_date);
        return $results;
    }

    public function getServeStatus($ct=0, $cl_date=null)
    {
        $results = [];
        if(!empty($ct) && !empty($cl_date)) {
            $subQuery = $this->db->table('heardt h')
            ->select("
                p.id AS is_printed, 
                casecode,
                r.courtno, 
                us.id, 
                COALESCE(u.name, tentative_da(m.diary_no)) as name, 
                m.casetype_id, 
                COALESCE(us.section_name, tentative_section(m.diary_no)) as section_name, 
                l.purpose, 
                c1.short_description, 
                EXTRACT(YEAR FROM m.active_fil_dt) AS fyr, 
                m.active_reg_year, 
                m.active_fil_dt, 
                m.reg_no_display, 
                m.active_fil_no, 
                m.pet_name, 
                m.res_name, 
                m.pno, 
                m.rno, 
                m.ref_agency_state_id,
                m.diary_no_rec_date, 
                m.active_casetype_id,
        
                h.*
            ")
            ->join('main m', 'm.diary_no = h.diary_no')
            ->join('master.listing_purpose l', 'l.code = h.listorder AND l.display = \'Y\'', 'INNER')
                ->join('master.roster r', 'r.id = h.roster_id AND h.board_type = \'J\' AND r.display = \'Y\'', 'INNER')
                ->join('cl_printed p', 'p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = \'Y\'', 'LEFT')
                ->join('master.casetype c1', 'm.active_casetype_id = c1.casecode', 'LEFT')
                ->join('master.users u', 'u.usercode = m.dacode AND u.display = \'Y\'', 'LEFT')
                ->join('master.usersection us', 'us.id = u.section', 'LEFT')
                ->where('m.active_casetype_id IN (' . $ct . ')')
                ->where('h.mainhead', 'M')
                ->where('h.next_dt', $cl_date)
                ->whereIn('h.main_supp_flag', [1, 2])
                ->where('m.c_status', 'P')
                ->where('h.clno >', 0)
                ->where('h.brd_slno >', 0)
                ->where('h.roster_id >', 0)
                ->where('m.diary_no IS NOT NULL')
                ->groupBy('h.diary_no, u.name,m.diary_no,r.courtno,us.id,p.id,c1.short_description,c1.casecode,l.purpose');

            // Execute the subquery
            $subQueryResult = $subQuery->getCompiledSelect();

            $builder = $this->db->table("($subQueryResult) j")
            ->select('j.*')
            ->join('last_heardt l', 'j.diary_no = l.diary_no AND j.next_dt != l.next_dt AND cast(l.judges as integer) != 0 AND l.judges IS NOT NULL AND l.brd_slno > 0 AND (l.bench_flag IS NULL)', 'LEFT')
                ->where('l.diary_no IS NULL')
                ->orderBy('j.casecode, j.active_fil_no', 'ASC');
                $results = $builder->get()->getResultArray();
                /*$radvname = $padvname = '';
                foreach ($results as $index => $result) {
                    $results[$index]['sno'] = $index + 1; // Serial number starting from 1
                    
                    $advocate = $this->getAdvocateData($result['diary_no']);
                    //pr($advocate);
                    if(!empty($advocate)) {
                        $radvname = $advocate["r_n"];
                        $padvname = $advocate["p_n"];
                    }
                }*/    
        }
        
        return $results;
    }

    public function diaryCivilMatters($ct=0, $cl_date=null)
    {
        $results = [];
        if(!empty($ct) && !empty($cl_date)){
            $subquery = $this->db->table('heardt h')
                ->select('p.id AS is_printed, docnum, docyear, casecode, r.courtno, us.id, 
                        COALESCE(u.name, tentative_da(m.diary_no)) AS name, 
                        COALESCE(us.section_name, tentative_section(m.diary_no)) AS section_name, 
                        l.purpose, c1.short_description, 
                        EXTRACT(YEAR FROM m.active_fil_dt) AS fyr, 
                        active_reg_year, active_fil_dt, reg_no_display, active_fil_no, 
                        m.pet_name, m.res_name, m.pno, m.rno, 
                        casetype_id, ref_agency_state_id, diary_no_rec_date, h.*')
                ->join('main m', 'm.diary_no = h.diary_no')
                ->join('master.listing_purpose l', 'l.code = h.listorder AND l.display = \'Y\'')
                ->join('master.roster r', 'r.id = h.roster_id AND r.display = \'Y\'')
                ->join('cl_printed p', 'p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = \'Y\'', 'left')
                ->join('master.casetype c1', 'm.active_casetype_id = c1.casecode', 'left')
                ->join('docdetails', 'h.diary_no = docdetails.diary_no AND iastat = \'P\' AND doccode = 8', 'left')
                ->join('master.users u', 'u.usercode = m.dacode AND u.display = \'Y\'', 'left')
                ->join('master.usersection us', 'us.id = u.section', 'left')
                ->where('h.mainhead', 'M')
                ->whereIn('casetype_id', explode(',', $ct))
                ->where('h.next_dt', $cl_date)
                ->groupStart()
                    ->where('active_reg_year', 0)
                    ->orWhere('active_reg_year IS NULL')
                ->groupEnd()
                ->groupStart()
                    ->where('h.main_supp_flag', 1)
                    ->orWhere('h.main_supp_flag', 2)
                ->groupEnd()
                ->where('m.c_status', 'P')
                ->where('h.clno >', 0)
                ->where('h.brd_slno >', 0)
                ->where('board_type', 'J')
                ->where('h.roster_id >', 0)
                ->where('m.diary_no IS NOT NULL')
                ->groupBy('h.diary_no,p.id,docdetails.docnum,docdetails.docyear,c1.casecode,r.courtno,us.id,u.name,m.diary_no,l.purpose,c1.short_description');
            $subQueryResult = $subquery->getCompiledSelect();
            //pr($subQueryResult);
            
            $serve_status_query = $this->db->table("($subQueryResult) j")
                ->select('j.*')
                ->join('last_heardt l', 'j.diary_no = l.diary_no AND j.next_dt != l.next_dt AND cast(l.judges as integer) != 0 AND l.judges IS NOT NULL AND l.brd_slno > 0 AND (l.bench_flag = \'\' OR l.bench_flag IS NULL)', 'left')
                ->where('l.diary_no IS NULL')
                ->orderBy('docnum', 'ASC');
           // pr($serve_status_query->getCompiledSelect());

            // Execute the query
            $query = $serve_status_query->get();
            $results = $query->getResultArray();
        }
        //pr($results);
        return $results;
    }


    public function diaryCriminalMatters($ct=0, $cl_date=null)
    {
        $results = [];
        if (!empty($ct) && !empty($cl_date)) {
            $subquery = $this->db->table('heardt h')
                ->select('p.id AS is_printed, docnum, docyear, r.courtno, us.id, 
                        COALESCE(u.name, tentative_da(m.diary_no)) AS name, 
                        COALESCE(us.section_name, tentative_section(m.diary_no)) AS section_name, 
                        l.purpose, c1.short_description, 
                        EXTRACT(YEAR FROM m.active_fil_dt) AS fyr, 
                        active_reg_year, active_fil_dt, reg_no_display, active_fil_no, 
                        m.pet_name, m.res_name, m.pno, m.rno, 
                        casetype_id, ref_agency_state_id, diary_no_rec_date, h.*')
                ->join('main m', 'm.diary_no = h.diary_no')
                ->join('master.listing_purpose l', 'l.code = h.listorder AND l.display = \'Y\'')
                ->join('master.roster r', 'r.id = h.roster_id AND r.display = \'Y\'')
                ->join('cl_printed p', 'p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = \'Y\'', 'left')
                ->join('master.casetype c1', 'm.active_casetype_id = c1.casecode', 'left')
                ->join('docdetails', 'h.diary_no = docdetails.diary_no AND iastat = \'P\' AND doccode = 8', 'left')
                ->join('master.users u', 'u.usercode = m.dacode AND u.display = \'Y\'', 'left')
                ->join('master.usersection us', 'us.id = u.section', 'left')
                ->where('h.mainhead', 'M')
                ->whereNotIn('casetype_id', [1, 3, 5, 7, 11, 13, 15, 17, 19, 21, 22, 23, 24, 25, 27, 32, 34, 40, 31])
                ->where('h.next_dt', $cl_date)
                ->groupStart()
                    ->where('active_reg_year', 0)
                    ->orWhere('active_reg_year IS NULL')
                ->groupEnd()
                ->groupStart()
                    ->where('h.main_supp_flag', 1)
                    ->orWhere('h.main_supp_flag', 2)
                ->groupEnd()
                ->where('m.c_status', 'P')
                ->where('h.clno >', 0)
                ->where('h.brd_slno >', 0)
                ->where('board_type', 'J')
                ->where('h.roster_id >', 0)
                ->where('m.diary_no IS NOT NULL')
                ->groupBy('h.diary_no,p.id,docdetails.docnum,docdetails.docyear,r.courtno,us.id,u.name,m.diary_no,l.purpose,c1.short_description');
            $subQueryResult = $subquery->getCompiledSelect();
            //pr($subQueryResult);
            $serve_status_query = $this->db->table("($subQueryResult) j")
                ->select('j.*')
                ->join('last_heardt l', 'j.diary_no = l.diary_no AND j.next_dt != l.next_dt AND cast(l.judges as integer) != 0 AND l.judges IS NOT NULL AND l.brd_slno > 0 AND (l.bench_flag = \'\' OR l.bench_flag IS NULL)', 'left')
                ->where('l.diary_no IS NULL')
                ->orderBy('docnum', 'ASC');
            
            
            $query = $serve_status_query->get();
            $results = $query->getResultArray();
        }
        
        return $results;
    }    


    public function getAdvocateData($diaryNo)
    {
        $subQuery = $this->db->table('advocate a')
        ->select("a.diary_no, b.name, 
            STRING_AGG(
                    COALESCE(a.adv, ''), ''
                ORDER BY CASE WHEN pet_res = 'I' THEN 99 ELSE 0 END ASC, 'adv_type DESC', pet_res_no ASC
                ) AS grp_adv,
            a.pet_res, a.adv_type, a.pet_res_no")
        ->join('master.bar b', 'a.advocate_id = b.bar_id AND b.isdead != \'Y\'', 'LEFT')
        ->where('a.diary_no', $diaryNo)
        ->where('a.display', 'Y')
        ->groupBy('a.diary_no, b.name, a.pet_res, a.adv_type, a.pet_res_no')
        ->orderBy("(CASE WHEN a.pet_res = 'I' THEN 99 ELSE 0 END) ASC")
            ->orderBy('(a.adv_type) DESC')
            ->orderBy('(a.pet_res_no) ASC')
        ->getCompiledSelect();
        
        $builder = $this->db->table("($subQuery) a", false);
        $builder->select("a.*, 
        STRING_AGG(a.name, '' ORDER BY CASE WHEN a.pet_res = 'R' THEN a.grp_adv END) AS r_n,
        STRING_AGG(a.name, '' ORDER BY CASE WHEN a.pet_res = 'P' THEN a.grp_adv END) AS p_n,
        STRING_AGG(a.name, '' ORDER BY CASE WHEN a.pet_res = 'I' THEN a.grp_adv END) AS i_n")
       ->groupBy('a.diary_no, a.name, a.grp_adv, a.pet_res, a.adv_type, a.pet_res_no');

        return $builder->get()->getResultArray();
    }


}
