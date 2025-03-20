<?php

namespace App\Controllers\Listing;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Listing\CaseType;
use App\Models\Listing\CaseAdd;
use App\Models\Listing\Judge;
use App\Models\Listing\Subheading;

use App\Models\Listing\AllocationTp;
use App\Models\Listing\ListingPurpose;
use App\Models\Listing\Submaster;


class QueryBuilder extends BaseController
{
    public $AllocationTp;
    public $diary_no;
    public $CaseType;
    public $CaseAdd;
    public $Judge;
    public $Subheading;
    public $ListingPurpose;
    public $Submaster;

    function __construct()
    {
        $this->AllocationTp = new AllocationTp();
        $this->CaseType = new CaseType();
        $this->CaseAdd = new CaseAdd();
        $this->Judge = new Judge();
        $this->Subheading = new Subheading();
        $this->ListingPurpose = new ListingPurpose();
        $this->Submaster = new Submaster();
    }

    public function pending()
    {

        $Judge = $this->Judge->getJudges(); 
        $getSubheading = $this->Subheading->getSubheading(); 
        $getListPurposes = $this->ListingPurpose->getListPurposes();
        $categories = $this->Submaster->getCategories();
        $caseTypes = $this->CaseType->getActiveCaseTypes(); 
        $getJudicialSections = $this->CaseAdd->getJudicialSections();  
        $getUsers = $this->CaseAdd->getUsers(); 
        $data = [];
        foreach ($categories as $row) {
            $subcategories = $this->Submaster->getSubcategories($row['subcode1']);
            $data[] = [
                'category' => $row,
                'subcategories' => $subcategories
            ];
        }
        return view('Listing/QueryBuilder/pending', [
            'Judge' => $Judge,
            'getSubheading' => $getSubheading,
            'getListPurposes' => $getListPurposes,
            'categories' => $data,
            'caseTypes' => $caseTypes,
            'getJudicialSections' => $getJudicialSections,
            'getUsers' => $getUsers
        ]);
    }


    public function get_result_old()
    {
        $Judge = $this->Judge->getJudges();
        return view(
            'Listing/QueryBuilder/get_result',
            [
                'Judge' => $Judge,
            ]
        );
    }


    public function getResult()
    {

        $request = service('request');
        $postData = $request->getPost();
        //Old Logic To set post data
        // $from_list_date = $request->getPost('from_list_date');
        // $to_list_date = $request->getPost('to_list_date');
        // $from_diary_date = $request->getPost('from_diary_date');
        // $to_diary_date = $request->getPost('to_diary_date');
        // $mainhead = $request->getPost('mainhead');
        // $board_type = $request->getPost('board_type');
        // $connected = $request->getPost('connected');
        // $status = $request->getPost('status');
        // $judge = $request->getPost('judge');
        // $category = $request->getPost('category');
        // $case_type = $request->getPost('case_type');
        // $section = $request->getPost('section');
        // $da = $request->getPost('da');
        // $subhead = $request->getPost('subhead');
        // $lp = $request->getPost('lp');
        // $coram_by_cji = $request->getPost('coram_by_cji');
        // $conditional_matter = $request->getPost('conditional_matter');
        // $sensitive = $request->getPost('sensitive');
        // $cav_matter = $request->getPost('cav_matter');
        // $list_after_vacation = $request->getPost('list_after_vacation');
        // $part_heard = $request->getPost('part_heard');
        // $flag = $request->getPost('flag');
        $data['report'] = $this->CaseAdd->getReportData($postData);
        return view('Listing/QueryBuilder/get_result', $data);
    }



    public function getResultSec()
    {
        $request = service('request');
        $filters = $request->getGet();

        if (empty($filters['add_columns'])) {
            $response = [
                'status' => false,
                'message' => '[add_columns] not found. Please select Columns field.'
            ];
            return response()->json($response);
        }
        
        $number_of_rows = !empty($filters['number_of_rows']) ? (int)$filters['number_of_rows'] : 10;

        $add_columns = $filters['add_columns'];
        $data['add_columns'] =  $add_columns;
        $sort_by2 = !empty($filters['sort_by2']) ? $filters['sort_by2'] : [];
        $data['report'] = $this->CaseAdd->getReportDataUsingCol($filters, $add_columns, $number_of_rows, $sort_by2);
        // pr($data);
        $data['input_title'] = 'Your Report Title';
        return view('Listing/QueryBuilder/report_view', $data);
    }

    public function generateReport()
    {
        $request = service('request');
        $diaryNos = $request->getPost('diaryNos');
        $inputTitle = $request->getPost('input_title');
        $listHeading = $request->getPost('listHeading');
        if (is_string($diaryNos)) {
            $diaryNos = array_filter(explode(',', $diaryNos));
        } elseif (!is_array($diaryNos)) {
            $diaryNos = [];
        }
        $diaryNumbersString = implode(',', $diaryNos);

        $data['reportData'] = $this->CaseAdd->getReportDataUsingDiaryNo($diaryNumbersString);
        $data['inputTitle'] = $inputTitle;
        $data['listHeading'] = $listHeading;
        return view('Listing/QueryBuilder/report_output', $data);
    }
}
