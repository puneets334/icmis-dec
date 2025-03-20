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
use App\Models\Listing\MiscAllocationModel;
use App\Models\Listing\Roster;
use App\Models\Listing\CaseAdd;
use App\Models\Listing\WorkingDaysModel;

use App\Models\CaseModel;

class AllocationMisc extends BaseController
{


    public $model;
    public $diary_no;
    public $CaseInfoModel;
    public $Dropdown_list_model;

    public $Subheading;
    public $CaseType;
    public $Submaster;
    public $ListingPurpose;
    public $AdvanceAllocated;
    public $MiscAllocationModel;
    public $request;
    




    function __construct()
    {
        $this->CaseInfoModel = new CaseInfoModel();
        $this->Subheading = new Subheading();
        $this->CaseType = new CaseType();
        $this->Submaster = new Submaster();
        $this->ListingPurpose = new ListingPurpose();
        $this->AdvanceAllocated = new AdvanceAllocated();
        $this->MiscAllocationModel = new MiscAllocationModel();
        $this->request = service('request');
    }
    public function a_m_b()
    {
         $cur_ddt = date('Y-m-d', strtotime('+1 day'));
         $session = session();

       
        // $usercode = $session->get('dcmis_usservice('request')er_idd');
        // $user_ip = $session->get('ipadd');

        $usercode = session()->get('login')['usercode'];
        $user_ip = session()->get('login')['ipadd'];

       
        $allowed_ips = [
            '10.40.186.23',
            '10.40.186.122',
            '10.40.186.126',
            '10.40.186.124',
            '10.40.186.125',
            '10.40.186.120',
            '10.40.186.158',
            '10.40.186.127',
            '10.40.186.121',
            '10.40.186.145',
            '10.40.186.159',
            '10.40.186.141',
            '10.40.186.118',
            '10.40.186.168',
            '10.40.186.128',
            '10.40.186.236',
            '10.40.186.62',
            '10.40.193.226',
            '10.40.186.19',
            '10.25.78.48'
        ];

      
        $isAllowed = in_array($user_ip, $allowed_ips) && in_array($usercode, [1, 49, 747, 744, 762]);

       
        $isOtpVerified = $session->get('is_otp_verified') === true;
        //pr($session->get('current_next_date'));

        $data = [
            'subheadings' =>  $this->Subheading->getActiveSubheadings(),
            'caseTypes' => $this->CaseType->getActiveCaseTypes(),
            'submasters' => $this->Submaster->getActiveSubmasters(),
            'getListPurposes' => $this->ListingPurpose->getListPurposes(),
            'next_court_work_day' => $this->nextCourtWorkingDate($cur_ddt),
            'getKeywords' => $this->AdvanceAllocated->getKeywords(),
            'getDocs' => $this->AdvanceAllocated->getDocs(),
            'getActs' => $this->AdvanceAllocated->getActs(),
            'isAllowed' => $isAllowed,
            'isOtpVerified' => $isOtpVerified,
            'usercode' => $usercode,
            'list_dt' => $session->get('current_next_date')

        ];

        return view('Listing/Misc/misc_a_m_b', $data);
    }
    private function nextCourtWorkingDate($current_date)
    {

        return date("Y-m-d", strtotime($current_date . ' +2 days'));
    }

    public function check_otp_verification(){
        $request = service('request');
        $data['usercode'] = session()->get('login')['usercode'];
        $data['list_dt'] = date('Y-m-d', strtotime($request->getPost('list_dt')));
        $data['mainhead'] = $request->getPost('mainhead');
        $data['bench'] = $request->getPost('bench');
        $data['main_supp'] = !empty($request->getPost('main_supp')) ? $request->getPost('main_supp') : 0;
        $data['from_function']= $request->getPost('from_function');
        $this->MiscAllocationModel->check_otp_verification($data);
    }

    public function get_allocation_judges_m_al_b(){
        $request = service('request');
        $list_dt = $request->getPost('list_dt');
        $mainhead = $request->getPost('mainhead');
        $bench = $request->getPost('bench');
        get_allocation_judge_m_alc_b($mainhead,$list_dt,$bench); 
    }

    public function generate_otp_sml_mail(){
        $request = service('request');
        $list_dt = date('Y-m-d', strtotime($request->getPost('list_dt')));
        $mainhead = $request->getPost('mainhead');
        $bench = $request->getPost('bench');
        $main_supp = !empty($request->getPost('main_supp')) ? $request->getPost('main_supp') : 0;
        $usercode = session()->get('login')['usercode'];
        $this->MiscAllocationModel->generate_otp_sml_mail($list_dt, $mainhead, $bench, $main_supp, $usercode);
    }

    public function verify_otp(){
        $request = service('request');
        $session = session();
        $listing_date = $session->get('current_next_date');
        $otpList = $request->getPost('otpList');
        $mainhead = $request->getPost('mainhead');
        $bench = $request->getPost('bench');
        $loggedInUser = session()->get('login')['usercode'];
        $main_supp = !empty($request->getPost('main_supp')) ? $request->getPost('main_supp') : 0;
        $this->MiscAllocationModel->verify_otp($listing_date, $otpList, $mainhead, $loggedInUser, $main_supp, $bench);
    }

    public function coram_q_b(){
        $request = service('request');
        $usercode = session()->get('login')['usercode'];
        $postData = $request->getPost();
        $this->MiscAllocationModel->coram_q_b($postData, $usercode);    
    }

    public function get_section_autoc(){
        $request = service('request');
        $section = $request->getGet('term');
        $actid = $request->getGet('act');
        $result = $this->MiscAllocationModel->get_section_autoc($section, $actid);    
        return $result;
    }

    public function get_listing_purps()
    {
        $request = service('request');
        $main_supp = $request->getPost('main_supp');
        $data['purposes'] = $this->ListingPurpose->getListingPurposes($main_supp);
        $data['main_supp'] = $main_supp;
        return view('Listing/allocation/listing_purposes', $data);
    }
    
}
