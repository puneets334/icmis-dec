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
        $builder->select([
            "CONCAT(SUBSTRING(m.diary_no::TEXT, 1, LENGTH(m.diary_no::TEXT) - 4), '/', SUBSTRING(m.diary_no::TEXT, -4)) AS diary_no",
            'm.diary_no AS dno',
            'm.reg_no_display',
            "CONCAT(m.pet_name, ' vs ', m.res_name) AS Cause_title"
        ]);
        // Clone the query before adding limit and offset
        $totalQuery = clone $builder;

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

 
}
