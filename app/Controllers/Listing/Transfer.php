<?php

namespace App\Controllers\Listing;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Filing\AdvocateModel;
use App\Models\Listing\CaseInfoModel;
use App\Models\Listing\AllocationTp;
use App\Models\Common\Dropdown_list_model;
use App\Models\Listing\Subheading;
use App\Models\Listing\AdvanceAllocated;
use App\Models\Listing\CaseType;
use App\Models\Listing\Submaster;
use App\Models\Listing\ListingPurpose;
use App\Models\Listing\Roster;
use App\Models\Listing\CaseAdd;
use App\Models\Listing\Heardt;

use App\Models\CaseModel;
use App\Models\Listing\WorkingDaysModel;

class Transfer extends BaseController
{
    public $CaseInfoModel;
    public $Dropdown_list_model;
    public $Heardt;
    public $Subheading;
    public $CaseType;
    public $Submaster;
    public $ListingPurpose;
    public $AdvanceAllocated;
    public $Roster;
    public $AllocationTp;



    function __construct()
    {
        $this->CaseInfoModel = new CaseInfoModel();
        $this->Heardt = new Heardt();
        $this->Subheading = new Subheading();
        $this->CaseType = new CaseType();
        $this->Submaster = new Submaster();
        $this->ListingPurpose = new ListingPurpose();
        $this->AdvanceAllocated = new AdvanceAllocated();
        $this->Roster = new Roster();
        $this->AllocationTp = new AllocationTp();
    }

    public function index()
    { 
        $cur_ddt = date('Y-m-d', strtotime('+1 day'));
        $data = [
                'subheadings' =>  $this->Subheading->getActiveSubheadings(),
                'caseTypes' => $this->CaseType->getActiveCaseTypes(),
                'submasters' => $this->Submaster->getActiveSubmasters(),
                'purposes' => $this->ListingPurpose->getListingPurp(),
                'next_court_work_day' => $this->nextCourtWorkingDate($cur_ddt),
                'getKeywords' => $this->AdvanceAllocated->getKeywords(),
                'getDocs' => $this->AdvanceAllocated->getDocs(),
                'getActs' => $this->AdvanceAllocated->getActs(),

            ];
            $next_court_work_day = date("d-m-Y", strtotime(chksDate($cur_ddt)));    
            $mf = "M";
            $jud_count = "2";
            $board_type = "J";
            $data['roster_judges'] = get_roster_judges_t($mf, $next_court_work_day,$jud_count,$board_type);
            //$data['roster_judges'] = $this->Heardt->getRosterJudges($mf, $next_court_work_day, $board_type);
            
            return view('Listing/transfer/index', $data);
        
    }

    public function get_roster_judges_p()
    {
        $request = service('request');
         $list_dt = $request->getPost('list_dt');
        $mainhead = $request->getPost('mainhead');
        $sitting_judges = $request->getPost('sitting_judges');
        $bench = $request->getPost('bench');
       
        //$data['roster_judges'] = $this->Heardt->getRosterJudges($mainhead, $list_dt, $bench);
        $data['roster_judges'] = get_roster_judges_t($mainhead, $list_dt, $sitting_judges, $bench);
        return view('Listing/transfer/roster_judges_view', $data);
    }

    public function sent_to_pool()
    {
        $request = service('request');
        $pool_dt = $request->getPost('pool_dt');
        $pool_dt =  date('Y-m-d', strtotime($pool_dt));
        $chk_tr = $request->getPost('chk_tr');
        $chk_tr = rtrim($chk_tr, ',');
        $ucode = session()->get('login')['usercode'];
        $result = $this->Heardt->f_make_proposal($pool_dt, $chk_tr, $ucode);
        if ($result) {
            return '<table width="100%" align="center"><tr><td class="class_green">Successfully sent to pool!</td></tr></table>';
        } else {
            return '<table width="100%" align="center"><tr><td class="class_red">Failed to send to pool.</td></tr></table>';
        }
    }

    public function get_cl_print_partno()
    {
        $request = service('request');
        $mainhead = $request->getPost('mainhead');
        $list_dt = $request->getPost('list_dt');
        $roster_id = $request->getPost('jud_ros');
        $board_type = $request->getPost('board_type');

        //$mainhead = 'F';
        //$roster_id = "47534";
        //$list_dt='2023-10-11';
        $options = $this->Heardt->get_cl_print_partno($mainhead, $list_dt, $roster_id, $board_type);
        return $this->response->setJSON($options); 
    }

    public function get_ros_to_tans_p()
    {
        $request = service('request');
        $list_dt = $request->getPost('list_dt');
        $mainhead = $request->getPost('mainhead');
        $sitting_judges = $request->getPost('sitting_judges');
        $bench = $request->getPost('bench');
        //$data['judges'] = $this->Roster->getJudgeRosterForTrans($mainhead, $list_dt, $sitting_judges, $bench);
        $data['judges'] = get_judge_rost_for_trans($mainhead, $list_dt, $sitting_judges, $bench);
        $data['cldt'] = $list_dt;
        $data['p1'] = $mainhead;

        return view('Listing/transfer/judge_roster_view', $data);
    }

    public function get_records()
    {
        $request = service('request');
        $postData = $request->getPost();
        $records = $this->AllocationTp->getRecords($postData);
        return view('Listing/transfer/get_records', $records);
    }

    private function nextCourtWorkingDate($current_date)
    {
        return date("Y-m-d", strtotime($current_date . ' +2 days'));
    }
}