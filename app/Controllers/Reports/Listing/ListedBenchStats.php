<?php

namespace App\Controllers\Reports\Listing;
use App\Controllers\BaseController;
use App\Models\Reports\Listing\ReportModel;

class ListedBenchStats extends BaseController
{

    public $ReportModel;

    function __construct()
    {
        $this->ReportModel= new ReportModel();
         
    }

    /**
     * Filter page for fresh cases never listed report
     *
     * @return void
     */
    public function listed_bench_matters()
    {
        
        $data['categories'] = $this->ReportModel->get_categories();
        if($this->request->getMethod() === 'post'){
            $data['from_dt'] = $from_date = $this->request->getPost('from_dt');
            $data['to_dt'] = $to_date = $this->request->getPost('to_dt');
            $data['mainhead'] =    $this->request->getPost('mainhead');
            $data['board_type'] =    $this->request->getPost('board_type');
            $data['category'] =    $this->request->getPost('category');
            $data['benches'] =    $this->request->getPost('benches');
            $data['total_rows'] = $this->ReportModel->listed_bench_matters($data);
        }    

        return  view('Reports/listing/listed_bench_matters', $data);
    }

    public function listed_bench_matters_action()
    {
        $data['from_dt'] = $from_date = $this->request->getPost('from_dt');
        $data['to_dt'] = $to_date = $this->request->getPost('to_dt');
        $data['mainhead'] =    $this->request->getPost('mainhead');
        $data['board_type'] =    $this->request->getPost('board_type');
        $data['category'] =    $this->request->getPost('category');
        $data['benches'] =    $this->request->getPost('benches');
        $data['tbl_sub'] =    $this->request->getPost('tbl_sub');
        $data['getResults'] = $this->ReportModel->listed_bench_matters_action($data);
    }
}
