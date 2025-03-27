<?php

namespace App\Models\Exchange;

use CodeIgniter\Model;

class Matters_Listed extends Model
{
    protected $table = 'heardt';
    protected $primaryKey = 'id';

    public function getListingDataBK($courtNo, $date1)
    {
        /*if (!$courtNo || !$date1) {
            throw new \Exception("Invalid input for courtNo or date");
        }*/

        if (!$courtNo || !$date1) {
            return [];
        }    
        $date1 = date('Y-m-d', strtotime($date1));
        $builder1 = $this->db->table('heardt hd')
            ->select('DATE(hd.next_dt) AS listing_date, r.courtno AS court_number, hd.brd_slno AS item_number, CAST(hd.roster_id AS text) AS roster_id')
            ->join('main m', 'CAST(hd.diary_no AS varchar) = CAST(m.diary_no AS varchar)', 'inner')
            ->join('master.roster_judge rj', 'hd.roster_id = rj.roster_id', 'inner')
            ->join('master.roster r', 'rj.roster_id = r.id', 'inner')
            ->join('cl_printed cp', 'hd.roster_id = cp.roster_id AND hd.next_dt = cp.next_dt AND hd.brd_slno BETWEEN cp.from_brd_no AND cp.to_brd_no AND hd.clno = cp.part', 'inner')
            ->where('cp.display', 'Y')
            ->where('hd.main_supp_flag !=', 0)
            ->where('hd.brd_slno IS NOT NULL')
            ->where('hd.brd_slno >', 0)
            ->where('DATE(hd.next_dt)', $date1)
            ->where('r.courtno', $courtNo);

        // Query 2
        $builder2 = $this->db->table('last_heardt hd')
            ->select('DATE(hd.next_dt) AS listing_date, r.courtno AS court_number, hd.brd_slno AS item_number, CAST(hd.roster_id AS text) AS roster_id')
            ->join('main m', 'CAST(hd.diary_no AS varchar) = CAST(m.diary_no AS varchar)', 'inner')
            ->join('master.roster_judge rj', 'hd.roster_id = rj.roster_id', 'inner')
            ->join('master.roster r', 'rj.roster_id = r.id', 'inner')
            ->join('cl_printed cp', 'hd.roster_id = cp.roster_id AND hd.next_dt = cp.next_dt AND hd.brd_slno BETWEEN cp.from_brd_no AND cp.to_brd_no AND hd.clno = cp.part', 'inner')
            ->where('cp.display', 'Y')
            ->where('hd.main_supp_flag !=', 0)
            ->where('hd.brd_slno IS NOT NULL')
            ->where('hd.brd_slno >', 0)
            ->where('DATE(hd.next_dt)', $date1)
            ->where('r.courtno', $courtNo);

        // Query 3
        $builder3 = $this->db->table('mention_memo mm')
            ->select('DATE(mm.date_on_decided) AS listing_date, r.courtno AS court_number, mm.m_brd_slno AS item_number, NULL AS roster_id')
            ->join('main m', 'CAST(mm.diary_no AS varchar) = CAST(m.diary_no AS varchar)', 'inner')
            ->join('master.roster_judge rj', 'mm.m_roster_id = rj.roster_id', 'inner')
            ->join('master.roster r', 'rj.roster_id = r.id', 'inner')
            ->where('mm.display', 'Y')
            ->where('mm.m_brd_slno IS NOT NULL')
            ->where('mm.m_brd_slno >', 0)
            ->where('DATE(mm.date_on_decided)', $date1)
            ->where('r.courtno', $courtNo);

        // Compile individual queries
        $sql1 = $builder1->getCompiledSelect();
        $sql2 = $builder2->getCompiledSelect();
        $sql3 = $builder3->getCompiledSelect();

        // Combine the queries using UNION
        $combinedSql = "SELECT listing_date, court_number, item_number, roster_id FROM ($sql1) sub1
                        UNION
                        SELECT listing_date, court_number, item_number, roster_id FROM ($sql2) sub2
                        UNION
                        SELECT listing_date, court_number, item_number, roster_id FROM ($sql3) sub3";

        // Subquery for file movement
        $builderSub = $this->db->table('causelist_file_movement_transactions cfmt')
            ->select('DATE(next_dt) AS listing_date, COUNT(*) AS received, CAST(roster_id AS text) AS roster_id')
            ->join('causelist_file_movement cfm', 'cfm.id = cfmt.causelist_file_movement_id', 'left')
            ->where('cfmt.ref_file_movement_status_id', 2)
            ->groupBy('DATE(next_dt), roster_id');
        
        $subQuery = $builderSub->getCompiledSelect();

        // Complete the final SQL query
        $finalSql = "SELECT a.listing_date, a.court_number, count(*) as listed, a.item_number, COALESCE(b.received, 0) AS received
                     FROM ($combinedSql) a
                     LEFT JOIN ($subQuery) b ON a.listing_date = b.listing_date AND a.roster_id = b.roster_id
                     GROUP BY a.listing_date, a.court_number, a.item_number, b.received
                     ORDER BY a.listing_date";
        //pr($finalSql);
        $query = $this->db->query($finalSql);

        return $query->getResultArray();
    }


