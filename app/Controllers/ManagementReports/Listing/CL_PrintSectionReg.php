<?php

namespace App\Controllers\ManagementReports\Listing;

use App\Controllers\BaseController;
use App\Models\ManagementReport\ClPrintOfficeReportModel;
use App\Models\Listing\PrintAdvanceModel;

class CL_PrintSectionReg extends BaseController
{
    public $clPrintReport;
    protected $PrintAdvanceModel;

    function __construct()
    {
        set_time_limit(10000000000);
        ini_set('memory_limit', '-1');
        $this->clPrintReport = new ClPrintOfficeReportModel();
        $this->PrintAdvanceModel = new PrintAdvanceModel();
    }

    public function index(){
        $data['f_listorder'] = $this->PrintAdvanceModel->getFListOrder();
        return view('ManagementReport/Listing/cl_print_section_reg', $data);
    }
    

    public function get_cause_list_sectionreg(){
        $this->db = \Config\Database::connect();
        $data['db'] = $this->db;
        return view('ManagementReport/Listing/get_cause_list_sectionreg', $data);
    }

   

}