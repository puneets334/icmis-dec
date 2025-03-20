<?php

namespace App\Controllers\HybridHearing;

use App\Controllers\BaseController;
use App\Models\Court\CourtMasterModel;

class Transfer_cases extends BaseController
{
    protected $CourtMasterModel;

    public function __construct()
    {
        $this->CourtMasterModel = new CourtMasterModel();
    }

    // View function to display available listing dates
    public function transfer_cases_view()
    {
        $db = \Config\Database::connect();
        $query = $db->table('heardt')
                    ->select('next_dt')
                    ->where('next_dt >=', date('Y-m-d'))
                    ->whereIn('main_supp_flag', [1, 2])
                    ->groupBy('next_dt')
                    ->orderBy('next_dt')
                    ->get();

        $dates = $query->getResultArray();

        return view('hybrid_hearing/transfer_cases', ['dates' => $dates]);
    }

    // Function to get transfer cases based on the posted listing date
    public function transfer_cases_get()
    {
        $request = \Config\Services::request();

        if ($request->getPost('listing_dts')) {
            $listing_dts = $request->getPost('listing_dts');

            $db = \Config\Database::connect();
            $builder = $db->table('consent_through_email c')
                ->select('c.roster_id AS old_roster_id, 
                        r1.courtno AS oldcourt,
                        STRING_AGG(j1.jname, \', \' ORDER BY j1.judge_seniority) AS old_coram,
                        h.roster_id AS new_roster_id, 
                        STRING_AGG(j2.jname, \', \' ORDER BY j2.judge_seniority) AS new_coram,
                        r2.courtno AS newcourt, 
                        h.mainhead, 
                        h.board_type,
                        COUNT(DISTINCT c.diary_no) AS total_cases, 
                        COUNT(DISTINCT (c.party_id || \'-\' || c.advocate_id)) AS total_concent')
                ->join('heardt h', 'c.diary_no = h.diary_no AND c.next_dt = h.next_dt')
                ->join('master.roster r1', 'r1.id = c.roster_id')
                ->join('master.roster_judge rj1', 'rj1.roster_id = r1.id')
                ->join('master.judge j1', 'j1.jcode = rj1.judge_id')
                ->join('master.roster r2', 'r2.id = h.roster_id')
                ->join('master.roster_judge rj2', 'rj2.roster_id = r2.id')
                ->join('master.judge j2', 'j2.jcode = rj2.judge_id')
                ->where('c.next_dt', $listing_dts)
                ->where('h.roster_id != c.roster_id')  
                ->where('c.is_deleted IS NULL')
                ->groupBy('c.roster_id, r1.courtno, h.roster_id, h.mainhead, h.board_type, r2.courtno');

            $query = $builder->get();

            if ($query->getNumRows() > 0) {
                $result = $query->getResultArray();

                return view('hybrid_hearing/transfer_cases_results', [
                    'dates' => $result,
                    'listing_dts' => $listing_dts
                ]);
            } else {
                return view('hybrid_hearing/transfer_cases_results', [
                    'dates' => [],
                    'listing_dts' => $listing_dts,
                    'message' => 'No cases found for the given date.'
                ]);
            }
        } else {
            return view('hybrid_hearing/transfer_cases', [
                'dates' => [], 
                'message' => 'Please select a mandatory listing date.'
            ]);
        }
    }

   
    public function transfer_cases_save()
    {
        $request = \Config\Services::request();
        
        if ($request->getPost()) {
            $next_dt = $request->getPost('next_dt');
            $old_roster_id = $request->getPost('old_roster_id');
            $new_roster_id = $request->getPost('new_roster_id');

            $db = \Config\Database::connect();

          
            $sqlQuery = "INSERT INTO consent_through_email (diary_no, conn_key, next_dt, roster_id, part, main_supp_flag, applicant_type, party_id, advocate_id, entry_source, user_id, entry_date, user_ip, is_deleted)
                         SELECT c.diary_no, c.conn_key, c.next_dt, c.roster_id, c.part, c.main_supp_flag, c.applicant_type, c.party_id, c.advocate_id, c.entry_source, :user_id, NOW(), :user_ip, NULL
                         FROM heardt h
                         INNER JOIN consent_through_email c ON h.diary_no = c.diary_no AND h.next_dt = c.next_dt
                         WHERE c.next_dt = :next_dt AND h.roster_id = :new_roster_id AND c.roster_id = :old_roster_id AND c.is_deleted IS NULL AND h.clno > 0";

            $db->query($sqlQuery, [
                'user_id' => session()->get('dcmis_user_idd'),
                'user_ip' => $_SERVER['REMOTE_ADDR'],
                'next_dt' => $next_dt,
                'new_roster_id' => $new_roster_id,
                'old_roster_id' => $old_roster_id
            ]);

            // Mark old cases as deleted
            $sqlUpdate = "UPDATE consent_through_email SET is_deleted = 1, deleted_by = :user_id, deleted_on = NOW(), deleted_ip = :user_ip 
                          WHERE next_dt = :next_dt AND roster_id = :old_roster_id AND is_deleted IS NULL";

            $db->query($sqlUpdate, [
                'user_id' => session()->get('dcmis_user_idd'),
                'user_ip' => $_SERVER['REMOTE_ADDR'],
                'next_dt' => $next_dt,
                'old_roster_id' => $old_roster_id
            ]);

            return $this->response->setJSON(['status' => 'success']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
    }
}