    public function getListingData($courtNo, $date)
    {
        /*if (!$courtNo || !$date1) {
            throw new \Exception("Invalid input for courtNo or date");
        }*/
        $return = [];
        if (!$courtNo || !$date) {
            return $return;
        }    
        $date = date('Y-m-d', strtotime($date));
        $sql="select a.listing_date,count(*) as listed,received from(SELECT DISTINCT
                    rj.roster_id,
                    m.diary_no,
                    hd.next_dt AS listing_date,
                    r.courtno AS court_number,hd.brd_slno AS item_number
                FROM
                    heardt hd
                        INNER JOIN
                    main m ON hd.diary_no = m.diary_no
                        INNER JOIN
                    master.roster_judge rj ON hd.roster_id = rj.roster_id
                        INNER JOIN
                    master.roster r ON rj.roster_id = r.id
                        INNER JOIN
                    cl_printed cp ON hd.roster_id = cp.roster_id
                        AND hd.next_dt = cp.next_dt
                        AND hd.brd_slno BETWEEN cp.from_brd_no AND cp.to_brd_no
                        AND hd.clno = cp.part
                WHERE
                    cp.display = 'Y'
                        AND hd.main_supp_flag != 0
                    
                        AND hd.brd_slno IS NOT NULL
                        AND hd.brd_slno > 0
                        AND hd.next_dt ='". $date."' and r.courtno='".$courtNo."'    
                    
                UNION SELECT DISTINCT
                    rj.roster_id,
                    m.diary_no,
                    hd.next_dt AS listing_date,
                    r.courtno AS court_number,hd.brd_slno AS item_number
                FROM
                    last_heardt hd
                        INNER JOIN
                    main m ON hd.diary_no = m.diary_no
                        INNER JOIN
                    master.roster_judge rj ON hd.roster_id = rj.roster_id
                        INNER JOIN
                    master.roster r ON rj.roster_id = r.id
                        INNER JOIN
                    cl_printed cp ON hd.roster_id = cp.roster_id
                        AND hd.next_dt = cp.next_dt
                        AND hd.brd_slno BETWEEN cp.from_brd_no AND cp.to_brd_no
                        AND hd.clno = cp.part
                WHERE
                    cp.display = 'Y'
                        AND hd.main_supp_flag != 0
                    
                        AND hd.brd_slno IS NOT NULL
                        AND hd.brd_slno > 0
                        AND hd.bench_flag = ''
                        AND hd.next_dt ='". $date."' and r.courtno='".$courtNo."'    
                UNION SELECT DISTINCT
                    rj.roster_id,
                    m.diary_no,
                    mm.date_on_decided AS listing_date,
                    r.courtno AS court_number,mm.m_brd_slno AS item_number
                FROM
                    mention_memo mm
                    INNER JOIN main m ON CAST(mm.diary_no AS varchar) = CAST(m.diary_no AS varchar)
                        INNER JOIN
                    master.roster_judge rj ON mm.m_roster_id = rj.roster_id
                        INNER JOIN
                    master.roster r ON rj.roster_id = r.id
                WHERE
                    mm.display = 'Y'
                    AND mm.m_brd_slno IS NOT NULL
                    AND mm.m_brd_slno > 0
                    AND mm.date_on_decided ='". $date."' and r.courtno='".$courtNo."') a
                    join (select date(next_dt) as listing_date,count(*) as received,roster_id from causelist_file_movement_transactions cfmt 
                    left join causelist_file_movement cfm on cfm.id=cfmt.causelist_file_movement_id where cfmt.ref_file_movement_status_id=2
                    group by date(next_dt), cfm.roster_id) b on a.listing_date=b.listing_date and a.roster_id=b.roster_id 
                    group by a.listing_date, b.received
                    order by listing_date";
                    $res1 = $this->db->query($sql);
                    if ($res1->getNumRows() >= 1) {
                        $return = $res1->getResultArray();
                    }
                    return $return;
                                            
    }
}
