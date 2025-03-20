<?php

namespace App\Controllers\Reports\DefaultReports;

use App\Controllers\BaseController;
use App\Models\Exchange\Matters_Listed;
use App\Models\Exchange\CauseListFileMovementModel;
use App\Models\Exchange\Transaction;
use App\Models\Exchange\Sql_Report;
use App\Models\Exchange\MovementOfDocumentModel;
use App\Models\Court\CourtMasterModel;
use App\Models\Reports\DefaultReports\DefaultReportsModel;

class DefaultReportsController extends BaseController
{
    public $Matters_Listed;
    public $CauseListFileMovementModel;
    public $CourtMasterModel;
    public $Transaction;
    public $MovementOfDocumentModel;
    public $DefaultReportsModel;

    function __construct()
    {   
        $this->Matters_Listed = new Matters_Listed();
        $this->CauseListFileMovementModel = new CauseListFileMovementModel();
        $this->CourtMasterModel = new CourtMasterModel();
        $this->Transaction = new Transaction();
        $this->MovementOfDocumentModel = new MovementOfDocumentModel();
        $this->DefaultReportsModel = new DefaultReportsModel();
    }

    public function casesNotVerifed()
    {
        $result['result_bifurcation'] = $this->DefaultReportsModel->cases_not_verified();
        return view('Reports/defaultReports/casesNotVerifed', $result);
    }

    public function casesNotVerifiedDetails()
    {
        $data = $this->DefaultReportsModel->cases_not_verified_details();
        return view('Reports/defaultReports/casesNotVerifiedDetails', $data);
    }
}