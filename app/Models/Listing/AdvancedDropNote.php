<?php

namespace App\Models\Listing;

use CodeIgniter\Model;

class AdvancedDropNote extends Model
{
    protected $table = 'advanced_drop_note';
    protected $primaryKey = 'id';

    // Define method to fetch drop notes
    public function getDropNotes_old($board_type, $next_dts)
    {
        // Adjust the SQL with explicit type casting to text
        $sql = "
                SELECT 
                    d.clno,
                    h.next_dt AS p_next_dt,
                    h.brd_slno AS p_brd_slno,
                    COALESCE(d.nrs, '-') AS nrs, 
                    d.diary_no,
                    CASE 
                        WHEN m.reg_no_display = '' THEN 'Diary No. ' || LEFT(m.diary_no::text, LENGTH(m.diary_no::text) - 4) || '-' || RIGHT(m.diary_no::text, 4)
                        ELSE m.reg_no_display
                    END AS case_no,
                    CASE 
                        WHEN pno = 2 THEN m.pet_name || ' AND ANR.'
                        WHEN pno > 2 THEN m.pet_name || ' AND ORS.'
                        ELSE m.pet_name 
                    END AS pname,
                    CASE 
                        WHEN rno = 2 THEN m.res_name || ' AND ANR.'
                        WHEN rno > 2 THEN m.res_name || ' AND ORS.'
                        ELSE m.res_name 
                    END AS rname
                FROM 
                    advanced_drop_note d
                INNER JOIN 
                    main m ON CAST(m.diary_no AS text) = CAST(d.diary_no AS text)  -- Proper casting to text
                INNER JOIN 
                    advance_allocated h ON CAST(h.diary_no AS text) = CAST(m.diary_no AS text)  -- Consistent casting to text
                WHERE 
                    d.board_type = CAST(? AS character varying)  -- Casting board_type to character varying
                    AND {$next_dts}  -- Ensure next_dts is type compatible
                    AND d.display IN ('Y', 'R')
                ORDER BY 
                    d.clno
            ";

        // Use query bindings to pass parameters correctly
        return $this->db->query($sql, [$board_type])->getResultArray();
    }
    public function getDropNotes($from_dt, $to_dt)
    {

        // // Validate date formats
        // if (!$this->isValidDate($from_dt) || !$this->isValidDate($to_dt)) {
        //     throw new \InvalidArgumentException('Invalid date format.');
        // }

        $builder = $this->db->table('single_judge_advanced_drop_note d');
        $builder->select('
                d.clno,
                h.next_dt AS p_next_dt,
                h.brd_slno AS p_brd_slno,
                COALESCE(d.nrs, \'-\') AS nrs,
                d.diary_no,
                COALESCE(NULLIF(m.reg_no_display, \'\'), 
                    CONCAT(\'Diary No. \', LEFT(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 4), 
                    \'-\', RIGHT(CAST(m.diary_no AS TEXT), 4))) AS case_no,
                (CASE WHEN pno = 2 THEN CONCAT(m.pet_name, \' AND ANR.\') 
                      WHEN pno > 2 THEN CONCAT(m.pet_name, \' AND ORS.\') 
                      ELSE m.pet_name END) AS pname,
                (CASE WHEN rno = 2 THEN CONCAT(m.res_name, \' AND ANR.\') 
                      WHEN rno > 2 THEN CONCAT(m.res_name, \' AND ORS.\') 
                      ELSE m.res_name END) AS rname
            ');

        $builder->join('main m', 'm.diary_no = d.diary_no');
        $builder->join('advance_single_judge_allocated h', 'h.diary_no = m.diary_no');
        $builder->where('d.board_type', 'S');
        $builder->where('d.from_dt', $from_dt);
        $builder->where('d.to_dt', $to_dt);
        $builder->where('h.from_dt', $from_dt);
        $builder->where('h.to_dt', $to_dt);
        $builder->whereIn('d.display', ['Y', 'R']);
        $builder->orderBy('d.clno');

        return $builder->get()->getResultArray();
    }

    public function getNotes($list_dt, $board_type)
    {
        //REmove vkg
        //$list_dt ='2023-05-02';
        if ($board_type === 'C')
        {
           
            $list_dt_exploded = explode("_", $list_dt);
            $list_dt_min = date('Y-m-d', strtotime($list_dt_exploded[0]));
            $list_dt_max = isset($list_dt_exploded[1]) ? date('Y-m-d', strtotime($list_dt_exploded[1])) : '1970-01-01';
            $next_dts = "AND d.cl_date BETWEEN '$list_dt_min' AND '$list_dt_max' AND h.next_dt BETWEEN '$list_dt_min' AND '$list_dt_max'";
        }
        else
        {
            
            $next_dts = "AND d.cl_date = '$list_dt' AND h.next_dt = '$list_dt'";
        }
        
        $sql = "SELECT
                        d.clno,
                        h.next_dt AS p_next_dt,
                        h.brd_slno AS p_brd_slno,
                        COALESCE(d.nrs, '-') AS nrs,
                        d.diary_no,
                        CASE
                            WHEN m.reg_no_display = '' THEN 'Diary No. ' || LEFT(m.diary_no::text, LENGTH(m.diary_no::text)-4) || '-' || RIGHT(m.diary_no::text, 4)
                            ELSE m.reg_no_display
                        END AS case_no,
                        CASE
                            WHEN pno = 2 THEN m.pet_name || ' AND ANR.'
                            WHEN pno > 2 THEN m.pet_name || ' AND ORS.'
                            ELSE m.pet_name
                        END AS pname,
                        CASE
                            WHEN rno = 2 THEN m.res_name || ' AND ANR.'
                            WHEN rno > 2 THEN m.res_name || ' AND ORS.'
                            ELSE m.res_name
                        END AS rname
                    FROM
                        public.advanced_drop_note d
                    INNER JOIN public.main m ON m.diary_no::text = d.diary_no::text  
                    INNER JOIN public.advance_allocated h ON h.diary_no::text = m.diary_no::text  
                    WHERE d.board_type = '$board_type' 
                       $next_dts
                     AND d.display IN ('Y', 'R')
                          
                    ORDER BY
                        d.clno";
        return $this->db->query($sql)->getResultArray();
    }

    public function getNotes_builder($list_dt, $board_type)
    {
        $builder = $this->db->table('public.advanced_drop_note d');
        $builder->join('public.main m', 'm.diary_no::text = d.diary_no::text');
        $builder->join('public.advance_allocated h', 'h.diary_no::text = m.diary_no::text');
        if ($board_type === 'C') {
            $list_dt_exploded = explode("_", $list_dt);
            $list_dt_min = date('Y-m-d', strtotime($list_dt_exploded[0]));
            $list_dt_max = isset($list_dt_exploded[1]) ? date('Y-m-d', strtotime($list_dt_exploded[1])) : '1970-01-01';

            $builder->where("d.cl_date BETWEEN '$list_dt_min' AND '$list_dt_max'");
            $builder->where("h.next_dt BETWEEN '$list_dt_min' AND '$list_dt_max'");
        } else {
            $builder->where('d.cl_date', $list_dt);
            $builder->where('h.next_dt', $list_dt);
        }

        $builder->select(
            '
                        d.clno,
                        h.next_dt AS p_next_dt,
                        h.brd_slno AS p_brd_slno,
                        COALESCE(d.nrs, \'-\') AS nrs,
                        d.diary_no,
                        CASE
                            WHEN m.reg_no_display = \'\' THEN CONCAT(\'Diary No. \', LEFT(m.diary_no::text, LENGTH(m.diary_no::text)-4), \'-\', RIGHT(m.diary_no::text, 4))
                            ELSE m.reg_no_display
                        END AS case_no,
                        CASE
                            WHEN pno = 2 THEN CONCAT(m.pet_name, \' AND ANR.\')
                            WHEN pno > 2 THEN CONCAT(m.pet_name, \' AND ORS.\')
                            ELSE m.pet_name
                        END AS pname,
                        CASE
                            WHEN rno = 2 THEN CONCAT(m.res_name, \' AND ANR.\')
                            WHEN rno > 2 THEN CONCAT(m.res_name, \' AND ORS.\')
                            ELSE m.res_name
                        END AS rname'
        );

        // $builder->whereIn('d.display', ['Y', 'R']); 

        $builder->orderBy('d.clno');
        $builder->limit(10);
        return $builder->get()->getResultArray();
    }

    public function getAdvocate($diary_no)
    {
        $builder = "SELECT
                        a.diary_no,  
                        a.name,      
                        STRING_AGG(CASE WHEN pet_res = 'R' THEN grp_adv END, '' ORDER BY adv_type DESC, pet_res_no ASC) AS r_n,
                        STRING_AGG(CASE WHEN pet_res = 'P' THEN grp_adv END, '' ORDER BY adv_type DESC, pet_res_no ASC) AS p_n
                    FROM (
                        SELECT
                            a.diary_no,
                            b.name,
                            STRING_AGG(COALESCE(a.adv, ''), '' ORDER BY pet_res ASC, adv_type DESC, pet_res_no ASC) AS grp_adv,
                            a.pet_res,
                            a.adv_type,
                            a.pet_res_no
                        FROM
                            advocate a
                        LEFT JOIN
                            master.bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y'
                        WHERE
                            a.diary_no = '$diary_no'  
                            AND a.display = 'Y'
                        GROUP BY
                            a.diary_no, b.name, a.pet_res, a.adv_type, a.pet_res_no  
                        ORDER BY
                            pet_res ASC, adv_type DESC, pet_res_no ASC
                    ) a
                    GROUP BY
                        a.diary_no, a.name;  ";


        return $this->db->query($builder)->getRowArray();
    }

    public function getAdvocate_builder($diary_no)
    {
        // Start building the query
        $builder = $this->db->table('advocate a');

        // Correct the SELECT statement with proper ORDER BY inside STRING_AGG
        $builder->select('
            a.diary_no,  
            a.name,      
            STRING_AGG(CASE WHEN pet_res = \'R\' THEN grp_adv END, \'\' ORDER BY adv_type DESC, pet_res_no ASC) AS r_n,
            STRING_AGG(CASE WHEN pet_res = \'P\' THEN grp_adv END, \'\' ORDER BY adv_type DESC, pet_res_no ASC) AS p_n
        ');

        // Join with master.bar table using left join
        $builder->join('master.bar b', 'a.advocate_id = b.bar_id AND b.isdead != \'Y\'', 'left')
            ->where('a.diary_no', $diary_no)
            ->where('a.display', 'Y')
            ->groupBy('a.diary_no, b.name, a.pet_res, a.adv_type, a.pet_res_no')
            ->orderBy('pet_res ASC, adv_type DESC, pet_res_no ASC');

        // Subquery part to handle the aggregation for grp_adv
        $subquery = $this->db->table('advocate a');
        $subquery->select('
            a.diary_no,
            b.name,
            STRING_AGG(COALESCE(a.adv, \'\'), \'\' ORDER BY pet_res ASC, adv_type DESC, pet_res_no ASC) AS grp_adv,
            a.pet_res,
            a.adv_type,
            a.pet_res_no
        ');

        // Left Join on master.bar with correct conditions
        $subquery->join('master.bar b', 'a.advocate_id = b.bar_id AND b.isdead != \'Y\'', 'left')
            ->where('a.diary_no', $diary_no)
            ->where('a.display', 'Y')
            ->groupBy('a.diary_no, b.name, a.pet_res, a.adv_type, a.pet_res_no')
            ->orderBy('pet_res ASC, adv_type DESC, pet_res_no ASC');

        // Use the subquery as the source for the outer query
        $builder->from('(' . $subquery->getCompiledSelect() . ') a');

        // Group by necessary fields
        $builder->groupBy('a.diary_no, a.name');
        echo $builder->getCompiledSelect();
        die;

        // Execute and return the query result
        return $builder->get()->getRowArray();
    }

    public function getCourtNo($p_r_id)
    {
        $sqq = "select courtno from master.roster where id = '" . $p_r_id . "' and display = 'Y'";
        return $this->db->query($sqq)->getRowArray();
    }



    private function isValidDate($date)
    {
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    public function getUpcomingDates()
    {
        $builder = $this->db->table('advance_cl_printed')
            ->select('next_dt')
            ->where('next_dt >=', date('Y-m-d'))
            ->groupBy('next_dt');
        return $builder->get()->getResultArray();
    }
}
