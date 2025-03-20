<?php

namespace App\Controllers\Listing;

use App\Controllers\BaseController;
use CodeIgniter\Controller;

use App\Models\Listing\Causelist;
use App\Models\Listing\Judge;


use App\Models\CaseModel;

class AdvanceListReport extends BaseController
{


    public $model;
    public $diary_no;
    public $Causelist;
    public $Dropdown_list_model;
    public $Judge;



    function __construct()
    {
        $this->Causelist = new Causelist();
        $this->Judge = new Judge();
    }

    public function dateWise()
    {
        $request = service('request');
        $data['app_name'] = 'Advance List';


        return view('Listing/AdvanceListReport/date_wise');
    }

    public function dateWiseRes()
    {
        $request = service('request');
        $clDate = $request->getPost('clDate');
        if (!empty($clDate))
        {
            $clDate = date('Y-m-d', strtotime($clDate));
            $data['date_result'] = $this->Causelist->dateWise($clDate);
            $data['dateDisplay'] = date('d-m-Y', strtotime($clDate));
            return view('Listing/AdvanceListReport/date_wise_res', $data);
        }
        else {
            return 'No data found';
        }
    }



    public function judgeWise()
    {
        $request = service('request');
        $data['judges'] = $this->Causelist->getJudge();
        $clDate = $request->getPost('clDate');
        $judge = $request->getPost('judge');
        return view('Listing/AdvanceListReport/judgeWise', $data);
    }

    public function judgeWiseRes()
    {
        $request = service('request');
        $data['judges'] = $this->Judge->getJudge();
        $clDate = $request->getPost('clDate');
        $clDate_1 = date('Y-m-d', strtotime($clDate));
        $judge = $request->getPost('judge');
        $data['date_result'] = $this->Causelist->judgeWise($clDate_1, $judge);
        $data['dateDisplay'] = $request->getPost('clDate');
        return view('Listing/AdvanceListReport/judge_wise_res', $data);
    }


    public function queryBuilder()
    {
        $request = service('request');
        $data['app_name'] = 'Query Builder';
        return view('Listing/query_builder/pending');
    }


    public function get_result()
    {
        $data['_POST'] = $_POST;
        return view('Listing/query_builder/get_result', $data);
    }

    public function cl()
    {
        $data['POST'] = $_REQUEST;
        // pr($data['_POST']);
        return view('Listing/query_builder/cl', $data);
        die;
    }
}
