<?php

namespace App\Models\CJI;

use CodeIgniter\Model;

class Cji extends Model
{

    protected $table = 'master.judge';

    //   protected $primaryKey = 'casecode';
    //   protected $allowedFields = ['casecode', 'skey', 'casename', 'short_description', 'display'];

    public function getJudge($jcode = null)
    {

        $builder = $this->db->table('master.judge');
        $builder->where('display', 'Y');

        if ($jcode !== null) {
            $builder->whereIn('jcode', $jcode);
        } else {
            $builder->where('is_retired', 'N');
        }
        $builder->orderBy('jtype', 'ASC');
        $builder->orderBy('judge_seniority', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function ropNotUploaded($causeListFromDate, $causeListToDate, $pJudge)
    {
        $causeListFromDate = $this->parseDate($causeListFromDate);
        $causeListToDate = $this->parseDate($causeListToDate);


        $db = \Config\Database::connect(); // Connect to the database

        // Define your SQL query as a raw string
        $sql = "
SELECT DISTINCT listed.* 
FROM (
    SELECT DISTINCT
        rj.roster_id,
        m.diary_no,
        hd.board_type,
        hd.next_dt AS listing_date,
        m.pet_name AS petitioner_name,
        m.res_name AS respondent_name,
        r.courtno AS court_number,
        hd.brd_slno AS item_number,
        CASE 
            WHEN hd.listed_ia = '' THEN m.reg_no_display 
            ELSE CONCAT('IA ', hd.listed_ia, ' in ', m.reg_no_display) 
        END AS registration_number_desc,
        m.pno,
        m.rno
    FROM heardt hd
    INNER JOIN main m ON hd.diary_no = m.diary_no
    INNER JOIN master.roster_judge rj ON hd.roster_id = rj.roster_id
    INNER JOIN master.roster r ON rj.roster_id = r.id
    INNER JOIN cl_printed cp ON hd.roster_id = cp.roster_id
        AND hd.next_dt = cp.next_dt
        AND hd.brd_slno BETWEEN cp.from_brd_no AND cp.to_brd_no
        AND hd.clno = cp.part
    LEFT JOIN case_remarks_multiple crm ON hd.diary_no = crm.diary_no 
        AND hd.next_dt = crm.cl_date 
        AND r_head != 19
    WHERE cp.display = 'Y'
        AND hd.main_supp_flag != 0
        AND (hd.conn_key IS NULL OR hd.conn_key = 0 OR hd.conn_key = hd.diary_no)
        AND hd.brd_slno IS NOT NULL
        AND hd.brd_slno > 0
        AND hd.next_dt BETWEEN '$causeListFromDate' AND '$causeListToDate'
        AND rj.judge_id = $pJudge 
    
    UNION
    
    SELECT DISTINCT
        rj.roster_id,
        m.diary_no,
        hd.board_type,
        hd.next_dt AS listing_date,
        m.pet_name AS petitioner_name,
        m.res_name AS respondent_name,
        r.courtno AS court_number,
        hd.brd_slno AS item_number,
        CASE 
            WHEN hd.listed_ia = '' THEN m.reg_no_display 
            ELSE CONCAT('IA ', hd.listed_ia, ' in ', m.reg_no_display) 
        END AS registration_number_desc,
        m.pno,
        m.rno
    FROM last_heardt hd
    INNER JOIN main m ON hd.diary_no = m.diary_no
    INNER JOIN master.roster_judge rj ON hd.roster_id = rj.roster_id
    INNER JOIN master.roster r ON rj.roster_id = r.id
    INNER JOIN cl_printed cp ON hd.roster_id = cp.roster_id
        AND hd.next_dt = cp.next_dt
        AND hd.brd_slno BETWEEN cp.from_brd_no AND cp.to_brd_no
        AND hd.clno = cp.part
    LEFT JOIN case_remarks_multiple crm ON hd.diary_no = crm.diary_no 
        AND hd.next_dt = crm.cl_date 
        AND r_head != 19
    WHERE cp.display = 'Y'
        AND hd.main_supp_flag != 0
        AND (hd.conn_key IS NULL OR hd.conn_key = 0 OR hd.conn_key = hd.diary_no)
        AND hd.brd_slno IS NOT NULL
        AND hd.brd_slno > 0
        AND hd.bench_flag = ''
       AND hd.next_dt BETWEEN '$causeListFromDate' AND '$causeListToDate'
        AND rj.judge_id = $pJudge 
) listed
LEFT JOIN ordernet o ON listed.diary_no = o.diary_no 
    AND listed.listing_date = o.orderdate 
    AND o.display = 'Y' 
    AND o.type = 'O'
WHERE o.diary_no IS NULL 
ORDER BY listing_date, board_type, court_number, item_number
";


        $query = $db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

    private function parseDate($date)
    {
        $myDate = \DateTime::createFromFormat('d-m-Y', $date);
        if ($myDate === false) {
            throw new \InvalidArgumentException("Invalid date format: $date. Expected format is dd-mm-yyyy.");
        }
        return $myDate->format('Y-m-d');
    }
}
