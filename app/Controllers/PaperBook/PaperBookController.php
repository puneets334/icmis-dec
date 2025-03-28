<?php

namespace App\Controllers\PaperBook;

use App\Controllers\BaseController;
use App\Models\PaperBook\PaperBookModel;
use App\Models\PaperBook\AdvanceReportModel;
use CodeIgniter\Encryption\Encryption;

class PaperBookController extends BaseController
{

    public $paperModal;
    protected $encryption;
    protected $advanceReport;

    function __construct()
    {
        $this->paperModal = new PaperBookModel();
        $this->advanceReport = new AdvanceReportModel();
        $this->encryption = \Config\Services::encryption();
    }

    // public function getCSRF()
    // {
    //     return $this->response->setJSON([
    //         'csrf_token' => csrf_hash()
    //     ]);
    // }


    public function daAllocation()
    {
        return view('PaperBook/da_allocation');
    }


    public function allocationReport()
    {
        $request = service('request');
        $pager = \Config\Services::pager();
        $perPage = 10;

        $currentPage = $request->getVar('page') ? $request->getVar('page') : 1;

        $allocatedUsers = $this->paperModal->getAllocatedUsers($perPage, ($currentPage - 1) * $perPage);
        $unallocatedDiaryMatters = null;
        $unallocatedRegisteredMatters = null;

        if (empty($allocatedUsers)) {
            $unallocatedDiaryMatters = $this->paperModal->getUnallocatedDiaryMatters();
            $unallocatedRegisteredMatters = $this->paperModal->getUnallocatedRegisteredMatters();
        } else {
            foreach ($allocatedUsers as &$user) {
                $user['cases'] = $this->paperModal->getUserCases($user['usercode']);
                $user['totalCases'] = $this->paperModal->getTotalCases($user['usercode']);
            }
        }

        $totalUsers = count($allocatedUsers);
        $pager = $pager->makeLinks($currentPage, $perPage, $totalUsers, 'default_full');

        $data = [
            'allocatedUsers' => $allocatedUsers,
            'unallocatedDiaryMatters' => $unallocatedDiaryMatters,
            'unallocatedRegisteredMatters' => $unallocatedRegisteredMatters,
            'pager' => $pager,
            'godownModel' => $this->paperModal,
        ];

        return view('PaperBook/da_allocation_view', $data);
    }

    public function viewUserInformation()
    {
        $request = service('request');
        $pager = \Config\Services::pager();
        $perPage = 10; // Items per page
        $currentPage = $request->getVar('page') ? $request->getVar('page') : 1;

        $param = $request->getVar('str');

        if (!empty($param)) {
            $parts = explode('/', $param);

            $caseGroup = $parts[0];
            $year = $parts[1];
            $case_from = $parts[2] ?? '0'; // default to '0' if not provided
            $case_to = $parts[3] ?? '0'; // default to '0' if not provided

            if (is_numeric($caseGroup)) {
                $rw_ctype =  is_data_from_table('master.casetype', "casecode=$caseGroup", "casename", "");           
                $data['des']=$rw_ctype['casename'];
            }else{
                $data['des']= $caseGroup;
            }

            // Fetch the data based on the case parameters
            $resultData  = $this->paperModal->getUserInformation($perPage, ($currentPage - 1) * $perPage, [
                'caseGroup' => $caseGroup,
                'year' => $year,
                'case_from' => $case_from,
                'case_to' => $case_to,
                'currentPage' =>$currentPage
            ]); 
           // print_r($resultData); die;
            // Extract the results and total record count from the result data
            $data['results'] = $resultData['results'];

            $totalRecords = $resultData['totalRecords'];

            // Loop through each result and fetch the tentativeDA value for each record
            foreach ($data['results'] as &$user) {
                $user['sectionName'] = $this->paperModal->getSectionName($user['dno']);
                $user['tentativeDA'] = $this->paperModal->getTentativeDA($user['dno']);
            }
            $data['currentPage'] = ($currentPage -1) * $perPage ;
            $data['pager'] = $pager->makeLinks($currentPage, $perPage, $totalRecords);
            $data['case_year'] = $year;
            return view('PaperBook/view_user_information', $data);
        } else {
            return redirect()->to('/error')->with('message', 'No parameter provided!');
        }
    }


    public function causeList()
    {

        $sql = $this->db->query("SELECT c.next_dt FROM heardt c WHERE mainhead = 'M' AND c.next_dt >= CURRENT_DATE AND (c.main_supp_flag = '1' OR c.main_supp_flag = '2') GROUP BY next_dt");
        //$dates = $this->paperModal->getNextDates();
        $dates = $sql->getResultArray();
        $data['dates'] = $dates;
        return view('PaperBook/cause_list_view', $data);
    }

    public function getCauseFinalReport()
    {
        $data['_REQUEST'] = $_REQUEST;
        $data['paperModal'] = $this->paperModal;
        return view('PaperBook/get_cause_final_report', $data);
    }

    public function draftList()
    {
        $dates = $this->paperModal->getNextDates();
        $data = [
            'dates' => $dates
        ];
        return view('PaperBook/draft_list_view', $data);
    }

    public function fixedDateMatter()
    {
        return view('PaperBook/fixed_date_matter_view');
    }

    public function getAdvanceReport()
    {
        $request = service('request');
        $date = $request->getVar('cl_date');
        $listType = $request->getVar('list_type');
        $includeReview = $request->getVar('ma');
    
        // Retrieve user code and type
        $ucode = session()->get('login')['usercode'];
        $userDetails = $this->advanceReport->getUserCode($ucode);
        $utype = $userDetails['usertype'] ?? null;
    
        // Define `$ct` based on user-specific conditions and `listType`
        $ct = null;
        $userCondition = null;
        
        if ($listType == 1) { // For 'Fresh Civil Matters' only
            if ($ucode != 1 && $utype != 14 && $ucode != 630) {
                // Get user-specific case types
                $ct = $this->advanceReport->getAllocatedCaseTypes($ucode);
                
                if (empty($ct)) {
                    return $this->response->setJSON(['error' => 'NO Fresh Matters']);
                }
                $userCondition = "and a.user_id='$ucode'";
            } else {
                // Default case type list
                $ct = ($includeReview == 1) 
                    ? '1,3,5,7,11,13,23,32,34,39,40,9,19,25' 
                    : '1,3,5,7,11,13,23,32,34,40,39';
            }
        }
        $data = $this->advanceReport->fetchServeStatus($date, $ct); 
        print_r($data); die;
        // Fetch the report data from the model
        // $data = $this->advanceReport->fetchAdvanceReport($date, $listType, $includeReview, $userCondition, $ct);
    
        // Return data as JSON response
        return $this->response->setJSON($data);
    }
    
}
