<?php

namespace App\Models\Record_room;

use CodeIgniter\Model;
use App\Models\Entities\Model_Ac;


class TransactionModel extends Model
{

    protected $table = 'transactions';

    protected $allowedFields = [
        'acid',
        'event_code',
        'event_date',
        'updated_by',
        'updated_on',
        'remarks',
        'updatedip'
    ];


    public function __construct()
    {
        parent::__construct();
        $db = \Config\Database::connect();
        $this->db = db_connect();
    }


    public function getTransactionDetails($tid)
    {
        if (empty($tid) || !is_numeric($tid)) {
            return [];
        }

        return $this->select("concat(event_name, ' dated ', TO_CHAR(event_date, 'DD-MM-YYYY'), ' updated on ', TO_CHAR(event_master.updated_on, 'DD-MM-YYYY'), '; Remarks: ', remarks) as temp")
            ->join('master.event_master', 'event_master.event_code = transactions.event_code')
            ->where('transactions.acid', $tid)
            ->findAll();
    }

    public function getRecordRoomRep($list_date)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('master.roster_judge rj');
        $builder->select('rj.roster_id, STRING_AGG(DISTINCT j.jcode::text, \',\') AS judge_code, STRING_AGG(DISTINCT j.jname, \',\') AS judge_name', false);
        $builder->join('master.judge j', 'j.jcode = rj.judge_id', 'left');
        $builder->where('j.is_retired !=', 'Y');
        $builder->where('j.display', 'Y');
        $builder->where('rj.display', 'Y');
        $builder->groupBy('rj.roster_id');
        $judgeAggSubquery = $builder->getCompiledSelect();

