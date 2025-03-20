<?php

namespace App\Controllers\Reports\DepartmentWise;

use App\Controllers\BaseController;
use App\Models\Exchange\Matters_Listed;
use App\Models\Exchange\CauseListFileMovementModel;
use App\Models\Exchange\Transaction;
use App\Models\Exchange\Sql_Report;
use App\Models\Exchange\MovementOfDocumentModel;
use App\Models\Court\CourtMasterModel;
use App\Models\Reports\DefaultReports\DefaultReportsModel;
use App\Models\Reports\DepartmentWise\DepartmentWiseModel;

class DepartmentWiseController extends BaseController
{
    public $Matters_Listed;
    public $CauseListFileMovementModel;
    public $CourtMasterModel;
    public $Transaction;
    public $MovementOfDocumentModel;
    public $DefaultReportsModel;
    public $DepartmentWiseModel;

    function __construct()
    {   
        $this->Matters_Listed = new Matters_Listed();
        $this->CauseListFileMovementModel = new CauseListFileMovementModel();
        $this->CourtMasterModel = new CourtMasterModel();
        $this->Transaction = new Transaction();
        $this->MovementOfDocumentModel = new MovementOfDocumentModel();
        $this->DefaultReportsModel = new DefaultReportsModel();
        $this->DepartmentWiseModel = new DepartmentWiseModel();
    }

    public function departmentWiseRPT()
    {
        $result['main_department'] = $this->DepartmentWiseModel->getMainDepartments();
        return view('Reports/departmentWise/departmentWiseRPT', $result);
    }

    public function getSubdept1()
    {
        $result = $this->DepartmentWiseModel->get_subdept1();
        return $this->response->setJSON([
            'status' => true,
            'data' => $result,
        ]);
    }

    public function getDepartmentWiseRPT()
    {
        $result = $this->DepartmentWiseModel->get_department_wise_rpt();
        return $this->response->setJSON([
            'status' => true,
            'data' => $result,
        ]);
    }

    public function departmentRPTExcel()
    {
        $result = $this->DepartmentWiseModel->departmentRPTExcel();
        $filename = 'dept_rpt';
        if($_REQUEST['hd_for_sts'] == 'P')
        {
            $filename .='_pen_';
        }
        else if($_REQUEST['hd_for_sts'] == 'D')
        {
            $filename .='_dis_';
        }
        $filename .= $_REQUEST['hd_for_mdept'].$_REQUEST['hd_for_sdept'].'f'.$_REQUEST['hd_for_fdate'].'t'.$_REQUEST['hd_for_tdate'].date('dmY');

        // Set headers for file download (Excel)
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=$filename.xls");
        echo $result;
    }
}