<?php

namespace App\Controllers\Reports\PendencyReport;

use App\Controllers\BaseController;
use App\Models\Reports\PendencyReport\PendencyReportsModel;


class Reports extends BaseController
{

    public $pendencyReportsModel;

    public function __construct()
    {
        $this->pendencyReportsModel = new PendencyReportsModel();
    }

    public function index()
    {
        $data['dtd'] = date("d-m-Y");
        return view('Reports/pendencyReport/pendency_report_kk', $data);
    }

    public function pendency_report_process_kk()
    {
        $dt1 = $this->request->getPost('dt1');
        $dt2 = $this->request->getPost('dt2');

        $tdt1 = date('d-m-Y', strtotime($dt1));
        $tdt2 = date('d-m-Y', strtotime($dt2));
        $prev_date = date('Y-m-d', strtotime($dt1 . ' -1 day'));
        $next_date = date('Y-m-d', strtotime($dt2 . ' +1 day'));

        // Fetch data
        $prev_dt_pendency = $this->pendencyReportsModel->getPrevPendency($prev_date);
       
        $to_dt_pendency = $this->pendencyReportsModel->getToDatePendency($dt2);
        
        $inst = $this->pendencyReportsModel->getInstCases($dt1, $dt2);
     
        $dispose = $this->pendencyReportsModel->getDisposedCases($dt1, $dt2);
        
        $pendency = $this->pendencyReportsModel->getPendencyCases($dt2);
        
        // Prepare data for the view
        $data = [
            'prev_dt_pendency' => $prev_dt_pendency->prev_dt_pendency ?? 0,
            'to_dt_pendency' => $to_dt_pendency->to_dt_pendency ?? 0,
            'inst' => $inst->inst ?? 0,
            'dispose' => $dispose->dispose ?? 0,
            'pendency' => $pendency->pendency ?? 0,
            'tdt1' => $tdt1,
            'tdt2' => $tdt2,
        ];

        return view('Reports/pendencyReport/pendency_report_process_kk', $data);
    }

    public function pendency_bifurcation()
    {
        
     
        return view('Reports/pendencyReport/pendency_bifurcation');
    }

    public function pendency_bifurcation_process()
    {
        $data['dt1']=$_POST['dt1'];
        $data['tdt1']=date('d-m-Y', strtotime($data['dt1']));
        $data['for_date'] = date('Y-m-d', strtotime($data['dt1']));
        $data['model'] = $this->pendencyReportsModel;
        return view('Reports/pendencyReport/pendency_bifurcation_process',$data);
    }

    public function pendency_bifurcation_process_detail(){
       
        $data['for_date'] = date('Y-m-d', strtotime($_REQUEST['ason']));
        $data['ason_dmy'] = date('d-m-Y', strtotime($_REQUEST['ason']));
        $data['flag'] = $_REQUEST['flag'];
        $data['model'] = $this->pendencyReportsModel;
        return view('Reports/pendencyReport/pendency_bifurcation_process_detail',$data);

    }

 
}
