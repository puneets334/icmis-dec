<?php

namespace App\Controllers\Reports\Listing;
use App\Controllers\BaseController;
use App\Models\Reports\Listing\ReportModel;

class ChamberJudge extends BaseController
{

    public $ReportModel;

    function __construct()
    {
        $this->ReportModel= new ReportModel();
         
    }

    /**
     * To display chamber judge cases view page
     *
     * @return void
     */
    public function chamber_judge_cases()
    {
        $data['cases'] = $this->ReportModel->chamber_judge_cases();
        return  view('Reports/listing/chamber_judge_cases', $data);
    }

    
    

}
