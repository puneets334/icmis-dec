<?php

namespace App\Models\HybridHearing;

use CodeIgniter\Model;

class VcConsentModal extends Model
{

    protected $db;

    public function __construct() {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    public function getCasesData($listingDts, $listType, $judgeCode, $courtNo) {
        $currentDate = date('Y-m-d');
        $mainhead_query = "";
        $board_type_query = "";
        if(isset($listType) && !empty($listType) && $listType !=0) {
            if($listType == 4) {
                $mainhead_query = "and h.mainhead = 'M'";
                $board_type_query = "and h.board_type = 'J'";
            } else if($listType == 3) {
                $mainhead_query = "and h.mainhead = 'F'";
                $board_type_query = "and h.board_type = 'J'";
            } else if($listType == 5) {
                $mainhead_query = "and h.mainhead = 'M'";
                $board_type_query = "and h.board_type = 'C'";
            } else if($listType == 6) {
                $mainhead_query = "and h.mainhead = 'M'";
                $board_type_query = "and h.board_type = 'R'";
            } else {
                echo "List Type Not Defined";
                exit();
            }
        }
        if($courtNo > 0) {
            if($courtNo < 20) {
                $judgeCode_query = "and (r.courtno = ".$courtNo." OR r.courtno = ".($courtNo+30).")";
            } else if($courtNo >= 21) {
                $judgeCode_query = "and (r.courtno = ".$courtNo." OR r.courtno = ".($courtNo+40).")";
            } else {
                echo "Wrong Court Number Selected";
                exit;
            }
        } else if($judgeCode > 0) {
            $judgeCode_query = "and rj.judge_id = $judgeCode";
        } else {
            $judgeCode_query = "";
        }
        $sql = "";
        $sql = "SELECT 
            cl.id AS is_printed, 
            m.reg_no_display,
            m.pet_name,
            m.res_name,
            h.main_supp_flag,
            h.board_type,
            h.judges,
            h.roster_id,
            h.brd_slno,
            h.clno,
            h.mainhead,
            h.next_dt,
            h.conn_key,
            h.diary_no,
            r.courtno,
            STRING_AGG(a.advocate_id::text, ',') AS advocate_ids,
            COUNT(DISTINCT a.advocate_id) AS total_advocates
            FROM main m
            INNER JOIN heardt h ON m.diary_no = h.diary_no
            LEFT JOIN conct ct ON m.diary_no = ct.diary_no AND ct.list = 'Y'
            INNER JOIN master.roster r ON h.roster_id = r.id
            INNER JOIN master.roster_judge rj ON rj.roster_id = r.id
            INNER JOIN advocate a ON m.diary_no = a.diary_no
            INNER JOIN cl_printed cl ON h.next_dt = cl.next_dt 
                AND cl.part = h.clno 
                AND h.roster_id = cl.roster_id 
                AND cl.display = 'Y'
            WHERE 
            a.display = 'Y' 
            AND r.display = 'Y' 
            AND rj.display = 'Y' 
            AND m.c_status = 'P'
            AND h.next_dt = '$listingDts'
            $mainhead_query
            $board_type_query
            $judgeCode_query
            AND h.brd_slno > 0
            GROUP BY m.diary_no,cl.id,h.main_supp_flag,h.board_type,h.judges,h.roster_id,h.brd_slno,h.clno,h.mainhead,h.next_dt,h.conn_key,h.diary_no,r.courtno,ct.ent_dt
            ORDER BY 
            r.courtno,
            h.next_dt,
            h.brd_slno,
            CASE WHEN h.conn_key = h.diary_no THEN 1 ELSE 99 END ASC,
            CASE WHEN ct.ent_dt IS NOT NULL THEN ct.ent_dt ELSE TO_DATE('9999-12-31','YYYY-MM-DD') END ASC,
            CAST(RIGHT(m.diary_no::text, 4) AS INTEGER) ASC,
            CAST(LEFT(m.diary_no::text, LENGTH(m.diary_no::text) - 4) AS INTEGER) ASC;";
        $res = $this->db->query($sql);
        $response = $res->getResultArray();
        return $response;
    }

    public function getAorData($diary_no, $next_dt, $roster_id, $advocate_ids) {
        
    }

}