        // Main query
        $builder = $db->table('cl_printed p');
        $builder->select('
            CASE
                WHEN r.courtno BETWEEN 31 AND 60 THEN (r.courtno - 30)
                WHEN r.courtno BETWEEN 61 AND 70 THEN (r.courtno - 40)
                ELSE r.courtno 
            END AS court_sorting,
            r.frm_time,
            u.name AS username,
            v.created_on,
            v.vc_url,
            p.roster_id,
            p.next_dt,
            p.main_supp,
            p.m_f,
            r.courtno,
            mb.board_type_mb,
            ja.judge_code,
            ja.judge_name,
            STRING_AGG(DISTINCT CASE 
                WHEN from_brd_no = to_brd_no THEN to_brd_no::text 
                ELSE CONCAT(from_brd_no::text, \'-\', to_brd_no::text) 
            END, \',\') AS item_numbers
        ', false);

        $builder->join('master.roster r', 'r.id = p.roster_id', 'inner');
        $builder->join('master.roster_bench rb', 'rb.id = r.bench_id', 'left');
        $builder->join('master.master_bench mb', 'mb.id = rb.bench_id', 'left');
        $builder->join("($judgeAggSubquery) ja", 'ja.roster_id = p.roster_id', 'left');
        $builder->join('vc_room_details v', 'v.roster_id = p.roster_id AND v.next_dt = p.next_dt AND v.display = \'Y\'', 'left');
        $builder->join('master.users u', 'u.usercode = v.created_by', 'left');

        $builder->where('r.display', 'Y');
        $builder->where('rb.display', 'Y');
        $builder->where('mb.display', 'Y');
        $builder->where('r.courtno >=', 1);
        $builder->where('r.courtno <=', 70);
        $builder->where('p.display', 'Y');
        $builder->where('p.next_dt', $list_date);
        $builder->whereIn('p.main_supp', [1, 2]);
        $builder->where('mb.board_type_mb !=', 'CC');

        $builder->groupBy('court_sorting, r.frm_time, u.name, v.created_on, v.vc_url, p.roster_id, p.next_dt, p.main_supp, p.m_f, r.courtno, mb.board_type_mb, ja.judge_code, ja.judge_name');
        $builder->orderBy('court_sorting, p.main_supp');

        //pr($builder->getCompiledSelect());
        $query = $builder->get();
        $result = $query->getResult();
    }


    public function getVcLinks($vc_date)
    {
        $builder = $this->db->table('cl_printed p');

        $builder->select("DISTINCT 
            CASE 
                WHEN r.courtno BETWEEN 31 AND 60 THEN (r.courtno - 30)
                WHEN r.courtno BETWEEN 61 AND 70 THEN (r.courtno - 40)
                ELSE r.courtno 
            END AS court_sorting, 
            r.frm_time, 
            u.name AS username, 
            v.created_on, 
            v.vc_url, 
            p.roster_id, 
            p.next_dt, 
            p.main_supp, 
            p.m_f, 
            r.courtno, 
            mb.board_type_mb, 
            STRING_AGG(j.jcode::text, ',' ORDER BY j.judge_seniority) AS judge_code, 
            STRING_AGG(j.jname, ',' ORDER BY j.judge_seniority) AS judge_name, 
            STRING_AGG(
                CASE 
                    WHEN from_brd_no = to_brd_no THEN to_brd_no::text 
                    ELSE from_brd_no || '-' || to_brd_no 
                END, ',' ORDER BY from_brd_no) AS item_numbers");
        $builder->join('master.roster r', 'r.id = p.roster_id');
        $builder->join('master.roster_bench rb', 'rb.id = r.bench_id', 'left');
        $builder->join('master.master_bench mb', 'mb.id = rb.bench_id', 'left');
        $builder->join('master.roster_judge rj', 'rj.roster_id = r.id', 'left');
        $builder->join('master.judge j', 'j.jcode = rj.judge_id', 'left');
        $builder->join('vc_room_details v', 'v.roster_id = p.roster_id AND v.next_dt = p.next_dt AND v.display = \'Y\'', 'left');
        $builder->join('master.users u', 'u.usercode = v.created_by', 'left');
        $builder->where('r.display', 'Y');
        $builder->where('rb.display', 'Y');
        $builder->where('mb.display', 'Y');
        $builder->where('j.is_retired !=', 'Y');
        $builder->where('j.display', 'Y');
        $builder->where('rj.display', 'Y');
        $builder->where('r.courtno BETWEEN 1 AND 70');
        $builder->where('p.display', 'Y');
        $builder->where('p.next_dt', $vc_date);
        $builder->whereIn('p.main_supp', [1, 2]);
        $builder->where('mb.board_type_mb !=', 'CC');
        $builder->groupBy('r.frm_time, u.name, v.created_on, v.vc_url, p.roster_id, p.next_dt, p.main_supp, p.m_f, r.courtno, mb.board_type_mb');
        $builder->orderBy('court_sorting, r.frm_time');

        return $builder->get()->getResultArray();
    }

    public function getCourtDetails($next_dt)
    {
        $builder = $this->db->table('cl_printed p')
            ->select(" 
                CASE
                    WHEN r.courtno BETWEEN 31 AND 60 THEN (r.courtno - 30)
                    WHEN r.courtno BETWEEN 61 AND 70 THEN (r.courtno - 40)
                    ELSE r.courtno
                END AS court_sorting,
                r.frm_time, u.name AS username, v.created_on, v.vc_url, p.roster_id, 
                p.next_dt, p.main_supp, p.m_f, r.courtno, mb.board_type_mb, 
                STRING_AGG(DISTINCT j.jcode::TEXT, ', ') AS judge_code, 
                STRING_AGG(DISTINCT j.jname::TEXT, ', ') AS judge_name,
                STRING_AGG(DISTINCT CASE 
                    WHEN from_brd_no = to_brd_no THEN to_brd_no::TEXT 
                    ELSE CONCAT(from_brd_no::TEXT, '-', to_brd_no::TEXT) 
                END, ', ') AS item_numbers")
            ->join('master.roster r', 'r.id = p.roster_id')
            ->join('master.roster_bench rb', 'rb.id = r.bench_id', 'left')
            ->join('master.master_bench mb', 'mb.id = rb.bench_id', 'left')
            ->join('master.roster_judge rj', 'rj.roster_id = r.id', 'left')
            ->join('master.judge j', 'j.jcode = rj.judge_id', 'left')
            ->join('vc_room_details v', 'v.roster_id = p.roster_id AND v.next_dt = p.next_dt AND v.display = \'Y\'', 'left')
            ->join('master.users u', 'u.usercode = v.created_by', 'left')
            ->where('r.display', 'Y')
            ->where('rb.display', 'Y')
            ->where('mb.display', 'Y')
            ->where('j.is_retired !=', 'Y')
            ->where('j.display', 'Y')
            ->where('rj.display', 'Y')
            ->where('r.courtno BETWEEN 1 AND 70')
            ->where('p.display', 'Y')
            ->where('p.next_dt', $next_dt)
            ->where('p.main_supp IN (1, 2)')
            ->where('mb.board_type_mb !=', 'CC')
            ->groupBy('court_sorting, r.frm_time, u.name, v.created_on, v.vc_url, p.roster_id, p.next_dt, p.main_supp, p.m_f, r.courtno, mb.board_type_mb, r.id') // Added r.id here
            ->orderBy('court_sorting, r.id'); // Now both ordered columns are part of the GROUP BY
    
        return $builder->get()->getResultArray();
    }

    public function getCourtDetailsById($courtId)
{
    return $this->db->table('cl_printed')
        ->where('roster_id', $courtId)
        ->get()
        ->getRowArray(); 
}
    
    
    
    
}
