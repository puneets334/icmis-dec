<?php

namespace App\Models\Exchange;

use CodeIgniter\Model;

class CauseListFileMovementModel extends Model
{
    protected $eservicesdb;

    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
        $this->eservicesdb = \Config\Database::connect('eservices');
    }

    public function getCasesToReceiveFromDA($causelistDate,$usercode,$status_id=1)
    {
        $queryString="SELECT DISTINCT
            cfm.id AS causelist_file_movement_id,
            (SELECT CONCAT(name, '(', empid, ')') FROM master.users WHERE usercode = cfmt.attendant_usercode) AS attendant,
            (SELECT CONCAT(name, '(', empid, ')') FROM master.users WHERE usercode = cfmt.usercode) AS updated_by,
            cfmt.updated_on,
            m.dacode,
            rj.roster_id,
            m.diary_no,
            hd.next_dt AS listing_date,
            m.pet_name AS petitioner_name,
            m.res_name AS respondent_name,
            r.courtno AS court_number,
            hd.brd_slno AS item_number,
            CASE
                WHEN (hd.listed_ia = '' OR hd.listed_ia IS NULL) THEN m.reg_no_display
                ELSE CONCAT('IA ', hd.listed_ia, ' in ', m.reg_no_display)
            END AS registration_number_desc,
            CONCAT
            (
                COALESCE(m.reg_no_display, ''),
                ' @ ',
                CONCAT(LEFT(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 4), '/', SUBSTRING(CAST(m.diary_no AS TEXT) FROM -4))
            ) AS case_no,
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
        LEFT JOIN causelist_file_movement cfm ON hd.diary_no = cfm.diary_no
            AND hd.next_dt = cfm.next_dt
            AND hd.roster_id = cfm.roster_id
        INNER JOIN causelist_file_movement_transactions cfmt ON cfm.id = cfmt.causelist_file_movement_id
            AND cfm.ref_file_movement_status_id = cfmt.ref_file_movement_status_id
        WHERE cfm.ref_file_movement_status_id = $status_id
            AND cp.display = 'Y'
            AND hd.main_supp_flag != 0
            AND (hd.conn_key IS NULL OR hd.conn_key = 0 OR hd.conn_key = hd.diary_no)
            AND hd.brd_slno IS NOT NULL
            AND hd.brd_slno > 0
            AND hd.next_dt = ?
            AND (m.dacode = ? OR TRUE)
        ORDER BY court_number, item_number";
        $query = $this->db->query($queryString,array($causelistDate,$usercode));
        if ($query->getNumRows() >= 1)
        {
            return $query->getResultArray();
        }
        else
        {
            return [];
        }
    }

    public function saveTransactionDetails(array $dataForMovementTransaction)
    {
        $this->db->table('causelist_file_movement_transactions')->insert($dataForMovementTransaction);

        $dataToUpdateMovement = [
            'ref_file_movement_status_id' => $dataForMovementTransaction['ref_file_movement_status_id'],
            'usercode' => $dataForMovementTransaction['usercode'],
            'updated_on' => date('Y-m-d H:i:s')
        ];

        $this->db->table('causelist_file_movement')->where('id', $dataForMovementTransaction['causelist_file_movement_id'])->update($dataToUpdateMovement);
    }

    public function getCasesToReceiveFromCM($usercode)
    {
        $queryString = "SELECT 
            m.pet_name AS petitioner_name,
            m.res_name AS respondent_name,
            CASE 
                WHEN cfm.ref_file_movement_status_id = 3 THEN 'Returned By' 
                ELSE 'Sent By' 
            END AS movement_status,
            m.section_id,
            m.reg_no_display,
            cfm.id AS causelist_file_movement_id,
            (SELECT CONCAT(name, '(', empid, ')') FROM master.users WHERE usercode = cfmt.attendant_usercode) AS attendant,
            (SELECT CONCAT(name, '(', empid, ')') FROM master.users WHERE usercode = cfmt.usercode) AS updated_by,
            cfmt.updated_on
        FROM 
            main m
        INNER JOIN 
            causelist_file_movement cfm ON m.diary_no = cfm.diary_no
        INNER JOIN 
            causelist_file_movement_transactions cfmt ON cfm.id = cfmt.causelist_file_movement_id AND cfm.ref_file_movement_status_id = cfmt.ref_file_movement_status_id
        WHERE 
            (m.section_id = (SELECT section FROM master.users WHERE usercode = '$usercode') OR TRUE) AND 
            cfm.ref_file_movement_status_id IN (3, 4)";

        $query = $this->db->query($queryString);
        if ($query->getNumRows() >= 1)
        {
            return $query->getResultArray();
        }
        else
        {
            return [];
        }
    }

    public function getListedCases($causelistDate, $courtNo, $usercode)
    {
        $courtNoCondition = "";
        if($courtNo != 0)
        {
            $courtNoCondition = " and r.courtno=".$courtNo;
        }

        $queryString = "SELECT DISTINCT 
            cfm.id AS causelist_file_movement_id,
            m.dacode,
            rj.roster_id,
            m.diary_no,
            hd.next_dt AS listing_date,
            m.pet_name AS petitioner_name,
            m.res_name AS respondent_name,
            r.courtno AS court_number,
            hd.brd_slno AS item_number,
            CASE 
                WHEN (hd.listed_ia = '' OR hd.listed_ia IS NULL) 
                THEN m.reg_no_display 
                ELSE CONCAT('IA ', hd.listed_ia, ' in ', m.reg_no_display) 
            END AS registration_number_desc,
            CONCAT(
                COALESCE(m.reg_no_display, ''), ' @ ', CONCAT(LEFT(m.diary_no::TEXT, LENGTH(m.diary_no::TEXT) - 4), '/', SUBSTRING(m.diary_no::TEXT FROM LENGTH(m.diary_no::TEXT) - 3 FOR 4))
                   ) AS case_no,
            m.pno,
            m.rno 
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
        LEFT JOIN 
            causelist_file_movement cfm ON hd.diary_no = cfm.diary_no 
            AND hd.next_dt = cfm.next_dt 
            AND hd.roster_id = cfm.roster_id
        WHERE 
            cfm.diary_no IS NULL 
            AND cp.display = 'Y' 
            AND hd.main_supp_flag != 0 
            AND (hd.conn_key IS NULL OR hd.conn_key = 0 OR hd.conn_key = hd.diary_no) 
            AND hd.brd_slno IS NOT NULL 
            AND hd.brd_slno > 0 
            AND hd.next_dt = ? 
            AND (m.dacode = ? OR TRUE) 
            $courtNoCondition 
        ORDER BY court_number, item_number";

        $query = $this->db->query($queryString,array($causelistDate,$usercode));

        if ($query->getNumRows() >= 1)
        {
            return $query->getResultArray();
        }
        else
        {
            return [];
        }
    }

    public function getAttendant()
    {
        $queryString="SELECT u.* FROM master.users u
            INNER JOIN master.usertype ut ON u.usertype = ut.id
            WHERE u.display = ?
            AND ut.display = ?
            AND ut.id IN (20, 25, 26, 27)
            ORDER BY u.name ASC";
        $query = $this->db->query($queryString,array('Y','Y'));
        if ($query->getNumRows() >= 1)
        {
            return $query->getResultArray();
        }
        else
        {
            return [];
        }
    }

    public function saveDispatchFileToCM($dataForUpdate,$attendant)
    {
        $this->db->table('causelist_file_movement')->insert($dataForUpdate);
        $causelistFileMovementId = $this->db->insertID();
        $this->saveDispatchFileToCMTransaction($causelistFileMovementId,1,$attendant);
    }

    public function saveDispatchFileToCMTransaction($causelistFileMovementId,$ststusId,$attendant)
    {
        $dataForMovementTransaction = array(
            'causelist_file_movement_id' => $causelistFileMovementId,
            'ref_file_movement_status_id' => $ststusId,
            'attendant_usercode' => $attendant,
            'usercode' => $usercode = session()->get('login')['usercode'],
            'updated_on' => date('Y-m-d H:i:s')
        );
        $this->db->table('causelist_file_movement_transactions')->insert($dataForMovementTransaction);
    }

    function getCasesForSendBackToDA($causelistDate,$usercode)
    {
        $queryString = "SELECT DISTINCT 
            cfm.id AS causelist_file_movement_id,
            cfm.diary_no,
            cfm.next_dt,
            cfm.roster_id,
            r.courtno AS court_number,
            m.pet_name AS petitioner_name,
            m.res_name AS respondent_name,
            listing_detail.brd_slno AS item_number,
            (SELECT CONCAT(name, '(', empid, ')') FROM master.users WHERE usercode = cfm.dacode) AS daname,
            tentative_section(m.diary_no) AS section,
            CONCAT(COALESCE(m.reg_no_display, ''), ' @ ', CONCAT(LEFT(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 4), '/', SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3 FOR 4))) AS case_no
        FROM 
            causelist_file_movement cfm
        INNER JOIN 
            main m ON cfm.diary_no = m.diary_no
        INNER JOIN 
            (
                SELECT diary_no, brd_slno, next_dt, roster_id 
                FROM heardt 
                WHERE next_dt = ? AND brd_slno != 0 AND main_supp_flag IN (1, 2)
                UNION 
                SELECT diary_no, brd_slno, next_dt, roster_id 
                FROM last_heardt 
                WHERE next_dt = ? AND brd_slno != 0 AND main_supp_flag IN (1, 2) AND (bench_flag IS NULL OR bench_flag = '')
            ) AS listing_detail ON cfm.diary_no = listing_detail.diary_no AND cfm.roster_id = listing_detail.roster_id
        INNER JOIN 
            master.roster r ON cfm.roster_id = r.id
        WHERE 
            cfm.next_dt = ? AND (cfm.cm_nsh_usercode = ? OR TRUE) AND cfm.ref_file_movement_status_id = ?";

        $query = $this->db->query($queryString,array($causelistDate,$causelistDate,$causelistDate,$usercode,2));

        if ($query->getNumRows() >= 1)
        {
            return $query->getResultArray();
        }
        else
        {
            return [];
        }
    }

    public function dispatch_cl_cases($usercode)
    {
        $queryString = "SELECT * 
        FROM main m 
        INNER JOIN heardt h ON h.diary_no = m.diary_no 
        LEFT JOIN main m1 ON m1.diary_no = m.conn_key::bigint
        LEFT JOIN diary_copy_set ds ON ds.diary_no = h.diary_no 
        LEFT JOIN diary_movement dm ON dm.diary_copy_set = ds.id 
        WHERE m.dacode = $usercode
        AND h.next_dt >= CURRENT_DATE
        AND h.roster_id > 0 
        AND h.main_supp_flag IN (1, 2) 
        AND ds.copy_set = 'A' 
        ORDER BY h.next_dt, 
        h.judges, 
        COALESCE(NULLIF(NULLIF(m.conn_key, ''), '')::bigint, m.diary_no),
        CASE WHEN m.diary_no = NULLIF(m.conn_key, '')::bigint THEN 0 ELSE 1 END";

        $query = $this->db->query($queryString);

        if ($query->getNumRows() >= 1)
        {
            return $query->getResultArray();
        }
        else
        {
            return [];
        }
    }
}