<?php

namespace App\Models\PaperBook;

use CodeIgniter\Model;

class AdvanceReportModel extends Model
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    private function getHeardtBuilder()
    {
        return $this->db->table('"heardt" AS "H"');
    }

    private function getUserBuilder()
    {
        return $this->db->table('"master"."users" AS "U"');
    }

    private function getGoDownBuilder()
    {
        return $this->db->table('"master"."godown_user_allocation" AS "GUA"');
    }

    public function fetchAdvanceReport($date, $listType, $includeReview, $userCondition = null)
    {
        $builder = $this->getHeardtBuilder();

        $builder->select('H.next_dt')
            ->where('H.mainhead', 'M')
            ->where('H.next_dt >=', $date)
            ->whereIn('H.main_supp_flag', ['1', '2'])
            ->groupBy('H.next_dt');

        // Apply user-specific condition if exists
        if ($userCondition) {
            $builder->where($userCondition);
        }

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getUserCode($ucode)
    {
        $builder = $this->getUserBuilder();

        $builder->select('U.usertype')
            ->where('U.usercode', $ucode);

        $query = $builder->get();
        return $query->getRowArray();
    }

    public function getAllocatedCaseTypes($ucode)
    {
        $builder = $this->getGoDownBuilder();

        $currentYear = date('Y');
        $builder->select('string_agg(GUA.casetype_id::text, \',\') AS ct', false)
            ->where('GUA.usercode', $ucode)
            ->whereIn('GUA.casetype_id', [1, 3, 5, 7, 11, 13, 15, 17, 19, 21, 22, 23, 24, 25, 27, 32, 34, 40, 31])
            ->where('GUA.caseyear', $currentYear);

        $query = $builder->get();
        $result = $query->getRow();

        return $result ? $result->ct : null;
    }

    public function fetchServeStatus($date, $ct)
    {
        $builder = $this->getHeardtBuilder();

        $builder->select("
                r.courtno, 
                us.id, 
                COALESCE(u.name, tentative_da(m.diary_no)) AS name, 
                casecode, 
                COALESCE(us.section_name, tentative_section(m.diary_no)) AS section_name, 
                l.purpose, 
                c1.short_description, 
                EXTRACT(YEAR FROM m.active_fil_dt) AS fyr,  
                active_reg_year, 
                active_fil_dt, 
                reg_no_display, 
                active_fil_no, 
                m.pet_name, 
                m.res_name, 
                m.pno, 
                m.rno, 
                casetype_id, 
                ref_agency_state_id, 
                diary_no_rec_date, 
                H.*")
            ->join('main m', 'm.diary_no = H.diary_no')
            ->join('master.listing_purpose l', 'l.code = H.listorder AND l.display = \'Y\'')
            ->join('master.roster r', 'r.id = H.roster_id AND H.board_type = \'J\' AND r.display = \'Y\'')
            ->join('cl_printed p', 'p.next_dt = H.next_dt AND p.m_f = H.mainhead AND p.part = H.clno AND p.roster_id = H.roster_id AND p.display = \'Y\'', 'left')
            ->join('master.casetype c1', 'm.active_casetype_id = c1.casecode', 'left')
            ->join('master.users u', 'u.usercode = m.dacode AND u.display = \'Y\'', 'left')
            ->join('master.usersection us', 'us.id = u.section', 'left')
            ->whereIn('m.active_casetype_id', explode(',', $ct))
            ->where('H.mainhead', 'M')
            ->where('H.next_dt', $date)
            ->whereIn('H.main_supp_flag', [1, 2])
            ->where([
                'm.c_status' => 'P',
                'H.clno >'   => 0,
                'H.brd_slno >' => 0,
                'H.roster_id >' => 0
            ])
            //->groupBy('H.diary_no')
            ->orderBy('casecode, active_fil_no', 'ASC');

        // Get the last query for debugging
        $query = $builder->get();
      
        return $query->getResultArray();
    }


}
