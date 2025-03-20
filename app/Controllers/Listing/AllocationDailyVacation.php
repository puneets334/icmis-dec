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
use App\Models\Listing\WorkingDaysModel;

use App\Models\CaseModel;

class AllocationDailyVacation extends BaseController
{


    public $model;
    public $diary_no;
    public $CaseInfoModel;
    public $Subheading;
    public $CaseType;
    public $Submaster;
    public $ListingPurpose;
    public $AdvanceAllocated;
    public $Dropdown_list_model;
    public $WorkingDaysModel;



    function __construct()
    {
        $this->CaseInfoModel = new CaseInfoModel();

        $this->Subheading = new Subheading();
        $this->CaseType = new CaseType();
        $this->Submaster = new Submaster();
        $this->ListingPurpose = new ListingPurpose();
        $this->AdvanceAllocated = new AdvanceAllocated();
        $this->WorkingDaysModel = new WorkingDaysModel();
        ini_set('memory_limit','4024M');
    }

    public function a_r_vacation()
    {
        // $Subheading = new Subheading();
        // $CaseType = new CaseType();
        // $Submaster = new Submaster();
        // $ListingPurpose = new ListingPurpose();
        // $AdvanceAllocated = new AdvanceAllocated();
        $cur_ddt = date('Y-m-d', strtotime('+1 day'));

        $data = [
            'subheadings' =>  $this->Subheading->getActiveSubheadings(),
            'caseTypes' => $this->CaseType->getActiveCaseTypes(),
            'submasters' => $this->Submaster->getActiveSubmasters(),
            'purposes' => $this->ListingPurpose->getActivePurposes(),
            'next_court_work_day' => $this->nextCourtWorkingDate($cur_ddt),
            'getKeywords' => $this->AdvanceAllocated->getKeywords(),
            'getDocs' => $this->AdvanceAllocated->getDocs(),
            'getActs' => $this->AdvanceAllocated->getActs(),

        ];

        return view('Listing/allocation_daily_vacation/a_r_vacation', $data);
    }

    private function nextCourtWorkingDate($current_date)
    {

        return date("Y-m-d", strtotime($current_date . ' +2 days'));
    }
   

    public function get_vacation()
    {
        
        $request = service('request');
        $list_dt = $request->getPost('list_dt');
        $mainhead = $request->getPost('mainhead');
        $partno = $request->getPost('partno');
        $chked_jud_sel = $request->getPost('chked_jud_sel');
        $listing_purpose = $request->getPost('listing_purpose');
        $main_supp = $request->getPost('main_supp');
       
        $q_next_dt = date("Y-m-d", strtotime($list_dt));     
        
        $workingDays = $this->WorkingDaysModel->getWorkingDays($q_next_dt);
        
        if (empty($workingDays)) {
            echo "Please enter a vacation date...";
            return;
        }

        // Fetch hearing records
        $records = $this->WorkingDaysModel->getHearingRecords($q_next_dt, $mainhead, $listing_purpose);
        
        if (!empty($records)) {
            return view('Listing/allocation_daily_vacation/get_vacation', ['records' => $records, 'mainhead' => $mainhead]);
        } else {
            echo "No records found.";
        }

        }

        public function response_get_vacation()
        {    
            $list_dt = $this->request->getPost('list_dt');
            $partno = $this->request->getPost('partno');
            $main_supp = $this->request->getPost('main_supp');
            $mainhead = $this->request->getPost('mainhead');
            $chk_dno = $this->request->getPost('chk_dno');
            $chk_jud_sel = $this->request->getPost('chked_jud_sel');
        
            //$ucode = session()->get('filing_details');
            $ucode = session()->get('login')['usercode'];
            $q_next_dt = date("Y-m-d", strtotime($list_dt));
            $str_dno = explode("_", $chk_dno);
            
            //$str_jud = explode("|", $chk_jud_sel);
            $chked_jud = rtrim($chk_jud_sel, "JG");
            $explode_rs = explode("JG", $chked_jud);
            $str_jud = explode("|", $explode_rs[0]);
            
            $q_judges = $str_jud[0];
            $q_roster_id = $str_jud[1];
            $total_case_listed = 0;
            $output = '';

            for ($i = 0; $i < (count($str_dno) - 1); $i++) {
                $dno_cat = explode("@", $str_dno[$i]);
                $q_diary_no = $dno_cat[0];
                $submaster_id = $dno_cat[1];
                $chk_jud_id = $q_judges;
                $dairy_with_conn_k = f_cl_conn_key($q_diary_no);
                if (empty($dairy_with_conn_k)) {
                    $dairy_with_conn_k = $q_diary_no;
                }

                $ntl_judge_verify = f_cl_ntl_judge($dairy_with_conn_k, $chk_jud_id);
                $ntl_judge_dept_verify = f_cl_ntl_jud_dept($dairy_with_conn_k, $chk_jud_id);
                $not_before_verify = f_cl_not_before($dairy_with_conn_k, $chk_jud_id);

                if ($ntl_judge_verify == 1) {
                    $output .= "<br/>" . $q_diary_no . " Not to go AOR Before " . f_get_judge_names_inshort($chk_jud_id);
                } else if ($ntl_judge_dept_verify == 1) {
                    $output .= "<br/>" . $q_diary_no . " Not to go Department Before " . f_get_judge_names_inshort($chk_jud_id);
                } else if ($not_before_verify == 1) {
                    $output .= "<br/>" . $q_diary_no . " Not / Before " . f_get_judge_names_inshort($chk_jud_id);
                } else {
                    $this->WorkingDaysModel->q_from_heardt_to_last_heardt($q_diary_no);
                    $total_case_listed += f_heardt_cl_update($q_diary_no, $q_next_dt, $partno, '123', $q_roster_id, $q_judges, $ucode, 23, $main_supp, $mainhead, $submaster_id);
                }
            }

            if ($total_case_listed > 0) {
                $output .= "<div class='class_green align_center'>Success. Total Listed " . $total_case_listed."</div>";
            } else {
                $output .= "<br><div class='class_red align_center'>Not Listed</div>";
            }

            return $this->response->setBody($output);
        }
    }