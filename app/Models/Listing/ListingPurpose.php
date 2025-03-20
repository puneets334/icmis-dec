<?php

namespace App\Models\Listing;

use CodeIgniter\Model;

class ListingPurpose extends Model
{
    protected $table = 'master.listing_purpose';
    protected $primaryKey = 'id';

    protected $allowedFields = ['code', 'purpose', 'display', 'priority'];
    protected $returnType = 'array';

    public function getActivePurposes()
    {
        return $this->where('display', 'Y')
            ->where('code !=', 99)
            ->orderBy('priority', 'ASC')
            ->findAll();
    }
    public function getDisplayPurposes()
    {
        return $this->where('display', 'Y')
            ->where('code !=', 99)
            ->orderBy('priority')
            ->findAll();
    }

    public function getListingPurposes($main_supp)
    {
        $this->where('display', 'Y');
        if ($main_supp == 2) {
            $this->whereIn('code', [4, 5]);
        }
        $this->where('code !=', 99);
        $this->orderBy('priority');

        return $this->findAll();
    }

    ////
    public function getListPurposes()
    {
        return $this->where('display', 'Y')
            ->where('code !=', 99)
            ->orderBy('priority')
            ->findAll();
    }

    public function getAllocationDetails($sell_roster_id, $m_ff, $q_next_dt, $mainhead, $main_supp, $purposeCode)
    {
        // Start the main query builder
        $builder = $this->db->table('main m');

        // Join tables
        $builder->select('STRING_AGG(subquery.rid::text, \',\') AS rid, subquery.cat, mc.submaster_id, h.*, c.cat_allot_id')
            ->join('heardt h', 'h.diary_no = m.diary_no', 'left')
            ->join('master.listing_purpose l', 'l.code = h.listorder', 'left')
            ->join('rgo_default rd', 'rd.fil_no = h.diary_no AND rd.remove_def = \'N\'', 'left')
            ->join('mul_category mc', 'mc.diary_no = m.diary_no', 'left')
            ->join('category_allottment c', 'c.ros_id = mc.new_submaster_id AND c.display = \'Y\'', 'left') // Join category_allottment here
            ->join('(
                   SELECT STRING_AGG(s.id::text, \',\') AS cat, t.rid 
                   FROM (
                       SELECT STRING_AGG(r.id::text, \',\') AS rid, c.cat_allot_id, c.stage_code, c.stage_nature, c.ros_id, c.submaster_id 
                       FROM master.roster r 
                       LEFT JOIN category_allottment c ON c.ros_id = r.id AND c.display = \'Y\' 
                       WHERE r.id IN (' . $sell_roster_id . ') 
                       AND r.m_f = \'' . $m_ff . '\' 
                       AND (r.to_date IS NULL OR \'' . $q_next_dt . '\' BETWEEN r.from_date AND r.to_date) 
                       AND r.display = \'Y\' 
                       GROUP BY c.cat_allot_id, c.stage_code, c.stage_nature, c.ros_id, c.submaster_id
                   ) t 
                   LEFT JOIN master.submaster s ON s.id = t.submaster_id
                   GROUP BY t.rid
               ) AS subquery', 'mc.submaster_id = ANY(string_to_array(subquery.cat, \',\')::int[])', 'inner');

        // Add where conditions
        $builder->where('rd.fil_no IS NULL')
            ->where('m.active_casetype_id NOT IN (9, 10, 25, 26)')
            ->where('mc.display', 'Y')
            ->where('mc.submaster_id NOT IN (911, 912, 914, 239, 240, 241, 242, 243)')
            ->where('m.c_status', 'P')
            ->where('(
                   (m.diary_no::bigint = COALESCE(NULLIF(m.conn_key, \'\')::bigint, 0) OR m.conn_key IS NULL)
               )')
            ->where('h.main_supp_flag', 0)
            ->where('h.mainhead', $mainhead)
            ->where('h.next_dt IS NOT NULL')
            ->where('h.roster_id', 0)
            ->where('h.brd_slno', 0)
            ->where('h.board_type', 'J');

        // Adjusting the case when condition to avoid errors
        $builder->where(
            '
           (CASE 
               WHEN h.listorder IN (4, 5, 7, 8) THEN 
                   (CASE WHEN ' . $main_supp . ' = 2 THEN h.next_dt = ' . $this->db->escape($q_next_dt) . ' 
                         ELSE (h.next_dt = ' . $this->db->escape($q_next_dt) . ' OR h.next_dt <= CURRENT_DATE) 
                   END)
               ELSE h.next_dt > \'1947-08-15\' 
           END)'
        );

        // Additional where condition
        $builder->where('h.listorder IN (' . $purposeCode . ')')
            ->groupBy('h.diary_no, subquery.cat, mc.submaster_id, c.cat_allot_id') // Add all relevant fields here
            ->limit(600);

        return $builder->get()->getResultArray();
    }














    public function getPurposeCodes($p_listorder)
    {
        $builder = $this->db->table('master.listing_purpose');
        $builder->select("STRING_AGG(code::text, ',' ORDER BY priority) AS code, 
                          priority, 
                          CASE WHEN code IN (4, 5, 7) THEN 1 ELSE 2 END AS mand")
            ->where('display', 'Y')
            ->where('code !=', '49');
        if (!empty($p_listorder)) {
            $builder->where($p_listorder);
        }

        $builder->groupBy('mand, priority')
            ->orderBy('priority');

        return $builder->get()->getResultArray();
    }


    ////

    public function getListingPurpose()
    {
        $query = $this->db->table('master.listing_purpose')
            ->where('display', 'Y')
            ->orderBy('priority', 'ASC')
            ->get();
        //    echo $this->db->getLastQuery(); die;
        return $query->getResultArray();
    }

    public function getListingPurp()
    {
        return $this->where('display', 'Y')
            ->where('code !=', 99)
            ->orderBy('priority')
            ->findAll();
    }
    public function getPurposeList()
    {
        return $this->where('display', 'Y')->orderBy('code', 'ASC')->findAll();
    }


 


    
}
