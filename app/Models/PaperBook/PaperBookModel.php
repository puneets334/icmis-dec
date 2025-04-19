<?php

namespace App\Models\PaperBook;

use CodeIgniter\Model;

class PaperBookModel extends Model
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    private function getGodownAllocationBuilder()
    {
        return $this->db->table('master.godown_user_allocation g');
    }

    private function getMainBuilder()
    {
        return $this->db->table('main m');
    }

    // public function getAllocatedUsersPaginated($limit, $offset)
    // {
    //     return $this->db->table('users')
    //         ->limit($limit, $offset)
    //         ->get()
    //         ->getResultArray();
    // }

    // public function countTotalAllocatedUsers()
    // {
    //     return $this->db->table('users')
    //         ->countAllResults();
    // }

    public function getAllocatedUsers($limit, $offset)
    {
        $builder = $this->getGodownAllocationBuilder();
        
        $builder->distinct();
        $builder->select('g.usercode, u.name, u.empid');
        
        $builder->join('master.users u', 'g.usercode = u.usercode');
        
        // Subquery to get distinct usercodes from godown_user_allocation table
        $builder->whereIn('g.usercode', function ($subquery) {
            return $subquery->select('usercode')
                            ->distinct()
                            ->from('master.godown_user_allocation');
        });
    
        $builder->limit($limit, $offset);
    
        $query = $builder->get();
        return $query->getResultArray();
    }
    

    public function getUserCases($usercode)
    {
        $builder = $this->getGodownAllocationBuilder();

        $builder->select('g.usercode, g.casetype_id, g.case_from, g.case_to, g.caseyear, ct.casename as cases, COUNT(*) as t')
            ->join('main m', 'g.casetype_id = m.active_casetype_id AND m.active_reg_year = g.caseyear')
            ->join('master.casetype ct', 'g.casetype_id = ct.casecode')
            ->where('m.c_status', 'P')
            ->groupStart()
            ->where('m.fil_no IS NULL')
            ->orWhere('m.fil_no', '')
            ->groupEnd()
            ->where('g.usercode', $usercode)
            ->groupBy(['g.casetype_id', 'g.caseyear', 'g.usercode', 'g.case_from', 'g.case_to', 'ct.casename']);

        $query = $builder->get();

        // Print the last query for debugging
        //echo $this->db->getLastQuery();

        $result = $query->getResultArray();

        // If no cases found for the user, return a list of cases by diary year
        if (empty($result)) {
            return $this->getDiaryCasesByYear([2017, 2018]);
        }

        return $result;
    }


    // Fallback function to get diary cases if no specific user cases are found
    private function getDiaryCasesByYear(array $years)
    {
        $builder = $this->getMainBuilder();
        $builder->select("CASE WHEN case_grp = 'C' THEN 'Civil' ELSE 'Criminal' END AS case_group, 
                          EXTRACT(YEAR FROM diary_no_rec_date) AS year, 
                          COUNT(*) AS total")
            ->groupStart()
            ->where('fil_no IS NULL')
            ->orWhere('fil_no', '')
            ->groupEnd()
            ->whereIn('EXTRACT(YEAR FROM diary_no_rec_date)', $years)
            ->where('c_status', 'P')
            ->groupBy('case_grp, year');

        $query = $builder->get();
        return $query->getResultArray();
    }

    // Get total cases for a user
    public function getTotalCases($usercode)
    {
        $builder = $this->getGodownAllocationBuilder();
        $builder->select('g.casetype_id, g.caseyear, g.case_from, g.case_to, ct.casename || \'-\' || g.caseyear as cases, COUNT(*) as t, SUM(COUNT(*)) OVER () as gtotal')
            ->join('main m', 'g.casetype_id = m.active_casetype_id AND m.active_reg_year = g.caseyear')
            ->join('master.casetype ct', 'g.casetype_id = ct.casecode')
            ->where('g.usercode', $usercode)
            ->where('m.c_status', 'P')
            ->where('m.fil_no IS NOT NULL')
            ->groupBy(['g.casetype_id', 'g.caseyear', 'g.usercode', 'ct.casename', 'g.case_from', 'g.case_to']);

        $query = $builder->get();
        return $query->getResultArray();
    }

    // Get unallocated diary matters
    public function getUnallocatedDiaryMatters()
    {
        $builder = $this->db->table('main');
        $builder->select("CASE WHEN case_grp = 'C' THEN 'Civil' ELSE 'Criminal' END AS case_group, 
                           EXTRACT(YEAR FROM diary_no_rec_date) AS year, 
                           COUNT(*) AS total");
        $builder->groupStart()
            ->where('fil_no IS NULL')
            ->orWhere('fil_no', '')
            ->groupEnd();
        $builder->whereIn('EXTRACT(YEAR FROM diary_no_rec_date)', [2017, 2018]); // Example years
        $builder->where('c_status', 'P');
        $builder->groupBy('case_grp, year');

        $query = $builder->get();
        return $query->getResultArray();
    }

    // Get unallocated registered matters
    public function getUnallocatedRegisteredMatters()
    {
        $builder = $this->db->table('main');
        $builder->select("CASE WHEN case_grp = 'C' THEN 'Civil' ELSE 'Criminal' END AS case_group, 
                           EXTRACT(YEAR FROM reg_date) AS year, 
                           COUNT(*) AS total");
        $builder->groupStart()
            ->where('fil_no IS NULL')
            ->orWhere('fil_no', '')
            ->groupEnd();
        $builder->whereIn('EXTRACT(YEAR FROM reg_date)', [2017, 2018]); // Example years
        $builder->where('c_status', 'P');
        $builder->groupBy('case_grp, year');

        $query = $builder->get();
        return $query->getResultArray();
    }


    public function getUserInformation($limit, $offset, $params)
    {
        $builder = $this->getMainBuilder();
        // Common query conditions
        
        if ($params['caseGroup'] === 'Civil') {
            $builder->where('case_grp', 'C')
                    ->where('c_status', 'P')
                    ->where('EXTRACT(YEAR FROM diary_no_rec_date) =', $params['year']);
        } else if ($params['caseGroup'] === 'Criminal') {
            $builder->where('case_grp', 'R')
                    ->where('c_status', 'P');
        } else if ($params['caseGroup'] === 'all') {
            $builder->where('c_status', 'P')
                    ->where('EXTRACT(YEAR FROM diary_no_rec_date) <=', 2016);
        } else if ($params['caseGroup'] === 'o') {
            $builder->whereNotIn('concat(active_casetype_id, active_reg_year)', [11999, 12000, 12001, /*... (your entire list) */])
                    ->where('c_status', 'P')
                    ->where('fil_no IS NOT NULL')
                    ->where('fil_no <>', '');
        } else {
            $builder->where('active_casetype_id', $params['caseGroup'])
                    ->where('c_status', 'P')
                    ->where('fil_no IS NOT NULL')
                    ->where('fil_no <>', '');
        }

        $builder->groupStart()
        ->where('fil_no', '') 
        ->orWhere('fil_no IS NULL', null, false) 
        ->groupEnd();
        $builder->select([
            "CONCAT(SUBSTRING(m.diary_no::TEXT, 1, LENGTH(m.diary_no::TEXT) - 4), '/', SUBSTRING(m.diary_no::TEXT, -4)) AS diary_no",
            'm.diary_no AS dno',
            'm.reg_no_display',
            "CONCAT(m.pet_name, ' vs ', m.res_name) AS Cause_title"
        ]);
        // Clone the query before adding limit and offset
        $totalQuery = clone $builder;
       // pr($builder->getCompiledSelect());
        // Limit and offset for pagination
        $builder->limit($limit, $offset);
        $query = $builder->get();

        // Get paginated results
        $results = $query->getResultArray();

        // Get the total record count (without limit and offset)
        $totalRecords = $totalQuery->countAllResults(false); // `false` prevents clearing the builder.

        // Debug: Show the last executed query
        //echo $this->db->getLastQuery();

        // Return both results and total count
        return [
            'results' => $results,
            'totalRecords' => $totalRecords
        ];
    }

    public function getSectionName($diaryNo)
    {
        $builder = $this->getMainBuilder();
    
      
        $builder->select('m.section_id')
                ->join('"master"."usersection" as us', 'm.section_id = us.id')
                ->where('m.diary_no', $diaryNo);
    
        $query = $builder->get();
        $row_1 = $query->getRowArray(); 
    
        $sectionName = $row_1['section_name'] ?? null;
    
        if (empty($sectionName) || $sectionName == 0) {
            $query = $this->db->query("SELECT tentative_section(?) AS section_name", [$diaryNo]);
            $row_2 = $query->getRowArray();
            $sectionName = $row_2['section_name'] ?? null;
        }
        return $sectionName;
    }
    
    public function getTentativeDA($diaryNo)
    {
        $query = $this->db->query("SELECT tentative_da(?) AS da_value", [$diaryNo]);
        $row = $query->getRowArray();
        $daValue = $row['da_value'] ?? null;
        return $daValue;
    }

    public function getNextDates()
    {
        $builder = $this->db->table('heardt');
        $builder->select('next_dt')
                ->where('mainhead', 'M')
                //->where('next_dt >=', date('Y-m-d')) // Ensure to format the date as needed
                ->groupStart()
                    ->where('main_supp_flag', '1')
                    ->orWhere('main_supp_flag', '2')
                ->groupEnd()
                ->groupBy('next_dt');
        
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getUserType($userCode)
    {
        return $this->db->table('master.users')
            ->select('usertype')
            ->where('usercode', $userCode)
            ->get()
            ->getRowArray();
    }

    public function getFreshMatters($cl_date, $caseTypes)
    {
        return $this->db->table('your_table') // Update with your actual table
            ->select('*')
            ->where('active_casetype_id IN (' . $caseTypes . ')')
            ->where('next_dt', $cl_date)
            ->get()
            ->getResultArray();
    }


    public function getServeStatus($ucode, $utype, $cl_date, $ct, $sorting)
    {
        if ($ucode == 1 || $ucode == 630) { // Super user and specific employee
            $sql = "SELECT a.* FROM (
                        SELECT r.courtno, docyear, docnum, 
                            (docnum || '/' || docyear) AS IA, 
                            reg_no_display, active_casetype_id, u.name, us.section_name, 
                            l.purpose, c1.short_description, 
                            EXTRACT(YEAR FROM m.active_fil_dt) AS fyr, 
                            active_reg_year, active_fil_dt, active_fil_no, 
                            m.pet_name, m.res_name, m.pno, m.rno, casetype_id, 
                            ref_agency_state_id, diary_no_rec_date, h.* 
                        FROM heardt h 
                        INNER JOIN main m ON m.diary_no = h.diary_no 
                        INNER JOIN master.listing_purpose l ON l.code = h.listorder AND l.display = 'Y' 
                        INNER JOIN master.roster r ON r.id = h.roster_id AND r.display = 'Y' 
                        LEFT JOIN brdrem br ON br.diary_no = m.diary_no 
                        LEFT JOIN master.casetype c1 ON m.active_casetype_id = c1.casecode  
                        LEFT JOIN docdetails ON m.diary_no = docdetails.diary_no 
                        LEFT JOIN master.users u ON u.usercode = m.dacode AND (u.display = 'Y' OR u.display IS NULL) 
                        LEFT JOIN master.usersection us ON us.id = u.section 
                        WHERE h.mainhead = 'M' 
                        AND h.next_dt = '$cl_date' 
                        AND subhead IN (811, 812) 
                        AND h.board_type = 'J' 
                        AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2) 
                        AND h.roster_id > 0 
                        AND m.diary_no IS NOT NULL 
                        AND m.c_status = 'P' 
                       AND iastat = 'P' 
                        GROUP BY h.diary_no, r.courtno, docyear, docnum, reg_no_display, active_casetype_id, 
                                 u.name, us.section_name, l.purpose, c1.short_description, m.active_fil_dt, 
                                 active_reg_year, active_fil_dt, active_fil_no, m.pet_name, m.res_name, m.pno, 
                                 m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date
                    ) a 
                    ORDER BY $sorting ASC";
        } elseif ($utype == 14) { 
            $sql = "SELECT a.* FROM (
                        SELECT r.courtno, docyear, docnum, 
                            (docnum || '/' || docyear) AS IA, 
                            reg_no_display, active_casetype_id, u.name, us.section_name, 
                            l.purpose, c1.short_description, 
                            EXTRACT(YEAR FROM m.active_fil_dt) AS fyr, 
                            active_reg_year, active_fil_dt, active_fil_no, 
                            m.pet_name, m.res_name, m.pno, m.rno, casetype_id, 
                            ref_agency_state_id, diary_no_rec_date, h.* 
                        FROM heardt h 
                        INNER JOIN main m ON m.diary_no = h.diary_no 
                        INNER JOIN master.listing_purpose l ON l.code = h.listorder AND l.display = 'Y' 
                        INNER JOIN master.roster r ON r.id = h.roster_id AND r.display = 'Y' 
                        LEFT JOIN brdrem br ON br.diary_no = m.diary_no 
                        LEFT JOIN master.casetype c1 ON m.active_casetype_id = c1.casecode  
                        LEFT JOIN docdetails ON m.diary_no = docdetails.diary_no 
                        LEFT JOIN master.users u ON u.usercode = m.dacode AND (u.display = 'Y' OR u.display IS NULL) 
                        LEFT JOIN master.usersection us ON us.id = u.section 
                        WHERE h.mainhead = 'M' 
                        AND h.next_dt = '$cl_date' 
                        AND subhead IN (811, 812) 
                        AND h.board_type = 'J' 
                        AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2) 
                        AND h.roster_id > 0 
                        AND m.diary_no IS NOT NULL 
                        AND m.c_status = 'P' 
                        AND iastat = 'P' 
                        GROUP BY h.diary_no, r.courtno, docyear, docnum, reg_no_display, active_casetype_id, 
                                 u.name, us.section_name, l.purpose, c1.short_description, m.active_fil_dt, 
                                 active_reg_year, active_fil_dt, active_fil_no, m.pet_name, m.res_name, m.pno, 
                                 m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date
                    ) a 
                    ORDER BY $sorting ASC";
        } else {
            $sql = "SELECT a.* FROM (
                        SELECT r.courtno, reg_no_display, docnum, docyear, 
                            (docnum || '/' || docyear) AS IA, 
                            u.name, us.section_name, l.purpose, c1.short_description, 
                            EXTRACT(YEAR FROM m.active_fil_dt) AS fyr, 
                            active_reg_year, active_fil_dt, active_fil_no, 
                            m.pet_name, m.res_name, m.pno, m.rno, casetype_id, 
                            ref_agency_state_id, diary_no_rec_date, h.* 
                        FROM heardt h 
                        INNER JOIN main m ON m.diary_no = h.diary_no 
                        INNER JOIN master.listing_purpose l ON l.code = h.listorder AND l.display = 'Y' 
                        INNER JOIN master.roster r ON r.id = h.roster_id AND r.display = 'Y' 
                        LEFT JOIN brdrem br ON br.diary_no = m.diary_no 
                        LEFT JOIN master.casetype c1 ON m.active_casetype_id = c1.casecode  
                        LEFT JOIN docdetails ON m.diary_no = docdetails.diary_no 
                        LEFT JOIN master.users u ON u.usercode = m.dacode AND (u.display = 'Y' OR u.display IS NULL) 
                        LEFT JOIN master.usersection us ON us.id = u.section 
                        WHERE h.mainhead = 'M' 
                        AND h.next_dt = '$cl_date' 
                        AND h.board_type = 'J' 
                        AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2) 
                        AND h.roster_id > 0 
                        AND m.diary_no IS NOT NULL 
                        AND m.c_status = 'P' 
                         AND iastat = 'P' 
                        AND subhead IN (811, 812) 
                        AND (active_casetype_id::TEXT || active_reg_year::TEXT) IN ($ct)
                    ) a 
                    ORDER BY $sorting ASC";
        }
        //echo $sql;
       // die;
        $query = $this->db->query($sql);
        return $query->getResultArray();
    }


    public function getAdvocatesByDiary($diary_no)
    {
        $sql = "SELECT a.*, 
                    STRING_AGG(CASE WHEN pet_res = 'R' THEN a.name || COALESCE(grp_adv, '') END, ', ' ORDER BY adv_type DESC, pet_res_no ASC) AS r_n,
                    STRING_AGG(CASE WHEN pet_res = 'P' THEN a.name || COALESCE(grp_adv, '') END, ', ' ORDER BY adv_type DESC, pet_res_no ASC) AS p_n,
                    STRING_AGG(CASE WHEN pet_res = 'I' THEN a.name || COALESCE(grp_adv, '') END, ', ' ORDER BY adv_type DESC, pet_res_no ASC) AS i_n
                FROM (
                    SELECT a.diary_no, b.name,
                        STRING_AGG(COALESCE(a.adv, ''), ', ' ORDER BY 
                            CASE WHEN pet_res = 'I' THEN 99 ELSE 0 END ASC, 
                            adv_type DESC, 
                            pet_res_no ASC) AS grp_adv,
                        a.pet_res, a.adv_type, pet_res_no
                    FROM advocate a
                    LEFT JOIN master.bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y'
                    WHERE a.diary_no = ? AND a.display = 'Y'
                    GROUP BY a.diary_no, b.name, a.pet_res, a.adv_type, pet_res_no
                ) a
                GROUP BY a.diary_no,a.name,a.grp_adv,a.pet_res,a.adv_type,a.pet_res_no";

        $query = $this->db->query($sql, [$diary_no]);
        return $query->getRowArray();
    }


    public function getSectionTenData($casetype_displ, $ten_reg_yr, $ref_agency_state_id)
    {
        $sql = "SELECT a.dacode, c.section_name, b.name 
                FROM master.da_case_distribution a
                LEFT JOIN master.users b ON b.usercode = a.dacode
                LEFT JOIN master.usersection c ON b.section = c.id
                WHERE a.case_type = ? 
                  AND ? BETWEEN a.case_f_yr AND a.case_t_yr 
                  AND a.state = ? 
                  AND a.display = 'Y'";

        $query = $this->db->query($sql, [$casetype_displ, $ten_reg_yr, $ref_agency_state_id]);
        return $query->getRowArray();
    }


    public function getMatters($ucode)
    {
        $sql = "SELECT STRING_AGG(casetype_id::TEXT, ',') AS ct 
                FROM godown_user_allocation 
                WHERE usercode = ? 
                  AND casetype_id NOT IN (1,3,5,7,11,13,23,32,34,40,9,19,25) 
                  AND caseyear = EXTRACT(YEAR FROM NOW())";

        $query = $this->db->query($sql, [$ucode]);
        return $query->getRowArray();
    }

    public function getServeStatusType3($cl_date, $sorting)
    {
        $sql = "SELECT a.* 
                FROM (
                    SELECT r.courtno, reg_no_display, active_casetype_id, 
                           u.name, us.section_name, l.purpose, 
                           c1.short_description, 
                           EXTRACT(YEAR FROM m.active_fil_dt) AS fyr, 
                           active_reg_year, active_fil_dt, active_fil_no, 
                           m.pet_name, m.res_name, m.pno, m.rno, 
                           casetype_id, ref_agency_state_id, 
                           diary_no_rec_date, remark, h.* 
                    FROM heardt h 
                    INNER JOIN main m ON m.diary_no = h.diary_no 
                    INNER JOIN master.listing_purpose l ON l.code = h.listorder AND l.display = 'Y' 
                    INNER JOIN master.roster r ON r.id = h.roster_id AND r.display = 'Y' 
                    LEFT JOIN brdrem br ON br.diary_no = m.diary_no 
                    LEFT JOIN master.casetype c1 ON m.active_casetype_id = c1.casecode 
                    LEFT JOIN master.users u ON u.usercode = m.dacode 
                        AND (u.display = 'Y' OR u.display IS NULL) 
                    LEFT JOIN master.usersection us ON us.id = u.section 
                    WHERE h.mainhead = 'M' 
                      AND h.next_dt = ? 
                      AND h.board_type = 'J' 
                      AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2) 
                      AND h.roster_id > 0 
                      AND m.diary_no IS NOT NULL 
                      AND m.c_status = 'P' 
                    GROUP BY h.diary_no, r.courtno, reg_no_display, 
                             active_casetype_id, u.name, us.section_name, 
                             l.purpose, c1.short_description, 
                             m.active_fil_dt, active_reg_year, active_fil_dt, 
                             active_fil_no, m.pet_name, m.res_name, 
                             m.pno, m.rno, casetype_id, ref_agency_state_id, 
                             diary_no_rec_date, remark, h.diary_no
                ) a 
                ORDER BY $sorting ASC";

        $query = $this->db->query($sql, [$cl_date]);
        return $query->getResultArray();
    }


    public function getServeStatusType4($cl_date, $sorting)
    {
        $sql = "SELECT a.* 
                FROM (
                    SELECT r.courtno, reg_no_display, u.name, active_casetype_id,
                           us.section_name, l.purpose, c1.short_description, 
                           EXTRACT(YEAR FROM m.active_fil_dt) AS fyr, 
                           active_reg_year, active_fil_dt, active_fil_no, 
                           m.pet_name, m.res_name, m.pno, m.rno, 
                           casetype_id, ref_agency_state_id, 
                           diary_no_rec_date, remark, h.* 
                    FROM heardt h 
                    INNER JOIN main m ON m.diary_no = h.diary_no 
                    INNER JOIN master.listing_purpose l ON l.code = h.listorder AND l.display = 'Y' 
                    INNER JOIN master.roster r ON r.id = h.roster_id AND r.display = 'Y' 
                    LEFT JOIN brdrem br ON br.diary_no = m.diary_no 
                    LEFT JOIN master.casetype c1 ON m.active_casetype_id = c1.casecode 
                    LEFT JOIN master.users u ON u.usercode = m.dacode 
                        AND (u.display = 'Y' OR u.display IS NULL) 
                    LEFT JOIN master.usersection us ON us.id = u.section 
                    WHERE h.mainhead = 'M'  
                      AND h.next_dt = ?  
                      AND active_casetype_id IN (9,10,25,26,19,20)  
                      AND h.board_type = 'J'  
                      AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)  
                      AND h.roster_id > 0  
                      AND m.diary_no IS NOT NULL  
                      AND m.c_status = 'P'  
                      AND subhead NOT IN (811,812)  
                    GROUP BY h.diary_no, r.courtno, reg_no_display, 
                             u.name, active_casetype_id, us.section_name, 
                             l.purpose, c1.short_description, 
                             m.active_fil_dt, active_reg_year, active_fil_dt, 
                             active_fil_no, m.pet_name, m.res_name, 
                             m.pno, m.rno, casetype_id, ref_agency_state_id, 
                             diary_no_rec_date, remark, h.diary_no
                ) a  
                ORDER BY $sorting ASC";

        $query = $this->db->query($sql, [$cl_date]);
        return $query->getResultArray();
    }



    public function getServeStatusType5($cl_date, $sorting)
    {
        $sql = "SELECT a.* 
                FROM (
                    SELECT r.courtno, reg_no_display, active_casetype_id, 
                           u.name, us.section_name, l.purpose, c1.short_description, 
                           EXTRACT(YEAR FROM m.active_fil_dt) AS fyr, 
                           active_reg_year, active_fil_dt, active_fil_no, 
                           m.pet_name, m.res_name, m.pno, m.rno, 
                           casetype_id, ref_agency_state_id, 
                           diary_no_rec_date, remark, h.* 
                    FROM heardt h 
                    INNER JOIN main m ON m.diary_no = h.diary_no 
                    INNER JOIN master.listing_purpose l ON l.code = h.listorder AND l.display = 'Y' 
                    INNER JOIN master.roster r ON r.id = h.roster_id AND r.display = 'Y' 
                    LEFT JOIN brdrem br ON br.diary_no = m.diary_no 
                    LEFT JOIN master.casetype c1 ON m.active_casetype_id = c1.casecode 
                    LEFT JOIN master.users u ON u.usercode = m.dacode 
                        AND (u.display = 'Y' OR u.display IS NULL) 
                    LEFT JOIN master.usersection us ON us.id = u.section 
                    WHERE h.mainhead = 'M'  
                      AND h.next_dt = ?  
                      AND h.board_type = 'J'  
                      AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)  
                      AND h.roster_id > 0  
                      AND m.diary_no IS NOT NULL  
                      AND m.c_status = 'P'  
                      AND (active_casetype_id::TEXT || active_reg_year::TEXT) NOT IN (
                          '11999', '12000', '12001', '12002', '12003', '12004', '12005', '12006', '12007', '12008', 
                            '12009', '12010', '12011', '12012', '12013', '12015', '12016', '12018', '12014', '12017', 
                            '22015', '72015', '82015', '52015', '62015', '22016', '72016', '82016', '52016', '62016',
                            '22018', '72018', '82018', '52018', '62018', '21950', '21951', '21952', '21953', '21954',
                            '21955', '21956', '21957', '21958', '21959', '21960', '21961', '21962', '21963', '21964',
                            '21965', '21966', '21967', '21968', '21969', '21970', '21971', '21972', '21973', '21974',
                            '21975', '21976', '21977', '21978', '21979', '21980', '21981', '21982', '21983', '21984',
                            '21985', '21986', '21987', '21988', '21989', '21990', '21991', '21992', '21993', '21994',
                            '21995', '21996', '21997', '21998', '21999', '22000', '22001', '22002', '22003', '22004',
                            '22005', '22006', '22007', '22008', '22009', '22010', '22011', '22012', '22013', '22014',
                            '22017', '71950', '71951', '71952', '71953', '71954', '71955', '71956', '71957', '71958',
                            '71959', '71960', '71961', '71962', '71963', '71964', '71965', '71966', '71967', '71968',
                            '71969', '71970', '71971', '71972', '71973', '71974', '71975', '71976', '71977', '71978',
                            '71979', '71980', '71981', '71982', '71983', '71984', '71985', '71986', '71987', '71988',
                            '71989', '71990', '71991', '71992', '71993', '71994', '71995', '71996', '71997', '71998',
                            '71999', '72000', '72001', '72002', '72003', '72004', '72005', '72006', '72007', '72008',
                            '72009', '72010', '72011', '72012', '72013', '72014', '72017'
                      )  
                      AND (m.fil_no IS NOT NULL AND m.fil_no <> '')  
                      AND active_casetype_id NOT IN ('9','10','19','20','25','26')  
                    GROUP BY h.diary_no, r.courtno, reg_no_display, 
                             active_casetype_id, u.name, us.section_name, 
                             l.purpose, c1.short_description, 
                             m.active_fil_dt, active_reg_year, active_fil_dt, 
                             active_fil_no, m.pet_name, m.res_name, 
                             m.pno, m.rno, casetype_id, ref_agency_state_id, 
                             diary_no_rec_date, remark, h.diary_no
                ) a  
                ORDER BY " . $sorting . " ASC";

        $query = $this->db->query($sql, [$cl_date]);
        return $query->getResultArray();
    }



    public function getServeStatusType6($cl_date, $sorting)
    {
         

        $sql = "
            SELECT a.*
            FROM (
                SELECT r.courtno, m.reg_no_display, u.name, us.section_name, 
                    l.purpose, docnum, docyear, c1.short_description, 
                    EXTRACT(YEAR FROM m.active_fil_dt) AS fyr, m.active_reg_year, 
                    m.active_fil_dt, m.active_fil_no, 
                    m.pet_name, m.res_name, m.pno, m.rno, 
                    m.casetype_id, m.ref_agency_state_id, m.diary_no_rec_date, 
                    h.*
                FROM heardt h
                INNER JOIN main m ON m.diary_no = h.diary_no
                INNER JOIN master.listing_purpose l ON l.code = h.listorder AND l.display = 'Y'
                INNER JOIN master.roster r ON r.id = h.roster_id AND r.display = 'Y'
                LEFT JOIN brdrem br ON br.diary_no = m.diary_no
                LEFT JOIN docdetails d ON h.diary_no = d.diary_no 
                    AND d.iastat = 'P' AND d.doccode = 8
                LEFT JOIN master.casetype c1 ON m.active_casetype_id = c1.casecode
                LEFT JOIN master.users u ON u.usercode = m.dacode
                    AND (u.display = 'Y' OR u.display IS NULL)
                LEFT JOIN master.usersection us ON us.id = u.section
                WHERE h.mainhead = 'M'
                    AND h.next_dt = '$cl_date'
                    AND h.board_type = 'J'
                    AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
                    AND h.roster_id > 0
                    AND m.diary_no IS NOT NULL
                    AND m.c_status = 'P' 
                    AND m.case_grp = 'C'
                    AND COALESCE(m.fil_no, '') = ''
                    AND h.subhead IN (811, 812)
                GROUP BY h.diary_no, r.courtno, m.reg_no_display, u.name, us.section_name, 
                         l.purpose, docnum, docyear, c1.short_description, 
                         m.active_fil_dt, m.active_reg_year, m.active_fil_no, 
                         m.pet_name, m.res_name, m.pno, m.rno, 
                         m.casetype_id, m.ref_agency_state_id, m.diary_no_rec_date
            ) a
            ORDER BY $sorting ASC
        ";

        $query = $this->db->query($sql);
        return $query->getResultArray(); // Return results as an associative array
    }


    public function getServeStatusType7($cl_date, $sorting)
    {
         

        $sql = "
            SELECT a.*
            FROM (
                SELECT r.courtno, m.reg_no_display, u.name, us.section_name, 
                    l.purpose, docnum, docyear, c1.short_description, 
                    EXTRACT(YEAR FROM m.active_fil_dt) AS fyr, m.active_reg_year, 
                    m.active_fil_dt, m.active_fil_no, 
                    m.pet_name, m.res_name, m.pno, m.rno, 
                    m.casetype_id, m.ref_agency_state_id, m.diary_no_rec_date, 
                    h.*
                FROM heardt h
                INNER JOIN main m ON m.diary_no = h.diary_no
                INNER JOIN master.listing_purpose l ON l.code = h.listorder AND l.display = 'Y'
                INNER JOIN master.roster r ON r.id = h.roster_id AND r.display = 'Y'
                LEFT JOIN brdrem br ON br.diary_no = m.diary_no
                LEFT JOIN docdetails d ON h.diary_no = d.diary_no 
                    AND d.iastat = 'P' AND d.doccode = 8
                LEFT JOIN master.casetype c1 ON m.active_casetype_id = c1.casecode
                LEFT JOIN master.users u ON u.usercode = m.dacode
                    AND (u.display = 'Y' OR u.display IS NULL)
                LEFT JOIN master.usersection us ON us.id = u.section
                WHERE h.mainhead = 'M'
                    AND h.next_dt = '$cl_date'
                    AND h.board_type = 'J'
                    AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
                    AND h.roster_id > 0
                    AND m.diary_no IS NOT NULL
                    AND m.c_status = 'P' 
                    AND m.case_grp = 'R'
                    AND COALESCE(m.fil_no, '') = ''
                    AND h.subhead IN (811, 812)
                GROUP BY h.diary_no, r.courtno, m.reg_no_display, u.name, us.section_name, 
                         l.purpose, docnum, docyear, c1.short_description, 
                         m.active_fil_dt, m.active_reg_year, m.active_fil_no, 
                         m.pet_name, m.res_name, m.pno, m.rno, 
                         m.casetype_id, m.ref_agency_state_id, m.diary_no_rec_date
            ) a
            ORDER BY $sorting ASC
        ";

        $query = $this->db->query($sql);
        return $query->getResultArray(); // Return data as an array
    }

    

 
}
